<?php
function Get_Retraite($Front,$country,$Latitude_front,$Cat=false)
{
	if($Front ==3)
	{
		if($country ==2)
			$Retraite=1572;
		elseif($country ==7)
			$Retraite=1366;
		elseif($country ==9)
			$Retraite=1368;
	}
	elseif($Front ==2)
	{
		if($country ==7)
			$Retraite=586; //586 après le 11/11/42, sinon 2149		
		elseif($Latitude_front >36.5)
		{
			if($country ==2)
				$Retraite=521;
			elseif($country ==1)
				$Retraite=198;
			elseif($country ==4)
				$Retraite=432;
			elseif($country ==6)
				$Retraite=438;
		}
		else
		{
			if($country ==2)
				$Retraite=521;
			elseif($country ==1)
				$Retraite=198;
			elseif($country ==4)
				$Retraite=432;	
			elseif($country ==6)
				$Retraite=438; //453
		}
	}
	elseif($Front ==1)
	{
		if($country ==1 or $country ==6)
			$Retraite=958;
		elseif($country ==8)
			$Retraite=618;
		elseif($country ==18)
			$Retraite=683;
		elseif($country ==19)
			$Retraite=1089;
	}
	elseif($Front ==4)
	{
		if($country ==1 or $country ==6)
			$Retraite=613;
		elseif($country ==8)
			$Retraite=601;
		elseif($country ==20)
			$Retraite=1419;
	}
	elseif($Front ==5)
	{
		if($country ==1)
			$Retraite=895;
		elseif($country ==2)
			$Retraite=898;
		elseif($country ==7)
			$Retraite=2079;
		elseif($country ==8)
			$Retraite=614;
		elseif($country ==20)
			$Retraite=1419;
        elseif($country ==35)
            $Retraite=896;
	}
	elseif($Front ==99)
	{
		if($country ==1)
		{
			$Retraite=2;
			if($Cat >16)$Retraite=212;
		}
		elseif($country ==2)
			$Retraite=269;
		elseif($country ==3)
			$Retraite=4;
		elseif($country ==4)
		{
			$Retraite=586; //1 ou 201
			if($Cat >16)$Retraite=201;
		}
		elseif($country ==5)
			$Retraite=379;
		elseif($country ==6)
			$Retraite=189;
		elseif($country ==7)
			$Retraite=2149;
		elseif($country ==8)
		{
			$Retraite=601;
			if($Cat >16)$Retraite=614;
		}
		elseif($country ==9)
			$Retraite=1368;
        elseif($country ==10)
            $Retraite=689;
        elseif($country ==15)
            $Retraite=709;
        elseif($country ==17)
            $Retraite=1079;
		elseif($country ==18)
			$Retraite=683;
		elseif($country ==19)
			$Retraite=1089;
        elseif($country ==20)
            $Retraite=1419;
        elseif($country ==35)
            $Retraite=896;
	}
	else
	{
		if($country ==1)
			$Retraite=212;
		elseif($country ==2)
			$Retraite=269;
		elseif($country ==3)
			$Retraite=118;
		elseif($country ==5)
			$Retraite=379;
		elseif($country ==4)
			$Retraite=267; //1 ou 201
		elseif($country ==6)
			$Retraite=189;
		elseif($country ==7)
			$Retraite=2149;	//2149 ou 1e port conquis en Europe	
		elseif($country ==8)
			$Retraite=601;
		elseif($country ==9)
			$Retraite=1368;
        elseif($country ==10)
            $Retraite=689;
        elseif($country ==20)
            $Retraite=1419;
        elseif($country ==35)
            $Retraite=896;
	}
	if(!$Retraite)
	{
		$con=dbconnecti();
		$Retraite=mysqli_result(mysqli_query($con,"SELECT Base_Arriere FROM Pays WHERE Pays_ID='$country' AND Front='$Front'"),0);
		mysqli_close($con);
	}
	return $Retraite;
}

