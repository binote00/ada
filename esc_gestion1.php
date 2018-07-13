<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
/*if(!$PlayerID or $PlayerID ==1)
{
echo"<pre>";
print_r($_POST);
print_r($_SESSION);
echo"</pre>";
}*/
$Action=Insec($_POST['Action']);
$Unite=Insec($_POST['Unite']);
$Cr=Insec($_POST['Cr']);
$Depot=Insec($_POST['depot']);
$CT_Avion1=Insec($_POST['CT_Avion1']);
$CT_Avion2=Insec($_POST['CT_Avion2']);
$CT_Avion3=Insec($_POST['CT_Avion3']);
$Transfer87=Insec($_POST['Transfer87']);
$Transfer100=Insec($_POST['Transfer100']);
$Transfer8mm=Insec($_POST['Transfer8mm']);
$Transfer13mm=Insec($_POST['Transfer13mm']);
$Transfer20mm=Insec($_POST['Transfer20mm']);
$Transfer50kg=Insec($_POST['Transfer50kg']);
$Transfer125kg=Insec($_POST['Transfer125kg']);
$Transfer250kg=Insec($_POST['Transfer250kg']);
$Transfer500kg=Insec($_POST['Transfer500kg']);
$Transfer_Unit87=Insec($_POST['Transfer_Unit87']);
$Transfer_Unit100=Insec($_POST['Transfer_Unit100']);
$Transfer_Unit8mm=Insec($_POST['Transfer_Unit8mm']);
$Transfer_Unit13mm=Insec($_POST['Transfer_Unit13mm']);
$Transfer_Unit20mm=Insec($_POST['Transfer_Unit20mm']);
$Transfer_Unit50kg=Insec($_POST['Transfer_Unit50kg']);
$Transfer_Unit125kg=Insec($_POST['Transfer_Unit125kg']);
$Transfer_Unit250kg=Insec($_POST['Transfer_Unit250kg']);
$Transfer_Unit500kg=Insec($_POST['Transfer_Unit500kg']);
$fret1=Insec($_POST['fret1']);
$fret2=Insec($_POST['fret2']);
$fret3=Insec($_POST['fret3']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $Unite >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_avions.inc.php');
	include_once('./jfv_txt.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	$Credits=GetData("Pilote","ID",$PlayerID,"Credits");
	if(!$MIA and $_SESSION['Distance'] ==0 and $Unite >0 and $Credits >0)
	{		
		$Gestion_Bonus=1;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Reputation,Credits FROM Pilote WHERE ID='$PlayerID'");
		$result2=mysqli_query($con,"SELECT Nom,Pays,Base,Reputation,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE ID='$Unite'");
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
				$Reputation=$data['Reputation'];
				$Credits=$data['Credits'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if(is_array($Skills_Pil))
		{
			if(in_array(59,$Skills_Pil))
				$Gestion=150;
			elseif(in_array(58,$Skills_Pil))
				$Gestion=125;
			elseif(in_array(57,$Skills_Pil))
				$Gestion=100;
			elseif(in_array(56,$Skills_Pil))
				$Gestion=75;
			else
				$Gestion=50;
			if(in_array(55,$Skills_Pil))
				$Gestion_Bonus=1.15;
			elseif(in_array(54,$Skills_Pil))
				$Gestion_Bonus=1.10;
			elseif(in_array(53,$Skills_Pil))
				$Gestion_Bonus=1.5;
			if(in_array(62,$Skills_Pil))
				$Gestion_Bonus+=0.1;
			if(in_array(130,$Skills_Pil))
				$Pers_Sup=1;
			if(in_array(63,$Skills_Pil))
				$Amis_Indus=true;
			else
				$Gestion_avion=floor($Gestion/50);
		}
		else
		{
			$Gestion=50;
			$Gestion_avion=1;
		}
		//End Edit
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Unite_Nom=$data['Nom'];
				$Pays=$data['Pays'];
				$Base=$data['Base'];
				$Reputation_Unite=$data['Reputation'];
				$Avion1=$data['Avion1'];
				$Avion2=$data['Avion2'];
				$Avion3=$data['Avion3'];
				$Avion1_Nbr=$data['Avion1_Nbr'];
				$Avion2_Nbr=$data['Avion2_Nbr'];
				$Avion3_Nbr=$data['Avion3_Nbr'];
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
			mysqli_free_result($result2);
			unset($data);
		}
		$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
		$Personnel=array_count_values($Pers);			
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Zone,Latitude,Longitude,Citernes,Camions,Port_Ori,NoeudF_Ori FROM Lieu WHERE ID='$Base'");
		$result2=mysqli_query($con,"SELECT COUNT(*),SUM(Industrie) FROM Lieu WHERE Flag='$country' AND TypeIndus<>'' AND Flag_Usine='$country'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Zone=$data['Zone'];
				$Base_Lat=$data['Latitude'];
				$Base_Long=$data['Longitude'];
				$Citernes=$data['Citernes'];
				$Camions=$data['Camions'];
				$Port_ori_base=$data['Port_Ori'];
				$Gare_ori_base=$data['NoeudF_Ori'];
			}
			mysqli_free_result($result);
		}
		if($result2)
		{
			if($data=mysqli_fetch_array($result2,MYSQLI_NUM))
			{
				if($data[0] >0)
					$Efficacite_prod=round($data[1]/$data[0]);
				else
					$Efficacite_prod=0;
			}
			mysqli_free_result($result2);
		}
		if($Port_ori_base)
			$Port_base=GetData("Lieu","ID",$Base,"Port");
		else
			$Port_base=100;
		if($Gare_ori_base)
			$Gare_base=GetData("Lieu","ID",$Base,"NoeudF");
		else
			$Gare_base=100;
		if($Port_base !=100 and $Port_base >=$Gare_base)
			$Inf_base=$Port_base;
		elseif($Gare_base !=100 and $Gare_base >$Port_base)
			$Inf_base=$Gare_base;
		else
			$Inf_base=100;
		//Outre-Mer ou anglais
		if($Base_Lat <38.2 or $Base_Long >70 or $Pays ==2 or $Zone ==6)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT COUNT(*),SUM(Port) FROM Lieu WHERE Flag='$country' AND Port_Ori >0 AND Flag_Port='$country'");
			$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$country'");
			mysqli_close($con);
			if($result)
			{
				if($data=mysqli_fetch_array($result,MYSQLI_NUM))
				{
					if($data[0] >0)
						$Efficacite_ravit_port=round($data[1]/$data[0]);
					else
						$Efficacite_ravit_port=0;
				}
				mysqli_free_result($result);
			}
			if($result2)
			{
				if($data=mysqli_fetch_array($result2,MYSQLI_NUM))
				{
					if($data[0] >0)
						$Efficacite_ravit=round($data[1]/$data[0]);
					else
						$Efficacite_ravit=0;
				}
				mysqli_free_result($result2);
			}
			$Efficacite_ravit=round(($Efficacite_ravit+($Efficacite_ravit_port*2))/3);
		}
		else
		{
			$Lat_base_min=$Base_Lat -1;
			$Lat_base_max=$Base_Lat +1;
			$Long_base_min=$Base_Long -3;
			$Long_base_max=$Base_Long +3;
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$country'");
			$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND Flag_Gare='$country' 
			AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max')");
			mysqli_close($con);
			if($result)
			{
				if($data=mysqli_fetch_array($result,MYSQLI_NUM))
				{
					if($data[0] >0)
						$Efficacite_ravit1=round($data[1]/$data[0]);
					else
						$Efficacite_ravit1=0;
				}
				mysqli_free_result($result);
			}
			if($result2)
			{
				if($data2=mysqli_fetch_array($result2,MYSQLI_NUM))
				{
					if($data2[0] >0)
						$Efficacite_ravit2=round($data2[1]/$data2[0]);
					else
						$Efficacite_ravit2=0;
				}
				mysqli_free_result($result2);
			}
			$Efficacite_ravit=round(($Efficacite_ravit1+($Efficacite_ravit2*2))/3);
		}				
		//Malus ravitaillement par saison ou terrain
		$Saison=$_SESSION['Saison'];
		if($Base_Long >20 and $Base_Lat >43)		//Front Est
		{
			if($Saison ==2)	// Printemps (boue dégel)
			{
				$Citernes+=20;
				$Camions+=20;
			}
			elseif($Saison ==1) // Automne
			{
				$Citernes+=10;
				$Camions+=10;
			}
			elseif($Saison ==0) // Hiver
			{
				$Citernes+=25;
				$Camions+=25;
			}
		}
		elseif($Base_Lat >55) // Europe du nord
		{
			if($Saison ==0) // Hiver
			{
				$Citernes+=25;
				$Camions+=25;
			}
		}
		elseif($Base_Lat >43) // Europe continentale
		{
			if($Saison == 0) // Hiver
			{
				$Citernes+=10;
				$Camions+=10;
			}
		}
		elseif($Base_Lat <33) // Désert
		{
			if($Saison ==3) // Ete (chaleur, pannes)
			{
				$Citernes+=5;
				$Camions+=5;
			}
		}
		if($Zone ==5 or $Zone ==9 or $Zone ==11)
		{
			$Citernes+=20;
			$Camions+=20;
		}
		elseif($Zone ==4)
		{
			$Citernes+=15;
			$Camions+=15;
		}
		elseif($Zone ==3)
		{
			$Citernes+=10;
			$Camions+=10;
		}
		elseif($Zone ==2 or $Zone ==8)
		{
			$Citernes+=5;
			$Camions+=5;
		}
		$Efficacite_ravit_fuel=($Efficacite_ravit-$Citernes)*($Inf_base/100);
		$Efficacite_ravit_muns=($Efficacite_ravit-$Camions+$Personnel[1]+$Pers_Sup)*($Inf_base/100);
		if($Efficacite_ravit_fuel <0)$Efficacite_ravit_fuel=0;
		if($Efficacite_ravit_muns <0)$Efficacite_ravit_muns=0;
		$fournitures=0;
		if($Efficacite_prod <50)
			$rav_base=0;
		else
			$rav_base=1;
		if($Depot >0)
		{
			//Depot
			$query="SELECT DISTINCT ID,Nom,Longitude,Latitude,ValeurStrat,Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,
			Stock_Munitions_40,Stock_Munitions_50,Stock_Munitions_60,Stock_Munitions_75,Stock_Munitions_90,Stock_Munitions_105,Stock_Munitions_125,Stock_Munitions_150,
			Stock_Bombes_30,Stock_Bombes_50,Stock_Bombes_80,Stock_Bombes_125,Stock_Bombes_250,Stock_Bombes_300,Stock_Bombes_400,Stock_Bombes_500,Stock_Bombes_800,Stock_Bombes_1000,Stock_Bombes_2000
			FROM Lieu WHERE ID='$Depot'";
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);			
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{			
					if($data['ValeurStrat'] >3)
					{
						$Stock_Munitions_8=$data['Stock_Munitions_8'];
						$Stock_Munitions_13=$data['Stock_Munitions_13'];
						$Stock_Munitions_20=$data['Stock_Munitions_20'];
						$Stock_Munitions_30=$data['Stock_Munitions_30'];
						$Stock_Munitions_40=$data['Stock_Munitions_40'];
						$Stock_Munitions_50=$data['Stock_Munitions_50'];
						$Stock_Munitions_60=$data['Stock_Munitions_60'];
						$Stock_Munitions_75=$data['Stock_Munitions_75'];
						$Stock_Munitions_90=$data['Stock_Munitions_90'];
						$Stock_Munitions_105=$data['Stock_Munitions_105'];
						$Stock_Munitions_125=$data['Stock_Munitions_125'];
						$Stock_Munitions_150=$data['Stock_Munitions_150'];
						$Stock_Essence_87=$data['Stock_Essence_87'];
						$Stock_Essence_100=$data['Stock_Essence_100'];
						$Stock_Essence_1=$data['Stock_Essence_1'];
						$Stock_Bombes_30=$data['Stock_Bombes_30'];
						$Stock_Bombes_50=$data['Stock_Bombes_50'];
						$Stock_Bombes_80=$data['Stock_Bombes_80'];
						$Stock_Bombes_125=$data['Stock_Bombes_125'];
						$Stock_Bombes_250=$data['Stock_Bombes_250'];
						$Stock_Bombes_300=$data['Stock_Bombes_300'];
						$Stock_Bombes_400=$data['Stock_Bombes_400'];
						$Stock_Bombes_500=$data['Stock_Bombes_500'];
						$Stock_Bombes_800=$data['Stock_Bombes_800'];
						$Stock_Bombes_1000=$data['Stock_Bombes_1000'];
						$Stock_Bombes_2000=$data['Stock_Bombes_2000'];
						$Stock_Bombes_30=$data['Stock_Bombes_30'];
					}
				}
				mysqli_free_result($result);
			}
		}				
		switch($Action)
		{
			case 1:
				if($Credits >=$CT_Avion1)
				{
					if($Amis_Indus)
						$fournitures=12;
					else
						$fournitures=floor($rav_base + mt_rand(0,$Gestion_avion) + ($Reputation/10000));
					if($fournitures >0)
					{
						$Max1=12-$Avion1_Nbr;
						if($fournitures >$Max1)$fournitures=$Max1;
						UpdateData("Unit","Avion1_Nbr",$fournitures,"ID",$Unite,12);
						$Credits=-$CT_Avion1;
						$units="avions";
						AddEvent("Avion",139,$Avion1,$PlayerID,$Unite,$Base,$fournitures);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_avions".$country;
			break;
			case 2:
				if($Credits >=$CT_Avion2)
				{
					if($Amis_Indus)
						$fournitures=12;
					else
						$fournitures=floor($rav_base + mt_rand(0,$Gestion_avion) + ($Reputation/10000));
					if($fournitures >0)
					{
						$Max2=12-$Avion2_Nbr;
						if($fournitures >$Max2)$fournitures=$Max2;
						UpdateData("Unit","Avion2_Nbr",$fournitures,"ID",$Unite,12);
						$Credits=-$CT_Avion2;
						$units="avions";
						AddEvent("Avion",139,$Avion2,$PlayerID,$Unite,$Base,$fournitures);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_avions".$country;
			break;
			case 3:
				if($Credits >=$CT_Avion3)
				{
					if($Amis_Indus)
						$fournitures=12;
					else
						$fournitures=floor($rav_base + mt_rand(0,$Gestion_avion) + ($Reputation/10000));
					if($fournitures >0)
					{
						$Max3=12-$Avion3_Nbr;
						if($fournitures >$Max3)$fournitures=$Max3;
						UpdateData("Unit","Avion3_Nbr",$fournitures,"ID",$Unite,12);
						$Credits=-$CT_Avion3;
						$units="avions";
						AddEvent("Avion",139,$Avion3,$PlayerID,$Unite,$Base,$fournitures);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_avions".$country;
			break;
			case 4:
				if($Credits >=2 and $Depot >0 and $Stock_Essence_87 >1000)
				{
					$fournitures=floor((mt_rand(0,1000) + mt_rand(10,$Gestion*100) + ($Reputation/10) + ($Reputation_Unite/1000))*($Efficacite_ravit_fuel/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Essence_87)
						$fournitures=$Stock_Essence_87;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Essence_87",$fournitures,"ID",$Unite,500000);
						UpdateData("Lieu","Stock_Essence_87",-$fournitures,"ID",$Depot);
						$Credits=-2;
						$units="litres d'essence";
						AddEvent("Avion",101,87,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_essence".$country;
			break;
			case 5:
				if($Credits >=3 and $Depot >0 and $Stock_Essence_100 > 1000)
				{
					$fournitures=floor((mt_rand(0,500) + mt_rand(0,$Gestion*100) + ($Reputation/20) + ($Reputation_Unite/1000))*($Efficacite_ravit_fuel/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Essence_100)
						$fournitures=$Stock_Essence_100;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Essence_100",$fournitures,"ID",$Unite,500000);
						UpdateData("Lieu","Stock_Essence_100",-$fournitures,"ID",$Depot);
						if(IsAllie($country))
							$Credits=-3;
						else
							$Credits=-5;
						$units="litres d'essence";
						AddEvent("Avion",101,100,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_essence".$country;
			break;
			case 6:
				if($Credits >=5 and $Depot >0 and $Stock_Essence_1 >1000)
				{
					$fournitures=floor((mt_rand(0,500) + mt_rand(10,$Gestion*100) + ($Reputation/20) + ($Reputation_Unite/1000))*($Efficacite_ravit_fuel/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Essence_1)
						$fournitures=$Stock_Essence_1;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Essence_1",$fournitures,"ID",$Unite,500000);
						UpdateData("Lieu","Stock_Essence_1",-$fournitures,"ID",$Depot);
						$Credits=-5;
						$units="litres de diesel";
						AddEvent("Avion",101,1,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_essence".$country;
			break;
			case 7:
				if($Credits >=1 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,1000) + mt_rand(10,$Gestion*250) + ($Reputation/10) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Munitions_8)
						$fournitures=$Stock_Munitions_8;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_8",$fournitures,"ID",$Unite,500000);
						UpdateData("Lieu","Stock_Munitions_8",-$fournitures,"ID",$Depot);
						$Credits=-1;
						$units="cartouches de 8mm";
						AddEvent("Avion",102,8,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 8:
				if($Credits >=2 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,500) + mt_rand(10,$Gestion*100) + ($Reputation/20) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Munitions_13)
						$fournitures=$Stock_Munitions_13;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_13",$fournitures,"ID",$Unite,200000);
						UpdateData("Lieu","Stock_Munitions_13",-$fournitures,"ID",$Depot);
						$Credits=-2;
						$units="cartouches de 13mm";
						AddEvent("Avion",102,13,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 9:
				if($Credits >=3 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,100) + mt_rand(10,$Gestion*100) + ($Reputation/20) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Munitions_20)
						$fournitures=$Stock_Munitions_20;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_20",$fournitures,"ID",$Unite,100000);
						UpdateData("Lieu","Stock_Munitions_20",-$fournitures,"ID",$Depot);
						$Credits=-3;
						$units="obus de 20mm";
						AddEvent("Avion",102,20,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 10:
				if($Credits >=4)
				{
					$fournitures=1;
					if(GetData("Lieu","ID",$Base,"QualitePiste") < 96)
						UpdateData("Lieu","QualitePiste",5,"ID",$Base,100);
					else
						SetData("Lieu","QualitePiste",100,"ID",$Base);
					$Credits=-4;
				}
				else
					$fournitures=0;
				$img_txt="gestion_piste".$country;
			break;
			case 12:
				/*UpdateCarac($PlayerID,"Missions_Jour",6);
				$Credits=-24;
				$img_txt="taxi".$country;*/
			break;
			case 13:
				if($Credits >=2)
				{
					$fournitures=1;
					/*$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE ID='$Base'");
					mysqli_close($con);*/
					UpdateData("Lieu","Camouflage",10,"ID",$Base,100);
					AddEvent("Avion",107,10,$PlayerID,$Unite,$Base);
					$Credits=-2;
				}
				else
					$fournitures=0;
				$img_txt="gestion_camouflage";
			break;
			case 14:
				if($Credits >=1 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,10) + mt_rand(10,$Gestion) + ($Reputation/1000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Bombes_50)
						$fournitures=$Stock_Bombes_50;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_50",$fournitures,"ID",$Unite,10000);
						UpdateData("Lieu","Stock_Bombes_50",-$fournitures,"ID",$Depot);
						$Credits=-1;
						$units="bombes de 50kg";
						AddEvent("Avion",103,50,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 15:
				if($Credits >=2 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,5) + mt_rand(1,$Gestion/10) + ($Reputation/2000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Bombes_125)
						$fournitures=$Stock_Bombes_125;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_125",$fournitures,"ID",$Unite,5000);
						UpdateData("Lieu","Stock_Bombes_125",-$fournitures,"ID",$Depot);
						$Credits=-2;
						$units="bombes de 125kg";
						AddEvent("Avion",103,125,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 16:
				if($Credits >=3 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,5) + mt_rand(1,$Gestion/10) + ($Reputation/2000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Bombes_250)
						$fournitures=$Stock_Bombes_250;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_250",$fournitures,"ID",$Unite,2500);
						UpdateData("Lieu","Stock_Bombes_250",-$fournitures,"ID",$Depot);
						$Credits=-3;
						$units="bombes de 250kg";
						AddEvent("Avion",103,250,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 17:
				if($Credits >=4 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,2) + mt_rand(1,$Gestion/10) + ($Reputation/2000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					$fournitures_debug=$fournitures;
					if($fournitures >$Stock_Bombes_500)
						$fournitures=$Stock_Bombes_500;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_500",$fournitures,"ID",$Unite,1000);
						UpdateData("Lieu","Stock_Bombes_500",-$fournitures,"ID",$Depot);
						$Credits=-4;
						$units="bombes de 500kg";
						AddEvent("Avion",103,500,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
					else
					{
						/*$Gestion_debug=$Gestion / 10;
						$Reputation_debug=$Reputation / 2000;
						$Reputation_Unite_debug=$Reputation_Unite / 10000;
						$Efficacite_ravit_muns_debug=$Efficacite_ravit_muns / 100;
						$debug_txt="<br>[DEBUG] La quantité de ravitaillement possible est de <b>".$fournitures_debug."</b>, pour une valeur de gestion de ".$Gestion_debug." , une réputation de ".$Reputation_debug." , une réputation d'unité de ".$Reputation_Unite_debug." 
						, et une efficacité de ravitaillement de ".$Efficacite_ravit_muns_debug."<br>La quantité finale livrée est de ".$fournitures." sur un stock de ".$Stock_Bombes_500;*/
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 18:
				if($Credits >=5 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,1) + mt_rand(0,$Gestion/100) + ($Reputation/5000) + ($Reputation_Unite/20000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Bombes_1000)
						$fournitures=$Stock_Bombes_1000;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_1000",$fournitures,"ID",$Unite,500);
						UpdateData("Lieu","Stock_Bombes_1000",-$fournitures,"ID",$Depot);
						$Credits=-5;
						$units="bombes de 1000kg";
						AddEvent("Avion",103,1000,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 19:
				if($Credits >=10 and $Depot >0)
				{
					$fournitures=floor((mt_rand(0,1) + mt_rand(0,$Gestion/100) + ($Reputation/10000) + ($Reputation_Unite/100000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Bombes_2000)
						$fournitures=$Stock_Bombes_2000;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_2000",$fournitures,"ID",$Unite,100);
						UpdateData("Lieu","Stock_Bombes_2000",-$fournitures,"ID",$Depot);
						$Credits=-10;
						$units="bombes de 2000kg";
						AddEvent("Avion",103,2000,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 20:
				if($Credits >=4)
				{
					$fournitures=1;
					UpdateData("Unit","Station_Meteo",1,"ID",$Unite,10);
					$Credits=-4;
				}
				else
					$fournitures=0;
				$img_txt="gestion_meteo";
			break;
			case 21:
				if($Credits >=$Cr)
				{
					$fournitures=1;
					SetData("Lieu","QualitePiste",100,"ID",$Base);
					$Credits=-$Cr;
					if($Cr >=40)
						UpdateCarac($PlayerID,"Note",4);
				}
				else
					$fournitures=0;
				$img_txt="gestion_piste".$country;
			break;
			case 22:
				$Stock_Essence_87=GetData("Unit","ID",$Unite,"Stock_Essence_87");
				$fournitures=$Transfer87*1000;
				if($Stock_Essence_87 >= $fournitures)
				{
					UpdateData("Unit","Stock_Essence_87",$fournitures,"ID",$Transfer_Unit87);
					UpdateData("Unit","Stock_Essence_87",-$fournitures,"ID",$Unite);
					$Credits=0-(($Transfer87*2)+1);
					$units="litres d'essence";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit87,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID,"Gestion",-1);
				}
				$img_txt="gestion_essence".$country;
			break;
			case 23:
				$Stock_Munitions_8=GetData("Unit","ID",$Unite,"Stock_Munitions_8");
				$fournitures=$Transfer8mm*1000;
				if($Stock_Munitions_8 >= $fournitures)
				{
					UpdateData("Unit","Stock_Munitions_8",$fournitures,"ID",$Transfer_Unit8mm);
					UpdateData("Unit","Stock_Munitions_8",-$fournitures,"ID",$Unite);
					$Credits=0-(($Transfer8mm*2)+1);
					$units="cartouches de 8mm";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit8mm,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID,"Gestion",-1);
				}
				$img_txt="gestion_muns".$country;
			break;
			case 24:
				$Stock_Essence_100=GetData("Unit","ID",$Unite,"Stock_Essence_100");
				$fournitures=$Transfer100*500;
				if($Stock_Essence_100 >= $fournitures)
				{
					UpdateData("Unit","Stock_Essence_100",$fournitures,"ID",$Transfer_Unit100);
					UpdateData("Unit","Stock_Essence_100",-$fournitures,"ID",$Unite);
					$Credits=0-(($Transfer100*2)+3);
					$units="litres d'essence";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit100,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID,"Gestion",-1);
				}
				$img_txt="gestion_essence".$country;
			break;
			case 25:
				$Stock_Munitions_13=GetData("Unit","ID",$Unite,"Stock_Munitions_13");
				$fournitures=$Transfer13mm*500;
				if($Stock_Munitions_13 >= $fournitures)
				{
					UpdateData("Unit","Stock_Munitions_13",$fournitures,"ID",$Transfer_Unit13mm);
					UpdateData("Unit","Stock_Munitions_13",-$fournitures,"ID",$Unite);
					$Credits=0-($Transfer13mm+3);
					$units="cartouches de 13mm";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit13mm,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID,"Gestion",-1);
				}
				$img_txt="gestion_muns".$country;
			break;
			case 26:
				$Stock_Munitions_20=GetData("Unit","ID",$Unite,"Stock_Munitions_20");
				$fournitures=$Transfer20mm*100;
				if($Stock_Munitions_20 >= $fournitures)
				{
					UpdateData("Unit","Stock_Munitions_20",$fournitures,"ID",$Transfer_Unit20mm);
					UpdateData("Unit","Stock_Munitions_20",-$fournitures,"ID",$Unite);
					$Credits=0-($Transfer20mm+4);
					$units="obus de 20mm";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit20mm,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID,"Gestion",-1);
				}
				$img_txt="gestion_muns".$country;
			break;
			case 27:
				$Stock_Bombes_50=GetData("Unit","ID",$Unite,"Bombes_50");
				$fournitures=$Transfer50kg*10;
				if($Stock_Bombes_50 >= $fournitures)
				{
					UpdateData("Unit","Bombes_50",$fournitures,"ID",$Transfer_Unit50kg);
					UpdateData("Unit","Bombes_50",-$fournitures,"ID",$Unite);
					$Credits=0-($Transfer50kg+1);
					$units="bombes de 50kg";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit50kg,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID, "Gestion", -1);
				}
				$img_txt="gestion_bombs".$country;
			break;
			case 28:
				$Stock_Bombes_125=GetData("Unit","ID",$Unite,"Bombes_125");
				$fournitures=$Transfer125kg*5;
				if($Stock_Bombes_125 >= $fournitures)
				{
					UpdateData("Unit","Bombes_125",$fournitures,"ID",$Transfer_Unit125kg);
					UpdateData("Unit","Bombes_125",-$fournitures,"ID",$Unite);
					$Credits=0-($Transfer125kg+2);
					$units="bombes de 125kg";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit125kg,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID, "Gestion", -1);
				}
				$img_txt="gestion_bombs".$country;
			break;
			case 29:
				$Stock_Bombes_250=GetData("Unit","ID",$Unite,"Bombes_250");
				$fournitures=$Transfer250kg*5;
				if($Stock_Bombes_250 >= $fournitures)
				{
					UpdateData("Unit","Bombes_250",$fournitures,"ID",$Transfer_Unit250kg);
					UpdateData("Unit","Bombes_250",-$fournitures,"ID",$Unite);
					$Credits=0-($Transfer250kg+3);
					$units="bombes de 250kg";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit250kg,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID, "Gestion", -1);
				}
				$img_txt="gestion_bombs".$country;
			break;
			case 30:
				$Stock_Bombes_500=GetData("Unit","ID",$Unite,"Bombes_500");
				$fournitures=$Transfer500kg*2;
				if($Stock_Bombes_500 >= $fournitures)
				{
					UpdateData("Unit","Bombes_500",$fournitures,"ID",$Transfer_Unit500kg);
					UpdateData("Unit","Bombes_500",-$fournitures,"ID",$Unite);
					$Credits=0-($Transfer500kg+4);
					$units="bombes de 500kg";
					//$Unite_Nom=GetData("Unit","ID",$Transfer_Unit500kg,"Nom"); 
				}
				else
				{
					$fournitures=0;
					UpdateCarac($PlayerID,"Gestion",-1);
				}
				$img_txt="gestion_bombs".$country;
			break;
			case 31:
				if($Credits >=4 and $Depot >0 and $Stock_Bombes_400 >0)
				{
					$fournitures=floor((mt_rand(0,2) + mt_rand(0,$Gestion/10) + ($Reputation/2000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures > $Stock_Bombes_400)
						$fournitures=$Stock_Bombes_400;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_400",$fournitures,"ID",$Unite,5000);
						$Credits=-4;
						$units="Mines";
						AddEvent("Avion",103,400,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 32:
				if($Credits >=4 and $Depot >0 and $Stock_Bombes_800 >0)
				{
					$fournitures=floor((mt_rand(0,2) + mt_rand(0,$Gestion/10) + ($Reputation/2000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures > $Stock_Bombes_800)
						$fournitures=$Stock_Bombes_800;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_800",$fournitures,"ID",$Unite,1000);
						$Credits=-4;
						$units="Torpilles";
						AddEvent("Avion",103,800,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 33:
				if($Credits >=4 and $Depot >0 and $Stock_Munitions_30 >0)
				{
					$fournitures=floor((mt_rand(0,50) + mt_rand(0,$Gestion*100) + ($Reputation/20) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures > $Stock_Munitions_30)
						$fournitures=$Stock_Munitions_30;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_30",$fournitures,"ID",$Unite,100000);
						UpdateData("Lieu","Stock_Munitions_30",-$fournitures,"ID",$Depot);
						$Credits=-4;
						$units="obus de 30mm";
						AddEvent("Avion",102,30,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 35:
				if($Credits >= 4)
				{
					$fournitures=1;
					if(GetData("Lieu","ID",$Base,"Tour") <96)
						UpdateData("Lieu","Tour",5,"ID",$Base,100);
					else
						SetData("Lieu","Tour",100,"ID",$Base);
					$Credits=-4;
				}
				else
					$fournitures=0;
				$img_txt="gestion_piste".$country;
			break;
			case 36:
				if($Credits >=4 and $Depot >0 and $Stock_Bombes_80 >0)
				{
					$fournitures=floor((mt_rand(2,10) + mt_rand(0,$Gestion/10) + ($Reputation/2000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures > $Stock_Bombes_80)
						$fournitures=$Stock_Bombes_80;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_80",$fournitures,"ID",$Unite,10000);
						UpdateData("Lieu","Stock_Bombes_80",-$fournitures,"ID",$Depot);
						$Credits=-4;
						$units="Rockets";
						AddEvent("Avion",103,80,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 37:
				if($Credits >=8)
				{
					$fournitures=1;
					UpdateData("Lieu","Meteo",40,"ID",$Base);
					$Credits=-8;
				}
				else
					$fournitures=0;
				$img_txt="gestion_piste".$country;
			break;
			case 38:
				if($Credits >=4 and $Depot >0 and $Stock_Bombes_300 >0)
				{
					$fournitures=floor((mt_rand(0,2) + mt_rand(0,$Gestion/10) + ($Reputation/2000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures > $Stock_Bombes_300)
						$fournitures=$Stock_Bombes_300;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_300",$fournitures,"ID",$Unite,5000);
						$Credits=-4;
						$units="Charges de profondeur";
						AddEvent("Avion",103,300,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 39:
				if($Credits >=2 and $Depot >0 and $Stock_Bombes_30 >0)
				{
					$fournitures=floor((mt_rand(0,50) + mt_rand(0,$Gestion/10) + ($Reputation/2000) + ($Reputation_Unite/10000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures > $Stock_Bombes_30)
						$fournitures=$Stock_Bombes_30;
					if($fournitures >0)
					{
						UpdateData("Unit","Bombes_30",$fournitures,"ID",$Unite,10000);
						$Credits=-2;
						$units="Fusées éclairantes";
						AddEvent("Avion",103,30,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_bombs".$country;
			break;
			case 44:
				if($Credits >=5 and $Depot >0 and $Stock_Munitions_40 >0)
				{
					$fournitures=floor((mt_rand(0,75) + mt_rand(10,$Gestion*50) + ($Reputation/50) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures > $Stock_Munitions_40)
						$fournitures=$Stock_Munitions_40;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_40",$fournitures,"ID",$Unite,50000);
						UpdateData("Lieu","Stock_Munitions_40",-$fournitures,"ID",$Depot);
						$Credits=-5;
						$units="obus de 40mm";
						AddEvent("Avion",102,40,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 45:
				if($Credits >=5 and $Depot >0 and $Stock_Munitions_50 >0)
				{
					$fournitures=floor((mt_rand(0,75) + mt_rand(10,$Gestion*50) + ($Reputation/50) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures > $Stock_Munitions_50)
						$fournitures=$Stock_Munitions_50;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_50",$fournitures,"ID",$Unite,50000);
						UpdateData("Lieu","Stock_Munitions_50",-$fournitures,"ID",$Depot);
						$Credits=-5;
						$units="obus de 50mm";
						AddEvent("Avion", 102, 50, $PlayerID, $Unite, $Base, $fournitures, $Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 46:
				if($Credits >=5 and $Depot >0 and $Stock_Munitions_60 >0)
				{
					$fournitures=floor((mt_rand(0,75) + mt_rand(10,$Gestion*50) + ($Reputation/50) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Munitions_60)
						$fournitures=$Stock_Munitions_60;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_60",$fournitures,"ID",$Unite,50000);
						UpdateData("Lieu","Stock_Munitions_60",-$fournitures,"ID",$Depot);
						$Credits=-5;
						$units="obus de 60mm";
						AddEvent("Avion",102,60,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 47:
				if($Credits >=6 and $Depot >0 and $Stock_Munitions_75 >0)
				{
					$fournitures=floor((mt_rand(0,50) + mt_rand(10,$Gestion*50) + ($Reputation/50) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Munitions_75)
						$fournitures=$Stock_Munitions_75;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_75",$fournitures,"ID",$Unite,20000);
						UpdateData("Lieu","Stock_Munitions_75",-$fournitures,"ID",$Depot);
						$Credits=-6;
						$units="obus de 75mm";
						AddEvent("Avion",102,75,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 48:
				if($Credits >=7 and $Depot >0 and $Stock_Munitions_90 >0)
				{
					$fournitures=floor((mt_rand(0,50) + mt_rand(10,$Gestion*20) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Munitions_90)
						$fournitures=$Stock_Munitions_90;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_90",$fournitures,"ID",$Unite,10000);
						UpdateData("Lieu","Stock_Munitions_90",-$fournitures,"ID",$Depot);
						$Credits=-7;
						$units="obus de 90mm";
						AddEvent("Avion",102,90,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 49:
				if($Credits >=7 and $Depot >0 and $Stock_Munitions_105 >0)
				{
					$fournitures=floor((mt_rand(0,50) + mt_rand(10,$Gestion*20) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Munitions_105)
						$fournitures=$Stock_Munitions_105;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_105",$fournitures,"ID",$Unite,10000);
						UpdateData("Lieu","Stock_Munitions_105",-$fournitures,"ID",$Depot);
						$Credits=-7;
						$units="obus de 105mm";
						AddEvent("Avion",102,105,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
			case 50:
				if($Credits >=9 and $Depot >0 and $Stock_Munitions_125 >0)
				{
					$fournitures=floor((mt_rand(0,20) + mt_rand(10,$Gestion*20) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100)*$Gestion_Bonus);
					if($fournitures >$Stock_Munitions_125)
						$fournitures=$Stock_Munitions_125;
					if($fournitures >0)
					{
						UpdateData("Unit","Stock_Munitions_125",$fournitures,"ID",$Unite,5000);
						UpdateData("Lieu","Stock_Munitions_125",-$fournitures,"ID",$Depot);
						$Credits=-9;
						$units="obus de 125mm";
						AddEvent("Avion",102,125,$PlayerID,$Unite,$Base,$fournitures,$Depot);
					}
				}
				else
					$fournitures=0;
				$img_txt="gestion_muns".$country;
			break;
		}		
		if($fret1)
		{
			$Credits=$Credits-1;
			$Array_Mod1=GetAmeliorations(GetData("Unit","ID",$Unite,"Avion1"));		
			$Fret_mun81=$Array_Mod1[22];
			$Fret_mun131=$Array_Mod1[23];
			$Fret_mun201=$Array_Mod1[24];
			$Fret_871=$Array_Mod1[25];
			$Fret_1001=$Array_Mod1[26];
			$Fret_501=$Array_Mod1[27];
			$Fret_1251=$Array_Mod1[28];
			$Fret_2501=$Array_Mod1[29];
			$Fret_5001=$Array_Mod1[30];
			$Fret_para1=$Array_Mod1[31];
			switch($fret1)
			{
				case 1:
					$Bombe_new=0;
					$Bombe_new_nbr=0;
				break;
				case 2:
					$Bombe_new=50;
					$Bombe_new_nbr=$Fret_501;
				break;
				case 3:
					$Bombe_new=125;
					$Bombe_new_nbr=$Fret_1251;
				break;
				case 4:
					$Bombe_new=250;
					$Bombe_new_nbr=$Fret_2501;
				break;
				case 5:
					$Bombe_new=500;
					$Bombe_new_nbr=$Fret_5001;
				break;
				case 6:
					$Bombe_new=50000;
					$Bombe_new_nbr=$Fret_mun81;
				break;
				case 7:
					$Bombe_new=15000;
					$Bombe_new_nbr=$Fret_mun131;
				break;
				case 8:
					$Bombe_new=5000;
					$Bombe_new_nbr=$Fret_mun201;
				break;
				case 9:
					$Bombe_new=1200;
					$Bombe_new_nbr=$Fret_871;
				break;
				case 10:
					$Bombe_new=1100;
					$Bombe_new_nbr=$Fret_1001;
				break;
				case 11:
					$Bombe_new=100;
					$Bombe_new_nbr=$Fret_para1;
				break;
			}
			SetData("Unit","Avion1_Bombe",$Bombe_new,"ID",$Unite);
			SetData("Unit","Avion1_Bombe_Nbr",$Bombe_new_nbr,"ID",$Unite);	
			$img_txt="gestion_bombs".$country;
		}
		if($fret2)
		{
			$Credits=$Credits-1;
			$Array_Mod2=GetAmeliorations(GetData("Unit","ID",$Unite,"Avion2"));			
			$Fret_mun82=$Array_Mod2[22];
			$Fret_mun132=$Array_Mod2[23];
			$Fret_mun202=$Array_Mod2[24];
			$Fret_872=$Array_Mod2[25];
			$Fret_1002=$Array_Mod2[26];
			$Fret_502=$Array_Mod2[27];
			$Fret_1252=$Array_Mod2[28];
			$Fret_2502=$Array_Mod2[29];
			$Fret_5002=$Array_Mod2[30];
			$Fret_para2=$Array_Mod2[31];		
			switch($fret2)
			{
				case 1:
					$Bombe_new=0;
					$Bombe_new_nbr=0;
				break;
				case 2:
					$Bombe_new=50;
					$Bombe_new_nbr=$Fret_502;
				break;
				case 3:
					$Bombe_new=125;
					$Bombe_new_nbr=$Fret_1252;
				break;
				case 4:
					$Bombe_new=250;
					$Bombe_new_nbr=$Fret_2502;
				break;
				case 5:
					$Bombe_new=500;
					$Bombe_new_nbr=$Fret_5002;
				break;
				case 6:
					$Bombe_new=50000;
					$Bombe_new_nbr=$Fret_mun82;
				break;
				case 7:
					$Bombe_new=15000;
					$Bombe_new_nbr=$Fret_mun132;
				break;
				case 8:
					$Bombe_new=5000;
					$Bombe_new_nbr=$Fret_mun202;
				break;
				case 9:
					$Bombe_new=1200;
					$Bombe_new_nbr=$Fret_872;
				break;
				case 10:
					$Bombe_new=1100;
					$Bombe_new_nbr=$Fret_1002;
				break;
				case 11:
					$Bombe_new=100;
					$Bombe_new_nbr=$Fret_para2;
				break;
			}
			SetData("Unit","Avion2_Bombe",$Bombe_new,"ID",$Unite);
			SetData("Unit","Avion2_Bombe_Nbr",$Bombe_new_nbr,"ID",$Unite);
			$img_txt="gestion_bombs".$country;
		}
		if($fret3)
		{
			$Credits=$Credits-1;
			$Array_Mod3=GetAmeliorations(GetData("Unit","ID",$Unite,"Avion3"));
			$Fret_mun83=$Array_Mod3[22];
			$Fret_mun133=$Array_Mod3[23];
			$Fret_mun203=$Array_Mod3[24];
			$Fret_873=$Array_Mod3[25];
			$Fret_1003=$Array_Mod3[26];
			$Fret_503=$Array_Mod3[27];
			$Fret_1253=$Array_Mod3[28];
			$Fret_2503=$Array_Mod3[29];
			$Fret_5003=$Array_Mod3[30];
			$Fret_para3=$Array_Mod3[31];
			switch($fret3)
			{
				case 1:
					$Bombe_new=0;
					$Bombe_new_nbr=0;
				break;
				case 2:
					$Bombe_new=50;
					$Bombe_new_nbr=$Fret_503;
				break;
				case 3:
					$Bombe_new=125;
					$Bombe_new_nbr=$Fret_1253;
				break;
				case 4:
					$Bombe_new=250;
					$Bombe_new_nbr=$Fret_2503;
				break;
				case 5:
					$Bombe_new=500;
					$Bombe_new_nbr=$Fret_5003;
				break;
				case 6:
					$Bombe_new=50000;
					$Bombe_new_nbr=$Fret_mun83;
				break;
				case 7:
					$Bombe_new=15000;
					$Bombe_new_nbr=$Fret_mun133;
				break;
				case 8:
					$Bombe_new=5000;
					$Bombe_new_nbr=$Fret_mun203;
				break;
				case 9:
					$Bombe_new=1200;
					$Bombe_new_nbr=$Fret_873;
				break;
				case 10:
					$Bombe_new=1100;
					$Bombe_new_nbr=$Fret_1003;
				break;
				case 11:
					$Bombe_new=100;
					$Bombe_new_nbr=$Fret_para3;
				break;
			}
			SetData("Unit","Avion3_Bombe",$Bombe_new,"ID",$Unite);
			SetData("Unit","Avion3_Bombe_Nbr",$Bombe_new_nbr,"ID",$Unite);
			$img_txt="gestion_bombs".$country;
		}
		//mail('binote@hotmail.com','Aube des Aigles: Gestion Unité',"Unité ".$Unite_Nom." / Joueur ".$PlayerID." : ".$msg);		
		$Corps=GetEM_Name($country);
		if($fournitures >0)
		{
			$skills=MoveCredits($PlayerID,3,$Credits);
			UpdateCarac($PlayerID,"Avancement",-$Credits);
			//UpdateCarac($PlayerID,"Gestion",-$Credits);
			$fournitures_txt="<br>Nous sommes en mesure de vous fournir actuellement <b>".$fournitures." ".$units.".</b>";
			$mes="<p>".$msg_prod."<br>".$msg_ravit."</p><p>Le ".$Corps." vous informe que votre demande a été acceptée.".$fournitures_txt."
				<br><br>Votre unité, le <b>".$Unite_Nom."</b> recevra sous peu les fournitures commandées.</p>";
		}
		else
			$mes="Le ".$Corps." vous informe que votre demande a été refusée.<br>Vos stocks ne sont pas suffisants ou votre demande est irréalisable au vu de la situation actuelle.".$debug_txt;
		/*$msg_prod="L'efficacité de production de nos usines est estimée à ".$Efficacite_prod."%";
		$msg_ravit="Notre ravitaillement général fonctionne à ".$Efficacite_ravit."% de ses possibilités.
		<br> Nos convois de ravitaillement en carburant fonctionnent à ".$Efficacite_ravit_fuel." %
		<br> Nos convois de ravitaillement en munitions fonctionnent à ".$Efficacite_ravit_muns." %";
		$mes="<p>".$msg_prod."<br>".$msg_ravit."</p><p>Le ".$Corps." vous informe que votre demande a été acceptée.".$fournitures_txt."
		<br><br>Votre unité, le <b>".$Unite_Nom."</b> recevra sous peu les fournitures commandées.</p>";*/
		$msg_prod="<img src='images/vehicules/vehicule4003.gif' alt='Efficacité de la production' title='Efficacité de la production'> ".$Efficacite_prod."% 
		<img src='images/vehicules/vehicule5001.gif' alt='Efficacité du ravitaillement général' title='Efficacité du ravitaillement général'> ".$Efficacite_ravit."%";
		$msg_ravit="<img src='images/vehicules/vehicule4008.gif' alt='Efficacité du ravitaillement en carburant' title='Efficacité du ravitaillement en carburant'> ".$Efficacite_ravit_fuel." %
		<img src='images/vehicules/vehicule3002.gif' alt='Efficacité du ravitaillement en munitions' title='Efficacité du ravitaillement en munitions'> ".$Efficacite_ravit_muns." %";
		$img="<img src='images/".$img_txt.".jpg'>";
		$menu="<a title='Retour' href='index.php?view=esc_gestion' class='btn btn-default'>Retour au menu de commande des stocks</a>";
	}
	else
	{
		$titre="MIA";
		$mes="<h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
		$img="<img src='images/unites".$country.".jpg'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>