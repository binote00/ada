<?php

function GetMalusFroid($alt, $Slot4=0, $Slot1=0, $Slot9=0)
{
	$Malus_Froid_Red=0;
	if($alt >6000 and $Slot4 !=27 and $Slot4 !=50 and $Slot4 !=51)
	{
		if($Slot4 ==30 or $Slot4 ==31 or $Slot4 ==32 or $Slot4 ==36 or $Slot4 ==37 or $Slot4 ==38 or $Slot4 ==39 or $Slot4 ==52)
			$Malus_Froid_Red+=50;
		if($Slot1 ==11)
			$Malus_Froid_Red+=25;
		if($Slot9 ==4 or $Slot9 ==54 or $Slot9 ==55)
			$Malus_Froid_Red+=25;
		$Malus_Froid_Red=1-((100-$Malus_Froid_Red)/100);
		if($Malus_Froid_Red >1)$Malus_Froid_Red=1;
		if($Malus_Froid_Red <0.25)
			$Malus_txt="<br>Le froid glacial qui règne à cette altitude vous tétanise!";
		elseif($Malus_Froid_Red <0.50)
			$Malus_txt="<br>Votre équipement atténue légèrement les effets du froid!";
		elseif($Malus_Froid_Red <0.75)
			$Malus_txt="<br>Votre équipement atténue les effets du froid!";
		elseif($Malus_Froid_Red <1)
			$Malus_txt="<br>Votre équipement atténue fortement les effets du froid!";
		else
			$Malus_txt="<br>Votre équipement vous protège parfaitement des effets du froid!";
	}
	return array($Malus_Froid_Red,$Malus_txt);
}

function CriticalHit($Avion_db, $avion, $PlayerID, $Mun_eni, $Engine_Nbr_Old=1, $pvp=false)
{
	$Essence=false;
	if($pvp)
	{
		$Pilote_db="Pilote_PVP";
		$Equipage_db="Equipage_PVP";
	}
	else
	{
		$Pilote_db="Pilote";
		$Equipage_db="Equipage";
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Equipage,S_Equipage_Nbr,S_Engine_Nbr,S_Blindage,Slot1,Sandbox FROM $Pilote_db WHERE ID='$PlayerID'");
	$result2=mysqli_query($con,"SELECT Type,Blindage,ArmePrincipale,ArmeSecondaire,Engine FROM $Avion_db WHERE ID='$avion'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Equipage=$data['Equipage'];
			$Equipage_Nbr=$data['S_Equipage_Nbr'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$S_Blindage=$data['S_Blindage'];
			$Sandbox=$data['Sandbox'];
			$Slot1=$data['Slot1'];
		}
		mysqli_free_result($result);
	}
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Type_avion=$data['Type'];
			$Blindage=$data['Blindage'];
			$ArmePrincipale=$data['ArmePrincipale'];
			$ArmeSecondaire=$data['ArmeSecondaire'];
			$Engine=$data['Engine'];
		}
		mysqli_free_result($result2);
	}
	if(!$Blindage and !$pvp)
	{
		$Blindage=$S_Blindage;
		if(!$Blindage)$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
	}
	$Arme1Avion=GetData("Armes","ID",$ArmePrincipale,"Nom");
	$Arme2Avion=GetData("Armes","ID",$ArmeSecondaire,"Nom");
	$Equipage_Nom=GetData($Equipage_db,"ID",$Equipage,"Nom");
	if(!$Equipage_Nom)$Equipage_Nom="Un membre d'équipage";
	$end_mission=false;
	$blesse=false;
	$random=mt_rand(1,40);
	switch($random)
	{
		case 1: case 2: case 3: case 4: case 5:
			if($ArmePrincipale !=0 and $ArmePrincipale !=5 and $ArmePrincipale !=25 and $ArmePrincipale !=26 and $ArmePrincipale !=27)
			{
				$mes="Les commandes de tir de votre ".$Arme1Avion." répondent difficilement";
				$Mun1=1;
				UpdateData($Pilote_db,"Stress_Arme1",50,"ID",$PlayerID);
			}
			else
				$mes="La rafale endommage les circuits de votre appareil, mais il tient bon!";
		break;
		case 6: case 7: case 8: case 9: case 10:
			if($ArmeSecondaire !=0 and $ArmeSecondaire !=5 and $ArmeSecondaire !=25 and $ArmeSecondaire !=26 and $ArmeSecondaire !=27)
			{
				$mes="Les commandes de tir de votre ".$Arme2Avion." répondent difficilement";
				$Mun2=1;
				UpdateData($Pilote_db,"Stress_Arme2",50,"ID",$PlayerID);
			}
			else
				$mes="La rafale endommage les circuits de votre appareil, mais il tient bon!";
		break;
		case 11: case 12: case 13: case 14: case 15:
			if($Equipage_Nbr >1)
			{
				if($Equipage and !$Sandbox)
				{
					UpdateCarac($Equipage,"Endurance",-1,$Equipage_db);
					$HP_Eq=GetData($Equipage_db,"ID",$Equipage,"Endurance");
					$Trait_e=GetData($Equipage_db,"ID",$Equipage,"Trait");
					if($HP_Eq <1 and $Trait_e !=3)
					{
						$mes="<b>".$Equipage_Nom."</b> est gravement touché. Il gît agonisant dans le cockpit";
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE $Equipage_db SET Moral=0,Courage=0 WHERE ID='$Equipage'");
						mysqli_close($con);
						UpdateData($Pilote_db,"S_Equipage_Nbr",-1,"ID",$PlayerID);
					}
					else
						$mes="<b>".$Equipage_Nom."</b> est blessé";
				}
				else
					$mes="<b>".$Equipage_Nom."</b> est blessé";
			}
			else
				$mes="Votre cockpit est criblé de balles, mais vous êtes miraculeusement sauf!";
		break;
		case 16: case 17: case 18:
			if($Equipage_Nbr >1)
			{
				if($Equipage and !$Sandbox)
				{
					$Trait_e=GetData($Equipage_db,"ID",$Equipage,"Trait");
					if($Trait_e !=3)
					{
						$mes="<b>".$Equipage_Nom."</b> est fauché par la rafale";
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE $Equipage_db SET Endurance=0,Moral=0,Courage=0 WHERE ID='$Equipage'");
						mysqli_close($con);
					}
					else
					{
						UpdateCarac($Equipage,"Endurance",-1,$Equipage_db);
						$mes="<b>".$Equipage_Nom."</b> est gravement touché. Il gît agonisant dans le cockpit";
					}
				}
				else
					$mes="<b>".$Equipage_Nom."</b> est fauché par la rafale";
				UpdateData($Pilote_db,"S_Equipage_Nbr",-1,"ID",$PlayerID);
			}
			elseif(!$Blindage)
			{
				if(($Slot1 ==10 or $Slot1 ==11 or $Slot1 ==81) and ($Mun_eni ==3 or $Mun_eni ==0))
					$mes="Votre casque protège d'une mort certaine";
				elseif(!$Sandbox)
				{
					if(!$pvp)
					{
						UpdateCarac($PlayerID,"Endurance",-1);
						DoBlessure($PlayerID,1);
					}
					$mes="Vous êtes blessé";
				}
				else
				{
					UpdateCarac($PlayerID,"Free",-1);
					$mes="Vous perdez un point As des AS suite à votre blessure";
				}
			}
			else
				$mes="Le blindage vous protège d'une mort certaine";
		break;
		case 19: case 20: case 21: case 22: case 23:
			if(!$Blindage)
			{
				if(($Slot1 ==10 or $Slot1 ==11 or $Slot1 ==81) and ($Mun_eni ==3 or $Mun_eni ==0))
					$mes="Votre casque protège d'une mort certaine";
				elseif(!$Sandbox)
				{
					if(!$pvp)
					{
						UpdateCarac($PlayerID,"Endurance",-1);
						DoBlessure($PlayerID,1);
					}
					$mes="Vous êtes blessé";
				}
				else
				{
					UpdateCarac($PlayerID,"Free",-1);
					$mes="Vous perdez un point As des AS suite à votre blessure";
				}
			}
			else
				$mes="Le blindage vous protège d'une mort certaine";
		break;
		case 24:
			if(!$Blindage)
			{
				if(!$pvp)
				{
					if(!$Sandbox)
					{
						UpdateCarac($PlayerID,"Endurance",-1);
						DoBlessure($PlayerID,10);
					}
					else
						UpdateCarac($PlayerID,"Free",-1);
				}
				$mes="Vous êtes grièvement blessé et ne pouvez continuer à piloter votre avion!<br>Le crash est inévitable";
				$end_mission=true;
				$blesse=1;
			}
			else
				$mes="Le blindage vous protège d'une mort certaine";
		break;
		case 25: case 26: case 27: case 28: case 29:
			$Engine_Type=GetData("Moteur","ID",$Engine,"Type");
			if($Engine_Type)
			{
				if($Engine_Nbr <2)
				{
					$mes="Une fumée noire s'échappe de votre moteur!";
					UpdateData($Pilote_db,"Stress_Moteur",100,"ID",$PlayerID);
				}
				else
				{
					$mes="Une fumée noire s'échappe d'un de vos moteurs en flammes!<br>Vous maintenez difficilement l'avion en vol";
					UpdateData($Pilote_db,"S_Engine_Nbr",-1,"ID",$PlayerID);
					UpdateData($Pilote_db,"Stress_Moteur",25,"ID",$PlayerID);
				}
			}
			else
			{
				$mes="Une fine fumée blanche s'échappe de votre moteur, mais il tient le coup";
				UpdateData($Pilote_db,"Stress_Moteur",10,"ID",$PlayerID);
			}
		break;
		case 30:
			if($Engine_Nbr <2)
			{
				$mes="Une fumée noire s'échappe de votre moteur en flammes!<br>Vous n'avez pas d'autre choix que de sauter en parachute";
				$end_mission=true;
				SetData($Pilote_db,"S_Engine_Nbr",0,"ID",$PlayerID);
				$_SESSION['Parachute']=true;
			}
			else
			{
				$mes="Une fumée noire s'échappe d'un de vos moteurs en flammes!<br>Vous maintenez difficilement l'avion en vol";
				UpdateData($Pilote_db,"S_Engine_Nbr",-1,"ID",$PlayerID);
				UpdateData($Pilote_db,"Stress_Moteur",25,"ID",$PlayerID);
			}
		break;
		case 31: case 32: case 33: case 34: case 35:
			if($Mun_eni ==3 or $Mun_eni ==5)
			{
				$Reservoir=GetData($Avion_db,"ID",$avion,"Reservoir");
				if(!$Reservoir)
				{
					$Essence=mt_rand(100,1000);
					$mes="Votre réservoir principal est touché et prend feu!";
				}
				else
				{
					$Essence=mt_rand(10,100);
					$mes="Votre réservoir principal est touché, mais ne prend pas feu!";
				}
			}
		break;
		case 36: case 37: case 38: case 39: case 40:
			$mes="Vos commandes répondent difficilement!";
			UpdateData($Pilote_db,"Stress_Commandes",5,"ID",$PlayerID);
			UpdateData($Pilote_db,"Stress_Train",5,"ID",$PlayerID);
		break;
	}
	if($mes)
		$mes="<p>".$mes."!</p>";
	return array($mes,$end_mission,$Mun1,$Mun2,$blesse,$Engine_Nbr,$Essence);
}

