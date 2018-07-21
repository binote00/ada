<?php
require_once './jfv_inc_sessions.php';
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 xor $OfficierEMID >0)
{
	include_once './jfv_include.inc.php';
	$Action=Insec($_POST['Action']);
	$CT=Insec($_POST['CT']);
	if($OfficierID >0)
		$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	elseif($OfficierEMID >0)
		$Credits=GetData("Officier_em","ID",$OfficierEMID,"Credits");
	if(!$CT)
		$CT=12;
	elseif($CT ==98)
	{
		$CT=0;
		$DB="Regiment_IA";
		$Reg_a_ia=true;
	}
	elseif($CT ==99)
		$CT=0;
	/*if(!$Action)
	{
		echo "Vous annulez votre action.";
		header("Location: ./index.php?view=ground_menu");
	}
	else*/
	if($Credits >=$CT)
	{
		include_once './jfv_txt.inc.php';
		include_once './jfv_combat.inc.php';
		include_once './jfv_ground.inc.php';
		$country=$_SESSION['country'];
		$Veh=Insec($_POST['Veh']);
		$Reg=Insec($_POST['Reg']);
		$Dist=Insec($_POST['distance']);
		$Mode=Insec($_POST['Mode']);
		$Reg_eni=strstr($Action,'_',true);	
		$Officier_eni=substr(strstr($Action,'_'),1);
		if($OfficierID >0 and $DB !="Regiment_IA")
			$DB="Regiment";
		elseif($OfficierEMID >0)
		{
			$DB="Regiment_IA";
			$Reg_a_ia=true;
		}
		if($Reg and $Reg_eni and $Veh)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Vehicule_Nbr,Experience,Lieu_ID,Position,Muns,HP,Visible,Skill,Matos FROM $DB WHERE ID = $Reg") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : torp-reg');
			$result2=mysqli_query($con,"SELECT Nom,Vitesse,Taille,Arme_AT,Blindage_f,HP,Type,mobile FROM Cible WHERE ID = $Veh") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : torp-veh');
			//mysqli_close($con);
			if($result2)
			{
				while($datav=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Veh_Nom=$datav['Nom'];
					$Vitesse=$datav['Vitesse'];
					$Taille=$datav['Taille'];
					$Arme=$datav['Arme_AT'];
					$Blindage=$datav['Blindage_f'];
					$HP=$datav['HP'];
					$mobile=$datav['mobile'];
					$Type_navire=$datav['Type'];
				}
				mysqli_free_result($result2);
				unset($datav);
			}
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Vehicule_Nbr=$data['Vehicule_Nbr'];
					$Reg_exp=$data['Experience'];
					$Lieu=$data['Lieu_ID'];
					$Pos=$data['Position'];
					$Muns=4; //$data['Muns'];
					$HP_navire=$data['HP'];
					$Visible=$data['Visible'];
					$Skill=$data['Skill'];
					$Matos=$data['Matos'];
				}
				mysqli_free_result($result);
				unset($data);
			}						
			if($mobile ==5)
			{
				$HP_ori=$HP;
				$HP=$HP_navire;
			}
			else
				$HP_ori=$HP;
			//Tir
			if($Vehicule_Nbr >0)
			{
				$Matos_mun=array(1,2,6,7,8);
				if($Officier_eni >0)
					$DB_Reg_eni="Regiment";
				else
					$DB_Reg_eni="Regiment_IA";
				if($Mode ==12)
					$Visible_final=0;
				else
					$Visible_final=1;
				//$con=dbconnecti();
				$resetreg=mysqli_query($con,"UPDATE $DB SET Visible=".$Visible_final.",Autonomie=Autonomie-1,Experience=Experience+1,Combats=Combats+1 WHERE ID='$Reg'");
				$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
				$result3=mysqli_query($con,"SELECT Nom,Zone,Occupant,Flag,Meteo,Port,Port_level,ValeurStrat,Latitude FROM Lieu WHERE ID='$Lieu'");
				$result2=mysqli_query($con,"SELECT Pays,Vehicule_ID,Vehicule_Nbr,Officier_ID,Position,Placement,Experience,Move,Camouflage,HP,Fret,Fret_Qty,Skill,Matos FROM $DB_Reg_eni WHERE ID='$Reg_eni'");
				//mysqli_close($con);
				if($result3)
				{
					while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
					{
						$Lieu_Nom=$data3['Nom'];
						$Zone=$data3['Zone'];
						//$Occupant=$data3['Occupant'];
						$Port=$data3['Port'];
						$Port_level=$data3['Port_level'];
						$Flag=$data3['Flag'];
						$Meteo=$data3['Meteo'];
						$ValeurStrat=$data3['ValeurStrat'];
						$Latitude=$data3['Latitude'];
						$Occupant=$Flag;
					}
					mysqli_free_result($result3);
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
						$Exp_eni=$data['Experience'];
						$Fret_eni=$data['Fret'];
						$Fret_Qty_eni=$data['Fret_Qty'];
						$Move=$data['Move'];
						$Skill_eni=$data['Skill'];
						$Matos_eni=$data['Matos'];
						/*if($Officier_eni >0)
						{
							$Trait_eni=GetData("Officier","ID",$Officier_eni,"Trait");
							$Transit_eni=GetData("Officier","ID",$Officier_eni,"Transit");
						}
						if($Trait_eni ==5)
							$Cam_bonus_eni=2;
						else
							$Cam_bonus_eni=1;
						$Cam_eni=$Taille_eni/$data['Camouflage']/$Cam_bonus_eni;
						*/
                        $Cam_eni=$data['Camouflage'];
					}
					mysqli_free_result($result2);
					unset($data);
				}
				//$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,HP,Blindage_f,Vitesse,Taille,Portee,mobile,Reput,Type,Categorie,Charge FROM Cible WHERE ID='$Vehicule'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Vehicule_Nom=$data['Nom'];
						$HP_eni=$data['HP'];
						$Blindage_eni=$data['Blindage_f'];
						$Vitesse_eni=$data['Vitesse'];
						$Taille_eni=$data['Taille'];
						$Range_eni=$data['Portee'];
						$mobile_eni=$data['mobile'];
						$Reput_eni=$data['Reput'];
						$Type_eni=$data['Type'];
						$Categorie_eni=$data['Categorie'];
						$Charge_eni=$data['Charge'];
					}
					mysqli_free_result($result);
					unset($result);
				}
				if($mobile_eni ==5)
				{
					$HP_ori_eni=$HP_eni_navire;
					$HP_eni=$HP_eni_navire;
				}
				else
					$HP_ori_eni=$HP_eni;
				$Vitesse_eni=Get_LandSpeed($Vitesse_eni,$mobile_eni,$Zone,$Pos_eni,$Type_eni);			
				if($Type_navire ==37 and ($Pos ==25 or $Mode ==12))
				{
					$Plongee=true;
					$Vitesse/=2;
					$Taille/=5;
					$Sub_bonus=$Reg_exp;
				}				
				if($Zone !=6 and $Plongee) //Init
				{
					$Initiative=true;
					$Dist+=mt_rand(-100,100);
					if($Dist <500)$Dist=500;
				}
				else
				{
					if(mt_rand(0,$Reg_exp)+$Sub_bonus+($Dist/100)+$Vitesse >=mt_rand(0,$Exp_eni)+$Vitesse_eni+(50-($Dist/100)))
					{
						if($Pos_eni ==22 and $Plongee ==false and $Vitesse_eni >$Vitesse) //Ecran de fumée
						{
							$mes.="<br>L'ennemi se protège derrière un écran de fumée!";
							$Initiative=false;
						}
						elseif($Pos_eni ==26)
						{
							$mes.="<br>L'ennemi est protégé par un filet anti-torpilles!";
							$Initiative=false;
						}
						else
						{
							$Initiative=true;
							$Dist+=mt_rand(-100,100);
							if($Dist <500)$Dist=500;
						}
					}
					else
					{
						$Initiative=false;
						$Dist=$Range_eni+mt_rand(-500,0);
						if($Dist <50)$Dist=50;
					}
				}					
				$Tir_base=floor(($Reg_exp/10)+10);
				//Tir
				if($Arme >0)
				{
					if($OfficierID >3)UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
					if($Initiative ==true)
					{
						$skills="<h2>Premium</h2><div style='width:50%;'>";
						$mes.="<br>Votre navire a l'initiative!";
						$con=dbconnecti();
						$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
						$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
						$Rudels=mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Lieu' AND r.Pays='$country' AND r.Placement=8 AND r.Vehicule_Nbr >0 AND c.Type=37");
						mysqli_close($con);
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
						if($OfficierID >0 and $DB =="Regiment")
							$Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Munitions_".$Arme_Cal);
						else
							$Muns_Stock =100;
						$Muns_Conso=$Vehicule_Nbr*$Arme_Multi;						
						if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
						{
							if($OfficierID >0 and $DB =="Regiment")
								UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg);
							if(!$Move)
								$Vitesse_eni=0;
							$mes.="<br>Votre unité tire une ".$Arme_Nom." à une distance de ".$Dist."m";
							$Update_Nbr_eni=0;
							$Update_Reput=0;
							$Update_xp=0;
							if($OfficierID >0 and IsSkill(39,$OfficierID))
							{
								$Tir_base+=25;
								$Arme_Multi+=1;
							}
							elseif($Skill ==37)
							{
								$Tir_base+=10;
								$Arme_Multi=1;
							}
							elseif($Skill ==150)
							{
								$Tir_base+=15;
								$Arme_Multi=2;
							}
							elseif($Skill ==151)
							{
								$Tir_base+=20;
								$Arme_Multi=3;
							}
							elseif($Skill ==152)
							{
								$Tir_base+=25;
								$Arme_Multi=4;
							}
							elseif($Skill ==43)
							{
								$Tir_base+=(5*$Rudels);
								$Arme_Multi=1*(1+((5*$Rudels)/10));
							}
							elseif($Skill ==168)
							{
								$Tir_base+=(10*$Rudels);
								$Arme_Multi=1.5*(1+((10*$Rudels)/10));
							}
							elseif($Skill ==169)
							{
								$Tir_base+=(15*$Rudels);
								$Arme_Multi=2*(1+((15*$Rudels)/10));
							}
							elseif($Skill ==170)
							{
								$Tir_base+=(20*$Rudels);
								$Arme_Multi=2.5*(1+((20*$Rudels)/10));
							}
							if($Vitesse_eni >0)$Esquive=(($Exp_eni/10)+10)+($Dist/500);							
							if($Officier_eni >0 and IsSkill(38,$Officier_eni))
							{
								$Esquive+=25;
								$Vitesse_eni+=5;
							}
							elseif($Skill_eni ==36)
							{
								$Esquive+=10;
								$Vitesse_eni+=5;
							}
							elseif($Skill_eni ==147)
							{
								$Esquive+=15;
								$Vitesse_eni+=10;
							}
							elseif($Skill_eni ==148)
							{
								$Esquive+=20;
								$Vitesse_eni+=15;
							}
							elseif($Skill_eni ==149)
							{
								$Esquive+=25;
								$Vitesse_eni+=20;
							}
							if($Matos ==23)
								$Acoustic=10;
							else
								$Acoustic=0;
							for($t=1;$t<=$Vehicule_Nbr;$t++)
							{
								if($Veh_Nbr_eni >0)
								{
									if($Plongee)$Tir_base*=2;
									$Tir=mt_rand(0,$Tir_base);
									$Shoot=$Tir+$Cam_eni-$Vitesse_eni-mt_rand(0,$Esquive)+$Meteo+$Vitesse+$Acoustic;
									if($Premium)
									{
										$pc_score=(300+$Shoot)/6;
										$Bar_pc=round($pc_score,1,PHP_ROUND_HALF_DOWN);
										if($Shoot >0)
											$skills.="Efficacité de votre ".$t."e torpillage<br><div class='progress'><div class='progress-bar-success' role='progressbar' aria-valuenow='".$pc_score."' aria-valuemin='0' aria-valuemax='100' style='width:100%;'>".$Bar_pc."%</div></div>";
										else
											$skills.="Efficacité de votre ".$t."e torpillage<br><div class='progress'><div class='progress-bar-danger' role='progressbar' aria-valuenow='".$pc_score."' aria-valuemin='0' aria-valuemax='100' style='width:100%;'>".$Bar_pc."%</div></div>";
									}
									else
										$skills.="Efficacité de votre ".$t."e torpillage<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100' style='width:100%;'>?%</div></div>";
									if($OfficierID ==1)
										$mes.="<br>[DEBUG] : Shoot=".$Shoot." (".$Tir."/".$Tir_base.") à ".$Dist."m (+Cam ".$Cam_eni.", - Vit ".$Vitesse_eni." - Esquive ".$Esquive." - Météo ".$Meteo.")";
									if($Shoot >0 or $Tir ==$Tir_base)
									{
										$Base_Dg=mt_rand(1,$Arme_Dg);
										$Blindage_torp=mt_rand($Blindage_eni,$Blindage_eni*100);
										if($Tir >=$Tir_base)$Base_Dg=$Arme_Dg;
										$Degats=($Base_Dg-$Blindage_torp)*GetShoot($Shoot,$Arme_Multi);
										$Degats=round(Get_Dmg($Muns,$Arme_Cal,$Blindage_eni,$Dist,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
										if($Blindage_eni >0 and $Vitesse_eni >10)
											$Degats=floor($Degats/2);										
										elseif($Pos_eni ==2 and $Cam_eni <2)
											$Degats=floor($Degats/2);
										if($Degats >$HP_eni)
										{
											$HP_eni=0;
											$mes.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!<br>La cible est détruite!";
											if($mobile_eni ==5)
											{
												if($Veh_Nbr_eni >1)$HP_eni=$HP_ori_eni;
												$Veh_Nbr_eni-=1;
												if($Transit_eni >0)
												{
													$con=dbconnecti();
													$reset=mysqli_query($con,"UPDATE Regiment SET Experience=0,Skill=0,Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
													Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
													Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Visible=0 WHERE Officier_ID='$Transit_eni' AND Vehicule_Nbr>0 ORDER BY RAND() LIMIT 1");
													mysqli_close($con);
												}
											}
											else
												$Veh_Nbr_eni=floor($Veh_Nbr_eni*0.75);
											if($Fret_eni >0)
											{
												if($Fret_eni ==888)
													UpdateData("Pays","Special_Score",-1,"ID",$Pays_eni);												
												if($Officier_eni >0)
												{
													if($Charge_eni)
													{
														$Perte_Stock=$Fret_Qty_eni/$Veh_Nbr_eni;
														UpdateData("Regiment","Fret_Qty",-$Perte_Stock,"ID",$Reg_eni);
													}
												}
												else
												{
													if($Veh_Nbr_eni <1)
														SetData("Regiment_IA","Fret",0,"ID",$Reg_eni);
												}
											}
											$Update_Nbr_eni-=1;
											$Update_Reput+=$Reput_eni;
											if($Pos_eni ==20) //formation dispersée
												break;												
										}
										elseif($Degats >0)
										{
											$Update_xp+=1;
											$mes.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!";
											$HP_eni-=$Degats;
											if($HP_eni_navire >0)
												UpdateData($DB_Reg_eni,"HP",-$Degats,"ID",$Reg_eni);
											AddEventGround(708,$Vehicule,$OfficierEMID,$Reg_eni,$Lieu,$Degats,$Reg);
										}
										else
											$mes.="<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
									}
									else
										$mes.="<br>Votre unité rate la cible!";
								}
								else
								{
//									if($Officier_eni >0 and IsSkill(29,$Officier_eni))
//									{
//										$Exp_final_eni=0;
//										if($Trait_eni ==11)$Exp_final_eni=$Exp_eni;
//										$con=dbconnecti();
//										$reset=mysqli_query($con,"UPDATE Regiment SET Experience='$Exp_final_eni',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
//										Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
//										Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg_eni'");
//										mysqli_close($con);
//										$Front=GetData("Officier","ID",$Officier_eni,"Front");
//										$Retraite=Get_Retraite($Front,$country,$Latitude);
//										SetData("Regiment","Lieu_ID",$Retraite,"ID",$Officier_eni);
//									}
//									else
//									{
										$con=dbconnecti();
										$reset=mysqli_query($con,"UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg_eni'");
										mysqli_close($con);
//									}
									$mes_eni.="<br>L'ennemi est totalement en déroute!";
									break;
								}
							}
							if($Update_Nbr_eni <0)
							{
								$con=dbconnecti();
								$Placement_eni=mysqli_result(mysqli_query($con,"SELECT Placement FROM $DB_Reg_eni WHERE ID='$Reg_eni'"),0);
								$reset=mysqli_query($con,"UPDATE $DB_Reg_eni SET Vehicule_Nbr=".$Veh_Nbr_eni.",HP='$HP_eni' WHERE ID='$Reg_eni'");
								$upmoral=mysqli_query($con,"UPDATE $DB SET Moral=Moral-".$Update_Nbr_eni." WHERE ID='$Reg'");
								mysqli_close($con);
								/*UpdateData($DB_Reg_eni,"Vehicule_Nbr",$Update_Nbr_eni,"ID",$Reg_eni);
								if($Officier_eni >0)
									UpdateData($DB_Reg_eni,"Moral",$Update_Nbr_eni,"ID",$Reg_eni);*/
								if($Officier_eni >0)
									AddEventGround(406,$Vehicule,$OfficierID,$Reg_eni,$Lieu,-$Update_Nbr_eni,$Reg);
								else
									AddEventGround(506,$Vehicule,$OfficierID,$Reg_eni,$Lieu,-$Update_Nbr_eni,$Reg);
								if($Officier_eni >0)
									$Reg_eni_tab=$Reg_eni;
								else
									$Reg_eni_tab=0;
								AddGroundAtk($Reg,$Reg_eni_tab,$Veh,$Vehicule_Nbr,$Vehicule,$Veh_Nbr_eni,$Pos,$Pos_eni,$Lieu,$Placement_eni,$Dist,-$Update_Nbr_eni,$Reg_a_ia);
							}
							if($OfficierID >0)
							{
								if($Update_Reput and $Pays_eni !=$country)
								{
									if(GetData("Officier","ID",$OfficierID,"Trait") ==1)
										$Update_Reput*=2;
									UpdateData("Regiment","Experience",$Update_Reput,"ID",$Reg);
									UpdateData("Officier","Avancement",$Update_Reput,"ID",$OfficierID);
									UpdateData("Officier","Reputation",$Update_Reput,"ID",$OfficierID);
								}
								elseif($Update_Reput and $Pays_eni ==$country)
								{
									UpdateData("Officier","Avancement",-$Update_Reput,"ID",$OfficierID);
									UpdateData("Officier","Reputation",-$Update_Reput,"ID",$OfficierID);
								}
								if($Update_xp and $Pays_eni !=$country)
								{
									UpdateData($DB,"Experience",$Update_xp,"ID",$Reg);
									UpdateData("Officier","Avancement",$Update_xp,"ID",$OfficierID);
									UpdateData("Officier","Reputation",$Update_xp,"ID",$OfficierID);
									if($Officier_eni >0)
										AddEventGround(456,$Vehicule,$OfficierID,$Reg_eni,$Lieu,$Degats,$Reg);
									else
										AddEventGround(466,$Vehicule,$OfficierID,$Reg_eni,$Lieu,$Degats,$Reg);
								}
							}
						}
						else
							$mes.='<br>Votre unité annule son attaque, faute de munitions!';
						$skills.='</div>';
					}
					elseif($Plongee ==false)
					{
						$mes.="<br>Votre navire n'a pas l'initiative!";
						//Riposte
						$con=dbconnecti();
						$pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Skill,r.Matos,c.Arme_Art FROM Regiment_IA as r, Cible as c, Pays as p 
						WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND c.mobile=5 AND c.Arme_Art >0 AND c.Type<>21 AND c.Portee>='$Dist'");
						/*(SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Skill,r.Matos,c.Arme_Art FROM Regiment as r, Cible as c, Pays as p 
						WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND c.mobile=5 AND c.Arme_Art >0 AND c.Type<>21 AND c.Portee>='$Dist') UNION (*/
						//mysqli_close($con);
						if($pj_unit)
						{
							$Update_XP_eni=0;
							while($data=mysqli_fetch_array($pj_unit))
							{
								$EXP=$data['Experience'];
								$Arme=$data['Arme_Art'];
								//$con=dbconnecti();
								$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
								//mysqli_close($con);
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
								if($data['Officier_ID'] >0)
								{
									$Reg_eni_r=$data['ID'];
									$Muns_Stock=GetData("Regiment","ID",$Reg_eni_r,"Stock_Munitions_".$Arme_Cal);
									$Reg_a_ia_rip=false;
								}
								else
								{
									$Reg_eni_r=0;
									$Muns_Stock=100;
									$Reg_a_ia_rip=true;
								}
								$Muns_Conso=$data['Vehicule_Nbr']*$Arme_Multi;								
								if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
								{
									$mes_eni.="<p>Les navires ennemis ripostent!</p>";
									if($data['Officier_ID'] >0)
										UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg_eni_r);
									$Tir=mt_rand(0,$EXP);
									$Shoot=$Tir+$Meteo+$Taille-mt_rand(0,$Reg_exp);
									if($OfficierID ==1)
										$mes_eni.="<br>[DEBUG] : Shoot=".$Shoot." (".$Tir."/".$EXP.") (+Taille ".$Taille.", - Meteo ".$Meteo.")";
									if($Shoot >1 or $Tir ==$EXP)
									{
										if(in_array($data['Matos'],$Matos_mun))
											$Munition=$data['Matos'];
										else
											$Munition=$data['Muns'];
										$Degats=(mt_rand(1,$Arme_Dg)-$Blindage)*GetShoot($Shoot,$Arme_Multi);
										$Degats=Get_Dmg($Munition,$Arme_Cal,$Blindage,$Dist,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max);
										if($data['Skill'] ==31)
											$Degats*=1.2;
										elseif($data['Skill'] ==132)
											$Degats*=1.3;
										elseif($data['Skill'] ==133)
											$Degats*=1.4;
										elseif($data['Skill'] ==134)
											$Degats*=1.5;
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
											$mes_eni.='<br><b>Le tir ennemi ('.$Reg_eni_r.'e Cie) détruit une de vos unités. ('.round($Degats).' points de dégats!)</b>';
											UpdateData($DB,"Vehicule_Nbr",-1,"ID",$Reg);
											AddEventGround(400,$Veh,$OfficierID,$Reg,$Lieu,1,$Reg_eni_r);
											AddGroundAtk($Reg_eni_r,$Reg,$data['Vehicule_ID'],$data['Vehicule_Nbr'],$Veh,$Vehicule_Nbr,$data['Position'],$Pos,$Lieu,$data['Placement'],$Dist,1,$Reg_a_ia_rip);
											$Update_XP_eni+=5;
											$Vehicule_Nbr=GetData($DB,"ID",$Reg,"Vehicule_Nbr");
											if($Vehicule_Nbr >=1)
											{
												$HP=$HP_ori;
												SetData($DB,"HP",$HP,"ID",$Reg);
											}
										}
										else
										{
											$mes_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.round($Degats).'</b> points de dégats!';
											$Update_XP_eni+=1;
											if($HP_navire >0)
											{
												AddEventGround(450,$Veh,$OfficierID,$Reg,$Lieu,$Degats,$Reg_eni_r);
												UpdateData($DB,"HP",-$Degats,"ID",$Reg);
											}
										}
									}
									else
										$mes_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
								}
								else
									$mes_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
								if($Update_XP_eni and !$Reg_a_ia_rip)
									UpdateData("Regiment","Experience",$Update_XP_eni,"ID",$Reg_eni_r);
							}
							mysqli_free_result($pj_unit);
							unset($data);
						}
						mysqli_close($con);
					}
					if($Vehicule_Nbr >0)
					{
						if($Type_navire ==37 and $Plongee ==true)
						{
							//Riposte ASM
							$con=dbconnecti();
							$pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Skill,c.Arme_Inf,c.Detection FROM Regiment_IA as r, Cible as c, Pays as p 
							WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position=24 AND c.mobile=5 AND c.Type <18 AND c.Arme_Inf >0");
							/*(SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Skill,c.Arme_Inf,c.Detection FROM Regiment as r, Cible as c, Pays as p 
							WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position=24 AND c.mobile=5 AND c.Type <18 AND c.Arme_Inf >0) UNION (*/
							mysqli_close($con);
							if($pj_unit)
							{
								$Update_XP_eni=0;
								$mes.="<br>Votre sous-marin en plongée est détecté par l'ennemi qui donne l'alerte!";
								while($data=mysqli_fetch_array($pj_unit))
								{
									if($data['ID'])
									{
										$EXP=$data['Experience']+$data['Detection'];
										$Arme=$data['Arme_Inf'];
										$Arme_Cal=round(GetData("Armes","ID",$Arme,"Calibre"));
										$Arme_Multi=10;
										if($data['OfficierID'] >0)
										{
											$Reg_eni_r=$data['ID'];
											$Muns_Stock=GetData("Regiment","ID",$Reg_eni_r,"Stock_Charges");
											$Muns_Conso=$data['Vehicule_Nbr']*$Arme_Multi;
											$Reg_a_ia_rip=false;
										}
										else
										{
											$Reg_eni_r=0;
											$Muns_Stock=2;
											$Muns_Conso=1;
											$Reg_a_ia_rip=true;
										}
										if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
										{
											$mes_eni.="<p>Les navires d'escortes ennemis ripostent en larguant des charges de profondeur!</p>";
											$Esquive=$Reg_exp;
											if($data['OfficierID'] >0)UpdateData("Regiment","Stock_Charges",-$Muns_Conso,"ID",$Reg_eni_r);
											if($OfficierID >0 and IsSkill(38,$OfficierID))
												$Esquive+=25;
											elseif($Skill ==32)
											{
												$Esquive+=10;
												$Plongee_rapide=20;
											}
											elseif($Skill ==135)
											{
												$Esquive+=15;
												$Plongee_rapide=30;
											}
											elseif($Skill ==136)
											{
												$Esquive+=20;
												$Plongee_rapide=40;
											}
											elseif($Skill ==137)
											{
												$Esquive+=25;
												$Plongee_rapide=50;
											}
											$Tir=mt_rand(0,$EXP);
											$Shoot=$Tir+$Meteo+$Taille-mt_rand(0,$Esquive);
											/*if($OfficierID ==1)$mes_eni.="<br>[DEBUG] : Shoot=".$Shoot." (".$Tir."/".$EXP.") (+Taille ".$Taille.", - Meteo ".$Meteo.")";*/
											if($Shoot >1 or $Tir ==$EXP)
											{
												$Degats=(mt_rand(1,600)-$Blindage)*GetShoot($Shoot,$Arme_Multi);
												$Degats=round(Get_Dmg(4,$Arme_Cal,$Blindage,$Dist,$Degats,255,$Dist,65535));
												if($Degats <1)$Degats=1;
												$HP-=$Degats;
												if($HP <1)
												{
													$mes_eni.='<br><b>Le tir ennemi ('.$Reg_eni_r.'e Cie) détruit une de vos unités. ('.$Degats.' points de dégats!)</b>';
													UpdateData($DB,"Vehicule_Nbr",-1,"ID",$Reg);
													AddEventGround(400,$Veh,$OfficierID,$Reg,$Lieu,1,$Reg_eni_r);
													AddGroundAtk($Reg_eni_r,$Reg,$data['Vehicule_ID'],$data['Vehicule_Nbr'],$Veh,$Vehicule_Nbr,$data['Position'],$Pos,$Lieu,$data['Placement'],$Dist,1,$Reg_a_ia_rip);
													$Update_XP_eni+=5;
													$Vehicule_Nbr=GetData($DB,"ID",$Reg,"Vehicule_Nbr");
													if($Vehicule_Nbr >=1)
													{
														$HP=$HP_ori;
														SetData($DB,"HP",$HP,"ID",$Reg);
													}
													else
														SetData($DB,"HP",0,"ID",$Reg);
												}
												else
												{
													$mes_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
													$Update_XP_eni+=1;
													if($HP_navire >0)
													{
														AddEventGround(450,$Veh,$OfficierID,$Reg,$Lieu,$Degats,$Reg_eni_r);
														UpdateData($DB,"HP",-$Degats,"ID",$Reg);
													}
												}
											}
											else
												$mes_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
										}
										else
											$mes_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
										if(mt_rand(0,100)<=$Plongee_rapide)
										{
											SetData($DB,"Visible",0,"ID",$Reg);
											$mes_eni.='<br>Le sous-marin bénéficie de la compétence plongée rapide et échappe au grenadage!';
											break;
										}
									}
									else
										$mes.='<br>Vous attendez la riposte qui ne vient pas...';
									if($Update_XP_eni and !$Reg_a_ia_rip)
										UpdateData("Regiment","Experience",$Update_XP_eni,"ID",$Reg_eni_r);
								}
								mysqli_free_result($pj_unit);
								unset($data);
							}
							else
								$mes.="<br>Votre sous-marin en plongée est détecté par l'ennemi qui effectue avec succès une manoeuvre d'évasion!";
							if($Port and $Flag !=$country)
							{
								if($Port_level >1 or $ValeurStrat >3)
									$EXP=250;
								else
									$EXP=100;
								$mes_eni.='<p>Les navires de protection du port ripostent en larguant des charges de profondeur!</p>';
								$Esquive=$Reg_exp;
								if($OfficierID >0 and IsSkill(38,$OfficierID))
									$Esquive+=25;
								$Tir=mt_rand(0,$EXP);
								$Shoot=$Tir+$Meteo+$Taille-mt_rand(0,$Esquive);
								if($Shoot >1 or $Tir ==$EXP)
								{
									$Degats=(mt_rand(1,600)-$Blindage)*GetShoot($Shoot,10);
									$Degats=round(Get_Dmg(7,150,$Blindage,400,$Degats,255,400,65535));
									if($Degats <1)$Degats=1;
									$HP-=$Degats;
									if($HP < 1)
									{
										$mes_eni.='<br><b>Le tir ennemi détruit une de vos unités. ('.$Degats.' points de dégats!)</b>';
										UpdateData($DB,"Vehicule_Nbr",-1,"ID",$Reg);
										AddEventGround(400,$Veh,$OfficierID,$Reg,$Lieu,1,0);
										AddGroundAtk(0,$Reg,5005,5,$Veh,$Vehicule_Nbr,24,$Pos,$Lieu,4,400,1);
										$Vehicule_Nbr=GetData($DB,"ID",$Reg,"Vehicule_Nbr");
										if($Vehicule_Nbr >=1)
										{
											$HP=$HP_ori;
											SetData($DB,"HP",$HP,"ID",$Reg);
										}
									}
									else
									{
										$mes_eni.='<br>Le tir ennemi endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
										if($HP_navire >0)
										{
											AddEventGround(450,$Veh,$OfficierID,$Reg,$Lieu,$Degats,0);
											UpdateData($DB,"HP",-$Degats,"ID",$Reg);
										}
									}
								}
								else
									$mes_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
							}
						}
						else
						{
							//Riposte contre-torpille
							$con=dbconnecti();
							$pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Matos,c.Arme_Art FROM Regiment_IA as r, Cible as c, Pays as p 
							WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (21,23) AND c.mobile=5 AND c.Arme_Art >0");
							/*(SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Matos,c.Arme_Art FROM Regiment as r, Cible as c, Pays as p 
							WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (21,23) AND c.mobile=5 AND c.Arme_Art >0) UNION (*/
							//mysqli_close($con);
							if($pj_unit)
							{
								$mes.="<br>Votre navire est détecté par l'ennemi qui donne l'alerte!";
								$Update_XP_eni=0;
								while($data=mysqli_fetch_array($pj_unit))
								{
									if($data['ID'])
									{
										$EXP=$data['Experience'];
										$Arme=$data['Arme_Art'];
										//$con=dbconnecti();
										$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
										//mysqli_close($con);
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
										if($data['Officier_ID'] >0)
										{
											$Reg_eni_r=$data['ID'];
											$Muns_Stock=GetData("Regiment","ID",$Reg_eni_r,"Stock_Munitions_".$Arme_Cal);
											$Reg_a_ia_rip=false;
										}
										else
										{
											$Reg_eni_r=0;
											$Muns_Stock=9999;
											$Reg_a_ia_rip=true;
										}
										$Muns_Conso=$data['Vehicule_Nbr']*$Arme_Multi;									
										if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
										{
											$mes_eni.="<p>Les navires d'escortes ennemis ripostent!</p>";
											if($data['Officier_ID'] >0)
												UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg_eni_r);
											$Tir=mt_rand(0,$EXP);
											$Shoot=$Tir+$Meteo+$Taille-mt_rand(0,$Reg_exp);
											if($OfficierID ==1)
												$mes_eni.="<br>[DEBUG] : Shoot=".$Shoot." (".$Tir."/".$EXP.") (+Taille ".$Taille.", - Meteo ".$Meteo.")";
											if($Shoot >1 or $Tir ==$EXP)
											{
												if(in_array($data['Matos'],$Matos_mun))
													$Munitionr=$data['Matos'];
												else
													$Munitionr=$data['Muns'];
												$Degats=(mt_rand(1,$Arme_Dg)-$Blindage)*GetShoot($Shoot,$Arme_Multi);
												$Degats=Get_Dmg($Munitionr,$Arme_Cal,$Blindage,$Dist,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max);
												if($data['Position'] ==23)
												{
													if($Skill ==41)
														$Degats*=1.1;
													elseif($Skill ==162)
														$Degats*=1.15;
													elseif($Skill ==163)
														$Degats*=1.2;
													elseif($Skill ==164)
														$Degats*=1.25;
												}
												if($Degats <1)$Degats=1;
												$HP-=round($Degats);
												if($HP <1)
												{
													$mes_eni.='<br><b>Le tir ennemi ('.$Reg_eni_r.'e Cie) détruit une de vos unités. ('.round($Degats).' points de dégats!)</b>';
													UpdateData($DB,"Vehicule_Nbr",-1,"ID",$Reg);
													AddEventGround(400,$Veh,$OfficierID,$Reg,$Lieu,1,$Reg_eni_r);
													AddGroundAtk($Reg_eni_r,$Reg,$data['Vehicule_ID'],$data['Vehicule_Nbr'],$Veh,$Vehicule_Nbr,$data['Position'],$Pos,$Lieu,$data['Placement'],$Dist,1,$Reg_a_ia_rip);
													$Update_XP_eni+=5;
													$Vehicule_Nbr=GetData($DB,"ID",$Reg,"Vehicule_Nbr");
													if($Vehicule_Nbr >=1)
													{
														$HP=$HP_ori;
														SetData($DB,"HP",$HP,"ID",$Reg);
													}											
												}
												else
												{
													$mes_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.round($Degats).'</b> points de dégats!';
													$Update_XP_eni+=1;
													if($HP_navire >0)
													{
														AddEventGround(450,$Veh,$OfficierID,$Reg,$Lieu,$Degats,$Reg_eni_r);
														UpdateData($DB,"HP",-$Degats,"ID",$Reg);
													}
												}
											}
											else
												$mes_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
										}
										else
											$mes_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
									}
									if($Update_XP_eni and !$Reg_a_ia_rip)
										UpdateData("Regiment","Experience",$Update_XP_eni,"ID",$Reg_eni_r);
								}
								mysqli_free_result($pj_unit);
								unset($data);
								if($Port)
								{
									$Faction_Occupant=GetData("Pays","ID",$Occupant,"Faction");
									if($Faction_Occupant !=$Faction)
									{
										if($Port_level >1 or $ValeurStrat >3)
										{
											$EXP=250;
											if($Occupant ==1)
												$Arme=305;
											elseif($Occupant ==2)
												$Arme=261;
											elseif($Occupant ==4)
												$Arme=249;
											elseif($Occupant ==6)
												$Arme=286;
											elseif($Occupant ==7)
												$Arme=253;
											elseif($Occupant ==8)
												$Arme=242;
											elseif($Occupant ==9)
												$Arme=238;
											else
												$Arme=261;
										}
										else
										{
											$EXP=100;
											if($Occupant ==1)
												$Arme=251;
											elseif($Occupant ==2)
												$Arme=200;
											elseif($Occupant ==4)
												$Arme=248;
											elseif($Occupant ==6)
												$Arme=245;
											elseif($Occupant ==7)
												$Arme=240;
											elseif($Occupant ==8)
												$Arme=157;
											elseif($Occupant ==9)
												$Arme=232;
											else
												$Arme=200;
										}
										$mes_eni.="<p>L'artillerie du port riposte!</p>";
										$Esquive=$Reg_exp;
										if($OfficierID >0 and IsSkill(38,$OfficierID))
											$Esquive+=25;
										$Tir=mt_rand(0,$EXP);
										$Shoot=$Tir+$Meteo+$Taille-mt_rand(0,$Esquive);
										if($Shoot >1 or $Tir ==$EXP)
										{
											//$con=dbconnecti();
											$resulta=mysqli_query($con,"SELECT Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
											//mysqli_close($con);
											if($resulta)
											{
												while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
												{
													$Arme_Cal=round($data3['Calibre']);
													$Arme_Multi=$data3['Multi'];
													$Arme_Dg=$data3['Degats'];
													$Arme_Perf=$data3['Perf'];
													$Arme_Portee=$data3['Portee'];
													$Arme_Portee_Max=$data3['Portee_max'];
												}
												mysqli_free_result($resulta);
											}
											$Degats=(mt_rand(1,$Arme_Dg)-$Blindage)*GetShoot($Shoot,$Arme_Multi);
											$Degats=round(Get_Dmg(7,$Arme_Cal,$Blindage,$Dist,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
											if($Degats <1)$Degats=1;
											$HP-=$Degats;
											if($HP < 1)
											{
												$mes_eni.='<br><b>Le tir ennemi détruit une de vos unités. ('.$Degats.' points de dégats!)</b>';
												UpdateData($DB,"Vehicule_Nbr",-1,"ID",$Reg);
												AddEventGround(400,$Veh,$OfficierID,$Reg,$Lieu,1,0);
												AddGroundAtk(0,$Reg,999,5,$Veh,$Vehicule_Nbr,24,$Pos,$Lieu,4,$Dist,1);
												$Update_XP_eni+=5;
												$Vehicule_Nbr=GetData($DB,"ID",$Reg,"Vehicule_Nbr");
												if($Vehicule_Nbr >=1)
												{
													$HP=$HP_ori;
													SetData($DB,"HP",$HP,"ID",$Reg);
												}
											}
											else
											{
												$mes_eni.='<br>Le tir ennemi endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
												$Update_XP_eni+=1;
												if($HP_navire >0)
												{
													AddEventGround(450,$Veh,$OfficierID,$Reg,$Lieu,$Degats,0);
													UpdateData($DB,"HP",-$Degats,"ID",$Reg);
												}
											}
										}
										else
											$mes_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
									}
								}
							}
							mysqli_close($con);
						}
					}					
					if($Vehicule_Nbr <1)
					{
						if($OfficierID >0)
						{
							$Reg_exp=GetData($DB,"ID",$Reg,"Experience");
							$Exp_final=0;
							if(GetData("Officier","ID",$OfficierID,"Trait") ==11)
							{
								$Exp_final=$Reg_exp;
								if($Exp_final >100)
									$Exp_final=100;
							}
							$con=dbconnecti();
							$reset=mysqli_query($con,"UPDATE Regiment SET Experience='$Exp_final',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
							Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_530=0,Stock_Munitions_610=0,Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg'");
							mysqli_close($con);
						}
						else
						{
							$con=dbconnecti();
							$reset=mysqli_query($con,"UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg'");
							mysqli_close($con);
						}
						$mes.='<br>Votre Flottille est totalement détruite!';
					}
					if(!$mes)$mes="<br>Erreur d'affichage !";
					$output="<h1>Torpillage</h1><table class='table'>
					<thead><tr><th></th><th>Votre navire</th><th>Votre cible</th></tr></thead>
					<tr><td></td><td><img src='images/vehicules/vehicule".$Veh.".gif'></td><td><img src='images/vehicules/vehicule".$Vehicule.".gif'></td></tr>
					<tr><td></td><td><img src='images/".$country."20.gif'></td><td><img src='images/".$Pays_eni."20.gif'></td></tr>
					<tr><td align='left'>Position</td><td>".GetPosGr($Pos)."</td><td>".GetPosGr($Pos_eni)."</td></tr>
					<tr><td align='left'>Expérience</td><td>".$Reg_exp."</td><td>".$Exp_eni."</td></tr>
					<tr><td></td><td align='left'>".$mes."</td><td align='left'>".$mes_eni."</td></tr>
					</table>";
					echo $output.$skills;
					if($mobile_eni ==5)
						mail("binote@hotmail.com","Aube des Aigles: Combat naval torpillage","Joueur : ".$OfficierID." dans les environs de : ".$Lieu_Nom."<br>Torpillage de ".$Veh_Nom." sur ".$Vehicule_Nom." <html>".$output."</html>", "Content-type: text/html; charset=utf-8");
					/*if($Admin)
					{
						$titre="Torpillage";
						$mes="<table class='table'>
						<thead><tr><th></th><th>Votre navire</th><th>Votre cible</th></tr></thead>
						<tr><td></td><td><img src='images/vehicules/vehicule".$Veh.".gif'></td><td><img src='images/vehicules/vehicule".$Vehicule.".gif'></td></tr>
						<tr><td></td><td><img src='images/".$country."20.gif'></td><td><img src='images/".$Pays_eni."20.gif'></td></tr>
						<tr><td align='left'>Position</td><td>".GetPosGr($Pos)."</td><td>".GetPosGr($Pos_eni)."</td></tr>
						<tr><td align='left'>Expérience</td><td>".$Reg_exp."</td><td>".$Exp_eni."</td></tr>
						<tr><td></td><td align='left'>".$mes."</td><td align='left'>".$mes_eni."</td></tr>
						</table>";
						mail("binote@hotmail.com","Aube des Aigles: Combat naval torpillage échec","Officier EM : ".$OfficierEMID." dans les environs de : ".$Lieu_Nom."<br>Torpillage de ".$Veh_Nom." sur ".$Vehicule_Nom." <html>".$output.$mes."</html>", "Content-type: text/html; charset=utf-8");
						include_once './default.php';
					}*/
				}
				else
					echo "Votre unité ne dispose pas d'un armement approprié!";
			}
			else
				echo "L'arme de votre unité n'a pas la portée suffisante!";
			if($OfficierEMID >0)
				echo "<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		else
			echo "Aucune cible sélectionnée, l'attaque est annulée !";
	}
	else
		echo 'Pas assez de crédits!';
}