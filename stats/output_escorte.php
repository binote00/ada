﻿<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 or $OfficierEMID >0)
{
	$country=$_SESSION['country'];
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
				$Front=$data['Front'];
				$Unite=$data['Unit'];
				$Admin=$data['Admin'];
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
				$Front=$data['Front'];
				$Admin=$data['Admin'];
			}
			mysqli_free_result($result);
		}
	}
	if($Admin ==1)
	{
		$query="SELECT e.Date,e.Unite,e.Avion,e.Escorte,e.Escorte_nbr,e.Joueur,e.Lieu,j.Nom,j.Front,u.Nom as Unite_Nom,u.Pays,l.Latitude,l.Longitude,l.Nom as Lieu_Nom FROM Escorte as e,Pilote as j,Unit as u,Lieu as l 
		WHERE e.Lieu=l.ID AND e.Joueur=j.ID AND e.Unite=u.ID ORDER BY e.ID DESC LIMIT 100";
	}
	else
	{
		$query="SELECT e.Date,e.Unite,e.Avion,e.Escorte,e.Escorte_nbr,e.Joueur,e.Lieu,j.Nom,j.Front,u.Pays,u.Nom as Unite_Nom,l.Latitude,l.Longitude,l.Nom as Lieu_Nom FROM Escorte as e,Pilote as j,Unit as u,Lieu as l 
		WHERE e.Lieu=l.ID AND j.Pays='$country' AND e.Joueur=j.ID AND e.Unite=u.ID ORDER BY e.ID DESC LIMIT 50";
	}
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
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
				$Escorte=$data['Escorte'];
				$Escorte_nbr=$data['Escorte_nbr'];
				$Joueur=$data['Joueur'];
				$Pilote=$data['Nom'];
				$Pays=$data['Pays'];
				$Lieu=$data['Lieu_Nom'];
				$Unite_s=$data['Unite_Nom'];
				$Escorte_Nom=GetData("Avion","ID",$Escorte,"Nom");
				$Avion_Nom=GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front_Lieu);
                $Escorte_Nom=GetAvionIcon($Escorte,$Pays,0,0,$Front_Lieu);
				$Avion_unit_img="images/unit/unit".$Unit."p.gif";
				if(is_file($Avion_unit_img))
					$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
				$Escs.="<tr><td>".$Date."</td><td>".$Lieu."</td><td><img src='".$Pays."20.gif'></td><td>".$Unite_s."</td><td>".$Pilote."</td><td>".$Avion_Nom."</td><td>".$Escorte_nbr." ".$Escorte_Nom."</td></tr>";
			}
		}
	}
	else
		$Escs="<h6>Aucune escorte récente</h6>";
	echo "<h1>Missions d'Escorte</h1>
	<p class='lead'>Ce tableau ne recense que les missions d'escorte réussies par des pilotes joueurs. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>
	<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr>
		<th>Date</th>
		<th>Lieu</th>
		<th>Pays</th>
		<th>Unité</th>
		<th>Pilote</th>
		<th>Avion</th>
		<th>Avions escortés</th>
	</tr></thead>".$Escs."</table></div>";
}
?>