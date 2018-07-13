<?
require_once('./jfv_inc_sessions.php');
$Officier_em=$_SESSION['Officier_em'];	
if($Officier_em >0 or $Admin)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$country=$_SESSION['country'];
		$i=0;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT r.ID,r.Front,r.Pays,r.Vehicule_ID,r.Combats,r.Division,DATE_FORMAT(g.`Date`,'%d-%m-%Y à %Hh%i') as Coule FROM Regiment_IA as r
		LEFT JOIN Ground_Cbt as g ON r.ID=g.Reg_b
		WHERE r.Combats >0 AND r.Vehicule_ID >5000 ORDER BY r.Combats DESC LIMIT 50");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$i++;
				$Pays_txt="<img src='".$data['Pays']."20.gif'>";
				if($data['Coule'])
					$Coule_txt="Coulé le ".$data['Coule'];
				else
					$Coule_txt="Inconnu";
				if($data['Division'] >0)
					$Div_txt=" <img src='images/div/div".$data['Division'].".png'>";
				else
					$Div_txt=$Pays_txt;
				$bat_list.="<tr><td>".$i."</td><td><span class='label label-primary'>".$data['ID']."e</span> ".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$data['Front'])."</td>
				<td>".$Pays_txt."</td><th>".$data['Combats']."</th><td>".$Coule_txt."</td><th>".$Div_txt."</th></tr>";
			}
			mysqli_free_result($result);
		}
		if($bat_list)
			echo "<h1>Flottilles</h1>
			<div style='overflow:auto; width: 100%;'>
			<table class='table table-striped table-condensed'>
				<thead><tr>
					<th>N°</th>
					<th>Unité</th>
					<th>Pays</th>
					<th>Combats</th>
					<th>Destin</th>
					<th>Division</th>
				</tr></thead>".$bat_list."</table></div>";
	}
	else
	{
		echo "<table class='table'>
			<tr><td><img src='images/acces_premium.png'></td></tr>
			<tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr>
		</table>";
	}
}
?>