<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$con=dbconnecti();
	$Dg_Max_result=mysqli_query($con,"SELECT Nom,Avancement,Pays,Degats_Max FROM Pilote WHERE Degats_Max >0 ORDER BY Degats_Max DESC");
	mysqli_close($con);
	if($Dg_Max_result)
	{
		while($data=mysqli_fetch_array($Dg_Max_result,MYSQLI_ASSOC))
		{
			$Grade=GetAvancement($data['Avancement'],$data['Pays']);
			$Dg_max_txt.="<tr><td>".$data['Nom']."</td><td><img title='".$Grade[0]."' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td><td><img src='".$data['Pays']."20.gif'></td><td>".$data['Degats_Max']."</td></tr>";
		}
		mysqli_free_result($Avions_perdus);
	}
	/*if(is_array($Vehs))
	{
		arsort($Vehs);
		foreach($Vehs as $Veh => $Avion_Nbr)
		{
			$Veh_pertes.="<tr><td>".$Avion_Nbr."</td><td>".GetVehiculeIcon($Veh)."<td></tr>";
		}
		unset($Vehs);
	}*/
	echo "<h1>Dégâts max des pilotes</h1><div class='row'><table class='table table-striped'><thead><tr><th>Pilote</th><th>Grade</th><th>Nation</th><th>Dégâts Max</th></tr></thead>".$Dg_max_txt."</table></div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>