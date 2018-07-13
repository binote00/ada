<?
require_once('./jfv_inc_sessions.php');
//session_unset();
session_destroy();
unset($_SESSION['country']);
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$Pays=Insec($_POST['country']);
$login=Insec($_POST['pseudo']);
$email=Insec($_POST['email']);
$Pwd=Insec($_POST['password']);
$Parrain=Insec($_POST['parrain']);
$IP=$_SERVER['REMOTE_ADDR'];
$out=false;
/*$con=dbconnecti();
$result_ip=mysqli_query($con,"SELECT COUNT(*) FROM Joueur WHERE IP='$IP'");
if($result_ip)
{
	$Doublon=mysqli_fetch_array($result_ip);
	if($Doublon[0])
	{
		$out=true;
		exit;
	}
}
if($out)
{
	$mes="Vous possédez déjà un personnage dans l'Aube des Aigles.<br>Veuillez contacter un administrateur pour valider un second compte.";
	header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
}
else
{*/
    $Lands=[1,2,4,6,7,8,9];
	if($Pays and in_array($Pays,$Lands))
	{
		if(!empty($login) and !empty($Pwd) and !empty($Pays) and !empty($email))
		{
			include_once('./jfv_msg.inc.php');
			$Non=false;
			$con=dbconnecti();
			$Doubles=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Joueur WHERE adresse='$email' OR IP='$IP' OR login='$login'"),0);
			if($Doubles >2)
				$Non=true;
			elseif($Doubles >0)
			{
				$Non=true;
				$result2c=mysqli_query($con,"SELECT ID,Premium FROM Joueur WHERE adresse='$email' OR IP='$IP' OR login='$login'");
				if($result2c)
				{
					while($data2c=mysqli_fetch_array($result2c,MYSQLI_ASSOC))
					{
						if($Premium)$Non=false;
						$Parrain=$data2c['ID'];
					}
					mysqli_free_result($result2c);
				}
			}
			if($Non ==true)
				echo "<div class='alert alert-danger'>Vous ne pouvez créer qu'un compte par joueur!<br>Si vous ne possédez aucun compte, veuillez contacter un administrateur via admin@aubedesaigles.net</div>";
			elseif(!IsValidEmail($email))
				echo "<div class='alert alert-danger'>Votre email n'est pas valide!</div>";
			else
			{
				$Pwd=trim($Pwd);
				$Date=date('Y-m-d');
				$login=mysqli_real_escape_string($con,$login);
				$Pwd=password_hash(mysqli_real_escape_string($con,$Pwd),PASSWORD_DEFAULT);
				$email=mysqli_real_escape_string($con,$email);
				$query="INSERT INTO Joueur (login,Mdp,adresse,IP,Pays,Engagement,Parrain) VALUES ('$login','$Pwd','$email','$IP','$Pays','$Date','$Parrain')";
				$ok=mysqli_query($con,$query);
				if($ok)
				{
					$ins_id=mysqli_insert_id($con);
					$_SESSION['AccountID']=$ins_id;
					$_SESSION['PlayerID']=false;
					$_SESSION['Officier']=false;
					$_SESSION['Officier_em']=false;
					$_SESSION['Pilote_pvp']=false;
					$_SESSION['Officier_pvp']=false;
					mail('binote@hotmail.com','Aube des Aigles: Nouveau Joueur','Aube des Aigles: Nouveau Joueur '.$login.' / ID '.$ins_id.' / Email : '.$email.' / Pays : '.$Pays.' / IP : '.$IP);
					$intro="Compte créé avec succès!";
					$img="<img src='images/transfer_yes".$Pays.".jpg'>";
					$mes="<h2>Choix de votre personnage</h2>
					<div class='alert alert-warning'>Dans le jeu, vous pouvez choisir d'incarner un aviateur, un officier ou un officier d'état-major.<br>
					La carrière d'aviateur vous permettra de débuter en tant que pilote dans une escadrille pour ensuite en devenir le commandant. Votre escadrille sera soumise à l'autorité de l'état-major aérien.<br>
					La carrière d'officier vous mettra aux commandes d'un bataillon de troupes terrestres ou navales, au service de l'état-major.<br>
					La carrière d'officier d'état-major vous permettra de gérer différents aspects stratégiques du jeu, de la logistique au commandement des troupes sur le terrain ou dans les airs.</div>
					<div class='row'><div class='col-md-4'><form action='index.php?view=signin_pilot' method='post'><input type='hidden' name='country' value='".$Pays."'><input type='hidden' name='PlayerID' value='".$ins_id."'><h4>Aviateur</h4><br><img src='images/new_aviateur.png'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div>
					<div class='col-md-4'><form action='index.php?view=signin_em' method='post'><input type='hidden' name='country' value='".$Pays."'><input type='hidden' name='pilote' value='".$ins_id."'><h4>Officier</h4><br><img src='images/new_em.png'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div>
					</div>";
					//<div class='col-md-4'><form action='index.php?view=signin_ground' method='post'><input type='hidden' name='country' value='".$Pays."'><input type='hidden' name='pilote' value='".$ins_id."'><h4>Officier</h4><br><img src='images/new_officier.png'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div>
					/*<table class='table'><tr><th>Aviateur</th><th>Officier terrestre</th><th>Officier d'état-major</th></tr><tr>
					<td><form action='index.php?view=signin_pilot' method='post'><input type='hidden' name='country' value='".$Pays."'><input type='hidden' name='PlayerID' value='".$ins_id."'><img src='images/new_aviateur.png'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td>
					<td><form action='index.php?view=signin_ground' method='post'><input type='hidden' name='country' value='".$Pays."'><input type='hidden' name='PlayerID' value='".$ins_id."'><img src='images/new_officier.png'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td>
					<td><form action='index.php?view=signin_em' method='post'><input type='hidden' name='country' value='".$Pays."'><input type='hidden' name='PlayerID' value='".$ins_id."'><img src='images/new_em.png'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td>
					</tr></table>";*/
					include_once('./index.php');
					echo "<p class='lead'>Compte créé avec succès!</p>".$img.$mes;
				}
				else
				{
					$mes.="Erreur de création de compte (IP ".$IP." / login ".$login." / Pwd ".$Pwd." / email ".$email." / Pays ".$Pays." / Date ".$Date." / Parrain ".$Parrain.") ".mysqli_error($con);
					mail('binote@hotmail.com','Aube des Aigles: Signin error',$mes);
					include_once('./index.php');
					echo "<p>Erreur de création de Compte ! Si le problème persiste, <a href='mailto:admin@aubedesaigles.net' class='lien'>contactez un administrateur.</a></p>";
					exit;
				}
			}
		}
		else
		{
            ?><h1>Création de votre compte</h1>
			<form action="index.php?view=signins" method="post">
			<input type="hidden" name="country" value="<?=$Pays;?>">
			<div class="input-group"><span class="input-group-addon">Adresse Email </span><input type="email" name="email" size="50" maxlength="50" class="form-control" style='width: 200px' placeholder="E-mail" onmouseup="valbtn.disabled=false;" required></div>
			<div class="input-group"><span class="input-group-addon">Identifiant </span><input type="text" name="pseudo" size="50" maxlength="50" class="form-control" style='width: 225px' placeholder="Identifiant" onmouseup="valbtn.disabled=false;" required></div>
			<div class="input-group"><span class="input-group-addon">Mot de Passe </span><input type="password" name="password" size="16" maxlength="16" class="form-control" style='width: 120px' placeholder="Mot de passe" onmouseup="valbtn.disabled=false;" required></div>
			<input type='submit' value='VALIDER' id="valbtn" class="btn btn-default" onclick='this.disabled=true;this.form.submit();'></form><?
		}
	}else{
        echo '<h1>Création de votre compte</h1><div class="alert alert-danger">Veuillez sélectionner une nation!</div><a href="index.php?view=signin" class="btn btn-default">Recommencer</a>';
    }
