<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_avions.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0)
	{
		$Saison=$_SESSION['Saison'];				
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Avancement,Credits,Unit,Front FROM Pilote WHERE ID='$PlayerID'");
		$resultu=mysqli_query($con,"SELECT Pays,Commandant,Officier_Adjoint,Officier_Technique FROM Unit WHERE ID='$Unite'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avancement=$data['Avancement'];
				$Credits=$data['Credits'];
				$Unite=$data['Unit'];
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($resultu)
		{
			while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
			{
				$Pays_u=$datau['Pays'];
				$Commandant=$datau['Commandant'];
				$Officier_Adjoint=$datau['Officier_Adjoint'];
				$Officier_Technique=$datau['Officier_Technique'];
			}
			mysqli_free_result($resultu);
			unset($datau);
		}
		$Grade=GetAvancement($Avancement,$country);		
		if($Avancement >24999 or $PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique)
		{
			function GetFactoryCT($Avion1,$CT_Avion1,$Date_Campagne,$country)
			{
				$Kaput=false;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Usine1,Usine2,Usine3,Fin_Prod,Production,Stock,Reserve FROM Avion WHERE ID='$Avion1'");
				$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Avion1' AND PVP=1"),0);
				$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Avion1'"),0);
				$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$Avion1' AND Etat=1"),0);
				$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$Avion1' AND Etat=1"),0);
				$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$Avion1' AND Etat=1"),0);
				mysqli_close($con);
				$con=dbconnecti(4);
				$Perdu=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Events_Pertes IN (11,12,34,221,222,231) AND Avion='$Avion1' AND Avion_Nbr >0"),0);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Usine1=$data['Usine1'];
						$Usine2=$data['Usine2'];
						$Usine3=$data['Usine3'];
						$Fin_Prod1=$data['Fin_Prod'];
						$Production=$data['Stock'];
						$Reserve=$data['Reserve'];
					}
					mysqli_free_result($result);
				}					
				if($country ==20)
				{
					if($Avion1 ==4)
						$Production=12;
					elseif($Avion1 == 6)
						$Production=15;
					elseif($Avion1 ==10)
						$Production=76;
					elseif($Avion1 ==13)
						$Production=15;
					elseif($Avion1 ==19)
						$Production=97;
					elseif($Avion1 == 26)
						$Production=30;
					elseif($Avion1 == 48)
						$Production=11;
					elseif($Avion1 == 63)
						$Production=35;
					elseif($Avion1 == 82)
						$Production=7;
					elseif($Avion1 ==146)
						$Production=97;
					elseif($Avion1 == 202)
						$Production=21;
					elseif($Avion1 == 209)
						$Production=24;
					elseif($Avion1 == 211)
						$Production=11;
					elseif($Avion1 == 262)
						$Production=11;
					elseif($Avion1 == 291)
						$Production=44;
					elseif($Avion1 == 292)
						$Production=22;
					elseif($Avion1 == 293)
						$Production=39;
				}
				elseif($country ==8)
				{
					if($Avion1 ==158)
						$Production=931;
				}					
				if(($DCA + $Abattu + $Perdu + $Service1 + $Service2 + $Service3 - $Reserve) >=$Production)
					$Kaput=true;					
				if($Kaput)
					$CT_Avion1=50;
				elseif($Fin_Prod1 <$Date_Campagne)
					$CT_Avion1=12;
				else
				{
					$Indus1=GetData("Lieu","ID",$Usine1,"Industrie");
					if($Indus1 <10)
						$CT_Avion1=50;
					elseif($Indus1 <25)
						$CT_Avion1*=4;
					elseif($Indus1 <50)
						$CT_Avion1*=2;
					if($Usine2)
					{
						$Indus2=GetData("Lieu","ID",$Usine2,"Industrie");
						if($Indus2 <10)
							$CT_Avion1=50;
						elseif($Indus2 <25)
							$CT_Avion1*=4;
						elseif($Indus2 <50)
							$CT_Avion1*=2;
					}
					if($Usine3)
					{
						$Indus3=GetData("Lieu","ID",$Usine3,"Industrie");
						if($Indus3 <10)
							$CT_Avion1=50;
						elseif($Indus3 <25)
							$CT_Avion1*=4;
						elseif($Indus3 <50)
							$CT_Avion1*=2;
					}
				}
				if($CT_Avion1 >50)$CT_Avion1=50;
				return $CT_Avion1;
			}
			if($Front or $Pays_u ==7)
				$Rayon_Depots=750;
			else
				$Rayon_Depots=500;
			//$con=dbconnecti();
			$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
			$result=mysqli_query($con,"SELECT Nom,Type,Pays,Reputation,Base,Station_Meteo,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,
			Stock_Essence_87,Stock_Essence_100,Stock_Essence_130,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,Stock_Munitions_40,Stock_Munitions_75,Stock_Munitions_90,
			Bombes_30,Bombes_50,Bombes_80,Bombes_125,Bombes_250,Bombes_300,Bombes_400,Bombes_500,Bombes_800,Bombes_1000,Bombes_2000 FROM Unit WHERE ID='$Unite'");
			/*$result3=mysqli_query($con,"SELECT o.ID,o.Nom,o.Division,c.mobile FROM Officier as o,Regiment as r,Cible as c WHERE o.Pays='$country' AND o.Front='$Front' AND o.Actif=0 AND o.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() 
			AND r.Officier_ID=o.ID AND r.Vehicule_ID=c.ID AND c.Charge >0 AND r.Vehicule_ID<>5000 AND r.Vehicule_Nbr >0");*/
			$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
			//mysqli_close($con);
			if($results)
			{
				while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
				{
					$Skills_Pil[]=$data['Skill'];
				}
				mysqli_free_result($results);
			}
			if(is_array($Skills_Pil))
			{
				if(in_array(95,$Skills_Pil))
					$Rayon_Depots*=2;
			}
			/*if($result3)
			{
				while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					if($data['ID'] !=$id_res)
					{
						if($data['Division'])
							$Division=GetData("Division","ID",$data['Division'],"Nom");
						else
							$Division="Sans division";
						if($data['mobile'] ==5)
							$Division.=" - naval";					
						$Ravit_Off_txt.="<option value=".$data['ID'].">".$data['Nom']." (".$Division.")</option>";
					}
					$id_res=$data['ID'];
				}
				mysqli_free_result($result3);
			}*/
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Unite_Nom=$data['Nom'];
					$Unite_Type=$data['Type'];
					$Unite_Reput=$data['Reputation'];
					$Pays=$data['Pays'];
					$Base=$data['Base'];
					$Station_Meteo=$data['Station_Meteo'];
					$Avion1=$data['Avion1'];
					$Avion2=$data['Avion2'];
					$Avion3=$data['Avion3'];
					$Avion1_nbr=$data['Avion1_Nbr'];
					$Avion2_nbr=$data['Avion2_Nbr'];
					$Avion3_nbr=$data['Avion3_Nbr'];
					$Stock_Essence_87=$data['Stock_Essence_87'];
					$Stock_Essence_100=$data['Stock_Essence_100'];
					$Stock_Essence_130=$data['Stock_Essence_130'];
					$Stock_Essence_1=$data['Stock_Essence_1'];
					$Stock_Munitions_8=$data['Stock_Munitions_8'];
					$Stock_Munitions_13=$data['Stock_Munitions_13'];
					$Stock_Munitions_20=$data['Stock_Munitions_20'];
					$Stock_Munitions_30=$data['Stock_Munitions_30'];
					$Stock_Munitions_40=$data['Stock_Munitions_40'];
					$Stock_Munitions_75=$data['Stock_Munitions_75'];
					$Stock_Munitions_90=$data['Stock_Munitions_90'];
					$Bombes_30=$data['Bombes_30'];
					$Bombes_50=$data['Bombes_50'];
					$Bombes_80=$data['Bombes_80'];
					$Bombes_125=$data['Bombes_125'];
					$Bombes_250=$data['Bombes_250'];
					$Bombes_300=$data['Bombes_300'];
					$Bombes_400=$data['Bombes_400'];
					$Bombes_500=$data['Bombes_500'];
					$Bombes_800=$data['Bombes_800'];
					$Bombes_1000=$data['Bombes_1000'];
					$Bombes_2000=$data['Bombes_2000'];
				}
				mysqli_free_result($result);
				unset($data);
			}					
			//$con=dbconnecti();
			$Avion1_nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Avion WHERE ID='$Avion1'"),0);
			$Avion2_nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Avion WHERE ID='$Avion2'"),0);
			$Avion3_nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Avion WHERE ID='$Avion3'"),0);
			$result=mysqli_query($con,"SELECT Nom,Camouflage,QualitePiste,BaseAerienne,LongPiste,Zone,Tour,Latitude,Longitude,Citernes,Camions,Meteo FROM Lieu WHERE ID='$Base'");
			$result2=mysqli_query($con,"SELECT DISTINCT ID,Nom,Base,Type FROM Unit WHERE Type IN (1,2,3,4,5,7,9,10) AND Pays='$country' AND Etat=1 AND ID<>'$Unite' ORDER BY Type ASC,Nom ASC");
			$result3=mysqli_query($con,"SELECT COUNT(*),SUM(Industrie) FROM Lieu WHERE Flag='$country' AND TypeIndus<>'' AND Flag_Usine='$country'");
			//mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Base_Nom=$data['Nom'];
					$Camouflage=$data['Camouflage'];
					$QualitePiste=$data['QualitePiste'];
					$BaseAerienne=$data['BaseAerienne'];
					$LongPiste=$data['LongPiste'];
					$Zone=$data['Zone'];
					$Tour=$data['Tour'];
					$Base_Lat=$data['Latitude'];
					$Base_Long=$data['Longitude'];
					$Citernes=$data['Citernes'];
					$Camions=$data['Camions'];
					$Meteo=$data['Meteo'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			//Unites
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
				{
					$Dist_chk=GetDistance($Base,$data['Base']);
					if($Dist_chk[0] <500)
					{
						$Type=GetAvionType($data['Type']);
						$Lieu=GetData("Lieu","ID",$data['Base'],"Nom");
						$Lieux.="<option value=".$data['ID'].">".$data['Nom']." ( ".$Type." - ".$Lieu." )</option>";
					}
				}
				mysqli_free_result($result2);
			}			
			//CT Avions
			if($result3)
			{
				if($data=mysqli_fetch_array($result3,MYSQLI_NUM))
				{
					if($data[0])
					{
						$Efficacite_prod_base=round($data[1]/$data[0]);
						$Efficacite_prod=round((100-($data[1]/$data[0]))/10);
					}
					else
					{
						$Efficacite_prod_base=0;
						$Efficacite_prod=0;
					}
				}
				mysqli_free_result($result3);
			}		
			$Credits_Piste=ceil((100-$QualitePiste)/2);
			if($Credits_Piste >40)$Credits_Piste=40;
			//Porte-avions
			if($Zone ==6)
			{
				$QualitePiste=100;
				$LongPiste=200;
			}
			$LongPiste*=($QualitePiste/100);
			if($BaseAerienne ==3)
			{
				$terrain="Le terrain";
				if($Zone ==8)
					$QualitePiste_img="piste38_".GetQualitePiste_img($QualitePiste).".jpg";
				if($Zone ==0 or $Zone ==2 or $Zone ==3 or $Zone ==9 or $Zone ==11)
					$QualitePiste_img="piste32_".GetQualitePiste_img($QualitePiste).".jpg";
				else
					$QualitePiste_img="piste31_".GetQualitePiste_img($QualitePiste).".jpg";
			}
			else
			{
				$terrain="La piste";
				$QualitePiste_img="piste".$BaseAerienne."_".GetQualitePiste_img($QualitePiste).".jpg";
			}	
			$Sqn=GetSqn($country);			
			//Outre-Mer ou anglais
			if($Base_Lat <38.2 or $Base_Long >70 or $Pays ==2 or $Zone ==6)
			{
				//$con=dbconnecti();
				$result=mysqli_query($con,"SELECT COUNT(*),SUM(Port) FROM Lieu WHERE Flag='$country' AND Port_Ori >0 AND Flag_Port='$country'");
				$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$country'");
				//mysqli_close($con);
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
				$Lat_base_min=$Base_Lat-1;
				$Lat_base_max=$Base_Lat+1;
				$Long_base_min=$Base_Long-3;
				$Long_base_max=$Base_Long+3;			
				//$con=dbconnecti();
				$result=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$country'");
				$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND Flag_Gare='$country' 
				AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max')");
				//mysqli_close($con);
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
				$Efficacite_ravit=round(($Efficacite_ravit1 + ($Efficacite_ravit2*2))/3);
			}
			//Malus ravitaillement par saison ou terrain
			if($Base_Long >20 and $Base_Lat >43)	//Front Est
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
			//Usines avion
			$CT_Avion1=round(7+$Efficacite_prod+((500-GetData("Avion","ID",$Avion1,"Production"))/100));
			if($CT_Avion1 <7)$CT_Avion1=7;
			$CT_Avion1=GetFactoryCT($Avion1,$CT_Avion1,$Date_Campagne,$country);
			if($Avion2 ==$Avion1)
				$CT_Avion2=$CT_Avion1;
			else
			{
				$CT_Avion2=round(7+$Efficacite_prod+((500-GetData("Avion","ID",$Avion2,"Production"))/100));
				if($CT_Avion2 <7)$CT_Avion2=7;
				$CT_Avion2=GetFactoryCT($Avion2,$CT_Avion2,$Date_Campagne,$country);
			}
			if($Avion3 ==$Avion1)
				$CT_Avion3=$CT_Avion1;
			elseif($Avion3 ==$Avion2)
				$CT_Avion3=$CT_Avion2;
			else
			{
				$CT_Avion3=round(7+$Efficacite_prod+((500-GetData("Avion","ID",$Avion3,"Production"))/100));
				if($CT_Avion3 <7)$CT_Avion3=7;
				$CT_Avion3=GetFactoryCT($Avion3,$CT_Avion3,$Date_Campagne,$country);
			}
			//Limites type d'unité
			$Max_stock=50000;
			$Max_avions=GetMaxFlight($Unite_Type,$Unite_Reput,0);
			//Demande ravit officier
			if($Ravit_Off_txt !="")
			{
				if($Date_Campagne >'1944-01-01')
					$Muns_txt="<option value='37'>APDS (AT)</option><option value='38'>HEAT (Soutien courte portée)</option>";
				elseif($Date_Campagne >'1941-01-01')
					$Muns_txt="<option value='38'>HEAT (Soutien courte portée)</option>";				
				$output_ravit="<h3>Demande de ravitaillement terrestre</h3><form action='index.php?view=esc_gestion3' method='post'>
					<input type='hidden' name='Officier' value='".$PlayerID."'>
					<input type='hidden' name='Cie' value='".$Unite."'>
					<input type='hidden' name='Cible' value='".$Base."'>
					<table class='table'>
					<thead><tr><th>Officier</th><th>Chargement</th><th>Quantité</th></tr></thead>
				<tr><td><select name='Ravit_Off' class='form-control'><option value='0'>- Aucun -</option>".$Ravit_Off_txt."</select></td>
				<td align='left'><select name='Charge' class='form-control'>
				<option value='8'>8mm</option>
				<option value='13'>13mm</option>
				<option value='20'>20mm</option>
				<option value='30'>30mm</option>
				<option value='40'>40mm</option>
				<option value='50'>50mm</option>
				<option value='60'>60mm</option>
				<option value='75'>75mm</option>
				<option value='90'>90mm</option>
				<option value='105'>105mm</option>
				<option value='125'>125mm</option>
				<option value='150'>150mm</option>
				<option value='9125'>Bombes 125kg</option>
				<option value='250'>Bombes 250kg</option>
				<option value='500'>Bombes 500kg</option>
				<option value='300'>Charges</option>
				<option value='400'>Mines</option>
				<option value='800'>Torpilles</option>
				<option value='87'>Essence 87 Octane</option>
				<option value='100'>Essence 100 Octane</option>
				<option value='1'>Diesel</option>
				</select></td>				
				<td align='left'><select name='Qty' class='form-control'>
				<option value='100'>100</option>
				<option value='250'>250</option>
				<option value='500'>500</option>
				<option value='750'>750</option>
				<option value='1000'>1000</option>
				<option value='2000'>2000</option>
				<option value='3000'>3000</option>
				<option value='4000'>4000</option>
				<option value='5000'>5000</option>
				<option value='10000'>10000</option>
				<option value='25000'>25000</option>
				<option value='50000'>50000</option>
				</select></td><td><input type='Submit' class='btn btn-default' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></td></tr>
				</table></form>";
			}		
			include_once('./menu_escadrille.php');
		?>
			<h2>Gestion des stocks</h2>
			<table class='table'>
				<thead><tr><th>Production</th><th>Ravitaillement</th><th>Carburant</th><th>Munitions</th></tr></thead>
				<tr><td><img src='images/vehicules/vehicule4003.gif' alt='Efficacité de la production' title='Efficacité de la production'></td> 
				<td><img src='images/vehicules/vehicule5001.gif' alt='Efficacité du ravitaillement' title='Efficacité du ravitaillement'></td>
				<td><img src='images/vehicules/vehicule4008.gif' alt='Efficacité du ravitaillement en carburant' title='Efficacité du ravitaillement en carburant'></td>
				<td><img src='images/vehicules/vehicule3002.gif' alt='Efficacité du ravitaillement en munitions' title='Efficacité du ravitaillement en munitions'></td></tr>
				<tr><td><?echo $Efficacite_prod_base;?>%</td><td><?echo $Efficacite_ravit;?>%</td><td><?echo $Efficacite_ravit_fuel;?>%</td><td><?echo $Efficacite_ravit_muns;?>%</td></tr>
			</table>
			<?echo $output_ravit;?>
			<h3>Livraison directement au dépôt <a href='#' class='popup'><img src='images/help.png'><span>Il est toujours plus efficace de faire une demande de ravitaillement terrestre ou aérien</span></a></h3>
			<div id="col_droite">
				<table class='table table-striped'>
					<thead><tr><th colspan="2">Etat des Stocks de l'unité <a href='#' class='popup'><img src='images/help.png'><span>Plus votre unité est réputée, plus efficace est votre ravitaillement</span></a></th></tr></thead>
					<tr><th>Essence 87 Octane</th><td><?echo $Stock_Essence_87;?></td></tr>
					<tr><th>Essence 100 Octane</th><td><?echo $Stock_Essence_100;?></td></tr>
					<tr><th>Essence 130 Octane</th><td><?echo $Stock_Essence_130;?></td></tr>
					<tr><th>Diesel</th><td><?echo $Stock_Essence_1;?></td></tr>
					<tr><th>Munitions 8mm</th><td><?echo $Stock_Munitions_8;?></td></tr>
					<tr><th>Munitions 13mm</th><td><?echo $Stock_Munitions_13;?></td></tr>
					<tr><th>Munitions 20mm</th><td><?echo $Stock_Munitions_20;?></td></tr>
					<tr><th>Munitions 30mm</th><td><?echo $Stock_Munitions_30;?></td></tr>
					<tr><th>Munitions 40mm</th><td><?echo $Stock_Munitions_40;?></td></tr>
					<tr><th>Munitions 75mm</th><td><?echo $Stock_Munitions_75;?></td></tr>
					<tr><th>Munitions 90mm</th><td><?echo $Stock_Munitions_90;?></td></tr>
					<tr><th>Bombes 50kg</th><td><?echo $Bombes_50;?></td></tr>
					<tr><th>Bombes 125kg</th><td><?echo $Bombes_125;?></td></tr>
					<tr><th>Bombes 250kg</th><td><?echo $Bombes_250;?></td></tr>
					<tr><th>Bombes 500kg</th><td><?echo $Bombes_500;?></td></tr>
					<tr><th>Bombes 1000kg</th><td><?echo $Bombes_1000;?></td></tr>
					<tr><th>Bombes 2000kg</th><td><?echo $Bombes_2000;?></td></tr>
					<tr><th>Charges de profondeur</th><td><?echo $Bombes_300;?></td></tr>
					<tr><th>Mines</th><td><?echo $Bombes_400;?></td></tr>
					<tr><th>Torpilles</th><td><?echo $Bombes_800;?></td></tr>
					<tr><th>Fusées</th><td><?echo $Bombes_30;?></td></tr>
					<tr><th>Rockets</th><td><?echo $Bombes_80;?></td></tr>
					<tr><td><?echo GetAvionIcon($Avion1,$country,0,$Unite,$Front);?></td><td><?echo $Avion1_nbr;?></td></tr>
					<tr><td><?echo GetAvionIcon($Avion2,$country,0,$Unite,$Front);?></td><td><?echo $Avion2_nbr;?></td></tr>
					<tr><td><?echo GetAvionIcon($Avion3,$country,0,$Unite,$Front);?></td><td><?echo $Avion3_nbr;?></td></tr>
					<tr><th>D.C.A de la base <?echo "<a title='Détail de la DCA' href='esc_infodca.php?Unite=".$Unite."' target='_blank'><img src='images/help.png' title='Détail de la DCA'></a>";?></th>
					<td><?echo "<a title='Détail de la DCA' href='esc_infodca.php?Unite=".$Unite."' target='_blank'><img src='images/vehicules/vehicule16.gif' title='Détail de la DCA'></a>";?></td></tr>
				</table>
			</div>
			<?
				$dep_nbr=0;
				$Lat_min=$Base_Lat-2;
				$Lat_max=$Base_Lat+2;
				$Long_min=$Base_Long-5;
				$Long_max=$Base_Long+5;
				//$Faction=GetData("Pays","ID",$country,"Faction");
				$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
				$query="SELECT DISTINCT l.ID,l.Nom,l.Longitude,l.Latitude,l.Flag,l.Stock_Essence_87,l.Stock_Essence_100,l.Stock_Essence_1,l.Stock_Munitions_8,l.Stock_Munitions_13,l.Stock_Munitions_20,l.Stock_Munitions_30,
						l.Stock_Munitions_40,l.Stock_Munitions_50,l.Stock_Munitions_60,l.Stock_Munitions_75,l.Stock_Munitions_90,l.Stock_Munitions_105,l.Stock_Munitions_125,
						l.Stock_Bombes_30,l.Stock_Bombes_50,l.Stock_Bombes_80,l.Stock_Bombes_125,l.Stock_Bombes_250,l.Stock_Bombes_300,l.Stock_Bombes_400,l.Stock_Bombes_500,l.Stock_Bombes_800,l.Stock_Bombes_1000,l.Stock_Bombes_2000
						FROM Lieu as l, Pays as p WHERE l.ValeurStrat >3 AND (l.NoeudF_Ori=100 OR l.Port_Ori=100) AND l.Flag=p.Pays_ID AND p.Faction='$Faction' AND l.Depot_prive=0 AND
						(l.ID='$Base' OR ((l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max')))
						ORDER BY l.ValeurStrat DESC,l.Stock_Essence_87 DESC,l.Stock_Essence_100 DESC, RAND()";
				//$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);			
				if($result)
				{
					while($data=mysqli_fetch_array($result))
					{			
						if(GetData("Pays","ID",$data['Flag'],"Faction") ==$Faction)
						{
							$mes.=$data['Nom'];
							$Distance=GetDistance(0,0,$Base_Long,$Base_Lat,$data[2],$data[3]);
							if($Distance[0] <=$Rayon_Depots and $dep_nbr <4)
							{
								$depots_liste.='<option value='.$data['ID'].'>'.$data['Nom'].'</option>';
								echo "<div id='col_droite'><table class='table table-striped'>
									<thead><tr><th colspan='2'>".$data['Nom']." (".$Distance[0]."km)</th></tr></thead>
									<tr><td>".$data['Stock_Essence_87']."</td></tr>
									<tr><td>".$data['Stock_Essence_100']."</td></tr>
									<tr><td>0</td></tr>
									<tr><td>".$data['Stock_Essence_1']."</td></tr>
									<tr><td>".$data['Stock_Munitions_8']."</td></tr>
									<tr><td>".$data['Stock_Munitions_13']."</td></tr>
									<tr><td>".$data['Stock_Munitions_20']."</td></tr>
									<tr><td>".$data['Stock_Munitions_30']."</td></tr>
									<tr><td>".$data['Stock_Munitions_40']."</td></tr>
									<tr><td>".$data['Stock_Munitions_75']."</td></tr>
									<tr><td>".$data['Stock_Munitions_90']."</td></tr>
									<tr><td>".$data['Stock_Bombes_50']."</td></tr>
									<tr><td>".$data['Stock_Bombes_125']."</td></tr>
									<tr><td>".$data['Stock_Bombes_250']."</td></tr>
									<tr><td>".$data['Stock_Bombes_500']."</td></tr>
									<tr><td>".$data['Stock_Bombes_1000']."</td></tr>
									<tr><td>".$data['Stock_Bombes_2000']."</td></tr>
									<tr><td>".$data['Stock_Bombes_300']."</td></tr>
									<tr><td>".$data['Stock_Bombes_400']."</td></tr>
									<tr><td>".$data['Stock_Bombes_800']."</td></tr>
									<tr><td>".$data['Stock_Bombes_30']."</td></tr>
									<tr><td>".$data['Stock_Bombes_80']."</td></tr>
									</table></div>";
								$dep_nbr++;
							}
						}
					}
					mysqli_free_result($result);
				}
			?>
			<form action='esc_gestion1.php' method='post'>
			<input type='hidden' name='Unite' value="<? echo $Unite;?>">
			<input type='hidden' name='Cr' value="<? echo $Credits_Piste;?>">
			<input type='hidden' name='CT_Avion1' value="<? echo $CT_Avion1;?>">
			<input type='hidden' name='CT_Avion2' value="<? echo $CT_Avion2;?>">
			<input type='hidden' name='CT_Avion3' value="<? echo $CT_Avion3;?>">
			<div id='col_droite'><table class='table'>
				<thead><tr><th>Choix du Dépôt <a href='#' class='popup'><img src='images/help.png'><span>Si aucun dépôt n'apparait dans le menu déroulant, c'est que votre unité se trouve trop loin d'un dépôt contrôlé par votre faction. Privilégiez alors le ravitaillement terrestre ou aérien en contactant d'autres joueurs.</span></a></th></tr></thead>
				<tr><td><select class='form-control' name='depot'><option value='0'>- Aucun -</option><optgroup label="Dépôts"><?echo $depots_liste;?></optgroup></select></td></tr>
				<tr><td>
						<?
						if($Credits <1)
							echo "Vous ne disposez pas de suffisamment de Crédits Temps pour gérer les stocks de votre unité !";
						elseif($Zone ==6)
							echo "Vous ne pouvez pas accéder aux dépôts lorsque vous êtes en mer !";
						else
						{
							$Date=date('Y-m-d');
							$con=dbconnecti(4);
							$resultm=mysqli_query($con,"SELECT `Date` FROM Events WHERE Event_Type=31 AND PlayerID='$PlayerID' ORDER BY ID DESC LIMIT 1");
							mysqli_close($con);
							if($resultm)
							{
								$data=mysqli_fetch_array($resultm);
								$Date_Mutation=$data[0];
								if($Date_Mutation)
								{
									$con=dbconnecti();
									$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date', '$Date_Mutation')"),0);
									mysqli_close($con);
								}
								else
								{
									$con=dbconnecti();
									$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date', '2012-09-01')"),0);
									mysqli_close($con);
								}
							}
							if($Datediff >3)
							{
								if($Credits >=$CT_Avion1 and $Avion1_nbr <$Max_avions){?>
								<Input type='Radio' name='Action' value='1'><img src='/images/CT<?echo $CT_Avion1;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander de nouveaux <b><?echo $Avion1_nom;?></b> pour le <?echo $Sqn;?> 1<br>
								<?}else{?>
								<Input type='Radio' name='Action' value='1' disabled><img src='/images/CT<?echo $CT_Avion1;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander de nouveaux <b><?echo $Avion1_nom;?></b> pour le <?echo $Sqn;?> 1<br>
								<?}if($Credits >=$CT_Avion2 and $Avion2_nbr <$Max_avions){?>
								<Input type='Radio' name='Action' value='2'><img src='/images/CT<?echo $CT_Avion2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander de nouveaux <b><?echo $Avion2_nom;?></b> pour le <?echo $Sqn;?> 2<br>
								<?}else{?>
								<Input type='Radio' name='Action' value='2' disabled><img src='/images/CT<?echo $CT_Avion2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander de nouveaux <b><?echo $Avion2_nom;?></b> pour le <?echo $Sqn;?> 2<br>
								<?}if($Credits >=$CT_Avion3 and $Avion3_nbr <$Max_avions){?>
								<Input type='Radio' name='Action' value='3'><img src='/images/CT<?echo $CT_Avion3;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander de nouveaux <b><?echo $Avion3_nom;?></b> pour le <?echo $Sqn;?> 3<br>
								<?}else{?>
								<Input type='Radio' name='Action' value='3' disabled><img src='/images/CT<?echo $CT_Avion3;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander de nouveaux <b><?echo $Avion3_nom;?></b> pour le <?echo $Sqn;?> 3<br>
								<?}
							}
							else{?>
								<Input type='Radio' name='Action' disabled><img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander de nouveaux avions indisponible pour l'instant<br>
							<?}if($Credits >=2 and $Stock_Essence_87 <$Max_stock){?>
							<Input type='Radio' name='Action' value='4'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Essence 87 Octane</b><br>
							<?}
							if(IsAllie($country)){
							if($Credits >=3 and $Stock_Essence_100 <$Max_stock){?>
							<Input type='Radio' name='Action' value='5'><img src='/images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Essence 100 Octane</b><br>
							<?}
							}
							else
							{
							if($Credits >=5 and $Stock_Essence_100 <$Max_stock){?>
							<Input type='Radio' name='Action' value='5'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Essence 100 Octane</b><br>
							<?}
							}
							if($Credits >=5 and $Stock_Essence_1 <$Max_stock){?>
							<Input type='Radio' name='Action' value='6'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Diesel</b><br>
							<?}if($Credits >=1 and $Stock_Munitions_8 <($Max_stock*2)){?>
							<Input type='Radio' name='Action' value='7'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 8mm</b><br>
							<?}if($Credits >=2 and $Stock_Munitions_13 <$Max_stock){?>
							<Input type='Radio' name='Action' value='8'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 13mm</b><br>
							<?}if($Credits >= 3 and $Stock_Munitions_20 <$Max_stock){?>
							<Input type='Radio' name='Action' value='9'><img src='/images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 20mm</b><br>
							<?}if($Credits >= 4 and $Stock_Munitions_30 <$Max_stock){?>
							<Input type='Radio' name='Action' value='33'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 30mm</b><br>
							<?}if($Credits >= 5 and $Stock_Munitions_40 <5000){?>
							<Input type='Radio' name='Action' value='44'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 40mm</b><br>
							<?/*}if($Credits >=5 and $Stock_Munitions_50 <10000){?>
							<Input type='Radio' name='Action' value='45'>- Commander un ravitaillement en <b>Munitions 50mm</b> (5 Crédits Temps)<br>
							<?}if($Credits >=5 and $Stock_Munitions_60 <10000){?>
							<Input type='Radio' name='Action' value='46'>- Commander un ravitaillement en <b>Munitions 60mm</b> (5 Crédits Temps)<br>
							<?*/}if($Credits >=6 and $Stock_Munitions_75 <1000){?>
							<Input type='Radio' name='Action' value='47'><img src='/images/CT6.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 75mm</b><br>
							<?}if($Credits >=7 and $Stock_Munitions_90 <1000){?>
							<Input type='Radio' name='Action' value='48'><img src='/images/CT7.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 90mm</b><br>
							<?}if($Credits >=7 and $Stock_Munitions_105 <1000){?>
							<Input type='Radio' name='Action' value='49'><img src='/images/CT7.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 105mm</b><br>
							<?}if($Credits >=8 and $Stock_Munitions_125 <1000){?>
							<Input type='Radio' name='Action' value='50'><img src='/images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Munitions 125mm</b><br>
							<?}if($Credits >=1 and $Bombes_50 <10000){?>
							<Input type='Radio' name='Action' value='14'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Bombes de 50kg</b><br>
							<?}if($Credits >=2 and $Bombes_125 <2500){?>
							<Input type='Radio' name='Action' value='15'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Bombes de 125kg</b><br>
							<?}if($Credits >=3 and $Bombes_250 <2000){?>
							<Input type='Radio' name='Action' value='16'><img src='/images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Bombes de 250kg</b><br>
							<?}if($Credits >=4 and $Bombes_500 <1000){?>
							<Input type='Radio' name='Action' value='17'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Bombes de 500kg</b><br>
							<?}if($Credits >=5 and $Bombes_1000 <500){?>
							<Input type='Radio' name='Action' value='18'><img src='/images/CT5.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Bombes de 1000kg</b><br>
							<?}if($Credits >=10 and $Bombes_2000 <100){?>
							<Input type='Radio' name='Action' value='19'><img src='/images/CT10.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Bombes de 2000kg</b><br>
							<?}if($Credits >=4 and $Bombes_300 <1000){?>
							<Input type='Radio' name='Action' value='38'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Charges de profondeur</b> <a href='#' class='popup'><img src='images/help.png'><span>Toutes les charges ASM sont considérées comme une munition de type HE</span></a><br>
							<?}if($Credits >=4 and $Bombes_400 <1000){?>
							<Input type='Radio' name='Action' value='31'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Mines</b> <a href='#' class='popup'><img src='images/help.png'><span>Toutes les mines sont considérées comme une munition de type HE</span></a><br>
							<?}if($Credits >=4 and $Bombes_800 <1000){?>
							<Input type='Radio' name='Action' value='32'><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Torpilles</b> <a href='#' class='popup'><img src='images/help.png'><span>Toutes les torpilles sont considérées comme une munition de type anti-navire</span></a><br>
							<?}if($Credits >=2 and $Bombes_30 <10000){?>
							<Input type='Radio' name='Action' value='39'><img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Fusées éclairantes</b> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour le marquage de cibles</span></a><br>
							<?}if($Credits >=8 and $Bombes_80 <10000 and ($country ==8 or $Date_Campagne >"1943-01-01")){?>
							<Input type='Radio' name='Action' value='36'><img src='/images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'>- Commander un ravitaillement en <b>Rockets</b> <a href='#' class='popup'><img src='images/help.png'><span>Toutes les rockets sont considérées comme une munition de type HEAT</span></a><br>
							<?}
						}
							/*?>
							</td></tr><tr><td colspan="3"><hr></td></tr><tr><td align='left'>
							<?if($Credits >=8 and $QualitePiste >0 and ($Meteo ==-50 or $Meteo ==-135)){?>
							<Input type='Radio' name='Action' value='37'>- Faire déneiger la piste. (8 Crédits Temps)<br>
							<?}if($Credits >=4 and $QualitePiste <100 and $QualitePiste >0){?>
							<Input type='Radio' name='Action' value='10'>- Faire réparer la piste. (4 Crédits Temps)<br>
							<?}if($Credits >=$Credits_Piste and $QualitePiste <100){?>
							<Input type='Radio' name='Action' value='21'>- Faire réparer totalement la piste. (<?echo $Credits_Piste;?> Crédits Temps)<br>
							<?}if($Credits >=4 and $Tour <100){?>
							<Input type='Radio' name='Action' value='35'>- Faire réparer la tour. (4 Crédits Temps)<br>
							<?}if($Credits >=2 and $Camouflage <100){?>
							<Input type='Radio' name='Action' value='13'>- Améliorer le camouflage de la base. (2 Crédits Temps)<br>
							<?}if($Credits >=4 and $Station_Meteo <10){?>
							<Input type='Radio' name='Action' value='20'>- Améliorer la station météo de l'unité. (4 Crédits Temps)<br>
							<?}*/?>
					</td>
				</tr></table>
		<?
			echo "<input type='Submit' class='btn btn-default' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></form></div>";
		}
		else
		{
			include_once('./menu_escadrille.php');
			PrintNoAccessPil($country,1,2,3);
		}
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
//include_once('./index.php');
?>