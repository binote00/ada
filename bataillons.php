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
		$Tri=Insec($_POST['Tri']);
		if($Tri ==1)
			$Tri='Combats';
		else
			$Tri='Experience';
		$country=$_SESSION['country'];
		$i=0;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT r.ID,r.Front,r.Pays,r.Experience,r.Vehicule_ID,r.Combats,r.Division FROM Regiment_IA as r
		WHERE r.Experience >50 AND r.Vehicule_ID <5000 AND r.Vehicule_ID !=424 ORDER BY r.".$Tri." DESC LIMIT 50");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$i++;
				$Pays_txt="<img src='".$data['Pays']."20.gif'>";
				if($data['Division'] >0)
					$Div_txt=" <img src='images/div/div".$data['Division'].".png'>";
				else
					$Div_txt=$Pays_txt;
				$bat_list.="<tr><td>".$i."</td><td><span class='label label-primary'>".$data['ID']."e</span> ".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$data['Front'])."</td>
				<td>".$Pays_txt."</td><th>".$data['Experience']."</th><th>".$data['Combats']."</th><th>".$Div_txt."</th></tr>";
			}
			mysqli_free_result($result);
		}
		if($bat_list)
			echo "<h1>Bataillons</h1>
			<div style='overflow:auto; width: 100%;'>
			<table class='table table-striped table-condensed'>
				<thead><tr>
					<th>N°</th>
					<th>Unité</th>
					<th>Pays</th>
					<th><form action='index.php?view=bataillons' method='post'><input type='hidden' name='Tri' value='0'><input type='submit' value='Expérience'></form></th>
					<th><form action='index.php?view=bataillons' method='post'><input type='hidden' name='Tri' value='1'><input type='submit' value='Combats'></form></th>
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