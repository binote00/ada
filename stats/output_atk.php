<?
require_once('./jfv_inc_sessions.php');
//include_once('./menu_classement.php');
$PlayerID=$_SESSION['PlayerID'];	
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 or $OfficierID >0 or $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
    include_once('./jfv_access.php');
	echo "<h1>Attaques aériennes</h1>
		<p class='lead'>Ce tableau n'est pas une liste exhaustive de toutes les pertes dues aux attaques aériennes. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>";
	echo "<div style='overflow:auto; width: 100%;'><table class='table table-striped table-condensed'><thead><tr>
			<th>Date</th>
			<th>Cycle</th>
			<th>Lieu</th>
			<th>Pays</th>
			<th>Unité</th>
			<th>Pilote crédité</th>
			<th>Avion</th>
			<th>Cible détruite</th>
			<th>Pays cible</th>
	</tr></thead>";
	$country=$_SESSION['country'];
	if($PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Front,Renseignement,Admin FROM Pilote WHERE ID='$PlayerID'");
		//mysqli_close($con);
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
		$Base=mysqli_result(mysqli_query($con,"SELECT Base FROM Unit WHERE ID='$Unite'"),0);
	}
	elseif($OfficierEMID)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Admin FROM Officier_em WHERE ID='$OfficierEMID'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	elseif($OfficierID)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Admin FROM Officier WHERE ID='$OfficierID'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	//$con=dbconnecti(); //(1,2,3,4,5,6,7,8,9,10,11,12,13,22,23,25,26,27,28,29,30,31,34,35,36)
	$result=mysqli_query($con,"SELECT a.Date,a.Unite,a.Avion,a.Lieu,a.Pays,a.Cible_id,a.Joueur,a.Cycle,a.Tues,a.Degats,j.Nom,j.Front,u.Nom as Unite_s,u.Pays as Pays_s,l.Latitude,l.Longitude,l.Nom as Lieu_Nom
	FROM Attaque as a,Pilote as j,Unit as u,Lieu as l WHERE a.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW() AND a.Cible_id <5000 AND a.Cible_id NOT IN (8,18,52)
	AND a.Joueur=j.ID AND a.Lieu=l.ID AND a.Unite=u.ID GROUP BY a.Date,a.Joueur,a.Cible_id ORDER BY a.ID DESC LIMIT 50");
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
					$Cible_s=GetVehiculeIcon($Cible_id,$Pays_cible,0,0,$Front);
				if($Tues)
					$Cible_s.=" ".$Tues;
				$Avion_unit_img="images/unit/unit".$Unit."p.gif";
				if(is_file($Avion_unit_img))
					$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
				if($Cycle)
					$Cycle_txt="Nuit";
				else
					$Cycle_txt="Jour";
				echo "<tr><td>".$Date."</td><td><img src='images/meteo".$Cycle.".gif' title='".$Cycle_txt."'></td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0 or $Lieu ==$Base)
					echo $Lieu_Nom;
				else
					echo "Inconnu";
				echo "</td><td><img src='".$Pays."20.gif'></td><td>";
				if($country ==$Pays or $Renseignement >150 or $OfficierEMID >0)
					echo $Unite_s;
				else
					echo "Inconnu";
				echo "</td><td>";
				if($country ==$Pays or $Renseignement >200 or $Admin ==1)
					echo $Pilote;
				else
					echo "Inconnu";
				echo "</td><td>".$Avion_Nom."</td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0 or $Lieu ==$Base)
					echo $Cible_s;
				else
					echo "Inconnu";
				echo "</td><td><img src='".$Pays_cible."20.gif'></td></tr>";
			}
		}
		mysqli_free_result($result);
	}
	else
		echo "<h6>Désolé, Aucune cible n'a été détruite récemment</h6>";
	echo "</table></div>";
}
?>