/*function Get_Transit($country,$Front_Ori,$Front_Dest)
{
	if($country ==1)
	{
		if($Front_Ori ==0)
		{
			if($Front_Dest ==1)
				$Transit=218;
			elseif($Front_Dest ==2)
				$Transit=198;
			elseif($Front_Dest ==4)
				$Transit=2;
			elseif($Front_Dest ==5)
				$Transit=704;
		}
		elseif($Front_Ori ==1)
		{
			if($Front_Dest ==0)
				$Transit=218;
			elseif($Front_Dest ==2)
				$Transit=709;
			elseif($Front_Dest ==4)
				$Transit=615; //967
		}
		elseif($Front_Ori ==2)
		{
			if($Front_Dest ==0)
				$Transit=198;
			elseif($Front_Dest ==1)
				$Transit=709;
		}
		elseif($Front_Ori ==4)
		{
			if($Front_Dest ==0)
				$Transit=2;
			elseif($Front_Dest ==1)
				$Transit=615; //967
		}
		elseif($Front_Ori ==5)
		{
			if($Front_Dest ==0)
				$Transit=704;
		}
	}
	elseif($country ==2)
	{
		if($Front_Ori ==0)
		{
			if($Front_Dest ==2)
				$Transit=344;
			elseif($Front_Dest ==5)
				$Transit=898;
		}
		elseif($Front_Ori ==2)
		{
			if($Front_Dest ==0)
				$Transit=344;
			elseif($Front_Dest ==3)
				$Transit=1896; //2015
		}
		elseif($Front_Ori ==3)
		{
			if($Front_Dest ==2)
				$Transit=1896; //2012-2013
		}
		elseif($Front_Ori ==5)
		{
			if($Front_Dest ==0)
				$Transit=898;
		}
	}
	elseif($country ==4)
	{
		if($Front_Ori ==0)
		{
			if($Front_Dest ==2)
				$Transit=201;
		}
		elseif($Front_Ori ==2)
		{
			if($Front_Dest ==0)
				$Transit=201;
		}
	}
	elseif($country ==6)
	{
		if($Front_Ori ==0)
		{
			if($Front_Dest ==2)
				$Transit=198;
			elseif($Front_Dest ==1)
				$Transit=199;
		}
		elseif($Front_Ori ==1)
		{
			if($Front_Dest ==0)
				$Transit=199;
		}
		elseif($Front_Ori ==2)
		{
			if($Front_Dest ==0)
				$Transit=198;
		}
	}
	elseif($country ==7)
	{
		if($Front_Ori ==0)
		{
			if($Front_Dest ==3)
				$Transit=2149;
			elseif($Front_Dest ==5)
				$Transit=2079;
		}
		elseif($Front_Ori ==3)
		{
			if($Front_Dest ==0)
				$Transit=1567;
		}
		elseif($Front_Ori ==5)
		{
			if($Front_Dest ==0)
				$Transit=2079;
		}
	}
	elseif($country ==8)
	{
		if($Front_Ori ==1)
		{
			if($Front_Dest ==4)
				$Transit=1280;
		}
		elseif($Front_Ori ==4)
		{
			if($Front_Dest ==1)
				$Transit=1280;
			elseif($Front_Dest ==5)
				$Transit=614;
		}
		elseif($Front_Ori ==5)
		{
			if($Front_Dest ==4)
				$Transit=614;
		}
	}
	return $Transit;
}*/

function GetEmboutMax($ValeurStrat,$Placement,$Zone=0,$Front=0)
{
    if($Zone ==6){
        if($Front ==3)
            $Embout_Max=10;
        else
            $Embout_Max=5;
    }else{
        if(!$Placement)
            $Embout_Max=5;
        else
            $Embout_Max=floor(3+($ValeurStrat/4));
    }
	return $Embout_Max;
}

function Auto_max($Auto,$Zone,$Mobile,$Front,$Type,$Matos=0,$Lat=0)
{
    if($Front ==1 or $Front ==4 or $Front ==5)
        $Auto_max=100;
    elseif($Front ==3)
        $Auto_max=300;
    elseif($Front ==2 and $Lat<37.3)
        $Auto_max=100;
    else
        $Auto_max=50;
    if($Zone==0 and ($Mobile ==1 or $Mobile ==2 or $Mobile ==6 or $Mobile ==7))
        $Auto_max*=1.2;
    elseif($Type ==97 and ($Zone ==1 or $Zone ==4 or $Zone ==5))
        $Auto_max*=1.2;
    elseif($Matos ==14 or $Matos ==15 or $Matos ==28 or $Matos ==30 or $Matos ==9999)
        $Auto_max*=1.2;
    if($Auto >$Auto_max)$Auto=$Auto_max;
    if($Type ==6 and ($Zone ==4 or $Zone ==5))
        return '<i class="text-danger">'.floor($Auto).'</i>';
    else
        return floor($Auto);
}

function IsSkill($Skill, $Officier, $Officier_em=false)
{
	if($Officier_em)
		$DB='Officier_em';
	else
		$DB='Officier';
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Skill1,Skill2,Skill3 FROM $DB WHERE ID='$Officier'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Skill1=$data['Skill1'];
			$Skill2=$data['Skill2'];
			$Skill3=$data['Skill3'];
		}
		mysqli_free_result($result);
	}
	if($Skill1 ==$Skill or $Skill2 ==$Skill or $Skill3 ==$Skill)
		return true;
	else
		return false;
}

