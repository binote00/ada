<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
//Check Joueur Valide
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID > 0)
{
	$country = $_SESSION['country'];
	$con = dbconnecti();	
	$result = mysqli_query($con, "SELECT Front,Renseignement,Avancement FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$Front = $data['Front'];
			$Renseignement = $data['Renseignement'];
			$Avancement = $data['Avancement'];
		}
		mysqli_free_result($result);
	}
	$con = dbconnecti();	
	$result2 = mysqli_query($con, "SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
	mysqli_close($con);
	if($result2)
	{
		while($data = mysqli_fetch_array($result2, MYSQLI_ASSOC))
		{
			$Commandant = $data['Commandant'];
			$Officier_Adjoint = $data['Adjoint_EM'];
			$Officier_EM = $data['Officier_EM'];
			$Officier_Rens = $data['Officier_Rens'];
		}
		mysqli_free_result($result2);
	}
	include_once('./menu_actus.php');
	echo "<a class='bouton_100' title='Pertes aérienne' href='index.php?view=news_pertes'>Aérien</a>";	
	if($PlayerID == 2 or $PlayerID == $Commandant or $PlayerID == $Officier_Adjoint or $PlayerID == $Officier_EM or $PlayerID == $Officier_Rens or $Avancement > 9999 or $Renseignement > 150)
	{			
		$Date_Campagne = GetData("Conf_Update","ID",2,"Date");		
		echo "<div>
		<table class='table'>
		<th colspan='5' class='TitreBleu_bc'>Pertes des véhicules terrestres</th>
		<tr bgcolor='#CDBDA7'>
			<th>N°</th>
			<th>Véhicule</th>
			<th>Pays</th>
			<th><a class='bouton_100' title='Mise en service' href='index.php?view=news_pertes2'>Mise en service</a></th>
			<th><a class='bouton' title='Pertes' href='index.php?view=news_pertes3'>Total</a></th></tr>";
		$i=1;
		$con = dbconnecti(4);
		$result = mysqli_query($con, "SELECT Avion,SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (400,401,404,405,605) GROUP BY Avion ORDER BY SUM(Avion_Nbr) DESC");
		mysqli_close($con);		
		if($result)
		{
			while($data = mysqli_fetch_array($result, MYSQLI_NUM))
			{
				$Perdus = $data[1];
				$ID = $data[0];
				//Transfer	
				$con = dbconnecti();
				$result2 = mysqli_query($con, "SELECT ID,Pays,Date,Nom FROM Cible WHERE ID='$ID'");
				mysqli_close($con);
				if($result2)
				{
					while($data2 = mysqli_fetch_array($result2, MYSQLI_ASSOC))
					{
						$ID = $data2['ID'];
						$Pays = $data2['Pays'];
						$Avion_img = GetVehiculeIcon($ID,$Pays);
						echo "<tr><td>".$i."</td><td>".$Avion_img."</td><td><img src='".$Pays."20.gif'></td><td>".$data2['Date']."</td><td bgcolor='MistyRose'>".$Perdus."</td></tr>";
						$i++;
					}
					mysqli_free_result($result2);
				}
			}
			mysqli_free_result($result);
			unset($data);
		}
		else
			echo "<b>Désolé, aucun véhicule</b>";
		echo "</table><hr></div>";
	}
	else
	{
		echo "<div align='center' bgcolor='#ECDDC1'><table class='table'>
			<tr><td><img src='images/top_secret.gif'></td></tr>
			<tr><td>Ces données sont classifiées.</td></tr>
			<tr><td>Votre rang ne vous permet pas d'accéder à ces informations.</td></tr>
			</table></div>";
	}
}
else
	echo "Vous devez être connecté pour accéder à cette page!";
?>