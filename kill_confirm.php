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
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $_SESSION['kill_confirm'] ==false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_rencontre.inc.php');
	$_SESSION['finish']=false;
	$_SESSION['tirer']=false;
	$_SESSION['missiondeux']=false;
	$_SESSION['missiontrois']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['evader']=false;
	$_SESSION['kill_confirm']=true;	
	$PVP=$_SESSION['PVP'];
	$country=$_SESSION['country'];	
	$panne_seche=false;
	$end_mission=false;
	$nav=false;
	$confirmee=false;
	$continue_eni=false;	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Ailier,S_Ailier,Pilotage,Tactique,Moral,S_Avion_db,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,S_Baby,
	S_Nuit,S_Cible,S_Mission,S_Longitude,S_Latitude,S_Escorte_nbr,S_Escorteb_nbr,S_Leader,S_Equipage_Nbr,S_Formation,Simu,Sandbox FROM Pilote WHERE ID='$PlayerID'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : confirm-player');
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
			$Tactique=$data['Tactique'];
			$Moral=$data['Moral'];
			$Avion_db=$data['S_Avion_db'];
			$Nuit=$data['S_Nuit'];
			$Cible=$data['S_Cible'];
			$Mission_Type=$data['S_Mission'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Escorte_nbr=$data['S_Escorte_nbr'];
			$Escorteb_nbr=$data['S_Escorteb_nbr'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Formation=$data['S_Formation'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Avion_Bombe_Nbr=$data['S_Avion_Bombe_Nbr'];
			$S_Baby=$data['S_Baby'];
			$Leader=$data['S_Leader'];
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
	if($Tactique >50)$Tactique=50;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(50,$Skills_Pil))
			$Bonne_Etoile=true;
		if(in_array(78,$Skills_Pil))
			$Discipline_fer=true;
	}
	if($Equipage)$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");		
	function GetVicPoints($Target)
	{
		switch($Target)
		{
			case 1:
				$Points=40;
			break;
			case 2:
				$Points=30;
			break;
			case 3:
				$Points=20;
			break;
			case 4:
				$Points=30;
			break;
			case 5:
				$Points=30;
			break;
			case 6:
				$Points=10;
			break;
			case 7:
				$Points=20;
			break;
			case 8:
				$Points=5;
			break;
			case 9:
				$Points=20;
			break;
			case 10:
				$Points=20;
			break;
			case 11:
				$Points=40;
			break;
			case 12:
				$Points=30;
			break;
		}
		return $Points;
	}	
	if($PVP and !$Sandbox)
	{
		$Pilote_db="Pilote";
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
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0 WHERE ID='$PlayerID'");
			mysqli_close($con);
			RetireCandidat($PlayerID,"nav");
			$Enis=0;
		}
	}
	else
		$Pilote_db="Pilote_IA";
	$avion_img=GetAvionImg($Avion_db,$avion);	
	//Boost
	if($c_gaz ==130)
		UpdateData("Pilote","Stress_Moteur",10,"ID",$PlayerID);	
	if($essence <1)
		$panne_seche=true;
	elseif($PVP and $HP <1)
	{
		$intro.="<p>Une rafale transforme votre appareil en passoire, ne vous laissant pas d'autre choix que de sauter en parachute!</p>";
		$end_mission=true;
		$_SESSION['Parachute'] =true;
	}
	else
	{
		$Faction=GetData("Pays","ID",$country,"Faction");
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Puissance,Masse,Robustesse,ArmePrincipale,ArmeSecondaire,Plafond,Type,Autonomie,Baby,Engine FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : confirm-avion');
		$result2=mysqli_query($con,"SELECT Nom,Type,Robustesse,ArmePrincipale,Plafond,Engine FROM $Avion_db_eni WHERE ID='$avion_eni'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : confirm-avioneni');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Puissance_nominale=$data['Puissance'];
				$HPmax=$data['Robustesse'];
				$Arme1Avion=$data['ArmePrincipale'];
				$Arme2Avion=$data['ArmeSecondaire'];
				$Plafond=$data['Plafond'];
				$Type_avion=$data['Type'];
				$Masse=$data['Masse'];
				$Autonomie=$data['Autonomie'];
				$Baby=$data['Baby'];
				$Engine=$data['Engine'];
			}
			mysqli_free_result($result);
		}
		//GetData Avion_eni
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Nom_eni=$data['Nom'];
				$Type_avioneni=$data['Type'];
				$HP_eni=$data['Robustesse'];
				$Arme1Avion_eni=$data['ArmePrincipale'];
				$Plafond_eni=$data['Plafond'];
				$Engine_eni=$data['Engine'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,$Sandbox,$Pilotage);
		//Malus avion touché
		$moda=$HPmax/$HP;
		if($Avion_db =="Avion" and $Avion_Bombe_Nbr >0 and $Avion_Bombe !=30)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_Nbr));
			$moda*=(1+$charge_sup);
		}
		elseif($Avion_db =="Avions_Persos" and $Baby and !$S_Baby)
		{
			$charge_sup=1.1/($Masse/$Baby);
			$moda*=(1-$charge_sup);
		}
		$PuissAvion=$Puissance*$moda;
		$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$VitAvioneni=GetSpeed($Avion_db_eni,$avion_eni,$alt,$meteo,1);
		//Pilotage eni
		if($Pilote_eni)
		{
			$Pilotage_eni=GetData($Pilote_db,"ID",$Pilote_eni,"Pilotage");
			$Tactique_eni=GetData($Pilote_db,"ID",$Pilote_eni,"Tactique");
		}
		else
		{
			$Pilotage_eni=mt_rand(50,200);
			$Tactique_eni=mt_rand(50,200);
		}
		//Si avion trop abimé, il s'écrase au sol
		if(($VitAvion <20) or ($PuissAvion <1))
			$Action=98;
		//Consommation
		$Conso=($Puissance_nominale*$c_gaz/100)/500;
		$Enis-=1;
		UpdateData("Pilote","enis",-1,"ID",$PlayerID);
		switch($Action)
		{
			case 1:
				//Poursuivre l'avion au sol pour confirmer
				if($Mission_Type ==103)
				{
					$intro.="Votre adversaire bat des ailes en signe de défaite!";
					$confirmee=true;
				}
				else
				{
					$Plafond=round($Plafond/$moda);
					$alt=SetAlt($alt,$Plafond,$Plafond_eni,-2000,-1000,$c_gaz);
					if($alt <100)$alt=100;
					$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
					$essence-=(10+$Conso);
					if($Type_avion !=1 and $Type_avion !=4 and $Type_avion !=12)
					{
						if(mt_rand(0,10) >9)
							$confirmee=true;
						elseif($Formation >0)
						{
							$intro.="Un avion de votre formation confirme la victoire!";
							$con=dbconnecti();
							$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Victoires=Victoires+1 WHERE Unit='$Unite' AND Cible='$Cible' ORDER BY RAND() LIMIT 1");
							mysqli_close($con);
						}
					}
					elseif($Mission_Type ==4 or $Mission_Type ==7 or $Mission_Type ==17 or $Mission_Type ==26)
						$confirmee=true;
					if($Enis >0 and $Mission_Type !=3 and $Mission_Type !=4 and $Mission_Type !=26)
					{
						$intro.="<p><b>Vous oubliez votre mission!</b></p>";
						UpdateCarac($PlayerID,"Avancement",-5);
						UpdateCarac($PlayerID,"Reputation",-2);
						//UpdateCarac($PlayerID,"Tactique",-1);
						SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);
					}	
					elseif($Enis >0 and $Mission_Type ==4 and $Escorteb_nbr >0)
					{
						$intro.="<p><b>Vous laissez votre escorte sans protection!</b></p>";
						UpdateCarac($PlayerID,"Avancement",-5);
						UpdateCarac($PlayerID,"Reputation",-2);
						//UpdateCarac($PlayerID,"Tactique",-1);
						$Escort_Time=mt_rand(0,10);
						if($Escort_Time <3)
						{
							$intro.='<p>L\'ennemi a profité de votre absence pour descendre un <b>'.$Escorteb_nom.'</b> que vous escortiez !</p>';
							UpdateCarac($PlayerID,"Moral",-1);
							UpdateCarac($PlayerID,"Reputation",-1);
							UpdateCarac($PlayerID,"Avancement",-1);
							$Escorteb_nbr-=1;
							if($Escorteb_nbr <1 and $_SESSION['done'] ==false)
							{
								$intro.="<p><b>Toute la formation que vous escortiez a été abattue! Vous échouez dans votre mission !</b><p>";
								$_SESSION['done'] =true;
								$chemin=$Distance;
								$Mission_Type=3; //Passer en mode chasse pour éviter le menu d'escorte
								UpdateCarac($PlayerID,"Moral",-10);
								UpdateCarac($PlayerID,"Reputation",-10);
								UpdateCarac($PlayerID,"Avancement",-10);
								UpdateData("Unit","Reputation",-10,"ID",$Unite,0,2);						
							}
						}
					}
					if($Enis >0)
					{
						if(GetFlee($PlayerID,$Pilote_eni))
						{
							$intro.="<p>Votre adversaire s'enfuit, ne se sentant pas de taille.</p>";
							$nav=true;
						}
						elseif($Type_avioneni ==1 or $Type_avioneni ==12 or ($Type_avioneni ==4 and $Type_avion !=1))
						{
							if($Enis ==1)
							{
								if($Pilotage >$Pilotage_eni)
								{
									$intro.="<p>Votre adversaire s'enfuit, ne se sentant pas de taille.</p>";
									$nav=true;
								}
								else
								{
									//IA=Chasseurs haute altitude
									$Compresseur=GetData("Moteur","ID",$Engine,"Compresseur");
									$Compresseur_eni=GetData("Moteur","ID",$Engine_eni,"Compresseur");
									if($Compresseur >1 and $Compresseur_eni <2 and $alt >6000)
									{
										$intro.="<p>La formation ennemie a profité de votre combat pour s'enfuir !</p>";
										$nav=true;
									}
									else
									{
										$msg_again='<p>Vous n\'avez pas le temps de fêter votre victoire qu\'un autre avion ennemi, un <b>'.$Nom_eni.'</b>, engage le combat.</p>';
										$continue_eni=true;
									}
								}
							}
							else
							{
								//IA=Chasseurs haute altitude
								$Compresseur=GetData("Moteur","ID",$Engine,"Compresseur");
								$Compresseur_eni=GetData("Moteur","ID",$Engine_eni,"Compresseur");
								if($Compresseur >1 and $Compresseur_eni <2 and $alt >6000)
								{
									$intro.="<p>La formation ennemie a profité de votre combat pour s'enfuir !</p>";
									$nav=true;
								}
								else
								{
									$msg_again='<p>Vous n\'avez pas le temps de fêter votre victoire qu\'un autre avion ennemi, un <b>'.$Nom_eni.'</b>, engage le combat.</p>';
									$continue_eni=true;
								}
							}
						}
						else
						{
							$intro.="<p>Le reste de la formation ennemie parvient à rompre le combat et à s'enfuir.</p>";
							$img.="<img src='images/escape.jpg' style='width:100%;'>";
							$nav=true;
						}
					}
					else
					{
						if(!$end_mission)
						{
							$intro.="<br>Vous avez nettoyé le ciel de tous les avions ennemis!";
							if(!$Sandbox)UpdateCarac($PlayerID,"Moral",10);
							$nav=true;
						}
					}
				}
			break;
			case 2:
				//Continuer le combat sans s'en soucier
				if($Mission_Type ==103)
				{
					$intro.="Votre adversaire bat des ailes en signe de défaite!";
					UpdateCarac($PlayerID,"Avancement",2);
					UpdateCarac($PlayerID,"Reputation",1);
					$confirmee=true;
				}
				else
				{
					if(!$Sandbox)UpdateCarac($PlayerID,"Avancement",5);
					/*if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						$Ailier=GetData("Pilote","ID",$PlayerID,"Ailier");*/
					if($Mission_Type ==9 or IsAilier($PlayerID,$Leader))
						$confirmee=true;
					elseif($Mission_Type ==7 and mt_rand(0,10) >2)
						$confirmee=true;
					elseif($Mission_Type ==4 and mt_rand(0,12) <$Formation)
						$confirmee=true;
					elseif($Type_avion !=1 and $Type_avion !=4 and $Type_avion !=12)
					{
						if(mt_rand(0,10) >9)
							$confirmee=true;
					}
					elseif(($Ailier or $Escorteb_nbr) and  mt_rand(0,10) >6)
						$confirmee=true;
					elseif(mt_rand(0,10) >9)
						$confirmee=true;
					//IA
					if($Enis >0)
					{
						if($Type_avioneni ==1 or $Type_avioneni ==12 or ($Type_avioneni ==4 and $Type_avion !=1))
						{
							if($Enis ==1 or GetFlee($PlayerID,$Pilote_eni))
							{
								if(($Pilotage >$Pilotage_eni) and (mt_rand(0,10) <2))
								{
									$intro.="<p>Votre adversaire s'enfuit, ne se sentant pas de taille.</p>";
									$nav=true;
								}
								else
								{
									//IA=Chasseurs haute altitude
									$Compresseur=GetData("Moteur","ID",$Engine,"Compresseur");
									$Compresseur_eni=GetData("Moteur","ID",$Engine_eni,"Compresseur");
									if($Compresseur >1 and $Compresseur_eni <2 and $alt >6000)
									{
										$intro.="<p>La formation ennemie a profité de votre combat pour s'enfuir !</p>";
										$nav=true;
									}
									else
									{
										$msg_again='<p>Ne vous laissant aucun répit, un <b>'.$Nom_eni.'</b>, engage le combat.</p>';
										$continue_eni=true;
									}
								}
							}
							else
							{
								//IA=Chasseurs haute altitude
								$Compresseur=GetData("Moteur","ID",$Engine,"Compresseur");
								$Compresseur_eni=GetData("Moteur","ID",$Engine_eni,"Compresseur");
								if($Compresseur >1 and $Compresseur_eni <2 and $alt >6000)
								{
									$intro.="<p>La formation ennemie a profité de votre combat pour s'enfuir !</p>";
									$nav=true;
								}
								else
								{
									//IA=Chasseurs haute altitude
									$Compresseur=GetData("Moteur","ID",$Engine,"Compresseur");
									$Compresseur_eni=GetData("Moteur","ID",$Engine_eni,"Compresseur");
									if($Compresseur >1 and $Compresseur_eni <2 and $alt >6000)
									{
										$intro.="<p>La formation ennemie a profité de votre combat pour s'enfuir !</p>";
										$nav=true;
									}
									else
									{
										$msg_again='<p>Ne vous laissant aucun répit, un <b>'.$Nom_eni.'</b>, engage le combat.</p>';
										$continue_eni=true;
									}
								}
							}
						}
						else
						{
							if(((mt_rand(10,$Tactique_eni)+($VitAvioneni/10)) >(($VitAvion/10)+mt_rand(10,$Tactique))) or GetFlee($PlayerID,$Pilote_eni))
							{
								$intro.="<p>Le reste de la formation ennemie parvient à rompre le combat et à s'enfuir.</p>";
								$img.="<img src='images/escape.jpg' style='width:100%;'>";
								$nav=true;
							}
							else
							{
								$msg_again='<p>Malgré la perte de l\'un des leurs, la formation de <b>'.$Nom_eni.'</b> ennemie continue sa route.</p>';
								$continue_eni=true;
							}
						}
					}
					else
					{
						if(!$end_mission)
						{
							$intro.="<br>Vous avez nettoyé le ciel de tous les avions ennemis!";
							if(!$Sandbox)UpdateCarac($PlayerID,"Moral",10);
							$confirmee=true;
							$nav=true;
						}
					}
				}
			break;
			case 10:
				//Abandonner l'appareil et saut en parachute
				if(!$Sandbox)
				{
					UpdateCarac($PlayerID,"Reputation",-10);
					UpdateCarac($PlayerID,"Courage",-10);
					UpdateCarac($PlayerID,"Avancement",-5);
				}
				$end_mission=true;
				$_SESSION['Parachute']=true;
			break;
			case 98:
				$intro.="<br>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !";
				$end_mission=true;
			break;
		}		
		//Combat tournoyant ou acrobatie
		if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
			$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Tenter une manoeuvre pour reprendre l'avantage.<br>";
		else
			$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Effectuer une manœuvre pour tenter d'attaquer l'ennemi par le flanc.<br>";		
		$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$PuissAvion,$Longitude,$Latitude,$Cible,$Mission,$c_gaz,1,$Avion_db,$flaps);	
	}		
	if($panne_seche or !$Cible)
	{
		if(!$toolbar)$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission,$c_gaz,1,$Avion_db,$flaps);
		$_SESSION['done']=false;
		$intro.="<br>Vous tombez en panne sèche!<br>Vous n'avez pas d'autre choix que d'abandonner votre appareil!"; //<br>Vous parvenez à rejoindre vos lignes à grand peine, mais vous êtes en vie!";
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
		/*$mes.="<p><b>FIN DE MISSION</b></p>";
		$menu.="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='0'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";*/
		$end_mission=true;
	}
	if(!$end_mission)
	{
		if($confirmee and $Formation >0 and $Mission_Type !=103)
		{
			if($Type_avion !=1 and $Type_avion !=4 and $Type_avion !=12)
			{
				if(mt_rand(0,10) >9)
					$confirmee=true;
				elseif($Formation >0)
					$intro.="Un avion de votre formation confirme la victoire!";
			}
			else
			{
				if($Pilote_eni >0 and $Pilote_eni !=2751 and $Pilote_eni !=3297 and (mt_rand(0,12) <$Formation or $Ailier >0))
					$confirmee=true;
				else
				{
					$confirmee=false;
					$intro.="Un avion de votre formation confirme la victoire!";
					$con=dbconnecti();
					$resultia=mysqli_query($con,"SELECT ID,Avion FROM Pilote_IA WHERE Unit='$Unite' AND Cible='$Cible' AND Actif=1 ORDER BY RAND() LIMIT 1");
					mysqli_close($con);
					if($resultia)
					{
						while($data=mysqli_fetch_array($resultia,MYSQLI_ASSOC))
						{
							$Pilote_IA_confirm=$data['ID'];
							$Pilote_IA_confirm_avion=$data['Avion'];
						}
						mysqli_free_result($resultia);
					}
					AddEvent("Avion",192,$Pilote_IA_confirm_avion,$Pilote_IA_confirm,$Unite,$Cible,$avion_eni,$Pilote_eni);
					UpdateData("Pilote_IA","Victoires",1,"ID",$Pilote_IA_confirm);
					UpdateData("Unit","Reputation",10,"ID",$Unite,0,4);
				}
			}
		}
		//Anti GB
		if($confirmee and !$Sandbox and $Mission_Type !=103)
		{
			if(date("H") <7)
			{
				if(mt_rand(0,100) >10)
					$confirmee=false;
			}
			else
			{
				$con=dbconnecti();
				$Vic=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Joueur_win='$PlayerID' AND PVP<>1 AND DATE(Date)=DATE(NOW())"),0);
				mysqli_close($con);
				if($Vic)
				{
					$chance_max=$Vic*5;
					$chance_conf=mt_rand(0,$chance_max);
					if($chance_conf >19)$confirmee=false;
				}
			}
		}
		if($confirmee and ($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12))
		{
			if($Mission_Type ==103)
			{
				$intro.="<p>Votre adversaire vous fait signe pour vous féliciter de votre victoire!</p>";
				$img=Afficher_Image('images/stay'.$country.'.jpg',"images/stay7.jpg","Victoire!");
				UpdateCarac($PlayerID,"Reputation",2);
				$nav=true;
			}
			elseif($Sandbox)
			{
				if($Avion_db =="Avions_Sandbox")
					$Avion_win=GetData($Avion_db,"ID",$avion,"ID_ref");
				else
					$Avion_win=$avion;
				$date=date('Y-m-d G:i');
				$query="INSERT INTO Chasse_sandbox (Date, Avion_loss, Avion_win, Joueur_win, Unite_win, Unite_loss, Lieu, Arme_win, Pilote_loss, PVP, Cycle, Longitude, Latitude, Altitude)
				VALUES ('$date','$avion_eni','$Avion_win','$PlayerID','$Unite','$Unit_eni','$Cible','$Arme1Avion','$Pilote_eni','$Vic_Etat','$Nuit','$Longitude','$Latitude','$alt')";
				$con=dbconnecti(2);
				$ok=mysqli_query($con,$query);
				mysqli_close($con);
				UpdateCarac($PlayerID,"Free",1);
				$intro.="<p><b>Votre adversaire s'abat en flammes!<br>Votre victoire est confirmée</b></p>";
			}
			elseif($Simu)
			{
				//Tableau de chasse
				if($PVP)
				{
					$Vic_Etat=2;
					$Unit_eni=GetData("Pilote","ID",$Pilote_eni,"Unit");
				}
				else
					$Vic_Etat=0;	
				if(!$PVP and $Pilote_eni)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT j.Avion,u.Avion1,u.Avion2,u.Avion3,j.Unit FROM Pilote_IA as j,Unit as u WHERE j.Unit=u.ID AND j.ID='$Pilote_eni'");
					$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$Pilote_eni'");
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
					if($Avion_lose and $Unit_lose)
					{
						UpdateData("Unit",$Avion_Flight_Lose,-1,"ID",$Unit_lose);
						//UpdateData("Unit","Reputation",-10,"ID",$Unit_lose);						
						$Unit_eni=$Unit_lose;
						if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							AddEvent($Avion_db,181,$avion,$PlayerID,$Unit_lose,$Cible,$Avion_lose,$Pilote_eni);
							AddEvent($Avion_db,188,$avion,$PlayerID,$Unite,$Cible,$Avion_lose,$Pilote_eni);
							$Pays_Cible=GetData("Unit","ID",$Unit_eni,"Pays");
							if(IsWar($country, $Pays_Cible))
							{
								$intro.="<p>Vous réduisez la couverture aérienne ennemie sur la zone!</p>";
								if($Mission_Type ==26)
								{
									UpdateData("Unit","Reputation",10,"ID",$Unite,0,4);
									UpdateCarac($PlayerID,"Avancement",20);
									UpdateCarac($PlayerID,"Reputation",10);
									UpdateCarac($PlayerID,"Missions",10);
									UpdateCarac($PlayerID,"Note",1);
								}
								elseif($Mission_Type ==7 or $Mission_Type ==31)
								{
									UpdateData("Unit","Reputation",10,"ID",$Unite,0,4);
									UpdateCarac($PlayerID,"Avancement",20);
									UpdateCarac($PlayerID,"Reputation",10);
									UpdateCarac($PlayerID,"Missions",10);
								}
								else
									UpdateCarac($PlayerID,"Avancement",5);
							}
						}
					}
					if(!$Discipline_fer or mt_rand(0,1) >0)
						WoundPilotIA($Pilote_eni);
					$Vic_Etat=4;
				}
				//Mission_Historique
				$BH_Mission=$_SESSION['BH_Mission'];
				if($Mission_Type ==9 and $BH_Mission ==9)
				{
					$Bombe=GetData("Avion","ID",$avion_eni,"Bombe");
					$Bombe_Nbr=GetData("Avion","ID",$avion_eni,"Bombe_Nbr");
					$Bombe_res=round($Bombe*$Bombe_Nbr/1000);
					if($Bombe_res <1)$Bombe_res=1;
					if(IsAxe($country))
						$Points_cat="Points_Axe";
					else
						$Points_cat="Points_Allies";
					UpdateData("Event_Historique",$Points_cat,$Bombe_res,"ID",$_SESSION['BH_ID']);
				}
				elseif(($Mission_Type ==7 and $BH_Mission ==7) or ($Mission_Type ==17 and $BH_Mission ==17) or ($Mission_Type ==26 and $BH_Mission ==26))
				{
					if(IsAxe($country))
						$Points_cat="Points_Axe";
					else
						$Points_cat="Points_Allies";
					UpdateData("Event_Historique",$Points_cat,2,"ID",$_SESSION['BH_ID']);
				}
				if($PVP or ($Pilote_eni >0 and $Pilote_eni !=2751 and $Pilote_eni !=3297))
				{
					$Pilote_eni_vic=(GetData($Pilote_db,"ID",$Pilote_eni,"Victoires")/100)+GetVicPoints($Type_avioneni);
					//UpdateCarac($PlayerID,"Victoires",$Pilote_eni_vic);
					UpdateCarac($PlayerID,"Reputation",$Pilote_eni_vic);
					UpdateCarac($PlayerID,"Avancement",5);
					UpdateData("Unit","Reputation",$Pilote_eni_vic,"ID",$Unite,0,4);
					AddVictoire($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,$Vic_Etat,$Nuit,$alt);
				}
				$intro.="<p><b>Votre adversaire s'abat en flammes!<br>Votre victoire est confirmée</b></p>";
			}
			SetData("Duels_Candidats","Target",0,"PlayerID",$PlayerID);
			RetireCandidat($Pilote_eni,"kill_confirm");
			if(!$img)$img=Afficher_Image('images/kill'.$country.'.jpg',"images/kill.jpg","Victoire!");
			//Risque appareil endommagé si tir à très courte distance
			if($Dist_shoot <20)
			{
				$chances_debris=mt_rand(0,100);
				$dist_bonus=20-$Dist_shoot;
				if($chances_debris <$dist_bonus)
				{
					$intro.="<p>Des débris de l'avion ennemi touchent votre appareil, l'endommageant !</p>";
					$Debris_Dmg_bonus=10*$dist_bonus;
					$HP-=mt_rand(1,$Debris_Dmg_bonus);
					SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
					//HP Avion perso persistant
					if($Avion_db =="Avions_Persos")
					{
						if($HP <1)$HP=0;
						SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
					}
					if($HP <1)
					{
						$end_mission=true;
						$continue_eni=false;
						$Enis=0;
					}
				}
				unset($chances_debris);
				unset($dist_bonus);
			}
		}
		else
		{
			if($Mission_Type ==103)
			{
				$intro.="Votre instructeur vous félicite à la radio pour votre attitude au combat!";
				UpdateCarac($PlayerID,"Avancement",2);
				$nav=true;
			}
			elseif($Simu and !$Sandbox and $Cible and $Pilote_eni)
			{
				if($PVP)
					$Vic_Etat=3;
				else
					$Vic_Etat=0;
				AddProbable($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,$Vic_Etat);
				if(!$Nuit and !$PVP and $Pilote_eni and ($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12))
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT j.Avion,u.Avion1,u.Avion2,u.Avion3,j.Unit FROM Pilote_IA as j,Unit as u WHERE j.Unit=u.ID AND j.ID='$Pilote_eni'");
					$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$Pilote_eni'");
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
						WoundPilotIA($Pilote_eni);
					if($Avion_lose and $Unit_lose)
					{
						UpdateData("Unit",$Avion_Flight_Lose,-1,"ID",$Unit_lose);
						$Unit_eni=$Unit_lose;
					}
					$Pays_Cible=GetData("Unit","ID",$Unit_eni,"Pays");
					if(IsWar($country,$Pays_Cible))
					{
						if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
						{
							AddEvent($Avion_db,181,$avion,$PlayerID,$Unit_lose,$Cible,$Avion_lose,$Pilote_eni);
							AddEvent($Avion_db,188,$avion,$PlayerID,$Unite,$Cible,$Avion_lose,$Pilote_eni);
							$skills.="<p>Vous réduisez la couverture aérienne ennemie sur la zone!</p>";
						}
						if($Mission_Type ==26)
						{
							UpdateCarac($PlayerID,"Avancement",10);
							UpdateCarac($PlayerID,"Reputation",5);
							UpdateCarac($PlayerID,"Missions",5);
						}
						elseif($Mission_Type ==7 or $Mission_Type ==31)
						{
							UpdateCarac($PlayerID,"Avancement",10);
							UpdateCarac($PlayerID,"Reputation",5);
							UpdateCarac($PlayerID,"Missions",5);
						}
						else
							UpdateCarac($PlayerID,"Avancement",5);
					}
				}
			}
			SetData("Duels_Candidats","Target",0,"PlayerID",$PlayerID);
			if(!$intro)$intro="<p>Vous continuez votre route sans avoir la possibilité de confirmer votre victoire.</p>";
			$img='<img src=\'images/stay'.$country.'.jpg\' style=\'width:100%;\'>';
			if($Enis >0 and !$nav)
				$continue_eni=true;
			else
				$nav=true;
		}		
		if($continue_eni)
		{
			$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';		
			$_SESSION['PVP']=false;
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
				$Alleger="";
			if($S_Baby >0 and $essence <$Autonomie-$S_Baby)
				$Larguer="<Input type='Radio' name='Action' value='19'>- Larguer le réservoir largable pour alléger l'avion.<br>";
			else
				$Larguer="";
			//Attaque par le ventre
			if($alt >1000 and $Tactique >50 and ($Type_avioneni ==2 or $Type_avioneni ==11))
				$Ventre ="<Input type='Radio' name='Action' value='11'>- Tenter d'attaquer par le ventre en évitant le mitrailleur arrière.<br>";
			if($Mission_Type ==103)
				$Pilote_eni=3297;
			else
			{
				//Nouvel ennemi
				//Anti Grosbill - plus ils ont de victoire, plus fort sera le pilote
				if($Simu)$Seed=2000+(1000*$Vic);
				//$Pilote_eni=Random_Pilot($PlayerID,$Unit_eni,$Cible,$Type_avioneni,$Seed,$Sandbox);
				$con=dbconnecti();
				$Pilote_eni=mysqli_result(mysqli_query($con,"SELECT ID FROM Pilote_IA WHERE Cible='$Cible' AND Unit='$Unit_eni' AND Actif=1 ORDER BY RAND() LIMIT 1"),0);
				mysqli_close($con);
			}
			$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
			//Seuls les chasseurs et chasseurs lourds attaquent
			if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
			{
				$choix1="<Input type='Radio' name='Action' value='1'>- Attaquer la formation ennemie par l'arrière.<br>";
				if($alt >1000)
					$choix7="<Input type='Radio' name='Action' title='".GetMes('Aide_Coiffer')."' value='7'>- Chercher à prendre de l'altitude pour ensuite piquer sur l'ennemi.<br>";
				else
					$choix7="";
				$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Attaquer la formation ennemie par le flanc.<br>";
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
			SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
			$intro.=$msg_again;
			$titre="Combat";
			$mes.='<form action=\'mission3.php\' method=\'post\'>
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
			<input type=\'hidden\' name=\'Unit_eni\' value='.$Unit_eni.'>
			<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
			<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,1).'
			'.GetSituation($Enis,$avion_eni,GetData("Unit","ID",$Unit_eni,"Pays"),$Leader,$Ailier,$avion).'
			<table class=\'table\'><tr><td align=\'left\'>'.$choix1.$choix7.$choix2.'
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'4\' checked>- Tenter de rompre le combat en vous lançant dans un piqué.<br>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Nuages').'\' value=\'5\'>- '.$choix5.'<br>'.$Ventre.$Alleger.'
				</td></tr></table>
			<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		}	
		if($nav)
		{
			if($PVP and !$Sandbox)
			{
				$_SESSION['done']=false;
				$chemin=0;
			}
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0,S_Essence='$essence' WHERE ID='$PlayerID'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : confirm-reset2');
			mysqli_close($con);
			$titre="Navigation";
			$mes.="<form action='nav.php' method='post'>
			<input type='hidden' name='Chemin'  value=".$chemin.">
			<input type='hidden' name='Distance' value=".$Distance.">
			<input type='hidden' name='Meteo'  value=".$meteo.">
			<input type='hidden' name='Avion'  value=".$avion.">
			<input type='hidden' name='Mun1' value=".$Mun1.">
			<input type='hidden' name='Mun2' value=".$Mun2.">
			<input type='hidden' name='Puissance' value=".$Puissance.">
			<input type='hidden' name='Enis' value=".$Enis.">
			".ShowGaz($avion,$c_gaz,$flaps,$alt)."
			".GetSituation($Enis,$avion_eni,GetData("Unit","ID",$Unit_eni,"Pays"),$Leader,$Ailier,$avion)."
			<table class='table'><tr><td align='left'>
						<Input type='Radio' name='Action' value='0' checked>- Continuer vers votre objectif.<br>
						<Input type='Radio' name='Action' value='1'>- Faire demi-tour.<br>
				</td></tr></table>
			<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}
	if($end_mission)
	{
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		if($Sandbox)
		{
			if($Avion_db =="Avions_Sandbox")
				$avion=GetData($Avion_db,"ID",$avion,"ID_ref");
			$date=date('Y-m-d G:i');
			$query="INSERT INTO Chasse_sandbox (Date, Avion_loss, Avion_win, Joueur_win, Unite_win, Unite_loss, Lieu, Arme_win, Pilote_loss, PVP, Cycle, Longitude, Latitude, Altitude)
			VALUES ('$date','$avion','$avion_eni','$Pilote_eni','$Unit_eni','$Unite','$Cible','$Arme1Avion_eni','$PlayerID','$Vic_Etat','$Nuit','$Longitude','$Latitude','$alt')";
			$con=dbconnecti(2);
			$ok=mysqli_query($con,$query);
			mysqli_close($con);			
			$_SESSION['Distance']=0;
			$_SESSION['PVP']=false;
			$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		elseif($Mission ==103)
		{
			$_SESSION['Distance']=0;
			$_SESSION['PVP']=false;
			$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
			$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		else
		{
			if($PVP)
				RetireCandidat($PlayerID,"end_mission");
			if($HP <1 or $_SESSION['Parachute'] ==true)
			{
				//Tableau de chasse
				if($PVP)
					$Vic_Etat=3;
				elseif(!$Vic_Etat)
					$Vic_Etat=1;
				AddVictoire($Avion_db,$avion,$avion_eni,$Pilote_eni,$Unit_eni,$Unite,$Cible,$Arme1Avion_eni,$PlayerID,$Vic_Etat,$Nuit,$alt);
				AddEvent($Avion_db,3,$avion,$PlayerID,$Unite,$Cible);
			}
			else
				AddEvent($Avion_db,34,$avion,$PlayerID,$Unite,$Cible);
			UpdateData("Unit","Reputation",-10,"ID",$Unite,0,4);
			//Blessure
			$blesse=0;
			$Blessure=GetBlessure($PlayerID,$Avion_db,$avion);
			switch($Blessure)
			{
				case 0:
					$Blessure_txt="<p>Vous vous en sortez indemne!</p>";
					$Hard=1;
					$Malus_Moral=-25;
				break;
				case 1:
					$Blessure_txt="<p>Vous êtes blessé, mais néanmoins en vie!</p>";
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
					AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pilote SET Credits=0,Missions_Max=6,Escorte=0,Couverture=0 WHERE ID='$PlayerID'");
					mysqli_close($con);
					$blesse=2;
					DoBlessure($PlayerID,10);
				break;
			}
			$intro.=$Blessure_txt;
			UpdateCarac($PlayerID,"Abattu",1);
			UpdateCarac($PlayerID,"Moral",$Malus_Moral);
			UpdateCarac($PlayerID,"Reputation",-10);
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
			//Prisonnier
			//$Base=GetData("Unit","ID",$Unite,"Base");
			$Dist=GetDistance(0,$Cible,$Longitude,$Latitude);
			if($Bonne_Etoile)$pas_mia=mt_rand(0,1);
			if($Mission_Type !=7 and $Mission_Type !=9 and $Mission <90 and $Mission_Type !=23 and $Dist[0] <30 and !$pas_mia)
			{
				$intro.="<p>Vous vous retrouvez au beau milieu d'une zone contrôlée par l'ennemi.
				<br>Le temps de regagner vos lignes vous rend indisponible jusqu'à votre retour.</p>";
				$mes.="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,MIA='$Cible',Commando=0 WHERE ID='$PlayerID'");
				mysqli_close($con);
				AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
				$_SESSION['Distance'] =0;
			}
			else
			{
				if($blesse <2)$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
				$mes.="<p><b>FIN DE MISSION</b></p>";
				$menu.='<form action=\'promotion.php\' method=\'post\'><input type=\'hidden\' name=\'Blesse\' value='.$blesse.'>
					<input type=\'Submit\' value=\'TERMINER LA MISSION\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
			}
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