<?php
require_once '../jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if (isset($_SESSION['AccountID']) AND $OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once '../jfv_include.inc.php';
    include_once '../jfv_inc_em.php';
    if ($GHQ or $Admin) {
        $Armee = Insec($_POST['Div']);
        $Front_Dest = Insec($_POST['Front']);
        if ($Armee > 0) {
            include_once '../jfv_ground.inc.php';
            $Base = Get_Retraite($Front_Dest, $country, 40);
            $con = dbconnecti();
            $resetdiv = mysqli_query($con, "UPDATE Division SET Armee=0,Cdt=NULL WHERE Armee=$Armee");
            $resetar = mysqli_query($con, "UPDATE Armee SET Front='$Front_Dest',Base='$Base',Cdt=NULL,Objectif=0,limite_ouest=0,limite_est=0,limite_nord=0,limite_sud=0 WHERE ID=$Armee");
            mysqli_close($con);
            if ($resetar and $resetdiv)
                $_SESSION['msg_em'] = 'L\'armée a changé de front avec succès!';
            else
                $_SESSION['msg_em_red'] = 'Erreur!';
        }
        header('Location : ../index.php?view=ground_em');
    } else
        PrintNoAccess($country, 1);
}