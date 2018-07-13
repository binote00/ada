<?
/*require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Rens or $Admin)
	{			
		if($Admin)
		{
			$query="SELECT DISTINCT o.Nom,o.Avancement,o.Pays,o.Train_Lieu,o.Barges_Lieu,o.Para_Lieu,o.Transit,r.Lieu_ID as Base FROM Officier as o,Regiment as r 
			WHERE r.Officier_ID=o.ID AND (o.Train_Lieu >0 OR o.Barges_Lieu >0 OR o.Para_Lieu >0 OR o.Transit >0) ORDER BY o.Front ASC,o.Nom ASC";		
		}
		else
		{
			$query="SELECT DISTINCT o.Nom,o.Avancement,o.Pays,o.Train_Lieu,o.Barges_Lieu,o.Para_Lieu,o.Transit,r.Lieu_ID as Base FROM Officier as o,Regiment as r 
			WHERE r.Officier_ID=o.ID AND o.Pays='$country' AND o.Front='$Front' AND (o.Train_Lieu >0 OR o.Barges_Lieu >0 OR o.Para_Lieu >0 OR o.Transit >0) ORDER BY o.Front ASC,o.Nom ASC";		
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($Data=mysqli_fetch_array($result))
			{
				$Train_Lieu="";
				$Barges_Lieu="";
				$Para_Lieu="";
				$Fret="";
				$Avancement=GetAvancement($Data['Avancement'],$Data['Pays'],0,1);
				if($Data['Train_Lieu'] >0)
					$Train_Lieu=GetData("Lieu","ID",$Data['Train_Lieu'],"Nom");
				if($Data['Barges_Lieu'] >0)
					$Barges_Lieu=GetData("Lieu","ID",$Data['Barges_Lieu'],"Nom");
				if($Data['Para_Lieu'] >0)
					$Para_Lieu=GetData("Lieu","ID",$Data['Para_Lieu'],"Nom");
				if($Data['Transit'] >0)
					$Fret=GetData("Officier","ID",$Data['Transit'],"Nom");
				$Base=GetData("Lieu","ID",$Data['Base'],"Nom");
				$offs.="<tr><td>".$Data['Nom']."</td>
					<td><img src='images/grades/ranks".$Data['Pays'].$Avancement[1].".png' title='".$Avancement[0]."'></td><td>".$Base."</td>
					<td>".$Train_Lieu."</td><td>".$Barges_Lieu."</td><td>".$Para_Lieu."</td><td>".$Fret."</td></tr>";
			}
		}
		else
			echo "Aucune demande de transit actuellement.";
		echo "<h2>Transit</h2><table class='table table-striped'>
		<thead><tr><th>Nom</th><th>Grade</th><th>Lieu</th><th>Demande Train</th><th>Demande Naval</th><th>Demande Parachutage</th><th>Transporte</th></tr></thead>".$offs."</table>";
	}
	else
		PrintNoAccess($country,1,4);
}
else
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";*/
?>