<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Action=Insec($_POST['Action']);
	$Battle=Insec($_POST['Battle']);
	$Faction=Insec($_POST['Camp']);
	$Reg=Insec($_POST['Reg']);
	$Pass=Insec($_POST['Pass']);
	$Ligne=Insec($_POST['Line']);
	$Armement=Insec($_POST['armement']);
	$Distance_tir=Insec($_POST['distance']);
	$Max_Range=Insec($_POST['Max_Range']);
	$Repli=Insec($_POST['repli']);
	if(!$Action or !$_SESSION['ground_bomb'])
	{
		echo "Vous annulez votre action";
		header("Location: ./index.php?view=ground_menu_pvp");
	}
	elseif($Reg and $Battle);
	{
		$Lieu=GetCiblePVP($Battle);
		$debug=0;
		$Gyokusai=false;
		$Gyokusai_eni=false;
		$Raid=false;
		$Tenaille=false;
		$Encercle=false;
		$Avant_Garde=false;
		$Barrage_eni=false;
		$Sur_les_toits=false;
		$Heure=date('H');
		$Reg_eni=strstr($Action,'_',true);
		$Officier_eni=strstr($Action,'_');
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Officier_ID,Pays,Vehicule_ID,Vehicule_Nbr,Position,Placement,Experience,Camouflage,HP,Muns,Distance,Fire,Stock_Art,Stock_AT,Moves FROM Regiment_PVP WHERE ID='$Reg'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl_pvp-reg');
		$result2=mysqli_query($con,"SELECT Officier_ID,Pays,Vehicule_ID,Vehicule_Nbr,Position,Placement,Experience,Camouflage,HP,Muns,Distance,Fire,Stock_Art,Stock_AT,Moves FROM Regiment_PVP WHERE ID='$Reg_eni'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl_pvp-regeni');
		$result3=mysqli_query($con,"SELECT Nom,Zone,Flag,Meteo,Fortification FROM Lieu WHERE ID='$Lieu'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl_pvp-lieu');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Reg_Officier_ID=$data['Officier_ID'];
				$Experience_ori=$data['Experience'];
				$Experience=floor(($Experience_ori/5)+10);
				$Tir_base=$Experience;
				$country=$data['Pays'];
				$Veh_Nbr=$data['Vehicule_Nbr'];
				$Veh=$data['Vehicule_ID'];
				$Camouflage=$data['Camouflage'];
				$HP_navire=$data['HP'];
				$Munition=$data['Muns'];
				$Stock_Art=$data['Stock_Art'];
				$Stock_AT=$data['Stock_AT'];
				$Distance=$data2['Distance'];
				$Moves=$data['Moves'];
				$Pos=$data['Position'];
				if($data['Position'] ==1 or $data['Position'] ==3 or $data['Position'] ==7 or $data['Position'] ==8)
					$Tactique=$Experience/2;
				elseif($data['Position'] ==2 or $data['Position'] ==6 or $data['Position'] ==9 or $data['Position'] ==10 or $data['Position'] ==26)
					$Tactique=0;
				else
					$Tactique=$Experience;
				$Move=1;
				if(!$Munition)$Munition=1;
			}
			mysqli_free_result($result);
			unset($data);
			$Veh_Nbr_Ori=$Veh_Nbr;
			if($Pass >0 and $Pass <=$Veh_Nbr)
				$Veh_Nbr_atk=floor($Pass);
			else
				$Veh_Nbr_atk=$Veh_Nbr;
		}
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Officier_eni=$data2['Officier_ID'];
				$Exp_eni_ori=$data2['Experience'];
				$Exp_eni=floor(($Exp_eni_ori/5)+10);
				$Tir_base_eni=$Exp_eni;
				$Pays_eni=$data2['Pays'];
				$Veh_Nbr_eni=$data2['Vehicule_Nbr'];
				$Vehicule=$data2['Vehicule_ID'];
				$Placement_eni=$data2['Placement'];
				$Camouflage_eni=$data2['Camouflage'];
				$HP_eni_navire=$data2['HP'];
				$Munition_eni=$data2['Muns'];
				$Stock_Art_eni=$data2['Stock_Art'];
				$Stock_AT_eni=$data2['Stock_AT'];
				$Distance_eni=$data2['Distance'];
				$Fire_eni=$data2['Fire'];
				$Moves_eni=$data['Moves'];
				$Pos_eni=$data2['Position'];
				if($data2['Position'] ==1 or $data2['Position'] ==3 or $data2['Position'] ==10)
					$Tactique_eni=$Exp_eni*2;
				elseif($data2['Position'] ==2)
					$Tactique_eni=$Exp_eni*4;
				elseif($data2['Position'] ==4 or $data2['Position'] ==5 or $data2['Position'] ==6 or $data2['Position'] ==7 or $data2['Position'] ==8 or $data2['Position'] ==11 or $data['Position'] ==26)
					$Tactique_eni=$Exp_eni/2;
				else
					$Tactique_eni=$Exp_eni;
			}
			mysqli_free_result($result2);
			$Max_Kill=$Veh_Nbr_eni;
		}
		if($result3)
		{
			while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$Lieu_Nom=$data['Nom'];
				$Zone=$data['Zone'];
				$Flag=$data['Flag'];
				$Meteo=$data['Meteo'];
				$Fortification=$data['Fortification'];
			}
			mysqli_free_result($result3);
			unset($data);
		}		
		if($Trait_o ==5)
			$Cam_bonus=2;
		else
			$Cam_bonus=1;
		if($Skill4 ==100 and $Zone ==8)
			$Cam_bonus+=1;			
		if($Veh and $Vehicule)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Veh'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl_pvp-veh');
			$result2=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Vehicule'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl_pvp-veheni');
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Veh_Nom=$data['Nom'];
					$HP=$data['HP'];
					$HP_ori=$HP;
					$Arme_Inf=$data['Arme_Inf'];
					$Arme_Art=$data['Arme_Art'];
					$Arme_AT=$data['Arme_AT'];
					$Arme_AA=$data['Arme_AA'];
					if($data['Categorie'] ==2 or $data['Categorie'] ==3 or $data['Categorie'] ==15 or $data['Type'] ==8)
					{
						$Arme_Art_mun=floor($data['Arme_Art_mun']/3);
						$Arme_AT_mun=floor($data['Arme_AT_mun']/3);
					}
					else
					{
						$Arme_Art_mun=$data['Arme_Art_mun'];
						$Arme_AT_mun=$data['Arme_AT_mun'];
					}
					$Optics=$data['Optics'];
					$Blindage_t=$data['Blindage_t'];
					$Blindage_l=$data['Blindage_l'];
					$Blindage_a=$data['Blindage_a'];
					$Blindage=$data['Blindage_f'];
					$Vitesse=$data['Vitesse'];
					$Taille=$data['Taille'];
					$mobile=$data['mobile'];
					$Reput=$data['Reput'];
					$Type=$data['Type'];
					$Range=$data['Portee'];
					$Carbu=$data['Carbu_ID'];
					$Categorie=$data['Categorie'];
					$Tactique+=(($data['Radio']*5)+($data['Tourelle']*5));
					$Cam=$Taille/$Camouflage/$Cam_bonus;
					$Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type);
				}
				mysqli_free_result($result);
				unset($data);
				if($OfficierID >0)
				{
					if($Categorie ==5)
					{
						if(IsSkill(20,$OfficierID))
							$Gyokusai=true;
						if(IsSkill(26,$OfficierID))
						{
							$Tactique_eni=$Exp_eni;
							$msg.="<p>Vos troupes bénéficient de votre compétence <b>Sturmtruppen</b> !</p>";
						}
					}
					if($mobile !=3)
					{				
						if(IsSkill(2,$OfficierID) and mt_rand(0,100)<25)
							$Avant_Garde=true;
					}
				}
				//Battle
				$Range_Battle=$Range+($Range-$Distance);
				$graph_reg=$Veh_Nbr." ".GetVehiculeIcon($Veh,$country,0,0,$Front)." ".$Range_Battle."m";
			}
			//Get Vehicule_eni
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Veh_Nom_eni=$data['Nom'];
					$HP_eni=$data['HP'];
					$HP_ori_eni=$HP_eni;
					$Arme_Inf_eni=$data['Arme_Inf'];
					$Arme_Art_eni=$data['Arme_Art'];
					$Arme_AT_eni=$data['Arme_AT'];
					$Arme_AA_eni=$data['Arme_AA'];
					if($data['Categorie'] ==2 or $data['Categorie'] ==3 or $data['Categorie'] ==15 or $data['Type'] ==8)
					{
						$Arme_Art_eni_mun=floor($data['Arme_Art_mun']/3);
						$Arme_AT_eni_mun=floor($data['Arme_AT_mun']/3);
					}
					else
					{
						$Arme_Art_eni_mun=$data['Arme_Art_mun'];
						$Arme_AT_eni_mun=$data['Arme_AT_mun'];
					}
					$Optics_eni=$data['Optics'];
					$Blindage_t_eni=$data['Blindage_t'];
					$Blindage_l_eni=$data['Blindage_l'];
					$Blindage_a_eni=$data['Blindage_a'];
					$Blindage_eni=$data['Blindage_f'];
					$Vitesse_eni=$data['Vitesse'];
					$Taille_eni=$data['Taille'];
					$mobile_eni=$data['mobile'];
					$Reput_eni=$data['Reput'];
					$Type_eni=$data['Type'];
					$Range_eni=$data['Portee'];
					$Carbu_eni=$data['Carbu_ID'];
					$Charge_eni=$data['Charge'];
					$Categorie_eni=$data['Categorie'];
					$Tactique_eni+=(($data['Radio']*5)+($data['Tourelle']*5));
					if($Trait_eni ==5)
						$Cam_bonus_eni=2;
					else
						$Cam_bonus_eni=1;
					if($Officier_eni >0 and $Pos_eni !=6 and $Pos_eni !=11)
					{
						if($Zone ==8 and IsSkill(100,$Officier_eni))
							$Cam_bonus_eni+=1;
						if($Pos_eni ==2 and IsSkill(9,$Officier_eni))
							$Deception=true;
						if($mobile_eni ==1 or $mobile_eni ==2 or $mobile_eni ==6 or $mobile_eni ==7)
						{
							if(IsSkill(10,$Officier_eni))
								$Defense_elastique=true;
						}
						elseif($Pos_eni ==2 or $Pos_eni ==3 or $Pos_eni ==10)
						{
							if(IsSkill(11,$Officier_eni))
								$Herisson=true;
							/*if(IsSkill(14,$Officier_eni))
								$Pente_inverse=true;*/
						}
						if($Pos_eni !=4 and ($Type_eni ==4 or $Type_eni ==6 or $Type_eni ==12))
						{
							if(IsSkill(12,$Officier_eni) and !IsSkill(24,$OfficierID))
								$Pak_front=true;
						}
					}
					$Cam_eni=$Taille_eni/$Camouflage_eni/$Cam_bonus_eni;
					$Vitesse_eni=Get_LandSpeed($Vitesse_eni,$mobile_eni,$Zone,$Pos_eni,$Type_eni);
					if($Flag ==$Pays_eni)
					{
						$Vitesse_eni+=10;
						if($Placement_eni ==0 and $Fortification >0)
							$Blindage_eni+=Get_Blindage($Zone,$Cam_eni,$Fortification,$Pos_eni);
					}
					if($Pos_eni ==2 and !$Fortification)
						$Blindage_eni+=Get_Blindage($Zone,$Cam_eni,0,2);
				}
				mysqli_free_result($result2);
				unset($data);
				if($mobile_eni ==5)
					$HP_eni=$HP_eni_navire;
				if($mobile ==5)
					$HP=$HP_navire;
				if($Officier_eni >0 and $Pos_eni !=6 and $Pos_eni !=11)
				{
					if($Categorie_eni ==5)
					{
						if(IsSkill(20,$Officier_eni))
							$Gyokusai_eni=true;
						if($Pos_eni ==2 or $Pos_eni ==3 or $Pos_eni ==10)
						{
							if(IsSkill(4,$Officier_eni))
								$Enfilade_eni=true;
							if(IsSkill(13,$Officier_eni))
								$Sur_les_toits=true;
						}
					}
					elseif($Categorie_eni ==8)
					{
						if(IsSkill(8,$Officier_eni) and mt_rand(0,100)<25 and $Pos_eni !=4)
							$Barrage_eni=true;
					}
					if($mobile_eni !=3)
					{				
						if(IsSkill(1,$Officier_eni) and mt_rand(0,100)<25)
							$Arriere_Garde_eni=true;
						if(IsSkill(5,$Officier_eni))
							$Flanc_Garde_eni=true;
						if($Pos_eni ==2 or $Pos_eni ==10)
						{
							if(IsSkill(7,$Officier_eni))
								$Tranchees_eni=true;
						}
					}
				}
				//Battle
				$Range_Battle_eni=$Range_eni+($Range_eni-$Distance_eni);
				$graph_reg_eni=$Veh_Nbr_eni." ".GetVehiculeIcon($Vehicule,$Pays_eni,0,0,$Front)." ".$Range_Battle_eni."m";
			}
			if($Vitesse >$Vitesse_eni and $OfficierID >0)
			{
				if($mobile !=3 and IsSkill(21,$OfficierID))
				{
					$Raid=true;
					$msg.="<p>Vos troupes bénéficient de votre compétence <b>Raid</b> !</p>";
				}
				if(IsSkill(23,$OfficierID) and !$Arriere_Garde_eni and ($mobile ==1 or $mobile==2 or $mobile==6 or $mobile==7))
				{
					$Tenaille=true;
					$Encercle=true;
					$msg.="<p>Vos troupes bénéficient de votre compétence <b>Encerclement</b> !</p>";
				}
				elseif(IsSkill(16,$OfficierID) and !$Arriere_Garde_eni and ($mobile ==1 or $mobile==2 or $mobile==6 or $mobile==7) and $Zone !=5 and $Zone !=7)
				{
					$Tenaille=true;
					$Enfilade_eni=false;
					$msg.="<p>Vos troupes bénéficient de votre compétence <b>Tenaille</b> !</p>";
				}
			}
			//Init
			if($Pos_eni ==3)$Bonus_Init=$Tactique_eni;		
			if(((($Range_Battle + mt_rand(0,$Tactique))>=($Bonus_Init + $Range_Battle_eni + mt_rand(0,$Tactique_eni))) OR (((mt_rand(0,$Vitesse)*50)+mt_rand(0,$Tactique))>($Bonus_Init + $Range_Battle_eni + mt_rand(0,$Tactique_eni))))
				AND !$Barrage_eni)
			{
				if($Pak_front)
				{
					$Initiative=false;
					$Dist_shoot=$Dist_eni+mt_rand(-100,100);
				}
				else
				{
					$Initiative=true;
					$Dist_shoot=$Distance_tir+mt_rand(-100,100);
				}
			}
			else
			{
				$Initiative=false;
				if($Dist_eni >$Dist_shoot)
					$Dist_shoot=$Dist_eni+mt_rand(-100,100);
				else
					$Dist_shoot=$Distance_tir+mt_rand(-100,100);
			}
			if($Max_Range >0 and $Dist_shoot >$Max_Range)$Dist_shoot=$Max_Range;
			if($Dist_shoot <50)$Dist_shoot=50;
			if($Zone ==6 and $Pos_eni ==22 and $Vitesse_eni >$Vitesse)$Smoke=true;
			/*Appui allié
			$con=dbconnecti();
			$arti_couv=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Muns,r.Stock_Essence_87,r.Officier_ID,c.Arme_Art,c.Portee
			FROM Regiment_PVP as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (5,23) AND r.Placement='$Placement_eni' AND c.Arme_Art >0 AND c.Charge=0 AND r.ID<>'$Reg_eni'");
			mysqli_close($con);
			if($arti_couv)
			{
				while($data=mysqli_fetch_array($arti_couv))
				{
					$Reg_eni_r=0;
					$Update_XP_eni=0;
					$EXP=($data['Experience']/10)+10;
					$chance_tir=mt_rand(0,40);						
					if($chance_tir <=$EXP and ($data['Portee'] >=$Distance_tir or $data['Portee'] >=$Dist_shoot))
					{
						$msg_eni.="<br>Une unité d'artillerie ennemie en appui tire pour couvrir votre adversaire !";
						$Arme=$data['Arme_Art'];							
						$con=dbconnecti();
						$result3=mysqli_query($con,"SELECT Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
						mysqli_close($con);
						if($result3)
						{
							while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
							{
								$Arme_Cal=round($data3['Calibre']);
								$mult=$data3['Multi'];
								$Arme_Degats=$data3['Degats'];
								$Arme_Perf=$data3['Perf'];
								$Arme_Portee=$data3['Portee'];
								$Arme_Portee_Max=$data3['Portee_max'];
							}
							mysqli_free_result($result3);
						}
						$Reg_eni_r=$data['ID']; //0
						$Muns_Stock=9999;
						if($Muns_Stock >=$mult and $mult >0)
						{
							$Tir=mt_rand(0,$EXP);
							$Shoot=$Tir+$Meteo+$Cam-$Vitesse-mt_rand(0,$Tactique);
							if($Raid and mt_rand(0,1)==1)$Shoot=0;
							if($Shoot >1 or $Tir ==$EXP)
							{
								$Degats=(mt_rand(1,$Arme_Degats)-$Blindage)*GetShoot($Shoot,$mult);
								$Degats=round(Get_Dmg($data['Muns'],$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
								if($Gyokusai or $Gyokusai_eni)$Degats*=2;
								$HP-=$Degats;
								if($Degats <2)
								{
									$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) est trop faible pour percer le blindage de vos unités!';
									$Degats=1;
								}
								elseif($HP <1)
								{
									$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) détruit une de vos unités. (<b>'.$Degats.'</b> points de dégats!)';
									$Update_XP_eni=$Reput;
									$Veh_Nbr-=1;
									$Veh_Nbr_atk-=1;
									if($Veh_Nbr_atk <1)
										break;
									else
										$HP=$HP_ori;
								}
								elseif($Degats >1)
								{
									$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
									$Update_XP_eni=1;
								}
								$Tac_Appui=true;
							}
							else
								$msg_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
						}
						else
							$msg_eni.='<br>Le tir ennemi fait long feu!';
					}
					$graph_art_eni.=GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)." ".$data['Portee']."m<br>";
				}
				mysqli_free_result($arti_couv);
				unset($data);
			}						
			if($Veh_Nbr_atk >0 and $Zone !=6)
			{
				//Couverture alliée
				$con=dbconnecti();
				$pj_unit=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Muns,r.Stock_Essence_87,r.Officier_ID,c.Arme_Art,c.Arme_AT,c.Arme_Inf,c.Portee
				FROM Regiment_PVP as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position=1 AND r.Placement='$Placement_eni' AND c.mobile IN (1,2,6) AND c.Vitesse >10 AND c.Charge=0 AND r.ID<>'$Reg_eni'");
				mysqli_close($con);
				if($pj_unit)
				{
					while($data=mysqli_fetch_array($pj_unit))
					{
						$Reg_eni_r=0;
						$Update_XP_eni=0;
						$EXP=($data['Experience']/10)+10;
						if($Dist_shoot <501)
							$chance_tir=1;
						else
							$chance_tir=mt_rand(0,50);
						if($chance_tir <=$EXP and ($data['Portee'] >=$Distance_tir or $data['Portee'] >=$Dist_shoot))
						{
							$msg_eni.="<br>Une unité mobile ennemie en couverture contre-attaque pour couvrir votre adversaire !";
							$Arme_Art_r=$data['Arme_Art'];
							$Arme_AT_r=$data['Arme_AT'];							
							if($Blindage and $Arme_AT_r)
								$Arme=$Arme_AT_r;
							elseif($Arme_Art_r)
								$Arme=$Arme_Art_r;
							else
								$Arme=$data['Arme_Inf'];								
							$con=dbconnecti();
							$result3=mysqli_query($con,"SELECT Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
							mysqli_close($con);
							if($result3)
							{
								while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
								{
									$Arme_Cal=round($data3['Calibre']);
									$mult=$data3['Multi'];
									$Arme_Degats=$data3['Degats'];
									$Arme_Perf=$data3['Perf'];
									$Arme_Portee=$data3['Portee'];
									$Arme_Portee_Max=$data3['Portee_max'];
								}
								mysqli_free_result($result3);
							}			
							$Reg_eni_r=$data['ID']; //0
							$Muns_Stock=9999;
							if($Muns_Stock >=$mult and $mult >0)
							{
								if($data['Officier_ID'] >0)
								{
									if($Arme ==136)
										UpdateData("Regiment","Stock_Essence_87",-$mult,"ID",$Reg_eni_r);
									else
										UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$mult,"ID",$Reg_eni_r);
								}												
								$Tir=mt_rand(0,$EXP)+(10-($Dist_shoot/100));
								$Shoot=$Tir+$Meteo+$Cam-($Vitesse/2)-mt_rand(0,$Tactique)+$data['Vehicule_Nbr'];
								if($Raid and mt_rand(0,1)==1)$Shoot=0;
								if($Shoot >1 or $Tir ==$EXP)
								{
									$Degats=(mt_rand(1,$Arme_Degats)-$Blindage)*GetShoot($Shoot,$mult);
									$Degats=round(Get_Dmg($data['Muns'],$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
									if($Gyokusai or $Gyokusai_eni)$Degats*=2;
									$HP-=$Degats;
									if($Degats <2)
									{
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) est trop faible pour percer le blindage de vos unités!';
										$Degats=1;
									}
									elseif($HP <1)
									{
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) détruit une de vos unités. (<b>'.$Degats.'</b> points de dégats!)';
										$Update_XP_eni=$Reput;
										$Veh_Nbr-=1;
										$Veh_Nbr_atk-=1;
										if($Veh_Nbr_atk <1)
											break;
										else
											$HP=$HP_ori;
									}
									elseif($Degats >1)
									{
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
										$Update_XP_eni=1;
									}
									$Tac_Couv=true;
								}
								else
									$msg_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
							}
							else
								$msg_eni.='<br>Le tir ennemi fait long feu!';
						}
						$graph_couv_eni.=GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)." ".$data['Portee']."m<br>";
					}
					mysqli_free_result($pj_unit);
					unset($data);
				}
			}*/		
			/*Embuscade AT
			if($Veh_Nbr_atk >0 and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7))
			{
				if($Avant_Garde)
					$msg_eni.='<br>Vous évitez une embuscade ennemie grâce à votre compétence <b>Avant-Garde</b>!';
				else
				{
					$con=dbconnecti();
					$at_couv=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Officier_ID,r.Muns,r.Stock_Essence_87,r.Visible,r.Stock_AT,c.Arme_AT,c.mobile,c.Portee,c.Arme_AT_mun
					FROM Regiment_PVP as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (3,10) AND r.Placement='$Placement_eni' AND c.Arme_AT >0 AND c.Charge=0 AND (c.mobile=3 OR c.Type=9)");
					mysqli_close($con);
					if($at_couv)
					{
						while($data=mysqli_fetch_array($at_couv))
						{
							$Reg_eni_r=0;
							$Update_XP_eni=0;
							$EXP=($data['Experience']/10)+10;
							$Muns_Stock=$data['Arme_AT_mun']-$data['Stock_AT'];					
							if($data['Portee'] >=$Distance_tir or $data['Portee'] >=$Dist_shoot and $Muns_Stock >0)
							{							
								$Update_Nbr=0;
								$Arme=$data['Arme_AT'];								
								$con=dbconnecti();
								$result3=mysqli_query($con,"SELECT Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
								mysqli_close($con);
								if($result3)
								{
									while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
									{
										$Arme_Cal=round($data3['Calibre']);
										$mult=$data3['Multi'];
										$Arme_Degats=$data3['Degats'];
										$Arme_Perf=$data3['Perf'];
										$Arme_Portee=$data3['Portee'];
										$Arme_Portee_Max=$data3['Portee_max'];
									}
									mysqli_free_result($result3);
								}			
								if($data['Vehicule_Nbr'] >25)
									$Emb_atk_nbr=ceil($data['Vehicule_Nbr']/=10);
								else
									$Emb_atk_nbr=$data['Vehicule_Nbr'];
								//Prise de flanc ou de face
								$rand_blindage=mt_rand(0,100)+$EXP;
								if(!$data['Visible'])
								{
									if($rand_blindage <20)
										$Blindage_emb=$Blindage;
									elseif($rand_blindage <50)
										$Blindage_emb=$Blindage_l;
									else
										$Blindage_emb=$Blindage_a;
									$emb_txt=", elle vous prend de flanc";
								}
								else
								{
									if($rand_blindage <60)
										$Blindage_emb=$Blindage;
									elseif($rand_blindage <95)
										$Blindage_emb=$Blindage_l;
									else
										$Blindage_emb=$Blindage_a;
								}
								for($t=1;$t<=$Emb_atk_nbr;$t++)
								{
									if($Veh_Nbr_atk <1)
										break;
									if($Dist_shoot <501)
										$chance_tir=1;
									else
										$chance_tir=mt_rand(0,40);									
									if($chance_tir <=$EXP)
									{
										$msg_eni.="<br>Une unité anti-char ennemie en embuscade tire pour couvrir votre adversaire".$emb_txt." !";
										$Reg_eni_r=$data['ID']; //0
										$Muns_Stock=($data['Arme_AT_mun']-$data['Stock_AT'])*$data['Vehicule_Nbr'];
										if($Muns_Stock >=$mult and $mult >0)
										{
											$Tir=mt_rand(0,$EXP)+(10-($Dist_shoot/100));
											$Shoot=$Tir+$Meteo+$Cam-($Vitesse/2)-mt_rand(0,$Tactique)+$data['Vehicule_Nbr']+($data['Portee']/100);
											if($Raid and mt_rand(0,1)==1)$Shoot=0;
											if($Shoot >1 or $Tir ==$EXP)
											{
												if(!$Initiative)$data['Portee']=100;
												$Degats=(mt_rand(1,$Arme_Degats)-$Blindage_emb)*GetShoot($Shoot,$mult);
												$Degats=round(Get_Dmg($data['Muns'],$Arme_Cal,$Blindage_emb,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
												if($Gyokusai or $Gyokusai_eni)
													$Degats*=2;
												if($data['Officier_ID'] >0 and IsSkill(3,$data['Officier_ID']))
													$Degats*=2;
												if($data['Officier_ID'] >0 and $data['mobile'] ==3 and !$Tenaille and ($data['Position'] ==2 or $data['Position'] ==3 or $data['Position'] ==10) and IsSkill(4,$data['Officier_ID']))
													$Degats*=2;
												$HP-=$Degats;
												if($Degats <2)
												{
													$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) est trop faible pour percer le blindage de vos unités!';
													$Degats=1;
												}
												elseif($HP <1)
												{
													if($Reg_eni_r >0)
														$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) détruit une de vos unités. (<b>'.$Degats.'</b> points de dégats!)';
													else
														$msg_eni.='<br>Le tir ennemi (Cie IA) détruit une de vos unités. (<b>'.$Degats.'</b> points de dégats!)';
													$Update_XP_eni=$Reput;
													$HP=$HP_ori;
													$Veh_Nbr-=1;
													$Veh_Nbr_atk-=1;
													$Update_Nbr-=1;
												}
												elseif($Degats >1)
												{
													if($Reg_eni_r >0)
														$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
													else
														$msg_eni.='<br>Le tir ennemi (Cie IA) endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
													$Update_XP_eni=1;
												}
												$Tac_Ambush=true;
											}
											else
												$msg_eni.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
											UpdateData("Regiment_PVP","Stock_AT",$mult,"ID",$Reg_eni_r);
										}
										else
											$msg_eni.='<br>Le tir ennemi fait long feu!';
									}
								}
							}
							$graph_AT_eni.=GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)." ".$data['Portee']."m<br>";
						}
						mysqli_free_result($at_couv);
						unset($data);
					}
				}
			}*/
			if($Repli ==1 and ($Tac_Appui or $Tac_Couv or $Tac_Ambush))$Repli=2;
			/*Rounds Distance_tir
			$Dist_shoot=round($Range_Battle-(($Range_Battle/255)*(mt_rand(0,$Tactique)-mt_rand(0,$Tactique_eni))));
			if($Dist_shoot >$Range_Battle)
				$Dist_shoot=$Range_Battle;
			elseif($Dist_shoot <100)
				$Dist_shoot=100;*/
			if($Veh_Nbr_atk >0 and $Smoke ==false)
			{
				if($Initiative ==true)
				{
					//Tir
					$msg.="Vos troupes ont l'initiative, ".$Veh_Nbr_atk." ".$Veh_Nom." (sur une force originale de ".$Veh_Nbr_Ori.") engagent l'ennemi à une distance d'environ ".$Dist_shoot."m";
					$Muns_Stock=9999;
					$Stock_up="Stock_Munitions_8";
					if($Armement ==1)
						$Arme=$Arme_Inf;
					elseif($Zone ==6 or $mobile ==5)
					{
						$Arme=$Arme_Art;
						$Muns_Stock=($Arme_Art_mun-$Stock_Art)*$Veh_Nbr_atk;
						$Stock_up="Stock_Art";
					}
					elseif($Blindage_eni >0 and $Arme_AT >0 and $Arme_AT !=82 and $Munition !=8)
					{
						$Arme=$Arme_AT;
						$Muns_Stock=($Arme_AT_mun-$Stock_AT)*$Veh_Nbr_atk;
						$Stock_up="Stock_AT";
						if($Type ==4 or $Type ==9) //Canon AT
						{
							$rand_blindage=mt_rand(0,100)+$Experience;
							if($rand_blindage <20)
								$Blindage_eni=$Blindage_eni;
							elseif($rand_blindage <50)
								$Blindage_eni=$Blindage_l_eni;
							else
								$Blindage_eni=$Blindage_a_eni;
							$msg.="<br><b>Vous prenez l'ennemi de flanc!</b>";
						}
					}
					elseif($Arme_Art >0 and $Arme_Art !=82 and $Range <3000 and $Munition !=7)
					{
						$Arme=$Arme_Art;
						$Muns_Stock=($Arme_Art_mun-$Stock_Art)*$Veh_Nbr_atk;
						$Stock_up="Stock_Art";
					}
					elseif($Munition <7)
						$Arme=$Arme_Inf;
					else
						$Arme=0;
					if($Arme >0)
					{
						$con=dbconnecti();
						$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
						mysqli_close($con);
						if($result3)
						{
							while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
							{
								$Arme_Nom=$data3['Nom'];
								$Arme_Cal=round($data3['Calibre']);
								$Arme_Multi=$data3['Multi'];
								$Arme_Dg=$data3['Degats'];
								$Arme_Perf=$data3['Perf'];
								$Arme_Portee=$data3['Portee'];
								$Arme_Portee_Max=$data3['Portee_max'];
							}
							mysqli_free_result($result3);
						}
						$Muns_Conso=$Veh_Nbr_atk*$Arme_Multi;
						if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
						{
							$msg.="<br>Votre unité tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
							//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage_eni,$Munition,$Dist_shoot);
							$Update_Nbr_eni=0;
							$Update_Reput=0;
							$Update_xp=0;
							if($Veh_Nbr_atk >25)$Veh_Nbr_atk=floor($Veh_Nbr_atk/10);
							for($t=1;$t<=$Veh_Nbr_atk;$t++)
							{
								if($Veh_Nbr_eni <1 or $Update_Nbr_eni <-$Max_Kill)
									break;
								$Tir=mt_rand(0,$Tir_base)+$Optics;
								$Defense_tir=$Vitesse_eni+mt_rand(0,$Tactique_eni)-$Cam_eni-$Meteo;
								if($debug)$msg_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse_eni.", Taille=".$Cam_eni.", Tac (max)=".$Tactique_eni.")";
								if($Sur_les_toits and mt_rand(0,100)<50)$Tir=0;
								if($Deception and mt_rand(0,100)<25)$Tir=0;
								if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base))
								{
									$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg);
									if($Tir-$Optics >=$Tir_base)
										$Base_Dg=$Arme_Dg-mt_rand(0,10);
									$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
									if($Tenaille)
									{
										if($Trait_o ==3)
											$Degats*=1.5;
										else
											$Degats*=1.25;
									}
									$Degats=round(Get_Dmg($Munition,$Arme_Cal,$Blindage_eni,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
									if($Pos_eni ==1 and $Defense_elastique and mt_rand(0,1) ==1)
									{
										if($Trait ==3)
											$Degats/=4;
										else
											$Degats/=2;
									}
									if($Herisson and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7))
										$Degats/=2;
									if($Gyokusai or $Gyokusai_eni)
										$Degats*=2;
									if($Pos_eni ==8 and $Categorie ==5)
										$Degats*=2;
									if($Raid)
									{
										if($Trait_o !=2)
											$Degats/=2;
									}
									if($Degats >$HP_eni or ($Encercle and $Degats >($HP_eni/2)))
									{
										if($Arme ==136 and $Categorie_eni ==5)
										{									
											$Collateral=1+(floor($Degats/$HP_eni/10));
											$Update_Nbr_eni-=$Collateral;
											$Veh_Nbr_eni-=$Collateral;
										}
										else
										{
											$Veh_Nbr_eni-=1;
											$Update_Nbr_eni-=1;
										}									
										$Update_Reput+=$Reput_eni;
										$msg.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!<br><b>La cible est détruite!</b>";
										$HP_eni=$HP_ori_eni;
										if($mobile_eni ==5 and $Pos_eni ==20)
											break;
									}
									elseif($Degats >0)
									{
										$Update_xp+=1;
										$msg.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!";
										$HP_eni-=$Degats;
									}
									else
										$msg.="<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
									AddEventPVP(1,$Reg_eni,$Reg,$Degats,$Battle);
								}
								else
									$msg.="<br>Votre unité rate la cible!";
							}
							if($Arme_Multi)
								UpdateData("Regiment_PVP",$Stock_up,$Arme_Multi,"ID",$Reg);
							if($Update_Nbr_eni <0)
							{
								UpdateData("Regiment_PVP","Vehicule_Nbr",$Update_Nbr_eni,"ID",$Reg_eni);
								SetData("Regiment_PVP","Visible",0,"ID",$Reg_eni);
								AddGroundAtkPVP($Reg,$Reg_eni,$Veh,$Veh_Nbr_atk,$Vehicule,$Veh_Nbr_eni,4,$Pos_eni,$Lieu,$Placement_eni,$Reg_Officier_ID,$Officier_eni,$Dist_shoot,-$Update_Nbr_eni);
							}
						}
						else
							$msg.="<br>Votre unité annule son attaque, faute de munitions!";
					}
					else
						$msg.="<br>Votre unité annule son attaque, faute d'armement adéquat!";
					if($Pos_eni ==8 and $Zone !=6)
						SetData("Regiment_PVP","Position",9,"ID",$Reg_eni);
					elseif($Pos_eni ==9)
					{
						if($Categorie ==8)
							$Pos_final=5;
						elseif($Categorie ==2 or $Categorie ==3)
							$Pos_final=1;
						else
							$Pos_final=10;
						SetData("Regiment_PVP","Position",$Pos_final,"ID",$Reg_eni);
					}
					//Tir eni
					$Veh_Nbr_eni=GetData("Regiment_PVP","ID",$Reg_eni,"Vehicule_Nbr");
					if($Veh_Nbr_eni >0)
					{
						if(($Dist_eni >=$Dist_shoot) or ($Range_Battle_eni >=$Dist_shoot and $Fire_eni) or !$Repli)
						{
							if(!$Repli)$Dist_shoot=$Dist_eni+mt_rand(-100,100);;
							$msg_eni.="<br>L'ennemi riposte à une distance d'environ ".$Dist_shoot."m";
							//Riposte
							if($HP_eni >0)
							{
								$Muns_Stock=9999;
								$Stock_up="Stock_Munitions_8";
								if($Zone ==6)
								{
									$Arme=$Arme_Art_eni;
									$Muns_Stock=($Arme_Art_eni_mun-$Stock_Art_eni)*$Veh_Nbr_eni;
									$Stock_up="Stock_Art";
								}
								elseif($Blindage and $Arme_AT_eni and $Arme_AT_eni !=82 and $Munition_eni !=8)
								{
									$Arme=$Arme_AT_eni;
									$Muns_Stock=($Arme_AT_eni_mun-$Stock_AT_eni)*$Veh_Nbr_eni;
									$Stock_up="Stock_AT";
								}
								elseif($Arme_Art_eni and $Arme_Art_eni !=82 and $Range_eni <3000 and $Munition_eni !=7)
								{
									$Arme=$Arme_Art_eni;
									$Muns_Stock=($Arme_Art_eni_mun-$Stock_Art_eni)*$Veh_Nbr_eni;
									$Stock_up="Stock_Art";
								}
								elseif($Munition_eni <7)
									$Arme=$Arme_Inf_eni;
								else
									$Arme=0;
								if($Arme >0)
								{
									$con=dbconnecti();
									$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
									mysqli_close($con);
									if($result3)
									{
										while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
										{
											$Arme_Nom=$data3['Nom'];
											$Arme_Cal=round($data3['Calibre']);
											$Arme_Multi=$data3['Multi'];
											$Arme_Dg=$data3['Degats'];
											$Arme_Perf=$data3['Perf'];
											$Arme_Portee=$data3['Portee'];
											$Arme_Portee_Max=$data3['Portee_max'];
										}
										mysqli_free_result($result3);
									}	
									$Muns_Conso=$Veh_Nbr_eni*$Arme_Multi;									
									if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
									{
										$msg_eni.="<br>L'unité ennemie tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
										//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage,$Munition,$Dist_shoot);
										$Update_Nbr=0;
										$Update_Reput_eni=0;
										$Update_xp_eni=0;										
										if($Veh_Nbr_eni >25)$Veh_Nbr_eni=floor($Veh_Nbr_eni/10);
										for($t=1;$t<=$Veh_Nbr_eni;$t++)
										{
											if($Veh_Nbr_atk <1)break;
											$Tir=mt_rand(0,$Tir_base_eni)+$Optics_eni;
											$Defense_tir=$Vitesse+mt_rand(0,$Tactique)-$Cam-$Meteo;
											if($debug)$msg_eni_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse.", Taille=".$Cam.", Tac (max)=".$Tactique.")";
											if($Raid and mt_rand(0,1) ==1)$Tir=0;
											if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base_eni))
											{
												$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg);
												if($Tir-$Optics_eni >=$Tir_base_eni)$Base_Dg=$Arme_Dg-mt_rand(0,10);
												$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
												$Degats=round(Get_Dmg($Munition_eni,$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
												if($Gyokusai or $Gyokusai_eni)
													$Degats*=2;
												if($Enfilade_eni)
													$Degats*=2;
												if($Tranchees_eni)
													$Degats*=2;
												if($Flanc_Garde_eni and $Tenaille)
													$Degats*=2;
												if($Categorie_eni ==6 and $Categorie ==5)
													$Degats*=2;
												if($Degats >$HP)
												{
													$Veh_Nbr-=1;
													$Veh_Nbr_atk-=1;
													$Update_Nbr-=1;
													$Update_Reput_eni+=$Reput;
													$msg_eni.="<br>Votre unité est touchée et subit <b>".$Degats."</b> dégâts!<br><b>Une de vos unités est détruite!</b>";
													$HP=$HP_ori;
												}
												elseif($Degats >1)
												{
													$Update_xp_eni+=1;
													$msg_eni.="<br>Votre unité est touchée et subit <b>".$Degats."</b> dégâts!";
													$HP-=$Degats;
												}
												else
													$msg_eni.="<br>Votre unité est touchée, mais le blindage n'a pas été percé!";
												AddEventPVP(1,$Reg,$Reg_eni,$Degats,$Battle);
											}
											else
												$msg_eni.="<br>L'ennemi tire à côté!";
										}
										if($Arme_Multi)
											UpdateData("Regiment_PVP",$Stock_up,$Arme_Multi,"ID",$Reg_eni);
										if($Update_Nbr <0)
										{
											UpdateData("Regiment_PVP","Vehicule_Nbr",$Update_Nbr,"ID",$Reg);
											AddGroundAtkPVP($Reg_eni,$Reg,$Vehicule,$Veh_Nbr_eni,$Veh,$Veh_Nbr_atk,$Pos_eni,4,$Lieu,$Placement_eni,$Officier_eni,$Reg_Officier_ID,$Dist_shoot,-$Update_Nbr);
										}
									}
									else
										$msg_eni.="<br>L'ennemi, à court de munitions, ne peut riposter!";
								}
								else
									$msg_eni.="<br>L'ennemi, pris de flanc, ne peut riposter!";
							}
						}
						else
							$msg_eni.="<br>L'ennemi ne riposte pas!";
					}
					else
						$msg_eni.="<br>L'ennemi, totalement en déroute, ne peut riposter!";
				}
				else //ICI
				{		
					$Veh_Nbr_eni=GetData("Regiment_PVP","ID",$Reg_eni,"Vehicule_Nbr");
					if($Veh_Nbr_eni >0)
					{
						if($Fire_eni or $Dist_shoot <=$Dist_eni or !$Repli)
						{
							$msg.="L'ennemi a l'initiative et engage vos troupes à une distance d'environ ".$Dist_shoot."m";
							$msg_eni.="<br>L'ennemi a l'initiative et engage vos troupes à une distance d'environ ".$Dist_shoot."m";
							//Riposte
							if($HP_eni >0)
							{
								$Muns_Stock=9999;
								$Stock_up="Stock_Munitions_8";
								if($Zone ==6)
								{
									$Arme=$Arme_Art_eni;
									$Muns_Stock=($Arme_Art_eni_mun-$Stock_Art_eni)*$Veh_Nbr_eni;
									$Stock_up="Stock_Art";
								}
								elseif($Blindage >0 and $Arme_AT_eni and $Arme_AT_eni !=82 and $Munition_eni !=8)
								{
									$Arme=$Arme_AT_eni;
									$Muns_Stock=($Arme_AT_eni_mun-$Stock_AT_eni)*$Veh_Nbr_eni;
									$Stock_up="Stock_AT";
								}
								elseif($Arme_Art_eni >0 and $Arme_Art_eni !=82 and $Range_eni <3000 and $Munition_eni !=7)
								{
									$Arme=$Arme_Art_eni;
									$Muns_Stock=($Arme_Art_eni_mun-$Stock_Art_eni)*$Veh_Nbr_eni;
									$Stock_up="Stock_Art";
								}
								elseif($Munition_eni <7)
									$Arme=$Arme_Inf_eni;
								else
									$Arme=0;
								if($Arme >0)
								{
									$con=dbconnecti();
									$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
									mysqli_close($con);
									if($result3)
									{
										while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
										{
											$Arme_Nom=$data3['Nom'];
											$Arme_Cal=round($data3['Calibre']);
											$Arme_Multi=$data3['Multi'];
											$Arme_Dg=$data3['Degats'];
											$Arme_Perf=$data3['Perf'];
											$Arme_Portee=$data3['Portee'];
											$Arme_Portee_Max=$data3['Portee_max'];
										}
										mysqli_free_result($result3);
									}
									$Muns_Conso=$Veh_Nbr_eni*$Arme_Multi;									
									if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
									{
										$msg_eni.="<br>L'unité ennemie tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
										//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage,$Munition,$Dist_shoot);
										$Update_Nbr=0;
										$Update_Reput_eni=0;
										$Update_xp_eni=0;
										if($Veh_Nbr_eni >25)$Veh_Nbr_eni=floor($Veh_Nbr_eni/10);
										for($t=1;$t<=$Veh_Nbr_eni;$t++)
										{
											if($Veh_Nbr_atk <1)
												break;
											$Tir=mt_rand(0,$Tir_base_eni)+$Optics_eni+(10-($Dist_shoot/100))+$Tir_base_eni; //Bonus init
											$Defense_tir=$Vitesse+mt_rand(0,$Tactique)-$Cam-$Meteo;
											if($debug)$msg_eni_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse.", Taille=".$Cam.", Tac (max)=".$Tactique.")";
											if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base_eni))
											{
												$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg);
												//if($Tir-$Optics_eni >=$Tir_base_eni)$Base_Dg=$Arme_Dg-mt_rand(0,10);
												if($Base_Dg ==$Arme_Dg)$Base_Dg=$Arme_Dg-mt_rand(0,10);
												$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
												$Degats=round(Get_Dmg($Munition_eni,$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
												if($Gyokusai or $Gyokusai_eni)$Degats*=2;
												if($Enfilade_eni)$Degats*=2;
												if($Tranchees_eni)$Degats*=2;
												if($Flanc_Garde_eni and $Tenaille)$Degats*=2;
												if($Categorie_eni ==6 and $Categorie ==5)$Degats*=2;
												if($Degats >$HP)
												{
													if($Categorie_eni ==6 and $Categorie ==5)
														$Collateral=1+(floor($Degats/$HP/10));
													else
														$Collateral=1;													
													$Veh_Nbr-=$Collateral;
													$Veh_Nbr_atk-=$Collateral;
													$Update_Nbr-=$Collateral;
													$Update_Reput_eni+=$Reput;
													$msg_eni.="<br>Votre unité est touchée et subit <b>".$Degats."</b> dégâts!<br><b>Une de vos unités est détruite!</b>";
													$HP=$HP_ori;
												}
												elseif($Degats >1)
												{
													$Update_xp_eni+=1;
													$msg_eni.="<br>Votre unité est touchée et subit <b>".$Degats."</b> dégâts!";
													$HP-=$Degats;
												}
												else
													$msg_eni.="<br>Votre unité est touchée, mais le blindage n'a pas été percé!";
												AddEventPVP(1,$Reg,$Reg_eni,$Degats,$Battle);
											}
											else
												$msg_eni.="<br>L'ennemi tire à côté!";
										}
										if($Arme_Multi)
											UpdateData("Regiment_PVP",$Stock_up,$Arme_Multi,"ID",$Reg_eni);
										if($Update_Nbr <0)
										{
											UpdateData("Regiment_PVP","Vehicule_Nbr",$Update_Nbr,"ID",$Reg);
											AddGroundAtkPVP($Reg_eni,$Reg,$Vehicule,$Veh_Nbr_eni,$Veh,$Veh_Nbr_atk,$Pos_eni,4,$Lieu,$Placement_eni,$Officier_eni,$Reg_Officier_ID,$Dist_shoot,-$Update_Nbr);
										}
									}
									else
										$msg_eni.="<br>L'ennemi, à court de munitions, ne peut riposter!";
								}
								else
									$msg_eni.="<br>L'ennemi, pris de flanc, ne peut riposter!";
							}
						}
						else
							$msg_eni.="<br>L'ennemi ne riposte pas!";
					}
					else
						$msg_eni.="<br>L'ennemi, totalement en déroute, ne peut riposter!";
					/*if($Distance_tir <$Dist_shoot)
					{
						$Dist_shoot=$Distance_tir;
						$msg.="<br>L'ennemi a l'initiative et engage vos troupes à une distance d'environ ".$Dist_shoot."m";
						$msg_eni.="<br>L'ennemi a l'initiative et engage vos troupes à une distance d'environ ".$Dist_shoot."m";
						//Riposte
						if($HP_eni >0)
						{
							$Muns_Stock=9999;
							$Stock_up="Stock_Munitions_8";
							if($Zone ==6)
							{
								$Arme=$Arme_Art_eni;
								$Muns_Stock=($Arme_Art_eni_mun-$Stock_Art_eni)*$Veh_Nbr_eni;
								$Stock_up="Stock_Art";
							}
							elseif($Blindage >0 and $Arme_AT_eni and $Arme_AT_eni !=82 and $Munition_eni !=8)
							{
								$Arme=$Arme_AT_eni;
								$Muns_Stock=($Arme_AT_eni_mun-$Stock_AT_eni)*$Veh_Nbr_eni;
								$Stock_up="Stock_AT";
							}
							elseif($Arme_Art_eni >0 and $Arme_Art_eni !=82 and $Range_eni <3000 and $Munition_eni !=7)
							{
								$Arme=$Arme_Art_eni;
								$Muns_Stock=($Arme_Art_eni_mun-$Stock_Art_eni)*$Veh_Nbr_eni;
								$Stock_up="Stock_Art";
							}
							elseif($Munition_eni <7)
								$Arme=$Arme_Inf_eni;
							else
								$Arme=0;
							if($Arme >0)
							{
								$con=dbconnecti();
								$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
								mysqli_close($con);
								if($result3)
								{
									while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
									{
										$Arme_Nom=$data3['Nom'];
										$Arme_Cal=round($data3['Calibre']);
										$Arme_Multi=$data3['Multi'];
										$Arme_Dg=$data3['Degats'];
										$Arme_Perf=$data3['Perf'];
										$Arme_Portee=$data3['Portee'];
										$Arme_Portee_Max=$data3['Portee_max'];
									}
									mysqli_free_result($result3);
								}
								$Muns_Conso=$Veh_Nbr_eni*$Arme_Multi;
								if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
								{
									$msg_eni.="<br>L'unité ennemie tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
									//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage,$Munition,$Dist_shoot);
									$Update_Nbr=0;
									$Update_Reput_eni=0;
									$Update_xp_eni=0;
									if($Veh_Nbr_eni >25)$Veh_Nbr_eni=floor($Veh_Nbr_eni/10);
									for($t=1;$t<=$Veh_Nbr_eni;$t++)
									{
										if($Veh_Nbr_atk <1)break;
										$Tir=mt_rand(0,$Tir_base_eni)+$Optics_eni+(10-($Dist_shoot/100));
										$Defense_tir=$Vitesse+mt_rand(0,$Tactique)-$Cam-$Meteo;
										if($debug)$msg_eni_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse.", Taille=".$Cam.", Tac (max)=".$Tactique.")";
										if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base_eni))
										{
											$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg);
											if($Tir-$Optics_eni >=$Tir_base_eni)$Base_Dg=$Arme_Dg-mt_rand(0,10);
											$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
											$Degats=round(Get_Dmg($Munition_eni,$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
											if($Gyokusai or $Gyokusai_eni)$Degats*=2;
											if($Enfilade_eni)$Degats*=2;
											if($Tranchees_eni)$Degats*=2;
											if($Flanc_Garde_eni and $Tenaille)$Degats*=2;
											if($Categorie_eni ==6 and $Categorie ==5)$Degats*=2;
											if($Degats >$HP)
											{
												$Veh_Nbr-=1;
												$Veh_Nbr_atk-=1;
												$Update_Nbr-=1;
												$Update_Reput_eni+=$Reput;
												$msg_eni.="<br>Votre unité est touchée et subit <b>".$Degats."</b> dégâts!<br><b>Une de vos unités est détruite!</b>";
												$HP=$HP_ori;
											}
											elseif($Degats >1)
											{
												$Update_xp_eni+=1;
												$msg_eni.="<br>Votre unité est touchée et subit <b>".$Degats."</b> dégâts!";
												$HP-=$Degats;
											}
											else
												$msg_eni.="<br>Votre unité est touchée, mais le blindage n'a pas été percé!";
										}
										else
											$msg_eni.="<br>L'ennemi tire à côté!";
									}
									UpdateData("Regiment_PVP",$Stock_up,$Arme_Multi,"ID",$Reg_eni);
									if($Update_Nbr <0)
									{
										UpdateData("Regiment_PVP","Vehicule_Nbr",$Update_Nbr,"ID",$Reg);
										AddGroundAtkPVP($Reg_eni,$Reg,$Vehicule,$Veh_Nbr_eni,$Veh,$Veh_Nbr_atk,$Pos_eni,4,$Lieu,$Placement_eni,$Officier_eni,$Reg_Officier_ID,$Dist_shoot,-$Update_Nbr);
									}
								}
								else
									$msg_eni.="<br>L'ennemi, à court de munitions, ne peut riposter!";
							}
							else
								$msg_eni.="<br>L'ennemi, pris de flanc, ne peut riposter!";
						}
					}*/
					if($Distance_tir <$Dist_shoot and $Repli ==2)
					{
						$msg.="<br>Vos troupes sont refoulées par l'ennemi, ne parvenant pas à s'approcher à la distance de tir voulue!";
						if($Veh_Nbr <1)
							$msg.="<br>Votre unité, en déroute, annule son attaque!";
					}
					else
					{
						if($Repli !=2)$Dist_shoot=$Distance_tir;							
						if($Veh_Nbr_atk >0 and $Range_Battle >=$Dist_shoot)
						{
							$msg.="<br>Vos troupes progressent en direction de l'ennemi, ".$Veh_Nbr_atk." ".$Veh_Nom." (sur une force originale de ".$Veh_Nbr_Ori.") l'engagent à une distance d'environ ".$Dist_shoot."m";
							$Muns_Stock=9999;
							$Stock_up="Stock_Munitions_8";
							if($Armement ==1)
								$Arme=$Arme_Inf;
							elseif($Zone ==6 or $mobile ==5)
							{
								$Arme=$Arme_Art;
								$Muns_Stock=($Arme_Art_mun-$Stock_Art)*$Veh_Nbr_atk;
								$Stock_up="Stock_Art";
							}
							elseif($Blindage_eni >0 and $Arme_AT and $Arme_AT !=82 and $Munition !=8)
							{
								$Arme=$Arme_AT;
								$Muns_Stock=($Arme_AT_mun-$Stock_AT)*$Veh_Nbr_atk;
								$Stock_up="Stock_AT";
								if($Type ==4 or $Type ==9) //Canon AT
								{
									$rand_blindage=mt_rand(0,100)+$Experience;
									if($rand_blindage <60)
										$Blindage_eni=$Blindage_eni;
									elseif($rand_blindage <95)
										$Blindage_eni=$Blindage_l_eni;
									else
										$Blindage_eni=$Blindage_a_eni;
								}
							}
							elseif($Arme_Art >0 and $Arme_Art !=82 and $Range <3000 and $Munition !=7)
							{
								$Arme=$Arme_Art;
								$Muns_Stock=($Arme_Art_mun-$Stock_Art)*$Veh_Nbr_atk;
								$Stock_up="Stock_Art";
							}
							elseif($Munition <7)
								$Arme=$Arme_Inf;
							else
								$Arme=0;
							if($Arme >0)
							{
								$con=dbconnecti();
								$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
								mysqli_close($con);
								if($result3)
								{
									while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
									{
										$Arme_Nom=$data3['Nom'];
										$Arme_Cal=round($data3['Calibre']);
										$Arme_Multi=$data3['Multi'];
										$Arme_Dg=$data3['Degats'];
										$Arme_Perf=$data3['Perf'];
										$Arme_Portee=$data3['Portee'];
										$Arme_Portee_Max=$data3['Portee_max'];
									}
									mysqli_free_result($result3);
								}
								$Muns_Conso=$Veh_Nbr_atk*$Arme_Multi;
								if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
								{
									$msg.="<br>Votre unité tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
									//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage_eni,$Munition,$Dist_shoot);
									$Update_Nbr_eni=0;
									$Update_Reput=0;
									$Update_xp=0;
									if($Veh_Nbr_atk >25)$Veh_Nbr_atk=floor($Veh_Nbr_atk/10);
									for($t=1;$t<=$Veh_Nbr_atk;$t++)
									{
										if($Veh_Nbr_eni <1 or $Update_Nbr_eni <-$Max_Kill)
											break;
										$Tir=mt_rand(0,$Tir_base)+$Optics;
										$Defense_tir=$Vitesse_eni+mt_rand(0,$Tactique_eni)-$Cam_eni-$Meteo;
										if($debug)$msg_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse_eni.", Taille=".$Cam_eni.", Tac (max)=".$Tactique_eni.")";
										if($Raid and mt_rand(0,1) ==1)$Tir=0;
										if($Sur_les_toits and mt_rand(0,100) <50)$Tir=0;
										if($Deception and mt_rand(0,100) <25)$Tir=0;
										if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base))
										{
											$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg);
											if($Tir-$Optics >=$Tir_base)$Base_Dg=$Arme_Dg-mt_rand(0,10);
											$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
											if($Tenaille)
											{
												if($Trait_o ==3)
													$Degats*=1.5;
												else
													$Degats*=1.25;
											}
											$Degats=round(Get_Dmg($Munition,$Arme_Cal,$Blindage_eni,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
											if($Pos_eni ==1 and $Defense_elastique and mt_rand(0,1)==1)
											{
												if($Trait ==3)
													$Degats/=4;
												else
													$Degats/=2;
											}
											if($Herisson and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7))$Degats/=2;
											if($Gyokusai or $Gyokusai_eni)$Degats*=2;
											if($Pos_eni ==8 and $Categorie ==5)$Degats*=2;
											if($Raid)
											{
												if($Trait_o !=2)$Degats/=2;
											}
											if($Degats >$HP_eni or ($Encercle and $Degats >($HP_eni/2)))
											{
												$Veh_Nbr_eni-=1;
												$Update_Nbr_eni-=1;
												$Update_Reput+=$Reput_eni;
												$msg.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!<br><b>La cible est détruite</b>!";
												$HP_eni=$HP_ori_eni;
												if($mobile_eni ==5 and $Pos_eni ==20)
													break;
											}
											elseif($Degats >0)
											{
												$Update_xp+=1;
												$msg.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!";
												$HP_eni-=$Degats;
											}
											else
												$msg.="<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
											AddEventPVP(1,$Reg_eni,$Reg,$Degats,$Battle);
										}
										else
											$msg.="<br>Votre unité rate la cible!";
									}
									if($Arme_Multi)
										UpdateData("Regiment_PVP",$Stock_up,$Arme_Multi,"ID",$Reg);
									if($Update_Nbr_eni <0)
									{
										UpdateData("Regiment_PVP","Vehicule_Nbr",$Update_Nbr_eni,"ID",$Reg_eni);
										SetData("Regiment_PVP","Visible",0,"ID",$Reg_eni);
										AddGroundAtkPVP($Reg,$Reg_eni,$Veh,$Veh_Nbr_atk,$Vehicule,$Veh_Nbr_eni,4,$Pos_eni,$Lieu,$Placement_eni,$Reg_Officier_ID,$Officier_eni,$Dist_shoot,-$Update_Nbr_eni);
									}
								}
								else
									$msg.="<br>Votre unité annule son attaque, faute de munitions!";
							}
							else
								$msg.="<br>Votre unité annule son attaque, faute d'armement adéquat!";
						}
						elseif($Veh_Nbr <1)
							$msg.="<br>Votre unité, en déroute, annule son attaque!";
						else
							$msg.="<br>Votre unité se replie!";
					}
				}
			}
			else
			{
				if($Smoke ==true)
					$msg.="<br>L'ennemi prend la fuite derrière un écran de fumée! Vous ne pouvez poursuivre votre attaque!";
				elseif($Veh_Nbr <1)
					$msg.="<br>Votre unité, en déroute, annule son attaque!";
				else
					$msg.="<p>Votre unité est incapable d'attaquer la cible!</p>";
			}
			if($Veh_Nbr <1)
				$msg.="<br>Votre unité, en déroute, annule son attaque!";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Visible=1,Position=4 WHERE ID='$Reg'");
			mysqli_close($con);
			$titre="Combat <span><img src='images/help.png' title=\"L'expérience de votre unité améliore l'efficacité du tir. Plus une unité est rapide (vitesse modifiée) et plus sa taille est réduite, plus elle est difficile à toucher. La vitesse d'une unité retranchée est nulle, mais sa taille est divisée par 4. La nature du terrain et le système de propulsion modifie la vitesse, tandis que le camouflage modifie la taille.\</span></a>";
			$mes="<table class='table table-striped'>
			<thead><tr><th></th><th>Votre Compagnie</th><th>".$Reg_eni."e Compagnie</th></tr></thead>
			<tr><td align='left'>Troupes</td><td><img src='images/vehicules/vehicule".$Veh.".gif' title='".$Veh_Nom."'></td><td><img src='images/vehicules/vehicule".$Vehicule.".gif' title='".$Veh_Nom_eni."'></td></tr>
			<tr><td align='left'>Nation</td><td><img src='images/".$country."20.gif'></td><td><img src='images/".$Pays_eni."20.gif'></td></tr>
			<tr><td align='left'>Terrain</td><td colspan='2'><img src='images/zone".$Zone.".jpg'></td></tr>
			<tr><td align='left'>Position</td><td>".GetPosGr($Pos)."</td><td>".GetPosGr($Pos_eni)."</td></tr>
			<tr><td align='left'>Tir</td><td>".$Tir_base."</td><td>".$Tir_base_eni."</td></tr>
			<tr><td align='left'>Camouflage</td><td>".round($Cam)."</td><td>".round($Cam_eni)."</td></tr>
			<tr><td align='left'>Blindage</td><td>".$Blindage."</td><td>".$Blindage_eni."</td></tr>
			<tr><td align='left'>Vitesse</td><td>".$Vitesse."km/h</td><td>".$Vitesse_eni."km/h</td></tr>
			<tr><td>Résumé</td><td align='left'>".$msg."</td><td align='left'>".$msg_eni."</td></tr>
			<tr><td>Pertes</td><td>".$Update_Nbr."</td><td>".$Update_Nbr_eni."</td></tr>
			</table>";
			$_SESSION['ground_bomb']=false;
			if($Admin ==1)
			{
				$mes.="<div class='row'><div class='col-md-1'></div><div class='col-md-1'></div><div class='col-md-1'></div><div class='col-md-1'></div><div class='col-md-1'></div><div class='col-md-1'></div>
				<div class='col-md-1'>".$graph_reg."</div><div class='col-md-1'></div><div class='col-md-1'>".$graph_reg_eni."</div><div class='col-md-1'>".$graph_AT_eni."</div><div class='col-md-1'>".$graph_couv_eni."</div><div class='col-md-1'>".$graph_art_eni."</div></div>";
			}
			$menu="<a href='index.php?view=ground_menu_pvp' class='btn btn-default' title='Retour'>Retour au menu</a>";
			include_once('./default.php');
			$msg_eni_debug.="<br> En ligne=".$Ligne;
		}
		else
			echo "<h6>Erreur de sélection de véhicules!</h6> [Code d'erreur] : ".$Veh."/".$Vehicule."/".$Reg."/".$Reg_eni;
	}
}
?>