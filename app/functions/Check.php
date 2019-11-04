<?php

$check = new Check();

class Check extends Controller
{

    public function ping($addr)
    {
        exec('ping -c 1'.$addr,$output,$state);
        return $state;
    }

    public function count()
    {
        $SQL = self::db()->prepare("SELECT * FROM `monitors` WHERE `state` != :disabled AND `state` != :unchecked");
        $SQL->execute(array(":disabled" => 'disabled', ":unchecked" => 'unchecked'));
        return $SQL->rowCount();
    }

    public function countOnline()
    {
        $SQL = self::db()->prepare("SELECT * FROM `monitors` WHERE `state` = :state");
        $SQL->execute(array(":state" => 'online'));
        return $SQL->rowCount();
    }

    public function countOffline()
    {
        $SQL = self::db()->prepare("SELECT * FROM `monitors` WHERE `state` = :state");
        $SQL->execute(array(":state" => 'offline'));
        return $SQL->rowCount();
    }

}