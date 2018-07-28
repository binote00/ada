<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Reg=Insec($_POST['Reg']);
	$Base=Insec($_POST['Base']);
	$Max_Veh=1;
	$Regs_txt="";
	$con=dbconnecti();
	$resulto=mysqli_query($con,"SELECT Credits,Avancement,Division,Front FROM Officier WHERE ID='$OfficierID'");
	$resultreg=mysqli_query($con,"SELECT ID FROM Regiment WHERE Officier_ID='$OfficierID'");
	//mysqli_close($con);
	if($resultreg)
	{
		while($datareg=mysqli_fetch_array($resultreg))
		{
			$Regs[]=$datareg['ID']; 
		}
		mysqli_free_result($resultreg);
	}
	if($resulto)
	{
		while($data=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
		{
			$Credits=$data['Credits'];
			$Avancement=$data['Avancement'];
			$Division=$data['Division'];
			$Front=$data['Front'];
		}
		mysqli_free_result($resulto);
	}
	$Regiments=implode(',',$Regs);
	//GetData Regiments
		//$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result2=mysqli_query($con,"SELECT Latitude,Longitude,Zone,Citernes,Camions,Port_Ori,NoeudF_Ori FROM Lieu WHERE ID='$Base'");
		$result3=mysqli_query($con,"SELECT COUNT(*),SUM(Industrie) FROM Lieu WHERE Flag='$country' AND TypeIndus<>'' AND Flag_Usine='$country'");
		$result=mysqli_query($con,"SELECT * FROM Regiment WHERE ID='$Reg'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Veh=$data['Vehicule_ID'];
				$Vehicule_Nbr=$data['Vehicule_Nbr'];
				$HP_PA=$data['HP'];
				$con=dbconnecti();
				$Categorie=mysqli_result(mysqli_query($con,"SELECT Categorie FROM Cible WHERE ID='$Veh'"),0);
				$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$Veh'"),0);
				$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$Veh'"),0);
				$result1=mysqli_query($con,"SELECT Nom,Reput,Fuel,Carbu_ID,Charge,Type,Flak,Production,Stock,Repare,Arme_AT,Arme_Art,HP,Categorie,mobile FROM Cible WHERE ID='$Veh'");
				mysqli_close($con);
				$con4=dbconnecti(4);
				$Perdus=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$Veh'"),0);
				$Perdus2=mysqli_result(mysqli_query($con4,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$Veh'"),0);
				if($Categorie ==5 or $Categorie ==6)
					$Perdus3=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$Veh'"),0);
				//$Pertes=mysqli_result(mysqli_query($con4,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Unit IN (".$Regiments.") AND Pilote_eni='$Veh'"),0);
				$Pertes=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (401,405,415,410,605,615) AND Unit IN (".$Regiments.") AND Avion='$Veh'"),0);
				$Pertes2=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,404,420) AND Unit IN (".$Regiments.") AND Avion='$Veh' AND PlayerID='$OfficierID'"),0);
				mysqli_close($con4);
				if($result1)
				{
					while($dataa=mysqli_fetch_array($result1,MYSQLI_ASSOC))
					{
						$Vehicule=$dataa['Nom'];
						$Credits_Veh=$dataa['Reput'];
						$Fuel=$dataa['Fuel'];
						$Carbu_ID=$dataa['Carbu_ID'];
						$Charge=$dataa['Charge'];
						$Type=$dataa['Type'];
						$Flak=$dataa['Flak'];
						$Production=$dataa['Production'];
						$Stock=floor($dataa['Stock']);
						$Repare=$dataa['Repare'];
						$Arme_AT=$dataa['Arme_AT'];
						$Arme_Art=$dataa['Arme_Art'];
						$HP_MAX_PA=$dataa['HP'];
						$Categorie=$dataa['Categorie'];
						$mobile=$dataa['mobile'];
					}
					mysqli_free_result($result1);
					unset($dataa);
				}
				if($Type <22 and $Type >17 and $HP_PA >0)
					$Credits_Veh=round($Credits_Veh/($HP_MAX_PA/$HP_PA));
				$Perdus=$Perdus+$Perdus2+$Perdus3;
				$Pertes+=$Pertes2;
				if($Pertes >0)
				{
					if($Pertes >=$Production/5)
						$Bourrin=true;
					elseif($Pertes >=$Production/20 and $Pertes >($Perdus/2))
						$Bourrin=true;
				}
				if($Repare >$Perdus)$Repare=$Perdus;
				$Reste=$Stock-$Service-$Service2-$Perdus+$Repare;
				if($Reste+$Service+$Service2 >$Stock)$Reste=$Stock-$Service-$Service2;
				$A_Reparer=$Perdus-$Repare;
				if($Production ==0)$Reste=999;
				$Stock_Diesel_Max=25000;
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
				}
				elseif($Type ==13 or $Type ==95)
					$Max_Veh=1;
				elseif($Type ==37)
				{
					$Max_Veh=1;
					$Stock_Diesel_Max=10000;
				}
				elseif($Type ==20 or $Type ==21)
				{
					$Max_Veh=1;
					$Stock_Diesel_Max=250000;
				}
				elseif($Type ==18 or $Type ==19)
				{
					if($Avancement >99999)
						$Max_Veh=4;
					elseif($Avancement >49999)
						$Max_Veh=3;
					elseif($Avancement >9999)
						$Max_Veh=2;
					else
						$Max_Veh=1;
					$Stock_Diesel_Max=100000*$Max_Veh;
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
					$Stock_Diesel_Max=10000*$Max_Veh;
				}
				elseif($mobile ==5)
				{
					if($Avancement >499999)
						$Max_Veh=10;
					elseif($Avancement >199999)
						$Max_Veh=9;
					elseif($Avancement >99999)
						$Max_Veh=8;
					elseif($Avancement >49999)
						$Max_Veh=7;
					elseif($Avancement >24999)
						$Max_Veh=6;
					elseif($Avancement >9999)
						$Max_Veh=5;
					else
						$Max_Veh=4;
					$Stock_Diesel_Max=10000*$Max_Veh;
				}
				elseif($Type ==4 or $Type ==6 or $Type ==8 or $Type ==10 or $Type ==11 or $Type ==12 or $Type ==91 or $Type ==92 or $Flak or $mobile ==4)
				{
					if($Avancement >499999)
						$Max_Veh=12;
					elseif($Avancement >199999)
						$Max_Veh=11;
					elseif($Avancement >99999)
						$Max_Veh=10;
					elseif($Avancement >49999)
						$Max_Veh=9;
					elseif($Avancement >24999)
						$Max_Veh=8;
					elseif($Avancement >9999)
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
					if($Avancement >499999)
						$Max_Veh=24;
					elseif($Avancement >199999)
						$Max_Veh=22;
					elseif($Avancement >99999)
						$Max_Veh=20;
					elseif($Avancement >49999)
						$Max_Veh=18;
					elseif($Avancement >24999)
						$Max_Veh=16;
					elseif($Avancement >9999)
						$Max_Veh=14;
					else
						$Max_Veh=12;
				}					
				if(!$Charge)
					$Charge_txt="Aucune";
				else
					$Charge_txt=$Charge."kg/l";				
				$Regs_txt.="<tr><td><img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$Vehicule."'></td><td>".$Vehicule_Nbr."/".$Max_Veh."</td>
				<td>".$data['Stock_Munitions_8']."/50000</td><td>".$data['Stock_Munitions_13']."/30000</td><td>".$data['Stock_Munitions_20']."/20000</td><td>".$data['Stock_Munitions_30']."/20000</td>
				<td>".$data['Stock_Munitions_40']."/10000</td><td>".$data['Stock_Munitions_50']."/10000</td><td>".$data['Stock_Munitions_60']."/10000</td><td>".$data['Stock_Munitions_75']."/5000</td>
				<td>".$data['Stock_Munitions_90']."/2500</td><td>".$data['Stock_Munitions_105']."/1500</td><td>".$data['Stock_Munitions_125']."/1000</td><td>".$data['Stock_Munitions_150']."/1000</td>
				<td>".$data['Stock_Essence_87']."/25000</td><td>".$data['Stock_Essence_1']."/".$Stock_Diesel_Max."</td><td>".$Charge_txt."</td></tr>";
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Base_Lat=$data['Latitude'];
				$Base_Long=$data['Longitude'];
				$Zone=$data['Zone'];
				$Citernes=$data['Citernes'];
				$Camions=$data['Camions'];	
				$Port_ori_base=$data['Port_Ori'];
				$Gare_ori_base=$data['NoeudF_Ori'];
			}
			mysqli_free_result($result2);
			unset($data);
		}		
		if($result3)
		{
			if($data=mysqli_fetch_array($result3,MYSQLI_NUM))
			{
				if($data[0] >0)
					$Efficacite_prod=round($data[1]/$data[0]);
				else
					$Efficacite_prod=0;
			}
			mysqli_free_result($result3);
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
		if($Base_Lat <38.2 or $Base_Long >70 or $country ==2 or $Zone ==6)
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
				if($data2=mysqli_fetch_array($result2,MYSQLI_NUM))
				{
					if($data2[0] >0)
						$Efficacite_ravit=round($data2[1]/$data2[0]);
					else
						$Efficacite_ravit=0;
				}
				mysqli_free_result($result2);
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
		if($Base_Long >20 and $Base_Lat >45)		//Front Est
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
		$Efficacite_ravit_fuel=round($Efficacite_ravit-$Citernes,2);
		$Efficacite_ravit_muns=round($Efficacite_ravit-$Camions,2);
		if($Efficacite_ravit_fuel <0)$Efficacite_ravit_fuel=0;
		if($Efficacite_ravit_muns <0)$Efficacite_ravit_muns=0;		
		if($Efficacite_ravit_muns <1)
			$Credits_Veh=50;
		elseif($Efficacite_ravit_muns <25)
			$Credits_Veh*=2;
		elseif($Efficacite_ravit_muns <50 and $country !=7)
			$Credits_Veh*=1.5;
		$Cal_AT=GetData("Armes","ID",$Arme_AT,"Calibre");
		$Cal_Art=GetData("Armes","ID",$Arme_Art,"Calibre");
		//Depot
		$query="SELECT DISTINCT ID,Nom,Longitude,Latitude,ValeurStrat,Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,
		Stock_Munitions_40,Stock_Munitions_50,Stock_Munitions_60,Stock_Munitions_75,Stock_Munitions_90,Stock_Munitions_105,Stock_Munitions_125,Stock_Munitions_150,Stock_Munitions_200,Stock_Munitions_300,Stock_Munitions_360,
		Stock_Bombes_30,Stock_Bombes_50,Stock_Bombes_80,Stock_Bombes_125,Stock_Bombes_250,Stock_Bombes_300,Stock_Bombes_400,Stock_Bombes_500,Stock_Bombes_800,Stock_Bombes_1000,Stock_Bombes_2000
		FROM Lieu WHERE ID='$Base'";
		$con=dbconnecti();
		$Cal_AT=mysqli_result(mysqli_query($con,"SELECT Calibre FROM Armes WHERE ID='$Arme_AT'"),0);
		$Cal_Art=mysqli_result(mysqli_query($con,"SELECT Calibre FROM Armes WHERE ID='$Arme_Art'"),0);
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
					$Stock_Munitions_200=$data['Stock_Munitions_200'];
					$Stock_Munitions_300=$data['Stock_Munitions_300'];
					$Stock_Munitions_360=$data['Stock_Munitions_360'];
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
					$Stock_Essence_1=$data['Stock_Essence_1'];
					$Stock_Essence_87=$data['Stock_Essence_87'];
					$Stock_Essence_100=$data['Stock_Essence_100'];
					$depot_info="<h3>Dépôt de ".$data['Nom']."</h3><div style='overflow:auto;'><table class='table'>
						<thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
						<th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th><th>Munitions 200mm</th><th>Munitions 300mm</th><th>Munitions 360mm</th>
						<th>Charges de Profondeur</th><th>Mines</th><th>Torpilles</th><th>Rockets</th></tr></thead>
						<tr><td>".$data['Stock_Essence_87']."</td><td>".$data['Stock_Essence_100']."</td><td>".$data['Stock_Essence_1']."</td><td>".$data['Stock_Munitions_8']."</td><td>".$data['Stock_Munitions_13']."</td>
						<td>".$data['Stock_Munitions_20']."</td><td>".$data['Stock_Munitions_30']."</td><td>".$data['Stock_Munitions_40']."</td><td>".$data['Stock_Munitions_50']."</td><td>".$data['Stock_Munitions_60']."</td>
						<td>".$data['Stock_Munitions_75']."</td><td>".$data['Stock_Munitions_90']."</td><td>".$data['Stock_Munitions_105']."</td><td>".$data['Stock_Munitions_125']."</td><td>".$data['Stock_Munitions_150']."</td>
						<td>".$data['Stock_Munitions_200']."</td><td>".$data['Stock_Munitions_300']."</td><td>".$data['Stock_Munitions_360']."</td>
						<td>".$data['Stock_Bombes_300']."</td><td>".$data['Stock_Bombes_400']."</td><td>".$data['Stock_Bombes_800']."</td><td>".$data['Stock_Bombes_80']."</td></tr>
						</table></div>";
					//$depot="<br><Input type='Radio' name='Action' value='".$data['ID']."_depot'>- Dépôt de ".$data['Nom']."<br>";
				}
			}
			mysqli_free_result($result);
		}
