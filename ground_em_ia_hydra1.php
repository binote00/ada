<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{	
	include_once('./jfv_include.inc.php');
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		include_once('./jfv_air_inc.php');
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_inc_em.php');
		$Cible=Insec($_POST['Cible']);
		$Type=Insec($_POST['Type']);
		$Avion=Insec($_POST['Avion']);
		$Base=Insec($_POST['Base']);
		$Reg=Insec($_POST['Reg']);
		$Mission_alt=Insec($_POST['Altitude']);
		$country=$_SESSION['country'];
		if($Cible and $Type and $Avion and $Base and $Reg)
		{
            $Unite=99999; //unité aérienne requise pour la DB mais inexistante dans le cas des hydra embarqués
			include_once('./jfv_map.inc.php');
			include_once('./jfv_combat.inc.php');
			function AddAtk_IA($Cible,$Unite,$Pilotes,$Avion,$Arme,$Alt,$Target,$Cycle,$DCA,$Escorte,$Couverture)
			{
				$date=date('Y-m-d G:i');
				$query="INSERT INTO Attaque_ia (Date, Lieu, Unite, Pilotes, Avion, Arme, Altitude, Target, Cycle, DCA, Escorte, Couverture)
				VALUES ('$date','$Cible','$Unite','$Pilotes','$Avion','$Arme','$Alt','$Target','$Cycle','$DCA','$Escorte','$Couverture')";
				$con=dbconnecti();
				$ok=mysqli_query($con,$query);
				mysqli_close($con);
				if(!$ok){
					$msg.="Erreur de mise à jour : Lieu=".$Cible." / Unite=".$Unite." / Date=".$date." ".mysqli_error($con);
					mail('binote@hotmail.com','Aube des Aigles: AddAtk_IA Error',$msg);
				}
			}
			$CT=4;
			$Avion_Nbr=1;
			$con=dbconnecti();
			$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
			$resultl=mysqli_query($con,"SELECT Nom,Latitude,Longitude,Zone,BaseAerienne,Camouflage,Meteo,DefenseAA_temp,ValeurStrat,Flag,Fortification FROM Lieu WHERE ID='$Cible'");
			$resulta=mysqli_query($con,"SELECT Type,Engine,Engine_Nbr,Robustesse,Stabilite,Visibilite,Blindage,ArmeSecondaire,Volets,Radar,ManoeuvreB,ManoeuvreH,Maniabilite FROM Avion WHERE ID='$Avion'");
			$resultb=mysqli_query($con,"SELECT Latitude,Longitude FROM Lieu WHERE ID='$Base'");
			mysqli_close($con);
			if($resultl)
			{
				while($data=mysqli_fetch_array($resultl,MYSQLI_ASSOC))
				{
					$Nom_Lieu=$data['Nom'];
					$Zone=$data['Zone'];
					$BaseAerienne=$data['BaseAerienne'];
					$Camouflage=$data['Camouflage'];
					$Latitude_c=$data['Latitude'];
					$Longitude_c=$data['Longitude'];
					$meteo=$data['Meteo'];
					$DefenseAA=$data['DefenseAA_temp'];
					$ValStrat=$data['ValeurStrat'];
					$Fortification=$data['Fortification'];
					$Pays_eni=$data['Flag'];
				}
				mysqli_free_result($resultl);
			}
			if($resulta)
			{
				while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
				{
					$HP_avion=$data['Robustesse'];
					$Engine=$data['Engine'];
					$Engine_Nbr=$data['Engine_Nbr'];
					$Blindage=$data['Blindage'];
					$Stab=$data['Stabilite'];
					$Camera=$data['ArmeSecondaire'];
					$VisAvion=$data['Visibilite'];
					$Type_avion=$data['Type'];
					$Volets=$data['Volets'];
					$Radar_Bonus=$data['Radar'];
					$ManB=$data['ManoeuvreB'];
					$ManH=$data['ManoeuvreH'];
					$Mani=$data['Maniabilite'];
				}
				mysqli_free_result($resulta);
			}
			if($resultb)
			{
				while($datab=mysqli_fetch_array($resultb,MYSQLI_ASSOC))
				{
					$Latitude_b=$datab['Latitude'];
					$Longitude_b=$datab['Longitude'];
				}
				mysqli_free_result($resultb);
			}
			$moda=1;
			$ManAvion_lead=GetMano($ManH,$ManB,1,9999,$Mission_alt,$moda);
			$ManiAvion_lead=GetMani($Mani,1,9999,$moda);
			$PuissAvion_lead=GetPuissance("Avion",$Avion,$Mission_alt,9999,$moda,1,$Engine_Nbr);
			$VitAvion_lead=GetSpeed("Avion",$Avion,$Mission_alt,$meteo,$moda);
			$Distance=GetDistance(0,0,$Longitude_b,$Latitude_b,$Longitude_c,$Latitude_c);
			$chemin=$Distance[0];
			if($chemin <10)$chemin=10;
			$Mission_alt_min=$Mission_alt-1500-($meteo*2);
			$Mission_alt_max=$Mission_alt+3000+($meteo*2);
			$Couv_field="Couverture";
			UpdateData("Regiment_IA","Autonomie",-1,"ID",$Reg);
			include_once('./jfv_ground.inc.php');
			$mes.='Votre ordre de mission a été validé.';
			if($Type ==5)
			{
				$alerte_reco=false;
				$con=dbconnecti();
				$Patrol_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Cible='$Cible' AND j.Actif=1"),0);
				if($Patrol_Nbr >0 and !$Escorte_Nbr)
					$mes.="<br><b>La couverture de chasse ennemie empêche les avions de reconnaissance d'atteindre leur objectif!</b>";
				else
				{
                    $Flak_IA_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c,Pays as p 
                    WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND p.Faction<>'$Faction' 
                    AND c.Flak >0 AND c.Portee>='$Mission_alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5)"),0);
					if($Flak_PJ_Ground or $Flak_IA_Ground)
					{
						$Malus_Range=$Mission_alt/100;
                        if($Flak_IA_Ground)
						{
							$query="SELECT r.ID,r.Vehicule_ID,r.Officier_ID,r.Experience,r.Vehicule_Nbr,r.Skill,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment_IA as r,Cible as c,Pays as p 
							WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
							AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee >='$Mission_alt' AND r.Position IN(1,5) ORDER BY r.Experience DESC LIMIT 2";
							$Unit_table="Regiment_IA";
						}
						$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb-ok_dca');
						if($result)
						{
							$intro.='<b>La défense anti-aérienne ouvre le feu!</b>';
							//Boucle pièces
							$attaque=true;
							while($data=mysqli_fetch_array($result))
							{
								if($data['Arme_AA3'] >0 and $Mission_alt <1000)
									$DCA_ID=$data['Arme_AA3'];
								elseif($data['Arme_AA2'] >0 and $Mission_alt <4000)
									$DCA_ID=$data['Arme_AA2'];
								else
									$DCA_ID=$data['Arme_AA'];
								$DCA_Unit=$data['ID'];
								$DCA_EXP=$data['Experience'];	
								$DCA_Nbr=$data['Vehicule_Nbr'];
								$DCA_Vehicule_ID=$data['Vehicule_ID'];
								if($data['mobile'] ==5) //Navire
									$Range=GetData("Armes","ID",$DCA_ID,"Portee");
								else
									$Range=$data['Portee'];
								if($Range >$Mission_alt)$Malus_Range+=(($Range-$Mission_alt)/100);
								$dca_cal=round(GetData("Armes","ID",$DCA_ID,"Calibre"));
								if($dca_cal)
								{
									if($dca_cal >40 and $Mission_alt <501 and $Type_avion !=11)
										$intro.='<br>La défense anti-aérienne semble étrangement silencieuse!';
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
												UpdateData($Unit_table,"Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$DCA_Unit);
											elseif(!$Flak_IA_Ground)
												AddEvent("Avion",276,$Avion,0,$DCA_Unit,$Cible,$dca_mult,$DCA_ID);
											if($Mission_alt <501)
												$Detect=1;
											else
												$Detect=mt_rand(0,$DCA_EXP)+$meteo-$Malus_Range;
											//Trait Anti-aérien
											if($data['Skill'] ==30)
											{
												$Detect+=10;
												$Bonus_2passe=$DCA_EXP+50;
											}
											if($Detect >0)
											{			
												//DCA sur Formation
												if($DCA_Nbr >0 and $Avion_Nbr >0)
												{
													$Formation_abattue=0;
													$DCA_Shoots=min($DCA_Nbr,$Avion_Nbr);
													$DCA_dg=GetData("Armes","ID",$DCA_ID,"Degats");
                                                    $Tactique_dca=mt_rand(10,50);
                                                    $Pilotage_dca=mt_rand(10,50);
                                                    $Shoot_Dca=mt_rand(0,$DCA_EXP/2)+$dca_mult;
                                                    if($Mission_alt <5000 and $VitAvion_lead <$Shoot_Dca)$Shoot_Dca+=((5000-$Mission_alt)/50);
                                                    $Shoot=$Shoot_Dca+$meteo+($VisAvion/2)-($Mission_alt/100)-$Tactique_dca-$Pilotage_dca-($VitAvion_lead/10)+$Bonus_2passe;
                                                    if($Admin)$debug_intro.="<br>Shoot=".$Shoot." (+Shoot_Dca=".$Shoot_Dca.", -meteo=".$meteo.", +VisAvion/2=".$VisAvion.", -Mission_alt/100=".$Mission_alt.", -Malus_Range=".$Malus_Range.
                                                    ", -Tactique_dca=".$Tactique_dca.", -Pilotage_dca=".$Pilotage_dca.", -VitAvion_lead/10=".$VitAvion_lead.", +Bonus_2passe=".$Bonus_2passe;
                                                    if($Shoot >1)
                                                    {
                                                        $Degats=round((mt_rand(1,$DCA_dg)-pow($Blindage,2))*GetShoot($Shoot,$dca_mult));
                                                        //AddEvent("Avion",179,$Avion_dca,$Pilote_ia_dca,$Unite,$Cible,2,$Pays_eni);
                                                        if($Mission_alt <4500)$Degats+=ceil($VisAvion);
                                                        if($Degats >$HP_avion)
                                                        {
                                                            $intro.="<br>L'explosion d'un obus de DCA met le feu à l'avion!";
                                                            $Avion_Nbr=0;
                                                        }
                                                        else
                                                            $intro.="<br>L'explosion d'un obus de DCA endommage l'avion, mais il peut heureusement continuer sa mission!";
                                                        if($Admin)$intro.=" (Dégâts= ".$Degats.")";
                                                    }
                                                    else
                                                        $intro.="<br>La dca encadre l'avion sans le toucher, il peut continuer sa mission!";
												}
											}
											else
												$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas avoir repéré l'avion.";
										}
										else
											$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
									}//DCA silencieuse
								}
							}
							mysqli_free_result($result);
						}
					}
					if($Avion_Nbr >0)
					{
						$Malus_Reperer_reg=GetMalusReperer($Zone,0);
						$Bonus_Camera=0;
						if($meteo >-50)
						{
							include_once('./jfv_avions.inc.php');
							$Array_Mod=GetAmeliorations($Avion);
							if($Array_Mod[17] >5)
							{
								if($Array_Mod[17] ==27)
									$Bonus_Camera=75;
								elseif($Array_Mod[17] ==26)
								{
									if($Mission_alt <=6000)
										$Bonus_Camera=50;
								}
							}
							elseif($Array_Mod[16] >5)
							{
								if($Array_Mod[16] ==26)
								{
									if($Mission_alt <=6000)
										$Bonus_Camera=50;
								}
								elseif($Array_Mod[16] ==25)
								{
									if($Mission_alt <=1000)
										$Bonus_Camera=10;
								}
							}
						}
						//Unités sur place
						$pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Camouflage,r.Visible,r.Skill,c.Type,c.Taille,c.Detection,c.ID as navire FROM Regiment_IA as r,Cible as c,Pays as p 
						WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position<>25 AND r.Placement=8 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : reco-reg');
						if($pj_unit)
						{
							$nbr_units_pj=0;				
							while($data=mysqli_fetch_array($pj_unit))
							{
								if($data['ID'] >0)
								{
									if(!$data['Camouflage'])$data['Camouflage']=1;
									if($data['Skill'] ==29 or $data['Skill'] ==25 or $data['Skill'] ==6)$data['Camouflage']*=1.1;
									elseif($data['Skill'] ==126 or $data['Skill'] ==129 or $data['Skill'] ==51)$data['Camouflage']*=1.2;
									elseif($data['Skill'] ==127 or $data['Skill'] ==130 or $data['Skill'] ==80)$data['Camouflage']*=1.3;
									elseif($data['Skill'] ==128 or $data['Skill'] ==131 or $data['Skill'] ==81)$data['Camouflage']*=1.4;
									if($data['Matos'] ==11)$data['Camouflage']*=1.1;
									$Taille=$data['Taille']/$data['Camouflage'];
									if($data['Type'] ==4 or $data['Type'] ==9 or $data['Type'] ==11 or $data['Type'] ==12)
									{
										if($data['Skill']==81 and mt_rand(0,100)<25)
											$Taille=1;
										elseif($data['Skill']==80 and mt_rand(0,100)<20)
											$Taille=1;
										elseif($data['Skill']==51 and mt_rand(0,100)<15)
											$Taille=1;
										elseif($data['Skill']==6 and mt_rand(0,100)<10)
											$Taille=1;
									}
									if($Taille >=2)
									{
										$Shoot=mt_rand(0,$Vue_ailier)+($Stab/10)-$Malus_Reperer_reg+$Taille+($meteo*3)-($Mission_alt/10)+$Bonus_ailier+$Bonus_steady;
										$Photo_shoot=mt_rand(0,50)+$Bonus_Camera+($Stab/10)+($meteo*2)-($Mission_alt/100)-$Malus_Reperer_reg+$Bonus_steady+$Bonus_photo;
										if($Admin)$debug_intro.="<br>Shoot=".$Shoot." (+Vue_ailier=".$Vue_ailier.", -meteo*3=".$meteo.", +Taille=".$Taille.", -Malus_Reperer_reg=".$Malus_Reperer_reg.
										", -Mission_alt/10=".$Mission_alt." +Stab/10=".$Stab." +Bonus_ailier+Bonus_steady)
										<br>Photo_shoot=".$Photo_shoot." (+Rand=0,50; -meteo*2=".$meteo.", +Bonus_Camera=".$Bonus_Camera.", -Malus_Reperer_reg=".$Malus_Reperer_reg.
										", -Mission_alt/100=".$Mission_alt." +Stab/10=".$Stab." +Bonus_steady+Bonus_photo)";
										if($Shoot >1 or $Photo_shoot >1)
										{
											if(!$data['Visible']){
												SetData("Regiment_IA","Visible",1,"ID",$data['ID']);
												$nbr_units_pj++;
											}
											$icons_unites.="<br><img src='/images/vehicules/vehicule".$data['navire'].".gif'>";
										}
									}
								}
							}
							mysqli_free_result($pj_unit);
							if($Skill_ailier ==35)$skills.="<img src='images/skills/skill35.png'>";
							if($icons_unites)
								$mes.='<p><b>Unités repérées</b></p>'.$icons_unites;
							if($Zone ==6)
							{
								if($nbr_units_pj >0)
									$intro.="<br><b>Vous repérez au moins ".$nbr_units_pj." navire(s) ennemi(s)</b>";
								else
									$intro.="<br><b>Vous ne parvenez à identifier aucun navire ennemi</b>";
							}
							else
							{
								if($nbr_units_pj >0)
									$intro.="<br><b>Vous repérez au moins ".$nbr_units_pj." compagnies ennemies</b>";
								else
									$intro.="<br><b>Vous ne parvenez à identifier aucune unité ennemie</b>";
							}
						}
						else
							$intro.='<br>Vous ne détectez aucune cible digne d\'intérêt';
						if($alerte_reco)
						{
							include_once('./jfv_msg.inc.php');
							$off_alerte=array_unique($alerte_reco);
							$off_count=count($off_alerte);
							for($x=0;$x<$off_count-1;$x++) 
							{
								if($off_alerte[$x] >0)
									SendMsgOff($off_alerte[$x],0,"Une reconnaissance aérienne ennemie a été détectée dans les environs de ".$Nom_Lieu,"Rapport de reconnaissance",0,2);
							}
							unset($alerte_reco);
							unset($off_alerte);
						}
					}
					else
					{
						$mes.="<br><b>Tous les avions de reconnaissance ont été abattus ou refoulés! La mission est un échec!</b>";
						UpdateData("Regiment_IA","Avions",-1,"ID",$Reg);
					}
				}
			}
			elseif($Type ==29)
			{
				if($Credits >=4)
				{
					$con=dbconnecti();
					$Patrol_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Cible='$Cible' AND j.Actif=1"),0);
					if($Patrol_Nbr >0 and !$Escorte_Nbr)
						$mes.="<br><b>La couverture de chasse ennemie empêche les avions d'attaque d'atteindre leur objectif! Pour pouvoir passer la couverture, vous devez placer une escorte de chasse à l'altitude d'attaque.</b>";
					else
					{
						//DCA
						if(!$Flak_PJ_Ground)
						{
							$Flak_IA_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c,Pays as p 
							WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND p.Faction<>'$Faction' 
							AND c.Flak >0 AND c.Portee>='$Mission_alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5)"),0);
						}
						if($Flak_PJ_Ground or $Flak_IA_Ground)
						{
							$Malus_Range=$Mission_alt/100;
							if($Flak_IA_Ground)
							{
								$query="SELECT r.ID,r.Vehicule_ID,r.Officier_ID,r.Experience,r.Vehicule_Nbr,r.Skill,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment_IA as r,Cible as c,Pays as p 
								WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
								AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee >='$Mission_alt' AND r.Position IN(1,5) ORDER BY r.Experience DESC LIMIT 2";
								$Unit_table="Regiment_IA";
							}
							$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : asm-ok_dca');
							if($result)
							{
								$intro.="<b>La défense anti-aérienne ouvre le feu!</b>";
								//Boucle pièces
								$attaque=true;
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
									$Shoot_Dca=0;
									if($data['Arme_AA3'] >0 and $Mission_alt <1000)
										$DCA_ID=$data['Arme_AA3'];
									elseif($data['Arme_AA2'] >0 and $Mission_alt <4000)
										$DCA_ID=$data['Arme_AA2'];
									else
										$DCA_ID=$data['Arme_AA'];
									$DCA_Unit=$data['ID'];
									$DCA_EXP=$data['Experience'];	
									$DCA_Nbr=$data['Vehicule_Nbr'];
									$DCA_Vehicule_ID=$data['Vehicule_ID'];
									if($data['mobile'] ==5) //Navire
										$Range=GetData("Armes","ID",$DCA_ID,"Portee");
									else
										$Range=$data['Portee'];
									if($Range >$Mission_alt)
										$Malus_Range+=(($Range-$Mission_alt)/100);
									$dca_cal=round(GetData("Armes","ID",$DCA_ID,"Calibre"));
									if($dca_cal)
									{
										if($dca_cal >40 and $Mission_alt <501 and $Type_avion !=11)
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
													UpdateData($Unit_table,"Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$DCA_Unit);
												$Detect=mt_rand(0,$DCA_EXP)+$meteo-$Malus_Range;
												//Trait Anti-aérien
												if($data['Skill'] ==30)
												{
													$Detect+=10;
													$Bonus_2passe=$DCA_EXP+50;
												}
												if($Detect >0)
												{			
													//DCA sur Formation
													if($DCA_Nbr >0 and $Avion_Nbr >0)
													{
														$Formation_abattue=0;
                                                        $Tactique_dca=mt_rand(10,50);
                                                        $Pilotage_dca=mt_rand(10,50);
														$DCA_Shoots=min($DCA_Nbr,$Avion_Nbr);
														/*if($Mission_alt <5000 and $VitAvion_lead <$Shoot_Dca)
															$Shoot_Dca+=((5000-$Mission_alt)/50);*/
														$DCA_dg=GetData("Armes","ID",$DCA_ID,"Degats");
                                                        $Shoot_Dca=mt_rand(0,$DCA_EXP/2)+$dca_mult;
                                                        $Shoot=$Shoot_Dca+$meteo+$VisAvion-$Malus_Range-$Tactique_dca-$Pilotage_dca-($VitAvion_lead/20)+$Bonus_2passe;
                                                        if($Admin)$debug_intro.="<br>Shoot=".$Shoot." (+Shoot_Dca=".$Shoot_Dca.", -meteo=".$meteo.", +VisAvion=".$VisAvion.", -Malus_Range=".$Malus_Range.
                                                        ", -Tactique_dca=".$Tactique_dca.", -Pilotage_dca=".$Pilotage_dca.", -VitAvion_lead/20=".$VitAvion_lead.", +Bonus_2passe=".$Bonus_2passe;
                                                        if($Shoot >1)
                                                        {
                                                            $Degats=round((mt_rand(1,$DCA_dg)-$Blindage)*GetShoot($Shoot,$dca_mult));
                                                            //if($Mission_alt <4500)$Degats+=ceil($VisAvion);
                                                            if($Degats >$HP_avion){
                                                                $intro.="<br>L'explosion d'un obus de DCA met le feu à l'avion!";
                                                                $Avion_Nbr=0;
                                                            }
                                                            elseif($Degats >0)
                                                                $intro.="<br>L'explosion d'un obus de DCA endommage l'avion de, mais il peut heureusement continuer sa mission!";
                                                            else
                                                                $intro.="<br>Le blindage de l'avion de le protège des tirs ennemis, il peut continuer sa mission!";
                                                            if($Admin)$intro.="(Dégâts= ".$Degats.")";
                                                        }
                                                        else
                                                            $intro.="<br>La dca encadre l'avion sans le toucher, il peut continuer sa mission!";
													}
												}
												else
													$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas avoir repéré l'avion.";
											}
											else
												$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
										}//DCA silencieuse
									}
								}
								mysqli_free_result($result);
							}
						}
						if($Avion_Nbr >0)
						{
							$Avions_Bomb=$Avion_Nbr;
							$Malus_Reperer_reg=GetMalusReperer($Zone,0);
							$Bomb_Form=400;
							//Unités sur place
							$pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Placement,r.Position,r.Visible,c.Taille,c.Blindage_f,c.Blindage_l,c.Blindage_t,c.Blindage_a,c.Detection,c.mobile,
							r.HP,c.Nom,c.ID as Veh,c.HP as HP_max FROM Regiment_IA as r,Cible as c,Pays as p 
							WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position=25 AND r.Placement=8 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Bomb_IA=0") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : emia1-asm-reg');
							if($pj_unit)
							{
								while($data=mysqli_fetch_array($pj_unit))
								{
									if(mt_rand(0,$Vue_ailier)+$Radar_Bonus+$Bonus_asm+$Meteo+$data['Taille']-mt_rand(0,$data['Experience']) >0)
										$Detect_asm=true;
									else
										$Detect_asm=false;
									if(($data['Visible'] >0 or $Detect_asm) and $data['ID'] >0 and $Avions_Bomb >0)
									{
										$rand_tir=11;
										$Bombs_Hit=1;
										$def_c=0;
										if($data['HP'] >0)
											$HP_eni=$data['HP'];
										else
											$HP_eni=$data['HP_max'];
										$Esquive=$data['Blindage_f'];
										$def_c=$data['Blindage_f'];
										if(!$Bomb_ailier)$Bomb_ailier=25;
										$rand_tir=mt_rand(0,$Bomb_ailier);
										$Shoot=$rand_tir+($Stab/10)+$meteo-$Esquive-($Mission_alt/100)+$Bonus_ailier+$Bonus_steady+$Bonus_asm;
										$msg_hit="Un avion effectue un grenadage, ";
										if($data['Position'] ==8 and $Bonus_repere)$Shoot+=5;
										if($Admin)$debug_intro.="<br>Shoot ASM=".$Shoot." (+rand_tir=".$rand_tir.", -meteo=".$meteo.", -Esquive=".$Esquive.
										", +Stab/10=".$Stab.", -alt/100=".$Mission_alt."), def_c".$def_c;
										if($Shoot >0 or $rand_tir ==$Bomb_ailier)
										{
											$Degats=1;
											if($Bombs_Hit >0)
												$Degats+=(mt_rand(1,400)*20);
											else
												$msghit.='L\'attaque manque de précision!';
											if($Degats <1)$Degats=mt_rand(1,10);
											$HP_eni-=$Degats;
											if($HP_eni <1)
											{
												$Gain_Reput+=1;
												$Tues=1;
                                                if($data['Vehicule_Nbr'] ==1)
                                                    $query_reset_ia="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Position=8,HP=0,Fret=0,Fret_Qty=0,Visible=1,Bomb_IA=1 WHERE ID='".$data['ID']."'";
                                                else
                                                {
                                                    $HP_new=$data['HP_max'];
                                                    $Nbr_end=$data['Vehicule_Nbr']-1;
                                                    $query_reset_ia="UPDATE Regiment_IA SET Position=8,HP='$HP_new',Vehicule_Nbr='$Nbr_end',Visible=1,Bomb_IA=1,Mission_Lieu_D='$Cible',Mission_Type_D=7,Experience=Experience+1 WHERE ID='".$data['ID']."'";
                                                }
                                                $reset=mysqli_query($con,$query_reset_ia);;
                                                AddEventGround(707,$Avion,0,$data['ID'],$Cible,1,$data['Vehicule_ID']);
												$msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts. ".$Tues." <b>".$data['Nom']."</b> détruit!</p>";
											}
											else
											{
												//Dégats persistants grosses unités navales
												if($data['mobile'] ==5)
												{
													$Gain_Reput+=1;
													$DB='Regiment_IA';
													UpdateData($DB,"HP",-$Degats,"ID",$data['ID']);
													$HP_final=GetData($DB,"ID",$data['ID'],"HP");
													if($HP_final <1){
														$msghit="<p>".$msg_hit." , occasionnant ".round($Degats)." dégâts, achève le <b>".$data['Nom']."</b>!</p>";
														AddVictoire_atk("Avion",$data['ID'],$data['Vehicule_ID'],$Avion,0,$Unite,$Cible,$Bomb_Form,$data['Pays'],0,$Mission_alt,$Nuit,$Degats);
														$reset=mysqli_query($con,"UPDATE $DB SET Position=8,HP='$hp_ori',Vehicule_Nbr=Vehicule_Nbr-1,Bomb_IA=1,Visible=1,Experience=Experience+1 WHERE ID='".$data['ID']."'");;
													}
													else{
														$msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts et endommageant le <b>".$data['Nom']."</b>!</p>";
                                                        AddEventGround(709,$Avion,0,$data['ID'],$Cible,$Degats,$data['Vehicule_ID']);
                                                        $reset=mysqli_query($con,"UPDATE Regiment_IA SET Mission_Type_D=7,Mission_Lieu_D='$Cible',Bomb_IA=1,Visible=1,Experience=Experience+1 WHERE ID='".$data['ID']."'");;
													}
												}
												else
													$msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts, n'a pas détruit le <b>".$data['Nom']."!</p>";
											}
										}
										else
										{
											$Arme_txt='La charge de profondeur';
											if($Shoot <-100)
												$msghit="<p>".$Arme_txt." explose très loin à côté de la cible. Cette attaque est totalement manquée!</p>";
											elseif($Shoot <-50)
												$msghit="<p>".$Arme_txt." explose à côté de la cible. Cette attaque a manqué de précision!</p>";
											else
												$msghit="<p>".$Arme_txt." explose juste à côté de la cible. Quel manque de chance!</p>";
										}
										$mes.=$msghit;
									}
									$Avions_Bomb-=1;
								}
								mysqli_free_result($pj_unit);
								if(!$msg_hit)$mes.='<br>Vous ne détectez aucun sous-marin!';
							}
							else
								$mes.='<br>Vous ne détectez aucun sous-marin!';
						}
						else
						{
							$mes.='<br><b>Tous les avions d\'attaque ont été abattus ou refoulés! La mission est un échec!</b>';
							UpdateData("Regiment_IA","Avions",-1,"ID",$Reg);
						}
					}
					//echo "<h1>Ordre de mission</h1>".$intro.$mes;
					AddAtk_IA($Cible,0,$Avion_Nbr,$Avion,$Bomb_Form,$Mission_alt,0,$Cycle,$DCA_Nbr,$Escorte_Nbr,$Patrol_Nbr);
				}
			}
			mysqli_close($con);
			if($OfficierEMID >0 and $CT)
			{
				UpdateCarac($OfficierEMID,"Avancement",$CT,"Officier_em");
				UpdateData("Officier_em","Credits",-$CT,"ID",$OfficierEMID);
			}
		}
		$titre='Ordre de mission';
		$img=Afficher_Image('images/avions/mission'.$Avion.'.jpg','images/avions/vol'.$Avion.'.jpg')."<h2>Avion embarqué</h2>";
		$mes.="<br><a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour</a>";
		if($Admin)$skills.=$debug_strat;
	}
	else
		echo "<img src='images/top_secret.gif'>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
include_once('./index.php');