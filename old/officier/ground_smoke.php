<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Cible=Insec($_POST['Cible']);
	$CT=Insec($_POST['CT']);
	$Reg=Insec($_POST['Reg']);
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	if($Credits >=$CT and $CT >0 and $Cible >0 and $Reg >0)
	{
		$con=dbconnecti();		
		$result=mysqli_query($con,"SELECT BaseAerienne,Port_Ori,Pont_Ori,NoeudR,NoeudF_Ori,TypeIndus,Radar_Ori FROM Lieu WHERE ID='$Cible'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				if($data['BaseAerienne'])
					$choix="<Input type='Radio' name='Placement' value='1'>- Aérodrome.<br>";
				if($data['NoeudR'])
					$choix.="<Input type='Radio' name='Placement' value='2'>- Route.<br>";
				if($data['NoeudF_Ori'])
					$choix.="<Input type='Radio' name='Placement' value='3'>- Gare.<br>";
				if($data['Port_Ori'])
					$choix.="<Input type='Radio' name='Placement' value='4'>- Port.<br>";
				if($data['Pont_Ori'])
					$choix.="<Input type='Radio' name='Placement' value='5'>- Pont.<br>";
				if($data['TypeIndus'])
					$choix.="<Input type='Radio' name='Placement' value='6'>- Usine.<br>";
				if($data['Radar_Ori'])
					$choix.="<Input type='Radio' name='Placement' value='7'>- Radar.<br>";
			}
			mysqli_free_result($result);
		}	
		$mes="Vous vous préparez à tirer une salve de roquettes fumigènes!";	
		$titre="Fumigènes";
		$img="<img src='images/nebel.jpg'>";
		$menu="<form action='index.php?view=ground_smoke1' method='post'>
			<input type='hidden' name='CT' value='".$CT."'>
			<input type='hidden' name='Reg' value='".$Reg."'>
			<input type='hidden' name='Cible' value='".$Cible."'>
			<table class='table'><thead><tr><th>Zone visée</th></tr></thead><tr><td>
			<Input type='Radio' name='Placement' value='10'>- Caserne.<br>".$choix."
			<Input type='Radio' name='Placement' value='0' checked>- Annuler l'attaque.<br></td></tr>
			</table><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}
?>