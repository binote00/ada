<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$Nbr_Pl=0;
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin)
	{	
		include_once('./em_effectifs.php');
		$Pays_q=$country;
		echo "<h2>Nouvelles recrues</h2>"; //<div style='overflow:auto; height: 640px;'>
		$table="<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr><th>Unité</th><th>Nom</th><th>Grade</th><th>Réputation</th><th>Statut</th>
		<th title='Missions accomplies'>Missions</th><th title='Abattu/Missions'>Ratio</th><th title='Missions tentées aujourdhui'>Sorties</th><th>Credits</th><th>Activité</th></tr></thead>";		
		if($Admin)
		{
			//$Pays_q="%";
			$query="SELECT p.ID,p.Nom,p.Unit,p.Avancement,p.Reputation,p.Raids_Bomb,p.Raids_Bomb_Nuit,p.Credits_date,p.Missions_Max,
			p.Credits,p.Pays,p.Abattu,p.MIA,p.Commando,p.Actif,u.Type,u.Nom,u.Commandant,u.Officier_Adjoint,u.Officier_Technique,p.Simu FROM Pilote as p,Unit as u WHERE u.ID=p.Unit
			AND p.Engagement BETWEEN CURDATE() - INTERVAL 15 DAY AND CURDATE() ORDER BY u.Type ASC,u.Reputation DESC,u.Nom ASC,p.Avancement DESC,p.Reputation DESC";
		}
		elseif($GHQ)
		{
			$query="SELECT p.ID,p.Nom,p.Unit,p.Avancement,p.Reputation,p.Raids_Bomb,p.Raids_Bomb_Nuit,p.Credits_date,p.Missions_Max,
			p.Credits,p.Pays,p.Abattu,p.MIA,p.Commando,p.Actif,u.Type,u.Nom,u.Commandant,u.Officier_Adjoint,u.Officier_Technique,p.Simu FROM Pilote as p,Unit as u WHERE p.Pays ='$Pays_q' AND u.ID=p.Unit AND u.Pays ='$Pays_q'
			AND p.Engagement BETWEEN CURDATE() - INTERVAL 15 DAY AND CURDATE() AND p.Actif=0 ORDER BY u.Reputation DESC,u.Nom ASC,p.Avancement DESC,p.Reputation DESC";
		}
		else
		{
			$query="SELECT p.ID,p.Nom,p.Unit,p.Avancement,p.Reputation,p.Raids_Bomb,p.Raids_Bomb_Nuit,p.Credits_date,p.Missions_Max,
			p.Credits,p.Pays,p.Abattu,p.MIA,p.Commando,p.Actif,u.Type,u.Nom,u.Commandant,u.Officier_Adjoint,u.Officier_Technique,p.Simu FROM Pilote as p,Unit as u WHERE p.Pays ='$Pays_q' AND u.ID=p.Unit AND u.Pays ='$Pays_q'
			AND p.Engagement BETWEEN CURDATE() - INTERVAL 15 DAY AND CURDATE() AND p.Actif=0 ORDER BY u.Reputation DESC,u.Nom ASC,p.Avancement DESC,p.Reputation DESC";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($Data=mysqli_fetch_array($result))
			{
				$Dispos='';
				$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);
				$Reputation=GetReputation($Data['Reputation'],$Data['Pays']);
				$Unite_Nom=$Data['Nom'];
				//MIA
				$MIA=$Data['MIA'];
				if(!$MIA)
				{
					if($Data['Commando'])
						$MIA="Commando";
					elseif($Data['Actif'])
						$MIA="Retraité";
					else
						$MIA="Actif";
				}
				else
					$MIA="MIA<br>".GetData("Lieu","ID",$MIA,"Nom");
				$Ratio=GetRatio($Data['ID']);
				$table.="<tr><td>".Afficher_Icone($Data[2],$Data['Pays'],$Unite_Nom)."<br>".$Unite_Nom."</td><td><a href='user_public.php?Pilote=".$Data[0]."' style='color:#4171ac;' target='_blank'>".$Data[1]."</a></td>
				<td title='".$Avancement[0]."'><img src='images/grades/grades".$Data['Pays'].$Avancement[1].".png'></td><td>".$Reputation."</td><td>".$MIA."</td>
				<td>".$Ratio[1]."</td><td>".$Ratio[0]."</td><td>".$Data['Missions_Max']."</td><td>".$Data['Credits']."</td><td>".$Data['Credits_date']."</td></tr>";
				$Nbr_Pl+=1;
			}
			$table.="</table></div>";
			echo $table;			
			if($Admin)
				echo "<p>Total Recrues =".$Nbr_Pl."</p>";
		}
		else
			echo "<h6>Désolé, votre nation ne compte pas de pilote actif sur ce front</h6>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>