function Get_Dmg($Mun,$Calibre,$Blindage_eni,$Distance,$Degats,$Perf=0,$Portee=100,$PorteeMax=500)
{
	if(!$Perf)$Perf=$Calibre;
	if($Distance >$PorteeMax)
		$Degats=1;
	elseif($Distance >$Portee)
		$Degats/=($PorteeMax/$Distance);
	elseif($Distance >$Portee/2)
		$Degats*=0.75;
	if($Perf >0 and $Blindage_eni >0)
	{
		if($Distance <101)
		{
			if($Calibre >74)
				$Perf/=0.9;
			elseif($Calibre >49)
				$Perf/=0.85;
			elseif($Calibre >29)
				$Perf/=0.8;
			else
				$Perf/=0.75;
		}
		elseif($Distance >$PorteeMax)
			$Perf=0;
		elseif($Distance >$Portee)
			$Perf/=1.5;
		elseif($Distance >1000)
		{
			if($Calibre >74)
				$Perf/=1.25;
			elseif($Calibre >29)
				$Perf/=1.5;
			else
				$Perf=0;
		}
		elseif($Distance >500)
		{
			if($Calibre >74)
				$Perf/=1.1;
			elseif($Calibre >49)
				$Perf/=1.2;
			elseif($Calibre >29)
				$Perf/=1.25;
			else
				$Perf/=1.5;
		}		
	}
	elseif(!$Blindage_eni and $Calibre >19 and $Calibre <75)
		$Degats/=1.5;	
	if($Mun ==7 and $Calibre >19) //APDS
		$Perf*=1.5;
	elseif($Mun ==6 and $Distance <=1000) //APCR
		$Perf*=1.5;
	if($Mun ==1)//APC
	{
		if($Blindage_eni >0 and $Perf >=$Blindage_eni and $Distance <=500)
			$Degats*=2;
		else
			$Degats*=0.9;
	}
	elseif($Mun ==2)//HE
	{
		if($Blindage_eni >0)
			$Degats/=2;
		else
			$Degats*=2;
	}
	elseif($Mun ==4)//APHE
	{
		if($Blindage_eni >0)
		{
			if($Perf >=$Blindage_eni)
				$Degats*=2;
			else
				$Degats=mt_rand(1,10);
		}
		else
			$Degats*=0.9;
	}		
	elseif($Mun ==6)//APCR
	{
		if($Blindage_eni >0 and $Perf >=$Blindage_eni and $Distance <=1000)
			$Degats*=2;
		else
			$Degats*=0.9;
	}
	elseif($Mun ==7)//APDS
	{
		if($Blindage_eni >0 and $Calibre >19 and $Perf >=$Blindage_eni)
			$Degats*=2;
		else
			$Degats*=0.5;
	}
	elseif($Mun ==8)//HEAT
	{
		if($Calibre >69)
		{
			if($Distance <=500)
				$Perf*=2;
			if($Blindage_eni >0)
			{
				if($Perf >=$Blindage_eni)
				{
					if($Distance <=500)
						$Degats*=2;
					else
						$Degats*=0.9;
				}
				else
					$Degats*=0.5;
			}
		}
		else
			$Degats=0;
	}
	elseif($Perf <$Blindage_eni)
		$Degats=0;
	if($Degats <1)$Degats=0;
	return $Degats;
}

