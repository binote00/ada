<?php
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	include_once('./jfv_include.inc.php');
	$Faction=Insec($_POST['Camp']);
	$Msg=htmlspecialchars(Insec($_POST['Mes']));
	if(strlen($Msg) >2)
	{
		if($Admin)
			$Mode=9;
		else
			$Mode=0;
		$date=date('Y-m-d G:i');
		$query="INSERT INTO Cchat (CDate, PlayerID, Mode, Faction, Msg)
		VALUES ('$date','$AccountID','$Mode','$Faction','$Msg')";
		$con=dbconnecti(5);
		$reset=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$Faction)
			header("Location: ./index.php?view=live_chat");
		else
			header("Location: ./index.php?view=live_chatf");
	}
	else
		header("Location: ./index.php?view=live_chat");
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>