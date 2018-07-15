<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
$Pilote_pvp = $_SESSION['Pilote_pvp'];
if ($Pilote_pvp > 0) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    echo "<h1>Missions</h1><div style='overflow:auto; width: 100%;'><table class='table table-dt table-striped'>
            <thead><tr><th>Date</th><th>Bataille</th><th>Pilote</th><th>Avion</th><th>Mission</th></tr></thead>";
    $con = dbconnecti(2);
    $result = mysqli_query($con, "SELECT * FROM Battle");
    mysqli_close($con);
    if ($result) {
        $i = 1;
        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            if ($data['Battle'] == 1)
                $Battle = "Maastricht - Mai 40";
            echo "<tr><td>" . $data['Date'] . "</td><td>" . $Battle . "</td><td>" . GetData("Pilote_PVP", "ID", $data['Pilote'], "Nom") . "</td><td>" . GetAvionIcon($data['Avion']) . "</td><td>" . GetMissionType($data['Mission']) . "</td></tr>";
        }
    }
    echo '</table></div>';
}