<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$Action=Insec($_POST['Action']);
$meteo=Insec($_POST['Meteo']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$HP_eni=Insec($_POST['HP_eni']);
$Deleguer=Insec($_POST['Deleguer']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$Lock=Insec($_POST['Cible_lock']);
$Battle=Insec($_POST['Battle']);
$Faction=Insec($_POST['Camp']);
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_pvp.php');
	$_SESSION['bombarder']=false;
	$_SESSION['attaquer']=false;
	$_SESSION['photographier']=false;
	$_SESSION['objectif']=false;
	$_SESSION['naviguer']=false;
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
	if($Chk_Bomb)
	{
		$intro="<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>";
		mail("binote@hotmail.com","Aube des Aigles: Init Mission F5 (bomb) : ".$Pilote_pvp ,"Joueur ".$Pilote_pvp." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Avancement,S_HP,Equipage,Pilotage,Tactique,Vue,Courage,Moral,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,S_Blindage,
	S_Avion_db,S_Nuit,S_Cible,S_Mission,S_Cible_Atk,S_Longitude,S_Latitude,S_Equipage_Nbr,S_Engine_Nbr,S_Strike,S_Pass,S_Formation,Simu,
	Slot5,Slot10,Slot11,Admin FROM Pilote_PVP WHERE ID='$Pilote_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb_pvp-player');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Avancement=$data['Avancement'];
			$HP=$data['S_HP'];
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
	if($HP <1)
		$end_mission=true;
	else
	{
		$Pays_eni=GetFlagPVP($Battle,$Faction);
		if($Equipage)
			$Endu_Eq=GetData("Equipage_PVP","ID",$Equipage,"Endurance");
		if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
			$Vue_Equipage=GetData("Equipage_PVP","ID",$Equipage,"Vue");			
		if($Slot11 ==69)
		{
			$Moral+=50;
			$Courage+=50;
		}		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Type,Puissance,Robustesse,Masse,Plafond,ArmePrincipale,Blindage,Detection,Radar FROM $Avion_db WHERE ID='$avion'")
		 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb_pvp-avion');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Type_avion=$data['Type'];
				$Puissance_ori=$data['Puissance'];
				$HPmax=$data['Robustesse'];
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
		$avion_img=GetAvionImg($Avion_db,$avion);		
		$Flak_PJ_Ground=false;
		$Zone=GetData("Lieu","ID",$Cible,"Zone");
		//DCA
		if($Battle ==1)
			$DefenseAA=6;
		//Boost
		if($c_gaz ==130)
			UpdateData("Pilote_PVP","Stress_Moteur",10,"ID",$Pilote_pvp);		
		if($HP)
		{
			$moda=$HPmax/$HP;
			if(!$moda)$moda=$HPmax;
			if($Bombs >0 and $Avion_Bombe)
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
			$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt);
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
			break;
			case 7:
				if($alt >1000)$alt=1000;
				$ArmeAvion_nbr=GetData($Avion_db,"ID",$avion,"Arme1_Nbr");
				$Mun=1;
			break;
			case 8: case 9:
				//low
				if($alt >100)$alt=100;
				$ArmeAvion_nbr=GetData($Avion_db,"ID",$avion,"Arme1_Nbr");
				$Mun=1;
			break;
			case 13:
				//low
				if($alt >500)$alt=500;
				$Arme2Avion=GetData($Avion_db,"ID",$avion,"ArmeSecondaire");
				$ArmeAvion_nbr=GetData($Avion_db,"ID",$avion,"Arme2_Nbr");
				$Mun=2;
			break;
			case 80:
				//low
				if($alt >100)$alt=100;
				$ArmeAvion=177;
				$ArmeAvion_nbr=$Bombs;
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
			if(mt_rand(10,$Pilotage) <$Malus_Reperer+($VitAvion/10))
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
			/*if($DefenseAA >0 and $alt <10000)
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
					$Detect=mt_rand(0,$DefenseAA*10) + $VisAvion + $meteo - ($alt/100) + ($Projo*100);
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
					$dca_site_hit=false;
					for($dca_shoot=1;$dca_shoot<4;$dca_shoot++)
					{
						$Arme_dca="Arme".$dca_shoot;
						if($$Arme_dca !=5)
						{
							$Shoot_Dca=mt_rand(0,$Dca_max);
							if($Shoot_Dca >0)
								$Shoot=$Shoot_Dca + $meteo + $vis_debug - $Malus_Range - ($Tactique/10) - ($Pilotage/10) - ($VitAvion/10) + $Bonus_2passe - $Malus_Nuit;
							else
								$Shoot=0;
							//$Shoot=$Shoot_Dca + $meteo + ($VisAvion/($Malus_Range/10)) - $Malus_Range - ($Tactique/10) - ($Pilotage/10) - ($VitAvion/($Malus_Range/5)) + $Bonus_2passe;
							if($Shoot >0 or $Shoot_Dca ==$Dca_max or $Chk_Bomb)
							{
								if($dca_shoot ==1 and $Arme1 !=5 and $Shoot >50)
									$Degats=round((mt_rand(1,GetData("Armes","ID",$Arme1,"Degats")) - pow($Blindage,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme1,"Multi")));
								elseif($dca_shoot ==2 and $Arme2 !=5)
									$Degats=round((mt_rand(1,GetData("Armes","ID",$Arme2,"Degats")) - pow($Blindage,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme2,"Multi")));
								elseif($dca_shoot ==3 and $Arme3 !=5)
									$Degats=round((mt_rand(1,GetData("Armes","ID",$Arme3,"Degats")) - pow($Blindage,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme3,"Multi")));
								if($Degats <1)$Degats=mt_rand(1,10);
								if($alt <4500)$Degats+=ceil($vis_debug);
								if($Noob and $Degats >1000)$Degats=1000+mt_rand(-100,0);
								$HP-=$Degats;
								$dca_site_hit=true;
								if($Shoot >100)
								{
									$CritH=CriticalHit($Avion_db,$avion,$Pilote_pvp,2,$Engine_Nbr,true);
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
										if(!$Chk_Bomb and GetData("Equipage_PVP","ID",$Equipage,"Moral") >0 and GetData("Equipage_PVP","ID",$Equipage,"Courage") >0)
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
								$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
						}
					}
					if(!$end_mission)$attaque=true;
				}
				else
				{
					$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
					$attaque=true;
				}
			}
			else*/
				$attaque=true;
			if($attaque)
			{
				$choix_rafale='';
				$rocket='';
				$Conso=$Puissance_ori/500;
				$essence-=(5+$Conso);
				if($Mission_Type ==1 or $Mission_Type ==6 or $Mission_Type ==11 or $Mission_Type ==31)
				{
					if($Mun1 <1 and $Mun2 <1){
						$intro.="<p>Vous n'avez plus de munitions, vous devez annuler votre attaque!</p>";
						$retour=true;
					}
					else
						$choix_rafale=true;
					if($Avion_Bombe ==80 and $Bombs >0){
						$rocket="	<Input type='Radio' name='Rafalet' value='80' checked>- Tirer une salve de roquettes <br>
									<Input type='Radio' name='Rafalet' value='81' checked>- Tirer toutes les roquettes <br>";
					}
					if($choix_rafale or $rocket)
					{
						$dive_form='attaque';
						$choix_dive='Attaquer';
						$atk_type='Attaque';
						$piste='un avion au sol';
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
						$retour=true;
					}
					else
					{
						$dive_form='bombardement';
						$choix_dive='Bombarder';
						$atk_type='Bombardement';
						$piste='la piste';
						$dive=true;
					}
				}
				elseif($Mission_Type ==5 or $Mission_Type ==15)
				{
					if(($Arme2Avion ==25 or $Arme2Avion ==26 or $Arme2Avion ==27) and $Mun2 <1)
						$intro.="<p>Vous n'avez plus de péllicule!</p>";
					$dive_form='photo';
					$choix_dive='Prendre des photos de';
					$atk_type='Reconnaissance photo';
					$dive=true;
				}
				elseif($Mission_Type ==21)
				{
					if($Bombs >0 and $Avion_Bombe ==30)
					{
						$dive_form='bombardement';
						$choix_dive='Marquer';
						$atk_type='Marquage de cible';
						$dive=true;
					}
					else
					{
						$intro.="<p>Vous n'avez pas de fusées éclairantes!</p>";
						$retour=true;
					}
				}
				elseif($Mission_Type ==24 or $Mission_Type ==25)
				{
					if($Bombs >0 and $Avion_Bombe ==100 and $Cible_Atk >0)
					{
						$dive_form='bombardement';
						$choix_dive='Larguer les parachutistes sur';
						$atk_type='Largage de parachutistes';
						$dive=true;
					}
					else
					{
						$intro.="<p>Vous n'avez pas de parachutistes à bord!</p>";
						$retour=true;
					}
				}
				elseif($Mission_Type ==27)
				{
					if($Cible_Atk >0)
					{
						$dive_form='bombardement';
						$choix_dive='Larguer le commando sur';
						$atk_type='Largage de commando';
						$dive=true;
					}
					else
					{
						$intro.="<p>Vous n'avez pas de commando à bord!</p>";
						$retour=true;
					}
				}
				elseif($Mission_Type ==13)
				{
					if($Bombs <1 or $Avion_Bombe !=800)
					{
						$intro.="<p>Vous n'avez pas de torpille, vous devez annuler votre attaque!</p>";
						$retour=true;
					}
					else
					{
						$dive_form='bombardement';
						$choix_dive='Torpiller';
						$atk_type='Torpillage';
						$dive=true;
					}
				}
				elseif($Mission_Type ==29)
				{
					if($Bombs <1 or $Avion_Bombe !=300)
					{
						$intro.="<p>Vous n'avez pas de charge de profondeur, vous devez annuler votre attaque!</p>";
						$retour=true;
					}
					else
					{
						$dive_form='bombardement';
						$choix_dive='Bombarder';
						$atk_type='ASM';
						$dive=true;
					}
				}
				elseif($Mission_Type ==14)
				{
					if($Bombs >0 and $Avion_Bombe ==400)
					{
						$dive_form='bombardement';
						$choix_dive='Mouiller';
						$atk_type='Mouillage de mines';
						$dive=true;
					}
					else
					{
						$intro.="<p>Vous n'avez pas de mines à bord!</p>";
						$retour=true;
					}
				}
			}
			//***WRITE TO DB***
			$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps,true);
			if($dive)
			{
				$choix1='';
				$choix2='';
				$choix3='';
				$choix4='';
				$choix5='';
				$choix6='';
				$choix7='';
				if($Mission_Type <5)
				{
					$con=dbconnecti();
					$result2=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Camouflage,c.Taille,c.Nom FROM Regiment_PVP as r,Cible as c WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Visible=1 AND r.Vehicule_ID <4999");
					mysqli_close($con);
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
					}
					else
						$value="";
					//$Veh_Battle=GetNavirePVP($Battle);
					if($Faction ==2)
						$Veh_Battle="133,131,198,157,130,108,118,31,22,23,124,29,30,120,80,122,123,82,21,20,359,103,113";
					elseif($Faction ==1)
						$Veh_Battle="26,37,62,678,677,675,676,615,618,679,691,692";
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT ID,Pays,Nom FROM Cible WHERE ID IN(".$Veh_Battle.")");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							if($BonusDetect + mt_rand(0,$Vue) > $Malus_Reperer + ($alt/100))
								$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."'>- ".$choix_dive." <img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'> <img src='images/navy".$data['Pays'].".png'><br>";
						}
						mysqli_free_result($result);
						unset($data);
					}
				}
				/*elseif($Mission_Type ==29)
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
				}*/
				else
				{
					/*$Recce=GetData("Lieu","ID",$Cible,"Recce");
					if($data['Recce'] ==2)
					{
						if($Nuit)
							$Recce=100;
						else
							$Recce=50;
					}*/
					$Recce=100;
					switch($Cible_Atk)
					{
						case 1:
							$Camouflage=1;
							if($Nuit)$Camouflage*=2;
							if($BonusDetect + mt_rand(0,$Vue)+$Recce+20 >$Malus_Reperer+$Camouflage+($alt/100))
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." ".$piste."<br>";
							else
								$choix1="Vous ne parvenez pas à repérer la piste!<br>";
							if($BonusDetect + mt_rand(0,$Vue)+$Recce > $Malus_Reperer + $Camouflage + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." un emplacement de D.C.A<br>";
							if($BonusDetect + mt_rand(0,$Vue)+$Recce+10 > $Malus_Reperer + $Camouflage + ($alt/100))
								$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule2.gif' title='la tour de contrôle'><br>";
							if($BonusDetect + mt_rand(0,$Vue)+$Recce+10 > $Malus_Reperer + $Camouflage + ($alt/100))
								$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un hangar'><br>";
						break;
						case 2:
							$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
							$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule3.gif' title='un bâtiment secondaire'><br>";
							$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule4.gif' title='un bâtiment principal'><br>";
							if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
						break;
						case 3:
							if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,100) + ($alt/100))
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." un groupe de soldats de la garnison<br>";
							$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." un bâtiment secondaire<br>";
							$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." le bâtiment principal<br>";
						break;
						case 4:
							$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule8.gif' title='les voies'><br>";
							$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
							$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule9.gif' title='le bâtiment principal'><br>";
							if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
						break;
						case 5:
							if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." un véhicule<br>";
							if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
							$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." le pont, en enfilade<br>";
							$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." le pont, perpendiculairement<br>";
						break;
						case 6:
							$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
							$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule11.gif' title='les réserves de carburant'><br>";
							$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule12.gif' title='les docks'><br>";
							if($BonusDetect + mt_rand(0,$Vue) + $Recce >$Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
						break;
						case 7:
							$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule13.gif' title='un bâtiment secondaire'><br>";
							$choix3="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule15.gif' title='une antenne'><br>";
							$choix4="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." le bâtiment principal<br>";
							if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) + ($alt/100))
								$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
						break;
					}
				}
				if($Mission_Type ==8 or $Mission_Type ==16)
				{
					$intro.="<br>Vous vous alignez sur votre objectif !";
					$approche="<Input type='Radio' name='Action' value='20'>- Recommencer l'approche.<br>";
				}
				else
					$intro.="<br>Vous piquez vers l'objectif !";
				if($Strike)
					$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,8,true);
				else
					$gaz_txt=ShowGaz($avion,$c_gaz,$flaps,$alt,1,true);
				if(!$img)$img=Afficher_Image('images/avions/pique'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
				if(!$choix1 and !$choix2 and !$choix3 and !$choix4 and !$choix5 and !$choix6 and !$choix7){
					$approche='Aucune cible détectée.<br>';
					$choix_rafale='';
				}
				//Deleguer équipage
				if($Deleguer or $Action ==4 or $Action ==5 or $Action ==6 or $Action ==9)$Deleguer=1;
				$_SESSION['cibler']=true;
				SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
				$mes.="<h2>".$atk_type."</h2><form action='index.php?view=".$dive_form."_pvp' method='post'>
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
				<input type='hidden' name='Battle' value=".$Battle.">
				<input type='hidden' name='Camp' value=".$Faction.">
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
		if(!$toolbar)$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps,true);
		if($Action ==90)
		{
			$chemin=0;
			$retour=false;
			//PvP
			$choix96="";
			$choix97="";
			$choix98="";
			$choix99="";
				//AddCandidatPVP($Avion_db,$PlayerID,$avion,$HP,$Puissance,$essence,$chemin,$Distance,$Mun1,$Mun2,$alt,$Cible,$Nuit);
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT PlayerID,Avion,Altitude,Target FROM Duels_Candidats_PVP WHERE Lieu='$Cible' AND PlayerID<>'$Pilote_pvp' AND Country<>'$country'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$PvP_ID=$data['PlayerID'];
						$Tactique_PvP=GetData("Pilote_PVP","ID",$PvP_ID,"Tactique");
						$Vis_eni=GetVis("Avion",$data['Avion'],$Cible,$meteo,$data['Altitude'],$alt);
						$Malus_alt=abs(($alt-$data['Altitude'])/100);
						$Detect=mt_rand(10,$Vue) + $BonusDetect + ($meteo*2) + ($Vis_eni/2) - $Malus_alt + $Radar - ($Tactique_PvP/10);
						if($Detect >0 and !$Chk_Bomb)
						{
							$Target=GetData("Duels_Candidats_PVP","PlayerID",$data['Target'],"Avion");
							$Avion_img="images/avions/avion".$data['Avion'].".gif";
							$Target_img="images/avions/avion".$Target.".gif";
							if($Target)
								$choix99.="<Input type='Radio' name='Action' value='99".$PvP_ID."'>- Se rapprocher du <img src='".$Avion_img."' title='Avion'> combattant un <img src='".$Target_img."' title='Avion'> volant à ".$data['Altitude']."m<br>";
							else
								$choix99.="<Input type='Radio' name='Action' value='99".$PvP_ID."'>- Se rapprocher du <img src='".$Avion_img."' title='Avion'> volant à ".$data['Altitude']."m<br>";
						}
					}
					mysqli_free_result($result);				
				}
				else
				{
					$intro.="<p>Aucun avion ennemi ne semble être dans les environs</p>";
					mail('binote@hotmail.com','Aube des Aigles: GetCandidatPVP',"Joueur : ".$Pilote_pvp." / Cible : ".$Cible);
					$choix99="";
				}
				/*$choix96="<Input type='Radio' name='Action' value='96'>- Survoler la zone en scrutant le ciel, à haute altitude (PvP)<br>";
				$choix97="<Input type='Radio' name='Action' value='97'>- Survoler la zone en scrutant le ciel, à basse altitude (PvP)<br>";
				$choix98="<Input type='Radio' name='Action' value='98'>- Survoler la zone en scrutant le ciel (PvP)<br>";*/
				$PVP_id_engaged=GetData("Duels_Candidats_PVP","Target",$Pilote_pvp,"PlayerID");
				if($PVP_id_engaged)
					$choix1="<Input type='Radio' name='Action' value='99".$PVP_id_engaged."' checked>- Attaquer votre adversaire (PvP)<br>";				
			$_SESSION['cibler']=true;
			SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
			$mes.="<form action='index.php?view=objectif_pvp' method='post'>
			<input type='hidden' name='Avion' value=".$avion.">
			<input type='hidden' name='Meteo' value=".$meteo.">	
			<input type='hidden' name='Chemin' value=".$chemin.">	
			<input type='hidden' name='Distance' value=".$Distance.">
			<input type='hidden' name='Mun1' value=".$Mun1.">
			<input type='hidden' name='Mun2' value=".$Mun2.">
			<input type='hidden' name='Puissance' value=".$Puissance.">
			<input type='hidden' name='Battle' value=".$Battle.">
			<input type='hidden' name='Camp' value=".$Faction.">
			<table class='table'>
				<tr><td colspan='8'>Arrivée sur l'objectif</td></tr>
				<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,1,true)."
					<td align='left'>
						".$choix1.$choix96.$choix97.$choix98.$choix99."
					</td></tr></table>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		if($retour and !$end_mission)
		{
			SetData("Pilote_PVP","S_Essence",$essence,"ID",$Pilote_pvp);
			Chemin_Retour();
			$chemin=$Distance;
			$_SESSION['cibler']=true;
			$intro.='<br>Vous prenez le chemin du retour en direction de votre base, située à '.$Distance.'km';
			$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$nom_avion);
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
			<table class='table'>
				<tr>".ShowGaz($avion,$c_gaz,$flaps,$alt,false,true)."</tr>
			</table>
			<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}	
	if($end_mission)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Unite_Intercept=0,Escorte=0,Couverture=0,Points=Points-1,Abattu=Abattu+1,Avion_Sandbox=0,S_HP=0 WHERE ID='$Pilote_pvp'");
		mysqli_close($con);
		RetireCandidatPVP($Pilote_pvp,"end_mission");
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		if($Zone ==6)
		{
			if($Slot5 ==17 or $Slot5 ==35)
				$intro.="<br>Votre gilet de sauvetage vous sauve de la noyade!";
			else
				$intro.="<br>Sans gilet de sauvetage, vous êtes emporté par la mer jusqu'au rivage!";
		}
		$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
		$mes.="<p><b>FIN DE MISSION</b></p>";
		$menu.='<form action=\'index.php?view=profil_pvp\' method=\'post\'>
			<input type=\'Submit\' value=\'TERMINER LA MISSION\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
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