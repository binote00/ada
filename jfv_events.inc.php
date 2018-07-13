<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./jfv_nav.inc.php');
include_once('./jfv_msg.inc.php');

function Update($Date)
{
	$con=dbconnecti();
	$res_conf_up=mysqli_query($con,"SELECT ID,Date FROM Conf_Update WHERE ID IN(1,2)");
	mysqli_close($con);
	if($res_conf_up)
	{
		while($datarcu=mysqli_fetch_array($res_conf_up,MYSQLI_ASSOC)) 
		{
			if($datarcu['ID'] ==1)
				$Update_date=$datarcu['Date'];
			elseif($datarcu['ID'] ==2)
				$Campagne=$datarcu['Date'];
		}
		mysqli_free_result($res_conf_up);
	}
	if($Date >$Update_date)
	{
		if($Campagne)
		{
			if($Date <'1941-01-01')
			{
				$Axe_nations='1,6,24';
				$Allie_nations='2,3,4,5,35';
			}
			else
			{
				$Axe_nations='1,6,9,15,18,19,20,24';
				$Allie_nations='2,3,4,5,7,8,10,35';
			}
			$con=dbconnecti();
			$ok_up=mysqli_query($con,"UPDATE Conf_Update SET Date=ADDDATE(Date,1) WHERE ID=2");
			$ok_up2=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=100,Co_Lieu_Mission=0");//Reset Nations
			//$Score_Axe=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag IN (".$Axe_nations.")"),0);
			//$Score_Allie=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag IN (".$Allie_nations.")"),0);
			$up2=mysqli_query($con,"UPDATE Pays SET Score=Score+(SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag IN (".$Axe_nations.")) WHERE ID=1");
			$up3=mysqli_query($con,"UPDATE Pays SET Score=Score+(SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag IN (".$Allie_nations.")) WHERE ID=2");
			$result3=mysqli_query($con,"UPDATE Avion SET Etat=1 WHERE Engagement='$Campagne'");
			$updateprodnav=mysqli_query($con,"UPDATE Cible SET Stock=1 WHERE mobile=5 AND Production=1 AND `Date`<='$Campagne'");
			$updateprod=mysqli_query($con,"UPDATE Cible SET Stock=Stock+(Production/DATEDIFF(Retrait,`Date`)*
			(((SELECT Industrie FROM Lieu WHERE ID=Usine1)+IF(Usine2 IS NOT NULL,(SELECT Industrie FROM Lieu WHERE ID=Usine2),100)+IF(Usine3 IS NOT NULL,(SELECT Industrie FROM Lieu WHERE ID=Usine3),100))/3)/100)
			WHERE Unit_ok=1 AND Production >0 AND Stock < Production AND ('$Campagne' BETWEEN `Date` AND Retrait) AND DATEDIFF(Retrait,`Date`) >0");
			$updateprod2=mysqli_query($con,"UPDATE Avion SET Stock=Stock+(Production/DATEDIFF(Fin_Prod,Engagement)*
			(((SELECT Industrie FROM Lieu WHERE ID=Usine1)+IF(Usine2 IS NOT NULL,(SELECT Industrie FROM Lieu WHERE ID=Usine2),100)+IF(Usine3 IS NOT NULL,(SELECT Industrie FROM Lieu WHERE ID=Usine3),100))/3)/100)
			WHERE Etat=1 AND Prototype=0 AND Production >0 AND ('$Campagne' BETWEEN Engagement AND Fin_Prod) AND DATEDIFF(Fin_Prod,Engagement) >0");
			mysqli_close($con);
			if(!$ok_up)
				mail('binote@hotmail.com','Aube des Aigles: Update Date','Erreur de mise à jour de la Date Campagne :'.$Score);
			if(!$ok_up2)
				mail('binote@hotmail.com','Aube des Aigles: Update Error','Erreur de mise à jour du pool ouvrier et des missions de coop de nation');
			if(!$up2)
				mail('binote@hotmail.com','Aube des Aigles: Update Error','Erreur de mise à jour du Score Axe');
			if(!$up3)
				mail('binote@hotmail.com','Aube des Aigles: Update Error','Erreur de mise à jour du Score Allié');
			if(!$result3)
				mail('binote@hotmail.com','Aube des Aigles: Update Error','Erreur de mise à jour des avions en service');
			if(!$updateprod)
				mail('binote@hotmail.com','Aube des Aigles: Update Error','Erreur de mise à jour de la production des véhicules');
			if(!$updateprod2)
				mail('binote@hotmail.com','Aube des Aigles: Update Error','Erreur de mise à jour de la production des avions');
			/*Chk_Event($Campagne);
			usleep(10);*/
			Attrition_IA();
			InitDay();
			usleep(10);
			Infrastructures($Campagne);
			usleep(10);
			SetData("Conf_Update","Date",$Date,"ID",1);
		}
	}
}

