<?php
$currPage = 'crone_Check Monitors';
include 'app/controller/PageController.php';

$error = null;

if(empty($_GET['key'])){
    $error = 'please enter a crone key';
}

if($_GET['key'] != $crone_key){
    $error = 'wrong crone key ';
}

if(empty($error)){

    $SQL = $db->prepare("SELECT * FROM `monitors` WHERE `state` != :state");
    $SQL->execute(array(":state" => 'disabled'));
    if ($SQL->rowCount() != 0) {
        while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {

            $response = $check->ping($row['addr']);
            if($response == 0){
                $update = $db->prepare("UPDATE `monitors` SET `state` = :state WHERE `id` = :id");
                $update->execute(array(":state" => 'online', ":id" => $row['id']));
            } elseif($response > 0){
                $update = $db->prepare("UPDATE `monitors` SET `state` = :state WHERE `id` = :id");
                $update->execute(array(":state" => 'offline', ":id" => $row['id']));
            } else {
                $update = $db->prepare("UPDATE `monitors` SET `state` = :state WHERE `id` = :id");
                $update->execute(array(":state" => 'disabled', ":id" => $row['id']));
            }

        }
    }

    $date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
    $datetime = $date->format('Y-m-d H:i:s');

    $update = $db->prepare("UPDATE `settings` SET `last_check` = :last_check");
    $update->execute(array(":last_check" => $datetime));

    die('done');

} else {
    die($error);
}