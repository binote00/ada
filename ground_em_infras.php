<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_EM or $Admin)
	{
		/*if($country ==1)
			$QFlag_txt="Flag IN (1,15,18,19,20)";
		elseif($country ==2)
			$QFlag_txt="Flag IN (2,3,5,10,35)";
		elseif($country ==6)
			$QFlag_txt="Flag IN (6,24)";
		elseif($country ==7)
			$QFlag_txt="Flag IN (4,7)";
		elseif($country ==8)
			$QFlag_txt="Flag IN (8,17)";
		else*/
			$QFlag_txt="Flag='$country'";		
		if($Front ==99)
			$query="SELECT DISTINCT ID,Nom,Flag FROM Lieu WHERE ".$QFlag_txt." AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==3)
			$query="SELECT DISTINCT ID,Nom,Flag FROM Lieu WHERE ".$QFlag_txt." AND Longitude >67 AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==2)
			$query="SELECT DISTINCT ID,Nom,Flag FROM Lieu WHERE ".$QFlag_txt." AND Latitude <43 AND Longitude <50 AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==1)
			$query="SELECT DISTINCT ID,Nom,Flag FROM Lieu WHERE ".$QFlag_txt." AND Latitude >41 AND Latitude <=50.5 AND Longitude >13 AND Longitude <67 AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==4)
			$query="SELECT DISTINCT ID,Nom,Flag FROM Lieu WHERE ".$QFlag_txt." AND Latitude >50.5 AND Longitude >13 AND Longitude <67 AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==5)
			$query="SELECT DISTINCT ID,Nom,Flag FROM Lieu WHERE ".$QFlag_txt." AND Latitude >60 AND Longitude >-50 AND Longitude <60 AND Zone<>6 ORDER BY Nom ASC";
		else
		{
			if($country ==7)
				$query="SELECT DISTINCT ID,Nom,Flag FROM Lieu WHERE ".$QFlag_txt." AND Latitude <60 AND Longitude <14 AND Zone<>6 ORDER BY Nom ASC";
			else
				$query="SELECT DISTINCT ID,Nom,Flag FROM Lieu WHERE ".$QFlag_txt." AND Latitude >=43 AND Latitude <60 AND Longitude <14 AND Zone<>6 ORDER BY Nom ASC";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
			{
				if($data[2]==$country)
					$Lieux.="<option value='".$data[0]."'>".$data[1]."</option>";
				else
					$Lieux_allies.="<option value='".$data[0]."'>".$data[1]."</option>";
			}
			mysqli_free_result($result);
		}
		echo "<h1>Etat-Major</h1><h2>Gestion des infrastructures</h2>
			<div class='row'><div class='col-md-6'><form action='index.php?view=ground_em_infras0' method='post'><input type='hidden' name='mode' value='1'>
			<table class='table'><thead><tr><th>Lieux contrôlés par votre nation</th></tr></thead>
			<tr><td align='left'><select name='lieu' class='form-control' style='width: 200px'>".$Lieux."
			</select></td></tr><tr><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Gestion des infrastructures, de la DCA et des garnisons</span></a></td></tr></table></form></div>";
		if($Lieux_allies)
			echo "<div class='row'><div class='col-md-6'><form action='index.php?view=ground_em_infras0' method='post'><input type='hidden' name='mode' value='2'>
			<table class='table'><thead><tr><th>Lieux contrôlés par vos alliés</th></tr></thead>
			<tr><td align='left'><select name='lieu' class='form-control' style='width: 200px'>".$Lieux_allies."
			</select></td></tr><tr><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Gestion des infrastructures uniquement</span></a></td></tr></table></form></div></div>";
		else
			echo '</div>';
	}
	else
		PrintNoAccess($country,1,3);
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';