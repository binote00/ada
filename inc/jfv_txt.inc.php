<?php
/*function AfficheTxt($Txt)
{
	$Lang=$_SESSION['Langue'];
	if($Txt == '')
	{
		switch($Lang)
		{
			case 0:
				$Txt='';
			break;
			case 1:
				$Txt='';
			break;
		}
	}
	elseif($Txt == '')
	{
		switch($Lang)
		{
			case 0:
				$Txt='';
			break;
			case 1:
				$Txt='';
			break;
		}
	}
	return $Txt;
}*/
function Pluriel($Txt,$Feminin=false)
{
	$End=substr($Txt,-1);
	if($Feminin)
	{
		if($End =='e')
			$sufix='s';
		elseif(substr($Txt,-2) =='en')
			$sufix='nes';
		else
			$sufix='es';
	}
	else
	{
		if($End =='s' or $End =='x')
			$sufix='';
		else
			$sufix='s';
	}
	return $Txt.$sufix;
}

function RangeNbr($Nbr)
{
	if($Nbr < 5)
		$Range='1-5';
	elseif($Nbr < 10)
		$Range='5-10';
	elseif($Nbr < 15)
		$Range='10-15';
	elseif($Nbr < 20)
		$Range='15-20';
	elseif($Nbr < 25)
		$Range='20-25';
	elseif($Nbr < 50)
		$Range='25-50';
	elseif($Nbr < 100)
		$Range='50-100';
	elseif($Nbr < 200)
		$Range='100-200';
	else
		$Range='200+';
	return $Range;
}

