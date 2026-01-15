<?php
/**
 * Zoneminder
 * @package project
 * @author Layet <layet@yandex.ru>
 * @copyright http://majordomo.smartliving.ru/ (c)
 * @version 1.0 (layet, 12:07:48 [Dec 22, 2025])
 */
//

/**
 *
 * Функция для преобразования StdClass к массиву для шаблонизатора
 *
 */

function convertStdClassToArray($source): array
{
    return json_decode(json_encode($source), true);
}

class zoneminder extends module {
    /**
     * zoneminder
     *
     * Module class constructor
     *
     * @access private
     */
    function __construct() {
        $this->name="zoneminder";
        $this->title="Zoneminder";
        $this->module_category="<#LANG_SECTION_APPLICATIONS#>";
        $this->checkInstalled();

        $this->getConfig();
    }
    /**
     * saveParams
     *
     * Saving module parameters
     *
     * @access public
     */
    function saveParams($data=1) {
        $p=array();
        if (IsSet($this->id)) {
            $p["id"]=$this->id;
        }
        if (IsSet($this->view_mode)) {
            $p["view_mode"]=$this->view_mode;
        }
        if (isset($mode)) {
            $p["mode"]=$this->mode;
        }
        if (isset($monitor)) {
            $p["monitor"]=$this->monitor;
        }
        if (isset($monitorname)) {
            $p["monitorname"]=$this->monitorname;
        }
        if (isset($eventid)) {
            $p["eventid"]=$this->eventid;
        }
        if (isset($page)) {
            $p["page"]=$this->page;
        }
        /*if (isset($interval)) {
            $p["interval"]=$this->interval;
        }*/
        return parent::saveParams($p);
    }
    /**
     * getParams
     *
     * Getting module parameters from query string
     *
     * @access public
     */
    function getParams() {
        global $id;
        global $mode;
        global $view_mode;
        global $monitor;
        global $monitorname;
        global $eventid;
        global $page;
        global $interval;
        global $showeventlist;
        global $scale;
        if (isset($id)) {
            $this->id=$id;
        }
        if (isset($mode)) {
            $this->mode=$mode;
        }
        if (isset($view_mode)) {
            $this->view_mode=$view_mode;
        }
        if (isset($monitor)) {
            $this->monitor=$monitor;
        }
        if (isset($monitorname)) {
            $this->monitorname=$monitorname;
        }
        if (isset($eventid)) {
            $this->eventid=$eventid;
        }
        if (isset($page)) {
            $this->page=$page;
        }
        if (isset($interval)) {
            $this->interval=$interval;
        }
        if (isset($showeventlist)) {
            $this->showeventlist=$showeventlist;
        }
        if (isset($scale)) {
            $this->scale=$scale;
        }
    }
    /**
     * Run
     *
     * Description
     *
     * @access public
     */
    function run() {
        global $session;
        $out=array();
        if ($this->action=='admin') {
            $this->admin($out);
        } else {
            $this->usual($out);
        }
        if (IsSet($this->owner->action)) {
            $out['PARENT_ACTION']=$this->owner->action;
        }
        if (IsSet($this->owner->name)) {
            $out['PARENT_NAME']=$this->owner->name;
        }
        $out['VIEW_MODE']=$this->view_mode;
        $out['MODE']=$this->mode;
        $out['ACTION']=$this->action;
        $this->data=$out;
        $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
        $this->result=$p->result;
    }

    /**
     * FrontEnd
     *
     * Module frontend
     *
     * @access public
     */
    function usual(&$out) {
        $monitor = $this->fetchMonitor($this->monitor);
        $out["monitor"] = $this->monitor;
        $out["name"] = $monitor->Monitor->Name;
        if (isset($this->scale)) $out["scale"] = $this->scale; else $out["scale"] = 100;
        if (isset($this->interval)) $out["interval"] = $this->interval; else $out["interval"] = 1000;
        if (isset($this->showeventlist)) {
            $events = $this->fetchEvents($this->monitor, 'day', 1);
            foreach ($events->events as $event) {
                $event->Event->Length = $this->secondsToHMS((int)$event->Event->Length);
                $out['EVENTS'][] = convertStdClassToArray($event->Event);
            }
        }
        //$out["DEBUG"] = print_r($out['EVENTS'], true);
    }

