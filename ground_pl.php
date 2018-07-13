<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0 xor $OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_combat.inc.php');
	$Action=Insec($_POST['Action']);
	$Reg=Insec($_POST['Reg']);
	$Pass=Insec($_POST['Pass']);
	$Ligne=Insec($_POST['Line']);
	$Armement=Insec($_POST['armement']);
	$Distance_tir=Insec($_POST['distance']);
	$Max_Range=Insec($_POST['Max_Range']);
	$Repli=Insec($_POST['repli']);
	$Cr_mini=Insec($_POST['CT']);
	$Mode=Insec($_POST['Mode']);
	if($OfficierID >0)
	{
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT Credits,Trait,Skill4,Division,Reputation FROM Officier WHERE ID='$OfficierID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl-off');
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Credits=$data['Credits'];
				$Trait_o=$data['Trait'];
				$Skill4=$data['Skill4'];
				$Division=$data['Division'];
				$Reputation=$data['Reputation'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
        $redirect='ground_menu';
	}
	else
	    $redirect='ground_em_ia_list';
	if(!$Cr_mini)$Cr_mini=4;
	if(!$Action or !$Reg)
	{
		echo 'Vous annulez votre action';
		header("Location: ./index.php?view=".$redirect);
	}
	elseif($Credits >=$Cr_mini or $Cr_mini >97)
	{
		$debug=1;
		$Gyokusai=false;
		$Gyokusai_eni=false;
		$Raid=false;
		$Tenaille=false;
		$Encercle=false;
		$Avant_Garde=false;
		$Barrage_eni=false;
		$Sur_les_toits=false;
		$Matos_mun=array(1,2,6,7,8);
		$Heure=date('H');
		$country=$_SESSION['country'];
		$Veh=Insec($_POST['Veh']);		
		$Reg_eni=strstr($Action,'_',true);
		$Officier_eni=strstr($Action,'_');
		//if($Officier_eni =="_0")
			$DB='Regiment_IA';
		/*else
			$DB="Regiment";
		if(!$Cr_mini or $Cr_mini ==98)*/
			$DB_reg='Regiment_IA';
		/*else
			$DB_reg="Regiment";
		if($OfficierID >0)
			$Premium=GetData("Joueur","Officier",$OfficierID,"Premium");
		elseif($OfficierEMID >0)*/
			$Premium=GetData("Joueur","Officier_em",$OfficierEMID,"Premium");
		$skills="<h2>Premium</h2><div style='width:25%;'>";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT ID,Officier_ID,Lieu_ID,Vehicule_ID,Vehicule_Nbr,Position,Experience,Skill,Matos,Move,Camouflage,Moral,HP,Stock_Essence_87,Stock_Essence_1,Muns,Atk FROM $DB_reg WHERE ID='$Reg'");
		$result2=mysqli_query($con,"SELECT Officier_ID,Pays,Vehicule_ID,Vehicule_Nbr,Position,Placement,Experience,Skill,Matos,Move,Camouflage,Moral,HP,Stock_Essence_87,Stock_Essence_1,Muns,Distance,Fire,Fret,Fret_Qty FROM $DB WHERE ID='$Reg_eni'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Reg=$data['ID'];
				$Reg_Officier_ID=$data['Officier_ID'];
				$Experience_ori=$data['Experience'];
				$Experience=floor(($Experience_ori/10)+10);
				$Skill=$data['Skill'];
				$Tir_base=$Experience;
				$Veh_Nbr_Ori=$data['Vehicule_Nbr'];
				$Veh=$data['Vehicule_ID'];
				$Lieu=$data['Lieu_ID'];
				$Camouflage=$data['Camouflage'];
				$Moral=$data['Moral'];
				$HP_navire=$data['HP'];
				$Essence=$data['Stock_Essence_87'];
				$Diesel=$data['Stock_Essence_1'];
				if(in_array($data['Matos'],$Matos_mun))
					$Munition=$data['Matos'];
				else
					$Munition=$data['Muns'];
				$Matos=$data['Matos'];
				$Pos=$data['Position'];
				if($data['Position'] ==1 or $data['Position'] ==3 or $data['Position'] ==7 or $data['Position'] ==8)
					$Tactique=$Experience/2;
				elseif($data['Position'] ==2 or $data['Position'] ==6 or $data['Position'] ==9 or $data['Position'] ==10 or $data['Position'] ==26)
					$Tactique=0;
				else
					$Tactique=$Experience;
                if($Matos ==11)$Camouflage*=1.1;
				if($Skill ==29 or $Skill ==25 or $Skill ==6)$Camouflage*=1.1;
				elseif($Skill ==126 or $Skill ==129 or $Skill ==51)$Camouflage*=1.2;
				elseif($Skill ==127 or $Skill ==130 or $Skill ==80)$Camouflage*=1.3;
				elseif($Skill ==128 or $Skill ==131 or $Skill ==81)$Camouflage*=1.4;
				if($DB_reg =='Regiment_IA'){
                    $Atk=$data['Atk'];
                    $Move=1;
                }
				else
					$Move=$data['Move'];
			}
			mysqli_free_result($result);
			unset($data);
			$Veh_Nbr_Final=$Veh_Nbr_Ori;
			if($Pass >0 and $Pass <=$Veh_Nbr_Ori)
				$Veh_Nbr_atk=floor($Pass);
			else
				$Veh_Nbr_atk=$Veh_Nbr_Ori;
		}
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Officier_eni=$data2['Officier_ID'];
				$Exp_eni_ori=$data2['Experience'];
				$Exp_eni=floor(($Exp_eni_ori/10)+10);
				$Skill_eni=$data['Skill'];
				$Tir_base_eni=$Exp_eni;
				$Pays_eni=$data2['Pays'];	
				$Veh_Nbr_eni=$data2['Vehicule_Nbr'];
				$Vehicule=$data2['Vehicule_ID'];
				$Placement_eni=$data2['Placement'];
				$Camouflage_eni=$data2['Camouflage'];
				$Moral_eni=$data2['Moral'];
				$HP_eni_navire=$data2['HP'];
				$Essence_eni=$data2['Stock_Essence_87'];
				$Diesel_eni=$data2['Stock_Essence_1'];
				if(in_array($data2['Matos'],$Matos_mun))
					$Munition_eni=$data2['Matos'];
				else
					$Munition_eni=$data2['Muns'];
				$Matos_eni=$data2['Matos'];
				$Dist_eni=$data2['Distance'];
				$Fire_eni=$data2['Fire'];
				$Fret_eni=$data['Fret'];
				$Fret_Qty_eni=$data['Fret_Qty'];
				$Pos_eni=$data2['Position'];
				if($data2['Position'] ==1 or $data2['Position'] ==3 or $data2['Position'] ==10)
					$Tactique_eni=$Exp_eni*2;
				elseif($data2['Position'] ==2)
					$Tactique_eni=$Exp_eni*4;
				elseif($data2['Position'] ==4 or $data2['Position'] ==5 or $data2['Position'] ==6 or $data2['Position'] ==7 or $data2['Position'] ==8 or $data2['Position'] ==11 or $data['Position'] ==26)
					$Tactique_eni=$Exp_eni/2;
				else
					$Tactique_eni=$Exp_eni;
				if($DB =="Regiment_IA")
					$Move_eni=1;
				else
					$Move_eni=$data2['Move'];
			}
			mysqli_free_result($result2);
			if($Officier_eni >0)
			{
				$resulto=mysqli_query($con,"SELECT Trait,Avancement,Reputation,Transit FROM Officier WHERE ID='$Officier_eni'");
				if($resulto)
				{
					while($data=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
					{
						$Trait_eni=$data['Trait'];
						$Transit_eni=$data['Transit'];
						$Grade_eni=$data['Avancement'];
						$Level_eni=$data['Reputation']+5000;
					}
					mysqli_free_result($resulto);
				}
				if($Grade_eni >$Level_eni)$Level_eni=$Grade_eni;
				$Max_Kill=$Veh_Nbr_eni/10000*$Level_eni;
			}
			else
			{
                if($Matos_eni ==11)$Camouflage_eni*=1.1;
				if($Skill_eni ==29)$Camouflage_eni*=1.1;
				elseif($Skill_eni ==126)$Camouflage_eni*=1.2;
				elseif($Skill_eni ==127)$Camouflage_eni*=1.3;
				elseif($Skill_eni ==128)$Camouflage_eni*=1.4;
				$Max_Kill=$Veh_Nbr_eni;
			}
			$Veh_Nbr_eni_Ori=$Veh_Nbr_eni;
		}
		$result3=mysqli_query($con,"SELECT Nom,Zone,Flag,Meteo,Fortification FROM Lieu WHERE ID='$Lieu'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl-lieu');
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
		if($Veh and $Vehicule and !$Atk)
		{
			//Get Vehicule
			$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
			$result=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Veh'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl-veh');
			$result2=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Vehicule'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pl-veheni');
			$units_eni_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3,5,10) AND r.Placement='$Placement_eni'"),0);
			$units_allies_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction='$Faction' AND r.Vehicule_Nbr >0 AND r.Position=5 AND r.Placement='$Placement_eni'"),0);
			if($OfficierID)
				$Bonus_Sec=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Sections as s WHERE s.SectionID IN(2,6) AND s.OfficierID=r.Officier_ID AND r.Lieu_ID='$Lieu' AND s.OfficierID='$OfficierID'"),0);
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
					$Optics=$data['Optics'];
					if($Matos ==9)$Optics+=5;
					elseif($Matos ==12)$Optics+=10;
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
					if($Carbu ==87 and !$Essence)
						$Move=0;
					elseif($Carbu ==1 and !$Diesel)
						$Move=0;
					elseif(!$Carbu and !$Moral)
						$Move=0;
					$Cam=$Taille/$Camouflage/$Cam_bonus;
					$Vitesse_ori=$Vitesse;
					if($OfficierID >0 and !$Move)
						$Vitesse=0;
					else
					{
						$Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type,0,$data['Sol_meuble']);
						if($Flag ==$country)$Vitesse+=10;
						if($Matos ==10)$Vitesse*=1.1;
						if($Matos ==14)$Vitesse*=1.5;
                        if($Matos ==30)$Vitesse/=1.25;
					}
				}
				mysqli_free_result($result);
				unset($data);
				if($OfficierID >0){
					/*if($Categorie ==5){
						if(IsSkill(20,$OfficierID))
							$Gyokusai=true;
						if(IsSkill(26,$OfficierID)){
							$Tactique_eni=$Exp_eni;
							$msg.="<p>Vos troupes bénéficient de votre compétence <b>Sturmtruppen</b> !</p>";
						}
					}
					if($mobile !=3){
						if(IsSkill(2,$OfficierID) and mt_rand(0,100)<25)
							$Avant_Garde=true;
					}*/
				}
				else
				{
					if($Skill ==26 or $Skill ==120 or $Skill ==121 or $Skill ==122){
						$Tactique_eni=$Exp_eni;
						$msg.="<p>Vos troupes bénéficient de la compétence <b>Sturmtruppen</b> !</p>";
					}
					elseif($Skill ==71 and mt_rand(0,100) <80)$Avant_Garde=true;
					elseif($Skill ==70 and mt_rand(0,100) <60)$Avant_Garde=true;
					elseif($Skill ==46 and mt_rand(0,100) <40)$Avant_Garde=true;
					elseif($Skill ==2 and mt_rand(0,100) <20)$Avant_Garde=true;
				}
				$graph_reg=$Veh_Nbr_atk."/".$Veh_Nbr_Ori." ".GetVehiculeIcon($Veh,$country,0,0,$Front)."<br>".$Range."m";
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
					$Optics_eni=$data['Optics'];
					if($Matos_eni ==9)$Optics_eni+=5;
					elseif($Matos_eni ==12)$Optics_eni+=10;
					$Blindage_t_eni=$data['Blindage_t'];
					$Blindage_eni=$data['Blindage_f'];
					$Blindage_l_eni=$data['Blindage_l'];
					$Vitesse_eni=$data['Vitesse'];
					$Taille_eni=$data['Taille'];
					$mobile_eni=$data['mobile'];
					$Reput_eni=$data['Reput'];
					$Type_eni=$data['Type'];
					$Range_eni=$data['Portee'];
					$Carbu_eni=$data['Carbu_ID'];
					$Charge_eni=$data['Charge'];
					$Categorie_eni=$data['Categorie'];
					$Sol_meuble_eni=$data['Sol_meuble'];
					$Tactique_eni+=(($data['Radio']*5)+($data['Tourelle']*5));
					if($Carbu_eni ==87 and !$Essence_eni)
						$Move_eni=0;
					elseif($Carbu_eni ==1 and !$Diesel_eni)
						$Move_eni=0;
					elseif(!$Carbu_eni and !$Moral_eni)
						$Move_eni=0;
					if($Trait_eni ==5)
						$Cam_bonus_eni=2;
					else
						$Cam_bonus_eni=1;
					if($Pos_eni !=POS_DEROUTE and $Pos_eni !=POS_TRANSIT)
					{
						if($Officier_eni >0){
							/*if($Zone ==8 and IsSkill(100,$Officier_eni))
								$Cam_bonus_eni+=1;
							if($Pos_eni ==2 and IsSkill(9,$Officier_eni)){
								$Deception=true;
								$Deception_rate=25;
							}
							if($mobile_eni ==1 or $mobile_eni ==2 or $mobile_eni ==6 or $mobile_eni ==7){
								if(IsSkill(10,$Officier_eni)){
									$Defense_elastique=true;
									$Defense_rate=50;
								}
							}
							elseif($Pos_eni ==2 or $Pos_eni ==3 or $Pos_eni ==10){
								if(IsSkill(11,$Officier_eni)){
									$Herisson=true;
									$Herisson_rate=0.5;
								}
							}
							if($Pos_eni !=4 and ($Type_eni ==4 or $Type_eni ==6 or $Type_eni ==12)){
								$Pak_front_rate=1;
								if(IsSkill(12,$Officier_eni) and !IsSkill(24,$OfficierID))
									$Pak_front=true;
							}*/
						}
						else
						{
							if($Pos_eni ==POS_RETRANCHE)
							{
								if($Skill_eni ==87){
									$Deception=true;
									$Deception_rate=20;
								}
								elseif($Skill_eni ==86){
									$Deception=true;
									$Deception_rate=15;
								}
								elseif($Skill_eni ==54){
									$Deception=true;
									$Deception_rate=10;
								}
								elseif($Skill_eni ==9){
									$Deception=true;
									$Deception_rate=5;
								}
							}
							if($mobile_eni ==1 or $mobile_eni ==2 or $mobile_eni ==6 or $mobile_eni ==7)
							{
								if($Skill_eni ==89){
									$Defense_elastique=true;
									$Defense_rate=25;
								}
								elseif($Skill_eni ==88){
									$Defense_elastique=true;
									$Defense_rate=20;
								}
								elseif($Skill_eni ==55){
									$Defense_elastique=true;
									$Defense_rate=15;
								}
								elseif($Skill_eni ==10){
									$Defense_elastique=true;
									$Defense_rate=10;
								}
							}
							elseif($Pos_eni ==POS_RETRANCHE or $Pos_eni ==POS_EMBUSCADE or $Pos_eni ==POS_EN_LIGNE)
							{
								if($Skill_eni ==91){
									$Herisson=true;
									$Herisson_rate=0.6;
								}
								elseif($Skill_eni ==90){
									$Herisson=true;
									$Herisson_rate=0.7;
								}
								elseif($Skill_eni ==56){
									$Herisson=true;
									$Herisson_rate=0.8;
								}
								elseif($Skill_eni ==11){
									$Herisson=true;
									$Herisson_rate=0.9;
								}
								if($Categorie_eni ==5 or $Categorie_eni ==6)
								{
									if($Skill ==122){
										$Sturm=true;
										$Sturm_rate=1.5;
									}
									if($Skill_eni ==121){
										$Sturm=true;
										$Sturm_rate=1.4;
									}
									if($Skill_eni ==120){
										$Sturm=true;
										$Sturm_rate=1.3;
									}
									if($Skill_eni ==26){
										$Sturm=true;
										$Sturm_rate=1.2;
									}
								}
							}
							if($Pos_eni !=POS_MOVE and ($Type_eni ==TYPE_AT_GUN or $Type_eni ==TYPE_ART or $Type_eni ==TYPE_DCA))
							{
								if($Skill_eni ==93){
									$Pak_front=true;
									$Pak_front_rate=1.25;
								}
								elseif($Skill_eni ==92){
									$Pak_front=true;
									$Pak_front_rate=1.2;
								}
								elseif($Skill_eni ==57){
									$Pak_front=true;
									$Pak_front_rate=1.15;
								}
								elseif($Skill_eni ==12){
									$Pak_front=true;
									$Pak_front_rate=1.1;
								}
							}
							if($Categorie ==3)
							{
								if($Skill ==111){
									$Panzerkeil=true;
									$Pak_front=false;
									$Panzerkeil_rate=0.75;
								}
								elseif($Skill ==110){
									$Panzerkeil=true;
									$Panzerkeil_rate=0.8;
								}
								elseif($Skill ==66){
									$Panzerkeil=true;
									$Panzerkeil_rate=0.85;
								}
								elseif($Skill ==24){
									$Panzerkeil=true;
									$Panzerkeil_rate=0.9;
								}
							}
						}
					}
					$Cam_eni=$Taille_eni/$Camouflage_eni/$Cam_bonus_eni;
					if($Officier_eni >0 and !$Move_eni)
						$Vitesse_eni=0;
					else
					{
						$Vitesse_eni=Get_LandSpeed($Vitesse_eni,$mobile_eni,$Zone,$Pos_eni,$Type_eni,0,$Sol_meuble_eni);
                        if($Flag ==$Pays_eni){
                            $Vitesse_eni+=10;
                            if($Placement_eni ==PLACE_CASERNE and $Fortification >0){
                                $Blindage_eni+=Get_Blindage($Zone,$Cam_eni,$Fortification,$Pos_eni);
                                $msg_eni.='<p>Les troupes ennemies bénéficient d\'une protection supplémentaire dûe aux fortifications</p>';
                            }
                        }
						if($Pos_eni ==POS_RETRANCHE and !$Fortification){
                            $Blindage_eni+=Get_Blindage($Zone,$Cam_eni,0,2);
                            $msg_eni.='<p>Les troupes ennemies bénéficient d\'une protection supplémentaire dûe au retranchement</p>';
                        }
						if($Matos_eni ==10)$Vitesse_eni*=1.1;
						elseif($Matos_eni ==14)$Vitesse_eni*=1.5;
                        elseif($Matos_eni ==30)$Vitesse_eni/=1.25;
					}
					if($Vitesse >$Vitesse_eni)
					{
						if($Skill ==16)
							$Tenaille=1.1;
						elseif($Skill ==60)
							$Tenaille=1.15;
						elseif($Skill ==98)
							$Tenaille=1.2;
						elseif($Skill ==99)
							$Tenaille=1.25;
						if($Tenaille)$msg.='<p>Vos troupes bénéficient de la compétence <b>Tenaille</b> !</p>';
					}
				}
				mysqli_free_result($result2);
				unset($data);
				if($mobile_eni ==5)
					$HP_eni=$HP_eni_navire;
				if($mobile ==5)
					$HP=$HP_navire;
				if($Pos_eni !=POS_DEROUTE and $Pos_eni !=POS_TRANSIT)
				{
					if($Officier_eni >0){
						/*if($Categorie_eni ==5)
						{
							if(IsSkill(20,$Officier_eni))
								$Gyokusai_eni=true;
							if($Pos_eni ==2 or $Pos_eni ==3 or $Pos_eni ==10)
							{
								if(IsSkill(4,$Officier_eni))
									$Enfilade_eni=true;
								if(IsSkill(13,$Officier_eni) and $Zone ==7)
									$Sur_les_toits=50;
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
						}*/
					}
					else
					{
						if($Pos_eni ==POS_RETRANCHE or $Pos_eni ==POS_EMBUSCADE or $Pos_eni ==POS_EN_LIGNE)
						{
							if($Categorie_eni ==5 and $Zone ==7)
							{
								if($Skill_eni ==95)
									$Sur_les_toits=50;
								elseif($Skill_eni ==94)
									$Sur_les_toits=40;
								elseif($Skill_eni ==58)
									$Sur_les_toits=30;
								elseif($Skill_eni ==13)
									$Sur_les_toits=20;
							}
							if($Skill_eni ==77)
								$Enfilade_ia=25;
							elseif($Skill_eni ==76)
								$Enfilade_ia=20;
							elseif($Skill_eni ==49)
								$Enfilade_ia=15;
							elseif($Skill_eni ==4)
								$Enfilade_ia=10;
						}
						if($mobile_eni !=3)
						{
							if($Pos_eni ==POS_RETRANCHE or $Pos_eni ==POS_EN_LIGNE)
							{
								if($Skill_eni ==7)
									$Tranchees_ia=10;
								elseif($Skill_eni ==52)
									$Tranchees_ia=15;
								elseif($Skill_eni ==82)
									$Tranchees_ia=20;
								elseif($Skill_eni ==83)
									$Tranchees_ia=25;
							}
							if($Skill_eni ==5)
								$Flanc_Garde_ia=10;
							elseif($Skill_eni ==50)
								$Flanc_Garde_ia=15;
							elseif($Skill_eni ==78)
								$Flanc_Garde_ia=20;
							elseif($Skill_eni ==79)
								$Flanc_Garde_ia=25;
							if($Vitesse >$Vitesse_eni)
							{
								if($mobile !=3)
								{
									if($Skill_eni ==107){
										$Raid=true;
										$Raid_rate=25;
									}
									elseif($Skill_eni ==106){
										$Raid=true;
										$Raid_rate=20;
									}
									elseif($Skill_eni ==64){
										$Raid=true;
										$Raid_rate=15;
									}
									elseif($Skill_eni ==21){
										$Raid=true;
										$Raid_rate=10;
									}
									if($Raid)$msg.="<p>Vos troupes bénéficient de la compétence <b>Raid</b> !</p>";
								}
								if($Skill_eni ==69 and mt_rand(0,100)<80)
									$Arriere_Garde_eni=true;
								elseif($Skill_eni ==68 and mt_rand(0,100)<60)
									$Arriere_Garde_eni=true;
								elseif($Skill_eni ==45 and mt_rand(0,100)<40)
									$Arriere_Garde_eni=true;
								elseif($Skill_eni ==1 and mt_rand(0,100)<20)
									$Arriere_Garde_eni=true;
							}
						}
					}
				}
				$graph_reg_eni=$Veh_Nbr_eni.'/'.$Veh_Nbr_eni_Ori.' '.GetVehiculeIcon($Vehicule,$Pays_eni,0,0,$Front).'<br>'.$Range_eni.'m';
			}			
			/*if($Vitesse >$Vitesse_eni)
			{
				if($OfficierID >0)
				{
					if($mobile !=3 and IsSkill(21,$OfficierID))
					{
						$Raid=true;
						$Raid_rate=50;
						$msg.="<p>Vos troupes bénéficient de votre compétence <b>Raid</b> !</p>";
					}
					if(!$Arriere_Garde_eni)
					{
						if(IsSkill(23,$OfficierID) and ($mobile ==1 or $mobile==2 or $mobile==6 or $mobile==7))
						{
							$Tenaille=1.5;
							$Encercle=true;
							$msg.="<p>Vos troupes bénéficient de votre compétence <b>Encerclement</b> !</p>";
						}
						elseif(IsSkill(16,$OfficierID) and ($mobile ==1 or $mobile==2 or $mobile==6 or $mobile==7) and $Zone !=5 and $Zone !=7)
						{
							$Tenaille=1.5;
							$Enfilade_eni=false;
							$msg.="<p>Vos troupes bénéficient de votre compétence <b>Tenaille</b> !</p>";
						}
					}
				}
			}*/			
			//Init
			if($Pos_eni ==POS_EMBUSCADE)$Bonus_Init=$Tactique_eni; //Bonus défensif embuscade
			if($Pos_eni !=POS_SOUS_LE_FEU and $Pos_eni !=POS_CLOUE_AU_SOL and $Distance_tir <501 and $mobile !=3)$Bonus_Init+=50; //Bonus défensif si attaque à courte portée
			$Bonus_Init+=($units_allies_zone-$units_eni_zone)+$Bonus_Sec;
			$Tir_base+=($units_allies_zone-$units_eni_zone)+$Bonus_Sec;
			if(((($Range + mt_rand(0,$Tactique))>=($Bonus_Init + $Range_eni + mt_rand(0,$Tactique_eni))) OR (((mt_rand(0,$Vitesse)*50)+mt_rand(0,$Tactique))>($Bonus_Init + $Range_eni + mt_rand(0,$Tactique_eni))))
				AND !$Barrage_eni) //Barrage_eni obsolete, only for officer's cie
			{
				if($Pak_front and $mobile !=3 and !$Panzerkeil){
					$Initiative=false;
					$Dist_shoot=$Dist_eni+mt_rand(-100,100);
				}
				else{
                    if(!$Move_eni and $Dist_shoot >500 and $Pos_eni ==POS_EMBUSCADE)
                        $Initiative=false;
                    else{
                        $Initiative=true;
                        $Dist_shoot=$Distance_tir+mt_rand(-100,100);
                    }
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
			if($Zone ==6 and $Pos_eni ==POS_EVASION and $Vitesse_eni >$Vitesse)$Smoke=true;
			//Appui allié
			$arti_couv=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Muns,r.Stock_Essence_87,r.Officier_ID,r.Skill,r.Matos,c.Arme_Art,c.Portee
			FROM Regiment_IA as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (5,23) AND r.Placement='$Placement_eni' AND c.Arme_Art >0 AND c.Charge=0 AND r.ID<>'$Reg_eni'");
			/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Muns,r.Stock_Essence_87,r.Officier_ID,r.Skill,r.Matos,c.Arme_Art,c.Portee
			FROM Regiment as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (5,23) AND r.Placement='$Placement_eni' AND c.Arme_Art >0 AND c.Charge=0 AND r.ID<>'$Reg_eni') UNION (*/
			//mysqli_close($con);
			if($arti_couv)
			{
				while($data=mysqli_fetch_array($arti_couv))
				{
					$Appui_des=0;
					$Reg_eni_r=0;
					$Update_XP_eni=0;
					$Suppression=0;
					$Ripostes_appui=1;
					$EXP=($data['Experience']/10)+10;
					if($data['Skill'] ==73)
						$data['Portee']*=1.25;
					elseif($data['Skill'] ==72)
						$data['Portee']*=1.2;
					elseif($data['Skill'] ==47)
						$data['Portee']*=1.15;
					elseif($data['Skill'] ==15)
						$data['Portee']*=1.1;
					$chance_tir=mt_rand(0,40);						
					if($chance_tir <=$EXP and ($data['Portee'] >=$Distance_tir or $data['Portee'] >=$Dist_shoot))
					{
						$msg_eni.="<br>Une unité d'artillerie ennemie en appui tire pour couvrir votre adversaire !";
						$Arme=$data['Arme_Art'];							
						//$con=dbconnecti();
						$result3=mysqli_query($con,"SELECT Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
						//mysqli_close($con);
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
						if($data['Skill'] ==109)
							$Ripostes_appui+=4;
						elseif($data['Skill'] ==108)
							$Ripostes_appui+=3;
						elseif($data['Skill'] ==65)
							$Ripostes_appui+=2;
						elseif($data['Skill'] ==22)
							$Ripostes_appui+=1;
						elseif($data['Skill'] ==113)
							$Suppression=25;
						elseif($data['Skill'] ==112)
							$Suppression=20;
						elseif($data['Skill'] ==67)
							$Suppression=15;
						elseif($data['Skill'] ==28)
							$Suppression=10;
						elseif($data['Skill'] ==85)
							$Barrage=25;
						elseif($data['Skill'] ==84)
							$Barrage=20;
						elseif($data['Skill'] ==53)
							$Barrage=15;
						elseif($data['Skill'] ==8)
							$Barrage=10;
						for($t=1;$t<=$Ripostes_appui;$t++)
						{
							if(!$Veh_Nbr_Final)break;
							if($data['Officier_ID'] >0)
							{
								$Reg_eni_r=$data['ID'];
								if($Arme ==136)
									$Muns_Stock=$data['Stock_Essence_87'];
								else
									$Muns_Stock=GetData("Regiment","ID",$Reg_eni_r,"Stock_Munitions_".$Arme_Cal);
							}
							else
							{
								$Reg_eni_r=$data['ID']; //0
								$Muns_Stock=9999;
							}			
							if($Muns_Stock >=$mult and $mult >0)
							{
								if($data['Officier_ID'] >0)
								{
									if($Arme ==136)
										UpdateData("Regiment","Stock_Essence_87",-$mult,"ID",$Reg_eni_r);
									else
										UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$mult,"ID",$Reg_eni_r);
								}							
								$Tir=mt_rand(0,$EXP);
								if($data['Matos'] ==3)$Tir+=2;
								$Shoot=$Tir+$Meteo+$Cam-$Vitesse-mt_rand(0,$Tactique);
								if($Raid and mt_rand(0,100)<$Raid_rate)$Shoot=0;
								if($Barrage and mt_rand(0,100)<$Barrage)$Repousse=true;
								if($Shoot >1 or $Tir ==$EXP)
								{
									if(in_array($data['Matos'],$Matos_mun))
										$Mun_a=$data['Matos'];
									else
										$Mun_a=$data['Muns'];
									$Degats=(mt_rand(1,$Arme_Degats)-$Blindage)*GetShoot($Shoot,$mult);
									$Degats=Get_Dmg($Mun_a,$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max);
									if($Gyokusai or $Gyokusai_eni)$Degats*=2;
									if(!$Initiative and $mobile ==5 and $mobile_eni ==5)
									{
										if($Skill_eni ==31)
											$Degats*=1.2;
										elseif($Skill_eni ==132)
											$Degats*=1.3;
										elseif($Skill_eni ==133)
											$Degats*=1.4;
										elseif($Skill_eni ==134)
											$Degats*=1.5;
									}
									if($Pos_eni ==23)
									{
										if($Skill_eni ==41)
											$Degats*=1.1;
										elseif($Skill_eni ==162)
											$Degats*=1.15;
										elseif($Skill_eni ==163)
											$Degats*=1.2;
										elseif($Skill_eni ==164)
											$Degats*=1.25;
									}
									$HP-=round($Degats);
									if($Degats <3)
									{
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) est trop faible pour percer le blindage de vos unités!';
										$Degats=1;
									}
									elseif($HP <1)
									{
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) <b>détruit une de vos unités</b>';
										if($Premium)$msg_eni.=' (<b>'.round($Degats).'</b> points de dégats!)';
										if($Suppression and mt_rand(0,100)<$Suppression)$Sous_le_feu=true;
										if(!$Reg_Officier_ID)
											$Reg_a_ia=1;
										else
											$Reg_a_ia=0;
										if($data['Officier_ID'])
											$Reg_b_ia=1;
										else
											$Reg_b_ia=0;
										AddEventGround(400,$Veh,$Reg_Officier_ID,$Reg,$Lieu,1,$Reg_eni_r);
										//if($Division >0 and $Division == $data['Division'])$Reput*=2;
										$Update_XP_eni+=$Reput;
										$Appui_des+=1;
										$Veh_Nbr_Final-=1;
										$Veh_Nbr_atk-=1;
										if($Veh_Nbr_Final <1 or $Veh_Nbr_atk <1)
											break;
										elseif($HP_ori >0)
										{
											$HP=$HP_ori;
											SetData($DB_reg,"HP",$HP,"ID",$Reg);
										}
									}
									elseif($Degats >1)
									{
										if($Degats >10)
										{
											AddEventGround(450,$Veh,$Reg_Officier_ID,$Reg,$Lieu,$Degats,$Reg_eni_r);
											if($Suppression and mt_rand(0,100)<$Suppression)$Sous_le_feu=true;
										}
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
										$Update_XP_eni+=1;
										if($Repousse or $HP <($HP_ori/2))
										{
											$msg_eni.="<br>Le tir de couverture ennemi contraint un véhicule à abandonner l'assaut!";
											$Veh_Nbr_Final-=1;
                                            $Veh_Nbr_atk-=1;
											if($Veh_Nbr_Final <1)break;
										}
									}
									$Tac_Appui=true;
								}
								elseif($Shoot <-10)
									$msg_eni.='<br>Le tir de couverture ennemi manque de précision!';
								else
								{
									$msg_eni.="<br>Le tir de couverture ennemi contraint un véhicule à abandonner l'assaut!";
									$Veh_Nbr_Final-=1;
                                    $Veh_Nbr_atk-=1;
									if($Veh_Nbr_Final <1)break;
								}
							}
							else
								$msg_eni.='<br>Le tir ennemi fait long feu!';
						}
						if($Appui_des >0)
						{
							UpdateData($DB_reg,"Vehicule_Nbr",-$Appui_des,"ID",$Reg);
							if($Reg_eni_r >0 and $Update_XP_eni)UpdateData("Regiment_IA","Experience",$Update_XP_eni,"ID",$Reg_eni_r);
							AddGroundAtk($Reg_eni_r,$Reg,$data['Vehicule_ID'],$data['Vehicule_Nbr'],$Veh,$Veh_Nbr_atk,$data['Position'],4,$Lieu,$data['Placement'],$Dist_shoot,1,$Reg_a_ia,$Reg_b_ia);
						}
					}
					$graph_art_eni.=GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)."<br>".$data['Portee']."m<br>Artillerie en Appui";
				}
				mysqli_free_result($arti_couv);
				unset($data);
			}						
			if($Veh_Nbr_Final >0 and $Veh_Nbr_atk >0 and $Zone !=6)
			{
				//Couverture alliée
				//$con=dbconnecti();
				$pj_unit=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Muns,r.Matos,r.Stock_Essence_87,r.Officier_ID,c.Arme_Art,c.Arme_AT,c.Arme_Inf,c.Portee
				FROM Regiment_IA as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position=1 AND r.Placement='$Placement_eni' AND c.mobile IN (1,2,6) AND c.Vitesse >10 AND c.Charge=0 AND r.ID<>'$Reg_eni'");
				/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Muns,r.Matos,r.Stock_Essence_87,r.Officier_ID,c.Arme_Art,c.Arme_AT,c.Arme_Inf,c.Portee
				FROM Regiment as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position=1 AND r.Placement='$Placement_eni' AND c.mobile IN (1,2,6) AND c.Vitesse >10 AND c.Charge=0 AND r.ID<>'$Reg_eni') UNION (*/
				//mysqli_close($con);
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
						if($chance_tir <=$EXP and ($data['Portee'] >=$Distance_tir or $data['Portee'] >=$Dist_shoot) and $Veh_Nbr_atk >0)
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
							//$con=dbconnecti();
							$result3=mysqli_query($con,"SELECT Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
							//mysqli_close($con);
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
							if($data['Officier_ID'] >0)
							{
								$Reg_eni_r=$data['ID'];
								if($Arme ==136)
									$Muns_Stock=$data['Stock_Essence_87'];
								else
									$Muns_Stock=GetData("Regiment","ID",$Reg_eni_r,"Stock_Munitions_".$Arme_Cal);
							}
							else
							{
								$Reg_eni_r=$data['ID']; //0
								$Muns_Stock=9999;
							}							
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
								if($data['Matos'] ==3)$Tir+=2;
								$Shoot=$Tir+$Meteo+$Cam-($Vitesse/2)-mt_rand(0,$Tactique)+$data['Vehicule_Nbr'];
								if($Raid and mt_rand(0,100)<$Raid_rate)$Shoot=0;
								if($Shoot >1 or $Tir ==$EXP)
								{
									if(in_array($data['Matos'],$Matos_mun))
										$Mun_c=$data['Matos'];
									else
										$Mun_c=$data['Muns'];
									$Degats=(mt_rand(1,$Arme_Degats)-$Blindage)*GetShoot($Shoot,$mult);
									$Degats=round(Get_Dmg($Mun_c,$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
									if($Gyokusai or $Gyokusai_eni)$Degats*=2;
									$HP-=$Degats;
									if($Degats <2)
									{
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) est trop faible pour percer le blindage de vos unités!';
										$Degats=1;
									}
									elseif($HP <1)
									{
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) <b>détruit une de vos unités</b>';
										if($Premium)$msg_eni.=' (<b>'.$Degats.'</b> points de dégats!)';
										UpdateData($DB_reg,"Vehicule_Nbr",-1,"ID",$Reg);
										AddEventGround(400,$Veh,$Reg_Officier_ID,$Reg,$Lieu,1,$Reg_eni_r);
										if(!$Reg_Officier_ID)
											$Reg_b_ia=1;
										else
											$Reg_b_ia=0;
										if($data['Officier_ID'])
											$Reg_a_ia=0;
										else
											$Reg_a_ia=1;
										AddGroundAtk($Reg_eni_r,$Reg,$data['Vehicule_ID'],$data['Vehicule_Nbr'],$Veh,$Veh_Nbr_Final,$data['Position'],4,$Lieu,$data['Placement'],$Dist_shoot,1,$Reg_a_ia,$Reg_b_ia);
										//if($Division >0 and $Division ==$data['Division'])$Reput*=2;
										$Update_XP_eni=$Reput;
										$Veh_Nbr-=1;
										$Veh_Nbr_Final-=1;
										$Veh_Nbr_atk-=1;
										if($Veh_Nbr_Final <1 or $Veh_Nbr_atk <1)
											break;
										else
										{
											$HP=$HP_ori;
											SetData($DB_reg,"HP",$HP,"ID",$Reg);
										}
									}
									elseif($Degats >1)
									{
										if($Degats >10)
											AddEventGround(450,$Veh,$Reg_Officier_ID,$Reg,$Lieu,$Degats,$Reg_eni_r);
										$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
										$Update_XP_eni=1;
										if($HP <($HP_ori/2))
										{
											$msg_eni.="<br>Le tir de couverture ennemi contraint un véhicule à abandonner l'assaut!";
											$Veh_Nbr_Final-=1;
											if($Veh_Nbr_Final <1)break;
										}
									}
									if($Reg_eni_r >0)// and $data['Officier_ID'] >0)
										UpdateData("Regiment_IA","Experience",$Update_XP_eni,"ID",$Reg_eni_r);
									$Tac_Couv=true;
								}
								else
									$msg_eni.='<br>Le tir de couverture ennemi manque de précision!';
							}
							else
								$msg_eni.='<br>Le tir ennemi fait long feu!';
						}
						$graph_couv_eni.=GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)."<br>".$data['Portee']."m<br>Véhicules en Couverture";
					}
					mysqli_free_result($pj_unit);
					unset($data);
				}
			}			
			//Embuscade AT
			if($Veh_Nbr_Final >0 and $Veh_Nbr_atk >0 and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7))
			{
				if($Avant_Garde)
					$msg_eni.='<br>Vous évitez une embuscade ennemie grâce à votre compétence <b>Avant-Garde</b>!';
				else
				{
					//$con=dbconnecti();
					$at_couv=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Officier_ID,r.Muns,r.Stock_Essence_87,r.Visible,r.Skill,r.Matos,c.Arme_AT,c.mobile,c.Portee,c.Type
					FROM Regiment_IA as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (3,10) AND r.Placement='$Placement_eni' AND c.Arme_AT >0 AND c.Charge=0 AND (c.mobile=3 OR c.Type=9) AND r.ID<>'$Reg_eni'");
					/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Position,r.Placement,r.Officier_ID,r.Muns,r.Stock_Essence_87,r.Visible,r.Skill,r.Matos,c.Arme_AT,c.mobile,c.Portee,c.Type
					FROM Regiment as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (3,10) AND r.Placement='$Placement_eni' AND c.Arme_AT >0 AND c.Charge=0 AND (c.mobile=3 OR c.Type=9) AND r.ID<>'$Reg_eni') UNION (*/
					//mysqli_close($con);
					if($at_couv)
					{
						while($data=mysqli_fetch_array($at_couv))
						{
							$Reg_eni_r=0;
							$Update_XP_eni=0;
							$Dg_mult_emb=1;
							$EXP=($data['Experience']/10)+10;
							if($data['Type'] ==6)$data['Portee']/=2;
							if(($data['Portee'] >=$Distance_tir or $data['Portee'] >=$Dist_shoot) and $Veh_Nbr_atk >0)
							{							
								$Update_Nbr=0;
								$Arme=$data['Arme_AT'];								
								//$con=dbconnecti();
								$result3=mysqli_query($con,"SELECT Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
								//mysqli_close($con);
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
										if($data['Type'] ==6)
										{
											$Arme_Portee/=2;
											$Arme_Portee/=2;
										}
									}
									mysqli_free_result($result3);
								}			
								if($data['Vehicule_Nbr'] >25)
									$Emb_atk_nbr=ceil($data['Vehicule_Nbr']/=10);
								else
									$Emb_atk_nbr=$data['Vehicule_Nbr'];
								//Prise de flanc ou de face
								if($data['Type']==4 or $data['Type']==9)$Bonus_CC=20;
								$rand_blindage=mt_rand(0,100)+$EXP+$Bonus_CC;
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
								if($data['mobile'] ==3)
								{
									if($data['Skill'] ==75)
										$Dg_mult_emb=1.5;
									elseif($data['Skill'] ==74)
										$Dg_mult_emb=1.4;
									elseif($data['Skill'] ==48)
										$Dg_mult_emb=1.3;
									elseif($data['Skill'] ==3)
										$Dg_mult_emb=1.2;										
								}
								for($t=1;$t<=$Emb_atk_nbr;$t++)
								{
									if($Veh_Nbr_Final <1 or $Veh_Nbr_atk <1)break;
									if($Dist_shoot <501)
										$chance_tir=1;
									else
										$chance_tir=mt_rand(0,40);									
									if($chance_tir <=$EXP)
									{
										if($data['Position'] ==3)
											$msg_eni.="<br>Une unité anti-char ennemie en embuscade tire pour couvrir votre adversaire".$emb_txt." !";
										else
											$msg_eni.="<br>Une unité anti-char ennemie tire pour couvrir votre adversaire".$emb_txt." !";
										if($data['Officier_ID'] >0)
										{
											$Reg_eni_r=$data['ID'];
											if($Arme ==136)
												$Muns_Stock=$data['Stock_Essence_87'];
											else
												$Muns_Stock=GetData("Regiment","ID",$Reg_eni_r,"Stock_Munitions_".$Arme_Cal);
										}
										else
										{
											$Reg_eni_r=$data['ID']; //0
											$Muns_Stock=9999;
										}										
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
											if($data['Matos'] ==3)$Tir+=2;
											$Shoot=$Tir+$Meteo+$Cam-($Vitesse/2)-mt_rand(0,$Tactique)+$data['Vehicule_Nbr']+($data['Portee']/100);
											if($Raid and mt_rand(0,100)<$Raid_rate)$Shoot=0;
											if($Shoot >1 or $Tir ==$EXP)
											{
												if(in_array($data['Matos'],$Matos_mun))
													$Mun_e=$data['Matos'];
												else
													$Mun_e=$data['Muns'];
												if($Arme ==356)$Mun_e=8;
												if(!$Initiative)$data['Portee']=100;
												$Degats=(mt_rand(1,$Arme_Degats)-$Blindage_emb)*GetShoot($Shoot,$mult);
												$Degats=round(Get_Dmg($Mun_e,$Arme_Cal,$Blindage_emb,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max)*$Dg_mult_emb);
												if($Gyokusai or $Gyokusai_eni)$Degats*=2;
												/*if($data['Officier_ID'] >0 and IsSkill(3,$data['Officier_ID']))$Degats*=2;
												if($data['Officier_ID'] >0 and $data['mobile'] ==3 and !$Tenaille and ($data['Position'] ==2 or $data['Position'] ==3 or $data['Position'] ==10) and IsSkill(4,$data['Officier_ID']))$Degats*=2;*/
												if($Panzerkeil)$Degats*=$Panzerkeil_rate;
												$HP-=$Degats;
												if($Degats <3)
												{
													$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) est trop faible pour percer le blindage de vos unités!';
													$Degats=1;
												}
												elseif($HP <1)
												{
													if($Reg_eni_r >0)
														$msg_eni.='<br>Le tir ennemi ('.$Reg_eni_r.'e Cie) <b>détruit une de vos unités</b>';
													else
														$msg_eni.='<br>Le tir ennemi (Cie IA) <b>détruit une de vos unités</b>';
													if($Premium)$msg_eni.=' (<b>'.$Degats.'</b> points de dégats!)';
													/*if($Division >0 and $Division == $data['Division'])$Reput*=2;*/
													$Update_XP_eni=$Reput;
													$HP=$HP_ori;
													$Veh_Nbr_Final-=1;
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
													if($HP <($HP_ori/2))
													{
														$msg_eni.="<br>Le tir de couverture ennemi contraint un véhicule à abandonner l'assaut!";
														$Veh_Nbr_Final-=1;
														$Veh_Nbr_atk-=1;
														if($Veh_Nbr_Final <1 or $Veh_Nbr_atk <1)break;
													}
												}
												if($Reg_eni_r >0)// and $data['Officier_ID'] >0)
													UpdateData("Regiment_IA","Experience",$Update_XP_eni,"ID",$Reg_eni_r);
												$Tac_Ambush=true;
											}
											elseif($Shoot <-10)
												$msg_eni.="<br>Le tir d'embuscade ennemi manque sa cible!";
											else
											{
												$msg_eni.="<br>Le tir d'embuscade ennemi contraint un véhicule à abandonner l'assaut!";
												$Veh_Nbr_Final-=1;
												$Veh_Nbr_atk-=1;
												if($Veh_Nbr_Final <1 or $Veh_Nbr_atk <1)break;
											}
										}
										else
											$msg_eni.='<br>Le tir ennemi fait long feu!';
									}
								}
								if($Update_Nbr <0)
								{
									UpdateData($DB_reg,"Vehicule_Nbr",$Update_Nbr,"ID",$Reg);
									UpdateData($DB_reg,"Moral",$Update_Nbr,"ID",$Reg);
									AddEventGround(400,$Veh,$Reg_Officier_ID,$Reg,$Lieu,-$Update_Nbr,$Reg_eni_r);
									if(!$Reg_Officier_ID)
										$Reg_b_ia=1;
									else
										$Reg_b_ia=0;
									if($data['Officier_ID'])
										$Reg_a_ia=0;
									else
										$Reg_a_ia=1;
									AddGroundAtk($Reg_eni_r,$Reg,$data['Vehicule_ID'],$data['Vehicule_Nbr'],$Veh,$Veh_Nbr_Final,$data['Position'],4,$Lieu,$data['Placement'],$Dist_shoot,-$Update_Nbr,$Reg_a_ia,$Reg_b_ia);
								}
								elseif($Update_XP_eni)
									AddEventGround(450,$Veh,$Reg_Officier_ID,$Reg,$Lieu,$Degats,$Reg_eni_r);
							}
							$graph_AT_eni.=GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)."<br>".$data['Portee']."m<br>Anti-char en embuscade";
						}
						mysqli_free_result($at_couv);
						unset($data);
					}
				}
			}
			if($Repli ==1 and ($Tac_Appui or $Tac_Couv or $Tac_Ambush))$Repli=2;
			/*Rounds Distance_tir
			$Dist_shoot=round($Range-(($Range/255)*(mt_rand(0,$Tactique)-mt_rand(0,$Tactique_eni))));
			if($Dist_shoot >$Range)
				$Dist_shoot=$Range;
			elseif($Dist_shoot <100)
				$Dist_shoot=100;*/
			if($Veh_Nbr_atk >0 and $Smoke ==false)
			{
				/*$hatk=GetData("Division","ID",$Division,"hatk");
				$datk=GetData("Division","ID",$Division,"atk");*/
				if($Initiative ==true)
				{
					//Tir
					$msg.="Vos troupes ont l'initiative, ".$Veh_Nbr_atk." ".$Veh_Nom." (sur une force originale de ".$Veh_Nbr_Ori.") engagent l'ennemi à une distance d'environ ".$Dist_shoot."m";
					if($Tenaille and $units_allies_zone and $units_eni_zone <2)
					{
						if(mt_rand(0,$units_eni_zone)<1){
							$Blindage_eni=$Blindage_l_eni;
							$msg.="<br>Vos troupes débordent les flancs de l'ennemi!";
						}
					}
					if($Armement ==1)
						$Arme=$Arme_Inf;
					elseif($Zone ==6 or $mobile ==5)
						$Arme=$Arme_Art;
					elseif($Blindage_eni >0 and $Arme_AT >0 and $Arme_AT !=82)
						$Arme=$Arme_AT;
					elseif($Arme_Art >0 and $Arme_Art !=82 and $Range <3000 and $Munition !=7)
						$Arme=$Arme_Art;
					elseif($Munition <7)
						$Arme=$Arme_Inf;
					else
						$Arme=0;
					if($Arme >0)
					{
						//$con=dbconnecti();
						$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
						//mysqli_close($con);
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
						if($Reg_Officier_ID >0)
						{
							if($Arme ==136)
								$Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Essence_87");
							else
								$Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Munitions_".$Arme_Cal);
						}
						else
							$Muns_Stock=9999;
						$Muns_Conso=$Veh_Nbr_atk*$Arme_Multi;
						if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
						{
							if($Reg_Officier_ID >0)
							{
								if($Arme ==136)
								{
									UpdateData("Regiment","Stock_Essence_87",-$Muns_Conso,"ID",$Reg);
									if($Categorie_eni ==5)
										$Blindage_eni=0;
								}
								else
									UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg);
							}
							$msg.="<br>Votre unité tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
							//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage_eni,$Munition,$Dist_shoot);
							$Update_Nbr_eni=0;
							$Update_Reput=0;
							$Update_xp=0;
							$Rafale=1;
							if($Categorie ==5 and $Categorie_eni ==5)
								$Rafale=$Veh_Nbr_atk;
							if($Veh_Nbr_atk >25){
								$Veh_Nbr_atk_dispersion=$Veh_Nbr_atk;
								$Veh_Nbr_atk=floor($Veh_Nbr_atk/10);
							}
							for($t=1;$t<=$Veh_Nbr_atk;$t++)
							{
								if($Veh_Nbr_eni <1 or $Update_Nbr_eni <-$Max_Kill or $HP_eni <=0)
									break;
								$Tir=mt_rand(0,$Tir_base);
								if($Matos ==3)$Tir+=2;
								$Defense_tir=$Vitesse_eni+mt_rand(0,$Tactique_eni)-$Cam_eni-($Meteo/2)-$Optics;
								if($Sur_les_toits and mt_rand(0,100)<$Sur_les_toits)$Tir=0;
								if($Deception and mt_rand(0,100)<$Deception_rate)$Tir=0;
                                /**
                                 * 23/09/17
                                 */
                                if($country ==2 or $country ==4){
                                    $Tir-=10;
                                }
								if($Premium)
								{
									$pc_score=(300+$Tir-$Defense_tir)/6;
									$Bar_pc=round($pc_score,1,PHP_ROUND_HALF_DOWN);
									if($Tir >$Defense_tir and $Tir >1)
										$skills.="Efficacité de votre ".$t."e tir<br><div class='progress'><div class='progress-bar-success' role='progressbar' aria-valuenow='".$pc_score."' aria-valuemin='0' aria-valuemax='100' style='width: ".$pc_score."%'>".$Bar_pc."%</div></div>";
									else
										$skills.="Efficacité de votre ".$t."e tir<br><div class='progress'><div class='progress-bar-danger' role='progressbar' aria-valuenow='".$pc_score."' aria-valuemin='0' aria-valuemax='100' style='width: ".$pc_score."%'>".$Bar_pc."%</div></div>";
								}
								else
									$skills.="Efficacité de votre ".$t."e tir<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100' style='width:100%'>?%</div></div>";
                                if($debug)$msg_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse_eni.", Taille=".$Cam_eni.", Tac (max)=".$Tactique_eni.", Meteo (/2)=".$Meteo.")";
								if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base))
								{
									$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg)*$Rafale;
									if($Tir >=$Tir_base)$Base_Dg=$Arme_Dg-mt_rand(0,10);
									$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
									if($Tenaille)
									{
										$Degats*=$Tenaille;
										if($Trait_o ==3)$Degats*=2;
									}
									$Degats=round(Get_Dmg($Munition,$Arme_Cal,$Blindage_eni,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
									if(!$Blindage_eni and $Dist_shoot <600 and $Arme_Cal <30 and $Degats <100)$Degats*=2; //Boost MG vs Infanterie
									if($Pos_eni ==1 and $Defense_elastique and mt_rand(0,100)< $Defense_rate)
									{
										if($Trait ==3)
											$Degats/=4;
										else
											$Degats/=2;
									}
									if($Herisson and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7))$Degats/=2;
									if($Gyokusai or $Gyokusai_eni)$Degats*=2;
									if($Pos_eni ==8 and $Categorie ==5)$Degats*=2;
									if($Sturm)$Degats*=$Sturm_rate;
									if($Raid and $Trait_o ==2)$Degats/=2;
									if($Mode ==4 and (($Veh_Nbr_atk_dispersion/5) >$Veh_Nbr_eni) and ($Pos_eni ==8 or $Pos_eni ==9 or $Pos_eni==7 or $Pos_eni ==6))
									{
										$msg.="<br>Votre unité disperse les fantassins ennemis désorganisés!</b>";
										$Degats*=$Veh_Nbr_atk_dispersion;
                                        if($Degats >65535)$Degats=65535;
										if($Degats <1)$Degats=1;
									}
									if($Degats >$HP_eni or ($Encercle and $Degats >($HP_eni/2)))
									{
										if($Charge_eni and $Fret_eni >0)
										{
											if($Fret_eni ==888)
												UpdateData("Pays","Special_Score",-1,"ID",$Pays_eni);
											elseif($Fret_eni ==200 and $Fret_Qty_eni >0){
												//$con=dbconnecti();
												$reset=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0,HP=0,Position=6 WHERE ID='$Reg_eni'");
												$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Moral=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Experience=0,Skill=0,Visible=0 WHERE ID='$Fret_Qty_eni'");
												//mysqli_close($con);
											}
											if($Officier_eni){
												$Perte_Stock=$Fret_Qty_eni/$Veh_Nbr_eni;
												UpdateData("Regiment","Fret_Qty",-$Perte_Stock,"ID",$Reg_eni);
											}
										}
										if($Categorie_eni ==5 or $Categorie_eni ==6){
											$Collateral=1+(floor($Degats/$HP_eni/10));
											if($Collateral >10 and $Arme !=136)$Collateral=10;
											$Update_Nbr_eni-=$Collateral;
											$Veh_Nbr_eni-=$Collateral;
										}
										else{
											$Veh_Nbr_eni-=1;
											$Update_Nbr_eni-=1;
										}
										$Update_Reput+=$Reput_eni;
										$msg.="<br>Votre unité touche la cible et lui occasionne <b>".floor($Degats)."</b> dégâts!<br><b>La cible est détruite!</b>";
										$HP_eni=$HP_ori_eni;
										if($mobile_eni ==5)
										{
											if($Veh_Nbr_eni)
												SetData($DB,"HP",$HP_ori_eni,"ID",$Reg_eni);
											else
												SetData($DB,"HP",0,"ID",$Reg_eni);
											if($Transit_eni >0){
												//$con=dbconnecti();
												$reset=mysqli_query($con,"UPDATE Regiment SET Experience=0,Skill=0,Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
												Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
												Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Visible=0 WHERE Officier_ID='$Transit_eni' AND Vehicule_Nbr>0 ORDER BY RAND() LIMIT 1");
												//mysqli_close($con);
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
										if($mobile_eni ==5){
											AddEventGround(461,$Vehicule,$Reg_Officier_ID,$Reg_eni,$Lieu,$Degats,$Reg);
											UpdateData($DB,"HP",-$Degats,"ID",$Reg_eni);
										}
									}
									else
										$msg.="<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
								}
								else
									$msg.="<br>Votre unité rate la cible!";
							}
							if($Update_Nbr_eni <0)
							{
								if(($Categorie_eni ==5 or $Categorie_eni ==6) and !$Arme_AT_eni and ($Type ==7 or $Type ==10 or $Type ==91))
								{
									$msg_eni.='<br>Vos blindés mettent en fuite une partie des fantassins ennemis!';
									//$con=dbconnecti();
									$units_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3,5,10) AND r.Placement='$Placement_eni'"),0);
									//(SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3,5,10) AND r.Placement='$Placement_eni') UNION (
									//mysqli_close($con);
									$Mult_Fear_TK=1;
									if($Pos_eni ==8 or $Pos_eni ==9)
										$Mult_Fear_TK+=1;
									if($units_eni <2)
										$Mult_Fear_TK+=1;
									if($Mult_Fear_TK >$Veh_Nbr_eni)$Mult_Fear_TK=$Veh_Nbr_eni;
									if($Mult_Fear_TK <1)$Mult_Fear_TK=1;
									$Update_Nbr_eni-=($Veh_Nbr_atk*$Mult_Fear_TK);
									$Veh_Nbr_eni-=($Veh_Nbr_atk*$Mult_Fear_TK);
								}
								UpdateData($DB,"Vehicule_Nbr",$Update_Nbr_eni,"ID",$Reg_eni);
								UpdateData($DB,"Moral",$Update_Nbr_eni,"ID",$Reg_eni);
								AddEventGround(401,$Vehicule,$Reg_Officier_ID,$Reg_eni,$Lieu,-$Update_Nbr_eni,$Reg);
								if(!$Reg_Officier_ID)
									$Reg_a_ia=1;
								else
									$Reg_a_ia=0;
								if(!$Officier_eni)
									$Reg_b_ia=1;
								else
									$Reg_b_ia=0;
								AddGroundAtk($Reg,$Reg_eni,$Veh,$Veh_Nbr_atk,$Vehicule,$Veh_Nbr_eni,4,$Pos_eni,$Lieu,$Placement_eni,$Dist_shoot,-$Update_Nbr_eni,$Reg_a_ia,$Reg_b_ia);
								/*if($Pos_eni ==2)SetData($DB,"Visible",0,"ID",$Reg_eni);*/
							}
							if($Reg_Officier_ID >0)
							{
								if($Update_Reput and $Pays_eni !=$country)
								{
									if($Trait_o ==1)
										$Update_Reput*=2;
									/*if($Division >0 and $hatk ==$Heure and $Lieu ==$datk)
										$Update_Reput*=2;*/
									UpdateData("Regiment","Experience",$Update_Reput,"ID",$Reg);
									UpdateData("Regiment","Moral",$Update_Reput,"ID",$Reg);
									UpdateData("Officier","Avancement",$Update_Reput,"ID",$Reg_Officier_ID);
									UpdateData("Officier","Reputation",$Update_Reput,"ID",$Reg_Officier_ID);
								}
								elseif($Update_Reput and $Pays_eni ==$country)
								{
									UpdateData("Officier","Avancement",-$Update_Reput,"ID",$Reg_Officier_ID);
									UpdateData("Officier","Reputation",-$Update_Reput,"ID",$Reg_Officier_ID);
								}
								if($Update_xp and $Pays_eni !=$country)
								{
									UpdateData("Regiment","Experience",$Update_xp,"ID",$Reg);
									UpdateData("Regiment","Moral",$Update_xp,"ID",$Reg);
									UpdateData("Officier","Avancement",$Update_xp,"ID",$Reg_Officier_ID);
									UpdateData("Officier","Reputation",$Update_xp,"ID",$Reg_Officier_ID);
								}
							}
							$skills.="</div>";
						}
						else
							$msg.="<br>Votre unité annule son attaque, faute de munitions!";
					}
					else
						$msg.="<br>Votre unité annule son attaque, faute d'armement adéquat!";						
					if($Pos_eni ==8 and $Zone !=6)
						SetData($DB,"Position",9,"ID",$Reg_eni);						
					//Tir eni
					//$Veh_Nbr_eni=GetData($DB,"ID",$Reg_eni,"Vehicule_Nbr");
					if($Veh_Nbr_eni_Ori >0)
					{
						if(($Dist_eni >=$Dist_shoot) or ($Range_eni >=$Dist_shoot and $Fire_eni) or !$Repli)
						{
							if(!$Repli)$Dist_shoot=$Dist_eni+mt_rand(-100,100);;
							$msg_eni.="<br>L'ennemi riposte à une distance d'environ ".$Dist_shoot."m";
							//Riposte
							if($HP_eni >0)
							{
								if($Zone ==6)
									$Arme=$Arme_Art_eni;
								elseif($Blindage and $Arme_AT_eni and $Arme_AT_eni !=82)
									$Arme=$Arme_AT_eni;
								elseif($Arme_Art_eni and $Arme_Art_eni !=82 and $Range_eni <3000 and $Munition_eni !=7)
									$Arme=$Arme_Art_eni;
								elseif($Munition_eni <7)
									$Arme=$Arme_Inf_eni;
								else
									$Arme=0;
								if($Arme >0)
								{
									//$con=dbconnecti();
									$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
									//mysqli_close($con);
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
									if($Officier_eni >0)
									{
										if($Arme ==136)
											$Muns_Stock=GetData("Regiment","ID",$Reg_eni,"Stock_Essence_87");
										else
											$Muns_Stock=GetData("Regiment","ID",$Reg_eni,"Stock_Munitions_".$Arme_Cal);
									}
									else
										$Muns_Stock=9999;
									$Muns_Conso=$Veh_Nbr_eni_Ori*$Arme_Multi;									
									if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
									{
										if($Officier_eni >0)
										{
											if($Arme ==136)
												UpdateData("Regiment","Stock_Essence_87",-$Muns_Conso,"ID",$Reg_eni);
											else
												UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg_eni);
										}																
										$msg_eni.="<br>L'unité ennemie tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
										//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage,$Munition,$Dist_shoot);
										$Update_Nbr=0;
										$Update_Reput_eni=0;
										$Update_xp_eni=0;
										$Rafale=1;
										if($Veh_Nbr_eni_Ori >25)$Veh_Nbr_eni_Ori=floor($Veh_Nbr_eni_Ori/10);
										if($Categorie ==5 and $Categorie_eni ==5)
											$Rafale=$Veh_Nbr_eni_Ori;
										for($t=1;$t<=$Veh_Nbr_eni_Ori;$t++)
										{
											if($Veh_Nbr_Final <1)break;
											$Tir=mt_rand(0,$Tir_base_eni);
											if($Matos_eni ==3)$Tir+=2;
											$Defense_tir=$Vitesse+mt_rand(0,$Tactique)-$Cam-($Meteo/2)-$Optics_eni;
											if($debug)$msg_eni_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse.", Taille=".$Cam.", Tac (max)=".$Tactique.", Meteo (/2)=".$Meteo.")";
											if($Raid and mt_rand(0,100)<$Raid_rate)$Tir=0;
											if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base_eni))
											{
												$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg)*$Rafale;
												if($Tir >=$Tir_base_eni)$Base_Dg=$Arme_Dg-mt_rand(0,10);
												$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
												$Degats=round(Get_Dmg($Munition_eni,$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
												if(!$Blindage and $Dist_shoot <600 and $Arme_Cal <30 and $Degats <100)$Degats*=2; //Boost MG vs Infanterie
												if($Gyokusai or $Gyokusai_eni)$Degats*=2;
												if($Enfilade_eni)$Degats*=2;
												if($Tranchees_eni)$Degats*=2;
												if($Flanc_Garde_eni and $Tenaille)$Degats*=2;
												if($Flanc_Garde_ia)$Degats*=(1+($Flanc_Garde_ia/10));
												if($Enfilade_ia)$Degats*=(1+($Enfilade_ia/10));
												if($Tranchees_ia)$Degats*=(1+($Tranchees_ia/10));
												if($Pak_front and $mobile !=3)$Degats*=$Pak_front_rate;
												if($Panzerkeil and $Type_eni ==4)$Degats*=$Panzerkeil_rate;
												if($Categorie_eni ==6 and $Categorie ==5)$Degats*=2;
												if($Pos_eni ==23)
												{
													if($Skill_eni ==41)
														$Degats*=1.1;
													elseif($Skill_eni ==162)
														$Degats*=1.15;
													elseif($Skill_eni ==163)
														$Degats*=1.2;
													elseif($Skill_eni ==164)
														$Degats*=1.25;
												}
												if($Degats >$HP)
												{
													$Veh_Nbr_Final-=1;
													$Update_Nbr-=1;
													$Update_Reput_eni+=$Reput;
													$msg_eni.="<br>Votre unité est touchée et subit <b>".floor($Degats)."</b> dégâts!<br><b>Une de vos unités est détruite!</b>";
													$HP=$HP_ori;
													if($mobile ==5)
													{
														if($Veh_Nbr_Final)
															SetData($DB_reg,"HP",$HP_ori,"ID",$Reg);
														else
															SetData($DB_reg,"HP",0,"ID",$Reg);
													}
												}
												elseif($Degats >1)
												{
													$Update_xp_eni+=1;
													$msg_eni.="<br>Votre unité est touchée et subit <b>".floor($Degats)."</b> dégâts!";
													$HP-=$Degats;
													if($mobile ==5){
														AddEventGround(450,$Veh,$Reg_Officier_ID,$Reg,$Lieu,$Degats,$Reg_eni);
														UpdateData($DB_reg,"HP",-$Degats,"ID",$Reg);
													}
												}
												else
													$msg_eni.="<br>Votre unité est touchée, mais le blindage n'a pas été percé!";
											}
											else
												$msg_eni.="<br>L'ennemi tire à côté!";
										}
										if($Update_Nbr <0)
										{
											UpdateData($DB_reg,"Vehicule_Nbr",$Update_Nbr,"ID",$Reg);
											UpdateData($DB_reg,"Moral",$Update_Nbr,"ID",$Reg);
											AddEventGround(400,$Veh,$Reg_Officier_ID,$Reg,$Lieu,-$Update_Nbr,$Reg_eni);
											if(!$Reg_Officier_ID)
												$Reg_b_ia=1;
											else
												$Reg_b_ia=0;
											if($Officier_eni)
												$Reg_a_ia=0;
											else
												$Reg_a_ia=1;
											AddGroundAtk($Reg_eni,$Reg,$Vehicule,$Veh_Nbr_eni_Ori,$Veh,$Veh_Nbr_atk,$Pos_eni,4,$Lieu,$Placement_eni,$Dist_shoot,-$Update_Nbr,$Reg_a_ia,$Reg_b_ia);
										}
										if($Update_Reput_eni and $Pays_eni !=$country and $Officier_eni >0)
										{
											UpdateData($DB,"Experience",$Update_Reput_eni,"ID",$Reg_eni);
											UpdateData($DB,"Moral",$Update_Reput_eni,"ID",$Reg_eni);
										}
										if($Update_xp_eni and $Pays_eni !=$country and $Officier_eni >0)
										{
											UpdateData($DB,"Experience",$Update_xp_eni,"ID",$Reg_eni);
											UpdateData($DB,"Moral",$Update_xp_eni,"ID",$Reg_eni);
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
					{
						if($Officier_eni >0)
						{
							/*if(IsSkill(29,$Officier_eni))
							{
								$Front=GetData("Officier","ID",$Officier_eni,"Front");
								$Latitude=GetData("Lieu","ID",$Lieu,"Latitude");
								$Retraite=Get_Retraite($Front,$country,$Latitude);
								SetData("Regiment","Lieu_ID",$Retraite,"ID",$Officier_eni);
							}
							elseif(IsSkill(6,$Officier_eni) and $mobile_eni !=5)
								SetData("Regiment","Position",2,"Officier_ID",$Officier_eni);*/
							$Exp_final_eni=0;
							if($Trait_eni ==11){
								$Exp_final_eni=$Exp_eni_ori;
								if($Exp_final_eni >100)
									$Exp_final_eni=100;
							}
							//$con=dbconnecti();
							$reset=mysqli_query($con,"UPDATE Regiment SET Experience='$Exp_final_eni',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
							Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg_eni'");
							$reset2=mysqli_query($con,"UPDATE Regiment SET Visible=0 WHERE Officier_ID='$Officier_eni'");
							//mysqli_close($con);
							if($Level_eni >10000){
								$Malus_Reput=($Level_eni/1000)+$Reput_eni;
								UpdateData("Officier","Reputation",-$Malus_Reput,"ID",$Officier_eni);
							}
						}
						$msg_eni.="<br>L'ennemi, totalement en déroute, ne peut riposter!";
					}
				}
				else
				{		
					//$Veh_Nbr_eni=GetData($DB,"ID",$Reg_eni,"Vehicule_Nbr");
					if($Veh_Nbr_eni_Ori >0)
					{
						if($Fire_eni or $Dist_shoot <=$Dist_eni or !$Repli)
						{
							$msg.="L'ennemi a l'initiative et engage vos troupes à une distance d'environ ".$Dist_shoot."m";
							$msg_eni.="<br>L'ennemi a l'initiative et engage vos troupes à une distance d'environ ".$Dist_shoot."m";
							//Riposte
							if($HP_eni >0)
							{
								if($Zone ==6)
									$Arme=$Arme_Art_eni;
								elseif($Blindage >0 and $Arme_AT_eni and $Arme_AT_eni !=82)
									$Arme=$Arme_AT_eni;
								elseif($Arme_Art_eni >0 and $Arme_Art_eni !=82 and $Range_eni <3000 and $Munition_eni !=7)
									$Arme=$Arme_Art_eni;
								elseif($Munition_eni <7)
									$Arme=$Arme_Inf_eni;
								else
									$Arme=0;
								if($Arme >0)
								{
									//$con=dbconnecti();
									$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
									//mysqli_close($con);
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
									if($Officier_eni >0)
									{
										if($Arme ==136)
											$Muns_Stock=GetData("Regiment","ID",$Reg_eni,"Stock_Essence_87");
										else
											$Muns_Stock=GetData("Regiment","ID",$Reg_eni,"Stock_Munitions_".$Arme_Cal);
									}
									else
										$Muns_Stock=9999;
									$Muns_Conso=$Veh_Nbr_eni_Ori*$Arme_Multi;									
									if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
									{
										if($Officier_eni >0)
										{
											if($Arme ==136)
												UpdateData("Regiment","Stock_Essence_87",-$Muns_Conso,"ID",$Reg_eni);
											else
												UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg_eni);
										}									
										$msg_eni.="<br>L'unité ennemie tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
										//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage,$Munition,$Dist_shoot);
										$Update_Nbr=0;
										$Update_Reput_eni=0;
										$Update_xp_eni=0;
										$Rafale=1;
										if($Veh_Nbr_eni_Ori >25)$Veh_Nbr_eni_Ori=floor($Veh_Nbr_eni_Ori/10);
										if($Categorie ==5 and $Categorie_eni ==5)
											$Rafale=$Veh_Nbr_eni_Ori;
										for($t=1;$t<=$Veh_Nbr_eni_Ori;$t++)
										{
											if($Veh_Nbr_Final <1)break;
											$Tir=mt_rand(0,$Tir_base_eni)+(10-($Dist_shoot/100)); //Bonus init
											if($Matos_eni ==3)$Tir+=2;
											$Defense_tir=$Vitesse+mt_rand(0,$Tactique)-$Cam-($Meteo/2)-$Optics_eni;
											if($debug)$msg_eni_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse.", Taille=".$Cam.", Tac (max)=".$Tactique.", Meteo (/2)=".$Meteo.")";
											if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base_eni))
											{
												$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg)*$Rafale;
												if($Tir >=$Tir_base_eni)$Base_Dg=$Arme_Dg-mt_rand(0,10);
												$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
												$Degats=round(Get_Dmg($Munition_eni,$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
												if(!$Blindage and $Dist_shoot <600 and $Arme_Cal <30 and $Degats <100)$Degats*=2; //Boost MG vs Infanterie
												if($Gyokusai or $Gyokusai_eni)$Degats*=2;
												if($Enfilade_eni)$Degats*=2;
												if($Tranchees_eni)$Degats*=2;
												if($Flanc_Garde_eni and $Tenaille)$Degats*=2;
												if($Flanc_Garde_ia)$Degats*=(1+($Flanc_Garde_ia/10));
												if($Enfilade_ia)$Degats*=(1+($Enfilade_ia/10));
												if($Tranchees_ia)$Degats*=(1+($Tranchees_ia/10));
												if($Pak_front and $mobile !=3)$Degats*=$Pak_front_rate;
												if($Panzerkeil and $Type_eni ==4)$Degats*=$Panzerkeil_rate;
												if($Categorie_eni ==6 and $Categorie ==5)$Degats*=2;
												if($mobile ==5 and $mobile_eni ==5)
												{
													if($Skill_eni ==31)
														$Degats*=1.2;
													elseif($Skill_eni ==132)
														$Degats*=1.3;
													elseif($Skill_eni ==133)
														$Degats*=1.4;
													elseif($Skill_eni ==134)
														$Degats*=1.5;
												}
												if($Pos_eni ==23)
												{
													if($Skill_eni ==41)
														$Degats*=1.1;
													elseif($Skill_eni ==162)
														$Degats*=1.15;
													elseif($Skill_eni ==163)
														$Degats*=1.2;
													elseif($Skill_eni ==164)
														$Degats*=1.25;
												}
												if($Degats >$HP)
												{
													if($Categorie_eni ==6 and $Categorie ==5) //Riposte MG
														$Collateral=1+(floor($Degats/$HP/10));
													else
														$Collateral=1;												
													$Veh_Nbr_Final-=$Collateral;
													$Update_Nbr-=$Collateral;
													$Update_Reput_eni+=$Reput;
													$msg_eni.="<br>Votre unité est touchée et subit <b>".floor($Degats)."</b> dégâts!<br><b>Une de vos unités est détruite!</b>";
													$HP=$HP_ori;
													if($mobile ==5)
													{
														if($Veh_Nbr_Final)
															SetData($DB_reg,"HP",$HP_ori,"ID",$Reg);
														else
															SetData($DB_reg,"HP",0,"ID",$Reg);
													}
												}
												elseif($Degats >1)
												{
													$Update_xp_eni+=1;
													$msg_eni.="<br>Votre unité est touchée et subit <b>".floor($Degats)."</b> dégâts!";
													$HP-=$Degats;
													if($mobile ==5)
													{
														AddEventGround(450,$Veh,$Reg_Officier_ID,$Reg,$Lieu,$Degats,$Reg_eni);
														UpdateData($DB_reg,"HP",-$Degats,"ID",$Reg);
													}
												}
												else
													$msg_eni.="<br>Votre unité est touchée, mais le blindage n'a pas été percé!";
											}
											else
												$msg_eni.="<br>L'ennemi tire à côté!";
										}
										if($Update_Nbr <0)
										{
											UpdateData($DB_reg,"Vehicule_Nbr",$Update_Nbr,"ID",$Reg);
											UpdateData($DB_reg,"Moral",$Update_Nbr,"ID",$Reg);
											AddEventGround(400,$Veh,$Reg_Officier_ID,$Reg,$Lieu,-$Update_Nbr,$Reg_eni);
											if(!$Reg_Officier_ID)
												$Reg_b_ia=1;
											else
												$Reg_b_ia=0;
											if($Officier_eni)
												$Reg_a_ia=0;
											else
												$Reg_a_ia=1;
											AddGroundAtk($Reg_eni,$Reg,$Vehicule,$Veh_Nbr_eni_Ori,$Veh,$Veh_Nbr_atk,$Pos_eni,4,$Lieu,$Placement_eni,$Dist_shoot,-$Update_Nbr,$Reg_a_ia,$Reg_b_ia);
											if($Update_Nbr <2)$Repli=2;
										}
										if($Update_Reput_eni and $Pays_eni !=$country and $Officier_eni >0)
										{
											UpdateData($DB,"Experience",$Update_Reput_eni,"ID",$Reg_eni);
											UpdateData($DB,"Moral",$Update_Reput_eni,"ID",$Reg_eni);
										}
										if($Update_xp_eni and $Pays_eni !=$country and $Officier_eni >0)
										{
											UpdateData($DB,"Experience",$Update_xp_eni,"ID",$Reg_eni);
											UpdateData($DB,"Moral",$Update_xp_eni,"ID",$Reg_eni);
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
					{
						if($Officier_eni >0)
						{
							/*if(IsSkill(29,$Officier_eni))
							{
								$Front=GetData("Officier","ID",$Officier_eni,"Front");
								$Latitude=GetData("Lieu","ID",$Lieu ,"Latitude");
								$Retraite=Get_Retraite($Front,$country,$Latitude);
								SetData("Regiment","Lieu_ID",$Retraite,"ID",$Officier_eni);
							}
							elseif(IsSkill(6,$Officier_eni) and $mobile_eni !=5)
								SetData("Regiment","Position",2,"Officier_ID",$Officier_eni);*/
							$Exp_final_eni=0;
							if($Trait_eni ==11){
								$Exp_final_eni=$Exp_eni_ori;
								if($Exp_final_eni >100)
									$Exp_final_eni=100;
							}
							//$con=dbconnecti();
							$reset=mysqli_query($con,"UPDATE Regiment SET Experience='$Exp_final_eni',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
							Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg_eni'");
							$reset2=mysqli_query($con,"UPDATE Regiment SET Visible=0 WHERE Officier_ID='$Officier_eni'");
							//mysqli_close($con);
							if($Level_eni >10000){
								$Malus_Reput=($Level_eni/1000)+$Reput_eni;
								UpdateData("Officier","Reputation",-$Malus_Reput,"ID",$Officier_eni);
							}
						}
						$msg_eni.="<br>L'ennemi, totalement en déroute, ne peut riposter!";
					}
					if($Distance_tir <$Dist_shoot)
					{
						$Dist_shoot=$Distance_tir;
						$msg.="<br>L'ennemi a l'initiative et engage vos troupes à une distance d'environ ".$Dist_shoot."m";
						$msg_eni.="<br>L'ennemi a l'initiative et engage vos troupes à une distance d'environ ".$Dist_shoot."m";
						//Riposte
						if($HP_eni >0)
						{
							if($Zone ==6)
								$Arme=$Arme_Art_eni;
							elseif($Blindage >0 and $Arme_AT_eni and $Arme_AT_eni !=82)
								$Arme=$Arme_AT_eni;
							elseif($Arme_Art_eni >0 and $Arme_Art_eni !=82 and $Range_eni <3000 and $Munition_eni !=7)
								$Arme=$Arme_Art_eni;
							elseif($Munition_eni <7)
								$Arme=$Arme_Inf_eni;
							else
								$Arme=0;
							if($Arme >0)
							{
								//$con=dbconnecti();
								$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
								//mysqli_close($con);
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
								if($Officier_eni >0)
								{
									if($Arme ==136)
										$Muns_Stock=GetData("Regiment","ID",$Reg_eni,"Stock_Essence_87");
									else
										$Muns_Stock=GetData("Regiment","ID",$Reg_eni,"Stock_Munitions_".$Arme_Cal);
								}
								else
									$Muns_Stock=9999;
								$Muns_Conso=$Veh_Nbr_eni_Ori*$Arme_Multi;
								if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
								{
									if($Officier_eni >0)
									{
										if($Arme ==136)
											UpdateData("Regiment","Stock_Essence_87",-$Muns_Conso,"ID",$Reg_eni);
										else
											UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg_eni);
									}									
									$msg_eni.="<br>L'unité ennemie tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
									//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage,$Munition,$Dist_shoot);
									$Update_Nbr=0;
									$Update_Reput_eni=0;
									$Update_xp_eni=0;
									$Rafale=1;
									if($Veh_Nbr_eni_Ori >25)$Veh_Nbr_eni_Ori=floor($Veh_Nbr_eni_Ori/10);
									if($Categorie ==5 and $Categorie_eni ==5)
										$Rafale=$Veh_Nbr_eni_Ori;
									for($t=1;$t<=$Veh_Nbr_eni_Ori;$t++)
									{
										if($Veh_Nbr_Final <1)break;
										$Tir=mt_rand(0,$Tir_base_eni)+(10-($Dist_shoot/100));
										if($Matos_eni ==3)$Tir+=2;
										$Defense_tir=$Vitesse+mt_rand(0,$Tactique)-$Cam-($Meteo/2)-$Optics_eni;
										if($debug)$msg_eni_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse.", Taille=".$Cam.", Tac (max)=".$Tactique.", Meteo (/2)=".$Meteo.")";
										if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base_eni))
										{
											$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg)*$Rafale;
											if($Tir >=$Tir_base_eni)$Base_Dg=$Arme_Dg-mt_rand(0,10);
											$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
											$Degats=round(Get_Dmg($Munition_eni,$Arme_Cal,$Blindage,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
											if(!$Blindage and $Dist_shoot <600 and $Arme_Cal <30 and $Degats <100)$Degats*=2; //Boost MG vs Infanterie
											if($Gyokusai or $Gyokusai_eni)$Degats*=2;
											if($Enfilade_eni)$Degats*=2;
											if($Tranchees_eni)$Degats*=2;
											if($Flanc_Garde_eni and $Tenaille)$Degats*=2;
											if($Flanc_Garde_ia)$Degats*=(1+($Flanc_Garde_ia/10));
											if($Enfilade_ia)$Degats*=(1+($Enfilade_ia/10));
											if($Tranchees_ia)$Degats*=(1+($Tranchees_ia/10));
											if($Pak_front and $mobile !=3)$Degats*=$Pak_front_rate;
											if($Panzerkeil and $Type_eni ==4)$Degats*=$Panzerkeil_rate;
											if($Categorie_eni ==6 and $Categorie ==5)$Degats*=2;
											if($mobile ==5 and $mobile_eni ==5)
											{
												if($Skill_eni ==31)
													$Degats*=1.2;
												elseif($Skill_eni ==132)
													$Degats*=1.3;
												elseif($Skill_eni ==133)
													$Degats*=1.4;
												elseif($Skill_eni ==134)
													$Degats*=1.5;
											}
											if($Pos_eni ==23)
											{
												if($Skill_eni ==41)
													$Degats*=1.1;
												elseif($Skill_eni ==162)
													$Degats*=1.15;
												elseif($Skill_eni ==163)
													$Degats*=1.2;
												elseif($Skill_eni ==164)
													$Degats*=1.25;
											}
											if($Degats >$HP)
											{
												$Veh_Nbr_Final-=1;
												$Update_Nbr-=1;
												$Update_Reput_eni+=$Reput;
												$msg_eni.="<br>Votre unité est touchée et subit <b>".floor($Degats)."</b> dégâts!<br><b>Une de vos unités est détruite!</b>";
												$HP=$HP_ori;
												if($mobile ==5)
												{
													if($Veh_Nbr_Final)
														SetData($DB_reg,"HP",$HP_ori,"ID",$Reg);
													else
														SetData($DB_reg,"HP",0,"ID",$Reg);
												}
											}
											elseif($Degats >1)
											{
												$Update_xp_eni+=1;
												$msg_eni.="<br>Votre unité est touchée et subit <b>".floor($Degats)."</b> dégâts!";
												$HP-=$Degats;
												if($mobile ==5)
												{
													AddEventGround(450,$Veh,$Reg_Officier_ID,$Reg,$Lieu,$Degats,$Reg_eni);
													UpdateData($DB_reg,"HP",-$Degats,"ID",$Reg);
												}
											}
											else
												$msg_eni.="<br>Votre unité est touchée, mais le blindage n'a pas été percé!";
										}
										else
											$msg_eni.="<br>L'ennemi tire à côté!";
									}
									if($Update_Nbr <0)
									{
										UpdateData($DB_reg,"Vehicule_Nbr",$Update_Nbr,"ID",$Reg);
										UpdateData($DB_reg,"Moral",$Update_Nbr,"ID",$Reg);
										AddEventGround(400,$Veh,$Reg_Officier_ID,$Reg,$Lieu,-$Update_Nbr,$Reg_eni);
										if(!$Reg_Officier_ID)
											$Reg_b_ia=1;
										else
											$Reg_b_ia=0;
										if($Officier_eni)
											$Reg_a_ia=0;
										else
											$Reg_a_ia=1;
										AddGroundAtk($Reg_eni,$Reg,$Vehicule,$Veh_Nbr_eni_Ori,$Veh,$Veh_Nbr_atk,$Pos_eni,4,$Lieu,$Placement_eni,$Dist_shoot,-$Update_Nbr,$Reg_a_ia,$Reg_b_ia);
										if($Update_Nbr <2)$Repli=2;
									}
									if($Update_Reput_eni and $Pays_eni !=$country and $Officier_eni >0)
									{
										UpdateData($DB,"Experience",$Update_Reput_eni,"ID",$Reg_eni);
										UpdateData($DB,"Moral",$Update_Reput_eni,"ID",$Reg_eni);
									}
									if($Update_xp_eni and $Pays_eni !=$country and $Officier_eni >0)
									{
										UpdateData($DB,"Experience",$Update_xp_eni,"ID",$Reg_eni);
										UpdateData($DB,"Moral",$Update_xp_eni,"ID",$Reg_eni);
									}
								}
								else
									$msg_eni.="<br>L'ennemi, à court de munitions, ne peut riposter!";
							}
							else
								$msg_eni.="<br>L'ennemi, pris de flanc, ne peut riposter!";
						}
					}
					if($Distance_tir <$Dist_shoot and $Repli ==2)
						$msg.="<br>Vos troupes sont refoulées par l'ennemi, ne parvenant pas à s'approcher à la distance de tir voulue!";
					else
					{
						if($Repli !=2)$Dist_shoot=$Distance_tir;							
						if($Veh_Nbr_atk >0 and $Range >=$Dist_shoot)
						{
							$msg.="<br>Vos troupes progressent en direction de l'ennemi, ".$Veh_Nbr_atk." ".$Veh_Nom." (sur une force originale de ".$Veh_Nbr_Ori.") l'engagent à une distance d'environ ".$Dist_shoot."m";
							//Tir
							if($Armement ==1)
								$Arme=$Arme_Inf;
							elseif($Zone ==6 or $mobile ==5)
								$Arme=$Arme_Art;
							elseif($Blindage_eni >0 and $Arme_AT and $Arme_AT !=82)
								$Arme=$Arme_AT;
							elseif($Arme_Art >0 and $Arme_Art !=82 and $Range <3000 and $Munition !=7)
								$Arme=$Arme_Art;
							elseif($Munition <7)
								$Arme=$Arme_Inf;
							else
								$Arme=0;
							if($Arme >0)
							{
								//$con=dbconnecti();
								$result3=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
								//mysqli_close($con);
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
								if($Reg_Officier_ID >0)
								{
									if($Arme ==136)
										$Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Essence_87");
									else
										$Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Munitions_".$Arme_Cal);
								}
								else
									$Muns_Stock=9999;
								$Muns_Conso=$Veh_Nbr_atk*$Arme_Multi;
								if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
								{
									if($Reg_Officier_ID >0)
									{
										if($Arme ==136)
											UpdateData("Regiment","Stock_Essence_87",-$Muns_Conso,"ID",$Reg);
										else
											UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg);
									}
									$msg.="<br>Votre unité tire ".$Muns_Conso." munitions à l'aide de son ".$Arme_Nom;
									//$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage_eni,$Munition,$Dist_shoot);
									$Update_Nbr_eni=0;
									$Update_Reput=0;
									$Update_xp=0;
									$Rafale=1;
									if($Veh_Nbr_atk >25)
									{
										$Veh_Nbr_atk_dispersion=$Veh_Nbr_atk;
										$Veh_Nbr_atk=floor($Veh_Nbr_atk/10);
									}
									if($Categorie ==5 and $Categorie_eni ==5)
										$Rafale=$Veh_Nbr_atk;
									for($t=1;$t<=$Veh_Nbr_atk;$t++)
									{
										if($Veh_Nbr_eni <1 or $Update_Nbr_eni <-$Max_Kill)
											break;
										$Tir=mt_rand(0,$Tir_base);
										if($Matos ==3)$Tir+=2;
										$Defense_tir=$Vitesse_eni+mt_rand(0,$Tactique_eni)-$Cam_eni-($Meteo/2)-$Optics;
										if($debug)$msg_debug.="<br>[DEBUG] Tir=".$Tir."/".$Defense_tir." (Vitesse=".$Vitesse_eni.", Taille=".$Cam_eni.", Tac (max)=".$Tactique_eni.", Meteo (/2)=".$Meteo.")";
										if($Raid and mt_rand(0,100)<$Raid_rate)$Tir=0;
										if($Sur_les_toits and mt_rand(0,100)<$Sur_les_toits)$Tir=0;
										if($Deception and mt_rand(0,100)<$Deception_rate)$Tir=0;
                                        /**
                                         * 23/09/17
                                         */
                                        if($country ==2 or $country ==4){
                                            $Tir-=10;
                                        }
										if($Premium)
										{
											$pc_score=(300+$Tir-$Defense_tir)/6;
											$Bar_pc=round($pc_score,1,PHP_ROUND_HALF_DOWN);
											if($Tir >$Defense_tir and $Tir >1)
												$skills.="Efficacité de votre ".$t."e tir<br><div class='progress'><div class='progress-bar-success' role='progressbar' aria-valuenow='".$pc_score."' aria-valuemin='0' aria-valuemax='100' style='width: ".$pc_score."%'>".$Bar_pc."%</div></div>";
											else
												$skills.="Efficacité de votre ".$t."e tir<br><div class='progress'><div class='progress-bar-danger' role='progressbar' aria-valuenow='".$pc_score."' aria-valuemin='0' aria-valuemax='100' style='width: ".$pc_score."%'>".$Bar_pc."%</div></div>";
										}
										else
											$skills.="Efficacité de votre ".$t."e tir<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100' style='width:100%'>?%</div></div>";						
										if($Tir >1 and ($Tir >$Defense_tir or $Tir >=$Tir_base))
										{
											$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg)*$Rafale;
											if($Tir >=$Tir_base)$Base_Dg=$Arme_Dg-mt_rand(0,10);
											$Degats=$Base_Dg*GetShoot($Tir,$Arme_Multi);
											if($Tenaille)
											{
												$Degats*=$Tenaille;
												if($Trait_o ==3)$Degats*=2;
											}
											$Degats=round(Get_Dmg($Munition,$Arme_Cal,$Blindage_eni,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
											if(!$Blindage_eni and $Dist_shoot <600 and $Arme_Cal <30 and $Degats <100)$Degats*=2; //Boost MG vs Infanterie
											if($Pos_eni ==1 and $Defense_elastique and mt_rand(0,100)<$Defense_rate)
											{
												if($Trait ==3)
													$Degats/=4;
												else
													$Degats/=2;
											}
											if($Herisson and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7))$Degats*=$Herisson_rate;
											if($Gyokusai or $Gyokusai_eni)$Degats*=2;
											if($Pos_eni ==8 and $Categorie ==5)$Degats*=2;
											if($Raid and $Trait_o ==2)$Degats/=2;
											if($Mode ==4 and (($Veh_Nbr_atk_dispersion/5) >$Veh_Nbr_eni) and ($Pos_eni ==8 or $Pos_eni ==9 or $Pos_eni==7 or $Pos_eni ==6))
											{
												$msg.="<br>Votre unité disperse les fantassins ennemis désorganisés!</b>";
												$Degats*=$Veh_Nbr_atk_dispersion;
                                                if($Degats >65535)$Degats=65535;
                                                if($Degats <1)$Degats=1;
											}
											if($Degats >$HP_eni or ($Encercle and $Degats >($HP_eni/2)))
											{
												if($Charge_eni and $Fret_eni >0)
												{
													if($Fret_eni ==888)
														UpdateData("Pays","Special_Score",-1,"ID",$Pays_eni);
													elseif($Fret_eni ==200 and $Fret_Qty_eni >0)
													{
														//$con=dbconnecti();
														$reset=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0,HP=0,Position=6 WHERE ID='$Reg_eni'");
														$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Moral=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Experience=0,Skill=0,Visible=0 WHERE ID='$Fret_Qty_eni'");
														//mysqli_close($con);
													}
													if($Officier_eni){
														$Perte_Stock=$Fret_Qty_eni/$Veh_Nbr_eni;
														UpdateData("Regiment","Fret_Qty",-$Perte_Stock,"ID",$Reg_eni);
													}
												}
												$Veh_Nbr_eni-=1;
												$Update_Nbr_eni-=1;
												$Update_Reput+=$Reput_eni;
												$msg.="<br>Votre unité touche la cible et lui occasionne <b>".floor($Degats)."</b> dégâts!<br><b>La cible est détruite</b>!";
												$HP_eni=$HP_ori_eni;
												if($mobile_eni ==5)
												{
													if($Veh_Nbr_eni)
														SetData($DB,"HP",$HP_ori_eni,"ID",$Reg_eni);
													else
														SetData($DB,"HP",0,"ID",$Reg_eni);
													if($Transit_eni >0)
													{
														//$con=dbconnecti();
														$reset=mysqli_query($con,"UPDATE Regiment SET Experience=0,Skill=0,Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
														Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
														Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Visible=0 WHERE Officier_ID='$Transit_eni' AND Vehicule_Nbr>0 ORDER BY RAND() LIMIT 1");
														//mysqli_close($con);
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
												{
													AddEventGround(461,$Vehicule,$Reg_Officier_ID,$Reg_eni,$Lieu,$Degats,$Reg);
													UpdateData($DB,"HP",-$Degats,"ID",$Reg_eni);
												}
											}
											else
												$msg.="<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
										}
										else
											$msg.="<br>Votre unité rate la cible!";
									}
									if($Update_Nbr_eni <0)
									{
										if(($Categorie_eni ==5 or $Categorie_eni ==6) and !$Arme_AT_eni and ($Type ==7 or $Type ==10 or $Type ==91))
										{
											$msg_eni.='<br>Vos blindés mettent en fuite une partie des fantassins ennemis!';
											//$con=dbconnecti();
											$units_eni=mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3,5,10) AND r.Placement='$Placement_eni'");
											//(SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3,5,10) AND r.Placement='$Placement_eni')
											//mysqli_close($con);
											$Mult_Fear_TK=1;
											if($Pos_eni ==8 or $Pos_eni ==9)
												$Mult_Fear_TK+=1;
											if($units_eni <2)
												$Mult_Fear_TK+=1;
											if($Mult_Fear_TK >$Veh_Nbr_eni)$Mult_Fear_TK=$Veh_Nbr_eni;
											if($Mult_Fear_TK <1)$Mult_Fear_TK=1;
											$Update_Nbr_eni-=($Veh_Nbr_atk*$Mult_Fear_TK);
											$Veh_Nbr_eni-=($Veh_Nbr_atk*$Mult_Fear_TK);
										}
										UpdateData($DB,"Vehicule_Nbr",$Update_Nbr_eni,"ID",$Reg_eni);
										UpdateData($DB,"Moral",$Update_Nbr_eni,"ID",$Reg_eni);
										AddEventGround(401,$Vehicule,$Reg_Officier_ID,$Reg_eni,$Lieu,-$Update_Nbr_eni,$Reg);
										if(!$Reg_Officier_ID)
											$Reg_a_ia=1;
										else
											$Reg_a_ia=0;
										if(!$Officier_eni)
											$Reg_b_ia=1;
										else
											$Reg_b_ia=0;
										AddGroundAtk($Reg,$Reg_eni,$Veh,$Veh_Nbr_atk,$Vehicule,$Veh_Nbr_eni_Ori,4,$Pos_eni,$Lieu,$Placement_eni,$Dist_shoot,-$Update_Nbr_eni,$Reg_a_ia,$Reg_b_ia);
										/*if($Pos_eni ==2)SetData($DB,"Visible",0,"ID",$Reg_eni);*/
									}
									if($Reg_Officier_ID >0)
									{
										if($Update_Reput and $Pays_eni !=$country)
										{
											if($Trait_o ==1)
												$Update_Reput*=2;
											/*if($Division >0 and $hatk ==$Heure and $Lieu ==$datk)
												$Update_Reput*=2;*/
											UpdateData("Regiment","Experience",$Update_Reput,"ID",$Reg);
											UpdateData("Regiment","Moral",$Update_Reput,"ID",$Reg);
											UpdateData("Officier","Avancement",$Update_Reput,"ID",$Reg_Officier_ID);
											UpdateData("Officier","Reputation",$Update_Reput,"ID",$Reg_Officier_ID);
										}
										elseif($Update_Reput and $Pays_eni ==$country)
										{
											UpdateData("Officier","Avancement",-$Update_Reput,"ID",$Reg_Officier_ID);
											UpdateData("Officier","Reputation",-$Update_Reput,"ID",$Reg_Officier_ID);
										}
										if($Update_xp and $Pays_eni !=$country)
										{
											UpdateData("Regiment","Experience",$Update_xp,"ID",$Reg);
											UpdateData("Regiment","Moral",$Update_xp,"ID",$Reg);
											UpdateData("Officier","Avancement",$Update_xp,"ID",$Reg_Officier_ID);
											UpdateData("Officier","Reputation",$Update_xp,"ID",$Reg_Officier_ID);
										}
									}
								}
								else
									$msg.="<br>Votre unité annule son attaque, faute de munitions!";
							}
							else
								$msg.="<br>Votre unité annule son attaque, faute d'armement adéquat!";
						}
						elseif($Veh_Nbr_Final <1)
							$msg.="<br>Votre unité, en déroute, annule son attaque!";
						else
							$msg.="<br>Votre unité se replie!";
					}
				}
			}
			elseif($Smoke ==true)
				$msg.="<br>L'ennemi prend la fuite derrière un écran de fumée! Vous ne pouvez poursuivre votre attaque!";
			if($Veh_Nbr_Final <1)
			{
				if($DB_reg =="Regiment")
				{
					$Exp_final=0;
					if($Trait_o ==11)
					{
						$Exp_final=$Experience_ori;
						if($Exp_final >100)$Exp_final=100;
					}
					$query_loose="UPDATE Regiment SET Experience='$Exp_final',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
					Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
					Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Atk_IA=1 WHERE ID='$Reg'";
					if($Reputation >10000 and $Reg_Officier_ID >0)
					{
						$Malus_Reput=($Reputation/1000)+$Reput;
						UpdateData("Officier","Reputation",-$Malus_Reput,"ID",$Reg_Officier_ID);
					}
				}
				else
					$query_loose="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,HP=0,Atk_IA=1 WHERE ID='$Reg'";
				$msg.='<br>Votre unité, en déroute, annule son attaque!';
				//$con=dbconnecti();
				$reset=mysqli_query($con,$query_loose);
				//mysqli_close($con);
			}
			if($Veh_Nbr_eni <1)
			{
				if($DB =='Regiment')
				{
					$Exp_final_eni=0;
					if($Trait_eni ==11)
					{
						$Exp_final_eni=$Exp_eni_ori;
						if($Exp_final_eni >100)$Exp_final_eni=100;
					}
					$query_loose_eni="UPDATE Regiment SET Experience='$Exp_final_eni',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
					Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Atk_IA=1 WHERE ID='$Reg_eni'";
					if($Level_eni >10000)
					{
						$Malus_Reput=($Level_eni/1000)+$Reput_eni;
						UpdateData("Officier","Reputation",-$Malus_Reput,"ID",$Officier_eni);
					}
				}
				else
					$query_loose_eni="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Fret=0,Fret_Qty=0,HP=0,Atk_IA=1 WHERE ID='$Reg_eni'";
				//$con=dbconnecti();
				$reset=mysqli_query($con,$query_loose_eni);
				//mysqli_close($con);
			}
			if($Cr_mini >0 and $OfficierID >3 and $Cr_mini !=99 and $Cr_mini !=98)
				UpdateData("Officier","Credits",-$Cr_mini,"ID",$OfficierID);
			if($DB_reg =="Regiment_IA" and $Pass >0 and $Update_Nbr_eni <0)$query_regeni_add=",Atk=1,Atk_Eni='$Reg',Atk_time=NOW()";
			if($Sous_le_feu)$query_reg_add=",Position=8";
			//$con=dbconnecti();
			$resetreg=mysqli_query($con,"UPDATE $DB_reg SET Bomb_PJ=0,Visible=1,Camouflage=1,Experience=Experience+10,Combats=Combats+1".$query_reg_add.",Atk=1,Atk_Eni=0,Atk_time=NOW() WHERE ID='$Reg'");
			$resetregeni=mysqli_query($con,"UPDATE $DB SET Experience=Experience+5,Combats=Combats+1".$query_regeni_add." WHERE ID='$Reg_eni'");
			mysqli_close($con);
			$titre='Combat';
			$mes="<a href='#' class='popup'><img src='images/help.png'><span>L'expérience de votre unité améliore l'efficacité du tir. Plus une unité est rapide (vitesse modifiée) et plus sa taille est réduite, plus elle est difficile à toucher. La vitesse d'une unité retranchée est nulle, mais sa taille est divisée par 4. La nature du terrain et le système de propulsion modifie la vitesse, tandis que le camouflage modifie la taille.</span></a>
			<table class='table table-striped'>
			<thead><tr><th></th><th>".$Reg."e Compagnie</th><th>".$Reg_eni."e Compagnie</th></tr></thead>
			<tr><td align='left'>Troupes</td><td><img src='images/vehicules/vehicule".$Veh.".gif' title='".$Veh_Nom."'></td><td><img src='images/vehicules/vehicule".$Vehicule.".gif' title='".$Veh_Nom_eni."'></td></tr>
			<tr><td align='left'>Nation</td><td><img src='images/".$country."20.gif'></td><td><img src='images/".$Pays_eni."20.gif'></td></tr>
			<tr><td align='left'>Terrain</td><td colspan='2'><img src='images/zone".$Zone.".jpg'></td></tr>
			<tr><td align='left'>Position</td><td>".GetPosGr($Pos)."</td><td>".GetPosGr($Pos_eni)."</td></tr>
			<tr><td align='left'>Tir</td><td>".$Tir_base."</td><td>".$Tir_base_eni."</td></tr>
			<tr><td align='left'>Camouflage</td><td>".round($Cam)."</td><td>".round($Cam_eni)."</td></tr>
			<tr><td align='left'>Blindage</td><td>".$Blindage."</td><td>".$Blindage_eni."</td></tr>
			<tr><td align='left'>Vitesse</td><td>".round($Vitesse)."km/h (sur une base de ".$Vitesse_ori.")</td><td>".round($Vitesse_eni)."km/h</td></tr>
			<tr><td>Résumé</td><td align='left'>".$msg."</td><td align='left'>".$msg_eni."</td></tr>
			<tr><td>Pertes</td><td>".$Update_Nbr."</td><td>".$Update_Nbr_eni."</td></tr>
			</table>";
			if($Admin or $Premium)
			{
				if($Veh_Nbr_Final <$Veh_Nbr_Ori)$graph_reg="<img src='images/map/lieu_fire.png'> ".$Veh_Nbr_Final."/".$graph_reg;
				if($Veh_Nbr_eni <$Veh_Nbr_eni_Ori)$graph_reg_eni="<img src='images/map/lieu_fire.png'> ".$Veh_Nbr_eni."/".$graph_reg_eni;
				$mes.="<div class='row'><div class='col-md-1'></div><div class='col-md-1'></div><div class='col-md-1'></div><div class='col-md-1'></div><div class='col-md-1'></div><div class='col-md-1'></div>
				<div class='col-md-1'>".$graph_reg."</div><div class='col-md-1'></div><div class='col-md-1'>".$graph_reg_eni."</div><div class='col-md-1'>".$graph_AT_eni."</div><div class='col-md-1'>".$graph_couv_eni."</div><div class='col-md-1'>".$graph_art_eni."</div></div>";
			}
			if($OfficierEMID >0 and !$OfficierID)
			{
				UpdateData("Officier_em","Reputation",20,"ID",$OfficierEMID);
				$menu="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}
			include_once('./default.php');
			//if($Update_Nbr or $Update_Nbr_eni)
			$msg_eni_debug.='<br> En ligne='.$Ligne;
			mail("binote@hotmail.com","Aube des Aigles: Combat","Joueur : ".$OfficierID." ( Off unité=".$OfficierEMID.") dans les environs de : ".$Lieu_Nom."<br>Attaque de ".$Veh_Nom." sur ".$Veh_Nom_eni." <html>".$mes.$msg_debug.$msg_eni_debug."</html>","Content-type: text/html; charset=utf-8");
		}
		elseif($Atk)
            header('Location : index.php');
        else
			echo "<h6>Erreur de sélection de véhicules!</h6> [Code d'erreur] : ".$Officier_eni."/".$Reg_eni;
	}
	else
		echo '<h6>Pas assez de crédits!</h6>';
}