/*Fortification=100
	2571,383,112,24,2570,2612,2569,2616,61,108,2613,2615,2614,23,2658,149,2697,240,219,366,2492,577,2699,389,35,2639,1116,2640,2605,2610,32,2728,30,2698,2169,107,1215,2657,114,409,2606,2611,630
*/
/*function ProductionVeh($Campagne)
{
	$query="UPDATE Cible SET Stock=Stock+(Production/DATEDIFF(Retrait,`Date`)*
	(((SELECT Industrie FROM Lieu WHERE ID=Usine1)+IF(Usine2 IS NOT NULL,(SELECT Industrie FROM Lieu WHERE ID=Usine2),100)+IF(Usine3 IS NOT NULL,(SELECT Industrie FROM Lieu WHERE ID=Usine3),100))/3)/100)
	WHERE Unit_ok=1 AND Production >0 AND ('1940-04-01' BETWEEN `Date` AND Retrait) AND DATEDIFF(Retrait,`Date`) >0";
	$con=dbconnecti();
	$updateprod=mysqli_query($con,$query);
	mysqli_close($con);
	
	//INIT GAME
	UPDATE Cible SET Stock=0,Repare=0

	UPDATE Cible SET Stock=Production
	WHERE Unit_ok=1 AND Production >0 AND Retrait <='1940-04-01'

	UPDATE Cible SET Stock=Production
	WHERE Unit_ok=1 AND Production >0 AND mobile=5 AND `Date`<='1940-04-01'

	UPDATE Cible SET Stock=Stock+(Production/DATEDIFF(Retrait,`Date`)*DATEDIFF('1940-04-01',`Date`))
	WHERE Unit_ok=1 AND Production >0 AND Stock=0 AND `Date` <'1940-04-01' AND Retrait >'1940-04-01'
	
	UPDATE Avion SET Stock=0,Reserve=0

	UPDATE Avion SET Stock=Production
	WHERE Production >0 AND Fin_Prod <='1940-04-01'

	UPDATE Avion SET Stock=Stock+(Production/DATEDIFF(Fin_Prod,Engagement)*DATEDIFF('1940-04-01',Engagement))
	WHERE Production >0 AND Stock=0 AND Engagement <'1940-04-01' AND Fin_Prod >'1940-04-01'
}*/

/*
INIT MATERIEL AERIEN
UPDATE Unit as u SET Avion1=(SELECT a.ID
FROM Avion AS a
WHERE a.Pays =u.Pays
AND a.Type =u.Type
AND a.Premium =0
AND a.Etat =1
AND a.Stock >0
ORDER BY a.Rating ASC LIMIT 1),
Avion2=(SELECT a.ID
FROM Avion AS a
WHERE a.Pays =u.Pays
AND a.Type =u.Type
AND a.Premium =0
AND a.Etat =1
AND a.Stock >0
ORDER BY a.Rating ASC LIMIT 1),
Avion3=(SELECT a.ID
FROM Avion AS a
WHERE a.Pays =u.Pays
AND a.Type =u.Type
AND a.Premium =0
AND a.Etat =1
AND a.Stock >0
ORDER BY a.Rating ASC LIMIT 1)
WHERE u.Type !=8
*/