    /**
     * BackEnd
     *
     * Module backend
     *
     * @access public
     */
    function admin(&$out) {
        $this->getConfig();

        $out['SERVER_PROTO']  =  $this->config['SERVER_PROTO'];
        $out['SERVER_ADDRESS']  =  $this->config['SERVER_ADDRESS'];
        $out['USER_NAME']  =  $this->config['USER_NAME'];
        $out['USER_PASS']  =  $this->config['USER_PASS'];

        if ($this->view_mode == 'update_settings') {
            $this->config['SERVER_PROTO'] = gr('server_proto');
            $this->config['SERVER_ADDRESS'] = gr('server_address');
            $this->config['USER_NAME'] = gr('user_name');
            $this->config['USER_PASS'] = gr('user_pass');

            $this->saveConfig();

            $this->redirect('?');
        }
        $out['TOTAL']['count'] = 0;
        $out['TOTAL']['bandwidth'] = 0;
        $out['TOTAL']['HourEvents'] = 0;
        $out['TOTAL']['HourEventDiskSpace'] = 0;
        $out['TOTAL']['DayEvents'] = 0;
        $out['TOTAL']['DayEventDiskSpace'] = 0;
        $out['TOTAL']['WeekEvents'] = 0;
        $out['TOTAL']['WeekEventDiskSpace'] = 0;
        $out['TOTAL']['MonthEvents'] = 0;
        $out['TOTAL']['MonthEventDiskSpace'] = 0;
        if ($this->view_mode == '') {
            $info = $this->fetchMonitors();
            foreach ($info as $monitor) {
                $id = $monitor->Monitor->Id;
                $url_path = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/api/monitors/daemonStatus/id:'.$id.'/daemon:zmc.json';
                $monitor->Monitor->monitor_color = json_decode(file_get_contents($url_path, false))->status == 1 ? '#33CA7F' : '#FE5F55';
                $monitor->Monitor->CaptureFPS = $monitor->Monitor_Status->CaptureFPS;
                $monitor->Monitor->CaptureBandwidth = sprintf("%03.2f ".LANG_ZONEMINDER_BANDWIDTH_KBS, $monitor->Monitor_Status->CaptureBandwidth / 1000);
                $pattern="/(\\d{1,3}.\\d{1,3}.\\d{1,3}.\\d{1,3})/";
                preg_match($pattern, $monitor->Monitor->Path, $matches);
                $monitor->Monitor->IP = $matches[1];
                $monitor->Monitor->HourEvents = $monitor->Event_Summary->HourEvents;
                $monitor->Monitor->DayEvents = $monitor->Event_Summary->DayEvents;
                $monitor->Monitor->WeekEvents = $monitor->Event_Summary->WeekEvents;
                $monitor->Monitor->MonthEvents = $monitor->Event_Summary->MonthEvents;
                $monitor->Monitor->HourEventDiskSpace = $this->formatBytes($monitor->Event_Summary->HourEventDiskSpace);
                $monitor->Monitor->DayEventDiskSpace = $this->formatBytes($monitor->Event_Summary->DayEventDiskSpace);
                $monitor->Monitor->WeekEventDiskSpace = $this->formatBytes($monitor->Event_Summary->WeekEventDiskSpace);
                $monitor->Monitor->MonthEventDiskSpace = $this->formatBytes($monitor->Event_Summary->MonthEventDiskSpace);
                $out['TOTAL']['count'] = $out['TOTAL']['count'] + 1;
                $out['TOTAL']['bandwidth'] = $out['TOTAL']['bandwidth'] + $monitor->Monitor_Status->CaptureBandwidth/1000;
                $out['TOTAL']['HourEvents'] = $out['TOTAL']['HourEvents'] + $monitor->Event_Summary->HourEvents;
                $out['TOTAL']['HourEventDiskSpace'] = $out['TOTAL']['HourEventDiskSpace'] +  $monitor->Event_Summary->HourEventDiskSpace;
                $out['TOTAL']['DayEvents'] = $out['TOTAL']['DayEvents'] + $monitor->Event_Summary->DayEvents;
                $out['TOTAL']['DayEventDiskSpace'] = $out['TOTAL']['DayEventDiskSpace'] +  $monitor->Event_Summary->DayEventDiskSpace;
                $out['TOTAL']['WeekEvents'] = $out['TOTAL']['WeekEvents'] + $monitor->Event_Summary->WeekEvents;
                $out['TOTAL']['WeekEventDiskSpace'] = $out['TOTAL']['WeekEventDiskSpace'] +  $monitor->Event_Summary->WeekEventDiskSpace;
                $out['TOTAL']['MonthEvents'] = $out['TOTAL']['MonthEvents'] + $monitor->Event_Summary->MonthEvents;
                $out['TOTAL']['MonthEventDiskSpace'] = $out['TOTAL']['MonthEventDiskSpace'] +  $monitor->Event_Summary->MonthEventDiskSpace;
                $out['MONITORS'][] = convertStdClassToArray($monitor->Monitor);
            }
            $out['TOTAL']['bandwidth'] = sprintf("%03.2f ".LANG_ZONEMINDER_BANDWIDTH_KBS, $out['TOTAL']['bandwidth']);
            $out['TOTAL']['HourEventDiskSpace'] = $this->formatBytes($out['TOTAL']['HourEventDiskSpace']);
            $out['TOTAL']['DayEventDiskSpace'] = $this->formatBytes($out['TOTAL']['DayEventDiskSpace']);
            $out['TOTAL']['WeekEventDiskSpace'] = $this->formatBytes($out['TOTAL']['WeekEventDiskSpace']);
            $out['TOTAL']['MonthEventDiskSpace'] = $this->formatBytes($out['TOTAL']['MonthEventDiskSpace']);
        }

        if ($this->view_mode == 'events') {
            $events = $this->fetchEvents($this->monitor, $this->mode, $this->page);
            foreach ($events->events as $event) {
                $event->Event->monitorname = $this->monitorname;
                $event->Event->DiskSpace = $this->formatBytes($event->Event->DiskSpace);
                $event->Event->Length = $this->secondsToHMS((int)$event->Event->Length);
                $out['EVENTS'][] = convertStdClassToArray($event->Event);
            }
            if ($events->pagination->pageCount > 1) {
                require(DIR_MODULES.$this->name.'/Paginator.php');
                $page=gr('page','int');
                if (!$page) $page=1;
                $urlPattern='?view_mode=events&monitor='.$this->monitor.'&mode='.$this->mode.'&monitorname='.$this->monitorname.'&page=(:num)';
                $paginator = new JasonGrimes\Paginator($events->pagination->count, $events->pagination->limit, $page, $urlPattern);
                $paginator->setNextText(LANG_ZONEMINDER_PAGE_NEXT);
                $paginator->setPreviousText(LANG_ZONEMINDER_PAGE_PREV);
                $out['Paginator'] = $paginator->toHtml();
            }
        }

        if ($this->view_mode == 'event') {
            $out['EVENT'] = convertStdClassToArray($this->fetchEvent($this->eventid));
        }

        if ($this->view_mode == 'help') {
            $out['lang'] = SETTINGS_SITE_LANGUAGE;
        }

        if ($this->view_mode == 'test') {
            $this->test();
            //$this->redirect('?');
        }

    }

