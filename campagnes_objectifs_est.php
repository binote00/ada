<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_inc_const.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_actus.php');

$PlayerID = Insec($_SESSION['PlayerID']);
$country = Insec($_SESSION['country']);
echo "<img src='images/event7316.jpg' border='1'>";
if(!$country)
	echo "<p>Vous devez être connecté pour pouvoir consulter le plan de bataille de votre nation</p>";
elseif($country == 1 or $country == 6)
{
	echo "<p><a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=131&t=5305' target='_blank'>Plan de Bataille de l'Axe (lien vers le forum privé Luftwaffe)</a></p>";

	$Allie_score = 0;
	$img = "<img src='images/objectifs.jpg' border='1'>";
	
	$con = dbconnecti();
	$query="SELECT ID,Nom,Pays,Pays_Origine,Unit,Avancement,Victoires_atk, 
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type IN(1,2) AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN(629) AND Attaque.Date >= '2013-03-09') AS Camions,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(58,60,62,78,79) AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN(629) AND Attaque.Date >= '2013-03-09') AS HT3,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(19,22,57,64,77,80,81,82,84) AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN(629) AND Attaque.Date >= '2013-03-09') AS HT5,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(23,27,28,43) AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN(629) AND Attaque.Date >= '2013-03-09') AS Blinde10,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(29,31,32,37,38,39,40,41,44,45,46,47) AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN(629) AND Attaque.Date >= '2013-03-09') AS Blinde15,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(30,42) AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN(629) AND Attaque.Date >= '2013-03-09') AS Blinde20,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=13 AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN(629) AND Attaque.Date >= '2013-03-09') AS Loco,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=6 AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN(629) AND Attaque.Date >= '2013-03-09') AS Canon,	
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=12 AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Attaque.Date >= '2013-03-09') AS DCA1,
	(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=12 AND Bombardement.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Bombardement.Date >= '2013-03-09') AS DCA2,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=29 AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Attaque.Date >= '2013-03-09') AS Pont1,
	(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=29 AND Bombardement.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Bombardement.Date >= '2013-03-09') AS Pont2,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=36 AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Attaque.Date >= '2013-03-09') AS Quais1,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=25 AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Attaque.Date >= '2013-03-09') AS Entrepot1,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=31 AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Attaque.Date >= '2013-03-09') AS Carbu1,
	(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=28 AND Attaque.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Attaque.Date >= '2013-03-09') AS Gare1,
	(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=25 AND Bombardement.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Bombardement.Date >= '2013-03-09') AS Entrepot2,
	(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=36 AND Bombardement.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Bombardement.Date >= '2013-03-09') AS Quais2,
	(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=31 AND Bombardement.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Bombardement.Date >= '2013-03-09') AS Carbu2,
	(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=28 AND Bombardement.Lieu=Lieu.ID 
	AND Lieu.ID IN (629) AND Bombardement.Date >= '2013-03-09') AS Gare2,
	(SELECT DCA1+DCA2) AS DCA,
	(SELECT Entrepot1+Entrepot2) AS Entrepot,
	(SELECT Pont1+Pont2) AS Pont,
	(SELECT Quais1+Quais2) AS Quais,
	(SELECT Carbu1+Carbu2) AS Carbu,
	(SELECT Gare1+Gare2) AS Gare,
	(SELECT (HT3*3)+(HT5*5)+(Blinde10*10)+(Blinde15*15)+(Blinde20*20)+(Canon*3)+(DCA*10)+(Loco*10)+(Entrepot*3)+(Quais*30)+(Gare*15)+(Pont*25)+(Carbu*5)+Camions) AS Points
	FROM Joueur WHERE Victoires_atk > 0 AND Actif=0 ORDER BY Points DESC LIMIT 50";
	$result = mysqli_query($con, $query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$Points=$data['Points'];
			if($Points)
			{
				$ID=$data['ID'];
				$Unite=GetData('Unit','ID',$data['Unit'],'Nom');
				$Grade=GetAvancement($data['Avancement'],$data['Pays_Origine']);
				
				$Avion_unit_img = "images/unit".$data['Unit']."p.gif";
				if(is_file($Avion_unit_img))
					$Unite = "<img src='".$Avion_unit_img."' title='".$Unite."'>";
				
				$Allie_score += $Points;
				
				$tableau .= "<tr>
						<td>".$data['Nom']."</td>
						<td>".$Unite."</td>
						<td><img title='".$Grade[0]."' src='images/grades".$data['Pays_Origine'].$Grade[1].".png'></td>
						<td><img src='".$data['Pays']."20.gif'></td>
						<td bgcolor='LightYellow'>".$Points."</td>
						<td>".$data['Camions']."</td>
						<td>".$data['HT3']."</td>
						<td>".$data['HT5']."</td>
						<td>".$data['Blinde10']."</td>
						<td>".$data['Blinde15']."</td>
						<td>".$data['Blinde20']."</td>
						<td>".$data['Canon']."</td>
						<td>".$data['DCA']."</td>
						<td>".$data['Loco']."</td>
						<td>".$data['Entrepot']."</td>
						<td>".$data['Carbu']."</td>
						<td>".$data['Quais']."</td>
						<td>".$data['Gare']."</td>
						<td>".$data['Pont']."</td>
					</tr>";
			}
		}
	}
?>
<div>
<h2>Kharkov</h2>
<table class='table'>
	<tr><th colspan="30" class="TitreBleu_bc">Tableau des Destructions</th></tr>
	<tr bgcolor="#CDBDA7">
		<th>Pilote</th>
		<th>Unité</th>
		<th>Grade</th>
		<th>Pays</th>
		<th>Points</th>
		<th>Camions</th>
		<th>Tractés</th>
		<th>Chenillés</th>
		<th>Blindés Lg</th>
		<th>Blindés My</th>
		<th>Blindés Ld</th>
		<th>Canons</th>
		<th>DCA</th>
		<th>Loco</th>
		<th>Entrepot</th>
		<th>Carburant</th>
		<th>Docks</th>
		<th>Gare</th>
		<th>Pont</th>
	</tr>

<?
	echo $tableau.'<tr bgcolor="lightyellow"><th colspan=\'4\'>Total</th><th>'.$Allie_score.'</th></tr></table>';
}
?>