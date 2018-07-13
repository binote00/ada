<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Action=Insec($_POST['Action']);
	$Battle=Insec($_POST['Battle']);
	$Faction=Insec($_POST['Camp']);
	$Reg=Insec($_POST['Reg']);
	if($Reg >0 and $Battle >0)
	{
		if(!$Action or !$_SESSION['ground_bomb'])
		{
			echo "Vous annulez votre action.";
			header("Location: ./index.php?view=ground_menu_pvp");
		}
		else
		{
			$debug=true;
			$Heure=date('H');		
			$Reg_eni=strstr($Action,'_',true);	
			$Officier_Eni=strstr($Action,'_');
			if($Reg_eni >0)
			{
				$Lieu=GetCiblePVP($Battle);
				$Malus_dg=1;
				$OfficierID=0;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT r.Vehicule_Nbr,r.Experience,r.Position,r.Muns,r.Stock_Art,r.HP as HP_navire,c.Nom,c.Reput,c.Arme_Art,c.Optics,c.Portee,c.Blindage_f,c.Taille,c.HP,c.mobile,c.Type,c.Arme_Art_mun,c.Categorie,c.ID as Veh 
				FROM Regiment_PVP as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.ID='$Reg'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Veh=$data['Veh'];
						$Vehicule_Nbr=$data['Vehicule_Nbr'];
						$Reg_exp=$data['Experience'];
						$Pos=$data['Position'];
						$Muns=$data['Muns'];
						$HP_navire=$data['HP_navire'];
						$Veh_Nom=$data['Nom'];
						$Reput=$data['Reput'];
						$HP=$data['HP'];
						$Arme_Art=$data['Arme_Art'];
						$Range_ori=$data['Portee'];
						$Blindage=$data['Blindage_f'];
						$Taille=$data['Taille'];
						$mobile=$data['mobile'];
						$Type_v=$data['Type'];
						$Optics=$data['Optics'];
						$Stock_Art=$data['Stock_Art'];
						if($data['Categorie'] ==2 or $data['Categorie'] ==3 or $data['Categorie'] ==15 or $data['Type'] ==8)
							$Arme_Art_mun=floor($data['Arme_Art_mun']/3);
						else
							$Arme_Art_mun=$data['Arme_Art_mun'];
						if(!$Muns)$Muns=1;
					}
					mysqli_free_result($result);
					unset($data);
				}
				if($mobile ==5)
				{
					$HPi=$HP_navire;
					$Shoots=floor($Arme_Art_mun/10);
					if(!$CT)$Shoots=floor(sqrt($Shoots));
					if($Shoots <1)$Shoots=1;
				}
				else
					$Shoots=$Vehicule_Nbr-$Malus;
				$HP_ori=$HP;							
				if($Arme_Art)
				{
					$Range=GetData("Armes","ID",$Arme_Art,"Portee");
					if($Muns ==8)
						$Range/=2;
				}
				else
					$Range=0;
				if($Shoots >25)$Shoots=25;
				if($Vehicule_Nbr >0 and $Range >2500 and $Shoots >0)
				{
					$Tir_base=floor(($Reg_exp/10)+10);
					$con=dbconnecti();
					$result2=mysqli_query($con,"SELECT Pays,Vehicule_ID,Vehicule_Nbr,Officier_ID,Position,Placement,Experience,Move,Camouflage,HP,Fret,Fret_Qty FROM Regiment_PVP WHERE ID='$Reg_eni'");
					$resultl=mysqli_query($con,"SELECT Zone,Flag,Meteo FROM Lieu WHERE ID='$Lieu'");
					mysqli_close($con);
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
							$Exp_eni=$data['Experience'];
							$Move=$data['Move'];
							$Fret_eni=$data['Fret'];
							$Fret_Qty_eni=$data['Fret_Qty'];
							if($Officier_eni >0)
							{
								$con=dbconnecti();
								$resulto=mysqli_query($con,"SELECT Trait,Avancement,Reputation,Transit FROM Officier WHERE ID='$Officier_eni'");
								mysqli_close($con);
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
									$Deception=true;
								if(IsSkill(10,$Officier_eni))
									$Defense_elastique=true;
							}
							else
							{
								$Cam_bonus_eni=1;
								$Max_Kill=$Veh_Nbr_eni;
							}
							$Cam_eni=$Taille_eni/$data['Camouflage']/$Cam_bonus_eni;
						}
						mysqli_free_result($result2);
						unset($data);
					}
					//Get Vehicule_eni
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,HP,Blindage_f,Vitesse,Taille,mobile,Reput,Type,Categorie,Charge FROM Cible WHERE ID='$Vehicule'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Veh_Nom_eni=$data['Nom'];
							$HP_eni=$data['HP'];
							$Blindage_eni=$data['Blindage_f'];
							$Vitesse_eni=$data['Vitesse'];
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
					if($mobile_eni ==3 and ($Pos_eni ==2 or $Pos_eni ==3 or $Pos_eni ==10))
					{
						if($Officier_eni >0 and IsSkill(14,$Officier_eni))
							$Pente_inverse=true;
					}
					if($mobile_eni ==5)
					{
						$HP_eni=$HP_eni_navire;
						if($Type_eni >17)
						{
							if($DB =="Regiment_IA")
								$HP_ori_eni=0;
							else
								$HP_ori_eni=$HP_eni_navire;
						}
						if($mobile !=5)$Blindage_eni*=20;
					}
					else
						$HP_ori_eni=$HP_eni;				
					//Tir
					$Arme=$Arme_Art;
					if($Arme >0 and $Arme !=82 and $Muns !=7)
					{
						$con=dbconnecti();
						$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
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
						$Muns_Stock=($Arme_Art_mun-$Stock_Art)*$Vehicule_Nbr;
						$Muns_Conso=$Shoots*$Arme_Multi;
						if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
						{
							if(!$Move)
								$Vitesse_eni=0;
							else
								$Vitesse_eni=Get_LandSpeed($Vitesse_eni,$mobile_eni,$Zone,$Pos_eni,$Type_eni);
							if($Placement_eni ==0 and $Flag ==$Pays_eni)
							{
								$Fort=mt_rand(0,50);
								if($Fort >0)
									$Blindage_eni+=Get_Blindage($Zone,$Cam_eni,$Fort,$Pos_eni);
							}
							if($Pos_eni ==2 and !$Fort)
								$Blindage_eni+=Get_Blindage($Zone,$Cam_eni,0,2);
							if($Zone ==6)
							{
								$Tir_base+=10; 
								$Vitesse_eni/=2;
							}
							else
							{
								$Blindage_eni*=2;
								$Vitesse_eni*=2;
							}
							$msg.="<br>Votre unité tire à l'aide de son ".$Arme_Nom;
							//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage_eni,$Muns,$Range);
							$Update_Nbr_eni=0;
							$Update_Reput=0;
							$Update_xp=0;
							$Update_Moral_eni=0;
							for($t=1;$t<=$Shoots;$t++)
							{
								$Degats=0;
								if($Veh_Nbr_eni >0 and $Update_Nbr_eni >-$Max_Kill)
								{
									$Tir=mt_rand(0,$Tir_base)+$Optics;
									//$Esquive=(($Exp_eni/10)+10);
									$Shoot=$Tir+$Cam_eni-$Vitesse_eni+$Meteo;
									if($Deception and mt_rand(0,100)<25)$Shoot=0;
									if($debug)
										$msg_debug.="<br>[DEBUG] : Shoot=".$Shoot." (".$Tir."/".$Tir_base.") (+Cam ".$Cam_eni.", - Vit ".$Vitesse_eni." - Esquive ".$Esquive." - Météo ".$Meteo.")";
									if($Shoot >0 or $Tir >=$Tir_base)
									{
										$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg);
										if($Tir-$Optics >=$Tir_base)$Base_Dg=$Arme_Dg-mt_rand(0,10);
										$Degats=($Base_Dg-$Blindage_eni)*GetShoot($Shoot,$Arme_Multi);
										$Degats=round(Get_Dmg($Muns,$Arme_Cal,$Blindage_eni,$Range,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max)/$Malus_dg);
										if($Blindage_eni >0 and $Vitesse_eni >10)
											$Degats=floor($Degats/2);										
										elseif($Pos_eni ==2 and $Cam_eni <2)
											$Degats=floor($Degats/2);
										elseif($Pos_eni ==1 and $Defense_elastique and ($mobile_eni ==1 or $mobile_eni ==2 or $mobile_eni ==6 or $mobile_eni ==7))
										{
											if($Trait_eni ==3)
												$Degats=round($Degats/4);
											else
												$Degats=round($Degats/2);
										}
										if($Pente_inverse)$Degats=floor($Degats/2);
										if($Degats >=$HP_eni)
										{
											if($Categorie_eni ==5)
											{
												$Collateral=1+(floor($Degats/$HP_eni/10));
												if($Arme >186 and $Arme <192) //Katyusha
												{
													$Collateral*=2;
													$Update_Moral_eni+=1;
												}
												if($Collateral >50)$Collateral=50;
											}
											else
												$Collateral=1;
											$Update_Nbr_eni-=$Collateral;
											$Update_Reput+=$Reput_eni;
											$msg.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!<br>La cible est détruite!";
											$HP_eni=$HP_ori_eni;
											$Veh_Nbr_eni-=$Collateral;
											if($mobile_eni ==5 and $Pos_eni ==20)
												break;
										}
										elseif($Degats >0)
										{
											$Update_xp+=1;
											$msg.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!";
											$HP_eni-=$Degats;
											if($Categorie_eni ==5 and $Arme >186 and $Arme <192)//Katyusha
												$Update_Moral_eni+=1;
										}
										else
											$msg.="<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
										AddEventPVP(2,$Reg_eni,$Reg,$Degats,$Battle);
									}
									else
										$msg.="<br>Votre unité rate la cible!";
								}
								else
								{
									if($Officier_eni >0)
									{
										$Exp_final_eni=0;
										if(IsSkill(6,$Officier_eni) and $mobile_eni !=5)
											SetData("Regiment_PVP","Position",2,"ID",$Officier_eni);
									}
									$msg_eni.="<br>L'ennemi est totalement en déroute!";
									break;
								}
							}
							UpdateData("Regiment_PVP","Stock_Art",1,"ID",$Reg);
							if($mobile_eni !=5)
							{
								if($Pos_eni !=11)
									SetData("Regiment_PVP","Position",8,"ID",$Reg_eni);
								if($Update_Moral_eni)
								{
									$Perte_moral=0-$Vehicule_Nbr-$Update_Moral_eni;
									UpdateData("Regiment_PVP","Moral",$Perte_moral,"ID",$Reg_eni);
								}
							}
							if($Update_Nbr_eni <0)
							{
								UpdateData("Regiment_PVP","Vehicule_Nbr",$Update_Nbr_eni,"ID",$Reg_eni);
								SetData("Regiment_PVP","Visible",0,"ID",$Reg_eni);
								AddGroundAtkPVP($Reg,$Reg_eni,$Veh,$Vehicule_Nbr,$Vehicule,$Veh_Nbr_eni,$Pos,$Pos_eni,$Lieu,$Placement_eni,$Officier_eni,$Officier_pvp,$Range,-$Update_Nbr_eni);
							}
						}
						else
							$msg.="<br>Votre unité annule son attaque, faute de munitions!";
						if($Pos_eni ==8 and $Zone !=6)
							SetData("Regiment_PVP","Position",9,"ID",$Reg_eni);
						/*Contre-batterie
						$query_pj_unit="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,r.Officier_ID,r.Stock_Art,c.Arme_Art,c.Arme_AT,c.Portee,c.Arme_Art_mun FROM Regiment_PVP as r,Cible as c,Pays as p 
						WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND c.Arme_Art >0 AND r.Position IN (3,5,23) AND c.Portee >2500 AND c.Charge=0";
						$con=dbconnecti();
						$pj_unit=mysqli_query($con,$query_pj_unit);
						mysqli_close($con);
						if($pj_unit)
						{
							$Update_XP_eni=0;
							while($data=mysqli_fetch_array($pj_unit))
							{
								$EXP_cb=$data['Experience'];
								if($data['Portee']+($EXP_cb*2) >=$Range_ori)
								{
									$Arme_Art_r=$data['Arme_Art'];
									$Arme_AT_r=$data['Arme_AT'];								
									if($Blindage >0 and $Arme_AT_r and $mobile !=5)
										$Arme=$Arme_AT_r;
									elseif($Arme_Art_r)
										$Arme=$Arme_Art_r;
									$con=dbconnecti();
									$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
									mysqli_close($con);
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
									$Muns_Conso=$data['Vehicule_Nbr']*$Arme_Multi;
									$Reg_eni_r=$data['ID']; //0
									$Muns_Stock=($data['Arme_Art_mun']-$data['Stock_Art'])*$data['Vehicule_Nbr'];
									$Reg_a_ia_rip=true;
									if($mobile == 5)
									{
										$Shoots_cb=floor($data['Arme_Art_mun']/10);
										$Shoots_cb=floor(sqrt($Shoots_cb));
									}
									else
										$Shoots_cb=1;
									if($Shoots_cb <1)$Shoots_cb=1;
									if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
									{
										$msg_eni.="<p>Vous subissez un tir de contre-artillerie de la part de l'ennemi !</p>";
										$Update_Nbr=0;
										for($t=1;$t<=$Shoots_cb;$t++)
										{
											$Tir=mt_rand(0,$EXP_cb);
											$Shoot=$Tir+$Meteo+$Taille-mt_rand(0,$Reg_exp);
											if($debug)
												$msg_debug.="<br>[DEBUG] : Shoot=".$Shoot." (".$Tir."/".$EXP_cb.") (+Taille ".$Taille.", - Meteo ".$Meteo.")";
											if($Shoot >1 or $Tir ==$EXP_cb)
											{
												$Degats=(mt_rand($Arme_Cal,$Arme_Dg)-$Blindage)*GetShoot($Shoot,$Arme_Multi);
												$Degats=round(Get_Dmg($data['Muns'],$Arme_Cal,$Blindage,$data['Portee'],$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
												if($Degats <1)$Degats=1;
												$HP-=$Degats;
												if($HP <1)
												{
													$msg_eni.='<br><b>Le tir ennemi ('.$Reg_eni_r.'e Cie) détruit une de vos unités. ('.$Degats.' points de dégats!)</b>';
													$Update_Nbr-=1;
													$Veh_Nbr-=1;
													$HP=$HP_ori;
													$Update_XP_eni+=$Reput;
												}
												elseif($Degats >1)
												{
													$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
													$Update_XP_eni+=1;
												}
												else
													$msg_eni='<br>Le tir ennemi ne perce pas le blindage de votre unité';
											}
											else
												$msg_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
										}
									}
									else
										$msg_eni.="<br>L'ennemi ne riposte pas à votre tir!";
								}
								else
									$msg_eni.="<br>L'ennemi, hors de portée, ne riposte pas à votre tir!";
							}
							mysqli_free_result($pj_unit);
							unset($data);
						}*/				
						if($Vehicule_Nbr <1)
							$msg.="<br>Votre Cie est totalement en déroute!";
						$titre="Bombardement";
						$mes="<table class='table table-striped'>
						<thead><tr><td></td><td><img src='images/vehicules/vehicule".$Veh.".gif'></td><td><img src='images/vehicules/vehicule".$Vehicule.".gif'></td></tr></thead>
						<tr><td></td><td><img src='images/".$country."20.gif'></td><td><img src='images/".$Pays_eni."20.gif'></td></tr>
						<tr><td align='left'>Terrain</td><td colspan='2'><img src='images/zone".$Zone.".jpg'></td></tr>
						<tr><td align='left'>Position</td><td>".GetPosGr($Pos)."</td><td>".GetPosGr($Pos_eni)."</td></tr>
						<tr><td align='left'>Expérience</td><td>".$Reg_exp."</td><td>".$Exp_eni."</td></tr>
						<tr><td></td><td align='left'>".$msg."</td><td align='left'>".$msg_eni."</td></tr>
						</table>";
						$_SESSION['ground_bomb']=false;
					}
					else
						$mes="<h6>Votre unité ne dispose pas d'un armement approprié!</h6>";
				}
				elseif($Range <2500)
				{
					$titre="Bombardement annulé";
					$img="<img src='images/congestion".$country.".jpg'>";
					$mes="L'arme de votre unité n'a pas la portée suffisante! (".$Range."/2500)";
				}
				else
				{
					$titre="Bombardement annulé";
					$img="<img src='images/congestion".$country.".jpg'>";
					$mes="Un trop grand nombre d'unités occupent cette zone, vous empêchant d'effectuer votre action!";
				}
				$menu="<a href='index.php?view=ground_menu_pvp' class='btn btn-default' title='Retour'>Retour au menu</a>";
				include_once('./default.php');
			}
			else
				echo "<h6>ERREUR : Aucune unité sélectionnée !</h6>";
		}
	}
	else
		echo "Tsss";
}?>