<?php
/*require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{	
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin)
	{
		$units='';
		if($Admin)
		{
			$query="SELECT r.*,c.Nom as Cible,l.Nom as ville,o.Nom as leader,o.Division,o.Credits_Date,o.Rapport FROM Regiment as r,Cible as c,Lieu as l,Officier as o 
			WHERE r.Lieu_ID=l.ID AND r.Officier_ID=o.ID AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0
			ORDER BY o.Credits_Date ASC,o.Division ASC, r.Lieu_ID ASC, r.Placement ASC, r.ID ASC";
		}
		elseif($GHQ)
		{
			$query="SELECT r.*,c.Nom as Cible,l.Nom as ville,o.Nom as leader,o.Division,o.Credits_Date,o.Rapport FROM Regiment as r,Cible as c,Lieu as l,Officier as o 
			WHERE r.Lieu_ID=l.ID AND r.Officier_ID=o.ID AND r.Vehicule_ID=c.ID 
			AND r.Pays='$country' AND r.Vehicule_Nbr >0 ORDER BY o.Division ASC,r.Lieu_ID ASC, r.Placement ASC, r.ID ASC";
		}
		else
		{
			$query="SELECT r.*,c.Nom as Cible,l.Nom as ville,o.Nom as leader,o.Division,o.Credits_Date,o.Rapport FROM Regiment as r,Cible as c,Lieu as l,Officier as o 
			WHERE r.Lieu_ID=l.ID AND r.Officier_ID=o.ID AND r.Vehicule_ID=c.ID 
			AND r.Pays='$country' AND o.Front='$Front' AND r.Vehicule_Nbr >0 ORDER BY o.Division ASC,r.Lieu_ID ASC, r.Placement ASC, r.ID ASC";
		}
		$con=dbconnecti();
		$Date_Courante=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=1"),0);
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result))
			{
				$rav_txt='';
				$Placement=GetPlace($data['Placement']);
				if($data['Division'] >0)
					$Division="<img src='images/div/div".$data['Division'].".png'>";
				else
					$Division="";
				$units.="<tr><td align='left'>".$data['ID']."e Compagnie</td>
				<td><img src='".$data['Pays']."20.gif'></td>
				<td>".$data['ville']."</td>
				<td>".$Placement."</td>
				<td>".GetPosGr($data['Position'])."</td>
				<td>".$data['Vehicule_Nbr']."</td>
				<td><img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Cible']."'></td>
				<td>".$data['leader']."</td>
				<td>".$Division."</td>
				<td>".$data['Credits_Date']."</td>
				<td>".$data['Rapport']."</td>
				</tr>";
				if($data['Rapport'] ==$Date_Courante)
				{
					$rav_txt="<tr><td></td><td colspan='10'><table class='table'><tr>";
					if($data['Stock_Essence_87'] >0)
						$rav_txt.="<td><b>Essence</b> ".$data['Stock_Essence_87']."</td>";
					if($data['Stock_Essence_1'] >0)
						$rav_txt.="<td><b>Diesel</b> ".$data['Stock_Essence_1']."</td>";
					if($data['Stock_Munitions_8'] >0)
						$rav_txt.="<td><b>8mm</b> ".$data['Stock_Munitions_8']."</td>";
					if($data['Stock_Munitions_13'] >0)
						$rav_txt.="<td><b>13mm</b> ".$data['Stock_Munitions_13']."</td>";
					if($data['Stock_Munitions_20'] >0)
						$rav_txt.="<td><b>20mm</b> ".$data['Stock_Munitions_20']."</td>";
					if($data['Stock_Munitions_30'] >0)
						$rav_txt.="<td><b>30mm</b> ".$data['Stock_Munitions_30']."</td>";
					if($data['Stock_Munitions_40'] >0)
						$rav_txt.="<td><b>40mm</b> ".$data['Stock_Munitions_40']."</td>";
					if($data['Stock_Munitions_50'] >0)
						$rav_txt.="<td><b>50mm</b> ".$data['Stock_Munitions_50']."</td>";
					if($data['Stock_Munitions_60'] >0)
						$rav_txt.="<td><b>60mm</b> ".$data['Stock_Munitions_60']."</td>";
					if($data['Stock_Munitions_75'] >0)
						$rav_txt.="<td><b>75mm</b> ".$data['Stock_Munitions_75']."</td>";
					if($data['Stock_Munitions_90'] >0)
						$rav_txt.="<td><b>90mm</b> ".$data['Stock_Munitions_90']."</td>";
					if($data['Stock_Munitions_105'] >0)
						$rav_txt.="<td><b>105mm</b> ".$data['Stock_Munitions_105']."</td>";
					if($data['Stock_Munitions_125'] >0)
						$rav_txt.="<td><b>125mm</b> ".$data['Stock_Munitions_125']."</td>";
					if($data['Stock_Munitions_150'] >0)
						$rav_txt.="<td><b>150mm</b> ".$data['Stock_Munitions_150']."</td>";
					$rav_txt.="</tr></table></td></tr>";
					$units.=$rav_txt;
				}
			}
			mysqli_free_result($result);
		}
		else
			echo "Désolé, aucune unité terrestre recensée.";
		echo "<div style='overflow:auto; height: 640px;'><table class='table table-striped'>
				<thead><tr><th>Unité</th><th>Nation</th><th>Lieu</th><th>Zone</th><th>Position</th><th>Effectifs</th><th>Troupes</th><th>Officier</th><th>Division</th><th>Activité</th><th>Rapport</th></tr></thead>
				".$units."
			</table></div>";
	}
	else
		PrintNoAccess($country,1,4);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page</h1>";*/
?>