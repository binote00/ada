<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Reg=Insec($_POST['Reg']);
	$Dist=Insec($_POST['distance']);
	$Fire=Insec($_POST['fire']);
	if($Reg >0)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment SET Distance='$Dist',Fire='$Fire' WHERE ID='$Reg'");
		mysqli_close($con);		
		if($Fire ==1)
			$Fire="Toujours riposter";
		else
			$Fire="Ne pas riposter à distance supérieure";	
		$mes="Vous donnez l'ordre à votre ".$Reg."e Cie de riposter à une distance de ".$Dist." mètres et de <b>".$Fire."</b>";
		$titre="Consignes défensives";
		$img="<img src='images/range.jpg'>";
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}
?>