?>
<h1>Ravitaillement</h1>
<table class='table'>
<thead><tr><th>Production</th><th>Ravitaillement</th><th>Carburant</th><th>Munitions</th></tr></thead>
<tr><td><img src='images/vehicules/vehicule4003.gif' alt='Efficacité de la production' title='Efficacité de la production'><?echo $Efficacite_prod;?>% </td>
<td><img src='images/vehicules/vehicule5001.gif' alt='Efficacité du ravitaillement' title='Efficacité du ravitaillement'><?echo $Efficacite_ravit;?>%</td>
<td><img src='images/vehicules/vehicule4008.gif' alt='Efficacité du ravitaillement en carburant' title='Efficacité du ravitaillement en carburant'><?echo $Efficacite_ravit_fuel;?>%</td>
<td><img src='images/vehicules/vehicule3002.gif' alt='Efficacité du ravitaillement en munitions' title='Efficacité du ravitaillement en munitions'><?echo $Efficacite_ravit_muns;?>%</td></tr></table>
<h2>Stocks de la Compagnie</h2>
	<div style='overflow:auto; width: 100%;'><table class='table'>
		<thead><tr>                                                             
			<th>Véhicules / Troupes</th>                         
			<th>Effectifs</th>  
			<th>8mm</th>
			<th>13mm</th>
			<th>20mm</th>
			<th>30mm</th>
			<th>40mm</th>
			<th>50mm</th>
			<th>60mm</th>
			<th>75mm</th>
			<th>90mm</th>
			<th>105mm</th>
			<th>125mm</th>
			<th>150mm</th>
			<th>Essence</th>
			<th>Diesel</th>
			<th>Charge</th>
		</tr></thead>
	<?echo $Regs_txt;?>
	</table></div>
