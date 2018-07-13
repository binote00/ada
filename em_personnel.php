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
	include_once('./menu_em_staff.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin)
	{	
		$Pays_q=$country;
		$Unite_Type=GetAvionType($Tab);
		//$Unite=GetData("Pilote","ID",$PlayerID,"Unit");
		//$Unite_Nom=GetData("Unit","ID",$Unite,"Nom");
		echo "<h2>Commandants d'escadrilles ".$Unite_Type."</h2>"; //<div style='overflow:auto; height: 640px;'>
		$table="<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr><th>Unité</th><th>Nom</th><th>Grade</th><th>Réputation</th><th>Statut</th>
		<th title='Missions accomplies'>Missions</th><th title='Abattu/Missions'>Ratio</th><th title='Missions tentées aujourdhui'>Sorties</th><th>Credits</th><th>Activité</th><th>Horaires</th></tr></thead>";		
		if($Admin)
		{
			//$Pays_q="%";
			$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Unit,Pilote.Avancement,Pilote.Reputation,Pilote.Raids_Bomb,Pilote.Raids_Bomb_Nuit,Pilote.Credits_date,Pilote.Missions_Max,
			Pilote.Credits,Pilote.Pays,Pilote.Abattu,Pilote.MIA,Pilote.Commando,Pilote.Actif,Unit.Type,Unit.Nom,Unit.Commandant,Unit.Officier_Adjoint,Unit.Officier_Technique,Pilote.Simu FROM Pilote,Unit WHERE Unit.ID=Pilote.Unit
			AND Pilote.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() AND Unit.Type='$Tab' ORDER BY Unit.Pays ASC, Unit.Type ASC, Unit.Reputation DESC, Unit.Nom ASC, Pilote.Avancement DESC, Pilote.Reputation DESC";
		}
		elseif($GHQ)
		{
			$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Unit,Pilote.Avancement,Pilote.Reputation,Pilote.Raids_Bomb,Pilote.Raids_Bomb_Nuit,Pilote.Credits_date,Pilote.Missions_Max,
			Pilote.Credits,Pilote.Pays,Pilote.Abattu,Pilote.MIA,Pilote.Commando,Pilote.Actif,Unit.Type,Unit.Nom,Unit.Commandant,Unit.Officier_Adjoint,Unit.Officier_Technique,Pilote.Simu FROM Pilote,Unit WHERE Pilote.Pays ='$Pays_q' AND Unit.ID=Pilote.Unit AND Unit.Pays ='$Pays_q'
			AND Pilote.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() AND Pilote.Actif=0 AND Unit.Type='$Tab' ORDER BY Unit.Type ASC, Unit.Reputation DESC, Unit.Nom ASC, Pilote.Avancement DESC, Pilote.Reputation DESC";
		}
		else
		{
			$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Unit,Pilote.Avancement,Pilote.Reputation,Pilote.Raids_Bomb,Pilote.Raids_Bomb_Nuit,Pilote.Credits_date,Pilote.Missions_Max,
			Pilote.Credits,Pilote.Pays,Pilote.Abattu,Pilote.MIA,Pilote.Commando,Pilote.Actif,Unit.Type,Unit.Nom,Unit.Commandant,Unit.Officier_Adjoint,Unit.Officier_Technique,Pilote.Simu FROM Pilote,Unit WHERE Pilote.Pays ='$Pays_q' AND Unit.ID=Pilote.Unit AND Unit.Pays ='$Pays_q'
			AND Pilote.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() AND Pilote.Actif=0 AND Pilote.Front='$Front' AND Unit.Type='$Tab' ORDER BY Unit.Type ASC, Unit.Reputation DESC, Unit.Nom ASC, Pilote.Avancement DESC, Pilote.Reputation DESC";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($Data=mysqli_fetch_array($result))
			{
				$Dispos='';
				/*$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);
				$Reputation=GetReputation($Data['Reputation'],$Data['Pays']);
				$Unite_Nom=GetData("Unit","ID",$Data['Unit'],"Nom");
				$Unite_Type=GetAvionType(GetData("Unit","ID",$Data['Unit'],"Type"));*/
				$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);
				$Reputation=GetReputation($Data['Reputation'],$Data['Pays']);
				$Unite_Nom=$Data['Nom'];
				//$Unite_Type=GetAvionType($Data['Type']);
				/*if($Unite_Type !=$Unite_Type_Ori)
				{
					$titre="<tr bgcolor='tan'><th colspan='15'>".$Unite_Type."</th></tr>";
					$Unite_Type_Ori=$Unite_Type;
				}
				else
				{
					$titre="";
				}*/
				//$Fonction_txt="";
				/*$Commandant=GetData("Unit","ID",$Data['Unit'],"Commandant");
				$Officier_Adjoint=GetData("Unit","ID",$Data['Unit'],"Officier_Adjoint");
				$Officier_Technique=GetData("Unit","ID",$Data['Unit'],"Officier_Technique");*/
				/*$Commandant=$Data['Commandant'];
				$Officier_Adjoint=$Data['Officier_Adjoint'];
				$Officier_Technique=$Data['Officier_Technique'];
				if($Commandant ==$Data['ID'])
				{
					$Fonction_txt=GetStaff($Data['Pays'],1);
					$Fonction_img="images/staff".$Data['Pays']."1.jpg";
					if(is_file($Fonction_img))
					{
						$Fonction_txt="<img src='".$Fonction_img."' title='".$Fonction_txt."'>";
					}
				}
				elseif($Officier_Adjoint ==$Data['ID'])
				{
					$Fonction_txt=GetStaff($Data['Pays'],2);
					$Fonction_img="images/staff".$Data['Pays']."2.jpg";
					if(is_file($Fonction_img))
					{
						$Fonction_txt="<img src='".$Fonction_img."' title='".$Fonction_txt."'>";
					}
				}
				elseif($Officier_Technique ==$Data['ID'])
				{
					$Fonction_txt=GetStaff($Data['Pays'],3);
					$Fonction_img="images/staff".$Data['Pays']."3.jpg";
					if(is_file($Fonction_img))
					{
						$Fonction_txt="<img src='".$Fonction_img."' title='".$Fonction_txt."'>";
					}
				}
				else
				{
					$Fonction_txt="Pilote";
				}*/
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
				//$Missions=GetMissions($Data['ID']);
				/*if($Data['Simu'] ==1)
					$Simu="Non";
				else
					$Simu="Oui";*/
				/*Horaires
				if($Data['Dispo_Jour'] =="tous")
					$Dispos="Tous les jours";
				else
				{
					if($Data['Dispo_Jour'] =="we")
						$Dispos="Week-end";
					elseif($Data['Dispo_Jour'] =="sem")
						$Dispos="Semaine";
					elseif($Data['Dispo_Jour'] =="lu")
						$Dispos="Lundi";
					elseif($Data['Dispo_Jour'] =="ma")
						$Dispos="Mardi";
					elseif($Data['Dispo_Jour'] =="me")
						$Dispos="Mercredi";
					elseif($Data['Dispo_Jour'] =="je")
						$Dispos="Jeudi";
					elseif($Data['Dispo_Jour'] =="ve")
						$Dispos="Vendredi";
					elseif($Data['Dispo_Jour'] =="sa")
						$Dispos="Samedi";
					elseif($Data['Dispo_Jour'] =="di")
						$Dispos="Dimanche";
				}
				if($Data['Dispo_Sauf'] !="aucun")
				{
					if($Data['Dispo_Sauf'] =="lu")
						$Dispos.= " sauf Lundi";
					elseif($Data['Dispo_Sauf'] =="ma")
						$Dispos.= " sauf Mardi";
					elseif($Data['Dispo_Sauf'] =="me")
						$Dispos.= " sauf Mercredi";
					elseif($Data['Dispo_Sauf'] =="je")
						$Dispos.= " sauf Jeudi";
					elseif($Data['Dispo_Sauf'] =="ve")
						$Dispos.= " sauf Vendredi";
					elseif($Data['Dispo_Sauf'] =="sa")
						$Dispos.= " sauf Samedi";
					elseif($Data['Dispo_Sauf'] =="di")
						$Dispos.= " sauf Dimanche";
				}
				$Dispos.="<br>de ".$Data['Dispo_Debut']."h à ".$Data['Dispo_Fin']."h";*/				
				$table.="<tr><td>".Afficher_Icone($Data[2],$Data['Pays'],$Unite_Nom)."<br>".$Unite_Nom."</td><td><a href='user_public.php?Pilote=".$Data[0]."' style='color:#4171ac;' target='_blank'>".$Data[1]."</a></td>
				<td title='".$Avancement[0]."'><img src='images/grades/grades".$Data['Pays'].$Avancement[1].".png'></td><td>".$Reputation."</td><td>".$MIA."</td>
				<td>".$Ratio[1]."</td><td>".$Ratio[0]."</td><td>".$Data['Missions_Max']."</td><td>".$Data['Credits']."</td><td>".$Data['Credits_date']."</td><td>".$Dispos."</td></tr>";
				$Nbr_Pl+=1;
			}
			$table.="</table></div>";
			echo $table;			
			/*for($i=1;$i<=13;$i++)
			{
				if($output[$i])
					echo $head.$output[$i].$foot;
			}*/			
			if($PlayerID ==1 or $Admin)
				echo "<p>Total Actifs =".$Nbr_Pl."</p>";
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