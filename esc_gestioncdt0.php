<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_avions.inc.php');
	include_once('./jfv_txt.inc.php');
	//$Secteur=Insec($_POST['Mode']);
	$country=$_SESSION['country'];
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0 and $PlayerID >0)
	{
		$con=dbconnecti();	
		$result=mysqli_query($con,"SELECT Unit,Avancement,Front,Credits FROM Pilote WHERE ID='$PlayerID'");
		$resultu=mysqli_query($con,"SELECT Commandant,Officier_Adjoint,Officier_Technique FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Avancement=$data['Avancement'];
				$Front=$data['Front'];
				$Credits=$data['Credits'];
			}
			mysqli_free_result($result);
		}
		if($resultu)
		{
			while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
			{
				$Commandant=$datau['Commandant'];
				$Officier_Adjoint=$datau['Officier_Adjoint'];
				$Officier_Technique=$datau['Officier_Technique'];
			}
			mysqli_free_result($resultu);
		}
		//$Do_Note=GetData("Pilote","ID",$PlayerID,"Do_Note");
		$Grade=GetAvancement($Avancement,$country);		
		if($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint) //or $PlayerID == $Officier_Technique 
		{
			$CT_30=30;
			$CT_30U=30;
			$CT_10=10;
			$CT_5=5;
			$CT_5W=5;
			$CT_5B=5;
			$CT_10B=10;
			$con=dbconnecti();
			$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
			$result=mysqli_query($con,"SELECT Nom,Type,Base,Reputation,Station_Meteo,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,
			Avion1_BombeT,Avion2_BombeT,Avion3_BombeT,Avion1_Bombe,Avion2_Bombe,Avion3_Bombe,Avion1_Bombe_Nbr,Avion2_Bombe_Nbr,Avion3_Bombe_Nbr,
			Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10,Mission_IA,
			Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,
			Bombes_50,Bombes_125,Bombes_250,Bombes_300,Bombes_400,Bombes_500,Bombes_800,Bombes_1000,Bombes_2000
			FROM Unit WHERE ID='$Unite'");
			$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
			mysqli_close($con);
			if($results)
			{
				while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
				{
					$Skills_Pil[]=$data['Skill'];
				}
				mysqli_free_result($results);
			}
			if($result)
			{
				$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
                $Unite_Nom=$data['Nom'];
                $Unit_Type=$data['Type'];
                $Base=$data['Base'];
                $Reputation=$data['Reputation'];
                $Station_Meteo=$data['Station_Meteo'];
                $Avion1=$data['Avion1'];
                $Avion2=$data['Avion2'];
                $Avion3=$data['Avion3'];
                $Avion1_nbr=$data['Avion1_Nbr'];
                $Avion2_nbr=$data['Avion2_Nbr'];
                $Avion3_nbr=$data['Avion3_Nbr'];
                $Avion1_BombeT=$data['Avion1_BombeT'];
                $Avion2_BombeT=$data['Avion2_BombeT'];
                $Avion3_BombeT=$data['Avion3_BombeT'];
                $Avion1_Bombe=$data['Avion1_Bombe'];
                $Avion2_Bombe=$data['Avion2_Bombe'];
                $Avion3_Bombe=$data['Avion3_Bombe'];
                $Avion1_Bombes=$data['Avion1_Bombe_Nbr'];
                $Avion2_Bombes=$data['Avion2_Bombe_Nbr'];
                $Avion3_Bombes=$data['Avion3_Bombe_Nbr'];
                $Pers1=$data['Pers1'];
                $Pers2=$data['Pers2'];
                $Pers3=$data['Pers3'];
                $Pers4=$data['Pers4'];
                $Pers5=$data['Pers5'];
                $Pers6=$data['Pers6'];
                $Pers7=$data['Pers7'];
                $Pers8=$data['Pers8'];
                $Pers9=$data['Pers9'];
                $Pers10=$data['Pers10'];
                $Stock_Essence_87=$data['Stock_Essence_87'];
                $Stock_Essence_100=$data['Stock_Essence_100'];
                $Stock_Essence_1=$data['Stock_Essence_1'];
                $Stock_Munitions_8=$data['Stock_Munitions_8'];
                $Stock_Munitions_13=$data['Stock_Munitions_13'];
                $Stock_Munitions_20=$data['Stock_Munitions_20'];
                $Stock_Munitions_30=$data['Stock_Munitions_30'];
                $Bombes_50=$data['Bombes_50'];
                $Bombes_125=$data['Bombes_125'];
                $Bombes_250=$data['Bombes_250'];
                $Bombes_300=$data['Bombes_300'];
                $Bombes_400=$data['Bombes_400'];
                $Bombes_500=$data['Bombes_500'];
                $Bombes_800=$data['Bombes_800'];
                $Bombes_1000=$data['Bombes_1000'];
                $Bombes_2000=$data['Bombes_2000'];
                $Mission_IA=$data['Mission_IA'];
				mysqli_free_result($result);
				unset($result);
			}
			$Bonus_Pers=1;
			if(is_array($Skills_Pil))
			{
				if(in_array(60,$Skills_Pil))
					$Secretaire=1;
				if(in_array(63,$Skills_Pil))
					$Amis_Industrie=1;
				if(in_array(107,$Skills_Pil))
					$Organisateur2=true;
				if(in_array(108,$Skills_Pil))
					$Organisateur3=true;
				if(in_array(109,$Skills_Pil))
					$Organisateur4=true;
				if(in_array(130,$Skills_Pil))
					$Pers_Sup=1;
				if(in_array(98,$Skills_Pil))
					$Bonus_Pers=1.5;
			}
			$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
			$Personnel=array_count_values($Pers);
			if($Personnel[15] >0 or $Secretaire or $Pers_Sup)
			{
				$CT_30-=$Personnel[15]+$Secretaire+$Pers_Sup;
				$CT_30U-=$Personnel[15]+$Secretaire+$Pers_Sup;
				$CT_10-=$Personnel[15]+$Secretaire+$Pers_Sup;
				$CT_10B-=$Personnel[15]+$Secretaire+$Pers_Sup;
				$CT_5-=floor($Personnel[15]/2)+$Secretaire+$Pers_Sup;
				$CT_5B-=floor($Personnel[15]/2)+$Secretaire+$Pers_Sup;
				$CT_5W-=floor($Personnel[15]/2)+$Secretaire+$Pers_Sup;
				if($CT_5 <1)$CT_5=1;
			}
			if($Amis_Industrie)$CT_30=ceil($CT_30/2);
			if($Organisateur4)
			{
				$CT_30U=ceil($CT_30U/2);
				$CT_10=ceil($CT_10/2);
			}
			if($Organisateur3)
			{
				$CT_5W=ceil($CT_5W/2);
			}
			if($Organisateur2)
			{
				$CT_10B=ceil($CT_10B/2);
				$CT_5B=ceil($CT_5B/2);
			}
			if($CT_10 <1)$CT_10=1;
			if($CT_10B <1)$CT_10B=1;
			if($CT_5B <1)$CT_5B=1;
			if($CT_5W <1)$CT_5W=1;
			$Pool_CT=$Credits;
			$con=dbconnecti();
			$result1=mysqli_query($con,"SELECT Nom,ArmePrincipale,ArmeSecondaire,Arme1_Nbr,Arme2_Nbr,Bombe,Bombe_Nbr FROM Avion WHERE ID='$Avion1'");
			$result2=mysqli_query($con,"SELECT Nom,ArmePrincipale,ArmeSecondaire,Arme1_Nbr,Arme2_Nbr,Bombe,Bombe_Nbr FROM Avion WHERE ID='$Avion2'");
			$result3=mysqli_query($con,"SELECT Nom,ArmePrincipale,ArmeSecondaire,Arme1_Nbr,Arme2_Nbr,Bombe,Bombe_Nbr FROM Avion WHERE ID='$Avion3'");
			$result=mysqli_query($con,"SELECT Nom,Camouflage,BaseAerienne,QualitePiste,Tour,Zone,LongPiste FROM Lieu WHERE ID='$Base'");
			mysqli_close($con);
			if($result3)
			{
				while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					$Avion3_nom=$data3['Nom'];
					$Avion3_Arme1=$data3['ArmePrincipale'];
					$Avion3_Arme2=$data3['ArmeSecondaire'];
					$Avion3_Arme1_Nbr=$data3['Arme1_Nbr'];
					$Avion3_Arme2_Nbr=$data3['Arme2_Nbr'];
				}
				mysqli_free_result($result3);
			}
			if($result2)
			{
				while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Avion2_nom=$data2['Nom'];
					$Avion2_Arme1=$data2['ArmePrincipale'];
					$Avion2_Arme2=$data2['ArmeSecondaire'];
					$Avion2_Arme1_Nbr=$data2['Arme1_Nbr'];
					$Avion2_Arme2_Nbr=$data2['Arme2_Nbr'];
				}
				mysqli_free_result($result2);
			}
			if($result1)
			{
				while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
				{
					$Avion1_nom=$data1['Nom'];
					$Avion1_Arme1=$data1['ArmePrincipale'];
					$Avion1_Arme2=$data1['ArmeSecondaire'];
					$Avion1_Arme1_Nbr=$data1['Arme1_Nbr'];
					$Avion1_Arme2_Nbr=$data1['Arme2_Nbr'];
				}
				mysqli_free_result($result1);
			}
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Base_nom=$data['Nom'];
					$Camouflage=$data['Camouflage'];
					$QualitePiste=$data['QualitePiste'];
					$BaseAerienne=$data['BaseAerienne'];
					$LongPiste=$data['LongPiste'];
					$Tour=$data['Tour'];
					$Zone=$data['Zone'];
				}
				mysqli_free_result($result);
			}
			$Credits_Piste=ceil((100-$QualitePiste)/2);
			if($Credits_Piste >40)$Credits_Piste=40;
			if($Personnel[12] >0 or $Pers_Sup)$Credits_Piste-=$Personnel[12]+$Pers_Sup;
			//Porte-avions
			if($Zone ==6)
			{
				$QualitePiste=100;
				$LongPiste=200;
			}
			$LongPiste*=($QualitePiste/100);
			if($BaseAerienne ==3)
			{
				$terrain='Le terrain';
				if($Zone ==8)
					$QualitePiste_img="piste38_".GetQualitePiste_img($QualitePiste).".jpg";
				if($Zone ==0 or $Zone ==2 or $Zone ==3 or $Zone ==9 or $Zone ==11)
					$QualitePiste_img="piste32_".GetQualitePiste_img($QualitePiste).".jpg";
				else
					$QualitePiste_img="piste31_".GetQualitePiste_img($QualitePiste).".jpg";
			}
			else
			{
				$terrain='La piste';
				$QualitePiste_img="piste".$BaseAerienne."_".GetQualitePiste_img($QualitePiste).".jpg";
			}			
			if($Camouflage >100)
			{
				$Camouflage=100;
				$Camouflage_txt='total';
			}
			elseif($Camouflage >80)
				$Camouflage_txt='supérieur';
			elseif($Camouflage >60)
				$Camouflage_txt='amélioré';
			elseif($Camouflage >40)
				$Camouflage_txt='avancé';
			elseif($Camouflage >20)
				$Camouflage_txt='classique';
			elseif($Camouflage >10)
				$Camouflage_txt='basique';
			else
				$Camouflage_txt='inexistant';
			$Sqn=GetSqn($country);
			if($Station_Meteo >10)
			{
				$Station_Meteo=10;
				$Station_Meteo_txt='Hi-Tech';
			}
			elseif($Station_Meteo >8)
				$Station_Meteo_txt='A la pointe';
			elseif($Station_Meteo >6)
				$Station_Meteo_txt='Perfectionnée';
			elseif($Station_Meteo >4)
				$Station_Meteo_txt='Améliorée';
			elseif($Station_Meteo >2)
				$Station_Meteo_txt='Standard';
			elseif($Station_Meteo >1)
				$Station_Meteo_txt='Elémentaire';
			else
				$Station_Meteo_txt='Inexistante';
			$Acces_Cdt=true;
			$Acces_Officier=true;
			/*Acces Officier
			$Acces_Officier=false;
			if($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==1)
			{
				$Acces_Officier=true;
				if($PlayerID ==$Commandant or $PlayerID ==1)
					$Acces_Cdt=true;
			}*/
			$Avion1_Arme1_txt=$Avion1_Arme1_Nbr." ".GetData("Armes","ID",$Avion1_Arme1,"Nom")." (".substr(GetData("Armes","ID",$Avion1_Arme1,"Calibre"),0,3)."mm)";
			if($Avion1_Arme2)
				$Avion1_Arme2_txt=$Avion1_Arme2_Nbr." ".GetData("Armes","ID",$Avion1_Arme2,"Nom")." (".substr(GetData("Armes","ID",$Avion1_Arme2,"Calibre"),0,3)."mm)";
			else
				$Avion1_Arme2_txt='Aucun';
			$Avion2_Arme1_txt=$Avion2_Arme1_Nbr." ".GetData("Armes","ID",$Avion2_Arme1,"Nom")." (".substr(GetData("Armes","ID",$Avion2_Arme1,"Calibre"),0,3)."mm)";
			if($Avion2_Arme2)
				$Avion2_Arme2_txt=$Avion2_Arme2_Nbr." ".GetData("Armes","ID",$Avion2_Arme2,"Nom")." (".substr(GetData("Armes","ID",$Avion2_Arme2,"Calibre"),0,3)."mm)";
			else
				$Avion2_Arme2_txt='Aucun';
			$Avion3_Arme1_txt=$Avion3_Arme1_Nbr." ".GetData("Armes","ID",$Avion3_Arme1,"Nom")." (".substr(GetData("Armes","ID",$Avion3_Arme1,"Calibre"),0,3)."mm)";
			if($Avion3_Arme2)
				$Avion3_Arme2_txt=$Avion3_Arme2_Nbr." ".GetData("Armes","ID",$Avion3_Arme2,"Nom")." (".substr(GetData("Armes","ID",$Avion3_Arme2,"Calibre"),0,3)."mm)";
			else
				$Avion3_Arme2_txt='Aucun';
			if($Avion1_Bombes)
			{
				if($Avion1_Bombe ==800)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Torpille(s)';
				elseif($Avion1_Bombe ==26)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Caméra fixe';
				elseif($Avion1_Bombe ==27)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Caméra haute';
				elseif($Avion1_Bombe ==30)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Fusée(s)';
				elseif($Avion1_Bombe ==80)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Rocket(s)';
				elseif($Avion1_Bombe ==50000)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 8mm';
				elseif($Avion1_Bombe ==15000)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 13mm';
				elseif($Avion1_Bombe ==5000)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 20mm';
				elseif($Avion1_Bombe ==3000)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 30mm';
				elseif($Avion1_Bombe ==1500)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 40mm';
				elseif($Avion1_Bombe ==1200)
					$Avion1_Bombes_txt=$Avion1_Bombes." Cargaison(s) d'Octane 87";
				elseif($Avion1_Bombe ==1100)
					$Avion1_Bombes_txt=$Avion1_Bombes." Cargaison(s) d'Octane 100";
				elseif($Avion1_Bombe ==350)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Réservoir externe';
				elseif($Avion1_Bombe ==100)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Parachutistes';
				else
					$Avion1_Bombes_txt=$Avion1_Bombes.' x '.$Avion1_Bombe.'kg';
			}
			else
				$Avion1_Bombes_txt='Vide';
			if($Avion2_Bombes)
			{
				if($Avion2_Bombe ==800)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Torpille(s)';
				elseif($Avion2_Bombe ==26)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Caméra fixe';
				elseif($Avion2_Bombe ==27)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Caméra haute';
				elseif($Avion2_Bombe ==30)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Fusée(s)';
				elseif($Avion2_Bombe ==80)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Rocket(s)';
				elseif($Avion2_Bombe ==50000)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 8mm';
				elseif($Avion2_Bombe ==15000)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 13mm';
				elseif($Avion2_Bombe ==5000)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 20mm';
				elseif($Avion2_Bombe ==3000)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 30mm';
				elseif($Avion2_Bombe ==1500)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 40mm';
				elseif($Avion2_Bombe ==1200)
					$Avion2_Bombes_txt=$Avion2_Bombes." Cargaison(s) d'Octane 87";
				elseif($Avion2_Bombe ==1100)
					$Avion2_Bombes_txt=$Avion2_Bombes." Cargaison(s) d'Octane 100";
				elseif($Avion2_Bombe ==100)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Parachutistes';
				elseif($Avion2_Bombe ==350)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Réservoir externe';
				else
					$Avion2_Bombes_txt=$Avion2_Bombes.' x '.$Avion2_Bombe.'kg';
			}
			else
				$Avion2_Bombes_txt='Vide';			
			if($Avion3_Bombes)
			{
				if($Avion3_Bombe ==800)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Torpille(s)';
				elseif($Avion3_Bombe ==26)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Caméra fixe';
				elseif($Avion3_Bombe ==27)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Caméra haute';
				elseif($Avion3_Bombe ==30)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Fusée(s)';
				elseif($Avion3_Bombe ==80)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Rocket(s)';
				elseif($Avion3_Bombe ==50000)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 8mm';
				elseif($Avion3_Bombe ==15000)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 13mm';
				elseif($Avion3_Bombe ==5000)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 20mm';
				elseif($Avion3_Bombe ==3000)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 30mm';
				elseif($Avion3_Bombe ==1500)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 40mm';
				elseif($Avion3_Bombe ==1200)
					$Avion3_Bombes_txt=$Avion3_Bombes." Cargaison(s) d'Octane 87";
				elseif($Avion3_Bombe ==1100)
					$Avion3_Bombes_txt=$Avion3_Bombes." Cargaison(s) d'Octane 100";
				elseif($Avion3_Bombe ==100)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Parachutistes';
				elseif($Avion3_Bombe ==350)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Réservoir externe';
				else
					$Avion3_Bombes_txt=$Avion3_Bombes.' x '.$Avion3_Bombe.'kg';
			}
			else
				$Avion3_Bombes_txt='Vide';			
			$Array_Mod1=GetAmeliorations($Avion1);				
			$Bombe50_nbr1=$Array_Mod1[12];
			$Bombe125_nbr1=$Array_Mod1[13];
			$Bombe250_nbr1=$Array_Mod1[14];
			$Bombe500_nbr1=$Array_Mod1[15];
			$Bombe1000_nbr1=$Array_Mod1[32];
			$Bombe2000_nbr1=$Array_Mod1[33];
			$Camera_low1=$Array_Mod1[16];
			$Camera_high1=$Array_Mod1[17];
			$Baby1=$Array_Mod1[18];
			$Torpilles1=$Array_Mod1[20];
			$Mines1=$Array_Mod1[21];	
			$Fret_mun81=$Array_Mod1[22];
			$Fret_mun131=$Array_Mod1[23];
			$Fret_mun201=$Array_Mod1[24];
			$Fret_mun301=$Array_Mod1[36];
			$Fret_mun401=$Array_Mod1[37];
			$Fret_mun501=$Array_Mod1[38];
			$Fret_mun601=$Array_Mod1[39];
			$Fret_mun751=$Array_Mod1[40];
			$Fret_mun901=$Array_Mod1[41];
			$Fret_871=$Array_Mod1[25];
			$Fret_1001=$Array_Mod1[26];
			$Fret_501=$Array_Mod1[27];
			$Fret_1251=$Array_Mod1[28];
			$Fret_2501=$Array_Mod1[29];
			$Fret_5001=$Array_Mod1[30];
			$Fret_para1=$Array_Mod1[31];
			$Rockets1=$Array_Mod1[35];			
			$Array_Mod2=GetAmeliorations($Avion2);	
			$Bombe50_nbr2=$Array_Mod2[12];
			$Bombe125_nbr2=$Array_Mod2[13];
			$Bombe250_nbr2=$Array_Mod2[14];
			$Bombe500_nbr2=$Array_Mod2[15];
			$Bombe1000_nbr2=$Array_Mod2[32];
			$Bombe2000_nbr2=$Array_Mod2[33];
			$Camera_low2=$Array_Mod2[16];
			$Camera_high2=$Array_Mod2[17];
			$Baby2=$Array_Mod2[18];
			$Torpilles2=$Array_Mod2[20];
			$Mines2=$Array_Mod2[21];
			$Fret_mun82=$Array_Mod2[22];
			$Fret_mun132=$Array_Mod2[23];
			$Fret_mun202=$Array_Mod2[24];
			$Fret_mun302=$Array_Mod2[36];
			$Fret_mun402=$Array_Mod2[37];
			$Fret_mun502=$Array_Mod2[38];
			$Fret_mun602=$Array_Mod2[39];
			$Fret_mun752=$Array_Mod2[40];
			$Fret_mun902=$Array_Mod2[41];
			$Fret_872=$Array_Mod2[25];
			$Fret_1002=$Array_Mod2[26];
			$Fret_502=$Array_Mod2[27];
			$Fret_1252=$Array_Mod2[28];
			$Fret_2502=$Array_Mod2[29];
			$Fret_5002=$Array_Mod2[30];
			$Fret_para2=$Array_Mod2[31];
			$Rockets2=$Array_Mod2[35];			
			$Array_Mod3=GetAmeliorations($Avion3);
			$Bombe50_nbr3=$Array_Mod3[12];
			$Bombe125_nbr3=$Array_Mod3[13];
			$Bombe250_nbr3=$Array_Mod3[14];
			$Bombe500_nbr3=$Array_Mod3[15];
			$Bombe1000_nbr3=$Array_Mod3[32];
			$Bombe2000_nbr3=$Array_Mod3[33];
			$Camera_low3=$Array_Mod3[16];
			$Camera_high3=$Array_Mod3[17];
			$Baby3=$Array_Mod3[18];
			$Torpilles3=$Array_Mod3[20];
			$Mines3=$Array_Mod3[21];
			$Fret_mun83=$Array_Mod3[22];
			$Fret_mun133=$Array_Mod3[23];
			$Fret_mun203=$Array_Mod3[24];
			$Fret_mun303=$Array_Mod3[36];
			$Fret_mun403=$Array_Mod3[37];
			$Fret_mun503=$Array_Mod3[38];
			$Fret_mun603=$Array_Mod3[39];
			$Fret_mun753=$Array_Mod3[40];
			$Fret_mun903=$Array_Mod3[41];
			$Fret_873=$Array_Mod3[25];
			$Fret_1003=$Array_Mod3[26];
			$Fret_503=$Array_Mod3[27];
			$Fret_1253=$Array_Mod3[28];
			$Fret_2503=$Array_Mod3[29];
			$Fret_5003=$Array_Mod3[30];
			$Fret_para3=$Array_Mod3[31];
			$Rockets3=$Array_Mod3[35];		
			$Pers=floor($Reputation/20000)+1;
			if($Pers >10)$Pers=10;		
		//include_once('./menu_esc_staff.php');
		include_once('./menu_escadrille.php');		
		if($Tab =='Avions' or $Tab =='Armement')
		{
		?>
		<h2>Hangar</h2>
		<table class='table table-striped'>
			<thead><tr>
				<th width="15%">Escadrille</th>
				<th width="30%">Avions</th>
				<th width="20%">Armement Principal</th>
				<th width="15%">Armement Secondaire</th>
				<th width="10%">Carburant</th>
				<th width="10%">Soute</th>
			</tr></thead>
			<tr>                               
				<td><?echo $Sqn;?> 1</td>
				<td><?echo $Avion1_nbr.' '.GetAvionIcon($Avion1,$country,0,$Unite,$Front);?></td>
				<td><? echo $Avion1_Arme1_txt;?></td>
				<td><? echo $Avion1_Arme2_txt;?></td>
				<td><? echo GetData("Moteur","ID",GetData("Avion","ID",$Avion1,"Engine"),"Carburant")." Octane";?></td><td><? echo $Avion1_Bombes_txt;?></td>
			</tr>	
			<tr>                               
				<td><?echo $Sqn;?> 2</td>
				<td><?echo $Avion2_nbr.' '.GetAvionIcon($Avion2,$country,0,$Unite,$Front);?></td>
				<td><? echo $Avion2_Arme1_txt;?></td>
				<td><? echo $Avion2_Arme2_txt;?></td>
				<td><? echo GetData("Moteur","ID",GetData("Avion","ID",$Avion2,"Engine"),"Carburant")." Octane";?></td><td><? echo $Avion2_Bombes_txt;?></td>
			</tr>	
			<tr>                               
				<td><?echo $Sqn;?> 3</td>
				<td><?echo $Avion3_nbr.' '.GetAvionIcon($Avion3,$country,0,$Unite,$Front);?></td>
				<td><? echo $Avion3_Arme1_txt;?></td>
				<td><? echo $Avion3_Arme2_txt;?></td>
				<td><? echo GetData("Moteur","ID",GetData("Avion","ID",$Avion3,"Engine"),"Carburant")." Octane";?></td><td><? echo $Avion3_Bombes_txt;?></td>
			</tr>
		</table>
		<?}elseif($Tab =='Base'){?>
		<h2>Base</h2>
		<table class='table'>
			<thead><tr><th>Lieu</th><th>Camouflage</th><th>Piste</th><th>Tour</th><th>Station météo</th></tr></thead>
			<tr><th><?echo $Base_nom;?></th><td><img src='images/cam<?echo $Camouflage;?>.jpg' title='Camouflage <?echo $Camouflage_txt;?>'></td>
			<td><img src='images/<?echo $QualitePiste_img;?>' title='<?echo $terrain;?> : Etat <?echo $QualitePiste;?>%'><br>Longueur : <?echo $LongPiste;?>m</td>
			<td><img src='images/vehicules/vehicule2.gif' title='Etat <?echo $Tour;?>%'></td>
			<td><?echo $Station_Meteo_txt;?></td></tr>
		</table>
		<?}?>
		<form action='esc_gestioncdt1.php' method='post'>
		<input type='hidden' name='Unite' value="<?=$Unite;?>">
		<input type='hidden' name='CT30' value="<?=$CT_30;?>">
		<input type='hidden' name='CT30U' value="<?=$CT_30U;?>">
		<input type='hidden' name='CT10' value="<?=$CT_10;?>">
		<input type='hidden' name='CT5' value="<?=$CT_5;?>">
		<input type='hidden' name='CT5W' value="<?=$CT_5W;?>">
		<input type='hidden' name='CT5B' value="<?=$CT_5B;?>">
		<input type='hidden' name='CT10B' value="<?=$CT_10B;?>">
			<?if($Tab =='Avions'){?>
		<h2>Gestion du parc avion</h2>
			<?if($Pool_CT >=$CT_30){?>
			<table class='table'>
			<thead><tr><th>Remplacer un modèle d'avion <img src='/images/CT<?echo $CT_30;?>.png' title='Montant en Crédits Temps que nécessite cette action'></th><th>Choix du modèle</th></tr></thead>
			<td align="left">
				<select name="sqnc" class='form-control' style="width: 200px">		
					<option value="1"><?echo $Sqn?> 1</option>
					<option value="2"><?echo $Sqn?> 2</option>
					<option value="3"><?echo $Sqn?> 3</option>
			</select></th>
			<th align="left">
					<select name="avionc" class='form-control' style="width: 250px">
					<option value='0'>Ne rien changer</option>
						<?$modele='';
						$Level=($Reputation/5000)+1;
						if(IsAxe($country))
							$Allies=array(1,6,9,15,18,19,20,24);
						else
							$Allies=array(2,3,4,5,7,8,10,35,36);
						$query="SELECT DISTINCT ID,Nom,Type,Production,Usine1,Fin_Prod,Lease FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Premium=0 AND Type='$Unit_Type' AND ((Rating BETWEEN 0 AND '$Level') OR (Fin_Prod <='$Date_Campagne') OR ID IN (".$Avion1.",".$Avion2.",".$Avion3.")) ORDER BY Nom ASC";						
						$con=dbconnecti();
						$result=mysqli_query($con,$query);
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Plane=$data['ID'];
								$Production=floor($data['Stock']);
								$Usine1=$data['Usine1'];
								$con=dbconnecti();
								$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Plane' AND PVP=1"),0);
								$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Plane'"),0);
								$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$Plane' AND Etat=1"),0);
								$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$Plane' AND Etat=1"),0);
								$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$Plane' AND Etat=1"),0);
								$resultu1=mysqli_query($con,"SELECT Flag,Flag_Usine FROM Lieu WHERE ID='$Usine1'");
								mysqli_close($con);
								if($resultu1)
								{
									while($datau1=mysqli_fetch_array($resultu1,MYSQLI_ASSOC))
									{
										$Usine1_Flag=$datau1['Flag'];
										$Usine1_Flag_Usine=$datau1['Flag_Usine'];
									}
									mysqli_free_result($resultu1);
								}
								if($data['Lease'] and $data['Fin_Prod'] >$Date_Campagne)
								{
									if(in_array($Usine1_Flag,$Allies) and in_array($Usine1_Flag_Usine,$Allies))
										$lend_lease=true;
									else
										$lend_lease=false;
								}
								else
									$lend_lease=true;
								if($lend_lease)
								{
									$con=dbconnecti(4);
									$Perdu=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$Plane' AND Avion_Nbr >0"),0);
									mysqli_close($con);								
									if(($DCA + $Abattu + $Perdu + $Service1 + $Service2 + $Service3) <$Production+1)
									{
										$Type=GetAvionType($data['Type']);
										$modele.="<option value='".$data['ID']."'>".$data['Nom']." ( ".$Type." )</option>";
									}
								}
								else
									$modele.="<option value='".$data['ID']."' disabled>".$data['Nom']." (Lend-Lease)</option>";
							}
							mysqli_free_result($result);
						}
						echo $modele;
						?>
				</select></th>
			</tr>
			</table>
			<?}if($Pool_CT >=$CT_5W){?>
			<table class='table'>
			<thead><tr><th><img src='/images/CT<?echo $CT_5W;?>.png' title='Montant en Crédits Temps que nécessite cette action'> Transférer des avions <a href='#' class='popup'><img src='images/help.png'><span>12 avions maximum par <?echo $Sqn;?>. Seuls les avions du même modèle peuvent être transférés</span></a></th><th>du <?echo $Sqn;?></th><th>vers le <?echo $Sqn;?></th></tr></thead>
			<tr><th>
					<select name='Avion_Transfer' class='form-control' style='width: 100px'>
						<option value='0' selected>0</option>
						<?if($Avion1_nbr <12 or $Avion2_nbr <12 or $Avion3_nbr <12){?>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
						<option value='11'>11</option>
						<option value='12'>12</option>
						<?}?>
					</select>
				</th>
				<td>		
					<select name='Staffel_o' class='form-control' style='width: 100px'>
						<?if($Avion1_nbr){?>
						<option value='1' selected>1</option>
						<?}if($Avion2_nbr){?>
						<option value='2'>2</option>
						<?}if($Avion3_nbr){?>
						<option value='3'>3</option>
						<?}?>
					</select>
				</td>
				<td>
					<select name='Staffel_d' class='form-control' style='width: 100px'>
						<?if($Avion1_nbr <12){?>
						<option value='1' selected>1</option>
						<?}if($Avion2_nbr <12){?>
						<option value='2'>2</option>
						<?}if($Avion3_nbr <12){?>
						<option value='3'>3</option>
						<?}?>
					</select>
			</td></tr>
			</table>
			<?}/*?>
			<table class='table table-striped'>
			<thead><tr><th><?echo $Sqn;?></th><th colspan='2'>Limite de crashs</th><th colspan='2'>Heures de vol minimum</th></tr></thead>
				<td>1</td><td>limite actuelle : <?echo $Avion1_Limite;?></td>
				<td><select name='Avion1_Limite' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10 ou plus</option>
					</select>
				</td><td>minimum actuel : <?echo $Avion1_XP;?></td>
				<td><select name='Avion1_exp' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='1'>0</option>
						<option value='10'>10</option>
						<option value='25'>25</option>
						<option value='50'>50</option>
						<option value='75'>75</option>
						<option value='100'>100</option>
						<option value='150'>150</option>
						<option value='200'>200</option>
						<option value='250'>250</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>2</td><td>limite actuelle : <?echo $Avion2_Limite;?></td>
				<td><select name='Avion2_Limite' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10 ou plus</option>
					</select>
				</td><td>minimum actuel : <?echo $Avion2_XP;?></td>
				<td><select name='Avion2_exp' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='1'>0</option>
						<option value='10'>10</option>
						<option value='25'>25</option>
						<option value='50'>50</option>
						<option value='75'>75</option>
						<option value='100'>100</option>
						<option value='150'>150</option>
						<option value='200'>200</option>
						<option value='250'>250</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>3</td><td>limite actuelle : <?echo $Avion3_Limite;?></td>
				<td><select name='Avion3_Limite' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10 ou plus</option>
					</select>
				</td><td>minimum actuel : <?echo $Avion3_XP;?></td>
				<td><select name='Avion3_exp' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='1'>0</option>
						<option value='10'>10</option>
						<option value='25'>25</option>
						<option value='50'>50</option>
						<option value='75'>75</option>
						<option value='100'>100</option>
						<option value='150'>150</option>
						<option value='200'>200</option>
						<option value='250'>250</option>
					</select>
				</td>
			</tr>
			</table>
			<?*/
			}
			elseif($Tab =='Armement')
			{
				if($Pool_CT >=$CT_5W)
				{
					$Munitions1_txt=GetMun_txt(GetData("Unit","ID",$Unite,"Avion1_Mun1"));
					$Munitions2_txt=GetMun_txt(GetData("Unit","ID",$Unite,"Avion2_Mun1"));
					$Munitions3_txt=GetMun_txt(GetData("Unit","ID",$Unite,"Avion3_Mun1"));
					if($Unit_Type ==6)
					{						
						echo "<table class='table table-striped'>
						<thead><tr><th colspan='3'>Fret par défaut <img src='/images/CT".$CT_5W.".png' title='Montant en Crédits Temps que nécessite cette action'></th></tr></thead>";						
						echo "<tr><th>".$Sqn." 1</th>
							<td>".$Avion1_Bombes_txt."</td>
							<td align='left'>
								<select name='fret1' class='form-control' style='width: 200px'>
									<option value='0'>Ne rien changer</option>
									<option value='1'>Aucune</option>";
							if($Fret_501 >0)
							{
								$masse_b=$Fret_501*50;
							?>
							<option value='2'><? echo $Fret_501." bombes de 50kg";?> (<?echo $masse_b;?>kg)</option>
							<?}
							if($Fret_1251 >0)
							{?>
							<option value='3'><? echo $Fret_1251." bombes de 125kg";?> (<?echo $Fret_1251*125;?>kg)</option>
							<?}
							if($Fret_2501 >0)
							{?>
							<option value='4'><? echo $Fret_2501." bombes de 250kg";?> (<?echo $Fret_2501*250;?>kg)</option>
							<?}
							if($Fret_5001 >0)
							{?>
							<option value='5'><? echo $Fret_5001." bombes de 500kg";?> (<?echo $Fret_5001*500;?>kg)</option>
							<?}
							if($Fret_mun81 >0)
							{?>
							<option value='6'><? echo $Fret_mun81*50000; echo " munitions de 8mm";?> (<?echo $Fret_mun81*1000;?>kg)</option>
							<?}
							if($Fret_mun131 >0)
							{?>
							<option value='7'><? echo $Fret_mun131*15000; echo " munitions de 13mm";?> (<?echo $Fret_mun131*1000;?>kg)</option>
							<?}
							if($Fret_mun201 >0)
							{?>
							<option value='8'><? echo $Fret_mun201*5000; echo " munitions de 20mm";?> (<?echo $Fret_mun201*1000;?>kg)</option>
							<?}
							if($Fret_mun301 >0)
							{?>
							<option value='12'><? echo $Fret_mun301*3000; echo " munitions de 30mm";?> (<?echo $Fret_mun301*1000;?>kg)</option>
							<?}
							if($Fret_mun401 >0)
							{?>
							<option value='13'><? echo $Fret_mun401*1500; echo " munitions de 40mm";?> (<?echo $Fret_mun401*1000;?>kg)</option>
							<?}
							if($Fret_871 >0)
							{?>
							<option value='9'><? echo $Fret_871*1200; echo " litres d'octane 87";?> (<?echo $Fret_871*1000;?>kg)</option>
							<?}
							if($Fret_1001 >0)
							{?>
							<option value='10'><? echo $Fret_1001*1100; echo " litres d'octane 100";?> (<?echo $Fret_1001*1000;?>kg)</option>
							<?}
							/*if($Fret_para1 >0)
							{?>
							<option value='11'><? echo $Fret_para1." parachutistes";?> (<?echo $Fret_para1*100;?>kg)</option>
							<?}*/?>
						</select></td></tr>						
						<?echo "<tr><th>".$Sqn." 2</th>
							<td>".$Avion2_Bombes_txt."</td>
							<td align='left'>
								<select name='fret2' class='form-control' style='width: 200px'>
									<option value='0'>Ne rien changer</option>
									<option value='1'>Aucune</option>";
							if($Fret_502 >0)
							{?>
							<option value='2'><? echo $Fret_502." bombes de 50kg";?> (<?echo $Fret_502*50;?>kg)</option>
							<?}
							if($Fret_1252 >0)
							{?>
							<option value='3'><? echo $Fret_1252." bombes de 125kg";?> (<?echo $Fret_1252*125;?>kg)</option>
							<?}
							if($Fret_2502 >0)
							{?>
							<option value='4'><? echo $Fret_2502." bombes de 250kg";?> (<?echo $Fret_2502*250;?>kg)</option>
							<?}
							if($Fret_5002 >0)
							{?>
							<option value='5'><? echo $Fret_5002." bombes de 500kg";?> (<?echo $Fret_5002*500;?>kg)</option>
							<?}
							if($Fret_mun82 >0)
							{?>
							<option value='6'><? echo $Fret_mun82*50000; echo " munitions de 8mm";?> (<?echo $Fret_mun82*1000;?>kg)</option>
							<?}
							if($Fret_mun132 >0)
							{?>
							<option value='7'><? echo $Fret_mun132*15000; echo " munitions de 13mm";?> (<?echo $Fret_mun132*1000;?>kg)</option>
							<?}
							if($Fret_mun202 >0)
							{?>
							<option value='8'><? echo $Fret_mun202*5000; echo " munitions de 20mm";?> (<?echo $Fret_mun202*1000;?>kg)</option>
							<?}
							if($Fret_mun302 >0)
							{?>
							<option value='12'><? echo $Fret_mun302*3000; echo " munitions de 30mm";?> (<?echo $Fret_mun302*1000;?>kg)</option>
							<?}
							if($Fret_mun402 >0)
							{?>
							<option value='13'><? echo $Fret_mun402*1500; echo " munitions de 40mm";?> (<?echo $Fret_mun402*1000;?>kg)</option>
							<?}
							if($Fret_872 >0)
							{?>
							<option value='9'><? echo $Fret_872*1200; echo " litres d'octane 87";?> (<?echo $Fret_872*1000;?>kg)</option>
							<?}
							if($Fret_1002 >0)
							{?>
							<option value='10'><? echo $Fret_1002*1100; echo " litres d'octane 100";?> (<?echo $Fret_1002*1000;?>kg)</option>
							<?}
							/*if($Fret_para2 >0)
							{?>
							<option value='11'><? echo $Fret_para2." parachutistes";?> (<?echo $Fret_para2*100;?>kg)</option>
							<?}*/?>
						</select></td></tr>						
						<?echo "<tr><th>".$Sqn." 3</th>
							<td>".$Avion3_Bombes_txt."</td>
							<td align='left'>
								<select name='fret3' class='form-control' style='width: 200px'>
									<option value='0'>Ne rien changer</option>
									<option value='1'>Aucune</option>";
							if($Fret_503 >0)
							{?>
							<option value='2'><? echo $Fret_503." bombes de 50kg";?> (<?echo $Fret_503*50;?>kg)</option>
							<?}
							if($Fret_1253 >0)
							{?>
							<option value='3'><? echo $Fret_1253." bombes de 125kg";?> (<?echo $Fret_1253*125;?>kg)</option>
							<?}
							if($Fret_2503 >0)
							{?>
							<option value='4'><? echo $Fret_2503." bombes de 250kg";?> (<?echo $Fret_2503*250;?>kg)</option>
							<?}
							if($Fret_5003 >0)
							{?>
							<option value='5'><? echo $Fret_5003." bombes de 500kg";?> (<?echo $Fret_5003*500;?>kg)</option>
							<?}
							if($Fret_mun83 >0)
							{?>
							<option value='6'><? echo $Fret_mun83*50000; echo " munitions de 8mm";?> (<?echo $Fret_mun83*1000;?>kg)</option>
							<?}
							if($Fret_mun133 >0)
							{?>
							<option value='7'><? echo $Fret_mun133*15000; echo " munitions de 13mm";?> (<?echo $Fret_mun133*1000;?>kg)</option>
							<?}
							if($Fret_mun203 >0)
							{?>
							<option value='8'><? echo $Fret_mun203*5000; echo " munitions de 20mm";?> (<?echo $Fret_mun203*1000;?>kg)</option>
							<?}
							if($Fret_mun303 >0)
							{?>
							<option value='12'><? echo $Fret_mun303*3000; echo " munitions de 30mm";?> (<?echo $Fret_mun303*1000;?>kg)</option>
							<?}
							if($Fret_mun403 >0)
							{?>
							<option value='13'><? echo $Fret_mun403*1500; echo " munitions de 40mm";?> (<?echo $Fret_mun403*1000;?>kg)</option>
							<?}
							if($Fret_873 >0)
							{?>
							<option value='9'><? echo $Fret_873*1200; echo " litres d'octane 87";?> (<?echo $Fret_873*1000;?>kg)</option>
							<?}
							if($Fret_1003 >0)
							{?>
							<option value='10'><? echo $Fret_1003*1100; echo " litres d'octane 100";?> (<?echo $Fret_1003*1000;?>kg)</option>
							<?}
							/*if($Fret_para3 >0)
							{?>
							<option value='11'><? echo $Fret_para3." parachutistes";?> (<?echo $Fret_para3*100;?>kg)</option>
							<?}*/?>
						</select></td></tr></table>
					<?
					}
					else
					{
			?>
			<h2>Gestion de l'armement</h2>
			<table class='table table-striped'>
			<thead><tr><th>Staffel</th><th colspan='2'>Munitions par défaut <a href='#' class='popup'><img src='images/help.png'><span>Toutes les rockets sont considérées comme une munition de type HEAT</span></a> <img src='/images/CT<?echo $CT_5W;?>.png' title='Montant en Crédits Temps que nécessite cette action'></th>
			<th colspan='3'>Bombes par défaut <a href='#' class='popup'><img src='images/help.png'><span>Attention qu'augmenter la charge de bombes diminuera l'autonomie et les performances! Toutes les torpilles sont considérées comme une munition de type anti-navire, les charges et les mines comme des munitions de type HE</span></a> <img src='/images/CT<?echo $CT_5W;?>.png' title='Montant en Crédits Temps que nécessite cette action'></th></tr></thead>
			<tr><th>1</th>
				<td><? echo $Munitions1_txt;?></td>
				<td align="left"><select name="muns1" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Standard</option>
						<option value='2'>AP (Perforant, chance d'ignorer un blindage lourd à courte portée)</option>
						<option value='3'>HE (Explosif, dégâts supplémentaires importants contre cibles non blindées)</option>
						<option value='4'>Incendiaire (chance de dégâts supplémentaires contre cibles non blindées)</option>
						<option value='5'>APHE (Chance d'ignorer un blindage léger. Dégâts supplémentaires importants si blindage perforé)</option>
						<option value='6'>API (Chance d'ignorer un blindage léger. Dégâts supplémentaires dans la durée si blindage perforé)</option>
				</select></td>
				<td><?echo $Avion1_Bombes_txt.' - '.GetBombeT($Avion1_BombeT);?></td>
				<td align="left"><select name="bombe_type1" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Standard</option>
						<option value='2'>Incendiaire (Efficace contre les petits bâtiments et les véhicules non blindés)</option>
						<option value='3'>Anti-personnel(Efficace contre les soldats et les chevaux)</option>
						<option value='4'>Anti-tank (Efficace contre les véhicules)</option>
						<option value='5'>Anti-navire (Efficace contre les navires)</option>
						<option value='6'>Anti-bâtiment (Efficace contre les bâtiments)</option>
						<option value='7'>Anti-piste (Efficace contre les pistes)</option>
					<?if($Date_Campagne >"1945-01-01"){?>
						<option value='8'>Bouncing bomb (Efficace contre les barrages)</option>
						<option value='9'>Thermobarique (Pouvoir explosif augmenté)</option>
						<option value='10'>Napalm (Enflamme tout type de cible)</option>
					<?}?>
				</select></td>
				<td align="left"><select name="bombes1" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Aucune</option>
						<?if($Bombe50_nbr1 >0)
						{
							for($ib=1;$ib<=$Bombe50_nbr1;$ib++)
							{
								$ibn='50_'.$ib;
								$bombe_kg=$ib*50;
								$bombes1_combo50_txt.="<option value='".$ibn."'>".$ib." bombes de 50kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes1_combo50_txt;
						}
						if($Bombe125_nbr1 >0)
						{					
							for($ib=1;$ib<=$Bombe125_nbr1;$ib++)
							{
								$ibn='125_'.$ib;
								$bombe_kg=$ib*125;
								$bombes1_combo125_txt.="<option value='".$ibn."'>".$ib." bombes de 125kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes1_combo125_txt;
						}
						if($Bombe250_nbr1 >0)
						{
							for($ib=1;$ib<=$Bombe250_nbr1;$ib++)
							{
								$ibn='250_'.$ib;
								$bombe_kg=$ib*250;
								$bombes1_combo250_txt.="<option value='".$ibn."'>".$ib." bombes de 250kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes1_combo250_txt;
						}
						if($Bombe500_nbr1 >0)
						{
							for($ib=1;$ib<=$Bombe500_nbr1;$ib++)
							{
								$ibn='500_'.$ib;
								$bombe_kg=$ib*500;
								$bombes1_combo500_txt.="<option value='".$ibn."'>".$ib." bombes de 500kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes1_combo500_txt;
						}
						if($Bombe1000_nbr1 >0)
						{
							for($ib=1;$ib<=$Bombe1000_nbr1;$ib++)
							{
								$ibn='1000_'.$ib;
								$bombe_kg=$ib*1000;
								$bombes1_combo1000_txt.="<option value='".$ibn."'>".$ib." bombes de 1000kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes1_combo1000_txt;
						}
						if($Torpilles1 >0)
						{
							for($ib=1;$ib<=$Torpilles1;$ib++)
							{
								$ibn='800_'.$ib;
								$bombe_kg=$ib*800;
								$bombes1_combo800_txt.="<option value='".$ibn."'>".$ib." torpille(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes1_combo800_txt;
						}
						if($Mines1 >0)
						{
							for($ib=1;$ib<=$Mines1;$ib++)
							{
								$ibm='300_'.$ib;
								$bombe_kg=$ib*300;
								$bombes1_combo300_txt.="<option value='".$ibm."'>".$ib." charge(s)(".$bombe_kg."kg)</option>";
								$ibn='400_'.$ib;
								$bombe_kg=$ib*400;
								$bombes1_combo400_txt.="<option value='".$ibn."'>".$ib." mine(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes1_combo300_txt;
							echo $bombes1_combo400_txt;
						}
						if($Rockets1 >0)
						{
							for($ib=1;$ib<=$Rockets1;$ib++)
							{
								$ibn='80_'.$ib;
								$bombe_kg=$ib*80;
								$bombes1_combo80_txt.="<option value='".$ibn."'>".$ib." rocket(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes1_combo80_txt;
						}
						if($Baby1 >0)
							echo "<option value='350_1'>Réservoir largable de ".$Baby1."L</option>";
						if($Camera_low1 ==25){?>
						<option value='25_1'>1 Caméra portative (Basse altitude uniquement)</option>
						<?}if($Camera_low1 ==26 or $Camera_high1 ==26){?>
						<option value='26_1'>1 Caméra fixe (Moyenne altitude)</option>
						<?}if($Camera_high1 ==27){?>
						<option value='27_1'>1 Caméra fixe (Haute altitude)</option>
						<?}if($Bombe125_nbr1 >0 or $Bombe250_nbr1 >0 or $Bombe500_nbr1 >0){?>	
						<option value='30_10'>10 fusées éclairantes (300 kg)</option>
						<?}?>
			</select></td></tr>
			<tr><th>2</th>
				<td><? echo $Munitions2_txt;?></td>
				<td align="left"><select name="muns2" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Standard</option>
						<option value='2'>AP (Perforant, chance d'ignorer un blindage lourd à courte portée)</option>
						<option value='3'>HE (Explosif, dégâts supplémentaires importants contre cibles non blindées)</option>
						<option value='4'>Incendiaire (chance de dégâts supplémentaires contre cibles non blindées)</option>
						<option value='5'>APHE (Chance d'ignorer un blindage léger. Dégâts supplémentaires importants si blindage perforé)</option>
						<option value='6'>API (Chance d'ignorer un blindage léger. Dégâts supplémentaires dans la durée si blindage perforé)</option>
				</select></td>
				<td><?echo $Avion2_Bombes_txt.' - '.GetBombeT($Avion2_BombeT);?></td>
				<td align="left"><select name="bombe_type2" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Standard</option>
						<option value='2'>Incendiaire (Efficace contre les petits bâtiments et les véhicules non blindés)</option>
						<option value='3'>Anti-personnel(Efficace contre les soldats et les chevaux)</option>
						<option value='4'>Anti-tank (Efficace contre les véhicules)</option>
						<option value='5'>Anti-navire (Efficace contre les navires)</option>
						<option value='6'>Anti-bâtiment (Efficace contre les bâtiments)</option>
						<option value='7'>Anti-piste (Efficace contre les pistes)</option>
				</select></td>
				<td align="left"><select name="bombes2" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Aucune</option>
						<?if($Bombe50_nbr2 >0)
						{
							for($ib=1;$ib<=$Bombe50_nbr2;$ib++)
							{
								$ibn='50_'.$ib;
								$bombe_kg=$ib*50;
								$bombes2_combo50_txt.="<option value='".$ibn."'>".$ib." bombes de 50kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes2_combo50_txt;
						}
						if($Bombe125_nbr2 >0)
						{					
							for($ib=1;$ib<=$Bombe125_nbr2;$ib++)
							{
								$ibn='125_'.$ib;
								$bombe_kg=$ib*125;
								$bombes2_combo125_txt.="<option value='".$ibn."'>".$ib." bombes de 125kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes2_combo125_txt;
						}
						if($Bombe250_nbr2 >0)
						{
							for($ib=1;$ib<=$Bombe250_nbr2;$ib++)
							{
								$ibn='250_'.$ib;
								$bombe_kg=$ib*250;
								$bombes2_combo250_txt.="<option value='".$ibn."'>".$ib." bombes de 250kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes2_combo250_txt;
						}
						if($Bombe500_nbr2 >0)
						{
							for($ib=1;$ib<=$Bombe500_nbr2;$ib++)
							{
								$ibn='500_'.$ib;
								$bombe_kg=$ib*500;
								$bombes2_combo500_txt.="<option value='".$ibn."'>".$ib." bombes de 500kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes2_combo500_txt;
						}
						if($Bombe1000_nbr2 >0)
						{
							for($ib=1;$ib<=$Bombe1000_nbr2;$ib++)
							{
								$ibn='1000_'.$ib;
								$bombe_kg=$ib*1000;
								$bombes2_combo1000_txt.="<option value='".$ibn."'>".$ib." bombes de 1000kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes2_combo1000_txt;
						}
						if($Torpilles2 >0)
						{
							for($ib=1;$ib<=$Torpilles2;$ib++)
							{
								$ibn='800_'.$ib;
								$bombe_kg=$ib*800;
								$bombes2_combo800_txt.="<option value='".$ibn."'>".$ib." torpille(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes2_combo800_txt;
						}
						if($Mines2 >0)
						{
							for($ib=1;$ib<=$Mines2;$ib++)
							{
								$ibm='300_'.$ib;
								$bombe_kg=$ib*300;
								$bombes2_combo300_txt.="<option value='".$ibm."'>".$ib." charge(s)(".$bombe_kg."kg)</option>";
								$ibn='400_'.$ib;
								$bombe_kg=$ib*400;
								$bombes2_combo400_txt.="<option value='".$ibn."'>".$ib." mine(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes2_combo300_txt;
							echo $bombes2_combo400_txt;
						}
						if($Rockets2 >0)
						{
							for($ib=1;$ib<=$Rockets2;$ib++)
							{
								$ibn='80_'.$ib;
								$bombe_kg=$ib*80;
								$bombes2_combo80_txt.="<option value='".$ibn."'>".$ib." rocket(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes2_combo80_txt;
						}
						if($Baby2 >0)
							echo "<option value='350_1'>Réservoir largable de ".$Baby2."L</option>";
						if($Camera_low2 == 25){?>
						<option value='25_1'>1 Caméra portative (Basse altitude uniquement)</option>
						<?}if($Camera_low2 == 26 or $Camera_high2 == 26){?>
						<option value='26_1'>1 Caméra fixe (Moyenne altitude)</option>
						<?}if($Camera_high2 == 27){?>
						<option value='27_1'>1 Caméra fixe (Haute altitude)</option>
						<?}if($Bombe125_nbr2 >0 or $Bombe250_nbr2 >0 or $Bombe500_nbr2 >0){?>	
						<option value='30_10'>10 fusées éclairantes (300 kg)</option>
						<?}?>
			</select></td></tr>
			<tr><th>3</th><td><? echo $Munitions3_txt;?></td><td align="left">
					<select name="muns3" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Standard</option>
						<option value='2'>AP (Perforant, chance d'ignorer un blindage lourd à courte portée)</option>
						<option value='3'>HE (Explosif, dégâts supplémentaires importants contre cibles non blindées)</option>
						<option value='4'>Incendiaire (chance de dégâts supplémentaires contre cibles non blindées)</option>
						<option value='5'>APHE (Chance d'ignorer un blindage léger. Dégâts supplémentaires importants si blindage perforé)</option>
						<option value='6'>API (Chance d'ignorer un blindage léger. Dégâts supplémentaires dans la durée si blindage perforé)</option>
				</select></td>
				<td><?echo $Avion3_Bombes_txt.' - '.GetBombeT($Avion3_BombeT);?></td>
				<td align="left"><select name="bombe_type3" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Standard</option>
						<option value='2'>Incendiaire (Efficace contre les petits bâtiments et les véhicules non blindés)</option>
						<option value='3'>Anti-personnel(Efficace contre les soldats et les chevaux)</option>
						<option value='4'>Anti-tank (Efficace contre les véhicules)</option>
						<option value='5'>Anti-navire (Efficace contre les navires)</option>
						<option value='6'>Anti-bâtiment (Efficace contre les bâtiments)</option>
						<option value='7'>Anti-piste (Efficace contre les pistes)</option>
				</select></td>
				<td align="left"><select name="bombes3" class='form-control' style="width: 200px">
						<option value='0'>Ne rien changer</option>
						<option value='1'>Aucune</option>
						<?if($Bombe50_nbr3 >0)
						{
							for($ib=1;$ib<=$Bombe50_nbr3;$ib++)
							{
								$ibn='50_'.$ib;
								$bombe_kg=$ib*50;
								$bombes_combo50_txt.="<option value='".$ibn."'>".$ib." bombes de 50kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes_combo50_txt;
						}
						if($Bombe125_nbr3 >0)
						{					
							for($ib=1;$ib<=$Bombe125_nbr3;$ib++)
							{
								$ibn='125_'.$ib;
								$bombe_kg=$ib*125;
								$bombes_combo125_txt.="<option value='".$ibn."'>".$ib." bombes de 125kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes_combo125_txt;
						}
						if($Bombe250_nbr3 >0)
						{
							for($ib=1;$ib<=$Bombe250_nbr3;$ib++)
							{
								$ibn='250_'.$ib;
								$bombe_kg=$ib*250;
								$bombes_combo250_txt.="<option value='".$ibn."'>".$ib." bombes de 250kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes_combo250_txt;
						}
						if($Bombe500_nbr3 >0)
						{
							for($ib=1;$ib<=$Bombe500_nbr3;$ib++)
							{
								$ibn='500_'.$ib;
								$bombe_kg=$ib*500;
								$bombes_combo500_txt.="<option value='".$ibn."'>".$ib." bombes de 500kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes_combo500_txt;
						}
						if($Bombe1000_nbr3 >0)
						{
							for($ib=1;$ib<=$Bombe1000_nbr3;$ib++)
							{
								$ibn='1000_'.$ib;
								$bombe_kg=$ib*1000;
								$bombes_combo1000_txt.="<option value='".$ibn."'>".$ib." bombes de 1000kg(".$bombe_kg."kg)</option>";
							}
							echo $bombes_combo1000_txt;
						}
						if($Torpilles3 >0)
						{
							for($ib=1;$ib<=$Torpilles3;$ib++)
							{
								$ibn='800_'.$ib;
								$bombe_kg=$ib*800;
								$bombes_combo800_txt.="<option value='".$ibn."'>".$ib." torpille(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes_combo800_txt;
						}
						if($Mines3 >0)
						{
							for($ib=1;$ib<=$Mines3;$ib++)
							{
								$ibm='300_'.$ib;
								$bombe_kg=$ib*300;
								$bombes_combo300_txt.="<option value='".$ibm."'>".$ib." charge(s)(".$bombe_kg."kg)</option>";
								$ibn='400_'.$ib;
								$bombe_kg=$ib*400;
								$bombes_combo400_txt.="<option value='".$ibn."'>".$ib." mine(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes_combo300_txt;
							echo $bombes_combo400_txt;
						}
						if($Rockets3 >0)
						{
							for($ib=1;$ib<=$Rockets3;$ib++)
							{
								$ibn='80_'.$ib;
								$bombe_kg=$ib*80;
								$bombes_combo80_txt.="<option value='".$ibn."'>".$ib." rocket(s)(".$bombe_kg."kg)</option>";
							}
							echo $bombes_combo80_txt;
						}
						if($Baby3 >0)
							echo "<option value='350_1'>Réservoir largable de ".$Baby3."L</option>";
						if($Camera_low3 ==25){?>
						<option value='25_1'>1 Caméra portative (Basse altitude uniquement)</option>
						<?}if($Camera_low3 ==26 or $Camera_high3 ==26){?>
						<option value='26_1'>1 Caméra fixe (Moyenne altitude)</option>
						<?}if($Camera_high3 ==27){?>
						<option value='27_1'>1 Caméra fixe (Haute altitude)</option>
						<?}if($Bombe125_nbr3 >0 or $Bombe250_nbr3 >0 or $Bombe500_nbr3 >0){?>	
						<option value='30_10'>10 fusées éclairantes (300 kg)</option>
						<?}?>
					</select>
			</td></tr></table>
			<?
					}
				}
				/*else{
					echo "<p>Il est nécessaire d'échanger vos CT contre des CTM pour pouvoir bénéficier des services de gestion d'unité<br>Pour ce faire, il suffit de vous mettre à disposition de l'unité en cliquant <a class='btn btn-default' href='index.php?view=escadrille'>ici</a></p>";
				}*/
			}
			/*elseif($Tab =="Pilotes" and $Acces_Officier)
			{?>
			<h2>Gestion des pilotes</h2>
			<table class='table table-striped'>
			<tr><th>Recrutement des élèves-pilotes</th>
				<td><select name='Recrut' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='1'>Oui</option>
						<option value='2'>Non</option>
				</select></td>
			<td><?if($Recrutement ==1){echo "Actuellement : Oui";}else{echo "Actuellement : Non";}?></td></tr>
			<tr><th>Fixer le ratio pour le recrutement</th>
				<td><select name='Ratio' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='0.20'>0.20</option>
						<option value='0.30'>0.30</option>
						<option value='0.40'>0.40</option>
						<option value='0.50'>0.50</option>
						<option value='0.60'>0.60</option>
						<option value='0.70'>0.70</option>
						<option value='0.80'>0.80</option>
						<option value='0.90'>0.90</option>
						<option value='1.00'>1.00</option>
						<option value='99.99'>Pas de limite</option>
				</select></td>
			<td><?echo "Actuellement : ".$Ratio;?></td></tr>
			<?if(!$Do_Note){?>
			<tr><th title="Ce pilote sera noté selon votre évaluation">Noter un pilote</th>
				<td><select name='Pilote_Note' class='form-control' style='width: 200px'>
						<option value="0" selected>Personne</option>
						<?
							$con=dbconnecti();
							$query="SELECT DISTINCT ID,Nom FROM Pilote WHERE Unit='$Unite' AND Actif=0 AND ID<>'$PlayerID' ORDER BY Nom ASC";
							$result=mysqli_query($con, $query);
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
								{
									?>
									 <option value="<? echo $data['ID'];?>"> <? echo $data['Nom'];?> </option>
									<?
								}
								mysqli_free_result($result);
							}
						?>
				</select></td>
				<td><select name='Note_Pilote' class='form-control' style='width: 200px'>
					<option value='0' selected>Pas de note</option>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
				</select></td>
			</tr><?}?>
			<tr><th title="Ce pilote sera muté dans une unité de réserve">Muter un pilote inactif vers l'unité école</th>
				<td><select name='Mutation_Pilote' class='form-control' style='width: 200px'>
						<option value="0" selected>Personne</option>
						<?
							$Dateref=date("Y-m-d");
							$con=dbconnecti();
							$query="SELECT DISTINCT ID,Nom FROM Pilote WHERE Unit='$Unite' AND ID<>'$PlayerID' AND Credits_date<>'$Dateref' ORDER BY Nom ASC";
							$result=mysqli_query($con,$query);
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
								{
									?>
									 <option value="<? echo $data['ID'];?>"> <? echo $data['Nom'];?> </option>
									<?
								}
								mysqli_free_result($result);
								unset($data);
							}
						?>
				</select></td>
			</tr>
			<?
			}*/
			elseif($Tab =='Base')
			{
				if($Pool_CT >=$CT_5B)
				{
					echo '<h2>Gestion de la base</h2>';
					echo "<table class='table table-striped'><tr><td>";
					if($QualitePiste >0 and ($Meteo ==-50 or $Meteo ==-135))
					{
						if($Pool_CT >=$CT_10B)
							echo "<Input type='Radio' name='Action' value='37'><img src='/images/CT".$CT_10B.".png' title='Montant en Crédits Temps que nécessite cette action'>- Faire déneiger la piste.<br>";
						else
							echo "<Input type='Radio' name='Action' value='37' disabled><img src='/images/CT".$CT_10B.".png' title='Montant en Crédits Temps que nécessite cette action'>- Faire déneiger la piste.<br>";
					}
					if($QualitePiste <100 and $Zone !=6 and $Unit_Type !=10 and $Unit_Type !=12)
					{
						if($QualitePiste >0)
						{
							if($Pool_CT >=$CT_5)
								echo "<Input type='Radio' name='Action' value='10'><img src='/images/CT".$CT_5.".png' title='Montant en Crédits Temps que nécessite cette action'>- Faire réparer partiellement la piste.<br>";
							else
								echo "<Input type='Radio' name='Action' value='10' disabled><img src='/images/CT".$CT_5.".png' title='Montant en Crédits Temps que nécessite cette action'>- Faire réparer partiellement la piste.<br>";
						}
						if($QualitePiste <50)
						{
							echo "<input type='hidden' name='Cr' value='".$Credits_Piste."'>";
							if($Pool_CT >=$Credits_Piste)
								echo "<Input type='Radio' name='Action' value='21'><img src='/images/CT".$Credits_Piste.".png' title='Montant en Crédits Temps que nécessite cette action'>- Faire réparer totalement la piste.<br>";
							else
								echo "<Input type='Radio' name='Action' value='21' disabled><img src='/images/CT".$Credits_Piste.".png' title='Montant en Crédits Temps que nécessite cette action'>- Faire réparer totalement la piste.<br>";
						}
					}
					if($Tour <100 and $Zone !=6 and $Unit_Type !=10 and $Unit_Type !=12)
					{
						if($Pool_CT >=$CT_10B)
							echo "<Input type='Radio' name='Action' value='35'><img src='/images/CT".$CT_10B.".png' title='Montant en Crédits Temps Mutualisés que nécessite cette action'>- Faire réparer la tour.<br>";
						else
							echo "<Input type='Radio' name='Action' value='35' disabled><img src='/images/CT".$CT_10B.".png' title='Montant en Crédits Temps Mutualisés que nécessite cette action'>- Faire réparer la tour.<br>";
					}
					if($Camouflage <100 and $Zone !=6 and $Unit_Type !=10 and $Unit_Type !=12)
					{
						if($Pool_CT >=$CT_5B)
							echo "<Input type='Radio' name='Action' value='13'><img src='/images/CT".$CT_5B.".png' title='Montant en Crédits Temps Mutualisés que nécessite cette action'>- Améliorer le camouflage de la base.<br>";
						else
							echo "<Input type='Radio' name='Action' value='13' disabled><img src='/images/CT".$CT_5B.".png' title='Montant en Crédits Temps Mutualisés que nécessite cette action'>- Améliorer le camouflage de la base.<br>";
					}
					if($Station_Meteo <10)
					{
						if($Pool_CT >=$CT_10B)
							echo "<Input type='Radio' name='Action' value='20'><img src='/images/CT".$CT_10B.".png' title='Montant en Crédits Temps Mutualisés que nécessite cette action'>- Améliorer la station météo de l'unité.<br>";
						else
							echo "<Input type='Radio' name='Action' value='20' disabled><img src='/images/CT".$CT_10B.".png' title='Montant en Crédits Temps Mutualisés que nécessite cette action'>- Améliorer la station météo de l'unité.<br>";
					}
					echo '</td></tr></table>';
				}
				/*else{
					echo "Il est nécessaire d'échanger vos CT contre des CTM pour pouvoir bénéficier des services de gestion d'unité<br>Pour ce faire, il suffit de vous mettre à disposition de l'unité en cliquant <a class='btn btn-default' href='index.php?view=escadrille'>ici</a>";
				}*/
			}
			elseif($Tab =='Cdt' and $Acces_Cdt)
			{
				echo '<h2>Privilèges du Commandant</h2>';
				echo "<table class='table table-striped'>
                <tr><th>Mutation des Pilotes IA</th>
				<td><select name='Recrut' class='form-control' style='width: 200px'>
						<option value='0' selected>Ne rien changer</option>
						<option value='1'>Oui</option>
						<option value='2'>Non</option>
				</select></td></tr>";
				if($Pool_CT >=$CT_10 and $Unit_Type !=10 and $Unit_Type !=12)
				{
					//echo "<tr><th><img src='/images/CTM10.png' title='Montant en Crédits Temps Mutualisés que nécessite cette action'> Donner l'ordre de saboter la piste</th><td>
					echo "<tr><th><img src='/images/CT".$CT_10.".png' title='Montant en Crédits Temps que nécessite cette action'> Donner l'ordre de saboter la piste</th><td>
					<Input type='Radio' name='Sabotage_Piste' value='0' title='Pas un peu fou non?' checked>- Non
					<Input type='Radio' name='Sabotage_Piste' value='1' title='Politique de la Terre Brûlée!'>- Oui<br>
					</td></tr>";
				}
				/*echo "<tr><th>Interdire tout décollage</th><td>
					<Input type='Radio' name='Au_Sol' value='0' title='Les ordres sont les ordres!' checked>- Ne rien changer (actuellement <b>".$Au_Sol."</b>)<br>
					<Input type='Radio' name='Au_Sol' value='1' title='Qui va réparer ma piste et mes avions?'>- Oui
					<Input type='Radio' name='Au_Sol' value='2' title='Un pilote est fait pour voler!'>- Non
					</td></tr>
					<tr><th>Donner ordre à l'unité d'ignorer la mission d'Etat-Major en cours</th><td>
					<Input type='Radio' name='Annuler_EM' value='0' title='Les ordres sont les ordres!' checked>- Ne rien changer (actuellement <b>".$NoEM."</b>)<br>
					<Input type='Radio' name='Annuler_EM' value='1' title='Je refuse de sacrifier mes hommes pour obéir à cet ordre inepte!'>- Oui
					<Input type='Radio' name='Annuler_EM' value='2' title='Les ordres sont les ordres!'>- Non<br>
					</td></tr>";*/
				if($Pool_CT >=$CT_30U) //($Pool_CT >=40 and $Unit_Type !=8 and $Unit_Type !=10 and $Unit_Type !=12)
				{
					//echo "<tr><th><img src='/images/CTM40.png' title='Montant en Crédits Temps Mutualisés que nécessite cette action'> Donner l'ordre de déménager l'escadrille</th><td>					
					if($Unit_Type ==9)
					{
						$query="SELECT ID,Nom FROM Lieu WHERE Flag='$country' AND Zone<>6 AND ID<>'$Base' AND Port_Ori >0 AND LongPiste >=1400 ORDER BY Nom ASC";
						$LongPiste_mini=1400;
					}
					else
					{
						$Lands=GetAllies($Date_Campagne);
						if(IsAxe($country))
							$pays_allies=$Lands[1];
						else
							$pays_allies=$Lands[0];
						$LongPiste_mini=GetLongPisteMin($Unit_Type,$Avion1,$Avion2,$Avion3);
						$query="SELECT ID,Nom,Longitude,Latitude FROM Lieu WHERE Flag IN (".$pays_allies.")
						AND Zone<>'6' AND ID<>'$Base' AND QualitePiste >49 AND Tour >49 AND LongPiste >='$LongPiste_mini' AND ((SELECT COUNT(*) FROM Unit WHERE Base=Lieu.ID AND Etat=1)<(ValeurStrat+2)) ORDER BY Nom ASC";
					}
					$con=dbconnecti();
					$resultb=mysqli_query($con,"SELECT Latitude,Longitude FROM Lieu WHERE ID='$Base'");
					$result=mysqli_query($con,$query);
					mysqli_close($con);
					if($resultb)
					{
						while($datab=mysqli_fetch_array($resultb))
						{
							$Lat_base=$datab['Latitude'];
							$Long_base=$datab['Longitude'];
						}
						mysqli_free_result($resultb);					
					}
					if($result)
					{
						if($Unite_Type ==6 or $Unite_Type ==9 or $Unite_Type ==11)
							$Limite=500;
						else
							$Limite=300;
						if($Front ==3)
							$Limite*=2;
						while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
						{
							$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data[2],$data[3]);
							if($Dist[0] <$Limite)
								$dest_move.="<option value='".$data[0]."'>".$data[1]."</option>";
						}
						unset($data);
					}
					echo "<tr><th><img src='/images/CT".$CT_30U.".png' title='Montant en Crédits Temps que nécessite cette action'> Donner l'ordre de déménager l'escadrille</th><td>";
					if($dest_move)
						echo "<select name='Transfer_esc' class='form-control' style='width: 150px'>
						<option value='".$Base."' selected>Rester ici</option>".$dest_move."</select>
						<Input type='Radio' name='Transfer_val' value='0' title='J\'y suis j\'y reste!' checked>- Non
						<Input type='Radio' name='Transfer_val' value='1' title='Barrons-nous vite fait!'>- Oui<br>
						</td></tr>";
					else
						echo 'Aucune destination possible dans un rayon de '.$Limite.'km depuis votre base. Votre unité nécessite une piste de '.$LongPiste_mini.'m et des infrastructures en bon état.<br>Contactez votre planificateur stratégique pour lui demander un déplacement longue distance</td></tr>';
				}
				else
					echo "<div class='alert alert-info'><img src='/images/CT".$CT_30U.".png' title='Montant en Crédits Temps que nécessite cette action'> sont nécessaires pour déplacer l'escadrille</div>";
			}
			echo '</table>';
			if($Pool_CT >=1 or ($Tab =='Pilotes' and $Acces_Officier))
				echo "<input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			else
			    echo '</form>';
		}
		else
		{
			include_once('./menu_escadrille.php');
			PrintNoAccessPil($country,1,2);
		}
	}
	else
	{
		$titre='MIA';
		$mes='<h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>';
		$img='<img src="images/unites'.$country.'.jpg">';
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
include_once('./index.php');