<h2>Ravitaillement de la Compagnie <a href='#' class='popup'><img src='images/help.png'><span>Privilégiez toujours le ravitaillement entre joueurs plutôt que le ravitaillement directement au dépôt. Pour ce faire, utilisez les transmissions.</span></a></h2>
<?echo $depot_info;?>
	<form action='index.php?view=ground_ravit1' method='post'>
	<input type='hidden' name='Unite' value='<? echo $Reg;?>'>
	<input type='hidden' name='Base' value='<? echo $Base;?>'>
	<input type='hidden' name='Credits_Veh' value='<? echo $Credits_Veh;?>'>
	<table class='table'>
		<tr><td align='left'><div class='row'><div class='col-md-6'>
				<?if($Credits <1){?>
				Vous ne disposez pas de suffisamment de Crédits Temps pour gérer les stocks de votre unité !
				<?}else{
					if($Credits >=1 and $Stock_Munitions_8 >0){?>
					<Input type='Radio' name='Action' value='1'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 8mm<br>
					<?}if($Credits >=2 and $Stock_Munitions_13 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='2'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 13mm<br>
					<?}if($Credits >=3 and $Stock_Munitions_20 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='3'><img src='/images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 20mm<br>
					<?}if($Credits >=3 and $Stock_Munitions_30 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='14'><img src='/images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 30mm<br>
					<?}if($Credits >=4 and $Stock_Munitions_40 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='4'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 40mm<br>
					<?}if($Credits >=5 and $Stock_Munitions_50 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='5'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 50mm<br>
					<?}if($Credits >=5 and $Stock_Munitions_60 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='16'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 60mm<br>
					<?}if($Credits >=6 and $Stock_Munitions_75 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='6'><img src='/images/CT6.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 75mm<br>
					<?}if($Credits >=7 and $Stock_Munitions_90 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='7'><img src='/images/CT7.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 90mm<br>
					<?}if($Credits >=8 and $Stock_Munitions_105 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='913'><img src='/images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 105mm<br>
					<?}if($Credits >=9 and $Stock_Munitions_125 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='17'><img src='/images/CT9.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 125mm<br>
					<?}if($Credits >=10 and $Stock_Munitions_150 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='12'><img src='/images/CT10.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 150mm<br>
					<?}if($Credits >=12 and $Stock_Munitions_200 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='1200'><img src='/images/CT12.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 200mm<br>
					<?}if($Credits >=15 and $Stock_Munitions_300 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='1300'><img src='/images/CT15.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 300mm<br>
					<?}if($Credits >=16 and $Stock_Munitions_360 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='1360'><img src='/images/CT16.png' title='Montant en Crédits Temps que nécessite cette action'>- Munitions 360mm<br>
					<?}?>
					</div><div class='col-md-6'>
					<?if($Credits >=4 and $Stock_Bombes_300 >0){?>
					<Input type='Radio' name='Action' value='130'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Charges de profondeur <a href='#' class='popup'><img src='images/help.png'><span>Toutes les charges ASM sont considérées comme une munition de type HE</span></a><br>
					<?}if($Credits >=4 and $Stock_Bombes_400 >0){?>
					<Input type='Radio' name='Action' value='140'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Mines <a href='#' class='popup'><img src='images/help.png'><span>Toutes les mines sont considérées comme une munition de type HE</span></a><br>
					<?}if($Credits >=8 and $Stock_Bombes_800 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='180'><img src='/images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'>- Torpilles <a href='#' class='popup'><img src='images/help.png'><span>Toutes les torpilles sont considérées comme une munition de type anti-navire</span></a><br>
					<?}if($Credits >=3 and $Carbu_ID >0 and $Stock_Essence_87 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='10'><img src='/images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'>- Essence<br>
					<?}if($Credits >=5 and $Carbu_ID >0 and $Stock_Essence_1 >0 and $Division >0){?>
					<Input type='Radio' name='Action' value='11'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'>- Diesel<br>
					<?}
					if($Credits_Veh >$CT_MAX)
					{
						?><Input type='Radio' name='Action' value='9' disabled><img src='/images/CT99.png' title='Crédits Temps insuffisants'>- <b><?echo $Vehicule;?></b> <a href='#' class='popup'><img src='images/help.png'><span>Le niveau de ravitaillement est trop faible pour ce type de troupe</span></a><br><?
					}
					elseif($Credits >=$Credits_Veh and $Vehicule_Nbr <$Max_Veh)
					{
						if($Bourrin and $Production >0){?>
							<Input type='Radio' name='Action' value='9' disabled><img src='/images/CT<?echo $Credits_Veh;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- <b><?echo $Vehicule;?></b> <a href='#' class='popup'><img src='images/help.png'><span>Vous ne pouvez pas renforcer les troupes de la Compagnie car vos pertes sont trop importantes</span></a><br>
						<?}elseif($Reste >0){?>
							<Input type='Radio' name='Action' value='9'><img src='/images/CT<?echo $Credits_Veh;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Ajouter un <b><?echo $Vehicule;?></b> <a href='#' class='popup'><img src='images/help.png'><span>Renforce les troupes de la Compagnie</span></a><br>
					<?}else{?>
							<Input type='Radio' name='Action' value='9' disabled><img src='/images/CT<?echo $Credits_Veh;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- <b><?echo $Vehicule;?></b> <a href='#' class='popup'><img src='images/help.png'><span>Vous ne pouvez pas renforcer les troupes de la Compagnie car tous ceux du stock ont été détruits ou endommagés</span></a><br>
					<?}
					}
					if($Perdus >0 and $Credits >=$Credits_Veh and $Categorie !=5 and $mobile !=5){?>
					<Input type='Radio' name='Action' value='15'><img src='/images/CT<?echo $Credits_Veh;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Réparer un <b><?echo $Vehicule;?></b> (<?echo $A_Reparer."/".$Perdus;?>)<a href='#' class='popup'><img src='images/help.png'><span>Le chiffre de gauche représente le nombre à réparer, le second le total perdus. Réparer accorde un bonus important à la réputation et au grade de votre officier.</span></a><br>
					<?}elseif($HP_PA <$HP_MAX_PA and $Type >13 and $Type <38)
					{
						if($Credits_Veh >$CT_MAX)$Credits_Veh=$CT_MAX;
						if($Credits >=$Credits_Veh and $HP_PA >0)
							echo "<Input type='Radio' name='Action' value='999'><img src='/images/CT".$Credits_Veh.".png' title='Montant en Crédits Temps que nécessite cette action'>- Réparer un ".$Vehicule."<br>";
						else
							echo "<Input type='Radio' name='Action' value='999' disabled><img src='/images/CT".$Credits_Veh.".png' title='Montant en Crédits Temps que nécessite cette action'>- Réparer un ".$Vehicule."<br>";
					}
					if($Credits >=1)
						echo "<Input type='Radio' name='Action' value='908'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Repos <a href='#' class='popup'><img src='images/help.png'><span>Permet à votre Compagnie de récupérer du moral</span></a><br>";
				}?>
		</div></div><td></tr></table>
		<table class='table'>
		<thead><tr><th colspan="3">Munitions par défaut <img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'></th></tr></thead>
		<tr><td align="left">
				<Input type='Radio' name='Action' value='930'>Standard<br>
				<Input type='Radio' name='Action' value='31'>AP (Perforant, chance d'ignorer un blindage lourd à courte portée)<br>
				<Input type='Radio' name='Action' value='32'>HE (Explosif, dégâts supplémentaires importants contre cibles non blindées)<br>
				<Input type='Radio' name='Action' value='34'>APHE (Chance d'ignorer un blindage léger. Dégâts supplémentaires importants si blindage perforé)<br>
				<Input type='Radio' name='Action' value='36'>APCR (Perforant, chance d'ignorer un blindage lourd à moyenne portée)<br>
				<?if($Arme_AT >0 and $Cal_AT >19 and $Date_Campagne >'1944-01-01'){?>
				<Input type='Radio' name='Action' value='37'>APDS (Sabot, chance d'ignorer un blindage lourd à longue portée. Uniquement pour armes AT !)<br>
				<?}if($Arme_Art >0 and $Cal_Art >69 and $Date_Campagne >'1941-01-01'){?>
				<Input type='Radio' name='Action' value='38'>HEAT (Charge creuse, chance d'ignorer un blindage à courte portée, dégâts supplémentaires contre cibles non blindées. Réduit la portée de tir ! Uniquement pour armes de soutien !)<br>
				<?}?>
		</td></tr></table>
		<?if($Charge){?>
		<table class='table'>
		<thead><tr><th colspan="3">Fret à charger <img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'> <a href='#' class='popup'><img src='images/help.png'><span>Attention : Vous ne pouvez pas décharger dans le dépôt d où vient votre dernière cargaison!</span></a></th></tr></thead>
		<tr><td align='left'>
		<?	$Charge*=$Vehicule_Nbr;
			if($Categorie !=14 and $Categorie !=18)
			{
				function FretStockList($Value,$Charge,$Stock,$Masse)
				{
					if($Stock >0)
					{
						?>
						  <script type="text/javascript">
							function updateTextInput(id,val) {
							  document.getElementById('FretNbr'+id).value=val; 
							}
						  </script>
						<?
						$Max_Charge=floor($Charge/$Masse);
						if($Max_Charge >$Stock)$Max_Charge=$Stock;
						$Masse=floor(1/$Masse);
						if($Masse <1)$Masse=1;
						if($Value <200)
							$Value_txt=$Value."mm";
						elseif($Value ==300)
							$Value_txt="Charges";
						elseif($Value ==400)
							$Value_txt="Mines";
						elseif($Value ==800)
							$Value_txt="Torpilles";
						elseif($Value >9000)
							$Value_txt="Bombes de ".($Value-9000)."kg";
						echo "<input type='Radio' name='Action' value='".$Value."'><input type='range' name='Fret".$Value."' value='".$Max_Charge."' max='".$Max_Charge."' min='0' step='".$Masse."' onchange=\"updateTextInput(".$Value.",this.value);\">
						<input type='text' id='FretNbr".$Value."' value='".$Max_Charge."' size='6' readonly> ".$Value_txt."<br>";
					}
				}
				FretStockList(8,$Charge,$Stock_Munitions_8,0.01);
				FretStockList(13,$Charge,$Stock_Munitions_13,0.05);
				FretStockList(20,$Charge,$Stock_Munitions_20,0.1);
				FretStockList(30,$Charge,$Stock_Munitions_30,0.2);
				FretStockList(40,$Charge,$Stock_Munitions_40,0.5);
				FretStockList(50,$Charge,$Stock_Munitions_50,1);
				FretStockList(60,$Charge,$Stock_Munitions_60,1.5);
				FretStockList(75,$Charge,$Stock_Munitions_75,5);
				FretStockList(90,$Charge,$Stock_Munitions_90,10);
				FretStockList(105,$Charge,$Stock_Munitions_105,15);
				FretStockList(125,$Charge,$Stock_Munitions_125,20);
				FretStockList(150,$Charge,$Stock_Munitions_150,50);
				FretStockList(80,$Charge,$Stock_Bombes_80,80);
				FretStockList(9050,$Charge,$Stock_Bombes_50,50);
				FretStockList(9125,$Charge,$Stock_Bombes_125,125);
				FretStockList(9250,$Charge,$Stock_Bombes_250,250);
				FretStockList(9500,$Charge,$Stock_Bombes_500,500);
				FretStockList(10000,$Charge,$Stock_Bombes_1000,1000);
				FretStockList(11000,$Charge,$Stock_Bombes_2000,2000);
				FretStockList(300,$Charge,$Stock_Bombes_300,300);
				FretStockList(400,$Charge,$Stock_Bombes_400,400);
				FretStockList(800,$Charge,$Stock_Bombes_800,800);
				if($Base_Long <-52 and ($country ==2 or $country ==7))
				{
					echo "<input type='Radio' name='Action' value='888'> Lend-Lease<br>";
					if($country==7)
						$UK_Lend_txt=" Bristol, Glasgow et Liverpool pour l'Empire Britannique,";
					$Help_txt.="<p class='lead'>Le Lend-Lease permet de fournir du matériel aux nations alliées via les ports d'Arkhangelsk et Mourmansk pour l'URSS,".$UK_Lend_txt." Casablanca pour la France.
					<br>Le matériel Lend-Lease est indiqué dans l'encyclopédie par le symbole <img src='/images/lendlease.png' title='Lend-Lease'></p>";
				}
			}
			elseif($Categorie ==14)
			{
				function CarbuStockList($Value,$Charge,$Stock,$Masse)
				{
					if($Stock >0)
					{
						?>
						  <script type="text/javascript">
							function updateTextInput(id,val) {
							  document.getElementById('FretNbr'+id).value=val; 
							}
						  </script>
						<?
						if($Charge >$Stock)$Charge=$Stock;
						if($Value ==1087)
							$Value_txt ="87 Octane";
						elseif($Value ==1100)
							$Value_txt ="100 Octane";
						elseif($Value ==1001)
							$Value_txt ="Diesel";
						echo "<input type='Radio' name='Action' value='".$Value."'><input type='range' name='Fret".$Value."' value='".$Charge."' max='".$Charge."' min='0' step='1' onchange=\"updateTextInput(".$Value.",this.value);\">
						<input type='text' id='FretNbr".$Value."' value='".$Charge."' size='6' readonly> ".$Value_txt."<br>";
					}
				}
				CarbuStockList(1087,$Charge,$Stock_Essence_87,1);
				CarbuStockList(1100,$Charge,$Stock_Essence_100,1);
				CarbuStockList(1001,$Charge,$Stock_Essence_1,1);
			}
		}?>
		</td></tr></table>
	</table>		
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?
	echo $Help_txt;
}
?>