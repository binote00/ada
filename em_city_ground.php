<?php
require_once './jfv_inc_sessions.php';
$PlayerID = $_SESSION['PlayerID'];
$OfficierID = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if ($PlayerID > 0 xor $OfficierID > 0 xor $OfficierEMID > 0) {
    include_once './jfv_include.inc.php';
    include_once './jfv_nav.inc.php';
    include_once './jfv_txt.inc.php';
    include_once './jfv_inc_em.php';
    $country = $_SESSION['country'];
    $con = dbconnecti();
    if ($OfficierEMID) {
        $result = mysqli_query($con, "SELECT Front,Avancement,Armee FROM Officier WHERE ID='$OfficierEMID'");
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Front = $data['Front'];
                $Avancement = $data['Avancement'];
                $Armee = $data['Armee'];
            }
            mysqli_free_result($result);
        }
    } elseif ($PlayerID) {
        $result = mysqli_query($con, "SELECT Front,Avancement,Renseignement FROM Pilote WHERE ID='$PlayerID'");
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Front = $data['Front'];
                $Avancement = $data['Avancement'];
                $Renseignement = $data['Renseignement'];
            }
            mysqli_free_result($result);
        }
    } elseif ($OfficierID) {
        $result = mysqli_query($con, "SELECT Front,Avancement,Trait FROM Officier WHERE ID='$OfficierID'");
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Front = $data['Front'];
                $Avancement = $data['Avancement'];
                $Trait_o = $data['Trait'];
            }
            mysqli_free_result($result);
        }
    }
    mysqli_close($con);
    if ($Admin == 1 or $Avancement > 9999 or $OfficierEMID > 0 or $Renseignement > 100 or $Trait_o == 23 or $GHQ)
        $Officier_acces = true;
    elseif ($Avancement > 499)
        $Pilote_acces = true;
    if ($Officier_acces or $Pilote_acces) {
        $Skill_scale = 10;
        if (!$Cible)
            $Cible = Insec($_POST['id']);
        if (!$Cible) {
            $Cible = Insec($_GET['id']);
            $Mode = Insec($_GET['mode']);
            if ($Mode != 3) {
                $Map_output = true;
                $Skill_scale = 5;
            }
        }
        if ($Cible) {
            include_once './jfv_map.inc.php';
            $con = dbconnecti();
            $Cible = mysqli_real_escape_string($con, $Cible);
            $Date_Campagne = mysqli_result(mysqli_query($con, "SELECT `Date` FROM Conf_Update WHERE ID=2"), 0);
            $Faction = mysqli_result(mysqli_query($con, "SELECT Faction FROM Pays WHERE ID='$country'"), 0);
            if ($Faction == 2)
                $Detect_field = 'Detect_Allie';
            elseif ($Faction == 1)
                $Detect_field = 'Detect_Axe';
            $result = mysqli_query($con, "SELECT Nom,Pays,`Zone`,Map,Meteo,Meteo_Hour,Latitude,Longitude,Occupant,Plage,ValeurStrat,DefenseAA_temp,BaseAerienne,QualitePiste,LongPiste,Impass,
			Industrie,Pont,Pont_Ori,Radar_Ori,Radar,Port_Ori,Port,Port_level,NoeudR,NoeudF,NoeudF_Ori,Flag,Garnison,Fortification,Oil,Flag_Air,Flag_Route,Flag_Gare,Flag_Port,Flag_Pont,Flag_Usine,Flag_Radar,Flag_Plage,
			Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,Stock_Munitions_40,Stock_Munitions_50,Stock_Munitions_60,
			Stock_Munitions_75,Stock_Munitions_90,Stock_Munitions_105,Stock_Munitions_125,Stock_Munitions_150,Stock_Munitions_200,Stock_Munitions_300,Stock_Munitions_360,Citernes,Mines_m,
			Stock_Bombes_30,Stock_Bombes_50,Stock_Bombes_80,Stock_Bombes_125,Stock_Bombes_250,Stock_Bombes_300,Stock_Bombes_400,Stock_Bombes_500,Stock_Bombes_800,Stock_Bombes_1000,Stock_Bombes_2000,Recce,Recce_PlayerID,Recce_PlayerID_TAL,Recce_PlayerID_TAX
			FROM Lieu WHERE ID='$Cible'");
            if ($Faction) {
                $resultm = mysqli_query($con, "SELECT Qty,`Zone` FROM Mines WHERE Lieu_ID='$Cible' AND " . $Detect_field . "=1");
                if ($resultm) {
                    while ($datam = mysqli_fetch_array($resultm)) {
                        $Mines = 'Mines' . $datam['Zone'];
                        $$Mines = $datam['Qty'];
                    }
                    mysqli_free_result($resultm);
                }
            }
            mysqli_close($con);
            if ($result) {
                while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Cible_nom = $data['Nom'];
                    $Pays_Ori = $data['Pays'];
                    $Cible_DefenseAA = $data['DefenseAA_temp'];
                    $Lat = $data['Latitude'];
                    $Long = $data['Longitude'];
                    $Impass_ori = $data['Impass'];
                    $ValeurStrat = $data['ValeurStrat'];
                    $Cible_base = $data['BaseAerienne'];
                    $QualitePiste = $data['QualitePiste'];
                    $Piste = $data['LongPiste'];
                    $Usine = $data['Industrie'];
                    $Pont = $data['Pont'];
                    $Pont_Ori = $data['Pont_Ori'];
                    $Port = $data['Port'];
                    $Port_Ori = $data['Port_Ori'];
                    $Port_level = $data['Port_level'];
                    $Radar = $data['Radar'];
                    $Radar_Ori = $data['Radar_Ori'];
                    $NoeudR = $data['NoeudR'];
                    $NoeudF = $data['NoeudF'];
                    $NoeudF_Ori = $data['NoeudF_Ori'];
                    $Zone = $data['Zone'];
                    $Map = $data['Map'];
                    $Occupant = $data['Occupant'];
                    $Flag = $data['Flag'];
                    $Flag_Air = $data['Flag_Air'];
                    $Flag_Route = $data['Flag_Route'];
                    $Flag_Gare = $data['Flag_Gare'];
                    $Flag_Port = $data['Flag_Port'];
                    $Flag_Pont = $data['Flag_Pont'];
                    $Flag_Usine = $data['Flag_Usine'];
                    $Flag_Radar = $data['Flag_Radar'];
                    $Flag_Plage = $data['Flag_Plage'];
                    $Garnison = $data['Garnison'];
                    $Fortification = $data['Fortification'];
                    $Oil = $data['Oil'];
                    $Citernes = $data['Citernes'];
                    $Mines_m = $data['Mines_m'];
                    $Plage = $data['Plage'];
                    $Recce = $data['Recce'];
                    $Recce_PlayerID = $data['Recce_PlayerID'];
                    $Recce_PlayerID_TAL = $data['Recce_PlayerID_TAL'];
                    $Recce_PlayerID_TAX = $data['Recce_PlayerID_TAX'];
                    $Front_Lieu = GetFrontByCoord(0, $Lat, $Long);
                    if ($Flag == 24) $Flag = 6; //Albanie
                    //Meteo
                    $today = getdate();
                    if (!$data['Meteo_Hour'] or ($today['hours'] > $data['Meteo_Hour'] + 2)) {
                        $Saison = GetSaison($Date_Campagne);
                        $Previsions_temp = GetMeteo($Saison, $Lat, $Long);
                        $Meteo = $Previsions_temp[1];
                        $con = dbconnecti();
                        $setmeteo = mysqli_query($con, "UPDATE Lieu SET Meteo='" . $Meteo . "',Meteo_Hour='" . $today['hours'] . "' WHERE ID='" . $Cible . "'");
                        mysqli_close($con);
                        unset($Previsions_temp);
                    } else
                        $Meteo = $data['Meteo'];
                    if ($Flag) {
                        $Rev_txt = "Les troupes de la nation contrôlant le lieu (<b>" . GetPays($Flag) . "</b>), bénéficient d'un bonus de portée de 500m et d'un bonus de déplacement de 10";
                        $Rev = "<a href='#' class='popup'><img src='images/flag" . $Flag . "p.jpg'><span>" . $Rev_txt . "</span></a>";
                    } else
                        $Rev = 'N/A';
                    $Zone_txt = GetZone($Zone);
                    $Region = 'Zone ' . $Zone_txt;
                    if ($NoeudR) $Noeud_txt = "<img src='images/route.gif' title='Noeud routier'>";
                    $Valstrat_icon = "<img src='images/strat" . $ValeurStrat . ".png'>";
                    if (($ValeurStrat > 3 and $Flag == $country and ($Officier_acces or ($Trait_o == 14 or $Trait_o == 23))) or ($ValeurStrat > 1 and $Admin == 1)) {
                        $con = dbconnecti();
                        $resultdiv = mysqli_query($con, "SELECT ID,Nom FROM Division WHERE Base='$Cible' AND Active=1");
                        mysqli_close($con);
                        if ($resultdiv) {
                            while ($datad = mysqli_fetch_array($resultdiv, MYSQLI_ASSOC)) {
                                $div_txt .= "<img src='images/div/div" . $datad['ID'] . ".png' title='" . $datad['Nom'] . "'> ";
                            }
                            mysqli_free_result($resultdiv);
                        }
                        if ($div_txt) $div_txt = "<h3>Base arrière des divisions suivantes</h3>" . $div_txt;
                        if ($Usine)
                            $depot = $div_txt . "<h3>Dépôt de " . $data['Nom'] . "</h3><table class='table table-striped'>";
                        else
                            $depot = "<h2>Logistique</h2>" . $div_txt . "<h3>Dépôt de " . $data['Nom'] . "</h3><table class='table table-striped table-800'>";
                        $depot .= "<thead><tr><th>Ressource</th><th>Quantité</th></tr></thead>
								<tr><th>Essence 87 Octane</th><td >" . $data['Stock_Essence_87'] . "</td></tr>
								<tr><th>Essence 100 Octane</th><td >" . $data['Stock_Essence_100'] . "</td></tr>
								<tr><th>Diesel</th><td >" . $data['Stock_Essence_1'] . "</td></tr>
								<tr><th>Munitions 8mm</th><td >" . $data['Stock_Munitions_8'] . "</td></tr>
								<tr><th>Munitions 13mm</th><td >" . $data['Stock_Munitions_13'] . "</td></tr>
								<tr><th>Munitions 20mm</th><td >" . $data['Stock_Munitions_20'] . "</td></tr>
								<tr><th>Munitions 30mm</th><td >" . $data['Stock_Munitions_30'] . "</td></tr>
								<tr><th>Munitions 40mm</th><td >" . $data['Stock_Munitions_40'] . "</td></tr>
								<tr><th>Munitions 50mm</th><td >" . $data['Stock_Munitions_50'] . "</td></tr>
								<tr><th>Munitions 60mm</th><td >" . $data['Stock_Munitions_60'] . "</td></tr>
								<tr><th>Munitions 75mm</th><td >" . $data['Stock_Munitions_75'] . "</td></tr>
								<tr><th>Munitions 90mm</th><td >" . $data['Stock_Munitions_90'] . "</td></tr>
								<tr><th>Munitions 105mm</th><td >" . $data['Stock_Munitions_105'] . "</td></tr>
								<tr><th>Munitions 125mm</th><td >" . $data['Stock_Munitions_125'] . "</td></tr>
								<tr><th>Munitions 150mm</th><td >" . $data['Stock_Munitions_150'] . "</td></tr>
								<tr><th>Bombes 50kg</th><td >" . $data['Stock_Bombes_50'] . "</td></tr>
								<tr><th>Bombes 125kg</th><td >" . $data['Stock_Bombes_125'] . "</td></tr>
								<tr><th>Bombes 250kg</th><td >" . $data['Stock_Bombes_250'] . "</td></tr>
								<tr><th>Bombes 500kg</th><td >" . $data['Stock_Bombes_500'] . "</td></tr>
								<tr><th>Bombes 1000kg</th><td >" . $data['Stock_Bombes_1000'] . "</td></tr>
								<tr><th>Bombes 2000kg</th><td >" . $data['Stock_Bombes_2000'] . "</td></tr>
								<tr><th>Charges de profondeur</th><td >" . $data['Stock_Bombes_300'] . "</td></tr>
								<tr><th>Mines</th><td >" . $data['Stock_Bombes_400'] . "</td></tr>
								<tr><th>Torpilles</th><td >" . $data['Stock_Bombes_800'] . "</td></tr>
								<tr><th>Fusées éclairantes</th><td >" . $data['Stock_Bombes_30'] . "</td></tr>
								<tr><th>Rockets</th><td >" . $data['Stock_Bombes_80'] . "</td></tr>
								</table>";
                        /*<tr><th>Munitions 200mm</th><td >".$data['Stock_Munitions_200']."</td></tr>
								<tr><th>Munitions 300mm</th><td >".$data['Stock_Munitions_300']."</td></tr>
								<tr><th>Munitions 360mm</th><td >".$data['Stock_Munitions_360']."</td></tr>*/
                    } elseif (!$ValeurStrat)
                        $Valstrat_icon = 'Aucune';
                }
                mysqli_free_result($result);
                unset($data);
            }
            $Faction_Flag = GetData("Pays", "ID", $Flag, "Faction");
            if (is_file('images/lieu/lieu' . $Cible . '.jpg'))
                $img_gen = 'images/lieu/lieu' . $Cible . '.jpg';
            else {
                if ($Zone == 8) {
                    if ($Map == 0 or $Map == 1)
                        $img_gen = 'images/dune_sea.jpg';
                    elseif ($Map == 2 or $Map == 3)
                        $img_gen = 'images/desert_airfield.jpg';
                } elseif ($Zone == 9) {
                    if ($Map == 0 or $Map == 1)
                        $img_gen = 'images/jungle.jpg';
                    elseif ($Map == 2 or $Map == 3)
                        $img_gen = 'images/pacific_airfield.jpg';
                    elseif ($Map == 8)
                        $img_gen = 'images/jungle_port.jpg';
                } elseif ($Pays_Ori == 8 and $Map == 1 or $Map == 3)
                    $img_gen = 'images/lieu/russian_town.jpg';
                if (!$img_gen) $img_gen = 'images/lieu/objectif' . $Map . '.jpg';
            }
            if ($Faction_Flag == $Faction or $Recce > 0 or $Admin == 1) {
                $Meteo_txt = "<img src='images/meteo" . $Meteo . ".gif'>";
                $dca = "<img src='images/vehicules/vehicule16.gif' title='DCA niveau " . $Cible_DefenseAA . "'> <b>" . $Cible_DefenseAA . "</b>";
                if ($Flag == $country or $Admin == 1) {
                    if ($Officier_acces or $Admin == 1)
                        $Garnison = "<img src='images/vehicules/vehicule107.gif'> " . $Garnison;
                    else
                        $Garnison = "<img src='images/vehicules/vehicule107.gif'> ?";
                } else
                    $Garnison = "<img src='images/vehicules/vehicule107.gif'> ?";
                if ($Fortification) {
                    if ($Officier_acces) {
                        if ($Mines0)
                            $icone_mines0 = "<br><img src='images/map/mines.png' title='Zone minée'>";
                        $Fortification = "<img src='images/icone_fort.gif' title='Fortifications niveau " . $Fortification . "'> <b>" . $Fortification . "</b>" . $icone_mines0;
                    } else
                        $Fortification = "<img src='images/icone_fort.gif'> ?";
                }
                if ($Cible_base) {
                    if ($Cible_base == 3 or $Cible_base == 4) {
                        if ($Zone == 8)
                            $QualitePiste_img = "piste38_" . GetQualitePiste_img($QualitePiste) . ".jpg";
                        if ($Zone == 0 or $Zone == 2 or $Zone == 3 or $Zone == 9)
                            $QualitePiste_img = "piste32_" . GetQualitePiste_img($QualitePiste) . ".jpg";
                        else
                            $QualitePiste_img = "piste31_" . GetQualitePiste_img($QualitePiste) . ".jpg";
                    } else
                        $QualitePiste_img = "piste" . $Cible_base . "_" . GetQualitePiste_img($QualitePiste) . ".jpg";
                    if ($Officier_acces) {
                        if ($Mines1)
                            $icone_mines1 = "<br><img src='images/map/mines.png' title='Zone minée'>";
                        $icones .= "<td><img src='images/" . $QualitePiste_img . "' title='Base aérienne " . $Piste . "m - " . $QualitePiste . "%'></td>";
                        $iconebase = "<td><img src='images/" . $Flag_Air . "20.gif' title='" . GetPays($Flag_Air) . "'> " . $Piste . "m</td>";
                    } else {
                        $icones .= "<td><img src='images/" . $QualitePiste_img . "'></td>";
                        $iconebase = "<td><img src='images/" . $Flag_Air . "20.gif' title='" . GetPays($Flag_Air) . "'>" . $icone_mines1 . "</td>";
                    }
                }
                if ($Pont_Ori) {
                    if ($Officier_acces or $Trait_o == 24) {
                        if (!$Pont)
                            $icones .= "<td><img src='images/map/pont_detruit.png' title='Pont détruit " . $Pont . "%'></td>";
                        else
                            $icones .= "<td><img src='images/vehicules/vehicule10.gif' title='Pont " . $Pont . "%'></td>";
                        if ($Mines5)
                            $icone_mines5 = "<br><img src='images/map/mines.png' title='Zone minée'>";
                    } else
                        $icones .= "<td><img src='images/vehicules/vehicule10.gif' title='Pont'></td>";
                    $iconebase .= "<td><img src='images/" . $Flag_Pont . "20.gif' title='" . GetPays($Flag_Pont) . "'>" . $icone_mines5 . "</td>";
                }
                if ($Port_Ori) {
                    if ($Port_level == 3)
                        $Port_txt = "Base navale. Port doté de toutes les infrastructures. Tous les navires peuvent accéder aux docks et au garage.";
                    elseif ($Port_level == 2)
                        $Port_txt = "Port principal. Dock accessible à tous les navires, sauf les porte-avions et les cuirassés.";
                    else
                        $Port_txt = "Port secondaire. Dock accessible aux petits navires, aux cargos et aux corvettes. Pas d accès au garage.";
                    if ($Port) {
                        if ($Officier_acces or $Trait_o == 24) {
                            if ($Mines4)
                                $icone_mines4 = "<br><img src='images/map/mines.png' title='Zone minée'>";
                            $icones .= "<td><img src='images/vehicules/vehicule12.gif' title='" . $Port_txt . " (" . $Port . "%)'></td>";
                        } else
                            $icones .= "<td><img src='images/vehicules/vehicule12.gif' title='" . $Port_txt . "'></td>";
                    } else
                        $icones .= "<td><img src='images/icon_port_detruit.png' title='" . $Port_txt . " (détruit)'></td>";
                    $iconebase .= "<td><img src='images/" . $Flag_Port . "20.gif' title='" . GetPays($Flag_Port) . "'>" . $icone_mines4 . "</td>";
                }
                if ($Radar_Ori) {
                    if ($Officier_acces or $Trait_o == 24) {
                        $icones .= "<td><img src='images/vehicules/vehicule15.gif' title='Radar " . $Radar . "%'></td>";
                        if ($Mines7)
                            $icone_mines7 = "<br><img src='images/map/mines.png' title='Zone minée'>";
                    } else
                        $icones .= "<td><img src='images/vehicules/vehicule15.gif' title='Radar'></td>";
                    $iconebase .= "<td><img src='images/" . $Flag_Radar . "20.gif' title='" . GetPays($Flag_Radar) . "'>" . $icone_mines7 . "</td>";
                }
                if ($NoeudF_Ori) {
                    if (!$NoeudF)
                        $icones .= "<td><img src='images/gare0.png' title='Gare détruite'></td>";
                    else {
                        if ($Officier_acces or $Trait_o == 24) {
                            $icones .= "<td><img src='images/vehicules/vehicule9.gif' title='Gare " . $NoeudF . "%'></td>";
                            if ($Mines3)
                                $icone_mines3 = "<br><img src='images/map/mines.png' title='Zone minée'>";
                        } else
                            $icones .= "<td><img src='images/vehicules/vehicule9.gif' title='Gare'></td>";
                    }
                    $iconebase .= "<td><img src='images/" . $Flag_Gare . "20.gif' title='" . GetPays($Flag_Gare) . "'>" . $icone_mines3 . "</td>";
                }
                if ($NoeudR) {
                    if ($Officier_acces or $Trait_o == 24) {
                        if ($Mines2) $icone_mines2 = "<br><img src='images/map/mines.png' title='Zone minée'>";
                    }
                    $icones .= "<td><img src='images/map/lieu_route" . $Flag_Route . ".png' title='Noeud Routier'></td>";
                    $iconebase .= "<td><img src='images/" . $Flag_Route . "20.gif' title='" . GetPays($Flag_Route) . "'>" . $icone_mines2 . "</td>";
                }
                if ($Usine) {
                    if ($Officier_acces) {
                        if ($Mines6) $icone_mines6 = "<br><img src='images/map/mines.png' title='Zone minée'>";
                        $icones .= "<td><img src='images/vehicules/vehicule5.gif' title='Usine " . $Usine . "%'></td>";
                        $iconebase .= "<td><img src='images/" . $Flag_Usine . "20.gif' title='" . GetPays($Flag_Usine) . "'>" . $icone_mines6 . "</td>";
                        if ($Flag == $country or $Admin == 1) {
                            $usine_txt = "<h2>Logistique</h2><h3>Production dans les usines de " . $Cible_nom . "</h3>";
                            $con = dbconnecti();
                            $resultu = mysqli_query($con, "SELECT DISTINCT ID,Nom FROM Avion WHERE Usine1='$Cible' OR Usine2='$Cible' OR Usine3='$Cible'");
                            $results = mysqli_query($con, "SELECT DISTINCT ID,Nom FROM Cible WHERE Usine1='$Cible' OR Usine2='$Cible' OR Usine3='$Cible'");
                            mysqli_close($con);
                            if ($results) {
                                while ($datau = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                                    $usines_txt .= "<img src='images/vehicules/vehicule" . $datau['ID'] . ".gif' title='" . $datau['Nom'] . "'>";
                                }
                                mysqli_free_result($results);
                            }
                            if ($resultu) {
                                while ($datau = mysqli_fetch_array($resultu, MYSQLI_ASSOC)) {
                                    $usines_txt .= "<img src='images/avions/avion" . $datau['ID'] . ".gif' title='" . $datau['Nom'] . "'>";
                                }
                                mysqli_free_result($resultu);
                            }
                            if (!$usines_txt)
                                $usine_txt .= "Cette usine participe à l'efficacité de la production globale de la nation";
                            else
                                $usine_txt .= $usines_txt;
                        }
                    } else
                        $icones .= "<td><img src='images/vehicules/vehicule5.gif' title='Usine'></td>";
                }
                if ($Oil)
                    $icones .= "<td><img src='images/map/icone_oil.gif' title='Raffinerie'></td>";
                if ($Citernes)
                    $icones .= "<td><img src='images/citernes.png' title='" . $Citernes . " Citernes en feu dans les ports alentours, diminuant le ravitaillement en carburant'></td>";
                if ($Plage) {
                    $icones .= "<td><img src='images/plage.jpg' alt='Plage de débarquement'>";
                    if ($Flag_Plage)
                        $icones .= '<br><img src="images/' . $Flag_Plage . '20.gif">';
                    $icones .= '</td>';
                }
            } else {
                $dca = 'Inconnu';
                $Garnison = 'Inconnu';
                $Fortification = 'Inconnu';
                $icones = 'Inconnu';
                $Meteo_txt = 'Inconnue';
            }
            $air_units = '';
            $units = '';
            $units_allies = '';
            $units_axe = '';
            $Print_detail = false;
            if ($Front == $Front_Lieu or $Admin or $GHQ) {
                include_once './jfv_ground.inc.php';
                $Print_detail = true;
                if ($Admin == 1) {
                    $query_units = "SELECT ID,Nom,Pays,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Commandant,Etat,Recce,Garnison,Mission_IA,Armee FROM Unit WHERE Base='$Cible' AND Etat=1";
                    $query_blitz = "SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Skill,r.Matos,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Division,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Move,c.Nom,c.Taille,c.Type,c.mobile,c.Fuel,r.Ravit,r.Bomb_IA,r.Bomb_PJ,r.Arti_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.HP as HP_max 
					FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' ORDER BY Placement ASC,Pays ASC,ID ASC";
                    //(SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Skill,r.Matos,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Division,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Move,c.Nom,c.Taille,c.Type,r.Ravit,r.Bomb_IA,r.Bomb_PJ,r.Arti_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,c.HP as HP_max FROM Regiment as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr>0) UNION (
                } elseif ($Officier_acces) {
                    $query_units = "SELECT u.ID,u.Nom,u.Pays,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,u.Commandant,u.Etat,u.Recce,u.Garnison,u.Mission_IA,u.Armee FROM Unit as u,Pays as p WHERE u.Pays=p.ID AND u.Base='$Cible' AND p.Faction='$Faction' AND u.Etat=1";
                    $query_blitz = "SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Skill,r.Matos,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Division,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Move,c.Nom,c.Taille,c.Type,c.mobile,c.Fuel,r.Ravit,r.Bomb_IA,r.Bomb_PJ,r.Arti_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.HP as HP_max 
					FROM Regiment_IA as r,Cible as c,Pays as p WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr>0 AND (p.Faction='$Faction' OR r.Visible=1) ORDER BY Placement ASC";
                    /*(SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Skill,r.Matos,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Division,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Move,c.Nom,c.Taille,c.Type,r.Ravit,r.Bomb_IA,r.Bomb_PJ,r.Arti_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,c.HP as HP_max FROM Regiment as r,Cible as c,Pays as p
					WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr>0 AND (p.Faction='$Faction' OR r.Visible=1)) UNION (*/
                } else {
                    $query_units = "SELECT ID,Nom,Pays,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Commandant,Etat,Recce,Garnison,Mission_IA FROM Unit WHERE Base='$Cible' AND Pays='$country' AND Etat=1";
                    $query_blitz = "SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Division,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Move,c.Nom,c.Taille,c.Type,c.mobile,c.Fuel,r.Ravit,r.Bomb_IA,r.Bomb_PJ,r.Arti_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.HP as HP_max 
					FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr>0 AND (r.Pays='$country' OR r.Visible=1) ORDER BY Placement ASC";
                    /*(SELECT r.ID,r.Pays,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Placement,r.Position,r.Visible,r.HP,r.Camouflage,r.Division,r.Stock_Munitions_8,r.Stock_Essence_87,r.Stock_Essence_1,r.Moral,r.Move,c.Nom,c.Taille,c.Type FROM Regiment as r,Cible as c
					WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr>0 AND (r.Pays='$country' OR r.Visible=1)) UNION (*/
                }
                $con = dbconnecti();
                $result_blitz = mysqli_query($con, $query_blitz);
                $result = mysqli_query($con, $query_units);
                $Faction_Air = mysqli_result(mysqli_query($con, "SELECT Faction FROM Pays WHERE ID='$Flag_Air'"), 0);
                if ($Zone != 6) {
                    if ($Faction_Flag == $Faction) {
                        $txt_help_voyage = '<ul>
                                <li><span class="text-success">En vert les lieux accessibles pour toutes vos unités depuis ' . $Cible_nom . '</span></li>
                                <li>En noir les lieux accessibles pour vos unités dotées de l\'autonomie suffisante depuis ' . $Cible_nom . '</li>
                                <li><span class="text-success"><b>En gras et en vert les lieux adverses permettant à toutes les unités ennemies de se déplacer vers ' . $Cible_nom . '</b></span></li>
                                <li><span class="text-primary"><b>En gras et en bleu les lieux adverses permettant aux unités ennemies dotées de l\'autonomie suffisante de se déplacer vers ' . $Cible_nom . '</b></span></li>
                                <li><span class="text-warning"><b>En gras et en orange les lieux adverses permettant aux unités motorisées ennemies de se déplacer vers ' . $Cible_nom . '</b></span></li>
                                <li><span class="text-danger"><b>En gras et en rouge les lieux adverses trop éloignés pour qu\'une unité ennemie se déplace vers ' . $Cible_nom . '</b></span></li>
                                </ul>';
                    } else {
                        $txt_help_voyage = '<ul>
                                <li><span class="text-success">En vert les lieux accessibles pour toutes les unités depuis ' . $Cible_nom . '</span></li>
                                <li><span class="text-primary">En bleu les lieux accessibles pour les unités dotées de l\'autonomie suffisante depuis ' . $Cible_nom . '</span></li>
                                <li><span class="text-warning">En orange les lieux accessibles pour les unités motorisées dotées de l\'autonomie suffisante depuis ' . $Cible_nom . '</span></li>
                                <li><span class="text-danger">En rouge les lieux inaccessibles depuis ' . $Cible_nom . '</span></li>
                                </ul>';
                    }
                    $Auto_max = GetAuto($Front_Lieu, $Lat, $Long);
                    $Dist_max = $Auto_max[0];
                    $Dist_min = $Auto_max[1];
                    $Dist_max_train = $Auto_max[2];
                    $Dist_max_train = 500;
                    $Dist_max_skill = $Auto_max[0] * 1.2;
                    /*if($Front_Lieu ==1 or $Front_Lieu ==4 or $Front_Lieu ==5 or $Long <-50 or $Long >235)
					{
                        $Dist_max_train=200;
						$Dist_max=100;
						$Dist_max_skill=120;
					}
					elseif($Front_Lieu ==3)
					{
						$Dist_max=200;
						$Dist_max_skill=240;
					}
					elseif($Long >34 and $Long <45) //Moyen-Orient
					{
						$Dist_max=75;
						$Dist_max_skill=90;
					}
					elseif($Lat <36.7 or ($Long <12 and $Lat <37.3 and $Pays_Ori !=6)) //AFN
					{
						$Dist_max_train=200;
						$Dist_max=100;
						$Dist_max_skill=120;
					}
					else
					{
						$Dist_max_train=100;
						$Dist_max=50;
						$Dist_max_skill=60;
					}*/
                    /*if($Admin)
						$Dist_max_final=$Dist_max_train;
					else*/
                    $Dist_max_final = $Dist_max_skill;
                    $voyage = mysqli_query($con, "SELECT Nom,Longitude,Latitude,Pays,NoeudR,NoeudF_Ori,Port_Ori,`Zone`,Flag,Impass 
					FROM Lieu WHERE (Longitude BETWEEN '$Long'-3 AND '$Long'+3) AND (Latitude BETWEEN '$Lat'-2 AND '$Lat'+2) AND ID!='$Cible' ORDER BY Nom");
                    if ($voyage) {
                        while ($datav = mysqli_fetch_array($voyage, MYSQLI_ASSOC)) {
                            $coord = 0;
                            $iconev = '';
                            $sensh = '';
                            $sensv = '';
                            $end_dist = '';
                            $choix = '';
                            if ($Zone != 6) {
                                $Dist_Voyage = GetDistance(0, 0, $Long, $Lat, $datav['Longitude'], $datav['Latitude']);
                                /*if($NoeudF_Ori and $datav['NoeudF_Ori'] and $datav['Flag'] ==$country)
									$Dist_max=200;
								else*/
                                if ($Dist_Voyage[0] <= $Dist_max_final) {
                                    $Faction_Dest = mysqli_result(mysqli_query($con, "SELECT Faction FROM Pays WHERE ID='" . $datav['Flag'] . "'"), 0);
                                    $Impass = $datav['Impass'];
                                    if ($NoeudF_Ori and $datav['NoeudF_Ori'])
                                        $iconev = "<img src='images/" . $datav['Flag'] . "20.gif' alt='Nation contrôlant le lieu'><a href='#' class='popup'><img src='images/rail.gif' title='Noeud Ferroviaire'><span><b>Noeud Ferroviaire</b> Les unités se déplaçant entre deux noeuds ferroviaires contrôlés par leur faction doublent leur distance de déplacement et ignorent les pénalités de déplacement dues au relief.</span></a>";
                                    elseif ($datav['NoeudR'] and $NoeudR)
                                        $iconev = "<img src='images/" . $datav['Flag'] . "20.gif' alt='Nation contrôlant le lieu'><a href='#' class='popup'><img src='images/route.gif'><span><b>Noeud Routier</b><ul><li>Les unités se déplaçant depuis un noeud routier ne subissent pas les malus dus au terrain.</li><li>Les unités se déplaçant entre deux noeuds routiers contrôlés par leur faction doublent leur distance de déplacement.</li><li>Les unités ennemies présentent sur le noeud routier (transformant la zone en zone de combat) annulent automatiquement tout bonus de déplacement.</li></ul></span></a>";
                                    else
                                        $iconev = "<img src='images/" . $datav['Flag'] . "20.gif' alt='Nation contrôlant le lieu'><img src='images/zone" . $datav['Zone'] . ".jpg'>";
                                    if ($Faction_Flag == $Faction) {
                                        if ($Faction != $Faction_Dest) {
                                            $Dist_Voyage[0] *= 1.5;
                                            if ($Dist_Voyage[0] > $Dist_max_skill)
                                                $iconev .= '<b class="text-danger">';
                                            elseif ($Dist_Voyage[0] <= $Dist_min)
                                                $iconev .= '<b class="text-success">';
                                            elseif ($Dist_Voyage[0] <= $Dist_max)
                                                $iconev .= '<b class="text-primary">';
                                            elseif ($Dist_Voyage[0] <= $Dist_max_skill)
                                                $iconev .= '<b class="text-warning">';
                                            else
                                                $iconev .= '<b>';
                                            $end_dist = '</b>';
                                        } else {
                                            if ($Dist_Voyage[0] > $Dist_max_skill) {
                                                $iconev .= '<span class="text-danger">';
                                            } elseif ($Dist_Voyage[0] <= $Dist_max_train and $NoeudF_Ori and $datav['NoeudF_Ori']) {
                                                $iconev .= '<span class="text-success">';
                                            } elseif ($Dist_Voyage[0] <= $Dist_min) {
                                                $iconev .= '<span class="text-success">';
                                            } else
                                                $iconev .= '<span>';
                                            $end_dist = '</span>';
                                        }
                                    } else {
                                        if ($Faction != $Faction_Dest) {
                                            $Dist_Voyage[0] *= 2;
                                            if ($Dist_Voyage[0] > $Dist_max_skill)
                                                $iconev .= '<b class="text-danger">';
                                            elseif ($Dist_Voyage[0] <= $Dist_min)
                                                $iconev .= '<b class="text-success">';
                                            elseif ($Dist_Voyage[0] <= $Dist_max)
                                                $iconev .= '<b class="text-primary">';
                                            elseif ($Dist_Voyage[0] <= $Dist_max_skill)
                                                $iconev .= '<b class="text-warning">';
                                            else
                                                $iconev .= '<b>';
                                        } else {
                                            $Dist_Voyage[0] *= 1.5;
                                            if ($Dist_Voyage[0] > $Dist_max_skill)
                                                $iconev .= '<b class="text-danger">';
                                            elseif ($Dist_Voyage[0] <= $Dist_min)
                                                $iconev .= '<b class="text-success">';
                                            elseif ($Dist_Voyage[0] <= $Dist_max)
                                                $iconev .= '<b class="text-primary">';
                                            elseif ($Dist_Voyage[0] <= $Dist_max_skill)
                                                $iconev .= '<b class="text-warning">';
                                            else
                                                $iconev .= '<b>';
                                        }
                                        $end_dist = '</b>';
                                    }
                                    /*if($Admin)
                                    {
								    	if($Dist_Voyage[0] <=$Dist_max_skill/1.5)
										{
											$iconev.="<b>";
											$end_dist="</b>";
										}
										elseif($Dist_Voyage[0] >$Dist_max_skill and $NoeudF_Ori and $datav['NoeudF_Ori'])
										{
											$iconev.="<em><b>";
											$end_dist="</b></em>";
										}
										elseif($Dist_Voyage[0] >$Dist_max and $Dist_Voyage[0] <=$Dist_max_skill)
										{
											$iconev.="<em>";
											$end_dist="</em>";
										}
										elseif($Dist_Voyage[0] >$Dist_max_skill)
											$iconev="";
									}*/
                                    if ($Long > $datav['Longitude'] + 0.1) {
                                        $sensh = 'Ouest';
                                        $coord += 2;
                                        if ($Impass == 2 or $Impass == 3 or $Impass == 4 or $Impass_ori == 6 or $Impass_ori == 7 or $Impass_ori == 8)
                                            $iconev = false;
                                    } elseif ($Long < $datav['Longitude'] - 0.1) {
                                        $sensh = 'Est';
                                        $coord += 1;
                                        if ($Impass == 6 or $Impass == 7 or $Impass == 8 or $Impass_ori == 2 or $Impass_ori == 3 or $Impass_ori == 4)
                                            $iconev = false;
                                    }
                                    if ($sensh) {
                                        if ($Lat > $datav['Latitude'] + 0.1) {
                                            $sensv = 'Sud';
                                            $coord += 20;
                                            if ($Impass == 1 or $Impass == 2 or $Impass == 8 or $Impass_ori == 4 or $Impass_ori == 5 or $Impass_ori == 6)
                                                $iconev = false;
                                        } elseif ($Lat < $datav['Latitude'] - 0.1) {
                                            $sensv = 'Nord';
                                            $coord += 10;
                                            if ($Impass == 4 or $Impass == 5 or $Impass == 6 or $Impass_ori == 1 or $Impass_ori == 2 or $Impass_ori == 8)
                                                $iconev = false;
                                        }
                                    } else {
                                        if ($Lat > $datav['Latitude']) {
                                            $sensv = 'Sud';
                                            $coord += 20;
                                            if ($Impass == 1 or $Impass == 2 or $Impass == 8 or $Impass_ori == 4 or $Impass_ori == 5 or $Impass_ori == 6)
                                                $iconev = false;
                                        } elseif ($Lat < $datav['Latitude']) {
                                            $sensv = 'Nord';
                                            $coord += 10;
                                            if ($Impass == 4 or $Impass == 5 or $Impass == 6 or $Impass_ori == 1 or $Impass_ori == 2 or $Impass_ori == 8)
                                                $iconev = false;
                                        }
                                    }
                                    $sens = $sensv . ' ' . $sensh;
                                    if ($iconev)
                                        $choix = $iconev . " " . $datav['Nom'] . " - " . $Dist_Voyage[0] . "km - " . $sens . $end_dist . "<br>";
                                    if ($coord == 1) //Est
                                        $Est_txt .= $choix;
                                    elseif ($coord == 2) //Ouest
                                        $Ouest_txt .= $choix;
                                    elseif ($coord == 10) //Nord
                                        $Nord_txt .= $choix;
                                    elseif ($coord == 20) //Sud
                                        $Sud_txt .= $choix;
                                    elseif ($coord == 11) //NE
                                        $NE_txt .= $choix;
                                    elseif ($coord == 21) //SE
                                        $SE_txt .= $choix;
                                    elseif ($coord == 12) //NO
                                        $NO_txt .= $choix;
                                    elseif ($coord == 22) //SO
                                        $SO_txt .= $choix;
                                }
                            }
                        }
                        unset($Dist_Voyage);
                        mysqli_free_result($voyage);
                    }
                    $list_voyage .= "<div class='row'><div class='col-md-4'>" . $NO_txt . "</div><div class='col-md-4'>" . $Nord_txt . "</div><div class='col-md-4'>" . $NE_txt . "</div></div>"
                        . "<div class='row'><div class='col-md-4'>" . $Ouest_txt . "</div><div class='col-md-4'></div><div class='col-md-4'>" . $Est_txt . "</div></div>"
                        . "<div class='row'><div class='col-md-4'>" . $SO_txt . "</div><div class='col-md-4'>" . $Sud_txt . "</div><div class='col-md-4'>" . $SE_txt . "</div></div>
                    <div class='row'><div class='col-md-12'><div class='alert alert-warning'>" . $txt_help_voyage . "</div></div></div>";
                }
                if ($result) {
                    /*if($Zone ==6)
						$Base_txt="Porte-avions";
					else
						$Base_txt="Base aérienne";
					$air_units.="<tr><th colspan='15'>".$Base_txt."</th></tr>";*/
                    while ($datau = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $Cdt = 'Inconnu';
                        if ($Officier_acces) {
                            if ($datau['Pays'] == $country or $Admin) {
                                if ($datau['Armee']) $Cdt = '<span class="btn btn-sm btn-danger">Armée</span>';
                                $Nbr = $datau['Avion1_Nbr'] + $datau['Avion2_Nbr'] + $datau['Avion3_Nbr'];
                                if ($datau['Commandant'])
                                    $Cdt = GetData("Pilote", "ID", $datau['Commandant'], "Nom");
                                elseif ($datau['Etat'] == 0)
                                    $Cdt = "<span class='label label-danger'>Unité inactive</span>";
                                elseif (!$Map_output and $OfficierEMID > 0 and ($Commandant == $OfficierEMID or $Adjoint_EM == $OfficierEMID or $Admin or ($Armee > 0 and $Armee == $datau['Armee']))) {
                                    if ($QualitePiste > 50) {
                                        if (!$datau['Mission_IA'] and $Faction == $Faction_Flag and $Faction == $Faction_Air)
                                            $Cdt = "<form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='" . $datau['ID'] . "'>
											<input type='submit' value='Ordre' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                                        elseif ($Faction != $Faction_Flag or $Faction != $Faction_Air)
                                            $Cdt = "<span class='label label-danger'>Sous le feu</span>";
                                        else
                                            $Cdt = "<span class='label label-danger'>En vol</span>";
                                    } else
                                        $Cdt = "<span class='label label-danger'>Piste rasée</span>";
                                }
                                $air_units_enc += 1;
                            } else
                                $Nbr = '?';
                            $air_units .= "<tr><td>" . Afficher_Icone($datau['ID'], $datau['Pays'], $datau['Nom']) . "<br>" . $datau['Nom'] . "</td><td><img src='images/" . $datau['Pays'] . "20.gif'></td><td>" . $Cdt . "</td><td><a href='#' class='popup'><img src='images/vehicules/vehicule111.gif'> " . $datau['Garnison'] . "<span>Ces troupes défendront les avions contre les attaques terrestres</span></a></td><td>" . $Nbr . " 
							" . GetAvionIcon($datau['Avion1'], $datau['Pays'], 0, $datau['ID'], $Front_Lieu) . " " . GetAvionIcon($datau['Avion2'], $datau['Pays'], 0, $datau['ID'], $Front_Lieu) . " " . GetAvionIcon($datau['Avion3'], $datau['Pays'], 0, $datau['ID'], $Front_Lieu) . "<td></tr>";
                        } else
                            $air_units .= "<tr><td>" . Afficher_Icone($datau['ID'], $datau['Pays'], $datau['Nom']) . "</td><td><img src='images/" . $datau['Pays'] . "20.gif'></td><td>" . $Cdt . "</td><td>Inconnu</td><td>
							" . GetAvionIcon($datau['Avion1'], $datau['Pays'], 0, $datau['ID'], $Front_Lieu) . " " . GetAvionIcon($datau['Avion2'], $datau['Pays'], 0, $datau['ID'], $Front_Lieu) . " " . GetAvionIcon($datau['Avion3'], $datau['Pays'], 0, $datau['ID'], $Front_Lieu) . "<td></tr>";
                    }
                    mysqli_free_result($result);
                }
                if ($result_blitz) {
                    while ($data = mysqli_fetch_array($result_blitz)) {
                        $exp = '';
                        $icon_fire = false;
                        $Led = false;
                        $Ordres_Armee_Ici = false;
                        /*if($Admin ==1)
						{
							$Vis_v=$data['Taille']/$data['Camouflage'];
							if($data['Officier_ID'] >0)
							{
								if($data['Bomb_IA'])$exp="<img src='images/map/noia.png' title='Ne peut plus être ciblé par les bombardements aériens EM jusque son prochain déplacement'>";
								if($data['Arti_IA'])$exp.="<img src='images/map/noart.png' title='Ne peut plus être ciblé par les bombardements terrestres ou navals jusque son prochain déplacement'>";
								if($data['Bomb_PJ'])$exp.="<img src='images/map/nopj.png' title='Ne peut plus être ciblé par les Joueurs jusque son prochain déplacement ou sa prochaine attaque'>";
								$exp.=" (".$data['Visible']."V - ".$data['Move']."M - Taille ".$Vis_v." - ".GetPosGr($data['Position'])." - ".$data['Experience']."XP - [".GetData("Officier","ID",$data['Officier_ID'],"Credits")."]CT - ".$data['HP']." HP - ".$data['Moral']." Moral || Stocks 87 ".$data['Stock_Essence_87']."; Stocks Diesel ".$data['Stock_Essence_1']."; Stocks 8mm ".$data['Stock_Munitions_8'].")";
							}
							else
							{
								if($data['Skill'])$exp="<img src='images/skills/skillo".$data['Skill'].".png' style='width:".$Skill_scale."%;'>";
								if($data['Matos'])$exp.="<img src='images/skills/skille".$data['Matos'].".png' style='width:".$Skill_scale."%;'></a>";
								if($data['Bomb_IA'])$exp.="<img src='images/map/noia.png' title='Ne peut plus être ciblé par les bombardements aériens EM jusque au prochain passage de date'>";
								if($data['Arti_IA'])$exp.="<img src='images/map/noart.png' title='Ne peut plus être ciblé par les bombardements terrestres ou navals jusque au prochain passage de date'>";
								if($data['Ravit'])$exp.="<img src='images/map/air_ravit.png' title='Ravitaillé par air'>";
								$exp.=" <i>(".$data['Visible']."V - T".$Vis_v." - ".$data['Experience']."XP - ".$data['HP']."HP - ".GetPosGr($data['Position'])." - ".$data['Moral']." Moral)</i>";
							}
						}
						else*/
                        if ($Officier_acces) {
                            if ($data['Pays'] == $country) {
                                if ($data['Skill']) $exp .= "<img src='images/skills/skillo" . $data['Skill'] . ".png' style='width:" . $Skill_scale . "%;'>";
                                if ($data['Matos']) $exp .= "<img src='images/skills/skille" . $data['Matos'] . ".png' style='width:" . $Skill_scale . "%;'>";
                                $exp .= "<span class='label label-primary'>" . $data['Experience'] . "XP</span>";
                                if ($data['Placement'] == 2)
                                    $Zone_calc = 0;
                                else
                                    $Zone_calc = $Zone;
                                $Autonomie_Min = $data['Fuel'];
                                if ($data['mobile'] == 3) {
                                    if ($data['Skill'] == 23) $Autonomie_Min *= 1.1;
                                    elseif ($data['Skill'] == 114) $Autonomie_Min *= 1.2;
                                    elseif ($data['Skill'] == 115) $Autonomie_Min *= 1.3;
                                    elseif ($data['Skill'] == 116) $Autonomie_Min *= 1.4;
                                    $data['Matos'] = 9999; //Pour function Auto_max;
                                }
                                if ($data['Matos'] == 14) $Autonomie_Min *= 1.5;
                                elseif ($data['Matos'] == 15) $Autonomie_Min *= 1.1;
                                elseif ($data['Matos'] == 28) $Autonomie_Min *= 2;
                                elseif ($data['Matos'] == 30) $Autonomie_Min *= 1.5;
                                $Auto = Get_LandSpeed($Autonomie_Min, $data['mobile'], $Zone_calc, 0, $data['Type'], 0, 0, $Amphi, $Front_Lieu, true);
                                $exp .= "<span class='label label-warning'>" . Auto_max($Auto, $Zone_calc, $data['mobile'], $Front_Lieu, $data['Type'], $data['Matos'], $Lat) . "km</span>";
                            }
                            if ($data['Position'] == 8 or $data['Position'] == 9) $exp .= "<img src='images/mortar.png' title='Sous le feu'>";
                            if ($data['Bomb_IA']) {
                                if ($data['Officier_ID'] > 0)
                                    $exp .= "<img src='images/map/noia.png' title='Ne peut plus être ciblé par les bombardements aériens EM jusque son prochain déplacement'>";
                                else
                                    $exp .= "<img src='images/map/noia.png' title='Ne peut plus être ciblé par les bombardements aériens EM jusque au prochain passage de date'>";
                            }
                            if ($data['Bomb_PJ'] and $data['Officier_ID'] > 0) $exp .= "<img src='images/map/nopj.png' title='Ne peut plus être ciblé par les Joueurs jusque son prochain déplacement ou sa prochaine attaque'>";
                            if ($data['Arti_IA']) $exp .= "<img src='images/map/noart.png' title='Ne peut plus être ciblé par les bombardements terrestres ou navals jusque au prochain déplacement'>";
                            if ($data['Ravit']) $exp .= "<img src='images/map/air_ravit.png' title='Ravitaillé par air'>";
                            if ($data['Vehicule_ID'] > 4999 and $data['HP'] < $data['HP_max'] / 2) $icon_fire = "<img src='images/map/lieu_fire.png'>";
                            if ($Admin == 1) {
                                //$Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi,$Front,true);
                                $exp .= " <i>(" . $data['Visible'] . "V / " . $data['Move'] . "M / " . $data['Atk'] . "A - T" . ($data['Taille'] / $data['Camouflage']) . " - " . $data['HP'] . "HP - " . GetPosGr($data['Position']) . ")</i>";
                            }
                        }
                        if ($data['Pays'] == $country or $Admin) {
                            $ground_units_enc += 1;
                            if ($data['Officier_ID'] > 0) {
                                $Cdt = GetData("Officier", "ID", $data['Officier_ID'], "Nom");
                                if ($Admin == 1) {
                                    $Cdt .= " (" . $data['Officier_ID'] . ")";
                                    if (!$data['Vehicule_Nbr']) $Cdt .= " <span class='label label-danger'>Inactif</span>";
                                }
                            } elseif ($data['Division']) {
                                if ($Armee) {
                                    $Armee_unit = mysqli_result(mysqli_query($con, "SELECT Armee FROM Division WHERE ID=" . $data['Division']), 0);
                                    if ($Armee_unit == $Armee) $Ordres_Armee_Ici = true;
                                }
                                $Cdt = Afficher_Image('images/div/div' . $data['Division'] . '.png', 'images/' . $data['Pays'] . 'div.png', '', 0);
                            } else
                                $Cdt = 'Inconnu';
                            if ($Officier_acces)
                                $Veh_Nbr = $data['Vehicule_Nbr'];
                            else
                                $Veh_Nbr = RangeNbr($data['Vehicule_Nbr']);
                            if ($data['Type'] == 99) $exp .= "<img src='images/map/mp.png' title='Réduit les risques de sabotages'>";
                        } else {
                            $Cdt = 'Inconnu';
                            if (!$data['Officier_ID'] and $data['Position'] == 11) {
                                $data['Vehicule_ID'] = 5000;
                                $Veh_Nbr = ceil($Veh_Nbr / 10);
                                $data['Nom'] = 'Barges de transport';
                            } else
                                $Veh_Nbr = RangeNbr($data['Vehicule_Nbr']);
                        }
                        if ($data['Placement'] == 1) $Place_icon = "<img src='images/map/lieu_air" . $Flag_Air . ".png'>";
                        elseif ($data['Placement'] == 2) $Place_icon = "<img src='images/map/lieu_route" . $Flag_Route . ".png'>";
                        elseif ($data['Placement'] == 3) $Place_icon = "<img src='images/map/lieu_gare" . $Flag_Gare . ".png'>";
                        elseif ($data['Placement'] == 4) $Place_icon = "<img src='images/map/lieu_port" . $Flag_Port . ".png'>";
                        elseif ($data['Placement'] == 5) $Place_icon = "<img src='images/map/lieu_pont" . $Flag_Pont . ".png'>";
                        elseif ($data['Placement'] == 6) $Place_icon = "<img src='images/map/icone_usine" . $Flag_Usine . ".gif'>";
                        elseif ($data['Placement'] == 7) $Place_icon = "<img src='images/vehicules/vehicule15.gif'>";
                        elseif ($data['Placement'] == 8) $Place_icon = "<img src='images/icone_mer.png'>";
                        elseif ($data['Placement'] == 9) $Place_icon = "<img src='images/icone_mer.png'>";
                        elseif ($data['Placement'] == 11) $Place_icon = "<img src='images/plage.jpg'>";
                        else
                            $Place_icon = "<img src='images/icone_fort.gif'>";
                        if (IsAxe($data['Pays'])) {
                            if ($data['Placement'] != $Placement_Ori_Axe) {
                                $Placement = GetPlace($data['Placement']);
                                $titrez = "<tr class='warning'><th colspan='15'>" . $Place_icon . " " . $Placement . "</th></tr>";
                                if ($data['Pays'] == $country or $Admin) {
                                    if ($ground_units_enc > GetEmboutMax($ValeurStrat, $data['Placement'], $Zone, $Front_Lieu))
                                        $units_axe .= "<tr><td colspan='15'><div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Embouteillage!</strong> Un trop grand nombre d'unités encombre cette zone!</div></td></tr>";
                                }
                                $ground_units_enc = false;
                            } else
                                $titrez = '';
                            $Placement_Ori_Axe = $data['Placement'];
                            $units_axe .= $titrez;
                            if (!$Map_output and $OfficierEMID > 0 and ($Commandant == $OfficierEMID or $Adjoint_Terre == $OfficierEMID or $Admin or $Ordres_Armee_Ici) and $data['Pays'] == $country) {
                                if (!$data['Officier_ID']) {
                                    if ($data['Move'])
                                        $Led = "<img src='images/led_red.png'>";
                                    else
                                        $Led = "<img src='images/led_green.png'>";
                                    if ($today['mday'] > $data['Jour'] + 1)
                                        $Combat_flag = false;
                                    elseif ($today['year'] > $data['Year_a'])
                                        $Combat_flag = false;
                                    elseif ($today['mon'] > $data['Mois'])
                                        $Combat_flag = false;
                                    elseif ($today['mday'] != $data['Jour'] and $today['hours'] >= $data['Heure'])
                                        $Combat_flag = false;
                                    else
                                        $Combat_flag = true;
                                    if ($today['mday'] > $data['Jour_m'] + 1)
                                        $Move_flag = false;
                                    elseif ($today['year'] > $data['Year_m'])
                                        $Combat_flag = false;
                                    elseif ($today['mon'] > $data['Mois_m'])
                                        $Move_flag = false;
                                    elseif ($today['mday'] != $data['Jour_m'] and $today['hours'] >= $data['Heure_m'])
                                        $Move_flag = false;
                                    else
                                        $Move_flag = true;
                                    if ($data['Position'] == 12)
                                        $Cie_ID = "<span class='label label-danger'>En Vol</span>";
                                    elseif ($data['Atk'] == 1 or $Combat_flag)
                                        $Cie_ID = "<span class='label label-danger'>En Combat</span>";
                                    elseif ($data['mobile'] != 5 and ($data['Move'] == 1 or $Move_flag))
                                        $Cie_ID = "<span class='label label-danger'>Mouvement</span>";
                                    else
                                        $Cie_ID = "<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='" . $data['ID'] . "'>" . $Led . "
										<input type='Submit' value='" . $data['ID'] . "' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                                } else
                                    $Cie_ID = '' . $data['ID'] . 'e Cie';
                            } else
                                $Cie_ID = '' . $data['ID'] . 'e Cie';
                            $units_axe .= "<tr><td align='left'>" . $Cie_ID . "</td>
							<td><img src='images/" . $data['Pays'] . "20.gif'></td><td>" . $Cdt . "</td>
							<td>" . $Veh_Nbr . "</td><td>" . $icon_fire . GetVehiculeIcon($data['Vehicule_ID'], $data['Pays'], 0, 0, $Front_Lieu, $data['Nom']) . $exp . "</td></tr>";
                        } else {
                            if ($data['Placement'] != $Placement_Ori_Allies) {
                                $Placement = GetPlace($data['Placement']);
                                $titrez = "<tr class='warning'><th colspan='15'>" . $Place_icon . " " . $Placement . "</th></tr>";
                                if ($data['Pays'] == $country or $Admin) {
                                    if ($ground_units_enc > GetEmboutMax($ValeurStrat, $data['Placement'], $Zone, $Front_Lieu))
                                        $units_allies .= "<tr><td colspan='15'><div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Embouteillage!</strong> Un trop grand nombre d'unités encombre cette zone!</div></td></tr>";
                                }
                                $ground_units_enc = false;
                            } else
                                $titrez = "";
                            $Placement_Ori_Allies = $data['Placement'];
                            $units_allies .= $titrez;
                            if (!$Map_output and $OfficierEMID > 0 and ($Commandant == $OfficierEMID or $Adjoint_Terre == $OfficierEMID or $Admin) and $data['Pays'] == $country) {
                                if (!$data['Officier_ID']) {
                                    if ($data['Move'])
                                        $Led = "<img src='images/led_red.png'>";
                                    else
                                        $Led = "<img src='images/led_green.png'>";
                                    if ($today['mday'] > $data['Jour'] + 1)
                                        $Combat_flag = false;
                                    elseif ($today['mon'] > $data['Mois'])
                                        $Combat_flag = false;
                                    elseif ($today['year'] > $data['Year_a'])
                                        $Combat_flag = false;
                                    elseif ($today['mday'] != $data['Jour'] and $today['hours'] >= $data['Heure'])
                                        $Combat_flag = false;
                                    else
                                        $Combat_flag = true;
                                    if ($today['mday'] > $data['Jour_m'] + 1)
                                        $Move_flag = false;
                                    elseif ($today['mon'] > $data['Mois_m'])
                                        $Move_flag = false;
                                    elseif ($today['year'] > $data['Year_m'])
                                        $Combat_flag = false;
                                    elseif ($today['mday'] != $data['Jour_m'] and $today['hours'] >= $data['Heure_m'])
                                        $Move_flag = false;
                                    else
                                        $Move_flag = true;
                                    if ($data['Position'] == 12)
                                        $Cie_ID = "<span class='label label-danger'>En Vol</span>";
                                    elseif ($data['Atk'] == 1 or $Combat_flag)
                                        $Cie_ID = "<span class='label label-danger'>En Combat</span>";
                                    elseif ($data['mobile'] != 5 and ($data['Move'] == 1 or $Move_flag))
                                        $Cie_ID = "<span class='label label-danger'>Mouvement</span>";
                                    elseif ($data['NoEM'])
                                        $Cie_ID = "<span class='label label-danger'>Réservé</span>";
                                    else
                                        $Cie_ID = "<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='" . $data['ID'] . "'>" . $Led . "
										<input type='Submit' value='" . $data['ID'] . "' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                                } else
                                    $Cie_ID = '' . $data['ID'] . 'e Cie';
                            } else
                                $Cie_ID = '' . $data['ID'] . 'e Cie';
                            $units_allies .= "<tr><td align='left'>" . $Cie_ID . "</td>
							<td><img src='images/" . $data['Pays'] . "20.gif'></td><td>" . $Cdt . "</td>
							<td>" . $Veh_Nbr . "</td><td>" . $icon_fire . GetVehiculeIcon($data['Vehicule_ID'], $data['Pays'], 0, 0, $Front_Lieu, $data['Nom']) . $exp . "</td></tr>";
                        }
                        /*if($data['Placement'] !=$Placement_Ori)
							{
								$Placement=GetPlace($data['Placement']);
								$titrez="<tr class='warning'><th colspan='15'>".$Placement."</th></tr>";
							}
							else
								$titrez="";
							$Placement_Ori=$data['Placement'];
							$units.=$titrez;
							$units.="<tr><td align='left'>".$data['ID']."e Cie</td>
							<td><img src='images/".$data['Pays']."20.gif'></td><td>".$Cdt."</td>
							<td>".$Veh_Nbr."</td><td>".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front_Lieu,$data['Nom']).$exp."</td></tr>";*/
                    }
                    mysqli_free_result($result_blitz);
                } else
                    $mes .= 'Désolé, aucune unité terrestre recensée.';
                if ($Cible_base and (($Flag == $country and $Officier_acces) or $Admin == 1)) {
                    include_once './jfv_combat.inc.php';
                    $DCA_guns = GetDCA($Flag, $Cible_DefenseAA);
                    $hgun = $DCA_guns[0];
                    $gun = $DCA_guns[1];
                    $mg = $DCA_guns[2];
                    if ($hgun and $hgun != 5)
                        $dca_piecei .= "<tr><td><img src='images/aa" . $hgun . ".png' title='" . GetData("Armes", "ID", $hgun, "Nom") . "'><td>2000-10000m</td></tr>";
                    if ($gun and $gun != 5)
                        $dca_piecei .= "<tr><td><img src='images/aa" . $gun . ".png' title='" . GetData("Armes", "ID", $gun, "Nom") . "'><td>100-7000m</td></tr>";
                    if ($mg and $mg != 5)
                        $dca_piecei .= "<tr><td><img src='images/aa" . $mg . ".png' title='" . GetData("Armes", "ID", $mg, "Nom") . "'><td>100-2000m</td></tr>";
                    if ($dca_piecei)
                        $dca_pieces = "<h2>Composition de la défense anti-aérienne des infrastructures</h2><table class='table'>
							<thead><tr><th>Type</th><th>Altitude</th></tr></thead>" . $dca_piecei . "</table>";
                    if ($Flag_Air == $country) {
                        $dca_res = mysqli_query($con, "SELECT DCA_ID,DCA_Nbr,DCA_Exp,Alt,Unit FROM Flak WHERE Lieu='$Cible'");
                        if ($dca_res) {
                            while ($data_flak = mysqli_fetch_array($dca_res, MYSQLI_ASSOC)) {
                                $DCA_ID = $data_flak['DCA_ID'];
                                $DCA_Nbr = $data_flak['DCA_Nbr'];
                                $DCA_Exp = floor($data_flak['DCA_Exp']);
                                $DCA_Alt = $data_flak['Alt'];
                                $DCA_Nom = GetData("Armes", "ID", $DCA_ID, "Nom");
                                $dca_piecex .= "<tr><td><img src='images/aa" . $DCA_ID . ".png' title='" . $DCA_Nom . "'><td>" . $DCA_Nbr . "</td><td>" . $DCA_Alt . "m</td><td>" . $DCA_Exp . "</td></tr>";
                            }
                            mysqli_free_result($dca_res);
                        }
                        if ($dca_piecex)
                            $dca_pieces .= "<h2>Composition de la défense anti-aérienne de l'aérodrome</h2><table class='table'>
								<thead><tr><th>Type</th><th>Nombre</th><th>Altitude</th><th>Expérience</th></tr></thead>" . $dca_piecex . "</table>";
                    }
                }
                if ($Plage)
                    $Plage_txt = "<img src='images/plage.jpg' title='Zone propice au débarquement'>";
                if ($Officier_acces or $Admin == 1) {
                    if ($Admin == 1) {
                        $Lieu = $Cible;
                        //<a href='em_city_journal.php?id=".$Cible."' target='_blank' class='btn btn-primary'>Archives aériennes</a>";
                        $query_dem = "(SELECT DISTINCT u.Mission_Type_D,p.Pays_ID,u.Nom,l.Recce,l.ID 
                        FROM Unit u,Lieu l,Pays p
						WHERE l.ID=$Cible AND u.Pays=p.Pays_ID AND u.Mission_Lieu_D >0 AND u.Mission_Type_D >0 AND u.Mission_Lieu_D=l.ID) 
						UNION (SELECT DISTINCT r.Mission_Type_D,r.Pays,r.ID,l.Recce,l.ID 
						FROM Lieu as l,Regiment_IA as r,Pays as p 
						WHERE r.Pays=p.Pays_ID AND r.Mission_Lieu_D=l.ID AND r.Mission_Lieu_D=$Cible AND r.Mission_Type_D >0 AND p.Faction=$Faction)";
                        /*UNION (SELECT DISTINCT Officier.Mission_Type_D,Pays.Pays_ID,Officier.Nom,Lieu.Recce,Lieu.ID FROM Officier,Lieu,Pays
						WHERE Officier.Pays=Pays.Pays_ID AND Lieu.ID='$Cible' AND Officier.Mission_Lieu_D >0 AND Officier.Mission_Type_D >0 AND Officier.Mission_Lieu_D=Lieu.ID)*/
                        $query_mi = "SELECT Zone,Recce,
						(SELECT COUNT(*) FROM Pilote WHERE Pilote.Couverture=$Cible AND Pilote.Front=$Front_Lieu) AS Couverturer,
						(SELECT COUNT(*) FROM Pilote_IA WHERE Pilote_IA.Couverture=$Cible AND Pilote_IA.Cible=$Cible AND Pilote_IA.Actif='1') AS Couverture_ia,
						(SELECT COUNT(*) FROM Pilote WHERE Pilote.Couverture_nuit=$Cible AND Pilote.Front=$Front_Lieu) AS Couverturer_nuit,
						(SELECT COUNT(*) FROM Pilote_IA WHERE Pilote_IA.Couverture_nuit='$Cible' AND Pilote_IA.Cible=$Cible AND Pilote_IA.Actif='1') AS Couverture_nuit_ia,
						(SELECT COUNT(*) FROM Pilote WHERE Pilote.Escorte=$Cible) AS Escorter,
						(SELECT COUNT(*) FROM Pilote_IA WHERE Pilote_IA.Escorte=$Cible AND Pilote_IA.Cible=$Cible AND Pilote_IA.Actif='1') AS Escorte_ia,
						(SELECT COUNT(*) FROM Pilote WHERE Pilote.ID=Lieu.Recce_PlayerID AND Lieu.ID=$Cible) AS Reco,
						(SELECT COUNT(*) FROM Pilote_IA WHERE Pilote_IA.Cible=$Cible AND Pilote_IA.Task>0 AND Pilote_IA.Actif='1') AS Tasks_Reco
						FROM Lieu WHERE ID=$Cible";
                        //(SELECT COUNT(*) FROM Regiment WHERE Regiment.Lieu_ID='$Cible' AND Regiment.Visible='1' AND Regiment.Vehicule_Nbr>1) AS PJ_Ground,
                        /*$query_e="(SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l WHERE j.ID=l.Pilote AND j.Escorte='$Cible' AND l.Lieu='$Cible')
						UNION (SELECT i.ID,i.Nom,i.Unit,i.Pays,i.Alt,i.Avion FROM Pilote_IA as i WHERE i.Escorte='$Cible' AND i.Actif='1')";*/
                        $query_e = "SELECT * FROM Pilote_IA WHERE Escorte='$Cible' AND Cible='$Cible' AND Actif='1'";
                        $query_c = "SELECT * FROM Pilote_IA WHERE Couverture='$Cible' AND Cible='$Cible' AND Actif='1'";
                        $query_n = "SELECT * FROM Pilote_IA WHERE Couverture_Nuit='$Cible' AND Cible='$Cible' AND Actif='1'";
                        /*$query_c="(SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l WHERE j.ID=l.Pilote AND j.Couverture='$Cible' AND l.Lieu='$Cible')
						UNION (SELECT i.ID,i.Nom,i.Unit,i.Pays,i.Alt,i.Avion FROM Pilote_IA as i WHERE i.Couverture='$Cible' AND i.Actif='1')";*/
                        $query_d = "SELECT * FROM Pilote_IA WHERE Cible='$Cible' AND Task>0 AND Actif=1";
                    } else {
                        if ($Premium or $OfficierEMID)
                            $Admin_txt = "<a href='em_city_combats.php?id=" . $Cible . "' target='_blank' class='btn btn-primary'>Archives des combats</a>";
                        if ($Premium)
                            $Admin_txt .= " <a href='em_city_dca.php?id=" . $Cible . "' target='_blank' class='btn btn-warning'>DCA</a>";
                        //<a href='em_city_journal.php?id=".$Cible."' target='_blank' class='btn btn-primary'>Archives aériennes</a>";
                        $query_dem = "SELECT DISTINCT u.Mission_Type_D,p.Pays_ID,u.Nom,l.Recce,l.ID FROM Unit u,Lieu l,Pays p
						WHERE l.ID=$Cible AND u.Pays=p.Pays_ID AND u.Mission_Lieu_D >0 AND u.Mission_Type_D >0 AND p.Faction=$Faction AND u.Mission_Lieu_D=l.ID";
                        /*UNION (SELECT DISTINCT Officier.Mission_Type_D,Pays.Pays_ID,Officier.Nom,Lieu.Recce,Lieu.ID FROM Officier,Lieu,Pays
						WHERE Officier.Pays=Pays.Pays_ID AND Lieu.ID='$Cible' AND Officier.Mission_Lieu_D >0 AND Officier.Mission_Type_D >0 AND Pays.Faction='$Faction' AND Officier.Mission_Lieu_D=Lieu.ID)*/
                        $query_mi = "SELECT Zone,Recce,
						(SELECT COUNT(*) FROM Pilote WHERE Pilote.Couverture='$Cible' AND Pilote.Front='$Front_Lieu' AND Pilote.Pays='$country') AS Couverturer,
						(SELECT COUNT(*) FROM Pilote_IA WHERE Pilote_IA.Couverture='$Cible' AND Pilote_IA.Cible='$Cible' AND Pilote_IA.Pays='$country' AND Pilote_IA.Actif='1') AS Couverture_ia,
						(SELECT COUNT(*) FROM Pilote WHERE Pilote.Escorte='$Cible' AND Pilote.Pays='$country') AS Escorter,
						(SELECT COUNT(*) FROM Pilote_IA WHERE Pilote_IA.Escorte='$Cible' AND Pilote_IA.Cible='$Cible' AND Pilote_IA.Pays='$country' AND Pilote_IA.Actif='1') AS Escorte_ia,
						(SELECT COUNT(*) FROM Pilote WHERE Pilote.ID=Lieu.Recce_PlayerID AND Lieu.ID='$Cible' AND Pilote.Pays='$country') AS Reco,
						(SELECT COUNT(*) FROM Pilote_IA WHERE Pilote_IA.Cible='$Cible' AND Pilote_IA.Task>0 AND Pilote_IA.Pays='$country' AND Pilote_IA.Actif='1') AS Tasks_Reco
						FROM Lieu WHERE ID='$Cible'";
                        //(SELECT COUNT(*) FROM Regiment WHERE Regiment.Lieu_ID='$Cible' AND Regiment.Visible=1 AND Regiment.Vehicule_Nbr>1) AS PJ_Ground,
                        $query_e = "SELECT i.ID,i.Nom,i.Unit,i.Pays,i.Alt,i.Avion,i.Endurance FROM Pilote_IA as i,Pays as p WHERE i.Pays=p.ID AND p.Faction='$Faction' AND i.Escorte='$Cible' AND i.Cible='$Cible' AND i.Actif='1'";
                        /*$query_e="(SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p
						WHERE j.ID=l.Joueur AND j.Pays=p.ID AND p.Faction='$Faction' AND j.Escorte='$Cible' AND l.Lieu='$Cible')
						UNION (SELECT i.ID,i.Nom,i.Unit,i.Pays,i.Alt,i.Avion FROM Pilote_IA as i,Pays as p WHERE i.Pays=p.ID AND p.Faction='$Faction' AND i.Escorte='$Cible' AND i.Actif='1')";*/
                        /*$query_c="(SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p
						WHERE j.ID=l.Joueur AND j.Pays=p.ID AND p.Faction='$Faction' AND j.Couverture='$Cible' AND l.Lieu='$Cible')
						UNION (SELECT i.ID,i.Nom,i.Unit,i.Pays,i.Alt,i.Avion FROM Pilote_IA as i,Pays as p WHERE i.Pays=p.ID AND p.Faction='$Faction' AND i.Couverture='$Cible' AND i.Actif='1')";*/
                        $query_c = "SELECT i.ID,i.Nom,i.Unit,i.Pays,i.Alt,i.Avion,i.Endurance FROM Pilote_IA as i,Pays as p WHERE i.Pays=p.ID AND p.Faction='$Faction' AND i.Couverture='$Cible' AND i.Cible='$Cible' AND i.Actif='1'";
                        $query_n = "SELECT i.ID,i.Nom,i.Unit,i.Pays,i.Alt,i.Avion,i.Endurance FROM Pilote_IA as i,Pays as p WHERE i.Pays=p.ID AND p.Faction='$Faction' AND i.Couverture_Nuit='$Cible' AND i.Cible='$Cible' AND i.Actif='1'";
                        $query_d = "SELECT * FROM Pilote_IA WHERE Cible='$Cible' AND Task>0 AND Pays='$country' AND Actif=1";
                    }
                    //Demandes en cours
                    $txt = '';
                    $result = mysqli_query($con, $query_dem);
                    $result_mi = mysqli_query($con, $query_mi);
                    if ($result) {
                        while ($Data_dem = mysqli_fetch_array($result, MYSQLI_NUM)) {
                            if ($Data_dem[1] == 6) {
                                $Nav_eni = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Data_dem[6]' AND Pays<>'$country' AND Vehicule_Nbr >0 AND Visible=1"), 0);
                                if ($Nav_eni > 0)
                                    $Recce = '<b>Oui</b>';
                                else
                                    $Recce = 'Non';
                            } else {
                                if ($Data_dem[5] == 2)
                                    $Recce = '<b>Eclairé</b>';
                                elseif ($Data_dem[5] == 1)
                                    $Recce = '<b>Oui</b>';
                                else
                                    $Recce = 'Non';
                            }
                            $dem_txt .= "<tr><td>" . GetMissionType($Data_dem[0]) . "</td><td><img src='images/" . $Data_dem[1] . "20.gif' title='" . $Data_dem[2] . "'></td><td>" . $Recce . "</td></tr>";
                        }
                        mysqli_free_result($result);
                    }
                    if ($result_mi) {
                        while ($Data = mysqli_fetch_array($result_mi, MYSQLI_ASSOC)) {
                            if ($Data['Couverturer'] or $Data['Escorter'] or $Data['Reco'] or $Data['PJ_Ground'] or $Data['Couverture_ia'] or $Data['Escorte_ia']) {
                                if ($Data['Zone'] == 6) {
                                    if ($Data['PJ_Ground']) {
                                        $Cible_i = 'Unités navales';
                                        $Recce = 'Tactique';
                                    } else
                                        $Recce = 'Non';
                                } else {
                                    if ($Data['Recce'] == 2)
                                        $Recce = '<b>Eclairé</b>';
                                    elseif ($Data['Recce'] == 1)
                                        $Recce = '<b>Oui</b>';
                                    elseif ($Data['PJ_Ground'])
                                        $Recce = 'Tactique';
                                    else
                                        $Recce = 'Non';
                                    if ($Data['PJ_Ground'])
                                        $Cible_i = 'Unités terrestres';
                                }
                                if (!$Data['PJ_Ground']) {
                                    if ($Data['Recce'])
                                        $Cible_i = 'Infrastructures';
                                    else
                                        $Cible_i = 'Aucune';
                                }
                                if (!$Data['Escorter'] and !$Data['Escorte_ia'])
                                    $Escortes = 0;
                                else
                                    $Escortes = $Data['Escorter'] + $Data['Escorte_ia'];
                                if (!$Data['Couverturer'] and !$Data['Couverture_ia'])
                                    $Couvertures = 0;
                                else
                                    $Couvertures = $Data['Couverturer'] + $Data['Couverture_ia'];
                                if ($Data['Couverturer_nuit'] or $Data['Couverture_nuit_ia']) {
                                    $Couvertures_nuit = $Data['Couverturer_nuit'] + $Data['Couverture_nuit_ia'];
                                    $Couvertures_txt = $Couvertures . " (jour) + " . $Couvertures_nuit . " (nuit)";
                                } else
                                    $Couvertures_txt = $Couvertures;
                                $Demandes = GetMissionType($Data['Demandes']);
                                $Missions_txt .= "<tr><td>" . $Couvertures_txt . "</td><td>" . $Escortes . "</td><td>" . $Recce . "</td><td>" . $Cible_i . "</td></tr>";
                            }
                            $Tasks_Reco = $Data['Tasks_Reco'];
                        }
                        mysqli_free_result($result_mi);
                        unset($Data);
                    }
                    function Alt_Range($Alt, $Premium, $Meteo, $Nuit = false)
                    {
                        if ($Premium) {
                            if ($Nuit) {
                                $Alt_max = $Alt + 1000 + ($Meteo * 2);
                                $Alt_min = $Alt - 2000 - ($Meteo * 2);
                            } else {
                                $Alt_max = $Alt + 1500 + ($Meteo * 2);
                                $Alt_min = $Alt - 3000 - ($Meteo * 2);
                            }
                            if ($Alt_min < 500) $Alt_min = 500;
                            if ($Alt_max > 12000) $Alt_min = 12000;
                            return $Alt_min . 'm <i>à</i> ' . $Alt_max . 'm';
                        } else
                            return $Alt . 'm';
                    }

                    if ($Escortes > 0) {
                        $height_re = 0;
                        $result = mysqli_query($con, $query_e);
                        if ($result) {
                            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                if ($data['Endurance'] > 8)
                                    $fatigue_icon = '<img src="images/fatigue9.png" title="Très fatigué">';
                                elseif ($data['Endurance'] > 5)
                                    $fatigue_icon = '<img src="images/fatigue6.png" title="Fatigué">';
                                elseif ($data['Endurance'] > 2)
                                    $fatigue_icon = '<img src="images/fatigue3.png" title="Légèrement fatigué">';
                                else
                                    $fatigue_icon = '<img src="images/fatigue0.png" title="En pleine forme">';
                                $Recce_EM_e .= "<tr><th>" . $data['Nom'] . $fatigue_icon . " " . GetAvionIcon($data['Avion'], $data['Pays'], $data['ID'], $data['Unit'], $Front_Lieu) . "</th><td>" . Afficher_Icone($data['Unit'], $data['Pays']) . "</td><td>" . Alt_Range($data['Alt'], $Premium, $Meteo) . "</td></tr>";
                                $height_re += 75;
                            }
                            mysqli_free_result($result);
                            if ($height_re > 150) $height_re = 150;
                            if ($Recce_EM_e)
                                $Recce_EM2 .= "<div style='overflow:auto; height:" . $height_re . "px;'><table class='table'><tr><td colspan='10'>" . $Escortes . " chasseurs en escorte sur votre cible</td></tr>" . $Recce_EM_e . "</table></div>";
                        }
                    }
                    if ($Couvertures > 0) {
                        $height_re = 0;
                        $result2 = mysqli_query($con, $query_c);
                        if ($result2) {
                            while ($data = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                                if ($data['Endurance'] > 8)
                                    $fatigue_icon = '<img src="images/fatigue9.png" title="Très fatigué">';
                                elseif ($data['Endurance'] > 5)
                                    $fatigue_icon = '<img src="images/fatigue6.png" title="Fatigué">';
                                elseif ($data['Endurance'] > 2)
                                    $fatigue_icon = '<img src="images/fatigue3.png" title="Légèrement fatigué">';
                                else
                                    $fatigue_icon = '<img src="images/fatigue0.png" title="En pleine forme">';
                                $Recce_EM_c .= "<tr><th>" . $data['Nom'] . $fatigue_icon . " " . GetAvionIcon($data['Avion'], $data['Pays'], $data['ID'], $data['Unit'], $Front_Lieu) . "</th><td>" . Afficher_Icone($data['Unit'], $data['Pays']) . "</td><td>" . Alt_Range($data['Alt'], $Premium, $Meteo) . "</td></tr>";
                                $height_re += 75;
                            }
                            mysqli_free_result($result2);
                            if ($height_re > 150) $height_re = 150;
                            if ($Recce_EM_c)
                                $Recce_EM1 .= "<div style='overflow:auto; height:" . $height_re . "px;'><table class='table'><tr><td colspan='10'>" . $Couvertures . " chasseurs en couverture sur votre cible</td></tr>" . $Recce_EM_c . "</table></div>";
                        }
                    }
                    if ($Couvertures_nuit > 0) {
                        $height_re = 0;
                        $result4 = mysqli_query($con, $query_n);
                        if ($result4) {
                            while ($data = mysqli_fetch_array($result4, MYSQLI_ASSOC)) {
                                $Recce_EM_n .= "<tr><th>" . $data['Nom'] . " " . GetAvionIcon($data['Avion'], $data['Pays'], $data['ID'], $data['Unit'], $Front_Lieu) . "</th><td>" . Afficher_Icone($data['Unit'], $data['Pays']) . "</td><td>" . Alt_Range($data['Alt'], $Premium, $Meteo, true) . "</td></tr>";
                                $height_re += 75;
                            }
                            mysqli_free_result($result4);
                            if ($height_re > 150) $height_re = 150;
                            if ($Recce_EM_n)
                                $Recce_EM1 .= "<div style='overflow:auto; height: " . $height_re . "px;'><table class='table'><tr><td colspan='10'>" . $Couvertures_nuit . " chasseurs en couverture de nuit sur votre cible</td></tr>" . $Recce_EM_n . "</table></div>";
                        }
                    }
                    if ($Tasks_Reco > 0) {
                        $height_re = 0;
                        //$con=dbconnecti();
                        $result3 = mysqli_query($con, $query_d);
                        //mysqli_close($con);
                        if ($result3) {
                            while ($data = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
                                $Recce_EM_d .= "<tr><th>" . $data['Nom'] . " " . GetAvionIcon($data['Avion'], $data['Pays'], $data['ID'], $data['Unit'], $Front_Lieu) . "</th><td>" . Afficher_Icone($data['Unit'], $data['Pays']) . "</td><td>" . GetTask($data['Task']) . "</td></tr>";
                                $height_re += 100;
                            }
                            mysqli_free_result($result3);
                            if ($height_re > 150) $height_re = 150;
                            if ($Recce_EM_d)
                                $Recce_EM3 .= "<div style='overflow:auto; height:" . $height_re . "px;'><table class='table'><tr><td colspan='10'>" . $Tasks_Reco . " avions de reco en mission</td></tr>" . $Recce_EM_d . "</table></div>";
                        }
                    }
                    $Recce_EM = "<div class='row'><div class='col-lg-4 col-md-6 col-sm-12'>" . $Recce_EM1 . "</div><div class='col-lg-4 col-md-6 col-sm-12'>" . $Recce_EM2 . "</div><div class='col-lg-4 col-md-6 col-sm-12'>" . $Recce_EM3 . "</div></div>";
                }
            }
            //Output
            require_once 'em/archives_ville.php';
            $Archives_txt = Output::viewModal('em-archives-ville', 'Archives', $modal_txt).Output::linkModal('em-archives-ville', 'Archives Lieu', 'btn btn-primary');
            $Admin_txt = "Long=" . $Long . " / Lat=" . $Lat . " / Mines=" . $Mines_m . " / Reco=" . $Recce . " (" . $Recce_PlayerID . " - " . $Recce_PlayerID_TAL . " - " . $Recce_PlayerID_TAX . ")<br>
                        ".$Archives_txt."
                        <a href='em_city_combats.php?id=" . $Cible . "' target='_blank' class='btn btn-primary'>Archives des combats</a> <a href='em_city_dca.php?id=" . $Cible . "' target='_blank' class='btn btn-warning'>DCA</a>
                        <a href='admin/admin_city_reveal.php?id=" . $Cible . "&f=1' class='btn btn-danger'>Reveal Axe</a>
                        <a href='admin/admin_city_reveal.php?id=" . $Cible . "&f=2' class='btn btn-danger'>Reveal Allies</a>
                        <a href='admin/admin_city_recce.php?id=" . $Cible . "' class='btn btn-danger'>Recce</a>
                        <a href='admin/admin_city_meteo.php?id=" . $Cible . "&m=1' class='btn btn-danger'>Meteo+</a>
                        <a href='admin/admin_city_meteo.php?id=" . $Cible . "&m=2' class='btn btn-danger'>Meteo-</a>
                        ";
            if (!$dem_txt) $dem_txt = "<tr><td colspan='5'>Aucune demande actuellement</td></tr>";
            $Demandes_txt = "<h3>Demandes de mission en cours</h3><table class='table table-striped'><thead><tr>
						<th>Mission demandée</th>
						<th>Unité demandeuse</th>
						<th>Status Reco</th></tr></thead>" . $dem_txt . "</table>";
            //Escortes et couvertures en cours
            require_once 'help/aide_missions_liste.php';
            $Missions_txt = Output::viewModal('help-aide-missions-liste', 'aide', $modal_txt)."<h3>Missions en cours ".Output::linkModal('help-aide-missions-liste', '<img src="images/help.png">')."</h3>
						<table class='table table-striped'><thead><tr>
							<th>Couvertures</th>
							<th>Escortes</th>
							<th>Reco</th>
							<th>Cibles</th>
						</tr></thead>";
            $Missions_txt .= '</table>';
            $mes .= $header . "<h1>" . $Cible_nom . "</h1>" . $toolbar . "<div class='row'><div class='col-md-6 col-sm-12'><img src='" . $img_gen . "' title='" . $Cible_nom . "' style='width:100%;'></div>
			<div class='col-md-6 col-sm-12'><table class='table table-800'><thead><tr><th>Territoire</th><th>Revendication</th><th>Valeur stratégique</th><th>Terrain</th><th>Météo</th></tr></thead>
			<tr><td><a href='#' class='popup'><img src='images/flag" . $Pays_Ori . "p.jpg'><span><b>" . GetPays($Pays_Ori) . "</b>. Nation contrôlant le lieu au début de la partie.</span></a></td><td>" . $Rev . "</td><td><a href='#' class='popup'>" . $Valstrat_icon . "<span>Valeur ajoutée quotidiennement au score de victoire de la faction qui le contrôle.</span></a></td><td><img src='images/zone" . $Zone . ".jpg' title='" . $Region . "'>" . $Noeud_txt . $Plage_txt . "</td>
			<td>" . $Meteo_txt . "</td></tr></table>" . $list_voyage . "</div></div>";
            if ($Zone != 6) {
                require_once 'help/aide_garnison.php';
                $mes .= Output::viewModal('help-aide-garnison', 'aide', $modal_txt).
                    "<table class='table'><thead><tr><th>DCA</th><th>Garnison ".Output::linkModal('help-aide-garnison', '<img src="images/help.png">')."</th><th>Fortification</th><th>Infrastructures</th></tr></thead>
				    <tr><td>" . $dca . "</td><td>" . $Garnison . "</td><td>" . $Fortification . "</td><td><table><tr>" . $icones . "</tr><tr>" . $iconebase . "</tr></table></td></tr></table>";
            }
            if ($Print_detail)
                $mes .= $Admin_txt . "<h2>Missions aériennes</h2>" . $Demandes_txt . $Missions_txt . $Recce_EM;
            if ($air_units) {
                $mes .= "<h2>Unités aériennes présentes à " . $Cible_nom . "</h2><table class='table table-striped'>
					<thead><tr><th>Unité</th><th width='50px'>Nation</th><th>Commandant</th><th>Défense</th><th>Avions</th></tr></thead>
					" . $air_units . "</table>";
                if ($air_units_enc > $ValeurStrat + 1) $mes .= "<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Cet aérodrome a atteint sa capacité maximale et ne peut plus accueillir de nouvelle escadrille</div>";
            }
            if ($units_allies xor $units_axe) {
                $mes .= "<h2>Troupes présentes à " . $Cible_nom . "</h2><table class='table table-hover table-800'><thead><tr><th>Unité</th><th width='50px'>Nation</th><th>Commandant</th><th width='50px'>Effectifs</th><th>Troupes</th></tr></thead>
				" . $units_axe . $units_allies . "</table>";
            } elseif ($units_allies or $units_axe) {
                if ($Faction == 2) {
                    $units_a = $units_allies;
                    $units_b = $units_axe;
                } else {
                    $units_a = $units_axe;
                    $units_b = $units_allies;
                }
                $mes .= "<h2>Troupes présentes à " . $Cible_nom . "</h2><div class='row'><div class='col-md-6'><table class='table table-hover table-800'>
					<thead><tr><th>Unité</th><th width='50px'>Nation</th><th>Commandant</th><th width='50px'>Effectifs</th><th>Troupes</th></tr></thead>
					" . $units_a . "</table></div>
					<div class='col-md-6'><table class='table table-hover table-800'>
					<thead><tr><th>Unité</th><th width='50px'>Nation</th><th>Commandant</th><th width='50px'>Effectifs</th><th>Troupes</th></tr></thead>
					" . $units_b . "</table></div></div>";
            }
            $mes .= $menu . $dca_pieces . $usine_txt . $depot . $footer;
            if ($Map_output) {
                include_once './new_header.php';
                echo '<div>' . $mes . '</div>';
                include_once './new_footer.php';
            } else
                echo $mes;
        } else
            echo 'Tsss!';
    } else
        echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}