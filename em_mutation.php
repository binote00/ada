<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');
	if($OfficierEMID ==$Commandant or $Admin)
	{
		include_once('./em_effectifs.php');
		$go=false;
		$query="SELECT p.ID as Pilote,p.Nom as Pilote_Nom,p.Mutation,u.Nom as Unit_Nom,p.Avancement,p.Pays,p.Front,u.Commandant,u.Officier_Adjoint,u.Officier_Technique,l.ID as Base,l.Nom as Base_Nom,l.Latitude,l.Longitude
		FROM Pilote as p,Unit as u,Lieu as l WHERE p.Pays='$country' AND p.Mutation=u.ID AND u.Base=l.ID AND p.Mutation >0
		ORDER BY p.Front ASC,p.Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			echo "<h2>Pilotes en attente de mutation</h2><table class='table table-striped'>
			<thead><tr><th>Nom</th><th>Grade</th><th>Front</th><th>Mutation demandée</th><th colspan='2'></th></tr></thead>";
			while($Data=mysqli_fetch_array($result))
			{
				if($Data['Mutation'] >0)
				{
					$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);
					if($Data['Pays'] ==9)
						$Front_dest=3;
					else
						$Front_dest=GetFrontByCoord(0,$Data['Latitude'],$Data['Longitude']);
					$Front_dest_txt=GetFront($Front_dest);		
					echo $titre."<tr><td><a href='user_public.php?Pilote=".$Data['Pilote']."' style='color:#4171ac;' target='_blank'>".$Data['Pilote_Nom']."</a></td>
						<td title='".$Avancement[0]."'><img src='images/grades/grades".$Data['Pays'].$Avancement[1].".png'></td><td>".GetFront($Data['Front'])."</td>
						<td>".Afficher_Icone($Data['Mutation'],$Data['Pays'],$Data['Unit_Nom'])." - Front ".$Front_dest_txt."</td>";
					if($Admin ==1 or $Front == $Front_dest)
					{
						if($Data['Avancement'] >2999 and !$Data['Commandant'])
							$go=true;
						elseif($Data['Avancement'] >1499 and !$Data['Officier_Adjoint'])
							$go=true;
						elseif(!$Data['Officier_Technique'])
							$go=true;
						if($go)
						{
							echo "<td><form action='index.php?view=em_mutation1' method='post'>
							<input type='hidden' name='pil' value='".$Data['Pilote']."'>
							<input type='hidden' name='mut' value='".$Data['Mutation']."'>
							<input type='submit' class='btn btn-default' value='Accepter' onclick='this.disabled=true;this.form.submit();'>
							</form></td>";
						}
						echo "<td><form action='index.php?view=em_mutation2' method='post'>
						<input type='hidden' name='pil' value='".$Data['Pilote']."'>
						<input type='hidden' name='mut' value='".$Data['Mutation']."'>
						<input type='submit' class='btn btn-default' value='Refuser' onclick='this.disabled=true;this.form.submit();'>
						</form></td>
						<tr>";
					}
					else
						echo "<td rowspan='2' class='text-danger'>Seuls les membres de l'état-major du front de destination peuvent valider une demande</td>";
				}
			}
			echo "</table><div class='alert alert-info'>Tout membre de l'Etat-Major dont la demande de mutation sur un autre front est validée perdra son poste à l'Etat-Major.</div>";
		}
		else
			echo "Aucun pilote n'est actuellement en demande de mutation.";
	}
	else
		PrintNoAccess($country,1);
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';