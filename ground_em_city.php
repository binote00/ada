<?php
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier_em'];
if($OfficierID >0)
{	
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Front=GetData("Officier_em","ID",$OfficierID,"Front");
	$con=dbconnecti();	
	$result2=mysqli_query($con,"SELECT Commandant,Adjoint_Terre,Officier_Mer FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
	mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Commandant=$data['Commandant'];
			$Adjoint_Terre=$data['Adjoint_Terre'];
			$Officier_Mer=$data['Officier_Mer'];
		}
		mysqli_free_result($result2);
	}	
	if($OfficierID ==$Commandant or $OfficierID ==$Adjoint_Terre or $OfficierID ==$Officier_Mer) and $Front !=12)
	{
		$Cible=Insec($_GET['id']);
		if($Cible)
		{
			$con=dbconnecti();
			$Cible=mysqli_real_escape_string($con,$Cible);
			$result=mysqli_query($con,"SELECT Nom,Zone,Map,Meteo,Latitude,Longitude,Occupant,Plage,ValeurStrat,DefenseAA_temp,BaseAerienne,QualitePiste,LongPiste,Industrie,Pont,Radar,Port,NoeudR,NoeudF,Recce,Flag,Garnison,Fortification,Oil,
			Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,Stock_Munitions_40,Stock_Munitions_50,Stock_Munitions_60,
			Stock_Munitions_75,Stock_Munitions_90,Stock_Munitions_105,Stock_Munitions_125,Stock_Munitions_150,Stock_Munitions_200,Stock_Munitions_300,Stock_Munitions_360,
			Stock_Bombes_30,Stock_Bombes_50,Stock_Bombes_80,Stock_Bombes_125,Stock_Bombes_250,Stock_Bombes_300,Stock_Bombes_400,Stock_Bombes_500,Stock_Bombes_800,Stock_Bombes_1000,Stock_Bombes_2000,Recce_PlayerID,Recce_PlayerID_TAL,Recce_PlayerID_TAX
			FROM Lieu WHERE ID='$Cible'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Cible_nom=$data['Nom'];
					$Cible_DefenseAA=$data['DefenseAA_temp'];
					$Lat=$data['Latitude'];
					$Long=$data['Longitude'];
					$ValeurStrat=$data['ValeurStrat'];
					$Cible_base=$data['BaseAerienne'];
					$QualitePiste=$data['QualitePiste'];
					$Piste=$data['LongPiste'];
					$Usine=$data['Industrie'];
					$Pont=$data['Pont'];
					$Port=$data['Port'];
					$Radar=$data['Radar'];
					$NoeudR=$data['NoeudR'];
					$NoeudF=$data['NoeudF'];
					$Zone=$data['Zone'];
					$Map=$data['Map'];
					$Occupant=$data['Occupant'];
					$Recce=$data['Recce'];
					$Flag=$data['Flag'];
					$Garnison=$data['Garnison'];
					$Fortification=$data['Fortification'];
					$Oil=$data['Oil'];
					$Meteo=$data['Meteo'];
					$Plage=$data['Plage'];
					$Recce_PlayerID=$data['Recce_PlayerID'];
					$Recce_PlayerID_TAL=$data['Recce_PlayerID_TAL'];
					$Recce_PlayerID_TAX=$data['Recce_PlayerID_TAX'];
					if($Flag)
						$Rev="<img src='images/flag".$Flag."p.jpg' title='".GetPays($Flag)."'>";
					else
						$Rev="N/A";
					$Zone_txt=GetZone($Zone);
					$Region='Zone '.$Zone_txt;
					$Valstrat_icon="<img src='images/strat".$ValeurStrat.".png'>";
					if($ValeurStrat >3)
					{
						if($Admin ==1)
						$depot="<table class='table table-striped'>
								<thead><tr><th colspan='2'>Dépôt de ".$data['Nom']."</th></tr></thead>
								<tr><th>Essence 87 Octane</th><td >".$data['Stock_Essence_87']."</td></tr>
								<tr><th>Essence 100 Octane</th><td >".$data['Stock_Essence_100']."</td></tr>
								<tr><th>Essence 130 Octane</th><td >0</td></tr>
								<tr><th>Diesel</th><td >".$data['Stock_Essence_1']."</td></tr>
								<tr><th>Munitions 8mm</th><td >".$data['Stock_Munitions_8']."</td></tr>
								<tr><th>Munitions 13mm</th><td >".$data['Stock_Munitions_13']."</td></tr>
								<tr><th>Munitions 20mm</th><td >".$data['Stock_Munitions_20']."</td></tr>
								<tr><th>Munitions 30mm</th><td >".$data['Stock_Munitions_30']."</td></tr>
								<tr><th>Munitions 40mm</th><td >".$data['Stock_Munitions_40']."</td></tr>
								<tr><th>Munitions 50mm</th><td >".$data['Stock_Munitions_50']."</td></tr>
								<tr><th>Munitions 60mm</th><td >".$data['Stock_Munitions_60']."</td></tr>
								<tr><th>Munitions 75mm</th><td >".$data['Stock_Munitions_75']."</td></tr>
								<tr><th>Munitions 90mm</th><td >".$data['Stock_Munitions_90']."</td></tr>
								<tr><th>Munitions 105mm</th><td >".$data['Stock_Munitions_105']."</td></tr>
								<tr><th>Munitions 125mm</th><td >".$data['Stock_Munitions_125']."</td></tr>
								<tr><th>Munitions 150mm</th><td >".$data['Stock_Munitions_150']."</td></tr>
								<tr><th>Bombes 50kg</th><td >".$data['Stock_Bombes_50']."</td></tr>
								<tr><th>Bombes 125kg</th><td >".$data['Stock_Bombes_125']."</td></tr>
								<tr><th>Bombes 250kg</th><td >".$data['Stock_Bombes_250']."</td></tr>
								<tr><th>Bombes 500kg</th><td >".$data['Stock_Bombes_500']."</td></tr>
								<tr><th>Bombes 1000kg</th><td >".$data['Stock_Bombes_1000']."</td></tr>
								<tr><th>Bombes 2000kg</th><td >".$data['Stock_Bombes_2000']."</td></tr>
								<tr><th>Charges de profondeur</th><td >".$data['Stock_Bombes_300']."</td></tr>
								<tr><th>Mines</th><td >".$data['Stock_Bombes_400']."</td></tr>
								<tr><th>Torpilles</th><td >".$data['Stock_Bombes_800']."</td></tr>
								<tr><th>Fusées éclairantes</th><td >".$data['Stock_Bombes_30']."</td></tr>
								<tr><th>Rockets</th><td >".$data['Stock_Bombes_80']."</td></tr>
								</table>";
								/*<tr><th>Munitions 200mm</th><td >".$data['Stock_Munitions_200']."</td></tr>
								<tr><th>Munitions 300mm</th><td >".$data['Stock_Munitions_300']."</td></tr>
								<tr><th>Munitions 360mm</th><td >".$data['Stock_Munitions_360']."</td></tr>*/
					}
					elseif(!$ValeurStrat)
						$Valstrat_icon="Aucune";
				}
				mysqli_free_result($result);
				unset($data);
			}			
			if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
				$img='images/lieu/lieu'.$Cible.'.jpg';
			else
			{
				if($Nuit)
					$img='images/lieu/objectif_nuit'.$Map.'.jpg';
				elseif($Zone ==8)
				{
					if($Map ==0 or $Map ==1)
						$img='images/dune_sea.jpg';
					elseif($Map ==2 or $Map ==3)
						$img='images/desert_airfield.jpg';
				}
				elseif($Zone ==9)
				{
					if($Map ==0 or $Map ==1)
						$img='images/jungle.jpg';
					elseif($Map ==2 or $Map ==3)
						$img='images/pacific_airfield.jpg';
					elseif($Map ==8)
						$img='images/jungle_port.jpg';
				}
				if(!$img)
					$img='images/lieu/objectif'.$Map.'.jpg';
			}			
			if($Flag == $country or $Admin ==1)
			{
				$dca="<img src='images/vehicules/vehicule16.gif' title='DCA niveau ".$Cible_DefenseAA."'><br>";
				$Garnison="<img src='images/vehicule107.gif'> ".$Garnison;
				if($Fortification)
					$Fortification="<img src='images/icone_fort.gif' title='Fortifications niveau ".$Fortification."'>";
				if($Cible_base)
					$icones.="<img src='images/vehicules/vehicule4005.gif' title='Base aérienne ".$Piste."m - ".$QualitePiste."%'>";
				if($NoeudF)
					$icones.="<img src='images/vehicules/vehicule9.gif' title='Gare'>"; 
				if($Pont)
					$icones.="<img src='images/vehicules/vehicule10.gif' title='Pont'>"; 
				if($Port)
					$icones.="<img src='images/vehicules/vehicule12.gif' title='Port'>"; 
				if($Oil)
					$icones.="<img src='images/map/icone_oil.gif' title='Raffinerie'>"; 
				if($Radar)
					$icones.="<img src='images/vehicules/vehicule15.gif' title='Radar'>"; 
			}
			else
			{
				$Garnison="Inconnu";
				$Fortification="Inconnu";
			}			
			$air_units='';
			if($Admin ==1)
				$query_units="SELECT ID,Nom,Pays,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE Base='$Cible'";
			else
				$query_units="SELECT ID,Nom,Pays,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE Base='$Cible' AND Pays='$country' AND Etat=1";
			$con=dbconnecti();
			$result=mysqli_query($con,$query_units);
			mysqli_close($con);
			if($result)
			{
				/*if($Zone ==6)
					$Base_txt="Porte-avions";
				else
					$Base_txt="Base aérienne";
				$air_units.="<tr><th colspan='15'>".$Base_txt."</th></tr>";*/
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Nbr=$data['Avion1_Nbr']+$data['Avion2_Nbr']+$data['Avion3_Nbr'];
					$air_units.="<tr><td><img src='images/unit/unit".$data['ID']."p.gif' title='".$data['Nom']."'></td>
					<td><img src='".$data['Pays']."20.gif'></td><td>".$Nbr."</td>
					<td><img src='images/avions/avion".$data['Avion1'].".gif'> <img src='images/avions/avion".$data['Avion2'].".gif'> <img src='images/avions/avion".$data['Avion3'].".gif'></td><td></td></tr>";		
				}
				mysqli_free_result($result);
			}			
			$units='';
			if($Admin ==1)
				$query="SELECT r.ID,r.Pays,r.Vehicule_ID,r.Vehicule_Nbr,r.Placement,r.Position,r.Experience,r.Visible,r.Officier_ID,c.Nom FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' ORDER BY r.Placement ASC,r.ID ASC";
				//(SELECT r.ID,r.Pays,r.Vehicule_ID,r.Vehicule_Nbr,r.Placement,r.Position,r.Experience,r.Visible,r.Officier_ID,c.Nom FROM Regiment as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' ORDER BY r.Placement ASC, r.ID ASC) 
			else
				$query="SELECT r.ID,r.Pays,r.Vehicule_ID,r.Vehicule_Nbr,r.Placement,r.Position,r.Experience,r.Visible,r.Officier_ID,c.Nom FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Lieu_ID='$Cible' ORDER BY r.Placement ASC,r.ID ASC";
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result))
				{
					if($data['Officier_ID'] >0)
						$Officier_Nom=GetData("Officier","ID",$data['Officier_ID'],"Nom");
					else
						$Officier_Nom="Officier IA";
					if($data['Visible'])
						$Visible="Visible";
					else
						$Visible="Camouflé";
					if($Admin ==1)
						$exp=" (".$Visible." - ".GetPosGr($data['Position'])." - ".$data['Experience']."XP - ".$Officier_Nom.")";
					else
						$exp=$Visible." - ".GetPosGr($data['Position'])." - ".$data['Experience']."XP - ".$Officier_Nom;
					if($Admin ==1 and $data['Officier_ID'] ==0)
						$Ordres="<form action='ground_em_ia.php' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'>
						<input type='Submit' value='Ordres' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
					if($data['Placement'] != $Placement_Ori)
					{
						$Placement=GetPlace($data['Placement']);
						$titre="<tr bgcolor='tan'><th colspan='15'>".$Placement."</th></tr>";
					}
					else
						$titre="";
					$Placement_Ori=$data['Placement'];
					$units.=$titre;
					$units.="<tr><td align='left'>".$data['ID']."e Compagnie</td>
					<td><img src='".$data['Pays']."20.gif'></td>
					<td>".$data['Vehicule_Nbr']."</td>
					<td><img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Nom']."'>".$exp."</td><td>".$Ordres."</td>
					</tr>";		
				}
				mysqli_free_result($result);
			}
			else
			{
				$mes.="Désolé, aucune unité terrestre recensée.";
			}
			if($Admin ==1)
			{
				$dca_pieces="<h2>Composition de la défense anti-aérienne de l'aérodrome</h2><table class='table'>
					<thead><tr><th>Type</th><th>Nombre</th><th>Altitude</th><th>Expérience</th></tr></thead>";
				$con=dbconnecti();
				$dca_res=mysqli_query($con,"SELECT DCA_ID,DCA_Nbr,DCA_Exp,Alt,Unit FROM Flak WHERE Lieu='$Cible'");
				mysqli_close($con);
				if($dca_res)
				{
					while($data_flak=mysqli_fetch_array($dca_res,MYSQLI_ASSOC))
					{
						$DCA_ID=$data_flak['DCA_ID'];
						$DCA_Nbr=$data_flak['DCA_Nbr'];
						$DCA_Exp=floor($data_flak['DCA_Exp']);
						$DCA_Alt=$data_flak['Alt'];
						$DCA_Nom=GetData("Armes","ID",$DCA_ID,"Nom");
						$dca_pieces .="<tr><td><img src='images/aa".$DCA_ID.".png' title='".$DCA_Nom."'><td>".$DCA_Nbr."</td><td>".$DCA_Alt."m</td><td>".$DCA_Exp."</td></tr>";
					}
					mysqli_free_result($dca_res);
				}
				$dca_pieces.="</table>";
			}
			if($Plage)
				$Plage_txt="<img src='images/plage.jpg' title='Zone propice au débarquement'>";
			else
				$Plage_txt=$Region;
			if($Admin ==1)$Admin_txt="Long=".$Long." / Lat=".$Lat." / Reco=".$Recce." (".$Recce_PlayerID." - ".$Recce_PlayerID_TAL." - ".$Recce_PlayerID_TAX.")<br>";			
			$titre=$Cible_nom;
			$img=Afficher_Image($img,$img,$Cible_nom);
			$intro="<table class='table'><thead><tr><th>Occupation</th><th>Revendication</th><th>Valeur stratégique</th><th>Terrain</th><th>Météo</th></tr></thead>
			<tr><td><img src='images/flag".$Occupant."p.jpg' title='".GetPays($Occupant)."'></td><td>".$Rev."</td><td>".$Valstrat_icon."</td><td><img src='images/zone".$Zone.".jpg' title='".$Region."'><br>".$Plage_txt."</td>
			<td><img src='images/meteo".$Meteo.".jpg' title='". $Meteo_txt."'></td></tr></table>";
			$mes.="<table class='table'><thead><tr><th>DCA</th><th>Garnison <a href='aide_garnison.php' target='_blank' title='Aide'><img src='images/help.png'></a></th><th>Fortification</th><th>Infrastructures</th></tr></thead>
			<tr><td>".$dca."</td><td>".$Garnison."</td><td>".$Fortification."</td><td>".$icones."</td></tr></table>";
			if($Admin ==1)$mes.=$Admin_txt;
			if($air_units or $units)
			{
				$mes.="<table class='table table-striped'>
					<thead><tr><th width='200px'>Unité</th><th width='50px'>Nation</th><th width='50px'>Effectifs</th><th>Troupes</th><th>Ordres</th></tr></thead>
					".$air_units.$units."</table>";
			}
			$mes.=$menu.$dca_pieces.$depot.$footer;
		}
		else
			$mes="Tsss!";
	}
	else
		$mes="<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	include_once('./default_blank.php');
}?>