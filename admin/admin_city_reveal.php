<?php
require_once '../jfv_inc_sessions.php';
include_once '../jfv_include.inc.php';
if (!$Admin) $Admin = GetData("Joueur", "ID", $_SESSION['AccountID'], "Admin");
if ($Admin == 1) {
    dbconnect();
    $result = $dbh->prepare("
    UPDATE Regiment_IA r, Pays p SET r.Visible=1
    WHERE r.Pays=p.ID AND r.Lieu_ID=:id AND p.Faction=:faction AND r.Vehicule_Nbr >0");
    $result->bindValue('id', $_GET['id'], 1);
    $result->bindValue('faction', $_GET['f'], 1);
    $result->execute();
    header('Location: ../em_city_ground.php?id=' . $_GET['id']);
}