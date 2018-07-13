<?php
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0 or $OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$Reg=Insec($_POST['Reg']);
	$Cible=Insec($_POST['Cible']);
	$Credits_mine=4;
	if($OfficierEMID)
		$Credits=GetData("Officier_em","ID",$OfficierEMID,"Credits");
	else
		$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	if($Cible >0 and $Reg and $Credits >=$Credits_mine)
	{
		if(IsAxe($country))
			$Recce_field="Recce_mines_m_ax";
		else
			$Recce_field="Recce_mines_m_al";
		$con=dbconnecti();
		if($OfficierEMID)
			$up1=mysqli_query($con,"UPDATE Officier_em SET Avancement=Avancement+5,Credits=Credits-".$Credits_mine." WHERE ID='$OfficierEMID'");
		else
			$up1=mysqli_query($con,"UPDATE Officier SET Avancement=Avancement+5,Credits=Credits-".$Credits_mine." WHERE ID='$OfficierID'");
		$up2=mysqli_query($con,"UPDATE Lieu SET Mines_m=Mines_m+10,".$Recce_field."=1 WHERE ID='$Cible'");
		$up3=mysqli_query($con,"UPDATE Regiment_IA SET Position=4,Camouflage=1,Move=1,Experience=Experience+1 WHERE ID='$Reg'");
		$Cible_Nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Lieu WHERE ID='$Cible'"),0);
		mysqli_close($con);
		$titre="Pose de mines";
		$img="<img src='images/minage.jpg'>";
		$mes="Votre unité établi un champ de mines au large de ".$Cible_Nom;
		$menu="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}
/*elseif($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country = $_SESSION['country'];
	$Regiment = Insec($_POST['Reg']);
	$Cible = Insec($_POST['Cible']);
	$Credits_mine=24;
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	$Trait_o=GetData("Officier","ID",$OfficierID,"Trait");
	if($Trait_o ==17)$Credits_mine -=4;
	if($Cible >0 and $Regiment and $Credits >=$Credits_mine)
	{
		UpdateData("Regiment","Experience",10,"ID",$Regiment);
		UpdateData("Regiment","Stock_Mines",-10,"ID",$Regiment);
		UpdateData("Officier","Avancement",10,"ID",$OfficierID);
		UpdateCarac($OfficierID,"Credits",-$Credits_mine,"Officier");
		SetData("Lieu","Mines_m",1,"ID",$Cible);		
		$titre="Pose de mines";
		$img="<img src='images/minage.jpg'>";
		$mes="Votre unité établi un champ de mines au large de ".GetData("Lieu","ID",$Cible,"Nom");
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}*/
?>