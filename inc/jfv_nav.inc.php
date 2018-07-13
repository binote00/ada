<?php

Function GetMeteo($Saison, $Latitude = 0, $Longitude = 0, $Previsions = 0, $Nuit = 0) //Eté=3, Printemps=2, Automne=1, Hiver=0
{
    if ($Previsions > 0 and $Latitude == 0 and $Longitude == 0) {
        //$Erreur_Meteo=2000-$Station;
        //$Meteo=mt_rand(0,$Erreur_Meteo)+$Previsions;
        $MeteoMalus = $Previsions;
        switch ($Previsions) {
            case 0:
                $MeteoEffect = "temps clair, vent nul";
                break;
            case -100:
                $MeteoEffect = "tornade";
                break;
            case -5:
                $MeteoEffect = "temps clair, vent faible";
                break;
            case -10:
                $MeteoEffect = "nuageux, vent faible";
                break;
            case -20:
                $MeteoEffect = "pluie, vent faible";
                break;
            case -75:
                $MeteoEffect = "tempête";
                break;
            case -70:
                $MeteoEffect = "vent cisaillant";
                break;
            case -50:
                $MeteoEffect = "neige, vent faible";
                break;
            default :
                $MeteoEffect = "temps clair, vent nul";
                break;
        }
    } else {
        $today = getdate();
        $Hour = $today['hours'];
        $Meteo = mt_rand(0, 1000);
        if ($Hour > 15 and $Hour < 22)
            $Meteo -= 100;
        elseif ($Hour < 6 or $Hour > 22)
            $Meteo += 100;
        if ($Latitude > 55) {
            if ($Saison == 3) //Eté
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 990) //1%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //8%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 880) //2%
                {
                    $MeteoEffect = "neige, vent faible";
                    $MeteoMalus = -50;
                } elseif ($Meteo > 750) //13%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 550) //20%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 250) //30%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //25%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } elseif ($Saison == 2) //Printemps
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 975) //2.5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //7.5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 800) //10%
                {
                    $MeteoEffect = "neige, vent faible";
                    $MeteoMalus = -50;
                } elseif ($Meteo > 650) //15%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 350) //30%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 100) //25%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //10%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } elseif ($Saison == 1) //Automne
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 950) //5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 800) //15%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 550) //25%
                {
                    $MeteoEffect = "neige, vent faible";
                    $MeteoMalus = -50;
                } elseif ($Meteo > 400) //15%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 200) //20%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 50) //15%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //5%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } else //Hiver
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 950) //5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 500) //40%
                {
                    $MeteoEffect = "neige, vent faible";
                    $MeteoMalus = -50;
                } elseif ($Meteo > 450) //5%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 250) //20%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 50) //15%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //5%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            }
        } elseif ($Latitude > 45) {
            if ($Longitude > -11 and $Longitude < 2 and $Meteo < 30) $Meteo += 100; //GB
            if ($Saison == 3) //Eté
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 975) //2.5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 925) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 800) //12.5%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 600) //20%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 350) //25%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //35%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } elseif ($Saison == 2) //Printemps
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 975) //2.5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 925) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 900) //2.5%
                {
                    $MeteoEffect = "neige, vent faible";
                    $MeteoMalus = -50;
                } elseif ($Meteo > 650) //25%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 350) //30%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 100) //25%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //10%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } elseif ($Saison == 1) //Automne
            {
                if ($Meteo > 995) //0.5%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 955) //4%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //5.5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 850) //5%
                {
                    $MeteoEffect = "neige, vent faible";
                    $MeteoMalus = -50;
                } elseif ($Meteo > 600) //30%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 250) //30%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 50) //20%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //5%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } else //Hiver
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 975) //2.5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 925) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 625) //30%
                {
                    $MeteoEffect = "neige, vent faible";
                    $MeteoMalus = -50;
                } elseif ($Meteo > 450) //20%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 250) //20%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 50) //15%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //5%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            }
        } elseif ($Latitude > 35) {
            if ($Saison == 3) //Eté
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 980) //2%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 950) //3%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 900) //5%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 750) //15%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 500) //25%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //50%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } elseif ($Saison == 2) //Printemps
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 975) //2.5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 950) //2.5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 850) //10%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 600) //25%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 300) //30%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //30%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } elseif ($Saison == 1) //Automne
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 950) //5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 650) //25%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 350) //25%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 150) //20%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //15%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } else //Hiver
            {
                if ($Meteo > 995) //0.5%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 950) //4.5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 800) //10%
                {
                    $MeteoEffect = "neige, vent faible";
                    $MeteoMalus = -50;
                } elseif ($Meteo > 600) //20%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 350) //25%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 150) //20%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //15%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            }
        } else {
            if ($Saison == 3) //Eté
            {
                if ($Meteo > 995) //0.5%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 950) //4.5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //5%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 750) //15%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 550) //20%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //55%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } elseif ($Saison == 2) //Printemps
            {
                if ($Meteo > 999) //0.1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 950) //5%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 800) //10%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 600) //20%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 400) //20%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //40%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } elseif ($Saison == 1) //Automne
            {
                if ($Meteo > 990) //1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 950) //4%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 600) //30%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 400) //20%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 200) //20%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //20%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            } else //Hiver
            {
                if ($Meteo > 990) //1%
                {
                    $MeteoEffect = "tornade";
                    $MeteoMalus = -100;
                } elseif ($Meteo > 950) //4%
                {
                    $MeteoEffect = "tempête";
                    $MeteoMalus = -75;
                } elseif ($Meteo > 900) //5%
                {
                    $MeteoEffect = "vent cisaillant";
                    $MeteoMalus = -70;
                } elseif ($Meteo > 650) //25%
                {
                    $MeteoEffect = "pluie, vent faible";
                    $MeteoMalus = -20;
                } elseif ($Meteo > 400) //25%
                {
                    $MeteoEffect = "nuageux, vent faible";
                    $MeteoMalus = -10;
                } elseif ($Meteo > 200) //20%
                {
                    $MeteoEffect = "temps clair, vent faible";
                    $MeteoMalus = -5;
                } else //20%
                {
                    $MeteoEffect = "temps clair, vent nul";
                    $MeteoMalus = 0;
                }
            }
        }
        /*$Erreur_Meteo=2000-$Station;
        $Meteo=round(mt_rand(0,10000)+($Saison*500)+((60-$Latitude)*100))+$Erreur_Meteo;
        if($Meteo <=1500 and $Saison ==3)$Meteo=1501;
        if($Meteo >8000 and $Saison ==0)$Meteo=5000;
        switch($Meteo)
        {
            case ($Meteo > 8000):
                $MeteoEffect="temps clair, vent nul";
                $MeteoMalus=0;
            break;
            case ($Meteo > 7995):
                $MeteoEffect="tornade";
                $MeteoMalus=-100;
            break;
            case ($Meteo > 6000):
                $MeteoEffect="temps clair, vent faible";
                $MeteoMalus=-5;
            break;
            case ($Meteo > 4000):
                $MeteoEffect="nuageux, vent faible";
                $MeteoMalus=-10;
            break;
            case ($Meteo > 2000):
                $MeteoEffect="pluie, vent faible";
                $MeteoMalus=-20;
            break;
            case ($Meteo > 1550):
                $MeteoEffect="nuageux, vent faible";
                $MeteoMalus=-10;
            break;
            case ($Meteo > 1524):
                $MeteoEffect="tempête";
                $MeteoMalus=-75;
            break;
            case ($Meteo > 1499):
                $MeteoEffect="vent cisaillant";
                $MeteoMalus=-70;
            break;
            case ($Meteo > 499):
                $MeteoEffect="neige, vent faible";
                $MeteoMalus=-50;
            break;
            case ($Meteo < 500):
                $MeteoEffect="nuageux, vent faible";
                $MeteoMalus=-10;
            break;
            default :
                $MeteoEffect="temps clair, vent nul";
                $MeteoMalus=0;
            break;
        }*/
    }
    if ($Nuit)
        $MeteoMalus -= 85;
    return array($MeteoEffect, $MeteoMalus);
}

/*Function Rencontre_Random_PJ($Unite,$country,$Cible,$Nuit=0,$IA=0)
{
	if($IA)
		$query="SELECT ID,Unit,Avion,Alt FROM Pilote_IA WHERE Cible='$Cible' AND Unit<>'$Unite' AND Pays<>'$country' AND Avion>0 AND Actif=1 ORDER BY RAND() LIMIT 1";
	elseif($Nuit)
		$query="SELECT ID,Unit,Avion,Alt FROM Pilote_IA WHERE (Couverture_Nuit='$Cible' OR Cible='$Cible') AND Escorte=0 AND Couverture=0 AND Unit<>'$Unite' AND Avion>0 AND Actif=1 ORDER BY RAND() LIMIT 1";
	else
		$query="SELECT ID,Unit,Avion,Alt FROM Pilote_IA WHERE (Couverture='$Cible' OR Escorte='$Cible' OR Cible='$Cible') AND Unit<>'$Unite' AND Pays<>'$country' AND Avion>0 AND Actif=1 ORDER BY RAND() LIMIT 1";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Pilote_eni=$data['ID'];
			$Avion_eni=$data['Avion'];
			$Unit_eni=$data['Unit'];
			$random_alt=$data['Alt'];
		}
		mysqli_free_result($result);
	}
	if(!$random_alt)
	{
		if($Avion_eni)		
			$random_alt=mt_rand(1000,GetData("Avion","ID",$Avion_eni,"Plafond"));
		else
			$random_alt=mt_rand(1000,3000);
	}
	$cardinal=mt_rand(1,8);
	$con=dbconnecti();
	$Esc_Nbr=mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Cible='$Cible' AND Unit='$Unit_eni' AND Avion>0 AND Actif=1");
	mysqli_close($con);
	switch($cardinal)
	{
		case 1:
			$direc="u Nord";
		break;
		case 2:
			$direc="u Nord-Est";
		break;
		case 3:
			$direc="e l'Est";
		break;
		case 4:
			$direc="u Sud-Est";
		break;
		case 5:
			$direc="u Sud";
		break;
		case 6:
			$direc="u Sud-Ouest";
		break;
		case 7:
			$direc="e l'Ouest";
		break;
		case 8:
			$direc="u Nord-Ouest";
		break;
	}
	if($Esc_Nbr >12)$Esc_Nbr=12;
	return array($Pilote_eni,$Avion_eni,$Unit_eni,$direc,$random_alt,$Esc_Nbr);
}

Function Rencontre_Random($Longitude, $Latitude, $Cible, $random_unit=0, $Pays=0, $Type=0, $PlayerID=0, $Level=0, $Front=0) //$Pays 1=Axe, 2=Alliés, 0=Neutres
{
	if($Level >0)
	{
		$Level_min=$Level-2;
		$Level_max=$Level+2;
		if($Level_min <1)$Level_min=1;
		if($Front ==1 or $Front ==4 or $Front ==5)
			$Allies="1,6,8,15,18,20";
		elseif($Front ==2)
			$Allies="1,2,4,6";
		elseif($Front ==3)
			$Allies="2,5,7,9";
		else
			$Allies="1,2,4";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT ID,Pays,Nom,Type FROM Avion WHERE Pays IN (".$Allies.") AND Etat=1 AND Prototype=0 AND (Rating BETWEEN '$Level_min' AND '$Level_max') ORDER BY RAND() LIMIT 1");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$random_avioneni=$data['ID'];
				$avioneni=$data['Nom'];
				$Type=$data['Type'];
				$Pays_eni=$data['Pays']; 
			}
			mysqli_free_result($result);
		}
		if($Pays_eni ==2 and $Type ==7)
			$Type=2;
		elseif($Type ==11)
			$Type=2;
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT ID FROM Unit WHERE Pays='$Pays_eni' AND Type='$Type' ORDER BY RAND() LIMIT 1");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$random_unit=$data['ID'];
			}
			mysqli_free_result($result2);
		}		
		$rand_avion=mt_rand(1,3);
		$Reputation=$Level*10000;
	}
	else
	{
		if($random_unit ==0)
		{
			if($Type <1)
			{
				$Lat_base_min=$Latitude-0.50;
				$Lat_base_max=$Latitude+0.50;
				$Long_base_min=$Longitude-1.00;
				$Long_base_max=$Longitude+1.00;				
				$i=0;
				if($Pays ==0)
				{
					$query="SELECT Unit.ID,Unit.Nom FROM Unit,Lieu WHERE Unit.Base=Lieu.ID AND Unit.Etat=1 AND (Lieu.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') 
					AND (Lieu.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND Lieu.Zone<>6 AND Lieu.QualitePiste >50 AND (Unit.Avion1_Nbr+Unit.Avion2_Nbr+Unit.Avion3_Nbr >0)
					ORDER BY RAND() LIMIT 1";
				}
				else
				{
					$query="SELECT Unit.ID,Unit.Nom FROM Unit,Lieu WHERE Unit.Base=Lieu.ID AND Unit.Pays='$Pays' AND Unit.Etat=1 AND (Lieu.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') 
					AND (Lieu.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND Lieu.Zone<>6 AND Lieu.QualitePiste >50 AND (Unit.Avion1_Nbr+Unit.Avion2_Nbr+Unit.Avion3_Nbr >0)
					ORDER BY RAND() LIMIT 1";
				}		
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						//echo $data['ID']." ".$data['Nom'];
						$random_unit=$data['ID'];
						mysqli_free_result($result);
						break;
					}
				}
			}
			elseif($Type ==17)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Unit.ID FROM Pilote,Unit WHERE Pilote.Unit=Unit.ID AND Unit.Type IN (2,4) AND Pilote.S_Nuit=1 AND Pilote.S_Cible='$Cible' AND DATE(Pilote.Credits_date)=DATE(NOW()) ORDER BY RAND() LIMIT 1");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result))
					{
						$random_unit=$data[0];
					}
					mysqli_free_result($result);
				}
			}			
			if(!$random_unit)
			{
				$i=0;
				if($Front ==10)
				{
					if(GetData("Lieu","ID",$Cible,"Flag") ==GetData("Pilote","ID",$PlayerID,"Pays"))
						$query="SELECT Unit.ID,Unit.Avion1,Unit.Avion2,Unit.Avion3,Lieu.Latitude,Lieu.Longitude FROM Unit,Lieu WHERE Unit.Base=Lieu.ID AND Unit.Etat=1 AND Unit.Type IN (2,3,4,5,7,11) AND Lieu.QualitePiste >50 AND (Unit.Avion1_Nbr + Unit.Avion2_Nbr + Unit.Avion3_Nbr >0) ORDER BY RAND()";
					else
						$query="SELECT Unit.ID,Unit.Avion1,Unit.Avion2,Unit.Avion3,Lieu.Latitude,Lieu.Longitude FROM Unit,Lieu WHERE Unit.Base=Lieu.ID AND Unit.Etat=1 AND Unit.Type IN (1,4,5,12) AND Lieu.QualitePiste >50 AND (Unit.Avion1_Nbr + Unit.Avion2_Nbr + Unit.Avion3_Nbr >0) ORDER BY RAND()";
				}
				elseif($Pays ==0)
				{
					if(GetData("Lieu","ID",$Cible,"Flag") ==GetData("Pilote","ID",$PlayerID,"Pays"))
						$query="SELECT Unit.ID,Unit.Avion1,Unit.Avion2,Unit.Avion3,Lieu.Latitude,Lieu.Longitude FROM Unit,Lieu WHERE Unit.Base=Lieu.ID AND Unit.Etat=1 AND Unit.Type IN (1,2,3,4,5,7,10,11,12) AND Lieu.QualitePiste >50 AND (Unit.Avion1_Nbr + Unit.Avion2_Nbr + Unit.Avion3_Nbr >0) ORDER BY RAND()";
					else
						$query="SELECT Unit.ID,Unit.Avion1,Unit.Avion2,Unit.Avion3,Lieu.Latitude,Lieu.Longitude FROM Unit,Lieu WHERE Unit.Base=Lieu.ID AND Unit.Etat=1 AND Unit.Type IN (1,4,5,12) AND Lieu.QualitePiste >50 AND (Unit.Avion1_Nbr + Unit.Avion2_Nbr + Unit.Avion3_Nbr >0) ORDER BY RAND()";
				}
				else
				{
					//Escorte Convoi
					if($Type ==9)
						$query="SELECT Unit.ID,Unit.Avion1,Unit.Avion2,Unit.Avion3,Lieu.Latitude,Lieu.Longitude FROM Unit,Lieu WHERE Unit.Base=Lieu.ID AND Unit.Etat=1 AND Unit.Type IN (2,3,4,5,7,9,10) AND Unit.Pays='$Pays' AND (Unit.Avion1_Nbr + Unit.Avion2_Nbr + Unit.Avion3_Nbr >0) ORDER BY RAND()";
					else
						$query="SELECT Unit.ID,Unit.Avion1,Unit.Avion2,Unit.Avion3,Lieu.Latitude,Lieu.Longitude FROM Unit,Lieu WHERE Unit.Base=Lieu.ID AND Unit.Etat=1 AND Unit.Type IN (1,2,3,4,5,7,11,12) AND Unit.Pays='$Pays' AND Lieu.QualitePiste >50 AND (Unit.Avion1_Nbr + Unit.Avion2_Nbr + Unit.Avion3_Nbr >0) ORDER BY RAND()";
				}
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						//$random_unit=$data['ID']; //$Unit=$data[0];
						//$Type=$data['Type']; //$Type=$data[1];					
						//$Base=$data['Base']; //$Base=$data[2];
						$Distance_Max=GetDistance(0,0,$Longitude,$Latitude,$data['Longitude'],$data['Latitude']);
						//Chk si l'avion de l'unité peut se trouver dans la région
						if($Front ==10)
						{
							$autonomie_avion1=GetData("Avion","ID",$data['Avion1'],"Autonomie")/3;
							$autonomie_avion2=GetData("Avion","ID",$data['Avion2'],"Autonomie")/3;
							$autonomie_avion3=GetData("Avion","ID",$data['Avion3'],"Autonomie")/3;
						}
						else
						{
							$autonomie_avion1=GetData("Avion","ID",$data['Avion1'],"Autonomie")/4;
							$autonomie_avion2=GetData("Avion","ID",$data['Avion2'],"Autonomie")/4;
							$autonomie_avion3=GetData("Avion","ID",$data['Avion3'],"Autonomie")/4;
						}
						if($Distance_Max[0] <= $autonomie_avion1 or $Distance_Max[0] <= $autonomie_avion2 or $Distance_Max[0] <= $autonomie_avion3)
						{
							$random_unit=$data['ID'];
							mysqli_free_result($result);
							unset($data);
							//mail('binote@hotmail.com','Aube des Aigles: Rencontre_Random No Result Classique',"No Result : Pays=".$Pays.", Unit : ".$random_unit." , Longitude=".$Longitude.", Latitude=".$Latitude);
							break;
						}
						$i++;
						if($i >200)
						{
							mysqli_free_result($result);
							unset($data);
							$random_unit=false;
							break;
						}
					}
				}
				else
					mail('binote@hotmail.com','Aube des Aigles: Rencontre_Random No Result Classique',"No Result : Pays=".$Pays.", Longitude=".$Longitude.", Latitude=".$Latitude);
			}			
			if(!$random_unit)
				$random_unit=GetData("Pilote","ID",$PlayerID,"Unit");
		}		
		$rand_avion=mt_rand(1,3);
		$random_avioneni=GetData("Unit","ID",$random_unit,"Avion".$rand_avion);
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Type FROM Avion WHERE ID='$random_avioneni'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$avioneni=$data['Nom'];
				$Type=$data['Type'];
			}
			mysqli_free_result($result);
		}
		if($Type ==1 or $Type ==4 or $Type ==12)
			$Reputation=GetData("Pilote","ID",$PlayerID,"Reputation");
	}	
	if($Type ==1 or $Type ==4 or $Type ==12)
		$nbreni=mt_rand(1,4)+(floor($Reputation/50000));
	elseif($Type ==3 or $Type ==6 or $Type ==9)
		$nbreni=mt_rand(1,2);
	elseif($Type ==7 or $Type ==10)
		$nbreni=mt_rand(3,12);
	else
	{
		$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
		//Formations Bombardiers
		if($Date_Campagne >"1943-01-01")
			$nbreni=mt_rand(1,6)*3;
		elseif($Date_Campagne >"1941-12-01")
			$nbreni=mt_rand(1,4)*3;
		elseif($Date_Campagne >"1940-06-30")
			$nbreni=mt_rand(1,2)*3;
		else
			$nbreni=3;
	}
	$random_alt=mt_rand(1000,GetData("Avion","ID",$random_avioneni,"Plafond"));
	//$random_lieu=mt_rand (1,56);
	//$lieueni=GetData("Lieu","ID",$random_lieu,"Nom");
	$cardinal=mt_rand(1,8);
	switch($cardinal)
	{
		case 1:
			$lieueni="u Nord";
		break;
		case 2:
			$lieueni="u Nord-Est";
		break;
		case 3:
			$lieueni="e l'Est";
		break;
		case 4:
			$lieueni="u Sud-Est";
		break;
		case 5:
			$lieueni="u Sud";
		break;
		case 6:
			$lieueni="u Sud-Ouest";
		break;
		case 7:
			$lieueni="e l'Ouest";
		break;
		case 8:
			$lieueni="u Nord-Ouest";
		break;
	}	
	if(!$avioneni or !$random_alt or !$random_unit or !$Type)
		mail ('binote@hotmail.com','Aube des Aigles: Rencontre_Random Last',"Generate error : Pays=".$Pays." / Type=".$Type." / Unit_random=".$random_unit." / avion_eni=".$avioneni." / alt=".$random_alt);
	
	return array($nbreni,$random_avioneni,$avioneni,$random_lieu,$lieueni,$random_alt,$random_unit);
}*/

function Random_Escort($PlayerID, $Pays, $Type = 0, $Unit = 0, $Longitude = 0, $Latitude = 0)
{
    if ($Unit) {
        $query = "SELECT Avion1,Avion2,Avion3 FROM Unit WHERE ID='$Unit'";
        $con = dbconnecti();
        $result = mysqli_query($con, $query);
        mysqli_close($con);
        if ($result) {
            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Av = mt_rand(1, 3);
                if ($Av == 3)
                    $Escort = $data['Avion3'];
                elseif ($Av == 2)
                    $Escort = $data['Avion2'];
                else
                    $Escort = $data['Avion1'];
            }
            mysqli_free_result($result);
        }
    } else {
        if ($Type == 10) {
            $query = "SELECT DISTINCT ID FROM Avion WHERE Pays='$Pays' AND Etat='1' AND Type=10 ORDER BY RAND() LIMIT 1";
            $con = dbconnecti();
            $result = mysqli_query($con, $query);
            mysqli_close($con);
            if ($result) {
                $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $Escort = $data['ID'];
                mysqli_free_result($result);
            }
        } else {
            if ($Longitude and $Latitude) {
                $Lat_base_min = $Latitude - 10.00;
                $Lat_base_max = $Latitude + 10.00;
                $Long_base_min = $Longitude - 20.00;
                $Long_base_max = $Longitude + 20.00;
                $query = "SELECT Unit.Avion1,Unit.Avion2,Unit.Avion3 FROM Unit,Lieu 
				WHERE Lieu.ID=Unit.Base AND Unit.Pays='$Pays' AND Unit.Type IN (2,7,11) AND Unit.Etat=1 
				AND (Lieu.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') 
				AND (Lieu.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
				ORDER BY RAND() LIMIT 1";
                $con = dbconnecti();
                $result = mysqli_query($con, $query);
                mysqli_close($con);
                if (mysqli_num_rows($result)) {
                    while ($data = mysqli_fetch_array($result, MYSQLI_NUM)) {
                        $Av = mt_rand(0, 2);
                        $Escort = $data[$Av];
                    }
                    mysqli_free_result($result);
                }
            }
            if (!$Escort) {
                $query = "SELECT DISTINCT ID FROM Avion WHERE Pays='$Pays' AND Etat='1' AND Type IN (2,7,11) ORDER BY RAND() LIMIT 1";
                $con = dbconnecti();
                $result = mysqli_query($con, $query);
                mysqli_close($con);
                if ($result) {
                    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $Escort = $data['ID'];
                    mysqli_free_result($result);
                }
            }
        }
    }
    return $Escort;
}

function GetEnnemi($Pays, $Latitude)
{
    $Date_Campagne = GetData("Conf_Update", "ID", 2, "Date");
    if (IsAxe($Pays)) {
        if ($Date_Campagne > "1940-06-22")
            $Renc_Pays = 2;
        elseif ($Date_Campagne > "1940-05-28") {
            if (mt_rand(0, 1) == 0)
                $Renc_Pays = 2;
            else
                $Renc_Pays = 4;
        } else
            $Renc_Pays = mt_rand(2, 4);
    } else {
        if ($Date_Campagne > "1940-12-01") {
            if ($Latitude < 47.5) {
                if (mt_rand(0, 2) == 0)
                    $Renc_Pays = 1;
                else
                    $Renc_Pays = 6;
            } else
                $Renc_Pays = 1;
        } elseif ($Date_Campagne > "1940-06-09") {
            if ($Latitude < 47.5)
                $Renc_Pays = 6;
            else
                $Renc_Pays = 1;
        } else
            $Renc_Pays = 1;
    }
    return $Renc_Pays;
}

function RetireAvionFromUnit($Unite, $avion, $Nbr = -1)
{
    //Avion retiré de l'inventaire de l'unité
    $con = dbconnecti();
    $result = mysqli_query($con, "SELECT Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE ID='$Unite'");
    mysqli_close($con);
    if ($result) {
        $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if ($data['Avion1'] == $avion and $data['Avion1_Nbr'] > 0) {
            UpdateData("Unit", "Avion1_Nbr", $Nbr, "ID", $Unite);
        } elseif ($data['Avion2'] == $avion and $data['Avion2_Nbr'] > 0) {
            UpdateData("Unit", "Avion2_Nbr", $Nbr, "ID", $Unite);
        } elseif ($data['Avion3'] == $avion and $data['Avion3_Nbr'] > 0) {
            UpdateData("Unit", "Avion3_Nbr", $Nbr, "ID", $Unite);
        }
    } else
        mail('binote@hotmail.com', 'Aube des Aigles: Erreur Select RetireAvionFromUnit', "Unite : " . $Unite . " ; Avion : " . $avion);
}

function AddPatrouille($Avion_db, $avion, $PlayerID, $Unite_win, $Lieu, $alt, $Cycle = 0, $Type = 0)
{
    if ($Avion_db == "Avions_Persos" or $Avion_db == "Avions_Sandbox")
        $avion = GetData($Avion_db, "ID", $avion, "ID_ref");
    $date = date('Y-m-d G:i');
    $query = "INSERT INTO Patrouille (Avion, Joueur, Unite, Lieu, Date, Cycle)
	VALUES ('$avion','$PlayerID','$Unite_win','$Lieu','$date','$Cycle')";
    $query2 = "REPLACE INTO Patrouille_live (Type, Avion, Joueur, Unite, Lieu, Altitude, Cycle, Date)
	VALUES ('$Type','$avion','$PlayerID','$Unite_win','$Lieu','$alt','$Cycle','$date')";
    $con = dbconnecti();
    $ok = mysqli_query($con, $query);
    $ok2 = mysqli_query($con, $query2);
    mysqli_close($con);
    if (!$ok) {
        $msg .= "Erreur de mise à jour" . mysqli_error($con);
        mail('binote@hotmail.com', 'Aube des Aigles: AddPatrouille Error', $msg);
    }
}

function AddEscorte($Avion_db, $avion, $PlayerID, $Lieu, $Escorte, $Escorte_nbr, $Unite, $alt, $Cycle = 0)
{
    if ($Avion_db == "Avions_Persos" or $Avion_db == "Avions_Sandbox")
        $avion = GetData($Avion_db, "ID", $avion, "ID_ref");
    $date = date('Y-m-d G:i');
    $query = "INSERT INTO Escorte (Avion, Joueur, Unite, Lieu, Date, Escorte, Escorte_nbr)
	VALUES ('$avion','$PlayerID','$Unite','$Lieu','$date','$Escorte','$Escorte_nbr')";
    $query2 = "REPLACE INTO Patrouille_live (Type, Avion, Joueur, Unite, Lieu, Altitude, Cycle, Date)
	VALUES (1,'$avion','$PlayerID','$Unite','$Lieu','$alt','$Cycle','$date')";
    $con = dbconnecti();
    $ok = mysqli_query($con, $query);
    $ok2 = mysqli_query($con, $query2);
    mysqli_close($con);
    if (!$ok) {
        $msg = "Erreur de mise à jour " . mysqli_error($con);
        mail('binote@hotmail.com', 'Aube des Aigles: AddEscorte Error', $msg);
    }
    if (!$ok2) {
        $msg = "Erreur de mise à jour " . mysqli_error($con);
        mail('binote@hotmail.com', 'Aube des Aigles: AddEscorte Live Error', $msg);
    }
}

/*function DestockUnit($Unite,$Arme1Avion,$Arme2Avion,$Mun1,$Mun2,$Autonomie,$Avion_db,$avion)
{
	$Engine=GetData($Avion_db,"ID",$avion,"Engine");
	if($Engine)
	{
		$Carburant=GetData("Moteur","ID",$Engine,"Carburant");
		$Stock_Essence="Stock_Essence_".$Carburant;
		UpdateData("Unit",$Stock_Essence,$essence,"ID",$Unite);
	}
	$Calibre1="Stock_Munitions_".round(GetData("Armes","ID",$Arme1Avion,"Calibre"));
	$Calibre2="Stock_Munitions_".round(GetData("Armes","ID",$Arme2Avion,"Calibre"));
	$Mun1_stock=0-$Mun1;
	$Mun2_stock=0-$Mun2;
	$Essence_stock=0-$Autonomie;
	UpdateData("Unit",$Calibre1,$Mun1_stock,"ID",$Unite);
	UpdateData("Unit",$Calibre2,$Mun2_stock,"ID",$Unite);
	UpdateData("Unit",$Stock_Essence,$Essence_stock,"ID",$Unite);
}*/