function Get_LandSpeed($g_Vitesse,$g_mobile,$Zone,$Position=0,$Type=0,$hp_good=0,$Sol_meuble=0,$Amphi=0,$Front=0,$Encyclo=false)
{
	if($Position ==2 or $Position ==3 or $Position ==5 or $Position ==8 or $Position ==9 or $Position ==10 or $Position ==11 or $Position ==26)
		$g_Vitesse=0;
	elseif($g_mobile ==5)
		$g_Vitesse*=($hp_good/100);
	else
	{
		if($Zone ==1)
		{
			if($g_mobile ==1 or $g_mobile ==3)
				$g_Vitesse*=0.75;
		}
		elseif($Zone ==2)
		{
			if($g_mobile ==2 or $g_mobile ==6)
				$g_Vitesse/=(2-($Sol_meuble/100));
			elseif($g_mobile ==1)
				$g_Vitesse/=(4-($Sol_meuble/100));
		}
		elseif($Zone ==3)
		{
			if($g_mobile ==2 or $g_mobile ==6)
				$g_Vitesse/=(2-($Sol_meuble/100));
			elseif($g_mobile ==3 or $g_mobile ==7)
				$g_Vitesse*=0.75;
			elseif($g_mobile ==1)
				$g_Vitesse/=(4-($Sol_meuble/100));
		}
		elseif($Zone ==4 or $Zone ==5)
		{
			if($g_mobile ==3 or $g_mobile ==7)
			{
				if($Type !=97)
					$g_Vitesse/=2;
			}
			else
				$g_Vitesse/=4;
		}
		elseif($Zone ==7)
		{
			if($g_mobile ==1 or $g_mobile ==2 or $g_mobile ==6)
				$g_Vitesse/=2;
		}
		elseif($Zone ==8)
		{
			if($g_mobile ==2 or $g_mobile ==6 or $g_mobile ==7)
				$g_Vitesse/=(2-($Sol_meuble/100));
			elseif($g_mobile ==1 or $g_mobile ==3)
				$g_Vitesse/=(4-($Sol_meuble/100));
		}
		elseif($Zone ==9 or $Zone ==11)
		{
			if($Amphi and $Zone ==11)
				$Type ==90;
			if($Type ==90)
				$g_Vitesse/=(2-($Sol_meuble/100));
			else
				$g_Vitesse/=(4-($Sol_meuble/100));
		}
		elseif($Zone ==10)
		{
			if($g_mobile ==3)
				$g_Vitesse/=(2-($Sol_meuble/100));
			else
				$g_Vitesse/=(4-($Sol_meuble/100));
		}
		if($Position ==1 or $Position ==14)
			$g_Vitesse/=2;
		if($Encyclo){
            if($Front ==3 and $g_Vitesse <75)
                $g_Vitesse=75;
            elseif(($Front ==1 or $Front ==2 or $Front ==4 or $Front ==5) and $g_Vitesse <50)
                $g_Vitesse=50;
            elseif($g_Vitesse <25)
                $g_Vitesse=25;
		}
	}
	return $g_Vitesse;
}

function Get_LandConso($Zone,$Conso)
{
	if($Zone ==1)
		$Conso_l=2;
	elseif($Zone ==2 or $Zone ==3)
		$Conso_l=3;
	elseif($Zone ==4 or $Zone ==5 or $Zone ==9 or $Zone ==10 or $Zone ==11)
		$Conso_l=5;
	elseif($Zone ==8)
		$Conso_l=2;
	else
		$Conso_l=1;
	$Conso_m=ceil($Conso*$Conso_l);
	return $Conso_m;
}

function Get_Blindage($Zone, $Cam, $Fort=0, $Position=0)
{		
	$Blindage=0;
	if(!$Fort)
	{
		if($Zone ==2 or $Zone ==3)
		{
			if($Cam <3)
				$Blindage=4;
			elseif($Cam <2)
				$Blindage=8;
		}
		elseif($Zone ==4 or $Zone ==5 or $Zone ==9 or $Zone ==10)
		{
			if($Cam <3)
				$Blindage=4;
			elseif($Cam <2)
				$Blindage=8;
		}
		elseif($Zone ==7)
		{
			if($Cam <5)
				$Blindage=4;
			elseif($Cam <3)
				$Blindage=8;
			elseif($Cam <2)
				$Blindage=13;
		}
	}
	else
	{
		if($Position ==2)
			$Blindage+=$Fort;
		elseif($Position ==3 or $Position ==10)
			$Blindage+=($Fort/2);
	}
	return $Blindage;
}

/**
 * @param $Categorie
 * @param $Type_Veh
 * @param $mobile
 * @param $Arme_AT
 * @return array
 */
