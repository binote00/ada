<?
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
if(isset($_POST['identifiant']))
	$login=Insec($_POST['identifiant']);
else
	$login='';
if(empty($login))
{
	$mes="Erreur d'identification!<br><a href='index.php?view=login' class='btn btn-default'>Recommencer/a>";
	$img="<img src='images/tsss.jpg'>";
}
else
{
    function generate_password($length = 20){
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        $max = strlen($chars) - 1;
        for ($i=0; $i < $length; $i++)
            $str .= $chars[random_int(0, $max)];
        return $str;
    }

	$IP=$_SERVER['REMOTE_ADDR'];
	mail('binote@hotmail.com','Aube des Aigles: Recuperation mot de passe',"Une demande pour le login ".$_POST['identifiant']." encodée ".$login." a été effectuée depuis l'IP ".$IP);

    $pass = generate_password();
    $crypted_pass = password_hash($pass,PASSWORD_DEFAULT);

	dbconnect();
	$update = $dbh->prepare("UPDATE Joueur SET Mdp='$crypted_pass' WHERE login=:login");
    $update->bindValue('login',$login,2);
    $update->execute();
	$result = $dbh->prepare("SELECT adresse,Actif FROM Joueur WHERE login=:login");
	$result->bindValue('login',$login,2);
	$result->execute();
	$data = $result->fetchObject();
    if($data)
	{
		$adresse=$data->adresse;
		$Actif=$data->Actif;
		if(!$Actif)
		{
			if($adresse)
			{
				$msg="Bonjour, \n Ce message provient du site aubedesaigles.net suite a une demande de nouveau mot de passe. \n Votre nouveau mot de passe est : ".$pass." \n A tout de suite sur le jeu.";
				mail($adresse,'Aube des Aigles: Mot de passe perdu',$msg);
				$mes="<p>Mot de passe envoyé à l'adresse d'inscription!</p>";
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