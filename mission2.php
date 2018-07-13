<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
/*if($_SESSION['PlayerID'] ==1)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
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
$Unit_eni=Insec($_POST['Unit_eni']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $avion_eni >0 AND $_SESSION['missiondeux'] ==false AND $avion >0 AND $action >0)
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_rencontre.inc.php');
	$_SESSION['naviguer']=false;
	$_SESSION['tirer']=false;
	$_SESSION['finish']=false;
	$_SESSION['evader']=false;
	$_SESSION['missiontrois']=false;
	$_SESSION['missiondeux']=true;	
	$country=$_SESSION['country'];
	$PVP=$_SESSION['PVP'];
	$Saison=$_SESSION['Saison'];
	//$_SESSION['Distance']=$Distance;
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
	$con=dbconnecti();
	$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
	$result=mysqli_query($con,"SELECT S_HP,Unit,Acrobatie,Tactique,Pilotage,Vue,Avancement,Reputation,S_Avion_db,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,S_Baby,Simu,Pilote_eni,
	S_Nuit,S_Cible,S_Mission,S_Escorte_nbr,S_Longitude,S_Latitude,S_Escorte,S_Escorte_nom,S_Escorteb_nbr,S_Escorteb_nom,S_Equipage_Nbr,S_Escorteb,S_Leader,S_Formation,Slot2,Slot4,Sandbox,Admin,Skill_Ins
	FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission2-player');
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
			$Tactique=$data['Tactique'];
			$Acrobatie=$data['Acrobatie'];
			$Pilotage=$data['Pilotage'];
			$Avancement=$data['Avancement'];
			$Reputation=$data['Reputation'];
			$Avion_db=$data['S_Avion_db'];
			$Cible=$data['S_Cible'];
			$Nuit=$data['S_Nuit'];
			$Mission_Type=$data['S_Mission'];
			$Escorte_nbr=$data['S_Escorte_nbr'];
			$Escorte=$data['S_Escorte'];
			$Escorte_nom=$data['S_Escorte_nom'];
			$Escorteb=$data['S_Escorteb'];
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
			$Skill_Ins=$data['Skill_Ins'];
			$Admin=$data['Admin'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($Pilotage >50)$Pilotage=50;
	if($Acrobatie >50)$Acrobatie=50;
	if($Tactique >50)$Tactique=50;
	if($Vue >50)$Vue=50;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(30,$Skills_Pil))
			$Trompe_la_mort=50;
		if(in_array(31,$Skills_Pil))
			$Fou_Volant=true;
		if(in_array(42,$Skills_Pil))
			$FingerFour=true;
		if(in_array(44,$Skills_Pil))
			$SoleilDos=true;
		if(in_array(45,$Skills_Pil))
			$ExDeflect=true;
		if(in_array(46,$Skills_Pil))
			$MDeflect=true;
		if(in_array(48,$Skills_Pil))
			$Insaisissable=true;
	}
	if($Mission_Type ==103)
	{
		$war=true;
		$nat_uniteni=$country;
	}
	else
	{
		if($Unit_eni)
			$nat_uniteni=GetData("Unit","ID",$Unit_eni,"Pays");
		$war=IsWar($nat_uniteni,$country);
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Type,Robustesse,Puissance,Masse,ChargeAlaire,VitesseA,VitesseP,Plafond,Autonomie,ArmePrincipale,ArmeSecondaire,Engine,Radio,Camouflage,Baby,ManoeuvreB,ManoeuvreH FROM $Avion_db WHERE ID='$avion'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission2-avion');
	$result2=mysqli_query($con,"SELECT Type,Nom,ChargeAlaire,Plafond,Robustesse FROM Avion WHERE ID='$avion_eni'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission2-avioneni');
	$Base=mysqli_result(mysqli_query($con,"SELECT Base FROM Unit WHERE ID='$Unite'"),0);
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
			$ManB=$data['ManoeuvreB'];
			$ManH=$data['ManoeuvreH'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	//Avion_eni
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Type_avioneni=$data['Type'];
			$nom_avioneni=$data['Nom'];
			$Plafond_eni=$data['Plafond'];
			$Rob_eni=$data['Robustesse'];
		}
		mysqli_free_result($result2);
		unset($data);
	}	
	$avion_img=GetAvionImg($Avion_db,$avion);
	$Avion_db_eni="Avion";
	$HP_eni=$Rob_eni;			
	if($Type_avioneni ==1 or $Type_avioneni ==12)
		$eni_chasseur=true;
	if($Nuit and ($Camouflage ==4 or $Camouflage ==5))
		$Stealth=10;
	if($Reputation >25000 or $Avancement >50000 or $Pilotage >=150)
		$Grosbill=true;
	if($Mission_Type !=103)
	{
		if(!$Enis)
		{
			//Couverture
			if($Nuit)
			{
				/*$query="SELECT COUNT(*) FROM (SELECT DISTINCT j.ID FROM Pilote as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture_Nuit='$Cible'
						UNION ALL SELECT DISTINCT j.ID FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture_Nuit='$Cible') as Total";*/
				$query="SELECT COUNT(*) FROM Pilote_IA as j, Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture_Nuit='$Cible' AND j.Actif=1";
				$con=dbconnecti();
				$Enis=mysqli_result(mysqli_query($con,$query),0);
				//$Couv_Event=mysqli_result(mysqli_query($con,"SELECT Unit FROM Pilote as j,Pays as p WHERE j.Pays=p.ID AND j.Couverture_Nuit='$Cible' AND p.Faction<>'$Faction' GROUP BY j.Unit ORDER BY COUNT(*) DESC"),0);
				mysqli_close($con);
			}
			else
			{			
				/*$query="SELECT COUNT(*) FROM (SELECT DISTINCT j.ID FROM Pilote as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible'
						UNION ALL SELECT DISTINCT j.ID FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible') as Total";*/
				$query="SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Actif=1";
				$con=dbconnecti();
				$Enis=mysqli_result(mysqli_query($con,$query),0);
				//$Couv_Event=mysqli_result(mysqli_query($con,"SELECT Unit FROM Pilote as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' GROUP BY j.Unit ORDER BY COUNT(*) DESC"),0);
				mysqli_close($con);
			}
			/*if($Mission_Type !=7 and $Mission_Type !=9 and $Mission_Type !=3 and $Mission_Type !=26 and $Enis >(($Escorte_nbr+1)*2))
			{
				$intro.="<p><b>Le ciel est rempli d'avions ennemis couvrant la zone,vous obligeant à faire demi-tour</b></p>";
				$Action=4;
				$retraite=true;
			}
			else
			{
				$Enis-=mt_rand(0,$Escorte_nbr);
				if($Enis <1)
				{
					$Enis=0;
					$Action=9;
				}
			}*/
			if($Enis <1)
			{
				$Enis=0;
				$Action=9;
			}
			SetData("Pilote","enis",$Enis,"ID",$PlayerID);
		}
		elseif($Enis >4)
		{
			if($war and !$Nuit)
			{
				$query="SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction='$Faction' AND j.Actif=1 AND (j.Escorte='$Cible' OR j.Couverture='$Cible')";
				$con=dbconnecti();
				$Couv_PJ_nbr=mysqli_result(mysqli_query($con,$query),0);
				mysqli_close($con);
				if($Couv_PJ_nbr >0)
				{
					$Enis-=mt_rand(0,$Couv_PJ_nbr);
					if($Enis <4)$Enis=4;
					$intro.="<br>La chasse parvient à tenir une partie de la formation ennemie à distance.";
				}
			}
		}
	}
	if($Enis >4)
	{
		if($Reputation <500 or $Avancement <500 or $Pilotage <60)
			$Enis=4;
		elseif($Enis >12 and $Reputation <10000 and $Avancement <25000 and $Pilotage <150)
			$Enis=12;
	}
	//Boost
	if($c_gaz ==130)UpdateData("Pilote","Stress_Moteur",10,"ID",$PlayerID);
	if($Admin ==1)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote SET Stress_Moteur=0,Stress_Commandes=0 WHERE ID='$PlayerID'");
		mysqli_close($con);
	}
	//Malus avion touché
	if($HP <1)
		$action=99;
	else
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
		$Plafond=round($Plafond/$moda);
	}	
	//Incident, plus de chances d'arriver s'il fait chaud
	$malus_incident=1;
	$malus_incident_eni=1;
	if(mt_rand($meteo,100) >80)
	{
		$break=GetIncident($PlayerID,2,$Saison,0,$Avion_db,$avion,$c_gaz);
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
	$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
	$VitAvioneni=GetSpeed($Avion_db_eni,$avion_eni,$alt_avioneni,$meteo,1,$malus_incident_eni);
	if($Mission_Type ==4)
	{
		$Plafond_escorte=GetData("Avion","ID",$Escorteb,"Plafond");
		if($Plafond_escorte <$alt)
			$alt_esc=$Plafond_escorte;
		else
			$alt_esc=$alt;
		$VitEscorte=GetSpeed("Avion",$Escorteb,$alt,$meteo);
		if($VitAvion >$VitEscorte)$VitAvion=$VitEscorte;
		$alt_intercept=$Plafond_escorte;
	}
	else
		$alt_intercept=$alt;
	if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
	{
		$wingman=IsAilier($PlayerID,$Leader);
		if($Sandbox)
			$Ailier=GetData("Pilote","ID",$PlayerID,"S_Ailier");
		else
			$Ailier=GetData("Pilote","ID",$PlayerID,"Ailier");
	}
	if(!$PVP and $wingman)
	{
		$choix3="<Input type='Radio' name='Action' title='".GetMes('Aide_Prot_leader')."' value='3'>- Tenir le coup afin de protéger votre leader<br>";
		$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='6'>- Appeler votre leader à l'aide<br>";
		$txtl="<br>Votre leader vous demande de rester en formation et de maintenir le cap vers l'objectif à une altitude de ";
	}
	elseif(!$PVP and $Ailier)
	{
		$choix3="<Input type='Radio' name='Action' title='".GetMes('Aide_Prot_leader')."' value='3'>- Couvrir votre ailier<br>";
		$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='6'>- Demander à votre ailier d'engager le combat<br>";
		$txtl="<br>Suivant votre plan de vol, vous maintenez la formation vers l'objectif à une altitude de ";
		if($FingerFour)$Tactique+=25;
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
	if($Grosbill)$VitSup+=100;
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
					UpdateCarac($PlayerID,"Avancement",-5);
					UpdateCarac($PlayerID,"Reputation",-2);
					//UpdateCarac($PlayerID,"Tactique",-1);
					$Escort_Time=mt_rand(0,10);
					if($Escort_Time <3 or $Grosbill)
					{
						$intro.='<p>L\'ennemi a profité de votre absence pour descendre un <b>'.$Escorteb_nom.'</b> que vous escortiez !</p>';
						UpdateCarac($PlayerID,"Moral",-1);
						UpdateCarac($PlayerID,"Reputation",-1);
						UpdateCarac($PlayerID,"Avancement",-1);
						$Escorteb_nbr-=1;
						if($Escorteb_nbr <1 and $_SESSION['done'] ==false)
						{
							$intro.="<p><b>Toute la formation que vous escortiez a été abattue! Vous échouez dans votre mission !</b><p>";
							$_SESSION['done']=true;
							$chemin=$Distance;
							SetData("Pilote","S_Mission",3,"ID",$PlayerID); //Passer en mode chasse pour éviter le menu d'escorte
							UpdateCarac($PlayerID,"Moral",-10);
							UpdateCarac($PlayerID,"Reputation",-10);
							UpdateCarac($PlayerID,"Avancement",-10);
							UpdateData("Unit","Reputation",-10,"ID",$Unite,0,2);
						}
					}
				}
				if($VitAvioneni+$diff_alt+$VitSup >=$VitAvion+$VitSup+$Stealth+$SoleilDos or ($alt_avioneni >=$alt and $eni_chasseur and !$Nuit and $meteo >-50))
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
						if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-1);
						if($Mission_Type ==9)
						{
							$intro.="<br>Vous échouez dans votre mission!";
							UpdateCarac($PlayerID,"Avancement",-5);
							UpdateCarac($PlayerID,"Reputation",-5);
							UpdateCarac($PlayerID,"Moral",-10);
							//UpdateCarac($PlayerID,"Tactique",-1);
							UpdateData("Unit","Reputation",-5,"ID",$Unite,0,2);
							SetData("Pilote","S_Intercept_nbr",0,"ID",$PlayerID);
						}
						$nav=true;
					}
				}
				elseif($Enis >2)
				{
					$intro.="<p>L'ennemi vous attendait et ne se laisse pas surprendre!</p>";
					$img="<img src='images/facetoface.jpg' style='width:100%;'>";
					$evade=true;
				}
				elseif($Type_avion ==1 and GetFlee($PlayerID,$Pilote_eni))
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
				if($VitAvioneni+$VitSup >=$VitAvion+$VitSup+$SoleilDos)
				{
					$intro.="<p>Votre adversaire est trop rapide pour vous, vous le perdez de vue.</p>";
					$img="<img src='images/toofast.jpg' style='width:100%;'>";
					if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-1);
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
				$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
			}
			if($war)
			{
				$cont_route=false;
				if($wingman and !$Sandbox)
				{
					UpdateCarac($PlayerID,"Avancement",1);
					UpdateCarac($PlayerID,"Reputation",1);
					//UpdateCarac($PlayerID,"Tactique",1);
					$txtl="<br>Votre leader vous donne l'ordre d'ignorer cet ennemi et de reprendre la formation vers l'objectif à une altitude de ";
				}
				elseif($Mission_Type ==4)
				{
					UpdateCarac($PlayerID,"Avancement",2);
					UpdateCarac($PlayerID,"Reputation",1);
					//UpdateCarac($PlayerID,"Tactique",1);
					UpdateCarac($PlayerID,"Courage",1);
					$txtl="<br>Vous protégez la formation de bombardiers à une altitude de ";
				}				
				if($Mission_Type ==9 or $Mission_Type ==103)
				{
					$intro.="<br>Votre leader vous fait signe d'attaquer.";
					$mission3=true;
				}
				else
				{					
					if($VitAvioneni+$VitSup >$VitAvion+$diff_alt+$VitSup+$SoleilDos or ($alt_avioneni >=$alt and $eni_chasseur and !$Nuit and $meteo >-50))
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
								SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);
								UpdateCarac($PlayerID,"Avancement",-10);
								UpdateCarac($PlayerID,"Reputation",-5);
								UpdateCarac($PlayerID,"Courage",-5);
								//UpdateCarac($PlayerID,"Tactique",-1);
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
				$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
			}
			if($war and ($Mission_Type ==7 or $Mission_Type ==17))
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);
				UpdateCarac($PlayerID,"Avancement",-10);
				UpdateCarac($PlayerID,"Reputation",-5);
				if($Enis <8 or $Grosbill)
				{
					UpdateCarac($PlayerID,"Courage",-5);
					//UpdateCarac($PlayerID,"Tactique",-1);
				}
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
				UpdateCarac($PlayerID,"Avancement",-5);
				UpdateCarac($PlayerID,"Reputation",-2);
				//UpdateCarac($PlayerID,"Tactique",-1);
				UpdateData("Unit","Reputation",-2,"ID",$Unite,0,2);
				$Escort_Time=mt_rand(0,10);
				if($Escort_Time <3 or $Grosbill)
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
						SetData("Pilote","S_Mission",3,"ID",$PlayerID); //Passer en mode chasse pour éviter le menu d'escorte
						UpdateCarac($PlayerID,"Moral",-10);
						UpdateCarac($PlayerID,"Reputation",-10);
						UpdateCarac($PlayerID,"Avancement",-10);
						UpdateData("Unit","Reputation",-10,"ID",$Unite,0,2);
					}
				}
			}
			if($Mission_Type ==9)
			{
				$intro.="<br>Vous échouez dans votre mission!";
				UpdateCarac($PlayerID,"Avancement",-10);
				UpdateCarac($PlayerID,"Reputation",-5);
				UpdateCarac($PlayerID,"Moral",-10);
				//UpdateCarac($PlayerID,"Tactique",-1);
				UpdateData("Unit","Reputation",-5,"ID",$Unite,0,2);
				SetData("Pilote","S_Intercept_nbr",0,"ID",$PlayerID);
			}
			elseif($Mission_Type ==103)
			{
				$intro.="<br>Vous échouez dans votre mission!";
				UpdateCarac($PlayerID,"Avancement",-5);
				UpdateCarac($PlayerID,"Reputation",-5);
			}
			if($war)
			{
				if(($VitAvioneni+$diff_alt+$VitSup >$VitAvion+$VitSup+$Stealth+$SoleilDos) or ($alt_avioneni >$alt and $eni_chasseur and !$Nuit and $meteo >-50))
				{
					if($eni_chasseur or $Type_avioneni ==4)
					{
						if($alt_intercept <=$Plafond_eni)
						{
							$intro.='<br>Votre adversaire, un <b>'.$nom_avioneni.'</b>,se rapproche de vous et cherche à se placer dans vos 6 heures.';
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
			}
			else
			{
				if($VitAvioneni+$VitSup >$VitAvion+$VitSup)
				{
					$intro.='<br>Le <b>'.$nom_avioneni.'</b> se rapproche de vous et balance ses ailes en guise de salut.';
					if(!$Sandbox)
					{
						UpdateCarac($PlayerID,"Courage",1);
						UpdateCarac($PlayerID,"Moral",1);
					}
					$retour_ok=true;
				}
				else
				{
					$intro.="Vous continuez votre route, retournant vers votre base.";
					$retour_ok=true;
				}		
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
					UpdateCarac($PlayerID,"Avancement",-5);
					UpdateCarac($PlayerID,"Reputation",-2);
					//UpdateCarac($PlayerID,"Tactique",-1);
					$Escort_Time=mt_rand(0,10);
					if($Escort_Time <3 or $Grosbill)
					{
						$intro.='<p>L\'ennemi a profité de votre absence pour descendre un <b>'.$Escorteb_nom.'</b> que vous escortiez !</p>';
						UpdateCarac($PlayerID,"Moral",-1);
						UpdateCarac($PlayerID,"Reputation",-1);
						UpdateCarac($PlayerID,"Avancement",-1);
						$Escorteb_nbr-=1;
						if($Escorteb_nbr <1 and $_SESSION['done'] ==false)
						{
							$intro.="<p><b>Toute la formation que vous escortiez a été abattue! Vous échouez dans votre mission !</b><p>";
							$_SESSION['done']=true;
							$chemin=$Distance;
							SetData("Pilote","S_Mission",3,"ID",$PlayerID); //Passer en mode chasse pour éviter le menu d'escorte
							UpdateCarac($PlayerID,"Moral",-10);
							UpdateCarac($PlayerID,"Reputation",-10);
							UpdateCarac($PlayerID,"Avancement",-10);
							UpdateData("Unit","Reputation",-10,"ID",$Unite);
						}
					}
				}
				//$skills.="[Votre Vitesse : ".$VitAvion." à ".$alt."m d'altitude; Vitesse de l'adversaire : ".$VitAvioneni." à ".$alt_avioneni."m d'altitude]";
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
						if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-1);
						$nav=true;
					}
				}
				else
				{
					if((($VitAvioneni*2)+$diff_alt+$VitSup+mt_rand(0,150) >=$VitAvion+$VitAAvion+$VitSup+$Stealth+mt_rand(0,$Tactique)+$SoleilDos) or ($alt_avioneni >=$alt and $eni_chasseur and !$Nuit and $meteo >-50))
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
							if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-1);
							if($Mission_Type ==9)
							{
								$intro.="<br>Vous échouez dans votre mission!";
								UpdateCarac($PlayerID,"Avancement",-10);
								UpdateCarac($PlayerID,"Reputation",-5);
								UpdateCarac($PlayerID,"Moral",-10);
								//UpdateCarac($PlayerID,"Tactique",-1);
								UpdateData("Unit","Reputation",-5,"ID",$Unite,0,2);
								SetData("Pilote","S_Intercept_nbr",0,"ID",$PlayerID);
							}
							$nav=true;
						}
					}
					elseif($Type_avion ==1 and GetFlee($PlayerID,$Pilote_eni))
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
			}
			else
			{
				if(($VitAvioneni+$VitSup >= $VitAvion+$VitSup+$SoleilDos) or ($alt_avioneni >$Plafond))
				{
					$intro.="<p>Votre adversaire parvient à vous distancer, vous le perdez de vue.</p>";
					$img="<img src='images/toofast.jpg' style='width:100%;'>";
					if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-1);
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
					UpdateCarac($PlayerID,"Avancement",-5);
					UpdateCarac($PlayerID,"Reputation",-2);
					//UpdateCarac($PlayerID,"Tactique",-1);
					$Escort_Time=mt_rand(0,10);
					if($Escort_Time <3 or $Grosbill)
					{
						$intro.='<p>L\'ennemi a profité de votre absence pour descendre un <b>'.$Escorteb_nom.'</b> que vous escortiez !</p>';
						UpdateCarac($PlayerID,"Moral",-1);
						UpdateCarac($PlayerID,"Reputation",-1);
						UpdateCarac($PlayerID,"Avancement",-1);
						$Escorteb_nbr-=1;
						if($Escorteb_nbr <1 and $_SESSION['done'] ==false)
						{
							$intro.="<p><b>Toute la formation que vous escortiez a été abattue! Vous échouez dans votre mission !</b><p>";
							$_SESSION['done']=true;
							$chemin=$Distance;
							SetData("Pilote","S_Mission",3,"ID",$PlayerID); //Passer en mode chasse pour éviter le menu d'escorte
							UpdateCarac($PlayerID,"Moral",-10);
							UpdateCarac($PlayerID,"Reputation",-10);
							UpdateCarac($PlayerID,"Avancement",-10,0,2);
							UpdateData("Unit","Reputation",-10,"ID",$Unite);
						}
					}
				}
				$Injection=GetData("Moteur","ID",$Engine,"Injection");
				if($VitAvioneni+$diff_alt+$VitSup >=$VitesseP+$VitSup+$Stealth+($Injection*50)+$SoleilDos)
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
						if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-1);
						if($Mission_Type ==9)
						{
							$intro.="<br>Vous échouez dans votre mission!";
							UpdateCarac($PlayerID,"Avancement",-10);
							UpdateCarac($PlayerID,"Reputation",-5);
							UpdateCarac($PlayerID,"Moral",-10);
							//UpdateCarac($PlayerID,"Tactique",-1);
							UpdateData("Unit","Reputation",-5,"ID",$Unite,0,2);
							SetData("Pilote","S_Intercept_nbr",0,"ID",$PlayerID);
						}
						$nav=true;
					}
				}
				else
				{
					if($Pilote_eni)$Tactique_eni=GetData("Pilote_IA","ID",$Pilote_eni,"Tactique");
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
						if($MDeflect)$Angle_shoot-=5;
						elseif($ExDeflect)$Angle_shoot-=2;
						if($Angle_shoot<0)$Angle_shoot=0;
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
					if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-1);
					if($Mission_Type ==9)
					{
						$intro.="<br>Vous échouez dans votre mission!";
						UpdateCarac($PlayerID,"Avancement",-10);
						UpdateCarac($PlayerID,"Reputation",-5);
						UpdateCarac($PlayerID,"Moral",-10);
						//UpdateCarac($PlayerID,"Tactique",-1);
						UpdateData("Unit","Reputation",-5,"ID",$Unite,0,2);
						SetData("Pilote","S_Intercept_nbr",0,"ID",$PlayerID);
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
			if($war and ($Mission_Type ==7 or $Mission_Type ==17))
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);
				UpdateCarac($PlayerID,"Avancement",-10);
				UpdateCarac($PlayerID,"Reputation",-5);
				if($Enis <8 or $Grosbill)
				{
					UpdateCarac($PlayerID,"Courage",-5);
					//UpdateCarac($PlayerID,"Tactique",-1);
				}
				$continue_route=true;
			}
			elseif($Mission_Type ==9)
			{
				$intro.="<br>Votre formation se porte à l'attaque!";
				$mission3=true;
			}
			else
				$continue_route=true;
			if($alt <100)$alt=100;
			$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
		break;
		case 8:
			//Continuer votre route vers votre objectif à basse altitude.
			if($war and ($Mission_Type ==7 or $Mission_Type ==17))
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);
				UpdateCarac($PlayerID,"Avancement",-10);
				UpdateCarac($PlayerID,"Reputation",-5);
				if($Enis <8 or $Grosbill)
				{
					UpdateCarac($PlayerID,"Courage",-5);
					//UpdateCarac($PlayerID,"Tactique",-1);
				}
				$alt=1000+mt_rand(-500,1000);
				$continue_route=true;
			}
			elseif($Mission_Type ==9)
			{
				$intro.="<br>Votre formation se porte à l'attaque!";
				$mission3=true;
			}
			else
			{
				$alt=1000+mt_rand(-500,1000);
				$continue_route=true;
			}
			if($alt <100)$alt=100;
			$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
		break;
		case 9:
			//Votre escorte éloigne toute couverture
			if(!$Sandbox and $Mission_Type !=103)AddEvent($Avion_db,85,$avion,$PlayerID,$Escorte,$Cible,$Couv_Event);
			$intro.="<br>L'escorte du ".$Escorte_nom." parvient à tenir l'ennemi à distance.";
			$VitAvioneni=0;
			$continue_route=true;
		break;
		case 10:
			//Continuer votre route vers votre objectif à très basse altitude.
			$alt=50;
			if($war and ($Mission_Type ==7 or $Mission_Type ==17))
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);
				UpdateCarac($PlayerID,"Avancement",-10);
				UpdateCarac($PlayerID,"Reputation",-5);
				if($Enis <8 or $Grosbill)
				{
					UpdateCarac($PlayerID,"Courage",-5);
					//UpdateCarac($PlayerID,"Tactique",-1);
				}
				$continue_route=true;
			}
			elseif($Mission_Type ==9)
			{
				$intro.="<br>Votre formation se porte à l'attaque!";
				$mission3=true;
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
				$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,$Sandbox,$Pilotage);
				$ManAvion=GetMano($ManH,$ManB,$HPmax,$HP,$alt,$moda,$malus_incident,$flaps);
				$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
				if(($Pilotage+$Trompe_la_mort+($ManAvion/2)) >($Malus_Reperer+mt_rand(50,200)-$meteo+($VitAvion/5)))
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
			if($Mission_Type !=103 and !$Sandbox)
			{
				$con=dbconnecti();
				$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j, Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Base' AND j.Actif=1"),0);
				$Escorte=mysqli_result(mysqli_query($con,"SELECT j.Unit FROM Pilote_IA as j, Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Base' AND j.Actif=1 ORDER BY RAND() LIMIT 1"),0);
				$Escorte_nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Unit WHERE ID='$Escorte'"),0);
				if($Escorte_nbr >0)
					$reset=mysqli_query($con,"UPDATE Pilote SET S_Escorte_nbr='$Escorte_nbr',S_Escorte_nom='$Escorte_nom',S_Escorte='$Escorte' WHERE ID='$PlayerID'");
				mysqli_close($con);
				if($Escorte_nbr)AddEvent($Avion_db,95,$avion,$PlayerID,$Escorte,$Base,$Escorte_nbr);				
			}
			if($c_gaz <60 and $alt >6000)
			{
				$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
				$alt=5000+mt_rand(-1000,500);
				$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$c_gaz,$flaps);
			}
			if($war and ($Mission_Type ==7 or $Mission_Type ==17))
			{
				$intro.="<br>Vous oubliez votre mission!";
				SetData("Pilote","S_Cible_Atk",99,"ID",$PlayerID);
				UpdateCarac($PlayerID,"Avancement",-10);
				UpdateCarac($PlayerID,"Reputation",-5);
				if($Enis <8 or $Grosbill){
					UpdateCarac($PlayerID,"Courage",-5);
					//UpdateCarac($PlayerID,"Tactique",-1);
				}
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
					elseif($alt_intercept <= $Plafond_eni)
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
				if(!$Sandbox)
				{
					UpdateCarac($PlayerID,"Courage",1);
					UpdateCarac($PlayerID,"Moral",1);
				}
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
	$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
	if($nav)
	{
		$retour_menu='';
		if(!$_SESSION['done'])$retour_menu='<Input type=\'Radio\' name=\'Action\' value=\'1\'>- Faire demi-tour et rentrer.<br>';
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0,S_Essence='$essence' WHERE ID='$PlayerID'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission2-reset');
		mysqli_close($con);
		$Enis=0;
		$avion_eni=0;
		$_SESSION['missiondeux']=false;
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
			'.GetSituation($Enis,$avion_eni,$nat_uniteni,$Leader,$Ailier,$avion).'
			<table class=\'table\'><tr><td align=\'left\'><Input type=\'Radio\' name=\'Action\' value=\'0\' checked>- Continuer vers votre objectif.<br>'.$retour_menu.'</td></tr></table>
			<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
		//Eviter la génération du pilote ennemi, inutile
		$war=false;
	}	
	/*if($war and !$PVP and !$Sandbox)
		AddEvent($Avion_db,1,$avion,$PlayerID,$Unite,$Cible,$avion_eni,$Nuit);*/
	//Combat tournoyant ou acrobatie
	if($eni_chasseur or $Type_avioneni ==4)
		$choix2="<Input type='Radio' title='".GetMes('Aide_Avantage')."' name='Action' value='2'>- Tenter une manoeuvre pour reprendre l'avantage.<br>";
	else
		$choix2="<Input type='Radio' title='".GetMes('Aide_Avantage')."' name='Action' value='2'>- Effectuer une manœuvre pour tenter d'attaquer l'ennemi par le flanc.<br>";
	//$Type_avion=GetData($Avion_db,"ID",$avion,"Type");
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
				$choix7="";
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
		SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
		$_SESSION['missiondeux']=false;
		$titre='Combat';
		$img='<img src=\'images/avions/vol'.$avion_eni.'.jpg\' style=\'width:100%;\'>';
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
			'.ShowGaz($avion,$c_gaz,$flaps,$alt_avioneni,1).'
			'.GetSituation($Enis,$avion_eni,$nat_uniteni,$Leader,$Ailier,$avion).'
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
			if($Insaisissable and !$Skill_Ins)
				$Rase_Motte="<Input type='Radio' name='Action' value='14'>- Tenter de fuir [en utilisant votre compétence Insaisissable].<br>";
			if($Fou_Volant)
				$Rase_Motte="<Input type='Radio' name='Action' value='11'>- Tenter de fuir [en utilisant votre compétence Fou Volant].<br>";
			elseif($Type_avion ==3)
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
			if($Formation and !IsAilier($PlayerID,$Leader) and $Tactique >75 and $Radio_a)
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
		SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
		$_SESSION['missiondeux']=false;
		$titre='Combat';
		$mes.='<form action=\'evade.php\' method=\'post\'>
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
		'.GetSituation($Enis,$avion_eni,$nat_uniteni,$Leader,$Ailier,$avion).'
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
			$DeuxArmes="";
		else
		{
			$Arme2=GetData("Armes","ID",$Arme2Avion,"Nom");
			$DeuxArmes='<Input type=\'Radio\' name=\'Action\' value=\'5\'>- Lâcher une courte rafale à l\'aide de votre '.$Arme2.' ('.$Mun2.' coups).<br>
			<Input type=\'Radio\' name=\'Action\' value=\'6\'>- Lâcher une longue rafale avec votre '.$Arme2.' ('.$Mun2.' coups).<br>
			<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Courte_Rafale').'\' value=\'7\'>- Lâcher une courte rafale à l\'aide de toutes vos armes de bord.<br>
			<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Longue_Rafale').'\' value=\'8\'>- Lâcher une longue rafale à l\'aide de toutes vos armes de bord.<br>';
		}
		SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
		$_SESSION['missiondeux']=false;
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
		'.GetSituation($Enis,$avion_eni,$nat_uniteni,$Leader,$Ailier,$avion).'
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
		include_once('./end_mission.php');
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