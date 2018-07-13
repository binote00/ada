<?php
/*function Random_Escort_Eni($Pays,$Longitude=0,$Latitude=0)
{
	$Lat_base_min=$Latitude-1.00;
	$Lat_base_max=$Latitude+1.00;
	$Long_base_min=$Longitude-2.00;
	$Long_base_max=$Longitude+2.00;
	$Faction=GetData("Pays","ID",$Pays,"Faction");
	$query2="SELECT Unit.ID,Unit.Avion1,Unit.Avion2,Unit.Avion3,Lieu.Longitude,Lieu.Latitude FROM Unit,Lieu,Pays 
	WHERE Lieu.ID=Unit.Base AND Pays.Pays_ID=Unit.Pays AND Unit.Etat=1 AND Unit.Type IN (1,4) AND Pays.Faction='$Faction' 
	AND (Lieu.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (Lieu.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND Lieu.QualitePiste >0
	ORDER BY RAND()";
	$con=dbconnecti();
	$result2=mysqli_query($con,$query2);
	mysqli_close($con);
	if($result2)
	{
		while($data_unit=mysqli_fetch_array($result2))
		{
			$Unit=$data_unit['ID'];
			$Avion1=$data_unit['Avion1'];
			$Avion2=$data_unit['Avion2'];
			$Avion3=$data_unit['Avion3'];
			if($Longitude and $Latitude)
			{
				$Distance_Max=GetDistance(0,0,$Longitude,$Latitude,$data_unit['Longitude'],$data_unit['Latitude']);
				$autonomie_avion1=GetData("Avion","ID",$Avion1,"Autonomie")/3;
				$autonomie_avion2=GetData("Avion","ID",$Avion2,"Autonomie")/3;
				$autonomie_avion3=GetData("Avion","ID",$Avion3,"Autonomie")/3;
				if($Distance_Max[0] <=$autonomie_avion1)
				{
					$Avion=$Avion1;
					break;
				}
				elseif($Distance_Max[0] <=$autonomie_avion2)
				{
					$Avion=$Avion2;
					break;
				}
				elseif($Distance_Max[0] <=$autonomie_avion3)
				{
					$Avion=$Avion3;
					break;
				}
			}
			else
			{
				$Avion_rand=mt_rand(1,3);
				switch($Avion_rand)
				{
					case 1:
						$Avion=$Avion1;
					break;
					case 2:
						$Avion=$Avion2;
					break;
					case 3:
						$Avion=$Avion3;
					break;
				}
				break;
			}
		}
	}
	else
		mail('binote@hotmail.com','Aube des Aigles: Random_Escort_Eni',"Erreur de Sélection".mysqli_error($con));
	return array($Avion,$Unit);
}*/

