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
		/*UpdateData("Regiment","Experience",25,"ID",$Regiment);
		UpdateData("Officier","Avancement",25,"ID",$OfficierID);*/
		UpdateData($DB,"Avancement",5,"ID",$OfficierID);
		UpdateData($DB,"Credits",-4,"ID",$OfficierID);
		$con=dbconnecti();
		$reset1=mysqli_query($con,"UPDATE Regiment_IA SET Camouflage=1,Position=0,Move=1 WHERE ID='$Regiment'");
		$reset2=mysqli_query($con,"UPDATE Lieu SET Pont=0 WHERE ID='$Cible'");
		mysqli_close($con);
		/*$titre="Faire sauter le pont";
		$img="<img src='images/exploser_pont.jpg'>";
		$mes="Votre unité fait sauter le pont permettant de franchir le cours d'eau";
		if($OfficierEMID)
			$menu="<a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour au menu</a>
			<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Regiment."'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		else
			$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');*/
        $_SESSION['reg'] = $Reg;
        $_SESSION['msg'] = 'Votre unité fait sauter le pont permettant de franchir le cours d\'eau';
        header( 'Location : index.php?view=ground_em_ia');
	}
	else
		echo "Tsss";
}
?>