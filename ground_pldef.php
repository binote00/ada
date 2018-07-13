<?php
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 xor $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$Reg=Insec($_POST['Reg']);
	$Veh=Insec($_POST['Veh']);
	$Cible=Insec($_POST['Cible']);
	$Conso=Insec($_POST['Conso']);
	$Bomb=Insec($_POST['Bomb']);
	$CT=Insec($_POST['CT']);
	$country=$_SESSION['country'];
	$choix='';
	$go=false;
	if($CT >0 and $Reg >0 and $Veh >0 and $Cible >0 and $OfficierID >0) //n'affecte pas les actions EM
	{
		SetData("Officier","Atk",1,"ID",$OfficierID);
		SetData("Officier","Heure_Mission",date('H'),"ID",$OfficierID);
		SetData("Regiment","Atk_H",date('H'),"ID",$Reg);
		$Credits=GetData("Officier","ID",$OfficierID,"Credits");
		$DB='Regiment';
		if($Credits >=$CT)$go=true;
	}
	elseif(!$CT and !$Veh and !$Conso and !$Cible and $Reg >0 and ($Bomb ==1 or $Bomb ==2 or $Bomb ==3 or $Bomb ==4 or $Bomb ==12)){
		$DB='Regiment_IA';
		$go=true;
	}
	if($go)
	{
        $intro='<div class="alert alert-warning">';
		//Get Reg & Lieu
		$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$result=mysqli_query($con,"SELECT Experience,Lieu_ID,Vehicule_ID,Vehicule_Nbr,`Position`,Placement,HP,Muns,Visible,Skill,Matos FROM $DB WHERE ID='$Reg'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pldef-reg');
		if($result){
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				$Reg_xp=$data['Experience'];
				$Lieu=$data['Lieu_ID'];
				$Veh=$data['Vehicule_ID'];
				$Vehicule_Nbr=$data['Vehicule_Nbr'];
				$Pos=$data['Position'];
				$Placement=$data['Placement'];
				$HP=$data['HP'];
				$Muns=$data['Muns'];
				$Visible=$data['Visible'];
				$Skill=$data['Skill'];
				$Matos=$data['Matos'];
			}
			mysqli_free_result($result);
		}
		if($DB =='Regiment_IA')$Cible=$Lieu;
		$result2=mysqli_query($con,"SELECT Flag,`Zone`,Meteo FROM Lieu WHERE ID='$Lieu'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pldef-lieu');
		if($result2){
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
				$Flag=$data['Flag'];
				$Zone=$data['Zone'];
				$meteo=$data['Meteo'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		if($Bomb ==1) //Bombardement
		{
			$resultc=mysqli_query($con,"SELECT `Type`,Portee FROM Cible WHERE ID='$Veh'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pldef-veh');
			mysqli_close($con);
			if($resultc){
				while($datac=mysqli_fetch_array($resultc,MYSQLI_ASSOC)){
					$Type=$datac['Type'];
					$Range=$datac['Portee'];
				}
				mysqli_free_result($resultc);
			}
			$Range+=($Reg_xp*2);
			if($Muns ==8 or $Matos ==8)$Range /=2;
            if($Flag ==$country)$Range +=500;
			if($Pos ==POS_RETRANCHE or $Pos ==POS_EMBUSCADE or $Pos ==POS_CLOUE_AU_SOL or $Pos ==POS_EN_LIGNE or $Pos ==26)$Range /=2;
            if($CT >0){
                /*if(IsSkill(15,$OfficierID)){
                    $Range*=1.1;
                    $intro.="<br>Vos troupes bénéficient de votre compétence <b>Artilleur Expert</b> !";
                }*/
            }
            else{
                if($Skill ==73){
                    $Art_Exp=true;
                    $Range*=1.25;
                }
                elseif($Skill ==72){
                    $Art_Exp=true;
                    $Range*=1.2;
                }
                elseif($Skill ==47){
                    $Art_Exp=true;
                    $Range*=1.15;
                }
                elseif($Skill ==15){
                    $Art_Exp=true;
                    $Range*=1.1;
                }
                if($Art_Exp)$intro.="<br>Vos troupes bénéficient de la compétence <b>Artilleur Expert</b> !";
            }
			if($meteo <-69)$Range /=2;
			if($Zone !=6){
				if($DB =='Regiment' and $CT >0)
					UpdateData("Regiment","Moral",-5,"ID",$Reg);
			}
			else
				$Range+=($Reg_xp*9);
			$dive='ground_bomb';
			$Veh_Nbr=1;
			$_SESSION['ground_bomb']=true;
		}
		else
		{
			$result=mysqli_query($con,"SELECT Nom,Vitesse,Sol_meuble,mobile,Carbu_ID,`Type`,Blindage_f,Portee,HP FROM Cible WHERE ID='$Veh'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pldef-veh');
			mysqli_close($con);
			if($result){
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					$Nom=$data['Nom'];
					$Vitesse=$data['Vitesse'];
					$mobile=$data['mobile'];
					$Type=$data['Type'];
					$Blindage=$data['Blindage_f'];
					$Range=$data['Portee'];
					$Veh_Carbu=$data['Carbu_ID'];
					if($mobile ==5)$hp_good=round(($HP/$data['HP'])*100);
					$Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type,$hp_good,$data['Sol_meuble']);
					if($Matos ==10)$Vitesse*=1.1;
					elseif($Matos ==14)$Vitesse*=1.5;
                    elseif($Matos ==30)$Vitesse/=1.25;
                    if($Flag ==$country)$Vitesse+=10;
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($Bomb ==2 or $Bomb ==12) //Torpillage
			{
				if($Pos ==25 or $Pos ==28)
				{
					$con=dbconnecti();
					$ASM=mysqli_query($con,"SELECT COUNT(r.ID) FROM Regiment_IA as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position=24 AND c.mobile=5 AND c.Type<18 AND c.Arme_Inf >0");
					//SELECT COUNT(*) FROM ((SELECT COUNT(r.ID) FROM Regiment as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position=24 AND c.mobile=5 AND c.Type<18 AND c.Arme_Inf >0) UNION (
					mysqli_close($con);
				}
				if($Placement ==PLACE_PORT or ($Pos ==25 and !$ASM))$Range=20000;
				$Range+=($Reg_xp*2);
				if($Muns ==8 or $Matos ==8)$Range/=2;
				if($Pos ==26)
					$Range/=2;
				elseif($Pos ==25 or $Pos ==28)
					$Range*=2;
				$dive='ground_torp';
			}
			else
			{
				$Range=($Vitesse*100)+($Reg_xp*2);
				if($Pos ==2 or $Pos ==3 or $Pos ==9 or $Pos ==10)$Range/=2;
				if($mobile ==7)$Range*=2;
				$dive='ground_pl';
				if($Bomb !=3)
				{
					if($mobile !=5)
						$Armement="<br><b>Armement </b><select name='armement' style='width: 200px'><option value='0'>Laisser le commandant de compagnie décider</option><option value='1'>Imposer l'utilisation de l'armement de base</option></select>";
					$Repli="<br><b>Repli </b>
					<select name='repli' style='width: 200px'>
					<option value='0'>Continuer l'attaque quoi qu'il arrive</option>
					<option value='1'>Se replier si nécessaire</option>
					<option value='2'>Se replier dès que possible</option>
					</select>";
				}
			}
			if($DB =='Regiment_IA' and ($Bomb ==2 or $Bomb ==12))
				$Veh_Nbr=1;
			else
				$Veh_Nbr=GetData($DB,"ID",$Reg,"Vehicule_Nbr");
			if($Conso and $DB =='Regiment')
			{
				if($Veh_Carbu ==87 or $Veh_Carbu ==1)
					$Stock='Stock_Essence_'.$Veh_Carbu;
				else
					$Stock='Moral';
				$Jauge=GetData("Regiment","ID",$Reg,$Stock);
				if($Jauge >=$Conso)
					UpdateData("Regiment",$Stock,-$Conso,"ID",$Reg);
				else
				{
					$Diff=($Conso-$Jauge)/10;
					SetData("Regiment",$Stock,0,"ID",$Reg);
					if($Diff >0)
					{
						UpdateData("Regiment","Vehicule_Nbr",-$Diff,"ID",$Reg);
						UpdateData("Regiment","Moral",-$Diff,"ID",$Reg);
						$Veh_Nbr=GetData("Regiment","ID",$Reg,"Vehicule_Nbr");
						AddEventGround(410,$Veh,$OfficierID,$Reg,$Cible,$Conso);
						$intro.="<br>Une partie de vos troupes déserte!";
					}
				}
			}			
			$choix_dist='';
			$Min_Range=500;
			if($Zone ==6)
			{
				$Step=500;
				if($meteo <-69)
					$Max_Range=5000;
				elseif($meteo <-9)
					$Max_Range=10000;
				else
					$Max_Range=20000;
			}
			elseif($Bomb ==2 or $Bomb ==12)
			{
				$Step=500;
				if($meteo <-69)
					$Max_Range=500;
				elseif($meteo <-9)
					$Max_Range=1000;
				else
					$Max_Range=2000;
			}
			elseif($Zone ==2 or $Zone ==3 or $Zone ==5 or $Zone ==7 or $Zone ==9 or $Zone ==10)
			{
				$Min_Range=100;
				$Step=100;
				if($meteo <-69)
					$Max_Range=200;
				else
					$Max_Range=500;
			}
			elseif($Zone ==1 or $Zone ==4)
			{
				$Min_Range=250;
				$Step=250;
				if($meteo <-69)
					$Max_Range=500;
				elseif($meteo <-9)
					$Max_Range=750;
				else
					$Max_Range=1000;
			}
			else //Zone 0 et 8 (désert et plaine)
			{
				$Step=500;
				if($meteo <-69)
					$Max_Range=500;
				elseif($meteo <-9)
					$Max_Range=1500;
				else
					$Max_Range=4000;
			}
			if($Range >0 and $Max_Range >$Range)$Max_Range=$Range;
			if($Bomb ==4)
				$choix_dist.="<option value='".$Min_Range."'>".$Min_Range."m</option><option value='".$Max_Range."'>".$Max_Range."m</option>";
			else
			{
				for($i=$Min_Range;$i<=$Max_Range;$i+=$Step)
				{
					if($i >$Max_Range)
						break;
					$choix_dist.="<option value='".$i."'>".$i."m</option>";
				}
			}
			$Distance_tir="<br><label for='distance'>Distance de tir</label> 
				<select name='distance' class='form-control' style='width: 100px'>".$choix_dist."</select>";
		}		
		if($Veh_Nbr >0)
		{
			if($Bomb ==1)
			{
				if($Range >2500)
				{
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.HP,c.HP as HP_Max FROM Regiment_IA as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Placement<>9 AND r.Pays<>'$country' AND r.Visible=1 AND r.Position<>25 AND r.Arti_IA=0";
					/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.HP,c.HP as HP_Max FROM Regiment as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Placement<>9 AND r.Officier_ID<>'$OfficierID' AND r.Pays<>'$country' AND r.Visible=1 AND r.Position<>25 AND r.Arti_IA=0) UNION (*/
				}
				else
				{
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment_IA as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Placement='$Placement' AND r.Pays<>'$country' AND r.Visible=1 AND r.Position<>25 AND r.Arti_IA=0";
					/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Placement='$Placement' AND r.Officier_ID<>'$OfficierID' AND r.Pays<>'$country' AND r.Visible=1 AND r.Position<>25 AND r.Arti_IA=0) UNION (*/
				}
			}
			elseif($Zone ==6 or $Placement ==PLACE_LARGE or $Bomb ==2 or $Bomb ==12) //Torpiller
			{
				$Pass=$Vehicule_Nbr;
				$con=dbconnecti();
				$result_inf=mysqli_query($con,"SELECT r.ID,r.Experience,r.Officier_ID FROM Regiment_IA as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.mobile=5 AND c.Type IN (15,16,17,18,19,20) AND r.Placement='$Placement' AND r.Position=21");
				/*(SELECT r.ID,r.Experience,r.Officier_ID FROM Regiment as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.mobile=5 AND c.Type IN (15,16,17,18,19,20) AND r.Placement='$Placement' AND r.Position=21) UNION (*/
				$Rudels=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Pays='$country' AND r.Placement=8 AND r.Vehicule_Nbr >0 AND c.Type=37"),0);
				mysqli_close($con);
				if($result_inf)
				{
					$Inf_eni=mysqli_num_rows($result_inf)+$ASM;
					if($Skill ==42)$Inf_eni*=1.25;
					elseif($Skill ==165)$Inf_eni*=1.5;
					elseif($Skill ==166)$Inf_eni*=1.75;
					elseif($Skill ==167)$Inf_eni*=2;
					while($datai=mysqli_fetch_array($result_inf,MYSQLI_NUM))
					{
						$Inf_couv=$datai[0];
						$Exp_eni=$datai[1];
						/*$Infoff_couv=$datai[2];
						if($Infoff_couv >0)
						{
							$con=dbconnecti();
							$update_inf=mysqli_query($con,"UPDATE Regiment SET Moral='$Moral_eni',Experience=Experience+1 WHERE ID='$Inf_couv'");
							$update_off=mysqli_query($con,"UPDATE Officier SET Avancement=Avancement+1,Reputation=Reputation+1 WHERE ID='$Infoff_couv'");
							mysqli_close($con);
						}*/
						if(($Exp_eni+($Inf_eni*100))>(mt_rand(0,$Reg_xp)+$Vitesse))$Pass-=1;
					}
					mysqli_free_result($result_inf);
				}
				if($Placement ==PLACE_PORT)
					$Range=20000; //Au port tous les navires de surface peuvent être ciblés
				elseif($Skill ==43)
					$Range*=(1+((5*$Rudels)/10));
				elseif($Skill ==168)
					$Range*=(1+((10*$Rudels)/10));
				elseif($Skill ==169)
					$Range*=(1+((15*$Rudels)/10));
				elseif($Skill ==170)
					$Range*=(1+((20*$Rudels)/10));
				$Range=round($Range);
				if($Inf_eni >0 and $Pass <($Inf_eni/2))
				{
					$intro.="<p>".$Inf_eni." navires ennemis forment un écran vous empêchant d'atteindre les navires de seconde ligne de l'ennemi</p>";
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment_IA as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.mobile=5 AND c.Type IN (15,16,17,18,19,20) AND r.Pays<>'$country' AND r.Visible=1";
					/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.mobile=5 AND c.Type IN (15,16,17,18,19,20) AND r.Pays<>'$country' AND r.Visible=1) UNION (*/
				}
				else
				{
					if($Pass >$Vehicule_Nbr)$Pass=$Vehicule_Nbr;
					if($Inf_eni >0)AddEventGround(430,$Veh,$OfficierID,$Reg,$Cible,$Pass,$Inf_eni);
					if($Pass >1)
						$intro.="<div class='alert alert-warning'>".$Pass." ".$Nom." parviennent à profiter d'une brèche dans la ligne tenue par l'ennemi à la vitesse de ".$Vitesse."km/h!<br>La visibilité est d'environ ".$Range."m</div>";
					else
						$intro.="<div class='alert alert-warning'>1 ".$Nom." parvient à profiter d'une brèche dans la ligne tenue par l'ennemi!<br>La visibilité est d'environ ".$Range."m</div>";
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.HP,c.HP as HP_Max FROM Regiment_IA as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND r.Position NOT IN (25,26) AND (c.mobile=5 OR r.Transit_Veh=5000)
					AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Visible=1";
					/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.HP,c.HP as HP_Max FROM Regiment as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND r.Position NOT IN (25,26) AND c.mobile=5
					AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Officier_ID<>'$OfficierID' AND r.Pays<>'$country' AND r.Visible=1) UNION (*/
				}
				if($OfficierID >3)UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
				if($DB=='Regiment')
					$CT=99;
				else
					$CT=98;
			}
			elseif($Mode ==4)
			{
				$intro.="<p>".$Inf_eni." Cie d'infanterie ennemies désorganisées tentent de fuir devant vous</p>";
				$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment_IA as r,Cible as c 
				WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Categorie=5 AND r.Position IN(6,7,8,9) AND r.Pays<>'$country' AND r.Visible=1";
				if($OfficierID >3)UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
				if($DB=='Regiment')
					$CT=99;
				else
					$CT=98;
			}
			else
			{
				$Detect_infiltres=false;
				$Taihaut=true;
				$Pass=$Vehicule_Nbr;
				$con=dbconnecti();
				$result_inf=mysqli_query($con,"SELECT r.Experience,r.Moral,r.ID,r.Officier_ID,r.Vehicule_Nbr,c.Detection,c.Categorie FROM Regiment_IA as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Moral >50
				AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Categorie IN (5,6,9) AND r.Placement='$Placement' AND r.Position=10");
				/*(SELECT r.Experience,r.Moral,r.ID,r.Officier_ID,r.Vehicule_Nbr,c.Detection,c.Categorie FROM Regiment as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Moral >50
				AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Categorie IN (5,6,9) AND r.Placement='$Placement' AND r.Position=10) UNION (*/
				mysqli_close($con);
				if($result_inf)
				{
					$Inf_eni=mysqli_num_rows($result_inf);
					while($datai=mysqli_fetch_array($result_inf,MYSQLI_NUM))
					{
						$Moral_eni=$datai[1]+1;
						$Inf_couv=$datai[2];
						$Infoff_couv=$datai[3];
						$regs_ligne[]=$Inf_couv;
						/*if($Infoff_couv >0)
						{
							$con=dbconnecti();
							$update_inf=mysqli_query($con,"UPDATE Regiment SET Moral='$Moral_eni',Experience=Experience+1 WHERE ID='$Inf_couv'");
							$update_off=mysqli_query($con,"UPDATE Officier SET Avancement=Avancement+1,Reputation=Reputation+1 WHERE ID='$Infoff_couv'");
							mysqli_close($con);
						}*/
						$Exp_eni=ceil($datai[0]/100*$Moral_eni);
						$Def_line=$Exp_eni+($Inf_eni*100);
						$Atk_line=mt_rand(0,$Reg_xp)+$Vitesse;
						if($Def_line >=$Atk_line)
						{
							if($mobile !=3 and $datai[6] ==9) //AT
								$Pass=$datai[4];
							elseif($datai[4] >=250)
								$Pass-=11;
							elseif($datai[4] >=225)
								$Pass-=10;
							elseif($datai[4] >=200)
								$Pass-=9;
							elseif($datai[4] >=175)
								$Pass-=8;
							elseif($datai[4] >=150)
								$Pass-=7;
							elseif($datai[4] >=125)
								$Pass-=6;
							elseif($datai[4] >=100)
								$Pass-=5;
							elseif($datai[4] >=75)
								$Pass-=4;
							elseif($datai[4] >=50)
								$Pass-=3;
							elseif($datai[4] >=25)
								$Pass-=2;
							else
								$Pass-=1;
							if($datai[5] >10)
								$Detect_infiltres=true;
						}
						if($Admin)
							$intro.="<br>".$Inf_couv."e Cie => Atk=".$Atk_line." <> ".$Def_line."=Def (".$Exp_eni." XP / ".$Moral_eni." Moral)";
					}
					mysqli_free_result($result_inf);
				}
				if(is_array($regs_ligne))
				{
					if(array_count_values($regs_ligne) >0)
					{
						$regs_ligne_list=implode(",",$regs_ligne);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Regiment_IA SET Experience=Experience+5 WHERE ID IN(".$regs_ligne_list.")");
						mysqli_close($con);
						$Taihaut=false;
					}
				}
                if($Bomb !=3 and $Blindage >0 and $mobile !=3 and $Zone !=5 and $Zone !=7 and $Zone !=9)
                {
                    if($OfficierID >0)
                    {
                        /*if(IsSkill(19,$OfficierID))
                        {
                            if(GetData("Officier","ID",$OfficierID,"Trait") ==3)
                            {
                                $Range*=1.5;
                                if($Pass >0)
                                    $Pass*=2;
                                else
                                    $Pass+=2;
                            }
                            else
                            {
                                $Range*=1.25;
                                if($Pass >0)
                                    $Pass*=1.5;
                                else
                                    $Pass+=1;
                            }
                            $intro.="<br>Vos troupes bénéficient de votre compétence <b>Fer de Lance</b> !";
                        }*/
                    }
                    elseif($Skill ==19 or $Skill ==62 or $Skill ==102 or $Skill ==103)
                    {
                        if($Skill ==19){
                            $Range*=1.1;
                            $Pass*=1.15;
                        }
                        elseif($Skill ==62){
                            $Range*=1.15;
                            $Pass*=1.3;
                        }
                        elseif($Skill ==102){
                            $Range*=1.20;
                            $Pass*=1.45;
                        }
                        elseif($Skill ==103){
                            $Range*=1.25;
                            $Pass*=1.6;
                        }
                        $intro.="<br>Vos troupes bénéficient de la compétence <b>Fer de Lance</b> !";
                    }
                }
				if(!$Detect_infiltres and $mobile ==3 and !$Visible and ($Zone ==2 or $Zone ==3 or $Zone ==4 or $Zone ==5 or $Zone ==7 or $Zone ==10)) //Bonus infanterie en terrain difficile
				{
					$Range*=2; //+3000
					$intro.="<br>Votre infanterie parvient à s'approcher de l'ennemi et à le surprendre par ses capacités furtives!";
				}
				if(!$Detect_infiltres and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7) and $Taihaut and ($Zone ==0 or $Zone ==8)) //Bonus véhicules en terrain plat
				{
					$Range*=2;
					$intro.="<br>Vos troupes mobiles se ruent vers l'ennemi afin d'éviter qu'il ne prenne la fuite!";
				}
				if($Inf_eni >0 and $Pass <$Inf_eni)
				{
					$intro.="<p>".$Inf_eni." Cie d'infanterie ennemies forment un front continu vous empêchant d'atteindre les lignes arrières de l'ennemi</p>";
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment_IA as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement'
					AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Categorie IN (5,6,9) AND r.Pays<>'$country' AND r.Visible=1";
					/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement'
					AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Categorie IN (5,6,9) AND r.Pays<>'$country' AND r.Visible=1 AND r.Arti_IA=0) UNION (*/
					if($Pass<0)$Pass=0;
				}
				else
				{
					if($Bomb !=3 and $Zone !=5 and $Zone !=7 and $Zone !=9 and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7))
					{
						if($OfficierID >0){
							/*if(IsSkill(18,$OfficierID))
							{
								$Range*=2;
								$intro.="<br>Vos troupes bénéficient de votre compétence <b>Exploitation</b> !";
							}*/
						}
						elseif($Skill ==18 or $Skill ==61 or $Skill ==100 or $Skill ==101){
							if($Skill ==18)
								$Range*=1.25;
							elseif($Skill ==61)
								$Range*=1.5;
							elseif($Skill ==100)
								$Range*=1.75;
							elseif($Skill ==101)
								$Range*=2;
							$intro.='<br>Vos troupes bénéficient de la compétence <b>Exploitation</b> !';
						}
					}
					if($Pass >=$Vehicule_Nbr)$Pass=$Vehicule_Nbr;
					if($Inf_eni >0)
						AddEventGround(430,$Veh,$OfficierID,$Reg,$Cible,$Pass,$Inf_eni);
					if($Pass >1)
						$intro.="<p>".$Pass." ".$Nom." parviennent à profiter d'une brèche dans le front tenu par l'ennemi! La visibilité est d'environ ".$Range."m</p>";
					else
						$intro.="<p>1 ".$Nom." parvient à profiter d'une brèche dans la ligne tenue par l'ennemi!</p>";
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,r.Placement,r.HP,c.Type,c.Portee,c.Vitesse,c.mobile,c.Sol_meuble,c.HP AS HP_Max FROM Regiment_IA as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND c.mobile NOT IN (4,5)
					AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Visible=1";
					/*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement FROM Regiment as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND c.mobile NOT IN (4,5)
					AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Officier_ID<>'$OfficierID' AND r.Pays<>'$country' AND r.Visible=1) UNION (*/
				}
				//if($OfficierID >3)UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
				if($DB=='Regiment')
					$CT=99;
				else
					$CT=98;
			}
			//Scan Pos
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					if($data['Position'] ==8)
						$Pos_icon="<img src='images/mortar.png' title='Sous le feu'>";
					else
						$Pos_icon='';
					if($data['Portee'] <500)
						$Range_fix='100';
					elseif($data['Portee'] >5000)
						$Range_fix='5500';
					else{
						$data['Portee']=round($data['Portee']/500)*500;
						$Range_fix=$data['Portee'];
					}
					if($data['Placement'] !=$Placement){
						$Pos_icon.="<img src='images/strat0.png' title='Zone adjacente'>";
						$Range_fix='5500';
					}
					if($data['Position'] ==11){
						$data['Vehicule_ID']=5000;
						$data['Vehicule_Nbr']=ceil($data['Vehicule_Nbr']/10);
					}
					if($Bomb ==2 and $meteo >-75 and $data['HP'] <($data['HP_Max']/2))
						$Trainard=true;
					else
						$Trainard=false;
                    if($data['mobile'] ==5)$hp_good=round(($data['HP']/$data['HP_Max'])*100);
                    $Vitesse_unit=Get_LandSpeed($data['Vitesse'],$data['Placement'],$Zone,$data['Position'],$data['Type'],$hp_good,$data['Sol_meuble']);
                    if($Matos ==10)$Vitesse_unit*=1.1;
                    elseif($Matos ==14)$Vitesse_unit*=1.5;
                    elseif($Matos ==30)$Vitesse_unit/=1.25;
                    if($Bomb !=1 and $Vitesse_unit <=2 and !$Inf_eni){
                        $Immobile=true;
                    }
					$choix='choix_'.$Range_fix;
					if($data['Vehicule_ID'] !=4000 and ($data['Portee'] <=$Range or $Trainard or $Immobile))
						$$choix.="<Input type='Radio' name='Action' value='".$data['ID']."_".$data['Officier_ID']."'>".$data['Vehicule_Nbr']."<img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'> <img src='".$data['Pays']."20.gif'> ".$Pos_icon."<br>";
					else
						$$choix.="<Input type='Radio' name='Action' value='".$data['ID']."_".$data['Officier_ID']."' disabled title='Hors de portée'>".$data['Vehicule_Nbr']."<img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'> <img src='".$data['Pays']."20.gif'> ".$Pos_icon."<br>";
				}
				mysqli_free_result($result);
			}
			if($Bomb ==2 or $Bomb ==12){
				$titre='Torpillage';
				$img=Afficher_Image('images/torpillage.jpg',"images/image.png","");
			}
			elseif($Zone ==6 or $Placement ==8){
				$titre='Combat Naval';
				$img=Afficher_Image('images/nav_tirer.jpg',"images/image.png","");
			}
			else{
				$titre='Combat Terrestre';
				$img=Afficher_Image('images/assault.jpg',"images/image.png","");
			}
			if($Bomb ==1)
				$Aide="<b>Ciblage :</b> Vous ne pouvez attaquer qu'une cible repérée (via une reco terrestre, navale ou aérienne) située à portée.<br><b>Portée d'attaque :</b> Plus votre unité aura une portée de tir importante, plus elle pourra attaquer une cible éloignée.";
			else
				$Aide="<b>Ciblage :</b> Vous ne pouvez attaquer qu'une cible repérée (via une reco terrestre, navale ou aérienne) située à portée.<br><b>Portée d'attaque :</b> Plus votre unité a une allonge de raid importante (vitesse modifiée en fonction du terrain et du système de propulsion), plus elle pourra attaquer une cible éloignée.";
            $intro.='</div>';
            if($choix){
                $card_pldef='';
                for($i=500;$i<=5000;$i+=500){
                    $choix_d='choix_'.$i;
                    if($$choix_d){
                        $card_pldef.="<div class='col-xs-6 col-sm-4 col-md-2 col-lg-1'>
                            <div class='panel panel-war'>
                                <div class='panel-heading'>".$i."m</div>
                                <div class='panel-body'>".$$choix_d."</div>                                    
                            </div>
                        </div>";
                    }
                }
                $hover=640;
                $mes="<form action='index.php?view=".$dive."' method='post'>
                    <input type='hidden' name='CT' value='".$CT."'>
                    <input type='hidden' name='Veh' value='".$Veh."'>
                    <input type='hidden' name='Reg' value='".$Reg."'>
                    <input type='hidden' name='Pass' value='".$Pass."'>
                    <input type='hidden' name='Line' value='".$Inf_eni."'>
                    <input type='hidden' name='Max_Range' value='".$Max_Range."'>
                    <input type='hidden' name='Mode' value='".$Bomb."'>
                    <h2>Cibles repérées ".GetPlace($Placement)."</h2>
                    <div class='row'>
                        <div class='col-xs-6 col-sm-4 col-md-2 col-lg-1'>
                            <div class='panel panel-war'>
                                <div class='panel-heading'>Ligne de front</div>
                                <div class='panel-body'>".$choix_100."</div>                                    
                            </div>
                        </div>".$card_pldef."
                        <div class='col-xs-6 col-sm-4 col-md-2 col-lg-2'>
                            <div class='panel panel-war'>
                                <div class='panel-heading'>+5000m</div>
                                <div class='panel-body'>".$choix_5500."</div>                                    
                            </div>
                        </div>
                    </div>
                    <Input type='Radio' name='Action' value='0' checked>- Annuler l'attaque.<br>
                    ".$Distance_tir.$Repli.$Armement."
                    <input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
                    <div class='alert alert-info'>".$Aide."</div>";
            }
            else{
                $mes='<h2>Aucune cible n\'a été repérée '.GetPlace($Placement).'</h2><a href="index.php?view=ground_em_ia_list"><span class="btn btn-danger">ANNULER</span></a>';
            }
            $mes.="<div class='alert alert-info'>".$Aide."</div>";
			include_once('./default.php');
		}
		else
			echo "<h6>Ne disposant plus d'aucune troupe, vous n'avez d'autre choix que de rejoindre vos positions de départ!</h6>";
	}
	else
		echo '<h6>Crédits Temps insuffisants!</h6>';
}