<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Cible=Insec($_POST['Cible']);
	$CT=Insec($_POST['CT']);
	$Reg=Insec($_POST['Reg']);
	$Placement=Insec($_POST['Placement']);
	$country=Insec($_SESSION['country']);
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	if($Credits >=$CT and $CT >0 and $Cible >0 and $Reg >0 and $country >0)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment SET Visible=0 WHERE Lieu_ID='$Cible' AND Pays='$country' AND Placement='$Placement'");
		$reset2=mysqli_query($con,"UPDATE Regiment SET Visible=1,Stock_Munitions_150=0 WHERE ID='$Reg'");
		mysqli_close($con);				
		UpdateData("Officier","Avancement",10,"ID",$OfficierID);
		UpdateData("Officier","Reputation",10,"ID",$OfficierID);
		UpdateCarac($OfficierID,"Credits",-$CT,"Officier");		
		$mes="Vous tirez une salve de roquettes fumigènes!";
		$titre="Fumigènes";
		$img="<img src='images/nebel.jpg'>";
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}
?>