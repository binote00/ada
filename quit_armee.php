<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');	
	$Off=Insec($_POST['Off']);
	$Armee=Insec($_POST['Armee']);
	if($Off >0 and $Armee >0)
	{
		$con=dbconnecti();
		$reset1=mysqli_query($con,"UPDATE Officier_em SET Armee=0,Postuler=0 WHERE ID='".$Off."'");
		$reset2=mysqli_query($con,"UPDATE Armee SET Cdt=NULL WHERE Cdt='".$Off."'");
		$reset3=mysqli_query($con,"UPDATE Unit SET Armee=0 WHERE Armee='".$Armee."'");
		mysqli_close($con);
        $_SESSION['msg_em'] = 'Vous avez retiré l\'officier du poste de commandant de l\'armée';
        header( 'Location : index.php?view=ground_em');
	}
}