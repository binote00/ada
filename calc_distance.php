<?php
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	include_once('./jfv_include.inc.php');
	$Lieua = Insec($_POST['lieua']);
	$Lieub = Insec($_POST['lieub']);
	if($Lieua and $Lieub)$Distance_calc=GetDistance($Lieua,$Lieub);
	$query="SELECT DISTINCT ID,Nom FROM Lieu ORDER BY Nom ASC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$Lieux.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
		}
		mysqli_free_result($result);
	}
	?>
	<html><head>
		<meta charset="utf-8">
		<meta name="author" content="jf BINOTE">
		<meta name="description" content="L'Aube des Aigles est un jeu de gestion et de stratégie multi-joueurs gratuit par navigateur ayant pour cadre la guerre aérienne pendant la seconde guerre mondiale (1939-1945)">
		<meta name="keywords" content="Jeu, Gratuit, Pilote, Guerre, 1940, mai 1940, Combat aérien, RAF, Royal Air Force, Luftwaffe, Armée de l'air, WW2, 2GM">
		<title>L'Aube des Aigles : Calcul des Distances</title>
		<link rel="icon" type="image/png" href="favicon.png">
		<link href="css/test.css" rel="stylesheet" type="text/css">
	</head><body>
		<form action="calc_distance.php" method="post"><table>
		<tr><th class="TitreBleu_bc">Calculer une distance</th></tr>
		<tr><td><select name="lieua" style="width: 150px"><?echo $Lieux;?></select> Départ</td></tr>
		<tr><td><select name="lieub" style="width: 150px"><?echo $Lieux;?></select> Arrivée</td></tr>
		<tr><td><input type="Submit" value="CALCULER" onclick='this.disabled=true;this.form.submit();'></td></tr>
	<?if($Distance_calc){?>
		<tr><td>De <?echo GetData("Lieu","ID",$Lieua,"Nom").' à '.GetData("Lieu","ID",$Lieub,"Nom").' : '.$Distance_calc[0];?> km</td></tr>
	<?}?>
	</table></form></body></html>
<?}?>