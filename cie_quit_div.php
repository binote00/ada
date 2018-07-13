<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
    include_once('./jfv_include.inc.php');
    include_once('./jfv_inc_em.php');
    if($Front !=12 and ($OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Commandant or $Admin or $Armee >0))
    {
        $Reg = Insec($_POST['Reg']);
        if($Reg){
            $_SESSION['div_id'] = $div;
            $con=dbconnecti();
            $reset_d=mysqli_query($con,"UPDATE Regiment_IA SET Division=NULL,Bataillon=NULL WHERE ID='$Reg'");
            mysqli_close($con);
            if($reset_d)
                $_SESSION['msg']='La '.$Reg.'e Compagnie a été retirée de la division.';
            header( 'Location : index.php?view=ground_em_div');
        }
    }
}