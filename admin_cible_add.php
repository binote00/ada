<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
    include_once('./jfv_include.inc.php');
    dbconnect();
    $resulta = $dbh->prepare("SELECT Admin FROM Joueur WHERE ID=:accountid");
    $resulta->bindValue('accountid',$_SESSION['AccountID'],1);
    $resulta->execute();
    $dataa = $resulta->fetchObject();
    if($dataa->Admin){
        header('Location: ./index.php?view=admin_cibles');
    }
}