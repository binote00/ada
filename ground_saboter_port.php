<?php
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 xor $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Regiment=Insec($_POST['Reg']);
	$Cible=Insec($_POST['Cible']);
	if($OfficierEMID)
	{
		$DB="Officier_em";
		$OfficierID=$OfficierEMID;
	}
	else
		$DB="Officier";
	$Credits=GetData($DB,"ID",$OfficierID,"Credits");		
	if($Cible >0 and $Regiment >0 and $Credits >=4)
	{
		//UpdateData("Officier","Avancement",25,"ID",$OfficierID);
		UpdateData("Officier","Credits",-4,"ID",$OfficierID);
		UpdateData("Lieu","Port",-25,"ID",$Cible);
		$con=dbconnecti();
		$reset1=mysqli_query($con,"UPDATE Regiment_IA SET Camouflage=1,Position=0,Move=1 WHERE ID='$Regiment'");
		mysqli_close($con);
		//AddEvent("Avion",131,25,$OfficierID,$Regiment,$Cible,$country);		
		$img="<img src='images/exploser_port.jpg'>";
		$mes="Votre unité sabote les infrastructures portuaires";
		$titre="Sabotage";
		if($OfficierEMID)
			$menu="<a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour au menu</a>
			<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Regiment."'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		else
			$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}	
?>