<?require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 xor $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_access.php');
	$country=$_SESSION['country'];
	if($PlayerID >0)
	{
		$con=dbconnecti();
		$query=mysqli_query($con,"SELECT Front,Renseignement,Unit FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($query)
		{
			while($data=mysqli_fetch_assoc($query))
			{
				$Renseignement=$data['Renseignement'];
				$Unite=$data['Unit'];
				$Front=$data['Front'];
			}
			mysqli_free_result($query);
		}
		$Base=GetData("Unit","ID",$Unite,"Base");
		$query_add=" AND (e.Lieu='$Base' OR e.Unit='$Unite')";
	}
	elseif($OfficierEMID >0)
	{
		$Renseignement=101;
		$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
		$Coord=GetCoord($Front,$country);
		$Lat_min=$Coord[0];
		$Lat_max=$Coord[1];
		$Long_min=$Coord[2];
		$Long_max=$Coord[3];
		$query_add=" AND (l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max')";
	}
	if($Admin)
		$query="SELECT e.*,l.Nom as Ville FROM gnmh_aubedesaiglesnet4.Events_em as e,Lieu as l WHERE e.Lieu=l.ID AND e.Event_Type IN (280,281,282,283,285) ORDER BY e.ID DESC LIMIT 100";
	else
		$query="SELECT e.*,l.Nom as Ville FROM gnmh_aubedesaiglesnet4.Events_em as e,Lieu as l WHERE e.Lieu=l.ID AND e.Event_Type IN (280,281,282,283,285)".$query_add." ORDER BY e.ID DESC LIMIT 50";
	$con=dbconnecti();
	$query2=mysqli_query($con,$query);
	if($query2)
	{
		while($data2=mysqli_fetch_assoc($query2))
		{
			if($Admin)
			{
				$avion1_legend='';
				$avion2_legend='';
				if($data2['Event_Type'] ==280)
				{
					$avion1_legend='<br><i>Couverture</i>';
					$avion2_legend='<br><i>Escorte</i>';
				}
				elseif($data2['Event_Type'] ==281)
				{
					$avion1_legend='<br><i>Escorte</i>';
					$avion2_legend='<br><i>Couverture</i>';
				}
				elseif($data2['Event_Type'] ==282)
				{
					$avion1_legend='<br><i>Attaque</i>';
					$avion2_legend='<br><i>Couverture</i>';
				}
				elseif($data2['Event_Type'] ==283)
				{
					$avion1_legend='<br><i>Couverture</i>';
					$avion2_legend='<br><i>Attaque</i>';
				}
			}
			$Date=substr($data2['Date'],0,16);
			$icon="<img src='images/ia_down.png' title='Avion abattu'>";
			if($data2['Event_Type'] ==283 or $data2['Event_Type'] ==285)
			{
				$resultp1=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['PlayerID']."");
				$resultp=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['Pilote_eni']."");
				if($resultp1)
				{
					while($datap1=mysqli_fetch_array($resultp1, MYSQLI_ASSOC))
					{
						$Pilote_win=$datap1['Nom'];
						$Pays_win=$datap1['Pays'];
						$Unit_win=$datap1['Unit'];
					}
					mysqli_free_result($resultp1);
				}
				if($resultp)
				{
					while($datap=mysqli_fetch_array($resultp, MYSQLI_ASSOC))
					{
						$Pilote_loss=$datap['Nom'];
						$Pays_loss=$datap['Pays'];
						$Unit_loss=$datap['Unit'];
					}
					mysqli_free_result($resultp);
				}
				if($Pays_win ==$country or $Pays_loss ==$country or $Admin)
				{
					if($data2['Event_Type'] ==285)
						$icon="<img src='images/ia_hit.png' title='Endommagé'>";
					$liste_ia.="<tr><td>".$Date."</td><td>".$icon."</td><td>".$data2['Ville']."</td>
					<td><img src='".$Pays_loss."20.gif'></td><td>".Afficher_Icone($Unit_loss,$Pays_loss)."</td><td>".$Pilote_loss."</td><td>".GetAvionIcon($data2['Avion_Nbr'],$Pays_loss,0,$Unit_loss,$Front_Lieu).$avion1_legend."</td>
					<td>".GetAvionIcon($data2['Avion'],$Pays_win,0,$Unit_win,$Front_Lieu).$avion2_legend."</td><td>".$Pilote_win."</td><td>".Afficher_Icone($Unit_win,$Pays_win)."</td><td><img src='".$Pays_win."20.gif'></td></tr>";
				}
			}
			else
			{
				$resultp1=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['PlayerID']."");
				$resultp=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['Pilote_eni']."");
				if($resultp1)
				{
					while($datap1=mysqli_fetch_array($resultp1, MYSQLI_ASSOC))
					{
						$Pilote_win=$datap1['Nom'];
						$Pays_win=$datap1['Pays'];
						$Unit_win=$datap1['Unit'];
					}
					mysqli_free_result($resultp1);
				}
				if($resultp)
				{
					while($datap=mysqli_fetch_array($resultp, MYSQLI_ASSOC))
					{
						$Pilote_loss=$datap['Nom'];
						$Pays_loss=$datap['Pays'];
						$Unit_loss=$datap['Unit'];
					}
					mysqli_free_result($resultp);
				}
				if($Pays_win ==$country or $Pays_loss ==$country or $Admin)
				{
					$liste_ia.="<tr><td>".$Date."</td><td>".$icon."</td><td>".$data2['Ville']."</td>
					<td><img src='".$Pays_win."20.gif'></td><td>".Afficher_Icone($Unit_win,$Pays_win)."</td><td>".$Pilote_win."</td><td>".GetAvionIcon($data2['Avion'],$Pays_win,0,$Unit_win,$Front_Lieu).$avion1_legend."</td>
					<td>".GetAvionIcon($data2['Avion_Nbr'],$Pays_loss,0,$Unit_loss,$Front_Lieu).$avion2_legend."</td><td>".$Pilote_loss."</td><td>".Afficher_Icone($Unit_loss,$Pays_loss)."</td><td><img src='".$Pays_loss."20.gif'></td></tr>";
				}
			}
		}
		mysqli_free_result($query2);
		/*if($liste_ia)
		{*/
			$liste_ia="<h2>Combats aériens</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped table-condensed'>
			<thead><tr><th>Date</th><th>Action</th><th>Lieu</th><th>Pays</th><th>Unité</th><th>Pilote</th><th>Avion 1</th>
			<th>Avion 2</th><th>Pilote</th><th>Unité</th><th>Pays</th></thead>".$liste_ia."</table></div>";
		//}
	}
	echo "<h1>Tableau de Chasse</h1>
	<a href='index.php?view=tableau' class='btn btn-primary'>Combats aériens joueurs</a>
	<a href='index.php?view=output_probable' class='btn btn-primary'>Victoires probables</a>".$liste_ia;
}