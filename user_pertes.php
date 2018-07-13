<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$PlayerID = Insec($_GET['id']);
	if(!$PlayerID)
	{
		$PlayerID = $_SESSION['PlayerID'];
		$Pays = $_SESSION['country'];
	}
	else
		$Pays=GetData("Pilote","ID",$PlayerID,"Pays");
	echo "<h1>Pertes </h1><table class='table table-striped'>		
		<thead><tr><th>Avion</th><th>Abattu</th><th>DCA</th><th>Crash</th><th>Perdu</th>
		<th>Total <a href='#' class='popup'><img src='images/help.png'><span>Le rouge signifie que vous ne pouvez plus obtenir ce modèle en avion perso</span></a></th></tr></thead>";	
	$con=dbconnecti();
	$Date_Campagne = mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	$result=mysqli_query($con,"SELECT ID,Production,Fin_Prod FROM Avion WHERE Pays='$Pays' AND Etat=1 AND Prototype=0 ORDER BY Fin_Prod ASC");
	mysqli_close($con);
	if($result)
	{
		while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Crash=0;
			$Perdu=0;
			$Abattu=0;
			$DCA=0;
			$Avion = $data['ID'];		
			$con=dbconnecti(4);
			$Crash = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type IN (11,12) AND PlayerID='$PlayerID' AND Avion='$Avion' AND Avion_Nbr=1"),0);
			$Perdu = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=34 AND PlayerID='$PlayerID' AND Avion='$Avion'"),0);
			mysqli_close($con);
			$con=dbconnecti();
			$Abattu = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Avion' AND PVP=1 AND Pilote_loss='$PlayerID'"),0);
			$DCA = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Avion' AND Joueur='$PlayerID'"),0);
			mysqli_close($con);		
			$Total=$Crash+$Perdu+$Abattu+$DCA;
			if($Total >0)
			{
				if($Total >=$data['Production']/20)
					$Total="<span class='text-danger'>".$Total."</span>";
				elseif($data['Fin_Prod'] < $Date_Campagne)
					$Total="<span class='text-success'>".$Total."</span>";
				echo "<tr><th>".GetAvionIcon($Avion,$Pays,$PlayerID)."</th><td>".$Abattu."</td><td>".$DCA."</td><td>".$Crash."</td><td>".$Perdu."</td>
				<th>".$Total."</th></tr>";
			}
		}
		mysqli_free_result($result);
	}
	else
		echo "Erreur";
	echo "</table>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>