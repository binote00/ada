<?php
/**
 * User: JF
 * Date: 04-02-18
 * Time: 13:59
 */

require_once '../jfv_inc_sessions.php';
include_once '../jfv_include.inc.php';
include_once '../jfv_txt.inc.php';

if ($_SESSION['Encodage'] == 1) {
    $msg = Insec($_POST['text']);
    $titre = Insec($_POST['titre']);
    $color = Insec($_POST['color']);
    dbconnect();
    $result = $dbh->prepare("UPDATE Msg SET msg=:txt,titre=:titre,color=:color WHERE id=1");
    $result->bindParam('txt', $msg, 2);
    $result->bindParam('titre', $titre, 2);
    $result->bindParam('color', $color, 1);
    $result->execute();
    header('Location: ../index.php?view=_admin');
}