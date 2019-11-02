<?php

$currPageName = explode('_',$currPage)[1];

if(strpos($currPage,'front_') !== false){
    include 'resources/additional/head.php';
    include 'resources/additional/navbar.php';
}