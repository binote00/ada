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
$Battle=Insec($_POST['Battle']);
$Faction=Insec($_POST['Camp']);
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $_SESSION['photographier'] ==false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_inc_pvp.php');
	$_SESSION['cibler']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['photographier']=true;
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
	$result=mysqli_query($con,"SELECT S_HP,Pilotage,Equipage,Vue,Courage,Moral,Front,S_Avion_db,S_Cible,S_Mission,S_Cible_Atk,S_Longitude,S_Latitude,S_Essence,Simu,
	S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Equipage_Nbr,S_Nuit,Slot5,Slot10,Slot11,Admin FROM Pilote_PVP WHERE ID='$Pilote_pvp'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : photo_pvp-player');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$HP=$data['S_HP'];
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
	if($Equipage)$Endu_Eq=GetData("Equipage_PVP","ID",$Equipage,"Endurance");		
	if($Slot11 ==69)
	{
		$Moral+=50;
		$Courage+=50;
	}	
	//Meteo nécessaire ici
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,BaseAerienne,Meteo,Zone,ValeurStrat,Camouflage,Flag,Recce FROM Lieu WHERE ID='$Cible'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : photo-cible');
	$result2=mysqli_query($con,"SELECT Pays,Type,Robustesse,Masse,ArmeSecondaire FROM $Avion_db WHERE ID='$avion'")
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
		}
		mysqli_free_result($result);
		unset($result);
	}
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$country=$data['Pays'];
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
		if($Bombs and $Avion_Bombe)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Bombs));
			$moda*=(1+$charge_sup);
		}
		$Speed=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt);
		$choix_pvp="<Input type='Radio' name='Action' value='99' checked>- Rentrer à la base.<br>";
		$Eni_PvP=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"ID");
		$Lieu_PvP=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"Lieu");		
		if($Zone ==6 or $Mission_Type !=15)
		{
			if(mt_rand(0,100) <10 and !$ValStrat)
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
	if($Action ==98)
	{
		$intro.="<br>La vitesse de votre avion est insuffisante pour vous maintenir en vol.";
		$end_mission=true;
	}
	elseif($Action ==5)
	{
		$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
		$retour=true;
	}
	elseif($Eni_PvP and $Lieu_PvP ==$Cible)
	{
		$intro.="<p>Un ennemi vous prend en chasse, vous empêchant d'accomplir votre mission!</p>";
		$img=Afficher_Image("images/facetoface.jpg",'images/avions/vol'.$avion_img.'.jpg',$nom_avion);
		$chemin=0;
		$_SESSION['done']=false;
		$_SESSION['PVP']=true;
		if(!GetData("Duels_Candidats_PVP","PlayerID",$Pilote_pvp,"ID"))
			AddCandidatPVP($Avion_db,$Pilote_pvp,$avion,$HP,$Puissance,$essence,$chemin,$Distance,$Mun1,$Mun2,$alt,$Cible,$Nuit);
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
		$Stab=GetStab($Avion_db,$avion,$HP);
		if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
			$Vue_Equipage=GetData("Equipage_PVP","ID",$Equipage,"Vue");
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
			$con=dbconnecti();
			$pj_unit=mysqli_query($con,"SELECT r.ID,r.Pays,r.Officier_ID,r.Camouflage,r.Position,r.Visible,c.Taille,c.Detection,c.ID as navire FROM Regiment_PVP as r,Cible as c,Pays as p 
			WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position<>25 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : reco_pvp-reg');
			mysqli_close($con);
			if($pj_unit)
			{
				$nbr_units_pj=0;				
				while($data=mysqli_fetch_array($pj_unit))
				{
					if($data['ID'] >0)
					{
						$Pays_eni=$data['Pays'];
						$Taille=$data['Taille']/$data['Camouflage'];
						$Shoot=mt_rand(0,$Vue) + mt_rand(0,$Vue_Equipage) + ($Stab/10) + ($Moral/10) - $Malus_Reperer_reg + $Taille + ($meteo*2) - ($alt/10);
						$Photo_shoot=mt_rand(0,50) + $Bonus_Camera + ($Stab/10) + ($meteo*2) - ($alt/100) - $Malus_Reperer_reg;
						if($Shoot >1 or $Photo_shoot >1)
						{
							if(!$data['Visible'])
							{
								SetData("Regiment_PVP","Visible",1,"ID",$data['ID']);
								$nbr_units_pj++;
							}
							if(!$data['Officier_ID'] and $data['Position'] ==11)
								$data['navire']=5000;
							$icons_navires.="<br><img src='/images/vehicules/vehicule".$data['navire'].".gif'>";
							$recce_ok=true;
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
						$intro.="<p><b>Vous repérez au moins ".$nbr_units_pj." compagnies ennemies</b></p>";
						$Target=50;
					}
					else
					{
						$intro.="<p><b>Vous ne parvenez à identifier aucune unité ennemie</b></p>";
						$img="<img src='images/recce_terre.jpg' style='width:100%;'>";
					}
				}
				$choix3="<Input type='Radio' name='Action' value='3'>- Tenter un passage supplémentaire à basse altitude.<br>";
			}
			else
			{			
				$img="<img src='images/lieu/objectif_atk".$Pays_eni.$Cible_Atk.".jpg' style='width:100%;'>";
				$Cible_nom="absence totale de cible";
				$intro.="<br>Vous ne détectez aucune cible digne d'intérêt";
				$cam_c=0;
				$arme_c=0;
				$loose=true;
				$retour=true;
			}
			$choix1="";
		}
		elseif($Zone ==6 or $Port_Base)
		{
			//$Veh_Battle=GetVehPVP($Battle);
			if($Faction ==2)
				$Veh_Battle="5000";
			elseif($Faction ==1)
				$Veh_Battle="5000";
			$con=dbconnecti();
			$pj_unit=mysqli_query($con,"SELECT ID,Taille FROM Cible WHERE ID IN(".$Veh_Battle.")") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : reco_pvp-reg6');
			mysqli_close($con);
			if($pj_unit)
			{
				$nbr_units_pj=0;				
				while($data=mysqli_fetch_array($pj_unit))
				{
					$Shoot=mt_rand(0,$Vue) + mt_rand(0,$Vue_Equipage) + ($Moral/10) - $Malus_Reperer + $data['Taille'] + ($meteo*3) - ($alt/10);
					$Photo_shoot=mt_rand(0,50) + $Bonus_Camera + ($Stab/10) + ($meteo*2) - ($alt/100) - $Malus_Reperer;
					if($Shoot >1 or $Photo_shoot >1)
						$nbr_units_pj++;
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
			$Shoot=$Shoot_rand + $meteo + ($VisAvion/5) - ($Pilotage/5) - ($Speed/10) + $dca_mult;
			//JF
			if($Pilote_pvp ==1)
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
						if(GetData("Equipage_PVP","ID",$Equipage,"Moral") >0 and GetData("Equipage_PVP","ID",$Equipage,"Courage") >0)
						{
							$Equipage_Nom=GetData("Equipage_PVP","ID",$Equipage,"Nom");
							$Meca=floor(GetData("Equipage_PVP","ID",$Equipage,"Mecanique")/2);
							if($Meca > $Degats)$Meca=$Degats;
							$intro.='<p>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)</p>';
							$HP+=$Meca;
						}
					}
					$attaque=true;
				}
				SetData("Pilote_PVP","S_HP", $HP,"ID",$Pilote_pvp);
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
				$Photo_shoot=mt_rand(0,50) + $Bonus_Camera + ($Stab/10) + ($meteo*2) - ($alt/100) - $Malus_Reperer;
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
					 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : photo_pvp-cible');
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
					$recce_ok=true;
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
				if(!$img)$img="<img src='images/strafing".$country.".jpg' style='width:100%;'>";
				$seconde_passe=true;
				$recce_ok=false;
			}
			if($recce_ok)
			{
				UpdateData("Pilote_PVP","Recce",1,"ID",$Pilote_pvp);
				UpdateData("Pilote_PVP","Points",10,"ID",$Pilote_pvp);
				$msghit="<br>Vous passez juste au-dessus de la cible et déclenchez vos photos au bon moment, votre objectif est dans la boite!<br><b>Vous avez accompli votre mission!</b>";
				if(!$img)
				{
					if($Zone ==6)
						$img="<img src='images/recce_sea.jpg' style='width:100%;'>";
					else		
						$img="<img src='images/recce.jpg' style='width:100%;'>";
				}
				$retour=true;	
			}
			$intro.=$msghit;
		}
	}
	$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps,true);	
	if($seconde_passe)
	{
		if(!$Eni_PvP and $Lieu_PvP !=$Cible)
			$choix2="<Input type='Radio' name='Action' value='2'>- Tenter un passage supplémentaire à moyenne altitude.<br>";
		else
		{
			$choix1="";
			$choix2="";
			$choix3="";
		}
		$mes.="<form action='index.php?view=bomb_pvp' method='post'>
		<input type='hidden' name='Avion' value=".$avion.">
		<input type='hidden' name='Meteo' value=".$meteo.">
		<input type='hidden' name='Mun1' value=".$Mun1.">
		<input type='hidden' name='Mun2' value=".$Mun2.">
		<input type='hidden' name='Puissance' value=".$Puissance.">
		<input type='hidden' name='HP_eni' value=".$HP_eni.">
		<input type='hidden' name='Pays_eni' value=".$Pays_eni.">
		<input type='hidden' name='Battle' value=".$Battle.">
		<input type='hidden' name='Camp' value=".$Faction.">
		<table class='table><thead><tr><td colspan='8'>Reconnaissance Photo</td></tr></thead>
			<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,1,true)."<td align='left'>".$choix1.$choix3.$choix_pvp."</td></tr></table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";		
	}
	if($retour)
	{
		Chemin_Retour();
		$chemin=$Distance;
		$intro.='<br>Vous prenez le chemin du retour en direction de votre base, située à '.$Distance.'km';
		$mes.="<form action='index.php?view=nav_pvp' method='post'>
		<input type='hidden' name='Chemin' value=".$chemin.">
		<input type='hidden' name='Distance' value=".$Distance.">
		<input type='hidden' name='Meteo' value=".$meteo.">
		<input type='hidden' name='Avion' value=".$avion.">
		<input type='hidden' name='Mun1' value=".$Mun1.">
		<input type='hidden' name='Mun2' value=".$Mun2.">
		<input type='hidden' name='Puissance' value=".$Puissance.">
		<input type='hidden' name='Enis' value=".$Enis.">
		<input type='hidden' name='Battle' value=".$Battle.">
		<input type='hidden' name='Camp' value=".$Faction.">
		<table class='table'><tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,false,true)."</tr></table>
		<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	if($end_mission)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Unite_Intercept=0,Escorte=0,Couverture=0,Points=Points-1,Abattu=Abattu+1,Avion_Sandbox=0,S_HP=0 WHERE ID='$Pilote_pvp'");
		mysqli_close($con);
		if($_SESSION['PVP'])RetireCandidatPVP($Pilote_pvp,"end_mission");
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		if($Zone ==6)
		{
			if($Slot5 ==17 or $Slot5 ==35)
				$intro.="<br>Votre gilet de sauvetage vous sauve de la noyade!";
			else
				$intro.="<br>Sans gilet de sauvetage, vous êtes emporté par la mer jusqu'au rivage!";
		}
		$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
		$mes.="<p><b>FIN DE MISSION</b></p>";
		$menu.="<form action='index.php?view=profil_pvp' method='post'>
			<input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
include_once('./default.php');