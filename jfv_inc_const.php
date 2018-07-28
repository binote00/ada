<?php
	$Date_debut="2016-12-26";
	$Date_cr_em="2016-12-26"; //Date création officier EM (login.php)
	$Date_inactif_off="2016-12-26"; //Date d'inactivité des officiers (tools.php)
	$Date_inactif_em="2017-05-15"; //Date d'inactivité des officiers EM (db_pilotes.php)
	$Date_msg_db="2016-12-26"; //Date du début des messages provenant de la DB (envois.php, ground_envois.php)
	$Date_logs_esc="2016-12-26";  //Date du début des logs escadrilles provenant de la DB (esc_journal.php)
	$G_Treve=false; //Trève sur tous les fronts
    $G_Treve_Med=false; //Trève sur le front Med
    $G_Treve_Est_Pac=false; //Trève sur les fronts Est & Pac
	$CT_MAX=50;
	//$Transit_cities=array(189,198,199,201,218,344,586,603,704,709,898,2079);
	$Transit_cities=array(2,189,198,199,201,218,344,586,603,614,615,619,621,704,722,898,959,967,1280,1567,1577,1600,1896,2079,2149,2732);
	$Nations_IA=array(3,5,10,15,17,18,19,20,35);
	$Flag_includes=true;

    $Heure=date("H");
    if($Heure >1 and $Heure <6)
        $Canada=true;
    else
        $Canada=false;

	//CONST
    define('EMAIL_LOG', 'binote@hotmail.com');

    //Countries
	define('DE','1');
	define('UK','2');
	define('BEL','3');
	define('FRA','4');
	define('NL','5');
	define('ITA','6');
	define('USA','7');
	define('URSS','8');
	define('JAP','9');
	define('GRE','10');
	define('BUL','15');
	define('YOU','17');
	define('ROU','18');
	define('HUN','19');
	define('FIN','20');
	define('NEUTRE','23');
	define('ALB','24');
	define('NOR','35');
	define('LUX','36');

	//Factions
	define('AXE', 1);
	define('ALLIES', 2);

	//Front
    define('FRONT_OUEST','0');
    define('FRONT_EST','1');
    define('FRONT_MED','2');
    define('FRONT_PAC','3');
    define('FRONT_NORD','4');
    define('FRONT_ARCTIC','5');

	//Avion_Type
	define('AVION_CHASSE','1');
	define('AVION_BOMBARDIER','2');
	define('AVION_RECO','3');
	define('AVION_CHASSE_LD','4');
	define('AVION_TRANSPORT','6');
	define('AVION_ATTAQUE','7');
	define('ENTRAINEMENT','8');
	define('AVION_PAT_MAR','9');
	define('AVION_EMBARQUE','10');
	define('AVION_BOMB_LD','11');
	define('AVION_CHASSE_EMB','12');
	
	//Veh_Type
	define('TYPE_TRUCK','1');
	define('TYPE_HALF_TRACK','2');
	define('TYPE_ARM_CAR','3');
	define('TYPE_AT_GUN','4');
	define('TYPE_BL_LG','5');
	define('TYPE_ART','6');
	define('TYPE_BL_MY','7');
	define('TYPE_ART_MOB','8');
	define('TYPE_TANK_DES','9');
	define('TYPE_BL_LD','10');
	define('TYPE_VEH_AA','11');
	define('TYPE_DCA','12');
	define('TYPE_LOCO','13');
    define('TYPE_CV','21');
    define('TYPE_SUB','37');

    //Veh_Cat
    define('CAT_TRUCK','1');
    define('CAT_BL_LG','2');
    define('CAT_TANK','3');
    define('CAT_CDT_CAR','4');
    define('CAT_INF','5');
    define('CAT_MG','6');
    define('CAT_CAV','7');
    define('CAT_ART','8');
    define('CAT_AT_GUN','9');
    define('CAT_LOCO','13');
    define('CAT_DCA','15');
    define('CAT_SUB','17');
    define('CAT_LAND','18');
    define('CAT_MINE','19');
    define('CAT_BB','20');
    define('CAT_CV','21');
    define('CAT_DD','22');
    define('CAT_CL','23');
    define('CAT_CA','24');

    //mobile
    define('MOBILE_WHEEL', 1);
    define('MOBILE_TRACK', 2);
    define('MOBILE_FOOT', 3);
    define('MOBILE_RAIL', 4);
    define('MOBILE_WATER', 5);

    //Missions
    define('MISSION_APPUI', '1');
    define('MISSION_BOMB_TAC', '2');
    define('MISSION_CHASSE_LIBRE', '3');
    define('MISSION_ESCORTE', '4');
    define('MISSION_RECO_TAC', '5');
    define('MISSION_ATTAQUE', '6');
    define('MISSION_PAT_DEF', '7');
    define('MISSION_BOMB_STRAT', '8');
    define('MISSION_INTERCEPT', '9');
    define('MISSION_ATTAQUE_NAVAL', '11');
    define('MISSION_BOMB_NAVAL', '12');
    define('MISSION_TORPILLAGE', '13');
    define('MISSION_MOUILLAGE', '14');
    define('MISSION_RECO_STRAT', '15');
    define('MISSION_BOMB_STRAT_NUIT', '16');
    define('MISSION_CHASSE_NUIT', '17');
    define('MISSION_SAUVETAGE', '18');
    define('MISSION_SAUVETAGE_MER', '19');
    define('MISSION_PATHFINDER', '21');
    define('MISSION_SAUVETAGE_NUIT', '22');
    define('MISSION_RAVITAILLEMENT', '23');
    define('MISSION_PARACHUTAGE', '24');
    define('MISSION_PARACHUTAGE_NUIT', '25');
    define('MISSION_SUP_AERIENNE', '26');
    define('MISSION_PARACHUTAGE_CDO', '27');
    define('MISSION_INFILTRATION_CDO', '28');
    define('MISSION_PAT_ASM', '29');
    define('MISSION_INTRUDER', '31');
    define('MISSION_VEILLE', '32');

	//Placement
    define('PLACE_CASERNE', '0');
    define('PLACE_AERODROME', '1');
    define('PLACE_ROUTE', '2');
    define('PLACE_GARE', '3');
    define('PLACE_PORT', '4');
    define('PLACE_PONT', '5');
    define('PLACE_USINE', '6');
    define('PLACE_RADAR', '7');
    define('PLACE_LARGE', '8');
    define('PLACE_PLAGE', '11');

	//Positions
    define('POS_DEFENSIVE','1');
    define('POS_RETRANCHE','2');
    define('POS_EMBUSCADE','3');
    define('POS_MOVE','4');
    define('POS_APPUI','5');
    define('POS_DEROUTE','6');
    define('POS_ENCERCLE','7');
    define('POS_SOUS_LE_FEU','8');
    define('POS_CLOUE_AU_SOL','9');
    define('POS_EN_LIGNE','10');
    define('POS_TRANSIT','11');
    define('POS_SENTINELLE','14');
    define('POS_EVASION','22');

