<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$Sections=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sections WHERE OfficierID='$OfficierID'"),0);
	$results=mysqli_query($con,"SELECT Avancement,Pays FROM Officier WHERE ID='$OfficierID'");
	mysqli_close($con);
	if($results)
	{
		while($datas=mysqli_fetch_array($results)) 
		{
			$Pays=$datas['Pays'];
			$Avancement=$datas['Avancement'];
		}
		mysqli_free_result($results);
	}
	$Grade_Level=GetAvancement($Avancement,$Pays);
	$Am_Nbr=$Grade_Level[1]-8;
	if($Sections <$Am_Nbr)
	{
		$Section=Insec($_POST['Sec']);
		if($Section >0 and $Section <8)
		{
			$con=dbconnecti();
			$query="INSERT INTO Sections (OfficierID,SectionID)";
			$query.="VALUES ('$OfficierID','$Section')";
			$ok=mysqli_query($con,$query);
			$err_txt=mysqli_error($con);
			mysqli_close($con);
			if($ok)
			{
				echo "<h1>".$OfficierID."e Bataillon</h1>La section a été activée avec succès!";
				echo "<br><a class='btn btn-default' title='Retour' href='index.php?view=ground_bat'>Retour</a>";
			}
			else
				echo "<div class='alert alert-danger'>Erreur de création de section!</div>".$err_txt;
		}
	}
	else
		echo "<div class='alert alert-danger'>Vous ne pouvez pas créer plus de sections pour ce poste de commandement!</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>