function IsEnrayage($ArmeAvion, $alt, $PlayerID=0, $StressArmePlayer=0, $pvp=false)
{
	$Enrayage=false;
	$Graisse=1;
	if($pvp)
		$Pilote_db="Pilote_PVP";
	else
		$Pilote_db="Pilote";
	if($PlayerID)
	{
		$con=dbconnecti();
		$StressArmePlayer=mysqli_real_escape_string($con,$StressArmePlayer);
		$result2=mysqli_query($con,"SELECT $StressArmePlayer,Unit,S_Graisse FROM $Pilote_db WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_NUM))
			{
				$Stress_Arme=$data[0];
				$Unite=$data[1];
				$Graisse=$data[2];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		if(!$Graisse and !$pvp)$Graisse=GetData("Unit","ID",$Unite,"U_Graisse");
	}
	if($Graisse)
		$malus_alt=$alt/1000;
	else
		$malus_alt=10-($alt/1000);
	$Enrayage_max=GetData("Armes","ID",$ArmeAvion,"Enrayage")+$malus_alt+$Stress_Arme;
	$enrayage_rnd=mt_rand(0,100);
	if($enrayage_rnd <$Enrayage_max)
		$Enrayage=true;
	return $Enrayage;
}

function SetDist_shoot($Pilot, $Pilot_eni, $Base=0)
{
	if(!$Base)$Base=4000;
	$Dist_shoot=$Base-(($Pilot-$Pilot_eni)*10);
	if($Dist_shoot <10)$Dist_shoot=mt_rand(10,150);
	return round($Dist_shoot);
}

function GetMalus_Range($Dist_shoot, $ArmeAvion_Range, $Angle_shoot, $Vitesse=0)
{
	//$Malus_Range=($Dist_shoot-($ArmeAvion_Range/5))/10+$Angle_shoot;
	$Malus_vitesse=$Vitesse/((181-$Angle_shoot)/10);
	$Malus_Range=(($Dist_shoot-$ArmeAvion_Range)/10)+($Angle_shoot*5)+$Malus_vitesse+($Dist_shoot/100);
	return $Malus_Range;
}

function SetAngle_shoot($Pilot, $Pilot_eni, $Angle_Base=0, $Angle_Max=90)
{
	$Angle_shoot=$Angle_Max-(($Pilot-$Pilot_eni)/10);
	if($Angle_shoot <$Angle_Base)
		$Angle_shoot=$Angle_Base;
	elseif($Angle_shoot >$Angle_Max)
		$Angle_shoot=$Angle_Max;
	return round($Angle_shoot);
}

function SetAlt($alt, $Plafond, $Plafond_eni, $Min, $Max, $c_gaz)
{
	$alt+=mt_rand($Min,$Max);
	if($alt >6000 and $c_gaz <60)$alt=mt_rand(5000,6000);
	if($alt >$Plafond)$alt=$Plafond;
	if($alt >$Plafond_eni)$alt=$Plafond_eni;
	return $alt;
}

Function GetShoot($Shoot,$ArmeAvion_nbr,$Bomb=false)
{
	if($Bomb)
		$tab=$Bomb;
	else
		$tab=1;
	if($Shoot >100)
		$ArmeAvion_nbr=$ArmeAvion_nbr;
	elseif($Shoot >90)
		$ArmeAvion_nbr -=$tab;
	elseif($Shoot >80)
		$ArmeAvion_nbr -=($tab*2);
	elseif($Shoot >70)
		$ArmeAvion_nbr -=($tab*3);
	elseif($Shoot >60)
		$ArmeAvion_nbr -=($tab*4);
	elseif($Shoot >50)
		$ArmeAvion_nbr -=($tab*5);
	elseif($Shoot >40)
		$ArmeAvion_nbr -=($tab*6);
	elseif($Shoot >30)
		$ArmeAvion_nbr -=($tab*7);
	elseif($Shoot >20)
		$ArmeAvion_nbr -=($tab*8);
	elseif($Shoot >10)
		$ArmeAvion_nbr -=($tab*9);
	else
		$ArmeAvion_nbr=1;
	if($ArmeAvion_nbr <1)
		$ArmeAvion_nbr=1;
	return $ArmeAvion_nbr;
}

Function Damage_Bonus($Avion_db, $avion, $avion_eni, $Arme1Avion, $Blindage_eni, $Avion_Mun=0, $Dist_shoot=100, $Arme=1)
{
	//Bonus Dégâts munitions spéciales
	$Bonus_Dg=0;
	if($Avion_db =="Avions_Persos" or $Avion_db =="Avions_Sandbox")
	{
		if($Arme ==2)
			$Munitions1=GetData($Avion_db,"ID",$avion,"Munitions2");
		else
			$Munitions1=GetData($Avion_db,"ID",$avion,"Munitions1");
	}
	else
		$Munitions1=$Avion_Mun;
	if($Munitions1 >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Calibre,Degats,Perf,Portee FROM Armes WHERE ID='$Arme1Avion'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$mun1_cal=$data['Calibre'];
				$degats=$data['Degats'];
				$Perf=$data['Perf'];
				$Portee=$data['Portee'];
			}
			mysqli_free_result($result);
		}
		if($Dist_shoot >$Portee)
			$degats/=2;
		elseif($Dist_shoot >$Portee/2)
			$degats*=0.75;
		switch($Munitions1)
		{
			case 1: case 6: case 7: //AP
				$max_perf=round($Dist_shoot/10);
				if($max_perf <1)$max_perf=1;
				$perf=mt_rand(0,$max_perf);
				if($perf <5)
					$Bonus_Dg=pow($Blindage_eni,2);
			break;
			case 2: //HE
				if($Blindage_eni ==0)
					$Bonus_Dg=$degats*0.2;
				else
					$Bonus_Dg=-$degats*0.1;
			break;
			case 3: //I
				if($Blindage_eni ==0)
				{
					if($Avion_db =="Regiment")
						$Bonus_Dg=$degats*0.5;
					else
					{
						$Reservoir=GetData("Avion","ID",$avion_eni,"Reservoir");
						if($Reservoir ==1)
							$Bonus_Dg=$degats*0.1;
						else
							$Bonus_Dg=$degats*0.25;
					}
				}
				else
					$Bonus_Dg=-$degats*0.1;
			break;
			case 4: //APHE
				if($Blindage_eni <=$mun1_cal)
				{
					$max_perf=round($Dist_shoot/10);
					if($max_perf <1)$max_perf=1;
					$perf=mt_rand(0,$max_perf);
					if($Blindage_eni ==0)
						$Bonus_Dg=$degats*0.1;
					elseif($perf <3)
						$Bonus_Dg=pow($Blindage_eni,2)+$degats*0.1;
				}
				else
					$Bonus_Dg=-$degats*0.1;
			break;
			case 5: //API
				$max_perf=round($Dist_shoot/10);
				if($max_perf <1)$max_perf=1;
				$perf=mt_rand(0,$max_perf);
				if($perf <3 and $Blindage_eni <=$mun1_cal)
				{
					$Reservoir=GetData("Avion","ID",$avion_eni,"Reservoir");
					if($Reservoir ==1)
						$Bonus_Dg=$degats*0.05;
					else
						$Bonus_Dg=$degats*0.15;
				}
				else
					$Bonus_Dg=-$degats*0.05;
			break;
			case 8: //HEAT
				if($Blindage_eni ==0)
					$Bonus_Dg=$degats*0.5;
				elseif($Dist_shoot <400)
					$Bonus_Dg=$degats*0.5;
				else
					$Bonus_Dg=-$degats*0.1;
			break;
			default:
				$Bonus_Dg=0;
			break;
		}
	}
	return round($Bonus_Dg);
}

