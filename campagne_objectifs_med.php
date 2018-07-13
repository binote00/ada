<?
require_once('./jfv_inc_sessions.php');
if(1==2)
{
	include_once('./jfv_inc_const.php');
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_actus.php');

	//Check Joueur Valide
	//if(isset($_SESSION['login']) AND isset($_SESSION['pwd']))
	$Tri = Insec($_POST['Tri']);
	if(!$Tri)
	{
		$Tri = 1;
	}
	if(1==1)
	{
		$Allie_score = 0;
		$Axe_score = 0;
		$PlayerID = $_SESSION['PlayerID'];
		$country = $_SESSION['country'];
		$img = "<img src='images/tobrouk.jpg' border='1'>";
		
		/*Ravit Tirana
		if($country == 6 or $country == 1 or $country == 4)
		{
			$con = dbconnecti(4);
			$mun8 = mysqli_result(mysqli_query($con, "SELECT SUM(Avion_Nbr) FROM Events WHERE Event_Type=112 AND Lieu=451 AND Date > 2013-03-15 AND Avion=8"),0);
			$mun13 = mysqli_result(mysqli_query($con, "SELECT SUM(Avion_Nbr) FROM Events WHERE Event_Type=112 AND Lieu=451 AND Date > 2013-03-15 AND Avion=13"),0);
			$mun20 = mysqli_result(mysqli_query($con, "SELECT SUM(Avion_Nbr) FROM Events WHERE Event_Type=112 AND Lieu=451 AND Date > 2013-03-15 AND Avion=20"),0);
			$carbu87 = mysqli_result(mysqli_query($con, "SELECT SUM(Avion_Nbr) FROM Events WHERE Event_Type=111 AND Lieu=451 AND Date > 2013-03-15 AND Avion=87"),0);
			mysqli_close($con);
			$ravit = "<table>
			<tr bgcolor='tan'><th colspan='3'>Ravitaillement de Tirana</th></tr>
			<tr><th>Munitions 8mm</th><td align='right'>".$mun8."</td><td align='right'>/ 1.000.000</td></tr>
			<tr><th>Munitions 13mm</th><td align='right'>".$mun13."</td><td align='right'>/ 300.000</td></tr>
			<tr><th>Munitions 20mm</th><td align='right'>".$mun20."</td><td align='right'>/ 50.000</td></tr>
			<tr><th>Carburant</th><td align='right'>".$carbu87."</td><td align='right'>/ 25.000</td></tr>
			</table>"
		}*/
		
		/*for($Pays = 1; $Pays < 7; $Pays++)
		{
			$con = dbconnecti();
			$Croiseur_lg_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=18 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2013-02-09'"),0);
			$Croiseur_ld_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=19 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2013-02-09'"),0);
			$Cuirasse_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=20 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2013-02-09'"),0);
			$Porte_avions_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=21 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2013-02-09'"),0);
			mysqli_close($con);
			
			//1000
			$Objectif_Axe_Med = (($Croiseur_lg_med[4] + $Croiseur_lg_med[6])*50) + (($Croiseur_ld_med[4] + $Croiseur_ld_med[6])*100) + (($Cuirasse_med[4] + $Cuirasse_med[6])*200) + (($Porte_avions_med[4] + $Porte_avions_med[6])*250);
			$Objectif_Allies_Med = ($Croiseur_lg_med[2]*50) + ($Croiseur_ld_med[2]*100) + ($Cuirasse_med[2]*200) + ($Porte_avions_med[2]*250);
		}*/

		
		$con = dbconnecti();
	/*	$query="SELECT ID,Nom,Pays,Pays_Origine,Unit,Avancement,Victoires_atk, 
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type IN(1,2) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Attaque.Date >= '2013-02-26') AS Camions,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(58,59,60,61,62,65,78,79,91) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Attaque.Date >= '2013-02-26') AS HT3,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(19,22,25,26,57,64,77,80,81,82,83,84,92) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Attaque.Date >= '2013-02-26') AS HT5,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(23,24,27,28,43) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Attaque.Date >= '2013-02-26') AS Blinde10,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(29,31,32,33,34,37,38,39,40,41,44,45,46,47,90) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Attaque.Date >= '2013-02-26') AS Blinde15,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(30,35,36,42) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Attaque.Date >= '2013-02-26') AS Blinde20,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=6 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Attaque.Date >= '2013-02-26') AS Canon1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=6 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Bombardement.Date >= '2013-02-26') AS Canon2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=12 AND Attaque.Lieu=Lieu.ID 
		AND (Lieu.Pays = 10 OR Lieu.ID IN(451,697,699,700,701,894,1113)) AND Attaque.Date >= '2013-02-26') AS DCA1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=12 AND Bombardement.Lieu=Lieu.ID 
		AND (Lieu.Pays = 10 OR Lieu.ID IN(451,697,699,700,701,894,1113)) AND Bombardement.Date >= '2013-02-26') AS DCA2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=13 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(451,697,699,700,701,894,1113) AND Attaque.Date >= '2013-02-26') AS Loco,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=22 AND Attaque.Lieu=Lieu.ID 
		AND (Lieu.Pays = 10 OR Lieu.ID IN(700,894,697,699,701,451,446,449,448,458,459,678)) AND Attaque.Date >= '2013-02-26') AS Avion_sol,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type IN(14,15,16,17,18,19,20,21) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(449,458,459,505,506,701) AND Attaque.Date >= '2013-02-26') AS Navire,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type IN(25,26,27,28,31,34) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-02-26') AS Batiments1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type IN(25,26,27,28,31,34) AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-02-26') AS Batiments2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=25 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-02-26') AS Entrepot1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=26 AND Attaque.Lieu=Lieu.ID 
		AND (Lieu.Pays = 10 OR Lieu.ID IN(700,894,697,699,701,451,446,449,448,458,459,678)) AND Attaque.Date >= '2013-02-26') AS Tours1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=27 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-02-26') AS Usines1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=28 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-02-26') AS Gare1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=29 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-02-26') AS Pont1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=30 AND Attaque.Lieu=Lieu.ID 
		AND (Lieu.Pays = 10 OR Lieu.ID IN(700,894,697,699,701,451,446,449,448,458,459,678)) AND Attaque.Date >= '2013-02-26') AS Piste1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=34 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-02-26') AS Caserne1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=36 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-02-26') AS Quais1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=31 AND Attaque.Lieu=Lieu.ID 
		AND (Lieu.Pays = 10 OR Lieu.ID IN(700,894,697,699,701,451,446,449,448,458,459,678)) AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-02-26') AS Carbu1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=25 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-02-26') AS Entrepot2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=26 AND Bombardement.Lieu=Lieu.ID AND 
		(Lieu.Pays = 10 OR Lieu.ID IN(700,894,697,699,701,451,446,449,448,458,459,678)) AND Bombardement.Date >= '2013-02-26') AS Tours2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=27 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-02-26') AS Usines2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=28 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-02-26') AS Gare2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=29 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-02-26') AS Pont2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=30 AND Bombardement.Lieu=Lieu.ID AND 
		(Lieu.Pays = 10 OR Lieu.ID IN(700,894,697,699,701,451,446,449,448,458,459,678)) AND Bombardement.Date >= '2013-02-26') AS Piste2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=34 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-02-26') AS Caserne2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=36 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Pays = 10 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-02-26') AS Quais2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=31 AND Bombardement.Lieu=Lieu.ID AND 
		(Lieu.Pays = 10 OR Lieu.ID IN(700,894,697,699,701,451,446,449,448,458,459,678)) AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-02-26') AS Carbu2,
		(SELECT Canon1+Canon2) AS Canon,
		(SELECT DCA1+DCA2) AS DCA,
		(SELECT HT3+HT5) AS HT,
		(SELECT Blinde10+Blinde15+Blinde20) AS Blinde,
		(SELECT Batiments1+Batiments2) AS Batiments,
		(SELECT Caserne1+Caserne2) AS Caserne,
		(SELECT Entrepot1+Entrepot2) AS Entrepot,
		(SELECT Tours1+Tours2) AS Tours,
		(SELECT Gare1+Gare2) AS Gare,
		(SELECT Usines1+Usines2) AS Usines,
		(SELECT Pont1+Pont2) AS Pont,
		(SELECT Piste1+Piste2) AS Piste,
		(SELECT Quais1+Quais2) AS Quais,
		(SELECT Carbu1+Carbu2) AS Carbu,
		(SELECT (HT3*3)+(HT5*5)+(Blinde10*10)+(Blinde15*15)+(Blinde20*20)+(Canon*3)+(DCA*10)+(Loco*10)+(Avion_sol*2)+(Entrepot*3)+(Usines*30)+(Quais*30)+(Pont*25)+(Gare*15)+(Piste*15)+(Tours*10)+(Caserne*5)+(Carbu*5)+Camions) AS Points
		FROM Joueur WHERE Victoires_atk > 0 AND Actif=0 ORDER BY Points DESC LIMIT 50";*/
		$query="SELECT ID,Nom,Pays,Pays_Origine,Unit,Avancement,Victoires_atk, 
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type IN(1,2) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,559,666,673,693,694,889,892,1104) AND Attaque.Date >= '2013-04-09') AS Camions,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(58,59,60,61,62,65,78,79,91) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,559,666,673,693,694,889,892,1104) AND Attaque.Date >= '2013-04-09') AS HT3,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(19,22,25,26,57,64,77,80,81,82,83,84,92) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,559,666,673,693,694,889,892,1104) AND Attaque.Date >= '2013-04-09') AS HT5,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(23,24,27,28,43) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,559,666,673,693,694,889,892,1104) AND Attaque.Date >= '2013-04-09') AS Blinde10,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(29,31,32,33,34,37,38,39,40,41,44,45,46,47,90) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,559,666,673,693,694,889,892,1104) AND Attaque.Date >= '2013-04-09') AS Blinde15,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Cible_id IN(30,35,36,42) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,559,666,673,693,694,889,892,1104) AND Attaque.Date >= '2013-04-09') AS Blinde20,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=6 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,559,666,673,693,694,889,892,1104) AND Attaque.Date >= '2013-04-09') AS Canon1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=6 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,559,666,673,693,694,889,892,1104) AND Bombardement.Date >= '2013-04-09') AS Canon2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=12 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Attaque.Date >= '2013-04-09') AS DCA1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=12 AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Bombardement.Date >= '2013-04-09') AS DCA2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=13 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Attaque.Date >= '2013-04-09') AS Loco,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=22 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Attaque.Date >= '2013-04-09') AS Avion_sol,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type IN(14,15,16,17,18,19,20,21,22) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.ID IN(456,457,504,514,515,747) AND Attaque.Date >= '2013-04-09') AS Navire,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type IN(25,26,27,28,31,34) AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-04-09') AS Batiments1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type IN(25,26,27,28,31,34) AND Bombardement.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-04-09') AS Batiments2,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=25 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-04-09') AS Entrepot1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=26 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Attaque.Date >= '2013-04-09') AS Tours1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=27 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-04-09') AS Usines1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=28 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-04-09') AS Gare1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=29 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-04-09') AS Pont1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=30 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Attaque.Date >= '2013-04-09') AS Piste1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=34 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-04-09') AS Caserne1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=36 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-04-09') AS Quais1,
		(SELECT COUNT(*) FROM Attaque,Lieu WHERE Attaque.Joueur=Joueur.ID AND Attaque.Type=31 AND Attaque.Lieu=Lieu.ID 
		AND Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Attaque.Date >= '2013-04-09') AS Carbu1,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=25 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-04-09') AS Entrepot2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=26 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Bombardement.Date >= '2013-04-09') AS Tours2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=27 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-04-09') AS Usines2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=28 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-04-09') AS Gare2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=29 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-04-09') AS Pont2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=30 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Bombardement.Date >= '2013-04-09') AS Piste2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=34 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-04-09') AS Caserne2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=36 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-04-09') AS Quais2,
		(SELECT COUNT(*) FROM Bombardement,Lieu WHERE Bombardement.Joueur=Joueur.ID AND Bombardement.Type=31 AND Bombardement.Lieu=Lieu.ID AND 
		Lieu.Latitude < 32.8 AND Lieu.ValeurStrat >0 AND Bombardement.Date >= '2013-04-09') AS Carbu2,
		(SELECT Canon1+Canon2) AS Canon,
		(SELECT DCA1+DCA2) AS DCA,
		(SELECT HT3+HT5) AS HT,
		(SELECT Blinde10+Blinde15+Blinde20) AS Blinde,
		(SELECT Batiments1+Batiments2) AS Batiments,
		(SELECT Caserne1+Caserne2) AS Caserne,
		(SELECT Entrepot1+Entrepot2) AS Entrepot,
		(SELECT Tours1+Tours2) AS Tours,
		(SELECT Gare1+Gare2) AS Gare,
		(SELECT Usines1+Usines2) AS Usines,
		(SELECT Pont1+Pont2) AS Pont,
		(SELECT Piste1+Piste2) AS Piste,
		(SELECT Quais1+Quais2) AS Quais,
		(SELECT Carbu1+Carbu2) AS Carbu,
		(SELECT (HT3*3)+(HT5*5)+(Blinde10*10)+(Blinde15*15)+(Blinde20*20)+(Canon*3)+(DCA*10)+(Loco*10)+(Avion_sol*2)+(Entrepot*3)+(Usines*30)+(Quais*30)+(Pont*25)+(Gare*15)+(Piste*15)+(Tours*10)+(Caserne*5)+(Carbu*5)+Camions) AS Points
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
				$Pilote=$data['Nom'];
				$Pays=$data['Pays'];
				$Pays_Origine=$data['Pays_Origine'];
				$Avancement=$data['Avancement'];
				$Unite=GetData('Unit','ID',$data['Unit'],'Nom');
				$Grade=GetAvancement($Avancement,$Pays_Origine);
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
				
				$Avion_unit_img = "images/unit".$data['Unit']."p.gif";
				if(is_file($Avion_unit_img))
				{
					$Unite = "<img src='".$Avion_unit_img."' title='".$Unite."'>";
				}
				
				if($Pays == 2)
					$Allie_score += $Points;
				else
					$Axe_score += $Points;
				
				$tableau .= "<tr>
						<td>".$Pilote."</td>
						<td>".$Unite."</td>
						<td><img title='".$Grade[0]."' src='images/pgrades".$Pays_Origine.$Grade[1].".gif'></td>
						<td><img src='".$Pays."20.gif'></td>
						<td bgcolor='LightYellow'>".$Points."</td>
						<td>".$Camions."</td>
						<td>".$HT."</td>
						<td>".$Blinde."</td>
						<td>".$Canon."</td>
						<td>".$DCA."</td>
						<td>".$Loco."</td>
						<td>".$Navire."</td>
						<td>".$Avion_sol."</td>
						<td>".$Batiments."</td>
						<td>".$Usines."</td>
						<td>".$Pont."</td>
						<td>".$Piste."</td>
					</tr>";
			}
		}
	}
	?>
	<div>
	<h2>Objectifs de la Campagne d'Afrique</h2>
		<table border="0" cellspacing="1" cellpadding="5" bgcolor="#ECDDC1">
		<tr><td colspan="3"><?echo $img;?></td></tr>
		<tr bgcolor='tan'><th colspan="3">Score</th></tr>
		<tr bgcolor='lightyellow'><th></th><th><img src='220.gif'> Alliés <img src='220.gif'></th><th><img src='620.gif'> Axe <img src='420.gif'></th></tr>
		<tr><th>Terrestre</th><td><?echo $Allie_score;?></td><td><?echo $Axe_score;?></td></tr>
		</table>
	<hr>
	<?echo '<br>'.$ravit.'<br>';?>
	<table border="0" cellspacing="1" cellpadding="5" bgcolor="#ECDDC1">
		<tr><th colspan="30" class="TitreBleu_bc">Tableau des Destructeurs</th></tr>
		<tr bgcolor="#CDBDA7">
			<th>Pilote</th>
			<th>Unité</th>
			<th>Grade</th>
			<th>Pays</th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="1"><input title="Points" type='Submit' value='Points'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="2"><input title="Camions et Half-Tracks non armés" type='Submit' value='Camions'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="3"><input title="Voitures blindées, Half-Tracks armés et Blindés légers" type='Submit' value='Véhicules'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="4"><input title="Blindés moyens et lourds, Artillerie automotrice et Chasseurs de chars" type='Submit' value='Blindés'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="5"><input title="Artillerie tractée ou Canons anti-chars" type='Submit' value='Canons'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="6"><input title="Canons de D.C.A détruits" type='Submit' value='D.C.A'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="7"><input title="Locomotives détruites" type='Submit' value='Trains'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="8"><input title="Navires coulés" type='Submit' value='Navires'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="9"><input title="Avions détruits au sol" type='Submit' value='Avions'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="10"><input title="Bâtiments secondaires détruits" type='Submit' value='Bâtiments'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="11"><input title="Bâtiments principaux détruits" type='Submit' value='Usines'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="12"><input title="Ponts détruits" type='Submit' value='Ponts'></form></th>
			<th><form action='index.php?view=campagne_objectifs_med' method='post'><input type='hidden' name='Tri' value="13"><input title="Pistes endommagées" type='Submit' value='Pistes'></form></th>
		</tr>

	<?
		echo $tableau.'</table>';
		/*Campagne 07-1940/09-1940
		$con = dbconnecti();
		$result =mysqli_query($con, "SELECT ID,Port,QualitePiste,Recce
		FROM Lieu WHERE ID IN(343,344) ORDER BY Nom ASC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result)) 
			{
				if(!$data['Port'])
					$Port = "<font color='red'>Détruit</font>";
				elseif($data['Port'] == 100)
					$Port = "<b>Intact</b>";
				elseif($data['Port'] < 100)
					$Port = "<font color='red'>".$data['Port']."%</font>";
				$Piste = "<img src='images/piste".GetQualitePiste_img($data['QualitePiste']).".jpg' title='Etat ".$data['QualitePiste']."%'>";
					
				if($data['ID'] == 343)
				{
					if($country == 2 or $data['Recce'] > 0)
					{
						$Malte_Port = $Port;
						$Malte_Base = $Piste;
					}
					else
					{
						$Malte_Port = "Inconnu";
						$Malte_Base = "<img src='images/piste100.jpg' title='Inconnu'>";
					}
				}
				else
				{
					if($country == 2 or $data['Recce'] > 0)
					{
						$Gib_Port = $Port;
						$Gib_Base = $Piste;
					}
					else
					{
						$Gib_Port = "Inconnu";
						$Gib_Base = "<img src='images/piste100.jpg' title='Inconnu'>";
					}
				}
			}
		}

		
		for($Pays = 1; $Pays < 7; $Pays++)
		{
			if($Pays == 2)
			{
				$con = dbconnecti();
				$Cargo[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Cible_id=5001 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.ID IN (96,118,120,128,142,167,168,264,350,378,480,481,482,483,484,486,668) AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);
			}
			else
			{
				$con = dbconnecti();
				$Cargo[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Cible_id=5001 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.ID IN (224,269,270,272,273,279,280,285,286,287,312,468,470,480,481,482,483,484,487,488,490,493,494,495,593) AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);
			}
			$Corvette[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=15 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Fregate[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=16 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Destroyer[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=17 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Croiseur_lg[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=18 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Croiseur_ld[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=19 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Cuirasse[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=20 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Porte_avions[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=21 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45 AND Attaque.Date >= '2012-11-01'"),0);

			$Cargo_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Cible_id=5001 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Corvette_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=15 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Fregate_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=16 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Destroyer_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=17 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Croiseur_lg_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=18 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Croiseur_ld_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=19 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Cuirasse_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=20 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2012-11-01'"),0);
			$Porte_avions_med[$Pays] = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=21 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45 AND Attaque.Date >= '2012-11-01'"),0);
			mysqli_close($con);
			
			$Obj_Cargo_Axe = $Cargo[1] + $Cargo[4] + $Cargo[6]; //200
			$Obj_Cargo_Allies = $Cargo[2];
			$Obj_Cargo_Axe_Med = $Cargo_med[1] + $Cargo_med[4] + $Cargo_med[6]; //300
			$Obj_Cargo_Allies_Med = $Cargo_med[2];
			//2500
			$Objectif_Axe_Med = (($Corvette_med[4] + $Corvette_med[6])*10) + (($Fregate_med[4] + $Fregate_med[6])*15) + (($Destroyer_med[4] + $Destroyer_med[6])*25) + (($Croiseur_lg_med[4] + $Croiseur_lg_med[6])*50) + (($Croiseur_ld_med[4] + $Croiseur_ld_med[6])*100) + (($Cuirasse_med[4] + $Cuirasse_med[6])*200) + (($Porte_avions_med[4] + $Porte_avions_med[6])*250);
			$Objectif_Allies_Med = ($Corvette_med[2]*10) + ($Fregate_med[2]*15) + ($Destroyer_med[2]*25) + ($Croiseur_lg_med[2]*50) + ($Croiseur_ld_med[2]*100) + ($Cuirasse_med[2]*200) + ($Porte_avions_med[2]*250);
		}	
	?>	
	<h2>Objectifs de Campagne Front Med</h2>
	<div id="col_gauche">	
		<table align="center" border="0" bgcolor="#ECDDC1">
		<tr><td align="center" colspan="3"><i>Les actions des pilotes de chaque faction durant les évènements historiques alimentent ce score.</i></td></tr>
		<tr><td><?echo $img;?></td></tr>
		</table>
	</div>
	<div id="col_droite">
		<table align="center" border="0" bgcolor="#ECDDC1" width="640">
		<tr align="center"><td><h3><img src='120.gif'> Axe <img src='620.gif'></h3></td><td></td><td><h3><img src='220.gif'> Alliés <img src='420.gif'></h3></td></tr>
		<tr bgcolor="lightyellow"><th colspan="3">Cargos coulés dans la Manche</th></tr>
		<tr><th><font color='red'><? echo $Obj_Cargo_Axe;?> / 200</font></th><td></td><th><font color='green'><? echo $Obj_Cargo_Allies;?> / 200</font></th></tr>
		<tr bgcolor="lightyellow"><th colspan="3">Cargos coulés en Méditerranée</th></tr>
		<tr><th><font color='green'><? echo $Obj_Cargo_Axe_Med;?> / 300</font></th><td></td><th><font color='green'><? echo $Obj_Cargo_Allies_Med;?> / 300</font></th></tr>
		<tr bgcolor="lightyellow"><th colspan="3">Navires de guerre coulés en Méditerranée</th></tr>
		<tr><th><font color='green'><? echo $Objectif_Axe_Med;?> / 2500</font></th><td></td><th><font color='green'><? echo $Objectif_Allies_Med;?> / 2500</font></th></tr>
		</table>
	<hr><br>
		<table border="0" cellspacing="1" cellpadding="5" bgcolor="#ECDDC1">
		<tr bgcolor='tan'><th colspan="3">Objectifs</th></tr>
		<tr bgcolor='lightyellow'><th></th><th>Gibraltar</th><th>Malte</th></tr>
		<tr><th>Port</th><td><?echo $Gib_Port;?></td><td><?echo $Malte_Port;?></td></tr>
		<tr><th>Aérodrome</th><td><?echo $Gib_Base;?></td><td><?echo $Malte_Base;?></td></tr>
		</table>
	<br>
	<!--
		<table border="0" cellspacing="1" cellpadding="5" bgcolor="#ECDDC1">
		<tr bgcolor='tan'><th colspan="10">Objectifs détruits</th></tr>
		<tr bgcolor='lightyellow'><th colspan="5">Front Ouest</th><th colspan="5">Front Med</th></tr>
		<tr><th></th><th title='Allemagne'><img src='120.gif'></th><th title='Angleterre'><img src='220.gif'></th><th title='France'><img src='420.gif'></th><th title='Italie'><img src='620.gif'></th><th bgcolor='lightyellow'></th><th title='Allemagne'><img src='120.gif'></th><th title='Angleterre'><img src='220.gif'></th><th title='France'><img src='420.gif'></th><th title='Italie'><img src='620.gif'></th></tr>
		<tr><td align='left'>Navires de transport</td><td align='right'><?echo $Cargo[1]?></td><td align='right'><?echo $Cargo[2]?></td><td align='right'><?echo $Cargo[4]?></td><td align='right'><?echo $Cargo[6]?></td>
		<td bgcolor='lightyellow'></td><td align='right'><?echo $Cargo_med[1]?></td><td align='right'><?echo $Cargo_med[2]?></td><td align='right'><?echo $Cargo_med[4]?></td><td align='right'><?echo $Cargo_med[6]?></td></tr>
		<tr><td align='left'>Corvettes</td><td align='right'><?echo $Corvette[1]?></td><td align='right'><?echo $Corvette[2]?></td><td align='right'><?echo $Corvette[4]?></td><td align='right'><?echo $Corvette[6]?></td>
		<td bgcolor='lightyellow'></td><td align='right'><?echo $Corvette_med[1]?></td><td align='right'><?echo $Corvette_med[2]?></td><td align='right'><?echo $Corvette_med[4]?></td><td align='right'><?echo $Corvette_med[6]?></td></tr>
		<tr><td align='left'>Frégates</td><td align='right'><?echo $Fregate[1]?></td><td align='right'><?echo $Fregate[2]?></td><td align='right'><?echo $Fregate[4]?></td><td align='right'><?echo $Fregate[6]?></td>
		<td bgcolor='lightyellow'></td><td align='right'><?echo $Fregate_med[1]?></td><td align='right'><?echo $Fregate_med[2]?></td><td align='right'><?echo $Fregate_med[4]?></td><td align='right'><?echo $Fregate_med[6]?></td></tr>
		<tr><td align='left'>Destroyers</td><td align='right'><?echo $Destroyer[1]?></td><td align='right'><?echo $Destroyer[2]?></td><td align='right'><?echo $Destroyer[4]?></td><td align='right'><?echo $Destroyer[6]?></td>
		<td bgcolor='lightyellow'></td><td align='right'><?echo $Destroyer_med[1]?></td><td align='right'><?echo $Destroyer_med[2]?></td><td align='right'><?echo $Destroyer_med[4]?></td><td align='right'><?echo $Destroyer_med[6]?></td></tr>
		<tr><td align='left'>Croiseurs Légers</td><td align='right'><?echo $Croiseur_lg[1]?></td><td align='right'><?echo $Croiseur_lg[2]?></td><td align='right'><?echo $Croiseur_lg[4]?></td><td align='right'><?echo $Croiseur_lg[6]?></td>
		<td bgcolor='lightyellow'></td><td align='right'><?echo $Croiseur_lg_med[1]?></td><td align='right'><?echo $Croiseur_lg_med[2]?></td><td align='right'><?echo $Croiseur_lg_med[4]?></td><td align='right'><?echo $Croiseur_lg_med[6]?></td></tr>
		<tr><td align='left'>Croiseurs Lourds</td><td align='right'><?echo $Croiseur_ld[1]?></td><td align='right'><?echo $Croiseur_ld[2]?></td><td align='right'><?echo $Croiseur_ld[4]?></td><td align='right'><?echo $Croiseur_ld[6]?></td>
		<td bgcolor='lightyellow'></td><td align='right'><?echo $Croiseur_ld_med[1]?></td><td align='right'><?echo $Croiseur_ld_med[2]?></td><td align='right'><?echo $Croiseur_ld_med[4]?></td><td align='right'><?echo $Croiseur_ld_med[6]?></td></tr>
		<tr><td align='left'>Cuirassés</td><td align='right'><?echo $Cuirasse[1]?></td><td align='right'><?echo $Cuirasse[2]?></td><td align='right'><?echo $Cuirasse[4]?></td><td align='right'><?echo $Cuirasse[6]?></td>
		<td bgcolor='lightyellow'></td><td align='right'><?echo $Cuirasse_med[1]?></td><td align='right'><?echo $Cuirasse_med[2]?></td><td align='right'><?echo $Cuirasse_med[4]?></td><td align='right'><?echo $Cuirasse_med[6]?></td></tr>
		<tr><td align='left'>Porte-avions</td><td align='right'><?echo $Porte_avions[1]?></td><td align='right'><?echo $Porte_avions[2]?></td><td align='right'><?echo $Porte_avions[4]?></td><td align='right'><?echo $Porte_avions[6];?></td>
		<td bgcolor='lightyellow'></td><td align='right'><?echo $Porte_avions_med[1]?></td><td align='right'><?echo $Porte_avions_med[2]?></td><td align='right'><?echo $Porte_avions_med[4]?></td><td align='right'><?echo $Porte_avions_med[6];?></td></tr>
		</table>
	</div>-->*/
	}
	else
		echo "<font color='#000000' size='4'>Vous devez être connecté pour accéder à cette page!</font>";
}
?>