<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	$Jour = Insec($_POST['jour']);
	$Sauf = Insec($_POST['sauf']);
	$Debut = Insec($_POST['hdebut']);
	$Fin = Insec($_POST['hfin']);
	if($Jour)
		SetData("Joueur","Dispo_Jour",$Jour,"ID",$PlayerID);
	if($Sauf)
		SetData("Joueur","Dispo_Sauf",$Sauf,"ID",$PlayerID);
	if($Debut)
		SetData("Joueur","Dispo_Debut",$Debut,"ID",$PlayerID);
	if($Fin)
		SetData("Joueur","Dispo_Fin",$Fin,"ID",$PlayerID);	
	echo "Votre horaire a été mis à jour!";
	echo "<p><a title='Retour au profil' href='index.php?view=user'>Retour au profil</a></p>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>