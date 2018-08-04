<?php
require_once './jfv_inc_sessions.php';
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0 xor $OfficierID >0)
{
	include_once './jfv_include.inc.php';
	include_once './jfv_txt.inc.php';
	include_once './jfv_combat.inc.php';
	include_once './jfv_ground.inc.php';
	$Action=Insec($_POST['Action']);
	$CT=Insec($_POST['CT']);
	$Veh=Insec($_POST['Veh']);
	$Reg=Insec($_POST['Reg']);
	if($Veh and $Reg)
	{
		if($OfficierID >0)$Credits=GetData("Officier","ID",$OfficierID,"Credits");
		if(!$Action or !$_SESSION['ground_bomb'])
		{
			echo 'Vous annulez votre action.';
			header("Location: ./index.php?view=ground_bat");
			//header("Location: ./index.php?view=ground_menu");
		}
		elseif($Credits >=$CT)
		{
			$debug=true;
			$country=$_SESSION['country'];
			$Heure=date('H');		
			$Reg_eni=strstr($Action,'_',true);	
			$Reg_eni_events=$Reg_eni;			
			$DB='Regiment_IA';
			if($Reg and $Reg_eni)
			{
				$Malus_dg=1;
				$Matos_mun=array(1,2,6,7,8);
				if($CT >1)
				{
					$Division=GetData("Officier","ID",$OfficierID,"Division");
					$Premium=GetData("Joueur","Officier",$_SESSION['AccountID'],"Premium");
					$DB_reg="Regiment";
					$Reg_a_ia=0;
					UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
				}
				else
				{
					$OfficierID=0;
					$DB_reg='Regiment_IA';
					$Reg_b_ia_rip=1;
					$Reg_a_ia=1;
					if($DB =='Regiment') //Dégâts diminués si IA tire sur PJ
						$Malus_dg=2;
					else
						$Malus_dg=1;
				}
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Vehicule_Nbr,Experience,Position,Lieu_ID,Muns,HP,Camouflage,Skill,Matos,Front FROM $DB_reg WHERE ID='$Reg'");
				$result2=mysqli_query($con,"SELECT Nom,Reput,Arme_Art,Arme_AT,Optics,Portee,Blindage_t,Taille,HP,Vitesse,mobile,Type,Arme_Art_mun,Fiabilite FROM Cible WHERE ID='$Veh'");
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Vehicule_Nbr=$data['Vehicule_Nbr'];
						$Reg_exp=$data['Experience'];
                        $Front_Reg=$data['Front'];
						$Pos=$data['Position'];
						$Lieu=$data['Lieu_ID'];
						$Matos=$data['Matos'];
						if(in_array($data['Matos'],$Matos_mun))
							$Muns=$data['Matos'];
						else
							$Muns=$data['Muns'];
						$HP_navire=$data['HP'];
						$Skill=$data['Skill'];
						if(!$data['Camouflage'])$data['Camouflage']=1;
						if($data['Skill'] ==29 or $data['Skill'] ==25 or $data['Skill'] ==6)$data['Camouflage']*=1.1;
						elseif($data['Skill'] ==126 or $data['Skill'] ==129 or $data['Skill'] ==51)$data['Camouflage']*=1.2;
						elseif($data['Skill'] ==127 or $data['Skill'] ==130 or $data['Skill'] ==80)$data['Camouflage']*=1.3;
						elseif($data['Skill'] ==128 or $data['Skill'] ==131 or $data['Skill'] ==81)$data['Camouflage']*=1.4;
						if($data['Matos'] ==11)$data['Camouflage']*=1.1;
						$Camouflage=$data['Camouflage'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				if($result2)
				{
					while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
					{
						$Veh_Nom=$data['Nom'];
						$Reput=$data['Reput'];
						$HP=$data['HP'];
						$Vitesse=$data['Vitesse'];
						$Arme_Art=$data['Arme_Art'];
						$Range_ori=$data['Portee'];
						$Blindage=$data['Blindage_t'];
						$Taille=$data['Taille']/$Camouflage;
						$mobile=$data['mobile'];
						$Type_v=$data['Type'];
                        if($Type_v == TYPE_VEH_AA or $Type_v == TYPE_DCA)
                            $Arme_Art=$data['Arme_AT'];
                        elseif($Type_v == TYPE_ART)
                            $Barrage = floor($data['Arme_Art_mun']+($data['Fiabilite']*2));
                        elseif($Type == TYPE_ART_MOB)
                            $Barrage = floor($data['Arme_Art_mun']/2)+($data['Fiabilite']*2);
                        $Optics=$data['Optics'];
						if($Matos ==9)$Optics+=5;
						elseif($Matos ==12)$Optics+=10;
						elseif($Matos ==10)$Vitesse*=1.1;
						elseif($Matos ==14)$Vitesse*=1.5;
						$Arme_Art_mun=floor($data['Arme_Art_mun']/10);
						if($mobile ==5)$Taille-=$Vitesse;
					}
					mysqli_free_result($result2);
					unset($data);
				}	
				if($mobile ==5) //navires
				{
					$HPi=$HP_navire;
					$Shoots=$Arme_Art_mun;
					if(!$CT)$Shoots=floor(sqrt($Shoots));
					if($Shoots <1)$Shoots=1;
				}
				else
				{
					if($CT >8)$Malus=$CT-8;
					$Shoots=$Vehicule_Nbr-$Malus;
				}
				$HP_ori=$HP;							
				/*Tir
				if($Type_v ==90) //Unités Pacifique
					$Range=2600;*/
				if($Arme_Art){
					$Range=GetData("Armes","ID",$Arme_Art,"Portee");
					if($Muns ==8)
						$Range/=2; //Portée limitée en cas d'obus HEAT
				}
				else
					$Range=0;
				if($Shoots >25)$Shoots=25; //Tirs limités
				if($Vehicule_Nbr >0 and $Range >2500 and $Shoots >0) //Conditions Bombardement
				{
					$Tir_base=floor(($Reg_exp/10)+10);
					$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
					$resultl=mysqli_query($con,"SELECT Zone,Flag,Meteo FROM Lieu WHERE ID='$Lieu'")
					or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_bomb-lieu');
					$result2=mysqli_query($con,"SELECT Pays,Vehicule_ID,Vehicule_Nbr,Officier_ID,Position,Placement,Experience,Move,Camouflage,HP,Fret,Fret_Qty,Skill,Matos FROM $DB WHERE ID='$Reg_eni'")
					or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_bomb-regeni');
					if($resultl)
					{
						while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC))
						{
							$Zone=$datal['Zone'];
							$Flag=$datal['Flag'];
							$Meteo=$datal['Meteo'];
						}
						mysqli_free_result($resultl);
						unset($datal);
					}				
					if($result2)
					{
						while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							$HP_eni_navire=$data['HP'];
							$Officier_eni=$data['Officier_ID'];
							$Pays_eni=$data['Pays'];
							$Vehicule=$data['Vehicule_ID'];
							$Veh_Nbr_eni=$data['Vehicule_Nbr'];
							$Pos_eni=$data['Position'];
							$Placement_eni=$data['Placement'];
							$Camouflage_eni=$data['Camouflage'];
							$Exp_eni=$data['Experience'];
							$Move=$data['Move'];
							$Fret_eni=$data['Fret'];
							$Fret_Qty_eni=$data['Fret_Qty'];
							$Skill_eni=$data['Skill'];
							$Matos_eni=$data['Matos'];
							/*if($Officier_eni >0)
							{
								
								$resulto=mysqli_query($con,"SELECT Trait,Avancement,Reputation,Transit FROM Officier WHERE ID='$Officier_eni'");
								if($resulto)
								{
									while($data=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
									{
										$Trait_eni=$data['Trait'];
										$Transit_eni=$data['Transit'];
										$Grade_eni=$data['Avancement'];
										$Level_eni=$data['Reputation'];
									}
									mysqli_free_result($resulto);
								}
								if($Grade_eni >$Level_eni)
									$Level_eni=$Grade_eni;
								$Max_Kill=$Veh_Nbr_eni/10000*$Level_eni;
								if($Trait_eni ==5)
									$Cam_bonus_eni=2;
								else
									$Cam_bonus_eni=1;
								if(IsSkill(100,$Officier_eni) and $Zone ==8)
									$Cam_bonus_eni+=1;
								if(IsSkill(9,$Officier_eni) and $Pos_eni ==2)
								{
									$Deception=true;
									$Deception_rate=25;
								}
								if(IsSkill(10,$Officier_eni))
									$Defense_elastique=true;
							}
							else
							{*/
								if($Pos_eni ==2)
								{
									if($Skill_eni ==87)
									{
										$Deception=true;
										$Deception_rate=20;
									}
									elseif($Skill_eni ==86)
									{
										$Deception=true;
										$Deception_rate=15;
									}
									elseif($Skill_eni ==54)
									{
										$Deception=true;
										$Deception_rate=10;
									}
									elseif($Skill_eni ==9)
									{
										$Deception=true;
										$Deception_rate=5;
									}
								}
								$Cam_bonus_eni=1;
								$Max_Kill=$Veh_Nbr_eni;
							//}
						}
						mysqli_free_result($result2);
						unset($data);
					}
					//Get Vehicule_eni
					$result=mysqli_query($con,"SELECT Nom,HP,Blindage_f,Blindage_t,Vitesse,Sol_meuble,Taille,mobile,Reput,Type,Categorie,Charge FROM Cible WHERE ID='$Vehicule'")
					or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_bomb-cveheni');
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Veh_Nom_eni=$data['Nom'];
							$HP_eni=$data['HP'];
							$Blindage_f_eni=$data['Blindage_f'];
							$Blindage_eni=$data['Blindage_t'];
							$Vitesse_eni=$data['Vitesse'];
							$Sol_meuble_eni=$data['Sol_meuble'];
							$Taille_eni=$data['Taille'];
							$mobile_eni=$data['mobile'];
							$Reput_eni=$data['Reput'];
							$Type_eni=$data['Type'];
							$Categorie_eni=$data['Categorie'];
							$Charge_eni=$data['Charge'];
						}
						mysqli_free_result($result);
						unset($data);
					}
					if($Skill_eni ==29)$Camouflage_eni*=1.1;
					elseif($Skill_eni ==126)$Camouflage_eni*=1.2;
					elseif($Skill_eni ==127)$Camouflage_eni*=1.3;
					elseif($Skill_eni ==128)$Camouflage_eni*=1.4;
					elseif($Skill_eni ==25)$Camouflage_eni*=1.1;
					if($Matos_eni ==11)$Camouflage_eni*=1.1;
					$Cam_eni=$Taille_eni/$Camouflage_eni; ///$Cam_bonus_eni;
					if($mobile_eni ==3 and ($Pos_eni ==2 or $Pos_eni ==3 or $Pos_eni ==10))
					{
						if($Skill_eni) //!$Officier_eni and 
						{
							if($Skill_eni ==98)
							{
								$Pente_inverse=true;
								$Pente_inverse_rate=0.6;
							}
							elseif($Skill_eni ==97)
							{
								$Pente_inverse=true;
								$Pente_inverse_rate=0.7;
							}
							elseif($Skill_eni ==59)
							{
								$Pente_inverse=true;
								$Pente_inverse_rate=0.8;
							}
							elseif($Skill_eni ==14)
							{
								$Pente_inverse=true;
								$Pente_inverse_rate=0.9;
							}
						}
						/*elseif($Officier_eni >0 and IsSkill(14,$Officier_eni))
						{
							$Pente_inverse=true;
							$Pente_inverse_rate=0.5;
						}*/
					}
					if($mobile_eni ==5)
					{
						if($HP_eni)
							$hp_good=round(($HP_eni_navire/$HP_eni)*100);
						else
							$hp_good=0;
						$HP_eni=$HP_eni_navire;
						if($Type_eni >17)
						{
							if($DB =="Regiment_IA")
								$HP_ori_eni=0;
							else
								$HP_ori_eni=$HP_eni_navire;
						}
						$Esquive=mt_rand(0,$Exp_eni);
						if($mobile !=5)$Blindage_eni*=20;
					}
					else
					{
						$Esquive=(($Exp_eni/10)+10);
						$HP_ori_eni=$HP_eni;
						if($Categorie_eni ==8)
							$Esquive*=2;
					}
					//Tir
					$Arme=$Arme_Art;
					if($Arme >0 and $Arme !=82 and $Muns !=7)
					{
						$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
						if($resulta)
						{
							while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
							{
								$Arme_Cal=round($data3['Calibre']);
								$Arme_Multi=$data3['Multi'];
								$Arme_Nom=$data3['Nom'];
								$Arme_Dg=$data3['Degats'];
								$Arme_Perf=$data3['Perf'];
								$Arme_Portee=$data3['Portee'];
								$Arme_Portee_Max=$data3['Portee_max'];
							}
							mysqli_free_result($resulta);
						}
						if($DB_reg =="Regiment")
							$Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Munitions_".$Arme_Cal);
						else
							$Muns_Stock=9999;
						$Muns_Conso=$Shoots*$Arme_Multi;
						if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0) //Check Munitions suffisantes pour le tir
						{
							if($DB_reg =="Regiment")
								UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg);
							/*if(!$Move)
								$Vitesse_eni=0;
							else*/
								$Vitesse_eni=Get_LandSpeed($Vitesse_eni,$mobile_eni,$Zone,$Pos_eni,$Type_eni,$hp_good,$Sol_meuble_eni);
							if($Matos_eni ==10)$Vitesse_eni*=1.1;
							elseif($Matos_eni ==14)$Vitesse_eni*=1.5;
                            elseif($Matos_eni ==30)$Vitesse_eni/=1.25;
							if($Placement_eni ==0 and $Flag ==$Pays_eni)
							{
								$Fort=GetData("Lieu","ID",$Lieu,"Fortification");
								if($Fort >0)
									$Blindage_eni+=Get_Blindage($Zone,$Cam_eni,$Fort,$Pos_eni);
							}
							if($Pos_eni ==2 and !$Fort)
								$Blindage_eni+=Get_Blindage($Zone,$Cam_eni,0,2);
							if($Zone ==6){
								$Tir_base+=10; 
								$Vitesse_eni/=2;
							}
							else
								$Vitesse_eni*=2;
							if($Skill ==38)
								$Optics*=1.1;
							elseif($Skill ==153)
								$Optics*=1.15;
							elseif($Skill ==154)
								$Optics*=1.2;
							elseif($Skill ==155)
								$Optics*=1.25;
							$msg.="<br>Votre unité tire à l'aide de son ".$Arme_Nom;
							$skills="<h2>Premium</h2><div style='width:25%;'>";
							$Update_Nbr_eni=0;
							$Update_Reput=0;
							$Update_xp=0;
							$Update_Moral_eni=0;
							for($t=1;$t<=$Shoots;$t++) //Boucle Tirs
							{
								$Degats=0;
								if($Veh_Nbr_eni >0 and $Update_Nbr_eni >-$Max_Kill)
								{
									$Tir=mt_rand(0,$Tir_base);
									$Shoot=$Tir+$Cam_eni-$Vitesse_eni-mt_rand(0,$Esquive)+$Meteo+$Optics;
									if($Deception and mt_rand(0,100)<$Deception_rate){
									    $Shoot=0;
									    $Tir=0;
                                    }
                                    /**
                                     * 23/09/17
                                     */
                                    if($country ==2 or $country ==4){
                                        $Shoot-=20;
                                    }
									if($Premium)
									{
										$pc_score=(300+$Shoot)/6;
										$Bar_pc=round($pc_score,1,PHP_ROUND_HALF_DOWN);
										if($Shoot >0)
											$skills.="Efficacité de votre ".$t."e bombardement<br><div class='progress'><div class='progress-bar-success' role='progressbar' aria-valuenow='".$pc_score."' aria-valuemin='0' aria-valuemax='100' style='width: ".$pc_score."%'>".$Bar_pc."%</div></div>";
										else
											$skills.="Efficacité de votre ".$t."e bombardement<br><div class='progress'><div class='progress-bar-danger' role='progressbar' aria-valuenow='".$pc_score."' aria-valuemin='0' aria-valuemax='100' style='width: ".$pc_score."%'>".$Bar_pc."%</div></div>";
									}
									else
										$skills.="Efficacité de votre ".$t."e bombardement<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100' style='width:100%'>?%</div></div>";
									if($debug)$msg_debug.="<br>[DEBUG TIR] : Shoot=".$Shoot." (".$Tir."/".$Tir_base.") (+Taille ".$Cam_eni.", - Vit ".$Vitesse_eni." - Esquive (rand_EXP si naval) ".$Esquive." - Météo ".$Meteo.")";
									if($Shoot >0 or ($Tir >0 and $Tir >=$Tir_base))
									{
										$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg);
										if($Tir >=$Tir_base)
											$Base_Dg=$Arme_Dg-mt_rand(0,10);
										if($mobile_eni ==5)
											$Degats=($Base_Dg-pow($Blindage_eni,2))*GetShoot($Shoot,$Arme_Multi);
										else
											$Degats=($Base_Dg-$Blindage_eni)*GetShoot($Shoot,$Arme_Multi);
										$Degats=Get_Dmg($Muns,$Arme_Cal,$Blindage_eni,$Range,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max)/$Malus_dg;
										if($Blindage_eni >0 and $Vitesse_eni >10)
											$Degats/=2;										
										elseif($Pos_eni ==2 and $Cam_eni <2)
											$Degats/=2;
										elseif($Pos_eni ==1 and $Defense_elastique and ($mobile_eni ==1 or $mobile_eni ==2 or $mobile_eni ==6 or $mobile_eni ==7))
										{
											if($Trait_eni ==3)
												$Degats/=4;
											else
												$Degats/=2;
										}
										if($Pente_inverse)$Degats*=$Pente_inverse_rate;
										if($Skill ==38)
											$Degats*=1.1;
										elseif($Skill ==153)
											$Degats*=1.15;
										elseif($Skill ==154)
											$Degats*=1.2;
										elseif($Skill ==155)
											$Degats*=1.25;
										if($Degats >=$HP_eni)
										{
											if($Charge_eni and $Fret_eni >0)
											{
												if($Fret_eni ==888)
													UpdateData("Pays","Special_Score",-1,"ID",$Pays_eni);
												elseif($Fret_eni ==200 and $Fret_Qty_eni >0)
												{
													$reset=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0,HP=0,Position=6,Arti_IA=1 WHERE ID='$Reg_eni'");
													$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Moral=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Experience=0,Skill=0,Visible=0,Arti_IA=1 WHERE ID='$Fret_Qty_eni'");
												}
												/*if($Officier_eni)
												{													
													$Perte_Stock=$Fret_Qty_eni/$Veh_Nbr_eni;
													UpdateData("Regiment","Fret_Qty",-$Perte_Stock,"ID",$Reg_eni);
												}*/
											}
											if($Categorie_eni ==5 or $Categorie_eni ==6)
											{
												$Collateral=1+(floor($Degats/$HP_eni/10));
												if($Arme >186 and $Arme <192) //Katyusha
												{
													$Collateral*=2;
													$Update_Moral_eni+=1;
												}
												if($Collateral >50)$Collateral=50;
											}
											elseif($mobile ==5)
											{
												if($Degats >$HP_eni*3)
													$Collateral=3;
												elseif($Degats >$HP_eni*2)
													$Collateral=2;
											}
											else
												$Collateral=1;
											$Update_Nbr_eni-=$Collateral;
											$Update_Reput+=$Reput_eni;
											$msg.="<br>Votre unité touche la cible et lui occasionne <b>".floor($Degats)."</b> dégâts!<br>La cible est détruite!";
											$HP_eni=$HP_ori_eni;
											$Veh_Nbr_eni-=$Collateral;
											if($mobile_eni ==5)
											{
												SetData($DB,"HP",$HP_ori_eni,"ID",$Reg_eni);
												if($Transit_eni >0)
												{
													$reset=mysqli_query($con,"UPDATE Regiment SET Experience=0,Skill=0,Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
													Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
													Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Visible=0,Arti_IA=1 WHERE Officier_ID='$Transit_eni' AND Vehicule_Nbr >0 ORDER BY RAND() LIMIT 1");
												}
												if($Pos_eni ==20) //formation dispersée
													break;
											}
										}
										elseif($Degats >0)
										{
											$Update_xp+=1;
											$msg.="<br>Votre unité touche la cible et lui occasionne <b>".floor($Degats)."</b> dégâts!";
											$HP_eni-=$Degats;
											if($mobile_eni ==5)
												UpdateData($DB,"HP",-$Degats,"ID",$Reg_eni);
											elseif($Categorie_eni ==5 and $Arme >186 and $Arme <192)//Katyusha
												$Update_Moral_eni+=1;
										}
										else
											$msg.="<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
									}
									else
										$msg.="<br>Votre unité rate la cible!";
								}
								else
								{
									if($Officier_eni >0)
									{
										$Exp_final_eni=0;
										/*if(IsSkill(29,$Officier_eni))
										{
											$Front=GetData("Officier","ID",$Officier_eni,"Front");
											$Latitude=GetData("Lieu","ID",$Lieu,"Latitude");
											$Retraite=Get_Retraite($Front,$country,$Latitude);
											SetData("Regiment","Lieu_ID",$Retraite,"ID",$Officier_eni);
										}
										elseif(IsSkill(6,$Officier_eni) and $mobile_eni !=5)
											SetData("Regiment","Position",2,"ID",$Officier_eni);*/
										if($Trait_eni ==11)
										{
											$Exp_final_eni=$Exp_eni;
											if($Exp_final_eni >100)
												$Exp_final_eni=100;
										}
										$reset=mysqli_query($con,"UPDATE Regiment SET Experience='$Exp_final_eni',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
										Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
										Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Arti_IA=1 WHERE ID='$Reg_eni'");
										$reset2=mysqli_query($con,"UPDATE Regiment SET Visible=0 WHERE Officier_ID='$Officier_eni'");
										if($Level_eni >10000)
										{
											$Malus_Reput=($Level_eni/1000)+$Reput_eni;
											UpdateData("Officier","Reputation",-$Malus_Reput,"ID",$Officier_eni);
										}
									}
									$msg_eni.="<br>L'ennemi est totalement en déroute!";
									break;
								}
							}
							if($mobile_eni !=5) //Bomb Terrestre
							{
								if($Pos_eni !=11 and $Vehicule_Nbr >=$Veh_Nbr_eni)
									SetData($DB,"Position",8,"ID",$Reg_eni);
								if($Pos_eni !=8 and $Pos_eni !=9)
									AddEventGround(431,$Vehicule,$OfficierID,$Reg_eni_events,$Lieu,1,$Reg);
								//% d'immobiliser l'unité
                                if(mt_rand(0,100) <= $Barrage)
                                    SetData($DB,"Move",1,"ID",$Reg_eni);
                                if($Update_Moral_eni and $Officier_eni >0)
								{
									$Perte_moral=0-$Vehicule_Nbr-$Update_Moral_eni;
									UpdateData("Regiment","Moral",$Perte_moral,"ID",$Reg_eni);
								}
							}
							if($Update_Nbr_eni <0)
							{
								$Placement_eni=GetData($DB,"ID",$Reg_eni,"Placement");
								UpdateData($DB,"Vehicule_Nbr",$Update_Nbr_eni,"ID",$Reg_eni);
								UpdateData($DB_reg,"Moral",-$Update_Nbr_eni,"ID",$Reg);
								if($Officier_eni >0)
								{
									$Reg_b_ia=0;
									if($DB_reg =="Regiment")
										AddEventGround(405,$Vehicule,$OfficierID,$Reg_eni_events,$Lieu,-$Update_Nbr_eni,$Reg);
									else
										AddEventGround(605,$Vehicule,$OfficierID,$Reg_eni_events,$Lieu,-$Update_Nbr_eni,$Reg);
									SetData("Regiment","Arti_IA",1,"ID",$Reg_eni);
									UpdateData("Regiment","Moral",$Update_Nbr_eni,"ID",$Reg_eni);
								}
								else
								{
									AddEventGround(505,$Vehicule,$OfficierID,$Reg_eni_events,$Lieu,-$Update_Nbr_eni,$Reg);
									$Reg_b_ia=1;
								}
								AddGroundAtk($Reg,$Reg_eni_events,$Veh,$Vehicule_Nbr,$Vehicule,$Veh_Nbr_eni,$Pos,$Pos_eni,$Lieu,$Placement_eni,$Range,-$Update_Nbr_eni,$Reg_a_ia,$Reg_b_ia);
								if($Pos_eni ==1)SetData($DB,"Visible",0,"ID",$Reg_eni);
							}
							if($OfficierID >0)
							{
								if($Update_Reput and $Pays_eni !=$country and $DB_reg =="Regiment")
								{
									if(GetData("Officier","ID",$OfficierID,"Trait") ==1)
										$Update_Reput*=2;
									if($Division >0 and GetData("Division","ID",$Division,"hatk") ==$Heure and $Lieu ==GetData("Division","ID",$Division,"atk"))
										$Update_Reput*=2;			
									UpdateData("Regiment","Experience",$Update_Reput,"ID",$Reg);
									UpdateData("Officier","Avancement",$Update_Reput,"ID",$OfficierID);
									UpdateData("Officier","Reputation",$Update_Reput,"ID",$OfficierID);
								}
								elseif($Update_Reput and $Pays_eni ==$country and $DB_reg =="Regiment")
								{
									UpdateData("Officier","Avancement",-$Update_Reput,"ID",$OfficierID);
									UpdateData("Officier","Reputation",-$Update_Reput,"ID",$OfficierID);
								}
								if($Update_xp and $Pays_eni !=$country)
								{
									UpdateData("Regiment","Experience",$Update_xp,"ID",$Reg);
									UpdateData("Officier","Avancement",$Update_xp,"ID",$OfficierID);
									UpdateData("Officier","Reputation",$Update_xp,"ID",$OfficierID);
									if($Degats >0)
										AddEventGround(455,$Vehicule,$OfficierID,$Reg_eni_events,$Lieu,$Degats,$Reg);
								}
							}
							elseif($Update_xp and $Pays_eni !=$country and $Degats >0)
								AddEventGround(465,$Vehicule,$OfficierID,$Reg_eni_events,$Lieu,$Degats,$Reg);
							$skills.="</div>";
						}
						else
							$msg.="<br>Votre unité annule son attaque, faute de munitions!";
						if($Pos_eni ==8 and $Zone !=6)
							SetData($DB,"Position",9,"ID",$Reg_eni);
						elseif(($mobile_eni ==1 or $mobile_eni ==6) and ($Pos_eni ==4 or $Pos_eni ==0))
							SetData($DB,"Position",1,"ID",$Reg_eni);
						elseif(($mobile_eni ==1 or $mobile_eni ==3) and ($Pos_eni ==4 or $Pos_eni ==0))
							SetData($DB,"Position",2,"ID",$Reg_eni);
						//***Contre-batterie***//
						if($Zone ==6)
							$Range_chk=$Range_ori;
						else
							$Range_chk=$Range_ori/2;
						if($mobile ==5)
							$query_add=" ORDER BY RAND() LIMIT 2";
						$query_pj_unit="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Matos,r.Officier_ID,r.Skill,c.Arme_Art,c.Arme_AT,c.Portee,c.Arme_Art_mun,c.mobile FROM Regiment_IA as r,Cible as c,Pays as p 
						WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND c.Arme_Art >0 AND r.Position IN (3,5,23) AND c.Portee >2500 AND c.Charge=0".$query_add;
						/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Matos,r.Officier_ID,r.Skill,c.Arme_Art,c.Arme_AT,c.Portee,c.Arme_Art_mun,c.mobile FROM Regiment as r,Cible as c,Pays as p 
						WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND c.Arme_Art >0 AND r.Position IN (3,5,23) AND c.Portee >2500 AND c.Charge=0) UNION (*/
						$pj_unit=mysqli_query($con,$query_pj_unit);
						if($pj_unit)
						{
							$Update_XP_eni=0;
							while($data=mysqli_fetch_array($pj_unit))
							{
								$EXP_cb=$data['Experience'];
								if($data['Skill'] ==73)
									$data['Portee']*=1.25;
								elseif($data['Skill'] ==72)
									$data['Portee']*=1.2;
								elseif($data['Skill'] ==47)
									$data['Portee']*=1.15;
								elseif($data['Skill'] ==15)
									$data['Portee']*=1.1;
								if($mobile ==5 and $data['mobile'] !=5)$data['Portee']/=2;
								if($data['Portee']+($EXP_cb*2) >=$Range_chk/2) //Chk Portée
								{
									$Arme_Art_r=$data['Arme_Art'];
									$Arme_AT_r=$data['Arme_AT'];								
									if($Blindage >0 and $Arme_AT_r and $mobile !=5)
										$Arme=$Arme_AT_r;
									elseif($Arme_Art_r)
										$Arme=$Arme_Art_r;
									$resulta=mysqli_query($con,"SELECT Nom,Calibre,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
									if($resulta)
									{
										while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
										{
											$Arme_Cal=round($data3['Calibre']);
											$Arme_Dg=$data3['Degats'];
											$Arme_Perf=$data3['Perf'];
											$Arme_Portee=$data3['Portee'];
											$Arme_Portee_Max=$data3['Portee_max'];
										}
										mysqli_free_result($resulta);
									}
									$Muns_Conso=$data['Vehicule_Nbr'];
									if($data['Officier_ID'] >0)
									{
										$Reg_eni_r=$data['ID'];
										$Muns_Stock=GetData("Regiment","ID",$Reg_eni_r,"Stock_Munitions_".$Arme_Cal);
										$Reg_a_ia_rip=false;
										if($data['mobile'] ==5)
											$Shoots_cb=floor($data['Arme_Art_mun']/10);
										else
											$Shoots_cb=$data['Vehicule_Nbr'];
									}
									else
									{
										$Reg_eni_r=$data['ID']; //0
										$Muns_Stock=9999;
										$Reg_a_ia_rip=true;
										if($data['mobile'] ==5)
										{
											$Shoots_cb=floor($data['Arme_Art_mun']/10);
											$Shoots_cb=floor(sqrt($Shoots_cb));
										}
										else
											$Shoots_cb=1;
									}
									if($data['Skill'] ==109)
										$Shoots_cb+=4;
									elseif($data['Skill'] ==108)
										$Shoots_cb+=3;
									elseif($data['Skill'] ==65)
										$Shoots_cb+=2;
									elseif($data['Skill'] ==22)
										$Shoots_cb+=1;
									elseif($data['Skill'] ==113)
										$Suppression=25;
									elseif($data['Skill'] ==112)
										$Suppression=20;
									elseif($data['Skill'] ==67)
										$Suppression=15;
									elseif($data['Skill'] ==28)
										$Suppression=10;
									elseif($data['Skill'] ==105)
										$Deploy_speed=25;
									elseif($data['Skill'] ==104)
										$Deploy_speed=20;
									elseif($data['Skill'] ==63)
										$Deploy_speed=15;
									elseif($data['Skill'] ==20)
										$Deploy_speed=10;
									if($Shoots_cb <1)$Shoots_cb=1;
									if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
									{
										$msg.="<p>Vous subissez un tir de contre-artillerie de la part de l'ennemi !";
										if($data['Officier_ID'] >0)UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg_eni_r);
										$Update_Nbr=0;
										$EXP_cb=($EXP_cb/10)+10;
										for($t=1;$t<=$Shoots_cb;$t++)
										{
											if($Deploy_speed and mt_rand(0,100)<$Deploy_speed)
												$msg.="<br>La compétence <b>Déploiement rapide</b> permet à l'unité d'éviter le tir de contre-artillerie!";
											else
											{
												$Tir=mt_rand(0,$EXP_cb);
												if($data['Skill'] ==38)
													$Tir+=mt_rand(0,10);
												elseif($data['Skill'] ==153)
													$Tir+=mt_rand(0,15);
												elseif($data['Skill']==154)
													$Tir+=mt_rand(0,20);
												elseif($data['Skill'] ==155)
													$Tir+=mt_rand(0,25);
												if($mobile ==5)
													$Shoot=$Tir+$Meteo+$Taille-$Vitesse-mt_rand(0,$Reg_exp);
												else
													$Shoot=$Tir+$Meteo+$Taille-$Vitesse-mt_rand(0,$Tir_base);
												if($debug)$msg_debug.="<br>[DEBUG RIPOSTE] : Shoot=".$Shoot." (".$Tir."/".$EXP_cb.") (+Taille ".$Taille.", - Vitesse ".$Vitesse.", - Meteo ".$Meteo." - rand_EXP. ".$Reg_exp.")";
												if($Shoot >1 or $Tir ==$EXP_cb)
												{
													if(in_array($data['Matos'],$Matos_mun))
														$Munition=$data['Matos'];
													else
														$Munition=$data['Muns'];
													//if($mobile ==5)
														$Degats=(mt_rand($Arme_Cal,$Arme_Dg)-pow($Blindage,2));
													/*else
														$Degats=$Arme_Cal-$Blindage;*/
													$Degats=Get_Dmg($Munition,$Arme_Cal,$Blindage,$data['Portee'],$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max);
													if($data['Position'] ==23)
													{
														if($data['Skill'] ==41)
															$Degats*=1.1;
														elseif($data['Skill'] ==162)
															$Degats*=1.15;
														elseif($data['Skill'] ==163)
															$Degats*=1.2;
														elseif($data['Skill'] ==164)
															$Degats*=1.25;
													}
													if($Degats <1)$Degats=1;
													$HP-=round($Degats);
													if($HP <1)
													{
														$msg.='<br><b>Le tir ennemi ('.$Reg_eni_r.'e Cie) détruit une de vos unités. ('.round($Degats).' points de dégats!)</b>';
														$Update_Nbr-=1;
														$Veh_Nbr-=1;
														$HP=$HP_ori;
														if($mobile ==5 and $Veh_Nbr >1)
														    SetData($DB_reg,"HP",$HP,"ID",$Reg);
														else
                                                            SetData($DB_reg,"HP",0,"ID",$Reg);
														$Update_XP_eni+=$Reput;
														if($Suppression and mt_rand(0,100)<$Suppression)$Sous_le_feu=true;
													}
													elseif($Degats >1)
													{
														$msg.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.round($Degats).'</b> points de dégats!';
														$Update_XP_eni+=1;
														AddEventGround(450,$Veh,$OfficierID,$Reg,$Lieu,$Degats,$Reg_eni_r);
														if($mobile ==5)UpdateData($DB_reg,"HP",-$Degats,"ID",$Reg);
													}
													else
														$msg.='<br>Le tir ennemi ne perce pas le blindage de votre unité';
												}
												else
													$msg.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
											}
										}
										$msg.='</p>';
										if($Update_Nbr <0)
										{
											UpdateData($DB_reg,"Vehicule_Nbr",$Update_Nbr,"ID",$Reg);
											if($OfficierID >0)
											{
												if($DB =="Regiment")
													AddEventGround(415,$data['Vehicule_ID'],$OfficierID,$Reg,$Lieu,-$Update_Nbr,$Reg_eni_r);
												else
													AddEventGround(615,$data['Vehicule_ID'],$OfficierID,$Reg,$Lieu,-$Update_Nbr,$Reg_eni_r);
											}
											else
												AddEventGround(515,$data['Vehicule_ID'],$Officier_Eni,$Reg,$Lieu,-$Update_Nbr,$Reg_eni_r);
											AddGroundAtk($Reg_eni_r,$Reg,$data['Vehicule_ID'],$data['Vehicule_Nbr'],$Veh,$Vehicule_Nbr,$data['Position'],$Pos,$Lieu,$data['Placement'],$Range,-$Update_Nbr,$Reg_a_ia_rip,$Reg_b_ia_rip);
										}
									}
									else
										$msg.="<br>L'ennemi ne riposte pas à votre tir!";
								}
								else
									$msg.="<br>L'ennemi, hors de portée, ne riposte pas à votre tir!";
								if($Update_XP_eni and !$Reg_a_ia_rip)
									UpdateData("Regiment","Experience",$Update_XP_eni,"ID",$Reg_eni_r);
							}
							mysqli_free_result($pj_unit);
							unset($data);
						}						
						if($Vehicule_Nbr <1)
						{
							if($OfficierID >0)
							{
								if(!$Reg_exp)$Reg_exp=GetData("Regiment","ID",$Reg,"Experience");
								$Reputation=GetData("Officier","ID",$OfficierID,"Reputation");
								$Exp_final=0;
								if(GetData("Officier","ID",$OfficierID,"Trait") ==11)
								{
									$Exp_final=$Reg_exp;
									if($Exp_final >100)
										$Exp_final=100;
								}
								//$con=dbconnecti();
								$reset=mysqli_query($con,"UPDATE Regiment SET Experience='$Exp_final',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
								Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
								Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg'");
								//mysqli_close($con);
								if($Reputation >10000)
								{
									$Malus_Reput=($Reputation/1000)+$Reput;
									UpdateData("Officier","Reputation",-$Malus_Reput,"ID",$OfficierID);
								}
							}
							$msg.='<br>Votre unité est totalement en déroute!';
						}
						$reset=mysqli_query($con,"UPDATE $DB_reg SET Visible=1,Experience=Experience+1,Combats=Combats+1,Atk=1,Atk_time=NOW(),Position=0 WHERE ID='$Reg'");
						$reseteni=mysqli_query($con,"UPDATE $DB SET Experience=Experience+1,Combats=Combats+1,Arti_IA=1 WHERE ID='$Reg_eni'");
						$titre='Bombardement';
						$mes="<table class='table table-striped'>
						<thead><tr><td></td><td><img src='images/vehicules/vehicule".$Veh.".gif'></td><td><img src='images/vehicules/vehicule".$Vehicule.".gif'></td></tr></thead>
						<tr><td></td><td><img src='images/".$country."20.gif'></td><td><img src='images/".$Pays_eni."20.gif'></td></tr>
						<tr><th align='left'>Terrain</th><td colspan='2'><img src='images/zone".$Zone.".jpg'></td></tr>
						<tr><th align='left'>Position</th><td>".GetPosGr($Pos)."</td><td>".GetPosGr($Pos_eni)."</td></tr>
						<tr><th align='left'>Expérience</th><td>".$Reg_exp."</td><td>".$Exp_eni."</td></tr>
						<tr><td></td><td align='left'>".$msg."</td><td align='left'>".$msg_eni."</td></tr>
						<tr><th>Pertes</th><td>".$Update_Nbr."</td><td>".$Update_Nbr_eni."</td></tr>
						</table>";
						if($OfficierEMID >0 and !$OfficierID)
						{
							UpdateData("Officier_em","Reputation",10,"ID",$OfficierEMID);
							$menu="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
						}
						if($Update_XP or $Update_Reput or $Update_XP_eni)
							mail(EMAIL_LOG,"Aube des Aigles: Combat : Bombardement","Officier/EM : ".$OfficierID."/".$OfficierEMID." dans les environs de : ".$Lieu."<br>Attaque de ".$Veh_Nom." sur ".$Veh_Nom_eni." <html>".$mes.$msg_debug."</html>", "Content-type: text/html; charset=utf-8");
						$_SESSION['ground_bomb']=false;
						include_once './default.php';
					}
					else
						echo "<h6>Votre unité ne dispose pas d'un armement approprié!</h6>";
				}
				elseif($Range <2500)
				{
					$titre='Bombardement annulé';
					$img="<img src='images/congestion".$country.".jpg'>";
					$mes="L'arme de votre unité n'a pas la portée suffisante! (".$Range."/2500)";
					include_once './default.php';
				}
				else
				{
					$titre='Bombardement annulé';
					$img="<img src='images/congestion".$country.".jpg'>";
					$mes="Un trop grand nombre d'unités occupent cette zone, vous empêchant d'effectuer votre action!";
					include_once './default.php';
				}
			}
			else
				echo '<h6>ERREUR : Aucune unité sélectionnée !</h6>';
		}
		else
			echo '<h6>Pas assez de crédits!</h6>';
	}
	else
		echo 'Tsss';
}