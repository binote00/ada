<?
require_once('./jfv_inc_sessions.php');
//include_once('./menu_classement.php');
$PlayerID=$_SESSION['PlayerID'];
//$Officier=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 xor $Officier >0 xor $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
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
	elseif($OfficierEMID >0)
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
		$Renseignement=201;
	}
	elseif($Officier >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Trait FROM Officier WHERE ID='$Officier'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
				if($data['Trait'] ==23)$Renseignement=101;
			}
			mysqli_free_result($result);
		}	
	}
	echo "<h1>Avions abattus par la D.C.A</h1>
	<p class='lead'>Ce tableau n'est pas une liste exhaustive de toutes les pertes dues à la DCA. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>
	<div style='overflow:auto; width: 100%;'>
	<table class='table table-striped table-condensed'>
		<thead><tr>
			<th>Date</th>
			<th>Cycle</th>
			<th>Lieu</th>
			<th>Pays</th>
			<th>Unité</th>
			<th>Pilote abattu</th>
			<th>Avion</th>
			<th>DCA</th>
			<th>Altitude</th>
		</tr></thead>";
	/*if($Admin ==1)
		$query="SELECT d.Avion,d.Joueur,d.Unite,d.Lieu,d.Altitude,d.Date,d.Cible_id,d.Cycle,l.Nom,l.Latitude,l.Longitude,l.Flag FROM DCA as d,Lieu as l 
		WHERE d.Lieu=l.ID AND d.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW() ORDER BY d.ID DESC LIMIT 200";
	else*/
		$query="SELECT d.Avion,d.Joueur,d.Unite as Unite,u.Pays,u.Nom as Unite_s,d.Lieu,d.Altitude as Altitude,d.Date,d.Cible_id as Cible_id,d.Cycle as Cycle,l.Nom,l.Latitude,l.Longitude,l.Flag 
		FROM DCA as d,Lieu as l,Unit as u 
		WHERE d.Lieu=l.ID AND d.Unite=u.ID AND d.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW()
		UNION
		SELECT d.Avion,0 as Joueur,d.Unit as Unite,u.Pays,u.Nom as Unite_s,d.Lieu,500 as Altitude,d.Date,16 as Cible_id,0 as Cycle,l.Nom,l.Latitude,l.Longitude,l.Flag 
		FROM gnmh_aubedesaiglesnet4.Events_Pertes as d,Lieu as l,Unit as u
		WHERE d.Event_Type=231 AND d.Lieu=l.ID AND d.Unit=u.ID AND d.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW()
		ORDER BY Date DESC LIMIT 50";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
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
				$Altitude=$data['Altitude'];
				$Joueur=$data['Joueur'];
				$Cycle=$data['Cycle'];
				$Cible_id=$data['Cible_id'];
				$Flag=$data['Flag'];
				$Pays=$data['Pays'];
				$Unite_s=$data['Unite_s'];
				if($country ==$Flag or $Unit ==$Unite or $Renseignement >100 or $Admin)
				{
					$Lieu_Nom=$data['Nom'];
					if($Joueur >0)
						$Pilote=GetData("Pilote","ID",$Joueur,"Nom");
					else
						$Pilote="Inconnu";
				}
				else
				{
					$Lieu_Nom="Inconnu";
					$Unite_s="Inconnu";
					$Pilote="Inconnu";
				}
				$Avion_Nom=GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front_Lieu);
				$Avion_unit_img='images/unit/unit'.$Unit.'p.gif';
				if(is_file($Avion_unit_img))
					$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
				if($Cycle)
					$Cycle_txt="Nuit";
				else
					$Cycle_txt="Jour";
				echo "<tr><td>".$Date."</td>
					<td>"."<img src='images/meteo".$Cycle.".gif' title='".$Cycle_txt."'>"."</td>
					<td>".$Lieu_Nom."</td>
					<td><img src='".$Pays."20.gif'></td>
					<td>".$Unite_s."</td>
					<td>".$Pilote."</td>
					<td>".$Avion_Nom."</td>
					<td>"."<img src='images/vehicules/vehicule".$Cible_id.".gif'>"."</td>
					<td>".$Altitude."m</td></tr>";
			}
		}
		mysqli_free_result($result);
	}
}
else
	echo "<h6>Aucun avion abattu par la D.C.A récemment</h6>";
echo "</table></div>";
?>
