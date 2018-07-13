<?php
require_once('./jfv_inc_sessions.php');
//header('P3P: CP="NON ADM ONL STA"');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./jfv_events.inc.php');
//set_error_handler('GestionErreurs');
//ini_set('session.bug_compat_42',0);
$AccountID = Insec($_SESSION['AccountID']);
$PlayerID = Insec($_SESSION['PlayerID']);
$Pilote_pvp = Insec($_SESSION['Pilote_pvp']);
//$OfficierID = Insec($_SESSION['Officier']);
$OfficierEMID = Insec($_SESSION['Officier_em']);
//$Officier_pvp = Insec($_SESSION['Officier_pvp']);
$country = $_SESSION['country'];
$Date_Campagne = GetData("Conf_Update", "ID", 2, "Date");
$view='update';
if ($AccountID < 8 and $AccountID > 0) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    $Date_up = date('Y-m-d');
    Update($Date_up);
    //$view = Insec($_GET['view']);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
$Datey = substr($Date_Campagne, 2, 2);
$Datem = substr($Date_Campagne, 5, 2);
$Dated = substr($Date_Campagne, 8, 2);
$Dist = $_SESSION['Distance'];
$view = Insec($_GET['view']);
if ($AccountID > 0) {
    dbconnect();
    /*if ($Pilote_pvp > 0) {
        $con = dbconnecti();
        $reset1 = mysqli_query($con, "UPDATE Joueur SET Con_date=NOW() WHERE ID='$AccountID'");
        $result2 = mysqli_query($con, "SELECT Premium,Admin,Encodage,Actif,Beta FROM Joueur WHERE ID='$AccountID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : i-account');
        $result = mysqli_query($con, "SELECT Nom,Avancement,Reputation,MIA,Actif,Equipage,Front_sandbox FROM Pilote_PVP WHERE ID='$Pilote_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : i-profil');
        mysqli_close($con);
        if ($result2) {
            while ($data2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                $Premium = $data2['Premium'];
                $Admin = $data2['Admin'];
                $Encodage = $data2['Encodage'];
                $Actif = $data2['Actif'];
                $Beta = $data2['Beta'];
            }
            mysqli_free_result($result2);
        }
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Nom = $data['Nom'];
                $Avancement = $data['Avancement'];
                $Reputation = $data['Reputation'];
                $MIA = $data['MIA'];
                $Actif = $data['Actif'];
                $Equipage = $data['Equipage'];
                $Front_sandbox = $data['Front_sandbox'];
            }
            mysqli_free_result($result);
            unset($data);
        }
        $img_profil = "images/pilotes/pilote_pvp.jpg";
        if ($view == 'update' or $view == 'mission' or $Dist != 0 or $Actif == 1) {
            $Show_all = false;
            $Show_partial = true;
        } else
            $Show_all = true;
    }*/
    /*elseif ($Officier_pvp > 0) {
        $con = dbconnecti();
        $reset1 = mysqli_query($con, "UPDATE Joueur SET Con_date=NOW() WHERE ID='$AccountID'");
        $result2 = mysqli_query($con, "SELECT Premium,Admin,Encodage,Actif FROM Joueur WHERE ID='$AccountID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : op-account');
        $result = mysqli_query($con, "SELECT * FROM Officier_PVP WHERE ID='$Officier_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : op-profil');
        mysqli_close($con);
        if ($result2) {
            while ($data2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                $Premium = $data2['Premium'];
                $Admin = $data2['Admin'];
                $Encodage = $data2['Encodage'];
                $Actif = $data2['Actif'];
            }
            mysqli_free_result($result2);
        }
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $ID = $data['ID'];
                $Nom = $data['Nom'];
                $Pays = $data['Pays'];
                $Front = $data['Front'];
                $Avancement = $data['Avancement'];
            }
            mysqli_free_result($result);
            unset($data);
        }
        $img_profil = "images/pilotes/jc.jpg";
        if ($view == 'update' or $Actif == 1) {
            $Show_all = false;
            $Show_partial = true;
        } else
            $Show_all = true;
    }*/
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
            $img_profil = 'uploads/Pilote/' . $PlayerID . '_photo.jpg';
        else
            $img_profil = 'images/persos/pilote' . $Pays . $Photo . '.jpg';
    }
    /*elseif ($OfficierID > 0) {
        $con = dbconnecti();
        $reset1 = mysqli_query($con, "UPDATE Joueur SET Con_date=NOW() WHERE ID='$AccountID'");
        $result2 = mysqli_query($con, "SELECT Premium,Pilote_id,Officier_em,Admin,Encodage,Actif FROM Joueur WHERE ID='$AccountID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : i-account');
        $result = mysqli_query($con, "SELECT * FROM Officier WHERE ID='$OfficierID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : i-off');
        mysqli_close($con);
        if ($result2) {
            while ($data2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                $Premium = $data2['Premium'];
                $Officier_em = $data2['Officier_em'];
                $PiloteID = $data2['Pilote_id'];
                $Admin = $data2['Admin'];
                $Encodage = $data2['Encodage'];
                $Actif = $data2['Actif'];
            }
            mysqli_free_result($result2);
        }
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $ID = $data['ID'];
                $Nom = $data['Nom'];
                $Pays = $data['Pays'];
                $Front = $data['Front'];
                $Avancement = $data['Avancement'];
                $Credits = $data['Credits'];
                $Photo = $data['Photo'];
                $Division = $data['Division'];
                if ($Credits > 40) SetData("Officier", "Credits", 40, "ID", $OfficierID);
            }
            mysqli_free_result($result);
            unset($data);
        }
        $con3 = dbconnecti(3);
        $ok = mysqli_query($con3, "SELECT COUNT(*) FROM Ada_Messages WHERE Reception='$OfficierID' AND Archive='0' AND Lu='0' AND Rec_em='2'");
        mysqli_close($con3);
        if ($ok) {
            while ($data = mysqli_fetch_array($ok, MYSQLI_NUM)) {
                if ($data[0] > 0) $Msg_nbr = $data[0];
            }
            mysqli_free_result($ok);
        }
        $Missions_Jour = "N";
        $Missions_Max = "A";
        $Grade = GetAvancement($Avancement, $Pays, 0, 1);
        $img_profil = "images/persos/general" . $Pays . $Photo . ".jpg";
        $Show_all = false;
        $Show_ground = true;
        if ($PiloteID) $Pilote = GetData("Pilote", "ID", $PiloteID, "Nom");
        if ($Officier_em) $Officier_em_Nom = GetData("Officier_em", "ID", $Officier_em, "Nom");
    }*/
    elseif ($OfficierEMID > 0) {
        $resetl = $dbh->prepare("UPDATE Joueur SET Con_date=NOW() WHERE ID=:account");
        $result2 = $dbh->prepare("SELECT Premium,Pilote_id,Officier_bonus,Admin,Encodage,Actif FROM Joueur WHERE ID=:account");
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
        $Officier_bonus_id = $data2->Officier_bonus;
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
        elseif($Admin){
            $resetct = $dbh->prepare("UPDATE Officier_em SET Credits=50 WHERE ID=:officieremid");
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
        if ($Officier_bonus_id){
            $resob = $dbh->prepare("SELECT Nom FROM Officier_em WHERE ID=:offbonusid");
            $resob->bindValue(':offbonusid', $Officier_bonus_id, 1);
            $resob->execute();
            $dataob = $resob->fetchObject();
            $Officier_bonus = $dataob->Nom;
            $resob->closeCursor();
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
    $result_chat = $dbh->query("SELECT login,Pays FROM Joueur WHERE Chat_date BETWEEN NOW() - INTERVAL 2 MINUTE AND NOW() AND ID >1");
    if($Admin){
        $result_con = $dbh->query("SELECT login FROM Joueur WHERE Con_date BETWEEN NOW() - INTERVAL 5 MINUTE AND NOW() AND Chat_date < Con_date AND Admin=0");
        while($datacd = $result_con->fetchObject()){
            $Connectes .= '<br><img src="images/led_orange.png"> ' . ucfirst(strtr(substr($datacd->login, 0, 17), '@', true));
        }
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
        /*if($Admin and $resultcb)
        {
            while($datac=mysqli_fetch_array($resultcb,MYSQLI_ASSOC))
            {
                $Chat_txt=nl2br($datac['Msg']);
                if($datac['Mode']==1)
                    $DB_Chatter="Pilote_PVP";
                elseif($datac['Mode']==2)
                    $DB_Chatter="Officier_PVP";
                elseif($datac['Mode']==9)
                    $Chatter="-Admin-";
                if($DB_Chatter)$Chatter=GetData($DB_Chatter,"ID",$datac['PlayerID'],"Nom");
                $Chat_open_b.="<br>".$datac['Date_Chat']." : =Battle= [".$Chatter."] >".$Chat_txt;
            }
            mysqli_free_result($resultcb);
            unset($datac);
        }*/
    }
} ?>
<!DOCTYPE html>
<!--[if lt IE 8]>
<html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]>
<html class="no-js"><![endif]-->
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>L'Aube des Aigles</title>
    <meta name="author" content="jf BINOTE">
    <meta name="description"
          content="Aube des Aigles est un jeu de gestion et de strategie multi-joueurs gratuit par navigateur ayant pour cadre la seconde guerre mondiale (1939-1945)">
    <meta name="keywords"
          content="Jeu, Gratuit, Strategie, Gestion, Multi-joueurs, Pilote, Guerre, 1940, 1945, RAF, Royal Air Force, Luftwaffe, Wehrmacht, USAAF, USAF, VVS, WW2, 2GM, Aviation, Wargame">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="https://aubedesaigles.net/index">
    <? if ($_SESSION['Distance'] >= 1) { ?>
        <meta name="robots" content="noindex,follow"><? } ?>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/png" href="/images/anti_apple.png">
    <link rel="stylesheet" href="./css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="./css/bootstrap-theme.css">
    <link rel="stylesheet" href="./css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/datatables.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/ada.css">
    <base target="_parent">
</head>
<body class="<?php echo (isset($_COOKIE['leftmenu']) && $_COOKIE['leftmenu'] == 'open') ? 'with-lefbar' : '' ?>">
<div id="dhtmltooltip"></div>
<!--[if lte IE 8]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Sidebar -->
<div id="leftbar"<?php echo (isset($_COOKIE['leftmenu']) && $_COOKIE['leftmenu'] == 'open') ? ' class="open"' : '' ?>>
    <div id="lefbar-wide" class="leftbar-container">
        <div id="leftbar-header">
            <? if ($Show_all and !$Pilote_pvp and !$Officier_pvp) { ?>
                <div id="message-shortcut">
                    <div id="message-shortcut-opener" class="closed">Ouvrir le menu des messages</div>
                    <? if ($Msg_nbr) { ?>
                        <div id="message-shortcut-number"><?=$Msg_nbr; ?></div>
                    <? } ?>
                    <div id="message-shortcut-content">
                        <h4 id="message-shortcut-title">Service Postal</h4>
                        <? if ($Msg_nbr) { ?>
                            <span id="message-shortcut-close"><?=$Msg_nbr; ?></span>
                        <? } ?>
                        <ul id="message-shortcut-list" class="list-unstyled">
                            <li><a href="index.php?view=ground_ecrire">Nouveau message</a></li>
                            <li><a href="index.php?view=ground_messagerie">Tous les messages</a></li>
                        </ul>
                    </div>
                </div>
            <? } elseif ($Show_ground) { ?>
                <div id="message-shortcut">
                    <div id="message-shortcut-opener" class="closed">Ouvrir le menu des messages</div>
                    <? if ($Msg_nbr) { ?>
                        <div id="message-shortcut-number"><?=$Msg_nbr ?></div>
                    <? } ?>
                    <div id="message-shortcut-content">
                        <h4 id="message-shortcut-title">Service Postal</h4>
                        <? if ($Msg_nbr) { ?>
                            <span id="message-shortcut-close"><?=$Msg_nbr ?></span>
                        <? } ?>
                        <ul id="message-shortcut-list" class="list-unstyled">
                            <li><a href="index.php?view=ground_ecrire">Nouveau message</a></li>
                            <li><a href="index.php?view=ground_messagerie">Tous les messages</a></li>
                        </ul>
                    </div>
                </div>
            <? }
            if ($Show_all or $Show_partial or $Show_ground){ ?>
            <div id="account-type"><span id="account-type-aviation">Aviation</span></div>
            <div id="account-army"><span
                        id="account-type-<? echo GetData("Pays", "ID", $Pays, "code"); ?>"><? echo GetPays($Pays); ?></span>
            </div>
            <div id="account-credit-time"><span id="credit-time-unit"
                                                title="Credits Temps"><?=$Credits ?></span><span
                        id="credit-time-mission"><? echo $Missions_Jour . '/' . $Missions_Max; ?></span></div>
        </div>
        <div id="leftbar-content">
            <? }
            if ($Show_ground) { ?>
                <div id="account-change">
                    <h4 id="account-change-title"><span class="account-earth">Armée de Terre</span><?=$Nom?> <b
                                class="caret"></b></h4>
                    <ul id="account-change-list" class="list-unstyled">
                        <? if ($PiloteID) { ?>
                            <li>
                                <a href="login_pilote.php"
                                   data-form="{'pil_id':'<?=$PiloteID ?>', 'ply_id':'<?=$AccountID ?>'}"
                                   class="post-data"><span class="account-aviation">Aviation</span><?=$Pilote?>
                                </a>
                            </li>
                        <? }/*if($Second_P and ($Second_P != $_SESSION['PlayerID'] or !$_SESSION['PlayerID'])){?>
						<li>
							<a href="login_pilote.php" data-form="{'pil_id':'<?echo $Second_P?>', 'ply_id':'<?echo $AccountID?>'}" class="post-data"><span class="account-aviation-bis">Aviation, second pilote</span><?echo $Second;?></a>
						</li>
					<?}*/
                        if ($Officier_em) { ?>
                            <li>
                                <a href="login_em.php"
                                   data-form="{'off_id':'<?=$Officier_em ?>', 'ply_id':'<?=$AccountID ?>'}"
                                   class="post-data"><span class="account-earth"></span><?=$Officier_em_Nom?></a>
                            </li>
                        <? } if($Officier_bonus_id) { ?>
                            <li>
                                <a href="login_em.php"
                                   data-form="{'off_id':'<?=$Officier_bonus_id ?>', 'ply_id':'<?=$AccountID ?>'}"
                                   class="post-data"><span class="account-earth"></span><?=$Officier_bonus?></a>
                            </li>
                        <? } ?>
                    </ul>
                </div>
            <? } elseif ($Show_all) { ?>
                <div id="account-change">
                    <h4 id="account-change-title"><span class="account-aviation">Aviation</span><?=$Nom?> <b
                                class="caret"></b></h4>
                    <ul id="account-change-list" class="list-unstyled">
                        <? if ($PiloteID) { ?>
                            <li>
                                <a href="login_pilote.php"
                                   data-form="{'pil_id':'<?=$PiloteID ?>', 'ply_id':'<?=$AccountID ?>'}"
                                   class="post-data"><span class="account-aviation">Aviation</span><?=$Pilote?>
                                </a>
                            </li>
                        <? }/*if($Ground_Officer){?>
						<li>
						<a href="login_ground.php" data-form="{'off_id':'<?echo $Ground_Officer?>', 'ply_id':'<?echo $AccountID?>'}" class="post-data"><span class="account-earth">Armée de Terre</span><?echo $Officier;?></a>
						</li>
					<?}if($Officier_naval){?>
						<li>	
						<a href="login_ground.php" data-form="{'off_id':'<?echo $Officier_naval?>', 'ply_id':'<?echo $AccountID?>'}" class="post-data"><span class="account-earth"></span><?echo $Officier_naval_Nom;?></a>
						</li>
					<?}if($Second_P and ($Second_P != $_SESSION['PlayerID'] or !$_SESSION['PlayerID'])){?>
						<li>
						<a href="login_pilote.php" data-form="{'pil_id':'<?echo $Second_P?>', 'ply_id':'<?echo $AccountID?>'}" class="post-data"><span class="account-aviation-bis">Aviation, second pilote</span><?echo $Second;?></a>
						</li>
					<?}/*elseif($Date_Campagne > '1945-01-01' and $Reputation > 500){?>
						<li>
						<a href="index.php?view=signin_seconds" data-form="{'email':'<?echo $Email?>'}" class="post-data"><span class="account-aviation-bis"></span>Creer votre second pilote</a>
						</li>
					<?}*/
                        if ($Officier_em) { ?>
                            <li>
                                <a href="login_em.php"
                                   data-form="{'off_id':'<?=$Officier_em ?>', 'ply_id':'<?=$AccountID ?>'}"
                                   class="post-data"><span class="account-earth"></span><?=$Officier_em_Nom?></a>
                            </li>
                        <? } if($Officier_bonus_id) { ?>
                        <li>
                            <a href="login_em.php"
                               data-form="{'off_id':'<?=$Officier_bonus_id ?>', 'ply_id':'<?=$AccountID ?>'}"
                               class="post-data"><span class="account-earth"></span><?=$Officier_bonus?></a>
                        </li>
                        <? } ?>
                    </ul>
                </div>
            <? } ?>
            <!-- Sidebar menu -->
            <ul id="leftbar-menu" class="list-unstyled">
                <? if ($Show_all or $Show_ground) { ?>
                    <li class="first"><a id="leftbar-account" class="leftbar-menu-icon" href="index.php?view=compte">Mon
                            compte</a></li>
                    <li><a id="leftbar-forum" class="leftbar-menu-icon"
                           href="http://cheratte.net/aceofaces/forum/index.php" target="_blank">Forum</a></li>
                    <li><a id="leftbar-chat" class="leftbar-menu-icon"
                           href="index.php?view=live_chat">Chat<? if ($icon_chat) {
                                echo '<div class="i-flex led_green"></div>';
                            } ?></a></li>
                    <li><a href="index.php?view=abo">
                            <div class="i-flex premium20"></div>
                            Premium</a></li>
                    <? if ($Encodage) { ?>
                        <li><a href="index.php?view=_admin">Anim</a></li>
                    <? } if ($Admin) { ?>
                        <li><a href="index.php?view=_admin">Admin</a></li>
                    <? } ?>
                    <li><a href="index.php?view=delog">Deconnexion</a></li>
                    <? if ($Admin) echo $Connectes; ?>
                <? } elseif ($Show_partial and $AccountID > 0) { ?>
                    <li><a id="leftbar-forum" class="leftbar-menu-icon"
                           href="http://cheratte.net/aceofaces/forum/index.php" target="_blank">Forum</a></li>
                    <li><a href="mumble://mumble11.omgserv.com:11136/" title="Canal Mumble du jeu" target="_blank"><img
                                    src="images/mumble.png"> Mumble</a></li>
                    <li><a href="index.php?view=delog">Deconnexion</a></li>
                <? } else { ?>
                    <li><a id="leftbar-forum" class="leftbar-menu-icon"
                           href="http://cheratte.net/aceofaces/forum/index.php" target="_blank">Forum</a></li>
                    <li><a href="index.php?view=login">Se connecter</a></li>
                <? }/*if(($Show_all or $Show_partial or $Show_ground) and $Dist == 0){?>
				<!--<iframe src="http://mumbleviewer.omgserv.com/?id=21020&size=11&font=Verdana&color=FFFFFF&bgcolor=221D1A" scrolling="vertical" frameborder="0" height="225" width="175" style="-webkit-border-radius:1px;-moz-border-radius:1px;border-radius:1px;"></iframe>-->	    	
				<?}*/ ?>
            </ul>
        </div>
        <div id="profil-image"><img src="<?=$img_profil ?>" alt="Image du profil" width="170" height="170"></div>
        <div id="leftbar-footer">
            <div class="ww-date">
                <span class="ww-date-day"><?=$Dated ?></span>
                <span class="ww-date-month"><?=$Datem ?></span>
                <span class="ww-date-year"><?=$Datey ?></span>
            </div>
            <? if ($Show_all or $Show_ground or !$PlayerID) { ?>
                <div id="leftbar-help" class="leftbar-footer-icon"><a href="index.php?view=regles" title="Aide">Aide</a>
                </div>
                <div id="leftbar-rule" class="leftbar-footer-icon"><a href="index.php?view=infos" title="Encyclopedie">Encyclopedie</a>
                </div>
            <? } ?>
        </div>
    </div>
    <div id="lefbar-small" class="leftbar-container">
        <? if ($Show_all or $Show_ground) { ?>
            <div id="small-profil-image">
                <h4>Image de profil:</h4>
                <span><img src="<?=$img_profil ?>" alt="Image du profil" width="91" height="130"></span>
                <div id="clear-small-profil"></div>
            </div>
            <div id="small-account-change">
                <span id="small-account-active"><?=$Nom ?> <b class="caret"></b></span>
                <ul class="list-unstyled">
                    <div id="account-change">
                    <? if ($Show_ground) { ?>
                            <h4 id="account-change-title"><span
                                        class="account-earth">Armée de Terre</span><?=$Nom ?> <b
                                        class="caret"></b></h4>
                            <ul id="account-change-list" class="list-unstyled">
                                <? if ($PiloteID) { ?>
                                    <li>
                                        <a href="login_pilote.php"
                                           data-form="{'pil_id':'<?=$PiloteID ?>', 'ply_id':'<?=$AccountID ?>'}"
                                           class="post-data"><span class="account-aviation"></span><?=$Pilote ?>
                                        </a>
                                    </li>
                                <? }/*if($Second_P and ($Second_P != $_SESSION['PlayerID'] or !$_SESSION['PlayerID'])){?>
							<li>
							<a href="login_pilote.php" data-form="{'pil_id':'<?echo $Second_P?>', 'ply_id':'<?echo $AccountID?>'}" class="post-data"><span class="account-aviation-bis">Aviation, second pilote</span><?echo $Second;?></a>
							</li>
						<?}*/
                                if ($OfficierEMID) { ?>
                                    <li>
                                        <a href="login_em.php"
                                           data-form="{'off_id':'<?=$OfficierEMID ?>', 'ply_id':'<?=$AccountID ?>'}"
                                           class="post-data"><span
                                                    class="account-earth"></span><?=$Officier_em_Nom ?></a>
                                    </li>
                                <? } if($Officier_bonus_id) { ?>
                                <li>
                                    <a href="login_em.php"
                                       data-form="{'off_id':'<?=$Officier_bonus_id ?>', 'ply_id':'<?=$AccountID ?>'}"
                                       class="post-data"><span class="account-earth"></span><?=$Officier_bonus ?></a>
                                </li>
                                <? } ?>
                            </ul>
                    <? } elseif ($Show_all) { ?>
                        <ul id="account-change-list" class="list-unstyled">
                        <? if ($PiloteID) { ?>
                            <li>
                                <a href="login_pilote.php"
                                   data-form="{'pil_id':'<?=$PiloteID ?>', 'ply_id':'<?=$AccountID ?>'}"
                                   class="post-data"><span class="account-aviation">Aviation</span><?=$Pilote ?>
                                </a>
                            </li>
                        <? }/*if($Ground_Officer){?>
					<li>	
					<a href="login_ground.php" data-form="{'off_id':'<?echo $Ground_Officer?>', 'ply_id':'<?echo $AccountID?>'}" class="post-data"><span class="account-earth"></span><?echo $Officier;?></a>
					</li>
				<?}if($Officier_naval){?>
					<li>	
					<a href="login_ground.php" data-form="{'off_id':'<?echo $Officier_naval?>', 'ply_id':'<?echo $AccountID?>'}" class="post-data"><span class="account-earth"></span><?echo $Officier_naval_Nom;?></a>
					</li>
				<?}if($Second_P and ($Second_P != $_SESSION['PlayerID'] or !$_SESSION['PlayerID'])){?>
					<li>
					<a href="login_pilote.php" data-form="{'pil_id':'<?echo $Second_P?>', 'ply_id':'<?echo $AccountID?>'}" class="post-data"><span class="account-aviation-bis"></span><?echo $Second;?></a>					
					</li>
				<?}*/
                        if ($OfficierEMID) { ?>
                            <li>
                                <a href="login_em.php"
                                   data-form="{'off_id':'<?=$OfficierEMID ?>', 'ply_id':'<?=$AccountID ?>'}"
                                   class="post-data"><span class="account-earth"></span><?=$Officier_em_Nom ?></a>
                            </li>
                        <? } if($Officier_bonus_id) { ?>
                        <li>
                            <a href="login_em.php"
                               data-form="{'off_id':'<?=$Officier_bonus_id ?>', 'ply_id':'<?=$AccountID ?>'}"
                               class="post-data"><span class="account-earth"></span><?=$Officier_bonus ?></a>
                        </li>
                        <? }?>
                        </ul>
                    <?} ?>
                    </div>
                </ul>
            </div>
            <? if ($Show_ground) { ?>
                <div id="lefbar-small-header" class="clearfix">
                    <div id="account-type-min"><span id="account-type-min-earth">Armée de Terre</span></div>
                    <div id="account-army-min"><span
                                id="account-type-<? echo GetData("Pays", "ID", $Pays, "code"); ?>"><? echo GetPays($Pays); ?></span>
                    </div>
                    <div id="account-credit-time-min" title="Credits Temps"><?=$Credits ?></div>
                    <div id="account-mission-min">N/A</div>
                    <? if ($Msg_nbr) { ?>
                        <div id="account-msg-min"><a href="index.php?view=ground_messagerie"><span
                                        id="account-msg-min-number"><?=$Msg_nbr ?></span> messages</a></div>
                    <? } ?>
                </div>
            <? } else { ?>
                <div id="lefbar-small-header" class="clearfix">
                    <div id="account-type-min"><span id="account-type-min-aviation">Aviation</span></div>
                    <div id="account-army-min"><span
                                id="account-type-<? echo GetData("Pays", "ID", $Pays, "code"); ?>"><? echo GetPays($Pays); ?></span>
                    </div>
                    <div id="account-credit-time-min" title="Credits Temps"><?=$Credits ?></div>
                    <div id="account-mission-min"
                         title="Missions"><? echo $Missions_Jour . '/' . $Missions_Max; ?></div>
                    <? if ($Msg_nbr) { ?>
                        <div id="account-msg-min"><a href="index.php?view=ground_messagerie"><span
                                        id="account-msg-min-number"><?=$Msg_nbr ?></span> messages</a></div>
                    <? } ?>
                </div>
            <? } ?>
        <? } ?>
        <div id="leftbar-min-content">
            <!-- Sidebar menu -->
            <ul id="leftbar-min-menu" class="list-unstyled">
                <? if ($Show_all or $Show_ground) { ?>
                    <li class="first"><a id="leftbar-account" class="leftbar-menu-icon" href="index.php?view=compte">Mon
                            compte</a></li>
                    <li><a id="leftbar-forum" class="leftbar-menu-icon"
                           href="http://cheratte.net/aceofaces/forum/index.php" target="_blank">Forum</a></li>
                    <li><a href="mumble://mumble11.omgserv.com:11136/?version=1.2.4" title="Canal Mumble du jeu"
                           target="_blank">Mumble</a></li>
                    <li><a href="index.php?view=delog">Deconnexion</a></li>
                <? } elseif ($Show_partial and $AccountID > 0) { ?>
                    <li><a id="leftbar-forum" class="leftbar-menu-icon"
                           href="http://cheratte.net/aceofaces/forum/index.php" target="_blank">Forum</a></li>
                    <li><a href="mumble://mumble11.omgserv.com:11136/?version=1.2.4" title="Canal Mumble du jeu"
                           target="_blank">Mumble</a></li>
                    <li><a href="index.php?view=delog">Deconnexion</a></li>
                <? } else { ?>
                    <li class="first"><a id="leftbar-forum" class="leftbar-menu-icon"
                                         href="http://cheratte.net/aceofaces/forum/index.php" target="_blank">Forum</a>
                    </li>
                    <li><a href="index.php?view=login">Se connecter</a></li>
                <? } ?>
            </ul>
        </div>
        <div id="lefbar-small-footer">
            <div class="ww-date">
                <span class="ww-date-day"><?=$Dated ?></span>
                <span class="ww-date-month"><?=$Datem ?></span>
                <span class="ww-date-year"><?=$Datey ?></span>
            </div>
        </div>
    </div>
    <div id="leftbar-minimizer"><span class="left"></span><span class="right"></span></div>
</div>
<div id="container">
    <div id="header">
        <!-- Topbar -->
        <nav id="navbar-menu-wrapper" class="navbar navbar-fixed-top navbar-default">
            <? if ($PlayerID > 0 and $view != 'update') {
                if ($view != 'mission' and $Dist == 0) {
                    ?>
                    <div id="navbar-menu" class="clearfix">
                        <!-- Menu left-->
                        <div id="navbar-menu-left" class="navbar-menu-group">
                            <ul class="list-unstyled list-inline">
                                <li class="first dropdown">
                                    <a href="index.php?view=news" class="dropdown-toggle"
                                       data-toggle="dropdown">News<span>:</span><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="index.php?view=news">Actualites</a></li>
                                        <li><a href="index.php?view=ground_news">Ordre du jour</a></li>
                                        <li><a href="index.php?view=live_chatf">Le Mess</a></li>
                                        <li><a href="index.php?view=infos">Encyclopedie</a></li>
                                        <li><a href="index.php?view=regles">Aide</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="index.php?view=user" class="dropdown-toggle"
                                       data-toggle="dropdown">Pilote<span>:</span><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <? if (!$MIA) {
                                            ?>
                                            <li class="active"><a href="index.php?view=user">Profil</a></li>
                                            <li><a href="points_coop.php">Cooperation</a></li>
                                            <? if ($Avion_Perso > 0 and $Credits > 0) {
                                                ?>
                                                <li><a href="index.php?view=garage">Avion perso</a></li>
                                            <?
                                            } elseif (($Reputation > 999 or $Avancement > 999) and ($Credits > 9 or $Reputation > 11000 or $PlayerID == 1)) {
                                                ?>
                                                <li><a href="index.php?view=avionperso">Avion perso</a></li>
                                            <?
                                            }
                                            if ($Proto > 0 and $Credits > 0 and $Premium > 0) {
                                                ?>
                                                <li><a href="index.php?view=proto">Prototype</a></li>
                                            <?
                                            }
                                            if ($Equipage > 0) {
                                                ?>
                                                <li><a href="index.php?view=equipage">Equipage</a></li>
                                            <?
                                            } elseif ($Reputation > 499 or $Avancement > 499) {
                                                ?>
                                                <li><a href="index.php?view=choix_equipage">Creer un membre
                                                        d'equipage</a></li>
                                            <?
                                            } ?>
                                            <li><a href="index.php?view=inventaire">Equipement</a></li>
                                        <?
                                        } ?>
                                        <li><a href="index.php?view=carte">Cartes</a></li>
                                        <li><a href="index.php?view=user_journal_menu">Journal</a></li>
                                        <li><a href="index.php?view=profile_stats">Statistiques</a></li>
                                        <? if ($Reputation > 499 and $Avancement > 499) {
                                            ?>
                                            <li><a href="index.php?view=transfer">Mutation</a></li>
                                        <?
                                        }
                                        if ($Missions_Max > 0) {
                                            ?>
                                            <li class="active"><a href="index.php?view=mission_start">Missions</a></li>
                                        <?
                                        } ?>
                                    </ul>
                                </li>
                                <li class="last dropdown">
                                    <a href="index.php?view=escadrille" class="dropdown-toggle" data-toggle="dropdown">Escadrille<span>:</span><b
                                                class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="index.php?view=esc_infos">Informations</a></li>
                                        <li><a href="index.php?view=esc_pilotes">Effectifs</a></li>
                                        <li><a href="index.php?view=esc_missions">Tableau des missions</a></li>
                                        <li><a href="index.php?view=esc_staff">Gestion</a></li>
                                        <li><a href="index.php?view=esc_mission">Transmissions</a></li>
                                        <li><a href="index.php?view=esc_journal">Journal</a></li>
                                        <li><a href="index.php?view=escadrille">Temps libre</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- Menu right -->
                        <div id="navbar-menu-right" class="navbar-menu-group">
                            <ul class="list-unstyled list-inline">
                                <li class="first dropdown">
                                    <a href="index.php?view=em_staff" class="dropdown-toggle" data-toggle="dropdown">Etat-major<span>:</span><b
                                                class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="index.php?view=em_actus">Organigramme</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle"
                                       data-toggle="dropdown">Operations<span>:</span><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <!--<li><a href="index.php?view=assaut">Assauts terrestres</a></li>-->
                                        <li><a href="index.php?view=attaques">Attaques au sol</a></li>
                                        <li><a href="index.php?view=navires">Attaques navales</a></li>
                                        <li><a href="index.php?view=bombs_ia">Bombardements</a></li>
                                        <li><a href="index.php?view=output_chasse_ia">Combats aeriens</a></li>
                                        <li><a href="index.php?view=terre">Combats terrestres</a></li>
                                        <li><a href="index.php?view=naval">Combats navals</a></li>
                                        <li><a href="index.php?view=dca">DCA</a></li>
                                        <li><a href="index.php?view=escorte">Escortes</a></li>
                                        <li><a href="index.php?view=paras">Parachutages</a></li>
                                        <li><a href="index.php?view=patrouille">Patrouilles</a></li>
                                        <li><a href="index.php?view=ravit">Ravitaillements</a></li>
                                        <li><a href="index.php?view=recce">Reconnaissances</a></li>
                                        <li><a href="index.php?view=sauvetage">Sauvetages</a></li>
                                    </ul>
                                </li>
                                <li class="last dropdown">
                                    <a href="index.php?view=campagne_scoret" class="dropdown-toggle"
                                       data-toggle="dropdown">Stats<span>:</span><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="index.php?view=campagne_scoret">Score de
                                                Campagne</a></li>
                                        <li><a href="index.php?view=squadrons">Escadrilles</a></li>
                                        <li><a href="index.php?view=pilotes">Pilotes</a></li>
                                        <li><a href="index.php?view=officiers_em">Officiers</a></li>
                                        <li><a href="index.php?view=destructeurs">Destructeurs</a></li>
                                        <li><a href="index.php?view=as">As</a></li>
                                        <li><a href="index.php?view=tableau_pvp">PVP</a></li>
                                        <? if ($Premium) {
                                            ?>
                                            <li><a href="index.php?view=persos">Personnages</a></li>
                                        <?
                                        } ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="navbar-logo">L'Aube des Aigles</div>
                <?
                } else {
                    ?>
                    <div id="navbar-menu" class="clearfix">
                        <!-- Menu Mission-->
                        <?=$toolbar_left ?>
                        <?=$toolbar_right ?>
                    </div>
                    <div id="navbar-logo">L'Aube des Aigles</div>
                <?
                }
            } elseif ($Officier_pvp > 0 and $view != 'update') { ?>
                <div id="navbar-menu" class="clearfix">
                    <!-- Menu left-->
                    <div id="navbar-menu-left" class="navbar-menu-group">
                        <ul class="list-unstyled list-inline">
                            <li class="last dropdown">
                                <a href="" class="dropdown-toggle" data-toggle="dropdown">Officier<span>:</span><b
                                            class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="active"><a href="index.php?view=ground_menu_pvp">Combat</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- Menu right -->
                    <div id="navbar-menu-right" class="navbar-menu-group">
                        <ul class="list-unstyled list-inline">
                            <li class="first dropdown">
                                <a href="" class="dropdown-toggle" data-toggle="dropdown">Infos<span>:</span><b
                                            class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="index.php?view=battles_menu">Batailles historiques</a></li>
                                    <li><a href="index.php?view=regles_battle">Regles</a></li>
                                    <? if ($Admin) { ?>
                                        <li><a href="index.php?view=ground_ia_pvp">Outils</a></li>
                                    <? } ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="navbar-logo">L'Aube des Aigles</div>
            <? } elseif ($Pilote_pvp > 0 and $view != 'update') {
                if ($view != 'mission' and $Dist == 0) {
                    ?>
                    <div id="navbar-menu" class="clearfix">
                        <!-- Menu left-->
                        <div id="navbar-menu-left" class="navbar-menu-group">
                            <ul class="list-unstyled list-inline">
                                <li class="last dropdown">
                                    <a href="index.php?view=profil_pvp" class="dropdown-toggle" data-toggle="dropdown">Pilote<span>:</span><b
                                                class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <? if (!$MIA) {
                                            ?>
                                            <li class="active"><a href="index.php?view=profil_pvp">Profil</a></li>
                                            <? if ($Avion_Perso > 0) {
                                                ?>
                                                <li><a href="index.php?view=garage_pvp">Avion perso</a></li>
                                            <?
                                            }
                                            if ($Equipage > 0) {
                                                ?>
                                                <li><a href="index.php?view=equipage_pvp">Equipage</a></li>
                                            <?
                                            } else {
                                                ?>
                                                <li><a href="index.php?view=choix_equipage_pvp">Creer un membre
                                                        d'equipage</a></li>
                                            <?
                                            } ?>
                                            <li><a href="index.php?view=inventaire_pvp">Equipement</a></li>
                                        <?
                                        }/*?><li><a href="index.php?view=mission_pvp">Mission historique</a></li><?*/
                                        if (!$Front_sandbox) {
                                            ?>
                                            <li><a href="index.php?view=duel_pvp">Duel</a></li>
                                        <?
                                        } ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- Menu right -->
                        <div id="navbar-menu-right" class="navbar-menu-group">
                            <ul class="list-unstyled list-inline">
                                <li class="first dropdown">
                                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Infos<span>:</span><b
                                                class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="index.php?view=battles_menu">Batailles historiques</a></li>
                                        <li><a href="index.php?view=regles_battle">Regles</a></li>
                                        <li><a href="index.php?view=pilotes_pvp">Classement</a></li>
                                        <li><a href="index.php?view=missions_pvp">Missions</a></li>
                                        <?if ($Admin) {?>
                                            <li><a href="index.php?view=ground_ia_pvp">Outils</a></li>
                                        <?} ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Logo <a href="index.php" id="navbar-logo" title="Aller à la page d'accueil">L'Aube des Aigles</a> -->
                    <div id="navbar-logo">L'Aube des Aigles</div>
                <?
                } else {
                    ?>
                    <div id="navbar-menu" class="clearfix">
                        <!-- Menu Mission-->
                        <?=$toolbar_left ?>
                        <?=$toolbar_right ?>
                    </div>
                    <div id="navbar-logo">L'Aube des Aigles</div>
                <?
                }
            } elseif ($Show_ground and $view != 'update') { ?>
                <div id="navbar-menu" class="clearfix">
                    <!-- Menu left-->
                    <div id="navbar-menu-left" class="navbar-menu-group">
                        <ul class="list-unstyled list-inline">
                            <li class="first dropdown">
                                <a href="index.php?view=news" class="dropdown-toggle"
                                   data-toggle="dropdown">News<span>:</span><b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="index.php?view=news">Actualites</a></li>
                                    <li><a href="index.php?view=ground_news">Ordre du jour</a></li>
                                    <li><a href="index.php?view=live_chatf">Le Mess</a></li>
                                    <li><a href="index.php?view=infos">Encyclopedie</a></li>
                                    <li><a href="index.php?view=regles">Aide</a></li>
                                    <? if ($Premium > 0) { ?>
                                        <li><a href="index.php?view=archives">Archives</a></li>
                                    <? } ?>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="index.php?view=ground_profile" class="dropdown-toggle" data-toggle="dropdown">Officier<span>:</span><b
                                            class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="active"><a href="index.php?view=ground_profile">Profil</a></li>
                                    <li><a href="points_coop.php">Cooperation</a></li>
                                    <? /*if($OfficierID){?>
				          <li><a href="index.php?view=off_pertes">Pertes</a></li>
						  <?}*/ ?>
                                </ul>
                            </li>
                            <? if ($OfficierID) { ?>
                                <li class="last dropdown">
                                    <a href="index.php?view=ground_em" class="dropdown-toggle" data-toggle="dropdown">Bataillon<span>:</span><b
                                                class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="index.php?view=ground_bat">Ordres</a></li>
                                        <li><a href="index.php?view=ground_carte">Carte</a></li>
                                        <li><a href="index.php?view=ground_div">Division</a></li>
                                        <!--<li><a href="index.php?view=ground_journal">Journal</a></li>
                                        <li><a href="index.php?view=ground_appui">Transmissions</a></li>-->
                                    </ul>
                                </li>
                            <? } elseif ($OfficierEMID) { ?>
                                <li class="first dropdown">
                                    <a href="index.php?view=em_staff" class="dropdown-toggle" data-toggle="dropdown">Etat-major
                                        air<span>:</span><b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <? if ($Front != 99) { ?>
                                            <li class="active"><a href="index.php?view=em_staff">Staff</a></li>
                                        <? } ?>
                                        <li><a href="index.php?view=em_radar">Alertes</a></li>
                                        <li><a href="index.php?view=air_em_carte">Cartes</a></li>
                                        <? if ($Front != 99) { ?>
                                            <li><a href="index.php?view=em_mission">Coordination</a></li>
                                        <? } ?>
                                        <li><a href="index.php?view=em_effectifs">Effectifs</a></li>
                                        <!-- <li><a href="index.php?view=em_calendrier">Horaires</a></li>-->
                                        <? if ($Front != 99 or $Admin) { ?>
                                            <li><a href="index.php?view=em_missions">Missions</a></li>
                                        <? } ?>
                                        <li><a href="index.php?view=em_production">Production</a></li>
                                        <li><a href="index.php?view=rapports">Unites</a></li>
                                        <li><a href="index.php?view=em_actus">Organigramme</a></li>
                                    </ul>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                    <!-- Menu right -->
                    <div id="navbar-menu-right" class="navbar-menu-group">
                        <ul class="list-unstyled list-inline">
                            <li class="first dropdown">
                                <a href="index.php?view=ground_em" class="dropdown-toggle" data-toggle="dropdown">Etat-major<span>:</span><b
                                            class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="active"><a href="index.php?view=ground_em">Staff</a></li>
                                    <? /*if($OfficierID){?>
				          <li class="active"><a href="index.php?view=ground_bons">Bons de guerre</a></li>
						<?}*/
                                    if ($OfficierEMID) { ?>
                                        <li><a href="index.php?view=ground_alertes">Alertes</a></li>
                                        <li><a href="index.php?view=ground_em_carte">Cartes</a></li>
                                        <li><a href="index.php?view=em_depots">Depots</a></li>
                                        <? if ($Front != 99 or $Admin) { ?>
                                            <li><a href="index.php?view=ground_em_infras">Infrastructures</a></li>
                                            <li><a href="index.php?view=em_rens_new">Intelligence</a></li>
                                        <? } ?>
                                        <li><a href="index.php?view=em_production2">Production</a></li>
                                        <? /*if($Front !=99 or $Admin){?>
				                        <li><a href="index.php?view=em_transits">Transit</a></li>}
                                        <li><a href="index.php?view=troupes">Troupes</a></li>*/ ?>
                                        <li><a href="index.php?view=ground_em_ia_list">Troupes</a></li>
                                        <li><a href="index.php?view=ville">Villes</a></li>
                                        <li><a href="index.php?view=em_actus">Organigramme</a></li>
                                    <? } ?>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="index.php?view=ranking" class="dropdown-toggle" data-toggle="dropdown">Operations<span>:</span><b
                                            class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <!--<li><a href="index.php?view=assaut">Assauts terrestres</a></li>-->
                                    <li><a href="index.php?view=bombs_tac_ia">Attaques aeriennes</a></li>
                                    <li><a href="index.php?view=navires">Attaques navales</a></li>
                                    <li><a href="index.php?view=bombs_ia">Bombardements</a></li>
                                    <? if ($OfficierEMID) { ?>
                                        <li><a href="index.php?view=output_chasse_ia">Combats aeriens</a></li>
                                    <? } ?>
                                    <li><a href="index.php?view=terre">Combats terrestres</a></li>
                                    <li><a href="index.php?view=naval">Combats navals</a></li>
                                    <li><a href="index.php?view=dca">DCA</a></li>
                                    <? if ($Premium) { ?>
                                        <li><a href="index.php?view=escorte">Escortes</a></li>
                                    <? }if($OfficierEMID){ ?>
                                    <li><a href="index.php?view=para_ia">Parachutages</a></li>
                                    <? }if ($Premium) { ?>
                                        <li><a href="index.php?view=patrouille">Patrouilles</a></li>
                                    <? } ?>
                                    <li><a href="index.php?view=ravit">Ravitaillements</a></li>
                                    <li><a href="index.php?view=recce">Reconnaissances</a></li>
                                    <li><a href="index.php?view=sauvetage">Sauvetages</a></li>
                                </ul>
                            </li>
                            <li class="last dropdown">
                                <a href="index.php?view=ranking" class="dropdown-toggle"
                                   data-toggle="dropdown">Stats<span>:</span><b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="active"><a href="index.php?view=campagne_scoret">Score de Campagne</a>
                                    </li>
                                    <li><a href="index.php?view=bataillons">Bataillons</a></li>
                                    <li><a href="index.php?view=flottilles">Flottilles</a></li>
                                    <li><a href="index.php?view=squadrons">Escadrilles</a></li>
                                    <li><a href="index.php?view=officiers_em">Officiers</a></li>
                                    <li><a href="index.php?view=pilotes">Pilotes</a></li>
                                    <li><a href="index.php?view=destructeurs">Destructeurs</a></li>
                                    <li><a href="index.php?view=as">As</a></li>
                                    <? if ($Premium) { ?>
                                        <li><a href="index.php?view=persos">Personnages</a></li>
                                    <? } ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Logo <a href="index.php" id="navbar-logo" title="Aller à la page d'accueil">L'Aube des Aigles</a> -->
                <div id="navbar-logo">L'Aube des Aigles</div>
            <? } else { ?>
                <!-- Logo <a href="index.php" id="navbar-logo" title="Aller à la page d'accueil">L'Aube des Aigles</a> -->
                <div id="navbar-logo">L'Aube des Aigles</div>
            <? } ?>
        </nav>
    </div>
    <div id="page-wrapper">
        <!-- Page -->
        <div class="container-fluid">
            <div id="page">
                <div id="page-tl"></div>
                <div id="page-tr"></div>
                <div id="page-tc"></div>
                <div id="body-wrapper">
                    <div id="body">
                        <div id="body-content" class="clearfix">
                            <?  require_once './content.php';
                                require_once './'.$content;?>
                        </div>
                    </div>
                </div>
                <div id="body-footer">
                    <div id="page-bl"></div>
                    <div id="page-br"></div>
                    <div id="page-bc"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./js/dhtml_tooltip.js"></script>
<script src="./js/lib/jquery-1.10.2.min.js"></script>
<script>window.jQuery || document.write('<script src="./js/lib/jquery-1.10.2.min.js"><\/script>')</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="./js/lib/bootstrap.min.js"></script>
<script src="./js/lib/jquery.cookie.js"></script>
<script src="./js/datatables.min.js"></script>
<script src="./js/main.js"></script>
<script src="./js/admin_cibles.js"></script>
<? /*if($view =='live_chat'){?>
	<script type="text/javascript">
	$(window).ready (function () {
		var updater = setTimeout (function () {
			$('div#body-content').load ('live_chat.php', 'txt=<?=$Chat_open?>');
		}, 1000);
	});</script>
<?}*/?>
</body>
</html>