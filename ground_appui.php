<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{			
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Faction=GetData("Pays","ID",$country,"Faction");
	$Division=GetData("Officier","ID",$OfficierID,"Division");
	$Position=GetData("Regiment","Officier_ID",$OfficierID,"Position");
	if($Division >0 and $Position !=11)
	{
		$para=true;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Credits,Mission_Lieu_D,Mission_Type_D,Transit,DATE_FORMAT(Rapport,'%d-%m-%Y') as Rapport FROM Officier WHERE ID='$OfficierID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
				$Credits=$data['Credits'];
				$Mission_Lieu_D=$data['Mission_Lieu_D'];
				$Mission_Type_D=$data['Mission_Type_D'];
				$Transit=$data['Transit'];
				$Rapport=$data['Rapport'];
			}
			mysqli_free_result($result);
		}
		$Lieu=GetData("Regiment","Officier_ID",$OfficierID,"Lieu_ID");
		$Ravit=GetData("Division","ID",$Division,"ravit");
		if($Ravit >0)
		{
			$Ravit_txt=GetData("Lieu","ID",$Ravit,"Nom");
			$Ravit_txt="<Input type='Radio' name='Cible' value=".$Ravit." title='Lieu de ravitaillement de la division'>".$Ravit_txt."<br>";
		}
		$Regiments="<option value='0'>Aucun</option>";
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result=mysqli_query($con,"SELECT Nom,Latitude,Longitude,Zone,Recce,Flag,Flag_Port,NoeudF,NoeudF_Ori,Pont_Ori,Port_Ori,Port,Plage,Radar_Ori,Industrie,NoeudR,BaseAerienne FROM Lieu WHERE ID='$Lieu'");
		$result2=mysqli_query($con,"SELECT r.ID,r.Placement,c.Para FROM Regiment as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Officier_ID='$OfficierID'");
		$result3=mysqli_query($con,"SELECT o.ID,o.Nom,o.Division,c.mobile FROM Officier as o,Regiment as r, Cible as c WHERE r.Officier_ID=o.ID AND r.Vehicule_ID=c.ID 
		AND c.Charge >0 AND r.Vehicule_ID<>5000 AND o.Pays='$country' AND o.Front='$Front' AND o.Actif=0 AND o.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()");
		mysqli_close($con);
		if($result2)
		{
			while($datar=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Placement=$datar['Placement'];
				if(!$datar['Para'])$para=false;
				$Regiments.="<option value='".$datar['ID']."'>".$datar['ID']."e Cie</option>";
			}
			mysqli_free_result($result2);
		}
		if($result3)
		{
			while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				if($data['ID'] !=$id_res)
				{
					if($data['Division'])
						$Division_nom=GetData("Division","ID",$data['Division'],"Nom");
					else
						$Division_nom="Sans division";
					if($data['mobile'] ==5)
						$Division_nom.=" - naval";					
					$Ravit_Off_txt.="<option value=".$data['ID'].">".$data['Nom']." (".$Division_nom.")</option>";
				}
				$id_res=$data['ID'];
			}
			mysqli_free_result($result3);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Cible_nom=$data['Nom'];
				$Zone=$data['Zone'];
				$Latitude_front=$data['Latitude'];
				$Longitude_front=$data['Longitude'];
				$Recce=$data['Recce'];
				$Flag=$data['Flag'];
				$Flag_Port=$data['Flag_Port'];
				$NoeudR=$data['NoeudR'];
				$NoeudF=$data['NoeudF'];
				$NoeudF_Ori=$data['NoeudF_Ori'];
				$Industrie=$data['Industrie'];
				$Radar_Ori=$data['Radar_Ori'];
				$Pont_Ori=$data['Pont_Ori'];
				$Port_Ori=$data['Port_Ori'];
				$Port=$data['Port'];
				$BaseAerienne=$data['BaseAerienne'];
				$Plage=$data['Plage'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($Flag)
			$Rev="<img src='images/".$Flag."20.gif' title='Nation revendiquant le lieu'>";
		if($Recce ==2)
			$Recce_txt="Zone éclairée";
		elseif($Recce ==1)
			$Recce_txt="Zone reconnue";
		else
			$Recce_txt="Zone non reconnue";		
		if(!$Mission_Lieu_D)
			$Mission_Lieu_D="<i>Aucune</i>";
		else
			$Mission_Lieu_D=GetData("Lieu","ID",$Mission_Lieu_D,"Nom");
		if(!$Mission_Type_D)
			$Mission_Type_D_txt="<i>Indéfini</i>";
		else
			$Mission_Type_D_txt=GetMissionType($Mission_Type_D);	
		if($Zone ==6)
		{
			$choix1="<option value='11'>Attaque navale</option>";
			$choix2="<option value='12'>Bombardement naval</option>";
			$choix3="<option value='14'>Mouillage de mines</option>";
			$choix3="<option value='7'>Patrouille</option>";
			$choix4.="<option value='29'>Patrouille ASM</option>";
			$choix5="<option value='5'>Reconnaissance tactique</option>";
			$choix6="<option value='13'>Torpillage</option>";
		}
		elseif($Zone !=6 and ($Port_Ori >0 or $Plage >0))
		{
			$choix1="<option value='1'>Appui rapproché</option>";
			$choix1.="<option value='11'>Attaque navale</option>";
			$choix2="<option value='12'>Bombardement naval</option>";
			$choix2.="<option value='2'>Bombardement tactique</option>";
			$choix3="<option value='17'>Chasse de Nuit</option>";
			$choix3.="<option value='14'>Mouillage de mines</option>";
			$choix4="<option value='7'>Patrouille</option>";
			$choix4.="<option value='29'>Patrouille ASM</option>";
			$choix5="<option value='5'>Reconnaissance tactique</option>";
			$choix6="<option value='13'>Torpillage</option>";
		}
		else
		{
			$choix1="<option value='1'>Appui rapproché</option>";
			$choix2="<option value='2'>Bombardement tactique</option>";
			$choix3="<option value='17'>Chasse de Nuit</option>";
			$choix4="<option value='7'>Patrouille</option>";
			$choix5="<option value='5'>Reconnaissance tactique</option>";
		}
		if($BaseAerienne >0)
		{
			$choix6.="<option value='23'>Ravitaillement aérien</option>";
			if($para)$choix6.="<option value='24'>Parachutage</option>";
		}
		if($Date_Campagne >'1944-01-01')
			$Muns_txt="<option value='37'>APDS (AT)</option><option value='38'>HEAT (Soutien courte portée)</option>";
		elseif($Date_Campagne >'1941-01-01')
			$Muns_txt="<option value='38'>HEAT (Soutien courte portée)</option>";			
		$Telephone = true;						
		echo "<h1>Transmissions</h1>";
		include_once('./unit_ground_infos.php');
		if($Bat_Veh_Nbr >0)
		{
			if($Faction >0)
			{
				$output="<h2>Rapport à l'état-major</h2><form action='index.php?view=ground_appui1' method='post'>
								<input type='hidden' name='reset' value='6'>
								<input type='hidden' name='Officier' value='".$OfficierID."'>
								Signaler la situation logistique de votre bataillon à votre état-major.  <input type='Submit' value='RAPPORT' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form> Date du dernier rapport envoyé : ".$Rapport;
				$output.="<h2>Demande de renforts</h2><form action='index.php?view=ground_appui1' method='post'>
							<input type='hidden' name='reset' value='4'>
							<input type='hidden' name='Officier' value='".$OfficierID."'>
							<input type='hidden' name='Cible' value='".$Lieu."'>
							Signaler à votre Etat-Major que vous avez besoin d'aide.  <input type='Submit' value='HELP' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
				$con=dbconnecti();
				$Enis_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
				$Enis_IA_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
				mysqli_close($con);
				$Enis_zone+=$Enis_IA_zone;
				if($Mission_Type_D)	
				{
					$output.="<h2>Demande d'appui en cours</h2><form action='index.php?view=ground_appui1' method='post'>
							<input type='hidden' name='reset' value='3'>
							<input type='hidden' name='Officier' value='".$OfficierID."'>
							<table class='table'>
							<thead><tr><th>Mission</th><th>Lieu</th><th></th></tr></thead>
							<tr><td>".$Mission_Type_D_txt."</td><td>".$Mission_Lieu_D."</td>
							<td><input type='Submit' value='Annuler la demande en cours' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></td></tr></table></form>";
				}
				$output.="<h2>Demande d'appui aérien</h2><form action='index.php?view=ground_appui1' method='post'>
					<input type='hidden' name='Officier' value='".$OfficierID."'>
					<input type='hidden' name='Cible' value='".$Lieu."'>
					<input type='hidden' name='reset' value='5'>
					<table class='table'>
					<thead><tr><th>Mission</th><th>Lieu</th><th>Statut reco</th></tr></thead>
					<tr><td><select name='Type' class='form-control' style='width:50%'>".$choix1.$choix2.$choix3.$choix4.$choix5.$choix6."</select></td><td>".$Cible_nom."</td><td>(".$Recce_txt.")</td></tr>
					<tr><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr></table></form>";
			}
			if($Ravit_Off_txt !="")
			{
				function GetRavMenu($Prefix)
				{
					return "<td align='left'><select name='Charge".$Prefix."' class='form-control'>
					<option value='8'>8mm</option><option value='13'>13mm</option><option value='20'>20mm</option><option value='30'>30mm</option>
					<option value='40'>40mm</option><option value='50'>50mm</option><option value='60'>60mm</option><option value='75'>75mm</option>
					<option value='90'>90mm</option><option value='105'>105mm</option><option value='125'>125mm</option><option value='150'>150mm</option>
					<option value='300'>Charges</option><option value='400'>Mines</option><option value='530'>Torpilles 530mm</option><option value='610'>Torpilles 610mm</option>
					<option value='87'>Essence</option><option value='1'>Diesel</option></select></td>			
					<td align='left'><select name='Qty".$Prefix."' class='form-control'>
					<option value='100'>100</option><option value='250'>250</option><option value='500'>500</option><option value='750'>750</option><option value='1000'>1000</option>
					<option value='2000'>2000</option><option value='3000'>3000</option><option value='4000'>4000</option><option value='5000'>5000</option><option value='10000'>10000</option>
					<option value='25000'>25000</option><option value='50000'>50000</option></select></td>		
					<td align='left'><select name='Mun".$Prefix."' class='form-control'>
					<option value='30'>Standard</option>
					<option value='31'>AP (Perforant lourd)</option>
					<option value='32'>HE (Explosif)</option>
					<option value='34'>APHE (Perforant léger + Explosif)</option>
					<option value='36'>APCR (Perforant courte portée)</option>".$Muns_txt."
					</select></td></tr>";
				}
				
				$output.="<h2>Demande de ravitaillement</h2><form action='index.php?view=ground_appui1' method='post'>
					<input type='hidden' name='Officier' value='".$OfficierID."'><input type='hidden' name='reset' value='10'>
					<table class='table'><thead><tr><th>Lieu de livraison</th><th>Officier</th><th>Compagnie</th><th>Chargement</th><th>Quantité</th><th>Type de Munitions</th></thead>			
				<tr><td rowspan='4' align='left'><Input type='Radio' name='Cible' value=".$Lieu." checked>".$Cible_nom."<br>".$Ravit_txt."</td>
				<td rowspan='4' align='left'><select name='Ravit_Off' class='form-control'><option value='0'>Aucun</option>".$Ravit_Off_txt."</select></td>
				<td align='left'><select name='Ciea' class='form-control'>".$Regiments."</select></td>".GetRavMenu("a")."
				<tr><td align='left'><select name='Cieb' class='form-control'>".$Regiments."</select></td>".GetRavMenu("b")."
				<tr><td align='left'><select name='Ciec' class='form-control'>".$Regiments."</select></td>".GetRavMenu("c")."
				<tr><td align='left'><select name='Cied' class='form-control'>".$Regiments."</select></td>".GetRavMenu("d")."
				</table><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}
			else
				$output.="<p><b>Demande de ravitaillement impossible.</b><br>Aucun officier ravitailleur n'est présent sur votre front!</p>";
			//Navires
			$Faction_occupant_port=GetData("Pays","ID",$Flag_Port,"Faction");
			if($g_mobile !=4 and $g_mobile !=5 and $Credits >=1 and !$Enis_IA_zone)
			{
				if(($Port >0 and $Placement ==4 and $Faction_occupant_port ==$Faction) or ($Plage >0 and $Placement ==11))
				{
					$choix=false;
					if($Front ==2)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Longitude <50 AND Latitude <43 AND Zone<>6 AND (Port >0 OR Plage >0) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Front ==1)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Longitude >13 AND Longitude <45 AND Latitude >=43 AND Latitude <=50.5 AND Zone<>6 AND (Port >0 OR Plage >0) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Front ==4)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Longitude >13 AND Longitude <45 AND Latitude >50.5 AND Zone<>6 AND (Port >0 OR Plage >0) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Front ==3)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Longitude >67 AND Zone<>6 AND (Port >0 OR Plage >0) AND ID<>'$Lieu' ORDER BY Nom ASC";
					elseif($Front ==5)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Longitude >13 AND Longitude <50 AND Latitude >60 AND Zone<>6 AND (Port >0 OR Plage >0) AND ID<>'$Lieu' ORDER BY Nom ASC";
					else
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Longitude <14 AND Latitude >=43 AND Latitude <60 AND Zone<>6 AND (Port >0 OR Plage >0) AND ID<>'$Lieu' ORDER BY Nom ASC";
					$con=dbconnecti();
					$result=mysqli_query($con,$query);
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_NUM))
						{
							$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,$data[2],$data[3]);
							if($Distance[0] <2000)
							{
								$sensh='';
								$sensv='';
								if($Longitude_front > $data[2])
									$sensh ='Ouest';
								elseif($Longitude_front < $data[2])
									$sensh ='Est';
								if($sensh)
								{
									if($Latitude_front > $data[3]+0.5)
										$sensv ='Sud';	
									elseif($Latitude_front < $data[3]-0.5)
										$sensv ='Nord';
								}
								else
								{
									if($Latitude_front > $data[3])
										$sensv ='Sud';
									elseif($Latitude_front < $data[3])
										$sensv ='Nord';
								}
								$sens=$sensv.' '.$sensh;
								$choix.="<option value='".$data[0]."'>".$data[1]." (".$sens." - ".$Distance[0]."km)</option>";
							}
						}
						mysqli_free_result($result);
					}
					if($choix)
					{
						 /*if($Front ==3)
							$carte_txt ="carte_pacifique.php";
						 elseif($Front ==2)
							$carte_txt ="carte_med_est.php";
						 elseif($Front ==4)
							$carte_txt ="carte_nord_est.php";
						 elseif($Front ==5)
							$carte_txt ="carte_arctic.php";
						 elseif($Front ==1)
							$carte_txt ="carte_sud_est.php";
						 else
							$carte_txt="carte_ouest.php";*/
						$carte_txt="carte_ground.php?map=".$Front."&mode=1";
						 $output.="<h2>Demande de transport maritime <a href='aide_transit.php' target='_blank' title='Attention, cette action immobilisera votre unité le temps du transit!'><img src='images/help.png'></a></h2>
						 <form action='index.php?view=ground_appui1' method='post'>
							<input type='hidden' name='Officier' value='".$OfficierID."'>
							<input type='hidden' name='reset' value='40'>
							<table class='table'>
								<thead><tr><th>Port de destination</th><th>Carte</th></tr></thead>
								<tr><td><select name='Cible' class='form-control' style='width:50%'><option value='0'>Aucun</option>".$choix."</select></td>
								<td><a href='".$carte_txt."' class='btn btn-primary' onclick='window.open(this.href); return false;'>Voir la carte</a></td></tr>
							</table><div class='alert alert-danger'>Attention, cette action immobilisera votre unité le temps du transit! Prenez contact avec le joueur contrôlant les navires AVANT de valider!</div><input type='Submit' value='VALIDER' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
					}
				}
			}
			elseif($g_mobile ==5 and $Credits >=4 and !$Transit)
			{
				if(($Port >0 and $Placement ==4 and $Faction_occupant_port == $Faction) or ($Plage >0 and $Placement ==8))
				{
					$wagons=0;
					$choix=false;
					$con=dbconnecti();
					$result_r=mysqli_query($con,"SELECT Vehicule_Nbr,Vehicule_ID,Fret FROM Regiment WHERE Officier_ID='$OfficierID'");
					mysqli_close($con);
					if($result_r)
					{
						while($data=mysqli_fetch_array($result_r))
						{
							if($data['Vehicule_ID'] ==5000 and !$data['Fret'])
								$wagons +=$data['Vehicule_Nbr'];
						}
						mysqli_free_result($result_r);
					}
					if($wagons >=4)
					{			
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT DISTINCT o.Nom,o.ID,l.Longitude,l.Latitude,l.Nom,o.Transit FROM Officier as o, Lieu as l, Regiment as r, Pays as p 
						WHERE r.Officier_ID=o.ID AND r.Pays=p.ID AND o.Barges_Lieu=l.ID AND o.Barges_Lieu >0 AND o.Front='$Front' AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_NUM))
							{
								if(!$data[5])
								{
									$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,$data[2],$data[3]);
									$choix.="<option value='".$data[1]."'>".$data[0]." (vers ".$data[4]." - ".$Distance[0]."km)</option>";
								}
							}
							mysqli_free_result($result);
						}		
						$output.="<h2>Transport naval <img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'></h2><form action='index.php?view=ground_appui1' method='post'>
							<input type='hidden' name='Officier' value='".$OfficierID."'>
							<input type='hidden' name='reset' value='50'>
							<table class='table'><thead><tr><th>Troupes à embarquer dans ce port <a href='#' class='popup'><img src='images/help.png'><span>Vous ne pouvez embarquer que des troupes de votre faction et de votre front</span></a></th></tr></thead>
								<tr><td><select name='Cible' class='form-control' style='width: 200px'><option value='0'>Aucune</option>".$choix."</select></td></tr></table>
							<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
					}
					else
						$output.="<p><b>Demande de transit impossible.</b><br>Vous devez posséder au moins 4 barges de transport vides pour transporter un bataillon</p>";
				}
			}
			//Navires End
			//Paras
			if($para and $Credits >=1)
			{
				$choix=false;
				$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Flag<>'$country' AND Zone<>6 AND ID<>'$Lieu' ORDER BY Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM))
					{
						$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,$data[2],$data[3]);
						if($Distance[0]<1000)
						{
							$sensh='';
							$sensv='';
							if($Longitude_front >$data[2])
								$sensh ='Ouest';
							elseif($Longitude_front <$data[2])
								$sensh ='Est';
							if($sensh)
							{
								if($Latitude_front >$data[3]+0.5)
									$sensv ='Sud';	
								elseif($Latitude_front <$data[3]-0.5)
									$sensv ='Nord';
							}
							else
							{
								if($Latitude_front >$data[3])
									$sensv ='Sud';
								elseif($Latitude_front <$data[3])
									$sensv ='Nord';
							}
							$sens=$sensv.' '.$sensh;
							$choix .="<option value='".$data[0]."'>".$data[1]." (".$sens." - ".$Distance[0]."km)</option>";
						}
					}
					mysqli_free_result($result);
				}
				if($choix)
				{
					$carte_txt="carte_ground.php?map=".$Front."&mode=1";
					$output.="<h2>Demande de parachutage <a href='aide_transit.php' target='_blank' title='Attention, cette action immobilisera votre unité le temps du transit!'><img src='images/help.png'></a></h2>
					 <form action='index.php?view=ground_appui1' method='post'>
						<input type='hidden' name='Officier' value='".$OfficierID."'>
						<input type='hidden' name='reset' value='60'>
						<table class='table'>
							<thead><tr><th>Destination</th><th>Zone</th><th>Carte</th></tr></thead>
							<tr><td><select name='Cible' class='form-control' style='width:50%'><option value='0'>Aucun</option>".$choix."</select></td><td>Caserne</td>
							<td><a href='".$carte_txt."' class='btn btn-primary' onclick='window.open(this.href); return false;'>Voir la carte</a></td></tr>
						</table><p class='lead'>Attention, cette action immobilisera votre unité le temps du transit! Prenez contact avec le joueur contrôlant l'unité de transport AVANT de valider!</p><input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
				}
			}
			//Paras End
			//Train
			if($g_mobile !=4 and $g_mobile !=5 and $Credits >=1 and $Placement ==3 and !$Enis_IA_zone)
			{
				$choix='';
				$txt_train='';
				$Range_train_max=200;
				$Lands=GetAllies($Date_Campagne);
				if(IsAxe($country))
					$Allies=$Lands[1];
				else
					$Allies=$Lands[0];
				if($Front ==2)
				{		
					if($country ==4)
					{
						if($Longitude_front >35)
							$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude < 36.5 AND Longitude > 35 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC LIMIT 1000";
						else
							$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude < 35 AND Longitude > 10 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,436,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC LIMIT 1000";
					}
					elseif($Latitude_front >36.5) //Grece
					{
						if($country ==2)
							$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude > 36.5 AND Latitude < 42 AND Longitude > 19 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC LIMIT 1000";
						else
						{
							if(GetData("Lieu","ID",1219,"Flag") !=2)
								$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude > 36.5 AND Latitude < 43.5 AND Longitude > 19 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC LIMIT 1000";
							else
								$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude > 39 AND Latitude < 43.5 AND Longitude > 19 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC LIMIT 1000";
						}
					}
					elseif($Latitude_front >31.45) //Syrie
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude < 36.5 AND Longitude > 31.45 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC LIMIT 1000";
					elseif($Latitude_front <33)
					{
						if($country ==2)
						{
							if(GetData("Lieu","ID",889,"Flag") !=2 and $Longitude_front >25)
							{
								$query="(SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude < 33 AND Longitude > 25 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653)) 
									UNION (SELECT l.ID,l.Nom,l.Longitude,l.Latitude,l.NoeudR,l.Impass FROM Event_Historique as e,Lieu as l WHERE e.Lieu=l.ID AND l.ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) AND l.Latitude <33 AND l.Longitude >25 AND l.Zone<>6 AND l.NoeudF >0
									AND (e.Type=40 AND e.Date BETWEEN '$Date_Campagne' - INTERVAL 2 DAY AND '$Date_Campagne' + INTERVAL 2 DAY)) ORDER BY Nom ASC LIMIT 1000";
							}
							else
							{
								$query="(SELECT l.ID,l.Nom,l.Longitude,l.Latitude,l.NoeudR,l.Impass FROM Lieu as l WHERE l.Latitude < 33 AND l.Longitude > 10 AND l.Zone<>6 AND l.Flag IN (".$Allies.") AND l.NoeudF >10 AND l.ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653)) 
									UNION (SELECT l.ID,l.Nom,l.Longitude,l.Latitude,l.NoeudR,l.Impass FROM Event_Historique as e,Lieu as l WHERE e.Lieu=l.ID AND l.ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) AND l.Latitude <33 AND l.Longitude >10 AND l.Zone<>6 AND l.NoeudF >0
									AND (e.Type=40 AND e.Date BETWEEN '$Date_Campagne' - INTERVAL 2 DAY AND '$Date_Campagne' + INTERVAL 2 DAY)) ORDER BY Nom ASC LIMIT 1000";
							}
						}
						else
						{
							if(GetData("Lieu","ID",889,"Flag") ==2 and $Longitude_front <25.2)
							{
								$query="(SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude <33 AND Longitude >10 AND Longitude <25.2 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653)) 
									UNION (SELECT l.ID,l.Nom,l.Longitude,l.Latitude,l.NoeudR,l.Impass FROM Event_Historique as e,Lieu as l WHERE e.Lieu=l.ID AND l.ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) AND l.Latitude <33 AND l.Longitude >10 AND l.Longitude <25 AND l.Zone<>6 AND l.NoeudF >0
									AND (e.Type=40 AND e.Date BETWEEN '$Date_Campagne' - INTERVAL 2 DAY AND '$Date_Campagne' + INTERVAL 2 DAY)) ORDER BY Nom ASC LIMIT 1000";
							}
							else
							{
								$query="(SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude <33 AND Longitude >10 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653)) 
									UNION (SELECT l.ID,l.Nom,l.Longitude,l.Latitude,l.NoeudR,l.Impass FROM Event_Historique as e,Lieu as l WHERE e.Lieu=l.ID AND l.ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) AND l.Latitude <33 AND l.Longitude >10 AND l.Zone <>6 AND l.NoeudF >0
									AND (e.Type=40 AND e.Date BETWEEN '$Date_Campagne' - INTERVAL 2 DAY AND '$Date_Campagne' + INTERVAL 2 DAY)) ORDER BY Nom ASC LIMIT 1000";
							}
						}
					}	
				}
				elseif($Front ==1 or $Front ==4)
				{
					$Range_train_max=500;
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude >=43 AND Longitude >13 AND Longitude <45 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',1252) ORDER BY Nom ASC";
				}
				elseif($Front ==5)
					$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Latitude >60 AND Longitude <67 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',1252) ORDER BY Nom ASC";
				else
				{
					if($Pays_Ori ==1 or $Pays_Ori ==3 or $Pays_Ori ==4 or $Pays_Ori ==5)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Pays<>2 AND Latitude >43 AND Longitude <14 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 
						AND ID NOT IN ('$Lieu',704,896) ORDER BY Nom ASC LIMIT 1000";
					elseif($Pays_Ori ==2)
						$query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass FROM Lieu WHERE Pays=2 AND Latitude >49 AND Longitude <14 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10  
						AND ID<>'$Lieu' ORDER BY Nom ASC LIMIT 1000";
				}
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM))
					{
						$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,$data[2],$data[3]);
						if($Distance[0] <$Range_train_max)
						{
							$sensh='';
							$sensv='';
							if($Longitude_front > $data[2])
								$sensh='Ouest';
							elseif($Longitude_front < $data[2])
								$sensh='Est';
							if($sensh)
							{
								if($Latitude_front > $data[3] +0.5)
									$sensv='Sud';	
								elseif($Latitude_front < $data[3] -0.5)
									$sensv='Nord';
							}
							else
							{
								if($Latitude_front > $data[3])
									$sensv='Sud';
								elseif($Latitude_front < $data[3])
									$sensv='Nord';
							}
							$sens=$sensv.' '.$sensh;
							$choix.="<option value='".$data[0]."'>".$data[1]." (".$sens.")</option>";
						}
					}
					mysqli_free_result($result);
				}
				if($choix)
				{
					$carte_txt="carte_ground.php?map=".$Front."&mode=1";
					 $output.="<h2>Demande de transport ferroviaire <a href='aide_transit.php' target='_blank' title='Attention, cette action immobilisera votre unité le temps du transit!'><img src='images/help.png'></a></h2>
					 <form action='index.php?view=ground_appui1' method='post'>
						<input type='hidden' name='Officier' value='".$OfficierID."'>
						<input type='hidden' name='reset' value='20'>
						<table class='table'>
							<thead><tr><th>Gare de destination</th><th>Carte</th></tr></thead>
							<tr><td><select name='Cible' class='form-control' style='width:50%'><option value='0'>Aucun</option>".$choix."</select></td>
							<td><a href='".$carte_txt."' class='btn btn-primary' onclick='window.open(this.href); return false;'>Voir la carte</a></td></tr>
						</table><p class='lead'>Attention, cette action immobilisera votre unité le temps du transit! Prenez contact avec le joueur contrôlant le train AVANT de valider!</p><input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
				}
			}
			elseif($NoeudF >10 and $g_mobile ==4 and $Credits >=24 and $Placement ==3)
			{		
				$train=true;
				$regi=1;
				$wagons=0;
				$con=dbconnecti();
				$result_r=mysqli_query($con,"SELECT r.Vehicule_Nbr,c.mobile,c.Charge,c.Categorie FROM Regiment as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Officier_ID='$OfficierID'");
				mysqli_close($con);
				if($result_r)
				{
					while($data=mysqli_fetch_array($result_r))
					{
						if($regi ==1)
						{
							if($data['Vehicule_Nbr'] < 1 or $data['mobile'] !=4 or $data['Categorie'] !=13)
							{
								$train=false;
								break;
							}
						}
						else
						{
							if($data['Categorie'] ==12)
								$wagons+=$data['Vehicule_Nbr'];
						}
						$regi++;
					}
					mysqli_free_result($result_r);
				}			
				if($train and $wagons >7)
				{			
					if($Front ==1 or $Front ==4)
						$Range_train_max=500;
					else
						$Range_train_max=200;
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT o.Nom,o.ID,l.Longitude,l.Latitude,l.Nom FROM Officier as o, Lieu as l WHERE o.Pays='$country' AND o.Front='$Front' AND o.Train_Lieu >0 AND o.Train_Lieu=l.ID AND l.NoeudF >10");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_NUM))
						{
							$Distance=GetDistance(0,0,$Longitude_front,$Latitude_front,$data[2],$data[3]);
							if($Distance[0] <$Range_train_max)
							{
								$choix.="<option value='".$data[1]."'>".$data[0]." (".$data[4]." - ".$Distance[0]."km)</option>";
							}
						}
						mysqli_free_result($result);
					}			
					$output.="<h2>Transport ferroviaire <img src='/images/CT24.png' title='Montant en Crédits Temps que nécessite cette action'>
					<a href='aide_transit.php' target='_blank' title='Attention, cette action immobilisera votre unité le temps du transit!'><img src='images/help.png'></a></h2><form action='index.php?view=ground_appui1' method='post'>
						<input type='hidden' name='Officier' value='".$OfficierID."'>
						<input type='hidden' name='reset' value='30'>
						<table class='table'>
							<tr><td><a href='".$carte_txt."' onclick='window.open(this.href); return false;'>Voir la carte</a></td>
							<th align='left'>Vers la gare de <select name='Cible' class='form-control' style='width: 200px'><option value='0'>Aucun</option>".$choix."</select> <input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></th></tr>
						</table></form>";
				}
				else
					$output.="<p>Vous devez posséder une locomotive en état de marche, ainsi que 8 wagons de transport de troupes pour transporter un bataillon</p>";
			}
		}
		else
			$output.="<p>Votre bataillon est décimé!</p>";
		echo $output;
	}
	else
	{
		echo "<h1>Transmissions</h1>";
		echo Afficher_Image('images/transfer_no'.$country.'.jpg',"images/image.png","Refus",25);
		echo "<div class='alert alert-danger'>Votre nation doit être en guerre et <b>vous devez faire partie d'une division ou d'une flotte</b> pour bénéficier de ce service!<br>Si votre unité se trouve <b>en transit</b>, les transmissions ne sont pas accessibles.</div>";
	}
}*/
?>