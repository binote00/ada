<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $GHQ or $Admin)
	{
		//echo "<p class='txt-danger'>Cliquer sur l'écusson de l'unité permet d'accéder à son journal.<br>Cliquer sur le nom d'unité permet d'accéder à ses archives.</p>";
		include('em_unites.php');
	}
	else
		PrintNoAccess($country,1,2);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>