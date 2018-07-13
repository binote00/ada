<?php
require_once('./jfv_inc_sessions.php');
if($_SESSION['AccountID'])
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$OfficierID=$_SESSION['Officier'];
	$OfficierEMID=$_SESSION['Officier_em'];
	$country=$_SESSION['country']; 
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if(($OfficierID >0 or $OfficierEMID) and $Premium >0)
	{	
		$img="move".$country;
		$Lieu=Insec($_POST['Lieu']);
		$Vehicule=Insec($_POST['Veh']);
		$Pays=Insec($_POST['Pays']);
		$Placement=Insec($_POST['Placement']);
		if($Lieu and !$Placement)
		{
			$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
			if($Admin or $OfficierEMID)
			{
				if($Admin)
				{
					$Veh_txt ="Nation <select name='Pays' class='form-control' style='width: 200px'>
					<option value='1'>Allemagne</option>
					<option value='2'>Angleterre</option>
					<option value='20'>Finlande</option>
					<option value='4'>France</option>
					<option value='6'>Italie</option>
					<option value='9'>Japon</option>
					<option value='8'>URSS</option>
					<option value='7'>USA</option></select>";
					$queryv="SELECT ID,Nom FROM Cible WHERE Unit_ok=1 ORDER BY Nom ASC";
				}
				else
					$queryv="SELECT ID,Nom FROM Cible WHERE Unit_ok=1 AND Pays='$country' ORDER BY Nom ASC";
				$con=dbconnecti();
				$resultv=mysqli_query($con, $queryv);
				mysqli_close($con);
				if($resultv)
				{
					while($data=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
					{
						$Veh.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
					}
					mysqli_free_result($resultv);
				}
				if($Admin)$Veh_txt.="Véhicule <select name='Veh' class='form-control' style='width: 200px'>".$Veh."</select>";
			}
			$con=dbconnecti();
			$resultv=mysqli_query($con,"SELECT NoeudR,NoeudF,Flag_Gare FROM Lieu WHERE ID='$Lieu'");
			mysqli_close($con);
			if($resultv)
			{
				while($data=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
				{
					$NoeudR_Ori=$data['NoeudR'];
					$NoeudF_Ori=$data['NoeudF'];
				}
				mysqli_free_result($resultv);
			}
			$Places="<option value='10'>Caserne</option>";
			if($NoeudR_Ori)$Places.="<option value='2'>Route</option>";
			if($NoeudF_Ori)$Places.="<option value='3'>Gare</option>";
			$titre="<h1>Simulation de déplacement</h1>";
			$mes="<form action='index.php?view=pr_ground_move' method='post'>
			<input type='hidden' name='Lieu' value='".$Lieu."'>
			<table class='table'><thead><tr><th>Zone de départ</th></tr></thead>
				<tr><td><select name='Placement' class='form-control' style='width: 200px'>".$Places."</select></td></tr>
			</table>".$Veh_txt."<input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		elseif($Lieu and $Placement)
		{
			$Fiabilite=0;
			$Rasputitsa=false;
			$Merzlota=false;
			$Mousson=false;
			if($Placement ==10)$Placement=0;
			if($Pays)$country=$Pays;
			$con=dbconnecti();
			$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
			$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
			$result3=mysqli_query($con,"SELECT Pays,Zone,NoeudR,NoeudF,Longitude,Latitude,Impass,Flag,Flag_Gare FROM Lieu WHERE ID='$Lieu'");
			mysqli_close($con);
			if($result3)
			{
				$Mois=substr($Date_Campagne,5,2);
				while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					$Pays_Ori=$data3['Pays'];
					$NoeudR=$data3['NoeudR'];
					$NoeudF=$data3['NoeudF'];
					$Latitude=$data3['Latitude'];
					$Longitude=$data3['Longitude'];
					$Impass_ori=$data3['Impass'];
					$Zone=$data3['Zone'];
					$Flag=$data3['Flag'];
					$Flag_Gare=$data3['Flag_Gare'];
					$Front=GetFrontByCoord($Lieu,$Latitude,$Longitude);
					if(($data3['Pays'] ==8 or $data3['Pays'] ==20) and ($Mois ==11 or $Mois ==3)) //Rasputitsa
					{
						$Rasputitsa=true;
						$img="rasputitsa";
					}
					elseif($Front ==3) 
					{
						if(($Longitude <=90 and ($Mois ==7 or $Mois ==8)) or ($Longitude >90 and ($Mois ==8 or $Mois ==9)))
						{
							$Mousson=true;
							$img="mousson";
						}
					}
					if(($data3['Pays'] ==8 or $data3['Pays'] ==20) and ($Mois ==12 or $Mois ==1 or $Mois ==2)) //Merzlota
						$Merzlota=true;
				}
				mysqli_free_result($result3);
			}
			if($Vehicule)
			{
				$con=dbconnecti();
				//$Enis2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
				$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction<>'$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement='$Placement' AND i.Vehicule_Nbr >0"),0);
				$result2=mysqli_query($con,"SELECT Fuel,mobile,Type,Fiabilite FROM Cible WHERE ID='$Vehicule'");
				mysqli_close($con);
				if($result2)
				{
					while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
					{
						if($NoeudR >0 and $Placement ==2 and !$Rasputitsa and !$Mousson and !$Enis)
						{
							if(!$Rasputitsa)
							{
								$Zone_calc=0;
								$txt_help.="<br>L'unité se trouvant sur un noeud routier et aucun ennemi n'étant présent sur ce noeud routier, l'éventuel malus de terrain est annulé.";
							}
							else
								$txt_help.="<br>Malgré le fait que l'unité se trouve sur un noeud routier et qu'aucun ennemi ne soit présent sur ce noeud routier, l'éventuel malus de terrain n'est pas annulé à cause de la Rasputitsa.";
						}
						$data2['Fuel']=Get_LandSpeed($data2['Fuel'],$data2['mobile'],$Zone_calc,0,$data2['Type']);
						if($Action ==115)
						{
							if($data2['mobile'] ==3 or $data2['Type'] ==6)
								$data2['Fuel']*=2;
						}
						if($Skill4 ==100 and $Zone ==8) //guerre du désert
							$data2['Fuel']*=2;
						$Autonomie_Min=$data2['Fuel'];
						$Mobile_Min=$data2['mobile'];
						$Fiabilite=$data2['Fiabilite'];
					}
					mysqli_free_result($result2);
				}
			}
			else
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Vehicule_ID FROM Regiment WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Vehicule=$data['Vehicule_ID'];	
						$con=dbconnecti();
						$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
						$Enis2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction<>'$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement='$Placement' AND i.Vehicule_Nbr >0"),0);
						$result2=mysqli_query($con,"SELECT Fuel,mobile,Type,Fiabilite FROM Cible WHERE ID='$Vehicule'");
						mysqli_close($con);
						//$Enis+=$Enis2;
						if($result2)
						{
							while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								if($NoeudR >0 and $Placement ==2 and !$Rasputitsa and !$Mousson and !$Enis)
								{
									if(!$Rasputitsa)
									{
										$Zone_calc=0;
										$txt_help.="<br>L'unité se trouvant sur un noeud routier et aucun ennemi n'étant présent sur ce noeud routier, l'éventuel malus de terrain est annulé. (1)";
										$txt_anex=true;
									}
									else
										$txt_help.="<br>Malgré le fait que l'unité se trouve sur un noeud routier et qu'aucun ennemi ne soit présent sur ce noeud routier, l'éventuel malus de terrain n'est pas annulé à cause de la Rasputitsa.";
								}
								$data2['Fuel']=Get_LandSpeed($data2['Fuel'],$data2['mobile'],$Zone_calc,0,$data2['Type']);
								if($Action ==115)
								{
									if($data2['mobile'] ==3 or $data2['Type'] ==6)
										$data2['Fuel']*=2;
								}
								if($Skill4 ==100 and $Zone ==8) //guerre du désert
									$data2['Fuel']*=2;
								$Autonomie[]=$data2['Fuel'];
								$Mobile_t[]=$data2['mobile'];
								$Fiabilite += $data2['Fiabilite'];
							}
							mysqli_free_result($result2);
						}
					}
				}
				if($Autonomie)$Autonomie_Min=min($Autonomie);
				if($Mobile_t)$Mobile_Min=min($Mobile_t);
				unset($Autonomie);
				unset($Mobile_t);
			}
			$Lat_min=$Latitude-2;
			$Lat_max=$Latitude+2;
			$Long_min=$Longitude-3;
			$Long_max=$Longitude+3;
			$Autonomie_Max=50;
			if($G_Treve)$Treve_txt="AND Flag='$country'";
			if($Front ==2)
			{
				if($Cible ==903 or $Cible ==910 or $Cible ==1090 or $Cible ==1288 or $Cible ==1653) //Crête
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (903,910,1090,1288,1653) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Cible ==435 or $Cible ==450 or $Cible ==465 or $Cible ==1644 or $Cible ==1647 or $Cible ==2127 or $Lieu ==2953 or $Lieu ==2954 or $Lieu ==2955 or $Lieu ==2956) //Sardaigne
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (435,450,465,1644,1647,2127,2953,2954,2955,2956) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Cible ==2306 or $Cible ==2307 or $Cible ==2308 or $Cible ==2309 or $Cible ==2310 or $Cible ==2957 or $Cible ==2958) //Corse
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (2306,2307,2308,2309,2310,2957,2958) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Latitude >36.7 and $Latitude <38.2 and $Longitude >12.5 and $Longitude <=15.55) //Sicile
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Latitude BETWEEN 36.7 AND 38.2) AND (Longitude BETWEEN 12.5 AND 15.56) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Latitude <36.7 or ($Longitude <12 and $Latitude <37.3 and $Pays_Ori !=6)) //AFN
				{
					$Autonomie_Max=100;
					if($Latitude <33 and $Longitude <34 and $Longitude >11.22)
					{
						if($Longitude <25.16 and $Latitude >31.12)
							$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND 25.16) AND (Latitude BETWEEN '$Lat_min' AND 33.15) AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
						elseif($Longitude >25.16 and $Latitude >31.12)
							$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 25.16 AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND 33) AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
						else
							$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND 33.15) AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653)";
					}
					else
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE Latitude <37.3 AND Longitude >-8 AND Longitude <50 AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
				}
				elseif($Latitude >36.6 and $Longitude >19) //Grèce
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE Latitude >36.6 AND Longitude >19 AND Longitude <50 AND Zone<>6 ".$Treve_txt." AND PAYS NOT IN (2,4,6) ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
				elseif($Latitude >38.2)
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Latitude BETWEEN 38.2 AND 45.5) AND (Longitude BETWEEN -2 AND 50) AND Zone<>6 ".$Treve_txt." AND PAYS NOT IN (10,24) AND ID NOT IN ('$Cible',343,435,445,450,465,529,678,903,910,1090,1288,1644,1647,1653,2127,2306,2307,2308,2309,2310) ORDER BY Nom ASC";
				else
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,435,445,450,465,529,678,903,910,1090,1288,1644,1647,1653,2127,2306,2307,2308,2309,2310) ORDER BY Nom ASC LIMIT 100";
			}
			elseif($Front ==1 or $Front ==4)
			{
				$Autonomie_Max=250;
				if($country ==20)
				{
					if($Lat_min <60)$Lat_min=60;
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
				}
				elseif($Latitude <46 and $Latitude>44.40 and $Longitude >33 and $Longitude <36.5) //Crimée
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 33 AND 36.5) AND (Latitude BETWEEN 44.4 AND 46.5) AND Zone<>6 ".$Treve_txt." AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Latitude <47 and $Latitude>41 and $Longitude >37 and $Longitude <48) //Caucase
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 37 AND 50) AND (Latitude BETWEEN 41 AND 48) AND Zone<>6 ".$Treve_txt." AND ID<>'$Cible' ORDER BY Nom ASC";
				else
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
			}
			elseif($Front ==5)
				$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
			elseif($Front ==3)
			{
				$Autonomie_Max=300;
				if($Cible ==1610 or $Cible ==1618 or $Cible ==1637 or $Cible ==1869 or $Cible ==1894) //Ceylan
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1610,1618,1637,1869,1894) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Longitude >90 and $Longitude <110 and $Latitude >1.20 and $Cible !=1870 and $Cible !=1903) //Continent
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 90 AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1754,1809,1870,1900) ORDER BY Nom ASC";
				elseif($Longitude <90 and $Latitude >9) //Inde
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND 90) AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1754,1809,1870,1900) ORDER BY Nom ASC";
				elseif($Cible ==1368 or $Cible ==1556 or $Cible ==1582 or $Cible ==1776 or $Cible ==1803 or $Cible ==1805 or $Cible ==1811 or $Cible ==1857 or $Cible ==2379 or $Cible ==2380 or $Cible ==2381) //Japon
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1368,1556,1582,1776,1803,1805,1811,1857,2379,2380,2381) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Cible ==1583 or $Cible ==1800 or $Cible ==1801 or $Cible ==1804) //Formose
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1583,1800,1801,1804) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Cible ==1569 or $Cible ==1570 or $Cible ==1571 or $Cible ==1764 or $Cible ==1881 or $Cible ==1888 or $Cible ==1889 or $Cible ==2353 or $Cible ==2354 or $Cible ==2355 or $Cible ==2356 or $Cible ==2357) //Philippines
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1569,1570,1571,1764,1881,1888,1889,2353,2354,2355,2356,2357) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Cible ==1370 or $Cible ==1574 or $Cible ==1575 or $Cible ==1576 or $Cible ==1613 or $Cible ==1892 or $Cible ==1895 or $Cible ==2358) //Java
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1370,1574,1575,1576,1613,1892,1895,2358) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Cible ==1365 or $Cible ==1809 or $Cible ==1873 or $Cible ==1887) //Sumatra
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1365,1809,1873,1887) AND ID<>'$Cible' ORDER BY Nom ASC";
				elseif($Cible ==1573 or $Cible ==1763 or $Cible ==1865 or $Cible ==1866 or $Cible ==1972 or $Cible ==2163 or $Cible ==2214) //Australie
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1573,1763,1865,1866,1972,2163,2214) AND ID<>'$Cible' ORDER BY Nom ASC";
				else
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID='$Cible'";
			}
			else
			{
				if($Pays_Ori ==1 or $Pays_Ori ==3 or $Pays_Ori ==4 or $Pays_Ori ==5 or $Pays_Ori ==6)
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt."
					AND Pays<>2 AND ID NOT IN ('$Cible',2306,2307,2308,2309,2310) ORDER BY Nom ASC";
				elseif($Pays_Ori ==2)
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Gare,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt."
					AND Pays=2 AND ID NOT IN ('$Cible',349,593,735,873,918,915,941,942,943,944,951,1373,1374) ORDER BY Nom ASC";
			}			
			$txt_help.="<br>Votre unité possède une autonomie de base de ".$Autonomie_Min."km.<br>L'autonomie maximale sur ce front est de ".$Autonomie_Max."km.";
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result))
				{
					$coord=0;
					$CT_city=9;
					$Train_move=false;
					$Distance=GetDistance(0,0,$Longitude,$Latitude,$data[2],$data[3]);
					if($data['NoeudR'])
						$Faction_Dest_Route=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Route']."'"),0);
					if($data['NoeudF'])
						$Faction_Dest_Gare=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Gare']."'"),0);
					if($Placement ==3 and $NoeudF >10 and $data['NoeudF'] >10 and $Flag ==$Faction and $Flag_Gare ==$Faction and $data['Flag'] ==$Faction and $data['Flag_Gare'] ==$Faction and !$Enis)
					{
						if($Front ==1 or $Front ==4)
							$Autonomie_Actu=500;
						else
							$Autonomie_Actu=200;
						$train_txt="- En Train";
						$Train_move=true;
					}
					elseif($data['NoeudR'] and $NoeudR >0 and $Placement ==2 and !$Rasputitsa and !$Enis and $Faction_Dest_Route ==$Faction)
					{
						$Bonus_Noeud=true;
						$Autonomie_Actu=$Autonomie_Min*2;
					}
					else
					{
						$Bonus_Noeud=false;
						$Autonomie_Actu=$Autonomie_Min;
					}
					if($Distance[0] <=$Autonomie_Actu)
					{
						$txt_help .="<h4><small>".$data[1]."</small></h4>";
						if($Train_move)
						{
							$txt_help .="<br>L'unité étant sur une gare contrôlée par sa nation et le lieu de destination (".$data[1].") comportant également une gare contrôlée par sa nation, l'autonomie de l'unité est de ".$Autonomie_Actu."km";
						}
						elseif($Bonus_Noeud)
						{
							$txt_help.="<br>L'unité étant sur un noeud routier alors qu'aucune unité ennemie n'est présente sur ce noeud routier et le lieu de destination (".$data[1].") comportant également un noeud routier, l'autonomie de l'unité est doublée pour atteindre la valeur de ".$Autonomie_Actu."km (1)";
							$txt_anex=true;
						}
						else
							$txt_help.="<br>L'unité ne bénéficie pas du bonus de noeud routier vers ce lieu (".$data[1].")";
						$Impass=$data['Impass'];
						if($data['NoeudR'] and !$Rasputitsa)
							$icone="<img src='/images/route.gif' title='Noeud Routier'>";
						else
							$icone="<img src='/images/zone".$data['Zone'].".jpg'>";
						$sensh='';
						$sensv='';
						if($Longitude > $data[2])
						{
							$sens='Ouest';
							$coord+=2;
							if($Impass ==2 or $Impass ==3 or $Impass ==4 or $Impass_ori ==6 or $Impass_ori ==7 or $Impass_ori ==8)
								$CT_city=999;
						}
						elseif($Longitude < $data[2])
						{
							$sensh='Est';
							$coord+=1;
							if($Impass ==6 or $Impass ==7 or $Impass ==8 or $Impass_ori ==2 or $Impass_ori ==3 or $Impass_ori ==4)
								$CT_city=999;
						}
						if($sensh)
						{
							if($Latitude > $data[3] +0.25)
							{
								$sensv='Sud';	
								$coord+=20;
								if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
									$CT_city=999;
							}
							elseif($Latitude < $data[3] -0.25)
							{
								$sensv='Nord';
								$coord+=10;
								if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
									$CT_city=999;
							}
						}
						else
						{
							if($Latitude > $data[3])
							{
								$sensv='Sud';
								$coord+=20;
								if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
									$CT_city=999;
							}
							elseif($Latitude < $data[3])
							{
								$sensv='Nord';
								$coord+=10;
								if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
									$CT_city=999;
							}
						}
						$sens=$sensv.' '.$sensh;
						$CT_city=ceil($CT_city+floor($Distance[0]/25));
						if($Rasputitsa or ($Merzlota and $Mobile_Min !=3))
						{
							$CT_city*=2;
							if($Rasputitsa and $Zone !=2 and $Zone !=3 and $Zone !=4 and $Zone !=5 and $Zone !=7 and $Mobile_Min !=3)
							{
								$Distance[0]*=1.25;
								$txt_help.="<br>La distance à parcourir vers ".$data[1]." est augmentée de 25% (".$Distance[0]."km) à cause de la Rasputitsa";
							}
						}
						if($Faction != GetData("Pays","ID",$data['Flag'],"Faction"))
						{
							$CT_city*=2;
							if($Enis)
							{
								$Distance[0]*=2;
								$txt_help.="<br>La distance à parcourir vers ".$data[1]." est augmentée de 100% (".$Distance[0]."km) à cause de la présence d'unités ennemies sur un lieu contrôlé par l'ennemi. (1)";
							}
							else
							{
								$Distance[0]*=1.5;
								$txt_help.="<br>La distance à parcourir vers ".$data[1]." est augmentée de 50% (".$Distance[0]."km) car ".$data[1]." est contrôlé par l'ennemi. (1)";
							}
						}
						if($Skill4 ==100 and $data['Zone'] ==8)$CT_city-=1;
						if($CT_city !=999 and $CT_city >35)$CT_city=35;
						$CT_city-=$Fiabilite;
						if(!$Train_move and $Autonomie_Actu >$Autonomie_Max)$Autonomie_Actu=$Autonomie_Max;
						if($Distance[0] <=$Autonomie_Actu)
						{
							$txt_help.="<br>La distance à parcourir vers ".$data[1]." est de <span class='text-primary'>".$Distance[0]."km</span> et l'autonomie de l'unité est de ".$Autonomie_Actu.", permettant le déplacement vers ce lieu.";
							if($Credits >= $CT_city)
								$choix="<tr><td><Input type='Radio' name='Action' value='".$data[0]."_".$CT_city."'><img src='/images/CT".$CT_city.".png' title='Montant en Crédits Temps que nécessite cette action'>- ".$data[1]."</td><td><img src='".$data['Flag']."20.gif'></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
							else
								$choix="<tr><td><Input type='Radio' name='Action' value='".$data[0]."_".$CT_city."' disabled><img src='/images/CT".$CT_city.".png' title='Montant en Crédits Temps que nécessite cette action'>- ".$data[1]."</td><td><img src='".$data['Flag']."20.gif'></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
							if($coord ==1) //Est
								$Est_txt.=$choix;
							elseif($coord ==2) //Ouest
								$Ouest_txt.=$choix;
							elseif($coord ==10) //Nord
								$Nord_txt.=$choix;
							elseif($coord ==20) //Sud
								$Sud_txt.=$choix;
							elseif($coord ==11) //NE
								$NE_txt.=$choix;
							elseif($coord ==21) //SE
								$SE_txt.=$choix;
							elseif($coord ==12) //NO
								$NO_txt.=$choix;
							elseif($coord ==22) //SO
								$SO_txt.=$choix;
						}
						else
							$txt_help.="<br>La distance à parcourir vers ".$data[1]." est de <span class='text-danger'>".$Distance[0]."km</span> et l'autonomie de l'unité est de ".$Autonomie_Actu.", ne permettant pas le déplacement vers ce lieu.";
					}
				}
				mysqli_free_result($result);
			}
			if($choix)
			{
				if($Autonomie_Min >$Autonomie_Max)$Autonomie_Min=$Autonomie_Max;
				$mes="<h1>Calculateur de distance</h1><h2>Destinations</h2>
					<p class='lead'>Autonomie max : ".$Autonomie_Min."km ".$train_txt."<a href='#' class='popup'><img src='images/help.png'><span>Doublé si votre bataillon se trouve sur un noeud routier</span></a></p>
					<div class='row'><div class='col-md-8'><table class='table'>
					<tr>
					<td width='30%'><table><tr><th colspan='3'>Nord Ouest</th></tr>".$NO_txt."</table></td>
					<td width='30%'><table><tr><th colspan='3'>Nord</th></tr>".$Nord_txt."</table></td>
					<td width='30%'><table><tr><th colspan='3'>Nord Est</th></tr>".$NE_txt."</table></td>
					</tr>
					<tr>
					<td width='30%'><table><tr><th colspan='3'>Ouest</th></tr>".$Ouest_txt."</table></td>
					<td width='30%'><img src='images/travel_icon.png'></td>
					<td width='30%'><table><tr><th colspan='3'>Est</th></tr>".$Est_txt."</table></td>
					</tr>
					<tr>
					<td width='30%'><table><tr><th colspan='3'>Sud Ouest</th></tr>".$SO_txt."</table></td>
					<td width='30%'><table><tr><th colspan='3'>Sud</th></tr>".$Sud_txt."</table></td>
					<td width='30%'><table><tr><th colspan='3'>Sud Est</th></tr>".$SE_txt."</table></td>
					</tr>
					</table>
					</div></div><a href='index.php?view=pr_ground_move0' class='btn btn-default' title='Retour'>Retour</a>".$txt_help;
				if($txt_anex)$mes.="<p class='lead'>(1) Attention que cette information ne prend pas en compte la présence d'éventuelles unités ennemies!</p>";
			}
			elseif($Mobile_Min ==5)
				$mes="<br>Cet outil ne permet pas de simuler le déplacement des unités navales que vous commandez.";
			else
				$mes="<br>L'autonomie de vos troupes (".$Autonomie_Min."km) est insuffisante pour atteindre la destination la plus proche !";
		}
		$img=Afficher_Image('images/'.$img.'.jpg',"images/image.png","");
		include_once('./default.php');
	}
	else
		echo "Tsss";
}
?>