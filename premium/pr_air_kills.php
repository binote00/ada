<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$PlayerID=Insec($_POST['Off']);
	$Officier_eni=Insec($_POST['Off_eni']);	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,Pays,Front,Reputation,Avancement,Photo,Photo_Premium,Pilotage,Acrobatie,Bombardement,Commandement,Gestion,Navigation,Tactique,Tir,Vue,Missions FROM Pilote WHERE ID='$PlayerID'");
	$result3=mysqli_query($con,"SELECT Nom,Pays,Front,Reputation,Avancement,Photo,Photo_Premium,Pilotage,Acrobatie,Bombardement,Commandement,Gestion,Navigation,Tactique,Tir,Vue,Missions FROM Pilote WHERE ID='$Officier_eni'");
	$resulta=mysqli_query($con,"SELECT ID,Nom FROM Avion WHERE Etat=1 ORDER BY Type ASC, Nom ASC");
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Nom=$data['Nom'];
			$Pays=$data['Pays'];
			$Front=$data['Front'];
			$Reputation=$data['Reputation'];
			$Avancement=$data['Avancement'];
			$Photo=$data['Photo'];
			$Photo_Premium=$data['Photo_Premium'];
			$Pilotage=$data['Pilotage'];
			$Acrobatie=$data['Acrobatie'];
			$Bombardement=$data['Bombardement'];
			$Commandement=$data['Commandement'];
			$Gestion=$data['Gestion'];
			$Navigation=$data['Navigation'];
			$Tactique=$data['Tactique'];
			$Tir=$data['Tir'];
			$Vue=$data['Vue'];
			$Missions=$data['Missions'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($result3)
	{
		while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
		{
			$Nom_eni=$data['Nom'];
			$Pays_eni=$data['Pays'];
			$Front_eni=$data['Front'];
			$Reputation_eni=$data['Reputation'];
			$Avancement_eni=$data['Avancement'];
			$Photo_eni=$data['Photo'];
			$Photo_Premium_eni=$data['Photo_Premium'];
			$Pilotage_eni=$data['Pilotage'];
			$Acrobatie_eni=$data['Acrobatie'];
			$Bombardement_eni=$data['Bombardement'];
			$Commandement_eni=$data['Commandement'];
			$Gestion_eni=$data['Gestion'];
			$Navigation_eni=$data['Navigation'];
			$Tactique_eni=$data['Tactique'];
			$Tir_eni=$data['Tir'];
			$Vue_eni=$data['Vue'];
			$Missions_eni=$data['Missions'];
		}
		mysqli_free_result($result3);
		unset($data);
	}
	while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
	{
		$i=$data['ID'];
		$Nom_veh=$data['Nom'];
		$resultk=mysqli_result(mysqli_query($con,"SELECT COUNT(ID) FROM Chasse WHERE Joueur_win='$PlayerID' AND Avion_loss='$i' AND PVP<>1"),0);
		$resultk_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(ID) FROM Chasse WHERE Joueur_win='$Officier_eni' AND Avion_loss='$i' AND PVP<>1"),0);
		if($resultk >0 or $resultk_eni >0)
		{
			$result_total+=$resultk;
			$result_total_eni+=$resultk_eni;
			$avions_txt.="<tr><td><img src='images/avions/avion".$i.".gif' title='".$Nom_veh."'></td><td>".$resultk."</td><td>".$resultk_eni."</td></tr>";
		}
	}
	mysqli_free_result($resulta);
	mysqli_close($con);
	$Grade=GetAvancement($Avancement,$Pays);
	$Grade_eni=GetAvancement($Avancement_eni,$Pays_eni);
	$Rep=GetReputation($Reputation,$Pays);
	$Rep_eni=GetReputation($Reputation_eni,$Pays_eni);
	if($Photo_Premium ==1)
		$Photo="<img src='uploads/Pilote/".$PlayerID."_photo.jpg' style='width:20%;'>";
	else
		$Photo="<img src='images/persos/pilote".$Pays.$Photo.".jpg' style='width:20%;'>";
	if($Photo_Premium_eni ==1)
		$Photo_eni="<img src='uploads/Pilote/".$Officier_eni."_photo.jpg' style='width:20%;'>";
	else
		$Photo_eni="<img src='images/persos/pilote".$Pays_eni.$Photo_eni.".jpg' style='width:20%;'>";
	echo "<h1>Comparatif pilotes</h1><table class='table'><thead><tr><th>Caractéristiques</th><th>".$Nom."</th><th>".$Nom_eni."</th></tr></thead>
	<tr><td></td><td><img src='images/grades/grades".$Pays.$Grade[1].".png' title='".$Grade[0]."'> ".$Photo."</td>
	<td><img src='images/grades/grades".$Pays_eni.$Grade_eni[1].".png' title='".$Grade_eni[0]."'> ".$Photo_eni."</td></tr>";
	if($Missions > $Missions_eni)
		echo "<tr><th>Score</th><td bgcolor='lightgreen'>".round($Missions)."</td><td bgcolor='LightCoral'>".round($Missions_eni)."</td></tr>";
	elseif($Missions_eni > $Missions)
		echo "<tr><th>Score</th><td bgcolor='LightCoral'>".round($Missions)."</td><td bgcolor='lightgreen'>".round($Missions_eni)."</td></tr>";
	else
		echo "<tr><th>Score</th><td bgcolor='lightyellow'>".round($Missions)."</td><td bgcolor='lightyellow'>".round($Missions_eni)."</td></tr>";
	if($Avancement > $Avancement_eni)
		echo "<tr><th>Avancement</th><td bgcolor='lightgreen'>".$Grade[0]."</td><td bgcolor='LightCoral'>".$Grade_eni[0]."</td></tr>";
	elseif($Avancement_eni > $Avancement)
		echo "<tr><th>Avancement</th><td bgcolor='LightCoral'>".$Grade[0]."</td><td bgcolor='lightgreen'>".$Grade_eni[0]."</td></tr>";
	else
		echo "<tr><th>Avancement</th><td bgcolor='lightyellow'>".$Grade[0]."</td><td bgcolor='lightyellow'>".$Grade_eni[0]."</td></tr>";
	if($Reputation > $Reputation_eni)
		echo "<tr><th>Reputation</th><td bgcolor='lightgreen'>".$Rep."</td><td bgcolor='LightCoral'>".$Rep_eni."</td></tr>";
	elseif($Reputation_eni > $Reputation)
		echo "<tr><th>Reputation</th><td bgcolor='LightCoral'>".$Rep."</td><td bgcolor='lightgreen'>".$Rep_eni."</td></tr>";
	else
		echo "<tr><th>Reputation</th><td bgcolor='lightyellow'>".$Rep."</td><td bgcolor='lightyellow'>".$Rep_eni."</td></tr>";
	/*if($Acrobatie > $Acrobatie_eni)
		echo "<tr><th>Acrobatie</th><td bgcolor='lightgreen'>".round($Acrobatie)."</td><td bgcolor='LightCoral'>".round($Acrobatie_eni)."</td></tr>";
	elseif($Acrobatie_eni > $Acrobatie)
		echo "<tr><th>Acrobatie</th><td bgcolor='LightCoral'>".round($Acrobatie)."</td><td bgcolor='lightgreen'>".round($Acrobatie_eni)."</td></tr>";
	else
		echo "<tr><th>Acrobatie</th><td bgcolor='lightyellow'>".round($Acrobatie)."</td><td bgcolor='lightyellow'>".round($Acrobatie_eni)."</td></tr>";
	if($Bombardement > $Bombardement_eni)
		echo "<tr><th>Bombardement</th><td bgcolor='lightgreen'>".round($Bombardement)."</td><td bgcolor='LightCoral'>".round($Bombardement_eni)."</td></tr>";
	elseif($Bombardement_eni > $Bombardement)
		echo "<tr><th>Bombardement</th><td bgcolor='LightCoral'>".round($Bombardement)."</td><td bgcolor='lightgreen'>".round($Bombardement_eni)."</td></tr>";
	else
		echo "<tr><th>Bombardement</th><td bgcolor='lightyellow'>".round($Bombardement)."</td><td bgcolor='lightyellow'>".round($Bombardement_eni)."</td></tr>";
	if($Commandement > $Commandement_eni)
		echo "<tr><th>Commandement</th><td bgcolor='lightgreen'>".round($Commandement)."</td><td bgcolor='LightCoral'>".round($Commandement_eni)."</td></tr>";
	elseif($Commandement_eni > $Commandement)
		echo "<tr><th>Commandement</th><td bgcolor='LightCoral'>".round($Commandement)."</td><td bgcolor='lightgreen'>".round($Commandement_eni)."</td></tr>";
	else
		echo "<tr><th>Commandement</th><td bgcolor='lightyellow'>".round($Commandement)."</td><td bgcolor='lightyellow'>".round($Commandement_eni)."</td></tr>";
	if($Vue > $Vue_eni)
		echo "<tr><th>Détection</th><td bgcolor='lightgreen'>".round($Vue)."</td><td bgcolor='LightCoral'>".round($Vue_eni)."</td></tr>";
	elseif($Vue_eni > $Vue)
		echo "<tr><th>Détection</th><td bgcolor='LightCoral'>".round($Vue)."</td><td bgcolor='lightgreen'>".round($Vue_eni)."</td></tr>";
	else
		echo "<tr><th>Détection</th><td bgcolor='lightyellow'>".round($Vue)."</td><td bgcolor='lightyellow'>".round($Vue_eni)."</td></tr>";
	if($Gestion > $Gestion_eni)
		echo "<tr><th>Gestion</th><td bgcolor='lightgreen'>".round($Gestion)."</td><td bgcolor='LightCoral'>".round($Gestion_eni)."</td></tr>";
	elseif($Gestion_eni > $Gestion)
		echo "<tr><th>Gestion</th><td bgcolor='LightCoral'>".round($Gestion)."</td><td bgcolor='lightgreen'>".round($Gestion_eni)."</td></tr>";
	else
		echo "<tr><th>Gestion</th><td bgcolor='lightyellow'>".round($Gestion)."</td><td bgcolor='lightyellow'>".round($Gestion_eni)."</td></tr>";
	if($Navigation > $Navigation_eni)
		echo "<tr><th>Navigation</th><td bgcolor='lightgreen'>".round($Navigation)."</td><td bgcolor='LightCoral'>".round($Navigation_eni)."</td></tr>";
	elseif($Navigation_eni > $Navigation)
		echo "<tr><th>Navigation</th><td bgcolor='LightCoral'>".round($Navigation)."</td><td bgcolor='lightgreen'>".round($Navigation_eni)."</td></tr>";
	else
		echo "<tr><th>Navigation</th><td bgcolor='lightyellow'>".round($Navigation)."</td><td bgcolor='lightyellow'>".round($Navigation_eni)."</td></tr>";
	if($Pilotage > $Pilotage_eni)
		echo "<tr><th>Pilotage</th><td bgcolor='lightgreen'>".round($Pilotage)."</td><td bgcolor='LightCoral'>".round($Pilotage_eni)."</td></tr>";
	elseif($Pilotage_eni > $Pilotage)
		echo "<tr><th>Pilotage</th><td bgcolor='LightCoral'>".round($Pilotage)."</td><td bgcolor='lightgreen'>".round($Pilotage_eni)."</td></tr>";
	else
		echo "<tr><th>Pilotage</th><td bgcolor='lightyellow'>".round($Pilotage)."</td><td bgcolor='lightyellow'>".round($Pilotage_eni)."</td></tr>";
	if($Tactique > $Tactique_eni)
		echo "<tr><th>Tactique</th><td bgcolor='lightgreen'>".round($Tactique)."</td><td bgcolor='LightCoral'>".round($Tactique_eni)."</td></tr>";
	elseif($Tactique_eni > $Tactique)
		echo "<tr><th>Tactique</th><td bgcolor='LightCoral'>".round($Tactique)."</td><td bgcolor='lightgreen'>".round($Tactique_eni)."</td></tr>";
	else
		echo "<tr><th>Tactique</th><td bgcolor='lightyellow'>".round($Tactique)."</td><td bgcolor='lightyellow'>".round($Tactique_eni)."</td></tr>";
	if($Tir > $Tir_eni)
		echo "<tr><th>Tir</th><td bgcolor='lightgreen'>".round($Tir)."</td><td bgcolor='LightCoral'>".round($Tir_eni)."</td></tr>";
	elseif($Tir_eni > $Tir)
		echo "<tr><th>Tir</th><td bgcolor='LightCoral'>".round($Tir)."</td><td bgcolor='lightgreen'>".round($Tir_eni)."</td></tr>";
	else
		echo "<tr><th>Tir</th><td bgcolor='lightyellow'>".round($Tir)."</td><td bgcolor='lightyellow'>".round($Tir_eni)."</td></tr>";*/
	echo "</table><table class='table table-striped'><thead><tr><th>Avions</th><th>".$Nom."</th><th>".$Nom_eni."</th></tr>";
	echo $avions_txt;
	echo "<tfoot><tr><th>Total</th><th>".$result_total."</th><th>".$result_total_eni."</th></tr></tfoot></table>";
}
?>