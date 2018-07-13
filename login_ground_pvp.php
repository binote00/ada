<?
session_unset();
//session_destroy();
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(isset($_POST['off_id']))
	$off_id=Insec($_POST['off_id']);
else
	$off_id="";
if(empty($off_id))
{
	$mes="Erreur d'identification!<br><a href='index.php?view=login'><span>Cliquez ici pour recommencer</span></a>";
	$img="<img src='images/tsss.jpg'>";
}
else
{
	$AccountID=Insec($_POST['ply_id']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT COUNT(*),ID,Nom,Pays,Front,Division,Reputation,Avancement,Trait,Heure_Para,Credits,Credits_date FROM Officier_PVP WHERE ID='$off_id'")
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : loging-p');
	mysqli_close($con);
	while($data=mysqli_fetch_array($result))
	{
		$num=$data[0];
		$ID=$data['ID'];
		$Nom=$data['Nom'];
		$country=$data['Pays'];
		$Front=$data['Front'];
		$Credits_Ori=$data['Credits'];
		$Credits_date=$data['Credits_date'];
		$Division=$data['Division'];
		$Reputation=$data['Reputation'];
		$Avancement=$data['Avancement'];
		$Trait_o=$data['Trait'];
		$Heure_Para=$data['Heure_Para'];
	}
	if($num >0)
	{
		if($ID ==$off_id)
		{
			$_SESSION['AccountID']=$AccountID;
			$_SESSION['Officier_pvp']=$ID;
			$_SESSION['country']=$country;
			$_SESSION['PlayerID']=false;
			$_SESSION['Officier']=false;
			$_SESSION['Officier_em']=false;
			SetData("Officier_PVP","Actif",0,"ID",$ID);			
			$mes="<h3><div class='btn btn-primary'><a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=4&t=3529' target='_blank'>Modifications récentes du jeu</a></div></h3>".$annonce."";
			$titre="Bienvenue ".$Nom." !";
			$img=Afficher_Image('images/transfer_yes'.$country.'.jpg',"images/image.png","",50);
		}
		else
		{
			$titre="Erreur d'identification!";
			$mes="<a href='index.php?view=login' class='btn btn-warning'>Recommencer</a>";
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