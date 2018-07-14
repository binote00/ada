<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
//Edit Maj
header("Location: ./index.php?view=ground_bat");
$OfficierID=false; 
//End Edit Maj
if($OfficierID >0)
{	
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
	$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	$result=mysqli_query($con,"SELECT Credits,Avancement,Front,Division,Trait,Atk,Transit FROM Officier WHERE ID='$OfficierID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : g_menu-off');
	$resultr=mysqli_query($con,"SELECT Lieu_ID,Placement FROM Regiment WHERE Officier_ID='$OfficierID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : g_menu-reg');
	if($result)
	{
		if($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Credits=$data['Credits'];
			$Avancement=$data['Avancement'];
			$Front=$data['Front'];
			$Division=$data['Division'];
			$Trait_o=$data['Trait'];
			$Atk_mode=$data['Atk'];
			$Transit=$data['Transit'];
		}
		mysqli_free_result($result);
	}
	if($resultr)
	{
		while($datar=mysqli_fetch_array($resultr,MYSQLI_ASSOC))
		{
			$Cible=$datar['Lieu_ID'];
			$Placement=$datar['Placement'];
		}
		mysqli_free_result($resultr);
	}
	if($Cible)
	{
		$Saison=GetSaison($Date_Campagne);
		//$con=dbconnecti();
		$resultl=mysqli_query($con,"SELECT Latitude,Longitude FROM Lieu WHERE ID='$Cible'");
		//mysqli_close($con);
		if($resultl)
		{
			while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC))
			{
				$Latitude_front=$datal['Latitude'];
				$Longitude_front=$datal['Longitude'];
			}
			mysqli_free_result($resultl);
		}
		if($Division >0)
		{
			$Help_Div="";
			$result=mysqli_query($con,"SELECT Base,repli,rally,atk,hatk,def,ravit,Cdt FROM Division WHERE ID='$Division'");
			$resultd=mysqli_query($con,"SELECT o.Nom,l.ID,l.Nom as lieu FROM Officier as o,Lieu as l WHERE o.Division='$Division' AND o.Aide>0 AND o.Aide=l.ID");
			$resulto=mysqli_query($con,"SELECT lieu_atk1,lieu_atk2,lieu_def FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
			if($resulto)
			{
				while($data=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
				{
					$lieu_atk1=$data['lieu_atk1'];
					$lieu_atk2=$data['lieu_atk2'];
					$lieu_def=$data['lieu_def'];
				}
				mysqli_free_result($resulto);
				unset($data);
			}
			if($resultd)
			{
				while($datad=mysqli_fetch_array($resultd,MYSQLI_ASSOC))
				{
					$Help_Div.="<tr><td>".$datad['Nom']."</td><td><a href='em_city_ground.php?id=".$datad['ID']."' target='_blank' class='lien'>".$datad['lieu']."</a></td></tr>";
				}
				mysqli_free_result($resultd);
			}
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Retraite=$data['Base'];
					$repli=$data['repli'];
					$rally=$data['rally'];
					$ravit=$data['ravit'];
					$atk=$data['atk'];
					$hatk=$data['hatk'];
					$def=$data['def'];
					$Division_Cdt=$data['Cdt'];
				}
				mysqli_free_result($result);
			}
			if($repli >0)
				$repli=GetData("Lieu","ID",$repli,"Nom");
			else
				$repli="Aucun";
			if($rally >0)
				$rally=GetData("Lieu","ID",$rally,"Nom");
			else
				$rally="Aucun";
			if($atk >0)
				$atk=GetData("Lieu","ID",$atk,"Nom");
			elseif($lieu_atk1 >0)
				$atk=GetData("Lieu","ID",$lieu_atk1,"Nom");
			elseif($lieu_atk2 >0)
				$atk=GetData("Lieu","ID",$lieu_atk2,"Nom");
			else
				$atk="Aucun";
			if($def >0)
				$def=GetData("Lieu","ID",$def,"Nom");
			elseif($lieu_def >0)
				$def=GetData("Lieu","ID",$lieu_def,"Nom");
			else
				$def="Aucun";
			if($ravit >0)
				$ravit=GetData("Lieu","ID",$ravit,"Nom");
			else
				$ravit="Aucun";
			if($Retraite)$Retraite_txt=GetData("Lieu","ID",$Retraite,"Nom");
			/*if($country ==20 or ($country ==8 and $Latitude_front >59.35 and $Longitude_front >20))
				$carte_txt="<a href='carte_finland.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			elseif($Front ==2)
				$carte_txt="<a href='carte_med_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			elseif($Front ==3)
				$carte_txt="<a href='carte_pacifique.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			elseif($Front ==4)
				$carte_txt="<a href='carte_nord_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			elseif($Front ==5)
				$carte_txt="<a href='carte_arctic.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			elseif($Front ==1)
				$carte_txt="<a href='carte_sud_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			elseif($Latitude_front >60)
				$carte_txt="<a href='carte_arctic.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			elseif($Longitude_front <-20)
				$carte_txt="<a href='carte_atlantic.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			else
				$carte_txt="<a href='carte_ouest.php' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";*/		
			$carte_txt="<a href='carte_ground.php?map=".$Front."&mode=1' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
			$Cdt_orders="<div class='row'><div class='col-md-3'><table class='table table-striped'>
				<thead><tr><th colspan='2'>Ordres du Commandant <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a></th></tr></thead>
				<tr><td align='left'>Point de repli</td><td>".$repli."</td></tr>
				<tr><td align='left'>Point de ralliement</td><td>".$rally."</td></tr>
				<tr><td align='left'>Point de ravitaillement</td><td>".$ravit."</td></tr>
				<tr><td align='left'>Objectif à défendre</td><td>".$def."</td></tr>
				<tr><td align='left'>Objectif à attaquer</td><td>".$atk."</td></tr>
				<tr><td align='left'>Heure de l'attaque</td><td>".$hatk."h</td></tr>
				<tr><td align='left'>Base arrière</td><td>".$Retraite_txt."</td></tr>
				<tr><td align='center' colspan='2'>".$carte_txt."</td></tr>
				<tr><td align='center' colspan='2'><div class='btn btn-primary'><a href='index.php?view=ground_news'>Ordre du jour</a></div></td></tr>";
		}
		else
		{
			$Cdt_orders="<div class='row'><div class='col-md-3'><table class='table table-striped'>
			<thead><tr><th colspan='2'>Ordres du Commandant</th></tr></thead>
			<tr><td><div class='alert alert-warning'>De nouvelles options de jeu apparaitront <b>si vous faites partie d'une division</b>.<br>Vous pouvez postuler via <a href='index.php?view=ground_profile' class='lien'>le profil de votre officier</a></div></td>
			<tr><td align='center' colspan='2'><div class='btn btn-primary'><a href='index.php?view=ground_news'>Ordre du jour</a></div></td></tr>";
			$Latitude_rav_max=70;
			if($Front ==2)
			{
				if($Latitude_front <33)
				{
					$Latitude_rav_max=37;		
					$Latitude_rav_min=29;
					$Longitude_rav=10;
				}
			}
			elseif($Front ==1)
				$Latitude_rav_min=45;
			elseif($Front ==4)
				$Latitude_rav_min=50;
			elseif($Front ==5)
				$Latitude_rav_min=50;
			else
				$Latitude_rav_min=45;
			if(!$Retraite)$Retraite=Get_Retraite($Front,$country,$Latitude_front);
		}
		//$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND Latitude < '$Latitude_rav_max' AND Latitude > '$Latitude_rav_min' AND Longitude > '$Longitude_rav' AND NoeudF_Ori >0 AND Flag_Gare='$country'");
		$result=mysqli_query($con,"SELECT Nom,Pays,Zone,Map,Meteo,Meteo_Hour,Latitude,Longitude,Occupant,ValeurStrat,DefenseAA_temp,BaseAerienne,QualitePiste,Industrie,Pont_Ori,Pont,Radar,Radar_Ori,Port,Port_Ori,Port_level,NoeudR,NoeudF,NoeudF_Ori,
		Mines,Mines_m,Fortification,Garnison,Recce,Flag,Plage,Detroit,Flag_Air,Flag_Route,Flag_Gare,Flag_Port,Flag_Pont,Flag_Usine,Flag_Radar,Flag_Plage,Fleuve FROM Lieu WHERE ID='$Cible'") or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : gmenu-lieu');
		mysqli_close($con);
		//Malus mouvement par rail
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
		if($country ==7 and $Longitude_front <-55)$Efficacite_ravit=100;
		if($result)//Lieu
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Cible_nom=$data['Nom'];
				$Pays_Ori=$data['Pays'];
				$Cible_DefenseAA=$data['DefenseAA_temp'];
				$ValeurStrat=$data['ValeurStrat'];
				$Cible_base=$data['BaseAerienne'];
				$QualitePiste=$data['QualitePiste'];
				$Usine=$data['Industrie'];
				$Pont=$data['Pont'];
				$Pont_Ori=$data['Pont_Ori'];
				$Port=$data['Port'];
				$Port_Ori=$data['Port_Ori'];
				$Port_level=$data['Port_level'];
				$Radar=$data['Radar'];
				$Radar_Ori=$data['Radar_Ori'];
				$NoeudR=$data['NoeudR'];
				$NoeudF=$data['NoeudF'];
				$NoeudF_Ori=$data['NoeudF_Ori'];
				$Zone=$data['Zone'];
				$Map=$data['Map'];
				$Plage=$data['Plage'];
				$Fleuve=$data['Fleuve'];
				$Detroit=$data['Detroit'];
				$Occupant=$data['Occupant'];
				$Mines=$data['Mines'];
				$Mines_m=$data['Mines_m'];
				$Fortification=$data['Fortification'];
				$Recce=$data['Recce'];
				$Flag=$data['Flag'];
				$Flag_Air=$data['Flag_Air'];
				$Flag_Route=$data['Flag_Route'];
				$Flag_Gare=$data['Flag_Gare'];
				$Flag_Port=$data['Flag_Port'];
				$Flag_Pont=$data['Flag_Pont'];
				$Flag_Usine=$data['Flag_Usine'];
				$Flag_Radar=$data['Flag_Radar'];
				$Flag_Plage=$data['Flag_Plage'];
				$Garnison=$data['Garnison'];
				$today=getdate();
				$Hour=$today['hours'];
				$meteo_txt="Meteo_Hour =".$data['Meteo_Hour']." / Hour =".$today['hours'];
				if(!$data['Meteo_Hour'] or ($today['hours'] >$data['Meteo_Hour']+2))
				{
					$Meteo=GetMeteo($Saison,$data['Latitude'],$data['Longitude']);
					$Meteo_malus=$Meteo[1];
					$Meteo_txt=$Meteo[0];
					$con=dbconnecti();
					$upmeteo=mysqli_query($con,"UPDATE Lieu SET Meteo='$Meteo_malus',Meteo_Hour='$Hour' WHERE ID='$Cible'");
					mysqli_close($con);
				}
				else
					$Meteo_malus=$data['Meteo'];
				$Zone_txt=GetZone($Zone);
				$Region='zone '.$Zone_txt;
			}
			mysqli_free_result($result);
			unset($data);
		}
		$Faction_flag=GetData("Pays","ID",$Flag,"Faction");
		if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
			$img='<img src=\'images/lieu/lieu'.$Cible.'.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
		else
		{
			if($Nuit)
				$img='<img src=\'images/lieu/objectif_nuit'.$Map.'.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
			elseif($Zone ==8)
			{
				if($Map ==0 or $Map ==1)
					$img='<img src=\'images/dune_sea.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
				elseif($Map ==2 or $Map ==3)
					$img='<img src=\'images/desert_airfield.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
			}
			elseif($Zone ==9)
			{
				if($Map ==0 or $Map ==1)
					$img='<img src=\'images/jungle.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
				elseif($Map ==2 or $Map ==3)
					$img='<img src=\'images/pacific_airfield.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
				elseif($Map ==8)
					$img='<img src=\'images/jungle_port.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
			}
			elseif($Front ==3)
			{
				if($Map ==2)
					$img='<img src=\'images/pacific_airfield.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
			}
			if(!$img)$img='<img src=\'images/lieu/objectif'.$Map.'.jpg\' title=\''.$Cible_nom.'\' style=\'width:100%;\'>';
		}	
		$icone_long=200;
		$icone_occupant="<a href='#' class='popup'><img src='images/flag".$Pays_Ori."p.jpg'><span><b>".GetPays($Pays_Ori)."</b>. Nation contrôlant le lieu au début de la partie.</span></a>";
		$icones="<b>".$Cible_nom."</b>";
		if($Mines_m >0)
		{
			$icones.="<img src='images/mine_navale.gif' title='Champ de Mines ralentissant vos déplacements'>";
			$icone_long+=25;
		}
		if($Faction_flag ==$Faction)
		{
			if($Mines)
			{
				$title ="Champ de Mines ".GetPlace($Mines);
				$icones.="<br><img src='images/mines.gif' title='".$title."'>";
				$icone_long+=25;
			}
			if($NoeudF)
			{
				$icones.="<br><img src='images/vehicules/vehicule9.gif' title='Gare'>"; 
				$icone_long+=25;
			}
			if($Pont)
			{
				$icones.="<br><img src='images/vehicules/vehicule10.gif' title='Pont'>"; 
				$icone_long+=25;
			}
			if($Port)
			{
				$icones.= "<br><img src='images/vehicules/vehicule12.gif' title='Port'>"; 
				$icone_long+=25;
			}
			if($Radar)
			{
				$icones.="<br><img src='images/vehicules/vehicule15.gif' title='Radar'>";
				$icone_long+=25;
			}
			if($Cible_base)
			{
				$icones.="<br><img src='images/vehicules/vehicule4005.gif' title='Base aérienne'>"; 
				$icone_long+=25;
			}
		}
		if($Flag >0)
		{
			$Rev_txt="Les troupes de la nation contrôlant le lieu (<b>".GetPays($Flag)."</b>), bénéficient d'un bonus de portée de 500m et d'un bonus de déplacement de 10";
			$Rev="<a href='#' class='popup'><img src='images/flag".$Flag."p.jpg'><span>".$Rev_txt."</span></a>";
		}
		?><h1><?echo $Cible_nom;?></h1>
		<div class='row'><div class='col-md-6'><table class='table'><thead><tr><th>Territoire</th><th>Revendication</th><th>Valeur stratégique</th><th>Terrain</th><th>Météo</th></tr></thead>
		<tr><td><?echo $icone_occupant;?></td><td><?echo $Rev;?></td><td><a href='#' class='popup'><img src='images/strat<?echo $ValeurStrat;?>.png'><span>Valeur ajoutée quotidiennement au score de victoire de la faction qui le contrôle.</span></a></td><td><a href='#' class='popup'><img src="images/zone<?echo $Zone;?>.jpg"><span><?echo $Region;?></span></a></td>
		<td><img src='images/meteo<?echo $Meteo_malus;?>.gif' title='<?echo $Meteo_txt;?>'></td></tr>
		<tr><td colspan='4'>
		<?if($icone_long >200){?>
		<div onMouseover="ddrivetip('<? echo addslashes($icones);?>','#E6E1DB','200','<?echo $icone_long;?>')"; onMouseout="hideddrivetip()">
		<?}echo $img;?></div></td></tr></table></div><div class='col-md-6'><h2>Troupes dans votre secteur</h2><?include_once('./unit_ground_place.php');?></div></div><?
		$con=dbconnecti();
		$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Position<>25 AND r.Vehicule_Nbr >0"),0);
		$Enis2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Position<>25 AND r.Vehicule_Nbr >0"),0);
		$Enis_vis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Position<>25 AND r.Vehicule_Nbr >0 AND r.Visible=1"),0);
		$Enis_vis_ia=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Position<>25 AND r.Vehicule_Nbr >0 AND r.Visible=1"),0);
		mysqli_close($con);
		$Enis+=$Enis2;
		include_once('./unit_ground_infos.php');
		$CT_move=1+round((100-$Speed_min)/10);
		if($Efficacite_ravit >0)
			$CT_front=round(24*100/$Efficacite_ravit);
		else
			$CT_front=24;
		$Credits_emb=4;
		$Credits_def=4;
		$Credits_ret=4;
		$Credits_app=4;
		$Credits_move=1;
		$Credits_form=4;
		$Credits_flee=12;
		$Credits_flee2=6;
		$Credits_smoke=4;
		$Credits_plonge=4;
		switch($Trait_o)
		{
			case 2:
				$Credits_emb=2;
			break;
			case 3:
				$Credits_def=2;
			break;
			case 4:
				$Credits_ret=2;
			break;
			case 7:
				$Credits_emb=5;
				$Credits_def=5;
				$Credits_ret=5;
				$Credits_move=2;
				$Credits_form=2;
				$CT_move+=1;
				$CT_front+=1;
			break;
			case 9:
				$Credits_flee-=1;
				$Credits_flee2-=1;
				$CT_front-=1;
				$CT_move-=1;
				if($CT_move<1)$CT_move=1;
			break;
			case 13:
				$Credits_flee=2;
				$Credits_flee2=2;
				$CT_front+=2;
				$CT_move+=2;
				$Credits_smoke=2;
			break;
			case 16:
				$Credits_flee+=2;
				$Credits_flee2+=2;
			break;
			case 18:
				if($mobile ==5)
				{
					$Credits_flee-=2;
					$Credits_flee2-=2;
					$CT_front-=2;
					$CT_move-=2;
					if($CT_move<1)$CT_move=1;
				}
			break;
			case 19:
				$Credits_app=2;
			break;
			case 20:
				$Credits_plonge=2;
			break;
			case 21:
				if($mobile ==5 and $g_Type ==21)
				{
					$Credits_flee-=1;
					$Credits_flee2-=1;
					$CT_front-=1;
					$CT_move-=1;
					if($CT_move<1)$CT_move=1;
				}
			break;
		}
		if(!$Pont and ($Pont_Ori or $Fleuve) and $g_mobile !=5)
		{
			if($g_Amphi >3)
			{
				$CT_front=ceil($CT_front*1.5);
				$CT_move=ceil($CT_move*1.5);
			}
			else
			{
				$CT_front*=2;
				$CT_move*=2;
			}
			$txt_pont="<p>Le pont est détruit, ralentissant les déplacements!</p>";
		}
		elseif($Mines_m and $g_mobile ==5)
		{
			$CT_front*=2;
			$CT_move*=2;
			$txt_pont="<p>La zone est minée, ralentissant les déplacements!</p>";
		}
		if($Faction_flag !=$Faction and $g_mobile !=5)
		{
			$CT_front*=2;
			$CT_move*=2;
			$txt_pont="<p>La zone est contrôlée par l'ennemi, ralentissant vos déplacements!</p>";
		}
		if($CT_front>40)$CT_front=40;
		if($CT_move>40)$CT_move=40;
        //Update : !$Front
		if(!$Front and $Bat_Veh_Nbr >0 and $Credits >=8 and $ValeurStrat >3 and $Faction_flag ==$Faction and (($Port_Ori >0 and $Placement ==4) or ($NoeudF_Ori >0 and $Placement ==3)) and $Position !=4 and $Position !=5 and $Position !=10 and $Division >0 and $Division_Cdt >0 and $Division_Cdt ==$OfficierID)
			$Cdt_orders.="<tr><td align='center' colspan='2'><form action='index.php?view=ground_bruler_depot' method='post' onsubmit=\"return confirm('Etes vous certain de vouloir brûler le dépôt?');\">
			<input type='hidden' name='Div' value='".$Division."'><input type='hidden' name='Cible' value='".$Cible."'><input type='Submit' value='Brûler le dépôt' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form><img src='/images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'></td></tr></table></div>";				
		else
			$Cdt_orders.="</table></div>";
		echo $Cdt_orders;
		?>
		<form action='index.php?view=ground_dig' method='post'>
		<input type='hidden' name='Loc' value='<?echo $Cible;?>'>
		<input type='hidden' name='Reg' value='<?echo $Regiment;?>'>
		<input type='hidden' name='CT_M' value='<?echo $CT_move;?>'>
		<input type='hidden' name='CT_F' value='<?echo $CT_front;?>'>
		<input type='hidden' name='CT_emb' value='<?echo $Credits_emb;?>'>
		<input type='hidden' name='CT_def' value='<?echo $Credits_def;?>'>
		<input type='hidden' name='CT_ret' value='<?echo $Credits_ret;?>'>
		<input type='hidden' name='CT_app' value='<?echo $Credits_app;?>'>
		<input type='hidden' name='CT_smoke' value='<?echo $Credits_smoke;?>'>
		<input type='hidden' name='CT_plonge' value='<?echo $Credits_plonge;?>'>
		<input type='hidden' name='CT_flee' value='<?echo $Credits_flee;?>'>
		<div class='col-md-9'><table class='table'>
		<thead><tr><th>Actions du Bataillon</th></tr></thead>
		<tr><th><?echo $txt_pont;?></th></tr>
		<tr><td align='left'>
				<?if($Credits >=$Credits_move and $Move !=1 and $Faction >0 and $g_mobile!=4 and $g_mobile!=5 and $Position !=11){?>
				<Input type='Radio' name='Action' value='17' title='Annule tout retranchement, position défensive et embuscade'>
				<img src='/images/CT<?echo $Credits_move;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Préparer le bataillon à faire mouvement<br>
				<?}if($Credits >=$Credits_ret and $Position !=2 and $Meteo[1] !=-50 and $Meteo[1] !=-135 and $g_mobile!=4 and $g_mobile!=5 and $Position !=11){?>
				<Input type='Radio' name='Action' value='1' title='Attaque et mouvement impossibles, défense quadruplée et camouflage doublé'>
				<img src='/images/CT<?echo $Credits_ret;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Se retrancher sur nos positions <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les troupes isolées</span></a><br>
				<?}if($Credits >=$Credits_def and $Position !=1 and $g_mobile!=4 and $g_mobile!=5 and $Position !=11){?>
				<Input type='Radio' name='Action' value='2' title='Défense augmentée, retraite à coût réduit, vitesse et attaque diminuées'>
				<img src='/images/CT<?echo $Credits_def;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Se placer en position défensive <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les troupes mobiles</span></a><br>
				<?}if($Credits >=$Credits_emb and $Position !=3 and $Zone !=8 and $Zone !=0 and $Zone !=6 and $g_mobile!=4 and $g_mobile!=5 and $Position !=11){?>
				<Input type='Radio' name='Action' value='3' title='Attaque et mouvement impossibles, défense et camouflage doublés, contre-attaque automatique lors des recos ennemies'>
				<img src='/images/CT<?echo $Credits_emb;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Se placer en embuscade <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les troupes anti-char</span></a><br>
				<?}if($Credits >=4 and $Position !=5 and $g_mobile!=4 and $g_mobile!=5 and $Position !=11){?>
				<Input type='Radio' name='Action' value='19' title='Défense réduite, unité immobilisée, couverture automatique des unités alliées au même endroit'>
				<img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Se placer en appui <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour l'artillerie</span></a><br>
				<?}if($Credits >=$Credits_ret and $Position !=10 and $g_mobile!=4 and $g_mobile!=5 and $Position !=11){?>
				<Input type='Radio' name='Action' value='20' title='Attaque et mouvement impossibles, défense et camouflage doublés'>
				<img src='/images/CT<?echo $Credits_ret;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Etablir une ligne de défense <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour l'infanterie</span></a><br>
				<?}if($Credits >=$Credits_flee2 and $Move and $Cible !=$Retraite and !$Atk_mode and $g_mobile!=4 and $g_mobile!=5 and $Position!=11 and ($Bat_Veh_Nbr <11 or (!$NoeudR and $Zone >7)))
				{
					if($Position ==1){?>
					<Input type='Radio' name='Action' value='4' title='Attaque impossible. Votre dernière Cie est toujours celle qui couvre la retraite'>
					<img src='/images/CT<?echo $Credits_flee2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Effectuer une retraite</span> <a href='#' class='popup'><img src='images/help.png'><span>Retour immédiat à la base arrière, mais perte de 25% des effectifs, 50% des stocks et 100% du chargement éventuel</span></a><br>
					<?}elseif($Credits >=$Credits_flee){?>
					<Input type='Radio' name='Action' value='5' title='Attaque impossible. Votre dernière Cie est toujours celle qui couvre la retraite'>
					<img src='/images/CT<?echo $Credits_flee;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Effectuer une retraite</span> <a href='#' class='popup'><img src='images/help.png'><span>Retour immédiat à la base arrière, mais perte de 25% des effectifs, 50% des stocks  et 100% du chargement éventuel</span></a><br>
					<?}
				}
				if($Credits >=$CT_front and $Placement ==3 and $Move and $NoeudF >10 and !$Enis and $Faction >0 and $Faction_flag ==$Faction and $Position !=11 and $g_mobile!=5)
				{
					$Faction_Gare=GetData("Pays","ID",$Flag_Gare,"Faction");
					if($Faction_Gare ==$Faction){?>
					<Input type='Radio' name='Action' value='6' title='Une gare non détruite est nécessaire sur le lieu de destination'>
					<img src='/images/CT<?echo $CT_front;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Déplacer le bataillon en utilisant le réseau ferroviaire<br>
					<?}
				}
				else
				{
					//Grisé avec raisons de refus
				}
				if($Credits >=1 and $Placement !=8 and $Faction_flag ==$Faction and $Position !=11)
				{
					if($Cible ==344 or $Cible ==189 or $Cible ==198 or $Cible ==201 or $Cible ==586)
						echo "<Input type='Radio' name='Action' value='301'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
					elseif($Cible ==2)
						echo "<Input type='Radio' name='Action' value='302'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
					elseif($Cible ==1280 or $Cible ==615 or $Cible ==619 or $Cible ==621 or $Cible ==967)
						echo "<Input type='Radio' name='Action' value='306'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
					elseif($Cible ==199 or $Cible ==218)
						echo "<Input type='Radio' name='Action' value='308'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
					elseif($Cible ==709)
						echo "<Input type='Radio' name='Action' value='309'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
				}
				if($Position !=11 and $g_mobile ==5 and ($Port >0 or $Plage >0 or $Zone ==6))
				{
					if($Credits >=40 and $Bat_Veh_Nbr ==0)
						echo "<Input type='Radio' name='Action' value='108'>
						<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- Saborder votre dernier navire et attendre les sauveteurs<br>";
					if($Credits >=$CT_move and $Move)
						echo "<Input type='Radio' name='Action' value='106' title='Uniquement vers les ports alliés et les zones maritimes'>
						<img src='/images/CT".$CT_move.".png' title='Montant en Crédits Temps que nécessite cette action'>- Appareiller<br>";				
					if($g_Type ==37)
					{
						if($Position ==25)
						{
							if($Credits >=$Credits_ret and $Move)
								echo "<Input type='Radio' name='Action' value='119' title='La visibilité du sous-marin augmente ainsi que sa vitesse'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Crédits Temps que nécessite cette action'>- Surface !<br>";
							if($Zone !=6 and $Port >0 and $Placement ==8 and $Credits >=8 and $Faction_flag !=$Faction and !$G_Treve)
								echo "<Input type='Radio' name='Action' value='122' title='Ready!'>
								<img src='/images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'>- Pénétrer dans le port ennemi<br>";
						}
						else
						{
							if($Credits >=$Credits_plonge and $Move)
								echo "<Input type='Radio' name='Action' value='116' title='La visibilité du sous-marin diminue ainsi que sa vitesse'>
								<img src='/images/CT".$Credits_plonge.".png' title='Montant en Crédits Temps que nécessite cette action'>- En plongée<br>";
						}
						if($Zone !=6 and ($Port >0 or $Plage >0) and $Move and $Credits >=1)
						{
							if($Credits >=1 and $Placement ==8 and $Port >0 and $Faction_flag ==$Faction)
							{
								$Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
								if($Faction_Port ==$Faction)
									echo "<Input type='Radio' name='Action' value='121' title='Ready!'>
									<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Rentrer au port<br>";
								else
									echo "- Le port est occupé par l'ennemi, vos navires ne peuvent y pénétrer!<br>";
							}
							elseif($Credits >=1 and $Placement !=8)
								echo "<Input type='Radio' name='Action' value='120' title='Ready!'>
								<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner au large<br>";
						}
						elseif($Zone ==6 and $Credits >=$Credits_ret and $Move)
							echo "<Input type='Radio' name='Action' value='110' title='Chaque attaque fera perdre au maximum 1 navire, appui impossible'>
							<img src='/images/CT".$Credits_ret.".png' title='Montant en Crédits Temps que nécessite cette action'>- Formation dispersée <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les flottes isolées ou sans protection</span></a><br>";
					}
					else
					{
						if($Zone ==6 or $Placement ==8)
						{
							if($Credits >=$Credits_smoke and $Move)
								echo "<Input type='Radio' name='Action' value='109'>
								<img src='/images/CT".$Credits_smoke.".png' title='Montant en Crédits Temps que nécessite cette action'>- Ecran de fumée<br>";
							if($Credits >=$Credits_app and $Move)
								echo "<Input type='Radio' name='Action' value='113' title='Les navires riposteront si des navires alliés sont attaqués'>
								<img src='/images/CT".$Credits_app.".png' title='Montant en Crédits Temps que nécessite cette action'>- Formation d'appui <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les flottes avec une bonne puissance de feu</span></a><br>";
							if($Credits >=$Credits_ret and $Move)
							{
								echo "<Input type='Radio' name='Action' value='111' title='Les navires escortant protègeront le convoi ou le porte-avions'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Crédits Temps que nécessite cette action'>- Formation d'escorte <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour la protection de convoi ou de porte-avions</span></a><br>";
								echo "<Input type='Radio' name='Action' value='112' title='Ecran de fumée automatique si attaqué par des navires plus lents, riposte impossible'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Crédits Temps que nécessite cette action'>- Formation d'évasion <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les flottes rapides désirant éviter le combat</span></a><br>";
								echo "<Input type='Radio' name='Action' value='114' title='Les navires riposteront si des sous-marins sont détectés'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Crédits Temps que nécessite cette action'>- Formation ASM <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les flottes ASM</span></a><br>";
								echo "<Input type='Radio' name='Action' value='110' title='Chaque attaque fera perdre au maximum 1 navire, appui impossible'>
								<img src='/images/CT".$Credits_ret.".png' title='Montant en Crédits Temps que nécessite cette action'>- Formation dispersée <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les flottes isolées ou sans protection</span></a><br>";
								if($Bat_Veh_Nbr >0 and !$G_Treve)
									echo "<Input type='Radio' name='Action' value='123' title='Intercepter les navires ennemis passant sur la zone'>
									<img src='/images/CT".$Credits_ret.".png' title='Montant en Crédits Temps que nécessite cette action'>- Interdiction <a href='#' class='popup'><img src='images/help.png'><span>conseillé pour les flottes de combat désirant barrer la route</span></a><br>";
							}
							if($Credits >=1 and $Zone !=6 and $Port >0 and $Faction_flag ==$Faction)
							{
								$Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
								if($Faction_Port ==$Faction)
									echo "<Input type='Radio' name='Action' value='121' title='Ready!'>
									<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Rentrer au port<br>";
								else
									echo "- Le port est occupé par l'ennemi, vos navires ne peuvent y pénétrer!<br>";
							}
							if($Credits >=40 and $Transit >0 and $Zone !=6 and $Plage >0 and $today['hours'] >8)
							{
								echo "<Input type='Radio' name='Action' value='300'>
								<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- Débarquer les troupes<br>";
							}
						}
						elseif($Placement ==4)
						{
							if($Credits >=1 and $Faction_flag ==$Faction)
							{
								if($Cible ==1896)
									echo "<Input type='Radio' name='Action' value='303'>
									<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
								elseif($Cible ==704 or $Cible ==898 or $Cible ==2079)
									echo "<Input type='Radio' name='Action' value='307'>
									<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
								elseif($Credits >=40 and $Cible ==1567)
									echo "<Input type='Radio' name='Action' value='304'>
									<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
								elseif($Credits >=40 and $Cible ==2149)
									echo "<Input type='Radio' name='Action' value='305'>
									<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Changer de front</span> <a href='#' class='popup'><img src='images/help.png'><span>Nécessaire pour pouvoir vous déplacer sur un autre front</span></a><br>";
							}
							if($Credits >=4 and $Transit >0 and $Faction_flag ==$Faction)
							{
								echo "<Input type='Radio' name='Action' value='299'>
								<img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Débarquer les troupes<br>";
							}
							if($Credits >=4 and $Faction_flag ==$Faction)
								echo "<Input type='Radio' name='Action' value='107'>
								<img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Camoufler les navires<br>";
							if($Credits >=40 and $g_Type !=37)
							{
								if($Move and $Position !=26)
									echo "<Input type='Radio' name='Action' value='117'>
									<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- Poser des filets anti-torpilles <a href='#' class='popup'><img src='images/help.png'><span>Protège vos navires contre les torpilles, mais les immobilise</span></a><br>";
								else
									echo "<Input type='Radio' name='Action' value='118'>
									<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- Retirer les filets anti-torpilles<br>";
							}
							if($Credits >=1)
							{
								echo "<Input type='Radio' name='Action' value='120' title='Ready!'>
								<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner au large<br>";
							}
						}
					}
				}
				if($Position !=11 and $g_mobile!=4 and $g_mobile!=5)
				{
					if($Credits >=$CT_move and $Move and $Faction >0 and !$Atk_mode){?>
					<Input type='Radio' name='Action' value='15' title='Déplacement limité par la plus petite autonomie de vos Cie'>
					<img src='/images/CT<?echo $CT_move;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Déplacer le bataillon<br>
					<Input type='Radio' name='Action' value='115' title='Vos unités à pieds voient leur autonomie doublée. Perte de Moral'>
					<img src='/images/CT<?echo $CT_move;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Déplacer le bataillon, à marche forcée <a href='#' class='popup'><img src='images/help.png'><span>Vos unités à pieds voient leur autonomie doublée. Perte de Moral</span></a><br>
					<?}
					if($Credits >=24 and $Move and $Faction >0 and !$Atk_mode and $Placement ==5 and $Fleuve >0)
					{
						$Faction_Fleuve=GetData("Pays","ID",$Flag_Pont,"Faction");
						if($Faction_Fleuve ==$Faction)
							echo "<Input type='Radio' name='Action' value='124' title='Uniquement vers les lieux contrôlés par votre faction situés sur le même fleuve'>
							<img src='/images/CT24.png' title='Montant en Crédits Temps que nécessite cette action'>- Déplacer le bataillon par le fleuve <a href='#' class='popup'><img src='images/help.png'><span>Uniquement vers les lieux contrôlés par votre faction situés sur le même fleuve</span></a><br>";
					}
					if($Faction_flag ==$Faction)
					{
						if($Credits >=1 and $Cible_base >0 and $Placement !=1){?>
						<Input type='Radio' name='Action' value='7' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours de la base aérienne <a href='#' class='popup'><img src='images/help.png'><span>Parachutage possible si le bataillon est composé de parachutistes et si une unité de transport aérien est présente</span></a><br>
						<?}if($Credits >=1 and $NoeudR and $Placement !=2){?>
						<Input type='Radio' name='Action' value='8' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours du noeud routier <a href='#' class='popup'><img src='images/help.png'><span>Double les capacités de déplacement vers les autres noeuds routiers contrôlés par votre faction. Ne pas encombrer cette zone avec votre bataillon facilite le déplacement des unités EM</span></a><br>
						<?}if($Credits >=1 and $NoeudF_Ori and $Placement !=3){?>
						<Input type='Radio' name='Action' value='9' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours de la gare <a href='#' class='popup'><img src='images/help.png'><span>Pour tout déplacement ferroviaire ainsi que pour accéder au dépôt</span></a><br>
						<?}if($Credits >=1 and $Port_Ori and $Placement !=4){?>
						<Input type='Radio' name='Action' value='10' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours du port <a href='#' class='popup'><img src='images/help.png'><span>Pour tout déplacement maritime ainsi que pour accéder au dépôt</span></a><br>
						<?}if($Credits >=1 and ($Pont_Ori or $Fleuve) and $Placement !=5){?>
						<Input type='Radio' name='Action' value='11' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours du fleuve <a href='#' class='popup'><img src='images/help.png'><span>Pour tout déplacement fluvial</span></a><br>
						<?}if($Credits >=1 and $Usine and $Placement !=6){?>
						<Input type='Radio' name='Action' value='12' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours de l'usine <a href='#' class='popup'><img src='images/help.png'><span>Contrôler cette zone permet de continuer la production de cette usine</span></a><br>
						<?}if($Credits >=1 and $Radar_Ori and $Placement !=7){?>
						<Input type='Radio' name='Action' value='13' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours du radar <a href='#' class='popup'><img src='images/help.png'><span>Contrôler cette zone permet de conserver le radar en activité</span></a><br>
						<?}if($Credits >=1 and $Plage and $Placement !=11){?>
						<Input type='Radio' name='Action' value='298' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours de la plage <a href='#' class='popup'><img src='images/help.png'><span>Embarquement possible si des barges sont présentes</span></a><br>
						<?}if($Credits >=1 and $Placement !=0){?>
						<Input type='Radio' name='Action' value='14' title='Ready!'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Se positionner aux alentours de la caserne <a href='#' class='popup'><img src='images/help.png'><span>Zone permettant le contrôle du lieu. Entrainer les troupes et remonter leur moral se fait ici. Ne pas encombrer cette zone avec votre bataillon facilite le déplacement des unités EM</span></a><br>
						<?}
					}
					elseif(!$G_Treve)
					{
						if($Recce >0 or $Faction_flag ==$Faction)
						{
							$Move_On=true;
							if($Plage and $Placement ==11)
							{
								$Faction_Plage=GetData("Pays","ID",$Flag_Plage,"Faction");
								if($Faction_Plage !=$Faction)
									$Move_On=false;
							}
						}
						else
							$Move_On=false;
						if($Credits >=2 and $Cible_base >0 and $Placement !=1 and $Move_On){?>
						<Input type='Radio' name='Action' value='7' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir la base aérienne <a href='#' class='popup'><img src='images/help.png'><span>Occuper cette zone avec votre bataillon empêchera les escadrilles ennemies de décoller de cette base</span></a><br>
						<?}if($Credits >=2 and $NoeudR and $Placement !=2){?>
						<Input type='Radio' name='Action' value='8' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir le noeud routier <a href='#' class='popup'><img src='images/help.png'><span>Occuper cette zone avec votre bataillon empêchera les unités ennemies de bénéficier du bonus de déplacement. Ne pas encombrer cette zone avec votre bataillon facilite le déplacement des unités EM</span></a><br>
						<?}if($Credits >=2 and $NoeudF_Ori and $Placement !=3 and $Move_On){?>
						<Input type='Radio' name='Action' value='9' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir la gare <a href='#' class='popup'><img src='images/help.png'><span>Occuper cette zone avec votre bataillon empêchera les unités ennemies de bénéficier du déplacement ferroviaire</span></a><br>
						<?}if($Credits >=2 and $Port_Ori and $Placement !=4 and $Move_On){?>
						<Input type='Radio' name='Action' value='10' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir le port <a href='#' class='popup'><img src='images/help.png'><span>Contrôler cette zone empêchera les unités ennemies de bénéficier des infrastructures portuaires</span></a><br>
						<?}if($Credits >=2 and ($Pont_Ori or $Fleuve) and $Placement !=5 and $Move_On){?>
						<Input type='Radio' name='Action' value='11' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir les berges du fleuve <a href='#' class='popup'><img src='images/help.png'><span>Contrôler cette zone empêchera les unités ennemies de bénéficier du déplacement fluvial</span></a><br>
						<?}if($Credits >=2 and $Usine and $Placement !=6 and $Move_On){?>
						<Input type='Radio' name='Action' value='12' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir l'usine <a href='#' class='popup'><img src='images/help.png'><span>Contrôler cette zone stoppera la production de cette usine</span></a><br>
						<?}if($Credits >=2 and $Radar_Ori and $Placement !=7 and $Move_On){?>
						<Input type='Radio' name='Action' value='13' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir la station radar <a href='#' class='popup'><img src='images/help.png'><span>Contrôler cette zone stoppera la surveillance de ce radar</span></a><br>
						<?}if($Credits >=2 and $Plage and $Placement !=11 and $Move_On){?>
						<Input type='Radio' name='Action' value='298' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir la plage <a href='#' class='popup'><img src='images/help.png'><span>Cette zone est la seule où des unités ennemies peuvent être débarquées</span></a><br>
						<?}if($Credits >=2 and $Placement !=0){
						if($Pont_Ori ==100 and !$Pont){?>
						<Input type='Radio' name='Action' value='14' title='Mince alors!' disabled>- Caserne inaccessible car le pont est détruit<br>
						<?}else{?>
						<Input type='Radio' name='Action' value='14' title='Ready!'>
						<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Investir la caserne <a href='#' class='popup'><img src='images/help.png'><span>Revendiquer cette zone permet de contrôler le lieu. Ne pas encombrer cette zone avec votre bataillon facilite le déplacement des unités EM</span></a><br>
						<?}}
					}
					if($Credits >=1 and $Cible !=$Retraite and !$Atk_mode and ($Enis_vis+$Enis_vis_ia) >0)
					{
						?><Input type='Radio' name='Action' value='400' title='Vous vous avouez vaincu...'> <img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Se rendre</span> <a href='#' class='popup'><img src='images/help.png'><span>Perte de 100% des effectifs, 100% des stocks et 100% du chargement éventuel</span></a><br><?
					}
				}
				if($Credits>=2 and $g_mobile ==5 and $Zone ==6 and !$Enis and $Cible !=$Retraite and $Faction_flag !=$Faction and $Position!=25 and $Bat_Veh_Nbr >0 and !$Canada and !$G_Treve)
					echo "<Input type='Radio' name='Action' value='69' title='Les troupes de votre faction combattront avec l´avantage du terrain!'>
					<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Quadriller la zone maritime<br>";
				if($Credits>=2 and $Division >0 and !$Enis and $Placement!=8 and $Placement!=9 and $g_mobile!=4 and $g_mobile!=5 and $Position!=11 and $Position!=6 and $Position!=8 and $Position!=9 and $Bat_Veh_Nbr >0 and !$Canada and !$G_Treve)
				{
					if($Placement ==1)
					{
						$Faction_Zone=GetData("Pays","ID",$Flag_Air,"Faction");
						if($Faction_Zone !=$Faction)
							echo "<Input type='Radio' name='Action' value='61' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
					elseif($Placement ==2)
					{
						$Faction_Zone=GetData("Pays","ID",$Flag_Route,"Faction");
						if($Faction_Zone !=$Faction)
							echo "<Input type='Radio' name='Action' value='62' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
					elseif($Placement ==3)
					{
						$Faction_Zone=GetData("Pays","ID",$Flag_Gare,"Faction");
						if($Faction_Zone !=$Faction)
							echo "<Input type='Radio' name='Action' value='63' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
					elseif($Placement ==4)
					{
						$Faction_Zone=GetData("Pays","ID",$Flag_Port,"Faction");
						if($Faction_Zone !=$Faction)
							echo "<Input type='Radio' name='Action' value='64' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
					elseif($Placement ==5)
					{
						$Faction_Zone=GetData("Pays","ID",$Flag_Pont,"Faction");
						if($Faction_Zone !=$Faction)
							echo "<Input type='Radio' name='Action' value='65' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
					elseif($Placement ==6)
					{
						$Faction_Zone=GetData("Pays","ID",$Flag_Usine,"Faction");
						if($Faction_Zone !=$Faction)
							echo "<Input type='Radio' name='Action' value='66' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
					elseif($Placement ==7)
					{
						$Faction_Zone=GetData("Pays","ID",$Flag_Radar,"Faction");
						if($Faction_Zone != $Faction)
							echo "<Input type='Radio' name='Action' value='67' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
					elseif($Placement ==11)
					{
						$Faction_Zone=GetData("Pays","ID",$Flag_Plage,"Faction");
						if($Faction_Zone !=$Faction)
							echo "<Input type='Radio' name='Action' value='68' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
					elseif($Placement ==0 and $Faction_flag !=$Faction and $Garnison <1)
					{
						$Rev_ok=false;
						$Faction_Ori=GetData("Pays","ID",$Pays_Ori,"Faction");
						if($Faction ==$Faction_Ori)
						{
							$Pays_Rev=$Pays_Ori;
							$Faction_Rev=$Faction_Ori;
						}
						else
						{
							$Pays_Rev=$country;
							$Faction_Rev=$Faction;
						}
						if($Flag_Pont)$Faction_Pont=GetData("Pays","ID",$Flag_Pont,"Faction");
						if($Flag_Port)$Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
						if($Flag_Gare)$Faction_Gare=GetData("Pays","ID",$Flag_Gare,"Faction");
						if($Flag_Route)$Faction_Route=GetData("Pays","ID",$Flag_Route,"Faction");
						if($Flag_Air)$Faction_Air=GetData("Pays","ID",$Flag_Air,"Faction");
						if($Flag_Usine)$Faction_Usine=GetData("Pays","ID",$Flag_Usine,"Faction");
						if($Flag_Radar)$Faction_Radar=GetData("Pays","ID",$Flag_Radar,"Faction");
						if($Flag_Plage)$Faction_Plage=GetData("Pays","ID",$Flag_Plage,"Faction");
						if($ValeurStrat ==10)
						{
							$Rev_ok=true;
							if(($Pont_Ori or $Fleuve) and $Faction_Pont !=$Faction_Rev)
								$Rev_ok=false;
							if($Port_Ori and $Faction_Port !=$Faction_Rev)
								$Rev_ok=false;
							if($NoeudF_Ori and $Faction_Gare !=$Faction_Rev)
								$Rev_ok=false;
							if($NoeudR and $Faction_Route !=$Faction_Rev)
								$Rev_ok=false;
							if($Cible_base and $Faction_Air !=$Faction_Rev)
								$Rev_ok=false;
							if($Usine and $Faction_Usine !=$Faction_Rev)
								$Rev_ok=false;
							if($Radar_Ori and $Faction_Radar !=$Faction_Rev)
								$Rev_ok=false;
							if($Plage and $Faction_Plage !=$Faction_Rev)
								$Rev_ok=false;
						}
						elseif($ValeurStrat >5)
						{
							//3 zones
							$Rev_part=0;
							if($Pont_Ori or $Fleuve)
							{
								if($Faction_Pont ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Port_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve)))
							{
								if($Faction_Port ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($NoeudF_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori)))
							{
								if($Faction_Gare ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($NoeudR and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori)))
							{
								if($Faction_Route ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Cible_base and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR)))
							{
								if($Faction_Air ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Usine and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base)))
							{
								if($Faction_Usine ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Radar_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base and !$Usine)))
							{
								if($Faction_Radar ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Plage and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base and !$Usine and !$Radar_Ori)))
							{
								if($Faction_Plage ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Rev_part >=2)
								$Rev_ok=true;
						}
						elseif($ValeurStrat >3)
						{
							//2 zones
							$Rev_part=0;
							if($Pont_Ori or $Fleuve)
							{
								if($Faction_Pont ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Port_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve)))
							{
								if($Faction_Port ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($NoeudF_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori)))
							{
								if($Faction_Gare ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($NoeudR and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori)))
							{
								if($Faction_Route ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Cible_base and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR)))
							{
								if($Faction_Air ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Usine and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base)))
							{
								if($Faction_Usine ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Radar_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base and !$Usine)))
							{
								if($Faction_Radar ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Plage and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base and !$Usine and !$Radar_Ori)))
							{
								if($Faction_Plage ==$Faction_Rev)
									$Rev_part+=1;
							}
							if($Rev_part >=1)
								$Rev_ok=true;
						}
						elseif($ValeurStrat >0)
						{
							if($Pont_Ori or $Fleuve)
							{
								if($Faction_Pont ==$Faction_Rev)
									$Rev_ok=true;
							}
							elseif($Port_Ori)
							{
								if($Faction_Port ==$Faction_Rev)
									$Rev_ok=true;
							}
							elseif($NoeudF_Ori)
							{
								if($Faction_Gare ==$Faction_Rev)
									$Rev_ok=true;
							}
							elseif($NoeudR)
							{
								if($Faction_Route ==$Faction_Rev)
									$Rev_ok=true;
							}
							elseif($Cible_base)
							{
								if($Faction_Air ==$Faction_Rev)
									$Rev_ok=true;
							}
							elseif($Usine)
							{
								if($Faction_Usine ==$Faction_Rev)
									$Rev_ok=true;
							}
							elseif($Radar_Ori)
							{
								if($Faction_Radar ==$Faction_Rev)
									$Rev_ok=true;
							}
							elseif($Plage)
							{
								if($Faction_Plage ==$Faction_Rev)
									$Rev_ok=true;
							}
						}
						else
							$Rev_ok=true;
						if($Rev_ok)
							echo "<Input type='Radio' name='Action' value='60' title='Les troupes de votre faction combattront avec l´avangage du terrain!'>
							<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Revendiquer la position <a href='help/aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a><br>";
					}
				}
				if($Credits >=$Credits_form and $Cible ==$Retraite and $Position !=11 and $Position !=8){?>
				<Input type='Radio' name='Action' value='16' title='Rien de tel pour être fin prêt!'>
				<img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Effectuer des manoeuvres d'entrainement <a href='#' class='popup'><img src='images/help.png'><span>Augmente l'expérience de toutes vos Compagnies jusqu'à un maximum de 50</span></a><br>
				<?}if($Credits >= $Credits_form and !$Enis and $Placement ==0 and $Faction_flag == $Faction and $Position !=11){?>
				<Input type='Radio' name='Action' value='18' title='Un discours, une messe, quelques cigarettes... peu importe! Ne fonctionne que dans la caserne'>
				<img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'>- Remonter le moral des troupes<br>
				<?}if($Credits >=2 and $Position !=11){?>
				<Input type='Radio' name='Action' value='401' title='Partager vos munitions ou votre carburant entre vos compagnies'>
				<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Gérer les stocks du bataillon <a href='#' class='popup'><img src='images/help.png'><span>Partager vos munitions ou votre carburant entre vos compagnies</span></a><br>
				<?}if($Credits >0 and $Placement ==3 and $Position !=11 and ($Position ==8 or $Position ==9) and $g_mobile ==4){?>
				<Input type='Radio' name='Action' value='74' title='Il est temps de passer à autre chose!'>
				<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'>- Rentrer au dépôt <a href='#' class='popup'><img src='images/help.png'><span>Lorsque votre train est bombardé, réfugiez-vous dans le dépôt!</span></a><br>
				<?}
				if($Credits >=40 and $Faction_flag ==$Faction and $Position !=11 and $Position != 8 and $Position !=9)
				{
					$Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
					if($Cible == $Retraite and $Placement ==3 and $NoeudF >10 and $Flag_Gare ==$country and $g_mobile !=4 and $Front !=3){?>
					<Input type='Radio' name='Action' value='70' title='Il est temps de passer à autre chose!'>
					<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Convertir le bataillon en convoi ferroviaire</span> <a href='#' class='popup'><img src='images/help.png'><span>ATTENTION! Vous perdrez toutes vos troupes qui seront remplacées par un train!</span></a><br>
					<?}if($Placement ==3 and $g_mobile ==4){?>
					<Input type='Radio' name='Action' value='71' title='Il est temps de passer à autre chose!'>
					<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Convertir le convoi ferroviaire en bataillon</span> <a href='#' class='popup'><img src='images/help.png'><span>ATTENTION! Vous perdrez votre train qui sera remplacé par des compagnies infanterie!</span></a><br>
					<?}if($Placement ==4 and $Port >0 and $Faction_Port ==$Faction and $g_mobile !=5 and $Front !=1){?>
					<Input type='Radio' name='Action' value='72' title='Il est temps de passer à autre chose!'>
					<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Convertir le bataillon en convoi maritime</span> <a href='#' class='popup'><img src='images/help.png'><span>ATTENTION! Vous perdrez toutes vos troupes qui seront remplacées par des navires cargos!</span></a><br>
					<?}if($Zone !=6 and $Placement ==4 and $g_mobile ==5 and $Position !=25){?>
					<Input type='Radio' name='Action' value='73' title='Il est temps de passer à autre chose!'>
					<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- <span class='text-danger'>Convertir le convoi maritime en bataillon</span> <a href='#' class='popup'><img src='images/help.png'><span>ATTENTION! Vous perdrez vos navires qui seront remplacés par des compagnies infanterie!</span></a><br>
					<?}
				}
		if($Position ==11)
		{
			if($Credits >=40)
				echo "<Input type='Radio' name='Action' value='99' title='Sortez-moi de là!'>
				<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'>- Annuler la demande de transit<br>";
			else
				echo "Votre unité est actuellement en transit, vous devez attendre que votre transport vous amène à destination. Cela peut prendre plusieurs jours.
				<br>Pour <img src='/images/CT40.png'> vous pouvez annuler à tout moment votre demande de transit. Cette action amènera vos troupes à leur lieu de départ.";
		}
		echo "</td></tr>";
		if(($Credits >=1 and $Position !=11) or ($Position ==11 and $Credits >=40))
			echo "<tfoot><tr><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr></tfoot>";
		echo "</table></div></form></div>";
		if($Help_Div)
			echo "<h2>Demande de renforts</h2><table class='table'><thead><th>Officier</th><th>Lieu</th></thead>".$Help_Div."</table>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>