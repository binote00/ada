<?php
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$Regiment=Insec($_POST['Reg']);
	$Cible=Insec($_POST['Cible']);
	$CT=Insec($_POST['CT']);
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");		
	if($Cible >0 and $Regiment >0)
	{
		if($Credits >=$CT and $CT >16)
		{
			UpdateData("Regiment","Experience",20,"ID",$Regiment);
			UpdateData("Officier","Avancement",20,"ID",$OfficierID);
			UpdateData("Officier","Reputation",10,"ID",$OfficierID);
			UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
			UpdateData("Lieu","QualitePiste",50,"ID",$Cible,100);
			$mes="Votre unité tente de réparer la piste de l'aérodrome";
		}
		else
			$mes="Vous n'avez pas le temps pour cela!";		
		$titre="Réparer";
		$img="<img src='images/bulldozer.jpg'>";
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}	
?>