<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
	if($Admin)
	{
		$query="SELECT o.ID as Officier,o.Nom,o.Postuler,o.Avancement,o.Pays,o.Front,o.Armee
		FROM Officier_em as o
		WHERE o.Postuler >0 ORDER BY o.Pays ASC,o.Front ASC,o.Postuler ASC,o.Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			echo "<h2>Officiers EM en attente de mutation</h2><table class='table table-striped'>
			<thead><tr><th>Nom</th><th>Grade</th><th>Front</th><th>Mutation demandée</th><th colspan='2'></th></tr></thead>";
			while($Data=mysqli_fetch_array($result))
			{
				$go=true;
				$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);
				echo "<tr><td>".$Data['Nom']."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$Data['Pays'].$Avancement[1].".png'></td><td>".GetFront($Data['Front'])."</td><td>".$Mutation."</td>";
				if($Data['Armee'])
					$go=false;
				if($go)
					echo '<td><span class="text-success">Valide</span></td>';
				else
					echo '<td><span class="text-danger">Invalide</span></td>';
				echo '<tr>';
			}
			echo '</table>';
		}
		else
			echo "Aucun officier n'est actuellement en demande de mutation.";
	}
	else
		PrintNoAccess($country,1);
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';