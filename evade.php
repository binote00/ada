<?php
require_once('./jfv_inc_sessions.php');
/*$time=microtime();
$time=explode(' ',$time);
$time=$time[1] + $time[0];
$start=$time;*/
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
/*if($_SESSION['PlayerID'] ==1 or $_SESSION['PlayerID'] ==238)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
$Action=Insec($_POST['Action']);
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$meteo=Insec($_POST['Meteo']);
$avion=Insec($_POST['Avion']);
$avion_eni=Insec($_POST['Avioneni']);
$alt=Insec($_POST['Alt']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Dist_shoot=Insec($_POST['Dist_shoot']);
$HP_eni=Insec($_POST['HP_eni']);
$Puissance=Insec($_POST['Puissance']);
$Enis=Insec($_POST['Enis']);
$Unit_eni=Insec($_POST['Unit_eni']);
$Pilote_eni=Insec($_POST['Pilote_eni']);
$Avion_db_eni=Insec($_POST['Avion_db_eni']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$PlayerID=$_SESSION['PlayerID'];
//Check Joueur Valide
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	$_SESSION['finish']=false;
	$_SESSION['tirer']=false;
	$_SESSION['missiondeux']=false;
	$_SESSION['missiontrois']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['kill_confirm']=false;
	//$_SESSION['evader']=true;	
	$PVP=$_SESSION['PVP'];
	$country=$_SESSION['country'];
	$Chk_Evade=$_SESSION['evader'];	
	$panne_seche=false;
	$mission3=false;
	$end_mission=false;
	$Shoots=false;
	$nav=false;
	$end_shoot=false;
	$shoot_tab=false;
	$UpdateMoral=0;
	$UpdateCourage=0;
	$UpdateTactique=0;
	$UpdatePilotage=0;
	$UpdateStress_Moteur=0;
	if($Chk_Evade)
	{
		$intro="<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>";
		UpdateCarac($PlayerID,"Free",-1);
		MoveCredits($PlayerID,90,-1);
		UpdateCarac($PlayerID,"Reputation",-10);
		UpdateCarac($PlayerID,"Avancement",-10);
		mail("binote@hotmail.com","Aube des Aigles: Init Mission F5 (evade) : ".$PlayerID , "Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Ailier,S_Ailier,Equipage,Pilotage,Acrobatie,Tactique,Tir,Vue,Moral,Courage,Avancement,S_Avion_db,S_Avion_Bombe,S_Avion_Bombe_Nbr,Simu,S_Tourelle_Mun,S_Blindage,
	S_Nuit,S_Cible,S_Mission,S_Formation,S_Escorte,S_Escorte_nom,S_Escorteb_nbr,S_Escorte_nbr,S_Engine_Nbr,S_Longitude,S_Latitude,S_Equipage_Nbr,S_Leader,S_Essence,S_Baby,S_Engine_Nbr_Eni,
	Slot1,Slot3,Slot4,Slot6,Slot7,Slot9,Slot10,Slot11,Sandbox,Admin FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : evade-player');
	$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
	mysqli_close($con);
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
			$Acrobatie=$data['Acrobatie'];
			$Tactique=$data['Tactique'];
			$Tir=$data['Tir'];
			$Vue=$data['Vue'];
			$Moral=$data['Moral'];
			$Courage=$data['Courage'];
			$Avancement=$data['Avancement'];
			$Avion_db=$data['S_Avion_db'];
			$Nuit=$data['S_Nuit'];
			$Cible=$data['S_Cible'];
			$Mission_Type=$data['S_Mission'];
			$Formation=$data['S_Formation'];
			$Escorte=$data['S_Escorte'];
			$Escorte_nom=$data['S_Escorte_nom'];
			$Escorte_nbr=$data['S_Escorte_nbr'];
			$Escorteb_nbr=$data['S_Escorteb_nbr'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Leader=$data['S_Leader'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Avion_Bombe_Nbr=$data['S_Avion_Bombe_Nbr'];
			$S_Baby=$data['S_Baby'];
			$Tourelle_Mun=$data['S_Tourelle_Mun'];
			$S_Blindage=$data['S_Blindage'];
			$Engine_Nbr_Eni=$data['S_Engine_Nbr_Eni'];
			$Slot1=$data['Slot1'];
			$Slot3=$data['Slot3'];
			$Slot4=$data['Slot4'];
			$Slot6=$data['Slot6'];
			$Slot7=$data['Slot7'];
			$Slot9=$data['Slot9'];
			$Slot10=$data['Slot10'];
			$Slot11=$data['Slot11'];
			$Simu=$data['Simu'];
			$Admin=$data['Admin'];
			$Sandbox=$data['Sandbox'];
			if($Sandbox)
				$Ailier=$data['S_Ailier'];
			else
				$Ailier=$data['Ailier'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($Pilotage >50)$Pilotage=50;
	if($Acrobatie >50)$Acrobatie=50;
	if($Tactique >50)$Tactique=50;
	if($Tir >50)$Tir=50;
	if($Vue >50)$Vue=50;
	$Bonus_Pers=1;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(30,$Skills_Pil))
			$Trompe_la_mort=50;
		if(in_array(31,$Skills_Pil))
			$Fou_Volant=true;
		if(in_array(36,$Skills_Pil))
			$SixSens=25;
		if(in_array(40,$Skills_Pil))
			$AsPique=50;
		if(in_array(42,$Skills_Pil))
			$FingerFour=25;
		if(in_array(43,$Skills_Pil))
			$CoordTir=50;
		if(in_array(45,$Skills_Pil))
			$ExDeflect=true;
		if(in_array(46,$Skills_Pil))
			$MDeflect=true;
		if(in_array(77,$Skills_Pil))
			$VoixRass=25;
		if(in_array(78,$Skills_Pil))
			$Discipline_fer=true;
		if(in_array(94,$Skills_Pil))
			$ExpTac=50;
		elseif(in_array(94,$Skills_Pil))
			$ExpTac=25;
		if(in_array(130,$Skills_Pil))
			$Pers_Sup=1;
		if(in_array(98,$Skills_Pil))
			$Bonus_Pers=1.5;
	}
	if($HP <1)
		$end_mission=true;
	else
	{
		if($Equipage)$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");			
		if($Slot6 ==49)
			$Tactique*=1.01;
		if($Slot7 ==16)
			$Acrobatie*=1.01;
		if($Slot11 ==69 and !$Sandbox)
		{
			$Moral+=50;
			$Courage+=50;
		}			
		$avion_img=GetAvionImg($Avion_db,$avion);		
		if($PVP and !$Sandbox)
		{
			$HP_Ori=$HP;
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT HP,Target FROM Duels_Candidats WHERE PlayerID='$PlayerID'");
			$PVP_Ok=mysqli_result(mysqli_query($con,"SELECT Lieu FROM Duels_Candidats WHERE PlayerID='$Pilote_eni'"),0);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$HP=$data['HP'];
					$PVP_Target=$data['Target'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($HP <$HP_Ori)
			{
				$Deg=$HP_Ori-$HP;
				$avion_eni_nom=GetData($Avion_db_eni,"ID",$avion_eni,"Nom");
				$intro.="<p>[PVP] Vous encaissez une rafale tirée par un <b>".$avion_eni_nom."</b>! (<b>".$Deg."</b> dégâts)</p>";
			}
			if($PVP_Ok !=$Cible or !$PVP_Target)
			{
				$_SESSION['PVP']=false;
				$PVP=false;
				SetData("Duels_Candidats","Target",0,"PlayerID",$Pilote_eni);
				RetireCandidat($PlayerID,"nav");
				$Enis=0;
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0 WHERE ID='$PlayerID'");
				mysqli_close($con);
			}
		}		
		//Boost
		if($c_gaz ==130)$UpdateStress_Moteur+=10;
		if($HP <1)
			$Action=98;
		elseif($essence <1)
			$panne_seche=true;
		elseif($PVP and $HP <1)
		{
			$intro.="<p>Une rafale transforme votre appareil en passoire, ne vous laissant pas d'autre choix que de sauter en parachute!</p>";
			$end_mission=true;
			$_SESSION['Parachute']=true;
		}
		elseif($Enis <1 or (!$PVP and $chemin <1 and !$Nuit))
		{
			$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
			$intro.="<p>Votre adversaire abandonne la poursuite!</p>";
			$img=Afficher_Image("images/epargner.jpg","images/epargner.jpg","Epargner");
			$chemin=0;
			$nav=true;
		}
		else
		{
			if($PVP)
				$Pilote_db='Pilote';
			else
				$Pilote_db='Pilote_IA';
			//GetData Avion
			$con=dbconnecti();
			$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
			$result=mysqli_query($con,"SELECT Nom,Type,Robustesse,Masse,Puissance,ArmePrincipale,ArmeSecondaire,ArmeArriere,TourelleSup,Arme3_Nbr,Arme5_Nbr,Blindage,Verriere,Detection,Plafond,Autonomie,Radio,Baby,Engine,ManoeuvreB,ManoeuvreH,Maniabilite FROM $Avion_db WHERE ID='$avion'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : evade-avion');
			$result2=mysqli_query($con,"SELECT Nom,Pays,Type,Equipage,Robustesse,Maniabilite,ArmePrincipale,ArmeSecondaire,Arme1_Nbr,Arme2_Nbr,Blindage,Detection,Plafond,VitesseA,Engine_Nbr,Engine,ManoeuvreB,ManoeuvreH,Maniabilite FROM $Avion_db_eni WHERE ID='$avion_eni'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : evade-avioneni');
			$result3=mysqli_query($con,"SELECT Pilotage,Acrobatie,Tactique,Tir FROM $Pilote_db WHERE ID='$Pilote_eni'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : evade-piloteni');
			mysqli_close($con);
			if($result)
			{
				while($dataa=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Avion_Nom=$dataa['Nom'];
					$Type_avion=$dataa['Type'];
					$HPmax=$dataa['Robustesse'];
					$Masse=$dataa['Masse'];
					$Puissance_nominale=$dataa['Puissance'];
					$Arme1Avion=$dataa['ArmePrincipale'];
					$ArmeArriere=$dataa['ArmeArriere'];
					$TourelleSup=$dataa['TourelleSup'];
					$Arme3=$dataa['Arme3_Nbr'];
					$Arme5=$dataa['Arme5_Nbr'];
					$Blindage=$dataa['Blindage'];
					$Verriere=$dataa['Verriere'];
					$DetAvion=$dataa['Detection'];
					$Plafond=$dataa['Plafond'];
					$Autonomie=$data['Autonomie'];
					$Radio_a=$dataa['Radio'];
					$Baby=$dataa['Baby'];
					$Engine=$dataa['Engine'];
					$ManB=$data['ManoeuvreB'];
					$ManH=$data['ManoeuvreH'];
					$Mani=$data['Maniabilite'];
				}
				mysqli_free_result($result);
				unset($dataa);
			}
			if($result2)
			{
				while($datai=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Nom_eni=$datai['Nom'];
					$Pays_eni=$datai['Pays'];
					$Type_avioneni=$datai['Type'];
					$Equipage_eni=$datai['Equipage'];
					$HPmax_eni=$datai['Robustesse'];
					$ManiAvion_eni=$datai['Maniabilite'];
					$Arme1Avion_eni=$datai['ArmePrincipale'];
					$Arme2Avion_eni=$datai['ArmeSecondaire'];
					$Arme1Avion_nbr_eni=$datai['Arme1_Nbr'];
					$Arme2Avion_nbr_eni=$datai['Arme2_Nbr'];
					$Blindage_eni=$datai['Blindage'];
					$DetAvion_eni=$datai['Detection'];
					$Plafond_eni=$datai['Plafond'];
					$VitAAvioneni=$datai['VitesseA'];
					$Engine_Nbr_enis=$datai['Engine_Nbr'];
					$Engine_eni=$datai['Engine'];
					$ManBeni=$data['ManoeuvreB'];
					$ManHeni=$data['ManoeuvreH'];
					$Manieni=$data['Maniabilite'];
				}
				mysqli_free_result($result2);
				unset($datai);
			}
			if($result3)
			{
				while($datap=mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					$Pilotage_eni=$datap['Pilotage'];
					$Acrobatie_eni=$datap['Acrobatie'];
					$Tactique_eni=$datap['Tactique'];
					$Tir_eni=$datap['Tir'];
				}
				mysqli_free_result($result3);
				unset($datap);
			}
			if(!$Blindage)
			{
				$Blindage=$S_Blindage;
				if(!$Blindage)$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
			}
			//GetData Armes_eni
			if($Arme1Avion_nbr_eni or $Arme2Avion_nbr_eni)
			{
				$con=dbconnecti();		
				$resultarmei=mysqli_query($con,"SELECT Degats,Calibre,Multi,Portee FROM Armes WHERE ID='$Arme1Avion_eni'")
				 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : evade-armeni');
				if($Arme2Avion_nbr_eni)
					$resultarmeii=mysqli_query($con,"SELECT Degats,Calibre,Multi,Portee FROM Armes WHERE ID='$Arme2Avion_eni'")
					or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : evade-armeni');
				mysqli_close($con);		
				if($resultarmei)		
				{		
					while($datab=mysqli_fetch_array($resultarmei,MYSQLI_ASSOC))	
					{
						$Arme1Avion_Dg_eni=$datab['Degats'];
						$Arme1Avion_Cal_eni=$datab['Calibre'];
						$Arme1Avion_Multi_eni=$datab['Multi'];
						$Arme1Avion_Range_eni=$datab['Portee'];
					}
					mysqli_free_result($resultarmei);
					unset($datab);
				}				
				if($resultarmeii)		
				{		
					while($datab=mysqli_fetch_array($resultarmeii,MYSQLI_ASSOC))	
					{
						$Arme2Avion_Dg_eni=$datab['Degats'];
						$Arme2Avion_Cal_eni=$datab['Calibre'];
						$Arme2Avion_Multi_eni=$datab['Multi'];
						$Arme2Avion_Range_eni=$datab['Portee'];
					}
					mysqli_free_result($resultarmeii);
					unset($datab);
				}
				if($Arme2Avion_Cal_eni >$Arme1Avion_Cal_eni or ($Arme2Avion_nbr_eni >$Arme1Avion_nbr_eni and $Arme2Avion_Cal_eni ==$Arme1Avion_Cal_eni))
				{
					$Arme1Avion_nbr_eni=$Arme2Avion_nbr_eni;
					$Arme1Avion_eni=$Arme2Avion_eni;
					$Arme1Avion_Dg_eni=$Arme2Avion_Dg_eni;
					$Arme1Avion_Multi_eni=$Arme2Avion_Multi_eni;
					$Arme1Avion_Range_eni=$Arme2Avion_Range_eni;
				}
			}
			if($Equipage_Nbr >1)
			{
				$con=dbconnecti();		
				$result=mysqli_query($con,"SELECT Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'");
				mysqli_close($con);		
				if($result)		
				{		
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))	
					{	
						$Pers1=$data['Pers1'];
						$Pers2=$data['Pers2'];
						$Pers3=$data['Pers3'];
						$Pers4=$data['Pers4'];
						$Pers5=$data['Pers5'];
						$Pers6=$data['Pers6'];
						$Pers7=$data['Pers7'];
						$Pers8=$data['Pers8'];
						$Pers9=$data['Pers9'];
						$Pers10=$data['Pers10'];
					}	
					mysqli_free_result($result);
					unset($data);
				}		
				$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
				$Personnel=array_count_values($Pers);
			}
			if($Pilotage_eni <10)$Pilotage_eni=mt_rand(100,200);
			if($Acrobatie_eni <10)$Acrobatie_eni=mt_rand(100,200);
			if($Tactique_eni <10)$Tactique_eni=mt_rand(100,200);
			if($Tir_eni <10)$Tir_eni=mt_rand(100,200);
			if($Reputation >25000 or $Avancement >50000 or $Pilotage >=150)$Grosbill=true;
			$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,$Sandbox,$Pilotage);
			if($Grosbill and $Enis >2 and ($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12))
			{
				$Mod_GB=1+($Enis*0.1);
				$Pilotage_eni*=$Mod_GB;
				$Acrobatie_eni*=$Mod_GB;
				$Tactique_eni*=$Mod_GB;
				if($HP_eni<$HPmax_eni/2)$HP_eni=$HPmax_eni;
				$Pilotage/=(1+($Enis*0.1));
				$Acrobatie/=(1+($Enis*0.1));
				$Tactique/=(1+($Enis*0.1));
			}
			elseif($Pilotage <50 or $Avancement <1000)
				$Noob=true;
			//Malus avion touché
			$moda=$HPmax/$HP;
			if($Avion_db =="Avion" and $Avion_Bombe_Nbr and $Avion_Bombe !=30)
			{
				$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_Nbr));
				$moda*=(1+$charge_sup);
			}
			elseif($Avion_db =="Avions_Persos" and $Baby and !$S_Baby)
			{
				$charge_sup=1.1/($Masse/$Baby);
				$moda*=(1-$charge_sup);
			}
			//Alt
			if(!$moda)
				$Plafond=1000;
			else
				$Plafond=round($Plafond/$moda);
			$alt=SetAlt($alt,$Plafond,$Plafond_eni,-1000,1000,$c_gaz);
			if($alt <100)$alt=100;
			//Malus Froid
			if($alt >6000)
			{
				$Malus_Froid_Red=GetMalusFroid($alt,$Slot4,$Slot1,$Slot9);
				$Pilotage*=$Malus_Froid_Red[0];
				$Acrobatie*=$Malus_Froid_Red[0];
				$Tactique*=$Malus_Froid_Red[0];
				$intro.=$Malus_Froid_Red[1];
				unset($Malus_Froid_Red);
			}
			$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,$alt,$PlayerID,$Unite);		
			$PuissAvion=GetPuissance($Avion_db,$avion,$alt,$HP,$moda,1,$Engine_Nbr,$c_gaz);
			$ManAvion=GetMano($ManH,$ManB,$HPmax,$HP,$alt,$moda,1,$flaps);
			$ManiAvion=GetMani($Mani,$HPmax,$HP,$moda,1,$flaps);
			$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
			$VitAAvion=GetSpeedA($Avion_db,$avion,$alt,$meteo,$Engine_Nbr,$moda,1,$c_gaz,$flaps);
			//Malus avion_eni touché
			///***Important ne pas effacer !!!***///
			if(!$HP_eni)$HP_eni=$HPmax_eni;
			///***!!!***///
			$moda_eni=$HPmax_eni/$HP_eni;
			$VitAAvioneni=round($VitAAvioneni/$moda_eni);
			if($Engine_Nbr_Eni <1)$Engine_Nbr_Eni=$Engine_Nbr_enis;
			$PuissAvioneni=GetPuissance($Avion_db_eni,$avion_eni,$alt,$HP_eni,$moda_eni,1,$Engine_Nbr_Eni);
			$ManAvion_eni=GetMano($ManHeni,$ManBeni,$HPmax_eni,$HP_eni,$alt,$moda_eni);
			$VitAvioneni=GetSpeed($Avion_db_eni,$avion_eni,$alt,$meteo,$moda_eni);
			$Vis_eni=GetVis($Avion_db_eni,$avion_eni,$Cible,$meteo,$alt,$alt); 			
			//Si avion trop abimé, il s'écrase au sol
			if(($VitAvion <50) or ($PuissAvion <1) or ($c_gaz <20) or $HP <1)
				$Action=98;
			elseif(!$PVP and $Mission_Type !=103)
			{
				//Formation : check si bombardiers alliés descendus
				if($Formation >0 and $Action !=21) // and ($Type_avion ==2 or $Type_avion ==7 or $Type_avion ==11))
				{				
					$Escort_Time=mt_rand(0,10)+$Discipline_fer;
					if($Enis >1 and $Escort_Time <3 and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12))
					{
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT p.ID,p.Nom,a.Nom as Avion FROM Pilote_IA as p,Avion as a WHERE p.Cible='$Cible' AND p.Unit='$Unite' AND p.Actif=1 AND p.Avion=a.ID ORDER BY RAND() LIMIT 1");
						mysqli_close($con);
						if($result)		
						{		
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$pilote_ia_dead=$data['ID'];
								$pilote_ia_dead_name=$data['Nom'];
								$pilote_ia_avion_name=$data['Avion'];
							}
							mysqli_free_result($result);
						}				
						$con=dbconnecti();
						$result=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Task=0,Moral=Moral-10 WHERE ID='$pilote_ia_dead'");
						mysqli_close($con);
						$intro.="<p><b>".$pilote_ia_dead_name."</b> vous signale qu'il doit abandonner la mission, il ramène son <b>".$pilote_ia_avion_name."</b> à la base !</p>";
						$UpdateMoral-=1;
						$Formation-=1;
						UpdateCarac($PlayerID,"S_Formation",-1);
					}
				}
				//Mission Escorte : check si bombardiers alliés descendus
				if($Mission_Type ==4 and ($Action ==4 or $Action ==12 or $Action ==7))
				{
					if($Enis >1 and $Escorteb_nbr >0 and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12))
					{
						$intro.="<p>L'ennemi vient d'abattre un <b>".GetData("Pilote","ID",$PlayerID,"S_Escorteb_nom")."</b> que vous escortiez !</p>";
						$UpdateMoral-=1;
						$UpdateReput-=1;
						$UpdateGrade-=1;
						$Update_S_Escorteb_nbr-=1;
						$Escorteb_nbr-=1;
						if($Escorteb_nbr <1 and $_SESSION['done'] ==false)
						{
							$intro.="<p><b>Toute la formation que vous escortiez a été abattue! Vous échouez dans votre mission !</b><p>";
							$UpdateMoral-=10;
							$UpdateReput-=10;
							$UpdateGrade-=10;
							$Update_Unit_Reput-=10;
							$_SESSION['done']=true;
							$chemin=$Distance;
							$Mission_Type=3;
							SetData("Pilote","S_Mission",3,"ID",$PlayerID); //Passer en mode chasse pour éviter le menu d'escorte
						}
					}
					unset($Escort_Time);
				}
			}
			$Conso=($Puissance_nominale*$c_gaz/100)/500;
			//Les bombardiers sont des cibles prioritaires, mais protection pour les newbies
			if($Pilotage >100 and ($Mission_Type ==1 or $Mission_Type ==2 or $Mission_Type ==6 or $Mission_Type ==8 or $Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13 or $Mission_Type ==31))$Proies=true;
			switch($Action)
			{
				case 1:
					//Tenter de manoeuvrer pour prendre l'avantage.
					$essence-=(5+$Conso);
					$Pilot=mt_rand(0,$Pilotage*2) + $meteo + ($ManAvion*2) - ($PuissAvion/3) + ($Moral/10) + mt_rand(0,$Tactique*2) + ($Verriere*10) + $DetAvion;
					$Pilot_eni=mt_rand(0,$Pilotage_eni*2) + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + mt_rand(10,$Tactique_eni*2) + ($Verriere_eni*10) + $DetAvion_eni + ($Enis*20);
					//JF
					if($PlayerID ==1 or $PlayerID ==2)
					{
						$ManAvion_txt=$ManAvion*2;
						$ManAvion_eni_txt=$ManAvion_eni*2;
						$PuissAvion_txt=$PuissAvion/3;
						$PuissAvioneni_txt=$PuissAvioneni/3;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><td colspan='3'>Bonus : Moral</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
							<tr><td>Puissance</td><td>".$PuissAvion_txt."</td><td>".$PuissAvioneni_txt."</td></tr>
							<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					//End JF
					if($Pilot >=$Pilot_eni and !$Chk_Evade)
					{
						$Dist_shoot=SetDist_shoot($Pilot,$Pilot_eni);
						$Angle_shoot=SetAngle_shoot($Pilot,$Pilot_eni,0,60);
						$car_up=mt_rand(0,1);
						$UpdatePilotage+=$car_up;
						$UpdateMoral+=1;
						$intro.="<br>Vous parvenez à quitter la ligne de tir de votre adversaire!";
						$img.="<img src='images/escape2.jpg' style='width:100%;'>";
						$mission3=true;
					}
					else
					{
						if($Equipage_eni >1)
						{
							$intro.="<br>Le mitrailleur de l'avion ennemi vous a pris pour cible!";
							$Shoots=true;
						}
						else
						{
							$UpdateMoral -=1;
							$intro.="<br>Vous ne parvenez pas à décoller votre adversaire de vos 6 heures!";
							$Shoots=true;
						}
					}
				break;
				case 2:
					//Tenter une figure acrobatique pour prendre l'avantage.
					$essence-=(10+$Conso);
					if($c_gaz >90)
					{
						$UpdateStress_Moteur+=1;
						$UpdateStress_Commandes+=1;
					}
					if($flaps)$UpdateStress_Commandes+=1;
					//As ou expert tactique/acrobatie met ses volets
					if($Tactique_eni >100 or $Pilotage_eni >100 or $Grosbill)$ManAvion_eni=GetMano($ManHeni,$ManBeni,$HPmax_eni,$HP_eni,$alt,$moda_eni,1,3);
					$Pilot=mt_rand(0,$Acrobatie*2) + $meteo + ($ManAvion*8) - ($PuissAvion/3) + $ManiAvion + ($Moral/10) + mt_rand(0,$Tactique*2) + ($Verriere*10) + $DetAvion;
					$Pilot_eni=mt_rand(0,$Acrobatie_eni*2) + $meteo + ($ManAvion_eni*8) - ($PuissAvioneni/3) + $ManiAvion_eni + mt_rand(10,$Tactique_eni*2) + ($Verriere_eni*10) + $DetAvion_eni + ($Enis*20);
					//JF
					if($PlayerID ==1 or $PlayerID ==2)
					{
						$ManAvion_txt=$ManAvion*8;
						$ManAvion_eni_txt=$ManAvion_eni*8;
						$PuissAvion_txt=$PuissAvion/3;
						$PuissAvioneni_txt=$PuissAvioneni/3;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><th colspan='3'>Tenter de reprendre l'avantage</th></tr>
							<tr><td colspan='3'>Bonus : Moral</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
							<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
							<tr><td>Acrobatie</td><td>".$Acrobatie."</td><td>".$Acrobatie_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					//End JF
					if($Pilot >=$Pilot_eni+50 and !$Chk_Evade)
					{
						$Dist_shoot=round(($VitAvioneni-$VitAvion)-($Pilot-$Pilot_eni));
						$Angle_shoot=SetAngle_shoot($Pilot,$Pilot_eni,45,90);
						if($MDeflect)$Angle_shoot-=5;
						elseif($ExDeflect)$Angle_shoot-=2;
						if($Angle_shoot<0)$Angle_shoot=0;
						if($Dist_shoot <10)$Dist_shoot=mt_rand(9,25);
						if($Simu and !$Sandbox)
						{
							/*$car_up=mt_rand(0,1);
							UpdateCarac($PlayerID,"Acrobatie",$car_up);
							$UpdateTactique += $car_up;*/
							$UpdateMoral+=1;
						}
						$intro.='<br>Vous exécutez votre manoeuvre à la perfection, forçant votre adversaire à vous dépasser!<br>Votre cible se trouve à '.$Dist_shoot.'m, sous un angle de '.$Angle_shoot.'°';
						$img.="<img src='images/escape2.jpg' style='width:100%;'>";
						$shoot_tab=true;
					}
					elseif($Pilot >=$Pilot_eni and $Type_avioneni !=2 and $Type_avioneni !=11 and !$Chk_Evade)
					{
						$Dist_shoot=SetDist_shoot($Pilot,$Pilot_eni);
						if($Simu and !$Sandbox)
						{
							/*$car_up=mt_rand(0,1);
							UpdateCarac($PlayerID,"Acrobatie",$car_up);*/
							$UpdateMoral+=1;
						}
						$intro.="<br>Vous parvenez à quitter la ligne de tir de votre adversaire!";
						$img.="<img src='images/escape2.jpg' style='width:100%;'>";
						$mission3=true;
					}
					else
					{
						if($Equipage_eni >1)
						{
							$intro.="<br>Le mitrailleur de l'avion ennemi vous a pris pour cible!";
							$Shoots=true;
						}
						else
						{
							$UpdateMoral-=1;
							$intro.="<br>Vous ne parvenez pas à décoller votre adversaire de vos 6 heures!";
							$Shoots=true;
						}
					}
				break;
				case 3:
					//Tenir le coup afin de protéger votre leader.
					$chemi-=5;
					$essence-=(5+$Conso);
					if(!$Chk_Evade and $Simu)
					{
						$UpdateGrade+=1;
						$UpdateReput+=2;
						$UpdateCourage+=2;
					}
					$Pilot=mt_rand(0,$Pilotage) + $meteo + $ManAvion - $PuissAvion + ($Moral/10) + mt_rand(0,$Tactique*2) + ($Noob*100);
					$Pilot_eni=mt_rand(0,$Pilotage_eni) + $meteo + $ManAvion_eni - $PuissAvioneni + mt_rand(10,$Tactique_eni*2) + ($Enis*20);
					//$skills.="<br>[Votre Manoeuvre : ".$Pilot." ; Manoeuvre de l'adversaire : ".$Pilot_eni."]<br>";
					if($Pilot >=$Pilot_eni and !$Chk_Evade)
					{
						$Dist_shoot=SetDist_shoot($Pilot,$Pilot_eni);
						$car_up=mt_rand(0,1);
						$UpdatePilotage+=$car_up;
						$UpdateMoral+=1;
						$UpdateTactique+=1;
						$intro.="<br>Vous parvenez à quitter la ligne de tir de votre adversaire!";
						$img.="<img src='images/escape2.jpg' style='width:100%;'>";
						$mission3=true;
					}
					else
					{
						if($Enis <3)$UpdateMoral-=1;
						if($Equipage_eni >1)
						{
							$intro.="<br>Le mitrailleur de l'avion ennemi vous a pris pour cible!";
							$Shoots=true;
						}
						else
						{
							$intro.="<br>Vous ne parvenez pas à décoller votre adversaire de vos 6 heures!";
							$Shoots=true;
						}
					}
				break;
				case 4:
					//Tenter de fuir (manoeuvre)
					$essence-=(5+$Conso);
					$chemin-=1;
					if($Type_avion ==1 and ($Mun1 >0 or $Mun2 >0))
					{
						$UpdateGrade-=2;
						$UpdateReput-=2;
						$UpdateCourage-=5;
					}
					$Pilot=mt_rand(0,$Pilotage*3) + $meteo + ($ManAvion*2) + $ManiAvion - ($PuissAvion/3) + $VitAvion + ($Moral/10) + mt_rand(0,$Tactique*2)+($Noob*50);
					$Pilot_eni=mt_rand(0,$Pilotage_eni*3) + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(0,$Tactique_eni*2) + ($Enis*10);
					if($Proies)$Pilot_eni+=50;
					//JF
					if($PlayerID ==1 or $PlayerID ==2)
					{
						$PuissAvion_txt=$PuissAvion/3;
						$PuissAvioneni_txt=$PuissAvioneni/3;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><td colspan='3'>Bonus : Moral + Roulis</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
							<tr><td>Roulis</td><td>".$ManiAvion."</td><td>-Bonus-</td></tr>
							<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
							<tr><td>Puissance</td><td>".$PuissAvion_txt."</td><td>".$PuissAvioneni_txt."</td></tr>
							<tr><td>Pilotage (x2)</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					//End JF
					if($Pilot >=$Pilot_eni and !$Chk_Evade)
					{
						$car_up=mt_rand(0,1);
						$UpdatePilotage+=$car_up;
						$UpdateMoral+=1;
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						$intro.="<br>Vous parvenez à vous échapper et continuez votre route.";
						$nav=true;
					}
					else
					{
						if($Enis <3)$UpdateMoral-=2;
						$intro.="<br>Vous ne parvenez pas à vous échapper.";
						$Shoots=true;
					}
				break;
				case 5:
					//Maintenir votre cap, la mission est plus importante.
					if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
					{
						$essence-=(5+$Conso);
						$chemin-=5;
						$UpdateCourage+=1;
						//Mitrailleur arrière avion
						if($Type_avion ==2 or $Type_avion ==3 or $Type_avion ==4 or $Type_avion ==6 or $Type_avion ==7 or $Type_avion ==9 or $Type_avion ==10 or $Type_avion ==11)
						{
							if($Arme3 >0)
								$Arme3Avion=$ArmeArriere;
							else
								$Arme3Avion=$TourelleSup;
							if($Tourelle_Mun <1)
							{
								$intro.="<p>Votre mitrailleur n'a plus de munitions!</p>";
								$Shoots=true;
							}
							elseif($Arme3Avion !=5 and $Arme3Avion !=0 and $Equipage_Nbr >1)
							{
								$go_shoot=true;
								//Equipage
								if($Equipage and $Endu_Eq >0)
								{
									$con=dbconnecti();		
									$result=mysqli_query($con,"SELECT Courage,Moral,Tir,Trait FROM Equipage WHERE ID='$Equipage'");		
									mysqli_close($con);		
									if($result)		
									{		
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))	
										{	
											$Courage_Eq=$data['Courage'];
											$Moral_Eq=$data['Moral'];
											$Tir_mg=$data['Tir'];
											$Trait_e=$data['Trait'];
										}	
										mysqli_free_result($result);
										unset($data);
									}
									if($Trait_e ==1)
										$Tir_mg=round($Tir_mg *1.1);
									elseif($Trait_e ==2 and $Courage_Eq <100)
										$Courage_Eq=100;
									elseif($Trait_e ==8 and $Moral_Eq <100)
										$Moral_Eq=100;
									if($Courage_Eq <1 and $Trait_e !=6)
									{
										$go_shoot=false;
										$Etat_Eq=",il est tétanisé par la peur";
									}
									if($Moral_Eq <1 and $Trait_e !=6)
									{
										$go_shoot=false;
										$Etat_Eq=",il est démoralisé";
									}
								}
								else
								{
									$Skill_min=10+(($Personnel[13]+$Pers_Sup)*10*$Bonus_Pers);
									$Tir_mg=mt_rand($Skill_min,150);
								}								
								if($go_shoot and !$Chk_Evade)
								{
									if($Pilotage_eni <10)
										$Pilotage_eni=mt_rand(100,200);
									if($Tactique_eni <10)
										$Tactique_eni=mt_rand(100,200);
									if($HP_eni <500)
									{
										$Rand_Pil_eni=$Pilotage_eni;
										$Rand_Tac_eni=$Tactique_eni;
									}
									else
									{
										$Rand_Pil_eni=mt_rand(10,$Pilotage_eni);
										$Rand_Tac_eni=mt_rand(10,$Tactique_eni);
									}
									$Shoot=mt_rand(0,$Tir_mg)+$meteo+$Vis_eni-($ManAvion_eni/10)-$Rand_Pil_eni-$Rand_Tac_eni+$CoordTir;
									//JF
									if($Admin ==1)
									{
										$skills.="<br>[Score de Tir MG : ".$Shoot."]
															<br>+Vis_eni ".$Vis_eni." 
															<br>-Man ".$ManAvion_eni." /10
															<br>-Tactique ".$Tactique_eni."/2 (rand)
															<br>-Pilotage ".$Pilotage_eni."/2 (rand)
															<br>Tir_mg :".$Tir_mg;
									}
									//End JF
									$Arme3Avion_Multi=GetData("Armes","ID",$Arme3Avion,"Multi");
									$Mun_mg=$Arme3Avion_Multi*5;
									UpdateCarac($PlayerID,"S_Tourelle_Mun",-$Mun_mg);
									if($Shoot >0)
									{
										$UpdateMoral+=1;
										if($Shoot >50)
										{
											if($Equipage and $Equipage_Nbr >1 and $Simu)
											{
												UpdateCarac($Equipage,"Moral",1,"Equipage");
												UpdateCarac($Equipage,"Tir",1,"Equipage");
											}
											$Arme3Avion_Dg=GetData("Armes","ID",$Arme3Avion,"Degats");
											if($Arme3Avion_Dg >0)
											{
												if($Arme3Avion_Multi <1)$Arme3Avion_Multi=1;
												$Avion_Mun=GetData("Pilote","ID",$PlayerID,"S_Avion_Mun");
												$Bonus_Dg=Damage_Bonus($Avion_db,$avion,$avion_eni,$Arme1Avion,$Blindage_eni,$Avion_Mun);
												$Degats=round((mt_rand(1,$Arme3Avion_Dg)+$Bonus_Dg-pow($Blindage_eni,2))*mt_rand(1,$Arme3Avion_Multi));
											}
										}
										else
											$Degats=0;
										if($Degats <1)$Degats=mt_rand(1,5);
										$HP_eni-=$Degats;
										if($HP_eni <1)
										{
											if($Equipage and $Equipage_Nbr >1 and $Simu)
											{
												UpdateCarac($Equipage,"Avancement",10,"Equipage");
												UpdateCarac($Equipage,"Reputation",10,"Equipage");
												UpdateCarac($Equipage,"Moral",10,"Equipage");
												UpdateCarac($Equipage,"Victoires",1,"Equipage");
											}
											$wing_txt="Votre mitrailleur";
											$intro.="<br>Votre mitrailleur fait mouche et abat l'avion ennemi !";
											$img="<img src='images/kill".$country.".jpg' style='width:100%;'>";
											$end_shoot=true;
										}
										else
										{
											$intro.='<br>Votre mitrailleur touche l\'avion ennemi!'; //, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
											$Shoots=true;
										}
									}
									else
									{
										$intro.="<br>Votre mitrailleur tire, mais manque sa cible.";
										$Shoots=true;
									}
								}
								else
								{
									$intro.='<br>Votre mitrailleur refuse d\'obéir aux ordres'.$Etat_Eq;
									$Shoots=true;
								}
							}
							else
								$Shoots=true;
						}
						else
						{
							if(!$Chk_Evade and $Simu)$UpdateCourage+=5;
							$Shoots=true;
						}
					}
					else
					{
						$intro.='<br>L\'ennemi poursuit sa route vous évitant.<br>Vous continuez votre route vers votre objectif, à environ '.$alt.'m d\'altitude.';
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						$nav=true;
					}	
				break;
				case 6:
					//Appeler votre leader à l'aide / Demander à l'ailier de couvrir
					$Combat_Ailier=false;
					$no_escort=false;
					if($Ailier and ($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)) //Ailier
					{
						if($Enis >1)$UpdateTactique+=1;						
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT Pilotage,Tactique,Avion,Nom,Unit FROM Pilote_IA WHERE ID='$Ailier'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Pilotage_ailier=$data['Pilotage'];
								$Tactique_ailier=$data['Tactique']+$FingerFour;
								$avion_lead=$data['Avion'];
								$wing_txt=$data['Nom'];
								$Cible_Escorte=$data['Unit'];
							}
							mysqli_free_result($result);
						}
						$Combat_Ailier=true;
						$no_escort=true;
					}				
					else
						$intro.="<br>Vous n'avez pas d'ailier!";
					if($Combat_Ailier)
					{
						//Ailier/Lead vs Eni
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT * FROM Avion WHERE ID='$avion_lead'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$HP_lead=$data['Robustesse'];
								$ManH_lead=$data['ManoeuvreH'];
								$ManB_lead=$data['ManoeuvreB'];
								$Mani_lead=$data['Maniabilite'];
								$Puissance_lead=$data['Puissance'];
								$Engine_lead=$data['Engine'];
								$Engine_Nbr_lead=$data['Engine_Nbr'];
								$Masse_lead=$data['Masse'];
								$Alt_ref_lead=$data['Alt_ref'];
							}
							mysqli_free_result($result);
						}
						$ManAvion_lead=GetMano($ManH_lead,$ManB_lead,$HP_lead,$HP_lead,$alt);
						$ManiAvion_lead=GetMani($Mani_lead,$HP_lead,$HP_lead);
						$PuissAvion_lead=GetPuissance("Avion",$avion_lead,$alt,$HP_lead,1,1,$Engine_Nbr_lead);
						$VitAvion_lead=GetSpeed("Avion",$avion_lead,$alt,$meteo);
						if($Pilotage_eni <10)$Pilotage_eni=mt_rand(100,200);
						if($Tactique_eni <10)$Tactique_eni=mt_rand(100,200);
						$Pilot_lead=mt_rand(10,$Pilotage_ailier)+$meteo+$ManAvion_lead+$ManiAvion_lead-($PuissAvion_lead/2)+$VitAvion_lead+mt_rand(10,$Tactique_ailier)+($Radio_a*10)+$VoixRass+$ExpTac;
						$Pilot_eni_skill=mt_rand(10,$Pilotage_eni)+$meteo+$ManAvion_eni+$ManiAvion_eni-($PuissAvioneni/3)+$VitAvioneni+mt_rand(10,$Tactique_eni)+($Enis*15);
						if($Pilot_lead >$Pilot_eni_skill and !$Chk_Evade)
						{
							$intro.='<p>'.$wing_txt.' vous sort de ce mauvais pas en mettant en fuite l\'ennemi qui vous prenait pour cible!</p>';
							$img=Afficher_Image('images/kill'.$country.'.jpg',"images/kill.jpg","Victoire!");	
							$Enis-=1;
							UpdateData("Pilote","enis",-1,"ID",$PlayerID);
							if($Enis >0)
							{
								$mission3=true;
								$HP_eni=$HPmax_eni;
							}
							else
							{
								$UpdateMoral+=1;
								if(!$PVP and $HP_eni <$HPmax_eni and $Simu and !$Sandbox and $Mission_Type !=103 and $Cible and $Pilote_eni)
									AddProbable($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,2);
								$nav=true;
							}
							if($Mission_Type !=3 and $Mission_Type !=103)
							{
								$con=dbconnecti();
								$update=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$Pilote_eni'");
								mysqli_close($con);
								if(($Type_avioneni ==1 or $Type_avioneni ==12) and $Simu and !$Sandbox)
								{
									if(GetData("Pilote_IA","ID",$Pilote_eni,"Escorte") ==$Cible)
										AddEvent($Avion_db,180,$avion,$PlayerID,$Cible_Escorte,$Cible,$avion_eni,$PJ_Couv_Final);
									else
									{
										AddEvent("Avion",191,$avion_lead,$Ailier,$Cible_Escorte,$Cible,$avion_eni,$Pilote_eni);
										AddEvent("Avion",189,$avion_lead,$Ailier,$Unite,$Cible,$avion_eni,$Pilote_eni);
									}
									$skills.="<p>Vous réduisez la couverture aérienne ennemie sur la zone!</p>";
									if($Mission_Type ==7 or $Mission_Type ==26 or $Mission_Type ==31)
									{
										UpdateCarac($PlayerID,"Avancement",15);
										UpdateCarac($PlayerID,"Reputation",10);
										UpdateCarac($PlayerID,"Missions",10);
									}
									else
										UpdateCarac($PlayerID,"Avancement",5);
								}
							}
						}
						elseif($Pilot_eni_skill >$Pilot_lead+50)
						{
							$intro.='<p>'.$wing_txt.' est abattu en flammes par un '.$Nom_eni.'.</p>';
							if($Admin ==1)
							{
								$msg=$intro."<br> Pilote_eni_skill=".$Pilot_eni_skill." Pilote_skill=".$Pilot_lead;
								error_log($msg,1,'binote@hotmail.com','Evade : Combat Ailier');
							}
							$img=Afficher_Image('images/hit'.$country.$Type_avion.'.jpg',"images/hit.jpg","Touché");
							if($Simu and !$Sandbox and $Mission_Type !=103) //L'ailier ou le leader ne diminue pas l'escorte si abattu
							{
								if($Ailier)
								{
									$con=dbconnecti();
									$result=mysqli_query($con,"SELECT j.Avion,u.Avion1,u.Avion2,u.Avion3,j.Unit FROM Pilote_IA as j,Unit as u WHERE j.Unit=u.ID AND j.ID='$Ailier'");
									$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$Ailier'");
									mysqli_close($con);
									if($result)
									{
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
										{
											$Avion_lose=$data['Avion'];
											$Unit_lose=$data['Unit'];
											if($Avion_lose == $data['Avion3'])
												$Avion_Flight_Lose="Avion3_Nbr";
											elseif($Avion_lose == $data['Avion2'])
												$Avion_Flight_Lose="Avion2_Nbr";
											else
												$Avion_Flight_Lose="Avion1_Nbr";
										}
										mysqli_free_result($result);
									}
									if(!$Discipline_fer or mt_rand(0,1) >0)
										WoundPilotIA($Ailier);
									UpdateData("Unit",$Avion_Flight_Lose,-1,"ID",$Unit_lose);
									//UpdateData("Unit","Reputation",-10,"ID",$Unit_lose);
									AddEvent("Avion",96,$Avion_lose,$PlayerID,$Unit_lose,$Cible,$avion_eni,$Ailier);
									UpdateCarac($PlayerID,"S_Formation",-1);
								}
							}
							if($Sandbox)
								SetData("Pilote","S_Ailier",0,"ID",$PlayerID);
							else
								SetData("Pilote","Ailier",0,"ID",$PlayerID);
							$Leader=false;
							$UpdateMoral-=5;
							$mission3=true;
						}
						else
						{
							$intro.='<p>'.$wing_txt.' ne parvient pas à éliminer votre adversaire.</p>';
							$img="<img src='images/miss_leader".$country.".jpg' style='width:100%;'>";	
							$mission3=true;
						}
						unset($ManAvion_lead);
						unset($VitAvion_lead);
					}
				break;
				case 7:
					//Tenter de fuir le combat en bénéficiant de votre vitesse (piqué).
					$essence-=(10+$Conso);
					$chemin-=1;
					if($Type_avion ==1 and ($Mun1 >0 or $Mun2 >0))
					{
						$UpdateGrade-=2;
						$UpdateReput-=2;
						$UpdateCourage-=5;
					}
					$alt=SetAlt($alt,$Plafond,$Plafond_eni,-2000,-500,$c_gaz);
					$pique_ok=false;
					if($alt <100)
					{
						if($Pilotage+$Trompe_la_mort+$AsPique >(200-$alt))
						{
							$intro.="<br>Effectuant votre ressource au ras du sol, vous échappez de peu au crash!";
							$pique_ok=true;
							$alt=100;
						}
						else
						{
							$intro.="<br>Ne parvenant pas à redresser à temps, votre avion percute le sol!";
							$UpdatePilotage -=1;
							$end_mission=true;
							$HP=0;
							$alt=0;
						}
					}
					else
						$pique_ok=true;
					if($pique_ok)
					{
						$SpeedP=GetSpeedP($Avion_db,$avion,$Engine_Nbr,$c_gaz,$flaps);
						$SpeedPeni=GetSpeedP($Avion_db_eni,$avion_eni,$Engine_Nbr_Eni,$c_gaz,$flaps);
						$Injection=GetData("Moteur","ID",$Engine,"Injection");
						$Injection_eni=GetData("Moteur","ID",$Engine_eni,"Injection");
						$Pilot=(mt_rand(0,$Pilotage)/2) - $meteo - $PuissAvion + ($SpeedP*2) + ($Moral/10) + ($Injection*100)+($Noob*50)+$Trompe_la_mort+$AsPique;
						$Pilot_eni=(mt_rand(0,$Pilotage_eni)/2) + $meteo - $PuissAvioneni + ($SpeedPeni*2) + ($Injection_eni*100) + $DetAvion_eni + ($Enis*10);
						//JF
						if($Admin ==1)
						{
							$PuissAvion_txt=$SpeedP*2;
							$PuissAvioneni_txt=$SpeedPeni*2;
							$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
								<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
								<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
								<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
								<tr><th colspan='3'>Fuite (piqué)</th></tr>
								<tr><td colspan='3'>Bonus : Moral</td></tr>
								<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
								<tr><td>Vitesse</td><td>".$PuissAvion_txt."</td><td>".$PuissAvioneni_txt."</td></tr>
								<tr><td>Puissance</td><td>".$PuissAvion."</td><td>".$PuissAvioneni."</td></tr>
								<tr><td>Injection</td><td>".$Injection."</td><td>".$Injection_eni."</td></tr>
								<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
								<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
							</table>";
							$Pilot+=5000;
						}
						//End JF
						unset($Injection);
						unset($Injection_eni);
						$img=Afficher_Image('images/avions/pique'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						if($Pilot >=$Pilot_eni and !$Chk_Evade)
						{
							$car_up=mt_rand(0,1);
							$UpdatePilotage+=$car_up;
							if($VitAvion >500)
								$UpdateTactique+=$car_up;
							elseif($VitAvion <300)
								$UpdateTactique-=1;
							$UpdateMoral +=1;
							$intro.='<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
							$nav=true;
						}
						elseif($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							$intro.="<br>Vous ne parvenez pas à décoller votre adversaire de vos 6 heures!";
							if($Enis <3)$UpdateMoral -=2;
							$Shoots=true;
						}
						else
						{
							if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
								$UpdateTactique -=1;
							$intro.='<br>Vous laissez filer votre proie...<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
							$nav=true;
						}
					}
				break;
				case 8:
					//Tenter un tonneau barriqué pour forcer l'adversaire à vous dépasser (overshoot).
					$essence-=(5+$Conso);
					UpdateData("Pilote","Stress_Commandes",1,"ID",$PlayerID);
					if($c_gaz >90)$UpdateStress_Moteur+=1;
					$Pilot=mt_rand(0,$Acrobatie*2) + $meteo + ($ManAvion*2) - ($PuissAvion/3) + ($ManiAvion*3) + mt_rand(0,$Tactique*2);
					$Pilot_eni=mt_rand(10,$Pilotage_eni*2) + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + ($ManiAvion_eni*3) + mt_rand(10,$Tactique_eni*2) + ($Enis*20);
					if($Proies)$Pilot_eni+=50;
					//JF
					if($PlayerID ==1 or $PlayerID ==2)
					{
						$PuissAvion_txt=$PuissAvion/3;
						$PuissAvioneni_txt=$PuissAvioneni/3;
						$ManAvion_txt=$ManAvion*2;
						$ManAvion_eni_txt=$ManAvion_eni*2;
						$ManiAvion_txt=$ManiAvion*3;
						$ManiAvion_eni_txt=$ManiAvion_eni*3;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><th colspan='3'>Tonneau Barriqué</th></tr>
							<tr><td colspan='3'>Bonus : Moral</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
							<tr><td>Roulis</td><td>".$ManiAvion_txt."</td><td>".$ManiAvion_eni_txt."</td></tr>
							<tr><td>Acrobatie</td><td>".$Acrobatie."</td><td>".$Pilotage_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					//End JF
					if($Pilot >=$Pilot_eni and ($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12) and !$Chk_Evade)
					{
						$Dist_shoot=round(($VitAvioneni-$VitAvion)-($Pilot-$Pilot_eni));
						$Angle_shoot=SetAngle_shoot($Pilot,$Pilot_eni,0,60);
						if($MDeflect)$Angle_shoot-=5;
						elseif($ExDeflect)$Angle_shoot-=2;
						if($Angle_shoot<0)$Angle_shoot=0;
						if($Dist_shoot <10)$Dist_shoot=mt_rand(9,25);
						if($Simu and !$Sandbox)
						{
							/*$car_up=mt_rand(0,1);
							UpdateCarac($PlayerID,"Acrobatie",$car_up);
							$UpdateTactique+=$car_up;*/
							$UpdateMoral+=1;
						}
						$intro.='<br>Vous exécutez votre manoeuvre à la perfection, forçant votre adversaire à vous dépasser!<br>Votre cible se trouve à '.$Dist_shoot.'m, sous un angle de '.$Angle_shoot.'°';
						$img.="<img src='images/escape2.jpg' style='width:100%;'>";
						$shoot_tab=true;
					}
					else
					{
						if($Equipage_eni >1)
						{
							$intro.="<br>Le mitrailleur de l'avion ennemi vous a pris pour cible!";
							$Shoots=true;
						}
						else
						{
							$UpdateMoral -=1;
							$intro.="<br>Vous ne parvenez pas à décoller votre adversaire de vos 6 heures!";
							$Shoots=true;
						}
					}
				break;
				case 9:
					//Tenter de fuir (immelman inversé)
					$essence-=(10+$Conso);
					UpdateData("Pilote","Stress_Commandes",1,"ID",$PlayerID);
					$pique_ok=false;
					$rand_immel=round($VitAvion);
					$rand_immel_max=$rand_immel*4;
					$alt-=mt_rand($rand_immel,$rand_immel_max);
					unset($rand_immel);
					unset($rand_immel_max);
					if($alt <100)
					{
						if($Pilotage >(200-$alt))
						{
							$intro.="<br>Effectuant votre ressource au ras du sol, vous échappez de peu au crash!";
							$pique_ok=true;
							$alt=mt_rand(10,100);
						}
						else
						{
							$intro.="<br>Ne parvenant pas à redresser à temps, votre avion percute le sol!";
							$UpdatePilotage -=1;
							//UpdateCarac($PlayerID,"Acrobatie",-1);
							$end_mission=true;
							$HP=0;
							$alt=0;
						}
					}
					else
					{
						$intro.="<br>Effectuant votre ressource, vous parvenez à redresser.";
						$pique_ok=true;
					}
					if($pique_ok)
					{
						if($Simu and !$Sandbox and $Mission_Type !=103)UpdateCarac($PlayerID,"Avancement",-5);
						$Pilot=(mt_rand(0,$Acrobatie)*3) - $meteo + $ManAvion + ($ManiAvion*2) - ($PuissAvion/3) + $VitAvion + ($Moral/10) + mt_rand(0,$Tactique);
						$Pilot_eni=(mt_rand(0,$Acrobatie_eni)*3) + $meteo + $ManAvion_eni + ($ManiAvion_eni*2) - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(0,$Tactique_eni);
						//JF
						if($PlayerID ==1 or $PlayerID ==2)
						{
							$PuissAvion_txt=$PuissAvion/3;
							$PuissAvioneni_txt=$PuissAvioneni/3;
							$ManiAvion_txt=$ManiAvion*2;
							$ManiAvion_eni_txt=$ManiAvion_eni*2;
							$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
								<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
								<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
								<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
								<tr><td colspan='3'>Bonus : Moral</td></tr>
								<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
								<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
								<tr><td>Roulis</td><td>".$ManiAvion_txt."</td><td>".$ManiAvion_eni_txt."</td></tr>
								<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
								<tr><td>Acrobatie (x2)</td><td>".$Acrobatie."</td><td>".$Acrobatie_eni."</td></tr>
								<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
								<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
								<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
							</table>";
						}
						//End JF
						if($Pilot >=$Pilot_eni and !$Chk_Evade)
						{
							if($Simu and !$Sandbox)
							{
								/*$car_up=mt_rand(0,1);
								UpdateCarac($PlayerID,"Acrobatie",$car_up);*/
								$UpdateMoral+=1;
							}
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							$intro.="<br>Vous parvenez à vous échapper et continuez votre route."; //L'altimètre indique ".$alt."m";
							$nav=true;
						}
						else
						{
							if($Enis <3)$UpdateMoral-=2;
							$intro.="<br>Vous ne parvenez pas à vous échapper."; //L'altimètre indique ".$alt."m";
							$Shoots=true;
						}
					}
				break;
				case 10:
					//Abandonner l'appareil et saut en parachute
					$UpdateGrade-=5;
					$UpdateReput-=10;
					$UpdateCourage-=10;
					$end_mission=true;
					$_SESSION['Parachute']=true;
				break;
				case 11: case 14:
					//Tenter de fuir (rase-motte)
					$essence-=(10+$Conso);
					$chemin-=5;
					if($Action ==14)
					{
						$con=dbconnecti();
						$updateis=mysqli_query($con,"UPDATE Pilote SET Stress_Commandes=Stress_Commandes+1,Skill_Ins=1 WHERE ID='$PlayerID'");
						mysqli_close($con);
					}
					else
						UpdateData("Pilote","Stress_Commandes",1,"ID",$PlayerID);
					if($VitAvion <50)
					{
						$intro.="<br>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !";
						$end_mission=true;
					}
					else
					{
						if($Fou_Volant and mt_rand(0,1) >0)
						{
							$Pilot=1;
							$Pilot_eni=0;
						}
						else
						{
							$alt_eni=$alt;
							$alt=mt_rand(100,500);
							$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,$alt_eni,$PlayerID,$Unite);
							$ManAvion=GetMano($ManH,$ManB,$HPmax,$HP,$alt,$moda,1,$flaps);
							$ManiAvion=GetMani($Mani,$HPmax,$HP,$moda,1,$flaps);
							$Pilot=(mt_rand(0,$Acrobatie)*3)-$meteo+($ManAvion*3)+($ManiAvion*2)-($PuissAvion/3)+$SpeedP+($Moral/10)+mt_rand(0,$Tactique)-$VisAvion+$Trompe_la_mort;
							$Pilot_eni=(mt_rand(0,$Acrobatie_eni)*3)+$meteo+($ManAvion_eni*3)+($ManiAvion_eni*2)-($PuissAvioneni/3)+$SpeedPeni+mt_rand(0,$Tactique_eni);
							//JF
							if($PlayerID ==1 or $PlayerID ==2)
							{
								$PuissAvion_txt=$PuissAvion/3;
								$PuissAvioneni_txt=$PuissAvioneni/3;
								$ManiAvion_txt=$ManiAvion*2;
								$ManiAvion_eni_txt=$ManiAvion_eni*2;
								$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
									<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
									<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
									<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
									<tr><td colspan='3'>Bonus : Moral</td></tr>
									<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
									<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
									<tr><td>Roulis</td><td>".$ManiAvion_txt."</td><td>".$ManiAvion_eni_txt."</td></tr>
									<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
									<tr><td>Acrobatie (x2)</td><td>".$Acrobatie."</td><td>".$Acrobatie_eni."</td></tr>
									<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
									<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
									<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
								</table>";
							}
							//Porte-bonheur
							if($Slot10 ==34 or $Slot10 ==71 or $Slot10 ==72 or $Slot10 ==77)$Pilot*=1.01;
						}
						//End JF
						if($Pilot >=$Pilot_eni and !$Chk_Evade)
						{
							if($Simu and !$Sandbox)
							{
								/*$car_up=mt_rand(0,1);
								UpdateCarac($PlayerID,"Acrobatie",$car_up);*/
								$UpdateMoral +=1;
							}
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							$intro.="<br>Vous parvenez à vous échapper et continuez votre route en volant au ras du sol.";
							$nav=true;
						}
						else
						{
							$intro.="<br>Vous ne parvenez pas à vous échapper.";
							$Shoots=true;
						}
					}
				break;
				case 12:
					//Tenter de fuir le combat en grimpant.
					$essence-=(10+$Conso);
					$chemin-=1;
					if($Type_avion ==1 and ($Mun1 >0 or $Mun2 >0))
					{
						$UpdateGrade-=2;
						$UpdateReput-=2;
						$UpdateCourage-=5;
					}
					$alt=SetAlt($alt,$Plafond,$Plafond,$VitAAvion,$VitAAvion,$c_gaz);
					if($alt >$Plafond_eni and !$Chk_Evade)
					{
						$intro.="<br>L'ennemi ne peut vous atteindre à cette altitude, vous lui échappez!";
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						$UpdateMoral +=1;
						$nav=true;
					}
					else
					{
						$Pilot=(mt_rand(0,$Pilotage)/2) + $meteo - ($PuissAvion/2) + ($VitAAvion*2);
						$Pilot_eni=(mt_rand(0,$Pilotage_eni)/2) + $meteo - ($PuissAvioneni/2) + ($VitAAvioneni*2) + ($Enis*20);
						if($Pilot >=$Pilot_eni and !$Chk_Evade)
						{
							$car_up=mt_rand(0,1);
							$UpdateTactique+=$car_up;
							$UpdateMoral+=1;
							unset($car_up);
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							$intro.='<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
							$nav=true;
						}
						elseif($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							$img.="<img src='images/visee.jpg' style='width:100%;'>";
							if($Enis >2 and $Enis >$Formation+1)
								$intro.="<br>Vos adversaires, trop nombreux, vous empêchent de fuir!";
							else
								$intro.="<br>Votre adversaire grimpe plus vite que vous et vous empêche de fuir!";
							if($Enis <3)$UpdateMoral -=2;
							if(!$PVP and $Pilot_eni >$Pilot+50)
								$Shoots=true;
							else
								$mission3=true;
						}
						else
						{
							if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
								$UpdateTactique -=1;
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							$intro.='<br>Vous laissez filer votre proie...<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
							$nav=true;
						}
					}
				break;
				case 13:
					//Concentrer le tir de votre formation.
					if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
					{
						$essence-=(5+$Conso);
						//$chemin-=5;
						if(!$Chk_Evade and $Simu)$UpdateTactique+=1;
						//Mitrailleur arrière avion
						if($Equipage_Nbr >1 and $Formation >0)
						{
							if($Arme3 >0)
								$Arme3Avion=$ArmeArriere;
							else
							{
								$Arme3Avion=$TourelleSup;
								$Arme3=$Arme5;
							}	
							if($Arme3Avion !=5 and $Arme3Avion !=0)
							{
								$go_shoot=true;
								//Equipage
								if($Equipage and $Endu_Eq >0)
								{
									$con=dbconnecti();		
									$result=mysqli_query($con,"SELECT Courage,Moral,Tir,Radio,Trait FROM Equipage WHERE ID='$Equipage'");		
									mysqli_close($con);		
									if($result)		
									{		
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))	
										{	
											$Courage_Eq=$data['Courage'];
											$Moral_Eq=$data['Moral'];
											$Tir_mg=$data['Tir'];
											$Radio_Eq=$data['Radio'];
											$Trait_e=$data['Trait'];
										}	
										mysqli_free_result($result);
										unset($data);
									}
									if($Trait_e ==1)
										$Tir_mg=round($Tir_mg*1.1);
									elseif($Trait_e ==2 and $Courage_Eq <100)
										$Courage_Eq=100;
									elseif($Trait_e ==8 and $Moral_Eq <100)
										$Moral_Eq=100;
									if($Courage_Eq <1 and $Trait_e !=6)
									{
										$go_shoot=false;
										$Etat_Eq=",il est tétanisé par la peur";
									}
									if($Moral_Eq <1 and $Trait_e !=6)
									{
										$go_shoot=false;
										$Etat_Eq=",il est démoralisé";
									}
								}
								else
								{
									$Skill_min=10+($Personnel[13]*10);
									$Radio_Eq=10+$Skill_min;
									$Tir_mg=mt_rand($Skill_min,250);
								}
								if($go_shoot and !$Chk_Evade)
								{
									//Addition Equipage et Formation
									$Bonus_radio=($Radio_Eq/20+$Radio_a)/10;
									$Tir_mg=mt_rand(10,$Tir_mg)*(1+$Bonus_radio);
									if($Pilotage_eni <10)
										$Pilotage_eni=mt_rand(100,200);
									if($Tactique_eni <10)
										$Tactique_eni=mt_rand(100,200);
									if($HP_eni <500)
									{
										$Rand_Pil_eni=$Pilotage_eni;
										$Rand_Tac_eni=$Tactique_eni;
									}
									else
									{
										$Rand_Pil_eni=mt_rand(10,$Pilotage_eni);
										$Rand_Tac_eni=mt_rand(10,$Tactique_eni);
									}
									$Shoot=$Tir_mg+$meteo+$Vis_eni-($ManAvion_eni/10)-($Rand_Pil_eni/2)-($Rand_Tac_eni/2)+($Moral_Eq/10);
									//JF
									if($PlayerID ==1 or $PlayerID ==2)
									{
										$skills.="<br>[Score de Tir MG : ".$Shoot."]
															<br>+Vis_eni ".$Vis_eni." 
															<br>-Man ".$ManAvion_eni." /10
															<br>-Tactique ".$Tactique_eni."/2 (rand)
															<br>-Pilotage ".$Pilotage_eni."/2 (rand)
															<br>Tir_mg :".$Tir_mg;
									}
									//End JF
									if($Shoot >0)
									{
										$UpdateMoral +=1;
										if($Equipage and $Equipage_Nbr >1 and $Simu)
										{
											UpdateCarac($Equipage,"Moral",1,"Equipage");
											UpdateCarac($Equipage,"Tir",1, "Equipage");									
											UpdateCarac($Equipage,"Radio",1,"Equipage");
										}
										$Arme3Avion_Dg=GetData("Armes","ID",$Arme3Avion,"Degats");
										$Arme3Avion_Multi=GetData("Armes","ID",$Arme3Avion,"Multi");
										$Avion_Mun=GetData("Pilote","ID",$PlayerID,"S_Avion_Mun");
										if($Arme3Avion_Dg <1)$Arme3Avion_Dg=1;
										if($Arme3Avion_Multi <1)$Arme3Avion_Multi=1;
										$Arme3 +=$Formation-1;
										if($Arme3 >10)$Arme3=10;
										$Arme3Avion_nbr=GetShoot($Shoot,$Arme3);
										$Bonus_Dg=Damage_Bonus($Avion_db,$avion,$avion_eni,$Arme3Avion,$Blindage_eni,$Avion_Mun);
										$Degats=0;
										for($i=1;$i<=$Arme3Avion_nbr;$i++)
										{
											$Degats+=(mt_rand(1,$Arme3Avion_Dg)+$Bonus_Dg-pow($Blindage_eni,2))*mt_rand(1,$Arme3Avion_Multi);
										}
										$Mun_mg=$Arme3Avion_Multi*10;
										UpdateCarac($PlayerID,"S_Tourelle_Mun",-$Mun_mg);
										//$Degats=(rand(1,$Arme3Avion_Dg)*mt_rand(1,$Arme3Avion_Multi));
										$HP_eni-=$Degats;
										if($Degats <1)$Degats=mt_rand(1,5);
										if($HP_eni <1)
										{
											if($Equipage and $Equipage_Nbr >1 and $Simu)
											{
												UpdateCarac($Equipage,"Avancement",10,"Equipage");
												UpdateCarac($Equipage,"Reputation",10,"Equipage");
												UpdateCarac($Equipage,"Moral",10,"Equipage");
												UpdateCarac($Equipage,"Victoires",1,"Equipage");
											}
											$wing_txt="Un mitrailleur";
											$intro.="<br>Un mitrailleur de votre formation fait mouche et abat l'avion ennemi !";
											$img=Afficher_Image('images/kill'.$country.'.jpg',"images/kill.jpg","Victoire!");
											$end_shoot=true;
										}
										else
										{
											$intro.='<br>Le tir concentré de votre formation touche l\'avion ennemi!'; //lui occasionnant <b>'.$Degats.'</b> points de dégats!';
											$Shoots=true;
											if($PVP)UpdateData("Duels_Candidats","HP",$Degats,"Target",$PlayerID);
										}
									}
									else
									{
										$intro.="<br>Le tir concentré de votre formation manque sa cible.";
										$Shoots=true;
									}
								}
								else
								{
									$intro.='<br>Votre mitrailleur refuse d\'obéir aux ordres'.$Etat_Eq;
									$Shoots=true;
								}
							}
							else
							{
								$intro.="<br>Les avions de votre formation ne disposent pas de l'armement nécessaire pour exécuter vos ordres.";
								$UpdateTactique-=2;
								$Shoots=true;
							}
						}
						else
							$Shoots=true;
					}
					else
					{
						$intro.='<br>L\'ennemi poursuit sa route en vous évitant.<br>Vous continuez votre route vers votre objectif, à environ '.$alt.'m d\'altitude.';
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						$nav=true;
					}	
				break;
				case 18:
					$intro.="<p><b>Vous vous délestez de vos charges!</b></p>";
					$img=Afficher_Image('images/alleger.jpg',"images/image.png", "");
					SetData("Pilote","S_Avion_Bombe_Nbr",0,"ID",$PlayerID);
					$mission3=true;				
				break;
				case 19:
					$intro .="<p><b>Vous larguez votre réservoir supplémentaire!</b></p>";
					$img=Afficher_Image('images/alleger.jpg',"images/image.png", "");
					SetData("Pilote","S_Baby",0,"ID",$PlayerID);
					$mission3=true;				
				break;
				case 20:
					//Appeler l'escorte à l'aide
					if($Escorte_nbr >0 and $Mission_Type !=3 and $Mission_Type !=103)
					{
						$con=dbconnecti();
						$Escorte_PJ_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Escorte='$Cible' AND p.Faction='$Faction' AND j.Actif=1"),0);
						mysqli_close($con);
						if($Escorte_PJ_Nbr >0)
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT DISTINCT j.ID,j.Tactique,j.Pilotage,j.Nom,j.Avion FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Escorte='$Cible' AND p.Faction='$Faction' AND j.Actif=1 ORDER BY RAND() LIMIT 1");
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_NUM))
								{
									$PJ_Couv_Final=$data[0];
									$Tactique_ailier=$data[1]+(10*$Escorte_PJ_Nbr);
									$Pilotage_ailier=$data[2]+(10*$Escorte_PJ_Nbr);
									$avion_lead=$data[4];
									$wing_txt=$data[3];
								}
								mysqli_free_result($result);
							}
							else
							{
								$avion_lead=GetData("Unit","ID",$Escorte,"Avion1");
								$wing_txt='Le pilote du '.$Escorte_nom.' vous escortant';
							}							
							$Combat_Ailier=true;
						}
						else
						{
							$intro.="<p>Votre escorte ne répond pas à l'appel!</p>";
							$img="<img src='images/facetoface.jpg' style='width:100%;'>";
							$Combat_Ailier=false;
							$mission3=true;
						}
					}
					else
					{
						$intro.="<p>Vous n'avez pas d'escorte!</p>";
						$img="<img src='images/facetoface.jpg' style='width:100%;'>";
						$Combat_Ailier=false;
						$mission3=true;
					}					
					if($Combat_Ailier and $PJ_Couv_Final)
					{
						//Ailier/Lead vs Eni
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT * FROM Avion WHERE ID='$avion_lead'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$HP_lead=$data['Robustesse'];
								$ManH_lead=$data['ManoeuvreH'];
								$ManB_lead=$data['ManoeuvreB'];
								$Mani_lead=$data['Maniabilite'];
								$Puissance_lead=$data['Puissance'];
								$Engine_lead=$data['Engine'];
								$Engine_Nbr_lead=$data['Engine_Nbr'];
								$Masse_lead=$data['Masse'];
								$Alt_ref_lead=$data['Alt_ref'];
							}
							mysqli_free_result($result);
						}
						$ManAvion_lead=GetMano($ManH_lead,$ManB_lead,$HP_lead,$HP_lead,$alt);
						$ManiAvion_lead=GetMani($Mani_lead,$HP_lead,$HP_lead);
						$PuissAvion_lead=GetPuissance("Avion",$avion_lead,$alt,$HP_lead,1,1,$Engine_Nbr_lead);
						$VitAvion_lead=GetSpeed("Avion",$avion_lead,$alt,$meteo);
						if($Pilotage_eni <10)$Pilotage_eni=mt_rand(100,200);
						if($Tactique_eni <10)$Tactique_eni=mt_rand(100,200);
						$Pilot_lead=mt_rand(10,$Pilotage_ailier)+$meteo + $ManAvion_lead + $ManiAvion_lead - ($PuissAvion_lead/2) + $VitAvion_lead + mt_rand(10,$Tactique_ailier)+($Radio_a*10)+($Escorte_nbr*10)+$VoixRass;
						$Pilot_eni_skill=mt_rand(10,$Pilotage_eni)+$meteo + $ManAvion_eni + $ManiAvion_eni - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(10,$Tactique_eni)+($Enis*10);
						if($Pilot_lead >$Pilot_eni_skill and !$Chk_Evade)
						{
							$intro.='<p>'.$wing_txt.' vous sort de ce mauvais pas en mettant en fuite l\'ennemi qui vous prenait pour cible!</p>';
							$img=Afficher_Image('images/kill'.$country.'.jpg',"images/kill.jpg","Victoire!");	
							$Enis-=1;
							UpdateData("Pilote","enis",-1,"ID",$PlayerID);
							if($Enis >0)
							{
								$mission3=true;
								$HP_eni=$HPmax_eni;
							}
							else
							{
								$UpdateMoral +=1;
								if(!$PVP and $HP_eni <$HPmax_eni and $Simu and !$Sandbox and $Mission_Type !=103 and $Cible and $Pilote_eni)
									AddProbable($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,2);
								$nav=true;
							}
							if($Mission_Type !=3 and $Mission_Type !=103)
							{							
								$con=dbconnecti();
								$update=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$Pilote_eni'");
								mysqli_close($con);
								if(($Type_avioneni ==1 or $Type_avioneni ==12) and $Simu and !$Sandbox)
								{
									if(GetData("Pilote_IA","ID",$Pilote_eni,"Escorte") ==$Cible)
										AddEvent($Avion_db,180,$avion,$PlayerID,$Cible_Escorte,$Cible,$avion_eni,$PJ_Couv_Final);
									else
									{
										AddEvent("Avion",191,$avion_lead,$PJ_Couv_Final,$Cible_Escorte,$Cible,$avion_eni,$Pilote_eni);
										AddEvent("Avion",189,$avion_lead,$PJ_Couv_Final,$Unite,$Cible,$avion_eni,$Pilote_eni);
									}	
									$skills.="<p>Vous réduisez la couverture aérienne ennemie sur la zone!</p>";
									if($Mission_Type ==26 or $Mission_Type ==31)
									{
										UpdateCarac($PlayerID,"Avancement",15);
										UpdateCarac($PlayerID,"Reputation",10);
										UpdateCarac($PlayerID,"Missions",10);
									}
									else
										UpdateCarac($PlayerID,"Avancement",5);
								}
							}
						}
						elseif($Pilot_eni_skill >$Pilot_lead+50)
						{
							$intro.='<p>'.$wing_txt.' est abattu en flammes par un '.$Nom_eni.'.</p>';
							if($Admin ==1)
							{
								$msg=$intro."<br> Pilote_eni_skill=".$Pilot_eni_skill." Pilote_skill=".$Pilot_lead;
								error_log($msg,1,'binote@hotmail.com','Evade : Combat Ailier');
							}
							$img=Afficher_Image('images/hit'.$country.$Type_avion.'.jpg',"images/hit.jpg","Touché");
							if($Simu and !$Sandbox and $Mission_Type !=103)
							{
								if($PJ_Couv_Final)
								{
									$con=dbconnecti();
									$result=mysqli_query($con,"SELECT j.Avion,u.Avion1,u.Avion2,u.Avion3,j.Unit FROM Pilote_IA as j,Unit as u WHERE j.Unit=u.ID AND j.ID='$PJ_Couv_Final'");
									$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$PJ_Couv_Final'");
									mysqli_close($con);
									if($result)
									{
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
										{
											$Avion_lose=$data['Avion'];
											$Unit_lose=$data['Unit'];
											if($Avion_lose ==$data['Avion3'])
												$Avion_Flight_Lose="Avion3_Nbr";
											elseif($Avion_lose ==$data['Avion2'])
												$Avion_Flight_Lose="Avion2_Nbr";
											else
												$Avion_Flight_Lose="Avion1_Nbr";
										}
										mysqli_free_result($result);
									}
									if(!$Discipline_fer or mt_rand(0,1) >0)
										WoundPilotIA($PJ_Couv_Final);
									UpdateData("Unit",$Avion_Flight_Lose,-1,"ID",$Unit_lose);
									//UpdateData("Unit","Reputation",-10,"ID",$Unit_lose);
									AddEvent("Avion",96,$Avion_lose,$PlayerID,$Unit_lose,$Cible,$avion_eni,$PJ_Couv_Final);
								}
							}
							UpdateData("Pilote","S_Escorte_nbr",-1,"ID",$PlayerID);
							$UpdateMoral-=5;
							$Escorte_nbr-=1;
							$mission3=true;
						}
						else
						{
							$intro.='<p>'.$wing_txt.' ne parvient pas à éliminer votre adversaire.</p>';
							$img="<img src='images/miss_leader".$country.".jpg' style='width:100%;'>";	
							$mission3=true;
						}
						unset($ManAvion_lead);
						unset($VitAvion_lead);
					}
					else
					{
						$intro.='<p>Votre escorte ne parvient pas à éliminer votre adversaire.</p>';
						$img="<img src='images/miss_leader".$country.".jpg' style='width:100%;'>";	
						$mission3=true;
					}
				break;
				case 21:
					//Appeler la formation à l'aide
					if($Formation >0 and $Mission_Type !=3 and $Mission_Type !=103)
					{
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT DISTINCT ID,Tactique,Pilotage,Nom,Avion FROM Pilote_IA WHERE Cible='$Cible' AND Unit='$Unite' AND Actif=1 ORDER BY RAND() LIMIT 1");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_NUM))
							{
								$PJ_Couv_Final=$data[0];
								$Tactique_ailier=$data[1]+(10*$Escorte_PJ_Nbr);
								$Pilotage_ailier=$data[2]+(10*$Escorte_PJ_Nbr);
								$avion_lead=$data[4];
								$wing_txt=$data[3];
							}
							mysqli_free_result($result);
						}
						else
						{
							$avion_lead=GetData("Unit","ID",$Unite,"Avion1");
							$wing_txt='Le pilote de votre formation';
						}							
						$Combat_Ailier=true;
					}
					else
					{
						$intro.='<p>Vous êtes seul!</p>';
						$img="<img src='images/facetoface.jpg' style='width:100%;'>";
						$Combat_Ailier=false;
						$mission3=true;
					}					
					if($Combat_Ailier and $PJ_Couv_Final)
					{
						//Ailier/Lead vs Eni
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT * FROM Avion WHERE ID='$avion_lead'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$HP_lead=$data['Robustesse'];
								$ManH_lead=$data['ManoeuvreH'];
								$ManB_lead=$data['ManoeuvreB'];
								$Mani_lead=$data['Maniabilite'];
								$Puissance_lead=$data['Puissance'];
								$Engine_lead=$data['Engine'];
								$Engine_Nbr_lead=$data['Engine_Nbr'];
								$Masse_lead=$data['Masse'];
								$Alt_ref_lead=$data['Alt_ref'];
							}
							mysqli_free_result($result);
						}
						$ManAvion_lead=GetMano($ManH_lead,$ManB_lead,$HP_lead,$HP_lead,$alt);
						$ManiAvion_lead=GetMani($Mani_lead,$HP_lead,$HP_lead);
						$PuissAvion_lead=GetPuissance("Avion",$avion_lead,$alt,$HP_lead,1,1,$Engine_Nbr_lead);
						$VitAvion_lead=GetSpeed("Avion",$avion_lead,$alt,$meteo);
						if($Pilotage_eni <10)$Pilotage_eni=mt_rand(100,200);
						if($Tactique_eni <10)$Tactique_eni=mt_rand(100,200);
						$Pilot_lead=mt_rand(10,$Pilotage_ailier)+$meteo+ $ManAvion_lead + $ManiAvion_lead - ($PuissAvion_lead/2) + $VitAvion_lead + mt_rand(10,$Tactique_ailier)+($Radio_a*10)+($Formation*10)+$VoixRass;
						$Pilot_eni_skill=mt_rand(10,$Pilotage_eni)+$meteo+ $ManAvion_eni + $ManiAvion_eni - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(10,$Tactique_eni)+($Enis*10);
						if($Pilot_lead > $Pilot_eni_skill and !$Chk_Evade)
						{
							$intro.='<p>'.$wing_txt.' vous sort de ce mauvais pas en mettant en fuite l\'ennemi qui vous prenait pour cible!</p>';
							$img=Afficher_Image('images/kill'.$country.'.jpg',"images/kill.jpg","Victoire!");	
							$Enis-=1;
							UpdateData("Pilote","enis",-1,"ID",$PlayerID);
							if($Enis >0)
							{
								$mission3=true;
								$HP_eni=$HPmax_eni;
							}
							else
							{
								$UpdateMoral +=1;
								if(!$PVP and $HP_eni <$HPmax_eni and $Simu and !$Sandbox and $Mission_Type !=103 and $Cible and $Pilote_eni)
									AddProbable($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,2);
								$nav=true;
							}
							if($Mission_Type !=3 and $Mission_Type !=103)
							{
								$con=dbconnecti();
								$update=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$Pilote_eni'");
								mysqli_close($con);
								if(($Type_avioneni ==1 or $Type_avioneni ==12) and $Simu and !$Sandbox)
								{
									if(GetData("Pilote_IA","ID",$Pilote_eni,"Escorte") ==$Cible)
										AddEvent($Avion_db,180,$avion,$PlayerID,$Cible_Escorte,$Cible,$avion_eni,$PJ_Couv_Final);
									else
									{
										AddEvent("Avion",191,$avion_lead,$PJ_Couv_Final,$Cible_Escorte,$Cible,$avion_eni,$Pilote_eni);
										AddEvent("Avion",189,$avion_lead,$PJ_Couv_Final,$Unite,$Cible,$avion_eni,$Pilote_eni);
									}	
									$skills.="<p>Vous réduisez la couverture aérienne ennemie sur la zone!</p>";
									if($Mission_Type ==26 or $Mission_Type ==31)
									{
										UpdateCarac($PlayerID,"Avancement",15);
										UpdateCarac($PlayerID,"Reputation",10);
										UpdateCarac($PlayerID,"Missions",10);
									}
									else
										UpdateCarac($PlayerID,"Avancement",5);
								}
							}
						}
						elseif($Pilot_eni_skill >$Pilot_lead+50)
						{
							$intro.='<p>'.$wing_txt.' est abattu en flammes par un '.$Nom_eni.'.</p>';
							if($Admin ==1)
							{
								$msg=$intro."<br> Pilote_eni_skill=".$Pilot_eni_skill." Pilote_skill=".$Pilot_lead;
								error_log($msg,1,'binote@hotmail.com','Evade : Combat Ailier');
							}
							$img=Afficher_Image('images/hit'.$country.$Type_avion.'.jpg',"images/hit.jpg","Touché");
							if($Simu and !$Sandbox and $Mission_Type !=103)
							{
								if($PJ_Couv_Final)
								{
									$con=dbconnecti();
									$result=mysqli_query($con,"SELECT j.Avion,u.Avion1,u.Avion2,u.Avion3,j.Unit FROM Pilote_IA as j,Unit as u WHERE j.Unit=u.ID AND j.ID='$PJ_Couv_Final'");
									$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$PJ_Couv_Final'");
									mysqli_close($con);
									if($result)
									{
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
										{
											$Avion_lose=$data['Avion'];
											$Unit_lose=$data['Unit'];
											if($Avion_lose == $data['Avion3'])
												$Avion_Flight_Lose="Avion3_Nbr";
											elseif($Avion_lose == $data['Avion2'])
												$Avion_Flight_Lose="Avion2_Nbr";
											else
												$Avion_Flight_Lose="Avion1_Nbr";
										}
										mysqli_free_result($result);
									}
									if(!$Discipline_fer or mt_rand(0,1) >0)
										WoundPilotIA($PJ_Couv_Final);
									UpdateData("Unit",$Avion_Flight_Lose,-1,"ID",$Unit_lose);
									//UpdateData("Unit","Reputation",-10,"ID",$Unit_lose);
									AddEvent("Avion",96,$Avion_lose,$PlayerID,$Unit_lose,$Cible,$avion_eni,$PJ_Couv_Final);
								}
							}
							UpdateData("Pilote","S_Escorte_nbr",-1,"ID",$PlayerID);
							$UpdateMoral-=5;
							$Escorte_nbr-=1;
							$mission3=true;
						}
						else
						{
							$intro.='<p>'.$wing_txt.' ne parvient pas à éliminer votre adversaire.</p>';
							$img="<img src='images/miss_leader".$country.".jpg' style='width:100%;'>";	
							$mission3=true;
						}
						unset($ManAvion_lead);
						unset($VitAvion_lead);
					}
					else
					{
						$intro.='<p>Votre pilote ne parvient pas à éliminer votre adversaire.</p>';
						$img="<img src='images/miss_leader".$country.".jpg' style='width:100%;'>";	
						$mission3=true;
					}
				break;
				case 98:
					$intro.="<br>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !";
					$end_mission=true;
				break;
			}		
			//Combat tournoyant ou acrobatie
			if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
				$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Virer de plus en plus serré pour reprendre l'avantage.<br>";
			else
				$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Effectuer une manœuvre pour tenter d'attaquer l'ennemi par le flanc.<br>";
		}	
		//***WRITE TO DB***	
		if(!$Chk_Evade and !$Sandbox)
		{
			if($UpdateMoral !=0)
				UpdateCarac($PlayerID,"Moral",$UpdateMoral);
			if($UpdateCourage !=0)
				UpdateCarac($PlayerID,"Courage",$UpdateCourage);
			if($UpdateReput !=0)
				UpdateCarac($PlayerID,"Reputation",$UpdateReput);
			if($UpdateGrade !=0)
				UpdateCarac($PlayerID,"Avancement",$UpdateGrade);
			/*if($UpdateTactique !=0)
				UpdateCarac($PlayerID,"Tactique",$UpdateTactique);
			if($UpdatePilotage !=0)
				UpdateCarac($PlayerID,"Pilotage",$UpdatePilotage);*/
			if($UpdateStress_Moteur !=0)
				UpdateCarac($PlayerID,"Stress_Moteur",$UpdateStress_Moteur);
			if($UpdateStress_Commandes !=0)
				UpdateCarac($PlayerID,"Stress_Commandes",$UpdateStress_Commandes);
			if($Update_S_Escorteb_nbr !=0)
				UpdateCarac($PlayerID,"S_Escorteb_nbr",$Update_S_Escorteb_nbr);
			if($Update_Unit_Reput !=0)
				UpdateData("Unit","Reputation",$Update_Unit_Reput,"ID",$Unite,0,1);
		}
		SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
		if($PVP and !$Sandbox)
		{
			SetData("Duels_Candidats","HP",$HP,"PlayerID",$PlayerID);
			SetData("Duels_Candidats","Altitude",$alt,"PlayerID",$PlayerID);
			SetData("Duels_Candidats","Altitude",$alt,"PlayerID",$Pilote_eni);
			SetData("Duels_Candidats","HP",$HP_eni,"PlayerID",$Pilote_eni);
		}
		$Pays_avion_eni=GetData("Unit","ID",$Unit_eni,"Pays");		
		$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$PuissAvion,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);		
		if($panne_seche)
		{
			$_SESSION['done']=false;
			$_SESSION['evader']=true;
			$intro.="<br>Vous tombez en panne sèche!<br>Vous n'avez pas d'autre choix que d'abandonner votre appareil.<br>Vous parvenez à rejoindre vos lignes à grand peine, mais vous êtes en vie!";
			$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
			if(!$Sandbox)
			{
				AddEvent($Avion_db,4,$avion,$PlayerID,$Unite,$Cible);
				if($Reputation >10000)
				{
					UpdateCarac($PlayerID,"Navigation",-10);
					UpdateCarac($PlayerID,"Reputation",-100);
				}
				else
				{
					UpdateCarac($PlayerID,"Navigation",-5);
					UpdateCarac($PlayerID,"Reputation",-10);
				}
				UpdateCarac($PlayerID,"Endurance",-1);
				UpdateCarac($PlayerID,"Moral",-10);
			}
			else
				UpdateCarac($PlayerID,"Free",-1);
			$mes.='<p><b>FIN DE MISSION</b></p>';
			$menu.="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='0'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		if($shoot_tab)
		{
			if($Tactique_eni <1)$Tactique_eni=mt_rand(50,150);
			if($Type_avioneni ==1 and (mt_rand(0,$Tactique_eni) >mt_rand(0,$Tactique)+50 or $Chk_Evade))
			{
				$intro.="<p>Votre adversaire effectue un tonneau barriqué, vous obligeant à le dépasser !</p>";
				$Shoots=true;
			}
			elseif($Enis >1 and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12) and (mt_rand(1,$Tactique_eni) >mt_rand(0,$Tactique)+25))
			{
				$intro.="<p>L'ailier de votre adversaire protège son leader, vous obligeant à dégager !</p>";
				$mission3=true;
			}
			else
			{	//Efficacité de l'arme à cette distance
				$Arme1Avion=GetData($Avion_db,"ID",$avion,"ArmePrincipale");
				$Arme2Avion=GetData($Avion_db,"ID",$avion,"ArmeSecondaire");
				$Arme1=GetData("Armes","ID",$Arme1Avion,"Nom");
				$Arme2=GetData("Armes","ID",$Arme2Avion,"Nom");
				$Arme1Avion_Range=GetData("Armes","ID",$Arme1Avion,"Portee");
				$Malus_Range=GetMalus_Range($Dist_shoot,$Arme1Avion_Range,$Angle_shoot,$VitAvioneni);
				$chk=$meteo-$Malus_Range+($Vis_eni/10)+($Courage/10)-($Pilotage_eni/10);
				if($chk ==0)$chk=-1;
				$luck=round(100/(31/$chk));
				if($luck <1)
					$luck=1;
				elseif($luck >99)
					$luck=99;
				$menu.='[Efficacité de l\'arme à cette distance : '.$luck.' % ]';
				if($Arme2Avion ==5 or $Arme2Avion ==0 or $Arme2Avion ==25 or $Arme2Avion ==26 or $Arme2Avion ==27)
					$DeuxArmes="";
				else
				{
					$DeuxArmes='<Input type=\'Radio\' name=\'Action\' value=\'5\'>- Lâcher une courte rafale à l\'aide de votre '.$Arme2.' ('.$Mun2.' coups).<br>
					<Input type=\'Radio\' name=\'Action\' value=\'6\'>- Lâcher une longue rafale avec votre '.$Arme2.' ('.$Mun2.' coups).<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Courte_Rafale').'\' value=\'7\'>- Lâcher une courte rafale à l\'aide de toutes vos armes de bord.<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Longue_Rafale').'\' value=\'8\'>- Lâcher une longue rafale à l\'aide de toutes vos armes de bord.<br>';
				}				
				SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
				$_SESSION['evader']=true;
				$titre='Combat';
				$img='<img src=\'images/visee'.$country.'.jpg\' style=\'width:100%;\'>';
				$mes.='<form action=\'shoot.php\' method=\'post\'>
							<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>	
							<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
							<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
							<input type=\'hidden\' name=\'Avioneni\' value='.$avion_eni.'>
							<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
							<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
							<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
							<input type=\'hidden\' name=\'Dist_shoot\' value='.$Dist_shoot.'>
							<input type=\'hidden\' name=\'Angle_shoot\' value='.$Angle_shoot.'>
							<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
							<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
							<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
							<input type=\'hidden\' name=\'Unit_eni\' value='.$Unit_eni.'>
							<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
							<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
							'.ShowGaz($avion,$c_gaz,$flaps,$alt,1).'
							'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion).'
							<table class=\'table\'><tr><td align=\'left\'>
										<Input type=\'Radio\' name=\'Action\' value=\'1\'>- Epargner votre adversaire.<br>
										<Input type=\'Radio\' name=\'Action\' value=\'2\'>- Vous rapprocher à la distance idéale pour faire un maximum de dégâts.<br>
										<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Barrique').'\' value=\'11\'>- Effectuer une manoeuvre pour vous rapprocher sans risquer de dépasser votre adversaire.<br>
										<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Courte_Rafale').'\' value=\'3\'>- Lâcher une courte rafale à l\'aide de votre '.$Arme1.' ('.$Mun1.' coups).<br>
										<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Longue_Rafale').'\' value=\'4\'>- Lâcher une longue rafale avec votre '.$Arme1.'('.$Mun1.' coups).<br>'.$DeuxArmes.'
										<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Rompre').'\' value=\'9\' checked>- Rompre le combat.<br>
								</td></tr></table>
							<input type=\'submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
			}
		}		
		if($Shoots)
		{
			$oo_mun_luck=mt_rand(0,100);
			//Porte-bonheur
			if($Slot10 ==34 or $Slot10 ==71 or $Slot10 ==72 or $Slot10 ==77)
				$oo_mun_luck-=5;
			if($Pilotage <100 and $Pilotage_eni >100)
				$oo_mun_luck-=50;
			if(!$PVP and ($oo_mun_luck <5 or $HP_eni <100) and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12))
			{
				$intro.='<p>Le <b>'.$Nom_eni.'</b> abandonne la poursuite pour une raison inconnue.</p>';
				$Enis-=1;
				UpdateData("Pilote","enis",-1,"ID",$PlayerID);
				if($HP_eni <$HPmax_eni/2 and $Simu and !$Sandbox and $Mission_Type !=103 and !$Grosbill and $Cible and $Pilote_eni)
					AddProbable($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,1);
				if($Enis >0)
				{
					//IA=Chasseurs haute altitude
					if($Enis <3 and $alt >6000)
					{
						$Compresseur=GetData("Moteur","ID",$Engine,"Compresseur");
						$Compresseur_eni=GetData("Moteur","ID",$Engine_eni,"Compresseur");
						if($Compresseur >1 and $Compresseur_eni <2)
						{
							$intro.="<p>La formation ennemie ne se sentant pas de taille en profite pour filer !</p>";
							$img=Afficher_Image("images/epargner.jpg","images/enrayage.jpg","Abandon de la poursuite");
							$nav=true;
						}
						else
						{
							$intro.='<p>Malgré l\'abandon d\'un des leurs, un autre <b>'.$Nom_eni.'</b> fonce sur vous !</p>';
							$img=Afficher_Image('images/avions/pique'.$avion_eni.'.jpg','images/avions/vol'.$avion_eni.'.jpg',$Nom_eni);
							$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
							$mission3=true;
						}
					}
					else
					{		
						$intro.='<p>Malgré l\'abandon d\'un des leurs, un autre <b>'.$Nom_eni.'</b> fonce sur vous !</p>';
						$img=Afficher_Image('images/avions/pique'.$avion_eni.'.jpg','images/avions/vol'.$avion_eni.'.jpg',$Nom_eni);
						$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
						$mission3=true;
					}
				}
				else
				{
					$img=Afficher_Image("images/epargner.jpg","images/enrayage.jpg","Abandon de la poursuite");
					$nav=true;
				}
			}
			elseif($PVP)
			{
				$img='<img src=\'images/shooted'.$country.'.jpg\' style=\'width:100%;\'>';
				$intro.="<br>Votre adversaire joue avec vous!";
				$mission3=true;
			}
			else
			{
				if($Tir_eni <10)$Tir_eni=mt_rand(100,200);
				$Rand_Tir_eni=mt_rand(10,$Tir_eni);
				$Shoot=$Rand_Tir_eni+($meteo/2)+($VisAvion/5)-mt_rand(0,$Pilotage)-($ManAvion/10)-($ManiAvion/10)+$Enis-$SixSens;
				if($Proies)$Shoot+=10;
				//JF
				if($PlayerID ==1 or $PlayerID ==2)
				{
					$skills.="<br>[Score de Tir : ".$Shoot."]
										<br>-Meteo ".$Meteo." /2
										<br>-Man ".$ManAvion." /10
										<br>-Mani ".$ManiAvion." /10
										<br>-Pilotage ".$Pilotage." (rand)
										<br>+Vis ".$VisAvion." /10
										<br>+Enis ".$Enis."
										<br>+Tir_eni :".$Rand_Tir_eni;
				}
				//End JF
				$intro.='<p>Le <b>'.$Nom_eni.'</b> tire !</p>';
				if($Shoot >10 or $Rand_Tir_eni ==$Tir_eni)
				{
					$Bonus_Tir=$Enis;
					if($Bonus_Tir >5)$Bonus_Tir=5;
					$Mun_eni=mt_rand(1,5);
					$Bonus_Dg=Damage_Bonus($Avion_db_eni,$avion_eni,$avion,$Arme1Avion_eni,$Blindage,$Mun_eni);
					$Arme1Avion_nbr_eni=GetShoot($Shoot, $Arme1Avion_nbr_eni)+$Bonus_Tir;
					$Degats=0;
					if($Arme1Avion_Dg_eni >0)
					{
						for($i=1;$i<=$Arme1Avion_nbr_eni;$i++)
						{
							if($Rand_Tir_eni ==$Tir_eni)
								$Degats+=round(($Arme1Avion_Dg_eni+$Bonus_Dg-pow($Blindage,2))*$Arme1Avion_Multi_eni);
							else
								$Degats+=round((mt_rand(1,$Arme1Avion_Dg_eni)+$Bonus_Dg-pow($Blindage,2))*mt_rand(1,$Arme1Avion_Multi_eni));
						}
					}
					if($Degats <1)$Degats=mt_rand(1,5);
					if($Proies)
					{
						$Degats+=$VisAvion;
						$Shoot=51;
					}
					//$Mun1-=($Arme1Avion_Multi_eni*$Arme1Avion_nbr_eni);
					$HP-=$Degats;
					//HP Avion perso persistant
					if($Avion_db =="Avions_Persos")
					{
						if($HP <1)$HP=0;
						SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
					}
					if($HP <1)
					{
						if($Premium)
							$intro.='<br>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
						else
							$intro.='<br>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute!';
						$end_mission=true;
						$_SESSION['Parachute']=true;
					}
					else
					{
						//Critical Hit
						if($Shoot >50 or $Rand_Tir_eni ==$Tir_eni)
						{
							$CritH=CriticalHit($Avion_db,$avion,$PlayerID,$Mun_eni,$Mun_eni,$Engine_Nbr);
							$intro.=$CritH[0];
							$end_mission =$CritH[1];
							if($end_mission)
								$HP=0;
							if($CritH[2] ==1)
								$Mun1=0;
							if($CritH[3] ==1)
								$Mun2=0;
							if($CritH[6])
								$essence -=$CritH[6];
							unset($CritH);
						}
						$img=Afficher_Image('images/hit'.$country.$Type_avion.'.jpg',"images/hit.jpg","Touché");
						if($Premium)
							$intro.='<br>La rafale frappe votre appareil de plein fouet, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
						else
							$intro.='<br>La rafale frappe votre appareil de plein fouet!';
						if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
						{
							if(GetData("Equipage","ID",$Equipage,"Moral") >0 and GetData("Equipage","ID",$Equipage,"Courage") >0)
							{
								$Equipage_Nom=GetData("Equipage","ID",$Equipage,"Nom");
								$Meca=floor(GetData("Equipage","ID",$Equipage,"Mecanique")/2);
								if($Simu)UpdateCarac($Equipage,"Mecanique",1,"Equipage");
								if($Meca >$Degats)$Meca=$Degats;
								$intro.='<p>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)</p>';
								$HP +=$Meca;
							}
						}
						if(!$end_mission)$mission3=true;
					}
					SetData("Pilote","S_HP", $HP,"ID",$PlayerID);
				}
				else
				{
					$img='<img src=\'images/shooted'.$country.'.jpg\' style=\'width:100%;\'>';
					$intro.="<p>Vous évitez la rafale de justesse!</p>";
					$mission3=true;
				}
				if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-2);
			}
		}
		//En cas d'équipage tué
		$Equipage=GetData("Pilote","ID",$PlayerID,"Equipage");
		if($end_shoot)
		{
			SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
			$_SESSION['evader']=true;
			$titre='Combat';
			$img=Afficher_Image('images/kill'.$country.'.jpg',"images/kill.jpg","Victoire!");
			$mes.='<form action=\'kill_confirm.php\' method=\'post\'>
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
			<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Avioneni\' value='.$avion_eni.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Dist_shoot\' value='.$Dist_shoot.'>
			<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
			<input type=\'hidden\' name=\'Unit_eni\' value='.$Unit_eni.'>
			<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
			<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,1).'
			'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion).'
			<table class=\'table\'><tr><td align=\'left\'>
						<Input type=\'Radio\' name=\'Action\' value=\'1\' checked>- Poursuivre votre proie pour confirmer votre victoire.<br>
						<Input type=\'Radio\' name=\'Action\' value=\'2\'>- Vous désintéresser de votre proie et continuer le combat.<br>
				</td></tr></table>
			<input type=\'submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}	
		if($mission3)
		{
			$choix3='';
			if($meteo <-9)
				$choix5="Tenter de vous échapper en vous cachant dans les nuages";
			else
			{
				if($Nuit)
					$choix5="Tenter de vous échapper en profitant de la nuit.";
				else
					$choix5="Tenter de vous échapper face au soleil.";
			}
			if($Avion_Bombe and $Avion_Bombe_Nbr >0)
				$Alleger="<Input type='Radio' name='Action' value='18'>- Vider la soute pour alléger l'avion.<br>";
			else
				$Alleger='';
			if($S_Baby >0 and $essence <$Autonomie-$S_Baby)
				$Larguer="<Input type='Radio' name='Action' value='19'>- Larguer le réservoir largable pour alléger l'avion.<br>";
			else
				$Larguer='';
			if(($HP <$HPmax/2) and $Slot3)
				$Parachute="<Input type='Radio' name='Action' value='10'>- Abandonner l'appareil et sauter en parachute.<br>";
			//Attaque par le ventre
			if($alt >1000 and $Tactique >50 and ($Type_avioneni ==2 or $Type_avioneni ==11))
				$Ventre="<Input type='Radio' name='Action' value='11'>- Tenter d'attaquer par le ventre en évitant le mitrailleur arrière.<br>";
			//Seuls les chasseurs et chasseurs lourds attaquent
			if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
			{
				$choix1="<Input type='Radio' name='Action' value='1'>- Chercher à vous placer dans ses 6 heures pour l'abattre.<br>";
				if($alt >1000)
					$choix7="<Input type='Radio' name='Action' title='".GetMes('Aide_Coiffer')."' value='7'>- Chercher à prendre de l'altitude pour ensuite piquer sur l'ennemi.<br>";
				else
					$choix7='';
				$choix8="<Input type='Radio' name='Action' title='".GetMes('Aide_Frontale')."' value='8'>- Tenter une attaque frontale.<br>";

				if(!$Radio_a)
					$Radio_a=GetData($Avion_db,"ID",$avion,"Radio");
				if($Mission_Type ==4 and $Tactique+($Radio_a*50) >100)
					$choix3="<Input type='Radio' name='Action' value='17'>- Attirer l'ennemi sur vous afin de protéger votre escorte<br>";
			}
			elseif($Equipage_Nbr >1 and $avion !=43 and $avion !=109)
			{
				$choix1="<Input type='Radio' name='Action' value='9'>- Maintenir votre cap, votre mitrailleur arrière se chargeant de l'adversaire.<br>";
				$choix2='';
				$choix7='';
				$choix8='';
			}
			else
			{
				$choix1='';
				$choix2='';
				$choix7='';
				$choix8='';
			}
			if(!$intro)$intro='.';
			SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
			$_SESSION['evader']=true;
			$titre='Combat';
			$mes.='<form action=\'mission3.php\' method=\'post\'>
				<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>	
				<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
				<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
				<input type=\'hidden\' name=\'Avioneni\' value='.$avion_eni.'>
				<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
				<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
				<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
				<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
				<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
				<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
				<input type=\'hidden\' name=\'Unit_eni\' value='.$Unit_eni.'>
				<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
				<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
				'.ShowGaz($avion,$c_gaz,$flaps,$alt,1).'
				'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion).'
				<table class=\'table\'><tr><td align=\'left\'>'.$choix1.$choix7.$choix8.$choix2.$choix3.'
							<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'4\' checked>- Tenter de fuir le combat en vous lançant dans un piqué.<br>
							<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
							<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Nuages').'\' value=\'5\'>- '.$choix5.'.<br>'.$Ventre.$Alleger.$Larguer.$Parachute.'
					</td></tr></table>
				<input type=\'submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}
		if($nav)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0,S_Essence='$essence' WHERE ID='$PlayerID'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : evade-reset2');
			mysqli_close($con);
			$_SESSION['evader']=true;
			$titre='Navigation';
			$mes.='<form action=\'nav.php\' method=\'post\'>
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
			<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt).'
			'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion).'
			<table class=\'table\'><tr><td align=\'left\'>
						<Input type=\'Radio\' name=\'Action\' value=\'0\' checked>- Continuer vers votre objectif.<br>
						<Input type=\'Radio\' name=\'Action\' value=\'1\'>- Faire demi-tour.<br>
				</td></tr></table>
			<input type=\'submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}
	}
	if($end_mission)
		include_once('./end_mission.php');
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
//Time
usleep(1);
/*if($PlayerID ==1 or $PlayerID ==2)
{
	$time=microtime();
	$time=explode(' ',$time);
	$time=$time[1]+$time[0];
	$finish=$time;
	$total_time=round(($finish-$start),4);
	$skills.=memory_get_usage()."/".memory_get_peak_usage()."<br>Page generated in ".$total_time." seconds.";
}*/
include_once('./index.php');