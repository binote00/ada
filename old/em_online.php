<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_txt.inc.php');

//Check Joueur Valide
if(isset($_SESSION['login']) AND isset($_SESSION['pwd']))
{
	$PlayerID = $_SESSION['PlayerID'];
	$country = $_SESSION['country'];
	$Front = GetData("Joueur","ID",$PlayerID,"Front");
	$Renseignement = GetData("Joueur","ID",$PlayerID,"Renseignement");
	$Avancement = GetData("Joueur","ID",$PlayerID,"Avancement");
	$Commandant = GetDoubleData("Pays", "Pays_ID", $country, "Front", $Front, "Commandant");
	$Officier_Adjoint = GetDoubleData("Pays", "Pays_ID", $country, "Front", $Front, "Adjoint_EM");
	$Officier_EM = GetDoubleData("Pays", "Pays_ID", $country, "Front", $Front, "Officier_EM");
	$Officier_Rens = GetDoubleData("Pays", "Pays_ID", $country, "Front", $Front, "Officier_Rens");
	
	include_once('./menu_em.php');
	if($PlayerID == 1 or $PlayerID == $Commandant or $PlayerID == $Officier_Adjoint or $PlayerID == $Officier_EM or $PlayerID == $Officier_Rens or $Avancement > 24999 or $Renseignement > 200)
	{			
		if($PlayerID == 1)
		{
			$con = dbconnecti();
			$result=mysqli_query($con, "SELECT Duels_Candidats.PlayerID,Lieu.Nom,Duels_Candidats.Avion,Joueur.Nom,Joueur.Avancement,Joueur.Pays,Unit.ID,Unit.Nom,Lieu.Zone,
			Duels_Candidats.Altitude,Duels_Candidats.HP,Duels_Candidats.Essence,Duels_Candidats.Mun1,Duels_Candidats.Mun2,Duels_Candidats.Cycle
			FROM Duels_Candidats,Joueur,Unit,Lieu
			WHERE Duels_Candidats.PlayerID=Joueur.ID AND Joueur.Unit=Unit.ID AND Duels_Candidats.Lieu=Lieu.ID
			AND Duels_Candidats.PlayerID<>'$PlayerID' ORDER BY Joueur.Nom ASC");
			mysqli_close($con);
		}
		else
		{
			$con = dbconnecti();
			$result=mysqli_query($con, "SELECT Duels_Candidats.PlayerID,Lieu.Nom,Duels_Candidats.Avion,Joueur.Nom,Joueur.Avancement,Joueur.Pays,Unit.ID,Unit.Nom,Lieu.Zone,
			Duels_Candidats.Altitude,Duels_Candidats.HP,Duels_Candidats.Essence,Duels_Candidats.Mun1,Duels_Candidats.Mun2,Duels_Candidats.Cycle
			FROM Duels_Candidats,Joueur,Unit,Lieu
			WHERE Duels_Candidats.PlayerID=Joueur.ID AND Joueur.Unit=Unit.ID AND Duels_Candidats.Lieu=Lieu.ID
			AND Duels_Candidats.PlayerID<>'$PlayerID' AND Duels_Candidats.Country='$country' AND Joueur.Front='$Front'
			ORDER BY Joueur.Nom ASC");
			mysqli_close($con);
		}
		if($result)
		{
			echo "<table border='0' cellspacing='1' cellpadding='5' bgcolor='#ECDDC1'><tr><th colspan='13' bgcolor='lightyellow'>Pilotes en mission</th></tr>
			<tr class='TitreBleu_bc'><th>Nom</th><th>Grade</th><th>Unité</th><th>Avion</th><th>Lieu</th><th>Zone</th><th>Cycle</th>
			<th>Altitude</th><th>Robustesse</th><th>Carburant</th><th>Munitions</th></tr>";
			while($Data = mysqli_fetch_array($result, MYSQLI_NUM))
			{
				$Avancement = GetAvancement($Data[4],$Data[5]);
				$Muns = $Data[12] + $Data[13];
				if($Data[14])
					$Cycle_txt = "Nuit";
				else
					$Cycle_txt = "Jour";
				
			echo $titre."<tr><td><a href='user_public.php?Pilote=".$Data[0]."' target='_blank'>".$Data[3]."</a></td>
				<td title='".$Avancement[0]."'><img src='images/pgrades".$Data[5].$Avancement[1].".gif'></td>
				<td title='".$Data[7]."'><img src='images/unit".$Data[6]."p.gif'></td>
				<td><img src='images/avion".$Data[2].".gif'></td>
				<td>".$Data[1]."</td><td><img src='images/zone".$Data[8].".jpg'></td><td><img src='images/meteo".$Data[14].".gif' title='".$Cycle_txt."'></td>
				<td>".$Data[9]."m</td><td>".$Data[10]."</td><td>".$Data[11]."</td><td>".$Muns."</td><tr>";
			}
			echo "</table>";
			mysqli_free_result($result);
			unset($Data);
			unset($result);
		}
		else
		{
			echo "Aucun pilote n'est actuellement en mission.";
		}
	}
	else
	{
		?>
			<div align="center" bgcolor="#ECDDC1">
				<table border="0" cellspacing="1" cellpadding="5" bgcolor="#ECDDC1">
					<tr>
					<td><img src='images/top_secret.gif'></td>
					</tr>
					<tr>
					<td>Ces données sont classifiées.</td> 
					</tr>
					<tr>
					<td>Votre rang ne vous permet pas d'accéder à ces informations.</td>
					</tr>
				</table>
			</div>
		<?
	}
}
else
{
	echo "Vous devez être connecté pour accéder à cette page!";
}
?>