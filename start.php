<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nav.inc.php');
	$_SESSION['mia_status']=false;
	$_SESSION['start_mission']=true;
	$PlayerID=$_SESSION['PlayerID'];
	if($PlayerID >0)
	{
		$Tevoet=false;
		RetireCandidat($PlayerID,"start");
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Unit,Front,Pays,Credits,Reputation,Avancement,Missions_Jour,MIA,Actif,S_Nuit,Slot5,Slot6,Slot10,Slot11,Simu,Heure_Mission,Equipage
		FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : start-player');
		$resultac=mysqli_query($con,"SELECT Officier,Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
		mysqli_close($con);
		if($resultac)
		{
			while($dataac=mysqli_fetch_array($resultac,MYSQLI_ASSOC))
			{
				$Officier=$dataac['Officier'];
				$Admin=$dataac['Admin'];
			}
			mysqli_free_result($resultac);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Nom=$data['Nom'];
				$Unite=$data['Unit'];
				$Front=$data['Front'];
				$country=$data['Pays'];
				$Credits=$data['Credits'];
				$Reputation=$data['Reputation'];
				$Avancement=$data['Avancement'];
				$Missions_Jour=$data['Missions_Jour'];
				$MIA=$data['MIA'];
				$Actif=$data['Actif'];
				$Nuit=$data['S_Nuit'];
				$Slot5=$data['Slot5'];
				$Slot6=$data['Slot6'];
				$Slot10=$data['Slot10'];
				$Slot11=$data['Slot11'];
				$Simu=$data['Simu'];
				$Heure_Mission=$data['Heure_Mission'];
				$Equipage=$data['Equipage'];
			}
			mysqli_free_result($result);
			unset($result);
		}
		if(!$Simu)
			echo "<p><b>Votre compte est actuellement configuré pour le mode <a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=203&t=5407' target='_blank'>Simulation</a></b></p>";
		if($proxy)
		{
			echo "<p><b>Vous utilisez un serveur proxy pour vous connecter à l'Aube des Aigles,rendant temporairement impossible le départ en mission de vol.</b><br>Veuillez vous rendre sur le forum pour de plus amples informations</p>";
			echo "<br>".Afficher_Image('images/martial'.$country.'.jpg','images/martial'.$country.'.jpg','Consigné au sol');
		}
		elseif($Actif ==1)
		{
			echo "<p>Vous êtes consigné dans vos quartiers en attendant votre jugement par la cour martiale.</p>";
			echo "<br>".Afficher_Image('images/martial'.$country.'.jpg','images/martial'.$country.'.jpg','Cour Martiale');
		}
		elseif($MIA)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Zone,BaseAerienne,Longitude,Flag,Meteo FROM Lieu WHERE ID='$MIA'");
			$resultu=mysqli_query($con,"SELECT Type,Base FROM Unit WHERE ID='$Unite'");
			//mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Ville=$data['Nom'];
					$Zone=$data['Zone'];
					$Flag=$data['Flag'];
					$BaseAerienne=$data['BaseAerienne'];
					$Longitude=$data['Longitude'];
					$Previsions=$data['Meteo'];
				}
				mysqli_free_result($result);
			}
			if($resultu)
			{
				while($data=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
				{
					$Unite_Type=$data['Type'];
					$Base=$data['Base'];
				}
				mysqli_free_result($resultu);
			}
			if($Zone ==6)
				$Type_save=9;
			else
				$Type_save=3;
			//$con=dbconnecti();
			$resultm=mysqli_query($con,"SELECT DISTINCT j.ID,j.Nom FROM Pilote as j,Unit as u WHERE j.Unit=u.ID AND u.Type='$Type_save' AND j.Pays='$country' AND j.Actif=0 AND j.Front='$Front' AND j.ID<>'$PlayerID' ORDER BY j.Nom ASC");
			mysqli_close($con);
			if($resultm)
			{
				while($data=mysqli_fetch_array($resultm,MYSQLI_NUM)) 
				{
					$pil_save.="<option value='".$data[0]."'>".$data[1]."</option>";
				}
				mysqli_free_result($resultm);
			}
			$Dist=GetDistance($MIA,$Base);
			$Distance=$Dist[0];
			if($Distance <20 or $Base ==$MIA or $Flag ==$country)$Tevoet=true;
			if($Unite_Type !=1 and $Equipage)
			{
				$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");
				if($Endu_Eq >0)
					$Equipage_Nom=GetData("Equipage","ID",$Equipage,"Nom");
			}
			$Msg_sauvetage="Demande de sauvetage du pilote <b>".$Nom."</b> dans la région de <b>".$Ville."</b>";
			echo "<h1>Disparu au combat</h1>";	
			?>
				<form action='mission_mia.php' method='post'>
				<input type='hidden' name='meteo' value="<?echo $Previsions_lieu;?>">
				<input type='hidden' name='lieu' value="<?echo $MIA;?>">
				<input type='hidden' name='km' value="<?echo $Distance;?>">
				<input type='hidden' name='zone' value="<?echo $Zone;?>">
				<div class='row'><div class='col-md-6'><img src='images/mia_tent.jpg' alt='Perdu en zone hostile' style='width:100%;'></div>
				<div class='col-md-6'><table class='table'>
					<thead><tr><th>Que faire ?</th></tr></thead>
						<tr><td align='left'>
							<?if($Credits <1){?>
							Vous ne disposez pas de suffisamment de Crédits Temps pour agir !
							<?}else{?>				
							<?if($Credits >=1 and ($Slot11 ==15 or $Slot10 ==73)){?>
							<Input type='Radio' name='Action' value='1' title='Au secours!'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Utiliser une fusée de détresse pour attirer l'attention sur vous<br>
							<?}if($Credits >=1 and $Zone !=6 and $Slot10 ==7){?>
							<Input type='Radio' name='Action' value='2' title='Au secours!'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Allumer un feu pour attirer l'attention sur vous<br>
							<?}if($Credits >=2 and $Zone !=6){?>
							<Input type='Radio' name='Action' value='3' title='Au secours!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Rejoindre l'habitation la plus proche pour réclamer des soins<br>
							<?}if($Credits >=2 and $Zone ==6 and $Slot5 ==35){?>
							<Input type='Radio' name='Action' value='11' title='Au secours!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Tenter d'attirer l'attention d'un navire<br>
							<?}if($Equipage and $Endu_Eq >0 and $Credits >=2 and $Zone !=6){?>
							<Input type='Radio' name='Action' value='4' title='Plutôt lui que moi!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Envoyer <?echo $Equipage_Nom;?> en reconnaissance vers l'habitation la plus proche <a href='#' class='popup'><img src='images/help.png'><span>Attention que cette action peut entrainer la perte DEFINITIVE de votre équipier</span></a><br>
							<?}if($Equipage and $Endu_Eq >0 and $Credits >=2 and $Zone !=6){?>
							<Input type='Radio' name='Action' value='5' title='Plutôt lui que moi!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Envoyer <?echo $Equipage_Nom;?> en reconnaissance vers <?echo $Ville;?> <a href='#' class='popup'><img src='images/help.png'><span>Attention que cette action peut entrainer la perte DEFINITIVE de votre équipier</span></a><br>
							<?}if(!$Nuit and $Credits >=2){?>
							<Input type='Radio' name='Action' value='7' title='Histoire de ne pas se faire repérér trop facilement!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Attendre la nuit<br>
							<?}if($Credits >=2 and $Zone !=6){?>
							<Input type='Radio' name='Action' value='10' title='Toujours utile de glaner quelques renseignements'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Vous diriger vers <?echo $Ville;?><br>
							<?}if($BaseAerienne and $Credits >=2 and $Zone !=6){?>
							<Input type='Radio' name='Action' value='6' title='Il y aura peut-être un avion à emprunter'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Vous diriger vers le terrain d'aviation le plus proche,afin de voler un avion<br>
							<?}if($BaseAerienne and $Credits >=2 and $Zone !=6){?>
							<Input type='Radio' name='Action' value='13' title='Il y aura peut-être du sabotage à effectuer'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Vous diriger vers le terrain d'aviation le plus proche,afin d'effectuer un sabotage<br>
							<?}if($Tevoet and $Credits >=2 and $Zone !=6 and $Slot10 ==5){?>
							<Input type='Radio' name='Action' value='8' title='Vite rentrer!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Tenter de rejoindre votre base à pieds<br>
							<?}if($Tevoet and $Credits >=2 and $Zone ==6 and $Slot6 ==6 and $Longitude <60){?>
							<Input type='Radio' name='Action' value='8' title='Vite rentrer!'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Tenter de rejoindre la terre ferme à la nage<br>
							<?}if(($Credits >=24 and $Zone !=6) or ($Slot11 ==26 and $Credits >=12)){?>
							<Input type='Radio' name='Action' value='12' title='Vite rentrer!'><img src='/images/CT24.png' title='Montant en Crédits Temps que nécessite cette action'>- Utiliser votre contact dans les services secrets pour rentrer au plus vite<br>
							<?}elseif($Credits >=24){?>
							<Input type='Radio' name='Action' value='14' title='Help!'><img src='/images/CT24.png' title='Montant en Crédits Temps que nécessite cette action'>- Errer en méditant sur votre incompétence<br>
							<?}}?>
					</td></tr></table>
				<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
				</div></div>
			<?
				if($pil_save)
				{
					echo "<form action='index.php?view=ground_envoyer' method='post'>
					<input type='hidden' name='exp' value='".$PlayerID."'>
					<input type='hidden' name='em' value='3'>
					<input type='hidden' name='dest_em' value='3'>
					<input type='hidden' name='msg' value='".$Msg_sauvetage."'>
					<input type='hidden' name='Sujet' value='Demande de Sauvetage'>
					<table class='table'>
					<thead><tr><th title='Contactez un pilote de reco de votre faction'>Demander un sauvetage par radio <a href='#' class='popup'><img src='images/help.png'><span>La manière la plus sure et la plus profitable pour rentrer est de demander à un pilote (joueur) de votre faction de venir vous chercher</span></a></th></tr></thead>
					<tr><td><select name='destinataire' class='form-control' style='width: 200px'>".$pil_save."</select></td>
					</tr></table><input type='Submit' value='Demander' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}
		}
		elseif($_SESSION['Distance'] ==0)
		{
			$Au_Sol=false;
			if($Officier >0)
				$Lieu_Reg_Off=GetData("Regiment","Officier_ID",$Officier,"Lieu_ID");
			$con=dbconnecti();
			$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
			$result2=mysqli_query($con,"SELECT Avancement,Avion_Perso,Proto,Escorte,Couverture FROM Pilote WHERE ID='$PlayerID'");
			$result=mysqli_query($con,"SELECT Base,Type,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Mission_Lieu,Mission_Type,Mission_alt,Mission_Flight,Briefing,Porte_avions FROM Unit WHERE ID='$Unite'");
			//mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Base=$data['Base'];
					$Unite_Type=$data['Type'];
					$AvionT1=$data['Avion1'];
					$AvionT2=$data['Avion2'];
					$AvionT3=$data['Avion3'];
					$AvionT1_Nbr=$data['Avion1_Nbr'];
					$AvionT2_Nbr=$data['Avion2_Nbr'];
					$AvionT3_Nbr=$data['Avion3_Nbr'];
					$Mission_Type=$data['Mission_Type'];
					$Mission_Lieu=$data['Mission_Lieu'];
					$Mission_alt=$data['Mission_alt'];
					$Mission_Flight=$data['Mission_Flight'];
					$Briefing=$data['Briefing'];
					$Porte_avions=$data['Porte_avions'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Avancement=$data['Avancement'];
					$Avion_Perso=$data['Avion_Perso'];
					$Proto=$data['Proto'];
					$Escorte_PJ=$data['Escorte'];
					$Couv=$data['Couverture'];
				}
				mysqli_free_result($result2);
			}
			//$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Zone,Latitude,Longitude,Meteo,Meteo_Hour,BaseAerienne,QualitePiste,LongPiste,Port,Plage,Flag,Flag_Air,Flag_Plage,Flag_Port FROM Lieu WHERE ID='$Base'");
			$result2=mysqli_query($con,"SELECT Nom,Autonomie,Engine,Train FROM Avion WHERE ID='$AvionT1'");
			$Pilotes_pas_dispos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND (Task >0 OR Couverture >0 OR Escorte >0 OR Couverture_Nuit >0 OR Cible >0) AND Actif=1"),0);
			$Avion1_dispos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Avion='$AvionT1' AND Actif=1"),0);
			$Avion2_dispos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Avion='$AvionT2' AND Actif=1"),0);
			$Avion3_dispos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Avion='$AvionT3' AND Actif=1"),0);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Zone=$data['Zone'];
					$Previsions=$data['Meteo'];
					$Previsions_Hour=$data['Meteo_Hour'];
					$Piste_Type=$data['BaseAerienne'];
					$Piste=$data['QualitePiste'];
					$LongPiste=$data['LongPiste']*($Piste/100);
					$Latitude=$data['Latitude'];
					$Longitude=$data['Longitude'];
					$Port=$data['Port'];
					$Plage=$data['Plage'];
					$Flag=$data['Flag'];
					$Flag_Air=$data['Flag_Air'];
					$Flag_Plage=$data['Flag_Plage'];
					$Flag_Port=$data['Flag_Port'];
				}
				mysqli_free_result($result);
			}
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Avion1=$data['Nom'];
					$Avion1_a=$data['Autonomie'];
					$Avion1_Engine=$data['Engine'];
					$Avion1_Train=$data['Train'];
				}
				mysqli_free_result($result2);
				unset($data);		
			}
			$Avion1_Fuel=GetData("Moteur","ID",$Avion1_Engine,"Carburant");
			if($Avion2_dispos >0 and $AvionT2_Nbr >0 and $AvionT1 !=$AvionT2)
				$AvionT2_Nbr-=$Avion2_dispos;
			if($Avion3_dispos >0 and $AvionT3_Nbr >0 and $AvionT1 !=$AvionT3 and $AvionT2 !=$AvionT3)
				$AvionT3_Nbr-=$Avion3_dispos;
			if($Avion1_dispos >0 or $AvionT1 ==$AvionT2 or $AvionT1 ==$AvionT3)
				$AvionT1_Nbr-=$Avion1_dispos;
			if($AvionT1_Nbr <0)
			{
				if($AvionT1 ==$AvionT2)
					$AvionT2_Nbr+=$AvionT1_Nbr;
				elseif($AvionT1 ==$AvionT3)
					$AvionT3_Nbr+=$AvionT1_Nbr;
				$AvionT1_Nbr=0;
			}
			$Cr_mission=4;
			$Sqn=GetSqn($country);
			if($Unite_Type !=3 and $Unite_Type !=6 and $Unite_Type !=8 and $Unite_Type !=9)
			{
				$Heure=date('H');
				if($Missions_Jour >1 and $Heure_Mission ==$Heure)
					$Cr_mission=8;
				if($Heure_Mission >0 or $Heure ==0)
					$Heure_mission_txt="<b>Votre dernière mission date de ".$Heure_Mission."h</b>";
				else
					$Heure_mission_txt="<b>Votre dernière mission date de hier</b>";					
				$Occupe_txt="<div class='alert alert-danger'>Toute mission exécutée dans la même heure que la mission précédente coûte <img src='/images/CT8.png'> au lieu de <img src='/images/CT4.png'>.<br>Laisser passer 1 heure permet de ramener le coût à <img src='/images/CT4.png'>.
				<br>Lors de votre première connexion de la journée,vous bénéficiez d'une mission supplémentaire gratuite.
				<br>Effectuer la mission de front à l'heure prévue permet de ramener le coût à <img src='/images/CT4.png'>.
				<br>Les unités de reconnaissance,de transport et de patrouille maritime ne sont pas concernées.
				<br>".$Heure_mission_txt."</div>";
			}
			//Etat occupé		
			if($Couv)
				$Cdt_Mission="<p class='lead'>Votre pilote est déjà assigné à une patrouille!<br><a class='btn btn-danger' href='index.php?view=escadrille_s'>Annulez la</a> si vous désirez effectuer une mission de chasse sur une autre zone <a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=7&t=191' target='_blank'><img src='/images/help.png'></a></p>";
			elseif($Escorte_PJ)
				$Cdt_Mission="<p class='lead'>Votre pilote est déjà assigné à une escorte!<br><a class='btn btn-danger' href='index.php?view=escadrille_s'>Annulez la</a> si vous désirez effectuer une mission de chasse sur une autre zone <a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=7&t=191' target='_blank'><img src='/images/help.png'></a></p>";
			//Etat de la Piste
			if($Porte_avions >0)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,Taille,HP FROM Cible WHERE ID='$Porte_avions'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Nom_PA=$data['Nom'];
						$LongPiste_PA=$data['Taille'];
						$HP_max_PA=$data['HP'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				$HP_PA=GetData("Regiment_IA","Vehicule_ID",$Porte_avions,"HP");
				$Placement_pa=GetData("Regiment_IA","Vehicule_ID",$Porte_avions,"Placement");
				if(!$HP_PA)
				{
					$HP_PA=GetData("Regiment","Vehicule_ID",$Porte_avions,"HP");
					$Placement_pa=GetData("Regiment","Vehicule_ID",$Porte_avions,"Placement");
				}
				if(!$HP_PA)
					$Piste=100;
				else
					$Piste=round(($HP_PA/$HP_max_PA)*100);
				$LongPiste=$LongPiste_PA*($Piste/100);
				$Piste_img="vehicules/vehicule".$Porte_avions.".gif";
				$Piste_txt="La piste du ".$Nom_PA.",longue de ".$LongPiste."m";
				if($Placement_pa ==4 and $Zone !=6 and !$Piste_Type)
					$Amarres=true;
			}
			else
			{
				if($Piste_Type ==3)
				{
					$terrain="Le terrain";
					if($Zone ==8)
						$Piste_img="piste38_".GetQualitePiste_img($Piste).".jpg";
					if($Zone ==0 or $Zone ==2 or $Zone ==3 or $Zone ==9 or $Zone ==11)
						$Piste_img="piste32_".GetQualitePiste_img($Piste).".jpg";
					else
						$Piste_img="piste31_".GetQualitePiste_img($Piste).".jpg";
				}
				elseif($Piste_Type ==2)
				{
					$terrain="La piste et son bassin pour hydravion";
					$Piste_img="piste".$Piste_Type."_".GetQualitePiste_img($Piste).".jpg";
				}
				elseif($Piste_Type ==1)
				{
					$terrain="La piste";
					$Piste_img="piste".$Piste_Type."_".GetQualitePiste_img($Piste).".jpg";
				}
				elseif($Port or $Plage)
				{
					$terrain="Le bassin";
					$Piste_img="hydravion.png";
				}
				//Hydra 
				if($Unite_Type ==9)
				{
					$Hydra_ok=true;
					$Faction_Flag_Plage=GetData("Pays","ID",$Flag_Plage,"Faction");
					$Faction_Flag_Port=GetData("Pays","ID",$Flag_Port,"Faction");
					if($Piste_Type ==2)
						$Piste_txt="Le bassin permet le décollage des hydravions. ";
					elseif($Port and $Faction ==$Faction_Flag_Port)
						$Piste_txt="Les infrastructures portuaires permettent le décollage des hydravions. ";
					elseif($Plage and $Faction ==$Faction_Flag_Plage)
						$Piste_txt="La plage permet le décollage des hydravions. ";
					else
					{
						$Hydra_ok=false;
						$Piste_txt="Le décollage des hydravions n'est pas possible sur cette base! ";
					}
				}
				if($Piste_Type)
				{
					if($Piste <100)
						$Piste_txt.=$terrain.' de votre base est endommagé. '.$terrain.' est praticable à '.$Piste.'% sur une longueur de '.$LongPiste.'m. Etes vous certain de vouloir partir en mission ?';
					else
						$Piste_txt.=$terrain.' de votre base,long de '.$LongPiste.'m,est en parfait état pour un décollage';
				}
			}
			//Interdiction de vol
			if($Zone !=6 and !$Porte_avions)
			{
				$Faction_Flag=GetData("Pays","ID",$Flag,"Faction");
				if($Piste_Type)$Faction_Flag_Air=GetData("Pays","ID",$Flag_Air,"Faction");
				if($Faction ==$Faction_Flag and ($Faction_Flag_Air ==$Faction or $Hydra_ok))
				{
					$con=dbconnecti();
					$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Base' AND r.Position<>25 AND r.Placement=1 AND r.Vehicule_Nbr >0"),0);
					$Amis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Base' AND r.Position<>25 AND r.Placement=1 AND r.Vehicule_Nbr >0"),0);
					//$Enis2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Base' AND r.Position<>25 AND r.Placement=1 AND r.Vehicule_Nbr >0"),0);
					mysqli_close($con);
					//$Enis+=$Enis2;
					if($Enis >0 and $Amis <1)$Au_Sol=true;
				}
				else
					$Au_Sol=true;
			}
			//Prévisions Météo
			if($Previsions <-100)$Previsions-=85;
			$today=getdate();
			if(!$Previsions or ($today['hours'] >$Previsions_Hour+2))
			{
				//$Station=GetData("Unit","ID",$Base,"Station_Meteo")*200;
				$Previsions_temp=GetMeteo($_SESSION['Saison'],$Latitude,$Longitude);
				$Meteo=$Previsions_temp[1];
				$con=dbconnecti();
				$setmeteo=mysqli_query($con,"UPDATE Lieu SET Meteo='".$Meteo."',Meteo_Hour='".$today['hours']."' WHERE ID='".$Base."'");
				mysqli_close($con);
				$_SESSION['Previsionss']=$Previsions_temp[1];
				$Previsions=$Previsions_temp[1];
				$Previsions_txt=$Previsions_temp[0];
				unset($Previsions_temp);
			}
			else
			{
				$_SESSION['Previsionss']=$Previsions;
				$Previsions_txt=GetPrevisions($Previsions);
			}
			$Meteo_txt='La station météo de la base vous informe que les prévisions pour la journée sont '.$Previsions_txt;
			if($Previsions <-20)
				$Meteo_txt.='Il est conseillé aux pilotes non confirmés de ne pas décoller tant que la météo ne sera pas meilleure.';		
			//Stocks
			$start_mission=true;
			$Grade=GetAvancement($Avancement,$country);
			if($Avion1_Fuel >86)
			{
				$Unit1_Stock_Fuel=GetData("Unit","ID",$Unite,"Stock_Essence_".$Avion1_Fuel);
				$AvionT1_Fuel=floor($Unit1_Stock_Fuel/$Avion1_a);
				if($AvionT1_Nbr >$AvionT1_Fuel)
					$AvionT1_Nbr_End=$AvionT1_Fuel;
				else
					$AvionT1_Nbr_End=$AvionT1_Nbr;
			}
			if($Grade[1] >1)
			{
				//Avion Perso
				if($Avion_Perso)
				{
					$Robustesse=GetData("Avions_Persos","ID",$Avion_Perso,"Robustesse");
					if($Robustesse <1)
						SetData("Pilote","Avion_Perso",0,"ID",$PlayerID);
					else
						$Autonomie=GetData("Avions_Persos","ID",$Avion_Perso,"Autonomie");
					if($Robustesse <1000 or $Autonomie <100)
					{
						$Hangar_hover_txt="- Votre mécano vous informe que votre avion personnel <b>n'est pas</b> en état de vol (Robustesse : ".$Robustesse." / Autonomie ".$Autonomie.")";
					}
					else
						$Hangar_hover_txt='- Votre mécano vous informe que votre avion personnel est en état de vol (Robustesse : '.$Robustesse.' / Autonomie '.$Autonomie.')';
				}
				else
					$Hangar_txt="N/A"; 
				//Proto
				if($Proto)
				{
					$con=dbconnecti();
					$resultproto=mysqli_query($con,"SELECT Robustesse,Autonomie,ID_ref FROM Avions_Persos WHERE ID='$Proto'");
					mysqli_close($con);
					if($resultproto)
					{
						while($dataproto=mysqli_fetch_array($resultproto,MYSQLI_ASSOC))
						{
							$Robustesse_proto=$dataproto['Robustesse'];
							$Autonomie_proto=$dataproto['Autonomie'];
							$ID_ref_proto=$dataproto['ID_ref'];
						}
						mysqli_free_result($resultproto);
					}
					$Hangar_txt.=GetAvionIcon($ID_ref_proto,$country,$PlayerID);
					if($Robustesse_proto <1 or $Autonomie_proto <10)
						$Hangar_hover_txt.="<br>- Votre mécano vous informe que votre prototype <b>n'est pas</b> en état de vol (Robustesse : ".$Robustesse_proto." / Autonomie ".$Autonomie_proto.")";
					else
						$Hangar_hover_txt.="<br>- Votre mécano vous informe que votre prototype est en état de vol (Robustesse : ".$Robustesse_proto." / Autonomie ".$Autonomie_proto.")";
				}
				if($Hangar_hover_txt)
					$Hangar_txt.="<div onMouseover=\"ddrivetip('".addslashes($Hangar_hover_txt)."','#E6E1DB','200','200','-100','-1000')\"; onMouseout=\"hideddrivetip()\">".GetAvionIcon(GetData("Avions_Persos","ID",$Avion_Perso,"ID_ref"),$country,$PlayerID)."</div>";
				//Stocks
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,Autonomie,Engine FROM Avion WHERE ID='$AvionT2'");
				$result2=mysqli_query($con,"SELECT Nom,Autonomie,Engine FROM Avion WHERE ID='$AvionT3'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Avion2=$data['Nom'];
						$Avion2_a=$data['Autonomie'];
						$Avion2_Engine=$data['Engine'];
					}
					mysqli_free_result($result);
				}
				if($result2)
				{
					while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
					{
						$Avion3=$data['Nom'];
						$Avion3_a=$data['Autonomie'];
						$Avion3_Engine=$data['Engine'];
					}
					mysqli_free_result($result2);
					unset($data);
				}
				$Avion2_Fuel=GetData("Moteur","ID",$Avion2_Engine,"Carburant");
				if($Avion2_Fuel >86)
				{
					$Unit2_Stock_Fuel=GetData("Unit","ID",$Unite,"Stock_Essence_".$Avion2_Fuel);
					$AvionT2_Fuel=floor($Unit2_Stock_Fuel/$Avion2_a);
					if($AvionT2_Nbr >$AvionT2_Fuel)
						$AvionT2_Nbr_End=$AvionT2_Fuel;
					else
						$AvionT2_Nbr_End=$AvionT2_Nbr;
				}
				$Avion3_Fuel=GetData("Moteur","ID",$Avion3_Engine,"Carburant");
				if($Avion3_Fuel >86)
				{
					$Unit3_Stock_Fuel=GetData("Unit","ID",$Unite,"Stock_Essence_".$Avion3_Fuel);
					$AvionT3_Fuel=floor($Unit3_Stock_Fuel/$Avion3_a);
					if($AvionT3_Nbr >$AvionT3_Fuel)
						$AvionT3_Nbr_End=$AvionT3_Fuel;
					else
						$AvionT3_Nbr_End=$AvionT3_Nbr;
				}
				if(($Unit1_Stock_Fuel <$Avion1_a and $Unit2_Stock_Fuel <$Avion2_a and $Unit3_Stock_Fuel <$Avion3_a) or (!$AvionT1_Nbr and !$AvionT2_Nbr and !$AvionT3_Nbr))
					$start_mission=false;
			}
			else
			{
				if(!$AvionT1_Nbr or $Unit1_Stock_Fuel <$Avion1_a)
					$start_mission=false;
			}
			if($AvionT1_Nbr <0 or $AvionT2_Nbr <0 or $AvionT3_Nbr <0)$start_mission=false;					
			if(!$start_mission)
			{
				echo "<h1>Mission Annulée</h1><p><img src='images/oo_essence".$country.".jpg' alt='Départ en mission'></p>
				<div class='alert alert-danger'>Votre mécano vous informe qu'il n'y a pas d'avion disponible pour partir en mission, soit parce qu'aucun n'est en état de vol, soit par manque de carburant.
				<br>Vérifiez également dans le <a href='index.php?view=esc_missions' class='lien'>tableau des missions</a> si vos pilotes ne sont pas toujours en vol. Si besoin,rappelez les pour libérer des appareils.</div>";
			}
			elseif($Unite_Type ==8)
			{
				if($Reputation <500)
				{
					$Briefing="<table class='table'><thead><tr><th>Briefing du Commandant</th></tr></thead><tr><td>
					<div class='alert alert-warning'>Vous êtes dans cette unité pour parfaire votre formation de pilote. Effectuez des missions d'entrainement jusqu'à obtenir votre brevet. Lisez bien les <a href='#' class='popup'><img src='images/help.png'><span>Passez votre souris ici pour voir apparaitre les conseils et aides</span></a> ou consultez <a href='index.php?view=regles' class='lien'>l'aide</a>
					<br>Cette étape ne sera certes pas très longue,mais nécessaire pour maîtriser les bases du maniement de votre appareil. Une fois cette formalité accomplie,vous pourrez <a href='index.php?view=escadrille_s' class='lien'>demander votre mutation</a> dans une unité de combat. La formation au combat proprement dite sera dispensée dans ces unités.
					<br>Prenez contact avec <a href='index.php?view=em_actus' class='lien'>vos chefs</a> le plus tôt possible,car seul vous ne pourrez aller bien loin...</div></td></tr></table>";
				}
				else
				{
					$Briefing="<table class='table'><thead><tr><th>Briefing du Commandant</th></tr></thead><tr><td>
					<div class='alert alert-warning'>Vous êtes dans cette unité pour parfaire votre formation de pilote.
					<br>Aucun avion ne peut être perdu par accident en mission d'entrainement,profitez-en pour effectuer des vols d'essai!</div></td></tr></table>";
				}		
				echo "<h1>Départ en Mission</h1>
				<form action='index.php?view=mission' method='post'>
				<div class='row'><div class='col-md-6'><img src='images/mission".$country.".jpg' alt='Départ en mission' style='width:100%'></div><div class='col-md-6'><table class='table'>
					<thead><tr><th>Etat de la piste</th><th>Prévisions météo <a href='#' class='popup'><img src='images/help.png'><span>Cette prévision concerne la base, pour visionner la météo de votre cible veuillez consulter le détail du lieu sur la carte avant de partir en mission</span></a></th></tr></thead>
					<tr><td title='".$Piste_txt."'><img src='images/".$Piste_img."'></td><td title='".$Meteo_txt."'><img src='images/meteo".$_SESSION['Previsionss'].".gif'></td></tr></table></div></div>
					".$Briefing;
				if($Au_Sol)
					echo "<p class='lead'>Votre base est sous le feu des troupes terrestres ennemies,tout décollage est impossible !</p>";
				elseif($Amarres)
					echo "<p class='lead'>Votre porte-avions est amarré et il n'existe pas de piste dans les alentours,tout décollage est impossible !</p>";
				else
					echo "<h3>Départ en mission</h3><input type='Submit' value='CONFIRMER' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form><img src='/images/CT".$Cr_mission.".png' title='Montant en Crédits Temps que nécessite cette action'>";
				echo $skills;
			}
			else
			{
				$Lieu_M="Lieu_Mission".$Unite_Type;
				$Type_M="Type_Mission".$Unite_Type;
				$con=dbconnecti();
				$Patrouille_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Couverture='$Base' AND Pays='$country' AND Actif=1"),0);
				$result=mysqli_query($con,"SELECT $Lieu_M,$Type_M,Co_Heure_Mission,Briefing FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
				if($result)
				{
					while($data=mysqli_fetch_array($result))
					{
						$MH_Lieu=$data[0];
						$MH_Mission=$data[1];
						$Co_Heure_Mission=$data[2];
						$Briefing_EM=$data[3];
						if($MH_Lieu and $MH_Lieu !=$Lieu_Reg_Off1 and $MH_Lieu !=$Lieu_Reg_Off2)
						{
							//$con=dbconnecti();
							$resultmh=mysqli_query($con,"SELECT Nom,Longitude,Latitude FROM Lieu WHERE ID='$MH_Lieu'");
							//mysqli_close($con);
							if($resultmh)
							{
								while($datamh=mysqli_fetch_array($resultmh,MYSQLI_ASSOC))
								{
									$MH_Nom=$datamh['Nom'];
									$MH_Longitude=$datamh['Longitude'];
									$MH_Latitude=$datamh['Latitude'];
								}
								mysqli_free_result($resultmh);
							}
							$Dist_EM=GetDistance(0,0,$Longitude,$Latitude,$MH_Longitude,$MH_Latitude);
							$MH_Nom.=" (".$Dist_EM[0]."km)";
							$MH_Type=GetMissionType($MH_Mission);
						}
					}
					mysqli_free_result($result);
					unset($data);
				}
				mysqli_close($con);
				if($MH_Nom)
				{
					//EM
					if($MH_Mission !=7 and $MH_Mission !=17)
					{
						if($MH_Mission >10 and $MH_Mission <15)
						{
							$con=dbconnecti();
							$pj_unit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$MH_Lieu' AND Vehicule_Nbr >0 AND Pays<>'$country' AND Visible=1"),0); //SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$MH_Lieu' AND Vehicule_Nbr >0 AND Pays<>'$country' AND Visible=1
							$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Escorte='$MH_Lieu' AND Pays='$country' UNION SELECT COUNT(*) FROM Pilote_IA WHERE Escorte='$MH_Lieu' AND Pays='$country' AND Actif=1"),0);
							mysqli_close($con);
							if($pj_unit)
								$Recce_EM="La cible navale désignée par l'état-major est reconnue";
							else
								$Recce_EM="<b>Attention,la cible navale désignée par l'état-major n'est pas reconnue ou est obsolète! Nous vous conseillons de ne pas effectuer votre mission d'état-major!</b>";
						}
						elseif($MH_Mission <3 or $MH_Mission ==5)
						{
							$con=dbconnecti();
							$pj_unit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$MH_Lieu' AND Vehicule_Nbr >0 AND Pays<>'$country' AND Visible=1"),0); //SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$MH_Lieu' AND Vehicule_Nbr >0 AND Pays<>'$country' AND Visible=1
							$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Escorte='$MH_Lieu' AND Pays='$country' UNION SELECT COUNT(*) FROM Pilote_IA WHERE Escorte='$MH_Lieu' AND Pays='$country' AND Actif=1"),0);
							mysqli_close($con);
							if($pj_unit)
								$Recce_EM="La cible désignée par l'état-major est reconnue";
							else
								$Recce_EM="<b>Attention,la cible désignée par l'état-major n'est pas reconnue ou est obsolète!";
						}
						else
						{
							$Recce_l=GetData("Lieu","ID",$MH_Lieu,"Recce");
							if($Recce_l >1)
								$Recce_EM.="<br>La cible d'Etat-Major est reconnue et éclairée pour les missions de nuit";
							elseif($Recce_l ==1)
								$Recce_EM.="<br>La cible d'Etat-Major est reconnue,mais pas marquée.<br>Le bombardement de nuit est déconseillé!";
							elseif($MH_Mission ==6 or $MH_Mission ==8 or $MH_Mission ==16)
								$Recce_EM.="<br><b>Attention,la cible désignée par l'état-major n'est pas reconnue! Nous vous conseillons de ne pas effectuer la mission d'état-major!</b>";
							$con=dbconnecti();
							$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Escorte='$MH_Lieu' AND Pays='$country' UNION SELECT COUNT(*) FROM Pilote_IA WHERE Escorte='$MH_Lieu' AND Pays='$country' AND Actif=1"),0);
							mysqli_close($con);
						}
						if($Escorte_nbr >0)
						{
							$height_re=0;
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p WHERE j.ID=l.Joueur AND j.Pays=p.ID
							AND j.Escorte='$MH_Lieu' AND p.Faction='$Faction'");
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
									$Recce_EM_e.="<tr><th>".$data['Nom']." ".GetAvionIcon($data['Avion'],$data['Pays'],$data['ID'],$data['Unit'],$Front)."</th><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td><td>".$data['S_alt']."m</td></tr>";
									$height_re+=75;
								}
								mysqli_free_result($result);
								if($height_re >150)$height_re=150;
								if($Recce_EM_e)
									$Recce_EM.="<div style='overflow:auto; width: 450px; height: ".$height_re."px;'><table><tr><td colspan='10'>".$Escorte_nbr." unités de chasseurs en escorte sur votre cible</td></tr>".$Recce_EM_e."</table></div>";
							}
						}
						else
							$Recce_EM.="<br>Aucun chasseur n'est actuellement assigné à votre escorte pour la mission d'Etat-Major";
					}
					else
					{
						$height_pat=0;
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p WHERE j.ID=l.Joueur AND j.Pays=p.ID
						AND j.Couverture='$MH_Lieu' AND p.Faction='$Faction'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Recce_EM_e.="<tr><th>".$data['Nom']." ".GetAvionIcon($data['Avion'],$data['Pays'],$data['ID'],$data['Unit'],$Front)."</th><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td><td>".$data['S_alt']."m</td></tr>";
								$height_pat+=75;
							}
							mysqli_free_result($result);
							if($height_pat >150)$height_pat=150;
							if($Recce_EM_e)
								$Recce_EM.="<div style='overflow:auto; width: 450px; height: ".$height_pat."px;'><table><tr><td colspan='10'>Unités de chasseurs en patrouille sur votre objectif</td></tr>".$Recce_EM_e."</table></div>";
						}
					}
					if($Patrouille_nbr >0)
					{
						$height_pat=0;
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p WHERE j.ID=l.Joueur AND j.Pays=p.ID
						AND j.Couverture='$Base' AND p.Faction='$Faction'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Recce_EM_eb.="<tr><th>".$data['Nom']." ".GetAvionIcon($data['Avion'],$data['Pays'],$data['ID'],$data['Unit'],$Front)."</th><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td><td>".$data['S_alt']."m</td></tr>";
								$height_pat+=75;
							}
							mysqli_free_result($result);
							if($height_pat >150)$height_pat=150;
							if($Recce_EM_eb)
								$Recce_EM.="<div style='overflow:auto; width: 450px; height: ".$height_pat."px;'><table><tr><td colspan='10'>".$Patrouille_nbr." unités de chasseurs en couverture sur votre base</td></tr>".$Recce_EM_eb."</table></div>";
						}
					}
					else
						$Recce_EM.="<br>Aucun chasseur n'est actuellement assigné en couverture sur votre base";
					if($Co_Heure_Mission >5)
					{
						$Co_Heure_Mission2=$Co_Heure_Mission+1;
						$Briefing_EM='<p>Mission de Front : <b>'.$MH_Type.' sur '.$MH_Nom.' entre '.$Co_Heure_Mission.'h et '.$Co_Heure_Mission2.'h</b><br>'.$Recce_EM.'<br>'.nl2br($Briefing_EM).'</p>';
					}
					else
						$Briefing_EM='<p>Mission de Front : <b>'.$MH_Type.' sur '.$MH_Nom.'</b><br>'.$Recce_EM.'<br>'.nl2br($Briefing_EM).'</p>';
				}
				elseif($MH_Lieu)
					$Briefing_EM="Votre pilote ne peut pas effectuer la mission d'état-major car un de vos officiers est présent sur le lieu de destination.";
				else
					$Briefing_EM="Aucune mission d'état-major définie.";
				if($Avancement >4999 and ($Unite_Type ==1 or $Unite_Type ==4))
					$Alerte_Radar="<a class='btn btn-primary' href='index.php?view=em_radar'>Alertes</a>";
				$Briefing_EM="<table class='table'><thead><tr><th colspan='2'>Message de l'Etat-Major</th></tr></thead><tr><td>".$Briefing_EM."</td><td>".$Alerte_Radar."</td></tr></table>";			
				if($Briefing and $Mission_Type)
				{
					if($Mission_Flight ==3)
						$Avion_Nom=$Avion3;
					elseif($Mission_Flight ==2)
						$Avion_Nom=$Avion2;
					else
						$Avion_Nom=$Avion1;
					//Msg indiquant si la cible est reconnue
					if($Mission_Type ==7 or $Mission_Type ==17)
					{
						$height_re=0;
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p WHERE j.ID=l.Joueur AND j.Pays=p.ID
						AND j.Couverture='$Mission_Lieu' AND p.Faction='$Faction'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Recce_e.="<tr><th>".$data['Nom']." ".GetAvionIcon($data['Avion'],$data['Pays'],$data['ID'],$data['Unit'],$Front)."</th><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td><td>".$data['S_alt']."m</td></tr>";
								$height_re+=75;
							}
							mysqli_free_result($result);
							if($height_re >150)$height_re=150;
							if($Recce_e)
								$Recce.="<div style='overflow:auto; width: 450px; height: 150px;'><table><tr><td colspan='10'>Unités de chasseurs en patrouille sur votre objectif</td></tr>".$Recce_e."</table></div>";
						}
					}
					elseif($Mission_Type >10 and $Mission_Type <15)
					{
						$con=dbconnecti();
						$pj_unit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Mission_Lieu' AND Vehicule_Nbr >0 AND Pays<>'$country' AND Visible=1"),0); //SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$Mission_Lieu' AND Vehicule_Nbr >0 AND Pays<>'$country' AND Visible=1
						$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Escorte='$Mission_Lieu' AND Pays='$country' UNION SELECT COUNT(*) FROM Pilote_IA WHERE Escorte='$Mission_Lieu' AND Pays='$country' AND Actif=1"),0);
						mysqli_close($con);
						if($pj_unit)
							$Recce.="La cible navale est reconnue";
						else
							$Recce.="<b>Attention,la cible navale désignée n'est pas reconnue ou est obsolète! Nous vous conseillons de ne pas effectuer votre mission d'unité!</b>";
					}
					elseif($Mission_Type ==1 or $Mission_Type ==2)
					{
						$con=dbconnecti();
						$pj_unit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Mission_Lieu' AND Vehicule_Nbr >0 AND Pays<>'$country' AND Visible=1"),0); //SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$Mission_Lieu' AND Vehicule_Nbr >0 AND Pays<>'$country' AND Visible=1
						$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Escorte='$Mission_Lieu' AND Pays='$country' UNION SELECT COUNT(*) FROM Pilote_IA WHERE Escorte='$Mission_Lieu' AND Pays='$country' AND Actif=1"),0);
						mysqli_close($con);
						if($pj_unit)
							$Recce.="La cible terrestre est reconnue";
						else
							$Recce.="<b>Attention,la cible terrestre désignée n'est pas reconnue ou est obsolète! Nous vous conseillons de ne pas effectuer votre mission d'unité!</b>";
					}
					elseif($Mission_Type ==6 or $Mission_Type ==8 or $Mission_Type ==16)
					{
						$Recce_cible=GetData("Lieu","ID",$Mission_Lieu,"Recce");
						if($Recce_cible >1)
							$Recce.="La cible d'unité est reconnue et éclairée pour les missions de nuit";
						elseif($Recce_cible ==1)
							$Recce.="La cible d'unité est reconnue,mais pas marquée.<br>Le bombardement de nuit est déconseillé!";
						else
							$Recce.="<b>Attention,la cible désignée par votre unité n'est pas reconnue! Nous vous conseillons de ne pas effectuer la mission d'unité!</b>";
						$con=dbconnecti();
						$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Escorte='$Mission_Lieu' AND Pays='$country' UNION SELECT COUNT(*) FROM Pilote_IA WHERE Escorte='$Mission_Lieu' AND Pays='$country' AND Actif=1"),0);
						mysqli_close($con);
					}
					elseif($Mission_Type ==4)
					{
						$con=dbconnecti();
						$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Escorte='$Mission_Lieu' AND Pays='$country' UNION SELECT COUNT(*) FROM Pilote_IA WHERE Escorte='$Mission_Lieu' AND Pays='$country' AND Actif=1"),0);
						mysqli_close($con);
					}
					if($Escorte_nbr >0)
					{
						$height_re=0;
						/*$con=dbconnecti();
						$Alt_Moy=mysqli_result(mysqli_query($con,"SELECT AVG(S_alt) FROM Pilote WHERE Escorte='$Mission_Lieu' AND ID<>'$PlayerID' AND Pays='$country'"),0);
						mysqli_close($con);
						$Recce.="<br><b>".$Escorte_nbr."</b> chasseurs sont actuellement assignés à votre escorte,à une altitude moyenne de ".round($Alt_Moy)."m";*/
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p WHERE j.ID=l.Joueur AND j.Pays=p.ID
						AND j.Escorte='$Mission_Lieu' AND p.Faction='$Faction'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Recce_e.="<tr><th>".$data['Nom']." ".GetAvionIcon($data['Avion'],$data['Pays'],$data['ID'],$data['Unit'],$Front)."</th><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td><td>".$data['S_alt']."m</td></tr>";
								$height_re+=75;
							}
							mysqli_free_result($result);
							if($height_re >150)$height_re=150;
							if($Recce_e)
								$Recce.="<div style='overflow:auto; width: 450px; height: ".$height_re."px;'><table><tr><th colspan='10'>".$Escorte_nbr." Chasseurs en escorte sur votre cible</th></tr>".$Recce_e."</table></div>";
						}
					}
					else
						$Recce.="<br>Aucun chasseur n'est actuellement assigné à votre escorte pour la mission d'unité";
					if(!$MH_Nom)
					{
						if($Patrouille_nbr >0)
						{
							$height_re=0;
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p WHERE j.ID=l.Joueur AND j.Pays=p.ID
							AND j.Couverture='$Base' AND p.Faction='$Faction'");
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
									$Recce_e.="<tr><th>".$data['Nom']." ".GetAvionIcon($data['Avion'],$data['Pays'],$data['ID'],$data['Unit'],$Front)."</th><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td><td>".$data['S_alt']."m</td></tr>";
									$height_re+=75;
								}
								mysqli_free_result($result);
								if($height_re >150)$height_re=150;
								if($Recce_e)
									$Recce.="<div style='overflow:auto; width: 450px; height: ".$height_re."px;'><table><tr><th colspan='10'>".$Patrouille_nbr." Chasseurs en couverture sur votre base</th></tr>".$Recce_e."</table></div>";
							}
						}
						else
							$Recce.="<br>Aucun chasseur n'est actuellement assigné en couverture sur votre base";
					}
					$Briefing="<table class='table'><thead><tr><th>Briefing du Commandant</th></tr></thead><tr><td>
					Vous effectuerez une mission de <b>".GetMissionType($Mission_Type)."</b> sur l'objectif de <b>".GetData("Lieu","ID",$Mission_Lieu,"Nom")."</b>
					<br>L'altitude du plan de vol est de ".$Mission_alt."m
					<br>L'avion préconisé pour cette mission est le <b>".$Avion_Nom." du ".$Sqn." ".$Mission_Flight."</b>
					<hr>".nl2br($Briefing)."</td></tr></table>";
				}
				/*else
					$Briefing="<b>Aucune mission d'unité n'a été définie pour aujourd'hui.</b>";*/		
				if($Avancement >99)
				{
					$Demande_txt2="";
					$Demande_txt.="<hr><h3>Demandes de Missions <a href='#' class='popup'><img src='images/help.png'><span>Ces missions sont demandées par des joueurs commandant des escadrilles,des bataillons ou des flottilles</span></a></h3>
					<a class='btn btn-default' href='index.php?view=esc_mission'>Demander une mission</a><table class='table'><thead><tr>
						<th>Mission demandée</th>
						<th>Cible</th>
						<th>Distance <a href='#' class='popup'><img src='images/help.png'><span>Distance entre la base et la cible. L'autonomie de l'avion devra donc être au moins égale au double de cette distance et idéalement une marge de sécurité en cas de mauvais temps ou de combat aérien.</span></a></th>
						<th>Statut Reco <a href='#' class='popup'><img src='images/help.png'><span>Reco stratégique permettant de bombarder les infrastructures. Les attaques de troupes au sol ne sont pas affectées par ce statut</span></a></th>
						<th>Demandeur</th>
						</tr></thead>";
					$Coord=GetCoord($Front);
					$Lat_base_min=$Coord[0];
					$Lat_base_max=$Coord[1];
					$Long_base_min=$Coord[2];
					$Long_base_max=$Coord[3];
					$query_dem="(SELECT DISTINCT l.Nom,l.Zone,u.Mission_Type_D,p.Pays_ID,u.Nom,l.Recce,l.ID,l.Latitude,l.Longitude FROM Unit as u,Lieu as l,Pays as p
					WHERE u.Pays=p.Pays_ID AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND u.Mission_Lieu_D >0 AND u.Mission_Type_D >0 AND p.Faction='$Faction' AND u.Mission_Lieu_D=l.ID) 
					UNION (SELECT DISTINCT l.Nom,l.Zone,r.Mission_Type_D,r.Pays,r.ID,l.Recce,l.ID,l.Latitude,l.Longitude FROM Lieu as l,Regiment_IA as r,Pays as p WHERE r.Pays=p.Pays_ID AND r.Mission_Lieu_D=l.ID AND r.Mission_Lieu_D >0 AND r.Mission_Type_D >0 AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off'))
					UNION (SELECT DISTINCT l.Nom,l.Zone,7,l.Flag,l.Nom,l.Recce,l.ID,l.Latitude,l.Longitude FROM Lieu as l,Attaque as a WHERE a.Lieu=l.ID AND DATE(a.Date)=DATE(NOW()) AND l.Flag='$country' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off'))
					UNION (SELECT DISTINCT l.Nom,l.Zone,7,l.Flag,l.Nom,l.Recce,l.ID,l.Latitude,l.Longitude FROM Lieu as l,Bombardement as b WHERE b.Lieu=l.ID AND DATE(b.Date)=DATE(NOW()) AND l.Flag='$country' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off'))
					"; //) ORDER BY l.Nom ASC //SELECT * FROM (
					/*UNION (SELECT DISTINCT l.Nom,l.Zone,o.Mission_Type_D,p.Pays_ID,o.Nom,l.Recce,l.ID,l.Latitude,l.Longitude FROM Officier as o,Lieu as l,Pays as p
					WHERE o.Pays=p.Pays_ID AND o.Front='$Front' AND o.Mission_Lieu_D >0 AND o.Mission_Type_D >0 AND p.Faction='$Faction' AND o.Mission_Lieu_D=l.ID AND o.ID NOT IN ('$Officier'))*/
					$con=dbconnecti();
					$result=mysqli_query($con,$query_dem);
					if($result)
					{
						while($Data=mysqli_fetch_array($result,MYSQLI_NUM)) 
						{
							if($Data[1] ==6)
							{
								//$con=dbconnecti();
								$Nav_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='".$Data[6]."' AND Pays<>'$country' AND Vehicule_ID >4999 AND Vehicule_Nbr >0 AND Visible=1"),0); //SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$Data[6]' AND Pays<>'$country' AND Vehicule_ID >4999 AND Vehicule_Nbr >0 AND Visible=1
								//mysqli_close($con);
								if($Nav_eni >0)
									$Recced='Oui';
								else
									$Recced='Non';
							}
							else
							{
								if($Data[5] ==2)
									$Recced='<b>Eclairé</b>';
								elseif($Data[5] ==1)
									$Recced='<b>Oui</b>';
								else
									$Recced='Non';
							}
							if(is_numeric($Data[4]))
								$Data[4].='e Cie';
							$Dist=GetDistance(0,0,$Longitude,$Latitude,$Data[8],$Data[7]);
							$Demande_txt2.="<tr><td>".GetMissionType($Data[2])."</td><td><img src='images/zone".$Data[1].".jpg'> ".$Data[0]."</td><td>".$Dist[0]."km</td><td>".$Recced."</td><td><img src='".$Data[3]."20.gif'> ".$Data[4]."</td></tr>";		
						}
						mysqli_free_result($result);
					}
					mysqli_close($con);
					if(!$Demande_txt2)
						$Demande_txt.="<tr><td colspan='4'>Aucune demande actuellement</td></tr></table>";
					else
						$Demande_txt.=$Demande_txt2."</table>";
				}
				if($Pilotes_pas_dispos >0)
					$Rappeler_pilotes="<br><a class='btn btn-danger' href='index.php?view=esc_missions'>Rappeler les pilotes</a>";
				if($LongPiste)$LongPiste_txt="<br>".$LongPiste."m";
				echo "<h1>Départ en Mission</h1><form action='index.php?view=mission' method='post'>
				<div class='row'><div class='col-md-6'><img src='images/mission".$country.".jpg' alt='Départ en mission' style='width:100%;'></div>
				<div class='col-md-6'><table class='table'><thead><tr><th>Etat de la piste</th><th>Prévisions météo <a href='#' class='popup'><img src='images/help.png'><span>Cette prévision concerne la base, pour visionner la météo de votre cible veuillez consulter le détail du lieu sur la carte avant de partir en mission</span></a></th><th>Hangar personnel</th></tr></thead>
					<tr><td title='".$Piste_txt."'><img src='images/".$Piste_img."'>".$LongPiste_txt."</td><td title='".$Meteo_txt."'><img src='images/meteo".$_SESSION['Previsionss'].".gif'></td><td>".$Hangar_txt."</td></tr></table>
					<table class='table'><thead><tr><th>Pilotes en mission <a href='#' class='popup'><img src='images/help.png'><span>Les pilotes actuellement en mission ne sont pas disponibles</span></a></th><th>Avions disponibles <a href='#' class='popup'><img src='images/help.png'><span>Le manque de carburant ou de munitions peut réduire le nombre effectivement disponible (à gauche du /)</span></a></th></tr></thead>
					<tr><td>".$Pilotes_pas_dispos.$Rappeler_pilotes."</td><td><i>1 ".$Sqn."</i> <b>".$AvionT1_Nbr_End."/".$AvionT1_Nbr."</b> ".GetAvionIcon($AvionT1,$country,0,$Unite,$Front)."<br><i>2 ".$Sqn."</i> <b>".$AvionT2_Nbr_End."/".$AvionT2_Nbr."</b> ".GetAvionIcon($AvionT2,$country,0,$Unite,$Front)."<br><i>3 ".$Sqn."</i> <b>".$AvionT3_Nbr_End."/".$AvionT3_Nbr."</b> ".GetAvionIcon($AvionT3,$country,0,$Unite,$Front)."
					</td></tr></table>
				</div>
					".$Cdt_Mission.$Briefing_EM.$Briefing."
					<tr><td>".$Recce."</td></tr>".$Demande_txt.$Occupe_txt;
				if(!$Au_Sol)
					echo "<hr><h3>Départ en mission</h3><input type='Submit' value='CONFIRMER' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'><img src='/images/CT".$Cr_mission.".png' title='Montant en Crédits Temps que nécessite cette action'>";
				else
					echo "<p class='lead'>Votre base est sous le feu des troupes terrestres ennemies,tout décollage est impossible !</p>";
				//MAJ echo "<p class='lead'>Une mise à jour importante a eu lieu,tout décollage est momentanément impossible !</p>";
				echo $skills.'</form>';
			}
		}
		else
			header("Location: ./tsss.php");
	}
	else
		header("Location: ./tsss.php");
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>