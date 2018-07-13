<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Officier = $_SESSION['Officier'];
if($Officier >0)
{
	$Sujet = htmlspecialchars(Insec($_POST['Sujet']));
	$Msg = htmlspecialchars(Insec($_POST['msg']));
	$Destinataire = Insec($_POST['destinataire']);
	$Expediteur = Insec($_POST['exp']);
	$country = $_SESSION['country'];
	$date=date('Y-m-d G:i');	
	if($Destinataire and $Expediteur)
	{
		$con=dbconnecti(3);
		//$Sujet=mysqli_real_escape_string($con, $Sujet);
		//$Msg=mysqli_real_escape_string($con, $Msg);
		$query="INSERT INTO Messages_Terre (Expediteur, Reception, Date, Message, Sujet)
		VALUES ('$Expediteur','$Destinataire','$date','$Msg','$Sujet')";
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if($ok)
		{
			echo "<p>Message envoyé avec succès!</p>";
			echo "<img src='images/poste".$country.".jpg'><br>";
		}
		else
			echo "Erreur d'envoi de message!<br>";
	}
	else
		echo "Erreur d'envoi de message!<br>Pas de destinataire !";
	echo "</body></html>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>