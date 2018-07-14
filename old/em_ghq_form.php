<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $Admin)
	{
		//Lieux
		if($Front ==3)
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Longitude >67 AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==2)
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude <43 AND Longitude <50 AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==1)
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >41 AND Latitude <=50.5 AND Longitude >13 AND Longitude <67 AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==4)
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >50.5 AND Longitude >13 AND Longitude <67 AND Zone<>6 ORDER BY Nom ASC";
		elseif($Front ==5)
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >60 AND Longitude >-50 AND Longitude <60 AND Zone<>6 ORDER BY Nom ASC";
		else
		{
			if($country ==7)
				$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude <60 AND Longitude <14 AND Zone<>6 ORDER BY Nom ASC";
			else
				$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >=43 AND Latitude <60 AND Longitude <14 AND Zone<>6 ORDER BY Nom ASC";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		$resultat=mysqli_query($con,"SELECT DISTINCT ID,Type FROM Avion_Type ORDER BY Type ASC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
			{
				$Lieux.="<option value='".$data[0]."'>".$data[1]."</option>";
			}
			mysqli_free_result($result);
		}
		if($resultat)
		{
			while($datat=mysqli_fetch_array($resultat,MYSQLI_NUM)) 
			{
				$Air_Cat.="<option value='".$datat[0]."'>".$datat[1]."</option>";
			}
			mysqli_free_result($resultat);
		}
		$Air_Units="<select name='air_cat' style='width: 200px'>".$Air_Cat."</select>";
		$Terre_Units="<select name='terre_cat' style='width: 200px'>
						<option value='8'>Artillerie</option>
						<option value='9'>Artillerie anti-char</option>
						<option value='15'>Artillerie anti-aérienne</option>
						<option value='5'>Infanterie</option>
						<option value='1'>Infanterie motorisée</option>
						<option value='6'>Mitrailleuse</option></select>";
		$Mer_Units="<select name='mer_cat' style='width: 200px'>
						<option value='14'>Petit navire</option>
						<option value='15'>Corvette</option>
						<option value='16'>Frégate</option>
						<option value='17'>Destroyer</option>
						<option value='18'>Croiseur léger</option>
						<option value='19'>Croiseur lourd</option>
						<option value='20'>Cuirassé</option>
						<option value='37'>Sous-marin</option></select>";
		$Strat_txt="Objectif à défendre <select name='lieud' class='form-control' style='width: 200px'><option value='0'>Aucun</option>".$Lieux."</select>
		<br>Objectif à attaquer <select name='lieua' class='form-control' style='width: 200px'><option value='0'>Aucun</option>".$Lieux."</select>";
		$Air_txt="Nous avons besoin de plus de ".$Air_Units." pour défendre <select name='lieud_air' style='width: 200px'><option value='0'>Aucun</option>".$Lieux."</select>
		<br>Nous avons besoin de plus de ".$Air_Units." pour attaquer <select name='lieua_air' style='width: 200px'><option value='0'>Aucun</option>".$Lieux."</select>";
		$Terre_txt="Nous avons besoin de plus de ".$Terre_Units." pour défendre <select name='lieud_air' style='width: 200px'><option value='0'>Aucun</option>".$Lieux."</select>
		<br>Nous avons besoin de plus de ".$Terre_Units." pour attaquer <select name='lieua_air' style='width: 200px'><option value='0'>Aucun</option>".$Lieux."</select>";
		$Mer_txt="Nous avons besoin de plus de ".$Mer_Units." pour défendre <select name='lieud_air' style='width: 200px'><option value='0'>Aucun</option>".$Lieux."</select>
		<br>Nous avons besoin de plus de ".$Mer_Units." pour attaquer <select name='lieua_air' style='width: 200px'><option value='0'>Aucun</option>".$Lieux."</select>";
		echo "<h2>Stratégie</h2><form>".$Strat_txt."<h2>Demande</h2><h3>Unités aériennes</h3>".$Air_txt."<h3>Troupes terrestres</h3>".$Terre_txt."<h3>Marine</h3>".$Mer_txt."</form>";
	}
}
?>