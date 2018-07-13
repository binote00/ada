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
    $Division = Insec($_GET['div']);
    if (($Commandant >0 and ($Commandant == $OfficierEMID))
        or ($Adjoint_Terre >0 and ($Adjoint_Terre == $OfficierEMID))
        or ($Officier_Mer >0 and ($Officier_Mer == $OfficierEMID))
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

    if ($Ordre_ok and $Reg >0 and $Division >0) {
        $_SESSION['reg']=$Reg;
        if ($Division == 9999) {
            $queryd="UPDATE Regiment_IA SET Division=NULL,Bataillon=NULL WHERE ID='$Reg'";
            $div_dest_txt="l'état-major";
        } else {
            $queryd="UPDATE Regiment_IA SET Division='$Division',Bataillon=NULL WHERE ID='$Reg'";
            $div_dest_txt='une division';
        }
        $con=dbconnecti();
        $reset_d=mysqli_query($con,$queryd);
        mysqli_close($con);
        if ($reset_d)
            $_SESSION['msg']='La Compagnie a été affectée à '.$div_dest_txt.'.<br>Elle a également été retirée de son ancien bataillon.';
        else
            $mes='<div class="alert alert-danger">[Erreur]</div>';
        //$img="<img src='images/em".$country.".jpg' style='width:100%;'>";
        header('Location : index.php?view=ground_em_ia');
    }
}
