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
	if(!$CT)$CT=2;
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");		
	if($Cible >0 and $Regiment >0 and $Para >0 and $Credits >=$CT)
	{
		/*UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
		UpdateData("Regiment","Experience",5,"ID",$Regiment);
		UpdateData("Officier","Avancement",5,"ID",$OfficierID);
		UpdateData("Officier","Reputation",5,"ID",$OfficierID);
		AddEvent("Avion",177,1,$OfficierID,$Regiment,$Cible,$country);
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Lieu SET Para=0 WHERE ID='$Cible'");
		mysqli_close($con);*/		
		$img="<img src='images/police.jpg'>";
		$mes="Votre unité capture ".$Paras." saboteurs ennemis!";
		$titre="Police";
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}	
?>