<?php
require_once './jfv_inc_sessions.php';
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0 xor $OfficierID > 0) {
    include_once './jfv_include.inc.php';
    include_once './jfv_txt.inc.php';
    include_once './jfv_ground.inc.php';
    include_once './jfv_combat.inc.php';
    $debug = 0;
    $country = $_SESSION['country'];
    $CT = Insec($_POST['CT']);
    $Reg = Insec($_POST['Reg']);
    $Veh = Insec($_POST['Veh']);
    /*if($OfficierID >0)
    {
        $con=dbconnecti();
        $resulto=mysqli_query($con,"SELECT Front,Credits,Trait FROM Officier WHERE ID='$OfficierID'");
        mysqli_close($con);
        if($resulta)
        {
            while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
            {
                $Front=$datao['Front'];
                $Credits=$datao['Credits'];
                $Trait_o=$datao['Trait'];
            }
            mysqli_free_result($resulto);
        }
        if($CT >0)
            $DB_Reg='Regiment';
        else
        {
            SetData("Regiment_IA","Move",1,"ID",$Reg);
            $DB_Reg='Regiment_IA';
            if($Veh <5165)$CT=4; //No Sub
            $Reg_b_ia=1;
        }
    }
    else*/
    if ($OfficierEMID > 0 and !$CT) {
        $mobile = GetData("Cible", "ID", $Veh, "mobile");
        if ($mobile != 5) {
            $con = dbconnecti();
            $resulto = mysqli_query($con, "SELECT Front,Credits,Trait FROM Officier_em WHERE ID='$OfficierEMID'");
            mysqli_close($con);
            if ($resulta) {
                while ($datao = mysqli_fetch_array($resulto, MYSQLI_ASSOC)) {
                    $Front = $datao['Front'];
                    $Credits = $datao['Credits'];
                    $Trait = $datao['Trait'];
                }
                mysqli_free_result($resulto);
            }
            /*if($Trait ==13)
                $CT=1;
            else
                $CT=2;*/
        }
        $Reg_b_ia = 1;
        $DB_Reg = 'Regiment_IA';
        SetData("Regiment_IA", "Move", 1, "ID", $Reg);
    }
    if ($Credits >= $CT) {
        $Cible = Insec($_POST['Cible']);
        $Conso = Insec($_POST['Conso']);
        $mes = '';
        $alerte_reco = false;
        $Matos_mun = array(1, 2, 6, 7, 8);
        $con = dbconnecti();
        $Faction = mysqli_result(mysqli_query($con, "SELECT Faction FROM Pays WHERE ID='$country'"), 0);
        $Premium = mysqli_result(mysqli_query($con, "SELECT Premium FROM Joueur WHERE ID='" . $_SESSION['AccountID'] . "'"), 0);
        $result = mysqli_query($con, "SELECT Nom,Zone,Meteo,Camouflage,DefenseAA_temp,TypeIndus,Industrie,BaseAerienne,QualitePiste,Pont_Ori,Pont,Radar_Ori,Radar,Port_Ori,Port,NoeudF_Ori,NoeudF,Garnison,Fortification,Recce,Flag FROM Lieu WHERE ID='$Cible'");
        $result1 = mysqli_query($con, "SELECT HP,Nom,Blindage_f,Vitesse,Taille,mobile,Reput,Detection,Carbu_ID,Conso,Type,Arme_Inf,Optics FROM Cible WHERE ID='$Veh'");
        $resultr = mysqli_query($con, "SELECT Experience,Vehicule_Nbr,Position,Placement,Muns,Skill,Matos FROM $DB_Reg WHERE ID='$Reg'");
        mysqli_close($con);
        if ($resultr) {
            while ($datar = mysqli_fetch_array($resultr, MYSQLI_ASSOC)) {
                $Reg_exp = $datar['Experience'];
                $Vehicule_Nbr = $datar['Vehicule_Nbr'];
                $Position = $datar['Position'];
                $Placement = $datar['Placement'];
                if (in_array($datar['Matos'], $Matos_mun))
                    $Muns = $datar['Matos'];
                else
                    $Muns = $datar['Muns'];
                $Skill = $datar['Skill'];
            }
            mysqli_free_result($resultr);
        }
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Cible_nom = $data['Nom'];
                $Cible_cam = $data['Camouflage'];
                $Zone = $data['Zone'];
                $Cible_DefenseAA = $data['DefenseAA_temp'];
                $TypeIndus = $data['TypeIndus'];
                $Cible_indus = $data['Industrie'];
                $Cible_base = $data['BaseAerienne'];
                $Cible_piste = $data['QualitePiste'];
                $Pont_Ori = $data['Pont_Ori'];
                $Pont = $data['Pont'];
                $Port_Ori = $data['Port_Ori'];
                $Port = $data['Port'];
                $Radar_Ori = $data['Radar_Ori'];
                $Radar = $data['Radar'];
                $NoeudF_Ori = $data['NoeudF_Ori'];
                $NoeudF = $data['NoeudF'];
                $Garnison = $data['Garnison'];
                $Fortification = $data['Fortification'];
                $Recce = $data['Recce'];
                $Flag = $data['Flag'];
                $Meteo = $data['Meteo'];
            }
            mysqli_free_result($result);
            unset($data);
        }
        if ($result1) {
            while ($data = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
                $HP = $data['HP'];
                $HP_ori = $HP;
                $Veh_Nom = $data['Nom'];
                $Blindage = $data['Blindage_f'];
                $Vitesse = $data['Vitesse'];
                $Taille = $data['Taille'];
                $mobile = $data['mobile'];
                $Reput = $data['Reput'];
                $Det = $data['Detection'];
                $Veh_Carbu = $data['Carbu_ID'];
                $Veh_Conso = $data['Conso'];
                $Type = $data['Type'];
                $Arme_Inf_reco = $data['Arme_Inf'];
                $Optics = $data['Optics'];
                $Vitesse = Get_LandSpeed($Vitesse, $mobile, $Zone, 0, $Type);
                if ($datar['Matos'] == 9) $Optics += 5;
                elseif ($datar['Matos'] == 13) $Optics += 5;
                elseif ($datar['Matos'] == 26) {
                    if ($Meteo > -20)
                        $Optics += 50;
                    else
                        $Optics += 10;
                    $mes .= '<br>Le <b>Radar</b> est enclenché!';
                } elseif ($datar['Matos'] == 10) $Vitesse *= 1.1;
                elseif ($datar['Matos'] == 14) $Vitesse *= 1.5;
                elseif ($datar['Matos'] == 30) $Vitesse /= 1.25;
                if (!$Blindage) $Blindage = Get_Blindage($Zone, $Taille, 0, 2);
            }
            mysqli_free_result($result1);
            unset($data);
        }
        $Detect_base = floor(($Reg_exp / 10) + 10);
        if ($Veh_Carbu)
            $Stock = 'Stock_Essence_' . $Veh_Carbu;
        else
            $Stock = 'Moral';
        //Embuscade
        $Avant_Garde = false;
        if ($mobile != 3) {
            if ($OfficierID > 0) {
                if (IsSkill(2, $OfficierID) and mt_rand(0, 100) < 25) $Avant_Garde = true;
            } else {
                if ($Skill == 71 and mt_rand(0, 100) < 80) $Avant_Garde = true;
                elseif ($Skill == 70 and mt_rand(0, 100) < 60) $Avant_Garde = true;
                elseif ($Skill == 46 and mt_rand(0, 100) < 40) $Avant_Garde = true;
                elseif ($Skill == 2 and mt_rand(0, 100) < 20) $Avant_Garde = true;
            }
        }
        if ($Avant_Garde)
            $mes .= '<br>Vous évitez une embuscade ennemie grâce à votre compétence <b>Avant-Garde</b>!';
        elseif ($Position != 25) {
            $Update_XP_eni = 0;
            $con = dbconnecti();
            $pj_unit = mysqli_query($con, "SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Officier_ID,r.Muns,r.Matos,c.Arme_Art,c.Arme_AT,c.Arme_Inf,c.Portee,c.Type,c.mobile FROM Regiment_IA as r,Cible as c,Pays as p 
			WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3) AND c.Portee >499 AND c.Charge=0");
            /*(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,r.Officier_ID,r.Muns,r.Matos,c.Arme_Art,c.Arme_AT,c.Arme_Inf,c.Portee,c.Type,c.mobile FROM Regiment as r,Cible as c,Pays as p
            WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3) AND c.Portee >499 AND c.Charge=0) UNION (*/
            //mysqli_close($con);
            if ($pj_unit) {
                while ($data = mysqli_fetch_array($pj_unit)) {
                    $Update_XP_eni = 0;
                    $EXP = $data['Experience'];
                    if ($data['Officier_ID'] > 0 and $data['Position'] == 3 and ($data['mobile'] == 3 or $data['Type'] == 9))
                        $Embuscade = IsSkill(3, $data['Officier_ID']);
                    else
                        $Embuscade = false;
                    if ($Embuscade)
                        $chance_tir = 1;
                    else
                        $chance_tir = mt_rand(0, 200);
                    if ($chance_tir <= $EXP) {
                        $Reg_eni = $data['ID'];
                        $Arme_Art = $data['Arme_Art'];
                        $Arme_AT = $data['Arme_AT'];
                        $Vehicule_ID_r = $data['Vehicule_ID'];
                        $Vehicule_Nbr_r = $data['Vehicule_Nbr'];
                        $Position_r = $data['Position'];
                        $Placement_r = $data['Placement'];
                        $Portee_r = $data['Portee'];
                        if ($Blindage > 0 and $Arme_AT)
                            $Arme = $Arme_AT;
                        elseif ($Arme_Art)
                            $Arme = $Arme_Art;
                        else
                            $Arme = $data['Arme_Inf'];
                        $resulta = mysqli_query($con, "SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme'");
                        if ($resulta) {
                            while ($data3 = mysqli_fetch_array($resulta, MYSQLI_ASSOC)) {
                                $Arme_Cal = round($data3['Calibre']);
                                $Arme_Multi = $data3['Multi'];
                                $Arme_Dg = $data3['Degats'];
                                $Arme_Perf = $data3['Perf'];
                                $Arme_Portee = $data3['Portee'];
                                $Arme_Portee_Max = $data3['Portee_max'];
                                if ($data['Type'] == 6) {
                                    $Arme_Portee /= 2;
                                    $Arme_Portee_Max /= 2;
                                }
                            }
                            mysqli_free_result($resulta);
                        }
                        if ($data['Officier_ID'] > 0) {
                            $alerte_reco[] = $data['Officier_ID'];
                            if ($Arme == 136)
                                $Muns_Stock = GetData("Regiment", "ID", $Reg_eni, "Stock_Essence_87");
                            else
                                $Muns_Stock = GetData("Regiment", "ID", $Reg_eni, "Stock_Munitions_" . $Arme_Cal);
                            $Reg_a_ia = false;
                        } else {
                            $Reg_a_ia = true;
                            $Muns_Stock = 9999;
                            $Reg_eni = 0;
                        }
                        $Muns_Conso = $data['Vehicule_Nbr'] * $Arme_Multi;
                        if ($Muns_Stock >= $Muns_Conso and $Muns_Conso > 0) {
                            $mes .= "<br>L'ennemi vous a tendu une embuscade !";
                            if ($data['Officier_ID'] > 0) {
                                if ($Arme == 136)
                                    UpdateData("Regiment", "Stock_Essence_87", -$Muns_Conso, "ID", $Reg_eni);
                                else
                                    UpdateData("Regiment", "Stock_Munitions_" . $Arme_Cal, -$Muns_Conso, "ID", $Reg_eni);
                            }
                            $Tir = mt_rand(0, $EXP);
                            if ($data['Position'] == 3) $Tir = $EXP;
                            if ($data['Matos'] == 3) $Tir += 2;
                            $Shoot_emb = $Tir + $Meteo + $Taille - $Vitesse - mt_rand(0, $Reg_exp) + $data['Vehicule_Nbr'];
                            if ($Shoot_emb > 1 or $Tir == $EXP) {
                                if (in_array($data['Matos'], $Matos_mun))
                                    $Munition = $data['Matos'];
                                else
                                    $Munition = $data['Muns'];
                                $Degats = (mt_rand($Arme_Cal, $Arme_Dg) - $Blindage) * GetShoot($Shoot_emb, $Arme_Multi);
                                $Degats = round(Get_Dmg($Munition, $Arme_Cal, $Blindage, $Portee_r, $Degats, $Arme_Perf, $Arme_Portee, $Arme_Portee_Max));
                                if ($Degats < 1) $Degats = mt_rand(1, 10);
                                if ($Embuscade) $Degats *= 2;
                                $HP -= $Degats;
                                if ($HP < 1) {
                                    $mes .= '<br>Le tir ennemi (' . $Reg_eni . 'e Cie) détruit une de vos unités. (' . $Degats . ' points de dégats!)';
                                    UpdateData($DB_Reg, "Vehicule_Nbr", -1, "ID", $Reg);
                                    if ($OfficierID > 0) {
                                        AddEventGround(400, $Veh, $OfficierID, $Reg, $Cible, 1, $Reg_eni);
                                        AddGroundAtk($Reg_eni, $Reg, $Vehicule_ID_r, $Vehicule_Nbr_r, $Veh, $Vehicule_Nbr, $Position_r, 4, $Cible, $Placement_r, 1, $Reg_a_ia, $Reg_b_ia);
                                    }
                                    $Update_XP_eni = $Reput;
                                    $Vehicule_Nbr = GetData($DB_Reg, "ID", $Reg, "Vehicule_Nbr");
                                    if ($Vehicule_Nbr < 1)
                                        break;
                                    else
                                        $HP = $HP_ori;
                                } else {
                                    $mes .= '<br>Le tir ennemi (' . $Reg_eni . 'e Cie) endommage une de vos unités, lui occasionnant <b>' . $Degats . '</b> points de dégats!';
                                    $Update_XP_eni = 1;
                                }
                                if ($Update_XP_eni > 0 and $Reg_eni > 0 and $data['Officier_ID'] > 0)
                                    UpdateData("Regiment", "Experience", $Update_XP_eni, "ID", $Reg_eni);
                            } else
                                $mes .= '<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
                        } else
                            $mes .= '<br>Le tir ennemi manque sa cible, vos troupes sont indemnes!';
                    }
                }
                mysqli_free_result($pj_unit);
                unset($data);
            }
            mysqli_close($con);
        }
        //Detection
        if ($Vehicule_Nbr > 0) {
            $Bonus_det = 0;
            if ($Trait_o == 10) $Bonus_det = 10;
            elseif ($Skill == 29) $Bonus_det = 10;
            elseif ($Skill == 126) $Bonus_det = 15;
            elseif ($Skill == 127) $Bonus_det = 20;
            elseif ($Skill == 128) $Bonus_det = 25;
            elseif ($Type == 37) {
                $con = dbconnecti();
                $Rudels = mysqli_query($con, "SELECT COUNT(*) FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Pays='$country' AND r.Placement=8 AND r.Vehicule_Nbr >0 AND c.Type=37");
                mysqli_close($con);
                if ($Skill == 43)
                    $Bonus_det += (5 * $Rudels);
                elseif ($Skill == 168)
                    $Bonus_det += (10 * $Rudels);
                elseif ($Skill == 169)
                    $Bonus_det += (15 * $Rudels);
                elseif ($Skill == 170)
                    $Bonus_det += (20 * $Rudels);
            }
            $Detect = mt_rand(0, $Detect_base);
            if ($Det > 10) $Detect *= 3;
            if ($Flag == $country) $Detect += 10;
            $Shoot = $Detect + $Det + $Bonus_det;
            if ($Shoot > 0) {
                //Camouflage zone
                switch ($Zone) {
                    case 2:
                    case 3:
                    case 5:
                    case 7:
                    case 10:
                        $Cam_zone = 30;
                        break;
                    case 4:
                        $Cam_zone = 20;
                        break;
                    case 1:
                    case 11:
                        $Cam_zone = 10;
                        break;
                    case 9:
                        $Cam_zone = 50;
                        break;
                    default:
                        $Cam_zone = 0;
                        break;
                }
                $aa_type = 'aucune information sur la DCA';
                $Shoot_infra = $Shoot - ($Cible_cam / 10);
                if ($Shoot > 0) {
                    if (!$Recce and $Zone != 6) {
                        $Faction_flag = GetData("Pays", "ID", $Flag, "Faction");
                        if ($Faction_flag != $Faction)
                            SetData("Lieu", "Recce", 1, "ID", $Cible);
                    }
                    if ($Shoot_infra > 5 and $Zone != 6) {
                        if ($Fortification > 75)
                            $Fort_txt = 'fortement fortifiée';
                        elseif ($Fortification > 50)
                            $Fort_txt = 'fortifiée';
                        elseif ($Fortification > 0)
                            $Fort_txt = 'légèrement fortifiée';
                    }
                    if ($Garnison > 0) {
                        require_once 'help/aide_garnison.php';
                        $Garnison_txt = Output::viewModal('help-aide-garnison', 'aide', $modal_txt) .
                            "<br>une garnison " . Output::linkModal('help-aide-garnison', '<img src="images/help.png">') . " " . $Fort_txt . " occupant la caserne";
                    }
                    if ($Radar_Ori > 0) {
                        if ($Shoot_infra > 10) {
                            if ($Radar < 1)
                                $Cible_radar_txt = '<br>un radar détruit';
                            elseif ($Radar < 25)
                                $Cible_radar_txt = '<br>un radar pratiquement détruit';
                            elseif ($Radar < 50)
                                $Cible_radar_txt = '<br>un radar sévèrement endommagé';
                            elseif ($Radar < 75)
                                $Cible_radar_txt = '<br>un radar endommagé';
                            elseif ($Radar < 100)
                                $Cible_radar_txt = '<br>un radar légèrement endommagé';
                            else
                                $Cible_radar_txt = '<br>un radar intact';
                        } else
                            $Cible_radar_txt = '<br>un radar';
                    } else
                        $Cible_radar_txt = '';
                    if ($NoeudF_Ori > 0) {
                        if ($Shoot_infra > 5) {
                            if ($NoeudF < 1)
                                $Cible_gare_txt = '<br>un noeud ferroviaire inutilisable';
                            elseif ($NoeudF < 25)
                                $Cible_gare_txt = '<br>un noeud ferroviaire pratiquement détruit';
                            elseif ($NoeudF < 50)
                                $Cible_gare_txt = '<br>un noeud ferroviaire sévèrement endommagé';
                            elseif ($NoeudF < 75)
                                $Cible_gare_txt = '<br>un noeud ferroviaire endommagé';
                            elseif ($NoeudF < 100)
                                $Cible_gare_txt = '<br>un noeud ferroviaire légèrement endommagé';
                            else
                                $Cible_gare_txt = '<br>un noeud ferroviaire intact';
                        } else
                            $Cible_gare_txt = '<br>une gare';
                    } else
                        $Cible_gare_txt = '';
                    if ($Pont_Ori > 0) {
                        if ($Shoot_infra > 5) {
                            if ($Pont < 1)
                                $Cible_pont_txt = '<br>un pont détruit';
                            elseif ($Pont < 25)
                                $Cible_pont_txt = '<br>un pont pratiquement détruit';
                            elseif ($Pont < 50)
                                $Cible_pont_txt = '<br>un pont sévèrement endommagé';
                            elseif ($Pont < 75)
                                $Cible_pont_txt = '<br>un pont endommagé';
                            elseif ($Pont < 100)
                                $Cible_pont_txt = '<br>un pont légèrement endommagé';
                            else
                                $Cible_pont_txt = '<br>un pont intact';
                        } else
                            $Cible_pont_txt = '<br>un pont';
                    } else
                        $Cible_pont_txt = '';
                    if ($Port_Ori > 0) {
                        if ($Shoot_infra > 5) {
                            if ($Port < 1)
                                $Cible_Port_txt = '<br>des infrastructures portuaires inutilisable';
                            elseif ($Port < 25)
                                $Cible_Port_txt = '<br>des infrastructures portuaires pratiquement détruites';
                            elseif ($Port < 50)
                                $Cible_Port_txt = '<br>des infrastructures portuaires sévèrement endommagées';
                            elseif ($Port < 75)
                                $Cible_Port_txt = '<br>des infrastructures portuaires endommagées';
                            elseif ($Port < 100)
                                $Cible_Port_txt = '<br>des infrastructures portuaires légèrement endommagées';
                            else
                                $Cible_Port_txt = '<br>des infrastructures portuaires intactes';
                        } else
                            $Cible_Port_txt = '<br>un Port';
                    } else
                        $Cible_Port_txt = '';
                    if ($TypeIndus != '') {
                        if ($Shoot_infra > 5) {
                            if ($Cible_indus < 1)
                                $Cible_ind_txt = '<br>une zone industrielle détruite';
                            elseif ($Cible_indus < 25)
                                $Cible_ind_txt = '<br>une zone industrielle pratiquement détruite';
                            elseif ($Cible_indus < 50)
                                $Cible_ind_txt = '<br>une zone industrielle sévèrement endommagée';
                            elseif ($Cible_indus < 75)
                                $Cible_ind_txt = '<br>une zone industrielle endommagée';
                            elseif ($Cible_indus < 100)
                                $Cible_ind_txt = '<br>une zone industrielle légèrement endommagée';
                            else
                                $Cible_ind_txt = '<br>une zone industrielle intacte';
                        } else
                            $Cible_ind_txt = '<br>une zone industrielle';
                    } else
                        $Cible_ind_txt = '';
                    if ($Shoot_infra > 5 and $Zone != 6) {
                        if ($Cible_DefenseAA > 4)
                            $aa_type = 'des défenses anti-aériennes de gros calibre';
                        elseif ($Cible_DefenseAA > 2)
                            $aa_type = 'des défenses anti-aériennes de calibre moyen';
                        elseif ($Cible_DefenseAA > 0)
                            $aa_type = 'des défenses anti-aériennes de faible calibre';
                        else
                            $aa_type = 'aucune défense anti-aérienne';
                    }
                    if ($Cible_base > 0) {
                        if ($Shoot_infra > 5) {
                            if ($Cible_base == 1)
                                $piste = 'un aérodrome avec une piste en dur';
                            elseif ($Cible_base == 2)
                                $piste = 'un aérodrome avec un bassin pour hydravions';
                            else
                                $piste = 'un aérodrome';
                            if ($Cible_piste < 1)
                                $Cible_base_txt = '<br>' . $piste . ' inutilisable';
                            if ($Cible_piste < 100)
                                $Cible_base_txt = '<br>' . $piste . ' endommagé';
                        } else
                            $Cible_base_txt = '<br>un aérodrome';
                    } else
                        $Cible_base_txt = '';
                } else
                    $Insuffisant = '<br>Vous ne distinguez pas suffisamment les infrastructures importantes pour que cela soit utile à une attaque.';
                $mes .= '<p>Vos troupes de reconnaissance ont repéré ' . $aa_type . $Cible_ind_txt . $Cible_radar_txt . $Cible_pont_txt . $Cible_gare_txt . $Cible_port_txt . $Cible_base_txt . $Garnison_txt . ' aux alentours de <b>' . $Cible_nom . '</b>' . $Insuffisant . '</p>';
            }
            /*if($OfficierID >0 and $Det >10 and IsSkill(25,$OfficierID) and $mobile !=5)
            {
                $Reco_by_Fire=true;
                if($Arme_Inf_reco >0)
                {
                    $con=dbconnecti();
                    $resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme_Inf_reco'");
                    mysqli_close($con);
                    if($resulta)
                    {
                        while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
                        {
                            $Arme_Nom=$data3['Nom'];
                            $Arme_Cal=round($data3['Calibre']);
                            $Arme_Multi=$data3['Multi'];
                            $Arme_Dg=$data3['Degats'];
                            $Arme_Perf=$data3['Perf'];
                            $Arme_Portee=$data3['Portee'];
                            $Arme_Portee_Max=$data3['Portee_max'];
                        }
                        mysqli_free_result($resulta);
                    }
                }
            }*/
            $Update_xp = 0;
            $skills = "<h2>Premium</h2><div style='width:50%;'>";
            if ($Premium) {
                $pc_score = $Shoot / 2;
                $Bar_pc = round($pc_score, 1, PHP_ROUND_HALF_DOWN);
                $skills .= "Efficacité de votre reconnaissance<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='" . $pc_score . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $Bar_pc . "%'>" . $Bar_pc . "%</div></div>";
            } else
                $skills .= "Efficacité de votre reconnaissance<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100' style='width:100%'>?%</div></div>";
            //Scan Pos
            $con = dbconnecti();
            /*$result=mysqli_query($con,"(SELECT DISTINCT r.ID,r.Pays,r.Vehicule_ID,r.Vehicule_Nbr,r.Officier_ID,r.Camouflage,r.Placement,r.Experience,r.Position,r.Visible FROM Regiment as r,Pays as p
            WHERE r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Officier_ID<>'$OfficierID' AND r.Position<>25)
            UNION (SELECT DISTINCT r.ID,r.Pays,r.Vehicule_ID,r.Vehicule_Nbr,r.Officier_ID,r.Camouflage,r.Placement,r.Experience,r.Position,r.Visible FROM Regiment_IA as r,Pays as p
            WHERE r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND r.Pays=p.ID AND p.Faction<>'$Faction')");*/
            $result = mysqli_query($con, "SELECT DISTINCT r.ID,r.Pays,r.Vehicule_ID,r.Vehicule_Nbr,r.Officier_ID,r.Camouflage,r.Placement,r.Experience,r.Position,r.Visible,r.Skill,r.Matos,c.Taille,c.Blindage_f,c.HP,c.Portee,c.Detection
			FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Vehicule_ID=c.ID");
            mysqli_close($con);
            if ($result) {
                while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Taille_eni = $data['Taille'];
                    $Blindage_eni = $data['Blindage_f'];
                    $HP_eni = $data['HP'];
                    $Portee_eni = $data['Portee'];
                    $Detection_eni = $data['Detection'];
                    /*$con=dbconnecti();
                    $resulta=mysqli_query($con,"SELECT Taille,Blindage_f,HP,Portee,Detection FROM Cible WHERE ID='".$data['Vehicule_ID']."'");
                    mysqli_close($con);
                    if($result4)
                    {
                        while($data4=mysqli_fetch_array($result4,MYSQLI_ASSOC))
                        {
                            $Taille_eni=$data4['Taille'];
                            $Blindage_eni=$data4['Blindage_f'];
                            $HP_eni=$data4['HP'];
                            $Portee_eni=$data4['Portee'];
                            $Detection_eni=$data4['Detection'];
                        }
                        mysqli_free_result($result4);
                    }*/
                    if ($data['Officier_ID'] > 0) {
                        $Trait_eni = GetData("Officier", "ID", $data['Officier_ID'], "Trait");
                        $DB = 'Regiment';
                        if (50 - $Detection_eni < $Taille)
                            $alerte_reco[] = $data['Officier_ID'];
                    } else {
                        $DB = 'Regiment_IA';
                        if ($data['Position'] == 11) $data['Vehicule_ID'] = 5000;
                    }
                    if ($data['Position'] == 1 or $data['Position'] == 3)
                        $Tactique_eni = $data['Experience'] / 10;
                    elseif ($data['Position'] == 2 or $data['Position'] == 10)
                        $Tactique_eni = $data['Experience'] / 5;
                    elseif ($data['Position'] == 0 or $data['Position'] > 3)
                        $Tactique_eni = $data['Experience'] / 20;
                    if (!$data['Camouflage']) $data['Camouflage'] = 1;
                    if ($Trait_eni == 5) $data['Camouflage'] *= 2;
                    elseif ($data['Matos'] == 11) $data['Camouflage'] *= 1.1;
                    if ($data['Skill'] == 29 or $data['Skill'] == 25 or $data['Skill'] == 6) $data['Camouflage'] *= 1.1;
                    elseif ($data['Skill'] == 126 or $data['Skill'] == 129 or $data['Skill'] == 51) $data['Camouflage'] *= 1.2;
                    elseif ($data['Skill'] == 127 or $data['Skill'] == 130 or $data['Skill'] == 80) $data['Camouflage'] *= 1.3;
                    elseif ($data['Skill'] == 128 or $data['Skill'] == 131 or $data['Skill'] == 81) $data['Camouflage'] *= 1.4;
                    $Cam_eni = $Taille_eni / $data['Camouflage'];
                    if ($Cam_eni < 1) $Cam_eni = 1;
                    $Defense_reco = $Cam_zone - $Meteo + mt_rand(0, $Tactique_eni * $data['Camouflage']) - $Cam_eni;
                    if ($debug) $mes .= "<br>[DEBUG] Reco = " . $Shoot . "/" . $Defense_reco . " (Terrain = " . $Cam_zone . ", Météo = " . $Meteo . ", Taille = " . $Cam_eni . ", Cam = " . $data['Camouflage'] . ", Tac (max) = " . $Tactique_eni;
                    if ($Shoot > $Defense_reco) {
                        if ($Premium) {
                            $eni_score = $Defense_reco / 6;
                            $Bar_eni = round($eni_score, 1, PHP_ROUND_HALF_DOWN);
                            $skills .= "Efficacité du camouflage de la " . $data['ID'] . " Cie<br><div class='progress'><div class='progress-bar-danger' role='progressbar' aria-valuenow='" . $eni_score . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $eni_score . "%'>" . $Bar_eni . "%</div></div>";
                        }
                        //Détecté
                        if (!$data['Visible']) {
                            $Update_xp += 1;
                            SetData($DB, "Visible", 1, "ID", $data['ID']);
                            $intro .= "<br>Une unité <img src='" . $data['Pays'] . "20.gif'>, " . GetPosGr($data['Position']) . ", a été détectée " . GetPlace($data['Placement']) . " <img src='images/vehicules/vehicule" . $data['Vehicule_ID'] . ".gif'>";
                            if ($OfficierID > 0 and $Reco_by_Fire) {
                                $mes .= "<br>Votre unité bénéficie de sa compétence <b>Reco By Fire</b>!";
                                if ($Arme_Inf_reco > 0) {
                                    if ($Arme_Inf_reco == 136)
                                        $Muns_Stock = GetData("Regiment", "ID", $Reg, "Stock_Essence_87");
                                    else
                                        $Muns_Stock = GetData("Regiment", "ID", $Reg, "Stock_Munitions_" . $Arme_Cal);
                                    $Muns_Conso = $Vehicule_Nbr * $Arme_Multi;
                                    if ($Muns_Stock >= $Muns_Conso and $Muns_Conso > 0) {
                                        if ($Arme_Inf_reco == 136)
                                            UpdateData("Regiment", "Stock_Essence_87", -$Muns_Conso, "ID", $Reg);
                                        else
                                            UpdateData("Regiment", "Stock_Munitions_" . $Arme_Cal, -$Muns_Conso, "ID", $Reg);
                                        $mes .= "<br>Votre unité tire " . $Muns_Conso . " munitions à l'aide de son " . $Arme_Nom;
                                        $Tir = mt_rand(0, $Detect_base) + $Optics;
                                        $Defense_tir = mt_rand(0, $Tactique_eni) - $Cam_eni - $Meteo;
                                        if ($debug) $mes .= "<br>[DEBUG] Tir = " . $Tir . "/" . $Defense_tir . " (Taille = " . $Cam_eni . ", Tac (max) = " . $Tactique_eni . ")";
                                        if ($Tir > 1 and $Tir > $Defense_tir) {
                                            $Degats = (mt_rand($Arme_Cal, $Arme_Dg) - $Blindage_eni) * GetShoot($Tir, $Arme_Multi);
                                            $Degats = round(Get_Dmg($Muns, $Arme_Cal, $Blindage_eni, $Portee_eni, $Degats, $Arme_Perf, $Arme_Portee, $Arme_Portee_Max));
                                            if (!$Blindage_eni and $Portee_eni < 600 and $Arme_Cal < 30 and $Degats < 100) $Degats *= 2; //Boost MG vs Infanterie
                                            if ($Degats < 1) $Degats = mt_rand(1, 10);
                                            if ($Degats > $HP_eni) {
                                                UpdateData($DB, "Vehicule_Nbr", -1, "ID", $Reg_eni);
                                                UpdateData($DB, "Moral", -1, "ID", $Reg_eni);
                                                AddEventGround(408, $data['Vehicule_ID'], $OfficierID, $data['ID'], $Cible, 1, $Reg);
                                                AddGroundAtk($Reg, $data['ID'], $Veh, $Vehicule_Nbr, $data['Vehicule_ID'], $data['Vehicule_Nbr'], 4, $data['Position'], $Cible, $data['Placement'], 500, 1);
                                                $Update_xp += 10;
                                                $mes .= "<br>Votre unité touche la cible et lui occasionne <b>" . $Degats . "</b> dégâts!<br><b>La cible est détruite!</b>";
                                            } elseif ($Degats > 0) {
                                                $Update_xp += 1;
                                                $mes .= '<br>Votre unité touche la cible et lui occasionne <b>' . $Degats . '</b> dégâts!';
                                            } else
                                                $mes .= "<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
                                        } else
                                            $mes .= '<br>Votre unité rate la cible!';
                                    } else
                                        $mes .= "<br>Votre unité annule son attaque, faute d'armement adéquat!";
                                } else
                                    $mes .= '<br>Votre unité annule son attaque, faute de munitions!';
                            }
                        }
                    }
                }
                mysqli_free_result($result);
                unset($data);
                if ($Update_xp > 0 and $Reco_by_Fire)
                    mail(EMAIL_LOG, "Aube des Aigles: Reco By Fire", "Joueur : " . $OfficierID . " dans les environs de : " . $Cible_nom . "<br>Reco de " . $Veh_Nom . " <html>" . $intro . $mes . "</html>", "Content-type: text/html; charset=utf-8");
            }
            $skills .= "</div>";
            if (!$intro) $intro = "<p>Vos troupes de reconnaissance n'ont détecté aucune autre unité dans les environs</p>";
            if ($Zone == 6)
                $img = Afficher_Image('images/bino_sea.jpg', "images/image.png", "Reco", 50);
            else
                $img = Afficher_Image('images/bino.jpg', "images/image.png", "Reco", 50);
            if ($CT > 0) {
                if ($OfficierID > 0) {
                    UpdateData("Officier", "Credits", -$CT, "ID", $OfficierID);
                    UpdateData("Regiment", $Stock, -$Conso, "ID", $Reg);
                    if ($Update_xp) {
                        UpdateData("Regiment", "Experience", $Update_xp, "ID", $Reg);
                        UpdateData("Officier", "Avancement", $Update_xp, "ID", $OfficierID);
                        UpdateData("Officier", "Reputation", $Update_xp, "ID", $OfficierID);
                    }
                } elseif ($OfficierEMID > 0)
                    UpdateData("Officier_em", "Credits", -$CT, "ID", $OfficierEMID);
            } elseif ($Veh_Carbu > 0 and $Veh < 5000) {
                if ($Front > 0) {
                    $Lat_min = $Latitude_base - 4;
                    $Lat_max = $Latitude_base + 4;
                    $Long_min = $Longitude_base - 5;
                    $Long_max = $Longitude_base + 5;
                } else {
                    $Lat_min = $Latitude_base - 2;
                    $Lat_max = $Latitude_base + 2;
                    $Long_min = $Longitude_base - 3;
                    $Long_max = $Longitude_base + 3;
                }
                $Conso_tot = $Veh_Conso * $Vehicule_Nbr;
                if ($Veh_Carbu == 100) {
                    $Stock_var = 'Stock_Essence_100';
                    $Octane = ' Octane 100';
                } elseif ($Veh_Carbu == 1) {
                    $Stock_var = 'Stock_Essence_1';
                    $Octane = ' Diesel';
                } elseif ($Veh_Carbu == 87) {
                    $Stock_var = 'Stock_Essence_87';
                    $Octane = ' Octane 87';
                }
                $con = dbconnecti();
                $getdepot = mysqli_result(mysqli_query($con, "SELECT l.ID FROM Lieu as l,Pays as p WHERE l.Flag=p.Pays_ID AND p.Faction='$Faction' AND
				(l.ID='$Base' OR ((l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max'))) AND l." . $Stock_var . " >='$Conso_tot' ORDER BY l." . $Stock_var . " DESC LIMIT 1"), 0);
                $resetconso = mysqli_query($con, "UPDATE Lieu SET " . $Stock_var . "=" . $Stock_var . "-'$Conso_tot' WHERE ID='" . $getdepot . "'");
                $reset_depot = mysqli_affected_rows($con);
                mysqli_close($con);
                $mes .= "<b>" . $Conso_tot . "L " . $Octane . "</b> ont été attribués à l'unité depuis le dépôt de <b>" . GetData("Lieu", "ID", $getdepot, "Nom") . "</b>";
            }
            if ($mobile != 5) {
                $resetquery = "UPDATE $DB_Reg SET Position=0,Camouflage=1,Move=1,Visible=1,Experience=Experience+1 WHERE ID='$Reg'";
            } elseif ($Type != 37) { //Pas les sous-marins
                $resetquery = "UPDATE $DB_Reg SET Position=0,Camouflage=1,Move=1,Visible=1,Experience=Experience+1,Autonomie=Autonomie-1 WHERE ID='$Reg'";
            } else {
                $resetquery = "UPDATE $DB_Reg SET Move=1,Experience=Experience+1,Autonomie=Autonomie-1 WHERE ID='$Reg'";
            }
            $con = dbconnecti();
            $reset = mysqli_query($con, $resetquery);
            mysqli_close($con);
        } else {
            if ($OfficierID > 0) {
                $Exp_final = 0;
                if ($Trait_o == 11) {
                    $Exp_final = $Reg_exp;
                    if ($Exp_final > 100) $Exp_final = 100;
                }
                $con = dbconnecti();
                $reset = mysqli_query($con, "UPDATE Regiment SET Experience='$Exp_final',Vehicule_Nbr=0,Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
				Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
				Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg'");
                mysqli_close($con);
            }
            $mes .= '<p>La mission est interrompue, vos troupes ont été décimées!</p>';
        }
        //mail(EMAIL_LOG, "Aube des Aigles: Combat : Reco", "Joueur : ".$OfficierID." dans les environs de : ".$Cible_nom."<br>Reco de ".$Veh_Nom."<html>".$intro.$mes.$skills."</html>", "Content-type: text/html; charset=iso-8859-1");
        if ($alerte_reco) {
            include_once './jfv_msg.inc.php';
            $off_alerte = array_unique($alerte_reco);
            $off_count = count($off_alerte);
            for ($x = 0; $x < $off_count; $x++) {
                SendMsgOff($off_alerte[$x], 0, "Une reconnaissance ennemie a été détectée dans les environs de " . $Cible_nom, "Rapport de reconnaissance", 0, 2);
            }
            unset($alerte_reco);
            unset($off_alerte);
        }
        $titre = 'Reconnaissance';
        if ($OfficierEMID > 0)
            $menu = Output::linkBtn('index.php?view=ground_em_ia_list', 'Retour au menu') .
                "<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='" . $Reg . "'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
        elseif ($OfficierID > 0)
            $menu = Output::linkBtn('index.php?view=ground_menu', 'Retour au menu Ordres');
        if ($intro)
            $intro = '<div class="panel panel-war"><div class="panel-heading">Unités détectées</div><div class="panel-body">' . $intro . '</div></div></div>';
        if ($mes)
            $mes = '<div class="panel panel-war"><div class="panel-heading">Informations</div><div class="panel-body">' . $mes . '</div></div>';
        include_once './default.php';
    } else
        echo '<h1>Pas assez de crédits!</h1>';
}