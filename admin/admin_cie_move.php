<?php
require_once '../jfv_inc_sessions.php';
if (isset($_SESSION['AccountID'])) {
    include_once '../jfv_include.inc.php';
    if (!$Admin) $Admin = GetData("Joueur", "ID", $_SESSION['AccountID'], "Admin");
    if ($Admin) {
        $dest = Insec($_POST['dest']);
        $reg = Insec($_POST['reg']);
        if ($dest > 0 and $reg > 0) {
            $_SESSION['reg'] = $reg;
            $con = dbconnecti();
            $resetbase = mysqli_query($con, "UPDATE Regiment_IA SET Lieu_ID='$dest' WHERE ID = $reg");
            mysqli_close($con);
            header('Location: ../index.php?view=ground_em_ia');
        }
    } else
        echo '<h1>Vous n\'êtes pas autorisé à effectuer cette action!</h1>';
} else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';