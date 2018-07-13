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
	$Division=Insec($_POST['Div']);
	$Cible=Insec($_POST['Cible']);
	if($OfficierID)
		$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	else
		$Credits=GetData("Officier_em","ID",$OfficierEMID,"Credits");
	if($Cible >0 and $Division >0 and $Credits >=8)
	{
		if($OfficierID)
		{
			UpdateData("Officier","Credits",-8,"ID",$OfficierID);
			AddEvent("Avion",135,1,$OfficierID,$Division,$Cible,$country);
			$mes="Votre unité brûle le dépôt";
			$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		}
		else
		{
			UpdateData("Officier_em","Credits",-8,"ID",$OfficierEMID);
			AddEvent("Avion",136,1,$OfficierEMID,$Division,$Cible,$country);
			$mes="Vous donnez l'ordre de brûler le dépôt";
			$menu="<a href='index.php?view=em_depots' class='btn btn-default' title='Retour'>Retour au menu</a>";
		}
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Lieu SET Stock_Essence_1=0,Stock_Essence_87=0,Stock_Essence_100=0,
		Stock_Bombes_30=0,Stock_Bombes_50=0,Stock_Bombes_80=0,Stock_Bombes_125=0,Stock_Bombes_250=0,Stock_Bombes_300=0,Stock_Bombes_400=0,Stock_Bombes_500=0,Stock_Bombes_800=0,Stock_Bombes_1000=0,Stock_Bombes_2000=0,
		Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
		Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0
		WHERE ID='$Cible'");
		mysqli_close($con);		
		$img="<img src='images/exploser_gare.jpg'>";
		$titre="Sabotage";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}	
?>