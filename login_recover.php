<?
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
if(isset($_POST['email']))
	$email=Insec($_POST['email']);
else
	$email="";
if(empty($email))
{
	$mes="Erreur d'identification!<br><a href='index.php?view=login' class='btn btn-default'>Recommencer/a>";
	$img="<img src='images/tsss.jpg'>";
}
else
{
	$logins=false;
	$IP=$_SERVER['REMOTE_ADDR'];
	mail('binote@hotmail.com','Aube des Aigles: Récupération identifiant',"Une demande pour l'adresse ".$_POST['email']." encodée ".$email." a été effectuée depuis l'IP ".$IP);
	$con=dbconnecti();
	$email=mysqli_real_escape_string($con,$email);
	$result=mysqli_query($con,"SELECT login,adresse FROM Joueur WHERE adresse='$email' AND Actif=0");
	$num=mysqli_num_rows($result);
	mysqli_close($con);
	if($num >0)
	{
		if($result)
		{
			while($data8=mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$login=$data8['login'];
				$adresse=$data8['adresse'];
				$logins.=$login." \n";
			}
			mysqli_free_result($result);
		}
		if(!$Actif)
		{
			if($adresse)
			{			
				$msg="Bonjour, \n Une demande d'identifiant perdu a été effectuée sur le site aubedesaigles.net. \n Les identifiants associés à cette adresse email sont : ".$logins." \n A bientôt.";
				mail($adresse,'Aube des Aigles: Identifiant perdu',$msg);
				$mes="<p>Identifiant envoyé à l'adresse d'inscription!</p>";
				$img="<img src='images/wounded.jpg'>";
			}
			else
			{
				$mes="Erreur!<br>Aucune adresse d'inscription n'a été définie.</a>";
				$img="<img src='images/tsss.jpg'>";
			}
		}
		else
		{
			$mes="Votre compte a été suspendu.<br>Vous pouvez contacter l'administration du jeu pour plus d'informations.</a>";
			$img="<img src='images/tsss.jpg'>";
		}
	}
	else
	{
		$mes="Utilisateur inconnu!";
		$img="<img src='images/tsss.jpg'>";
	}
}
include_once('./index.php');
?>