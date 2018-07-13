<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Regi=Insec($_POST['Reg']);
	$Vehicule=Insec($_POST['Veh']);
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	if($Regi >0 and $Vehicule >4999 and $Credits >0)
	{
		$HP_max=GetData("Cible","ID",$Vehicule,"HP");
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment SET Vehicule_Nbr=Vehicule_Nbr-1,HP='$HP_max' WHERE ID='$Regi'");
		$reset2=mysqli_query($con,"UPDATE Officier SET Reputation=Reputation-10,Avancement=Avancement-10 WHERE ID='$OfficierID'");
		mysqli_close($con);		
		UpdateData("Officier","Credits",-1,"ID",$OfficierID);
		$mes="Vous sabordez un de vos navires!";
		$titre="Saborder";
		$img="<img src='images/saborder.jpg'>";
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}
?>