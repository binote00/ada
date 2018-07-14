<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']) AND $_SESSION['Mission_Choose'] ==false)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$PlayerID=$_SESSION['PlayerID'];
	if($PlayerID >0 and $_SESSION['Distance'] ==0 and $_SESSION['start_mission'] ==true)
	{
		$country=$_SESSION['country'];
		$_SESSION['Cr']=false;
		$_SESSION['Distance']=1;
		$_SESSION['Mission_Choose']=true;	
		$Veteran_noob=false;
		$Heure=date('H');				
		$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$reset=mysqli_query($con,"UPDATE Pilote SET S_Cible_Atk=0,S_Mission=0,S_Unite_Intercept=0,Commando=0,Ecole=0,S_Pass=0 WHERE ID='$PlayerID'");
		$resultac=mysqli_query($con,"SELECT Officier,Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
		$result=mysqli_query($con,"SELECT Unit,Front,Credits,Missions_Jour,Missions_Max,Avancement,Reputation,Intercept,Escorte,Couverture,Heure_Mission FROM Pilote WHERE ID='$PlayerID'");
		if($resultac)
		{
			while($dataac=mysqli_fetch_array($resultac,MYSQLI_ASSOC))
			{
				$Officier=$dataac['Officier'];
				$Admin=$dataac['Admin'];
			}
			mysqli_free_result($resultac);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Front=$data['Front'];
				$Credits=$data['Credits'];
				$Missions_Jour=$data['Missions_Jour'];
				$Missions_Max=$data['Missions_Max'];
				$Avancement=$data['Avancement'];
				$Reputation=$data['Reputation'];
				$Mission_Intercept=$data['Intercept'];
				$Escorte_PJ=$data['Escorte'];
				$Couv=$data['Couverture'];
				$Heure_Mission=$data['Heure_Mission'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		/*if($Officier >0)
			$Lieu_Reg_Off=GetData("Regiment","Officier_ID",$Officier,"Lieu_ID");*/
        $result=mysqli_query($con,"SELECT u.`Type`,u.Base,u.Mission_Type,u.Mission_Lieu,l.Latitude,l.Longitude FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.ID='$Unite'");
        if($result)
        {
            while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
            {
                $Unite_Type=$data['Type'];
                $Base=$data['Base'];
                $Lat_Base_Unit=$data['Latitude'];
                $Long_Base_Unit=$data['Longitude'];
                /*$Mission_Type_U=$data['Mission_Type'];
                $Mission_Lieu_U=$data['Mission_Lieu'];*/
            }
            mysqli_free_result($result);
            unset($data);
        }
		$Coord=GetCoord($Front);
		$Lat_base_min=$Coord[0];
		$Lat_base_max=$Coord[1];
		$Long_base_min=$Coord[2];
		$Long_base_max=$Coord[3];
        if($Lat_Base_Unit <47 and $Long_Base_Unit >7){
            $Lat_base_max=46.5;
        }
        elseif($Lat_Base_Unit >47 and $Long_Base_Unit >7){
            $Lat_base_min=46.5;
        }
		$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Avion=0 WHERE Unit='$Unite' AND Couverture=0 AND Escorte=0 AND Couverture_Nuit=0 AND Task=0");
		$result2=mysqli_query($con,"(SELECT u.Mission_Type_D,u.Mission_Lieu_D,0 FROM Unit as u,Pays as p,Lieu as l WHERE u.Pays=p.ID AND u.Mission_Lieu_D=l.ID AND u.Mission_Lieu_D >0 AND u.Mission_Type_D >0 AND p.Faction='$Faction' AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND u.Commandant NOT IN ('$PlayerID') AND u.Mission_Lieu_D NOT IN('$Lieu_Reg_Off1','$Lieu_Reg_Off2'))
		UNION (SELECT r.Mission_Type_D,r.Mission_Lieu_D,0 FROM Lieu as l,Regiment_IA as r,Pays as p WHERE r.Pays=p.Pays_ID AND r.Mission_Lieu_D=l.ID AND r.Mission_Lieu_D >0 AND r.Mission_Type_D >0 AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off') AND r.Mission_Lieu_D NOT IN('$Lieu_Reg_Off1','$Lieu_Reg_Off2'))
		UNION (SELECT 7,l.ID,0 FROM Lieu as l,Attaque as a WHERE a.Lieu=l.ID AND DATE(a.Date)=DATE(NOW()) AND l.Flag='$country' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off'))
		UNION (SELECT 17,l.ID,0 FROM Lieu as l,Bombardement as b WHERE b.Lieu=l.ID AND DATE(b.Date)=DATE(NOW()) AND l.Flag='$country' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off'))
		");
		/*(SELECT o.Mission_Type_D,o.Mission_Lieu_D,o.ID FROM Officier as o,Pays as p WHERE p.ID=o.Pays AND o.Front='$Front' AND o.Mission_Lieu_D >0 AND o.Mission_Type_D >0 AND o.ID NOT IN ('$Officier') AND p.Faction='$Faction' AND o.Mission_Lieu_D NOT IN('$Lieu_Reg_Off1','$Lieu_Reg_Off2'))	UNION */
		mysqli_close($con);
		if($result2)//Demande de mission coop
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Mission_Type_T=$data['Mission_Type_D'];
				$Mission_Lieu_T=$data['Mission_Lieu_D'];
				if($Unite_Type !=8 and $Unite_Type !=6)
				{
					if($Mission_Type_T ==1)
						$Go_41=true;
					if($Mission_Type_T ==2)
						$Go_42=true;
					if($Mission_Type_T ==4)
						$Go_44=true;
					if($Mission_Type_T ==5)
						$Go_45=true;
					if($Mission_Type_T ==7)
						$Go_47=true;
					if($Mission_Type_T ==11)
						$Go_411=true;
					if($Mission_Type_T ==12)
						$Go_412=true;
					if($Mission_Type_T ==13)
						$Go_413=true;
					if($Mission_Type_T ==14)
						$Go_414=true;
					if($Mission_Type_T ==15)
						$Go_415=true;
					if($Mission_Type_T ==17)
						$Go_417=true;
					if($Mission_Type_T ==21)
						$Go_421=true;
					if($Mission_Type_T ==29)
						$Go_429=true;
					//$MT_Nom=GetData("Lieu","ID",$Mission_Lieu_T,"Nom");
					//$Dist_MT=GetDistance($Base,$Mission_Lieu_T);
					//$Choix_ter.='<option value=\'30_'.$Mission_Type_T.'\'>Coopération Terrestre : '.GetMissionType($Mission_Type_T).' : '.$MT_Nom.' ('.$Dist_MT[0].' km)</option>';
				}
				elseif($Unite_Type ==6)
				{
					if($Mission_Type_T ==23)
						$Go_423=true;
					if($Mission_Type_T ==24)
						$Go_424=true;
				}
			}
			mysqli_free_result($result2);
			unset($data);
		}		
		//Missions quotidiennes offertes
		$Free_M=2;
		if(($Unite_Type >5 and $Unite_Type !=12) or $Unite_Type ==3)
			$Free_M=4;
		elseif($Unite_Type >1)
			$Free_M=3;			
		if($Unite_Type !=3 and $Unite_Type !=6 and $Unite_Type !=8 and $Missions_Max >1 and $Heure_Mission ==$Heure)
			$Cr_Mission=8;
		else
			$Cr_Mission=4;
		if($Missions_Jour <$Free_M and $Credits >=$Cr_Mission and $Missions_Max <6)
		{			
			/*Entrée en guerre des différentes nations
			switch($country)
			{
				case 6:
					$Date_Guerre='1940-06-10';
				break;
				case 7: case 9:
					$Date_Guerre='1941-12-06';
				break;
				case 8:
					$Date_Guerre='1941-06-21';
				break;
				case 10:
					$Date_Guerre='1940-10-27';
				break;
				case 15:
					$Date_Guerre='1941-03-01';
				break;
				case 18:
					$Date_Guerre='1940-11-23';
				break;
				case 19:
					$Date_Guerre='1941-06-21';
				break;
				case 20:
					$Date_Guerre='1941-06-25';
				break;
				default:
					$Date_Guerre='1940-05-01';
				break;
			}*/
			//Unites réserve
			if($Reputation <50)
				$Choix_tr="<option value='98' selected>Entrainement au pilotage</option>";
			elseif($Reputation <100)
			{
				$Choix_tr="<option value='98' selected>Entrainement au pilotage</option>
				<option value='99'>Entrainement à l'acrobatie</option>
				<option value='102'>Entrainement à la navigation</option>";
			}
			else
			{
				$Choix_tr="<option value='98' selected>Entrainement au pilotage</option>
				<option value='99'>Entrainement à l'acrobatie</option>
				<option value='102'>Entrainement à la navigation</option>
				<option value='100'>Entrainement au tir</option>
				<option value='101'>Entrainement au bombardement</option>";
			}
			if($Unite_Type ==1 or $Unite_Type ==4 or $Unite_Type ==12)
				$Choix_tr.="<option value='103' selected>Entrainement au combat aérien</option>";
			if($Unite ==GetTraining($country))
			{
				$prepa_txt='<b>Ecole de Pilotage</b>';
				$Choix=$Choix_tr;
			}
			elseif($Faction ==0 or $Veteran_noob ==true or $G_Treve or ($G_Treve_Med and $Front ==2) or ($G_Treve_Est_Pac and ($Front ==1 or $Front ==4 or $Front ==3)))
			{
				$prepa_txt='<b>Choix du type de mission</b>';
				$Choix=$Choix_tr;
			}
			else
			{
				/*Missions Unites
				if($Mission_Type_U and $Mission_Lieu_U)
				{
					$Dist_MU=GetDistance($Base, $Mission_Lieu_U);
					$MU_Nom=GetData("Lieu","ID",$Mission_Lieu_U,"Nom");
					$prepa_txt="<b>L'état-major a assigné une mission particulière à notre unité !
								<br>Votre commandant exige une réaction immédiate.</b>";
					$Choix_unite='<option value=\'20\'>Mission Unité : '.GetMissionType($Mission_Type_U).' : '.$MU_Nom.' ('.$Dist_MU[0].' km)</option>';
				}*/
				//Activer Batailles Historiques
				$Bataille_Historique=false;
				$_SESSION['BH_Lieu']=0;
				$_SESSION['BH_Mission']=0;
				$_SESSION['BH_ID']=0;
				$_SESSION['BH_Nom']=0;
				/*Activation des Batailles Historiques encodées
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID,Nom,Lieu,Pays,Type_Mission FROM Event_Historique WHERE Date='$Date_Campagne' AND Type_Mission>0 AND Pays='$country' AND Unite='$Unite_Type' AND Avion_Nbr='$Front'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$_SESSION['BH_ID']=$data['ID'];
						$_SESSION['BH_Nom']=$data['Nom'];
						$_SESSION['BH_Lieu']=$data['Lieu'];
						$_SESSION['BH_Mission']=$data['Type_Mission'];
					}
					mysqli_free_result($result);
				}*/
				//Fin Batailles Historiques encodées
				if(!$_SESSION['BH_ID'])
				{
					$Lieu_M="Lieu_Mission".$Unite_Type;
					$Type_M="Type_Mission".$Unite_Type;
					$con=dbconnecti();
					$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
					$result=mysqli_query($con,"SELECT $Lieu_M,$Type_M FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
					mysqli_close($con);
					if($result2)
					{
						while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							$Commandant_EM=$data2['Commandant'];
							$Officier_Adjoint_EM=$data2['Adjoint_EM'];
						}
						mysqli_free_result($result2);
					}
					if($result)
					{
						while($data=mysqli_fetch_array($result))
						{
							if((($Unite_Type ==3 and $data[1] ==5) or ($Unite_Type ==4 and $data[1] ==17) or ($Unite_Type ==1 and $data[1] ==7) or ($Unite_Type ==9 and $data[1] ==29) or ($Unite_Type ==2 and $data[1] ==8) or ($Unite_Type ==2 and $data[1] ==16) or ($Unite_Type ==7 and $data[1] ==2) or ($Unite_Type ==10 and $data[1] ==12) or ($Unite_Type ==10 and $data[1] ==13) or ($Unite_Type ==11 and $data[1] ==8) or ($Unite_Type ==11 and $data[1] ==16)) 
							or ($PlayerID !=$Commandant_EM and $PlayerID !=$Officier_Adjoint_EM))
							{
								if($data[0] !=$Lieu_Reg_Off1 and $data[0] !=$Lieu_Reg_Off2)
								{
									$_SESSION['BH_Mission']=$data[1];
									$_SESSION['BH_Lieu']=$data[0];
									$_SESSION['BH_Nom']=GetData("Lieu","ID",$_SESSION['BH_Lieu'],"Nom");
									$_SESSION['BH_ID']=0;
								}
							}
						}
						mysqli_free_result($result);
					}
				}
				if($_SESSION['BH_Mission'] >0){
					$Bataille_Historique=true;
					$BH_Type=GetMissionType($_SESSION['BH_Mission']);
				}
				$random_mission=true;
				/*Interception
				if($Unite_Type ==1 or $Unite_Type ==4 or $Unite_Type ==12)
				{
					if(!$Mission_Intercept)
					{
						$lieux_int=array($Base);
						$Dateref=date('Y-m-d');
						if(!$Lat_base)$Lat_base=GetData("Lieu","ID",$Base,"Latitude");
						if(!$Long_base)$Long_base=GetData("Lieu","ID",$Base,"Longitude");
						$Lat_base_min=$Lat_base-1.00;
						$Lat_base_max=$Lat_base+1.00;
						$Long_base_min=$Long_base-2.00;
						$Long_base_max=$Long_base+2.00;
						$con=dbconnecti();
						//$resultl=mysqli_query($con,"SELECT DISTINCT ID FROM Lieu WHERE Flag='$country'
						$resultl=mysqli_query($con,"SELECT DISTINCT Lieu.ID FROM Lieu,Pays WHERE Lieu.Flag=Pays.Pays_ID AND Pays.Faction='$Faction'
						AND (Lieu.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
						AND (Lieu.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max')");
						mysqli_close($con);
						if($resultl)
						{
							while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
							{
								$lieux_int[]=$datal['ID'];
							}
							mysqli_free_result($resultl);
							shuffle($lieux_int);
							$lieux_inte=implode(',',$lieux_int);
						}
						unset($lieux_int);
						$Int_rand=mt_rand(1,9);
						switch($Int_rand)
						{
							case 1: case 2: case 3: case 4: case 5:
								if(!$Nuit)
									$query="SELECT DISTINCT b.Unite FROM Bombardement as b, Unit as u, Pays as p WHERE u.ID=b.Unite AND u.Pays=p.Pays_ID AND p.Faction<>'$Faction' AND
									DATEDIFF(DATE(b.Date),'$Dateref')=0 AND b.Lieu IN (".$lieux_inte.") AND b.Cycle=0 ORDER BY RAND() LIMIT 1";
								else
									$query="SELECT DISTINCT b.Unite FROM Bombardement as b, Unit as u, Pays as p WHERE u.ID=b.Unite AND u.Pays=p.Pays_ID AND p.Faction<>'$Faction' AND
									DATEDIFF(DATE(b.Date),'$Dateref')=0 AND b.Lieu IN (".$lieux_inte.") AND Cycle=1 ORDER BY RAND() LIMIT 1";
							break;
							case 6: case 7: case 8:
								if(!$Nuit)
									$query="SELECT DISTINCT b.Unite FROM Attaque as a, Unit as u, Pays as p WHERE u.ID=a.Unite AND u.Pays=p.Pays_ID AND p.Faction<>'$Faction' AND
									DATEDIFF(DATE(b.Date),'$Dateref')=0 AND b.Lieu IN (".$lieux_inte.") AND b.Cycle=0 ORDER BY RAND() LIMIT 1";
								else
									$query="SELECT DISTINCT b.Unite FROM Attaque as a, Unit as u, Pays as p WHERE u.ID=a.Unite AND u.Pays=p.Pays_ID AND p.Faction<>'$Faction' AND
									DATEDIFF(DATE(b.Date),'$Dateref')=0 AND b.Lieu IN (".$lieux_inte.") AND b.Cycle=1 ORDER BY RAND() LIMIT 1";
							break;
							case 9:
								if($Unite_Type ==4 and $Navigation > 100)
									$query="SELECT DISTINCT b.Unite FROM Bombardement as b, Unit as u, Pays as p WHERE u.ID=b.Unite AND u.Pays=p.Pays_ID AND p.Faction<>'$Faction' AND
									DATEDIFF(DATE(b.Date),'$Dateref')=0 AND b.Lieu IN (".$lieux_inte.") AND b.Cycle=1 ORDER BY RAND() LIMIT 1";
								else
									$query="SELECT DISTINCT b.Unite FROM Recce as r, Unit as u, Pays as p WHERE u.ID=r.Unite AND u.Pays=p.Pays_ID AND p.Faction<>'$Faction' AND
									DATEDIFF(DATE(b.Date),'$Dateref')=0 AND b.Lieu IN (".$lieux_inte.") ORDER BY RAND() LIMIT 1";
							break;
						}
						$con=dbconnecti();
						$result=mysqli_query($con,$query);
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Unite_Intercept=$data['Unite'];
								SetData("Pilote","S_Unite_Intercept",$Unite_Intercept,"ID",$PlayerID);
							}
							mysqli_free_result($result);
						}

						if($Unite_Intercept)
						{
							if($Unite_Type ==4 and $Navigation >100)
								SetData("Pilote","S_Nuit",1,"ID",$PlayerID);
							$prepa_txt='<b>Une formation ennemie approche de votre base ! L\'alerte est donnée !
										<br>Votre commandant exige une réaction immédiate.</b>';
							$Choix='<option value=\'9\'>Interception</option>';
							$random_mission=false;
						}
					}
				}*/
				if($Bataille_Historique)
				{
					if(!$_SESSION['BH_Lieu'])
						$DH_txt=' (cible au choix)';
					else{
						$Dist_Histo=GetDistance($Base,$_SESSION['BH_Lieu']);
						$DH_txt=' ('.$Dist_Histo[0].' km)';
					}
					$prepa_txt='<b>Nos troupes ont besoin de notre soutien dans une bataille décisive !
								<br>Votre commandant exige une réaction immédiate.</b>';
					$Choix_histo='<option value=\'10\'>Mission EM : '.$BH_Type.' : '.$_SESSION['BH_Nom'].$DH_txt.'</option>';
				}
				if($random_mission)
				{	
					/*if($Dispo_EM !=2 or $Admin)
					{*/
						//Bouton Aide
						$aide_prepa="<a href='help/aide_missions.php' target='_blank' title='Aide à propos du choix des missions'><img src='images/help.png'></a>";
						$prepa_txt='<b>Choix du type de mission</b>'.$aide_prepa;
						//Choix en fonction du type d'unité
						$choix1='';
						$choix6='';
						$choix2='';
						$choix8='';
						$choix16='';
						$choix3='';
						$choix17='';
						$choix4='';
						$choix7='';
						$choix5='';
						$choix14='';
						$choix15='';
						$choix11='';
						$choix12='';
						$choix13='';
						$choix18='';
						$choix19='';
						$choix21='';
						$choix22='';
						$choix23='';
						$choix24='';
						$choix25='';
						$choix26='';
						$choix27='';
						$choix28='';
						$choix29='';
						$choix31='';
						$choix41='';
						$choix42='';
						$choix44='';
						$choix45='';
						switch($Unite_Type)
						{
							case 1:
								$choix1="<option value='1'>Appui rapproché</option>";
								$choix6="<option value='6'>Attaque au sol</option>";
								$choix11="<option value='11'>Attaque de navire</option>";
								$choix2="<option value='2'>Bombardement tactique</option>";
								$choix3="<option value='3'>Chasse libre</option>";
								$choix4="<option value='4'>Escorte</option>";
								$choix7="<option value='7'>Patrouille défensive</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								//$choix26="<option value='26'>Supériorité aérienne</option>";
								if($Go_41)
									$choix41="<option value='41'>Appui rapproché (Coop Terrestre)</option>";
								if($Go_411)
									$choix411="<option value='411'>Attaque de navire (Coop Navale)</option>";
								if($Go_42)
									$choix42="<option value='42'>Bombardement tactique (Coop Terrestre)</option>";
								if($Go_44)
									$choix44="<option value='44'>Escorte (Coop Mixte)</option>";
								if($Go_47)
									$choix47="<option value='47'>Patrouille (Coop Mixte)</option>";
								if($Go_45)
									$choix45="<option value='45'>Reconnaissance tactique (Coop Terrestre)</option>";
							break;
							case 2:
								$choix12="<option value='12'>Bombardement naval</option>";
								$choix8="<option value='8'>Bombardement stratégique de jour</option>";
								$choix16="<option value='16'>Bombardement stratégique de nuit</option>";
								$choix2="<option value='2'>Bombardement tactique</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								$choix13="<option value='13'>Torpillage</option>";
								if($Go_412)
									$choix412="<option value='412'>Bombardement naval (Coop Navale)</option>";
								if($Go_42)
									$choix42="<option value='42'>Bombardement tactique (Coop Terrestre)</option>";
								if($Go_45)
									$choix45="<option value='45'>Reconnaissance tactique (Coop Terrestre)</option>";
								if($Go_413)
									$choix413="<option value='413'>Torpillage (Coop Navale)</option>";
							break;
							case 3:
								$choix2="<option value='2'>Bombardement tactique</option>";
								$choix28="<option value='28'>Commando (Infiltration)</option>";
								$choix21="<option value='21'>Marquage de cible</option>";
								$choix15="<option value='15'>Reconnaissance stratégique</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								$choix18="<option value='18'>Sauvetage</option>";
								$choix22="<option value='22'>Sauvetage de Nuit</option>";
								if($Go_42)
									$choix42="<option value='42'>Bombardement tactique (Coop Terrestre)</option>";
								if($Go_421)
									$choix421="<option value='421'>Marquage (Coop Mixte)</option>";
								if($Go_45)
									$choix45="<option value='45'>Reconnaissance tactique (Coop Mixte)</option>";
								if($Go_415)
									$choix415="<option value='415'>Reconnaissance stratégique (Coop Mixte)</option>";
								//Prévoir mission brouillage radio et récupération commandos
							break;
							case 4:
								$choix1="<option value='1'>Appui rapproché</option>";
								$choix6="<option value='6'>Attaque au sol</option>";
								$choix11="<option value='11'>Attaque de navire</option>";
								$choix12="<option value='12'>Bombardement naval</option>";
								$choix2="<option value='2'>Bombardement tactique</option>";
								$choix17="<option value='17'>Chasse de nuit</option>";
								$choix4="<option value='4'>Escorte</option>";
								$choix31="<option value='31'>Harcèlement (nuit)</option>";
								$choix7="<option value='7'>Patrouille défensive</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								if($Go_41)
									$choix41="<option value='41'>Appui rapproché (Coop Terrestre)</option>";
								if($Go_411)
									$choix411="<option value='411'>Attaque de navire (Coop Navale)</option>";
								if($Go_412)
									$choix412="<option value='412'>Bombardement naval (Coop Navale)</option>";
								if($Go_42)
									$choix42="<option value='42'>Bombardement tactique (Coop Terrestre)</option>";
								if($Go_417)
									$choix417="<option value='417'>Chasse de nuit (Coop Terrestre)</option>";
								if($Go_44)
									$choix44="<option value='44'>Escorte (Coop Mixte)</option>";
								if($Go_47)
									$choix47="<option value='47'>Patrouille (Coop Mixte)</option>";
								if($Go_45)
									$choix45="<option value='45'>Reconnaissance tactique (Coop Terrestre)</option>";
								if($Go_413)
									$choix413="<option value='413'>Torpillage (Coop Navale)</option>";
							break;
							case 6:
								//$choix24="<option value='24'>Parachutage de jour</option>";
								$choix27="<option value='27'>Commando (Parachutage)</option>";
								$choix25="<option value='25'>Parachutage de nuit</option>";
								$choix23="<option value='23'>Ravitaillement</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								$choix18="<option value='18'>Sauvetage</option>";
								$choix22="<option value='22'>Sauvetage de Nuit</option>";
								if($Go_423)
									$choix423="<option value='423'>Ravitaillement (Coop Terrestre)</option>";
								if($Go_424)
									$choix424="<option value='424'>Parachutage (Coop Terrestre)</option>";
								//Prévoir missions récupération commandos
							break;
							case 7:
								$choix1="<option value='1'>Appui rapproché</option>";
								$choix6="<option value='6'>Attaque au sol</option>";
								$choix11="<option value='11'>Attaque de navire</option>";
								$choix12="<option value='12'>Bombardement naval</option>";
								$choix8="<option value='8'>Bombardement stratégique de jour</option>";
								$choix2="<option value='2'>Bombardement tactique</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								$choix13="<option value='13'>Torpillage</option>";
								if($Go_41)
									$choix41="<option value='41'>Appui rapproché (Coop Terrestre)</option>";
								if($Go_411)
									$choix411="<option value='411'>Attaque de navire (Coop Navale)</option>";
								if($Go_412)
									$choix412="<option value='412'>Bombardement naval (Coop Navale)</option>";
								if($Go_42)
									$choix42="<option value='42'>Bombardement tactique (Coop Terrestre)</option>";
								if($Go_45)
									$choix45="<option value='45'>Reconnaissance tactique (Coop Terrestre)</option>";
								if($Go_413)
									$choix413="<option value='413'>Torpillage (Coop Navale)</option>";
							break;
							case 8:
							break;
							case 9:
								$choix12="<option value='12'>Bombardement naval</option>";
								$choix14="<option value='14'>Mouillage de mines</option>";
								$choix29="<option value='29'>Patrouille ASM</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								$choix15="<option value='15'>Reconnaissance stratégique</option>";
								$choix13="<option value='13'>Torpillage</option>";
								$choix19="<option value='19'>Sauvetage en mer</option>";
								if($Go_412)
									$choix412="<option value='412'>Bombardement naval (Coop Navale)</option>";
								if($Go_429)
									$choix429="<option value='429'>Patrouille ASM (Coop Navale)</option>";
								if($Go_45)
									$choix45="<option value='45'>Reconnaissance tactique (Coop Navale)</option>";
								if($Go_415)
									$choix415="<option value='415'>Reconnaissance stratégique (Coop Navale)</option>";
								if($Go_413)
									$choix413="<option value='413'>Torpillage (Coop Navale)</option>";
							break;
							case 10:
								$choix1="<option value='1'>Appui rapproché</option>";
								$choix6="<option value='6'>Attaque au sol</option>";
								$choix11="<option value='11'>Attaque de navire</option>";
								$choix12="<option value='12'>Bombardement naval</option>";
								$choix8="<option value='8'>Bombardement stratégique de jour</option>";
								$choix2="<option value='2'>Bombardement tactique</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								$choix13="<option value='13'>Torpillage</option>";
								if($Go_41)
									$choix41="<option value='41'>Appui rapproché (Coop Terrestre)</option>";
								if($Go_411)
									$choix411="<option value='411'>Attaque de navire (Coop Navale)</option>";
								if($Go_412)
									$choix412="<option value='412'>Bombardement naval (Coop Navale)</option>";
								if($Go_42)
									$choix42="<option value='42'>Bombardement tactique (Coop Terrestre)</option>";
								if($Go_45)
									$choix45="<option value='45'>Reconnaissance tactique (Coop Mixte)</option>";
								if($Go_413)
									$choix413="<option value='413'>Torpillage (Coop Navale)</option>";
							break;
							case 11:
								$choix8="<option value='8'>Bombardement stratégique de jour</option>";
								$choix16="<option value='16'>Bombardement stratégique de nuit</option>";
							break;
							case 12:
								$choix1="<option value='1'>Appui rapproché</option>";
								$choix6="<option value='6'>Attaque au sol</option>";
								$choix11="<option value='11'>Attaque de navire</option>";
								$choix12="<option value='12'>Bombardement naval</option>";
								$choix2="<option value='2'>Bombardement tactique</option>";
								$choix4="<option value='4'>Escorte</option>";
								$choix7="<option value='7'>Patrouille défensive</option>";
								$choix5="<option value='5'>Reconnaissance tactique</option>";
								if($Go_41)
									$choix41="<option value='41'>Appui rapproché (Coop Terrestre)</option>";
								if($Go_411)
									$choix411="<option value='411'>Attaque de navire (Coop Navale)</option>";
								if($Go_412)
									$choix412="<option value='412'>Bombardement naval (Coop Navale)</option>";
								if($Go_42)
									$choix42="<option value='42'>Bombardement tactique (Coop Terrestre)</option>";
								if($Go_44)
									$choix44="<option value='44'>Escorte (Coop Mixte)</option>";
								if($Go_47)
									$choix47="<option value='47'>Patrouille (Coop Mixte)</option>";
								if($Go_45)
									$choix45="<option value='45'>Reconnaissance tactique (Coop Mixte)</option>";
							break;
						}						
						$Grade=GetAvancement($Avancement,$country);
						if($Couv or $Escorte_PJ)
						{
							$choix4='';
							$choix7='';
						}					
						if($Admin)
						{
							$Choix=$Choix_tr.$choix3.$choix17.$choix7.$choix26.$choix29.$choix14.$choix5.$choix15.$choix21.$choix18.$choix19.$choix22.$choix27.$choix28.$choix23.$choix31.$choix13.
							$choix41.$choix42.$choix45.$choix44.$choix47.$choix411.$choix412.$choix413.$choix415.$choix417.$choix421.$choix424.$choix423.$choix429.$Choix_histo.$Choix_unite;
							/*$Choix=$Choix_tr.$choix1.$choix6.$choix2.$choix8.$choix16.$choix3.$choix17.$choix7.$choix26.$choix14.$choix29.$choix5.$choix15.$choix21.$choix18.$choix19.$choix22.$choix27.$choix28.$choix23.$choix31.
							$choix41.$choix42.$choix45.$choix47.$choix411.$choix412.$choix413.$choix414.$choix415.$choix417.$choix423.$Choix_histo.$Choix_unite;*/
						}
						elseif($Grade[1] <2)
						{
							$conseil="<p class='lead'>Votre hiérarchie vous conseille de vous entraîner avant de partir en mission de combat</p>";
							/*Mission aléatoire
							$random_miss=mt_rand(1,5);
							switch($Unite_Type)
							{
								case 1:
									$random_miss=7;
								break;
								case 2:
									$random_miss=5;
								break;
								case 3:
									$random_miss=5;
								break;
								case 4:
									$random_miss=7;
								break;
								case 5:
									$random_miss=5;
								break;
								case 6:
									$random_miss=5;
								break;
								case 7:
									$random_miss=5;
								break;
								case 9:
									$random_miss=15;
								break;
								case 10:
									$random_miss=5;
								break;
								case 12:
									$random_miss=7;
								break;
							}
							$miss_txt='Mission de combat';
							$Choix=$Choix_tr.'<option value='.$random_miss.'>'.$miss_txt.'</option>'.$Choix_histo.$Choix_unite;*/
							$Choix=$Choix_tr.$Choix_histo.$Choix_unite;
						}
						elseif($Grade[1] >10) //Capitaine
                        {
							$Choix=$Choix_tr.$choix6.$choix8.$choix16.$choix3.$choix17.$choix7.$choix26.$choix29.$choix14.$choix5.$choix15.$choix21.$choix18.$choix19.$choix22.$choix27.$choix28.$choix23.$choix31.
							$choix41.$choix42.$choix45.$choix44.$choix47.$choix429.$choix411.$choix412.$choix413.$choix415.$choix417.$choix421.$choix424.$choix423.$Choix_histo.$Choix_unite;							
						}
						elseif($Grade[1] >7) //Adjudant-Chef
                        {
							$Choix=$Choix_tr.$choix6.$choix8.$choix16.$choix17.$choix7.$choix29.$choix5.$choix15.$choix21.$choix18.$choix19.$choix22.$choix27.$choix28.$choix23.
							$choix41.$choix42.$choix45.$choix44.$choix47.$choix429.$choix411.$choix412.$choix413.$choix415.$choix417.$choix421.$choix424.$choix423.$Choix_histo.$Choix_unite;
						}
						elseif($Grade[1] >3) //Sergent
                        {
							$Choix=$Choix_tr.$choix6.$choix8.$choix16.$choix17.$choix7.$choix29.$choix15.$choix21.$choix18.$choix19.$choix22.$choix27.$choix28.$choix23.
							$choix41.$choix42.$choix45.$choix44.$choix47.$choix429.$choix411.$choix412.$choix413.$choix415.$choix417.$choix421.$choix424.$choix423.$Choix_histo.$Choix_unite;
						}
						else
							$Choix=$Choix_tr.$choix6.$choix8.$choix7.$choix15.$choix18.$choix19.$choix22.$choix23.
							$choix41.$choix42.$choix45.$choix44.$choix47.$choix429.$choix411.$choix412.$choix413.$choix415.$choix417.$choix421.$choix424.$choix423.$Choix_histo.$Choix_unite;
					/*}
					else
					{
						$prepa_txt='<b>Choix du type de mission</b>';
						$Choix=$Choix_tr.$Choix_histo.$Choix_unite;
					}*/
				}
			}//Unités réserve			
			//Retirer les crédits pour la mission		
			MoveCredits($PlayerID,1,-$Cr_Mission);
			UpdateCarac($PlayerID,"Missions_Jour",1);
			UpdateCarac($PlayerID,"Missions_Max",1);
			SetData("Pilote","Heure_Mission",$Heure,"ID",$PlayerID);					
			echo "<h1>Préparation de la mission</h1><img src='images/briefing.jpg'><h2>".$prepa_txt."</h2>
			<form action='mission1.php' method='post'><input type='hidden' name='Crm' value=".$Cr_Mission.">
			<select name='Type' class='form-control' style='width: 300px'>".$Choix."</select>
			<p><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form>".$conseil;
		}
		else
		{
			$_SESSION['Distance'] =0;
			/*echo "<h6>Vous avez déjà effectué le maximum de missions disponibles pour aujourd'hui, ou vous ne disposez pas de suffisamment de temps <img src='/images/CT0.png'> pour effectuer la mission.</h6>";
			echo "<img src='images/briefing.jpg'>";
			echo "<h6>Reposez-vous un peu ou profitez de votre temps libre et revenez demain!</h6>";*/
			echo "<h1>Repos forcé</h1><img src='images/briefing.jpg'>";
			echo "<p>Vous avez déjà effectué le maximum de missions disponibles pour aujourd'hui, ou vous ne disposez pas de suffisamment de temps <img src='/images/CT0.png'> pour effectuer la mission.</p>";
			echo "<p>Reposez-vous un peu ou profitez de votre temps libre et revenez demain!</p>";
			echo "<a href='index.php' class='btn btn-default' title='Retour'>Retour</a>";
		}
		$_SESSION['start_mission']=false;
	}
	else
		echo '<p>Une fois le départ en mission confirmé, vous ne pouvez accéder aux autres menus du jeu sous peine de réinitialisation de la mission.</p>';
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';