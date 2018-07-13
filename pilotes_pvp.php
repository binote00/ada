<?
require_once('./jfv_inc_sessions.php');
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if($Pilote_pvp >0)
{
	include_once('./jfv_include.inc.php');
	$Tri=Insec($_POST['Tri']);
	if(!$Tri)$Tri=1;
	?>
	<h1>Pilotes</h1>
	<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
		<thead><tr><th>N°</th><th>Nom</th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="1"><input type='Submit' value='Score'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="2"><input type='Submit' value='Missions'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="3"><input type='Submit' value='Atterrissages'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="4"><input type='Submit' value='Reco'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="5"><input type='Submit' value='Raids diurnes'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="6"><input type='Submit' value='Raids nocturnes'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="7"><input type='Submit' value='Véhicules'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="8"><input type='Submit' value='Victoires'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="9"><input type='Submit' value='Probables'></form></th>
			<th><form action='index.php?view=pilotes_pvp' method='post'><input type='hidden' name='Tri' value="10"><input type='Submit' value='Abattu'></form></th>
		</tr></thead>
	<?
	switch($Tri)
	{
		case 1:
			$Tri="Points";
		break;
		case 2:
			$Tri="Missions";
		break;
		case 3:
			$Tri="Landings";
		break;
		case 4:
			$Tri="Recce";
		break;
		case 5:
			$Tri="Raids_Bomb";
		break;
		case 6:
			$Tri="Raids_Bomb_Nuit";
		break;
		case 7:
			$Tri="Dive";
		break;
		case 8:
			$Tri="Victoires";
		break;
		case 9:
			$Tri="Victoires_prob";
		break;
		case 10:
			$Tri="Abattu";
		break;
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT ID,Nom,Points,Missions,Landings,Raids_Bomb,Raids_Bomb_Nuit,Dive,Victoires,Victoires_prob,Recce,Abattu FROM Pilote_PVP ORDER BY ".$Tri." DESC LIMIT 100");
	mysqli_close($con);
	if($result)
	{
		$i=1;
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo "<tr><td>".$i."</td><td align='left'>".$data['Nom']."</td>
				<td>".$data['Points']."</td><td>".$data['Missions']."</td><td>".$data['Landings']."</td><td>".$data['Recce']."</td><td>".$data['Raids_Bomb']."</td><td>".$data['Raids_Bomb_Nuit']."</td>
				<td>".$data['Dive']."</td><td>".$data['Victoires']."</td><td>".$data['Victoires_prob']."</td><td>".$data['Abattu']."</td></tr>";
			$i++;
		}
	}
	echo '</table></div>';
}