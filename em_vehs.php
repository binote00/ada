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
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Adjoint_Mer or $GHQ or $Admin)
	{
		$Mode=Insec($_POST['mode']);
		$con=dbconnecti();
		if($Mode ==2)
		{
			//$Service1=mysqli_query($con,"SELECT SUM(r.Vehicule_Nbr) as Nbr,c.ID FROM Regiment as r,Cible as c WHERE r.Pays='$country' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Vehicule_ID <5000 GROUP BY c.ID ORDER BY c.Type ASC,Nbr DESC");
			$Service2=mysqli_query($con,"SELECT SUM(r.Vehicule_Nbr) as Nbr,c.ID FROM Regiment_IA as r,Cible as c WHERE r.Pays='$country' AND r.Front='$Front' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Vehicule_ID <5000 GROUP BY c.ID ORDER BY c.Type ASC,Nbr DESC");
		}
		else
		{
			//$Service1=mysqli_query($con,"SELECT SUM(r.Vehicule_Nbr) as Nbr,c.ID FROM Regiment as r,Cible as c WHERE r.Pays='$country' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Vehicule_ID <5000 GROUP BY c.ID ORDER BY c.Type ASC,Nbr DESC");
			$Service2=mysqli_query($con,"SELECT SUM(r.Vehicule_Nbr) as Nbr,c.ID FROM Regiment_IA as r,Cible as c WHERE r.Pays='$country' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Vehicule_ID <5000 GROUP BY c.ID ORDER BY c.Type ASC,Nbr DESC");
			//$ServiceNaval1=mysqli_query($con,"SELECT SUM(r.Vehicule_Nbr) as Nbr,c.ID FROM Regiment as r,Cible as c WHERE r.Pays='$country' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Vehicule_ID >=5000 GROUP BY c.ID ORDER BY c.Type ASC,Nbr DESC");
			$ServiceNaval2=mysqli_query($con,"SELECT SUM(r.Vehicule_Nbr) as Nbr,c.ID FROM Regiment_IA as r,Cible as c WHERE r.Pays='$country' AND r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0 AND r.Vehicule_ID >=5000 GROUP BY c.ID ORDER BY c.Type ASC,Nbr DESC");
		}
		mysqli_close($con);
		if($Service1)
		{
			while($data=mysqli_fetch_array($Service1))
			{
				$Vehicules[$data['ID']]+=$data['Nbr'];
			}
			mysqli_free_result($Service1);
		}
		if($Service2)
		{
			while($data=mysqli_fetch_array($Service2))
			{
				$Vehicules[$data['ID']]+=$data['Nbr'];
			}
			mysqli_free_result($Service2);
		}
		if($ServiceNaval1)
		{
			while($data=mysqli_fetch_array($ServiceNaval1))
			{
				$Navires[$data['ID']]+=$data['Nbr'];
			}
			mysqli_free_result($ServiceNaval1);
		}
		if($ServiceNaval2)
		{
			while($data=mysqli_fetch_array($ServiceNaval2))
			{
				$Navires[$data['ID']]+=$data['Nbr'];
			}
			mysqli_free_result($ServiceNaval2);
		}
		if($OfficierEMID !=$Adjoint_Mer)
		{
			if(is_array($Vehicules))
			{
				$Total_Veh=array_sum($Vehicules);
				/*foreach($Vehicules as $Veh => $Veh_Nbr)
				{
					$Veh_tot.="<tr><td>".$Veh_Nbr."</td><td>".GetVehiculeIcon($Veh,$country)."<td></tr>";
				}*/
				arsort($Vehicules);
				foreach($Vehicules as $Veh => $Veh_Nbr)
				{
					$Veh_tot2.="<tr><td>".$Veh_Nbr."</td><td>".GetVehiculeIcon($Veh,$country)."<td></tr>";
				}
				unset($Vehicules);
			}
			$Total_txt="<h3>Total terrestre ".$Total_Veh."</h3>";
			$Land_txt="<table class='table'><thead><tr><th>En service</th><th>Modèle</th></tr></thead>".$Veh_tot2."</table>";
		}
		if($OfficierEMID !=$Adjoint_Terre)
		{
			if(is_array($Navires))
			{
				$Total_Nav=array_sum($Navires);
				foreach($Navires as $Nav => $Nav_Nbr)
				{
					$Nav_tot.="<tr><td>".$Nav_Nbr."</td><td>".GetVehiculeIcon($Nav,$country)."<td></tr>";
				}
				arsort($Navires);
				foreach($Navires as $Nav => $Nav_Nbr)
				{
					$Nav_tot2.="<tr><td>".$Nav_Nbr."</td><td>".GetVehiculeIcon($Nav,$country)."<td></tr>";
				}
				unset($Navires);
			}
			$Total_txt.="<h3>Total naval ".$Total_Nav."</h3>";
			$Nav_txt="<table class='table'><thead><tr><th>En service</th><th>Modèle</th></tr></thead>".$Nav_tot2."</table>";
		}
		echo "<h2>Véhicules en service</h2>
		<div class='row'>
		<div class='col-md-2'><form action='index.php?view=em_vehs' method='post'><input type='hidden' name='mode' value='1'><input type='Submit' value='Total nation' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
		<div class='col-md-2'><form action='index.php?view=em_vehs' method='post'><input type='hidden' name='mode' value='2'><input type='Submit' value='Total front' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
		</div>
		".$Total_txt."<div class='row'><div class='col-md-6'>".$Land_txt."</div><div class='col-md-6'>".$Nav_txt."</div></div>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>