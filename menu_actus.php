<?
$country=$_SESSION['country'];
$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
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
		$Camp="Barbarossa";
	elseif($Front ==5)
		$Camp="L'arctique";
	else
		$Camp="La Bataille de l'Atlantique";
}
elseif($Date_Campagne >"1941-06-21")
{
	if($Front ==2)
		$Camp="La Campagne d'Afrique";
	elseif($Front ==1 or $Front ==4)
		$Camp="Barbarossa";
	elseif($Front ==5)
		$Camp="L'arctique";
	else
		$Camp="La Bataille de l'Atlantique";
}
elseif($Date_Campagne >"1940-12-07")
{
	if($Front ==2)
		$Camp="La Campagne d'Afrique";
	elseif($Front ==1)
		$Camp="Aucune campagne";
	elseif($Front ==5)
		$Camp="L'arctique";
	else
		$Camp="La Bataille de l'Atlantique";
}
elseif($Date_Campagne >"1940-10-28")
{
	if($Front ==2)
		$Camp="La Campagne des Balkans";
	elseif($Front ==1)
		$Camp="Aucune campagne";
	elseif($Front ==5)
		$Camp="L'arctique";
	else
		$Camp="La Bataille d'Angleterre";
}
elseif($Date_Campagne >"1940-07-01")
{
	if($Front ==2)
		$Camp="La Campagne d'Egypte";
	elseif($Front ==1)
		$Camp="Aucune campagne";
	elseif($Front ==5)
		$Camp="L'arctique";
	else
		$Camp="La Bataille d'Angleterre";
}
else
	$Camp="La Bataille de France";
echo "<h1>".$Camp."</h1>";
?>