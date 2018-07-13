<?php
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Premium,Anim,Admin FROM Joueur WHERE ID='$AccountID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Premium=$data['Premium'];
			$Anim=$data['Anim'];
			$Admin=$data['Admin'];
		}
		mysqli_free_result($result);
		unset($data);
	}
}