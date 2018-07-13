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
$meteo=Insec($_POST['Meteo']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$roues=Insec($_POST['roues']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $_SESSION['mia_status'] ==false AND $avion >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_msg.inc.php');
	$_SESSION['bombarder']=false;
	$_SESSION['attaquer']=false;
	$_SESSION['photographier']=false;
	$_SESSION['objectif']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['cibler']=false;
	$_SESSION['mia_status']=true;
	$Place=0;	
	$Distance=$_SESSION['Distance'];
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT S_HP,Unit,Equipage,Tactique,Courage,Moral,S_Avion_db,S_Cible,S_Mission,S_Nuit,S_Cible_Atk,S_Essence,S_Blindage,S_Formation,Slot10,S_Avion_Bombe,S_Avion_Bombe_Nbr
    FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$HP=$data['S_HP'];
			$Unite=$data['Unit'];
			$Tactique=$data['Tactique'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Avion_db=$data['S_Avion_db'];
			$Cible=$data['S_Cible'];
			$Cible_Atk=$data['S_Cible_Atk'];
			$Mission_Type=$data['S_Mission'];
			$Nuit=$data['S_Nuit'];
			$Equipage=$data['Equipage'];
			$essence=$data['S_Essence'];
			$S_Blindage=$data['S_Blindage'];
			$Formation=$data['S_Formation'];
			$Slot10=$data['Slot10'];
            $Fret=$data['S_Avion_Bombe'];
            $Fret_nbr=$data['S_Avion_Bombe_Nbr'];
		}
		mysqli_free_result($result);
	}
	$VisAvion=GetVis($Avion_db,$avion,$Cible,$meteo,$alt,0,$PlayerID,$Unite);
	$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion);
	$avion_img=GetAvionImg($Avion_db,$avion);
	$retour=false;
	$rescue=false;
	$end_mission=false;
	$decol=false;	
	switch($Action)
	{
		case 0:
			//cancel
			$img=Afficher_Image('images/avions/formation'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$avion_img);
			$intro.="<br>Vous annulez votre mission.";
			$retour=true;
		break;
		default:
			$alt=100;		
		break;
	}
	if(!$retour)
	{	
		if($Mission_Type ==23)
			$rescue=true;
		else
		{
			$DefenseAA=GetData("Lieu","ID",$Cible,"DefenseAA_temp");
			if($DefenseAA)
			{
				$Arme1=6;
				$Flak=3;
				$intro.='<br>Vous vous trouvez à '.$alt.'m d\'altitude. <b>La défense anti-aérienne ouvre le feu sur vous!</b>';
				if($Nuit)
					$img.="<img src='images/flak_nuit.jpg' style='width:50%;'>";
				else
					$img.="<img src='images/flak".$Flak.GetData("Lieu","ID",$Cible,"Flag").".jpg' style='width:100%;'>";
				$Malus_Range=$alt/100;
				$Shoot=mt_rand(0,$DefenseAA*10)+$meteo+$VisAvion-$Malus_Range-($Tactique/10)-($Pilotage/10);
				if($Shoot >10)
				{
					$Blindage=GetData($Avion_db,"ID",$avion,"Blindage");
					if(!$Blindage)
					{
						$Blindage=$S_Blindage;
						if(!$Blindage)
							$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
					}
					if($Arme1 !=5 and $Arme1 !=0)
						$Degats_max1=(mt_rand(1,GetData("Armes","ID",$Arme1,"Degats"))-pow($Blindage,2))*GetShoot($Shoot,GetData("Armes","ID",$Arme1,"Multi"));
					$Degats=round($Degats_max1+$Degats_max2+$Degats_max3);
					if($Degats <1)$Degats=mt_rand(1,10);
					$HP-=$Degats;
					//HP Avion perso persistant
					if($Avion_db =="Avions_Persos")
					{
						if($HP <1)$HP=0;
						SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
					}
					if($HP <1)
					{
						$intro.='<br>L\'explosion met le feu à votre avion, ne vous laissant pas d\'autre choix que de sauter en parachute. ('.$Degats.' points de dégats!)';
						$end_mission=true;
					}
					else
					{
						$intro.='<br>Des éclats d\'obus explosent non loin de votre appareil, lui occasionnant <b>'.$Degats.'</b> points de dégats! (Votre appareil peut encore encaisser : '.$HP.')';
						if($Equipage)
						{
							if(GetData("Equipage","ID",$Equipage,"Moral") >0 and GetData("Equipage","ID",$Equipage,"Courage") >0)
							{
								$Equipage_Nom=GetData("Equipage","ID",$Equipage,"Nom");
								$Meca=floor(GetData("Equipage","ID",$Equipage,"Mecanique")/2);
								if($Simu)
									UpdateCarac($Equipage,"Mecanique",1,"Equipage");
								if($Meca >$Degats)$Meca=$Degats;
								$intro.='<p>'.$Equipage_Nom.' vous signale qu\'il peut réparer <b>'.$Meca.'</b> Point(s) de Dégât(s)</p>';
								$HP+=$Meca;
							}
						}
						$rescue=true;
					}
					SetData("Pilote","S_HP",$HP,"ID",$PlayerID);
				}
				else
				{
					$intro.="<br>Les éclats d'obus encadrent votre appareil, mais vous parvenez à les éviter!";
					$rescue=true;
				}
			}
			else
				$rescue=true;
		}
		if($rescue)
		{
			$con=dbconnecti();
			$resulta=mysqli_query($con,"SELECT Nom,Helice,Train,ChargeAlaire,Robustesse,ManoeuvreB,ManoeuvreH,Maniabilite FROM $Avion_db WHERE ID='$avion'");
			mysqli_close($con);
			if($resulta)
			{
				while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
				{
					$NomAvion=$data['Nom'];
					$HPmax=$data['Robustesse'];
					$Helice=$data['Helice'];
					$Train=$data['Train'];
					$ChargeAlaire=$data['ChargeAlaire'];
					$ManB=$data['ManoeuvreB'];
					$ManH=$data['ManoeuvreH'];
					$Mani=$data['Maniabilite'];
				}
				mysqli_free_result($resulta);
			}
			$ManoeuvreB=GetMano($ManH,$ManB,$HPmax,$HP,1,1,1,$flaps);
			$Mani=GetMani($Mani,$HPmax,$HP,1,1,$flaps);			
			if($Mission_Type ==23)
			{
				if($roues)
				{
					switch($Train)
					{
						case 1:
							$Train=10;
						break;
						case 2:
							$Train=25;
						break;
						case 8:
							$Train=10;
						break;
						case 9:
							$Train=-5;
						break;
						case 13: case 16:
							$Zone=GetData("Lieu","ID",$Cible,"Zone");
							if($Zone ==6)
								$Train=50;
							else
								$Train=-50;
						break;
						default:
							$Train=1;
						break;
					}
				}
				else
				{
					if($Train <5)
						$intro.="Votre train n'est pas sorti, vous devez atterrir sur le ventre!<br>";
					else
						$intro.="Votre train est endommagé, vous devez atterrir sur le ventre!<br>";
					$Train=-50;
				}
				$QualitePiste=GetData("Lieu","ID",$Cible,"QualitePiste");
				$ChargeAlaire=$ChargeAlaire/100;
			}
			else
			{
				$Zone=GetData("Lieu","ID",$Cible,"Zone");
				switch($Zone)
				{
					case 0:
						$intro.='<br>Vous tentez d\'atterrir en rase campagne aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(20,80);
					break;
					case 1:
						$intro.='<br>Vous tentez d\'atterrir dans ces collines aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(10,70);
					break;
					case 2:
						$intro.='<br>Vous tentez d\'atterrir dans une clairière aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(10,80);
					break;
					case 3:
						$intro.='<br>Vous tentez d\'atterrir dans ces collines boisées aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(0,60);
					break;
					case 4:
						$intro.='<br>Vous tentez d\'atterrir dans ces montagnes aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(0,30);
					break;
					case 5:
						$intro.='<br>Vous tentez d\'atterrir dans ces montagnes boisées aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(0,10);
					break;
					case 6:
						$intro.='<br>Vous tentez d\'amerrir aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(90,100)-$meteo;
						$ChargeAlaire /= 10;
					break;
					case 7:
						$intro.='<br>Vous tentez d\'atterrir dans cette zone urbaine aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(10,90);
					break;
					case 8:
						$intro.='<br>Vous tentez d\'atterrir en plein désert aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(20,80);
					break;
					case 9:
						$intro.='<br>Vous tentez d\'atterrir en pleine jungle aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(0,20);
					break;
					case 11:
						$intro.='<br>Vous tentez d\'atterrir en plein marécage aux commandes de votre <b>'.$NomAvion.'</b>.';
						$QualitePiste=mt_rand(0,20);
					break;
				}
			}
			if($Nuit)$meteo_bonus=$meteo+85;
			$Piste=(100-$QualitePiste)*2;
			$Landing=$Pilotage+$ManoeuvreB-($ChargeAlaire/20)-($Piste*2)+($Moral/10)+($Courage/10)+($Helice*5)+($Train*20)-$c_gaz+$meteo_bonus;
			//Porte-bonheur
			if($Slot10 ==34 or $Slot10 ==71 or $Slot10 ==72)$Landing+=10;
			//Landing
			if($Landing >0)
			{
				if($PlayerID ==1)
				{
					$skills.="<br>[Score à l'atterrissage: ".$Landing."]
					<br>(Pilotage: ".$Pilotage."; Qualité de la piste : -".$Piste."; Charge Alaire de l'avion /20: ".$ChargeAlaire."; Manoeuvrabilité de l'avion /10: ".$ManoeuvreB.")";
				}
				$intro.="<p><b>Vous atterrissez sans problème.</b></p>";
				$img=Afficher_Image('images/avions/landing'.$avion_img.'.jpg','images/avions/decollage'.$avion_img.'.jpg',$NomAvion);
				UpdateCarac($PlayerID,"Moral",10);
				UpdateCarac($PlayerID,"Reputation",2);
				if($Mission_Type ==23)
					AddPilotage($Avion_db,$avion,$PlayerID,1);
				else
				{
					AddPilotage($Avion_db,$avion,$PlayerID,1);
					UpdateCarac($Action,"Moral",20);
				}
				$decol=true;
			}
			elseif($Landing <-50)
			{
				if($PlayerID ==1)
				{
					$skills.="<br>[Score à l'atterrissage: ".$Landing."]
					<br>(Pilotage: ".$Pilotage."; Qualité de la piste : -".$Piste."; Maniabilité de l'avion /10: ".$Mani."; Manoeuvrabilité de l'avion /10: ".$ManoeuvreB."; 
					Malus Météo: ".$Meteo[1]."; Incident Technique: ".$Incident[1].")";
				}
				if($Zone ==6)
				{
					$intro.="<p>Vous amerrissez, mais la houle vous fait perdre le contrôle de votre appareil qui percute la surface de l'eau. 
					<br>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
				}
				elseif($Mission_Type ==23)
				{
					$intro.="<p>Vous perdez le contrôle de votre appareil qui s'écrase. 
					<br>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
				}
				else
				{
					$intro.="<p>Vous atterrissez, mais l'état lamentable du sol vous fait perdre le contrôle de votre appareil qui s'écrase. 
					<br>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
				}
				$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg', 'images/avions/crash.jpg', 'crash');
				//AddEvent($Avion_db,12,$avion,$PlayerID,$Unite,$base,1);
				UpdateCarac($Action,"Moral",-10);
				UpdateData("Unit","Reputation",-5,"ID",$Unite,0,10);
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,MIA='$Cible',Crashs_Jour=Crashs_Jour+1,Moral=Moral-5,Reputation=Reputation-5,Endurance=Endurance-1 WHERE ID='$PlayerID'");
				mysqli_close($con);
				AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
				//Eviter abattu DCA
				$HP=1;
				$end_mission=true;
			}
			else
			{
				if($PlayerID ==1)
				{
					$skills.="<br>[Score à l'atterrissage: ".$Landing."]
					<br>(Pilotage: ".$Pilotage."; Qualité de la piste : -".$QualitePiste."; Maniabilité de l'avion /10: ".$Mani."; Manoeuvrabilité de l'avion /10: ".$ManoeuvreB."; Stabilité de l'avion /10: ".$Stab."; 
					Malus Météo: ".$Meteo[1]."; Incident Technique: ".$Incident[1].")";
				}
				if($Zone ==6)
					$intro.="<p>Vous amerrissez. Malgré la houle, vous parvenez à conserver l'appareil en un seul morceau!</p>";
				else
					$intro.="<p>Vous atterrissez. Malgré l'état lamentable du sol, vous parvenez à conserver l'appareil en un seul morceau!</p>";
				$intro.="<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>";
				$HP=$HP-mt_rand(1,50);
				if($HP <1)$HP=1;
				$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
				//AddEvent($Avion_db,12,$avion,$PlayerID,$Unite,$base,0);
				UpdateCarac($PlayerID,"Endurance",-1);
				UpdateCarac($PlayerID,"Moral",-5);
				$decol=true;
			}
		}		
	}
	$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,100,1,$Avion_db,$flaps);
	if($decol)
	{
		$Decollage=$Pilotage+$ManoeuvreB-($ChargeAlaire/20)-$Piste+($Moral/10)+($Courage/10)+($Helice*5)+($Train*20);
		if($Mission_Type ==23)
		{
			if(strpos($Action,"_") !==false)
			{
				$Action=strstr($Action,'_',true);
                $Unit_table="Regiment_IA";
                /* old regiment
                $Unit_table="Regiment";
				$EventMun=312;
				$EventEss=311;*/
			}
			else
			{
				$Unit_table="Unit";
				$EventMun=112;
				$EventEss=111;
			}
			$Reg_slot="";
			$Fret_nbr+=($Fret_nbr*$Formation);
			switch($Fret)
			{
				case 50:
					$Fret_slot="Bombes_50";
					AddEvent("Avion",113,$Fret,$PlayerID,$Action,$Cible,$Fret_nbr);
				break;
				case 125:
					$Fret_slot="Bombes_125";
					AddEvent("Avion",113,$Fret,$PlayerID,$Action,$Cible,$Fret_nbr);
				break;
				case 250:
					$Fret_slot="Bombes_250";
					AddEvent("Avion",113,$Fret,$PlayerID,$Action,$Cible,$Fret_nbr);
				break;
				case 500:
					$Fret_slot="Bombes_500";
					AddEvent("Avion",113,$Fret,$PlayerID,$Action,$Cible,$Fret_nbr);
				break;
				case 1100:
					$Fret_slot="Stock_Essence_100";
					AddEvent("Avion",111,100,$PlayerID,$Action,$Cible,$Fret);
					$Fret_nbr=$Fret+($Fret*$Formation);
				break;
				case 1200:
					$Reg_slot="Stock_Essence_87";
					$Fret_slot="Stock_Essence_87";
					AddEvent("Avion",111,87,$PlayerID,$Action,$Cible,$Fret);
					$Fret_nbr=$Fret+($Fret*$Formation);
				break;
				case 1500:
					$Reg_slot="Stock_Munitions_40";
					$Fret_slot="Stock_Munitions_40";
					AddEvent("Avion",111,40,$PlayerID,$Action,$Cible,$Fret);
					$Fret_nbr=$Fret+($Fret*$Formation);
				break;
				case 3000:
					$Reg_slot="Stock_Munitions_30";
					$Fret_slot="Stock_Munitions_30";
					AddEvent("Avion",112,30,$PlayerID,$Action,$Cible,$Fret);
					$Fret_nbr=$Fret+($Fret*$Formation);
				break;
				case 5000:
					$Reg_slot="Stock_Munitions_20";
					$Fret_slot="Stock_Munitions_20";
					AddEvent("Avion",112,20,$PlayerID,$Action,$Cible,$Fret);
					$Fret_nbr=$Fret+($Fret*$Formation);
				break;
				case 15000:
					$Reg_slot="Stock_Munitions_13";
					$Fret_slot="Stock_Munitions_13";
					AddEvent("Avion",112,13,$PlayerID,$Action,$Cible,$Fret);
					$Fret_nbr=$Fret+($Fret*$Formation);
				break;
				case 50000:
					$Reg_slot="Stock_Munitions_8";
					$Fret_slot="Stock_Munitions_8";
					AddEvent("Avion",112,8,$PlayerID,$Action,$Cible,$Fret);
					$Fret_nbr=$Fret+($Fret*$Formation);
				break;
				default:
					$Fret_slot="";
				break;
			}
			if($Fret_slot and $Unit_table =="Unit")
			{
				$Mission_Type_D=GetData("Unit","ID",$Action,"Mission_Type_D");
				$Mission_Lieu_D=GetData("Unit","ID",$Action,"Mission_Lieu_D");
				if($Mission_Type_D ==23 and $Mission_Lieu_D ==$Cible)
				{
					UpdateData("Unit",$Fret_slot,$Fret_nbr,"ID",$Action,500000);		
					$Base=GetData("Unit","ID",$Unite,"Base");
					if($Base !=$Cible)
					{
						$Dist_Pts=10+floor($Distance / 10);
						if($Dist_Pts >100)$Dist_Pts=100;
						$con=dbconnecti();
						$resetu=mysqli_query($con,"UPDATE Unit SET Reputation=Reputation+'$Dist_Pts',Mission_Type_D=0,Mission_Lieu_D=0 WHERE ID='$Unite'");
						$reset=mysqli_query($con,"UPDATE Pilote SET Missions=Missions+'$Dist_Pts',Reputation=Reputation+'$Dist_Pts',Avancement=Avancement+'$Dist_Pts',S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$PlayerID'");
						$resetia=mysqli_query($con,"UPDATE Pilote_IA SET Points=Points+'$Dist_Pts',Avancement=Avancement+'$Dist_Pts' WHERE Unit='$Unite' AND Cible='$Cible'");
						mysqli_close($con);
					}
					AddRavit($Avion_db,$avion,$PlayerID,$Unite,$Cible,$Action,$Fret,$Fret_nbr);
					$intro.="<p>Vous déchargez votre cargaison et ravitaillez l'unité aérienne. Votre mission est un succès!</p>";
				}
				else
					$intro.="<p>L'unité n'a pas besoin de votre livraison!</p>";
			}
			elseif($Reg_slot and $Unit_table =="Regiment")
			{
				UpdateData("Regiment",$Fret_slot,$Fret_nbr,"ID",$Action,500000);		
				$Base=GetData("Unit","ID",$Unite,"Base");
				if($Base !=$Cible)
				{
					$Dist_Pts=10+floor($Distance/10);
					if($Dist_Pts >100)$Dist_Pts=100;
					UpdateData("Unit","Reputation",$Dist_Pts,"ID",$Unite,0,15);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pilote SET Missions=Missions+'$Dist_Pts', Reputation=Reputation+'$Dist_Pts', Avancement=Avancement+'$Dist_Pts', S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$PlayerID'");
					$resetia=mysqli_query($con,"UPDATE Pilote_IA SET Points=Points+'$Dist_Pts', Avancement=Avancement+'$Dist_Pts' WHERE Unit='$Unite'");
					mysqli_close($con);
				}
				AddRavit($Avion_db,$avion,$PlayerID,$Unite,$Cible,$Action,$Fret,$Fret_nbr,1);
				$intro.="<p>Vous déchargez votre cargaison et ravitaillez l'unité terrestre. Votre mission est un succès!</p>";
			}
			elseif($Unit_table =="Regiment_IA")
            {
                $Dist_Pts=10+floor($Distance/10);
                if($Dist_Pts >100)$Dist_Pts=100;
                $con=dbconnecti();
                $reset=mysqli_query($con,"UPDATE Pilote SET Missions=Missions+'$Dist_Pts', Reputation=Reputation+'$Dist_Pts', Avancement=Avancement+'$Dist_Pts', S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0 WHERE ID='$PlayerID'");
                $resetia=mysqli_query($con,"UPDATE Pilote_IA SET Points=Points+'$Dist_Pts', Avancement=Avancement+'$Dist_Pts' WHERE Unit='$Unite'");
                $updateunit=mysqli_query($con,"UPDATE Unit SET Reputation=Reputation+'$Dist_Pts' WHERE ID='$Unite'");
                $updatereg=mysqli_query($con,"UPDATE Regiment_IA SET Ravit=1 WHERE ID='$Action'");
                mysqli_close($con);
                AddRavit($Avion_db,$avion,$PlayerID,$Unite,$Cible,$Action,99,1,1);
                $intro.="<p>Vous déchargez votre cargaison et ravitaillez l'unité terrestre. Votre mission est un succès!</p>";
            }
			else
			{
				$intro.="<p>Vous n'avez aucune cargaison à décharger!</p>";
				UpdateCarac($PlayerID,"Reputation",-5);
				UpdateCarac($PlayerID,"Avancement",-5);
				UpdateData("Unit","Reputation",-10,"ID",$Unite,0,15);
			}
		}
		elseif($Mission_Type ==28)
		{
			$Cdo=GetData("Pilote","ID",$Cible_Atk,"Nom");
			if($Cdo)
			{
				$Valstrat=(GetData("Lieu","ID",$Cible,"ValeurStrat")*5)+30;
				$intro.='<p><b>Vous larguez '.$Cdo.' sur l\'objectif.<br>Vous avez accompli votre mission!</b></p>';
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET MIA='$Cible',Commando=0 WHERE ID='$Cible_Atk'");
				mysqli_close($con);	
				SendMsgOff($Cible_Atk,$PlayerID,"Salut Camarade !\n Vous voilà sur l\'objectif. A vous de jouer maintenant !","Commando : Arrivée à destination!",3,3);
				UpdateCarac($PlayerID,"Missions",$Valstrat);
				UpdateCarac($PlayerID,"Reputation",$Valstrat);
				UpdateCarac($PlayerID,"Avancement",$Valstrat);
			}
			else
			{
				$intro.="<p>Vous n'avez pas de commando à bord! La mission est annulée !</p>";
				UpdateCarac($PlayerID,"Moral",-10);
				UpdateCarac($PlayerID,"Reputation",-10);
				UpdateCarac($PlayerID,"Avancement",-2);
			}
		}
		else
		{
			$Place=GetData($Avion_db,"ID",$avion,"Equipage");
			if($Place >1)
				$mes.="<p><b>".GetData("Pilote","ID",$Action,"Nom")."</b> monte à bord <br>Vous décollez aux commandes de votre <b>".$NomAvion."</b></p>";
			else
				$mes.="<p>Manquant de place à bord, <b>".GetData("Pilote","ID",$Action,"Nom")."</b> ne peut que vous regarder tristement décoller aux commandes de votre <b>".$NomAvion."</b></p>";
		}				
		if($Decollage >0)
		{
			$img="<img src='images/avions/decollage".$avion_img.".jpg' style='width:100%;'>";
			AddPilotage($Avion_db,$avion,$PlayerID,1);
			UpdateCarac($PlayerID,"Moral",1);
			if($Place >1)UpdateCarac($Action,"Moral",20);
			$retour=true;
		}
		elseif($Decollage <-50)
		{
			if($Zone ==6)
			{
				$mes.="<p>Vous entamez votre course de décollage, mais la houle vous fait capoter, écrasant votre appareil contre une vague. Quelle poisse !</p>
				<p>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
			}
			else
			{
				$mes.="<p>Vous entamez votre course de décollage, mais l'état lamentable du sol vous fait capoter, écrasant votre appareil dans un arbre. Quelle poisse !</p>
				<p>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
			}
			$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
			//AddEvent($Avion_db,11,$avion,$PlayerID,GetData("Pilote","ID",$PlayerID,"Unit"),$base,1);
			if($Mission_Type !=23)
				UpdateCarac($Action,"Moral",-20);
			UpdateCarac($PlayerID,"Moral",-10);
			UpdateCarac($PlayerID,"Reputation",-10);
			UpdateCarac($PlayerID,"Avancement",-2);
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,MIA='$Cible' WHERE ID='$PlayerID'");
			mysqli_close($con);
			AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
			UpdateCarac($PlayerID,"Crashs_Jour",1);
			$end_mission=true;
		}
		else
		{
			$mes.="<p>Vous parvenez à décoller malgré l'état lamentable du sol. Il s'en est fallu de peu !<br>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>";
			//$skills.="<br>[Score de décollage: ".$Decollage."]
			//<br>(Pilotage: ".$Pilotage."; Qualité de la piste : -".$QualitePiste."; Maniabilité de l'avion: ".$Mani."; Manoeuvrabilité de l'avion: ".$ManoeuvreB."; Malus Météo: ".$Meteo[1]."; Incident Technique: ".$Incident[1].")";
			$img=Afficher_Image('images/avions/decollage'.$avion_img.'.jpg',"images/image.png",$NomAvion);
			//AddEvent($Avion_db,11,$avion,$PlayerID,GetData("Pilote","ID",$PlayerID,"Unit"),$base,0);
			if($Mission_Type !=23)
				UpdateCarac($Action,"Moral",20);
			$retour=true;
		}
	}
	if($retour)
	{
		SetData("Pilote","S_Cible_Atk",0,"ID",$PlayerID);
		$Enis=0;
		Chemin_Retour();
		$chemin=$Distance;
		if($Place >1)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET MIA=0,Missions_Jour=1 WHERE ID='$Action'");
			mysqli_close($con);	
			AddEvent($Avion_db,36,$avion,$PlayerID,$Unite,$Cible,$Action);
			AddSauvetage($Avion_db,$avion,$PlayerID,$Unite,$Cible,$Action,$Nuit);
			UpdateCarac($PlayerID,"Note",1);
			SendMsgOff($Action,$PlayerID,"Salut Camarade !\n Alors, content d être de retour ? Là-bas c était l enfer pas vrai ?","MIA : Sauvetage réussi!",3,3);
			$mes.='<p>Vous prenez le chemin du retour en direction de votre base, située à '.$Distance.'km</p>';
			$_SESSION['mia_status'] =2;
		}
		else
			$mes.="Vous prenez le chemin du retour en direction de votre base";
		$menu.="<form action='nav.php' method='post'>
		<input type='hidden' name='Chemin' value=".$chemin.">
		<input type='hidden' name='Distance' value=".$Distance.">
		<input type='hidden' name='Meteo' value=".$meteo.">
		<input type='hidden' name='Avion' value=".$avion.">
		<input type='hidden' name='Mun1' value=".$Mun1.">
		<input type='hidden' name='Mun2' value=".$Mun2.">
		<input type='hidden' name='Puissance' value=".$Puissance.">
		<input type='hidden' name='Enis' value=".$Enis.">
		<table class='table'><tr>".ShowGaz($avion,$c_gaz,$flaps,$alt)."</tr></table>
		<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}	
	if($end_mission)
	{
		if($_SESSION['PVP'])
			RetireCandidat($PlayerID,"end_mission");
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
		UpdateCarac($PlayerID,"Abattu",1);
		if($HP <1)
		{
			//Tableau de chasse
			AddVictoire_atk($Avion_db,0,16,$avion,$PlayerID,$Unite,$Cible,$Arme1,$country,1,$alt,$Nuit,$Degats);
			AddEvent($Avion_db,3,$avion,$PlayerID,$Unite,$Cible);
			UpdateCarac($PlayerID,"Crashs_Jour",1);
		}
		else
			AddEvent($Avion_db,34,$avion,$PlayerID,$Unite,$Cible);
		//Blessure
		$blesse=0;
		$Blessure=GetBlessure($PlayerID,$Avion_db,$avion);
		switch($Blessure)
		{
			case 0:
				$Blessure_txt="<br><br>Vous vous en sortez indemne!";
				$Hard=1;
				$Malus_Moral=-25;
			break;
			case 1:
				$Blessure_txt="<br><br>Vous êtes blessé, mais néanmoins en vie!";
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
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,MIA='$Cible' WHERE ID='$PlayerID'");
				mysqli_close($con);
				AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
				$blesse=2;
				DoBlessure($PlayerID,10);
			break;
		}
		$mes.=$Blessure_txt;
		UpdateCarac($PlayerID,"Moral",$Malus_Moral);
		if($Equipage and $Type_avion !=1 and $Type_avion !=12)
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
		$Base=GetData("Unit","ID",$Unite,"Base");
		$Dist=GetDistance($Base,$Cible,$Longitude,$Latitude);
		if($Slot10 ==71)
			$luck_p=mt_rand(0,20);
		elseif($Slot10 ==72 or $Slot10 ==77)
			$luck_p=mt_rand(0,10);
		elseif($Slot10 ==34)
			$luck_p=mt_rand(0,10);
		else
			$luck_p=0;
		if($Mission_Type !=7 and $Mission_Type !=9 and $Mission <90 and $Mission_Type !=23 and $Dist[0] >30 and $luck_p <5 and $blesse <2)
		{
			$intro.="<p>Vous vous retrouvez au beau milieu d'une zone contrôlée par l'ennemi.
			<br>Le temps de regagner vos lignes vous rend indisponible jusqu'à votre retour.</p>";
			$mes.="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,MIA='$Cible' WHERE ID='$PlayerID'");
			mysqli_close($con);
			if($blesse <2)
				AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
		}
		else
		{
			$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
			UpdateCarac($PlayerID,"Reputation",-10);
			SetData("Pilote","MIA",0,"ID",$PlayerID);
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.="<form action='promotion.php' method='post'>
				<input type='hidden'  name='Blesse'  value=".$blesse.">
				<input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
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
include_once('./index.php');
?>