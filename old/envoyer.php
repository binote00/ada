<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Sujet = htmlspecialchars(Insec($_POST['Sujet']));
	$Msg = htmlspecialchars(Insec($_POST['msg']));
	$Destinataire = Insec($_POST['destinataire']);
	$Expediteur = Insec($_POST['exp']);
	$Exp_em = Insec($_POST['em']);
	$country = Insec($_SESSION['country']);
	$date=date('Y-m-d G:i');
	include_once('./menu_messagerie.php');
	include_once('./jfv_access.php');	
	if(($Expediteur and $Destinataire) or $Admin or $Anim)
	{
		if(!$Destinataire)
			echo "<p>Vous avez envoyé un message à l'équipe d'animation du jeu.<br>Tout message ne concernant pas le roleplay de votre personnage ou une réponse à une question posée par l'équipe d'animation ne sera pas traité.</p>";
		elseif(strpos($Destinataire,"_") !==false)
		{
			$Destinataire = strstr($Destinataire,'_',true);
			$Rec_em=1;
		}
		//$Sujet = mysqli_real_escape_string($con, $Sujet);
		//$Msg = mysqli_real_escape_string($con, $Msg);
		$query="INSERT INTO Messages (Expediteur, Reception, Date, Message, Sujet, Exp_em, Rec_em)
		VALUES ('$Expediteur','$Destinataire','$date','$Msg','$Sujet','$Exp_em','$Rec_em')";
		$con=dbconnecti(3);
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if($ok)
		{
			echo "<p><img src='images/poste".$country.".jpg'></p>";
			echo "<p>Message envoyé avec succès!</p>";
		}
		else
			echo "<p>Erreur d'envoi de message!</p>";
	}
	else
		echo "<p>Erreur d'envoi de message!<br>Pas d'expéditeur ou destinataire inconnu !</p>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>