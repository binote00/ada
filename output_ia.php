<?require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_access.php');
	$country=$_SESSION['country'];
	$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
	if($Admin)
		$Limite=100;
	else
		$Limite=50;
	$query="SELECT DISTINCT e.*,l.Latitude,l.Longitude,l.Nom FROM gnmh_aubedesaiglesnet4.Events_em as e,gnmh_aubedesaiglesnet.Lieu as l 
	WHERE e.Lieu=l.ID AND e.Event_Type IN (280,281,282,283,284,285) ORDER BY e.ID DESC LIMIT ".$Limite."";
	$con=dbconnecti();
	$query2=mysqli_query($con,$query);
	mysqli_close($con);
	if($query2)
	{
		while($data2=mysqli_fetch_assoc($query2))
		{
			$Front_Lieu=GetFrontByCoord(0,$data2['Latitude'],$data2['Longitude']);
			if($Front ==$Front_Lieu or $Front ==99)
			{
				$Date=substr($data2['Date'],0,16);
				$Lieu=$data2['Nom'];
				$icon="<img src='images/ia_down.png' title='Avion abattu'>";
				if($data2['Event_Type'] ==283 or $data2['Event_Type'] ==284 or $data2['Event_Type'] ==285)
				{
					if($data2['Event_Type'] ==285)
						$icon="<img src='images/ia_return.png' title='Avion forcé à faire demi-tour'>";
					elseif($data2['Event_Type'] ==284)
						$icon="<img src='images/ia_combat.png' title='Interception'>";
					$con=dbconnecti();
					$resultp1=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['PlayerID']."");
					$resultp=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['Pilote_eni']."");
					mysqli_close($con);
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
					if($Pays_loss ==$country or $Pays_win ==$country)
					$liste.="<tr><td>".$Date."</td><td>".$icon."</td><td>".$Lieu."</td>
					<td><img src='".$Pays_loss."20.gif'></td><td>".Afficher_Icone($Unit_loss,$Pays_loss)."</td><td>".$Pilote_loss."</td><td>".GetAvionIcon($data2['Avion_Nbr'],$Pays_loss,0,$Unit_loss,$Front)."</td>
					<td>".GetAvionIcon($data2['Avion'],$Pays_win,0,$Unit_win,$Front)."</td><td>".$Pilote_win."</td><td>".Afficher_Icone($Unit_win,$Pays_win)."</td><td><img src='".$Pays_win."20.gif'></td></tr>";
				}
				else
				{
					$con=dbconnecti();
					$resultp1=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['PlayerID']."");
					$resultp=mysqli_query($con,"SELECT Nom,Pays,Unit FROM Pilote_IA WHERE ID=".$data2['Pilote_eni']."");
					mysqli_close($con);
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
					if($Pays_loss ==$country or $Pays_win ==$country)
					$liste.="<tr><td>".$Date."</td><td>".$icon."</td><td>".$Lieu."</td>
					<td><img src='".$Pays_win."20.gif'></td><td>".Afficher_Icone($Unit_win,$Pays_win)."</td><td>".$Pilote_win."</td><td>".GetAvionIcon($data2['Avion'],$Pays_win,0,$Unit_win,$Front)."</td>
					<td>".GetAvionIcon($data2['Avion_Nbr'],$Pays_loss,0,$Unit_loss,$Front)."</td><td>".$Pilote_loss."</td><td>".Afficher_Icone($Unit_loss,$Pays_loss)."</td><td><img src='".$Pays_loss."20.gif'></td></tr>";
				}
			}
		}
		mysqli_free_result($query2);
	}
	echo "<h1>Combats aériens</h1>
	<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
	<thead><tr><th>Date</th><th>Action</th><th>Lieu</th><th>Pays</th><th>Unité</th><th>Pilote</th><th>Avion</th>
	<th>Avion Abattu</th><th>Pilote</th><th>Unité</th><th>Pays</th></thead>".$liste."</table></div>";
}?>