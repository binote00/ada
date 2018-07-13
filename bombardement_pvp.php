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
$Deleguer=Insec($_POST['Deleguer']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$Battle=Insec($_POST['Battle']);
$Faction=Insec($_POST['Camp']);
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $_SESSION['bombarder'] ==false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_pvp.php');
	$_SESSION['cibler']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['bombarder']=true;
	$Distance=$_SESSION['Distance'];
	$country=$_SESSION['country'];
	$retour=false;
	$end_mission=false;
	$attaque=false;
	$reperage=false;
	$seconde_passe=false;
	$dca=false;
	$dca_unit=false;
	$gare=false;
	$usine=false;
	$piste=false;
	$hangar=false;
	$tour=false;
	$caserne=false;
	$citerne=false;
	$camion=false;
	$depot=false;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Front,Courage,Moral,Pilotage,Bombardement,Vue,Equipage,S_Avion_db,S_Nuit,S_Cible,S_Mission,S_Cible_Atk,S_Strike,S_Pass,S_Engine_Nbr,S_Blindage,
	S_Longitude,S_Latitude,S_Essence,Simu,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Avion_BombeT,S_Equipage_Nbr,S_Formation,Slot5,Slot10,Slot11,Admin FROM Pilote_PVP WHERE ID='$Pilote_pvp'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard_pvp-player');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$HP=$data['S_HP'];
			$Unite=$data['Unit'];
			$Front=$data['Front'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Pilotage=$data['Pilotage'];
			$Tir=$data['Bombardement'];
			$Vue=$data['Vue'];
			$Avion_db=$data['S_Avion_db'];
			$Cible=$data['S_Cible'];
			$Nuit=$data['S_Nuit'];
			$Mission_Type=$data['S_Mission'];
			$Cible_Atk=$data['S_Cible_Atk'];
			$Strike=$data['S_Strike'];
			$S_Pass=$data['S_Pass'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Bombs=$data['S_Avion_Bombe_Nbr'];
			$Avion_BombeT=$data['S_Avion_BombeT'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$S_Blindage=$data['S_Blindage'];
			$Formation=$data['S_Formation'];
			$Slot5=$data['Slot5'];
			$Slot10=$data['Slot10'];
			$Slot11=$data['Slot11'];
			$Simu=$data['Simu'];
			$Admin=$data['Admin'];
		}
		mysqli_free_result($result);
		unset($data);
	}		
	if($Slot11 ==69)
	{
		$Moral+=50;
		$Courage+=50;
	}		
	if($HP)
	{
		$go_shoot=true;
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT Nom,Meteo,DefenseAA_temp,Latitude,Longitude,Zone,ValeurStrat,Camouflage,Garnison,Recce,Fortification,Flag FROM Lieu WHERE ID='$Cible'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard_pvp-cible');
		$result=mysqli_query($con,"SELECT Type,Robustesse,Blindage,Volets,Moteur,Viseur,Masse,Radar FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard_pvp-avion');
		mysqli_close($con);
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Cible_nom=$data2['Nom'];
				$meteo=$data2['Meteo'];
				$DefenseAA=$data2['DefenseAA_temp'];
				$Lat_base=$data['Latitude'];
				$Long_base=$data['Longitude'];
				$Zone=$data['Zone'];
				$ValStrat=$data['ValeurStrat'];
				$Camouflage_lieu=$data['Camouflage'];
				$Garnison=$data['Garnison'];
				$Recce_Lieu=$data['Recce'];
				$Fortification=$data['Fortification'];
				$Flag=$data['Flag'];
			}
			mysqli_free_result($result2);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Type_Avion=$data['Type'];
				$HPmax=$data['Robustesse'];
				$Masse=$data['Masse'];
				$Blindage=$data['Blindage'];
				$Volets=$data['Volets'];
				$Moteur=$data['Moteur'];
				$Viseur=$data['Viseur'];
				$Radar=$data['Radar'];
			}
			mysqli_free_result($result);
		}
		//Meteo nécessaire ici
		if($Nuit)$meteo-=85;
		if($Equipage)$Endu_Eq=GetData("Equipage_PVP","ID",$Equipage,"Endurance");		
		if($Equipage and $Endu_Eq >0 and $Deleguer and !strrpos($Action,"66"))
		{
			$con=dbconnecti();		
			$result=mysqli_query($con,"SELECT Courage,Moral,Bombardement,Trait FROM Equipage_PVP WHERE ID='$Equipage'");		
			mysqli_close($con);		
			if($result)		
			{		
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))	
				{	
					$Courage=$data['Courage'];
					$Moral=$data['Moral'];
					$Tir=$data['Bombardement'];
					$Trait_e=$data['Trait'];
				}	
				mysqli_free_result($result);	
			}
			if($Trait_e ==1)
				$Tir*=1.1;
			elseif($Trait_e ==8 and $Moral <100)
				$Moral=100;
			elseif($Trait_e ==2 and $Courage <100)
				$Courage_Eq=100;
			if($Courage <1 and $Trait_e !=6)
			{
				$go_shoot=false;
				$Etat_Eq=" est tétanisé par la peur";
			}
			if($Moral <1 and $Trait_e !=6)
			{
				$go_shoot=false;
				$Etat_Eq=" est démoralisé";
			}	
		}
		$moda=$HPmax/$HP;
		if($Bombs >0 and $Avion_Bombe)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Bombs));
			$moda*=(1+$charge_sup);
		}
		$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt);
		$Speed=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$SpeedP=GetSpeedP($Avion_db,$avion,$Engine_Nbr,$c_gaz,$flaps);
	}
	else
		$Action=98;
	if($Speed <50)$Action=98;
	if(!$Bombs)$Action=97;
	$Pays_cible=GetFlagPVP($Battle,$Faction);
	$avion_img=GetAvionImg($Avion_db,$avion);
	$choix_pvp="<Input type='Radio' name='Action' value='99' checked>- Rentrer à la base.<br>";
	$Eni_PvP=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"ID");
	$Lieu_PvP=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"Lieu");
	//Boost
	if($c_gaz ==130)
		UpdateData("Pilote_PVP","Stress_Moteur",10,"ID",$Pilote_pvp);
	if($Action ==98)
	{
		$intro.="<br>La vitesse de votre avion est insuffisante pour vous maintenir en vol.";
		$end_mission=true;
	}
	elseif($Eni_PvP and $Lieu_PvP ==$Cible)
	{
		$intro.="<p>Un ennemi vous prend en chasse, vous empêchant d'accomplir votre mission!</p>";
		$img=Afficher_Image("images/facetoface.jpg", 'images/avions/vol'.$avion_img.'.jpg', $nom_avion);
		$chemin=0;
		$_SESSION['done']=false;
		$_SESSION['PVP']=true;
		if(!GetData("Duels_Candidats_PVP","PlayerID",$Pilote_pvp,"ID"))
			AddCandidatPVP($Avion_db,$Pilote_pvp,$avion,$HP,$Puissance,$essence,$chemin,$Distance,$Mun1,$Mun2,$alt,$Cible,$Nuit);
		$choix_pvp="<Input type='Radio' name='Action' value='90' checked>- Affronter votre adversaire.<br>";
		$seconde_passe=true;
	}
	elseif($Action ==97)
	{
		$intro.="<br>Vous n'avez pas de bombes!";
		$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg', 'images/avions/vol'.$avion_img.'.jpg',$nom_avion);
		$retour=true;
	}
	elseif($Action ==5)
	{
		$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg', 'images/avions/vol'.$avion_img.'.jpg',$nom_avion);
		$retour=true;
	}
	elseif($Action ==20)
	{
		$intro.="Vous tentez une seconde passe!";
		$img="<img src='images/cible_masquee.jpg' style='width:100%;'>";
		$seconde_passe=true;
	}
	elseif($go_shoot)
	{ 	
		$essence-=5;
		$arme_c_mun=1;
		if($Mission_Type ==24 or $Mission_Type ==25 or $Mission_Type ==27 or $Mission_Type ==14)
		{
			$arme_c=5;
			$def_c=0;
			$tank=999;
		}
		elseif($Mission_Type ==21)
		{
			$arme_c=15;
			$def_c=0;
			$tank=999;
		}
		elseif($Mission_Type ==2)
		{
			if(strpos($Action,"000ia") !==false)
				$Regi=strstr($Action,'000ia',true);
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Officier_ID,Vehicule_ID,Experience,Camouflage,Placement,Position FROM Regiment_PVP WHERE ID='$Regi'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard_pvp-reg');
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Officier_eni=$data['Officier_ID'];
					$tank=$data['Vehicule_ID'];
					$dca_unit_skill=$data['Experience'];
					$cible_camouflage=$data['Camouflage'];
					$cible_placement=$data['Placement'];
					$cible_position=$data['Position'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($tank)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,Type,Blindage_f,Blindage_t,Blindage_l,Blindage_a,Arme_AA,HP,Reput,mobile,Taille,Camouflage,Flak FROM Cible WHERE ID='$tank'")
				 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard_pvp-tank');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$nom_c='un '.$data['Nom'];
						$type_c=$data['Type'];
						$def_c=$data['Blindage_f'];
						$def_c_t=$data['Blindage_t'];
						$def_c_l=$data['Blindage_l'];
						$def_c_a=$data['Blindage_a'];
						$arme_c=$data['Arme_AA'];
						$hp_c=$data['HP'];
						$rep_c=$data['Reput'];
						$mobile=$data['mobile'];
						$cam_c=$data['Camouflage'];
						$Flak_c=$data['Flak'];
						if(!$cam_c)$cam_c=1;
						if(!$cible_camouflage)$cible_camouflage=1;
						$Taille_eni=$data['Taille']/$cam_c/$cible_camouflage;
					}
					mysqli_free_result($result);
					unset($data);
				}
			}
			else
				mail('binote@hotmail.com','Aube des Aigles: Attaque au sol error',"Cible : ".$tank." / Lieu : ".$Cible);
		}
		elseif($Mission_Type ==12 or $Mission_Type ==13 or $Mission_Type ==29)
		{
			$tank=$Action;
			$dca_unit_skill=50;
			if($tank)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,Type,Vitesse,Taille,Blindage_f,Blindage_t,Arme_AA,Arme_AA2,Arme_AA3,Arme_AA_mun,Arme_AA2_mun,Arme_AA3_mun,HP,Reput,Camouflage,mobile FROM Cible WHERE ID='$tank'")
				 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard-tank-naval');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$nom_c='un '.$data['Nom'];
						$type_c=$data['Type'];
						$Vitesse=$data['Vitesse'];
						$Taille=$data['Taille'];
						$deft_c=$data['Blindage_f'];
						$def_c=$data['Blindage_t'];
						$arme_c=$data['Arme_AA'];
						$arme2_c=$data['Arme_AA2'];
						$arme3_c=$data['Arme_AA3'];
						$arme_c_mun=$data['Arme_AA_mun'];
						$arme2_c_mun=$data['Arme_AA2_mun'];
						$arme3_c_mun=$data['Arme_AA3_mun'];
						$hp_ori=$data['HP'];
						$rep_c=$data['Reput'];
						$cam_c=$data['Camouflage'];
						$mobile=$data['mobile'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				$hp_c=$hp_ori;
				$Target_id="cibles".$tank;
			}
		}
		else
		{
			if($DefenseAA >($ValStrat*2)+2)$DefenseAA=($ValStrat*2)+2;
			if($Cible_Atk ==1)
			{				
				$dca_unit_skill=50;
				/*$Alt_Flak_min=$alt-1000;
				$Alt_Flak_max=$alt+1000;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID,DCA_ID,DCA_Exp,Unit FROM Flak WHERE Lieu='$Cible' AND (Alt BETWEEN '$Alt_Flak_min' AND '$Alt_Flak_max') ORDER BY RAND() LIMIT 1");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result))
					{
						$dca_unit_skill=$data['DCA_Exp'];
						$arme_c=$data['DCA_ID'];
						$DCA_Unit_ID=$data['Unit'];
						$DCA_ID=$data['ID'];
					}
					mysqli_free_result($result);
				}*/
				$cam_c=$Camouflage_lieu;
				switch($Action)
				{
					case 1:
						$nom_c="la piste";
						$def_c=20;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						//$arme_c=17;
						$hp_c=10000;
						$rep_c=15;
						$type_c=30;
						$piste=true;
						$tank=66;
					break;
					case 2:
						$nom_c="un emplacement de D.C.A";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						//$arme_c=15;
						$hp_c=500;
						$rep_c=7;
						$cam_c+=10;
						$type_c=12;
						$dca_unit=true;
						$tank=16;
					break;
					case 3:
						$nom_c="un hangar";
						$def_c=5;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						//$arme_c=17;
						$hp_c=1000;
						$rep_c=3;
						$type_c=25;
						$hangar=true;
						$tank=1;
					break;
					case 4: case 6:
						$nom_c="la tour de contrôle";
						$def_c=20;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						//$arme_c=4;
						$hp_c=5000;
						$rep_c=10;
						$type_c=26;
						$tour=true;
						$tank=2;
					break;
				}
			}
			elseif($Cible_Atk ==2)
			{
				switch($Action)
				{
					case 1:
						$nom_c="un entrepôt";
						$def_c=5;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						$hp_c=1000+(1000*$ValStrat);
						$rep_c=3;
						$cam_c=0;
						$type_c=25;
						$usine=true;
						$tank=3;
					break;
					case 2:
						$nom_c="un emplacement de D.C.A";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=500+(500*$ValStrat);
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c="un bâtiment secondaire";
						$def_c=20;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=5000+(5000*$ValStrat);
						$rep_c=15;
						$cam_c=0;
						$type_c=25;
						$usine=true;
						$tank=4;
					break;
					case 4: case 6:
						$nom_c="le bâtiment principal";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=10000+(10000*$ValStrat);
						$rep_c=30;
						$cam_c=0;
						$type_c=27;
						$usine=true;
						$tank=5;
					break;
				}
			}
			elseif($Cible_Atk ==3)
			{
				switch($Action)
				{
					case 1:
						$nom_c="un groupe de soldats de la garnison";
						if($Fortification >0)
							$def_c=$Fortification;
						else
							$def_c=0;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						$hp_c=100;
						$rep_c=2;
						$cam_c=20;
						$tank=48;
					break;
					case 2:
						$nom_c="un canon";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						$hp_c=500;
						$rep_c=3;
						$cam_c=20;
						$type_c=6;
						$tank=17;
					break;
					case 3:
						$nom_c="un bâtiment secondaire";
						if($Fortification >0)
							$def_c=$Fortification;
						else
							$def_c=5;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						$hp_c=2000+(2000*$ValStrat);
						$rep_c=2;
						$cam_c=0;
						$tank=6;
					break;
					case 4: case 6:
						$nom_c="le bâtiment principal";
						if($Fortification >10)
							$def_c=$Fortification;
						else
							$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=5000+(5000*$ValStrat);
						$rep_c=5;
						$cam_c=0;
						$type_c=34;
						$caserne=true;
						$tank=7;
					break;
				}
			}
			elseif($Cible_Atk ==4)
			{
				switch($Action)
				{
					case 1:
						$nom_c="les voies";
						$def_c=5;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						$hp_c=500+(500*$ValStrat);
						$rep_c=1;
						$cam_c=0;
						$gare=true;
						$tank=8;
					break;
					case 2:
						$nom_c="Emplacement de D.C.A";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=500+(500*$ValStrat);
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c="un entrepôt";
						$def_c=5;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						$hp_c=1000+(1000*$ValStrat);
						$rep_c=3;
						$cam_c=0;
						$type_c=25;
						$depot=true;
						$tank=3;
					break;
					case 4: case 6:
						$nom_c="le bâtiment principal";
						$def_c=20;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=3000+(3000*$ValStrat);
						$rep_c=15;
						$cam_c=0;
						$type_c=28;
						$gare=true;
						$tank=9;
					break;
				}
			}
			elseif($Cible_Atk ==5)
			{
				switch($Action)
				{
					case 1:
						$nom_c="un véhicule";
						$def_c=0;
						$arme_c=4;
						$hp_c=200;
						$rep_c=1;
						$cam_c=5;
						$type_c=1;
						$tank=18;
					break;
					case 2:
						$nom_c="un emplacement de D.C.A";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=500+(500*$ValStrat);
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c="le pont, en enfilade";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=8000+(8000*$ValStrat);
						$rep_c=25;
						$cam_c=0;
						$type_c=29;
						$pont=true;
						$tank=10;
					break;
					case 4:
						$nom_c="le pont, perpendiculairement";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=8000+(8000*$ValStrat);
						$rep_c=25;
						$cam_c=0;
						$type_c=29;
						$pont=true;
						$tank=10;
					break;
				}
			}	
			elseif($Cible_Atk ==6)
			{
				switch($Action)
				{
					case 1:
						$nom_c="un entrepôt";
						$def_c=5;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						$hp_c=1000+(1000*$ValStrat);
						$rep_c=3;
						$cam_c=0;
						$type_c=25;
						$camion=10;
						$depot=true;
						$tank=3;
					break;
					case 2:
						$nom_c="un emplacement de D.C.A";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=500+(500*$ValStrat);
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c="les réserves de carburant";
						$def_c=0;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=1500+(1500*$ValStrat);
						$rep_c=5;
						$cam_c=0;
						$type_c=31;
						$port=true;
						$citerne=true;
						$tank=11;
					break;
					case 4: case 6:
						$nom_c="les quais";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=10000+(10000*$ValStrat);
						$rep_c=30;
						$cam_c=0;
						$port=true;
						$tank=12;
					break;
				}
			}
			elseif($Cible_Atk ==7)
			{
				switch($Action)
				{			
					case 1:
						$nom_c="un bâtiment secondaire";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=2000+(2000*$ValStrat);
						$rep_c=5;
						$cam_c=20;
						$tank=13;
					break;
					case 2:
						$nom_c="un emplacement de D.C.A";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=500+(500*$ValStrat);
						$rep_c=7;
						$cam_c=20;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3: case 6:
						$nom_c="le bâtiment principal";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=10000+(10000*$ValStrat);
						$rep_c=30;
						$cam_c=20;
						$radar=true;
						$tank=14;
					break;
					case 4:
						$nom_c="une antenne";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,1);
						$hp_c=4000+(4000*$ValStrat);
						$rep_c=20;
						$cam_c=20;
						$radar=true;
						$tank=15;
					break;
				}
			}
		}
		$HP_eni=$hp_c;
		//DCA
		if($alt <1000 and $arme3_c >0 and $Type_Avion !=11)
		{
			$arme_c=$arme3_c;
			$arme_c_mun=$arme3_c_mun;
		}
		elseif($alt <4000 and $arme2_c >0)
		{
			$arme_c=$arme2_c;
			$arme_c_mun=$arme2_c_mun;
		}
		if($arme_c !=5 and $arme_c >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Portee,Calibre,Multi,Degats FROM Armes WHERE ID='$arme_c'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$DCA_Plafond=$data['Portee'];
					$dca_cal=round($data['Calibre']);
					$dca_mult=$data['Multi'];
					$dca_degats=$data['Degats'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			//Nbr tir
			$Nbr_Tir=$arme_c_mun/100;
			if($Nbr_Tir >10)$Nbr_Tir=10;
			if($Nbr_Tir <1)$Nbr_Tir=1;
			if($alt <=$DCA_Plafond and $Cible_Atk !=23 and $dca_cal >0)
			{
				for($i=1;$i<=$Nbr_Tir;$i++)
				{
					$DCA_mun=9999;			
					if($DCA_mun >=$dca_mult)
					{
						if($i ==$Nbr_Tir)
						{
							$intro.="<br><b>La défense anti-aérienne rapprochée ouvre le feu sur vous!</b>";
							if($Target_id)
								$img=Afficher_Image('images/cibles/'.$Target_id.'.jpg',"images/image.png",$nom_c);
							else
								$img=Afficher_Image('images/cibles/cibles'.$tank.'_'.$Pays_cible.'.jpg','images/cibles/cibles'.$tank.'.jpg',$nom_c);
						}
						$dca_max=$rep_c*10;
						if($dca_max >250)$dca_max=250;	
						$Shoot_rand=mt_rand(10,50)+mt_rand($DCA,$dca_unit_skill)+mt_rand($rep_c,$dca_max);
						if($S_Pass)
							$meteo_malus=0;
						else
							$meteo_malus=$meteo;				
						if($alt <5000)
							$Shoot_rand+=((5000-$alt)/50);
						if($alt <1001 and ($Type_avion ==2 or $Type_avion ==11))
							$VisAvion=$VisAvion/(($alt/100)/5);
						if($Action ==66) //Bomb en Piqué
							$Shoot=$Shoot_rand+$meteo_malus+($VisAvion/10) - ($Pilotage/2) - ($SpeedP/5)+($S_Pass*25)+$dca_mult;
						elseif($Mission_Type ==13)
							$Shoot=$Shoot_rand+$meteo_malus+($VisAvion/5) - ($Pilotage/2) - ($Speed/10)+($S_Pass*25)+$dca_mult;
						else
							$Shoot=$Shoot_rand+$meteo_malus+$VisAvion - ($Pilotage/2) - ($Speed/10)+($S_Pass*25)+$dca_mult;
						//JF
						if($Pilote_pvp ==1)
						{
							$skills.="<br>[Score de Tir : ".$Shoot."]
												<br>+Vis ".$VisAvion."
												<br>+Meteo ".$meteo_malus."
												<br>-Speed ".$Speed." /10
												<br>-Pilotage ".$Pilotage." /2
												<br>-S_Pass ".$S_Pass." *25
												<br>+Tir_eni :".$Shoot_rand;
						}
						//End JF
						if($Shoot >10 or $Shoot_rand >250 or $Shoot_rand ==$dca_unit_skill)
						{
							if($alt <501 and $Type_Avion ==11)
								$Degats_base=$dca_degats;
							else
								$Degats_base=mt_rand(0,$dca_degats);
							$Degats=round(($Degats_base-pow($Blindage,2))*GetShoot($Shoot,$dca_mult));
							if($Degats <1)
								$Degats=mt_rand(1,10);
							if($alt <4500)
								$Degats+=ceil($VisAvion);
							if($S_Pass)
								$Degats*=$S_Pass;
							$HP-=$Degats;
							if($Shoot >100)
							{
								$CritH=CriticalHit($Avion_db,$avion,$Pilote_pvp,2,$Engine_Nbr,true); //Todo : Remplacer 2 par type de munition
								$intro.=$CritH[0];
								$end_mission=$CritH[1];
								if($end_mission)
									$HP=0;
								if($CritH[2] ==1)
									$Mun1=0;
								if($CritH[3] ==1)
									$Mun2=0;
								if($CritH[6])
									$essence-=$CritH[6];
								unset($CritH);
							}
							if($HP <1)
							{
								$intro.='<br>Un obus frappe votre appareil de plein fouet. L\'explosion met me feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
								$end_mission=true;
								break;
							}
							else
							{
								$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
								if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
								{
									if(GetData("Equipage_PVP","ID",$Equipage,"Moral") >0 and GetData("Equipage_PVP","ID",$Equipage,"Courage") >0)
									{
										$Equipage_Nom=GetData("Equipage_PVP","ID",$Equipage,"Nom");
										$Meca=floor(GetData("Equipage_PVP","ID",$Equipage,"Mecanique")/2);
										if($Meca >$Degats)$Meca=$Degats;
										$intro.='<p>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)</p>';
										$HP+=$Meca;
									}
								}
								$reperage=true;
							}
							SetData("Pilote_PVP","S_HP",$HP,"ID",$Pilote_pvp);
						}
						else
						{
							$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
							$reperage=true;
						}
					}
					else
					{
						$intro.="<br>La DCA rapprochée est étrangement silencieuse!";
						$reperage=true;
					}
				} //End For Nbr_Tir
			}
			else
			{
				$intro.="<br>Les obus de DCA explosent bien trop bas pour vous atteindre!";
				$reperage=true;
			}
		}
		elseif($Mission_Type ==101)
		{
			$intro.="<br><i>La DCA ne tire pas en mission d'entrainement, mais ce ne sera pas le cas en mission de combat!</i>";
			$reperage=true;
		}
		else
		{
			$intro.="<br>La DCA rapprochée est étrangement silencieuse!";
			$reperage=true;
		}
		//Repérage
		if($reperage and $tank >0 and !$end_mission)
		{
			if($Cible_Atk ==23 or $type_c ==37)
			{
				$reperer=mt_rand(0,50)+($Radar*10)+$meteo-$cam_c;
			}
			else
				$reperer=1;
			if($reperer >0)
				$attaque=true;
			else
			{
				$intro.="<br>Vous ne distinguez pas suffisamment la cible et ne parvenez pas à viser correctement!";
				$seconde_passe=true;
				$attaque=false;
			}
		}
		else
		{
			$intro.="<br>Vous ne distinguez pas suffisamment la cible et ne parvenez pas à viser correctement!";
			$seconde_passe=true;
			$attaque=false;
		}		
		if(($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13) and $HP_eni <1)
			$attaque=false;		
		if($attaque and !$end_mission)
		{
			$rand_tir=11;
			$Bombs_Hit=1;
			$ArmeAvion=$Avion_Bombe;
			if(!$Avion_Bombe)$ArmeAvion=GetData($Avion_db,"ID",$avion,"Bombe");
			$Stab=GetStab($Avion_db,$avion,$HP);
			switch($Viseur)
			{
				case 0:
					$Bonus_Viseur=0;
				break;
				case 1:
					$Bonus_Viseur=-10;
				break;
				case 2:
					$Bonus_Viseur=0;
				break;
				case 3:
					$Bonus_Viseur=10;
				break;
				case 4:
					$Bonus_Viseur=20;
				break;
			}
			if($Action ==66) //Bomb en Piqué
			{
				if(!$def_c_t)
					$def_c=$def_c/2;
				else
					$def_c=$def_c_t;
				if($Volets ==3 and $flaps ==4)
					$Bonus_pique=35;
				else
					$Bonus_pique=10;
				$Shoot=mt_rand(0,$Tir)+($meteo/2)+($Courage/5)+($Moral/10)-$def_c-($SpeedP/5)+$Bonus_Viseur+$Bonus_pique+$Bonus_Pathfinder-($Nuit*100);
				$debug_msg="Bomb Piqué Joueur ".$Pilote_pvp." (courage/5 + Moral/10)=".$Shoot." (Tir=".$Tir." ; Vitesse Piqué/5=-".$SpeedP." ; Météo/2=-".$meteo." ; MalusBonus_Viseur=".$Bonus_Viseur." ; Bonus_pique=".$Bonus_pique." ; Bonus_Pathfinder=".$Bonus_Pathfinder." ; Défense=-".$def_c.")";
				if($debug_msg)mail('binote@hotmail.com','Aube des Aigles: Bomb Piqué Stats',$debug_msg);		
				//JF
				if($Pilote_pvp ==1 or $Admin ==1)
				{
					$skills.="<br>[Lâché de bombe en piqué : ".$Shoot."]
										<br>+Viseur ".$Bonus_Viseur."
										<br>+Bonus Piqué ".$Bonus_pique."
										<br>+Bonus Path ".$Bonus_Pathfinder."
										<br>-meteo ".$meteo." /2
										<br>-Speed ".$Speed." /5
										<br>-Def ".$def_c."
										<br>-Nuit ".$Nuit." *100
										<br>+Courage/5 +Moral/10";
				}
				//End JF
			}
			elseif($Mission_Type ==13) // Torpillage
			{
				$def_c=$deft_c;
				if($alt <101)
					$Torp_shoot=mt_rand(0,$Tir)+($Tir/10);
				else
					$Torp_shoot=mt_rand(0,$Tir);
				//Esquive
				if($dca_unit_skill >0)
				{
					$Esquive=mt_rand(1,$dca_unit_skill);
					$Taille/=2;
					if($RR =="Vehicule_Nbr" and $Regi)
					{
						$Officier_eni=GetData("Regiment_PVP","ID",$Regi,"Officier_ID");
						if($Officier_eni >0 and IsSkill(38,$Officier_eni))
							$Esquive+=25;
					}
				}
				elseif($type_c >18)
				{
					$Esquive=mt_rand(50,200);
					$Taille=50;
				}
				elseif($type_c <17)
				{
					$Esquive=mt_rand(0,200);
					$Taille=0;
				}
				else
				{
					$Esquive=mt_rand(25,200);
					$Taille=25;
				}
				if($cible_position ==26 or $cible_position ==29)
					$Vitesse=0;
				$Shoot=$Torp_shoot+($Stab/10)+$meteo+($Moral/10)-($alt/10)-$Esquive-$Vitesse+$Taille;
				/*$debug_msg="Torpillage Joueur ".$Pilote_pvp." (Moral/10)=".$Shoot." (Tir=".$Torp_shoot." ; Stabilité/10=".$Stab." ; Météo=-".$meteo." ; Esquive=- ".$Esquive." ; Taille=".$Taille." ; Malus Altitude/10=-".$alt.")";
				if($debug_msg)mail('binote@hotmail.com','Aube des Aigles: Torpillage Stats',$debug_msg);*/
				//JF
				if($Pilote_pvp ==1 or $Admin ==1)
				{
					$skills.="<br>[Torpillage : ".$Shoot."]
										<br>+Stab ".$Stab." /10
										<br>-meteo ".$meteo."
										<br>-Altitude ".$alt." /10
										<br>-Esquive ".$Esquive."
										<br>+Taille ".$Taille."
										<br>+Moral/10";
				}
			}
			else
			{
				$Esquive=$def_c;
				if($Regi >0)
				{
					$local_hit=mt_rand(0,10);
					if($local_hit ==10)
						$def_c=$def_c_a;
					elseif($local_hit >7)
						$def_c=$def_c_l;
					elseif($local_hit >3)
						$def_c=$def_c_t;
					if($Mission_Type ==2 and $Taille_eni and $Zone !=6)
					{
						if($Flag == $Pays_cible and $cible_placement ==0 and $Fortification >0)
							$def_c+=Get_Blindage($Zone,$Taille_eni,$Fortification,$cible_position);
						elseif(!$Fortification and $cible_position ==2)
							$def_c+=Get_Blindage($Zone,$Taille_eni,0,2);
					}
					if($RR =="Vehicule_Nbr" and $Regi)
					{
						$Officier_eni=GetData("Regiment_PVP","ID",$Regi,"Officier_ID");
						if($Officier_eni >0 and IsSkill(38,$Officier_eni))
							$Esquive+=25;
					}
				}
				$rand_tir=mt_rand(0,$Tir);
				$Shoot=$rand_tir+($Stab/10)+$meteo+($Courage/10)+($Moral/10)-$Esquive+($Bonus_Viseur*5)-($alt/100);
				//JF
				if($Admin == 1)
				{
					$skills.="<br>[Lâché de bombe horizontal : ".$Shoot."]
										<br>+Rand_tir ".$rand_tir."
										<br>+Viseur ".$Bonus_Viseur." *5
										<br>+Stab ".$Stab." /10
										<br>-meteo ".$meteo."
										<br>-Altitude ".$alt." /100
										<br>-Def ".$Esquive."
										<br>+Courage/10 +Moral/10";
					$Shoot+=500;
				}
				//End JF
			}			
			//Mult Dg
			switch($Avion_BombeT)
			{
				case 0:
					$mult_deg=30-$def_c;				
				break;
				case 1: //incendiaire
					if($port)
					{
						$mult_deg=76-$def_c;
						$HP_eni-=$Bombs*10;
					}
					elseif($citerne or $gare or $hangar or $depot or $camion)
					{
						$mult_deg=31-$def_c;
						$HP_eni-=$Bombs*10;
					}
					else
						$mult_deg=21-$def_c;	
				break;
				case 2: //anti-personnel
					if(!$def_c)
						$mult_deg=51-$def_c;				
					else
						$mult_deg=15-$def_c;				
				break;
				case 3: //anti-tank
					if($type_c ==91 or ($type_c >0 and $type_c <14))
					{
						$def_c-=($Shoot/10);
						$mult_deg=101-$def_c;
					}
					else
						$mult_deg=15-$def_c;				
				break;
				case 4: //anti-navire
					if($tank >4999)
						$mult_deg=101-$def_c;	
					else
						$mult_deg=15-$def_c;	
				break;
				case 5: //anti-batiment
					if($usine or $gare or $caserne or $pont or $radar)
						$mult_deg=101-$def_c;				
					else
						$mult_deg=15-$def_c;				
				break;
				case 6: //anti-piste
					if($piste)
						$mult_deg=51-$def_c;		
					else
						$mult_deg=15-$def_c;		
				break;
			}
			if($mult_deg <1)$mult_deg=1;
			if($Mission_Type ==21)
			{
				$ArmeAvion=1;
				$Bombs-=1;
				$msg_hit="Vos fusées éclairantes illuminent la cible";
				$mult_deg=0;
			}
			elseif($Mission_Type ==24 or $Mission_Type ==25)
			{
				$ArmeAvion=1;
				$msg_hit="Vous larguez les parachutistes sur la cible";
				$mult_deg=0;
				$Shoot=1;
				$rand_tir=11;
			}
			elseif($Mission_Type ==27)
			{
				$ArmeAvion=1;
				$msg_hit="Vous larguez le commando sur la cible";
				$mult_deg=0;
				$Shoot=1;
				$rand_tir=11;
			}
			elseif($ArmeAvion ==800)
			{
				$Bombs-=1;
				$msg_hit="Votre torpille explose";
				$mult_deg=300-$def_c;
				if($mult_deg <1)$mult_deg=1;
			}
			elseif($ArmeAvion ==300)
			{
				$Bombs-=1;
				$msg_hit="Votre charge de profondeur explose, provoquant une énorme gerbe d'eau!";
				if($Cible_Atk ==23 or $Mission_Type ==29)
					$mult_deg=20;
				else
					$mult_deg=1;
			}
			elseif($ArmeAvion ==400)
			{
				$Bombs-=1;
				$msg_hit="Vous larguez une mine maritime!";
				$mult_deg=0;
			}
			elseif(($Mission_Type >7 and $Cible_Atk <10 and $Action !=66 and $alt >4499 and $Zone !=6) or ($Type_Avion ==11 and $Zone !=6) or $Action ==5)
			{
				$Bombs_Hit=GetShoot($Shoot,$Bombs);
				if($Formation >1)
				{
					$msg_formation="les bombes de votre formation";
					if($Shoot >100)
						$Bombs_Hit_Formation=$Bombs_Hit*$Formation;
					elseif($Shoot >50)
						$Bombs_Hit_Formation=$Bombs_Hit*mt_rand(1,$Formation);
					elseif($Shoot >10)
						$Bombs_Hit_Formation=$Bombs_Hit*mt_rand(1,$Formation/2);
					else
						$Bombs_Hit_Formation=$Bombs_Hit;
				}
				else
					$msg_formation="vos bombes";
				if($Bombs_Hit >=$Bombs)
					$msg_hit="Toutes ".$msg_formation." explosent sur la cible !";
				elseif($Bombs_Hit)
					$msg_hit="Il semblerait que ".$msg_formation." explosent sur la cible, mais certaines en sont bien loin!";
				else
					$msg_hit="Hélas, ".$msg_formation." explosent carrément à côté de la cible !";
				$Bombs=0;
				$mult_deg*=1.25;
			}
			else
			{
				if($ArmeAvion >500)$ArmeAvion=500;
				if($Action ==66)$Bombs_Hit*=2;
				$Bombs-=1;
				$msg_hit="Votre bombe explose";
			}			
			if($Shoot >0 and $rand_tir >10)
			{
				if($Mission_Type ==24 or $Mission_Type ==25 or $Mission_Type ==27)
				{
					$rep_c=floor((($ValStrat+2)/2)*10);
					if(IsWar($Flag,$country))
					{
						if($Mission_Type ==27)
						{
							$Cdo=GetData("Pilote_PVP","ID",$Cible_Atk,"Nom");
							$intro.='<p><b>Vous larguez '.$Cdo.' sur l\'objectif.<br>Vous avez accompli votre mission!</b></p>';
							SetData("Pilote_PVP","MIA",$Cible,"ID",$Cible_Atk);
							SetData("Pilote_PVP","S_Cible_Atk",0,"ID",$Pilote_pvp);
						}
						else
						{
							$Cdo=GetData("Pilote_PVP","ID",$Cible_Atk,"Nom");
							$intro.='<p><b>Vous larguez '.$Bombs.' parachutistes sous le commandement de <b>'.$Cdo.'</b> sur l\'objectif.<br>Vous avez accompli votre mission!</b></p>';
							$today=getdate();
							$Heure=$today['hours'];
							$con=dbconnecti();
							$reset=mysqli_query($con,"UPDATE Pilote_PVP SET S_Cible_Atk=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$Pilote_pvp'");
							mysqli_close($con);
						}
						SetData("Pilote_PVP","S_Strike",1,"ID",$Pilote_pvp);
					}
					else
						$msghit="<p>Vous attaquez des cibles alliées!</p>";
					$img="<img src='images/parachutage.jpg' style='width:100%;'>";	
					$Bombs=0;
					$retour=true;
				}
				elseif($Mission_Type ==14)
				{
					$img="<img src='images/mouillage_mine.jpg' style='width:100%;'>";
					if($Strike ==false)
						SetData("Pilote_PVP","S_Strike",1,"ID",$Pilote_pvp);
					$retour=true;
				}
				elseif($Mission_Type ==21)
				{
					$img="<img src='images/pathfinder.jpg' style='width:100%;'>";
					//Reput missions unité
					if($Recce_Lieu !=2 and $Simu)
					{
						$rep_c=20+($ValStrat*3);
						if(IsWar($Flag,$country) or $Zone ==6)
						{
							if($Strike ==false)
								SetData("Pilote_PVP","S_Strike",1,"ID",$Pilote_pvp);
						}
						else
							$msghit="<p>Vous attaquez des cibles alliées!</p>";
					}
					else
						$msghit="<p><b>Cette cible est déjà illuminée!</b></p>";
					$retour=true;
				}
				else
				{
					$Degats=1;
					if($mult_deg >100)$mult_deg=100;
					if($Bombs_Hit >0)
					{
						$logs_deg="";
						if(!$Bombs_Hit_Formation)$Bombs_Hit_Formation=$Bombs_Hit;							
						if($ArmeAvion ==800)
							$res_d=$def_c;
						else
							$res_d=$def_c*4;
						for($i=1;$i<=$Bombs_Hit_Formation;$i++)
						{
							if($ArmeAvion >$res_d)
								$Degats+=(mt_rand(1,$ArmeAvion)*$mult_deg);
							else
								$Degats+=1;
							//$logs_deg.=$Degats.' + ';
							if($Admin ==1)
								$skills.="<br> Dégâts=".$Degats." (ArmeAvion ".$ArmeAvion." * Mult_deg ".$mult_deg.") / Def=".$res_d;
						}
					}
					else
						$msghit.="<p>Votre bombardement manque de précision!</p>";
					if($Admin)
						$Degats=$HP_eni+1;
					elseif($Degats <1)
						$Degats=mt_rand(1,10);
					$HP_eni-=$Degats;
					/*if($HP_eni >4000)mail('binote@hotmail.com','Aube des Aigles: Bombardement',"Joueur : ".$Pilote_pvp." / Lieu : ".$Cible." / Cible : ".$nom_c." / Dégâts : ".$Degats."/".$HP_eni." (".$logs_deg.") / Rep_Pts : ".$rep_pts." / Bombe : ".$ArmeAvion." kg ( Type ".$Avion_BombeT.") / Blindage (def*4) : ".$res_d." / Mult_Deg : ".$mult_deg." // ".$msg_hit);*/
					if($HP_eni <1 and $Simu)
					{
						$Faction_db=GetFactionDB($Faction);
						UpdateData("Battle_score",$Faction_db,$rep_c,"ID",$Battle);
						$msghit='<p>'.$msg_hit.' , occasionnant '.round($Degats).' dégâts. Votre objectif est détruit!</p>';
						if($Mission_Type ==2)
						{
							UpdateData("Pilote_PVP","Dive",1,"ID",$Pilote_pvp);
							UpdateData("Pilote_PVP","Points",$rep_c,"ID",$Pilote_pvp);
							SetData("Regiment_PVP","Visible",0,"ID",$Regi);
						}
						else
						{
							if($Nuit)
								$img="<img src='images/explosion_nuit.jpg' style='width:100%;'>";
							else
								$img="<img src='images/explosion2.jpg' style='width:100%;'>";
							if($dca)
								$msghit.="<br>Votre bombardement détruit le canon anti-aérien!";
							elseif($gare)
								$msghit.="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
							elseif($usine)
								$msghit.="<br>Votre attaque diminue le potentiel de production de l'ennemi!";
							elseif($caserne)
								$msghit.="<br>Votre attaque diminue le moral des troupes de l'ennemi!";
							elseif($pont)
							{
								if($Damage >99)
									$msghit.="<br>Le pont est totalement détruit!";
								else
									$msghit.="<br>Le pont est endommagé!";
							}
							elseif($port)
								$msghit.="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
							elseif($radar)
								$msghit.="<br>Votre attaque diminue le potentiel de détection de l'ennemi!";
							elseif($tour)
								$msghit.="<br>Votre attaque diminue les capacités d'organisation de l'aérodrome!";
							elseif($hangar)
								$msghit="<br>Votre bombe détruit un hangar, réduisant les stocks de l'ennemi!";
							elseif($depot)
								$msghit="<br>Votre bombe détruit un entrepôt, réduisant les stocks de l'ennemi!";
							elseif($piste)
								$msghit="<br>Votre bombe endommage la piste!";
							elseif($citerne)
								$msghit='<p>Votre tir fait mouche, vous détruisez '.$nom_c.'</p>';
							elseif($camion)
								$msghit='<p>Votre tir fait mouche, vous détruisez '.$nom_c.'</p>';
							elseif($Cible_Atk ==3 and $tank ==48)
							{
								$Kills=floor($Degats/100);
								if($Kills >100)
									$msghit="<p>Votre bombardement dévastateur met hors de combat des dizaines de soldats de la garnison</p>";
								elseif($Kills >50)
									$msghit="<p>Votre bombardement précis met hors de combat une bonne partie des soldats de la garnison</p>";
								elseif($Kills >10)
									$msghit="<p>Votre bombardement met hors de combat quelques soldats de la garnison</p>";
								else
									$msghit="<p>Votre bombardement imprécis n'entame pas la détermination de la garnison</p>";
							}
							UpdateData("Pilote_PVP","Raids_Bomb",1,"ID",$Pilote_pvp);
							UpdateData("Pilote_PVP","Points",$rep_c,"ID",$Pilote_pvp);
						}
						if($Cible_Atk >9)
							$img=Afficher_Image('images/explosion_navire'.$type_c.'.jpg',"images/image.png","Explosion Navire");
						$seconde_passe=true;
					}
					else
					{
						$msghit="<p>L'explosion, occasionnant ".round($Degats)." dégâts, n'a pas détruit la cible!</p>";
						if(!$img)$img=Afficher_Image('images/cibles/cibles'.$tank.'_'.$Pays_cible.'.jpg','images/cibles/cibles'.$tank.'.jpg',$nom_c);
						$seconde_passe=true;
					}	
				}				
			}
			else
			{
				if($Mission_Type ==21)
				{
					$msghit="<p>Votre fusée n'illumine pas le bon objectif! Votre marquage a manqué de précision!</p>";
					$img="<img src='images/pathfinder.jpg' style='width:100%;'>";
				}
				elseif($Mission_Type ==24 or $Mission_Type ==25 or $Mission_Type ==27)
				{
					$msghit="<p>Vous n'êtes pas aligné au-dessus de la zone de largage!</p>";
					$img="<img src='images/pathfinder.jpg' style='width:100%;'>";	
				}
				else
				{
					if($ArmeAvion ==800)
						$Arme_txt="torpille";
					elseif($ArmeAvion ==300)
						$Arme_txt="charge";
					elseif($ArmeAvion ==400)
						$Arme_txt="mine";
					else
						$Arme_txt="bombe";
					if($Shoot <-100)
						$msghit="<p>Votre ".$Arme_txt." explose très loin à côté de la cible. Votre bombardement est totalement manqué!</p>";
					elseif($Shoot <-50)
						$msghit="<p>Votre ".$Arme_txt." explose à côté de la cible. Votre bombardement a manqué de précision!</p>";
					else
						$msghit="<p>Votre ".$Arme_txt." explose juste à côté de la cible. Quel manque de chance!</p>";
					$img="<img src='images/bomb_miss.jpg' style='width:100%;'>";
				}
				$seconde_passe=true;
			}
			$intro.=$msghit;
		}
		else
		{
			if(!$end_mission and $Mission_Type >10)
			{
				$intro.="<br>Les conditions ne vous permettent pas de poursuivre l'attaque!";
				$img="<img src='images/compte_naval.jpg' style='width:100%;'>";
				$seconde_passe=true;
			}
		}			
	}
	else
	{
		$Equipage_Nom=GetData("Equipage_PVP","ID",$Equipage,"Nom");
		$intro.="<br>".$Equipage_Nom." refuse d'obéir aux ordres, il ".$Etat_Eq;
		$img="<img src='images/demoralise.jpg' style='width:100%;'>";
		$seconde_passe=true;
	}	
	$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps,true);	
	$con=dbconnecti();
	$reset=mysqli_query($con,"UPDATE Pilote_PVP SET S_Avion_Bombe_Nbr='$Bombs',S_Essence='$essence' WHERE ID='$Pilote_pvp'");
	mysqli_close($con);
	if(!$end_mission)
	{
		if($seconde_passe)
		{
			UpdateData("Pilote_PVP","S_Pass",1,"ID",$Pilote_pvp);
			if(!$img)$img="<img src='images/hidden.jpg' style='width:100%;'>";
			if($Strike)
				$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,8,true);
			else
				$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,1,true);
			$choix3="";
			$choix6="";
			if(!$Eni_PvP and $Lieu_PvP !=$Cible)
			{
				if($Mission_Type ==24 or $Mission_Type ==25 or $Mission_Type ==27)
					$choix3="<Input type='Radio' name='Action' value='3'>- Tenter un passage supplémentaire.<br>";
				elseif($Mission_Type == 8 or $Mission_Type ==16 or $Mission_Type ==21)
				{
					if(!$Strike)
						$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,0);
					$choix3="<Input type='Radio' name='Action' value='2'>- Tenter une nouvelle passe.<br>";
					if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
						$choix6="<Input type='Radio' name='Action' value='5'>- Ordonner à votre équipage de tenter une nouvelle passe.<br>";
				}
				else
				{
					$choix3="<Input type='Radio' name='Action' value='3'>- Tenter une passe supplémentaire. (Il vous reste ".$Bombs." Bombe(s))<br>";
					if($Equipage and $Endu_Eq and $Equipage_Nbr > 1)
						$choix6="<Input type='Radio' name='Action' value='6'>- Ordonner à votre équipage de tenter une passe supplémentaire. (Il vous reste ".$Bombs." Bombe(s))<br>";
				}
			}				
			$mes.="<form action='index.php?view=bomb_pvp' method='post'>
			<input type='hidden' name='Avion' value=".$avion.">
			<input type='hidden' name='Meteo' value=".$meteo.">
			<input type='hidden' name='Mun1' value=".$Mun1.">
			<input type='hidden' name='Mun2' value=".$Mun2.">
			<input type='hidden' name='Puissance' value=".$Puissance.">
			<input type='hidden' name='HP_eni' value=".$HP_eni.">
			<input type='hidden' name='Pays_eni' value=".$Pays_cible.">
			<input type='hidden' name='Battle' value=".$Battle.">
			<input type='hidden' name='Camp' value=".$Faction.">
			".$gaz_txt."<table class='table'><thead><tr><th colspan='8'>Seconde passe</th></tr></thead>
			<tr><td align='left'>".$choix3.$choix6.$choix_pvp."</td></tr></table>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";		
		}
		if($retour)
		{
			Chemin_Retour();
			$chemin=$Distance;
			$intro.="<p>Vous prenez le chemin du retour en direction de votre base, située à ".$Distance."km</p>";
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
			<table class='table'>
				<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,false,true)."</tr></table>
			<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}
	elseif($end_mission)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Unite_Intercept=0,Escorte=0,Couverture=0,Points=Points-1,Abattu=Abattu+1,Avion_Sandbox=0,S_HP=0 WHERE ID='$Pilote_pvp'");
		mysqli_close($con);
		RetireCandidatPVP($Pilote_pvp,"end_mission");
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