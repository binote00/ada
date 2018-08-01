<?php
require_once '../jfv_inc_sessions.php';
if (isset($_SESSION['AccountID'])) {
    include_once '../jfv_include.inc.php';
    $Armee = Insec($_POST['Armee']);
    $Base = Insec($_POST['Base']);
    $OfficierEMID = $_SESSION['Officier_em'];
    if ($OfficierEMID > 0 and $Armee > 0 and $Base > 0) {
        $con = dbconnecti();
        $resetbase = mysqli_query($con, "UPDATE Armee SET Base='$Base' WHERE ID='$Armee'");
        $resultb = mysqli_query($con, "SELECT a.Nom,l.Nom as Ville FROM Armee as a,lieu as l WHERE a.Base=l.ID AND a.ID='$Armee'");
        mysqli_close($con);
        if ($resultb) {
            while ($data = mysqli_fetch_array($resultb)) {
                $Armee_Nom = $data['Nom'];
                $Base_Nom = $data['Ville'];
            }
            mysqli_free_result($resultb);
        }
        header('Location: ../index.php?view=ground_em');
    } else
        echo '<h1>Vous n\'êtes pas autorisé à effectuer cette action!</h1>';
} else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';