<?php
function RetireCandidatPVP($PlayerID, $Page="")
{
	$con=dbconnecti();
	$ok=mysqli_query($con,"DELETE FROM Duels_Candidats_PVP WHERE PlayerID='$PlayerID' LIMIT 1");
	mysqli_close($con);
	SetData("Duels_Candidats_PVP","Target",0,"Target",$PlayerID);
}
function GetFactionDB($Faction)
{
	if($Faction ==1)
		return "Axe";
	elseif($Faction ==2)
		return "Allies";
}
function GetFlagPVP($Battle,$Faction)
{
	if($Battle ==1)//Maastricht
	{
		if($Faction ==1)
			$Flag=5;
		elseif($Faction ==2)
			$Flag=1;
	}
	elseif($Battle ==2)//Hannut
	{
		if($Faction ==1)
			$Flag=3;
		elseif($Faction ==2)
			$Flag=1;
	}
	elseif($Battle ==93)
	{
		if($Faction ==1)
			$Flag=2;
		elseif($Faction ==2)
			$Flag=1;
	}
	elseif($Battle ==94)
	{
		if($Faction ==1)
			$Flag=8;
		elseif($Faction ==2)
			$Flag=1;
	}
	return $Flag;
}
function GetCiblePVP($Battle)
{
	if($Battle ==1)
		$Cible=109;
	elseif($Battle ==2)
		$Cible=144;
	elseif($Battle ==92)
		$Cible=25;
	elseif($Battle ==93)
		$Cible=224;
	elseif($Battle ==94 or $Battle ==95 or $Battle ==96 or $Battle ==100)
		$Cible=343;
	elseif($Battle ==97 or $Battle ==99)
		$Cible=614;
	elseif($Battle ==98)
		$Cible=1366;
	elseif($Battle ==101)
		$Cible=1565;
	elseif($Battle ==102)
		$Cible=618;
    elseif($Battle ==103)
        $Cible=1599;
    elseif($Battle ==104)
        $Cible=1420;
    elseif($Battle ==105)
        $Cible=629;
    elseif($Battle ==106)
        $Cible=621;
    elseif($Battle ==107)
        $Cible=866;
    elseif($Battle ==108)
        $Cible=4;
    elseif($Battle ==109)
        $Cible=226;
    elseif($Battle ==110)
        $Cible=211;
    elseif($Battle ==111)
        $Cible=80;
    elseif($Battle ==112)
        $Cible=1756;
    elseif($Battle ==113)
        $Cible=401;
	return $Cible;
}
function GetFrontPVP($Battle)
{
	if($Battle ==1 or $Battle ==2 or $Battle ==92 or $Battle ==93)
		$Front=0;
	elseif($Battle ==94 or $Battle ==95 or $Battle ==96 or $Battle ==100)
		$Front=2;
	elseif($Battle ==98 or $Battle ==101)
		$Front=3;
	elseif($Battle ==97 or $Battle ==99 or $Battle ==102)
		$Front=1;
	return $Front;
}
function AddGroundAtkPVP($Rega,$Regb,$Veha,$Veh_Nbra,$Vehb,$Veh_Nbrb,$Posa,$Posb,$Lieu,$Place,$Off_a,$Off_b,$Distance=0,$Kills=0)
{
	if($Rega and $Regb)
	{
		$date=date('Y-m-d G:i');
		$query="INSERT INTO Ground_Cbt_PVP (Date, Reg_a, Veh_a, Veh_Nbr_a, Pos_a, Reg_b, Veh_b, Veh_Nbr_b, Pos_b, Lieu, Place, Distance, Kills, Off_a, Off_b)
		VALUES ('$date','$Rega','$Veha','$Veh_Nbra','$Posa','$Regb','$Vehb','$Veh_Nbrb','$Posb','$Lieu','$Place','$Distance','$Kills','$Off_a','$Off_b')";
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg.="Erreur de mise à jour ".mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddGroundAtkPVP Error',$msg);
		}
	}
	else
		mail('binote@hotmail.com','Aube des Aigles: AddGroundAtkPVP Error No Reg',$Rega.' -- '.$Regb);
}
function AddAirCbtPVP($Pilote_a,$Avion_a,$Pilote_b,$Avion_b,$Lieu,$Alt,$Distance)
{
	if($Pilote_a and $Pilote_b)
	{
		$date=date('Y-m-d G:i');
		$query="INSERT INTO Air_Cbt_PVP (Date, Pilote_a, Avion_a, Pilote_b, Avion_b, Lieu, Alt, Distance)
		VALUES ('$date','$Pilote_a','$Avion_a','$Pilote_b','$Avion_b','$Lieu','$Alt','$Distance')";
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg.="Erreur de mise à jour ".mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddAirCbtPVP Error',$msg);
		}
	}
}
function AddDCACbtPVP($Lieu,$Pilote,$Avion,$Alt,$Cycle,$Veh,$Arme,$Degats)
{
	if($Pilote_a and $Pilote_b)
	{
		$date=date('Y-m-d G:i');
		$query="INSERT INTO DCA_Cbt_PVP (Date, Lieu, Pilote, Avion, Alt, Cycle, Veh, Arme, Degats)
		VALUES ('$date','$Lieu','$Pilote','$Avion','$Alt','$Cycle','$Veh','$Arme','$Degats')";
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg.="Erreur de mise à jour ".mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddDCACbtPVP Error',$msg);
		}
	}
}
function AddCandidatPVP($Avion_db, $PlayerID, $avion, $HP, $Puissance, $Essence, $chemin, $Distance, $Mun1, $Mun2, $alt, $Cible, $Nuit)
{
	$chemin=round($chemin);
	$Puissance=round($Puissance);
	/*if($Avion_db =="Avions_Persos" or $Avion_db =="Avions_Sandbox")
		$avion=GetData($Avion_db,"ID",$avion,"ID_ref");*/
	$date=date('Y-m-d G:i');
	if(GetData("Duels_Candidats_PVP","PlayerID",$PlayerID,"ID"))
	{
		$query="UPDATE Duels_Candidats_PVP SET Avion='$avion',Lieu='$Cible',Date='$date',Altitude='$alt',HP='$HP',Essence='$Essence',chemin='$chemin',Distance='$Distance',Cycle='$Nuit' WHERE PlayerID='$PlayerID'";
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg.='Erreur de mise à jour'.mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddCandidatPVP Update Error',$msg);
		}
	}
	else
	{
		$Pays=GetData("Avion","ID",$avion,"Pays");
		$query="INSERT INTO Duels_Candidats_PVP (PlayerID, Date, Lieu, HP, Altitude, Essence, Country, Avion, Mun1, Mun2, Puissance, chemin, Distance)
		VALUES ('$PlayerID','$date','$Cible','$HP','$alt','$Essence','$Pays','$avion','$Mun1','$Mun2','$Puissance','$chemin','$Distance')";
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg.='Erreur insert '.mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddCandidatPVP Insert Error',$msg);
		}
	}
	//SetData("Pilote_PVP","PvP",$Cible,"ID",$PlayerID);
}
function AddEventPVP($Event,$Reg,$Reg_eni,$Degats,$Battle)
{
	if($Event and $Reg)
	{
		//$date=date('Y-m-d G:i');
		$query="INSERT INTO Events_Battle (Event, Reg, Reg_eni, Degats, Battle)
		VALUES ('$Event','$Reg','$Reg_eni','$Degats','$Battle')";
		$con=dbconnecti(5);
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg.="Erreur de mise à jour ".mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: AddEventPVP Error',$msg);
		}
	}
}
function GetVehPVP($Battle)
{
	if($Battle ==1)
		return array(691,692,700,693,694,695,696,698,697,615,618,678,675,676,103,153,108,129,133,235,242,131,198,128,130,298);
	elseif($Battle ==2)
		return array(691,692,700,703,702,693,26,701,62,37,696,105,143,87,147,362,27,174,146,145,61,38,169,41,103,153,108,129,133,235,359,242,119,22,215,122,23,29,30);
}
function GetAvionPVP($Battle,$Mission,$Faction,$Premium)
{
	if($Battle ==1)//Maastricht
	{
		if($Mission ==3 or $Mission ==4 or $Mission ==7 or $Mission ==17)//Chasse
		{
			if($Faction ==1)
			{
				if($Premium)
					return array(1,2,17,27);
				else
					return array(1,17);
			}
			elseif($Faction ==2)
			{
				if($Premium)
					return array(4,25,5,6,8,10,566,565,568,42,43,146,147);
				else
					return array(4,5,8,10,566,568,42,43,146);
			}
		}
		elseif($Mission ==99)//Ecran battle
		{
			if($Premium)
				return array(40,38,41,42,567,566,568,565,148,149,147,146,18,19,4,25,35,23,32,34,20,22,43,5,6,8,10,131,47,12,31,13,29,15,16,14,56,28,17,27,1,2);
			else
				return array(40,38,41,42,567,566,568,565,148,149,147,146,18,4,35,32,34,43,5,8,10,131,47,12,31,13,29,14,56,17,1);
		}
		elseif($Mission ==98)//Choix
		{
			if($Faction ==2)
			{
				if($Premium)
					return array(40,38,41,42,567,566,568,565,148,149,147,146,18,19,4,25,35,23,32,34,20,22,43,5,6,8,10);
				else
					return array(40,38,41,42,567,566,568,565,148,149,147,146,18,4,35,32,34,43,5,8,10);
			}
			elseif($Faction ==1)
			{
				if($Premium)
					return array(131,47,12,31,13,29,15,16,14,56,28,17,27,1,2);
				else
					return array(131,47,12,31,13,29,14,56,17,1);
			}
		}
	}
	elseif($Battle ==2)//Hannut
	{
		if($Mission ==3 or $Mission ==4 or $Mission ==7 or $Mission ==17)//Chasse
		{
			if($Faction ==1)
			{
				if($Premium)
					return array(1,2,17,27);
				else
					return array(1,17);
			}
			elseif($Faction ==2)
			{
				if($Premium)
					return array(4,25,5,6,8,10,568,42,43);
				else
					return array(4,5,8,10,568,42,43);
			}
		}
		elseif($Mission ==99)//Ecran battle
		{
			if($Premium)
				return array(41,568,18,19,4,25,54,35,23,32,43,5,6,8,10,131,47,12,31,13,16,14,56,28,17,27,1,2);
			else
				return array(41,568,18,4,54,35,32,43,5,8,10,131,47,12,31,13,29,14,56,17,1);
		}
		elseif($Mission ==98)//Choix
		{
			if($Faction ==2)
			{
				if($Premium)
					return array(41,568,18,19,4,25,54,35,23,32,43,5,6,8,10);
				else
					return array(41,568,18,4,54,35,32,43,5,8,10);
			}
			elseif($Faction ==1)
			{
				if($Premium)
					return array(131,47,12,31,13,16,14,56,28,17,27,1,2);
				else
					return array(131,47,12,31,13,29,14,56,17,1);
			}
		}
	}
	elseif($Battle ==92)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(9,36,7,10,4,25);
			else
				return array(9,36,10,4,25);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(1,3,17,27);
			else
				return array(1,17,27);
		}
	}	
	elseif($Battle ==93)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(4,25,57,71);
			else
				return array(4,25,57,71);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(1,3,60,17,27);
			else
				return array(1,3,17,27);
		}
	}
	elseif($Battle ==94)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(11,26,4,25);
			else
				return array(11,4);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(112,39,63,79);
			else
				return array(112,39,63,79);
		}
	}
	elseif($Battle ==95)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(123,26,4,25);
			else
				return array(123,4,25);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(39,63,79,3,60,27,228);
			else
				return array(39,63,79,3,27,228);
		}
	}
	elseif($Battle ==96)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(153,251,109,69,70,241);
			else
				return array(153,109,69);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(60,117,27,228);
			else
				return array(60,27,228);
		}
	}
	elseif($Battle ==97)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(226,281,214,202,203,207,215);
			else
				return array(226,214,202,203,207,215);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(117,137,27);
			else
				return array(117,27);
		}
	}
	elseif($Battle ==98)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(308,304,305);
			else
				return array(308,304);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(328,309);
			else
				return array(328,309);
		}
	}
	elseif($Battle ==99)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(302,554,281,282,214,202,207,215);
			else
				return array(281,214,202,207,215);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(137,138,27,197,273);
			else
				return array(137,138,27,197,273);
		}
	}
	elseif($Battle ==100)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(189,334,109,69,70,242,390,275);
			else
				return array(189,109,69,70,242,275);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(117,138,27,197,199);
			else
				return array(117,137,27,197,199);
		}
	}
	elseif($Battle ==101)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(315,313,399);
			else
				return array(315,313);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(328,309,378);
			else
				return array(328,309);
		}
	}
	elseif($Battle ==102)
	{
		if($Faction ==2)
		{
			if($Premium)
				return array(414,413,554,422,282,284,214,207,215,294);
			else
				return array(414,413,554,282,214,207,215);
		}
		elseif($Faction ==1)
		{
			if($Premium)
				return array(137,138,271,27,273,130);
			else
				return array(137,138,27,273);
		}
	}
    elseif($Battle ==103)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(414,413,422,283,284,294,216,411);
            else
                return array(414,413,283,284,294,411);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(138,271,272,198,130,312,288);
            else
                return array(138,272,198,130,288);
        }
    }
    elseif($Battle ==104)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(553,226,281,214,202,205,207,215);
            else
                return array(226,214,202,205,207,215);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(291,292,706,708,713,707);
            else
                return array(291,292,706,708,713,707);
        }
    }
    elseif($Battle ==105)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(448,413,481,422,424,284,426,216,420);
            else
                return array(413,422,284,216,420);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(272,441,442,198,443,130,312);
            else
                return array(272,441,198,130,312);
        }
    }
    elseif($Battle ==106)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(448,535,481,482,424,425,427,216,420,512);
            else
                return array(448,481,424,427,216,420);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(441,442,443,492,312,487,488);
            else
                return array(441,442,443,312,487);
        }
    }
    elseif($Battle ==107)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(535,425,661,512,558,647,652);
            else
                return array(535,425,512,558,647);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(442,492,488,489);
            else
                return array(442,492,488,489);
        }
    }
    elseif($Battle ==108)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(542,624,659,632);
            else
                return array(542,624,659,632);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(442,658,489,666);
            else
                return array(442,658,489,666);
        }
    }
    elseif($Battle ==109)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(81,122,25,57);
            else
                return array(81,25,57);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(3,60,181,27,13,15,16,14,30);
            else
                return array(3,60,27,13,15,16,14);
        }
    }
    elseif($Battle ==110)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(495,616,587,510);
            else
                return array(495,587,510);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(500,569,633,662,487,488,442,471);
            else
                return array(500,569,633,487,488,442,471);
        }
    }
    elseif($Battle ==111)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(495,616,587,510);
            else
                return array(495,587,510);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(500,569,633,662,487,488,442,471);
            else
                return array(500,569,633,487,488,442,471);
        }
    }
    elseif($Battle ==112)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(465,509);
            else
                return array(465);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(309,378,363);
            else
                return array(309,378,363);
        }
    }
    elseif($Battle ==113)
    {
        if($Faction ==2)
        {
            if($Premium)
                return array(457,510);
            else
                return array(457);
        }
        elseif($Faction ==1)
        {
            if($Premium)
                return array(471,442,487,488);
            else
                return array(471,442,487,488);
        }
    }
    else
		return false;
}
function GetAvionRenc($Battle,$Faction)
{
    $Avions = GetAvionPVP($Battle,99,$Faction,1);
    if (is_array($Avions)) {
        $key = array_rand($Avions,1);
        return $Avions[$key];
    }
    return false;
}
function GetEscorte($Battle,$Faction)
{
	if($Battle ==1)
	{
		if($Faction ==1)
			$Escorte=array(14,28,56,13,15,16,29,31,44);
		elseif($Faction ==2)
			$Escorte=array(19,76,150,18,19,20,22,32,34,37,149,567);
	}
	$key=array_rand($Escorte);
	return $Escorte[$key];
}
function GetBasePVP($Battle,$Avion,$Faction=0)
{
	if($Battle ==1 or $Battle ==2)//Maastricht
	{
		$Koln=array(13,15,16,29,31,44);
		$GBach=array(1,2,12,14,17,27,28,47,56,131);
		$Tienen=array(38,40,41,42);
		if($Avion ==10)
			$Base=17;
		elseif($Avion ==567)
			$Base=4;
		elseif($Avion ==568)
			$Base=117;
		elseif($Avion ==22)
			$Base=38;
		elseif($Avion ==34)
			$Base=121;
		elseif($Avion ==37)
			$Base=19;
		elseif($Avion ==76)
			$Base=520;
		elseif($Avion ==148)
			$Base=543;
		elseif($Avion ==149)
			$Base=379;
		elseif($Avion ==150)
			$Base=681;
		elseif($Avion ==8 or $Avion ==9)
			$Base=24;
		elseif($Avion ==4 or $Avion ==25)
			$Base=53;
		elseif($Avion ==18 or $Avion ==19)
			$Base=327;
		elseif($Avion ==20 or $Avion ==32)
			$Base=83;
		elseif($Avion ==23 or $Avion ==35)
			$Base=5;
		elseif($Avion ==146 or $Avion ==147)
			$Base=388;
		elseif($Avion ==565 or $Avion ==566)
			$Base=115;
		elseif($Avion ==5 or $Avion ==6 or $Avion ==43)
			$Base=6;
		elseif(in_array($Avion,$Koln))
			$Base=29;
		elseif(in_array($Avion,$GBach))
			$Base=35;
		elseif(in_array($Avion,$Tienen))
			$Base=116;
	}
	elseif($Battle ==92)
	{
		if($Faction ==1)
			$Base=145;
		elseif($Faction ==2)
			$Base=16;
	}
	elseif($Battle ==93)
	{
		if($Faction ==1)
			$Base=249;
		elseif($Faction ==2)
			$Base=226;
	}
	elseif($Battle ==94 or $Battle ==95 or $Battle ==96 or $Battle ==100)
	{
		if($Faction ==1)
			$Base=440;
		elseif($Faction ==2)
			$Base=343;
	}
	elseif($Battle ==97 or $Battle ==99)
	{
		if($Faction ==1)
			$Base=847;
		elseif($Faction ==2)
			$Base=614;
	}
	elseif($Battle ==98)
	{
		if($Faction ==1)
			$Base=2382;
		elseif($Faction ==2)
			$Base=1366;
	}
	elseif($Battle ==101)
	{
		if($Faction ==1)
			$Base=1818;
		elseif($Faction ==2)
			$Base=1565;
	}
	elseif($Battle ==102)
	{
		if($Faction ==1)
			$Base=1599;
		elseif($Faction ==2)
			$Base=2094;
	}
    elseif($Battle ==103)
    {
        if($Faction ==1)
            $Base=1950;
        elseif($Faction ==2)
            $Base=2094;
    }
    elseif($Battle ==104)
    {
        if($Faction ==1)
            $Base=1470;
        elseif($Faction ==2)
            $Base=1472;
    }
    elseif($Battle ==105)
    {
        if($Faction ==1)
            $Base=1591;
        elseif($Faction ==2)
            $Base=1757;
    }
    elseif($Battle ==106)
    {
        if($Faction ==1)
            $Base=1332;
        elseif($Faction ==2)
            $Base=1597;
    }
    elseif($Battle ==107)
    {
        if($Faction ==1)
            $Base=1382;
        elseif($Faction ==2)
            $Base=1070;
    }
    elseif($Battle ==108)
    {
        if($Faction ==1)
            $Base=1116;
        elseif($Faction ==2)
            $Base=118;
    }
    elseif($Battle ==109)
    {
        if($Faction ==1)
            $Base=55;
        elseif($Faction ==2)
            $Base=227;
    }
    elseif($Battle ==110)
    {
        if($Faction ==1)
            $Base=703;
        elseif($Faction ==2)
            $Base=225;
    }
    elseif($Battle ==111)
    {
        if($Faction ==1)
            $Base=2993;
        elseif($Faction ==2)
            $Base=351;
    }
    elseif($Battle ==112)
    {
        if($Faction ==1)
            $Base=1731;
        elseif($Faction ==2)
            $Base=1729;
    }
    elseif($Battle ==113)
    {
        if($Faction ==1)
            $Base=584;
        elseif($Faction ==2)
            $Base=235;
    }
	return $Base;
}
function GetMissionPVP($Battle,$Type,$Faction)
{
    $Mission=3;
    if($Battle ==109)
    {
        if($Faction ==1){
            if($Type ==2 or $Type ==7)
                $Mission=8;
            else
                $Mission=4;
        }
        else{
            $Mission=7;
        }
    }
    elseif($Battle ==113)
    {
        if($Faction ==1){
            if($Type ==2)
                $Mission=23;
            else
                $Mission=4;
        }
        else{
            $Mission=7;
        }
    }
    return $Mission;
}