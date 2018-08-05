<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once './jfv_include.inc.php';
    include_once './jfv_txt.inc.php';
    include_once './jfv_ground.inc.php';
    $Vehicule = Insec($_POST['Ve']);
    $Reput = Insec($_POST['Cr']);
    $Retraite = Insec($_POST['Nid']);
    if ($Vehicule > 0 && $Reput > 0) {
        $Veh = Cible::getById($Vehicule);
        if ($mobile == MOBILE_WATER) {
            $Placement = 8;
            $Experience = 250;
            $Veh_Nbr = 1;
            $Autonomie = 10;
        } else {
            $Placement = 6;
            $Experience = 50;
            $Autonomie = 0;
            $VehNbrMax = GetMaxVeh($Veh->Type, $Veh->mobile, $Veh->Flak, 500000);
            if ($Veh->Stock >= $VehNbrMax)
                $Veh_Nbr = $VehNbrMax;
            else
                $Veh_Nbr = floor($Stock);
        }

        if ($Veh->Type != 13 && $Veh->Type != 1) {
            if ($Veh->Type == 37) //Sub
                $Skills_1 = array(25, 32, 35, 37, 43);
            elseif ($Veh->Type == 21) //PA
                $Skills_1 = array(25, 30, 36);
            elseif ($Veh->Type == 20 || $Veh->Type == 19 || $Veh->Type == 18) //Cuirassé & Croiseur
                $Skills_1 = array(15, 22, 25, 30, 31, 33, 34, 35, 36, 38, 41);
            elseif ($Veh->Type == 15 || $Veh->Type == 16 || $Veh->Type == 17) //Escorteurs
                $Skills_1 = array(25, 30, 35, 36, 37, 39, 40, 42);
            elseif ($Veh->Type == 14) //Pt navires
                $Skills_1 = array(25, 35, 36);
            elseif ($Veh->Categorie == 6) //MG
                $Skills_1 = array(3, 4, 6, 7, 9, 11, 13, 14, 23, 25, 29);
            elseif ($Veh->Type == 4) //Canon AT
                $Skills_1 = array(3, 6, 9, 11, 12, 14, 25);
            elseif ($Veh->Type == 6)
                $Skills_1 = array(6, 8, 9, 12, 15, 22, 25, 28);
            elseif ($Veh->Type == 8)
                $Skills_1 = array(6, 8, 9, 15, 20, 22, 25, 28);
            elseif ($Veh->Type == 9)
                $Skills_1 = array(1, 2, 3, 5, 6, 9, 10, 16, 18, 19, 21, 24, 25);
            elseif ($Veh->Type == 12)
                $Skills_1 = array(6, 9, 12, 14, 25, 30);
            elseif ($Veh->Type == 7 || $Veh->Type == 10 || $Veh->Type == 91)
                $Skills_1 = array(1, 2, 5, 6, 9, 10, 16, 18, 19, 21, 24, 25);
            elseif ($Veh->Type == 11)
                $Skills_1 = array(1, 2, 5, 6, 9, 10, 16, 18, 19, 21, 25, 30);
            elseif ($Veh->Type == 2 || $Veh->Type == 3 || $Veh->Type == 5 || $Veh->Type == 93)
                $Skills_1 = array(1, 2, 5, 6, 9, 10, 16, 18, 19, 21, 25);
            elseif ($Veh->Type == 1) //Camions
                $Skills_1 = array(6, 25);
            else //Inf
                $Skills_1 = array(3, 4, 6, 7, 9, 11, 13, 14, 17, 23, 25, 26, 29);
        }
        if (is_array($Skills_1))
            $Skill_p = $Skills_1[mt_rand(0, count($Skills_1) - 1)];
        if (!$Retraite) $Retraite = Get_Retraite(99, $country, 40);
        $Front = GetFrontByCoord($Retraite);
        $query2 = "INSERT INTO Regiment_IA (CT,Pays,Front,Vehicule_ID,Lieu_ID,Vehicule_Nbr,Position,Placement,HP,Camouflage,Experience,Moral,Distance,Move,Skill,Avions,Autonomie,Move_time,Atk_time) 
        VALUES (0,'$country','$Front','$Vehicule','$Retraite','$Veh_Nbr',4,'$Placement','$Veh->HP',1,'$Experience',100,'$Veh->Portee',1,'$Skill_p','$Veh->Hydra_Nbr','$Autonomie',NOW(),NOW())";
        $con = dbconnecti();
        $ok2 = mysqli_query($con, $query2);
        if ($Vehicule == 5392) //Dépot flottant
        {
            $ins_id = mysqli_insert_id($con);
            $ok3 = mysqli_query($con, "INSERT INTO Depots (Reg_ID) VALUES ('$ins_id')");
        } else
            $ok3 = true;

        //Output
        echo "<h1>Création d'unité</h1>";
        if ($ok2 && $ok3) {
            echo Output::ShowAdvert('L\'unité a été activée avec succès !', 'info');
            echo "<p>" . $Veh_Nbr . " " . GetVehiculeIcon($Vehicule, $country, 0, 0, $Front) . "</p>";
        } else {
            mail(EMAIL_LOG, 'ERREUR Création d\'unité', mysqli_error($con));
            echo Output::ShowAdvert('Erreur lors de l\'activation de l\'unité !', 'danger');
        }
        mysqli_close($con);
        echo Output::linkBtn('index.php?view=ground_em_ia_list', 'Retour');
    }
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page</h1>";