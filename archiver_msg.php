<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$ID = Insec($_POST['msg']);
	include_once('./menu_messagerie.php');	
	if($ID >0)
	{
		$con=dbconnecti(3);
		$ok_up=mysqli_query($con,"UPDATE Messages SET Archive=1 WHERE ID='$ID'");			
		mysqli_close($con);
		echo "<p>Message effac� avec succ�s!</p>";
	}
	else
		echo "<p>Erreur!</p>";
}
else
	echo "<h1>Vous devez �tre connect� pour acc�der � cette page!</h1>";
?>