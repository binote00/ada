<?php
require_once('./jfv_inc_sessions.php');
$User = $_SESSION['AccountID'];
if($User >0)
{
	include_once('./jfv_include.inc.php');
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT DATE_ADD('Premium_date',INTERVAL 1 MONTH),Premium=Premium+1 FROM Joueur WHERE ID='$User'");
	mysqli_close($con);	
	$img="<img src='/images/Logo_ada.png'>";	
	$mes="Merci pour votre achat !";
}
include_once('./index.php');
?>