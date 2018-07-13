<?
require_once('./jfv_inc_sessions.php');
$OfficierID = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	$Off = Insec($_POST['Off']);
	$Trait_o = Insec($_POST['Trait_o']);
	$Skill = Insec($_POST['Skill']);
	if($Off and $Trait_o and $Skill)
	{
		$Skill="Skill".$Skill;
		SetData("Officier",$Skill,$Trait_o,"ID",$Off);
		$mes="Vous avez choisi la spécialisation de votre officier!";
		$img="<img src='images/spec_gen.jpg'>";
	}
	$titre="Choix de spécialisation";
	include_once('./default.php');
}
elseif($OfficierEMID > 0)
{
	$Off = Insec($_POST['Off']);
	$Trait_o = Insec($_POST['Trait_o']);
	$Skill = Insec($_POST['Skill']);
	if($Off and $Trait_o and $Skill)
	{
		$Skill="Skill".$Skill;
		SetData("Officier_em",$Skill,$Trait_o,"ID",$Off);
		$mes="Vous avez choisi la spécialisation de votre officier d'état-major!";
		$img="<img src='images/spec_gen.jpg'>";
	}
	$titre="Choix de spécialisation";
	include_once('./default.php');
}
?>