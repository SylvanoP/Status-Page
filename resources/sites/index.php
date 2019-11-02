<?php
$currPage = 'front_Startseite';
include 'app/controller/PageController.php';
?>

<div id="system_status">
    <?php

    $count = $check->count();
    $countOnline = $check->countOnline();
    $countOffline = $check->countOffline();

    if($countOffline != 0 && $count == $countOffline){
        $text = 'Kompletter Systemausfall';
        $color = 'danger';
    } elseif($count == 0) {
        $text = 'Es sind keine überprüften Systeme vorhanden';
        $color = 'primary';
    } elseif($countOffline == 0 && $count == $countOnline){
        $text = 'Alle Systeme erreichbar';
        $color = 'success';
    } else {
        $text = 'Ein oder mehrere Systeme sind nicht erreichbar';
        $color = 'warning';
    }

    ?>
    <div class="alert alert-<?= $color; ?>" role="alert">
        <i class="fas fa-signal"></i> <?= $text; ?> <span style="float: right;">Letzte Prüfung <?= $helper->formatDate($helper->getSetting('last_check')); ?></span>
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <?php
        $categories = $db->prepare("SELECT * FROM `categories` WHERE `state` = :state");
        $categories->execute(array(":state" => 'active'));
        if ($categories->rowCount() != 0) {
        while ($categorie = $categories->fetch(PDO::FETCH_ASSOC)){ ?>
        <div class="card">
            <div class="card-header">
                <?= $categorie['name']; ?> <span style="float: right;">Nächste Prüfung in <span id="countdown<?= $categorie['id']; ?>">30</span> Sekunden</span>
            </div>
            <ul class="list-group list-group-flush" id="monitors<?= $categorie['id']; ?>">
                <?php
                $SQL = $db->prepare("SELECT * FROM `monitors` WHERE `state` != :state AND `categorie_id` = :categorie_id");
                $SQL->execute(array(":state" => 'disabled', ":categorie_id" => $categorie['id']));
                if ($SQL->rowCount() != 0) {
                while ($row = $SQL->fetch(PDO::FETCH_ASSOC)){

                    if($row['state'] == 'online'){
                        $badge = 'success';
                        $state = 'Online';
                    } elseif($row['state'] == 'offline'){
                        $badge = 'danger';
                        $state = 'Offline';
                    } elseif($row['state'] == 'unchecked'){
                        $badge = 'primary';
                        $state = 'Warte auf Prüfung';
                    } else {
                        $badge = 'warning';
                        $state = 'Fehler';
                    }

                ?>
                    <li class="list-group-item">
                        <?= $row['name']; ?> <span class="badge-right badge badge-pill badge-<?= $badge; ?>"><?= $state; ?></span>
                    </li>
                <?php } } ?>
            </ul>
        </div>
        <br>

        <script>
            function countdown<?= $categorie['id']; ?>() {
                var counter<?= $categorie['id']; ?> = 0;
                var interval<?= $categorie['id']; ?> = setInterval(function() {
                    counter<?= $categorie['id']; ?>++;
                    $('#countdown<?= $categorie["id"]; ?>').html(30-counter<?= $categorie['id']; ?>);
                    if (counter<?= $categorie['id']; ?> == 30) {
                        reloadMonitors<?= $categorie['id']; ?>();
                        clearInterval(interval<?= $categorie['id']; ?>);
                    }
                }, 1000);
            }
            countdown<?= $categorie['id']; ?>();

            function reloadMonitors<?= $categorie['id']; ?>() {
                $("#monitors<?= $categorie['id']; ?>").load(location.href + " #monitors<?= $categorie['id']; ?>");
                countdown<?= $categorie['id']; ?>();
            }
        </script>
        <?php } } else { echo 'Es wurden keine Kategorien gefunden'; } ?>
    </div>

</div>

<script>
    function countdown() {
        var counter = 0;
        var interval = setInterval(function() {
            counter++;
            $('#countdown').html(30-counter);
            if (counter == 30) {
                reloadMonitors();
                clearInterval(interval);
            }
        }, 1000);
    }
    countdown();

    function reloadMonitors() {
        $("#system_status").load(location.href + " #system_status");
        countdown();
    }
</script>
