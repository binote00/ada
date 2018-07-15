<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
require_once __DIR__ . '/../jfv_include.inc.php';
include_once __DIR__ . '/../lib/Output.php';

$Tete = Insec($_POST['1']);
$Visage = Insec($_POST['2']);
$Dos = Insec($_POST['3']);
$Torse = Insec($_POST['4']);
$Ceinture = Insec($_POST['5']);
$Poignet = Insec($_POST['6']);
$Mains = Insec($_POST['7']);
$Arme = Insec($_POST['8']);
$Pieds = Insec($_POST['9']);
$Poche = Insec($_POST['10']);
$Sac = Insec($_POST['11']);
$Pilote_pvp = $_SESSION['Pilote_pvp'];
if (isset($_SESSION['AccountID']) AND $Pilote_pvp > 0 AND isset($Dos) AND isset($Torse)) {
    $country = $_SESSION['country'];
    $con = dbconnecti();
    $result = mysqli_query($con, "SELECT Slot1,Slot2,Slot3,Slot4,Slot5,Slot6,Slot7,Slot8,Slot9,Slot10,Slot11 FROM Pilote_PVP WHERE ID = $Pilote_pvp");
    if ($result) {
        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            if (!$Tete)
                $Tete = $data['Slot1'];
            if (!$Visage)
                $Visage = $data['Slot2'];
            if (!$Dos)
                $Dos = $data['Slot3'];
            if (!$Torse)
                $Torse = $data['Slot4'];
            if (!$Ceinture)
                $Ceinture = $data['Slot5'];
            if (!$Poignet)
                $Poignet = $data['Slot6'];
            if (!$Mains)
                $Mains = $data['Slot7'];
            if (!$Arme)
                $Arme = $data['Slot8'];
            if (!$Pieds)
                $Pieds = $data['Slot9'];
            if (!$Poche)
                $Poche = $data['Slot10'];
            if (!$Sac)
                $Sac = $data['Slot11'];
        }
        mysqli_free_result($result);
        unset($result);
    }
    $reset = mysqli_query($con, "UPDATE Pilote_PVP SET Slot1='$Tete',Slot2='$Visage',Slot3='$Dos',Slot4='$Torse',Slot5='$Ceinture',Slot6='$Poignet',Slot7='$Mains',Slot8='$Arme',Slot9='$Pieds',Slot10='$Poche',Slot11='$Sac' WHERE ID='$Pilote_pvp'");
    mysqli_close($con);
    Output::ShowAlert('Vous recevez votre nouvel équipement');
//    $mes = 'Vous recevez votre nouvel équipement';
//    $img = Afficher_Image('./images/equip_valider.jpg', './images/equip_valider.jpg', 'Equipement validé!', 50);
//    $skills .= "<a class='btn btn-default' href='../index.php?view=inventaire_pvp'>Retour à l'inventaire</a>";
//    $titre = 'Equipement';
    header("Location: ../index.php?view=pvp/inventaire_pvp");
}
//include_once '../index.php';