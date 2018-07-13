<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin)
	{	
		$con=dbconnecti();
		$Service1=mysqli_query($con,"SELECT SUM(u.Avion1_Nbr) as Nbr,a.ID FROM Unit as u,Avion as a WHERE u.Pays='$country' AND u.Avion1=a.ID AND u.Etat=1 AND u.Type!=8 GROUP BY a.ID ORDER BY u.Type ASC,Nbr DESC");
		$Service2=mysqli_query($con,"SELECT SUM(u.Avion2_Nbr) as Nbr,a.ID FROM Unit as u,Avion as a WHERE u.Pays='$country' AND u.Avion2=a.ID AND u.Etat=1 AND u.Type!=8 GROUP BY a.ID ORDER BY u.Type ASC,Nbr DESC");
		$Service3=mysqli_query($con,"SELECT SUM(u.Avion3_Nbr) as Nbr,a.ID FROM Unit as u,Avion as a WHERE u.Pays='$country' AND u.Avion3=a.ID AND u.Etat=1 AND u.Type!=8 GROUP BY a.ID ORDER BY u.Type ASC,Nbr DESC");
		mysqli_close($con);
		if($Service1)
		{
			while($data=mysqli_fetch_array($Service1))
			{
				$Plane[$data['ID']]+=$data['Nbr'];
			}
			mysqli_free_result($Service1);
		}
		if($Service2)
		{
			while($data=mysqli_fetch_array($Service2))
			{
				$Plane[$data['ID']]+=$data['Nbr'];
			}
			mysqli_free_result($Service2);
		}
		if($Service3)
		{
			while($data=mysqli_fetch_array($Service3))
			{
				$Plane[$data['ID']]+=$data['Nbr'];
			}
			mysqli_free_result($Service3);
		}
		if(is_array($Plane))
		{
			$Total_avions=array_sum($Plane);
			foreach($Plane as $Avion => $Avion_Nbr)
			{
				$Avions_tot.="<tr><td>".$Avion_Nbr."</td><td>".GetAvionIcon($Avion,$country)."<td></tr>";
			}
			arsort($Plane);
			//print_r(array_values($Avions));
			foreach($Plane as $Avion => $Avion_Nbr)
			{
				$Avions_tot2.="<tr><td>".$Avion_Nbr."</td><td>".GetAvionIcon($Avion,$country)."<td></tr>";
			}
			unset($Plane);
		}
		include_once('./em_effectifs.php');
		echo "<h2>Avions en service</h2><h3>Total <div class='label label-default'>".$Total_avions."</div></h3>
		<div class='row'><div class='col-md-6'><table class='table table-800'><thead><tr><th>En service</th><th>Modèle</th></tr></thead>".$Avions_tot."</table></div>
		<div class='col-md-6'><table class='table table-800'><thead><tr><th>En service</th><th>Modèle</th></tr></thead>".$Avions_tot2."</table></div></div>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>