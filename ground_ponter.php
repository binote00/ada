<?php
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 xor $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$Regiment=Insec($_POST['Reg']);
	$Cible=Insec($_POST['Cible']);
	$Credits_mine=4;
	if($OfficierEMID)
	{
		$DB='Officier_em';
		$OfficierID=$OfficierEMID;
	}
	else
	{
		$DB='Officier';
		$Trait_o=GetData("Officier","ID",$OfficierID,"Trait");
		if($Trait_o ==17)$Credits_mine-=2;
	}
	$Credits=GetData($DB,"ID",$OfficierID,"Credits");
	if($Cible >0 and $Regiment >0 and $Credits >=$Credits_mine)
	{
		/*UpdateData("Regiment","Experience",25,"ID",$Regiment);
		UpdateData("Officier","Avancement",25,"ID",$OfficierID);
		UpdateData("Officier","Reputation",25,"ID",$OfficierID);*/
		UpdateData($DB,"Avancement",5,"ID",$OfficierID);
		UpdateData($DB,"Credits",-$Credits_mine,"ID",$OfficierID);
		UpdateData("Lieu","Pont",10,"ID",$Cible,100);
		$con=dbconnecti();
		$reset1=mysqli_query($con,"UPDATE Regiment_IA SET Camouflage=1,Position=0,Move=1 WHERE ID='$Regiment'");
		mysqli_close($con);
		$titre='Ponter';
		$img="<img src='images/ponter.jpg'>";
		$mes='Votre unité a construit un pont de fortune permettant de franchir le cours d\'eau';
		if($OfficierEMID)
			$menu="<a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour au menu</a>
			<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Regiment."'><input type='submit' value='Retour unité' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		else
			$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo 'Tsss';
}