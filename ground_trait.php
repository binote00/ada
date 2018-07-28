<?php
require_once './jfv_inc_sessions.php';
$Officier = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if ($Officier > 0 or $OfficierEMID > 0) {
    include_once './jfv_include.inc.php';
    $Off = Insec($_POST['Off']);
    $Trait_o = Insec($_POST['Trait_o']);
    if ($Off and $Trait_o) {
        if ($Officier > 0)
            SetData("Officier", "Trait", $Trait_o, "ID", $Off);
        elseif ($OfficierEMID > 0)
            SetData("Officier_em", "Trait", $Trait_o, "ID", $Off);
        $mes = "Vous avez choisi le trait de votre officier!";
    }
    $titre = "Choix de trait";
    include_once './default.php';
}