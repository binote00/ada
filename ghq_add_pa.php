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
		$Escadrille = Insec($_POST['Unite']);
		$PA = Insec($_POST['PAC']);
		echo '<h1>Embarquement sur un porte-avions</h1>';
		if($Escadrille and ($Credits >=8 or $Admin))
		{
            $armeepa=0;
            $con=dbconnecti();
			if($PA)
                $armeepa=mysqli_result(mysqli_query($con,"SELECT d.Armee FROM Regiment_IA as r,Division as d WHERE r.Division=d.ID AND r.Vehicule_ID='$PA'"),0);
			$reset=mysqli_query($con,"UPDATE Unit SET Porte_avions='$PA',Armee='$armeepa' WHERE ID='$Escadrille'"); //,Mission_IA=1,Date_Mission=NOW()
			mysqli_close($con);
			if(!$Admin)UpdateData("Officier_em","Credits",-8,"ID",$OfficierEMID);
			if($reset){
                if($PA)
                    echo '<div class="alert alert-success">L\'unité est embarquée sur le porte-avions</div>';
                else
                    echo '<div class="alert alert-success">L\'unité est débarquée du porte-avions</div>';
            }
            else
                echo '<div class="alert alert-danger">[ERREUR] : Une erreur est survenue, veuillez le signaler sur le forum!</div>';
		}
		else
			echo '<div class="alert alert-danger">Vous n\'avez pas le temps pour cela!</div>';
		echo "<br><a href='index.php?view=em_unites_12' class='btn btn-default' title='Retour'>Retour</a>";
	}
	else
		PrintNoAccess($country,1);
}