    /**
     *
     * Функция для форматирования байтов в удобочитаемый формат
     *
     */
    function formatBytes($bytes, $precision = 2): string {
        $units = array(LANG_ZONEMINDER_DISKSPACE_B, LANG_ZONEMINDER_DISKSPACE_KB, LANG_ZONEMINDER_DISKSPACE_MB, LANG_ZONEMINDER_DISKSPACE_GB, LANG_ZONEMINDER_DISKSPACE_TB);
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     *
     * Функция для форматирования секунд в Ч:М:С
     *
     */
    function secondsToHMS(int $seconds): string {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $remainingSeconds);
    }

    /**
     *
     * Тестовая функция
     *
     */
    function test()
    {
        echo "<pre>".print_r(SETTINGS_SITE_LANGUAGE, true)."</pre>";
    }

    /**
     *
     * Функция проксирования картинки камеры
     *
     */
    function image($monitor, $scale)
    {
        if (isset($scale) && isset($monitor)) {
            $file = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/cgi-bin/nph-zms?mode=single&scale='.$scale.'&monitor='.$monitor;
            header('Content-Type: image/jpeg');
            readfile($file);
        }
    }

    /**
     *
     * Функция проксирования картинки события
     *
     */
    function thumbnail($eventid, $width, $height)
    {
        $file = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/index.php?eid='.$eventid.'&fid=snapshot&view=image&width='.$width.'&height='.$height;
        header('Content-Type: image/jpeg');
        readfile($file);
    }

