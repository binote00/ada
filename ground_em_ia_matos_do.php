<?php
require_once './jfv_inc_sessions.php';
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierID > 0 xor $OfficierEMID > 0) {
    include_once './jfv_include.inc.php';
    $reg = Insec($_GET['reg']);
    $matos = Insec($_GET['matos']);
    $country = $_SESSION['country'];
    if ($OfficierEMID and $reg >0 and $matos >0 and $country >0) {
        $_SESSION['reg'] = $reg;
        dbconnect();
        $resulto = $dbh->prepare("SELECT Front,Credits,Trait,Armee FROM Officier_em WHERE ID=:offid");
        $resulto->bindValue('offid', $OfficierEMID, 1);
        $resulto->execute();
        $datao = $resulto->fetchObject();
        $Front = $datao->Front;
        $Credits = $datao->Credits;
        $Trait = $datao->Trait;
        $Armee = $datao->Armee;
        if ($Armee > 0) {
            $reg_pre = $dbh->prepare("SELECT d.Armee,r.Move,r.Placement,c.mobile,c.Categorie,c.Arme_AT,l.Flag,l.Flag_Usine,l.Flag_Port FROM Regiment_IA as r,Division as d,Cible as c,Lieu as l WHERE r.Division=d.ID AND r.Vehicule_ID=c.ID AND r.Lieu_ID=l.ID AND r.ID=:reg");
            $reg_pre->bindValue('reg', $reg, 1);
            $reg_pre->execute();
            $datar = $reg_pre->fetchObject();
            $Move = $datar->Move;
            $Placement = $datar->Placement;
            $mobile = $datar->mobile;
            $Categorie = $datar->Categorie;
            $Arme_AT = $datar->Arme_AT;
            $Flag = $datar->Flag;
            $Flag_Usine = $datar->Flag_Usine;
            $Flag_Port = $datar->Flag_Port;
            if($datar->Armee == $Armee) $Ordre_ok = true;
        }else{
            $resadmin = $dbh->prepare("SELECT Admin FROM Joueur WHERE ID=:account");
            $resadmin->bindValue('account', $_SESSION['AccountID'], 1);
            $resadmin->execute();
            $dataa = $resadmin->fetchObject();
            $Admin = $dataa->Admin;
            $reg_pre = $dbh->prepare("SELECT r.Move,r.Placement,c.mobile,c.Categorie,c.Type,c.Arme_AT,l.Flag,l.Flag_Usine,l.Flag_Port FROM Regiment_IA as r,Cible as c,Lieu as l WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID=l.ID AND r.ID=:reg");
            $reg_pre->bindValue('reg', $reg, 1);
            $reg_pre->execute();
            $datar = $reg_pre->fetchObject();
            $Move = $datar->Move;
            $Placement = $datar->Placement;
            $mobile = $datar->mobile;
            $Categorie = $datar->Categorie;
            $Type_Veh = $datar->Type;
            $Arme_AT = $datar->Arme_AT;
            $Flag = $datar->Flag;
            $Flag_Usine = $datar->Flag_Usine;
            $Flag_Port = $datar->Flag_Port;
            $resultem = $dbh->prepare("SELECT Commandant,Adjoint_Terre,Officier_Mer,Officier_Log FROM Pays WHERE Pays_ID=:pays AND Front=:front");
            $resultem->bindValue('pays', $country, 1);
            $resultem->bindValue('front', $Front, 1);
            $resultem->execute();
            $dataem = $resultem->fetchObject();
            $Commandant = $dataem->Commandant;
            $Adjoint_Terre = $dataem->Adjoint_Terre;
            $Officier_Mer = $dataem->Officier_Mer;
            $Officier_Log = $dataem->Officier_Log;
            if (($Commandant > 0 and ($Commandant == $OfficierEMID))
                or ($Adjoint_Terre > 0 and ($Adjoint_Terre == $OfficierEMID))
                or ($Officier_Mer > 0 and ($Officier_Mer == $OfficierEMID))
                or ($Officier_Log > 0 and ($Officier_Log == $OfficierEMID))
                or $country ==BEL or $country ==NL or $country ==GRE or $country ==BUL or $country ==YOU or $country ==ROU or $country ==HUN or $country ==FIN or $country ==NOR
                or $Admin ==1)
                $Ordre_ok = true;
        }
        if ($Ordre_ok) {
            if(!$Move){
                include_once './jfv_ground.inc.php';
                $list_matos=Get_Matos_List($Categorie,$Type_Veh,$mobile,$Arme_AT);
                if(in_array($matos,$list_matos)){
                    if(!IsWar($Flag,$country) and (($mobile !=MOBILE_WATER and !IsWar($Flag_Usine,$country) and $Placement ==PLACE_USINE) or ($mobile ==MOBILE_WATER and !IsWar($Flag_Port,$country)))){
                        $ok_up = $dbh->prepare("UPDATE Regiment_IA SET Matos=:matos,Move=1,Position=0 WHERE ID=:reg");
                        $ok_up->bindValue('matos', $matos, 1);
                        $ok_up->bindValue('reg', $reg, 1);
                        $ok_up->execute();
                        if ($ok_up->rowCount())
                            $_SESSION['msg'] = '<h2>Equipement</h2>L\'équipement <img src="images/skills/skille' . $matos . '.png"> a été ajouté à l\'unité!';
                        else
                            $_SESSION['msg_red'] = 'Erreur';
                    }
                    elseif($mobile ==5){
                        $_SESSION['msg_red'] = 'Cette unité ne peut recevoir un nouvel équipement que si elle se trouve sur un port de sa faction!';
                    }else{
                        $_SESSION['msg_red'] = 'Cette unité ne peut recevoir un nouvel équipement que si elle se trouve sur une usine de sa faction!';
                    }
                }else{
                    $_SESSION['msg_red'] = 'Cette unité ne peut recevoir cet équipement!';
                }
            }else{
                $_SESSION['msg_red'] = 'Cette unité n\'est pas disponible car elle a déjà effectué son action du jour!';
            }
        }
        header('Location : index.php?view=ground_em_ia');
    }
    /*$Reg = Insec($_POST['Reg']);
    $Matos = Insec($_POST['matos']);
    if ($Reg > 0 and $Matos > 0) {
        $Ordre_ok = false;
        $country = $_SESSION['country'];
        $con = dbconnecti();
        if ($OfficierID > 0) {
            $resulto = mysqli_query($con, "SELECT Front,Credits FROM Officier WHERE ID='$OfficierID'");
            if ($resulto) {
                while ($datao = mysqli_fetch_array($resulto, MYSQLI_ASSOC)) {
                    $Front = $datao['Front'];
                    $Credits = $datao['Credits'];
                }
                mysqli_free_result($resulto);
            }
        } elseif ($OfficierEMID) {
            $resulto = mysqli_query($con, "SELECT Front,Credits,Trait,Armee FROM Officier_em WHERE ID='$OfficierEMID'");
            if ($resulto) {
                while ($datao = mysqli_fetch_array($resulto, MYSQLI_ASSOC)) {
                    $Front = $datao['Front'];
                    $Credits = $datao['Credits'];
                    $Trait = $datao['Trait'];
                    $Armee = $datao['Armee'];
                }
                mysqli_free_result($resulto);
            }
        }
        if ($Front == 99) {
            $Planificateur = GetData("GHQ", "Pays", $country, "Planificateur");
            if ($Planificateur > 0 and $OfficierEMID == $Planificateur)
                $GHQ = true;
        } else {
            $result2 = mysqli_query($con, "SELECT Commandant,Adjoint_Terre,Officier_Mer,Officier_Log FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
            if ($result2) {
                while ($data = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                    $Commandant = $data['Commandant'];
                    $Adjoint_Terre = $data['Adjoint_Terre'];
                    $Officier_Mer = $data['Officier_Mer'];
                    $Officier_Log = $data['Officier_Log'];
                }
                mysqli_free_result($result2);
            }
        }
        if (($Commandant > 0 and ($Commandant == $OfficierEMID))
            or ($Adjoint_Terre > 0 and ($Adjoint_Terre == $OfficierEMID))
            or ($Officier_Mer > 0 and ($Officier_Mer == $OfficierEMID))
            or ($Officier_Log > 0 and ($Officier_Log == $OfficierEMID))
            or $Admin == 1 or $GHQ)
            $Ordre_ok = true;
        else {
            $reg_pre = mysqli_query($con, "SELECT Bataillon,Division FROM Regiment_IA WHERE ID='$Reg'");
            if ($reg_pre) {
                while ($datarp = mysqli_fetch_array($reg_pre, MYSQLI_ASSOC)) {
                    $Bataillono = $datarp['Bataillon'];
                    $Divisiono = $datarp['Division'];
                }
                mysqli_free_result($reg_pre);
            }
            if ($OfficierID > 0) {
                if ($Bataillono == $OfficierID)
                    $Ordre_ok = true;
                else {
                    $Division_Cdt = mysqli_result(mysqli_query($con, "SELECT Cdt FROM Division WHERE ID='$Divisiono'"), 0);
                    if ($Division_Cdt == $OfficierID) $Ordre_ok = true;
                    $menu = "<a href='index.php?view=ground_div' class='btn btn-default' title='Retour'>Retour</a>";
                }
            } elseif ($Armee > 0) {
                $Division_Armee = GetData("Division", "ID", $Divisiono, "Armee");
                if ($Division_Armee == $Armee) $Ordre_ok = true;
            }
        }
        if ($Ordre_ok and $Reg > 0) {
            $_SESSION['reg'] = $Reg;
            if (!$Move) {
                $ok_up2 = mysqli_query($con, "UPDATE Regiment_IA SET Matos=" . $Matos . ",Move=1,Position=0 WHERE ID='$Reg'");
                $_SESSION['msg'] = '<h2>Equipement</h2>L\'équipement <img src="/images/skills/skille' . $Matos . '.png"> a été ajouté à l\'unité!';
            } else
                $_SESSION['reg'] = 'Cette unité n\'est pas disponible car elle a déjà effectué son action du jour!';
            header('Location : index.php?view=ground_em_ia');
            /*if(!$mes)$mes="Non, vraiment, vous ne pouvez pas!";
            $img="<img src='images/scenes/skills_m.jpg'>";
            $titre=$Reg."e Compagnie IA";
            if(!$menu)
            {
                if($OfficierEMID)
                    $menu="<form action='index.php?view=ground_em_ia_list' method='post'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                $menu="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='Submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
            }
            include_once('./default.php');*/
        /*} else
            echo '<div class="alert alert-danger">Vous n\'êtes pas autorisé à commander cette unité!</div>';
    }*/
} else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';