<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID'])) {
    include_once('./jfv_include.inc.php');
    if ($_GET) {
        dbconnect();
        $resultv = $dbh->prepare("SELECT ID,Nom FROM Cible WHERE Pays=:pays ORDER BY Nom");
        $resultv->bindParam('pays', $_GET['pays'], 1);
        $resultv->execute();
        while ($data = $resultv->fetchObject()) {
            $lieux .= '<option value="' . $data->ID . '">' . $data->Nom . '</option>';
        }
        echo $lieux;
    }
}
