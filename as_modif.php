<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');

$PlayerID = $_SESSION['PlayerID'];
$Encodage = GetData("Joueur","ID",$PlayerID,"Encodage");
if($Encodage > 0)
{
	$Pilote = Insec($_POST['pilote']);
	$Pays = Insec($_POST['country']);
	if($Pilote)
	{
		$con = dbconnecti();
		$num = mysqli_query($con, "UPDATE Pilote SET login='$login' WHERE ID='$Pilote'");
		if($num)
		{
			$mes.="<br>Le pilote a été mis à jour!";
			$mes.="<a title='Retour à la liste des as' href='index.php?view=db_as&pays=".$Pays."'>Retour à la liste des as</a>";
		}
		else
		{
			$mes.="<br>Le pilote n'a pas été mis à jour!<br>Vérifiez la validité de vos données.";
		}
		mysqli_close($con);
	}
}
else
{
	echo "<center><h1>Vous devez être connecté pour accéder à cette page!</h1></center>";
	echo "<br><center><h1>Si vous êtes déconnecté après vous être identifié, veuillez activer les <b>Cookies de Session</b> dans les options de votre navigateur Internet</font></center>";
	echo "<br><center><a href='help/aide_cookies.php' target='_blank'>Tutoriel pour activer les Cookies de Session dans Internet Explorer</a></center>";
}
include_once('./index.php');