<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_em.php');
if($country)
{
	echo "<h2>Missions historiques</h2><div style='overflow:auto; height: 640px;'>
	<table class='table'>";
	//Events_Historiques
	$query="SELECT DISTINCT ID,Nom,`Date`,Pays,Lieu,Unite,Type_Mission FROM Event_Historique WHERE Type <3 AND Pays='$country' ORDER BY `Date` DESC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($Data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			if(strrpos($Data['Nom'],"("))
			{
				$Event_ID=$Data['ID'];
				$Nom=$Data['Nom'];
				$Pays=GetPays($Data['Pays']);
				$Lieu=GetData("Lieu","ID",$Data['Lieu'],"Nom");
				$Date=$Data['Date'];
				$Mission=GetMissionType($Data['Type_Mission']);
				$Unite=GetAvionType($Data['Unite']);
			}
			echo "<tr>
			<td>".$Date."</td><td>".$Nom."</td><th>".$Lieu."</th><td>".$Pays."</td><td>".$Unite."</td><td>".$Mission."</td>
			</tr>";
		}
	}
	echo "</table></div>";
}
?>