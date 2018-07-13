<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_actus.php');
//if(isset($_SESSION['login']) AND isset($_SESSION['pwd']))
if(1==1)
{
	$PlayerID = Insec($_SESSION['PlayerID']);
	$img = "<img src='images/victoires.jpg'>";
	//Events_Historiques
	$con = dbconnecti();
	$result=mysqli_query($con,"SELECT SUM(Points_Allies),SUM(Points_Axe) FROM Event_Historique WHERE Type_Mission > 0");
	mysqli_close($con);
	if($result)
	{
		while($Data=mysqli_fetch_array($result, MYSQLI_NUM)) 
		{
			$Points_Allies_Total = $Data[0]*10;
			$Points_Axe_Total = $Data[1]*10;
			//$Points_Allies_Total = $Points_Allies_Total + $Data['Points_Allies'];
			//$Points_Axe_Total = $Points_Axe_Total + $Data['Points_Axe'];
		}
	}
	unset($result);
	unset($Data);
	
	for($Pays = 1; $Pays < 7; $Pays++)
	{
		$con = dbconnecti();
		$camions[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type IN (1,2) AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$HT[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type IN (3,4,5,11) AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Blinde[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type IN (7,8,9,10) AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Canon[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=6 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Canon2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=6 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$DCA[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=12 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$DCA2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=12 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Loco[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=13 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Entrepot[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =25 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Entrepot2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =25 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Tours[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =26 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Tours2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =26 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Usine[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =27 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Usine2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =27 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Caserne[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =34 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Caserne2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =34 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Radar[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =35 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Radar2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =35 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Quais[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =36 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Quais2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =36 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Gare[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =28 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Gare2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =28 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Pont[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=29 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Pont2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=29 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Piste[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=30 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Piste2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=30 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Carbu[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=31 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Carbu2[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=31 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Cargo[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=14 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Corvette[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=15 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Fregate[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=16 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Destroyer[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=17 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Croiseur_lg[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=18 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Croiseur_ld[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=19 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Cuirasse[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=20 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Porte_avions[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=21 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$Avions_sol[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=22 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);

		$camions_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type IN (1,2) AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$HT_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type IN (3,4,5,11) AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Blinde_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type IN (7,8,9,10) AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Canon_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=6 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Canon2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=6 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$DCA_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=12 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$DCA2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=12 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Loco_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=13 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Entrepot_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =25 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Entrepot2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =25 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Tours_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =26 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Tours2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =26 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Usine_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =27 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Usine2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =27 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Caserne_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =34 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Caserne2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =34 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Radar_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =35 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Radar2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =35 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Quais_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =36 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Quais2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =36 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Gare_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type =28 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Gare2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type =28 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Pont_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=29 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Pont2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=29 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Piste_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=30 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Piste2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=30 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Carbu_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=31 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Carbu2_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement,Unit,Lieu WHERE Bombardement.Unite=Unit.ID AND Bombardement.Type=31 AND Unit.Pays='$Pays' AND Bombardement.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Cargo_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=14 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Corvette_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=15 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Fregate_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=16 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Destroyer_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=17 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Croiseur_lg_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=18 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Croiseur_ld_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=19 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Cuirasse_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=20 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Porte_avions_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=21 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Avions_sol_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque,Unit,Lieu WHERE Attaque.Unite=Unit.ID AND Attaque.Type=22 AND Unit.Pays='$Pays' AND Attaque.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		
		$Transports[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=6 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude > 45"),0);
		$Transports_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=6 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude <= 45"),0);
		$Reco[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type IN (3,9) AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude > 45"),0);
		$Reco_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type IN (3,9) AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude <= 45"),0);
		$Bomb[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type IN (7,10) AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude > 45"),0);
		$Bomb_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type IN (7,10) AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude <= 45"),0);
		$Bi[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=2 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude > 45"),0);
		$Bi_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=2 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude <= 45"),0);
		$Chasseurs[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=1 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude > 45"),0);
		$Chasseurs_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=1 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude <= 45"),0);
		$Jabos[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=5 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude > 45"),0);
		$Jabos_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=5 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude <= 45"),0);
		$Zerstorer[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=4 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude > 45"),0);
		$Zerstorer_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=4 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude <= 45"),0);
		$Quadri[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=11 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude > 45"),0);
		$Quadri_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion,Unit,Chasse WHERE Chasse.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Avion.ID = Chasse.Avion_loss AND Avion.Type=11 AND (Chasse.PVP=0 OR Chasse.PVP=2) AND Chasse.Latitude <= 45"),0);

		$Points[$Pays] = floor(($Transports[$Pays] + ($Reco[$Pays]*20) + ($Bomb[$Pays]*20) + ($Bi[$Pays]*30) + ($Chasseurs[$Pays]*40) + ($Jabos[$Pays]*30) + ($Zerstorer[$Pays]*30) + ($Quadri[$Pays]*50))/10);
		$Points_med[$Pays] = floor(($Transports_med[$Pays] + ($Reco_med[$Pays]*20) + ($Bomb_med[$Pays]*20) + ($Bi_med[$Pays]*30) + ($Chasseurs_med[$Pays]*40) + ($Jabos_med[$Pays]*30) + ($Zerstorer_med[$Pays]*30) + ($Quadri_med[$Pays]*50))/10);
		//$Tot_des[$Pays] = $camions[$Pays]+$HT[$Pays]*5+$Blinde[$Pays]*20+$Loco[$Pays]*10+$Avions_sol[$Pays]*3+$Cargo[$Pays]*2+$Corvette[$Pays]*10+$Fregate[$Pays]*15+$Destroyer[$Pays]*25+$Croiseur_lg[$Pays]*50+$Croiseur_ld[$Pays]*100+$Cuirasse[$Pays]*200+$Porte_avions[$Pays]*250+(($Canon[$Pays]+$Canon2[$Pays])*3)+(($DCA[$Pays]+$DCA2[$Pays])*10)+(($Usine[$Pays]+$Usine2[$Pays])*50)+(($Quais[$Pays]+$Quais2[$Pays])*50)+(($Radar[$Pays]+$Radar2[$Pays])*30)+(($Pont[$Pays]+$Pont2[$Pays])*50)+(($Gare[$Pays]+$Gare2[$Pays])*15)+(($Piste[$Pays]+$Piste2[$Pays])*15)+(($Tours[$Pays]+$Tours2[$Pays])*10)+(($Caserne[$Pays]+$Caserne2[$Pays])*5)+(($Entrepot[$Pays]+$Entrepot2[$Pays])*3)+(($Carbu[$Pays]+$Carbu2[$Pays])*5);
		$Tot_des[$Pays] = round(mysqli_result(mysqli_query($con,"SELECT SUM(Victoires_atk) FROM Pilote WHERE Pays='$Pays' AND Victoires_atk > 0 AND Actif = 0"),0));
		$Tot_des_med[$Pays] = round(mysqli_result(mysqli_query($con,"SELECT SUM(Victoires_atk) FROM Pilote WHERE Pays='$Pays' AND Victoires_atk > 0 AND Actif = 0"),0));
		
		$sauvetage[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sauvetage,Unit,Lieu WHERE Sauvetage.Unite=Unit.ID AND Unit.Pays='$Pays' AND Sauvetage.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$para[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Parachutages,Unit,Lieu WHERE Parachutages.Unite=Unit.ID AND Unit.Pays='$Pays' AND Parachutages.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$ravit[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ravitaillements,Unit,Lieu WHERE Ravitaillements.Unite=Unit.ID AND Unit.Pays='$Pays' AND Ravitaillements.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$recce[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce,Unit,Lieu WHERE Recce.Unite=Unit.ID AND Unit.Pays='$Pays' AND Recce.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$escorte[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Escorte,Unit,Lieu WHERE Escorte.Unite=Unit.ID AND Unit.Pays='$Pays' AND Escorte.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$intercept[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Intercept,Unit,Lieu WHERE Intercept.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Intercept.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$patrouille[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Patrouille,Unit,Lieu WHERE Patrouille.Unite=Unit.ID AND Unit.Pays='$Pays' AND Patrouille.Lieu=Lieu.ID AND Lieu.Latitude > 45"),0);
		$sauvetage_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sauvetage,Unit,Lieu WHERE Sauvetage.Unite=Unit.ID AND Unit.Pays='$Pays' AND Sauvetage.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$para_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Parachutages,Unit,Lieu WHERE Parachutages.Unite=Unit.ID AND Unit.Pays='$Pays' AND Parachutages.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$ravit_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ravitaillements,Unit,Lieu WHERE Ravitaillements.Unite=Unit.ID AND Unit.Pays='$Pays' AND Ravitaillements.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$recce_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce,Unit,Lieu WHERE Recce.Unite=Unit.ID AND Unit.Pays='$Pays' AND Recce.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$escorte_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Escorte,Unit,Lieu WHERE Escorte.Unite=Unit.ID AND Unit.Pays='$Pays' AND Escorte.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$intercept_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Intercept,Unit,Lieu WHERE Intercept.Unite_win=Unit.ID AND Unit.Pays='$Pays' AND Intercept.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$patrouille_med[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Patrouille,Unit,Lieu WHERE Patrouille.Unite=Unit.ID AND Unit.Pays='$Pays' AND Patrouille.Lieu=Lieu.ID AND Lieu.Latitude <= 45"),0);
		$Raids_Bomb[$Pays] = mysqli_result(mysqli_query($con,"SELECT SUM(Raids_Bomb) FROM Pilote WHERE Joueur.Pays='$Pays'"),0);
		$Raids_Bomb_Nuit[$Pays] = mysqli_result(mysqli_query($con,"SELECT SUM(Raids_Bomb_Nuit) FROM Pilote WHERE Joueur.Pays='$Pays'"),0);

		$Missions[$Pays] = round(mysqli_result(mysqli_query($con,"SELECT SUM(Missions) FROM Pilote WHERE Pays='$Pays' AND Missions > 0 AND Actif = 0"),0)/10);
		//$Pilotes[$Pays] = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Pays='$Pays' AND Missions > 0 AND Actif = 0"),0);
		mysqli_close($con);
		/*if($Pilotes[$Pays])
		$Missions[$Pays] = floor($Missions_Nbr[$Pays] / $Pilotes[$Pays]);*/
	}
	$Date_Campagne = GetData("Conf_Update","ID",2,"Date");
	if($Date_Campagne > "1940-06-22")
	{
		$Total_Axe = number_format($Points_Axe_Total+$Tot_des[1]+$Tot_des[4]+$Tot_des[6]+$Points[1]+$Points[4]+$Points[6]+$Missions[1]+$Missions[4]+$Missions[6] + 1000000);
		$Total_Allies = number_format($Points_Allies_Total+$Tot_des[2]+$Tot_des[3]+$Points[2]+$Points[3]+$Missions[2]+$Missions[3] + 1000000); //bonus scénarios
	}
	else
	{
		$Total_Axe = $Points_Axe_Total+$Tot_des[1]+$Tot_des[6]+$Tot_des_med[1]+$Tot_des_med[6]+$Points[1]+$Points[6]+$Points_med[1]+$Points_med[6]+$Missions[1]+$Missions[6];
		$Total_Allies = $Points_Allies_Total+$Tot_des[2]+$Tot_des[3]+$Tot_des[4]+$Tot_des_med[2]+$Tot_des_med[4]+$Points[2]+$Points[3]+$Points[4]+$Points_med[2]+$Points_med[4]+$Missions[2]+$Missions[3]+$Missions[4];
	}
?>	
<div>
	<br>
	<table align="center" border="0" bgcolor="#ECDDC1" width="640">
	<tr><td colspan="3"><h2>Score de Campagne</h2></td></tr>
	<tr align="center"><td><h3><img src='120.gif'> Axe <img src='620.gif'></h3></td><td></td><td><h3><img src='220.gif'> Alliés <img src='420.gif'></h3></td></tr>
	<tr><th>Score Total</th><td></td><th>Score Total</th></tr>
	<tr><th><? echo $Total_Axe;?></th><td></td><th><? echo $Total_Allies;?></th></tr>
	</table>
	<table align="center" border="0" bgcolor="#ECDDC1">
	<tr><td align="center" colspan="3"><? echo $img;?></td></tr>
	<tr><td align="center" colspan="3"><i>Les actions des pilotes de chaque faction durant les évènements historiques alimentent ce score.</i></td></tr>
	</table>
</div>
<div class="col_droite">
	<table class='table'>
	<tr bgcolor='tan'><th colspan="10">Avions abattus</th></tr>
	<tr bgcolor='lightyellow'><th colspan="5">Front Ouest</th><th colspan="5">Front Med</th></tr>
	<tr><th></th><th title='Allemagne'><img src='120.gif'></th><th title='Angleterre'><img src='220.gif'></th><th title='France'><img src='420.gif'></th><th title='Italie'><img src='620.gif'></th><th bgcolor='lightyellow'></th><th title='Allemagne'><img src='120.gif'></th><th title='Angleterre'><img src='220.gif'></th><th title='France'><img src='420.gif'></th><th title='Italie'><img src='620.gif'></th></tr>
	<tr><td align='left'>Transport</td><td align='right'><?echo $Transports[1]?></td><td align='right'><?echo $Transports[2]?></td><td align='right'><?echo $Transports[4]?></td><td align='right'><?echo $Transports[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Transports_med[1]?></td><td align='right'><?echo $Transports_med[2]?></td><td align='right'><?echo $Transports_med[4]?></td><td align='right'><?echo $Transports_med[6]?></td></tr>
	<tr><td align='left'>Reconnaissance</td><td align='right'><?echo $Reco[1]?></td><td align='right'><?echo $Reco[2]?></td><td align='right'><?echo $Reco[4]?></td><td align='right'><?echo $Reco[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Reco_med[1]?></td><td align='right'><?echo $Reco_med[2]?></td><td align='right'><?echo $Reco_med[4]?></td><td align='right'><?echo $Reco_med[6]?></td></tr>
	<tr><td align='left'>Attaque</td><td align='right'><?echo $Bomb[1]?></td><td align='right'><?echo $Bomb[2]?></td><td align='right'><?echo $Bomb[4]?></td><td align='right'><?echo $Bomb[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Bomb_med[1]?></td><td align='right'><?echo $Bomb_med[2]?></td><td align='right'><?echo $Bomb_med[4]?></td><td align='right'><?echo $Bomb_med[6]?></td></tr>
	<tr><td align='left'>Bombardiers</td><td align='right'><?echo $Bi[1]?></td><td align='right'><?echo $Bi[2]?></td><td align='right'><?echo $Bi[4]?></td><td align='right'><?echo $Bi[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Bi_med[1]?></td><td align='right'><?echo $Bi_med[2]?></td><td align='right'><?echo $Bi_med[4]?></td><td align='right'><?echo $Bi_med[6]?></td></tr>
	<tr><td align='left'>Quadrimoteurs</td><td align='right'><?echo $Quadri[1]?></td><td align='right'><?echo $Quadri[2]?></td><td align='right'><?echo $Quadri[4]?></td><td align='right'><?echo $Quadri[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Quadri_med[1]?></td><td align='right'><?echo $Quadri_med[2]?></td><td align='right'><?echo $Quadri_med[4]?></td><td align='right'><?echo $Quadri_med[6]?></td></tr>
	<tr><td align='left'>Chasseurs bombardiers</td><td align='right'><?echo $Jabos[1]?></td><td align='right'><?echo $Jabos[2]?></td><td align='right'><?echo $Jabos[4]?></td><td align='right'><?echo $Jabos[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Jabos_med[1]?></td><td align='right'><?echo $Jabos_med[2]?></td><td align='right'><?echo $Jabos_med[4]?></td><td align='right'><?echo $Jabos_med[6]?></td></tr>
	<tr><td align='left'>Chasseurs lourds</td><td align='right'><?echo $Zerstorer[1]?></td><td align='right'><?echo $Zerstorer[2]?></td><td align='right'><?echo $Zerstorer[4]?></td><td align='right'><?echo $Zerstorer[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Zerstorer_med[1]?></td><td align='right'><?echo $Zerstorer[2]?></td><td align='right'><?echo $Zerstorer[4]?></td><td align='right'><?echo $Zerstorer[6]?></td></tr>
	<tr><td align='left'>Chasseurs</td><td align='right'><?echo $Chasseurs[1]?></td><td align='right'><?echo $Chasseurs[2]?></td><td align='right'><?echo $Chasseurs[4]?></td><td align='right'><?echo $Chasseurs[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Chasseurs_med[1]?></td><td align='right'><?echo $Chasseurs_med[2]?></td><td align='right'><?echo $Chasseurs_med[4]?></td><td align='right'><?echo $Chasseurs_med[6]?></td></tr>
	<tr bgcolor='lightyellow'><th align='left'>Points</th><td align='right'><?echo $Points[1];?></td><td align='right'><?echo $Points[2];?></td><td align='right'><?echo $Points[4];?></td><td align='right'><?echo $Points[6];?></td>
	<th></th><td align='right'><?echo $Points_med[1];?></td><td align='right'><?echo $Points_med[2];?></td><td align='right'><?echo $Points_med[4];?></td><td align='right'><?echo $Points_med[6];?></td><tr>
	<tr><td></td></tr>
	</table>
</div>
<div class="col_gauche">
	<table class='table'>
	<tr bgcolor='tan'><th colspan="10">Objectifs détruits</th></tr>
	<tr bgcolor='lightyellow'><th colspan="5">Front Ouest</th><th colspan="5">Front Med</th></tr>
	<tr><th></th><th title='Allemagne'><img src='120.gif'></th><th title='Angleterre'><img src='220.gif'></th><th title='France'><img src='420.gif'></th><th title='Italie'><img src='620.gif'></th><th bgcolor='lightyellow'></th><th title='Allemagne'><img src='120.gif'></th><th title='Angleterre'><img src='220.gif'></th><th title='France'><img src='420.gif'></th><th title='Italie'><img src='620.gif'></th></tr>
	<tr><td align='left'>Camions</td><td align='right'><?echo $camions[1]?></td><td align='right'><?echo $camions[2]?></td><td align='right'><?echo $camions[4]?></td><td align='right'><?echo $camions[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $camions_med[1]?></td><td align='right'><?echo $camions_med[2]?></td><td align='right'><?echo $camions_med[4]?></td><td align='right'><?echo $camions_med[6]?></td></tr>
	<tr><td align='left'>Véhicules blindés</td><td align='right'><?echo $HT[1]?></td><td align='right'><?echo $HT[2]?></td><td align='right'><?echo $HT[4]?></td><td align='right'><?echo $HT[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $HT_med[1]?></td><td align='right'><?echo $HT_med[2]?></td><td align='right'><?echo $HT_med[4]?></td><td align='right'><?echo $HT_med[6]?></td></tr>
	<tr><td align='left'>Blindés</td><td align='right'><?echo $Blinde[1]?></td><td align='right'><?echo $Blinde[2]?></td><td align='right'><?echo $Blinde[4]?></td><td align='right'><?echo $Blinde[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Blinde_med[1]?></td><td align='right'><?echo $Blinde_med[2]?></td><td align='right'><?echo $Blinde_med[4]?></td><td align='right'><?echo $Blinde_med[6]?></td></tr>
	<tr><td align='left'>Trains</td><td align='right'><?echo $Loco[1]?></td><td align='right'><?echo $Loco[2]?></td><td align='right'><?echo $Loco[4]?></td><td align='right'><?echo $Loco[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Loco_med[1]?></td><td align='right'><?echo $Loco_med[2]?></td><td align='right'><?echo $Loco_med[4]?></td><td align='right'><?echo $Loco_med[6]?></td></tr>
	<tr><td align='left'>Canons</td><td align='right'><?echo $Canon[1]+$Canon2[1]?></td><td align='right'><?echo $Canon[2]+$Canon2[2]?></td><td align='right'><?echo $Canon[4]+$Canon2[4]?></td><td align='right'><?echo $Canon[6]+$Canon2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Canon_med[1]+$Canon2_med[1]?></td><td align='right'><?echo $Canon_med[2]+$Canon2_med[2]?></td><td align='right'><?echo $Canon_med[4]+$Canon2_med[4]?></td><td align='right'><?echo $Canon_med[6]+$Canon2_med[6]?></td></tr>
	<tr><td align='left'>DCA</td><td align='right'><?echo $DCA[1]+$DCA2[1]?></td><td align='right'><?echo $DCA[2]+$DCA2[2]?></td><td align='right'><?echo $DCA[4]+$DCA2[4]?></td><td align='right'><?echo $DCA[6]+$DCA2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $DCA_med[1]+$DCA2_med[1]?></td><td align='right'><?echo $DCA_med[2]+$DCA2_med[2]?></td><td align='right'><?echo $DCA_med[4]+$DCA2_med[4]?></td><td align='right'><?echo $DCA_med[6]+$DCA2_med[6]?></td></tr>
	<tr><td align='left'>Usines</td><td align='right'><?echo $Usine[1]+$Usine2[1]?></td><td align='right'><?echo $Usine[2]+$Usine2[2]?></td><td align='right'><?echo $Usine[4]+$Usine2[4]?></td><td align='right'><?echo $Usine[6]+$Usine2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Usine_med[1]+$Usine2_med[1]?></td><td align='right'><?echo $Usine_med[2]+$Usine2_med[2]?></td><td align='right'><?echo $Usine_med[4]+$Usine2_med[4]?></td><td align='right'><?echo $Usine_med[6]+$Usine2_med[6]?></td></tr>
	<tr><td align='left'>Ports</td><td align='right'><?echo $Quais[1]+$Quais2[1]?></td><td align='right'><?echo $Quais[2]+$Quais2[2]?></td><td align='right'><?echo $Quais[4]+$Quais2[4]?></td><td align='right'><?echo $Quais[6]+$Quais2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Quais_med[1]+$Quais2_med[1]?></td><td align='right'><?echo $Quais_med[2]+$Quais2_med[2]?></td><td align='right'><?echo $Quais_med[4]+$Quais2_med[4]?></td><td align='right'><?echo $Quais_med[6]+$Quais2_med[6]?></td></tr>
	<tr><td align='left'>Radars</td><td align='right'><?echo $Radar[1]+$Radar2[1]?></td><td align='right'><?echo $Radar[2]+$Radar2[2]?></td><td align='right'><?echo $Radar[4]+$Radar2[4]?></td><td align='right'><?echo $Radar[6]+$Radar2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Radar_med[1]+$Radar2_med[1]?></td><td align='right'><?echo $Radar_med[2]+$Radar2_med[2]?></td><td align='right'><?echo $Radar_med[4]+$Radar2_med[4]?></td><td align='right'><?echo $Radar_med[6]+$Radar2_med[6]?></td></tr>
	<tr><td align='left'>Ponts</td><td align='right'><?echo $Pont[1]+$Pont2[1]?></td><td align='right'><?echo $Pont[2]+$Pont2[2]?></td><td align='right'><?echo $Pont[4]+$Pont2[4]?></td><td align='right'><?echo $Pont[6]+$Pont2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Pont_med[1]+$Pont2_med[1]?></td><td align='right'><?echo $Pont_med[2]+$Pont2_med[2]?></td><td align='right'><?echo $Pont_med[4]+$Pont2_med[4]?></td><td align='right'><?echo $Pont_med[6]+$Pont2_med[6]?></td></tr>
	<tr><td align='left'>Gares</td><td align='right'><?echo $Gare[1]+$Gare2[1]?></td><td align='right'><?echo $Gare[2]+$Gare2[2]?></td><td align='right'><?echo $Gare[4]+$Gare2[4]?></td><td align='right'><?echo $Gare[6]+$Gare2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Gare_med[1]+$Gare2_med[1]?></td><td align='right'><?echo $Gare_med[2]+$Gare2_med[2]?></td><td align='right'><?echo $Gare_med[4]+$Gare2_med[4]?></td><td align='right'><?echo $Gare_med[6]+$Gare2_med[6]?></td></tr>
	<tr><td align='left'>Casernes</td><td align='right'><?echo $Caserne[1]+$Caserne2[1]?></td><td align='right'><?echo $Caserne[2]+$Caserne2[2]?></td><td align='right'><?echo $Caserne[4]+$Caserne2[4]?></td><td align='right'><?echo $Caserne[6]+$Caserne2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Caserne_med[1]+$Caserne2_med[1]?></td><td align='right'><?echo $Caserne_med[2]+$Caserne2_med[2]?></td><td align='right'><?echo $Caserne_med[4]+$Caserne2_med[4]?></td><td align='right'><?echo $Caserne_med[6]+$Caserne2_med[6]?></td></tr>
	<tr><td align='left'>Entrepots</td><td align='right'><?echo $Entrepot[1]+$Entrepot2[1]?></td><td align='right'><?echo $Entrepot[2]+$Entrepot2[2]?></td><td align='right'><?echo $Entrepot[4]+$Entrepot2[4]?></td><td align='right'><?echo $Entrepot[6]+$Entrepot2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Entrepot_med[1]+$Entrepot2_med[1]?></td><td align='right'><?echo $Entrepot_med[2]+$Entrepot2_med[2]?></td><td align='right'><?echo $Entrepot_med[4]+$Entrepot2_med[4]?></td><td align='right'><?echo $Entrepot_med[6]+$Entrepot2_med[6]?></td></tr>
	<tr><td align='left'>Pistes</td><td align='right'><?echo $Piste[1]+$Piste2[1]?></td><td align='right'><?echo $Piste[2]+$Piste2[2]?></td><td align='right'><?echo $Piste[4]+$Piste2[4]?></td><td align='right'><?echo $Piste[6]+$Piste2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Piste_med[1]+$Piste2_med[1]?></td><td align='right'><?echo $Piste_med[2]+$Piste2_med[2]?></td><td align='right'><?echo $Piste_med[4]+$Piste2_med[4]?></td><td align='right'><?echo $Piste_med[6]+$Piste2_med[6]?></td></tr>
	<tr><td align='left'>Tours de contrôle</td><td align='right'><?echo $Tours[1]+$Tours2[1]?></td><td align='right'><?echo $Tours[2]+$Tours2[2]?></td><td align='right'><?echo $Tours[4]+$Tours2[4]?></td><td align='right'><?echo $Tours[6]+$Tours2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Tours_med[1]+$Tours2_med[1]?></td><td align='right'><?echo $Tours_med[2]+$Tours2_med[2]?></td><td align='right'><?echo $Tours_med[4]+$Tours2_med[4]?></td><td align='right'><?echo $Tours_med[6]+$Tours2_med[6]?></td></tr>
	<tr><td align='left'>Avions au sol</td><td align='right'><?echo $Avions_sol[1]?></td><td align='right'><?echo $Avions_sol[2]?></td><td align='right'><?echo $Avions_sol[4]?></td><td align='right'><?echo $Avions_sol[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Avions_sol_med[1]?></td><td align='right'><?echo $Avions_sol_med[2]?></td><td align='right'><?echo $Avions_sol_med[4]?></td><td align='right'><?echo $Avions_sol_med[6]?></td></tr>
	<tr><td align='left'>Réserves de carburant</td><td align='right'><?echo $Carbu[1]+$Carbu2[1]?></td><td align='right'><?echo $Carbu[2]+$Carbu2[2]?></td><td align='right'><?echo $Carbu[4]+$Carbu2[4]?></td><td align='right'><?echo $Carbu[6]+$Carbu2[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Carbu_med[1]+$Carbu2_med[1]?></td><td align='right'><?echo $Carbu_med[2]+$Carbu2_med[2]?></td><td align='right'><?echo $Carbu_med[4]+$Carbu2_med[4]?></td><td align='right'><?echo $Carbu_med[6]+$Carbu2_med[6]?></td></tr>
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
	<tr bgcolor='lightyellow'><th align='left'>Points</th>
	<td align='right'><?//$Tot_des[1] = $camions[1]+$HT[1]*5+$Blinde[1]*20+$Loco[1]*10+$Avions_sol[1]*2+$Cargo[1]*2+$Corvette[1]*10+$Fregate[1]*15+$Destroyer[1]*25+$Croiseur_lg[1]*50+$Croiseur_ld[1]*100+$Cuirasse[1]*200+$Porte_avions[1]*250+(($Canon[1]+$Canon2[1])*3)+(($DCA[1]+$DCA2[1])*10)+(($Usine[1]+$Usine2[1])*30)+(($Quais[1]+$Quais2[1])*30)+(($Radar[1]+$Radar2[1])*30)+(($Pont[1]+$Pont2[1])*25)+(($Gare[1]+$Gare2[1])*15)+(($Piste[1]+$Piste2[1])*15)+(($Tours[1]+$Tours2[1])*10)+(($Caserne[1]+$Caserne2[1])*10)+(($Entrepot[1]+$Entrepot2[1])*3)+(($Carbu[1]+$Carbu2[1])*5); 
	echo $Tot_des[1];?></td>
	<td align='right'><?//$Tot_des[2] = $camions[2]+$HT[2]*5+$Blinde[2]*20+$Loco[2]*10+$Avions_sol[2]*2+$Cargo[2]*2+$Corvette[2]*10+$Fregate[2]*15+$Destroyer[2]*25+$Croiseur_lg[2]*50+$Croiseur_ld[2]*100+$Cuirasse[2]*200+$Porte_avions[2]*250+(($Canon[2]+$Canon2[2])*3)+(($DCA[2]+$DCA2[2])*10)+(($Usine[2]+$Usine2[2])*30)+(($Quais[2]+$Quais2[2])*30)+(($Radar[2]+$Radar2[2])*30)+(($Pont[2]+$Pont2[2])*25)+(($Gare[2]+$Gare2[2])*15)+(($Piste[2]+$Piste2[2])*15)+(($Tours[2]+$Tours2[2])*10)+(($Caserne[2]+$Caserne2[2])*10)+(($Entrepot[2]+$Entrepot2[2])*3)+(($Carbu[2]+$Carbu2[2])*5); 
	echo $Tot_des[2];?></td>
	<td align='right'><?//$Tot_des[4] = $camions[4]+$HT[4]*5+$Blinde[4]*20+$Loco[4]*10+$Avions_sol[4]*2+$Cargo[4]*2+$Corvette[4]*10+$Fregate[4]*15+$Destroyer[4]*25+$Croiseur_lg[4]*50+$Croiseur_ld[4]*100+$Cuirasse[4]*200+$Porte_avions[4]*250+(($Canon[4]+$Canon2[4])*3)+(($DCA[4]+$DCA2[4])*10)+(($Usine[4]+$Usine2[4])*30)+(($Quais[4]+$Quais2[4])*30)+(($Radar[4]+$Radar2[4])*30)+(($Pont[4]+$Pont2[4])*25)+(($Gare[4]+$Gare2[4])*15)+(($Piste[4]+$Piste2[4])*15)+(($Tours[4]+$Tours2[4])*10)+(($Caserne[4]+$Caserne2[4])*10)+(($Entrepot[4]+$Entrepot2[4])*3)+(($Carbu[4]+$Carbu2[4])*5); 
	echo $Tot_des[4];?></td>
	<td align='right'><?//$Tot_des[6] = $camions[6]+$HT[6]*5+$Blinde[6]*20+$Loco[6]*10+$Avions_sol[6]*2+$Cargo[6]*2+$Corvette[6]*10+$Fregate[6]*15+$Destroyer[6]*25+$Croiseur_lg[6]*50+$Croiseur_ld[6]*100+$Cuirasse[6]*200+$Porte_avions[6]*250+(($Canon[6]+$Canon2[6])*3)+(($DCA[6]+$DCA2[6])*10)+(($Usine[6]+$Usine2[6])*30)+(($Quais[6]+$Quais2[6])*30)+(($Radar[6]+$Radar2[6])*30)+(($Pont[6]+$Pont2[6])*25)+(($Gare[6]+$Gare2[6])*15)+(($Piste[6]+$Piste2[6])*15)+(($Tours[6]+$Tours2[6])*10)+(($Caserne[6]+$Caserne2[6])*10)+(($Entrepot[6]+$Entrepot2[6])*3)+(($Carbu[6]+$Carbu2[6])*5); 
	echo $Tot_des[6];?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Tot_des_med[1];?></td><td align='right'><?echo $Tot_des_med[2];?></td><td align='right'><?echo $Tot_des_med[4];?></td><td align='right'><?echo $Tot_des_med[6];?></td>
	</tr>
	<tr><td></td></tr>
	</table>
</div>
<div class="col_droite">
	<table class='table'>
	<tr bgcolor='tan'><th colspan="10">Missions</th></tr>
	<tr bgcolor='lightyellow'><th colspan="5">Front Ouest</th><th colspan="5">Front Med</th></tr>
	<tr><th></th><th title='Allemagne'><img src='120.gif'></th><th title='Angleterre'><img src='220.gif'></th><th title='France'><img src='420.gif'></th><th title='Italie'><img src='620.gif'></th><th bgcolor='lightyellow'></th><th title='Allemagne'><img src='120.gif'></th><th title='Angleterre'><img src='220.gif'></th><th title='France'><img src='420.gif'></th><th title='Italie'><img src='620.gif'></th></tr>
	<tr><td align='left'>Interceptions</td><td align='right'><?echo $intercept[1];?></td><td align='right'><?echo $intercept[2]?></td><td align='right'><?echo $intercept[4]?></td><td align='right'><?echo $intercept[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $intercept_med[1];?></td><td align='right'><?echo $intercept_med[2]?></td><td align='right'><?echo $intercept_med[4]?></td><td align='right'><?echo $intercept_med[6]?></td></tr>
	<tr><td align='left'>Patrouilles</td><td align='right'><?echo $patrouille[1];?></td><td align='right'><?echo $patrouille[2]?></td><td align='right'><?echo $patrouille[4]?></td><td align='right'><?echo $patrouille[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $patrouille_med[1];?></td><td align='right'><?echo $patrouille_med[2]?></td><td align='right'><?echo $patrouille_med[4]?></td><td align='right'><?echo $patrouille_med[6]?></td></tr>
	<tr><td align='left'>Escortes</td><td align='right'><?echo $escorte[1];?></td><td align='right'><?echo $escorte[2]?></td><td align='right'><?echo $escorte[4]?></td><td align='right'><?echo $escorte[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $escorte_med[1];?></td><td align='right'><?echo $escorte_med[2]?></td><td align='right'><?echo $escorte_med[4]?></td><td align='right'><?echo $escorte_med[6]?></td></tr>
	<tr><td align='left'>Reconnaissances</td><td align='right'><?echo $recce[1]+$sauvetage[1];?></td><td align='right'><?echo $recce[2]+$sauvetage[2];?></td><td align='right'><?echo $recce[4]+$sauvetage[6];?></td><td align='right'><?echo $recce[6]+$sauvetage[6];?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $recce_med[1]+$sauvetage_med[1];?></td><td align='right'><?echo $recce_med[2]+$sauvetage_med[2];?></td><td align='right'><?echo $recce_med[4]+$sauvetage_med[6];?></td><td align='right'><?echo $recce_med[6]+$sauvetage_med[6];?></td></tr>
	<tr><td align='left'>Parachutages</td><td align='right'><?echo $para[1];?></td><td align='right'><?echo $para[2]?></td><td align='right'><?echo $para[4]?></td><td align='right'><?echo $para[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $para_med[1];?></td><td align='right'><?echo $para_med[2]?></td><td align='right'><?echo $para_med[4]?></td><td align='right'><?echo $para_med[6]?></td></tr>
	<tr><td align='left'>Ravitaillements</td><td align='right'><?echo $ravit[1];?></td><td align='right'><?echo $ravit[2]?></td><td align='right'><?echo $ravit[4]?></td><td align='right'><?echo $ravit[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $ravit_med[1];?></td><td align='right'><?echo $ravit_med[2]?></td><td align='right'><?echo $ravit_med[4]?></td><td align='right'><?echo $ravit_med[6]?></td></tr>
	<tr bgcolor='lightyellow'><th colspan="10">Affectent tous les fronts</th></tr>
	<tr><td align='left'>Raids de jour</td><td align='right'><?echo $Raids_Bomb[1];?></td><td align='right'><?echo $Raids_Bomb[2]?></td><td align='right'><?echo $Raids_Bomb[4]?></td><td align='right'><?echo $Raids_Bomb[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Raids_Bomb[1];?></td><td align='right'><?echo $Raids_Bomb[2]?></td><td align='right'><?echo $Raids_Bomb[4]?></td><td align='right'><?echo $Raids_Bomb[6]?></td></tr>
	<tr><td align='left'>Raids de nuit</td><td align='right'><?echo $Raids_Bomb_Nuit[1];?></td><td align='right'><?echo $Raids_Bomb_Nuit[2]?></td><td align='right'><?echo $Raids_Bomb_Nuit[4]?></td><td align='right'><?echo $Raids_Bomb_Nuit[6]?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $Raids_Bomb_Nuit[1];?></td><td align='right'><?echo $Raids_Bomb_Nuit[2]?></td><td align='right'><?echo $Raids_Bomb_Nuit[4]?></td><td align='right'><?echo $Raids_Bomb_Nuit[6]?></td></tr>
	<tr><th align='left'>Total</th><td align='right'><?echo $escorte[1]+$intercept[1]+$patrouille[1]+$escorte[1]+$Raids_Bomb[1]+$Raids_Bomb_Nuit[1];?></td><td align='right'><?echo $escorte[2]+$intercept[2]+$patrouille[2]+$escorte[2]+$Raids_Bomb[2]+$Raids_Bomb_Nuit[2];?></td><td align='right'><?echo $escorte[4]+$intercept[4]+$patrouille[4]+$escorte[4]+$Raids_Bomb[4]+$Raids_Bomb_Nuit[4];?></td><td align='right'><?echo $escorte[6]+$intercept[6]+$patrouille[6]+$escorte[6]+$Raids_Bomb[6]+$Raids_Bomb_Nuit[6];?></td>
	<td bgcolor='lightyellow'></td><td align='right'><?echo $escorte_med[1]+$intercept_med[1]+$patrouille_med[1]+$escorte_med[1]+$Raids_Bomb[1]+$Raids_Bomb_Nuit[1];?></td><td align='right'><?echo $escorte_med[2]+$intercept_med[2]+$patrouille_med[2]+$escorte_med[2]+$Raids_Bomb[2]+$Raids_Bomb_Nuit[2];?></td><td align='right'><?echo $escorte_med[4]+$intercept_med[4]+$patrouille_med[4]+$escorte_med[4]+$Raids_Bomb[4]+$Raids_Bomb_Nuit[4];?></td><td align='right'><?echo $escorte_med[6]+$intercept_med[6]+$patrouille_med[6]+$escorte_med[6]+$Raids_Bomb[6]+$Raids_Bomb_Nuit[6];?></td></tr>
	<tr bgcolor='lightyellow'><th align='left'>Points</th>
	<td align='right'><?echo $Missions[1];?></td><td align='right'><?echo $Missions[2];?></td><td align='right'><?echo $Missions[4];?></td><td align='right'><?echo $Missions[6];?></td><td bgcolor='lightyellow'></td>
	<td align='right'><?echo $Missions[1];?></td><td align='right'><?echo $Missions[2];?></td><td align='right'><?echo $Missions[4];?></td><td align='right'><?echo $Missions[6];?></td></tr>
	</table>
</div>
	<?
	//<td align='right'>echo $escorte[1]*50+$intercept[1]*30+$patrouille[1]*40+$recce[1]*50+$sauvetage[1]*50+$ravit[1]*10+$para[1]*50+$Raids_Bomb[1]*70+$Raids_Bomb_Nuit[1]*50;</td><td align='right'>echo $escorte[2]*50+$intercept[2]*30+$patrouille[2]*40+$recce[2]*50+$sauvetage[2]*50+$ravit[2]*10+$para[2]*50+$Raids_Bomb[2]*70+$Raids_Bomb_Nuit[2]*50;</td>
	//<td align='right'>echo $escorte[4]*50+$intercept[4]*30+$patrouille[4]*40+$recce[4]*50+$sauvetage[4]*50+$ravit[4]*10+$para[4]*50+$Raids_Bomb[4]*70+$Raids_Bomb_Nuit[4]*50;</td><td align='right'>echo $escorte[6]*50+$intercept[6]*30+$patrouille[6]*40+$recce[6]*50+$sauvetage[6]*50+$ravit[6]*10+$para[6]*50+$Raids_Bomb[6]*70+$Raids_Bomb_Nuit[6]*50;</td></tr>
}
else
{
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
}
?>