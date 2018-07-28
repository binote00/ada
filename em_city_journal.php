<?php
require_once './jfv_inc_sessions.php';
$PlayerID = $_SESSION['PlayerID'];
$OfficierID = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if ($PlayerID > 0 xor $OfficierID > 0 xor $OfficierEMID > 0) {
    include_once './jfv_include.inc.php';
    $Premium = GetData("Joueur", "ID", $_SESSION['AccountID'], "Premium");
    if ($Premium) {
        include_once './jfv_txt.inc.php';
        $country = $_SESSION['country'];
        if ($PlayerID) {
            $con = dbconnecti();
            $result = mysqli_query($con, "SELECT Unit,Avancement,Renseignement,Admin FROM Pilote WHERE ID='$PlayerID'");
            mysqli_close($con);
            if ($result) {
                while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Unite = $data['Unit'];
                    $Avancement = $data['Avancement'];
                    $Renseignement = $data['Renseignement'];
                    $Admin = $data['Admin'];
                }
                mysqli_free_result($result);
            }
            $Base = GetData("Unit", "ID", $Unite, "Base");
        } elseif ($OfficierID) {
            $con = dbconnecti();
            $result = mysqli_query($con, "SELECT Avancement,Admin FROM Officier WHERE ID='$OfficierID'");
            mysqli_close($con);
            if ($result) {
                while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Avancement = $data['Avancement'];
                    $Admin = $data['Admin'];
                }
                mysqli_free_result($result);
            }
        } elseif ($OfficierEMID) {
            $con = dbconnecti();
            $result = mysqli_query($con, "SELECT Avancement,Admin FROM Officier_em WHERE ID='$OfficierEMID'");
            mysqli_close($con);
            if ($result) {
                while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Avancement = $data['Avancement'];
                    $Admin = $data['Admin'];
                }
                mysqli_free_result($result);
            }
        }
        if ($Admin == 1 or $Avancement > 4999 or $Renseignement > 100)
            $Officier_acces = true;
        elseif ($Avancement > 499)
            $Pilote_acces = true;
        if ($Officier_acces or $Pilote_acces) {
            if (!$Cible)
                $Cible = Insec($_POST['id']);
            if (!$Cible)
                $Cible = Insec($_GET['id']);
            if ($Cible) {
                $con = dbconnecti();
                $Cible = mysqli_real_escape_string($con, $Cible);
                $resultc = mysqli_query($con, "SELECT Nom,Latitude,Longitude FROM Lieu WHERE ID='$Cible'");
                mysqli_close($con);
                if ($resultc) {
                    while ($data = mysqli_fetch_array($resultc, MYSQLI_ASSOC)) {
                        $Event_Lieu_Nom = $data['Nom'];
                        $Lat = $data['Latitude'];
                        $Long = $data['Longitude'];
                    }
                    mysqli_free_result($resultc);
                }
                if ($Long > 67)
                    $Front = 3;
                elseif ($Lat > 60)
                    $Front = 5;
                elseif ($Long > 13 and $Lat > 50.5)
                    $Front = 4;
                elseif ($Long > 13 and $Lat > 41)
                    $Front = 1;
                elseif ($Lat < 43)
                    $Front = 2;
                else
                    $Front = 0;
                $mes = "<h1>" . $Event_Lieu_Nom . "</h1>";
                $in_em = "79,84,85,87,89,96,97,120,121,122,123,124,130,131,135,179,188,189,280,281,282,283,284";
                $query = "SELECT ID,Event_Type,`Date`,Lieu,Unit,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Lieu='$Cible' AND Event_Type IN (" . $in_em . ") ORDER BY ID DESC LIMIT 100";
                $con = dbconnecti(4);
                $msc = microtime(true);
                $result = mysqli_query($con, $query);
                $msc = microtime(true) - $msc;
                mysqli_close($con);
                if ($result) {
                    while ($Classement = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $Event_Date = substr($Classement['Date'], 0, 16);
                        $Event_Type = $Classement['Event_Type'];
                        $Event_Lieu = $Classement['Lieu'];
                        $Event_PlayerID = $Classement['PlayerID'];
                        $Event_Avion = $Classement['Avion'];
                        $Event_Pilote_eni = $Classement['Pilote_eni'];
                        $Event_Avion_Nbr = $Classement['Avion_Nbr'];
                        if ($Event_Type != 97 and $Event_Type < 280)
                            $Event_Pilote_Nom = GetData("Pilote", "ID", $Event_PlayerID, "Nom");
                        else
                            $Event_Pilote_Nom = GetData("Pilote_IA", "ID", $Event_PlayerID, "Nom");
                        $Event_Avion_Nom = GetData("Avion", "ID", $Event_Avion, "Nom");
                        $Event_Avion_eni_Nom = GetData("Avion", "ID", $Event_Avion_Nbr, "Nom");
                        switch ($Event_Type) {
                            case 79:
                                if (!$Event_Avion_Nom) $Event_Avion_Nom = "avion non identifié";
                                $Event .= $Event_Date . " : La batterie de DCA <b>" . GetData("Armes", "ID", $Event_Pilote_eni, "Nom") . "</b> a endommagé un " . $Event_Avion_Nom . " au-dessus de " . $Event_Lieu_Nom . "<br>";
                                break;
                            case 84:
                                if ($Event_Pilote_eni) {
                                    $Pilote_eni = GetData("Pilote", "ID", $Event_Pilote_eni, "Nom");
                                    $Event .= $Event_Date . " : " . $Pilote_eni . " a <span class='text-danger'>intercepté</span> <b>" . $Event_Pilote_Nom . "</b> à bord de son " . $Event_Avion_Nom . " lors d'une patrouille, dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                } else
                                    $Event .= $Event_Date . " : Notre patrouille composée de " . $Event_Avion_eni_Nom . " a <span class='text-danger'>intercepté</span> " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 85:
                                $Event .= $Event_Date . " : Nos avions escortant " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " ont tenu a distance les avions ennemis dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 87:
                                if (!$Admin) {
                                    $Pays_a = GetData("Pilote", "ID", $Event_PlayerID, "Pays");
                                    $Pays_b = GetData("Pilote", "ID", $Event_Pilote_eni, "Pays");
                                }
                                if ($Pays_a == $country or $Pays_b == $country or $Admin) {
                                    if ($Event_Pilote_eni) {
                                        $Pilote_eni = GetData("Pilote", "ID", $Event_Pilote_eni, "Nom");
                                        $Event .= $Event_Date . " : <b>" . $Pilote_eni . "</b> a <span class='text-primary'>escorté</span> avec succès " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " lors d'une mission au-dessus de " . $Event_Lieu_Nom . ".<br>";
                                    } else
                                        $Event .= $Event_Date . " : Un de nos avions escortant " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a été <span class='text-warning'>abattu</span> par un " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                } elseif ($Event_Pilote_eni)
                                    $Event .= $Event_Date . " : Un " . $Event_Avion_Nom . " a été <span class='text-primary'>escorté</span> lors d'une mission au-dessus de " . $Event_Lieu_Nom . ".<br>";
                                else
                                    $Event .= $Event_Date . " : Un avion escortant un " . $Event_Avion_Nom . " a été <span class='text-warning'>abattu</span> par un " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 89:
                                if (!$Admin) {
                                    $Pays_a = GetData("Pilote", "ID", $Event_PlayerID, "Pays");
                                    $Pays_b = GetData("Pilote", "ID", $Event_Pilote_eni, "Pays");
                                }
                                if ($Pays_a == $country or $Pays_b == $country or $Admin) {
                                    $Pilote_eni = GetData("Pilote", "ID", $Event_Pilote_eni, "Nom");
                                    $Event .= $Event_Date . " : <b>" . $Event_Pilote_Nom . "</b> à bord de son " . $Event_Avion_Nom . " a bénéficié d'une reconnaissance effectuée par <b>" . $Pilote_eni . "</b> dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                } else
                                    $Event .= $Event_Date . " : Un " . $Event_Avion_Nom . " a bénéficié d'une reconnaissance effectuée dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 96:
                                $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                $Event .= $Event_Date . " : " . $Pilote_eni . " escortant <b>" . $Event_Pilote_Nom . "</b> a été <span class='text-warning'>abattu</span> par un " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 97:
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " a été <span class='text-warning'>abattu</span> lors d'un sauvetage à bord d'un <b>" . $Event_Avion_Nom . "</b> au-dessus de " . $Event_Lieu_Nom . "<br>";
                                break;
                            case 120:
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " a saboté <b>" . $Event_Avion_Nbr . " " . $Event_Avion_Nom . "</b>, sur la base de " . $Event_Lieu_Nom . "<br>";
                                break;
                            case 121:
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " a saboté <b>" . $Event_Avion_Nbr . " litres de carburant</b>, sur la base de " . $Event_Lieu_Nom . "<br>";
                                break;
                            case 122:
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " a saboté <b>" . $Event_Avion_Nbr . " munitions</b>, sur la base de " . $Event_Lieu_Nom . "<br>";
                                break;
                            case 123:
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " a saboté <b>" . $Event_Avion_Nbr . " canon de DCA</b>, sur la base de " . $Event_Lieu_Nom . "<br>";
                                break;
                            case 124:
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " a volé de l'équipement, sur la base de " . $Event_Lieu_Nom . "<br>";
                                break;
                            case 179:
                                $Event .= $Event_Date . " : Une batterie de DCA a endommagé un " . $Event_Avion_Nom . " de <b>" . GetData("Armes", "ID", $Event_Pilote_eni, "Nom") . "</b> au-dessus de " . $Event_Lieu_Nom . "<br>";
                                break;
                            case 180:
                                if ($Event_Pilote_eni) {
                                    $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                    $Event .= $Event_Date . " : " . $Pilote_eni . " a été <span class='text-warning'>abattu</span> par " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " lors d'une escorte dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                } else
                                    $Event .= $Event_Date . " : Un de nos avions escortant " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a été <span class='text-warning'>abattu</span> par un " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 181:
                                if ($Event_Pilote_eni) {
                                    $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                    if ($Pilote_eni == $Event_Pilote_Nom)
                                        $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " a été <span class='text-warning'>abattu</span> à bord de son " . $Event_Avion_eni_Nom . " lors d'une patrouille dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                    else
                                        $Event .= $Event_Date . " : " . $Pilote_eni . " a été <span class='text-warning'>abattu</span> lors d'une patrouille dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                } else
                                    $Event .= $Event_Date . " : Une de nos patrouilles escortant " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a été <span class='text-warning'>abattue</span> par un " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 188:
                                $Event .= $Event_Date . " : <b>" . $Event_Pilote_Nom . "</b> à bord de son " . $Event_Avion_Nom . " a abattu un " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ", diminuant la couverture aérienne ennemie.<br>";
                                break;
                            case 189:
                                $Event_Pilote_Nom = GetData("Pilote_IA", "ID", $Event_PlayerID, "Nom");
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a abattu un " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ", diminuant la couverture aérienne ennemie.<br>";
                                break;
                            case 191:
                                $Event_Pilote_Nom = GetData("Pilote_IA", "ID", $Event_PlayerID, "Nom");
                                if ($Event_Pilote_eni) {
                                    $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                    if ($Pilote_eni == $Event_Pilote_Nom)
                                        $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " a été <span class='text-warning'>abattu</span> à bord de son " . $Event_Avion_eni_Nom . " lors d'une patrouille dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                    else
                                        $Event .= $Event_Date . " : " . $Pilote_eni . " a été <span class='text-warning'>abattu</span> lors d'une patrouille dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                } else
                                    $Event .= $Event_Date . " : Une de nos patrouilles escortant " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a été <span class='text-warning'>abattue</span> par un " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 280:
                                $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                $Event .= $Event_Date . " : Le chasseur d'escorte " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a été <span class='text-warning'>abattu</span> par le chasseur en couverture " . $Pilote_eni . " à bord de son " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 281:
                                $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                $Event .= $Event_Date . " : Le chasseur en couverture " . $Pilote_eni . " à bord de son " . $Event_Avion_eni_Nom . " a été <span class='text-warning'>abattu</span> par le chasseur d'escorte " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 282:
                                $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                $Event .= $Event_Date . " : Le chasseur en couverture " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a été <span class='text-warning'>abattu</span> par " . $Pilote_eni . " à bord de son " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 283:
                                $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a été <span class='text-warning'>abattu</span> par le chasseur en couverture " . $Pilote_eni . " à bord de son " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                            case 284:
                                $Pilote_eni = GetData("Pilote_IA", "ID", $Event_Pilote_eni, "Nom");
                                $Event .= $Event_Date . " : " . $Event_Pilote_Nom . " à bord de son " . $Event_Avion_Nom . " a été <span class='text-danger'>intercepté</span> par le chasseur en couverture " . $Pilote_eni . " à bord de son " . $Event_Avion_eni_Nom . " dans les environs de " . $Event_Lieu_Nom . ".<br>";
                                break;
                        }
                    }
                    mysqli_free_result($result);
                }
                if ($msc > 5) {
                    mail(EMAIL_LOG, 'Aube des Aigles: Slow EscJournal', $msc . ' secondes pour unité ' . $Unite_Nom . ' (' . $Unite . ')');
                    echo "<p class='lead'>L'affichage de cette page est trop lent sur votre système. Veuillez vider le cache de votre navigateur internet et/ou utiliser une connexion plus stable.</p>";
                }
                $mes .= $Event;
                include_once './default_blank.php';
            }
        }
    } else
        echo "<table class='table'><tr><td><img src='images/acces_premium.png'></td></tr><tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr></table>";
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";