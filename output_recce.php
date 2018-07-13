<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
$Officier = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if($PlayerID >0 or $Officier >0 or $OfficierEMID >0)
{
	$country = $_SESSION['country'];
	include_once('./jfv_include.inc.php');
	if($PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Front,Admin FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front = $data['Front'];
				$Unite = $data['Unit'];
				$Admin = $data['Admin'];
			}
			mysqli_free_result($result);
		}	
		$Base=GetData("Unit","ID",$Unite,"Base"); 
	}
	elseif($OfficierEMID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Admin FROM Officier_em WHERE ID='$OfficierEMID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front = $data['Front'];
				$Admin = $data['Admin'];
			}
			mysqli_free_result($result);
		}
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT r.*,l.Latitude,l.Longitude,l.Nom as Lieu_Nom FROM Recce as r,Lieu as l WHERE r.Lieu=l.ID ORDER BY ID DESC LIMIT 50");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Front_Lieu=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
			if($Admin or $Front ==$Front_Lieu or $Front ==99)
			{
				$Date = substr($data['Date'],0,16);
				$Avion = $data['Avion'];
				$Type = $data['Type'];
				$Noms = $data['Nom'];
				$Lieu = $data['Lieu_Nom'];
				$Pays=GetData("Unit","ID",$data['Unite'],"Pays");
				$Avion_Nom=GetAvionIcon($Avion,$Pays,$Pilote,$data['Unite'],$Front_Lieu);
				if($country ==$Pays or $OfficierEMID >0)
				{
					$Lieu =$data['Lieu_Nom'];
					$Unite_s=Afficher_Icone($data['Unite'],$Pays);
				}
				else
				{
					$Lieu="Inconnu";
					$Unite_s="Inconnu";
				}
				if($country ==$Pays)
				{
					$Pilote=GetData("Pilote","ID",$data['Joueur'],"Nom");
					if($Type ==2)
						$Cible_s="Cible marquée";
					else
					{
						if($Noms ==56 or $Noms ==6)
							$Cible_s="Visuel naval";
						elseif($Noms ==50)
							$Cible_s="Visuel terrestre";
						elseif($Noms ==15)
							$Cible_s="Visuel infrastructures";
						elseif($Noms ==1)
							$Cible_s="Photo";
					}
				}
				else
				{
					$Pilote="Inconnu";
					$Cible_s="Inconnu";
				}
				$Rec_txt.="<tr><td>".$Date."</td><td>".$Lieu."</td><td><img src='".$Pays."20.gif'></td><td>".$Unite_s."</td><td>".$Pilote."</td><td>".$Avion_Nom."</td><td>".$Cible_s."</td></tr>";
			}
		}
		mysqli_free_result($result);
	}
	else
		$Rec_txt="<h6>Aucune reconnaissance récente</h6>";
	echo "<h1>Missions de Reconnaissance</h1>
	<p class='lead'>Ce tableau ne recense que les missions de reconnaissance réussies par des pilotes joueurs. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>
	<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr>
			<th>Date</th>
			<th>Lieu</th>
			<th>Pays</th>
			<th>Unité</th>
			<th>Pilote</th>
			<th>Avion</th>
			<th>Résultat</th>
		</tr></thead>".$Rec_txt."</table></div>";
}
?>