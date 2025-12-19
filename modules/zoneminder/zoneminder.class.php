<?php
/**
 * Query Library
 * @package project
 * @author Wizard <sergejey@gmail.com>
 * @copyright http://majordomo.smartliving.ru/ (c)
 * @version 0.1 (wizard, 12:07:48 [Jul 18, 2023])
 */
//
//
class zoneminder extends module {
    /**
     * querylib
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
        if (IsSet($this->edit_mode)) {
            $p["edit_mode"]=$this->edit_mode;
        }
        if (IsSet($this->tab)) {
            $p["tab"]=$this->tab;
        }
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
        global $edit_mode;
        global $tab;
        if (isset($id)) {
            $this->id=$id;
        }
        if (isset($mode)) {
            $this->mode=$mode;
        }
        if (isset($view_mode)) {
            $this->view_mode=$view_mode;
        }
        if (isset($edit_mode)) {
            $this->edit_mode=$edit_mode;
        }
        if (isset($tab)) {
            $this->tab=$tab;
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
        $out['EDIT_MODE']=$this->edit_mode;
        $out['MODE']=$this->mode;
        $out['ACTION']=$this->action;
        $this->data=$out;
        $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
        $this->result=$p->result;
    }
    /**
     * BackEnd
     *
     * Module backend
     *
     * @access public
     */
    function admin(&$out) {
        //global $session;
        //if ($this->owner->name=='panel') {
        //    $out['CONTROLPANEL']=1;
        //}
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

        if ($this->view_mode == '') {
            $res = SQLSelect("SELECT * FROM `zoneminder_monitors` ORDER BY `ID` asc");
            if ($res[0]['ID']) {

                $total=count($res);
                for($i=0;$i<$total;$i++) {
                    // some action for every record if required
                    //$tmp=explode(' ', $res[$i]['UPDATED']);
                    //$res[$i]['UPDATED']=fromDBDate($tmp[0])." ".$tmp[1];
                    $res[$i]['SERVER_PROTO'] = $out['SERVER_PROTO'];
                    $res[$i]['SERVER_ADDRESS'] = $out['SERVER_ADDRESS'];
                }
                $out['RESULT']=$res;
            }
        }

        if ($this->view_mode == 'refresh_monitors') {
            $this->refresh_monitors();
            //$this->redirect('?');
        }

        if ($this->view_mode == 'test') {
            $this->test();
            //$this->redirect('?');
        }

    }

    /**
     *
     * Тестовая функция
     *
     */
    function test()
    {
        echo "<pre>".print_r($this->config, true)."</pre>";
    }

    /**
     *
     * Обновить список камер
     *
     */
    function refresh_monitors()
    {
        $url_path = $this->config['SERVER_PROTO'].'://'.$this->config['SERVER_ADDRESS'].'/zm/api/monitors.json';
        $results = file_get_contents($url_path, false);

        $monitors = array();
        $pattern="/(\\d{1,3}.\\d{1,3}.\\d{1,3}.\\d{1,3})/";

        foreach (json_decode($results)->monitors as $result) {

            $monitors[$result->Monitor->Id] = SQLSelectOne("SELECT `ID`, `MONITOR_ID`, `NAME`, `FUNCTION`, `ENABLED`, `ZONE_COUNT`, `STATUS`, `CAPTURE_FPS`, `CAPTURE_BANDWIDTH`, `IP` FROM `zoneminder_monitors` WHERE `MONITOR_ID`=".$result->Monitor->Id);

            $monitors[$result->Monitor->Id]['MONITOR_ID'] = $result->Monitor->Id;
            $monitors[$result->Monitor->Id]['NAME'] = $result->Monitor->Name;
            $monitors[$result->Monitor->Id]['FUNCTION'] = $result->Monitor->Function;
            $monitors[$result->Monitor->Id]['ENABLED'] = $result->Monitor->Enabled;
            $monitors[$result->Monitor->Id]['ZONE_COUNT'] = $result->Monitor->ZoneCount;
            $monitors[$result->Monitor->Id]['STATUS'] = $result->Monitor_Status->Status;
            $monitors[$result->Monitor->Id]['CAPTURE_FPS'] = $result->Monitor_Status->CaptureFPS;
            $monitors[$result->Monitor->Id]['CAPTURE_BANDWIDTH'] = $result->Monitor_Status->CaptureBandwidth;
            preg_match($pattern, $result->Monitor->Path, $matches);
            $monitors[$result->Monitor->Id]['IP'] = $matches[1];
            $monitors[$result->Monitor->Id]['MODIFIED_ON'] = date("Y-m-d H:i:s");

            SQLInsertUpdate('zoneminder_monitors', $monitors[$result->Monitor->Id]);
        }
    }

    /**
     * FrontEnd
     *
     * Module frontend
     *
     * @access public
     */
    function usual(&$out) {
        //$this->admin($out);
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
