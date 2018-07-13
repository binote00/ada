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
$HP_eni=Insec($_POST['HP_eni']);
$Puissance=Insec($_POST['Puissance']);
$ArmeAvion=Insec($_POST['ArmeAvion']);
$ArmeAvion_nbr=Insec($_POST['ArmeAvion_nbr']);
$Mun=Insec($_POST['Mun']);
$Pays_cible=Insec($_POST['Pays_eni']);
$Lock=Insec($_POST['Cible_lock']);
$Deleguer=Insec($_POST['Deleguer']);
$Rafalet=Insec($_POST['Rafalet']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$PlayerID=$_SESSION['PlayerID'];
//Check Joueur Valide
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $_SESSION['attaquer'] ==false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	$_SESSION['cibler'] =false;
	$_SESSION['naviguer'] =false;
	$_SESSION['attaquer'] =true;
	$country=$_SESSION['country'];
	$Distance=$_SESSION['Distance'];
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
	$avion_parque=false;
	$hangar=false;
	$caserne=false;
	$citerne=false;
	$camion=false;
	$depot=false;
	$recce_tac=false;
	$Atk_Mob=false;
	$dca_unit_skill=10;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Equipage,Pilotage,Tir,Courage,Moral,S_Avion_db,S_Cible,S_Mission,S_Cible_Atk,S_Strike,S_Longitude,S_Latitude,S_Essence,Simu,S_Blindage,S_Pass,
	S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Equipage_Nbr,S_Engine_Nbr,S_Nuit,S_Formation,Slot5,Slot10,Slot11 FROM Pilote WHERE ID='$PlayerID'")
	 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk-player');
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
			$Pilotage=$data['Pilotage'];
			$Tir=$data['Tir'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Avion_db=$data['S_Avion_db'];
			$Cible=$data['S_Cible'];
			$Nuit=$data['S_Nuit'];
			$Mission_Type=$data['S_Mission'];
			$Cible_Atk=$data['S_Cible_Atk'];
			$Strike=$data['S_Strike'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Bombs=$data['S_Avion_Bombe_Nbr'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$S_Blindage=$data['S_Blindage'];
			$S_Pass=$data['S_Pass'];
			$Formation=$data['S_Formation'];
			$Slot5=$data['Slot5'];
			$Slot10=$data['Slot10'];
			$Slot11=$data['Slot11'];
			$Simu=$data['Simu'];
		}
		mysqli_free_result($result);
	}
	if($Pilotage >50)$Pilotage=50;
	if($Tir >50)$Tir=50;
	$Steady=1;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(39,$Skills_Pil))
			$Steady=1.1;
		if(in_array(41,$Skills_Pil))
			$AsZigZag=50;
		if(in_array(50,$Skills_Pil))
			$Bonne_Etoile=true;
		if(in_array(78,$Skills_Pil))
			$Discipline_fer=true;
	}
	if($Equipage)$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");		
	if($Slot11 ==69)
	{
		$Moral+=50;
		$Courage+=50;
	}
	$con=dbconnecti();
	$meteo=mysqli_result(mysqli_query($con,"SELECT Meteo FROM Lieu WHERE ID='$Cible'"),0);
	$result=mysqli_query($con,"SELECT Robustesse,Type,Masse,ArmePrincipale,ArmeSecondaire,Viseur FROM $Avion_db WHERE ID='$avion'")
	 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk-avion');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$HPmax=$data['Robustesse'];
			$Type_avion=$data['Type'];
			$Masse=$data['Masse'];
			$Arme1Avion=$data['ArmePrincipale'];
			$Arme2Avion=$data['ArmeSecondaire'];
			$Viseur=$data['Viseur'];
		}
		mysqli_free_result($result);
	}	
	if($alt >100)$alt=100;		
	if($HP)
	{
		$moda=$HPmax/$HP;
		if($Avion_db =="Avion" and $Bombs and $Avion_Bombe)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Bombs));
			$moda*=(1+$charge_sup);
		}
		$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,0,$Pilotage);
		$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,0,$PlayerID,$Unite);
		$Speed=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$avion_img=GetAvionImg($Avion_db,$avion);
		$choix_pvp="<Input type='Radio' name='Action' value='99' checked>- Rentrer à la base.<br>";
		$Eni_PvP=GetData("Duels_Candidats","Target",$PlayerID,"ID");
		$Lieu_PvP=GetData("Duels_Candidats","Target",$PlayerID,"Lieu");
	}
	else
		$Action=98;
	if($Speed <50)$Action=98;	
	//Boost
	if($c_gaz ==130)
		UpdateData("Pilote","Stress_Moteur",10,"ID",$PlayerID);
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
	elseif($Eni_PvP and $Lieu_PvP ==$Cible)
	{
		$intro.="<p>Un ennemi vous prend en chasse,vous empêchant d'accomplir votre mission!</p>";
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
		$essence-=10;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Latitude,Longitude,Zone,ValeurStrat,Camouflage,Garnison,Recce,Fortification,DefenseAA_temp,Flag FROM Lieu WHERE ID='$Cible'")
		 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk-cible');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Lat_base=$data['Latitude'];
				$Long_base=$data['Longitude'];
				$Zone=$data['Zone'];
				$Flag=$data['Flag'];
				$ValStrat=$data['ValeurStrat'];
				$Camouflage_lieu=$data['Camouflage'];
				$Garnison=$data['Garnison'];
				$Recce_Lieu=$data['Recce'];
				$Fortification=$data['Fortification'];
				$DefenseAA=$data2['DefenseAA_temp'];
			}
			mysqli_free_result($result);
			unset($data);
		}		
		if($Mission_Type ==1 or $Mission_Type ==2 or $Mission_Type ==11)
		{
			$Atk_Mob=true;
			if(strpos($Action,"000_") !==false)
			{
				$Regi=strstr($Action,'000_',true);
				$tank=GetData("Regiment","ID",$Regi,"Vehicule_ID");
				$dca_unit_skill=GetData("Regiment","ID",$Regi,"Experience");
				$RR="Vehicule_Nbr";
			}
			elseif(strpos($Action,"000ia") !==false)
			{
				$Regi=strstr($Action,'000ia',true);
				$tank=GetData("Regiment_IA","ID",$Regi,"Vehicule_ID");
				$dca_unit_skill=GetData("Regiment_IA","ID",$Regi,"Experience");
				$RR="Vehicule_Nbr_ia";
			}
			if($tank >0)
			{
				//GetData Cible
				if($RR =="Vehicule_Nbr" or $RR =="Vehicule_Nbr_ia")
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Type,Blindage_f,Arme_AA,HP,Reput,Camouflage,mobile FROM Cible WHERE ID='$tank'")
					 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk-tank');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$nom_c='un '.$data['Nom'];
							$type_c=$data['Type'];
							$def_c=$data['Blindage_f'];
							$arme_c=$data['Arme_AA'];
							$hp_c=$data['HP'];
							$rep_c=$data['Reput'];
							$cam_c=51-$data['Taille'];
							$mobile=$data['mobile'];
						}
						mysqli_free_result($result);
						unset($data);
					}
				}
				else
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Type,Defense,Arme,HP,Reput,Camouflage,mobile FROM Cible WHERE ID='$tank'")
					 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk-tank');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$nom_c="un ".$data['Nom'];
							$type_c=$data['Type'];
							$def_c=$data['Defense'];
							$arme_c=$data['Arme'];
							$hp_c=$data['HP'];
							$rep_c=$data['Reput'];
							$cam_c=$data['Camouflage'];
							$mobile=$data['mobile'];
						}
						mysqli_free_result($result);
						unset($data);
					}
				}
			}
			else
				mail('binote@hotmail.com','Aube des Aigles: Attaque au sol error',"Cible : ".$tank." / Lieu : ".$Cible);
		}
		/*elseif($Cible_Atk >9)
		{
			if(strpos($Action,"000_") !==false)
			{
				$Regi=strstr($Action,'000_',true);
				$tank=GetData("Regiment","ID",$Regi,"Vehicule_ID");
				$dca_unit_skill=GetData("Regiment","ID",$Regi,"Experience");
				$RR="Vehicule_Nbr";			
				if($tank)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Type,Blindage_f,Arme_AA,Arme_AA2,Arme_AA3,HP,Reput,Camouflage,mobile FROM Cible WHERE ID='$tank'")
					 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : bombard-tank-naval');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$nom_c='un '.$data['Nom'];
							$type_c=$data['Type'];
							$def_c=$data['Blindage_f'];
							$arme_c=$data['Arme_AA'];
							$arme2_c=$data['Arme_AA2'];
							$arme3_c=$data['Arme_AA3'];
							$hp_c=$data['HP'];
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
				switch($Action)
				{
					case 10: case 6610:
						$nom_c="un cargo";
						$def_c=0;
						$arme_c=4;
						$hp_c=1000;
						$rep_c=2;
						$cam_c=10;
						$type_c=14;
						$camion=5;
						$tank=1;
					break;
					case 11: case 6611:
						$nom_c="une barge de transport";
						$def_c=5;
						$arme_c=17;
						$hp_c=1000;
						$rep_c=2;
						$cam_c=10;
						$type_c=14;
					break;
					case 12: case 6612:
						$nom_c="un patrouilleur";
						$def_c=10;
						$arme_c=17;
						$hp_c=3000;
						$rep_c=5;
						$cam_c=10;
						$type_c=14;
						$tank=2;
					break;
					case 13: case 6613:
						$nom_c="une corvette";
						$def_c=20;
						$arme_c=17;
						$hp_c=5000;
						$rep_c=10;
						$cam_c=0;
						$type_c=15;
						$tank=3;
					break;
					case 14: case 6614:
						$nom_c="une frégate";
						$def_c=25;
						$arme_c=14;
						$hp_c=7000;
						$rep_c=20;
						$cam_c=0;
						$type_c=16;
						$tank=4;
					break;
					case 15: case 6615:
						$nom_c="un destroyer";
						$def_c=30;
						$arme_c=14;
						$hp_c=10000;
						$rep_c=50;
						$cam_c=0;
						$type_c=17;
						$tank=5;
					break;
					case 16: case 6616:
						$nom_c="un croiseur léger";
						$def_c=50;
						$arme_c=15;
						$hp_c=25000;
						$rep_c=100;
						$cam_c=0;
						$type_c=18;
						$tank=6;
					break;
					case 17: case 6617:
						$nom_c="un croiseur lourd";
						$def_c=80;
						$arme_c=15;
						$hp_c=40000;
						$rep_c=200;
						$cam_c=0;
						$type_c=19;
					break;
					case 18: case 6618:
						$nom_c="un cuirassé";
						$def_c=120;
						$arme_c=15;
						$hp_c=100000;
						$rep_c=500;
						$cam_c=0;
						$type_c=20;
					break;
					case 19: case 6619:
						$nom_c="un porte-avions";
						$def_c=80;
						$arme_c=15;
						$hp_c=50000;
						$rep_c=500;
						$cam_c=0;
						$type_c=21;
					break;
					case 22:
						$nom_c="un sous-marin en surface";
						$def_c=10;
						$arme_c=14;
						$hp_c=5000;
						$rep_c=30;
						$cam_c=25;
						$type_c=37;
						$db_hp=5000;
						$tank=22;
					break;
					case 23:
						$nom_c="un sous-marin en plongée";
						$def_c=100;
						$arme_c=0;
						$hp_c=5000;
						$rep_c=30;
						$cam_c=75;
						$type_c=37;
						$db_hp=5000;
						$tank=23;
					break;
				}
				if($arme_c == 4)
				{
					if($Pays_cible ==1)
						$arme_c=35;
					elseif($Pays_cible == 2)
						$arme_c=38;
					elseif($Pays_cible == 4)
						$arme_c=29;
					elseif($Pays_cible == 6)
						$arme_c=44;
					elseif($Pays_cible == 7)
						$arme_c=17;
					elseif($Pays_cible == 8)
						$arme_c=66;
				}
				elseif($arme_c ==17)
				{
					if($Pays_cible ==1)
						$arme_c=31;
					elseif($Pays_cible == 2)
						$arme_c=17;
					elseif($Pays_cible == 4)
						$arme_c=43;
					elseif($Pays_cible == 6)
						$arme_c=46;
				}
				elseif($arme_c ==14)
				{
					if($Pays_cible ==1)
						$arme_c=23;
					elseif($Pays_cible == 2)
						$arme_c=50;
					elseif($Pays_cible == 4)
						$arme_c=49;
					elseif($Pays_cible == 6)
						$arme_c=46;
				}
				elseif($arme_c ==15)
				{
					if($Pays_cible ==1)
						$arme_c=15;
					elseif($Pays_cible == 2)
						$arme_c=51;
					elseif($Pays_cible == 4)
						$arme_c=53;
					elseif($Pays_cible == 6)
						$arme_c=54;
				}
				$tank+=5000;
				$Target_id="objectif_torpille".$Pays_cible.$Cible_Atk;
			}
		}*/
		else
		{
			if($Cible_Atk ==1)
			{
				$dca_unit_skill=0;
				if(strpos($Action,'99_') !==false) //Vérifie si la cible est une DCA,extrait l'ID de la pièce
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
					$result=mysqli_query($con,"SELECT Flak.DCA_ID,Flak.DCA_Exp FROM Flak WHERE Lieu='$Cible' AND (Alt BETWEEN '$Alt_Flak_min' AND '$Alt_Flak_max') ORDER BY RAND() LIMIT 1");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result))
						{
							$arme_c=$data['DCA_ID'];
							$dca_unit_skill=$data['DCA_Exp'];
						}
						mysqli_free_result($result);
					}
				}							
				if(!$dca_unit_skill)
				{
					$con=dbconnecti();
					$unites_tot=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit WHERE Base='$Cible' AND Etat=1"),0);
					$unites_ia=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit WHERE Base='$Cible' AND Etat=1 AND Commandant IS NULL AND Officier_Adjoint IS NULL AND Officier_Technique IS NULL"),0);
					mysqli_close($con);
					if($unites_tot >0 and $unites_ia ==$unites_tot)
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
					else
						$arme_c=0;
				}
				$cam_c=$Camouflage_lieu;
				/*$con=dbconnecti();
				$result=mysqli_query($con,"SELECT SUM(DCA),SUM(Pers1) FROM Unit WHERE Base='$Cible'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM))
					{
						$DCA =$data[0];
						$Artilleurs =$data[1];
					}
					mysqli_free_result($result);
				}
				if($DCA >5)
					$arme_c=15;
				elseif($DCA ==5)
					$arme_c=14;
				elseif($DCA ==4)
					$arme_c=23;
				elseif($DCA ==3)
					$arme_c=8;
				elseif($DCA ==2)
					$arme_c=3;
				elseif($DCA ==1)
					$arme_c=17;
				else
					$arme_c=13;
				$dca_unit_skill=$DCA*(10+$Artilleurs);*/
				switch($Action)
				{
					case 1:
						$nom_c="un avion parqué le long de la piste";
						$def_c=0;
						$hp_c=1500;
						$rep_c=3;
						$type_c=22;
						$avion_parque=true;
						$tank=1000;
					break;
					case 2:
						$nom_c="un emplacement de D.C.A";
						$def_c=10;					
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
						$hp_c=1000;
						$rep_c=3;
						$type_c=25;
						$hangar=true;
						$tank=1;
					break;
					case 4:
						$nom_c="la tour de contrôle";
						$def_c=20;
						$hp_c=5000;
						$rep_c=10;
						$type_c=26;
						$tank=2;
					break;
				}
			}
			elseif($Cible_Atk == 2)
			{
				switch($Action)
				{
					case 1:
						$nom_c="un entrepôt";
						$def_c=5;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,3);
						$hp_c=1000 + (1000*$ValStrat);
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
						$hp_c=500 + (500*$ValStrat);
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
						$hp_c=5000 + (5000*$ValStrat);
						$rep_c=15;
						$cam_c=0;
						$type_c=25;
						$usine=true;
						$tank=4;
					break;
					case 4:
						$nom_c="le bâtiment principal";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=10000 + (10000*$ValStrat);
						$rep_c=30;
						$cam_c=0;
						$type_c=27;
						$usine=true;
						$tank=5;
					break;
				}
			}
			elseif($Cible_Atk == 3)
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
						$hp_c=2000 + (2000*$ValStrat);
						$rep_c=2;
						$cam_c=0;
						$tank=6;
					break;
					case 4:
						$nom_c="le bâtiment principal";
						if($Fortification >10)
							$def_c=$Fortification;
						else
							$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=5000 + (5000*$ValStrat);
						$rep_c=5;
						$cam_c=0;
						$type_c=34;
						$caserne=true;
						$tank=7;
					break;
				}
			}
			elseif($Cible_Atk == 4)
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
						$hp_c=1000 + (1000*$ValStrat);
						$rep_c=3;
						$cam_c=0;
						$type_c=25;
						$depot=true;
						$tank=3;
					break;
					case 4:
						$nom_c="le bâtiment principal";
						$def_c=20;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=3000 + (3000*$ValStrat);
						$rep_c=15;
						$cam_c=0;
						$type_c=28;
						$gare=true;
						$tank=9;
					break;
				}
			}
			elseif($Cible_Atk == 5)
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
						$hp_c=500 + (500*$ValStrat);
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c="le pont,en enfilade";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=8000 + (8000*$ValStrat);
						$rep_c=25;
						$cam_c=0;
						$type_c=29;
						$pont=true;
						$tank=10;
					break;
					case 4:
						$nom_c="le pont,perpendiculairement";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=8000 + (8000*$ValStrat);
						$rep_c=25;
						$cam_c=0;
						$type_c=29;
						$pont=true;
						$tank=10;
					break;
				}
			}	
			elseif($Cible_Atk == 6)
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
						$hp_c=1500 + (1500*$ValStrat);
						$rep_c=5;
						$cam_c=0;
						$type_c=31;
						$port=true;
						$citerne=true;
						$tank=11;
					break;
					case 4:
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
			elseif($Cible_Atk == 7)
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
					case 3:
						$nom_c="le bâtiment principal";
						$def_c=50;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=10000 + (10000*$ValStrat);
						$rep_c=30;
						$cam_c=20;
						$radar=true;
						$tank=14;
					break;
					case 4:
						$nom_c="une antenne";
						$def_c=10;
						$arme_c=GetDCA($Pays_cible,$DefenseAA,2);
						$hp_c=4000 + (4000*$ValStrat);
						$rep_c=20;
						$cam_c=20;
						$radar=true;
						$tank=15;
					break;
				}
			}
			//$Target_id="bomb".$Pays_cible.$Cible_Atk.$Action;
		}
		if($HP_eni <1 or $Lock !=$Action)$HP_eni=$hp_c;
		//DCA
		if($arme_c !=5 and $arme_c >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Calibre,Multi,Degats FROM Armes WHERE ID='$arme_c'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$dca_cal=round($data['Calibre']);
					$dca_mult=$data['Multi'];
					$dca_degats=$data['Degats'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($RR =="Vehicule_Nbr" and $Regi)
				$DCA_mun=GetData("Regiment","ID",$Regi,"Stock_Munitions_".$dca_cal);
			else
				$DCA_mun=9999;			
			if($DCA_mun >=$dca_mult and $dca_cal >0)
			{
				if($RR =="Vehicule_Nbr" and $Regi)
				{
					UpdateData("Regiment","Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$Regi);
					AddEventGround(376,$avion,$PlayerID,$Regi,$Cible,$dca_mult,$arme_c);
				}
				$intro.="<br><b>La défense anti-aérienne rapprochée ouvre le feu sur vous!</b>";
				if($Target_id)
					$img=Afficher_Image('images/cibles/'.$Target_id.'.jpg',"images/image.png",$nom_c);
				else
					$img=Afficher_Image('images/cibles/cibles'.$tank.'_'.$Pays_cible.'.jpg','images/cibles/cibles'.$tank.'.jpg',$nom_c);					
				$dca_max=$rep_c*10;
				if($dca_max >250)$dca_max=250;		
				$Shoot_rand=mt_rand(10,50)+mt_rand(0,$dca_unit_skill)+mt_rand($rep_c,$dca_max);
				$Shoot=$Shoot_rand+$meteo+$VisAvion-($Pilotage/2)-($Speed/10)+$dca_mult+($S_Pass*25)-$AsZigZag;
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
				if($Shoot >10 or $Shoot_rand >250)
				{
					$Blindage=GetData($Avion_db,"ID",$avion,"Blindage");
					if(!$Blindage)
					{
						$Blindage=$S_Blindage;
						if(!$Blindage)
							$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
					}
					$Degats=round((mt_rand(1,$dca_degats)-pow($Blindage,2))*GetShoot($Shoot,$dca_mult));
					if($Degats <1)
						$Degats=mt_rand(1,10);
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
						$intro.='<br>L\'explosion met le feu à votre avion,ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
						$end_mission=true;
					}
					else
					{
						$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil,lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
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
					$intro.="<br>Les éclats d'obus encadrent votre appareil,mais vous parvenez à les éviter!";
					$reperage=true;
				}
			}
			else
			{
				$intro.="<br>La DCA rapprochée est étrangement silencieuse!";
				$reperage=true;
			}
		}
		else
		{
			$intro.="<br>La DCA rapprochée est étrangement silencieuse!";
			$reperage=true;
		}		
		//Repérage
		if($reperage and $tank >0)
		{
			if($Cible_Atk ==23)
			{
				$Radar=GetData($Avion_db,"ID",$avion,"Radar");
				$reperer=($Radar*100)+$meteo-$cam_c;
				if($reperer >0)
					UpdateCarac($Equipage,"Radar",1,"Equipage");
			}
			else
				$reperer=1;
			if($reperer >0)
			{
				$car_up=mt_rand(0,1);
				UpdateCarac($Equipage,"Vue",$car_up,"Equipage");
				/*if($Strike ==false and $Vue <100)
					UpdateCarac($PlayerID,"Vue",$car_up);*/
				$attaque=true;
			}
			else
			{
				$intro.="<br>Vous ne parvenez pas à repérer votre cible!";
				$seconde_passe=true;
				$attaque=false;
			}			
			//Enrayage
			if($Rafalet <80)
			{
				if($Mun ==2)
				{
					if(IsEnrayage($Arme2Avion,0,$PlayerID,"Stress_Arme2"))
					{
						$Mun2=0;
						$intro.="<br><b>Vos armes s'enrayent au plus mauvais moment!</b>";
						$attaque=false;
						$seconde_passe=true;
					}
					UpdateData("Pilote","Stress_Arme2",1,"ID",$PlayerID);
				}
				else
				{
					if(IsEnrayage($Arme1Avion,0,$PlayerID,"Stress_Arme1"))
					{
						$Mun1=0;
						$intro.="<p>Vos armes s'enrayent au plus mauvais moment!</p>";
						$attaque=false;
						$seconde_passe=true;
					}
					UpdateData("Pilote","Stress_Arme1",1,"ID",$PlayerID);
				}
			}
			else
				$ArmeAvion=177;
		}
		else
		{
			$intro.="<br>Vous ne parvenez pas à repérer votre cible!";
			$seconde_passe=true;
			$attaque=false;
		}			
		if($attaque)
		{
			//Sable
			if($Avion_db =="Avions_Persos")
			{
				$Moteur=GetData($Avion_db,"ID",$avion,"Moteur");
				if($Zone ==8 and $Moteur !=7)
				{
					$Stress=floor($c_gaz/5);
					UpdateData("Pilote","Stress_Moteur",$Stress,"ID",$PlayerID);
					$intro.="<p>Du sable encrasse votre moteur!</p>";
				}
			}
			$ArmeAvion_Multi=GetData("Armes","ID",$ArmeAvion,"Multi");
			$ArmeAvion_Dg=GetData("Armes","ID",$ArmeAvion,"Degats");
			$Stab=GetStab($Avion_db,$avion,$HP)*$Steady;
			if($ArmeAvion_Multi <1)$ArmeAvion_Multi=1;
			//Bonus Viseur
			switch($Viseur)
			{
				case 0:
					$Bonus_Viseur=0;
				break;
				case 1:
					$Bonus_Viseur=0;
				break;
				case 2:
					$Bonus_Viseur=10;
				break;
				case 3:
					$Bonus_Viseur=0;
				break;
				case 4:
					$Bonus_Viseur=20;
				break;
			}
			//Rafale
			switch($Rafalet)
			{
				case 2:
					$Mult_Rafale=0.5;
					$Mun_Rafale=4; //2
				break;
				case 3:
					$Malus=10+($ArmeAvion_nbr*10);
					$Mult_Rafale=1;
					$Mun_Rafale=10; //5
				break;
				case 80:
					$Malus=20;
					$Mult_Rafale=1;
					$Mun_Rafale=0;
					$ArmeAvion_nbr=2;
					$Avion_Mun=4;
					UpdateData("Pilote","S_Avion_Bombe_Nbr",-2,"ID",$PlayerID);
					$Bombs -= 2;
				break;
				case 81:
					$Malus=10+($Bombs*10);
					$Mult_Rafale=1;
					$Mun_Rafale=0;
					$ArmeAvion_nbr=$Bombs;
					$Avion_Mun=4;
					SetData("Pilote","S_Avion_Bombe_Nbr",0,"ID",$PlayerID);
					$Bombs=0;
				break;
			}			
			if($Atk_Mob)$Malus+=$def_c;
			$Shoot=mt_rand(0,$Tir)+($Stab/10)+($meteo/2)+($Courage/10)+($Moral/10)-($Speed/10)+$Bonus_Viseur-$Malus;		
			//JF
			if($PlayerID ==1)
			{
				$skills.="<br>[Tir : ".$Shoot."]
									<br>+Viseur ".$Bonus_Viseur."
									<br>+Stab ".$Stab." /10
									<br>-meteo ".$meteo." /2
									<br>-Speed ".$Speed." /10
									<br>-Malus ".$Malus."
									<br>+Courage/10 +Moral/10";
			}
			//End JF
			if($Mun ==1)
			{
				$Mun1-=($ArmeAvion_Multi*$ArmeAvion_nbr*$Mun_Rafale);
				if($Mun1 <0)$Mun1=0;
			}
			else
			{
				$Mun2-=($ArmeAvion_Multi*$ArmeAvion_nbr*$Mun_Rafale);
				if($Mun2 <0)$Mun2=0;
			}
			if(date("H") <7)//pas d'attaque canadienne
			{
				if(mt_rand(0,100) >10)$Shoot=0;
			}
			if($Shoot >0)
			{
				$Degats=0;
				$Arme_Cal=round(GetData("Armes","ID",$ArmeAvion,"Calibre"));
				if(!$Avion_Mun)$Avion_Mun=GetData("Pilote","ID",$PlayerID,"S_Avion_Mun");
				if($Avion_Mun ==1)
				{
					if($Arme_Cal<20)
						$Arme_Cal*=2;
					else
						$Arme_Cal*=(2+($Arme_Cal/10));
				}
				if($Arme_Cal >$def_c)
				{
					//Bonus dégâts incendiaire
					if($citerne)
						$dmg_bonus_cible=4;
					else
						$dmg_bonus_cible=1;
					$Bonus_Dg=Damage_Bonus($Avion_db,$avion,$dmg_bonus_cible,$ArmeAvion,$def_c,$Avion_Mun);
					$ArmeAvion_nbr=GetShoot($Shoot,$ArmeAvion_nbr);
					for($i=1;$i<=$ArmeAvion_nbr;$i++)
					{
						$Degats+=round(($ArmeAvion_Dg+$Bonus_Dg-$def_c)*mt_rand(1,$ArmeAvion_Multi)*$Mult_Rafale);
					}
					if($Degats <1)$Degats=1;
					$intro.='<p>Votre tir fait mouche! (<b>'.$Degats.'</b> dégâts)</p>';
				}
				else
				{
					$Degats=mt_rand(1,$ArmeAvion_nbr);
					$intro.='<p>Votre tir fait mouche,mais vos projectiles ricochent sur le blindage! (<b>'.$Degats.'</b> dégâts)</p>';
				}
				$HP_eni-=$Degats;
				if($Formation >1 and $RR !="Vehicule_Nbr" and $RR !="Vehicule_Nbr_ia")
				{
					$Degats_f=0;
					$Formation_abattue=0;
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT p.ID,p.Nom,p.Tir,p.Pilotage,p.Moral,p.Courage,p.Avion,a.Stabilite,a.VitesseB,a.Visibilite,a.Blindage,a.Robustesse,w.Degats,w.Multi,w.Calibre,
					u.Mission_Flight,u.Avion1_Bombe,u.Avion2_Bombe,u.Avion3_Bombe,u.Avion1_Mun1,u.Avion2_Mun1,u.Avion3_Mun1,u.Avion1,u.Avion2,u.Avion3
					FROM Pilote_IA as p,Unit as u,Avion as a,Armes as w WHERE p.Avion=a.ID AND p.Unit=u.ID AND a.ArmePrincipale=w.ID AND p.Unit='$Unite' AND p.Cible='$Cible' AND p.Actif=1");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$F_shoot=false;
							$hit_ia=false;
							$Pilote_ia_f=$data['ID'];
							$Tir_f=$data['Tir'];
							$Flight_f=$data['Mission_Flight'];
							if($Rafalet >79) //Rockets
							{
								if(($data['Mission_Flight'] ==3 and $data['Avion3_Bombe'] ==80) 
								or ($data['Mission_Flight'] ==2 and $data['Avion2_Bombe'] ==80)
								or ($data['Mission_Flight'] ==1 and $data['Avion1_Bombe'] ==80))
									$F_shoot=true;
							}
							else
								$F_shoot=true;
							if($F_shoot and $data['Calibre'] >$def_c)
							{
								$Shoot_f=mt_rand(0,$Tir_f)+($data['Stabilite']/10)+($meteo/2)+($data['Courage']/10)+($data['Moral']/10)-($data['VitesseB']/10)-$Malus;
								if($Shoot_f >0)
								{
									if($data['Mission_Flight'] ==3)
										$Avion_Mun_f=$data['Avion3_Mun1'];
									elseif($data['Mission_Flight'] ==2)
										$Avion_Mun_f=$data['Avion2_Mun1'];
									else
										$Avion_Mun_f=$data['Avion1_Mun1'];
									$Bonus_Dg_f=Damage_Bonus("Avion",$data['Avion'],1,$data['ArmePrincipale'],$def_c,$Avion_Mun_f);
									$Degats_solo_f=round(($data['Degats']+$Bonus_Dg_f-$def_c)*mt_rand(1,$data['Multi'])*$Mult_Rafale);
									if($Shoot_rand >0)
									{
										if(!$arme_c)
										{
											$arme_c=17;
											$dca_mult=24;
										}
										$Shoot_dca_ia=$Shoot_rand+$meteo+$data['Visibilite']-($data['Pilotage']/10)-($data['VitesseB']/10)+$dca_mult;
										//$mail_debug.='<br>'.$data['Nom'].' Shoot_dca_ia '.$Shoot_dca_ia.'=Shoot_rand '.$Shoot_rand.' + meteo '.$meteo.' + Vis '.$data['Visibilite'].' + dca_mult '.$dca_mult.' - Pilotage/10 '.$data['Pilotage'].' - VitesseB/10 '.$data['VitesseB'];
										if($Shoot_dca_ia >0)
										{
											if(!$data['Blindage'])$data['Blindage']=GetData("Unit","ID",$Unite,"U_Blindage");
											$Degats=round((mt_rand(1,GetData("Armes","ID",$arme_c,"Degats"))-pow($data['Blindage'],2))*GetShoot($Shoot_dca_ia,$dca_mult));
											$mail_debug.=' <'.$Degats.'/'.$data['Robustesse'].'>';
											if($Degats >$data['Robustesse'])
											{
												AddEvent("Avion",179,$data['Avion'],$Pilote_ia_f,$Unite,$Cible,4,$Pays_eni);
												$intro.="<br>L'avion de ".$data['Nom']." s'abat en flamme,touché par la DCA!";
												if($data['Avion'] == $data['Avion1'])
													$Avion1_Nbr_dca+=1;
												elseif($data['Avion'] ==$data['Avion2'])
													$Avion2_Nbr_dca+=1;
												elseif($data['Avion'] ==$data['Avion3'])
													$Avion3_Nbr_dca+=1;
												$Formation-=1;
												$Formation_abattue+=1;
												$con=dbconnecti();
												$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0 WHERE ID='$Pilote_ia_f'");
												mysqli_close($con);
												if(!$Discipline_fer or mt_rand(0,1) >0)
													WoundPilotIA($Pilote_ia_f);
												$hit_ia=true;
											}
											else
												$intro.="<br>La DCA endommage l'avion de ".$data['Nom'].",mais il peut poursuivre sa mission!";
										}
									}
									if($Degats_solo_f >0 and !$hit_ia)
									{
										$intro.="<br>".$data['Nom']." endommage la cible! (<b>".$Degats_solo_f."</b> dégâts)";
										$Degats_f += $Degats_solo_f;
										$con=dbconnecti();
										$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Points=Points+'$rep_c',Reputation=Reputation+'$rep_c',Avancement=Avancement+'$rep_c',Courage=Courage+1,Moral=Moral+2 WHERE ID='$Pilote_ia_f'");
										mysqli_close($con);
									}
								}
							}
						}
						mysqli_free_result($result);
					}
					if($Formation_abattue >0)
					{
						//mail('binote@hotmail.com','Aube des Aigles: attaque DCA IA',$mail_debug);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Unit SET Avion1_Nbr=Avion1_Nbr-'$Avion1_Nbr_dca',Avion2_Nbr=Avion2_Nbr-'$Avion2_Nbr_dca',Avion3_Nbr=Avion3_Nbr-'$Avion3_Nbr_dca',Reputation=Reputation-'$Formation_abattue' WHERE ID='$Unite'");
						mysqli_close($con);
						SetData("Pilote","S_Formation",$Formation,"ID",$PlayerID);
					}
					if($Degats_f >0)
						$intro.='<p>Votre formation endommage la cible! (<b>'.$Degats_f.'</b> dégâts totaux)</p>';
					else
						$intro.='<p>Les pilotes de votre formation ne parviennent pas à toucher la cible!</p>';
				}
				$HP_eni-=$Degats_f;				
				if($PlayerID ==1)
					$intro.='<br> HP eni='.$HP_eni;				
				if($HP_eni <0 and $Simu)
				{
					//$Target_id=substr($nom_c,3);
					if($Cible_Atk >9)
						$img=Afficher_Image('images/explosion_navire'.$type_c.'.jpg',"images/image.png","Explosion Navire");
					else
						$img="<img src='images/explosion.jpg' style='width:100%;'>";
					//Modif Lieu
					$date=GetData("Conf_Update","ID",2,"Date");
					if($dca)
					{
						AddEvent($Avion_db,13,$avion,$PlayerID,$Unite,$Cible);
						UpdateData("Lieu","DefenseAA_temp",-1,"ID",$Cible);
						$msghit="<br>Votre rafale détruit le canon anti-aérien!";
					}
					elseif($dca_unit)
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
						$msghit="<br>Votre rafale détruit le canon anti-aérien!";
						UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
					}
					elseif($gare)
					{
						//AddEvent($Avion_db,15,$avion,$PlayerID,$Unite,$Cible,$Damage);
						$Damage=floor(0-($rep_c/10));
						UpdateData("Lieu","NoeudF",$Damage,"ID",$Cible);
						$msghit="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
						//if($Target_id =="bâtiment principal")$Target_id="gare";
						unset($Damage);
					}
					elseif($usine)
					{
						//AddEvent($Avion_db,16,$avion,$PlayerID,$Unite,$Cible,$Damage);
						$Damage=floor(0-($rep_c/10));
						UpdateData("Lieu","Industrie",$Damage,"ID",$Cible);
						$msghit="<br>Votre attaque diminue le potentiel de production de l'ennemi!";
						//if($Target_id =="bâtiment principal")$Target_id="usine";
						unset($Damage);
					}
					elseif($caserne)
					{
						//AddEvent($Avion_db,71,$avion,$PlayerID,$Unite,$Cible,$Damage);
						$msghit="<br>Votre attaque diminue le moral des troupes de l'ennemi!";
					}
					elseif($pont)
					{
						//AddEvent($Avion_db,17,$avion,$PlayerID,$Unite,$Cible,$Damage);
						SetData("Lieu","Pont",-10,"ID",$Cible);
						$msghit.="<br>Le pont est endommagé!";
					}
					elseif($port)
					{
						//AddEvent($Avion_db,29,$avion,$PlayerID,$Unite,$Cible,$Damage);
						$Damage=floor(0-($rep_c/10));
						UpdateData("Lieu","Port",$Damage,"ID",$Cible);
						$msghit="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
						if($citerne)
						{
							if($Cible ==343)
							{
								$Flag_343=GetData("Lieu","ID",343,"Flag");
								$query="SELECT ID FROM Lieu WHERE Flag IN(2,'$Flag_343') AND Latitude <43 AND Longitude < 60 AND Zone<>6";
							}
							elseif($Cible ==344)
							{
								$Flag_344=GetData("Lieu","ID",344,"Flag");
								$query="SELECT ID FROM Lieu WHERE Flag IN(2,'$Flag_343') AND Latitude <43 AND Longitude < 60 AND Zone<>6";
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
						$Damage=floor(0-($rep_c/10));
						UpdateData("Lieu","Radar",$Damage,"ID",$Cible);
						$msghit="<br>Votre attaque diminue le potentiel de détection de l'ennemi!";
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
						//Si pas d'unité sur cette base,pas de message de perte de stock
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
						$msghit="<br>Votre rafale détruit un hangar,réduisant les stocks de l'ennemi!";
						UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
					}
					elseif($depot)
					{
						//Si pas d'unité sur cette base,pas de message de perte de stock
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
						AddEvent($Avion_db,114,$avion,$PlayerID,$Unite,$Cible,1,$stock_qty);
						UpdateData("Lieu",$stock,$stock_qty,"ID",$Cible);
						$msghit="<br>Votre attaque détruit un entrepôt,réduisant les stocks de l'ennemi!";
						UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
					}
					elseif($avion_parque)
					{
						$Avion_det=44; //Ju-52 par défaut si l'aérodrome n'est pas rattaché à une unité
						//$Avion_txt="Avion non identifié";
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT DISTINCT ID,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE Base='$Cible' AND Etat=1 ORDER BY RAND() LIMIT 1");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								if($data['Avion1_Nbr'] >0)
								{
									UpdateData("Unit","Avion1_Nbr",-1,"ID",$data['ID']);
									$Avion_txt=GetData("Avion","ID",$data['Avion1'],"Nom");
									$Avion_det=$data['Avion1'];
									//mail('binote@hotmail.com','Aube des Aigles: Attack Report',"Mission Attaque sur cible ".$Cible." : avion ".$data['Avion1']." détruit");
								}
								elseif($data['Avion2_Nbr'] >0)
								{
									UpdateData("Unit","Avion2_Nbr",-1,"ID",$data['ID']);
									$Avion_txt=GetData("Avion","ID",$data['Avion2'],"Nom");
									$Avion_det=$data['Avion2'];
									//mail('binote@hotmail.com','Aube des Aigles: Attack Report',"Mission Attaque sur cible ".$Cible." : avion ".$data['Avion2']." détruit");
								}
								elseif($data['Avion3_Nbr'] >0)
								{
									UpdateData("Unit","Avion3_Nbr",-1,"ID",$data['ID']);
									$Avion_txt=GetData("Avion","ID",$data['Avion3'],"Nom");
									$Avion_det=$data['Avion3'];
									//mail('binote@hotmail.com','Aube des Aigles: Attack Report',"Mission Attaque sur cible ".$Cible." : avion ".$data['Avion3']." détruit");
								}
								else
								{
									$Avion_txt="avion";
									$rep_c=1;
									//mail('binote@hotmail.com','Aube des Aigles: Erreur Select Avions : No Planes',"Mission Attaque sur cible ".$Cible." : ".$data['Avion1']."/".$data['Avion2']."/".$data['Avion3']);
								}
							}
							mysqli_free_result($result);
							unset($result);
						}
						else
						{
							$Avion_txt="avion";
							$rep_c=1;
							//mail('binote@hotmail.com','Aube des Aigles: Erreur Select Avions : No Unit' ,'Mission Attaque sur cible '.$Cible.'.'.mysqli_error($con));
						}
						AddEvent("Avion",142,$Avion_det,$PlayerID,$Unite,$Cible,1);
						$msghit="<br>Votre rafale détruit un ".$Avion_txt." au sol!";
						//$Target_id=$Avion_txt." au sol";
						$tank=10000+$Avion_det;
						UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
					}
					elseif($citerne)
					{
						$msghit='<p>Votre tir fait mouche,vous détruisez '.$nom_c.'</p>';
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
						unset($resultl);
					}
					elseif($camion)
					{
						$msghit='<p>Votre tir fait mouche,vous détruisez '.$nom_c.'</p>';
						//UpdateData("Lieu","Camions",$camion,"ID",$Cible);
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
						unset($resultl);
					}
					elseif($Cible_Atk ==3 and $tank ==48)
					{
						$Kills=floor($Degats/100);
						if($Kills >50)$Kills=50;
						if($Kills >$Garnison)$Kills=$Garnison;
						UpdateData("Lieu","Garnison",-$Kills,"ID",$Cible);
						if($Kills >100)
							$msghit="<p>Votre attaque dévastatrice met hors de combat des dizaines de soldats de la garnison</p>";
						elseif($Kills >50)
							$msghit="<p>Votre attaque précise met hors de combat une bonne partie des soldats de la garnison</p>";
						elseif($Kills >10)
							$msghit="<p>Votre attaque met hors de combat quelques soldats de la garnison</p>";
						else
							$msghit="<p>Votre attaque imprécise n'entame pas la détermination de la garnison</p>";
					}
					else
						$msghit='<p>Votre tir fait mouche,vous détruisez '.$nom_c.'</p>';
					//Navires et troupes au sol
					if($mobile and $RR)
					{
						if($RR =="Vehicule_Nbr")
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT r.Vehicule_Nbr,r.Pays,r.Fret_Qty,r.Fret,o.Transit FROM Regiment as r,Officier as o WHERE r.Officier_ID=o.ID AND r.ID='$Regi'");
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
									$Vehicule_Nbr_regi=$data['Vehicule_Nbr'];
									$Pays_cible=$data['Pays'];
									$Transit=$data['Transit'];
									$Fret=$data['Fret'];
									$Fret_Qty=$data['Fret_Qty'];
								}
								mysqli_free_result($result);
							}
							if($Transit >0)
							{
								$con=dbconnecti();
								$reset=mysqli_query($con,"UPDATE Regiment SET Experience=0,Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
								Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
								Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Visible=0 WHERE Officier_ID='$Transit' AND Vehicule_Nbr>0 ORDER BY RAND() LIMIT 1");
								mysqli_close($con);
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
										$con=dbconnecti();
										$reset=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0,HP=0,Position=6 WHERE ID='$Regi'");
										$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Moral=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Experience=0,Skill=0,Visible=0 WHERE ID='$Fret_Qty'");
										mysqli_close($con);
									}
								}
								elseif($Fret_Qty >0)
								{
									$Perte_Stock=$Fret_Qty/$Vehicule_Nbr_regi;
									UpdateData("Regiment","Fret_Qty",-$Perte_Stock,"ID",$Regi);
								}
							}
							UpdateData("Regiment",$RR,-1,"ID",$Regi);
							SetData("Regiment","Visible",0,"ID",$Regi);
							AddEventGround(403,$avion,$PlayerID,$Regi,$Cible,1,$tank);
							$rep_c*=2;
							$recce_tac=true;
						}
						elseif($RR =="Vehicule_Nbr_ia")
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT Vehicule_Nbr,Pays FROM Regiment_IA WHERE ID='$Regi'");
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
									$Vehicule_Nbr_regi=$data['Vehicule_Nbr'];
									$Pays_cible=$data['Pays'];
								}
								mysqli_free_result($result);
							}
							if($Vehicule_Nbr_regi)
								UpdateData("Regiment_IA","Vehicule_Nbr",-1,"ID",$Regi);
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
									$HP_new=$hp_c;
									$Nbr_end=$Vehicule_Nbr_regi-1;
									$query_reset_ia="UPDATE Regiment_IA SET Position=8,HP='$HP_new',Vehicule_Nbr='$Nbr_end',Visible=0 WHERE ID='$Regi'";
								}
								$con=dbconnecti();
								$reset=mysqli_query($con,$query_reset_ia);;
								mysqli_close($con);
							}
							elseif($Vehicule_Nbr_regi)
								SetData("Regiment_IA","Visible",0,"ID",$Regi);
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
					//Tableau de chasse
					if($Simu)AddVictoire_atk($Avion_db,$type_c,$tank,$avion,$PlayerID,$Unite,$Cible,$ArmeAvion,$Pays_cible,0,$alt,$Nuit,$Degats);
					//Reput missions unité
					if($Simu)
					{	
						if(IsWar($Pays_cible,$country))
						{
							if($rep_c >1)
							{
								//Mission_Historique
								if($Cible ==$BH_Lieu)
								{
									if(IsAxe($country))
										$Points_cat="Points_Axe";
									else
										$Points_cat="Points_Allies";
									UpdateData("Event_Historique",$Points_cat,$rep_c,"ID",$_SESSION['BH_ID']);
									UpdateData("Unit","Reputation",$rep_c,"ID",$Unite,0,7);
								}
								UpdateCarac($PlayerID,"Victoires_atk",$rep_c);
								UpdateCarac($PlayerID,"Reputation",$rep_c);
								UpdateCarac($PlayerID,"Avancement",$rep_c);
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
								//Seules les cibles importantes valident la mission
								if($rep_c >1)
								{
									//Doubler la récompense en cas de mission demandée
                                    if($tank >4999)$dem_naval=",11";
									$con=dbconnecti();
									$result=mysqli_query($con,"SELECT ID FROM Unit WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D IN (1,6,11)");
									//$result2=mysqli_query($con,"SELECT ID FROM Officier WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D IN (1,6,11)");
                                    $resultdem=mysqli_query($con,"SELECT ID FROM Regiment_IA WHERE Mission_Lieu_D='$Cible' AND Mission_Type_D IN (1,6".$dem_naval.")");
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
                                    if($result)
									{
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
										{
											UpdateData("Unit","Reputation",$base_pts,"ID",$data['ID'],0,8);
										}
										mysqli_free_result($result);
										UpdateData("Unit","Reputation",$base_pts,"ID",$Unite,0,8);
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
										UpdateData("Unit","Reputation",$base_pts,"ID",$Unite);
										UpdateCarac($PlayerID,"Note",1);
										$Pts_Bonus+=1;
									}*/									
									if(!$Front)$Front=GetData("Pilote","ID",$PlayerID,"Front");
									/*if($Cible ==GetData("Unit","ID",$Unite,"Mission_Lieu"))
									{
										$Cdt=GetData("Unit","ID",$Unite,"Commandant");
										if($Cdt)
										{
											UpdateCarac($Cdt,"Reputation",10);
											UpdateCarac($Cdt,"Avancement",5);
											UpdateCarac($Cdt,"Commandement",5);
										}
										UpdateData("Unit","Reputation",10,"ID",$Unite);
									}*/
									if($Cible ==GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unite,"Type")))
									{
										$Cdt=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
										if($Cdt)
										{
											UpdateCarac($Cdt,"Reputation",5);
											UpdateCarac($Cdt,"Avancement",10);
										}
										UpdateData("Unit","Reputation",10,"ID",$Unite,0,7);
									}
									$msghit.="<p><b>Vous avez accompli votre mission!</b></p>";
									$rep_c+=10;
									$rep_c*=$Pts_Bonus;
									if($rep_c >50)$rep_c=50;
									UpdateCarac($PlayerID,"Missions",$rep_c);
									UpdateCarac($PlayerID,"Reputation",$rep_c);
									UpdateCarac($PlayerID,"Avancement",$rep_c);									
									if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
									{
										UpdateCarac($Equipage,"Missions",1,"Equipage");
										UpdateCarac($Equipage,"Avancement",10,"Equipage");
										UpdateCarac($Equipage,"Reputation",15,"Equipage");
									}
									if(!$Nuit and $Mission_Type !=31)
									{
										//Reput Chasseurs escorte
										$con=dbconnecti();
										$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
										$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Points=Points+20,Reputation=Reputation+20,Avancement=Avancement+20,Points=Points+20,Moral=Moral+10 WHERE Escorte='$Cible' AND Pays='$country'");
										$result=mysqli_query($con,"SELECT DISTINCT j.ID,j.Unit,j.Pays,u.Mission_Lieu,u.Commandant FROM Pilote as j,Pays as p,Unit as u
										WHERE j.Escorte='$Cible' AND j.Unit<>'$Unite' AND j.Pays=p.ID AND j.Unit=u.ID AND p.Faction='$Faction'");
										mysqli_close($con);
										if($result)
										{
											while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
											{
												/*Reput missions unité
												if($Cible ==$data['Mission_Lieu'])
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
													UpdateData("Event_Historique",$Points_cat,20,"ID",$_SESSION['BH_ID']);
													UpdateCarac($data['ID'],"Batailles_Histo",1);
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
										$Faction=GetData("Pays","ID",$country,"Faction");
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
										$Bonus_Recce_PID=10+($Valstrat*2);
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
										else*/if($Cible == GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unit_Recce,"Type")))
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
								SetData("Lieu","Last_Attack",$date,"ID",$Cible);
							}
						}
						else
						{
							$msghit="<p>Vous attaquez des cibles alliées!</p>";
							/*if($Cible == GetData("Unit","ID",$Unite,"Mission_Lieu"))
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
					$seconde_passe=true;
				}
				else
				{
					$msghit="<p>Votre tir manque de puissance,vous ne parvenez pas à détruire votre cible!</p>";
					$seconde_passe=true;
				}	
				
			}
			else
			{
				$msghit="<p>Votre tir est inefficace,manquant de précision!</p>";
				$seconde_passe=true;
			}
			$intro.=$msghit;
		}
	}
	$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
	SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
	//Si le site a été camouflé entre temps,réinitialiser le pilote de reco
	if(!$Recce_Lieu and !$Cible)
		SetData("Lieu","Recce_PlayerID",0,"ID",$Cible);		
	if($seconde_passe and !$end_mission)
	{
		UpdateData("Pilote","S_Pass",1,"ID",$PlayerID);
		if(!$img)
			$img=Afficher_Image('images/strafing'.$country.'.jpg',"images/image.png","Strafing");
		if($Arme1Avion !=5 and $Mun1 >0 and !$Eni_PvP and $Lieu_PvP !=$Cible)
		{
			$Arme1_Nom=GetData("Armes","ID",$Arme1Avion,"Nom");
			$Arme1_txt="<Input type='Radio' name='Action' value='3' checked>- Tenter une passe supplémentaire à l'aide de vos ".$Arme1_Nom." (reste ".$Mun1." coups).<br>";
		}
		if($Arme2Avion !=5 and $Arme2Avion !=25 and $Arme2Avion !=26 and $Arme2Avion !=27 and $Mun2 >0 and !$Eni_PvP and $Lieu_PvP !=$Cible)
		{
			$Arme2_Nom=GetData("Armes","ID",$Arme2Avion,"Nom");
			$Arme2_txt="<Input type='Radio' name='Action' value='13' checked>- Tenter une passe supplémentaire à l'aide de vos ".$Arme2_Nom." (reste ".$Mun2." coups).<br>";
		}
		if($Bombs >0 and $Avion_Bombe ==80)
			$Rockets_txt="<Input type='Radio' name='Action' value='80' checked>- Tenter une passe supplémentaire à l'aide de vos roquettes (reste ".$Bombs." coups).<br>";
		if($Mission_Type !=14)$Lock=$Action;
		$mes.="<form action='bomb.php' method='post'>
		<input type='hidden' name='Avion' value=".$avion.">
		<input type='hidden' name='Meteo' value=".$meteo.">
		<input type='hidden' name='Mun1' value=".$Mun1.">
		<input type='hidden' name='Mun2' value=".$Mun2.">
		<input type='hidden' name='Puissance' value=".$Puissance.">
		<input type='hidden' name='HP_eni' value=".$HP_eni.">
		<input type='hidden' name='Pays_eni' value=".$Pays_cible.">
		<input type='hidden' name='Cible_lock' value=".$Lock.">
		<table class='table'>
			<tr><td colspan='8'>Attaque au sol</td></tr>
			<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,8)."
				<td align='left'>
					".$Arme1_txt.$Arme2_txt.$choix_pvp."
				</td></tr></table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";		
	}
	if($retour and !$end_mission)
	{
		if($Zone ==6)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE ID='$Cible'");
			mysqli_close($con);
		}
		Chemin_Retour();
		$chemin=$Distance;
		$intro.='<br>Vous prenez le chemin du retour en direction de votre base,située à '.$Distance.'km';
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
			<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt)."
			</tr></table>
		<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	if($end_mission)
	{
		RetireCandidat($PlayerID,"end_mission");
		$avion_img=GetAvionImg($Avion_db,$avion);
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
				$intro.="<br>Sans gilet de sauvetage,vous êtes emporté par la mer jusqu'au rivage!";
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
				$Blessure_txt="<br><br>Vous êtes blessé,mais néanmoins en vie!";
				$Hard=1;
				$Malus_Moral=-50;
				AddEvent($Avion_db,9,$avion,$PlayerID,$Unite,$Cible);
				$blesse=1;
				DoBlessure($PlayerID,1);
			break;
			case 2:
				$Blessure_txt="<p>Vous gisez étendu sur le sol,mortellement blessé.</p>";
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
		UpdateCarac($PlayerID,"Moral",$Malus_Moral);
		if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
		{
			$Blessure_max=10;
			$Trait_e=GetData("Equipage","ID",$Equipage,"Trait");
			if($Trait_e ==3)$Blessure_max=20;
			$Blessure=mt_rand(0,$Blessure_max);
			if($Blessure <1)UpdateCarac($Equipage,"Endurance",-1,"Equipage");
			UpdateCarac($Equipage,"Moral",-25,"Equipage");
		}
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