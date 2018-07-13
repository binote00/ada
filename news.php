<?
require_once('./jfv_inc_sessions.php');
if($_SESSION['AccountID'])
{
	include_once('./jfv_include.inc.php');
	//include_once('./menu_actus.php');
	$PlayerID=$_SESSION['PlayerID'];
	$OfficierID=$_SESSION['Officier'];
	$OfficierEMID=$_SESSION['Officier_em'];
	if($PlayerID >0 or $OfficierID >0 or $OfficierEMID >0)
	{
		$country=$_SESSION['country'];
		if(!$Date_Campagne)$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
		if($PlayerID >0)
			$Front=GetData("Pilote","ID",$PlayerID,"Front");
		elseif($OfficierEMID >0)
			$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
		elseif($OfficierID >0)
			$Front=GetData("Officier","ID",$OfficierID,"Front");
		switch($country)
		{
			case 1:
				$Etat_Major="Generalstab";
			break;
			case 2: case 7:
				$Etat_Major="Headquarters";
			break;
			case 3:
				$Etat_Major="Etat-Major";
			break;
			case 4:
				$Etat_Major="Etat-Major";
			break;
			case 6:
				$Etat_Major="Stato maggiore";
			break;
			case 8:
				$Etat_Major="General nyy shtab";
			break;
			case 9:
				$Etat_Major="Honbu";
			break;
			default:
				$Etat_Major="Accès réservé";
			break;
		}
		if($Date_Campagne >"1941-12-06")
		{
			if($Front ==3)
				$Camp="La Guerre du Pacifique";
			elseif($Front ==2)
				$Camp="La Campagne d'Afrique";
			elseif($Front ==1 or $Front ==4)
				$Camp="Le Front de l'Est";
			else
				$Camp="La Bataille de l'Atlantique";
		}
		elseif($Date_Campagne >"1941-06-21")
		{
			if($Front == 2)
				$Camp="La Campagne d'Afrique";
			elseif($Front ==1 or $Front ==4)
				$Camp="Barbarossa";
			else
				$Camp="La Bataille de l'Atlantique";
		}
		elseif($Date_Campagne >"1940-12-07")
		{
			if($Front ==2)
				$Camp="La Campagne d'Afrique";
			elseif($Front ==1 or $Front ==4)
				$Camp="Aucune campagne";
			else
				$Camp="La Bataille de l'Atlantique";
		}
		elseif($Date_Campagne >"1940-10-28")
		{
			if($Front ==2)
				$Camp="La Campagne des Balkans";
			elseif($Front ==1 or $Front ==4)
				$Camp="Aucune campagne";
			else
				$Camp="La Bataille d'Angleterre";
		}
		elseif($Date_Campagne >"1940-07-01")
		{
			if($Front ==2)
				$Camp="La Campagne d'Egypte";
			elseif($Front ==1 or $Front ==4)
				$Camp="Aucune campagne";
			else
				$Camp="La Bataille d'Angleterre";
		}
		else
			$Camp="La Bataille de France";
	}
	include_once('./ada_feed.php');
}
?>