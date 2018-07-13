<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
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
$Battle=Insec($_POST['Battle']);
$Faction=Insec($_POST['Camp']);
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $_SESSION['attaquer'] ==false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_inc_pvp.php');
	$_SESSION['cibler']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['attaquer']=true;
	$Distance=$_SESSION['Distance'];
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
	$Atk_Mob=false;
	$dca_unit_skill=10;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Equipage,Pilotage,Tir,Courage,Moral,S_Avion_db,S_Cible,S_Mission,S_Cible_Atk,S_Strike,S_Longitude,S_Latitude,S_Essence,Simu,S_Blindage,S_Pass,
	S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Equipage_Nbr,S_Engine_Nbr,S_Nuit,S_Formation,Slot5,Slot10,Slot11 FROM Pilote_PVP WHERE ID='$Pilote_pvp'")
	 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk_pvp-player');
	mysqli_close($con);
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
	if($Equipage)
		$Endu_Eq=GetData("Equipage_PVP","ID",$Equipage,"Endurance");		
	if($Slot11 ==69)
	{
		$Moral+=50;
		$Courage+=50;
	}
	$con=dbconnecti();
	$meteo=mysqli_result(mysqli_query($con,"SELECT Meteo FROM Lieu WHERE ID='$Cible'"),0);
	$result=mysqli_query($con,"SELECT Robustesse,Type,Masse,ArmePrincipale,ArmeSecondaire,Blindage FROM Avion WHERE ID='$avion'")
	 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk_pvp-avion');
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
			$Blindage=$data['Blindage'];
		}
		mysqli_free_result($result);
	}	
	if($alt >100)$alt=100;		
	if($HP)
	{
		$moda=$HPmax/$HP;
		if($Bombs and $Avion_Bombe)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Bombs));
			$moda*=(1+$charge_sup);
		}
		$VisAvion=GetVis("Avion",$avion,$Cible,$meteo,$alt,0,$Pilote_pvp,0);
		$Speed=GetSpeed("Avion",$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$avion_img=GetAvionImg("Avion",$avion);
		$choix_pvp="<Input type='Radio' name='Action' value='99' checked>- Rentrer à la base.<br>";
		$Eni_PvP=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"ID");
		$Lieu_PvP=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"Lieu");
	}
	else
		$Action=98;
	if($Speed <50)$Action=98;
	$Pays_cible=GetFlagPVP($Battle,$Faction);
	//Boost
	if($c_gaz ==130)
		UpdateData("Pilote_PVP","Stress_Moteur",10,"ID",$Pilote_pvp);
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
	elseif($Eni_PvP and $Lieu_PvP == $Cible)
	{
		$intro.="<p>Un ennemi vous prend en chasse,vous empêchant d'accomplir votre mission!</p>";
		$img=Afficher_Image("images/facetoface.jpg",'images/avions/vol'.$avion_img.'.jpg',$nom_avion);
		$chemin=0;
		$_SESSION['done']=false;
		$_SESSION['PVP']=true;
		if(!GetData("Duels_Candidats_PVP","PlayerID",$Pilote_pvp,"ID"))
			AddCandidatPVP("Avion",$Pilote_pvp,$avion,$HP,$Puissance,$essence,$chemin,$Distance,$Mun1,$Mun2,$alt,$Cible,$Nuit);
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
				$tank=GetData("Regiment_PVP","ID",$Regi,"Vehicule_ID");
				$dca_unit_skill=GetData("Regiment_PVP","ID",$Regi,"Experience");
				$RR="Vehicule_Nbr";
			}
			elseif(strpos($Action,"000ia") !==false)
			{
				$Regi=strstr($Action,'000ia',true);
				$tank=GetData("Regiment_PVP","ID",$Regi,"Vehicule_ID");
				$dca_unit_skill=GetData("Regiment_PVP","ID",$Regi,"Experience");
				$RR="Vehicule_Nbr_ia";
			}
			if($tank >0)
			{
				//GetData Cible
				if($RR =="Vehicule_Nbr" or $RR =="Vehicule_Nbr_ia")
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Type,Blindage_f,Arme_AA,HP,Reput,Camouflage,mobile FROM Cible WHERE ID='$tank'")
					 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk_pvp-tank');
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
					 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : atk_pvp-tank');
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
		else
		{
			if($Cible_Atk ==1)
			{
				$cam_c=$Camouflage_lieu;
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
				$DCA_mun=GetData("Regiment_PVP","ID",$Regi,"Stock_Munitions_".$dca_cal);
			else
				$DCA_mun=9999;			
			if($DCA_mun >=$dca_mult and $dca_cal >0)
			{
				if($RR =="Vehicule_Nbr" and $Regi)
					UpdateData("Regiment_PVP","Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$Regi);
				$intro.="<br><b>La défense anti-aérienne rapprochée ouvre le feu sur vous!</b>";
				if($Target_id)
					$img=Afficher_Image('images/'.$Target_id.'.jpg',"images/image.png",$nom_c);
				else
					$img=Afficher_Image('images/cibles/cibles'.$tank.'_'.$Pays_cible.'.jpg','images/cibles/cibles'.$tank.'.jpg',$nom_c);					
				$dca_max=$rep_c*10;
				if($dca_max >250)$dca_max=250;		
				$Shoot_rand=mt_rand(10,50)+mt_rand(0,$dca_unit_skill)+mt_rand($rep_c,$dca_max);
				$Shoot=$Shoot_rand + $meteo + $VisAvion - ($Pilotage/2) - ($Speed/10) + $dca_mult + ($S_Pass*25);
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
					$Degats=round((mt_rand(1,$dca_degats)-pow($Blindage,2))*GetShoot($Shoot,$dca_mult));
					if($Degats <1)
						$Degats=mt_rand(1,10);
					if($S_Pass)
						$Degats*=$S_Pass;
					$HP-=$Degats;
					if($Shoot >100)
					{
						$CritH=CriticalHit("Avion",$avion,$PlayerID,2,$Engine_Nbr); //Todo : Remplacer 2 par type de munition
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
						$intro.='<br>L\'explosion met le feu à votre avion,ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
						$end_mission=true;
					}
					else
					{
						$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil,lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
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
				$Radar=GetData("Avion","ID",$avion,"Radar");
				$reperer=($Radar*100)+$meteo-$cam_c;
			}
			else
				$reperer=1;
			if($reperer >0)
				$attaque=true;
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
					if(IsEnrayage($Arme2Avion,0,$Pilote_pvp,"Stress_Arme2",true))
					{
						$Mun2=0;
						$intro.="<br><b>Vos armes s'enrayent au plus mauvais moment!</b>";
						$attaque=false;
						$seconde_passe=true;
					}
					UpdateData("Pilote_PVP","Stress_Arme2",1,"ID",$Pilote_pvp);
				}
				else
				{
					if(IsEnrayage($Arme1Avion,0,$Pilote_pvp,"Stress_Arme1",true))
					{
						$Mun1=0;
						$intro.="<p>Vos armes s'enrayent au plus mauvais moment!</p>";
						$attaque=false;
						$seconde_passe=true;
					}
					UpdateData("Pilote_PVP","Stress_Arme1",1,"ID",$Pilote_pvp);
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
			$ArmeAvion_Multi=GetData("Armes","ID",$ArmeAvion,"Multi");
			$ArmeAvion_Dg=GetData("Armes","ID",$ArmeAvion,"Degats");
			$Stab=GetStab("Avion",$avion,$HP);
			if($ArmeAvion_Multi <1)$ArmeAvion_Multi=1;
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
					UpdateData("Pilote_PVP","S_Avion_Bombe_Nbr",-2,"ID",$Pilote_pvp);
					$Bombs -= 2;
				break;
				case 81:
					$Malus=10+($Bombs*10);
					$Mult_Rafale=1;
					$Mun_Rafale=0;
					$ArmeAvion_nbr=$Bombs;
					$Avion_Mun=4;
					SetData("Pilote_PVP","S_Avion_Bombe_Nbr",0,"ID",$Pilote_pvp);
					$Bombs=0;
				break;
			}			
			if($Atk_Mob)$Malus+=$def_c;
			$Shoot=mt_rand(0,$Tir)+($Stab/10)+($meteo/2)+($Courage/10)+($Moral/10)-($Speed/10)+$Bonus_Viseur-$Malus;		
			//JF
			if($Pilote_pvp ==1)
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
			if($Shoot >0)
			{
				$Degats=0;
				$Arme_Cal=round(GetData("Armes","ID",$ArmeAvion,"Calibre"));
				if(!$Avion_Mun)$Avion_Mun=GetData("Pilote_PVP","ID",$Pilote_pvp,"S_Avion_Mun");
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
					$Bonus_Dg=Damage_Bonus("Avion",$avion,$dmg_bonus_cible,$ArmeAvion,$def_c,$Avion_Mun);
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
				if($HP_eni <0 and $Simu)
				{
					UpdateData("Pilote_PVP","Dive",1,"ID",$Pilote_pvp);
					UpdateData("Pilote_PVP","Points",$rep_c,"ID",$Pilote_pvp);
					if($Cible_Atk >9)
						$img=Afficher_Image('images/explosion_navire'.$type_c.'.jpg',"images/image.png","Explosion Navire");
					else
						$img="<img src='images/explosion.jpg' style='width:100%;'>";
					//Modif Lieu
					if($dca)
						$msghit="<br>Votre rafale détruit le canon anti-aérien!";
					elseif($gare)
						$msghit="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
					elseif($usine)
						$msghit="<br>Votre attaque diminue le potentiel de production de l'ennemi!";
					elseif($caserne)
						$msghit="<br>Votre attaque diminue le moral des troupes de l'ennemi!";
					elseif($pont)
						$msghit.="<br>Le pont est endommagé!";
					elseif($port)
						$msghit="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
					elseif($radar)
						$msghit="<br>Votre attaque diminue le potentiel de détection de l'ennemi!";
					elseif($hangar)
						$msghit="<br>Votre rafale détruit un hangar,réduisant les stocks de l'ennemi!";
					elseif($depot)
						$msghit="<br>Votre attaque détruit un entrepôt,réduisant les stocks de l'ennemi!";
					elseif($avion_parque)					
						$msghit="<br>Votre rafale détruit un avion au sol!";
					elseif($Cible_Atk ==3 and $tank ==48)
					{
						$Kills=floor($Degats/100);
						if($Kills >50)$Kills=50;
						if($Kills >$Garnison)$Kills=$Garnison;
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
							$result=mysqli_query($con,"SELECT r.Vehicule_Nbr,r.Pays FROM Regiment_PVP as r,Officier as o WHERE r.Officier_ID=o.ID AND r.ID='$Regi'");
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
							UpdateData("Regiment_PVP",$RR,-1,"ID",$Regi);
							SetData("Regiment_PVP","Visible",0,"ID",$Regi);
						}
						elseif($RR =="Vehicule_Nbr_ia")
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT Vehicule_Nbr,Pays FROM Regiment_PVP WHERE ID='$Regi'");
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
								UpdateData("Regiment_PVP","Vehicule_Nbr",-1,"ID",$Regi);
						}
						else
						{
							$Strike=true;
							SetData("Pilote_PVP","S_Strike",1,"ID",$Pilote_pvp);
						}
					}
					//if($Simu)AddVictoire_atk($Avion_db,$type_c,$tank,$avion,$PlayerID,$Unite,$Cible,$ArmeAvion,$Pays_cible,0,$alt,$Nuit,$Degats);
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
	$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps,true);
	SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
	if($seconde_passe and !$end_mission)
	{
		UpdateData("Pilote_PVP","S_Pass",1,"ID",$Pilote_pvp);
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
		$mes.="<form action='index.php?view=bomb_pvp' method='post'>
		<input type='hidden' name='Avion' value=".$avion.">
		<input type='hidden' name='Meteo' value=".$meteo.">
		<input type='hidden' name='Mun1' value=".$Mun1.">
		<input type='hidden' name='Mun2' value=".$Mun2.">
		<input type='hidden' name='Puissance' value=".$Puissance.">
		<input type='hidden' name='HP_eni' value=".$HP_eni.">
		<input type='hidden' name='Pays_eni' value=".$Pays_cible.">
		<input type='hidden' name='Cible_lock' value=".$Lock.">
		<input type='hidden' name='Battle' value=".$Battle.">
		<input type='hidden' name='Camp' value=".$Faction.">
		<table class='table'>
			<tr><td colspan='8'>Attaque au sol</td></tr>
			<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,8,true)."
				<td align='left'>
					".$Arme1_txt.$Arme2_txt.$choix_pvp."
				</td></tr></table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";		
	}
	if($retour and !$end_mission)
	{
		Chemin_Retour();
		$chemin=$Distance;
		$intro.='<br>Vous prenez le chemin du retour en direction de votre base,située à '.$Distance.'km';
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
			<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,true)."
			</tr></table>
		<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	if($end_mission)
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
include_once('./index.php');
?>