<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_combat.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_infos.php');

$avion=Insec($_POST['avion']);
$avion_eni=Insec($_POST['avion_eni']);
$meteo=Insec($_POST['meteo']);
$alt=Insec($_POST['alt']);
$Pilote_eni=Insec($_POST['eni']);

if(isset($_SESSION['login']) AND isset($_SESSION['pwd'])  AND !empty($_POST))
{
	$Pilot1_win = 0;
	$Pilot2_win = 0;
	$Pilot1_total = 0;
	$Pilot2_total = 0;
	$Pilot91_win = 0;
	$Pilot92_win = 0;
	$Pilot91_total = 0;
	$Pilot92_total = 0;
	/*function addWin($Score1, $Score2)
	{
		//global $Pilot1_win;
		//global $Pilot2_win;
		
		if($Score1 >= $Score2)
		{
			$Pilot1_win +=1;
		}
		else
		{
			$Pilot2_win +=1;
		}
	}*/

	$Pilote_db = "Pilote";
	//$Pilote_db = "Joueur";
	//$Pilote_eni = $PlayerID;
	
	$moda = 1;
	$malus_incident = 1;
	$moda_eni = 1;
	$malus_incident_eni = 1;
	$Avion_db = "Avion";
	$Avion_db_eni = "Avion";
	$HP = GetData($Avion_db,"ID",$avion,"Robustesse");
	$HP_eni = GetData("Avion","ID",$avion_eni,"Robustesse");
	
	$Avion_Nom = GetData($Avion_db,"ID",$avion,"Nom");
	$Verriere = GetData($Avion_db,"ID",$avion,"Verriere");
	$DetAvion = GetData($Avion_db,"ID",$avion,"Detection");
	$Injection = GetData($Avion_db,"ID",$avion,"Injection");
	$Engine_Nbr = GetData($Avion_db,"ID",$avion,"Engine_Nbr");
	$PuissAvion = GetPuissance($Avion_db,$avion,$alt,$HP,$moda,$malus_incident,$Engine_Nbr);
	$ManiAvion = GetMani($Avion_db,$avion,$HP,$moda,$malus_incident);
	$ManAvion = GetMan($Avion_db,$avion,$alt,$HP,$moda,$malus_incident);
	$VitAvion = GetSpeed($Avion_db,$avion,$alt,$meteo,$moda,$malus_incident);
	$VitAAvion = GetSpeedA($Avion_db,$avion,$alt,$meteo,$Engine_Nbr,$moda,$malus_incident);

	$Pilotage=GetPilotage($Avion_db, $PlayerID, $avion);	
	$Acrobatie=GetData("Joueur","ID",$PlayerID,"Acrobatie");
	$Moral=GetData("Joueur","ID",$PlayerID,"Moral");
	$Courage=GetData("Joueur","ID",$PlayerID,"Courage");
	$Tactique=GetData("Joueur","ID",$PlayerID,"Tactique");
	
	//Avion eni
	$Avion_eni_Nom = GetData($Avion_db_eni,"ID",$avion_eni,"Nom");
	$ManiAvion_eni = GetMani($Avion_db_eni,$avion_eni,$HP_eni,$moda_eni,$malus_incident_eni);
	$Engine_Nbr_eni = GetData($Avion_db_eni,"ID",$avion_eni,"Engine_Nbr");
	$PuissAvioneni = GetPuissance($Avion_db_eni,$avion_eni,$alt,$HP_eni,$moda_eni,$malus_incident_eni,$Engine_Nbr_eni);
	$ManAvion_eni = GetMan($Avion_db_eni,$avion_eni,$alt,$HP_eni,$moda_eni,$malus_incident_eni);	
	$VitAvioneni = GetSpeed($Avion_db_eni,$avion_eni,$alt,$meteo,$moda_eni,$malus_incident_eni);
	$VitAAvioneni = GetSpeedA($Avion_db_eni,$avion_eni,$alt,$meteo,$Engine_Nbr_eni,$moda_eni,$malus_incident_eni);
	$Verriere_eni = GetData($Avion_db_eni,"ID",$avion_eni,"Verriere");
	$DetAvion_eni = GetData($Avion_db_eni,"ID",$avion_eni,"Detection");
	$Injection_eni = GetData($Avion_db_eni,"ID",$avion_eni,"Injection");

	$Pilotage_eni = GetData($Pilote_db,"ID",$Pilote_eni,"Pilotage");
	$Acrobatie_eni = GetData($Pilote_db,"ID",$Pilote_eni,"Acrobatie");
	$Tactique_eni = GetData($Pilote_db,"ID",$Pilote_eni,"Tactique");

	$Solo = false;
	$u = 0;
	for($u = 1; $u < 500; $u++)
	{
		$Pilot1_win = 0;
		$Pilot2_win = 0;
		$Pilot91_win = 0;
		$Pilot92_win = 0;
		//Mission3

		//6h/Avantage (+Evade.php)
		$Pilot1 = mt_rand(0,$Pilotage) + $meteo + ($ManAvion*2) - ($PuissAvion/3) + ($Moral/10) + mt_rand(0,$Tactique) + ($Verriere*10) + $DetAvion;
		$Pilot_eni1 = mt_rand(0,$Pilotage_eni) + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + mt_rand(10,$Tactique_eni) + ($Verriere_eni*10)+ $DetAvion_eni + ($Enis*10);
		$Pilot91 = $Pilotage + $meteo + ($ManAvion*2) - ($PuissAvion/3) + ($Verriere*10) + $DetAvion;
		$Pilot_eni91 = $Pilotage_eni + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + ($Verriere_eni*10)+ $DetAvion_eni;
		
		if($Solo)
		{
			$ManAvion_txt = $ManAvion*2;
			$ManAvion_eni_txt = $ManAvion_eni*2;
			$PuissAvion_txt = $PuissAvion/3;
			$PuissAvioneni_txt = $PuissAvioneni/3;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>6h / Avantage</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Avion</td><td>".$Avion_Nom."</td><td>".$Avion_eni_Nom."</td></tr>
				<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
				<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
				<tr><th>Total</th><th>".$Pilot1."</th><th>".$Pilot_eni1."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot91."</th><th>".$Pilot_eni91."</th></tr>
			</table>";
		}
		
		//Acrobatie/Flanc
		$Pilot2 = mt_rand(0,$Acrobatie) + $meteo + ($ManAvion*5) - ($PuissAvion/3) + ($ManiAvion) + ($Moral/10) + mt_rand(0,$Tactique) + ($Verriere*10) + $DetAvion;
		$Pilot_eni2 = mt_rand(0,$Acrobatie_eni) + $meteo + ($ManAvion_eni*5) - ($PuissAvioneni/3) + ($ManiAvion_eni) + mt_rand(10,$Tactique_eni) + ($Verriere_eni*10) + $DetAvion_eni + ($Enis*10);
		$Pilot92 = $Acrobatie + $meteo + ($ManAvion*5) - ($PuissAvion/3) + ($ManiAvion) + $Tactique + ($Verriere*10) + $DetAvion;
		$Pilot_eni92 = $Acrobatie_eni + $meteo + ($ManAvion_eni*5) - ($PuissAvioneni/3) + ($ManiAvion_eni) + $Tactique_eni + ($Verriere_eni*10) + $DetAvion_eni;
		
		if($Solo)
		{
			$ManAvion_txt = $ManAvion*5;
			$ManAvion_eni_txt = $ManAvion_eni*5;
			$PuissAvion_txt = $PuissAvion/3;
			$PuissAvioneni_txt = $PuissAvioneni/3;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Flanc / Avantage</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
				<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
				<tr><td>Acrobatie</td><td>".$Acrobatie."</td><td>".$Acrobatie_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
				<tr><th>Total</th><th>".$Pilot2."</th><th>".$Pilot_eni2."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot92."</th><th>".$Pilot_eni92."</th></tr>
			</table>";
		}						
		
		//Fuite Piqué (+Evade.php)
		$Pilot3 = mt_rand(0,$Pilotage) + $meteo - ($PuissAvion) + ($VitAvion*2) + ($Moral/10) + ($Injection*50);
		$Pilot_eni3 = mt_rand(0,$Pilotage_eni) + $meteo - ($PuissAvioneni) + ($VitAvioneni*2) + ($Injection_eni*50);
		$Pilot93 = $Pilotage + $meteo - $PuissAvion + ($VitAvion*2) + ($Injection*50);
		$Pilot_eni93 = $Pilotage_eni + $meteo - $PuissAvioneni + ($VitAvioneni*2) + ($Injection_eni*50);
		
		if($Solo)
		{
			$PuissAvion_txt = $VitAvion*2;
			$PuissAvioneni_txt = $VitAvioneni*2;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Fuite (piqué)</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Vitesse</td><td>".$PuissAvion_txt."</td><td>".$PuissAvioneni_txt."</td></tr>
				<tr><td>Injection * 50</td><td>".$Injection."</td><td>".$Injection_eni."</td></tr>
				<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion."</td><td>-".$PuissAvioneni."</td></tr>
				<tr><th>Total</th><th>".$Pilot3."</th><th>".$Pilot_eni3."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot93."</th><th>".$Pilot_eni93."</th></tr>
			</table>";
		}
		
		//Nuages
		$Pilot4 = mt_rand(0,$Pilotage) - $meteo + $ManAvion + $ManiAvion - ($PuissAvion/3) + $VitAvion + ($Moral/10) + ($Courage/10) + mt_rand(0,$Tactique);
		$Pilot_eni4 = mt_rand(0,$Pilotage_eni) + $meteo + $ManAvion_eni - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(0,$Tactique_eni) + $Radar_eni;
		$Pilot94 = $Pilotage - $meteo + $ManAvion + $ManiAvion - ($PuissAvion/3) + $VitAvion;
		$Pilot_eni94 = $Pilotage_eni + $meteo + $ManAvion_eni - ($PuissAvioneni/3) + $VitAvioneni;

		if($Solo)
		{
			$PuissAvion_txt = $PuissAvion/3;
			$PuissAvioneni_txt = $PuissAvioneni/3;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Fuite dans les nuages</th></tr>
				<tr><td colspan='3'>Bonus : Moral + Courage</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
				<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
				<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
				<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
				<tr><th>Total</th><th>".$Pilot4."</th><th>".$Pilot_eni4."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot94."</th><th>".$Pilot_eni94."</th></tr>
			</table>";
		}
		
		//Fuite Manoeuvre (+Evade.php)
		$Pilot5 = mt_rand(0,$Pilotage*2) + $meteo + $ManAvion + $ManiAvion - ($PuissAvion/3) + $VitAvion + ($Moral/10) + mt_rand(0,$Tactique);
		$Pilot_eni5 = mt_rand(0,$Pilotage_eni*2) + $meteo + $ManAvion_eni - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(0,$Tactique_eni);
		$Pilot95 = $Pilotage*2 + $meteo + $ManAvion + $ManiAvion - ($PuissAvion/3) + $VitAvion;
		$Pilot_eni95 = $Pilotage_eni*2 + $meteo + $ManAvion_eni - ($PuissAvioneni/3) + $VitAvioneni;

		if($Solo)
		{
			$PuissAvion_txt = $PuissAvion/3;
			$PuissAvioneni_txt = $PuissAvioneni/3;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Fuite Manoeuvre</th></tr>
				<tr><td colspan='3'>Bonus : Moral + Roulis</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
				<tr><td>Roulis</td><td>".$ManiAvion."</td><td>-Bonus-</td></tr>
				<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
				<tr><td>Pilotage (x2)</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
				<tr><th>Total</th><th>".$Pilot5."</th><th>".$Pilot_eni5."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot95."</th><th>".$Pilot_eni95."</th></tr>
			</table>";
		}
						
		//Fuite Grimper (+Evade.php)
		$Pilot6 = mt_rand(0,$Pilotage) + $meteo - $PuissAvion + $VitAAvion;
		$Pilot_eni6 = mt_rand(0,$Pilotage_eni) + $meteo - $PuissAvioneni + $VitAAvioneni;
		$Pilot96 = $Pilotage + $meteo - $PuissAvion + $VitAAvion;
		$Pilot_eni96 = $Pilotage_eni + $meteo - $PuissAvioneni + $VitAAvioneni;
		
		if($Solo)
		{
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Fuite en Grimpant</th></tr>
				<tr><td colspan='3'>Bonus : Aucun</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Vitesse</td><td>".$VitAAvion."</td><td>".$VitAAvioneni."</td></tr>
				<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni." (max)</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion."</td><td>-".$PuissAvioneni."</td></tr>
				<tr><th>Total</th><th>".$Pilot6."</th><th>".$Pilot_eni6."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot96."</th><th>".$Pilot_eni96."</th></tr>
			</table>";
		}

		//Coiffer				
		$Pilot7 = mt_rand(0,$Pilotage) + $meteo - $PuissAvion + $VitAAvion + $VitAvion  + ($Moral/10) + mt_rand(10,$Tactique*2);
		$Pilot_eni7 = mt_rand(0,$Pilotage_eni) + $meteo - $PuissAvioneni + ($VitAvioneni*2) + ($Verriere_eni*10) + mt_rand(10,$Tactique_eni*2);
		$Pilot97 = $Pilotage + $meteo - $PuissAvion + $VitAAvion + $VitAvion + ($Tactique*2);
		$Pilot_eni97 = $Pilotage_eni + $meteo - $PuissAvioneni + ($VitAvioneni*2) + ($Verriere_eni*10) + ($Tactique*2);
		
		if($Solo)
		{
			$PuissAvion_txt = $PuissAvion;
			$PuissAvioneni_txt = $PuissAvioneni;
			$VitAAvion_txt = $VitAAvion;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Coiffer</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Vitesse</td><td>".$VitAAvion_txt."</td><td>".$VitAvioneni."</td></tr>
				<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni." (max)</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
				<tr><th>Total</th><th>".$Pilot7."</th><th>".$Pilot_eni7."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot97."</th><th>".$Pilot_eni97."</th></tr>
			</table>";
		}
		
		//Frontale
		$Pilot8 = mt_rand(0,$Acrobatie) + $meteo + ($ManAvion*3) - $PuissAvion + $ManiAvion + ($Moral/10) + $VitAvion + ($Tactique/5);
		$Pilot_eni8 = mt_rand(0,$Pilotage_eni) + $meteo + ($ManAvion_eni*3) - $PuissAvioneni + $ManiAvion_eni + $VitAvioneni + ($Tactique_eni/5);
		$Pilot98 = $meteo + ($ManAvion*3) - $PuissAvion + $ManiAvion + $VitAvion + ($Tactique/5);
		$Pilot_eni98 = $meteo + ($ManAvion_eni*3) - $PuissAvioneni + $ManiAvion_eni + $VitAvioneni + ($Tactique_eni/5);
		
		if($Solo)
		{
			$ManAvion_txt = $ManAvion*3;
			$ManAvion_eni_txt = $ManAvion_eni*3;
			$Tactique_txt = $Tactique/5;
			$Tactique_eni_txt = $Tactique_eni/5;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Attaque Frontale</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
				<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
				<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
				<tr><td>Pilotage</td><td>".$Acrobatie." (A)</td><td>".$Pilotage_eni." (P)</td></tr>
				<tr><td>Tactique</td><td>".$Tactique_txt."</td><td>".$Tactique_eni_txt."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion."</td><td>-".$PuissAvioneni."</td></tr>
				<tr><th>Total</th><th>".$Pilot8."</th><th>".$Pilot_eni8."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot98."</th><th>".$Pilot_eni98."</th></tr>
			</table>";
		}
		
		//Ventre
		$Pilot9 = mt_rand(0,$Pilotage) + $meteo - ($PuissAvion/3) + ($ManiAvion) + ($Moral/10) + mt_rand(0,$Tactique);
		$Pilot_eni9 = mt_rand(0,$Pilotage_eni) + $meteo - ($PuissAvioneni/3) + ($ManiAvion_eni) + mt_rand(10,$Tactique_eni) + ($Enis*10);
		$Pilot99 = $Pilotage + $meteo - ($PuissAvion/3) + ($ManiAvion);
		$Pilot_eni99 = $Pilotage_eni + $meteo - ($PuissAvioneni/3) + ($ManiAvion_eni);

		if($Solo)
		{
			$PuissAvion_txt = $PuissAvion/3;
			$PuissAvioneni_txt = $PuissAvioneni/3;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Ventre / Avantage</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
				<tr><td>Acrobatie</td><td>".$Acrobatie."</td><td>".$Acrobatie_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
				<tr><th>Total</th><th>".$Pilot9."</th><th>".$Pilot_eni9."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot99."</th><th>".$Pilot_eni99."</th></tr>
			</table>";	
		}
						
		//Evade
			
		//Protéger le leader
		$Pilot10 = mt_rand(0,$Pilotage) + $meteo + $ManAvion - $PuissAvion + ($Moral/10) + $Tactique;
		$Pilot_eni10 = mt_rand(0,$Pilotage_eni) + $meteo + $ManAvion_eni - $PuissAvioneni + $Tactique_eni + ($Enis*10);
		
		//Overshoot
		$Pilot11 = mt_rand(0,$Acrobatie) + $meteo + ($ManAvion*2) - ($PuissAvion/3) + ($ManiAvion*3) + mt_rand(0,$Tactique);
		$Pilot_eni11 = mt_rand(0,$Acrobatie_eni) + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + ($ManiAvion_eni*3) + mt_rand(10,$Tactique_eni);
		$Pilot911 = $Acrobatie + $meteo + ($ManAvion*2) - ($PuissAvion/3) + ($ManiAvion*3);
		$Pilot_eni911 = $Acrobatie_eni + $meteo + ($ManAvion_eni*2) - ($PuissAvioneni/3) + ($ManiAvion_eni*3);

		if($Solo)
		{
			$PuissAvion_txt = $PuissAvion/3;
			$PuissAvioneni_txt = $PuissAvioneni/3;
			$ManAvion_txt = $ManAvion*2;
			$ManAvion_eni_txt = $ManAvion_eni*2;
			$ManiAvion_txt = $ManiAvion*3;
			$ManiAvion_eni_txt = $ManiAvion_eni*3;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Overshoot</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Virage</td><td>".$ManAvion_txt."</td><td>".$ManAvion_eni_txt."</td></tr>
				<tr><td>Roulis</td><td>".$ManiAvion_txt."</td><td>".$ManiAvion_eni_txt."</td></tr>
				<tr><td>Acrobatie</td><td>".$Acrobatie."</td><td>".$Pilotage_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
				<tr><th>Total</th><th>".$Pilot11."</th><th>".$Pilot_eni11."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot911."</th><th>".$Pilot_eni911."</th></tr>
			</table>";
		}
		
		//Immelmann
		$Pilot12 = mt_rand(0,$Acrobatie*2) + $meteo + $ManAvion + ($ManiAvion*2) - ($PuissAvion/3) + $VitAvion + ($Moral/10) + mt_rand(0,$Tactique);
		$Pilot_eni12 = mt_rand(0,$Pilotage_eni*2) + $meteo + $ManAvion_eni + ($ManiAvion_eni*2) - ($PuissAvioneni/3) + $VitAvioneni + mt_rand(0,$Tactique_eni);
		$Pilot912 = $Acrobatie*2 + $meteo + $ManAvion + ($ManiAvion*2) - ($PuissAvion/3) + $VitAvion;
		$Pilot_eni912 = $Pilotage_eni*2 + $meteo + $ManAvion_eni + ($ManiAvion_eni*2) - ($PuissAvioneni/3) + $VitAvioneni;
		
		if($Solo)
		{
			$PuissAvion_txt = $PuissAvion/3;
			$PuissAvioneni_txt = $PuissAvioneni/3;
			$ManiAvion_txt = $ManiAvion*2;
			$ManiAvion_eni_txt = $ManiAvion_eni*2;
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Immelmann</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
				<tr><td>Roulis</td><td>".$ManiAvion_txt."</td><td>".$ManiAvion_eni_txt."</td></tr>
				<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
				<tr><td>Acrobatie (x2)</td><td>".$Acrobatie."</td><td>".$Acrobatie_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$PuissAvion_txt."</td><td>-".$PuissAvioneni_txt."</td></tr>
				<tr><th>Total</th><th>".$Pilot12."</th><th>".$Pilot_eni12."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot912."</th><th>".$Pilot_eni912."</th></tr>
			</table>";
		}
		
		//Shoot
		
		//Se rapprocher
		$Pilot13 = mt_rand(0,$Pilotage*2) + $meteo + $ManAvion + ($VitAvion*2) - $Puissance + $ManiAvion + mt_rand(0,$Tactique*3);
		$Pilot_eni13 = mt_rand(0,$Pilotage_eni*2) + $meteo + $ManAvion_eni + ($VitAvioneni*2) - $PuissAvioneni + $ManiAvion_eni + mt_rand(0,$Tactique_eni*3);
		$Pilot913 = $Pilotage + $meteo + $ManAvion + ($VitAvion*2) - $Puissance + $ManiAvion;
		$Pilot_eni913 = $Pilotage_eni + $meteo + $ManAvion_eni + ($VitAvioneni*2) - $PuissAvioneni + $ManiAvion_eni;
		
		if($Solo)
		{
			$skills.="
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Se rapprocher</th></tr>
				<tr><td colspan='3'>Bonus : Moral</td></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><td>Virage</td><td>".$ManAvion."</td><td>".$ManAvion_eni."</td></tr>
				<tr><td>Roulis</td><td>".$ManiAvion."</td><td>".$ManiAvion_eni."</td></tr>
				<tr><td>Vitesse</td><td>".$VitAvion."</td><td>".$VitAvioneni."</td></tr>
				<tr><td>Pilotage</td><td>".$Pilotage."</td><td>".$Pilotage_eni."</td></tr>
				<tr><td>Tactique</td><td>".$Tactique."</td><td>".$Tactique_eni."</td></tr>
				<tr><td>Puissance</td><td>-".$Puissance."</td><td>-".$PuissAvioneni."</td></tr>
				<tr><th>Total</th><th>".$Pilot13."</th><th>".$Pilot_eni13."</th></tr>
				<tr><th>Skills Max</th><th>".$Pilot913."</th><th>".$Pilot_eni913."</th></tr>
			</table>";
		}

		//echo $skills;
		
		if($Pilot91 >= $Pilot_eni91)
		{
			$Pilot91_win +=1;
			$Pilot91_win_6h +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_6h +=1;
		}
		if($Pilot92 >= $Pilot_eni92)
		{
			$Pilot91_win +=1;
			$Pilot91_win_flanc +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_flanc +=1;
		}
		if($Pilot93 >= $Pilot_eni93)
		{
			$Pilot91_win +=1;
			$Pilot91_win_pique +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_pique +=1;
		}
		if($Pilot94 >= $Pilot_eni94)
		{
			$Pilot91_win +=1;
			$Pilot91_win_nuages +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_nuages +=1;
		}
		if($Pilot95 >= $Pilot_eni95)
		{
			$Pilot91_win +=1;
			$Pilot91_win_manoeuvre +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_manoeuvre +=1;
		}
		if($Pilot96 >= $Pilot_eni96)
		{
			$Pilot91_win +=1;
			$Pilot91_win_grimper +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_grimper +=1;
		}
		if($Pilot97 >= $Pilot_eni97)
		{
			$Pilot91_win +=1;
			$Pilot91_win_coiffer +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_coiffer +=1;
		}
		if($Pilot98 >= $Pilot_eni98)
		{
			$Pilot91_win +=1;
			$Pilot91_win_frontal +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_frontal +=1;
		}
		if($Pilot99 >= $Pilot_eni99)
		{
			$Pilot91_win +=1;
			$Pilot91_win_ventral +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_ventral +=1;
		}
		if($Pilot910 >= $Pilot_eni910)
		{
			$Pilot91_win +=1;
			$Pilot91_win_leader +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_leader +=1;
		}
		if($Pilot911 >= $Pilot_eni911)
		{
			$Pilot91_win +=1;
			$Pilot91_win_overshoot +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_overshoot +=1;
		}
		if($Pilot912 >= $Pilot_eni912)
		{
			$Pilot91_win +=1;
			$Pilot91_win_immelmann +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_immelmann +=1;
		}
		if($Pilot913 >= $Pilot_eni913)
		{
			$Pilot91_win +=1;
			$Pilot91_win_rapprocher +=1;
		}
		else
		{
			$Pilot92_win +=1;
			$Pilot92_win_rapprocher +=1;
		}
		/*for($p = 1; $p < 14; $p++)
		{
			$Pilots = "Pilot".$p;
			$Pilots_eni = "Pilots_eni".$p;
			if($$Pilots_eni)
			{
				$Var = $$Pilots_eni;
			}
			elseif($$Pilots)
			{
				addWin($$Pilots, $Var);
			}
		}*/
		
		if($Pilot1 >= $Pilot_eni1)
		{
			$Pilot1_win +=1;
			$Pilot1_win_6h +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_6h +=1;
		}
		if($Pilot2 >= $Pilot_eni2)
		{
			$Pilot1_win +=1;
			$Pilot1_win_flanc +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_flanc +=1;
		}
		if($Pilot3 >= $Pilot_eni3)
		{
			$Pilot1_win +=1;
			$Pilot1_win_pique +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_pique +=1;
		}
		if($Pilot4 >= $Pilot_eni4)
		{
			$Pilot1_win +=1;
			$Pilot1_win_nuages +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_nuages +=1;
		}
		if($Pilot5 >= $Pilot_eni5)
		{
			$Pilot1_win +=1;
			$Pilot1_win_manoeuvre +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_manoeuvre +=1;
		}
		if($Pilot6 >= $Pilot_eni6)
		{
			$Pilot1_win +=1;
			$Pilot1_win_grimper +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_grimper +=1;
		}
		if($Pilot7 >= $Pilot_eni7)
		{
			$Pilot1_win +=1;
			$Pilot1_win_coiffer +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_coiffer +=1;
		}
		if($Pilot8 >= $Pilot_eni8)
		{
			$Pilot1_win +=1;
			$Pilot1_win_frontal +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_frontal +=1;
		}
		if($Pilot9 >= $Pilot_eni9)
		{
			$Pilot1_win +=1;
			$Pilot1_win_ventral +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_ventral +=1;
		}
		if($Pilot10 >= $Pilot_eni10)
		{
			$Pilot1_win +=1;
			$Pilot1_win_leader +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_leader +=1;
		}
		if($Pilot11 >= $Pilot_eni11)
		{
			$Pilot1_win +=1;
			$Pilot1_win_overshoot +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_overshoot +=1;
		}
		if($Pilot12 >= $Pilot_eni12)
		{
			$Pilot1_win +=1;
			$Pilot1_win_immelmann +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_immelmann +=1;
		}
		if($Pilot13 >= $Pilot_eni13)
		{
			$Pilot1_win +=1;
			$Pilot1_win_rapprocher +=1;
		}
		else
		{
			$Pilot2_win +=1;
			$Pilot2_win_rapprocher +=1;
		}
		$detail = $detail."Simulation de Combat ".$u." (Altitude ".$alt."m) => ".$Avion_Nom." : ".$Pilot1_win." Victoires / ".$Avion_eni_Nom." : ".$Pilot2_win." Victoire <br>";
		$detail = $detail."Comparatif theorique ".$u." (Altitude ".$alt."m) => ".$Avion_Nom." : ".$Pilot91_win." Victoires / ".$Avion_eni_Nom." : ".$Pilot92_win." Victoire <br><br>";
		
		$Pilot1_total = $Pilot1_total + $Pilot1_win;
		$Pilot2_total = $Pilot2_total + $Pilot2_win;
		$Pilot91_total = $Pilot91_total + $Pilot91_win;
		$Pilot92_total = $Pilot92_total + $Pilot92_win;
		
		/*$Pilot1_array[] = $Pilot1;
		$Pilot2_array[] = $Pilot2;
		$Pilot3_array[] = $Pilot3;
		$Pilot4_array[] = $Pilot4;
		$Pilot5_array[] = $Pilot5;
		$Pilot6_array[] = $Pilot6;
		$Pilot7_array[] = $Pilot7;
		$Pilot8_array[] = $Pilot8;
		$Pilot9_array[] = $Pilot9;
		$Pilot10_array[] = $Pilot10;
		$Pilot11_array[] = $Pilot11;
		$Pilot12_array[] = $Pilot12;
		$Pilot13_array[] = $Pilot13;*/
	}
		if($PlayerID == 1)
		{
			echo "<p> Final Combat (Altitude ".$alt."m) => ".$Avion_Nom." : ".$Pilot1_total." Victoires / ".$Avion_eni_Nom." : ".$Pilot2_total." Victoire </p>";
			echo "<p> Final Theorique (Altitude ".$alt."m) => ".$Avion_Nom." : ".$Pilot91_total." Victoires / ".$Avion_eni_Nom." : ".$Pilot92_total." Victoire </p>";
		}
		echo "
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
				<tr><th colspan='3'>Simulation de Combat</th></tr>
				<tr><th colspan='3'>Altitude ".$alt."m</th></tr>
				<tr><th>Avions</th><th>".$Avion_Nom."</th><th>".$Avion_eni_Nom."</th></tr>
				<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
				<tr><th colspan='3'>Avantage</th></tr>
				<tr><td>6h/Manoeuvre</td><td>".$Pilot1_win_6h."</td><td>".$Pilot2_win_6h."</td></tr>
				<tr><td>Flanc</td><td>".$Pilot1_win_flanc."</td><td>".$Pilot2_win_flanc."</td></tr>
				<tr><td>Ventral</td><td>".$Pilot1_win_ventral."</td><td>".$Pilot2_win_ventral."</td></tr>
				<tr><td>Frontal</td><td>".$Pilot1_win_frontal."</td><td>".$Pilot2_win_frontal."</td></tr>
				<tr><td>Coiffer</td><td>".$Pilot1_win_coiffer."</td><td>".$Pilot2_win_coiffer."</td></tr>
				<tr><td>Rapprocher</td><td>".$Pilot1_win_rapprocher."</td><td>".$Pilot2_win_rapprocher."</td></tr>
				<tr><th colspan='3'>Fuite</th></tr>
				<tr><td>Pique</td><td>".$Pilot1_win_pique."</td><td>".$Pilot2_win_pique."</td></tr>
				<tr><td>Manoeuvre</td><td>".$Pilot1_win_manoeuvre."</td><td>".$Pilot2_win_manoeuvre."</td></tr>
				<tr><td>Nuages</td><td>".$Pilot1_win_nuages."</td><td>".$Pilot2_win_nuages."</td></tr>
				<tr><td>Grimper</td><td>".$Pilot1_win_grimper."</td><td>".$Pilot2_win_grimper."</td></tr>
				<tr><td>Immelmann</td><td>".$Pilot1_win_immelmann."</td><td>".$Pilot2_win_immelmann."</td></tr>
			</table>";
		if($PlayerID == 1)
		{
			echo "
				<table border='1' cellspacing='2' cellpadding='2' bgcolor='DarkSeaGreen'>
					<tr><th colspan='3'>Comparatif théorique des performances</th></tr>
					<tr><th colspan='3'>Altitude ".$alt."m</th></tr>
					<tr><th>Avions</th><th>".$Avion_Nom."</th><th>".$Avion_eni_Nom."</th></tr>
					<tr><td>Meteo</td><td colspan='2'>".$meteo."</td></tr>
					<tr><th colspan='3'>Avantage</th></tr>
					<tr><td>6h/Manoeuvre</td><td>".$Pilot91_win_6h."</td><td>".$Pilot92_win_6h."</td></tr>
					<tr><td>Flanc</td><td>".$Pilot91_win_flanc."</td><td>".$Pilot92_win_flanc."</td></tr>
					<tr><td>Ventral</td><td>".$Pilot91_win_ventral."</td><td>".$Pilot92_win_ventral."</td></tr>
					<tr><td>Frontal</td><td>".$Pilot91_win_frontal."</td><td>".$Pilot92_win_frontal."</td></tr>
					<tr><td>Coiffer</td><td>".$Pilot91_win_coiffer."</td><td>".$Pilot92_win_coiffer."</td></tr>
					<tr><td>Rapprocher</td><td>".$Pilot91_win_rapprocher."</td><td>".$Pilot92_win_rapprocher."</td></tr>
					<tr><th colspan='3'>Fuite</th></tr>
					<tr><td>Pique</td><td>".$Pilot91_win_pique."</td><td>".$Pilot92_win_pique."</td></tr>
					<tr><td>Manoeuvre</td><td>".$Pilot91_win_manoeuvre."</td><td>".$Pilot92_win_manoeuvre."</td></tr>
					<tr><td>Nuages</td><td>".$Pilot91_win_nuages."</td><td>".$Pilot92_win_nuages."</td></tr>
					<tr><td>Grimper</td><td>".$Pilot91_win_grimper."</td><td>".$Pilot92_win_grimper."</td></tr>
					<tr><td>Immelmann</td><td>".$Pilot91_win_immelmann."</td><td>".$Pilot92_win_immelmann."</td></tr>
				</table>";
			echo $detail;
		}

	/*echo"<pre>";
	print_r($Pilot1_array);
	print_r($Pilot2_array);
	print_r($Pilot3_array);
	print_r($Pilot4_array);
	print_r($Pilot5_array);
	print_r($Pilot6_array);
	print_r($Pilot7_array);
	print_r($Pilot8_array);
	print_r($Pilot9_array);
	print_r($Pilot10_array);
	print_r($Pilot11_array);
	print_r($Pilot12_array);
	print_r($Pilot13_array);
	echo"</pre>";*/
}
else
{
	header("Location: ./tsss.php");
}
include_once('./index.php');
?>