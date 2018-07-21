<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./menu_as_des_as.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	$country=$_SESSION['country'];
	$mes='tableau';
	include_once('./jfv_access.php');
	$con=dbconnecti();
	$query2 = mysqli_query($con,"SELECT c.Date,c.Joueur_win,c.Pilote_loss,c.Unite_win,c.Unite_loss,c.Avion_win,c.Avion_loss,c.Lieu,c.Cycle,c.Altitude,c.PVP,c.Latitude,c.Longitude,u.Nom,u.Pays 
	FROM Chasse_sandbox as c,Unit as u WHERE c.PVP < 3 AND c.Unite_win = u.ID ORDER BY c.ID DESC LIMIT 50");
	mysqli_close($con);
	if($query2)
	{
		while($data2 = mysqli_fetch_assoc($query2))
		{
			$Date = substr($data2['Date'],0,16);
			$Unite_win = $data2['Nom'];
			$Pays_win = $data2['Pays'];
			$Unite_loss = $data2['Unite_loss'];
			$Avion_win = $data2['Avion_win'];
			
			$con = dbconnecti();
			$query3 = mysqli_query($con,"SELECT Pays,Nom FROM Unit WHERE ID = '$Unite_loss' ORDER BY ID DESC LIMIT 50");
			$Avion_win = mysqli_result(mysqli_query($con,"SELECT Nom FROM Avion WHERE ID = '$Avion_win' ORDER BY ID DESC LIMIT 50"),0);
			mysqli_close($con);
			while($data3 = mysqli_fetch_assoc($query3))
			{
				$Unite_loss = $data3['Nom'];
				$Pays_loss = $data3['Pays'];
			}
			mysqli_free_result($query3);	
			$Lieu = GetData("Lieu","ID",$data2['Lieu'],"Nom");		
			if($data2['Longitude'] > 67)
				$Front = 3;
			elseif($data2['Latitude'] < 43)
				$Front = 2;
			elseif($data2['Longitude'] > 14)
				$Front = 1;
			else
				$Front = 0;
			if($data2['PVP'] == 1)
			{
				$Pilote_loss = "<b>".GetData("Pilote","ID",$data2['Pilote_loss'],"Nom")."</b>";		
				$Pilote_win = GetData("Pilote_IA","ID",$data2['Joueur_win'],"Nom");
				$Avion_win = GetAvionIcon($data2['Avion_win'], $Pays_win, 0, $data2['Unite_win'],$Front);
				$Avion_loss = GetAvionIcon($data2['Avion_loss'], $Pays_loss, $data2['Pilote_loss'], $data2['Unite_loss'],$Front);
			}
			else
			{
				$Pilote_win = "<b>".GetData("Pilote","ID",$data2['Joueur_win'],"Nom")."</b>";		
				$Pilote_loss = GetData("Pilote_IA","ID",$data2['Pilote_loss'],"Nom");
				$Avion_win = GetAvionIcon($data2['Avion_win'], $Pays_win, $data2['Joueur_win'], $data2['Unite_win'],$Front);
				$Avion_loss = GetAvionIcon($data2['Avion_loss'], $Pays_loss, 0, $data2['Unite_loss'],$Front);
			}	
				
			$Avion_unit_win_img = "images/unit/unit".$data2['Unite_win']."p.gif";
			if(is_file($Avion_unit_win_img))
				$Unite_win_txt = "<img src='".$Avion_unit_win_img."' title='".$Unite_win."'>";
			else
				$Unite_win_txt = $Unite_win;
			$Avion_unit_loss_img = "images/unit/unit".$data2['Unite_loss']."p.gif";
			if(is_file($Avion_unit_loss_img))
				$Unite_loss_txt = "<img src='".$Avion_unit_loss_img."' title='".$Unite_loss."'>";
			else
				$Unite_loss_txt = $Unite_loss;
			if($data2['Cycle'])
				$Cycle_txt = "Nuit";
			else
				$Cycle_txt = "Jour";
			$liste .= "<tr><td>".$Date."</td><td><img src='images/meteo".$data2['Cycle'].".gif' title='".$Cycle_txt."'></td><td>".$Lieu."</td>
				<td><img src='".$Pays_win."20.gif'></td><td>".$Unite_win_txt."</td><td>".$Pilote_win."</td><td>".$Avion_win."</td>
				<td>".$Avion_loss."</td><td>".$Pilote_loss."</td><td>".$Unite_loss_txt."</td><td><img src='".$Pays_loss."20.gif'></td>
				<td>".$data2['Altitude']."m</td></tr>";
		}
	}
	echo "<h2>Tableau de Chasse</h2><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
	<thead><tr><th>Date</th><th>Cycle</th><th>Lieu</th><th>Pays</th><th>Unité</th><th>Pilote crédité</th><th>Avion</th>
	<th>Avion Abattu</th><th>Pilote Abattu</th><th>Unité</th><th>Pays</th><th>Altitude</th></thead>";
	echo $liste."</table></div>";
	include_once('./index.php');
}