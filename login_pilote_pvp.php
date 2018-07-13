<?
session_unset();
//session_destroy();
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_txt.inc.php');
if(isset($_POST['pil_id']))
	$pil_id=Insec($_POST['pil_id']);
else
	$pil_id="";
if(empty($pil_id))
{
	$mes="<h1>Erreur d'identification!</h1><a href='index.php?view=login' class='btn btn-default'>Recommencer</a>";
	$img="<img src='images/tsss.jpg'>";
}
else
{
	$AccountID=Insec($_POST['ply_id']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT ID,Nom,Avancement,Reputation,Engagement,Credits_date,Credits,Actif FROM Pilote_PVP WHERE ID='$pil_id'")
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : loginpvp-p');
	mysqli_close($con);
	while($data=mysqli_fetch_array($result))
	{
		$ID=$data['ID'];
		$Nom=$data['Nom'];
		$Engagement=$data['Engagement'];
		$Avancement=$data['Avancement'];
		$Reputation=$data['Reputation'];
		$Credits_date=$data['Credits_date'];
		$Credits_Ori=$data['Credits'];
		$Actif=$data['Actif'];
	}
	if($Actif ==1)
	{
		$mes="<h1>Ce personnage est désactivé!</h1>Si cela n'est pas dû à une demande de votre part, veuillez contacter l'administrateur à l'adresse suivante : admin@aubedesaigles.net";
		$img="<img src='images/tsss.jpg'>";
	}
	elseif($ID >0 and $ID ==$pil_id)
	{
		$_SESSION['AccountID']=$AccountID;
		$_SESSION['Pilote_pvp']=$ID;
		$_SESSION['PlayerID']=false;
		$_SESSION['Officier']=false;
		$_SESSION['Officier_em']=false;
		$_SESSION['Officier_pvp']=false;
		$Date_Actuelle =date("Y-m-d");
		$Heure =date("H");
		$ok=true;
		if($Date_Actuelle >$Credits_date)
		{
			if($Heure >6)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote_PVP SET Credits_date='$Date_Actuelle',Missions_Jour=0,Missions_Max=0,enis=0,avion_eni=0,S_Chargeurs=1,S_Purge=0,S_Moteurs=0,S_Camo=0,S_Blindage=0 WHERE ID='$ID'");
				mysqli_close($con);
			}
		}
		//Output
		$titre='Bienvenue '.$Nom.' !';
		$img=Afficher_Image('images/shooted.jpg',"images/image.png","",50);
		$mes='<div class="alert alert-warning">Accédez au mode action en utilisant les menus du haut</div>';
	}
}
usleep(1);
include_once('./index.php');