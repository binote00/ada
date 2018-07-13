<?
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
$meteo=Insec($_POST['meteo']);
$MIA=Insec($_POST['lieu']);
$Km=Insec($_POST['km']);
$Zone=Insec($_POST['zone']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 and $_SESSION['mia_status'] ==false AND $MIA >0 AND !empty($_POST))
{
	include_once('./jfv_combat.inc.php');
	$_SESSION['mia_status']=true;
	$_SESSION['mia2']=false;
	$country=$_SESSION['country'];
	$Credits=false;		
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Unit,Equipage,Reputation,Navigation,Duperie,Courage,Moral,S_Nuit,Slot5,Slot8,Slot11 FROM Pilote WHERE ID='$PlayerID'")
	 or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : mission_mia-player');
	$resultc=mysqli_query($con,"SELECT DefenseAA_temp,ValeurStrat,Longitude,Garnison,Flag FROM Lieu WHERE ID='$MIA'");
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
			$Unite=$data['Unit'];
			$Equipage=$data['Equipage'];
			$Reputation=$data['Reputation'];
			$Navigation=$data['Navigation'];
			$Duperie=$data['Duperie'];
			$Courage=$data['Courage'];
			$Nuit=$data['S_Nuit'];
			$Moral=$data['Moral'];
			$Slot5=$data['Slot5'];
			$Slot8=$data['Slot8'];
			$Slot11=$data['Slot11'];
		}
		mysqli_free_result($result);
	}
	if($resultc)
	{
		while($datac=mysqli_fetch_array($resultc,MYSQLI_ASSOC))
		{
			$DefenseAA=$datac['DefenseAA_temp'];
			$ValeurStrat=$datac['ValeurStrat'];
			$Longitude=$datac['Longitude'];
			$Garnison=$datac['Garnison'];
			$Flag=$datac['Flag'];
		}
		mysqli_free_result($resultc);
	}
	$Bonus_Pers=1;
	if($Duperie >50)$Duperie=50;
	if(is_array($Skills_Pil))
	{
		if(in_array(83,$Skills_Pil))
			$Duperie=150;
		elseif(in_array(82,$Skills_Pil))
			$Duperie=125;
		elseif(in_array(81,$Skills_Pil))
			$Duperie=100;
		elseif(in_array(80,$Skills_Pil))
			$Duperie=75;
		if(in_array(88,$Skills_Pil))
			$Agent_Special=true;
		if(in_array(96,$Skills_Pil))
			$Amicaux=true;
		if(in_array(130,$Skills_Pil))
			$Pers_Sup=1;
		if(in_array(98,$Skills_Pil))
			$Bonus_Pers=1.5;
	}
	if($Equipage)$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");		
	if($Equipage and $Endu_Eq >0)
	{
		$Equipage_Nom=GetData("Equipage","ID",$Equipage,"Nom");
		$Courage_Eq=GetData("Equipage","ID",$Equipage,"Courage");
		$Trait_e=GetData("Equipage","ID",$Equipage,"Trait");
	}	
	$con=dbconnecti();
	$result_mp=mysqli_result(mysqli_query($con,"SELECT COUNT(r.Vehicule_Nbr) FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$MIA' AND r.Pays='$Flag' AND c.Type=99"),0);
	$result_mp+=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sections as s,Officier as o,Regiment as r WHERE s.SectionID=5 AND s.OfficierID=o.ID AND r.Officier_ID=s.OfficierID AND r.Pays='$Flag' AND r.Lieu_ID='$MIA' AND o.Actif=0"),0);
	mysqli_close($con);
	if($result_mp >0)$Anti_Sab=$result_mp*25;	
	$rescue=false;
	$prisonnier=false;
	$retour=false;
	$mia_lieu=false;
	$choix4='';
	$choix5='';
	$choix6='';
	$choix7='';	
	switch($Action)
	{
		//Fusée
		case 1:
			$img=Afficher_Image("images/mia_fusee.jpg","images/image.png","Fusée de détresse");
			$Cache=$Duperie-$Anti_Sab-($ValeurStrat*10)+($Nuit*10)-mt_rand(0,10);
			if($Slot11 ==15)$Cache+=10;
			if($Cache >0)
				$rescue=true;
			else
			{
				if($Slot8 ==3 or $Slot8 ==45 or $Slot8 ==46 or $Slot8 ==47 or $Slot8 ==48)
				{
					$mia_lieu=true;
					$rnd_pr=mt_rand(0,10);
					if($Slot5 ==33 and $rnd_pr >1)
					{
						$prisonnier=false;
						SetData("Pilote","Slot5",0,"ID",$PlayerID);
						$chargeur_txt="<br>Vous tirez quelques cartouches pour faire diversion et vous enfuir!";
					}
					else
					{
						if($rnd_pr <5)
							$prisonnier=false;
						else
							$prisonnier=true;
					}
				}
				else
					$prisonnier=true;
				if($prisonnier)
				{
					if($Zone ==6)
						$mes.="<p>Votre manque de discrétion a rameuté un patrouilleur ennemi qui croisait dans les parages<br>Ils ont tôt fait de vous arrêter et de vous faire prisonnier!</p>";
					else
						$mes.="<p>Votre manque de discrétion a rameuté la garnison toute proche<br>Ils ont tôt fait de vous arrêter et de vous faire prisonnier!</p>";
					$rescue=false;
					$retour=false;
					$mia_lieu=false;
				}
				else
				{
					if($Zone ==6)
						$mes.="<p>Votre manque de discrétion a rameuté un patrouilleur ennemi qui croisait dans les parages".$chargeur_txt."<br>Par chance,vous leur échappez!</p>";
					else
						$mes.="<p>Votre manque de discrétion a rameuté la garnison toute proche".$chargeur_txt."<br>Par chance,vous leur échappez!</p>";
				}
			}
			$Credits=-1;
		break;
		//Feu
		case 2:
			$img=Afficher_Image("images/mia_feu.jpg","images/image.png","Feu");
			$Cache=$Duperie-$Anti_Sab-($ValeurStrat*10)+($Nuit*10);
			if($Cache >0)
				$rescue=true;
			else
			{
				if($Slot8 ==3 or $Slot8 ==45 or $Slot8 ==46 or $Slot8 ==47 or $Slot8 ==48)
				{
					$rnd_pr=mt_rand(0,10);
					if($Slot5 ==33 and $rnd_pr >1)
					{
						$prisonnier=false;
						SetData("Pilote","Slot5",0,"ID",$PlayerID);
						$chargeur_txt="<br>Vous tirez quelques cartouches pour faire diversion et vous enfuir!";
					}
					else
					{
						if($rnd_pr <5)
							$prisonnier=false;
						else
							$prisonnier=true;
					}
				}
				else
					$prisonnier=true;
				if($prisonnier)
				{
					if($Zone ==6)
						$mes.="<p>Votre manque de discrétion a rameuté un patrouilleur ennemi qui croisait dans les parages<br>Ils ont tôt fait de vous arrêter et de vous faire prisonnier!</p>";
					else
						$mes.="<p>Votre manque de discrétion a rameuté la garnison toute proche<br>Ils ont tôt fait de vous arrêter et de vous faire prisonnier!</p>";
					$rescue=false;
					$retour=false;
					$mia_lieu=false;
				}
				else
				{
					if($Zone ==6)
						$mes.="<p>Votre manque de discrétion a rameuté un patrouilleur ennemi qui croisait dans les parages".$chargeur_txt."<br>Par chance,vous leur échappez!</p>";
					else
						$mes.="<p>Votre manque de discrétion a rameuté la garnison toute proche".$chargeur_txt."<br>Par chance,vous leur échappez!</p>";
				}
			}
			$Credits=-1;
		break;
		//Ferme
		case 3:
			$img=Afficher_Image("images/mia_ferme.jpg","images/image.png","Ferme");
			$Cache=$Duperie-$Anti_Sab-mt_rand(0,50);
			if($Cache >0 or $Flag ==$country or $Amicaux)
			{
				$mes.="<p>Les habitants ne sont pas hostiles et vous soignent volontiers<br>Après quelques heures,ils vous demandent de partir</p>";
				UpdateCarac($PlayerID,"Endurance",1,"Pilote",10);
				UpdateCarac($PlayerID,"Moral",10);
			}
			else
			{
				if($Slot8 ==3 or $Slot8 ==45 or $Slot8 ==46 or $Slot8 ==47 or $Slot8 ==48)
				{
					$rnd_pr=mt_rand(0,10);
					if($Slot5 ==33 and $rnd_pr >1)
					{
						$prisonnier=false;
						SetData("Pilote","Slot5",0,"ID",$PlayerID);
						$chargeur_txt="<br>Vous tirez quelques cartouches pour faire diversion et vous enfuir!";
					}
					else
					{
						if($rnd_pr <5)
							$prisonnier=false;
						else
							$prisonnier=true;
					}
				}
				else
					$prisonnier=true;
				if($prisonnier)
				{
					$mes.="<p>Les habitants sont hostiles et donnent l'alerte<br>Les soldats ennemis ont tôt fait de vous arrêter et de vous faire prisonnier!</p>";
					$prisonnier=true;
					$rescue=false;
					$retour=false;
					$mia_lieu=false;
				}
				else
				{
					if($Zone ==6)
						$mes.="<p>Votre manque de discrétion a rameuté un patrouilleur ennemi qui croisait dans les parages".$chargeur_txt."<br>Par chance,vous leur échappez!</p>";
					else
						$mes.="<p>Votre manque de discrétion a rameuté la garnison toute proche".$chargeur_txt."<br>Par chance,vous leur échappez!</p>";
				}
			}
			$Credits=-2;
		break;
		//Recce Ferme
		case 4:
			$img=Afficher_Image("images/mia_ferme.jpg","images/image.png","Ferme");
			if($Courage_Eq >0 or $Trait_e ==6)
			{
				$Cache=mt_rand(0,50)-($Nuit*20);
				if($Cache <20 or $Amicaux)
				{
					if($Anti_Sab >0)
						$mes.="<p>".$Equipage_Nom." revient vous avertir qu'il a repéré des soldats ennemis non loin de là.<br>S'approcher pourrait être dangereux,vous risqueriez d'être fait prisonnier.</p>";
					else
						$mes.="<p>".$Equipage_Nom." revient vous avertir que le coin semble calme pour le moment.</p>";
				}
				else
				{
					$mes.="<p>Vous ne voyez pas ".$Equipage_Nom." revenir. Vous vous rendez à l'évidence,il ne reviendra plus!</p>";
					$con=dbconnecti();
					$reseteq=mysqli_query($con,"UPDATE Equipage SET Moral=0,Courage=0,Endurance=0,Abattu=Abattu+1 WHERE ID_ref=".$PlayerID);
					mysqli_close($con);
				}
				$Credits=-2;
			}
			else
				$mes.="<p>".$Equipage_Nom." refuse d'y aller,il estime que c'est trop dangereux pour lui!</p>";
		break;
		//Recce Ville
		case 5:
			$img=Afficher_Image("images/mia_ville.jpg","images/image.png","Ville");
			if($Courage_Eq >0 or $Trait ==6)
			{
				$Cache=mt_rand(0,50)-$Anti_Sab-($ValeurStrat*10)+($Nuit*20);
				if($Cache >0 or $Amicaux)
				{
					if($Anti_Sab >0)
						$mes.="<p>".$Equipage_Nom." revient vous avertir qu'il a repéré des soldats ennemis non loin de là.<br>S'approcher pourrait être dangereux,vous risqueriez d'être fait prisonnier.</p>";
					else
						$mes.="<p>".$Equipage_Nom." revient vous avertir que le coin semble calme pour le moment.</p>";
				}
				else
				{
					$mes.="<p>Vous ne voyez pas ".$Equipage_Nom." revenir. Vous vous rendez à l'évidence,il ne reviendra plus!</p>";
					$con=dbconnecti();
					$reseteq=mysqli_query($con,"UPDATE Equipage SET Moral=0,Courage=0,Endurance=0,Abattu=Abattu+1 WHERE ID_ref=".$PlayerID);
					mysqli_close($con);
				}
				$Credits=-2;
			}
			else
				$mes.="<p>".$Equipage_Nom." refuse d'y aller,il estime que c'est trop dangereux pour lui!</p>";
		break;
		//Aerodrome
		case 6:
			if(GetData("Pays","ID",$Flag,"Faction") ==GetData("Pays","ID",$country,"Faction"))
			{
				$Orientation=mt_rand(0,$Navigation)-($Nuit*100)-($Km/10);
				if($Orientation >0)
				{
					$mes.="<p>L'aérodrome est occupé par l'armée de votre nation.<br>Vous rejoignez votre unité sain et sauf!</p>";
					$retour=true;
				}
				else
					$mes.="<p>Vous vous perdez en chemin!</p>";
				$img=Afficher_Image("images/mia_airfield.jpg","images/image.png","Aérodrome");
			}
			else
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT DISTINCT ID FROM Unit WHERE Base='$MIA' ORDER BY RAND() LIMIT 1");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						$Unit_Base=$data['ID'];
				}
				if($Unit_Base)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Pays,Base,Reputation,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unit_Base'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Unite_Nom=$data['Nom'];
							$Pays=$data['Pays'];
							$Base=$data['Base'];
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
					}
					$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
					$Personnel=array_count_values($Pers);
					$Anti_Sab+=($Personnel[4]+$Pers_Sup)*50*$Bonus_Pers;
				}
				if($Nuit and $Slot8 ==2)$Nuit=2;
				$Cache=$Duperie-($ValeurStrat*10)+($Nuit*10)-mt_rand(10,100)-$Anti_Sab-$Garnison;
				if($Cache >0)
				{
					$mes.="<p>Vous vous infiltrez sur l'aérodrome ennemi et parvenez à vous glisser dans le hangar!</p>";
					$img=Afficher_Image("images/mia_stealavion.jpg","images/image.png","Décollage clandestin");
					$mia_lieu="aerodrome";					
					$Avion_det3=44; //Ju-52 par défaut si l'aérodrome n'est pas rattaché à une unité
					if(mt_rand(0,5) <1 or $Cache >100)
					{
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT DISTINCT ID,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE Base='$MIA' ORDER BY RAND() LIMIT 1");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								if($data['Avion1'] >0)
								{
									UpdateData("Unit","Avion1_Nbr",-1,"ID",$data['ID']);
									$Avion_txt1=GetData("Avion","ID",$data['Avion1'],"Nom");
									$Avion_det3=$data['Avion1'];
								}
								elseif($data['Avion2'] >0 and $Cache >50)
								{
									UpdateData("Unit","Avion2_Nbr",-1,"ID",$data['ID']);
									$Avion_txt2=GetData("Avion","ID",$data['Avion2'],"Nom");
									$Avion_det4=$data['Avion2'];
								}
								elseif($data['Avion3'] >0 and $Cache >100)
								{
									UpdateData("Unit","Avion3_Nbr",-1,"ID",$data['ID']);
									$Avion_txt3=GetData("Avion","ID",$data['Avion3'],"Nom");
									$Avion_det5=$data['Avion3'];
								}
							}
							mysqli_free_result($result);
						}
					}
					if(!$Avion_txt1)
						$Avion_txt1=GetData("Avion","ID",$Avion_det3,"Nom");
					$choix1="<Input type='Radio' name='Action' value='3'>- Monter à bord d'un ".$Avion_txt1." et décoller ! (1 Crédit Temps).<br>";
					if($Avion_txt2)
						$choix2="<Input type='Radio' name='Action' value='4'>- Monter à bord d'un ".$Avion_txt2." et décoller ! (1 Crédit Temps).<br>";
					if($Avion_txt3)
						$choix3="<Input type='Radio' name='Action' value='5'>- Monter à bord d'un ".$Avion_txt3." et décoller ! (1 Crédit Temps).<br>";
				}
				else
				{
					if($Slot8 ==3 or $Slot8 ==45 or $Slot8 ==46 or $Slot8 ==47 or $Slot8 ==48)
					{
						$rnd_pr=mt_rand(0,10);
						if($Slot5 ==33 and $rnd_pr >1)
						{
							$prisonnier=false;
							SetData("Pilote","Slot5",0,"ID",$PlayerID);
							$chargeur_txt="<br>Vous tirez quelques cartouches pour faire diversion et vous enfuir!";
						}
						else
						{
							if($rnd_pr <5)
								$prisonnier=false;
							else
								$prisonnier=true;
						}
					}
					else
						$prisonnier=true;
					if($prisonnier)
					{
						$mes.="<p>Vous tentez de vous infiltrer sur l'aérodrome ennemi,mais la garde veille et vous fait prisonnier!</p>";
						$prisonnier=true;
						$rescue=false;
						$retour=false;
						$mia_lieu=false;
					}					
					else
					{
						$mes.="<p>Le lieu est trop bien gardé !<br>Vous évitez les sentinelles ennemies de justesse!</p>";
						$img=Afficher_Image("images/garde".$Flag.".jpg","images/image.png","gardes");
					}
				}
			}
			$Credits=-2;
		break;
		//Nuit
		case 7:
			$mes.="<p>Vous attendez que la nuit tombe.</p>";
			$img=Afficher_Image("images/lieu/objectif_nuit5.jpg","images/image.png","Nuit");
			SetData("Pilote","S_Nuit",1,"ID",$PlayerID);
			$Credits=-2;
		break;
		//Retour Base
		case 8:
			$img=Afficher_Image("images/mia_airfield.jpg","images/image.png","Aérodrome");
			$Orientation=mt_rand(0,$Navigation)-($Nuit*100)-$Km;
			if($Orientation >0)
			{
				$mes.="<p>Vous rejoignez votre unité sain et sauf!</p>";
				$retour=true;
			}
			else
				$mes.="<p>Vous vous perdez en chemin!</p>";
			$Credits=-2;
		break;
		//Ville
		case 10:
			if($Flag ==$country)
			{
				$Orientation=mt_rand(0,$Navigation)-($Nuit*100)-($Km/10);
				if($Orientation >0)
				{
					$mes.="<p>La ville est contrôlé par l'armée de votre nation.<br>Vous rejoignez votre unité sain et sauf!</p>";
					$retour=true;
				}
				else
					$mes.="<p>Vous vous perdez en chemin!</p>";
				$img=Afficher_Image("images/mia_ville.jpg","images/image.png","Ville");
			}
			else
			{
				$Cache=$Duperie-$Anti_Sab-($ValeurStrat*10)+($Nuit*10)-$Garnison;
				if($Cache >0 or ($Nuit and $Slot8 ==2))
				{
					$mes.="<p>Vous vous approchez de la ville et parvenez à vous glisser à l'intérieur sans vous faire repérer!</p>";
					$img=Afficher_Image("images/mia_ville.jpg","images/image.png","Ville");
					$mia_lieu="ville";
					$Credits_act=GetData("Pilote","ID",$PlayerID,"Credits");
					if($Credits_act >=2)
						$choix1="<Input type='Radio' name='Action' value='1'>- Récolter un minimum de renseignements sur la ville (2 Crédits Temps).<br>";
					if($Credits_act >=4)
						$choix2="<Input type='Radio' name='Action' value='2'>- Récolter un maximum de renseignements sur la ville (4 Crédits Temps).<br>";
					$choix3="";
				}
				else
				{					
					if($Slot8 == 3 or $Slot8 == 45 or $Slot8 == 46 or $Slot8 == 47 or $Slot8 == 48)
					{
						$rnd_pr=mt_rand(0,10);
						if($Slot5 ==33 and $rnd_pr >1)
						{
							$prisonnier=false;
							SetData("Pilote","Slot5",0,"ID",$PlayerID);
							$chargeur_txt="<br>Vous tirez quelques cartouches pour faire diversion et vous enfuir!";
						}
						else
						{
							if($rnd_pr <5)
								$prisonnier=false;
							else
								$prisonnier=true;
						}
					}
					else
						$prisonnier=true;
					if($prisonnier)
					{
						$mes.="<p>Vous tentez de vous approcher de la ville,mais la garde veille et vous fait prisonnier!</p>";
						$prisonnier=true;
						$rescue=false;
						$retour=false;
						$mia_lieu=false;
					}
					else
					{
						if($Zone ==6)
							$mes.="<p>Votre manque de discrétion a rameuté un patrouilleur ennemi qui croisait dans les parages".$chargeur_txt."<br>Par chance,vous leur échappez!</p>";
						else
							$mes.="<p>Votre manque de discrétion a rameuté la garnison toute proche".$chargeur_txt."<br>Par chance,vous leur échappez!</p>";
					}
				}
			}
			$Credits=-2;
		break;
		//Chalutier
		case 11:
			$img=Afficher_Image("images/chalutier.jpg","images/image.png","Chalutier");
			$Cache=$Duperie-($Nuit*50)+mt_rand(-50,50);
			if($Cache >0)
			{
				$retour=true;
				$con=dbconnecti();
				$Nav_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$MIA' AND Pays='$country' AND Vehicule_Nbr >0"),0);
				mysqli_close($con);
				if($Longitude >67)
				{
					if($Nav_nbr >0)
						$mes.="<p>Des navires de votre nation présents dans les parages vous receuillent!<br>Vous êtes rapidement transféré vers votre unité d'origine.</p>";
					else
					{
						$mes.="<p>Aucun navire ne croise dans les parages...<br>Après quelques heures d'attente,vous perdez courage!</p>";
						UpdateCarac($PlayerID,"Moral",-10);
						UpdateCarac($PlayerID,"Courage",-10);
						$retour=false;
					}
				}
				else
				{
					if($Nav_nbr >0)
						$mes.="<p>Des navires de votre nation présents dans les parages vous receuillent<br>Vous êtes rapidement transféré vers votre unité d'origine.</p>";
					else
						$mes.="<p>Les pêcheurs ne sont pas hostiles et vous recueillent volontiers<br>Après une courte traversée,ils vous débarquent sur le rivage en vous souhaitant bonne chance.</p>";
				}
				if($retour)
					UpdateCarac($PlayerID,"Moral",10);
			}
			else
			{			
				if($Longitude >67)
					$mes.="<p>Un patrouilleur ennemi a tôt fait de vous repérer et de vous faire prisonnier!</p>";
				else
					$mes.="<p>Les pêcheurs sont hostiles et donnent l'alerte<br>Un patrouilleur ennemi a tôt fait de vous faire prisonnier!</p>";
				$prisonnier=true;
				$rescue=false;
				$retour=false;
				$mia_lieu=false;
			}
			$Credits=-2;
		break;
		//Services Secrets
		case 12:
			$img=Afficher_Image("images/agent.jpg","images/image.png","Agent Secret");
			$mes.="<p>Vous rejoignez votre unité sain et sauf!</p>";
			$retour=true;
			if($Slot11 ==26)
				$Credits=-12;
			else
				$Credits=-24;
		break;
		//Sabotage
		case 13:
			if(GetData("Pays","ID",$Flag,"Faction") == GetData("Pays","ID",$country,"Faction"))
			{
				$Orientation=mt_rand(0,$Navigation)-($Nuit*100)-$Km;
				if($Orientation >0)
				{
					$mes.="<p>L'aérodrome est contrôlé par l'armée de votre nation.<br>Vous rejoignez votre unité sain et sauf!</p>";
					$retour=true;
				}
				else
					$mes.="<p>Vous vous perdez en chemin!</p>";
				$img=Afficher_Image("images/mia_airfield.jpg","images/image.png","Aérodrome");
			}
			else
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT DISTINCT ID FROM Unit WHERE Base='$MIA' ORDER BY RAND() LIMIT 1");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						$Unit_Base=$data['ID'];
				}
				if($Unit_Base)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Pays,Base,Reputation,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unit_Base'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Unite_Nom=$data['Nom'];
							$Pays=$data['Pays'];
							$Base=$data['Base'];
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
					}
					$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
					$Personnel=array_count_values($Pers);
					$Anti_Sab=($Personnel[4]+$Pers_Sup)*50*$Bonus_Pers;
				}	
				if($result_mp >0)
					$Anti_Sab+=($result_mp*25);				
				if($Slot8 ==2)
					$Bonus_Infil=20; //Arme blanche
				elseif($Slot8 ==3 or $Slot8 ==45 or $Slot8 ==46 or $Slot8 ==47 or $Slot8 ==48)
					$Bonus_Infil=10; //Arme de poing
				else
					$Bonus_Infil=0;
				$Cache=$Duperie-($ValeurStrat*10)-mt_rand(10,100)+$Bonus_Infil-$Anti_Sab-$Garnison;
				if($Cache >0)
				{
					$Credits_act=GetData("Pilote","ID",$PlayerID,"Credits");
					$mes.="<p>Vous vous infiltrez sur l'aérodrome ennemi!</p>";
					$img=Afficher_Image("images/mia_sabotage.jpg","images/image.png","Décollage clandestin");
					$mia_lieu="sabotage";
					//
					if($Unit_Base and $Credits_act >11)
					{
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT DISTINCT ID,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE ID='$Unit_Base' AND Etat=1");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Avion1_Nbr=$data['Avion1_Nbr'];
								$Avion2_Nbr=$data['Avion2_Nbr'];
								$Avion3_Nbr=$data['Avion3_Nbr'];
								if($data['Avion1_Nbr'] >0)
								{								
									$Avion_txt1=GetData("Avion","ID",$data['Avion1'],"Nom");
									$Avion_det3=$data['Avion1'];
								}
								elseif($data['Avion2_Nbr'] >0)
								{
									$Avion_txt2=GetData("Avion","ID",$data['Avion2'],"Nom");
									$Avion_det4=$data['Avion2'];
								}
								elseif($data['Avion3_Nbr'] >0)
								{
									$Avion_txt3=GetData("Avion","ID",$data['Avion3'],"Nom");
									$Avion_det5=$data['Avion3'];
								}
								$Unitsa=$data['ID'];
							}
							mysqli_free_result($result);
							unset($data);
						}
						if($Unitsa and $Avion1_Nbr >0 and $Avion_txt1)
							$choix1="<Input type='Radio' name='Action' value='6'>- Tenter de saboter un ".$Avion_txt1." (12 Crédits Temps).<br>";
						if($Unitsa and $Avion2_Nbr >0 and $Avion_txt2)							
							$choix2="<Input type='Radio' name='Action' value='7'>- Tenter de saboter un ".$Avion_txt2." (12 Crédits Temps).<br>";
						if($Unitsa and $Avion3_Nbr >0 and $Avion_txt3)
							$choix3="<Input type='Radio' name='Action' value='8'>- Tenter de saboter un ".$Avion_txt3." (12 Crédits Temps).<br>";
					}
					else
					{
						$choix1="";
						$choix2="";
						$choix3="";
					}
					if($Credits_act >3)
						$choix4="<Input type='Radio' name='Action' value='9'>- Tenter de dérober du matériel (4 Crédits Temps).<br>";
					if($Credits_act >7 and $DefenseAA)
						$choix5="<Input type='Radio' name='Action' value='11'>- Tenter de saboter un canon de DCA (8 Crédits Temps).<br>";
					if($Unitsa and $Credits_act >11)
					{
						$choix6="<Input type='Radio' name='Action' value='12'>- Tenter de mettre le feu au stock de carburant (12 Crédits Temps).<br>";
						$choix7="<Input type='Radio' name='Action' value='13'>- Tenter de mettre le feu au stock de munitions (12 Crédits Temps).<br>";
					}
					else
					{
						$choix6="";
						$choix7="";
					}
				}
				else
				{
					if($Slot8 ==3 or $Slot8 ==45 or $Slot8 ==46 or $Slot8 ==47 or $Slot8 ==48)
					{
						$rnd_pr=mt_rand(0,10);
						if($Slot5 ==33 and $rnd_pr >1)
						{
							$prisonnier=false;
							SetData("Pilote","Slot5",0,"ID",$PlayerID);
							$chargeur_txt="<br>Vous tirez quelques cartouches pour faire diversion et vous enfuir!";
						}
						else
						{
							if($rnd_pr <5)
								$prisonnier=false;
							else
								$prisonnier=true;
						}
					}
					else
						$prisonnier=true;
					if($prisonnier)
					{
						$mes.="<p>Vous tentez de vous infiltrer sur l'aérodrome ennemi,mais la garde veille et vous fait prisonnier!</p>";
						$prisonnier=true;
						$rescue=false;
						$retour=false;
						$mia_lieu=false;
					}
					else
					{
						$mes.="<p>Le lieu est trop bien gardé !<br>Vous évitez les sentinelles ennemies de justesse!</p>";
						$img=Afficher_Image("images/garde".$Flag.".jpg","images/image.png","gardes");
					}
				}
			}
			$Credits=-2;
		break;
		case 14:
			$img=Afficher_Image("images/lost.jpg","images/image.png","Perdu!");
			$mes.="<p>Vous errez en méditant sur votre incompétence.<br>La prochaine fois,vous veillerez sans doute à vous équiper de manière adéquate ou à ne pas vous aventurer là où votre hiérarchie ne vous a pas dit d'aller!</p>";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,Commando=0,Moral=0,Courage=0,Endurance=0 WHERE ID='$PlayerID'");
			mysqli_close($con);
			$retour=true;
			$Credits=-24;
		break;
	}
	if($mia_lieu)
	{
		$menu.="<form action='mission_mia2.php' method='post'>
		<input type='hidden' name='meteo' value=".$meteo.">
		<input type='hidden' name='MIA' value=".$MIA.">
		<input type='hidden' name='Av1' value=".$Avion_det3.">
		<input type='hidden' name='Av2' value=".$Avion_det4.">
		<input type='hidden' name='Av3' value=".$Avion_det5.">
		<input type='hidden' name='Unitsa'  value=".$Unitsa.">
		<table class='table'>
			<thead><tr><td colspan='8'>Infiltration</td></tr></thead>
			<tr><th>Choix de l'action</th>
				<td align='left'>
					".$choix1.$choix2.$choix3.$choix4.$choix5.$choix6.$choix7."
					<Input type='Radio' name='Action' value='10'>- Retourner à votre campement (1 Crédit Temps).<br>
			</td></tr>
		</table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}	
	if($rescue)
	{
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT p.*,a.Detection,a.ManoeuvreB,a.Nom as Plane FROM Pilote_IA as p,Avion as a WHERE p.Avion=a.ID AND p.Cible='$MIA' AND p.Task=4 AND p.Avion>0 AND p.Pays='$country' AND p.Actif=1 ORDER BY RAND() LIMIT 1");
		mysqli_close($con);	
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Pilote_ia=$data2['ID'];
				$avion=$data2['Avion'];
				$NomAvion=$data2['Plane'];
				$NomPilote=$data2['Nom'];
				$Unit_ia=$data2['Unit'];
				$Pilotage_ia=$data2['Pilotage'];
				$Acrobatie_ia=$data2['Acrobatie'];
				$Vue_ia=$data2['Vue'];
				$Detection_ia=$data2['Detection'];
				$Man_ia=$data2['ManoeuvreB'];
			}
			mysqli_free_result($result2);
		}		
		if(!$avion)
		{
			switch($country)
			{
				case 1:
					$NomAvion="Henschel Hs126";
					$avion=47;
				break;
				case 2:
					$NomAvion="Westland Lysander";
					$avion=48;
				break;
				case 3:
					$NomAvion="Fairey Fox";
					$avion=40;
				break;
				case 4:
					$NomAvion="Potez 63.11";
					$avion=23;
				break;
				case 6:
					$NomAvion="Caproni Ca.311";
					$avion=67;
				break;
				case 7:
					$NomAvion="Piper L-4";
					$avion=401;
				break;
				case 8:
					$NomAvion="Polikarpov R-Z";
					$avion=298;
				break;
				case 9:
					$NomAvion="Mitsubishi Ki-15";
					$avion=359;
				break;
			}
			$NomPilote="Inconnu";
			$Pilotage_ia=mt_rand(25,100);
			$Acrobatie_ia=mt_rand(25,50);
			$Vue_ia=mt_rand(25,100);
			$Detection_ia=20;
			$Man_ia=150;
		}
		$rescue_ok=false;
		$Detect_ia=mt_rand(0,$Vue_ia)+($Nuit*10)+$Detection_ia;
		if($Detect_ia >(mt_rand(0,50)+GetMalusReperer($Zone)))
			$mes.="<p>Un <b>".$NomAvion."</b> repère vos signaux et descend vers vous<br>";
		else
		{
			$mes.="<p>Un <b>".$NomAvion."</b> passe au loin sans repérer vos signaux<br>";
			$retour=false;
			$mia_lieu=true;
		}
		if($DefenseAA >0)
		{
			if(($DefenseAA*10)>(mt_rand(0,$Pilotage_ia)+mt_rand(0,$Acrobatie_ia)+($Man_ia/10)))
			{
				$mes.="La défense anti-aérienne locale ouvre le feu et touche le <b>".$NomAvion."</b> qui s'abat en flammes!<br>Ce sera peut-être pour une autre fois...</p>";
				if($Unit_ia >0 and $Pilote_ia >0)
				{
					AddEvent("Avion",97,$avion,$Pilote_ia,$Unit_ia,$MIA,$Nuit,17);
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Avion1,Avion2,Avion3 FROM Unit WHERE ID='$Unit_ia'");
					$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Task=0 WHERE ID='$Pilote_ia'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Avion_lose=$data['Avion'];
							if($Avion_lose == $data['Avion3'])
								$Avion_Flight_Lose="Avion3_Nbr";
							elseif($Avion_lose == $data['Avion2'])
								$Avion_Flight_Lose="Avion2_Nbr";
							else
								$Avion_Flight_Lose="Avion1_Nbr";
						}
						mysqli_free_result($result);
					}
					WoundPilotIA($Pilote_ia);
					UpdateData("Unit",$Avion_Flight_Lose,-1,"ID",$Unit_ia);
				}
				$retour=false;
				$mia_lieu=true;
			}
			else
			{
				$mes.="La défense anti-aérienne locale ouvre le feu,mais le <b>".$NomAvion."</b> parvient à atterrir sans casse.</p>";
				$rescue_ok=true;
			}
		}
		else
		{
			$mes.="Le <b>".$NomAvion."</b> parvient à atterrir sans casse.</p>";
			$rescue_ok=true;
		}
		if($rescue_ok)
		{
			$mes.="<p>Vous montez à bord du <b>".$NomAvion."</b> du pilote <b>".$NomPilote."</b> et décollez pour rejoindre votre base.</p>";
			//SetData("Pilote","Credits",3,"ID",$PlayerID);
			AddEvent($Avion_db,36,$avion,$PlayerID,$Unite,$MIA,0);
			if($Unit_ia >0)
				UpdateData("Unit","Reputation",10,"ID",$Unit_ia,0,10);
			$retour=true;
		}
	}
	MoveCredits($PlayerID,1,$Credits);	
	if($prisonnier)
	{
		if($Reputation >100000 or $Agent_Special)
		{
			$valeur_txt="Votre réputation vous assure une libération immédiate par l'intermédiaire d'un pays neutre,en échange d'un officier ennemi.";
			$retour=true;
		}
		elseif($Reputation >10000)
		{
			$valeur_txt="Etant un élément de valeur,votre prochaine libération ne saurait tarder.<br>D'ici à ce qu'elle arrive,vous croupissez quelques temps dans un camp de prisonniers ennemi.<br>Tout votre équipement vous est confisqué!";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Slot1=0,Slot2=0,Slot3=0,Slot4=0,Slot5=0,Slot6=0,Slot7=0,Slot8=0,Slot9=0,Slot10=0,Slot11=0 WHERE ID='$PlayerID'");
			mysqli_close($con);
		}
		else
		{
			$valeur_txt="Etant un illustre inconnu aux yeux de votre hiérarchie,vous croupissez quelques temps dans un camp de prisonniers ennemi.<br>Tout votre équipement vous est confisqué!";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Credits=0,Missions_Max=6,Missions_Jour=6,Slot1=0,Slot2=0,Slot3=0,Slot4=0,Slot5=0,Slot6=0,Slot7=0,Slot8=0,Slot9=0,Slot10=0,Slot11=0 WHERE ID='$PlayerID'");
			mysqli_close($con);
		}
		$mes.="<p>".$valeur_txt."</p>";
		$img=Afficher_Image("images/stalag.jpg","images/image.png","Campement");
	}
	if($retour)
	{
		SetData("Pilote","MIA",0,"ID",$PlayerID);
		$menu.="<form action='promotion.php' method='post'>
			<input type='hidden'  name='Blesse'  value=".$blesse.">
			<input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	elseif(!$mia_lieu)
	{
		$menu="<a title='Retour à votre campement' href='index.php?view=mission_start' class='btn btn-default'>Retour à votre campement</a>";
		$mes.="<p><b>FIN DE MISSION</b></p>";
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