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
	include_once('./menu_em.php');
	if($Front !=12 and ($OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Commandant or $Admin))
	{
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT o.Nom as Officer,o.Avancement,o.Credits_Date,o.ID as Cdt,r.ID,r.Vehicule_ID,r.Vehicule_Nbr,l.Nom as Ville,r.Placement,r.Position,o.Division,d.Nom
		FROM Officier as o,Regiment as r,Division as d,Lieu as l WHERE r.Officier_ID=o.ID AND o.Division=d.ID AND r.Lieu_ID=l.ID
		AND o.Pays='$country' AND o.Front='$Front' AND o.Division >0 ORDER BY o.Division ASC");
		mysqli_close($con);
		echo "<h2>Liste des Bataillons actifs <span title='Unités contrôlées par des joueurs'><img src='images/help.png</span></h2><div style='overflow:auto; height: 640px;'><table class='table'><thead><tr>
				<th>Division</th>
				<th>Compagnie</th>
				<th>Officier</th>
				<th>Troupes</th>
				<th>Lieu</th>
				<th>Position</th>
				<th>Activité</th>
				<th>Action</th>
			</tr></thead>";
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Action="";
				$Nommer="";
				if($data['Officer'] !=$Off_test)
				{
					$Action="<form action='index.php?view=quit_division' method='post'><input type='hidden' name='Off' value='".$data['Cdt']."'>
					<input type='Submit' value='Retirer de la division' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
					$Nommer="<form action='index.php?view=promote_division' method='post'><input type='hidden' name='Off' value='".$data['Cdt']."'><input type='hidden' name='Div' value='".$data['Division']."'>
					<input type='Submit' value='Nommer Commandant' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				if($data['Division'] !=$Div_test)
					echo "<tr><th colspan='2'>".GetData("Division","ID",$data['Division'],"Nom")."</th><th colspan='7'><hr></th></tr>";
				echo "<tr><td>".Afficher_Image('images/div/div'.$data['Division'].'.png','images/'.$country.'div.png',$data['Nom'],0)."</td><td>".$data['ID']."e</td><td align='left'>".$data['Officer']."</td>
				<td>".$data['Vehicule_Nbr']." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'></td><td>".$data['Ville']."</td><td>".GetPosGr($data['Position']).' '.GetPlace($data['Placement'])."</td><td>".$data['Credits_Date']."</td><td>".$Nommer."<br>".$Action."</td></tr>";
				$Div_test=$data['Division'];
				$Off_test=$data['Officer'];
			}
			mysqli_free_result($result2);
		}
		echo "</table></div>";
		echo "<a href='index.php?view=ground_em' class='btn btn-default' title='Retour'>Retour</a>";
	}
}
?>