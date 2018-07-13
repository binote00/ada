<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
/*if($_SESSION['PlayerID'] ==1 or $_SESSION['PlayerID'] ==2)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
$Action=Insec($_POST['Action']);
$meteo=Insec($_POST['Meteo']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$HP_eni=Insec($_POST['HP_eni']);
$Pays_eni=Insec($_POST['Pays_eni']);
$Deleguer=Insec($_POST['Deleguer']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$Lock=Insec($_POST['Cible_lock']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	$_SESSION['bombarder']=false;
	$_SESSION['attaquer']=false;
	$_SESSION['photographier']=false;
	$_SESSION['objectif']=false;
	$_SESSION['naviguer']=false;
	//$_SESSION['cibler']=true;
	$Distance=$_SESSION['Distance'];
	$country=$_SESSION['country'];
	$Chk_Bomb=$_SESSION['cibler'];	
	$retour=false;
	$dive=false;
	$attaque=false;
	$end_mission=false;
	$UpdateMoral=0;
	$UpdateCourage=0;
	$UpdateTactique=0;
	if($Pays_eni ==10)$Pays_eni=2;
	if($Chk_Bomb)
	{
		$intro="<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>";
		MoveCredits($PlayerID,90,-1);
		UpdateCarac($PlayerID,"Reputation",-10);
		UpdateCarac($PlayerID,"Avancement",-10);
		mail("binote@hotmail.com","Aube des Aigles: Init Mission F5 (bomb) : ".$PlayerID ,"Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Avancement,S_HP,Unit,Equipage,Pilotage,Tactique,Vue,Courage,Moral,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,S_Blindage,
	S_Avion_db,S_Nuit,S_Cible,S_Mission,S_Cible_Atk,S_Longitude,S_Latitude,S_Equipage_Nbr,S_Engine_Nbr,S_Strike,S_Pass,S_Formation,Simu,
	Slot5,Slot10,Slot11,Admin FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb-player');
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
			$Avancement=$data['Avancement'];
			$HP=$data['S_HP'];
			$Unite=$data['Unit'];
			$Pilotage=$data['Pilotage'];
			$Tactique=$data['Tactique'];
			$Vue=$data['Vue'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Avion_db=$data['S_Avion_db'];
			$Nuit=$data['S_Nuit'];
			$Cible=$data['S_Cible'];
			$Mission_Type=$data['S_Mission'];
			$Cible_Atk=$data['S_Cible_Atk'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Bombs=$data['S_Avion_Bombe_Nbr'];
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$S_Blindage=$data['S_Blindage'];
			$Strike=$data['S_Strike'];
			$S_Pass=$data['S_Pass'];
			$Formation=$data['S_Formation'];
			$Slot5=$data['Slot5'];
			$Slot10=$data['Slot10'];
			$Slot11=$data['Slot11'];
			$Admin=$data['Admin'];
			$Simu=$data['Simu'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($Pilotage >50)$Pilotage=50;
	if($Tactique >50)$Tactique=50;
	if($Vue >50)$Vue=50;
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(30,$Skills_Pil))
			$Trompe_la_mort=50;
		if(in_array(41,$Skills_Pil))
			$AsZigZag=50;
		if(in_array(50,$Skills_Pil))
			$Bonne_Etoile=true;
		if(in_array(78,$Skills_Pil))
			$Discipline_fer=true;
		if(in_array(94,$Skills_Pil))
			$ExpTac=50;
		elseif(in_array(94,$Skills_Pil))
			$ExpTac=25;
	}
	if($HP <1)
		$end_mission=true;
	else
	{
		if($Equipage)
			$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");
		if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
			$Vue_Equipage=GetData("Equipage","ID",$Equipage,"Vue");			
		if($Slot11 ==69)
		{
			$Moral+=50;
			$Courage+=50;
		}		
		$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$result=mysqli_query($con,"SELECT Type,Puissance,Robustesse,Masse,Plafond,ArmePrincipale,Blindage,Detection,Radar FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb-avion');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Type_avion=$data['Type'];
				$HPmax=$data['Robustesse'];
				$Puissance_ori=$data['Puissance'];
				$Masse=$data['Masse'];
				$Plafond=$data['Plafond'];
				$ArmeAvion=$data['ArmePrincipale'];
				$BonusDetect=$data['Detection'];
				$Blindage=$data['Blindage'];
				$Radar=$data['Radar'];
			}
			mysqli_free_result($result);
		}
		$BonusDetect+=$Vue_Equipage;		
		$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,0,$Pilotage);
		$avion_img=GetAvionImg($Avion_db,$avion);		
		$Flak_PJ_Ground=false;
		if($Pilotage <50 or $Avancement <1000)$Noob=true;		
		$Zone=GetData("Lieu","ID",$Cible,"Zone");
		if($Zone ==6 or $Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13) // and $Cible_Atk >9)
		{
			if($Cible_Atk ==23)
				$DefenseAA=0;
			else
			{
				//DCA Flotte
				$con=dbconnecti();
				/*$Flak_PJ_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND c.Flak >0 AND c.Portee >='$alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5)"),0);*/
				$Flak_IA_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND c.Flak >0 AND c.Portee >='$alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5)"),0);
				mysqli_close($con);
				$Flak_PJ_Ground+=$Flak_IA_Ground;
				if(!$Flak_PJ_Ground)
				{			
					$intro.="<p>La DCA reste silencieuse!</p>";
					$DefenseAA=0;
				}
			}
		}
		elseif($Mission_Type <6)
		{
			$con=dbconnecti();
			/*$Flak_PJ_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Cible as c,Pays as p 
			WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND p.Faction<>'$Faction' 
			AND c.Flak >0 AND c.Portee >='$alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5)"),0);
			if(!$Flak_PJ_Ground)
			{*/
				$Flak_IA_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c,Pays as p 
				WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND p.Faction<>'$Faction' 
				AND c.Flak >0 AND c.Portee >='$alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5)"),0);
			//}
			mysqli_close($con);
			if(!$Flak_PJ_Ground and !$Flak_IA_Ground)
				$DefenseAA=0;
		}
		elseif($Mission_Type ==101)
			$DefenseAA=0;
		else //DCA autres cibles
		{	
			if(!$DefenseAA and $Cible_Atk !=23)
			{
				$DefenseAA=GetData("Lieu","ID",$Cible,"DefenseAA");
				$DefenseAA_temp=GetData("Lieu","ID",$Cible,"DefenseAA_temp");
				if($Mission_Type ==16)
				{
					if($DefenseAA >3 and $DefenseAA_temp <4)
						$DefenseAA=4;
				}
				elseif($Mission_Type ==15)
				{
					if($DefenseAA_temp >$DefenseAA)
						$DefenseAA_temp=$DefenseAA;
				}
			}
			if($DefenseAA_temp >$DefenseAA)$DefenseAA=$DefenseAA_temp;
		}
		/*if(!$Simu and $DefenseAA >1)$DefenseAA-=2;*/
		//Boost
		if($c_gaz ==130)
			UpdateData("Pilote","Stress_Moteur",10,"ID",$PlayerID);		
		if($HP)
		{
			$moda=$HPmax/$HP;
			if(!$moda)$moda=$HPmax;
			if($Avion_db =="Avion" and $Bombs >0 and $Avion_Bombe)
			{
				$charge_sup=2/($Masse/($Avion_Bombe*$Bombs));
				$moda*=(1+ $charge_sup);
			}
			$Plafond=round($Plafond/$moda);
			if($alt >$Plafond)$alt=$Plafond;
			if($alt >6000 and $c_gaz <60)
			{
				$alt=5000+mt_rand(-500,500);
				$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
			}
			$VitAvion=GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,1,$c_gaz,$flaps);
			$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,0,$PlayerID,$Unite);
			if($VitAvion <50 or $c_gaz <20)
				$Action=98;
		}
		else
			$Action=98;
		switch($Action)
		{
			case 1: case 4:
				//high
				/*if($c_gaz >50)
					$alt=mt_rand($Plafond-1000,$Plafond);
				else
				{
					$alt=5000+mt_rand(-500,500);
					$intro.="<p>Le manque de puissance du moteur ne vous permet pas d'atteindre l'altitude voulue.</p>";
				}*/
			break;
			case 2: case 5:
				//actual
			break;
			case 3: case 6:
				//low
				if($alt >500)$alt=500;
				$ArmeAvion_nbr=GetData($Avion_db,"ID",$avion,"Arme1_Nbr");
				$Mun=1;
				if(!$Chk_Bomb)
				{
					$courage_dca=1+$DefenseAA;
					$UpdateCourage+=$courage_dca;
				}
			break;
			case 7:
				if($alt >1000)$alt=1000;
				$ArmeAvion_nbr=GetData($Avion_db,"ID",$avion,"Arme1_Nbr");
				$Mun=1;
				if(!$Chk_Bomb)
				{
					$courage_dca=1+$DefenseAA;
					$UpdateCourage+=$courage_dca;
				}
			break;
			case 8: case 9:
				//low
				if($alt >100)$alt=100;
				$ArmeAvion_nbr=GetData($Avion_db,"ID",$avion,"Arme1_Nbr");
				$Mun=1;
				if(!$Chk_Bomb)
				{
					$courage_dca=5+$DefenseAA;
					$UpdateCourage+=$courage_dca;
				}
			break;
			case 13:
				//low
				if($alt >500)$alt=500;
				$Arme2Avion=GetData($Avion_db,"ID",$avion,"ArmeSecondaire");
				$ArmeAvion_nbr=GetData($Avion_db,"ID",$avion,"Arme2_Nbr");
				$Mun=2;
				if(!$Chk_Bomb)
				{
					$courage_dca=1+$DefenseAA;
					$UpdateCourage+=$courage_dca;
				}
			break;
			case 80:
				//low
				if($alt >100)$alt=100;
				$ArmeAvion=177;
				$ArmeAvion_nbr=$Bombs;
				if(!$Chk_Bomb)
				{
					$courage_dca=1+$DefenseAA;
					$UpdateCourage+=$courage_dca;
				}
			break;
			case 90:
				//pvp
				$img=Afficher_Image("images/facetoface.jpg","images/facetoface.jpg","combat pvp");
				$intro.="<p>Vous affrontez votre adversaire!</p>";
				$retour=true;
			break;
			case 98:
				//too low
				$intro.="<br>La vitesse de votre avion est insuffisante pour vous maintenir en vol.";
				$retour=true;
				$end_mission=true;
			break;
			case 99:
				//cancel
				$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
				$intro.="<br>Vous stoppez votre attaque.";
				$retour=true;
			break;
		}		
		if($alt <100) //Si rase-mottes, bonus de terrain pour attaquant, mais check pilotage
		{
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
				case 2:
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
				case 9:
					$zone_txt="jungle";
					$Malus_Reperer=30;
				break;
				case 11:
					$zone_txt="marécage";
					$Malus_Reperer=10;
				break;
			}
			$Malus_Reperer-=$meteo;
			if(mt_rand(10,$Pilotage)+$Trompe_la_mort <$Malus_Reperer+($VitAvion/10))
			{
				$intro.="<p>Votre partie de saute-moutons avec la mort se termine mal, votre avion percute une ".$zone_txt."</p>";
				$retour=true;
				$end_mission=true;
			}
			else
				$intro.="<p>Au ras du sol, vous engagez une partie de saute-moutons avec la mort!</p>";
		}		
		if(!$retour)
		{
			$Malus_Reperer-=$meteo; //2e fois, normal
			$Malus_Range=$alt/100;
			if($alt <100) //Si rase-mottes, bonus de terrain pour attaquant, mais check pilotage
				$Malus_Range+=GetMalusReperer($Zone);
			if($Type_avion ==2 or $Type_avion ==3 or $Type_avion ==7 or $Type_avion ==9 or $Type_avion ==11)
			{
				if($DefenseAA >4 and $alt >7000 and !$Chk_Bomb)
					$UpdateTactique+=1;
			}
			if($Mission_Type ==13)//En cas de mission de torpillage, DCA plus difficile à toucher si tir à distance de sécurité.
			{
				if($Action ==3 or $Action ==6)
					$vis_debug=$VisAvion/2;
				else
					$vis_debug=$VisAvion*($alt/50);
			}
			elseif($Mission_Type ==12 and $Action ==7)
				$vis_debug=$VisAvion/2;
			elseif($Type_avion ==2)
				$vis_debug=$VisAvion/($Malus_Range/5);
			else
				$vis_debug=$VisAvion;
			if($Type_avion ==11)
				$vis_debug=$VisAvion/($Malus_Range/5);
			if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1 and !$Chk_Bomb)
				UpdateCarac($Equipage,"Navigation",1,"Equipage");			
			$OK_DCA=false;
			if($Cible_Atk ==1)
			{
				$Alt_Flak_min=$alt-1000;
				$Alt_Flak_max=$alt+1000;
				$Projo=0;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT COUNT(*),DCA_ID FROM Flak WHERE Lieu='$Cible' AND (Alt BETWEEN '$Alt_Flak_min' AND '$Alt_Flak_max')");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result))
					{
						if($data[1] ==63 and $alt <=3000)
							$Projo +=10;
						elseif($data[1] ==64 and $alt <=6000)
							$Projo +=10;
						elseif($data[1] ==65 and $alt <=10000)
							$Projo +=10;
						$OK_DCA=$data[0];
					}
					mysqli_free_result($result);
				}
			}
			if($OK_DCA or $Flak_PJ_Ground or $Flak_IA_Ground)
			{
				//NEW
				if($Equipage)
				{
					$con=dbconnecti();
					$Eq_res=mysqli_query($con,"SELECT Moral,Courage,Nom,Mecanique FROM Equipage WHERE ID='$Equipage'");
					mysqli_close($con);
					if($Eq_res)
					{
						while($data_eq=mysqli_fetch_array($Eq_res))
						{
							$Eq_Moral=$data_eq['Moral'];
							$Eq_MCourage=$data_eq['Courage'];
							$Equipage_Nom=$data_eq['Nom'];
							$Meca=floor($data_eq['Mecanique']/2);
						}
						mysqli_free_result($Eq_res);
						unset($data_eq);
					}			
				}				
				if($OK_DCA)
				{
					$query="SELECT DCA_ID,DCA_Exp,DCA_Nbr,Unit FROM Flak WHERE Lieu='$Cible' AND DCA_Nbr >0 AND (Alt BETWEEN '$Alt_Flak_min' AND '$Alt_Flak_max')";
					$Unit_table="Unit";
				}
				/*elseif($Flak_PJ_Ground)
				{
					$query="SELECT r.ID,r.Vehicule_ID,r.Experience,r.Vehicule_Nbr,r.Skill,r.Matos,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment as r, Cible as c, Pays as p 
					WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
					AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee >='$alt' AND r.Position IN(1,5) ORDER BY r.Experience DESC LIMIT 2";
					$Unit_table="Regiment";
				}*/
				elseif($Flak_IA_Ground)
				{
					$query="SELECT r.ID,r.Vehicule_ID,r.Experience,r.Vehicule_Nbr,r.Skill,r.Matos,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment_IA as r, Cible as c, Pays as p 
					WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
					AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee >='$alt' AND r.Position IN(1,5) ORDER BY r.Experience DESC LIMIT 2";
					$Unit_table="Regiment_IA";
				}
				$con=dbconnecti();
				$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb-ok_dca');
				mysqli_close($con);
				if($result)
				{
					if(!$Blindage)
					{
						$Blindage=$S_Blindage;
						if(!$Blindage)$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
					}
					if($Nuit and $Projo)
						$intro.="<br>De puissants projecteurs illuminent le ciel!";
					$intro.="<br>Les explosions de DCA encadrent votre appareil!
					<br>Vous vous trouvez à ".$alt."m d'altitude. <b>La défense anti-aérienne ouvre le feu sur vous!</b>";
					if($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13 or $Mission_Type ==14 or $Zone == 6)
						$img=Afficher_Image("images/flak_nav.jpg","images/image.png","D.C.A Navale");
					else
					{
						if($Nuit)
							$img="<img src='images/flak_nuit.jpg' style='width:50%;'>";
						else
							$img=Afficher_Image('images/flak3'.$Pays_eni.'.jpg',"images/image.png","D.C.A");
					}				
					//Boucle pièces
					$attaque=true;
					while($data=mysqli_fetch_array($result))
					{
						$Malus_Range_dca=$Malus_Range;
						if($OK_DCA)
						{
							$DCA_ID=$data['DCA_ID'];
							$DCA_Unit=$data['Unit'];
							$DCA_EXP=$data['DCA_Exp']*25;	
							$DCA_Nbr=$data['DCA_Nbr'];
						}
						else
						{
							if($data['Arme_AA3'] >0 and $alt <500)
								$DCA_ID=$data['Arme_AA3'];
							elseif($data['Arme_AA2'] >0 and $alt <4000)
								$DCA_ID=$data['Arme_AA2'];
							else
								$DCA_ID=$data['Arme_AA'];
							$DCA_Unit=$data['ID'];
							$DCA_EXP=$data['Experience'];	
							$DCA_Nbr=$data['Vehicule_Nbr'];
							$Vehicule_ID=$data['Vehicule_ID'];
							if($data['mobile'] ==5) //Navire
								$Range=GetData("Armes","ID",$DCA_ID,"Portee");
							else
								$Range=$data['Portee'];
							if($Range >$alt)
								$Malus_Range_dca+=(($Range-$alt)/100);
						}
						//Bonus DCA si seconde passe
						$Bonus_2passe=0;
						if($S_Pass)
							$Bonus_2passe=$DCA_EXP+($S_Pass*25);							
						elseif($Strike)
							$Bonus_2passe=$DCA_EXP+50;
						//Muns
						$dca_cal=round(GetData("Armes","ID",$DCA_ID,"Calibre"));
						if($dca_cal)
						{
							if($dca_cal >40 and $alt <501 and $Type_avion !=11)
							{
								$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
								$attaque=true;
							}
							else
							{
								$dca_mult=GetData("Armes","ID",$DCA_ID,"Multi")*mt_rand(1,$DCA_Nbr);
								if($Flak_IA_Ground)
									$DCA_mun=9999;
								else
									$DCA_mun=GetData($Unit_table,"ID",$DCA_Unit,"Stock_Munitions_".$dca_cal);
								if($DCA_mun >=$dca_mult)
								{
									if(!$Flak_IA_Ground)
										UpdateData($Unit_table,"Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$DCA_Unit);
									if($OK_DCA)
										AddEvent($Avion_db,76,$avion,$PlayerID,$Unite,$Cible,$dca_mult,$DCA_ID);
									elseif(!$Flak_IA_Ground)
										AddEvent($Avion_db,276,$avion,$PlayerID,$DCA_Unit,$Cible,$dca_mult,$DCA_ID);
									if($alt <501)
										$Detect=1;
									elseif($Mission_Type ==5 or $Mission_Type ==15)
										$Detect=mt_rand(0,$DCA_EXP)+$meteo-$Malus_Range_dca;
									else
										$Detect=mt_rand(0,$DCA_EXP)+$VisAvion+$meteo-$Malus_Range_dca-($Nuit*100)+$Projo+($Formation*$VisAvion);
									//Trait Anti-aérien
									if($Flak_PJ_Ground)
									{
										$Officier_DCA=GetData("Regiment","ID",$DCA_Unit,"Officier_ID");
										if(IsSkill(30,$Officier_DCA))
										{
											$Detect+=10;
											$Bonus_2passe=$DCA_EXP+50;
										}
									}
									elseif($data['Skill'] ==30)
									{
										$Detect+=10;
										$Bonus_2passe=$DCA_EXP+50;
									}
									if($Detect or $Chk_Bomb)
									{			
										$Shoot_Dca=mt_rand(0,$DCA_EXP)+$dca_mult;
										if($data['Matos'] ==3)$Shoot_Dca+=2;
										elseif($data['Matos'] ==9)$Shoot_Dca+=5;
										elseif($data['Matos'] ==12)$Shoot_Dca+=10;
										elseif($data['Matos'] ==22)$Shoot_Dca+=5;
										if($alt <5000 and $VitAvion <$Shoot_Dca)$Shoot_Dca+=((5000-$alt)/50);
										$DCA_dg=GetData("Armes","ID",$DCA_ID,"Degats");
										//DCA sur Formation
										if($DCA_Nbr >1 and $Formation >0)
										{
											$Formation_abattue=0;
											$con=dbconnecti();
											$resultf=mysqli_query($con,"SELECT Avion1,Avion2,Avion3 FROM Unit WHERE ID='$Unite'");
											$resultp=mysqli_query($con,"SELECT p.ID,p.Nom,p.Pilotage,p.Tactique,p.Avion,a.Visibilite,a.VitesseB,a.Blindage,a.Robustesse FROM Pilote_IA as p,Avion as a 
											WHERE p.Avion=a.ID AND p.Unit='$Unite' AND p.Cible='$Cible' AND p.Actif=1 ORDER BY RAND() LIMIT '$DCA_Nbr'");
											//mysqli_close($con);
											if($resultf)
											{
												while($data=mysqli_fetch_array($resultf,MYSQLI_ASSOC))
												{
													$Avion1_dca=$data['Avion1'];
													$Avion2_dca=$data['Avion2'];
													$Avion3_dca=$data['Avion3'];
												}
												mysqli_free_result($resultf);
											}
											if($resultp)
											{
												while($dataa=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
												{
													$Pilote_ia_dca=$dataa['ID'];
													$Nom_pilote_ia=$dataa['Nom'];
													$Avion_dca=$dataa['Avion'];
													$VisAvion_dca=$dataa['Visibilite'];
													$Tactique_dca=$dataa['Tactique']+$ExpTac;
													$Pilotage_dca=$dataa['Pilotage'];
													$VitAvion_dca=$dataa['VitesseB'];
													$Blindage_dca=$dataa['Blindage'];
													$Robustesse_dca=$dataa['Robustesse'];
													$Shoot=$Shoot_Dca+$meteo+$VisAvion_dca-$Malus_Range_dca-($Tactique_dca/10)-($Pilotage_dca/10)-($VitAvion_dca/10)+$Bonus_2passe;
													if($Shoot >1 or $Chk_Bomb)
													{
														$Degats=round((mt_rand(1,$DCA_dg)-pow($Blindage_dca,2))*GetShoot($Shoot,$dca_mult));
														if($data['Matos'] ==22)$Degats*=1.1;
														AddEvent("Avion",179,$Avion_dca,$Pilote_ia_dca,$Unite,$Cible,2,$Pays_eni);
														if($alt <4500)$Degats+=ceil($VisAvion_dca);
														if($Degats >$Robustesse_dca)
														{
															$intro.="<br>L'explosion met le feu à l'avion de ".$Nom_pilote_ia.", ne lui laissant pas d'autre choix que de sauter en parachute!";
															//$con=dbconnecti();
															$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0 WHERE ID='$Pilote_ia_dca'");
															//mysqli_close($con);
															if($Avion_dca ==$Avion1_dca)
																$Avion1_Nbr_dca+=1;
															elseif($Avion_dca ==$Avion2_dca)
																$Avion2_Nbr_dca+=1;
															elseif($Avion_dca ==$Avion3_dca)
																$Avion3_Nbr_dca+=1;
															$Formation-=1;
															$Formation_abattue+=1;
															if($Avion_Bombe ==100 and $Bombs ==10)
															{
																//$con=dbconnecti();
																$reset3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=0,Vehicule_Nbr=0,Moral=0 WHERE Officier_ID='$Cible_Atk' ORDER BY RAND() LIMIT 1");
																//mysqli_close($con);
																$intro.="<br>Une compagnie de parachutistes a été perdue!";
															}
															if(!$Discipline_fer or mt_rand(0,1) >0)
																WoundPilotIA($Pilote_ia_dca);
														}
														else
															$intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", mais il peut heureusement continuer sa mission!";
													}
												}
												mysqli_free_result($resultp);
											}
											if($Formation_abattue >0)
											{
												//$con=dbconnecti();
												$reset=mysqli_query($con,"UPDATE Unit SET Avion1_Nbr=Avion1_Nbr-'$Avion1_Nbr_dca',Avion2_Nbr=Avion2_Nbr-'$Avion2_Nbr_dca',Avion3_Nbr=Avion3_Nbr-'$Avion3_Nbr_dca',Reputation=Reputation-'$Formation_abattue' WHERE ID='$Unite'");
												//mysqli_close($con);
												SetData("Pilote","S_Formation",$Formation,"ID",$PlayerID);
											}
											mysqli_close($con);
										}
										/*if($Zone ==6)
										{
											$headers='MIME-Version: 1.0' . "\r\n";
											$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
											$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo." / DCA Unit=".$DCA_Unit." / Navire=".$Vehicule_ID." / DCA=".$DCA_ID."</p>";
											$msgm.="<br>[Score de Tir : ".$Shoot."]
																<br>+Vis ".$vis_debug."
																<br>-Meteo ".$meteo."
																<br>-Malus_Range ".$Malus_Range."
																<br>-Speed ".$VitAvion." /10
																<br>-Pilotage ".$Pilotage." /10
																<br>-Tactique ".$Tactique." /10
																<br>+S_Pass ".$Bonus_2passe."
																<br>+Tir_eni :".$Shoot_Dca."</body></html>";
											mail('binote@hotmail.com','Aube des Aigles: DCA Bomb Log bomb.php',$msgm,$headers);
										}*/
										/*if($alt <101)
										{
											$debug_msg="Shoot_DCA(alt=".$alt." ; avion=".$avion.") => ".$Shoot."(Shoot_Dca=".$Shoot_Dca." ; VisAvion=".$vis_debug." ; VitAvion/10=-".$VitAvion." ; Météo*2=".$meteo." ; Malus Range=-".$Malus_Range." ; Tactique/10=-".$Tactique." ; Pilotage/10=-".$Pilotage." ; Bonus_2passe=".$Bonus_2passe.")";
											if($debug_msg)
												mail('binote@hotmail.com','Aube des Aigles: Shoot_DCA AUTO Stats',$debug_msg);
										}*/
										$Shoot=$Shoot_Dca+$meteo+$vis_debug-$Malus_Range_dca-($Tactique/10)-($Pilotage/10)-($VitAvion/10)+$Bonus_2passe-$AsZigZag;
										if($Shoot >1 or $Shoot_Dca ==$DCA_EXP or $Chk_Bomb)
										{
											if($alt <501 and $Type_avion ==11)
												$Degats_base=$DCA_dg;
											elseif($Noob)
											{
												if($DCA_dg >500)$DCA_dg=500;
												$Degats_base=mt_rand(1,$DCA_dg);
											}
											else
												$Degats_base=mt_rand(1,$DCA_dg);
											$Degats=round(($Degats_base-pow($Blindage,2))*GetShoot($Shoot,$dca_mult));
											if($Degats <1)$Degats=mt_rand(1,10);
											if($alt <4500)$Degats+=ceil($vis_debug);
											if($Admin)
												$Degats=1;
											elseif($Noob and $Degats >550)
												$Degats=500+mt_rand(-100,50);
											$HP-=$Degats;
											if($OK_DCA)
												AddEvent($Avion_db,77,$avion,$PlayerID,$Unite,$Cible,1,$DCA_ID); //1=DCA Aérodrome PJ
											elseif(!$Flak_IA_Ground)
											{
												AddEventGround(378,$avion_img,$PlayerID,$DCA_Unit,$Cible,$Unite,$Vehicule_ID);
												UpdateData("Regiment","Experience",10,"ID",$DCA_Unit);
												UpdateData("Regiment","Moral",10,"ID",$DCA_Unit);
												UpdateCarac($Officier_DCA,"Avancement",10,"Officier");
												UpdateCarac($Officier_DCA,"Reputation",10,"Officier");
											}
											//HP Avion perso persistant
											if($Avion_db =="Avions_Persos")
											{
												if($HP <1)$HP=0;
												SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
											}
											if($Shoot >100 or $alt <501)
											{
												$CritH=CriticalHit($Avion_db,$avion,$PlayerID,2,$Engine_Nbr); //Todo : Remplacer 2 par type de munition
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
												$intro.='<br>L\'explosion met le feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
												if($Avion_Bombe ==100 and $Bombs ==10)
												{
													$con=dbconnecti();
													$reset=mysqli_query($con,"UPDATE Pilote SET S_Cible_Atk=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$PlayerID'");
													$reset3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=0,Vehicule_Nbr=0,Moral=0 WHERE Officier_ID='$Cible_Atk'");
													mysqli_close($con);
													$intro.="<br>Le bataillon complet de parachutistes a été perdu!";
												}
												$end_mission=true;
												$attaque=false;
												break;
											}
											else
											{
												$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
												if($Equipage and $Equipage_Nbr >1)
												{
													if($Eq_Moral >0 and $Eq_Courage >0 and !$Chk_Bomb)
													{
														if($Simu)UpdateCarac($Equipage,"Mecanique",1,"Equipage");
														if($Meca > $Degats)$Meca=$Degats;
														$intro.='<br>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)';
														$HP+=$Meca;
													}
												}
												$attaque=true;
											}
											SetData("Pilote","S_HP", $HP,"ID",$PlayerID);
										}
										else
										{
											$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
											$attaque=true;
										}
									}
									else
									{
										$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
										$attaque=true;
									}
								}
								else
								{
									$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
									$attaque=true;
								}
							}
						}
					}
					mysqli_free_result($result);
				}
				else
					$OK_DCA=false;
			}		//			
			else
			{
				if($DefenseAA >0 and $alt <10000)
				{
					if($Mission_Type ==5 or $Mission_Type ==15)
						$Detect=mt_rand(0,$DefenseAA*10)+$meteo-($alt/100);
					else
					{
						if($Nuit)
						{
							if($DefenseAA >3)
							{
								$Projo=($DefenseAA/5);
								$Malus_Nuit=50-($Projo*10);
								$intro.="<br>De puissants projecteurs illuminent le ciel!";
							}
							else
								$Malus_Nuit=50;
						}
						else
							$Projo=0;
						//Detection
						$Detect=mt_rand(0,$DefenseAA*10) + $VisAvion + $meteo - ($alt/100) + ($Projo*100);
							/*$headers='MIME-Version: 1.0' . "\r\n";
							$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
							$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo."</p>";
							$msgm.="<br>[Score de Tir : ".$Detect."]
												<br>+DefenseAA ".$DefenseAA."
												<br>+VisAvion ".$VisAvion."
												<br>+Projo :".$Projo." * 100
												<br>-meteo ".$meteo."
												<br>-alt ".$alt." /100</body></html>";
							mail('binote@hotmail.com','Aube des Aigles: DCA Detect Log bomb.php',$msgm,$headers);*/
					}
					if($Detect >0 or $Chk_Bomb)
					{
						$intro.="<br>Les explosions de DCA encadrent votre appareil!";
						$DCA_guns=GetDCA($Pays_eni,$DefenseAA);
						$hgun=$DCA_guns[0];
						$gun=$DCA_guns[1];
						$mg=$DCA_guns[2];
						//Effet arme principale bombardiers
						if(($Type_avion ==2 or $Type_avion ==3 or $Type_avion ==6 or $Type_avion ==9 or $Type_avion ==11) and $ArmeAvion >0 and $ArmeAvion !=5 and $alt <1000)
						{
							$ArmeAvion_cal=GetData("Armes","ID",$ArmeAvion,"Calibre");
							if($ArmeAvion_cal <8)
								$DefenseAA-=1;
							elseif($ArmeAvion_cal <13)
							{
								$mg=5;
								$DefenseAA-=1;
							}
							elseif($ArmeAvion_cal <21)
							{
								$mg=5;
								$DefenseAA-=2;
							}
							else
							{
								$mg=5;
								$gun=5;
								$DefenseAA-=2;
							}
							$intro.="<br>Votre mitrailleur avant arrose copieusement la DCA rapprochée ennemie!";
						}
						switch($alt)
						{
							case ($alt <500):
								$Arme1=$gun;
								$Arme2=$mg;
								$Arme3=$mg;
								$Flak=3;
							break;
							case ($alt <2000):
								//88mm Flak / QF 3.7inch AA
								//37mm Flak / Bofors L60
								//20mm Flak / 20mm
								$Arme1=$gun;
								$Arme2=$gun;
								$Arme3=$mg;
								$Flak=3;
							break;
							case ($alt <7000):
								//88mm Flak / QF 3.7inch AA
								//37mm Flak / Bofors L60
								$Arme1=$hgun;
								$Arme2=$gun;
								$Arme3=5;
								$Flak=14;
							break;
							case ($alt >=7000):
								//88mm Flak / QF 3.7inch AA
								$Arme1=$hgun;
								$Arme2=5;
								$Arme3=5;
								$Flak=15;
							break;
						}
						//DCA Torpillage
						if($Mission_Type ==13 and $alt <500)
						{
							$Arme1=$gun;
							$Arme2=$mg;
							$Arme3=$mg;
							$Flak=3;
						}
						$intro.='<br>Vous vous trouvez à '.$alt.'m d\'altitude. <b>La défense anti-aérienne ouvre le feu sur vous!</b>';
						if($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13 or $Mission_Type ==14 or $Zone ==6)
							$img=Afficher_Image("images/flak_nav.jpg","images/image.png","D.C.A Navale");
						else
						{
							if($Nuit)
								$img="<img src='images/flak_nuit.jpg' style='width:50%;'>";
							else
								$img=Afficher_Image('images/flak'.$Flak.$Pays_eni.'.jpg',"images/image.png","D.C.A");
						}
						$Dca_max=10+($DefenseAA*10);
						//Bonus DCA si seconde passe
						if($Strike or $S_Pass)
							$Bonus_2passe=mt_rand(10,100);
						else
							$Bonus_2passe=0;
						if(!$Blindage)
						{
							$Blindage=$S_Blindage;
							if(!$Blindage)$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
						}
						$dca_site_hit=false;
						for($dca_shoot=1;$dca_shoot<4;$dca_shoot++)
						{
							$Arme_dca="Arme".$dca_shoot;
							if($$Arme_dca !=5)
							{
								$Shoot_Dca=mt_rand(0,$Dca_max);
								if($Shoot_Dca >0)
								{
									//DCA sur Formation
									if($Formation >0)
									{
										$Formation_abattue=0;
										$DCA_dg=GetData("Armes","ID",$Arme_dca,"Degats");
										$dca_mult=GetData("Armes","ID",$Arme_dca,"Multi")*mt_rand(1,$dca_shoot);
										$con=dbconnecti();
										$result=mysqli_query($con,"SELECT Avion2,Avion3 FROM Unit WHERE ID='$Unite'");
										$resultp=mysqli_query($con,"SELECT p.ID,p.Nom,p.Pilotage,p.Tactique,p.Avion,a.Visibilite,a.VitesseB,a.Blindage,a.Robustesse FROM Pilote_IA as p,Avion as a 
										WHERE p.Avion=a.ID AND p.Unit='$Unite' AND p.Cible='$Cible' AND p.Actif=1 ORDER BY RAND() LIMIT '$DefenseAA'");
										//mysqli_close($con);
										if($result)
										{
											while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
											{
												$Avion2_dca=$data['Avion2'];
												$Avion3_dca=$data['Avion3'];
											}
											mysqli_free_result($result);
										}
										if($resultp)
										{
											while($dataa=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
											{
												$Pilote_ia_dca=$dataa['ID'];
												$Nom_pilote_ia=$dataa['Nom'];
												$Avion_dca=$dataa['Avion'];
												$VisAvion_dca=$dataa['Visibilite'];
												$Tactique_dca=$dataa['Tactique']+$ExpTac;
												$Pilotage_dca=$dataa['Pilotage'];
												$VitAvion_dca=$dataa['VitesseB'];
												$Blindage_dca=$dataa['Blindage'];
												$Robustesse_dca=$dataa['Robustesse'];
												$Shoot=$Shoot_Dca + $meteo + $VisAvion_dca - $Malus_Range - ($Tactique_dca/10) - ($Pilotage_dca/10) - ($VitAvion_dca/10) + $Bonus_2passe - $Malus_Nuit;
												if($Shoot >1 or $Chk_Bomb)
												{
													$Degats=round((mt_rand(1,$DCA_dg)-pow($Blindage_dca,2))*GetShoot($Shoot,$dca_mult));
													if($alt <4500)$Degats+=ceil($VisAvion_dca);
													AddEvent("Avion",179,$Avion_dca,$Pilote_ia_dca,$Unite,$Cible,2,$Pays_eni);
													if($Degats >$Robustesse_dca)
													{
														$intro.="<br>L'explosion met le feu à l'avion de ".$Nom_pilote_ia.", ne lui laissant pas d'autre choix que de sauter en parachute!";
														//$con=dbconnecti();
														$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0 WHERE ID='$Pilote_ia_dca'");
														//mysqli_close($con);
														if($Avion_dca ==$Avion3_dca)
															$Avion3_Nbr_dca+=1;
														elseif($Avion_dca ==$Avion2_dca)
															$Avion2_Nbr_dca+=1;
														else
															$Avion1_Nbr_dca+=1;
														$Formation-=1;
														$Formation_abattue+=1;
														if(!$Discipline_fer or mt_rand(0,1) >0)
															WoundPilotIA($Pilote_ia_dca);
													}
													else
														$intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", mais il peut heureusement continuer sa mission!";
												}
											}
											mysqli_free_result($resultp);
										}
										mysqli_close($con);
										if($Formation_abattue >0)
										{
											$con=dbconnecti();
											$reset=mysqli_query($con,"UPDATE Unit SET Avion1_Nbr=Avion1_Nbr-'$Avion1_Nbr_dca',Avion2_Nbr=Avion2_Nbr-'$Avion2_Nbr_dca',Avion3_Nbr=Avion3_Nbr-'$Avion3_Nbr_dca',Reputation=Reputation-'$Formation_abattue' WHERE ID='$Unite'");
											mysqli_close($con);
											SetData("Pilote","S_Formation",$Formation,"ID",$PlayerID);
										}
									}
									$Shoot=$Shoot_Dca + $meteo + $vis_debug - $Malus_Range - ($Tactique/10) - ($Pilotage/10) - ($VitAvion/10) + $Bonus_2passe - $Malus_Nuit;
									/*if($PlayerID ==2)
									{
										$headers='MIME-Version: 1.0' . "\r\n";
										$headers.='Content-type: text/html; charset=utf-8' . "\r\n";
										$msgm="<html><body><p>Joueur : ".$PlayerID." / Avion :".$avion." / Altitude : ".$alt. " / Meteo : ".$meteo."</p>";
										$msgm.="<br>[Score de Tir : ".$Shoot."]
															<br>+Vis ".$vis_debug."
															<br>-Meteo ".$meteo."
															<br>-Malus_Range ".$Malus_Range."
															<br>-Malus_Nuit ".$Malus_Nuit."
															<br>-Speed ".$VitAvion." /10
															<br>-Pilotage ".$Pilotage." /10
															<br>-Tactique ".$Tactique." /10
															<br>+S_Pass ".$Bonus_2passe."
															<br>+Tir_eni :".$Shoot_Dca."</body></html>";
										mail('binote@hotmail.com','Aube des Aigles: DCA Bomb Log bomb.php',$msgm,$headers);
									}
									if($alt <101)
									{
										$debug_msg="Shoot_DCA (alt=".$alt." ; avion=".$avion.") => ".$Shoot." (Shoot_Dca=".$Shoot_Dca." ; VisAvion=".$vis_debug." ; VitAvion/10=-".$VitAvion." ; Météo*2=".$meteo." ; Malus Range=-".$Malus_Range." ; Tactique/10=-".$Tactique." ; Pilotage/10=-".$Pilotage." ; Bonus_2passe=".$Bonus_2passe.")";
										if($debug_msg)mail('binote@hotmail.com','Aube des Aigles: Shoot_DCA AUTO Stats',$debug_msg);
									}*/
								}
								else
									$Shoot=0;
								//$Shoot=$Shoot_Dca+$meteo+($VisAvion/($Malus_Range/10))-$Malus_Range-($Tactique/10)-($Pilotage/10)-($VitAvion/($Malus_Range/5))+$Bonus_2passe;
								if($Shoot >0 or $Shoot_Dca ==$Dca_max or $Chk_Bomb)
								{
									if($dca_shoot ==1 and $Arme1 !=5 and $Shoot >50)
										$Degats=round((mt_rand(1,GetData("Armes","ID",$Arme1,"Degats"))-pow($Blindage,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme1,"Multi")));
									elseif($dca_shoot ==2 and $Arme2 !=5)
										$Degats=round((mt_rand(1,GetData("Armes","ID",$Arme2,"Degats"))-pow($Blindage,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme2,"Multi")));
									elseif($dca_shoot ==3 and $Arme3 !=5)
										$Degats=round((mt_rand(1,GetData("Armes","ID",$Arme3,"Degats"))-pow($Blindage,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme3,"Multi")));
									if($Degats <1)$Degats=mt_rand(1,10);
									if($alt <4500)$Degats+=ceil($vis_debug);
									if($Noob and $Degats >1000)$Degats=1000+mt_rand(-100,0);
									$HP-=$Degats;
									$dca_site_hit=true;
									if($Avion_db =="Avions_Persos")
									{
										if($HP <1)$HP=0;
										SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
									}
									if($Shoot >100)
									{
										$CritH=CriticalHit($Avion_db,$avion,$PlayerID,2,$Engine_Nbr); //Todo : Remplacer 2 par type de munition
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
										$intro.='<br>L\'explosion met le feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
										$end_mission=true;
										break;
									}
									else
									{
										$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
										if($Equipage and $Equipage_Nbr >1)
										{
											if(!$Chk_Bomb and GetData("Equipage","ID",$Equipage,"Moral") >0 and GetData("Equipage","ID",$Equipage,"Courage") >0)
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
									$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
							}
						}
						if($dca_site_hit and !$Sandbox)
						{
							if($Avion_db =="Avions_Persos")
								$avion_event=GetData($Avion_db,"ID",$avion,"ID_ref");
							else
								$avion_event=$avion;
							AddEventFeed(78,$avion_event,$PlayerID,$Unite,$Cible,2,$Pays_eni); //2=DCA rapprochée
						}
						if(!$end_mission)
							$attaque=true;
					}
					else
					{
						$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
						$attaque=true;
					}
				}
				else
					$attaque=true;
			}
			if($attaque)
			{
				$choix_rafale="";
				$rocket="";
				$Conso=$Puissance_ori/500;
				$essence-=(5+$Conso);
				if($Mission_Type ==1 or $Mission_Type ==6 or $Mission_Type ==11 or $Mission_Type ==31)
				{
					if($Mun1 <1 and $Mun2 <1)
					{
						$intro.="<p>Vous n'avez plus de munitions, vous devez annuler votre attaque!</p>";
						$UpdateMoral-=1;
						$UpdateTactique-=2;
						$retour=true;
					}
					else
						$choix_rafale=true;
					if($Avion_Bombe ==80 and $Bombs >0)
					{
						$rocket="	<Input type='Radio' name='Rafalet' value='80' checked>- Tirer une salve de roquettes <br>
									<Input type='Radio' name='Rafalet' value='81' checked>- Tirer toutes les roquettes <br>";
					}
					if($choix_rafale or $rocket)
					{
						$dive_form="attaque";
						$choix_dive="Attaquer";
						$atk_type="Attaque";
						$piste="un avion au sol";
						$choix_rafale="<table class='table'><thead><tr><th>Choix du tir</th></thead></tr><tr><td align='left'>
								<Input type='Radio' name='Rafalet' title='".GetMes('Aide_Courte_Rafale')."' value='2'>- Lâcher une courte rafale <br>
								<Input type='Radio' name='Rafalet' title='".GetMes('Aide_Longue_Rafale')."' value='3' checked>- Lâcher une longue rafale <br>
								".$rocket."</td></tr></table>";
						$dive=true;
					}
				}
				elseif($Mission_Type ==2 or $Mission_Type ==8 or $Mission_Type ==12 or $Mission_Type ==16 or $Mission_Type ==101)
				{
					if($Bombs <1 or $Avion_Bombe ==800 or $Avion_Bombe ==300 or $Avion_Bombe <50)
					{
						$intro.="<p>Vous n'avez pas de bombe, vous devez annuler votre attaque!</p>";
						$UpdateMoral-=1;
						$UpdateTactique-=5;
						$retour=true;
					}
					else
					{
						$dive_form="bombardement";
						$choix_dive="Bombarder";
						$atk_type="Bombardement";
						$piste="la piste";
						$dive=true;
					}
				}
				elseif($Mission_Type ==5 or $Mission_Type ==15)
				{
					if(($Arme2Avion ==25 or $Arme2Avion ==26 or $Arme2Avion ==27) and $Mun2 <1)
						$intro.="<p>Vous n'avez plus de péllicule!</p>";
					$dive_form="photo";
					$choix_dive="Prendre des photos de";
					$atk_type="Reconnaissance photo";
					$dive=true;
				}
				elseif($Mission_Type ==21)
				{
					if($Bombs >0 and $Avion_Bombe ==30)
					{
						$dive_form="bombardement";
						$choix_dive="Marquer";
						$atk_type="Marquage de cible";
						$dive=true;
					}
					else
					{
						$intro.="<p>Vous n'avez pas de fusées éclairantes!</p>";
						$UpdateMoral-=1;
						$UpdateTactique-=5;
						$retour=true;
					}
				}
				elseif($Mission_Type ==24 or $Mission_Type ==25)
				{
					if($Bombs >0 and $Avion_Bombe ==100 and $Cible_Atk >0)
					{
						$dive_form="bombardement";
						$choix_dive="Larguer les parachutistes sur";
						$atk_type="Largage de parachutistes";
						$dive=true;
					}
					else
					{
						$intro.="<p>Vous n'avez pas de parachutistes à bord!</p>";
						$UpdateMoral-=1;
						$UpdateTactique-=5;
						$retour=true;
					}
				}
				elseif($Mission_Type ==27)
				{
					if($Cible_Atk >0)
					{
						$dive_form="bombardement";
						$choix_dive="Larguer le commando sur";
						$atk_type="Largage de commando";
						$dive=true;
					}
					else
					{
						$intro.="<p>Vous n'avez pas de commando à bord!</p>";
						$UpdateMoral-=1;
						$UpdateTactique-=5;
						$retour=true;
					}
				}
				elseif($Mission_Type ==13)
				{
					if($Bombs <1 or $Avion_Bombe !=800)
					{
						$intro.="<p>Vous n'avez pas de torpille, vous devez annuler votre attaque!</p>";
						$UpdateMoral-=1;
						$UpdateTactique-=5;
						$retour=true;
					}
					else
					{
						$dive_form="bombardement";
						$choix_dive="Torpiller";
						$atk_type="Torpillage";
						$dive=true;
					}
				}
				elseif($Mission_Type ==29)
				{
					if($Bombs <1 or $Avion_Bombe !=300)
					{
						$intro.="<p>Vous n'avez pas de charge de profondeur, vous devez annuler votre attaque!</p>";
						$UpdateMoral-=1;
						$UpdateTactique-=5;
						$retour=true;
					}
					else
					{
						$dive_form="bombardement";
						$choix_dive="Bombarder";
						$atk_type="ASM";
						$dive=true;
					}
				}
				elseif($Mission_Type ==14)
				{
					if($Bombs >0 and $Avion_Bombe ==400)
					{
						$dive_form="bombardement";
						$choix_dive="Mouiller";
						$atk_type="Mouillage de mines";
						$dive=true;
					}
					else
					{
						$intro.="<p>Vous n'avez pas de mines à bord!</p>";
						$UpdateMoral-=1;
						$UpdateTactique-=5;
						$retour=true;
					}
				}
				/*elseif($Mission_Type ==14)
				{
					if($Action >13)
						SetData("Pilote","S_Cible_Atk",$Action,"ID",$PlayerID);
					if($Bombs <1)
					{
						if($Mun1 <1 and $Mun2 <1)
						{
							$intro.="<p>Vous n'avez plus de munitions, vous devez annuler votre attaque!</p>";
							$UpdateMoral-=1;
							$UpdateTactique-=2;
							$retour=true;
						}
						else
						{
							if($Avion_Bombe ==80 and $Bombs >0)
								$rocket="	<Input type='Radio' name='Rafalet' value='80' checked>- Tirer une salve de roquettes <br><Input type='Radio' name='Rafalet' value='81' checked>- Tirer toutes les roquettes <br>";
							$dive_form="attaque";
							$choix_dive="Attaquer";
							$atk_type="Attaque de navire";
							$piste="un navire";
							$choix_rafale="<table class='table'><thead><tr><th>Choix du tir</th></thead></tr><tr><td align='left'>
									<Input type='Radio' name='Rafalet' title='".GetMes('Aide_Courte_Rafale')."' value='2'>- Lâcher une courte rafale <br>
									<Input type='Radio' name='Rafalet' title='".GetMes('Aide_Longue_Rafale')."' value='3' checked>- Lâcher une longue rafale <br>
									".$rocket."
							</td></tr></table>";
							$dive=true;
						}
					}
					else
					{
						$dive_form="bombardement";
						$choix_dive="Bombarder";
						$atk_type="Bombardement";
						$piste="le navire";
						$dive=true;
					}
				}*/
			}
			//***WRITE TO DB***
			if(!$Chk_Bomb)
			{
				if($UpdateMoral !=0)
					UpdateCarac($PlayerID,"Moral",$UpdateMoral);
				if($UpdateCourage !=0)
					UpdateCarac($PlayerID,"Courage",$UpdateCourage);
				/*if($UpdateTactique !=0)
					UpdateCarac($PlayerID,"Tactique",$UpdateTactique);*/
			}
			$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
			if($dive)
			{
				$choix1="";
				$choix2="";
				$choix3="";
				$choix4="";
				$choix5="";
				$choix6="";
				$choix7="";
				if($Mission_Type <5)
				{
					$con=dbconnecti();
					//$result=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Camouflage,c.Taille,c.Nom FROM Regiment as r,Cible as c WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Visible=1 AND r.Vehicule_ID <4999 AND r.Bomb_PJ=0");
					$result2=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Camouflage,c.Taille,c.Nom FROM Regiment_IA as r,Cible as c WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Visible=1 AND r.Vehicule_ID <4999");
					mysqli_close($con);
					/*if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Cam=$data['Taille']/$data['Camouflage'];
							if($BonusDetect+mt_rand(0,$Vue)+$Cam >$Malus_Reperer+($alt/100))
								$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."000_'>- ".$choix_dive." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Nom']."'> ".$data['Vehicule_Nbr']."<br>";
						}
						mysqli_free_result($result);
						unset($data);
					}*/
					if($result2)
					{
						while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							$Cam=$data['Taille']/$data['Camouflage'];
							if($BonusDetect+mt_rand(0,$Vue)+$Cam >$Malus_Reperer+($alt/100))
								$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."000ia'>- ".$choix_dive." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Nom']."'> ".$data['Vehicule_Nbr']."<br>";
						}
						mysqli_free_result($result2);
						unset($data);
					}
				}
				elseif($Mission_Type ==5 or $Mission_Type ==14 or $Mission_Type ==15 or $Mission_Type ==21 or $Mission_Type ==24 or $Mission_Type ==25 or $Mission_Type ==27)
					$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." l'objectif<br>";
				elseif($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13)
				{			
					if($Action ==7)
					{
						$choix_dive="Piquer sur";
						$value="66";
						//$choix1="<Input type='Radio' name='Action' value='66'>- Piquer sur un".$cible_txt."<br>";
					}
					else
						$value="";
					$con=dbconnecti();
					//$result=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,c.Nom FROM Regiment as r,Cible as c WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Visible=1 AND r.Position<>25 AND r.Vehicule_ID >4999");
					$result2=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,c.Nom FROM Regiment_IA as r,Cible as c WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Visible=1 AND r.Position<>25 AND r.Vehicule_ID >4999");
					mysqli_close($con);
					/*if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							if($BonusDetect+mt_rand(0,$Vue) >$Malus_Reperer+($alt/100))
								$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."000_'>- ".$choix_dive." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Nom']."'> ".$data['Vehicule_Nbr']." <img src='images/navy".$data['Pays'].".png'><br>";
						}
						mysqli_free_result($result);
						unset($data);
					}*/
					if($result2)
					{
						while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							if($BonusDetect+mt_rand(0,$Vue) >$Malus_Reperer+($alt/100))
								$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."000ia'>- ".$choix_dive." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Nom']."'> ".$data['Vehicule_Nbr']." <img src='images/navy".$data['Pays'].".png'><br>";
						}
						mysqli_free_result($result2);
						unset($data);
					}
				}
				/*elseif($Mission_Type ==14)
				{
					if($Action ==7)
					{
						$choix_dive="Piquer sur";
						$value="66";
					}
					else
						$value="";
					$cible_txt=GetNavire($Cible_Atk);
					//Si Convoi, chance de couler des navires du convoi
					$Escorteb_nbr=GetData("Pilote","ID",$PlayerID,"S_Escorteb_nbr");
					if($Escorteb_nbr >1)
					{
						$couler=mt_rand(0,25);
						if($couler < $Cible_Atk or $Chk_Bomb)
						{
							$Max_coules=$Cible_Atk;
							if($Cible_Atk >21) //sous-marins
								$Max_coules=10;
							$coules=mt_rand(1,$Max_coules);
							if($coules >$Escorteb_nbr)$coules=$Escorteb_nbr;
							if($coules)
								$intro.='<p>'.$coules.' cargos du convoi sont coulés par un '.$cible_txt.' ennemi!';
							$Escorteb_nbr-=$coules;
							SetData("Pilote","S_Escorteb_nbr",$Escorteb_nbr,"ID",$PlayerID);
						}
					}
					$choix1="<Input type='Radio' name='Action' value='".$value.$Cible_Atk."'>- ".$choix_dive." un ".$cible_txt."<br>";
				}*/
				elseif($Mission_Type ==29)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT ID,Vehicule_ID,Vehicule_Nbr,Pays FROM Regiment WHERE Lieu_ID='$Cible' AND Vehicule_Nbr >0 AND Position=25");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Pays_eni=$data['Pays'];
							if($BonusDetect + mt_rand(0,$Vue) > $Malus_Reperer + ($alt/100))
								$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."000_'>- ".$choix_dive." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".GetData("Cible","ID",$data['Vehicule_ID'],"Nom")."'> ".$data['Vehicule_Nbr']." <img src='images/navy".$Pays_eni.".png'><br>";
						}
						mysqli_free_result($result);
						unset($data);
					}
				}
				elseif($Mission_Type ==101)
					$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/map/training_target.png' title='Cible entrainement'><br>";
				else
				{
					$Recce=GetData("Lieu","ID",$Cible,"Recce");
					if($data['Recce'] ==2)
					{
						if($Nuit)
							$Recce=100;
						else
							$Recce=50;
					}
					switch($Cible_Atk)
					{
						case 1:
							$DCA_ID=false;
							$choix2="";
							$con=dbconnecti();
							$Unit_eni=mysqli_result(mysqli_query($con,"SELECT DISTINCT ID FROM Unit WHERE Base='$Cible' AND Etat=1 ORDER BY RAND() LIMIT 1"),0);
							$result2=mysqli_query($con,"SELECT BaseAerienne,QualitePiste,Tour,Camouflage FROM Lieu WHERE ID='$Cible'");
							$result=mysqli_query($con,"SELECT DCA_ID,DCA_Nbr FROM Flak WHERE Lieu='$Cible' AND DCA_Nbr >0");
							mysqli_close($con);
							if($result2)
							{
								while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
								{
									$Camouflage=$data['Camouflage'];
									if(!$Camouflage)$Camouflage=10;
									if($Nuit)$Camouflage*=2;
									if($data['BaseAerienne'] ==3)
										$Cam_piste=$Camouflage;
									elseif($data['BaseAerienne'] ==1)
										$Cam_piste=$Camouflage/2;
									$Ae=$data['QualitePiste'];
									$Tour=$data['Tour'];
								}
								mysqli_free_result($result2);
								unset($data);
							}
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
									$DCA_ID=$data['DCA_ID'];
									if($DCA_ID and $BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + $Camouflage + ($alt/100))
										$choix2.="<Input type='Radio' name='Action' value='99_".$DCA_ID."'>- ".$choix_dive." <img src='images/aa".$DCA_ID.".png' title='une batterie de DCA'> DCA<br>";
									$DCA_ID=false;
								}
								mysqli_free_result($result);
								unset($data);
							}
							if($Ae and $piste =="la piste")
							{
								if($BonusDetect + mt_rand(0,$Vue)+$Recce+20 >$Malus_Reperer+$Cam_piste+($alt/100))
									$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." ".$piste."<br>";
								else
									$choix1="Vous ne parvenez pas à repérer la piste!<br>";
							}
							elseif($Unit_eni and $piste =="un avion au sol" and $BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + ($Camouflage*2) + ($alt/100))
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." ".$piste."<br>";
							/*if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0)
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." un emplacement de D.C.A<br>";*/
							if($Tour and $BonusDetect + mt_rand(0,$Vue)+$Recce+10 > $Malus_Reperer + $Camouflage + ($alt/100))
								$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule2.gif' title='la tour de contrôle'><br>";
							if($BonusDetect + mt_rand(0,$Vue)+$Recce+10 > $Malus_Reperer + $Camouflage + ($alt/100))
								$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un hangar'><br>";
						break;
						case 2:
							$Usine_hp=GetData("Lieu","ID",$Cible,"Industrie");
							if($Usine_hp >0)
							{
								if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) + ($alt/100))
								{
									if($Usine_hp >50)
									{
										$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
										$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule3.gif' title='un bâtiment secondaire'><br>";
									}
									$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule4.gif' title='un bâtiment principal'><br>";
								}
								else
									$choix4="<Input type='Radio' name='Action' value='6'>- ".$choix_dive." au hasard.<br>";
							}
							else
								$choix1="L'usine n'est plus qu'un amoncellement de ruines fumantes!<br>";
							
							if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
						break;
						case 3:
							$Garnison=GetData("Lieu","ID",$Cible,"Garnison");
							if($Garnison >0 and ($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,100) + ($alt/100)))
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." un groupe de soldats de la garnison<br>";
							/*if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." un canon<br>";*/
							if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
								$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." un bâtiment secondaire<br>";
							if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
								$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." le bâtiment principal<br>";
						break;
						case 4:
							$hp_gare=GetData("Lieu","ID",$Cible,"NoeudF");
							if($hp_gare >0)
							{
								if($hp_gare >50)
								{
									if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
										$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule8.gif' title='les voies'><br>";
									if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
										$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
								}
								if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
									$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule9.gif' title='le bâtiment principal'><br>";
								else
									$choix4="<Input type='Radio' name='Action' value='6'>- ".$choix_dive." au hasard.<br>";
							}
							else
								$choix1="La gare n'est plus qu'un amoncellement de ruines fumantes!<br>";
							if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
						break;
						case 5:
							if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." un véhicule<br>";
							if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
							if(GetData("Lieu","ID",$Cible,"Pont") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10))
							{
								$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." le pont, en enfilade<br>";
								$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." le pont, perpendiculairement<br>";
							}
						break;
						case 6:
							$hp_Port=GetData("Lieu","ID",$Cible,"Port");
							if($hp_Port >0)
							{
								if($hp_Port >50)
								{
									if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
										$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
									if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
										$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule11.gif' title='les réserves de carburant'><br>";
								}
								if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
									$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule12.gif' title='les docks'><br>";
								else
									$choix4="<Input type='Radio' name='Action' value='6'>- ".$choix_dive." au hasard.<br>";
							}
							else
								$choix1="Le port n'est plus qu'un amoncellement de ruines fumantes!<br>";
							if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
						break;
						case 7:
							$hp_Radar=GetData("Lieu","ID",$Cible,"Radar");
							if($hp_Radar >0)
							{
								if($hp_Radar >50)
								{
									if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,25) + ($alt/100))
										$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule13.gif' title='un bâtiment secondaire'><br>";
								}
								elseif($hp_Radar >25)
								{
									if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(0,10) + ($alt/100))
										$choix3="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule15.gif' title='une antenne'><br>";
								}
								if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,20) + ($alt/100))
									$choix4="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." le bâtiment principal<br>";
								else
									$choix4="<Input type='Radio' name='Action' value='6'>- ".$choix_dive." au hasard.<br>";
							}
							else
								$choix1="La station radar n'est plus qu'un amoncellement de ruines fumantes!<br>";
							if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
						break;
					}
				}
				if($Mission_Type ==8 or $Mission_Type ==16 or $Mission_Type ==101)
				{
					$intro.="<br>Vous vous alignez sur votre objectif !";
					$approche="<Input type='Radio' name='Action' value='20'>- Recommencer l'approche.<br>";
				}
				else
					$intro.="<br>Vous piquez vers l'objectif !";
				if($Strike)
					$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,8);
				else
					$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,1);
				if(!$img)$img=Afficher_Image('images/avions/pique'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
				if(!$choix1 and !$choix2 and !$choix3 and !$choix4 and !$choix5 and !$choix6 and !$choix7)
				{
					$approche="Aucune cible détectée.<br>";
					$choix_rafale='';
				}
				//Deleguer équipage
				if($Deleguer or $Action ==4 or $Action ==5 or $Action ==6 or $Action ==9)$Deleguer=1;
				$_SESSION['cibler']=true;
				SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
				$mes.="<h2>".$atk_type."</h2><form action='".$dive_form.".php' method='post'>
				<input type='hidden' name='Avion' value=".$avion.">
				<input type='hidden' name='Meteo' value=".$meteo.">
				<input type='hidden' name='Mun1' value=".$Mun1.">
				<input type='hidden' name='Mun2' value=".$Mun2.">
				<input type='hidden' name='HP_eni' value=".$HP_eni.">
				<input type='hidden' name='Puissance' value=".$Puissance.">
				<input type='hidden' name='ArmeAvion' value=".$ArmeAvion.">
				<input type='hidden' name='ArmeAvion_nbr' value=".$ArmeAvion_nbr.">
				<input type='hidden' name='Mun' value=".$Mun.">
				<input type='hidden' name='Cible_lock' value=".$Lock.">
				<input type='hidden' name='Pays_eni' value=".$Pays_eni.">
				<input type='hidden' name='Deleguer' value=".$Deleguer.">
				<table class='table'>
					<tr>".$gaz_txt."
						<td align='left'>".$choix1.$choix2.$choix3.$choix4.$choix5.$choix6.$choix7.$approche."
							<Input type='Radio' name='Action' value='5' checked>- Annuler l'attaque.<br>
						</td>
					</tr></table>
				".$choix_rafale."
				<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}
		}		
		if(!$toolbar)$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
		if($Action ==90)
		{
			$chemin=0;
			$retour=false;
			//PvP
			$choix96="";
			$choix97="";
			$choix98="";
			$choix99="";
				//AddCandidat($Avion_db,$PlayerID,$avion,$HP,$Puissance,$essence,$chemin,$Distance,$Mun1,$Mun2,$alt,$Cible,$Nuit);
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT PlayerID,Avion,Altitude,Target FROM Duels_Candidats WHERE Lieu='$Cible' AND PlayerID<>'$PlayerID' AND Country<>'$country'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$PvP_ID=$data['PlayerID'];
						//Detection
						$Tactique_PvP=GetData("Pilote","ID",$PvP_ID,"Tactique");
						$Vis_eni=GetVis("Avion",$data['Avion'],$Cible,$meteo,$data['Altitude'],$alt);
						$Malus_alt=abs(($alt-$data['Altitude'])/100);
						$Detect=mt_rand(10,$Vue) + $BonusDetect + ($meteo*2) + ($Vis_eni/2) - $Malus_alt + $Radar - ($Tactique_PvP/10);
						if($Detect >0 and !$Chk_Bomb)
						{
							$Target=GetData("Duels_Candidats","PlayerID",$data['Target'],"Avion");
							$Avion_img="images/avions/avion".$data['Avion'].".gif";
							$Target_img="images/avions/avion".$Target.".gif";
							if($Target)
								$choix99.="<Input type='Radio' name='Action' value='99".$PvP_ID."'>- Se rapprocher du <img src='".$Avion_img."' title='Avion'> combattant un <img src='".$Target_img."' title='Avion'> volant à ".$data['Altitude']."m<br>";
							else
								$choix99.="<Input type='Radio' name='Action' value='99".$PvP_ID."'>- Se rapprocher du <img src='".$Avion_img."' title='Avion'> volant à ".$data['Altitude']."m<br>";
						}
						if($PlayerID ==1)$skills.='<br> Détection : '.$Detect;
					}
					mysqli_free_result($result);				
				}
				else
				{
					$intro.="<p>Aucun avion ennemi ne semble être dans les environs</p>";
					mail('binote@hotmail.com','Aube des Aigles: GetCandidat',"Joueur : ".$PlayerID." / Cible : ".$Cible);
					$choix99="";
				}
				/*$choix96="<Input type='Radio' name='Action' value='96'>- Survoler la zone en scrutant le ciel, à haute altitude (PvP)<br>";
				$choix97="<Input type='Radio' name='Action' value='97'>- Survoler la zone en scrutant le ciel, à basse altitude (PvP)<br>";
				$choix98="<Input type='Radio' name='Action' value='98'>- Survoler la zone en scrutant le ciel (PvP)<br>";*/
				$PVP_id_engaged=GetData("Duels_Candidats","Target",$PlayerID,"PlayerID");
				if($PVP_id_engaged)
					$choix1="<Input type='Radio' name='Action' value='99".$PVP_id_engaged."' checked>- Attaquer votre adversaire (PvP)<br>";				
			$_SESSION['cibler'] =true;
			SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
			$mes.="<form action='objectif.php' method='post'>
			<input type='hidden' name='Avion' value=".$avion.">
			<input type='hidden' name='Meteo' value=".$meteo.">	
			<input type='hidden' name='Chemin' value=".$chemin.">	
			<input type='hidden' name='Distance' value=".$Distance.">
			<input type='hidden' name='Mun1' value=".$Mun1.">
			<input type='hidden' name='Mun2' value=".$Mun2.">
			<input type='hidden' name='Puissance' value=".$Puissance.">
			<table class='table'>
				<tr><td colspan='8'>Arrivée sur l'objectif</td></tr>
				<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,1)."
					<td align='left'>
						".$choix1.$choix96.$choix97.$choix98.$choix99."
					</td></tr></table>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		if($retour and !$end_mission)
		{
			SetData("Pilote","S_Essence",$essence,"ID",$PlayerID);
			Chemin_Retour();
			$chemin=$Distance;
			$_SESSION['cibler']=true;
			$intro.='<br>Vous prenez le chemin du retour en direction de votre base, située à '.$Distance.'km';
			$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
			$mes.="<form action='nav.php' method='post'>
			<input type='hidden' name='Chemin' value=".$chemin.">
			<input type='hidden' name='Distance' value=".$Distance.">
			<input type='hidden' name='Meteo' value=".$meteo.">
			<input type='hidden' name='Avion' value=".$avion.">
			<input type='hidden' name='Mun1' value=".$Mun1.">
			<input type='hidden' name='Mun2' value=".$Mun2.">
			<input type='hidden' name='Puissance' value=".$Puissance.">
			<input type='hidden' name='Enis' value=".$Enis.">
			<table class='table'>
				<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt)."</tr>
			</table>
			<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}	
	if($end_mission)
	{
		RetireCandidat($PlayerID,"end_mission");
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		UpdateCarac($PlayerID,"Abattu",1);
		if($HP <1)
		{
			//Tableau de chasse
			if($Arme1 ==5)
			{
				if($Arme2 ==5)
					$Arme1=$Arme3;
				else
					$Arme1=$Arme2;
			}
			if(!$Vehicule_ID)$Vehicule_ID=16;
			AddVictoire_atk($Avion_db,$DCA_Unit,$Vehicule_ID,$avion,$PlayerID,$Unite,$Cible,$Arme1,$country,1,$alt,$Nuit,$Degats);
			AddEvent($Avion_db,3,$avion,$PlayerID,$Unite,$Cible);
			UpdateCarac($PlayerID,"Crashs_Jour",1);
		}
		else
			AddEvent($Avion_db,34,$avion,$PlayerID,$Unite,$Cible);
		if($Zone ==6)
		{
			if($Slot5 ==17 or $Slot5 ==35)
				$intro.="<br>Votre gilet de sauvetage vous sauve de la noyade!";
			else
			{
				$intro.="<br>Sans gilet de sauvetage, vous êtes emporté par la mer jusqu'au rivage!";
				$CritH=true;
			}
		}
		//Blessure
		$blesse =0;
		if(!$CritH)
			$Blessure=GetBlessure($PlayerID,$Avion_db,$avion);
		else
			$Blessure=2;
		switch($Blessure)
		{
			case 0:
				$Blessure_txt ="<br><br>Vous vous en sortez indemne!";
				$Hard=1;
				$Malus_Moral =-25;
			break;
			case 1:
				$Blessure_txt ="<br><br>Vous êtes blessé, mais néanmoins en vie!";
				$Hard=1;
				$Malus_Moral=-50;
				AddEvent($Avion_db,9,$avion,$PlayerID,$Unite_loss,$Cible);
				$blesse=1;
				DoBlessure($PlayerID,1);
			break;
			case 2:
				$Blessure_txt ="<p>Vous gisez étendu sur le sol, mortellement blessé.</p>";
				$Hard=0;
				$Malus_Moral=-100;
				AddEvent($Avion_db,9,$avion,$PlayerID,$Unite_loss,$Cible);
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Escorte=0,Couverture=0,MIA='$Cible',Commando=0 WHERE ID='$PlayerID'");
				mysqli_close($con);
				AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
				$blesse=2;
				DoBlessure($PlayerID,10);
			break;
		}
		$intro.=$Blessure_txt;
		UpdateCarac($PlayerID,"Moral",$Malus_Moral);
		if($Equipage and $Endu_Eq >0 and $Equipage_Nbr >1)
		{
			$Blessure_max=10;
			$Trait_e=GetData("Equipage","ID",$Equipage,"Trait");
			if($Trait_e ==3)
				$Blessure_max=20;
			$Blessure=mt_rand(0,$Blessure_max);
			if($Blessure <1)
				UpdateCarac($Equipage,"Endurance",-1,"Equipage");
			UpdateCarac($Equipage,"Moral",-25,"Equipage");
		}
		UpdateCarac($PlayerID,"Reputation",-10);
		UpdateData("Unit","Reputation",-10,"ID",$Unite,0,4);
		//Prisonnier
		$Base=GetData("Unit","ID",$Unite,"Base");
		$Dist=GetDistance($Base,$Cible);
		if($BonneEtoile)
			$luck_p=mt_rand(0,25);
		elseif($Slot10 ==71)
			$luck_p=mt_rand(0,20);
		elseif($Slot10 ==72 or $Slot10 ==77)
			$luck_p=mt_rand(0,10);
		elseif($Slot10 ==34)
			$luck_p=mt_rand(0,10);
		else
			$luck_p=0;
		if($Mission_Type !=7 and $Mission_Type !=9 and $Dist[0] >30 and $luck_p <5 and $Simu)
		{
			$intro.="<p>Vous vous retrouvez au beau milieu d'une zone contrôlée par l'ennemi.
			<br>Le temps de regagner vos lignes vous rend indisponible jusqu'à votre retour.</p>";
			$mes ="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,MIA='$Cible',Commando=0 WHERE ID='$PlayerID'");
			mysqli_close($con);
			AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
			$_SESSION['Distance']=0;
			if($_SESSION['PVP'])
				RetireCandidat($PlayerID,"end_mission");
		}
		else
		{		
			if($blesse <2)
				$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.='<form action=\'promotion.php\' method=\'post\'>
				<input type=\'hidden\'  name=\'Blesse\'  value='.$blesse.'>
				<input type=\'Submit\' value=\'TERMINER LA MISSION\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
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
unset($dive_form);
unset($atk_type);
usleep(1);
include_once('./index.php');
?>