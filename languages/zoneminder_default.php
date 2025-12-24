<?php


$dictionary=array(

	'ZONEMINDER_COLUMN_ID'=>'Id',
    'ZONEMINDER_COLUMN_NAME'=>'Name',
    'ZONEMINDER_COLUMN_FUNCTION'=>'Function',
    'ZONEMINDER_COLUMN_ENABLED'=>'Enabled',
    'ZONEMINDER_COLUMN_ZONE_COUNT'=>'Zones',
    'ZONEMINDER_COLUMN_SOURCE'=>'Source',
    'ZONEMINDER_COLUMN_HOUR'=>'Hour',
    'ZONEMINDER_COLUMN_DAY'=>'Day',
    'ZONEMINDER_COLUMN_WEEK'=>'Week',
    'ZONEMINDER_COLUMN_MONTH'=>'Month',
    'ZONEMINDER_DISKSPACE_B'=>'B',
    'ZONEMINDER_DISKSPACE_KB'=>'KB',
    'ZONEMINDER_DISKSPACE_MB'=>'MB',
    'ZONEMINDER_DISKSPACE_GB'=>'GB',
    'ZONEMINDER_DISKSPACE_TB'=>'TB',
    'ZONEMINDER_BANDWIDTH_KBS'=>'kB/s',
    'ZONEMINDER_COLUMN_MONITOR'=>'Monitor',
    'ZONEMINDER_COLUMN_START_TIME'=>'Start Time',
    'ZONEMINDER_COLUMN_END_TIME'=>'End Time',
    'ZONEMINDER_COLUMN_DURATION'=>'Duration',
    'ZONEMINDER_COLUMN_DISK_SPACE'=>'Disk Space',
    'ZONEMINDER_COLUMN_THUMBNAIL'=>'Thumbnail',
    'ZONEMINDER_PAGE_PREV'=>'Prev',
    'ZONEMINDER_PAGE_NEXT'=>'Next',
    'ZONEMINDER_NO_EVENTS_TEXT'=>'No events found',
    'ZONEMINDER_TOTAL'=>'Total',

    'ZONEMINDER_SETTINGS_SERVER_ADDRESS'=>'Server',
    'ZONEMINDER_SETTINGS_USERNAME'=>'Username',
    'ZONEMINDER_SETTINGS_PASSWORD'=>'Password',
    'ZONEMINDER_SETTINGS_TEXT'=>'Settings',

    'ZONEMINDER_ABOUT'=>'About',
    'ZONEMINDER_HELP'=>'Help',
    'ZONEMINDER_CLOSE'=>'Close',
    'ZONEMINDER_ABOUT_TEXT'=>'Video surveillance system support module <b>Zoneminder</b>.<br><br>
               Discussion of the module on the <a href="https://mjdm.ru/forum/viewtopic.php" target="_blank">forum</a>.<br>
               Project on <a href="https://github.com/layet/majordomo-zoneminder" target="_blank">Github</a>.<br>
               Project on <a href="https://connect.smartliving.ru/tasks/922.html" target="_blank">Connect</a>.<br>',
);

foreach ($dictionary as $k=>$v) {
	if (!defined('LANG_'.$k)) {
		define('LANG_'.$k, $v);
	}
}
