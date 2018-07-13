<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_avions.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	//$Secteur=Insec($_POST['Mode']);
	$country=$_SESSION['country'];
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT MIA,Credits,Unit FROM Pilote WHERE ID='$PlayerID'");
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$MIA=$data['MIA'];
			$Credits=$data['Credits'];
			$Unite=$data['Unit'];
		}
		mysqli_free_result($result);
		unset($result);
	}
	$Commandant=mysqli_result(mysqli_query($con,"SELECT Commandant FROM Unit WHERE ID='$Unite'"),0);
	mysqli_close($con);
	if(!$MIA and $_SESSION['Distance'] ==0)
	{
		if($PlayerID ==$Commandant or $PlayerID ==1)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Type,Base,Reputation,Station_Meteo,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Ratio,
			Avion1_BombeT,Avion2_BombeT,Avion3_BombeT,
			Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10,
			Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,
			Bombes_50,Bombes_125,Bombes_250,Bombes_300,Bombes_400,Bombes_500,Bombes_800,Bombes_1000,Bombes_2000
			FROM Unit WHERE ID='$Unite'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Unite_Nom=$data['Nom'];
				}
				mysqli_free_result($result);
			}
			echo "<h1>Missions de l'escadrille</h1>
				<table class='table'><thead><tr><th>Flight 1</th><th>Flight 2</th><th>Flight 3</th></tr></thead>
				<tr><td></td><td></td><td></td></tr>
				</table>";
			echo "<form action='index.php?view=esc_gestion_missions1' method='post'>			
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>