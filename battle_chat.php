<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if($Officier_pvp >0 or $Pilote_pvp >0)
{
	include_once('./jfv_include.inc.php');
	$Battle=Insec($_POST['Battle']);
	$Faction=Insec($_POST['Camp']);
	$Msg=htmlspecialchars(Insec($_POST['Mes']));
	if(strlen($Msg) >2)
	{
		if($Officier_pvp)
			$Mode=2;
		elseif($Pilote_pvp)
		{
			$Mode=1;
			$Officier_pvp=$Pilote_pvp;
		}
		$date=date('Y-m-d G:i');
		$query="INSERT INTO Bchat (BDate, Battle, PlayerID, Mode, Faction, Msg)
		VALUES ('$date','$Battle','$Officier_pvp','$Mode','$Faction','$Msg')";
		$con=dbconnecti(5);
		$reset=mysqli_query($con,$query);
		mysqli_close($con);
		header("Location: ./index.php?view=ground_menu_pvp");
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>