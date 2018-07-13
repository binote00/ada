<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$Action=Insec($_POST['Action']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$ArmeAvion=Insec($_POST['ArmeAvion']);
$ArmeAvion_nbr=Insec($_POST['ArmeAvion_nbr']);
$Mun=Insec($_POST['Mun']);
$Pays_eni=Insec($_POST['Pays_eni']);
$Deleguer=Insec($_POST['Deleguer']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $_SESSION['photographier'] == false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	$_SESSION['cibler']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['photographier']=true;
	$country=$_SESSION['country'];
	$Distance=$_SESSION['Distance'];	
	$retour=false;
	$end_mission=false;
	$attaque=false;
	$seconde_passe=false;
	$recce_ok=false;
	$add_convoi=false;
	$add_ground=false;
	$loose=false;	
	$Target=false;
	if($Pays_eni ==10)$Pays_eni=2;	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Equipage,Pilotage,Vue,Courage,Moral,Front,S_Avion_db,S_Cible,S_Mission,S_Cible_Atk,S_Longitude,S_Latitude,S_Essence,Simu,
	S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Equipage_Nbr,S_Nuit,Slot5,Slot10,Slot11,Admin FROM Pilote WHERE ID='$PlayerID'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : photo-player');
	$resultac=mysqli_query($con,"SELECT Officier FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
	$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
	//mysqli_close($con);
	if($resultac)
	{
		while($dataac=mysqli_fetch_array($resultac,MYSQLI_ASSOC))
		{
			$Officier=$dataac['Officier'];
		}
		mysqli_free_result($resultac);
	}
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
			$Pilotage=$data['Pilotage'];
			$Vue=$data['Vue'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Front=$data['Front'];
			$Avion_db=$data['S_Avion_db'];
			$Cible=$data['S_Cible'];
			$Nuit=$data['S_Nuit'];
			$Mission_Type=$data['S_Mission'];
			$Cible_Atk=$data['S_Cible_Atk'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Bombs=$data['S_Avion_Bombe_Nbr'];
			$Slot5=$data['Slot5'];
			$Slot10=$data['Slot10'];
			$Slot11=$data['Slot11'];
			$Simu=$data['Simu'];
			$Admin=$data['Admin'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($Pilotage >50)$Pilotage=50;
	if($Vue >50)$Vue=50;
	$Steady=1;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(35,$Skills_Pil))
			$As_Tele=50;
		if(in_array(39,$Skills_Pil))
			$Steady=1.1;
		if(in_array(41,$Skills_Pil))
			$AsZigZag=50;
		if(in_array(50,$Skills_Pil))
			$Bonne_Etoile=true;
	}
	if($Equipage)$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");		
	if($Slot11 ==69)
	{
		$Moral+=50;
		$Courage+=50;
	}	
	//Meteo nécessaire ici
	$result=mysqli_query($con,"SELECT Nom,BaseAerienne,Meteo,Zone,ValeurStrat,Camouflage,Flag,Recce,Recce_PlayerID,Recce_PlayerID_TAL,Recce_PlayerID_TAX FROM Lieu WHERE ID='$Cible'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : photo-cible');
	$result2=mysqli_query($con,"SELECT Type,Robustesse,Masse,ArmeSecondaire FROM $Avion_db WHERE ID='$avion'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : photo-avion');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Ville_Nom=$data['Nom'];
			$Flag=$data['Flag'];
			$BaseAerienne=$data['BaseAerienne'];
			$meteo=$data['Meteo'];
			$Zone=$data['Zone'];
			$ValStrat=$data['ValeurStrat'];
			$cam_terrain=$data['Camouflage'];
			$Recce_Base=$data['Recce'];
			$Recce_PlayerID=$data['Recce_PlayerID'];
			$Recce_PlayerID_TAL=$data['Recce_PlayerID_TAL'];
			$Recce_PlayerID_TAX=$data['Recce_PlayerID_TAX'];
		}
		mysqli_free_result($result);
		unset($result);
	}
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Type_avion=$data['Type'];
			$Masse=$data['Masse'];
			$HPmax=$data['Robustesse'];
			$ArmeSecondaire=$data['ArmeSecondaire'];
		}
		mysqli_free_result($result2);
		unset($data);
	}
	$Malus_Reperer=GetMalusReperer($Zone,$cam_terrain);
	$avion_img=GetAvionImg($Avion_db,$avion);
	if($HP)
	{
		$moda=$HPmax/$HP;
		if($Avion_db =="Avion" and $Bombs and $Avion_Bombe)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Bombs));
			$moda*=(1+$charge_sup);
		}
		$Speed=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,0,$PlayerID,$Unite);
		$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,0,$Pilotage);
		$choix_pvp="<Input type='Radio' name='Action' value='99' checked>- Rentrer à la base.<br>";
		$Eni_PvP=GetData("Duels_Candidats","Target",$PlayerID,"ID");
		$Lieu_PvP=GetData("Duels_Candidats","Target",$PlayerID,"Lieu");		
		if($Zone ==6 or $Mission_Type !=15)
		{
			if(mt_rand(0,100) < 10 and !$ValStrat)
			{
				if($meteo >-20)
					$intro="Inutile de chercher, il n'y a vraiment aucun ennemi dans les environs...";
				else
					$intro="Vous avez beau chercher, aucun ennemi ne se trouve dans les environs...";
				$Action=5;
			}
		}
	}
	else
		$Action=98;		
	if($Officier >0)
		$Lieu_Reg_Off=GetData("Regiment","Officier_ID",$Officier,"Lieu_ID");
	if($Cible ==$Lieu_Reg_Off)
	{
		$intro.="<br>Votre pilote ne peut pas effectuer de mission sur le lieu où se trouve votre officier!";
		$end_mission=true;
	}
	elseif($Action ==98)
	{
		$intro.="<br>La vitesse de votre avion est insuffisante pour vous maintenir en vol.";
		$end_mission=true;
	}
	elseif($Action ==5)
	{
		$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
		$retour=true;
	}
	elseif($Eni_PvP and $Lieu_PvP == $Cible)
	{
		$intro.="<p>Un ennemi vous prend en chasse, vous empêchant d'accomplir votre mission!</p>";
		$img=Afficher_Image("images/facetoface.jpg",'images/avions/vol'.$avion_img.'.jpg',$nom_avion);
		$chemin=0;
		$_SESSION['done']=false;
		$_SESSION['PVP']=true;
		if(!GetData("Duels_Candidats","PlayerID",$PlayerID,"ID"))
			AddCandidat($Avion_db,$PlayerID,$avion,$HP,$Puissance,$essence,$chemin,$Distance,$Mun1,$Mun2,$alt,$Cible,$Nuit);
		$choix_pvp="<Input type='Radio' name='Action' value='90' checked>- Affronter votre adversaire.<br>";
		$seconde_passe=true;
	}
	else
	{
		$Port_Base=GetData("Lieu","ID",$Cible,"Port_Ori");
		//Camera
		if($Avion_Bombe !=25 and $Avion_Bombe !=26 and $Avion_Bombe !=27)
			$Camera=$ArmeSecondaire;
		else
		{
			$Camera=$Avion_Bombe;
			$Mun2=24;
		}
		$Stab=GetStab($Avion_db,$avion,$HP)*$Steady;
		if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
			$Vue_Equipage=GetData("Equipage","ID",$Equipage,"Vue");
		else
			$Vue_Equipage=0;
		//Bonus Camera
		if($Camera ==25 or $Camera ==26 or $Camera ==27)
		{
			if($Mun2 >0)
			{
				$Mun2-=1;
				if($alt<=GetData("Armes","ID",$Camera,"Portee"))
					$Bonus_Camera=GetData("Armes","ID",$Camera,"Enrayage");
				else
					$Bonus_Camera=0;
			}
		}
		if($Mission_Type ==5)
		{
			$alerte_reco=false;
			$Malus_Reperer_reg=GetMalusReperer($Zone,0);
			//Unité joueur sur place
			$con=dbconnecti();
			$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
			/*$pj_unit=mysqli_query($con,"(SELECT r.ID,r.Pays,r.Officier_ID,r.Camouflage,r.Position,r.Visible,r.Skill,c.Type,c.Taille,c.Detection,c.ID as navire FROM Regiment as r,Cible as c,Pays as p 
			WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position<>25 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction')
			UNION (SELECT r.ID,r.Pays,r.Officier_ID,r.Camouflage,r.Position,r.Visible,r.Skill,c.Type,c.Taille,c.Detection,c.ID as navire FROM Regiment_IA as r,Cible as c,Pays as p 
			WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position<>25 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction')") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : reco-reg');*/
			$pj_unit=mysqli_query($con,"SELECT r.ID,r.Pays,r.Officier_ID,r.Camouflage,r.Position,r.Visible,r.Skill,r.Matos,c.Type,c.Taille,c.Detection,c.ID as navire FROM Regiment_IA as r,Cible as c,Pays as p 
			WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position<>25 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : reco-reg');
			mysqli_close($con);
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
						$Pays_eni=$data['Pays'];
						$Taille=$data['Taille']/$data['Camouflage'];
						$Shoot=mt_rand(0,$Vue)+mt_rand(0,$Vue_Equipage)+($Stab/10)+($Moral/10)-($Malus_Reperer_reg*$data['Camouflage'])+$Taille+($meteo*2)-($alt/10);
						$Photo_shoot=mt_rand(0,50)+$Bonus_Camera+($Stab/10)+($meteo*2)-($alt/100)-($Malus_Reperer_reg*$data['Camouflage'])+$As_Tele;
						if($data['Type'] ==4 or $data['Type'] ==9 or $data['Type'] ==11 or $data['Type'] ==12)
						{
							if($data['Skill']==81 and mt_rand(0,100)<25)
							{
								$Shoot=0;
								$Photo_shoot=0;
							}
							elseif($data['Skill']==80 and mt_rand(0,100)<20)
							{
								$Shoot=0;
								$Photo_shoot=0;
							}
							elseif($data['Skill']==51 and mt_rand(0,100)<15)
							{
								$Shoot=0;
								$Photo_shoot=0;
							}
							elseif($data['Skill']==6 and mt_rand(0,100)<10)
							{
								$Shoot=0;
								$Photo_shoot=0;
							}
						}
						if($Admin ==1)
						{
							$Stab_txt=$Stab/10;
							$Malus_alt=$alt/100;
							$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
								<tr><td>Camera</td><td>".$Camera."</td></tr>
								<tr><td>Pellicule</td><td>".$Mun2."</td></tr>
								<tr><td colspan='2'>Bonus : Moral /10</td></tr>
								<tr><th colspan='2'>Global</th></tr>
								<tr><td>Stabilité</td><td>".$Stab_txt."</td></tr>
								<tr><td>Meteo (x2)</td><td>".$meteo."</td></tr>
								<tr><td>Malus Terrain (-)</td><td>".$Malus_Reperer_reg."</td></tr>
								<tr><th colspan='2'>Visuel</th></tr>
								<tr><td>Vue (rand)</td><td>".$Vue."</td></tr>
								<tr><td>Vue Equipage (rand)</td><td>".$Vue_Equipage."</td></tr>
								<tr><td>Taille</td><td>".$Taille."</td></tr>
								<tr><td>Malus Altitude (x10) (-)</td><td>".$Malus_alt."</td></tr>
								<tr><th>Visuel</th><th>".$Shoot."</th></tr>
								<tr><th colspan='2'>Photo (+ rand 0,50)</th></tr>
								<tr><td>Bonus Camera</td><td>".$Bonus_Camera."</td></tr>
								<tr><td>Malus Altitude (-)</td><td>".$Malus_alt."</td></tr>
								<tr><th>Photo</th><th>".$Photo_shoot."</th></tr>
							</table>";
						}
						if($Pays_eni ==1){
						    $Shoot-=50;
                            $Photo_shoot-=50;
                        }
						/*if($Zone ==6)
						{
							$headers='MIME-Version: 1.0'."\r\n";
							$headers.='Content-type: text/html; charset=utf-8'."\r\n";
							$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Unit cible=".$data['ID']."</p>";
							$msgm.= $skills."</body></html>";
							mail('binote@hotmail.com','Aube des Aigles: DCA Bomb Log bomb.php',$msgm,$headers);
						}*/
						if($Shoot >1 or $Photo_shoot >1)
						{
							if(!$data['Visible'])
							{
								if(!$data['Officier_ID'])
									SetData("Regiment_IA","Visible",1,"ID",$data['ID']);
								else
									SetData("Regiment","Visible",1,"ID",$data['ID']);
								$nbr_units_pj++;
							}
							if(!$data['Officier_ID'] and $data['Position'] ==11)
								$data['navire']=5000;
							$icons_navires.="<br><img src='/images/vehicules/vehicule".$data['navire'].".gif'>";
							$recce_ok=true;
						}
						if($data['Officier_ID'] >0 and $alt <2000)
						{
							if($data['Detection'] >-$meteo)
								$alerte_reco[]=$data['Officier_ID'];
						}
					}
				}
				mysqli_free_result($pj_unit);
				if($Zone ==6)
				{
					$Cible_nom="navire non-identifié";
					if($nbr_units_pj >0)
					{
						$intro.="<p><b>Vous repérez au moins ".$nbr_units_pj." navire(s) ennemi(s)</b></p>";
						$Target=56;
					}
					else
					{
						$intro.="<p><b>Vous ne parvenez à identifier aucun navire ennemi</b></p>";
						$img="<img src='images/recce_sea.jpg' style='width:100%;'>";
					}
				}
				else
				{
					$Cible_nom="unité terrestre non-identifiée";
					if($nbr_units_pj >0)
					{
						$intro.="<p><b>Vous repérez au moins ".$nbr_units_pj." unités ennemies</b></p>";
						$Target=50;
					}
					else
					{
						$intro.="<p><b>Vous ne parvenez à identifier aucune unité ennemie</b></p>";
						$img="<img src='images/recce_terre.jpg' style='width:100%;'>";
					}
				}
				$choix3="<Input type='Radio' name='Action' value='3'>- Tenter un passage supplémentaire à basse altitude.<br>";
				if($alerte_reco)
				{
					include_once('./jfv_msg.inc.php');
					$off_alerte=array_unique($alerte_reco);
					$off_count=count($off_alerte);
					for($x=0;$x<$off_count-1;$x++) 
					{
						if($off_alerte[$x] >0)
							SendMsgOff($off_alerte[$x],0,"Une reconnaissance aérienne ennemie a été détectée dans les environs de ".$Ville_Nom,"Rapport de reconnaissance",0,2);
					}
					unset($alerte_reco);
					unset($off_alerte);
				}
				$base_pts=$nbr_units_pj;
			}
			else
			{			
				$img="<img src='images/lieu/objectif_atk".$Pays_eni.$Cible_Atk.".jpg' style='width:100%;'>";
				$Cible_nom="absence totale de cible";
				$intro.="<br>Vous ne détectez aucune cible digne d'intérêt";
				$cam_c=0;
				$arme_c=0;
				$base_pts=0;
				$loose=true;
				$retour=true;
			}
			$choix1="";
		}
		elseif($Zone ==6 or $Port_Base)
		{
			//Unités sur place
			$con=dbconnecti();
			$pj_unit=mysqli_query($con,"SELECT COUNT(*),ID,Camouflage,Visible,Skill FROM Regiment_IA WHERE Lieu_ID='$Cible' AND Vehicule_Nbr >0 AND Vehicule_ID >4999 AND Placement IN (4,8) AND Position<>25 AND Pays<>'$country'");
			// COUNT(*),ID,Camouflage,Visible,Skill FROM Regiment WHERE Lieu_ID='$Cible' AND Vehicule_Nbr >0 AND Vehicule_ID >4999 AND Placement IN (4,8) AND Position<>25 AND Pays<>'$country'
			mysqli_close($con);
			if($pj_unit)
			{
				$nbr_units_pj=0;				
				while($data=mysqli_fetch_array($pj_unit))
				{
					if($data[0] >0)
					{
						if($data['Skill'] ==29)$data['Camouflage']*=1.1;
						elseif($data['Skill'] ==126)$data['Camouflage']*=1.2;
						elseif($data['Skill'] ==127)$data['Camouflage']*=1.3;
						elseif($data['Skill'] ==128)$data['Camouflage']*=1.4;
						$Shoot=mt_rand(0,$Vue) + mt_rand(0,$Vue_Equipage) + ($Moral/10) - $Malus_Reperer - ($data['Camouflage']*25) + ($meteo*3) - ($alt/10);
						$Photo_shoot=mt_rand(0,50) + $Bonus_Camera + ($Stab/10) + ($meteo*2) - ($alt/100) - $Malus_Reperer+$As_Tele;
						if($Shoot >1 or $Photo_shoot >1)
						{
							if(!$data['Visible'])
								SetData("Regiment","Visible",1,"ID",$data['ID']);
							$nbr_units_pj++;
						}
					}
				}
				mysqli_free_result($pj_unit);
				$img="<img src='images/recce_mer.jpg' style='width:100%;'>";
				$choix1="<Input type='Radio' name='Action' value='1'>- Tenter un passage supplémentaire à haute altitude.<br>";
				if($nbr_units_pj >1)
				{
					$intro.="<p><b>Vous repérez au moins ".$nbr_units_pj." navires ennemis</b></p>";
					$Cible_nom="Flotte";
					$Target=6;
				}
				elseif($Zone ==6)
				{
					$intro.="<p><b>Vous ne parvenez à identifier aucun navire ennemi</b></p>";
					$Cible_nom="étendue d'eau à perte de vue...";
					$seconde_passe=false;
					$recce_ok=false;
					$loose=true;
					$retour=true;
				}
				else
				{
					$intro.="<p><b>Vous ne parvenez à identifier aucun navire ennemi</b></p>";
					$Cible_nom="un port";
					$Target=16;
				}
				$base_pts=$nbr_units_pj;
			}
			else
			{
				$choix1="<Input type='Radio' name='Action' value='1'>- Tenter un passage supplémentaire à haute altitude.<br>";
				$choix3="";
				$cam_c=mt_rand(0,100); //Camouflage
			}
		}
		else
		{
			if($Recce_Base >0)
			{
				$cam_c=0;
				$base_pts=0;
			}
			else
			{
				if(!$cam_terrain or !$BaseAerienne)
					$cam_c=mt_rand(10,100); //Camouflage
				else
					$cam_c=$cam_terrain;
				$base_pts=10+($ValStrat*2);
			}
			$choix1="<Input type='Radio' name='Action' value='1'>- Tenter un passage supplémentaire à haute altitude.<br>";
			$choix3="";
			$arme_c=17;
		}	
		//DCA
		if($arme_c >0 and $alt <6000)
		{
			$Arme1=$arme_c;
			$dca_mult=GetData("Armes","ID",$Arme1,"Multi");
			$intro.="<br><b>La défense anti-aérienne rapprochée ouvre le feu sur vous!</b>";
			$Shoot_rand=mt_rand(10,200);
			$Shoot=$Shoot_rand+$meteo+($VisAvion/5)-($Pilotage/5)-($Speed/10)+$dca_mult-$AsZigZag;
			//JF
			if($PlayerID ==1)
			{
				$skills.="<br>[Score de Tir : ".$Shoot."]
						<br>+Vis ".$VisAvion."
						<br>-Speed ".$Speed." /10
						<br>-Pilotage ".$Pilotage." /10
						<br>Tir_eni :".$Tir_dca;
			}
			//End JF
			if($Shoot >0)
			{
				$Degats=round(mt_rand(1,GetData("Armes","ID",$Arme1,"Degats"))*GetShoot($Shoot,$dca_mult));
				if($Degats <1)$Degats=mt_rand(1,10);
				$HP-=$Degats;
				//HP Avion perso persistant
				if($Avion_db =="Avions_Persos")
				{
					if($HP <1)$HP=0;
					SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
				}
				if($HP <1)
				{
					$intro.='<br>L\'explosion met le feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
					$end_mission=true;
				}
				else
				{
					$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
					if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
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
					$attaque=true;
				}
				SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
			}
			else
			{
				$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
				$attaque=true;
			}
		}
		else
			$attaque=true;
		if($attaque and !$loose)
		{
			//$Shoot=mt_rand(0,$Vue)+ mt_rand(0,$Vue_Equipage) + ($Stab/10) + ($meteo*5) + ($Moral/10) - ($cam_c*2) - ($alt/10) - $Malus_Reperer;
			if(!$recce_ok)
				$Shoot=mt_rand(0,$Vue) + mt_rand(0,$Vue_Equipage) + ($Stab/10) + ($Moral/10) - $Malus_Reperer - ($cam_c*2) + ($meteo*2) - ($alt/10);
			else
				$Shoot=1;
			if(!$Photo_shoot)
				$Photo_shoot=mt_rand(0,50) + $Bonus_Camera + ($Stab/10) + ($meteo*2) - ($alt/100) - $Malus_Reperer+$As_Tele;
			/*JF
			if($cam_c >0)
			{
				$headers='MIME-Version: 1.0' . "\r\n";
				$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
				$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo."</p>";
				$msgm.="<br>[Score de reperer : ".$Shoot."]
									<br>+Vue ".$Vue." rand
									<br>+Vue_Equipage ".$Vue_Equipage." rand
									<br>-Alt ".$alt." x/10
									<br>-Meteo ".$meteo." *3
									<br>-Cam ".$cam_c." *2
									<br>-Malus_Zone ".$Malus_Reperer."
						<br>[Score de photo : ".$Photo_shoot."]
									<br>+Bonus_Camera ".$Bonus_Camera."
									<br>+Stab ".$Stab." /10
									<br>-Meteo ".$meteo." *2
									<br>-Alt ".$alt." x/100
									<br>-Malus_Zone ".$Malus_Reperer."
									</body></html>";
				mail('binote@hotmail.com','Aube des Aigles: Reperer PhotoShoot Log',$msgm,$headers);
			}*/
			if($Admin ==1)
			{
				$Stab_txt=$Stab/10;
				$Malus_alt=$alt/100;
				$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
					<tr><td>Camera</td><td>".$Camera."</td></tr>
					<tr><td>Pellicule</td><td>".$Mun2."</td></tr>
					<tr><th colspan='2'>Global</th></tr>
					<tr><td>Meteo (x2)</td><td>".$meteo."</td></tr>
					<tr><td>Stabilité</td><td>".$Stab_txt."</td></tr>
					<tr><td>Malus Terrain (-)</td><td>".$Malus_Reperer."</td></tr>
					<tr><th colspan='2'>Visuel</th></tr>
					<tr><td>Vue (rand)</td><td>".$Vue."</td></tr>
					<tr><td>Vue Equipage (rand)</td><td>".$Vue_Equipage."</td></tr>
					<tr><td>Camouflage (x2) (-)</td><td>".$cam_c."</td></tr>
					<tr><td>Malus Altitude (x10) (-)</td><td>".$Malus_alt."</td></tr>
					<tr><th>Visuel</th><th>".$Shoot."</th></tr>
					<tr><th colspan='2'>Photo</th></tr>
					<tr><td>Bonus Camera</td><td>".$Bonus_Camera."</td></tr>
					<tr><td>Malus Altitude (-)</td><td>".$Malus_alt."</td></tr>
					<tr><th>Photo</th><th>".$Photo_shoot."</th></tr>
				</table>";
			}
			//End JF
			if($Shoot >0 and $Simu)
			{
				if($Mission_Type ==15 and $Zone !=6)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,DefenseAA_temp,TypeIndus,Industrie,BaseAerienne,QualitePiste,Pont_Ori,Pont,Radar_Ori,Radar,Port_Ori,Port,NoeudF_Ori,NoeudF FROM Lieu WHERE ID='$Cible'")
					 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : photo-cible');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Cible_nom=$data['Nom'];
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
						}
						mysqli_free_result($result);
						unset($data);
					}	
					if(!$Target)$Target=15;
					$aa_type="aucune présence de défenses ennemies.";
					if($Radar_Ori >0)
					{
						if($Shoot >50)
						{
							if($Radar <1)
								$Cible_radar_txt="<br>un radar détruit";
							elseif($Radar <25)
								$Cible_radar_txt="<br>un radar pratiquement détruit";
							elseif($Radar <50)
								$Cible_radar_txt="<br>un radar sévèrement endommagé";
							elseif($Radar <75)
								$Cible_radar_txt="<br>un radar endommagé";
							elseif($Radar <100)
								$Cible_radar_txt="<br>un radar légèrement endommagé";
							else
								$Cible_radar_txt="<br>un radar intact";
						}
						else
							$Cible_radar_txt="<br>un radar";
					}
					else
						$Cible_radar_txt="";
					if($NoeudF_Ori >0)
					{
						if($Shoot >25)
						{
							if($NoeudF <1)
								$Cible_gare_txt="<br>un noeud ferroviaire inutilisable";
							elseif($NoeudF <25)
								$Cible_gare_txt="<br>un noeud ferroviaire pratiquement détruit";
							elseif($NoeudF <50)
								$Cible_gare_txt="<br>un noeud ferroviaire sévèrement endommagé";
							elseif($NoeudF <75)
								$Cible_gare_txt="<br>un noeud ferroviaire endommagé";
							elseif($NoeudF <100)
								$Cible_gare_txt="<br>un noeud ferroviaire légèrement endommagé";
							else
								$Cible_gare_txt="<br>un noeud ferroviaire intact";
						}
						else
							$Cible_gare_txt="<br>une gare";
					}
					else
						$Cible_gare_txt="";
					if($Pont_Ori >0)
					{
						if($Shoot >25)
						{
							if($Pont <1)
								$Cible_pont_txt="<br>un pont détruit";
							elseif($Pont <25)
								$Cible_pont_txt="<br>un pont pratiquement détruit";
							elseif($Pont <50)
								$Cible_pont_txt="<br>un pont sévèrement endommagé";
							elseif($Pont <75)
								$Cible_pont_txt="<br>un pont endommagé";
							elseif($Pont <100)
								$Cible_pont_txt="<br>un pont légèrement endommagé";
							else
								$Cible_pont_txt="<br>un pont intact";
						}
						else
							$Cible_pont_txt="<br>un pont";
					}
					else
						$Cible_pont_txt="";
					if($Port_Ori >0)
					{
						if($Shoot >25)
						{
							if($Port <1)
								$Cible_Port_txt="<br>des infrastructures portuaires inutilisable";
							elseif($Port <25)
								$Cible_Port_txt="<br>des infrastructures portuaires pratiquement détruites";
							elseif($Port <50)
								$Cible_Port_txt="<br>des infrastructures portuaires sévèrement endommagées";
							elseif($Port <75)
								$Cible_Port_txt="<br>des infrastructures portuaires endommagées";
							elseif($Port <100)
								$Cible_Port_txt="<br>des infrastructures portuaires légèrement endommagées";
							else
								$Cible_Port_txt="<br>des infrastructures portuaires intactes";
						}
						else
							$Cible_Port_txt="<br>un Port";
					}
					else
						$Cible_Port_txt="";
					if($TypeIndus !='')
					{
						if($Shoot >25)
						{
							if($Cible_indus <1)
								$Cible_ind_txt="<br>une zone industrielle détruite";
							elseif($Cible_indus <25)
								$Cible_ind_txt="<br>une zone industrielle pratiquement détruite";
							elseif($Cible_indus <50)
								$Cible_ind_txt="<br>une zone industrielle sévèrement endommagée";
							elseif($Cible_indus <75)
								$Cible_ind_txt="<br>une zone industrielle endommagée";
							elseif($Cible_indus <100)
								$Cible_ind_txt="<br>une zone industrielle légèrement endommagée";
							else
								$Cible_ind_txt="<br>une zone industrielle intacte";
						}
						else
							$Cible_ind_txt="<br>une zone industrielle";
					}
					else
					{
						$Cible_ind_txt="";
					}					
					if($Shoot >25)
					{
						if($Cible_DefenseAA >4)
							$aa_type="des défenses anti-aériennes de gros calibre";
						elseif($Cible_DefenseAA >2)
							$aa_type="des défenses anti-aériennes de calibre moyen";
						elseif($Cible_DefenseAA >0)
							$aa_type="des défenses anti-aériennes de faible calibre";
						else
							$aa_type="aucune défense anti-aérienne";
						//AddEvent($Avion_db,18,$avion,$PlayerID,$Unite,$Cible,$Cible_DefenseAA);
					}
					//Reco unités ennemies sur la base
					if($Shoot >50)
					{
						$Nom_unit=false;
						$con=dbconnecti();
						$resultuc=mysqli_query($con,"SELECT ID,Nom,Pays FROM Unit WHERE Base='$Cible'");
						mysqli_close($con);
						if($resultuc)
						{
							while($data=mysqli_fetch_array($resultuc,MYSQLI_ASSOC)) 
							{
								$Nom_unit.=Afficher_Icone($data['ID'],$data['Pays'],$data['Nom'])." ";
								$Units_Recce[]=$data['ID'];
								//AddEvent($Avion_db,19,$avion,$PlayerID,$Unite,$Cible,$data['ID']);
							}
							mysqli_free_result($resultuc);
						}
						if($Nom_unit)
						{
							$Unitz='<br>Vous repérez également les emblèmes des unités suivantes : '.$Nom_unit;
							if(array_count_values($Units_Recce) >0)
							{
								$Units_Recce_in=implode(',',$Units_Recce);
								$con=dbconnecti();
								$reset=mysqli_query($con,"UPDATE Unit SET Recce=1 WHERE ID IN(".$Units_Recce_in.")");
								mysqli_close($con);
								unset($Units_Recce);
							}
						}
					}
					if($Shoot >10) //Important en dernier de la liste des items à détecter
					{
						if($Cible_base >0)
						{
							if($Shoot >25)
							{
								if($Cible_base ==1)
									$piste="un aérodrome avec une piste en dur";
								elseif($Cible_base ==2)
									$piste="un aérodrome avec un bassin pour hydravions";
								else
									$piste="un aérodrome";
								if($Cible_piste <1)
									$Cible_base_txt="<br>".$piste." inutilisable";
								if($Cible_piste <100)
									$Cible_base_txt="<br>".$piste." endommagé";
							}
							else
								$Cible_base_txt="<br>un aérodrome";
						}
						else
							$Cible_base_txt="";
					}
					$intro.='<p>En survolant <b>'.$Cible_nom.'</b> vous repérez '.$aa_type.$Cible_ind_txt.$Cible_radar_txt.$Cible_pont_txt.$Cible_gare_txt.$Cible_port_txt.$Cible_base_txt.$Unitz.'</p>';
					unset($aa_type);
					unset($Cible_DefenseAA);
					unset($Cible_base);
					unset($Cible_base_txt);
					unset($data);
					unset($Nom_unit);
					unset($Unitz);
					if(!$img)
					{
						if($Zone ==6)
							$img="<img src='images/recce_sea.jpg' style='width:100%;'>";
						else		
							$img="<img src='images/recce.jpg' style='width:100%;'>";
					}
				}
				else
				{
					if($icons_navires)
						$img="<p><div style='overflow:auto; width: 600px; height: 400px;'>".$icons_navires."</div></p>";
					elseif($nbr_units_pj >0)
					{
						if($Shoot >25+$cam_c)
						{
							if($arme_c ==14)
								$aa_type="des défenses anti-aériennes de calibre moyen";
							elseif($arme_c ==15)
								$aa_type="des défenses anti-aériennes de gros calibre";
							elseif($arme_c ==17)
								$aa_type="des défenses anti-aériennes de faible calibre";
							else
								$aa_type="des défenses anti-aériennes non identifiées";
						}
						$intro.='<p>En survolant l\'objectif, vous repérez clairement un(e) <b>'.$Cible_nom.'</b></p>';
						if($aa_type)$intro.=' Cette unité possède '.$aa_type;
					}
				}
				if(!$loose)
				{
					//UpdateCarac($PlayerID,"Vue",1);
					if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
						UpdateCarac($Equipage,"Vue",1,"Equipage");
					$recce_ok=true;
				}
			}
			elseif($Photo_shoot >0 and $Simu and !$loose)
			{
				$msghit="<p>Vous n'identifiez pas visuellement la cible, mais votre photo est réussie!</p>";
				if(!$img)
				{
					if($Zone ==6)
						$img="<img src='images/recce_sea.jpg' style='width:100%;'>";
					else		
						$img="<img src='images/recce.jpg' style='width:100%;'>";
				}
				$Cible_nom="Non identifié";
				$Target=1;
				$recce_ok=true;
			}
			else
			{
				if($Photo_shoot <-100)
					$msghit="<p>Votre passage est raté, les photos ne seront pas exploitables!<br>Vu les conditions, il est inutile de persévérer!</p>";
				elseif($Photo_shoot <-20)
					$msghit="<p>Votre passage est raté, les photos ne seront pas exploitables!</p>";
				else
					$msghit="<p>Votre passage est raté, les photos ne seront pas exploitables!<br>Il ne vous manque pas grand chose!</p>";
				if(!$img)
					$img="<img src='images/strafing".$country.".jpg' style='width:100%;'>";
				$seconde_passe=true;
				$recce_ok=false;
			}
			if($recce_ok)
			{
				if($Mission_Type ==15)
				{
					if(!$Recce_Base or !$Recce_PlayerID)
					{
						$con=dbconnecti();
						$setlieu=mysqli_query($con,"UPDATE Lieu SET Recce=1,Recce_PlayerID='$PlayerID' WHERE ID='$Cible'")
						 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : photo-setreco');
						mysqli_close($con);
					}
					AddRecce($Avion_db,$Target,$avion,$PlayerID,$Unite,$Cible,1);
				}
				else
				{
					if($country !=$Pays_eni) //or $country !=$Flag)
					{
						$Faction=GetData("Pays","ID",$country,"Faction");
						if($Faction ==2 and !$Recce_PlayerID_TAL)
							SetData("Lieu","Recce_PlayerID_TAL",$PlayerID,"ID",$Cible);
						elseif($Faction ==1 and !$Recce_PlayerID_TAX)
							SetData("Lieu","Recce_PlayerID_TAX",$PlayerID,"ID",$Cible);
					}
					else
						$base_pts=0;
					AddRecce($Avion_db,$Target,$avion,$PlayerID,$Unite,$Cible);
				}
				$msghit="<br>Vous passez juste au-dessus de la cible et déclenchez vos photos au bon moment, votre objectif est dans la boite!<br><b>Vous avez accompli votre mission!</b>";
				if(!$img)
				{
					if($Zone ==6)
						$img="<img src='images/recce_sea.jpg' style='width:100%;'>";
					else		
						$img="<img src='images/recce.jpg' style='width:100%;'>";
				}
				//Récompenses Pts
				//Doubler la récompense en cas de mission de front
				if($Cible ==GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unite,"Type")))
				{
					$Cdt=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
					if($Cdt)
					{
						UpdateCarac($Cdt,"Reputation",5);
						UpdateCarac($Cdt,"Avancement",10);
					}
					$Pts_Bonus+=1;
				}
				//Doubler la récompense en cas de bataille historique
				$BH_Lieu=$_SESSION['BH_Lieu'];
				if($Cible == $BH_Lieu and $base_pts >0)
				{
					if(IsAxe($country))
						$Points_cat="Points_Axe";
					else
						$Points_cat="Points_Allies";
					$Pts_Bonus+=1;
					UpdateCarac($PlayerID,"Batailles_Histo",1);
					UpdateData("Event_Historique",$Points_cat,$base_pts,"ID",$_SESSION['BH_ID']);
				}
				//Doubler la récompense en cas de mission demandée
				if($Mission_Type ==15 or $Mission_Type ==21)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT ID FROM Unit WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D=15");
					//$result2=mysqli_query($con,"SELECT ID FROM Officier WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D=15");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							UpdateData("Unit","Reputation",$base_pts,"ID",$data['ID'],0,13);
						}
						mysqli_free_result($result);
						UpdateCarac($PlayerID,"Note",1);
						$Pts_Bonus+=1;
					}
					/*if($result2)
					{
						while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							UpdateData("Officier","Reputation",$base_pts,"ID",$data2['ID']);
							UpdateData("Officier","Avancement",$base_pts,"ID",$data2['ID']);
						}
						mysqli_free_result($result2);
						UpdateCarac($PlayerID,"Note",1);
						$Pts_Bonus+=1;
					}*/
				}
				elseif($Mission_Type ==5)
				{
					/*$con=dbconnecti();
					$result2=mysqli_query($con,"SELECT ID FROM Officier WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D=5");
					mysqli_close($con);
					if($result2)
					{
						while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							UpdateData("Officier","Reputation",$base_pts,"ID",$data2['ID']);
							UpdateData("Officier","Avancement",$base_pts,"ID",$data2['ID']);
						}
						mysqli_free_result($result2);
						UpdateCarac($PlayerID,"Note",1);
						$Pts_Bonus+=1;
					}*/
				}
				$Pts_Recce=$base_pts*$Pts_Bonus;
				if($Pts_Recce >50)$Pts_Recce=50;
				if($Pts_Recce >0)
				{
					UpdateData("Unit","Reputation",$Pts_Recce,"ID",$Unite,0,14);
					UpdateCarac($PlayerID,"Missions",$Pts_Recce);
					UpdateCarac($PlayerID,"Reputation",$Pts_Recce);
					UpdateCarac($PlayerID,"Avancement",$Pts_Recce);
					UpdateCarac($PlayerID,"Moral",10);
					if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
					{
						UpdateCarac($Equipage,"Missions",1,"Equipage");
						UpdateCarac($Equipage,"Avancement",$Pts_Recce,"Equipage");
						UpdateCarac($Equipage,"Reputation",$Pts_Recce,"Equipage");
					}
				}
				$retour=true;	
			}
			$intro.=$msghit;
		}
	}
	$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);	
	if($seconde_passe)
	{
		if(!$Eni_PvP and $Lieu_PvP != $Cible)
			$choix2="<Input type='Radio' name='Action' value='2'>- Tenter un passage supplémentaire à moyenne altitude.<br>";
		else
		{
			$choix1="";
			$choix2="";
			$choix3="";
		}
		$mes.="<form action='bomb.php' method='post'>
		<input type='hidden' name='Avion' value=".$avion.">
		<input type='hidden' name='Meteo' value=".$meteo.">
		<input type='hidden' name='Mun1' value=".$Mun1.">
		<input type='hidden' name='Mun2' value=".$Mun2.">
		<input type='hidden' name='Puissance' value=".$Puissance.">
		<input type='hidden' name='HP_eni' value=".$HP_eni.">
		<input type='hidden' name='Pays_eni' value=".$Pays_eni.">
		<table class='table><thead><tr><td colspan='8'>Reconnaissance Photo</td></tr></thead>
			<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,1)."<td align='left'>".$choix1.$choix3.$choix_pvp."</td></tr></table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";		
	}
	if($retour)
	{
		Chemin_Retour();
		$chemin=$Distance;
		$intro.='<br>Vous prenez le chemin du retour en direction de votre base, située à '.$Distance.'km';
		$mes.="<form action='nav.php' method='post'>
		<input type='hidden' name='Chemin' value=".$chemin.">
		<input type='hidden' name='Distance' value=".$Distance.">
		<input type='hidden' name='Meteo' value=".$meteo.">
		<input type='hidden' name='Avion' value=".$avion.">
		<input type='hidden' name='Mun1' value=".$Mun1.">
		<input type='hidden' name='Mun2' value=".$Mun2.">
		<input type='hidden' name='Puissance' value=".$Puissance.">
		<input type='hidden' name='Enis' value=".$Enis.">
		<table class='table'><tr>".ShowGaz($avion,$c_gaz,$flaps,$alt)."</tr></table>
		<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	if($end_mission)
	{
		if($_SESSION['PVP'])RetireCandidat($PlayerID,"end_mission");
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		UpdateCarac($PlayerID,"Abattu",1);
		if($HP <1)
		{
			//Tableau de chasse
			AddVictoire_atk($Avion_db,0,16,$avion,$PlayerID,$Unite,$Cible,$Arme1,$country,1,$alt,$Nuit,$Degats);
			AddEvent($Avion_db,3,$avion,$PlayerID,$Unite,$Cible);
			UpdateCarac($PlayerID,"Crashs_Jour",1);
		}
		else
			AddEvent($Avion_db,34,$avion,$PlayerID,$Unite,$Cible);		
		if($Zone ==6)
		{
			if($Slot5 ==17 or $Slot5 ==35)
				$intro.="<br>Votre gilet de sauvetage vous sauve de la noyade!";
			else
			{
				$intro.="<br>Sans gilet de sauvetage, vous êtes emporté par la mer jusqu'au rivage!";
				$CritH=true;
			}
		}
		//Blessure
		$blesse=0;
		if(!$CritH)
			$Blessure=GetBlessure($PlayerID,$Avion_db,$avion);
		else
			$Blessure=2;
		switch($Blessure)
		{
			case 0:
				$Blessure_txt="<br><br>Vous vous en sortez indemne!";
				$Hard=1;
				$Malus_Moral=-25;
			break;
			case 1:
				$Blessure_txt="<br><br>Vous êtes blessé, mais néanmoins en vie!";
				$Hard=1;
				$Malus_Moral=-50;
				AddEvent($Avion_db,9,$avion,$PlayerID,$Unite,$Cible);
				$blesse=1;
				DoBlessure($PlayerID,1);
			break;
			case 2:
				$Blessure_txt="<p>Vous gisez étendu sur le sol, mortellement blessé.</p>";
				$Hard=0;
				$Malus_Moral=-100;
				AddEvent($Avion_db,9,$avion,$PlayerID,$Unite,$Cible);
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Escorte=0,Couverture=0,MIA='$Cible',Commando=0 WHERE ID='$PlayerID'");
				mysqli_close($con);
				$blesse=2;
				DoBlessure($PlayerID,10);
			break;
		}
		$intro.=$Blessure_txt;
		UpdateCarac($PlayerID,"Moral",$Malus_Moral);
		if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
		{
			$Blessure_max=10;
			$Trait_e=GetData("Equipage","ID",$Equipage,"Trait");
			if($Trait_e ==3)$Blessure_max=20;
			$Blessure=mt_rand(0,$Blessure_max);
			if($Blessure <1)
				UpdateCarac($Equipage,"Endurance",-1,"Equipage");
			UpdateCarac($Equipage,"Moral",-25,"Equipage");
		}
		UpdateCarac($PlayerID,"Reputation",-10);
		//Prisonnier
		$Base=GetData("Unit","ID",$Unite,"Base");
		$Dist=GetDistance($Base,$Cible);
		if($BonneEtoile)
			$luck_p=mt_rand(0,25);
		elseif($Slot10 ==71)
			$luck_p=mt_rand(0,20);
		elseif($Slot10 ==72 or $Slot10 ==77)
			$luck_p=mt_rand(0,10);
		elseif($Slot10 ==34)
			$luck_p=mt_rand(0,10);
		else
			$luck_p=0;
		if($Mission_Type !=7 and $Mission_Type !=9 and $Mission <90 and $Mission_Type !=23 and $Dist[0] >30 and $luck_p <5 and $Simu)
		{
			$intro.="<p>Vous vous retrouvez au beau milieu d'une zone contrôlée par l'ennemi.
			<br>Le temps de regagner vos lignes vous rend indisponible jusqu'à votre retour.</p>";
			$mes="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,MIA='$Cible',Commando=0 WHERE ID='$PlayerID'");
			mysqli_close($con);
			$_SESSION['Distance'] =0;
		}
		else
		{		
			if($blesse <2)
				$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.="<form action='promotion.php' method='post'>
				<input type='hidden' name='Blesse' value=".$blesse.">
				<input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
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
usleep(1);
include_once('./index.php');
?>