<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_txt.inc.php');
	$Pays=$_SESSION['country'];
	$con=dbconnecti();
	$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
	$result_vic=mysqli_query($con,"SELECT AvionID,Pilotage FROM XP_Avions WHERE PlayerID='$PlayerID' ORDER BY Pilotage DESC");
	$Heures=mysqli_result(mysqli_query($con,"SELECT SUM(Pilotage) FROM XP_Avions WHERE PlayerID='$PlayerID'"),0);
	$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
	mysqli_close($con);
	if($results)
	{
		while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
		{
			$Skills_Pil[]=$data['Skill'];
		}
		mysqli_free_result($results);
	}
	$Pilotage=50;
	if(is_array($Skills_Pil))
	{
		if(in_array(21,$Skills_Pil))
			$Pilotage=150;
		elseif(in_array(20,$Skills_Pil))
			$Pilotage=125;
		elseif(in_array(19,$Skills_Pil))
			$Pilotage=100;
		elseif(in_array(18,$Skills_Pil))
			$Pilotage=75;
	}
	if($result_vic)
	{
		while($data=mysqli_fetch_array($result_vic,MYSQLI_ASSOC))
		{
			if($Premium)
			{
				$Pilotage_tot=GetPilotage("Avion",$PlayerID,$data['AvionID'],0,$Pilotage);
				$Exp_cumul=($Pilotage_tot-$Pilotage)*10;
				$liste.="<tr><th>".GetAvionIcon($data['AvionID'],$Pays,$PlayerID)."</th><td>".round($data['Pilotage'])."</td><td>".round($Exp_cumul)."</td><td>".round($Pilotage_tot)."</td></tr>";
			}
			else
				$liste.="<tr><th>".GetAvionIcon($data['AvionID'],$Pays,$PlayerID)."</th><td>".round($data['Pilotage'])."</td><td><img src='images/premium50.png' title='Information Premium'></td><td><img src='images/premium50.png' title='Information Premium'></td></tr>";
		}	
		echo "<h1>Expérience en Pilotage</h1><h3>Expérience totale ".round($Heures)."</h3><h3>Expérience sur porte-avions ".round(GetPil_PA($PlayerID))."</h3>
		<table class='table table-striped'><thead><tr><th>Avion</th><th>Expérience sur ce modèle</th><th>Expérience cumulée <a href='#' class='popup'><img src='images/help.png'><span>Expérience sur toutes les versions de ce modèle</span></a></th><th>Pilotage sur ce modèle</th></tr></thead>".$liste."</table>";
	}
	else
		echo "<h6>Vous ne possédez pas encore suffisamment d'expérience</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>