/*function Random_Pilot($PlayerID, $Unit_eni, $Type_avioneni, $Bonus=0, $Sandbox=0)
{
	if($Sandbox)$Bonus=0;
	if($Cible >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT DISTINCT ID FROM Pilote_IA WHERE Cible='$Cible' AND Unit='$Unit_eni' AND Actif='1' ORDER BY RAND() LIMIT 1");
		mysqli_close($con);
		if($result)
		{
			$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$Pilote_eni=$data['ID'];
			mysqli_free_result($result);
		}
	}
	if(!$Pilote_eni)
	{
		if($Bonus ==-1)
		{
			$lucky_pil=mt_rand(1,9);
			switch($lucky_pil)
			{
				case 1:
					$Pilote_eni=4;
				break;
				case 2:
					$Pilote_eni=4;
				break;
				case 3:
					$Pilote_eni=4;
				break;
				case 4:
					$Pilote_eni=147;
				break;
				case 5:
					$Pilote_eni=147;
				break;
				case 6:
					$Pilote_eni=147;
				break;
				case 7:
					$Pilote_eni=148;
				break;
				case 8:
					$Pilote_eni=149;
				break;
				case 9:
					$Pilote_eni=150;
				break;
			}
		}
		else
		{
			if($Cible >0)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT DISTINCT ID FROM Pilote_IA WHERE Cible='$Cible' AND Unit='$Unit_eni' AND Actif='1' ORDER BY RAND() LIMIT 1");
				mysqli_close($con);
				if($result)
				{
					$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
					$Pilote_eni=$data['ID'];
					mysqli_free_result($result);
				}
			}
			elseif(!$Pilote_eni)
			{
				$defaut=false;
				$chk_pl=mt_rand(0,10);
				if($chk_pl <1)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT DISTINCT ID FROM Pilote_IA WHERE Unit='$Unit_eni' AND Actif='1' ORDER BY RAND() LIMIT 1");
					mysqli_close($con);
					if($result)
					{
						$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
						$Pilote_eni=$data['ID'];
						mysqli_free_result($result);
					}
					else
						$defaut=true;
					if($Pilote_eni =="" or $Pilote_eni ==0)
						$defaut=true;
				}
				else
					$defaut=true;
				//$Pilote_eni=rand(1,146);
				//$Unit_Pilote_eni=GetData("Pilote_IA","ID",$Pilote_eni,"Unit");
				//if($Unit_Pilote_eni != $Unit_eni)
				//{
				if($defaut)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Unit,Pilotage,Tactique,Vue,Tir,Reputation,Victoires FROM Pilote WHERE ID='$PlayerID'");
					mysqli_close($con);
					if($result)
					{
						$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
						$Unit=$data['Unit'];
						$Reput=$data['Reputation'];
						if($Sandbox)
							$Vict=$data['Pilotage'] + $data['Tactique'] + $data['Vue'] + $data['Tir'];
						else
							$Vict=$data['Pilotage'] + $data['Tactique'] + $data['Vue'] + $data['Tir'] + $data['Victoires'];
						mysqli_free_result($result);
					}
					$Reput_unit=GetData("Unit","ID",$Unit,"Reputation")/1000;
					$Reput_unit_eni=GetData("Unit","ID",$Unit_eni,"Reputation")/100;
					$Pilotage=($Reput/10)+$Reput_unit+$Reput_unit_eni+$Vict+$Bonus;
					unset($data);
					
					if($Type_avioneni >1 or $Pilotage >10000)
						$lucky_pil=mt_rand(1,10);
					else
						$lucky_pil=mt_rand(1,9);
					
					if($Pilotage >50000)
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=147;
							break;
							case 3:
								$Pilote_eni=148;
							break;
							case 4:
								$Pilote_eni=149;
							break;
							case 5:
								$Pilote_eni=150;
							break;
							case 6:
								$Pilote_eni=150;
							break;
							case 7:
								$Pilote_eni=460;
							break;
							case 8:
								$Pilote_eni=460;
							break;
							case 9:
								$Pilote_eni=460;
							break;
							case 10:
								$Pilote_eni=460;
							break;
						}
					}
					elseif($Pilotage >35000)
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=147;
							break;
							case 3:
								$Pilote_eni=148;
							break;
							case 4:
								$Pilote_eni=149;
							break;
							case 5:
								$Pilote_eni=150;
							break;
							case 6:
								$Pilote_eni=150;
							break;
							case 7:
								$Pilote_eni=150;
							break;
							case 8:
								$Pilote_eni=460;
							break;
							case 9:
								$Pilote_eni=460;
							break;
							case 10:
								$Pilote_eni=460;
							break;
						}
					}
					elseif($Pilotage >20000)
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=147;
							break;
							case 3:
								$Pilote_eni=148;
							break;
							case 4:
								$Pilote_eni=149;
							break;
							case 5:
								$Pilote_eni=149;
							break;
							case 6:
								$Pilote_eni=150;
							break;
							case 7:
								$Pilote_eni=150;
							break;
							case 8:
								$Pilote_eni=150;
							break;
							case 9:
								$Pilote_eni=460;
							break;
							case 10:
								$Pilote_eni=460;
							break;
						}
					}
					elseif($Pilotage >10000) //OK
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=147;
							break;
							case 3:
								$Pilote_eni=148;
							break;
							case 4:
								$Pilote_eni=148;
							break;
							case 5:
								$Pilote_eni=149;
							break;
							case 6:
								$Pilote_eni=149;
							break;
							case 7:
								$Pilote_eni=149;
							break;
							case 8:
								$Pilote_eni=150;
							break;
							case 9:
								$Pilote_eni=150;
							break;
							case 10:
								$Pilote_eni=460;
							break;
						}
					}
					elseif($Pilotage >5000) //OK
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=147;
							break;
							case 3:
								$Pilote_eni=148;
							break;
							case 4:
								$Pilote_eni=148;
							break;
							case 5:
								$Pilote_eni=148;
							break;
							case 6:
								$Pilote_eni=148;
							break;
							case 7:
								$Pilote_eni=149;
							break;
							case 8:
								$Pilote_eni=149;
							break;
							case 9:
								$Pilote_eni=150;
							break;
							case 10:
								$Pilote_eni=460;
							break;
						}
					}
					elseif($Pilotage >1000) //OK
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=147;
							break;
							case 3:
								$Pilote_eni=147;
							break;
							case 4:
								$Pilote_eni=147;
							break;
							case 5:
								$Pilote_eni=147;
							break;
							case 6:
								$Pilote_eni=148;
							break;
							case 7:
								$Pilote_eni=148;
							break;
							case 8:
								$Pilote_eni=149;
							break;
							case 9:
								$Pilote_eni=150;
							break;
							case 10:
								$Pilote_eni=460;
							break;
						}
					}
					elseif($Pilotage >750)
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=4;
							break;
							case 3:
								$Pilote_eni=147;
							break;
							case 4:
								$Pilote_eni=147;
							break;
							case 5:
								$Pilote_eni=147;
							break;
							case 6:
								$Pilote_eni=147;
							break;
							case 7:
								$Pilote_eni=148;
							break;
							case 8:
								$Pilote_eni=149;
							break;
							case 9:
								$Pilote_eni=150;
							break;
							case 10:
								$Pilote_eni=460;
							break;
						}
					}
					elseif($Pilotage > 500)
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=4;
							break;
							case 3:
								$Pilote_eni=4;
							break;
							case 4:
								$Pilote_eni=147;
							break;
							case 5:
								$Pilote_eni=147;
							break;
							case 6:
								$Pilote_eni=147;
							break;
							case 7:
								$Pilote_eni=148;
							break;
							case 8:
								$Pilote_eni=149;
							break;
							case 9:
								$Pilote_eni=150;
							break;
							case 10:
								$Pilote_eni=460;
							break;
						}
					}
					else
					{
						switch($lucky_pil)
						{
							case 1:
								$Pilote_eni=4;
							break;
							case 2:
								$Pilote_eni=4;
							break;
							case 3:
								$Pilote_eni=4;
							break;
							case 4:
								$Pilote_eni=4;
							break;
							case 5:
								$Pilote_eni=4;
							break;
							case 6:
								$Pilote_eni=147;
							break;
							case 7:
								$Pilote_eni=147;
							break;
							case 8:
								$Pilote_eni=148;
							break;
							case 9:
								$Pilote_eni=149;
							break;
							case 10:
								$Pilote_eni=150;
							break;
						}
					}		
				}
			}
		}
	}
	//echo "".$Pilote_eni." : ".GetData("Pilote_IA","ID",$Pilote_eni,"Nom")." (Unité pilote:".$Unit_Pilote_eni."/ Unité avion:".$Unit_eni.")";		
	return $Pilote_eni;
}*/

