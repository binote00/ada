<?php
require_once './jfv_inc_sessions.php';
$OfficierID = $_SESSION['Officier_em'];
if ($OfficierID > 0) {
    include_once './jfv_include.inc.php';
    if (!$Front) $Front = GetData("Officier_em", "ID", $OfficierID, "Front");
    echo "<iframe width='100%' height='800' src='./carte_ground.php?map=" . $Front . "&mode=2&frame=1'></iframe>";
    echo "<a href='carte_ground.php?map=" . $Front . "&mode=2' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte (dans un onglet)'></a>";
}