<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_inc_pvp.php');
	$PlayerID=$_SESSION['PlayerID'];
	$Pilote_pvp=$_SESSION['Pilote_pvp'];
	if($PlayerID >0){
        RetireCandidat($PlayerID,"delog");
    }
    if($Pilote_pvp >0){
        RetireCandidatPVP($Pilote_pvp,"delog");
    }
/*	echo "<h1>A bientôt !</h1>";
	echo "<img src='images/goodbye.jpg'>";
	include_once('./index.php');*/
	session_unset();
	session_destroy();
    session_write_close();
	header('Location: ./index.php');
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';