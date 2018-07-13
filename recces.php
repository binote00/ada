<?php
require_once('./jfv_inc_sessions.php');
$PlayerID =$_SESSION['PlayerID'];
if($PlayerID >0)
{
	$country =$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$ID =Insec($_GET['pilote']);
	$mes="<h1>Missions de reconnaissance</h1>
	<table class='table table-hover'>
		<thead><tr>
		<th>Date</th>
		<th>Unité</th>
		<th>Avion</th>
		<th>Cible photographiée</th>
		<th>Lieu</th></tr></thead>";
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT * FROM Recce WHERE Joueur='$ID' ORDER BY ID DESC");
	mysqli_close($con);
	if($result)
	{
		$num=mysqli_num_rows($result);
		if($num ==0)
			echo "<h6>Désolé, aucune mission réussie enregistrée à ce jour.</h6>";
		else
		{
			$i=0;
			while($i <$num) 
			{
				$Date=mysqli_result($result,$i,"Date");
				$Unit=mysqli_result($result,$i,"Unite");
				$Avion=mysqli_result($result,$i,"Avion");
				$Cible_detruite=mysqli_result($result,$i,"Nom");
				$Avion_win=GetData("Avion","ID",$Avion,"Nom");
				$Unite_win=GetData("Unit","ID",$Unit,"Nom");
				$Lieu=GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");				
				$Avion_img="images/avions/avion".$Avion.".gif";
				$Avion_unit_img="images/unit/unit".$Unit."p.gif";
				if(is_file($Avion_img))
					$Avion_win="<img src='".$Avion_img."' title='".$Avion_win."'>";
				if(is_file($Avion_unit_img))
					$Unite_win="<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
				if($ID ==$PlayerID)
					$mes.="<tr><td>".$Date."</td><td>".$Unite_win."</td><td>".$Avion_win."</td><td>".$Cible_detruite."</td><td>".$Lieu."</td></tr>";
				$i++;
			}
		}
		$mes.="</table></body>";
		include_once('./default_blank.php');
	}
	else
		echo "<h6>Désolé, aucune mission de reco réussie à ce jour.</h6>";
}
?>