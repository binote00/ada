<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(!$Admin)$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1) {
    if($_GET['m'] =='1'){
        $meteo = '-5';
    }elseif($_GET['m'] =='2'){
        $meteo = '-75';
    }
    else{
        $meteo = false;
    }
    if($meteo){
        dbconnect();
        $result = $dbh->prepare("UPDATE Lieu SET Meteo=$meteo WHERE ID=:id");
        $result->bindValue('id',$_GET['id'],1);
        $result->execute();
    }
    header('Location: em_city_ground.php?id='.$_GET['id']);
}