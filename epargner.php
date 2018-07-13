<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./jfv_combat.inc.php');
include_once('./jfv_rencontre.inc.php');

$Action=Insec($_POST['Action']);
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$meteo=Insec($_POST['Meteo']);
$avion=Insec($_POST['Avion']);
$avion_eni=Insec($_POST['Avioneni']);
$alt=Insec($_POST['Alt']);
$essence=Insec($_POST['Essence']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$HP_eni=Insec($_POST['HP_eni']);
$Puissance=Insec($_POST['Puissance']);
$Enis=Insec($_POST['Enis']);
$Unit_eni=Insec($_POST['Unit_eni']);
$Pilote_eni=Insec($_POST['Pilote_eni']);
$Avion_db_eni = Insec($_POST['Avion_db_eni']);
$c_gaz = Insec($_POST['gaz']);
$flaps = Insec($_POST['flaps']);

//Check Joueur Valide
if(isset($_SESSION['login']) AND isset($_SESSION['pwd']) AND $avion > 0 AND !empty($_POST))
{
	$_SESSION['finish'] = false;
	$_SESSION['tirer'] = false;
	$_SESSION['evader'] = false;
	$_SESSION['missiondeux'] = false;
	$_SESSION['naviguer'] = false;
	$_SESSION['kill_confirm'] = false;
	
	$PVP = $_SESSION['PVP'];
	$Saison = $_SESSION['Saison'];
	$Nuit = $_SESSION['Nuit'];
	$PlayerID = $_SESSION['PlayerID'];
	$country = $_SESSION['country'];
	$Chk_EP = $_SESSION['epargner'];

	if($Chk_EP > 0)
	{
		$mes = "<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>";
		mail ("binote@hotmail.com", "Aube des Aigles: Init Mission F5 (epargner) : ".$PlayerID , "Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}
}
else
{
	$mes = GetMes("init_mission");
	$view = 'login';
	session_unset();
	session_destroy();
}
include_once('./index.php');
?>