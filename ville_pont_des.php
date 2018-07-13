<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
    include_once('./jfv_include.inc.php');
    $OfficierEMID=$_SESSION['Officier_em'];
    if($OfficierEMID >0)
    {
        $ville=Insec($_POST['lieu']);
        if($ville >0){
            dbconnect();
            $result = $dbh->prepare("UPDATE Lieu SET Pont=0 WHERE ID=:ville");
            $result->bindValue('ville',$ville,1);
            $result->execute();
            header('Location: ./index.php?view=ville');
        }
    }
    else
        echo '<h1>Vous n\'êtes pas autorisé à effectuer cette action!</h1>';
}
else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';