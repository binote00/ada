<?php
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_combat.inc.php');
	$NoIncidentJournal=false;
	$PvPVic=false;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Unit,Avion_Perso,S_Avion_db,S_Nuit,S_Cible,S_Mission,S_Longitude,S_Latitude,S_HP,Slot5,Slot10,Sandbox FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unite=$data['Unit'];
			$Avion_Perso=$data['Avion_Perso'];
			$Avion_db=$data['S_Avion_db'];
			$Nuit=$data['S_Nuit'];
			$Cible=$data['S_Cible'];
			$Mission=$data['S_Mission'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$HP=$data['S_HP'];
			$Slot5=$data['Slot5'];
			$Slot10=$data['Slot10'];
			$Sandbox=$data['Sandbox'];
		}
		mysqli_free_result($result);
		unset($data);
	}	
	if($Sandbox)
	{
		UpdateCarac($PlayerID,"Free",-1);
		if($Avion_db =="Avions_Sandbox")$avion=GetData($Avion_db,"ID",$avion,"ID_ref");
		$date=date('Y-m-d G:i');
		$query="INSERT INTO Chasse_sandbox (Date, Avion_loss, Avion_win, Joueur_win, Unite_win, Unite_loss, Lieu, Arme_win, Pilote_loss, PVP, Cycle, Longitude, Latitude, Altitude)
		VALUES ('$date','$avion','$avion_eni','$Pilote_eni','$Unit_eni','$Unite','$Cible','$Arme1Avion_eni','$PlayerID',1,'$Nuit','$Longitude','$Latitude','$alt')";
		$con=dbconnecti(2);
		$ok=mysqli_query($con,$query);
		mysqli_close($con);	
		$_SESSION['Distance']=0;
		$_SESSION['PVP']=false;
		$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
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
		$Base=GetData("Unit","ID",$Unite,"Base");
		$Zone=GetData("Lieu","ID",$Cible,"Zone");		
		if($c_gaz <20)
		{
			$Avancement_add=-50;
			$Reputation_add=-50;
			$Tactique_add=-10;
			$Pilotage_add=-10;
		}
		else
		{
			$Avancement_add=0;
			$Reputation_add=0;
			$Tactique_add=0;
			$Pilotage_add=0;
		}
		//Set Vars
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote SET enis=0,avion_eni=0,S_Unite_Intercept=0,Escorte=0,Couverture=0,Avancement=Avancement+".$Avancement_add.",
		Reputation=Reputation+".$Reputation_add.",Tactique=Tactique+".$Tactique_add.",Pilotage=Pilotage+".$Pilotage_add." WHERE ID='$PlayerID'");
		mysqli_close($con);
		if(!$Pilotage)$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,0,$Pilotage);
		if(!$avion and $Avion_db =="Avions_Persos")
		{
			$avion=GetData("Avions_Persos","ID",$Avion_Perso,"ID_ref");
			$HP_max=GetData("Avions_Persos","ID",$Robustesse,"ID_ref");
		}
		if($Avion_db =="Avion")$HP_max=GetData("Avion","ID",$avion,"Robustesse");		
		RetireCandidat($PlayerID,"end_mission");
		if($_SESSION['PVP'])$PvPVic=true;
		$Avion_Perdu=false;
		if($HP <1 or $_SESSION['Parachute'] ==true or $HP <$HP_max or $c_gaz <20)
		{
			//Tableau de chasse
			if(!$NoAddVic)
			{
				if($PvPVic)
				{
					$Vic_Etat=3;
					$Unit_eni=GetData("Pilote","ID",$Pilote_eni,"Unit");
				}
				else
				{
					$Vic_Etat=1;
					UpdateCarac($Pilote_eni,"Victoires",1,"Pilote_IA");
					UpdateData("Unit","Reputation",10,"ID", $Unit_eni,0,4);
				}
				AddVictoire($Avion_db,$avion,$avion_eni,$Pilote_eni,$Unit_eni,$Unite,$Cible,$Arme1Avion_eni,$PlayerID,$Vic_Etat,$Nuit,$alt);
				UpdateCarac($PlayerID,"Abattu",1);
				AddEvent($Avion_db,3,$avion,$PlayerID,$Unite,$Cible);
				$NoIncidentJournal=true;
			}
			$Avion_Perdu=true;
			$NoAddVic=false;
		}
		else
		{
			//Crash
			$Dist=GetDistance(0,$Base,$Longitude,$Latitude);
			if($Mission ==9 or $Mission ==7 or $Mission ==17 or ($Dist[0]<30))
			{
				$Stab=GetStab($Avion_db,$avion,$HP,$moda);
				$ManoeuvreB=GetMan($Avion_db,$avion,1,$HP,1,1,$flaps);
				$planeur=mt_rand(10,$Pilotage)+$meteo-($alt/100)+($ManoeuvreB/10)+($Stab/10);
				if($planeur >0)
				{
					$NomAvion=GetData($Avion_db,"ID",$avion,"Nom");
					$Helice=GetData($Avion_db,"ID",$avion,"Helice");
					$Train=GetData($Avion_db,"ID",$avion,"Train");
					switch($Zone)
					{
						case 0:
							$mes.="<br>Vous tentez d'atterrir en rase campagne aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(20,80);
						break;
						case 1:
							$mes.="<br>Vous tentez d'atterrir dans ces collines aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(10,70);
						break;
						case 2:
							$mes.="<br>Vous tentez d'atterrir dans une clairière aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(10,80);
						break;
						case 3:
							$mes.="<br>Vous tentez d'atterrir dans ces collines boisées aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(0,60);
						break;
						case 4:
							$mes.="<br>Vous tentez d'atterrir dans ces montagnes aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(0,30);
						break;
						case 5:
							$mes.="<br>Vous tentez d'atterrir dans ces montagnes boisées aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(0,10);
						break;
						case 6:
							$mes.="<br>Vous tentez d'amerrir aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(90,100) - $meteo;
						break;
						case 7:
							$mes.="<br>Vous tentez d'atterrir dans cette zone urbaine aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(10,90);
						break;
						case 8:
							$mes.="<br>Vous tentez d'atterrir en plein désert aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(20,80);
						break;
						case 9:
							$mes.="<br>Vous tentez d'atterrir en pleine jungle aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(0,20);
						break;
						case 11:
							$mes.="<br>Vous tentez d'atterrir en plein marécage aux commandes de votre <b>".$NomAvion."</b>.";
							$QualitePiste=mt_rand(0,20);
						break;
					}			
					$Landing=$Pilotage+($ManoeuvreB/10)+($Mani/10)+($Stab/10)-$QualitePiste+($Moral/10)+($Courage/10)+($Helice*5)+($Train*5);
					if($Landing)
						$mes.="<p>Vous parvenez à poser votre avion sans trop de casse!</p>";
					else
					{
						$mes.="<p>Vous atterrissez, mais l'état lamentable du sol vous fait perdre le contrôle de votre appareil qui s'écrase. 
						<br>Vous êtes grièvement blessé, gisant sur le sol aux côtés de votre compagnon d'infortune!
						<br>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
						$Avion_Perdu=true;
					}
				}
				else
				{
					$mes.="<p>Vous ne parvenez pas à maintenir votre avion en vol.<br>Vous êtes obligé de l'abandonner en sautant en parachute!</p>";
					$Avion_Perdu=true;
				}
			}
			else
			{
				$mes.="<p>Vous ne parvenez pas à maintenir votre avion en vol.<br>Vous êtes obligé de l'abandonner en sautant en parachute!</p>";
				$Avion_Perdu=true;
			}
		}		
		if($Avion_Perdu)
		{
			if($Zone ==6)
			{
				if($Slot5 ==17 or $Slot5 ==35)
					$mes.="<br>Votre gilet de sauvetage vous sauve de la noyade!";
				else
				{
					$mes.="<br>Sans gilet de sauvetage, vous êtes emporté par la mer jusqu'au rivage!";
					$CritH[4]=true;
				}
			}
			$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
			$Type_avion=GetData($Avion_db,"ID",$avion,"Type");
			if($Type_avion ==1 or $Type_avion ==4)
				$Reputc=floor(GetData("Pilote","ID",$PlayerID,"Reputation")/100)+10;
			else
				$Reputc=10;
			UpdateCarac($PlayerID,"Crashs_Jour",1);
			UpdateCarac($PlayerID,"Reputation",-$Reputc);
			UpdateCarac($PlayerID,"Moral",-10);
			UpdateData("Unit","Reputation",-$Reputc,"ID",$Unite,0,4);
			if($Avion_db =="Avions_Persos" or $Avion_db =="Avions_Sandbox")
				SetData($Avion_db,"Robustesse",0,"ID",$avion);
			if(!$NoIncidentJournal)
				AddEvent($Avion_db,34,$avion,$PlayerID,$Unite,$Cible);
		}
		else
		{
			if($Avion_db =="Avion")
				AddAvionToUnit($Unite,$avion);
			$img=Afficher_Image('images/avions/landing'.$avion_img.'.jpg','images/avions/decollage'.$avion_img.'.jpg','Atterrissage');
		}		
		//Blessure
		$blesse=0;
		if(!$CritH[4])
			$Blessure=GetBlessure($PlayerID,$Avion_db,$avion);
		else
			$Blessure=2;
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
				$Blessure_txt="<p>Vous gisez étendu sur le sol, mortellement blessé</p>";
				$Hard=0;
				$Malus_Moral=-100;
				AddEvent($Avion_db,9,$avion,$PlayerID,$Unite,$Cible);
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Escorte=0,Couverture=0,MIA='$Cible',Commando=0 WHERE ID='$PlayerID'");
				mysqli_close($con);
				AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
				$blesse=2;
				DoBlessure($PlayerID,10);
			break;
		}
		$mes.=$Blessure_txt;
		UpdateCarac($PlayerID,"Moral",$Malus_Moral);
		if($Equipage and $Endu_Eq and $Equipage_Nbr >1)
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
		//$Dist=GetDistance(0,$Cible,$Longitude,$Latitude);
		$Dist_Base=GetDistance(0,$Base,$Longitude,$Latitude);
		if($BonneEtoile)
			$luck_p=mt_rand(0,25);
		elseif($Slot10 ==71)
			$luck_p=mt_rand(0,20);
		elseif($Slot10 ==72)
			$luck_p=mt_rand(0,5);
		elseif($Slot10 ==34)
			$luck_p=mt_rand(0,5);
		else
			$luck_p=0;
		if($Mission_Type !=7 and $Mission_Type !=9 and $Mission <90 and $Mission_Type !=23 and $Dist_Base[0] >50 and $luck_p <5 and $blesse <2)
		{
			$intro.="<p>Vous vous retrouvez au beau milieu d'une zone contrôlée par l'ennemi.
			<br>Le temps de regagner vos lignes vous rend indisponible jusqu'à votre retour.</p>";
			$mes.="<br><form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Missions_Max=6,Missions_Jour=6,Escorte=0,Couverture=0,MIA='$Cible',Commando=0 WHERE ID='$PlayerID'");
			mysqli_close($con);
			AddEvent($Avion_db,35,$avion,$PlayerID,$Unite,$Cible);
			$_SESSION['Distance']=0;
		}
		else
		{
			$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
			$mes.="<p><b>FIN DE MISSION</b></p>";
			$menu.="<form action='promotion.php' method='post'>
				<input type='hidden' name='Blesse' value='".$blesse."'>
				<input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}
}
?>