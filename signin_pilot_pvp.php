<?
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$Nom=Insec($_POST['name_pil']);
	$IP=$_SERVER['REMOTE_ADDR'];
	$country=$_SESSION['country'];
	$Pseudo_Reserve=false;
	$Non=false;
	$con=dbconnecti();
	$resultia=mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Nom='$Nom'");
	$result=mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Nom='$Nom'");
	$resultpvp=mysqli_query($con,"SELECT COUNT(*) FROM Pilote_PVP WHERE Nom='$Nom'");
	mysqli_close($con);
	if($resultpvp) 
	{
		$resultat=mysqli_fetch_row($resultpvp);
		if($resultat[0])
			$Non=true;
		mysqli_free_result($resultpvp);
	}
	if($result) 
	{
		$resultat=mysqli_fetch_row($result);
		if($resultat[0])
			$Non=true;
		mysqli_free_result($result);
	}
	if($resultia) 
	{
		$resultat=mysqli_fetch_row($resultia);
		if($resultat[0])
			$Non=true;
		mysqli_free_result($resultia);
	}
	if(!empty($Nom))
	{
		if($Non ==true)
			echo 'Ce nom est déjà utilisé!';
		elseif(!preg_match("#^[[:alpha:]äçéèêüöëêûôùîï'\- ]+$#",$Nom) or $Pseudo_Reserve or strlen($Nom) <7)
			echo "Le nom de votre pilote n'est pas valide!<br>Le nom du pilote doit comporter au moins 6 lettres, et éventuellement un espace entre le prénom et le nom.";
		else
		{
			$Nom=ucwords(trim(strtolower($Nom)));
			$Date=date('Y-m-d');
			$con=dbconnecti();
			$Nom=mysqli_real_escape_string($con,$Nom);
			$query="INSERT INTO Pilote_PVP (Nom,Pays,Engagement,IP)";
			$query.="VALUES ('$Nom','$country','$Date','$IP')";
			$ok=mysqli_query($con,$query);
			if($ok)
			{
				$ins_id=mysqli_insert_id($con);
				SetData("Joueur","Pilote_pvp",$ins_id,"ID",$AccountID);
				mail('binote@hotmail.com','Aube des Aigles: Nouveau Pilote PVP',$AccountID.' / Nom : '.$Nom.' / Pilote_pvp : '.$ins_id);
				echo "<h1>Personnage créé avec succès!</h1>";
				echo "<p><img src='images/shooted.jpg'></p>";
				$_SESSION['Pilote_pvp']=$ins_id;
				exit;
			}
			else
			{
				mail ('binote@hotmail.com','Aube des Aigles: Signin error pilot pvp' , "Erreur de création de personnage (".$IP.") ".mysqli_error($con));
				echo '<p>Erreur de création de Personnage !</p>';
			}
		}
	}
	else
		$avert='';
	echo "<h1>Création du pilote PVP</h1>".$avert."<form action='index.php?view=signin_pilot_pvp' method='post'>
	<h2>Nom du Pilote <a class='bold' href='aide_nom_pilote.php' target='_blank' title='Aide'><img src='images/help.png'></a></h2>
	<input type='text' title='Le nom du pilote ne peut comporter que des lettres et éventuellement un espace entre le prénom et le nom' name='name_pil' size='30' maxlength='30' class='form-control' style='width: 300px'>
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
}
