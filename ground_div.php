<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	$mes="Front ".$Front."<br> Admin ".$Admin."<br> OfficierEMID ".$OfficierEMID."<br> Commandant ".$Commandant."<br> Adjoint_Terre ".$Adjoint_Terre;
	if($Front !=12 and ($OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Commandant or $Admin))
	{
		$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$result2=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,l.Nom as Ville,r.Placement,r.Position,r.Division,d.Nom
		FROM Regiment_IA as r,Division as d,Lieu as l WHERE r.Division=d.ID AND r.Lieu_ID=l.ID AND r.Pays='$country' AND r.Front='$Front' ORDER BY r.Division ASC");
		mysqli_close($con);
		echo "<h2>Liste des Divisions</h2><div style='overflow:auto; height: 640px;'><table class='table'><thead><tr>
				<th>Division</th>
				<th>Compagnie</th>
				<th>Troupes</th>
				<th>Lieu</th>
				<th>Position</th>
			</tr></thead>";
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				echo "<tr><td>".Afficher_Image('images/div/div'.$data['Division'].'.png','images/'.$country.'div.png',$data['Nom'],0)."</td><td>".$data['ID']."e</td>
				<td>".$data['Vehicule_Nbr']." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'></td><td>".$data['Ville']."</td><td>".GetPosGr($data['Position']).' '.GetPlace($data['Placement'])."</td></tr>";
			}
			mysqli_free_result($result2);			
		}
	}
}
?>