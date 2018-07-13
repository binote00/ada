<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0)
	{
		$Date=date('Y-m-d');
		$Valid=false;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Front,Credits,Missions_Jour,Missions_Max,Victoires,Reputation,Pilotage,Acrobatie,Bombardement,Navigation,Tactique,Vue,Duperie,Endurance,Avancement,Equipage,Couverture,Escorte,Skill_Fav FROM Pilote WHERE ID='$PlayerID'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esca-player');
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
		$Brevet=mysqli_result(mysqli_query($con,"SELECT Value FROM Pil_medals WHERE PlayerID='$PlayerID AND Medal=0'"),0);
		mysqli_close($con);
		if($results)
		{
			while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
			{
				$Skills_Pil[]=$data['Skill'];
			}
			mysqli_free_result($results);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Front=$data['Front'];
				$Credits=$data['Credits'];
				$Missions_Jour=$data['Missions_Jour'];
				$Missions_Max=$data['Missions_Max'];
				$Victoires=$data['Victoires'];
				$Reputation=$data['Reputation'];
				$Duperie=$data['Duperie'];
				$Endurance=$data['Endurance'];
				$Avancement=$data['Avancement'];
				$Equipage=$data['Equipage'];
				$Couverture=$data['Couverture'];
				$Escorte=$data['Escorte'];
				$Pilotage=$data['Pilotage'];
				$Acrobatie=$data['Acrobatie'];
				$Bombardement=$data['Bombardement'];
				$Navigation=$data['Navigation'];
				$Tactique=$data['Tactique'];
				$Vue=$data['Vue'];
				$Skill_Fav=$data['Skill_Fav'];
			}
			mysqli_free_result($result);
			unset($data);
		}		
		if($Pilotage >50)$Pilotage=50;
		if($Acrobatie >50)$Acrobatie=50;
		if($Bombardement >50)$Bombardement=50;
		if($Navigation >50)$Navigation=50;
		if($Tactique >50)$Tactique=50;
		if($Vue >50)$Vue=50;
		if($Duperie >50)$Duperie=50;
		$CT_Perm=12;
		if(is_array($Skills_Pil))
		{
			include_once('./jfv_skills_inc.php');
			if(in_array(67,$Skills_Pil))
				$Skill=150;
			elseif(in_array(66,$Skills_Pil))
				$Skill=125;
			elseif(in_array(65,$Skills_Pil))
				$Skill=100;
			elseif(in_array(64,$Skills_Pil))
				$Skill=75;
			if(in_array(71,$Skills_Pil))
				$Commandement=150;
			elseif(in_array(70,$Skills_Pil))
				$Commandement=125;
			elseif(in_array(69,$Skills_Pil))
				$Commandement=100;
			elseif(in_array(68,$Skills_Pil))
				$Commandement=75;
			if(in_array(83,$Skills_Pil))
				$Duperie=150;
			elseif(in_array(82,$Skills_Pil))
				$Duperie=125;
			elseif(in_array(81,$Skills_Pil))
				$Duperie=100;
			elseif(in_array(80,$Skills_Pil))
				$Duperie=75;
			if(in_array(101,$Skills_Pil))
				$Pilote_Salon=true;
			elseif(in_array(100,$Skills_Pil))
				$Assis_Bureau=true;
			elseif(in_array(99,$Skills_Pil))
				$CT_Perm=6;
			if(in_array(97,$Skills_Pil))
				$Favori_General=true;
			if(in_array(130,$Skills_Pil))
				$Pers_Sup=1;
		}
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Base,Type,Commandant,Officier_Adjoint,Avion1,Avion2,Avion3,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esca-unit');
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite_Nom=$data['Nom'];
				$Base=$data['Base'];
				$Unite_Type=$data['Type'];
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Avion1=$data['Avion1'];
				$Avion2=$data['Avion2'];
				$Avion3=$data['Avion3'];
				$Pers1=$data['Pers1'];
				$Pers2=$data['Pers2'];
				$Pers3=$data['Pers3'];
				$Pers4=$data['Pers4'];
				$Pers5=$data['Pers5'];
				$Pers6=$data['Pers6'];
				$Pers7=$data['Pers7'];
				$Pers8=$data['Pers8'];
				$Pers9=$data['Pers9'];
				$Pers10=$data['Pers10'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
		$Personnel=array_count_values($Pers);		
		//$con=dbconnecti();
		$Probable=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse_Probable WHERE Joueur_win='$PlayerID' AND PVP=0"),0);
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$result=mysqli_query($con,"SELECT QualitePiste,Camouflage,Zone,Tour FROM Lieu WHERE ID='$Base'");
		$result2=mysqli_query($con,"SELECT DISTINCT Nom FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 ORDER BY RAND() LIMIT 1");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$QualitePiste=$data['QualitePiste'];
				$Tour=$data['Tour'];
				$Camouflage=$data['Camouflage'];
				$Zone=$data['Zone'];
			}
			mysqli_free_result($result);
			unset($data);
		}		
		if($result2)
		{
			while($Data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$As=$Data['Nom'];
			}
			mysqli_free_result($result2);
		}		
		if(!$As)$As="un as de l'escadrille";			
		if($Equipage)
		{
			$Eq_Nom=GetData("Equipage","ID",$Equipage,"Nom");
			$Eq_mec=GetData("Equipage","ID",$Equipage,"Mecanique");
		}	
		if(IsAllie($country))
			$Veh_faction="Jeep";
		else
			$Veh_faction="Side-car";		
		if($Reputation <50)
			$Mission_Sup_Txt="Se porter volontaire pour des heures de formation supplémentaires";
		else
			$Mission_Sup_Txt="User de son influence pour obtenir une mission supplémentaire";		
		if($Zone ==6)$QualitePiste=100;	//Porte-avions
	include_once('./menu_temps_libre.php');
	if($Credits <1)
		echo "<div class='alert alert-danger'>Vous ne disposez pas de suffisamment de Crédits Temps pour bénéficier de votre temps libre !</div>";
	else
	{				
		echo "<form action='esc_gestion2.php' method='post'><input type='hidden' name='As' value='".$As."'>";
		if($Tab =="forme"){
		$con=dbconnecti(4);
		$resultm=mysqli_query($con,"SELECT `Date` FROM Events WHERE Event_Type=31 AND PlayerID='$PlayerID' ORDER BY ID DESC LIMIT 1");
		mysqli_close($con);
		if($resultm)
		{
			$data=mysqli_fetch_array($resultm);
			$Date_Mutation=$data[0];
			if($Date_Mutation)
			{
				$con=dbconnecti();
				$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date','$Date_Mutation')"),0);
				mysqli_close($con);
			}
			else
			{
				$con=dbconnecti();
				$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date','2012-09-01')"),0);
				mysqli_close($con);
			}
		}
		echo "<table class='table'><thead><tr><th>Remise en forme</th></tr></thead><tr><td align='left'>";
		if($Pilote_Salon)
		{
			echo "<Input type='Radio' name='Action' value='1'>- <a href='#' class='popup'>S'en jeter une au mess<span>Il faut bien remonter le moral des troupes de temps en temps! Par cette action le moral ne peut dépasser 100</span></a><br>
				<Input type='Radio' name='Action' value='2'>- <a href='#' class='popup'>Secouer ces incapables de la station météo<span>Cela fait du bien au moral, mais risque de vous rendre impopulaire! Par cette action le moral ne peut dépasser 100</span></a><br>
				<Input type='Radio' name='Action' value='4'>- <a href='#' class='popup'>Faire un tour en ".$Veh_faction." parce que vous aimez la vitesse<span>Rien de tel que tester son courage! Par cette action le courage ne peut dépasser 100</span></a><br>
				<Input type='Radio' name='Action' value='6'>- <a href='#' class='popup'>Se reposer dans vos quartiers<span>Là au moins, vous ne verrez pas la tête de l'infirmière! Vous récupérez de l'endurance</span></a><br>
				<Input type='Radio' name='Action' value='20'>- <a href='#' class='popup'>Profiter d'une permission bien méritée<span>Idéal pour revenir gonflé à bloc! Récupération de moral et de courage</span></a><br>";
			if($Equipage)
				echo "<Input type='Radio' name='Action' value='23'>- <a href='#' class='popup'>Emmener ".$Eq_Nom." en virée, il en a bien besoin !<span>Il faut bien remonter le moral des troupes de temps en temps!</span></a><br>";
			if($Datediff >3 and $Credits >=1)
				echo "<Input type='Radio' name='Action' value='948'>- <a href='#' class='popup'>Payer une beuverie à toute l'escadrille !<span>Il faut bien remonter le moral des troupes de temps en temps, mais attention aux effets secondaires!</span></a><br>";
			if($Commandant !=$PlayerID)
				echo "<Input type='Radio' name='Action' value='3'>- <a href='#' class='popup'>Se plaindre chez le Commandant<span>Idéal pour se faire remarquer!</span></a><br>";
			if(($Personnel[7] >0 or $Pers_Sup) and $Endurance >5)
				echo "<Input type='Radio' name='Action' value='50'><img src='/images/CT4p.png' title='Montant en Crédits Temps gagné via cette action'>- <a href='#' class='popup'>Demander au médecin un stimulant pour tenir toute la nuit!<span>Un petit coup de boost! Attention aux effets secondaires!</span></a><br>";
		}
		elseif($Assis_Bureau)
		{
			if($Credits >=1)
			{
				echo "<Input type='Radio' name='Action' value='1'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>S'en jeter une au mess<span>Il faut bien remonter le moral des troupes de temps en temps! Par cette action le moral ne peut dépasser 100</span></a><br>
				<Input type='Radio' name='Action' value='2'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Secouer ces incapables de la station météo<span>Cela fait du bien au moral, mais risque de vous rendre impopulaire! Par cette action le moral ne peut dépasser 100</span></a><br>
				<Input type='Radio' name='Action' value='4'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Faire un tour en ".$Veh_faction." parce que vous aimez la vitesse<span>Rien de tel que tester son courage! Par cette action le courage ne peut dépasser 100</span></a><br>
				<Input type='Radio' name='Action' value='6'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Se reposer dans vos quartiers<span>Là au moins, vous ne verrez pas la tête de l'infirmière! Vous récupérez de l'endurance</span></a><br>";
				if($Equipage)
					echo "<Input type='Radio' name='Action' value='23'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Emmener ".$Eq_Nom." en virée, il en a bien besoin !<span>Il faut bien remonter le moral des troupes de temps en temps!</span></a><br>";
			}
			if($Datediff >3)
			{
				if($Credits >=2)
					echo "<Input type='Radio' name='Action' value='48'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Payer une tournée à toute l'escadrille !<span>Il faut bien remonter le moral des troupes de temps en temps, mais attention aux effets secondaires!</span></a><br>";
				if($Credits >=15)
					echo "<Input type='Radio' name='Action' value='948'><img src='/images/CT15.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Payer une beuverie à toute l'escadrille !<span>Il faut bien remonter le moral des troupes de temps en temps, mais attention aux effets secondaires!</span></a><br>";
			}
			if($Commandant !=$PlayerID)
				echo "<Input type='Radio' name='Action' value='3'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Se plaindre chez le Commandant<span>Idéal pour se faire remarquer!</span></a><br>";
			if(($Personnel[7] >0 or $Pers_Sup) and $Endurance >5)
				echo "<Input type='Radio' name='Action' value='50'><img src='/images/CT4p.png' title='Montant en Crédits Temps gagné via cette action'>- <a href='#' class='popup'>Demander au médecin un stimulant pour tenir toute la nuit!<span>Un petit coup de boost! Attention aux effets secondaires!</span></a><br>";
			if($Credits >=6)
				echo "<Input type='Radio' name='Action' value='20'><img src='/images/CT6.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Profiter d'une permission bien méritée<span>Idéal pour revenir gonflé à bloc! Récupération de moral et de courage</span></a><br>";
		}
		else
		{
			if($Credits >=1){?>
			<Input type='Radio' name='Action' value='1'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>S'en jeter une au mess<span>Il faut bien remonter le moral des troupes de temps en temps! Par cette action le moral ne peut dépasser 100</span></a><br>
			<?}if($Equipage and $Credits >=2){?>
			<Input type='Radio' name='Action' value='23'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Emmener <?echo $Eq_Nom;?> en virée, il en a bien besoin !<span>Il faut bien remonter le moral des troupes de temps en temps!</span></a><br>
			<?}if($Credits >=5 and $Datediff >3){?>
			<Input type='Radio' name='Action' value='48'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Payer une tournée à toute l'escadrille !<span>Il faut bien remonter le moral des troupes de temps en temps!</span></a><br>
			<?}if($Credits >=30 and $Datediff >3){?>
			<Input type='Radio' name='Action' value='948'><img src='/images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Payer une beuverie à toute l'escadrille !<span>Il faut bien remonter le moral des troupes de temps en temps, mais attention aux effets secondaires!</span></a><br>
			<?}if($Credits >=1){?>
			<Input type='Radio' name='Action' value='2'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Secouer ces incapables de la station météo<span>Cela fait du bien au moral, mais risque de vous rendre impopulaire! Par cette action le moral ne peut dépasser 100</span></a><br>
			<?}if($Credits >=1 and $Commandant !=$PlayerID){?>
			<Input type='Radio' name='Action' value='3'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Se plaindre chez le Commandant<span>Idéal pour se faire remarquer!</span></a><br>
			<?}if($Credits >=1){?>
			<Input type='Radio' name='Action' value='4'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Faire un tour en <?echo $Veh_faction;?> parce que vous aimez la vitesse<span>Rien de tel que tester son courage! Par cette action le courage ne peut dépasser 100</span></a><br>
			<?}if($Credits >=2){?>
			<Input type='Radio' name='Action' value='6'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Se reposer dans vos quartiers<span>Là au moins, vous ne verrez pas la tête de l'infirmière! Vous récupérez de l'endurance</span></a><br>
			<?}if(($Personnel[7] >0 or $Pers_Sup) and $Endurance >5){?>
			<Input type='Radio' name='Action' value='50'><img src='/images/CT4p.png' title='Montant en Crédits Temps gagné via cette action'>- <a href='#' class='popup'>Demander au médecin un stimulant pour tenir toute la nuit!<span>Un petit coup de boost! Attention aux effets secondaires!</span></a><br>
			<?}if($Credits >=$CT_Perm and $Avancement >199){?>
			<Input type='Radio' name='Action' value='20'><img src='/images/CT<?echo $CT_Perm;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- <a href='#' class='popup'>Profiter d'une permission bien méritée<span>Idéal pour revenir gonflé à bloc! Récupération de moral et de courage</span></a><br>
			<?}
		}
		echo "</td></tr></table>";
	}elseif($Tab =="service"){
		if($Credits >=4 and $Reputation >999 and $Missions_Max <6)
		{
			$OfficierEM=GetData("Joueur","ID",$_SESSION['AccountID'],"Officier_em");
			$con=dbconnecti();	
			$resultem=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
			mysqli_close($con);
			if($resultem)
			{
				while($dataem=mysqli_fetch_array($resultem,MYSQLI_ASSOC))
				{
					$Commandant=$dataem['Commandant'];
					$Officier_Adjoint=$dataem['Adjoint_EM'];
					$Officier_EM=$dataem['Officier_EM'];
				}
				mysqli_free_result($resultem);
				unset($dataem);
			}
			if($OfficierEM !=$Commandant and $OfficierEM !=$Officier_Adjoint and $OfficierEM !=$Officier_EM)
				$Bons_ok=true;
			else
				$Bons_ok=false;
		}
		$con=dbconnecti(4);
		$resultm=mysqli_query($con,"SELECT `Date` FROM Events WHERE Event_Type=31 AND PlayerID='$PlayerID' ORDER BY ID DESC LIMIT 1");
		mysqli_close($con);
		if($resultm)
		{
			$data=mysqli_fetch_array($resultm);
			$Date_Mutation=$data[0];
			if($Date_Mutation)
			{
				$con=dbconnecti();
				$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date','$Date_Mutation')"),0);
				mysqli_close($con);
			}
			else
			{
				$con=dbconnecti();
				$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date','2012-09-01')"),0);
				mysqli_close($con);
			}
		}
	?>
		<table class='table'>
			<thead><tr><th>Service</th></tr></thead>
			<tr><td align='left'>
					<?if($Credits >=4 and $QualitePiste <100 and $QualitePiste >0 and $Zone !=6 and $Unite_Type !=10 and $Unite_Type !=12 and $Datediff >3){?>
					<Input type='Radio' name='Action' value='5' title='Le dévouement pour la cause avant tout!'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Aider les pauvres rampants qui oeuvrent à la remise en état de la piste<br>
					<?}if($Credits >=4 and $Tour <100 and $Zone !=6 and $Unite_Type !=10 and $Unite_Type !=12 and $Datediff >3){?>
					<Input type='Radio' name='Action' value='46' title='Le dévouement pour la cause avant tout!'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Aider les pauvres rampants qui oeuvrent à la remise en état de la tour de contrôle<br>
					<?}if($Credits >=2 and $Camouflage <100 and $Zone !=6 and $Unite_Type !=10 and $Unite_Type !=12){?>
					<Input type='Radio' name='Action' value='30' title='Le dévouement pour la cause avant tout!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Participer à l'amélioration du camouflage de la base<br>
					<?}if($Credits >=1 and $Couverture){?>
					<Input type='Radio' name='Action' value='32' title='Scramble!'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Annuler la mission de couverture actuelle de votre pilote<br>
					<?}if($Credits >=1 and $Escorte){?>
					<Input type='Radio' name='Action' value='34' title='Scramble!'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Annuler la mission d'escorte actuelle de votre pilote<br>
					<?}if($Credits >=1){?>
					<Input type='Radio' name='Action' value='49' title='Annulez tout!'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Annuler les missions actuelles et rappeler tous les pilotes à la base<br>
					<?}if($Credits >=8 and $Missions_Jour >0 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='13' title="Le coût total d'une mission supplémentaire de combat est de 8 CT"><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- <?echo $Mission_Sup_Txt;?><br>
					<?}if($Missions_Max <6 and $Reputation >50 and $Avancement >199){?>
					<Input type='Radio' name='Action' value='24' title="Echanger une mission de combat contre des CT"><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'><img src='/images/CT4p.png' title='Montant en Crédits Temps gagné via cette action'>- Se mettre à disposition de l'Etat-Major <a href='#' class='popup'><img src='images/help.png'><span>Cette action nécessite au moins 1 CT disponible!</span></a><br>
					<?$Valid=true;}if($Missions_Max <6 and $Credits >=4){?>
					<Input type='Radio' name='Action' value='31' title='Idéal pour envisager une carrière bureaucratique!'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Passer des heures à potasser les règlements plutôt que de partir en mission<br>
					<?}if($Credits >=4 and $Reputation >49 and $Missions_Max <6 and $Endurance >0){?>
					<Input type='Radio' name='Action' value='36'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Suivre un entrainement Commando<br>
					<?}if($Credits >=2 and $Reputation >99 and $Duperie >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='35'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Se mettre à disposition de l'Etat-Major pour une mission Commando<br>
					<?}/*if($Bons_ok){?>
					<Input type='Radio' name='Action' value='40' title='Cette action augmentera les possibilités du QG de Front'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la promotion des bons de guerre<br>
					<?}/*if($Commandant ==$PlayerID or $Officier_Adjoint ==$PlayerID){
						if($Credits >=10){?>
					<Input type='Radio' name='Action' value='44' title='Cette action augmentera les possibilités de gestion en alimentant les Crédits Mutualisés de 10CTM'><img src='/images/CT10.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/CTM10p.png' title='Montant en Crédits Temps Mutualisés gagné via cette action'>- Se mettre à disposition de l'unité<br>
						<?}if($Credits >=5){?>
					<Input type='Radio' name='Action' value='45' title='Cette action augmentera les possibilités de gestion en alimentant les Crédits Mutualisés de 5CTM'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/CTM5p.png' title='Montant en Crédits Temps Mutualisés gagné via cette action'>- Se mettre à disposition de l'unité<br>
					<?}}elseif($Credits >=10 and $Commandant !=$PlayerID){?>
					<Input type='Radio' name='Action' value='44' title='Cette action augmentera les possibilités de votre Commandant en alimentant les Crédits Mutualisés de 5CTM'><img src='/images/CT10.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/CTM5p.png' title='Montant en Crédits Temps Mutualisés gagné via cette action'>- Se mettre à disposition du Commandant<br>
					<?}*/if($Credits >=5 and $Brevet >0 and ($Avancement >99 or $Reputation >49) and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='47'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Demander votre mutation<br>
					<?}?>
			</td></tr>
		</table>
	<?}elseif($Tab =="formation"){?>
		<table class='table'>
			<thead><tr><th>Formation</th></tr></thead>
			<tr><td align='left'>
					<?if($Credits >=4 and $Reputation >499 and $Avancement >499 and $Skill >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='37'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Acrobatie des pilotes de votre escadrille<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement >499 and $Skill >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='14'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Bombardement des pilotes de votre escadrille<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement >499 and $Skill >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='15'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Détection des pilotes de votre escadrille<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement >499 and $Skill >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='16'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Navigation des pilotes de votre escadrille<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement >499 and $Skill >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='17'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Pilotage des pilotes de votre escadrille<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement >499 and $Skill >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='18'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Tactique des pilotes de votre escadrille<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement >499 and $Skill >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='19'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Tir des pilotes de votre escadrille<br>
					<?}
					$con=dbconnecti();
					$Pilotes_dispos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Cible=0 AND Couverture=0 AND Escorte=0 AND Couverture_Nuit=0 AND Actif=1"),0);
					mysqli_close($con);
					echo "<p class='lead'>".$Pilotes_dispos." Pilotes sont disponibles pour une formation <a href='#' class='popup'><img src='images/help.png'><span>Les pilotes en vol ne bénéficient pas de la formation<br>Former des pilotes nécessite <img src='/images/CT4.png'> et <img src='/images/M1.png'></span></a></p>";
					/*if($Unite ==GetTraining($country) and $Credits >=2){?>
					<Input type='Radio' name='Action' value='39' title='Requis pour suivre une formation théorique au sol, annule toute mission de vol en cours!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Se mettre à disposition d'un instructeur pour une formation<br>
					<?}elseif($Credits >=4 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='38' title='Requis pour suivre une formation théorique au sol, annule toute mission de vol en cours!'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Se mettre à disposition d'un instructeur pour une formation<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement > 499 and $Acrobatie >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='37'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Acrobatie des nouvelles recrues<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement > 499 and $Bombardement >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='14'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Bombardement des nouvelles recrues<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement > 499 and $Vue >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='15'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Détection des nouvelles recrues<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement > 499 and $Navigation >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='16'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Navigation des nouvelles recrues<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement > 499 and $Pilotage >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='17'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Pilotage des nouvelles recrues<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement > 499 and $Tactique >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='18'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Tactique des nouvelles recrues<br>
					<?}if($Credits >=4 and $Reputation >499 and $Avancement > 499 and $Gestion >50 and $Missions_Max <6){?>
					<Input type='Radio' name='Action' value='19'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><img src='/images/M1.png' title='Cette action nécessite 1 mission du jour'>- Aider à la formation en Gestion des nouvelles recrues<br>
					<?}*/?>
			</td></tr>
		</table>
        <div class='row'><div class='col-xs-12 col-sm-6'>
            <div class='alert alert-info'>
                <table class='table'>
                    <thead><tr><th>Rang</th><th>Compétence</th><th>Adjectif</th></tr></thead>
                    <tr><td>Rang 0</td><td>0-24</td><td>Bleu</td></tr>
                    <tr><td>Rang 0</td><td>25-49</td><td>Apte</td></tr>
                    <tr><td>Rang I</td><td>50-74</td><td>Compétent</td></tr>
                    <tr><td>Rang II</td><td>75-99</td><td>Entraîné</td></tr>
                    <tr><td>Rang III</td><td>100-124</td><td>Chevronné</td></tr>
                    <tr><td>Rang IV</td><td>125-149</td><td>Vétéran</td></tr>
                    <tr><td>Rang V</td><td>150-174</td><td>Expert</td></tr>
                    <tr><td>Rang VI</td><td>175-199</td><td>Elite</td></tr>
                    <tr><td>Rang VII</td><td>200+</td><td>Virtuose</td></tr>
                </table>
            </div>
        </div></div>
    <?}elseif($Tab =="renseignement"){?>
		<table class='table'>
			<thead><tr><th>Renseignement</th></tr></thead>
			<tr><td align='left'>
					<?if($Credits >=2 and $Faction >0 and $Avancement >2999){?>
					<Input type='Radio' name='Action' value='7' title="Besoin de glaner des informations sur l'ennemi?"><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Interroger un prisonnier<br>
					<?}if($Credits >=2 and $As){?>
					<Input type='Radio' name='Action' value='22' title="Le détail qui peut faire la différence"><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Discuter avec <?echo $As;?><br>
					<?}if($Credits >=4){?>
					<Input type='Radio' name='Action' value='33' title="Le rat sait où trouver son fromage!"><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Fouiller dans les dossiers de l'Etat-Major<br>
					<?}if($Credits >=1){?>
					<Input type='Radio' name='Action' value='8' title="Le détail qui peut faire la différence"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Rendre visite à l'armurier de l'escadrille<br>
					<?}if($Credits >=1){?>
					<Input type='Radio' name='Action' value='9' title="Le détail qui peut faire la différence"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Rendre visite au mécano de l'escadrille<br>
					<?}if($Credits >=1){?>
					<Input type='Radio' name='Action' value='11' title="Le détail qui peut faire la différence"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Examiner les avions de l'escadrille<br>
					<?}?>
			</td></tr></table>
		<?}elseif($Tab =="reputation")
			{
				echo "<table class='table'><thead><tr><th>Réputation</th></tr></thead><tr><td align='left'>";
				if($Pilote_Salon)
				{
					echo "<Input type='Radio' name='Action' value='27' title='Montrer qui a la classe'>- Soigner votre look<br>
					<Input type='Radio' name='Action' value='21' title='Pour ceux pour qui honneur n'est pas un vain mot!'>- Demander à faire partie de l'escorte honorant les tombés au champ d'honneur<br>";
					if($Victoires)
					{
						echo "<Input type='Radio' name='Action' value='28' title='Montrer à ces bleus qui est as des as!'>- Faire peindre vos marques de victoires sur votre appareil<br>
						<Input type='Radio' name='Action' value='26' title='Montrer à ces bleus qui est as des as!'>- Vous pavaner au mess en faisant l'éloge de vos propres exploits<br>";
						if($Reputation >999)
							echo "<Input type='Radio' name='Action' value='29' title='Le début de la gloire!'>- Répondre à l'invitation d'une radio locale pour prononcer un discours sur le combat aérien<br>";
					}
					if($Probable >0)
						echo "<Input type='Radio' name='Action' value='12' title='A moi!'>- User de son influence pour confirmer une victoire probable<br>";
					if($Favori_General and !$Skill_Fav)
						echo "<Input type='Radio' name='Action' value='25' title='Idéal pour se faire des relations!'>- Assister à la réception donnée par le Général commandant la région<br>";
					elseif($Avancement >4999 and $Reputation >9999)
						echo "<Input type='Radio' name='Action' value='25' title='Idéal pour se faire des relations!'>- Assister à la réception donnée par le Général commandant la région<br>";
				}
				elseif($Assis_Bureau)
				{
					if($Credits >=1)
					{
						echo "<Input type='Radio' name='Action' value='27' title='Montrer qui a la classe'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Soigner votre look<br>
						<Input type='Radio' name='Action' value='21' title='Pour ceux pour qui honneur n'est pas un vain mot!'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Demander à faire partie de l'escorte honorant les tombés au champ d'honneur<br>";
						if($Victoires)
						{
							echo "<Input type='Radio' name='Action' value='28' title='Montrer à ces bleus qui est as des as!'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Faire peindre vos marques de victoires sur votre appareil<br>
							<Input type='Radio' name='Action' value='26' title='Montrer à ces bleus qui est as des as!'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Vous pavaner au mess en faisant l'éloge de vos propres exploits<br>";
							if($Credits >=2 and $Reputation >999)
								echo "<Input type='Radio' name='Action' value='29' title='Le début de la gloire!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Répondre à l'invitation d'une radio locale pour prononcer un discours sur le combat aérien<br>";
						}
					}
					if($Credits >=2 and $Probable >0)
						echo "<Input type='Radio' name='Action' value='12' title='A moi!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- User de son influence pour confirmer une victoire probable<br>";
					if($Favori_General and !$Skill_Fav)
						echo "<Input type='Radio' name='Action' value='25' title='Idéal pour se faire des relations!'>- Assister à la réception donnée par le Général commandant la région<br>";
					elseif($Credits >=2 and $Avancement >4999 and $Reputation >9999)
						echo "<Input type='Radio' name='Action' value='25' title='Idéal pour se faire des relations!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Assister à la réception donnée par le Général commandant la région<br>";
				}
				else
				{
					if($Credits >=1){?>
					<Input type='Radio' name='Action' value='27' title="Montrer qui a la classe"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Soigner votre look<br>
					<?}if($Credits >=2){?>
					<Input type='Radio' name='Action' value='21' title="Pour ceux pour qui l'honneur n'est pas un vain mot!"><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Demander à faire partie de l'escorte honorant les tombés au champ d'honneur<br>
					<?}if($Credits >=1 and $Victoires){?>
					<Input type='Radio' name='Action' value='28' title="Montrer à ces bleus qui est l'as des as!"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Faire peindre vos marques de victoires sur votre appareil<br>
					<?}if($Credits >=2 and $Victoires){?>
					<Input type='Radio' name='Action' value='26' title="Montrer à ces bleus qui est l'as des as!"><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Vous pavaner au mess en faisant l'éloge de vos propres exploits<br>
					<?}if($Credits >=4 and $Victoires and $Reputation >999){?>
					<Input type='Radio' name='Action' value='29' title="Le début de la gloire!"><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Répondre à l'invitation d'une radio locale pour prononcer un discours sur le combat aérien<br>
					<?}if($Credits >=4 and $Probable >0){?>
					<Input type='Radio' name='Action' value='12' title="A moi!"><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- User de son influence pour confirmer une victoire probable<br>
					<?}if($Favori_General and !$Skill_Fav){?>
					<Input type='Radio' name='Action' value='25' title='Idéal pour se faire des relations!'>- Assister à la réception donnée par le Général commandant la région<br>
					<?}elseif($Credits >=4 and $Avancement >4999 and $Reputation >9999){?>
					<Input type='Radio' name='Action' value='25' title='Idéal pour se faire des relations!'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Assister à la réception donnée par le Général commandant la région<br>
					<?}
				}
				echo "</td></tr></table>";
			}
			if($Tab)
			{
				if($Credits or $Valid)
					echo "<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'>";
			}
			else
				echo "<p><img src='images/free".$country.".jpg'></p>";
			echo "</form>";
		}
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>