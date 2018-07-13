<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if($Officier_pvp >0 or $Pilote_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_pvp.php');
	if(!$Battle)$Battle=Insec($_POST['Battle']);
	if(!$Faction)$Faction=Insec($_POST['Camp']);
	$Cible=GetCiblePVP($Battle);
	if($Cible)
	{
		include_once('./jfv_map.inc.php');
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Pays,Zone,Map,Meteo,Meteo_Hour,Latitude,Longitude,Occupant,Plage,ValeurStrat,DefenseAA_temp,BaseAerienne,QualitePiste,LongPiste,
		Industrie,Pont,Pont_Ori,Radar_Ori,Radar,Port_Ori,Port,Port_level,NoeudR,NoeudF,NoeudF_Ori,Flag,Garnison,Fortification,Oil,Flag_Air,Flag_Route,Flag_Gare,Flag_Port,Flag_Pont,Flag_Usine,Flag_Radar,Flag_Plage,Recce 
		FROM Lieu WHERE ID='$Cible'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Cible_nom=$data['Nom'];
				$Pays_Ori=$data['Pays'];
				$Lat=$data['Latitude'];
				$Long=$data['Longitude'];
				$ValeurStrat=$data['ValeurStrat'];
				$Cible_base=$data['BaseAerienne'];
				$QualitePiste=$data['QualitePiste'];
				$Piste=$data['LongPiste'];
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
				$Flag=$data['Flag'];
				$Garnison=$data['Garnison'];
				$Fortification=$data['Fortification'];
				$Oil=$data['Oil'];
				$Plage=$data['Plage'];
				$Recce=$data['Recce'];
				$Meteo=$data['Meteo'];
				$Front_Lieu=GetFrontByCoord(0,$Lat,$Long);
				$Zone_txt=GetZone($Zone);
				$Region='Zone '.$Zone_txt;
			}
			mysqli_free_result($result);
			unset($data);
		}		
		if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
			$img_gen='images/lieu/lieu'.$Cible.'.jpg';
		else
		{
			if($Nuit)
				$img_gen='images/lieu/objectif_nuit'.$Map.'.jpg';
			elseif($Zone ==8)
			{
				if($Map ==0 or $Map ==1)
					$img_gen='images/dune_sea.jpg';
				elseif($Map ==2 or $Map ==3)
					$img_gen='images/desert_airfield.jpg';
			}
			elseif($Zone ==9)
			{
				if($Map ==0 or $Map ==1)
					$img_gen='images/jungle.jpg';
				elseif($Map ==2 or $Map ==3)
					$img_gen='images/pacific_airfield.jpg';
				elseif($Map ==8)
					$img_gen='images/jungle_port.jpg';
			}
			if(!$img_gen)
				$img_gen='images/lieu/objectif'.$Map.'.jpg';
		}
		$Faction_Flag=GetData("Pays","ID",$Flag,"Faction");
		if($Faction_Flag ==$Faction or $Recce >0 or $Admin ==1)
		{
			$Meteo_txt="<img src='images/meteo".$Meteo.".jpg'>";
			$dca="<img src='images/vehicules/vehicule16.gif'> ?";
			$Garnison="<img src='images/vehicules/vehicule107.gif'> ?";
			if($Fortification)
				$Fortification="<img src='images/icone_fort.gif'> ?";
			if($Cible_base)
			{
				if($Cible_base ==3)
				{
					if($Zone ==8)
						$QualitePiste_img="piste38_".GetQualitePiste_img($QualitePiste).".jpg";
					if($Zone ==0 or $Zone ==2 or $Zone ==3 or $Zone ==9)
						$QualitePiste_img="piste32_".GetQualitePiste_img($QualitePiste).".jpg";
					else
						$QualitePiste_img="piste31_".GetQualitePiste_img($QualitePiste).".jpg";
				}
				else
					$QualitePiste_img="piste".$Cible_base."_".GetQualitePiste_img($QualitePiste).".jpg";
				$icones.="<img src='images/".$QualitePiste_img."'>";
			}
			if($Pont_Ori)
			{
				$icones.="<img src='images/vehicules/vehicule10.gif' title='Pont'>"; 
			}
			if($Port_Ori)
			{
				if($Port_level <1)
					$Port_txt="Port secondaire, incapable de recevoir les grosses unités. Pas d accès au garage.";
				else
					$Port_txt="Port doté de toutes les infrastructures";
				$icones.="<img src='images/vehicules/vehicule12.gif' title='".$Port_txt."'>";
			}
			if($Radar_Ori)
			{
				$icones.="<img src='images/vehicules/vehicule15.gif' title='Radar'>"; 
			}
			if($NoeudF_Ori)
			{
				$icones.="<img src='images/vehicules/vehicule9.gif' title='Gare'>";
			}
			if($NoeudR)
			{
				$icones.="<img src='images/map/lieu_route0.png' title='Noeud Routier'>";
			}
			if($Usine)
			{
				$icones.="<img src='images/vehicules/vehicule5.gif' title='Usine'>";
			}
			if($Oil)
				$icones.="<img src='images/map/icone_oil.gif' title='Raffinerie'>";
		}
		else
		{
			$Garnison="Inconnu";
			$Fortification="Inconnu";
		}			
		$air_units='';
		$units='';
		if($Front ==$Front_Lieu or $Admin or $GHQ)
		{
			include_once('./jfv_ground.inc.php');
			/*if($Admin ==1)
				$query_blitz="SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Distance,c.Nom,c.Taille,c.Portee,r.Ravit,r.Bomb_IA FROM Regiment_PVP as r,Cible as c 
				WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' ORDER BY r.Placement ASC,r.Pays ASC,r.ID ASC";
			else*/
				$query_blitz="SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Distance,c.Nom,c.Taille,c.Portee,r.Ravit,r.Bomb_IA FROM Regiment_PVP as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr>0 AND (p.Faction='$Faction' OR r.Visible=1) ORDER BY r.Placement ASC, r.Distance ASC";
			/*else
				$query_blitz="SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Distance,c.Nom,c.Taille,c.Portee FROM Regiment_PVP as r,Cible as c
				WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr>0 AND (r.Pays='$country' OR r.Visible=1) ORDER BY r.Placement ASC, r.Distance ASC";*/
			$con=dbconnecti();
			$result_blitz=mysqli_query($con,$query_blitz);
			mysqli_close($con);
			if($result_blitz)
			{
				while($data=mysqli_fetch_array($result_blitz))
				{
					$exp="";
					if(!$data['Distance'])
						$Distance_v=$data['Portee'];
					else
						$Distance_v=$data['Distance'];
					if($Admin ==1)
					{
						$Vis_v=$data['Taille']/$data['Camouflage'];
						if($data['Officier_ID'] >0)
						{
							if($data['Bomb_IA'])$exp="<img src='images/map/noiapj.png' title='Ne peut plus être ciblé par les IA jusque son prochain déplacement'>";
							$exp.=" (".$data['Visible']."V - ".$data['Bomb_IA']."B - Taille ".$Vis_v." - ".GetPosGr($data['Position'])." - ".$data['Experience']."XP - [".GetData("Officier_PVP","ID",$data['Officier_ID'],"Credits")."]CT - ".$data['HP']." HP - ".$data['Moral']." Moral || Stocks 87 ".$data['Stock_Essence_87']."; Stocks Diesel ".$data['Stock_Essence_1']."; Stocks 8mm ".$data['Stock_Munitions_8'].")";
						}
						else
						{
							if($data['Bomb_IA'])$exp="<img src='images/map/noia.png' title='Ne peut plus être ciblé par les bombardements tactiques IA jusque au prochain passage de date'>";
							if($data['Ravit'])$exp.="<img src='images/map/air_ravit.png' title='Ravitaillé par air'>";
							$exp.=" (".$data['Visible']."V - ".$data['Bomb_IA']."B - Taille ".$Vis_v." - ".GetPosGr($data['Position'])." - ".$data['Experience']."XP - Officier IA - ".$data['HP']." HP - ".$data['Moral']." Moral)";
						}
					}
					if($data['Placement'] !=$Placement_Ori)
						$titrez="<tr bgcolor='tan'><th colspan='15'>".GetPlace($data['Placement'])."</th></tr>";
					else
						$titrez="";
					if($data['Pays'] ==$country or $Admin ==1)
					{
						$Cdt=GetData("Officier_PVP","ID",$data['Officier_ID'],"Nom");
						if($Officier_acces)
							$Veh_Nbr=$data['Vehicule_Nbr'];
						else
							$Veh_Nbr=RangeNbr($data['Vehicule_Nbr']);
					}
					else
					{
						$Cdt=GetData("Officier_PVP","ID",$data['Officier_ID'],"Nom");
						if(!$data['Officier_ID'] and $data['Position'] ==11)
						{
							$data['Vehicule_ID']=5000;
							$Veh_Nbr=floor($Veh_Nbr/10);
							$data['Nom']="Barges de transport";
						}
						else
							$Veh_Nbr=RangeNbr($data['Vehicule_Nbr']);
					}
					$Placement_Ori=$data['Placement'];
					$units.=$titrez;
					$units.="<tr><td align='left'>".$data['ID']."e Compagnie</td>
					<td><img src='".$data['Pays']."20.gif'></td><td>".$Cdt."</td>
					<td>".$Veh_Nbr."</td><td>".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front_Lieu,$data['Nom']).$exp."</td><td>".$Distance_v."m</td></tr>";	
				}
				mysqli_free_result($result_blitz);
			}
			else
				$mes.="Désolé, aucune unité terrestre recensée.";
			if($Plage)
				$Plage_txt="<img src='images/plage.jpg' title='Zone propice au débarquement'>";
		}
		//Output
		$mes.=$header."<h1>".$Cible_nom."</h1>".$toolbar."<div class='row'><div class='col-md-6'><img src='".$img_gen."' title='".$Cible_nom."' style='width:100%;'></div>
		<div class='col-md-6'><table class='table'><thead><tr><th>Territoire</th><th>Terrain</th><th>Météo</th></tr></thead>
		<tr><td><img src='images/flag".$Pays_Ori."p.jpg' title='".GetPays($Pays_Ori)."'></td><td><img src='images/zone".$Zone.".jpg' title='".$Region."'>".$Plage_txt."</td>
		<td>".$Meteo_txt."</td></tr></table><table class='table'><thead><tr><th>Infrastructures</th></tr></thead><tr><td>".$icones."</td></tr></table></div></div>";
		if($units)
		{
			$mes.="<h2>Unités présentes à ".$Cible_nom."</h2><table class='table table-striped'>
				<thead><tr><th>Unité</th><th width='50px'>Nation</th><th>Commandant</th><th width='50px'>Effectifs</th><th>Troupes</th><th>Distance</th></tr></thead>
				".$units."</table>";
		}
		$mes.=$menu.$dca_pieces.$usine_txt.$depot.$footer;
		echo $mes;
	}
	else
		echo "Tsss!";
}?>