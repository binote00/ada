<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	$Pilote=Insec($_POST['Pil']);
	$Trait=Insec($_POST['Spec']);
	$country=$_SESSION['country'];
	if($Pilote and $Trait)
	{
		if($Pilote >0)
			SetData("Pilote","Skill_Cat",$Trait,"ID",$Pilote);
		$mes="Vous avez choisi la spécialisation de votre pilote!";
	}
	$titre="Choix de spécialisation";
	$img=Afficher_Image("images/instruction".$country.".jpg","","",50);
	$menu="<a href='index.php?view=user' class='btn btn-default' title='Retour'>Retour au profil</a>";
	include_once('./default.php');
}
?>