<?
require_once('./jfv_inc_sessions.php');
//include_once('./menu_classement.php');
$PlayerID=$_SESSION['PlayerID'];
$Officier=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID or $Officier or $OfficierEMID)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	if($PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Renseignement,Admin FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Renseignement=$data['Renseignement'];
				$Front=$data['Front'];
				$Admin=$data['Admin'];
			}
			mysqli_free_result($result);
		}	
	}
	elseif($OfficierEMID)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Admin FROM Officier_em WHERE ID='$OfficierEMID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
				$Admin=$data['Admin'];
			}
			mysqli_free_result($result);
		}	
	}
	elseif($Officier)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Admin FROM Officier WHERE ID='$Officier'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
				$Admin=$data['Admin'];
			}
			mysqli_free_result($result);
		}	
	}
?>
	<h1>Navires Coulés</h1>
	<p class='lead'>Ce tableau ne recense que les navires coulés, en aucun cas toutes les attaques subies par les navires. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>
	<div style='overflow:auto; width: 100%;'>
	<table class='table table-striped table-condensed'>
		<thead><tr>
			<th>Date</th>
			<th>Lieu</th>
			<th>Avion</th>
			<th>Cible détruite</th>
		</tr></thead>
<?
	$con=dbconnecti();
	/*$result=mysqli_query($con,"SELECT a.Date,a.Unite,a.Avion,a.Lieu,a.Pays,a.Cible_id,a.Joueur,a.Cycle,a.Tues,a.Degats,j.Nom,j.Front,u.Nom as Unite_s,u.Pays as Pays_s,l.Latitude,l.Longitude,l.Nom as Lieu_Nom
	FROM Attaque as a,Pilote as j,Unit as u,Lieu as l WHERE a.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW() AND a.Cible_id >4999
	AND a.Joueur=j.ID AND a.Lieu=l.ID AND a.Unite=u.ID GROUP BY a.Date,a.Joueur,a.Cible_id ORDER BY a.ID DESC LIMIT 50");*/
	$result2=mysqli_query($con,"SELECT e.*,l.Latitude,l.Longitude,l.Nom as Lieu_Nom FROM gnmh_aubedesaiglesnet4.Events_Ground as e,gnmh_aubedesaiglesnet.Lieu as l
	WHERE e.Lieu=l.ID AND e.Event_Type IN (602,702) AND e.Pilote_eni >4999 ORDER BY e.ID DESC");
	mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Front_Lieu=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
			if($Admin or $Front ==$Front_Lieu or $Front ==99)
			{
				$Date=substr($data['Date'],0,16);
				$Unit=$data['Unit'];
				$Avion=$data['Avion'];
				$Lieu=$data['Lieu'];
				$Cible_id=$data['Pilote_eni'];
				$Lieu_Nom=$data['Lieu_Nom'];
				//$Tues=$data['Avion_Nbr'];
				$Unite_s=Afficher_Icone($Unit,0);
				$Avion_Nom=GetAvionIcon($Avion,0,0,$Unit,$Front_Lieu);
				if($Cible_id)
				{
					$Cible_s=GetNavireByIcon($Cible_id);
					$Cible_img='images/vehicules/vehicule'.$Cible_id.'.gif';
					if(is_file($Cible_img))
						$Cible_s="<img src='".$Cible_img."' title='".$Cible_s."'>";
				}
				echo "<tr><td>".$Date."</td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0)
					echo $Lieu_Nom;
				else
					echo "Inconnu";
				echo "</td><td>".$Avion_Nom."</td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0)
					echo $Cible_s;
				else
					echo "Inconnu";
				echo "</td></tr>";
			}
		}
		mysqli_free_result($result2);
	}
	/*if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Front_Lieu=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
			if($Admin or $Front ==$Front_Lieu or $Front ==99)
			{
				$Date=substr($data['Date'],0,16);
				$Unit=$data['Unite'];
				$Avion=$data['Avion'];
				$Lieu=$data['Lieu'];
				$Pays_cible=$data['Pays'];
				$Cible_id=$data['Cible_id'];
				$Joueur=$data['Joueur'];
				$Unite_s=$data['Unite_s'];
				$Pays=$data['Pays_s'];
				$Lieu_Nom=$data['Lieu_Nom'];
				$Cycle=$data['Cycle'];
				$Pilote=$data['Nom'];
				$Front=$data['Front'];
				$Tues=$data['Tues'];
				$Degats=$data['Degats'];
				$Avion_Nom=GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front);
				if($Cible_id)
				{
					$Cible_s=GetNavireByIcon($Cible_id);
					$Cible_img='images/vehicules/vehicule'.$Cible_id.'.gif';
					if(is_file($Cible_img))
						$Cible_s="<img src='".$Cible_img."' title='".$Cible_s."'>";
				}
				$Avion_unit_img="images/unit/unit".$Unit."p.gif";
				if(is_file($Avion_unit_img))$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
				echo "<tr><td>".$Date."</td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0)
					echo $Lieu_Nom;
				else
					echo "Inconnu";
				echo "</td><td><img src='".$Pays."20.gif'></td><td>";
				if($country ==$Pays or $Renseignement >150 or $OfficierEMID >0)
					echo $Unite_s;
				else
					echo "Inconnu";
				echo "</td><td>";
				if($country ==$Pays or $Renseignement >200 or $PlayerID ==1)
					echo $Pilote;
				else
					echo "Inconnu";
				echo "</td><td>".$Avion_Nom."</td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0)
					echo $Cible_s;
				else
					echo "Inconnu";
				echo "</td><td><img src='".$Pays_cible."20.gif'></td></tr>";
			}
		}
		mysqli_free_result($result);
	}
	else
		echo "<h6>Désolé, Aucun navire n'a été coulé récemment</h6>";*/
	echo "</table></div>";
}
?>