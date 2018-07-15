<?php
require_once './jfv_inc_sessions.php';
require_once './jfv_include.inc.php';
$avion = Insec($_POST['Avion']);
$chemin = Insec($_POST['Chemin']);
$Distance = Insec($_POST['Distance']);
$meteo = Insec($_POST['Meteo']);
$Mun1 = Insec($_POST['Mun1']);
$Mun2 = Insec($_POST['Mun2']);
$alt = Insec($_POST['Alt']);
$c_gaz = Insec($_POST['gaz']);
$flaps = Insec($_POST['flaps']);
$Puissance = Insec($_POST['Puissance']);
$base = Insec($_POST['Base']);
$Battle = Insec($_POST['Battle']);
$Faction = Insec($_POST['Camp']);
$Pilote_pvp = $_SESSION['Pilote_pvp'];
$Cible_Atk_Post = Insec($_POST['Cible_Atk']);
if ($Pilote_pvp == 1) {
    echo "<pre>";
    print_r($_POST);
    print_r($_SESSION);
    echo "</pre>";
}
if (isset($_SESSION['AccountID']) AND $Pilote_pvp > 0 AND $avion > 0 AND $base > 0 AND !empty($_POST)) {
    include_once './jfv_txt.inc.php';
    include_once './jfv_air_inc.php'; //OK
    include_once './jfv_combat.inc.php'; //OK
    include_once './jfv_nav.inc.php';
    include_once './jfv_rencontre.inc.php';
    $Chk_Decollage = $_SESSION['Decollage0'];
    $Saison = 3;
    $finmission = false;
    if ($Chk_Decollage) {
        $mes = '<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>';
        mail('binote@hotmail.com', "Aube des Aigles: Init Mission F5 (takeoff) : " . $Pilote_pvp, "Joueur " . $Pilote_pvp . " (IP " . $_SERVER['REMOTE_ADDR'] . ") depuis la page " . $_SERVER['HTTP_REFERER'] . " a tenté de charger la page " . $_SERVER['REQUEST_URI'] . " en utilisant " . $_SERVER['HTTP_USER_AGENT']);
    }
    if ($c_gaz < 20) {
        $mes .= "<br>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !";
        $finmission = true;
    } else {
        if ($Cible_Atk_Post)
            SetData("Pilote_PVP", "S_Cible_Atk", $Cible_Atk_Post, "ID", $Pilote_pvp);
        $con = dbconnecti();
        $result = mysqli_query($con, "SELECT Pilotage,S_Mission,S_Longitude,S_Latitude,S_Cible,S_HP,S_Nuit,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,Sandbox FROM Pilote_PVP WHERE ID='$Pilote_pvp'")
        or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav0-player');
        mysqli_close($con);
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Pilotage = $data['Pilotage'];
                $Mission_Type = $data['S_Mission'];
                $Cible = $data['S_Cible'];
                $Longitude = $data['S_Longitude'];
                $Latitude = $data['S_Latitude'];
                $HP = $data['S_HP'];
                $Nuit = $data['S_Nuit'];
                $Avion_Bombe = $data['S_Avion_Bombe'];
                $Avion_Bombe_nbr = $data['S_Avion_Bombe_Nbr'];
                $essence = $data['S_Essence'];
                $Sandbox = $data['Sandbox'];
            }
            mysqli_free_result($result);
            unset($data);
        }
        $con = dbconnecti();
        $result2 = mysqli_query($con, "SELECT Type,Masse,Moteur,Plafond,Engine_Nbr,Train,Helice,ManoeuvreB FROM Avion WHERE ID='$avion'")
        or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav0-avion');
        $result3 = mysqli_query($con, "SELECT Zone,Tour,BaseAerienne,LongPiste FROM Lieu WHERE ID='$base'")
        or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav0-base');
        mysqli_close($con);
        if ($result2) {
            while ($data = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                $Type_avion = $data['Type'];
                $Masse = $data['Masse'];
                $Moteur = $data['Moteur'];
                $Plafond = $data['Plafond'];
                $Engine_Nbr = $data['Engine_Nbr'];
                $ManoeuvreB = $data['ManoeuvreB'];
                $Helice = $data['Helice'];
                $Train = $data['Train'];
            }
            mysqli_free_result($result2);
            unset($data);
        }
        if ($result3) {
            while ($data = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
                $Zone_base = $data['Zone'];
                $Tour_base = $data['Tour'];
                $BaseAerienne = $data['BaseAerienne'];
                $QualitePiste = 100;
                $LongPiste = $data['LongPiste'];
            }
            mysqli_free_result($result3);
            unset($data);
        }
        if ($Avion_Bombe) $Masse += ($Avion_Bombe * $Avion_Bombe_nbr);
        $avion_img = GetAvionImg("Avion", $avion);
        $Pil_mod = (pow($Pilotage, 2) / 1000);
        if ($Nuit)
            $meteo_malus = $meteo + 85;
        else
            $meteo_malus = $meteo;
        //Porte-avions et Hydravions
        if ($Porte_avions > 0) {
            /*$Placement_pa=GetData("Regiment_IA","Vehicule_ID",$Porte_avions,"Placement");
            if($Placement_pa ==8 or $Zone_base ==6)
            {
                $con=dbconnecti();
                $result=mysqli_query($con,"SELECT Nom,Taille,HP FROM Cible WHERE ID='$Porte_avions'");
                mysqli_close($con);
                if($result)
                {
                    while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                    {
                        $Nom_PA=$data['Nom'];
                        $LongPiste_PA=$data['Taille'];
                        $HP_max_PA=$data['HP'];
                    }
                    mysqli_free_result($result);
                    unset($data);
                }
                $HP_PA=GetData("Regiment_IA","Vehicule_ID",$Porte_avions,"HP");
                if(!$HP_PA)$HP_PA=GetData("Regiment","Vehicule_ID",$Porte_avions,"HP");
                if(!$HP_PA)
                {
                    $QualitePiste=100;
                    $Tour_base=100;
                }
                else
                {
                    $QualitePiste=round(($HP_PA/$HP_max_PA)*100);
                    $Tour_base=round(($HP_PA/$HP_max_PA)*100);
                }
                $LongPiste=$LongPiste_PA*($QualitePiste/100);
                $QualitePiste_final=100-$QualitePiste-$meteo_malus;
                $BaseAerienne=1;
                if($avion == 398)$Masse*=0.8;
            }
            elseif($BaseAerienne)
            {
                $QualitePiste_final=100-$QualitePiste;
                $intro.="Votre porte-avions étant ammarré au port, vous utilisez la piste d'envol de la base aérienne terrestre<br>";
            }
            else
            {
                $intro.="Votre porte-avions étant ammarré au port, il ne vous est pas possible de décoller!<br>";
                $finmission=true;
            }*/
            $LongPiste = 350;
            $QualitePiste_final = 0 - $meteo_malus;
        } elseif ($Zone_base == 6 or $Train == 13 or $Train == 16)
            $QualitePiste_final = 0 - $meteo_malus;
        else
            $QualitePiste_final = 100 - $QualitePiste;
        if (!$finmission) {
            $Incident = GetIncident($Pilote_pvp, 1, $Saison, $Zone_base, "Avion", $avion, $c_gaz, true);
            $Decollage = $Pilotage + ($ManoeuvreB / 10) - ($QualitePiste_final * 10) + ($meteo_malus * 3) + $Incident[1] + ($Moral / 10) + ($Courage / 10) + ($Helice * 5) + ($Train * 5) - ((100 - $Tour_base) / 10);
            if ($flaps < 3) $Masse *= (1 - ($flaps / 10));
            if ($BaseAerienne < 3)
                $Takeoff_run = round($Masse / 20 / $c_gaz * 100) - $Pil_mod;
            elseif ($meteo_malus < -19 and $meteo_malus != -70)
                $Takeoff_run = round($Masse / 5 / $c_gaz * 100) - $Pil_mod;
            else
                $Takeoff_run = round($Masse / 10 / $c_gaz * 100) - $Pil_mod;
            if ($Helice == 2)
                $Takeoff_run *= 0.75;
            elseif ($Helice == 1)
                $Takeoff_run *= 0.9;
            if ($BaseAerienne > 2 and ($Train == 2 or $Train > 6))
                $Takeoff_run *= 0.9;
            elseif ($Porte_avions and ($Train == 2 or $Train > 6))
                $Takeoff_run *= 0.9;
            if ($Takeoff_run > $LongPiste and $Train != 13 and $Train != 16)
                $Decollage = -99999999;
            if ($Decollage > 0 or $Admin) {
                if ($Takeoff_run < 75) $Takeoff_run = 50 + ($Masse / 100);
                $intro .= "<p>Vous décollez sans problème, au terme d'une course de " . round($Takeoff_run) . "m !</p>";
                UpdateData("Pilote_PVP", "Points", 1, "ID", $Pilote_pvp);
            } elseif ($Decollage < -50) {
                if ($Decollage == -99999999) {
                    if ($Porte_avions > 0)
                        $intro .= "<p>Votre avion ne parvient pas à s'arracher du pont d'envol. Passant par dessus bord après une course de " . round($Takeoff_run) . "m, vous crashez votre avion en mer !</p>";
                    else
                        $intro .= "<p>Avalant toute la piste (" . round($Takeoff_run) . "m parcouru / piste de " . $LongPiste . "m), votre avion ne parvient pas à s'arracher du sol. Vous vous crashez en bout de piste !</p>";
                } else {
                    if ($Incident[1] < -49)
                        $intro .= '<p>Vous entamez votre course de décollage lorsqu\'<b>' . $Incident[0] . '</b> vous oblige à interrompre votre mission. Quelle poisse !</p>';
                    elseif ($QualitePiste != 0 and ($Train == 13 or $Train == 16))
                        $intro .= "<p>Incapable de déjauger correctement à cause du mauvais temps, votre avion percute une vague de plein fouet !</p>";
                    elseif ($QualitePiste != 0)
                        $intro .= "<p>Vous entamez votre course de décollage, mais vous ne pouvez empêcher votre avion d'aller dans le décor à cause de l'état de la piste !</p>";
                    elseif ($meteo_malus < -49)
                        $intro .= "<p>Vous entamez votre course de décollage, mais la météo vous oblige à interrompre votre mission. Quelle poisse !</p>";
                    elseif ($Incident[1])
                        $intro .= '<p>Vous entamez votre course de décollage lorsqu\'<b>' . $Incident[0] . '</b> vous oblige à interrompre votre mission. Quelle poisse !</p>';
                }
                $intro .= '<p>Votre appareil est gravement endommagé, c\'est une perte totale pour l\'escadrille !</p>';
                $img = Afficher_Image('images/avions/crash' . $avion_img . '.jpg', 'images/avions/crash.jpg', 'crash');
                $finmission = true;
                UpdateData("Pilote_PVP", "Points", -1, "ID", $Pilote_pvp);
            } else {
                $intro .= '<p>Vous entamez votre course de décollage lorsqu\'<b>' . $Incident[0] . '</b> vous oblige à interrompre votre mission. Quelle poisse !</p>
				<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>';
                $img .= Afficher_Image('images/avions/crash' . $avion_img . '.jpg', 'images/avions/crash.jpg', 'crash');
                $finmission = true;
            }
            unset($Incident);
            $toolbar = GetToolbar($chemin, $Pilote_pvp, $avion, $HP, $Mun1, $Mun2, $essence, $meteo, $alt, $Puissance, $Longitude, $Latitude, $Cible, $Mission_Type, $c_gaz, 1, "Avion", $flaps, true);
        }
    }
    if (!$finmission) {
        $Puissance = GetPuissance("Avion", $avion, 0, $HP, 1, 1, $Engine_Nbr);
        $intro .= '<p>Votre objectif se trouve à une distance de <b>' . $Distance . ' km</b>. Vous grimpez à l\'altitude indiquée sur votre plan de vol pour sélectionner votre régime de croisière</p>';
        $chemin = $Distance;
        $gaz_menu = ShowGaz($avion, $c_gaz, $flaps, $alt, false, true);
        $_SESSION['Decollage'] = true;
        $_SESSION['Distance'] = $Distance;
        $img = Afficher_Image('images/avions/vol' . $avion_img . '.jpg', 'images/avions/vol' . $avion_img . '.jpg', $avion_img);
        $titre = 'Régime de croisière';
        $mes = '<form action=\'index.php?view=nav_pvp\' method=\'post\'>
				<input type=\'hidden\' name=\'Chemin\' value=' . $chemin . '>
				<input type=\'hidden\' name=\'Distance\' value=' . $Distance . '>
				<input type=\'hidden\' name=\'Meteo\' value=' . $meteo . '>
				<input type=\'hidden\' name=\'Avion\' value=' . $avion . '>
				<input type=\'hidden\' name=\'Mun1\' value=' . $Mun1 . '>
				<input type=\'hidden\' name=\'Mun2\' value=' . $Mun2 . '>
				<input type=\'hidden\' name=\'Puissance\' value=' . $Puissance . '>
				<input type=\'hidden\' name=\'Battle\' value=' . $Battle . '>
				<input type=\'hidden\' name=\'Camp\' value=' . $Faction . '>
				<input type=\'hidden\' name=\'Enis\' value=\'0\'>' . $gaz_menu . '
				<input type=\'Submit\' title=\'Choisissez votre régime de croisière\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
    } elseif ($c_gaz < 20) {
        $_SESSION['Decollage0'] = true;
        $_SESSION['Distance'] = 0;
        include_once('./end_mission_pvp.php');
    } else {
        $_SESSION['Decollage0'] = true;
        $_SESSION['Distance'] = 0;
        $mes .= "<p class='lead'>FIN DE MISSION</p>";
        $menu .= "<form action='index.php?view=profil_pvp' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
    }
    include_once './default.php';
}