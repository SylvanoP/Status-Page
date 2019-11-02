<?php

ob_start();
session_start();

$date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
$datetime = $date->format('Y-m-d H:i:s');

/*
 * config
 */
include 'app/controller/config.php';
include_once 'app/functions/autoload.php';

/*
 * page manager
 */
$resources = 'resources/';
$sites = $resources.'sites/';

$page = $helper->protect($_GET['page']);
if(isset($_GET['page'])) {
    switch ($page) {

        default: include($sites."404.php");  break;

        //mainpage
        case "index": include($sites."index.php");  break;

    }
    include 'resources/additional/footer.php';
} else {
    die('please enable .htaccess on your server');
}