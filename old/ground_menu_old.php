<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_map.inc.php');
include_once('./jfv_nav.inc.php');
include_once('./jfv_ground.inc.php');
include_once('./jfv_txt.inc.php');

$PlayerID = $_SESSION['Officier'];

if($PlayerID > 0)
{	
	$country = $_SESSION['country'];
	$Faction = GetData("Pays","ID",$country,"Faction");
	$Date_Campagne = GetData('Conf_Update','ID',2,'Date');
	//GetData Player
	$con = dbconnecti();
	$result = mysqli_query($con, "SELECT Credits,Avancement,Front,Division,Trait,Atk FROM Officier WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		if($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$Credits = $data['Credits'];
			$Avancement = $data['Avancement'];
			$Front = $data['Front'];
			$Division = $data['Division'];
			$Trait = $data['Trait'];
			$Atk_mode = $data['Atk'];
		}
		mysqli_free_result($result);
	}
	/*Operation Merkur
	if($Date_Campagne > '1941-05-19' and $Date_Campagne < '1941-06-01' and ($Division == 47 or $Division == 35))
	{
		$Drop = false;
		if($Division == 47)
		{
			$Drop_zone = 1090;
			$Drop_date = "2013-09-20";
		}
		else
		{
			$Drop_zone = 903;
			$Drop_date = "2013-09-21";
		}
		$con = dbconnecti();
		$result = mysqli_query($con, "SELECT DISTINCT COUNT(*) FROM Parachutages WHERE Date > '2013-09-19' AND Lieu='$Drop_zone'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result))
			{
				if($data[0] > 3)
					$Drop = true;
			}
			mysqli_free_result($result);
		}
		if($Drop)
		{
			SetData("Regiment","Lieu_ID",$Drop_zone,"Officier_ID",$PlayerID);
			$Cible = $Drop_zone;
		}
		else
			$Cible = GetData("Regiment","Officier_ID",$PlayerID,"Lieu_ID");
	}
	else*/
	$Cible = GetData("Regiment","Officier_ID",$PlayerID,"Lieu_ID");
	$Placement = GetData("Regiment","Officier_ID",$PlayerID,"Placement");
	$Latitude_front = GetData("Lieu","ID",$Cible,"Latitude");
	$Longitude_front = GetData("Lieu","ID",$Cible,"Longitude");
	if($Division > 0)
	{
		$con = dbconnecti();
		$result = mysqli_query($con, "SELECT Base,repli,rally,atk,hatk,def,ravit,Cdt FROM Division WHERE ID='$Division'");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$Retraite = $data['Base'];
				$repli = $data['repli'];
				$rally = $data['rally'];
				$ravit = $data['ravit'];
				$atk = $data['atk'];
				$hatk = $data['hatk'];
				$def = $data['def'];
				$Division_Cdt = $data['Cdt'];
			}
			mysqli_free_result($result);
		}
		if($repli > 0)
			$repli = GetData("Lieu","ID",$repli,"Nom");
		else
			$repli = "Aucun";
		if($rally > 0)
			$rally = GetData("Lieu","ID",$rally,"Nom");
		else
			$rally = "Aucun";
		if($atk > 0)
			$atk = GetData("Lieu","ID",$atk,"Nom");
		else
			$atk = "Aucun";
		if($def > 0)
			$def = GetData("Lieu","ID",$def,"Nom");
		else
			$def = "Aucun";
		if($ravit > 0)
			$ravit = GetData("Lieu","ID",$ravit,"Nom");
		else
			$ravit = "Aucun";
			
		if($country == 20 or $Latitude_front > 59.35)
		{
			$carte_txt = "<a href='carte_finland.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Acc�der � la carte'></a>";
		}elseif($Front == 2)
		{
			$carte_txt = "<a href='carte_med_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Acc�der � la carte'></a>";
		}elseif($Front == 3)
		{
			$carte_txt = "<a href='carte_pacifique.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Acc�der � la carte'></a>";
		}elseif($Front == 1 and $Latitude_front >= 52)
		{
			$carte_txt = "<a href='carte_nord_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Acc�der � la carte'></a>";
		}elseif($Front == 1 and $Latitude_front < 52)
		{
			$carte_txt = "<a href='carte_sud_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Acc�der � la carte'></a>";
		}else
		{
			$carte_txt = "<a href='carte_ouest.php' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Acc�der � la carte'></a>";
		}
		
		$Cdt_orders = "<div id='col_droite'><table class='table table-striped'>
			<thead><tr><th colspan='2'>Ordres du Commandant</th></tr></thead>
			<tr><td align='left'>Point de repli</td><td>".$repli."</td></tr>
			<tr><td align='left'>Point de ralliement</td><td>".$rally."</td></tr>
			<tr><td align='left'>Point de ravitaillement</td><td>".$ravit."</td></tr>
			<tr><td align='left'>Objectif � d�fendre</td><td>".$def."</td></tr>
			<tr><td align='left'>Objectif � attaquer</td><td>".$atk."</td></tr>
			<tr><td align='left'>Heure de l'attaque</td><td>".$hatk."h</td></tr>
			<tr><td align='center' colspan='2'>".$carte_txt."</td></tr>
			</table></div>";
	}
	else
	{
		$Cdt_orders = "<div id='col_droite'><table class='table table-striped'>
		<thead><tr><th colspan='2'>Ordres du Commandant</th></tr></thead>
		<tr><td align='left'>De nouvelles options apparaitront si vous faites partie d'une division.<br>Vous pouvez postuler via <a href='index.php?view=ground_profile' class='btn btn-default'>le profil de votre officier</a>.</td></table></div>";
		if($Front == 3)
		{
			if($country == 2)
			{
				$Retraite = 1572;
			}
			elseif($country == 7)
			{
				$Retraite = 1366;
			}
			elseif($country == 9)
			{
				$Retraite = 1368;
			}
		}
		elseif($Front == 2)
		{
			if($Latitude_front > 36.5)
			{
				$Latitude_rav_max = 43.5;		
				$Latitude_rav_min = 37;
				$Longitude_rav = 19;
				if($country == 2)
				{
					$Retraite = 689;
					$country = 10;
				}
				elseif($country == 1)
				{
					$Retraite = 709;
					$country = 15;
				}
				elseif($country == 6)
					$Retraite = 451;		
				elseif($country == 4)
				{
					$Latitude_rav_max = 37;		
					$Latitude_rav_min = 29;
					$Longitude_rav = 10;
					$Retraite = 433;
				}
			}
			elseif($Latitude_front < 33)
			{
				$Latitude_rav_max = 37;		
				$Latitude_rav_min = 29;
				$Longitude_rav = 10;
				if($country == 2)
					$Retraite = 521;
				elseif($country == 1 or $country == 6)
				{
					$Retraite = 453;
					$country = 6;
				}
				elseif($country == 4)
					$Retraite = 433;				
			}
			else
			{
				if($country == 2)
					$Retraite = 521;
				elseif($country == 1 or $country == 6)
				{
					$Retraite = 453;
					$country = 6;
				}
				elseif($country == 4)
					$Retraite = 433;	
			}
		}
		elseif($Front == 1)
		{
			if($country == 1 or $country == 6)
			{
				if($Latitude_front < 54)
					$Retraite = 1000;
				elseif($Latitude_front > 54)
					$Retraite = 613;
				else
					$Retraite = 2;
			}
			elseif($country == 8)
			{
				if($Latitude_front < 52)
					$Retraite = 618;
				elseif($Latitude_front > 52)
					$Retraite = 614;
				else
					$Retraite = 601;
			}
			elseif($country == 6)
			{
				$Retraite = 683;
				$country = 1;
			}
			elseif($country == 20)
				$Retraite = 1419;
		}
	}
		
	//GetData Lieu
	$con = dbconnecti();
	$result2 = mysqli_query($con, "SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Occupant='$country' AND Latitude < '$Latitude_rav_max' AND Latitude > '$Latitude_rav_min' AND Longitude > '$Longitude_rav' AND NoeudF_Ori > 0");
	$result = mysqli_query($con, "SELECT Nom,Zone,Map,Meteo,Latitude,Occupant,ValeurStrat,DefenseAA_temp,BaseAerienne,Industrie,Pont_Ori,Pont,Radar,Port,Port_Ori,NoeudR,NoeudF,NoeudF_Ori,Mines,Mines_m,Garnison,Recce,Flag,Plage,Detroit FROM Lieu WHERE ID='$Cible'");
	mysqli_close($con);
	//Malus mouvement par rail
	if($result2)
	{
		if($data=mysqli_fetch_array($result2, MYSQLI_NUM))
		{
			if($data[0] > 0)
				$Efficacite_ravit = round($data[1]/$data[0]);
			else
				$Efficacite_ravit = 0;
		}
		mysqli_free_result($result2);
	}
	//Lieu
	if($result)
	{
		while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$Cible_nom = $data['Nom'];
			$Cible_DefenseAA = $data['DefenseAA_temp'];
			$ValeurStrat = $data['ValeurStrat'];
			$Cible_base = $data['BaseAerienne'];
			$Usine = $data['Industrie'];
			$Pont = $data['Pont'];
			$Pont_Ori = $data['Pont_Ori'];
			$Port = $data['Port'];
			$Port_Ori = $data['Port_Ori'];
			$Radar = $data['Radar'];
			$NoeudR = $data['NoeudR'];
			$NoeudF = $data['NoeudF'];
			$NoeudF_Ori = $data['NoeudF_Ori'];
			$Zone = $data['Zone'];
			$Map = $data['Map'];
			$Plage = $data['Plage'];
			$Detroit = $data['Detroit'];
			$Occupant = $data['Occupant'];
			$Mines = $data['Mines'];
			$Mines_m = $data['Mines_m'];
			$Recce = $data['Recce'];
			$Flag = $data['Flag'];
			$Garnison = $data['Garnison'];
			//Infos Terrain, M�t�o
			if(!$data['Meteo'])
			{
				$Meteo = GetMeteo($_SESSION['Saison'], 0, $data['Latitude'], 0, 2000);
				SetData("Lieu","Meteo",$Meteo[1], "ID", $Cible);
			}
			else
			{
				$Meteo = GetMeteo($_SESSION['Saison'], 0, $data['Latitude'], $data['Meteo']);
			}
			$Meteo_malus = $Meteo[1];
			$Meteo_txt = $Meteo[0];
			$Zone_txt = GetZone($Zone);
			$Region= 'zone '.$Zone_txt;
		}
		mysqli_free_result($result);
		unset($result);
		unset($data);
	}
	$Faction_occupant = GetData("Pays","ID",$Occupant,"Faction");
	
	if(is_file('images/lieu'.$Cible.'.jpg'))
	{
		$img = '<img src=\'images/lieu'.$Cible.'.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
	}
	else
	{
		if($Nuit)
			$img = '<img src=\'images/objectif_nuit'.$Map.'.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
		elseif($Zone == 8)
		{
			if($Map == 0 or $Map == 1)
				$img = '<img src=\'images/dune_sea.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
			elseif($Map == 2 or $Map == 3)
				$img = '<img src=\'images/desert_airfield.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
		}
		elseif($Zone == 9)
		{
			if($Map == 0 or $Map == 1)
				$img = '<img src=\'images/jungle.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
			elseif($Map == 2 or $Map == 3)
				$img = '<img src=\'images/pacific_airfield.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
			elseif($Map == 8)
				$img = '<img src=\'images/jungle_port.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
		}
		elseif($Front == 3)
		{
			if($Map == 2)
				$img = '<img src=\'images/pacific_airfield.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
		}
		if(!$img)
			$img = '<img src=\'images/objectif'.$Map.'.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
	}
	
	$icone_long = 200;
	$icone_occupant = "<img src='images/flag".$Occupant."p.jpg' title='".GetPays($Occupant)."'>";
	$icones = "<b>".$Cible_nom."</b>";
	if($Mines_m > 0)
	{
		$icones .= "<img src='images/mine_navale.gif' title='Champ de Mines ralentissant vos d�placements'>";
		$icone_long += 25;
	}
	if($Faction_occupant == $Faction)
	{
		if($Mines)
		{
			$title = "Champ de Mines ".GetPlace($Mines);
			$icones .= "<br><img src='images/mines.gif' title='".$title."'>";
			$icone_long += 25;
		}
		if($NoeudF)
		{
			$icones .= "<br><img src='images/vehicule9.gif' title='Gare'>"; 
			$icone_long += 25;
		}
		if($Pont)
		{
			$icones .= "<br><img src='images/vehicule10.gif' title='Pont'>"; 
			$icone_long += 25;
		}
		if($Port)
		{
			$icones .= "<br><img src='images/vehicule12.gif' title='Port'>"; 
			$icone_long += 25;
		}
		if($Radar)
		{
			$icones .= "<br><img src='images/vehicule15.gif' title='Radar'>";
			$icone_long += 25;
		}
		if($Cible_base)
		{
			$icones .= "<br><img src='images/vehicule4005.gif' title='Base a�rienne'>"; 
			$icone_long += 25;
		}
	}
	
	if($Flag > 0)
	{
		$Rev = "<img src='images/flag".$Flag."p.jpg'>";
		$Faction_flag = GetData("Pays","ID",$Flag,"Faction");
	}
	else
		$Faction_flag = $Faction_occupant;
		
	/*
<tr><td><a href='index.php?view=ground_profile'><img src="images/dossier.gif" title="Acc�der au profil de votre officier"></a>
		 <a href='ground_journal.php'><img src="images/news.gif" title="Acc�der aux nouvelles"></a>
		 <?if($country == 20 or $Latitude_front > 59.35){?>
		 <a href='carte_finland.php?longit=<?echo $Longitude_front;?>&latit=<?echo $Latitude_front;?>' target='_blank'><img src="images/map_icon.gif" title="Acc�der � la carte"></a>
		 <?}elseif($Front == 2){?>
		 <a href='carte_med_est.php?longit=<?echo $Longitude_front;?>&latit=<?echo $Latitude_front;?>' target='_blank'><img src="images/map_icon.gif" title="Acc�der � la carte"></a>
		 <?}elseif($Front == 3){?>
		 <a href='carte_pacifique.php?longit=<?echo $Longitude_front;?>&latit=<?echo $Latitude_front;?>' target='_blank'><img src="images/map_icon.gif" title="Acc�der � la carte"></a>
		 <?}elseif($Front == 1 and $Latitude_front >= 52){?>
		 <a href='carte_nord_est.php?longit=<?echo $Longitude_front;?>&latit=<?echo $Latitude_front;?>' target='_blank'><img src="images/map_icon.gif" title="Acc�der � la carte"></a>
		 <?}elseif($Front == 1 and $Latitude_front < 52){?>
		 <a href='carte_sud_est.php?longit=<?echo $Longitude_front;?>&latit=<?echo $Latitude_front;?>' target='_blank'><img src="images/map_icon.gif" title="Acc�der � la carte"></a>
		 <?}else{?>
		 <a href='carte_ouest.php' target='_blank'><img src="images/map_icon.gif" title="Acc�der � la carte"></a>
		 <?}?>
		 <a href='ground_appui.php'><img src="images/telephone.gif" title="Demander un appui"></a>
		 <a href='ground_em.php'><img src="images/general.gif" title="Acc�der aux fonctions Etat-Major"></a>
		 <a href='aide_blitz.php' target='_blank'><img src="images/manuel.gif" title="Acc�der � l'aide"></a></td></tr>	*/
