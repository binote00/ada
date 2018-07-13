<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		$country=$_SESSION['country'];	
		include_once('./jfv_include.inc.php');
		include_once('./jfv_nomission.inc.php');
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_inc_em.php');
		include_once('./menu_em.php');
		//include_once('./menu_staff.php');	
		if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin)
		{
			include_once('./em_effectifs.php');
			if($Admin)
			{
				$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Unit,Pilote.Avancement,Pilote.Reputation,Pilote.Pays,Pilote.Credits,Pilote.Missions_Jour,Pilote.MIA,Pilote.Commando,Pilote.Slot11,Unit.Nom 
				FROM Pilote,Unit WHERE Unit.ID=Pilote.Unit AND Pilote.Commando >0 AND Pilote.Actif=0 AND Pilote.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
				ORDER BY Pilote.Avancement DESC,Pilote.Reputation DESC";
			}
			else
			{
				$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Unit,Pilote.Avancement,Pilote.Reputation,Pilote.Pays,Pilote.Credits,Pilote.Missions_Jour,Pilote.MIA,Pilote.Commando,Pilote.Slot11,Unit.Nom 
				FROM Pilote,Unit WHERE Pilote.Pays='$country' AND Unit.ID=Pilote.Unit AND Pilote.Commando >0 AND Pilote.Actif=0 AND Pilote.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
				ORDER BY Pilote.Avancement DESC,Pilote.Reputation DESC";
			}
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				echo "<h2>Commandos disponibles</h2><table class='table table-striped'>
				<thead><tr><th>Nom</th><th>Grade</th><th>Unité</th><th>Réputation</th><th>Equipement</th><th>Statut</th>
				<th title='Missions tentées aujourdhui'>Sorties</th><th>Credits</th></tr></thead>";
				while($Data=mysqli_fetch_array($result))
				{
					$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);
					$Reputation=GetReputation($Data['Reputation'],$Data['Pays']);
					//MIA
					$MIA=$Data['MIA'];
					if(!$MIA)
					{
						if($Data['Commando'])
							$MIA="Commando";
						else
							$MIA="Actif";
					}
					else
						$MIA="MIA<br>".GetData("Lieu","ID",$MIA,"Nom");
					//Equipement
					$Equipement=GetData("Matos","ID",$Data['Slot11'],"Nom");				
					echo $titre."<tr><td><a href='user_public.php?Pilote=".$Data[0]."' target='_blank' class='lien'>".$Data[1]."</a></td>
						<td title='".$Avancement[0]."'><img src='images/grades/grades".$Data['Pays'].$Avancement[1].".png'></td>
						<td title='".$Data['Nom']."'><img src='images/unit/unit".$Data[2]."p.gif'></td><td>".$Reputation."</td><td>".$Equipement."</td>
						<td>".$MIA."</td><td>".$Data['Missions_Jour']."</td><td>".$Data['Credits']."</td></tr>";
				}
				echo "</table>";
			}
			else
				echo "<h6>Désolé, aucun Commando n'est disponible</h6>";
			include_once('./em_journal.php');
		}
		else
			PrintNoAccess($country,1,4);
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>