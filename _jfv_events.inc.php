<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./jfv_msg.inc.php');

function Chk_Update($Date)
{
	$Update = false;
	$Update_date=GetData("Conf_Update","ID",1,"Date");
	if($Date > $Update_date)
		$Update = true;
	return $Update;
}
function Update($Date)
{
	if(Chk_Update($Date))
	{
		$Campagne=GetData("Conf_Update","ID",2,"Date");
		if($Campagne)
		{
			$con=dbconnecti();
			$ok_up=mysqli_query($con,"UPDATE Conf_Update SET Date=ADDDATE(Date, 1) WHERE ID=2");
			$ok_up2=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=100");		//Reset Pool Ouvriers
			mysqli_close($con);
			if(!$ok_up)
				mail ('binote@hotmail.com', 'Aube des Aigles: Update Date' , 'Erreur de mise à jour de la Date Campagne :'.$Score);			
			//Renforts + Events
			Renforts($Campagne);
			usleep(10);
			Chk_Event($Campagne);
			usleep(10);
			InitLieux();
			usleep(10);
			InitMissions();
			InitSousLeFeu();
			usleep(10);
			TrainPilotes();
			usleep(10);
			//ResetVotes();
			SetData("Conf_Update","Date",$Date,"ID",1);
			//Score Campagne
			$Axe_nations = '1,4,6,9,15,18,19,20,24';
			$Allie_nations = '2,3,5,7,8,10,35';
			$con=dbconnecti();
			$Score_Axe = mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag IN (".$Axe_nations.")"),0);
			$Score_Allie = mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag IN (".$Allie_nations.")"),0);
			$result3=mysqli_query($con,"SELECT ID FROM Avion WHERE Engagement='$Campagne'");
			mysqli_close($con);
			if($result3)
			{
				while($data3 = mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					SetData("Avion","Etat",1,"ID",$data3['ID']);
				}
				mysqli_free_result($result3);
			}
			UpdateData("Pays","Score", $Score_Axe,"ID", 1);
			UpdateData("Pays","Score", $Score_Allie,"ID", 2);
		}
	}
}

function InitSousLeFeu()
{
	$con=dbconnecti();
	$reset=mysqli_query($con,"UPDATE Regiment_IA SET Move=0,Visible=0,Mission_Lieu_D=0,Mission_Type_D=0");
	$result=mysqli_query($con,"UPDATE Regiment SET Position=9 WHERE Position=8");			
	mysqli_close($con);
	if(!$result)
		mail('binote@hotmail.com', 'Aube des Aigles: Update : Init InitSousLeFeu' , 'Regiments pas initialisés! '.mysqli_error($con));
	unset($result);	
}

function InitLieux()
{
	//Init Lieu
	$con=dbconnecti();
	$result=mysqli_query($con,"UPDATE Lieu SET Meteo=0,Couverture=0,Couverture_Nuit=0,Couverture_Nbr=0,Couverture_Level=0,Couverture_Nuit_Nbr=0,Couverture_Nuit_Level=0,Escorte=0,Escorte_Nbr=0,Escorte_Level=0,Citernes=0,Camions=0,Recce=0,Recce_PlayerID=0");			
	mysqli_close($con);
	if(!$result)
		mail('binote@hotmail.com', 'Aube des Aigles: Update : Init Lieux' , 'Lieux pas initialisés! '.mysqli_error($con));
	unset($result);
}

function TrainPilotes()
{
	//Init Lieu
	$con=dbconnecti();
	$result=mysqli_query($con,"UPDATE Pilote_IA SET Pilotage=Pilotage+0.05,Acrobatie=Acrobatie+0.05,Navigation=Navigation+0.05,Tactique=Tactique+0.05,Tir=Tir+0.05,Vue=Vue+0.05 WHERE Pilotage < 100");			
	mysqli_close($con);
	if(!$result)
		mail('binote@hotmail.com', 'Aube des Aigles: Update : TrainPilotes' , 'Pilotes pas entrainés! '.mysqli_error($con));
	unset($result);
}

