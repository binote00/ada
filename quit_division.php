<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');	
	$Off=Insec($_POST['Off']);
	if($Off >0)
	{
		$con=dbconnecti();
		$reset1=mysqli_query($con,"UPDATE Officier SET Division=0 WHERE ID='$Off'");
		$reset2=mysqli_query($con,"UPDATE Division SET Cdt=0 WHERE Cdt='$Off'");
		$reset3=mysqli_query($con,"UPDATE Regiment_IA SET Bataillon=0 WHERE Bataillon='$Off'");
		mysqli_close($con);
		$mes="Vous avez retiré les unités sélectionnées de l'inventaire de la division.";
		$titre="Gestion des unités terrestres";
		$menu="<a href='index.php?view=ground_em' class='btn btn-default' title='Retour'>Retour</a>";
		include_once('./default.php');
	}
}
?>