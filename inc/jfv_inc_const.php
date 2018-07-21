<?php
$Date_debut = "2016-12-26";
$Date_cr_em = "2016-12-26"; //Date création officier EM (login.php)
$Date_inactif_off = "2016-12-26"; //Date d'inactivité des officiers (tools.php)
$Date_inactif_em = "2017-05-15"; //Date d'inactivité des officiers EM (db_pilotes.php)
$Date_msg_db = "2016-12-26"; //Date du début des messages provenant de la DB (envois.php, ground_envois.php)
$Date_logs_esc = "2016-12-26";  //Date du début des logs escadrilles provenant de la DB (esc_journal.php)
$G_Treve = false; //Trève sur tous les fronts
$G_Treve_Med = false; //Trève sur le front Med
$G_Treve_Est_Pac = false; //Trève sur les fronts Est & Pac
$CT_MAX = 50;
//$Transit_cities=array(189,198,199,201,218,344,586,603,704,709,898,2079);
$Transit_cities = array(2, 189, 198, 199, 201, 218, 344, 586, 603, 614, 615, 619, 621, 704, 722, 898, 959, 967, 1280, 1567, 1577, 1600, 1896, 2079, 2149, 2732);
$Nations_IA = array(3, 5, 10, 15, 17, 18, 19, 20, 35);
$Flag_includes = true;

$Heure = date("H");
if ($Heure > 1 and $Heure < 6)
    $Canada = true;
else
    $Canada = false;

//CONST
define('EMAIL_LOG', 'binote@hotmail.com');

//Countries
define('DE', '1');
define('UK', '2');
define('BEL', '3');
define('FRA', '4');
define('NL', '5');
define('ITA', '6');
define('USA', '7');
define('URSS', '8');
define('JAP', '9');
define('GRE', '10');
define('BUL', '15');
define('YOU', '17');
define('ROU', '18');
define('HUN', '19');
define('FIN', '20');
define('NEUTRE', '23');
define('ALB', '24');
define('NOR', '35');
define('LUX', '36');

//Front
define('FRONT_OUEST', '0');
define('FRONT_EST', '1');
define('FRONT_MED', '2');
define('FRONT_PAC', '3');
define('FRONT_NORD', '4');
define('FRONT_ARCTIC', '5');

//Avion_Type
define('AVION_CHASSE', '1');
define('AVION_BOMBARDIER', '2');
define('AVION_RECO', '3');
define('AVION_CHASSE_LD', '4');
define('AVION_TRANSPORT', '6');
define('AVION_ATTAQUE', '7');
define('ENTRAINEMENT', '8');
define('AVION_PAT_MAR', '9');
define('AVION_EMBARQUE', '10');
define('AVION_BOMB_LD', '11');
define('AVION_CHASSE_EMB', '12');

//Veh_Type
define('TYPE_TRUCK', '1');
define('TYPE_HALF_TRACK', '2');
define('TYPE_ARM_CAR', '3');
define('TYPE_AT_GUN', '4');
define('TYPE_BL_LG', '5');
define('TYPE_ART', '6');
define('TYPE_BL_MY', '7');
define('TYPE_ART_MOB', '8');
define('TYPE_TANK_DES', '9');
define('TYPE_BL_LD', '10');
define('TYPE_VEH_AA', '11');
define('TYPE_DCA', '12');
define('TYPE_LOCO', '13');

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
define('POS_DEFENSIVE', '1');
define('POS_RETRANCHE', '2');
define('POS_EMBUSCADE', '3');
define('POS_MOVE', '4');
define('POS_APPUI', '5');
define('POS_DEROUTE', '6');
define('POS_ENCERCLE', '7');
define('POS_SOUS_LE_FEU', '8');
define('POS_CLOUE_AU_SOL', '9');
define('POS_EN_LIGNE', '10');
define('POS_TRANSIT', '11');
define('POS_SENTINELLE', '14');
define('POS_EVASION', '22');

