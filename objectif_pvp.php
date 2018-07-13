<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$Action=Insec($_POST['Action']);
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$meteo=Insec($_POST['Meteo']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$Battle=Insec($_POST['Battle']);
$Faction=Insec($_POST['Camp']);
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $_SESSION['objectif'] ==false AND $avion >0 AND !empty($_POST))
{
	$_SESSION['naviguer']=false;
	$_SESSION['objectif']=true;
	$_SESSION['done']=true;
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Distance_totale=$_SESSION['Distance'];
	$PVP=$_SESSION['PVP'];
	$retour=false;
	$bomb=false;
	$patrol=false;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Equipage,Vue,S_Avion_db,S_Nuit,S_Cible,S_Mission,S_Longitude,S_Latitude,S_Cible_Atk,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Escorte_nbr,S_Equipage_Nbr,S_Escorteb,S_Escorteb_nbr,S_Engine_Nbr,S_Essence,S_Blindage,S_Formation,Slot4,Simu,Sandbox
	FROM Pilote_PVP WHERE ID='$Pilote_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : objectif-player');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$HP=$data['S_HP'];
			$Vue=$data['Vue'];
			$Avion_db=$data['S_Avion_db'];
			$Cible=$data['S_Cible'];
			$Nuit=$data['S_Nuit'];
			$Mission_Type=$data['S_Mission'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Cible_Atk=$data['S_Cible_Atk'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Avion_Bombe_nbr=$data['S_Avion_Bombe_Nbr'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Escorte_nbr=$data['S_Escorte_nbr'];
			$Escorteb=$data['S_Escorteb'];
			$Escorteb_nbr=$data['S_Escorteb_nbr'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$S_Blindage=$data['S_Blindage'];
			$Formation=$data['S_Formation'];
			$Slot4=$data['Slot4'];
			$Simu=$data['Simu'];
			$Sandbox=$data['Sandbox'];
		}
		mysqli_free_result($result);
		unset($data);
	}	
	if($HP <1 or !$Cible)
		$end_mission=true;
	else
	{			
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Type,Pays,Puissance,Robustesse,Masse,Plafond,ArmePrincipale,ArmeSecondaire,Detection,Radio,Radar,Blindage,Volets FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : objectif_pvp-avion');
		$result2=mysqli_query($con,"SELECT ValeurStrat,DefenseAA_temp,Recce,Zone,Map,Meteo,BaseAerienne,Flag,Mines_m FROM Lieu WHERE ID='$Cible'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : objectif_pvp-cible');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Type_avion=$data['Type'];
				$country=$data['Pays'];
				$Puissance_nominale=$data['Puissance'];
				$HPmax=$data['Robustesse'];
				$Masse=$data['Masse'];
				$Plafond=$data['Plafond'];
				$Arme1Avion=$data['ArmePrincipale'];
				$Arme2Avion=$data['ArmeSecondaire'];
				$Vue_avion=$data['Detection'];
				$Radio_avion=$data['Radio'];
				$Radar_avion=$data['Radar'];
				$Blindage=$data['Blindage'];
				$Volets=$data['Volets'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$ValStrat=$data['ValeurStrat'];
				$DefenseAA=$data['DefenseAA_temp'];
				$Recce_Base=$data['Recce'];
				$Zone=$data['Zone'];
				$Cible_map=$data['Map'];
				$Mines_m=$data['Mines_m'];
				$BaseAerienne=$data['BaseAerienne'];
				//if(!$Nuit)$meteo=$data['Meteo'];
			}
			mysqli_free_result($result2);
			unset($data);
		}		
		if($Equipage and $Equipage_Nbr >1)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Endurance,Vue,Trait FROM Equipage_PVP WHERE ID='$Equipage'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Endu_Eq=$data['Endurance'];
					if($Endu_Eq >0)
					{
						$Vue_Eq=$data['Vue'];
						$Trait_e=$data['Trait'];
					}
				}
				mysqli_free_result($result);
				unset($result);
			}		
		}
		$Pays_eni=GetFlagPVP($Battle,$Faction);
		$avion_img=GetAvionImg($Avion_db,$avion);
		$Conso=$Puissance_nominale/500;
		if($c_gaz ==130)
			UpdateData("Pilote_PVP","Stress_Moteur",10,"ID",$Pilote_pvp);		
		$moda=$HPmax/$HP;
		if($Avion_Bombe_nbr and $Avion_Bombe !=30)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_nbr));
			$moda*=(1+$charge_sup);
		}
		$Plafond=round($Plafond/$moda);
		if($alt >$Plafond)$alt=$Plafond;
		if($alt >6000 and $c_gaz <60)
		{
			$alt=5000+mt_rand(-500,500);
			$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
		}
		//Veste
		$haute_alt=true;
		if($alt >5000 and $Slot4 !=27 and $Slot4 !=30 and $Slot4 !=31 and $Slot4 !=32 and $Slot4 !=36 and $Slot4 !=37 and $Slot4 !=38 and $Slot4 !=39 and $Slot4 !=50 and $Slot4 !=51 and $Slot4 !=52)
		{
			$alt=mt_rand(4000,5000);
			$intro.="<p>Vous n'avez pas la tenue adéquate pour voler à haute altitude.</p>";
			$haute_alt=false;
		}				
		$alt_min=$alt-3000-$meteo;
		$alt_max=$alt+2000+$meteo;
		//Zone Objectif
		switch($Zone)
		{
			case 0:
				$zone_txt="champs et prairies";
				$QualitePiste=mt_rand(20,80);
				$Malus_Reperer=0;
			break;
			case 1:
				$zone_txt="collines";
				$QualitePiste=mt_rand(10,70);
				$Malus_Reperer=10;
			break;
			case 2:
				$zone_txt="forêts";
				$QualitePiste=mt_rand(10,80);
				$Malus_Reperer=20;
			break;
			case 3:
				$zone_txt="collines boisées";
				$QualitePiste=mt_rand(0,60);
				$Malus_Reperer=50;
			break;
			case 4:
				$zone_txt="montagnes";
				$QualitePiste=mt_rand(0,30);
				$Malus_Reperer=50;
			break;
			case 5:
				$zone_txt="montagnes boisées";
				$QualitePiste=mt_rand(0,10);
				$Malus_Reperer=100;
			break;
			case 6:
				$zone_txt="cette eau qui porte les bateaux, à perte de vue!";
				$QualitePiste=mt_rand(90,100)-$meteo;
				$Malus_Reperer=0;
			break;
			case 7:
				$zone_txt="zones urbaines";
				$QualitePiste=mt_rand(10,90);
				$Malus_Reperer=50;
			break;
			case 8:
				$zone_txt="désert";
				$QualitePiste=mt_rand(20,80);
				$Malus_Reperer=0;
			break;
			case 9:
				$zone_txt="jungle";
				$QualitePiste=mt_rand(0,10);
				$Malus_Reperer=30;
			break;
			case 11:
				$zone_txt="marécage";
				$QualitePiste=mt_rand(0,10);
				$Malus_Reperer=10;
			break;
		}
		$intro.='<p>La région est composée de '.$zone_txt.'</p>';		
		$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps,true);
		if($Nuit)$Malus_Reperer*=2;	
		$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		if($VitAvion <50)
		{
			$intro.="<p>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !</p>";
			$Action=90;
		}		
		if(!$Sandbox)
		{
			$pvp_eni=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"ID");
			if($pvp_eni)
			{
				$HP_Ori=$HP;
				$intro.="<p><b>Vous êtes pris en chasse ! <br><i>(Un autre joueur vous a engagé)</i></b></p>";
				$Pilote_eni=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"PlayerID");
				$avion_eni=GetData("Duels_Candidats_PVP","PlayerID",$Pilote_eni,"Avion");
				$HP=GetData("Duels_Candidats_PVP","PlayerID",$Pilote_pvp,"HP");
				if($HP <$HP_Ori)
				{
					$Deg=$HP_Ori-$HP;
					$avion_eni_nom=GetData("Avion","ID",$avion_eni,"Nom");
					$intro.='<p>[PVP] Vous encaissez une rafale tirée par un <b>'.$avion_eni_nom.'</b>! (<b>'.$Deg.'</b> dégâts)</p>';
				}
				$_SESSION['PVP']=true;
				$PVP=true;
			}
			if($PVP)
			{
				if($HP <1)
				{
					/*Tableau de chasse
					AddVictoire($Avion_db,$avion,$avion_eni,$Pilote_eni,$Unit_eni,$Unite,$Cible,1,$PlayerID,3,$Nuit,$alt);*/
					$end_mission=true;
					$_SESSION['PVP']=false;
					$PVP=false;
					SetData("Duels_Candidats_PVP","Target",0,"PlayerID",$Pilote_eni);
					RetireCandidatPVP($Pilote_pvp,"objectif_pvp");
					$Enis=0;
				}
			}
		}		
		$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt);
		/*DCA
		if($Zone ==6)
		{
			//ToDo
		}
		elseif($Zone !=6 and $Mission_Type <90 and $Mission_Type !=9 and $Mission_Type !=7 and $Mission_Type !=14 and $Mission_Type !=23)
		{
			if($Pays_eni ==$country and $Radio_avion)
				$intro.="<br>La défense anti-aérienne cesse de tirer à votre approche.";
			else
			{
				if($DefenseAA >($ValStrat*2)+2)$DefenseAA=($ValStrat*2)+2;
				if($DefenseAA and $alt <10000)
				{
					if($Mission_Type ==18 or $Mission_Type ==22)$DefenseAA-=1;
					if($Nuit and $DefenseAA >4)
					{
						$Projo=($DefenseAA/5);
						$intro.="<br>De puissants projecteurs illuminent le ciel!";
					}
					else
						$Projo=0;
					//Detection
					$Detect=mt_rand(0,$DefenseAA*10) + $VisAvion + $meteo - ($alt/100) - ($Nuit*100) + ($Projo*50) + ($Formation*$VisAvion);
					if($Detect >0)
					{			
						$VisAvion_dca=$VisAvion;
						$Tactique_dca=$Tactique;
						$Pilotage_dca=$Pilotage;
						$VitAvion_dca=$VitAvion;
						$Blindage_dca=$Blindage;
						$intro.='<br>Les explosions de DCA encadrent votre appareil!<br>Vous vous trouvez à '.$alt.'m d\'altitude. <b>La défense anti-aérienne ouvre le feu sur vous!</b>';
						$DCA_guns=GetDCA($Pays_eni,$DefenseAA);
						$hgun=$DCA_guns[0];
						$gun=$DCA_guns[1];
						$mg=$DCA_guns[2];
						switch($alt)
						{
							case ($alt <2000):
								//88mm Flak / QF 3.7inch AA
								//37mm Flak / Bofors L60
								//20mm Flak / 20mm
								$Arme1=$gun;
								$Arme2=$gun;
								$Arme3=$mg;
								$Flak=3;
							break;
							case ($alt <7000):
								//88mm Flak / QF 3.7inch AA
								//37mm Flak / Bofors L60
								$Arme1=$hgun;
								$Arme2=$gun;
								$Arme3=5;
								$Flak=14;
							break;
							case ($alt >=7000):
								//88mm Flak / QF 3.7inch AA
								$Arme1=$hgun;
								$Arme2=5;
								$Arme3=5;
								$Flak=15;
							break;
						}
						if($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13 or $Mission_Type ==14)
							$img=Afficher_Image("images/flak_nav.jpg","images/image.png","D.C.A Navale");
						else
						{
							if($Nuit)
								$img="<img src='images/flak_nuit.jpg' style='width:50%;'>";
							else
								$img=Afficher_Image('images/flak'.$Flak.$Pays_eni.'.jpg','images/flak156.jpg','D.C.A');
						}
						$dca_site_hit=false;
						$Malus_Range=$alt/100;
						$Dca_max=10+($DefenseAA*10);	
						for($dca_shoot=1;$dca_shoot<4;$dca_shoot++)
						{
							$Shoot_Dca=mt_rand(0,$Dca_max);
							$Shoot=$Shoot_Dca + $meteo + ($VisAvion_dca/($Malus_Range/10)) - $Malus_Range - ($Tactique_dca/10) - ($Pilotage_dca/10) - ($VitAvion_dca/($Malus_Range/5));
							if($Shoot >10 or $Shoot_Dca ==$Dca_max)
							{							
								if($dca_shoot ==1 and $Arme1 !=5)
									$Degats=round((mt_rand(0,GetData("Armes","ID",$Arme1,"Degats")) - pow($Blindage_dca,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme1,"Multi")));
								elseif($dca_shoot ==2 and $Arme2 !=5)
									$Degats=round((mt_rand(0,GetData("Armes","ID",$Arme2,"Degats")) - pow($Blindage_dca,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme2,"Multi")));
								elseif($dca_shoot ==3 and $Arme3 !=5)
									$Degats=round((mt_rand(0,GetData("Armes","ID",$Arme3,"Degats")) - pow($Blindage_dca,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme3,"Multi")));
								if($Degats <1)$Degats=mt_rand(1,10);
								if(!$cible_dca)
								{
									$dca_site_hit=true;
									$HP-=$Degats;
									if($HP <1)
									{
										$intro.='<br>L\'explosion met le feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
										$Action=90;
										break;
									}
									else
									{
										$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
										if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1 and !$Chk_Objectif)
										{
											if(GetData("Equipage_PVP","ID",$Equipage,"Moral") >0 and GetData("Equipage_PVP","ID",$Equipage,"Courage") >0)
											{
												$Equipage_Nom=GetData("Equipage_PVP","ID",$Equipage,"Nom");
												$Meca=floor(GetData("Equipage_PVP","ID",$Equipage,"Mecanique")/2);
												if($Meca >$Degats)$Meca=$Degats;
												$intro.='<p>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)</p>';
												$HP+=$Meca;
											}
										}
									}
									SetData("Pilote_PVP","S_HP",$HP,"ID",$Pilote_pvp);
								}
								elseif($Degats >$HP_dca and $Pilote_ia_dca and $Avion_dca)
								{
									UpdateData("Pilote_PVP","S_Formation",-1,"ID",$Pilote_pvp);
									$Formation-=1;
									$intro.="<br>L'explosion met le feu à l'avion de ".$Nom_pilote_ia.", ne lui laissant pas d'autre choix que de sauter en parachute!";
								}
								else
									$intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", mais il peut heureusement continuer sa mission!";
							}
							else
								$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
						}
					}
					else
						$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
				}	
			}
		}*/
		switch($Action)
		{
			case 1:
				$HP_eni=0; //Pas encore défini, vu que pas encore d'ennemi, mais nécessaire pour bomb.php
				//Appui
				if($Mission_Type ==1 or $Mission_Type ==6 or $Mission_Type ==31)
				{
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
					elseif($meteo >-6)
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					if($Recce_Base ==2)
						$Nuit=0;
					$reperer=$Vue+$Vue_avion+($meteo*2)+($Moral/10)+$Vue_Eq-$Malus_Reperer-($Nuit*100)-($alt/100);
					if($reperer >0)
					{
						if($Arme1Avion !=5 and $Mun1 >0)
							$Arme1_txt="<Input type='Radio' name='Action' value='3' checked>- Attaquer votre cible à basse altitude à l'aide de vos ".GetData("Armes","ID",$Arme1Avion,"Nom")." (reste ".$Mun1." coups).<br>";
						if($Mun2 >0 and $Arme2Avion !=5 and $Arme2Avion !=25 and $Arme2Avion !=26 and $Arme2Avion !=27)
							$Arme2_txt="<Input type='Radio' name='Action' value='13' checked>- Attaquer votre cible à basse altitude à l'aide de vos ".GetData("Armes","ID",$Arme2Avion,"Nom")." (reste ".$Mun2." coups).<br>";
						if($Avion_Bombe_nbr >0 and $Avion_Bombe ==80)
							$Rockets_txt="<Input type='Radio' name='Action' value='80' checked>- Attaquer votre cible à basse altitude à l'aide de vos roquettes (reste ".$Avion_Bombe_nbr." coups).<br>";
						if($Mission_Type ==1)
							$atk_img_txt="lieu/objectif_atk".$Pays_eni.$Cible_Atk;
						else
							$atk_img_txt="lieu/objectif_bomb".$Pays_eni.$Cible_Atk;
						if(!$img)$img=Afficher_Image('images/'.$atk_img_txt.'.jpg','images/image.png','attaque');
						SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
						$titre="Attaque au sol";
						$intro.="<p>Vous avez repéré votre cible!</p>";
						$mes.='<form action=\'index.php?view=bomb_pvp\' method=\'post\'>
						<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
						<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
						<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
						<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
						<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
						<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
						<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
						<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
						'.ShowGaz($avion,$c_gaz,$flaps,$alt,2,true).'
						<table class=\'table\'><tr><td>'.$Arme1_txt.$Arme2_txt.$Rockets_txt.'
							<Input type=\'Radio\' name=\'Action\' value=\'99\'>- Annuler la mission.<br>
						</td></tr></table>
						<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';		
					}
					else
					{
						$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
						$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
						$retour=true;
					}
				}
				//Ravito
				elseif($Mission_Type ==23)
				{				
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer le pilote ne sera pas chose facile!";
					else
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					$reperer=$Vue+$Vue_avion+$meteo+($Moral/10)+$Vue_Eq-$Malus_Reperer;
					if($reperer >0)
					{
						/*$con=dbconnecti();
						$result=mysqli_query($con,"SELECT DISTINCT Unit.ID,Unit.Nom FROM Unit,Pays WHERE Unit.Base='$Cible' AND Unit.Pays=Pays.Pays_ID AND Unit.ID<>'$Unite' AND Pays.Faction='$Faction'");
						$result2=mysqli_query($con,"SELECT DISTINCT r.ID,r.Vehicule_ID FROM Regiment as r,Pays as p WHERE r.Lieu_ID='$Cible' AND r.Pays=p.Pays_ID AND p.Faction='$Faction'");
						mysqli_close($con);
						if($result)
						{
							while ($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Sauve=$data['ID'];
								$Sauve_Nom=$data['Nom'];
								$sauve_input.="<option value='".$Sauve."'>".$Sauve_Nom."</option>"; 
							}
							mysqli_free_result($result);
						}
						if($result2)
						{
							while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								$sauve_input.="<option value='".$data['ID']."_'>".$data['ID']."e Compagnie</option>"; 
							}
							mysqli_free_result($result2);
						}
						$Train=GetData($Avion_db,"ID",$avion,"Train");*/
						$Stress_Train=GetData("Pilote","ID",$Pilote_pvp,"Stress_Train");
						if($Train ==0)
						{
							if($Stress_Train <10)
							{
								$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>
									<Input type='Radio' name='roues' value='0' checked>- Train rentré.<br>
									<Input type='Radio' name='roues' value='1'>- Train sorti.<br></td>";
							}
							else
							{
								$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>
									<Input type='Radio' name='roues' value='0' checked>- Train rentré.<br>
									<Input type='Radio' name='roues' value='1' disabled>- Train endommagé !<br></td>";
							}
						}
						elseif($Train <6)
						{
							if($Stress_Train <20)
							{
								$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>
									<Input type='Radio' name='roues' value='0' checked>- Train rentré.<br>
									<Input type='Radio' name='roues' value='1'>- Train sorti.<br></td>";
							}
							else
							{
								$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>
									<Input type='Radio' name='roues' value='0' checked>- Train rentré.<br>
									<Input type='Radio' name='roues' value='1' disabled>- Train endommagé !<br></td>";
							}
						}
						else
						{
							if($Stress_Train <10)
								$roues_txt="<input type='hidden' name='roues' value='1'>";
							else
								$roues_txt="<input type='hidden' name='roues' value='0'>";
						}
						SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
						$titre="Ravitaillement";
						$intro.="<p>Vous avez repéré le terrain!</p>";
						$img=Afficher_Image('images/avions/landing'.$avion_img.'.jpg','images/avions/decollage'.$avion_img.'.jpg','Atterrissage');
						$mes.='<form action=\'index.php?view=mia_pvp\' method=\'post\'>
						<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
						<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
						<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
						<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
						<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
						'.ShowGaz($avion,$c_gaz,$flaps,$alt,7,true).$roues_txt.'
						<table class=\'table\'><tr><td><select name=\'Action\' class=\'form-control\' style=\'width: 200px\'>'.$sauve_input.'<option value=\'0\'>Annuler la mission</option></select></td></tr></table>
						<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';		
					}
					else
					{
						$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
						$retour=true;
					}
				}
				//Chasse
				elseif($Mission_Type ==3 or $Mission_Type ==7 or $Mission_Type ==17 or $Mission_Type ==26)
				{
					$essence-=(5+$Conso);
					if($Sandbox)
					{
						$intro.="<p>Il est temps de faire demi-tour</p>";
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						$retour=true;
					}
					else
					{
						/*if($Cible_Atk ==99)//Echec mission chasse
						{
							$intro.="<p><b>Vous avez échoué dans la mission qui vous a été assignée!</b></p>";
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							$retour=true;
						}
						else
						{*/
							if(!$pvp_eni)
							{
								$intro.="<p><b>Vous avez accompli la mission qui vous a été assignée.</b></p>";
								$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
								if($Mission_Type ==7)
								{
									//SetData("Pilote_PVP","Couverture",$Cible,"ID",$Pilote_pvp);
									SetData("Pilote_PVP","S_alt",$alt,"ID",$Pilote_pvp);
									$intro.="<br>Vous établissez un périmètre de patrouille au-dessus de l'objectif.";
								}
								elseif($Mission_Type ==17)
								{
									//SetData("Pilote_PVP","Couverture_Nuit",$Cible,"ID",$Pilote_pvp);
									$intro.="<br>Vous établissez un périmètre de patrouille de nuit dans la zone.";
								}
								$retour=true;
							}
							else
							{
								$intro.="<p><b>[PVP] La présence de l'ennemi vous empêche d'effectuer votre mission!</b></p>";
								$img=Afficher_Image('images/facetoface.jpg','images/avions/vol'.$avion_img.'.jpg','PvP');
								$patrol=true;
							}
						//}
					}
				}
				//Escorte
				elseif($Mission_Type ==4)
				{
					$essence-=(10+$Conso);
					if(!$pvp_eni and $Simu)
					{
						$intro.="<p>La formation que vous escortiez vous confirme que la mission a été accomplie.</p>";								
						$img=Afficher_Image('images/avions/formation'.$Escorteb.'.jpg','images/avions/vol'.$Escorteb.'.jpg','');
						$Plafond_escorte=GetData("Avion","ID",$Escorteb,"Plafond");
						if($alt >$Plafond_escorte)$alt=$Plafond_escorte;
						/*$con=dbconnecti();
						$update=mysqli_query($con,"UPDATE Pilote_PVP SET S_alt='$alt',Escorte='$Cible' WHERE ID='$Pilote_pvp'");
						mysqli_close($con);*/
						$intro.="<p><b>Vous avez accompli avec succès la mission qui vous a été assignée.</b></p>";					
						$retour=true;
					}
					else
					{
						$intro.="<p><b>[PVP] La présence de l'ennemi vous empêche d'effectuer votre mission!</b></p>";
						$img=Afficher_Image('images/facetoface.jpg','images/avions/vol'.$avion_img.'.jpg','PvP');
						$patrol=true;
					}
				}
				//Recce
				elseif($Mission_Type ==5 or $Mission_Type ==15)
				{
					//Camera
					if($Avion_Bombe !=25 and $Avion_Bombe !=26 and $Avion_Bombe !=27)
						$Camera=$Arme2Avion;
					else
						$Camera=$Avion_Bombe;
					if($Camera ==25 or $Camera ==26 or $Camera ==27)
					{
						if($Mun2 >0)
						{
							$Mun2-=1;
							if($alt <=GetData("Armes","ID",$Camera,"Portee"))
								$Bonus_Camera=GetData("Armes","ID",$Camera,"Enrayage");
							else
								$Bonus_Camera=0;
						}
					}
					if($meteo <-9)
						$intro.="<br>le temps est couvert, prendre des photos ne sera pas chose facile!";
					if($Mission_Type ==15)
						$reperer=$Vue + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq - $Malus_Reperer + $Bonus_Camera;
					else
						$reperer=$Vue + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq - $Malus_Reperer;
					if($Cible_Atk ==99)//Echec mission  refresh nav.php
						$reperer=0;
					if($reperer >0)
					{
						//Détection Sol
						$choix_bomb="Prendre des photos";
						if($Mission_Type ==5)
						{
							if($Zone ==6)
								$atk_img_txt='ocean.jpg';
							else
								$atk_img_txt='recce';
							$haute_alt=false;
							$choix_bomb1="<Input type='Radio' name='Action' value='1'>- Photographier votre cible.<br>";
							$choix_bomb2="";		
							$choix_bomb4="";		
							$choix_bomb5="";		
							$choix_bomb6="";		
						}
						else
						{
							$choix_bomb1="<Input type='Radio' name='Action' value='1'>- Photographier votre cible.<br>";
							$choix_bomb2="";		
							$choix_bomb4="";		
							$choix_bomb5="";		
							$choix_bomb6="";		
							if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
								$atk_img_txt='lieu/lieu'.$Cible;
							else
								$atk_img_txt='lieu/objectif'.$Cible_map;
						}
						$bomb=true;
					}
					else
					{
						$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée! <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
						if(!$img)$img='<img src=\'images/avions/vol'.$avion_img.'.jpg\' style=\'width:100%;\'>';
						$retour=true;
					}	
				}
				//Pathfinder
				elseif($Mission_Type ==21)
				{
					if($Nuit and $Recce_Base ==2)
					{
						$intro.="<p>La cible est déjà marquée, la mission est annulée!</p>";
						$img="<img src='images/pathfinder.jpg' style='width:100%;'>";
						$retour=true;
					}
					else
					{
						if($meteo <-9)
							$intro="<br>le temps est couvert, repérer la cible ne sera pas chose facile!";
						$reperer=$Vue + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq - $Malus_Reperer;
						if($reperer >0)
						{
							//Détection Sol
							$choix_bomb="Marquer la cible";
							$choix_bomb1="<Input type='Radio' name='Action' value='1'>- Marquer la zone.<br>";
							$choix_bomb2="<Input type='Radio' name='Action' value='4'>- Ordonner à votre équipage de marquer la zone.<br>";		
							$choix_bomb4="";		
							$choix_bomb5="";		
							$choix_bomb6="";		
							$bomb=true;
							if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
								$atk_img_txt='lieu/lieu'.$Cible;
							else
								$atk_img_txt='lieu/objectif'.$Cible_map;
						}
						elseif($Camouflage_cible >50)
						{
							$intro.="<p>Vous repérez une cible, mais il semblerait que ce soit un leurre. Le camouflage sophistiqué de l'ennemi vous empêche d'accomplir votre mission! <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l\'altitude sont des facteurs importants de la réussite d\'une détection</span></a></p>";
							$img='<img src=\'images/avions/vol'.$avion_img.'.jpg\' style=\'width:100%;\'>';
							$retour=true;
						}
						else
						{
							$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée! <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l\'altitude sont des facteurs importants de la réussite d\'une détection</span></a></p>";
							$img='<img src=\'images/avions/vol'.$avion_img.'.jpg\' style=\'width:100%;\'>';
							$retour=true;
						}	
					}
				}
				// Bomb
				elseif($Mission_Type ==2 or $Mission_Type ==8 or $Mission_Type ==16 or $Mission_Type ==101)
				{
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
					else
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					if($Mission_Type ==2)
					{
						if($Type_avion ==7 or $Type_avion ==10)
						{
							$Vue_avion*=2;
							$Nuit=0;
						}
					}
					else
					{
						if($Recce_Base ==2)
							$Nuit=0;
					}
					$reperer=$Vue+$Vue_avion+($meteo*2)+($Moral/10)+$Vue_Eq-$Malus_Reperer-($Nuit*100)-($alt/100);
					if($reperer >0)
					{
						if($Mission_Type ==2)
						{
							$haute_alt=false;
							$choix_bomb1="";
							if($Type_avion ==7)
								$choix_bomb1="<Input type='Radio' name='Action' value='7' title='Bombardement de précision. Utilisera une bombe'>- Bombarder votre cible en piqué.<br>";
							$choix_bomb2="";
							$choix_bomb3="<Input type='Radio' name='Action' value='3' title='Bombardement de précision. Utilisera une bombe'>- Bombarder votre cible à basse altitude.<br>";
							if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
							{
								$choix_bomb4="";
								$choix_bomb5="";
								$choix_bomb6="<Input type='Radio' name='Action' value='6' title='Bombardement de précision. Utilisera une bombe'>- Ordonner à votre équipage de bombarder la cible à basse altitude.<br>";
							}
							$atk_img_txt='lieu/objectif_atk'.$Pays_eni.'6';
						}
						else
						{
							$choix_bomb1="";	
							$choix_bomb2="<Input type='Radio' name='Action' value='1' title='Bombardement en tapis. Utilisera toutes vos bombes'>- Bombarder en tapis.<br>";	
							$choix_bomb3="<Input type='Radio' name='Action' value='3' title='Bombardement de précision. Utilisera une bombe'>- Bombarder avec précision.<br>";	
							if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
							{
								$choix_bomb4="";	
								$choix_bomb5="<Input type='Radio' name='Action' value='4' title='Bombardement en tapis. Utilisera toutes vos bombes'>- Ordonner à votre équipage de bombarder en tapis.<br>";		
								$choix_bomb6="<Input type='Radio' name='Action' value='6' title='Bombardement de précision. Utilisera une bombe'>- Ordonner à votre équipage de bombarder avec précision.<br>";
							}
							if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
								$atk_img_txt='lieu/lieu'.$Cible;
							else
								$atk_img_txt='lieu/objectif'.$Cible_map;
						}
						$choix_bomb="Bombarder votre cible";
						$bomb=true;
					}
					else
					{
						if($Mission_Type !=2)
							$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La détection est obsolète ou le lieu a été recamouflé par votre adversaire</span></a></p>";
						else
							$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
						if(!$img)$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
						$retour=true;
					}
				}
				// Attaque navire
				elseif($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13)
				{
					$atk_img_txt='lieu/objectif_torpille';
					//$atk_img_txt='lieu/objectif_torpille'.$Pays_eni.$Cible_Atk;
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
					else
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					$reperer=mt_rand(10,$Vue) + $Vue_avion + ($meteo*3) + ($Moral/10) + $Vue_Eq + ($Radar_avion*50) - ($alt/100);
					if($reperer >0)
					{
						$haute_alt=false;
						if($Mission_Type ==11)
						{
							$choix_bomb="Mitrailler votre cible";
							$choix_bomb1="<Input type='Radio' name='Action' value='3'>- Mitrailler un navire à basse altitude.<br>";		
							$choix_bomb2="";		
							$choix_bomb3="";		
							$choix_bomb4="";		
							$choix_bomb5="";		
							$choix_bomb6="";		
						}
						elseif($Mission_Type ==12)
						{
							$choix_bomb="Bombarder votre cible";
							$choix_bomb2="";
							if($Type_avion ==7)
								$choix_bomb2="<Input type='Radio' name='Action' value='7' title='Bombardement de précision. Utilisera une bombe'>- Bombarder un navire en piqué.<br>";
							$choix_bomb1="<Input type='Radio' name='Action' value='3' title='Bombardement de précision. Utilisera une bombe'>- Bombarder un navire à basse altitude.<br>";			
							$choix_bomb3="";
							$choix_bomb4="";		
							$choix_bomb5="";		
							$choix_bomb6="";		
							if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
							{
								$choix_bomb5="<Input type='Radio' name='Action' value='6' title='Bombardement de précision. Utilisera une bombe'>- Ordonner à votre équipage de bombarder un navire à basse altitude.<br>";
								if($Type_avion ==7)
									$choix_bomb6="<Input type='Radio' name='Action' value='7' title='Bombardement de précision. Utilisera une bombe'>- Ordonner à votre équipage de bombarder un navire en piqué.<br>";		
							}
						}					
						else
						{
							$choix_bomb="Torpiller votre cible";
							$choix_bomb1="<Input type='Radio' name='Action' value='3' title='Moins de chances de toucher la cible'>- Torpiller un navire à basse altitude à distance de sécurité.<br>";		
							$choix_bomb2="<Input type='Radio' name='Action' value='8' title='Plus de chances de toucher la cible en prenant des risques'>- Torpiller un navire en vous approchant au ras des flots.<br>";		
							$choix_bomb3="";
							$choix_bomb4="";		
							$choix_bomb5="";		
							$choix_bomb6="";		
							if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
							{
								$choix_bomb5="<Input type='Radio' name='Action' value='6' title='Bombardement de précision. Utilisera une torpille'>- Ordonner à votre équipage de Torpiller un navire à basse altitude à distance de sécurité.<br>";
								$choix_bomb6="<Input type='Radio' name='Action' value='9' title='Bombardement de précision avec prise de risques. Utilisera une torpille'>- Ordonner à votre équipage de Torpiller un navire tandis que vous vous approcherez au ras des flots.<br>";
							}
						}
						$bomb=true;
					}
					else
					{
						$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l altitude sont des facteurs importants de la réussite d une détection</span></a></p>";
						$img ='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
						$retour=true;
					}
				}
				//ASM
				elseif($Mission_Type ==29)
				{
					$essence-=(10+$Conso);					
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
					else
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					$reperer=mt_rand(10,$Vue) + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq + ($Radar_avion*50) - ($alt/100);
					if($reperer >0)
					{
						if($Avion_Bombe_nbr >0 and $Avion_Bombe ==300)
						{
							$choix_bomb1="<Input type='Radio' name='Action' value='8' title='Bombardement de précision. Utilisera une charge'>- Larguer une charge de profondeur sur votre cible à basse altitude.<br>";			
							if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
								$choix_bomb4="<Input type='Radio' name='Action' value='9' title='Bombardement de précision. Utilisera une charge'>- Ordonner à votre équipage de larguer une charge de profondeur sur la cible à basse altitude.<br>";
						}
						else
						{
							$intro.="<p>Ne disposant pas de l'armement adéquat, vous devez abandonner votre mission!</p>";
							$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
							$retour=true;
						}
						$haute_alt=false;
						SetData("Pilote_PVP","S_Cible_Atk",23,"ID",$Pilote_pvp);
						$atk_img_txt='lieu/objectif_torpille'.$Pays_eni.'22';
						$bomb=true;
					}
					else
					{
						$intro.="<p><b>Vous ne détectez aucun sous-marin dans les environs</b>, la mission est annulée. <span style='color:red;' title='La météo, le camouflage ennemi, l utilisation du radar et l altitude sont des facteurs importants de la réussite de la détection'>?</span></p>";
						$img ='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
						$retour=true;
					}
				}
			break;
			case 2: case 3:
				$img="<img src='images/avions/vol".$avion_img.".jpg' style='width:100%;'>";
				$retour=true;
			break;
			case 90:
				$end_mission=true;
			break;
			//PvP
			case 96: case 97: case 98:
				$essence-=(1+$Conso);
				if($c_gaz <60 and $alt >6000)
				{
					$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
					$alt=5000+mt_rand(-1000,500);
				}
				$PvP_Avion=GetData("Duels_Candidats_PVP","PlayerID",$Pilote_pvp,"Avion");
				$HP_PvP=GetData("Duels_Candidats_PVP","PlayerID",$Pilote_pvp,"HP");
				if($PvP_Avion and $HP_PvP <1)
				{
					$intro.="<p>Vous avez été abattu par l'ennemi !</p>";
					$img="<img src='images/kill".$country.".jpg' style='width:100%;'>";
					RetireCandidatPVP($Pilote_pvp,"objectif");
					$_SESSION['PVP']=false;
					$PVP=false;
					$end_mission=true;
				}
				else
				{
					if(!$img)$img="<img src='images/avions/vol".$avion_img.".jpg' style='width:100%;'>";
					$patrol=true;
				}
			break;
			case $Action >99:
				$essence-=(5+$Conso);
				$PvP_ID=substr($Action,2);
				$PvP_Cible=GetData("Duels_Candidats_PVP","PlayerID",$PvP_ID,"Lieu");
				if($Cible ==$PvP_Cible)
				{
					$mission3=true;
					$PVP=true;
					$_SESSION['PVP']=true;
					$_SESSION['done']=false;
					$chemin=0;
					SetData("Duels_Candidats_PVP","Target",$PvP_ID,"PlayerID",$Pilote_pvp);
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT HP,Avion,Altitude FROM Duels_Candidats_PVP WHERE PlayerID='$PvP_ID'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$HP_eni=$data['HP'];
							$avion_eni=$data['Avion'];
							$alt_avioneni=$data['Altitude'];
						}
						mysqli_free_result($result);
					}
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Type,Engine_Nbr FROM Avion WHERE ID='$avion_eni'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$PvP_Avion_Nom=$data['Nom'];
							$Type_avioneni=$data['Type'];
							$Engine_Nbr_eni=$data['Engine_Nbr'];
						}
						mysqli_free_result($result);
					}						
					SetData("Pilote_PVP","S_Engine_Nbr_Eni",$Engine_Nbr_eni,"ID",$Pilote_pvp);
					$Pilote_eni=$PvP_ID;
					$Avion_db_eni="Avion";
					$Enis=1;
					$intro='Vous engagez le combat contre un <b>'.$PvP_Avion_Nom.'</b>';
				}
				else
				{
					$intro="L'ennemi a pris la fuite!";
					$img="<img src='images/avions/vol".$avion.".jpg' style='width:100%;'>";
					$patrol=true;
				}				
				if($mission3)
				{
					if($meteo <-9)
						$choix5="Tenter de vous échapper en vous cachant dans les nuages";
					else
					{
						if($Nuit)
							$choix5="Tenter de vous échapper en profitant de la nuit.";
						else
							$choix5="Tenter de vous échapper face au soleil.";
					}
					//Attaque par le ventre
					if($alt_avioneni >1000 and $Tactique >50 and ($Type_avioneni ==2 or $Type_avioneni ==11))
						$Ventre ="<Input type='Radio' name='Action' value='11'>- Tenter d'attaquer par le ventre en évitant le mitrailleur arrière.<br>";
					//Seuls les chasseurs et chasseurs lourds attaquent
					if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
					{
						$choix1="<Input type='Radio' name='Action' value='1'>- Chercher à vous placer dans ses 6 heures pour l'abattre.<br>";
						if($alt_avioneni >1000)
							$choix7="<Input type='Radio' name='Action' title='".GetMes('Aide_Coiffer')."' value='7'>- Chercher à prendre de l'altitude pour ensuite piquer sur l'ennemi.<br>";
						else
							$choix7="";
						$choix8="<Input type='Radio' name='Action' title='".GetMes('Aide_Frontale')."' value='8'>- Tenter une attaque frontale.<br>";
					}
					elseif($Equipage_Nbr >1 and $avion !=43 and $avion !=109)
					{
						$choix1="<Input type='Radio' name='Action' value='9'>- Maintenir votre cap, votre mitrailleur arrière se chargeant de l'adversaire.<br>";
						$choix2="";
						$choix7="";
						$choix8="";
					}
					else
					{
						$choix1="";
						$choix2="";
						$choix7="";
						$choix8="";
					}
					SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
					$img="<img src='images/avions/vol".$avion_eni.".jpg' style='width:100%;'>";
					$titre="Combat";
					$menu.='<form action=\'index.php?view=mission3_pvp\' method=\'post\'>
						<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>	
						<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
						<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
						<input type=\'hidden\' name=\'Avioneni\' value='.$avion_eni.'>
						<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
						<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
						<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
						<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
						<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
						<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
						<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
						<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
						<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
						<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
						'.ShowGaz($avion,$c_gaz,$flaps,$alt_avioneni,1,true).'
						<table class=\'table\'><tr><td>'.$choix1.$choix7.$choix8.$choix2.$choix3.'
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'4\' checked>- Tenter de fuir le combat en vous lançant dans un piqué.<br>
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Nuages').'\' value=\'5\'>- '.$choix5.'<br>'.$Ventre.'
						</td></tr></table>
						<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
				}
			break;
		}		
		SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
		if($bomb)
		{
			if($haute_alt)
				$com_int=ShowGaz($avion,$c_gaz,$flaps,$alt,false,true);
			else
				$com_int=ShowGaz($avion,$c_gaz,$flaps,$alt,4,true);
			$intro.="<p>Vous localisez votre cible!</p>";
			$img=Afficher_Image('images/'.$atk_img_txt.'.jpg',"images/image.png","Cible");
			$titre=$choix_bomb;
			$mes.='<form action=\'index.php?view=bomb_pvp\' method=\'post\'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
			<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
			<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
			'.$com_int.'
			<table class=\'table\'><tr><td>
				'.$choix_bomb1.$choix_bomb2.$choix_bomb3.$choix_bomb4.$choix_bomb5.$choix_bomb6.'
				<Input type=\'Radio\' name=\'Action\' value=\'99\' checked>- Annuler la mission.<br>
			</td></tr></table>
			<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}
		if($retour)
		{
			Chemin_Retour();
			$chemin=$Distance_totale;
			if(!$img)$img=Afficher_Image('images/avions/vol'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
			$intro.='<p>Vous prenez le chemin du retour en direction de votre base, située à '.$Distance_totale.'km</p>';
			$titre='Retour';
			$mes.='<form action=\'index.php?view=nav_pvp\' method=\'post\'>
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
			<input type=\'hidden\' name=\'Distance\' value='.$Distance_totale.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
			<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
			<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,false,true).'
			<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}		
		if($patrol)
		{
			$_SESSION['done'] =false;
			$chemin=0;
			$titre='Patrouille';
			$intro.="<p>Vous patrouillez dans la zone</p>";
			$mes.='<form action=\'index.php?view=nav_pvp\' method=\'post\'>
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
			<input type=\'hidden\' name=\'Distance\' value='.$Distance_totale.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
			<input type=\'hidden\' name=\'Patrol\' value=\'1\'>
			<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
			<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,false,true).'
			<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}
	}	
	if($end_mission)
		include_once('./end_mission_pvp.php');
	if($Pilote_pvp ==1)
	{
		$skills.='<br>'.memory_get_usage().'/'.memory_get_peak_usage().'<br>';
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT PlayerID,Lieu,Avion FROM Duels_Candidats_PVP WHERE PlayerID<>'$Pilote_pvp'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$En_PlayerID=$data['PlayerID'];
				$En_Lieu=$data['Lieu'];
				$En_Avion=$data['Avion'];
				$skills.="<p>".GetData("Pilote_PVP","ID",$En_PlayerID,"Nom")." ( ".GetData("Avion","ID",$En_Avion,"Nom")." ) : ".GetData("Lieu","ID",$En_Lieu,"Nom")."</p>";
			}
			mysqli_free_result($result);
			unset($data);
		}
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
unset($choix_bomb);
include_once('./default.php');