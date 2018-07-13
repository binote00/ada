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
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	$Coord=GetCoord($Front);
	$Lat_base_min=$Coord[0];
	$Lat_base_max=$Coord[1];
	$Long_base_min=$Coord[2];
	$Long_base_max=$Coord[3];
	$query="SELECT a.*,u.Nom as Unite_s,u.Pays,l.Nom,l.Latitude,l.Longitude FROM Attaque_ia as a,Unit as u,Lieu as l WHERE a.Unite=u.ID AND a.Lieu=l.ID AND l.Latitude >='$Lat_base_min' AND l.Latitude <='$Lat_base_max' AND l.Longitude >='$Long_base_min' AND l.Longitude <='$Long_base_max' AND a.Arme >0 ORDER BY a.ID DESC LIMIT 25";
	$liste_ia='';
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
				if($data['Cycle'])
					$Cycle_txt='Nuit';
				else
					$Cycle_txt='Jour';
				if(!$data['Pilotes'])
					$icon="<img src='images/ia_down.png' title='Attaque sans effet'>";
				else
					$icon="<img src='images/ia_bomb.png' title='Attaque réussie'>";
				if($data['Target'])
					$icon_target=GetVehiculeIcon($data['Target'],0,0,0,$Front_Lieu);
				elseif(!$data['Arme'])
					$icon_target="<img src='images/ia_para.png' title='Parachutage de ravitaillement'>";
				else
					$icon_target="<img src='images/ia_troops.png' title='Troupes au sol'>";
				if($data['DCA'])
					$icon_dca="<img src='images/dca_shoot.png' title='DCA active'>";
				else
					$icon_dca="<img src='images/dca_sleep.png' title='DCA inactive'>";
				if($OfficierEMID >0 or $Admin or $Premium)
					$Pilotes_txt=$data['Pilotes'];
				else
				{
					$data['Couverture']='?';
					$data['Escorte']='?';
					$data['DCA']='?';
					$Pilotes_txt='?';
				}
				$liste_ia.="<tr><td>".$Date."</td><td><img src='images/meteo".$data['Cycle'].".gif' title='".$Cycle_txt."'></td><td>".$data['Nom']."</td><td>".$icon."</td>
				<td><img src='".$data['Pays']."20.gif'></td><td>".Afficher_Icone($data['Unite'],$data['Pays'],$data['Unite_s'])."</td><td>".$Pilotes_txt." ".GetAvionIcon($data['Avion'],$data['Pays'],0,$data['Unite'],$Front_Lieu)."</td>
				<td>".$icon_target."</td><td>".$data['DCA']." ".$icon_dca."</td><td>".$data['Couverture']."</td><td>".$data['Escorte']."</td></tr>";
			}
		}
		mysqli_free_result($result);
		//Output
		echo "<h1>Attaques aériennes</h1><a href='index.php?view=output_atk' class='btn btn-primary'>Pilotes Joueurs</a>";
		if($liste_ia)
		{
			echo "<div style='overflow:auto; width: 100%;'><table class='table table-striped table-condensed'>
			<thead><tr><th>Date</th><th>Cycle</th><th>Lieu</th><th>Action</th><th>Pays</th><th>Unité</th><th>Avions</th><th>Cible</th><th>DCA</th><th>Patrouilles</th><th>Escortes</th></tr></thead>".$liste_ia."</table></div>";
		}
		else
			echo '<p class="lead">Aucune attaque à ce jour</p>';
	}
}