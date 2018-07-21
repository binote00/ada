<?
require_once('./jfv_inc_sessions.php');
//include_once('./menu_classement.php');
$PlayerID=$_SESSION['PlayerID'];	
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 or $OfficierID >0 or $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_access.php');
	include_once('./jfv_txt.inc.php');
	echo "<h1>Tableau des Attaques aériennes IA</h1>
		<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr>
			<th>Date</th>
			<th>Cycle</th>
			<th>Lieu</th>
			<th>Action</th>
			<th>Pays</th>
			<th>Unité</th>
			<th>Avions</th>
			<th>Cible</th>
			<th>DCA</th>
			<th>Patrouilles</th>
			<th>Escortes</th>
	</tr></thead>";
	$country=$_SESSION['country'];
	if($PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Front,Renseignement FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Renseignement=$data['Renseignement'];
				$Front=$data['Front'];
				$Unite=$data['Unit'];
			}
			mysqli_free_result($result);
		}	
		$Base=GetData("Unit","ID",$Unite,"Base"); 
	}
	elseif($OfficierEMID)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front FROM Officier_em WHERE ID='$OfficierEMID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	elseif($Officier)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front FROM Officier WHERE ID='$Officier'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT a.*,u.Nom as Unite_s,u.Pays,l.Latitude,l.Longitude,l.Nom as Lieu_Nom
	FROM Attaque_ia as a,Unit as u,Lieu as l WHERE a.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW() AND a.Lieu=l.ID AND a.Unite=u.ID ORDER BY a.ID DESC LIMIT 50");
	mysqli_close($con);
	if($result)
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
				$Cible_id=$data['Target'];
				$Pilotes=$data['Pilotes'];
				$Unite_s=$data['Unite_s'];
				$Pays=$data['Pays'];
				$Lieu_Nom=$data['Lieu_Nom'];
				$Cycle=$data['Cycle'];
				if($Cycle)
					$Cycle_txt="Nuit";
				else
					$Cycle_txt="Jour";
				if(!$Pilotes)
					$icon="<img src='images/ia_down.png' title='Attaque sans effet'>";
				else
					$icon="<img src='images/ia_bomb.png' title='Attaque réussie'>";
				if($data['DCA'])
					$icon_dca="<img src='images/dca_shoot.png' title='DCA active'>";
				else
					$icon_dca="<img src='images/dca_sleep.png' title='DCA inactive'>";
				echo "<tr><td>".$Date."</td><td><img src='images/meteo".$Cycle.".gif' title='".$Cycle_txt."'></td><td>";
				if($country ==$Pays or $OfficierEMID >0 or $Lieu ==$Base)
					echo $Lieu_Nom."<td>".$icon;
				else
					echo "Inconnu</td><td>Inconnu";
				echo "</td><td><img src='".$Pays."20.gif'></td><td>";
				if($country ==$Pays or $Renseignement >150 or $OfficierEMID >0)
					echo Afficher_Icone($Unit,$Pays,$Unite_s);
				else
					echo "Inconnu";
				echo "</td><td>";
				if($country ==$Pays or $Renseignement >200 or $Admin ==1)
					echo $Pilotes;
				else
					echo "?";
				echo " ".GetAvionIcon($Avion,$Pays,0,$Unit,$Front)."</td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0 or $Lieu ==$Base)
				{
					if($Cible_id)
						echo GetVehiculeIcon($Cible_id,0,0,0,$Front);
					elseif(!$data['Arme'])
						echo "<img src='images/ia_para.png' title='Parachutage de ravitaillement'>";
					else
						echo "<img src='images/ia_troops.png' title='Troupes au sol'>";
				}
				else
					echo "Inconnu";
				if($country ==$Pays or $Renseignement >200 or $OfficierEMID >0)
					echo "</td><td>".$data['DCA']." ".$icon_dca."</td><td>".$data['Couverture']."</td><td>".$data['Escorte']."</td></tr>";
				else
					echo "</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td></tr>";
			}
		}
		mysqli_free_result($result);
	}
	else
		echo "<h6>Désolé, Aucune cible n'a été détruite récemment</h6>";
	echo "</table></div>";
}
?>