function Get_Matos_List($Categorie,$Type_Veh,$mobile,$Arme_AT){
    $list_matos=[3,11];
    if($Categorie ==1)
        array_push($list_matos,10,15,24,30);
    elseif($Categorie ==2)
        array_push($list_matos,1,2,6,9,10,13,15,25,27,30);
    elseif($Categorie ==3)
        array_push($list_matos,1,2,6,7,8,9,10,12,13,15,16,17,25,27,30);
    elseif($Categorie ==5 and $mobile ==3)
        $list_matos[]=28;
    elseif($Categorie ==8)
        array_push($list_matos,1,2,6,8,9,12,27);
    elseif($Categorie ==9)
        array_push($list_matos,1,2,6,7,8,9,14,24,25,27);
    elseif($Categorie ==17)
        array_push($list_matos,9,12,13,18,22,23,26,27);
    elseif($Categorie ==21)
        array_push($list_matos,1,2,9,12,13,22,26,27);
    elseif($Categorie ==22)
        array_push($list_matos,1,2,9,12,13,19,20,21,22,23,26,27);
    elseif($Categorie ==20 or $Categorie ==23 or $Categorie ==24)
        array_push($list_matos,1,2,9,12,13,22,26,27);
    if($Type_Veh ==3)
        $list_matos[]=24;
    elseif($Type_Veh ==6)
        array_push($list_matos,14,24);
    elseif($Type_Veh ==8)
        array_push($list_matos,10,13,15,30);
    elseif($Type_Veh ==11)
        array_push($list_matos,1,2,9,10,12,13,15,25,27,30);
    elseif($Type_Veh ==12){
        array_push($list_matos,1,2,9,12,25,27);
        if($Arme_AT)
            array_push($list_matos,6,7);
    }
    return $list_matos;
}

function GetPosGr($Pos)
{
	switch($Pos)
	{
		case 0:
			$Pos_txt='En position';
		break;
		case 1:
			$Pos_txt='En position défensive';
		break;
		case 2:
			$Pos_txt='Retranchée';
		break;
		case 3:
			$Pos_txt='En embuscade';
		break;
		case 4:
			$Pos_txt='En mouvement';
		break;
		case 5:
			$Pos_txt='En appui';
		break;
		case 6:
			$Pos_txt='En déroute';
		break;
		case 7:
			$Pos_txt='Encerclée';
		break;
		case 8:
			$Pos_txt='Sous le feu';
		break;
		case 9:
			$Pos_txt='Cloué au sol';
		break;
		case 10:
			$Pos_txt='En ligne';
		break;
		case 11:
			$Pos_txt='En transit';
		break;
		case 12:
			$Pos_txt='En transit';
		break;
        case 14:
            $Pos_txt='Sentinelle';
        break;
		case 20:
			$Pos_txt='Dispersé';
		break;
		case 21:
			$Pos_txt='En ligne';
		break;
		case 22:
			$Pos_txt='Evasion';
		break;
		case 23:
			$Pos_txt='En appui';
		break;
		case 24:
			$Pos_txt='ASM';
		break;
		case 25:
			$Pos_txt='En plongée';
		break;
		case 26:
			$Pos_txt="A l'ancre";
		break;
		case 27:
			$Pos_txt='Interdiction';
		break;
		case 28:
			$Pos_txt='Attaque de convoi';
		break;
		case 29:
			$Pos_txt='Bombardement côtier';
		break;
		case 30:
			$Pos_txt='Engagement';
		break;
		case 32:
			$Pos_txt='En attente de transit';
		break;
		case 33:
			$Pos_txt='Assaut';
		break;
		case 34:
			$Pos_txt='En cale sèche';
		break;
		case 37:
			$Pos_txt='Fumigène';
		break;
		case 40:
			$Pos_txt='Attaque à la torpille';
		break;
		default:
			$Pos_txt='Inconnu';
		break;
	}
	return $Pos_txt;
}

function GetPlace($Placement, $Naval=0)
{
	if($Naval)
	{
		if($Placement ==4)
			$Place='au port';
		elseif($Placement ==8)
			$Place='au large';
		else
			$Place='en mer';
	}
	else
	{
		switch($Placement)
		{
			case 1:
				$Place='dans les environs de l aérodrome';
			break;
			case 2:
				$Place='le long de la route';
			break;
			case 3:
				$Place='dans les environs de la gare';
			break;
			case 4:
				$Place='dans les environs du port';
			break;
			case 5:
				$Place='dans les environs du fleuve';
			break;
			case 6:
				$Place='dans les environs de la zone industrielle';
			break;
			case 7:
				$Place='dans les environs du radar';
			break;
			case 8:
				$Place='au large';
			break;
			case 9:
				$Place='dans les navires de transport';
			break;
			case 11:
				$Place='dans les environs de la plage';
			break;
			default:
				$Place='dans les environs de la caserne';
			break;
		}
	}
	return $Place;
}

