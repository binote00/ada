<?php

function GetMapPos($longit, $latit, $long_par_km, $lat_par_km, $SensH, $SensV)
{
    if ($SensH == "ouest")
        $longit -= $long_par_km;
    else
        $longit += $long_par_km;
    if ($SensV == "sud")
        $latit -= $lat_par_km;
    else
        $latit += $lat_par_km;
    return array($longit, $latit);
}

function Chemin_Retour()
{
    //MapPos Retour
    $SensH = $_SESSION['SensH'];
    $SensV = $_SESSION['SensV'];
    if ($SensH == "ouest")
        $SensH = "est";
    elseif ($SensH == "est")
        $SensH = "ouest";
    else
        echo "Erreur de localisation";
    if ($SensV == "sud")
        $SensV = "nord";
    elseif ($SensV == "nord")
        $SensV = "sud";
    else
        echo "Erreur de localisation";
    $_SESSION['SensH'] = $SensH;
    $_SESSION['SensV'] = $SensV;
}

function GetCible($Pays, $Categorie)
{
    $Date_Campagne = GetData("Conf_Update", "ID", 2, "Date");
    $query = "SELECT ID FROM Cible WHERE Date <'$Date_Campagne' AND Categorie='$Categorie' AND (Pays='$Pays' OR Pays=0) ORDER BY RAND() LIMIT 1";
    $con = dbconnecti();
    $result = mysqli_query($con, $query);
    mysqli_close($con);
    if ($result) {
        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            return $data['ID'];
        }
        mysqli_free_result($result);
    }
}

function GetZone($Zone)
{
    switch ($Zone) {
        case 0:
            $zone_txt = "de plaines";
            $Malus_Reperer = 0;
            break;
        case 1:
            $zone_txt = "vallonnée";
            $Malus_Reperer = 10;
            break;
        case 2:
            $zone_txt = "forestière";
            $Malus_Reperer = 20;
            break;
        case 3:
            $zone_txt = "collines boisées";
            $Malus_Reperer = 50;
            break;
        case 4:
            $zone_txt = "montagneuse";
            $Malus_Reperer = 50;
            break;
        case 5:
            $zone_txt = "montagnes boisées";
            $Malus_Reperer = 100;
            break;
        case 6:
            $mes = "maritime";
            $Malus_Reperer = 0;
            break;
        case 7:
            $zone_txt = "urbaine";
            $Malus_Reperer = 50;
            break;
        case 8:
            $zone_txt = "désertique";
            $Malus_Reperer = 0;
            break;
        case 9:
            $zone_txt = "jungle";
            $Malus_Reperer = 30;
            break;
        case 11:
            $zone_txt = "marécageuse";
            $Malus_Reperer = 10;
            break;
    }
    return $zone_txt;
}

function GetNation($Nation)
{
    switch ($Nation) {
        case 1:
            $Pays = "Deutsches Reich";
            break;
        case 2:
            $Pays = "British Empire";
            break;
        case 3:
            $Pays = "Royaume de Belgique";
            break;
        case 4:
            $Pays = "République Française";
            break;
        case 5:
            $Pays = "Koninkrijk der Nederlanden";
            break;
        case 6:
            $Pays = "Regno d\'Italia";
            break;
        case 7:
            $Pays = "United States of America";
            break;
        case 8:
            $Pays = "SSSR";
            break;
        case 9:
            $Pays = "Nippon-koku";
            break;
        case 10:
            $Pays = "Hellas";
            break;
        case 15:
            $Pays = "Tsarstvo Balgariya";
            break;
        case 17:
            $Pays = "Kraljevina Jugoslavija";
            break;
        case 18:
            $Pays = "România";
            break;
        case 19:
            $Pays = "Magyarország";
            break;
        case 20:
            $Pays = "Suomen tasavalta";
            break;
        case 24:
            $Pays = "Regno Albanese";
            break;
        case 35:
            $Pays = "Kongeriket Norge";
            break;
        case 36:
            $Pays = "Groussherzogtum Lëtzebuerg";
            break;
        default:
            $Pays = "Neutre";
            break;
    }
    return $Pays;
}

