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
	$debug=0;
	$Reg=Insec($_POST['Reg']);
	$Battle=Insec($_POST['Battle']);
	$Faction=Insec($_POST['Camp']);
	$mes="";
	$alerte_reco=false;
	$Reg_exp=50;
	$Cible=GetCiblePVP($Battle);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,Zone,Meteo,Camouflage,DefenseAA_temp,TypeIndus,Industrie,BaseAerienne,QualitePiste,Pont_Ori,Pont,Radar_Ori,Radar,Port_Ori,Port,NoeudF_Ori,NoeudF,Garnison,Fortification,Recce,Flag FROM Lieu WHERE ID='$Cible'") or die('Le jeu a rencontr� une erreur, merci de le signaler sur le forum avec la r�f�rence suivante : reco_pvp-lieu');
	$result2=mysqli_query($con,"SELECT r.Vehicule_ID,r.Vehicule_Nbr,r.Placement,c.Detection FROM Regiment_PVP as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.ID='$Reg'") or die('Le jeu a rencontr� une erreur, merci de le signaler sur le forum avec la r�f�rence suivante : reco_pvp-reg');
	mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Veh=$data['Vehicule_ID'];
			$Vehicule_Nbr=$data['Vehicule_Nbr'];
			$Placement=$data['Placement'];
			$Det=$data['Detection'];
		}
		mysqli_free_result($result2);
		unset($data);
	}
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Cible_nom=$data['Nom'];
			$Cible_cam=$data['Camouflage'];
			$Zone=$data['Zone'];
			$Cible_DefenseAA=$data['DefenseAA_temp'];
			$TypeIndus=$data['TypeIndus'];
			$Cible_indus=$data['Industrie'];
			$Cible_base=$data['BaseAerienne'];
			$Cible_piste=$data['QualitePiste'];
			$Pont_Ori=$data['Pont_Ori'];
			$Pont=$data['Pont'];
			$Port_Ori=$data['Port_Ori'];
			$Port=$data['Port'];
			$Radar_Ori=$data['Radar_Ori'];
			$Radar=$data['Radar'];
			$NoeudF_Ori=$data['NoeudF_Ori'];
			$NoeudF=$data['NoeudF'];
			$Garnison=$data['Garnison'];
			$Fortification=$data['Fortification'];
			$Recce=$data['Recce'];
			$Flag=$data['Flag'];
			$Meteo=$data['Meteo'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	$con=dbconnecti();
	$result1=mysqli_query($con,"SELECT HP,Nom,Blindage_f,Vitesse,Taille,mobile,Reput,Detection,Carbu_ID,Type,Arme_Inf,Optics FROM Cible WHERE ID='$Veh'") or die('Le jeu a rencontr� une erreur, merci de le signaler sur le forum avec la r�f�rence suivante : reco_pvp-veh');
	mysqli_close($con);
	if($result1)
	{
		while($data=mysqli_fetch_array($result1,MYSQLI_ASSOC))
		{
			$HP=$data['HP'];
			$HP_ori=$HP;
			$Veh_Nom=$data['Nom'];
			$Blindage=$data['Blindage_f'];
			$Vitesse=$data['Vitesse'];
			$Taille=$data['Taille'];
			$mobile=$data['mobile'];
			$Reput=$data['Reput'];
			$Det=$data['Detection'];
			$Veh_Carbu=$data['Carbu_ID'];
			$Type=$data['Type'];
			$Arme_Inf_reco=$data['Arme_Inf'];
			$Optics=$data['Optics'];				
			$Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type);		
			if(!$Blindage)$Blindage=Get_Blindage($Zone,$Taille,0,2);
		}
		mysqli_free_result($result1);
		unset($data);
	}
	$Detect_base=floor(($Reg_exp/10)+10);
	/*if($Position !=25)
	{
		$Update_XP_eni=0;
		$con=dbconnecti();
		$pj_unit=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Muns,c.Arme_Art,c.Arme_AT,c.Arme_Inf,c.Portee,c.Type,c.mobile FROM Regiment_PVP as r,Cible as c,Pays as p 
		WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3) AND c.Portee >499 AND c.Charge=0");
		mysqli_close($con);
		if($pj_unit)
		{
			while($data=mysqli_fetch_array($pj_unit))
			{
				$Update_XP_eni=0;
				$EXP=$data['Experience'];
				$chance_tir=mt_rand(0,200);
				if($chance_tir <=$EXP)
				{
					$Reg_eni=$data['ID'];
					$Arme_Art=$data['Arme_Art'];
					$Arme_AT=$data['Arme_AT'];
					$Vehicule_ID_r=$data['Vehicule_ID'];
					$Vehicule_Nbr_r=$data['Vehicule_Nbr'];
					$Position_r=$data['Position'];
					$Placement_r=$data['Placement'];
					$Portee_r=$data['Portee'];
					if($Blindage >0 and $Arme_AT)
						$Arme=$Arme_AT;
					elseif($Arme_Art)
						$Arme=$Arme_Art;
					else
						$Arme=$data['Arme_Inf'];
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
					$Reg_eni=0;
					$mes.="<br>L'ennemi vous a tendu une embuscade !";
					$Tir=mt_rand(0,$EXP);
					if($data['Position'] ==3)$Tir=$EXP;
					$Shoot_emb=$Tir+$Meteo+$Taille-$Vitesse-mt_rand(0,$Reg_exp)+$data['Vehicule_Nbr'];
					if($Shoot_emb >1 or $Tir ==$EXP)
					{
						$Degats=(mt_rand($Arme_Cal,$Arme_Dg)-$Blindage)*GetShoot($Shoot_emb,$Arme_Multi);
						$Degats=round(Get_Dmg($data['Muns'],$Arme_Cal,$Blindage,$Portee_r,$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
						if($Degats <1)$Degats=mt_rand(1,10);
						if($Embuscade)$Degats*=2;
						$HP-=$Degats;
						if($HP <1)
						{
							$mes.='<br>Le tir ennemi ('.$Reg_eni.'e Cie) d�truit une de vos unit�s. ('.$Degats.' points de d�gats!)';
							$Vehicule_Nbr-=1;
							if($Vehicule_Nbr <1)
								break;
							else
								$HP=$HP_ori;
						}
						else
							$mes.='<br>Le tir ennemi ('.$Reg_eni.'e Cie) endommage une de vos unit�s, lui occasionnant <b>'.$Degats.'</b> points de d�gats!';
					}
					else
						$mes.='<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
				}
			}
			mysqli_free_result($pj_unit);
			unset($data);
		}
	}*/
	//Detection
	if($Vehicule_Nbr >0)
	{
		$Bonus_det=0;
		if($Trait_o ==10)$Bonus_det=10;
		$Detect=mt_rand(0,$Detect_base);
		if($Det >10)$Detect*=3;
		if($Flag ==$country)$Detect+=10;
		$Shoot=$Detect+$Det+$Bonus_det;
		if($Shoot >0)
		{
			//Camouflage zone
			switch($Zone)
			{
				case 2: case 3: case 5: case 7: case 10:
					$Cam_zone=30;
				break;
				case 4:
					$Cam_zone=20;
				break;
				case 1: case 11:
					$Cam_zone=10;
				break;
				case 9:
					$Cam_zone=100;
				break;
				default:
					$Cam_zone=0;
				break;
			}			
			$aa_type="aucune information sur la DCA";
			$Shoot_infra=$Shoot-($Cible_cam/10);
			if($Shoot >0)
			{
				/*if($Garnison >0)
					$Garnison_txt="<br>une garnison <a href='help/aide_garnison.php' target='_blank' title='Aide'><img src='images/help.png'></a> ".$Fort_txt." occupant la caserne";*/
				if($Radar_Ori >0)
					$Cible_radar_txt="<br>un radar";
				else
					$Cible_radar_txt="";
				if($NoeudF_Ori >0)
					$Cible_gare_txt="<br>une gare";
				else
					$Cible_gare_txt="";
				if($Pont_Ori >0)
					$Cible_pont_txt="<br>un pont";
				else
					$Cible_pont_txt="";
				if($Port_Ori >0)
					$Cible_Port_txt="<br>un Port";
				else
					$Cible_Port_txt="";
				if($TypeIndus !='')
					$Cible_ind_txt="<br>une zone industrielle";
				else
					$Cible_ind_txt="";
				if($Cible_base >0)
					$Cible_base_txt="<br>un a�rodrome";
				else
					$Cible_base_txt="";
			}
			else
				$Insuffisant="<br>Vous ne distinguez pas suffisamment les infrastructures importantes pour que cela soit utile � une attaque.";
			$mes.='<p>Vos troupes de reconnaissance ont rep�r� '.$aa_type.$Cible_ind_txt.$Cible_radar_txt.$Cible_pont_txt.$Cible_gare_txt.$Cible_port_txt.$Cible_base_txt.$Garnison_txt.' aux alentours de <b>'.$Cible_nom.'</b>'.$Insuffisant.'</p>';
		}
		//Scan Pos
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT DISTINCT r.ID,r.Pays,r.Vehicule_ID,r.Vehicule_Nbr,r.Officier_ID,r.Camouflage,r.Placement,r.Experience,r.Position,r.Visible FROM Regiment_PVP as r,Pays as p
		WHERE r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND r.Pays=p.ID AND p.Faction<>'$Faction'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$con=dbconnecti();
				$resulta=mysqli_query($con,"SELECT Taille,Blindage_f,HP,Portee,Detection FROM Cible WHERE ID='".$data['Vehicule_ID']."'");
				mysqli_close($con);
				if($result4)
				{
					while($data4=mysqli_fetch_array($result4,MYSQLI_ASSOC))
					{
						$Taille_eni=$data4['Taille'];
						$Blindage_eni=$data4['Blindage_f'];
						$HP_eni=$data4['HP'];
						$Portee_eni=$data4['Portee'];
						$Detection_eni=$data4['Detection'];
					}
					mysqli_free_result($result4);
				}
				if($data['Position'] ==11)$data['Vehicule_ID']=5000;
				if($data['Position'] ==1 or $data['Position'] ==3)
					$Tactique_eni=$data['Experience']/10;
				elseif($data['Position'] ==2 or $data['Position'] ==10)
					$Tactique_eni=($data['Experience']/10)*2;
				elseif($data['Position'] ==0 or $data['Position'] >3)
					$Tactique_eni=$data['Experience']/20;
				if(!$data['Camouflage'])$data['Camouflage']=1;
				if($Trait_eni ==5)$data['Camouflage']*=2;			
				$Cam_eni=$Taille_eni/$data['Camouflage'];
				if($Cam_eni <1)$Cam_eni=1;
				$Defense_reco=$Cam_zone-$Meteo+mt_rand(0,$Tactique_eni*$data['Camouflage'])-$Cam_eni;
				if($debug)$mes.="<br>[DEBUG] Reco = ".$Shoot."/".$Defense_reco." (Terrain = ".$Cam_zone.", M�t�o = ".$Meteo.", Taille = ".$Cam_eni.", Cam = ".$data['Camouflage'].", Tac (max) = ".$Tactique_eni;
				if($Shoot >$Defense_reco)
				{
					//D�tect�
					if(!$data['Visible'])
					{
						SetData("Regiment_PVP","Visible",1,"ID",$data['ID']);
						$intro.="<br>Une unit� <img src='".$data['Pays']."20.gif'>, ".GetPosGr($data['Position']).", a �t� d�tect�e ".GetPlace($data['Placement'])." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'>";
					}
				}
			}
			mysqli_free_result($result);
			unset($data);
		}
		$skills.="</div>";
		if(!$intro)
			$intro="<p>Vos troupes de reconnaissance n'ont d�tect� aucune autre unit� dans les environs</p>";
		if($Zone ==6)
			$img=Afficher_Image('images/bino_sea.jpg',"images/image.png","Reco",50);
		else
			$img=Afficher_Image('images/bino.jpg',"images/image.png","Reco",50);
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Visible=1,Position=4,Moves=Moves+1 WHERE ID='$Reg'");
		mysqli_close($con);
	}
	else
		$mes.="<p>La mission est interrompue, vos troupes ont �t� d�cim�es!</p>";
	$titre="Reconnaissance";
	$menu="<a href='index.php?view=ground_menu_pvp' class='btn btn-default' title='Retour'>Retour au menu</a>";
	include_once('./default.php');
}
?>