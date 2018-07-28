<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{	
	include_once('./jfv_include.inc.php');
	$Regiment=Insec($_POST['Reg']);
	$Vehicule=Insec($_POST['Veh']);
	$Lieu=Insec($_POST['Cible']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Zone,Meteo FROM Lieu WHERE ID='$Lieu'");
	$result2=mysqli_query($con,"SELECT Distance,Fire FROM Regiment WHERE ID='$Regiment'");
	mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Distance_ori=$data['Distance'];
			$Fire_ori=$data['Fire'];
		}
		mysqli_free_result($result2);
	}	
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Zone=$data['Zone'];
			$meteo=$data['Meteo'];
		}
		mysqli_free_result($result);
	}
	$Range=GetData("Cible","ID",$Vehicule,"Portee");		
	$choix_dist="";
	if($Zone ==6)
	{
		$Step=500;
		if($meteo <-69)
			$Max_Range=5000;
		elseif($meteo <-9)
			$Max_Range=10000;
		else
			$Max_Range=20000;					
	}
	elseif($Zone ==2 or $Zone ==3 or $Zone ==5 or $Zone ==7 or $Zone ==9 or $Zone ==11)
	{
		$Step=100;
		if($meteo <-69)
			$Max_Range=200;
		else
			$Max_Range=500;
	}
	elseif($Zone ==1 or $Zone ==4)
	{
		$Step=100;
		if($meteo <-69)
			$Max_Range=500;
		elseif($meteo <-9)
			$Max_Range=700;
		else
			$Max_Range=1000;
	}
	else //Zone 0 et 8 (désert et plaine)
	{
		$Step=100;
		if($meteo <-69)
			$Max_Range=500;
		elseif($meteo <-9)
			$Max_Range=1500;
		else
			$Max_Range=2500;					
	}
	if($Range >0 and $Max_Range >$Range)
		$Max_Range=$Range;
	for($i=$Step;$i<=$Max_Range;$i+=$Step)
	{
		if($i >$Max_Range)
			break;
		$choix_dist.="<option value='".$i."'>".$i."m</option>";
	}
	$Distance_tir="<tr><th>Distance de riposte 
		<select name='distance' style='width: 100px'>".$choix_dist."</select></th></tr>";
	$Fire="<tr><th>Tir 
	<select name='fire' style='width: 200px'>
	<option value='0'>Ne pas riposter à distance supérieure</option>
	<option value='1'>Toujours riposter</option>
	</select></th></tr>";
	if($Fire_ori ==1)
		$Fire_ori="Toujours riposter";
	else
		$Fire_ori="Ne pas riposter à distance supérieure";				
	$mes.="<p>Vous pouvez déterminer ici la distance à laquelle vos troupes riposteront si elles sont attaquées  <a href='index.php?view=aide_blitz#tab_atk' title='Aide'><img src='images/help.png'></a>
	<br>La distance actuelle est de <b>".$Distance_ori." m</b> et la consigne est de <b>".$Fire_ori."</b></p>";
	$titre="Consignes défensives";
	$img="<img src='images/range.jpg'>";
	$menu="<form action='index.php?view=ground_consignes1' method='post'>
		<input type='hidden' name='Reg' value='".$Regiment."'>
		<table class='table'>".$Distance_tir.$Fire."</table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	include_once('./default.php');
}
?>