<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_inc_em.php');
	if($GHQ or $Admin)
	{
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_ground.inc.php');		
		echo "<h1>Remise en état d'unités démoralisées</h1>";
		if($Credits >=4 or $Admin)
		{
			$con=dbconnecti();	
			$reset=mysqli_query($con,"UPDATE Regiment_IA as r,Cible as c SET r.Moral=50 WHERE r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Moral=0 AND (r.Vehicule_ID<5000 OR c.Type<19)");
			mysqli_close($con);
			if(!$Admin)
				UpdateData("Officier_em","Credits",-4,"ID",$OfficierEMID);
			echo "Un bon petit discours remonte toujours le moral des troupes!";
		}
		else
			echo "Vous n'avez pas le temps pour cela!";
		echo "<br><a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour</a>";
	}
	else
		PrintNoAccess($country,1);
}