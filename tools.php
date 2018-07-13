<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
//Check Joueur Valide
if($_SESSION['AccountID'] >0 and $_SESSION['AccountID']<8)
{
	include_once('./jfv_txt.inc.php');
	echo "<br><a href='index.php?view=admin_esc_journal' class='lien'>Journaux d'unité</a>";
	echo "<br><a href='admin_esc_pat.php' target='_blank' class='lien'>Patrouilles d'unité</a>";
	echo "<br><a href='admin_esc_esc.php' target='_blank' class='lien'>Escortes d'unité</a>";
	echo "<hr>====Multi IP====<br>";
	$Dateref=date('Y-m-d');	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT DISTINCT j1.Nom,j1.IP,j1.Pays,j2.Pays FROM Joueur j1 
	LEFT OUTER JOIN Joueur j2 ON j2.ID!=j1.ID AND j2.IP=j1.IP AND j1.Pays!=j2.Pays AND j1.IP<>'' WHERE j2.ID IS NOT NULL AND j1.Actif=0 AND j2.Actif=0 AND j2.Admin=0 ORDER BY j1.IP ASC");
	$result3=mysqli_query($con,"SELECT DISTINCT j1.Nom,j1.Mdp,j1.Pays,j2.Pays FROM Joueur j1 
	LEFT OUTER JOIN Joueur j2 ON j2.ID!=j1.ID AND j2.Mdp=j1.Mdp AND j1.Pays!=j2.Pays WHERE j2.ID IS NOT NULL AND j1.Actif=0 AND j2.Actif=0 AND j2.Admin=0 ORDER BY j1.Mdp ASC");
	//$result4=mysqli_query($con,"SELECT DISTINCT j1.Nom,j1.login FROM Joueur j1 LEFT OUTER JOIN Joueur j2 ON j2.ID!=j1.ID AND j2.login=j1.login WHERE j2.ID IS NOT NULL ORDER BY j1.login ASC");
	//$result55=mysqli_query($con,"SELECT DISTINCT j.Nom FROM Joueur as j,Connectes as c WHERE j.ID = c.PlayerID AND c.Proxy<>'' ORDER BY j.Nom ASC");
	$result44=mysqli_query($con,"SELECT COUNT(IP),IP,login,Pays FROM Joueur WHERE Actif=0 AND Admin=0 GROUP BY (IP)");
	$result45=mysqli_query($con,"SELECT COUNT(Mdp),Mdp,login,Pays FROM Joueur WHERE Actif=0 AND Admin=0 GROUP BY (Mdp)");
	$result5=mysqli_query($con,"SELECT Nom,Engagement,Avancement,Parrain FROM Pilote WHERE Parrain >0 AND Actif=0
	AND Credits_Date BETWEEN '$Dateref' - INTERVAL 7 DAY AND '$Dateref' + INTERVAL 7 DAY AND Unit NOT IN (191,192,193,194,387,388,389,413,414,759)");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_NUM))
		{
			if(GetData("Pays","ID",$data[2],"Faction") != GetData("Pays","ID",$data[3],"Faction"))
				echo $data[1]." ".$data[0]." (".$data[2]." ".$data[3].")<br>";
		}
		mysqli_free_result($result);
	}
	unset($data);
	echo "====Multi Pwd====<br>";
	if($result3)
	{
		while($data=mysqli_fetch_array($result3,MYSQLI_NUM))
		{
			if(GetData("Pays","ID",$data[2],"Faction") != GetData("Pays","ID",$data[3],"Faction"))
				echo $data[1]." ".$data[0]." (".$data[2]." ".$data[3].")<br>";
		}
		mysqli_free_result($result3);
	}
	unset($data);
	echo "====4====<br>";
	if($result4)
	{
		while($data=mysqli_fetch_array($result4,MYSQLI_NUM))
		{
			echo $data[1]." ".$data[0]."<br>";
		}
		mysqli_free_result($result4);
	}
	unset($data);
	echo "====Multi==== (IP - MdP)<br>";
	if($result44)
	{
		while($data=mysqli_fetch_array($result44,MYSQLI_NUM))
		{
			if($data[0] > 2)
				echo $data[0]."x ".$data[1]." (".$data[2].")<br>";
		}
		mysqli_free_result($result44);
	}
	if($result45)
	{
		while($data=mysqli_fetch_array($result45,MYSQLI_NUM))
		{
			if($data[0] > 2)
				echo $data[0]."x ".$data[1]." (".$data[2].")<br>";
		}
		mysqli_free_result($result45);
	}
	echo "====Parrain====<br>";
	if($result5)
	{
		while($data=mysqli_fetch_array($result5,MYSQLI_NUM))
		{
			echo $data[0]." (".$data[1].") ".$data[2].", parrainé par ".GetData("Joueur","ID",$data[3],"Nom")."<br>";
		}
		mysqli_free_result($result5);
	}
	/*Proxy
	echo "====Proxy====<br>";
	if($result55)
	{
		while($data=mysqli_fetch_array($result55,MYSQLI_NUM))
		{
			echo $data[0]."<br>";
		}
		mysqli_free_result($result55);
	}
	unset($data);*/
	//Medal 1 an
	//$Dateav=str_replace('2016','2015',$Dateref);
	$con=dbconnecti();
    /*$reset100=mysqli_query($con,"UPDATE Lieu SET Flag=Pays WHERE (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");
    $reset101=mysqli_query($con,"UPDATE Lieu SET Flag_Route=Pays WHERE NoeudR >0 AND (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");
    $reset102=mysqli_query($con,"UPDATE Lieu SET Flag_Gare=Pays WHERE NoeudF_Ori >0 AND (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");
    $reset103=mysqli_query($con,"UPDATE Lieu SET Flag_Port=Pays WHERE Port_Ori >0 AND (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");
    $reset104=mysqli_query($con,"UPDATE Lieu SET Flag_Pont=Pays WHERE Pont_Ori >0 AND (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");
    $reset105=mysqli_query($con,"UPDATE Lieu SET Flag_Air=Pays WHERE BaseAerienne >0 AND (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");
    $reset106=mysqli_query($con,"UPDATE Lieu SET Flag_Usine=Pays WHERE Industrie<>'' AND (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");
    $reset107=mysqli_query($con,"UPDATE Lieu SET Flag_Radar=Pays WHERE Radar_Ori >0 AND (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");
    $reset108=mysqli_query($con,"UPDATE Lieu SET Flag_Plage=Pays WHERE Plage >0 AND (Longitude >92 OR (Longitude >21 AND Latitude >44)) AND Zone<>6 AND Pays NOT IN(3,4,6,10,15,17,19)");*/
	
	/*$reset110=mysqli_query($con,"UPDATE Regiment_IA as r,Lieu as l SET r.Lieu_ID=1368,r.Visible=0,r.Placement=0 WHERE r.Lieu_ID=l.ID AND r.Front=3 AND r.Pays=9 AND l.Pays<>9");
	$reset111=mysqli_query($con,"UPDATE Regiment_IA as r,Lieu as l SET r.Lieu_ID=1366,r.Visible=0,r.Placement=0 WHERE r.Lieu_ID=l.ID AND r.Front=3 AND r.Pays=7 AND l.Pays<>7");
	$reset112=mysqli_query($con,"UPDATE Regiment_IA as r,Lieu as l SET r.Lieu_ID=601,r.Visible=0,r.Placement=0 WHERE r.Lieu_ID=l.ID AND r.Front IN(1,4) AND r.Pays=8 AND l.Pays<>8");
	$reset113=mysqli_query($con,"UPDATE Regiment_IA as r,Lieu as l SET r.Lieu_ID=1000,r.Visible=0,r.Placement=0 WHERE r.Lieu_ID=l.ID AND r.Front IN(1,4) AND r.Pays=1 AND l.Pays=8");*/
	
	//$reset=mysqli_query($con,"UPDATE Pilote SET medal16=1 WHERE Engagement <'$Dateav' AND Credits_date='$Dateref' AND Actif=0");
	$resetpr=mysqli_query($con,"UPDATE Joueur SET Premium=0 WHERE Premium_date <'$Dateref'");
	/*$reset1=mysqli_query($con,"UPDATE Regiment as r,Officier as o SET r.Vehicule_Nbr=0,o.Division=0 WHERE r.Officier_ID=o.ID AND o.Credits_date <'".$Date_inactif_off."'");
	$reset2=mysqli_query($con,"UPDATE Regiment as r,Officier as o SET r.Vehicule_Nbr=0,o.Division=0 WHERE r.Officier_ID=o.ID AND o.Actif=1");*/
/*	$result33=mysqli_query($con,"SELECT DISTINCT j1.Nom,j1.Officier_Adjoint,j2.Officier_Adjoint FROM Unit as j1 LEFT OUTER JOIN Unit as j2 ON j2.ID!=j1.ID AND j2.Officier_Adjoint=j1.Officier_Adjoint 
	WHERE j1.Officier_Adjoint >0 AND j2.Officier_Adjoint >0 AND j2.ID IS NOT NULL ORDER BY j1.Officier_Adjoint ASC");*/
	/*$resultdoubles=mysqli_query($con,"SELECT DISTINCT j2.ID,j1.adresse,j2.adresse,j1.Mdp,j2.Mdp,j1.IP,j2.IP,j1.2nd_Pilot,j2.2nd_Pilot,j1.ID FROM Joueur j1 
	LEFT OUTER JOIN Joueur j2 ON j2.ID!=j1.ID AND (j2.Pilote_id=j1.2nd_Pilot OR j1.Pilote_id=j2.2nd_Pilot) 
	WHERE j2.ID IS NOT NULL AND j1.Actif=0 AND j2.Actif=0 AND j2.Admin=0 AND j2.2nd_Pilot>0 ORDER BY j1.Mdp ASC");*/
	/*$Pays_o=6;
	$dist=mysqli_query($con,"SELECT l.ID AS City, l.Nom as Depart, l.Latitude AS Lata, l.Longitude AS Longa, o.Nom AS Objectif, o.Longitude AS Longb, o.Latitude AS Latb
	FROM Lieu AS l,Lieu AS o WHERE l.ID!=o.ID AND l.Pays='$Pays_o' AND l.Zone<>6 AND l.Travel=0 AND o.Pays='$Pays_o' AND o.Zone<>6 AND o.Travel=0");
	if($dist)
	{
		while($data=mysqli_fetch_array($dist))
		{
			$Distance=GetDistance(0,0,$data['Longa'],$data['Lata'],$data['Longb'],$data['Latb']);
			if($Distance[0]<=50)
			{
				$Travel[]=$data['City'];
				echo $data['Depart']." vers ".$data['Objectif']." ".$Distance[0]."km<br>";
			}
		}
		mysqli_free_result($dist);
	}
	if(is_array($Travel))
	{
		$lieux_inte=implode(',',$Travel);
		$reset99=mysqli_query($con,"UPDATE Lieu SET Travel=1 WHERE ID IN(".$lieux_inte.")");
	}
	mysqli_close($con);*/
/*	if($result33)
	{
		while($data=mysqli_fetch_array($result33,MYSQLI_NUM))
		{
			echo $data[0]." => ".$data[1]." - ".$data[2]."<br>";
		}
		mysqli_free_result($result33);
	}*/
	/*echo "====Doubles====<br>";
	if($resultdoubles)
	{
		while($data=mysqli_fetch_array($resultdoubles,MYSQLI_NUM))
		{
			$con=dbconnecti();
			$reset99=mysqli_query($con,"UPDATE Joueur SET Parrain='".$data[9]."' WHERE ID='".$data[0]."'");
			mysqli_close($con);
			echo $data[0]." => (".$data[1]." - ".$data[2].") - (".$data[3]." - ".$data[4].") - (".$data[5]." - ".$data[6].") - (".$data[7]." - ".$data[8].")<br>";
		}
		mysqli_free_result($resultdoubles);
	}*/
	echo "<h2>Tests</h2>";
	$Front = 0;
    $Coord=GetCoord($Front);
    $Lat_base_min=$Coord[0];
    $Lat_base_max=$Coord[1];
    $Long_base_min=$Coord[2];
    $Long_base_max=$Coord[3];
	dbconnect();
	$result = $dbh->query("SELECT COUNT(*),Meteo,(SELECT COUNT(*) FROM Lieu WHERE (Latitude BETWEEN $Lat_base_min AND $Lat_base_max) AND (Longitude BETWEEN $Long_base_min AND $Long_base_max)) AS Total 
    FROM Lieu WHERE (Latitude BETWEEN $Lat_base_min AND $Lat_base_max) AND (Longitude BETWEEN $Long_base_min AND $Long_base_max) GROUP BY Meteo");
	while($data = $result->fetch(PDO::FETCH_BOTH)){
	    echo $data[0].' ('.round(100/($data['Total']/$data[0]),2).'% sur '.$data['Total'].') <img src="images/meteo'.$data['Meteo'].'.gif"><br>';
    }
	/*include_once('./jfv_nav.inc.php');
	$con=dbconnecti();
	$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	$resultl=mysqli_query($con,"SELECT ID,Nom,Latitude,Longitude FROM Lieu");
	mysqli_close($con);
	if($resultl)
	{
		$Saison=GetSaison($Date_Campagne);
		while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
		{ 
			$ID=$datal['ID'];
			$Meteo_ar=GetMeteo($Saison,$datal['Latitude'],$datal['Longitude']);
			$Meteo=$Meteo_ar[1];
			$con=dbconnecti();
			$upmeteo=mysqli_query($con,"UPDATE Lieu SET Meteo='$Meteo',Meteo_Hour=7 WHERE ID='$ID'");
			mysqli_close($con);
			echo "<br>".$datal['Nom']." -> ".$Meteo_ar[0]." (".$Meteo_ar[1].")";
			unset($Meteo_ar);
		}
		mysqli_free_result($resultl);
		unset($data);
	}*/
	/* Skills Pilotes
	$con=dbconnecti();
	$resultl=mysqli_query($con,"SELECT p.ID,s.Categorie,s.Rang,p.Nom,p.Skill_Cat FROM Skills_Pil as sp,Skills as s,Pilote as p WHERE sp.Skill=s.ID AND sp.PlayerID=p.ID AND s.Categorie >0 AND p.Skill_Cat >0");
	if($resultl)
	{
		while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
		{ 
			if($datal['Categorie'] ==$datal['Skill_Cat'])
				$Mult=1;
			else
				$Mult=2;
			$Total_Pts=$datal['Rang']*$Mult;
			$reset=mysqli_query($con,"UPDATE Pilote SET Skill_Pts=Skill_Pts+'$Total_Pts' WHERE ID=".$datal['Skill_Cat']."");
			mysqli_free_result($reset);
			echo "<br>".$datal['Nom']." a vu son total augmenté de ".$Total_Pts;
		}
		mysqli_free_result($resultl);
		unset($data);
	}
	mysqli_close($con);*/
						/*if($Admin){
							echo"<pre>";
							print_r(get_defined_vars());
							echo"</pre>";
						}*/
}
else
	echo "<h1>Vous n'avez pas accès à cette page!</h1>";
?>