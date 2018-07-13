<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	$Sujet = htmlspecialchars(Insec($_POST['Sujet']));
	$Msg = htmlspecialchars(Insec($_POST['msg']));
	$Destinataire = Insec($_POST['destinataire']);
	$Expediteur = Insec($_POST['exp']);
	$country = $_SESSION['country'];
	$date=date('Y-m-d G:i');	
	if($Destinataire and $Expediteur)
	{
		$con=dbconnecti(3);
		//$Sujet = mysqli_real_escape_string($con, $Sujet);
		//$Msg = mysqli_real_escape_string($con, $Msg);
		$query="INSERT INTO Messages (Expediteur, Reception, Date, Message, Sujet)
		VALUES ('$Expediteur','$Destinataire','$date','$Msg','$Sujet')";
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if($ok)
		{
			$mes="Message envoyé avec succès!";
			$img="<img src='images/poste".$country.".jpg'>";
		}
		else
			$mes="Erreur d'envoi de message!<br>";
	}
	else
		$mes="Erreur d'envoi de message!<br>Pas de destinataire !";
	$titre="Envoi de message";
	include_once('./default_blank.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>