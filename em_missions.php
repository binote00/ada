<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once './jfv_include.inc.php';
	include_once './jfv_txt.inc.php';
	include_once './jfv_inc_em.php';
	include_once './menu_em.php';
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Mer or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk or $Admin or $Armee)
	{
		$Co_Lieu=Insec($_POST['lieu_co']);
        if($_SESSION['msg_esc'])
            $Alert_Msg = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_esc'].'</div>';
        $_SESSION['msg_esc'] = false;
		if($Admin and $Front ==99)
			$Front_list='0,1,2,3,4,5';
		else
			$Front_list=$Front;
        if(!$Admin)$query_add="AND u.Armee=0 ";
		$Coord=GetCoord($Front);
		$Lat_base_min=$Coord[0];
		$Lat_base_max=$Coord[1];
		$Long_base_min=$Coord[2];
		$Long_base_max=$Coord[3];
		$height_mia=40;
		$height_dem=40;
		$Sqn=GetSqn($country);
		if($Premium and !$Armee)
		{
			if($Front ==99)
			{
				if($country ==8 or $country ==17 or $country ==18 or $country ==19)
					$Front=1;
				elseif($country ==6 or $country ==10)
					$Front=2;
				elseif($country ==20)
					$Front=5;
                elseif($country ==9)
                    $Front=3;
				else
					$Front=0;
			}
			$Legend=true;
			if($Co_Lieu)
			{
				SetDoubleData("Pays","Co_Lieu_Mission",$Co_Lieu,"Pays_ID",$country,"Front",$Front);
				header("Refresh:0");
			}
			else
			{
				$con=dbconnecti();
				$result2=mysqli_query($con,"SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Pays as p,Lieu as l WHERE p.Co_Lieu_Mission=l.ID AND p.Pays_ID='$country' AND p.Front='$Front'");
				mysqli_close($con);
				if($result2)
				{
					while($Data=mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
					{
						$Co_Lieu_Mission=$Data['ID'];
						$Co_Lieu_Mission_Nom=$Data['Nom'];
						$Co_Lieu_Mission_Lat=$Data['Latitude'];
						$Co_Lieu_Mission_Long=$Data['Longitude'];
					}
					mysqli_free_result($result2);
				}
			}
			if(!$Co_Lieu_Mission)
				$Co_Lieu_Mission_Nom='Aucune';
			else
			{
				$qunits="SELECT u.ID,u.Nom,u.Base,u.Type,u.Avion1,u.Avion2,u.Avion3,l.Latitude,l.Longitude FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND l.Latitude >='$Lat_base_min' AND l.Latitude <='$Lat_base_max' AND l.Longitude >='$Long_base_min' AND l.Longitude <'$Long_base_max'
				AND u.Pays='$country' ".$query_add."AND u.Etat >0 AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.Mission_IA=0 AND l.QualitePiste >50 AND l.Meteo >-50";
				$con=dbconnecti();
				$resultr=mysqli_query($con,$qunits);
				if($resultr)
				{
					while($datal=mysqli_fetch_array($resultr,MYSQLI_ASSOC)) 
					{
						$autonomies_u=false;
						$autonomies_u_s=false;
						$autonomies_u_t=false;
						$resultau=mysqli_query($con,"SELECT Autonomie,Masse,Bombe,Bombe_Nbr,Puissance FROM Avion WHERE ID IN('".$datal['Avion1']."','".$datal['Avion2']."','".$datal['Avion3']."')");
						if($resultau)
						{
							while($dataau=mysqli_fetch_array($resultau))
							{
								$auto_actu=floor(($dataau['Autonomie']/2)-200);
								if($auto_actu <50)$auto_actu=50;
								$autonomies_u[]=$auto_actu;
								if($datal['Type'] ==2 or $datal['Type'] ==7 or $datal['Type'] ==10 or $datal['Type'] ==11)
								{
									$Massef_s=$dataau['Masse']+($dataau['Bombe']*$dataau['Bombe_Nbr']);
									$Massef_t=$dataau['Masse']+$dataau['Bombe'];
									$Poids_Puiss_ori=$dataau['Masse']/$dataau['Puissance'];
									$Poids_Puiss_s=$Massef_s/$dataau['Puissance'];
									$Poids_Puiss_t=$Massef_t/$dataau['Puissance'];
									if($datal['Type'] ==2 or $datal['Type'] ==11)
										$autonomies_u[]=round($dataau['Autonomie']-(($Poids_Puiss_s-$Poids_Puiss_ori)*($Massef_s/10)));
									if($datal['Type'] ==2 or $datal['Type'] ==7 or $datal['Type'] ==10)
										$autonomies_u[]=round((($dataau['Autonomie']/2)-200)-(($Poids_Puiss_t-$Poids_Puiss_ori)*($Massef_t/10)));
								}
							}
							mysqli_free_result($resultau);
							$Autonomie_max=min(array_filter($autonomies_u));
						}
						$Dist_u=GetDistance($Co_Lieu_Mission,$datal['Base'],$Co_Lieu_Mission_Long,$Co_Lieu_Mission_Lat,$datal['Longitude'],$datal['Latitude']);
						if($Dist_u[0] <=$Autonomie_max)$Co_units[]=$datal['ID'];
					}
					mysqli_free_result($resultr);
				}
				mysqli_close($con);
				if(is_array($Co_units))$Co_units_list=implode(", ",$Co_units);
			}
		}
		//Missions IA
		$txt_missions_ia='';
		$txt_action_ia='';
		if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $Admin)
		{
			$menu_units_ia="<p>";
			if($Admin)$menu_units_ia.="<a class='btn btn-default' href='index.php?view=em_missions'>Tout</a>";
			$menu_units_ia.="<a class='btn btn-default' href='index.php?view=em_missions_7'>Attaque</a>
			<a class='btn btn-default' href='index.php?view=em_missions_2'>Bombardier</a>
			<a class='btn btn-default' href='index.php?view=em_missions_11'>Bombardier lourd</a>
			<a class='btn btn-default' href='index.php?view=em_missions_1'>Chasse</a>
			<a class='btn btn-default' href='index.php?view=em_missions_12'>Chasse embarquée</a>
			<a class='btn btn-default' href='index.php?view=em_missions_4'>Chasse lourde</a>
			<a class='btn btn-default' href='index.php?view=em_missions_10'>Embarqué</a>
			<a class='btn btn-default' href='index.php?view=em_missions_9'>Pat Mar</a>
			<a class='btn btn-default' href='index.php?view=em_missions_3'>Reco</a>
			<a class='btn btn-default' href='index.php?view=em_missions_6'>Transport</a>
			<a class='btn btn-warning' href='index.php?view=em_missions_99'>Mission</a>
			</p>";
		}
        elseif($OfficierEMID ==$Officier_Mer)
            $menu_units_ia="<a class='btn btn-default' href='index.php?view=em_missions_9'>Patrouille Maritime</a>";
		elseif($OfficierEMID ==$Cdt_Chasse)
			$menu_units_ia="<p><a class='btn btn-default' href='index.php?view=em_missions_1'>Chasse</a>
			<a class='btn btn-default' href='index.php?view=em_missions_4'>Chasse lourde</a></p>";
		elseif($OfficierEMID ==$Cdt_Bomb)
			$menu_units_ia="<p><a class='btn btn-default' href='index.php?view=em_missions_2'>Bombardier</a>
			<a class='btn btn-default' href='index.php?view=em_missions_11'>Bombardier lourd</a></p>";
		elseif($OfficierEMID ==$Cdt_Reco)
			$menu_units_ia="<p><a class='btn btn-default' href='index.php?view=em_missions_9'>Pat Mar</a>
			<a class='btn btn-default' href='index.php?view=em_missions_3'>Reco</a></p>";
		elseif($OfficierEMID ==$Cdt_Atk)
		{
			if($country ==2 or $country ==7 or $country ==9)
				$menu_units_ia="<p><a class='btn btn-default' href='index.php?view=em_missions_7'>Attaque</a>
				<a class='btn btn-default' href='index.php?view=em_missions_12'>Chasse embarquée</a>
				<a class='btn btn-default' href='index.php?view=em_missions_10'>Embarqué</a></p>";
			else
				$menu_units_ia="<p><a class='btn btn-default' href='index.php?view=em_missions_7'>Attaque</a>";
		}
		elseif($Armee)
		{
			$menu_units_ia="<p>
			<a class='btn btn-default' href='index.php?view=em_missions_7'>Attaque</a>
			<a class='btn btn-default' href='index.php?view=em_missions_2'>Bombardier</a>
			<a class='btn btn-default' href='index.php?view=em_missions_1'>Chasse</a>
			<a class='btn btn-default' href='index.php?view=em_missions_9'>Pat Mar</a>
			<a class='btn btn-default' href='index.php?view=em_missions_3'>Reco</a>
			<a class='btn btn-default' href='index.php?view=em_missions_6'>Transport</a>
			</p>";
		}
		$Mis_list="1,2,4,5,6,7,8,11,12,13,14,15,16,17,23,29,32";
		if($Type ==99)
		{
			$units_list='6';
			$Mis_list="1,2,5,8,11,12,13,14,15,16,29";
		}
        elseif($OfficierEMID ==$Officier_Mer){
            $units_list="9";
            $Mis_list="5,14,29";
        }
		elseif($Type >0)
		{
			$units_list=$Type;
			if($Type ==1)
				$Mis_list="4,7";
			elseif($Type ==2)
				$Mis_list="2,8,11,12,13,16";
			elseif($Type ==3)
				$Mis_list="5,15,32";
			elseif($Type ==4)
				$Mis_list="7,17";
			elseif($Type ==6)
				$Mis_list="23";
			elseif($Type ==7)
				$Mis_list="2,8,11,12,13";
			elseif($Type ==9)
				$Mis_list="5,14,29";
			elseif($Type ==10)
				$Mis_list="2,5,8,11,12,13";
			elseif($Type ==11)
				$Mis_list="8,16";
			elseif($Type ==12)
				$Mis_list="4,5,7";
			else
                $Mis_list="6";
		}
		elseif($GHQ)
			$units_list="6";
		elseif($OfficierEMID ==$Commandant)
			$units_list="6";
		elseif($OfficierEMID ==$Officier_Adjoint)
			$units_list="6";
		elseif($OfficierEMID ==$Cdt_Chasse)
			$units_list="1,4";
		elseif($OfficierEMID ==$Cdt_Bomb)
			$units_list="2,11";
		elseif($OfficierEMID ==$Cdt_Reco)
			$units_list="3,9";
		elseif($OfficierEMID ==$Cdt_Atk)
			$units_list="5,7,10,12";
		elseif($Admin)
			$units_list="1,2,3,4,5,6,7,9,10,11,12";
		/*if($Admin ==1){
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.QualitePiste,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Base=l.ID AND u.Etat >0 AND u.Type IN (1,4,12) AND u.Commandant=0 AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) ORDER BY l.Nom ASC";
		}
		if($country ==6){
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.QualitePiste,l.Zone,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Base=l.ID AND u.Etat >0 AND u.Type IN (".$units_list.") AND u.Commandant=0 AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.Pays=6 ORDER BY l.Nom ASC";
		}
		else*/
		if($Premium and $Type ==99 and $Co_Lieu_Mission)
		{
			$Alert_Msg="<p class='lead'>Objectif de Mission : ".$Co_Lieu_Mission_Nom."<p>";
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Base=l.ID ".$query_add."AND u.Commandant IS NULL AND u.NoEM=0 AND u.ID IN (".$Co_units_list.") ORDER BY u.Type ASC";
		}
		elseif($Armee)
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 AND u.Armee='$Armee' AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 ORDER BY l.Nom ASC";
		elseif($Front ==3)
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 AND l.Longitude >67 ORDER BY l.Nom ASC";
		elseif($Front ==2)
		{
			if($country ==4)
				$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
				WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 AND l.Latitude <41 AND l.Longitude <50 ORDER BY l.Nom ASC";
			else
				$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
				WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 AND l.Latitude <43 AND l.Longitude <50 ORDER BY l.Nom ASC";
		}
		elseif($Front ==1)
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 AND l.Latitude >41 AND l.Latitude <=50.5 AND l.Longitude >13 AND l.Longitude < 60 ORDER BY l.Nom ASC";
		elseif($Front ==4)
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 AND l.Latitude >50.5 AND l.Longitude >13 AND l.Longitude <60 ORDER BY l.Nom ASC";
		elseif($Front ==5)
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 AND l.Latitude >60 AND l.Longitude >-50 AND l.Longitude <60 ORDER BY l.Nom ASC";
		elseif($Front ==99)
			$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) ORDER BY l.Nom ASC";
		else
		{
			if($country ==7)
				$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
				WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 AND l.Longitude <14 ORDER BY l.Nom ASC";
			else
				$query_unit="SELECT u.ID,u.Nom,u.Mission_Type,u.Mission_Lieu,u.Base,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Mission_IA,u.Type,u.Porte_avions,l.Latitude,l.Longitude,l.QualitePiste,l.Zone,l.Port,l.Flag_Port,l.Flag,l.Flag_Air,l.Nom as Base_nom FROM Lieu as l,Unit as u 
				WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Base=l.ID AND u.Etat >0 ".$query_add."AND u.Commandant IS NULL AND (u.Avion1_Nbr+u.Avion2_Nbr+u.Avion3_Nbr >0) AND u.NoEM=0 AND l.Latitude >=43 AND l.Latitude <60 AND l.Longitude <14 ORDER BY l.Nom ASC";
		}
		$querylieux="SELECT DISTINCT ID,Nom FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max' ORDER BY Nom ASC";
		$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$resultlieux=mysqli_query($con,$querylieux);
		$result=mysqli_query($con,"SELECT * FROM ((SELECT DISTINCT 1 as tri,l.Nom,l.Zone,u.Mission_Type_D,p.Pays_ID,u.Nom as Unite,l.Recce,l.ID FROM Unit as u,Lieu as l,Pays as p
		WHERE (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND u.Pays=p.Pays_ID AND u.Mission_Lieu_D >0 AND u.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' AND u.Mission_Lieu_D=l.ID)
		UNION ALL (SELECT DISTINCT 2 as tri,l.Nom,l.Zone,r.Mission_Type_D,r.Pays,r.ID as Unite,l.Recce,l.ID FROM Lieu as l,Regiment_IA as r,Pays as p 
		WHERE r.Pays=p.Pays_ID AND r.Front IN(".$Front_list.") AND r.Mission_Lieu_D=l.ID AND r.Mission_Lieu_D >0 AND r.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max'))
		) a ORDER BY tri,Nom ASC");
		/*UNION ALL (SELECT DISTINCT 3 as tri,l.Nom,l.Zone,o.Mission_Type_D,p.Pays_ID,o.Nom as Unite,l.Recce,l.ID FROM Officier as o,Lieu as l,Pays as p
		WHERE o.Pays=p.Pays_ID AND o.Front IN(".$Front_list.") AND o.Mission_Lieu_D >0 AND o.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' AND o.Mission_Lieu_D=l.ID)*/
		$result_unit=mysqli_query($con,$query_unit);
		if($resultlieux)
		{
			while($datal=mysqli_fetch_array($resultlieux,MYSQLI_ASSOC)) 
			{
				$Lieux.="<option value=".$datal['ID'].">".$datal['Nom']."</option>";
			}
			mysqli_free_result($resultlieux);
		}
		if($result)
		{
			while($Data=mysqli_fetch_array($result,MYSQLI_NUM)) 
			{
				if($Data[2] ==6)
				{
					$Nav_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Data[6]' AND Pays<>'$country' AND Vehicule_Nbr >0 AND Visible=1"),0);
					if($Nav_eni >0)
						$Recce='<b>Oui</b>';
					else
						$Recce='Non';
				}
				else
				{
					if($Data[6] ==2)
						$Recce='<b>Eclairé</b>';
					elseif($Data[6] ==1)
						$Recce='<b>Oui</b>';
					else
						$Recce='Non';
				}
				if(is_numeric($Data[5]))$Data[5].='e Cie';
				$txt.="<tr><td>".$Data[1]."</td><td><img src='images/zone".$Data[2].".jpg'></td><td>".GetMissionType($Data[3])."</td><td><img src='".$Data[4]."20.gif' title='".$Data[5]."'> ".$Data[5]."</td><td>".$Recce."</td></tr>";
				$height_dem+=60;
			}
		}
		if($result_unit)
		{
			while($Data_unit=mysqli_fetch_array($result_unit,MYSQLI_ASSOC)) 
			{
				$piste_txt='Piste';
				if($Data_unit['Mission_Lieu'] >0)
				{
					$Mission_Type=GetMissionType($Data_unit['Mission_Type']);
					if($Data_unit['Base'] ==$Data_unit['Mission_Lieu'])
						$Cible_txt=$Data_unit['Base_nom'];
					else
						$Cible_txt=GetData("Lieu","ID",$Data_unit['Mission_Lieu'],"Nom");
					$txt_missions_ia.="<tr><td>".$Cible_txt."</td><td>".Afficher_Icone($Data_unit['ID'],$country,$Data_unit['Nom'])." ".$Data_unit['Nom']."</td><td>".$Mission_Type."</td></tr>";
					$height_mia+=60;
				}
				if($Data_unit['Porte_avions'] >0) //P-A
				{
					$HP_max_PA=mysqli_result(mysqli_query($con,"SELECT HP FROM Cible WHERE ID='".$Data_unit['Porte_avions']."'"),0);
					$HP_PA=mysqli_result(mysqli_query($con,"SELECT HP FROM Regiment_IA WHERE Vehicule_ID='".$Data_unit['Porte_avions']."'"),0);
					//$HP_max_PA=GetData("Cible","ID",$Data_unit['Porte_avions'],"HP");
					//$HP_PA=GetData("Regiment_IA","Vehicule_ID",$Data_unit['Porte_avions'],"HP");
					$Data_unit['QualitePiste']=round(($HP_PA/$HP_max_PA)*100);
					$Faction_Flag=$Faction;
					$Faction_Air=$Faction;
				}
				else
				{
					$Faction_Flag=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$Data_unit['Flag']."'"),0);
					$Faction_Air=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$Data_unit['Flag_Air']."'"),0);
					//$Faction_Flag=GetData("Pays","ID",$Data_unit['Flag'],"Faction");
					//$Faction_Air=GetData("Pays","ID",$Data_unit['Flag_Air'],"Faction");
				}
				if($Data_unit['Type'] ==9)
				{
                    if($Data_unit['Flag_Port'] >0)
                    {
                        $Faction_Air=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$Data_unit['Flag_Port']."'"),0);
                        if($Data_unit['Port'] >50 and $Faction_Air==$Faction)
                            $Data_unit['QualitePiste']=100;
                        else
                            $piste_txt='Infrastructures portuaires';
                    }
                    else
                        $Faction_Air=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$Data_unit['Flag_Air']."'"),0);
				}
				if($Data_unit['Type'] ==2 or $Data_unit['Type'] ==7 or $Data_unit['Type'] ==10 or $Data_unit['Type'] ==11)
					$queryskills="SELECT AVG(Pilotage+Tir)/2 FROM Pilote_IA WHERE Unit='".$Data_unit['ID']."' AND Actif=1";
				elseif($Data_unit['Type'] ==3 or $Data_unit['Type'] ==6 or $Data_unit['Type'] ==9)
					$queryskills="SELECT AVG(Pilotage+Vue)/2 FROM Pilote_IA WHERE Unit='".$Data_unit['ID']."' AND Actif=1";
				else
					$queryskills="SELECT AVG(Pilotage+Tactique+Tir+Vue)/4 FROM Pilote_IA WHERE Unit='".$Data_unit['ID']."' AND Actif=1";
				$Skill_Moy=mysqli_result(mysqli_query($con,$queryskills),0);
				$Pilotes_max=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='".$Data_unit['ID']."' AND Actif=1"),0);
				$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='".$Data_unit['ID']."' AND Courage >0 AND Moral >0 AND Actif=1"),0);
                if($Data_unit['Mission_IA'])
                    $Led="<div class='i-flex led_red'></div>";
                elseif($Data_unit['Mission_Type'])
                    $Led="<a href='#' class='popup'><div class='i-flex led_orange'></div><span>Unité en patrouille ou en escorte</span></a>";
                else
                    $Led="<div class='i-flex led_green'></div>";
                if(!$Data_unit['Porte_avions'] and ($Faction !=$Faction_Flag or $Faction !=$Faction_Air))
                    $Led.="<img src='images/mortar.png' title='Sous le feu'>";
                if($Data_unit['Porte_avions'] >0 and $Data_unit['Port'] >0){
                    $Action="<span class='label label-danger'>Navire au port</span>";
                    $Led="<div class='i-flex led_red'></div>";
                }
				elseif($Data_unit['QualitePiste'] >50)
				{
					if(!$Data_unit['Mission_IA'] and $Faction ==$Faction_Flag and $Faction ==$Faction_Air)
						$Action="<form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$Data_unit['ID']."'>
						<input type='submit' value='Ordre' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
					elseif($Faction !=$Faction_Flag or $Faction !=$Faction_Air)
						$Action="<span class='label label-danger'>Sous le feu</span>";
					else
						$Action="<span class='label label-danger'>En vol</span>";
				}
				else
					$Action="<span class='label label-danger'>".$piste_txt." à réparer</span>";
				if($Premium and $Type ==99 and $Co_Lieu_Mission)
				{
					$Dist_Mission_Co=GetDistance($Co_Lieu_Mission,0,$Co_Lieu_Mission_Long,$Co_Lieu_Mission_Lat,$Data_unit['Longitude'],$Data_unit['Latitude']);
					$Dist_Mission_Co_txt="<br>".$Dist_Mission_Co[0]."km";
				}
				$txt_action_ia.="<tr><td>".Afficher_Icone($Data_unit['ID'],$country)."<br>".$Data_unit['Nom']."</td><td>".$Data_unit['Base_nom'].$Dist_Mission_Co_txt."</td><td>".$Led." ".$Action."</td><th>".round($Skill_Moy)."</th><td>".$Pilotes."/".$Pilotes_max."</td>
				<td>".$Data_unit['Avion1_Nbr']." ".GetAvionIcon($Data_unit['Avion1'],$country,0,0,$Front,false,$Legend)."</td><td>".$Data_unit['Avion2_Nbr']." ".GetAvionIcon($Data_unit['Avion2'],$country,0,0,$Front,false,$Legend)."</td><td>".$Data_unit['Avion3_Nbr']." ".GetAvionIcon($Data_unit['Avion3'],$country,0,0,$Front,false,$Legend)."</td></tr>";	
			}
			mysqli_free_result($result_unit);
			unset($Data_unit);
		}
		mysqli_close($con);
		if(!$txt)$txt="<tr><td colspan='5'>Aucune demande actuellement</td></tr>";
		if(($Premium or $Admin) and $Type !=99 and !$Armee)
		{
			echo "<h2>Cible de mission</h2><form action='index.php?view=em_missions_6' method='post'>
				<table class='table'><thead><tr><th>Cible actuelle</th><th>Changer</th></tr></thead>
				<tr><td>".$Co_Lieu_Mission_Nom."</td><td><select name='lieu_co' class='form-control' style='width: 200px'>".$Lieux."</select></td>
				<td><input type='submit' value='Changer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Les unités aériennes pouvant opérer sur cet objectif sont renseignées dans la section Mission (bouton orange) des unités IA</span></a></td></tr></table></form>";
		}
		if($height_dem >400)$height_dem=400;
		echo "<h2>Demandes de mission</h2><div style='overflow:auto; height: ".$height_dem."px;'><table class='table table-striped'>
			<thead><tr><th>Lieu</th><th>Zone</th><th>Mission demandée</th><th>Unité demandeuse</th><th>Statut Reco</th></tr></thead>".$txt."</table></div>";
		if($txt_missions_ia)
		{
			if($height_mia >400)$height_mia=400;
			echo "<h2>Missions en cours</h2><div style='overflow:auto; height: ".$height_mia."px;'><table class='table table-striped'><thead><tr><th>Lieu</th><th>Unité</th><th>Mission</th></tr></thead>".$txt_missions_ia."</table></div>";
		}
		if($txt_action_ia and ($Admin or $OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Mer or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk or $Armee))
			echo "<h2>Unités</h2>".$menu_units_ia.$Alert_Msg."<table class='table table-dt table-striped'><thead><tr><th>Unité</th><th>Base</th><th>Action</th><th>Exp</th><th>Pilotes</th><th>".$Sqn." 1</th><th>".$Sqn." 2</th><th>".$Sqn." 3</th></tr></thead>".$txt_action_ia."</table>";
		elseif($Armee or $OfficierEMID ==$Officier_Mer)
			echo "<h2>Unités</h2>".$menu_units_ia."<div class='alert alert-info'>Le commandant en chef peut vous assigner des unités aériennes, prenez contact avec lui.</div>";
		else
			echo '<h2>Unités</h2>'.$menu_units_ia;
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';