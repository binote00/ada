<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) and $PlayerID ==1)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$con=dbconnecti();
	$ok_up=mysqli_query($con,"UPDATE Conf_Update SET `Date`=DATE(NOW()) WHERE ID IN(1,4)");
	$ok_up=mysqli_query($con,"UPDATE Conf_Update SET `Date`='1940-05-01' WHERE ID=2");
	$ok_up=mysqli_query($con,"UPDATE Armee SET Active=0,Cdt=0,Base=Base_Ori");
	$ok_up=mysqli_query($con,"UPDATE Division SET Armee=0,Cdt=0,Base=Base_Ori,repli=0,rally=0,atk=0,hatk=9,def=0,ravit=0");
	$ok_up=mysqli_query($con,"UPDATE Depots SET Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,
	Stock_Essence_1=0,Stock_Essence_87=0,Stock_Essence_100=0,Stock_Bombes_30=0,Stock_Bombes_50=0,Stock_Bombes_80=0,Stock_Bombes_125=0,Stock_Bombes_250=0,Stock_Bombes_300=0,Stock_Bombes_400=0,Stock_Bombes_500=0,Stock_Bombes_800=0,Stock_Bombes_1000=0,Stock_Bombes_2000=0");
	$ok_up=mysqli_query($con,"UPDATE Pays SET Commandant=NULL,Adjoint_EM=NULL,Officier_EM=NULL,Cdt_Chasse=NULL,Cdt_Bomb=NULL,Cdt_Reco=NULL,Cdt_Atk=NULL,Officier_Rens=NULL,Adjoint_Terre=NULL,Officier_Mer=NULL,Officier_Log=NULL,Base_Arriere=0,
	Lieu_Mission1=0,Lieu_Mission2=0,Lieu_Mission3=0,Lieu_Mission4=0,Lieu_Mission5=0,Lieu_Mission6=0,Lieu_Mission7=0,Lieu_Mission8=0,Lieu_Mission9=0,Lieu_Mission10=0,Lieu_Mission12=0,
	Type_Mission1=0,Type_Mission2=0,Type_Mission3=0,Type_Mission4=0,Type_Mission5=0,Type_Mission6=0,Type_Mission7=0,Type_Mission8=0,Type_Mission9=0,Type_Mission10=0,Type_Mission12=0,
	Co_Heure_Mission=9,Co_Lieu_Mission=0,Pool_ouvriers=100,Score=0,Special_Score=0,lieu_atk1=0,lieu_atk2=0,lieu_def=0");
	/*$ok_up=mysqli_query($con,"UPDATE Event_Historique SET Points_Allies=0, Points_Axe=0 WHERE Type IN (1,2)");
	printf("Lignes mises à jour (Init Points de Campagne) : %d\n", mysqli_affected_rows());*/
	/*$ok_up=mysqli_query($con,"UPDATE Pilote_IA SET Unit=Unit_Ori");
	$ok_up=mysqli_query($con,"UPDATE Pilote_IA SET Actif=1 WHERE Engagement <'1940-05-11'");
	printf("Lignes mises à jour (Pilotes Actifs) : %d\n", mysqli_affected_rows());*/
	$ok_up=mysqli_query($con,"UPDATE Lieu SET Occupant=Pays,Flag=Pays,Flag_Air=0,Flag_Usine=0,Flag_Route=0,Flag_Gare=0,Flag_Port=0,Flag_Pont=0,Flag_Radar=0,Flag_Plage=0,Meteo=0,Meteo_Hour=0,DefenseAA_temp=DefenseAA,BaseAerienne=Base_Ori,NoeudF=NoeudF_Ori,Port=Port_Ori,Pont=Pont_Ori,Radar=Radar_Ori,Camouflage=0,Citernes=0,Camions=0,Fortification=0,Garnison=0,Mines=0,Mines_m=0,Recce=0,Recce_PlayerID=0,Recce_PlayerID_TAL=0,Recce_PlayerID_TAX=0,Last_Attack='0000-00-00',Auto_repare=1,Depot_prive=0,
	Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,
	Stock_Essence_1=0,Stock_Essence_87=0,Stock_Essence_100=0,Stock_Bombes_30=0,Stock_Bombes_50=0,Stock_Bombes_80=0,Stock_Bombes_125=0,Stock_Bombes_250=0,Stock_Bombes_300=0,Stock_Bombes_400=0,Stock_Bombes_500=0,Stock_Bombes_800=0,Stock_Bombes_1000=0,Stock_Bombes_2000=0");
	printf("Lignes mises à jour (Lieux) : %d\n", mysqli_affected_rows());
	$ok_up=mysqli_query($con,"UPDATE Lieu SET Industrie=100,Flag_Usine=Pays WHERE TypeIndus <>''");
	printf("Lignes mises à jour (Usines) : %d\n", mysqli_affected_rows());
	$ok_up=mysqli_query($con,"UPDATE Lieu SET QualitePiste=100,Tour=100,LongPiste=LongPiste_Ori,Flag_Air=Pays WHERE BaseAerienne >0");
	printf("Lignes mises à jour (Pistes) : %d\n", mysqli_affected_rows());
	$ok_up=mysqli_query($con,"UPDATE Avion SET Etat=0 WHERE Engagement >'1940-05-01'");
	printf("Lignes mises à jour (Avions) : %d\n", mysqli_affected_rows());	
	$ok_up=mysqli_query($con,"UPDATE Unit SET Base=Base_Ori,Commandant=NULL,Officier_Technique=NULL,Officier_Adjoint=NULL,Station_Meteo=0,Avion1=Avion1_Ori,Avion2=Avion2_Ori,Avion3=Avion3_Ori,Avion1_Nbr=1,Avion2_Nbr=1,Avion3_Nbr=1,Reputation=0,Porte_avions=0,Garnison=50,
	Stock_Essence_87=10000,Stock_Essence_100=0,Stock_Essence_1=0,Stock_Munitions_8=20000,Stock_Munitions_13=10000,Stock_Munitions_20=10000,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,
	Bombes_30=1000,Bombes_50=0,Bombes_125=0,Bombes_250=0,Bombes_300=0,Bombes_400=0,Bombes_500=0,Bombes_800=0,Bombes_1000=0,Bombes_2000=0,
	Mission_Lieu=0,Mission_Type=0,Mission_alt=5000,Mission_Flight=1,Briefing='',Mission_Lieu_D=0,Mission_Type_D=0,Avion1_Bombe=0,Avion2_Bombe=0,Avion3_Bombe=0,Avion1_Bombe_Nbr=0,Avion2_Bombe_Nbr=0,Avion3_Bombe_Nbr=0,Avion1_BombeT=0,Avion2_BombeT=0,Avion3_BombeT=0,Avion1_Mun1=0,Avion2_Mun1=0,Avion3_Mun1=0,
	Recrutement=1,NoEM=0,Pers1=0,Pers2=0,Pers3=0,Pers4=0,Pers5=0,Pers6=0,Pers7=0,Pers8=0,Pers9=0,Pers10=0,Ravit=0,Hide=0,Mission_IA=0,Recce=0 WHERE Type<>8");
	printf("Lignes mises à jour (Units) : %d\n", mysqli_affected_rows());
	$ok_up=mysqli_query($con,"UPDATE Unit SET Etat=0 WHERE WHERE Active_Date >'1940-05-01'");
	printf("Lignes mises à jour (Unités inactives) : %d\n", mysqli_affected_rows());


	$ok_up=mysqli_query($con,"UPDATE Pilote SET Front=0, Unit=192, Ailier = 0, Credits = 0, Credits_date=NULL, Crashs_Jour = 0, Missions_Jour = 0, Missions_Max = 0,
	Pilotage=10,Navigation=10,Tir=10,Endurance=10,Vue=10,Acrobatie=10,Bombardement=10,Tactique=10,Gestion=10,Commandement=10,Renseignement=10,Duperie=10,Courage=100,Moral=100,Reputation=0,Avion_Perso=0,Equipage=0,
	Missions=0,Victoires=0,Victoires_atk=0,Raids_Bomb=0,Raids_Bomb_Nuit=0,Dive=0,Batailles_Histo=0,Abattu=0,MIA=0,Couverture=0,Escorte=0,Intercept=0,enis=0,avion_eni=0,
	S_Cible_Atk=0,S_Strike=0,S_Escorte=0,S_Escorte_nbr=0,S_Cible=0,S_Avion_db='Avion',S_Avancement_mission=0,S_Engine_Nbr=0,S_Engine_Nbr_eni=0,S_Unite_Intercept=0,S_Nuit=0,S_Mission=0,
	S_Escorteb=0,S_Escorteb_nbr=0,S_Intercept_nbr=0,S_Equipage_Nbr=1,S_Leader=0,S_Ailier=0,S_Avion_Mun=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0,S_Formation=0,S_HP=0,S_Meteo=0,
	medal0=0,medal1=0,medal2=0,medal3=0,medal4=0,medal5=0,medal6=0,medal7=0,medal8=0,medal9=0,medal10=0,medal11=0 WHERE Pays=1");
	$ok_up=mysqli_query($con,"UPDATE Pilote SET Front=0, Unit=193, Ailier = 0, Credits = 0, Credits_date=NULL, Crashs_Jour = 0, Missions_Jour = 0, Missions_Max = 0,
	Pilotage=10,Navigation=10,Tir=10,Endurance=10,Vue=10,Acrobatie=10,Bombardement=10,Tactique=10,Gestion=10,Commandement=10,Renseignement=10,Duperie=10,Courage=100,Moral=100,Reputation=0,Avion_Perso=0,Equipage=0,
	Missions=0,Victoires=0,Victoires_atk=0,Raids_Bomb=0,Raids_Bomb_Nuit=0,Dive=0,Batailles_Histo=0,Abattu=0,MIA=0,Couverture=0,Escorte=0,Intercept=0,enis=0,avion_eni=0,
	S_Cible_Atk=0,S_Strike=0,S_Escorte=0,S_Escorte_nbr=0,S_Cible=0,S_Avion_db='Avion',S_Avancement_mission=0,S_Engine_Nbr=0,S_Engine_Nbr_eni=0,S_Unite_Intercept=0,S_Nuit=0,S_Mission=0,
	S_Escorteb=0,S_Escorteb_nbr=0,S_Intercept_nbr=0,S_Equipage_Nbr=1,S_Leader=0,S_Ailier=0,S_Avion_Mun=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0,S_Formation=0,S_HP=0,S_Meteo=0,
	medal0=0,medal1=0,medal2=0,medal3=0,medal4=0,medal5=0,medal6=0,medal7=0,medal8=0,medal9=0,medal10=0,medal11=0 WHERE Pays_Origine=2");
	$ok_up=mysqli_query($con,"UPDATE Pilote SET Front=0, Unit=414, Ailier = 0, Credits = 0, Credits_date=NULL, Crashs_Jour = 0, Missions_Jour = 0, Missions_Max = 0, Pilotage=10,Navigation=10,Tir=10,Endurance=10,Vue=10,Acrobatie=10,Bombardement=10,Tactique=10,Gestion=10,Commandement=10,Renseignement=10,Duperie=10,Courage=100,Moral=100,Reputation=0,Avion_Perso=0,Equipage=0,Missions=0,Victoires=0,Victoires_atk=0,Raids_Bomb=0,Raids_Bomb_Nuit=0,Dive=0,Batailles_Histo=0,Abattu=0,MIA=0,Couverture=0,Escorte=0,Intercept=0,enis=0,avion_eni=0,S_Cible_Atk=0,S_Strike=0,S_Escorte=0,S_Escorte_nbr=0,S_Cible=0,S_Avion_db='Avion',S_Avancement_mission=0,S_Engine_Nbr=0,S_Engine_Nbr_eni=0,S_Unite_Intercept=0,S_Nuit=0,S_Mission=0,
	S_Escorteb=0,S_Escorteb_nbr=0,S_Intercept_nbr=0,S_Equipage_Nbr=1,S_Leader=0,S_Ailier=0,S_Avion_Mun=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0,S_Formation=0,S_HP=0,S_Meteo=0,medal0=0,medal1=0,medal2=0,medal3=0,medal4=0,medal5=0,medal6=0,medal7=0,medal8=0,medal9=0,medal10=0,medal11=0 WHERE Pays_Origine=3");
	$ok_up=mysqli_query($con,"UPDATE Pilote SET Front=0, Unit=191, Ailier = 0, Credits = 0, Credits_date=NULL, Crashs_Jour = 0, Missions_Jour = 0, Missions_Max = 0, Pilotage=10,Navigation=10,Tir=10,Endurance=10,Vue=10,Acrobatie=10,Bombardement=10,Tactique=10,Gestion=10,Commandement=10,Renseignement=10,Duperie=10,Courage=100,Moral=100,Reputation=0,Avion_Perso=0,Equipage=0,Missions=0,Victoires=0,Victoires_atk=0,Raids_Bomb=0,Raids_Bomb_Nuit=0,Dive=0,Batailles_Histo=0,Abattu=0,MIA=0,Couverture=0,Escorte=0,Intercept=0,enis=0,avion_eni=0,S_Cible_Atk=0,S_Strike=0,S_Escorte=0,S_Escorte_nbr=0,S_Cible=0,S_Avion_db='Avion',S_Avancement_mission=0,S_Engine_Nbr=0,S_Engine_Nbr_eni=0,S_Unite_Intercept=0,S_Nuit=0,S_Mission=0,
	S_Escorteb=0,S_Escorteb_nbr=0,S_Intercept_nbr=0,S_Equipage_Nbr=1,S_Leader=0,S_Ailier=0,S_Avion_Mun=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0,S_Formation=0,S_HP=0,S_Meteo=0,medal0=0,medal1=0,medal2=0,medal3=0,medal4=0,medal5=0,medal6=0,medal7=0,medal8=0,medal9=0,medal10=0,medal11=0 WHERE Pays_Origine=4");
	$ok_up=mysqli_query($con,"UPDATE Pilote SET Front=2, Unit=194, Ailier = 0, Credits = 0, Credits_date=NULL, Crashs_Jour = 0, Missions_Jour = 0, Missions_Max = 0,
	Pilotage=10,Navigation=10,Tir=10,Endurance=10,Vue=10,Acrobatie=10,Bombardement=10,Tactique=10,Gestion=10,Commandement=10,Renseignement=10,Duperie=10,Courage=100,Moral=100,Reputation=0,Avion_Perso=0,Equipage=0,
	Missions=0,Victoires=0,Victoires_atk=0,Raids_Bomb=0,Raids_Bomb_Nuit=0,Dive=0,Batailles_Histo=0,Abattu=0,MIA=0,Couverture=0,Escorte=0,Intercept=0,enis=0,avion_eni=0,
	S_Cible_Atk=0,S_Strike=0,S_Escorte=0,S_Escorte_nbr=0,S_Cible=0,S_Avion_db='Avion',S_Avancement_mission=0,S_Engine_Nbr=0,S_Engine_Nbr_eni=0,S_Unite_Intercept=0,S_Nuit=0,S_Mission=0,
	S_Escorteb=0,S_Escorteb_nbr=0,S_Intercept_nbr=0,S_Equipage_Nbr=1,S_Leader=0,S_Ailier=0,S_Avion_Mun=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0,S_Formation=0,S_HP=0,S_Meteo=0,
	medal0=0,medal1=0,medal2=0,medal3=0,medal4=0,medal5=0,medal6=0,medal7=0,medal8=0,medal9=0,medal10=0,medal11=0 WHERE Pays=6");
	
	$ok_up=mysqli_query($con,"UPDATE Pilote SET Avancement=4999 WHERE Avancement >4999");
	//End REINIT
	//$ok_up=mysqli_query($con,"DELETE FROM Pilote WHERE Actif=1");
	

	
		//REINIT
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Avions_Persos');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Avions_Sandbox');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Attaque');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Attaque_ia');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Bombardement');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Chasse');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Chasse_Probable');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE DCA');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Duels_Candidats');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Equipage');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Escorte');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Flak');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Intercept');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Officier');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Officier_em');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Officier_PVP');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Parachutages');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Patrouille');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Patrouille_live');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Ravitaillements');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Recce');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE Sauvetage');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE XP_Avions');
	$ok_up=mysqli_query($con, 'TRUNCATE TABLE XP_Avions_IA');
	mysqli_close($con);
	$con=dbconnecti(2);
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Connectes');
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Porte_Monnaie');
	mysqli_close($con);
	$con=dbconnecti(3);
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Chat');
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Messages');
	mysqli_close($con);
	$con=dbconnecti(4);
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Events'); //Peut être reinit régulièrement
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Events_em'); //Peut être reinit régulièrement
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Events_Feed'); //Peut être reinit régulièrement
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Events_Ground'); //Peut être reinit régulièrement
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Events_Ground_Stats');
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Events_Pertes');
	$ok_up=mysqli_query($con,'TRUNCATE TABLE Events_ravit'); //Peut être reinit régulièrement
	mysqli_close($con);
	printf("Lignes effacées (Classements) : %d\n", mysqli_affected_rows())."<br>";

	//alter table tbl auto_increment = 16*/

}
else
{
	echo "<center><h1>Vous devez être connecté pour accéder à cette page!</h1></center>";
}
?>