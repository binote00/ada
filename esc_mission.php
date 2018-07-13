<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Avancement,Credits,Unit,Front FROM Pilote WHERE ID='$PlayerID'");
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
		$result=mysqli_query($con,"SELECT Nom,`Type`,Base,Avion1,Avion2,Avion3,Commandant,Officier_Adjoint,Mission_Lieu,Mission_Type,Mission_alt,Mission_Flight,Mission_Lieu_D,Mission_Type_D FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite_Nom=$data['Nom'];
				$Unite_Type=$data['Type'];
				$Base=$data['Base'];
				$Avion1=$data['Avion1'];
				$Avion2=$data['Avion2'];
				$Avion3=$data['Avion3'];
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Mission_Lieu=$data['Mission_Lieu'];
				$Mission_Type=$data['Mission_Type'];
				$Mission_alt=$data['Mission_alt'];
				$Mission_Flight=$data['Mission_Flight'];
				$Mission_Lieu_D=$data['Mission_Lieu_D'];
				$Mission_Type_D=$data['Mission_Type_D'];
			}
			mysqli_free_result($result);
			unset($data);
		}		
		if($Credits >0)
		{
			if($PlayerID >0 and ($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint))
			{
				if(!$Faction)$Faction=GetData("Pays","ID",$country,"Faction");	
				$Date_Campagne=GetData("Conf_Update","ID",2,"Date");				
				$Avion1_a=GetData("Avion","ID",$Avion1,"Autonomie");
				$Avion2_a=GetData("Avion","ID",$Avion2,"Autonomie");
				$Avion3_a=GetData("Avion","ID",$Avion3,"Autonomie");	
				//GetData Lieu
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,Longitude,Latitude FROM Lieu WHERE ID='$Base'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Base_Nom=$data['Nom'];
						$Longitude_base=$data['Longitude'];
						$Latitude_base=$data['Latitude'];
					}
					mysqli_free_result($result);
					unset($data);
				}				
				$Mission_Lieu=GetData("Lieu","ID",$Mission_Lieu,"Nom");
				if(!$Mission_Lieu)$Mission_Lieu="<i>Aucune</i>";
				$Mission_Type=GetMissionType($Mission_Type);
				if(!$Mission_Type)$Mission_Type="<i>Indéfini</i>";				
				//Demande Mission
				$choix_1="";
				$choix_2="";
				$choix_4="";
				$choix_5="";
				$choix_11="";
				$choix_12="";
				$choix_13="";
				$choix_15="";
				$choix_21="";
				$choix_26="";
				if($Unite_Type ==2 or $Unite_Type ==4 or $Unite_Type ==7 or $Unite_Type ==10)
				{
					$choix_4="<option value='4'>Escorte</option>";
					$choix_21="<option value='21'>Marquage de cible</option>";
					$choix_15="<option value='15'>Reconnaissance stratégique</option>";
					$choix_5="<option value='5'>Reconnaissance tactique</option>";
				}
				elseif($Unite_Type ==11)
				{
					$choix_4="<option value='4'>Escorte</option>";
					$choix_21="<option value='21'>Marquage de cible</option>";
					$choix_15="<option value='15'>Reconnaissance stratégique</option>";
				}
				elseif($Unite_Type ==6)
				{
					$choix_4="<option value='4'>Escorte</option>";
				}
				elseif($Unite_Type ==9)
				{
					$choix_11="<option value='11'>Attaque de navire</option>";
					$choix_12="<option value='12'>Bombardement naval</option>";
					$choix_13="<option value='13'>Torpillage</option>";
				}
				elseif($Unite_Type ==3)
				{
					$choix_1="<option value='1'>Appui rapproché</option>";
					$choix_11="<option value='11'>Attaque de navire</option>";
					$choix_12="<option value='12'>Bombardement naval</option>";
					$choix_2="<option value='2'>Bombardement tactique</option>";
					$choix_13="<option value='13'>Torpillage</option>";
				}				
				//Missions
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
					break;
					case 2:
						$choix12="<option value='12'>Bombardement naval</option>";
						$choix8="<option value='8'>Bombardement stratégique de jour</option>";
						$choix16="<option value='16'>Bombardement stratégique de nuit</option>";
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						$choix13="<option value='13'>Torpillage</option>";
					break;
					case 3:
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix21="<option value='21'>Marquage de cible</option>";
						$choix15="<option value='15'>Reconnaissance stratégique</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						$choix18="<option value='18'>Sauvetage</option>";
						$choix22="<option value='22'>Sauvetage de Nuit</option>";
						//Prévoir mission brouillage radio
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
					break;
					case 6:
						$choix24="<option value='24'>Parachutage de jour</option>";
						$choix25="<option value='25'>Parachutage de nuit</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						$choix18="<option value='18'>Sauvetage</option>";
						$choix22="<option value='22'>Sauvetage de Nuit</option>";
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
					break;
					case 8:
					break;
					case 9:
						$choix12="<option value='12'>Bombardement naval</option>";
						$choix29="<option value='29'>Patrouille ASM</option>";
						$choix14="<option value='14'>Mouillage de mines</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						$choix15="<option value='15'>Reconnaissance stratégique</option>";
						$choix13="<option value='13'>Torpillage</option>";
						$choix19="<option value='19'>Sauvetage en mer</option>";
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
						//$choix26="<option value='26'>Supériorité aérienne</option>";
					break;
				}
				/*if($country ==20)
					$Carte_Bouton='<td><a href=\'cartepos_finland.php?longit='.$Longit.'&latit='.$Latit.'\' class=\'btn btn-info\' onclick=\'window.open(this.href); return false;\'>Voir la carte</a></td>';
				elseif($Longitude_base >67)
					$Carte_Bouton='<td><a href=\'cartepos_pacifique.php?longit='.$Longit.'&latit='.$Latit.'\' class=\'btn btn-info\' onclick=\'window.open(this.href); return false;\'>Voir la carte</a></td>';
				elseif($Latitude_base >52 and $Longitude_base >13)
					$Carte_Bouton='<td><a href=\'cartepos_nord_est.php?longit='.$Longit.'&latit='.$Latit.'\' class=\'btn btn-info\' onclick=\'window.open(this.href); return false;\'>Voir la carte</a></td>';
				elseif($Latitude_base >44 and $Longitude_base >13)
					$Carte_Bouton='<td><a href=\'cartepos_sud_est.php?longit='.$Longit.'&latit='.$Latit.'\' class=\'btn btn-info\' onclick=\'window.open(this.href); return false;\'>Voir la carte</a></td>';
				elseif($Latitude_base >44 and $Longitude_base <14)
					$Carte_Bouton='<td><a href=\'cartepos.php?longit='.$Longit.'&latit='.$Latit.'\' class=\'btn btn-info\' onclick=\'window.open(this.href); return false;\'>Voir la carte</a></td>';
				elseif($Latitude_base <43 and $Longitude_base >13)
					$Carte_Bouton='<td><a href=\'cartepos_med_est.php?longit='.$Longit.'&latit='.$Latit.'\' class=\'btn btn-info\' onclick=\'window.open(this.href); return false;\'>Voir la carte</a></td>';
				else
					$Carte_Bouton='<td><a href=\'cartepos_med.php?longit='.$Longit.'&latit='.$Latit.'\' class=\'btn btn-info\' onclick=\'window.open(this.href); return false;\'>Voir la carte</a></td>';*/
				$Carte_Bouton="<td><a href='carte_ground.php?map=".$Front."&mode=1' class='btn btn-info' onclick='window.open(this.href); return false;'>Voir la carte</a></td>";
				$Sqn=GetSqn($country);
				//Output
				include_once('./menu_escadrille.php');
				/*?>			
				<h2>Mission d'unité en cours</h2>
				<form action='esc_mission1.php' method='post'><input type='hidden' name='reset' value="1"><input type='hidden' name='Unite' value="<? echo $Unite;?>">
				<table class='table'><thead><tr><th>Type de mission</th><th>Objectif</th><th>Altitude</th><th><?echo $Sqn;?></th><th>Action</th></tr></thead>
				<tr><td><?echo $Mission_Type;?></td><td><?echo $Mission_Lieu;?></td><td><?echo $Mission_alt;?>m</td><td><?echo $Mission_Flight;?></td><td><input type='Submit' class="btn btn-warning" value="Annuler la mission en cours"></td></tr>
				</table></form>
				<?*/
				//Demande de ravitaillement
                if($Unite_Type !=6 and $Unite_Type !=8 and $Unite_Type !=10 and $Unite_Type !=12){
                    $con=dbconnecti();
                    $result3=mysqli_query($con,"SELECT DISTINCT j.ID,j.Nom,u.Nom as Unite FROM Pilote as j,Unit as u WHERE j.Unit=u.ID 
				    AND u.Type=6 AND j.Pays='$country' AND j.Front='$Front' AND j.Actif=0 AND j.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()");
                    mysqli_close($con);
                    if($result3)
                    {
                        while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
                        {
                            $Ravit_Off_txt.="<option value=".$data['ID'].">".$data['Nom']." (".$data['Unite'].")</option>";
                        }
                        mysqli_free_result($result3);
                    }
                    if($Ravit_Off_txt !="")
                    {
                        echo "<h2>Demande de ravitaillement</h2><form action='index.php?view=esc_ravit' method='post'>
						<input type='hidden' name='Cible' value='".$Base."'>
						<input type='hidden' name='Unit' value='".$Unite."'>
						<table class='table'><thead><tr><th>Unité à contacter</th><th>Chargement</th><th>Quantité</th></thead>			
						<tr><td align='left'><select name='Ravit_Off' class='form-control'><option value='0'>Aucun</option>".$Ravit_Off_txt."</select></td>
						<td align='left'><select name='Charge' class='form-control'>
						<option value='8'>8mm</option>
						<option value='13'>13mm</option>
						<option value='20'>20mm</option>
						<option value='30'>30mm</option>
						<option value='40'>40mm</option>
						<option value='50'>Bombes de 50kg</option>
						<option value='125'>Bombes de 125kg</option>
						<option value='250'>Bombes de 250kg</option>
						<option value='500'>Bombes de 500kg</option>
						<option value='300'>Charges</option>
						<option value='30'>Fusées</option>
						<option value='400'>Mines</option>
						<option value='80'>Rockets</option>
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
						</select></td>
						<td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr></table></form>";
                    }
                }
				if($Unite_Type !=8 and $Mission_Type_D and $Mission_Lieu_D)
				{
					$Mission_Lieu_D=GetData("Lieu","ID",$Mission_Lieu_D,"Nom");
					if(!$Mission_Lieu_D)$Mission_Lieu_D="<i>Aucune</i>";
					$Mission_Type_D=GetMissionType($Mission_Type_D);
					if(!$Mission_Type_D)$Mission_Type_D="<i>Indéfini</i>";
					echo "<h2>Demande de mission en cours</h2>
					<form action='esc_mission1.php' method='post'>
					<input type='hidden' name='reset' value='3'>
					<input type='hidden' name='Unite' value='".$Unite."'>
					<table class='table'><tr><td>".$Mission_Type_D."</td><td>".$Mission_Lieu_D."</td><td><input type='Submit' class='btn btn-warning' value='Annuler la demande en cours'></td></tr></table></form>";
				}
				echo "<h2>Demande d'assistance sur la base de ".$Base_Nom."</h2>
					<form action='esc_mission1.php' method='post'>
					<input type='hidden' name='Unite' value='".$Unite."'>
					<input type='hidden' name='Cible' value=".$Base.">
					<input type='hidden' name='reset' value='5'>
					<table class='table'><tr><td><select name='Type' class='form-control'><option value='7'>Patrouille défensive</option><option value='17'>Chasse de nuit</option></select></td>
					<td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr></table></form>";
				if($Credits < 1)
					echo "<h6>Vous ne disposez pas de suffisamment de Crédits Temps pour assigner une mission à votre unité !<h6>";
				else
				{
					//Lieux Offensif
					$Lieux="";
					$Lieux2="";
					$Lands=GetAllies($Date_Campagne);
					if(IsAxe($country))
					{
						$Allies=$Lands[0];
						$Axe=$Lands[1];
					}
					else
					{
						$Axe=$Lands[0];
						$Allies=$Lands[1];
					}
					if($Latitude_base >60)
						$query_off="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >55 AND Longitude <60 AND Longitude >-52 ORDER BY Nom ASC";
					elseif($Front ==3) //$Date_Campagne >"1941-12-06"
						$query_off="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >67 ORDER BY Nom ASC";
					elseif($Front ==1 or $Front ==4) //$Date_Campagne >"1941-06-21"
						$query_off="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >41.5 AND Longitude >13.35 ORDER BY Nom ASC";
					elseif($Front ==2)
						$query_off="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude <43 AND Longitude <50 ORDER BY Nom ASC";
					else
						$query_off="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >=43 AND Longitude <=14 ORDER BY Nom ASC";
					$con=dbconnecti();
					$result_off=mysqli_query($con,$query_off) or die(mysqli_error($con));
					mysqli_close($con);
					if($result_off)
					{
						while ($data=mysqli_fetch_array($result_off,MYSQLI_ASSOC)) 
						{
							$Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data['Longitude'],$data['Latitude']);
							$Dist_chk=($Dist[0]*2)+200;
							if($Dist_chk < $Avion1_a or $Dist_chk < $Avion2_a or $Dist_chk < $Avion3_a)
								$Lieux.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
						}
						mysqli_free_result($result_off);
					}	
					/*Lieux défensifs
					if($result_def)
					{
						while($data=mysqli_fetch_array($result_def,MYSQLI_ASSOC)) 
						{
							$Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data['Longitude'],$data['Latitude']);
							if($data['ID'] ==$Base)$Dist[0]=10;
							$Dist_chk=($Dist[0]*2)+200;
							if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a)
								$Lieuxd.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
						}
						mysqli_free_result($result_def);
					}*/
					/*if($Lieux or $Lieux2)
					{
						echo "<h2>Missions Offensives</h2>
							<form action='esc_mission1.php' method='post'>
							<input type='hidden' name='Unite' value='".$Unite."'>
							<table class='table'>
								<tr><th>Choix de la Cible</th><td>
										<select name='Cible' class='form-control'>".$Lieux.$Lieux2."</select>
									</td><th>Choix du Type de Mission</th><td>
										<select name='Type' class='form-control'>
										".$choix1.$choix6.$choix2.$choix8.$choix16.$choix3.$choix4.$choix5.$choix11.$choix12.$choix13.$choix15.$choix21.$choix24.$choix25.$choix26.$choix29.$choix31."
										</select>			
								</td></tr>
								<tr><th>Choix de l'altitude de Mission</th><td>	
										<select name='Altitude' class='form-control'>
											<option value='100'>Basse altitude (100m)</option>
											<option value='500'>Basse altitude (500m)</option>
											<option value='1000'>Basse altitude (1000m)</option>
											<option value='2000'>Altitude moyenne (2000m)</option>
											<option value='3000'>Altitude moyenne (3000m)</option>
											<option value='4000'>Altitude moyenne (4000m)</option>
											<option value='5000' selected>Altitude moyenne (5000m)</option>
											<option value='6000'>Altitude moyenne (6000m)</option>
											<option value='7000'>Haute altitude (7000m)</option>
											<option value='8000'>Haute altitude (8000m)</option>
											<option value='9000'>Haute altitude (9000m)</option>
											<option value='10000'>Haute altitude (10000m)</option>
										</select>
								</td><th>Choix du ".$Sqn."</th><td>	
										<select name='Flight' class='form-control'>
											<option value='1' selected>1</option>
											<option value='2'>2</option>
											<option value='3'>3</option>
										</select>
								</td></tr>
								<tr><th>Briefing</th><td>
									<textarea name='Briefing' class='form-control' rows='5' cols='50'>
									</textarea>
								</td><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
							</table></form>";
					}
					else
						echo "Aucune destination à portée";
					/*if($Lieuxd or $Lieuxd2)
					{
						echo "<h2>Missions Défensives</h2>
							<form action='esc_mission1.php' method='post'>
							<input type='hidden' name='Unite' value='".$Unite."'>
							<table class='table'>
								<tr><th>Choix de la Cible</th>
								<td align='left'><select name='Cible' class='form-control'>".$Lieuxd.$Lieuxd2."</select></td>
								<th>Choix du Type de Mission</th>
								<td align='left'><select name='Type' class='form-control'>".$choix7.$choix14.$choix17."</select></td></tr>";
						echo "<tr><th>Choix de l'altitude de Mission</th>
								<td align='left'>	
										<select name='Altitude' class='form-control'>
											<option value='100'>Basse altitude (100m)</option>
											<option value='500'>Basse altitude (500m)</option>
											<option value='1000'>Basse altitude (1000m)</option>
											<option value='2000'>Altitude moyenne (2000m)</option>
											<option value='3000'>Altitude moyenne (3000m)</option>
											<option value='4000'>Altitude moyenne (4000m)</option>
											<option value='5000' selected>Altitude moyenne (5000m)</option>
											<option value='6000'>Altitude moyenne (6000m)</option>
											<option value='7000'>Haute altitude (7000m)</option>
											<option value='8000'>Haute altitude (8000m)</option>
											<option value='9000'>Haute altitude (9000m)</option>
											<option value='10000'>Haute altitude (10000m)</option>
										</select>
									</td><th>Choix du ".$Sqn."</th>
								<td align='left'>	
										<select name='Flight' class='form-control'>
											<option value='1' selected>1</option>
											<option value='2'>2</option>
											<option value='3'>3</option>
										</select>
									</td></tr>
								<tr><th>Briefing</th><td align='left'>
									<textarea name='Briefing' class='form-control' rows='5' cols='50'></textarea>
								</td><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
							</table></form>";
					}*/
					//Demande de Mission
					if($Unite_Type !=8 and $Unite_Type !=1 and $Unite_Type !=12 and $Lieux)
					{
						echo "<h2>Demande de mission</h2>
							<form action='esc_mission1.php' method='post'>
							<input type='hidden' name='Unite' value='".$Unite."'>
							<input type='hidden' name='reset' value='5'>
							<table class='table'>
								<thead><tr><th>Choix de la Cible</th><th>Choix du Type de Mission</th></thead>
								<td><select name='Cible' class='form-control' style='width: 200px'>".$Lieux."</select>
									<td><select name='Type' class='form-control' style='width: 200px'>
										".$choix_1.$choix_11.$choix_12.$choix_2.$choix_4.$choix_21.$choix_15.$choix_5.$choix_13."
									</select></td>
							<td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td>".$Carte_Bouton."</tr></table></form>";
					}
					//Demandes en cours
					$Coord=GetCoord($Front);
					$Lat_base_min=$Coord[0];
					$Lat_base_max=$Coord[1];
					$Long_base_min=$Coord[2];
					$Long_base_max=$Coord[3];
					$txt="";
					$con=dbconnecti();
					$result=mysqli_query($con,"(SELECT DISTINCT Lieu.Nom,Lieu.Zone,Unit.Mission_Type_D,Pays.Pays_ID,Unit.Nom,Lieu.Recce,Lieu.ID FROM Unit,Lieu,Pays 
					WHERE (Lieu.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (Lieu.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND Unit.Pays=Pays.Pays_ID AND Unit.Mission_Lieu_D >0 AND Unit.Mission_Type_D >0 AND Pays.Faction='$Faction' AND Unit.Mission_Lieu_D=Lieu.ID) 
					UNION (SELECT l.Nom,l.Zone,r.Mission_Type_D,r.Pays,r.ID,l.Recce,l.ID FROM Lieu as l,Regiment_IA as r,Pays as p 
					WHERE r.Pays=p.Pays_ID AND r.Mission_Lieu_D=l.ID AND r.Mission_Lieu_D >0 AND r.Mission_Type_D >0 AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max'))");
					/*UNION (SELECT DISTINCT Lieu.Nom,Lieu.Zone,Officier.Mission_Type_D,Pays.Pays_ID,Officier.Nom,Lieu.Recce,Lieu.ID FROM Officier,Lieu,Pays 
					WHERE Officier.Pays=Pays.Pays_ID AND Officier.Front='$Front' AND Officier.Mission_Lieu_D >0 AND Officier.Mission_Type_D >0 AND Pays.Faction='$Faction' AND Officier.Mission_Lieu_D=Lieu.ID)*/
					mysqli_close($con);
					if($result)
					{
						while($Data=mysqli_fetch_array($result,MYSQLI_NUM)) 
						{
							if($Data[1] ==6)
							{
								$con=dbconnecti();
								$Nav_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Data[6]' AND Pays<>'$country' AND Vehicule_Nbr >0 AND Visible=1"),0);
								mysqli_close($con);
								if($Nav_eni >0)
									$Recce='<b>Oui</b>';
								else
									$Recce='Non';
							}
							else
							{
								if($Data[5] ==2)
									$Recce='<b>Eclairé</b>';
								elseif($Data[5] ==1)
									$Recce='<b>Oui</b>';
								else
									$Recce='Non';
							}
							//$txt.="<tr><td>".$Data[0]."</td><td><img src='images/zone".$Data[1].".jpg'></td><td>".GetMissionType($Data[2])."</td><td><img src='images/unit".$Data[3]."p.gif' title='".$Data[4]."'></td><td>".$Recce."</td></tr>";		
							$txt.="<tr><td>".$Data[0]."</td><td><img src='images/zone".$Data[1].".jpg'></td><td>".GetMissionType($Data[2])."</td><td><img src='".$Data[3]."20.gif' title='".$Data[4]."'></td><td>".$Recce."</td></tr>";		
						}
					}
					if(!$txt)
						$txt="<tr><td colspan='5'>Aucune demande actuellement</td></tr>";
					echo "<h1>Le Front</h1><h2>Demandes de mission en cours</h2><table class='table table-striped'>
						<thead><tr>
						<th>Lieu</th>
						<th>Zone</th>
						<th>Mission demandée</th>
						<th>Unité demandeuse</th>
						<th>Status Reco</th></tr></thead>";
					echo $txt.'</table>';
				}
			}
			else
			{
				include_once('./menu_escadrille.php');
				echo "<br><img src='images/top_secret.gif'><div class='alert alert-info'>Lorsque vous commanderez votre propre escadrille, vous pourrez utiliser les transmissions</div>";
			}
		}
		else
			echo "<h1>Manque de temps</h1><div class='alert alert-danger'>Vous manquez de temps pour donner vos ordres...</div>";
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><div class='alert alert-danger'>Peut-être la reverrez-vous un jour votre escadrille...</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>