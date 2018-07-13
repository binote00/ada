<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$country=$_SESSION['country'];
	$Action=Insec($_POST['Action']);
	$Unite=Insec($_POST['Unite']);
	$Base=Insec($_POST['Base']);
	$Credits_Veh=Insec($_POST['Credits_Veh']);	
	if($Action >0 and $Unite >0 and $Credits_Veh >0)
	{
		$Credits=-1;
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result2=mysqli_query($con,"SELECT Pays,Experience,Vehicule_ID,Vehicule_Nbr FROM Regiment WHERE ID='$Unite'");
		$result=mysqli_query($con,"SELECT Avancement,Reputation,Credits,Trait FROM Officier WHERE ID='$OfficierID'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Pays=$data['Pays'];
				$Reputation_Unite=$data['Experience'];
				$Vehicule_ID=$data['Vehicule_ID'];
				$Vehicule_Nbr=$data['Vehicule_Nbr'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avancement=$data['Avancement'];
				$Reputation=$data['Reputation'];
				$Credits_ori=$data['Credits'];
				$Trait_o=$data['Trait'];
			}
			mysqli_free_result($result);
			unset($data);
		}			
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Zone,Latitude,Longitude,Citernes,Camions,Port_Ori,NoeudF_Ori,Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,
		Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,Stock_Munitions_40,Stock_Munitions_50,Stock_Munitions_60,Stock_Munitions_75,Stock_Munitions_90,
		Stock_Munitions_105,Stock_Munitions_125,Stock_Munitions_150,Stock_Munitions_200,Stock_Munitions_300,Stock_Munitions_360,
		Stock_Bombes_30,Stock_Bombes_50,Stock_Bombes_80,Stock_Bombes_125,Stock_Bombes_250,Stock_Bombes_300,Stock_Bombes_400,Stock_Bombes_500,Stock_Bombes_800,Stock_Bombes_1000,Stock_Bombes_2000
		FROM Lieu WHERE ID='$Base'");
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
				$Stock_Munitions_200=$data['Stock_Munitions_200'];
				$Stock_Munitions_300=$data['Stock_Munitions_300'];
				$Stock_Munitions_360=$data['Stock_Munitions_360'];
				$Stock_Essence_1=$data['Stock_Essence_1'];
				$Stock_Essence_87=$data['Stock_Essence_87'];
				$Stock_Essence_100=$data['Stock_Essence_100'];
				$Stock_Bombes_50=$data['Stock_Bombes_50'];
				$Stock_Bombes_125=$data['Stock_Bombes_125'];
				$Stock_Bombes_250=$data['Stock_Bombes_250'];
				$Stock_Bombes_500=$data['Stock_Bombes_500'];
				$Stock_Bombes_80=$data['Stock_Bombes_80'];
				$Stock_Bombes_300=$data['Stock_Bombes_300'];
				$Stock_Bombes_400=$data['Stock_Bombes_400'];
				$Stock_Bombes_800=$data['Stock_Bombes_800'];
				$Stock_Bombes_1000=$data['Stock_Bombes_1000'];
				$Stock_Bombes_2000=$data['Stock_Bombes_2000'];
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
			$Port_base=GetData("Lieu", "ID", $Base, "Port");
		else
			$Port_base=100;
		if($Gare_ori_base)
			$Gare_base=GetData("Lieu", "ID", $Base, "NoeudF");
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
			}
			$Efficacite_ravit=round(($Efficacite_ravit+($Efficacite_ravit_port*2))/3);
		}
		else
		{
			$Lat_base_min=$Base_Lat-1;
			$Lat_base_max=$Base_Lat+1;
			$Long_base_min=$Base_Long-3;
			$Long_base_max=$Base_Long+3;			
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
		$Saison=GetSaison($Date_Campagne);
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
		elseif($Base_Lat >45) // Europe continentale
		{
			if($Saison ==0) // Hiver
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
		$Efficacite_ravit_muns=($Efficacite_ravit-$Camions)*($Inf_base/100);
		if($Efficacite_ravit_fuel <0)$Efficacite_ravit_fuel=0;
		if($Efficacite_ravit_muns <0)$Efficacite_ravit_muns=0;
		$fournitures=0;
		switch($Action)
		{
			case 1:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((1000 + ($Reputation/10) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,1000) + ($Reputation/10) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=50000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_8)
					$fournitures=$Stock_Munitions_8;
				if($fournitures <10)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(10,100);
				}
				if($fournitures >0 and $Credits_ori >0)
				{
					UpdateData("Regiment","Stock_Munitions_8",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_8",-$fournitures,"ID",$Base);
					$Credits=-1;
					$img_txt="gestion_muns_ground";
					$units="cartouches de 8mm";
					AddEventGround(302,8,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 2:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((750 + ($Reputation/20) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,750) + ($Reputation/20) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=30000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_13)
					$fournitures=$Stock_Munitions_13;
				if($fournitures <5)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(5,75);
				}
				if($fournitures >0 and $Credits_ori >1)
				{
					UpdateData("Regiment","Stock_Munitions_13",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_13",-$fournitures,"ID",$Base);
					$Credits=-2;
					$img_txt="gestion_muns_ground";
					$units="cartouches de 13mm";
					AddEventGround(302,13,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 3:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((500 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,500) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=20000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_20)
					$fournitures=$Stock_Munitions_20;
				if($fournitures <5)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(5,50);
				}
				if($fournitures >0 and $Credits_ori >2)
				{
					UpdateData("Regiment","Stock_Munitions_20",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_20",-$fournitures,"ID",$Base);
					$Credits=-3;
					$img_txt="gestion_muns_ground";
					$units="obus de 20mm";
					AddEventGround(302,20,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 4:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((250 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,250) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=10000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_40)
					$fournitures=$Stock_Munitions_40;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,25);
				}
				if($fournitures >0 and $Credits_ori >3)
				{
					UpdateData("Regiment","Stock_Munitions_40",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_40",-$fournitures,"ID",$Base);
					$Credits=-4;
					$img_txt="gestion_muns_ground";
					$units="obus de 40mm";
					AddEventGround(302,40,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 5:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((200 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,200) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=10000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_50)
					$fournitures=$Stock_Munitions_50;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,20);
				}
				if($fournitures >0 and $Credits_ori >4)
				{
					UpdateData("Regiment","Stock_Munitions_50",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_50",-$fournitures,"ID",$Base);
					$Credits=-5;
					$img_txt="gestion_muns_ground";
					$units="obus de 50mm";
					AddEventGround(302,50,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 6:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((100 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,100) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=5000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_75)
					$fournitures=$Stock_Munitions_75;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,10);
				}
				if($fournitures >0 and $Credits_ori >5)
				{
					UpdateData("Regiment","Stock_Munitions_75",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_75",-$fournitures,"ID",$Base);
					$Credits=-6;
					$img_txt="gestion_muns_ground";
					$units="obus de 75mm";
					AddEventGround(302,75,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 7:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((75 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,75) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=2500;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_90)
					$fournitures=$Stock_Munitions_90;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,8);
				}
				if($fournitures >0 and $Credits_ori >6)
				{
					UpdateData("Regiment","Stock_Munitions_90",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_90",-$fournitures,"ID",$Base);
					$Credits=-7;
					$img_txt="gestion_muns_ground";
					$units="obus de 90mm";
					AddEventGround(302,90,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 9:
				$con=dbconnecti();
				$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$Vehicule_ID'"),0);
				$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$Vehicule_ID'"),0);
				$result1=mysqli_query($con,"SELECT Production,Stock,Repare,Categorie FROM Cible WHERE ID='$Vehicule_ID'");
				mysqli_close($con);
				if($result1)
				{
					while($dataa=mysqli_fetch_array($result1,MYSQLI_ASSOC))
					{
						$Production=$dataa['Production'];
						$Stock=floor($dataa['Stock']);
						$Reparen=$dataa['Repare'];
						$Categorie=$dataa['Categorie'];
					}
					mysqli_free_result($result1);
					unset($dataa);
				}
				if($Production >0)
				{
					$Perdus=0;
					$con=dbconnecti(4);
					$Perdus=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$Vehicule_ID'"),0);
					$Perdus2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$Vehicule_ID'"),0);
					if($Categorie ==5 or $Categorie ==6)
						$Perdus3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$Vehicule_ID'"),0);
					mysqli_close($con);
					$Perdus=$Perdus+$Perdus2+$Perdus3;
					if($Reparen >$Perdus)$Reparen=$Perdus;
					$Reste=$Stock-$Service-$Service2-$Perdus+$Reparen;
					if($Reste+$Service+$Service2 >$Stock)$Reste=$Stock-$Service-$Service2;
				}
				else
					$Reste=250;
				if($Reste >0)
				{
					$fournitures=1+(log($Reste)+($Reputation/10000))*($Efficacite_ravit_muns/100);
					if($Trait_o ==7 or $Trait_o ==15 or $Avancement <6000)
						$fournitures*=1.5;
					elseif($Avancement >=10000)
						$fournitures=$fournitures*$Avancement/10000;
					if($fournitures <1)$fournitures=1;
				}
				else
					$fournitures=0;
				if($fournitures >$Reste)$fournitures=$Reste;
				if($fournitures >0 and $Credits_ori >=$Credits_Veh)
				{
					$con=dbconnecti();
					$result2=mysqli_query($con,"SELECT Categorie,Type,Flak,mobile,HP FROM Cible WHERE ID='$Vehicule_ID'");
					mysqli_close($con);
					if($result2)
					{
						while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							$Type=$data['Type'];
							$Categorie=$data['Categorie'];
							$Flak=$data['Flak'];
							$mobile=$data['mobile'];
							$HP_ori=$data['HP'];
						}
						mysqli_free_result($result2);
						unset($data);
					}					
					if(!$Type or $Type ==93 or $Type ==96 or $Type ==97)
					{
						if($Avancement >499999)
							$Max_Veh=250;
						elseif($Avancement >199999)
							$Max_Veh=225;
						elseif($Avancement >99999)
							$Max_Veh=200;
						elseif($Avancement >49999)
							$Max_Veh=175;
						elseif($Avancement >24999)
							$Max_Veh=150;
						elseif($Avancement >9999)
							$Max_Veh=125;
						else
							$Max_Veh=100;
						if($fournitures <20)$fournitures=20;
					}
					elseif($Type ==90)
						$Max_Veh=1000;
					elseif($Type ==94 or $Type ==99 or ($Type ==98 and $Categorie ==5))
					{
						if($Avancement >199999)
							$Max_Veh=100;
						elseif($Avancement >99999)
							$Max_Veh=90;
						elseif($Avancement >49999)
							$Max_Veh=80;
						elseif($Avancement >24999)
							$Max_Veh=70;
						elseif($Avancement >9999)
							$Max_Veh=60;
						else
							$Max_Veh=50;
						if($fournitures <5)$fournitures=5;
					}
					elseif($Type ==13 or $Type ==20 or $Type ==21 or $Type ==37 or $Type ==95)
						$Max_Veh=1;
					elseif($Type ==18 or $Type ==19)
					{
						if($Avancement >99999)
							$Max_Veh=3;
						elseif($Avancement >49999)
							$Max_Veh=2;
						else
							$Max_Veh=1;
					}
					elseif($Type ==17)
					{
						if($Avancement >199999)
							$Max_Veh=6;
						elseif($Avancement >99999)
							$Max_Veh=5;
						elseif($Avancement >49999)
							$Max_Veh=4;
						elseif($Avancement >9999)
							$Max_Veh=3;
						else
							$Max_Veh=2;
					}
					elseif($mobile ==5)
					{
						if($Avancement > 499999)
							$Max_Veh=10;
						elseif($Avancement > 199999)
							$Max_Veh=9;
						elseif($Avancement > 99999)
							$Max_Veh=8;
						elseif($Avancement > 49999)
							$Max_Veh=7;
						elseif($Avancement > 24999)
							$Max_Veh=6;
						elseif($Avancement > 9999)
							$Max_Veh=5;
						else
							$Max_Veh=4;
					}
					elseif($Type ==4 or $Type ==6 or $Type ==8 or $Type ==10 or $Type ==11 or $Type ==12 or $Type ==91 or $Type ==92 or $Flak or $mobile ==4)
					{
						if($Avancement > 499999)
							$Max_Veh=12;
						elseif($Avancement > 199999)
							$Max_Veh=11;
						elseif($Avancement > 99999)
							$Max_Veh=10;
						elseif($Avancement > 49999)
							$Max_Veh=9;
						elseif($Avancement > 24999)
							$Max_Veh=8;
						elseif($Avancement > 9999)
							$Max_Veh=7;
						else
							$Max_Veh=6;
					}
					elseif($Type ==9)
					{
						if($Avancement >499999)
							$Max_Veh=18;
						elseif($Avancement >199999)
							$Max_Veh=16;
						elseif($Avancement >99999)
							$Max_Veh=14;
						elseif($Avancement >49999)
							$Max_Veh=12;
						elseif($Avancement >24999)
							$Max_Veh=10;
						elseif($Avancement >9999)
							$Max_Veh=8;
						else
							$Max_Veh=6;
					}
					else
					{
						if($Avancement > 499999)
							$Max_Veh=24;
						elseif($Avancement > 199999)
							$Max_Veh=22;
						elseif($Avancement > 99999)
							$Max_Veh=20;
						elseif($Avancement > 49999)
							$Max_Veh=18;
						elseif($Avancement > 24999)
							$Max_Veh=16;
						elseif($Avancement > 9999)
							$Max_Veh=14;
						else
							$Max_Veh=12;
					}
					$Veh_Diff=$Max_Veh-$Vehicule_Nbr;
					if($fournitures >$Veh_Diff)$fournitures=$Veh_Diff;
					if($fournitures >0 and $Vehicule_Nbr <$Max_Veh)
					{
						$fournitures=floor($fournitures);
						UpdateData("Regiment","Vehicule_Nbr",$fournitures,"ID",$Unite,$Max_Veh);
						if($mobile ==5)
							SetData("Regiment","HP",$HP_ori,"ID",$Unite);
						$Credits=-$Credits_Veh;
						$units="renforts";
						AddEventGround(239,$Vehicule_ID,$OfficierID,$Unite,$Base,$fournitures);
					}
					else
						echo "[INFO] : Nombre maximal de troupes atteint";
				}
				else
					echo "[INFO] : Crédits ou ravitaillement insuffisants : C= ".$Credits_ori."/".$Credits_Veh." ; F= ".$fournitures." ; R= ".$Reste." ; P= ".$Perdus." ; P2= ".$Perdus2." ; PR= ".$Production." ; S1= ".$Service." ; S2= ".$Service." ; RP= ".$Reparen;
				$img_txt="gestion_tanks";
			break;
			case 10:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((1000 + ($Reputation/10) + ($Reputation_Unite/1000))*($Efficacite_ravit_fuel/100));
				else
					$fournitures=floor((mt_rand(0,1000) + ($Reputation/10) + ($Reputation_Unite/1000))*($Efficacite_ravit_fuel/100));
				$Stock_Max=25000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Essence_87)$fournitures=$Stock_Essence_87;
				if($fournitures <10)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(10,100);
				}
				if($fournitures >0 and $Credits_ori >2)
				{
					UpdateData("Regiment","Stock_Essence_87",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Essence_87",-$fournitures,"ID",$Base);
					$Credits=-3;
					$img_txt="gestion_essence".$country;
					$units="litres d'essence";
					AddEventGround(301,87,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 11:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((1000 + ($Reputation/50) + ($Reputation_Unite/1000))*($Efficacite_ravit_fuel/100));
				else
					$fournitures=floor((mt_rand(0,1000) + ($Reputation/50) + ($Reputation_Unite/1000))*($Efficacite_ravit_fuel/100));
				if(GetData("Cible","ID",$Vehicule_ID,"mobile") ==5)
				{
					$fournitures*=2;
					$Stock_Max=250000;
				}
				else
					$Stock_Max=25000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Essence_1)$fournitures=$Stock_Essence_1;
				if($fournitures <10)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(10,100);
				}
				if($fournitures >0 and $Credits_ori >4)
				{
					UpdateData("Regiment","Stock_Essence_1",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Essence_1",-$fournitures,"ID",$Base);
					$Credits=-5;
					$img_txt="gestion_essence".$country;
					$units="litres de diesel";
					AddEventGround(301,1,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 12:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((25 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,25) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				if($fournitures >$Stock_Munitions_150)$fournitures=$Stock_Munitions_150;
				$Stock_Max=1000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,2);
				}
				if($fournitures >0 and $Credits_ori >9)
				{
					UpdateData("Regiment","Stock_Munitions_150",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_150",-$fournitures,"ID",$Base);
					$Credits=-10;
					$img_txt="gestion_muns_ground";
					$units="obus de 150mm";
					AddEventGround(302,150,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 14:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((400 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,400) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				if($fournitures >$Stock_Munitions_30)$fournitures=$Stock_Munitions_30;
				$Stock_Max=20000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures <5)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(5,40);
				}
				if($fournitures >0 and $Credits_ori >2)
				{
					UpdateData("Regiment","Stock_Munitions_30",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_30",-$fournitures,"ID",$Base);
					$Credits=-3;
					$img_txt="gestion_muns_ground";
					$units="obus de 30mm";
					AddEventGround(302,30,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 15:
				$con=dbconnecti(4);
				$Perdus=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$Vehicule_ID'"),0);
				$Perdus2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$Vehicule_ID'"),0);
				mysqli_close($con);
				$Perdus+=$Perdus2;
				if($Perdus >0)
				{				
					if($Credits_Veh >$CT_MAX)$Credits_Veh=$CT_MAX;
					if($Credits_ori >=$Credits_Veh)
					{
						$Credits=-$Credits_Veh;
						UpdateData("Cible","Repare",1,"ID",$Vehicule_ID,$Perdus);
						UpdateData("Officier","Avancement",$Credits_Veh,"ID",$OfficierID);
						UpdateData("Officier","Reputation",$Credits_Veh,"ID",$OfficierID);
						$repare=true;
					}
				}
				$img_txt="gestion_tanks";
			break;
			case 16:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((150 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,150) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=5000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_60)$fournitures=$Stock_Munitions_60;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,15);
				}
				if($fournitures >0 and $Credits_ori >4)
				{
					UpdateData("Regiment","Stock_Munitions_60",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_60",-$fournitures,"ID",$Base);
					$Credits=-5;
					$img_txt="gestion_muns_ground";
					$units="obus de 60mm";
					AddEventGround(302,60,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 17:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((30 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,30) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=5000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_125)$fournitures=$Stock_Munitions_125;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,3);
				}
				if($fournitures >0 and $Credits_ori >9)
				{
					UpdateData("Regiment","Stock_Munitions_125",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_125",-$fournitures,"ID",$Base);
					$Credits=-9;
					$img_txt="gestion_muns_ground";
					$units="obus de 125mm";
					AddEventGround(302,125,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 8: case 13:
				$Qty=Insec($_POST['Fret'.$Action]);
				SetData("Regiment","Fret",$Action,"ID",$Unite);
				SetData("Regiment","Fret_Qty",$Qty,"ID",$Unite);
				UpdateData("Lieu","Stock_Munitions_".$Action,-$Qty,"ID",$Base);
				$units="cartouches de ".$Action."mm";
				$fret=1;
			break;
			case 20: case 30: case 40: case 50: case 60: case 75: case 90: case 105: case 125: case 150:
				$Qty=Insec($_POST['Fret'.$Action]);
				SetData("Regiment","Fret",$Action,"ID",$Unite);
				SetData("Regiment","Fret_Qty",$Qty,"ID",$Unite);
				UpdateData("Lieu","Stock_Munitions_".$Action,-$Qty,"ID",$Base);
				$units="obus de ".$Action."mm";
				$fret=1;
			break;
			case 31: case 32: case 33: case 34: case 35: case 36: case 37: case 38: case 930:
				SetData("Regiment","Muns",$Action-30,"ID",$Unite);
				$muns=1;
				$img_txt="gestion_muns6";
			break;
			case 130:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((50 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,50) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=500;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Bombes_300)$fournitures=$Stock_Bombes_300;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,5);
				}
				if($fournitures >0 and $Credits_ori >3)
				{
					UpdateData("Regiment","Stock_Charges",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Bombes_300",-$fournitures,"ID",$Base);
					$Credits=-4;
					$img_txt="gestion_muns_ground";
					$units="charges de profondeur";
					AddEventGround(302,300,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 140:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((10 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,10) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=500;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Bombes_400)$fournitures=$Stock_Bombes_400;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=1;
				}
				if($fournitures >0 and $Credits_ori >3)
				{
					UpdateData("Regiment","Stock_Mines",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Bombes_400",-$fournitures,"ID",$Base);
					$Credits=-4;
					$img_txt="gestion_muns_ground";
					$units="mines";
					AddEventGround(302,400,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 180:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((10 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,10) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=1000;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Bombes_800)$fournitures=$Stock_Bombes_800;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=1;
				}
				if($fournitures >0 and $Credits_ori >7)
				{
					if($country ==9)
					{
						UpdateData("Regiment","Stock_Munitions_530",$fournitures,"ID",$Unite,$Stock_Max);
						UpdateData("Regiment","Stock_Munitions_610",$fournitures,"ID",$Unite,$Stock_Max);
					}
					else
						UpdateData("Regiment","Stock_Munitions_530",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Bombes_800",-$fournitures,"ID",$Base);
					$Credits=-8;
					$img_txt="gestion_muns_ground";
					$units="torpilles";
					AddEventGround(302,800,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 200:
				/*SetData("Regiment","Fret",200,"ID",$Unite);
				UpdateData("Lieu","Stock_Troupes",-100,"ID",$Base);
				$units=" troupes";
				$fret=1;*/
			break;
			case 80: case 300: case 400: case 800:
				$Qty=Insec($_POST['Fret'.$Action]);
				SetData("Regiment","Fret",$Action,"ID",$Unite);
				SetData("Regiment","Fret_Qty",$Qty,"ID",$Unite);
				UpdateData("Lieu","Stock_Bombes_".$Action,-$Qty,"ID",$Base);
				$units=" charges";
				$fret=1;
			break;
			case 888:
				SetData("Regiment","Fret",888,"ID",$Unite);
				$units=" Lend-Lease";
				$fret=1;
			break;
			case 9050: case 9125: case 9250: case 9500: case 10000: case 11000:
				$Qty=Insec($_POST['Fret'.$Action]);
				SetData("Regiment","Fret",$Action,"ID",$Unite);
				SetData("Regiment","Fret_Qty",$Qty,"ID",$Unite);
				$Action -=9000;
				UpdateData("Lieu","Stock_Bombes_".$Action,-$Qty,"ID",$Base);
				$units=" bombes";
				$fret=1;
			break;
			case 908:
				SetData("Regiment","Moral",100,"ID",$Unite);
				$Moral=100;
				$img_txt="rest";
			break;
			case 913:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((50 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,50) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=1500;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_105)$fournitures=$Stock_Munitions_105;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=mt_rand(1,5);
				}
				if($fournitures >0 and $Credits_ori >7)
				{
					UpdateData("Regiment","Stock_Munitions_105",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_105",-$fournitures,"ID",$Base);
					$Credits=-8;
					$img_txt="gestion_muns_ground";
					$units="obus de 105mm";
					AddEventGround(302,105,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 999:
				$HP_Max=GetData("Cible","ID",$Vehicule_ID,"HP");
				if($HP_Max >0)
				{
					if($Credits_Veh >$CT_MAX)$Credits_Veh=$CT_MAX;
					if($Credits_ori >=$Credits_Veh)
					{
						$Credits=-$Credits_Veh;
						SetData("Regiment","HP",$HP_Max,"ID",$Unite);
						UpdateData("Officier","Avancement",$Credits_Veh,"ID",$OfficierID);
						$repare=true;
					}
				}
				$img_txt="gestion_cale_seche";
			break;
			case 1001:
				$Qty=Insec($_POST['Fret'.$Action]);
				SetData("Regiment","Fret",$Action,"ID",$Unite);
				SetData("Regiment","Fret_Qty",$Qty,"ID",$Unite);
				UpdateData("Lieu","Stock_Essence_1",-$Qty,"ID",$Base);
				$units=" diesel";
				$fret=1;
			break;
			case 1087: case 1100:
				$Qty=Insec($_POST['Fret'.$Action]);
				SetData("Regiment","Fret",$Action,"ID",$Unite);
				SetData("Regiment","Fret_Qty",$Qty,"ID",$Unite);
				$Action-=1000;
				UpdateData("Lieu","Stock_Essence_".$Action,-$Qty,"ID",$Base);
				$units=" essence ".$Action;
				$fret=1;
			break;
			case 1200:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((10 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,10) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=500;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_200)$fournitures=$Stock_Munitions_200;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=1;
				}
				if($fournitures >0 and $Credits_ori >12)
				{
					UpdateData("Regiment","Stock_Munitions_200",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_200",-$fournitures,"ID",$Base);
					$Credits=-12;
					$img_txt="gestion_muns_ground";
					$units="obus de 200mm";
					AddEventGround(302,1200,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 1300:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((5 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,5) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=200;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_300)$fournitures=$Stock_Munitions_300;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=1;
				}
				if($fournitures >0 and $Credits_ori >15)
				{
					UpdateData("Regiment","Stock_Munitions_300",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_300",-$fournitures,"ID",$Base);
					$Credits=-15;
					$img_txt="gestion_muns_ground";
					$units="obus de 300mm";
					AddEventGround(302,1300,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 1360:
				if(IsSkill(42,$OfficierID))
					$fournitures=floor((5 + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				else
					$fournitures=floor((mt_rand(0,5) + ($Reputation/100) + ($Reputation_Unite/1000))*($Efficacite_ravit_muns/100));
				$Stock_Max=200;
				if($Trait_o ==14)
				{
					$Stock_Max*=1.1;
					$fournitures*=1.1;
				}
				if($fournitures >$Stock_Munitions_360)$fournitures=$Stock_Munitions_360;
				if($fournitures <1)
				{
					if(IsSkill(40,$OfficierID))
						$fournitures=1;
				}
				if($fournitures >0 and $Credits_ori >16)
				{
					UpdateData("Regiment","Stock_Munitions_360",$fournitures,"ID",$Unite,$Stock_Max);
					UpdateData("Lieu","Stock_Munitions_360",-$fournitures,"ID",$Base);
					$Credits=-16;
					$img_txt="gestion_muns_ground";
					$units="obus de 360mm";
					AddEventGround(302,1360,$OfficierID,$Unite,$Base,$fournitures);
				}
			break;
			case 5432:
				$Cie_EM=Insec($_POST['Cie']);
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Fret=200,Fret_Qty='$Cie_EM' WHERE ID='$Unite'");
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Camouflage=1,Placement=9,Position=11,Visible=0 WHERE ID='$Cie_EM'");
				mysqli_close($con);		
				$units=" troupes";
				$Credits=-$Credits_Veh;
				$img_txt="embarquement";
				$fret=1;
			break;
		}
		if(!$img_txt)$img_txt="fret";
		UpdateCarac($OfficierID,"Credits",$Credits,"Officier");
		//UpdateCarac($OfficierID,"Avancement",-$Credits);
		$msg_prod="<img src='images/vehicules/vehicule4003.gif' alt='Efficacité de la production' title='Efficacité de la production'> ".round($Efficacite_prod)."% 
		<img src='images/vehicules/vehicule5001.gif' alt='Efficacité du ravitaillement général' title='Efficacité du ravitaillement général'> ".round($Efficacite_ravit)."%";
		$msg_ravit="<img src='images/vehicules/vehicule4008.gif' alt='Efficacité du ravitaillement en carburant' title='Efficacité du ravitaillement en carburant'> ".round($Efficacite_ravit_fuel)." %
		<img src='images/vehicules/vehicule3002.gif' alt='Efficacité du ravitaillement en munitions' title='Efficacité du ravitaillement en munitions'> ".round($Efficacite_ravit_muns)." %";
		if($muns ==1)
			$mes="Vos troupes s'équipent des munitions adéquates";
		elseif($fret ==1)
		{
			SetData("Officier","Depot",$Base,"ID",$OfficierID);
			$mes="Vous chargez ".$Qty." ".$units." dans vos véhicules";
		}
		elseif($repare ==true)
			$mes="<p>Vos mécaniciens réparent un véhicule endommagé</p>";
		elseif($fournitures >0)
		{
			$fournitures_txt="<br>Nous sommes en mesure de vous fournir actuellement <b>".$fournitures." ".$units.".</b>";
			$mes="<table class='table'>
				<thead><tr><th>Production</th><th>Ravitaillement</th><th>Carburant</th><th>Munitions</th></tr></thead>
				<tr><td><img src='images/vehicules/vehicule4003.gif' alt='Efficacité de la production' title='Efficacité de la production'>".round($Efficacite_prod)."% </td>
				<td><img src='images/vehicules/vehicule5001.gif' alt='Efficacité du ravitaillement' title='Efficacité du ravitaillement'>".round($Efficacite_ravit)."%</td>
				<td><img src='images/vehicules/vehicule4008.gif' alt='Efficacité du ravitaillement en carburant' title='Efficacité du ravitaillement en carburant'>".round($Efficacite_ravit_fuel)."%</td>
				<td><img src='images/vehicules/vehicule3002.gif' alt='Efficacité du ravitaillement en munitions' title='Efficacité du ravitaillement en munitions'>".round($Efficacite_ravit_muns)."%</td></tr></table>";
			$mes.="<p>L'état-major vous informe que votre demande a été acceptée.".$fournitures_txt."<br>Votre unité, la <b>".$Unite."e Cie</b>, recevra sous peu les fournitures commandées</p>";
		}
		elseif($Moral ==100)
			$mes="Vos hommes prennent un repos bien mérité et sont de nouveau en pleine forme!";
		else
			$mes="L'état-major vous informe que votre demande a été refusée.<br>Vos stocks ne sont pas suffisants ou votre demande est irréalisable au vu de la situation actuelle.";
		$img="<img src='images/".$img_txt.".jpg'>";
	}
	else
		$mes="Tsss...";
	$titre="Ravitaillement";
	$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
	include_once('./default.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>