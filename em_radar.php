<?php
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');	
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Officier_EM or $OfficierEMID ==$Officier_Rens or $OfficierEMID ==$Adjoint_Terre or $GHQ or $Admin)
	{			
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Flag='$country' AND Radar >50 AND Flag_Radar='$country'"); // AND Latitude >47
		//mysqli_close($con);
		if($result)
		{
			if($datal=mysqli_fetch_array($result,MYSQLI_NUM))
				$Efficacite_radar=$datal[0];
			mysqli_free_result($result);
		}
		if($Efficacite_radar >0)
		{		
			if($Admin ==1)
			{
				$query="SELECT Lieu.Nom,Lieu.Zone,Pilote.S_alt,Pilote.S_Nuit,Lieu.Radar_Ori,Lieu.Radar
				FROM Pilote,Lieu WHERE Pilote.S_Cible=Lieu.ID AND DATE(Pilote.Credits_date)=DATE(NOW()) AND Pilote.Pays<>Lieu.Flag AND Lieu.Flag='$country'
				AND Lieu.Latitude >47 AND Lieu.Longitude <14 AND Lieu.Meteo >-50 AND Pilote.S_alt >100
				ORDER BY Lieu.Nom ASC";
			}
			else
			{
				$query="SELECT Lieu.Nom,Lieu.Zone,Pilote.S_alt,Pilote.S_Nuit,Lieu.Radar_Ori,Lieu.Radar
				FROM Pilote,Lieu WHERE Pilote.S_Cible=Lieu.ID AND DATE(Pilote.Credits_date)=DATE(NOW()) AND Pilote.Pays<>Lieu.Flag AND Lieu.Flag='$country' 
				AND Lieu.Latitude >47 AND Lieu.Longitude <14 AND Lieu.Meteo >-50 AND Pilote.S_alt >100 AND Lieu.Zone NOT IN (3,4,5,9) 
				ORDER BY Lieu.Nom ASC LIMIT '$Efficacite_radar'";
			}
			//$con=dbconnecti();
			$resultra=mysqli_query($con,$query);
			//mysqli_close($con);
			if($resultra)
			{
				while($Data=mysqli_fetch_array($resultra))
				{
					if($Data['Radar_Ori'] and $Data['Radar'] <50)
					{
						//Radar défectueux, ne rien afficher
					}
					else
					{
						if($Data['S_Nuit'])
							$Cycle_txt="Nuit";
						else
							$Cycle_txt="Jour";
						$alertes.="<tr><td>".$Data['Nom']."</td><td><img src='images/zone".$Data['Zone'].".jpg'></td>
						<td><img src='images/meteo".$Data['S_Nuit'].".gif' title='".$Cycle_txt."'></td><td>".$Data['S_alt']."m</td><td>Station radar</td><tr>";
					}
				}
				mysqli_free_result($resultra);
				unset($Data);
			}
			else
				$mes="<div class='alert alert-warning'>Aucune alerte radar pour l'instant</div>";
		}
		else
			$mes="<div class='alert alert-danger'>La chaîne de radars est tellement endommagée qu'elle est inefficace!</div>";
		//Recce
		if($Admin ==1)
		{
			$queryr="SELECT DISTINCT r.*,l.Nom as Ville,l.Zone FROM Recce as r,Pilote_IA as p,Lieu as l WHERE r.Lieu=p.Cible AND r.Lieu=l.ID AND p.Actif=1 AND l.Meteo >-50 AND p.Task=5 GROUP BY r.Lieu ORDER BY r.ID DESC LIMIT 50";
			$query_al="SELECT e.Event_Type,e.Avion,e.Lieu,e.Avion_Nbr,DATE_FORMAT(e.`Date`,'%Hh%i') as Heure FROM gnmh_aubedesaiglesnet4.Events_Feed as e,gnmh_aubedesaiglesnet.Lieu as l WHERE e.Lieu=l.ID 
						 AND e.Event_Type IN (78,116,200,201,201,202,432) AND DATE(e.`Date`)=DATE(NOW())";
		}
		else
		{
			$Coord=GetCoord($Front);
			$Lat_base_min=$Coord[0];
			$Lat_base_max=$Coord[1];
			$Long_base_min=$Coord[2];
			$Long_base_max=$Coord[3];
			$queryr="SELECT DISTINCT r.*,l.Nom as Ville,l.Zone FROM Recce as r,Pilote_IA as p,Lieu as l WHERE r.Lieu=p.Cible AND r.Lieu=l.ID AND p.Pays='$country' AND p.Actif=1 AND l.Meteo >-50 AND p.Task=5
			AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') GROUP BY r.Lieu ORDER BY r.ID DESC LIMIT 50";
			$query_al="SELECT e.Event_Type,e.Avion,e.Lieu,e.Avion_Nbr,DATE_FORMAT(e.`Date`,'%Hh%i') as Heure FROM gnmh_aubedesaiglesnet4.Events_Feed as e,gnmh_aubedesaiglesnet.Lieu as l WHERE e.Lieu=l.ID 
			AND l.Flag='$country' AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND e.Event_Type IN (78,116,200,201,201,202,432) AND DATE(e.`Date`)=DATE(NOW())";
		}
		//$con=dbconnecti();
		$resultr=mysqli_query($con,$queryr);
		mysqli_close($con);
		if($resultr)
		{
			while($data=mysqli_fetch_array($resultr))
			{				
				$Meteo_fix=0;
				if($data['Type'] ==2)
				{
					$Cycle_txt="Nuit";
					$Alt_txt="Variable";
					$Meteo_fix=1;
				}
				elseif($data['Type'] ==1)
				{
					$Cycle_txt="Jour";
					$Alt_txt="Haute";
				}
				else
				{
					$Cycle_txt="Jour";
					$Alt_txt="Basse";
				}
				$alertes.="<tr><td>".$data['Ville']."</td><td><img src='images/zone".$data['Zone'].".jpg'></td>
				<td><img src='images/meteo".$Meteo_fix.".gif' title='".$Cycle_txt."'></td><td>".$Alt_txt."</td><td>Veille aérienne</td><tr>";
			}
			mysqli_free_result($resultr);
		}
		//Move & DCA
		$con=dbconnecti(4);
		$result_dca=mysqli_query($con,$query_al);
		mysqli_close($con);
		if($result_dca)
		{
			while($data=mysqli_fetch_array($result_dca,MYSQLI_ASSOC))
			{
				if($data['Event_Type'] ==78)
				{
					if($data['Avion_Nbr'] ==3)
						$mes2.="<p>".$data['Heure']." La DCA de site de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b> annonce avoir endommagé un ".GetData("Avion","ID",$data['Avion'],"Nom")."</p>";
					elseif($data['Avion_Nbr'] ==2)
						$mes2.="<p>".$data['Heure']." La DCA rapprochée de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b> annonce avoir endommagé un ".GetData("Avion","ID",$data['Avion'],"Nom")."</p>";
					elseif($data['Avion_Nbr'] ==4)
						$mes2.="<p>".$data['Heure']." La DCA d'aérodrome de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b> annonce avoir endommagé un ".GetData("Avion","ID",$data['Avion'],"Nom")."</p>";
				}
				elseif($data['Event_Type'] ==116)
					$mes2.="<p>".$data['Heure']." Des troupes ont débarqué dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==200)
				{
					if($data['PlayerID'] >0)
						$mes2.="<p>".$data['Heure']." Des troupes ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"),true)." <img src='images/".$data['Avion']."20.gif'> ont été repérées faisant mouvement dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
					else
						$mes2.="<p>".$data['Heure']." Des unités ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"),true)." <img src='images/".$data['Avion']."20.gif'> ont été repérées faisant mouvement dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				}
				elseif($data['Event_Type'] ==201)
					$mes2.="<p>".$data['Heure']." Des navires ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"))." <img src='images/".$data['Avion']."20.gif'> ont été repérés faisant mouvement au large de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==202)
					$mes2.="<p>".$data['Heure']." Des navires ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"))." <img src='images/".$data['Avion']."20.gif'> ont été repérés faisant mouvement au large de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==432)
					$mes2.="<p>".$data['Heure']." Des troupes ".Pluriel(GetData("Pays","ID",$data['Avion_Nbr'],"adj"),true)." <img src='images/".$data['Avion_Nbr']."20.gif'> ont été repérées débarquant sur les plages de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
			}
			mysqli_free_result($result_dca);
		}
		if(!$mes2)
			$mes2="Rien à signaler";
		echo "<h2>Rapports</h2><ul class='list-inline'><li><a href='index.php?view=output_ia' class='btn btn-primary'>Combats aériens</a></li><li><a href='index.php?view=output_atk_ia' class='btn btn-primary'>Attaques aériennes</a></li></ul><div class='alert alert-warning'>".$mes2."</div>
		<h2>Alertes</h2><table class='table table-striped'><thead><tr><th>Cible</th><th>Zone</th><th>Cycle</th><th>Altitude</th><th>Source</th></tr></thead>".$alertes."</table>".$mes;
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>