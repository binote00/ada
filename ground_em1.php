<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once './jfv_include.inc.php';
    include_once './jfv_txt.inc.php';
    include_once './jfv_inc_em.php';
    if (($OfficierEMID == $Commandant or $OfficierEMID == $Officier_EM or $GHQ or $Admin) and $Front != 12) {
        $Lieu_g = Insec($_POST['lieu']);
        if ($Lieu_g) {
            $Mode = Insec($_POST['mode']);
            $query = "SELECT ID,Nom,Zone,DefenseAA,DefenseAA_temp,ValeurStrat,BaseAerienne,QualitePiste,LongPiste,Usine_muns,Industrie,NoeudF_Ori,Pont_Ori,Port_Ori,Radar_Ori,Pont,Port,NoeudF,Tour,Radar,Fortification,Garnison,Recce,Auto_repare,Depot_prive,Flag,Flag_Air,Flag_Gare,Flag_Pont,Flag_Port,Flag_Usine FROM Lieu WHERE ID='$Lieu_g'";
            $con = dbconnecti();
            $Faction = mysqli_result(mysqli_query($con, "SELECT Faction FROM Pays WHERE ID='$country'"), 0);
            $result = mysqli_query($con, $query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_em1-lieu');
            //$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu_g' AND r.Position<>25 AND r.Placement=0 AND r.Vehicule_Nbr >0"),0);
            $Enis = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu_g' AND r.Position<>25 AND r.Placement=0 AND r.Vehicule_Nbr >0"), 0);
            $DCA_Max = mysqli_result(mysqli_query($con, "SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag='$country'"), 0);
            $DCA_actu = mysqli_result(mysqli_query($con, "SELECT SUM(DefenseAA_temp) FROM Lieu WHERE Flag='$country'"), 0);
            $Lieux_rev = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Lieu WHERE Flag='$country' AND Zone<>6"), 0);
            mysqli_close($con);
            if ($result) {
                while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $ID_lieu = $data['ID'];
                    $Nom_lieu = $data['Nom'];
                    $Zone = $data['Zone'];
                    $DCA = $data['DefenseAA'];
                    $DCA_temp = $data['DefenseAA_temp'];
                    $ValStrat = $data['ValeurStrat'];
                    $BaseAerienne = $data['BaseAerienne'];
                    $LongPiste = $data['LongPiste'];
                    $QualitePiste = $data['QualitePiste'];
                    $Usine_muns = $data['Usine_muns'];
                    $Industrie = $data['Industrie'];
                    $Fortification = $data['Fortification'];
                    $Gare_Ori = $data['NoeudF_Ori'];
                    $Pont_Ori = $data['Pont_Ori'];
                    $Port_Ori = $data['Port_Ori'];
                    $Radar_Ori = $data['Radar_Ori'];
                    $Gare = $data['NoeudF'];
                    $Pont = $data['Pont'];
                    $Port = $data['Port'];
                    $Tour = $data['Tour'];
                    $Radar = $data['Radar'];
                    $Recce = $data['Recce'];
                    $Flag = $data['Flag'];
                    $Flag_Air = $data['Flag_Air'];
                    $Flag_Gare = $data['Flag_Gare'];
                    $Flag_Pont = $data['Flag_Pont'];
                    $Flag_Port = $data['Flag_Port'];
                    $Flag_Usine = $data['Flag_Usine'];
                    $Garnison = $data['Garnison'];
                    $Auto_repare = $data['Auto_repare'];
                    $Depot_prive = $data['Depot_prive'];
                }
                mysqli_free_result($result);
            }
            $Enis += $Enis2;
            $DCA_p = 10 - $DCA_temp;
            $DCA_Max += ($Lieux_rev * 2);
            if ($Admin or $GHQ)
                $Ouvriers = 100;
            else
                $Ouvriers = GetDoubleData("Pays", "Pays_ID", $country, "Front", $Front, "Pool_ouvriers");
            if (!$Officier_EM) $EM_CT = 1; //Coût réduit si pas d'EM infras
            $Faction_Flag = GetData("Pays", "ID", $Flag, "Faction");
            if ($Faction_Flag == $Faction and $Zone != 6) {
                $Garnison_Max = ($ValStrat * 100) + 100;
                if ($Garnison < $Garnison_Max and !$Enis and $Mode == 1) {
                    $garn_txt = "<br>Envoyer <select name='garnison' style='width: 100px'><option value='0'>0 escouades</option>";
                    if ($Garnison < $Garnison_Max and $Credits >= GetModCT(2, $country, $EM_CT))
                        $garn_txt .= "<option value='1'>1 escouade (" . GetModCT(2, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.9) and $Credits >= GetModCT(4, $country, $EM_CT))
                        $garn_txt .= "<option value='2'>2 escouades (" . GetModCT(4, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.8) and $Credits >= GetModCT(6, $country, $EM_CT))
                        $garn_txt .= "<option value='3'>3 escouades (" . GetModCT(6, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.7) and $Credits >= GetModCT(8, $country, $EM_CT))
                        $garn_txt .= "<option value='4'>4 escouades (" . GetModCT(8, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.6) and $Credits >= GetModCT(10, $country, $EM_CT))
                        $garn_txt .= "<option value='5'>5 escouades (" . GetModCT(10, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.5) and $Credits >= GetModCT(12, $country, $EM_CT))
                        $garn_txt .= "<option value='6'>6 escouades (" . GetModCT(12, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.4) and $Credits >= GetModCT(14, $country, $EM_CT))
                        $garn_txt .= "<option value='7'>7 escouades (" . GetModCT(14, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.3) and $Credits >= GetModCT(16, $country, $EM_CT))
                        $garn_txt .= "<option value='8'>8 escouades (" . GetModCT(16, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.2) and $Credits >= GetModCT(18, $country, $EM_CT))
                        $garn_txt .= "<option value='9'>9 escouades (" . GetModCT(18, $country, $EM_CT) . " CT)</option>";
                    if ($Garnison < ($Garnison_Max * 0.1) and $Credits >= GetModCT(20, $country, $EM_CT))
                        $garn_txt .= "<option value='10'>10 escouades (" . GetModCT(20, $country, $EM_CT) . " CT)</option>";
                    $garn_txt .= "</select> pour renforcer la garnison";
                }
                if ($DCA_actu < $DCA_Max and $Mode == 1) {
                    $dca_txt = "<br>Augmenter la DCA de
					<select name='dca' style='width: 50px'><option value='0'>0</option>";
                    if ($DCA_temp > 0) {
                        $Cout_Fort = GetModCT(10 * $DCA_temp, $country, $EM_CT);
                        if ($DCA_p >= 1 and $Ouvriers >= $Cout_Fort)
                            $dca_txt .= "<option value='1'>1 (" . $Cout_Fort . " ouvriers)</option>";
                        $Cout_Fort = (20 * $DCA_temp);
                        if ($DCA_p >= 2 and $Ouvriers >= $Cout_Fort)
                            $dca_txt .= "<option value='2'>2 (" . $Cout_Fort . " ouvriers)</option>";
                        $Cout_Fort = (30 * $DCA_temp);
                        if ($DCA_p >= 3 and $Ouvriers >= $Cout_Fort)
                            $dca_txt .= "<option value='3'>3 (" . $Cout_Fort . " ouvriers)</option>";
                        $Cout_Fort = (40 * $DCA_temp);
                        if ($DCA_p >= 4 and $Ouvriers >= $Cout_Fort)
                            $dca_txt .= "<option value='4'>4 (" . $Cout_Fort . " ouvriers)</option>";
                    } else {
                        $Cout_Fort = GetModCT(10 * ($DCA_temp + 1), $country, $EM_CT);
                        if ($DCA_p >= 1 and $Ouvriers >= $Cout_Fort)
                            $dca_txt .= "<option value='1'>1 (" . $Cout_Fort . " ouvriers)</option>";
                    }
                    $dca_txt .= "</select> niveau (Total nation " . $DCA_actu . "/" . $DCA_Max . ")";
                } else
                    $dca_txt .= "<br>Toutes les pièces de DCA ont déjà été attribuées!";
                if (!$Enis) {
                    $fort_txt = "<br>Augmenter les fortifications de
					<select name='fort' style='width: 50px'><option value='0'>0</option>";
                    $Fort = $Fortification / 10;
                    if ($Fortification > 0) {
                        if ($Fortification > 50)
                            $Cout_Fort = GetModCT(100, $country, $EM_CT) * $Fort;
                        else
                            $Cout_Fort = GetModCT(50, $country, $EM_CT) * $Fort;
                        if ($Fortification < 90 and $Ouvriers >= $Cout_Fort)
                            $fort_txt .= "<option value='1'>10 (" . $Cout_Fort . " ouvriers)</option>";
                        $Cout_Fort = (100 * $Fort);
                        if ($Fortification < 80 and $Ouvriers >= $Cout_Fort)
                            $fort_txt .= "<option value='2'>20 (" . $Cout_Fort . " ouvriers)</option>";
                        $Cout_Fort = (150 * $Fort);
                        if ($Fortification < 70 and $Ouvriers >= $Cout_Fort)
                            $fort_txt .= "<option value='3'>30 (" . $Cout_Fort . " ouvriers)</option>";
                        $Cout_Fort = (200 * $Fort);
                        if ($Fortification < 60 and $Ouvriers >= $Cout_Fort)
                            $fort_txt .= "<option value='4'>40 (" . $Cout_Fort . " ouvriers)</option>";
                    } else {
                        $Cout_Fort = GetModCT(50 * ($Fort + 1), $country, $EM_CT);
                        if ($Ouvriers >= $Cout_Fort)
                            $fort_txt .= "<option value='1'>10 (" . $Cout_Fort . " ouvriers)</option>";
                    }
                    $fort_txt .= "</select>%";
                } else
                    $fort_txt .= "<br>La présence d'unités ennemies empêche les travaux!";
                if ($BaseAerienne and !$Enis and ($Flag_Air == $country or $Mode == 2)) {
                    if ($Zone == 3 or $Zone == 4 or $Zone == 5 or $Zone == 7 or $Zone == 9)
                        $LongPisteMax = 1151;
                    elseif ($Zone == 1 or $Zone == 2 or $Zone == 11)
                        $LongPisteMax = 1351;
                    else
                        $LongPisteMax = 1951;
                    if ($Tour < 100) {
                        $piste_txt = "<img src='images/vehicules/vehicule2.gif' title='Tour'> <img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
						<select name='tour' style='width: 100px'>		
							<option value='0'>0</option>";
                        if ($Tour < 90 and $Credits >= 2 and $Ouvriers > GetModCT(9, $country, $EM_CT)) {
                            $piste_txt .= "<option value='1'>10</option>";
                        }
                        if ($Tour < 80 and $Credits >= 2 and $Ouvriers > GetModCT(19, $country, $EM_CT)) {
                            $piste_txt .= "<option value='2'>20</option>";
                        }
                        if ($Tour < 70 and $Credits >= 2 and $Ouvriers > GetModCT(29, $country, $EM_CT)) {
                            $piste_txt .= "<option value='3'>30</option>";
                        }
                        if ($Tour < 60 and $Credits >= 2 and $Ouvriers > GetModCT(39, $country, $EM_CT)) {
                            $piste_txt .= "<option value='4'>40</option>";
                        }
                        if ($Tour < 50 and $Credits >= 2 and $Ouvriers > GetModCT(49, $country, $EM_CT))
                            $piste_txt .= "<option value='5'>50</option>";
                        $piste_txt .= "</select> ouvriers pour accélérer la réparation de la tour";
                    }
                    if ($QualitePiste < 90) {
                        $piste_txt .= "<br><img src='images/bulldozer.png' title='Réparation Piste'> <img src='images/CT24.png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
						<select name='rpiste' style='width: 100px'>		
							<option value='0'>0</option>";
                        if ($Credits >= 24 and $Ouvriers > GetModCT(99, $country, $EM_CT))
                            $piste_txt .= "<option value='1'>100</option>";
                        if ($Credits >= 24 and $Ouvriers > GetModCT(199, $country, $EM_CT) and $QualitePiste < 50)
                            $piste_txt .= "<option value='2'>200</option>";
                        if ($Credits >= 24 and $Ouvriers > GetModCT(299, $country, $EM_CT) and $QualitePiste < 10)
                            $piste_txt .= "<option value='3'>300</option>";
                        $piste_txt .= "</select> ouvriers pour réparer la piste (" . $QualitePiste . "% actuellement + 20% par 100 ouvriers)";
                    }
                    if ($LongPiste < $LongPisteMax and $QualitePiste == 100) {
                        $piste_txt .= "<br><img src='images/pistel.png' title='Piste'> <img src='images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
						<select name='piste' style='width: 100px'>		
							<option value='0'>0</option>";
                        if ($Credits >= 30 and $Ouvriers > GetModCT(99, $country, $EM_CT))
                            $piste_txt .= "<option value='1'>100</option>";
                        $piste_txt .= "</select> ouvriers pour agrandir la piste (" . $LongPiste . "m actuellement) <a href='#' class='popup'><img src='images/help.png'><span>La piste doit être à 100% pour pouvoir agrandir</span></a>";
                    }
                    $ouvriers_piste_dur = GetModCT(199, $country, $EM_CT);
                    if ($BaseAerienne == 3 and $QualitePiste == 100 and $Ouvriers >= $ouvriers_piste_dur and $Credits >= $CT_MAX) {
                        $piste_txt .= "<br><img src='images/pisted.png' title='Piste'> <img src='images/CT" . $CT_MAX . ".png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
						<select name='dur' style='width: 100px'><option value='0'>0</option><option value='1'>" . $ouvriers_piste_dur . "</option></select> ouvriers pour poser un revêtement en dur sur la piste 
						<a href='#' class='popup'><img src='images/help.png'><span>Une piste en dur permet aux avions de décoller par temps de pluie. La piste doit être à 100%</span></a>";
                    } elseif ($BaseAerienne != 1 and $BaseAerienne != 2)
                        $piste_txt .= "<br><img src='images/pisted.png' title='Piste'> <img src='images/CT" . $CT_MAX . ".png' title='Montant en Crédits Temps que nécessite cette action'> + " . $ouvriers_piste_dur . " Ouvriers pour poser un revêtement en dur sur la piste";
                }
                if ($Radar_Ori > 0 and $Radar < 100 and ($Flag_Radar == $country or $Mode == 2)) {
                    $radar_txt = "<br><img src='images/vehicules/vehicule14.gif' title='Radar'> <img src='images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
					<select name='radar' style='width: 100px'>		
						<option value='0'>0</option>";
                    if ($Radar < 90 and $Credits >= 3 and $Ouvriers > GetModCT(19, $country, $EM_CT))
                        $radar_txt .= "<option value='1'>20</option>";
                    if ($Radar < 80 and $Credits >= 6 and $Ouvriers > GetModCT(39, $country, $EM_CT))
                        $radar_txt .= "<option value='2'>40</option>";
                    if ($Radar < 70 and $Credits >= 9 and $Ouvriers > GetModCT(59, $country, $EM_CT))
                        $radar_txt .= "<option value='3'>60</option>";
                    if ($Radar < 60 and $Credits >= 12 and $Ouvriers > GetModCT(79, $country, $EM_CT))
                        $radar_txt .= "<option value='4'>80</option>";
                    if ($Radar < 50 and $Credits >= 15 and $Ouvriers > GetModCT(99, $country, $EM_CT))
                        $radar_txt .= "<option value='5'>100</option>";
                    $radar_txt .= "</select>ouvriers pour accélérer la réparation du radar";
                }
                if ($Gare_Ori > 0 and $Gare < 100 and ($Flag_Gare == $country or $Mode == 2)) {
                    $gare_txt = "<br><img src='images/vehicules/vehicule9.gif' title='Gare'> <img src='images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
					<select name='gare' style='width: 100px'>		
						<option value='0'>0</option>";
                    if ($Gare < 90 and $Credits >= 3 and $Ouvriers > GetModCT(9, $country, $EM_CT))
                        $gare_txt .= "<option value='1'>10</option>";
                    if ($Gare < 80 and $Credits >= 6 and $Ouvriers > GetModCT(19, $country, $EM_CT))
                        $gare_txt .= "<option value='2'>20</option>";
                    if ($Gare < 70 and $Credits >= 9 and $Ouvriers > GetModCT(29, $country, $EM_CT))
                        $gare_txt .= "<option value='3'>30</option>";
                    if ($Gare < 60 and $Credits >= 12 and $Ouvriers > GetModCT(39, $country, $EM_CT))
                        $gare_txt .= "<option value='4'>40</option>";
                    if ($Gare < 50 and $Credits >= 15 and $Ouvriers > GetModCT(49, $country, $EM_CT))
                        $gare_txt .= "<option value='5'>50</option>";
                    $gare_txt .= "</select>ouvriers pour accélérer la réparation du noeud ferroviaire";
                }
                if ($Pont_Ori > 0 and $Pont < 100 and ($Flag_Pont == $country or $Mode == 2)) {
                    $pont_txt = "<br><img src='images/vehicules/vehicule10.gif' title='Pont'> <img src='images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
					<select name='pont' style='width: 100px'>		
						<option value='0'>0</option>";
                    if ($Pont < 90 and $Credits >= 3 and $Ouvriers > GetModCT(9, $country, $EM_CT))
                        $pont_txt .= "<option value='1'>10</option>";
                    if ($Pont < 80 and $Credits >= 6 and $Ouvriers > GetModCT(19, $country, $EM_CT))
                        $pont_txt .= "<option value='2'>20</option>";
                    if ($Pont < 70 and $Credits >= 9 and $Ouvriers > GetModCT(29, $country, $EM_CT))
                        $pont_txt .= "<option value='3'>30</option>";
                    if ($Pont < 60 and $Credits >= 12 and $Ouvriers > GetModCT(39, $country, $EM_CT))
                        $pont_txt .= "<option value='4'>40</option>";
                    if ($Pont < 50 and $Credits >= 15 and $Ouvriers > GetModCT(49, $country, $EM_CT))
                        $pont_txt .= "<option value='5'>50</option>";
                    $pont_txt .= "</select>ouvriers pour accélérer la réparation du pont";
                }
                if ($Port_Ori > 0 and $Port < 100 and ($Flag_Port == $country or $Mode == 2)) {
                    $port_txt = "<br><img src='images/vehicules/vehicule12.gif' title='Port'> <img src='images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
					<select name='port' style='width: 100px'>		
						<option value='0'>0</option>";
                    if ($Port < 90 and $Credits >= 3 and $Ouvriers > GetModCT(9, $country, $EM_CT))
                        $port_txt .= "<option value='1'>10</option>";
                    if ($Port < 80 and $Credits >= 6 and $Ouvriers > GetModCT(19, $country, $EM_CT))
                        $port_txt .= "<option value='2'>20</option>";
                    if ($Port < 70 and $Credits >= 9 and $Ouvriers > GetModCT(29, $country, $EM_CT))
                        $port_txt .= "<option value='3'>30</option>";
                    if ($Port < 60 and $Credits >= 12 and $Ouvriers > GetModCT(39, $country, $EM_CT))
                        $port_txt .= "<option value='4'>40</option>";
                    if ($Port < 50 and $Credits >= 15 and $Ouvriers > GetModCT(49, $country, $EM_CT))
                        $port_txt .= "<option value='5'>50</option>";
                    $port_txt .= "</select>ouvriers pour accélérer la réparation du port";
                }
                if ($Industrie > 0 and ($Flag_Usine == $country or $Mode == 2)) {
                    if ($Industrie < 100) {
                        $usine_txt = "<br><img src='images/vehicules/vehicule5.gif' title='Usine'> <img src='images/CT3.png' title='Montant en Crédits Temps que nécessite cette action'> Envoyer
						<select name='usine' style='width: 100px'><option value='0'>0</option>";
                        if ($Industrie < 100 and $Credits >= 3 and $Ouvriers > GetModCT(9, $country, $EM_CT))
                            $usine_txt .= "<option value='1'>10</option>";
                        if ($Industrie < 80 and $Credits >= 6 and $Ouvriers > GetModCT(19, $country, $EM_CT))
                            $usine_txt .= "<option value='2'>20</option>";
                        if ($Industrie < 70 and $Credits >= 9 and $Ouvriers > GetModCT(29, $country, $EM_CT))
                            $usine_txt .= "<option value='3'>30</option>";
                        if ($Industrie < 60 and $Credits >= 12 and $Ouvriers > GetModCT(39, $country, $EM_CT))
                            $usine_txt .= "<option value='4'>40</option>";
                        if ($Industrie < 50 and $Credits >= 15 and $Ouvriers > GetModCT(49, $country, $EM_CT))
                            $usine_txt .= "<option value='5'>50</option>";
                        $usine_txt .= "</select> ouvriers pour accélérer la réparation de l'usine";
                    } elseif ($Usine_muns > 0 and $Flag_Usine == $country) {
                        $usine_txt .= "<br><img src='images/ammo_icon.png' title='Usine armement'> Envoyer
						<select name='ouvriers' style='width: 100px'><option value='0'>0</option>";
                        if ($Ouvriers > 9)
                            $usine_txt .= "<option value='10'>10</option>";
                        if ($Ouvriers > 19)
                            $usine_txt .= "<option value='20'>20</option>";
                        if ($Ouvriers > 29)
                            $usine_txt .= "<option value='30'>30</option>";
                        if ($Ouvriers > 39)
                            $usine_txt .= "<option value='40'>40</option>";
                        if ($Ouvriers > 49)
                            $usine_txt .= "<option value='50'>50</option>";
                        $usine_txt .= "</select> ouvriers pour augmenter la production de l'usine";
                    }
                }
                if ($Recce > 0) {
                    $CT_Discount = Get_CT_Discount($Avancement);
                    $con = dbconnecti();
                    $Observation = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Pilote_IA WHERE Cible='$Lieu_g' AND Task=1 AND Avion>0 AND Pays<>'$country' AND Actif=1"), 0);
                    mysqli_close($con);
                    $Cr_cam = 7 + $Observation - $CT_Discount;
                    if ($Trait == 5) $Cr_cam -= 2;
                    if ($Cr_cam < 1) $Cr_cam = 1;
                    if ($Credits >= $Cr_cam) {
                        $reco_txt = "<br><img src='images/camouflage.png'> <img src='images/CT" . $Cr_cam . ".png' title='Montant en Crédits Temps que nécessite cette action'> Ordonner de camoufler le site d'urgence<br>
								<Input type='Radio' name='recce' value='0' checked>- Non<br>
								<Input type='Radio' name='recce' value='1'>- Oui<br>";
                    } else
                        $reco_txt = "<br><img src='images/camouflage.png'> <img src='images/CT" . $Cr_cam . ".png' title='Montant en Crédits Temps que nécessite cette action'> Ordonner de camoufler le site d'urgence<br>
						La présence d'avions d'observation ennemis empêche le camouflage ou vous ne disposez pas de suffisamment de CT pour effectuer cette action";
                }
                if ($Flag == $country and ($ValStrat or $BaseAerienne)) {
                    if ($Auto_repare)
                        $Auto_repare = "Réparation activée";
                    else
                        $Auto_repare = "Réparation annulée";
                    $repa_txt = "<div class='col-md-6'><img src='images/norepauto.png' title='Si le lieu a une valeur stratégique non nulle et que depuis au moins 3 jours ce lieu est vierge de toute attaque, les infrastructures détruites (0%) sont remises à 1%'> Annuler la réparation automatique des infrastructures (actuellement : " . $Auto_repare . ")<br>
							<Input type='Radio' name='auto_repa' value='0' checked>- Ne rien changer<br><Input type='Radio' name='auto_repa' value='2'>- Non<br><Input type='Radio' name='auto_repa' value='1'>- Oui<br></div>";
                    if ($ValStrat > 3) {
                        if ($Depot_prive)
                            $Depot_prive = "Inaccessible";
                        else
                            $Depot_prive = "Accessible";
                        $repa_txt .= "<div class='col-md-6'><img src='images/noravair.png'> Rendre le dépôt inaccessible aux unités aériennes (actuellement : " . $Depot_prive . ")<br>
								<Input type='Radio' name='depot_off' value='0' checked>- Ne rien changer<br><Input type='Radio' name='depot_off' value='2'>- Non<br><Input type='Radio' name='depot_off' value='1'>- Oui<br></div>";
                    }
                    if ($repa_txt) $repa_txt = "<hr><div class='row'>" . $repa_txt . "</div>";
                }
            }
            echo "<h1>" . $Nom_lieu . "</h1><h2>Le front dispose de " . $Ouvriers . " ouvriers disponibles</h2>
			<form action='index.php?view=ground_em2' method='post'><input type='hidden' name='lieu' value='" . $ID_lieu . "'><table class='table'>
				<tr><thead><th>DCA</th><th>Fortifications</th><th>Garnison <a href='help/aide_garnison.php' target='_blank' title='Aide'><img src='images/help.png'></a></th></thead></tr>
				<tr><th><a href='#' class='popup'><img src='images/vehicules/vehicule16.gif'> Niveau " . $DCA_temp . "<span>La nation a installé " . $DCA_actu . " pièces de DCA sur un total de " . $DCA_Max . " disponibles</span></th>
				<th><img src='images/icone_fort.gif' title='Fortifications de la caserne'> Niveau " . $Fortification . "</th>
				<th><img src='images/vehicules/vehicule107.gif' title='Garnison'> " . $Garnison . " hommes</th></tr>
				<tr><td>" . $dca_txt . "</td><td>" . $fort_txt . "</td><td>" . $garn_txt . "</td></tr>
			</table>
			" . $radar_txt . $gare_txt . $port_txt . $usine_txt . $piste_txt . $reco_txt . $repa_txt . "
			<br><input type='submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>
			<p><a href='index.php?view=ground_em_infras' class='btn btn-default' title='Retour'>Retour au menu</a></p>";
        }//lieu_g
    } else
        echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";