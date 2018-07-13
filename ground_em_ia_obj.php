<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
    $Ordre_ok=false;
    $country = $_SESSION['country'];
    include_once('./jfv_include.inc.php');
    include_once('./jfv_inc_em.php');
    $Reg = Insec($_GET['id']);
    $Objectif = Insec($_GET['obj']);
    if (($Commandant >0 and ($Commandant == $OfficierEMID))
        or ($Adjoint_Terre >0 and ($Adjoint_Terre == $OfficierEMID))
        or ($Officier_Mer >0 and ($Officier_Mer == $OfficierEMID))
        or ($Officier_Log >0 and ($Officier_Log == $OfficierEMID))
        or $Admin ==1 or $GHQ
    )
        $Ordre_ok=true;
    else{
        $con=dbconnecti();
        $Cdt_army=mysqli_result(mysqli_query($con, "SELECT a.Cdt FROM Armee as a,Division as d,Regiment_IA as r WHERE a.ID=d.Armee AND r.Division=d.ID AND r.ID=$Reg"),0);
        mysqli_close($con);
        if($Cdt_army == $OfficierEMID)
            $Ordre_ok=true;
    }
    if ($Ordre_ok and $Reg >0) {
        $_SESSION['reg']=$Reg;
        if(!$Objectif)$Objectif='NULL';
        $con=dbconnecti();
        $reset_d=mysqli_query($con,"UPDATE Regiment_IA SET objectif='$Objectif' WHERE ID='$Reg'");
        if($Objectif)$obj_nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Lieu WHERE ID='$Objectif'"),0);
        mysqli_close($con);
        if($reset_d and $Objectif)
            $_SESSION['msg']='L\'objectif de l\'unité est <b>'.$obj_nom.'</b>';
        else
            $mes='<div class="alert alert-danger">[Erreur]</div>';
        header('Location : index.php?view=ground_em_ia');
    }
}
