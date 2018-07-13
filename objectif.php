<?php
require_once('./jfv_inc_sessions.php');
/*$time=microtime();
$time=explode(' ',$time);
$time=$time[1] + $time[0];
$start=$time;*/
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
/*if($PlayerID ==1 or $PlayerID ==2)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
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
$PlayerID=$_SESSION['PlayerID'];
//Check Joueur Valide
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $_SESSION['objectif'] ==false AND $avion >0 AND !empty($_POST))
{
	$_SESSION['naviguer']=false;
	$_SESSION['objectif']=true;
	$_SESSION['done']=true;
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_combat.inc.php');
	$Distance_totale=$_SESSION['Distance'];
	$country=$_SESSION['country'];
	$BH_Lieu=$_SESSION['BH_Lieu'];
	$PVP=$_SESSION['PVP'];
	$retour=false;
	$bomb=false;
	$patrol=false;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Equipage,Tactique,Vue,S_Avion_db,S_Nuit,S_Cible,S_Mission,S_Longitude,S_Latitude,S_Cible_Atk,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Escorte_nbr,S_Equipage_Nbr,S_Escorteb_nbr,S_Engine_Nbr,S_Essence,S_Blindage,S_Formation,Slot4,Simu,Sandbox,Stress_Train
	FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : objectif-player');
	$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
	//mysqli_close($con);
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
			$HP=$data['S_HP'];
			$Unite=$data['Unit'];
			$Tactique=$data['Tactique'];
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
			$Escorteb_nbr=$data['S_Escorteb_nbr'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$S_Blindage=$data['S_Blindage'];
			$Formation=$data['S_Formation'];
			$Slot4=$data['Slot4'];
			$Simu=$data['Simu'];
			$Sandbox=$data['Sandbox'];
            $Stress_Train=$data['Stress_Train'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($Tactique >50)$Tactique=50;
	if($Vue >50)$Vue=50;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(33,$Skills_Pil))
			$Compas_oeil=true;
		if(in_array(38,$Skills_Pil))
			$PSV=true;
		if(in_array(78,$Skills_Pil))
			$Discipline_fer=true;
		if(in_array(94,$Skills_Pil))
			$ExpTac=50;
		elseif(in_array(94,$Skills_Pil))
			$ExpTac=25;
	}
	if($HP <1 or !$Cible or $alt <1)
		$end_mission=true;
	else
	{			
		//En cas de mission historique de front, tout lieu devient une cible valable
		if($_SESSION['BH_Mission'] and $BH_Lieu ==0 and $Simu and !$Sandbox)
		{
			$_SESSION['BH_Lieu']=$Cible;
			$BH_Lieu=$Cible;
		}		
		//$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Type,Puissance,Robustesse,Masse,Plafond,ArmePrincipale,ArmeSecondaire,Detection,Radio,Radar,Blindage,Volets,Train FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : objectif-avion');
		$result2=mysqli_query($con,"SELECT ValeurStrat,DefenseAA_temp,Recce,Camouflage,Zone,Map,Meteo,BaseAerienne,Flag,Mines_m FROM Lieu WHERE ID='$Cible'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : objectif-cible');
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Type_avion=$data['Type'];
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
                $Train=$data['Train'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		//GetData Objectif
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$ValStrat=$data['ValeurStrat'];
				$DefenseAA=$data['DefenseAA_temp'];
				$Pays_eni=$data['Flag'];
				$Recce_Base=$data['Recce'];
				$Camouflage_cible=$data['Camouflage'];
				$Zone=$data['Zone'];
				$Cible_map=$data['Map'];
				$Mines_m=$data['Mines_m'];
				$BaseAerienne=$data['BaseAerienne'];
				if(!$Nuit)$meteo=$data['Meteo'];
			}
			mysqli_free_result($result2);
			unset($data);
		}		
		if(!$Blindage)
		{
			$Blindage=$S_Blindage;
			if(!$Blindage)$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
		}
		if($Equipage and $Equipage_Nbr >1)
		{
			//$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Endurance,Vue,Trait FROM Equipage WHERE ID='$Equipage'");
			//mysqli_close($con);
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
		$avion_img=GetAvionImg($Avion_db,$avion);
		$Conso=$Puissance_nominale/500;
		if($c_gaz ==130)
			UpdateData("Pilote","Stress_Moteur",10,"ID",$PlayerID);		
		$moda=$HPmax/$HP;
		if($Avion_db =="Avion" and $Avion_Bombe_nbr and $Avion_Bombe !=30)
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
		$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
		if($Nuit and !$PSV)$Malus_Reperer*=2;	
		//XP Avion
		if(!$PVP and $Distance_totale >199 and $Simu and !$Sandbox)
		{
			$car_up=floor($Distance_totale/200);
			if($car_up >5)$car_up=5;
			AddPilotage($Avion_db,$avion,$PlayerID,$car_up);
		}		
		$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		if($VitAvion <50)
		{
			$intro.="<p>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !</p>";
			$Action=90;
		}		
		if(!$Sandbox)
		{
			$pvp_eni=GetData("Duels_Candidats","Target",$PlayerID,"ID");
			if($pvp_eni)
			{
				$HP_Ori=$HP;
				$intro.="<p><b>Vous êtes pris en chasse ! <br><i>(Un autre joueur vous a engagé)</i></b></p>";
				$Pilote_eni=GetData("Duels_Candidats","Target",$PlayerID,"PlayerID");
				$avion_eni=GetData("Duels_Candidats","PlayerID",$Pilote_eni,"Avion");
				$HP=GetData("Duels_Candidats","PlayerID",$PlayerID,"HP");
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
					//Tableau de chasse
					$Unit_eni=GetData("Pilote","ID",$Pilote_eni,"Unit");
					AddVictoire($Avion_db,$avion,$avion_eni,$Pilote_eni,$Unit_eni,$Unite,$Cible,1,$PlayerID,3,$Nuit,$alt);
					$end_mission=true;
					$_SESSION['PVP']=false;
					$PVP=false;
					SetData("Duels_Candidats","Target",0,"PlayerID",$Pilote_eni);
					RetireCandidat($PlayerID, "objectif_pvp");
					$Enis=0;
				}
			}
		}		
		//DCA
		$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,0,$PlayerID,$Unite);
		if($Zone ==6)
		{
			if($Pays_eni ==$country and $Radio_avion)
				$intro.="<br>La défense anti-aérienne cesse de tirer à votre approche.";
			else
			{
				//DCA Flotte
				/*$con=dbconnecti();
				$Flak_PJ_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND c.Flak >0 AND c.Portee >='$alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5)"),0);*/
				$Flak_IA_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND c.Flak >0 AND c.Portee >='$alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5)"),0);
				//mysqli_close($con);
				$Flak_PJ_Ground+=$Flak_IA_Ground;
				if(!$Flak_PJ_Ground)
				{			
					$intro.="<p>La DCA reste silencieuse!</p>";
					$DefenseAA=0;
				}			
				else
				{
					//Detection
					$Detect=mt_rand(0,$Flak_PJ_Ground*10) + $VisAvion + $meteo - ($alt/100) + ($Formation*$VisAvion);
					if($Detect >0)
					{
						/*if($Flak_PJ_Ground)
						{
							$query="SELECT r.ID,r.Vehicule_ID,r.Experience,r.Vehicule_Nbr,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment as r, Cible as c, Pays as p 
							WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
							AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee>='$alt' ORDER BY r.Experience DESC LIMIT 2";
							$Unit_table="Regiment";
						}
						else*/if($Flak_IA_Ground)
						{
							$query="SELECT r.ID,r.Vehicule_ID,r.Experience,r.Vehicule_Nbr,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment_IA as r, Cible as c, Pays as p 
							WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
							AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee>='$alt' ORDER BY r.Experience DESC LIMIT 2";
							$Unit_table="Regiment_IA";
						}
						//$con=dbconnecti();
						$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : obj-ok_dca');
						//mysqli_close($con);
						if($result)
						{
							$intro.="<br>Les explosions de DCA encadrent votre appareil!
							<br>Vous vous trouvez à ".$alt."m d'altitude. <b>La défense anti-aérienne ouvre le feu sur vous!</b>";
							if($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13 or $Mission_Type ==14 or $Zone ==6)
								$img=Afficher_Image("images/flak_nav.jpg","images/image.png","D.C.A Navale");
							else
							{
								if($Nuit)
									$img="<img src='images/flak_nuit.jpg' style='width:50%;'>";
								else
									$img=Afficher_Image('images/flak3'.$Pays_eni.'.jpg',"images/image.png","D.C.A");
							}				
							//Boucle pièces
							$attaque=true;
							while($data=mysqli_fetch_array($result))
							{
								if($OK_DCA)
								{
									$DCA_ID=$data['DCA_ID'];
									$DCA_Unit=$data['Unit'];
									$DCA_EXP=$data['DCA_Exp']*25;	
									$DCA_Nbr=$data['DCA_Nbr'];
								}
								else
								{
									if($data['Arme_AA3'] >0 and $alt <1000)
										$DCA_ID=$data['Arme_AA3'];
									elseif($data['Arme_AA2'] >0 and $alt <4000)
										$DCA_ID=$data['Arme_AA2'];
									else
										$DCA_ID=$data['Arme_AA'];
									$DCA_Unit=$data['ID'];
									$DCA_EXP=$data['Experience'];	
									$DCA_Nbr=$data['Vehicule_Nbr'];
									$Vehicule_ID=$data['Vehicule_ID'];
									if($data['mobile'] ==5) //Navire
										$Range=GetData("Armes","ID",$DCA_ID,"Portee");
									else
										$Range=$data['Portee'];
									$Malus_Range=$alt/100;
									if($Range >$alt)$Malus_Range+=(($Range-$alt)/100);
								}
								//Muns
								$dca_cal=round(GetData("Armes","ID",$DCA_ID,"Calibre"));
								if($dca_cal)
								{
									if($dca_cal >40 and $alt <501 and $Type_avion !=11)
										$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
									else
									{
										$dca_mult=GetData("Armes","ID",$DCA_ID,"Multi")*mt_rand(1,$DCA_Nbr);
										if($Flak_IA_Ground)
											$DCA_mun=9999;
										else
											$DCA_mun=GetData($Unit_table,"ID",$DCA_Unit,"Stock_Munitions_".$dca_cal);
										if($DCA_mun >=$dca_mult)
										{
											if(!$Flak_IA_Ground)
											{
												UpdateData($Unit_table,"Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$DCA_Unit);
												AddEvent($Avion_db,277,$avion,$PlayerID,$DCA_Unit,$Cible,$dca_mult,$DCA_ID);
											}
											if($Detect >0)
											{			
												$Shoot_Dca=mt_rand(0,$DCA_EXP)+$dca_mult;
												if($alt <5000 and $VitAvion <$Shoot_Dca)$Shoot_Dca+=((5000-$alt)/50);
												$DCA_dg=GetData("Armes","ID",$DCA_ID,"Degats");
												//DCA sur Formation
												if($DCA_Nbr >1 and $Formation >0)
												{
													$Formation_abattue=0;
													//$con=dbconnecti();
													$resultf=mysqli_query($con,"SELECT Avion1,Avion2,Avion3 FROM Unit WHERE ID='$Unite'");
													//mysqli_close($con);
													if($resultf)
													{
														while($data=mysqli_fetch_array($resultf,MYSQLI_ASSOC))
														{
															$Avion1_dca=$data['Avion1'];
															$Avion2_dca=$data['Avion2'];
															$Avion3_dca=$data['Avion3'];
														}
														mysqli_free_result($resultf);
													}
													//$con=dbconnecti();
													$resultp=mysqli_query($con,"SELECT p.ID,p.Nom,p.Pilotage,p.Tactique,p.Avion,a.Visibilite,a.VitesseB,a.Blindage,a.Robustesse FROM Pilote_IA as p,Avion as a 
													WHERE p.Avion=a.ID AND p.Unit='$Unite' AND p.Cible='$Cible' AND p.Actif=1 ORDER BY RAND() LIMIT '$DCA_Nbr'");
													//mysqli_close($con);
													if($resultp)
													{
														while($dataa=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
														{
															$Pilote_ia_dca=$dataa['ID'];
															$Nom_pilote_ia=$dataa['Nom'];
															$Avion_dca=$dataa['Avion'];
															$VisAvion_dca=$dataa['Visibilite'];
															$Tactique_dca=$dataa['Tactique']+$ExpTac;
															$Pilotage_dca=$dataa['Pilotage'];
															$VitAvion_dca=$dataa['VitesseB'];
															$Blindage_dca=$dataa['Blindage'];
															$Robustesse_dca=$dataa['Robustesse'];
															$Shoot=$Shoot_Dca + $meteo + $VisAvion_dca - $Malus_Range - ($Tactique_dca/10) - ($Pilotage_dca/10) - ($VitAvion_dca/10);
															if($Shoot >1)
															{
																$Degats=round((mt_rand(1,$DCA_dg)-pow($Blindage_dca,2))*GetShoot($Shoot,$dca_mult));
																AddEvent("Avion",179,$Avion_dca,$Pilote_ia_dca,$Unite,$Cible,2,$Pays_eni);
																if($alt <4500)$Degats+=ceil($VisAvion_dca);
																if($Degats >$Robustesse_dca)
																{
																	$intro.="<br>L'explosion met le feu à l'avion de ".$Nom_pilote_ia.", ne lui laissant pas d'autre choix que de sauter en parachute!";
																	//$con=dbconnecti();
																	$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0 WHERE ID='$Pilote_ia_dca'");
																	//mysqli_close($con);
																	if($Avion_dca ==$Avion1_dca)
																		$Avion1_Nbr_dca+=1;
																	elseif($Avion_dca ==$Avion2_dca)
																		$Avion2_Nbr_dca+=1;
																	elseif($Avion_dca ==$Avion3_dca)
																		$Avion3_Nbr_dca+=1;
																	$Formation-=1;
																	$Formation_abattue+=1;
																	if($Avion_Bombe ==100 and $Avion_Bombe_nbr ==10)
																	{
																		//$con=dbconnecti();
																		$reset3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=0,Vehicule_Nbr=0,Moral=0 WHERE Officier_ID='$Cible_Atk' ORDER BY RAND() LIMIT 1");
																		//mysqli_close($con);
																		$intro.="<br>Une compagnie de parachutistes a été perdue!";
																	}
																	if(!$Discipline_fer or mt_rand(0,1) >0)
																		WoundPilotIA($Pilote_ia_dca);
																}
																else
																	$intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", mais il peut heureusement continuer sa mission!";
															}
														}
														mysqli_free_result($resultp);
													}
													if($Formation_abattue >0)
													{
														//$con=dbconnecti();
														$reset=mysqli_query($con,"UPDATE Unit SET Avion1_Nbr=Avion1_Nbr-'$Avion1_Nbr_dca',Avion2_Nbr=Avion2_Nbr-'$Avion2_Nbr_dca',Avion3_Nbr=Avion3_Nbr-'$Avion3_Nbr_dca',Reputation=Reputation-'$Formation_abattue' WHERE ID='$Unite'");
														//mysqli_close($con);
														SetData("Pilote","S_Formation",$Formation,"ID",$PlayerID);
													}
												}
												$Shoot=$Shoot_Dca + $meteo + $VisAvion - $Malus_Range - ($Tactique/10) - ($VitAvion/10);
												if($Shoot >1 or $Shoot_Dca ==$DCA_EXP)
												{
													if($alt <501 and $Type_avion ==11)
														$Degats_base=$DCA_dg;
													else
														$Degats_base=mt_rand(1,$DCA_dg);
													$Degats=round(($Degats_base-pow($Blindage,2))*GetShoot($Shoot,$dca_mult));
													if($Degats <1)$Degats=mt_rand(1,10);
													if($alt <4500)$Degats+=ceil($VisAvion);
													$HP-=$Degats;
													if(!$Flak_IA_Ground)
													{
														AddEventGround(379,$avion_img,$PlayerID,$DCA_Unit,$Cible,$Unite,$Vehicule_ID);
														UpdateData("Regiment","Experience",10,"ID",$DCA_Unit);
														UpdateData("Regiment","Moral",10,"ID",$DCA_Unit);
														UpdateCarac($Officier_DCA,"Avancement",10,"Officier");
														UpdateCarac($Officier_DCA,"Reputation",10,"Officier");
													}
													//HP Avion perso persistant
													if($Avion_db =="Avions_Persos")
													{
														if($HP <1)$HP=0;
														SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
													}
													if($Shoot >100 or $alt <1001)
													{
														$CritH=CriticalHit($Avion_db,$avion,$PlayerID,2,$Engine_Nbr); //Todo : Remplacer 2 par type de munition
														$intro.=$CritH[0];
														$end_mission=$CritH[1];
														if($end_mission)
															$HP=0;
														if($CritH[2] ==1)
															$Mun1=0;
														if($CritH[3] ==1)
															$Mun2=0;
														if($CritH[6])
															$essence-=$CritH[6];
														unset($CritH);
													}
													if($HP <1)
													{
														$intro.='<br>L\'explosion met le feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
														if($Avion_Bombe ==100 and $Avion_Bombe_Nbr ==10)
														{
															//$con=dbconnecti();
															$reset=mysqli_query($con,"UPDATE Pilote SET S_Cible_Atk=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$PlayerID'");
															$reset3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=0,Vehicule_Nbr=0,Moral=0 WHERE Officier_ID='$Cible_Atk'");
															//mysqli_close($con);
															$intro.="<br>Le bataillon complet de parachutistes a été perdu!";
														}
														$end_mission=true;
														$Action=90;
														break;
													}
													else
													{
														$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
														if($Equipage and $Equipage_Nbr >1)
														{
															if($Eq_Moral >0 and $Eq_Courage >0)
															{
																if($Simu)UpdateCarac($Equipage,"Mecanique",1,"Equipage");
																if($Meca > $Degats)$Meca=$Degats;
																$intro.='<br>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)';
																$HP+=$Meca;
															}
														}
													}
													SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
												}
												else
													$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
											}
											else
												$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
										}
										else
											$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
									}
								}
							}
							mysqli_free_result($result);
						}
					}//No Detect
				}	
			}
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
						$VitAvion_dca=$VitAvion;
						$Blindage_dca=$Blindage;
						if($Formation >0)
						{
							$intro.="<br>Les explosions de DCA encadrent votre formation!<br>Vous vous trouvez à ".$alt."m d'altitude.";
							$cible_dca=mt_rand(0,$Formation);
							if($cible_dca >0)
							{
								$queryp="SELECT p.ID,p.Nom,p.Pilotage,p.Tactique,p.Avion,a.Visibilite,a.VitesseB,a.Blindage,a.Robustesse FROM Pilote_IA as p,Avion as a 
								WHERE p.Avion=a.ID AND p.Unit='$Unite' AND p.Cible='$Cible' AND p.Actif=1 ORDER BY RAND() LIMIT 1"; 
								//$con=dbconnecti();
								$resultp=mysqli_query($con,$queryp);
								//mysqli_close($con);
								if($resultp)
								{
									while($dataa=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
									{
										$Pilote_ia_dca=$dataa['ID'];
										$Nom_pilote_ia=$dataa['Nom'];
										$Avion_dca=$dataa['Avion'];
										$VisAvion_dca=$dataa['Visibilite'];
										$Tactique_dca=$dataa['Tactique']+$ExpTac;
										$Pilotage_dca=$dataa['Pilotage'];
										$VitAvion_dca=$dataa['VitesseB'];
										$Blindage_dca=$dataa['Blindage'];
										$HP_dca=$dataa['Robustesse'];
									}
									mysqli_free_result($resultp);
								}
								$intro.=" <b>La défense anti-aérienne concentre son tir sur ".$Nom_pilote_ia."!</b>";
							}
							else
								$intro.=" <b>La défense anti-aérienne ouvre le feu sur vous!</b>";
						}
						else
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
									//HP Avion perso persistant
									if($Avion_db_dca =="Avions_Persos")
									{
										if($HP <1)$HP=0;
										SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
									}
									if($HP <1)
									{
										$intro.='<br>L\'explosion met le feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
										if($Avion_Bombe ==100 and $Avion_Bombe_nbr ==10)
										{
											//$con=dbconnecti();
											$reset=mysqli_query($con,"UPDATE Pilote SET S_Cible_Atk=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$PlayerID'");
											$reset3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=0,Vehicule_Nbr=0,Moral=0 WHERE Officier_ID='$Cible_Atk'");
											//mysqli_close($con);
											$intro.="<br>Le bataillon complet de parachutistes a été perdu!";
										}
										$Action=90;
										if(!$Sandbox)
											AddVictoire_atk($Avion_db,0,16,$avion,$PlayerID,$Unite,$Cible,$Arme1,$country,1,$alt,$Nuit,$Degats);
										break;
									}
									else
									{
										$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
										if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1 and !$Chk_Objectif)
										{
											if(GetData("Equipage","ID",$Equipage,"Moral") >0 and GetData("Equipage","ID",$Equipage,"Courage") >0)
											{
												$Equipage_Nom=GetData("Equipage","ID",$Equipage,"Nom");
												$Meca=floor(GetData("Equipage","ID",$Equipage,"Mecanique")/2);
												if($Simu)UpdateCarac($Equipage,"Mecanique",1,"Equipage");
												if($Meca >$Degats)$Meca=$Degats;
												$intro.='<p>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)</p>';
												$HP+=$Meca;
											}
										}
									}
									SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
								}
								elseif($Degats >$HP_dca and $Pilote_ia_dca and $Avion_dca)
								{
									//$con=dbconnecti();
									$result=mysqli_query($con,"SELECT Avion1,Avion2,Avion3 FROM Unit WHERE ID='$Unite'");
									$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0 WHERE ID='$Pilote_ia_dca'");
									//mysqli_close($con);
									if($result)
									{
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
										{
											if($Avion_dca ==$data['Avion3'])
												$Avion_Flight_Lose="Avion3_Nbr";
											elseif($Avion_dca ==$data['Avion2'])
												$Avion_Flight_Lose="Avion2_Nbr";
											else
												$Avion_Flight_Lose="Avion1_Nbr";
										}
										mysqli_free_result($result);
									}
									if(!$Discipline_fer or mt_rand(0,1) >0)
										WoundPilotIA($Pilote_ia_dca);
									UpdateData("Unit",$Avion_Flight_Lose,-1,"ID",$Unite);
									UpdateData("Unit","Reputation",-10,"ID",$Unite,0,5);
									UpdateData("Pilote","S_Formation",-1,"ID",$PlayerID);
									$Formation-=1;
									AddEvent("Avion",79,$Avion_dca,$Pilote_ia_dca,$Unite,$Cible,3,$Pays_eni);
									$intro.="<br>L'explosion met le feu à l'avion de ".$Nom_pilote_ia.", ne lui laissant pas d'autre choix que de sauter en parachute!";
									if($Avion_Bombe ==100 and $Avion_Bombe_nbr ==10)
									{
										//$con=dbconnecti();
										$reset3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=0,Vehicule_Nbr=0,Moral=0 WHERE Officier_ID='$Cible_Atk' ORDER BY RAND() LIMIT 1");
										//mysqli_close($con);
										$intro.="<br>Une Compagnie de parachutistes a été perdue!";
									}
								}
								else
									$intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", mais il peut heureusement continuer sa mission!";
							}
							else
								$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
						}
						if($dca_site_hit and !$Sandbox)
						{
							if($Avion_db =="Avions_Persos")
								$avion_event=GetData($Avion_db,"ID",$avion,"ID_ref");
							else
								$avion_event=$avion;
							AddEventFeed(78,$avion_event,$PlayerID,$Unite,$Cible,3,$Pays_eni); //3=DCA Site
						}
					}
					else
						$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
				}	
			}
		}
		switch($Action)
		{
			case 1:
				$HP_eni=0; //Pas encore défini, vu que pas encore d'ennemi, mais nécessaire pour bomb.php
				//$con=dbconnecti();
				$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
				$Faction_eni=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$Pays_eni'"),0);
				$Escorte_Base=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j, Pays as p WHERE j.Pays=p.ID AND p.Faction='$Faction' AND j.Escorte='$Cible' AND j.Cible='$Cible' AND j.Actif=1"),0);
				$Couverture_Base=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j, Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Cible='$Cible' AND j.Actif=1"),0);
				$result=mysqli_query($con,"SELECT Front,Moral,Escorte,Couverture,S_Cible_Atk FROM Pilote WHERE ID='$PlayerID'");
				//mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Front=$data['Front'];
						$Moral=$data['Moral'];
						$Escorte_PJ=$data['Escorte'];
						$Couv=$data['Couverture'];
						$Cible_Atk=$data['S_Cible_Atk'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				/*if($Faction ==$Faction_eni) //Les avions effectuant une attaque sur un lieu occupé par leur faction bénéficient des patrouilles en plus des escortes pour passer la couverture
				{
					$con=dbconnecti();
					$Couverture_Base_Def=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j, Pays as p WHERE j.Pays=p.ID AND p.Faction='$Faction' AND j.Couverture='$Cible' AND j.Actif=1"),0);
					mysqli_close($con);
					$Escorte_Base+=$Couverture_Base_Def;
				}
				//Escorte<>Couverture
				if($Escorte_nbr <$Escorte_Base)$Escorte_Base=$Escorte_nbr;
				$Escorte_Base*=2;*/
				if($Mission_Type !=15 and $Mission_Type !=18 and $Mission_Type !=19 and $Mission_Type !=22 and $Mission_Type !=4 and $Mission_Type !=7 and $Mission_Type !=9 and $Mission_Type !=17 and $Mission_Type !=26 and !$Nuit and !$Sandbox and $Couverture_Base >0 and !$Escorte_Base) //$Couverture_Base >$Escorte_Base)
				{
					$intro.="<p><b>La couverture aérienne de l'ennemi vous empêche d'effectuer votre mission! (Demandez une escorte !)</b></p>";
					$img=Afficher_Image('images/facetoface.jpg','images/avions/vol'.$avion_img.'.jpg','PvP');
					$patrol=true;
				}
				//Appui
				elseif($Mission_Type ==1 or $Mission_Type ==6 or $Mission_Type ==31)
				{
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
					elseif($meteo >-6)
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					if($Recce_Base ==2)
						$Nuit=0;
					//Camouflage terrain
					if($Mission_Type ==1)
						$Camouflage_cible=0;
					elseif($Cible_Atk ==1 and $BaseAerienne ==3)
						$Camouflage_cible*=1.5;
					$reperer=$Vue+$Vue_avion+($meteo*2)+($Moral/10)+$Vue_Eq-$Malus_Reperer-($Camouflage_cible/2)-($Nuit*100)-($alt/100);
					/*if($reperer <10)
					{
						$headers='MIME-Version: 1.0' . "\r\n";
						$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
						$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo."</p>";
						$msgm.="<br>[Score de reperer : ".$reperer."]
											<br>+Vue ".$Vue."
											<br>+Vue_Equipage ".$Vue_Eq."
											<br>+Vue Avion ".$Vue_avion."
											<br>-Meteo ".$meteo." *2
											<br>-Cam ".$Camouflage_cible."
											<br>-Malus_Reperer ".$Malus_Reperer."</body></html>";
						mail('binote@hotmail.com','Aube des Aigles: Reperer Atk Log',$msgm,$headers);
					}*/
					if($reperer >0)
					{
						if(!$Sandbox)
							AddEvent($Avion_db,5,$avion,$PlayerID,$Unite,$Cible);
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
						SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
						$titre="Attaque au sol";
						$intro.="<p>Vous avez repéré votre cible!</p>";
						$mes.='<form action=\'bomb.php\' method=\'post\'>
						<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
						<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
						<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
						<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
						<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
						<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
						<input type=\'hidden\' name=\'Pays_eni\' value='.$Pays_eni.'>
						'.ShowGaz($avion,$c_gaz,$flaps,$alt,2).'
						<table class=\'table\'><tr><td>'.$Arme1_txt.$Arme2_txt.$Rockets_txt.'
							<Input type=\'Radio\' name=\'Action\' value=\'99\'>- Annuler la mission.<br>
						</td></tr></table>
						<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';		
					}
					else
					{
						/*$debug_msg="Reperer=".$reperer." [Cible=".$Cible."] (Detection=".$Vue." ; Avion=".$Vue_avion." ; Météo*2=".$meteo." ; Malus Terrain=".$Malus_Reperer." ; Camouflage=".$Camouflage_cible.")";
						if($debug_msg)mail('binote@hotmail.com','Aube des Aigles: Reperer Stats',$debug_msg);*/
						$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
						$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
						$retour=true;
					}
				}
				//Sauvetage
				elseif($Mission_Type ==18 or $Mission_Type ==19 or $Mission_Type ==22 or $Mission_Type ==28)
				{
					$atterrissage=true;
					if($Mission_Type ==18 or $Mission_Type ==22)
					{
						if($Train ==13 or $Train ==16)
							$atterrissage=false;
					}
					elseif($Mission_Type ==19)
					{
						if($Train !=13 and $Train !=16)
							$atterrissage=false;
					}					
					if($atterrissage)
					{
						if($meteo <-49)
						{
							if($Mission_Type ==28)
								$intro.="<br>La météo est exécrable, repérer la zone d'atterrissage ne sera pas chose facile!";
							else
								$intro.="<br>La météo est exécrable, repérer le pilote ne sera pas chose facile!";
						}
						else
							$intro.="<br>La météo clémente devrait faciliter votre repérage.";
						$reperer=$Vue+$Vue_avion+$meteo+($Moral/10)+$Vue_Eq-$Malus_Reperer;
						if($reperer >0)
						{
							if($Mission_Type ==28)
							{
								$intro.="<p>Vous avez repéré la zone d'atterrissage!</p>";
								$sauve_input="<Input type='Radio' name='Action' value='".$Cible_Atk."'>- Atterrir.<br>";
							}
							else
							{
								//$con=dbconnecti();
								$result=mysqli_query($con,"SELECT DISTINCT Pilote.ID,Pilote.Nom FROM Pilote,Pays WHERE Pilote.MIA='$Cible' AND Pilote.Pays=Pays.Pays_ID AND Pays.Faction='$Faction'");
								//mysqli_close($con);
								if($result)
								{
									while ($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
									{
										$Sauve=$data['ID'];
										$Sauve_Nom=$data['Nom'];
										$sauve_input.="<Input type='Radio' name='Action' value='".$Sauve."'>- Sauver ".$Sauve_Nom.".<br>";
									}
									mysqli_free_result($result);
								}
								$intro.="<p>Vous avez repéré un pilote abattu!</p>";
							}
							SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
							if($Mission_Type ==19)
								$img.="<img src='images/rescue.jpg' style='width:100%;'>";
							else
								$img.='<img src=\'images/rescue'.$country.'.jpg\' style=\'width:100%;\'>';
							$titre="Sauvetage";
							$mes.='<form action=\'mia.php\' method=\'post\'>
							<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
							<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
							<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
							<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
							<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
							'.ShowGaz($avion,$c_gaz,$flaps,$alt,2).'
							<table class=\'table\'><tr><td>'.$sauve_input.'
								<Input type=\'Radio\' name=\'Action\' value=\'0\'>- Annuler la mission.<br>
							</td></tr></table>
							<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';		
						}
						else
						{
							$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
							UpdateCarac($PlayerID,"Moral",-2);
							$retour=true;
						}
					}
					else
					{
						$intro.="<p>Votre avion ne peut pas atterrir ici, la mission est annulée.</p>";
						UpdateCarac($PlayerID,"Moral",-5);
						UpdateCarac($PlayerID,"Reputation",-2);
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
						//$con=dbconnecti();
						$result=mysqli_query($con,"SELECT DISTINCT Unit.ID,Unit.Nom FROM Unit,Pays WHERE Unit.Base='$Cible' AND Unit.Pays=Pays.Pays_ID AND Unit.ID<>'$Unite' AND Pays.Faction='$Faction'");
						$result2=mysqli_query($con,"SELECT DISTINCT r.ID,r.Vehicule_ID FROM Regiment_IA as r,Pays as p WHERE r.Lieu_ID='$Cible' AND r.Pays=p.Pays_ID AND p.Faction='$Faction'");
						//mysqli_close($con);
						if($result)
						{
							while ($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Sauve=$data['ID'];
								$Sauve_Nom=$data['Nom'];
								$sauve_input.="<option value='".$Sauve."'>".$Sauve_Nom."</option>";
								//'<Input type=\'Radio\' name=\'Action\' value=\''.$Sauve.'\'>- Ravitailler le '.$Sauve_Nom.'.<br>';
							}
							mysqli_free_result($result);
						}
						if($result2)
						{
							while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								$sauve_input.="<option value='".$data['ID']."_'>".$data['ID']."e Compagnie</option>"; 
								//="<Input type='Radio' name='Action' value='".$data['ID']."_'>- Ravitailler la ".$data['ID']."e Compagnie<br>";
							}
							mysqli_free_result($result2);
						}
						/*if($Train ==0)
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
						}*/
						//AddEvent($Avion_db,5,$avion,$PlayerID,$Unite,$Cible);
						SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
						$titre="Ravitaillement";
						$intro.="<p>Vous avez repéré le terrain!</p>";
						$img=Afficher_Image('images/avions/landing'.$avion_img.'.jpg','images/avions/decollage'.$avion_img.'.jpg','Atterrissage');
						$mes.='<form action=\'mia.php\' method=\'post\'>
						<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
						<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
						<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
						<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
						<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
						'.ShowGaz($avion,$c_gaz,$flaps,$alt,7).$roues_txt.'
						<table class=\'table\'><tr><td><select name=\'Action\' class=\'form-control\' style=\'width: 200px\'>'.$sauve_input.'<option value=\'0\'>Annuler la mission</option></select></td></tr></table>
						<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';		
					}
					else
					{
						$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
						UpdateCarac($PlayerID,"Moral",-2);
						$retour=true;
					}
				}
				//Parachutage
				elseif($Mission_Type ==24 or $Mission_Type ==25 or $Mission_Type ==27)
				{				
					if(($Mission_Type ==24 and $meteo <-49) or ($Mission_Type ==25 and $meteo <-129) or ($Mission_Type ==27 and $meteo <-129))
						$intro.="<br>La météo est exécrable, repérer le pilote ne sera pas chose facile!";
					else
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					$reperer=$Vue+$Vue_avion+$meteo+($Moral/10)+$Vue_Eq-$Malus_Reperer-($alt/100);
					if($reperer >0)
					{
						$choix_bomb="Larguer les parachutistes";
						$choix_bomb1="<Input type='Radio' name='Action' value='1'>- Larguer.<br>";
						$choix_bomb2="";
						$choix_bomb4="";
						$choix_bomb5="";
						$choix_bomb6="";
						$bomb=true;
						if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
							$atk_img_txt='lieu/lieu'.$Cible;
						else
							$atk_img_txt='lieu/objectif'.$Cible_map;
					}
					else
					{
						$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
						UpdateCarac($PlayerID,"Moral",-2);
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
						if($Cible_Atk ==99)//Echec mission chasse
						{
							$intro.="<p><b>Vous avez échoué dans la mission qui vous a été assignée!</b></p>";
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							UpdateCarac($PlayerID,"Avancement",-10);
							UpdateCarac($PlayerID,"Reputation",-10);
							UpdateCarac($PlayerID,"Moral",-5);
							UpdateData("Unit","Reputation",-5,"ID",$Unite,0,2);
							$retour=true;
						}
						else
						{
							if(!$pvp_eni)
							{
								$intro.="<p><b>Vous avez accompli la mission qui vous a été assignée.</b></p>";
								$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
								if($Simu)UpdateCarac($Equipage,"Missions",1,"Equipage");
								if(!$Couv and !$Escorte_PJ)
								{
									//Bataille historique
									if($Cible ==$BH_Lieu and $Simu)
									{
										if(IsAxe($country))
											$Points_cat="Points_Axe";
										else
											$Points_cat="Points_Allies";
										UpdateData("Event_Historique",$Points_cat,2,"ID",$_SESSION['BH_ID']);
										UpdateCarac($PlayerID,"Batailles_Histo",1);
										UpdateData("Unit","Reputation",30,"ID",$Unite,0,6);
									}
									//Couverture
									if($Mission_Type ==7)
									{
										//$con=dbconnecti();
										$resetp=mysqli_query($con,"UPDATE Pilote SET Couverture='$Cible',S_alt='$alt',Avancement=Avancement+2,Reputation=Reputation+2,Moral=Moral+2,Missions=Missions+2 WHERE ID='$PlayerID'");
										$updateunits=mysqli_query($con,"UPDATE Unit SET Reputation=Reputation+30 WHERE ID='$Unite' OR (Pays='$country' AND Mission_Lieu_D='$Cible' AND Mission_Type_D=7)");
										$resetpia=mysqli_query($con,"UPDATE Pilote_IA SET Couverture='$Cible',Cible='$Cible',Reputation=Reputation+10,Avancement=Avancement+10,Points=Points+1,Moral=Moral+10,Alt='$alt' WHERE Unit='$Unite' AND Cible='$Cible' AND Avion >0");
										$pilotes_ia=mysqli_affected_rows($con);
										//mysqli_close($con);
										$intro.="<br>Vous établissez un périmètre de patrouille au-dessus de l'objectif.";
										if($pilotes_ia >0)
											$intro.="<br>".$pilotes_ia." pilotes de votre escadrille patrouillent à présent sur l'objectif.";
										AddPatrouille($Avion_db,$avion,$PlayerID,$Unite,$Cible,$alt,$Nuit,2);
									}
									elseif($Mission_Type ==17)
									{
										//$con=dbconnecti();
										$resetp=mysqli_query($con,"UPDATE Pilote SET Couverture_Nuit='$Cible',Avancement=Avancement+2,Reputation=Reputation+2,Moral=Moral+2,Missions=Missions+2 WHERE ID='$PlayerID'");
										$updateunits=mysqli_query($con,"UPDATE Unit SET Reputation=Reputation+30 WHERE ID='$Unite' OR (Pays='$country' AND Mission_Lieu_D='$Cible' AND Mission_Type_D=17)");
										$reset=mysqli_query($con,"UPDATE Pilote_IA SET Couverture_Nuit='$Cible',Cible='$Cible,Reputation=Reputation+10,Avancement=Avancement+10,Points=Points+2,Moral=Moral+10,Alt='$alt' WHERE Unit='$Unite' AND Cible='$Cible' AND Avion >0");
										$pilotes_ia=mysqli_affected_rows($con);
										//mysqli_close($con);
										$intro.="<br>Vous établissez un périmètre de patrouille de nuit dans la zone.";
										if($pilotes_ia >0)
											$intro.="<br>".$pilotes_ia." pilotes de votre escadrille patrouillent à présent sur l'objectif.";
										AddPatrouille($Avion_db,$avion,$PlayerID,$Unite,$Cible,$alt,$Nuit,3);
									}
								}
								else
								{
									$intro.="<p><b>Vous êtes déjà occupé à une autre tâche, votre patrouille n'apporte rien à votre faction!</b></p>";
									$img=Afficher_Image('images/facetoface.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
								}
								$retour=true;
							}
							else
							{
								$intro.="<p><b>[PVP] La présence de l'ennemi vous empêche d'effectuer votre mission!</b></p>";
								$img=Afficher_Image('images/facetoface.jpg','images/avions/vol'.$avion_img.'.jpg','PvP');
								$patrol=true;
							}
						}
					}
				}
				//Escorte
				elseif($Mission_Type ==4)
				{
					$essence-=(10+$Conso);
					if(!$pvp_eni and $Simu)
					{
						$Escorteb=GetData("Pilote","ID",$PlayerID,"S_Escorteb");
						$intro.="<p>La formation que vous escortiez vous confirme que la mission a été accomplie.</p>";								
						$img=Afficher_Image('images/avions/formation'.$Escorteb.'.jpg','images/avions/vol'.$Escorteb.'.jpg','');
						//Escorte
						if(!$Couv and !$Escorte_PJ)
						{		
							$Plafond_escorte=GetData("Avion","ID",$Escorteb,"Plafond");
							if($alt >$Plafond_escorte)$alt=$Plafond_escorte;
							//Doubler la récompense en cas de mission demandée
							//$con=dbconnecti();
							$update=mysqli_query($con,"UPDATE Pilote SET S_alt='$alt',Escorte='$Cible',Moral=Moral+8,Avancement=Avancement+2,Reputation=Reputation+2,Moral=Moral+2,Missions=Missions+2 WHERE ID='$PlayerID'");
							$updateunits=mysqli_query($con,"UPDATE Unit SET Reputation=Reputation+30 WHERE ID='$Unite' OR (Pays='$country' AND Mission_Lieu_D='$Cible' AND Mission_Type_D=4)");
							$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Escorte='$Cible',Cible='$Cible',Reputation=Reputation+10,Avancement=Avancement+10,Points=Points+2,Moral=Moral+10,Alt='$alt' WHERE Unit='$Unite' AND Cible='$Cible' AND Avion >0");
							$pilotes_ia=mysqli_affected_rows($con);
							//mysqli_close($con);
							$intro.="<p><b>Vous avez accompli avec succès la mission qui vous a été assignée.</b></p>";					
							if($pilotes_ia >0)
								$intro.="<br>".$pilotes_ia." pilotes de votre escadrille se joignent à l'escorte.";
							AddEscorte($Avion_db,$avion,$PlayerID,$Cible,$Escorteb,$Escorteb_nbr,$Unite,$alt,$Nuit);
						}
						else
						{
							$intro.="<p><b>Vous êtes déjà occupé à une autre tâche, votre escorte n'apporte rien à votre faction!</b></p>";
							$img=Afficher_Image('images/avions/formation'.$Escorteb.'.jpg','images/avions/vol'.$Escorteb.'.jpg','');
						}
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
						$reperer=$Vue + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq - $Malus_Reperer - $Camouflage_cible + $Bonus_Camera;
					else
						$reperer=$Vue + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq - $Malus_Reperer;
					/*$headers='MIME-Version: 1.0' . "\r\n";
					$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
					$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo."</p>";
					$msgm.="<br>[Score de reperer : ".$reperer."]
										<br>+Vue ".$Vue."
										<br>+Vue_Equipage ".$Vue_Eq."
										<br>+Vue Avion ".$Vue_avion."
										<br>-Meteo ".$meteo." *2
										<br>-Cam ".$Camouflage_cible."
										<br>-Malus_Reperer ".$Malus_Reperer."</body></html>";
					mail('binote@hotmail.com', 'Aube des Aigles: Reperer Reco Log',$msgm,$headers);*/
					if($Cible_Atk ==99)//Echec mission  refresh nav.php
					{
						UpdateCarac($PlayerID,"Avancement",-10);
						UpdateCarac($PlayerID,"Reputation",-10);
						UpdateCarac($PlayerID,"Moral",-5);
						UpdateData("Unit","Reputation",-5,"ID",$Unite,0,2);
						$reperer=0;
					}
					if($reperer >0)
					{
						AddEvent($Avion_db,7,$avion,$PlayerID,$Unite,$Cible);
						//Detection aérienne
						if(!$Nuit and $reperer >50)
						{
							//$con=dbconnecti();
							$Unit_renc_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j, Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Actif=1"),0);
							//mysqli_close($con);
						}
						if($Unit_renc_Nbr >0)
							$intro.="<p>Vous repérez des chasseurs ennemis au loin. Cette zone semble surveillée par l'ennemi.</p>";
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
						$reperer=$Vue + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq - $Malus_Reperer - $Camouflage_cible;
						/*if($reperer <10)
						{
							$headers='MIME-Version: 1.0' . "\r\n";
							$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
							$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo."</p>";
							$msgm.="<br>[Score de reperer : ".$reperer."]
												<br>+Vue ".$Vue."
												<br>+Vue_Equipage ".$Vue_Eq."
												<br>+Vue Avion ".$Vue_avion."
												<br>-Meteo ".$meteo." *2
												<br>-Cam ".$Camouflage_cible."
												<br>-Malus_Reperer ".$Malus_Reperer."</body></html>";
							mail('binote@hotmail.com','Aube des Aigles: Reperer Pathfinder Log',$msgm,$headers);
						}*/
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
				/*Intercept
				elseif($Mission_Type ==9)
				{
					$essence-=(5+$Conso);
					$Unit_renc=GetData("Pilote","ID",$PlayerID,"S_Unite_Intercept");
					$Renc=Rencontre_Random($Longitude,$Latitude,$Cible,$Unit_renc,0,1,$PlayerID);
					$Enis=$Renc[0];
					$avion_eni=$Renc[1];
					$Nom_avion_eni=$Renc[2];
					$random_alt=$Renc[5];
					$unit_eni=$Renc[6];
					//Recup ID pour tableau Intercept
					$_SESSION['Escorte_avioneni'] =$Renc[1];
					unset($Renc);
					$Lieu_eni="e ".GetData("Lieu","ID",$Cible,"Nom");				
					$Vis_eni=GetVis("Avion",$avion_eni,$Cible,$meteo,$alt,$random_alt);
					$Malus_alt=abs(($alt-$random_alt)/100);
					if($Trait_e ==7)$Malus_alt ==0;
					$Detect=mt_rand(0,$Vue) + $Vue_avion + ($meteo*2.5) + $Vis_eni - $Malus_alt + ($Moral/10) + $Vue_Eq;
					if($Detect >0)
					{
						if($Detect <50 and $Enis >1)
							$nbr_eni_det="plusieurs";
						else
						{
							$nbr_eni_det=$Enis;
							if($Detect >100)
								$nav_unit_det="du ".GetData("Unit","ID",$unit_eni,"Nom").",";
						}
						$intro.='<p>Vous détectez <b>'.$nbr_eni_det.' '.$Nom_avion_eni.'</b> '.$nav_unit_det.' volant en direction d'.$Lieu_eni.' à  environ '.$random_alt.'m d\'altitude.</p>';  
						$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';
						//Altitude
						if($random_alt >$alt)
						{
							$choix1="Tenter de vous approcher en grimpant.";
							$choix_value="5";
						}
						elseif($alt >$random_alt)
						{
							$choix1="Tenter de vous approcher en bénéficiant de votre avantage en altitude.";
							$choix_value="6";
						}
						else
						{
							$choix1="Tenter de vous approcher.";
							$choix_value="1";
						}
					}
					else
					{
						if($Equipage_Nbr >1)
							$intro.="<p>Votre mitrailleur vous signale qu'il lui semble avoir vu quelque chose au loin.</p>";
						elseif($Leader)
							$intro.="<p>Votre ailier vous signale qu'il lui semble avoir vu quelque chose au loin.</p>";
						else
							$intro.="<p>Il vous semble avoir vu quelque chose au loin.</p>";
						$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
						$choix1="Tenter de vous approcher.";
						$choix_value="1";
					}
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pilote SET S_Intercept_nbr='$Enis',S_Escorte_nom='$unit_eni',S_Essence='$essence' WHERE ID='$PlayerID'");
					mysqli_close($con);
					Chemin_Retour();
					$chemin=$Distance_totale;
					$titre="Rencontre";
					$mes.='<form action=\'mission2.php\' method=\'post\'>
							<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
							<input type=\'hidden\' name=\'Avioneni\' value='.$avion_eni.'>
							<input type=\'hidden\' name=\'Alt_avioneni\' value='.$random_alt.'>
							<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>	
							<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
							<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
							<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
							<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
							<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
							<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
							<input type=\'hidden\' name=\'Unit_eni\' value='.$unit_eni.'>
							'.ShowGaz($avion,$c_gaz,$flaps,$alt,1).'
							<table class=\'table\'><tr><td>
								<Input type=\'Radio\' name=\'Action\' value=\''.$choix_value.'\'>- '.$choix1.'<br>
								<Input type=\'Radio\' name=\'Action\' value=\'2\' checked>- Attaquer la formation ennemie<br>
								<Input type=\'Radio\' name=\'Action\' value=\'4\'>- Retourner à votre base.<br>
							</td></tr></table>
							<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
				}*/
				//Training
				elseif($Mission_Type ==98)
				{
					$obj_train=mt_rand(5,10);
					$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,0,$Pilotage);
					if($Pilotage <50)
					{
						if($Pilotage <26)
						{
							$obj_train=mt_rand(50,100);
							$intro.="<br>Vous effectuez quelques manoeuvres de base.
							<br>Votre instructeur vous informe qu'il vaut mieux éviter de descendre sous les 20% de puissance moteur en vol normal.
							<br>Votre instructeur vous informe qu'il vaut mieux éviter de descendre sous les 60% de puissance moteur si vous voulez grimper.";
						}
						elseif($Pilotage >25)
						{
							$intro.="<br>Vous effectuez quelques manoeuvres en formation.
							<br>Votre instructeur vous informe qu'il faut toujours suivre les instructions de votre leader, spécialement en combat.";
						}
						UpdateCarac($PlayerID,"Reputation",$obj_train);
					}
					AddPilotage($Avion_db,$avion,$PlayerID,$obj_train);
					if($Equipage)
					{
						if($Trait_e ==4)$obj_train*=2;
						$con=dbconnecti();
						$resulte=mysqli_query($con,"SELECT Radar,Vue,Radio FROM Equipage WHERE ID='$Equipage'");
						mysqli_close($con);
						if($result)
						{
							while($datae=mysqli_fetch_array($resulte,MYSQLI_ASSOC))
							{
								$Radar_Eq=$datae['Radar'];
								$Vue_Eq=$datae['Vue'];
								$Radio_Eq=$datae['Radio'];
							}
							mysqli_free_result($resulte);
							unset($resulte);
						}
						if($Radar_avion and $Radar_Eq <50)
							UpdateCarac($Equipage,"Radar",$obj_train,"Equipage");
						if($Radio_avion and $Radio_Eq <50)
							UpdateCarac($Equipage,"Radio",$obj_train,"Equipage");
						if($Vue_Eq <50)
							UpdateCarac($Equipage,"Vue",$obj_train,"Equipage");
					}
					$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
					$retour=true;
				}
				elseif($Mission_Type ==99)
				{
					$obj_train=mt_rand(10,25);
					$Acrobatie=GetData("Pilote","ID",$PlayerID,"Acrobatie");
					if($Acrobatie <50)
					{
						UpdateCarac($PlayerID,"Reputation",$obj_train);
						$intro.='<br>Votre instructeur vous informe que la vitesse de décrochage est de 50.';
						if($Volets >2)
							$intro.='<br>Votre avion est équipé de freins de piqué';
						elseif($Volets >1)
							$intro.='<br>Votre avion est équipé de volets automatiques';
						elseif($Volets)
							$intro.='<br>Votre avion est équipé de volets améliorés';
						else
							$intro.='<br>Votre avion est équipé de volets standards';
					}
					if($Equipage)
					{
						if($Trait_e ==4)$obj_train*=2;
						$Acrobatie_Eq=GetData("Equipage","ID",$Equipage,"Courage");
						if($Acrobatie_Eq <50)
							UpdateCarac($Equipage,"Courage",$obj_train,"Equipage");
					}
					$intro.='<br>Vous effectuez quelques acrobaties.';
					$img="<img src='images/avions/vol".$avion_img.".jpg' style='width:100%;'>";
					$retour=true;
				}
				elseif($Mission_Type ==100)
				{
					if($Mun1 >0)
					{
						$obj_train=mt_rand(10,25);
						$Tir=GetData("Pilote","ID",$PlayerID,"Tir");
						if($Tir <50)
							UpdateCarac($PlayerID,"Reputation",$obj_train);
						if($Tir >25)
						{
							$Enrayage1=GetData("Armes","ID",$Arme1Avion,"Enrayage");
							$Arme1_Nom=GetData("Armes","ID",$Arme1Avion,"Nom");
							$Enrayage2=GetData("Armes","ID",$Arme2Avion,"Enrayage");
							$Arme2_Nom=GetData("Armes","ID",$Arme2Avion,"Nom");
							if($Enrayage1 >20)
								$arme_txt=" est une arme assez sensible aux enrayages";
							if($Enrayage1 >15)
								$arme_txt=" est une arme relativement sensible aux enrayages";
							elseif($Enrayage1 >10)
								$arme_txt=" est une arme relativement fiable";
							elseif($Enrayage1 >5)
								$arme_txt=" est une arme fiable";
							else
								$arme_txt=" est une arme très fiable";
							if($Enrayage2 >20)
								$arme2_txt=" est une arme assez sensible aux enrayages";
							if($Enrayage2 >15)
								$arme2_txt=" est une arme relativement sensible aux enrayages";
							elseif($Enrayage2 >10)
								$arme2_txt=" est une arme relativement fiable";
							elseif($Enrayage2 >5)
								$arme2_txt=" est une arme fiable";
							else
								$arme2_txt=" est une arme très fiable";
							if($Arme1Avion !=5)
								$Enrayage_txt='<p>Vous constatez que votre '.$Arme1_Nom.$arme_txt;
							if($Arme2Avion !=5)
								$Enrayage_txt.='<p>Vous constatez que votre '.$Arme2_Nom.$arme2_txt;
						}
						if($Equipage)
						{
							if($Trait_e ==4)$obj_train*=2;
							$Tir_Eq=GetData("Equipage","ID",$Equipage,"Tir");
							if($Tir_Eq <50)
								UpdateCarac($Equipage,"Tir",$obj_train,"Equipage");
						}
						$intro.='<br>Vous tirez sur une cible remorquée pour vous perfectionner'.$Enrayage_txt;
						$img="<img src='images/remorquage_cible.jpg' style='width:100%;'>";
						$retour=true;
					}
					else
					{
						$intro.="<p>Vous devez annuler votre mission, car votre avion n'emporte pas d'arme!<br>Choisissez mieux votre avion la prochaine fois!</p>";
						$img="<img src='images/avions/vol".$avion_img.".jpg' style='width:100%;'>";
						$retour=true;
						UpdateCarac($PlayerID,"Reputation",-2);
						UpdateCarac($PlayerID,"Avancement",-2);
					}
				}
				/*elseif($Mission_Type ==101)
				{
					if($Avion_Bombe_nbr >0)
					{
						$obj_train=mt_rand(10,20);
						$Bombardement=GetData("Pilote","ID",$PlayerID,"Bombardement");
						if($Bombardement <50)
						{
							UpdateCarac($PlayerID,"Bombardement",$obj_train);
							UpdateCarac($PlayerID,"Reputation",$obj_train);
						}
						if($Bombardement >10)
						{
							//Bonus Viseur
							$Viseur=GetData($Avion_db,"ID",$avion,"Viseur");
							if($Viseur <3)
								$intro.="<p>Vous constatez que le modèle d'avion que vous pilotez ne possède pas de viseur de bombardement.</p>";
							else
								$intro.="<p>Vous constatez que le modèle d'avion que vous pilotez possède un viseur spécifique pour le bombardement.</p>";
						}
						if($Vue <50)UpdateCarac($PlayerID,"Vue",$obj_train);
						if($Equipage)
						{
							if($Trait_e ==4)$obj_train*=2;
							$Bombardement_Eq=GetData("Equipage","ID",$Equipage,"Bombardement");
							$Vue_Eq=GetData("Equipage","ID",$Equipage,"Vue");
							if($Bombardement_Eq <50)
								UpdateCarac($Equipage,"Bombardement",$obj_train,"Equipage");
							if($Vue_Eq <50)
								UpdateCarac($Equipage,"Vue",$obj_train,"Equipage");
						}
						SetData("Pilote","S_Avion_Bombe_Nbr",0,"ID",$PlayerID);
						$intro.="<br>Vous larguez votre bombe sous le regard de vos instructeurs.";
						$img="<img src='images/explosion_training.jpg' style='width:100%;'>";
						$retour=true;
					}
					else
					{
						$intro.="<p>Vous annulez votre mission, car votre avion n'emporte pas de bombes!<br>Choisissez mieux votre avion la prochaine fois!</p>";
						$img="<img src='images/avions/vol".$avion_img.".jpg' style='width:100%;'>";
						$retour=true;
						UpdateCarac($PlayerID,"Reputation",-2);
						UpdateCarac($PlayerID,"Avancement",-2);
					}
				}*/
				elseif($Mission_Type ==102)
				{
					$obj_train=mt_rand(10,20);
					$Navigation=GetData("Pilote","ID",$PlayerID,"Navigation");
					if($Navigation <26)
					{
						UpdateCarac($PlayerID,"Navigation",$obj_train);
						UpdateCarac($PlayerID,"Reputation",$obj_train);
						if($Vue <50)UpdateCarac($PlayerID,"Vue",$obj_train);
						$intro.="<br>Vous effectuez plusieurs exercices de navigation à vue";
					}
					elseif($Navigation >25 and $Navigation <50)
					{
						UpdateCarac($PlayerID,"Navigation",$obj_train);
						UpdateCarac($PlayerID,"Reputation",$obj_train);
						$intro.="<br>Vous effectuez plusieurs exercices de navigation aux instruments";
					}
					if($Equipage)
					{
						if($Trait_e ==4)$obj_train*=2;
						//$con=dbconnecti();
						$resulte=mysqli_query($con,"SELECT Navigation,Vue,Radio FROM Equipage WHERE ID='$Equipage'");
						//mysqli_close($con);
						if($result)
						{
							while($datae=mysqli_fetch_array($resulte,MYSQLI_ASSOC))
							{
								$Navigation_Eq=$datae['Navigation'];
								$Vue_Eq=$datae['Vue'];
								$Radio_Eq=$datae['Radio'];
							}
							mysqli_free_result($resulte);
							unset($resulte);
						}	
						if($Navigation_Eq <50)
							UpdateCarac($Equipage,"Navigation",$obj_train,"Equipage");
						if($Vue_Eq <50)
							UpdateCarac($Equipage,"Vue",$obj_train,"Equipage");
						if($Navigation_Eq >25 and $Radio_Eq <50)
							UpdateCarac($Equipage,"Radio",$obj_train,"Equipage");
					}
					$img="<img src='images/avions/vol".$avion_img.".jpg' style='width:100%;'>";
					$retour=true;
				}
				elseif($Mission_Type ==103)
				{
					$obj_train=mt_rand(10,25);
					UpdateCarac($PlayerID,"Pilotage",$obj_train);
					UpdateCarac($PlayerID,"Acrobatie",$obj_train);
					UpdateCarac($PlayerID,"Vue",$obj_train);
					UpdateCarac($PlayerID,"Tactique",$obj_train);
					$intro.="<br>Ce combat simulé a amélioré votre compréhension du combat aérien!";
					$img="<img src='images/avions/vol".$avion_img.".jpg' style='width:100%;'>";
					$retour=true;
				}
				//Bomb
				elseif($Mission_Type ==2 or $Mission_Type ==8 or $Mission_Type ==16 or $Mission_Type ==101)
				{
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
					else
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					if($Mission_Type ==2)
					{
						$Camouflage_cible=0;
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
						if($Cible_Atk ==1 and $BaseAerienne ==3)
							$Camouflage_cible*=1.5;
					}
					$reperer=$Vue+$Vue_avion+($meteo*2)+($Moral/10)+$Vue_Eq-$Malus_Reperer-($Camouflage_cible/2)-($Nuit*100)-($alt/100);
					/*if($reperer <10 and ($meteo <0 or $Camouflage_cible >0))
					{
						$headers='MIME-Version: 1.0' . "\r\n";
						$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
						$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo."</p>";
						$msgm.="<br>[Score de reperer : ".$reperer."]
											<br>+Vue ".$Vue."
											<br>+Vue_Equipage ".$Vue_Eq."
											<br>+Vue Avion ".$Vue_avion."
											<br>-Meteo ".$meteo." *2
											<br>-Alt ".$alt." / 100
											<br>-Cam ".$Camouflage_cible."
											<br>-Nuit ".$Nuit." * 100</body></html>";
						mail('binote@hotmail.com','Aube des Aigles: Reperer Bomb Log',$msgm,$headers);
					}*/
					if($reperer >0 or $Mission_Type ==101 or $Compas_oeil)
					{
						//AddEvent($Avion_db,6,$avion,$PlayerID,$Unite,$Cible);
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
							if($Mission_Type ==101)
								$intro="<br><i>En mission de combat votre score de repérage aurait été <b>".$reperer."</b>. Un score positif est nécessaire pour réussir la détection de la cible.
								<br>La météo, l'altitude, la nature du terrain et l'éventuel camouflage influent sur la détection.</i>";
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
					//$con=dbconnecti();
					//$Nav_eni2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$Cible' AND Pays<>'$country' AND Vehicule_Nbr >0 AND Vehicule_ID >4999 AND Visible=1"),0);
					$Nav_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Cible' AND Pays<>'$country' AND Vehicule_Nbr >0 AND Vehicule_ID >4999 AND Visible=1"),0);
					//mysqli_close($con);
					$Nav_eni+=$Nav_eni2;
					$atk_img_txt='lieu/objectif_torpille';
					//$atk_img_txt='lieu/objectif_torpille'.$Pays_eni.$Cible_Atk;
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
					else
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					$reperer=mt_rand(0,$Vue) + $Vue_avion + ($meteo*3) + ($Moral/10) + $Vue_Eq + ($Radar_avion*50) - ($alt/100);
					if($reperer >0 and $Nav_eni >0)
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
						AddEvent($Avion_db,10,$avion,$PlayerID,$Unite,$Cible);
						$bomb=true;
					}
					else
					{
						$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l altitude sont des facteurs importants de la réussite d une détection</span></a></p>";
						$img ='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
						$retour=true;
					}
				}
				elseif($Mission_Type ==14) //Mouillage Mines
				{
					if($Mines_m)
					{
						$intro.="<p>La cible est déjà minée!</p>";
						$img="<img src='images/ocean.jpg' style='width:100%;'>";
						$retour=true;
					}
					else
					{
						if($meteo <-9)
							$intro="<br>le temps est couvert, repérer la cible ne sera pas chose facile!";
						$reperer=$Vue + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq - $Malus_Reperer;
						if($reperer >0)
						{
							$choix_bomb="Mouillage de mines";
							$choix_bomb1="<Input type='Radio' name='Action' value='1'>- Larguer une mine.<br>";
							$choix_bomb2="<Input type='Radio' name='Action' value='4'>- Ordonner à votre équipage de larguer une mine.<br>";		
							$choix_bomb4="";		
							$choix_bomb5="";		
							$choix_bomb6="";		
							$bomb=true;
							if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
								$atk_img_txt='lieu/lieu'.$Cible;
							else
								$atk_img_txt='lieu/objectif'.$Cible_map;
						}
						else
						{
							$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée! <a href='#' class='popup'><img src='images/help.png'><span>La météo, la nature du terrain et l\'altitude sont des facteurs importants de la réussite d\'une détection</span></a></p>";
							$img='<img src=\'images/avions/vol'.$avion_img.'.jpg\' style=\'width:100%;\'>';
							$retour=true;
						}
					}
				}
				/*Convoi
				elseif($Mission_Type ==14)
				{
					$essence-=(10+$Conso);
					if(!$Couv and !$Escorte_PJ and $Simu)
					{
						//Bataille historique
						if($Cible ==$BH_Lieu)
						{
							if(IsAxe($country))
								$Points_cat="Points_Axe";
							else
								$Points_cat="Points_Allies";
							UpdateData("Event_Historique",$Points_cat,2,"ID",$_SESSION['BH_ID']);
							UpdateCarac($PlayerID,"Batailles_Histo",1);
						}
						//Couverture
						SetData("Pilote","Couverture",$Cible,"ID",$PlayerID);
						$intro.="<br>Vous apportez votre soutien à la couverture planifiée par votre commandement.";
					}
					else
					{
						$intro.="<p><b>Vous êtes déjà occupé à une autre tâche, vous ne participez pas à la couverture de cette zone!</b></p>";
						$img=Afficher_Image('images/facetoface.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
					}
					$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
					if(($Cible ==1142 and $Date_Campagne =='1940-11-21') or
						($Cible ==657 and $Date_Campagne =='1940-11-22') or
						($Cible ==1137 and $Date_Campagne =='1940-11-23') or
						($Cible ==604 and $Date_Campagne =='1940-11-24') or
						($Cible ==896 and $Date_Campagne =='1940-11-25'))
					{
						$Ermland=true;
						$type_renc_convoi=mt_rand(1,9);
					}
					else
					{
						$type_renc_convoi=mt_rand(1,10);
					}
					if($type_renc_convoi <4)
					{
						if($Ermland)
						{
							$Escorteb_nbr=1;
							$intro.='<p>Le pétrolier <b>Ermland</b>, est attaqué par des navires ennemis !</p>';
						}
						else
						{
							$Escorteb_nbr=mt_rand(2,12);
							$intro.='<p>Le convoi, composé de <b>'.$Escorteb_nbr.' cargos</b>, est attaqué par des navires ennemis !</p>';
						}
						//$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pilote SET S_Escorteb_nbr='$Escorteb_nbr',S_Intercept_nbr=1 WHERE ID='$PlayerID'");
						//mysqli_close($con);
						if($meteo <-49)
							$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
						else
							$intro.="<br>La météo clémente devrait faciliter votre repérage.";
						$reperer=mt_rand(0,$Vue) + $Vue_avion + ($meteo*3) + ($Moral/10) + $Vue_Eq + ($Radar_avion*50) - ($alt/100);
						if($reperer >0)
						{
							$haute_alt=false;
							$Cible_Atk=mt_rand(12,18);
							SetData("Pilote","S_Cible_Atk",$Cible_Atk,"ID",$PlayerID);
							if($Avion_Bombe_nbr >0)
							{
								$choix_bomb="Bombarder votre cible";
								$choix_bomb1="<Input type='Radio' name='Action' value='3' title='Bombardement de précision. Utilisera une bombe'>- Bombarder votre cible à basse altitude.<br>";			
								if($Type_avion ==7)
									$choix_bomb2="<Input type='Radio' name='Action' value='7' title='Bombardement de précision. Utilisera une bombe'>- Bombarder votre cible en piqué.<br>";	
								$choix_bomb3="";
								$choix_bomb4="";		
								$choix_bomb5="";		
								$choix_bomb6="";		
								if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
								{
									$choix_bomb4="<Input type='Radio' name='Action' value='6' title='Bombardement de précision. Utilisera une bombe'>- Ordonner à votre équipage de bombarder la cible à basse altitude.<br>";
									if($Type_avion ==7)
										$choix_bomb5="<Input type='Radio' name='Action' value='7' title='Bombardement de précision. Utilisera une bombe'>- Ordonner à votre équipage de bombarder votre cible en piqué.<br>";		
								}
							}
							else
							{
								$choix_bomb="Mitrailler votre cible";
								$choix_bomb1="<Input type='Radio' name='Action' value='3'>- Mitrailler votre cible à basse altitude.<br>";		
								$choix_bomb2="";		
								$choix_bomb3="";		
								$choix_bomb4="";		
								$choix_bomb5="";		
								$choix_bomb6="";		
							}
							AddEvent($Avion_db,10,$avion,$PlayerID,$Unite,$Cible);
							$Pays_eni=GetEnnemi($country,$Latitude);
							$atk_img_txt='lieu/objectif_torpille';
							$bomb=true;
						}
						else
						{
							$intro.="<p>Vous ne parvenez pas à repérer votre cible, la mission est annulée. <a href='#' class='popup'><img src='images/help.png'><span>La météo, le camouflage ennemi, la nature du terrain et l altitude sont des facteurs importants de la réussite de la détection</span></a></p>";
							$img ='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
							$retour=true;
						}
					}
					elseif($type_renc_convoi <6)
					{
						$Escorteb_nbr=mt_rand(2,12);
							$intro.='<p>Le convoi, composé de <b>'.$Escorteb_nbr.' cargos</b>, vous signale qu\'il est attaqué par des sous-marins ennemis!</p>';
						//$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pilote SET S_Escorteb_nbr='$Escorteb_nbr',S_Intercept_nbr=1 WHERE ID='$PlayerID'");
						//mysqli_close($con);
						if($meteo <-49)
							$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
						else
							$intro.="<br>La météo clémente devrait faciliter votre repérage.";
						if(mt_rand(0,1))
							$Cible_Atk=23; //Plongee
						else
							$Cible_Atk=22;	
						if($Cible_Atk ==23)
						{
							$cam_asm=mt_rand(0,100);
							$reperer=($Radar_avion*50)+$meteo-$cam_asm;
						}
						else
							$reperer=mt_rand(0,$Vue) + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq + ($Radar_avion*50) - ($alt/100);
						if($reperer >0)
						{
							$haute_alt=false;
							SetData("Pilote","S_Cible_Atk",$Cible_Atk,"ID",$PlayerID);
							if($Avion_Bombe_nbr >0)
							{
								$choix_bomb="Bombarder votre cible";
								if($Avion_Bombe ==300)
									$choix_bomb1="<Input type='Radio' name='Action' value='8' title='Bombardement de précision. Utilisera une charge'>- Larguer une charge de profondeur sur votre cible à basse altitude.<br>";			
								else
									$choix_bomb1="<Input type='Radio' name='Action' value='8' title='Bombardement de précision. Utilisera une bombe'>- Bombarder votre cible à basse altitude.<br>";			
								if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
								{
									if($Avion_Bombe ==300)
										$choix_bomb4="<Input type='Radio' name='Action' value='9' title='Bombardement de précision. Utilisera une charge'>- Ordonner à votre équipage de larguer une charge de profondeur sur la cible à basse altitude.<br>";
									else
										$choix_bomb4="<Input type='Radio' name='Action' value='9' title='Bombardement de précision. Utilisera une bombe'>- Ordonner à votre équipage de bombarder la cible à basse altitude.<br>";		
								}
							}
							else
							{
								$choix_bomb="Mitrailler votre cible";
								$choix_bomb1="<Input type='Radio' name='Action' value='3'>- Mitrailler votre cible à basse altitude.<br>";		
								$choix_bomb2="";		
								$choix_bomb3="";		
								$choix_bomb4="";		
								$choix_bomb5="";		
								$choix_bomb6="";		
							}
							AddEvent($Avion_db,10,$avion,$PlayerID,$Unite,$Cible);
							$Pays_eni=GetEnnemi($country,$Latitude);
							$atk_img_txt='lieu/objectif_torpille'.$Pays_eni.'22';
							$bomb=true;
						}
						else
						{
							$intro.="<p><b>Vous ne détectez aucun sous-marin dans les environs</b>, la mission est annulée. <span style='color:red;' title='La météo, le camouflage ennemi, l utilisation d un radar et l altitude sont des facteurs importants de la réussite de la détection'>?</span></p>";
							$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
							$retour=true;
						}
					}
					elseif($type_renc_convoi <10)
					{
						//Avion
						$Pays_eni=GetEnnemi($country,$Latitude);
						$Renc=Rencontre_Random($Longitude,$Latitude,$Cible,0,$Pays_eni,9,$PlayerID);
						$Enis =$Renc[0];
						$avion_eni =$Renc[1];
						$Nom_avion_eni =$Renc[2];
						$random_alt =$Renc[5];
						$unit_eni =$Renc[6];
						unset($Renc);
						//Infos Convoi
						if($Ermland)
						{
							$Escorteb_nbr=1;
							$Escorteb_nom="Le pétrolier Ermland";
							$intro.='<p>Le pétrolier <b>Ermland</b>, est attaqué par des avions ennemis !</p>';
						}
						else
						{
							$Escorteb_nbr=mt_rand(4,24);
							$Escorteb_nom="Cargo(s) du convoi ".mt_rand(1,999);
							$intro.='<p>Le convoi, composé de <b>'.$Escorteb_nbr.' '.$Escorteb_nom.'</b>, est attaqué par des avions ennemis !</p>';
						}
						//$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pilote SET S_Escorteb=10,S_Escorteb_nom='$Escorteb_nom',S_Escorteb_nbr='$Escorteb_nbr',S_Intercept_nbr='$Enis',S_Essence='$essence' WHERE ID='$PlayerID'");
						//mysqli_close($con);
						$Lieu_eni="e ".GetData("Lieu","ID",$Cible,"Nom");				
						$Vis_eni=GetVis("Avion",$avion_eni,$Cible,$meteo,$alt,$random_alt);
						$Malus_alt=abs(($alt-$random_alt)/100);
						if($Trait_e ==7)$Malus_alt=0;
						$Detect=mt_rand(0,$Vue) + $Vue_avion + ($meteo*2.5) + $Vis_eni - $Malus_alt + ($Moral/10) + $Vue_Eq + ($Radar_avion*50);
						if($Detect >0)
						{
							if($Detect <50 and $Enis >1)
								$nbr_eni_det="plusieurs";
							else
								$nbr_eni_det=$Enis;
							$intro.='<p>Vous détectez <b>'.$nbr_eni_det.' '.$Nom_avion_eni.'</b> volant en direction d'.$Lieu_eni.' à  environ '.$random_alt.'m d\'altitude.</p>';  
							$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';
							//Altitude
							if($random_alt >$alt)
							{
								$choix1="Tenter de vous approcher en grimpant.";
								$choix_value="5";
							}
							elseif($alt >$random_alt)
							{
								$choix1="Tenter de vous approcher en bénéficiant de votre avantage en altitude.";
								$choix_value="6";
							}
							else
							{
								$choix1="Tenter de vous approcher.";
								$choix_value="1";
							}
						}
						else
						{
							if($Equipage_Nbr >1)
								$intro.="<p>Votre mitrailleur vous signale qu'il lui semble avoir vu quelque chose au loin.</p>";
							elseif($Leader)
								$intro.="<p>Votre ailier vous signale qu'il lui semble avoir vu quelque chose au loin.</p>";
							else
								$intro.="<p>Il vous semble avoir vu quelque chose au loin.</p>";
							$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
							$choix1="Tenter de vous approcher.";
							$choix_value="1";
						}
						Chemin_Retour();
						$chemin=$Distance_totale;
						//$intro.="<p>Vous prenez le chemin du retour en direction de votre base, située à ".$Distance_totale."km</p>";
						$titre="Protection du convoi";
						$mes.='<form action=\'mission2.php\' method=\'post\'>
								<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
								<input type=\'hidden\' name=\'Avioneni\' value='.$avion_eni.'>
								<input type=\'hidden\' name=\'Alt_avioneni\' value='.$random_alt.'>
								<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>	
								<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
								<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
								<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
								<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
								<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
								<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
								<input type=\'hidden\' name=\'Unit_eni\' value='.$unit_eni.'>
								'.ShowGaz($avion,$c_gaz,$flaps,$alt,1).'
								<table class=\'table\'><tr><td>
									<Input type=\'Radio\' name=\'Action\' value=\''.$choix_value.'\' checked>- '.$choix1.'<br>
									<Input type=\'Radio\' name=\'Action\' value=\'4\'>- Retourner à votre base.<br>
								</td></tr></table>
								<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
					}
					else
					{
						SetData("Pilote","S_Escorteb_nbr",1,"ID",$PlayerID);
						$Enis=0;
						$intro.="<p>Le convoi que vous escortez rentre au port sans encombre.</p><p><b>Vous avez accompli la mission qui vous a été assignée!</b></p>";
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						UpdateCarac($PlayerID,"Moral",2);
						$retour=true;
					}
				}*/
				//ASM
				elseif($Mission_Type ==29)
				{
					$essence-=(10+$Conso);					
					//$con=dbconnecti();
					$subs=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Cible' AND Vehicule_Nbr >0 AND Position=25"),0);
					//mysqli_close($con);
					if($meteo <-49)
						$intro.="<br>La météo est exécrable, repérer les cibles ne sera pas chose facile!";
					else
						$intro.="<br>La météo clémente devrait faciliter votre repérage.";
					$reperer=mt_rand(0,$Vue) + $Vue_avion + ($meteo*2) + ($Moral/10) + $Vue_Eq + ($Radar_avion*50) - ($alt/100);
					if($subs >0 and $reperer >0)
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
						SetData("Pilote","S_Cible_Atk",23,"ID",$PlayerID);
						AddEvent($Avion_db,10,$avion,$PlayerID,$Unite,$Cible);
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
				$PvP_Avion=GetData("Duels_Candidats","PlayerID",$PlayerID,"Avion");
				$HP_PvP=GetData("Duels_Candidats","PlayerID",$PlayerID,"HP");
				if($PvP_Avion and $HP_PvP <1)
				{
					$intro.="<p>Vous avez été abattu par un avion ennemi !</p>";
					$img="<img src='images/kill".$country.".jpg' style='width:100%;'>";
					UpdateCarac($PlayerID,"Moral",-25);
					UpdateCarac($PlayerID,"Reputation",-20);
					RetireCandidat($PlayerID,"objectif");
					$_SESSION['PVP'] =false;
					$PVP=false;
					$end_mission=true;
				}
				else
				{
					if(!$img)
						$img="<img src='images/avions/vol".$avion_img.".jpg' style='width:100%;'>";
					$patrol=true;
				}
			break;
			case $Action >99:
				$essence-=(5+$Conso);
				$PvP_ID=substr($Action,2);
				$PvP_Cible=GetData("Duels_Candidats","PlayerID",$PvP_ID,"Lieu");
				if($PlayerID ==1)
					$skills.='<p>PVPID='.$PvP_ID.'</p>';
				if($Cible ==$PvP_Cible)
				{
					$mission3=true;
					$PVP=true;
					$_SESSION['PVP']=true;
					$_SESSION['done']=false;
					$chemin=0;
					SetData("Duels_Candidats","Target",$PvP_ID,"PlayerID",$PlayerID);
					//$con=dbconnecti();
					$result=mysqli_query($con,"SELECT HP,Avion,Altitude FROM Duels_Candidats WHERE PlayerID='$PvP_ID'");
					//mysqli_close($con);
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
					//$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Type,Engine_Nbr FROM Avion WHERE ID='$avion_eni'");
					//mysqli_close($con);
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
					SetData("Pilote","S_Engine_Nbr_Eni",$Engine_Nbr_eni,"ID",$PlayerID);
					$Unit_eni=GetData("Pilote","ID",$PvP_ID,"Unit");
					$Pilote_eni=$PvP_ID;
					$Avion_db_eni="Avion";
					$Enis=1;
					$intro='Vous engagez le combat contre un <b>'.$PvP_Avion_Nom.'</b>';
					AddEvent($Avion_db,90,$avion,$PlayerID,$Unite,$Cible,$avion_eni);
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
						$Ventre="<Input type='Radio' name='Action' value='11'>- Tenter d'attaquer par le ventre en évitant le mitrailleur arrière.<br>";
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
					SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
					$img="<img src='images/avions/vol".$avion_eni.".jpg' style='width:100%;'>";
					$titre="Combat";
					$menu.='<form action=\'mission3.php\' method=\'post\'>
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
						<input type=\'hidden\' name=\'Unit_eni\' value='.$Unit_eni.'>
						<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
						<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
						'.ShowGaz($avion,$c_gaz,$flaps,$alt_avioneni,1).'
						<table class=\'table\'><tr><td>'.$choix1.$choix7.$choix8.$choix2.$choix3.'
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'4\' checked>- Tenter de fuir le combat en vous lançant dans un piqué.<br>
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Nuages').'\' value=\'5\'>- '.$choix5.'<br>'.$Ventre.'
						</td></tr></table>
						<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
				}
			break;
		}		
		SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
		if($bomb)
		{
			if($haute_alt)
				$com_int=ShowGaz($avion,$c_gaz,$flaps,$alt);
			else
				$com_int=ShowGaz($avion,$c_gaz,$flaps,$alt,4);
			$intro.="<p>Vous localisez votre cible!</p>";
			$img=Afficher_Image('images/'.$atk_img_txt.'.jpg',"images/image.png","Cible");
			$titre=$choix_bomb;
			$mes.='<form action=\'bomb.php\' method=\'post\'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
			<input type=\'hidden\' name=\'Pays_eni\' value='.$Pays_eni.'>
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
			$titre="Retour";
			$mes.='<form action=\'nav.php\' method=\'post\'>
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
			<input type=\'hidden\' name=\'Distance\' value='.$Distance_totale.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt).'
			<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}		
		if($patrol)
		{
			$_SESSION['done']=false;
			$chemin=0;
			$titre="Patrouille";
			$intro.="<p>Vous patrouillez dans la zone</p>";
			$mes.='<form action=\'nav.php\' method=\'post\'>
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
			<input type=\'hidden\' name=\'Distance\' value='.$Distance_totale.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
			<input type=\'hidden\' name=\'Patrol\' value=\'1\'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt).'
			<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}
	}	
	if($end_mission)
	{
		$NoAddVic=true;
		include_once('./end_mission.php');
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
if($PlayerID ==1 or $PlayerID ==2)
{
	$skills.='<br>'.memory_get_usage().'/'.memory_get_peak_usage().'<br>';
	//GetData Enis PVP	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT PlayerID,Lieu,Avion FROM Duels_Candidats WHERE PlayerID<>'$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$En_PlayerID=$data['PlayerID'];
			$En_Lieu=$data['Lieu'];
			$En_Avion=$data['Avion'];
			$skills.="<p>".GetData("Pilote","ID",$En_PlayerID,"Nom")." ( ".GetData("Avion","ID",$En_Avion,"Nom")." ) : ".GetData("Lieu","ID",$En_Lieu,"Nom")."</p>";
		}
		mysqli_free_result($result);
		unset($data);
	}
	$time=microtime();
	$time=explode(' ',$time);
	$time=$time[1] + $time[0];
	$finish=$time;
	$total_time=round(($finish-$start),4);
	$skills.='<br>Page generated in '.$total_time.' seconds.';
}
//Time
usleep(1);
include_once('./index.php');
?>