function InitMissions()
{
	//Init Missions
	$con=dbconnecti();
	//$result=mysqli_query($con,"UPDATE Unit SET Mission_Lieu=0,Mission_Type=0,Mission_Lieu_D=0,Mission_Type_D=0 WHERE Commandant=0 AND Officier_Technique=0 AND Officier_Adjoint=0");	
	$result2=mysqli_query($con,"UPDATE Unit SET Mission_IA=0,U_Chargeurs=1,U_Blindage=0,U_Camo=0,U_Moteurs=0,U_Purge=0,Mission_Lieu_D=0,Mission_Type_D=0");
	$result3=mysqli_query($con,"UPDATE Officier SET Mission_Lieu_D=0,Mission_Type_D=0,Aide=0 WHERE Mission_Lieu_D > 0 OR Aide > 0");
	mysqli_close($con);
	/*if(!$result)
	{
		mail('binote@hotmail.com', 'Aube des Aigles: Update : Init Missions' , 'Missions unités pas initialisés! '.mysqli_error($con));
	}*/
	if(!$result2)
		mail('binote@hotmail.com', 'Aube des Aigles: Update : Init Missions' , 'Ateliers unités pas initialisés! '.mysqli_error($con));
	if(!$result3)
		mail('binote@hotmail.com', 'Aube des Aigles: Update : Init Missions' , 'Demandes de Missions terrestres pas initialisés! '.mysqli_error($con));
}

