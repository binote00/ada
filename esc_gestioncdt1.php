<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{	
	include_once('./jfv_include.inc.php');
	$Unite=Insec($_POST['Unite']);
	if($Unite >0)
	{
		/*$Avion1_Limite=Insec($_POST['Avion1_Limite']);
		$Avion2_Limite=Insec($_POST['Avion2_Limite']);
		$Avion3_Limite=Insec($_POST['Avion3_Limite']);
		$Avion1_XP=Insec($_POST['Avion1_exp']);
		$Avion2_XP=Insec($_POST['Avion2_exp']);
		$Avion3_XP=Insec($_POST['Avion3_exp']);*/
		$Avions_Transfer=Insec($_POST['Avion_Transfer']);
		$Staffel_ori=Insec($_POST['Staffel_o']);
		$Staffel_dest=Insec($_POST['Staffel_d']);
		$bombes1=Insec($_POST['bombes1']);
		$bombes2=Insec($_POST['bombes2']);
		$bombes3=Insec($_POST['bombes3']);
		$bombe_type1=Insec($_POST['bombe_type1']);
		$bombe_type2=Insec($_POST['bombe_type2']);
		$bombe_type3=Insec($_POST['bombe_type3']);
		$muns1=Insec($_POST['muns1']);
		$muns2=Insec($_POST['muns2']);
		$muns3=Insec($_POST['muns3']);
		$fret1=Insec($_POST['fret1']);
		$fret2=Insec($_POST['fret2']);
		$fret3=Insec($_POST['fret3']);
		$Mutation_Pilote=Insec($_POST['Mutation_Pilote']);
		$Sabotage_Piste=Insec($_POST['Sabotage_Piste']);
		$Annuler_EM=Insec($_POST['Annuler_EM']);
		$Recrut=Insec($_POST['Recrut']);
		$Transfer_esc=Insec($_POST['Transfer_esc']);
		$Transfer_val=Insec($_POST['Transfer_val']);
		$Choix=Insec($_POST['Action']);
		$Credits_Piste=Insec($_POST['Cr']);
		$Staffelc=Insec($_POST['sqnc']);
		$Avionc=Insec($_POST['avionc']);
		$CT_30=Insec($_POST['CT30']);
		$CT_10=Insec($_POST['CT10']);
		$CT_5=Insec($_POST['CT5']);
		$CT_10B=Insec($_POST['CT10B']);
		$CT_5B=Insec($_POST['CT5B']);
		$CT_5W=Insec($_POST['CT5W']);
		$CT_30U=Insec($_POST['CT30U']);
		$country=$_SESSION['country'];
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Type,Commandant,Officier_Adjoint,Officier_Technique,Base,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Mission_Lieu,Mission_Type,Mission_IA,
		Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'") or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : escgcdt1-unit');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite_Type=$data['Type'];
				$Base=$data['Base'];
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Officier_Technique=$data['Officier_Technique'];
				$Avion1=$data['Avion1'];
				$Avion2=$data['Avion2'];
				$Avion3=$data['Avion3'];
				$Avion1_Nbr=$data['Avion1_Nbr'];
				$Avion2_Nbr=$data['Avion2_Nbr'];
				$Avion3_Nbr=$data['Avion3_Nbr'];
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
				$Mission_Lieu=$data['Mission_Lieu'];
				$Mission_Type=$data['Mission_Type'];
				$Mission_IA=$data['Mission_IA'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($PlayerID >0 and ($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique))
		{
			$Pool_CT=0;
			include_once('./jfv_txt.inc.php');
			include_once('./jfv_msg.inc.php');
			$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
			$Personnel=array_count_values($Pers);
			$Pool_CT_ori=GetData("Pilote","ID",$PlayerID,"Credits");			
			if($Pool_CT_ori >=$CT_30 and $Staffelc and $Avionc >0)
			{
				$Pool_CT+=$CT_30;
				$Squadc="Avion".$Staffelc;
				SetData("Unit",$Squadc,$Avionc,"ID",$Unite);
				SetData("Unit","Avion".$Staffelc."_Nbr",1,"ID",$Unite);
				SetData("Unit","Avion".$Staffelc."_Bombe_Nbr",0,"ID",$Unite);
				SetData("Unit","Avion".$Staffelc."_Bombe",0,"ID",$Unite);
				//UpdateCarac($PlayerID,"Commandement",1);
				//UpdateCarac($PlayerID,"Gestion",3);
				$mes="<p>L'unité a été équipée du nouveau modèle d'avion.</p>";
			}		
			if($Recrut)
				SetData("Unit","Recrutement",$Recrut,"ID",$Unite);		
			if($Choix >0)
			{
				switch($Choix)
				{
					case 10:
						if($Pool_CT_ori >=$CT_5)
						{
							if(GetData("Lieu","ID",$Base,"QualitePiste") <96)
								UpdateData("Lieu","QualitePiste",5,"ID",$Base,100);
							else
								SetData("Lieu","QualitePiste",100,"ID",$Base);
							$Pool_CT+=$CT_5;
						}
						else
							$fournitures=0;
						$img_txt="gestion_piste".$country;
					break;
					case 13:
						if($Pool_CT_ori >=$CT_5B)
						{
							$Repa=5+($Personnel[12]*5);
							UpdateData("Lieu","Camouflage",$Repa,"ID",$Base,100);
							SetData("Unit","Recce",0,"ID",$Unite);
							//AddEvent("Avion",107,10,$PlayerID,$Unite,$Base);
							$Pool_CT+=$CT_5B;
						}
						$img_txt="gestion_camouflage";
					break;
					case 20:
						if($Pool_CT_ori >=$CT_10B)
						{
							UpdateData("Unit","Station_Meteo",1,"ID",$Unite,10);
							$Pool_CT+=$CT_10B;
						}
						$img_txt="gestion_meteo";
					break;
					case 21:
						if($Pool_CT_ori >=$Credits_Piste)
						{
							SetData("Lieu","QualitePiste",100,"ID",$Base);
							$Pool_CT=$Credits_Piste;
						}
						$img_txt="gestion_piste".$country;
					break;
					case 35:
						if($Pool_CT_ori >=$CT_10B)
						{
							if(GetData("Lieu","ID",$Base,"Tour") <96)
								UpdateData("Lieu","Tour",5,"ID",$Base,100);
							else
								SetData("Lieu","Tour",100,"ID",$Base);
							$Pool_CT+=$CT_10B;
						}
						$img_txt="gestion_piste".$country;
					break;
					case 37:
						if($Pool_CT_ori >=$CT_10B)
						{
							UpdateData("Lieu","Meteo",40,"ID",$Base);
							$Pool_CT+=$CT_10B;
						}
						$img_txt="gestion_piste".$country;
					break;
				}
			}		
			if($Transfer_val and $Transfer_esc >0 and $Transfer_esc !=$Base)
			{
				if($Pool_CT_ori >=$CT_30U)
				{
					$con=dbconnecti();
					$ValStrat=mysqli_result(mysqli_query($con,"SELECT ValeurStrat FROM Lieu WHERE ID='$Transfer_esc'"),0);
					$New_Base_Units=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit WHERE Base='$Transfer_esc'"),0);
					mysqli_close($con);
					if($New_Base_Units <($ValStrat+2))
					{
						$Pool_CT+=$CT_30U;
						SetData("Unit","Base",$Transfer_esc,"ID",$Unite);
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Flak SET Lieu='$Transfer_esc' WHERE Unit='$Unite'");
						$reset_tr=mysqli_query($con,"DELETE Flak FROM Flak LEFT JOIN Armes ON Flak.DCA_ID=Armes.ID WHERE Flak.Unit='$Unite' AND Armes.Transport=0");
						$reset_l=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE ID='$Transfer_esc'");
						$reset2=mysqli_query($con,"UPDATE Pilote_IA SET Avion=0,Cible=0,Couverture=0,Couverture_Nuit=0,Escorte=0,Alt=0,Task=0 WHERE Unit='$Unite'");
						mysqli_close($con);
						AddEvent("Avion",41,1,$PlayerID,$Unite,$Transfer_esc);
						$mes="<div class='alert alert-success'>Le déménagement de l'unité a été réalisé avec succès!</div>";
					}
					else
						$mes="<div class='alert alert-danger'>L'aérodrome est déjà complet! Vous ne pouvez y déplacer votre unité.</div>";
				}
				else
					$mes="<div class='alert alert-danger'>Le déménagement de l'unité n'a pas pu être réalisé par manque de temps.</div>";
			}		
			if($Sabotage_Piste and $Pool_CT_ori >=$CT_10)
			{
				$Piste=GetData("Lieu","ID",$Base,"QualitePiste");
				if($Piste >50)
					SetData("Lieu","QualitePiste",50,"ID",$Base);
				$Pool_CT+=$CT_10;
			}		
			/*if($Annuler_EM ==1)
				SetData("Unit","NoEM",1,"ID",$Unite);
			elseif($Annuler_EM ==2)
				SetData("Unit","NoEM",0,"ID",$Unite);*/
			if($Avions_Transfer and $Pool_CT_ori >=$CT_5W)
			{
				$Max_Flight=GetMaxFlight($Unite_Type,0,0);
				$Tr_St=false;
				switch($Staffel_dest)
				{
					case 1:
						if($Staffel_ori ==2 and $Avion1 == $Avion2 and $Avion2_Nbr >= $Avions_Transfer and $Avion1_Nbr + $Avions_Transfer <$Max_Flight)
							$Tr_St=true;
						elseif($Staffel_ori ==3 and $Avion1 == $Avion3 and $Avion3_Nbr >= $Avions_Transfer and $Avion1_Nbr + $Avions_Transfer <$Max_Flight)
							$Tr_St=true;
					break;
					case 2:
						if($Staffel_ori ==1 and $Avion1 == $Avion2 and $Avion1_Nbr >= $Avions_Transfer and $Avion2_Nbr + $Avions_Transfer <$Max_Flight)
							$Tr_St=true;
						elseif($Staffel_ori ==3 and $Avion2 == $Avion3 and $Avion3_Nbr >= $Avions_Transfer and $Avion2_Nbr + $Avions_Transfer <$Max_Flight)
							$Tr_St=true;
					break;
					case 3:
						if($Staffel_ori ==2 and $Avion3 == $Avion2 and $Avion2_Nbr >= $Avions_Transfer and $Avion3_Nbr + $Avions_Transfer <$Max_Flight)
							$Tr_St=true;
						elseif($Staffel_ori ==1 and $Avion1 == $Avion3 and $Avion1_Nbr >= $Avions_Transfer and $Avion3_Nbr + $Avions_Transfer <$Max_Flight)
							$Tr_St=true;
					break;
				}
				if($Tr_St ==true)
				{
					UpdateData("Unit","Avion".$Staffel_dest."_Nbr",$Avions_Transfer,"ID",$Unite);
					UpdateData("Unit","Avion".$Staffel_ori."_Nbr",-$Avions_Transfer,"ID",$Unite);
					$Pool_CT+=$CT_5W;
					AddEvent("Avion",137,$Avion1,$Staffel_ori,$Unite,$Staffel_dest,$Avions_Transfer);
				}
				else
					$mes="<p>Ordre annulé. Soit vous ne disposez pas de suffisamment d'avions de ce modèle à transférer,soit l'unité de destination a atteint sa dotation maximale.<br>Les avions à transférer doivent être du même modèle.</p>";
			}		
			if($Pool_CT_ori >=$CT_5W)
			{
				include_once('./jfv_avions.inc.php');
				if($muns1)
					SetData("Unit","Avion1_Mun1",$muns1-1,"ID",$Unite);
				if($muns2)
					SetData("Unit","Avion2_Mun1",$muns2-1,"ID",$Unite);
				if($muns3)
					SetData("Unit","Avion3_Mun1",$muns3-1,"ID",$Unite);
				if($bombe_type1)
				{
					$bombe_type1-=1;
					SetData("Unit","Avion1_BombeT",$bombe_type1,"ID",$Unite);
				}
				if($bombe_type2)
				{
					$bombe_type2-=1;
					SetData("Unit","Avion2_BombeT",$bombe_type2,"ID",$Unite);
				}
				if($bombe_type3)
				{
					$bombe_type3-=1;
					SetData("Unit","Avion3_BombeT",$bombe_type3,"ID",$Unite);
				}
				if($bombes1 !=0)
				{
					//Baby
					if($bombes1 ==1)
					{
						SetData("Unit","Avion1_Bombe",0,"ID",$Unite);
						SetData("Unit","Avion1_Bombe_Nbr",0,"ID",$Unite);
					}
					else
					{
						$Bombe1_new=strstr($bombes1,"_",true);
						$Bombe1_new_nbr=substr($bombes1,strpos($bombes1,"_")+1);
						SetData("Unit","Avion1_Bombe",$Bombe1_new,"ID",$Unite);
						SetData("Unit","Avion1_Bombe_Nbr",$Bombe1_new_nbr,"ID",$Unite);
					}
				}
				if($bombes2 !=0)
				{
					if($bombes2 ==1)
					{
						SetData("Unit","Avion2_Bombe",0,"ID",$Unite);
						SetData("Unit","Avion2_Bombe_Nbr",0,"ID",$Unite);
					}
					else
					{
						$Bombe2_new=strstr($bombes2,"_",true);
						$Bombe2_new_nbr=substr($bombes2,strpos($bombes2,"_")+1);
						SetData("Unit","Avion2_Bombe",$Bombe2_new,"ID",$Unite);
						SetData("Unit","Avion2_Bombe_Nbr",$Bombe2_new_nbr,"ID",$Unite);
					}
				}
				if($bombes3 !=0)
				{
					if($bombes3 ==1)
					{
						SetData("Unit","Avion3_Bombe",0,"ID",$Unite);
						SetData("Unit","Avion3_Bombe_Nbr",0,"ID",$Unite);
					}
					else
					{
						$Bombe3_new=strstr($bombes3,"_",true);
						$Bombe3_new_nbr=substr($bombes3,strpos($bombes3,"_")+1);
						SetData("Unit","Avion3_Bombe",$Bombe3_new,"ID",$Unite);
						SetData("Unit","Avion3_Bombe_Nbr",$Bombe3_new_nbr,"ID",$Unite);
					}
				}
				if($fret1)
				{
					$Array_Mod1=GetAmeliorations($Avion1);		
					$Fret_mun81=$Array_Mod1[22];
					$Fret_mun131=$Array_Mod1[23];
					$Fret_mun201=$Array_Mod1[24];
					$Fret_mun301=$Array_Mod1[36];
					$Fret_mun401=$Array_Mod1[37];
					$Fret_871=$Array_Mod1[25];
					$Fret_1001=$Array_Mod1[26];
					$Fret_501=$Array_Mod1[27];
					$Fret_1251=$Array_Mod1[28];
					$Fret_2501=$Array_Mod1[29];
					$Fret_5001=$Array_Mod1[30];
					$Fret_para1=$Array_Mod1[31];
					switch($fret1)
					{
						case 1:
							$Bombe_new=0;
							$Bombe_new_nbr=0;
						break;
						case 2:
							$Bombe_new=50;
							$Bombe_new_nbr=$Fret_501;
						break;
						case 3:
							$Bombe_new=125;
							$Bombe_new_nbr=$Fret_1251;
						break;
						case 4:
							$Bombe_new=250;
							$Bombe_new_nbr=$Fret_2501;
						break;
						case 5:
							$Bombe_new=500;
							$Bombe_new_nbr=$Fret_5001;
						break;
						case 6:
							$Bombe_new=50000;
							$Bombe_new_nbr=$Fret_mun81;
						break;
						case 7:
							$Bombe_new=15000;
							$Bombe_new_nbr=$Fret_mun131;
						break;
						case 8:
							$Bombe_new=5000;
							$Bombe_new_nbr=$Fret_mun201;
						break;
						case 9:
							$Bombe_new=1200;
							$Bombe_new_nbr=$Fret_871;
						break;
						case 10:
							$Bombe_new=1100;
							$Bombe_new_nbr=$Fret_1001;
						break;
						case 11:
							$Bombe_new=100;
							$Bombe_new_nbr=$Fret_para1;
						break;
						case 12:
							$Bombe_new=3000;
							$Bombe_new_nbr=$Fret_mun301;
						break;
						case 13:
							$Bombe_new=1500;
							$Bombe_new_nbr=$Fret_mun401;
						break;
					}
					SetData("Unit","Avion1_Bombe",$Bombe_new,"ID",$Unite);
					SetData("Unit","Avion1_Bombe_Nbr",$Bombe_new_nbr,"ID",$Unite);	
				}
				if($fret2)
				{
					$Array_Mod2=GetAmeliorations($Avion2);			
					$Fret_mun82=$Array_Mod2[22];
					$Fret_mun132=$Array_Mod2[23];
					$Fret_mun202=$Array_Mod2[24];
					$Fret_mun302=$Array_Mod2[36];
					$Fret_mun402=$Array_Mod2[37];
					$Fret_872=$Array_Mod2[25];
					$Fret_1002=$Array_Mod2[26];
					$Fret_502=$Array_Mod2[27];
					$Fret_1252=$Array_Mod2[28];
					$Fret_2502=$Array_Mod2[29];
					$Fret_5002=$Array_Mod2[30];
					$Fret_para2=$Array_Mod2[31];		
					switch($fret2)
					{
						case 1:
							$Bombe_new=0;
							$Bombe_new_nbr=0;
						break;
						case 2:
							$Bombe_new=50;
							$Bombe_new_nbr=$Fret_502;
						break;
						case 3:
							$Bombe_new=125;
							$Bombe_new_nbr=$Fret_1252;
						break;
						case 4:
							$Bombe_new=250;
							$Bombe_new_nbr=$Fret_2502;
						break;
						case 5:
							$Bombe_new=500;
							$Bombe_new_nbr=$Fret_5002;
						break;
						case 6:
							$Bombe_new=50000;
							$Bombe_new_nbr=$Fret_mun82;
						break;
						case 7:
							$Bombe_new=15000;
							$Bombe_new_nbr=$Fret_mun132;
						break;
						case 8:
							$Bombe_new=5000;
							$Bombe_new_nbr=$Fret_mun202;
						break;
						case 9:
							$Bombe_new=1200;
							$Bombe_new_nbr=$Fret_872;
						break;
						case 10:
							$Bombe_new=1100;
							$Bombe_new_nbr=$Fret_1002;
						break;
						case 11:
							$Bombe_new=100;
							$Bombe_new_nbr=$Fret_para2;
						break;
						case 12:
							$Bombe_new=3000;
							$Bombe_new_nbr=$Fret_mun302;
						break;
						case 13:
							$Bombe_new=1500;
							$Bombe_new_nbr=$Fret_mun402;
						break;
					}
					SetData("Unit","Avion2_Bombe",$Bombe_new,"ID",$Unite);
					SetData("Unit","Avion2_Bombe_Nbr",$Bombe_new_nbr,"ID",$Unite);
					$img_txt="gestion_bombs".$country;
				}
				if($fret3)
				{
					$Array_Mod3=GetAmeliorations($Avion3);
					$Fret_mun83=$Array_Mod3[22];
					$Fret_mun133=$Array_Mod3[23];
					$Fret_mun203=$Array_Mod3[24];
					$Fret_mun303=$Array_Mod3[36];
					$Fret_mun403=$Array_Mod3[37];
					$Fret_873=$Array_Mod3[25];
					$Fret_1003=$Array_Mod3[26];
					$Fret_503=$Array_Mod3[27];
					$Fret_1253=$Array_Mod3[28];
					$Fret_2503=$Array_Mod3[29];
					$Fret_5003=$Array_Mod3[30];
					$Fret_para3=$Array_Mod3[31];
					switch($fret3)
					{
						case 1:
							$Bombe_new=0;
							$Bombe_new_nbr=0;
						break;
						case 2:
							$Bombe_new=50;
							$Bombe_new_nbr=$Fret_503;
						break;
						case 3:
							$Bombe_new=125;
							$Bombe_new_nbr=$Fret_1253;
						break;
						case 4:
							$Bombe_new=250;
							$Bombe_new_nbr=$Fret_2503;
						break;
						case 5:
							$Bombe_new=500;
							$Bombe_new_nbr=$Fret_5003;
						break;
						case 6:
							$Bombe_new=50000;
							$Bombe_new_nbr=$Fret_mun83;
						break;
						case 7:
							$Bombe_new=15000;
							$Bombe_new_nbr=$Fret_mun133;
						break;
						case 8:
							$Bombe_new=5000;
							$Bombe_new_nbr=$Fret_mun203;
						break;
						case 9:
							$Bombe_new=1200;
							$Bombe_new_nbr=$Fret_873;
						break;
						case 10:
							$Bombe_new=1100;
							$Bombe_new_nbr=$Fret_1003;
						break;
						case 11:
							$Bombe_new=100;
							$Bombe_new_nbr=$Fret_para3;
						break;
						case 12:
							$Bombe_new=3000;
							$Bombe_new_nbr=$Fret_mun303;
						break;
						case 13:
							$Bombe_new=1500;
							$Bombe_new_nbr=$Fret_mun403;
						break;
					}
					SetData("Unit","Avion3_Bombe",$Bombe_new,"ID",$Unite);
					SetData("Unit","Avion3_Bombe_Nbr",$Bombe_new_nbr,"ID",$Unite);
				}
				if($muns1 or $muns2 or $muns3 or $bombe_type1 or $bombe_type2 or $bombe_type3 or $bombes1 or $bombes2 or $bombes3 or $fret1 or $fret2 or $fret3)
					$Pool_CT+=$CT_5W;
			}			
			/*if($Avion1_Limite >0)
				SetData("Unit","Avion1_Limite",$Avion1_Limite,"ID",$Unite);
			if($Avion2_Limite >0)
				SetData("Unit","Avion2_Limite",$Avion2_Limite,"ID",$Unite);
			if($Avion3_Limite >0)
				SetData("Unit","Avion3_Limite",$Avion3_Limite,"ID",$Unite);
			if($Avion1_XP >0)
			{
				if($Avion1_XP ==1)$Avion1_XP=0;
				SetData("Unit","Avion1_XP",$Avion1_XP,"ID",$Unite);
			}
			if($Avion2_XP >0)
			{
				if($Avion2_XP ==1)$Avion2_XP=0;
				SetData("Unit","Avion2_XP",$Avion2_XP,"ID",$Unite);
			}
			if($Avion3_XP >0)
			{
				if($Avion3_XP ==1)$Avion3_XP=0;
				SetData("Unit","Avion3_XP",$Avion3_XP,"ID",$Unite);
			}*/
			if($Mutation_Pilote)
			{
				$Unit_mut=GetTraining($country);
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET Unit='$Unit_mut',Ailier=0,Couverture=0,Escorte=0,Commando=0 WHERE ID='$Mutation_Pilote'");
				if($Commandant ==$Mutation_Pilote)
					$reset2=mysqli_query($con,"UPDATE Unit SET Commandant=NULL WHERE ID='$Unite'");
				elseif($Officier_Adjoint ==$Mutation_Pilote)
					$reset2=mysqli_query($con,"UPDATE Unit SET Officier_Adjoint=NULL WHERE ID='$Unite'");
				elseif($Officier_Technique ==$Mutation_Pilote)
					$reset2=mysqli_query($con,"UPDATE Unit SET Officier_Technique=NULL WHERE ID='$Unite'");
				mysqli_close($con);
				SendMsgOff($Mutation_Pilote,$PlayerID,"Suitre à votre évaluation,vous êtes muté dans une unité de réserve.","Mutation",3,3);
			}
			if($Pool_CT >0 and $Pool_CT_ori >=$Pool_CT)
			{
				UpdateCarac($PlayerID,"Avancement",$Pool_CT);
				//UpdateCarac($PlayerID,"Gestion",$Pool_CT);
				//UpdateCarac($PlayerID,"Commandement",$Pool_CT);
				UpdateData("Pilote","Credits",-$Pool_CT,"ID",$PlayerID);
			}
			if(!$mes)$mes="<p>Vos ordres ont été exécutés!</p>";
			if(!$img)
				$img="<img src='images/transfer_yes".$country.".jpg'>";
			else
				$img="<img src='images/".$img_txt.".jpg'>";
			$menu="<a class='btn btn-default' title='Retour à l'escadrille' href='index.php?view=esc_infos'>Retour à l'escadrille</a>";
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
		include_once('./index.php');
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>