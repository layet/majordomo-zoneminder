<?php
include_once("config.php");
include_once("lib/loader.php");
include_once("load_settings.php");
include_once(DIR_MODULES . "zoneminder/zoneminder.class.php");

$monitor = htmlspecialchars($_GET["id"]);
$scale = htmlspecialchars($_GET["scale"]);
$source = htmlspecialchars($_GET["source"]);

$zm = new zoneminder();
if ($monitor != '' && $scale != '') {
    $zm->image($monitor, $scale);
}

if ($source != '') {
    if ($source == 'thumbnail') {
        $eventid = htmlspecialchars($_GET["eid"]);
        if ($eventid == '') $eventid = 0;
        $width = htmlspecialchars($_GET["width"]);
        if ($width == '') $width = 48;
        $height= htmlspecialchars($_GET["height"]);
        if ($height == '') $height = 27;
        $zm->thumbnail($eventid, $width, $height);
    }

    if ($source == 'mjpeg') {
        $eventid = htmlspecialchars($_GET["eid"]);
        if ($eventid == '') $eventid = 0;
        $zm->videoMJPEG($eventid);
    }

    if ($source == 'mpeg') {
        $eventid = htmlspecialchars($_GET["eid"]);
        if ($eventid == '') $eventid = 0;
        $zm->videoMPEG($eventid);
    }

    if ($source == 'eventlist') {
        if ($monitor == '') $monitor = 1;

        echo json_encode($zm->fetchEvents($monitor, 'day')->events);
    }

    if ($source == 'monitor') {
        if ($monitor == '') $monitor = 1;

        echo json_encode($zm->fetchMonitor($monitor));
    }
}
?>