function Attrition_IA()
{
	$Axe=array(1,6,9,15,18,19,20,24);
	$Allies=array(2,3,4,5,7,8,10,35,36);
	$con=dbconnecti();
	$resultat=mysqli_query($con,"SELECT r.ID as Reg,r.Bataillon,r.Division,r.Lieu_ID,r.Placement,r.Pays,p.Faction,l.Flag,l.Flag_Air,l.Flag_Route,l.Flag_Gare,l.Flag_Pont,l.Flag_Port,l.Flag_Usine,l.Flag_Radar,l.Flag_Plage,c.Categorie
	FROM Regiment_IA as r,Lieu as l,Cible as c,Pays as p WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays=p.ID
	AND c.mobile NOT IN(4,5) AND l.Flag<>r.Pays AND r.Placement<>8 AND r.Vehicule_Nbr >0 AND r.Ravit=0 AND r.Matos<>29");
	if($resultat)
	{
		while($dataat=mysqli_fetch_array($resultat,MYSQLI_ASSOC)) 
		{
			$Attrition=true;
			$Lieu_ID=$dataat['Lieu_ID']; 
			if($dataat['Placement'] ==1)
				$Flag_Zone=$dataat['Flag_Air'];
			elseif($dataat['Placement'] ==2)
				$Flag_Zone=$dataat['Flag_Route'];
			elseif($dataat['Placement'] ==3)
				$Flag_Zone=$dataat['Flag_Gare'];
			elseif($dataat['Placement'] ==4)
				$Flag_Zone=$dataat['Flag_Port'];
			elseif($dataat['Placement'] ==5)
				$Flag_Zone=$dataat['Flag_Pont'];
			elseif($dataat['Placement'] ==6)
				$Flag_Zone=$dataat['Flag_Usine'];
			elseif($dataat['Placement'] ==7)
				$Flag_Zone=$dataat['Flag_Radar'];
			elseif($dataat['Placement'] ==11)
				$Flag_Zone=$dataat['Flag_Plage'];
			else
				$Flag_Zone=$dataat['Flag'];
			if(($dataat['Faction'] ==1 and in_array($Flag_Zone,$Axe)) or ($dataat['Faction'] ==2 and in_array($Flag_Zone,$Allies)))
				$Attrition=false;
			else
			{
				if($dataat['Division'] >0)
				{
					if($dataat['Categorie'] ==4)
					{
						$Lieu_Veh_Allies=false;
						$Lieu_Veh_Allies=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID=".$Lieu_ID." AND Pays=".$dataat['Pays']." AND ID<>".$dataat['Reg']." AND Vehicule_Nbr >0"),0);
						if($Lieu_Veh_Allies)$Attrition=false;
					}
					else
					{
						$Lieu_Veh_Cdt=false;
						$Lieu_Veh_Cdt=mysqli_result(mysqli_query($con,"SELECT r.Lieu_ID FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Division=".$dataat['Division']." AND c.Categorie=4 AND r.Vehicule_Nbr=1"),0);
						if($Lieu_Veh_Cdt ==$Lieu_ID)$Attrition=false;
					}
				}
				/*if($dataat['Bataillon'] and $Attrition)
				{
					$Sec_Med=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sections WHERE OfficierID='".$dataat['Bataillon']."' AND SectionID=1"),0);
					if($Sec_Med) //Section Médicale du PC
					{
						$Base_Reg=mysqli_result(mysqli_query($con,"SELECT Lieu_ID FROM Regiment WHERE Officier_ID='".$dataat['Bataillon']."'"),0);
						$Grade_Off_Bat=mysqli_result(mysqli_query($con,"SELECT Avancement FROM Officier WHERE ID='".$dataat['Bataillon']."'"),0);
						if($Base_Reg ==$Lieu_ID)
						{
							if($Grade_Off_Bat >=50000)
								$Attrition=false;
							elseif($Grade_Off_Bat >=25000 and $dataat['Categorie'] !=3)
								$Attrition=false;
							elseif($Grade_Off_Bat >=10000 and $dataat['Categorie'] !=3 and $dataat['Categorie'] !=2 and $dataat['Categorie'] !=8)
								$Attrition=false;
							elseif($Grade_Off_Bat >=5000 and $dataat['Categorie'] !=3 and $dataat['Categorie'] !=2 and $dataat['Categorie'] !=8 and $dataat['Categorie'] !=15)
								$Attrition=false;
						}
					}
				}*/
			}
			if($Attrition)
				$Reg_Atr[]=$dataat['Reg'];
		}
		mysqli_free_result($resultat);
		unset($dataat);
		if(is_array($Reg_Atr))
		{
			if(array_count_values($Reg_Atr) >0)
			{
				$Reg_Atr_in=implode(',',$Reg_Atr);
				$reset=mysqli_query($con,"UPDATE Regiment_IA SET Vehicule_Nbr=Vehicule_Nbr-1,Mission_Type_D=23,Mission_Lieu_D=Lieu_ID WHERE ID IN(".$Reg_Atr_in.")");
				/*if($reset)
					return '<br>Attrition_IA : Regiments IA ('.$Reg_Atr_in.') sur lieux ennemis ont subi attrition!';
				else
					return '<br>Attrition_IA : ERREUR';*/
			}
			/*else
				return '<br>Attrition_IA : Aucun Régiment IA soumis à une attrition';*/
		}
	}
	mysqli_close($con);
}

function Infrastructures($Date)
{
	$Saison=GetSaison($Date);
	if($Date >'1943-01-01')
		$Rockets_prod=",Stock_Bombes_80=Stock_Bombes_80+'$up_stock'";
	else
		$Rockets_prod='';
	$con=dbconnecti();
	$resultl=mysqli_query($con,"SELECT ID,Latitude,Longitude,ValeurStrat,Industrie,Usine_muns,Flag,Flag_Usine,boostProd,Garnison FROM Lieu WHERE Last_Attack <'$Date'");
	$resultinf=mysqli_query($con,"SELECT ID,BaseAerienne,Tour,QualitePiste,TypeIndus,Industrie,NoeudF_Ori,NoeudF,Pont_Ori,Pont,Port_Ori,Port,Radar_Ori,Radar,Auto_repare FROM Lieu WHERE Last_Attack BETWEEN ('$Date' - INTERVAL 365 DAY) AND ('$Date' - INTERVAL 3 DAY) AND Auto_repare >0");
	$upstockoil=mysqli_query($con,"UPDATE Lieu SET Stock_Essence_100=Stock_Essence_100+(Industrie*Oil*20*(1+(boostProd/10))),Stock_Essence_87=Stock_Essence_87+(Industrie*Oil*100*(1+(boostProd/10))),Stock_Essence_1=Stock_Essence_1+(Industrie*Oil*20*(1+(boostProd/10))) WHERE Oil >0 AND Industrie >0");
	//Infras détruites et Raffineries
	if($resultinf)
	{
		while($datainf=mysqli_fetch_array($resultinf,MYSQLI_ASSOC)) 
		{
			$query_inf=false;
			if($datainf['BaseAerienne'])
			{
				if($datainf['Tour'] ==0)
					$query_inf.=",Tour=1";
				if($datainf['QualitePiste'] ==0)
					$query_inf.=",QualitePiste=1";
			}
			if($datainf['TypeIndus'] and $datainf['Industrie'] ==0)
				$query_inf.=",Industrie=1";
			if($datainf['NoeudF_Ori'] and $datainf['NoeudF'] ==0)
				$query_inf.=",NoeudF=1";
			if($datainf['Pont_Ori'] and $datainf['Pont'] ==0)
				$query_inf.=",Pont=1";
			if($datainf['Port_Ori'] and $datainf['Port'] ==0)
				$query_inf.=",Port=1";
			if($datainf['Radar_Ori'] and $datainf['Radar'] ==0)
				$query_inf.=",Radar=1";
			$updateinf=mysqli_query($con,"UPDATE Lieu SET Meteo=0".$query_inf." WHERE ID=".$datainf['ID']);
		}
		mysqli_free_result($resultinf);
		unset($datainf);
	}
	if($resultl)
	{
		while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
		{
            $query_add='';
			$ID=$datal['ID'];
			if($datal['Industrie'] >0 and $datal['Usine_muns'] >0 and $datal['Flag'] ==$datal['Flag_Usine'])
			{
			    $boostProd=1+($datal['boostProd']/10);
				$up_max=$datal['Usine_muns']*$datal['Industrie'];
				$up_stock=mt_rand(1,$up_max)*$boostProd;
				$up_stock8=$up_stock*15*$boostProd;
				$up_stock20_40=$up_stock*2*$boostProd;
				if($datal['Flag'] ==7)
					$up_stock13=$up_stock*10*$boostProd;
				else
					$up_stock13=$up_stock*5*$boostProd;
				if($datal['Usine_muns'] >4)
				{
					$up_stock2=mt_rand(0,$up_max);
					/*$upstock=mysqli_query($con,"UPDATE Lieu SET Stock_Munitions_50=Stock_Munitions_50+'$up_stock',Stock_Munitions_60=Stock_Munitions_60+'$up_stock',Stock_Munitions_75=Stock_Munitions_75+'$up_stock',
					Stock_Munitions_90=Stock_Munitions_90+'$up_stock',Stock_Munitions_105=Stock_Munitions_105+'$up_stock',Stock_Munitions_125=Stock_Munitions_125+'$up_stock',Stock_Munitions_150=Stock_Munitions_150+'$up_stock', 
					Stock_Bombes_50=Stock_Bombes_50+'$up_stock'".$Rockets_prod.",Stock_Bombes_125=Stock_Bombes_125+'$up_stock',Stock_Bombes_250=Stock_Bombes_250+'$up_stock',Stock_Bombes_300=Stock_Bombes_300+'$up_stock',Stock_Bombes_400=Stock_Bombes_400+'$up_stock',
					Stock_Bombes_500=Stock_Bombes_500+'$up_stock',Stock_Bombes_800=Stock_Bombes_800+'$up_stock',Stock_Bombes_1000=Stock_Bombes_1000+'$up_stock2',Stock_Bombes_2000=Stock_Bombes_2000+'$up_stock2' WHERE ID='$ID'");*/
					//Stock_Munitions_200=Stock_Munitions_200+'$up_stock2',Stock_Munitions_300=Stock_Munitions_300+'$up_stock2',Stock_Munitions_360=Stock_Munitions_360+'$up_stock2',
                    $query_add.=",Stock_Munitions_50=Stock_Munitions_50+'$up_stock',Stock_Munitions_60=Stock_Munitions_60+'$up_stock',Stock_Munitions_75=Stock_Munitions_75+'$up_stock',
					Stock_Munitions_90=Stock_Munitions_90+'$up_stock',Stock_Munitions_105=Stock_Munitions_105+'$up_stock',Stock_Munitions_125=Stock_Munitions_125+'$up_stock',Stock_Munitions_150=Stock_Munitions_150+'$up_stock', 
					Stock_Bombes_50=Stock_Bombes_50+'$up_stock'".$Rockets_prod.",Stock_Bombes_125=Stock_Bombes_125+'$up_stock',Stock_Bombes_250=Stock_Bombes_250+'$up_stock',Stock_Bombes_300=Stock_Bombes_300+'$up_stock',Stock_Bombes_400=Stock_Bombes_400+'$up_stock',
					Stock_Bombes_500=Stock_Bombes_500+'$up_stock',Stock_Bombes_800=Stock_Bombes_800+'$up_stock',Stock_Bombes_1000=Stock_Bombes_1000+'$up_stock2',Stock_Bombes_2000=Stock_Bombes_2000+'$up_stock2'";
				}
                $query_add.=",Stock_Munitions_8=Stock_Munitions_8+'$up_stock8',Stock_Munitions_13=Stock_Munitions_13+'$up_stock13',Stock_Munitions_20=Stock_Munitions_20+'$up_stock20_40',
				Stock_Munitions_30=Stock_Munitions_30+'$up_stock20_40',Stock_Munitions_40=Stock_Munitions_40+'$up_stock20_40'";
				/*$upstock1=mysqli_query($con,"UPDATE Lieu SET Stock_Munitions_8=Stock_Munitions_8+'$up_stock8',Stock_Munitions_13=Stock_Munitions_13+'$up_stock13',Stock_Munitions_20=Stock_Munitions_20+'$up_stock20_40',
				Stock_Munitions_30=Stock_Munitions_30+'$up_stock20_40',Stock_Munitions_40=Stock_Munitions_40+'$up_stock20_40' WHERE ID='$ID'");*/
			}
			/*if($datal['ValeurStrat'] >0){
                $Max_Garnison=($datal['ValeurStrat']*200)+100;
                if($datal['Garnison'] < $Max_Garnison)
                    $query_add=',Garnison=Garnison+10';
            }*/
			$Meteo_ar=GetMeteo($Saison,$datal['Latitude'],$datal['Longitude']);
			$Meteo=$Meteo_ar[1];
			$upmeteo=mysqli_query($con,"UPDATE Lieu SET Meteo='$Meteo',Meteo_Hour=7".$query_add." WHERE ID='$ID'");
			unset($Meteo_ar);
		}
		mysqli_free_result($resultl);
		unset($data);
	}
	if(!$resultinf)
		mail('binote@hotmail.com','Aube des Aigles: Update AutoRepare','Erreur de réparation des infras détruites');
	if(!$upstockoil)
		mail('binote@hotmail.com','Aube des Aigles: Update Stock_Oil','Erreur de mise à jour des stocks de carburant');
}
//Nécessite l'appel de l'include "jfv_const.inc.php" pour la constante '$Date_debut'.
function Chk_Event($Campagne)
{	
	/*$Date_time=$_SERVER['REQUEST_TIME'];
	$Date_debut=GetData("Conf_Update","ID",4,"Date");
	$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
	$Date_start=strtotime($Date_debut);
	$tab=diff_date($Date_time,$Date_start);
	$Date_final="20120510"+($tab["mois"]*100)+($tab["semaine"]*7)+$tab["jour"];*/	
	//if($Date_time > $Date_Campagne)
	//{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Date,Type,Lieu,Pays,Unite,Avion,Avion_Nbr FROM Event_Historique WHERE Date='$Campagne'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				Do_Event($data['Date'],$data['Type'],$data['Lieu'],$data['Pays'],$data['Unite'],$data['Avion'],$data['Avion_Nbr']);
				//mail ('binote@hotmail.com', 'Aube des Aigles: Chk_Event' , 'Event trouvé :'.$data['Type']);
			}
			mysqli_free_result($result);
			unset($result);
		}
		/*else
			mail ('binote@hotmail.com', 'Aube des Aigles: Chk_Event' , 'Aucun Event trouvé');*/
	//}
}
//Type : 1=Bataille, 2=Bombardement, 40=Occupation, 41=Mouvement, 21=Renfort, 50=Nouvel Avion, 51=Nouvelle Unité
function Do_Event($Date,$Type,$Lieu,$Pays,$Unit=0,$avion=0,$avion_Nbr=0)
{
	switch($Type)
	{
		case 21:	//Renfort($Lieu détermine la série d'avion : 1,2 ou 3. Si 0, tous les avions sont remplacés)
			if($Unit)
				$Base=GetData("Unit","ID",$Unit,"Base");
			if($avion)
			{
				$con=dbconnecti();	
				$result2=mysqli_query($con,"SELECT Bombe,Bombe_Nbr FROM Avion WHERE ID='$avion'");
				mysqli_close($con);
				if($result2)
				{
					while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
					{
						$Bombe=$data['Bombe'];
						$Bombe_Nbr=$data['Bombe_Nbr'];
					}
					mysqli_free_result($result2);
				}
			}
			//$avion_Nbr=1; //Edit : afin de ne pas perturber la production
			if($Lieu ==0)
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
			AddEvent("Avion",141,$avion,1,$Unit,$Base,$avion_Nbr);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event Renfort '.$Date,'Unité '.$Unit.' Avion'.$Lieu.' : '.$avion_Nbr.' '.$avion);
		break;
		case 31:	/*Mutation PNJ
			SetData("Pilote_IA","Unit",$Unit,"ID",$avion);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event Mutation '.$Date,'Pilote '.$avion.' est muté au '.$Unit);*/
		break;
		case 40:	/*Occupation
			$Faction_eni=GetData("Pays","ID",$Pays,"Faction");
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Latitude,Longitude,BaseAerienne,NoeudF_Ori,Pays FROM Lieu WHERE ID='$Lieu'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$BaseAerienne=$data['BaseAerienne'];
					$Lat_base=$data['Latitude'];
					$Long_base=$data['Longitude'];
					$NoeudF_Ori=$data['NoeudF_Ori'];
					$Nation=$data['Pays'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($NoeudF_Ori >0 and $Nation ==8)
				SetData("Lieu","NoeudF",0,"ID",$Lieu);
			if($BaseAerienne)
			{
				$units_auto_move=false;
				$Lat_base_min=$Lat_base-2.00;
				$Lat_base_max=$Lat_base+2.00;
				$Long_base_min=$Long_base-3.00;
				$Long_base_max=$Long_base+3.00;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT u.ID,u.Pays,u.Base_Ori FROM Unit as u, Pays as p WHERE u.Base='$Lieu' AND u.Pays=p.ID AND p.Faction<>'$Faction_eni'");
				mysqli_close($con);	
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Pays_unit=$data['Pays'];
						$Unit_id=$data['ID'];
						$Base_Ori=$data['ID'];
						$con=dbconnecti();
						$Dest_unit=mysqli_result(mysqli_query($con,"SELECT ID FROM Lieu WHERE Flag='$Pays_unit' AND BaseAerienne >0
						AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') 
						ORDER BY RAND() LIMIT 1"),0);
						mysqli_close($con);	
						if(!$Dest_unit)$Dest_unit=$Base_Ori;
						SetData("Unit","Base",$Dest_unit,"ID",$Unit_id);
						SetData("Flak","Lieu",$Dest_unit,"Unit",$Unit_id);
						$units_auto_move[]=$Unit_id;
					}
					mysqli_free_result($result);
					if($units_auto_move)
					{
						$units_move=implode(", ",$units_auto_move);
						mail('binote@hotmail.com','Aube des Aigles: Do_Event Occupation Auto-Move '.$Date,"Unités (".$units_move.") déplacées depuis le Lieu ".$Lieu);
					}
				}
			}
			$con=dbconnecti();
			$reset1=mysqli_query($con,"UPDATE Lieu SET Occupant='$Pays',Recce=0,Recce_PlayerID=0,DefenseAA_temp=DefenseAA WHERE ID='$Lieu'");
			$reset2=mysqli_query($con,"UPDATE Pilote SET Escorte=0,Couverture=0,Couverture_Nuit=0,Cible=0 WHERE Escorte='$Lieu' OR Couverture='$Lieu' OR Couverture_Nuit='$Lieu'");
			$reset_tr1=mysqli_query($con,"UPDATE Flak,Armes,Unit SET Flak.Unit=0 WHERE Flak.Unit=Unit.ID AND Flak.DCA_ID=Armes.ID AND Flak.Lieu='$Lieu' AND Armes.Transport=0 AND Unit.Pays<>'$Pays'");
			$reset_tr2=mysqli_query($con,"DELETE FROM Flak WHERE Unit=0");
			mysqli_close($con);
			unset($reset);
			//mail('binote@hotmail.com','Aube des Aigles: Do_Event Occupation '.$Date,'Pays '.$Pays.' occupe '.$Lieu);*/
		break;
		case 41:	/*Mouvement
			//Camouflage 0 et Piste >=50 si unité étrangère occupe le terrain
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Zone,Occupant,QualitePiste FROM Lieu WHERE ID='$Lieu'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Zone=$data['Zone'];
					$Occupant=$data['Occupant'];
					$QualitePiste=$data['QualitePiste'];
				}
				mysqli_free_result($result);
			}
			if((IsAllie($Occupant) and  IsAxe($Pays)) OR (IsAxe($Occupant) and IsAllie($Pays)))
			{
				if($QualitePiste <50)
					SetData("Lieu","QualitePiste",50,"ID",$Lieu);
				SetData("Lieu","Camouflage",0,"ID",$Lieu);
			}
			if($Zone !=6)
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
			//mail('binote@hotmail.com','Aube des Aigles: Do_Event Mouvement '.$Date,'Unité '.$Unit.' fait Mouvement vers la base de '.$Lieu);*/
		break;
		case 42:	//Capitulation
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE Pays='$Pays'");
			$reset2=mysqli_query($con,"UPDATE Unit SET Etat=0 WHERE Pays='$Pays'");
			mysqli_close($con);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event Capitulation '.$Date,'Pays '.$Pays.' capitule.');
		break;
		case 43:	//Alliance
			if($Pays ==6 or $Pays ==8)
			{
				SetData("Unit","Etat",1,"Pays",$Pays);
				SetData("Unit","Etat",0,"Type",8);
			}
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Unit SET Commandant=NULL,Officier_Adjoint=NULL,Officier_Technique=NULL,Mission_Lieu=0,Mission_Type=0,Mission_Lieu_D=0,Mission_Type_D=0 WHERE Pays='$Pays'");
			$reset1=mysqli_query($con,"UPDATE Pays SET Faction='$avion_Nbr' WHERE ID='$Pays'");
			mysqli_close($con);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event Alliance '.$Date,'Pays '.$Pays.' rejoint la faction '.$avion_Nbr);
		break;
		case 50:	//Nouvel Avion
			/*SetData("Avion","Etat",1,"ID",$avion);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event Nouvel Avion '.$Date,'Avion '.$avion);*/
		break;
		case 51:	//Nouvelle Unité
			SetData("Unit","Etat",1,"ID",$Unit);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event Nouvelle Unité '.$Date,'Unité '.$Unit.' arrive sur la base de '.$Lieu);
		break;
		case 52:	/*Unité Dissoute
			mail('binote@hotmail.com','Aube des Aigles: Do_Event Unité Dissoute '.$Date,'Unité '.$Unit.' dissoute');
			$Msg_diss="Votre unité a été dissoute.\n Vous avez été reversé dans une unité de réserve en attendant votre nouvelle affectation.\n Veuillez choisir votre prochaine unité via la demande de mutation classique.";
			$Sujet_diss="Dissolution de votre unité";
			//Affectation PJ restants
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Unit SET Etat=0,Commandant=NULL,Officier_Adjoint=NULL,Officier_Technique=NULL,Mission_Lieu=0,Mission_Type=0,Mission_Lieu_D=0,Mission_Type_D=0,Porte_avions=0 WHERE ID='$Unit'");
			$reset2=mysqli_query($con,"UPDATE Pilote_IA SET Avion=0,Cible=0,Couverture=0,Couverture_Nuit=0,Escorte=0,Alt=0,Task=0 WHERE Unit='$Unit'");
			$result=mysqli_query($con,"SELECT ID,Pays FROM Pilote WHERE Unit='$Unit'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Pilote_ID=$data['ID'];
					$Pilote_Pays=$data['Pays'];
					switch($Pilote_Pays)
					{
						case 1:
							$Reserve=192;
							$Expediteur=4;
						break;
						case 2:
							$Reserve=193;
							$Expediteur=238;
						break;
						case 3:
							$Reserve=193;
							$Expediteur=1;
							SetData("Pilote","Pays",2,"ID",$Pilote_ID);
						break;
						case 4:
							$Reserve=191;
							$Expediteur=2;
						break;
						case 6:
							$Reserve=194;
							$Expediteur=425;
						break;
						case 7:
							$Reserve=388;
							$Expediteur=1;
						break;
						case 8:
							$Reserve=387;
							$Expediteur=1;
						break;
						case 9:
							$Reserve=389;
							$Expediteur=1;
						break;
					}
					SetData("Pilote","Unit",$Reserve,"ID",$Pilote_ID);
					SendMsg($Pilote_ID,$Expediteur,$Msg_diss,$Sujet_diss);
					//mail('binote@hotmail.com','Aube des Aigles: Chk_Event','Event trouvé :'.$data['Type']);
				}
				mysqli_free_result($result);
			}*/
		break;
		case 53: //Unité renommée
			//$avion=Nouvelle unité
			if($Unit and $avion)
			{
				$Rep_O=GetData("Unit","ID",$Unit,"Reputation");
				$Nouveau_Type=GetAvionType(GetData("Unit","ID",$avion,"Type"));
				$Msg_diss='Votre unité a été reformée sous un différent nom, en tant qu unité de '.$Nouveau_Type.'. \n Si vous occupiez une fonction de Staff, vous devez postuler à nouveau.';
				$Sujet_diss='Unité renommée';
				//Affectation PJ restants
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Unit SET Etat=0,Commandant=NULL,Officier_Adjoint=NULL,Officier_Technique=NULL,Mission_Lieu=0,Mission_Type=0,Mission_Lieu_D=0,Mission_Type_D=0 WHERE ID='$Unit'");
				$reset2=mysqli_query($con,"UPDATE Unit SET Etat=1,Reputation='$Rep_O' WHERE ID='$avion'");
				$result=mysqli_query($con,"SELECT ID FROM Pilote WHERE Unit='$Unit'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Pilote_ID=$data['ID'];
						SetData("Pilote","Unit",$avion,"ID",$Pilote_ID);
						SendMsgOff($Pilote_ID,0,$Msg_diss,$Sujet_diss,0,3);
						//mail('binote@hotmail.com','Aube des Aigles: Chk_Event','Event trouvé :'.$data['Type']);
					}
					mysqli_free_result($result);
				}
				mail('binote@hotmail.com','Aube des Aigles: Do_Event Unité renommée '.$Date,'Unité '.$Unit.' dissoute');
			}
		break;
		case 54:	//Unité changement de type
			if($Unit and $avion_Nbr)
				SetData("Unit","Type",$avion_Nbr,"ID",$Unit);
		case 55:	//Piste améliorée
			if($Lieu and $avion_Nbr and $avion)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Lieu SET BaseAerienne='$avion_Nbr',LongPiste='$avion' WHERE ID='$Lieu'");
				mysqli_close($con);
			}
		break;
		case 56:	//Production Transférée
			if($avion and $Lieu)
				SetData("Avion","Usine".$avion_Nbr,$Lieu,"ID",$avion);
		break;
		case 57:	//Nouveau PA
			if($Unit and $Lieu)
				SetData("Unit","Porte_avions",$Lieu,"ID",$Unit);
		break;
		case 60:	//Victoire PNJ
			/*UpdateData("Pilote_IA","Victoires",$avion_Nbr,"ID",$avion);
			UpdateData("Pilote_IA","Pilotage",$avion_Nbr,"ID",$avion);
			UpdateData("Pilote_IA","Tactique",$avion_Nbr,"ID",$avion);
			UpdateData("Pilote_IA","Tir",$avion_Nbr,"ID",$avion);*/
			//mail('binote@hotmail.com','Aube des Aigles: Do_Event Nouvelle Victoire '.$Date ,'As '.$avion.' a abattu '.$avion_Nbr.' avions ennemis ce jour');
		break;
		case 61:	//Promotion PNJ
			//SetData("Pilote_IA","Avancement",$Lieu,"ID",$avion);
			//mail('binote@hotmail.com','Aube des Aigles: Do_Event Nouvelle Promotion '.$Date ,'As '.$avion.' a été promu au grade supérieur ce jour');
		break;
		case 62:	/*Reput PNJ
			SetData("Pilote_IA","Reputation",$Lieu,"ID",$avion);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event Nouvelle Fonction '.$Date ,'As '.$avion.' a été promu à une fonction supérieure ce jour');*/
		break;
		case 64:	/*Blesse PNJ
			SetData("Pilote_IA","Actif",0,"ID",$avion);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event As Blessé '.$Date ,'As '.$avion.' a été blessé ce jour');*/
		break;
		case 65:	//Mort PNJ
			if($avion)
				SetData("Pilote_IA","Actif",0,"ID",$avion);
			mail('binote@hotmail.com','Aube des Aigles: Do_Event As Tué '.$Date,'As '.$avion.' a été tué ce jour');
		break;
	}
}

