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
$Dist_shoot=Insec($_POST['Dist_shoot']);
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
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $_SESSION['kill_confirm'] ==false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_rencontre.inc.php');
	include_once('./jfv_inc_pvp.php');
	$_SESSION['finish']=false;
	$_SESSION['tirer']=false;
	$_SESSION['missiondeux']=false;
	$_SESSION['missiontrois']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['evader']=false;
	$_SESSION['kill_confirm']=true;	
	$PVP=$_SESSION['PVP'];
	$panne_seche=false;
	$end_mission=false;
	$nav=false;
	$confirmee=false;
	$continue_eni=false;	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Ailier,S_Ailier,Pilotage,Tactique,Moral,S_Avion_db,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,S_Baby,
	S_Nuit,S_Cible,S_Mission,S_Longitude,S_Latitude,S_Escorte_nbr,S_Escorteb_nbr,S_Leader,S_Equipage_Nbr,S_Formation,Simu,Sandbox FROM Pilote_PVP WHERE ID='$Pilote_pvp'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : confirm_pvp-player');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$HP=$data['S_HP'];
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
			$avion_eni_nom=GetData($Avion_db_eni,"ID",$avion_eni,"Nom");
			$intro.="<p>[PVP] Vous encaissez une rafale tirée par un <b>".$avion_eni_nom."</b>! (<b>".$Deg."</b> dégâts)</p>";
		}
		if($PVP_Ok !=$Cible or !$PVP_Target)
		{
			$_SESSION['PVP']=false;
			$PVP=false;
			SetData("Duels_Candidats_PVP","Target",0,"PlayerID",$Pilote_eni);
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0 WHERE ID='$Pilote_pvp'");
			mysqli_close($con);
			RetireCandidatPVP($Pilote_pvp,"nav");
			$Enis=0;
		}
	}
	$avion_img=GetAvionImg($Avion_db,$avion);	
	//Boost
	if($c_gaz ==130)
		UpdateData("Pilote_PVP","Stress_Moteur",10,"ID",$Pilote_pvp);	
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
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Pays,Puissance,Masse,Robustesse,ArmePrincipale,ArmeSecondaire,Plafond,Type,Autonomie,Baby,Engine FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : confirm_pvp-avion');
		$result2=mysqli_query($con,"SELECT Nom,Type,Robustesse,ArmePrincipale,Plafond,Engine,Rating FROM $Avion_db_eni WHERE ID='$avion_eni'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : confirm_pvp-avioneni');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$country=$data['Pays'];
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
			unset($data);
		}
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
				$Reput_eni=$data['Rating'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		//Malus avion touché
		$moda=$HPmax/$HP;
		if($Avion_Bombe_Nbr >0 and $Avion_Bombe !=30)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_Nbr));
			$moda*=(1+$charge_sup);
		}
		$PuissAvion=$Puissance*$moda;
		$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$VitAvioneni=GetSpeed($Avion_db_eni,$avion_eni,$alt,$meteo,1);
		//Pilotage eni
		if($Pilote_eni)
		{
			$Pilotage_eni=GetData("Pilote_PVP","ID",$Pilote_eni,"Pilotage");
			$Tactique_eni=GetData("Pilote_PVP","ID",$Pilote_eni,"Tactique");
		}
		else
		{
			$Pilotage_eni=mt_rand(50,200);
			$Tactique_eni=mt_rand(50,200);
		}
		//Si avion trop abimé, il s'écrase au sol
		if(($VitAvion <20) or ($PuissAvion <1))$Action=98;
		$Conso=($Puissance_nominale*$c_gaz/100)/500;
		$Enis-=1;
		UpdateData("Pilote_PVP","enis",-1,"ID",$Pilote_pvp);
		switch($Action)
		{
			case 1:
				//Poursuivre l'avion au sol pour confirmer
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
						$intro.="Un avion de votre formation confirme la victoire!";
				}
				elseif($Mission_Type ==4 or $Mission_Type ==7 or $Mission_Type ==17 or $Mission_Type ==26)
					$confirmee=true;
				if($Enis >0 and $Mission_Type !=3 and $Mission_Type !=4 and $Mission_Type !=26)
				{
					$intro.="<p><b>Vous oubliez votre mission!</b></p>";
					SetData("Pilote_PVP","S_Cible_Atk",99,"ID",$Pilote_pvp);
				}	
				elseif($Enis >0 and $Mission_Type ==4 and $Escorteb_nbr >0)
				{
					$intro.="<p><b>Vous laissez votre escorte sans protection!</b></p>";
					$Escort_Time=mt_rand(0,10);
					if($Escort_Time <3)
					{
						$intro.='<p>L\'ennemi a profité de votre absence pour descendre un <b>'.$Escorteb_nom.'</b> que vous escortiez !</p>';
						$Escorteb_nbr-=1;
						if($Escorteb_nbr <1 and $_SESSION['done'] ==false)
						{
							$intro.="<p><b>Toute la formation que vous escortiez a été abattue! Vous échouez dans votre mission !</b><p>";
							$_SESSION['done']=true;
							$chemin=$Distance;
							$Mission_Type=3; //Passer en mode chasse pour éviter le menu d'escorte				
						}
					}
				}
				if($Enis >0)
				{
					if($Pilotage_eni <50)
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
						$nav=true;
					}
				}
			break;
			case 2:
				//Continuer le combat sans s'en soucier
				if($Mission_Type ==9)
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
						if($Enis ==1)
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
						if(((mt_rand(10,$Tactique_eni)+($VitAvioneni/10)) >(($VitAvion/10)+mt_rand(10,$Tactique))))
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
						$confirmee=true;
						$nav=true;
					}
				}
			break;
			case 10:
				//Abandonner l'appareil et saut en parachute
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
			$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Effectuer une manuvre pour tenter d'attaquer l'ennemi par le flanc.<br>";		
		$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$PuissAvion,$Longitude,$Latitude,$Cible,$Mission,$c_gaz,1,$Avion_db,$flaps,true);	
	}		
	if($panne_seche or !$Cible)
	{
		if(!$toolbar)$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission,$c_gaz,1,$Avion_db,$flaps,true);
		$_SESSION['done']=false;
		$intro.="<br>Vous tombez en panne sèche!<br>Vous n'avez pas d'autre choix que d'abandonner votre appareil!"; //<br>Vous parvenez à rejoindre vos lignes à grand peine, mais vous êtes en vie!";
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		$end_mission=true;
	}
	if(!$end_mission)
	{
		if($confirmee and $Formation >0)
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
				$confirmee=false;
				$intro.="Un avion de votre formation confirme la victoire!";
			}
		}
		if($confirmee and ($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12))
		{
			$intro.="<p><b>Votre adversaire s'abat en flammes!<br>Votre victoire est confirmée</b></p>";
			$Faction_db=GetFactionDB($Faction);
			UpdateData("Battle_score",$Faction_db,$Reput_eni,"ID",$Battle);
			SetData("Duels_Candidats_PVP","Target",0,"PlayerID",$Pilote_pvp);
			UpdateData("Pilote_PVP","Victoires",1,"ID",$Pilote_pvp);
			UpdateData("Pilote_PVP","Points",$Reput_eni,"ID",$Pilote_pvp);
			RetireCandidatPVP($Pilote_eni,"kill_confirm");
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
					SetData("Pilote_PVP","S_HP",$HP,"ID",$Pilote_pvp);
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
			UpdateData("Pilote_PVP","Victoires_prob",1,"ID",$Pilote_pvp);
			SetData("Duels_Candidats_PVP","Target",0,"PlayerID",$Pilote_pvp);
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
				$Alleger='';
			if($S_Baby >0 and $essence <$Autonomie-$S_Baby)
				$Larguer="<Input type='Radio' name='Action' value='19'>- Larguer le réservoir largable pour alléger l'avion.<br>";
			else
				$Larguer='';
			//Attaque par le ventre
			if($alt >1000 and $Tactique >50 and ($Type_avioneni ==2 or $Type_avioneni ==11))
				$Ventre ="<Input type='Radio' name='Action' value='11'>- Tenter d'attaquer par le ventre en évitant le mitrailleur arrière.<br>";
			$Pilote_eni=3297;
			$HP_eni=GetData("Avion","ID",$avion_eni,"Robustesse");
			//Seuls les chasseurs et chasseurs lourds attaquent
			if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
			{
				$choix1="<Input type='Radio' name='Action' value='1'>- Attaquer la formation ennemie par l'arrière.<br>";
				if($alt >1000)
					$choix7="<Input type='Radio' name='Action' title='".GetMes('Aide_Coiffer')."' value='7'>- Chercher à prendre de l'altitude pour ensuite piquer sur l'ennemi.<br>";
				else
					$choix7='';
				$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Attaquer la formation ennemie par le flanc.<br>";
			}
			elseif($Equipage_Nbr >1 and $avion !=43 and $avion !=109)
			{
				$choix1="<Input type='Radio' name='Action' value='9'>- Maintenir votre cap, votre mitrailleur arrière se chargeant de l'adversaire.<br>";
				$choix7='';
				$choix2='';
			}
			else
			{
				$choix1='';
				$choix7='';
				$choix2='';
			}
			SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
			$intro.=$msg_again;
			$titre='Combat';
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
			'.GetSituation($Enis,$avion_eni,GetData("Avion","ID",$avion_eni,"Pays"),$Leader,$Ailier,$avion,true).'
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
			$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Essence='$essence' WHERE ID='$Pilote_pvp'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : confirm-reset2');
			mysqli_close($con);
			$titre='Navigation';
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
			".ShowGaz($avion,$c_gaz,$flaps,$alt,false,true)."
			".GetSituation($Enis,$avion_eni,GetData("Avion","ID",$avion_eni,"Pays"),$Leader,$Ailier,$avion,true)."
			<table class='table'><tr><td align='left'>
						<Input type='Radio' name='Action' value='0' checked>- Continuer vers votre objectif.<br>
						<Input type='Radio' name='Action' value='1'>- Faire demi-tour.<br>
				</td></tr></table>
			<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}
	if($end_mission)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Unite_Intercept=0,Escorte=0,Couverture=0,Points=Points-1,Abattu=Abattu+1,Avion_Sandbox=0,S_HP=0 WHERE ID='$Pilote_pvp'");
		mysqli_close($con);
		AddAirCbtPVP($Pilote_eni,$avion_eni,$Pilote_pvp,$avion,$Cible,$alt,$Dist_shoot);
		$_SESSION['Distance']=0;
		$_SESSION['PVP']=false;
		$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		$mes.="<p><b>FIN DE MISSION</b></p>";
		$menu.="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
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