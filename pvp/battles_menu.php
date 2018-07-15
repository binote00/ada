<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
$Pilote_pvp = $_SESSION['Pilote_pvp'];
$Officier_pvp = $_SESSION['Officier_pvp'];
if ($Pilote_pvp > 0 or $Officier_pvp > 0) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    $titre = 'Batailles historiques';
    $mes = "<div class='alert alert-warning'>Le mode batailles historiques vous permet d'incarner un officier ou un pilote d'une nation présente lors des différentes batailles, et ce indépendamment des personnages que vous incarnez dans le mode campagne.
	<br>Ces batailles ne sont pas disponibles en permanence, elles se déroulent selon un calendrier. Lorsqu'une bataille historique est disponible, une annonce apparait lors de votre connexion au jeu quelques jours avant son déroulement, afin de permettre à chacun de choisir son camp.
	<br>Veillez à bien vérifier la date et l'heure et ne vous inscrivez que si vous êtes certain de pouvoir être présent à ce moment là, les batailles durant en moyenne 30 minutes.</div>";
    /*
    $mes.="<table class='table'><thead><tr><th>Nom</th><th>Date</th><th>Lieu</th><th>Nations Terrestres</th><th>Nations Aériennes</th></tr></thead>";
    $mes.="<tr><td><a href='index.php?view=battles&i=1' class='lien'>Les ponts du Canal Albert</a></td><td>Mai 1940</td><td>Maastricht</td>
    <td><img src='images/120.gif' title='".GetPays(1)."'><img src='images/320.gif' title='".GetPays(3)."'><img src='images/520.gif' title='".GetPays(5)."'></td>
    <td><img src='images/120.gif' title='".GetPays(1)."'><img src='images/220.gif' title='".GetPays(2)."'><img src='images/320.gif' title='".GetPays(3)."'><img src='images/420.gif' title='".GetPays(4)."'><img src='images/520.gif' title='".GetPays(5)."'></td></tr>";
    $mes.="<tr><td><a href='index.php?view=battles&i=2' class='lien'>La bataille de Hannut</a></td><td>Mai 1940</td><td>Hannut</td>
    <td><img src='images/120.gif' title='".GetPays(1)."'><img src='images/320.gif' title='".GetPays(3)."'><img src='images/420.gif' title='".GetPays(4)."'></td>
    <td><img src='images/120.gif' title='".GetPays(1)."'><img src='images/220.gif' title='".GetPays(2)."'><img src='images/320.gif' title='".GetPays(3)."'><img src='images/420.gif' title='".GetPays(4)."'></td></tr>";
    $mes.='</table>';*/
    $mes .= '<div class="alert alert-danger">Ce mode de jeu est temporairement désactivé</div>';
    include_once __DIR__ . '/../default.php';
}
