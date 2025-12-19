<?php


$dictionary=array(

	'ZONEMINDER_COLUMN_ID'=>'Id',
    'ZONEMINDER_COLUMN_NAME'=>'Name',
    'ZONEMINDER_COLUMN_FUNCTION'=>'Function',
    'ZONEMINDER_COLUMN_ENABLED'=>'Enabled',
    'ZONEMINDER_COLUMN_ZONE_COUNT'=>'Zones',
    'ZONEMINDER_COLUMN_SOURCE'=>'Source',
);

foreach ($dictionary as $k=>$v) {
	if (!defined('LANG_'.$k)) {
		define('LANG_'.$k, $v);
	}
}
