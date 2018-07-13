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
	$Credits_mine=8;
	if($OfficierEMID)
	{
		$DB="Officier_em";
		$OfficierID=$OfficierEMID;
	}
	else
	{
		$DB="Officier";
		$Trait_o=GetData("Officier","ID",$OfficierID,"Trait");
		if($Trait_o ==17)$Credits_mine-=2;
	}
	$Credits=GetData($DB,"ID",$OfficierID,"Credits");
	if($Cible >0 and $Regiment and $Credits >=$Credits_mine)
	{
		/*UpdateData("Regiment","Experience",25,"ID",$Regiment);
		UpdateData("Officier","Avancement",25,"ID",$OfficierID);
		UpdateData("Officier","Reputation",25,"ID",$OfficierID);*/
		$con=dbconnecti();
		$Placement=mysqli_result(mysqli_query($con,"SELECT Placement FROM Regiment_IA WHERE ID='$Regiment'"),0);
		$Mines=mysqli_result(mysqli_query($con,"SELECT Qty FROM Mines WHERE Lieu_ID='$Cible' AND Zone='$Placement'"),0);
		if($Mines)
		{
			if($Mines <=10)
			{
				$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
				if($Faction ==2)
					$Detect_field="Detect_Allie";
				else
					$Detect_field="Detect_Axe";
				$Reset2_append=",".$Detect_field."=0";
			}
			$reset1=mysqli_query($con,"UPDATE Regiment_IA SET Camouflage=1,Position=0,Move=1,Move_time=NOW() WHERE ID='$Regiment'");
			$reset2=mysqli_query($con,"UPDATE Mines SET Qty=0 WHERE Lieu_ID='$Cible' AND Zone='$Placement'");
			if($reset2)
    			$msg_clear=", mais il semblerait qu'elle ne soit pas complètement sécurisée.";
			else
                $msg_clear=", mais il semblerait qu'elle ne soit pas totalement sécurisée.";
		}
		else
			$msg_clear="<br>La zone semble sécurisée!";
		mysqli_close($con);
		UpdateData($DB,"Avancement",10,"ID",$OfficierID);
		UpdateData($DB,"Credits",-$Credits_mine,"ID",$OfficierID);
		$titre="Déminage";
		$img="<img src='images/deminer.jpg'>";
		$mes="Votre unité a déminé la zone ".GetPlace($Placement).$msg_clear;
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