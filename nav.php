<?php
require_once('./jfv_inc_sessions.php');
/*$time=microtime();
$time=explode(' ',$time);
$time=$time[1]+$time[0];
$start=$time;*/
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
/*if($_SESSION['PlayerID'] ==2)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$meteo=Insec($_POST['Meteo']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$Enis=Insec($_POST['Enis']);
$Cible_Atk_Post=Insec($_POST['Cible_Atk']);
$Patrol=Insec($_POST['Patrol']);
$Action=Insec($_POST['Action']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$PlayerID=$_SESSION['PlayerID'];
//Check Joueur Valide
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_nav.inc.php');
	$_SESSION['bombarder']=false;
	$_SESSION['attaquer']=false;
	$_SESSION['photographier']=false;
	$_SESSION['cibler']=false;
	$_SESSION['objectif']=false;
	$_SESSION['finish']=false;
	$_SESSION['tirer']=false;
	$_SESSION['evader']=false;
	$_SESSION['missiontrois']=false;
	$_SESSION['kill_confirm']=false;
	$_SESSION['atterr']=false;
	//$_SESSION['naviguer']=true;
	$end_mission=false;
	$finnav=false;
	$panne_seche=false;
	$SetLongLat=false;
	$mission2=false;
	$nav=false;
	$Noob_Front=false;
	$UpdateMoral=0;
	$UpdateReput=0;
	$UpdateGrade=0;
	$UpdateTactique=0;
	$UpdateNavigation=0;
	$Update_S_Escorteb_nbr=0;
	$UpdateStress_Moteur=0;
	$UpdateStress_Arme1=0;
	$UpdateStress_Arme2=0;
	$Update_Unit_Reput=0;
	$PVP=$_SESSION['PVP'];
	$done=$_SESSION['done'];
	$Saison=$_SESSION['Saison'];
	$SensH=$_SESSION['SensH'];
	$SensV=$_SESSION['SensV'];
	$Long_par_km=$_SESSION['Long_par_km'];
	$Lat_par_km=$_SESSION['Lat_par_km'];
	$_SESSION['Escorte_eni_Nbr']=0;
	$_SESSION['Escorte_eni_avion_eni']=0;
	$_SESSION['Escorte_eni_Unit_eni']=0;
	$Chk_Nav=$_SESSION['naviguer'];
	if($Chk_Nav >0 and $Chk_Nav !=99)
	{
		$mes='<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>';
		UpdateCarac($PlayerID,"Free",-1);
		MoveCredits($PlayerID,90,-1);
		if($Mission_Type ==15)SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);	
		UpdateCarac($PlayerID,"Reputation",-10);
		UpdateCarac($PlayerID,"Avancement",-10);
		mail('binote@hotmail.com',"Aube des Aigles: Init Mission F5 (nav) : ".$PlayerID , "Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}	
	if($Action ==18)
	{
		$intro.="<p><b>Vous vous délestez de vos charges!</b></p>";
		$img=Afficher_Image('images/alleger.jpg',"images/image.png","");
		SetData("Pilote","S_Avion_Bombe_Nbr",0,"ID",$PlayerID);
		$Action=0;
	}
	elseif($Action ==19)
	{
		$intro.="<p><b>Vous larguez votre réservoir supplémentaire!</b></p>";
		$img=Afficher_Image('images/alleger.jpg',"images/image.png","");
		SetData("Pilote","S_Baby",0,"ID",$PlayerID);
		$Action=0;
	}
	$con=dbconnecti();
	$Admin=mysqli_result(mysqli_query($con,"SELECT Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
	$result=mysqli_query($con,"SELECT S_HP,Unit,Pays,Front,Front_sandbox,Navigation,Pilotage,Vue,Moral,Ailier,S_Ailier,Equipage,S_Avion_db,S_Nuit,S_Cible,S_Mission,S_Longitude,S_Latitude,
	S_Escorteb,S_Escorteb_nbr,S_Escorteb_nom,S_Equipage_Nbr,S_Leader,S_Meteo,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,S_Baby,Simu,
	Slot2,Slot4,Slot6,Slot11,Sandbox FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav-player');
	$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
	if($results){
		while($data=mysqli_fetch_array($results,MYSQLI_ASSOC)){
			$Skills_Pil[]=$data['Skill'];
		}
		mysqli_free_result($results);
	}
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)){
			$HP=$data['S_HP'];
			$Unite=$data['Unit'];
			$country=$data['Pays'];
			$Front=$data['Front'];
			$Front_sandbox=$data['Front_sandbox'];
			$Avion_db=$data['S_Avion_db'];
			$Cible=$data['S_Cible'];
			$Nuit=$data['S_Nuit'];
			$Mission_Type=$data['S_Mission'];
			$Pilotage=$data['Pilotage'];
			$Navigation=$data['Navigation'];
			$Vue=$data['Vue'];
			$Moral=$data['Moral'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Escorteb=$data['S_Escorteb'];
			$Escorteb_nbr=$data['S_Escorteb_nbr'];
			$Escorteb_nom=$data['S_Escorteb_nom'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Avion_Bombe_Nbr=$data['S_Avion_Bombe_Nbr'];
			$S_Baby=$data['S_Baby'];
			$Leader=$data['S_Leader'];
			$Meteo_PJ=$data['S_Meteo'];
			$Slot2=$data['Slot2'];
			$Slot4=$data['Slot4'];
			$Slot6=$data['Slot6'];
			$Slot11=$data['Slot11'];
			$Simu=$data['Simu'];
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
	if($Navigation >50)$Navigation=50;
	if($Vue >50)$Vue=50;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(34,$Skills_Pil))
			$Oeil_Lynx=true;
		if(in_array(37,$Skills_Pil))
			$S_Orient=true;
		if(in_array(38,$Skills_Pil))
			$PSV=true;
		if(in_array(50,$Skills_Pil))
			$Bonne_Etoile=true;
	}
	if($HP <1 or !$Cible)
		$end_mission=true;
	else
	{
		$Unit_Base=mysqli_result(mysqli_query($con,"SELECT Base FROM Unit WHERE ID='$Unite'"),0);
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$resultc=mysqli_query($con,"SELECT Nom,Zone,Flag FROM Lieu WHERE ID='$Cible'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav-cible');
		$result2=mysqli_query($con,"SELECT Type,Robustesse,Masse,Plafond,Autonomie,ArmePrincipale,ArmeSecondaire,Radar,Navigation,Detection,Train,Radio,Baby FROM $Avion_db WHERE ID='$avion'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav-avion');
		$Pathfinder=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Cible='$Cible' AND Task=2 AND Avion>0 AND Pays='$country' AND Actif=1"),0);
		mysqli_close($con);
		if($resultc)
		{
			while($data=mysqli_fetch_array($resultc,MYSQLI_ASSOC))
			{
				$Cible_Nom=$data['Nom'];
				$Flag=$data['Flag'];
				$Zone=$data['Zone'];
			}
			mysqli_free_result($resultc);
			unset($data);
		}
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Type_avion=$data['Type'];
				$HPmax=$data['Robustesse'];
				$Plafond=$data['Plafond'];
				$Autonomie=$data['Autonomie'];
				$Arme1Avion=$data['ArmePrincipale'];
				$Arme2Avion=$data['ArmeSecondaire'];
				$Radar=$data['Radar'];
				$Navi=$data['Navigation'];
				$Detection=$data['Detection'];
				$Masse=$data['Masse'];
				$Train=$data['Train'];
				$Radio=$data['Radio'];
				$Baby=$data['Baby'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		$Navigation+=($Pathfinder*10);
		if($Slot6 ==23)
			$Navigation*=1.01;
		if($Slot11 ==8 or $Slot11 ==14)
			$Navigation*=1.02;
		elseif($Slot11 ==9)
			$Navigation*=1.05;
		if($PVP and !$Sandbox)
		{
			if(GetData("Duels_Candidats","PlayerID",$PlayerID,"ID"))
			{
				$HP_Ori=$HP;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT PlayerID,Avion FROM Duels_Candidats WHERE Target='$PlayerID'");
				$HP=mysqli_result(mysqli_query($con,"SELECT HP FROM Duels_Candidats WHERE PlayerID='$PlayerID'"),0);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Pilote_eni=$data['PlayerID'];
						$avion_eni=$data['Avion'];
					}
					mysqli_free_result($result);
				}
				if($HP <$HP_Ori)
				{
					$Deg=$HP_Ori-$HP;
					$avion_eni_nom=GetData("Avion","ID",$avion_eni,"Nom");
					$intro.='<p>[PVP] Vous encaissez une rafale tirée par un <b>'.$avion_eni_nom.'</b>! (<b>'.$Deg.'</b> dégâts)</p>';
				}
				if($HP <1)
				{
					//Tableau de chasse
					$Unit_eni=GetData("Pilote","ID",$Pilote_eni,"Unit");
					AddVictoire($Avion_db,$avion,$avion_eni,$Pilote_eni,$Unit_eni,$Unite,$Cible,1,$PlayerID,3,$Nuit,$alt);
					$end_mission=true;
					$chemin=0;
					$Patrol=false;
				}
			}
		}
		if(!$Patrol)
		{
			$_SESSION['PVP']=false;
			if($Pilote_eni)SetData("Duels_Candidats","Target",0,"PlayerID",$Pilote_eni);
			RetireCandidat($PlayerID,"nav");
		}		
		if($HP <1)
		{
			$end_mission=true;
			$VitAvion=0;
			$chemin=0;
		}
		elseif($HP >0)
		{		
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
			if($moda ==0)$moda=10;
			$Plafond=round($Plafond/$moda);
		}
		if($Cible_Atk_Post and !$Chk_Nav)SetData("Pilote","S_Cible_Atk",$Cible_Atk_Post,"ID",$PlayerID);		
		if($Action ==10)//Sauter Parachute
		{
			$Puissance=0;
			$UpdateCourage-=10;
			$UpdateReput-=10;
			$UpdateGrade-=5;
			$_SESSION['Parachute']=true;
			$end_mission=true;
		}
		elseif($Action >0 and !$end_mission) //Retourner à votre base.
		{
			$_SESSION['done']=true;
			$Distance-=$chemin;
			$chemin=$Distance;
			if($Mission_Type ==4 and $Escorteb_nbr >0)
			{
				$intro.="<p>Vous laissez votre escorte sans protection!</p>";
				if(!$Chk_Nav and !$Sandbox)
				{
					$UpdateReput-=2;
					$UpdateGrade-=5;
					//$UpdateTactique-=1;
					$Update_Unit_Reput-=2;
					$Escort_Time=mt_rand(0,10);
					if($Escort_Time <3)
					{
						$intro.='<p>L\'ennemi a profité de votre absence pour descendre un <b>'.$Escorteb_nom.'</b> que vous escortiez !</p>';
						$UpdateMoral-=1;
						$UpdateReput-=1;
						$UpdateGrade-=1;
						$Update_S_Escorteb_nbr-=1;
						if($Escorteb_nbr <1)
						{
							$intro.="<p><b>Toute la formation que vous escortiez a été abattue! Vous échouez dans votre mission !</b><p>";
							SetData("Pilote","S_Mission",3,"ID",$PlayerID); //Passer en mode chasse pour éviter le menu d'escorte
							$UpdateMoral-=10;
							$UpdateReput-=10;
							$UpdateGrade-=10;
							$Update_Unit_Reput-=10;
						}
					}
				}
			}
		}
		/*Meteo
		if(!$Meteo_PJ)
		{
			$meteo_temp=GetMeteo($Saison,$Latitude,$Longitude,0,$Nuit);
			$meteo=$meteo_temp[1];
			unset($meteo_temp);
			SetData("Pilote","S_Meteo",$meteo,"ID",$PlayerID);
		}*/
		//Boost
		if($c_gaz ==130)
			$UpdateStress_Moteur+=10;
		elseif($c_gaz <60 and $alt >6000)
		{
			$alt=mt_rand(4000,6000);
			$intro.="<p>Votre manque de puissance ne vous permet pas de vous maintenir à haute altitude</p>";
		}
		//Veste
		if($alt >10000 and $Slot2 !=21 and $Slot2 !=22)
		{
			$alt=mt_rand(8000,9000);
			$intro.="<p>Vous n'avez pas la tenue adéquate pour voler à très haute altitude.</p>";
		}
		if($alt >5000 and $Slot4 !=27 and $Slot4 !=30 and $Slot4 !=31 and $Slot4 !=32 and $Slot4 !=36 and $Slot4 !=37 and $Slot4 !=38 and $Slot4 !=39 and $Slot4 !=50 and $Slot4 !=51 and $Slot4 !=52)
		{
			$alt=mt_rand(4000,5000);
			$intro.="<p>Vous n'avez pas la tenue adéquate pour voler à haute altitude.</p>";
		}
		if($alt >$Plafond)$alt=$Plafond;
		$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$avion_img=GetAvionImg($Avion_db,$avion);		
		if($Equipage and $Equipage_Nbr >1)
		{
			$con=dbconnecti();
			$resulteq=mysqli_query($con,"SELECT Navigation,Vue FROM Equipage WHERE ID='$Equipage'");
			mysqli_close($con);
			if($resulteq)
			{
				while($data=mysqli_fetch_array($resulteq,MYSQLI_ASSOC))
				{
					$Navig_Eq=$data['Navigation'];
					$Vue_Equipage=$data['Vue'];
				}
				mysqli_free_result($resulteq);
				unset($data);
			}
		}
		if($VitAvion <50 OR $Puissance <1 OR $c_gaz <20 OR $end_mission)
		{
			$intro.="<br>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !";
			$end_mission=true;
			$nav=false;
			$mission2=false;
			$finnav=false;
		}
		elseif($essence <1)
			$panne_seche=true;
		elseif($chemin ==0) //En cas de bombardier continuant sa route			
			$finnav=true;
		else
		{
			$test_timeout=0;
			$echecnav=0;
			if(!$Chk_Nav)
			{
				if($meteo <-9 and !$Sandbox)
				{
					$UpdateNavigation+=1;
					$UpdateMoral+=1;
				}				
				//Refroidissement Armes
				$UpdateStress_Arme1-=1;
				$UpdateStress_Arme2-=1;
			}		 
			//Calcul déplacement selon la vitesse (vitesse et alt max des avions escortés si mission d'escorte)
			if($Mission_Type ==4)
			{
				$PlafondEscorte=GetData("Avion","ID",$Escorteb,"Plafond");
				if($alt >$PlafondEscorte)$alt_esc=$PlafondEscorte;
				$VitEscorte=GetSpeed("Avion",$Escorteb,$alt_esc,$meteo,1,1,100,0);
				if($VitAvion >=$VitEscorte)$VitAvion=$VitEscorte;
				elseif(!$_SESSION['done'])
				{
					$_SESSION['done']=true;
					$finnav=true;
					$chemin=0;
					$img=Afficher_Image('images/avions/landing'.$avion_img.'.jpg', 'images/avions/decollage'.$avion_img.'.jpg', 'Atterrissage');
					$intro.="<p>La formation que vous devez escorter est plus rapide que vous!<br>Vous ne pouvez accomplir votre mission et devez rentrer à votre base.</p>";
				}
				unset($PlafondEscorte);
				unset($VitEscorte);
			}
			//Efficacité Radar
			$Efficacite_radar=0;
			if($Mission_Type <98)
			{
				$Radar_test=true;
				if($Front ==0)
				{
					$Long_ref=-6;
					if($Occcupant ==2)
						$Lat_ref=49;
					else
						$Lat_ref=47;
				}
				elseif($Front ==3)
				{
					$Long_ref=180;
					$Lat_ref=1;
				}
				elseif($Front ==1 or $Front ==4)
				{
					$Long_ref=30;
					$Lat_ref=40;
				}
				else
					$Radar_test=false;
				if($Radar_test)
				{
					$con=dbconnecti();
					$resultrad=mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Pays='$Flag' AND Latitude >'$Lat_ref' AND Longitude >'$Long_ref' AND Radar_Flag='$Flag' AND Radar >50");
					$Vic=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Joueur_win='$PlayerID' AND PVP<>1 AND DATE(Date)=DATE(NOW())"),0);
					mysqli_close($con);
					if($resultrad)
					{
						if($datal=mysqli_fetch_array($resultrad,MYSQLI_NUM))
							$Efficacite_radar=$datal[0]*10;
						if($Efficacite_radar >200)$Efficacite_radar=200;
						mysqli_free_result($resultrad);
					}
				}
			}
			$alt_min=$alt-3000-$meteo-($Efficacite_radar*5);
			$alt_max=$alt+2000+$meteo+($Efficacite_radar*5);	
			while($chemin >0)
			{
				$nav=false;
				$mission2=false;
				$Detect=0;
				if($essence <1)
				{
					$panne_seche=true;
					$chemin=0;
				}
				else
				{
					$Navig=mt_rand(0,$Navigation)+$meteo+($Moral/10)+$Navig_Eq+$Radar+($Navi*50);
					if($echecnav >5 or $Bonne_Etoile)$Navig=1;
					$test_timeout+=1;
					$test_timeout_modulo=$test_timeout/10;//Eviter boucle infinie en cas de mauvaise navigation
					if($Navig >0 and $test_timeout_modulo !=1)
					{
						$route=($VitAvion/25);
						if($route>$Distance)$route=$Distance-1;
						if($route<1)$route=1;
						$chemin=round($chemin-$route);
						if(!$Nuit)
						{
							$conso=$route+abs(round($meteo/10));
							if($conso<1)$conso=1;
							$essence-=$conso;
						}
						else
						{
							$ess_meteo=abs(round(($meteo+85)/10));
							if($ess_meteo<0)$ess_meteo=0;
							$essence-=($route+$ess_meteo);
						}
						if($Admin ==1)
						{
							if($chemin >200)$chemin=50;
							if($essence <500)$essence=500;
						}
						if($essence <1)
						{
							$panne_seche=true;
							$chemin=0;
						}
						//Déplacement de l'avion sur la carte
						$MapPos=GetMapPos($Longitude,$Latitude,$Long_par_km*$route,$Lat_par_km*$route,$SensH,$SensV);				
						$Longitude=$MapPos[0];
						$Latitude=$MapPos[1];
						unset($MapPos);
						if($chemin >0)
						{
							if($Chk_Nav ==99 and $Type_avion !=1 and $Type_avion !=4 and $Type_avion !=12 and $Enis >0)
							{
								$Detect=1;
								$mission2=true;
								break;
							}
							else
							{
								$Interception=false;
								$Rencontre_IA=false;
								$avion_eni=0;
								$Pilote_eni=0;
								$Unit_eni=0;
								$Renc_Nbr=0;
								if($Mission_Type ==103)
								{
									$Pilote_eni=3297;
									$Unit_eni=$Unite;
									$Renc_Nbr=1;
									$Detect=1;
									if($country ==1)
										$avion_eni=321;
									elseif($country ==2)
										$avion_eni=11;
									elseif($country ==4)
										$avion_eni=319;
									elseif($country ==6)
										$avion_eni=112;
									elseif($country ==7)
										$avion_eni=325;
									elseif($country ==8)
										$avion_eni=202;
									elseif($country ==9)
										$avion_eni=328;
									else
										$avion_eni=$avion;
									$random_alt=mt_rand(1000,GetData("Avion","ID",$avion_eni,"Plafond"));
								}
								else
								{
									if((($Mission_Type <90 and $Vic <10) or $Mission_Type ==9))
									{
										//Interception des formations offensives à -100km de l'objectif
										$chk_chemin=10+mt_rand(0,100);
										if(!$_SESSION['done'] and $chemin <$chk_chemin and $Cible and $Vic <10)
										{
											//Si unité en couverture, chance de les rencontrer plus importante
											if($Nuit)
											{
												$queryr="SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture_Nuit='$Cible' AND j.Avion>0 AND j.Actif=1";
												$queryr2="SELECT j.ID,j.Unit,j.Avion,j.Alt FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture_Nuit='$Cible' AND j.Avion>0 AND j.Actif=1 ORDER BY RAND() LIMIT 1";
											}
											elseif($Mission_Type ==7)
											{
												$queryr="SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Escorte='$Cible' AND j.Avion>0 AND j.Actif=1";
												$queryr2="SELECT j.ID,j.Unit,j.Avion,j.Alt FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Escorte='$Cible' AND j.Avion>0 AND j.Actif=1 ORDER BY RAND() LIMIT 1";
											}
											else
											{
												$queryr="SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Avion>0 AND j.Actif=1";
												$queryr2="SELECT j.ID,j.Unit,j.Avion,j.Alt FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Avion>0 AND j.Actif=1 ORDER BY RAND() LIMIT 1";
											}
											$con=dbconnecti();
											$Renc_Nbr=mysqli_result(mysqli_query($con,$queryr),0);
											$resultr2=mysqli_query($con,$queryr2);
											mysqli_close($con);
											if($Renc_Nbr >12)$Renc_Nbr=12;
											if($Renc_Nbr >0)$Rencontre_IA=true;
										}
										elseif($Vic <5)
										{
											if(mt_rand(0,3) ==3)
											{
												if($Nuit)
												{
													$queryr="SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Cible='$Cible' AND j.Couverture=0 AND j.Escorte=0 AND j.Avion>0 AND j.Actif=1";
													$queryr2="SELECT j.ID,j.Unit,j.Avion,j.Alt FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Cible='$Cible' AND j.Couverture=0 AND j.Escorte=0 AND j.Avion>0 AND j.Actif=1 ORDER BY RAND() LIMIT 1";
												}
												elseif($Mission_Type ==7)
												{
													$queryr="SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Cible='$Cible' AND j.Couverture_Nuit=0 AND j.Avion>0 AND j.Actif=1";
													$queryr2="SELECT j.ID,j.Unit,j.Avion,j.Alt FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Cible='$Cible' AND j.Couverture_Nuit=0 AND j.Avion>0 AND j.Actif=1 ORDER BY RAND() LIMIT 1";
												}
												else
												{
													$queryr="SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Cible='$Cible' AND j.Couverture_Nuit=0 AND j.Couverture=0 AND j.Escorte=0 AND j.Avion>0 AND j.Actif=1";
													$queryr2="SELECT j.ID,j.Unit,j.Avion,j.Alt FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Cible='$Cible' AND j.Couverture_Nuit=0 AND j.Couverture=0 AND j.Escorte=0 AND j.Avion>0 AND j.Actif=1 ORDER BY RAND() LIMIT 1";
												}
												$con=dbconnecti();
												$Renc_Nbr=mysqli_result(mysqli_query($con,$queryr),0);
												$resultr2=mysqli_query($con,$queryr2);
												mysqli_close($con);
												if($Renc_Nbr >0)
												{
													$Renc_Nbr=mt_rand(1,$Renc_Nbr);
													if($Renc_Nbr >12)$Renc_Nbr=12;
													$Rencontre_IA=true;
												}
											}
										}
									}//Anti-GB
									if($Rencontre_IA and $Renc_Nbr >0)
									{
										if($resultr2)
										{
											while($data=mysqli_fetch_array($resultr2,MYSQLI_ASSOC))
											{
												$Pilote_eni=$data['ID'];
												$avion_eni=$data['Avion'];
												$Unit_eni=$data['Unit'];
												$random_alt=$data['Alt'];
											}
											mysqli_free_result($resultr2);
										}
										if(!$random_alt)
										{
											if($avion_eni)		
												$random_alt=mt_rand(1000,GetData("Avion","ID",$avion_eni,"Plafond"));
											else
												$random_alt=mt_rand(1000,3000);
										}
										$cardinal=mt_rand(1,8);
										switch($cardinal)
										{
											case 1:
												$direc="u Nord";
											break;
											case 2:
												$direc="u Nord-Est";
											break;
											case 3:
												$direc="e l'Est";
											break;
											case 4:
												$direc="u Sud-Est";
											break;
											case 5:
												$direc="u Sud";
											break;
											case 6:
												$direc="u Sud-Ouest";
											break;
											case 7:
												$direc="e l'Ouest";
											break;
											case 8:
												$direc="u Nord-Ouest";
											break;
										}
									}
									/*if($Admin ==1)
									{
										$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
											<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
											<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
											<tr><th>Avion_eni</th><th colspan='2'>".$avion_eni."</th></tr>
											<tr><th>Renc_Nbr</th><th colspan='2'>".$Renc_Nbr."</th></tr>
											<tr><th>Rencontre_IA</th><th colspan='2'>".$Rencontre_IA."</th></tr>
											Rencontre_IA
										</table>";
										$headers='MIME-Version: 1.0'."\r\n";
										$headers.='Content-type: text/html; charset=utf-8'."\r\n";
										$msg=$toolbar.$intro.$mes.$skills;
										mail('binote@hotmail.com','Aube des Aigles: nav.php Rencontre',$msg,$headers);
									}*/
									//Détection
									$Detection+=$Renc_Nbr;
									if($Mission_Type ==18 or $Mission_Type ==19 or $Mission_Type ==22 or $Mission_Type ==23) //Missions safe
										$Efficacite_radar=0;
									if(($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12) and ($Mission_Type ==3 or $Mission_Type ==7 or $Mission_Type ==17 or $Mission_Type ==26 or $Mission_Type ==31))
									{
										$BonusDetect=$Detection+mt_rand(0,$Vue_Equipage);
										if($avion_eni)
											$Vis_eni=GetVis("Avion",$avion_eni,$Cible,$meteo,$random_alt,$alt);
										else
											$Vis_eni=mt_rand(50,255);
										$Malus_alt=abs(($alt-$random_alt)/50);
										if($Radar)
										{
											$Camouflage_eni=0;
											$intro.="<p>Votre radar est enclenché!</p>";
											/*if($Equipage and $Simu and !$Sandbox)
												UpdateCarac($Equipage,"Radar",1,"Equipage");*/
										}
										else
										{
											if(GetData("Avion","ID",$avion_eni,"Camouflage"))
												$Camouflage_eni=10;
										}
										$Detect=mt_rand(10,$Vue)+$BonusDetect+($meteo*2.5)+($Vis_eni/2)+$Renc_Nbr-$Malus_alt+($Moral/10)+($Radar*20)-$Camouflage_eni+$Oeil_Lynx;
										if($Admin ==1)$skills.='<br>[Score de détection offensif: '.$Detect.']';
									}
									elseif($avion_eni)
									{
										$Vis=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,$random_alt,$PlayerID,$Unite);
										$Radar_eni=GetData("Avion","ID",$avion_eni,"Radar");
										$Malus_alt=abs(($alt-$random_alt)/50);
										$Bonus_Detect=mt_rand(50,250);
										//Malus detect de nuit
										if($Nuit and !$PSV)
										{
											$Bonus_Detect/=10;
											if($Bonus_Detect >50)$Bonus_Detect=50;
										}
										$Detect=$Bonus_Detect+($meteo*2.5)+($Vis/2)-$Malus_alt+($Radar_eni*100)+$Efficacite_radar-($Nuit*100);
										if($Admin ==1)
											$skills.='<br>[Score de détection défensif: '.$Detect.' de avion eni '.$avion_eni.'] : Bonus Compétence='.$Bonus_Detect.' / Météo*2.5='.$meteo.' / Vis/2='.$Vis.' / -Malus_alt='.$Malus_alt.' / Radar_eni*100='.$Radar_eni.' / Efficacite_radar='.$Efficacite_radar.' / -Nuit*100='.$Nuit;
									}
									if($Type_avion !=1 and $Type_avion !=4 and $Type_avion !=12)
									{
										$Type_avioneni=GetData("Avion","ID",$avion_eni,"Type");
										if($Type_avioneni !=1 and $Type_avioneni !=4 and $Type_avioneni !=12)
										{
											$Detect=0;
											$avion_eni=0;
											$Renc_Nbr=0;
											$Unit_eni=0;
											$Pilote_eni=0;
										}
									}
								}
								if($Pilote_eni >0 and $avion_eni >0 and $Unit_eni >0 and $Renc_Nbr >0 and $Detect >0)
								{
									if($Mission_Type !=103)
										$Pays_eni=GetData("Unit","ID",$Unit_eni,"Pays");
									if($Pays_eni !=$country or $Mission_Type ==103)
									{
										$mission2=true;
										break;
									}
								}
								elseif($Nuit)
								{
									$con=dbconnecti();
									$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0 WHERE ID='$PlayerID'");
									mysqli_close($con);
									if($avion_eni and !strpos($intro,'rend impossible toute détection'))
									{
										if($Equipage_Nbr >1)
											$intro.="<p>Votre mitrailleur vous signale qu'il lui semble avoir aperçu quelque chose, mais la nuit rend impossible toute détection.</p>";
										elseif($Leader or $Ailier)
											$intro.="<p>Votre ailier vous signale qu'il lui semble avoir aperçu quelque chose, mais la nuit rend impossible toute détection.</p>";
										else
											$intro.="<p>Il vous semble avoir aperçu quelque chose, mais la nuit rend impossible toute détection..</p>";
									}
								}
								else
								{
									$intro="";
									$con=dbconnecti();
									$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0 WHERE ID='$PlayerID'");
									mysqli_close($con);
								}
							}//Chk_nav
						}
						else
						{
							if(!$panne_seche)
							{
								$intro.="<p>Vous calculez votre route et <b>vous arrivez sur votre objectif</b>.</p>";							
								if(GetData("Lieu","ID",$Unit_Base,"Zone") ==6)
									$img="<img src='images/appontage.jpg' style='width:100%;'>";
								else
									$img="<img src='images/lieu_aerodrome.jpg' style='width:100%;'>";
								$finnav=true;
							}
						}
					}
					else
					{
						if($Navig >0)
							$intro.="<p>Vous volez depuis plusieurs minutes sans rencontrer un chat.</p>";
						else
						{
							if($meteo <-84)
								$perdu="<p>Vous calculez mal votre route et vous vous perdez.</p>";
							/*if($meteo >-4)
								$UpdateNavigation-=1;
							else*/
								$UpdateMoral-=1;
							$echecnav+=1;
							$essence-=1;
						}
						if($echecnav >4 or $Navig >0 or $S_Orient)
						{
							if($echecnav >4)
							{
								//$UpdateNavigation-=1;
								$intro.="<p>Vous calculez mal votre route et vous vous perdez.</p>";
							}
							$nav=true;
							break;
						}
					}//navig
				}//essence_nav
			}//while
			if(!$panne_seche and $chemin <1)$finnav=true;
		}//essence_debut
		if($finnav ==false and $panne_seche ==false and $end_mission ==false and ($Mission_Type ==7 or $Mission_Type ==17 or $Mission_Type ==3 or $Mission_Type ==26 or $Mission_Type ==31 or $Mission_Type ==103))
		{
			if($c_gaz <60)
			{
				SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);
				$intro.="<p>Volant trop lentement pour intercepter quoi que ce soit, vous laissez filer votre proie!</p>";
				$Detect=0;
				$mission2=false;
				$nav=true;
			}
		}
		//***WRITE TO DB***
		if(!$Chk_Nav)
		{
			if(!$Sandbox)
			{
				if($UpdateMoral !=0)
					UpdateCarac($PlayerID,"Moral",$UpdateMoral);
				if($UpdateReput !=0)
					UpdateCarac($PlayerID,"Reputation",$UpdateReput);
				if($UpdateGrade !=0)
					UpdateCarac($PlayerID,"Avancement",$UpdateGrade);
				/*if($UpdateTactique !=0)
					UpdateCarac($PlayerID,"Tactique",$UpdateTactique);
				if($UpdateNavigation !=0)
					UpdateCarac($PlayerID,"Navigation",$UpdateNavigation);*/
				if($Update_S_Escorteb_nbr !=0)
					UpdateCarac($PlayerID,"S_Escorteb_nbr",$Update_S_Escorteb_nbr);
				if($UpdateStress_Moteur !=0)
					UpdateCarac($PlayerID,"Stress_Moteur",$UpdateStress_Moteur);
				if($UpdateStress_Arme1 !=0)
					UpdateCarac($PlayerID,"Stress_Arme1",$UpdateStress_Arme1);
				if($UpdateStress_Arme2 !=0)
					UpdateCarac($PlayerID,"Stress_Arme2",$UpdateStress_Arme2);
				if($Update_Unit_Reput !=0)
					UpdateData("Unit","Reputation",$Update_Unit_Reput,"ID",$Unite,0,1);
			}
			SetData("Pilote","S_HP",$HP,"ID",$PlayerID);			
			//Set Long & Lat
			if(!$SetLongLat)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET S_Longitude='$Longitude',S_Latitude='$Latitude' WHERE ID='$PlayerID'");
				mysqli_close($con);
			}
		}
		//***END WRITE***
		if($finnav and $_SESSION['done'])
		{
			if(!$Nuit)
				$meteo=GetData("Lieu","ID",$Unit_Base,"Meteo");
		}
		$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);		
		SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
		if($panne_seche)
		{
			$nav=false;
			$_SESSION['done']=false;
			$_SESSION['naviguer']=true;
			$intro.="<br>Vous tombez en panne sèche!<br>Vous n'avez pas d'autre choix que d'abandonner votre appareil<br>Vous parvenez à rejoindre vos lignes à grand peine, mais vous êtes en vie!";
			$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg', 'images/avions/crash.jpg', 'crash');
			if(!$Sandbox)
			{
				AddEvent($Avion_db,4,$avion,$PlayerID,$Unite,$Cible);
				UpdateCarac($PlayerID,"Endurance",-1);
				UpdateCarac($PlayerID,"Moral",-10);
				//Avion perso endommagé pour éviter les pannes sèches volontaires et récupérer son avion perso intact
				if($Avion_db =="Avions_Persos")
				{
					$HP-=100;
					if($HP <0)$HP=1;
					SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
					$skills.="<br>Votre avion personnel a été endommagé par cet atterrissage forcé!";
				}
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
			}
			else
				UpdateCarac($PlayerID,"Free",-1);
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='0'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}			
		if($nav)
		{
			if($avion_eni and $Unit_eni)
				$Pays_eni=GetData("Unit","ID",$Unit_eni,"Pays");
			if($Avion_Bombe and $Avion_Bombe_Nbr >0)
				$Alleger="<Input type='Radio' name='Action' value='18'>- Vider la soute pour alléger l'avion.<br>";
			else
				$Alleger="";
			if($S_Baby >0 and $essence <$Autonomie-$S_Baby)
				$Larguer="<Input type='Radio' name='Action' value='19'>- Larguer le réservoir largable pour alléger l'avion.<br>";
			else
				$Larguer="";
			if($_SESSION['done'])
				$move="<Input type='Radio' name='Action' value='0' checked>- Continuer vers votre base.<br>";
			else
				$move="<Input type='Radio' name='Action' value='0' checked>- Continuer vers votre objectif.<br><Input type='Radio' name='Action' value='1'>- Faire demi-tour.<br>";
			$_SESSION['naviguer']=99;
			$titre="En route";
			$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
			$mes.='<form action=\'nav.php\' method=\'post\'>
				<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
				<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
				<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
				<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
				<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
				<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
				<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
				<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
				'.GetSituation($Enis,$avion_eni,$Pays_eni,$Leader,$Ailier,$avion).'
				'.ShowGaz($avion,$c_gaz,$flaps,$alt).
				'<table class=\'table\'><td>'.$move.$Alleger.$Larguer.'</td></tr></table>
				<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}		
		elseif($mission2)
		{
			if(!$Enis)$Enis=$Renc_Nbr;
			if($avion_eni and $Pilote_eni)
			{
				$Nom_avion_eni=GetData("Avion","ID",$avion_eni,"Nom");		
				$Engine_Nbr_Eni=GetData("Avion","ID",$avion_eni,"Engine_Nbr");
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET S_Engine_Nbr_Eni='$Engine_Nbr_Eni',avion_eni='$avion_eni',enis='$Enis',Pilote_eni='$Pilote_eni' WHERE ID='$PlayerID'");
				mysqli_close($con);
			}
										if($Admin ==1)
										{
											$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
												<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
												<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
												<tr><th>Avion_eni</th><th colspan='2'>".$avion_eni."</th></tr>
												<tr><th>Renc_Nbr</th><th colspan='2'>".$Renc_Nbr."</th></tr>
												<tr><th>Rencontre_IA</th><th colspan='2'>".$Rencontre_IA."</th></tr>
												Rencontre_IA
											</table>";
											$headers='MIME-Version: 1.0'."\r\n";
											$headers.='Content-type: text/html; charset=utf-8'."\r\n";
											$msg=$toolbar.$intro.$mes.$skills;
											mail('binote@hotmail.com', 'Aube des Aigles: nav.php Rencontre',$msg,$headers);
										}
			/*if(!$Chk_Nav and $Simu and !$Sandbox)
			{
				if(!$Unit_renc_type)$Unit_renc_type=GetData("Unit","ID",$Unit_eni,"Type");
				if($Unit_renc_type ==1 or $Unit_renc_type ==4 or $Unit_renc_type ==12)
				{
					$con=dbconnecti();
					$Unit_renc_p=mysqli_result(mysqli_query($con,"SELECT Pays FROM Unit WHERE ID='$Unit_eni'"),0);
					$Unit_renc_f=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$Unit_renc_p'"),0);
					mysqli_close($con);					
					if($Unit_eni and $Unit_renc_f !=$Faction)
					{
						//Reput Chasseurs couverture
						if($Nuit)
						{
							$modif=5;
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT j.ID,j.Unit,j.Pays,u.Type FROM Pilote as j,Unit as u WHERE j.Couverture_Nuit='$Cible' AND j.Unit=u.ID");
							mysqli_close($con);
						}
						else
						{
							$modif=1;
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT j.ID,j.Unit,j.Pays,u.Type FROM Pilote as j,Unit as u WHERE j.Couverture='$Cible' AND j.Unit=u.ID AND j.S_alt>'$alt_min' AND j.S_alt<'$alt_max'");
							mysqli_close($con);
						}
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								if($data['ID'] !=$PlayerID and $country !=$data['Pays'])
								{
									if($Cible ==GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Lieu_Mission".$data['Type']))
									{
										$Cdt=GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Commandant");
										if($Cdt)
										{
											UpdateCarac($Cdt,"Reputation",1);
											UpdateCarac($Cdt,"Avancement",1);
										}
										UpdateData("Unit","Reputation",$modif,"ID",$data['Unit']);
									}
									UpdateCarac($data['Unit'],"Missions",$modif*2);
									AddEvent($Avion_db,84,$avion,$PlayerID,$data['Unit'],$Cible,$avion_eni,$data['ID']);
								}
							}
						}
					}
				}
			}*/
			unset($Renc);								
			if($Enis >0 and $avion_eni >0 and $Detect >0)
			{
				if(!$Lieu_eni)$Lieu_eni="e ".$Cible_Nom;
				if($Mission_Type !=103)
				{
					$date=date('Y-m-d G:i');
					$con=dbconnecti(2);
					$addrenc=mysqli_query($con,"INSERT INTO Rencontres (Moment,PlayerID,Lieu,Distance,Meteo,Unit_eni,Pilote_eni,Avion_eni,Renc_nbr,Avion)
					VALUES ('$date','$PlayerID','$Cible','$chemin','$meteo','$Unit_eni','$Pilote_eni','$avion_eni','$Enis','$avion')");
					mysqli_close($con);
				}
				if($Detect <50 and $Enis >1)
					$intro.='<p>Vous détectez <b>plusieurs '.$Nom_avion_eni.'</b> volant en direction d'.$Lieu_eni.' à  environ '.$random_alt.'m d\'altitude.</p>';
				else
				{
					//$car_up=mt_rand(0,1);
					$nbr_eni_det=$Enis;
					if($nbr_eni_det >1)$pluriel="s";
					if($Detect >100)
					{
						$nav_unit_det="<br>L'avion le plus proche semble arborer l'emblême du ".GetData("Unit","ID",$Unit_eni,"Nom");
						$intro.='<p>Vous détectez au moins '.$nbr_eni_det.' avion'.$pluriel.', dont '.$Renc_Nbr.' <b>'.$Nom_avion_eni.'</b> volant en direction d'.$Lieu_eni.' à  environ '.$random_alt.'m d\'altitude.'.$nav_unit_det.'</p>';
						//$car_up=1;
					}
					else
						$intro.='<p>Vous détectez environ '.$nbr_eni_det.' avion'.$pluriel.', dont plusieurs <b>'.$Nom_avion_eni.'</b> volant en direction d'.$Lieu_eni.' à  environ '.$random_alt.'m d\'altitude</p>';
					/*if(!$Chk_Nav and $Simu and !$Sandbox)
						UpdateCarac($PlayerID,"Vue",$car_up);*/
				}
				if(!$img)$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';
				if($random_alt >$alt)
					$choix1="<Input type='Radio' name='Action' value='5'>- Tenter de vous approcher en grimpant.<br>";
				elseif($alt >$random_alt)
					$choix1="<Input type='Radio' name='Action' value='6'>- Tenter de vous approcher en bénéficiant de votre avantage en altitude.<br>";
				else
					$choix1="<Input type='Radio' name='Action' value='6'>- Tenter de vous approcher.<br>";
				$Situation_avions=GetSituation($nbr_eni_det,$avion_eni,GetData("Unit","ID",$Unit_eni,"Pays"),$Leader,$Ailier,$avion);
			}
			else
			{
				$Situation_avions="";
				if($Equipage_Nbr >1)
					$intro.="<p>Votre mitrailleur vous signale qu'il lui semble avoir vu quelque chose au loin.</p>";
				elseif($Leader)
					$intro.="<p>Votre ailier vous signale qu'il lui semble avoir vu quelque chose au loin.</p>";
				else
					$intro.="<p>Il vous semble avoir vu quelque chose au loin.</p>";
				//$skills.="<br>[Score de détection: ".$Detect." (Détection: ".$Vue."),(Malus Météo: ".$meteo.")]";
				$img='<img src=\'images/ciel'.$meteo.'.jpg\' style=\'width:100%;\'>';
				$choix1="<Input type='Radio' name='Action' value='6'>- Tenter de vous approcher.<br>";
			}			
			if(IsAilier($PlayerID,$Leader))
				$choix2="<Input type='Radio' name='Action' value='2'>- Suivre les consignes de votre leader<br>";
			elseif($Mission_Type !=103)
			{
				if($Mission_Type ==4)
					$choix2="<Input type='Radio' name='Action' value='2'>- Protéger les bombardiers<br>";
				else
					$choix2="<Input type='Radio' name='Action' value='2'>- Ignorer et suivre votre plan de vol<br>";
			}
			if(!$_SESSION['done'])
			{
				$choix_retour="<Input type='Radio' name='Action' value='4'>- Faire demi-tour et rentrer.<br>";
				$choix4="Ignorer et continuer votre route vers votre objectif";
				$choix6="Continuer vers votre objectif en prenant des risques à très basse altitude";
				$choix11="";
			}
			else
			{
				$choix_retour="";
				$choix4="Rentrer à votre base";
				$choix6="Rentrer à votre base en prenant des risques à très basse altitude";
				if(!$Escorte_nbr and $Mission_Type !=9 and $Mission_Type !=103)
				{
					if(($Radio ==2 and $chemin <101) or ($Radio ==1 and $chemin <51))
					{
						$con=dbconnecti();
						$Esc_radio_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Couverture='$Unit_Base' AND p.Faction='$Faction'"),0);
						mysqli_close($con);
						if($Esc_radio_Nbr)
							$choix11="<Input type='Radio' name='Action' value='11'>- Demander l'aide de la chasse par radio.<br>";
					}
				}
			}
			if($Type_avion !=1 and $Type_avion !=4 and $Type_avion !=12)$choix1="";
			$_SESSION['naviguer']=true;
			$titre="Rencontre";
			$mes.='<form action=\'mission2.php\' method=\'post\'>
				<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
				<input type=\'hidden\' name=\'Avioneni\' value='.$avion_eni.'>
				<input type=\'hidden\' name=\'Alt_avioneni\' value='.$random_alt.'>
				<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>	
				<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
				<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
				<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
				<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
				<input type=\'hidden\' name=\'Enis\' value='.$Enis.'>
				<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
				<input type=\'hidden\' name=\'Unit_eni\' value='.$Unit_eni.'>
				'.$Situation_avions.'
				'.ShowGaz($avion,$c_gaz,$flaps,$alt,3).'
				<table class=\'table\'><td align=\'left\'>'.$choix1.$choix2.'
					<Input type=\'Radio\' name=\'Action\' value=\'3\' checked>- '.$choix4.'.<br>
					<Input type=\'Radio\' name=\'Action\' value=\'10\'>- '.$choix6.'.<br>
					'.$choix_retour.'</td></tr></table>
				<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
			if($Admin ==1)
			{
				$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
					<tr><th>Pilote IA</th><th colspan='2'>".$Pilote_eni."</th></tr>
					<tr><th>Unit_eni</th><th colspan='2'>".$Unit_eni."</th></tr>
					<tr><th>Unit_renc</th><th colspan='2'>".$Unit_eni."</th></tr>
					<tr><th>Avion_eni</th><th colspan='2'>".$avion_eni."</th></tr>
					<tr><th>Enis</th><th colspan='2'>".$Enis."</th></tr>
					<tr><th>Interception_PJ</th><th colspan='2'>".$Interception_PJ."</th></tr>
					<tr><th>Rencontre_IA</th><th colspan='2'>".$Rencontre_IA."</th></tr>
					Rencontre_IA
				</table>";
				$headers='MIME-Version: 1.0'."\r\n";
				$headers.='Content-type: text/html; charset=utf-8'."\r\n";
				$msg=$toolbar.$intro.$mes.$skills;
				mail('binote@hotmail.com', 'Aube des Aigles: nav.php Rencontre',$msg,$headers);
			}
		}
	}	
	if($end_mission){
		$NoAddVic=true;
		$finnav=false;
		include_once('./end_mission.php');
	}	
	if($finnav)
	{		
		if($_SESSION['done'])
		{
			if($Simu and !$Sandbox and (!$Chk_Nav or $Chk_Nav ==99))
			{
				if(($Mission_Type ==18 or $Mission_Type ==19 or $Mission_Type ==22) and $_SESSION['mia_status'] ==2)
				{
					$intro.="<p><b>Vous avez accompli la mission et ramené le pilote abattu à bon port!</b></p>";
					UpdateCarac($PlayerID,"Avancement",50);
					UpdateCarac($PlayerID,"Reputation",50);
					UpdateCarac($PlayerID,"Moral",20);
					UpdateData("Unit","Reputation",50,"ID",$Unite,0,10);
					UpdateCarac($PlayerID,"Missions",50);
					$_SESSION['mia_status']=false;
				}
				/*elseif($Mission_Type ==9)
				{
					$avion_eni=$_SESSION['Escorte_avioneni'];
					$Intercept_nbr=GetData("Pilote","ID",$PlayerID,"S_Intercept_nbr");
					$Unit_eni=GetData("Pilote","ID",$PlayerID,"S_Unite_Intercept");
					if(!$Intercept_nbr)
					{
						$Intercept_nbr=$_SESSION['Escorte_eni_Nbr'];
						$avion_eni=$_SESSION['Escorte_eni_avion_eni'];
						$Unit_eni=$_SESSION['Escorte_eni_Unit_eni'];
					}
					if($Enis <$Intercept_nbr and $avion_eni and $Unit_eni)
					{
						$Pts_Int=$Intercept_nbr-$Enis;
						if($Equipage and $Equipage_Nbr >1)
						{
							UpdateCarac($Equipage,"Missions",1,"Equipage");
							UpdateCarac($Equipage,"Avancement",$Pts_Int,"Equipage");
							UpdateCarac($Equipage,"Reputation",$Pts_Int,"Equipage");
						}
						$intro .="<p><b>Vous avez accompli avec succès la mission qui vous a été assignée.</b></p>";
						UpdateCarac($PlayerID,"Missions",$Pts_Int);
						UpdateCarac($PlayerID,"Avancement",$Pts_Int);
						UpdateCarac($PlayerID,"Reputation",$Pts_Int);
						UpdateCarac($PlayerID,"Moral",$Pts_Int);
						UpdateData("Unit","Reputation",$Pts_Int,"ID",$Unite);
						AddIntercept($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible);
						//Couverture
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT Couverture,Escorte FROM Pilote WHERE ID='$PlayerID'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Couv=$data['Couverture'];
								$Escorte_PJ=$data['Escorte'];
							}
							mysqli_free_result($result);
							unset($data);
						}
						if(!$Couv and !$Escorte_PJ)
						{
							$intro.="<br>Vous apportez votre soutien à la couverture de votre aérodrome.";
							SetData("Pilote","Couverture",$Cible,"ID",$PlayerID);
							SetData("Pilote","Intercept",1,"ID",$PlayerID);
							$date=date('Y-m-d G:i');
							if($Avion_db =="Avions_Persos")
								$avionp=GetData($Avion_db,"ID",$avion,"ID_ref");
							else
								$avionp=$avion;
							$query="REPLACE INTO Patrouille_live (Type, Avion, Joueur, Unite, Lieu, Altitude, Cycle, Date)
							VALUES (2,'$avionp','$PlayerID','$Unite','$Cible','$alt','$Nuit','$date')";
							$con=dbconnecti();
							$ok=mysqli_query($con,$query);
							mysqli_close($con);
							if(!$ok)
							{
								$msg.="Erreur de mise à jour ".mysqli_error($con);
								mail('binote@hotmail.com','Aube des Aigles: AddEscorte Live Error',$msg);
							}
						}
					}
					else
					{
						$intro.="<p><b>Vous n'avez pas accompli la mission qui vous a été assignée!</b></p>";
						UpdateCarac($PlayerID,"Avancement",-10);
						UpdateCarac($PlayerID,"Reputation",-10);
						UpdateCarac($PlayerID,"Moral",-5);
						UpdateData("Unit","Reputation",-10,"ID",$Unite);
					}
				}
				/*elseif($Mission_Type ==14)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Longitude,Latitude FROM Lieu WHERE ID='$Cible'");
					mysqli_close($con);
					if($result)
					{
						if($data=mysqli_fetch_array($result))
						{
							$Long_Cible=$data['Longitude'];
							$Lat_Cible=$data['Latitude'];
						}
						mysqli_free_result($result);
					}
					if($Escorteb_nbr >0)
					{
						$intro.="<p><b>Vous avez accompli avec succès la mission qui vous a été assignée.</b></p>";
						UpdateCarac($PlayerID,"Missions",$Escorteb_nbr);
						UpdateCarac($PlayerID,"Avancement",$Escorteb_nbr);
						UpdateCarac($PlayerID,"Reputation",$Escorteb_nbr);
						UpdateCarac($PlayerID,"Moral",$Escorteb_nbr);
						AddEscorte($Avion_db,$avion,$PlayerID,$Cible,0,$Escorteb_nbr,$Unite,$alt,$Nuit);
						$con=dbconnecti();
						$resultl=mysqli_query($con,"SELECT ID,Longitude,Latitude FROM Lieu WHERE Flag='$country'");
						mysqli_close($con);
						if($resultl)
						{
							while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
							{
								if(GetDistance(0,0,$Long_Cible,$Lat_Cible,$datal['Longitude'],$datal['Latitude']) <1000)
								{
									UpdateData("Lieu","Citernes",-5,"ID",$datal['ID']);
									UpdateData("Lieu","Camions",-5,"ID",$datal['ID']);
								}
							}
							mysqli_free_result($resultl);
						}
					}
					else
					{
						$intro.="<p><b>Vous n'avez pas accompli la mission qui vous a été assignée!</b></p>";
						UpdateCarac($PlayerID,"Avancement",-10);
						UpdateCarac($PlayerID,"Reputation",-10);
						UpdateCarac($PlayerID,"Moral",-5);
						UpdateData("Unit","Reputation",-10,"ID",$Unite);						
						$con=dbconnecti();
						$resultl=mysqli_query($con,"SELECT ID,Longitude,Latitude FROM Lieu WHERE Flag='$country'");
						mysqli_close($con);
						if($resultl)
						{
							while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
							{
								if(GetDistance(0,0,$Long_Cible,$Lat_Cible,$datal['Longitude'],$datal['Latitude']) <1000)
								{
									UpdateData("Lieu","Citernes",5,"ID",$datal['ID']);
									UpdateData("Lieu","Camions",5,"ID",$datal['ID']);
								}
							}
							mysqli_free_result($resultl);
						}
					}
				}*/
			}
			$con=dbconnecti();
			$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Avion=0 WHERE Unit='$Unite' AND Escorte=0 AND Couverture=0 AND Couverture_Nuit=0 AND Task=0");
			mysqli_close($con);
			if(!$img)$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$avion_img);
			if(!$intro)$intro='Vous arrivez à destination.';
			$_SESSION['naviguer']=true;
			if(GetData("Lieu","ID",$Unit_Base,"Tour"))
			{
				$Vit_mini_t=round((100+sqrt($Masse))-($Pilotage/10));
				$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,$Sandbox,$Pilotage);
				$Vit_mini=round(((100+sqrt($Masse))*0.7)-($Pilotage/10));
				if($Vit_mini_t >245)$Vit_mini_t=245;
				if($Vit_mini >245)$Vit_mini=245;
				$intro.="<p>La tour vous communique à la radio que la vitesse minimale d'atterrissage conseillée de votre appareil, sans volets, est de ".$Vit_mini_t."<br>La vitesse avec 3 crans de volets est de ".$Vit_mini."</p>";
			}
			$titre="Atterrissage <a href='help/aide_landing.php' target='_blank' title='Aide atterrissage'><img src='images/help.png'></a>";
			$mes.='<form action=\'landing.php\' method=\'post\'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>	
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>	
			<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,7).'
			<input type=\'submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'> <a href=\'help/aide_landing.php\' target=\'_blank\' title=\'Aide atterrissage\'><img src=\'images/help.png\'></a></form>';
		}
		else
		{
			//PvP
			$choix96='';
			$choix97='';
			$choix98='';
			$choix99='';
			$PvP_Engage=false;
            $con=dbconnecti();
			if($Type_avion!=8 and !$Sandbox)
			{
				AddCandidat($Avion_db,$PlayerID,$avion,$HP,$Puissance,$essence,$chemin,$Distance,$Mun1,$Mun2,$alt,$Cible,$Nuit);
				$result=mysqli_query($con,"SELECT PlayerID,Avion,Altitude,Target FROM Duels_Candidats WHERE Lieu='$Cible' AND PlayerID<>'$PlayerID' AND Country<>'$country' AND Cycle='$Nuit'");
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$PvP_ID=$data['PlayerID'];
						$BonusDetect=$Detection+mt_rand(0,$Vue_Equipage);
						$Vis_eni=GetVis("Avion", $data['Avion'],$Cible,$meteo,$data['Altitude'],$alt);
						$Malus_alt=abs(($alt-$data['Altitude'])/100);
						$Radar=GetData($Avion_db,"ID",$avion,"Radar");
						$Tactique_PvP=GetData("Pilote","ID",$PvP_ID,"Tactique");
						$Detect=mt_rand(10,$Vue)+$BonusDetect+($meteo*2)+($Vis_eni/2)-$Malus_alt+$Radar-($Tactique_PvP/10);
						//Si ennemi, l'avion du PJ ne pourra pas attaquer l'objectif au sol
						if((IsAxe($PlayerID) and IsAllie($PvP_ID)) or (IsAxe($PvP_ID) and IsAllie($PlayerID)))
							$PvP_Engage=true;
						if($Detect >0 or $data['Target'] ==$PlayerID)
						{
							/*if(!$Chk_Nav and $PvP_Engage)UpdateCarac($PlayerID,"Vue",1);*/
							$Target=GetData("Duels_Candidats","PlayerID",$data['Target'],"Avion");
							$Avion_img='images/avions/avion'.$data['Avion'].'.gif';
							$Target_img='images/avions/avion'.$Target.'.gif';
							if($Target)
								$choix99.="<Input type='Radio' name='Action' value='99".$PvP_ID."'>- Se rapprocher du <img src='".$Avion_img."' title='Avion'> combattant un <img src='".$Target_img."' title='Avion'> volant à ".$data['Altitude']."m<br>";
							else
								$choix99.="<Input type='Radio' name='Action' value='99".$PvP_ID."'>- Se rapprocher du <img src='".$Avion_img."' title='Avion'> volant à ".$data['Altitude']."m<br>";
						}
						if($Admin ==1)$skills.='<br> Détection : '.$Detect;
					}
					mysqli_free_result($result);					
				}
				else
				{
					$intro.='<p>Aucun avion ennemi ne semble être dans les environs</p>';
					mail('binote@hotmail.com','Aube des Aigles: GetCandidat',"Joueur : ".$PlayerID." / Cible : ".$Cible);
					$choix99='';
				}
				$choix96="<Input type='Radio' name='Action' value='96' title='".GetMes('Aide_PVP')."'>- Survoler la zone en scrutant le ciel (PvP)<br>";
				/*$choix97="<Input type='Radio' name='Action' value='97' title='".GetMes('Aide_PVP')."'>- Survoler la zone en scrutant le ciel, à basse altitude (PvP)<br>";
				$choix98="<Input type='Radio' name='Action' value='98' title='".GetMes('Aide_PVP')."'>- Survoler la zone en scrutant le ciel (PvP)<br>";*/
			}
			//Météo
			$result=mysqli_query($con,"SELECT Meteo,Meteo_Hour FROM Lieu WHERE ID='$Cible'");
			if($result)
			{
				if($data=mysqli_fetch_array($result))
				{
					$Previsions=$data['Meteo'];
					$Previsions_Hour=$data['Meteo_Hour'];
				}
				mysqli_free_result($result);
			}
            $today=getdate();
			if(!$Previsions or ($today['hours'] >$Previsions_Hour+2))
			{
				$meteoo=GetMeteo($Saison,$Latitude,$Longitude,0,$Nuit);
				$meteo=$meteoo[1];
				$Previsions_txt=$meteoo[0];
				$setmeteo=mysqli_query($con,"UPDATE Lieu SET Meteo='".$meteo."',Meteo_Hour='".$today['hours']."' WHERE ID='".$Cible."'");
				unset($meteoo);
			}
			else
			{
				switch($Previsions)
				{
					case 0:
						$MeteoEffect="temps clair, vent nul";
					break;
					case -100:
						$MeteoEffect="tornade";
					break;
					case -5:
						$MeteoEffect="temps clair, vent faible";
					break;
					case -10:
						$MeteoEffect="nuageux, vent faible";
					break;
					case -20:
						$MeteoEffect="pluie, vent faible";
					break;
					case -75:
						$MeteoEffect="tempête";
					break;
					case -70:
						$MeteoEffect="vent cisaillant";
					break;
					case -50:
						$MeteoEffect="neige, vent faible";
					break;
					default :
						$MeteoEffect="temps clair, vent nul";
					break;
				}
				$Previsions_txt=$MeteoEffect;
			}
			$intro.='<br>La météo au-dessus de votre objectif est : <b>'.$Previsions_txt.'</b>';
			if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
				$img='<img src=\'images/lieu/lieu'.$Cible.'.jpg\' style=\'width:100%;\'>';
			else
			{
				$Map=GetData("Lieu","ID",$Cible,"Map");
				if($Nuit)
					$img='<img src=\'images/lieu/objectif_nuit'.$Map.'.jpg\' style=\'width:100%;\'>';
				else
					$img='<img src=\'images/lieu/objectif'.$Map.'.jpg\' style=\'width:100%;\'>';
			}
			$choix2="<Input type='Radio' name='Action' value='2'>- Annuler la mission.<br>";
			$choix3='';
			$choix4='';
			switch($Mission_Type)
			{
				//Appui
				case 1: case 2:
					$choix1="Chercher des cibles au sol.";
				break;
				//Chasse
				case 3: case 7: case 9: case 17: case 26:
					$choix1="Patrouiller dans la zone.";
				break;
				//Escorte
				case 4:
					$choix1="Attendre que les avions escortés accomplissent leur mission.";
				break;
				//Recce, Atk, Infiltration
				case 5: case 6: case 15: case 21: case 28:
					$choix1="Tenter de repérer l'objectif.";
				break;
				//Bomb
				case 8: case 16:
					$choix1="Bombarder votre objectif.";
				break;
				//Navires
				case 11: case 12: case 13:
					$choix1="Tenter de repérer le navire.";
				break;
				/*Convoi
				case 14:
					$choix1="Tenter de repérer le convoi.";
				break;*/
				//Atk
				case 18: case 19: case 22:
					$choix1="Tenter de repérer le pilote abattu.";
				break;
				//Ravit
				case 23:
					$choix1="Ravitailler l'unité amie.";
				break;
				//Parachutage
				case 24: case 25: case 27: case 14:
					$choix1="Repérer la zone de largage.";
				break;
				//ASM
				case 28:
					$choix1="Tenter de repérer un sous-marin.";
				break;
				//Training
				case 98:
					if($Pilotage < 30)
						$choix1="S'exercer aux manoeuvres de base.";
					else
						$choix1="S'exercer aux manoeuvres de combat.";
				break;
				case 99:
					$choix1="Faire quelques acrobaties.";
				break;
				case 100:
					$choix1="S'entrainer au tir.";
				break;
				case 101:
					$choix1="S'entrainer au largage de bombe.";
				break;
				case 102:
					$choix1="S'entrainer à la navigation.";
				break;
				case 103:
					$choix1="Signaler à la tour que votre simulation de combat est terminée.";
				break;
				default:
					$choix1="Effectuer votre mission.";
				break;
			}
			$_SESSION['naviguer']=true;
			//Si Zone occupée par l'ennemi, pas de mission accomplie possible
			if($PvP_Engage)
				$choix1="<Input type='Radio' name='Action' value='98' checked>- Survoler la zone en attendant qu'une opportunité se présente<br>";
			else
				$choix1='<Input type=\'Radio\' name=\'Action\' value=\'1\' checked>- '.$choix1.'<br>';
			$titre="Arrivée sur l'objectif";
			$mes.='<form action=\'objectif.php\' method=\'post\'>
			<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
			<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>	
			<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>	
			<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
			<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
			<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
			<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt).'
			<table class=\'table\'><tr><td>'.$choix1.$choix2.$choix3.$choix4.$choix6.'
						<Input type=\'Radio\' name=\'Action\' value=\'3\'>- Revenir à la base.<br>
						'.$choix96.$choix97.$choix98.$choix99.'</td></tr></table>
			<input type=\'submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}
	}
	else
	{
		if(!$mes)$mes="Résultat inattendu, veuillez signaler ce problème sur le forum!";
		if(!$img)$img=Afficher_Image('images/inattendu.jpg','images/inattendu.jpg','Problème');
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
unset($finnav);
unset($echecnav);
unset($nav_maxrenc);
unset($nav_renc);
if($Admin ==1)
{
	$skills.='<br>'. memory_get_usage().'/'.memory_get_peak_usage().'<br>';
	//GetData Enis	PVP	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT PlayerID,Lieu,Avion FROM Duels_Candidats WHERE PlayerID<>'$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$En_PlayerID=$data['PlayerID'];
			$En_Lieu=$data['Lieu'];
			$En_Avion=$data['Avion'];
			$skills.="<p>".GetData("Pilote","ID",$En_PlayerID,"Nom")." ( ".GetData("Avion","ID",$En_Avion,"Nom")." ) : ".GetData("Lieu","ID",$En_Lieu,"Nom")."</p>";
		}
		mysqli_free_result($result);
		unset($data);
	}
}
/*Time
$time=microtime();
$time=explode(' ',$time);
$time=$time[1]+$time[0];
$finish=$time;
$total_time=round(($finish-$start),4);
$skills.='<br>Page generated in '.$total_time.' seconds.';*/
usleep(1);
include_once('./index.php');