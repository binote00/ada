<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{	
	$units='';
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	if(!$Cible or !$Placement or !$Position)
	{
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT Lieu_ID,Placement,Position FROM Regiment WHERE Officier_ID='$OfficierID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ugp-off');
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Cible=$data['Lieu_ID'];
				$Placement=$data['Placement'];
				$Position=$data['Position'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
	}
	for($i=1;$i<3;$i++)
	{
		if($i ==1)
			$query="SELECT r.*,c.Nom as Cible,l.Nom as ville,o.Nom as leader,c.mobile,c.Arme_Art,c.Arme_AT,c.Vitesse,c.Categorie,o.Division,o.Front,p.Faction FROM Regiment as r,Cible as c,Lieu as l,Officier as o,Pays as p
			WHERE r.Lieu_ID=l.ID AND r.Officier_ID=o.ID AND r.Vehicule_ID=c.ID AND r.Pays=p.ID AND (p.Faction='$Faction' OR r.Visible=1)
			AND r.Officier_ID<>'$OfficierID' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 ORDER BY r.Placement ASC, r.ID ASC";
		else
			$query="SELECT r.*,c.Nom as Cible,l.Nom as ville,c.mobile,c.Arme_Art,c.Arme_AT,c.Vitesse,c.Categorie,p.Faction FROM Regiment_IA as r,Cible as c,Lieu as l,Pays as p
			WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays=p.ID AND (p.Faction='$Faction' OR r.Visible=1)
			AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 ORDER BY r.Placement ASC, r.ID ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result))
			{
				$Appui="<i>Non</i>";
				$Div='';
				if($i ==1)$Front=$data['Front'];
				if($data['Faction'] ==$Faction)
				{
					if($data['Position'] ==1)
					{
						if(($data['mobile'] ==1 or $data['mobile'] ==2 or $data['mobile'] ==6 or $data['mobile'] ==7) and $data['Vitesse'] >10)
							$Appui="Si attaqué";
					}
					elseif($data['Position'] ==10)
					{
						if($data['Categorie'] ==5 or $data['Categorie'] ==6 or $data['Categorie'] ==9)
							$Appui="Si attaqué";
						elseif($data['mobile'] ==3 and $data['Arme_AT'] >0)
							$Appui="Si attaqué";
					}
					elseif($data['Position'] ==3)
					{
						if($data['mobile'] ==3 and $data['Arme_AT'] >0)
							$Appui="Si attaqué";
						elseif($data['Arme_Art'] >0)
							$Appui="Si bombardé";
					}
					elseif($data['Position'] ==5)
					{
						if($data['Arme_Art'] >0)
							$Appui="Si attaqué";
					}
					elseif($data['Position'] ==21)
					{
							$Appui="Escorte";
					}
					elseif($data['Position'] ==23)
					{
							$Appui="Appui";
					}
					elseif($data['Position'] ==24)
					{
							$Appui="ASM";
					}
					if($data['Division'])
						$Div="<img src='images/div/div".$data['Division'].".png'>";
					if($i ==1)
						$Leader=$data['leader'];
					else
						$Leader="Officier EM";						
					$units.="<tr><td align='left'>".$data['ID']."e Cie ".$Div."</td>
					<td><img src='".$data['Pays']."20.gif'></td>
					<td>".GetPosGr($data['Position'])."</td>
					<td>".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)."</td>
					<td>".$Leader."</td>
					<td>".$Appui."</td>
					</tr>";		
				}
				else
				{
					if($data['Position'] ==8)
						$Appui="Sous le feu";
					else
						$Appui="Inconnu";
					if($data['Transit_Veh'] ==5000)$data['Vehicule_ID']=5000;
					$units.="<tr><td align='left'>Inconnu</td>
					<td><img src='".$data['Pays']."20.gif'></td>
					<td>Inconnu</td>
					<td>".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)."</td>
					<td>Inconnu</td>
					<td>".$Appui."</td>
					</tr>";		
				}				
			}
			mysqli_free_result($result);
		}
	}
	if(!$units)$units="<tr><td colspan='6' style='color:red;'>Aucune unité terrestre ou navale alliée</td></tr>";		
	//Couverture aérienne
	$con=dbconnecti();
	$result=mysqli_query($con,"(SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p WHERE j.ID=l.Joueur AND j.Pays=p.ID AND j.Couverture='$Cible' AND p.Faction='$Faction') 
	UNION (SELECT i.ID,i.Nom,i.Unit,i.Pays,i.Alt,i.Avion FROM Pilote_IA as i,Pays as p WHERE i.Pays=p.ID AND i.Couverture='$Cible' AND p.Faction='$Faction' AND i.Actif=1)");
	$pilotes_couv=mysqli_affected_rows($con);
	mysqli_close($con);
	if($result)
	{
		//$Couverture.="<div style='overflow:auto; width: 450px; height: 150px;'><table><tr><th colspan='10'>Chasseurs en patrouille sur votre objectif</th></tr>";
		while($data=mysqli_fetch_array($result))
		{
			$Couverture.="<tr><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td><td><img src='".$data['Pays']."20.gif'></td><td>".$data[4]."m</td>
			<th>".GetAvionIcon($data['Avion'], $data['Pays'], $data['ID'], $data['Unit'], $Front)."</th><td>".$data['Nom']."</td><td>Oui</td></tr>";
		}
		mysqli_free_result($result);
	}
	if(!$Couverture)
		$Couv_txt="<tr><td colspan='6' style='color:red;'>Aucune couverture aérienne <a href='index.php?view=ground_appui' title='Demander une couverture aérienne'><img src='images/help.png'></a></td></tr>";
	else
		$Couv_txt="<tr><td colspan='6'>".$pilotes_couv." chasseurs vous couvrent<td></tr>";
?>
<div style="overflow:auto; width:100%; height:360px;">
	<table class='table table-striped'>
		<thead><tr><th>Unité</th><th>Nation</th><th>Position</th><th>Troupes</th><th>Officier</th><th>Vous couvre</th></tr></thead>
		<?echo $Couv_txt.$units.$Couverture;
		if($units or $Couverture){?>
		<tfoot><tr><th>Unité</th><th>Nation</th><th>Position</th><th>Troupes</th><th>Officier</th><th>Vous couvre</th></tr></tfoot>
		<?}?>
	</table>
</div>
<?}?>
