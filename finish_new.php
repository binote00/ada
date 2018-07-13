<?php
require_once('./jfv_inc_sessions.php');
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
$Armet=Insec($_POST['Armet']);
$Rafalet=Insec($_POST['Rafalet']);
$Viset=Insec($_POST['Viset']);
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$meteo=Insec($_POST['Meteo']);
$avion=Insec($_POST['Avion']);
$avion_eni=Insec($_POST['Avioneni']);
$alt=Insec($_POST['Alt']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Dist_shoot=Insec($_POST['Dist_shoot']);
$Angle_shoot=Insec($_POST['Angle_shoot']);
$HP_eni=Insec($_POST['HP_eni']);
$Puissance=Insec($_POST['Puissance']);
$Enis=Insec($_POST['Enis']);
$Unit_eni=Insec($_POST['Unit_eni']);
$Pilote_eni=Insec($_POST['Pilote_eni']);
$Avion_db_eni=Insec($_POST['Avion_db_eni']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	$_SESSION['tirer']=false;
	$_SESSION['missiondeux']=false;
	$_SESSION['missiontrois']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['evader']=false;
	//$_SESSION['finish']=true;		
	$Degats=0;
	$end_shoot=false;
	$cont_shoot=false;
	$nav=false;
	$mission3=false;
	$mitrailleur=false;
	$evade=false;
	$panne_seche=false;
	$end_mission=false;
	$Mg_crit=false;
	$PVP=$_SESSION['PVP'];
	$country=$_SESSION['country'];
	$Chk_finish=$_SESSION['finish'];
	if($Chk_finish)
	{
		$intro="<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>";
		UpdateCarac($PlayerID,"Free",-1);
		MoveCredits($PlayerID,90,-1);
		UpdateCarac($PlayerID,"Reputation",-10);
		UpdateCarac($PlayerID,"Avancement",-10);
		mail("binote@hotmail.com","Aube des Aigles: Init Mission F5 (finish) : ".$PlayerID , "Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Ailier,S_Ailier,Equipage,Pilotage,Acrobatie,Tactique,Tir,Vue,Courage,S_Avion_db,S_Avion_Bombe,S_Avion_Bombe_Nbr,Simu,S_Essence,S_Blindage,Degats_Max,
	S_Nuit,S_Cible,S_Mission,S_Engine_Nbr,S_Longitude,S_Latitude,S_Avion_Mun,S_Equipage_Nbr,S_Leader,Slot1,Slot4,Slot7,Slot9,Slot11,Sandbox,S_Baby,Admin,Skill_Ins FROM Pilote WHERE ID='$PlayerID'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : finish-player');
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
			$Courage=$data['Courage'];
			$Avion_db=$data['S_Avion_db'];
			$Nuit=$data['S_Nuit'];
			$Cible=$data['S_Cible'];
			$Mission_Type=$data['S_Mission'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Avion_Mun=$data['S_Avion_Mun'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Leader=$data['S_Leader'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Avion_Bombe_Nbr=$data['S_Avion_Bombe_Nbr'];
			$S_Blindage=$data['S_Blindage'];
			$Slot1=$data['Slot1'];
			$Slot4=$data['Slot4'];
			$Slot7=$data['Slot7'];
			$Slot9=$data['Slot9'];
			$Slot11=$data['Slot11'];
			$Degats_Max=$data['Degats_Max'];
			$S_Baby=$data['S_Baby'];
			$Admin=$data['Admin'];
			$Simu=$data['Simu'];
			$Skill_Ins=$data['Skill_Ins'];
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
	$Steady=1;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(31,$Skills_Pil))
			$Fou_Volant=true;
		if(in_array(36,$Skills_Pil))
			$SixSens=25;
		if(in_array(39,$Skills_Pil))
			$Steady=1.1;
		if(in_array(42,$Skills_Pil))
			$FingerFour=true;
		if(in_array(48,$Skills_Pil))
			$Insaisissable=true;
	}
	if($Equipage)$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");		
	if($Slot11 ==69)$Courage+=50;	
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
	if($c_gaz ==130)
		UpdateData("Pilote","Stress_Moteur",10,"ID",$PlayerID);	
	if($essence <1)
		$panne_seche=true;
	elseif($PVP and $HP <1)
	{
		$intro.="<p>Une rafale transforme votre appareil en passoire, ne vous laissant pas d'autre choix que de sauter en parachute!</p>";
		$end_mission=true;
		$_SESSION['Parachute']=true;
	}
	elseif($Enis <1)
	{
		$intro.="<p>Votre adversaire parvient à s'enfuir!</p>";
		$img=Afficher_Image("images/epargner.jpg","images/epargner.jpg","Epargner");
		$chemin=0;
		$nav=true;
	}
	else
	{
		$con=dbconnecti();
		$Pays_eni=mysqli_result(mysqli_query($con,"SELECT Pays FROM Unit WHERE ID='$Unit_eni'"),0);
		$result=mysqli_query($con,"SELECT Robustesse,Masse,ArmePrincipale,Arme1_Nbr,ArmeSecondaire,Arme2_Nbr,Blindage,Type,Radio,Autonomie,Baby,ManoeuvreB,ManoeuvreH,Maniabilite FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : finish-avion');
		$result2=mysqli_query($con,"SELECT Nom,Type,Robustesse,ArmePrincipale,ArmeSecondaire,ArmeArriere,Blindage,ManoeuvreB,ManoeuvreH,Maniabilite FROM $Avion_db_eni WHERE ID='$avion_eni'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : finish-avioneni');
		$Vic=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Joueur_win='$PlayerID' AND PVP<>1 AND DATE(Date)=DATE(NOW())"),0);
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$HPmax=$data['Robustesse'];
				$Arme1Avion=$data['ArmePrincipale'];
				$Arme2Avion=$data['ArmeSecondaire'];
				$Arme1Avion_nbr=$data['Arme1_Nbr'];
				$Arme2Avion_nbr=$data['Arme2_Nbr'];
				$Blindage=$data['Blindage'];
				$Type_avion=$data['Type'];
				$Masse=$data['Masse'];
				$Autonomie=$data['Autonomie'];
				$Baby=$data['Baby'];
				$Radio_a=$data['Radio'];
				$ManB=$data['ManoeuvreB'];
				$ManH=$data['ManoeuvreH'];
				$Mani=$data['Maniabilite'];
			}
			mysqli_free_result($result);
		}
		//GetData Avion_eni
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$nom_avioneni=$data['Nom'];
				$Type_avioneni=$data['Type'];
				$HPmax_eni=$data['Robustesse'];
				$Arme1Avion_eni=$data['ArmePrincipale'];
				$Arme2Avion_eni=$data['ArmeSecondaire'];
				$Arme3Avion_eni=$data['ArmeArriere'];
				$Blindage_eni=$data['Blindage'];
				$ManBeni=$data['ManoeuvreB'];
				$ManHeni=$data['ManoeuvreH'];
				$Manieni=$data['Maniabilite'];
			}
			mysqli_free_result($result2);
		}
		//GetData Armes
		//$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Calibre,Degats,Multi,Portee FROM Armes WHERE ID='$Arme1Avion'");
		$result2=mysqli_query($con,"SELECT Nom,Calibre,Degats,Multi,Portee FROM Armes WHERE ID='$Arme2Avion'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Arme1=$data['Nom'];
				$Calibre1=$data['Calibre'];
				$Arme1Avion_Dg=$data['Degats'];
				$Arme1Avion_Multi=$data['Multi'];
				$Arme1Avion_Range=$data['Portee'];
			}
			mysqli_free_result($result);
		}
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Arme2=$data['Nom'];
				$Calibre2=$data['Calibre'];
				$Arme2Avion_Dg=$data['Degats'];
				$Arme2Avion_Multi=$data['Multi'];
				$Arme2Avion_Range=$data['Portee'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		if(!$Blindage)
		{
			$Blindage=$S_Blindage;
			if(!$Blindage)$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
		}
		$avion_img=GetAvionImg($Avion_db,$avion);
		$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,$Sandbox,$Pilotage);
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
		//Malus avion_eni touché
		if(!$HP_eni)$HP_eni=$HPmax_eni;
		$moda_eni=$HPmax_eni/$HP_eni;		
		$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,$alt,$PlayerID,$Unite);
		$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
		$ManAvion=GetMano($ManH,$ManB,$HPmax,$HP,$alt,$moda,1,$flaps);
		$ManiAvion=GetMani($Mani,$HPmax,$HP,$moda,1,$flaps);
		$StabAvion=GetStab($Avion_db,$avion,$HP,$moda)*$Steady;
		$ManAvion_eni=GetMano($ManHeni,$ManBeni,$HPmax_eni,$HP_eni,$alt,$moda_eni);
		$VitAvioneni=GetSpeed($Avion_db_eni,$avion_eni,$alt,$meteo,$moda_eni);
		$Vis_eni=GetVis($Avion_db_eni,$avion_eni,$Cible,$meteo,$alt,$alt);
		//$PuissAvioneni=GetPuissance("Avion",$avion_eni,$alt,$HP_eni,$moda_eni);
		if($PVP)
			$Pilote_db="Pilote";
		else
			$Pilote_db="Pilote_IA";
		if($Pilote_eni)
		{
			$Pilotage_eni=GetData($Pilote_db,"ID",$Pilote_eni,"Pilotage")+$Enis;
			$Tactique_eni=GetData($Pilote_db,"ID",$Pilote_eni,"Tactique")+$Enis;
		}
		else
		{
			$Pilotage_eni=mt_rand(50,200)+$Enis;
			$Tactique_eni=mt_rand(50,200)+$Enis;
		}		
		//Ailier
		if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
		{
			$wingman=IsAilier($PlayerID, $Leader);
			if($Sandbox)
				$Ailier=GetData("Pilote","ID",$PlayerID,"S_Ailier");
			else
				$Ailier=GetData("Pilote","ID",$PlayerID,"Ailier");
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
			if($FingerFour)$Tactique+=25;
		}
		else
		{
			$choix3="";
			$choix6="";
		}
		//Mitrailleur eni riposte automatiquement si attaquant trop près, mais l'attaquant peut tirer
		if($Type_avioneni !=1 and $Type_avioneni !=12 and $Dist_shoot <200 and $Arme3Avion_eni !=5 and $Arme3Avion_eni !=0)
		{
			$Tir_eni=mt_rand(25,250);
			$Shoot=mt_rand(0,$Tir_eni)+($meteo/2)+$VisAvion-($ManAvion/10)-($Pilotage/10)-($Dist_shoot/10);
			$intro.="<br>Le mitrailleur du <b>".$nom_avioneni."</b> tire à votre approche !";
			if($Shoot >0)
			{
				$Arme3Avion_nbr_eni=GetData($Avion_db_eni,"ID",$avion_eni,"Arme3_Nbr");
				if(!$Arme3Avion_nbr_eni)
				{
					$Arme3Avion_eni=GetData($Avion_db_eni,"ID",$avion_eni,"TourelleSup");
					$Arme3Avion_nbr_eni=GetData($Avion_db_eni,"ID",$avion_eni,"Arme5_Nbr");
				}
				$Arme3Avion_nbr_eni=GetShoot($Shoot,$Arme3Avion_nbr_eni);
				$Arme3Avion_Dg_eni=GetData("Armes","ID",$Arme3Avion_eni,"Degats");
				$Arme3Avion_Multi_eni=GetData("Armes","ID",$Arme3Avion_eni,"Multi");
				$Mun_eni=mt_rand(1,5);
				$Bonus_Dg=Damage_Bonus($Avion_db_eni,$avion_eni,$avion,$Arme3Avion_eni,$Blindage,$Mun_eni,$Dist_shoot);
				$Degats=0;
				for($i=1;$i<=$Arme3Avion_nbr_eni;$i++)
				{
					$Degats=round($Degats+((mt_rand(1,$Arme3Avion_Dg_eni)+$Bonus_Dg-pow($Blindage,2))*mt_rand(1,$Arme3Avion_Multi_eni)));
				}
				if($Degats <1)$Degats=mt_rand(1,5);
				$HP-=$Degats;
				//HP Avion perso persistant
				if($Avion_db =="Avions_Persos")
				{
					if($HP <1)$HP=0;
					SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
				}
				if($HP <1)
				{
					$intro.='<br>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
					$Action=99;
					$end_mission=true;
					$_SESSION['Parachute']=true;
				}
				else
				{
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
							if($Meca > $Degats)$Meca=$Degats;
							$intro.='<p>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)</p>';
							$HP+=$Meca;
						}
					}
					if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-5);
				}
				SetData("Pilote","S_HP", $HP,"ID",$PlayerID);
			}
			else
				$intro.="<p>Vous évitez la rafale de justesse!</p>";
		}
		$Degats=0;		
		//Tir avec toutes les armes
		if($Armet ==3)$Action=7;		
		$Conso=(GetData($Avion_db,"ID",$avion,"Puissance")*$c_gaz/100)/500;		
		switch($Action)
		{
			case 1:
				$img="<img src='images/epargner.jpg' style='width:100%;'>";
				if(!$PVP)
				{
					if(!$Chk_finish and $Simu and !$Sandbox)
					{
						UpdateCarac($PlayerID,"Avancement",-5);
						UpdateCarac($PlayerID,"Reputation",10);
					}
					if($HP_eni <($HPmax_eni/10))
					{
						$intro.="<br>Votre adversaire balance ses ailes en retour pour saluer votre acte honorable.<br>Vous rompez le combat.";
						$nav=true;
					}
					else
					{
						$intro.="<br>Votre adversaire ne semble pas réagir à votre tentative de trève.<br>Le combat continue.";
						$mission3=true;
					}
				}
				else
				{
					$intro.="<br>Votre adversaire ne semble pas réagir à votre tentative de trève.<br>Le combat continue.";
					$mission3=true;
				}
			break;
			case 3:
				//Lâcher une courte rafale à l'aide de votre...
				if($Armet ==99 or $Rafalet ==99 or $Viset ==99 or $Chk_finish)
				{
					//Tir annulé
					$intro.="<br>Vous laissez passer cette opportunité.";
					$img="<img src='images/facetoface.jpg' style='width:100%;'>";
					if(!$Sandbox)UpdateCarac($PlayerID,"Reputation",-2);
					$mission3=true;
				}
				else
				{
					switch($Armet)
					{
						case 1:
							$Mun=$Mun1;
							$ArmeAvion=$Arme1Avion;
							$ArmeAvion_Range=$Arme1Avion_Range;
							$ArmeAvion_nbr=$Arme1Avion_nbr;
							$ArmeAvion_Dg=$Arme1Avion_Dg;
							$ArmeAvion_Multi=$Arme1Avion_Multi;
						break;
						case 2:
							$Mun=$Mun2;
							$ArmeAvion=$Arme2Avion;
							$ArmeAvion_Range=$Arme2Avion_Range;
							$ArmeAvion_nbr=$Arme2Avion_nbr;
							$ArmeAvion_Dg=$Arme2Avion_Dg;
							$ArmeAvion_Multi=$Arme2Avion_Multi;
						break;
					}
					switch($Rafalet)
					{
						case 2:
							$Malus=0;
							$Mult_Rafale=1;
							$Mun_Rafale=2; //1
						break;
						case 3:
							if($Armet ==1)
								$Malus=$ArmeAvion_nbr*$Calibre1;
							else
								$Malus=$ArmeAvion_nbr*$Calibre2;
							$Mult_Rafale=1.5;
							$Mun_Rafale=5; //2
						break;
					}					
					if($Mun <1)
					{
						$intro.="<p>Vous n'avez plus de munitions!</p>";
						$img.="<img src='images/enrayage.jpg' style='width:100%;'>";
						$cont_shoot=true;
					}
					elseif(IsEnrayage($ArmeAvion,$alt))
					{
						$intro.="<p><b>Vos armes s'enrayent au plus mauvais moment!</b></p>";
						$img="<img src='images/enrayage.jpg' style='width:100%;'>";
						$mes.=".";//"<br>Votre adversaire se replace, le combat continue!";
						if($Armet ==2)
							$Mun2=0;
						else
							$Mun1=0;
						$cont_shoot=true;
					}
					else
					{
						$Malus_Range=GetMalus_Range($Dist_shoot,$ArmeAvion_Range,$Angle_shoot,$VitAvioneni);
						if($Dist_shoot <50)
							$Bonus_Pil_eni=0;
						else
							$Bonus_Pil_eni=($Pilotage_eni/10) + ($ManiAvion_eni/10);
						$Tir_rand=mt_rand(0,$Tir);
						$Shoot=$Tir_rand + ($meteo/2) - $Malus_Range + ($StabAvion/10) + ($Vis_eni/5) + ($Courage/10) - $Bonus_Pil_eni - $Malus + $ArmeAvion_Multi;
						//JF
						if($PlayerID ==1 or $PlayerID ==2)
						{
							$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
								<tr><td colspan='3'>Tir : Moral</td></tr>
								<tr><td>Tir</td><td>".$Tir_rand." / ".$Tir."</td></tr>
								<tr><td>Vis</td><td>".$Vis_eni." /5</td></tr>
								<tr><td>StabAvion</td><td>".$StabAvion." /10</td></tr>
								<tr><td>Bonus Rafale</td><td>".$ArmeAvion_Multi."</td></tr>
								<tr><td>Courage</td><td>".$Courage." /10</td></tr>
								<tr><td>Malus_Range</td><td>-".$Malus_Range."</td></tr>
								<tr><td>Malus Arme</td><td>-".$Malus."</td></tr>
								<tr><td>Pilote eni</td><td>-".$Bonus_Pil_eni."</td></tr>
								<tr><td>Meteo /2</td><td>-".$meteo."</td></tr>
								<tr><th>Total</th><th>".$Shoot."</th></tr>
							</table>";
						}
						elseif($Vic)
							$Shoot-=($Vic*20);
						//End JF
						if($Shoot >0)
						{
							//if($Viset >1)$Blindage_eni+=1;
							$Bonus_Dg=Damage_Bonus($Avion_db,$avion,$avion_eni,$ArmeAvion,$Blindage_eni,$Avion_Mun,$Dist_shoot);
							$ArmeAvion_nbr=GetShoot($Shoot,$ArmeAvion_nbr);
							$Degats=0;
							for($i=1;$i<=$ArmeAvion_nbr;$i++)
							{
								$Degats=round($Degats+(($ArmeAvion_Dg+$Bonus_Dg-pow($Blindage_eni,2))*mt_rand(1,$ArmeAvion_Multi)*$Mult_Rafale));
							}
							if($Degats <1)$Degats=mt_rand(1,5);
							if($Degats >$Degats_Max)
								SetData("Pilote","Degats_Max",$Degats,"ID",$PlayerID);
							$HP_eni-=$Degats;
							if($HP_eni <1)
							{
								if($Mission_Type ==103)
									$intro.='<p>En combat réel, votre rafale suffirait certainement à achever votre adversaire!';
								elseif($Premium)
									$intro.='<p>Votre rafale achève votre cible qui s\'abat en flammes, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
								else
									$intro.='<p>Votre rafale achève votre cible qui s\'abat en flammes!';
								if(!$Sandbox)
								{
									//UpdateCarac($PlayerID,"Tir",1);
									UpdateCarac($PlayerID,"Moral",25);
								}
								$end_shoot=true;
							}
							else
							{
								if($Mission_Type ==103)
									$intro.='<p>En combat réel, votre rafale toucherait certainement votre cible!';
								elseif($Premium)
									$intro.='<p>Votre rafale touche votre cible, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
								else
									$intro.='<p>Votre rafale touche votre cible!';
								$img.="<img src='images/hit.jpg' style='width:100%;'>";
								if(!$Sandbox)
								{
									//UpdateCarac($PlayerID,"Tir",1);
									UpdateCarac($PlayerID,"Moral",5);
								}
								$cont_shoot=true;
							}
						}
						else
						{
							if($Shoot <100)
								$intro.="<p>Votre rafale rate complètement la cible!";
							elseif($Shoot <50)
								$intro.="<p>Votre rafale rate la cible!";
							else
								$intro.="<p>Votre rafale rate de peu la cible!";
							//$skills.="[Tir : ".$Shoot." (Malus Meteo : ".$meteo." ; Malus Distance : ".$Malus_Range." ; Distance : ".$Dist_shoot.")]";
							$img.='<img src=\'images/miss'.$country.'.jpg\' style=\'width:100%;\'>';
							if(!$Sandbox)
							{
								UpdateCarac($PlayerID,"Moral",-1);
								/*if($Rafalet ==3 or $Viset >1)UpdateCarac($PlayerID,"Tir",-1);*/
							}
							$cont_shoot=true;
						}
						$Mun-=($ArmeAvion_Multi*$ArmeAvion_nbr*$Mun_Rafale);				
						if($Armet ==2)
							$Mun2=$Mun;
						else
							$Mun1=$Mun;
						if($Shoot >200 or $Tir_rand ==$Tir)
						{
							switch($Viset)
							{
								case 1:
								break;
								//Moteur
								case 2:
									$Engine_eni=GetData($Avion_db_eni,"ID",$avion_eni,"Engine");
									$Engine_Type_eni=GetData("Moteur","ID",$Engine_eni,"Type");
									if($Engine_Type_eni >0)
									{
										$Engine_Nbr_Eni=GetData("Pilote","ID",$PlayerID,"S_Engine_Nbr_Eni");
										if($Engine_Nbr_Eni <2)
										{
											$intro.="<p>Votre tir précis fait mouche! Une fumée noire s'échappe du moteur de votre cible!<br>L'avion ennemi s'abat le moteur en flammes!</p>";
											$HP_eni=0;
											$cont_shoot=false;
											$end_shoot=true;
											SetData("Pilote","S_Engine_Nbr_Eni",0,"ID",$PlayerID);
										}
										else
										{
											$intro.="<p>Votre tir précis fait mouche! Une fumée noire s'échappe d'un des moteurs de votre cible!</p>";
											UpdateData("Pilote","S_Engine_Nbr_Eni",-1,"ID",$PlayerID);
										}
									}
									else
										$intro.="<p>Votre tir précis fait mouche! Une fumée blanche s'échappe du moteur de votre cible.</p>";
								break;
								//Aile
								case 3:
									if($Avion_db =="Avions_Persos" or $Avion_db =="Avions_Sandbox")
									{
										if($Armet ==2)
											$Munitions=GetData($Avion_db,"ID",$avion,"Munitions2");
										else
											$Munitions=GetData($Avion_db,"ID",$avion,"Munitions1");
									}
									else
										$Munitions=$Avion_Mun;
									if($Munitions ==3 or $Munitions ==5)
									{
										$Reservoir=GetData("Avion","ID",$avion_eni,"Reservoir");
										if($Reservoir ==1)
											$intro.="<p>Votre tir précis endommage les ailes de votre cible, mais le feu ne se propage pas aux réservoirs!</p>";
										else
										{
											$intro.="<p>Votre tir précis fait jaillir des flammes des réservoirs d'ailes de votre cible!</p>";
											$HP_eni=0;
											$cont_shoot=false;
											$end_shoot=true;
										}
									}
									else
										$intro.="<p>Votre tir précis endommage les ailes de votre cible!</p>";
								break;
								//Queue
								case 4:
									if($Type_avioneni !=1 and $Type_avioneni !=12)
									{
										$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"ArmeArriere");
										$ArmeTourelle_eni=GetData($Avion_db_eni,"ID",$avion_eni,"Arme5_Nbr");
										if($ArmeArriere_eni)
										{
											$intro.="<p>Votre tir précis réduit au silence le mitrailleur arrière!</p>";
											$Mg_crit=true;
										}
										elseif($ArmeTourelle_eni)
										{
											$intro.="<p>Votre tir précis réduit au silence un mitrailleur ennemi!</p>";
											$Mg_crit=true;
										}
										else
											$intro.="<p>Votre tir précis endommage l'empennage de votre cible!</p>";
									}
									else
										$intro.="<p>Votre tir précis endommage l'empennage de votre cible!</p>";
								break;
								//Cockpit
								case 5:
									if($Blindage_eni <1)
									{
										if($Shoot >200 and $Pilotage_eni <100)
										{
											$intro.="<p>Votre tir précis touche le cockpit, tuant net le pilote ennemi!</p>";
											$HP_eni=0;
											$cont_shoot=false;
											$end_shoot=true;
										}
										else
										{
											$intro.="<p>Votre tir précis touche le cockpit, blessant le pilote ennemi!</p>";
											$Mg_crit=true;
										}
									}
									else
										$intro.="<p>Votre tir précis touche le cockpit, mais le blindage de votre adversaire protège son pilote!</p>";
								break;
								//Flanc
								case 6:
									if($Type_avioneni !=1 and $Type_avioneni !=5 and $Type_avioneni !=12)
									{
										$ArmeSabord_eni=GetData($Avion_db_eni,"ID",$avion_eni,"ArmeSabord");
										if($ArmeSabord_eni)
										{
											$intro.="<p>Votre tir précis réduit au silence un mitrailleur ennemi!</p>";
											$Mg_crit=true;
										}
										else
											$intro.="<p>Votre tir précis traverse votre cible de part en part.</p>";
									}
									else
										$intro.="<p>Votre tir précis endommage le flanc de votre cible!</p>";
								break;
							}
						}
					}
				}		
			break;
			case 7:
				//Lâcher une rafale à l'aide de toutes vos armes de bord...
				if($Armet ==99 or $Rafalet ==99 or $Viset ==99 or $Chk_finish)
				{
					//Tir annulé
					$intro.="<br>Vous laissez passer cette opportunité.";
					$img="<img src='images/facetoface.jpg' style='width:100%;'>";
					if(!$Sandbox)UpdateCarac($PlayerID,"Reputation",-2);
					$mission3=true;
				}
				else
				{
					switch($Rafalet)
					{
						case 2:
							$Mult_Rafale=1;
							$Mun_Rafale=2; //1
						break;
						case 3:
							$Mult_Rafale=1.5;
							$Mun_Rafale=5; //2
							UpdateData("Pilote","Stress_Arme1",1,"ID",$PlayerID);
							UpdateData("Pilote","Stress_Arme2",1,"ID",$PlayerID);
						break;
					}
					if($Mun1 <1 and $Mun2 <1)
					{
						$intro.="<br>Vous n'avez plus de munitions!";
						$img="<img src='images/enrayage.jpg' style='width:100%;'>";
						$cont_shoot=true;
					}
					elseif(IsEnrayage($Arme1Avion,$alt,$PlayerID,"Stress_Arme1"))
					{
						$intro.="<br><b>Vos armes s'enrayent au plus mauvais moment!</b>";
						$img="<img src='images/enrayage.jpg' style='width:100%;'>";
						$mes.=".";//"<br>Votre adversaire se replace, le combat continue!";
						$Mun1=0;
						$cont_shoot=true;
					}
					else
					{
						$Malus=$Calibre1+($Arme1Avion_nbr*$Calibre1)+$Calibre2+($Arme2Avion_nbr*$Calibre2);
						$Malus_Range=GetMalus_Range($Dist_shoot,$Arme1Avion_Range,$Angle_shoot,$VitAvioneni);
						$Malus_Range2=GetMalus_Range($Dist_shoot,$Arme2Avion_Range,$Angle_shoot,$VitAvioneni);
						if($Dist_shoot <50)
							$Bonus_Pil_eni=0;
						else
							$Bonus_Pil_eni=($Pilotage_eni/10)+($ManiAvion_eni/10);
						$Tir_rand=mt_rand(0,$Tir);
						$Shoot=$Tir_rand+($meteo/2) - $Malus_Range + ($StabAvion/10) + ($Vis_eni/5) + ($Courage/10) - $Bonus_Pil_eni - $Malus;
						//JF
						if($PlayerID ==1 or $PlayerID ==2)
						{
							$skills.="<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
								<tr><td colspan='3'>Tir : Moral</td></tr>
								<tr><td>Tir</td><td>".$Tir_rand." / ".$Tir."</td></tr>
								<tr><td>Vis</td><td>".$Vis_eni." /5</td></tr>
								<tr><td>StabAvion</td><td>".$StabAvion." /10</td></tr>
								<tr><td>Courage</td><td>".$Courage." /10</td></tr>
								<tr><td>Malus_Range</td><td>-".$Malus_Range."</td></tr>
								<tr><td>Malus Arme</td><td>-".$Malus."</td></tr>
								<tr><td>Pilote eni</td><td>-".$Bonus_Pil_eni."</td></tr>
								<tr><td>Meteo /2</td><td>-".$meteo."</td></tr>
								<tr><th>Total</th><th>".$Shoot."</th></tr>
							</table>";
						}
						elseif($Vic)
							$Shoot-=($Vic*20);
						//End JF
						if($Shoot >0)
						{
							$Degats=0;
							if($Mun1 >0)
							{
								$Bonus_Dg_a=Damage_Bonus($Avion_db,$avion,$avion_eni,$Arme1Avion,$Blindage_eni,$Avion_Mun,$Dist_shoot);
								$Arme1Avion_nbr=GetShoot($Shoot,$Arme1Avion_nbr);
								for($i=1;$i<=$Arme1Avion_nbr;$i++)
								{
									$Degats=round($Degats+(($Arme1Avion_Dg+$Bonus_Dg_a-pow($Blindage_eni,2))*mt_rand(1,$Arme1Avion_Multi)*$Mult_Rafale));
								}
							}
							if($Mun2 >0)
							{
								$Bonus_Dg_b=Damage_Bonus($Avion_db,$avion,$avion_eni,$Arme2Avion,$Blindage_eni,$Avion_Mun,$Dist_shoot,2);
								$Arme2Avion_nbr=GetShoot($Shoot,$Arme2Avion_nbr);							
								for($i=1;$i<=$Arme2Avion_nbr;$i++)
								{
									$Degats=round($Degats+(($Arme2Avion_Dg+$Bonus_Dg_b-pow($Blindage_eni,2))*mt_rand(1,$Arme2Avion_Multi)*$Mult_Rafale));
								}
							}
							if($Degats <1)$Degats=mt_rand(1,10);
							if($Degats >$Degats_Max)SetData("Pilote","Degats_Max",$Degats,"ID",$PlayerID);
							$HP_eni-=$Degats;
							if($HP_eni <1)
							{
								if($Mission_Type ==103)
									$intro.='<p>En combat réel, votre rafale suffirait certainement à achever votre adversaire!';
								elseif($Premium)
									$intro.='<p>Votre rafale achève votre cible qui s\'abat en flammes, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
								else
									$intro.='<p>Votre rafale achève votre cible qui s\'abat en flammes!';
								if(!$Sandbox)
								{
									/*$car_up=mt_rand(1,2);
									UpdateCarac($PlayerID,"Tir",$car_up);*/
									UpdateCarac($PlayerID,"Moral",25);
								}
								$end_shoot=true;
							}
							else
							{
								if($Premium)
									$intro.='<p>Votre rafale touche votre cible de plein fouet, lui occasionnant <b>'.$Degats.'</b> points de dégats!'; // (reste :".$HP_eni.")";
								else
									$intro.='<br>Votre rafale touche votre cible de plein fouet!';
								$img="<img src='images/hit.jpg' style='width:100%;'>";
								if(!$Sandbox)
								{
									/*$car_up=mt_rand(0,1);
									UpdateCarac($PlayerID,"Tir",$car_up);*/
									UpdateCarac($PlayerID,"Moral",5);
								}
								$cont_shoot=true;
							}
						}
						else
						{
							$intro.="<p>Votre rafale rate votre cible!";
							$img="<img src='images/miss".$country.".jpg' style='width:100%;'>";
							if($Shoot <0 and !$Sandbox)
							{
								UpdateCarac($PlayerID,"Moral",-1);
								//UpdateCarac($PlayerID,"Tir",-1);
							}
							$cont_shoot=true;
						}
						$Mun1-=($Arme1Avion_Multi*$Arme1Avion_nbr*$Mun_Rafale);
						$Mun2-=($Arme2Avion_Multi*$Arme2Avion_nbr*$Mun_Rafale);
					}
				}
			break;
			case 9:
				//Rompre le combat.
				if(($Mun1 >0 or $Mun2 >0) and !$Sandbox)
				{
					UpdateCarac($PlayerID,"Reputation",-5);
					UpdateCarac($PlayerID,"Courage",-5);
					UpdateCarac($PlayerID,"Avancement",-5);
				}
				if($HP_eni <$HPmax_eni and !$Chk_finish and $Simu and !$Sandbox and $Mission_Type !=103 and $Cible and $Pilote_eni)
					AddProbable($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,1);
				$intro.="<br>";
				$img=Afficher_Image('images/avions/pique'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
				$nav=true;
			break;
			case 11:
				//Attendre une meilleure chance.
				if(($Dist_shoot >300 or $Angle_shoot >50) and !$Chk_finish and !$Sandbox)
					UpdateCarac($PlayerID,"Reputation",1);
				$intro.="<br>Vous décidez d'attendre qu'une meilleure opportunité se présente.";
				$img="<img src='images/facetoface.jpg' style='width:100%;'>";
				$mission3=true;
			break;
			case 99:
				//Descendu par mitrailleur
				$end_mission=true;
			break;
		}
	}
	if($PVP and !$Sandbox)
	{
		SetData("Duels_Candidats","HP",$HP,"PlayerID",$PlayerID);
		SetData("Duels_Candidats","Altitude",$alt,"PlayerID",$PlayerID);
		SetData("Duels_Candidats","Altitude",$alt,"PlayerID",$Pilote_eni);
		SetData("Duels_Candidats","HP",$HP_eni,"PlayerID",$Pilote_eni);
	}	
	if($Admin ==1)
	{
		$Mun1=1000;
		$Mun2=2000;
	}
	if($Mun1 <0 or $Arme1Avion ==25 or $Arme1Avion ==26 or $Arme1Avion ==27)$Mun1=0;
	if($Mun2 <0 or $Arme1Avion ==25 or $Arme2Avion ==26 or $Arme2Avion ==27)$Mun2=0;
	if($Mun1 <1 and $Mun2 <1)
	{
		$choix_fuite="Rompre le combat en vous lançant dans un piqué, faute de munitions";
		$choix_fuite_man="Rompre le combat en manoeuvrant, faute de munitions";
	}
	else
	{
		$choix_fuite="Tenter de rompre le combat en vous lançant dans un piqué";
		$choix_fuite_man="Tenter une manœuvre d'évasion et fuir le combat";
	}	
	$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
	if($end_shoot)
	{
		SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
		$_SESSION['finish']=true;
		$titre="Combat";
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
		'.ShowGaz($avion,$c_gaz,$flaps,$alt,5).'
		'.GetSituation($Enis,$avion_eni,$Pays_eni,$Leader,$Ailier,$avion).'
		<table class=\'table\'><tr><td align=\'left\'>
					<Input type=\'Radio\' name=\'Action\' value=\'1\' checked>- Poursuivre votre proie pour confirmer votre victoire.<br>
					<Input type=\'Radio\' name=\'Action\' value=\'2\'>- Vous désintéresser de votre proie et continuer le combat.<br>
			</td></tr></table>
		<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
	}	
	if($cont_shoot)
	{
		//overshoot
		$Shoots=false;
		if(($Dist_shoot <100 and ($VitAvion-$VitAvioneni) >200) or $Chk_finish)
		{
			$intro.="<br>Emporté par votre vitesse, vous dépassez votre cible!<br>";
			if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
				$Shoots=true;
			else
			{
				if($Mg_crit)
					$mission3=true;
				else
				{
					$ArmeTourelle=GetData($Avion_db_eni,"ID",$avion_eni,"Arme5_Nbr");
					$Arme3Avion_nbr_eni_base=$ArmeTourelle + GetData($Avion_db_eni,"ID",$avion_eni,"Arme1_Nbr");
					if($ArmeTourelle >0)
						$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"TourelleSup");
					else
						$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"ArmePrincipale");
					$mitrailleur=true;
				}
			}
		}
		else
		{	
			if($HP_eni <($HPmax_eni/2))
			{
				//$PuissAvion=GetData("Avion","ID",$avion,"Puissance");
				$Engine_Nbr_Eni=GetData("Pilote","ID",$PlayerID,"S_Engine_Nbr_Eni");
				$PuissAvioneni=GetPuissance($Avion_db_eni,$avion_eni,$alt,$HP_eni,1,1,$Engine_Nbr_Eni);
				$Injection_eni=GetData($Avion_db_eni,"ID",$avion_eni,"Injection");
				$Mano=$VitAvion+$Tactique-$Puissance;
				$Mano_eni=$VitAvioneni+$Tactique_eni-$PuissAvioneni+($Injection_eni*50);
				unset($Injection_eni);
				if($Mano_eni >$Mano)
				{
					$essence-=(5+$Conso);
					if($HP_eni <$HPmax_eni and $Simu and !$Sandbox and $Mission_Type !=103 and $Cible and $Pilote_eni)
						AddProbable($Avion_db,$avion_eni,$avion,$PlayerID,$Unite,$Unit_eni,$Cible,$Arme1Avion,$Pilote_eni,1);
					$intro.="<br>Votre adversaire rompt le combat et parvient à s'échapper.";
					//$skills.="<br>[Votre Manoeuvre : ".$Mano." (Bonus Tactique : ".$Tactique."), Manoeuvre ennemi : ".$Mano_eni."]";
					$img="<img src='images/escape.jpg' style='width:100%;'>";
					if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-1);
					$nav=true;
				}
				else
				{
					$essence-=(5+$Conso);
					$intro.='<br><br>Votre adversaire, le <b>'.$nom_avioneni.'</b> tente en vain de s\'échapper.';
					$mission3=true;
				}
			}
			else
				$mission3=true;
		}
	}	
	if($Shoots)
	{
		if($PVP)
		{
			$img='<img src=\'images/shooted'.$country.'.jpg\' style=\'width:100%;\'>';
			$intro.="<p>Votre adversaire joue avec vous!</p>";
			$evade=true;
		}
		else
		{
			if($Pilote_eni)$Tir_eni=GetData($Pilote_db,"ID",$Pilote_eni,"Tir");
			$Rand_Tir_eni=mt_rand(0,$Tir_eni);
			$Shoot=$Rand_Tir_eni+($meteo/2)+($VisAvion/5)-mt_rand(10,$Pilotage)-($ManAvion/10)-($ManiAvion/10)+$Enis-$SixSens;
			//JF
			if($PlayerID ==1 or $PlayerID ==2)
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
			if($Shoot >10 or $Rand_Tir_eni ==$Tir_eni or $Chk_finish)
			{
				$Arme1_eni_nbr=GetData($Avion_db_eni,"ID",$avion_eni,"Arme1_Nbr");
				$Arme2_eni_nbr=GetData($Avion_db_eni,"ID",$avion_eni,"Arme2_Nbr");
				if($Arme2_eni_nbr > $Arme1_eni_nbr)
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
				$Mun_eni=mt_rand(1,5);
				$Bonus_Dg=Damage_Bonus($Avion_db_eni,$avion_eni,$avion,$Arme1Avion_eni,$Blindage,$Mun_eni);
				$Arme1Avion_nbr_eni=GetShoot($Shoot,$Arme1Avion_nbr_eni)+$Enis;
				$Degats=0;
				if($Arme1Avion_Dg_eni >0)
				{
					for($i=1;$i<=$Arme1Avion_nbr_eni;$i++)
					{
						if($Rand_Tir_eni ==$Tir_eni)
							$Degats=round($Degats+(($Arme1Avion_Dg_eni+$Bonus_Dg-pow($Blindage,2))*$Arme1Avion_Multi_eni));
						else
							$Degats=round($Degats+((mt_rand(1,$Arme1Avion_Dg_eni)+$Bonus_Dg-pow($Blindage,2))*mt_rand(1,$Arme1Avion_Multi_eni)));
					}
				}
				if($Degats <1)$Degats=mt_rand(1,5);
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
					$intro.='<p>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)</p>';
					$end_mission=true;
					$_SESSION['Parachute']=true;
				}
				else
				{
					$evade=true;
					//Critical Hit
					if($Shoot >100 or $Rand_Tir_eni ==$Tir_eni)
					{
						$CritH=CriticalHit($Avion_db,$avion,$PlayerID,$Mun_eni,$Engine_Nbr);
						$intro.=$CritH[0];
						$end_mission=$CritH[1];
						if($end_mission)
						{
							$HP=0;
							$evade=false;
						}
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
				}
				SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
			}
			else
			{
				$img="<img src='images/shooted".$country.".jpg' style='width:100%;'>";
				$intro.="<p>Vous évitez la rafale de justesse!</p>";
				$evade=true;
			}
		}
		if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-2);
	}	
	if($nav)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0,S_Essence='$essence' WHERE ID='$PlayerID'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : finish-reset2');
		mysqli_close($con);
		$_SESSION['finish']=true;
		$titre="Navigation";
		$intro.="<p>Vous rompez le combat et continuez votre route.</p>";
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
			'.GetSituation($Enis,$avion_eni,$Pays_eni,$Leader,$Ailier,$avion).'
			<table class=\'table\'>
				<tr><td align=\'left\'>
						<Input type=\'Radio\' name=\'Action\' value=\'0\' checked>- Continuer vers votre objectif.<br>
						<Input type=\'Radio\' name=\'Action\' value=\'1\'>- Faire demi-tour.<br>
				</td></tr></table>
			<input type=\'Submit\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
	}
	if($mission3)
	{
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
		//Seuls les chasseurs et chasseurs lourds attaquent
		if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
		{
			$choix1="<Input type='Radio' name='Action' value='1'>- Chercher à vous placer dans ses 6 heures pour l'abattre.<br>";
			if($alt >1000)
				$choix7="<Input type='Radio' name='Action' title='".GetMes('Aide_Coiffer')."' value='7'>- Chercher à prendre de l'altitude pour ensuite piquer sur l'ennemi.<br>";
			else
				$choix7="";
			$choix8="<Input type='Radio' name='Action' title='".GetMes('Aide_Frontale')."' value='8'>- Tenter une attaque frontale.<br>";
			if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
				$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Virer de plus en plus serré.<br>";			
			else
				$choix2="<Input type='Radio' name='Action' title='".GetMes('Aide_Avantage')."' value='2'>- Tenter une attaque par le flanc.<br>";
		}
		elseif($Equipage_Nbr >1 and $avion !=43 and $avion !=109)
		{
			$choix1="<Input type='Radio' name='Action' value='9'>- Maintenir votre cap, votre mitrailleur arrière se chargeant de l'adversaire.<br>";
			$choix2="";
			$choix7="";
			$choix8="";
		}
		else
		{
			$choix1="";
			$choix2="";
			$choix7="";
			$choix8="";
		}
		if(!$intro)$intro=".";
		SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
		$_SESSION['finish']=true;
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
		'.GetSituation($Enis,$avion_eni,$Pays_eni,$Leader,$Ailier,$avion).'
		<table class=\'table\'><tr><td align=\'left\'>'.$choix1.$choix7.$choix8.$choix2.'
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Man').'\' value=\'6\' checked>- '.$choix_fuite_man.'.<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Pique').'\' value=\'4\'>- '.$choix_fuite.'.<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Nuages').'\' value=\'5\'>- '.$choix5.'<br>'.$Ventre.$Alleger.'
			</td></tr></table>
		<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
	}	
	if($mitrailleur)
	{
		if(!$ArmeArriere_eni)$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"ArmeArriere");
		if(IsEnrayage($ArmeArriere_eni,$alt))
		{
			$intro.='<br>Le mitrailleur du <b>'.$nom_avioneni.'</b> tire à votre approche !<br>Heureusement pour vous, son arme s\'enraye!';
			$evade=true;
		}
		else
		{
			$ArmeArriere_eni=GetData($Avion_db_eni,"ID",$avion_eni,"ArmeArriere");
			if($ArmeArriere_eni !=5 and $ArmeArriere_eni !=0 and !$PVP)
			{
				$Concentrer=false;
				$Tir_eni=mt_rand(25,250);
				$Rand_Tir_eni=mt_rand(0,$Tir_eni);
				$Shoot=$Rand_Tir_eni+($meteo/2)+$VisAvion-($ManAvion/10)-($ManiAvion/10)-($Pilotage/10)-($Dist_shoot/10);
				if($Enis >1 and mt_rand(0,$Tactique_eni) >49)
				{
					$intro.="<p>La formation ennemie concentre son tir sur vous !</p>";
					$Shoot=$Shoot+$Enis;
					$Concentrer=true;
				}
				else
					$intro.='<p>Le mitrailleur du <b>'.$nom_avioneni.'</b> tire à votre approche !</p>';
				$img="<img src='images/mg_ar".$Pays_eni.".jpg' style='width:100%;'>";
				//JF
				if($PlayerID ==1 or $PlayerID ==2)
				{
					$skills.="<br>[Score de Tir : ".$Shoot."]
										<br>+Vis ".$VisAvion." /2
										<br>-Man ".$ManAvion." /10
										<br>-Mani ".$ManiAvion." /10
										<br>-Pilotage ".$Pilotage." /10
										<br>Tir_eni :".$Tir_eni;
				}
				//End JF
				if($Shoot >10 or $Rand_Tir_eni ==$Tir_eni or $Chk_finish)
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
						$Degats=round($Degats+((mt_rand(1,$Arme3Avion_Dg_eni)+$Bonus_Dg-pow($Blindage,2))*mt_rand(1,$Arme3Avion_Multi_eni)));
					}
					if($Degats <1)$Degats=mt_rand(1,5);
					//$Mun1_eni-=($Arme1Avion_Multi_eni*$Arme1Avion_nbr_eni);
					$HP-=$Degats;
					//HP Avion perso persistant
					if($Avion_db =="Avions_Persos")
					{
						if($HP <1)$HP=0;
						SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
					}
					if($HP <1)
					{
						$intro.='<p>La rafale transforme votre appareil en passoire, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)</p>';
						$end_mission=true;
						$_SESSION['Parachute']=true;
					}
					else
					{
						if($Premium)
							$intro.='<p>La rafale frappe votre appareil de plein fouet, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')</p>';
						else
							$intro.='<p>La rafale frappe votre appareil de plein fouet!</p>';
						$evade=true;
						//Critical Hit
						if($Shoot >100)
						{
							$CritH=CriticalHit($Avion_db,$avion,$PlayerID,$Mun_eni,$Engine_Nbr);
							$intro.=$CritH[0];
							$end_mission=$CritH[1];
							if($end_mission)
							{
								$HP=0;
								$evade=false;
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
					SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
				}
				else
				{
					$intro.="<p>Vous évitez la rafale de justesse!</p>";
					$evade=true;
				}
				if(!$Sandbox)UpdateCarac($PlayerID,"Moral",-2);
			}
			else
			{
				$intro.="<p>Vous dépassez votre adversaire, emporté par votre vitesse !</p>";
				$evade=true;
			}
		}
	}
	//Combat tournoyant ou acrobatie
	if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
		$choix2="<Input type='Radio' title='".GetMes('Aide_Avantage')."' name='Action' value='2'>- Tenter une manoeuvre pour reprendre l'avantage.<br>";
	else
		$choix2="<Input type='Radio' title='".GetMes('Aide_Avantage')."' name='Action' value='2'>- Effectuer une manœuvre pour tenter d'attaquer l'ennemi par le flanc.<br>";	
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
		//Message mitrailleur arrière
		if($Equipage_Nbr >1)
		{
			$Formation=GetData("Pilote","ID",$PlayerID,"S_Formation");
			$choix2="";
			$choix3="";
			$choix5="<Input type='Radio' name='Action' value='5'>- Maintenir votre cap, votre mitrailleur arrière se chargeant de l'adversaire.<br>";
			if(!$PVP and GetData("Pilote","ID",$PlayerID,"S_Escorte_nbr"))
				$choix6="<Input type='Radio' name='Action' title='".GetMes('Aide_Appel_leader')."' value='20'>- Appeler votre escorte à l'aide<br>";
			else
				$choix6="";
			if($Formation and !IsAilier($PlayerID,$Leader) and $Tactique >75 and $Radio_a)
				$choix13="<Input type='Radio' name='Action' value='13'>- Ordonner à la formation de concentrer le tir sur votre cible<br>";
		}
		else
			$choix5="<Input type='Radio' name='Action' value='5'>- Vous désintéresser de l'adversaire et maintenir votre cap, atteindre l'objectif est plus important.<br>";
		if($PVP)
			$choix5="";
		if($Type_avioneni ==1 or $Type_avioneni ==4 or $Type_avioneni ==12)
			$choix8='<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Barrique_Off').'\' value=\'8\'>- Tenter une manoeuvre pour forcer l\'adversaire à vous dépasser.<br>';
		else
			$choix8='';
		if(!$img)$img='<img src=\'images/shooted'.$country.'.jpg\' style=\'width:100%;\'>';
		SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
		$_SESSION['finish']=true;
		$titre="Combat";
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
		'.GetSituation($Enis,$avion_eni,$Pays_eni,$Leader,$Ailier,$avion).'
		<table class=\'table\'><tr><td align=\'left\'>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Degager').'\' value=\'1\'>- Tenter de manoeuvrer pour vous dégager de la ligne de tir de votre adversaire.<br>
					'.$choix2.$choix8.$choix3.$choix6.$choix5.$choix13.'
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Man').'\' value=\'4\' checked>- Tenter de manoeuvrer pour fuir.<br>
					<Input type=\'Radio\' name=\'Action\' title=\''.GetMes('Aide_Fuite_Climb').'\' value=\'12\' checked>- Tenter de fuir en grimpant.<br>
					<Input type=\'Radio\' name=\'Action\' value=\'7\'>- '.$choix_fuite.'.<br>
					'.$Immelman.$Rase_Motte.'</td></tr></table>
		<input type=\'Submit\' value=\'VALIDER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
	}	
	if($panne_seche)
	{
		$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
		$_SESSION['done']=false;
		$_SESSION['finish']=true;
		$intro.="<p>Vous tombez en panne sèche!<br>Vous n'avez pas d'autre choix que d'abandonner votre appareil.<br>Vous parvenez à rejoindre vos lignes à grand peine, mais vous êtes en vie!</p>";
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
		$mes.="<p><b>FIN DE MISSION</b></p>";
		$menu.="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='0'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
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
?>