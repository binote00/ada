<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Msg = htmlspecialchars(Insec($_POST['officier_msg']));
$Off_em = $_SESSION['Officier_em'];
//Check Joueur Valide
if($Off_em > 0 AND $Msg)
{
	include_once('./jfv_msg.inc.php');
	$Officier = Insec($_POST['Officier']);
	$country = Insec($_POST['Pays']);
	$Front = Insec($_POST['Front']);
	$date = date('Y-m-d G:i');
	echo "<h1>Etat-Major Terrestre</h1>";	
	$con = dbconnecti(3);
	$ok = mysqli_query($con, "INSERT INTO Chat (PlayerID, Pays, Front, Date, Msg) VALUES ('$Officier','$country','$Front','$date','$Msg')");
	mysqli_close($con);
	if($ok)
	{
		if(!$_SESSION['Ground_Chat'] and $Off_em > 0)
		{
			if(strlen($Msg) > 50)
			{
				UpdateData("Officier_em","Note",1,"ID",$Off_em);
				UpdateData("Officier_em","Avancement",2,"ID",$Off_em);
				UpdateData("Officier_em","Reputation",2,"ID",$Off_em);
			}
			$_SESSION['Ground_Chat'] = true;
		}
		echo "Message posté avec succès!";
	}
	else
		echo "Erreur d'envoi de message!";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>