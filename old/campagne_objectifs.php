<?
require_once('./jfv_inc_sessions.php');
if(1==2)
{
	include_once('./jfv_inc_const.php');
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_actus.php');
	$PlayerID = Insec($_SESSION['PlayerID']);
	$country = Insec($_SESSION['country']);
	echo "<img src='images/event2455.jpg' border='1'>";
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
		AND Lieu.ID IN(139,224,329,592) AND Attaque.Date >= '2013-03-09') AS Camions,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(58,60,62,78,79) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(139,224,329,592) AND Attaque.Date >= '2013-03-09') AS HT3,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(19,22,57,64,77,80,81,82,84) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(139,224,329,592) AND Attaque.Date >= '2013-03-09') AS HT5,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(23,27,28,43) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(139,224,329,592) AND Attaque.Date >= '2013-03-09') AS Blinde10,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(29,31,32,37,38,39,40,41,44,45,46,47) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(139,224,329,592) AND Attaque.Date >= '2013-03-09') AS Blinde15,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(30,42) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(139,224,329,592) AND Attaque.Date >= '2013-03-09') AS Blinde20,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=13 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(139,329) AND Attaque.Date >= '2013-03-09') AS Loco,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=6 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(139,224,329,592) AND Attaque.Date >= '2013-03-09') AS Canon,	
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=12 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (139,224,225,239,326,329,592,898) AND Attaque.Date >= '2013-03-09') AS DCA1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=12 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (139,224,225,239,326,329,592,898) AND Bombardement.Date >= '2013-03-09') AS DCA2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=29 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (139,224,329,592) AND Attaque.Date >= '2013-03-09') AS Pont1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=29 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (139,224,329,592) AND Bombardement.Date >= '2013-03-09') AS Pont2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=36 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (224,898) AND Attaque.Date >= '2013-03-09') AS Quais1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=25 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (224,898) AND Attaque.Date >= '2013-03-09') AS Entrepot1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=31 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (224,898) AND Attaque.Date >= '2013-03-09') AS Carbu1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=28 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (139,329) AND Attaque.Date >= '2013-03-09') AS Gare1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=25 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (224,898) AND Bombardement.Date >= '2013-03-09') AS Entrepot2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=36 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (224,898) AND Bombardement.Date >= '2013-03-09') AS Quais2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=31 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (224,898) AND Bombardement.Date >= '2013-03-09') AS Carbu2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=28 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (139,329) AND Bombardement.Date >= '2013-03-09') AS Gare2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Cible_id=14 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (224,225,239,326,329) AND Bombardement.Date >= '2013-03-09') AS Radar,
		(SELECT DCA1+DCA2) AS DCA,
		(SELECT Entrepot1+Entrepot2) AS Entrepot,
		(SELECT Pont1+Pont2) AS Pont,
		(SELECT Quais1+Quais2) AS Quais,
		(SELECT Carbu1+Carbu2) AS Carbu,
		(SELECT Gare1+Gare2) AS Gare,
		(SELECT (HT3*3)+(HT5*5)+(Blinde10*10)+(Blinde15*15)+(Blinde20*20)+(Canon*3)+(DCA*10)+(Loco*10)+(Entrepot*3)+(Quais*30)+(Gare*15)+(Pont*25)+(Carbu*5)+(Radar*30)+Camions) AS Points
		FROM Joueur WHERE Victoires_atk > 0 AND Actif=0 ORDER BY Points DESC LIMIT 50";
		$result=mysqli_query($con, $query);
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
					$Avion_unit_img = "images/unit/unit".$data['Unit']."p.gif";
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
							<td>".$data['Radar']."</td>
						</tr>";
				}
			}
		}
	?>
	<div>
	<h2>Unternehmen Drachenhöhle</h2>
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
			<th>Radar</th>
		</tr>

	<?
		echo $tableau.'<tr bgcolor="lightyellow"><th colspan=\'4\'>Total</th><th>'.$Allie_score.'</th></tr></table>';
	}
	else
	{ 
		echo "<p><a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=185&t=5324' target='_blank'>Plan de Bataille Allié (lien vers le forum privé Royal Air Force)</a></p>";
		//echo "<p><a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=131&t=5305' target='_blank'>Plan de Bataille Allié (lien vers le forum privé Royal Air Force)</a></p>";

		$Allie_score = 0;
		$img = "<img src='images/objectifs.jpg' border='1'>";
		
		$con = dbconnecti();
		/*Rhubarb & Circus
		$query="SELECT ID,Nom,Pays,Pays_Origine,Unit,Avancement,Victoires_atk, 
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type IN(1,2) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(55,62,133,138,140,142,163,164,167,210,261,263,575,641) AND Attaque.Date >= '2013-03-09') AS Camions,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(58,60,62,78,79) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(55,62,133,138,140,142,163,164,167,210,261,263,575,641) AND Attaque.Date >= '2013-03-09') AS HT3,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(19,22,57,64,77,80,81,82,84) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(55,62,133,138,140,142,163,164,167,210,261,263,575,641) AND Attaque.Date >= '2013-03-09') AS HT5,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(23,27,28,43) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(55,62,133,138,140,142,163,164,167,210,261,263,575,641) AND Attaque.Date >= '2013-03-09') AS Blinde10,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(29,31,32,37,38,39,40,41,44,45,46,47) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(55,62,133,138,140,142,163,164,167,210,261,263,575,641) AND Attaque.Date >= '2013-03-09') AS Blinde15,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(30,42) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(55,62,133,138,140,142,163,164,167,210,261,263,575,641) AND Attaque.Date >= '2013-03-09') AS Blinde20,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=13 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(55,62,133,138,140,142,163,164,167,210,261,263,575,641) AND Attaque.Date >= '2013-03-09') AS Loco,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=6 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(55,62,133,138,140,142,163,164,167,210,261,263,575,641) AND Attaque.Date >= '2013-03-09') AS Canon,	
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=12 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (62,96,118,120,128,133,142,167,168,210,264,378,580,668,751) AND Attaque.Date >= '2013-03-09') AS DCA1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=12 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (62,96,118,120,128,133,142,167,168,210,264,378,580,668,751) AND Bombardement.Date >= '2013-03-09') AS DCA2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=29 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (62,133,210,580,751) AND Attaque.Date >= '2013-03-09') AS Pont1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=29 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (62,133,210,580,751) AND Bombardement.Date >= '2013-03-09') AS Pont2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=36 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (96,118,120,128,142,167,168,264,378,668) AND Attaque.Date >= '2013-03-09') AS Quais1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=25 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (96,118,120,128,142,167,168,264,378,668) AND Attaque.Date >= '2013-03-09') AS Entrepot1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=31 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (96,118,120,128,142,167,168,264,378,668) AND Attaque.Date >= '2013-03-09') AS Carbu1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=25 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (96,118,120,128,142,167,168,264,378,668) AND Bombardement.Date >= '2013-03-09') AS Entrepot2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=36 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (96,118,120,128,142,167,168,264,378,668) AND Bombardement.Date >= '2013-03-09') AS Quais2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=31 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (96,118,120,128,142,167,168,264,378,668) AND Bombardement.Date >= '2013-03-09') AS Carbu2,	
		(SELECT DCA1+DCA2) AS DCA,
		(SELECT Entrepot1+Entrepot2) AS Entrepot,
		(SELECT Pont1+Pont2) AS Pont,
		(SELECT Quais1+Quais2) AS Quais,
		(SELECT Carbu1+Carbu2) AS Carbu,
		(SELECT (HT3*3)+(HT5*5)+(Blinde10*10)+(Blinde15*15)+(Blinde20*20)+(Canon*3)+(DCA*10)+(Loco*10)+(Entrepot*3)+(Quais*30)+(Pont*25)+(Carbu*5)+Camions) AS Points
		FROM Joueur WHERE Victoires_atk > 0 AND Actif=0 ORDER BY Points DESC LIMIT 50";*/
		$query="SELECT ID,Nom,Pays,Pays_Origine,Unit,Avancement,Victoires_atk, 
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=12 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (77,216,265,345,346) AND Attaque.Date >= '2013-04-09') AS DCA1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=12 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (77,216,265,345,346) AND Bombardement.Date >= '2013-04-09') AS DCA2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=36 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (77,216,265,345,346) AND Attaque.Date >= '2013-04-09') AS Quais1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=25 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (77,216,265,345,346) AND Attaque.Date >= '2013-04-09') AS Entrepot1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=31 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN (77,216,265,345,346) AND Attaque.Date >= '2013-04-09') AS Carbu1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=25 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (77,216,265,345,346) AND Bombardement.Date >= '2013-04-09') AS Entrepot2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=36 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (77,216,265,345,346) AND Bombardement.Date >= '2013-04-09') AS Quais2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=31 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN (77,216,265,345,346) AND Bombardement.Date >= '2013-04-09') AS Carbu2,	
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=37 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(493,496,497,1141,1153,1155,1156,1157) AND Attaque.Date >= '2013-04-09') AS Sub,
		(SELECT DCA1+DCA2) AS DCA,
		(SELECT Entrepot1+Entrepot2) AS Entrepot,
		(SELECT Quais1+Quais2) AS Quais,
		(SELECT Carbu1+Carbu2) AS Carbu,
		(SELECT (DCA*10)+(Entrepot*3)+(Quais*30)+(Carbu*5)+(Sub*20)) AS Points
		FROM Joueur WHERE Victoires_atk > 0 AND Actif=0 ORDER BY Points DESC LIMIT 50";
		$result = mysqli_query($con, $query);
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$Points = $data['Points'];
				if($Points)
				{
					$ID = $data['ID'];
					$Unite = GetData('Unit','ID',$data['Unit'],'Nom');
					$Grade = GetAvancement($data['Avancement'],$data['Pays_Origine']);				
					$Avion_unit_img = "images/unit/unit".$data['Unit']."p.gif";
					if(is_file($Avion_unit_img))
						$Unite = "<img src='".$Avion_unit_img."' title='".$Unite."'>";				
					$Allie_score += $Points;
					
					$tableau .= "<tr>
							<td>".$data['Nom']."</td>
							<td>".$Unite."</td>
							<td><img title='".$Grade[0]."' src='images/grades".$data['Pays_Origine'].$Grade[1].".png'></td>
							<td><img src='".$data['Pays']."20.gif'></td>
							<td bgcolor='LightYellow'>".$Points."</td>
							<td>".$data['DCA']."</td>
							<td>".$data['Entrepot']."</td>
							<td>".$data['Carbu']."</td>
							<td>".$data['Quais']."</td>
							<td>".$data['Sub']."</td>
						</tr>";
					/*$tableau .= "<tr>
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
							<td>".$data['Pont']."</td>
						</tr>";*/
				}
			}
		}
	?>
	<div>
	<h2>Battle of the Bay</h2>
	<table class='table'>
		<tr><th colspan="30" class="TitreBleu_bc">Tableau des Destructions</th></tr>
		<tr bgcolor="#CDBDA7">
			<th>Pilote</th>
			<th>Unité</th>
			<th>Grade</th>
			<th>Pays</th>
			<th>Points</th>
			<!--<th>Camions</th>
			<th>Tractés</th>
			<th>Chenillés</th>
			<th>Blindés Lg</th>
			<th>Blindés My</th>
			<th>Blindés Ld</th>
			<th>Canons</th>-->
			<th>DCA</th>
			<!--<th>Loco</th>-->
			<th>Entrepot</th>
			<th>Carburant</th>
			<th>Docks</th>
			<th>U-boot</th>
			<!--<th>Pont</th>-->
		</tr>

	<?
		echo $tableau.'<tr bgcolor="lightyellow"><th colspan=\'4\'>Total</th><th>'.$Allie_score.'</th></tr></table>';
	}
	/*Check Joueur Valide
	//if(isset($_SESSION['login']) AND isset($_SESSION['pwd']))
	if(1==1)
	{
		$PlayerID = Insec($_SESSION['PlayerID']);
		$country = Insec($_SESSION['country']);
		$img2 = "<img src='images/objectifs.jpg' border='1'>";
		//$img = "<img src='images/chain_home.jpg' border='1'>";
		
		$con = dbconnecti();
		//Octobre 1940
		$result =mysqli_query($con, "SELECT ID,Nom,BaseAerienne,QualitePiste,Industrie,TypeIndus,NoeudF,Pont,Recce FROM Lieu WHERE Lieu.ID =139");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result)) 
			{
				$Nom = '<b>'.$data['Nom'].'</b>';
				if(!$data['Industrie'] and $data['TypeIndus'])
				{
					$Indus = "<font color='red'>Détruite</font>";
				}
				elseif($data['Industrie'] == 100 and $data['TypeIndus'])
				{
					$Indus = "<b>Intacte</b>";
				}
				elseif($data['Industrie'] < 100 and $data['TypeIndus'])
				{
					$Indus = "<font color='red'>".$data['Industrie']."%</font>";
				}
				if(!$data['NoeudF'])
				{
					$NoeudF = "<font color='red'>Détruite</font>";
				}
				elseif($data['NoeudF'] == 100)
				{
					$NoeudF = "<b>Intacte</b>";
				}
				elseif($data['NoeudF'] < 100)
				{
					$NoeudF = "<font color='red'>".$data['NoeudF']."%</font>";
				}
				if(!$data['Pont'])
				{
					$Pont = "<font color='red'>Détruit</font>";
				}
				elseif($data['Pont'] == 100)
				{
					$Pont = "<b>Intact</b>";
				}
				elseif($data['Pont'] < 100)
				{
					$Pont = "<font color='red'>".$data['Pont']."%</font>";
				}
				if($data['BaseAerienne'])
					$Piste = "<img src='images/piste".GetQualitePiste_img($data['QualitePiste']).".jpg' title='Etat ".$data['QualitePiste']."%'>";
				else
					$Piste = "N/A";
				$txt .= "<tr><td align='left'>".$Nom."</td><td>".$NoeudF."</td><td>".$Pont."</td><td>".$Piste."</td><td>".$Indus."</td></tr>";		
			}
		}

		/* Aout 40
		$result =mysqli_query($con, "SELECT ID,Nom,BaseAerienne,QualitePiste,Radar,Radar_Ori,Recce
		FROM Lieu WHERE Lieu.ID IN(139,226,227,228,230,231,232,233,234,235,237,238,287,288,290,291,299,300,304,307,308,311) OR (Pays=2 AND Radar_Ori=100) ORDER BY Nom ASC");*/
		/* Septembre 40
		$result =mysqli_query($con, "SELECT ID,Nom,BaseAerienne,QualitePiste,Industrie,TypeIndus,Recce
		FROM Lieu WHERE Lieu.ID IN(139,226,227,228,230,231,232,233,234,235,237,238,287,288,290,291,299,300,301,302,304,305,307,308,309,310,311,651,730,732,741,898) OR (Pays=2 AND TypeIndus<>'') ORDER BY Nom ASC");
		mysqli_close($con);
		if($result)
		{
				while($data=mysqli_fetch_array($result)) 
				{
					if($country == 2 or $Renseignement > 100 or $data['Recce'] > 0)
					{
						$Nom = '<b>'.$data['Nom'].'</b>';
						switch($data['ID'])
						{
							case 139: case 226: case 227: case 230: case 231: case 233: case 234: case 235: case 238: case 287: case 291: case 299: case 300: case 308:
								$Valeur = 2;
							break;
							default:
								$Valeur = 1;
							break;
						}
						if(!$data['Industrie'] and $data['TypeIndus'])
						{
							$Radar = "<font color='red'>Détruite</font>";
						}
						elseif($data['Industrie'] == 100 and $data['TypeIndus'])
						{
							$Radar = "<b>Intacte</b>";
						}
						elseif($data['Industrie'] < 100 and $data['TypeIndus'])
						{
							$Radar = "<font color='red'>".$data['Industrie']."%</font>";
						}
						else
						{
							$Radar = "N/A";
						}
						/*if(!$data['Radar'] and $data['Radar_Ori'])
						{
							$Radar = "<font color='red'>Détruit</font>";
						}
						elseif($data['Radar'] == 100 and $data['Radar_Ori'])
						{
							$Radar = "<b>Intact</b>";
						}
						elseif($data['Radar'] < 100 and $data['Radar_Ori'])
						{
							$Radar = "<font color='red'>".$data['Radar']."%</font>";
						}
						else
						{
							$Radar = "N/A";
						}*/
	/*					if($data['BaseAerienne'])
							$Piste = "<img src='images/piste".GetQualitePiste_img($data['QualitePiste']).".jpg' title='Etat ".$data['QualitePiste']."%'>";
						else
							$Piste = "N/A";
					}
					else
					{
						$Nom = "Inconnu";
						$Valeur = "Inconnu";
						$Piste = "Inconnu";
						$Radar = "Inconnu";
					}
					$txt .= "<tr><td align='left'>".$Nom."</td><td>".$Valeur."</td><td>".$Piste."</td><td>".$Radar."</td></tr>";		
				}
		}*/
		/*mysqli_free_result($result);
		unset($data);
		
		/*$bomb_tot = 0;
		$con = dbconnecti();
		//$resultm=mysqli_query($con, "SELECT COUNT(*),Chasse.Avion_loss,Unit.Pays FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Unit.Type IN (2,7) AND Chasse.Date > '2012-12-01' AND Chasse.Latitude > 48.5 GROUP BY Chasse.Avion_loss ORDER BY COUNT(*) DESC");
		$resultm=mysqli_query($con, "SELECT COUNT(*),Chasse.Avion_loss,Unit.Pays FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Unit.Type IN (2,7) AND Chasse.Date > '2012-12-31' AND Chasse.Latitude > 48.5 GROUP BY Chasse.Avion_loss ORDER BY COUNT(*) DESC");
		mysqli_close($con);
		if($resultm)
		{
			while($data=mysqli_fetch_array($resultm))
			{
				$bombs .= '<tr><td>'.GetAvionIcon($data[1],$data[2]).'</td><td>'.$data[0].'</td><td></td></tr>';
				$bomb_tot += $data[0];
			}
		}
		$con = dbconnecti();
	//	$pvp_nbr=mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Unit.Type IN (2,7) AND Chasse.Date > '2012-12-01' AND Chasse.Latitude > 48.5 AND Chasse.PVP > 1 ORDER BY COUNT(*) DESC"),0);
		$pvp_nbr=mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Unit.Type IN (2,7) AND Chasse.Date > '2013-01-01' AND Chasse.Latitude > 48.5 AND Chasse.PVP > 1 ORDER BY COUNT(*) DESC"),0);
		mysqli_close($con);*/
		
		/*$chass_tot = 0;
		$con = dbconnecti();
		//$resultm=mysqli_query($con, "SELECT COUNT(*),Chasse.Avion_loss,Unit.Pays FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Unit.Type IN (2,7) AND Chasse.Date > '2012-12-01' AND Chasse.Latitude > 48.5 GROUP BY Chasse.Avion_loss ORDER BY COUNT(*) DESC");
		//$resultm=mysqli_query($con, "SELECT COUNT(*),Chasse.Avion_loss,Unit.Pays FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Unit.Type IN (1,4) AND Chasse.Date > '2013-01-01' AND Chasse.Latitude > 48.5 GROUP BY Chasse.Avion_loss ORDER BY COUNT(*) DESC");
		$resultm=mysqli_query($con, "SELECT COUNT(*),Chasse.Avion_loss,Unit.Pays FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Chasse.Date > '2013-01-30' AND Chasse.Lieu=139 GROUP BY Chasse.Avion_loss ORDER BY COUNT(*) DESC");
		mysqli_close($con);
		if($resultm)
		{
			while($data=mysqli_fetch_array($resultm))
			{
				$chasseurs .= '<tr><td>'.GetAvionIcon($data[1],$data[2]).'</td><td>'.$data[0].'</td><td></td></tr>';
				$chass_tot += $data[0];
			}
		}
		$con = dbconnecti();
	//	$pvp_nbr=mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Unit.Type IN (2,7) AND Chasse.Date > '2012-12-01' AND Chasse.Latitude > 48.5 AND Chasse.PVP > 1 ORDER BY COUNT(*) DESC"),0);
	//	$pvp3_nbr=mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Unit.Type IN (1,4) AND Chasse.Date > '2013-01-01' AND Chasse.Latitude > 48.5 AND Chasse.PVP > 1 ORDER BY COUNT(*) DESC"),0);
		$pvp3_nbr=mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays IN (1,6) AND Chasse.Date > '2013-01-30' AND Chasse.Lieu=139 AND Chasse.PVP > 1 ORDER BY COUNT(*) DESC"),0);
		mysqli_close($con);

		//2e faction
		$chass2_tot = 0;
		$con = dbconnecti();
	//	$resultm=mysqli_query($con, "SELECT COUNT(*),Chasse.Avion_loss,Unit.Pays FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays=2 AND Unit.Type=1 AND Chasse.Date > '2013-01-01' AND Chasse.Latitude > 48.5 GROUP BY Chasse.Avion_loss ORDER BY COUNT(*) DESC");
		$resultm=mysqli_query($con, "SELECT COUNT(*),Chasse.Avion_loss,Unit.Pays FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays=2 AND Unit.Type IN(1,4) AND Chasse.Date > '2013-01-30' AND Chasse.Lieu=139 GROUP BY Chasse.Avion_loss ORDER BY COUNT(*) DESC");
		mysqli_close($con);
		if($resultm)
		{
			while($data=mysqli_fetch_array($resultm))
			{
				$chasseurs2 .= '<tr><td>'.GetAvionIcon($data[1],$data[2]).'</td><td>'.$data[0].'</td><td></td></tr>';
				$chass2_tot += $data[0];
			}
		}
		$con = dbconnecti();
	//	$pvp2_nbr=mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays=2 AND Unit.Type=1 AND Chasse.Date > '2013-01-01' AND Chasse.Latitude > 48.5 AND Chasse.PVP > 1 ORDER BY COUNT(*) DESC"),0);
		$pvp2_nbr=mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse,Unit WHERE Chasse.Unite_loss=Unit.ID AND Unit.Pays=2 AND Unit.Type IN(1,4) AND Chasse.Date > '2013-01-30' AND Chasse.Lieu=139 AND Chasse.PVP > 1 ORDER BY COUNT(*) DESC"),0);
		mysqli_close($con);
		
		echo"<div><table align='center' border='0' bgcolor='#ECDDC1'>
		<tr><td colspan='3'><h2>Objectifs de Campagne Front Ouest</h2></td></tr></table>";
		echo "<div id='col_gauche'>";
		/*echo "<table align='center' border='0' bgcolor='#ECDDC1'>
		<tr bgcolor='tan'><th align='center' colspan='2'>Bombardiers de l'Axe abattus</th><th>Objectif</th></tr>
		".$bombs."<tr bgcolor='lightyellow'><th>Total</th><th>".$bomb_tot."</th><th>300</th></tr>
		<tr><td>PVP</td><td>".$pvp_nbr."</td><th>5</th></tr>
		</table><hr>";*/
		/*echo"<table align='center' border='0' bgcolor='#ECDDC1'>
		<tr bgcolor='tan'><th align='center' colspan='2'>Avions de l'Axe abattus</th><th>Objectif</th></tr>
		".$chasseurs."<tr bgcolor='lightyellow'><th>Total</th><th>".$chass_tot."</th><th>300</th></tr>
		<tr><td>PVP</td><td>".$pvp3_nbr."</td><th>5</th></tr>
		</table><hr>
		<table align='center' border='0' bgcolor='#ECDDC1'>
		<tr bgcolor='tan'><th align='center' colspan='2'>Chasseurs Alliés abattus</th><th>Objectif</th></tr>
		".$chasseurs2."<tr bgcolor='lightyellow'><th>Total</th><th>".$chass2_tot."</th><th>300</th></tr>
		<tr><td>PVP</td><td>".$pvp2_nbr."</td><th>5</th></tr>
		</table>";
		//echo "<img src='images/cibles5.jpg' border='1'>";
		echo "</div>";
		echo "<div id='col_droite'><table border='0' cellspacing='1' cellpadding='5' bgcolor='#ECDDC1' rules=rows>
			<tr><td rowspan='60'>".$img."</td></tr>
			<tr bgcolor='tan'>
				<th>Lieu</th>
				<th><img src='images/vehicule9.gif' title='Gare'>Gare</th>
				<th>Pont</th>
				<th>Aérodrome</th>
				<th><img src='images/vehicule5.gif' title='Usine'>Usine</th>
			</tr>";
	//			<th><img src='images/vehicule15.gif' title='Radar'>Radar</th>
		echo $txt;
		echo"<tr bgcolor='tan'>
			<th>Lieu</th>
			<th>Gare</th>
			<th>Pont</th>
			<th>Aérodrome</th>
			<th>Usine</th>
			</tr></table></div>";
	}
	else
	{
		echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
	}*/
}
?>