function GetSpeedP($Avion_db, $avion, $Engine_Nbr, $gaz=100, $flaps=0)
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT VitesseP,Engine_Nbr FROM $Avion_db WHERE ID='$avion'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$VitesseP=$data['VitesseP'];
			$Engine_Nbr_Ori=$data['Engine_Nbr'];
		}
		mysqli_free_result($result);
	}
	$VitesseP-=($flaps*100);
	if($Engine_Nbr <$Engine_Nbr_Ori)
	{
		$Engine_Diff=$Engine_Nbr_Ori-$Engine_Nbr;
		$VitesseP/=$Engine_Diff;
	}
	$Speed=round($VitesseP*$gaz/100);
	if($Speed <0)$Speed=0;
	return $Speed;
}

function GetSpeedPi($VitesseP, $Engine_Nbr_Ori, $gaz=100, $flaps=0)
{
	$VitesseP-=($flaps*100);
	$Speed=round($VitesseP*$gaz/100);
	if($Speed <0)$Speed=0;
	return $Speed;
}

function GetSpeedA($Avion_db, $avion, $alt, $meteo, $Engine_Nbr, $moda=1, $malus_incident=1, $gaz=100, $flaps=0)
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT VitesseA,Engine,Engine_Nbr,Alt_ref FROM $Avion_db WHERE ID='$avion'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$VitesseA=$data['VitesseA']-($flaps*100);
			$Moteur=$data['Engine'];
			$Engine_Nbr_Ori=$data['Engine_Nbr'];
			$Arme2Avion=$data['ArmeSecondaire'];
			$alt_ref=$data['Alt_ref'];
		}
		mysqli_free_result($result);
	}
	$Compresseur=GetData("Moteur","ID",$Moteur,"Compresseur");
	if(!$alt_ref)$alt_ref=5000;
	if($alt >$alt_ref)
		$VitesseA=floor($VitesseA/($alt/$alt_ref));
	if($Engine_Nbr <$Engine_Nbr_Ori)
	{
		$Engine_Diff=$Engine_Nbr_Ori-$Engine_Nbr;
		$VitesseA/=$Engine_Diff;
	}
	if(!$moda)$moda=$VitesseA;
	if($meteo <-50)$moda+=0.2;
	$Speed=round($VitesseA/$moda*$malus_incident*$gaz/100);
	if($Speed <0)$Speed=0;
	return $Speed;
}

function GetMan($Avion_db, $avion, $alt, $HP, $moda=1, $malus_incident=1, $flaps=0)
{
	if($HP <1)
		$Man=1;
	else
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Robustesse,ManoeuvreB,ManoeuvreH FROM $Avion_db WHERE ID='$avion'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$HPmax=$data['Robustesse'];
				$ManoeuvreB=$data['ManoeuvreB'];
				$ManoeuvreH=$data['ManoeuvreH'];
			}
			mysqli_free_result($result);
		}
		if(!$moda)$moda=10;
		if($moda ==1 and $HP <9999)
		{
			if($HPmax >$HP)
				$moda=$HPmax/$HP;
		}
		if($alt <2000)
			$Man=$ManoeuvreB;
		elseif($alt <6000)
			$Man=($ManoeuvreB+$ManoeuvreH)/2;
		else
			$Man=$ManoeuvreH;
		$Man=($Man+($flaps*10))/$moda*$malus_incident;
	}
	return $Man;
}

function GetMano($ManoeuvreB, $ManoeuvreH, $HPmax, $HP, $alt, $moda=1, $malus_incident=1, $flaps=0)
{
	if($HP <1)
		$Man=1;
	else
	{
		if(!$moda)$moda=10;
		if($moda ==1 and $HP <9999)
		{
			if($HPmax >$HP)
				$moda=$HPmax/$HP;
		}
		if($alt <2000)
			$Man=$ManoeuvreB;
		elseif($alt <6000)
			$Man=($ManoeuvreB+$ManoeuvreH)/2;
		else
			$Man=$ManoeuvreH;
		$Man=($Man+($flaps*10))/$moda*$malus_incident;
	}
	return $Man;
}

function GetMani($Maniabilite, $HPmax, $HP, $moda=1, $malus_incident=1, $flaps=0)
{
	if($HP <1)
		$Mani=1;
	else
	{
		if($moda ==1 and $HP <9999)
		{
			if($HPmax >$HP)
				$moda=$HPmax/$HP;
		}
		elseif(!$moda)
			$moda=1;
		$Mani=($Maniabilite-($flaps*10))/$moda*$malus_incident;
	}
	return $Mani;
}

function GetStab($Avion_db, $avion, $HP, $moda=1, $malus_incident=1)
{
	if($HP <1)
		$Stab=1;
	else
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Robustesse,Stabilite FROM $Avion_db WHERE ID='$avion'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$HPmax=$data['Robustesse'];
				$Stabilite=$data['Stabilite'];
			}
			mysqli_free_result($result);
		}
		if($moda ==1 and $HP <9999)
		{
			if($HPmax >$HP)
				$moda=$HPmax/$HP;
		}
		if(!$moda)
			$Stab=0;
		else
			$Stab=$Stabilite/$moda*$malus_incident;
	}
	return $Stab;
}