function GetQuota($Pays,$Front,$Date_Campagne,$Cat)
{
	if($Pays ==1)
	{
		if($Cat ==15)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=125;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1940-12-01')
				$Quota=50; //75
			else
				$Quota=25; //50
		}
		elseif($Cat ==9)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1940-12-01')
				$Quota=30; //50
			else
				$Quota=15; //25
		}
		elseif($Cat ==8)
		{
			if($Date_Campagne >'1940-12-01')
				$Quota=50; //100
			else
				$Quota=25; //50
		}
		elseif($Cat ==6)
			$Quota=20; //50
		elseif($Cat ==5)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=250;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=225;
			elseif($Date_Campagne >'1940-12-01')
				$Quota=100; //200
			else
				$Quota=50; //150
		}
		elseif($Cat ==3)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=200;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1940-12-01')
				$Quota=50; //100
			else
				$Quota=25; //50
		}
		elseif($Cat ==2)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1940-12-01')
				$Quota=75;
			else
				$Quota=30; //50
		}
		elseif($Cat ==17)
		{
			if($Date_Campagne >'1940-12-01')
				$Quota=50; //100
			else
				$Quota=25; //50
		}
		elseif($Cat ==22)
			$Quota=20;
		elseif($Cat ==23)
			$Quota=10;
		elseif($Cat ==24)
			$Quota=5;
		elseif($Cat ==20)
			$Quota=3;
		elseif($Cat ==100)
            $Quota=5;
		elseif($Cat ==1)
        {
            if($Date_Campagne >'1940-12-01')
                $Quota=10;
            else
                $Quota=5;
        }
	}
	elseif($Pays ==2)
	{
		if($Cat ==15)
		{
			if($Date_Campagne >'1942-01-01')
				$Quota=50;
			else
				$Quota=10; //25
		}
		elseif($Cat ==9)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=50;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=25;
			else
				$Quota=10;
		}
		elseif($Cat ==8)
		{
			if($Date_Campagne >'1942-01-01')
				$Quota=50;
			else
				$Quota=20; //25
		}
		elseif($Cat ==6)
			$Quota=20; //25
		elseif($Cat ==5)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=50;
			elseif($Date_Campagne >'1940-12-01')
				$Quota=30;
			else
				$Quota=25;
		}
		elseif($Cat ==3)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=50;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=25;
			else
				$Quota=10;
		}
		elseif($Cat ==2)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=50;
			else
				$Quota=20; //25
		}
		elseif($Cat ==17)
			$Quota=25;
		elseif($Cat ==20)
			$Quota=25;
		elseif($Cat ==21)
			$Quota=15;
		elseif($Cat ==23)
			$Quota=40;
		elseif($Cat ==24)
			$Quota=25;
		elseif($Cat ==22)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=75;
			else
				$Quota=50;
		}
		elseif($Cat ==100)
			$Quota=50;
		elseif($Cat ==1)
			$Quota=5;
	}
	elseif($Pays ==4)
	{
		if($Cat ==15)
			$Quota=10; //25
		elseif($Cat ==9)
			$Quota=10; //25
		elseif($Cat ==8)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=75;
			else
				$Quota=25; //50
		}
		elseif($Cat ==6)
			$Quota=20; //50
		elseif($Cat ==5)
			$Quota=50; //100
		elseif($Cat ==3)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=50;
			else
				$Quota=10; //25
		}
		elseif($Cat ==2)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=75;
			else
				$Quota=20; //50
		}
		elseif($Cat ==17)
			$Quota=10;
		elseif($Cat ==20)
			$Quota=10;
		elseif($Cat ==22)
			$Quota=25;
		elseif($Cat ==23)
			$Quota=15;
		elseif($Cat ==24)
			$Quota=10;
		elseif($Cat ==100)
			$Quota=20;
		elseif($Cat ==1)
			$Quota=5;
	}
	elseif($Pays ==3)
	{
		if($Cat ==15)
			$Quota=5;
		elseif($Cat ==9)
			$Quota=5;
		elseif($Cat ==8)
			$Quota=10;
		elseif($Cat ==6)
			$Quota=10;
		elseif($Cat ==5)
			$Quota=30;
		elseif($Cat ==3)
			$Quota=2;
		elseif($Cat ==2)
			$Quota=5;
	}
	elseif($Pays ==5)
	{
		if($Cat ==15)
			$Quota=5;
		elseif($Cat ==9)
			$Quota=5;
		elseif($Cat ==8)
			$Quota=10;
		elseif($Cat ==6)
			$Quota=10;
		elseif($Cat ==5)
			$Quota=25;
		elseif($Cat ==2)
			$Quota=1;
		elseif($Cat ==17)
			$Quota=5;
		elseif($Cat ==22)
			$Quota=10;
		elseif($Cat ==23)
			$Quota=3;
		elseif($Cat ==100)
			$Quota=10;
	}
	elseif($Pays ==6)
	{
		if($Cat ==15)
			$Quota=15; //25
		elseif($Cat ==9)
			$Quota=15; //25
		elseif($Cat ==8)
			$Quota=20; //25
		elseif($Cat ==6)
			$Quota=20; //50
		elseif($Cat ==5)
			$Quota=30; //50
		elseif($Cat ==3)
			$Quota=10;
		elseif($Cat ==2)
			$Quota=20; //25
		elseif($Cat ==17)
			$Quota=10;
		elseif($Cat ==20)
			$Quota=10;
		elseif($Cat ==22)
			$Quota=25;
		elseif($Cat ==23)
			$Quota=10;
		elseif($Cat ==24)
			$Quota=10;
		elseif($Cat ==100)
			$Quota=20;
		elseif($Cat ==1)
			$Quota=5;
	}
	elseif($Pays ==8)
	{
		if($Cat ==15)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=50;
			else
				$Quota=15; //25
		}
		elseif($Cat ==9)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=50;
			else
				$Quota=15; //25
		}
		elseif($Cat ==8)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=125;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=100;
			else
				$Quota=25; //50
		}
		elseif($Cat ==6)
			$Quota=30;
		elseif($Cat ==5)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=400;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=300;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=200;
			else
				$Quota=60; //100
		}
		elseif($Cat ==3)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=200;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=100;
			else
				$Quota=25; //50
		}
		elseif($Cat ==2)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=200;
			elseif($Date_Campagne >'1944-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=100;
			else
				$Quota=30; //50
		}
		elseif($Cat ==22)
			$Quota=10;
		elseif($Cat ==100)
			$Quota=10;
        elseif($Cat ==1)
        {
            if($Date_Campagne >'1942-01-01')
                $Quota=10;
            else
                $Quota=5;
        }
	}
	elseif($Pays ==7)
	{			
		if($Cat ==15)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=50;
			else
				$Quota=15; //25
		}
		elseif($Cat ==9)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=50;
			else
				$Quota=10; //25
		}
		elseif($Cat ==8)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=50;
			else
				$Quota=15; //25
		}
		elseif($Cat ==6)
		{
			if($Date_Campagne >'1942-01-01')
				$Quota=50;
			else
				$Quota=20; //25
		}
		elseif($Cat ==5)
		{
			if($Date_Campagne >'1945-01-01')
				$Quota=200;
			elseif($Date_Campagne >'1944-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=25; //50
			else
				$Quota=25;
		}
		elseif($Cat ==3)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=50;
			elseif($Date_Campagne >'1941-01-01')
				$Quota=10;  //25
			else
				$Quota=10;
		}
		elseif($Cat ==2)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=75;
			elseif($Date_Campagne >'1941-01-01')
				$Quota=20;  //50
			else
				$Quota=20; //25
		}
		elseif($Cat ==17)
			$Quota=25;
		elseif($Cat ==20)
			$Quota=25;
		elseif($Cat ==21)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=25;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=20;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=10;
			else
				$Quota=5;
		}
		elseif($Cat ==22)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=150;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=100;
			elseif($Date_Campagne >'1942-01-01')
				$Quota=75;
			else
				$Quota=50;
		}
		elseif($Cat ==23)
		{
			if($Date_Campagne >'1944-01-01')
				$Quota=50;
			elseif($Date_Campagne >'1943-01-01')
				$Quota=40;
			else
				$Quota=30;
		}
		elseif($Cat ==24)
		{
			if($Date_Campagne >'1943-01-01')
				$Quota=30;
			else
				$Quota=20;
		}
		elseif($Cat ==100)
			$Quota=50;
	}
	elseif($Pays ==9)
	{
		if($Cat ==15)
			$Quota=20;
		elseif($Cat ==9)
			$Quota=10;
		elseif($Cat ==8)
			$Quota=25;
		elseif($Cat ==6)
			$Quota=20; //25
		elseif($Cat ==5)
			$Quota=50;
		elseif($Cat ==3)
			$Quota=15;
		elseif($Cat ==2)
			$Quota=25;
		elseif($Cat ==17)
			$Quota=25;
		elseif($Cat ==20)
			$Quota=10;
		elseif($Cat ==21)
			$Quota=10;
		elseif($Cat ==22)
			$Quota=30;
		elseif($Cat ==23)
			$Quota=20;
		elseif($Cat ==24)
			$Quota=15;
		elseif($Cat ==100)
			$Quota=30;
	}
    elseif($Pays ==10)
    {
        if($Cat ==8)
            $Quota=5;
        elseif($Cat ==6)
            $Quota=5;
        elseif($Cat ==5)
            $Quota=15;
        elseif($Cat ==15)
            $Quota=2;
        elseif($Cat ==2)
            $Quota=1;
        elseif($Cat ==20)
            $Quota=1;
        elseif($Cat ==22)
            $Quota=5;
        elseif($Cat ==24)
            $Quota=1;
    }
    elseif($Pays ==15)
    {
        if($Cat ==8)
            $Quota=10;
        elseif($Cat ==9)
            $Quota=5;
        elseif($Cat ==6)
            $Quota=10;
        elseif($Cat ==5)
            $Quota=20;
        elseif($Cat ==15)
            $Quota=5;
        elseif($Cat ==2)
            $Quota=2;
    }
    elseif($Pays ==17)
    {
        if($Cat ==8)
            $Quota=10;
        elseif($Cat ==9)
            $Quota=5;
        elseif($Cat ==6)
            $Quota=5;
        elseif($Cat ==5)
            $Quota=20;
        elseif($Cat ==15)
            $Quota=5;
        elseif($Cat ==2)
            $Quota=2;
    }
	elseif($Pays ==18)
	{
		if($Cat ==15)
			$Quota=10;
		elseif($Cat ==9)
		{
			if($Date_Campagne >'1942-01-01')
				$Quota=10;
			else
				$Quota=5;
		}
		elseif($Cat ==8)
			$Quota=10;
		elseif($Cat ==6)
			$Quota=10;
		elseif($Cat ==5)
			$Quota=25;
		elseif($Cat ==3)
			$Quota=5;
		elseif($Cat ==2)
			$Quota=10;
		elseif($Cat ==17)
			$Quota=3;
		elseif($Cat ==22)
			$Quota=5;
	}
	elseif($Pays ==19)
	{
		if($Cat ==15)
		{
			if($Date_Campagne >'1942-01-01')
				$Quota=10;
			else
				$Quota=5;
		}
		elseif($Cat ==9)
		{
			if($Date_Campagne >'1942-01-01')
				$Quota=10;
			else
				$Quota=5;
		}
		elseif($Cat ==8)
			$Quota=15;
		elseif($Cat ==6)
			$Quota=10;
		elseif($Cat ==5)
			$Quota=25;
		elseif($Cat ==3)
			$Quota=10;
		elseif($Cat ==2)
			$Quota=10;
	}
    elseif($Pays ==20)
    {
        if($Cat ==8)
            $Quota=10;
        elseif($Cat ==9){
            if($Date_Campagne >'1943-01-01')
                $Quota=10;
            else
                $Quota=5;
        }
        elseif($Cat ==6)
            $Quota=10;
        elseif($Cat ==5)
            $Quota=25;
        elseif($Cat ==15){
            if($Date_Campagne >'1943-01-01')
                $Quota=10;
            else
                $Quota=5;
        }
        elseif($Cat ==2)
            $Quota=2;
        elseif($Cat ==1)
            $Quota=2;
        elseif($Cat ==3 and $Date_Campagne >'1943-01-01')
            $Quota=1;
        elseif($Cat ==24)
            $Quota=2;
        elseif($Cat ==100)
            $Quota=5;
    }
	elseif($Pays ==35)
	{
		if($Cat ==8)
			$Quota=5;
		elseif($Cat ==6)
			$Quota=5;
		elseif($Cat ==5)
			$Quota=10;
		elseif($Cat ==17)
			$Quota=5;
		elseif($Cat ==22)
			$Quota=5;
		elseif($Cat ==100)
			$Quota=5;
	}
	return $Quota;
}

function AddGroundAtk($Rega,$Regb,$Veha,$Veh_Nbra,$Vehb,$Veh_Nbrb,$Posa,$Posb,$Lieu,$Place,$Distance=0,$Kills=0,$Reg_a_ia=0,$Reg_b_ia=0)
{
	if($Rega)
	{
		$date=date('Y-m-d G:i');
		$query="INSERT INTO Ground_Cbt (Date, Reg_a, Veh_a, Veh_Nbr_a, Pos_a, Reg_b, Veh_b, Veh_Nbr_b, Pos_b, Lieu, Place, Distance, Kills, Reg_a_ia, Reg_b_ia)
		VALUES ('$date','$Rega','$Veha','$Veh_Nbra','$Posa','$Regb','$Vehb','$Veh_Nbrb','$Posb','$Lieu','$Place','$Distance','$Kills','$Reg_a_ia','$Reg_b_ia')";
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		if(!$ok)
		{
			$msg='Erreur de mise à jour '.mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddGroundAtk Error',$msg);
		}
		mysqli_close($con);
	}
}