<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0 xor $OfficierID)
{
	if($OfficierID)
	{
		$Officier=$OfficierID;
		$DB="Officier_em";
	}
	else
	{
		$Officier=$OfficierEMID;
		$DB="Officier_em";
	}
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$Reg=Insec($_POST['Reg']);
	$Cible=Insec($_POST['Cible']);
	$Credits_mine=8;
	$con=dbconnecti();
	$Credits=mysqli_result(mysqli_query($con,"SELECT Credits FROM $DB WHERE ID='$Officier'"),0);
	$result=mysqli_query($con,"SELECT Nom,Mines_m FROM Lieu WHERE ID='$Cible'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Cible_Nom=$data['Nom'];
			$Mines_m=$data['Mines_m'];
		}
		mysqli_free_result($result);
	}
	if($Cible >0 and $Reg)
	{
		if(!$Mines_m)
			$mes="Votre unité ne trouve aucune mine au large de ".$Cible_Nom."!";
		elseif($Credits >=$Credits_mine)
		{
			$con=dbconnecti();
			$up3=mysqli_query($con,"UPDATE Regiment_IA SET Position=4,Camouflage=1,Move=1,Experience=Experience+1 WHERE ID='$Reg'");
			$up1=mysqli_query($con,"UPDATE $DB SET Avancement=Avancement+10 WHERE ID='$Officier'");
			UpdateData('Lieu','Mines_m',-20, 'ID', $Cible);
			//$up2=mysqli_query($con,"UPDATE Lieu SET Mines_m=Mines_m-20 WHERE ID='$Cible'");
			$Mines_m_final=mysqli_result(mysqli_query($con,"SELECT Mines_m FROM Lieu WHERE ID='$Cible'"),0);
			if(!$Mines_m_final)
				$up3=mysqli_query($con,"UPDATE Lieu SET Recce_mines_m_ax=0,Recce_mines_m_al=0 WHERE ID='$Cible'");
			mysqli_close($con);
			$mes="Votre unité a déminé un champ de mines au large de ".$Cible_Nom;
		}
		UpdateCarac($Officier,"Credits",-$Credits_mine,$DB);
		$titre="Déminage";
		$img="<img src='images/nav_demine.jpg'>";
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
	$country=$_SESSION['country'];
	$Regiment=Insec($_POST['Reg']);
	$Cible=Insec($_POST['Cible']);
	$Credits_mine=40;
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	$Trait_o=GetData("Officier","ID",$OfficierID,"Trait");
	if($Trait_o ==17)$Credits_mine-=4;		
	if($Cible >0 and $Regiment and $Credits >=$Credits_mine)
	{
		UpdateData("Regiment","Experience",25,"ID",$Regiment);
		UpdateData("Officier","Avancement",25,"ID",$OfficierID);
		UpdateData("Officier","Reputation",25,"ID",$OfficierID);
		UpdateData("Officier","Credits",-$Credits_mine,"ID",$OfficierID);
		SetData("Lieu","Mines_m",0,"ID",$Cible);		
		$titre="Déminage";
		$img="<img src='images/deminer.jpg'>";
		$mes="Votre unité a déminé l'accès au port!";
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}*/	
?>