function AddProbable($Avion_db, $Avion_loss, $Avion_win, $Joueur_win, $Unite_win, $Unite_loss, $Lieu, $Arme_win, $Pilote_loss, $Etat=0)
{
	//Etat 0=non confirmé, 1=endommagé, 2=collabo
	if($Avion_db =="Avions_Persos" or $Avion_db =="Avions_Sandbox")
		$Avion_win=GetData($Avion_db,"ID",$Avion_win,"ID_ref");
	$date=date('Y-m-d G:i');
	$query="INSERT INTO Chasse_Probable (Date, Avion_loss, Avion_win, Joueur_win, Unite_win, Unite_loss, Lieu, Arme_win, Pilote_loss, PVP)
	VALUES ('$date','$Avion_loss','$Avion_win','$Joueur_win','$Unite_win','$Unite_loss','$Lieu','$Arme_win','$Pilote_loss','$Etat')";
	$con=dbconnecti();
	/*$date=date('G:i');
	$date=GetData("Conf_Update","ID",2,"Date").$date;*/
	$ok=mysqli_query($con,$query);
	mysqli_close($con);
	if(!$ok)
	{
		$msg.="Erreur de mise à jour".mysqli_error($con);
		mail('binote@hotmail.com','Aube des Aigles: AddVProbable Error',$msg);
	}
	/*else
		$msg.="Votre victoire probable et ajoutée à votre tableau de chasse!";*/
	UpdateData("Unit","Reputation",1,"ID",$Unite_win,0,4);
	//return $msg;
}

function AddVictoire_atk($Avion_db, $Type, $Nom, $avion, $PlayerID, $Unite_win, $Cible, $Arme1Avion, $Pays, $DCA=0, $alt=0, $Nuit=0, $Degats=0, $Tues=1)
{
	if($Avion_db =="Avions_Persos" or $Avion_db =="Avions_Sandbox")
		$avion=GetData($Avion_db,"ID",$avion,"ID_ref");
	if(is_numeric($Nom))
	{
		$date=date('Y-m-d G:i');
		if($DCA ==1)
		{
			$query="INSERT INTO DCA (`Type`, Avion, Joueur, Unite, Lieu, Arme, Altitude, `Date`, Cible_id, Pays, Cycle, Degats)
			VALUES ('$Type','$avion','$PlayerID','$Unite_win','$Cible','$Arme1Avion','$alt','$date','$Nom','$Pays','$Nuit','$Degats')";
		}
		else
		{
			$query="INSERT INTO Attaque (`Type`, Avion, Joueur, Unite, Lieu, Arme, Altitude, `Date`, Cible_id, Pays, Cycle, Degats, Tues)
			VALUES ('$Type','$avion','$PlayerID','$Unite_win','$Cible','$Arme1Avion','$alt','$date','$Nom','$Pays','$Nuit','$Degats','$Tues')";
		}
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		if(!$ok)
		{
			$msg.="Erreur de mise à jour : Nom (Cible_id)=".$Nom." / PlayerID=".$PlayerID." / Date=".$date." ".mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddVictoire_atk Error Numeric',$msg);
		}
        mysqli_close($con);
		/*else
			$msg.="Votre victoire a été homologuée et ajoutée à votre tableau de chasse!";*/
	}
	else
		mail('binote@hotmail.com','Aube des Aigles: AddVictoire_atk Error Numeric','Cible_id n est pas un nombre : '.$Nom.' / DCA='.$DCA);
	/*else
	{
		$date=date('Y-m-d G:i');
		$query="INSERT INTO $table (Type, Nom, Avion, Joueur, Unite, Lieu, Arme, Date)
		VALUES ('$Type','$Nom','$avion','$PlayerID','$Unite_win','$Cible','$Arme1Avion','$date')";
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg=$msg."Erreur de mise à jour".mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddVictoire_atk Error',$msg);
		}
		/*else
			$msg.="Votre victoire a été homologuée et ajoutée à votre tableau de chasse!";
	}*/
	//return $msg;
}

function AddVictoire_Bomb($Avion_db, $Type, $Nom, $avion, $PlayerID, $Unite_win, $Cible, $Arme1Avion, $Cycle, $Pays, $alt)
{
	if($Avion_db =="Avions_Persos" or $Avion_db =="Avions_Sandbox")
		$avion=GetData($Avion_db,"ID",$avion,"ID_ref");
	$date=date('Y-m-d G:i');
	$query="INSERT INTO Bombardement (Type, Avion, Joueur, Unite, Lieu, Arme, Altitude, Date, Cycle, Cible_id, Pays)
	VALUES ('$Type','$avion','$PlayerID','$Unite_win','$Cible','$Arme1Avion','$alt','$date','$Cycle','$Nom','$Pays')";
	$con=dbconnecti();
	$ok=mysqli_query($con,$query);
	if(!$ok)
	{
		$msg.="Erreur de mise à jour".mysqli_error($con);
		mail('binote@hotmail.com','Aube des Aigles: AddVictoire_Bomb Error',$msg);
	}
	mysqli_close($con);
}

function AddPara($Avion_db, $avion, $PlayerID, $Unite_win, $Cible, $Paras, $Cycle)
{
	if($Avion_db =="Avions_Persos" or $Avion_db =="Avions_Sandbox")
		$avion=GetData($Avion_db,"ID",$avion,"ID_ref");
	$date=date('Y-m-d G:i');
	$query="INSERT INTO Parachutages (Joueur, Avion, Unite, Lieu, Paras, Date, Cycle)
	VALUES ('$PlayerID','$avion','$Unite_win','$Cible','$Paras','$date','$Cycle')";
	$con=dbconnecti();
	$ok=mysqli_query($con,$query);
	if(!$ok)
	{
		$msg.="Erreur de mise à jour".mysqli_error($con);
		mail('binote@hotmail.com','Aube des Aigles: AddVictoire_Bomb Error',$msg);
	}
	mysqli_close($con);
}

function GetBlessure($PlayerID, $Avion_db, $avion)
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Equipage,Slot11 FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Equipage=$data['Equipage'];
			$Trousse=$data['Slot11'];
		}
		mysqli_free_result($result);
	}
	if($Trousse ==28)$Trousse=10;
	if($Equipage)
		$Aid=floor(GetData("Equipage","ID",$Equipage,"Premiers_Soins")/10);
	$Blindage=GetData($Avion_db,"ID",$avion,"Blindage");
	$luck_max=10+$Blindage+$Aid+$Trousse;
	$luck=mt_rand(0,$luck_max);
	if($luck <2)
	{
		UpdateCarac($PlayerID,"Endurance",-1);
		$Endurance=GetData("Pilote","ID",$PlayerID,"Endurance");
		if($Endurance <1)
			$Etat=2; //Mort
		else
			$Etat=1; //Blesse
	}
	else
	{
		UpdateCarac($Equipage,"Premiers_Soins",1,"Equipage");
		$Etat=0; //Indemne
	}
	return $Etat;
}

function DoBlessure($PlayerID, $Intensite=1)
{
	$carac=mt_rand(1,7);
	switch($carac)
	{
		case 1:
			UpdateCarac($PlayerID,"Pilotage",-$Intensite);
		break;
		case 2:
			UpdateCarac($PlayerID,"Bombardement",-$Intensite);
		break;
		case 3:
			UpdateCarac($PlayerID,"Navigation",-$Intensite);
		break;
		case 4:
			UpdateCarac($PlayerID,"Tactique",-$Intensite);
		break;
		case 5:
			UpdateCarac($PlayerID,"Tir",-$Intensite);
		break;
		case 6:
			UpdateCarac($PlayerID,"Vue",-$Intensite);
		break;
		case 7:
			UpdateCarac($PlayerID,"Acrobatie",-$Intensite);
		break;
	}
}

