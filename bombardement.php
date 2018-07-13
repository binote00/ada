<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
/*if($PlayerID ==1 or $PlayerID ==2)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
$Action=Insec($_POST['Action']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$ArmeAvion=Insec($_POST['ArmeAvion']);
$ArmeAvion_nbr=Insec($_POST['ArmeAvion_nbr']);
$Mun=Insec($_POST['Mun']);
$Pays_cible=Insec($_POST['Pays_eni']);
$Deleguer=Insec($_POST['Deleguer']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$PlayerID=$_SESSION['PlayerID'];
//Check Joueur Valide
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $_SESSION['bombarder'] ==false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	$_SESSION['cibler']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['bombarder']=true;
	$Distance=$_SESSION['Distance'];
	$country=$_SESSION['country'];
	$BH_Lieu=$_SESSION['BH_Lieu'];	
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
	$recce_tac=false;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Front,Courage,Moral,Pilotage,Bombardement,Vue,Equipage,S_Avion_db,S_Nuit,S_Cible,S_Mission,S_Cible_Atk,S_Strike,S_Pass,S_Engine_Nbr,S_Blindage,
	S_Longitude,S_Latitude,S_Essence,Simu,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Avion_BombeT,S_Equipage_Nbr,S_Formation,Slot5,Slot10,Slot11,Admin FROM Pilote WHERE ID='$PlayerID'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard-player');
	$resultac=mysqli_query($con,"SELECT Officier FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
	$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
	mysqli_close($con);
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
			$Front=$data['Front'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Pilotage=$data['Pilotage'];
			$Bombardement=$data['Bombardement'];
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
	if($Officier >0)
		$Lieu_Reg_Off=GetData("Regiment","Officier_ID",$Officier,"Lieu_ID");
	if($Pilotage >50)$Pilotage=50;
	if($Bombardement >50)$Bombardement=50;
	if($Vue >50)$Vue=50;
	$Steady=1;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(32,$Skills_Pil) and $Strike)
			$Point_repere=50;
		if(in_array(39,$Skills_Pil))
			$Steady=1.1;
		if(in_array(40,$Skills_Pil))
			$AsPique=50;
		if(in_array(41,$Skills_Pil))
			$AsZigZag=50;
		if(in_array(47,$Skills_Pil))
			$TEquipe=true;
		if(in_array(50,$Skills_Pil))
			$Bonne_Etoile=true;
		if(in_array(129,$Skills_Pil))
			$Torpilleur=25;
		if(in_array(51,$Skills_Pil))
			$Combat_Box=true;
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
		$result2=mysqli_query($con,"SELECT Nom,Meteo,DefenseAA_temp FROM Lieu WHERE ID='$Cible'");
		$result=mysqli_query($con,"SELECT Type,Robustesse,Blindage,Volets,Moteur,Viseur,Masse,Radar FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard-avion');
		mysqli_close($con);
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Cible_nom=$data2['Nom'];
				$meteo=$data2['Meteo'];
				$DefenseAA=$data2['DefenseAA_temp'];
			}
			mysqli_free_result($result2);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Type_Avion=$data['Type'];
				$HPmax=$data['Robustesse'];
				$Blindage=$data['Blindage'];
				$Volets=$data['Volets'];
				$Moteur=$data['Moteur'];
				$Viseur=$data['Viseur'];
				$Masse=$data['Masse'];
				$Radar=$data['Radar'];
			}
			mysqli_free_result($result);
		}
		if(!$Blindage)
		{
			$Blindage=$S_Blindage;
			if(!$Blindage)$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
		}
		//Meteo nécessaire ici
		if($Nuit)$meteo-=85;
		if($Equipage)$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");		
		if($Equipage and $Endu_Eq >0 and $Deleguer and !strrpos($Action,"66"))
		{
			$con=dbconnecti();		
			$result=mysqli_query($con,"SELECT Courage,Moral,Bombardement,Trait FROM Equipage WHERE ID='$Equipage'");		
			mysqli_close($con);		
			if($result)		
			{		
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))	
				{	
					$Courage=$data['Courage'];
					$Moral=$data['Moral'];
					$Bombardement2=$data['Bombardement'];
					$Trait_e=$data['Trait'];
				}	
				mysqli_free_result($result);	
			}
			if($Trait_e ==1)
				$Bombardement2*=1.1;
			elseif($Trait_e ==8 and $Moral <100)
				$Moral=100;
			elseif($Trait_e ==2 and $Courage <100)
				$Courage_Eq=100;
			if($Courage <1 and $Trait_e !=6)
			{
				$go_shoot=false;
				$Etat_Eq=" est tétanisé par la peur";
				$Bombardement2=0;
			}
			if($Moral <1 and $Trait_e !=6)
			{
				$go_shoot=false;
				$Etat_Eq=" est démoralisé";
				$Bombardement2=0;
			}
			if($TEquipe)
				$Bombardement+=$Bombardement2;
			else
				$Bombardement=$Bombardement2;
		}
		$moda=$HPmax/$HP;
		if($Avion_db =="Avion" and $Bombs >0 and $Avion_Bombe)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Bombs));
			$moda*=(1+$charge_sup);
		}
		$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,0,$PlayerID,$Unite);
		$Speed=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$SpeedP=GetSpeedP($Avion_db,$avion,$Engine_Nbr,$c_gaz,$flaps);
		$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,0,$Pilotage);
	}
	else
		$Action=98;
	if($Speed <50)$Action=98;
	if(!$Bombs)$Action=97;	
	$avion_img=GetAvionImg($Avion_db,$avion);
	$choix_pvp="<Input type='Radio' name='Action' value='99' checked>- Rentrer à la base.<br>";
	$Eni_PvP=GetData("Duels_Candidats","Target",$PlayerID,"ID");
	$Lieu_PvP=GetData("Duels_Candidats","Target",$PlayerID,"Lieu");
	//Boost
	if($c_gaz ==130)
		UpdateData("Pilote","Stress_Moteur",10,"ID",$PlayerID);
	if($Cible ==$Lieu_Reg_Off1 or $Cible ==$Lieu_Reg_Off2)
	{
		$intro.="<br>Votre pilote ne peut pas effectuer de mission sur le lieu où se trouve votre officier!";
		$end_mission=true;
	}
	elseif($Action ==98)
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
		if(!GetData("Duels_Candidats","PlayerID",$PlayerID,"ID"))
			AddCandidat($Avion_db,$PlayerID,$avion,$HP,$Puissance,$essence,$chemin,$Distance,$Mun1,$Mun2,$alt,$Cible,$Nuit);
		$choix_pvp="<Input type='Radio' name='Action' value='90' checked>- Affronter votre adversaire.<br>";
		$seconde_passe=true;
	}
	elseif($Action ==97)
	{
		$intro.="<br>Vous n'avez pas de bombes!";
		$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg', 'images/avions/vol'.$avion_img.'.jpg', $nom_avion);
		UpdateCarac($PlayerID,"Reputation",-1);
		$retour=true;
	}
	elseif($Action ==5)
	{
		$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg', 'images/avions/vol'.$avion_img.'.jpg', $nom_avion);
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
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result=mysqli_query($con,"SELECT Latitude,Longitude,Zone,ValeurStrat,Camouflage,Garnison,Recce,Fortification,Flag FROM Lieu WHERE ID='$Cible'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard-cible');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
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
			mysqli_free_result($result);
			unset($data);
		}				
		if($Mission_Type ==24 or $Mission_Type ==25 or $Mission_Type ==27 or $Mission_Type ==14 or $Mission_Type ==101)
		{
			$arme_c=5;
			$def_c=0;
			$tank=999;
			if($Mission_Type ==101)
			{
				$Bonus_Training=50;
				$hp_c=50;
			}
		}
		elseif($Mission_Type ==21)
		{
			$arme_c=15;
			$def_c=0;
			$tank=999;
		}
		elseif($Mission_Type ==2)
		{
			if(strpos($Action, "000_") !==false)
			{
				$Regi=strstr($Action,'000_',true);
				$RR="Vehicule_Nbr";
				$DB="Regiment";
			}
			elseif(strpos($Action, "000ia") !==false)
			{
				$Regi=strstr($Action,'000ia',true);
				$RR="Vehicule_Nbr_ia";
				$DB="Regiment_IA";
			}
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Officier_ID,Vehicule_ID,Experience,Camouflage,Placement,Position,Skill FROM $DB WHERE ID='$Regi'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard-reg');
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
					$Skill_eni=$data['Skill'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($tank)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,Type,Blindage_f,Blindage_t,Blindage_l,Blindage_a,Arme_AA,HP,Reput,mobile,Taille,Camouflage,Flak FROM Cible WHERE ID='$tank'")
				 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard-tank');
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
			if(strpos($Action,"000_") !==false)
			{
				$Regi=strstr($Action,'000_',true);
				$RR="Vehicule_Nbr";
				$DB="Regiment";
			}
			elseif(strpos($Action,"000ia") !==false)
			{
				$Regi=strstr($Action,'000ia',true);
				$RR="Vehicule_Nbr_ia";
				$DB="Regiment_IA";
			}
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Vehicule_ID,Experience,HP FROM $DB WHERE ID='$Regi'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bombard-reg');
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$tank=$data['Vehicule_ID'];
					$dca_unit_skill=$data['Experience'];
					$hp_c=$data['HP'];
				}
				mysqli_free_result($result);
				unset($data);
			}
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
						$nom_c ='un '.$data['Nom'];
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
				$Target_id="cibles".$tank;
			}
		}
		else
		{
			if($DefenseAA >($ValStrat*2)+2)$DefenseAA=($ValStrat*2)+2;
			if($Cible_Atk ==1)
			{				
				$dca_unit_skill=0;
				if(strpos($Action, '99_') !==false) //Vérifie si la cible est une DCA, extrait l'ID de la pièce
				{
					$arme_c=substr($Action,3);
					$Action=2;
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT ID,DCA_Exp,Unit FROM Flak WHERE Lieu='$Cible' AND DCA_ID='$arme_c' ORDER BY RAND() LIMIT 1");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result))
						{
							$dca_unit_skill=$data['DCA_Exp'];
							$DCA_Unit_ID=$data['Unit'];
							$DCA_ID=$data['ID'];
						}
						mysqli_free_result($result);
					}
				}
				else
				{
					$Alt_Flak_min=$alt-1000;
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
					}
				}							
				if(!$dca_unit_skill)$arme_c=0;
				$cam_c=$Camouflage_lieu;				
				/*	$con=dbconnecti();
					$result=mysqli_query($con,"SELECT SUM(DCA),SUM(Pers1) FROM Unit WHERE Base='$Cible'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_NUM))
						{
							$DCA=$data[0];
							$Artilleurs=$data[1];
						}
						mysqli_free_result($result);
					}
					if($DCA > 59)
						$arme_c=15;
					elseif($DCA < 60)
						$arme_c=14;
					elseif($DCA < 50)
						$arme_c=23;
					elseif($DCA < 40)
						$arme_c=8;
					elseif($DCA < 30)
						$arme_c=3;
					elseif($DCA < 20)
						$arme_c=17;
					else
						$arme_c=13;
					$dca_unit_skill=$DCA+($Artilleurs*10);
				}*/
				switch($Action)
				{
					case 1:
						$nom_c="la piste";
						$def_c=20;
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
						//$arme_c=15;
						$hp_c=500;
						$rep_c=7;
						$cam_c += 10;
						$type_c=12;
						$dca_unit=true;
						$tank=16;
					break;
					case 3:
						$nom_c="un hangar";
						$def_c=5;
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
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
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=10000+(10000*$ValStrat);
						$rep_c=30;
						$cam_c=20;
						$radar=true;
						$tank=14;
					break;
					case 4:
						$nom_c="une antenne";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
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
					if($RR =="Vehicule_Nbr" and $Regi)
						$DCA_mun=GetData("Regiment","ID",$Regi,"Stock_Munitions_".$dca_cal);
					else
						$DCA_mun=9999;			
					if($DCA_mun >=$dca_mult)
					{
						if($RR =="Vehicule_Nbr" and $Regi)
						{
							UpdateData("Regiment","Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$Regi);
							if($i ==$Nbr_Tir)
							{
								$muns_used=$dca_mult*$Nbr_Tir;
								AddEventGround(376,$avion,$PlayerID,$Regi,$Cible,$muns_used,$arme_c);
							}
						}
						if($i ==$Nbr_Tir)
						{
							$intro.="<br><b>La défense anti-aérienne rapprochée ouvre le feu sur vous!</b>";
							if($Target_id)
								$img=Afficher_Image('images/cibles/'.$Target_id.'.jpg',"images/image.png",$nom_c);
							else
								$img=Afficher_Image('images/cibles/cibles'.$tank.'_'.$Pays_cible.'.jpg','images/cibles/cibles'.$tank.'.jpg',$nom_c);
						}
						/*$dca_max=$rep_c*10;
						if($dca_max >250)$dca_max =250;	
						$Shoot_rand=mt_rand(10,50)+mt_rand($DCA,$dca_unit_skill)+mt_rand($rep_c,$dca_max);*/
						$Shoot_rand=mt_rand(0,$dca_unit_skill);
						if($S_Pass)
							$meteo_malus=0;
						else
							$meteo_malus=$meteo;				
						if($alt <5000)
							$Shoot_rand+=((5000-$alt)/50);
						if($alt <1001 and ($Type_avion ==2 or $Type_avion ==11))
							$VisAvion=$VisAvion/(($alt/100)/5);
						if($Action ==66) //Bomb en Piqué
							$Shoot=$Shoot_rand+$meteo_malus+($VisAvion/10)-($Pilotage/2)-($SpeedP/5)+($S_Pass*25)+$dca_mult-$AsPique-$AsZigZag;
						elseif($Mission_Type ==13)
							$Shoot=$Shoot_rand+$meteo_malus+($VisAvion/5)-($Pilotage/2)-($Speed/10)+($S_Pass*25)+$dca_mult-$AsZigZag;
						else
						{
							$Shoot=$Shoot_rand+$meteo_malus+$VisAvion-($Pilotage/2)-($Speed/10)+($S_Pass*25)+$dca_mult-$AsZigZag;
							/*if($S_Pass >0)
							{
								$headers='MIME-Version: 1.0' . "\r\n";
								$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
								$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo."</p>";
								$msgm.="<br>[Score de Tir : ".$Shoot."]
													<br>+Vis ".$VisAvion."
													<br>+Meteo ".$meteo_malus."
													<br>-Speed ".$Speed." /10
													<br>-Pilotage ".$Pilotage." /2
													<br>-S_Pass ".$S_Pass." *25
													<br>+Tir_eni :".$Shoot_rand."</body></html>";
								mail('binote@hotmail.com','Aube des Aigles: DCA Bomb Log',$msgm, $headers);
							}*/
						}
						//JF
						if($PlayerID ==1 or $PlayerID ==2)
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
							if($RR =="Vehicule_Nbr" and $Regi)
								AddEventGround(377,$avion_img,$PlayerID,$Regi,$Cible,$Unite,$tank);
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
								$CritH=CriticalHit($Avion_db,$avion,$PlayerID,2,$Engine_Nbr); //Todo : Remplacer 2 par type de munition
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
							//HP Avion perso persistant
							if($Avion_db =="Avions_Persos")
							{
								if($HP <1)$HP=0;
								SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
							}
							if($HP <1)
							{
								$intro.='<br>Un obus frappe votre appareil de plein fouet. L\'explosion met me feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
								if($Avion_Bombe ==100 and $Bombs ==10)
								{
									$con=dbconnecti();
									$reset=mysqli_query($con,"UPDATE Pilote SET S_Cible_Atk=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$PlayerID'");
									$reset3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=0,Vehicule_Nbr=0,Moral=0 WHERE Officier_ID='$Cible_Atk'");
									mysqli_close($con);
									$intro.="<br>Le bataillon complet de parachutistes a été perdu!";
								}
								$end_mission=true;
								break;
							}
							else
							{
								$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
								if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
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
								$reperage=true;
							}
							SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
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
				$Radar=GetData($Avion_db,"ID",$avion,"Radar");
				$reperer=mt_rand(0,50)+($Radar*10)+$meteo-$cam_c;
				if($reperer >0)
					UpdateCarac($Equipage,"Radar",1,"Equipage");
			}
			else
				$reperer=1;
			if($reperer >0)
			{
				$car_up=mt_rand(0,1);
				UpdateCarac($Equipage,"Vue",$car_up,"Equipage");
				/*if($Strike ==false)UpdateCarac($PlayerID,"Vue",$car_up);*/
				$attaque=true;
			}
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
			$Stab=GetStab($Avion_db,$avion,$HP)*$Steady;
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
				{
					$Bonus_pique=35;
					UpdateCarac($PlayerID,"Reputation",1);
				}
				else
					$Bonus_pique=10;
				$Shoot=mt_rand(0,$Bombardement)+($meteo/2)+($Courage/5)+($Moral/10)-$def_c-($SpeedP/5)+$Bonus_Viseur+$Bonus_pique+$Bonus_Pathfinder-($Nuit*100)+$Bonus_Training+$Point_repere+$AsPique;
				$debug_msg="Bomb Piqué Joueur ".$PlayerID." (courage/5 + Moral/10)=".$Shoot." (Tir=".$Bombardement." ; Vitesse Piqué/5=-".$SpeedP." ; Météo/2=-".$meteo." ; MalusBonus_Viseur=".$Bonus_Viseur." ; Bonus_pique=".$Bonus_pique." ; Bonus_Pathfinder=".$Bonus_Pathfinder." ; Défense=-".$def_c.")";
				if($debug_msg)mail('binote@hotmail.com','Aube des Aigles: Bomb Piqué Stats',$debug_msg);		
				//JF
				if($PlayerID ==1 or $Admin ==1)
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
					$Torp_shoot=mt_rand(0,$Bombardement)+($Bombardement/10)+$Point_repere+$Torpilleur;
				else
					$Torp_shoot=mt_rand(0,$Bombardement)+$Point_repere+$Torpilleur;
				//Esquive
				if($dca_unit_skill >0)
				{
					$Esquive=mt_rand(1,$dca_unit_skill);
					$Taille/=2;
					if($RR =="Vehicule_Nbr" and $Regi)
					{
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
				if($Skill_eni ==36)
					$Esquive+=10;
				elseif($Skill_eni ==147)
					$Esquive+=15;
				elseif($Skill_eni ==148)
					$Esquive+=20;
				elseif($Skill_eni ==149)
					$Esquive+=25;
				if($cible_position ==26 or $cible_position ==29)$Vitesse=0;
				$Shoot=$Torp_shoot+($Stab/10)+$meteo+($Moral/10)-($alt/10)-$Esquive-$Vitesse+$Taille;
				/*$debug_msg="Torpillage Joueur ".$PlayerID." (Moral/10)=".$Shoot." (Tir=".$Torp_shoot." ; Stabilité/10=".$Stab." ; Météo=-".$meteo." ; Esquive=- ".$Esquive." ; Taille=".$Taille." ; Malus Altitude/10=-".$alt.")";
				if($debug_msg)mail('binote@hotmail.com','Aube des Aigles: Torpillage Stats',$debug_msg);*/
				//JF
				if($PlayerID ==1 or $Admin ==1)
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
						if($Officier_eni >0 and IsSkill(38,$Officier_eni))
							$Esquive+=25;
					}
					if($Skill_eni ==36)
						$Esquive+=10;
					elseif($Skill_eni ==147)
						$Esquive+=15;
					elseif($Skill_eni ==148)
						$Esquive+=20;
					elseif($Skill_eni ==149)
						$Esquive+=25;
				}
				$rand_tir=mt_rand(0,$Bombardement);
				$Shoot=$rand_tir+($Stab/10)+$meteo+($Courage/10)+($Moral/10)-$Esquive+($Bonus_Viseur*5)-($alt/100)+$Bonus_Training+$Point_repere;
				if($Mission_Type ==8 and $Combat_Box and $rand_tir >0 and $Shoot <1)$Shoot=1;
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
            $rep_pts=$rep_c;
			//Bomb Tapis
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
				$rep_pts=floor($rep_c*(($ValStrat+2)/2));
				$mult_deg*=1.25;
			}
			else
			{
				if($ArmeAvion >500)$ArmeAvion=500;
				if($Action ==66)$Bombs_Hit*=2;
				$Bombs-=1;
				$msg_hit="Votre bombe explose";
				//Sable
				if($Avion_db =="Avions_Persos")
				{
					if($Zone ==8 and $Moteur !=7)
					{
						$Stress=floor($c_gaz/5);
						UpdateData("Pilote","Stress_Moteur",$Stress,"ID",$PlayerID);
						$intro.="<p>Du sable encrasse votre moteur!</p>";
					}
				}
			}			
			if($Mission_Type ==101)
			{
				$obj_train=mt_rand(5,10);
				if($Bombardement <50)
				{
					//UpdateCarac($PlayerID,"Bombardement",$obj_train);
					UpdateCarac($PlayerID,"Reputation",$obj_train);
				}
				if($Bombardement >10)
				{
					if($Viseur <3)
						$intro.="<p>Vous constatez que le modèle d'avion que vous pilotez ne possède pas de viseur de bombardement.</p>";
					else
						$intro.="<p>Vous constatez que le modèle d'avion que vous pilotez possède un viseur spécifique pour le bombardement.</p>";
				}
				//if($Vue <50)UpdateCarac($PlayerID,"Vue",$obj_train);
				//Equipage
				if($Equipage)
				{
					if($Trait_e ==4)$obj_train*=2;
					$Bombardement_Eq=GetData("Equipage","ID",$Equipage,"Bombardement");
					$Vue_Eq=GetData("Equipage","ID",$Equipage,"Vue");
					if($Bombardement_Eq <50)
						UpdateCarac($Equipage,"Bombardement",$obj_train,"Equipage");
					if($Vue_Eq <50)
						UpdateCarac($Equipage,"Vue",$obj_train,"Equipage");
				}
			}
			if(date("H")<7) //pas d'attaque canadienne
				if(mt_rand(0,100)>10)$Shoot=0;
			if($Shoot >0 and $rand_tir >10)
			{
				if($Mission_Type ==24 or $Mission_Type ==25 or $Mission_Type ==27)
				{
					//$rep_c=floor(($rep_c/2)*(($ValStrat+2)/2));
					$rep_c=floor((($ValStrat+2)/2)*10);
					if(IsWar($Flag,$country))
					{
						if($Simu)
						{
							//Mission_Historique
							if($Cible ==$BH_Lieu)
							{
								if(IsAxe($country))
									$Points_cat="Points_Axe";
								else
									$Points_cat="Points_Allies";
								UpdateData("Event_Historique",$Points_cat,$rep_c,"ID",$_SESSION['BH_ID']);
							}
							if($Deleguer)UpdateCarac($Equipage,"Radio",1,"Equipage");
							//Doubler la récompense en cas de bataille historique
							if($Cible == $BH_Lieu)
							{
								$Pts_Bonus=2;
								UpdateCarac($PlayerID,"Batailles_Histo",1);
							}
							else
								$Pts_Bonus=1;
							//Bonus Unité/E-M
							$rep_c*=$Pts_Bonus;
							/*if($Cible ==GetData("Unit","ID",$Unite,"Mission_Lieu"))
							{
								UpdateData("Unit","Reputation",$rep_c,"ID",$Unite);
								$Cdt=GetData("Unit","ID",$Unite,"Commandant");
								if($Cdt)
								{
									UpdateCarac($Cdt,"Reputation",10);
									UpdateCarac($Cdt,"Avancement",5);
									UpdateCarac($Cdt,"Commandement",5);
								}
							}
							else*/if($Cible ==GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unite,"Type")))
							{
								$Cdt=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
								if($Cdt)
								{
									UpdateCarac($Cdt,"Reputation",5);
									UpdateCarac($Cdt,"Avancement",10);
								}
								UpdateData("Unit","Reputation",$rep_c,"ID",$Unite,0,7);
							}
							UpdateCarac($PlayerID,"Missions",$rep_c);
							UpdateCarac($PlayerID,"Reputation",$rep_c);
							UpdateCarac($PlayerID,"Avancement",$rep_c);
							if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
							{
								UpdateCarac($Equipage,"Missions",1,"Equipage");
								UpdateCarac($Equipage,"Avancement",20,"Equipage");
								UpdateCarac($Equipage,"Reputation",25,"Equipage");
								UpdateCarac($Equipage,"Moral",10,"Equipage");
							}
						}
						if($Mission_Type ==27)
						{
							$Cdo=GetData("Pilote","ID",$Cible_Atk,"Nom");
							$intro.='<p><b>Vous larguez '.$Cdo.' sur l\'objectif.<br>Vous avez accompli votre mission!</b></p>';
							SetData("Pilote","MIA",$Cible,"ID",$Cible_Atk);
							SetData("Pilote","S_Cible_Atk",0,"ID",$PlayerID);
						}
						else
						{
							$Cdo=GetData("Pilote","ID",$Cible_Atk,"Nom");
							$intro.='<p><b>Vous larguez '.$Bombs.' parachutistes sous le commandement de <b>'.$Cdo.'</b> sur l\'objectif.<br>Vous avez accompli votre mission!</b></p>';
							AddPara($Avion_db,$avion,$PlayerID,$Unite,$Cible,$Bombs,$Nuit);
							$today=getdate();
							$Heure=$today['hours'];
							$con=dbconnecti();
							$reset=mysqli_query($con,"UPDATE Pilote SET S_Cible_Atk=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$PlayerID'");
							$reset2=mysqli_query($con,"UPDATE Officier SET Para_Lieu=0,Mission_Lieu_D=0,Mission_Type_D=0,Credits=0,Heure_Para='$Heure',Avancement=Avancement+10 WHERE ID='$Cible_Atk'");
							$reset3=mysqli_query($con,"UPDATE Regiment SET Placement=0,Position=2,Lieu_ID='$Cible',Visible=1,Experience=Experience+10,Moral=Moral+10 WHERE Officier_ID='$Cible_Atk'");
							mysqli_close($con);
						}
						SetData("Pilote","S_Strike",1,"ID",$PlayerID);
					}
					else
					{
						$msghit="<p>Vous attaquez des cibles alliées!</p>";
						/*if($Cible == GetData("Unit","ID",$Unite,"Mission_Lieu"))
						{
							$Cdt=GetData("Unit","ID",$Unite,"Commandant");
							if($Cdt)
							{
								UpdateCarac($Cdt,"Reputation",-10);
								UpdateCarac($Cdt,"Avancement",-5);
							}
							UpdateData("Unit","Reputation",-10,"ID",$Unite);
						}*/
						UpdateCarac($PlayerID,"Reputation",-10);
						UpdateCarac($PlayerID,"Avancement",-25);
					}
					$img="<img src='images/parachutage.jpg' style='width:100%;'>";	
					$Bombs=0;
					$retour=true;
				}
				elseif($Mission_Type ==14)
				{
					$img="<img src='images/mouillage_mine.jpg' style='width:100%;'>";
					if($Strike ==false)
					{
						$rep_c=10;
						//Doubler la récompense en cas de mission demandée
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT ID FROM Unit WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D=14");
						//$result2=mysqli_query($con,"SELECT ID FROM Officier WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D=14");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								UpdateData("Unit","Reputation",$rep_pts,"ID",$data['ID'],0,8);
							}
							mysqli_free_result($result);
							UpdateData("Unit","Reputation",$rep_pts,"ID",$Unite,0,8);
							UpdateCarac($PlayerID,"Note",1);
							$Pts_Bonus+=1;
						}
						/*if($result2)
						{
							while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								UpdateData("Officier","Reputation",$rep_pts,"ID",$data2['ID']);
								UpdateData("Officier","Avancement",$rep_pts,"ID",$data2['ID']);
							}
							mysqli_free_result($result2);
							UpdateData("Unit","Reputation",$rep_pts,"ID",$Unite);
							UpdateCarac($PlayerID,"Note",1);
							$Pts_Bonus+=1;
						}*/
						if($Pts_Bonus)
						{
							UpdateCarac($PlayerID,"Missions",$rep_c*$Pts_Bonus);
							UpdateCarac($PlayerID,"Reputation",$rep_c*$Pts_Bonus);
							UpdateCarac($PlayerID,"Avancement",$rep_c*$Pts_Bonus);
							if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
							{
								UpdateCarac($Equipage,"Missions",1,"Equipage");
								UpdateCarac($Equipage,"Avancement",20,"Equipage");
								UpdateCarac($Equipage,"Reputation",25,"Equipage");
								UpdateCarac($Equipage,"Moral",10,"Equipage");
							}
						}
						UpdateData("Lieu","Mines_m",2,"ID",$Cible);
						SetData("Pilote","S_Strike",1,"ID",$PlayerID);
					}
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
							//Mission_Historique
							if($Cible ==$BH_Lieu)
							{
								if(IsAxe($country))
									$Points_cat="Points_Axe";
								else
									$Points_cat="Points_Allies";
								UpdateData("Event_Historique",$Points_cat,$rep_c,"ID",$_SESSION['BH_ID']);
							}
							if($Deleguer)
								UpdateCarac($Equipage,"Radio",1,"Equipage");
							else
								UpdateCarac($PlayerID,"Reputation",$Bombs_Hit); //UpdateCarac($PlayerID,"Bombardement",$Bombs_Hit);
							UpdateCarac($PlayerID,"Reputation",$rep_c);
							UpdateCarac($PlayerID,"Avancement",$rep_c);
							UpdateCarac($PlayerID,"Moral",10);
							if($Strike ==false)
							{
								//Doubler la récompense en cas de bataille historique
								if($Cible ==$BH_Lieu)
								{
									$Pts_Bonus=2;
									UpdateCarac($PlayerID,"Batailles_Histo",1);
								}
								else
									$Pts_Bonus=1;
								//Seules les cibles importantes valident la mission
								if($rep_c >1)
								{
									//Doubler la récompense en cas de mission demandée
									$con=dbconnecti();
									$result=mysqli_query($con,"SELECT ID FROM Unit WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D IN (2,8,12,13,16)");
									//$result2=mysqli_query($con,"SELECT ID FROM Officier WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D IN (2,8,12,13,16)");
									mysqli_close($con);
									if($result)
									{
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
										{
											UpdateData("Unit","Reputation",$rep_pts,"ID",$data['ID'],0,8);
										}
										mysqli_free_result($result);
										UpdateData("Unit","Reputation",$rep_pts,"ID",$Unite,0,8);
										UpdateCarac($PlayerID,"Note",1);
										$Pts_Bonus+=1;
									}
									/*if($result2)
									{
										while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
										{
											UpdateData("Officier","Reputation",$rep_pts,"ID",$data2['ID']);
											UpdateData("Officier","Avancement",$rep_pts,"ID",$data2['ID']);
										}
										mysqli_free_result($result2);
										UpdateData("Unit","Reputation",$rep_pts,"ID",$Unite,0,8);
										UpdateCarac($PlayerID,"Note",1);
										$Pts_Bonus+=1;
									}*/
									//Bonus Unité/E-M
									/*if($Cible ==GetData("Unit","ID",$Unite,"Mission_Lieu"))
									{
										UpdateData("Unit","Reputation",$rep_c,"ID",$Unite);
										$Cdt=GetData("Unit","ID",$Unite,"Commandant");
										if($Cdt)
										{
											UpdateCarac($Cdt,"Reputation",10);
											UpdateCarac($Cdt,"Avancement",5);
											UpdateCarac($Cdt,"Commandement",5);
										}
									}
									else*/if($Cible ==GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unite,"Type")))
									{
										$Cdt=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
										if($Cdt)
										{
											UpdateCarac($Cdt,"Reputation",5);
											UpdateCarac($Cdt,"Avancement",10);
										}
										UpdateData("Unit","Reputation",$rep_c,"ID",$Unite,0,8);
									}
									$msghit.="<p><b>Vous avez accompli votre mission!</b></p>";
									UpdateCarac($PlayerID,"Raids_Bomb_Nuit",1);
									UpdateCarac($PlayerID,"Missions",$rep_c*$Pts_Bonus);
									UpdateCarac($PlayerID,"Reputation",$rep_c*$Pts_Bonus);
									UpdateCarac($PlayerID,"Avancement",$rep_c*$Pts_Bonus);
									if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
									{
										UpdateCarac($Equipage,"Missions",1,"Equipage");
										UpdateCarac($Equipage,"Avancement",20,"Equipage");
										UpdateCarac($Equipage,"Reputation",25,"Equipage");
										UpdateCarac($Equipage,"Moral",10,"Equipage");
									}
								}										
								AddRecce($Avion_db,$Cible_nom,$avion,$PlayerID,$Unite,$Cible,2);
								SetData("Lieu","Recce",2,"ID",$Cible);
								SetData("Pilote","S_Strike",1,"ID",$PlayerID);
							}
						}
						else
						{
							$msghit="<p>Vous attaquez des cibles alliées!</p>";
							/*if($Cible ==GetData("Unit","ID",$Unite,"Mission_Lieu"))
							{
								$Cdt=GetData("Unit","ID",$Unite,"Commandant");
								if($Cdt)
								{
									UpdateCarac($Cdt,"Reputation",-10);
									UpdateCarac($Cdt,"Avancement",-5);
								}
								UpdateData("Unit","Reputation",-10,"ID",$Unite);
							}*/
							UpdateCarac($PlayerID,"Reputation",-10);
							UpdateCarac($PlayerID,"Avancement",-25);
						}
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
						if($ArmeAvion ==800 and $Mission_Type ==13)
							mail('binote@hotmail.com','Aube des Aigles: bomb torpillage',"Joueur : ".$PlayerID." / ArmeAvion : ".$ArmeAvion." / mult_deg : ".$mult_deg." / res_d : ".$res_d." / navire : ".$tank." / (Si ArmeAvion > res_d) => Degats : ".$Degats." == rnd(1,".$ArmeAvion.") * ".$mult_deg);
					}
					else
						$msghit.="<p>Votre bombardement manque de précision!</p>";
					/*if($Officier_eni >0 and ($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7))
					{
						if(IsSkill(10,$Officier_eni))
							$Degats=round($Degats/2);
					}*/
					if($Degats <1)$Degats=mt_rand(1,10);
					$HP_eni-=$Degats;
					/*if($HP_eni >4000)mail('binote@hotmail.com','Aube des Aigles: Bombardement',"Joueur : ".$PlayerID." / Lieu : ".$Cible." / Cible : ".$nom_c." / Dégâts : ".$Degats."/".$HP_eni." (".$logs_deg.") / Rep_Pts : ".$rep_pts." / Bombe : ".$ArmeAvion." kg ( Type ".$Avion_BombeT.") / Blindage (def*4) : ".$res_d." / Mult_Deg : ".$mult_deg." // ".$msg_hit);*/
					if($HP_eni <1 and $Simu)
					{
						$msghit='<p>'.$msg_hit.' , occasionnant '.round($Degats).' dégâts. Votre objectif est détruit!</p>';
						if($Cible_Atk >9)
							$img=Afficher_Image('images/explosion_navire'.$type_c.'.jpg',"images/image.png","Explosion Navire");
						else
						{
							if($Nuit)
								$img="<img src='images/explosion_nuit.jpg' style='width:100%;'>";
							else
								$img="<img src='images/explosion2.jpg' style='width:100%;'>";
						}
						if($Mission_Type ==101)
						{
							$msghit.="<br>Votre bombardement détruit la cible d'entrainement!";
							$intro.="<br>Vous larguez votre bombe sous le regard de vos instructeurs.";
							$img="<img src='images/explosion_training.jpg' style='width:100%;'>";
						}
						else
						{
							//Modif Lieu
							$Deg_bonus=$Degats/10000;
							if($Deg_bonus >50)$Deg_bonus=50;
							if($dca_unit)
							{
								if($DCA_Unit_ID and $DCA_ID)
								{
									$DCA_Nbr=GetData("Flak","ID",$DCA_ID,"DCA_Nbr");
									if($DCA_Nbr >1)
										UpdateData("Flak","DCA_Nbr",-1,"ID",$DCA_ID);
									else
										DeleteData("Flak","ID",$DCA_ID);
									AddEvent($Avion_db,23,$avion,$PlayerID,$DCA_Unit_ID,$Cible);
									//UpdateData("Unit","DCA",-1,"ID",$DCA_Unit_ID);
								}
								/*else
								{
									$con=dbconnecti();
									$result=mysqli_query($con,"SELECT DISTINCT ID FROM Unit WHERE Base='$Cible' ORDER BY RAND() LIMIT 1");
									mysqli_close($con);
									if($result)
									{
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
										{
											$Unite_loss=$data['ID'];
											AddEvent($Avion_db,23,$avion,$PlayerID,$Unite_loss,$Cible);
											UpdateData("Unit","DCA",-1,"ID",$Unite_loss);
										}
										mysqli_free_result($result);
									}
								}*/
								//AddEvent($Avion_db,13,$avion,$PlayerID,$Unite,$Cible);
								$msghit.="<br>Votre bombardement détruit le canon anti-aérien!";
								UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
							}
							elseif($dca)
							{
								//AddEvent($Avion_db,13,$avion,$PlayerID,$Unite,$Cible);
								UpdateData("Lieu","DefenseAA_temp",-1,"ID",$Cible);
								$msghit.="<br>Votre bombardement détruit le canon anti-aérien!";
							}
							elseif($gare)
							{
								$Damage=floor(0-($rep_c/5)-$Deg_bonus);
								//AddEvent($Avion_db,15,$avion,$PlayerID,$Unite,$Cible,$Damage);
								UpdateData("Lieu","NoeudF",$Damage,"ID",$Cible);
								$msghit.="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
							}
							elseif($usine)
							{
								$Damage=floor(0-($rep_c/5)-$Deg_bonus);
								//AddEvent($Avion_db,16,$avion,$PlayerID,$Unite,$Cible,$Damage);
								UpdateData("Lieu","Industrie",$Damage,"ID",$Cible);
								$msghit.="<br>Votre attaque diminue le potentiel de production de l'ennemi!";
							}
							elseif($caserne)
							{
								//AddEvent($Avion_db,71,$avion,$PlayerID,$Unite,$Cible,$Damage);
								UpdateData("Lieu","Fortification",-10,"ID",$Cible);
								$msghit.="<br>Votre attaque diminue le moral des troupes de l'ennemi!";
							}
							elseif($pont)
							{
								$Damage=floor(0-($rep_c/5)-$Deg_bonus);
								//AddEvent($Avion_db,17,$avion,$PlayerID,$Unite,$Cible,$Damage);
								SetData("Lieu","Pont",$Damage,"ID",$Cible);
								if($Damage >99)
									$msghit.="<br>Le pont est totalement détruit!";
								else
									$msghit.="<br>Le pont est endommagé!";
							}
							elseif($port)
							{
								//AddEvent($Avion_db,29,$avion,$PlayerID,$Unite,$Cible,$Damage);
								$Damage=floor(0-($rep_c/5)-$Deg_bonus);
								UpdateData("Lieu","Port",$Damage,"ID",$Cible);
								$msghit.="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
								if($citerne)
								{
									if($Cible ==343)
									{
										$Flag_343=GetData("Lieu","ID",343,"Flag");
										$query="SELECT ID FROM Lieu WHERE Flag IN(2,'$Flag_343') AND Latitude <43 AND Longitude <60 AND Zone<>6";
									}
									elseif($Cible ==344)
									{
										$Flag_344=GetData("Lieu","ID",344,"Flag");
										$query="SELECT ID FROM Lieu WHERE Flag IN(2,'$Flag_343') AND Latitude <43 AND Longitude <60 AND Zone<>6";
									}
									else
									{
										//UpdateData("Lieu","Citernes",5,"ID",$Cible);
										$Lat_base_min=$Lat_base-1.00;
										$Lat_base_max=$Lat_base+1.00;
										$Long_base_min=$Long_base-2.00;
										$Long_base_max=$Long_base+2.00;
										$query="SELECT ID FROM Lieu WHERE Flag='$Pays_cible'
										AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
										AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND Zone<>6";
									}
									$con=dbconnecti();
									$resultl=mysqli_query($con,$query);
									mysqli_close($con);
									if($resultl)
									{
										while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
										{
											UpdateData("Lieu","Citernes",5,"ID",$datal['ID']);
											AddEvent($Avion_db,72,$avion,$PlayerID,$Unite,$datal['ID'],5,$Cible);
										}
										mysqli_free_result($resultl);
									}
								}
							}
							elseif($radar)
							{
								//AddEvent($Avion_db,70,$avion,$PlayerID,$Unite,$Cible,$Damage);
								$Damage=floor(0-($rep_c/5)-$Deg_bonus);
								UpdateData("Lieu","Radar",$Damage,"ID",$Cible);
								UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
								$msghit.="<br>Votre attaque diminue le potentiel de détection de l'ennemi!";
							}
							elseif($tour)
							{
								$Damage=floor(0-($rep_c/5)-$Deg_bonus);
								UpdateData("Lieu","Tour",$Damage,"ID",$Cible);
								UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
								$msghit.="<br>Votre attaque diminue les capacités d'organisation de l'aérodrome!";
							}
							elseif($hangar)
							{
								$con=dbconnecti();
								$result=mysqli_query($con,"SELECT DISTINCT ID FROM Unit WHERE Base='$Cible' ORDER BY RAND() LIMIT 1");
								mysqli_close($con);
								if($result)
								{
									$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
									$Unite_loss=$data['ID'];
								}
								//AddEvent($Avion_db,14,$avion,$PlayerID,$Unite,$Cible);
								//Si pas d'unité sur cette base, pas de message de perte de stock
								if($Unite_loss >0)
								{
									AddEvent($Avion_db,24,$avion,$PlayerID,$Unite_loss,$Cible);
									$stock_rand=mt_rand(1,9);
									//$stock_qty=mt_rand(-500,-100);
									$stock_qty=-$Degats;
									switch($stock_rand)
									{
										case 1:
											$stock="Stock_Essence_87";
										break;
										case 2:
											$stock="Stock_Essence_100";
										break;
										case 3:
											$stock="Stock_Munitions_8";
										break;
										case 4:
											$stock="Stock_Munitions_13";
										break;
										case 5:
											$stock="Stock_Munitions_20";
										break;
										case 6:
											$stock="Stock_Munitions_30";
										break;
										case 7:
											$stock="Stock_Essence_1";
										break;
										case 8:
											$stock="Stock_Munitions_40";
										break;
										case 9:
											$stock="Stock_Munitions_75";
										break;
									}
									UpdateData("Unit",$stock,$stock_qty,"ID",$Unite_loss);
								}
								$msghit="<br>Votre bombe détruit un hangar, réduisant les stocks de l'ennemi!";
								UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
							}
							elseif($depot)
							{
								//Si pas d'unité sur cette base, pas de message de perte de stock
								$stock_rand=mt_rand(1,12);
								$stock_qty=-$Degats;
								switch($stock_rand)
								{
									case 1:
										$stock="Stock_Munitions_8";
									break;
									case 2:
										$stock="Stock_Munitions_13";
									break;
									case 3:
										$stock="Stock_Munitions_20";
									break;
									case 4:
										$stock="Stock_Munitions_30";
									break;
									case 5:
										$stock="Stock_Munitions_40";
									break;
									case 6:
										$stock="Stock_Munitions_50";
									break;
									case 7:
										$stock="Stock_Munitions_60";
									break;
									case 8:
										$stock="Stock_Munitions_75";
									break;
									case 9:
										$stock="Stock_Munitions_90";
									break;
									case 10:
										$stock="Stock_Munitions_105";
									break;
									case 11:
										$stock="Stock_Munitions_125";
									break;
									case 12:
										$stock="Stock_Munitions_150";
									break;
								}
								AddEvent($Avion_db,114,$avion,$PlayerID,$Unite,$Cible,2,$stock_qty);
								UpdateData("Lieu",$stock,$stock_qty,"ID",$Cible);
								$msghit="<br>Votre bombe détruit un entrepôt, réduisant les stocks de l'ennemi!";
								UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
							}
							elseif($piste)
							{
								if($Deg_bonus >10)$Deg_bonus=10;
								$con=dbconnecti();
								$result=mysqli_query($con,"SELECT ID FROM Unit WHERE Base='$Cible'");
								mysqli_close($con);
								if($result)
								{
									while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
									{
										$Unite_loss=$data['ID'];
										AddEvent($Avion_db,28,$avion,$PlayerID,$Unite_loss,$Cible);
									}
									mysqli_free_result($result);
								}
								//AddEvent($Avion_db,27,$avion,$PlayerID,$Unite,$Cible);
								$Damage=floor(0-(mt_rand(0,$ArmeAvion)/10)-$Deg_bonus);
								UpdateData("Lieu","QualitePiste",$Damage,"ID",$Cible);
								UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
							}
							elseif($citerne)
							{
								$msghit='<p>Votre tir fait mouche, vous détruisez '.$nom_c.'</p>';
								//UpdateData("Lieu","Citernes",1,"ID",$Cible);
								$Lat_base_min=$Lat_base-1.00;
								$Lat_base_max=$Lat_base+1.00;
								$Long_base_min=$Long_base-1.00;
								$Long_base_max=$Long_base+1.00;
								$con=dbconnecti();
								$resultl=mysqli_query($con,"SELECT ID FROM Lieu WHERE Flag='$Pays_cible'
								AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
								AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND Zone<>6");
								mysqli_close($con);
								if($resultl)
								{
									while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
									{
										UpdateData("Lieu","Citernes",1,"ID",$datal['ID']);
										AddEvent($Avion_db,72,$avion,$PlayerID,$Unite,$datal['ID'],1,$Cible);
									}
									mysqli_free_result($resultl);
								}							
								$stock_rand=mt_rand(1,3);
								$stock_qty=-$Degats;
								switch($stock_rand)
								{
									case 1:
										$stock="Stock_Essence_1";
									break;
									case 2:
										$stock="Stock_Essence_87";
									break;
									case 3:
										$stock="Stock_Essence_100";
									break;
								}
								UpdateData("Lieu",$stock,$stock_qty,"ID",$Cible);
							}
							elseif($camion)
							{
								$msghit='<p>Votre tir fait mouche, vous détruisez '.$nom_c.'</p>';
								//UpdateData("Lieu", "Camions", $camion, "ID", $Cible);
								$Lat_base_min=$Lat_base-1.00;
								$Lat_base_max=$Lat_base+1.00;
								$Long_base_min=$Long_base-1.00;
								$Long_base_max=$Long_base+1.00;
								$con=dbconnecti();
								$resultl=mysqli_query($con,"SELECT ID FROM Lieu WHERE Flag='$Pays_cible'
								AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
								AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND Zone<>6");
								mysqli_close($con);
								if($resultl)
								{
									while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
									{
										UpdateData("Lieu","Camions",$camion,"ID",$datal['ID']);
										AddEvent($Avion_db,73,$avion,$PlayerID,$Unite,$datal['ID'],$camion,$Cible);
									}
									mysqli_free_result($resultl);
								}
							}				
							elseif($Cible_Atk ==3 and $tank ==48)
							{
								$Kills=floor($Degats/100);
								if($Kills >50) $Kills=50;
								if($Kills >$Garnison)$Kills=$Garnison;
								UpdateData("Lieu","Garnison",-$Kills,"ID",$Cible);
								if($Kills >100)
									$msghit="<p>Votre bombardement dévastateur met hors de combat des dizaines de soldats de la garnison</p>";
								elseif($Kills >50)
									$msghit="<p>Votre bombardement précis met hors de combat une bonne partie des soldats de la garnison</p>";
								elseif($Kills >10)
									$msghit="<p>Votre bombardement met hors de combat quelques soldats de la garnison</p>";
								else
									$msghit="<p>Votre bombardement imprécis n'entame pas la détermination de la garnison</p>";
							}
							//Tableau de chasse
							if($Mission_Type >7 and $Cible_Atk <10 and $Action !=66 and $alt >4499)
							{
								if($Simu)
								{
									AddVictoire_Bomb($Avion_db,$type_c,$tank,$avion,$PlayerID,$Unite,$Cible,$ArmeAvion,$Nuit,$Pays_cible,$alt);
									AddEventFeed(206,$avion,$PlayerID,$Unite,$Cible,$Nuit,$tank);
								}
							}
							else
							{
								//Navires et troupes au sol
								if($mobile and $RR)
								{
									$Tues=1;
									if($RR =="Vehicule_Nbr")
									{
										$con=dbconnecti();
										$result=mysqli_query($con,"SELECT r.Vehicule_Nbr,r.Pays,r.Fret_Qty,r.Fret,o.Transit,o.Avancement FROM Regiment as r,Officier as o WHERE r.Officier_ID=o.ID AND r.ID='$Regi'");
										//mysqli_close($con);
										if($result)
										{
											while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
											{
												$Vehicule_Nbr_regi=$data['Vehicule_Nbr'];
												$Pays_cible=$data['Pays'];
												$Transit=$data['Transit'];
												$Fret=$data['Fret'];
												$Fret_Qty=$data['Fret_Qty'];
												$Avancement_Off_eni=$data['Avancement']; 
											}
											mysqli_free_result($result);
										}
										if($Vehicule_Nbr_regi >25)
										{
											$Tues=floor(1+(($Degats-$hp_c)/$hp_c));
											$Max_Spread=$Bombs_Hit*10;
											if($Tues >$Max_Spread)$Tues=$Max_Spread;
											if($cible_position ==2 and $Tues >1)$Tues=floor($Tues/2);
											if($Tues >25)$Tues=25;
										}
										if($Transit >0)
										{
											//$con=dbconnecti();
											$reset=mysqli_query($con,"UPDATE Regiment SET Experience=0,Skill=0,Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
											Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
											Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Visible=0 WHERE Officier_ID='$Transit' AND Vehicule_Nbr>0 ORDER BY RAND() LIMIT 1");
											//mysqli_close($con);
											AddEventGround(411,$avion,$Transit,$Regi,$Cible,1,$tank);
										}
										if($Fret >0)
										{
											if($Fret ==888)
												UpdateData("Pays","Special_Score",-1,"ID",$Pays_cible);
											elseif($Fret ==200 and $Fret_Qty >0)
											{
												if($Vehicule_Nbr_regi <2)
												{
													//$con=dbconnecti();
													$reset=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0,HP=0,Position=6 WHERE ID='$Regi'");
													$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Moral=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Experience=0,Skill=0,Visible=0 WHERE ID='$Fret_Qty'");
													//mysqli_close($con);
												}
											}
											elseif($Fret_Qty >0)
											{
												$Perte_Stock=$Fret_Qty/$Vehicule_Nbr_regi;
												UpdateData("Regiment","Fret_Qty",-$Perte_Stock,"ID",$Regi);
											}
										}
										if($mobile ==5)
											$HP_new=$hp_ori;
										else
											$HP_new=0;
										$Nbr_end=$Vehicule_Nbr_regi-$Tues;
										$Nbr_crit=GetMaxVeh($type_c,$mobile,$Flak_c,$Avancement_Off_eni);
										if($Strike or $Nbr_end ==1 or $Nbr_end<($Nbr_crit/2))
											$Vis_final=0;
										else
											$Vis_final=1;
										if($cible_position ==11) //unités en transit
											$cible_pos_finale=11;
										elseif($cible_position ==8)
											$cible_pos_finale=9;
										else
										{
											if($Nbr_end ==1 or $Nbr_end<($Nbr_crit/2))
												$cible_pos_finale=8;
											else
												$cible_pos_finale=$cible_position;
										}
										//$con=dbconnecti();
										$reset=mysqli_query($con,"UPDATE Regiment SET Position='$cible_pos_finale',HP='$HP_new',Vehicule_Nbr='$Nbr_end',Visible='$Vis_final',Bomb_PJ=1 WHERE ID='$Regi'");;
										mysqli_close($con);
										AddEventGround(402,$avion,$PlayerID,$Regi,$Cible,$Tues,$tank);
										$rep_pts*=2;
										$recce_tac=true;
									}
									elseif($RR =="Vehicule_Nbr_ia")
									{
										$con=dbconnecti();
										$result=mysqli_query($con,"SELECT r.Vehicule_Nbr,r.Pays,c.Type,c.mobile,c.Flak FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.ID='$Regi'");
										mysqli_close($con);
                                        if($result)
										{
											while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
											{
												$Vehicule_Nbr_regi=$data['Vehicule_Nbr'];
												$Pays_cible=$data['Pays'];
												$Type_Naval=$data['Type'];
												$Type_mobile=$data['mobile'];
												$Flakc=$data['Flak'];
											}
											mysqli_free_result($result);
										}
										if($tank >4999)
										{
											if($Vehicule_Nbr_regi ==1)
											{
												if($Type_Naval >15 and $Type_Naval <22)
													$query_reset_ia="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Position=8,HP=0,Moral=0,Fret=0,Fret_Qty=0,Visible=0 WHERE ID='$Regi'";
												else
													$query_reset_ia="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Position=8,HP=0,Fret=0,Fret_Qty=0,Visible=0 WHERE ID='$Regi'";
											}
											else
											{
												$HP_new=$hp_ori;
												$Nbr_end=$Vehicule_Nbr_regi-1;
												$query_reset_ia="UPDATE Regiment_IA SET Position=8,HP='$HP_new',Vehicule_Nbr='$Nbr_end',Visible=0 WHERE ID='$Regi'";
											}
											$con=dbconnecti();
											$reset=mysqli_query($con,$query_reset_ia);;
											mysqli_close($con);
										}
										else
										{
											if($Vehicule_Nbr_regi >25)$Tues=floor(1+(($Degats-$hp_c)/$hp_c));
											$Max_Spread=$Bombs_Hit*20;
											if($Tues >$Max_Spread)$Tues=$Max_Spread;
											if($cible_position ==2 and $Tues >1)$Tues=floor($Tues/2);
											if($Tues >25)$Tues=25;
											$Nbr_end=$Vehicule_Nbr_regi-$Tues;
											$Nbr_crit=GetMaxVeh($Type_Naval,$Type_mobile,$Flakc,25000);
											if($cible_position ==8)
												$cible_pos_finale=9;									
											elseif($Nbr_end ==1 or $Nbr_end<($Nbr_crit/1.5))
												$cible_pos_finale=8;
											elseif($Type_mobile !=5 and ($cible_position ==4 or $cible_position ==0))
												$cible_pos_finale=2;
											else
												$cible_pos_finale=$cible_position;
											$con=dbconnecti();
											$reset=mysqli_query($con,"UPDATE Regiment_IA SET Position='$cible_pos_finale',Vehicule_Nbr='$Nbr_end',Visible=0,Experience=Experience+1 WHERE ID='$Regi'");;
											mysqli_close($con);
										}
										if($Avion_db !="Avion")
											$avion_event=GetData($Avion_db,"ID",$avion,"ID_ref");
										else
											$avion_event=$avion;
										AddEventGround(502,$avion_event,$PlayerID,$Regi,$Cible,$Tues,$tank);
										//error_log("Tués=".$Tues." / Total=".$Vehicule_Nbr_regi." / Regi=".$Regi, 1,'binote@hotmail.com','Bomb : Regiment_IA kills');
										$recce_tac=true;
									}
									else
									{
										$rep_c/=2;
										if($rep_c <1)$rep_c=1;
										$Strike=true;
										SetData("Pilote","S_Strike",1,"ID",$PlayerID);
									}
								}
								if($Simu)
									AddVictoire_atk($Avion_db,$type_c,$tank,$avion,$PlayerID,$Unite,$Cible,$ArmeAvion,$Pays_cible,0,$alt,$Nuit,$Degats,$Tues);
							}
							if($Simu)
							{
								//Reput missions unité
								if(IsWar($Pays_cible,$country))
								{
									//Augmenter le score des bombardements en tapis
									if($Bombs_Hit >1)$rep_pts*=2;
									if($rep_c >1)
									{
										/*if($Cible == GetData("Unit","ID",$Unite,"Mission_Lieu"))
										{
											UpdateData("Unit","Reputation",$rep_pts,"ID",$Unite);
											$Cdt=GetData("Unit","ID",$Unite,"Commandant");
											if($Cdt)
											{
												UpdateCarac($Cdt,"Reputation",10);
												UpdateCarac($Cdt,"Avancement",5);
												UpdateCarac($Cdt,"Commandement",5);
											}
										}*/
                                        if($tank >4999)$dem_naval=",12";
										$con=dbconnecti();
                                        $resultdem=mysqli_query($con,"SELECT ID FROM Regiment_IA WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D IN (1,2".$dem_naval.")");
										$reset=mysqli_query($con,"UPDATE Pilote_IA SET Points=Points+'$rep_c',Reputation=Reputation+'$rep_c',Avancement=Avancement+'$rep_c',Moral=Moral+10,Courage=Courage+10 WHERE Unit='$Unite' AND Cible='$Cible'");;
										mysqli_close($con);
                                        if($resultdem)
                                        {
                                            while($datad=mysqli_fetch_array($resultdem,MYSQLI_ASSOC))
                                            {
                                                UpdateData("Regiment_IA","Experience",5,"ID",$datad['ID'],0,8);
                                            }
                                            mysqli_free_result($resultdem);
                                            UpdateData("Unit","Reputation",$rep_pts,"ID",$Unite,0,8);
                                            UpdateCarac($PlayerID,"Note",1);
                                        }
                                        if($Cible == GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unite,"Type")))
										{
											$Cdt=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
											if($Cdt)
											{
												UpdateCarac($Cdt,"Reputation",5);
												UpdateCarac($Cdt,"Avancement",10);
											}
											UpdateData("Unit","Reputation",$rep_pts,"ID",$Unite,0,7);
										}
										//Mission_Historique
										if($Cible ==$BH_Lieu)
										{
											if(IsAxe($country))
												$Points_cat="Points_Axe";
											else
												$Points_cat="Points_Allies";
											UpdateData("Event_Historique",$Points_cat,$rep_pts,"ID",$_SESSION['BH_ID']);
											//UpdateData("Unit","Reputation",$rep_pts,"ID",$Unite,0,7);
										}
										if($Deleguer)
											UpdateCarac($Equipage,"Bombardement",$Bombs_Hit,"Equipage");
										else
											UpdateCarac($PlayerID,"Reputation",$Bombs_Hit); //UpdateCarac($PlayerID,"Bombardement",$Bombs_Hit);
										UpdateCarac($PlayerID,"Victoires_atk",$rep_pts);
										UpdateCarac($PlayerID,"Reputation",$rep_pts);
										UpdateCarac($PlayerID,"Avancement",$rep_pts);
										UpdateCarac($PlayerID,"Moral",10);
									}
									if($Strike ==false)
									{
										//Doubler la récompense en cas de bataille historique
										if($Cible ==$BH_Lieu)
										{
											$Pts_Bonus=2;
											UpdateCarac($PlayerID,"Batailles_Histo",1);
										}
										else
											$Pts_Bonus=1;
										if($rep_c >1)
										{
											/*if($Mission_Type ==14 and $Escorteb_nbr <1)
												$msghit.="<br><b>Le convoi ayant été détruit, votre mission est un échec!</b>";
											else*/
												$msghit.="<br><b>Vous avez accompli votre mission!</b>";
											if($Mission_Type >5 and $Cible_Atk <10 and $Action !=66 and $alt >4499)
											{
												if($Nuit)
													UpdateCarac($PlayerID,"Raids_Bomb_Nuit",1);
												else
													UpdateCarac($PlayerID,"Raids_Bomb",1);
											}
											else
												UpdateCarac($PlayerID,"Dive",1);
											$rep_pts+=10;
											$rep_pts*=$Pts_Bonus;
											if($rep_pts >50)$rep_pts=50;
											UpdateCarac($PlayerID,"Missions",$rep_pts);
											UpdateCarac($PlayerID,"Reputation",$rep_pts);
											UpdateCarac($PlayerID,"Avancement",$rep_pts);										
											if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
											{
												UpdateCarac($Equipage,"Missions",1,"Equipage");
												UpdateCarac($Equipage,"Avancement",20,"Equipage");
												UpdateCarac($Equipage,"Reputation",25,"Equipage");
												UpdateCarac($Equipage,"Moral",10,"Equipage");
											}
											//Reput Chasseurs escorte
											if(!$Nuit and $Mission_Type !=16)
											{
												$con=dbconnecti();
												$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
												$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Points=Points+20,Reputation=Reputation+20,Avancement=Avancement+20,Points=Points+20,Moral=Moral+10 WHERE Escorte='$Cible' AND Pays='$country'");
												$result=mysqli_query($con,"SELECT DISTINCT j.ID,j.Unit,j.Pays,u.Mission_Lieu,u.Commandant FROM Pilote as j,Pays as p,Unit as u
												WHERE j.Escorte='$Cible' AND j.ID<>'$PlayerID' AND j.Pays=p.ID AND j.Unit=u.ID AND p.Faction='$Faction'");
												mysqli_close($con);
												if($result)
												{
													while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
													{
														/*Reput missions unité
														if($Cible == $data['Mission_Lieu'])
														{
															if($data['Commandant'] >0)
															{
																UpdateCarac($data['Commandant'],"Reputation",5);
																UpdateCarac($data['Commandant'],"Avancement",2);
																UpdateCarac($data['Commandant'],"Commandement",2);
															}
															UpdateData("Unit","Reputation",10,"ID",$data['Unit']);
														}
														else*/if($Cible == GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$data['Unit'],"Type")))
														{
															$Cdt=GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Commandant");
															if($Cdt)
															{
																UpdateCarac($Cdt,"Reputation",2);
																UpdateCarac($Cdt,"Avancement",5);
															}
															UpdateData("Unit","Reputation",10,"ID",$data['Unit'],0,9);
														}
														//Doubler la récompense en cas de bataille historique
														if($Cible ==$BH_Lieu)
														{
															if(IsAxe($country))
																$Points_cat="Points_Axe";
															else
																$Points_cat="Points_Allies";
															if($data['Commandant'] >0)
																UpdateCarac($data['Commandant'],"Batailles_Histo",1);
															UpdateData("Event_Historique",$Points_cat,20,"ID",$_SESSION['BH_ID']);
															//UpdateData("Unit","Reputation",20,"ID",$data['Unit'],0,9);
														}
														UpdateCarac($data['ID'],"Missions",20);
														UpdateCarac($data['ID'],"Avancement",20);
														UpdateCarac($data['ID'],"Reputation",20);
														UpdateCarac($data['ID'],"Note",1);
														AddEvent($Avion_db,87,$avion,$PlayerID,$data['Unit'],$Cible,0,$data['ID']);
													}
												}	
											}
											//Reput Reco
											if($recce_tac)
											{
												if(!$Faction)$Faction=GetData("Pays","ID",$country,"Faction");
												if($Faction ==1)
													$query_recce_ply="SELECT Recce_PlayerID_TAX FROM Lieu WHERE ID='$Cible'";										
												elseif($Faction ==2)
													$query_recce_ply="SELECT Recce_PlayerID_TAL FROM Lieu WHERE ID='$Cible'";
												$con=dbconnecti();
												$Recce_PID=mysqli_result(mysqli_query($con,$query_recce_ply),0);
												mysqli_close($con);
												if($Recce_PID >0)$Unit_Recce=GetData("Pilote","ID",$Recce_PID,"Unit");
												$Bonus_Recce_PID=10;								
											}
											else
											{
												$con=dbconnecti();
												$Recce_PID=mysqli_result(mysqli_query($con,"SELECT Recce_PlayerID FROM Lieu WHERE ID='$Cible'"),0);
												mysqli_close($con);
												if($Recce_PID >0)$Unit_Recce=GetData("Pilote","ID",$Recce_PID,"Unit");		
												$Bonus_Recce_PID=20+($Valstrat*2);
											}
											if($Unit_Recce >0 and $Unit_Recce !=$Unite)
											{
												/*Reput missions unité
												if($Cible ==GetData("Unit","ID",$Unit_Recce,"Mission_Lieu"))
												{
													$Cdt=GetData("Unit","ID",$Unit_Recce,"Commandant");
													if($Cdt)
													{
														UpdateCarac($Cdt,"Reputation",10);
														UpdateCarac($Cdt,"Avancement",5);
														UpdateCarac($Cdt,"Commandement",5);
													}
													UpdateData("Unit","Reputation",$Bonus_Recce_PID,"ID",$Unit_Recce);
												}
												else*/if($Cible ==GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unit_Recce,"Type")))
												{
													$Cdt=GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Commandant");
													if($Cdt)
													{
														UpdateCarac($Cdt,"Reputation",5);
														UpdateCarac($Cdt,"Avancement",10);
													}
													UpdateData("Unit","Reputation",$Bonus_Recce_PID,"ID",$Unit_Recce,0,11);
												}
												/*Doubler la récompense en cas de bataille historique
												if($Cible ==$BH_Lieu)
													UpdateData("Unit","Reputation",$Bonus_Recce_PID,"ID",$Unit_Recce,0,11);*/
												UpdateCarac($Recce_PID,"Missions",$Bonus_Recce_PID);
												UpdateCarac($Recce_PID,"Avancement",$Bonus_Recce_PID);
												UpdateCarac($Recce_PID,"Reputation",$Bonus_Recce_PID);
												UpdateCarac($Recce_PID,"Note",1);
												AddEvent($Avion_db,89,$avion,$PlayerID,$Unit_Recce,$Cible,0,$Recce_PID);
											}
										}	
										else
											$msghit.="<p><b>La cible que vous venez de détruire est insignifiante.</b></p>";
										SetData("Pilote","S_Strike",1,"ID",$PlayerID);
										SetData("Lieu","Last_Attack",$Date_Campagne,"ID",$Cible);
									}
								}
								else
								{
									$msghit="<p>Vous attaquez des cibles alliées!</p>";
									/*if($Cible ==GetData("Unit","ID",$Unite,"Mission_Lieu"))
									{
										$Cdt=GetData("Unit","ID",$Unite,"Commandant");
										if($Cdt)
										{
											UpdateCarac($Cdt,"Reputation",-20);
											UpdateCarac($Cdt,"Avancement",-10);
										}
										UpdateData("Unit","Reputation",-20,"ID",$Unite);
									}*/
									UpdateCarac($PlayerID,"Reputation",-20);
									UpdateCarac($PlayerID,"Avancement",-50);
								}
							}
						}
						$seconde_passe=true;
					}
					else
					{
						//Dégats persistants grosses unités navales
						if($mobile ==5 and $Simu and ($RR =="Vehicule_Nbr" or $RR =="Vehicule_Nbr_ia"))
						{
							if($RR =="Vehicule_Nbr")
								$DB="Regiment";
							else
								$DB="Regiment_IA";
							UpdateData($DB,"HP",-$Degats,"ID",$Regi);
							$HP_final=GetData($DB,"ID",$Regi,"HP");
							if(!$Pays_cible)$Pays_cible=GetData($DB,"ID",$Regi,"Pays");
							if($HP_final <1)
							{
								$rep_c_nav=$rep_c;
								$msghit="<p>L'explosion, occasionnant ".round($Degats)." dégâts, achève votre cible!</p>";
								AddVictoire_atk($Avion_db,$type_c,$tank,$avion,$PlayerID,$Unite,$Cible,$ArmeAvion,$Pays_cible,0,$alt,$Nuit,$Degats);
								$con=dbconnecti();
								$reset=mysqli_query($con,"UPDATE $DB SET Position=8,HP='$hp_ori',Vehicule_Nbr=Vehicule_Nbr-1 WHERE ID='$Regi'");;
								mysqli_close($con);
							}
							else
							{
								$rep_c_nav=$rep_c/10;
								$msghit="<p>L'explosion, occasionnant ".round($Degats)." dégâts, a endommagé la cible, mais ne l'a pas détruite!</p>";
								if($RR =="Vehicule_Nbr")
									AddEventGround(409,$avion_img,$PlayerID,$Regi,$Cible,$Degats,$tank);
								else
									AddEventGround(509,$avion_img,$PlayerID,$Regi,$Cible,$Degats,$tank);
								if($RR =="Vehicule_Nbr_ia")
								{
									$con=dbconnecti();
									$reset=mysqli_query($con,"UPDATE Regiment_IA SET Mission_Type_D=7,Mission_Lieu_D='$Cible' WHERE ID='$Regi'");;
									mysqli_close($con);
								}
							}
							//UpdateCarac($PlayerID,"Bombardement",$Bombs_Hit);
							UpdateData("Unit","Reputation",$rep_c_nav,"ID",$Unite,0,12);
							$con=dbconnecti();
							$reset=mysqli_query($con,"UPDATE Pilote SET Victoires_atk=Victoires_atk+5,Reputation=Reputation+5,Avancement=Avancement+5,Courage=Courage+5,Moral=Moral+5,Points=Points+'$rep_c_nav',Exp_Pts=Exp_Pts+5 WHERE ID='$PlayerID'");
							mysqli_close($con);
						}
						else
							$msghit="<p>L'explosion, occasionnant ".round($Degats)." dégâts, n'a pas détruit la cible!</p>";
						if(!$img)$img=Afficher_Image('images/cibles/cibles'.$tank.'_'.$Pays_cible.'.jpg','images/cibles/cibles'.$tank.'.jpg',$nom_c);
						$seconde_passe=true;
					}	
				}//End Pathfinder				
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
		$Equipage_Nom=GetData("Equipage","ID",$Equipage,"Nom");
		$intro.="<br>".$Equipage_Nom." refuse d'obéir aux ordres, il ".$Etat_Eq;
		$img="<img src='images/demoralise.jpg' style='width:100%;'>";
		$seconde_passe=true;
	}	
	$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);	
	$con=dbconnecti();
	$reset=mysqli_query($con,"UPDATE Pilote SET S_Avion_Bombe_Nbr='$Bombs',S_Essence='$essence' WHERE ID='$PlayerID'");
	mysqli_close($con);
	//Si le site a été camouflé entre temps, réinitialiser le pilote de reco
	if(!$Recce_Lieu and !$Cible)
		SetData("Lieu","Recce_PlayerID",0,"ID",$Cible);
	if(!$end_mission)
	{
		if($seconde_passe)
		{
			UpdateData("Pilote","S_Pass",1,"ID",$PlayerID);
			if(!$img)
				$img="<img src='images/hidden.jpg' style='width:100%;'>";
			if($Strike)
				$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,8);
			else
				$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,1);
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
			$mes.="<form action='bomb.php' method='post'>
			<input type='hidden' name='Avion' value=".$avion.">
			<input type='hidden' name='Meteo' value=".$meteo.">
			<input type='hidden' name='Mun1' value=".$Mun1.">
			<input type='hidden' name='Mun2' value=".$Mun2.">
			<input type='hidden' name='Puissance' value=".$Puissance.">
			<input type='hidden' name='HP_eni' value=".$HP_eni.">
			<input type='hidden' name='Pays_eni' value=".$Pays_cible.">
			".$gaz_txt."<table class='table'><thead><tr><th colspan='8'>Seconde passe</th></tr></thead>
			<tr><td align='left'>".$choix3.$choix6.$choix_pvp."</td></tr></table>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";		
		}
		if($retour)
		{
			if($Zone ==6)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE ID='$Cible'");
				mysqli_close($con);
			}
			Chemin_Retour();
			$chemin=$Distance;
			$intro.="<p>Vous prenez le chemin du retour en direction de votre base, située à ".$Distance."km</p>";
			$mes.="<form action='nav.php' method='post'>
			<input type='hidden' name='Chemin' value=".$chemin.">
			<input type='hidden' name='Distance' value=".$Distance.">
			<input type='hidden' name='Meteo' value=".$meteo.">
			<input type='hidden' name='Avion' value=".$avion.">
			<input type='hidden' name='Mun1' value=".$Mun1.">
			<input type='hidden' name='Mun2' value=".$Mun2.">
			<input type='hidden' name='Puissance' value=".$Puissance.">
			<input type='hidden' name='Enis' value=".$Enis.">
			<table class='table'>
				<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt)."</tr></table>
			<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}
	elseif($end_mission)
	{
		RetireCandidat($PlayerID,"end_mission");
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		UpdateCarac($PlayerID,"Abattu",1);
		if($HP <1)
		{
			//Tableau de chasse
			if(!$tank or $tank ==999)$tank=16;
			AddVictoire_atk($Avion_db,$Regi,$tank,$avion,$PlayerID,$Unite,$Cible,$arme_c,$country,1,$alt,$Nuit,$Degats);
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
				AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
				$blesse=2;
				DoBlessure($PlayerID,10);
			break;
		}
		$intro.=$Blessure_txt;
		if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
		{
			$Blessure_max=10;
			$Trait_e=GetData("Equipage","ID",$Equipage,"Trait");
			if($Trait_e ==3)$Blessure_max=20;
			$Blessure=mt_rand(0,$Blessure_max);
			if($Blessure <1)UpdateCarac($Equipage,"Endurance",-1,"Equipage");
			UpdateCarac($Equipage,"Moral",-25,"Equipage");
		}
		UpdateCarac($PlayerID,"Moral",$Malus_Moral);
		UpdateCarac($PlayerID,"Reputation",-10);
		UpdateData("Unit","Reputation",-10,"ID",$Unite,0,4);
		//Prisonnier
		$Base=GetData("Unit","ID",$Unite,"Base");
		$Dist=GetDistance($Base,$Cible);
		if($BonneEtoile)
			$luck_p=mt_rand(0,25);
		elseif($Slot10 ==71)
			$luck_p=mt_rand(0,20);
		elseif($Slot10 ==72 or $Slot10 ==77)
			$luck_p=mt_rand(0,5);
		elseif($Slot10 ==34)
			$luck_p=mt_rand(0,5);
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
			AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
			$_SESSION['Distance']=0;
		}
		else
		{		
			if($blesse <2)$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.="<form action='promotion.php' method='post'>
				<input type='hidden'  name='Blesse'  value=".$blesse.">
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