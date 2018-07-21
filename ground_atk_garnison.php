<?php
require_once './jfv_inc_sessions.php';
include_once './jfv_include.inc.php';
include_once './jfv_txt.inc.php';
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierID > 0 xor $OfficierEMID > 0) {
    $Reg = Insec($_POST['Reg']);
    $Cible = Insec($_POST['Cible']);
    $Vehicule = Insec($_POST['Veh']);
    $Mode = Insec($_POST['Mode']);
    if ($Reg and $Cible and $Vehicule) {
        include_once './jfv_combat.inc.php';
        include_once './jfv_ground.inc.php';
        $country = $_SESSION['country'];
        $DB = "Regiment_IA";
        $Demo_bonus = 1;
        $con = dbconnecti();
        $resultr = mysqli_query($con, "SELECT Experience,Skill FROM Regiment_IA WHERE ID = $Reg");
        $result = mysqli_query($con, "SELECT Nom,Latitude,Longitude,Zone,ValeurStrat,Camouflage,Meteo,Recce,Fortification,Garnison,Flag FROM Lieu WHERE ID = $Cible");
        $result2 = mysqli_query($con, "SELECT * FROM Cible WHERE ID = $Vehicule");
        mysqli_close($con);
        if ($resultr) {
            while ($datar = mysqli_fetch_array($resultr, MYSQLI_ASSOC)) {
                $Exp = floor(($datar['Experience'] / 10) + 10);
                $Skill = $datar['Skill'];
            }
            mysqli_free_result($resultr);
        }
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Lieu_Nom = $data['Nom'];
                $Lat_base = $data['Latitude'];
                $Long_base = $data['Longitude'];
                $Zone = $data['Zone'];
                $ValStrat = $data['ValeurStrat'];
                $meteo = $data['Meteo'];
                $Camouflage_lieu = $data['Camouflage'];
                $Recce_Lieu = $data['Recce'];
                $Garnison = $data['Garnison'];
                $Fortification = $data['Fortification'];
                $Flag = $data['Flag'];
            }
            mysqli_free_result($result);
            unset($data);
        }
        if ($result2) {
            while ($data = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                $HP = $data['HP'];
                $HP_ori = $HP;
                $Arme_Inf = $data['Arme_Inf'];
                $Arme_AT = $data['Arme_AT'];
                $Arme_Art = $data['Arme_Art'];
                $Blindage = $data['Blindage_f'];
                $Vitesse = $data['Vitesse'];
                $Taille = $data['Taille'];
                $Detection = $data['Detection'];
                $mobile = $data['mobile'];
                $Reput = $data['Reput'];
                $Categorie = $data['Categorie'];
                $Type = $data['Type'];
                $Vitesse = Get_LandSpeed($Vitesse, $mobile, $Zone, 0, $Type);
                if (!$Blindage) $Blindage = Get_Blindage($Zone, $Taille, 0, 4);
            }
            mysqli_free_result($result2);
            unset($data);
        }
        if ($Categorie == 5) {
            if ($Skill == 119) {
                $Fortification -= 4;
                $Demo_bonus = 2;
            } elseif ($Skill == 118) {
                $Fortification -= 3;
                $Demo_bonus = 1.75;
            } elseif ($Skill == 117) {
                $Fortification -= 2;
                $Demo_bonus = 1.5;
            } elseif ($Skill == 17) {
                $Fortification -= 1;
                $Demo_bonus = 1.25;
            }
        }
        if ($Fortification < 0) $Fortification = 0;
        $Def_cote = $ValStrat + ($Fortification / 10);
        if ($Def_cote >= 10) {
            if ($Pays_cible == 1) //150mm 24k
                $arme_c = 324;
            elseif ($Pays_cible == 2)
                $arme_c = 407;
            elseif ($Pays_cible == 4)
                $arme_c = 355;
            elseif ($Pays_cible == 6)
                $arme_c = 138;
            elseif ($Pays_cible == 7)
                $arme_c = 372;
            elseif ($Pays_cible == 8)
                $arme_c = 189;
            elseif ($Pays_cible == 9)
                $arme_c = 367;
            else
                $arme_c = 324;
        } elseif ($Def_cote >= 8) //150mm 12k
        {
            if ($Pays_cible == 1)
                $arme_c = 138;
            elseif ($Pays_cible == 2)
                $arme_c = 201;
            elseif ($Pays_cible == 4)
                $arme_c = 327;
            elseif ($Pays_cible == 6)
                $arme_c = 87;
            elseif ($Pays_cible == 7)
                $arme_c = 201;
            elseif ($Pays_cible == 8)
                $arme_c = 294;
            elseif ($Pays_cible == 9)
                $arme_c = 368;
            else
                $arme_c = 201;
        } elseif ($Def_cote >= 6) //105mm 10k
        {
            if ($Pays_cible == 1)
                $arme_c = 137;
            elseif ($Pays_cible == 2)
                $arme_c = 101;
            elseif ($Pays_cible == 4)
                $arme_c = 126;
            elseif ($Pays_cible == 6)
                $arme_c = 402;
            elseif ($Pays_cible == 7)
                $arme_c = 308;
            elseif ($Pays_cible == 8)
                $arme_c = 150;
            elseif ($Pays_cible == 9)
                $arme_c = 369;
            else
                $arme_c = 126;
        } elseif ($Def_cote >= 4) //75mm 5k
        {
            if ($Pays_cible == 1)
                $arme_c = 89;
            elseif ($Pays_cible == 2)
                $arme_c = 265;
            elseif ($Pays_cible == 4)
                $arme_c = 96;
            elseif ($Pays_cible == 6)
                $arme_c = 124;
            elseif ($Pays_cible == 7)
                $arme_c = 272;
            elseif ($Pays_cible == 8)
                $arme_c = 148;
            elseif ($Pays_cible == 9)
                $arme_c = 366;
            else
                $arme_c = 96;
        } else {
            if ($Pays_cible == 1) //20mm 1k
                $arme_c = 300;
            elseif ($Pays_cible == 2)
                $arme_c = 220;
            elseif ($Pays_cible == 4)
                $arme_c = 33;
            elseif ($Pays_cible == 6)
                $arme_c = 46;
            elseif ($Pays_cible == 7)
                $arme_c = 17;
            elseif ($Pays_cible == 8)
                $arme_c = 316;
            elseif ($Pays_cible == 9)
                $arme_c = 205;
            else
                $arme_c = 220;
        }
        //Défense
        if ($arme_c > 0) {
            $intro .= '<br><b>La défense rapprochée ouvre le feu sur vous!</b>';
            $img = Afficher_Image('images/attack.jpg', 'images/image.png', '');
            $dca_max = $Def_cote * 10;
            if ($dca_max > 250) $dca_max = 250;
            $Shoot_rand = mt_rand(10, 50) + mt_rand(0, $dca_max);
            $Shoot = $Shoot_rand + ($meteo / 10) + $Taille - $Vitesse;
            if ($Shoot > 10 or $Shoot_rand > 250) {
                $con = dbconnecti();
                $result = mysqli_query($con, "SELECT Degats,Multi FROM Armes WHERE ID='$arme_c'");
                mysqli_close($con);
                if ($result) {
                    while ($datab = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $Armeni_Degats = $datab['Degats'];
                        $Armeni_Multi = $datab['Multi'];
                    }
                    mysqli_free_result($result);
                    unset($datab);
                }
                $Degats = (mt_rand(0, $Armeni_Degats) - $Blindage) * GetShoot($Shoot, $Armeni_Multi);
                if ($Degats < 1) $Degats = mt_rand(1, 10);
                $HP -= $Degats;
                if ($HP < 1) {
                    $intro .= '<br>Le tir ennemi détruit une de vos unités. (' . $Degats . ' points de dégats!)';
                    UpdateData($DB, "Vehicule_Nbr", -1, "ID", $Reg);
                    AddEventGround(400, $Vehicule, $OfficierEMID, $Reg, $Cible, 1, 0);
                    $HP = $HP_ori;
                } else
                    $intro .= '<br>Le tir ennemi endommage une de vos unités, lui occasionnant <b>' . $Degats . '</b> points de dégats!';
            } else
                $intro .= "<br>Les explosions d'obus encadrent votre unité!";
        }
        $Blindage_eni = Get_Blindage($Zone, 2, $Fortification, 2);
        if ($mobile == 5)
            $Arme = $Arme_Art;
        elseif ($Blindage_eni > 0 and $Arme_AT > 0)
            $Arme = $Arme_AT;
        elseif ($Arme_Art > 0)
            $Arme = $Arme_Art;
        else
            $Arme = $Arme_Inf;
        $Vehicule_Nbr = GetData($DB, "ID", $Reg, "Vehicule_Nbr");
        if (!$Vehicule_Nbr or !$Vehicule or !$Arme)
            $intro .= "<br>Votre unité a été décimée, l'attaque est stoppée!";
        else {
            $con = dbconnecti();
            $result = mysqli_query($con, "SELECT Nom,Calibre,Degats,Multi FROM Armes WHERE ID='$Arme'");
            mysqli_close($con);
            if ($result) {
                while ($datab = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Arme_Nom = $datab['Multi'];
                    $ArmeAvion_Dg = $datab['Degats'];
                    $ArmeAvion_Multi = $datab['Multi'];
                    $Arme_Cal = round($datab['Calibre']);
                }
                mysqli_free_result($result);
                unset($datab);
            }
            if ($Arme == 82 or $Arme == 136) {
                $Avion_Mun = 2;
                $Blindage_eni = 0;
            }
            if ($ArmeAvion_Multi < 1) $ArmeAvion_Multi = 1;
            if ($OfficierID > 0 and IsSkill(37, $OfficierID)) {
                $Exp += 25;
                $ArmeAvion_Multi += 1;
            }
            $Shoot = mt_rand(0, $Exp) + ($meteo / 10) - ($Blindage_eni / 10);
            if ($Canada) {
                if (mt_rand(0, 100) > 10) $Shoot = 0;
            }
            if ($Shoot > 0) {
                $Degats = 0;
                if ($Vehicule_Nbr > 25)
                    $Vehicule_Nbr_shoot = floor($Vehicule_Nbr / 10);
                else
                    $Vehicule_Nbr_shoot = $Vehicule_Nbr;
                if ($Arme_Cal > $Blindage_eni or $Arme_Cal > 70) {
                    if (!$Avion_Mun) $Avion_Mun = GetData($DB, "ID", $Reg, "Muns");
                    $Bonus_Dg = Damage_Bonus("Regiment_IA", 1, $dmg_bonus_cible, $Arme, $Blindage_eni, $Avion_Mun);
                    if ($Categorie == 5) {
                        $Bonus_Dg *= 2;
                        if ($OfficierID > 0 and IsSkill(17, $OfficierID)) {
                            if (GetData("Officier", "ID", $OfficierID, "Trait") == 2)
                                $Bonus_Dg *= 4;
                            else
                                $Bonus_Dg *= 2;
                            $intro .= "<p>Vos troupes bénéficient de votre compétence <b>Démolition</b> !</p>";
                        } elseif ($Demo_bonus)
                            $Bonus_Dg *= $Demo_bonus;
                    }
                    for ($i = 1; $i <= $Vehicule_Nbr_shoot; $i++) {
                        $Degats += round((mt_rand(1, $ArmeAvion_Dg) + $Bonus_Dg - $Blindage_eni) * mt_rand(1, $ArmeAvion_Multi));
                    }
                    if ($Degats < 1) $Degats = mt_rand(1, 10);
                    $intro .= '<p>Le tir de votre unité fait mouche! (<b>' . $Degats . '</b> dégâts)</p>';
                } else {
                    $Degats = mt_rand(1, $Vehicule_Nbr_shoot);
                    $intro .= '<p>Le tir de votre unité fait mouche, mais les projectiles ricochent sur le blindage! (<b>' . $Degats . '</b> dégâts)</p>';
                }
                if ($Degats >= 100) {
                    /*if($OfficierEMID)
                        $Reg_a_ia=1;
                    else
                        $Reg_a_ia=0;*/
                    $Kills = floor($Degats / 100);
                    if ($Kills > 50) $Kills = 50;
                    if ($Kills > $Garnison) $Kills = $Garnison;
                    AddGroundAtk($Reg, 0, $Vehicule, $Vehicule_Nbr, 48, $Garnison, 4, 2, $Cible, 0, 0, $Kills, 1);
                    if ($Mode == 38) {
                        UpdateData("Lieu", "Garnison", -$Kills, "ID", $Cible);
                        $introhit = '<p>Vous éliminez une partie de la garnison</p>';
                    } elseif ($Mode == 48) {
                        $con = dbconnecti();
                        $Esc_eni = mysqli_result(mysqli_query($con, "SELECT ID FROM Unit WHERE Base='$Cible' AND Pays<>'$country' AND Etat=1 AND Garnison >0 ORDER BY RAND() LIMIT 1"), 0);
                        mysqli_close($con);
                        if ($Esc_eni) {
                            UpdateData("Unit", "Garnison", -$Kills, "ID", $Esc_eni);
                            $introhit = "<p>Vous éliminez une partie des troupes de défense de l'aérodrome</p>";
                        } else
                            $introhit = "<p>Aucune troupe ne semble défendre cet aérodrome!</p>";
                    }
                } else
                    $introhit = "<p>Votre attaque manque de puissance, vous ne parvenez pas à détruire votre cible!</p>";

            } else
                $introhit = "<p>Votre attaque est inefficace, manquant de précision!</p>";
            $intro .= $introhit;
            $con = dbconnecti();
            $reset = mysqli_query($con, "UPDATE $DB SET Visible=1,Experience=Experience+1,Atk=1,Atk_time=NOW() WHERE ID = $Reg");
            mysqli_close($con);
        }
        $titre = 'Assaut';
        $mes .= "<h2>Attaque de la garnison " . $Lieu_Nom . "</h2><table class='table'>
			<thead><tr><th>Vos Troupes</th><th>Armement</th><th>Expérience</th><th>Terrain</th><th>Météo</th></tr></thead>
			<tr><td><img src='images/vehicules/vehicule" . $Vehicule . ".gif'></td><td>" . $Arme_Nom . "</td><td>" . $Exp . "</td><td><img src='images/zone" . $Zone . ".jpg'></td><td><img src='images/meteo" . $meteo . ".gif'></td></tr>
			</table>";
        if ($OfficierEMID > 0) {
            mail(EMAIL_LOG, 'Aube des Aigles: Assaut Forteresse', $mes, "MIME-Version: 1.0" . "\r\n 'Content-type: text/html; charset=utf-8'\r\n");
            $menu = "<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='" . $Reg . "'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
        }
        include_once './default.php';
    }
} else {
    $mes = GetMes("init_mission");
    $view = 'login';
    session_unset();
    session_destroy();
}