function GetMalusReperer($Zone, $Cam=0)
{
	switch($Zone)
	{
		case 0: case 6: case 8:
			$Malus_Reperer=1;
		break;
		case 1: case 11:
			$Malus_Reperer=10;
		break;
		case 2: case 3: case 4: case 7:
			$Malus_Reperer=20;
		break;
		case 5: case 9:
			$Malus_Reperer=50;
		break;
		default:
			$Malus_Reperer=1;
		break;
	}
	if($Cam >0)
	{
		$Cam=log($Cam);
		$malus=$Malus_Reperer*$Cam;
	}
	else
		$malus=$Malus_Reperer;
	return $malus;
}

function AddAvionToUnit($Unite, $avion, $Nbr=1)
{
	//Avion ajouté à l'iventaire de l'unité
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE ID='$Unite'");
	mysqli_close($con);
	if($result)
	{
		$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
		if($data['Avion1'] ==$avion)
			UpdateData("Unit","Avion1_Nbr",$Nbr,"ID",$Unite);
		elseif($data['Avion2'] ==$avion)
			UpdateData("Unit","Avion2_Nbr",$Nbr,"ID",$Unite);
		elseif($data['Avion3'] ==$avion)
			UpdateData("Unit","Avion3_Nbr",$Nbr,"ID",$Unite);
	}
	else
		mail('binote@hotmail.com','Aube des Aigles: Erreur Select AddAvionToUnit',"Unite : ".$Unite." ; Avion : ".$avion);
}

function WoundPilotIA($Pilot)
{
	$rnd_skill=mt_rand(1,7);
	if($rnd_skill ==1)
		$Skillx="Vue";
	elseif($rnd_skill ==2)
		$Skillx="Navigation";
	elseif($rnd_skill ==3)
		$Skillx="Pilotage";
	elseif($rnd_skill ==4)
		$Skillx="Tactique";
	elseif($rnd_skill ==5)
		$Skillx="Tir";
	elseif($rnd_skill ==6)
		$Skillx="Acrobatie";
	elseif($rnd_skill ==7)
		$Skillx="Bombardement";
	UpdateCarac($Pilot,$Skillx,-1,"Pilote_IA");
}

function GetDCA($Pays_eni, $DefenseAA=2, $Mode=0) //Mode0=3 DCA, Mode1=DCA Gros, Mode2=DCA moyen, Mode3=DCA petit
{
	if($DefenseAA <2)
	{
		$gun=5;
		$hgun=5;
		if($Pays_eni ==1)
			$mg=35;
		elseif($Pays_eni ==2)
			$mg=37;
		elseif($Pays_eni ==4)
			$mg=282;
		elseif($Pays_eni ==6)
			$mg=24;
		elseif($Pays_eni ==7)
			$mg=17;
		elseif($Pays_eni ==8)
			$mg=141;
		elseif($Pays_eni ==9)
			$mg=216;
		else
			$mg=36;
	}
	elseif($DefenseAA <3)
	{
		$gun=5;
		$hgun=5;
		if($Pays_eni ==1)
			$mg=45;
		elseif($Pays_eni ==2)
			$mg=39;
		elseif($Pays_eni ==4)
			$mg=41;
		elseif($Pays_eni ==6)
			$mg=44;
		elseif($Pays_eni ==7)
			$mg=17;
		elseif($Pays_eni ==8)
			$mg=163;
		elseif($Pays_eni ==9)
			$mg=209;
		else
			$mg=39;
	}
	elseif($DefenseAA <4)
	{
		$gun=5;
		$hgun=5;
		if($Pays_eni ==1)
			$mg=48;
		elseif($Pays_eni ==2)
			$mg=40;
		elseif($Pays_eni ==4)
			$mg=47;
		elseif($Pays_eni ==6)
			$mg=46;
		elseif($Pays_eni ==7)
			$mg=220;
		elseif($Pays_eni ==8)
			$mg=163;
		elseif($Pays_eni ==9)
			$mg=205;
		else
			$mg=46;
	}
	elseif($DefenseAA <5)
	{
		$hgun=5;
		if($Pays_eni ==1)
		{
			$mg=48;
			$gun=23;
		}
		elseif($Pays_eni ==2)
		{
			$mg=40;
			$gun=14;
		}
		elseif($Pays_eni ==4)
		{
			$mg=47;
			$gun=49;
		}
		elseif($Pays_eni ==6)
		{
			$mg=46;
			$gun=76;
		}
		elseif($Pays_eni ==7)
		{
			$mg=220;
			$gun=273;
		}
		elseif($Pays_eni ==8)
		{
			$mg=186;
			$gun=152;
		}
		elseif($Pays_eni ==9)
		{
			$mg=205;
			$gun=206;
		}
		else
		{
			$mg=46;
			$gun=14;
		}
	}
	elseif($DefenseAA <7)
	{
		if($Pays_eni ==1)
		{
			$mg=48;
			$gun=23;
			$hgun=15;
		}
		elseif($Pays_eni ==2)
		{
			$mg=40;
			$gun=14;
			$hgun=56;
		}
		elseif($Pays_eni ==4)
		{
			$mg=47;
			$gun=49;
			$hgun=53;
		}
		elseif($Pays_eni ==6)
		{
			$mg=46;
			$gun=46;
			$hgun=55;
		}
		elseif($Pays_eni ==7)
		{
			$mg=220;
			$gun=273;
			$hgun=275;
		}
		elseif($Pays_eni ==8)
		{
			$mg=186;
			$gun=152;
			$hgun=184;
		}
		elseif($Pays_eni ==9)
		{
			$mg=205;
			$gun=206;
			$hgun=210;
		}
		else
		{
			$mg=46;
			$gun=14;
			$hgun=55;
		}
	}
	else
	{
		if($Pays_eni ==1)
		{
			$mg=48;
			$gun=23;
			$hgun=62;
		}
		elseif($Pays_eni ==2)
		{
			$mg=40;
			$gun=14;
			$hgun=61;
		}
		elseif($Pays_eni ==4)
		{
			$mg=47;
			$gun=49;
			$hgun=53;
		}
		elseif($Pays_eni ==6)
		{
			$mg=46;
			$gun=46;
			$hgun=60;
		}
		elseif($Pays_eni ==7)
		{
			$mg=220;
			$gun=273;
			$hgun=271;
		}
		elseif($Pays_eni ==8)
		{
			$mg=186;
			$gun=152;
			$hgun=153;
		}
		elseif($Pays_eni ==9)
		{
			$mg=205;
			$gun=206;
			$hgun=211;
		}
		else
		{
			$mg=46;
			$gun=14;
			$hgun=55;
		}
	}
	if(!$Mode)
		return array($hgun,$gun,$mg);
	elseif($Mode ==1)
		return $hgun;
	elseif($Mode ==2)
		return $gun;
	elseif($Mode ==3)
		return $mg;
}

function GetPil($PlayerID, $avion, $full=false)
{
	$Pilotage_xp=0;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Pilotage FROM XP_Avions WHERE PlayerID='$PlayerID' AND AvionID='$avion'");
	mysqli_close($con);
	if($result)
	{
		$Classement=mysqli_fetch_array($result,MYSQLI_ASSOC);
		if(!$full)
			$Pilotage_xp=$Classement['Pilotage']/10;
		else
			$Pilotage_xp=$Classement['Pilotage'];
	}
	return $Pilotage_xp;
}

