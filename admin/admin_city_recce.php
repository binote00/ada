<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(!$Admin)$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1) {
    dbconnect();
    $result = $dbh->prepare("UPDATE Lieu SET Recce=1 WHERE ID=:id");
    $result->bindValue('id',$_GET['id'],1);
    $result->execute();
    header('Location: em_city_ground.php?id='.$_GET['id']);
}