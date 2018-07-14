<?php
require_once('./jfv_inc_sessions.php');
/*$OfficierID=$_SESSION['Officier'];
$AccountID=$_SESSION['AccountID'];*/
if($OfficierID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$Action=Insec($_POST['Action']);
	$Reg=Insec($_POST['Reg']);
	$Cible=Insec($_POST['Loc']);
	$CT_move=Insec($_POST['CT_M']);
	$CT_front=Insec($_POST['CT_F']);
	$Credits_emb=Insec($_POST['CT_emb']);
	$Credits_def=Insec($_POST['CT_def']);
	$Credits_ret=Insec($_POST['CT_ret']);
	$Credits_app=Insec($_POST['CT_app']);
	$Credits_smoke=Insec($_POST['CT_smoke']);
	$Credits_plonge=Insec($_POST['CT_plonge']);
	$Credits_flee=Insec($_POST['CT_flee']);
	$con=dbconnecti();
	$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
	$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	$result2=mysqli_query($con,"SELECT Front,Division,Trait,Skill4,Transit,Credits,Admin FROM Officier WHERE ID='$OfficierID'");
	mysqli_close($con);
	if($result2)
	{
		while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Front=$data2['Front'];
			$Division=$data2['Division'];
			$Trait_o=$data2['Trait'];
			$Skill4=$data2['Skill4'];
			$Transit=$data2['Transit'];
			$Credits=$data2['Credits'];
			$Admin=$data2['Admin'];
		}
		mysqli_free_result($result2);
	}
	if(!$Cible)$Cible=GetData("Regiment","Officier_ID",$OfficierID,"Lieu_ID");
	if($Cible)
	{
		$con=dbconnecti();
		$result3=mysqli_query($con,"SELECT Nom,Pays,Latitude,Longitude,Zone,Impass,Fleuve,Fleuve_1,Fleuve_2,Fleuve_3,ValeurStrat,NoeudR,NoeudF,BaseAerienne,Flag,Flag_Air,Meteo FROM Lieu WHERE ID='$Cible'");
		mysqli_close($con);
		if($result3)
		{
			while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$titre=$data3['Nom'];
				$Pays_Ori=$data3['Pays'];
				$Latitude_front=$data3['Latitude'];
				$Longitude_front=$data3['Longitude'];
				$Zone=$data3['Zone'];
				$NoeudR=$data3['NoeudR'];
				$NoeudF=$data3['NoeudF'];
				$Impass_ori=$data3['Impass'];
				$Fleuve=$data3['Fleuve'];
				$Fleuve_1=$data3['Fleuve_1'];
				$Fleuve_2=$data3['Fleuve_2'];
				$Fleuve_3=$data3['Fleuve_3'];
				$ValStrat=$data3['ValeurStrat'];
				$BaseAerienne=$data3['BaseAerienne'];
				$Flag=$data3['Flag'];
				$Flag_Air=$data3['Flag_Air'];
				$Meteo=$data3['Meteo'];
			}
			mysqli_free_result($result3);
		}
	}
	if($Action)
	{	
		if($Action ==1)
		{
			if($Credits >=$Credits_ret and $Credits_ret >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=2,Move=0,Camouflage=4 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_ret,"ID",$OfficierID);
				$mes='<br>Vos troupes se retranchent sur leurs positions';
				$img="digin";
			}
		}
		elseif($Action ==2)
		{
			if($Credits >=$Credits_def and $Credits_def >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=1,Move=2,Camouflage=2 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_def,"ID",$OfficierID);
				$mes='<br>Vos troupes se placent en position d�fensive';
				$img="defense".$country;
			}
		}
		elseif($Action ==3)
		{
			if($Credits >=$Credits_emb and $Credits_emb >0)
			{			
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=3,Visible=0,Move=0,Camouflage=4 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_emb,"ID",$OfficierID);
				$mes='<br>Vos troupes se placent en embuscade';
				$img="ambush".$country;
			}
		}
		elseif($Action ==4 or $Action ==5)
		{
			if(($Credits >=6 and $Action ==4) or ($Credits >=$Credits_flee and $Action ==5))
			{
				/*$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
				$Veh_Nbr_sum=0;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID,Lieu_ID,Vehicule_ID,Vehicule_Nbr,Stock_Essence_87,Stock_Essence_1,Moral FROM Regiment WHERE Officier_ID='$OfficierID' AND Vehicule_Nbr >0");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Vehicule=$data['Vehicule_ID'];
						$Veh_Nbr=$data['Vehicule_Nbr'];
						$Stock87=$data['Stock_Essence_87'];
						$Stock1=$data['Stock_Essence_1'];
						$Moral=$data['Moral'];						
						$con=dbconnecti();
						$result2=mysqli_query($con,"SELECT Puissance,Conso,Vitesse,Carbu_ID FROM Cible WHERE ID='$Vehicule'");
						mysqli_close($con);
						if($result)
						{
							while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								$Veh_Carbu=$data2['Carbu_ID'];
								$Puissance=$data2['Puissance'];
								$Conso=$data2['Conso'];
								$Vitesse=$data2['Vitesse'];
							}
							mysqli_free_result($result2);
						}
						$Conso_base=Get_LandConso(0,$Conso);
						if($Veh_Carbu ==87)
						{
							$Jauge=$Stock87;
							$Stock="Stock_Essence_87";
							$Conso=$Veh_Nbr*$Conso_base;
						}
						elseif($Veh_Carbu ==1)
						{
							$Jauge=$Stock1;
							$Stock="Stock_Essence_1";
							$Conso=$Veh_Nbr*$Conso_base;
						}
						else
						{
							$Jauge=$Moral;
							$Stock="Moral";
							$Conso=1;
							$Conso_base=1;
						}
						if($Jauge >=$Conso)
						{
							UpdateData("Regiment",$Stock,-$Conso,"ID",$data['ID']);
							$Veh_Nbr_sum+=$Veh_Nbr;
						}
						else
						{
							//$mes.="<br>[DEBUG] Consommation de la ".$data['ID']."e Cie : ".$Conso." sur son stock de ".$Jauge;
							$Diff=($Conso-$Jauge)/$Conso_base;
							SetData("Regiment",$Stock,0,"ID",$data['ID']);
							$Charisme=0;
							if($Trait_o ==6)$Charisme=mt_rand(0,1);
							if($Diff >0 and !$Charisme)
							{
								UpdateData("Regiment","Vehicule_Nbr",-$Diff,"ID",$data['ID']);
								AddEventGround(410,$Vehicule,$OfficierID,$data['ID'],$data['Lieu_ID'],$Diff);
								$Veh_Nbr_sum+=$Veh_Nbr-$Diff;
								$mes.="<br>Une partie des troupes de la ".$data['ID']."e Cie, dans l'impossibilit� de battre en retraite, sont captur�es par l'ennemi!";
							}
						}
					}
					mysqli_free_result($result);	
				}*/
				if($Division >0)
					$Retraite=GetData("Division","ID",$Division,"Base");
				else
				{
					$Latitude_front=GetData("Lieu","ID",$Lieu,"Latitude");
					$Retraite=Get_Retraite($Front,$country,$Latitude_front);
				}
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=6,Move=1,Camouflage=1,Visible=0,Moral=0,Fret=0,Fret_Qty=0,Lieu_ID='$Retraite',Vehicule_Nbr=Vehicule_Nbr DIV 1.33,
				Stock_Munitions_8=Stock_Munitions_8 DIV 2,Stock_Munitions_13=Stock_Munitions_13 DIV 2,Stock_Munitions_20=Stock_Munitions_20 DIV 2,Stock_Munitions_30=Stock_Munitions_30 DIV 2,Stock_Munitions_40=Stock_Munitions_40 DIV 2,Stock_Munitions_50=Stock_Munitions_50 DIV 2,
				Stock_Munitions_60=Stock_Munitions_60 DIV 2,Stock_Munitions_75=Stock_Munitions_75 DIV 2,Stock_Munitions_90=Stock_Munitions_90 DIV 2,Stock_Munitions_105=Stock_Munitions_105 DIV 2,Stock_Munitions_150=Stock_Munitions_150 DIV 2,
				Stock_Munitions_200=Stock_Munitions_200 DIV 2,Stock_Munitions_300=Stock_Munitions_300 DIV 2,Stock_Munitions_360=Stock_Munitions_360 DIV 2,Stock_Munitions_530=Stock_Munitions_530 DIV 2,Stock_Munitions_610=Stock_Munitions_610 DIV 2,
				Stock_Charges=Stock_Charges DIV 2,Stock_Mines=Stock_Mines DIV 2,Stock_Essence_87=Stock_Essence_87 DIV 2,Stock_Essence_1=Stock_Essence_1 DIV 2
				WHERE Officier_ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Officier SET Train_Lieu=0,Reputation=Reputation-10 WHERE ID='$OfficierID'");
				mysqli_close($con);
				if($Action ==5)
					UpdateData("Officier","Credits",-$Credits_flee,"ID",$OfficierID);
				else
					UpdateData("Officier","Credits",-6,"ID",$OfficierID);
				$mes='<br>Vos troupes battent en retraite!';
				$img="retreat";
			}
		}
		elseif($Action ==6) //train
		{
			if($Credits >=$CT_front and $CT_front >0)
			{
				$img="move_front".$country;
				$CT_front_ori=$CT_front;
				/*$con=dbconnecti();
				$result3=mysqli_query($con,"SELECT Pays,Latitude,Longitude,Impass,NoeudF FROM Lieu WHERE ID='$Cible'");
				mysqli_close($con);
				if($result3)
				{
					while($data2=mysqli_fetch_array($result3,MYSQLI_ASSOC))
					{
						$Pays_Ori=$data2['Pays'];
						$Latitude_front=$data2['Latitude'];
						$Longitude_front=$data2['Longitude'];
						$NoeudF=$data2['NoeudF'];
						$Impass_ori=$data2['Impass'];
					}
					mysqli_free_result($result3);
				}*/
				if($NoeudF >10)
				{
					$Range_train=200;
					$Lands=GetAllies($Date_Campagne);
					if(IsAxe($country))
						$Allies=$Lands[1];
					else
						$Allies=$Lands[0];
					if($Front ==2)
					{		
						if($Cible ==2306 or $Cible ==2307) //Corse
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 AND ID IN (2306,2307) AND ID<>'$Cible' ORDER BY Nom ASC";
						elseif($Latitude_front <36.7 or ($Longitude_front <12 and $Latitude_front <37.3 and $Pays !=6)) //AFN
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE Latitude <37.3 AND Longitude >-8 AND Longitude <50 AND Zone<>6 AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
						elseif($Latitude_front >36.6 and $Longitude_front >19) //Gr�ce
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE Latitude >36.6 AND Longitude >19 AND Longitude <50 AND Zone<>6 AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 AND PAYS NOT IN (2,4,6) ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
						elseif($Latitude_front >38.2)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE (Latitude BETWEEN 38.2 AND 45.5) AND (Longitude BETWEEN -2 AND 50) AND Zone<>6 AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 AND PAYS NOT IN (10,24) AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
					}
					elseif($Front ==1 or $Front ==4)
					{
						$Range_train=500;
						if($country ==8)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE (Latitude BETWEEN 41 AND 70) AND (Longitude BETWEEN 13 AND 52) AND Zone<>6 AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
						else
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE (Latitude BETWEEN 43 AND 60) AND (Longitude BETWEEN 13 AND 46) AND Zone<>6 AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
					}
					elseif($Front ==5)
						$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE (Latitude BETWEEN 60 AND 70) AND (Longitude BETWEEN 0 AND 60) AND Zone<>6 AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
					elseif($Front ==3)
					{
						$Range_train=500;
						if($Cible ==1610 or $Cible ==1618 or $Cible ==1637 or $Cible ==1869 or $Cible ==1894) //Ceylan
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE ID IN (1610,1618,1637,1869,1894) AND ID<>'$Cible' AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						elseif($Longitude_front >90 and $Longitude_front <110 and $Latitude_front >1.20 and $Cible !=1870 and $Cible !=1903) //Continent est Brahmaputra
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE (Longitude BETWEEN 90 AND 110) AND (Latitude BETWEEN 1.2 AND 40) AND ID NOT IN ('$Cible',1754,1809,1870,1900) AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						elseif($Longitude_front <90 and $Latitude_front >9) //Inde
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE (Longitude BETWEEN 67 AND 90) AND (Latitude BETWEEN 20 AND 40) AND ID NOT IN ('$Cible',1754,1809,1870,1900) AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						elseif($Cible ==1368 or $Cible ==1556 or $Cible ==1582 or $Cible ==1776 or $Cible ==1803 or $Cible ==1805 or $Cible ==1811 or $Cible ==1857 or $Cible ==2379 or $Cible ==2380 or $Cible ==2381) //Japon
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE ID IN (1368,1556,1582,1776,1803,1805,1811,1857,2379,2380,2381) AND ID<>'$Cible' AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						elseif($Cible ==1583 or $Cible ==1800 or $Cible ==1801 or $Cible ==1804) //Formose
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE ID IN (1583,1800,1801,1804) AND ID<>'$Cible' AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						elseif($Cible ==1569 or $Cible ==1570 or $Cible ==1571 or $Cible ==1764 or $Cible ==1881 or $Cible ==1888 or $Cible ==1889 or $Cible ==2353 or $Cible ==2354 or $Cible ==2355 or $Cible ==2356 or $Cible ==2357) //Philippines
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE ID IN (1569,1570,1571,1764,1881,1888,1889,2353,2354,2355,2356,2357) AND ID<>'$Cible' AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						elseif($Cible ==1370 or $Cible ==1574 or $Cible ==1575 or $Cible ==1576 or $Cible ==1613 or $Cible ==1892 or $Cible ==1895 or $Cible ==2358) //Java
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE ID IN (1370,1574,1575,1576,1613,1892,1895,2358) AND ID<>'$Cible' AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						elseif($Cible ==1365 or $Cible ==1809 or $Cible ==1873 or $Cible ==1887) //Sumatra
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE ID IN (1365,1809,1873,1887) AND ID<>'$Cible' AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						elseif($Cible ==1573 or $Cible ==1763 or $Cible ==1865 or $Cible ==1866 or $Cible ==1972 or $Cible ==2163 or $Cible ==2214) //Australie
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE ID IN (1573,1763,1865,1866,1972,2163,2214) AND ID<>'$Cible' AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF >10 ORDER BY Nom ASC";
						else
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE ID='$Cible'";
					}
					else
					{
						if($Cible ==2306 or $Cible ==2307) //Corse
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND NoeudF > 10 AND ID IN (2306,2307) AND ID<>'$Cible' ORDER BY Nom ASC";
						elseif($Pays_Ori ==1 or $Pays_Ori ==3 or $Pays_Ori ==4 or $Pays_Ori ==5 or $Pays_Ori ==6)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE Latitude >=43 AND Longitude <14 AND Zone<>6 AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND Pays<>2 AND NoeudF >10 
							AND ID NOT IN ('$Cible',704,896,2306,2307) ORDER BY Nom ASC";
						elseif($Pays_Ori ==2)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass FROM Lieu WHERE Latitude >49 AND Longitude <14 AND Zone<>6 AND Flag IN (".$Allies.") AND Flag_Gare IN (".$Allies.") AND Pays=2 AND NoeudF >10  
							AND ID<>'$Cible' ORDER BY Nom ASC";
					}
					$con=dbconnecti();
					$result=mysqli_query($con,$query);
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result))
						{
							$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,$data['Longitude'],$data['Latitude']);
							if($Admin)$skills.="<br>".$Distance[0];
							if($Distance[0] <=$Range_train)
							{
								$CT_front_final=$CT_front_ori;
								$Impass=$data['Impass'];
								$sensh='';
								$sensv='';
								$coord=false;
								if($Longitude_front >$data['Longitude'])
								{
									$sensh='Ouest';
									$coord+=2;
									if($Impass ==2 or $Impass ==3 or $Impass ==4 or $Impass_ori ==6 or $Impass_ori ==7 or $Impass_ori ==8)
										$CT_front_final*=4;
								}
								elseif($Longitude_front <$data['Longitude'])
								{
									$sensh='Est';
									$coord+=1;
									if($Impass ==6 or $Impass ==7 or $Impass ==8 or $Impass_ori ==2 or $Impass_ori ==3 or $Impass_ori ==4)
										$CT_front_final*=4;
								}
								if($sensh)
								{
									if($Latitude_front >$data['Latitude']+0.5)
									{
										$sensv='Sud';
										$coord+=20;
										if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
											$CT_front_final*=4;
									}
									elseif($Latitude_front <$data['Latitude']-0.5)
									{
										$sensv='Nord';
										$coord+=10;
										if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
											$CT_front_final*=4;
									}
								}
								else
								{
									if($Latitude_front >$data['Latitude'])
									{
										$sensv='Sud';
										$coord+=20;
										if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
											$CT_front_final*=4;
									}
									elseif($Latitude_front <$data['Latitude'])
									{
										$sensv='Nord';
										$coord+=10;
										if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
											$CT_front_final*=4;
									}
								}
								$sens=$sensv.' '.$sensh;
								if($Credits >=$CT_front_final)
								{
									$choix.="<option value='".$data['ID']."_".$CT_front_final."'>".$data['Nom']." (".$Distance[0]."km ".$sens.")</option>";
									$choix_rose="<tr><td>".$data['Nom']."</td><td>".$Distance[0]."km</td></tr>";
									if($coord ==1) //Est
										$Est_txt.=$choix_rose;
									elseif($coord ==2) //Ouest
										$Ouest_txt.=$choix_rose;
									elseif($coord ==10) //Nord
										$Nord_txt.=$choix_rose;
									elseif($coord ==20) //Sud
										$Sud_txt.=$choix_rose;
									elseif($coord ==11) //NE
										$NE_txt.=$choix_rose;
									elseif($coord ==21) //SE
										$SE_txt.=$choix_rose;
									elseif($coord ==12) //NO
										$NO_txt.=$choix_rose;
									elseif($coord ==22) //SO
										$SO_txt.=$choix_rose;
								}
							}
						}
						mysqli_free_result($result);
					}
					if($choix)
					{
						$carte_txt="carte_ground.php?map=".$Front."&mode=1";
						$mes="<h2>Destination</h2><div class='row'><div class='col-md-6'>
							<form action='index.php?view=ground_move' method='post'><input type='hidden' name='Move_Type' value='".$Action."'>
							<select name='Action' class='form-control' style='width: 200px'><option value='0'>Rester � ".$titre."</option>".$choix."</select>
							<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div>
							<div class='col-md-6'><div class='btn btn-primary'><a href='".$carte_txt."' onclick='window.open(this.href); return false;'>Voir la carte</a></div></div></div>
							<div class='alert alert-info'>Autonomie du train ".$Range_train."km</div>";
						$mes.="<div class='row'>
							<div class='col-md-4 col-sm-6'><div class='panel panel-warning text-center'><div class='panel-heading'>Nord Ouest</div>
							<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$NO_txt."</table></div></div></div></div>
							<div class='col-md-4 col-sm-6'><div class='panel panel-warning text-center'><div class='panel-heading'>Nord</div>
							<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$Nord_txt."</table></div></div></div></div>
							<div class='col-md-4 col-sm-6'><div class='panel panel-warning text-center'><div class='panel-heading'>Nord Est</div>
							<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$NE_txt."</table></div></div></div></div>
						</div>
						<div class='row'>
							<div class='col-md-4 col-sm-6'><div class='panel panel-warning text-center'><div class='panel-heading'>Ouest</div>
							<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$Ouest_txt."</table></div></div></div></div>
							<div class='col-md-4 col-sm-6 text-center'><img src='images/travel_icon.png'></div>
							<div class='col-md-4 col-sm-6'><div class='panel panel-warning text-center'><div class='panel-heading'>Est</div>
							<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$Est_txt."</table></div></div></div></div>
						</div>
						<div class='row'>
							<div class='col-md-4 col-sm-6'><div class='panel panel-warning text-center'><div class='panel-heading'>Sud Ouest</div>
							<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$SO_txt."</table></div></div></div></div>
							<div class='col-md-4 col-sm-6'><div class='panel panel-warning text-center'><div class='panel-heading'>Sud</div>
							<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$Sud_txt."</table></div></div></div></div>
							<div class='col-md-4 col-sm-6'><div class='panel panel-warning text-center'><div class='panel-heading'>Sud Est</div>
							<div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover'>".$SE_txt."</table></div></div></div></div>
						</div>";
					}
					else
						$mes="<br>Vous retournez � votre point de d�part, aucune destination n'�tant disponible!<br>Les gares de destination doivent �tre contr�l�es par votre faction, ne pas �tre d�truites et �tre situ�es dans un rayon de ".$Range_train."km pour �tre des destinations valides";
				}
				else
					$mes="<br>Il n'y a pas de gare ici!";
			}
		}
		elseif($Action ==7)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=1,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes rejoignent la base a�rienne';
			$img="defense";
		}
		elseif($Action ==8)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=2,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes rejoignent le noeud routier';
			$img="defense";
		}
		elseif($Action ==9)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=3,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes rejoignent la gare';
			$img="defense";
		}
		elseif($Action ==10)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=4,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes rejoignent le port';
			$img="defense";
		}
		elseif($Action ==11)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=5,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes rejoignent le pont';
			$img="defense";
		}
		elseif($Action ==12)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=6,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes="<br>Vos troupes rejoignent l'usine";
			$img="defense";
		}
		elseif($Action ==13)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=7,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes rejoignent le radar';
			$img="defense";
		}
		elseif($Action ==14)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=0,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes rejoignent la caserne';
			$img="defense";
		}
		elseif($Action ==15 or $Action ==115)
		{
			if($Credits >=$CT_move and $CT_move >0)
			{
				$img="move_front".$country;
				$Fiabilite=0;
				$Rasputitsa=false;
				$Merzlota=false;
				$Mousson=false;
				$Mois=substr($Date_Campagne,5,2);
				if(($Pays_Ori ==8 or $Pays_Ori ==20) and ($Mois ==11 or $Mois ==3)) //Rasputitsa
				{
					$Rasputitsa=true;
					$img="rasputitsa";
				}
				elseif($Front ==3) 
				{
					if(($Longitude_front <=90 and ($Mois ==7 or $Mois ==8)) or ($Longitude_front >90 and ($Mois ==8 or $Mois ==9)))
					{
						$Mousson=true;
						$img="mousson";
					}
				}
				if(($Pays_Ori ==8 or $Pays_Ori ==20 or $Front ==5) and ($Mois ==12 or $Mois ==1 or $Mois ==2))$Merzlota=true;					
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Vehicule_ID,Placement FROM Regiment WHERE Officier_ID='$OfficierID' AND Vehicule_Nbr >0");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Vehicule=$data['Vehicule_ID'];
						$Placement=$data['Placement'];						
						$con=dbconnecti();
						$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
						$result2=mysqli_query($con,"SELECT Fuel,mobile,Type,Fiabilite FROM Cible WHERE ID='$Vehicule'");
						mysqli_close($con);
						if($result2)
						{
							while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								if($NoeudR >0 and $Placement ==2 and !$Rasputitsa and !$Mousson and !$Enis)$Zone_calc=0;
								$data2['Fuel']=Get_LandSpeed($data2['Fuel'],$data2['mobile'],$Zone_calc,0,$data2['Type'],0,0,0, $Front);
								if($Action ==115)
								{
									if($data2['mobile'] ==3 or $data2['Type'] ==6)
									{
										$data2['Fuel']*=2;
										UpdateData("Regiment","Moral",-20,"ID",$data['ID']);
									}
								}
								if($Skill4 ==100 and $Zone ==8)$data2['Fuel']*=2; //guerre du d�sert									
								$Autonomie[]=$data2['Fuel'];
								$Mobile_t[]=$data2['mobile'];
								$Fiabilite+=$data2['Fiabilite'];
							}
							mysqli_free_result($result2);
						}
					}
				}
				if($Autonomie)$Autonomie_Min=min($Autonomie);
				if($Mobile_t)$Mobile_Min=min($Mobile_t);
				unset($Autonomie);
				unset($Mobile_t);				
				$Lat_min=$Latitude_front-2;
				$Lat_max=$Latitude_front+2;
				$Long_min=$Longitude_front-3;
				$Long_max=$Longitude_front+3;
				$Autonomie_Max=150;
				if($G_Treve)$Treve_txt="AND Flag='$country'";
				if($Front ==2)
				{
					if($Cible ==903 or $Cible ==910 or $Cible ==1090 or $Cible ==1288 or $Cible ==1653) //Cr�te
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (903,910,1090,1288,1653) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Cible ==435 or $Cible ==450 or $Cible ==465 or $Cible ==1644 or $Cible ==1647 or $Cible ==2127) //Sardaigne
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (435,450,465,1644,1647,2127) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Cible ==2306 or $Cible ==2307 or $Cible ==2308 or $Cible ==2309 or $Cible ==2310) //Corse
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (2306,2307,2308,2309,2310) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Latitude_front >36.7 and $Latitude_front <38.2 and $Longitude_front >12.5 and $Longitude_front <=15.55) //Sicile
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Latitude BETWEEN 36.7 AND 38.2) AND (Longitude BETWEEN 12.5 AND 15.56) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Latitude_front <36.7 or ($Longitude_front <12 and $Latitude_front <37.3 and $Pays_Ori !=6)) //AFN
					{
						if($Latitude_front <33 and $Longitude_front <34 and $Longitude_front >11.22)
						{
							if($Longitude_front <25.16 and $Latitude_front >31.12)
								$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND 25.16) AND (Latitude BETWEEN '$Lat_min' AND 33.15) AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
							elseif($Longitude_front >25.16 and $Latitude_front >31.12)
								$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 25.16 AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND 33) AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
							else
								$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND 33.15) AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653)";
						}
						else
							$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE Latitude <37.3 AND Longitude >-8 AND Longitude <50 AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
					}
					elseif($Latitude_front >36.6 and $Longitude_front >19) //Gr�ce
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE Latitude >36.6 AND Longitude >19 AND Longitude <50 AND Zone<>6 ".$Treve_txt." AND PAYS NOT IN (2,4,6) ID NOT IN ('$Cible',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
					elseif($Latitude_front >38.2)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Latitude BETWEEN 38.2 AND 45.5) AND (Longitude BETWEEN -2 AND 50) AND Zone<>6 ".$Treve_txt." AND PAYS NOT IN (10,24) AND ID NOT IN ('$Cible',343,435,445,450,465,529,678,903,910,1090,1288,1644,1647,1653,2127,2306,2307,2308,2309,2310) ORDER BY Nom ASC";
					else
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',343,435,445,450,465,529,678,903,910,1090,1288,1644,1647,1653,2127,2306,2307,2308,2309,2310) ORDER BY Nom ASC LIMIT 100";
				}
				elseif($Front ==1 or $Front ==4)
				{
					$Autonomie_Max=250;
					if($country ==20)
					{
						if($Lat_min <60)$Lat_min=60;
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
					}
					elseif($Latitude_front <46 and $Latitude_front>44.40 and $Longitude_front >33 and $Longitude_front <36.5) //Crim�e
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 33 AND 36.5) AND (Latitude BETWEEN 44.4 AND 46.5) AND Zone<>6 ".$Treve_txt." AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Latitude_front <47 and $Latitude_front>41 and $Longitude_front >37 and $Longitude_front <48) //Caucase
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 37 AND 50) AND (Latitude BETWEEN 41 AND 48) AND Zone<>6 ".$Treve_txt." AND ID<>'$Cible' ORDER BY Nom ASC";
					else
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
				}
				elseif($Front ==5)
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1252) ORDER BY Nom ASC";
				elseif($Front ==3)
				{
					$Autonomie_Max=300;
					if($G_Treve)$Treve_txt="AND Flag='$country'";
					if($Cible ==1610 or $Cible ==1618 or $Cible ==1637 or $Cible ==1869 or $Cible ==1894) //Ceylan
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1610,1618,1637,1869,1894) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Longitude_front >90 and $Longitude_front <110 and $Latitude_front >1.20 and $Cible !=1870 and $Cible !=1903) //Continent
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 90 AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1754,1809,1870,1900) ORDER BY Nom ASC";
					elseif($Longitude_front <90 and $Latitude_front >9) //Inde
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND 90) AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt." AND ID NOT IN ('$Cible',1754,1809,1870,1900) ORDER BY Nom ASC";
					elseif($Cible ==1368 or $Cible ==1556 or $Cible ==1582 or $Cible ==1776 or $Cible ==1803 or $Cible ==1805 or $Cible ==1811 or $Cible ==1857 or $Cible ==2379 or $Cible ==2380 or $Cible ==2381) //Japon
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1368,1556,1582,1776,1803,1805,1811,1857,2379,2380,2381) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Cible ==1583 or $Cible ==1800 or $Cible ==1801 or $Cible ==1804) //Formose
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1583,1800,1801,1804) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Cible ==1569 or $Cible ==1570 or $Cible ==1571 or $Cible ==1764 or $Cible ==1881 or $Cible ==1888 or $Cible ==1889 or $Cible ==2353 or $Cible ==2354 or $Cible ==2355 or $Cible ==2356 or $Cible ==2357) //Philippines
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1569,1570,1571,1764,1881,1888,1889,2353,2354,2355,2356,2357) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Cible ==1370 or $Cible ==1574 or $Cible ==1575 or $Cible ==1576 or $Cible ==1613 or $Cible ==1892 or $Cible ==1895 or $Cible ==2358) //Java
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1370,1574,1575,1576,1613,1892,1895,2358) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Cible ==1365 or $Cible ==1809 or $Cible ==1873 or $Cible ==1887) //Sumatra
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1365,1809,1873,1887) AND ID<>'$Cible' ORDER BY Nom ASC";
					elseif($Cible ==1573 or $Cible ==1763 or $Cible ==1865 or $Cible ==1866 or $Cible ==1972 or $Cible ==2163 or $Cible ==2214) //Australie
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID IN (1573,1763,1865,1866,1972,2163,2214) AND ID<>'$Cible' ORDER BY Nom ASC";
					else
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE ID='$Cible'";
				}
				else
				{
					if($Pays_Ori ==1 or $Pays_Ori ==3 or $Pays_Ori ==4 or $Pays_Ori ==5 or $Pays_Ori ==6)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt."
						AND Pays<>2 AND ID NOT IN ('$Cible',2306,2307,2308,2309,2310) ORDER BY Nom ASC";
					elseif($Pays_Ori ==2)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Flag_Route,Flag,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 ".$Treve_txt."
						AND Pays=2 AND ID NOT IN ('$Cible',349,593,735,873,918,915,941,942,943,944,951,1373,1374) ORDER BY Nom ASC";
				}
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				//mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result))
					{			
						$coord=0;
						$CT_city=$CT_move;
						$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,$data[2],$data[3]);
						if($data['NoeudR'])
							$Faction_Dest_Route=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Route']."'"),0);
						if($data['NoeudR'] and $NoeudR >0 and $Placement ==2 and !$Rasputitsa and !$Enis and $Faction_Dest_Route ==$Faction)
							$Autonomie_Actu=$Autonomie_Min*2;
						else
							$Autonomie_Actu=$Autonomie_Min;
						if($Autonomie_Actu >$Autonomie_Max)
							$Autonomie_Actu=$Autonomie_Max;
						if($Distance[0] <=$Autonomie_Actu)
						{
							$Impass=$data['Impass'];
							if($data['NoeudF'])
								$icone="<img src='/images/rail.gif' title='Noeud Ferroviaire'>";
							elseif($data['NoeudR'] and !$Rasputitsa)
								$icone="<img src='/images/route.gif' title='Noeud Routier'>";
							else
								$icone="<img src='/images/zone".$data['Zone'].".jpg'>";
							$sensh='';
							$sensv='';
							if($Longitude_front >$data[2])
							{
								$sensh='Ouest';
								$coord+=2;
								if($Impass ==2 or $Impass ==3 or $Impass ==4 or $Impass_ori ==6 or $Impass_ori ==7 or $Impass_ori ==8)
									$CT_city=999;
							}
							elseif($Longitude_front <$data[2])
							{
								$sensh='Est';
								$coord+=1;
								if($Impass ==6 or $Impass ==7 or $Impass ==8 or $Impass_ori ==2 or $Impass_ori ==3 or $Impass_ori ==4)
									$CT_city=999;
							}
							if($sensh)
							{
								if($Latitude_front >$data[3]+0.25)
								{
									$sensv='Sud';	
									$coord+=20;
									if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
										$CT_city=999;
								}
								elseif($Latitude_front <$data[3]-0.25)
								{
									$sensv='Nord';
									$coord+=10;
									if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
										$CT_city=999;
								}
							}
							else
							{
								if($Latitude_front >$data[3])
								{
									$sensv='Sud';
									$coord+=20;
									if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
										$CT_city=999;
								}
								elseif($Latitude_front <$data[3])
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
									$Distance[0]=ceil($Distance[0]*1.25);
							}
							if($Faction !=GetData("Pays","ID",$data['Flag'],"Faction"))
							{
								$CT_city*=2;
								if($Enis)
									$Distance[0]*=2;
								else
									$Distance[0]=ceil($Distance[0]*1.5);
							}
							if($Skill4 ==100 and $data['Zone'] ==8)$CT_city-=1;
							if($CT_city !=999 and $CT_city >35)$CT_city=35;
							$CT_city-=$Fiabilite;
							if($Distance[0] <=$Autonomie_Actu)
							{
								if($Credits >=$CT_city)
									$choix="<tr><td><Input type='Radio' name='Action' value='".$data[0]."_".$CT_city."'><img src='/images/CT".$CT_city.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- ".$data[1]."</td><td><img src='".$data['Flag']."20.gif'></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
								else
									$choix="<tr><td><Input type='Radio' name='Action' value='".$data[0]."_".$CT_city."' disabled><img src='/images/CT".$CT_city.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- ".$data[1]."</td><td><img src='".$data['Flag']."20.gif'></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
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
						}
					}
					mysqli_free_result($result);
				}
				mysqli_close($con);
					/*if($Front ==2 and $Date_Campagne >'1941-04-10')
					{
						$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,23.96,32.08);
						if($NoeudR)
							$Autonomie_Actu=$Autonomie_Min*2;
						if($Distance[0] <=$Autonomie_Actu)
						{
							$CT_city=ceil($CT_move+floor($Distance[0]/100));
							if($Credits >=$CT_city)
								$choix.="<tr><td align='left'><Input type='Radio' name='Action' value='457_".$CT_city."'>- Tobrouk<br></td><td>".$Distance[0]."km</td><td>".$CT_city."CT</td></tr>";
							else
								$choix.="<tr><td align='left'><Input type='Radio' name='Action' value='".$data[0]."_".$CT_city."' disabled>- ".$data[1]." (".$Distance[0]."km</td><td>".$CT_city."CT</td></tr>";
						}
					}*/
				if($choix)
				{
					 /*if($Front ==3)
						$carte_txt="carte_pacifique.php";
					 elseif($Front ==2)
						$carte_txt="carte_med_est.php";
					 elseif($Front ==5)
						$carte_txt="carte_arctic.php";
					 elseif($Front ==4)
						$carte_txt="carte_nord_est.php";
					 elseif($Front ==1)
						$carte_txt="carte_sud_est.php";
					 else
						$carte_txt="carte_ouest.php";*/
					$carte_txt="carte_ground.php?map=".$Front."&mode=10&cible=".$Cible;
					if($Autonomie_Min >300)$Autonomie_Min=300;
					$mes="<div class='btn btn-info'><a href='".$carte_txt."' onclick='window.open(this.href); return false;'>Voir la carte</a></div>
						<h2>Destinations</h2>
						<p class='lead'>Autonomie max : ".$Autonomie_Min."km <a href='#' class='popup'><img src='images/help.png'><span>Doubl� si votre bataillon se trouve sur un noeud routier</span></a></p>
						<form action='index.php?view=ground_move' method='post'>
						<input type='hidden' name='Move_Type' value='".$Action."'>
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
						<Input type='Radio' name='Action' value='0' checked>- Annuler le d�placement.<br>
						<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div></div>";
				}
				else
					$mes="<br>Vous retournez � votre point de d�part, l'autonomie de vos troupes (".$Autonomie_Min."km) est insuffisante ou vous manquez de temps pour atteindre la destination la plus proche !
						<br>Si le lieu poss�de un <img src='images/route.gif' title='noeud routier' alt='noeud routier'> contr�l� par votre faction, l'autonomie de vos troupes augmentera en vous d�pla�ant sur le noeud routier.<br>Vous pouvez aussi utiliser le transport ferroviaire en vous rendant dans une <img src='images/vehicules/vehicule9.gif' title='gare' alt='gare'> contr�l�e de votre faction.";
			}
		}
		elseif($Action ==16)
		{
			if($Credits >=4)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID,Experience FROM Regiment WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						if($data['Experience'] <41)
						{
							UpdateData("Regiment","Experience",10,"ID",$data['ID']);
							$mes.="<br>La ".$data['ID']."e Cie a gagn� en exp�rience!";
						}
					}
					mysqli_free_result($result);
				}
				UpdateData("Officier","Credits",-4,"ID",$OfficierID);
				$mes.="<p>Vos troupes s'entrainent dur!</p>";
				$img="training";
			}
		}
		elseif($Action ==17)
		{
			if($Credits >=1)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-1,"ID",$OfficierID);
				$mes='<br>Vos troupes se pr�parent � faire mouvement';
				$img="prepare";
			}
		}
		elseif($Action ==18)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Moral=100 WHERE Officier_ID='$OfficierID' AND Moral < 100");
			mysqli_close($con);
			UpdateData("Officier","Credits",-4,"ID",$OfficierID);
			$mes='<br>Vos troupes ont � nouveau le moral!';
			$img="cig";
		}
		elseif($Action ==19)
		{
			if($Credits >=4)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=5,Move=0,Camouflage=1 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-4,"ID",$OfficierID);
				$mes='<br>Vos troupes se placent en appui';
				$img="appui".$country;
			}
		}	
		elseif($Action ==20)
		{
			if($Credits >= $Credits_ret and $Credits_ret >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=10,Visible=0,Move=0,Camouflage=4 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_ret,"ID",$OfficierID);
				$mes='<br>Vos troupes se retranchent sur leurs positions et �tablissent une ligne de d�fense';
				$img="digin";
			}
		}
		elseif($Action ==40)
		{
			if($Credits >=24)
			{
				SetData("Lieu","Pont",0,"ID",$Cible);
				UpdateData("Officier","Credits",-24,"ID",$OfficierID);
				UpdateData("Officier","Avancement",24,"ID",$OfficierID);
				$mes='<br>Vos troupes font sauter le pont';
				$img="exploser_pont";
			}
		}	
		elseif($Action ==41)
		{
			if($Credits >=24)
			{
				UpdateData("Lieu","NoeudF",-25,"ID",$Cible);
				UpdateData("Officier","Credits",-24,"ID",$OfficierID);
				UpdateData("Officier","Avancement",24,"ID",$OfficierID);
				$mes='<br>Vos troupes sabotent la gare';
				$img="exploser_gare";
			}
		}	
		elseif($Action ==42)
		{
			if($Credits >=24)
			{
				UpdateData("Lieu","Port",-25,"ID",$Cible);
				UpdateData("Officier","Credits",-24,"ID",$OfficierID);
				UpdateData("Officier","Avancement",24,"ID",$OfficierID);
				$mes='<br>Vos troupes sabotent le port';
				$img="exploser_port";
			}
		}	
		elseif($Action ==51)
		{
			if($Credits >=40)
			{
				UpdateData("Lieu","NoeudF",25,"ID",$Cible,100);
				UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				UpdateData("Officier","Avancement",40,"ID",$OfficierID);
				UpdateData("Officier","Reputation",10,"ID",$OfficierID);
				$mes='<br>Vos troupes tentent de r�parer la gare';
				$img="bulldozer";
			}
		}	
		elseif($Action ==52)
		{
			if($Credits >=40)
			{
				UpdateData("Lieu","Port",25,"ID",$Cible,100);
				UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				UpdateData("Officier","Avancement",40,"ID",$OfficierID);
				UpdateData("Officier","Reputation",10,"ID",$OfficierID);
				$mes='<br>Vos troupes tentent de r�parer le port';
				$img="bulldozer";
			}
		}	
		elseif($Action ==60)
		{
			if($Credits >=2)
			{
				$Faction_Ori=GetData("Pays","ID",$Pays_Ori,"Faction");
				if($Faction ==$Faction_Ori)
					$Pays_Rev=$Pays_Ori;
				else
					$Pays_Rev=$country;
				if($ValStrat >0)
				{
					$con=dbconnecti();
					$reset1=mysqli_query($con,"UPDATE Officier SET Avancement=Avancement+'$ValStrat',Atk WHERE Pays='$country' AND Front='$Front'");
					$reset2=mysqli_query($con,"UPDATE Pilote SET Moral=Moral+'$ValStrat' WHERE Pays='$country' AND Actif=0 AND Front='$Front'");
					$reset3=mysqli_query($con,"UPDATE Officier SET Avancement=Avancement-'$ValStrat' WHERE Pays='$Flag' AND Front='$Front'");
					$reset4=mysqli_query($con,"UPDATE Pilote SET Moral=Moral-'$ValStrat' WHERE Pays='$Flag' AND Actif=0");
					$reset5=mysqli_query($con,"UPDATE Regiment SET Moral=Moral-'$ValStrat' WHERE Pays='$Flag'");
					$reset6=mysqli_query($con,"UPDATE Division as d,Pays as p SET d.Base=d.Base_Ori WHERE d.Pays=p.ID AND d.Base='$Cible' AND p.Faction<>'$Faction'");
					mysqli_close($con);
					AddEventFeed(44,$Pays_Rev,$OfficierID,$Reg,$Cible,$Faction);
					/*$carb=mt_rand(0,1);
					$cal_array=array(8,13,20,30,40,50,60,75,90,105,125,150);
					$cal_key=array_rand($cal_array);
					$cal=$cal_array($cal_key);
					if($ValStrat <6)
					{
						if($ValStrat ==1 and $cal >20)
							$cal=8;
						elseif($ValStrat ==2 and $cal >40)
							$cal=8;
						elseif($ValStrat ==3 and $cal >75)
							$cal=8;
						elseif($ValStrat ==4 and $cal >105)
							$cal=8;
						elseif($ValStrat ==5 and $cal >125)
							$cal=8;
					}
					if(!$cal)$cal=8;
					if(!$carb)$carb=87;
					$ValStrat*=10;
					$up_cal=mt_rand((10*$ValStrat),(250*$ValStrat));
					$up_carb=mt_rand((100*$ValStrat),(1000*$ValStrat));
					$con=dbconnecti();
					$reset6=mysqli_query($con,"UPDATE Regiment SET Moral=Moral+'$ValStrat' WHERE Officier_ID='$OfficierID'");
					$reset7=mysqli_query($con,"UPDATE Officier SET Avancement=Avancement+'$ValStrat' WHERE ID='$OfficierID'");
					$reset8=mysqli_query($con,"UPDATE Regiment SET Moral=Moral+'$ValStrat',Stock_Munitions_'$cal'=Stock_Munitions_'$cal'+'$up_cal',Stock_Essence_'$carb'=Stock_Essence_'$carb'+'$up_carb' WHERE Officier_ID='$OfficierID'");
					mysqli_close($con);*/
				}
				SetData("Lieu","Flag",$Pays_Rev,"ID",$Cible);
				UpdateData("Officier","Credits",-2,"ID",$OfficierID);
				SetData("Officier","Atk",1,"ID",$OfficierID);
				$mes='<br>Vos troupes prennent position';
				$img="capture_flag";				
			}
		}
		elseif($Action >60 and $Action <69)
		{
			if($Credits >=2)
			{
				$Pays_Ori=GetData("Lieu","ID",$Cible,"Pays");
				$Faction_Ori=GetData("Pays","ID",$Pays_Ori,"Faction");
				if($Faction ==$Faction_Ori)
					$Pays_Rev=$Pays_Ori;
				else
					$Pays_Rev=$country;
				if($Action ==61)
				{
					$Flag_field="Flag_Air";
					$Retraite=Get_Retraite($Front,$Flag_Air,$Latitude_front);
					$Lands=GetAllies($Date_Campagne);
					if($Faction ==1)
						$Ennemis=$Lands[0];
					elseif($Faction ==2)
						$Ennemis=$Lands[1];
					$con=dbconnecti();
					$resultf=mysqli_query($con,"SELECT Commandant,Adjoint_EM FROM Pays WHERE Pays_ID='$Flag_Air' AND Front='$Front'");
					$reset_esc=mysqli_query($con,"UPDATE Unit as u,Pilote_IA as j,Flak as f SET u.Avion1_Nbr=0,u.Avion2_Nbr=0,u.Avion3_Nbr=0,u.Stock_Essence_87=0,u.Stock_Essence_100=0,u.Stock_Essence_130=0,u.Stock_Essence_1=0,
					u.Stock_Munitions_8=0,u.Stock_Munitions_13=0,u.Stock_Munitions_20=0,u.Stock_Munitions_30=0,u.Stock_Munitions_40=0,u.Stock_Munitions_75=0,u.Stock_Munitions_90=0,u.Stock_Munitions_105=0,u.Stock_Munitions_125=0,
					u.Bombes_50=0,u.Bombes_80=0,u.Bombes_125=0,u.Bombes_250=0,u.Bombes_300=0,u.Bombes_400=0,u.Bombes_500=0,u.Bombes_800=0,u.Bombes_1000=0,u.Bombes_2000=0,u.Mission_Lieu=0,u.Mission_Type=0,u.Mission_alt=0,u.Mission_Lieu_D=0,u.Mission_Type_D=0,u.Mission_IA=0,u.Hide=0,u.Base='$Retraite',
					j.Cible=0,j.Couverture=0,j.Couverture_Nuit=0,j.Escorte=0,j.Avion=0,j.Alt=0,j.Task=0,f.Unit=0 WHERE j.Unit=u.ID AND f.Unit=u.ID AND u.Base='$Cible' AND u.Pays IN (".$Ennemis.")");
					$reset_esc=mysqli_affected_rows($con);
					mysqli_close($con);
					if($resultf)
					{
						while($data=mysqli_fetch_array($resultf,MYSQLI_ASSOC))
						{
							$Commandant_eni=$data['Commandant'];
							$Officier_Adjoint_eni=$data['Adjoint_EM'];
						}
						mysqli_free_result($resultf);
					}
					if($reset_esc >0)
					{
						include_once('./jfv_msg.inc.php');
						if($Commandant_eni >0)
							SendMsgOff($Commandant_eni,0,"Les troupes ennemies ont captur� ".$titre." et son a�rodrome. Les unit�s a�riennes non �vacu�es ont �t� d�truites!","A�rodrome captur�",0,3);
						elseif($Officier_Adjoint_eni >0)
							SendMsgOff($Officier_Adjoint_eni,0,"Les troupes ennemies ont captur� ".$titre." et son a�rodrome. Les unit�s a�riennes non �vacu�es ont �t� d�truites!","A�rodrome captur�",0,3);
					}
				}
				elseif($Action ==62)
					$Flag_field="Flag_Route";
				elseif($Action ==63)
					$Flag_field="Flag_Gare";
				elseif($Action ==64)
					$Flag_field="Flag_Port";
				elseif($Action ==65)
					$Flag_field="Flag_Pont";
				elseif($Action ==66)
					$Flag_field="Flag_Usine";
				elseif($Action ==67)
					$Flag_field="Flag_Radar";
				elseif($Action ==68)
					$Flag_field="Flag_Plage";
				SetData("Lieu",$Flag_field,$Pays_Rev,"ID",$Cible);
				UpdateData("Officier","Credits",-2,"ID",$OfficierID);
				$mes="<br>Vos troupes prennent position sur la zone";
				$img="capture_flag";				
			}
		}
		elseif($Action ==69)
		{
			if($Credits >=2)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Lieu SET Occupant='$country',Flag='$country' WHERE ID='$Cible'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-2,"ID",$OfficierID);
				$mes='<br>Vos navires quadrillent la zone';
				$img="quadrillage";				
			}
		}
		elseif($Action ==70)
		{
			if($Credits >=40)
			{				
				$con=dbconnecti();
				$First=mysqli_result(mysqli_query($con,"SELECT MIN(ID) FROM Regiment WHERE Officier_ID='$OfficierID'"),0);
				mysqli_close($con);
				$con=dbconnecti();
				$ok3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=67,Vehicule_Nbr=4,Camouflage=1,Position=0,Fret=0,Fret_Qty=0,Experience=0,HP=0 WHERE Officier_ID='$OfficierID'");
				$ok4=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=56,Vehicule_Nbr=1,Camouflage=1,Position=0,Fret=0,Fret_Qty=0,Experience=0,HP=0 WHERE ID='$First'");
				mysqli_close($con);
				if($ok3 and $ok4)
				{
					$mes='<br>Votre bataillon est transform� en convoi ferroviaire';
					UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				}
				else
					$mes="[Erreur 70] : Veuillez signaler cette erreur sur le forum";
				$img="train_convoi";				
			}
			else
				$mes="Tsss !";
		}
		elseif($Action ==71)
		{
			if($Credits >=40)
			{				
				switch($country)
				{
					case 1:
						$Vehicule=103;
						$Veh_Nbr=100;
					break;
					case 2:
						$Vehicule=104;
						$Veh_Nbr=100;
					break;
					case 4:
						$Vehicule=105;
						$Veh_Nbr=100;
					break;
					case 6:
						$Vehicule=106;
						$Veh_Nbr=100;
					break;
					case 7:
						$Vehicule=48;
						$Veh_Nbr=100;
					break;
					case 8:
						$Vehicule=270;
						$Veh_Nbr=100;
					break;
					case 20:
						$Vehicule=334;
						$Veh_Nbr=100;
					break;
					default:
						$Vehicule=48;
						$Veh_Nbr=100;
					break;
				}
				$con=dbconnecti();
				$ok3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID='$Vehicule',Vehicule_Nbr='$Veh_Nbr',Camouflage=1,Position=0,Fret=0,Fret_Qty=0,Experience=0,HP=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				if($ok3)
				{
					$mes='<br>Votre convoi ferroviaire est transform� en bataillon';
					UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				}
				else
					$mes="[Erreur 71] : Veuillez signaler cette erreur sur le forum";
				$img="train_convoi";				
			}
			else
				$mes="Tsss !";
		}
		elseif($Action ==72)
		{
			if($Credits >=40)
			{				
				$con=dbconnecti();
				$ok3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID=5001,Vehicule_Nbr=1,Camouflage=1,Position=0,Fret=0,Fret_Qty=0,Experience=0,HP=5000 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				if($ok3)
				{
					$mes='<br>Votre bataillon est transform� en convoi maritime';
					UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				}
				else
					$mes="[Erreur 72] : Veuillez signaler cette erreur sur le forum";
				$img="train_convoi";				
			}
			else
				$mes="Tsss !";
		}
		elseif($Action ==73)
		{
			if($Credits >=40)
			{				
				switch($country)
				{
					case 1:
						$Vehicule=103;
						$Veh_Nbr=100;
					break;
					case 2:
						$Vehicule=104;
						$Veh_Nbr=100;
					break;
					case 4:
						$Vehicule=105;
						$Veh_Nbr=100;
					break;
					case 6:
						$Vehicule=106;
						$Veh_Nbr=100;
					break;
					case 7:
						$Vehicule=478;
						$Veh_Nbr=100;
					break;
					case 8:
						$Vehicule=270;
						$Veh_Nbr=100;
					break;
					case 9:
						$Vehicule=558;
						$Veh_Nbr=100;
					break;
					case 20:
						$Vehicule=334;
						$Veh_Nbr=100;
					break;
					default:
						$Vehicule=48;
						$Veh_Nbr=100;
					break;
				}
				$con=dbconnecti();
				$ok3=mysqli_query($con,"UPDATE Regiment SET Vehicule_ID='$Vehicule',Vehicule_Nbr='$Veh_Nbr',Camouflage=1,Position=0,Fret=0,Fret_Qty=0,Experience=0,HP=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				if($ok3)
				{
					$mes='<br>Votre convoi maritime est transform� en bataillon';
					UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				}
				else
					$mes="[Erreur 73] : Veuillez signaler cette erreur sur le forum";
				$img="train_convoi";				
			}
			else
				$mes="Tsss !";
		}
		elseif($Action ==74)
		{
			if($Credits >=1)
			{				
				$con=dbconnecti();
				$ok=mysqli_query($con,"UPDATE Regiment SET Camouflage=2,Position=0,Visible=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-1,"ID",$OfficierID);
				$mes='<br>Votre convoi ferroviaire se r�fugie dans le d�p�t';
				$img="train_convoi";
			}
		}
		elseif($Action ==99)
		{
			if($Credits >=40)
			{
				$con=dbconnecti();
				$ok=mysqli_query($con,"UPDATE Officier SET Train_Lieu=0,Barges_Lieu=0,Para_Lieu=0,Mission_Lieu_D=0,Mission_Type_D=0 WHERE ID='$OfficierID'");
				mysqli_close($con);
				SetData("Regiment","Position",4,"Officier_ID",$OfficierID);
				UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				$mes="<p>Vous annulez votre demande de transit.</p>";
				$img="train_convoi";	
			}
		}
		elseif($Action ==106) //maritime
		{
			if($Credits >=$CT_move and $CT_move >0)
			{
				$img="appareiller";
				$Lieu=$Cible;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT r.Vehicule_ID,r.Vehicule_Nbr,r.Lieu_ID,r.Position,r.HP as Vie,c.Fuel,c.Conso,c.Vitesse,c.Type,c.HP as HP_max FROM Regiment as r,Cible as c WHERE r.Officier_ID='$OfficierID' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$CT_move_this=$CT_move;
						$Vehicule=$data['Vehicule_ID'];
						$Position=$data['Position'];
						if(!$Lieu)$Lieu=$data['Lieu_ID'];
						if(!$Longitude_front)$Longitude_front=GetData("Lieu","ID",$Lieu,"Longitude");
						if(!$Latitude_front)$Latitude_front=GetData("Lieu","ID",$Lieu,"Latitude");
						$hp_good=round(($data['HP_max']/$data['Vie'])*100);
						$Speed=Get_LandSpeed($data['Vitesse'],5,6,0,0,$hp_good);
						if($Longitude_front >-8 and $Longitude_front <60 and $Latitude_front <61 and $Latitude_front >29.96  and $data['Vie'] >0)
						{
							if($data['Type'] ==14)
								$Auto_max=$Speed*12;
							else
								$Auto_max=$Speed*8;
							$CT_move_this=$CT_move/2;
						}
						else
							$Auto_max=$Speed*72;
						if($Meteo <-70)
							$Auto_max/=5;
						elseif($Meteo <-20)
							$Auto_max/=2;
						if($data['Fuel'] >$Auto_max)
							$Auto_ind=round($Auto_max);
						else
							$Auto_ind=$data['Fuel'];
						if($data['Position'] ==25) //Plong�e
							$Auto_ind/=2;
						$Autonomie[]=$Auto_ind;	
						$Vitesse_n[]=$Speed;
						/*if($OfficierID ==1)
							mail('binote@hotmail.com','Aube des Aigles: Appareillage',"Vehicule : ".$Vehicule." : HP_max : ".$data['HP_max'].", HP : ".$data['Vie'].", Vitesse : ".$data['Vitesse'].", Auto_max : ".$Auto_max.", Auto_ind : ".$Auto_ind." / Lieu : ".$Lieu);*/
					}
					mysqli_free_result($result);
				}
				if($Autonomie)$Autonomie_Min=min($Autonomie);
				unset($Autonomie);
				if($Vitesse_n)$Vitesse_Min=min($Vitesse_n);
				unset($Vitesse_n);
				if($Autonomie_Min >2400)$Autonomie_Min=2400;
				$con=dbconnecti();
				$result3=mysqli_query($con,"SELECT Longitude,Latitude,Impass,Meteo,Mines_m FROM Lieu WHERE ID='$Lieu'");
				mysqli_close($con);
				if($result3)
				{
					while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
					{
						$Latitude_front=$data3['Latitude'];
						$Longitude_front=$data3['Longitude'];
						$Impass_ori=$data3['Impass'];
						$Meteo=$data3['Meteo'];
						$Mines_m=$data3['Mines_m'];
					}
					mysqli_free_result($result3);
				}				
				if($Position !=25 and $Vitesse_Min <40)
				{
					$con=dbconnecti();
					$Enis_Interdiction=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Position=27 AND c.Type >14 AND r.Vehicule_Nbr >0 AND c.Vitesse >35"),0);
					$Enis_Interdiction2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Position=27 AND c.Type >14 AND r.Vehicule_Nbr >0 AND c.Vitesse >35"),0);
					mysqli_close($con);
					$Enis_Interdiction+=$Enis_Interdiction2;
				}
				if($Meteo <-70 and !$Admin)
				{
					$CT_move=999;
					$mes="<p>Le mauvais temps interdit tout appareillage!</p>";
				}
				else
				{				
					if($Lieu ==1984 or $Lieu ==1986 or $Lieu ==1987 or $Lieu ==1988) //Cote Espagnole
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <-5.35 AND Latitude <50 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Lieu ==198 or $Lieu ==199 or $Lieu ==449 or $Lieu ==459 or $Lieu ==500 or $Lieu ==507 or $Lieu ==701 or $Lieu ==750 or $Lieu ==819 or $Lieu ==1113 or $Lieu ==1180 or $Lieu ==1181 or $Lieu ==1562 or $Lieu ==2550) //Mer Adriatique
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE ID IN(198,199,449,459,500,506,507,701,750,819,1113,1180,1181,1562,2550) ORDER BY Nom ASC";
					elseif($Lieu ==269 or $Lieu ==494 or $Lieu ==593 or $Lieu ==731 or $Lieu ==1609 or $Lieu ==2302 or $Lieu ==2551) //Mer Irlande
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE ID IN(269,494,495,593,731,1154,1609,2302,2551) ORDER BY Nom ASC";
					elseif($Lieu ==2026 or $Lieu ==2016 or $Lieu ==2011 or $Lieu ==2030 or $Lieu ==2031 or $Lieu ==2027 or $Lieu ==2028) //Mer Rouge
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >32 AND Longitude <46 AND Latitude <30 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Lieu ==279 or $Lieu ==280 or $Lieu ==295 or $Lieu ==296 or $Lieu ==312 or $Lieu ==470 or $Lieu ==488 or $Lieu ==489 or $Lieu ==490 or $Lieu ==592 or $Lieu ==729 or $Lieu ==730 or $Lieu ==880 or $Lieu ==952 or $Lieu ==1138 or $Lieu ==1364 or $Lieu ==1614 or $Lieu ==2540) //Pas de Mer Irlande depuis l'est de l'angleterre
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE ((Longitude >-2.5 AND Longitude <14 AND Latitude >44 AND Latitude <54) OR (Longitude >-4 AND Longitude <14 AND Latitude >55)) AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Latitude_front >64)
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <60 AND Latitude >60 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Front ==2)
					{		
						if($Longitude_front <15.6 and $Latitude_front <45.3) //Pas de Mer Adriatique si on vient de l'ouest du d�troit de Messine
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >-5.5 AND Longitude <73 AND Latitude <44.5 AND (Zone=6 OR Port>0 OR Plage=1) AND ID NOT IN ('$Lieu',198,199,449,458,459,500,505,506,507,701,750,819,1113,1180,1181,1562,2550) ORDER BY Nom ASC";
						elseif($country ==6 or $country ==1) //Pas de Suez
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <50 AND Latitude <44.5 AND (Zone=6 OR Port >0 OR Plage=1) AND Longitude >-5.51 AND ID NOT IN ('$Lieu',262,2015,2016,2026) ORDER BY Nom ASC";
						else
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >-5.51 AND Longitude <73 AND Latitude <44.5 AND (Zone=6 OR Port >0 OR Plage=1) AND ID NOT IN ('$Lieu',262) ORDER BY Nom ASC";
					}
					elseif($Front ==1 or $Front ==4)
					{
						if($G_Treve)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >13 AND Longitude <57 AND Latitude >43 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' AND Flag='$country' ORDER BY Nom ASC";
						elseif($country ==8)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >13 AND Longitude <57 AND Latitude >43 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
						else
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >13 AND Longitude <45 AND Latitude >43 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					}
					elseif($Front ==5)
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >-50 AND Longitude <60 AND Latitude >58.89 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Front ==3)
					{
						if(!$Faction or $G_Treve)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Flag='$country' AND Longitude >67 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
						elseif($Longitude_front <99.5)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Zone,Flag FROM Lieu WHERE Longitude >67 AND Longitude <100.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
						elseif($Longitude_front >99.5)
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Zone,Flag FROM Lieu WHERE Longitude >99.4 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
						else
							$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >67 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					}
					elseif($Lieu ==344 or $Lieu ==503) //Gibraltar
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <-5.34 AND Latitude <50 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Longitude_front <-45)
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <-10 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Longitude_front <=-10)
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <=-8 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Longitude_front <-0.5 and $Longitude_front >-10 and $Latitude_front >39.99 and $Latitude_front <48) //Golfe de Gascogne
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <-0.5 AND Latitude <48.5 (Zone=6 OR Port >0 OR Plage=1) AND ID NOT IN ('$Lieu',432,502,2223,2255) ORDER BY Nom ASC";
					elseif($Longitude_front <-5.5 and $Latitude_front <45) //C�te Ouest Afrique
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <-5.51 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Longitude_front <15.6 and $Longitude_front >2 and $Latitude_front <45.3) //Pas de Mer Adriatique si on vient de l'ouest du d�troit de Messine
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >-5.5 AND Longitude <50 AND Latitude <44.5 AND (Zone=6 OR Port>0 OR Plage=1) AND ID NOT IN ('$Lieu',198,199,449,458,459,500,505,506,507,701,750,819,1113,1180,1181,1562,2550) ORDER BY Nom ASC";
					elseif($Longitude_front >9 and $Latitude_front >53.8) //Mer Baltique
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude >7.99 AND Latitude >53.8 AND (Zone=6 OR Port>0 OR Plage=1) ORDER BY Nom ASC";
					else
						$query="SELECT ID,Nom,Longitude,Latitude,Impass,Occupant,Flag,Zone FROM Lieu WHERE Longitude <=8 AND Latitude >44 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
					$con=dbconnecti();
					$result=mysqli_query($con,$query);
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result))
						{
							$coord=0;
							$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,$data[2],$data[3]);
							if($Distance[0] <=$Autonomie_Min)
							{
								if($Front ==3)
									$CT_dep=ceil($CT_move_this+floor($Distance[0]/100));							
								else
									$CT_dep=ceil($CT_move_this+floor($Distance[0]/25));
								if($data['Zone'] ==6)
									$icone="<img src='/images/zone".$data['Zone'].".jpg'>";
								else
								{
									$icone="<img src='/images/icone_ancre".$data['Flag'].".gif'>";
									if($data['Flag'] !=$country)$CT_dep*=2;
									$CT_dep+=5;
								}									
								$Impass=$data['Impass'];
								$sensh='';
								$sensv='';
								if($Longitude_front >$data[2])
								{
									$sensh='Ouest';
									$coord+=2;
								}
								elseif($Longitude_front <$data[2])
								{
									$sensh='Est';
									$coord+=1;
								}
								if($sensh)
								{
									if($Latitude_front >$data[3]+0.5)
									{
										$sensv='Sud';	
										$coord+=20;
									}
									elseif($Latitude_front <$data[3]-0.5)
									{
										$sensv='Nord';
										$coord+=10;
									}
								}
								else
								{
									if($Latitude_front >$data[3])
									{
										$sensv='Sud';
										$coord+=20;
									}
									elseif($Latitude_front <$data[3])
									{
										$sensv='Nord';
										$coord+=10;
									}
								}
								$sens=$sensv.' '.$sensh;
								if($CT_move !=999 and $CT_dep >36)$CT_dep=36;
								if($Enis_Interdiction >0)
								{
									$mes="<p>La pr�sence de navires ennemis plus rapides rend la fuite tr�s difficile!</p>";
									$CT_dep=40;
								}
								if($Mines_m >0)
								{
									$mes="<p>La pr�sence de mines rend la travers�e plus lente!</p>";
									$CT_dep=40;
								}
								if($Lieu ==1989 and ($data[2] >1 or $data[3] <44))
									$CT_dep=99;
								elseif(($Lieu ==509 or $Lieu ==498) and $data[2] <1 and $data[3] >43.99)
									$CT_dep=99;
								if($Credits >=$CT_dep)
									$choix="<tr><td><Input type='Radio' name='Action' value='".$data[0]."_".$CT_dep."'><img src='/images/CT".$CT_dep.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- ".$data[1]."</td><td><img src='".$data['Flag']."20.gif'></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
								elseif($CT_dep <=40)
									$choix="<tr><td><Input type='Radio' name='Action' value='".$data[0]."_".$CT_dep."' disabled><img src='/images/CT".$CT_dep.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- ".$data[1]."</td><td><img src='".$data['Flag']."20.gif'></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
								elseif($CT_dep <99)
									$choix="<tr><td><Input type='Radio' name='Action' value='".$data[0]."_".$CT_dep."' disabled><img src='/images/CT".$CT_dep.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- ".$data[1]."</td><td><img src='".$data['Flag']."20.gif'></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
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
						}
						mysqli_free_result($result);
					}
					if($choix)
					{
						 /*if($Front ==2 and $Longitude_front <=10)
							$carte_txt="carte_med.php";
						 elseif($Front ==2)
							$carte_txt="carte_med_est.php";
						 elseif($Front ==3)
							$carte_txt="carte_pacifique.php";
						 elseif($Front ==4)
							$carte_txt="carte_nord_est.php";
						 elseif($Front ==5)
							$carte_txt="carte_arctic.php";
						 elseif($Front ==1)
							$carte_txt="carte_sud_est.php";
						 else
							$carte_txt="carte_ouest.php";*/
						$carte_txt="carte_ground.php?map=".$Front."&mode=1";
						$mes="<div class='btn btn-info'><a href='".$carte_txt."' onclick='window.open(this.href); return false;'>Voir la carte</a></div>
							<h2>Destinations</h2>
							<p class='lead'>Autonomie max : ".$Autonomie_Min."km <a href='#' class='popup'><img src='images/help.png'><span>La m�t�o ou la coque endommag�e peuvent r�duire la distance maximale</span></a></p>
							<form action='index.php?view=ground_move' method='post'>
							<input type='hidden' name='Move_Type' value='".$Action."'>
							<div class='row'><div class='col-md-8'><table class='table'>
							<tr>
							<td width='30%'><table><tr><th colspan='3'>Nord Ouest</th></tr>".$NO_txt."</table></td>
							<td width='30%'><table><tr><th colspan='3'>Nord</th></tr>".$Nord_txt."</table></td>
							<td width='30%'><table><tr><th colspan='3'>Nord Est</th></tr>".$NE_txt."</table></td>
							</tr>
							<tr>
							<td width='30%'><table><tr><th colspan='3'>Ouest</th></tr>".$Ouest_txt."</table></td>
							<td width='30%' align='center'><img src='images/travel_icon.png'></td>
							<td width='30%'><table><tr><th colspan='3'>Est</th></tr>".$Est_txt."</table></td>
							</tr>
							<tr>
							<td width='30%'><table><tr><th colspan='3'>Sud Ouest</th></tr>".$SO_txt."</table></td>
							<td width='30%'><table><tr><th colspan='3'>Sud</th></tr>".$Sud_txt."</table></td>
							<td width='30%'><table><tr><th colspan='3'>Sud Est</th></tr>".$SE_txt."</table></td>
							</tr>
							</table>
							<Input type='Radio' name='Action' value='0' checked>- Annuler le d�placement.<br>
							<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div></div>";
					}
					else
						$mes="<br>Vous retournez � votre point de d�part, aucune destination n'�tant disponible dans un rayon de ".$Autonomie_Min."km!<br>Si la m�t�o est mauvaise, attendez des cieux plus cl�ments (la m�t�o change toutes les 2h)";	
				}
			}
		}
		elseif($Action ==107)
		{
			if($Credits >=4)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Visible=0,Camouflage=2 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-4,"ID",$OfficierID);
				$mes='<br>Vous camouflez vos navires';
				$img="camouflage_navire";
			}
		}
		elseif($Action ==109)
		{
			if($Credits >=$Credits_smoke)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Visible=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_smoke,"ID",$OfficierID);
				$mes='<br>Vos navires produisent un �cran de fum�e pour se dissimuler';
				$img="smokescreen";
			}
		}
		elseif($Action ==108)
		{
			if($Division >0)
				$Retraite=GetData("Division","ID",$Division,"Base");
			else
			{
				$Latitude_front=GetData("Lieu","ID",$Lieu,"Latitude");
				$Retraite=Get_Retraite($Front,$country,$Latitude_front);
			}
			if(!$Retraite)
			{
				if($country ==1)
					$Retraite=216;
				elseif($country ==2)
					$Retraite=344;
				elseif($country ==4)
					$Retraite=203;	
				elseif($country ==6)
					$Retraite=458;	
				elseif($country ==7)
					$Retraite=1366;	
				elseif($country ==9)
					$Retraite=1368;
			}
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=4,Move=1,Camouflage=1,Fret=0,Fret_Qty=0,Visible=0,Lieu_ID='$Retraite' WHERE Officier_ID='$OfficierID' OR Officier_ID='$Transit'");
			$reset2=mysqli_query($con,"UPDATE Officier SET Train_Lieu=0,Transit=0,Reputation=Reputation-10 WHERE ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-40,"ID",$OfficierID);
			$mes='<br>Vous rejoignez votre port d\'attache, d�fait et honteux !';
			$img="objectif8";
		}
		elseif($Action ==110)
		{
			if($Credits >=$Credits_ret and $Credits_ret >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=20,Visible=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_ret,"ID",$OfficierID);
				$mes="<br>Vos navires se dispersent";
				$img="nav_disperse";
			}
		}
		elseif($Action ==111)
		{
			if($Credits >=$Credits_ret and $Credits_ret >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=21,Visible=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_ret,"ID",$OfficierID);
				$mes="<br>Vos navires d'escorte �tablissent une ligne de d�fense";
				$img="nav_line";
			}
		}
		elseif($Action ==112)
		{
			if($Credits >=$Credits_ret and $Credits_ret >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=22,Visible=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_ret,"ID",$OfficierID);
				$mes="<br>Vos navires se placent en formation d'�vasion";
				$img="nav_evade";
			}
		}
		elseif($Action ==113)
		{
			if($Credits >=$Credits_app and $Credits_app >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=23 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_app,"ID",$OfficierID);
				$mes="<br>Vos navires se placent en position d'appui";
				$img="nav_appui";
			}
		}
		elseif($Action ==114)
		{
			if($Credits >=$Credits_ret and $Credits_ret >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=24 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_ret,"ID",$OfficierID);
				$mes="<br>Vos navires se placent en formation ASM";
				$img="nav_asm";
			}
		}
		elseif($Action ==116)
		{
			if($Credits >=$Credits_plonge and $Credits_plonge >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=25,Visible=0,Camouflage=4 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_plonge,"ID",$OfficierID);
				$mes="<br>Vos sous-marins plongent";
				$img="plongee";
			}
		}
		elseif($Action ==117)
		{
			if($Credits >=40)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Move=0,Position=26 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				$mes='<br>Vous placez des filets anti-torpilles pour prot�ger vos navires';
				$img="camouflage_navire";
			}
		}
		elseif($Action ==118)
		{
			if($Credits >=40)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Move=1,Position=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				$mes='<br>Vous retirez les filets anti-torpilles de vos navires';
				$img="camouflage_navire";
			}
		}
		elseif($Action ==119)
		{
			if($Credits >=$Credits_ret and $Credits_ret >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Camouflage=1 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_ret,"ID",$OfficierID);
				$mes="<br>Vos sous-marins font surface";
				$img="plongee";
			}
		}
		elseif($Action ==120)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=8 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos navires quittent le port';
			$img="a_quai";
		}
		elseif($Action ==121)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Move=1,Camouflage=1,Placement=4 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos navires rejoignent le port';
			$img="a_quai";
		}
		elseif($Action ==122)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Move=1,Placement=4 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-8,"ID",$OfficierID);
			$mes='<br>Vos navires p�n�trent dans le port';
			$img="a_quai";
		}
		elseif($Action ==123)
		{
			if($Credits >=$Credits_ret and $Credits_ret >0)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=27 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-$Credits_ret,"ID",$OfficierID);
				$mes="<br>Vos navires se placent en formation d'interdiction";
				$img="nav_evade";
			}
		}
		elseif($Action ==124) //$Fluvial
		{
			if($Credits >=24)
			{
				$img="move_front".$country;
				$Merzlota=false;
				$Mois=substr($Date_Campagne,5,2);
				if(($Pays_Ori ==8 or $Pays_Ori ==20 or $Front ==5) and ($Mois ==12 or $Mois ==1 or $Mois ==2))$Merzlota=true;					
				if($Merzlota)
					$mes="<br>Le gel emp�che tout d�placement fluvial dans la r�gion!";
				else
				{
					$query="SELECT l.ID,l.Nom,l.Longitude,l.Latitude,l.Flag,l.Zone FROM Lieu as l,Pays as p WHERE l.Flag_Pont=p.ID AND p.Faction='$Faction' AND l.ID IN ('$Fleuve_1','$Fleuve_2','$Fleuve_3') ORDER BY l.Nom ASC";
					$con=dbconnecti();
					$result=mysqli_query($con,$query);
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result))
						{
							$coord=0;
							$sensh='';
							$sensv='';
							if($data[0] >0)
							{
								if($Longitude_front > $data[2])
								{
									$sensh='Ouest';
									$coord+=2;
								}
								elseif($Longitude_front < $data[2])
								{
									$sensh='Est';
									$coord+=1;
								}
								if($sensh)
								{
									if($Latitude_front > $data[3]+0.25)
									{
										$sensv='Sud';	
										$coord+=20;
									}
									elseif($Latitude_front < $data[3]-0.25)
									{
										$sensv='Nord';
										$coord+=10;
									}
								}
								else
								{
									if($Latitude_front > $data[3])
									{
										$sensv='Sud';
										$coord+=20;
									}
									elseif($Latitude_front < $data[3])
									{
										$sensv='Nord';
										$coord+=10;
									}
								}
								$sens=$sensv.' '.$sensh;
								$icone="<img src='/images/zone".$data['Zone'].".jpg'>";
								$choix="<tr><td><Input type='Radio' name='Action' value='".$data[0]."_24'><img src='/images/CT24.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- ".$data[1]."</td><td><img src='".$data['Flag']."20.gif'></td><td>".$icone."</td></tr>";
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
						}
						mysqli_free_result($result);
					}
					if($choix)
					{
						$carte_txt="carte_ground.php?map=".$Front."&mode=9";
						$mes="<div class='btn btn-info'><a href='".$carte_txt."' onclick='window.open(this.href); return false;'>Voir la carte</a></div>
							<h2>Destinations</h2>
							<p class='lead'>Le d�placement fluvial ne peut se faire qu'entre lieux situ�s sur un m�me fleuve dont le pont strat�gique ou les berges du fleuve sont contr�l�es par votre faction</span></p>
							<form action='index.php?view=ground_move' method='post'>
							<input type='hidden' name='Move_Type' value='".$Action."'>
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
							<Input type='Radio' name='Action' value='0' checked>- Annuler le d�placement.<br>
							<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div></div>";
					}
					else
						$mes="<br>Vous restez sur les berges du fleuve, les destinations sur ce fleuve ne sont pas contr�l�es par votre faction.";
				}
			}
		}
		elseif($Action ==298)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=0,Visible=0,Move=1,Camouflage=1,Placement=11,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes rejoignent la plage';
			$img="defense";
		}
		elseif($Action ==299)
		{
			if($Credits >=4)
			{
				if(!$Transit)$Transit=GetData("Officier","ID",$OfficierID,"Transit");
				if($Transit >0)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Regiment SET Position=4,Placement=4,Camouflage=1,Move=1,Visible=0,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$Transit'");
					$reset2=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0 WHERE Officier_ID='$OfficierID' AND Vehicule_ID=5000");
					$ok=mysqli_query($con,"UPDATE Officier SET Barges_Lieu=0 WHERE ID='$Transit'");
					$ok2=mysqli_query($con,"UPDATE Officier SET Transit=0 WHERE ID='$OfficierID'");
					mysqli_close($con);
					UpdateData("Officier","Credits",-4,"ID",$OfficierID);
					$mes="<p>Vous d�barquez les troupes dans le port.</p>";
					$img="dechargement";	
				}
				else
					$mes="erreur transit";
			}
		}
		elseif($Action ==300)
		{
			if($Credits >=40)
			{
				$Heure=date('H');
				if(!$Transit)$Transit=GetData("Officier","ID",$OfficierID,"Transit");
				if($Heure <9)$Heure=9;
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment SET Position=4,Placement=11,Camouflage=1,Move=0,Visible=1,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$Transit'");
				$reset2=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0 WHERE Officier_ID='$OfficierID' AND Vehicule_ID=5000");
				$ok=mysqli_query($con,"UPDATE Officier SET Barges_Lieu=0,Credits=0,Heure_Para='$Heure' WHERE ID='$Transit'");
				$ok2=mysqli_query($con,"UPDATE Officier SET Transit=0 WHERE ID='$OfficierID'");
				mysqli_close($con);
				UpdateData("Officier","Credits",-40,"ID",$OfficierID);
				$mes="<p>Vous d�barquez les troupes sur les plages!</p>";
				$img="debarquement";	
				if(GetData("Lieu","ID",$Cible,"Flag") !=$country)
					AddEventFeed(432,$Transit,$OfficierID,$Reg,$Cible,$country);
			}
		}
		elseif($Action ==301)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)
				$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)
					$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)
					$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Front ==2 and $Sec_Front >0 and $Parrain_Off_Front >0 and $Parrain_Sec_Off_Front >0)
			{
				$Front_dest=0;
				$Front_txt=" ouest";
			}
			elseif($Front ==0 and $Sec_Front !=2 and $Parrain_Off_Front !=2 and $Parrain_Sec_Off_Front !=2)
			{
				$Front_dest=2;
				$Front_txt=" m�diterran�en";
			}
			else
				$Changer_Front=false;
			if($Changer_Front)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-1 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
			}
			else
				$mes='<br>Votre officier ne peut pas rejoindre le m�me front que celui de votre second officier';
			$img="dechargement";
		}
		elseif($Action ==302)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)
				$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)
					$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)
					$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Front ==4 and $Sec_Front >0 and $Parrain_Off_Front >0 and $Parrain_Sec_Off_Front >0)
			{
				$Front_dest=0;
				$Front_txt=" ouest";
			}
			elseif($Front ==0 and $Sec_Front !=4 and $Parrain_Off_Front !=4 and $Parrain_Sec_Off_Front !=4)
			{
				$Front_dest=4;
				$Front_txt=" nord-est";
			}
			else
				$Changer_Front=false;
			if($Changer_Front)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-1 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
			}
			else
				$mes='<br>Votre officier ne peut pas rejoindre le m�me front que celui de votre second officier';
			$img="dechargement";
		}
		elseif($Action ==303)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)
				$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)
					$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)
					$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Front ==3 and $Sec_Front !=2 and $Parrain_Off_Front !=2 and $Parrain_Sec_Off_Front !=2)
			{
				$Front_dest=2;
				$Front_txt=" m�diterran�en";
			}
			elseif($Front ==2 and $Sec_Front !=3 and $Parrain_Off_Front !=3 and $Parrain_Sec_Off_Front !=3)
			{
				$Front_dest=3;
				$Front_txt=" pacifique";
			}
			else
				$Change_Front=false;
			if($Changer_Front)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-1 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
			}
			else
				$mes='<br>Votre officier ne peut pas rejoindre le m�me front que celui de votre second officier';
			$img="dechargement";
		}
		elseif($Action ==304)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Credits >=40 and $Sec_Front >0 and $Parrain_Off_Front >0 and $Parrain_Sec_Off_Front >0)
			{
				$Front_dest=0;
				$Front_txt=" ouest";
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-40 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				$reset3=mysqli_query($con,"UPDATE Regiment SET Lieu_ID=2149,Placement=4,Position=4,Move=1,Camouflage=1,Visible=0,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
				$img="dechargement";
			}
		}
		elseif($Action ==305)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)
					$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)
					$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Credits >=40 and $Sec_Front !=3 and $Parrain_Off_Front !=3 and $Parrain_Sec_Off_Front !=3)
			{
				$Front_dest=3;
				$Front_txt=" pacifique";
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-40 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				$reset3=mysqli_query($con,"UPDATE Regiment SET Lieu_ID=1567,Placement=4,Position=4,Move=1,Camouflage=1,Visible=0,Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
				$img="dechargement";
			}
		}
		elseif($Action ==306)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)
				$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)
					$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)
					$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Front ==1 and $Sec_Front !=4 and $Parrain_Off_Front !=4 and $Parrain_Sec_Off_Front !=4)
			{
				$Front_dest=4;
				$Front_txt=" nord-est";
			}
			elseif($Front ==4 and $Sec_Front !=1 and $Parrain_Off_Front !=1 and $Parrain_Sec_Off_Front !=1)
			{
				$Front_dest=1;
				$Front_txt=" sud-est";
			}
			else
				$Change_Front=false;
			if($Changer_Front)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-1 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
			}
			else
				$mes='<br>Votre officier ne peut pas rejoindre le m�me front que celui de votre second officier';
			$img="dechargement";
		}
		elseif($Action ==307)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)
				$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)
					$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)
					$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Front ==5 and $Sec_Front >0 and $Parrain_Off_Front >0 and $Parrain_Sec_Off_Front >0)
			{
				$Front_dest=0;
				$Front_txt=" ouest";
			}
			elseif($Front ==0 and $Sec_Front !=5 and $Parrain_Off_Front !=5 and $Parrain_Sec_Off_Front !=5)
			{
				$Front_dest=5;
				$Front_txt=" arctique";
			}
			else
				$Change_Front=false;
			if($Changer_Front)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-1 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
			}
			else
				$mes='<br>Votre officier ne peut pas rejoindre le m�me front que celui de votre second officier';
			$img="dechargement";
		}
		elseif($Action ==308)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)
				$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)
					$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)
					$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Front ==1 and $Sec_Front >0 and $Parrain_Off_Front >0 and $Parrain_Sec_Off_Front >0)
			{
				$Front_dest=0;
				$Front_txt=" ouest";
			}
			elseif($Front ==0 and $Sec_Front !=1 and $Parrain_Off_Front !=1 and $Parrain_Sec_Off_Front !=1)
			{
				$Front_dest=1;
				$Front_txt=" est";
			}
			else
				$Change_Front=false;
			if($Changer_Front)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-1 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
			}
			else
				$mes='<br>Votre officier ne peut pas rejoindre le m�me front que celui de votre second officier';
			$img="dechargement";
		}
		elseif($Action ==309)
		{
			$Changer_Front=true;
			$Sec_Front=99;
			$Parrain_Off_Front=99;
			$Parrain_Sec_Off_Front=99;
			$con=dbconnecti();
			$resultj=mysqli_query($con,"SELECT Officier_naval,Parrain FROM Joueur WHERE ID='$AccountID'");
			mysqli_close($con);
			if($resultj)
			{
				while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
				{
					$Sec_Off=$data['Officier_naval'];
					$Parrain=$data['Parrain'];
				}
				mysqli_free_result($resultj);
			}
			if($Sec_Off >0)
				$Sec_Front=GetData("Officier","ID",$Sec_Off,"Front");
			if($Parrain >0)
			{
				$con=dbconnecti();
				$resultj=mysqli_query($con,"SELECT Pays,Actif,Officier,Officier_naval FROM Joueur WHERE ID='$Parrain'");
				mysqli_close($con);
				if($resultj)
				{
					while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
					{
						if($data['Actif'] !=1 and $country ==$data['Pays'])
						{
							$Parrain_Off=$data['Officier'];
							$Parrain_Sec_Off=$data['Officier_naval'];
						}
					}
					mysqli_free_result($resultj);
				}
				if($Parrain_Off >0)
					$Parrain_Off_Front=GetData("Officier","ID",$Parrain_Off,"Front");
				if($Parrain_Sec_Off >0)
					$Parrain_Sec_Off_Front=GetData("Officier","ID",$Parrain_Sec_Off,"Front");
			}
			if($Front ==2 and $Sec_Front !=1 and $Parrain_Off_Front !=1 and $Parrain_Sec_Off_Front !=1)
			{
				$Front_dest=1;
				$Front_txt=" est";
			}
			elseif($Front ==1 and $Sec_Front !=2 and $Parrain_Off_Front !=2 and $Parrain_Sec_Off_Front !=2)
			{
				$Front_dest=2;
				$Front_txt=" med";
			}
			else
				$Change_Front=false;
			if($Changer_Front)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Front='$Front_dest',Division=0,Credits=Credits-1 WHERE ID='$OfficierID'");
				$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$OfficierID'");
				mysqli_close($con);
				$mes='<br>Vos troupes rejoignent les effectifs du front'.$Front_txt;
			}
			else
				$mes='<br>Votre officier ne peut pas rejoindre le m�me front que celui de votre second officier';
			$img="dechargement";
		}
		elseif($Action ==400)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Position=6,Move=1,Camouflage=1,Visible=0,Moral=0,Fret=0,Fret_Qty=0,Vehicule_Nbr=0,Experience=0,HP=0,
			Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,
			Stock_Munitions_105=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
			Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0 WHERE Officier_ID='$OfficierID'");
			$reset2=mysqli_query($con,"UPDATE Officier SET Train_Lieu=0,Reputation=Reputation-100 WHERE ID='$OfficierID'");
			mysqli_close($con);
			UpdateData("Officier","Credits",-1,"ID",$OfficierID);
			$mes='<br>Vos troupes se rendent!';
			$img="retreat";
		}
		elseif($Action ==401)
		{
			$Regs="";
			$Cie="";
			$titre="Ravitaillement";
			$img="logistics";
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT * FROM Regiment WHERE Officier_ID='$OfficierID' AND Vehicule_Nbr >0");
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Regs.="<tr><td>".$data['ID']."e Cie</td><td>".$data['Vehicule_Nbr']." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'></td>
					<td>".$data['Stock_Munitions_8']."/50000</td><td>".$data['Stock_Munitions_13']."/30000</td><td>".$data['Stock_Munitions_20']."/20000</td><td>".$data['Stock_Munitions_30']."/20000</td>
					<td>".$data['Stock_Munitions_40']."/10000</td><td>".$data['Stock_Munitions_50']."/10000</td><td>".$data['Stock_Munitions_60']."/10000</td><td>".$data['Stock_Munitions_75']."/5000</td>
					<td>".$data['Stock_Munitions_90']."/2500</td><td>".$data['Stock_Munitions_105']."/1500</td><td>".$data['Stock_Munitions_125']."/1000</td><td>".$data['Stock_Munitions_150']."/1000</td>
					<td>".$data['Stock_Essence_87']."/25000</td><td>".$data['Stock_Essence_1']."</td></tr>";
					$Cie.="<option value='".$data['ID']."'>".$data['ID']."e Cie</option>";
				}
				mysqli_free_result($result);
				unset($data);
			}		
			$mes="<h2>Stocks des Compagnies</h2>
				<div style='overflow:auto; width: 100%;'><table class='table'><thead><tr><th>Cie</th><th>V�hicules / Troupes</th>
				<th>8mm</th><th>13mm</th><th>20mm</th><th>30mm</th><th>40mm</th><th>50mm</th><th>60mm</th><th>75mm</th><th>90mm</th><th>105mm</th><th>125mm</th><th>150mm</th>
				<th>Essence</th><th>Diesel</th></tr></thead>".$Regs."</table></div>";
			if($Credits >=2)
			{
				$mes.="<h2>Gestion des stocks <img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'></h2>
					<form action='index.php?view=ground_ravitin1' method='post'>
					<table class='table'><thead><tr><th>Type</th><th>Compagnie transf�rant</th><th>Compagnie recevant</th><th></th></tr></thead>
					<tr><td><select name='Type_Stock' class='form-control' style='width: 200px'><option value='8'>8mm</option><option value='13'>13mm</option><option value='20'>20mm</option><option value='30'>30mm</option>
					<option value='40'>40mm</option><option value='50'>50mm</option><option value='60'>60mm</option><option value='75'>75mm</option><option value='90'>90mm</option><option value='105'>105mm</option>
					<option value='125'>125mm</option><option value='150'>150mm</option><option value='1087'>Essence</option><option value='1001'>Diesel</option></select></td>
					<td><select name='Cie_ori' class='form-control' style='width: 200px'>".$Cie."</select></td>
					<td><select name='Cie_dest' class='form-control' style='width: 200px'>".$Cie."</select></td>
					<td><input type='Submit' value='Transf�rer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr></table>
					<p class='lead'>Le transfert est un partage 50/50 des stocks. La compagnie transf�rant verra son stock divis� par deux, tandis que la compagnie recevant verra son stock augment� de la moiti� du stock de la compagnie transf�rant.</p>";
			}
			else
				$mes.="Vous manquez de temps pour cela !";
		}
		$img=Afficher_Image('images/'.$img.'.jpg',"images/image.png","");
	}
	$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
	include_once('./default.php');
}
?>