function Renforts($Date)
{
	$con=dbconnecti();
	$resultl=mysqli_query($con,"SELECT * FROM Lieu WHERE Zone<>6 AND Last_Attack < '$Date'");
	mysqli_close($con);
	//Lieux
	if($resultl)
	{
		while ($datal = mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
		{ 
			//$Last_Attack = $data['Last_Attack'];
			/*if($Last_Attack < $Date)
			{*/
				$mes_lieux = "";
				$ID = $datal['ID'];
				$Nom = $datal['Nom'];
				$Camouflage = $datal['Camouflage'];
				$BaseAerienne = $datal['BaseAerienne'];
				$QualitePiste = $datal['QualitePiste'];
				$Meteo = $datal['Meteo'];
				$Zone = $datal['Zone'];
				$DefenseAA = $datal['DefenseAA_temp'];
				$DefenseAA_ori = $datal['DefenseAA'];
				$TypeIndus = $datal['TypeIndus'];
				$Industrie = $datal['Industrie'];
				$NoeudF = $datal['NoeudF'];
				$NoeudF_Ori = $datal['NoeudF_Ori'];
				$Pont = $datal['Pont'];
				$Pont_Ori = $datal['Pont_Ori'];
				$Port = $datal['Port'];
				$Port_Ori = $datal['Port_Ori'];
				$Radar = $datal['Radar'];
				$Radar_Ori = $datal['Radar_Ori'];
				$Tour = $datal['Tour'];
				$Fortification = $datal['Fortification'];
				$Garnison = $datal['Garnison'];
				$ValeurStrat = $datal['ValeurStrat'];
				$Auto_repare = $datal['Auto_repare'];
				if($Industrie > 0 and $datal['Usine_muns'] > 0 and $datal['Occupant'] == $datal['Flag'])
				{
					$up_max = $datal['Usine_muns'] * 1000 * $Industrie / 100;
					$up_stock = mt_rand(10,$up_max);
					$up_stock8 = $up_stock*5;
					if($datal['Occupant'] == 7)
						$up_stock13 = $up_stock*10;
					else
						$up_stock13 = $up_stock*5;
					if($datal['Usine_muns'] > 4)
					{
						$up_max2 = $datal['Usine_muns'] * 100 * $Industrie / 100;
						$up_stock2 = mt_rand(1,$up_max2);
						$con=dbconnecti();
						$upstock=mysqli_query($con,"UPDATE Lieu SET Stock_Munitions_50=Stock_Munitions_50+'$up_stock',Stock_Munitions_60=Stock_Munitions_60+'$up_stock',Stock_Munitions_75=Stock_Munitions_75+'$up_stock',
						Stock_Munitions_90=Stock_Munitions_90+'$up_stock',Stock_Munitions_105=Stock_Munitions_105+'$up_stock',Stock_Munitions_125=Stock_Munitions_125+'$up_stock',Stock_Munitions_150=Stock_Munitions_150+'$up_stock', 
						Stock_Bombes_50=Stock_Bombes_50+'$up_stock',Stock_Bombes_125=Stock_Bombes_125+'$up_stock',Stock_Bombes_250=Stock_Bombes_250+'$up_stock',Stock_Bombes_300=Stock_Bombes_300+'$up_stock',Stock_Bombes_400=Stock_Bombes_400+'$up_stock',
						Stock_Bombes_500=Stock_Bombes_500+'$up_stock',Stock_Bombes_800=Stock_Bombes_800+'$up_stock',Stock_Munitions_200=Stock_Munitions_200+'$up_stock2',Stock_Munitions_300=Stock_Munitions_300+'$up_stock2',
						Stock_Munitions_360=Stock_Munitions_360+'$up_stock2',Stock_Bombes_1000=Stock_Bombes_1000+'$up_stock2',Stock_Bombes_2000=Stock_Bombes_2000+'$up_stock2' WHERE ID='$ID'");
						mysqli_close($con);
					}
					$con=dbconnecti();
					$upstock1=mysqli_query($con,"UPDATE Lieu SET Stock_Munitions_8=Stock_Munitions_8+'$up_stock8',Stock_Munitions_13=Stock_Munitions_13+'$up_stock13',Stock_Munitions_20=Stock_Munitions_20+'$up_stock',
					Stock_Munitions_30=Stock_Munitions_30+'$up_stock',Stock_Munitions_40=Stock_Munitions_40+'$up_stock' WHERE ID='$ID'");
					mysqli_close($con);
				}			
				/*if($ValeurStrat > 4 and $datal['Stock_Troupes'] < 100)
					UpdateData("Lieu","Stock_Troupes",100,"ID",$datal['ID']);*/
				if($BaseAerienne and $QualitePiste < 100 and $Auto_repare)
				{
					if($QualitePiste > 0 or $ValeurStrat > 0)
					{
						$Qual_rnd = mt_rand(0,10);
						$QualitePiste += $Qual_rnd;
						if($QualitePiste > 100)
							$QualitePiste = 100;
					}
					else
						$QualitePiste = 0;
					SetData("Lieu","QualitePiste",$QualitePiste,"ID",$ID);
				}
				if($BaseAerienne and $Tour < 100 and $Auto_repare)
				{
					$Qual_rnd = mt_rand(0,10);
					$Tour += $Qual_rnd;
					if($Tour > 100)
						$Tour = 100;
					SetData("Lieu","Tour",$Tour,"ID",$ID);
				}
				if($DefenseAA+1 < $DefenseAA_ori)
				{
					$Qual_rnd = mt_rand(0,1);
					$DefenseAA += $Qual_rnd;
					if($DefenseAA > $DefenseAA_ori)
						$DefenseAA = $DefenseAA_ori;
					SetData("Lieu","DefenseAA_temp",$DefenseAA,"ID",$ID);
				}
				if($TypeIndus and $Industrie < 100 and $Auto_repare)
				{
					if($Industrie > 0 or $ValeurStrat > 0)
					{
						$Qual_rnd = mt_rand(0,10);
						$Industrie += $Qual_rnd;
						if($Industrie > 100)
							$Industrie = 100;
					}
					else
						$Industrie = 0;
					SetData("Lieu","Industrie",$Industrie,"ID",$ID);
				}
				if($NoeudF_Ori > 0 and $NoeudF < 100 and $Auto_repare)
				{
					if($NoeudF > 0 or $ValeurStrat > 0)
					{
						$Qual_rnd = mt_rand(0,10);
						$NoeudF += $Qual_rnd;
						if($NoeudF > 100)
							$NoeudF = 100;
					}
					else
						$NoeudF = 0;
					SetData("Lieu","NoeudF",$NoeudF,"ID",$ID);
				}
				if($Pont_Ori > 0 and $Pont < 100 and $Auto_repare)
				{
					if($Pont > 0 or $ValeurStrat > 0)
					{
						$Qual_rnd = mt_rand(0,10);
						$Pont += $Qual_rnd;
						if($Pont > 100)
							$Pont = 100;
					}
					else
						$Pont = 0;
					SetData("Lieu","Pont",$Pont,"ID",$ID);
				}
				if($Port_Ori > 0 and $Port < 100 and $Auto_repare)
				{
					if($Port > 0 or $ValeurStrat > 0)
					{
						$Qual_rnd = mt_rand(0,10);
						$Port += $Qual_rnd;
						if($Port > 100)
							$Port = 100;
					}
					else
						$Port = 0;
					SetData("Lieu","Port",$Port,"ID",$ID);
				}
				if($Radar_Ori > 0 and $Radar < 100 and $Radar > 0 and $Auto_repare)
				{
					$Qual_rnd = mt_rand(0,10);
					$Radar += $Qual_rnd;
					if($Radar > 100)
						$Radar = 100;
					SetData("Lieu","Radar",$Radar,"ID",$ID);
				}
				if($Camouflage or $Zone == 6)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE ID='$ID'");
					mysqli_close($con);
				}
				elseif($data['Recce'] == 2)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Lieu SET Recce=1,Recce_PlayerID=0 WHERE ID='$ID'");
					mysqli_close($con);
				}
				else
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Lieu SET Recce_PlayerID_AX=0,Recce_PlayerID_AL=0 WHERE ID='$ID'");
					mysqli_close($con);
				}
				if($mes_lieux)
					$mes_lieux_gen .=" \n ".$Nom." \n ".$mes_lieux;
			//}
		}
		mysqli_free_result($resultl);
		unset($resultl);
		unset($data);
	}
	//Infras détruites et Raffineries
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT ID,BaseAerienne,Tour,QualitePiste,TypeIndus,Industrie,NoeudF_Ori,NouedF,Pont_Ori,Pont,Port_Ori,Port,Radar_Ori,Radar FROM Lieu WHERE Last_Attack BETWEEN ('$Date' - INTERVAL 365 DAY) AND ('$Date' - INTERVAL 3 DAY)");
	$resultoil=mysqli_query($con,"SELECT ID,Nom,Oil,Industrie FROM Lieu WHERE Occupant=Flag AND Oil > 0 AND Industrie > 0");
	mysqli_close($con);
	if($result)
	{
		while($data = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{ 
			if($data['BaseAerienne'])
			{
				if($data['Tour'] == 0)
					SetData("Lieu","Tour",1,"ID",$data['ID']);
				if($data['QualitePiste'] == 0)
					SetData("Lieu","QualitePiste",1,"ID",$data['ID']);
			}
			if($data['TypeIndus'] and $data['Industrie'] == 0)
					SetData("Lieu","Industrie",1,"ID",$data['ID']);
			if($data['NoeudF_Ori'] and $data['NoeudF'] == 0)
					SetData("Lieu","NoeudF",1,"ID",$data['ID']);
			if($data['Pont_Ori'] and $data['Pont'] == 0)
					SetData("Lieu","Pont",1,"ID",$data['ID']);
			if($data['Port_Ori'] and $data['Port'] == 0)
					SetData("Lieu","Port",1,"ID",$data['ID']);
			if($data['Radar_Ori'] and $data['Radar'] == 0)
					SetData("Lieu","Radar",1,"ID",$data['ID']);
		}
		mysqli_free_result($result);
	}
	if($resultoil)
	{
		while($data = mysqli_fetch_array($resultoil))
		{
			$up_stock = $data['Oil'] * 10000 * $data['Industrie'] / 100;
			UpdateData("Lieu","Stock_Essence_100",$up_stock,"ID",$data['ID']);
			UpdateData("Lieu","Stock_Essence_87",$up_stock,"ID",$data['ID']);
			if($data['Oil'] > 2)
				UpdateData("Lieu","Stock_Essence_1",$up_stock,"ID",$data['ID']);
			$mes.= "<br>La raffinerie de ".$data['Nom']." a produit ".$up_stock." litres de carburant";
		}
		mysqli_free_result($resultoil);
	}
	//mail
	$mes.=$mes_lieux_gen;
	mail ('binote@hotmail.com', 'Aube des Aigles: Update' , $mes);
}
//Nécessite l'appel de l'include "jfv_const.inc.php" pour la constante '$Date_debut'.
function Chk_Event($Campagne)
{	
	/*$Date_time = $_SERVER['REQUEST_TIME'];
	$Date_debut=GetData("Conf_Update","ID",4,"Date");
	$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
	$Date_start = strtotime($Date_debut);
	$tab = diff_date($Date_time,$Date_start);
	$Date_final = "20120510"+($tab["mois"]*100)+($tab["semaine"]*7)+$tab["jour"];*/	
	//if($Date_time > $Date_Campagne)
	//{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Date,Type,Lieu,Pays,Unite,Avion,Avion_Nbr FROM Event_Historique WHERE Date='$Campagne'");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				Do_Event($data['Date'],$data['Type'],$data['Lieu'],$data['Pays'],$data['Unite'],$data['Avion'],$data['Avion_Nbr']);
				//mail ('binote@hotmail.com', 'Aube des Aigles: Chk_Event' , 'Event trouvé :'.$data['Type']);
			}
			mysqli_free_result($result);
			unset($result);
		}
		/*else
		{
			//mail ('binote@hotmail.com', 'Aube des Aigles: Chk_Event' , 'Aucun Event trouvé');
		}*/
	//}
}
//Type : 1=Bataille, 2=Bombardement, 40=Occupation, 41=Mouvement, 21=Renfort, 50=Nouvel Avion, 51=Nouvelle Unité
function Do_Event($Date,$Type,$Lieu,$Pays,$Unit=0,$avion=0,$avion_Nbr=0)
{
	switch($Type)
	{
		case 21:	//Renfort ($Lieu détermine la série d'avion : 1,2 ou 3. Si 0, tous les avions sont remplacés)
			$Base=GetData("Unit","ID",$Unit,"Base");
			$Bombe=GetData("Avion","ID",$avion,"Bombe");
			$Bombe_Nbr=GetData("Avion","ID",$avion,"Bombe_Nbr");
			//$avion_Nbr = 1; //Edit : afin de ne pas perturber la production
			if($Lieu == 0)
			{
				$con=dbconnecti();
				$update=mysqli_query($con,"UPDATE Unit SET Avion1='$avion',Avion2='$avion',Avion3='$avion',
				Avion1_Bombe='$Bombe',Avion2_Bombe='$Bombe',Avion3_Bombe='$Bombe',Avion1_Bombe_Nbr='$Bombe_Nbr',Avion2_Bombe_Nbr='$Bombe_Nbr',Avion3_Bombe_Nbr='$Bombe_Nbr' WHERE ID='$Unit'");
				mysqli_close($con);
			}
			else
			{
				SetData("Unit","Avion".$Lieu,$avion,"ID",$Unit);
				SetData("Unit","Avion".$Lieu."_Nbr",$avion_Nbr,"ID",$Unit);
				SetData("Unit","Avion".$Lieu."_Bombe",$Bombe,"ID",$Unit);
				SetData("Unit","Avion".$Lieu."_Bombe_Nbr",$Bombe_Nbr,"ID",$Unit);
			}
			AddEvent("Avion", 141, $avion, 1, $Unit, $Base, $avion_Nbr);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Renfort '.$Date , 'Unité '.$Unit.' Avion'.$Lieu.' : '.$avion_Nbr.' '.$avion);
		break;
		case 31:	//Mutation PNJ
			SetData("Pilote_IA","Unit",$Unit,"ID",$avion);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Mutation '.$Date , 'Pilote '.$avion.' est muté au '.$Unit);
		break;
		case 40:	//Occupation
			$Faction_eni=GetData("Pays","ID",$Pays,"Faction");
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Latitude,Longitude,BaseAerienne,NoeudF_Ori,Pays FROM Lieu WHERE ID='$Lieu'");
			mysqli_close($con);
			if($result)
			{
				while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$BaseAerienne = $data['BaseAerienne'];
					$Lat_base = $data['Latitude'];
					$Long_base = $data['Longitude'];
					$NoeudF_Ori = $data['NoeudF_Ori'];
					$Nation = $data['Pays'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($NoeudF_Ori > 0 and $Nation == 8)
				SetData("Lieu","NoeudF",0,"ID",$Lieu);
			if($BaseAerienne)
			{
				$units_auto_move = false;
				$Lat_base_min = $Lat_base -2.00;
				$Lat_base_max = $Lat_base +2.00;
				$Long_base_min = $Long_base -3.00;
				$Long_base_max = $Long_base +3.00;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT u.ID,u.Pays,u.Base_Ori FROM Unit as u, Pays as p WHERE u.Base='$Lieu' AND u.Pays=p.ID AND p.Faction<>'$Faction_eni'");
				mysqli_close($con);	
				if($result)
				{
					while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Pays_unit = $data['Pays'];
						$Unit_id = $data['ID'];
						$Base_Ori = $data['ID'];
						$con=dbconnecti();
						$Dest_unit = mysqli_result(mysqli_query($con,"SELECT ID FROM Lieu WHERE Occupant='$Pays_unit' AND BaseAerienne > 0
						AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') 
						ORDER BY RAND() LIMIT 1"),0);
						mysqli_close($con);	
						if(!$Dest_unit)
							$Dest_unit = $Base_Ori;
						SetData("Unit","Base",$Dest_unit,"ID",$Unit_id);
						SetData("Flak","Lieu",$Dest_unit,"Unit",$Unit_id);
						$units_auto_move[] = $Unit_id;
					}
					mysqli_free_result($result);
					if($units_auto_move)
					{
						$units_move = explode(' ', $units_auto_move);
						mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Occupation Auto-Move '.$Date , "Unités (".$units_move.") déplacées depuis le Lieu ".$Lieu);
					}
				}
			}
			$con=dbconnecti();
			$reset1=mysqli_query($con,"UPDATE Lieu SET Occupant='$Pays',Recce=0,Recce_PlayerID=0,DefenseAA_temp=DefenseAA,
			Couverture=0,Couverture_Nbr=0,Escorte=0,Escorte_Nbr=0,Couverture_Nuit=0,Couverture_Nuit_Nbr=0,Couverture_Level=0,Escorte_Level=0 WHERE ID='$Lieu'");
			$reset2=mysqli_query($con,"UPDATE Pilote SET Escorte=0,Couverture=0,Couverture_Nuit=0 WHERE Escorte='$Lieu' OR Couverture='$Lieu' OR Couverture_Nuit='$Lieu'");
			$reset_tr1=mysqli_query($con,"UPDATE Flak,Armes,Unit SET Flak.Unit=0 WHERE Flak.Unit=Unit.ID AND Flak.DCA_ID=Armes.ID AND Flak.Lieu='$Lieu' AND Armes.Transport=0 AND Unit.Pays<>'$Pays'");
			$reset_tr2=mysqli_query($con,"DELETE FROM Flak WHERE Unit=0");
			mysqli_close($con);
			unset($reset);
			//mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Occupation '.$Date , 'Pays '.$Pays.' occupe '.$Lieu);
		break;
		case 41:	//Mouvement
			//Camouflage 0 et Piste >=50 si unité étrangère occupe le terrain
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Zone,Occupant,QualitePiste FROM Lieu WHERE ID='$Lieu'");
			mysqli_close($con);
			if($result)
			{
				while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Zone = $data['Zone'];
					$Occupant = $data['Occupant'];
					$QualitePiste = $data['QualitePiste'];
				}
				mysqli_free_result($result);
			}
			if((IsAllie($Occupant) and  IsAxe($Pays)) OR (IsAxe($Occupant) and IsAllie($Pays)))
			{
				if($QualitePiste < 50)
					SetData("Lieu","QualitePiste",50,"ID",$Lieu);
				SetData("Lieu","Camouflage",0,"ID",$Lieu);
			}
			if($Zone != 6)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Flak SET Lieu='$Lieu' WHERE Unit='$Unit'");
				$reset_tr1=mysqli_query($con,"UPDATE Flak,Armes SET Flak.Unit=0 WHERE Flak.DCA_ID=Armes.ID AND Flak.Unit='$Unit' AND Armes.Transport=0");
				$reset_tr2=mysqli_query($con,"DELETE FROM Flak WHERE Unit=0");
				//$reset_tr2=mysqli_query($con,"DELETE FROM Flak USING Armes WHERE Flak.Unit='$Unit' AND Flak.DCA_ID=Armes.ID AND Armes.Transport=0");
				mysqli_close($con);
			}
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0,Occupant='$Pays' WHERE ID='$Lieu'");
			$reset1=mysqli_query($con,"UPDATE Unit SET Base='$Lieu',Mission_Lieu=0,Mission_Type=0 WHERE ID='$Unit'");
			$reset2=mysqli_query($con,"UPDATE Pilote_IA SET Avion=0,Cible=0,Couverture=0,Couverture_Nuit=0,Escorte=0,Alt=0,Task=0 WHERE Unit='$Unit'");
			mysqli_close($con);
			AddEvent("Avion",41,1,1,$Unit,$Lieu);
			//mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Mouvement '.$Date , 'Unité '.$Unit.' fait Mouvement vers la base de '.$Lieu);
		break;
		case 42:	//Capitulation
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE Pays='$Pays'");
			$reset2=mysqli_query($con,"UPDATE Unit SET Etat=0 WHERE Pays='$Pays'");
			mysqli_close($con);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Capitulation '.$Date , 'Pays '.$Pays.' capitule.');
		break;
		case 43:	//Alliance
			if($Pays == 6 or $Pays == 8)
			{
				SetData("Unit","Etat",1,"Pays",$Pays);
				SetData("Unit","Etat",0,"Type",8);
			}
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Unit SET Commandant=0,Officier_Adjoint=0,Officier_Technique=0,Mission_Lieu=0,Mission_Type=0,Mission_Lieu_D=0,Mission_Type_D=0 WHERE Pays='$Pays'");
			$reset1=mysqli_query($con,"UPDATE Pays SET Faction='$avion_Nbr' WHERE ID='$Pays'");
			mysqli_close($con);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Alliance '.$Date , 'Pays '.$Pays.' rejoint la faction '.$avion_Nbr);
		break;
		case 50:	//Nouvel Avion
			/*SetData("Avion","Etat",1,"ID",$avion);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Nouvel Avion '.$Date , 'Avion '.$avion);*/
		break;
		case 51:	//Nouvelle Unité
			SetData("Unit","Etat",1,"ID",$Unit);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Nouvelle Unité '.$Date , 'Unité '.$Unit.' arrive sur la base de '.$Lieu);
		break;
		case 52:	//Unité Dissoute
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Unité Dissoute '.$Date , 'Unité '.$Unit.' dissoute');
			$Msg_diss = "Votre unité a été dissoute.\n Vous avez été reversé dans une unité de réserve en attendant votre nouvelle affectation.\n Veuillez choisir votre prochaine unité via la demande de mutation classique.";
			$Sujet_diss = "Dissolution de votre unité";
			//Affectation PJ restants
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Unit SET Etat=0,Commandant=0,Officier_Adjoint=0,Officier_Technique=0,Mission_Lieu=0,Mission_Type=0,Mission_Lieu_D=0,Mission_Type_D=0,Porte_avions=0 WHERE ID='$Unit'");
			$reset2=mysqli_query($con,"UPDATE Pilote_IA SET Avion=0,Cible=0,Couverture=0,Couverture_Nuit=0,Escorte=0,Alt=0,Task=0 WHERE Unit='$Unit'");
			$result=mysqli_query($con,"SELECT ID,Pays FROM Pilote WHERE Unit='$Unit'");
			mysqli_close($con);
			if($result)
			{
				while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Pilote_ID = $data['ID'];
					$Pilote_Pays = $data['Pays'];
					switch($Pilote_Pays)
					{
						case 1:
							$Reserve = 192;
							$Expediteur = 4;
						break;
						case 2:
							$Reserve = 193;
							$Expediteur = 238;
						break;
						case 3:
							$Reserve = 193;
							$Expediteur = 1;
							SetData("Pilote","Pays",2,"ID",$Pilote_ID);
						break;
						case 4:
							$Reserve = 191;
							$Expediteur = 2;
						break;
						case 6:
							$Reserve = 194;
							$Expediteur = 425;
						break;
						case 7:
							$Reserve = 388;
							$Expediteur = 1;
						break;
						case 8:
							$Reserve = 387;
							$Expediteur = 1;
						break;
						case 9:
							$Reserve = 389;
							$Expediteur = 1;
						break;
					}
					SetData("Pilote","Unit",$Reserve,"ID",$Pilote_ID);
					SendMsg($Pilote_ID,$Expediteur,$Msg_diss,$Sujet_diss);
					//mail ('binote@hotmail.com', 'Aube des Aigles: Chk_Event' , 'Event trouvé :'.$data['Type']);
				}
				mysqli_free_result($result);
			}
		break;
		case 53: //Unité renommée
			//$avion = Nouvelle unité
			$Rep_O=GetData("Unit","ID",$Unit,"Reputation");
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Unit SET Etat=0,Commandant=0,Officier_Adjoint=0,Officier_Technique=0,Mission_Lieu=0,Mission_Type=0,Mission_Lieu_D=0,Mission_Type_D=0 WHERE ID='$Unit'");
			$reset2=mysqli_query($con,"UPDATE Unit SET Etat=1,Reputation='$Rep_O' WHERE ID='$avion'");
			mysqli_close($con);
			$Nouveau_Type = GetAvionType(GetData("Unit","ID",$avion,"Type"));
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Unité renommée '.$Date , 'Unité '.$Unit.' dissoute');
			$Msg_diss = 'Votre unité a été reformée sous un différent nom, en tant qu unité de '.$Nouveau_Type.'. \n Si vous occupiez une fonction de Staff, vous devez postuler à nouveau.';
			$Sujet_diss = 'Unité renommée';
			//Affectation PJ restants
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT ID FROM Pilote WHERE Unit='$Unit'");
			mysqli_close($con);
			if($result)
			{
				while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Pilote_ID = $data['ID'];
					SetData("Pilote","Unit",$avion,"ID",$Pilote_ID);
					SendMsg($Pilote_ID,$Expediteur,$Msg_diss,$Sujet_diss);
					//mail ('binote@hotmail.com', 'Aube des Aigles: Chk_Event' , 'Event trouvé :'.$data['Type']);
				}
				mysqli_free_result($result);
			}
		break;
		case 54:	//Unité changement de type
			SetData("Unit","Type",$avion_Nbr,"ID",$Unit);
		case 55:	//Piste améliorée
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Lieu SET BaseAerienne='$avion_Nbr',LongPiste='$avion' WHERE ID='$Lieu'");
			mysqli_close($con);
		break;
		case 56:	//Production Transférée
			SetData("Avion","Usine".$avion_Nbr,$Lieu,"ID",$avion);
		break;
		case 57:	//Nouveau PA
			SetData("Unit","Porte_avions",$Lieu,"ID",$Unit);
		break;
		case 60:	//Victoire PNJ
			/*UpdateData("Pilote_IA","Victoires",$avion_Nbr,"ID",$avion);
			UpdateData("Pilote_IA","Pilotage",$avion_Nbr,"ID",$avion);
			UpdateData("Pilote_IA","Tactique",$avion_Nbr,"ID",$avion);
			UpdateData("Pilote_IA","Tir",$avion_Nbr,"ID",$avion);*/
			//mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Nouvelle Victoire '.$Date , 'As '.$avion.' a abattu '.$avion_Nbr.' avions ennemis ce jour');
		break;
		case 61:	//Promotion PNJ
			//SetData("Pilote_IA","Avancement",$Lieu,"ID",$avion);
			//mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Nouvelle Promotion '.$Date , 'As '.$avion.' a été promu au grade supérieur ce jour');
		break;
		case 62:	/*Reput PNJ
			SetData("Pilote_IA","Reputation",$Lieu,"ID",$avion);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event Nouvelle Fonction '.$Date , 'As '.$avion.' a été promu à une fonction supérieure ce jour');*/
		break;
		case 64:	/*Blesse PNJ
			SetData("Pilote_IA","Actif",0,"ID",$avion);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event As Blessé '.$Date , 'As '.$avion.' a été blessé ce jour');*/
		break;
		case 65:	//Mort PNJ
			SetData("Pilote_IA","Actif",0,"ID",$avion);
			mail ('binote@hotmail.com', 'Aube des Aigles: Do_Event As Tué '.$Date , 'As '.$avion.' a été tué ce jour');
		break;
	}
}
/*function diff_date($date1, $date2) 
{
	$second = floor($date1 - $date2);
	if ($second == 0) return "0";
	return array("an"         => date('Y', $second)-1970, 
		  "mois"      => date('m', $second)-1,
		  "semaine" => floor((date('d', $second)-1)/7),
		  "jour"	 => (date('d', $second)-1)%7,
	 );
}*/

$Date = date('Y-m-d');
if(Chk_Update($Date))
	Update($Date);
else
	mail ('binote@hotmail.com', 'Aube des Aigles: Update' , 'Erreur de mise à jour : Chk_Update false');	
?>