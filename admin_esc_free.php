<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(!$Admin)$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1) {
    dbconnect();
    $result = $dbh->prepare("UPDATE Unit SET Mission_IA=0 WHERE ID=:id");
    $result->bindValue('id',$_POST['id'],1);
    $result->execute();
    header('Location: index.php?view=em_unites_'.$_POST['type']);
}