    /**
     *
     * Функция проксирования MJPEG
     *
     */
    function videoMJPEG($eventid)
    {
        $file = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/cgi-bin/nph-zms?mode=jpeg&frame=1&scale=0&rate=100&maxfps=30&replay=none&source=event&event='.$eventid.'&rand='.rand(0,65535);
        header('Content-Type: multipart/x-mixed-replace;boundary=ZoneMinderFrame');
        readfile($file);
    }

    /**
     *
     * Функция проксирования MJPEG
     *
     */
    function videoMPEG($eventid)
    {
        $file = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/index.php?mode=mpeg&format=h264&eid='.$eventid.'&view=view_video';
        header('Content-Type: video/mp4');
        readfile($file);
    }

    /**
     *
     * Функция получения информации о камерах
     *
     */
    function fetchMonitors(): array
    {
        $url_path = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/api/monitors.json';

        $results = file_get_contents($url_path, false);
        if (!$results) return []; else return json_decode($results)->monitors;
    }

    /**
     *
     * Функция получения информации о камере по ID
     *
     */
    function fetchMonitor($id = 0): StdClass
    {
        $url_path = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/api/monitors/'.$id.'.json';
        $results = file_get_contents($url_path, false);

        return json_decode($results)->monitor;
    }

    /**
     *
     * Функция получения списка событий
     *
     */
    function fetchEvents($monitorId, $dateRange, $page = 1): StdClass
    {
        if (isset($monitorId) && isset($dateRange)) {
            switch ($dateRange) {
                case 'hour': $dateRange = '-1 hour'; break;
                case 'day': $dateRange = '-1 day'; break;
                case 'week': $dateRange = '-1 week'; break;
                case 'month': $dateRange = '-1 month'; break;
                default: $dateRange = '-1 hour';
            }

            $url_path = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/api/events/index/MonitorId:'.$monitorId.'/StartDateTime>=:'.date("Y-m-d H:i:s", strtotime($dateRange)).'/EndDateTime<=:'.date("Y-m-d H:i:s").'.json?sort=StartDateTime&direction=desc&page='.$page;
            $url_path = str_replace(' ', '%20', $url_path);
            $results = file_get_contents($url_path, false);
            return json_decode($results);
        }
    }

    /**
     *
     * Функция получения информации о событии
     *
     */
    function fetchEvent($eventId): StdClass
    {
        $url_path = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/api/events/'.$eventId.'.json';
        $results = file_get_contents($url_path, false);

        return json_decode($results)->event->Event;
    }

    /**
     * Install
     *
     * Module installation routine
     *
     * @access private
     */
    function install($data='') {
        parent::install();
    }
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgSnVsIDE4LCAyMDIzIHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
