<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$action=Insec($_POST['Action']);
$avion=Insec($_POST['Avion']);
$alt=Insec($_POST['Alt']);
$meteo=Insec($_POST['Meteo']);
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Enis=Insec($_POST['Enis']);
$Puissance=Insec($_POST['Puissance']);
$avion_eni=Insec($_POST['Avioneni']);
$alt_avioneni=Insec($_POST['Alt_avioneni']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$Battle=Insec($_POST['Battle']);
$Faction=Insec($_POST['Camp']);
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $avion_eni >0 AND $_SESSION['missiondeux'] ==false AND $avion >0 AND $action >0)
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_rencontre.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Mode=Insec($_POST['Mode']);
	$_SESSION['naviguer']=false;
	$_SESSION['tirer']=false;
	$_SESSION['finish']=false;
	$_SESSION['evader']=false;
	$_SESSION['missiontrois']=false;
	$_SESSION['missiondeux']=true;	
	$country=$_SESSION['country'];
	$PVP=$_SESSION['PVP'];
	$Saison=$_SESSION['Saison'];
	$nav=false;
	$mission3=false;
	$evade=false;
	$shoot=false;
	$continue_route=false;
	$escort_eni=false;
	$end_mission=false;
	$retraite=false;
	$war=false;
	$eni_chasseur=false;	
	$Stealth=false;
	$Base=GetBasePVP($Battle,$avion,$Faction);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Pilotage,Acrobatie,Tactique,Pilotage,Vue,Avancement,Reputation,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,S_Baby,Simu,Pilote_eni,
	S_Nuit,S_Cible,S_Mission,S_Escorte_nbr,S_Longitude,S_Latitude,S_Escorte,S_Escorte_nom,S_Escorteb_nbr,S_Escorteb_nom,S_Equipage_Nbr,S_Leader,S_Formation,Slot2,Slot4,Sandbox,Admin
	FROM Pilote_PVP WHERE ID='$Pilote_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission2_pvp-player');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$HP=$data['S_HP'];
			$Pilotage=$data['Pilotage'];
			$Tactique=$data['Tactique'];
			$Acrobatie=$data['Acrobatie'];
			$Pilotage=$data['Pilotage'];
			$Vue=$data['Vue'];
			$Avancement=$data['Avancement'];
			$Reputation=$data['Reputation'];
			$Cible=$data['S_Cible'];
			$Nuit=$data['S_Nuit'];
			$Mission_Type=$data['S_Mission'];
			$Escorte_nbr=$data['S_Escorte_nbr'];
			$Escorte=$data['S_Escorte'];
			$Escorte_nom=$data['S_Escorte_nom'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Equipage=$data['Equipage'];
			$Escorteb_nbr=$data['S_Escorteb_nbr'];
			$Escorteb_nom=$data['S_Escorteb_nom'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Leader=$data['S_Leader'];
			$Formation=$data['S_Formation'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Avion_Bombe_Nbr=$data['S_Avion_Bombe_Nbr'];
			$S_Baby=$data['S_Baby'];
			$Slot2=$data['Slot2'];
			$Slot4=$data['Slot4'];
			$Simu=$data['Simu'];
			$Pilote_eni=$data['Pilote_eni'];
			$Sandbox=$data['Sandbox'];
			$Admin=$data['Admin'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Type,Robustesse,Puissance,Masse,ChargeAlaire,VitesseA,VitesseP,Plafond,Autonomie,ArmePrincipale,ArmeSecondaire,Engine,Radio,Camouflage,Baby FROM Avion WHERE ID='$avion'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission2_pvp-avion');
	$result2=mysqli_query($con,"SELECT Type,Pays,Nom,ChargeAlaire,Plafond,Robustesse FROM Avion WHERE ID='$avion_eni'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission2_pvp-avioneni');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Type_avion=$data['Type'];
			$HPmax=$data['Robustesse'];
			$Puissance_ori=$data['Puissance'];
			$Masse=$data['Masse'];
			$Surface_Alaire=$data['ChargeAlaire'];
			$VitesseA=$data['VitesseA'];
			$VitesseP=$data['VitesseP'];
			$Plafond=$data['Plafond'];
			$Autonomie=$data['Autonomie'];
			$Arme1Avion=$data['ArmePrincipale'];
			$Arme2Avion=$data['ArmeSecondaire'];
			$Engine=$data['Engine'];
			$Radio_a=$data['Radio'];
			$Camouflage=$data['Camouflage'];
			$Baby=$data['Baby'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	//Avion_eni
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Pays_eni=$data['Pays'];
			$Type_avioneni=$data['Type'];
			$nom_avioneni=$data['Nom'];
			$Plafond_eni=$data['Plafond'];
			$Rob_eni=$data['Robustesse'];
		}
		mysqli_free_result($result2);
		unset($data);
	}
	$Faction_eni=GetData("Pays","ID",$Pays_eni,"Faction");
	if($Faction !=$Faction_eni)$war=true;
	if($war and ($Type_avioneni ==1 or $Type_avioneni ==12))$eni_chasseur=true;
	$avion_img=GetAvionImg("Avion",$avion);
	$Avion_db_eni="Avion";
	$HP_eni=$Rob_eni;			
	if($Nuit and ($Camouflage ==4 or $Camouflage ==5))
		$Stealth=10;
	//Boost
	if($c_gaz ==130)UpdateData("Pilote_PVP","Stress_Moteur",10,"ID",$Pilote_pvp);
	//Malus avion touché
	if($HP <1)
		$action=99;
	else
	{
		$moda=$HPmax/$HP;
		if($Avion_Bombe_Nbr and $Avion_Bombe !=30)
		{
			$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_Nbr));
			$moda*=(1+$charge_sup);
		}
		$Plafond=round($Plafond/$moda);
	}	
	//Incident, plus de chances d'arriver s'il fait chaud
	$malus_incident=1;
	$malus_incident_eni=1;
	if(mt_rand($meteo,100) >80)
	{
		$break=GetIncident($Pilote_pvp,2,$Saison,0,"Avion",$avion,$c_gaz,true);
		if($break[1] <-9)
			$intro.='Vous constatez <b>'.$break[0].'</b> réduisant temporairement les performances de votre machine.';
		$malus_incident=(100+$break[1])/100;

	}
	if(mt_rand($meteo,100) >95)
	{
		//Incident_eni
		$break_eni=GetIncident(0,2,$Saison,0,$Avion_db_eni,$avion_eni);
		$malus_incident_eni=(100+$break_eni[1])/100;
	}
	$VitAvion=GetSpeed("Avion",$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
	$VitAvioneni=GetSpeed($Avion_db_eni,$avion_eni,$alt_avioneni,$meteo,1,$malus_incident_eni);
	if($Mission_Type ==4)
	{
		$Escorteb=GetData("Pilote_PVP","ID",$PlayerID,"S_Escorteb");
		$Plafond_escorte=GetData("Avion","ID",$Escorte,"Plafond");
		if($Plafond_escorte <$alt)
			$alt_esc=$Plafond_escorte;
		else
			$alt_esc=$alt;
		$VitEscorte=GetSpeed("Avion",$Escorte,$alt,$meteo);
		if($VitAvion >$VitEscorte)$VitAvion=$VitEscorte;
		$alt_intercept=$Plafond_escorte;
	}
	else
		$alt_intercept=$alt;
	if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
		$Ailier=3297;
	if(!$PVP and $Ailier)
	{
		$choix3="<Input type='Radio' name='Action' title='".GetMes('Aide_Prot_leader')."' value='3'>- Couvrir votre ailier<br>";
		$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='6'>- Demander à votre ailier d'engager le combat<br>";
		$txtl="<br>Suivant votre plan de vol, vous maintenez la formation vers l'objectif à une altitude de ";
	}
	else
	{
		$choix3="";
		$choix6="";
		$txtl="";
	}
	$diff_alt=abs($alt_avioneni-$alt)/100;	
	$Conso=($Puissance_ori*$c_gaz/100)/500;	
	if($HP_eni <500)$HP_eni=$Rob_eni;
	//Supplément vitesse aléatoire
	$VitSup=mt_rand(-5,10);
	switch($action)
	{
		case 1:
			//Tenter de vous rapprocher.
			$essence-=round((abs($meteo)/10)+$Conso+5);
			if($war)
			{
				if($Mission_Type ==4 and $Escorteb_nbr >0)
				{
					$intro.="<p>Vous laissez votre escorte sans protection!</p>";
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
							SetData("Pilote_PVP","S_Mission",3,"ID",$PlayerID); //Passer en mode chasse pour éviter le menu d'escorte
						}
					}
				}
				if($VitAvioneni+$diff_alt+$VitSup >= $VitAvion+$VitSup+$Stealth or ($alt_avioneni >=$alt and $eni_chasseur and !$Nuit and $meteo >-50))
				{
					if($eni_chasseur)
					{
						$intro.="<p>L'ennemi vous attendait et ne se laisse pas surprendre!</p>";
						$img="<img src='images/facetoface.jpg' style='width:100%;'>";
						$evade=true;
					}
					else
					{
						$intro.="<p>Votre adversaire est trop rapide pour vous, vous le perdez de vue.</p>";
						$img="<img src='images/toofast.jpg' style='width:100%;'>";
						$nav=true;
					}
				}
				elseif($Enis >2)
				{
					$intro.="<p>L'ennemi vous attendait et ne se laisse pas surprendre!</p>";
					$img="<img src='images/facetoface.jpg' style='width:100%;'>";
					$evade=true;
				}
				elseif($Type_avion ==1 and $Pilotage_eni <50)
				{
					$intro.="<p>La formation ennemie refuse le combat et s'enfuit dans la direction opposée.</p>";
					$img="<img src='images/toofast.jpg' style='width:100%;'>";
					$Enis=0;
					$nav=true;
				}
				else
				{
					$intro.='<p>Vous engagez le combat contre votre adversaire, un <b>'.$nom_avioneni.'</b>.</p>';
					$mission3=true;
				}
			}
			else
			{
				if($VitAvioneni+$VitSup >=$VitAvion+$VitSup)
				{
					$intro.="<p>Votre adversaire est trop rapide pour vous, vous le perdez de vue.</p>";
					$img="<img src='images/toofast.jpg' style='width:100%;'>";
					$nav=true;
				}
				else
				{
					$intro.='<p>Vous vous approchez à portée de tir de votre adversaire et constatez qu\'il s\'agit d\'un <b>'.$nom_avioneni.'</b>, un appareil allié.</p>';
					$img="<img src='images/avions/vol".$avion_eni.".jpg' style='width:100%;'>";
					$nav=true;
				}
			}
		break;
		case 2:
			//Suivre votre leader / Protéger l'escorte.
			if($c_gaz <60 and $alt >6000)
			{
				$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
				$alt=5000+mt_rand(-1000,500);
				$VitAvion=GetSpeed("Avion",$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
			}
			if($war)
			{
				$cont_route=false;
				if($Mission_Type ==4)
					$txtl="<br>Vous protégez la formation de bombardiers à une altitude de ";
				else
				{					
					if($VitAvioneni+$VitSup > $VitAvion+$diff_alt+$VitSup or ($alt_avioneni >=$alt and $eni_chasseur and !$Nuit and $meteo >-50))
					{
						if($eni_chasseur or $Type_avioneni ==4)
						{
							$intro.='<p>L\'ennemi, un <b>'.$nom_avioneni.'</b>, fonce sur vous dans le but de vous intercepter!</p>';						
							$img=Afficher_Image('images/avions/pique'.$avion_eni.'.jpg','images/avions/vol'.$avion_eni.'.jpg',$nom_avioneni);
							//$skills.="<br>[Votre Vitesse : ".$VitAvion." à ".$alt."m d'altitude; Vitesse de l'adversaire : ".$VitAvioneni." à ".$alt_avioneni."m d'altitude]";
							if($alt_intercept >$Plafond_eni)
							{
								if($wingman)
								{
									if($Mission_Type ==3 or $Mission_Type ==7 or $Mission_Type ==17 or $Mission_Type ==26 or $Mission_Type ==31)
									{
										$txtl="<br>Votre leader vous fait signe d'attaquer.";
										$mission3=true;
									}
									else
									{
										$intro.="<br>L'altitude à laquelle vous évoluez empêche l'ennemi de vous intercepter.";
										$cont_route=true;
									}
								}
								else
								{
									$intro.="<br>L'altitude à laquelle vous évoluez empêche l'ennemi de vous intercepter.";
									$cont_route=true;
								}
							}
							else
								$evade=true;
						}
						else
						{
							$intro.="<br>L'ennemi continue sa route en vous évitant.";
							$cont_route=true;
						}
					}
					else
					{
						$intro.="<br>Vous distancez l'ennemi.";
						if($wingman)
						{
							if(($Mission_Type ==3 or $Mission_Type ==7 or $Mission_Type ==17 or $Mission_Type ==26 or $Mission_Type ==31) and $Type_avioneni !=1 and $Type_avioneni !=4 and $Type_avioneni !=12)
							{
								$intro.="<br>Votre leader vous fait signe d'attaquer.";
								$mission3=true;
							}
							else
								$cont_route=true;
						}
						else
						{
							if($Mission_Type ==7 or $Mission_Type ==17)
							{
								SetData("Pilote_PVP","S_Cible_Atk",99,"ID",$Pilote_pvp);
								$intro.="<br>Vous oubliez votre mission!";
							}
							$cont_route=true;
						}
					}
				}//Mission Intercept
			}
			else //ami
				$cont_route=true;			
			if($cont_route)
			{
				if($txtl)
					$alt_txt=$alt.'m';
				else
					$intro.="<br>Vous continuez votre route";
				$intro.=$txtl.$alt_txt;
				$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
				$nav=true;
			}	
			unset($cont_route);
		break;
		case 3:
			//Continuer votre route vers votre objectif à moyenne altitude.
			if($c_gaz <60 and $alt >6000)
			{
				$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
				$alt=5000+mt_rand(-1000,500);
				$VitAvion=GetSpeed("Avion",$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
			}
			if($war and ($Mission_Type ==7 or $Mission_Type ==17))
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote_PVP","S_Cible_Atk",99,"ID",$Pilote_pvp);
				$continue_route=true;
			}
			elseif($Mission_Type ==9)
			{
				$intro.="<br>Votre formation se porte à l'attaque!";
				$mission3=true;
			}
			else
				$continue_route=true;
		break;
		case 4:
			//Retourner à votre base.
			$_SESSION['done']=true;
			$retour_ok=false;
			$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
			if($Mission_Type ==4 and $Escorteb_nbr >0 and $retraite ==false)
			{
				$intro.="<br><br>Vous laissez votre escorte sans protection!";
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
						SetData("Pilote_PVP","S_Mission",3,"ID",$Pilote_pvp); //Passer en mode chasse pour éviter le menu d'escorte
					}
				}
			}
			if($VitAvioneni+$diff_alt+$VitSup > $VitAvion+$VitSup+$Stealth or ($alt_avioneni >$alt and $eni_chasseur and !$Nuit and $meteo >-50))
			{
				if($eni_chasseur or $Type_avioneni ==4)
				{
					if($alt_intercept <=$Plafond_eni)
					{
						$intro.='<br>Votre adversaire, un <b>'.$nom_avioneni.'</b>, se rapproche de vous et cherche à se placer dans vos 6 heures.';
						$img="<img src='images/visee.jpg' style='width:100%;'>";
						//$skills.="<br>[Votre Vitesse : ".$VitAvion." à ".$alt."m d'altitude; Vitesse de l'adversaire : ".$VitAvioneni." à ".$alt_avioneni."m d'altitude]";
						$evade=true;
					}
					else
					{
						$intro.="<br>L'altitude à laquelle vous évoluez empêche l'ennemi de vous intercepter.";
						$img="<img src='images/toofast.jpg' style='width:100%;'>";
						$retour_ok=true;
					}
				}
				else
				{
					$intro.="<br>L'ennemi continue sa route en vous évitant.";
					$retour_ok=true;
				}
			}
			else
			{
				$intro.="<br>Vous vous éloignez progressivement, retournant vers votre base.";
				$retour_ok=true;
			}
			if($retour_ok)
			{
				$Distance-=$chemin;
				$chemin=$Distance;
				$nav=true;
			}
		break;
		case 5:
			//Tenter de vous rapprocher en grimpant.
			$VitAAvion=$VitesseA/10;
			$essence-=round((abs($meteo)/10)+$Conso+10);
			if($war)
			{
				if($Mission_Type ==4 and $Escorteb_nbr >0)
				{
					$intro.="<br><br>Vous laissez votre escorte sans protection!";
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
							SetData("Pilote_PVP","S_Mission",3,"ID",$Pilote_pvp); //Passer en mode chasse pour éviter le menu d'escorte
						}
					}
				}
				if($alt_avioneni >$Plafond)
				{
					if($escorte_eni or $Type_avioneni ==1)
					{
						$intro.="<p>L'ennemi vous attendait et ne se laisse pas surprendre!</p>";
						$img="<img src='images/facetoface.jpg' style='width:100%;'>";
						$evade=true;
					}
					else
					{
						$intro.="<p>Votre adversaire vole à une altitude hors de votre atteinte.</p>";
						$img="<img src='images/toofast.jpg' style='width:100%;'>";
						$nav=true;
					}
				}
				else
				{
					if((($VitAvioneni*2)+$diff_alt+$VitSup+mt_rand(0,150) >= $VitAvion+$VitAAvion+$VitSup+$Stealth+mt_rand(0,$Tactique)) or ($alt_avioneni >=$alt and $eni_chasseur and !$Nuit and $meteo >-50))
					{
						if($eni_chasseur or $Type_avioneni ==4)
						{
							$intro.='<p>L\'ennemi, un <b>'.$nom_avioneni.'</b>, vous attendait et ne se laisse pas surprendre!</p>';
							$img="<img src='images/facetoface.jpg' style='width:100%;'>";
							$evade=true;
						}
						else
						{
							$intro.="<p>Votre adversaire est trop rapide pour vous, vous le perdez de vue.</p>";
							$img="<img src='images/toofast.jpg' style='width:100%;'>";
							$nav=true;
						}
					}
					else
					{
						$intro.='<p>Vous engagez le combat contre votre adversaire, un <b>'.$nom_avioneni.'</b>.</p>';
						$mission3=true;
					}
				}
			}
			else
			{
				if(($VitAvioneni+$VitSup >= $VitAvion+$VitSup) or ($alt_avioneni >$Plafond))
				{
					$intro.="<p>Votre adversaire parvient à vous distancer, vous le perdez de vue.</p>";
					$img="<img src='images/toofast.jpg' style='width:100%;'>";
					$nav=true;
				}
				else
				{
					$intro.='<p>Vous vous approchez à portée de tir de votre adversaire et constatez qu\'il s\'agit d\'un <b>'.$nom_avioneni.'</b>, un appareil allié.</p>';
					$img="<img src='images/avions/vol".$avion_eni.".jpg' style='width:100%;'>";
					$nav=true;
				}
			}
		break;
		case 6:
			//Tenter de vous approcher en bénéficiant de votre avantage en altitude.
			$essence-=round((abs($meteo)/10)+$Conso+5);
			if($war)
			{
				if($Mission_Type ==4 and $Escorteb_nbr >0)
				{
					$intro.="<br><br>Vous laissez votre escorte sans protection!";
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
							SetData("Pilote_PVP","S_Mission",3,"ID",$Pilote_pvp); //Passer en mode chasse pour éviter le menu d'escorte
						}
					}
				}
				$Injection=GetData("Moteur","ID",$Engine,"Injection");
				if($VitAvioneni+$diff_alt+$VitSup >=$VitesseP+$VitSup+$Stealth+($Injection*50))
				{
					if($eni_chasseur ==1 or $Type_avioneni ==4)
					{
						$intro.="<p>L'ennemi vous attendait et ne se laisse pas surprendre!</p>";
						$img="<img src='images/facetoface.jpg' style='width:100%;'>";
						$evade=true;
					}
					else
					{
						$intro.="<p>Votre adversaire est trop rapide pour vous, vous le perdez de vue.</p>";
						$img="<img src='images/toofast.jpg' style='width:100%;'>";
						$nav=true;
					}
				}
				else
				{
					$Tactique_eni=mt_rand(25,100);
					if($escorte_eni or $Tactique_eni >100)
					{
						$intro.="<p>L'ennemi vous attendait et ne se laisse pas surprendre!</p>";
						$img="<img src='images/facetoface.jpg' style='width:100%;'>";
						$evade=true;
					}
					else
					{
						$Dist_min_s=1500-($Tactique*5);
						$Dist_max_s=3000-($Tactique*10);
						$Angle_max_s=60-($Tactique/10);
						$Dist_shoot=mt_rand($Dist_min_s,$Dist_max_s);
						$Angle_shoot=mt_rand(0,$Angle_max_s);
						$alt=$alt_avioneni;
						$intro.='<br>Vous surprenez votre adversaire, un <b>'.$nom_avioneni.'</b>, et parvenez à vous placer en position de tir, à une distance d\'environ '.$Dist_shoot.' m, sous un angle de '.$Angle_shoot.'°';
						$shoot=true;
					}
				}
			}
			else
			{
				if($VitAvioneni+$VitSup >=$VitesseP+$diff_alt+$VitSup)
				{
					$intro.="<p>Votre adversaire est trop rapide pour vous, vous le perdez de vue.</p>";
					$img="<img src='images/toofast.jpg' style='width:100%;'>";
					if($Mission_Type ==9)
					{
						$intro.="<br>Vous échouez dans votre mission!";
						SetData("Pilote_PVP","S_Intercept_nbr",0,"ID",$Pilote_pvp);
					}
					$nav=true;
				}
				else
				{
					$intro.='<p>Vous vous approchez à portée de tir de votre adversaire et constatez qu\'il s\'agit d\'un <b>'.$nom_avioneni.'</b>, un appareil allié.</p>';
					$img="<img src='images/avions/vol".$avion_eni.".jpg' style='width:100%;'>";
					$nav=true;
				}
			}
		break;
		case 7:
			//Continuer votre route vers votre objectif à haute altitude.
			if($c_gaz <60)
			{
				$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
				$alt=5000+mt_rand(-1000,500);
			}
			else
				$alt=$Plafond-mt_rand(0,2000);
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
			if($Mission_Type ==7 or $Mission_Type ==17)
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote_PVP","S_Cible_Atk",99,"ID",$Pilote_pvp);
				$continue_route=true;
			}
			else
				$continue_route=true;
			if($alt <100)$alt=100;
			$VitAvion=GetSpeed("Avion",$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
		break;
		case 8:
			//Continuer votre route vers votre objectif à basse altitude.
			if($Mission_Type ==7 or $Mission_Type ==17)
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote_PVP","S_Cible_Atk",99,"ID",$Pilote_pvp);
				$alt=1000+mt_rand(-500,1000);
				$continue_route=true;
			}
			else
			{
				$alt=1000+mt_rand(-500,1000);
				$continue_route=true;
			}
			if($alt <100)$alt=100;
			$VitAvion=GetSpeed("Avion",$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
		break;
		case 9:
			//Votre escorte éloigne toute couverture
			$intro.="<br>L'escorte du ".$Escorte_nom." parvient à tenir l'ennemi à distance.";
			$VitAvioneni=0;
			$continue_route=true;
		break;
		case 10:
			//Continuer votre route vers votre objectif à très basse altitude.
			$alt=50;
			if($Mission_Type ==7 or $Mission_Type ==17)
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote_PVP","S_Cible_Atk",99,"ID",$Pilote_pvp);
				$continue_route=true;
			}
			else
			{
				$Zone=GetData("Lieu","ID",$Cible,"Zone");
				switch($Zone)
				{
					case 0:
						$zone_txt="prairie";
						$Malus_Reperer=0;
					break;
					case 1:
						$zone_txt="colline";
						$Malus_Reperer=10;
					break;
					case 2: case 9:
						$zone_txt="forêt";
						$Malus_Reperer=20;
					break;
					case 3:
						$zone_txt="colline boisée";
						$Malus_Reperer=50;
					break;
					case 4:
						$zone_txt="montagne";
						$Malus_Reperer=50;
					break;
					case 5:
						$zone_txt="montagne boisée";
						$Malus_Reperer=100;
					break;
					case 6:
						$zone_txt="vague";
						$Malus_Reperer=-$meteo;
					break;
					case 7:
						$zone_txt="maison";
						$Malus_Reperer=50;
					break;
					case 8:
						$zone_txt="dune";
						$Malus_Reperer=10;
					break;
					case 11:
						$zone_txt="marécage";
						$Malus_Reperer=10;
					break;
				}
				$ManAvion=GetMan("Avion",$avion,$alt,$HP,$moda,$malus_incident,$flaps);
				$VitAvion=GetSpeed("Avion",$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
				if(($Pilotage+($ManAvion/2)) >($Malus_Reperer+mt_rand(50,200)-$meteo+($VitAvion/5)))
				{
					$intro.="<p><b>Vous volez au ras des ".$zone_txt."s, ne devant votre survie qu'à votre virtuosité du pilotage...et à la chance!</b></p>";
					$continue_route=true;
				}
				else
				{
					$intro.="<p><b>Descendant dangereusement au plus près du sol, vous percutez une ".$zone_txt."!</b></p>";
					$continue_route=false;
					$end_mission=true;
				}
			}
		break;
		case 11:
			//Appeler la chasse à l'aide.
			if($c_gaz <60 and $alt >6000)
			{
				$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
				$alt=5000+mt_rand(-1000,500);
				$VitAvion=GetSpeed("Avion",$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
			}
			if($Mission_Type ==7 or $Mission_Type ==17)
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote_PVP","S_Cible_Atk",99,"ID",$Pilote_pvp);
				$continue_route=true;
			}
			else
				$continue_route=true;
		break;
		case 99:
			$intro.="<p>Votre avion se décompose littéralement en plein vol, la cellule ne résistant pas aux sollicitations du pilote.</p>";
			$continue_route=false;
			$end_mission=true;
		break;
	}	
	if($continue_route)
	{
		if($Mission_Type ==4)
			$alt_intercept=$Plafond_escorte;
		else
			$alt_intercept=$alt;
		$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
		if($war)
		{
			if($VitAvioneni+$VitSup >$VitAvion+$diff_alt+$VitSup+$Stealth or ($alt_avioneni >$alt and $eni_chasseur and !$Nuit and $meteo >-50))
			{
				if($eni_chasseur or $Type_avioneni ==4)
				{
					if($alt <100 and $Type_avion !=1)
					{
						$intro.="<br>L'altitude à laquelle vous évoluez empêche l'ennemi de vous intercepter.";
						$nav=true;					
					}
					elseif($alt_intercept <=$Plafond_eni)
					{
						$intro.='<br>Votre adversaire, un <b>'.$nom_avioneni.'</b>,se rapproche de vous et cherche à se placer dans vos 6 heures.';
						$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';
						$evade=true;
					}
					else
					{
						$intro.="<br>L'altitude à laquelle vous évoluez empêche l'ennemi de vous intercepter.";
						$nav=true;
					}
				}
				else
				{
					$intro.="<br>L'ennemi continue sa route en vous évitant.";
					$nav=true;
				}
			}
			else
			{
				$intro.="<br>Vous parvenez à distancer l'ennemi.";
				$img="<img src='images/toofast.jpg' style='width:100%;'>";
				//$skills.="<br>[Votre Vitesse : ".$VitAvion." à ".$alt."m d'altitude; Vitesse de l'adversaire : ".$VitAvioneni." à ".$alt_avioneni."m d'altitude]";
				$nav=true;
			}		
		}
		else
		{
			if(($VitAvioneni+$VitSup >$VitAvion+$VitSup) and !$Nuit)
			{
				$intro.='<br>Le <b>'.$nom_avioneni.'</b> se rapproche de vous et balance ses ailes en guise de salut.';
				$nav=true;
			}
			else
			{
				$intro.="<br>Vous continuez votre route en maintenant votre cap vers l'objectif.";
				$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
				$nav=true;
			}		
		}
	}
	//Toolbar
	$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,"Avion",$flaps,true);
	if($nav)
	{
		$retour_menu='';
		if(!$_SESSION['done'])$retour_menu='<Input type=\'Radio\' name=\'Action\' value=\'1\'>- Faire demi-tour et rentrer.<br>';
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Essence='$essence' WHERE ID='$Pilote_pvp'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission2_pvp-reset');
		mysqli_close($con);
		$Enis=0;
		$avion_eni=0;
		$_SESSION['missiondeux']=false;
		$titre="Navigation";
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
			<input type=\'hidden\' name=\'Mode\' value='.$Mode.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt,false,true).'
			'.GetSituation($Enis,$avion_eni,$nat_uniteni,$Leader,$Ailier,$avion,true).'
			<table class=\'table\'><tr><td align=\'left\'><Input type=\'Radio\' name=\'Action\' value=\'0\' checked>- Continuer vers votre objectif.<br>'.$retour_menu.'</td></tr></table>
			<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		//Eviter la génération du pilote ennemi, inutile
		$war=false;
	}	
	//Combat tournoyant ou acrobatie
	if($eni_chasseur or $Type_avioneni ==4)
		$choix2="<Input type='Radio' title='".GetMes('Aide_Avantage')."' name='Action' value='2'>- Tenter une manoeuvre pour reprendre l'avantage.<br>";
	else
		$choix2="<Input type='Radio' title='".GetMes('Aide_Avantage')."' name='Action' value='2'>- Effectuer une manuvre pour tenter d'attaquer l'ennemi par le flanc.<br>";
	if($mission3)
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
		if($Avion_Bombe and $Avion_Bombe_Nbr)
			$Alleger="<Input type='Radio' name='Action' value='18'>- Vider la soute pour alléger l'avion.<br>";
		else
			$Alleger='';
		if($S_Baby >0 and $essence <$Autonomie -$S_Baby)
			$Larguer="<Input type='Radio' name='Action' value='19'>- Larguer le réservoir largable pour alléger l'avion.<br>";
		else
			$Larguer='';
			//Seuls les chasseurs et chasseurs lourds attaquent
		if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
		{			
			$choix1="<Input type='Radio' name='Action' value='1'>- Chercher à vous placer dans ses 6 heures pour l'abattre.<br>";
			if($alt_avioneni >1000)
				$choix7="<Input type='Radio' name='Action' title='".GetMes('Aide_Coiffer')."' value='7'>- Chercher à prendre de l'altitude pour ensuite piquer sur l'ennemi.<br>";
			else
				$choix7='';
			$choix8="<Input type='Radio' name='Action' title='".GetMes('Aide_Frontale')."' value='8'>- Tenter une attaque frontale.<br>";
			//Attaque par le ventre
			if($alt_avioneni >1000 and $Tactique >50 and ($Type_avioneni ==2 or $Type_avioneni ==11))
				$Ventre="<Input type='Radio' name='Action' value='11'>- Tenter d'attaquer par le ventre en évitant le mitrailleur arrière.<br>";
			//Attaque coordonnée
			if($escorte_eni and $Escorte_nbr >0 and $Tactique >75 and $Reputation >999)
			{
				$Esc_txt="<Input type='Radio' name='Action' value='13'>- Se concentrer sur les avions escortés au détriment de l'escorte.<br>";
				$Esc_txt2="<Input type='Radio' name='Action' value='14'>- Se concentrer sur l'escorte au détriment des avions escortés.<br>";
			}
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
		SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
		$_SESSION['missiondeux']=false;
		$titre='Combat';
		$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';
		$mes.='<form action=\'index.php?view=mission3_pvp\' method=\'post\'>
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
			<input type=\'hidden\' name=\'Pilote_eni\' value='.$Pilote_eni.'>
			<input type=\'hidden\' name=\'Avion_db_eni\' value='.$Avion_db_eni.'>
			<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>
			<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
			<input type=\'hidden\' name=\'Mode\' value='.$Mode.'>
			'.ShowGaz($avion,$c_gaz,$flaps,$alt_avioneni,1,true).'
			'.GetSituation($Enis,$avion_eni,$nat_uniteni,$Leader,$Ailier,$avion,true).'
			<table class=\'table\'><tr><td align=\'left\'>'.$choix1.$choix7.$choix8.$choix2.$choix3.'
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'4\' checked>- Tenter de fuir le combat en vous lançant dans un piqué.<br>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
						<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Nuages').'\' value=\'5\'>- '.$choix5.'<br>'.$Ventre.$Esc_txt.$Esc_txt2.$Alleger.$Larguer.'
				</td></tr></table>
			<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
	}	
	if($evade)
	{
		if($Tactique >50 and $Acrobatie >50)
		{
			$Immelman="<Input type='Radio' name='Action' value='9'>- Tenter de fuir en effectuant un <i>Immelman inversé</i>.<br>";
			if($Type_avion ==3)
				$Rase_Motte="<Input type='Radio' name='Action' value='11'>- Tenter de fuir au ras du sol.<br>";
		}
		//Seuls les chasseurs et chasseurs lourds attaquent + options pour les bombardiers et avions d'attaque
		if($Equipage_Nbr >1 and $avion !=43 and $avion !=109)
		{
			$choix2='';
			$choix3='';
			$choix5="<Input type='Radio' name='Action' value='5'>- Maintenir votre cap, votre mitrailleur arrière se chargeant de l'adversaire.<br>";
			if(!$PVP and $Escorte_nbr)
				$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='20'>- Appeler votre escorte à l'aide<br>";
			else
				$choix6='';
			if($Formation >0 and $Radio_a)
				$choix13="<Input type='Radio' name='Action' value='13'>- Ordonner à la formation de concentrer le tir sur votre cible<br>";
		}
		else
		{
			$choix2='';
			$choix3='';
			$choix5="<Input type='Radio' name='Action' value='5'>- Vous désintéresser de l'adversaire et maintenir votre cap, atteindre l'objectif est plus important.<br>";
			if(!$PVP and $Escorte_nbr)
				$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='20'>- Appeler votre escorte à l'aide<br>";
			else
				$choix6='';
			$choix13='';
		}		
		if($eni_chasseur or $Type_avioneni ==4)
			$choix8='<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Barrique_Off').'\' value=\'8\'>- Tenter une manoeuvre pour forcer l\'adversaire à vous dépasser.<br>';
		else
			$choix8='';
		SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
		$_SESSION['missiondeux']=false;
		$titre="Combat";
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
		<input type=\'hidden\' name=\'Mode\' value='.$Mode.'>
		'.ShowGaz($avion,$c_gaz,$flaps,$alt,1,true).'
		'.GetSituation($Enis,$avion_eni,$nat_uniteni,$Leader,$Ailier,$avion,true).'
		<table class=\'table\'><tr><td align=\'left\'>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Degager').'\' value=\'1\' checked>- Tenter de manoeuvrer pour vous dégager de la ligne de tir de votre adversaire.<br>
					'.$choix2.$choix8.$choix3.$choix6.$choix5.$choix13.'
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Man').'\' value=\'4\'>- Tenter de manoeuvrer pour fuir.<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'7\'>- Tenter de fuir le combat en vous lançant dans un piqué.<br>'.$Immelman.$Rase_Motte.'</td>
				</tr></table>
		<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
	}
	if($shoot)
	{
		$Arme1=GetData("Armes","ID",$Arme1Avion,"Nom");
		if($Arme2Avion ==5 or $Arme2Avion ==0 or $Arme2Avion ==25 or $Arme2Avion ==26 or $Arme2Avion ==27)
			$DeuxArmes='';
		else
		{
			$Arme2=GetData("Armes","ID",$Arme2Avion,"Nom");
			$DeuxArmes='<Input type=\'Radio\' name=\'Action\' value=\'5\'>- Lâcher une courte rafale à l\'aide de votre '.$Arme2.' ('.$Mun2.' coups).<br>
			<Input type=\'Radio\' name=\'Action\' value=\'6\'>- Lâcher une longue rafale avec votre '.$Arme2.' ('.$Mun2.' coups).<br>
			<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Courte_Rafale').'\' value=\'7\'>- Lâcher une courte rafale à l\'aide de toutes vos armes de bord.<br>
			<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Longue_Rafale').'\' value=\'8\'>- Lâcher une longue rafale à l\'aide de toutes vos armes de bord.<br>';
		}
		SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
		$_SESSION['missiondeux']=false;
		$titre='Combat';
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
		<input type=\'hidden\' name=\'Mode\' value='.$Mode.'>
		'.ShowGaz($avion,$c_gaz,$flaps,$alt,1,true).'
		'.GetSituation($Enis,$avion_eni,$nat_uniteni,$Leader,$Ailier,$avion,true).'
		<table class=\'table\'><tr><td align=\'left\'>
				<Input type=\'Radio\' name=\'Action\' value=\'1\'>- Epargner votre adversaire.<br>
				<Input type=\'Radio\' name=\'Action\' value=\'2\'>- Vous rapprocher à la distance idéale pour faire un maximum de dégâts.<br>
				<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Barrique').'\' value=\'11\'>- Effectuer une manoeuvre pour vous rapprocher sans risquer de dépasser votre adversaire.<br>
				<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Courte_Rafale').'\' value=\'3\'>- Lâcher une courte rafale à l\'aide de votre '.$Arme1.' ('.$Mun1.' coups).<br>
				<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Longue_Rafale').'\' value=\'4\'>- Lâcher une longue rafale avec votre '.$Arme1.'('.$Mun1.' coups).<br>'.$DeuxArmes.'
				<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Rompre').'\' value=\'9\' checked>- Rompre le combat.<br></td>
			</tr></table>
		<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
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