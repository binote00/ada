<?php
require_once '../jfv_inc_sessions.php';
if (isset($_SESSION['AccountID'])) {
    include_once '../jfv_include.inc.php';
    if (!$Admin) $Admin = GetData("Joueur", "ID", $_SESSION['AccountID'], "Admin");
    if ($Admin) {
        $reg = Insec($_POST['reg']);
        $Max_Veh = Insec($_POST['Max']);
        if ($reg > 0 and $Max_Veh > 0) {
            $_SESSION['reg'] = $reg;
            $con = dbconnecti();
            $reset_r = mysqli_query($con, "UPDATE Regiment_IA SET Vehicule_Nbr='$Max_Veh',Moral=100,Visible=0,Move=1,Move_time='0000-00-00 00:00:00' WHERE ID = $reg");
            mysqli_close($con);
            header('Location: ../index.php?view=ground_em_ia');
        }
    } else
        echo '<h1>Vous n\'êtes pas autorisé à effectuer cette action!</h1>';
} else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';