<?php
/**
 * Created by PhpStorm.
 * User: JF
 * Date: 19-05-17
 * Time: 16:02
 */
require_once('./jfv_inc_sessions.php');
header('P3P: CP="NON ADM ONL STA"');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./jfv_events.inc.php');
set_error_handler('GestionErreurs');
//ini_set('session.bug_compat_42',0);
$AccountID = Insec($_SESSION['AccountID']);
$PlayerID = Insec($_SESSION['PlayerID']);
$Pilote_pvp = Insec($_SESSION['Pilote_pvp']);
$OfficierEMID = Insec($_SESSION['Officier_em']);
$country = $_SESSION['country'];
$Date_Campagne = GetData("Conf_Update", "ID", 2, "Date");
//$view='update';
if ($AccountID < 8 and $AccountID > 0) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    $Date_up = date('Y-m-d');
    Update($Date_up);
    $view = Insec($_GET['view']);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
$Datey = substr($Date_Campagne, 2, 2);
$Datem = substr($Date_Campagne, 5, 2);
$Dated = substr($Date_Campagne, 8, 2);
$Dist = $_SESSION['Distance'];
$view = Insec($_GET['view']);
require_once('./content.php');
usleep(1);
if ($AccountID > 0) {
    dbconnect();
    if ($PlayerID > 0) {
        $resetl = $dbh->prepare("UPDATE Joueur SET Con_date=NOW() WHERE ID=:account");
        $result2 = $dbh->prepare("SELECT Premium,Officier_em,Officier,Admin,Encodage,Actif FROM Joueur WHERE ID=:account");
        $result = $dbh->prepare("SELECT Nom,Pays,Front,Unit,Avion_Perso,Proto,Photo,Photo_Premium,Credits,Missions_Jour,Missions_Max,Avancement,Reputation,MIA,Equipage,Actif FROM Pilote WHERE ID=:playerid");
        $resetl->bindParam(':account', $AccountID, 1);
        $resetl->execute();
        unset($resetl);
        /* Account */
        $result2->bindParam(':account', $AccountID, 1);
        $result2->execute();
        $data2 = $result2->fetchObject();
        $Premium = $data2->Premium;
        $Officier_em = $data2->Officier_em;
        $Admin = $data2->Admin;
        $Encodage = $data2->Encodage;
        $Actif = $data2->Actif;
        $result2->closeCursor();
        /* Pilote */
        $result->bindParam(':playerid', $PlayerID, 1);
        $result->execute();
        $data = $result->fetchObject();
        $ID = $data->ID;
        $Nom = $data->Nom;
        $Pays = $data->Pays;
        $Front = $data->Front;
        $Unite = $data->Unit;
        $Avancement = $data->Avancement;
        $Reputation = $data->Reputation;
        $Missions_Jour = $data->Missions_Jour;
        $Missions_Max = $data->Missions_Max;
        $MIA = $data->MIA;
        $Avion_Perso = $data->Avion_Perso;
        $Proto = $data->Proto;
        $Photo = $data->Photo;
        $Photo_Premium = $data->Photo_Premium;
        $Credits = $data->Credits;
        $Equipage = $data->Equipage;
        $Actif = $data->Actif;
        $result->closeCursor();
        if ($Credits > 40){
            $resetct = $dbh->prepare("UPDATE Pilote SET Credits=40 WHERE ID=:playerid");
            $resetct->bindParam(':playerid', $PlayerID, 1);
            $resetct->execute();
            unset($resetct);
        }
        if ($Officier_em){
            $respil = $dbh->prepare("SELECT Nom FROM Officier_em WHERE ID=:officieremid");
            $respil->bindValue(':officieremid', $Officier_em, 1);
            $respil->execute();
            $datapil = $respil->fetchObject();
            $Officier_em_Nom = $datapil->Nom;
            $respil->closeCursor();
        }
        if ($PlayerID and $Dist == 0) {
            $resmsg = $dbh->prepare("SELECT COUNT(*) FROM gnmh_aubedesaiglesnet3.Ada_Messages WHERE Reception=:playerid AND Archive='0' AND Lu='0' AND Rec_em='3'");
            $resmsg->bindParam(':playerid', $PlayerID, 1);
            $resmsg->execute();
            $Msg_nbr = $resmsg->fetchColumn();
            $resmsg->closeCursor();
        }
        $Missions_Max = 6 - $Missions_Max;
        if ($view == 'update' or $view == 'mission' or $Dist != 0 or $Actif == 1) {
            $Show_all = false;
            $Show_partial = true;
        }
        else{
            $Show_all = true;
        }
        if ($Premium > 0 and $Photo_Premium == 1)
            $img_profil = "uploads/Pilote/" . $PlayerID . "_photo.jpg";
        else
            $img_profil = "images/persos/pilote" . $Pays . $Photo . ".jpg";
    }
    elseif ($OfficierEMID > 0) {
        $resetl = $dbh->prepare("UPDATE Joueur SET Con_date=NOW() WHERE ID=:account");
        $result2 = $dbh->prepare("SELECT Premium,Pilote_id,Officier,Admin,Encodage,Actif FROM Joueur WHERE ID=:account");
        $result = $dbh->prepare("SELECT * FROM Officier_em WHERE ID=:officieremid");
        $resetl->bindParam(':account', $AccountID, 1);
        $resetl->execute();
        unset($resetl);
        /* Account */
        $result2->bindParam(':account', $AccountID, 1);
        $result2->execute();
        $data2 = $result2->fetchObject();
        $Premium = $data2->Premium;
        $PiloteID = $data2->Pilote_id;
        $Admin = $data2->Admin;
        $Encodage = $data2->Encodage;
        $Actif = $data2->Actif;
        $result2->closeCursor();
        /* Officier */
        $result->bindParam(':officieremid', $OfficierEMID, 1);
        $result->execute();
        $data = $result->fetchObject();
        $ID = $data->ID;
        $Nom = $data->Nom;
        $Pays = $data->Pays;
        $Front = $data->Front;
        $Avancement = $data->Avancement;
        $Credits = $data->Credits;
        $Photo = $data->Photo;
        $Photo_Premium = $data->Photo_Premium;
        $result->CloseCursor();
        if ($Credits > $CT_MAX){
            $resetct = $dbh->prepare("UPDATE Officier_em SET Credits=$CT_MAX WHERE ID=:officieremid");
            $resetct->bindParam(':officieremid', $OfficierEMID, 1);
            $resetct->execute();
            unset($resetct);
        }
        if ($PiloteID){
            $respil = $dbh->prepare("SELECT Nom FROM Pilote WHERE ID=:piloteid");
            $respil->bindValue(':piloteid', $PiloteID, 1);
            $respil->execute();
            $datapil = $respil->fetchObject();
            $Pilote = $datapil->Nom;
            $respil->closeCursor();
        }
        $resmsg = $dbh->prepare("SELECT COUNT(*) FROM gnmh_aubedesaiglesnet3.Ada_Messages WHERE Reception=:officieremid AND Archive='0' AND Lu='0' AND Rec_em='1'");
        $resmsg->bindParam(':officieremid', $OfficierEMID, 1);
        $resmsg->execute();
        $Msg_nbr = $resmsg->fetchColumn();
        $resmsg->closeCursor();
        $Missions_Jour = 'N';
        $Missions_Max = 'A';
        $Grade = GetAvancement($Avancement, $Pays, 0, 1);
        if ($Premium > 0 and $Photo_Premium == 1)
            $img_profil = 'uploads/Officier/' . $OfficierEMID . '_photo.jpg';
        else
            $img_profil = 'images/persos/general' . $Pays . $Photo . '.jpg';
        $Show_all = false;
        $Show_ground = true;
    }
    else {
        $img_profil = 'images/profil_default.png';
        $Show_all = false;
        $Show_partial = true;
    }
}
else {
    $img_profil = 'images/profil_default.png';
    $Show_all = false;
    $Show_partial = true;
}
if ($Show_all or $Show_ground) {
    if($country){
        $respays = $dbh->prepare("SELECT Faction FROM Pays WHERE ID=:country");
        $respays->bindParam(':country', $country, 1);
        $respays->execute();
        $datapays = $respays->fetchObject();
        $Faction = $datapays->Faction;
        $respays->closeCursor();
    }
    $result_con = $dbh->query("SELECT login FROM Joueur WHERE Con_date BETWEEN NOW() - INTERVAL 5 MINUTE AND NOW() AND Chat_date < Con_date AND Admin=0");
    $result_chat = $dbh->query("SELECT login,Pays FROM Joueur WHERE Chat_date BETWEEN NOW() - INTERVAL 2 MINUTE AND NOW() AND ID >1");
    while($datacd = $result_con->fetchObject()){
        $Connectes .= '<br><img src="images/led_orange.png"> ' . ucfirst(strtr(substr($datacd->login, 0, 17), '@', true));
    }
    while($datachat = $result_chat->fetchObject()){
        $Connectes .= '<br><img src="images/led_green.png" title="Connecte sur le Chat"><img src="' . $datachat->Pays . '20.gif">' . ucfirst(strtr(substr($datachat->login, 0, 17), '@', true));
        $icon_chat = true;
    }
    if ($view == 'live_chat' || $view == 'live_chatf') {
        $resultc = $dbh->query("SELECT ID,PlayerID,Mode,Faction,Msg,DATE_FORMAT(CDate,'%d-%m %H:%i') as Date_Chat FROM gnmh_aubedesaiglesnet5.Cchat WHERE Mode<10 ORDER BY ID DESC LIMIT 30");
        while($datac = $resultc->fetchObject()){
            $Chat_txt = nl2br($datac->Msg);
            if ($datac->PlayerID > 0) {
                if ($datac->PlayerID < 8)
                    $Chatter = '-Admin-';
                else
                    $Chatter = GetData("Joueur", "ID", $datac->PlayerID, "login");
            }
            if (!$datac->Faction)
                $Chat_open .= "<br>" . $datac->Date_Chat . " : [" . $Chatter . "] >" . $Chat_txt;
            elseif ($datac->Faction == $Faction)
                $Chat_faction .= "<br>" . $datac->Date_Chat . " : [" . $Chatter . "] >" . $Chat_txt;
        }
    }
} ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="JF Binote">
    <title>Aube des Aigles : Avion</title>
    <link href="css/bs4/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">Aube des Aigles</a>
    <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#section-engine">News</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section-struct">Compte</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section-weapons">E-M Air</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section-stuff">Etat-Major</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section-perfs">Opérations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#section-perfs">Stats</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <? require_once './' . $content; ?>
</div>
<footer class="bg-inverse text-center text-success">&copy;JF-2017</footer>
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="js/bs4/bootstrap.min.js"></script>
</body>
</html>
