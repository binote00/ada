<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$Action=Insec($_POST['Action']);
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$meteo=Insec($_POST['Meteo']);
$avion=Insec($_POST['Avion']);
$avion_eni=Insec($_POST['Avioneni']);
$alt=Insec($_POST['Alt']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$HP_eni=Insec($_POST['HP_eni']);
$Puissance=Insec($_POST['Puissance']);
$Enis=Insec($_POST['Enis']);
$Pilote_eni=Insec($_POST['Pilote_eni']);
$Avion_db_eni=Insec($_POST['Avion_db_eni']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$Battle=Insec($_POST['Battle']);
$Faction=Insec($_POST['Camp']);
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php'); //OK
	include_once('./jfv_rencontre.inc.php');
	include_once('./jfv_inc_pvp.php');
	$_SESSION['finish']=false;
	$_SESSION['tirer']=false;
	$_SESSION['evader']=false;
	$_SESSION['missiondeux']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['kill_confirm']=false;
	$panne_seche=false;
	$evade=false;
	$shoot_tab=false;
	$mitrailleur=false;
	$end_shoot=false;
	$continue_eni=false;
	$Shoots=false;
	$zoom_tab=false;
	$Bonus_Pil=false;
	$wingman=false;
	$UpdateMoral=0;
	$UpdateCourage=0;
	$UpdateReput=0;
	$UpdateGrade=0;
	$UpdateEnis=0;
	$UpdateTactique=0;
	$UpdatePilotage=0;
	$Update_S_Escorte_nbr=0;
	$Update_S_Escorteb_nbr=0;
	$UpdateStress_Moteur=0;
	$UpdateStress_Commandes=0;
	$Update_Unit_Reput=0;					
	$PVP=$_SESSION['PVP'];
	$Saison=$_SESSION['Saison'];
	$Chk_M3=$_SESSION['missiontrois'];
	if($Chk_M3 >0 and $Chk_M3 !=99)
	{
		$intro="<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>";
		mail("binote@hotmail.com","Aube des Aigles: Init Mission F5 (mission3) : ".$Pilote_pvp ,"Joueur ".$Pilote_pvp." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Avancement,S_HP,Equipage,Pilotage,Acrobatie,Tactique,Vue,Reputation,Moral,Courage,Stress_Moteur,S_Avion_db,S_Avion_Bombe,S_Avion_Bombe_Nbr,Simu,S_Tourelle_Mun,S_Essence,S_Baby,
	S_Nuit,S_Cible,S_Mission,S_Formation,S_Escorte,S_Escorte_nom,S_Escorte_nbr,S_Engine_Nbr,S_Engine_Nbr_Eni,S_Longitude,S_Latitude,S_Escorteb,S_Escorteb_nbr,S_Equipage_Nbr,S_Leader,S_Blindage,
	Slot1,Slot4,Slot6,Slot7,Slot9,Slot10,Slot11,Sandbox FROM Pilote_PVP WHERE ID='$Pilote_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission3_pvp-player');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Avancement=$data['Avancement'];
			$HP=$data['S_HP'];
			$Pilotage=$data['Pilotage'];
			$Acrobatie=$data['Acrobatie'];
			$Tactique=$data['Tactique'];
			$Vue=$data['Vue'];
			$Moral=$data['Moral'];
			$Courage=$data['Courage'];
			$Reputation=$data['Reputation'];
			$Stress_Moteur=$data['Stress_Moteur'];
			$Avion_db="Avion"; //$data['S_Avion_db'];
			$Nuit=$data['S_Nuit'];
			$Cible=$data['S_Cible'];
			$Mission_Type=$data['S_Mission'];
			$Formation=$data['S_Formation'];
			$Escorte=$data['Escorte'];
			$Escorte_nbr=$data['S_Escorte_nbr'];
			$Escorte_nom=$data['S_Escorte_nom'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$Engine_Nbr_Eni=$data['S_Engine_Nbr_Eni'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Escorteb=$data['S_Escorteb'];
			$Escorteb_nbr=$data['S_Escorteb_nbr'];
			$Leader=$data['S_Leader'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Avion_Bombe_Nbr=$data['S_Avion_Bombe_Nbr'];
			$Tourelle_Mun=$data['S_Tourelle_Mun'];
			$S_Baby=$data['S_Baby'];
			$Blindage=$data['S_Blindage'];
			$Slot1=$data['Slot1'];
			$Slot4=$data['Slot4'];
			$Slot6=$data['Slot6'];
			$Slot7=$data['Slot7'];
			$Slot9=$data['Slot9'];
			$Slot10=$data['Slot10'];
			$Slot11=$data['Slot11'];
			$Simu=$data['Simu'];
			$Sandbox=$data['Sandbox'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($Acrobatie >150)$Acrobatie=150;
	if($Tactique >150)$Tactique=150;	
	if($HP <1)
		$end_mission=true;
	else
	{
		if($Equipage)$Endu_Eq=GetData("Equipage_PVP","ID",$Equipage,"Endurance");
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
			$result=mysqli_query($con,"SELECT HP,Target FROM Duels_Candidats_PVP WHERE PlayerID='$Pilote_pvp'");
			$PVP_Ok=mysqli_result(mysqli_query($con,"SELECT Lieu FROM Duels_Candidats_PVP WHERE PlayerID='$Pilote_eni'"),0);
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
				$nom_avioneni=GetData($Avion_db_eni,"ID",$avion_eni,"Nom");
				$intro.="<p>[PVP] Vous encaissez une rafale tirée par un <b>".$nom_avioneni."</b>! (<b>".$Deg."</b> dégâts)</p>";
			}
			if($PVP_Ok !=$Cible or !$PVP_Target)
			{
				$_SESSION['PVP']=false;
				$PVP=false;
				SetData("Duels_Candidats_PVP","Target",0,"PlayerID",$Pilote_eni);
				RetireCandidatPVP($Pilote_pvp,"nav");
				$Enis=0;
			}
		}		
		//Boost
		if($c_gaz ==130)$UpdateStress_Moteur+=10;
		if($essence <1)
			$panne_seche=true;
		elseif($PVP and $HP <1)
		{
			$intro.="<p>Une rafale transforme votre appareil en passoire, ne vous laissant pas d'autre choix que de sauter en parachute!</p>";
			$end_mission=true;
			$_SESSION['Parachute']=true;
		}
		elseif($Enis <1 or (!$PVP and $chemin <1 and !$Nuit))
		{
			$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps,true);
			$intro.="<p>Votre adversaire abandonne le combat!</p>";
			$img=Afficher_Image("images/epargner.jpg","images/epargner.jpg","Epargner");
			$chemin=0;
			$nav=true;
		}
		else
		{		
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Pays,Robustesse,Masse,Autonomie,Puissance,ArmePrincipale,ArmeSecondaire,Blindage,Verriere,Detection,Plafond,Type,Radio,Moteur,Baby,Engine,ManoeuvreB,ManoeuvreH,Maniabilite FROM Avion WHERE ID='$avion'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission3_pvp-avion');
			$result2=mysqli_query($con,"SELECT Nom,Pays,Type,Engine,Robustesse,ArmePrincipale,ArmeSecondaire,ArmeArriere,TourelleSup,Arme3_Nbr,Arme4_Nbr,Arme5_Nbr,Blindage,Verriere,Detection,Plafond,VitesseA,ManoeuvreB,ManoeuvreH,Maniabilite FROM Avion WHERE ID='$avion_eni'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission3_pvp-avioneni');
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$country=$data['Pays'];
					$HPmax=$data['Robustesse'];
					$Puissance_nominale=$data['Puissance'];
					$Arme1Avion=$data['ArmePrincipale'];
					$Arme2Avion=$data['ArmeSecondaire'];
					$Blindage=$data['Blindage'];
					$Verriere=$data['Verriere'];
					$DetAvion=$data['Detection'];
					$Plafond=$data['Plafond'];
					$Type_avion=$data['Type'];
					$Masse=$data['Masse'];
					$Autonomie=$data['Autonomie'];
					$Radio_a=$data['Radio'];
					$Filtre=$data['Moteur'];
					$Baby=$data['Baby'];
					$Engine=$data['Engine'];
					$ManB=$data['ManoeuvreB'];
					$ManH=$data['ManoeuvreH'];
					$Mani=$data['Maniabilite'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			//GetData Avion_eni
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$nom_avioneni=$data['Nom'];
					$Pays_eni=$data['Pays'];
					$Type_avioneni=$data['Type'];
					$Engine_eni=$data['Engine'];
					$HPmax_eni=$data['Robustesse'];
					$Arme1Avion_eni=$data['ArmePrincipale'];
					$Arme2Avion_eni=$data['ArmeSecondaire'];
					$Arme3Avion_eni=$data['ArmeArriere'];
					$Arme5Avion_eni=$data['TourelleSup'];
					$ArmeTourelle=$data['Arme3_Nbr'];
					$ArmeSabord=$data['Arme4_Nbr'];
					$ArmeTourelle2=$data['Arme5_Nbr'];
					$Blindage_eni=$data['Blindage'];
					$Verriere_eni=$data['Verriere'];
					$DetAvion_eni=$data['Detection'];
					$Plafond_eni=$data['Plafond'];
					$VitAAvioneni=$data['VitesseA'];
					$ManBeni=$data['ManoeuvreB'];
					$ManHeni=$data['ManoeuvreH'];
					$Manieni=$data['Maniabilite'];
				}
				mysqli_free_result($result2);
				unset($data);
			}
			if($PVP)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Pilotage,Acrobatie,Tactique FROM Pilote_PVP WHERE ID='$Pilote_eni'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission3_pvp-piloteni');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Pilotage_eni=$data['Pilotage'];
						$Acrobatie_eni=$data['Acrobatie'];
						$Tactique_eni=$data['Tactique'];
					}
					mysqli_free_result($result);
				}
			}
			else
			{
				$Pilotage_eni=mt_rand(25,100);
				$Acrobatie_eni=mt_rand(25,100);
				$Tactique_eni=mt_rand(25,100);
			}
			$Arme1=GetData("Armes","ID",$Arme1Avion,"Nom");
			$Arme2=GetData("Armes","ID",$Arme2Avion,"Nom");				
			$malus_incident=1;
			$malus_incident_eni=1;
			$Fiabilite=GetData("Moteur","ID",$Engine,"Fiabilite")-$Stress_Moteur;
			if(mt_rand(0,100) >$Fiabilite)
			{
				$break=GetIncident($Pilote_pvp,2,$Saison,0,"Avion",$avion,$c_gaz,true);
				if($break[1] <-9)
					$intro.='Vous constatez <b>'.$break[0].'</b> réduisant temporairement les performances de votre machine.';
				$malus_incident=(100+$break[1])/100;
				unset($break);
			}
			if(mt_rand(0,100) >95 and !$Grosbill)
			{
				//Incident_eni
				$break_eni=GetIncident(0,2,$Saison,0,"Avion",$avion_eni);
				if($break_eni[1] <-9)
				{
					$intro.="<br>Votre adversaire semble connaître quelques soucis mécaniques.";
					$malus_incident_eni=(100+$break_eni[1])/100;
				}
				else
					$malus_incident_eni=1;
				unset($break_eni);
			}					
			//Malus avion touché
			$moda=$HPmax/$HP;
			if($Avion_Bombe_Nbr and $Avion_Bombe !=30)
			{
				$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_Nbr));
				$moda*=(1+$charge_sup);
			}
			if(!$moda)
				$Plafond=1000;
			else
				$Plafond=round($Plafond/$moda);
			if($alt <100)$alt=100;
			if($alt >$Plafond)$alt=$Plafond;
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
			$PuissAvion=GetPuissance($Avion_db,$avion,$alt,$HP,$moda,$malus_incident,$Engine_Nbr,$c_gaz);
			$ManiAvion=GetMani($Mani,$HPmax,$HP,$moda,$malus_incident,$flaps);
			$ManAvion=GetMano($ManH,$ManB,$HPmax,$HP,$alt,$moda,$malus_incident,$flaps);
			$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
			$VitAAvion= GetSpeedA($Avion_db,$avion,$alt,$meteo,$Engine_Nbr,$moda,$malus_incident,$c_gaz,$flaps);
			$SpeedP=GetSpeedP($Avion_db,$avion,$Engine_Nbr,$c_gaz,$flaps);
			$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,$alt);			
			//Malus avion_eni touché
			///***Important ne pas effacer !!!***///
			if(!$HP_eni)$HP_eni=$HPmax_eni;
			///***!!!***///
			$moda_eni=$HPmax_eni/$HP_eni;
			if($HP_eni <$HPmax_eni)$VitAAvioneni=$VitAAvioneni/$moda_eni*$malus_incident_eni;
			$ManiAvion_eni=GetMani($Manieni,$HPmax_eni,$HP_eni,$moda_eni,$malus_incident_eni);
			if($Engine_Nbr_Eni <1)$Engine_Nbr_Eni=GetData($Avion_db_eni,"ID",$avion_eni,"Engine_Nbr");
			$PuissAvioneni=GetPuissance($Avion_db_eni,$avion_eni,$alt,$HP_eni,$moda_eni,$malus_incident_eni,$Engine_Nbr_Eni);
			$ManAvion_eni=GetMano($ManHeni,$ManBeni,$HPmax_eni,$HP_eni,$alt,$moda_eni,$malus_incident_eni);
			$VitAvioneni=GetSpeed($Avion_db_eni,$avion_eni,$alt,$meteo,$moda_eni,$malus_incident_eni);
			$SpeedPeni=GetSpeedP($Avion_db_eni,$avion_eni,$Engine_Nbr_Eni,$c_gaz,$flaps);
			$Vis_eni=GetVis($Avion_db_eni,$avion_eni,$Cible,$meteo,$alt,$alt); 			
			//Si avion trop abimé,il s'écrase au sol
			if(($VitAvion <50) or ($PuissAvion <1) or ($c_gaz <20))
				$Action=98;
			else
			{
				//Si avion_eni trop abimé,il s'écrase au sol
				if(!$PVP and (($VitAvioneni <10) or ($ManAvion_eni <1)))
				{
					if(mt_rand(0,$Pilotage_eni) <50)
						$Action=99;
				}
				elseif($Action ==13 or $Action ==14)
				{
					if(mt_rand(0,$Tactique) >mt_rand(0,$Tactique_eni) and !$Chk_M3)
					{
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pilote_PVP SET S_Escorte_nom='',S_Escorte_nbr=0,S_Escorte=0 WHERE ID='$Pilote_pvp'")
						 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission3_pvp-reset');
						mysqli_close($con);
						$intro.="<p>Vos ordres sont exécutés, votre escorte rompt la formation et se dirige vers l'ennemi désigné.</p>";
						if($Action ==13)
						{
							$_SESSION['Escorte_eni_Nbr']=mt_rand(2,8);
							$_SESSION['Escorte_eni_avion_eni']=$avion_eni;
							$Action=3;
						}
						elseif($Action ==14)
						{
							$Enis=$_SESSION['Escorte_eni_Nbr'];
							$avion_eni=$_SESSION['Escorte_eni_avion_eni'];
							$Type_avioneni=1;
							SetData("Pilote_PVP","S_Intercept_nbr",$Enis,"ID",$Pilote_pvp);
							$Action=3;
						}
					}
					else
					{
						$intro.="<br>Votre tactique ne fonctionne pas !";
						$Action=3;
					}
				}
				/*Ailier
				if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
				{
					$Ailier=3297;
					if($Formation >0 and $Enis >1)
						$Melee=true;
				}
				if(!$PVP and $wingman)
				{
					$choix3="<Input type='Radio' name='Action' title='".GetMes('Aide_Prot_leader')."' value='3'>- Tenir le coup afin de protéger votre leader<br>";
					$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='6'>- Appeler votre leader à l'aide<br>";
				}
				elseif(!$PVP and $Ailier)
				{
					$choix3="<Input type='Radio' name='Action' title='".GetMes('Aide_Prot_leader')."' value='3'>- Couvrir votre ailier<br>";
					$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='6'>- Demander à votre ailier d'engager le combat<br>";
				}
				elseif(!$PVP and $Melee)
				{
					$choix3="";
					$choix6="<Input type='Radio' name='Action' value='21'>- Demander de l'aide à votre formation<br>";
				}
				else
				{*/
					$choix3="";
					$choix6="";
				//}
				//Surprise IA
				if(($Action ==1 or $Action ==2 or $Action ==7) and $Type_avion ==1 and $Type_avioneni ==1 and $Mission_Type !=5)
				{
					if($Ailier)$Ailier_bonus=5;
					if($Enis>(4+($Tactique/10)+$Ailier_bonus+$Formation))
						$Action=16;
				}
				elseif($Type_avioneni ==1 and $Tactique_eni >150 and $Enis >1)
				{
					$luck_surprise=mt_rand(0,3);
					if($luck_surprise >0 or $Chk_M3)
					{
						$Surprise=$DetAvion+mt_rand(0,$Vue)+mt_rand(0,$Tactique)-$Tactique_eni;
						if($Surprise <0 or $Chk_M3)$Action=15;
					}
				}
			}			
			if(!$PVP and !$Sandbox and $Mission_Type !=103)
			{
				//Mission Escorte : check si bombardiers alliés descendus
				if($Mission_Type ==4)
				{
					if($Enis >1 and $Escorteb_nbr >0 and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12))
					{
						$Escort_Time=mt_rand(0,10)+$Radio_a;
						if($Escort_Time <3 or $Action ==4 or $Action ==12)
						{
							$intro.="<p>L'ennemi vient de descendre un <b>".GetData("Avion","ID",$Escorteb,"Nom")."</b> que vous escortiez !</p>";
							$UpdateMoral-=1;
							$UpdateReput-=1;
							$UpdateGrade-=1;
							$Update_S_Escorteb_nbr-=1;
							$Escorteb_nbr-=1;
							if($Escorteb_nbr <1 and $_SESSION['done'] ==false)
							{
								$intro.="<p><b>Toute la formation que vous escortiez a été abattue! Vous échouez dans votre mission !</b><p>";
								$chemin=$Distance;
								$Mission_Type=3;
								$_SESSION['done']=true;
								SetData("Pilote_PVP","S_Mission",3,"ID",$Pilote_pvp); //Passer en mode chasse pour éviter le menu d'escorte
							}
						}
						//Mission Escorte : check si patrouille eni descendu par avions escortés
						if($Action ==3 or $Action ==17)
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT Nom,Arme1_Nbr,Arme4_Nbr,Arme5_Nbr,Arme6_Nbr FROM Avion WHERE ID='$Escorteb'"); //Avion_escorté
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
									$nom_esc=$data['Nom'];
									$Arme1_Nbr=$data['Arme1_Nbr'];
									$Arme4_Nbr=$data['Arme4_Nbr'];
									$Arme5_Nbr=$data['Arme5_Nbr'];
									$Arme6_Nbr=$data['Arme6_Nbr'];
								}
								mysqli_free_result($result);
								unset($data);
							}
							$Tourelle_esc=($Arme1_Nbr + $Arme4_Nbr + $Arme5_Nbr + $Arme6_Nbr)*$Escorteb_nbr;
							if($Tourelle_esc)
							{
								$Tir_esc=mt_rand(10,250)+$Tourelle_esc+($Radio_a*10);
								$Esq_eni=(mt_rand(10,$Pilotage_eni) + $ManAvion_eni + $ManiAvion_eni - ($PuissAvioneni/3) + ($VitAvioneni/2) + mt_rand(10,$Tactique_eni))/2;
								if($Tir_esc >$Esq_eni or $Tir_esc >250)
								{
									$intro.='<p>La formation de '.$nom_esc.' que vous escortez vient d\'abattre un <b>'.$nom_avioneni.'</b> ennemi !</p>';
									$UpdateMoral+=1;
									$UpdateEnis-=1;
									$Enis-=1;
								}
								else
								{
									$intro.='<p>La formation de '.$nom_esc.' que vous escortez tire mais sans succès !</p>';
								}
								if($Admin)
									$skills.='<br>Tir esc='.$Tir_esc.' / Esquive eni='.$Esq_eni.'<br>';
							}
						}
					}
				}	
				//Mission Bombardement : check si ennemis descendus par votre escorte
				elseif($Mission_Type ==2 or $Mission_Type ==8 or $Mission_Type ==12 or $Mission_Type ==13)
				{
					//Mission Bombardement : check si escorte descendus par ennemis
					if($Enis >1 and $Escorte_nbr >0)
					{
						$Radio=1;
						if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
							$Radio=floor(GetData("Equipage_PVP","ID",$Equipage,"Radio")/20)+$Radio_a;
						$Esc_check=9+$Radio;
						$Escort_Time=mt_rand(0,$Esc_check);
						if($Enis >1 and $Escort_Time <2 and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12))
						{
							$intro.='<p>L\'ennemi vient d\'abattre un chasseur de votre escorte !</p>';
							$Update_S_Escorte_nbr-=1;
							$Escorte_nbr-=1;
						}
						//Mission Bombardement : check si ennemis descendus par votre escorte
						$Escort_Time=mt_rand(0,20);
						if($Enis >1 and $Escort_Time <$Radio and $Escorte_nbr >0 and ($Type_avioneni ==1 or $Type_avioneni == 4 or $Type_avioneni ==12) and !$Chk_M3)
						{
							$intro.='<p>Votre escorte vient d\'abattre un <b>'.$nom_avioneni.'</b> ennemi !</p>';
							$UpdateEnis-=1;
							$Enis-=1;
						}
						unset($Escort_Time);
						unset($Esc_check);
					}
				}
				elseif($Mission_Type ==7)
				{
					//check si escorte descendus par ennemis
					$Escort_Time=mt_rand(0,5);
					if($Enis >1 and $Escort_Time >3 and $Escorte_nbr >0 and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12))
					{
						$alt_min=$alt-3000-$meteo;
						$alt_max=$alt+2000+$meteo;
						$Radio=1;
						if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
							$Radio=floor(GetData("Equipage_PVP","ID",$Equipage,"Radio")/20)+$Radio_a;
						$Esc_check=9+$Radio;
						$Escort_Time=mt_rand(0,$Esc_check);
						if($Enis >1 and $Escort_Time <2 and ($Type_avioneni ==1 or $Type_avioneni == 4 or $Type_avioneni ==12))
						{
							$intro.='<p>L\'ennemi vient d\'abattre un <b>'.$Escorte_nom.'</b> de votre escorte !</p>';
							$Update_S_Escorte_nbr-=1;
							$Escorte_nbr-=1;
						}
					}
					//Mission Bombardement : check si ennemis descendus par votre escorte
					$Escort_Time=mt_rand(0,20);
					if($Enis >1 and $Escort_Time < $Radio and $Escorte_nbr >0 and ($Type_avioneni ==1 or $Type_avioneni == 4 or $Type_avioneni ==12) and !$Chk_M3)
					{
						$intro.='<p>Votre escorte vient d\'abattre un <b>'.$nom_avioneni.'</b> ennemi !</p>';
						$UpdateEnis-=1;
						$Enis-=1;
					}
					unset($Escort_Time);
					unset($Esc_check);
				}
			}			
			$Degats=0;
			$Conso=($Puissance_nominale*$c_gaz/100)/500;
			switch($Action)
			{
				case 1:
					//Chercher à vous placer dans ses 6 heures pour l'abattre, grâce à votre Pilotage.
					$essence-=(5+$Conso);
					if($c_gaz >90)$UpdateStress_Moteur+=1;
					$Pilot=mt_rand(0,$Pilotage*2) + $meteo + ($ManAvion*2) - ($PuissAvion/3) + ($Moral/10) + mt_rand(0,$Tactique*2) + ($Verriere*10) + $DetAvion;
					$Pilot_eni=mt_rand(10,$Pilotage_eni*2) + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + mt_rand(10,$Tactique_eni*2) + ($Verriere_eni*10)+ $DetAvion_eni + ($Enis*20);
					//JF
					if($Pilote_pvp ==1)
					{
						$ManAvion_txt=$ManAvion*2;
						$ManAvion_eni_txt=$ManAvion_eni*2;
						$PuissAvion_txt=$PuissAvion/3;
						$PuissAvioneni_txt=$PuissAvioneni/3;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><td colspan='3'>Bonus : Moral</td></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
							<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					if($Front ==2 and $Type_avion ==1 and $Mission_Type ==26 and $Filtre !=7)
					{
						if($Type_avioneni ==1)$Pilot=0;
						$UpdateStress_Moteur+=5;
						$UpdateStress_Commandes+=5;
					}
					//End JF
					$Arme3Avion_nbr_eni_base=$ArmeTourelle+$ArmeTourelle2;
					if($ArmeTourelle >0)
						$ArmeArriere_eni=$Arme3Avion_eni;
					else
						$ArmeArriere_eni=$Arme5Avion_eni;
					if($Pilot >=$Pilot_eni and !$Chk_M3)
					{
						if(($VitAvion/2) >$VitAvioneni)
						{
							$intro.="<br>Vous dépassez votre adversaire, emporté par votre vitesse !";
							$evade=true;
						}
						elseif($Type_avioneni ==2 or $Type_avioneni ==3 or $Type_avioneni ==11)
							$mitrailleur=true;
						elseif($ArmeArriere_eni and $Pilot_eni+50 >$Pilot)
							$mitrailleur=true;
						else
						{
							$Dist_shoot=SetDist_shoot($Pilot,$Pilot_eni);
							$Angle_shoot=SetAngle_shoot($Pilot,$Pilot_eni,0,60);
							$intro.='<p>Vous prenez l\'avantage sur votre adversaire et vous placez en position de tir à une distance d\'environ '.$Dist_shoot.' m, sous un angle de '.$Angle_shoot.'°</p>';
							$shoot_tab=true;
						}
					}
					else
					{
						if($ArmeArriere_eni)
							$mitrailleur=true;
						elseif($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							$intro.="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
							$img="<img src='images/visee.jpg' style='width:100%;'>";
							if(!$PVP and $Pilot_eni >$Pilot+50)
								$Shoots=true;
							else
								$evade=true;
						}
						else
						{
							$intro.="<p>Votre adversaire prend l'avantage sur vous et en profite pour rompre le combat et s'enfuir</p>";
							$img="<img src='images/toofast.jpg' style='width:100%;'>";
							$Enis-=1;
							if($Enis >0)
								$continue_eni=true;
							else
								$nav=true;
						}
					}
				break;
				case 2:
					//Effectuer une manuvre acrobatique pour tenter d'abattre votre adversaire, à l'aide de votre talent d'Acrobatie.
					$essence-=(10+$Conso);
					if($c_gaz >90)
					{
						$UpdateStress_Moteur+=1;
						$UpdateStress_Commandes+=1;
					}
					if($flaps)
						$UpdateStress_Commandes+=1;
					//As ou expert tactique/acrobatie met ses volets
					if($Tactique_eni >100 or $Pilotage_eni >100)
						$ManAvion_eni=GetMan($Avion_db_eni,$avion_eni,$alt,$HP_eni,$moda_eni,$malus_incident_eni,3);
					elseif($Tactique_eni >75 or $Pilotage_eni >75)
						$ManAvion_eni=GetMan($Avion_db_eni,$avion_eni,$alt,$HP_eni,$moda_eni,$malus_incident_eni,2);
					$Pilot=mt_rand(0,$Acrobatie*2) + $meteo + ($ManAvion*7) - $PuissAvion + ($ManiAvion) + ($Moral/10) + mt_rand(0,$Tactique*2) + ($Verriere*10) + $DetAvion;
					$Pilot_eni=mt_rand(10,$Pilotage_eni*2) + $meteo + ($ManAvion_eni*7) - $PuissAvioneni + ($ManiAvion_eni) + mt_rand(10,$Tactique_eni*2) + ($Verriere_eni*10) + $DetAvion_eni + ($Enis*20);
					//JF
					if($Pilote_pvp ==1)
					{
						$ManAvion_txt=$ManAvion*7;
						$ManAvion_eni_txt=$ManAvion_eni*7;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><th colspan='3'>Flanc / Avantage</th></tr>
							<tr><td colspan='3'>Bonus : Moral</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
							<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
							<tr><td>Verrière</td><td>".$Verriere."</td><td>".$Verriere_eni."</td></tr>
							<tr><td>Détection</td><td>".$DetAvion."</td><td>".$DetAvion_eni."</td></tr>
							<tr><td>Acrobatie</td><td>".$Acrobatie."</td><td>".$Acrobatie_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion."</td><td>-".$PuissAvioneni."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					if($Front ==2 and $Type_avion ==1 and $Mission_Type ==26 and $Filtre !=7)
					{
						if($Type_avioneni ==1)$Pilot=0;
						$UpdateStress_Moteur+=5;
						$UpdateStress_Commandes+=5;
					}
					//End JF
					if($Pilot >=$Pilot_eni and $Type_avioneni !=2 and $Type_avioneni !=11 and !$Chk_M3)
					{
						$Dist_shoot=SetDist_shoot($Pilot,$Pilot_eni);
						$Angle_shoot=SetAngle_shoot($Pilot,$Pilot_eni,45,90);
						$intro.='<p>Vous prenez l\'avantage sur votre adversaire et vous placez en position de tir à une distance d\'environ '.$Dist_shoot.' m, sous un angle de '.$Angle_shoot.'°</p>';
						$shoot_tab=true;
					}
					else
					{
						if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							$img="<img src='images/visee.jpg' style='width:100%;'>";
							$UpdateMoral-=1;
							if(!$PVP and $Pilot_eni >$Pilot+50)
							{
								$intro.="<p>Votre adversaire joue littéralement avec vous et n'a aucun mal à se placer en position de tir</p>";
								$Shoots=true;
							}
							else
							{
								$intro.="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
								$evade=true;
							}
						}
						else
						{
							$Arme3Avion_nbr_eni_base=$ArmeSabord+$ArmeTourelle2;
							if($ArmeSabord >0)
								$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"ArmeSabord");
							else
								$ArmeArriere_eni=$Arme5Avion_eni;
							$mitrailleur=true;
						}
					}
				break;
				case 3:
					//Protéger votre leader / Attirer l'ennemi sur vous
					$chemin-=5;
					$essence-=(5+$Conso);
					if($Enis ==1)
						$combat_ailier=true;
					else
					{
						$combat_ailier=false;
						if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
							$evade=true;
						else
						{
							//Bombardier eni tente de fuir
							$Arme3Avion_nbr_eni_base=$ArmeTourelle+$ArmeTourelle2;
							if($ArmeTourelle >0)
								$ArmeArriere_eni=$Arme3Avion_eni;
							else
								$ArmeArriere_eni=$Arme5Avion_eni;
							$mitrailleur=true;
						}
					}
					if($combat_ailier)
					{
						$avion_lead=$avion;
						$wing_txt="Votre leader";
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
						$Pilot_lead=mt_rand(10,150)+$meteo+ $ManAvion_lead + $ManiAvion_lead - ($PuissAvion_lead/2) + $VitAvion_lead + mt_rand(10,150)+($Radio_a*10);
						$Pilot_eni=mt_rand(10,$Pilotage_eni)+$meteo+ $ManAvion_eni + $ManiAvion_eni - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(10,$Tactique_eni)+($Enis*10);
						if($Pilot_lead >$Pilot_eni and !$Chk_M3)
						{
							$intro.='<p>'.$wing_txt.' vous sort de ce mauvais pas en mettant en fuite l\'ennemi qui vous prenait pour cible!</p>';
							$img=Afficher_Image('images/kill'.$country.'.jpg', "images/kill.jpg","Victoire!");	
							$Enis-=1;
							$UpdateEnis-=1;
							if($Enis >0)
								$continue_eni=true;
							else
							{
								$UpdateMoral+=1;
								if($HP_eni <$HPmax_eni)
								{
									if($avion and $avion_eni and $Battle and $Cible and $Pilote_eni)
										AddVictoirePVP("Avion",$avion_eni,$avion,$Pilote_pvp,$Battle,$Mission,$Cible,$Arme1Avion,$Pilote_eni);
									$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
								}
								$nav=true;
							}
						}
						elseif($Pilot_eni >$Pilot_lead+50)
						{
							$intro.='<p>'.$wing_txt.' est abattu en flammes par un '.$nom_avioneni.'.</p>';
							$img=Afficher_Image('images/hit'.$country.$Type_avion.'.jpg', "images/hit.jpg","Touché");
							$Leader=false;
							$continue_eni=true;
							if($Sandbox)
								SetData("Pilote_PVP","S_Ailier",0,"ID",$Pilote_pvp);
							else
								SetData("Pilote_PVP","Ailier",0,"ID",$Pilote_pvp);
						}
						else
						{
							$intro.='<p>'.$wing_txt.' ne parvient pas à éliminer votre adversaire.</p>';
							$img.='<img src=\'images/miss_leader'.$country.'.jpg\' style=\'width:100%;\'>';	
							$continue_eni=true;
						}
					}
				break;
				case 4:
					//Tenter de fuir le combat en bénéficiant de votre vitesse (piqué).
					$essence-=(10+$Conso);
					$chemin-=1;
					$pique_ok=false;
					$Speeddb=$SpeedP*2;
					$alt=SetAlt($alt,$Plafond,$Plafond_eni,-$Speeddb,-$SpeedP,$c_gaz);
					if($alt <100)
					{
						if($Pilotage >(200-$alt))
						{
							$intro.="<br>Effectuant votre ressource au ras du sol, vous échappez de peu au crash!";
							$pique_ok=true;
							$alt=100;
						}
						else
						{
							$intro.="<br>Ne parvenant pas à redresser à temps, votre avion percute le sol!";
							$end_mission=true;
							$HP=0;
							$alt=0;
						}
					}
					else
						$pique_ok=true;
					if($pique_ok)
					{
						$Injection=GetData("Moteur","ID",$Engine,"Injection");
						$Injection_eni=GetData("Moteur","ID",$Engine_eni,"Injection");
						$Pilot=(mt_rand(0,$Pilotage)/2) - $meteo - $PuissAvion + $Speeddb + ($Moral/10) + ($Injection*100)+($Noob*50);
						$Pilot_eni=(mt_rand(0,$Pilotage_eni)/2) + $meteo - $PuissAvioneni + ($SpeedPeni*2) + ($Injection_eni*100) + $DetAvion_eni + ($Enis*10);
						//JF
						if($Admin)
						{
							$PuissAvion_txt=$SpeedP*2;
							$PuissAvioneni_txt=$SpeedPeni*2;
							$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
								<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
								<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
								<tr><th colspan='3'>Fuite (piqué)</th></tr>
								<tr><td colspan='3'>Bonus : Moral</td></tr>
								<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
								<tr><td>Vitesse P</td><td>".$PuissAvion_txt."</td><td>".$PuissAvioneni_txt."</td></tr>
								<tr><td>Injection</td><td>".$Injection."</td><td>".$Injection_eni."</td></tr>
								<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
								<tr><td>Puissance</td><td>-".$PuissAvion."</td><td>-".$PuissAvioneni."</td></tr>
								<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
							</table>";
						}
						//End JF
						unset($Injection);
						unset($Injection_eni);
						if($Pilot >=$Pilot_eni and !$Chk_M3)
						{
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							$intro.='<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
							$nav=true;
						}
						elseif($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							$img="<img src='images/visee.jpg' style='width:100%;'>";
							if(!$PVP and $Pilot_eni >$Pilot+50)
							{
								$intro.="<p>Votre adversaire joue littéralement avec vous et n'a aucun mal à se placer en position de tir</p>";
								$Shoots=true;
							}
							else
							{
								$intro.="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
								$evade=true;
							}
						}
						else
						{
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							$intro.='<br>Vous laissez filer votre proie...<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
							$nav=true;
						}
					}
				break;
				case 5:
					//Tenter de vous échapper en vous cachant dans les nuages / ou face au soleil.
					$essence-=(5+$Conso);
					$chemin-=1;
					if($Nuit)
						$Radar_eni=GetData("Avion","ID",$avion_eni,"Radar")*50;
					else
						$Radar_eni=GetData("Avion","ID",$avion_eni,"Radar");
					$Pilot=mt_rand(0,$Pilotage) - $meteo + $ManAvion + $ManiAvion - ($PuissAvion/3) + $VitAvion + ($Moral/10) + ($Courage/10) + mt_rand(0,$Tactique*2)+($Noob*50);
					$Pilot_eni=mt_rand(0,$Pilotage_eni) + $meteo + $ManAvion_eni - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(0,$Tactique_eni*2) + $Radar_eni + ($Enis*20);
					//JF
					if($Admin ==1)
					{
						$PuissAvion_txt=$PuissAvion/3;
						$PuissAvioneni_txt=$PuissAvioneni/3;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><th colspan='3'>Fuite dans les nuages</th></tr>
							<tr><td colspan='3'>Bonus : Moral + Courage</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
							<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
							<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
							<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
						$Pilot+=5000;
					}
					//End JF
					if($Nuit and $Type_avioneni !=4 and !$Radar_eni)$Pilot_eni=1;
					if(!$Chk_M3 and $Pilot >=$Pilot_eni)
					{
						$intro.='<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
						$img.='<img src=\'images/avions/vol'.$avion_img.'.jpg\' style=\'width:100%;\'>';
						$nav=true;
					}
					elseif($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
					{
						$img="<img src='images/visee.jpg' style='width:100%;'>";
						if($Enis >2 and $Enis >($Formation+$Escorte_nbr+1))
						{
							$intro.="<p>Vos adversaires, trop nombreux, vous empêchent de fuir!<br>L'un d'eux se place en position de tir!</p>";
							$Shoots=true;
						}
						else
						{
							if(!$PVP and $Pilot_eni >$Pilot+50)
							{
								$intro.="<p>Votre adversaire joue littéralement avec vous et n'a aucun mal à se placer en position de tir</p>";
								$Shoots=true;
							}
							else
							{
								$intro.="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
								$evade=true;
							}
						}
						if($Enis <3)
							$UpdateMoral-=2;
					}
					else
					{
						if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
							$UpdateTactique-=1;
						$intro.='<br>Vous laissez filer votre proie...<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
						$img.='<img src=\'images/avions/vol'.$avion_img.'.jpg\' style=\'width:100%;\'>';
						$nav=true;
					}
				break;
				case 6:
					//Tenter une manuvre d'évasion et fuir le combat (manoeuvre).
					$essence-=(5+$Conso);
					$chemin-=1;
					if($Type_avion ==1 and ($Mun1 >0 or $Mun2 >0))
					{
						$UpdateCourage-=5;
						$UpdateReput-=2;
						$UpdateGrade-=2;
					}
					$Pilot=mt_rand(0,$Pilotage*3) + $meteo + ($ManAvion*2) + $ManiAvion - ($PuissAvion/3) + $VitAvion + ($Moral/10) + mt_rand(0,$Tactique*2)+($Noob*50);
					$Pilot_eni=mt_rand(0,$Pilotage_eni*3) + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(0,$Tactique_eni*2) + ($Enis*20);
					//JF
					if($Pilote_pvp ==1)
					{
						$PuissAvion_txt=$PuissAvion/3;
						$PuissAvioneni_txt=$PuissAvioneni/3;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><td colspan='3'>Bonus : Moral + Roulis</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
							<tr><td>Roulis</td><td>".$ManiAvion."</td><td>-Bonus-</td></tr>
							<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
							<tr><td>Pilotage (x2)</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					//End JF
					if($Pilot >=$Pilot_eni and !$Chk_M3)
					{
						$intro.='<p>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.</p>';
						$img.='<img src=\'images/avions/vol'.$avion_img.'.jpg\' style=\'width:100%;\'>';
						$nav=true;
					}
					elseif($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
					{
						$img="<img src='images/visee.jpg' style='width:100%;'>";
						if(!$PVP and $Pilot_eni >$Pilot+50)
						{
							$intro.="<p>Votre adversaire joue littéralement avec vous et n'a aucun mal à se placer en position de tir</p>";
							$Shoots=true;
						}
						else
						{
							$intro.="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
							$evade=true;
						}
					}
					else
					{
						if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)$UpdateTactique-=1;
						$intro.='<br>Vous laissez filer votre proie...<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						$nav=true;
					}	
				break;
				case 7:
					//Chercher à coiffer votre adversaire pour bénéficier de l'avantage de l'altitude.
					$essence-=(10+$Conso);
					$alt=SetAlt($alt,$Plafond,$Plafond_eni,100,1000,$c_gaz);
					if($c_gaz >90)$UpdateStress_Moteur+=1;
					$Tactique_eni=mt_rand(25,100);
					$Tac_bz=mt_rand(0,$Tactique*2)+($Verriere*10)+$DetAvion;
					$Tac_bz_eni=mt_rand(10,$Tactique_eni*2)+($Verriere_eni*10)+$DetAvion_eni;
					if($Tac_bz >$Tac_bz_eni+50 and !$Chk_M3)
					{
						$SpeedPeni=$VitAvioneni*2;
						$SpeedP=$SpeedP+$VitAAvion;
						$intro.="<br>Votre tactique supérieure surprend totalement votre adversaire !";
					}
					else
					{
						$SpeedPeni=$SpeedPeni*2;
						$SpeedP+=($VitAAvion/2);
						$intro.="<br>Votre adversaire ne se laisse pas abuser par votre tactique !";
					}
					$Pilot=mt_rand(0,$Pilotage) + $meteo - $PuissAvion + $SpeedP + mt_rand(0,$Tactique*2) + ($Moral/10);
					$Pilot_eni=mt_rand(10,$Pilotage_eni) + $meteo - $PuissAvioneni + $SpeedPeni + mt_rand(10,$Tactique_eni*2) + ($Enis*20);
					//JF
					if($Pilote_pvp ==1)
					{
						$PuissAvion_txt=$PuissAvion;
						$PuissAvioneni_txt=$PuissAvioneni;
						$VitAAvion_txt=$SpeedP;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><th colspan='3'>Coiffer</th></tr>
							<tr><td colspan='3'>Bonus : Moral</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Vitesse</td><td>".$VitAAvion_txt."</td><td>".$SpeedPeni."</td></tr>
							<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni." (max)</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					//End JF
					if($Pilot >=$Pilot_eni and !$Chk_M3)
					{
						$Arme3Avion_nbr_eni_base=$ArmeTourelle+$ArmeTourelle2;
						if($ArmeTourelle >0)
							$ArmeArriere_eni=$Arme3Avion_eni;
						else
							$ArmeArriere_eni=$Arme5Avion_eni;
						//Si l'attaquant ne réalise pas un résultat supérieur à 50 au Bombardier défenseur, le mitrailleur a droit à un tir d'opportunité
						if($ArmeArriere_eni)
						{
							if($Pilot <$Pilot_eni+50)
								$Tir_eni=mt_rand(50,150);
							elseif($Pilot <$Pilot_eni)
								$Tir_eni=mt_rand(25,100);
							else
								$Tir_eni=mt_rand(25,50);							
							$Shoot=mt_rand(0,$Tir_eni) + ($meteo/2) + $VisAvion - ($ManAvion/10) - ($Pilotage/10);
							$intro.='<p>Malgré votre manoeuvre supérieure, le mitrailleur du <b>'.$nom_avioneni.'</b> a le temps de vous envoyer une rafale !</p>';
							if($Shoot >0 or $Tir_eni >150)
							{
								$Arme3Avion_Dg_eni=GetData("Armes","ID",$ArmeArriere_eni,"Degats");
								$Arme3Avion_Multi_eni=GetData("Armes","ID",$ArmeArriere_eni,"Multi");
								if($Arme3Avion_Dg_eni >0)$Degats=round(mt_rand(1,$Arme3Avion_Dg_eni)*mt_rand(1,$Arme3Avion_Multi_eni));
								if($Degats <1)$Degats=mt_rand(1,5);
								$HP-=$Degats;
								if($HP <1)
								{
									$intro.='<p>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)</p>';
									$end_mission=true;
									$_SESSION['Parachute']=true;
								}
								else
								{
									$intro.='<p>La rafale frappe votre appareil de plein fouet, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')</p>';
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
									$shoot_tab=true;
									//Critical Hit
									if($Shoot >100)
									{
										$CritH=CriticalHit($Avion_db,$avion,$Pilote_pvp,$Mun_eni,$Engine_Nbr,true);
										$intro.=$CritH[0];
										$end_mission=$CritH[1];
										if($end_mission)
										{
											$HP=0;
											$shoot_tab=false;
										}
										if($CritH[2] ==1)
											$Mun1=0;
										if($CritH[3] ==1)
											$Mun2=0;
										if($CritH[6])
											$essence-=$CritH[6];
										unset($CritH);
									}
								}
							}
							else
							{
								$intro.="<p>Vous évitez la rafale de justesse!</p>";
								$shoot_tab=true;
							}				
						}
						//Conséquences du duel gagné
						if(!$end_mission)
						{
							$alt=SetAlt($alt,$Plafond,$Plafond_eni,0,3000,$c_gaz);
							$Dist_shoot=SetDist_shoot($Pilot,$Pilot_eni,2000);
							$Angle_shoot=SetAngle_shoot($Pilot,$Pilot_eni,0,60);
							$intro.='<p>Vous prenez l\'avantage sur votre adversaire et vous placez en position de tir à une distance d\'environ '.$Dist_shoot.' m, sous un angle de '.$Angle_shoot.'°</p>';
							$shoot_tab=true;
							$zoom_tab=true;
						}
					}
					else
					{
						$fuite=false;						
						if(!$PVP) //IA
						{
							if($Type_avioneni ==2 or $Type_avioneni ==3 or $Type_avioneni ==6 or $Type_avioneni ==7 or $Type_avioneni ==9 or $Type_avioneni ==10 or $Type_avioneni ==11)
								$fuite=true;
							else
							{
								$courage_rand=mt_rand(0,100);
								if($HP_eni <$HPmax_eni and $courage_rand <10)$fuite=true;
								unset($courage_eni);
								unset($courage_rand);
							}
						}
						//fuite
						if($fuite and !$Chk_M3)
						{
							$Enis-=1;
							$UpdateEnis-=1;
							$intro.="<br>Votre adversaire prend la fuite!";
							/*if($HP_eni <$HPmax_eni and $Cible and $Pilote_eni)
								AddProbable($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,1);*/
							if($Enis >0)
							{
								$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
								if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
								{
									$intro.='<p>Ne vous laissant aucun répit, un <b>'.$nom_avioneni.'</b> engage le combat.</p>';
									$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';
									$evade=true;
								}
								else
								{
									if((mt_rand(0,$Tactique) + mt_rand(0,$Pilotage)) >($Tactique_eni + $Pilotage_eni + ($Enis*10)))
									{
										$intro.='<p>Malgré la perte de l\'un des leurs, la formation de <b>'.$nom_avioneni.'</b> ennemie continue sa route.</p>';
										$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';
										$continue_eni=true;
									}
									else
									{
										$intro.="<br>Le reste de la formation ennemie parvient à rompre le combat et à s'enfuir.";
										$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
										$nav=true;
									}
								}
							}
							else
							{
								$intro.="<br>Le dernier ennemi s'est enfuit, vous laissant maître du ciel.";
								$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
								$nav=true;
							}
						}
						else
						{
							$alt=SetAlt($alt,$Plafond,$Plafond_eni,-1000,0,$c_gaz);
							if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
							{
								$intro.="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
								$img="<img src='images/visee.jpg' style='width:100%;'>";
								$evade=true;
							}
							else
							{
								$Arme3Avion_nbr_eni_base=GetData($Avion_db_eni,"ID",$avion_eni,"Arme5_Nbr");
								$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"TourelleSup");
								$mitrailleur=true;
							}
						}
					}
				break;
				case 8:
					// Tenter une attaque frontale.
					$zoom_tab=true;
					$essence-=(15+$Conso);
					$Pilot=mt_rand(0,$Acrobatie*2) + $meteo + ($ManAvion*3) - $PuissAvion + $ManiAvion + ($Moral/10) + $VitAvion + ($Tactique/5);
					$Pilot_eni=mt_rand(10,$Pilotage_eni*2) + $meteo + ($ManAvion_eni*3) - $PuissAvioneni + $ManiAvion_eni + $VitAvioneni + ($Tactique_eni/5) + ($Enis*20);
					//JF
					if($Pilote_pvp ==1)
					{
						$ManAvion_txt=$ManAvion*3;
						$ManAvion_eni_txt=$ManAvion_eni*3;
						$Tactique_txt=$Tactique/5;
						$Tactique_eni_txt=$Tactique_eni/5;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><th colspan='3'>Attaque Frontale</th></tr>
							<tr><td colspan='3'>Bonus : Moral</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
							<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
							<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
							<tr><td>Pilotage</td><td>".$Acrobatie." (A)</td><td>".$Pilotage_eni." (P)</td></tr>
							<tr><td>Tactique</td><td>".$Tactique_txt."</td><td>".$Tactique_eni_txt."</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion."</td><td>-".$PuissAvioneni."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					//End JF
					if($Pilot >=$Pilot_eni and !$Chk_M3)
					{
						$Tir_eni=50;
						$Shoot=mt_rand(0,$Tir_eni) + ($meteo/2) + $VisAvion - ($ManAvion/10) - ($Pilotage/10) - ($VitAvion/10);
						$intro.='<br>Malgré votre manoeuvre supérieure, le <b>'.$nom_avioneni.'</b> a le temps de vous envoyer une rafale !';
						//JF
						if($Pilote_pvp ==1)
						{
							$skills.="<br>[Tir : ".$Shoot."]
												<br>+ Tir_eni ".$Tir_eni."
												<br>+ Vis ".$VisAvion."
												<br>- ManAvion ".$ManAvion." / 10
												<br>- Meteo ".$meteo."
												<br>- Pilotage ".$Pilotage_eni." (rand)";
						}
						//End JF
						if($Shoot >10 and $Arme1Avion_eni >0)
						{
							$Mun_eni=mt_rand(1,5);
							$Bonus_Dg=Damage_Bonus($Avion_db_eni,$avion_eni,$avion,$Arme1Avion_eni,$Blindage,$Mun_eni);
							$Arme1Avion_Dg_eni=GetData("Armes","ID",$Arme1Avion_eni,"Degats");
							$Arme1Avion_Multi_eni=GetData("Armes","ID",$Arme1Avion_eni,"Multi");
							if($Arme1Avion_Dg_eni >0)$Degats=round((mt_rand(1,$Arme1Avion_Dg_eni)+$Bonus_Dg-pow($Blindage,2))*mt_rand(1,$Arme1Avion_Multi_eni));
							if($Degats <1)$Degats=mt_rand(1,5);
							$HP-=$Degats;
							if($HP <1)
							{
								$intro.='<p>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)</p>';
								$end_mission=true;
								$_SESSION['Parachute'] =true;
							}
							else
							{
								$intro.='<p>La rafale frappe votre appareil de plein fouet, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')</p>';
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
								$shoot_tab=true;
								//Critical Hit
								if($Shoot >100)
								{
									$CritH=CriticalHit($Avion_db,$avion,$Pilote_pvp,$Mun_eni,$Engine_Nbr,true);
									$intro.=$CritH[0];
									$end_mission=$CritH[1];
									if($end_mission)
									{
										$HP=0;
										$shoot_tab=false;
									}
									if($CritH[2] ==1)
										$Mun1=0;
									if($CritH[3] ==1)
										$Mun2=0;
									if($CritH[6])
										$essence-=$CritH[6];
									unset($CritH);
								}
							}
						}
						else
						{
							$intro.="<p>Vous évitez la rafale de justesse!</p>";
							$shoot_tab=true;
						}						
						if(!$end_mission)
						{
							$Dist_shoot=SetDist_shoot($Pilot,$Pilot_eni,1000);
							$Angle_shoot=SetAngle_shoot($Pilot,$Pilot_eni,91,180);
							$intro.='<p>Vous prenez l\'avantage sur votre adversaire et vous placez en position de tir à une distance d\'environ '.$Dist_shoot.' m, sous un angle de '.$Angle_shoot.'°</p>';
							$shoot_tab=true;
						}
					}
					else
					{
						if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==5 or $Type_avioneni ==12)
						{
							$intro.="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
							$img="<img src='images/visee.jpg' style='width:100%;'>";
							if(!$PVP)
								$Shoots=true;
							else
								$evade=true;
						}
						else
						{
							$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"ArmePrincipale");
							$Arme3Avion_nbr_eni_base=GetData($Avion_db_eni,"ID",$avion_eni,"Arme1_Nbr");
							$mitrailleur=true;
						}
					}
				break;
				case 9:
					//Mitrailleur arrière.
					if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
					{
						$essence-=(5+$Conso);
						$chemin-=2;
						//Mitrailleur arrière avion
						if($Type_avion !=1 and $Type_avion !=12)
						{
							$Arme3=GetData($Avion_db,"ID",$avion,"Arme3_Nbr");
							if($Arme3 >0)
								$Arme3Avion=GetData($Avion_db,"ID",$avion,"ArmeArriere");
							else
								$Arme3Avion=GetData($Avion_db,"ID",$avion,"TourelleSup");
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
									$result=mysqli_query($con,"SELECT Courage,Moral,Tir,Trait FROM Equipage_PVP WHERE ID='$Equipage'");		
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
										$Tir_mg=round($Tir_mg*1.1);
									elseif($Trait_e ==2 and $Courage_Eq <100)
										$Courage_Eq=100;
									elseif($Trait_e ==8 and $Moral_Eq <100)
										$Moral_Eq=100;
									if($Courage_Eq <1 and $Trait_e !=6)
									{
										$go_shoot=false;
										$Etat_Eq=", il est tétanisé par la peur";
									}
									if($Moral_Eq <1 and $Trait_e !=6)
									{
										$go_shoot=false;
										$Etat_Eq=", il est démoralisé";
									}
								}
								else
									$Tir_mg=mt_rand(25,100);
								if($go_shoot and !$Chk_M3)
								{
									$Pilotage_eni=mt_rand(25,100);
									$Tactique_eni=mt_rand(25,100);
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
									$Shoot=mt_rand(0,$Tir_mg) + $meteo + $Vis_eni - ($ManAvion_eni/10) - $Rand_Pil_eni - $Rand_Tac_eni;
									//JF
									if($Pilote_pvp ==1)
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
									UpdateCarac($Pilote_pvp,"S_Tourelle_Mun",-$Mun_mg,"Pilote_PVP");
									if($Shoot >0)
									{
										if($Shoot >50)
										{
											$Arme3Avion_Dg=GetData("Armes","ID",$Arme3Avion,"Degats");
											if($Arme3Avion_Dg >0)
											{
												$Avion_Mun=GetData("Pilote_PVP","ID",$Pilote_pvp,"S_Avion_Mun");
												$Bonus_Dg=Damage_Bonus($Avion_db,$avion,$avion_eni,$Arme3Avion,$Blindage_eni,$Avion_Mun);
												$Degats=round((mt_rand(1,$Arme3Avion_Dg) + $Bonus_Dg - pow($Blindage_eni,2))*mt_rand(1,$Arme3Avion_Multi));
											}
										}
										else
											$Degats=0;
										if($Degats <1)$Degats=mt_rand(1,5);
										$HP_eni-=$Degats;
										if($HP_eni <1)
										{
											$intro.="<p>Votre mitrailleur fait mouche et abat l'avion ennemi !</p>";
											$end_shoot=true;
										}
										else
										{
											$intro.='<p>Votre mitrailleur touche l\'avion ennemi!';
											$Shoots=true;
										}
									}
									else
									{
										$intro.="<p>Votre mitrailleur tire, mais manque sa cible.</p>";
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
							$Shoots=true;
					}
					else
					{
						$intro.='<br>Vous continuez votre route, à environ '.$alt.'m d\'altitude.';
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						$nav=true;
					}	
				break;
				case 10:
					//Abandonner l'appareil et saut en parachute
					$end_mission=true;
					$_SESSION['Parachute']=true;
				break;
				case 11:
					//Effectuer une manuvre d'attaque par le ventre du bombardier.
					$essence-=(10+$Conso);
					$Pilot=mt_rand(0,$Pilotage*2) + $meteo - ($PuissAvion/3) + ($ManiAvion) + ($Moral/10) + mt_rand(0,$Tactique*2);
					$Pilot_eni=mt_rand(10,$Pilotage_eni*2) + $meteo - ($PuissAvioneni/3) + ($ManiAvion_eni) + mt_rand(10,$Tactique_eni*2) + ($Enis*20);
					//JF
					if($Pilote_pvp ==1)
					{
						$PuissAvion_txt=$PuissAvion/3;
						$PuissAvioneni_txt=$PuissAvioneni/3;
						$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
							<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
							<tr><th>HP</th><th>".$HP."</th><th>".$HP_eni."</th></tr>
							<tr><th colspan='3'>Ventre / Avantage</th></tr>
							<tr><td colspan='3'>Bonus : Moral</td></tr>
							<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
							<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
							<tr><td>Acrobatie</td><td>".$Acrobatie."</td><td>".$Acrobatie_eni."</td></tr>
							<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
							<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
							<tr><th>Total</th><th>".$Pilot."</th><th>".$Pilot_eni."</th></tr>
						</table>";
					}
					//End JF
					if($Pilot >=$Pilot_eni and !$Chk_M3)
					{
						$Dist_shoot=SetDist_shoot($Pilot,$Pilot_eni);
						$Angle_shoot=SetAngle_shoot($Pilot,$Pilot_eni,0,60);
						$intro.='<p>Vous prenez l\'avantage sur votre adversaire et vous placez en position de tir à une distance d\'environ '.$Dist_shoot.' m, sous un angle de '.$Angle_shoot.'°</p>';
						$UpdateMoral+=1;
						$shoot_tab=true;
						$Arme3Avion_nbr_eni=GetData($Avion_db_eni,"ID",$avion_eni,"Arme6_Nbr");
						if($Arme3Avion_nbr_eni)
						{
							if($Pilot >$Pilot_eni+50)
								$Tir_eni=1;
							elseif($Pilot >$Pilot_eni)
								$Tir_eni=mt_rand(10,50);
							else
								$Tir_eni=mt_rand(25,100);
							$Shoot=mt_rand(0,$Tir_eni) + ($meteo/2) + $VisAvion - ($ManAvion/10) - ($Pilotage/10) - ($Dist_shoot/10) + $Arme3Avion_nbr_eni;
							$intro.="<br>Le mitrailleur du <b>".$nom_avioneni."</b> tire à votre approche !";
							if($Shoot >0 or $Tir_eni >200)
							{
								$ArmeDef=GetData($Avion_db_eni,"ID",$avion_eni,"TourelleVentre");
								$Arme3Avion_nbr_eni=GetData($Avion_db_eni,"ID",$avion_eni,"Arme6_Nbr");
								$Arme3Avion_nbr_eni=GetShoot($Shoot,$Arme3Avion_nbr_eni);
								$Arme3Avion_Dg_eni=GetData("Armes","ID",$ArmeDef,"Degats");
								$Arme3Avion_Multi_eni=GetData("Armes","ID",$ArmeDef,"Multi");
								$Mun_eni=mt_rand(1,5);
								$Bonus_Dg=Damage_Bonus($Avion_db_eni,$avion_eni,$avion,$ArmeDef,$Blindage,$Mun_eni,$Dist_shoot);
								$Degats=0;
								if($Arme3Avion_Dg_eni >0)
								{
									for($i=1;$i<=$Arme3Avion_nbr_eni;$i++)
									{
										$Degats+=round((mt_rand(1,$Arme3Avion_Dg_eni)+$Bonus_Dg-pow($Blindage,2))*mt_rand(1,$Arme3Avion_Multi_eni));
									}
								}
								if($Degats <1)$Degats=mt_rand(1,5);
								$HP-=$Degats;
								if($HP <1)
								{
									$intro.='<br>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
									$Action=99;
									$end_mission=true;
									$shoot_tab=false;
									$_SESSION['Parachute']=true;
								}
								else
								{
									$intro.='<br>La rafale frappe votre appareil de plein fouet, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
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
								}
								SetData("Pilote_PVP","S_HP",$HP,"ID",$Pilote_pvp);
							}
							else
								$intro.="<p>Vous évitez la rafale de justesse!</p>";
						}
					}
					else
					{
						if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							$img="<img src='images/visee.jpg' style='width:100%;'>";
							if($Pilot_eni >$Pilot+50)
							{
								$intro.="<p>Votre adversaire joue littéralement avec vous et n'a aucun mal à se placer en position de tir</p>";
								$Shoots=true;
							}
							else
							{
								$intro.="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
								$evade=true;
							}
						}
						elseif(GetData($Avion_db_eni,"ID",$avion_eni,"TourelleVentre"))
						{
							$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"TourelleVentre");
							$Arme3Avion_nbr_eni_base=GetData($Avion_db_eni,"ID",$avion_eni,"Arme6_Nbr");
							$UpdateTactique-=1;
							$mitrailleur=true;
						}
						elseif($Type_avioneni ==3)
						{
							$intro.="<p>Votre adversaire prend l'avantage sur vous et en profite pour rompre le combat et s'enfuir</p>";
							$img.="<img src='images/toofast.jpg' style='width:100%;'>";
							$Enis-=1;
							if($Enis >0)
								$continue_eni=true;
							else
								$nav=true;
						}
						else
						{
							$intro.="<br>Votre adversaire parvient à se dégager de votre axe de tir";
							$img.='<img src=\'images/stay'.$country.'.jpg\' style=\'width:100%;\'>';
							$evade=true;
						}
					}
				break;
				case 12:
					//Tenter de fuir le combat en grimpant.
					$essence-=(10+$Conso);
					$chemin-=1;
					$alt=SetAlt($alt,$Plafond,$Plafond,$VitAAvion,$VitAAvion,$c_gaz);
					if($alt >$Plafond_eni and !$Chk_M3)
					{
						$intro.="<p>L'ennemi ne peut vous atteindre à cette altitude, vous lui échappez!</p>";
						$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
						$nav=true;
					}
					else
					{
						$Pilot=(mt_rand(0,$Pilotage)/2) + $meteo - ($PuissAvion/2) + ($VitAAvion*2);
						$Pilot_eni=(mt_rand(0,$Pilotage_eni)/2) + $meteo - ($PuissAvioneni/2) + ($VitAAvioneni*2) + ($Enis*20);
						if($Pilot >=$Pilot_eni and !$Chk_M3)
						{
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
							$intro.='<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
							$nav=true;
						}
						elseif($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							$img="<img src='images/visee.jpg' style='width:100%;'>";
							if($Enis >2 and $Enis >$Formation+1)
							{
								$intro.="<p>Vos adversaires, trop nombreux, vous empêchent de fuir!<br>L'un d'eux se place en position de tir!</p>";
								$Shoots=true;
							}
							else
							{
								if(!$PVP and $Pilot_eni >$Pilot+50)
								{
									$intro.="<p>Votre adversaire joue littéralement avec vous et n'a aucun mal à se placer en position de tir</p>";
									$Shoots=true;
								}
								else
								{
									$intro .="<p>Votre adversaire prend l'avantage sur vous et se place en position de tir</p>";
									$evade=true;
								}
							}
						}
						else
						{
							$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg', 'images/avions/vol'.$avion_img.'.jpg', $nom_avion);
							$intro.='<br>Vous parvenez à vous échapper et continuez votre route, à environ '.$alt.'m d\'altitude.';
							$nav=true;
						}
					}
				break;
				//Case 13 and 14 reserved
				case 15:
					//Surprise As eni
					$intro.="<p>Surgissant dans votre dos par surprise, un avion que vous n'aviez pas détecté tire!</p>";
					$img="<img src='images/surprise.jpg' style='width:100%;'>";
					$Bonus_Tir=$Tactique_eni/10;
					$Bonus_Pil=true;
					$Shoots=true;
				break;
				case 16:
					//Surnombre
					$intro.="<p><b>L'ennemi en surnombre vous empêche de manoeuvrer librement!</b></p>";
					$img=Afficher_Image('images/miss_leader'.$country.'.jpg', "images/image.png", "");
					$evade=true;
				break;
				case 17:
					//Attirer l'ennemi sur vous
					$chemin-=5;
					$essence-=(5+$Conso);
					$Shoots=true;
				break;
				case 18:
					$intro.="<p><b>Vous vous délestez de vos charges!</b></p>";
					$img=Afficher_Image('images/alleger.jpg',"images/image.png", "");
					SetData("Pilote_PVP","S_Avion_Bombe_Nbr",0,"ID",$Pilote_pvp);
					$evade=true;				
				break;
				case 19:
					$intro.="<p><b>Vous larguez votre réservoir supplémentaire!</b></p>";
					$img=Afficher_Image('images/alleger.jpg',"images/image.png", "");
					SetData("Pilote_PVP","S_Baby",0,"ID",$Pilote_pvp);
					$evade=true;				
				break;
				case 98:
					$intro.="<p>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !</p>";
					$end_mission=true;
				break;
				case 99:
					$intro.="<p>Incapable de maîtriser son appareil se désagrégeant de toutes parts, le pilote ennemi abandonne son avion, qui part en vrille et tombe vers le sol.</p>";
					$end_shoot=true;
				break;
			}			
			//Combat tournoyant ou acrobatie
			if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
				$choix2="<Input type='Radio' title='".GetMes('Aide_Avantage')."' name='Action' value='2'>- Tenter une manoeuvre pour reprendre l'avantage.<br>";
			else
				$choix2="<Input type='Radio' title='".GetMes('Aide_Avantage')."' name='Action' value='2'>- Effectuer une manuvre pour tenter d'attaquer l'ennemi par le flanc.<br>";		
			if($alt<1)$alt=50;
			$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$PuissAvion,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,$malus_incident,$Avion_db,$flaps,true);
		}
		//En cas d'équipage tué
		$Equipage=GetData("Pilote_PVP","ID",$Pilote_pvp,"Equipage");		
		//***WRITE TO DB***
		if(!$Chk_M3 and !$Sandbox)
		{
			if($Update_S_Escorteb_nbr !=0)
				UpdateCarac($PlayerID,"S_Escorteb_nbr",$Update_S_Escorteb_nbr);
			if($Update_S_Escorte_nbr !=0)
				UpdateCarac($PlayerID,"S_Escorte_nbr",$Update_S_Escorte_nbr);
			if($UpdateStress_Moteur !=0)
				UpdateCarac($PlayerID,"Stress_Moteur",$UpdateStress_Moteur);
			if($UpdateStress_Commandes !=0)
				UpdateCarac($PlayerID,"Stress_Commandes",$UpdateStress_Commandes);
			if($UpdateEnis !=0)
				UpdateCarac($PlayerID,"enis",$UpdateEnis);
		}
		SetData("Pilote_PVP","S_HP",$HP,"ID",$Pilote_pvp);
		if($PVP and !$Sandbox)
		{
			SetData("Duels_Candidats_PVP","HP",$HP,"PlayerID",$Pilote_pvp);
			SetData("Duels_Candidats_PVP","Altitude",$alt,"PlayerID",$Pilote_pvp);
			SetData("Duels_Candidats_PVP","Altitude",$alt,"PlayerID",$Pilote_eni);
			SetData("Duels_Candidats_PVP","HP",$HP_eni,"PlayerID",$Pilote_eni);
		}
		//***END WRITE***
		$Pays_avion_eni=GetData("Avion","ID",$avion_eni,"Pays");		
		if($panne_seche)
		{
			$_SESSION['done']=false;
			$_SESSION['missiontrois']=true;
			$intro.="<br>Vous tombez en panne sèche!<br>Vous n'avez pas d'autre choix que d'abandonner votre appareil.<br>Vous parvenez à rejoindre vos lignes à grand peine, mais vous êtes en vie!";
			$img.=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.="<form action='index.php?view=profil_pvp' method='post'><input type='hidden' name='Blesse' value='0'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}			
		if($end_shoot)
		{
			SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
			$_SESSION['missiontrois']=true;
			$titre="Combat";
			$img=Afficher_Image('images/kill'.$country.'.jpg', "images/kill.jpg","Victoire!");
			$mes.='<form action=\'index.php?view=kill_confirm_pvp\' method=\'post\'>
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
			<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
			<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
			<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
			<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,1,true).'
			'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion,true).'
			<table class=\'table\'><tr><td align=\'left\'>
						<Input type=\'Radio\' name=\'Action\' value=\'1\' checked>- Poursuivre votre proie pour confirmer votre victoire.<br>
						<Input type=\'Radio\' name=\'Action\' value=\'2\'>- Vous désintéresser de votre proie et continuer le combat.<br>
				</td></tr></table>
			<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}		
		if($Shoots)
		{
			$oo_mun_luck=mt_rand(0,100);
			//Porte-bonheur
			if($Slot10 ==34 or $Slot10 ==71 or $Slot10 ==72 or $Slot10 ==77)
				$oo_mun_luck-=5;
			if($Pilotage <100 and $Pilotage_eni >100)
				$oo_mun_luck-=50;
			if(!$PVP and ($oo_mun_luck <5 or $HP_eni <100))
			{
				$intro.='<p>Le <b>'.$nom_avioneni.'</b> abandonne la poursuite pour une raison inconnue.</p>';
				$Enis-=1;
				UpdateData("Pilote_PVP","enis",-1,"ID",$Pilote_pvp);
				/*if($HP_eni <$HPmax_eni and $Cible and $Pilote_eni)
					AddProbable($Avion_db,$avion_eni,$avion,$Pilote_pvp,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni);*/		
				if($Enis >0 and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12))
				{
					//IA=Chasseurs haute altitude
					if($Enis <3 and $alt >6000)
					{
						$Compresseur=GetData("Moteur","ID",$Engine,"Compresseur");
						$Compresseur_eni=GetData("Moteur","ID",$Engine_eni,"Compresseur");
						if($Compresseur >1 and $Compresseur_eni <2)
						{
							$intro.="<p>La formation ennemie ne se sentant pas de taille en profite pour filer !</p>";
							$img=Afficher_Image("images/epargner.jpg", "images/enrayage.jpg","Abandon de la poursuite");
							$nav=true;
						}
						else
						{
							$intro.='<p>Malgré l\'abandon d\'un des leurs, un autre <b>'.$nom_avioneni.'</b> fonce sur vous !</p>';
							$img=Afficher_Image('images/avions/pique'.$avion_eni.'.jpg', 'images/avions/vol'.$avion_eni.'.jpg', $nom_avioneni);
							$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
							$continue_eni=true;
						}
					}
					else
					{		
						$intro.='<p>Malgré l\'abandon d\'un des leurs, un autre <b>'.$nom_avioneni.'</b> fonce sur vous !</p>';
						$img=Afficher_Image('images/avions/pique'.$avion_eni.'.jpg', 'images/avions/vol'.$avion_eni.'.jpg', $nom_avioneni);
						$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
						$continue_eni=true;
					}
				}
				else
				{
					$img=Afficher_Image("images/epargner.jpg", "images/enrayage.jpg","Abandon de la poursuite");
					$nav=true;
				}
			}
			elseif($PVP)
			{
				$img='<img src=\'images/shooted'.$country.'.jpg\' style=\'width:100%;\'>';
				$intro.="<p>Votre adversaire joue avec vous!</p>";
				$evade=true;
			}
			else
			{
				if($Bonus_Pil ==true) //Tir par surprise
				{
					$Bonus_Pil=0;
					if(!$Blindage)
						$Mun_eni=2;
					else
						$Mun_eni=1;
				}
				else
				{
					$Bonus_Pil=mt_rand(0,$Pilotage)+($ManAvion/10)+($ManiAvion/10);
					$Mun_eni=mt_rand(1,5);
				}
				$Tir_eni=mt_rand(25,100);
				$Rand_Tir_eni=mt_rand(10,$Tir_eni);
				$Shoot=$Rand_Tir_eni+($meteo/2)+($VisAvion/5)-$Bonus_Pil+$Bonus_Tir+$Enis;
				//JF
				if($Pilote_pvp ==1)
				{
					$skills.="<br>[Score de Tir : ".$Shoot."]
										<br>-Meteo ".$Meteo." /2
										<br>-Man ".$ManAvion." /10
										<br>-Mani ".$ManiAvion." /10
										<br>-Pilotage ".$Pilotage." (rand)
										<br>+Vis ".$VisAvion." /10
										<br>+Enis ".$Bonus_Tir."
										<br>+Tir_eni :".$Rand_Tir_eni;
				}
				//End JF
				$intro.='<p>Le <b>'.$nom_avioneni.'</b> tire !</p>';
				if($Shoot >0 or $Rand_Tir_eni ==$Tir_eni)
				{
					$Arme1_eni_nbr=GetData($Avion_db_eni,"ID",$avion_eni,"Arme1_Nbr");
					$Arme2_eni_nbr=GetData($Avion_db_eni,"ID",$avion_eni,"Arme2_Nbr");
					if($Arme2_eni_nbr >$Arme1_eni_nbr)
					{
						$Arme1Avion_nbr_eni=$Arme2_eni_nbr;
						$Arme1Avion_Dg_eni=GetData("Armes","ID",$Arme2Avion_eni,"Degats");
						$Arme1Avion_Multi_eni=GetData("Armes","ID",$Arme2Avion_eni,"Multi");
					}
					else
					{
						$Arme1Avion_nbr_eni=$Arme1_eni_nbr;
						$Arme1Avion_Dg_eni=GetData("Armes","ID",$Arme1Avion_eni,"Degats");
						$Arme1Avion_Multi_eni=GetData("Armes","ID",$Arme1Avion_eni,"Multi");
					}
					$Bonus_Tir=$Enis;
					if($Bonus_Tir >5)$Bonus_Tir=5;
					$Bonus_Dg=Damage_Bonus($Avion_db_eni,$avion_eni,$avion,$Arme1Avion_eni,$Blindage,$Mun_eni);
					$Arme1Avion_nbr_eni=GetShoot($Shoot,$Arme1Avion_nbr_eni)+$Bonus_Tir;
					$Degats=0;
					if($Arme1Avion_Dg_eni >0)
					{
						for($i=1;$i<=$Arme1Avion_nbr_eni;$i++)
						{
							if($Rand_Tir_eni ==$Tir_eni)
								$Degats+=round(($Arme1Avion_Dg_eni + $Bonus_Dg - pow($Blindage,2))*$Arme1Avion_Multi_eni);
							else
								$Degats+=round((mt_rand(1,$Arme1Avion_Dg_eni) + $Bonus_Dg - pow($Blindage,2))*mt_rand(1,$Arme1Avion_Multi_eni));
						}
					}
					if($Degats <1)$Degats=mt_rand(1,5);
					$HP-=$Degats;
					if($HP <1)
					{
						$intro.='<p>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)</p>';
						$end_mission=true;
						$_SESSION['Parachute']=true;
					}
					else
					{
						//Critical Hit
						if($Shoot >100 or $Rand_Tir_eni ==$Tir_eni)
						{
							$CritH=CriticalHit($Avion_db,$avion,$Pilote_pvp,$Mun_eni,$Engine_Nbr,true);
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
						$img=Afficher_Image('images/hit'.$country.$Type_avion.'.jpg',"images/hit.jpg","Touché");
						$intro.='<p>La rafale frappe votre appareil de plein fouet, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')</p>';
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
						if(!$end_mission)$evade=true;
					}
					SetData("Pilote_PVP","S_HP", $HP,"ID",$Pilote_pvp);
				}
				else
				{
					$img='<img src=\'images/shooted'.$country.'.jpg\' style=\'width:100%;\'>';
					$intro.="<p>Vous évitez la rafale de justesse!</p>";
					$evade=true;
				}
			}
		}
		if($continue_eni)
		{
			if($meteo <-9)
				$choix5="Tenter de vous échapper en vous cachant dans les nuages.";
			else
			{
				if($Nuit)
					$choix5="Tenter de vous échapper en profitant de la nuit.";
				else
					$choix5="Tenter de vous échapper face au soleil.";
			}
			$Pilote_eni=3297;
			$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
			//Seuls les chasseurs et chasseurs lourds attaquent
			if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
			{
				$choix1="<Input type='Radio' name='Action' value='1'>- Attaquer la formation ennemie par l'arrière.<br>";
				if($alt >1000)
					$choix7="<Input type='Radio' name='Action' title='".GetMes('Aide_Coiffer')."' value='7'>- Chercher à prendre de l'altitude pour ensuite piquer sur la formation ennemie.<br>";				
				else
					$choix7="";
				$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Attaquer la formation ennemie par le flanc.<br>";
				//Attaque par le ventre
				if($alt >1000 and $Tactique >50 and ($Type_avioneni ==2 or $Type_avioneni ==11))
					$Ventre ="<Input type='Radio' name='Action' value='11'>- Tenter d'attaquer par le ventre en évitant le mitrailleur arrière.<br>";
			}
			elseif($Equipage_Nbr >1 and $avion !=43 and $avion !=109)
			{
				$choix1="<Input type='Radio' name='Action' value='9'>- Maintenir votre cap, votre mitrailleur arrière se chargeant de l'adversaire.<br>";
				$choix7="";
				$choix2="";
			}
			else
			{
				$choix1="";
				$choix7="";
				$choix2="";
			}
			SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
			$_SESSION['missiontrois']=99;
			$titre="Combat";
			$intro.=$msg_again;
			$mes.='<form action=\'index.php?view=mission3_pvp\' method=\'post\'>
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>	
			<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Avioneni\' value='.$avion_eni.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'HP_eni\' value='.$HP_eni.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
			<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
			<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
			<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
			<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,1,true).'
			'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion,true).'
			<table class=\'table\'><tr><td align=\'left\'>'.$choix1.$choix7.$choix2.'
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'4\' checked>- Tenter de rompre le combat en vous lançant dans un piqué.<br>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Nuages').'\' value=\'5\'>- '.$choix5.'<br>'.$Ventre.'
			</td></tr></table><input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}		
		if($shoot_tab)
		{
			if($Tactique_eni <10)$Tactique_eni=mt_rand(100,200);
			if($Type_avioneni ==1 and (mt_rand(0,$Tactique_eni) > mt_rand(0,$Tactique)+50 or $Chk_M3))
			{
				if($zoom_tab)
					$intro.="<p>Votre adversaire vous a vu arriver et dégage au dernier moment, vous obligeant à le dépasser !</p>";
				else
					$intro.="<p>Votre adversaire effectue un tonneau barriqué, vous obligeant à le dépasser !</p>";
				$evade=true;
			}
			elseif($Enis >1 and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12) and mt_rand(10,$Tactique_eni) > mt_rand(0,$Tactique)+25)
			{
				$intro.="<p>L'ailier de votre adversaire protège son leader, vous obligeant à dégager !</p>";
				$evade=true;
			}
			else
			{
				//Efficacité de l'arme à cette distance
				$Arme1Avion_Range=GetData("Armes","ID",$Arme1Avion,"Portee");
				$Malus_Range=GetMalus_Range($Dist_shoot,$Arme1Avion_Range,$Angle_shoot);
				$chk=$meteo-$Malus_Range+($Vis_eni/10)+($Courage/10)-($Pilotage_eni/10);
				if($chk ==0)$chk=-1;
				$luck=round(100/(31/$chk));
				if($luck <1)
					$luck=1;
				elseif($luck >99)
					$luck=99;
				$menu.='[Efficacité de l\'arme à cette distance : '.$luck.' % ]';
				if($Angle_shoot >45)$zoom_tab=true;
				if($zoom_tab)
					$zoom_txt="";
				else
				{
					$zoom_txt="<Input type='Radio' name='Action' value='2'>- Vous rapprocher à la distance idéale pour faire un maximum de dégâts.<br>
					<Input type='Radio' name='Action' title='".GetMes('Aide_Barrique')."' value='11'>- Effectuer une manoeuvre pour vous rapprocher sans risquer de dépasser votre adversaire.<br>";
				}				
				if($Arme2Avion ==5 or $Arme2Avion ==0 or $Arme2Avion ==25 or $Arme2Avion ==26 or $Arme2Avion ==27)
					$DeuxArmes="";
				else
				{
					$DeuxArmes='<Input type=\'Radio\' name=\'Action\' value=\'5\'>- Lâcher une courte rafale à l\'aide de votre '.$Arme2.' ('.$Mun2.' coups).<br>
					<Input type=\'Radio\' name=\'Action\' value=\'6\'>- Lâcher une longue rafale avec votre '.$Arme2.' ('.$Mun2.' coups).<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Courte_Rafale').'\' value=\'7\'>- Lâcher une courte rafale à l\'aide de toutes vos armes de bord.<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Longue_Rafale').'\' value=\'8\'>- Lâcher une longue rafale à l\'aide de toutes vos armes de bord.<br>';
				}
				SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
				$_SESSION['missiontrois']=true;
				$titre="Combat";
				$img='<img src=\'images/visee'.$country.'.jpg\' style=\'width:100%;\'>';
				$mes.='<form action=\'index.php?view=shoot_pvp\' method=\'post\'>
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
						<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
						<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
						<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
						<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
						'.ShowGaz($avion,$c_gaz,$flaps,$alt,5,true).'
						'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion,true).'
						<table class=\'table\'><tr><td align=\'left\'>
									<Input type=\'Radio\' name=\'Action\' value=\'1\'>- Epargner votre adversaire.<br>'.$zoom_txt.'
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Courte_Rafale').'\' value=\'3\'>- Lâcher une courte rafale à l\'aide de votre '.$Arme1.' ('.$Mun1.' coups).<br>
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Longue_Rafale').'\' value=\'4\'>- Lâcher une longue rafale avec votre '.$Arme1.'('.$Mun1.' coups).<br>'.$DeuxArmes.'
									<Input type=\'Radio\' name=\'Action\' value=\'12\'>- Attendre qu\'une meilleure opportunité se présente.<br>
									<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Rompre').'\' value=\'9\' checked>- Rompre le combat.<br>
							</td></tr></table>
						<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form></div>';
			}
		}			
		if($mitrailleur)
		{
			if(!$ArmeArriere_eni)$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"ArmeArriere");
			if(IsEnrayage($ArmeArriere_eni,$alt) and !$Chk_M3)
			{
				$intro.='<br>Le mitrailleur du <b>'.$nom_avioneni.'</b> tire à votre approche !<br>Heureusement pour vous, son arme s\'enraye!';
				$evade=true;
			}
			else
			{
				if($ArmeArriere_eni !=5 and $ArmeArriere_eni !=0 and !$PVP)
				{
					$Concentrer=false;
					$Tir_eni=mt_rand(25,250);
					$Rand_Tir_eni=mt_rand(0,$Tir_eni);
					$Shoot=$Rand_Tir_eni + ($meteo/2) + ($VisAvion/2) - ($ManAvion/10) - ($ManiAvion/10) - ($Pilotage/10);
					if($Enis >1 and mt_rand(0,$Tactique_eni) >49)
					{
						$intro.="<p>La formation ennemie concentre son tir sur vous !</p>";
						$Shoot +=$Enis;
						$Concentrer=true;
					}
					else
						$intro.='<p>Le mitrailleur du <b>'.$nom_avioneni.'</b> tire à votre approche !</p>';
					$img=Afficher_Image('images/mg_ar'.$Pays_eni.'.jpg','images/mg_ar9.jpg','mitrailleur',50);
					//JF
					if($Pilote_pvp ==1)
					{
						$skills.="<br>[Score de Tir : ".$Shoot."]
											<br>+Vis ".$VisAvion." /2
											<br>-Man ".$ManAvion." /10
											<br>-Mani ".$ManiAvion." /10
											<br>-Pilotage ".$Pilotage." /10
											<br>Tir_eni :".$Tir_eni;
					}
					//End JF
					if($Shoot >10 or $Rand_Tir_eni ==$Tir_eni or $Chk_M3)
					{
						if(!$Arme3Avion_nbr_eni_base)$Arme3Avion_nbr_eni_base=GetData($Avion_db_eni,"ID",$avion_eni,"Arme3_Nbr");
						$Arme3Avion_Dg_eni=GetData("Armes","ID",$ArmeArriere_eni,"Degats");
						$Arme3Avion_Multi_eni=GetData("Armes","ID",$ArmeArriere_eni,"Multi");
						if($Concentrer)$Arme3Avion_nbr_eni_base+=$Enis-1;
						$Mun_eni=mt_rand(1,5);
						$Bonus_Dg=Damage_Bonus($Avion_db_eni,$avion_eni,$avion,$Arme3Avion_eni,$Blindage,$Mun_eni);
						$Arme3Avion_nbr_eni=GetShoot($Shoot,$Arme3Avion_nbr_eni_base);
						$Degats=0;
						for($i=1;$i<=$Arme3Avion_nbr_eni;$i++)
						{
							$Degats+=round((mt_rand(1,$Arme3Avion_Dg_eni) + $Bonus_Dg - pow($Blindage,2))*mt_rand(1,$Arme3Avion_Multi_eni));
						}
						//$Mun1_eni-=($Arme1Avion_Multi_eni*$Arme1Avion_nbr_eni);
						if($Degats <1)$Degats=mt_rand(1,5);
						$HP-=$Degats;
						if($HP <1)
						{
							$intro.='<br>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
							$end_mission=true;
							$_SESSION['Parachute']=true;
						}
						else
						{
							//Critical Hit
							if($Shoot >100)
							{
								$CritH=CriticalHit($Avion_db,$avion,$Pilote_pvp,$Mun_eni,$Engine_Nbr,true);
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
							$intro.='<br>La rafale frappe votre appareil de plein fouet, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
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
							if(!$end_mission)$evade=true;
						}
						SetData("Pilote_PVP","S_HP",$HP,"ID",$Pilote_pvp);
					}
					else
					{
						$intro.="<p>Vous évitez la rafale de justesse!</p>";
						$evade=true;
					}
				}
				else
				{
					$intro.="<br>Vous dépassez votre adversaire, emporté par votre vitesse !";
					$evade=true;
				}
			}
		}		
		if($evade)
		{
			if($Tactique >50 and $Acrobatie >50)
			{
				$Immelman="<Input type='Radio' name='Action' value='9'>- Tenter de fuir en effectuant un <i>Immelman inversé</i>.<br>";
				if($Type_avion ==3)
					$Rase_Motte="<Input type='Radio' name='Action' value='11'>- Tenter de fuir au ras du sol.<br>";
			}
			if(($HP <$HPmax/2) and GetData("Pilote_PVP","ID",$Pilote_pvp,"Slot3"))
				$Parachute="<Input type='Radio' name='Action' value='10'>- Abandonner l'appareil et sauter en parachute.<br>";
			if($Avion_Bombe and $Avion_Bombe_Nbr >0)
				$Alleger="<Input type='Radio' name='Action' value='18'>- Vider la soute pour alléger l'avion.<br>";
			else
				$Alleger='';
			if($S_Baby >0 and $essence <$Autonomie-$S_Baby)
				$Larguer="<Input type='Radio' name='Action' value='19'>- Larguer le réservoir largable pour alléger l'avion.<br>";
			else
				$Larguer='';
			//Message mitrailleur arrière
			if($Equipage_Nbr >1)
			{
				$choix2='';
				$choix3='';
				$choix5="<Input type='Radio' name='Action' value='5'>- Maintenir votre cap, votre mitrailleur arrière se chargeant de l'adversaire.<br>";
				if(!$PVP and $Escorte_nbr)
					$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='20'>- Appeler votre escorte à l'aide<br>";
				else
					$choix6="";
				if($Formation >0 and ($Tactique >75 or $Radio_a))
					$choix13="<Input type='Radio' name='Action' value='13'>- Ordonner à la formation de concentrer le tir sur votre cible<br>";
			}
			else
				$choix5="<Input type='Radio' name='Action' value='5'>- Vous désintéresser de l'adversaire et maintenir votre cap, atteindre l'objectif est plus important.<br>";
			if($PVP)
				$choix5="";
			if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
				$choix8 ='<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Barrique_Off').'\' value=\'8\'>- Tenter une manoeuvre pour forcer l\'adversaire à vous dépasser.<br>';
			else
				$choix8='';
			if(!$img)$img='<img src=\'images/shooted'.$country.'.jpg\' style=\'width:100%;\'>';
			SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
			$_SESSION['missiontrois']=true;
			$titre='Combat';
			$mes.='<form action=\'index.php?view=evade_pvp\' method=\'post\'>
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
			<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
			<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
			<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
			<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,1,true).'
			'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion,true).'
			<table class=\'table\'><tr><td align=\'left\'>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Degager').'\' value=\'1\' checked>- Tenter de manoeuvrer pour vous dégager de la ligne de tir de votre adversaire.<br>
						'.$choix2.$choix8.$choix3.$choix6.$choix5.$choix13.'
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Man').'\' value=\'4\'>- Tenter de manoeuvrer pour fuir.<br>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'7\'>- Tenter de fuir le combat en vous lançant dans un piqué.<br>'.$Immelman.$Rase_Motte.$Alleger.$Larguer.$Parachute.'
				</td></tr></table>
			<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}
		if($nav)
		{
			if($PVP)
				$continuer_obj='';
			else
				$continuer_obj="<Input type='Radio' name='Action' value='0' checked>- Continuer vers votre objectif.<br>";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Essence='$essence' WHERE ID='$Pilote_pvp'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission3_pvp-reset2');
			mysqli_close($con);
			$_SESSION['missiontrois']=true;
			$titre='Navigation';
			$mes.='<form action=\'index.php?view=nav_pvp\' method=\'post\'>
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
			<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
			<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
			<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,false,true).'
			'.GetSituation($Enis,$avion_eni,$Pays_avion_eni,$Leader,$Ailier,$avion,true).'
			<table class=\'table\'><tr><td align=\'left\'>'.$continuer_obj.'<Input type=\'Radio\' name=\'Action\' value=\'1\'>- Faire demi-tour.<br></td></tr></table>
			<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}
	}
	if($end_mission)
		include_once('./end_mission_pvp.php');
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
include_once('./default.php');