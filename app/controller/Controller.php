<?php

global $url;

abstract class Controller
{

    public function db()
    {

        include 'config.php';

        $db = new PDO('mysql:host=' . $db_host . ';charset=utf8;dbname=' . $db_name, $db_username, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    public function url()
    {
        include 'config.php';

        return $url;
    }

    public function cdnUrl()
    {
        include 'config.php';

        return $cdnUrl;
    }

    public function picUrl()
    {
        include 'config.php';

        return $picUrl;
    }

    public function siteName()
    {
        include 'config.php';

        return $siteName;
    }

    public function getDateTime()
    {
        $date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
        $datetime = $date->format('Y-m-d H:i:s');

        return $datetime;
    }

    public function setCookie($name, $variable, $time = '777600', $path = '/', $domain = null, $secure = 0)
    {
        setcookie($name, $variable,time()+$time, $path, $domain, $secure);
    }

}