function GetMes($Msg)
{
	if($Msg =="init_mission")
	{
		$Msg="<h6>Votre mission a été réinitialisée</h6>
				<br> <b>Si vous êtes régulièrement déconnecté, veuillez vérifier que votre navigateur internet accepte les Cookies de Session <a href='aide_cookies.php' title='Tutoriel pour activer les Cookies de Session dans Internet Explorer' target='_blank'><img src='images/help.png'></a></b>
				<br><br>
				<br>- Veillez à vider le cache de votre navigateur en cas de mise à jour récente de l'Aube des Aigles.
				<br>- Veillez à <u>ne pas recharger/rafraichir les pages du site (ou utiliser la touche F5)</u> ou quitter le site en cours de mission.  
				<br>- Utilisez l'interface du site plutôt que les boutons 'retour', 'précédent' ou 'reculer d'une page' de votre navigateur.
				<br>- Il se peut qu'un problème de connexion soit la cause de cette réinitialisation.
				<br>
				<br> <a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=5&t=6' class='btn btn-default' target='_blank'>Liste des bugs connus</a>
				<br> <a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=4&t=14' class='btn btn-default' target='_blank'>F.A.Q</a>
				<br><br>Si le menu de gauche n'apparait pas, redémarrez votre navigateur internet avant de vous reconnecter au jeu.
				";
		$PlayerID=$_SESSION['PlayerID'];
		if($PlayerID > 0){
			mail("binote@hotmail.com","Aube des Aigles: Init Mission : ".$PlayerID,"Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
			RetireCandidat($PlayerID,"jfv_txt_init");
		}
		$_SESSION['Distance']=0;
	}
	elseif($Msg == "Aide_Avantage")
		$Msg="Acrobatie facilitée par un bon rayon de virage";
	elseif($Msg == "Aide_Barrique")
		$Msg="Les avions maniables et puissants, possédant un bon taux de roulis sont les plus efficaces dans ce cas de figure";
	elseif($Msg == "Aide_Barrique_Off")
		$Msg="Acrobatie facilitée par un bon taux de roulis et un bon rayon de virage";
	elseif($Msg == "Aide_Coiffer")
		$Msg="Votre tactique ainsi que la puissance et la vitesse ascensionnelle de votre avion sont les atouts majeurs de cette manoeuvre";
	elseif($Msg == "Aide_Degager")
		$Msg="Les avions manoeuvrables, particulièrement ceux possédant un bon rayon de virage, sont les plus efficaces dans ce cas de figure";
	elseif($Msg == "Aide_Fuite_Climb")
		$Msg="La vitesse ascensionnelle ainsi que la puissance de votre avion sont les atouts majeurs de cette manoeuvre";
	elseif($Msg == "Aide_Fuite_Man")
		$Msg="Votre pilotage est votre atout majeur dans cette manoeuvre, suivi par les performances de votre avion";
	elseif($Msg == "Aide_Fuite_Nuages")
		$Msg="Plus la météo est mauvaise, plus cela vous aidera";
	elseif($Msg == "Aide_Fuite_Pique")
		$Msg="Votre pilotage, ainsi que la puissance et la vitesse de votre avion sont les atouts majeurs de cette manoeuvre";
	elseif($Msg == "Aide_Frontale")
		$Msg="Acrobatie facilitée par le sens tactique du pilote. Un avion puissant, rapide et possédant un bon rayon de virage est un atout";
	elseif($Msg == "Aide_Appel_leader")
		$Msg="Perdre courage est le prix à payer pour recevoir cette aide";
	elseif($Msg == "Aide_Prot_leader")
		$Msg="La tactique est votre atout majeur de cette manoeuvre, suivie par le pilotage et les performances de votre avion";
	elseif($Msg == "Aide_Rompre")
		$Msg="Rompre le combat lorsque vous êtes en position dominante est automatique. Par contre, vous perdez réputation et courage; sauf si vous êtes à court de munitions";
	elseif($Msg == "Aide_Courte_Rafale")
		$Msg="Bonus pour toucher la cible, malus aux dégâts infligés";
	elseif($Msg == "Aide_Longue_Rafale")
		$Msg="Bonus aux dégâts infligés, malus pour toucher la cible";
	elseif($Msg == "Aide_PVP")
		$Msg="Tenter de repérer un avion piloté par un autre joueur";
	return $Msg;
}

function GetSkillTxt($Skills)
{
	if($Skills > 200)
		$txt='Virtuose';
	elseif($Skills > 174)
		$txt='Elite';
	elseif($Skills > 149)
		$txt='Expert';
	elseif($Skills > 124)
		$txt='Vétéran';
	elseif($Skills > 99)
		$txt='Chevronné';
	elseif($Skills > 74)
		$txt='Entraîné';
	elseif($Skills > 49)
		$txt='Compétent';
	elseif($Skills > 24)
		$txt='Apte';
	else
		$txt='Bleu';
	return $txt;
}

function GetMoralTxt($Skills)
{
	if($Skills > 250)
		$txt="Moral d'acier";
	elseif($Skills > 200)
		$txt='Enthousiaste';
	elseif($Skills > 149)
		$txt='Motivé';
	elseif($Skills > 99)
		$txt='Satisfait';
	elseif($Skills > 49)
		$txt='Démotivé';
	else
		$txt='Démoralisé';
	return $txt;
}

function GetCourageTxt($Skills)
{
	if($Skills > 250)
		$txt='Fanatique';
	elseif($Skills > 200)
		$txt='Téméraire';
	elseif($Skills > 149)
		$txt='Intrépide';
	elseif($Skills > 109)
		$txt='Audacieux';
	elseif($Skills > 99)
		$txt='Déterminé';
	elseif($Skills > 49)
		$txt='Prudent';
	else
		$txt='Lâche';
	return $txt;
}

function GetPays($Pays)
{
	switch($Pays)
	{
		case 1:
			$Pays_nom='Allemagne';
		break;
		case 2:
			$Pays_nom='Angleterre';
		break;
		case 3:
			$Pays_nom='Belgique';
		break;
		case 4:
			$Pays_nom='France';
		break;
		case 5:
			$Pays_nom='Pays-Bas';
		break;
		case 6:
			$Pays_nom='Italie';
		break;
		case 7:
			$Pays_nom='USA';
		break;
		case 8:
			$Pays_nom='URSS';
		break;
		case 9:
			$Pays_nom='Japon';
		break;
		case 10:
			$Pays_nom='Grèce';
		break;
		case 15:
			$Pays_nom='Bulgarie';
		break;
		case 17:
			$Pays_nom='Yougoslavie';
		break;
		case 18:
			$Pays_nom='Roumanie';
		break;
		case 19:
			$Pays_nom='Hongrie';
		break;
		case 20:
			$Pays_nom='Finlande';
		break;
		case 24:
			$Pays_nom='Albanie';
		break;
		case 35:
			$Pays_nom='Norvège';
		break;
		case 36:
			$Pays_nom='Luxembourg';
		break;
		default:
			$Pays_nom='Neutre';
		break;
	}
	return $Pays_nom;
}

function GetBombeType($Type)
{
	switch($Type)
	{
		case 0:
			$Type='Standard';
		break;
		case 1:
			$Type='Incendiaire';
		break;
		case 2:
			$Type='Anti-personnel';
		break;
		case 3:
			$Type='Anti-tank';
		break;
		case 4:
			$Type='Anti-navire';
		break;
		case 5:
			$Type='Anti-bâtiment';
		break;
		case 6:
			$Type='Anti-piste';
		break;
		case 7:
			$Type='Bouncing bomb';
		break;
		case 8:
			$Type='Thermobarique';
		break;
		case 9:
			$Type='Napalm';
		break;
		case 10:
			$Type='Nucléaire';
		break;
	}
	return $Type;
}

function GetSqn($country)
{
	switch($country)
	{
		case 1:
			$Sqn='Staffel';
		break;
		case 2: case 7:
			$Sqn='Flight';
		break;
		case 3: case 4:
			$Sqn='Escadrille';
		break;
		case 6:
			$Sqn='Squadriglia';
		break;
		case 8:
			$Sqn="Eskadra";
		break;
		case 9:
			$Sqn="Chutai";
		break;
		case 20:
			$Sqn="Laivue";
		break;
		default:
			$Sqn="Escadrille";
		break;
	}
	return $Sqn;
}

function GetEsc($country)
{
	switch($country)
	{
		case 1:
			$Sqn='Geschwader';
		break;
		case 2: case 7:
			$Sqn='Squadron';
		break;
		case 3: case 4:
			$Sqn='Escadre';
		break;
		case 6:
			$Sqn='Squadra';
		break;
		case 8:
			$Sqn="Eskadra";
		break;
		case 9:
			$Sqn="Sentai";
		break;
		case 20:
			$Sqn="Laivue";
		break;
		default:
			$Sqn="Escadre";
		break;
	}
	return $Sqn;
}

function GetTraining($Pays)
{
	switch($Pays)
	{
		case 1:
			$Training=192;
		break;
		case 2:
			$Training=193;
		break;
		case 3:
			$Training=414;
		break;
		case 4:
			$Training=191;
		break;
		case 5:
			$Training=413;
		break;
		case 6:
			$Training=194;
		break;
		case 7:
			$Training=388;
		break;
		case 8:
			$Training=387;
		break;
		case 9:
			$Training=389;
		break;
		case 20:
			$Training=759;
		break;
	}
	return $Training;
}

function GetAvionType($Type)
{
	switch($Type)
	{
		case 1:
			$Type_nom='Chasseur';
		break;
		case 2:
			$Type_nom='Bombardier';
		break;
		case 3:
			$Type_nom='Reconnaissance';
		break;
		case 4:
			$Type_nom='Chasseur lourd';
		break;
		case 5:
			$Type_nom='Chasseur-bombardier';
		break;
		case 6:
			$Type_nom='Transport';
		break;
		case 7:
			$Type_nom='Attaque';
		break;
		case 8:
			$Type_nom='Entrainement';
		break;
		case 9:
			$Type_nom='Patrouille Maritime';
		break;
		case 10:
			$Type_nom='Embarqué';
		break;
		case 11:
			$Type_nom='Bombardier lourd';
		break;
		case 12:
			$Type_nom='Chasseur Embarqué';
		break;
	}
	return $Type_nom;
}

function GetMissionType($Type)
{
	switch($Type)
	{
		case 1:
			$Type_nom='Appui rapproché';
		break;
		case 2:
			$Type_nom='Bombardement Tactique';
		break;
		case 3:
			$Type_nom='Chasse libre';
		break;
		case 4:
			$Type_nom='Escorte';
		break;
		case 5:
			$Type_nom='Reco Tactique';
		break;
		case 6:
			$Type_nom='Attaque au sol';
		break;
		case 7:
			$Type_nom='Patrouille';
		break;
		case 8:
			$Type_nom='Bombardement Stratégique';
		break;
		case 9:
			$Type_nom='Interception (obsolète)';
		break;
		case 11:
			$Type_nom='Attaque Navale';
		break;
		case 12:
			$Type_nom='Bombardement Naval';
		break;
		case 13:
			$Type_nom='Torpillage';
		break;
		case 14:
			$Type_nom='Mouillage de mines';
		break;
		case 15:
			$Type_nom='Reco Stratégique';
		break;
		case 16:
			$Type_nom='Bombardement de Nuit';
		break;
		case 17:
			$Type_nom='Chasse de Nuit';
		break;
		case 18: case 19: case 22:
			$Type_nom='Sauvetage';
		break;
		case 21:
			$Type_nom='Marquage';
		break;
		case 23:
			$Type_nom='Ravitaillement';
		break;
		case 24: case 25:
			$Type_nom='Parachutage';
		break;
		case 27: case 28:
			$Type_nom='Commando';
		break;
		case 26:
			$Type_nom='Supériorité (obsolète)';
		break;
		case 29:
			$Type_nom='Patrouille ASM';
		break;
		case 31:
			$Type_nom='Harcèlement';
		break;
		case 32:
			$Type_nom='Veille';
		break;
		case 41: case 42:  case 44: case 45: case 46: case 47: case 411: case 412: case 413: case 415: case 423: case 424:
			$Type_nom='Coopération';
		break;
		case 101: case 102: case 103: case 104:
			$Type_nom='Entrainement';
		break;
		case 127:
			$Type_nom='Avion volé';
		break;
		default:
			$Type_nom='Inconnue';
		break;
	}
	return $Type_nom;
}

function GetTask($Task)
{
	switch($Task)
	{
		case 1:
			$Task_nom='Observation';
		break;
		case 2:
			$Task_nom='Pathfinder';
		break;
		case 3:
			$Task_nom='Reconnaissance';
		break;
		case 4:
			$Task_nom='Sauvetage';
		break;
		case 5:
			$Task_nom='Veille';
		break;
	}
	return $Task_nom;
}

function Get_EM($country)
{
	switch($country)
	{
		case 1:
			$Etat_Major='Generalstab';
		break;
		case 2: case 7:
			$Etat_Major='Headquarters';
		break;
		case 3: case 4:
			$Etat_Major='Etat-Major';
		break;
		case 6:
			$Etat_Major='Stato maggiore';
		break;
		case 8:
			$Etat_Major='General nyy shtab';
		break;
		case 9:
			$Etat_Major='Kokugun';
		break;
		default:
			$Etat_Major='Etat-Major';
		break;
	}
	return $Etat_Major;
}

function GetPers_txt($Pers)
{
	switch($Pers)
	{
	case 1:
		$Pers='Armurier';
	break;
	case 2:
		$Pers='Artilleur';
	break;
	case 3:
		$Pers='Barman';
	break;
	case 4:
		$Pers='Garde';
	break;
	case 5:
		$Pers='Instructeur';
	break;
	case 6:
		$Pers='Mécano';
	break;
	case 7:
		$Pers='Médecin';
	break;
	case 8:
		$Pers='Observateur';
	break;
	case 9:
		$Pers='Officier';
	break;
	case 10:
		$Pers='Officier de propagande';
	break;
	case 11:
		$Pers='Officier des renseignements';
	break;
	case 12:
		$Pers='Ouvrier';
	break;
	case 13:
		$Pers='Personnel navigant';
	break;
	case 14:
		$Pers='Prévisionniste météo';
	break;
	case 15:
		$Pers='Secrétaire';
	break;
	case 16:
		$Pers='Soldat';
	break;
	case 17:
		$Pers='Pompier';
	break;
	default:
		$Pers='Oisif';
	break;
	}
	
	return $Pers;
}

function GetSpec_txt($Spec)
{
	switch($Spec)
	{
	case 1:
		$Spec="Arrière-Garde";
	break;
	case 2:
		$Spec="Avant-Garde";
	break;
	case 3:
		$Spec="Embuscade";
	break;
	case 4:
		$Spec="Enfilade";
	break;
	case 5:
		$Spec="Flanc-Garde";
	break;
	case 6:
		$Spec="Evitement";
	break;
	case 7:
		$Spec="Guerre de tranchées";
	break;
	case 8:
		$Spec="Tir de barrage";
	break;
	case 9:
		$Spec="Deception";
	break;
	case 10:
		$Spec="Défense élastique";
	break;
	case 11:
		$Spec="Hérisson";
	break;
	case 12:
		$Spec="Pakfront";
	break;
	case 13:
		$Spec="Panzerkeil";
	break;
	case 14:
		$Spec="Pente inverse";
	break;
	case 15:
		$Spec="Artilleur Expert";
	break;
	case 16:
		$Spec="Tenaille";
	break;
	case 17:
		$Spec="Démolition";
	break;
	case 18:
		$Spec="Exploitation";
	break;
	case 19:
		$Spec="Fer de lance";
	break;
	case 20:
		$Spec="Gyokusai";
	break;
	case 21:
		$Spec="Raid";
	break;
	case 22:
		$Spec="Batterie";
	break;
	case 23:
		$Spec="Encerclement";
	break;
	case 24:
		$Spec="Panzerkeil";
	break;
	case 25:
		$Spec="Reco by fire";
	break;
	case 26:
		$Spec="Sturmtruppen";
	break;
	case 27:
		$Spec="Tankodesantniki";
	break;
	case 28:
		$Spec="Tir de suppression";
	break;
	case 29:
		$Spec="Retraite ordonnée";
	break;
	case 30:
		$Spec="Anti-aérien";
	break;
	case 31:
		$Spec="Barrer le T";
	break;
	case 32:
		$Spec="Plongée rapide";
	break;
	case 33:
		$Spec="Blocus naval";
	break;
	case 34:
		$Spec="Interdiction navale";
	break;
	case 35:
		$Spec="Spécialiste du sonar";
	break;
	case 36:
		$Spec="Spécialiste du grenadage";
	break;
	case 37:
		$Spec="Spécialiste de l'appui-feu";
	break;
	case 38:
		$Spec="Spécialiste de l'évitement";
	break;
	case 39:
		$Spec="Spécialiste du torpillage";
	break;
	case 40:
		$Spec="Ravitaillement favorisé";
	break;
	case 41:
		$Spec="Stocks optimisés";
	break;
	case 42:
		$Spec="Ravitaillement prioritaire";
	break;
	case 43:
		$Spec="Stocks optimisés II";
	break;
	case 100:
		$Spec="Guerre du désert";
	break;
	case 101:
		$Spec="Guerre d'hiver";
	break;
	case 200:
		$Spec="Breveté d'état-major";
	break;
	case 201:
		$Spec="Ecole de guerre";
	break;
	case 202:
		$Spec="Expert en casernement";
	break;
	case 203:
		$Spec="Expert en logistique";
	break;
	case 204:
		$Spec="Expert en production";
	break;
	case 205:
		$Spec="Ingénieur en construction";
	break;
	case 206:
		$Spec="Spécialiste des transmissions";
	break;
	case 207:
		$Spec="Spécialiste du renseignement";
	break;
	case 208:
		$Spec="Spécialiste de la DCA";
	break;
	default:
		$Spec="Aucune";
	break;
	}
	
	return $Spec;
}

function GetMun_txt($Munitions)
{
	switch($Munitions)
	{
		case 0:
			$Munitions_txt='Standard';
		break;
		case 1:
			$Munitions_txt='AP';
		break;
		case 2:
			$Munitions_txt='HE';
		break;
		case 3:
			$Munitions_txt='I';
		break;
		case 4:
			$Munitions_txt='APHE';
		break;
		case 5:
			$Munitions_txt='API';
		break;
		case 6:
			$Munitions_txt='APCR';
		break;
		case 7:
			$Munitions_txt='APDS';
		break;
		case 8:
			$Munitions_txt='HEAT';
		break;
	}
	return $Munitions_txt;
}

function GetMedal_Name($Pays, $Rang_promo, $terre=0)
{
	switch($Rang_promo)
	{
		case 0:
			$Nom_med="Brevet de Pilote";
		break;
		case 1:
			if($Pays ==1)
				$Nom_med="Eisernes Kreuz 2 Klasse";
			elseif($Pays == 2)
				$Nom_med="Mentioned in Despatch";
			elseif($Pays == 6)
				$Nom_med="Croce al merito di guerra";
			elseif($Pays == 7)
				$Nom_med="Commendation Medal";
			elseif($Pays == 8)
				$Nom_med="Orden Slava tretej stepeni";
			elseif($Pays == 9)
				$Nom_med="Zuihosho";
			else
				$Nom_med="Croix de guerre";
		break;
		case 2:
			if($Pays ==1)
				$Nom_med="Eisernes Kreuz 1 Klasse";
			elseif($Pays == 2)
				$Nom_med="Military Cross";
			elseif($Pays == 3)
				$Nom_med="Croix de guerre avec palme de bronze";
			elseif($Pays == 6)
			{
				if($terre)
					$Nom_med="Croce di bronzo al merito dell Esercito";
				else
					$Nom_med="Croce di bronzo al merito dell aeronautica";
			}
			elseif($Pays == 7)
				$Nom_med="Air Medal";
			elseif($Pays == 8)
				$Nom_med="Orden Slava vtoroj stepeni";
			elseif($Pays == 9)
				$Nom_med="Zuihosho";
			else
				$Nom_med="Croix de guerre avec étoile de bronze";
		break;
		case 3:
			if($Pays ==1)
			{
				if($terre)
					$Nom_med="Ehrenblattspange des Heeres";
				else
					$Nom_med="Ehrenpokal der Luftwaffe";
			}
			elseif($Pays == 2)
			{
				if($terre)
					$Nom_med="Military Cross with bar";
				else
					$Nom_med="Distinguished Flying Cross";
			}
			elseif($Pays == 3)
				$Nom_med="Palmes d argent";
			elseif($Pays == 6)
			{
				if($terre)
					$Nom_med="Croce d argento al merito dell Esercito";
				else
					$Nom_med="Croce d argento al merito dell aeronautica";
			}
			elseif($Pays == 7)
				$Nom_med="Bronze Star";
			elseif($Pays == 8)
				$Nom_med="Orden Slava pervoj stepeni";
			elseif($Pays == 9)
				$Nom_med="Kyokujitsu sho";
			else
				$Nom_med="Croix de guerre avec deux étoiles de bronze";
		break;
		case 4:
			if($Pays ==1)
				$Nom_med="Deutsches Kreuz in silber";
			elseif($Pays == 2)
			{
				if($terre)
					$Nom_med="Distinguished Service Class";
				else
					$Nom_med="Distinguished Flying Cross with bar";
			}
			elseif($Pays == 3)
				$Nom_med="Palmes d or";
			elseif($Pays == 6)
			{
				if($terre)
					$Nom_med="Croce d oro al merito dell Esercito";
				else
					$Nom_med="Croce d oro al merito dell aeronautica";
			}
			elseif($Pays == 7)
				$Nom_med="Soldiers Medal";
			elseif($Pays == 8)
				$Nom_med="Orden Otechestvennoj vojny vtoroj stepeni";
			elseif($Pays == 9)
				$Nom_med="Kyokujitsu sho";
			else
				$Nom_med="Croix de guerre avec palme de bronze";
		break;
		case 5:
			if($Pays ==1)
				$Nom_med="Deutsches Kreuz in gold";
			elseif($Pays == 2)
			{
				if($terre)
					$Nom_med="Distinguished Service Class with bar";
				else
					$Nom_med="Distinguished Flying Cross with 2 bars";
			}
			elseif($Pays == 3)
				$Nom_med="Chevalier de l Ordre de la Couronne";
			elseif($Pays == 6)
				$Nom_med="Medaglia di bronzo al valore aeronautico";
			elseif($Pays == 7)
				$Nom_med="Distinguished Flying Cross";
			elseif($Pays == 8)
				$Nom_med="Orden Otechestvennoj vojny pervoj stepeni";
			elseif($Pays == 9)
				$Nom_med="Toka sho";
			else
				$Nom_med="Croix de guerre avec deux palmes de bronze";
		break;
		case 6:
			if($Pays ==1)
				$Nom_med="Ritterkreuz des Eisernen Kreuzes";
			elseif($Pays == 2)
				$Nom_med="Distinguished Service Order";
			elseif($Pays == 3)
				$Nom_med="Officier de l Ordre de la Couronne";
			elseif($Pays == 6)
				$Nom_med="Medaglia d argento al valore aeronautico";
			elseif($Pays == 7)
				$Nom_med="Legion of Merit";
			elseif($Pays == 8)
				$Nom_med="Orden Krasnoj Zvezdy";
			elseif($Pays == 9)
				$Nom_med="Kinshi Kunsho";
			else
				$Nom_med="Croix de guerre avec palme d argent";
		break;
		case 7:
			if($Pays ==1)
				$Nom_med="Ritterkreuz des Eisernen Kreuzes mit Eichenlaub";
			elseif($Pays == 2)
				$Nom_med="Distinguished Service Order with bar";
			elseif($Pays == 3)
				$Nom_med="Commandeur de l Ordre de la Couronne";
			elseif($Pays == 6)
				$Nom_med="Medaglia d oro al valore aeronautico";
			elseif($Pays == 7)
				$Nom_med="Silver Star";
			elseif($Pays == 8)
				$Nom_med="Orden Krasnogo Znameni";
			elseif($Pays == 9)
				$Nom_med="Kinshi Kunsho";
			else
				$Nom_med="Croix de guerre avec deux palmes d argent";
		break;
		case 8:
			if($Pays ==1)
				$Nom_med="Ritterkreuz des Eisernen Kreuzes mit Eichenlaub und Schwertern";
			elseif($Pays == 2)
				$Nom_med="Distinguished Service Order with 2 bars";
			elseif($Pays == 3)
				$Nom_med="Chevalier de l Ordre de Léopold";
			elseif($Pays == 6)
				$Nom_med="Medaglia di bronzo al Valore Militare";
			elseif($Pays == 7)
				$Nom_med="Distinguished Service Medal";
			elseif($Pays == 8)
				$Nom_med="Medal Za otvagu";
			elseif($Pays == 9)
				$Nom_med="Kinshi Kunsho";
			else
				$Nom_med="Médaille militaire";
		break;
		case 9:
			if($Pays ==1)
				$Nom_med="Ritterkreuz des Eisernen Kreuzes mit Eichenlaub, Schwertern und Brillanten";
			elseif($Pays == 2)
				$Nom_med="Victoria Cross";
			elseif($Pays == 3)
				$Nom_med="Officier de l Ordre de Léopold";
			elseif($Pays == 6)
				$Nom_med="Medaglia d argento al Valore Militare";
			elseif($Pays == 7)
				$Nom_med="Distinguished Service Cross";
			elseif($Pays == 8)
				$Nom_med="Orden Lenina";
			elseif($Pays == 9)
				$Nom_med="Kinshi Kunsho";
			else
				$Nom_med="Légion d honneur";
		break;
		case 10:
			if($Pays ==1)
				$Nom_med="Goldenes Eichenlaub mit Schwertern und Brillanten zum Ritterkreuz des Eisernen Kreuzes";
			elseif($Pays == 2)
				$Nom_med="Victoria Cross with bar";
			elseif($Pays == 3)
				$Nom_med="Commandeur de l Ordre de Léopold";
			elseif($Pays == 6)
				$Nom_med="Medaglia d oro al Valore Militare";
			elseif($Pays == 7)
				$Nom_med="Medal of Honor";
			elseif($Pays == 8)
				$Nom_med="Geroy Sovyetskogo Soyuza";
			elseif($Pays == 9)
				$Nom_med="Dai-kuni kikka-sho";
			else
				$Nom_med="Officier de la Légion d honneur";
		break;
		case 11:
			if($Pays ==1)
				$Nom_med="Verwundetenabzeichen";
			elseif($Pays == 2)
				$Nom_med="King George VI Coronation Medal";
			elseif($Pays == 6)
				$Nom_med="Distintivo per feriti";
			elseif($Pays == 7)
				$Nom_med="Purple Heart";
			else
				$Nom_med="Insigne des blessés militaires";
		break;
		case 12:
			if($Pays ==1)
				$Nom_med="Kriegsverdienstkreuz II Klasse";
			elseif($Pays == 2)
				$Nom_med="Efficiency Medal";
			elseif($Pays == 3)
				$Nom_med="Médaille du volontaire de guerre";
			elseif($Pays == 6)
				$Nom_med="Volontario di Guerra";
			else
				$Nom_med="Croix des services militaires volontaires 2e classe";
		break;
		case 13:
			if($Pays ==1)
				$Nom_med="Kriegsverdienstkreuz I Klasse";
			elseif($Pays == 2)
				$Nom_med="Air Force Cross";
			elseif($Pays == 3)
				$Nom_med="Médaille du volontaire de guerre";
			elseif($Pays == 6)
				$Nom_med="Volontario di Guerra";
			else
				$Nom_med="Croix des services militaires volontaires 1e classe";
		break;
		case 14:
			if($Pays ==1)
				$Nom_med="Frontflugspange";
			elseif($Pays == 2)
				$Nom_med="Dover Front-Line Medal 1940";
			elseif($Pays == 3)
				$Nom_med="Médaille de mai 1940";
			elseif($Pays == 6)
				$Nom_med="Campagna di Francia 1940";
			elseif($Pays == 7)
				$Nom_med="Presidential Unit Citation";
			else
				$Nom_med="Médaille de la Campagne de France";
		break;
		case 15:
			if($Pays ==1)
				$Nom_med="Frontflugspange";
			elseif($Pays == 2 or $Pays == 3)
				$Nom_med="Battle of Britain Bar";
			else
				$Nom_med="Médaille de la Bataille d Angleterre";
		break;
		case 16:
				$Nom_med="1 an de jeu";
		break;
		case 17:
			if($Pays ==1)
				$Nom_med="Medaille für den italienisch-deutschen Feldzug in Afrika";
			elseif($Pays == 2 or $Pays == 3)
				$Nom_med="Africa Star";
			elseif($Pays == 6)
				$Nom_med="Medaglia commemorativa della campagna italo-tedesca in Africa";
			else
				$Nom_med="Médaille commémorative du Levant";
		break;
		case 18:
			if($Pays ==1 or $Pays == 6)
				$Nom_med="Winterschlacht im Osten 1941/42";
			elseif($Pays == 8)
				$Nom_med="Za oboronu Moskvy";
		break;
	}
	return $Nom_med;
}

function GetReputation($Reput, $Pays)
{
	switch($Pays)
	{
		case 1:
			if($Reput > 999999)
				$txt="Geschwader Kommodore";
			/*elseif($Reput > 999999)
				$txt="Geschwader Adjudant";*/
			elseif($Reput > 99999)
				$txt="Gruppe Kommandeur";
			elseif($Reput > 9999)
				$txt="Staffel Kapitän";
			elseif($Reput > 999)
				$txt="Schwarmführer";
			elseif($Reput > 499)
				$txt="Rottenführer";
			elseif($Reput > 49)
				$txt="Flügelmann";
			else
				$txt="Fahnenjunker";
		break;
		case 2:
			if($Reput > 9999999)
				$txt="Group Captain";
			elseif($Reput > 999999)
				$txt="Station Commander";
			elseif($Reput > 99999)
				$txt="Wing Commander";
			elseif($Reput > 9999)
				$txt="Squadron Leader";
			elseif($Reput > 999)
				$txt="Flight Leader";
			elseif($Reput > 499)
				$txt="Pair leader";
			elseif($Reput > 49)
				$txt="Wingman";
			else
				$txt="Aircraftman";
		break;
		case 4:
			if($Reput > 999999)
				$txt="Commandant d'escadre";
			elseif($Reput > 99999)
				$txt="Commandant de groupe";
			elseif($Reput > 9999)
				$txt="Chef d'escadrille";
			elseif($Reput > 999)
				$txt="Adjoint d'escadrille";
			elseif($Reput > 499)
				$txt="Chef de patrouille";
			elseif($Reput > 49)
				$txt="Adjoint de patrouille";
			else
				$txt="Elève Pilote";
		break;
		default:
			if($Reput >999999)
				$txt="Commandant d'escadre";
			elseif($Reput > 99999)
				$txt="Commandant de groupe";
			elseif($Reput > 9999)
				$txt="Chef d'escadrille";
			elseif($Reput > 999)
				$txt="Adjoint d'escadrille";
			elseif($Reput > 499)
				$txt="Pilote confirmé";
			elseif($Reput > 49)
				$txt="Pilote novice";
			else
				$txt="Elève Pilote";
		break;
	}
	return $txt;
}

function GetReputOfficier($Reput)
{
	if($Reput <1)
	{
		$Titre="Inconnu";
		$Rank=0;
	}
	elseif($Reput <50)
	{
		$Titre="Bleu quelque peu aguerri";
		$Rank=1;
	}
	elseif($Reput <100)
	{
		$Titre="Officier anonyme";
		$Rank=2;
	}
	elseif($Reput <500)
	{
		$Titre="Reconnu par ses pairs";
		$Rank=3;
	}
	elseif($Reput <1000)
	{
		$Titre="Réputé dans ses rangs";
		$Rank=4;
	}
	elseif($Reput <2000)
	{
		$Titre="Connu de l'ennemi";
		$Rank=5;
	}
	elseif($Reput <5000)
	{
		$Titre="Adversaire réputé";
		$Rank=6;
	}
	elseif($Reput <10000)
	{
		$Titre="Craint par l'ennemi";
		$Rank=7;
	}
	elseif($Reput <20000)
	{
		$Titre="Tacticien hors-pair";
		$Rank=8;
	}
	elseif($Reput <50000)
	{
		$Titre="Grand Stratège";
		$Rank=9;
	}
	elseif($Reput <100000)
	{
		$Titre="Héros national";
		$Rank=10;
	}
	elseif($Reput <500000)
	{
		$Titre="Référence historique";
		$Rank=11;
	}
	else
	{
		$Titre="Légende vivante";
		$Rank=12;
	}		
	return array ($Titre,$Rank);
}

function GetTraitOfficier($Trait_e)
{
	if($Trait_e ==1)
		$Trait_txt="Acteur de la propagande";
	elseif($Trait_e == 2)
		$Trait_txt="Adepte de la guerilla";
	elseif($Trait_e == 3)
		$Trait_txt="Adepte de la guerre de mouvement";
	elseif($Trait_e == 4)
		$Trait_txt="Adepte de la guerre de position";
	elseif($Trait_e == 5)
		$Trait_txt="Adepte du camouflage";
	elseif($Trait_e == 6)
		$Trait_txt="Charismatique";
	elseif($Trait_e == 7)
		$Trait_txt="De la vieille école";
	elseif($Trait_e == 8)
		$Trait_txt="Evaluateur opérationnel";
	elseif($Trait_e == 9)
		$Trait_txt="Expert en lecture de cartes";
	elseif($Trait_e ==10)
		$Trait_txt="Expert en repérage";
	elseif($Trait_e ==11)
		$Trait_txt="Insaisissable";
	elseif($Trait_e ==12)
		$Trait_txt="Instructeur";
	elseif($Trait_e ==13)
		$Trait_txt="Prudent";
	elseif($Trait_e ==14)
		$Trait_txt="Logisticien";
	elseif($Trait_e ==15)
		$Trait_txt="Premier de promotion";
	elseif($Trait_e ==16)
		$Trait_txt="Offensif";
	elseif($Trait_e ==17)
		$Trait_txt="Démineur";
	elseif($Trait_e ==18)
		$Trait_txt="Expert en navigation";
	elseif($Trait_e ==19)
		$Trait_txt="Adepte de la guerre de surface";
	elseif($Trait_e == 20)
		$Trait_txt="Adepte de la guerre sous-marine";
	elseif($Trait_e == 21)
		$Trait_txt="Adepte de l'aéronavale";
	elseif($Trait_e == 23)
		$Trait_txt="Expert en renseignements";
	elseif($Trait_e == 24)
		$Trait_txt="Ingénieur";
	else
		$Trait_txt="Aucun";
	return $Trait_txt;
}

function GetAvancement($Avancement, $Pays, $Jauge=0, $Officier=0)
{
	if($Jauge ==0)
	{
		if($Avancement < 100)
			$Jauge=0;
		elseif($Avancement < 200)
			$Jauge=1;
		elseif($Avancement < 300)
			$Jauge=2;
		elseif($Avancement < 500)
			$Jauge=3;
		elseif($Avancement < 1000)
			$Jauge=4;
		elseif($Avancement < 1500)
			$Jauge=5;
		elseif($Avancement < 2000)
			$Jauge=6;
		elseif($Avancement < 3000)
			$Jauge=7;
		elseif($Avancement < 5000)
			$Jauge=8;
		elseif($Avancement < 10000)
			$Jauge=9;
		elseif($Avancement < 25000)
			$Jauge=10;
		elseif($Avancement < 50000)
			$Jauge=11;
		elseif($Avancement < 100000)
			$Jauge=12;
		elseif($Avancement < 200000)
			$Jauge=13;
		elseif($Avancement < 500000)
			$Jauge=14;
		else
			$Jauge=15;
	}
	
	if($Pays==1)
	{
		switch($Jauge)
		{
			case 0:
				$Grade="Flieger";
			break;
			case 1:
				$Grade="Gefreiter";
			break;
			case 2:
				$Grade="Obergefreiter";
			break;
			case 3:
				$Grade="Hauptgefreiter";
			break;
			case 4:
				$Grade="Unteroffizier";
			break;
			case 5:
				$Grade="Unterfeldwebel";
			break;
			case 6:
				$Grade="Feldwebel";
			break;
			case 7:
				$Grade="Oberfeldwebel";
			break;
			case 8:
				$Grade="Hauptfeldwebel";
			break;
			case 9:
				$Grade="Leutnant";
			break;
			case 10:
				$Grade="Oberleutnant";
			break;
			case 11:
				$Grade="Hauptmann";
			break;
			case 12:
				$Grade="Major";
			break;
			case 13:
				$Grade="OberstLeutnant";
			break;
			case 14:
				$Grade="Oberst";
			break;
			case 15:
				$Grade="Generalmajor";
			break;
		}
	}
	elseif($Pays==2)
	{
		if($Officier ==1)
		{
			switch($Jauge)
			{
				case 8:
					$Grade="Warrant Officer";
				break;
				case 9:
					$Grade="Second Lieutenant";
				break;
				case 10:
					$Grade="First Lieutenant";
				break;
				case 11:
					$Grade="Captain";
				break;
				case 12:
					$Grade="Major";
				break;
				case 13:
					$Grade="Lieutenant Colonel";
				break;
				case 14:
					$Grade="Colonel";
				break;
				case 15:
					$Grade="Brigadier";
				break;
			}
		}
		else
		{
			switch($Jauge)
			{
				case 0:
					$Grade="Aircraftman";
				break;
				case 1:
					$Grade="Leading Aircraftman";
				break;
				case 2:
					$Grade="Lance Corporal";
				break;
				case 3:
					$Grade="Corporal";
				break;
				case 4:
					$Grade="Sergeant";
				break;
				case 5:
					$Grade="Chief Technician";
				break;
				case 6:
					$Grade="Flight Sergeant";
				break;
				case 7:
					$Grade="Warrant Officer 2nd Class";
				break;
				case 8:
					$Grade="Warrant Officer 1st Class";
				break;
				case 9:
					$Grade="Pilot Officer";
				break;
				case 10:
					$Grade="Flying Officer";
				break;
				case 11:
					$Grade="Flight Lieutenant";
				break;
				case 12:
					$Grade="Squadron Leader";
				break;
				case 13:
					$Grade="Wing Commander";
				break;
				case 14:
					$Grade="Group Captain";
				break;
				case 15:
					$Grade="Air Commodore";
				break;
			}
		}
	}
	elseif($Pays==4)
	{
		switch($Jauge)
		{
			case 0:
				$Grade="Aviateur";
			break;
			case 1:
				$Grade="Première classe";
			break;
			case 2:
				$Grade="Caporal";
			break;
			case 3:
				$Grade="Caporal-Chef";
			break;
			case 4:
				$Grade="Sergent";
			break;
			case 5:
				$Grade="Sergent-Chef";
			break;
			case 6:
				$Grade="Sergent-Major";
			break;
			case 7:
				$Grade="Adjudant";
			break;
			case 8:
				$Grade="Adjudant-Chef";
			break;
			case 9:
				$Grade="Sous-Lieutenant";
			break;
			case 10:
				$Grade="Lieutenant";
			break;
			case 11:
				$Grade="Capitaine";
			break;
			case 12:
				$Grade="Commandant";
			break;
			case 13:
				$Grade="Lieutenant-Colonel";
			break;
			case 14:
				$Grade="Colonel";
			break;
			case 15:
				$Grade="Général de Brigade";
			break;
		}
	}
	elseif($Pays==6)
	{
		switch($Jauge)
		{
			case 0:
				$Grade="Aviere";
			break;
			case 1:
				$Grade="Aviere Scelto";
			break;
			case 2:
				$Grade="Aviere Capo";
			break;
			case 3:
				$Grade="Primo Aviere";
			break;
			case 4:
				$Grade="Sergente";
			break;
			case 5:
				$Grade="Sergente Maggiore";
			break;
			case 6:
				$Grade="Maresciallo di terza classe";
			break;
			case 7:
				$Grade="Maresciallo di seconda classe";
			break;
			case 8:
				$Grade="Maresciallo di prima classe";
			break;
			case 9:
				$Grade="Sottotenente";
			break;
			case 10:
				$Grade="Tenente";
			break;
			case 11:
				$Grade="Capitano";
			break;
			case 12:
				$Grade="Maggiore";
			break;
			case 13:
				$Grade="Tenente Colonnello";
			break;
			case 14:
				$Grade="Colonnello";
			break;
			case 15:
				$Grade="Generale di Brigata";
			break;
		}
	}				
	elseif($Pays==7)
	{
		switch($Jauge)
		{
			case 0:
				$Grade="Private";
			break;
			case 1:
				$Grade="Private First Class";
			break;
			case 2:
				$Grade="Corporal";
			break;
			case 3:
				$Grade="Sergeant";
			break;
			case 4:
				$Grade="Staff Sergeant";
			break;
			case 5:
				$Grade="Technical Sergeant";
			break;
			case 6:
				$Grade="Master Sergeant";
			break;
			case 7:
				$Grade="Junior Warrant Officer";
			break;
			case 8:
				$Grade="Chief Warrant Officer";
			break;
			case 9:
				$Grade="Second Lieutenant";
			break;
			case 10:
				$Grade="First Lieutenant";
			break;
			case 11:
				$Grade="Captain";
			break;
			case 12:
				$Grade="Major";
			break;
			case 13:
				$Grade="Lieutenant Colonel";
			break;
			case 14:
				$Grade="Colonel";
			break;
			case 15:
				$Grade="Brigadier General";
			break;
		}
	}
	elseif($Pays==8)
	{
		switch($Jauge)
		{
			case 0:
				$Grade="Krasnoarmeyets";
			break;
			case 1:
				$Grade="Yefreytor";
			break;
			case 2:
				$Grade="Mladshiy Serzhant";
			break;
			case 3:
				$Grade="Serzhant";
			break;
			case 4:
				$Grade="Starshiy Serzhant";
			break;
			case 5:
				$Grade="Starshina";
			break;
			case 6:
				$Grade="Mladshiy Voentehnik";
			break;
			case 7:
				$Grade="Voentehnik";
			break;
			case 8:
				$Grade="Mladshiy leytenant";
			break;
			case 9:
				$Grade="Leytenant";
			break;
			case 10:
				$Grade="Starshiy Leytenant";
			break;
			case 11:
				$Grade="Kapitan";
			break;
			case 12:
				$Grade="Major";
			break;
			case 13:
				$Grade="Podpolkovnik";
			break;
			case 14:
				$Grade="Polkovnik";
			break;
			case 15:
				$Grade="General major";
			break;
		}
	}
	elseif($Pays==9)
	{
		switch($Jauge)
		{
			case 0:
				$Grade="Santo Hei";
			break;
			case 1:
				$Grade="Nito Hei";
			break;
			case 2:
				$Grade="Ito Hei";
			break;
			case 3:
				$Grade="Joto Hei";
			break;
			case 4:
				$Grade="Heicho";
			break;
			case 5:
				$Grade="Gocho";
			break;
			case 6:
				$Grade="Gunso";
			break;
			case 7:
				$Grade="Socho";
			break;
			case 8:
				$Grade="Juni";
			break;
			case 9:
				$Grade="Shoi";
			break;
			case 10:
				$Grade="Chui";
			break;
			case 11:
				$Grade="Taii";
			break;
			case 12:
				$Grade="Shosa";
			break;
			case 13:
				$Grade="Chusa";
			break;
			case 14:
				$Grade="Taisa";
			break;
			case 15:
				$Grade="Shosho";
			break;
		}
	}
	elseif($Pays==20)
	{
		switch($Jauge)
		{
			case 0:
				$Grade="Sotamies";
			break;
			case 1:
				$Grade="Lentosotamies";
			break;
			case 2:
				$Grade="Korpraali";
			break;
			case 3:
				$Grade="Alikersantti";
			break;
			case 4:
				$Grade="Kersantti";
			break;
			case 5:
				$Grade="Ylikersantti";
			break;
			case 6:
				$Grade="Vääpeli";
			break;
			case 7:
				$Grade="Ylivääpeli";
			break;
			case 8:
				$Grade="Lentomestari";
			break;
			case 9:
				$Grade="Luutnantti";
			break;
			case 10:
				$Grade="Yliluutnantti";
			break;
			case 11:
				$Grade="Kapteeni";
			break;
			case 12:
				$Grade="Majuri";
			break;
			case 13:
				$Grade="Everstiluutnantti";
			break;
			case 14:
				$Grade="Eversti";
			break;
			case 15:
				$Grade="Kenraalimajuri";
			break;
		}
	}
	else
	{
		switch($Jauge)
		{
			case 0:
				$Grade="Aviateur";
			break;
			case 1:
				$Grade="Première classe";
			break;
			case 2:
				$Grade="Caporal";
			break;
			case 3:
				$Grade="Caporal-Chef";
			break;
			case 4:
				$Grade="Sergent";
			break;
			case 5:
				$Grade="Sergent-Chef";
			break;
			case 6:
				$Grade="Adjudant";
			break;
			case 7:
				$Grade="Adjudant-Chef";
			break;
			case 8:
				$Grade="Sous-Lieutenant";
			break;
			case 9:
				$Grade="Lieutenant";
			break;
			case 10:
				$Grade="Capitaine";
			break;
			case 11:
				$Grade="Capitaine-Commandant";
			break;
			case 12:
				$Grade="Major";
			break;
			case 13:
				$Grade="Lieutenant-Colonel";
			break;
			case 14:
				$Grade="Colonel";
			break;
			case 15:
				$Grade="Général de Brigade";
			break;
		}
	}
	return array ($Grade, $Jauge);
}

function GetStaff($Pays,$Role)
{
	switch($Role)
	{
		case 1:
			if($Pays ==1)
				$txt="Kommandeur";
			elseif($Pays == 2)
				$txt="Squadron Leader";
			elseif($Pays == 6)
				$txt="Comandante";
			else
				$txt="Commandant";
		break;
		case 2:
			if($Pays ==1)
				$txt="Z.b.V. Offizier";
			elseif($Pays == 2)
				$txt="Staff Officer";
			elseif($Pays == 6)
				$txt="Ufficiale di Stato Maggiore";
			else
				$txt="Commandant adjoint";
		break;
		case 3:
			if($Pays ==1)
				$txt="Technischer Offizier";
			elseif($Pays == 2)
				$txt="Supply Officer";
			elseif($Pays == 6)
				$txt="Ufficiale Tecnico";
			else
				$txt="Officier du ravitaillement";
		break;
	}
	return $txt;
}

function PrintNoAccessPil($country,$Function,$Function2=0,$Function3=0,$Function4=0,$Function5=0)
{
	if($Function2)
		$F2=" ou celui de <b>".GetStaff($country,$Function2)."</b>";
	if($Function3)
		$F3=" ou celui de <b>".GetStaff($country,$Function3)."</b>";
	if($Function4)
		$F4=" ou celui de <b>".GetStaff($country,$Function4)."</b>";
	if($Function5)
		$F5=" ou celui de <b>".GetStaff($country,$Function5)."</b>";
	echo "<div><img src='images/top_secret.gif'><br>Ces données sont classifiées.Votre rang ne vous permet pas d'accéder à ces informations.
			<br>Pour accéder à ces informations, vous devez occuper le poste de <b>".GetStaff($country,$Function)."</b>".$F2.$F3.$F4.$F5;
}

function PrintNoAccess($country,$Function,$Function2=0,$Function3=0,$Function4=0,$Function5=0)
{
	if($Function2)
		$F2=" ou celui de <b>".GetGenStaff($country,$Function2)."</b>";
	if($Function3)
		$F3=" ou celui de <b>".GetGenStaff($country,$Function3)."</b>";
	if($Function4)
		$F4=" ou celui de <b>".GetGenStaff($country,$Function4)."</b>";
	if($Function5)
		$F5=" ou celui de <b>".GetGenStaff($country,$Function5)."</b>";
	echo "<div><img src='images/top_secret.gif'><br>Ces données sont classifiées.Votre rang ne vous permet pas d'accéder à ces informations.
			<br>Pour accéder à ces informations, vous devez occuper le poste de <b>".GetGenStaff($country,$Function)."</b>".$F2.$F3.$F4.$F5;
}

function GetGenStaff($Pays,$Role)
{
	switch($Role)
	{
		case 1:
			if($Pays ==1)
				$txt="Befehlshaber";
			elseif($Pays == 2)
				$txt="Commander in Chief";
			elseif($Pays == 6)
				$txt="Capo di Stato Maggiore";
			elseif($Pays == 7)
				$txt="Commander in Chief";
			else
				$txt="Commandant en Chef";
		break;
		case 2:
			if($Pays ==1)
				$txt="Befehlshaber der Luftwaffe";
			elseif($Pays == 2)
				$txt="Chief of the Air Staff";
			elseif($Pays == 6)
				$txt="Capo della Regia Aeronautica";
			elseif($Pays == 7)
				$txt="Air Forces Commander";
			else
				$txt="Commandant de l'Armée de l'Air";
		break;
		/*case 2:
			if($Pays ==1)
				$txt="Chef des Generalstabes";
			elseif($Pays == 2 or $Pays == 7)
				$txt="Chief of Staff";
			elseif($Pays == 6)
				$txt="Sottocapo di Stato Maggiore";
			else
				$txt="Chef d'Etat-Major";
		break;*/
		case 3:
			if($Pays ==1)
				$txt="Offizier des Generalstabes";
			elseif($Pays == 2 or $Pays == 7)
				$txt="Headquarters Officer";
			elseif($Pays == 6)
				$txt="Ufficiale di Stato Maggiore";
			else
				$txt="Officier des infrastructures";
		break;	
		case 4:
			if($Pays ==1)
				$txt="Abwehr Offizier";
			elseif($Pays == 2)
				$txt="MI5 Officer";
			elseif($Pays == 6)
				$txt="Ufficiale di Servizio Informazioni Militare";
			elseif($Pays == 7)
				$txt="OSS Officer";
			else
				$txt="Officier du Renseignement";
		break;	
		/*case 5:
			if($Pays ==1)
				$txt="Befehlshaber des Heeres";
			elseif($Pays == 2)
				$txt="Commander in Chief of the Army";
			elseif($Pays == 6)
				$txt="Capo di Stato Maggiore";
			elseif($Pays == 7)
				$txt="Commander in Chief of the Army";
			elseif($Pays == 9)
				$txt="Commandant en Chef de l'Armée de Terre";
			else
				$txt="Commandant en Chef de l'Armée de Terre";
		break;*/
		case 6:
			if($Pays ==1)
				$txt="Z.b.V. Offizier";
			elseif($Pays == 2)
				$txt="Supply Officer";
			elseif($Pays == 6)
				$txt="Ufficiale Tecnico";
			else
				$txt="Officier du ravitaillement";
		break;
		case 7:
			if($Pays ==1)
				$txt="Befehlshaber des Ersatzheeres"; //Befehlshaber des Heeres
			elseif($Pays == 2)
				$txt="Commander of Territorial Army"; //Commander in Chief of the Army
			elseif($Pays == 6)
				$txt="Capo della Riserva Militare"; //Capo dell Esercito"
			elseif($Pays == 7)
				$txt="Commander of Army Reserve";
			elseif($Pays == 9)
				$txt="Commandant de l'Armée de Réserve";
			else
				$txt="Commandant de l'Armée de Réserve";
		break;
		case 8:
			if($Pays ==1)
				$txt="Befehlshaber der Marine";
			elseif($Pays == 2)
				$txt="Navy Officer";
			elseif($Pays == 6)
				$txt="Ufficiale della Marina";
			elseif($Pays == 7)
				$txt="Navy Officer";
			elseif($Pays == 9)
				$txt="Commandant de la flotte";
			else
				$txt="Commandant de la flotte";
		break;
		case 9:
			if($Pays ==1)
				$txt="Jagdflieger";
			elseif($Pays == 2)
				$txt="Fighter Command";
			elseif($Pays == 6)
				$txt="Caccia";
			elseif($Pays == 7)
				$txt="Fighter Command";
			else
				$txt="Chasse";
		break;
		case 10:
			if($Pays ==1)
				$txt="Kampfflieger";
			elseif($Pays == 2)
				$txt="Bomber Command";
			elseif($Pays == 6)
				$txt="Bombardamento";
			elseif($Pays == 7)
				$txt="Bomber Command";
			else
				$txt="Bombardement";
		break;
		case 11:
			if($Pays ==1)
				$txt="Aufklärungsflieger";
			elseif($Pays == 2)
				$txt="Coastal Command";
			elseif($Pays == 6)
				$txt="Ricognizione";
			elseif($Pays == 7)
				$txt="Reconnaissance";
			else
				$txt="Reconnaissance";
		break;
		case 12:
			if($Pays ==1)
				$txt="Schlachtflieger";
			elseif($Pays == 2)
				$txt="Fleet Air Arm";
			elseif($Pays == 6)
				$txt="Tuffo";
			elseif($Pays == 7)
				$txt="US Navy";
			elseif($Pays == 9)
				$txt="Aéronavale";
			else
				$txt="Attaque";
		break;
	}
	return $txt;
}

function GetRobustesse($Robustesse)
{
	if($Robustesse < 800)
		$Robustesse_nom="désastreuse";
	elseif($Robustesse < 1000)
		$Robustesse_nom="très médiocre";
	elseif($Robustesse < 1500)
		$Robustesse_nom="médiocre";
	elseif($Robustesse < 2000)
 		$Robustesse_nom="moyenne";
	elseif($Robustesse < 2500)
		$Robustesse_nom="bonne";
	elseif($Robustesse < 3000)
		$Robustesse_nom="très bonne";
	elseif($Robustesse < 3500)
		$Robustesse_nom="excellente";
	elseif($Robustesse < 4000)
		$Robustesse_nom="exceptionnelle";
	else
		$Robustesse_nom="extraordinaire";
	return $Robustesse_nom;
}

function GetManoeuvre($ManoeuvreH)
{
	if($ManoeuvreH < 50)
		$ManoeuvreH_nom="désastreux";
	elseif($ManoeuvreH < 80)
		$ManoeuvreH_nom="très médiocre";
	elseif($ManoeuvreH < 100)
		$ManoeuvreH_nom="médiocre";
	elseif($ManoeuvreH < 120)
		$ManoeuvreH_nom="moyen";
	elseif($ManoeuvreH < 140)
		$ManoeuvreH_nom="bon";
	elseif($ManoeuvreH < 160)
		$ManoeuvreH_nom="très bon";
	elseif($ManoeuvreH < 180)
		$ManoeuvreH_nom="excellent";
	elseif($ManoeuvreH < 200)
		$ManoeuvreH_nom="exceptionnel";
	else
		$ManoeuvreH_nom="extraordinaire";
	return $ManoeuvreH_nom;
}

function GetManiabilite($Maniabilite)
{
	if($Maniabilite < 50)
		$Maniabilite_nom="désastreux";
	elseif($Maniabilite < 80)
		$Maniabilite_nom="très médiocre";
	elseif($Maniabilite < 100)
		$Maniabilite_nom="médiocre";
	elseif($Maniabilite < 120)
		$Maniabilite_nom="moyen";
	elseif($Maniabilite < 140)
		$Maniabilite_nom="bon";
	elseif($Maniabilite < 160)
		$Maniabilite_nom="très bon";
	elseif($Maniabilite < 180)
		$Maniabilite_nom="excellent";
	elseif($Maniabilite < 200)
		$Maniabilite_nom="exceptionnel";
	else
		$Maniabilite_nom="extraordinaire";
	return $Maniabilite_nom;
}

function GetQualitePiste_img($QualitePiste)
{ 	
	if($QualitePiste >= 100)
		$QualitePiste_img=100;
	elseif($QualitePiste > 85)
		$QualitePiste_img=90;
	elseif($QualitePiste > 75)
		$QualitePiste_img=80;
	elseif($QualitePiste > 65)
		$QualitePiste_img=70;
	elseif($QualitePiste > 55)
		$QualitePiste_img=60;
	elseif($QualitePiste > 45)
		$QualitePiste_img=50;
	elseif($QualitePiste > 35)
		$QualitePiste_img=40;
	elseif($QualitePiste > 25)
		$QualitePiste_img=30;
	elseif($QualitePiste > 15)
		$QualitePiste_img=20;
	elseif($QualitePiste > 5)
		$QualitePiste_img=10;
	else
		$QualitePiste_img=0;
	return $QualitePiste_img;
}

function GetPrevisions($Meteo)
{
	switch($Meteo)
	{
		case 0:
			$MeteoEffect="temps clair, vent nul";
		break;
		case -100:
			$MeteoEffect="tornade";
		break;
		case -5:
			$MeteoEffect="temps clair, vent faible";
		break;
		case -10:
			$MeteoEffect="nuageux, vent faible";
		break;
		case -20:
			$MeteoEffect="pluie, vent faible";
		break;
		case -75:
			$MeteoEffect="tempête";
		break;
		case -70:
			$MeteoEffect="vent cisaillant";
		break;
		case -50:
			$MeteoEffect="neige, vent faible";
		break;
		default :
			$MeteoEffect="temps clair, vent nul";
		break;
	}
	return $MeteoEffect;
}

function GetNavire($cible_bomb)
{
	if($cible_bomb ==10)
		$cible_b=" cargo";
	elseif($cible_bomb ==11)
		$cible_b="e barge de transport";
	elseif($cible_bomb ==12)
		$cible_b=" patrouilleur";
	elseif($cible_bomb ==13)
		$cible_b="e corvette";
	elseif($cible_bomb ==14)
		$cible_b="e frégate";
	elseif($cible_bomb ==15)
		$cible_b=" destroyer";
	elseif($cible_bomb ==16)
		$cible_b=" croiseur léger";
	elseif($cible_bomb ==17)
		$cible_b=" croiseur lourd";
	elseif($cible_bomb ==18)
		$cible_b=" cuirassé";
	elseif($cible_bomb ==19)
		$cible_b=" porte-avions";
	elseif($cible_bomb ==20)
		$cible_b=" navire(s)";
	return $cible_b;
}

function GetNavireByIcon($Icon)
{
	$cible_b=GetData("Cible","ID",$Icon,"Nom");
	return $cible_b;
}

function GetBombeT($Avion_BombeT)
{
	switch($Avion_BombeT)
	{
		case 0:
			$Avion_BombeT="Standard";
		break;
		case 1:
			$Avion_BombeT="Incendiaire";
		break;
		case 2:
			$Avion_BombeT="Anti-personnel";
		break;
		case 3:
			$Avion_BombeT="Anti-tank";
		break;
		case 4:
			$Avion_BombeT="Anti-navire";
		break;
		case 5:
			$Avion_BombeT="Anti-bâtiment";
		break;
		case 6:
			$Avion_BombeT="Anti-piste";
		break;
	}
	return $Avion_BombeT;
}

function GetEM_Name($country)
{
	switch($country)
	{
		case 1:
			$Corps="Fliegerkorp";
		break;
		case 2:
			$Corps="British Air Forces HQ";
		break;
		case 3:
			$Corps="Régiment";
		break;
		case 4:
			$Corps="QG de la Zone Opérations Aériennes";
		break;
		case 6:
			$Corps="Stato Maggiore";
		break;
		case 7:
			$Corps="Headquarter";
		break;
		case 8:
			$Corps="General nyy shtab";
		break;
		default:
			$Corps="Etat-Major";
		break;
	}
	return $Corps;
}

function GetAllies($Date_Campagne)
{
	/*if($Date_Campagne >"1941-06-01")
	{
		$Allies="2,3,4,5,7,8,10,35,36";
		$Axe="1,6,9,15,18,19,20,24";
	}
	else*/if($Date_Campagne >"1940-12-01")
	{
		$Allies="2,3,4,5,7,8,10,17,35,36";
		$Axe="1,6,9,15,18,19,20,24";
	}
	else
	{
		$Allies="2,3,4,5,35,36";
		$Axe="1,6,24";
	}
	/*
	if($Date_Campagne > "1942-11-12")
	{
		$Allies="2,3,4,5,7,8,10";
		$Axe="1,6,9,15,18,19,20";
	}
	elseif($Date_Campagne > "1941-12-06")
	{
		$Allies="2,3,5,7,8,10";
		$Axe="1,4,6,9,15,18,19,20";
	}
	elseif($Date_Campagne > "1941-06-21")
	{
		$Allies="2,3,5,8,10";
		$Axe="1,4,6,15,18,19,20";
	}
	elseif($Date_Campagne > "1941-03-01")
	{
		$Allies="2,3,5,10";
		$Axe="1,4,6,15,18";
	}
	elseif($Date_Campagne > "1940-11-23")
	{
		$Allies="2,3,5,10";
		$Axe="1,4,6,18";
	}
	elseif($Date_Campagne > "1940-10-27")
	{
		$Allies="2,3,5,10";
		$Axe="1,4,6";
	}
	elseif($Date_Campagne > "1940-06-22")
	{
		$Allies="2,3,5";
		$Axe="1,4,6";
	}
	elseif($Date_Campagne > "1940-06-10")
	{
		$Allies="2,3,4,5";
		$Axe="1,6";
	}
	else
	{
		$Allies="2,3,4,5";
		$Axe="1";
	}*/
	return array ($Allies,$Axe);
}

function GetImpass($Impass)
{
	if($Impass >0)
	{
		if($Impass ==1)
			$txt='nord';
		elseif($Impass ==2)
			$txt='nord-est';
		elseif($Impass ==3)
			$txt='est';
		elseif($Impass ==4)
			$txt='sud-est';
		elseif($Impass ==5)
			$txt='sud';
		elseif($Impass ==6)
			$txt='sud-ouest';
		elseif($Impass ==7)
			$txt='ouest';
		elseif($Impass ==8)
			$txt='nord-ouest';
	}
	return $txt;
}

function GetFront($Front)
{
	if($Front ==3)
		$Front='Pacifique';
	elseif($Front ==2)
		$Front='Med';
	elseif($Front ==4)
		$Front='Nord';
	elseif($Front ==1)
		$Front='Est';
	elseif($Front ==5)
		$Front='Arctique';
	elseif($Front ==12)
		$Front='Réserve';
	elseif($Front ==99)
		$Front='Planification stratégique';
	else
		$Front='Ouest';
	return $Front;
}

function GetLongPisteMin($Unit_Type,$Avion1=0,$Avion2=0,$Avion3=0)
{
	$Jets=array(662,663);
	if($Unit_Type ==11 or $Unit_Type ==9)
		$LongPiste_mini=1400;
	elseif($Unit_Type ==2 or $Unit_Type ==6)
		$LongPiste_mini=1200;
	elseif($Unit_Type ==4)
		$LongPiste_mini=1000;
	elseif(in_array($Avion1,$Jets) or in_array($Avion2,$Jets) or in_array($Avion3,$Jets))
		$LongPiste_mini=2000;	
	elseif($Avion1 and $Avion2 and $Avion3) //if($Unit_Type ==1 or $Unit_Type ==3 or $Unit_Type ==7 or $Unit_Type ==10 or $Unit_Type ==12)
	{
		$con=dbconnecti();	
		$Engine_Nbr1=mysqli_result(mysqli_query($con,"SELECT Engine_Nbr FROM Avion WHERE ID='$Avion1'"),0);
		$Engine_Nbr2=mysqli_result(mysqli_query($con,"SELECT Engine_Nbr FROM Avion WHERE ID='$Avion2'"),0);
		$Engine_Nbr3=mysqli_result(mysqli_query($con,"SELECT Engine_Nbr FROM Avion WHERE ID='$Avion3'"),0);
		mysqli_close($con);	
		if($Engine_Nbr1 >1 or $Engine_Nbr2 >1 or $Engine_Nbr3 >1)
			$LongPiste_mini=1000;
		else
			$LongPiste_mini=500;
	}
	else
		$LongPiste_mini=2000;
	return $LongPiste_mini;
}

function Get_Compresseur($Compresseur)
{
	if($Compresseur ==2)
		$Compresseur='Haute altitude';
	elseif($Compresseur ==3)
		$Compresseur='Basse altitude';
	elseif($Compresseur ==1)
		$Compresseur='Classique';
	else
		$Compresseur='Aucun';
	return $Compresseur;
}

function Get_Injection($Injection)
{
	if($Injection >0)
		$Injection='Injection';
	else
		$Injection='Carburateur';
	return $Injection;
}

function GetAutoLog($Front,$Latitude=false,$Air=false) //Rayon des dépôts logistiques
{
	if($Air)
	{
		if($Front ==3)
			$AutoLog=750;
		elseif($Front ==1 or $Front ==4 or $Front ==5)
			$AutoLog=250;
		else
			$AutoLog=200;
	}
	else
	{
		if($Front ==3)
			$AutoLog=750;
		elseif($Front ==1 or $Front ==4 or $Front ==5)
			$AutoLog=250;
		elseif($Front ==2 and $Latitude >25 and $Latitude <33) //AFN
            $AutoLog=400;
		else
			$AutoLog=200;
	}
	return $AutoLog;
}

function GetModCT($CT,$Pays,$EM=0,$Admin=0,$Pil_Front=0)
{
	if($Pays ==3 or $Pays ==5 or $Pays ==10 or $Pays ==15 or $Pays ==16 or $Pays ==17 or $Pays ==18 or $Pays ==19)
		$CT=floor($CT/2);
	if($EM)$CT-=2;
	if($Pil_Front)$CT+=4;
	if($CT>50)$CT=50;
	elseif($CT<1)$CT=1;
	return $CT;
}

function GetPosteEM($Poste)
{
    switch($Poste)
    {
        case 21:
            $poste='Commandant en chef';
            break;
        case 2:
            $poste='Commandant aérien';
            break;
        case 3:
            $poste='Officier des infrastructures';
            break;
        case 4:
            $poste='Officier du renseignement';
            break;
        case 5:
            $poste='Commandant obsolète';
            break;
        case 6:
            $poste='Commandant armée de terre';
            break;
        case 7:
            $poste='Commandant naval';
            break;
        case 8:
            $poste='Officier logistique';
            break;
        case 9:
            $poste='Cdt Chasse';
            break;
        case 10:
            $poste='Cdt Bomb';
            break;
        case 11:
            $poste='Cdt Reco';
            break;
        case 12:
            $poste='Cdt Atk';
            break;
        case 20:
            $poste='Commandant Armée';
            break;
    }
    return $poste;
}