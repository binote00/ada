<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');	
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $Admin)
	{			
		if($Admin)
		{
			$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Unit,Unit.Nom,Pilote.Avancement,Pilote.Pays,Pilote.Credits,Pilote.Missions_Jour,Pilote.MIA,Lieu.Nom,Lieu.Zone
			FROM Pilote,Unit,Lieu WHERE Unit.ID=Pilote.Unit AND Pilote.MIA=Lieu.ID AND Pilote.MIA >0
			ORDER BY Pilote.Front ASC, Lieu.Nom ASC";
		}
		else
		{
			$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Unit,Unit.Nom,Pilote.Avancement,Pilote.Pays,Pilote.Credits,Pilote.Missions_Jour,Pilote.MIA,Lieu.Nom,Lieu.Zone
			FROM Pilote,Unit,Lieu WHERE Pilote.Pays='$country' AND Unit.ID=Pilote.Unit AND Pilote.MIA=Lieu.ID AND Pilote.MIA >0
			ORDER BY Pilote.Front ASC, Lieu.Nom ASC";
		}
		$queryf="SELECT p.ID,p.Nom,p.Avancement,p.Pays,p.Endurance,u.ID as Unit,u.Nom as Unite,l.Nom as Ville FROM Pilote_IA as p,Unit as u,Lieu as l
		WHERE p.Endurance >0 AND p.Couverture >0 AND p.Pays='$country' AND u.ID=p.Unit AND l.ID=p.Couverture ORDER BY p.Endurance DESC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		$resultf=mysqli_query($con,$queryf);
		mysqli_close($con);
		if($result)
		{
			while($Data=mysqli_fetch_array($result))
			{
				$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);				
				$pilotes_mia.="<tr><td><a href='user_public.php?Pilote=".$Data[0]."' style='color:#4171ac;' target='_blank'>".$Data[1]."</a></td>
					<td><img src='images/grades/grades".$Data['Pays'].$Avancement[1].".png' title='".$Avancement[0]."'></td>
					<td>".Afficher_Icone($Data[2],$Data['Pays'],$Data[3])."</td>
					<td>".$Data['Nom']."</td><td><img src='images/zone".$Data['Zone'].".jpg'></td></tr>";
			}
			mysqli_free_result($result);
		}
		else
			$pilotes_mia="Aucun pilote n'est actuellement MIA.";
		if($resultf)
		{
			while($dataf=mysqli_fetch_array($resultf))
			{
				$Avancementf=GetAvancement($dataf['Avancement'],$dataf['Pays']);
				if($dataf['Endurance'] >8)
					$fatigue_icon='<img src="images/fatigue9.png" title="Très fatigué">';
				elseif($dataf['Endurance'] >5)
					$fatigue_icon='<img src="images/fatigue6.png" title="Fatigué">';
				elseif($dataf['Endurance'] >2)
					$fatigue_icon='<img src="images/fatigue3.png" title="Légèrement fatigué">';
				else
					$fatigue_icon='<img src="images/fatigue0.png" title="En pleine forme">';
				$pilotes_fatigues.="<tr><td>".$dataf['Nom']."</td>
					<td><img src='images/grades/grades".$dataf['Pays'].$Avancementf[1].".png' title='".$Avancementf[0]."'></td>
					<td>".Afficher_Icone($dataf['Unit'],$dataf['Pays'],$dataf['Unite'])."</td>
					<td>".$dataf['Ville']."</td><td>".$fatigue_icon."</td></tr>";
			}
			mysqli_free_result($resultf);
		}
		else
			$pilotes_fatigues="Aucun pilote n'est actuellement épuisé.";
		include_once('./em_effectifs.php');
		if(!$OfficierEMID ==$Officier_Adjoint)
			echo "<h2>Pilotes MIA</h2><div class='alert alert-info'>Pilotes joueurs attendant du secours en territoire ennemi</div><table class='table table-striped'>
			<thead><tr><th>Nom</th><th>Grade</th><th>Unité</th><th>Lieu</th><th>Zone</th></tr></thead>".$pilotes_mia."</table>";
		if(!$OfficierEMID ==$Officier_Rens)
			echo "<h2>Pilotes épuisés</h2><div class='alert alert-info'>Pilotes IA fatigués par les combats demandant une relève
			<a href='#' class='popup'><img src='images/help.png'><span>Pour faire se reposer des pilotes, remonter le moral via le menu de l'escadrille. Les pilotes restant plus de 24h au sol sont automatiquement reposés.</span></a>
			</div><table class='table table-striped'>
			<thead><tr><th>Nom</th><th>Grade</th><th>Unité</th><th>Lieu</th><th>Fatigue</th></tr></thead>".$pilotes_fatigues."</table>";
	}
	else
		PrintNoAccess($country,1,2,4);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>