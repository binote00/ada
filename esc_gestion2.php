<?php
require_once './jfv_inc_sessions.php';
include_once './jfv_include.inc.php';
$Action = Insec($_POST['Action']);
$PlayerID = $_SESSION['PlayerID'];
if (isset($_SESSION['AccountID']) AND $PlayerID > 0 and isset($Action)) {
    $As = Insec($_POST['As']);
    include_once './jfv_avions.inc.php';
    include_once './jfv_txt.inc.php';
    $con = dbconnecti();
    $result = mysqli_query($con, "SELECT Credits,Missions_Max,MIA FROM Pilote WHERE ID='$PlayerID'");
    mysqli_close($con);
    if ($result) {
        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $Credits_ori = $data['Credits'];
            $Missions_Max = $data['Missions_Max'];
            $MIA = $data['MIA'];
        }
        mysqli_free_result($result);
    }
    if (!$PlayerID or $Action < 1 or ($Credits_ori < 1 and $Missions_Max > 5)) {
        $mes = "<p>Vous ne savez pas lire?<br>Quand il n'y en a plus,il n'y en a plus!</p>";
        $menu = "<a class='btn btn-default' title='Retour à l\'escadrille' href='index.php?view=escadrille'>Retour à l'escadrille</a>";
        $img = "<img src='images/tsss.jpg'>";
        $Action = 0;
    } else {
        $country = $_SESSION['country'];
        if (!$MIA and $_SESSION['Distance'] == 0) {
            $con = dbconnecti();
            $result = mysqli_query($con, "SELECT Unit,Front,Avancement,Reputation,Endurance,Moral,Courage,Gestion,Renseignement,Duperie,Ailier,
			Slot1,Slot2,Slot4,Slot7,Slot8,Slot10,Slot11 FROM Pilote WHERE ID='$PlayerID'")
            or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : esc2-player');
            $results = mysqli_query($con, "SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
            //mysqli_close($con);
            if ($results) {
                while ($data = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                    $Skills_Pil[] = $data['Skill'];
                }
                mysqli_free_result($results);
            }
            if ($result) {
                while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Unite = $data['Unit'];
                    $Front = $data['Front'];
                    $Avancement = $data['Avancement'];
                    $Reputation = $data['Reputation'];
                    $Endurance = $data['Endurance'];
                    $Moral = $data['Moral'];
                    $Courage = $data['Courage'];
                    $Renseignement = $data['Renseignement'];
                    $Duperie = $data['Duperie'];
                    $Ailier = $data['Ailier'];
                    $Slot1 = $data['Slot1'];
                    $Slot2 = $data['Slot2'];
                    $Slot4 = $data['Slot4'];
                    $Slot7 = $data['Slot7'];
                    $Slot8 = $data['Slot8'];
                    $Slot10 = $data['Slot10'];
                    $Slot11 = $data['Slot11'];
                }
                mysqli_free_result($result);
                unset($result);
            }
            //$con=dbconnecti();
            $resultu = mysqli_query($con, "SELECT Nom,Base,Type,Commandant,Officier_Adjoint,Avion1,Avion2,Avion3,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'")
            or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : esc2-unit');
            mysqli_close($con);
            if ($resultu) {
                while ($data = mysqli_fetch_array($resultu, MYSQLI_ASSOC)) {
                    $Unite_Nom = $data['Nom'];
                    $Base = $data['Base'];
                    $Unite_Type = $data['Type'];
                    $Commandant = $data['Commandant'];
                    $Officier_Adjoint = $data['Officier_Adjoint'];
                    $Avion1 = $data['Avion1'];
                    $Avion2 = $data['Avion2'];
                    $Avion3 = $data['Avion3'];
                    $Pers1 = $data['Pers1'];
                    $Pers2 = $data['Pers2'];
                    $Pers3 = $data['Pers3'];
                    $Pers4 = $data['Pers4'];
                    $Pers5 = $data['Pers5'];
                    $Pers6 = $data['Pers6'];
                    $Pers7 = $data['Pers7'];
                    $Pers8 = $data['Pers8'];
                    $Pers9 = $data['Pers9'];
                    $Pers10 = $data['Pers10'];
                }
                mysqli_free_result($resultu);
            }
            $Pers = array($Pers1, $Pers2, $Pers3, $Pers4, $Pers5, $Pers6, $Pers7, $Pers8, $Pers9, $Pers10);
            $Personnel = array_count_values($Pers);
            $Grade_a = GetAvancement($Avancement, $country);
            SetData("Pilote", "S_Avancement_mission", $Grade_a[1], "ID", $PlayerID);
            unset($Grade_a);
            $Bonus_Pers = 1;
            if ($Renseignement > 50) $Renseignement = 50;
            if ($Duperie > 50) $Duperie = 50;
            if (is_array($Skills_Pil)) {
                if (in_array(83, $Skills_Pil))
                    $Duperie = 150;
                elseif (in_array(82, $Skills_Pil))
                    $Duperie = 125;
                elseif (in_array(81, $Skills_Pil))
                    $Duperie = 100;
                elseif (in_array(80, $Skills_Pil))
                    $Duperie = 75;
                if (in_array(101, $Skills_Pil))
                    $Pilote_Salon = true;
                elseif (in_array(100, $Skills_Pil))
                    $Assis_Bureau = true;
                if (in_array(99, $Skills_Pil))
                    $Carotteur = true;
                if (in_array(87, $Skills_Pil))
                    $Personnel[11] += 1;
                if (in_array(112, $Skills_Pil))
                    $Indiscret = true;
                if (in_array(130, $Skills_Pil))
                    $Pers_Sup = 1;
                if (in_array(98, $Skills_Pil))
                    $Bonus_Pers = 1.5;
                if (in_array(97, $Skills_Pil))
                    $Favori_General = true;
            }
            $Credits = 0;
            switch ($Action) {
                case 1:
                    $Repa = 10 + (($Personnel[3] + $Pers_Sup) * 10 * $Bonus_Pers);
                    if ($Moral < 100) UpdateCarac($PlayerID, "Moral", $Repa, "Pilote", 100);
                    $mes = "<p>Vous vous sentez nettement mieux!</p>";
                    if (!$Pilote_Salon) $Credits = -1;
                    $img_txt = 'fiesta' . $country;
                    break;
                case 2:
                    if ($Moral < 100) UpdateCarac($PlayerID, "Moral", 15, "Pilote", 100);
                    UpdateCarac($PlayerID, "Reputation", -1);
                    $mes = "<p>Il y a pas à dire,ça fait du bien!</p>";
                    if (!$Pilote_Salon) $Credits = -1;
                    $img_txt = 'fiesta' . $country;
                    break;
                case 3:
                    if ($Moral < 100) UpdateCarac($PlayerID, "Moral", 20, "Pilote", 100);
                    UpdateCarac($PlayerID, "Avancement", -2);
                    $mes = "<p>Il y a pas à dire,ça fait du bien!</p>";
                    if (!$Pilote_Salon) $Credits = -1;
                    $img_txt = 'transfer_no' . $country;
                    break;
                case 4:
                    if ($Courage < 100) UpdateCarac($PlayerID, "Courage", 10, "Pilote", 100);
                    $mes = "<p>Wouaouh! Vous voilà remonté à bloc!</p>";
                    if (!$Pilote_Salon) $Credits = -1;
                    $img_txt = 'speed' . $country;
                    break;
                case 5:
                    $Repa = 5 + (($Personnel[12] + $Pers_Sup) * 5 * $Bonus_Pers);
                    UpdateData("Lieu", "QualitePiste", $Repa, "ID", $Base, 100);
                    $Credits = -4;
                    $img_txt = 'gestion_piste' . $country;
                    break;
                case 6:
                    $Repa = 10 + (($Personnel[7] + $Pers_Sup) * 10 * $Bonus_Pers);
                    $mes .= "<br>Ces quelques heures de repos vous permettent de récupérer un peu de vos blessures.";
                    $img_txt = "repos";
                    UpdateCarac($PlayerID, "Endurance", 1, "Pilote", 10);
                    if ($Moral < 100) UpdateCarac($PlayerID, "Moral", $Repa, "Pilote", 100);
                    if ($Courage < 100) UpdateCarac($PlayerID, "Courage", $Repa, "Pilote", 100);
                    if (!$Pilote_Salon) {
                        if ($Assis_Bureau)
                            $Credits = -1;
                        else
                            $Credits = -2;
                    }
                    break;
                case 7:
                    if ($Avancement > 4999) {
                        $Date_Campagne = GetData("Conf_Update", "ID", 2, "Date");
                        if (IsAllie($country)) {
                            if ($Date_Campagne > "1943-01-01" and $Front != 3)
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (1,6,15,18) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            elseif ($Date_Campagne > "1941-12-06") {
                                if ($Front == 3)
                                    $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays=9 AND Zone<>6 ORDER BY RAND() LIMIT 1";
                                else
                                    $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (1,4,6,15,18) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            } elseif ($Date_Campagne > "1941-06-21")
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (1,4,6,15,18) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            elseif ($Date_Campagne > "1940-06-22")
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (1,4,6) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            elseif ($Date_Campagne > "1940-06-09")
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (1,6) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            else
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays='1' AND Zone<>6 ORDER BY RAND() LIMIT 1";
                        } else {
                            if ($Date_Campagne > "1943-01-01" and $Front != 3)
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (2,5,7,8,20) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            elseif ($Date_Campagne > "1941-12-06") {
                                if ($Front == 3)
                                    $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (2,5,7) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                                else
                                    $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (2,5,7,8,20) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            } elseif ($Date_Campagne > "1941-06-21")
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (2,8,20) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            elseif ($Date_Campagne > "1940-06-22")
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays=2 AND Zone<>6 ORDER BY RAND() LIMIT 1";
                            else
                                $query_pri = "SELECT DISTINCT * FROM Lieu WHERE Pays IN (2,3,4,5) AND Zone<>6 ORDER BY RAND() LIMIT 1";
                        }
                        $con = dbconnecti();
                        $result_pri = mysqli_query($con, $query_pri);
                        mysqli_close($con);
                        if ($result_pri) {
                            $data_pri = mysqli_fetch_array($result_pri, MYSQLI_ASSOC);
                            $Ville = $data_pri['Nom'];
                            $Ville_Pays = $data_pri['Pays'];
                            $DefenseAA_pri = $data_pri['DefenseAA'];
                            $BaseAerienne_pri = $data_pri['BaseAerienne'];
                            $Industrie_pri = $data_pri['Industrie'];
                            $NoeudF_pri = $data_pri['NoeudF'];
                            $NoeudR_pri = $data_pri['NoeudR'];
                            $Pont_pri = $data_pri['Pont'];
                            $Radar_pri = $data_pri['Radar'];
                        }
                        unset($data_pri);
                        $mensonge = mt_rand(0, 10);
                        if ($mensonge < 5) {
                            $DefenseAA_pri = mt_rand(0, 5);
                            $Troupes_pri = 0;
                            $BaseAerienne_pri = mt_rand(0, 1);
                            $Industrie_pri = mt_rand(0, 1);
                            $NoeudF_pri = mt_rand(0, 1);
                            $NoeudR_pri = mt_rand(0, 1);
                            $Pont_pri = mt_rand(0, 1);
                            $Radar_pri = mt_rand(0, 1);
                        }
                        $Interro = $Renseignement + (mt_rand(1, 100) * ($Personnel[11] + $Pers_Sup) * $Bonus_Pers);
                        $Reponse = "Le prisonnier est originaire de " . $Ville . "<p>Après l'avoir cuisiné un peu ";
                        if ($Interro > 30 and $mensonge > 4) $Reponse .= 'il vous révèle que :';
                        if ($Interro > 150) {
                            if ($mensonge < 5)
                                $Reponse .= "<p>Vous êtes convaincu que ce prisonnier vous ment!</p>";
                            if ($Radar_pri)
                                $Reponse .= "<br>Il y a un radar dans les environs";
                            /*Matos random
							$con=dbconnecti(1);
							$Item=mysqli_result(mysqli_query($con,"SELECT ID FROM Matos WHERE Pays IN (0,'$Ville_Pays') AND Reput_mini <='$Reputation' ORDER BY RAND() LIMIT 1"),0);
							mysqli_close($con);
							$Reponse.="<p>Vous confisquez un objet qui pourrait être utile. Il est ajouté à l'équipement de votre unité.</p>";
							AddToCoffre($Unite,$Item);*/
                        } elseif ($Interro > 120 and $mensonge < 5)
                            $Reponse .= "<p>Vous avez l'impression que ce prisonnier vous ment!</p>";
                        if ($Interro > 100) {
                            switch ($DefenseAA_pri) {
                                case 0:
                                    $DCA_txt = "Inexistante";
                                    break;
                                case 1:
                                    $DCA_txt = "composée de Mitrailleuses légères";
                                    break;
                                case 2:
                                    $DCA_txt = "composée de Mitrailleuses lourdes";
                                    break;
                                case 3:
                                    $DCA_txt = "composée de Canons de petit calibre";
                                    break;
                                case 4:
                                    $DCA_txt = "composée de Canons de moyen calibre";
                                    break;
                                case 5:
                                    $DCA_txt = "1 batterie de canons de gros calibre";
                                    break;
                                case 6:
                                    $DCA_txt = "2 batteries de canons de gros calibre";
                                    break;
                                case 7:
                                    $DCA_txt = "3 batteries de canons de gros calibre";
                                    break;
                                case 8:
                                    $DCA_txt = "4 batteries de canons de gros calibre";
                                    break;
                                case 9:
                                    $DCA_txt = "5 batteries de canons de gros calibre";
                                    break;
                                case ($DCA > 9):
                                    $DCA_txt = "6 batteries de canons de gros calibre";
                                    break;
                            }
                            $Reponse .= "<br>La défense anti-aérienne est " . $DCA_txt;
                        }
                        if ($Interro > 80) {
                            if ($BaseAerienne_pri)
                                $Reponse .= "<br>Il y a une base aérienne dans les environs";
                        }
                        if ($Interro > 60) {
                            if ($Industrie_pri)
                                $Reponse .= "<br>Une usine importante est située non loin de là";
                        }
                        if ($Interro > 50) {
                            if ($Pont_pri)
                                $Reponse .= "<br>Il y a un pont stratégique dans les environs";
                            if ($NoeudF_pri)
                                $Reponse .= "<br>Une gare importante achemine troupes et ravitaillement dans la région";
                        }
                        if ($Interro > 30) {
                            if ($NoeudR_pri)
                                $Reponse .= "<br>La ville est un noeud routier vital pour la région";
                        }
                        if ($Interro < 31)
                            $Reponse .= "<p>Le prisonnier n'a rien voulu vous dire.</p>";
                        $mes = $Reponse . '</p>';
                        $img_txt = 'prisonnier' . $country;
                        $Credits = -2;
                        //UpdateCarac($PlayerID,"Renseignement",1);
                        //if($Interro >20)UpdateCarac($PlayerID,"Duperie",1);
                    } else {
                        $mes .= "<p>Vous n'êtes pas autorisé à interroger les prisonniers.</p>";
                        $img_txt = 'transfer_no' . $country;
                    }
                    break;
                case 8:
                    $Credits = -1;
                    $mes = "<p>L'armurier vous explique en détails les possibilités d'armement des avions de l'unité.</p>";
                    $garage = "<table class='table'>";
                    for ($u = 1; $u < 4; $u++) {
                        switch ($u) {
                            case 1:
                                $ID_ref = $Avion1;
                                break;
                            case 2:
                                $ID_ref = $Avion2;
                                break;
                            case 3:
                                $ID_ref = $Avion3;
                                break;
                        }
                        $con = dbconnecti();
                        $resulta = mysqli_query($con, "SELECT Nom,ArmePrincipale,Arme1_Nbr,ArmeSecondaire,Arme2_Nbr,Bombe,Bombe_Nbr FROM Avion WHERE ID='$ID_ref'");
                        //mysqli_close($con);
                        if ($result) {
                            while ($data = mysqli_fetch_array($resulta, MYSQLI_ASSOC)) {
                                $Avion_nom = $data['Nom'];
                                $Arme1 = $data['ArmePrincipale'];
                                $Arme1_nbr = $data['Arme1_Nbr'];
                                $Arme2 = $data['ArmeSecondaire'];
                                $Arme2_nbr = $data['Arme2_Nbr'];
                                $Bombe = $data['Bombe'];
                                $Bombe_Nbr = $data['Bombe_Nbr'];
                            }
                            mysqli_free_result($resulta);
                        }
                        //$con=dbconnecti();
                        $result = mysqli_query($con, "SELECT Nom,Calibre,Munitions,Degats,Multi,Enrayage,Portee FROM Armes WHERE ID='$Arme1'");
                        $result2 = mysqli_query($con, "SELECT Nom,Calibre,Munitions,Degats,Multi,Enrayage,Portee FROM Armes WHERE ID='$Arme2'");
                        mysqli_close($con);
                        if ($result) {
                            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $Arme1_nom = $data['Nom'];
                                $Arme1_cal = round($data['Calibre']);
                                $Arme1_chargeur = $data['Munitions'];
                                $Arme1_dg = $data['Degats'];
                                $Arme1_cadence = $data['Multi'] * 60;
                                $Arme1_enrayage = 100 - $data['Enrayage'];
                                $Arme1_portee = $data['Portee'];
                            }
                            mysqli_free_result($result);
                        }
                        if ($result2) {
                            while ($data2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                                $Arme2_nom = $data2['Nom'];
                                $Arme2_cal = round($data2['Calibre']);
                                $Arme2_chargeur = $data2['Munitions'];
                                $Arme2_dg = $data2['Degats'];
                                $Arme2_cadence = $data2['Multi'] * 60;
                                $Arme2_enrayage = 100 - $data2['Enrayage'];
                                $Arme2_portee = $data2['Portee'];
                            }
                            mysqli_free_result($result2);
                        }
                        unset($data);
                        if ($Arme2 == 25 or $Arme2 == 26 or $Arme2 == 27) $Arme2_enrayage = 99;
                        $garage .= "<thead><tr><th colspan='8'>" . $Avion_nom . "</th><tr></thead>
								<tr><td>Arme Principale</td><th>" . $Arme1_nom . "</th><td>" . $Arme1_cal . "mm</td><td>" . $Arme1_chargeur . "</td><td>" . $Arme1_dg . "</td><td>" . $Arme1_portee . "m</td><td>" . $Arme1_cadence . " cp/min</td><td>" . $Arme1_enrayage . "%</td></tr>
								<tr><td>Arme Secondaire</td><th>" . $Arme2_nom . "</th><td>" . $Arme2_cal . "mm</td><td>" . $Arme2_chargeur . "</td><td>" . $Arme2_dg . "</td><td>" . $Arme2_portee . "m</td><td>" . $Arme2_cadence . " cp/min</td><td>" . $Arme2_enrayage . "%</td></tr>
								";
                        if ($Bombe_Nbr > 0) {
                            if ($Bombe == 800) {
                                $Bombe = "533mm";
                                $Arme_nom = "Torpille";
                                $Bombe_Portee = "4km";
                            } elseif ($Bombe == 400) {
                                $Bombe = $Bombe . "kg";
                                $Arme_nom = "Mine";
                                $Bombe_Portee = "N/A";
                            } else {
                                $Bombe = $Bombe . "kg";
                                $Arme_nom = "Bombe";
                                $Bombe_Portee = "N/A";
                            }
                            $Bombe_Dg = $Bombe * 30;
                            $garage .= "<tr><td>Soute</td><th>" . $Arme_nom . "</th><td>" . $Bombe . "</td><td>" . $Bombe_Nbr . "</td><td>" . $Bombe_Dg . "</td><td>" . $Bombe_Portee . "</td><td>N/A</td><td>N/A</td></tr>";
                        }
                    }
                    $garage .= "<tr><th colspan='2'>Arme</th><th>Calibre</th><th>Munitions</th><th>Dégats</th><th>Portée</th><th>Cadence</th><th>Fiabilité</th><tr></table>";
                    $img_txt = "mecano";
                    //UpdateCarac($PlayerID,"Renseignement",1);
                    break;
                case 9:
                    $Credits = -1;
                    $Sqn = GetSqn($country);
                    $Cons = false;
                    $rand_info = mt_rand(1, 16);
                    switch ($rand_info) {
                        case 1:
                            $Data_Avion = "ManoeuvreB";
                            $Txt_comp = " vire plus court à basse altitude";
                            break;
                        case 2:
                            $Data_Avion = "ManoeuvreH";
                            $Txt_comp = " vire plus court à basse altitude";
                            break;
                        case 3:
                            $Data_Avion = "Maniabilite";
                            $Txt_comp = " possède un meilleur taux de roulis";
                            break;
                        case 4:
                            $Data_Avion = "Stabilite";
                            $Txt_comp = " est plus stable";
                            break;
                        case 5:
                            $Data_Avion = "VitesseB";
                            $Txt_comp = " est plus rapide à basse altitude";
                            break;
                        case 6:
                            $Data_Avion = "VitesseH";
                            $Txt_comp = " est plus rapide à haute altitude";
                            break;
                        case 7:
                            $Data_Avion = "Plafond";
                            $Txt_comp = " peut voler plus haut";
                            break;
                        case 8:
                            $Data_Avion = "ArmePrincipale";
                            $Txt_comp = "<p>Votre mécano attire votre attention sur le fait que vos armes s'enrayent plus facilement avec l'altitude</p>";
                            $Cons = true;
                            break;
                        case 9:
                            $Data_Avion = "ArmePrincipale";
                            $Txt_comp = "<p>Votre mécano attire votre attention sur le fait que les manoeuvres de combat consomment beaucoup de carburant</p>";
                            $Cons = true;
                            break;
                        case 10:
                            $Data_Avion = "ArmePrincipale";
                            $Txt_comp = "<p>Votre mécano attire votre attention sur le fait que la chaleur favorise les pannes moteur</p>";
                            $Cons = true;
                            break;
                        case 11:
                            $Data_Avion = "ArmePrincipale";
                            $Txt_comp = "<p>Votre mécano attire votre attention sur le fait que les moteurs à injection directe possèdent un avantage lors des piqués</p>";
                            $Cons = true;
                            break;
                        case 12:
                            $Data_Avion = "ArmePrincipale";
                            $Txt_comp = "<p>Votre mécano attire votre attention sur le fait que les moteurs en ligne refroidis par liquide sont plus fragiles</p>";
                            $Cons = true;
                            break;
                        case 13:
                            $Data_Avion = "ArmePrincipale";
                            $Txt_comp = "<p>Votre mécano attire votre attention sur le fait que les moteurs en étoile refroidis par air sont plus résistants</p>";
                            $Cons = true;
                            break;
                        case 14:
                            $Data_Avion = "ArmePrincipale";
                            $Txt_comp = "<p>Votre mécano attire votre attention sur le fait qu'un régime moteur trop élevé favorise les pannes moteur</p>";
                            $Cons = true;
                            break;
                        case 15:
                            $Data_Avion = "Visibilite";
                            $Txt_comp = " représente une cible bien plus grande";
                            break;
                        case 16:
                            $Data_Avion = "Moteur";
                            $Txt_comp = "<p>Votre mécano attire votre attention sur le fait qu'un moteur en ligne à refroidissement par liquide résiste mal au froid</p>";
                            $Cons = true;
                            break;
                    }
                    $IDAvion = mt_rand(1, 3);
                    $Avionx = "Avion" . $IDAvion;
                    $Data = GetData("Avion", "ID", $$Avionx, $Data_Avion);
                    $Avion_nom = GetData("Avion", "ID", $$Avionx, "Nom");
                    $con = dbconnecti();
                    $ok = mysqli_query($con, "SELECT DISTINCT ID,Nom FROM Avion WHERE Pays='$country' AND Etat=1 ORDER BY RAND() LIMIT 1");
                    //mysqli_close($con);
                    if ($ok) {
                        while ($data = mysqli_fetch_array($ok, MYSQLI_ASSOC)) {
                            $ID_comp = $data['ID'];
                            $Nom_comp = $data['Nom'];
                        }
                        mysqli_free_result($ok);
                    }
                    $Data_comp = GetData("Avion", "ID", $ID_comp, $Data_Avion);
                    if ($Data >= $Data_comp)
                        $mes .= '<p>Votre mécano vous glisse à l\'oreille que le ' . $Avion_nom . $Txt_comp . ' que le ' . $Nom_comp . '</p>';
                    else
                        $mes .= '<p>Votre mécano vous glisse à l\'oreille que le ' . $Nom_comp . $Txt_comp . ' que le ' . $Avion_nom . '</p>';
                    if ($Cons)
                        $mes = $Txt_comp;
                    //Infos avions
                    //$con=dbconnecti();
                    $result = mysqli_query($con, "SELECT Autonomie,Engine,Engine_Nbr,Puissance FROM Avion WHERE ID='$Avion1'");
                    $result2 = mysqli_query($con, "SELECT Autonomie,Engine,Engine_Nbr,Puissance FROM Avion WHERE ID='$Avion1'");
                    $result3 = mysqli_query($con, "SELECT Autonomie,Engine,Engine_Nbr,Puissance FROM Avion WHERE ID='$Avion1'");
                    mysqli_close($con);
                    if ($result) {
                        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $Autonomie1 = $data['Autonomie'];
                            $Engine1 = $data['Engine'];
                            $Puissance1 = $data['Puissance'] / $data['Engine_Nbr'];
                        }
                        mysqli_free_result($result);
                        unset($data);
                    }
                    if ($result2) {
                        while ($data = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                            $Autonomie2 = $data['Autonomie'];
                            $Engine2 = $data['Engine'];
                            $Puissance2 = $data['Puissance'] / $data['Engine_Nbr'];
                        }
                        mysqli_free_result($result2);
                        unset($data);
                    }
                    if ($result3) {
                        while ($data = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
                            $Autonomie3 = $data['Autonomie'];
                            $Engine3 = $data['Engine'];
                            $Puissance3 = $data['Puissance'] / $data['Engine_Nbr'];
                        }
                        mysqli_free_result($result3);
                        unset($data);
                    }
                    //Moteurs
                    $con = dbconnecti(1);
                    $result = mysqli_query($con, "SELECT Nom,Fiabilite,Compresseur,Injection,Carburant FROM Moteur WHERE ID='$Engine1'");
                    $result2 = mysqli_query($con, "SELECT Nom,Fiabilite,Compresseur,Injection,Carburant FROM Moteur WHERE ID='$Engine2'");
                    $result3 = mysqli_query($con, "SELECT Nom,Fiabilite,Compresseur,Injection,Carburant FROM Moteur WHERE ID='$Engine3'");
                    mysqli_close($con);
                    if ($result) {
                        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $Engine_Nom1 = $data['Nom'];
                            $Fiabilite1 = $data['Fiabilite'];
                            $Compresseur1 = $data['Compresseur'];
                            $Injection1 = $data['Injection'];
                            $Carburant1 = $data['Carburant'];
                        }
                        mysqli_free_result($result);
                        unset($data);
                    }
                    if ($result2) {
                        while ($data = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                            $Engine_Nom2 = $data['Nom'];
                            $Fiabilite2 = $data['Fiabilite'];
                            $Compresseur2 = $data['Compresseur'];
                            $Injection2 = $data['Injection'];
                            $Carburant2 = $data['Carburant'];
                        }
                        mysqli_free_result($result2);
                        unset($data);
                    }
                    if ($result3) {
                        while ($data = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
                            $Engine_Nom3 = $data['Nom'];
                            $Fiabilite3 = $data['Fiabilite'];
                            $Compresseur3 = $data['Compresseur'];
                            $Injection3 = $data['Injection'];
                            $Carburant3 = $data['Carburant'];
                        }
                        mysqli_free_result($result3);
                        unset($data);
                    }
                    if ($Compresseur1 == 2)
                        $Compresseur1 = "Haute altitude";
                    elseif ($Compresseur1 == 3)
                        $Compresseur1 = "Basse altitude";
                    elseif ($Compresseur1 == 1)
                        $Compresseur1 = "Classique";
                    else
                        $Compresseur1 = "Aucun";
                    if ($Compresseur2 == 2)
                        $Compresseur2 = "Haute altitude";
                    elseif ($Compresseur2 == 3)
                        $Compresseur2 = "Basse altitude";
                    elseif ($Compresseur2 == 1)
                        $Compresseur2 = "Classique";
                    else
                        $Compresseur2 = "Aucun";
                    if ($Compresseur3 == 2)
                        $Compresseur3 = "Haute altitude";
                    elseif ($Compresseur3 == 3)
                        $Compresseur3 = "Basse altitude";
                    elseif ($Compresseur3 == 1)
                        $Compresseur3 = "Classique";
                    else
                        $Compresseur3 = "Aucun";
                    if ($Injection1 > 0)
                        $Injection1 = "Injection";
                    else
                        $Injection1 = "Carburateur";
                    if ($Injection2 > 0)
                        $Injection2 = "Injection";
                    else
                        $Injection2 = "Carburateur";
                    if ($Injection3 > 0)
                        $Injection3 = "Injection";
                    else
                        $Injection3 = "Carburateur";
                    $mes .= "<p>Votre mécano vous montre ensuite les moteurs équipant les avions de l'escadrille.</p>";
                    $garage = "<table class='table table-striped'>
								<thead><tr>
									<th></th><th>" . $Sqn . " 1</th><th>" . $Sqn . " 2</th><th>" . $Sqn . " 3</th>
								</tr></thead>
								<tr>
									<td></td><td>" . $Engine_Nom1 . "</td><td>" . $Engine_Nom2 . "</td><td>" . $Engine_Nom3 . "</td>
								</tr>
								<tr>
									<td>Carburant</td><td>" . $Carburant1 . " Octane</td><td>" . $Carburant2 . " Octane</td><td>" . $Carburant3 . " Octane</td>
								</tr>
								<tr>
									<td>Système d'alimentation</td><td>" . $Injection1 . "</td><td>" . $Injection2 . "</td><td>" . $Injection3 . "</td>
								</tr>
								<tr>
									<td>Compresseur</td><td>" . $Compresseur1 . "</td><td>" . $Compresseur2 . "</td><td>" . $Compresseur3 . "</td>
								</tr>
								<tr>
									<td>Puissance unitaire</td><td>" . $Puissance1 . "cv</td><td>" . $Puissance2 . "cv</td><td>" . $Puissance3 . "cv</td>
								</tr>
								<tr>
									<td>Fiabilité</td><td>" . $Fiabilite1 . "%</td><td>" . $Fiabilite2 . "%</td><td>" . $Fiabilite3 . "%</td>
								</tr>
								<tr><td colspan='4'><hr><td></tr>
								<tr>
									<td>Autonomie</td><td>" . $Autonomie1 . "km</td><td>" . $Autonomie2 . "km</td><td>" . $Autonomie3 . "km</td>
								</tr>
							</table>";
                    $img_txt = 'moteur' . $country;
                    //UpdateCarac($PlayerID,"Renseignement",1);
                    break;
                case 11:
                    $Credits = -1;
                    $Sqn = GetSqn($country);
                    $mes = "<p>Vous examinez chaque avion en détail.</p>";
                    for ($u = 1; $u < 4; $u++) {
                        switch ($u) {
                            case 1:
                                $ID_ref = $Avion1;
                                break;
                            case 2:
                                $ID_ref = $Avion2;
                                break;
                            case 3:
                                $ID_ref = $Avion3;
                                break;
                        }
                        $con = dbconnecti();
                        $result = mysqli_query($con, "SELECT Nom,ArmePrincipale,Arme1_Nbr,ArmeSecondaire,Arme2_Nbr,Autonomie,Reservoir FROM Avion WHERE ID='$ID_ref'");
                        mysqli_close($con);
                        if ($result) {
                            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $Avion_nom = $data['Nom'];
                                $Arme1 = $data['ArmePrincipale'];
                                $Arme1_nbr = $data['Arme1_Nbr'];
                                $Arme2 = $data['ArmeSecondaire'];
                                $Arme2_nbr = $data['Arme2_Nbr'];
                                $Autonomie = $data['Autonomie'];
                                $Reservoir = $data['Reservoir'];
                            }
                            mysqli_free_result($result);
                        }
                        if ($Reservoir)
                            $Reservoir = "(auto-obturant)";
                        else
                            $Reservoir = "";
                        $Array_Mod = GetAmeliorations($ID_ref);
                        $Arme8_fus = $Array_Mod[0];
                        $Arme8_ailes = $Array_Mod[1];
                        $Arme13 = $Array_Mod[2];
                        $Arme20 = $Array_Mod[3];
                        $Arme8_fus_nbr = $Array_Mod[4];
                        $Arme13_fus_nbr = $Array_Mod[5];
                        $Arme20_fus_nbr = $Array_Mod[6];
                        $Arme8_ailes_nbr = $Array_Mod[7];
                        $Arme13_ailes_nbr = $Array_Mod[8];
                        $Arme20_ailes_nbr = $Array_Mod[9];
                        $Arme8_ailes_max = $Array_Mod[10];
                        $Arme13_ailes_max = $Array_Mod[11];
                        $Bombe50_nbr = $Array_Mod[12];
                        $Bombe125_nbr = $Array_Mod[13];
                        $Bombe250_nbr = $Array_Mod[14];
                        $Bombe500_nbr = $Array_Mod[15];
                        $Camera_low = $Array_Mod[16];
                        $Camera_high = $Array_Mod[17];
                        $Baby = $Array_Mod[18];
                        $Radar_On = $Array_Mod[19];
                        $Torpilles = $Array_Mod[20];
                        $Mines = $Array_Mod[21];
                        $Rockets = $Array_Mod[35];
                        $Arme1_cal = round(GetData("Armes", "ID", $Arme1, "Calibre"));
                        $Arme1_chargeur = GetData("Armes", "ID", $Arme1, "Munitions");
                        $Arme1_nom = GetData("Armes", "ID", $Arme1, "Nom");
                        $Arme2_cal = round(GetData("Armes", "ID", $Arme2, "Calibre"));
                        $Arme2_chargeur = GetData("Armes", "ID", $Arme2, "Munitions");
                        $Arme2_nom = GetData("Armes", "ID", $Arme2, "Nom");
                        $Arme8_fus_masse = GetData("Armes", "ID", $Arme8_fus, "Masse");
                        $Arme8_fus_nom = GetData("Armes", "ID", $Arme8_fus, "Nom");
                        $Arme8_fus_cal = round(GetData("Armes", "ID", $Arme8_fus, "Calibre"));
                        $Arme8_fus_chargeur = GetData("Armes", "ID", $Arme8_fus, "Munitions");
                        $Arme8_ailes_masse = GetData("Armes", "ID", $Arme8_ailes, "Masse");
                        $Arme8_ailes_nom = GetData("Armes", "ID", $Arme8_ailes, "Nom");
                        $Arme8_ailes_cal = round(GetData("Armes", "ID", $Arme8_ailes, "Calibre"));
                        $Arme8_ailes_chargeur = GetData("Armes", "ID", $Arme8_ailes, "Munitions");
                        if ($Arme13 != 5) {
                            $Arme13_masse = GetData("Armes", "ID", $Arme13, "Masse");
                            $Arme13_cal = round(GetData("Armes", "ID", $Arme13, "Calibre"));
                            $Arme13_chargeur = GetData("Armes", "ID", $Arme13, "Munitions");
                        }
                        if ($Arme20 != 5) {
                            $Arme20_masse = GetData("Armes", "ID", $Arme20, "Masse");
                            $Arme20_cal = round(GetData("Armes", "ID", $Arme20, "Calibre"));
                            $Arme20_chargeur = GetData("Armes", "ID", $Arme20, "Munitions");
                        }
                        if ($Camera_high != 5)
                            $Camera_high_masse = GetData("Armes", "ID", $Camera_high, "Masse");
                        $Arme13_nom = GetData("Armes", "ID", $Arme13, "Nom");
                        $Arme20_nom = GetData("Armes", "ID", $Arme20, "Nom");
                        $garage .= "<table class='table'>
								<thead><tr><th colspan='6'>" . $Sqn . " " . $u . "</th><tr></thead>
								<tr bgcolor='lightyellow'><th colspan='6'>" . $Avion_nom . "</th><tr>
								<tr><td colspan='2'>Réservoir principal " . $Autonomie . " litres " . $Reservoir . "</td></tr>
								<tr>
									<th>Arme Principale (choix)</th>
									<th>Arme Secondaire (choix)</th>
								</tr>";
                        if ($Arme8_fus_nbr > 0) {
                            $garage .= '<tr><td>' . $Arme8_fus_nbr . ' ' . $Arme8_fus_nom . ' (' . $Arme8_fus_cal . 'mm / ' . $Arme8_fus_chargeur . ' coups)</td>';
                        }
                        if ($Arme8_ailes_nbr > 0) {
                            $garage .= '<td>' . $Arme8_ailes_nbr . ' ' . $Arme8_ailes_nom . ' (' . $Arme8_ailes_cal . 'mm / ' . $Arme8_ailes_chargeur . ' coups)</td></tr>';
                        }
                        if ($Arme13_fus_nbr > 0) {
                            $garage .= '<tr><td>' . $Arme13_fus_nbr . ' ' . $Arme13_nom . ' (' . $Arme13_cal . 'mm / ' . $Arme13_chargeur . ' coups)</td>';
                        }
                        if ($Arme8_ailes_max > 3) {
                            $Arme8_ailes_nbr = $Arme8_ailes_nbr * 2;
                            $garage .= '<td>' . $Arme8_ailes_nbr . ' ' . $Arme8_ailes_nom . ' (' . $Arme8_ailes_cal . 'mm / ' . $Arme8_ailes_chargeur . ' coups)</td></tr>';
                        }
                        if ($Arme20_fus_nbr > 0) {
                            $garage .= '<tr><td>' . $Arme20_fus_nbr . ' ' . $Arme20_nom . ' (' . $Arme20_cal . 'mm / ' . $Arme20_chargeur . ' coups)</td>';
                        }
                        if ($Arme8_ailes_max > 5) {
                            $garage .= '<td>' . $Arme8_ailes_max . ' ' . $Arme8_ailes_nom . ' (' . $Arme8_ailes_cal . 'mm / ' . $Arme8_ailes_chargeur . ' coups)</td></tr>';
                        }
                        if ($Camera_low != 5) {
                            $garage .= "<tr><td>1 Caméra portative (Basse altitude uniquement)</td>";
                        }
                        if ($Arme13_ailes_max > 0) {
                            $garage .= '<td>' . $Arme13_ailes_nbr . ' ' . $Arme13_nom . ' (' . $Arme13_cal . 'mm / ' . $Arme13_chargeur . ' coups)</td></tr>';
                        }
                        if ($Camera_high != 5) {
                            $garage .= '<tr><td>1 Caméra fixe (' . $Camera_high_masse . 'kg)</td>';
                        }
                        if ($Arme13_ailes_max > 3) {
                            $garage .= '<td>' . $Arme13_ailes_max . ' ' . $Arme13_nom . ' (' . $Arme13_cal . 'mm / ' . $Arme13_chargeur . ' coups)</td></tr>';
                        }
                        if ($Arme20_ailes_nbr > 0) {
                            $garage .= '<td>' . $Arme20_ailes_nbr . ' ' . $Arme20_nom . ' (' . $Arme20_cal . 'mm / ' . $Arme20_chargeur . ' coups)</td></tr>';
                        }
                        if ($Baby) {
                            $garage .= "<tr><th colspan='2'>Réservoir largable</td></tr>";
                            $garage .= "<tr><td colspan='2'>" . $Baby . " litres</td></tr>";
                        }
                        $garage .= "<tr><th colspan='2'>Options de Bombes ou Charges supplémentaires</td></tr>";
                        if ($Bombe50_nbr > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Bombe50_nbr . " bombes de 50kg</td></tr>";
                        }
                        if ($Bombe125_nbr > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Bombe125_nbr . " bombes de 125kg</td></tr>";
                        }
                        if ($Bombe250_nbr > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Bombe250_nbr . " bombes de 250kg</td></tr>";
                        }
                        if ($Bombe500_nbr > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Bombe500_nbr . " bombes de 500kg</td></tr>";
                        }
                        if ($Bombe1000_nbr > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Bombe1000_nbr . " bombes de 1000kg</td></tr>";
                        }
                        if ($Bombe2000_nbr > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Bombe2000_nbr . " bombes de 2000kg</td></tr>";
                        }
                        if ($Torpilles > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Torpilles . " torpilles</td></tr>";
                        }
                        if ($Mines > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Mines . " mines ou charges</td></tr>";
                        }
                        if ($Rockets > 0) {
                            $garage .= "<tr><td colspan='2'>" . $Rockets . " Rockets</td></tr>";
                        }
                        if ($Camera_low != 5) {
                            $garage .= "<tr><td colspan='2'>1 Caméra portative (Basse altitude uniquement)</td></tr>";
                        }
                        if ($Camera_high != 5) {
                            $garage .= "<tr><td colspan='2'>1 Caméra fixe (" . $Camera_high_masse . "kg)</td></tr>";
                        }
                        if ($Radar_On) {
                            $garage .= "<tr><td colspan='2'>1 Radar embarqué</td></tr>";
                        }
                        $garage .= '</td></tr></table></div><br>';
                    }
                    $img_txt = 'garage' . $Avion1;
                    //UpdateCarac($PlayerID,"Renseignement",1);
                    break;
                case 12:
                    //if($Commandant)$Duperie_Cdt=GetData("Pilote","ID",$Commandant,"Duperie");
                    if (!$Duperie_Cdt) $Duperie_Cdt = mt_rand(50, 250);
                    if ($Reputation > 9999 and $Duperie > $Duperie_Cdt) {
                        $mes .= "<p>Votre demande est acceptée par votre hiérarchie.</p>";
                        $img_txt = 'transfer_yes' . $country;
                        /*SetData("Unit","Mission_Lieu",0,"ID",$Unite);
						SetData("Unit","Mission_Type",0,"ID",$Unite);*/
                        //UpdateCarac($PlayerID,"Duperie",1);
                        UpdateCarac($PlayerID, "Reputation", 1);
                        $Credits = -4;
                    } else {
                        $mes .= "<p>Votre demande est rejetée.</p>";
                        $img_txt = 'transfer_no' . $country;
                        UpdateCarac($PlayerID, "Reputation", -1);
                        UpdateCarac($PlayerID, "Avancement", -1);
                    }
                    break;
                case 13:
                    if ($Reputation < 50 or $Reputation > 10000 or $Commandant == $PlayerID) {
                        $mes .= "<p>Votre demande est acceptée par votre hiérarchie.</p>";
                        $img_txt = 'transfer_yes' . $country;
                        UpdateCarac($PlayerID, "Missions_Jour", -1);
                        $Credits = -4;
                        if ($Reputation > 50) UpdateCarac($PlayerID, "Endurance", -1);
                    } else {
                        $mes .= "<p>Votre demande est rejetée.</p>";
                        $img_txt = 'transfer_no' . $country;
                    }
                    break;
                case 14:
                case 15:
                case 16:
                case 17:
                case 18:
                case 19:
                case 37:
                    $rep_gain = 0;
                    if ($Action == 14)
                        $Skillx = "Bombardement";
                    elseif ($Action == 15)
                        $Skillx = "Vue";
                    elseif ($Action == 16)
                        $Skillx = "Navigation";
                    elseif ($Action == 17)
                        $Skillx = "Pilotage";
                    elseif ($Action == 18)
                        $Skillx = "Tactique";
                    elseif ($Action == 19)
                        $Skillx = "Tir";
                    elseif ($Action == 37)
                        $Skillx = "Acrobatie";
                    //$Skill=GetData("Pilote","ID",$PlayerID,$Skillx);
                    $con = dbconnecti();
                    $results = mysqli_query($con, "SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
                    mysqli_close($con);
                    if ($results) {
                        while ($data = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                            $Skills_Pil[] = $data['Skill'];
                        }
                        mysqli_free_result($results);
                    }
                    $Skill = 50;
                    if (is_array($Skills_Pil)) {
                        if (in_array(67, $Skills_Pil))
                            $Skill = 150;
                        elseif (in_array(66, $Skills_Pil))
                            $Skill = 125;
                        elseif (in_array(65, $Skills_Pil))
                            $Skill = 100;
                        elseif (in_array(64, $Skills_Pil))
                            $Skill = 75;
                        if (in_array(71, $Skills_Pil))
                            $Commandement = 150;
                        elseif (in_array(70, $Skills_Pil))
                            $Commandement = 125;
                        elseif (in_array(69, $Skills_Pil))
                            $Commandement = 100;
                        elseif (in_array(68, $Skills_Pil))
                            $Commandement = 75;
                        if (in_array(76, $Skills_Pil))
                            $Personnel[5] += 1;
                        if (in_array(130, $Skills_Pil))
                            $Personnel[5] += 1;
                    }
                    if ($Skill > 50) {
                        $Max_Train = $Skill;
                        /*if($Skill >200)
							$Max_Train=125;
						elseif($Skill >150)
							$Max_Train=100;
						elseif($Skill >100)
							$Max_Train=75;
						else
							$Max_Train=50;*/
                        $Progress = ($Skill / 100) + ($Reputation / 100000) + ($Commandement / 100);
                        $Progress += (($Progress / 10) * $Personnel[5]);
                        $query = "SELECT ID,Nom FROM Pilote_IA WHERE Cible=0 AND Unit='$Unite' AND $Skillx <'$Max_Train' AND Escorte=0 AND Couverture=0 AND Couverture_Nuit=0 AND Actif=1";
                        $con = dbconnecti();
                        $ok = mysqli_query($con, $query);
                        mysqli_close($con);
                        if ($ok) {
                            while ($data = mysqli_fetch_array($ok, MYSQLI_ASSOC)) {
                                UpdateCarac($data['ID'], $Skillx, $Progress, "Pilote_IA");
                                $mes .= '<br>' . $data['Nom'] . ' a progressé de ' . $Progress . ' en ' . $Skillx;
                                $rep_gain += 1;
                            }
                            mysqli_free_result($ok);
                        }
                        $img_txt = 'instruction' . $country;
                        $Credits = -4;
                        UpdateCarac($PlayerID, "Missions_Max", 1);
                        if ($rep_gain > 0) {
                            $mes .= "<div class='alert alert-warning'>" . $rep_gain . " pilotes ont bénéficié de votre entrainement.</div>
							<div class='alert alert-info'>La progression de caractéristique est dégressive. Plus la caractéristique est élevée, plus la progression sera lente.
							<h4>Dégressivité</h4>
							<ul>
								<li>100% jusqu'à 25</li>
								<li>75% entre 25 et 50</li>
								<li>50% entre 50 et 75</li>
								<li>20% entre 75 et 100</li>
								<li>10% entre 100 et 125</li>
								<li>4% entre 125 et 150</li>
								<li>2% entre 150 et 175</li>
								<li>0.8% entre 175 et 200</li>
								<li>0.4% au-delà de 200</li>
							</ul></div>";
                            //UpdateCarac($PlayerID,"Commandement",$rep_gain);
                        } else
                            $mes .= "Aucun pilote n'a bénéficié de votre formation";
                    } else {
                        $mes = "Vous n'êtes pas encore suffisamment qualifié pour former vos collègues dans ce domaine.";
                        $img_txt = 'transfer_no' . $country;
                    }
                    break;
                case 20:
                    $mes .= "<p>Ces quelques heures de permission vous ont remis en forme comme jamais!</p>";
                    $img_txt = "repos";
                    if ($Moral < 201) SetData("Pilote", "Moral", 200, "ID", $PlayerID);
                    if ($Courage < 201) SetData("Pilote", "Courage", 200, "ID", $PlayerID);
                    if (!$Pilote_Salon) {
                        if ($Carotteur)
                            $Credits = -6;
                        else
                            $Credits = -12;
                    }
                    break;
                case 21:
                    $Rep = 5 + (($Personnel[4] + $Pers_Sup) * 5 * $Bonus_Pers);
                    UpdateCarac($PlayerID, "Moral", -5);
                    UpdateCarac($PlayerID, "Reputation", $Rep);
                    UpdateCarac($PlayerID, "Avancement", 2);
                    //UpdateCarac($PlayerID,"Duperie",-1);
                    $mes = "
					<p>Une bonne dose de cafard,mais vous avez gagné le respect de la troupe et de vos supérieurs!</p>
					<p>Au champ d'honneur,les coquelicots
					<br>Sont parsemés de lot en lot
					<br>Auprès des croix; et dans l'espace
					<br>Les alouettes devenues lasses
					<br>Mêlent leurs chants au sifflement
					<br>Des obusiers.
					<br>
					<br>Nous sommes morts,
					<br>Nous qui songions la veille encor'
					<br>À nos parents,à nos amis,
					<br>C'est nous qui reposons ici,
					<br>Au champ d'honneur.
					<br>
					<br>À vous jeunes désabusés,
					<br>À vous de porter l'oriflamme
					<br>Et de garder au fond de l'âme
					<br>Le goût de vivre en liberté.
					<br>Acceptez le défi,sinon
					<br>Les coquelicots se faneront
					<br>Au champ d'honneur.</p>";
                    if (!$Pilote_Salon) {
                        if ($Assis_Bureau)
                            $Credits = -1;
                        else
                            $Credits = -2;
                    }
                    $img_txt = 'funerailles' . $country;
                    break;
                case 22:
                    if (!$Pilote_Salon) {
                        if ($Assis_Bureau)
                            $Credits = -1;
                        else
                            $Credits = -2;
                    }
                    $rand_info = mt_rand(1, 33);
                    switch ($rand_info) {
                        case 1:
                            $mes = "<p>" . $As . " vous conseille de ne pas tenter un Immelman à basse altitude</p>";
                            break;
                        case 2:
                            $mes = "<p>" . $As . " vous conseille de réserver vos bombes pour les cibles blindées,les ponts et les bâtiments</p>";
                            break;
                        case 3:
                            $mes = "<p>" . $As . " vous conseille de ne pas gaspiller vos munitions de mitrailleuses sur des cibles blindées,des ponts ou des bâtiments</p>";
                            break;
                        case 4:
                            $mes = "<p>" . $As . " vous conseille de voler à la plus haute altitude possible lors des missions de reconnaissance</p>";
                            break;
                        case 5:
                            $mes = "<p>" . $As . " vous conseille de suivre les consignes de votre leader si vous voulez monter rapidement en grade</p>";
                            break;
                        case 6:
                            $mes = "<p>" . $As . " vous conseille de ne pas chercher à confirmer vos victoires à tout prix si vous voulez monter rapidement en grade</p>";
                            break;
                        case 7:
                            $mes = "<p>" . $As . " vous conseille de ne pas trop vous approcher d'un avion pour l'achever,vous risqueriez d'être endommagé par les débris</p>";
                            break;
                        case 8:
                            $mes = "<p>" . $As . " vous conseille d'éviter de tirer lorsque l'angle de tir est trop important</p>";
                            break;
                        case 9:
                            $mes = "<p>" . $As . " vous conseille de tenter de ne tirer qu'à courte distance,moins de 100 mètres si possible</p>";
                            break;
                        case 10:
                            $mes = "<p>" . $As . " vous conseille de tenter de tirer en courtes rafales</p>";
                            break;
                        case 11:
                            $mes = "<p>" . $As . " vous conseille de tenter de fuir dans les nuages en début de combat si vous ne vous sentez pas de taille</p>";
                            break;
                        case 12:
                            $mes = "<p>" . $As . " vous conseille de tenter de vous laisser dépasser par votre adversaire si votre taux de roulis est élevé</p>";
                            break;
                        case 13:
                            $mes = "<p>" . $As . " vous conseille de coiffer votre adversaire si votre vitesse ascensionnelle est élevée</p>";
                            break;
                        case 14:
                            $mes = "<p>" . $As . " vous conseille d'attaquer les chasseurs lourds par le flanc</p>";
                            break;
                        case 15:
                            $mes = "<p>" . $As . " vous conseille de ne pas attaquer les chasseurs lourds de face</p>";
                            break;
                        case 16:
                            $mes = "<p>" . $As . " vous conseille de ne pas attaquer les bombardiers par l'arrière</p>";
                            break;
                        case 17:
                            $mes = "<p>" . $As . " vous conseille d'attaquer les bombardiers par le ventre ou le flanc</p>";
                            break;
                        case 18:
                            $mes = "<p>" . $As . " vous conseille d'utiliser des munitions explosives sur les cibles peu blindées</p>";
                            break;
                        case 19:
                            $mes = "<p>" . $As . " vous conseille d'utiliser des munitions perforantes sur les cibles blindées</p>";
                            break;
                        case 20:
                            $mes = "<p>" . $As . " vous conseille de ne pas tenter de fuir en piqué si votre adversaire possède un moteur à injection directe et pas vous</p>";
                            break;
                        case 21:
                            $mes = "<p>" . $As . " vous conseille de ne pas tenter de fuir en grimpant si votre adversaire possède une vitesse ascensionnelle plus élevée que la vôtre</p>";
                            break;
                        case 22:
                            $mes = "<p>" . $As . " vous conseille de ne pas engager le combat si les conditions ne sont pas favorables</p>";
                            break;
                        case 23:
                            $mes = "<p>" . $As . " vous rappelle qu'en mission d'escorte,tenir les chasseurs ennemis à distance suffit,il n'est pas nécessaire de les détruire.</p>";
                            break;
                        case 24:
                            $mes = "<p>" . $As . " vous rappelle qu'en mission de patrouille,vous devez engager tous les avions ennemis,mais pas forcément les détruire.</p>";
                            break;
                        case 25:
                            $mes = "<p>" . $As . " vous conseille de vérifier l'armement et les munitions de votre avion avant de partir en mission.</p>";
                            break;
                        case 26:
                            $mes = "<p>" . $As . " vous conseille de ne jamais laisser votre escorte sans protection.</p>";
                            break;
                        case 27:
                            $mes = "<p>" . $As . " vous conseille de bien vérifier votre sélecteur de tir avant d'utiliser vos armes.</p>";
                            break;
                        case 28:
                            $mes = "<p>" . $As . " vous conseille d'approcher tous les avions que vous rencontrez lors d'une patrouille.</p>";
                            break;
                        case 29:
                            $mes = "<p>" . $As . " vous conseille de privilégier la mission au détriment de votre gloire personnelle.</p>";
                            break;
                        case 30:
                            $mes = "<p>" . $As . " vous conseille d'attaquer les bombardiers par le ventre,s'ils ne disposent pas de tourelle ventrale.</p>";
                            break;
                        case 31:
                            $mes = "<p>" . $As . " vous conseille d'atterrir avec une vitesse comprise entre 50 et 200.</p>";
                            break;
                        case 32:
                            $mes = "<p>" . $As . " vous conseille d'utiliser vos volets pour réduire votre rayon de virage en vol.</p>";
                            break;
                        case 33:
                            $mes = "<p>" . $As . " vous conseille de toujours emporter un porte-bonheur avec vous.</p>";
                            break;
                    }
                    $As_ID = GetData("Pilote_IA", "Nom", $As, "ID");
                    if ($Ailier == $As_ID) {
                        $mes .= $As . ' est très content de voler avec vous. Il apprécie vos discussions.';
                        UpdateCarac($PlayerID, "Moral", 5);
                        UpdateCarac($PlayerID, "Reputation", 1);
                        /*Matos random
						if(mt_rand(0,10) >5)
						{
							$mes.=$As."<p> vous fournit une paire de gants qui améliorera votre pilotage. La paire de gants est ajoutée à l'équipement de votre unité.</p>";
							AddToCoffre($Unite,74);
						}*/
                    }
                    $mes .= $As . " vous rappelle les principes de base du combat aérien,à savoir :
					<br>1- Etre le premier à détecter l'ennemi.
					<br>2- Se placer idéalement.
					<br>3- Effectuer des manoeuvres tirant avantage des qualités de votre avion.
					<br>4- Faire en sorte que le combat soit le plus bref possible.
					<br>5- Dégager avant qu'il ne soit trop tard.
					<br>Et surtout ! Ne pas engager le combat si la situation n'est pas favorable !";
                    $img_txt = 'as' . $country;
                    //UpdateCarac($PlayerID,"Renseignement",1);
                    break;
                case 23:
                    $Equipage = GetData("Pilote", "ID", $PlayerID, "Equipage");
                    if ($Equipage) {
                        $Repa = 50 + (($Personnel[3] + $Pers_Sup) * 50 * $Bonus_Pers);
                        //UpdateCarac($PlayerID,"Moral",5,"Pilote",100);
                        UpdateCarac($Equipage, "Endurance", 1, "Equipage", 5);
                        UpdateCarac($Equipage, "Moral", $Repa, "Equipage");
                        UpdateCarac($Equipage, "Courage", $Repa, "Equipage");
                        UpdateCarac($PlayerID, "Reputation", 1);
                        UpdateCarac($PlayerID, "Avancement", -1);
                        $mes = "<p>Votre équipier vous remercie,il se sent nettement mieux!</p>";
                        if (!$Pilote_Salon) {
                            if ($Assis_Bureau)
                                $Credits = -1;
                            else
                                $Credits = -2;
                        }
                        $img_txt = 'fiesta' . $country;
                    } else {
                        $mes = "<p>Vous n'avez personne avec qui faire la fête!</p>";
                        $img_txt = 'transfer_no' . $country;
                    }
                    break;
                case 24:
                    if ($Reputation > 50 and $Avancement > 199 and $Missions_Max < 6) {
                        $mes .= '<p>Votre demande est acceptée par votre hiérarchie.</p>';
                        $img_txt = 'transfer_yes' . $country;
                        //UpdateCarac($PlayerID,"Missions_Jour",1);
                        UpdateCarac($PlayerID, "Missions_Max", 1);
                        $Credits = 4;
                    } else {
                        $mes .= '<p>Votre demande est rejetée.</p>';
                        $img_txt = 'transfer_no' . $country;
                    }
                    break;
                case 25:
                    if ($Moral < 100) UpdateCarac($PlayerID, "Moral", 5, "Pilote", 100);
                    $Base_rep = 5;
                    if ($Slot4 == 29 or $Slot4 == 59)
                        $Base_rep *= 2;
                    elseif ($Slot4 == 53 or $Slot4 == 58 or $Slot4 == 63 or $Slot4 == 82)
                        $Base_rep *= 1.25;
                    if ($Slot7 == 61)
                        $Base_rep *= 1.1;
                    if ($Slot8 == 44)
                        $Base_rep *= 1.5;
                    if ($Slot8 == 86)
                        $Base_rep *= 1.5;
                    if ($Slot11 == 13)
                        $Base_rep *= 1.5;
                    UpdateCarac($PlayerID, "Reputation", $Base_rep);
                    UpdateCarac($PlayerID, "Avancement", $Base_rep);
                    SetData("Pilote", "Skill_Fav", 1, "ID", $PlayerID);
                    $mes = "<p>Vous passez une merveilleuse soirée,serrant des mains et échangeant des banalités sans intérêt.</p>";
                    if ($Duperie + ($Reputation / 1000) > mt_rand(0, 100)) {
                        $Base_rep *= 2;
                        UpdateCarac($PlayerID, "Reputation", $Base_rep);
                        UpdateCarac($PlayerID, "Avancement", $Base_rep);
                        $mes .= "Vous avez retenu l'attention du général. Nul doute que cela aura un effet bénéfique sur votre carrière.";
                    } else
                        $mes .= "Malheureusement pour vous,le général n'a pas semblé vous porter le moindre intérêt.";
                    if ($Favori_General)
                        $Credits = -1;
                    elseif ($Pilote_Salon)
                        $Credits = -1;
                    elseif ($Assis_Bureau)
                        $Credits = -2;
                    else
                        $Credits = -4;
                    $img_txt = 'ball' . $country;
                    //UpdateCarac($PlayerID,"Duperie",1);
                    //UpdateCarac($PlayerID,"Commandement",1);
                    //UpdateCarac($PlayerID,"Renseignement",1);
                    break;
                case 26:
                    $Rep = 2 + (($Personnel[10] + $Pers_Sup) * 2 * $Bonus_Pers);
                    if ($Slot1 == 12 or $Slot1 == 57)
                        $Rep *= 1.5;
                    elseif ($Slot1 == 56 or $Slot1 == 80)
                        $Rep *= 2;
                    if ($Slot4 == 31 or $Slot4 == 36 or $Slot4 == 37 or $Slot4 == 38 or $Slot4 == 39)
                        $Rep *= 1.5;
                    if ($Slot11 == 13)
                        $Rep *= 1.5;
                    elseif ($Slot4 == 59)
                        $Rep *= 2;
                    UpdateCarac($PlayerID, "Reputation", $Rep);
                    $mes = "Vous sortez votre attirail de parfait pilote de salon pour impressionner la bleusaille.";
                    if ($Pilote_Salon or $Assis_Bureau)
                        $Credits = -1;
                    else
                        $Credits = -2;
                    $img_txt = 'frime' . $country;
                    break;
                case 27:
                    $Rep = 1 + $Personnel[10] + $Pers_Sup;
                    if ($Slot1 == 12 or $Slot1 == 57)
                        $Rep *= 1.5;
                    elseif ($Slot1 == 18 or $Slot1 == 40 or $Slot1 == 41 or $Slot1 == 42 or $Slot1 == 43 or $Slot1 == 79)
                        $Rep *= 1.1;
                    elseif ($Slot1 == 56 or $Slot1 == 80)
                        $Rep *= 2;
                    if ($Slot2 == 19)
                        $Rep *= 1.5;
                    elseif ($Slot2 == 20)
                        $Rep *= 1.25;
                    if ($Slot4 == 31 or $Slot4 == 36 or $Slot4 == 37 or $Slot4 == 38 or $Slot4 == 39)
                        $Rep *= 1.5;
                    elseif ($Slot4 == 53 or $Slot4 == 58)
                        $Rep *= 1.25;
                    elseif ($Slot4 == 29 or $Slot4 == 59)
                        $Rep *= 2;
                    UpdateCarac($PlayerID, "Reputation", $Rep);
                    $mes = "Vous enfilez un uniforme impeccable,vos plus belles décorations et prenez une pose décontractée,comme seuls les plus grands savent le faire!";
                    $Credits = -1;
                    $img_txt = 'dandy' . $country;
                    break;
                case 28:
                    $Rep = 1 + $Personnel[10] + $Pers_Sup;
                    UpdateCarac($PlayerID, "Reputation", $Rep);
                    $mes = "Vous vous assurez qu'il n'en manque aucune et que la peinture soit bien fraiche!";
                    $Credits = -1;
                    $img_txt = 'cocardes' . $country;
                    break;
                case 29:
                    $Rep = 5 + (($Personnel[10] + $Pers_Sup) * 5 * $Bonus_Pers);
                    UpdateCarac($PlayerID, "Reputation", $Rep);
                    $mes = "Vous prenez votre plus belle voix pour vanter les mérites des pilotes,et surtout les vôtres!";
                    if ($Pilote_Salon)
                        $Credits = -1;
                    elseif ($Assis_Bureau)
                        $Credits = -2;
                    else
                        $Credits = -4;
                    $img_txt = 'radio' . $country;
                    break;
                case 30:
                    $Repa = 5 + (($Personnel[12] + $Pers_Sup) * 5 * $Bonus_Pers);
                    UpdateData("Lieu", "Camouflage", $Repa, "ID", $Base, 100);
                    $Credits = -2;
                    $img_txt = "gestion_camouflage";
                    break;
                case 31:
                    $Avance = 20 + (($Personnel[9] + $Pers_Sup) * 2 * $Bonus_Pers);
                    if ($Slot11 == 64 or $Slot11 == 65 or $Slot11 == 66 or $Slot11 == 67 or $Slot11 == 68 or $Slot11 == 78 or $Slot11 == 84 or $Slot11 == 85)
                        $Avance *= 1.5;
                    //UpdateCarac($PlayerID,"Missions_Jour",1);
                    UpdateCarac($PlayerID, "Missions_Max", 1);
                    UpdateCarac($PlayerID, "Gestion", 1);
                    UpdateCarac($PlayerID, "Courage", -1);
                    UpdateCarac($PlayerID, "Avancement", $Avance);
                    $Credits = -4;
                    $img_txt = 'em' . $country;
                    break;
                case 32:
                    SetData("Pilote", "Couverture", 0, "ID", $PlayerID);
                    DeleteData("Patrouille_live", "Joueur", $PlayerID);
                    $Credits = -1;
                    $img_txt = 'gestion_avions' . $country;
                    break;
                case 33:
                    if ($Slot11 == 1)
                        $Duperie += 10;
                    elseif ($Slot10 == 73)
                        $Duperie += 5;
                    if (($Renseignement + $Duperie > mt_rand(10, 100)) or $Indiscret) {
                        $up = 2 + $Personnel[11] + $Pers_Sup;
                        $mes .= "<p>Une auxiliaire féminine vous permet d'accéder aux dossiers.<br>Vous les parcourez,récoltant des tas d'informations utiles!</p>";
                        $img_txt = 'dossiers' . $country;
                        $Credits = -4;
                        //UpdateCarac($PlayerID,"Renseignement",$up);
                        //UpdateCarac($PlayerID,"Duperie",$up);
                    } else {
                        $mes .= "<p>Vous n'êtes pas autorisé à accéder aux dossiers<br>Votre indiscrétion est remarquée par votre hiérarchie!</p>";
                        $img_txt = 'transfer_no' . $country;
                        //UpdateCarac($PlayerID,"Duperie",-1);
                        //UpdateCarac($PlayerID,"Renseignement",-1);
                        //UpdateCarac($PlayerID,"Avancement",-5);
                    }
                    break;
                case 34:
                    SetData("Pilote", "Escorte", 0, "ID", $PlayerID);
                    DeleteData("Patrouille_live", "Joueur", $PlayerID);
                    $Credits = -1;
                    $img_txt = 'gestion_avions' . $country;
                    break;
                case 35:
                    $con = dbconnecti();
                    $reset = mysqli_query($con, "UPDATE Pilote SET Escorte=0,Couverture=0,Courage=Courage+10,Commando=1 WHERE ID='$PlayerID'");
                    mysqli_close($con);
                    $Credits = -2;
                    $img_txt = 'commando' . $country;
                    $mes .= "<p>Vous êtes intégré au groupe Commando.<br>Vous serez averti de votre prochaine mission.</p>";
                    break;
                case 36:
                    $con = dbconnecti();
                    $reset = mysqli_query($con, "UPDATE Pilote SET Escorte=0,Couverture=0,Courage=Courage+25,Endurance=Endurance-1 WHERE ID='$PlayerID'");
                    mysqli_close($con);
                    UpdateCarac($PlayerID, "Missions_Max", 1);
                    //UpdateCarac($PlayerID,"Duperie",5);
                    $Credits = -4;
                    $img_txt = 'commando_training';
                    $mes .= "<p>Vous vous entrainez durement aux techniques furtives du commando.</p>";
                    break;
                case 38:
                    UpdateCarac($PlayerID, "Missions_Max", 1);
                    $con = dbconnecti();
                    $reset = mysqli_query($con, "UPDATE Pilote SET Escorte=0,Couverture=0,Ecole=1 WHERE ID='$PlayerID'");
                    mysqli_close($con);
                    $Credits = -4;
                    $mes = "Vous êtes prêt pour suivre une formation théorique au sol.";
                    $img_txt = "instruction" . $country;
                    break;
                case 39:
                    $con = dbconnecti();
                    $reset = mysqli_query($con, "UPDATE Pilote SET Escorte=0,Couverture=0,Ecole=1 WHERE ID='$PlayerID'");
                    mysqli_close($con);
                    $Credits = -2;
                    $mes = "Vous êtes prêt pour suivre une formation théorique au sol.";
                    $img_txt = "instruction" . $country;
                    break;
                /*case 40:
					if($Credits_ori >=4)
					{
						$Front=GetData("Pilote","ID",$PlayerID,"Front");
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers+10 WHERE Pays_ID='$country' AND Front='$Front'");
						mysqli_close($con);
						UpdateCarac($PlayerID,"Missions_Max",1);
						UpdateCarac($PlayerID,"Reputation",10);
						UpdateCarac($PlayerID,"Avancement",10);
						UpdateCarac($PlayerID,"Note",2);
						$Credits=-4;
						$mes="Vous mettez votre réputation au service de la vente des bons de guerre.";
						$img_txt="bons".$country;
					}
					else
					{
						$mes="Action impossible par manque de temps.";
						$img_txt='transfer_no'.$country;
					}
				break;*/
                case 41:
                    if ($Avion1 > 0) {
                        $Reserve = GetData("Avion", "ID", $Avion1, "Reserve");
                        $con = dbconnecti(4);
                        $result_crash = mysqli_query($con, "SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type IN (11,12) AND Avion='$Avion1' AND Avion_Nbr=1");
                        mysqli_close($con);
                        if ($result_crash) {
                            while ($data_crash = mysqli_fetch_array($result_crash, MYSQLI_NUM)) {
                                $Crash = $data_crash[0];
                            }
                            mysqli_free_result($result_crash);
                        }
                        if ($Crash > 0 and $Crash > $Reserve and $Credits >= 8) {
                            $Equipage = GetData("Pilote", "ID", $PlayerID, "Equipage");
                            UpdateCarac($Equipage, "Mecanique", 5, "Equipage");
                            UpdateData("Avion", "Reserve", 1, "ID", $Avion1);
                            $Credits = -8;
                            UpdateCarac($PlayerID, "Missions_Max", 1);
                            UpdateCarac($PlayerID, "Reputation", 10);
                            UpdateCarac($PlayerID, "Avancement", 10);
                            UpdateCarac($PlayerID, "Note", 1);
                            $mes = "Votre mécano se met directement au travail.";
                            $img_txt = "repare" . $country;
                        } else {
                            $mes = "Votre mécano vous signale que la réparation est impossible.";
                            $img_txt = 'transfer_no' . $country;
                        }
                    }
                    break;
                case 42:
                    if ($Avion2 > 0) {
                        $Reserve = GetData("Avion", "ID", $Avion2, "Reserve");
                        $con = dbconnecti(4);
                        $result_crash = mysqli_query($con, "SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type IN (11,12) AND Avion='$Avion2' AND Avion_Nbr=1");
                        mysqli_close($con);
                        if ($result_crash) {
                            while ($data_crash = mysqli_fetch_array($result_crash, MYSQLI_NUM)) {
                                $Crash = $data_crash[0];
                            }
                            mysqli_free_result($result_crash);
                        }
                        if ($Crash > 0 and $Crash > $Reserve and $Credits >= 8) {
                            $Equipage = GetData("Pilote", "ID", $PlayerID, "Equipage");
                            UpdateCarac($Equipage, "Mecanique", 5, "Equipage");
                            UpdateData("Avion", "Reserve", 1, "ID", $Avion2);
                            $Credits = -8;
                            UpdateCarac($PlayerID, "Missions_Max", 1);
                            UpdateCarac($PlayerID, "Reputation", 10);
                            UpdateCarac($PlayerID, "Avancement", 10);
                            UpdateCarac($PlayerID, "Note", 1);
                            $mes = "Votre mécano se met directement au travail.";
                            $img_txt = "repare" . $country;
                        } else {
                            $mes = "Votre mécano vous signale que la réparation est impossible.";
                            $img_txt = 'transfer_no' . $country;
                        }
                    }
                    break;
                case 43:
                    if ($Avion3 > 0) {
                        $Reserve = GetData("Avion", "ID", $Avion3, "Reserve");
                        $con = dbconnecti(4);
                        $result_crash = mysqli_query($con, "SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type IN (11,12) AND Avion='$Avion3' AND Avion_Nbr=1");
                        mysqli_close($con);
                        if ($result_crash) {
                            while ($data_crash = mysqli_fetch_array($result_crash, MYSQLI_NUM)) {
                                $Crash = $data_crash[0];
                            }
                            mysqli_free_result($result_crash);
                        }
                        if ($Crash > 0 and $Crash > $Reserve and $Credits_ori >= 8) {
                            $Equipage = GetData("Pilote", "ID", $PlayerID, "Equipage");
                            UpdateCarac($Equipage, "Mecanique", 5, "Equipage");
                            UpdateData("Avion", "Reserve", 1, "ID", $Avion3);
                            $Credits = -8;
                            UpdateCarac($PlayerID, "Missions_Max", 1);
                            UpdateCarac($PlayerID, "Reputation", 10);
                            UpdateCarac($PlayerID, "Avancement", 10);
                            UpdateCarac($PlayerID, "Note", 1);
                            $mes = "Votre mécano se met directement au travail.";
                            $img_txt = "repare" . $country;
                        } else {
                            $mes = "Votre mécano vous signale que la réparation est impossible.";
                            $img_txt = 'transfer_no' . $country;
                        }
                    }
                    break;
                case 46:
                    $Repa = 5 + (($Personnel[12] + $Pers_Sup) * 5 * $Bonus_Pers);
                    UpdateData("Lieu", "Tour", $Repa, "ID", $Base, 100);
                    $Credits = -4;
                    $img_txt = 'gestion_piste' . $country;
                    break;
                case 47:
                    $mes = "<p>Vous êtes invité à choisir votre future affectation.<br>Celle-ci devra être validée par votre état-major avant d'être effective.</p>";
                    $menu = "<a href='index.php?view=transfer' title='Demande de mutation' class='btn btn-default'>Accéder au formulaire de demande de mutation</a>";
                    $img_txt = 'transfer_yes' . $country;
                    break;
                case 48:
                    $Repa = 50 + (($Personnel[3] + $Pers_Sup) * 50 * $Bonus_Pers);
                    $con = dbconnecti();
                    $reset = mysqli_query($con, "UPDATE Pilote_IA SET Moral=Moral+'$Repa',Courage=Courage+'$Repa' WHERE Unit='$Unite'");
                    mysqli_close($con);
                    if ($Pilote_Salon)
                        $Credits = -1;
                    elseif ($Assis_Bureau)
                        $Credits = -2;
                    else
                        $Credits = -5;
                    UpdateCarac($PlayerID, "Reputation", 5);
                    UpdateCarac($PlayerID, "Avancement", -1);
                    $mes = "<p>Les pilotes de votre escadrille chantent en votre honneur!</p>";
                    $img_txt = 'fiesta' . $country;
                    break;
                case 49:
                    $con = dbconnecti();
                    $reset = mysqli_query($con, "UPDATE Pilote_IA SET Couverture=0,Couverture_Nuit=0,Escorte=0,Cible=0,Avion=0,Alt=0,Task=0 WHERE Unit='$Unite'");
                    mysqli_close($con);
                    $Credits = -1;
                    $img_txt = 'gestion_avions' . $country;
                    break;
                case 50:
                    $img_txt = "stimulant";
                    if ($Endurance > 5) {
                        $mes .= "<p>Le médecin de la base vous prescrit un remède de cheval ! Seul soucis,vous n'êtes pas un cheval...</p>";
                        $con = dbconnecti();
                        $reset = mysqli_query($con, "UPDATE Pilote SET Moral=Moral-50,Courage=Courage-50,Endurance=Endurance-5 WHERE ID='$PlayerID'");
                        mysqli_close($con);
                        $Credits = 4;
                    } else
                        $mes .= "<p>Vous êtes trop fatigué pour supporter le traitement!</p>";
                    break;
                case 948:
                    $con = dbconnecti();
                    $reset = mysqli_query($con, "UPDATE Pilote_IA SET Moral=255,Courage=255,Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Cible=0,Avion=0,Alt=0 WHERE Unit='$Unite'");
                    mysqli_close($con);
                    if ($Pilote_Salon)
                        $Credits = -1;
                    elseif ($Assis_Bureau)
                        $Credits = -15;
                    else
                        $Credits = -30;
                    UpdateCarac($PlayerID, "Reputation", 10);
                    UpdateCarac($PlayerID, "Avancement", -1);
                    $mes = "<p>Les pilotes de votre escadrille sont rappelés à terre pour faire la fête au mess!<br>Aucun avion,et surtout aucun pilote,n'est en vol jusqu'à nouvel ordre.</p>";
                    $img_txt = 'fiesta' . $country;
                    break;
                default:
                    $mes = "<p>Besoin de lunettes?</p>";
                    $menu = '<a class="btn btn-default" title="Retour à l\'escadrille" href="index.php?view=escadrille">Retour à l\'escadrille</a>';
                    $img = "<img src='images/tsss.jpg'>";
                    break;
            }
            if ($Action) {
                MoveCredits($PlayerID, 2, $Credits);
                $img = "<img src='images/" . $img_txt . ".jpg'>";
                if (!$mes) $mes = "Vous avez le sentiment du devoir accompli.";
                if (!$menu) $menu = "<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='-1'><input class='btn btn-default' type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                $menu .= $garage;
            }
        } else {
            $titre = "MIA";
            $mes = "<h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
            $img = "<img src='images/unites" . $country . ".jpg'>";
        }
    }
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once './index.php';