function GetPilotage($Avion_db, $PlayerID, $avion, $Sandbox=0, $Pilotage=0)
{
	if($Sandbox ==1)
		$Pilotage_xp=GetData("Pilote","ID",$PlayerID,"Pilotage");
	elseif($Sandbox ==2)
		$Pilotage_xp=GetData("Pilote_PVP","ID",$PlayerID,"Pilotage");
	else
	{
		if($Avion_db =="Avions_Persos")
			$avion=GetData($Avion_db,"ID",$avion,"ID_ref");
		switch($avion)
		{
			case 1: case 2: case 3: case 24: case 60: case 61: case 117: case 181: case 317:
				$Pilotage_xp=GetPil($PlayerID,1) + GetPil($PlayerID,2) + GetPil($PlayerID,3) + GetPil($PlayerID,24) + GetPil($PlayerID,60) + GetPil($PlayerID,61) + GetPil($PlayerID,117) + GetPil($PlayerID,181) + GetPil($PlayerID,317);
			break;
			case 4: case 25: case 69: case 70: case 152: case 190: case 241: case 242: case 389: case 390: case 463: case 565: case 631:
				$Pilotage_xp=GetPil($PlayerID,4) + GetPil($PlayerID,25) + GetPil($PlayerID,69) + GetPil($PlayerID,70) + GetPil($PlayerID,152) + GetPil($PlayerID,190) + GetPil($PlayerID,241) + GetPil($PlayerID,242) + GetPil($PlayerID,389) + GetPil($PlayerID,390) + GetPil($PlayerID,463) + GetPil($PlayerID,565) + GetPil($PlayerID,631);
			break;
			case 5: case 6: case 36: case 82: case 292: case 308: case 369:
				$Pilotage_xp=GetPil($PlayerID,5) + GetPil($PlayerID,6) + GetPil($PlayerID,36) + GetPil($PlayerID,82) + GetPil($PlayerID,292) + GetPil($PlayerID,308) + GetPil($PlayerID,369);
			break;
			case 8: case 9: case 133:
				$Pilotage_xp=GetPil($PlayerID,8) + GetPil($PlayerID,9) + GetPil($PlayerID,133);
			break;
			case 10: case 262:
				$Pilotage_xp=GetPil($PlayerID,10) + GetPil($PlayerID,262);
			break;
			case 11: case 26: case 134: case 566:
				$Pilotage_xp=GetPil($PlayerID,11) + GetPil($PlayerID,26) + GetPil($PlayerID,134) + GetPil($PlayerID,566);
			break;
			case 12: case 13: case 31: case 168: case 169:
				$Pilotage_xp=GetPil($PlayerID,12) + GetPil($PlayerID,13) + GetPil($PlayerID,31) + GetPil($PlayerID,168) + GetPil($PlayerID,169);
			break;
			case 14: case 30: case 151: case 472: case 493: case 494: case 576: case 577:
				$Pilotage_xp=GetPil($PlayerID,14) + GetPil($PlayerID,30) + GetPil($PlayerID,151) + GetPil($PlayerID,472) + GetPil($PlayerID,493) + GetPil($PlayerID,494) + GetPil($PlayerID,576) + GetPil($PlayerID,577);
			break;
			case 15: case 29: case 166: case 167: case 346: case 496: case 613:
				$Pilotage_xp=GetPil($PlayerID,15) + GetPil($PlayerID,29) + GetPil($PlayerID,166) + GetPil($PlayerID,167) + GetPil($PlayerID,346) + GetPil($PlayerID,496) + GetPil($PlayerID,613);
			break;
			case 16: case 83: case 84: case 120: case 125: case 236: case 248: case 404: case 502: case 518: case 578: case 633:
				$Pilotage_xp=GetPil($PlayerID,16) + GetPil($PlayerID,83) + GetPil($PlayerID,84) + GetPil($PlayerID,120) + GetPil($PlayerID,125) + GetPil($PlayerID,236) + GetPil($PlayerID,248) + GetPil($PlayerID,404) + GetPil($PlayerID,502) + GetPil($PlayerID,518) + GetPil($PlayerID,578) + GetPil($PlayerID,633);
			break;
			case 17: case 27: case 28: case 89: case 179: case 197: case 198: case 228: case 267: case 348: case 443: case 471:
				$Pilotage_xp=GetPil($PlayerID,17) + GetPil($PlayerID,27) + GetPil($PlayerID,28) + GetPil($PlayerID,89) + GetPil($PlayerID,179) + GetPil($PlayerID,197) + GetPil($PlayerID,198)
				+ GetPil($PlayerID,228) + GetPil($PlayerID,267) + GetPil($PlayerID,348) + GetPil($PlayerID,443) + GetPil($PlayerID,471);
			break;
			case 19: case 81: case 121: case 122: case 342: case 630:
				$Pilotage_xp=GetPil($PlayerID,19) + GetPil($PlayerID,81) + GetPil($PlayerID,121) + GetPil($PlayerID,122) + GetPil($PlayerID,342) + GetPil($PlayerID,630);
			break;
			case 21: case 135: case 310:
				$Pilotage_xp=GetPil($PlayerID,21) + GetPil($PlayerID,135) + GetPil($PlayerID,310);
			break;
			case 23: case 35: case 43: case 78:
				$Pilotage_xp=GetPil($PlayerID,23) + GetPil($PlayerID,35) + GetPil($PlayerID,43) + GetPil($PlayerID,78);
			break;
			case 38: case 40: case 42:
				$Pilotage_xp=GetPil($PlayerID,38) + GetPil($PlayerID,40) + GetPil($PlayerID,42);
			break;
			case 39: case 268:
				$Pilotage_xp=GetPil($PlayerID,39) + GetPil($PlayerID,268);
			break;
			case 48: case 539:
				$Pilotage_xp=GetPil($PlayerID,48) + GetPil($PlayerID,539);
			break;
			case 55: case 159: case 230:
				$Pilotage_xp=GetPil($PlayerID,55) + GetPil($PlayerID,159) + GetPil($PlayerID,230);
			break;
			case 57: case 58: case 75: case 116: case 51: case 145:
				$Pilotage_xp=GetPil($PlayerID,57) + GetPil($PlayerID,58) + GetPil($PlayerID,75) + GetPil($PlayerID,116) + GetPil($PlayerID,51) + GetPil($PlayerID,145);
			break;
			case 62: case 140: case 286: case 394: case 395: case 396:
				$Pilotage_xp=GetPil($PlayerID,62) + GetPil($PlayerID,140) + GetPil($PlayerID,286) + GetPil($PlayerID,394) + GetPil($PlayerID,395) + GetPil($PlayerID,396);
			break;
			case 63: case 142:
				$Pilotage_xp=GetPil($PlayerID,63) + GetPil($PlayerID,142);
			break;
			case 64: case 95:
				$Pilotage_xp=GetPil($PlayerID,64) + GetPil($PlayerID,95);
			break;
			case 65: case 162:
				$Pilotage_xp=GetPil($PlayerID,65) + GetPil($PlayerID,162);
			break;
			case 67: case 231: case 232: case 249: case 343:
				$Pilotage_xp=GetPil($PlayerID,67) + GetPil($PlayerID,231) + GetPil($PlayerID,232) + GetPil($PlayerID,249) + GetPil($PlayerID,343);
			break;
			case 71: case 160:
				$Pilotage_xp=GetPil($PlayerID,71) + GetPil($PlayerID,160);
			break;
			case 72: case 261:
				$Pilotage_xp=GetPil($PlayerID,72) + GetPil($PlayerID,261);
			break;
			case 73: case 154: case 259: case 303: case 350: case 453: case 527: case 536: case 537:
				$Pilotage_xp=GetPil($PlayerID,73) + GetPil($PlayerID,154) + GetPil($PlayerID,259) + GetPil($PlayerID,303) + GetPil($PlayerID,350) + GetPil($PlayerID,453) + GetPil($PlayerID,527) + GetPil($PlayerID,536) + GetPil($PlayerID,537);
			break;
			case 76: case 77: case 54:
				$Pilotage_xp=GetPil($PlayerID,76) + GetPil($PlayerID,77) + GetPil($PlayerID,54);
			break;
			case 91: case 130: case 273: case 312: case 487: case 488: case 560: case 561: case 562: case 637:
				$Pilotage_xp=GetPil($PlayerID,91) + GetPil($PlayerID,130) + GetPil($PlayerID,273) + GetPil($PlayerID,312) + GetPil($PlayerID,487) + GetPil($PlayerID,488) + GetPil($PlayerID,560) + GetPil($PlayerID,561) + GetPil($PlayerID,562) + GetPil($PlayerID,637);
			break;
			case 92: case 93: case 275: case 339: case 340: case 397: case 498: case 517:
				$Pilotage_xp=GetPil($PlayerID,92) + GetPil($PlayerID,93) + GetPil($PlayerID,275) + GetPil($PlayerID,339) + GetPil($PlayerID,340) + GetPil($PlayerID,397) + GetPil($PlayerID,498) + GetPil($PlayerID,517);
			break;
			case 97: case 227: case 431:
				$Pilotage_xp=GetPil($PlayerID,97) + GetPil($PlayerID,227) + GetPil($PlayerID,431);
			break;
			case 100: case 170:
				$Pilotage_xp=GetPil($PlayerID,100) + GetPil($PlayerID,170);
			break;
			case 103: case 239: case 240:
				$Pilotage_xp=GetPil($PlayerID,103) + GetPil($PlayerID,239) + GetPil($PlayerID,240);
			break;
			case 105: case 161: case 277:
				$Pilotage_xp=GetPil($PlayerID,105) + GetPil($PlayerID,161) + GetPil($PlayerID,277);
			break;
			case 109: case 171: case 192: case 387: case 388: case 528:
				$Pilotage_xp=GetPil($PlayerID,109) + GetPil($PlayerID,171) + GetPil($PlayerID,192) + GetPil($PlayerID,387) + GetPil($PlayerID,388) + GetPil($PlayerID,528);
			break;
			case 115: case 233:
				$Pilotage_xp=GetPil($PlayerID,115) + GetPil($PlayerID,233);
			break;
			case 118: case 119: case 383: case 470:
				$Pilotage_xp=GetPil($PlayerID,118) + GetPil($PlayerID,119) + GetPil($PlayerID,383) + GetPil($PlayerID,470);
			break;
			case 123: case 191:
				$Pilotage_xp=GetPil($PlayerID,123) + GetPil($PlayerID,191);
			break;
			case 124: case 258:
				$Pilotage_xp=GetPil($PlayerID,124) + GetPil($PlayerID,258);
			break;
			case 129: case 473:
				$Pilotage_xp=GetPil($PlayerID,129) + GetPil($PlayerID,473);
			break;
			case 136: case 137: case 138: case 271: case 272: case 441: case 442:
				$Pilotage_xp=GetPil($PlayerID,136) + GetPil($PlayerID,137) + GetPil($PlayerID,138) + GetPil($PlayerID,271) + GetPil($PlayerID,272) + GetPil($PlayerID,441) + GetPil($PlayerID,442);
			break;
			case 139: case 237: case 344: case 384: case 444: case 445: case 446: case 500:
				$Pilotage_xp=GetPil($PlayerID,139) + GetPil($PlayerID,237) + GetPil($PlayerID,344) + GetPil($PlayerID,384) + GetPil($PlayerID,444) + GetPil($PlayerID,445) + GetPil($PlayerID,446) + GetPil($PlayerID,500);
			break;
			case 141: case 256: case 436: case 582:
				$Pilotage_xp=GetPil($PlayerID,141) + GetPil($PlayerID,256) + GetPil($PlayerID,436) + GetPil($PlayerID,582);
			break;
			case 144: case 366:
				$Pilotage_xp=GetPil($PlayerID,144) + GetPil($PlayerID,366);
			break;
			case 148: case 293:
				$Pilotage_xp=GetPil($PlayerID,148) + GetPil($PlayerID,293);
			break;
			case 153: case 189 : case 250: case 251: case 302: case 304: case 305: case 306: case 307: case 334: case 335: case 413: case 432: case 477: case 478: case 481: case 482: case 483: case 503:
				$Pilotage_xp=GetPil($PlayerID,153) + GetPil($PlayerID,189) + GetPil($PlayerID,250) + GetPil($PlayerID,251)
				+ GetPil($PlayerID,302) + GetPil($PlayerID,304) + GetPil($PlayerID,305) + GetPil($PlayerID,306) + GetPil($PlayerID,307) + GetPil($PlayerID,334) + GetPil($PlayerID,335)
				+ GetPil($PlayerID,413) + GetPil($PlayerID,432) + GetPil($PlayerID,477) + GetPil($PlayerID,478) + GetPil($PlayerID,481) + GetPil($PlayerID,482) + GetPil($PlayerID,483) + GetPil($PlayerID,503);
			break;
			case 156: case 157:
				$Pilotage_xp=GetPil($PlayerID,156) + GetPil($PlayerID,157);
			break;
			case 158: case 370: case 371: case 414: case 423: case 448: case 468: case 469: case 534: case 535: case 543:
				$Pilotage_xp=GetPil($PlayerID,158) + GetPil($PlayerID,370) + GetPil($PlayerID,371) + GetPil($PlayerID,414) + GetPil($PlayerID,423) + GetPil($PlayerID,448) + GetPil($PlayerID,468) + GetPil($PlayerID,469) + GetPil($PlayerID,534) + GetPil($PlayerID,535) + GetPil($PlayerID,543);
			break;
			case 182: case 183:
				$Pilotage_xp=GetPil($PlayerID,182) + GetPil($PlayerID,183);
			break;
			case 187: case 349:
				$Pilotage_xp=GetPil($PlayerID,187) + GetPil($PlayerID,349);
			break;
			case 188: case 254: case 337: case 405: case 495: case 529: case 616: case 643:
				$Pilotage_xp=GetPil($PlayerID,188) + GetPil($PlayerID,254) + GetPil($PlayerID,337) + GetPil($PlayerID,405) + GetPil($PlayerID,495) + GetPil($PlayerID,529) + GetPil($PlayerID,616) + GetPil($PlayerID,643);
			break;
			case 193: case 234: case 235:
				$Pilotage_xp=GetPil($PlayerID,193) + GetPil($PlayerID,234) + GetPil($PlayerID,235);
			break;
			case 201: case 202: case 322:
				$Pilotage_xp=GetPil($PlayerID,201) + GetPil($PlayerID,202) + GetPil($PlayerID,322);
			break;
			case 203: case 204: case 205: case 206: case 207:
				$Pilotage_xp=GetPil($PlayerID,203) + GetPil($PlayerID,204) + GetPil($PlayerID,205) + GetPil($PlayerID,206) + GetPil($PlayerID,207);
			break;
			case 208: case 209: case 217: case 290:
				$Pilotage_xp=GetPil($PlayerID,208) + GetPil($PlayerID,209) + GetPil($PlayerID,217) + GetPil($PlayerID,290);
			break;
			case 210: case 211: case 212:
				$Pilotage_xp=GetPil($PlayerID,210) + GetPil($PlayerID,211) + GetPil($PlayerID,212);
			break;
			case 214: case 255:
				$Pilotage_xp=GetPil($PlayerID,214) + GetPil($PlayerID,255);
			break;
			case 215: case 216: case 294:
				$Pilotage_xp=GetPil($PlayerID,215) + GetPil($PlayerID,216) + GetPil($PlayerID,294);
			break;
			case 218: case 376: case 410: case 455: case 456:
				$Pilotage_xp=GetPil($PlayerID,218) + GetPil($PlayerID,376) + GetPil($PlayerID,410) + GetPil($PlayerID,455) + GetPil($PlayerID,456);
			break;
			case 225: case 252: case 313: case 365: case 399: case 406: case 530:
				$Pilotage_xp=GetPil($PlayerID,225) + GetPil($PlayerID,252) + GetPil($PlayerID,313) + GetPil($PlayerID,365) + GetPil($PlayerID,399) + GetPil($PlayerID,406) + GetPil($PlayerID,530);
			break;
			case 226: case 281: case 282: case 283: case 284: case 426: case 427:
				$Pilotage_xp=GetPil($PlayerID,226) + GetPil($PlayerID,281) + GetPil($PlayerID,282) + GetPil($PlayerID,283) + GetPil($PlayerID,284) + GetPil($PlayerID,426) + GetPil($PlayerID,427);
			break;
			case 229: case 253: case 291: case 314: case 315:
				$Pilotage_xp=GetPil($PlayerID,229) + GetPil($PlayerID,253) + GetPil($PlayerID,291) + GetPil($PlayerID,314) + GetPil($PlayerID,315);
			break;
			case 247: case 287: case 372: case 419: case 522: case 547: case 584: case 585: case 615:
				$Pilotage_xp=GetPil($PlayerID,247) + GetPil($PlayerID,287) + GetPil($PlayerID,372) + GetPil($PlayerID,419) + GetPil($PlayerID,522) + GetPil($PlayerID,547) + GetPil($PlayerID,584) + GetPil($PlayerID,585) + GetPil($PlayerID,615);
			break;
			case 257: case 326: case 373: case 416: case 417: case 418: case 484:
				$Pilotage_xp=GetPil($PlayerID,257) + GetPil($PlayerID,326) + GetPil($PlayerID,373) + GetPil($PlayerID,416) + GetPil($PlayerID,417) + GetPil($PlayerID,418) + GetPil($PlayerID,484);
			break;
			case 276: case 447:
				$Pilotage_xp=GetPil($PlayerID,276) + GetPil($PlayerID,447);
			break;
			case 278: case 279:
				$Pilotage_xp=GetPil($PlayerID,278) + GetPil($PlayerID,279);
			break;
			case 285: case 452:
				$Pilotage_xp=GetPil($PlayerID,285) + GetPil($PlayerID,452);
			break;
			case 297: case 298:
				$Pilotage_xp=GetPil($PlayerID,297) + GetPil($PlayerID,298);
			break;
			case 300: case 583:
				$Pilotage_xp=GetPil($PlayerID,300) + GetPil($PlayerID,583);
			break;
			case 309: case 311: case 378: case 559:
				$Pilotage_xp=GetPil($PlayerID,309) + GetPil($PlayerID,311) + GetPil($PlayerID,378) + GetPil($PlayerID,559);
			break;
			case 329: case 400: case 532:
				$Pilotage_xp=GetPil($PlayerID,329) + GetPil($PlayerID,400) + GetPil($PlayerID,532);
			break;
			case 330: case 435:
				$Pilotage_xp=GetPil($PlayerID,330) + GetPil($PlayerID,435);
			break;
			case 333: case 380: case 415:
				$Pilotage_xp=GetPil($PlayerID,333) + GetPil($PlayerID,380) + GetPil($PlayerID,415);
			break;
			case 336: case 505: case 586: case 587: case 588: case 594: case 595: case 596:
				$Pilotage_xp=GetPil($PlayerID,336) + GetPil($PlayerID,505) + GetPil($PlayerID,586) + GetPil($PlayerID,587) + GetPil($PlayerID,588) + GetPil($PlayerID,594) + GetPil($PlayerID,595) + GetPil($PlayerID,596);
			break;
			case 341: case 393: case 429: case 508: case 544: case 545:
				$Pilotage_xp=GetPil($PlayerID,341) + GetPil($PlayerID,393) + GetPil($PlayerID,429) + GetPil($PlayerID,508) + GetPil($PlayerID,544) + GetPil($PlayerID,545);
			break;
			case 350: case 412: case 507: case 511:
				$Pilotage_xp=GetPil($PlayerID,350) + GetPil($PlayerID,412) + GetPil($PlayerID,507) + GetPil($PlayerID,511);
			break;
			case 351: case 352: case 353: case 514: case 592: case 593:
				$Pilotage_xp=GetPil($PlayerID,351) + GetPil($PlayerID,352) + GetPil($PlayerID,353) + GetPil($PlayerID,514) + GetPil($PlayerID,592) + GetPil($PlayerID,593);
			break;
			case 356: case 407:
				$Pilotage_xp=GetPil($PlayerID,356) + GetPil($PlayerID,407);
			break;
			case 357: case 450:
				$Pilotage_xp=GetPil($PlayerID,357) + GetPil($PlayerID,450);
			break;
			case 358: case 504:
				$Pilotage_xp=GetPil($PlayerID,358) + GetPil($PlayerID,504);
			break;
			case 361: case 362: case 550:
				$Pilotage_xp=GetPil($PlayerID,361) + GetPil($PlayerID,362) + GetPil($PlayerID,550);
			break;
			case 363: case 533:
				$Pilotage_xp=GetPil($PlayerID,363) + GetPil($PlayerID,533);
			break;
			case 374: case 379: case 490: case 513: case 628:
				$Pilotage_xp=GetPil($PlayerID,374) + GetPil($PlayerID,379) + GetPil($PlayerID,490) + GetPil($PlayerID,513) + GetPil($PlayerID,628);
			break;
			case 381: case 411:
				$Pilotage_xp=GetPil($PlayerID,381) + GetPil($PlayerID,411);
			break;
			case 382: case 409: case 430: case 465: case 475: case 476: case 491: case 509: case 542:
				$Pilotage_xp=GetPil($PlayerID,382) + GetPil($PlayerID,409) + GetPil($PlayerID,430) + GetPil($PlayerID,465) + GetPil($PlayerID,475) + GetPil($PlayerID,476) + GetPil($PlayerID,491) + GetPil($PlayerID,509)+ GetPil($PlayerID,542);
			break;
			case 403: case 458: case 459:
				$Pilotage_xp=GetPil($PlayerID,403) + GetPil($PlayerID,458) + GetPil($PlayerID,459);
			break;
			case 408: case 457: case 510:
				$Pilotage_xp=GetPil($PlayerID,408) + GetPil($PlayerID,457) + GetPil($PlayerID,510);
			break;
			case 420: case 512:
				$Pilotage_xp=GetPil($PlayerID,420) + GetPil($PlayerID,512);
			break;
			case 421: case 551: case 571:
				$Pilotage_xp=GetPil($PlayerID,421) + GetPil($PlayerID,551) + GetPil($PlayerID,571);
			break;
			case 422: case 424: case 425:
				$Pilotage_xp=GetPil($PlayerID,422) + GetPil($PlayerID,424) + GetPil($PlayerID,425);
			break;
			case 433: case 434:
				$Pilotage_xp=GetPil($PlayerID,433) + GetPil($PlayerID,434);
			break;
			case 439: case 479: case 480: case 581:
				$Pilotage_xp=GetPil($PlayerID,439) + GetPil($PlayerID,479) + GetPil($PlayerID,480) + GetPil($PlayerID,581);
			break;
			case 451: case 516: case 610:
				$Pilotage_xp=GetPil($PlayerID,451) + GetPil($PlayerID,516) + GetPil($PlayerID,610);
			break;
			case 466: case 575: case 627:
				$Pilotage_xp=GetPil($PlayerID,466) + GetPil($PlayerID,575) + GetPil($PlayerID,627);
			break;
			case 467: case 574:
				$Pilotage_xp=GetPil($PlayerID,467) + GetPil($PlayerID,574);
			break;
			case 501: case 589: case 590: case 591:
				$Pilotage_xp=GetPil($PlayerID,501) + GetPil($PlayerID,589) + GetPil($PlayerID,590) + GetPil($PlayerID,591);
			break;
			case 521: case 622: case 623:
				$Pilotage_xp=GetPil($PlayerID,521) + GetPil($PlayerID,622) + GetPil($PlayerID,623);
			break;
			case 549: case 625: case 626: case 641:
				$Pilotage_xp=GetPil($PlayerID,549) + GetPil($PlayerID,625) + GetPil($PlayerID,626) + GetPil($PlayerID,641);
			break;
			case 556: case 557:
				$Pilotage_xp=GetPil($PlayerID,556) + GetPil($PlayerID,557);
			break;
			case 573: case 609:
				$Pilotage_xp=GetPil($PlayerID,573) + GetPil($PlayerID,609);
			break;
			case 617: case 618:
				$Pilotage_xp=GetPil($PlayerID,617) + GetPil($PlayerID,618);
			break;
			default:
				$Pilotage_xp=GetPil($PlayerID,$avion);
			break;
		}
		if(!$Pilotage)$Pilotage=GetData("Pilote","ID",$PlayerID,"Pilotage");
		$Pilotage+=$Pilotage_xp;
		if($Pilotage >175)$Pilotage=175;
	}
	return $Pilotage;
}