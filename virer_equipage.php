<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	$country = $_SESSION['country'];
	if($PlayerID > 0)
	{
		DeleteData("Equipage","ID_ref",$PlayerID);
		SetData("Pilote","Equipage",0,"ID",$PlayerID);
		echo "<h1>Vous renvoyez votre membre d'équipage!</h1>";
		echo "<p><img src='images/mess".$country.".jpg'></p>";
	}
	else
		echo "Une erreur s'est produite, veuillez contacter un administrateur.";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>