function InitDay()
{
	$con=dbconnecti();
	$resetreg=mysqli_query($con,"UPDATE Regiment_IA SET Move=0,Visible=0,Mission_Lieu_D=0,Mission_Type_D=0,Ravit=0,Bomb_IA=0,Arti_IA=0,Atk_IA=0,Atk=0");
	$resetacart=mysqli_query($con,"UPDATE Regiment_IA as r,Cible as c SET r.Position=5 WHERE r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Position IN(0,4,8,9) AND ((c.Categorie=8 AND r.Atk_time < NOW() - INTERVAL 1 DAY AND r.Move_time < NOW() - INTERVAL 1 DAY) OR (c.Type=8 AND r.Atk_time < NOW() - INTERVAL 1 DAY))");
	$resetacmob=mysqli_query($con,"UPDATE Regiment_IA as r,Cible as c SET r.Position=1 WHERE r.Vehicule_ID=c.ID AND c.Categorie IN(2,3,15) AND r.Vehicule_Nbr >0 AND r.Position IN(0,4,8,9) AND r.Atk_time < NOW() - INTERVAL 1 DAY");
	$resetacinf=mysqli_query($con,"UPDATE Regiment_IA as r,Cible as c SET r.Position=10 WHERE r.Vehicule_ID=c.ID AND c.Categorie IN(5,6,9) AND r.Vehicule_Nbr >0 AND r.Position IN(0,4,8,9) AND r.Atk_time < NOW() - INTERVAL 1 DAY AND r.Move_time < NOW() - INTERVAL 1 DAY");
	/*$result1=mysqli_query($con,"UPDATE Regiment SET Position=9 WHERE Position=8");
	$result2=mysqli_query($con,"UPDATE Regiment SET Bomb_IA=0,Arti_IA=0,Atk_IA=0");*/
	$result3=mysqli_query($con,"UPDATE Unit SET U_Chargeurs=1,U_Blindage=0,U_Camo=0,U_Moteurs=0,U_Purge=0,Mission_Lieu_D=0,Mission_Type_D=0,Mission_IA=0,Recce=0");
	$result4=mysqli_query($con,"UPDATE Unit SET Mission_Lieu=0,Mission_Type=0 WHERE Mission_Type NOT IN(4,7,17,32)");
	//$result5=mysqli_query($con,"UPDATE Officier SET Mission_Lieu_D=0,Mission_Type_D=0,Aide=0 WHERE Mission_Lieu_D >0 OR Aide >0");
	$result6=mysqli_query($con,"UPDATE Pilote_IA SET Pilotage=Pilotage+0.05,Acrobatie=Acrobatie+0.05,Navigation=Navigation+0.05,Tactique=Tactique+0.05,Tir=Tir+0.05,Vue=Vue+0.05 WHERE Pilotage <100");			
	$result7=mysqli_query($con,"UPDATE Pilote_IA SET Endurance=0 WHERE Cible=0 AND Task=0 AND Avion=0 AND Alt=0 AND Couverture=0 AND Escorte=0 AND Couverture_Nuit=0 AND Endurance >0");			
	$initlieux=mysqli_query($con,"UPDATE Lieu SET Meteo=0,Meteo_Hour=0,Citernes=0,Camions=0,Recce=0,Recce_PlayerID=0,Recce_PlayerID_TAX=0,Recce_PlayerID_TAL=0,boostProd=0");
	mysqli_close($con);
	if(!$resetreg)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Régiments IA pas initialisés!');
	/*if(!$result1)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Régiments joueurs sous le feu pas passés en cloué au sol!');
	if(!$result2)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Régiments joueurs bombardés IA pas initialisés!');*/
	if(!$result3)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Ateliers unités pas initialisés!');
	if(!$result4)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Demandes de Missions terrestres unités pas initialisés!');
	/*if(!$result5)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Demandes de Missions terrestres officiers pas initialisés!');*/
	if(!$result6)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Pilotes pas entrainés!');
	if(!$result7)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Pilotes pas reposés!');
	if(!$initlieux)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Lieux pas initialisés!');
	if(!$resetacart)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Cie Artillerie pas initialisés!');
	if(!$resetacmob)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Cie Mobiles pas initialisés!');
	if(!$resetacinf)
		mail('binote@hotmail.com','Aube des Aigles: Update : Init','Cie Infanterie pas initialisés!');
}
/*function diff_date($date1,$date2) 
{
	$second=floor($date1-$date2);
	if($second ==0) return "0";
	return array("an" => date('Y',$second)-1970, 
		  "mois" => date('m',$second)-1,
		  "semaine" => floor((date('d',$second)-1)/7),
		  "jour" => (date('d',$second)-1)%7,);
}*/