function AddCandidat($Avion_db, $PlayerID, $avion, $HP, $Puissance, $Essence, $chemin, $Distance, $Mun1, $Mun2, $alt, $Cible, $Nuit)
{
    $chemin = round($chemin);
    $Puissance = round($Puissance);
    if ($Avion_db == "Avions_Persos" or $Avion_db == "Avions_Sandbox")
        $avion = GetData($Avion_db, "ID", $avion, "ID_ref");
    $Pays = GetData("Pilote", "ID", $PlayerID, "Pays");
    $date = date('Y-m-d G:i');
    if (GetData("Duels_Candidats", "PlayerID", $PlayerID, "ID")) {
        $query = "UPDATE Duels_Candidats SET Avion='$avion',Lieu='$Cible',Date='$date',Altitude='$alt',HP='$HP',Essence='$Essence',chemin='$chemin',Distance='$Distance',Cycle='$Nuit' WHERE PlayerID='$PlayerID'";
        $con = dbconnecti();
        $ok = mysqli_query($con, $query);
        mysqli_close($con);
        if (!$ok) {
            $msg .= 'Erreur de mise à jour' . mysqli_error($con);
            mail('binote@hotmail.com', 'Aube des Aigles: AddCandidat Update Error', $msg);
        }
    } else {
        $query = "INSERT INTO Duels_Candidats (PlayerID, Date, Lieu, HP, Altitude, Essence, Country, Avion, Mun1, Mun2, Puissance, chemin, Distance)
		VALUES ('$PlayerID','$date','$Cible','$HP','$alt','$Essence','$Pays','$avion','$Mun1','$Mun2','$Puissance','$chemin','$Distance')";
        $con = dbconnecti();
        $ok = mysqli_query($con, $query);
        mysqli_close($con);
        if (!$ok) {
            $msg .= 'Erreur insert ' . mysqli_error($con);
            mail('binote@hotmail.com', 'Aube des Aigles: AddCandidat Insert Error', $msg);
        }
    }
    //SetData("Pilote","PvP",$Cible,"ID",$PlayerID);
}

function AddRecce($Avion_db, $Nom, $avion, $PlayerID, $Unite_win, $Lieu, $Type = 0)
{
    if ($Avion_db == "Avions_Persos") $avion = GetData($Avion_db, "ID", $avion, "ID_ref");
    $date = date('Y-m-d G:i');
    $query = "INSERT INTO Recce (Nom, Avion, Joueur, Unite, Lieu, Date, Type)
	VALUES ('$Nom','$avion','$PlayerID','$Unite_win','$Lieu','$date','$Type')";
    $con = dbconnecti();
    $ok = mysqli_query($con, $query);
    mysqli_close($con);
    if (!$ok) {
        $msg .= 'Erreur de mise à jour ' . mysqli_error($con);
        mail('binote@hotmail.com', 'Aube des Aigles: AddRecce Error', $msg);
    }
}

function AddRavit($Avion_db, $avion, $PlayerID, $Unite, $Lieu, $Unite_Cible, $Cargaison, $Quantite, $Air = false)
{
    if ($Avion_db == "Avions_Persos" or $Avion_db == "Avions_Sandbox") $avion = GetData($Avion_db, "ID", $avion, "ID_ref");
    $date = date('Y-m-d G:i');
    $query = "INSERT INTO Ravitaillements (PlayerID, Avion, Unite, Lieu, Unite_Cible, Cargaison, Quantite, Cible_Type, Date)
	VALUES ('$PlayerID','$avion','$Unite','$Lieu','$Unite_Cible','$Cargaison','$Quantite','$Air','$date')";
    $con = dbconnecti();
    $ok = mysqli_query($con, $query);
    mysqli_close($con);
    if (!$ok) {
        $msg .= "Erreur de mise à jour" . mysqli_error($con);
        mail('binote@hotmail.com', 'Aube des Aigles: AddRavit Error', $msg);
    }
}

function AddSauvetage($Avion_db, $avion, $PlayerID, $Unite, $Lieu, $MIA, $Cycle)
{
    if ($Avion_db == "Avions_Persos" or $Avion_db == "Avions_Sandbox") $avion = GetData($Avion_db, "ID", $avion, "ID_ref");
    $date = date('Y-m-d G:i');
    $query = "INSERT INTO Sauvetage (Avion, PlayerID, Unite, Lieu, Date, MIA, Cycle)
	VALUES ('$avion','$PlayerID','$Unite','$Lieu','$date','$MIA','$Cycle')";
    $con = dbconnecti();
    $ok = mysqli_query($con, $query);
    mysqli_close($con);
    if (!$ok) {
        $msg .= "Erreur de mise à jour" . mysqli_error($con);
        mail('binote@hotmail.com', 'Aube des Aigles: AddSauvetage Error', $msg);
    }
}