<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
//$Officier=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 xor $Officier >0 xor $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_access.php');
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
	$con=dbconnecti(); //(1,2,3,4,5,6,7,8,9,10,11,12,13,22,23,25,26,27,28,29,30,31,34,35,36)
	$result=mysqli_query($con,"SELECT a.Date,a.Unite,a.Avion,a.Lieu,a.Pays,a.Cible_id,a.Cycle,a.Altitude,u.Nom as Unite_s,u.Pays as Pays_s,l.Latitude,l.Longitude,l.Nom as Lieu_Nom
	FROM Bombardement as a,Unit as u,Lieu as l WHERE a.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW() AND a.Joueur=0
	AND a.Lieu=l.ID AND a.Unite=u.ID GROUP BY a.Date ORDER BY a.ID DESC LIMIT 50");
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
				$Avion_Nom=GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front_Lieu);
				if($Cible_id)$Cible_s=GetVehiculeIcon($Cible_id,$Pays_cible,0,0,$Front_Lieu);
				$Avion_unit_img="images/unit/unit".$Unit."p.gif";
				if(is_file($Avion_unit_img))
					$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
				if($Cycle)
					$Cycle_txt="Nuit";
				else
					$Cycle_txt="Jour";
				$liste_ia.="<tr><td>".$Date."</td><td><img src='images/meteo".$Cycle.".gif' title='".$Cycle_txt."'></td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0 or $Lieu ==$Base)
					$liste_ia.=$Lieu_Nom;
				else
					$liste_ia.="Inconnu";
				$liste_ia.="</td><td><img src='".$Pays."20.gif'></td><td>";
				if($country ==$Pays or $Renseignement >150 or $OfficierEMID >0)
					$liste_ia.=$Unite_s;
				else
					$liste_ia.="Inconnu";
				$liste_ia.="</td><td>".$Avion_Nom."</td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0 or $Lieu ==$Base)
					$liste_ia.=$Cible_s;
				else
					$liste_ia.="Inconnu";
				$liste_ia.="</td><td><img src='".$Pays_cible."20.gif'></td>";
				if(($OfficierEMID >0 or $Lieu ==$Base or $Admin ==1) and $Premium)
					$Dist_txt=$data['Altitude']."m";
				else
					$Dist_txt="<div class='i-flex premium20'></div>";
				$liste_ia.="<td>".$Dist_txt."</td></tr>";
			}
		}
		mysqli_free_result($result);
	}
	//Output
	echo "<h1>Bombardements Stratégiques</h1><a href='index.php?view=bombardements' class='btn btn-primary'>Pilotes Joueurs</a>";
	if($liste_ia)
	{
		echo "<div style='overflow:auto; width: 100%;'><table class='table table-striped table-condensed'>
		<thead><tr><th>Date</th><th>Cycle</th><th>Lieu</th><th>Pays</th><th>Unité</th><th>Avion</th><th>Cible</th><th>Pays Cible</th><th>Altitude</th></tr></thead>".$liste_ia."</table></div>";
	}
	else
		echo "Aucun bombardement stratégique à ce jour";
}
?>