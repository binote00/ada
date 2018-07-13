<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
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
	if($PlayerID == 1 or $PlayerID == $Commandant or $PlayerID == $Officier_Adjoint or $PlayerID == $Officier_EM or $PlayerID == $Officier_Rens or $Avancement > 4999 or $Renseignement > 100)
	{			
		$Date_Campagne = GetData("Conf_Update","ID",2,"Date");
	echo "<div>
	<a class='bouton_100' title='terrestre' href='index.php?view=news_pertes2'>Terrestre</a>
	<table class='table'>
	<th colspan='20' class='TitreBleu_bc'>Pertes par modèle d'avion</th>
	<tr bgcolor='#CDBDA7'>
		<th>N°</th>
		<th>Avion</th>
		<th>Pays</th>
		<th>Type</th>
		<th>Mise en service</th>
		<th></th>
		<th title='Abattus en vol'>Abattus</th>
		<th title='Abattus par la DCA'>DCA</th>
		<th>Crashs</th>
		<th title='Autres pertes'>Autres</th>
		<th title='Pertes totales'>Total</th></tr>";
		$i=0;
		$con = dbconnecti();
		$result = mysqli_query($con, "SELECT ID,Pays,Type,Engagement FROM Avion WHERE Pays IN (1,2,4,6,8) AND Prototype=0 AND Type<>8 AND Engagement < '$Date_Campagne' ORDER BY Engagement ASC");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$Total = 0;
				$ID = $data['ID'];
				$Pays = $data['Pays'];
				$Type = GetAvionType($data['Type']);
				$Avion_img = GetAvionIcon($ID,$Pays);
				
				//Transfer	
				$con = dbconnecti();
				$Abattu = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$ID' AND PVP = 1"),0);
				$DCA = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM DCA WHERE Avion='$ID'"),0);
				mysqli_close($con);
				$con = dbconnecti(4);
				$Crash = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Events WHERE Event_Type IN (11,12) AND Avion='$ID' AND Avion_Nbr=1"),0);
				$Perdu = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Events WHERE Event_Type=34 AND Avion='$ID'"),0);
				$Perdu2 = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Events_em WHERE Event_Type=222 AND Avion='$ID'"),0);
				mysqli_close($con);
				$i++;
				$Total = $DCA + $Abattu + $Crash + $Perdu + $Perdu2;
					
				echo "<tr><td>".$i."</td><td>".$Avion_img."</td><td><img src='".$Pays."20.gif'></td><td>".$Type."</td><td>".$data['Engagement']."</td><td bgcolor='black'></td>
				<td>".$Abattu."</td><td>".$DCA."</td><td>".$Crash."</td><td>".$Perdu."</td><td bgcolor='MistyRose'>".$Total."</td></tr>";
			}
			mysqli_free_result($result);
			unset($data);
		}
		else
			echo "<b>Désolé, aucun avion</b>";
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