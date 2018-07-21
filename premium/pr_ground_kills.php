<?
/*require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$OfficierID=Insec($_POST['Off']);
	$Officier_eni=Insec($_POST['Off_eni']);	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,Pays,Front,Reputation,Avancement,Photo,Division FROM Officier WHERE ID='$OfficierID'");
	$result3=mysqli_query($con,"SELECT Nom,Pays,Front,Reputation,Avancement,Photo,Division FROM Officier WHERE ID='$Officier_eni'");
	mysqli_close($con);
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
			$Division=$data['Division'];
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
			$Division=$data['Division'];
		}
		mysqli_free_result($result3);
		unset($data);
	}
	$Grade=GetAvancement($Avancement,$Pays);
	$Grade_eni=GetAvancement($Avancement_eni,$Pays_eni);
	$Rep=GetReputOfficier($Reputation);
	$Rep_eni=GetReputOfficier($Reputation_eni);	
	echo "<table class='table table-striped'><thead><tr><th>Véhicule</th><th>".$Nom."</th><th>".$Nom_eni."</th></tr></thead>
	<tr><td></td><td><img src='images/grades/ranks".$Pays.$Grade[1].".png' title='".$Grade[0]."'> <img src='images/persos/general".$Pays.$Photo.".jpg' style='width:10%;'> <img title='".$Rep[0]."' src='images/general".$Rep[1].".png'></td>
	<td><img src='images/grades/ranks".$Pays_eni.$Grade_eni[1].".png' title='".$Grade_eni[0]."'> <img src='images/persos/general".$Pays_eni.$Photo_eni.".jpg' style='width:10%;'> <img title='".$Rep_eni[0]."' src='images/general".$Rep_eni[1].".png'></td></tr>";
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT ID,Nom FROM Cible WHERE Unit_ok=1 ORDER BY Type ASC, Categorie ASC, Nom ASC");
	mysqli_close($con);
	while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		$i=$data['ID'];
		$Nom_veh=$data['Nom'];
		$con=dbconnecti();
		$resultk=mysqli_result(mysqli_query($con,"SELECT SUM(g.Kills) FROM Ground_Cbt as g,Regiment as r WHERE g.Reg_a=r.ID AND r.Officier_ID='$OfficierID' AND g.Veh_b='$i'"),0);
		$resultk_eni=mysqli_result(mysqli_query($con,"SELECT SUM(g.Kills) FROM Ground_Cbt as g,Regiment as r WHERE g.Reg_a=r.ID AND r.Officier_ID='$Officier_eni' AND g.Veh_b='$i'"),0);
		mysqli_close($con);
		if($resultk >0 or $resultk_eni >0)
		{
			$result_total+=$resultk;
			$result_total_eni+=$resultk_eni;
			echo "<tr><td><img src='images/vehicules/vehicule".$i.".gif' title='".$Nom_veh."'></td><td>".$resultk."</td><td>".$resultk_eni."</td></tr>";
		}
	}
	mysqli_free_result($result);
	echo "<tfoot><tr><th>Total</th><th>".$result_total."</th><th>".$result_total_eni."</th></tr></tfoot></table>";
}*/
?>