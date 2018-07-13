<?
require_once('./jfv_inc_sessions.php');
include('./jfv_include.inc.php');

$Pilote=$_SESSION['PlayerID'];
$chemin=Insec($_POST['Chemin']);
$Meteo=Insec($_POST['Meteo']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);

switch($Action)
{
	case 1:
	break;
}

include('./index.php');
?>