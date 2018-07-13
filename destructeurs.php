<?
include_once('./jfv_include.inc.php');
$PlayerID = $_SESSION['PlayerID'];
if($PlayerID >0)
{
	$i=0;
	include_once('./jfv_txt.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$Tri = Insec($_POST['Tri']);
		if(!$Tri)$Tri=1;
		echo "<h1>Tableau des Destructeurs</h1><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
			<thead><tr>
				<th>N°</th>
				<th>Pilote</th>
				<th>Unité</th>
				<th>Grade</th>
				<th>Pays</th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='1'><input title='Points' type='Submit' value='Points'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='2'><input title='Camions et Half-Tracks non armés' type='Submit' value='Camions'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='3'><input title='Voitures blindées, Half-Tracks armés et Blindés légers' type='Submit' value='Véhicules'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='4'><input title='Blindés moyens et lourds, Artillerie automotrice et Chasseurs de chars' type='Submit' value='Blindés'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='5'><input title='Artillerie tractée ou Canons anti-chars' type='Submit' value='Canons'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='6'><input title='Canons de D.C.A détruits' type='Submit' value='D.C.A'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='7'><input title='Locomotives détruites' type='Submit' value='Trains'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='8'><input title='Navires coulés' type='Submit' value='Navires'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='9'><input title='Avions détruits au sol' type='Submit' value='Avions'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='10'><input title='Bâtiments secondaires détruits' type='Submit' value='Bâtiments'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='11'><input title='Bâtiments principaux détruits' type='Submit' value='Usines'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='12'><input title='Ponts détruits' type='Submit' value='Ponts'></form></th>
				<th><form action='index.php?view=destructeurs' method='post'><input type='hidden' name='Tri' value='13'><input title='Pistes endommagées' type='Submit' value='Pistes'></form></th>
				<th title='Avion le plus souvent utilisé par le pilote'>Avion</th></tr></thead>";
		switch($Tri)
		{
			case 1:
				$Tri="Victoires_atk";
			break;
			case 2:
				$Tri="Camions";
			break;
			case 3:
				$Tri="HT";
			break;
			case 4:
				$Tri="Blinde";
			break;
			case 5:
				$Tri="Canon";
			break;
			case 6:
				$Tri="DCA";
			break;
			case 7:
				$Tri="Loco";
			break;
			case 9:
				$Tri="Avion_Sol";
			break;
			case 8:
				$Tri="Navire";
			break;
			case 10:
				$Tri="Batiments";
			break;
			case 11:
				$Tri="Usines";
			break;
			case 12:
				$Tri="Pont";
			break;
			case 13:
				$Tri="Piste";
			break;
			case 14:
				$Tri="Carbu";
			break;
			case 15:
				$Tri="Points";
			break;
		}
			$query="SELECT ID,Nom,Pays,Unit,Avancement,Victoires_atk, 
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type IN (1,2)) AS Camions,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type IN (3,4,5,11)) AS HT,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type IN (7,8,9,10)) AS Blinde,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=6) AS Canon1, 
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=6) AS Canon2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=12) AS DCA1, 
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=12) AS DCA2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=13) AS Loco, 
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=17) AS Destroyer, 
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=16) AS Fregate, 
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=15) AS Corvette, 
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=14) AS Cargo, 
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=22) AS Avion_sol, 
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type IN (14,15,16,17,18,19,20,21)) AS Navire,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type IN (25,26,27,28,31,34)) AS Batiments1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type IN (25,26,27,28,31,34)) AS Batiments2, 
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=25) AS Entrepot1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=25) AS Entrepot2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=26) AS Tours1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=26) AS Tours2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=27) AS Usines1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=27) AS Usines2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=28) AS Gare1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=28) AS Gare2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=29) AS Pont1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=29) AS Pont2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=30) AS Piste1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=30) AS Piste2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=34) AS Caserne1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=34) AS Caserne2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=35) AS Radar1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=35) AS Radar2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=36) AS Quais1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=36) AS Quais2,
			(SELECT COUNT(*) FROM Attaque WHERE Attaque.Joueur=Pilote.ID AND Attaque.Type=31) AS Carbu1,
			(SELECT COUNT(*) FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID AND Bombardement.Type=31) AS Carbu2,
			(SELECT Avion FROM Attaque WHERE Attaque.Joueur=Pilote.ID ORDER BY COUNT(Avion) DESC LIMIT 1) AS Aviona,
			(SELECT Avion FROM Bombardement WHERE Bombardement.Joueur=Pilote.ID GROUP BY Avion ORDER BY COUNT(Avion) DESC LIMIT 1) AS Avionb,
			(SELECT Canon1+Canon2) AS Canon,
			(SELECT DCA1+DCA2) AS DCA,
			(SELECT Batiments1+Batiments2) AS Batiments,
			(SELECT Caserne1+Caserne2) AS Caserne,
			(SELECT Entrepot1+Entrepot2) AS Entrepot,
			(SELECT Tours1+Tours2) AS Tours,
			(SELECT Gare1+Gare2) AS Gare,
			(SELECT Usines1+Usines2) AS Usines,
			(SELECT Pont1+Pont2) AS Pont,
			(SELECT Piste1+Piste2) AS Piste,
			(SELECT Radar1+Radar2) AS Radar,
			(SELECT Quais1+Quais2) AS Quais,
			(SELECT Carbu1+Carbu2) AS Carbu,
			(SELECT (HT*5)+(Blinde*20)+(Canon*3)+(DCA*10)+(Loco*10)+(Avion_sol*2)+(Entrepot*3)+(Usines*30)+(Quais*30)+(Radar*30)+(Pont*25)+(Gare*15)+(Piste*15)+(Tours*10)+(Caserne*5)+(Carbu*5)+Camions+(Cargo*2)+(Corvette*10)+(Fregate*15)+(Destroyer*25)) AS Points
			FROM Pilote WHERE Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() AND Victoires_atk >0 AND Actif=0 ORDER BY $Tri DESC LIMIT 100";
		//$query="SELECT ID,Nom,Pays,Unit,Avancement,Victoires_atk FROM Pilote WHERE Victoires_atk >0 ORDER BY Victoires_atk DESC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
					$ID = $data['ID'];
					$Points = $data['Victoires_atk'];
					$Pilote = $data['Nom'];
					$Pays = $data['Pays'];
					$Camions = $data['Camions'];
					$HT = $data['HT'];
					$Blinde = $data['Blinde'];
					$Canon = $data['Canon'];
					$DCA = $data['DCA'];
					$Loco = $data['Loco'];
					$Avion_sol = $data['Avion_sol'];
					$Navire = $data['Navire'];
					$Batiments = $data['Batiments'];
					$Usines = $data['Usines'];
					$Pont = $data['Pont'];
					$Piste = $data['Piste'];
					$Carbu = $data['Carbu'];
					//$Points = $data['Points'];
					$Grade=GetAvancement($data['Avancement'],$Pays);
					$Unite=GetData('Unit','ID',$data['Unit'],'Nom');
					if($data['Avionb'])
						$Avion = GetAvionIcon($data['Avionb'],$Pays);
					elseif($data['Aviona'])
						$Avion = GetAvionIcon($data['Aviona'],$Pays);
					else
						$Avion="";				
					$Avion_unit_img="images/unit/unit".$data['Unit']."p.gif";
					if(is_file($Avion_unit_img))
						$Unite="<img src='".$Avion_unit_img."' title='".$Unite."'>";						
					if($PlayerID >0 and $ID == $PlayerID)
						echo "<tr bgcolor='LightYellow'>";
					else
						echo "<tr>";
					$i++;			
			?>
						<td><? echo $i;?></td>
						<td><a href="user_public.php?Pilote=<?echo $ID;?>" target="_blank" class='lien'><? echo $Pilote;?></a></td>
						<td><? echo $Unite;?></td>
						<td><img title="<?echo $Grade[0];?>" src="images/grades/grades<? echo $Pays.$Grade[1]; ?>.png"></td>
						<td><img src='<? echo $Pays;?>20.gif'></td>
						<td><? echo $Points;?></td>
						<td><? echo $Camions;?></td>
						<td><? echo $HT;?></td>
						<td><? echo $Blinde;?></td>
						<td><? echo $Canon;?></td>
						<td><? echo $DCA;?></td>
						<td><? echo $Loco;?></td>
						<td><? echo $Navire;?></td>
						<td><? echo $Avion_sol;?></td>
						<td><? echo $Batiments;?></td>
						<td><? echo $Usines;?></td>
						<td><? echo $Pont;?></td>
						<td><? echo $Piste;?></td>
						<td><? echo $Avion;?></td>
					</tr>
			<?
				$data['Aviona']=false;
				$data['Avionb']=false;
			}
		}
		else
			echo "<b>Désolé, aucun destructeur n'a encore émergé dans cette campagne</b>";
		echo "</table></div>";
	}
	else
		echo "<table class='table'><tr><td><img src='images/acces_premium.png'></td></tr><tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr></table>";
}
?>