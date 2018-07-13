<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_infos.php');
	$country=$_SESSION['country'];
	$Base=Insec($_POST['Base']);
	if($Base)
	{
		$Unite_Type=Insec($_POST['Unit']);		
		$Lat_base=GetData("Lieu","ID",$Base,"Latitude");
		$Long_base=GetData("Lieu","ID",$Base,"Longitude");				
		/*if($Unite_Type ==6 or $Unite_Type ==9)
		{
			$Lat_base_min=$Lat_base -3.00;
			$Lat_base_max=$Lat_base +3.00;
			$Long_base_min=$Long_base -4.50;
			$Long_base_max=$Long_base +4.50;
		}
		else
		{
			$Lat_base_min=$Lat_base -1.00;
			$Lat_base_max=$Lat_base +1.00;
			$Long_base_min=$Long_base -1.50;
			$Long_base_max=$Long_base +1.50;
		}*/		
		if($Unite_Type ==9)
		{
			/*$result=mysqli_query($con,"SELECT ID,Nom,Longitude,Latitude FROM Lieu WHERE Occupant='$country' AND Zone<>6 AND ID<>'$Base' AND Port_Ori >0
			AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
			AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') 
			ORDER BY Nom ASC");*/
			$query="SELECT ID,Nom,Longitude,Latitude FROM Lieu WHERE Flag='$country' AND Zone<>6 AND ID<>'$Base' AND Port_Ori >0 ORDER BY Nom ASC";
		}
		else
		{
			$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
			$Lands=GetAllies($Date_Campagne);
			if(IsAxe($country))
				$pays_allies=$Lands[1];
			else
				$pays_allies=$Lands[0];
			/*$result=mysqli_query($con,"SELECT ID,Nom,Longitude,Latitude FROM Lieu WHERE Occupant IN (".$pays_allies.")
			AND Zone<>6 AND ID<>'$Base' AND QualitePiste > 49 AND Tour > 49
			AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
			AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') 
			ORDER BY Nom ASC");*/
			$query="SELECT ID,Nom,Longitude,Latitude FROM Lieu WHERE Flag IN (".$pays_allies.")
			AND Zone<>6 AND ID<>'$Base' AND QualitePiste > 49 AND Tour > 49 ORDER BY Nom ASC";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			if($Unite_Type ==6 or $Unite_Type ==9)
				$Limite=500;
			else
				$Limite=300;
			while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
			{
				$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data[2],$data[3]);
				if($Dist[0] <$Limite)
					$dest_move.="<br>".$data[1]." (".$Dist[0]."km)";
			}
			mysqli_free_result($result);
			unset($data);
		}
		else
			$dest_move="Aucune destination à portée";
		echo "<h2>Destinations possibles</h2>".$dest_move;
	}
	else
	{
		if($PlayerID ==1)
			$query2="SELECT DISTINCT ID,Nom FROM Lieu ORDER BY Nom ASC";
		else
			$query2="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' ORDER BY Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query2);
		mysqli_close($con);
		if($result)
		{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
				{
					$Lieux.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
				}
		}
		mysqli_free_result($result);
?>
	<h2>Simulation de déplacement d'unité aérienne</h2>
	<form action="index.php?view=pr_demenager" method="post">
	<table class='table'><thead><tr><th>Base de départ</th><th>Type d'unité</th></tr></thead>
			<tr><td>
				<select name='Base' class='form-control' style='width: 200px'>
					<?echo $Lieux;?>
				</select>
			</td>
			<td><select name='Unit' class='form-control' style='width: 200px'>
					<option value='0'>Autre</option>
					<option value='9'>Patrouille Maritime</option>
					<option value='6'>Transport</option>
				</select>
			</td></tr>
	</table>
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?
	}
}?>