?>
	<h1><?echo $Cible_nom;?></h1>
	<div class='row'>
	<div class='col-md-6'><table class='table'><thead><tr><th>Occupation</th><th>Revendication</th><th>Valeur strat�gique</th><th>Terrain</th><th>M�t�o</th></tr></thead>
	<tr><td><?echo $icone_occupant;?></td><td><?echo $Rev;?></td><td><img src='images/strat<?echo $ValeurStrat;?>.png'></td><td><img src="images/zone<?echo $Zone;?>.jpg" title="<?echo $Region;?>"><br><?echo $Region;?></td>
	<td><img src='images/meteo<?echo $Meteo_malus;?>.jpg' title='<?echo $Meteo_txt;?>'></td></tr>
	<tr><td colspan='4'><div onMouseover="ddrivetip('<? echo addslashes($icones);?>', '#B0C4DE', '200', '<?echo $icone_long;?>')"; onMouseout="hideddrivetip()"><?echo $img;?></div></td></tr></table></div>	
	<div class='col-md-6'><h2>Troupes dans votre secteur</h2><?include_once('./unit_ground_place.php');?></div>
	</div>
	<?
	$con = dbconnecti();
	$Enis = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays = p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Position<>25 AND r.Vehicule_Nbr > 0"),0);
	mysqli_close($con);
	include_once('./unit_ground_infos.php');
	$CT_move = 1 + round((100-$Speed_min)/10);
	if($Efficacite_ravit > 0)
		$CT_front = round(24 * 100 / $Efficacite_ravit);
	else
		$CT_front = 24;
	$Credits_emb = 4;
	$Credits_def = 4;
	$Credits_ret = 4;
	$Credits_app = 4;
	$Credits_move = 1;
	$Credits_form = 4;
	$Credits_flee = 12;
	$Credits_flee2 = 6;
	$Credits_smoke = 4;
	$Credits_plonge = 4;
	switch($Trait)
	{
		case 2:
			$Credits_emb = 2;
		break;
		case 3:
			$Credits_def = 2;
		break;
		case 4:
			$Credits_ret = 2;
		break;
		case 7:
			$Credits_emb = 5;
			$Credits_def = 5;
			$Credits_ret = 5;
			$Credits_move = 2;
			$Credits_form = 2;
			$CT_move += 1;
			$CT_front += 1;
		break;
		case 9:
			$Credits_flee -= 1;
			$Credits_flee2 -= 1;
			$CT_front -= 1;
			$CT_move -= 1;
			if($CT_move < 1)
				$CT_move = 1;
		break;
		case 13:
			$Credits_flee = 2;
			$Credits_flee2 = 2;
			$CT_front += 2;
			$CT_move += 2;
			$Credits_smoke = 2;
		break;
		case 16:
			$Credits_flee += 2;
			$Credits_flee2 += 2;
		break;
		case 18:
			if($mobile == 5)
			{
				$Credits_flee -= 2;
				$Credits_flee2 -= 2;
				$CT_front -= 2;
				$CT_move -= 2;
				if($CT_move < 1)
					$CT_move = 1;
			}
		break;
		case 19:
			$Credits_app = 2;
		break;
		case 20:
			$Credits_plonge = 2;
		break;
		case 21:
			if($mobile == 5 and $g_Type == 21)
			{
				$Credits_flee -= 1;
				$Credits_flee2 -= 1;
				$CT_front -= 1;
				$CT_move -= 1;
				if($CT_move < 1)
					$CT_move = 1;
			}
		break;
	}
	if(!$Pont and $Pont_Ori and $g_mobile != 5)
	{
		$CT_front *= 2;
		$CT_move *= 2;
		$txt_pont = "<p>Le pont est d�truit, ralentissant les d�placements!</p>";
	}
	elseif($Mines_m and $g_mobile == 5)
	{
		$CT_front *= 2;
		$CT_move *= 2;
		$txt_pont = "<p>La zone est min�e, ralentissant les d�placements!</p>";
	}
	if($Faction_occupant != $Faction and $Faction_flag != $Faction and $g_mobile != 5)
	{
		$CT_front *= 2;
		$CT_move *= 2;
		$txt_pont = "<p>La zone est contr�l�e par l'ennemi, ralentissant vos d�placements!</p>";
	}
	if($CT_front > 40)
		$CT_front = 40;
	if($CT_move > 40)
		$CT_move = 40;
			
	if(date("H") < 6)
		$Canada = true;
	else
		$Canada = false;
	
	echo '<br>'.$Cdt_orders;
	?>
	<form action='index.php?view=ground_dig' method='post'>
	<input type='hidden' name='Loc' value='<?echo $Cible;?>'>
	<input type='hidden' name='Reg' value='<?echo $Regiment;?>'>
	<input type='hidden' name='CT_M' value='<?echo $CT_move;?>'>
	<input type='hidden' name='CT_F' value='<?echo $CT_front;?>'>
	<input type='hidden' name='CT_emb' value='<?echo $Credits_emb;?>'>
	<input type='hidden' name='CT_def' value='<?echo $Credits_def;?>'>
	<input type='hidden' name='CT_ret' value='<?echo $Credits_ret;?>'>
	<input type='hidden' name='CT_flee' value='<?echo $Credits_flee;?>'>
	<div style='width:80%'><table class='table'>
		<thead><tr><th>Actions du Bataillon</th></tr></thead>
		<tr><th><?echo $txt_pont;?></th></tr>
		<tr><td align='left'>
				<?if($Credits >= $Credits_move and $Move != 1 and $Faction > 0 and $g_mobile!=4 and $g_mobile!=5 and $Position != 11){?>
				<Input type='Radio' name='Action' value='17' title='Annule tout retranchement, position d�fensive et embuscade'>
				<img src='/images/CT<?echo $Credits_move;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Pr�parer le bataillon � faire mouvement<br>
				<?}if($Credits >= $Credits_ret and $Position != 2 and $Meteo[1] != -50 and $Meteo[1] != -135 and $g_mobile!=4 and $g_mobile!=5 and $Position != 11){?>
				<Input type='Radio' name='Action' value='1' title='Attaque et mouvement impossibles, d�fense quadrupl�e et camouflage doubl�'>
				<img src='/images/CT<?echo $Credits_ret;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se retrancher sur nos positions <span title='conseill� pour les troupes isol�es'><img src='images/help.png'></span><br>
				<?}if($Credits >= $Credits_def and $Position != 1 and $g_mobile!=4 and $g_mobile!=5 and $Position != 11){?>
				<Input type='Radio' name='Action' value='2' title='D�fense augment�e, retraite � co�t r�duit, vitesse et attaque diminu�es'>
				<img src='/images/CT<?echo $Credits_def;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se placer en position d�fensive <span title='conseill� pour les troupes mobiles'><img src='images/help.png'></span><br>
				<?}if($Credits >= $Credits_emb and $Position != 3 and $Zone != 8 and $Zone != 0 and $Zone != 6 and $g_mobile!=4 and $g_mobile!=5 and $Position != 11){?>
				<Input type='Radio' name='Action' value='3' title='Attaque et mouvement impossibles, d�fense et camouflage doubl�s, contre-attaque automatique lors des recos ennemies'>
				<img src='/images/CT<?echo $Credits_emb;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se placer en embuscade <span title='conseill� pour les troupes anti-char'><img src='images/help.png'></span><br>
				<?}if($Credits >= 4 and $Position != 5 and $g_mobile!=4 and $g_mobile!=5 and $Position != 11){?>
				<Input type='Radio' name='Action' value='19' title='D�fense r�duite, unit� immobilis�e, couverture automatique des unit�s alli�es au m�me endroit'>
				<img src='/images/CT4.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se placer en appui <span title='conseill� pour l artillerie'><img src='images/help.png'></span><br>
				<?}if($Credits >= $Credits_ret and $Position != 10 and $g_mobile!=4 and $g_mobile!=5 and $Position != 11){?>
				<Input type='Radio' name='Action' value='20' title='Attaque et mouvement impossibles, d�fense et camouflage doubl�s'>
				<img src='/images/CT<?echo $Credits_ret;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Etablir une ligne de d�fense <span title='conseill� pour l infanterie'><img src='images/help.png'></span><br>
				<?}if($Credits >= $Credits_flee2 and $Move and $Cible != $Retraite and !$Atk_mode and $g_mobile!=4 and $g_mobile!=5)
				{
					if($Position == 1){?>
					<Input type='Radio' name='Action' value='4' title='Attaque impossible. Votre derni�re Cie est toujours celle qui couvre la retraite'>
					<img src='/images/CT<?echo $Credits_flee2;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Effectuer une retraite<br>
					<?}elseif($Credits >= $Credits_flee){?>
					<Input type='Radio' name='Action' value='5' title='Attaque impossible. Votre derni�re Cie est toujours celle qui couvre la retraite'>
					<img src='/images/CT<?echo $Credits_flee;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Effectuer une retraite<br>
					<?}
				}if($Credits >= $CT_front and $Placement == 3 and $Move and $NoeudF > 0 and !$Enis and $Faction > 0 and $Faction_occupant == $Faction and $Faction_flag == $Faction and $Position != 11 and $g_mobile!=5)
				{
					if($Cible == $Retraite){?>
					<Input type='Radio' name='Action' value='6' title='Une gare non d�truite est n�cessaire sur le lieu de destination'>
					<img src='/images/CT<?echo $CT_front;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Monter au front en utilisant le r�seau ferroviaire<br>
					<?}else{?>
					<Input type='Radio' name='Action' value='6' title='Une gare non d�truite est n�cessaire sur le lieu de destination'>
					<img src='/images/CT<?echo $CT_front;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- D�placer le bataillon en utilisant le r�seau ferroviaire<br>
					<?}
				}
				if($Position != 11 and $g_mobile == 5 and ($Port > 0 or $Plage > 0 or $Zone == 6) and $Placement == 4)
				{
					if($Credits >= 40 and $Bat_Veh_Nbr == 0)
						echo "<Input type='Radio' name='Action' value='108'>
						<img src='/images/CT40.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Saborder votre dernier navire et attendre les sauveteurs<br>";
					if($Credits >= $CT_move and $Move)
						echo "<Input type='Radio' name='Action' value='106' title='Uniquement vers les ports alli�s et les zones maritimes'>
						<img src='/images/CT".$CT_move.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Appareiller<br>";
					if($g_Type == 37)
					{
						if($Position == 25)
						{
							if($Credits >= $Credits_ret and $Move)
								echo "<Input type='Radio' name='Action' value='119' title='La visibilit� du sous-marin augmente ainsi que sa vitesse'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Surface !<br>";
						}
						else
						{
							if($Credits >= $Credits_plonge and $Move)
								echo "<Input type='Radio' name='Action' value='116' title='La visibilit� du sous-marin diminue ainsi que sa vitesse'>
								<img src='/images/CT".$Credits_plonge.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- En plong�e<br>";
						}
						if($Zone == 6 and $Credits >= $Credits_ret and $Move)
							echo "<Input type='Radio' name='Action' value='110' title='Chaque attaque fera perdre au maximum 1 navire, appui impossible'>
							<img src='/images/CT".$Credits_ret.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Formation dispers�e <span title='conseill� pour les flottes isol�es ou sans protection'><img src='images/help.png'></span><br>";
					}
					else
					{
						if($Zone == 6)
						{
							if($Credits >= $Credits_smoke and $Move)
								echo "<Input type='Radio' name='Action' value='109'>
								<img src='/images/CT".$Credits_smoke.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Ecran de fum�e<br>";
							if($Credits >= $Credits_app and $Move)
								echo "<Input type='Radio' name='Action' value='113' title='Les navires riposteront si des navires alli�s sont attaqu�s'>
								<img src='/images/CT".$Credits_app.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Formation d'appui <span title='conseill� pour les flottes avec une bonne puissance de feu'><img src='images/help.png'></span><br>";
							if($Credits >= $Credits_ret and $Move)
							{
								echo "<Input type='Radio' name='Action' value='111' title='Les navires escortant prot�geront le convoi ou le porte-avions'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Formation d'escorte <span title='conseill� pour la protection de convoi ou de porte-avions'><img src='images/help.png'></span><br>";
								echo "<Input type='Radio' name='Action' value='112' title='Ecran de fum�e automatique si attaqu� par des navires plus lents, riposte impossible'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Formation d'�vasion <span title='conseill� pour les flottes rapides d�sirant �viter le combat'><img src='images/help.png'></span><br>";
								echo "<Input type='Radio' name='Action' value='114' title='Les navires riposteront si des sous-marins sont d�tect�s'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Formation ASM <span title='conseill� pour les flottes ASM'><img src='images/help.png'></span><br>";
							}
							if($Credits >= $Credits_ret and $Move)
								echo "<Input type='Radio' name='Action' value='110' title='Chaque attaque fera perdre au maximum 1 navire, appui impossible'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Formation dispers�e <span title='conseill� pour les flottes isol�es ou sans protection'><img src='images/help.png'></span><br>";
						}
						else
						{
							if($Credits >= 4 and $Faction_occupant == $Faction)
								echo "<Input type='Radio' name='Action' value='107'>
								<img src='/images/CT4.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Camoufler les navires<br>";
							if($Credits >= 40 and $g_Type != 37)
							{
								if($Move and $Position != 26)
									echo "<Input type='Radio' name='Action' value='117'>
									<img src='/images/CT40.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Poser des filets anti-torpilles<br>";
								else
									echo "<Input type='Radio' name='Action' value='118'>
									<img src='/images/CT40.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Retirer les filets anti-torpilles<br>";
							}
						}
					}
				}
				if($Credits >= $CT_move and $Move and $Faction > 0 and $g_mobile!=4 and $Position != 11 and $g_mobile!=5){?>
				<Input type='Radio' name='Action' value='15' title='D�placement limit� par la plus petite autonomie de vos Cie'>
				<img src='/images/CT<?echo $CT_move;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- D�placer le bataillon<br>
				<Input type='Radio' name='Action' value='115' title='Vos unit�s � pieds voient leur autonomie doubl�e. Perte de Moral'>
				<img src='/images/CT<?echo $CT_move;?>.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- D�placer le bataillon, � marche forc�e<br>
				<?}
				if($Position != 11 and $g_mobile!=4 and $g_mobile!=5)
				{
					if($Faction_occupant == $Faction)
					{
						if($Credits >= 1 and $Cible_base > 0 and $Placement != 1){?>
						<Input type='Radio' name='Action' value='7' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se positionner aux alentours de la base a�rienne<br>
						<?}if($Credits >= 1 and $NoeudR and $Placement != 2){?>
						<Input type='Radio' name='Action' value='8' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se positionner aux alentours du noeud routier<br>
						<?}if($Credits >= 1 and $NoeudF_Ori and $Placement != 3){?>
						<Input type='Radio' name='Action' value='9' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se positionner aux alentours de la gare<br>
						<?}if($Credits >= 1 and $Port_Ori and $Placement != 4){?>
						<Input type='Radio' name='Action' value='10' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se positionner aux alentours du port<br>
						<?}if($Credits >= 1 and $Pont_Ori and $Placement != 5){?>
						<Input type='Radio' name='Action' value='11' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se positionner aux alentours du pont strat�gique<br>
						<?}if($Credits >= 1 and $Usine and $Placement != 6){?>
						<Input type='Radio' name='Action' value='12' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se positionner aux alentours de l'usine<br>
						<?}if($Credits >= 1 and $Radar and $Placement != 7){?>
						<Input type='Radio' name='Action' value='13' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se positionner aux alentours du radar<br>
						<?}if($Credits >= 1 and $Placement != 0){?>
						<Input type='Radio' name='Action' value='14' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Se positionner aux alentours de la caserne<br>
						<?}
					}
					else
					{
						if($Recce > 0 or $Faction_flag == $Faction)
							$Move_On = true;
						else
							$Move_On = false;
						if($Credits >= 2 and $Cible_base > 0 and $Placement != 1 and $Move_On){?>
						<Input type='Radio' name='Action' value='7' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Investir la base a�rienne<br>
						<?}if($Credits >= 2 and $NoeudR and $Placement != 2){?>
						<Input type='Radio' name='Action' value='8' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Investir le noeud routier<br>
						<?}if($Credits >= 2 and $NoeudF_Ori and $Placement != 3 and $Move_On){?>
						<Input type='Radio' name='Action' value='9' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Investir la gare<br>
						<?}if($Credits >= 2 and $Port_Ori and $Placement != 4 and $Move_On){?>
						<Input type='Radio' name='Action' value='10' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Investir le port<br>
						<?}if($Credits >= 2 and $Pont_Ori and $Placement != 5 and $Move_On){?>
						<Input type='Radio' name='Action' value='11' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Investir le pont strat�gique<br>
						<?}if($Credits >= 2 and $Usine and $Placement != 6 and $Move_On){?>
						<Input type='Radio' name='Action' value='12' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Investir l'usine<br>
						<?}if($Credits >= 2 and $Radar and $Placement != 7 and $Move_On){?>
						<Input type='Radio' name='Action' value='13' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Investir la station radar<br>
						<?}if($Credits >= 2 and $Placement != 0){
						if($Pont_Ori == 100 and !$Pont){?>
						<Input type='Radio' name='Action' value='14' title='Mince alors!' disabled>- Caserne inaccessible car le pont est d�truit<br>
						<?}else{?>
						<Input type='Radio' name='Action' value='14' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Investir la caserne<br>
						<?}}
					}
				}
				if($Credits >= 2 and $Zone == 6 and $g_mobile == 5 and !$Enis and $Cible != $Retraite and $Faction_flag != $Faction and !$Canada and $Position != 25)
				{?><Input type='Radio' name='Action' value='60' title='Les troupes de votre faction combattront avec l�avantage du terrain!'>
				<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Quadriller la zone maritime<br><?
				}if($Credits >= 2 and !$Enis and $Cible != $Retraite and $Faction_flag != $Faction and $Placement == 0 and !$Canada and $g_mobile!=4 and $g_mobile!=5 and $Position != 11 and $Position != 6 and $Position != 8 and $Position != 9)
				{if(($Faction_occupant != $Faction and $Garnison < 1) or $Faction_occupant == $Faction){?>
					<Input type='Radio' name='Action' value='60' title='Les troupes de votre faction combattront avec l�avangage du terrain!'>
					<img src='/images/CT2.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Revendiquer la position<br><?}
				}if($Credits >= $Credits_form and $Cible == $Retraite and $Position != 11 and $Position != 8){?>
				<Input type='Radio' name='Action' value='16' title='Rien de tel pour �tre fin pr�t!'>
				<img src='/images/CT4.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Effectuer des manoeuvres d'entrainement<br>
				<?}if($Credits >= $Credits_form and $Cible != $Retraite and !$Move and $Placement == 0 and $Faction_flag == $Faction and $Position != 11){?>
				<Input type='Radio' name='Action' value='18' title='Un discours, une messe, quelques cigarettes... peu importe! Ne fonctionne que dans la caserne'>
				<img src='/images/CT4.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Remonter le moral des troupes<br>
				<?}if($Credits >= 40 and $Position == 11){?>
				<Input type='Radio' name='Action' value='99' title='Sortez-moi de l�!'>
				<img src='/images/CT40.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Annuler la demande de transit<br>
				<?}if($Credits >= 40 and $Cible == $Retraite and $Placement == 3 and $Position != 11 and $Position != 8 and $Position != 9 and $NoeudF > 0 and $Faction_flag == $Faction and $g_mobile != 4 and $Front != 3){?>
				<Input type='Radio' name='Action' value='70' title='Il est temps de passer � autre chose!'>
				<img src='/images/CT40.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Convertir le bataillon en convoi ferroviaire <span title='ATTENTION! Vous perdrez toutes vos Compagnies qui seront remplac�es par un train!'><img src='images/help.png'></span><br>
				<?}if($Credits >= 40 and $Placement == 3 and $Position != 11 and $Position != 8 and $Position != 9 and $Faction_flag == $Faction and $g_mobile == 4 and $Front != 3){?>
				<Input type='Radio' name='Action' value='71' title='Il est temps de passer � autre chose!'>
				<img src='/images/CT40.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Convertir le convoi ferroviaire en bataillon <span title='ATTENTION! Vous perdrez votre train qui sera remplac� par des compagnies infanterie!'><img src='images/help.png'></span><br>
				<?}if($Credits >= 40 and $Placement == 4 and $Position != 11 and $Position != 8 and $Position != 9 and $Port > 0 and $Faction_flag == $Faction and $g_mobile != 5 and $Front == 2 and $Latitude_front <= 36.80){?>
				<Input type='Radio' name='Action' value='72' title='Il est temps de passer � autre chose!'>
				<img src='/images/CT40.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Convertir le bataillon en convoi maritime <span title='ATTENTION! Vous perdrez toutes vos Compagnies qui seront remplac�es par un train!'><img src='images/help.png'></span><br>
				<?}if($Credits >= 40 and $Zone != 6 and $Placement == 4 and $Position != 11 and $Position != 8 and $Position != 9 and $Faction_flag == $Faction and $g_mobile == 5 and $Position != 25 and $Front == 2 and $Latitude_front <= 36.80 and $Front != 3){?>
				<Input type='Radio' name='Action' value='73' title='Il est temps de passer � autre chose!'>
				<img src='/images/CT40.png' title='Montant en Cr�dits Temps que n�cessite cette action'>- Convertir le convoi maritime en bataillon <span title='ATTENTION! Vous perdrez vos navires qui seront remplac�s par des compagnies infanterie!'><img src='images/help.png'></span><br>
				<?}?>
		</td></tr>
		<?if($Credits >= 1){?>
		<tfoot><tr><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr></tfoot>
		<?}?>
		</table></div>
</form>
<!--</body></html>-->
<?
}
else
{
	echo "<h1>Vous devez �tre connect� pour acc�der � cette page!</h1>";
}
?>