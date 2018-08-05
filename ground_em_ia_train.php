<?php
/**
 * User: JF
 * Date: 28-07-18
 * Time: 12:03
 */

if ($ground_em_ia_train == true) {
    $Auto_train = 500; //Autonomie Trains
    $Divisions = 'Etat-Major';
    $Placements = 'Gare';
    $Positions = 'En position';
    if (!$Regiment->Move and ($Ordres_Cdt or $Ordres_Adjoint or $Ordres_Log) and $Regiment->Lieu_ID and $Lieu->NoeudF > 10) {
        $CT_front = 0;
        $Lieu->Stock_field = false;
        if ($Regiment->Fret) {
            if ($Regiment->Fret == 1001)
                $Lieu->Stock_field = ',Stock_Essence_1';
            elseif ($Regiment->Fret == 1087)
                $Lieu->Stock_field = ',Stock_Essence_87';
            elseif ($Regiment->Fret == 1100)
                $Lieu->Stock_field = ',Stock_Essence_100';
            elseif ($Regiment->Fret == 930)
                $Lieu->Stock_field = ',Stock_Bombes_30';
            elseif ($Regiment->Fret == 80)
                $Lieu->Stock_field = ',Stock_Bombes_80';
            elseif ($Regiment->Fret == 300)
                $Lieu->Stock_field = ',Stock_Bombes_300';
            elseif ($Regiment->Fret == 400)
                $Lieu->Stock_field = ',Stock_Bombes_400';
            elseif ($Regiment->Fret == 800)
                $Lieu->Stock_field = ',Stock_Bombes_800';
            elseif ($Regiment->Fret == 1200)
                $Lieu->Stock_field = ',Stock_Munitions_200';
            elseif ($Regiment->Fret == 9050 or $Regiment->Fret == 9125 or $Regiment->Fret == 9250 or $Regiment->Fret == 9500)
                $Lieu->Stock_field0 = 'Stock_Bombes_' . substr($Regiment->Fret, 1);
            elseif ($Regiment->Fret > 9999)
                $Lieu->Stock_field0 = 'Stock_Bombes_' . substr($Regiment->Fret, 0, -1);
            else
                $Lieu->Stock_field0 = 'Stock_Munitions_' . $Regiment->Fret;
            if (!$Lieu->Stock_field) $Lieu->Stock_field = ',' . $Lieu->Stock_field0;
        }
        if ($Regiment->Lieu_ID == 2306 or $Regiment->Lieu_ID == 2307) //Corse
            $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE ID IN (2306,2307) AND ID<>'$Regiment->Lieu_ID' ORDER BY Nom ASC";
        elseif ($Regiment->Front == 2) {
            $Auto_train = 250;
            if ($country == 4) {
                if ($Lieu->Longitude > 35)
                    $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude <36.5 AND Longitude >35 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                else
                    $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude <37.3 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
            } elseif ($Lieu->Longitude > 44) {
                $Auto_train = 500;
                $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude <44 AND Longitude <50 AND Zone<>6 AND Flag=" . $Lieu->Pays . " AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID') ORDER BY Nom ASC";
            } elseif ($Lieu->Latitude > 36.5 and $Lieu->Longitude > 19) //Grèce
            {
                if ($country == 2)
                    $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude >36.5 AND Latitude <42 AND Longitude >19 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                else {
                    if (GetData("Lieu", "ID", 1219, "Flag") != 2)
                        $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude >36.5 AND Latitude <43.5 AND Longitude >19 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                    else
                        $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude >39 AND Latitude <43.5 AND Longitude >19 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                }
            } elseif ($Lieu->Latitude < 33) //AFN
            {
                if ($country == 2) {
                    if (GetData("Lieu", "ID", 889, "Flag") != 2 and $Lieu->Longitude > 25)
                        $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude < 33 AND Longitude >25 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                    else
                        $query = "SELECT l.ID,l.Nom,l.Longitude,l.Latitude,l.NoeudR,l.Impass,l.ValeurStrat FROM Lieu as l WHERE l.Latitude <33 AND l.Longitude >10 AND Zone<>6 AND l.Flag IN (" . $Allies . ") AND l.NoeudF >10 AND l.ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                } else {
                    if (GetData("Lieu", "ID", 889, "Flag") == 2 and $Lieu->Longitude < 25.2)
                        $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude <33 AND Longitude >10 AND Longitude <25.2 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                    else
                        $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude <33 AND Longitude >10 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                }
            } else
                $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Latitude <46 AND Longitude <50 AND Zone<>6 AND Flag=" . $Lieu->Pays . " AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',343,436,2306,2307) ORDER BY Nom ASC";
        } elseif ($Regiment->Front == 1)
            $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE (Latitude BETWEEN 41 AND 52) AND (Longitude BETWEEN 13 AND 52) AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',1252) ORDER BY Nom ASC";
        elseif ($Regiment->Front == 4 or $Regiment->Front == 5)
            $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE (Latitude BETWEEN 50.4 AND 70) AND (Longitude BETWEEN 13 AND 65) AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',1252) ORDER BY Nom ASC";
        elseif ($Regiment->Front == 3) {
            if ($country == 7)
                $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Longitude >235 AND Zone<>6 AND Flag=7 AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID') ORDER BY Nom ASC";
            elseif ($country == 2)
                $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE (Latitude BETWEEN 9.6 AND 32) AND (Longitude BETWEEN 67 AND 97) AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID') ORDER BY Nom ASC";
            else
                $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE ID='$Regiment->Lieu_ID'";
        } else {
            if ($Lieu->Pays == 1 or $Lieu->Pays == 3 or $Lieu->Pays == 4 or $Lieu->Pays == 5 or $Lieu->Pays == 6 or $Lieu->Pays == 36) {
                $Auto_train = 250;
                $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Pays<>2 AND Latitude >41 AND Longitude <14 AND Zone<>6 AND Flag IN (" . $Allies . ") AND NoeudF >10 AND ID NOT IN ('$Regiment->Lieu_ID',704,896) ORDER BY Nom ASC";
            } elseif ($Lieu->Pays == 2) {
                $Auto_train = 250;
                $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Pays=" . $Lieu->Pays . " AND Latitude >49 AND Longitude <14 AND Zone<>6 AND Flag=" . $Lieu->Pays . " AND NoeudF >10 AND ID<>'$Regiment->Lieu_ID' ORDER BY Nom ASC";
            } elseif ($Lieu->Pays == 7)
                $query = "SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat" . $Lieu->Stock_field . " FROM Lieu WHERE Pays=" . $Lieu->Pays . " AND Latitude >30 AND Zone<>6 AND Flag=" . $Lieu->Pays . " AND NoeudF >10 AND ID<>'$Regiment->Lieu_ID' ORDER BY Nom ASC";
        }
        $con = dbconnecti();
        $result = mysqli_query($con, $query);
        mysqli_close($con);
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_NUM)) {
                //$Battle=$data[1];
                $lieux_obj .= '<option value="' . $data[0] . '">' . $data[1] . '</option>';
                $coord = 0;
                $CT_front = 0;
                $Distance = GetDistance(0, 0, $Lieu->Longitude, $Lieu->Latitude, $data[2], $data[3]);
                if ($Distance[0] < $Auto_train) {
                    $Impass = $data[5];
                    $sensh = '';
                    $sensv = '';
                    $depot_icon = false;
                    if ($Lieu->Longitude > $data[2]) {
                        $sensh = 'Ouest';
                        $coord += 2;
                        if ($Impass == 2 or $Impass == 3 or $Impass == 4 or $Lieu->Impass == 6 or $Lieu->Impass == 7 or $Lieu->Impass == 8)
                            $CT_front = 4;
                    } elseif ($Lieu->Longitude < $data[2]) {
                        $sensh = 'Est';
                        $coord += 1;
                        if ($Impass == 6 or $Impass == 7 or $Impass == 8 or $Lieu->Impass == 2 or $Lieu->Impass == 3 or $Lieu->Impass == 4)
                            $CT_front = 4;
                    }
                    if ($sensh) {
                        if ($Lieu->Latitude > $data[3] + 0.5) {
                            $sensv = 'Sud';
                            $coord += 20;
                            if ($Impass == 1 or $Impass == 2 or $Impass == 8 or $Lieu->Impass == 4 or $Lieu->Impass == 5 or $Lieu->Impass == 6)
                                $CT_front = 4;
                        } elseif ($Lieu->Latitude < $data[3] - 0.5) {
                            $sensv = 'Nord';
                            $coord += 10;
                            if ($Impass == 4 or $Impass == 5 or $Impass == 6 or $Lieu->Impass == 1 or $Lieu->Impass == 2 or $Lieu->Impass == 8)
                                $CT_front = 4;
                        }
                    } else {
                        if ($Lieu->Latitude > $data[3]) {
                            $sensv = 'Sud';
                            $coord += 20;
                            if ($Impass == 1 or $Impass == 2 or $Impass == 8 or $Lieu->Impass == 4 or $Lieu->Impass == 5 or $Lieu->Impass == 6)
                                $CT_front = 4;
                        } elseif ($Lieu->Latitude < $data[3]) {
                            $sensv = 'Nord';
                            $coord += 10;
                            if ($Impass == 4 or $Impass == 5 or $Impass == 6 or $Lieu->Impass == 1 or $Lieu->Impass == 2 or $Lieu->Impass == 8)
                                $CT_front = 4;
                        }
                    }
                    $sens = $sensv . ' ' . $sensh;
                    if ($data[6] > 3) {
                        $sens .= ' - Dépôt';
                        $depot_icon = "<img src='images/depot_icon.png' title='" . $data[7] . "'>";
                    }
                    if ($Admin)
                        $skills .= '<br>Stock à ' . $data[1] . ' : ' . $data[7] . ' ' . $Lieu->Stock_field . ' ' . $Regiment->Fret;
                    if (!$CT_front) {
                        $modal_conso = '<p>Le déplacement rendra l\'unité inaccessible pendant 24h';
                        $choix = "<tr><td><a href='#' class='lien' data-toggle='modal' data-target='#modal-dest-" . $data[0] . "'>" . $data[1] . "</a></td><td>" . $depot_icon . "</td><td>" . $Distance[0] . "km</td></tr>";
                        $lieux_modal .= '<div class="modal fade" id="modal-dest-' . $data[0] . '" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title">Déplacement
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </h2>
                                                                </div>
                                                                <div class="modal-body">
                                                                Le train se déplacera vers <b>' . $data[1] . '</b>
                                                                <form action="index.php?view=ground_em_ia_go" method="post"><input type="hidden" name="Unit" value="' . $Unit . '"><input type="hidden" name="base" value="' . $Regiment->Lieu_ID . '"><input type="hidden" name="cible" value="' . $data[0] . '"><input class="btn btn-danger" type="submit" value="confirmer"></form>' . $modal_conso . '</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';
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
            mysqli_free_result($result);
        }
    }
//Depot
    $Faction_Gare = GetData("Pays", "ID", $Lieu->Flag_Gare, "Faction");
    if ($Lieu->ValeurStrat > 3 and $Faction == $Faction_Flag and $Faction == $Faction_Gare and $Regiment->Vehicule_Nbr > 0) {
        $depot_info = "<h3>Dépôt de " . $Lieu->Nom . "</h3><div style='overflow:auto;'><table class='table'>
                        <thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
                        <th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th></tr></thead>
                        <tr><td>" . $Lieu->Stock_Essence_87 . "</td><td>" . $Lieu->Stock_Essence_100 . "</td><td>" . $Lieu->Stock_Essence_1 . "</td><td>" . $Lieu->Stock_Munitions_8 . "</td><td>" . $Lieu->Stock_Munitions_13 . "</td>
                        <td>" . $Lieu->Stock_Munitions_20 . "</td><td>" . $Lieu->Stock_Munitions_30 . "</td><td>" . $Lieu->Stock_Munitions_40 . "</td><td>" . $Lieu->Stock_Munitions_50 . "</td><td>" . $Lieu->Stock_Munitions_60 . "</td>
                        <td>" . $Lieu->Stock_Munitions_75 . "</td><td>" . $Lieu->Stock_Munitions_90 . "</td><td>" . $Lieu->Stock_Munitions_105 . "</td><td>" . $Lieu->Stock_Munitions_125 . "</td><td>" . $Lieu->Stock_Munitions_150 . "</td>
                        </tr></table></div>";
        if ($Lieu->Stock_Munitions_8 > 100000)
            $Fret_options .= "<option value='8'>100000 cartouches de 8mm</option>";
        if ($Lieu->Stock_Munitions_13 > 50000)
            $Fret_options .= "<option value='13'>50000 cartouches de 13mm</option>";
        if ($Lieu->Stock_Munitions_20 > 20000)
            $Fret_options .= "<option value='20'>20000 obus de 20mm</option>";
        if ($Lieu->Stock_Munitions_30 > 10000)
            $Fret_options .= "<option value='30'>10000 obus de 30mm</option>";
        if ($Lieu->Stock_Munitions_40 > 5000)
            $Fret_options .= "<option value='40'>5000 obus de 40mm</option>";
        if ($Lieu->Stock_Munitions_50 > 3000)
            $Fret_options .= "<option value='50'>3000 obus de 50mm</option>";
        if ($Lieu->Stock_Munitions_60 > 2000)
            $Fret_options .= "<option value='60'>2000 obus de 60mm</option>";
        if ($Lieu->Stock_Munitions_75 > 1500)
            $Fret_options .= "<option value='75'>1500 obus de 75mm</option>";
        if ($Lieu->Stock_Munitions_90 > 1000)
            $Fret_options .= "<option value='90'>1000 obus de 90mm</option>";
        if ($Lieu->Stock_Munitions_105 > 750)
            $Fret_options .= "<option value='105'>750 obus de 105mm</option>";
        if ($Lieu->Stock_Munitions_125 > 500)
            $Fret_options .= "<option value='125'>500 obus de 125mm</option>";
        if ($Lieu->Stock_Munitions_150 > 200)
            $Fret_options .= "<option value='150'>200 obus de 150mm</option>";
        if ($Lieu->Stock_Bombes_50 > 2000)
            $Fret_options .= "<option value='9050'>2000 bombes de 50kg</option>";
        if ($Lieu->Stock_Bombes_125 > 1000)
            $Fret_options .= "<option value='9125'>1000 bombes de 125kg</option>";
        if ($Lieu->Stock_Bombes_250 > 500)
            $Fret_options .= "<option value='9250'>500 bombes de 250kg</option>";
        if ($Lieu->Stock_Bombes_500 > 200)
            $Fret_options .= "<option value='9500'>200 bombes de 500kg</option>";
        if ($Lieu->Stock_Bombes_1000 > 100)
            $Fret_options .= "<option value='10000'>100 bombes de 1000kg</option>";
        if ($Lieu->Stock_Bombes_2000 > 50)
            $Fret_options .= "<option value='11000'>50 bombes de 2000kg</option>";
        if ($Lieu->Stock_Bombes_300 > 250)
            $Fret_options .= "<option value='300'>250 charges de profondeur</option>";
        if ($Lieu->Stock_Bombes_400 > 250)
            $Fret_options .= "<option value='400'>250 mines</option>";
        if ($Lieu->Stock_Bombes_80 > 1000)
            $Fret_options .= "<option value='80'>1000 rockets</option>";
        if ($Lieu->Stock_Bombes_800 > 100)
            $Fret_options .= "<option value='800'>100 torpilles</option>";
        if ($Lieu->Stock_Bombes_30 > 10000)
            $Fret_options .= "<option value='930'>10000 fusées éclairantes</option>";
        if ($Lieu->Stock_Essence_87 > 50000)
            $Fret_options .= "<option value='1087'>50000L Essence 87 Octane</option>";
        if ($Lieu->Stock_Essence_100 > 50000)
            $Fret_options .= "<option value='1100'>50000L Essence 100 Octane</option>";
        if ($Lieu->Stock_Essence_1 > 50000)
            $Fret_options .= "<option value='1001'>50000L de Diesel</option>";
    } elseif ($Admin)
        $skills = "Faction=" . $Faction . " / Faction_Flag=" . $Faction_Flag . " / Faction_Gare=" . $Faction_Gare;
    $Conso_txt = "<span class='label label-default' title='Charge possible'>" . $Charge . "kg</span>";
    $Atk_Options = "<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='" . $Unit . "'><input type='hidden' name='base' value='" . $Regiment->Lieu_ID . "'>
                <select name='fret' class='form-control' style='max-width:200px;'><option value='0'>Ne rien charger</option>" . $Fret_options . "</select><input type='submit' value='Charger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td>0</td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une quantité minimale est nécessaire dans le dépôt pour pouvoir transporter du fret</span></a></td></tr>";
    $Pos_titre = 'Fret';
    if (!$Regiment->Fret)
        $Pos_ori = "Vide";
    elseif ($Regiment->Fret == 1001)
        $Pos_ori = "50000L Diesel";
    elseif ($Regiment->Fret == 1087)
        $Pos_ori = "50000L Essence 87";
    elseif ($Regiment->Fret == 1100)
        $Pos_ori = "50000L Essence 100";
    elseif ($Regiment->Fret == 1)
        $Pos_ori = "Troupes";
    elseif ($Regiment->Fret == 930)
        $Pos_ori = "10000 Fusées";
    elseif ($Regiment->Fret == 80)
        $Pos_ori = "1000 Rockets";
    elseif ($Regiment->Fret == 200)
        $Pos_ori = "Troupes IA";
    elseif ($Regiment->Fret == 300)
        $Pos_ori = "250 Charges";
    elseif ($Regiment->Fret == 400)
        $Pos_ori = "250 Mines";
    elseif ($Regiment->Fret == 800)
        $Pos_ori = "100 Torpilles";
    elseif ($Regiment->Fret == 1200)
        $Pos_ori = "Obus de 200mm";
    elseif ($Regiment->Fret == 9050 or $Regiment->Fret == 9125 or $Regiment->Fret == 9250 or $Regiment->Fret == 9500)
        $Pos_ori = "Bombes de " . substr($Regiment->Fret, 1) . "kg";
    elseif ($Regiment->Fret > 9999)
        $Pos_ori = "Bombes de " . substr($Regiment->Fret, 0, -1) . "kg";
    else
        $Pos_ori = "Obus de " . $Regiment->Fret . "mm";
    if ($Regiment->Fret and $Faction == $Faction_Flag and $Faction == $Faction_Gare and $Lieu->ValeurStrat > 3)
        $Atk_Options .= "<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='" . $Unit . "'><input type='hidden' name='base' value='" . $Regiment->Lieu_ID . "'><input type='hidden' name='Dech' value='" . $Regiment->Fret . "'><input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td>0</td><td>0</td><td>N/A</td><td>N/A</td></tr>";
    if ($Regiment->Vehicule_Nbr < 1 and $Lieu->NoeudF > 0 and $Faction == $Faction_Flag and $Faction == $Faction_Gare)
        $Renforts_txt = '<tr><td>
                                            <form action="index.php?view=ground_em_ia_go" method="post">
                                                <input type="hidden" name="renf" value="3">
                                                <input type="hidden" name="Unit" value="' . $Unit . '">
                                                <input class="btn btn-sm btn-warning" type="submit" value="Réparer">
                                            </form>
                                        </td>
                                        <td><div class="i-flex"><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></div></td>
                                   </tr>';
    else
        $Renforts_txt = '<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Une gare en bon état contrôlée par votre faction est nécessaire pour la réparation</span></a></td></tr>';
}