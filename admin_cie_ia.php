<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1)
{
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$Tri = Insec($_POST['Tri']);
	$units='';
	if($Tri)
		$query="SELECT r.*,c.Nom as Cible,l.Nom as ville FROM Regiment_IA as r,Cible as c,Lieu as l 
		WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID
		ORDER BY r.Pays ASC,r.Front ASC,r.Vehicule_ID ASC,r.Lieu_ID ASC,r.Placement ASC,r.Division ASC,r.ID ASC";
	else
		$query="SELECT r.*,c.Nom as Cible,l.Nom as ville FROM Regiment_IA as r,Cible as c,Lieu as l 
		WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID
		ORDER BY r.Pays ASC,r.Front ASC,r.Lieu_ID ASC,r.Placement ASC,r.Division ASC,r.ID ASC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data = mysqli_fetch_array($result))
		{
			if($data['Division'] >0)
				$Division="<img src='images/div/div".$data['Division'].".png'>";
			else
				$Division="";
			$units.="<tr><td><img src='".$data['Pays']."20.gif'></td><td>".GetFront($data['Front'])."</td><td align='left'>".$data['ID']."e Compagnie</td>			
			<td>".$data['ville']."</td><td>".GetPlace($data['Placement'])."</td><td>".GetPosGr($data['Position'])."</td>
			<td>".$data['Vehicule_Nbr']."</td><td><img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Cible']."'></td><td>".$Division."</td>
			</tr>";		
		}
		mysqli_free_result($result);
	}
	else
		echo "Désolé, aucune unité terrestre recensée.";
	echo "<h1>Unités EM</h1><div style='overflow:auto; height: 640px;'><table class='table table-striped'>
			<thead><tr><th>Nation</th><th>Front</th><th>Unité</th><th>Lieu</th><th>Zone</th><th>Position</th><th>Effectifs</th><th>Troupes</th><th>Division</th></tr></thead>
			".$units."
		</table></div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page</h1>";
?>