Function GetIncident($PlayerID, $Type, $Saison, $Terrain, $Avion_db, $avion, $gaz=100, $pvp=false) 
{		
	//Saison : Eté=3, Printemps=2, Automne=1, Hiver=0
	$con=dbconnecti();
	$result2=mysqli_query($con,"SELECT Moteur,Engine FROM $Avion_db WHERE ID='$avion'");
	mysqli_close($con);
	if($result2)
	{
		while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Moteur=$data2['Moteur'];
			$Engine=$data2['Engine'];
		}
		mysqli_free_result($result2);
		unset($data2);
	}
	$con1=dbconnecti(1);
	$result2=mysqli_query($con1,"SELECT Type,Fiabilite,Injection FROM Moteur WHERE ID='$Engine'");
	mysqli_close($con1);
	if($result2)
	{
		while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Type_moteur=$data2['Type'];
			$Fiabilite=$data2['Fiabilite'];
			$Injection=$data2['Injection'];
		}
		mysqli_free_result($result2);
		unset($data2);
	}
	if($PlayerID >0)
	{
		if($pvp)
			$db_pilote="Pilote_PVP";
		else
			$db_pilote="Pilote";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Stress_Moteur,Stress_Commandes,Stress_Train,S_Purge,S_Moteurs FROM $db_pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Stress_Moteur=$data['Stress_Moteur'];
				$Stress_Commandes=$data['Stress_Commandes'];
				$Stress_Train=$data['Stress_Train'];
				$Purge=$data['S_Purge']*5;
				$Moteurs=$data['S_Moteurs'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if(!$Purge and !$pvp)
			$Purge=GetData("Unit","ID",$Unite,"U_Purge");
		if(!$Moteurs and !$pvp)
			$Moteurs=GetData("Unit","ID",$Unite,"U_Moteurs");
		if($Avion_db =="Avions_Persos" and $Terrain ==8 and $Moteur !=7)
			$Stress_Moteur+=50;
	}
	if($Type_moteur ==1 and $Injection ==1 and $Saison ==0)
		$Stress_Moteur+=10;
	$Fiabilite_Moteur=100-$Fiabilite+$Stress_Moteur-(100-$gaz)-$Moteurs;	
	//1 ou 2=Calibré, 5=Refroidissement amélioré
	if($Moteur ==1 or $Moteur ==2)
		$Fiabilite_Moteur-=5;
	elseif($Moteur ==5)
		$Fiabilite_Moteur-=10;
	elseif($Moteur ==8 and $Saison ==3)
		$Fiabilite_Moteur-=10;	
	//Type 1=Atter/Decoll, 2=En Vol		
	if($Type ==1)
		$incident=mt_rand(1,3);
	else
		$incident=mt_rand(1,2);
	//Incident
	switch($incident)
	{
		//Moteur
		case 1:
			$Test_Moteur=mt_rand(0,100)-$Saison;
			if($Test_Moteur < $Fiabilite_Moteur)
			{
				//Incident
				$IncidentEffect="une panne propulseur";
				$IncidentMalus=-99;
				$Stress=50;
			}
			elseif($Test_Moteur-5 < $Fiabilite_Moteur)
			{
				$IncidentEffect="une surchauffe du moteur";
				$IncidentMalus=-70;
				$Stress=5;
			}
			elseif($Test_Moteur-10 < $Fiabilite_Moteur)
			{
				$IncidentEffect="une fuite d'huile";
				$IncidentMalus=-50;
				$Stress=3;
			}
			elseif($Test_Moteur-15 < $Fiabilite_Moteur)
			{
				$IncidentEffect="un raté moteur";
				$IncidentMalus=-20;
				$Stress=2;
			}
			else
			{
				$IncidentEffect="aucun incident notable";
				$IncidentMalus=0;
				$Stress=1;
			}
			if($PlayerID >0)
				UpdateData($db_pilote,"Stress_Moteur",$Stress,"ID",$PlayerID);
		break;
		//Commandes
		case 2:
			$Test_Commandes=mt_rand(0,100);
			if($Test_Commandes < $Stress_Commandes-$Purge)
			{
				//Incident
				$IncidentEffect="un blocage des commandes";
				$IncidentMalus=-50;
				$Stress=10;
			}
			elseif($Test_Commandes -5 < $Stress_Commandes-$Purge)
			{
				//Incident
				$IncidentEffect="une mauvaise réponse des commandes";
				$IncidentMalus=-20;
				$Stress=5;
			}
			elseif($Test_Commandes -10 > $Stress_Commandes-$Purge)
			{
				$IncidentEffect="une fuite de liquide hydraulique";
				$IncidentMalus=-10;
				$Stress=2;
			}
			else
			{
				$IncidentEffect="aucun incident notable";
				$IncidentMalus=0;
				$Stress=1;
			}
			if($PlayerID >0)
				UpdateData($db_pilote,"Stress_Commandes",$Stress,"ID",$PlayerID);
		break;
		//Train
		case 3:
			$Test_Train=mt_rand(0,100);
			if($Test_Train < $Stress_Train-$Purge)
			{
				//Incident
				$IncidentEffect="un éclatement de pneu";
				$IncidentMalus=-99;
				$Stress=50;
			}
			else
			{
				$IncidentEffect="aucun incident notable";
				$IncidentMalus=0;
				$Stress=1;
			}
			if($PlayerID >0)
				UpdateData($db_pilote,"Stress_Train",$Stress,"ID",$PlayerID);
		break;
	}		
	return array($IncidentEffect,$IncidentMalus);
}

function GetFlee($PlayerID, $Pilote_eni)
{
	$flee=false;
	//$Pilote_vic=GetData("Pilote","ID",$PlayerID,"Victoires");
	$Reputation=GetData("Pilote","ID",$PlayerID,"Reputation");
	/*if($Pilote_eni == 4 and ($Reputation > 10000 or $Pilote_vic > 500))
	{
		$flee=true;
	}
	elseif($Pilote_eni ==147 and ($Reputation > 100000 or $Pilote_vic > 5000))
	{
		$flee=true;
	}
	elseif($Pilote_eni ==148 and ($Reputation > 250000 or $Pilote_vic > 10000))
	{
		$flee=true;
	}
	elseif($Pilote_eni ==149 and ($Reputation > 500000 or $Pilote_vic > 20000))
	{
		$flee=true;
	}*/
	if($Pilote_eni)
		$Reputation_eni=GetData("Pilote_IA","ID",$Pilote_eni,"Reputation");
	if($Reputation_eni >$Reputation)
		$flee=false;
	elseif(mt_rand(0,1) ==1)
			$flee=true;
	return $flee;
}