<?php
require_once('./jfv_inc_sessions.php');
/*$time=microtime();
$time=explode(' ',$time);
$time=$time[1] + $time[0];
$start=$time;*/
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$Type_Mission=Insec($_POST['Type']);
$Cr_Mission=Insec($_POST['Crm']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) and $PlayerID >0 AND !empty($_POST))
{
	$i=0;
	$country=$_SESSION['country'];
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_avions.inc.php');
	$_SESSION['Distance']=1;
	$_SESSION['Decollage']=false;
	$_SESSION['Decollage0']=false;
	$_SESSION['naviguer']=false;
	$_SESSION['missiondeux']=false;
	$_SESSION['missiontrois']=false;
	$_SESSION['evader']=false;
	$_SESSION['tirer']=false;
	$_SESSION['finish']=false;
	$_SESSION['objectif']=false;
	$_SESSION['done']=false;
	$_SESSION['cibler']=false;
	$_SESSION['attaquer']=false;
	$_SESSION['Mission_Choose']=false;	
	$_SESSION['Parachute']=false;
	$Chk_Mission=$_SESSION['Cr'];
	$finmission=false;
	$voyage=false;
	$no_test_dist=false;
	$Interdit=false;
	if($Chk_Mission){
		$mes='<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>';
		//mail('binote@hotmail.com','Aube des Aigles: Init Mission F5 (mission1) : '.$PlayerID,'Joueur '.$PlayerID.' (IP '.$_SERVER['REMOTE_ADDR'].') depuis la page '.$_SERVER['HTTP_REFERER'].' a tenté de charger la page '.$_SERVER['REQUEST_URI'].' en utilisant '.$_SERVER['HTTP_USER_AGENT']);
	}		
	$con=dbconnecti();
	$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
	$reset=mysqli_query($con,"UPDATE Pilote SET S_Strike=0,S_Cible=0,S_Escorte=0,S_Escorteb=0,S_Escorte_nbr=0,S_Escorteb_nbr=0,S_Equipage_Nbr=1,S_Leader=0,S_Ailier=0,S_Intercept_nbr=0,enis=0,avion_eni=0,Sandbox=0 WHERE ID='$PlayerID'") 
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-reset');
	$result=mysqli_query($con,"SELECT Unit,Front,Reputation,Courage,Moral,Endurance,Crashs_Jour,Avancement,Missions_Max,Proto,Avion_Perso,S_Blindage,Heure_Mission FROM Pilote WHERE ID='$PlayerID'") 
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-player');
	$resultac=mysqli_query($con,"SELECT Officier,Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
	//mysqli_close($con);
	if($resultac)
	{
		while($dataac=mysqli_fetch_array($resultac,MYSQLI_ASSOC))
		{
			$Officier=$dataac['Officier'];
			$Admin=$dataac['Admin'];
		}
		mysqli_free_result($resultac);
	}
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unite=$data['Unit'];
			$Front=$data['Front'];
			$Reputation=$data['Reputation'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Endurance=$data['Endurance'];
			$Missions_Max=$data['Missions_Max'];
			$Crashs_Jour=$data['Crashs_Jour'];
			$Avancement=$data['Avancement'];
			$Proto=$data['Proto'];
			$Avion_p=$data['Avion_Perso'];
			$S_Blindage=$data['S_Blindage'];
			$Heure_Mission=$data['Heure_Mission'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	$result=mysqli_query($con,"SELECT Commandant,Type,Base,Porte_avions,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Avion1_Bombe,Avion2_Bombe,Avion3_Bombe,Avion1_Bombe_Nbr,Avion2_Bombe_Nbr,Avion3_Bombe_Nbr,
	Mission_Type,Mission_Lieu,Mission_Flight,U_Blindage,Avion1_Mun1,Avion2_Mun1,Avion3_Mun1,Mission_Lieu_D FROM Unit WHERE ID='$Unite'")
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-unit');
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Commandant=$data['Commandant'];
			$Unite_Type=$data['Type'];
			$Base=$data['Base'];
			$Porte_avions=$data['Porte_avions'];
			$AvionT1=$data['Avion1'];
			$AvionT2=$data['Avion2'];
			$AvionT3=$data['Avion3'];
			$AvionT1_Nbr=$data['Avion1_Nbr'];
			$AvionT2_Nbr=$data['Avion2_Nbr'];
			$AvionT3_Nbr=$data['Avion3_Nbr'];
			$Mission_Type=$data['Mission_Type'];
			$Mission_Lieu=$data['Mission_Lieu'];
			$Mission_Flight=$data['Mission_Flight'];
			$Avion1_Bombe=$data['Avion1_Bombe'];
			$Avion1_Bombe_nbr=$data['Avion1_Bombe_Nbr'];
			$Avion2_Bombe=$data['Avion2_Bombe'];
			$Avion2_Bombe_Nbr=$data['Avion2_Bombe_Nbr'];
			$Avion3_Bombe=$data['Avion3_Bombe'];
			$Avion3_Bombe_Nbr=$data['Avion3_Bombe_Nbr'];
			$U_Blindage=$data['U_Blindage'];
			$Avion1_Mun1=$data['Avion1_Mun1'];
			$Avion2_Mun1=$data['Avion2_Mun1'];
			$Avion3_Mun1=$data['Avion3_Mun1'];
			$Mission_Lieu_D=$data['Mission_Lieu_D'];
		}
		mysqli_free_result($result);
		unset($data);
	}	
	$result=mysqli_query($con,"SELECT Nom,Longitude,Latitude,Zone FROM Lieu WHERE ID='$Base'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-base');
	$Avion1_dispos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Avion='$AvionT1' AND Actif=1"),0);
	$Avion2_dispos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Avion='$AvionT2' AND Actif=1"),0);
	$Avion3_dispos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Avion='$AvionT3' AND Actif=1"),0);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Terrain=$data['Nom'];
			$Long_base=$data['Longitude'];
			$Lat_base=$data['Latitude'];
			$Zone=$data['Zone'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	$Grade=GetAvancement($Avancement,$country);	
	//Prerequis
	if($Endurance <1 OR $Courage <1)
	{
		$mes.='<br>Vous n\'êtes pas en état de voler. Prenez du repos!';
		$img="<img src='images/free".$country.".jpg' alt='Repos'>";
		$finmission=true;
	}
	//Flight Mission
	if($Type_Mission ==20)
	{
		if($Mission_Flight ==3)
			$AvionT2_Nbr=0;
		elseif($Mission_Flight ==2)
			$AvionT3_Nbr=0;
		else
		{
			$AvionT2_Nbr=0;
			$AvionT3_Nbr=0;
		}
	}
	//Plus d'avions
	if(!$AvionT1_Nbr and !$AvionT2_Nbr and !$AvionT3_Nbr and $Type_Mission <98)
	{
		$mes.='<br>Le taux de pertes d\'avions de votre escadrille est catastrophique! Plus aucun avion n\'est en état de voler!<br>Si vous ne faites rien pour vous organiser, vous ne pourrez plus partir en mission!';
		$img='<img src=\'images/transfer_no'.$country.'.jpg\' style=\'width:100%;\'>';
		$finmission=true;
	}
	if($Crashs_Jour >1 and $Type_Mission <98 and $Type_Mission !=9 and ($Grade[1] <3 or !$Commandant))
	{
		$mes.='<br>Votre commandant n\'apprécie guère que vous perdiez tous ses avions par manque de prudence!<br>Vous êtes interdit de vol jusqu\'à ce que vous vous calmiez un peu!';
		$img='<img src=\'images/transfer_no'.$country.'.jpg\' style=\'width:100%;\'>';
		$finmission=true;
		$Interdit=true;
	}
	if(!$finmission)
	{		
		$titre='Préparation de la mission';
		$Sqn=GetSqn($country);
		if($Avion2_dispos >0 and $AvionT2_Nbr >0 and $AvionT1 !=$AvionT2)
			$AvionT2_Nbr-=$Avion2_dispos;
		if($Avion3_dispos >0 and $AvionT3_Nbr >0 and $AvionT1 !=$AvionT3 and $AvionT2 !=$AvionT3)
			$AvionT3_Nbr-=$Avion3_dispos;
		if($Avion1_dispos >0 or $AvionT1 ==$AvionT2 or $AvionT1 ==$AvionT3)
			$AvionT1_Nbr-=$Avion1_dispos;
		if($AvionT1_Nbr <0)
		{
			if($AvionT1 ==$AvionT2)
				$AvionT2_Nbr+=$AvionT1_Nbr;
			elseif($AvionT1 ==$AvionT3)
				$AvionT3_Nbr+=$AvionT1_Nbr;
			$AvionT1_Nbr=0;
		}
		//Avion imposé pour les débutants
		if(($Grade[1] <2 and $Unite_Type !=8) or ($Unite_Type ==8 and $Reputation <50))
		{
			if($AvionT1_Nbr >0)
				SetData("Pilote","S_Leader",1,"ID",$PlayerID);
			else
			{
				$AvionT1_Nbr=0;
				if($Crashs_Jour >2)
				{
					$AvionT1_txt_popup='Interdiction de vol';
					$Interdit=true;
				}
				elseif($AvionT1_Nbr <1)
					$AvionT1_txt_popup='Aucun avion en état de marche';
				else
					$AvionT1_txt_popup='Heures de vol insuffisantes';
				$AvionT1_txt="<Input type='Radio' name='Avion' value='9992' title='Avion non disponible ".$AvionT1_txt_popup."' disabled>-".GetAvionIcon($AvionT1,$country,0,$Unite,$Front)." ".$Sqn." 1";
			}
			$AvionT2_txt="<Input type='Radio' name='Avion' value='9992' title='Avion non disponible par manque de réputation' disabled>-".GetAvionIcon($AvionT2,$country,0,$Unite,$Front)." ".$Sqn." 2";
			$AvionT3_txt="<Input type='Radio' name='Avion' value='9992' title='Avion non disponible par manque de réputation' disabled>-".GetAvionIcon($AvionT3,$country,0,$Unite,$Front)." ".$Sqn." 3";
			$AvionT2_Nbr=0;
			$AvionT3_Nbr=0;
		}
		else
		{
			//Prototype
			if($Proto >0)
			{
				$proto_ok=false;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Type,Nom,Autonomie,Robustesse,ID_ref FROM Avions_Persos WHERE ID='$Proto'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-proto2');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Type_Mission_avion=$data['Type'];
						$Avion4=$data['Nom'];
						$Avion4_a=$data['Autonomie'];
						$Avion4_r=$data['Robustesse'];
						$ID_ref_proto=$data['ID_ref'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				if($Type_Mission_avion ==$Unite_Type)$proto_ok=true;
				if($proto_ok)
					$AvionT4_txt="<Input type='Radio' name='Avion' value='".$Proto."'>-".GetAvionIcon($ID_ref_proto,$country,0,$Unite,$Front)." Prototype";
				else
					$AvionT4_txt="<Input type='Radio' name='Avion' value='".$Proto."' title='Avion non disponible par manque de carburant ou de munitions' disabled>-".GetAvionIcon($ID_ref_proto,$country,0,$Unite,$Front)." Prototype";
			}
			if($Avion_p >0)
			{
				$Avion_perso=false;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Type,Nom,Autonomie,Robustesse,Bombe,Bombe_Nbr,Engine,Moteur,ID_ref FROM Avions_Persos WHERE ID='$Avion_p'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-perso');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Type_Mission_avion=$data['Type'];
						$Avion5=$data['Nom'];
						$Avion5_a=$data['Autonomie'];
						$Avion5_r=$data['Robustesse'];
						$Avion5_Bombe=$data['Bombe'];
						$Avion5_Bombe_Nbr=$data['Bombe_Nbr'];
						$Avion5_Engine=$data['Engine'];
						$Avion5_Moteur=$data['Moteur'];
						$ID_ref=$data['ID_ref'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				if($Avion5_Moteur ==2)
					$Avion5_Fuel=100;
				else
					$Avion5_Fuel=GetData("Moteur","ID",$Avion5_Engine,"Carburant");
				$Unit5_Stock_Fuel=GetData("Unit","ID",$Unite,"Stock_Essence_".$Avion5_Fuel);
				$Bomb5=true;
				if($Type_Mission_avion !=6 and $Unite_Type !=6)
				{
					if($Avion5_Bombe !=25 and $Avion5_Bombe !=26 and $Avion5_Bombe !=27 and $Avion5_Bombe !=350)
					{
						if($Avion5_Bombe_Nbr >0 and $Avion5_Bombe >0)
							$Unit_Stock_Bombes5=GetData("Unit","ID",$Unite,"Bombes_".$Avion5_Bombe);
						if($Avion5_Bombe_Nbr >0 and $Unit_Stock_Bombes5 <$Avion5_Bombe_Nbr)
						{
							$Bomb5=false;
							$Bomb5_txt="(".$Unit_Stock_Bombes5."/".$Avion5_Bombe_Nbr." Bombes de ".$Avion5_Bombe."kg)";
						}
					}
				}
				if($Type_Mission_avion !=$Unite_Type)
				{
					if($Type_Mission_avion ==5 and $Unite_Type ==1)
						$Avion_perso=true;
				}
				else
					$Avion_perso=true;
				if($Avion_perso and $Bomb5 and $Unit5_Stock_Fuel > $Avion5_a)
					$AvionT5_txt="<Input type='Radio' name='Avion' value='".$Avion_p."'>-".GetAvionIcon($ID_ref,$country,0,$Unite,$Front)." Avion perso";
				else
					$AvionT5_txt="<Input type='Radio' name='Avion' value='".$Avion_p."' title='Avion non disponible par manque de carburant (".$Unit5_Stock_Fuel."/".$Avion5_a."L ".$Avion5_Fuel." Octane) ou de munitions ".$Bomb5_txt.", ou parce que son rôle est différent de celui de votre unité' disabled>-".GetAvionIcon($ID_ref,$country,0,$Unite,$Front)." Avion perso";
			}			
			//Avion2
			if($AvionT2_Nbr >0) //and $Avion2_Limite >= $Crashs_Jour and GetPil($PlayerID, $AvionT2, 1) >= $Avion2_XP)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Engine,Autonomie,ArmePrincipale,Arme1_Nbr,Arme1_Mun,ArmeSecondaire FROM Avion WHERE ID='$AvionT2'")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-avion2');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Avion2_Engine=$data['Engine'];
						$Avion2_a=$data['Autonomie'];
						$Avion2_ap=$data['ArmePrincipale'];
						$Avion2_ap_nbr=$data['Arme1_Nbr'];
						$Avion2_mun=$data['Arme1_Mun'];
						$Avion2_as=$data['ArmeSecondaire'];
					}
					mysqli_free_result($result);
					unset($data);
				}				
				//Fuel
				if($Avion2_Bombe ==350)
				{
					$Array_Mod=GetAmeliorations($AvionT2);
					$Avion2_a+=$Array_Mod[18];
					unset($Array_Mod);
				}
				$Avion2_Fuel=GetData("Moteur","ID",$Avion2_Engine,"Carburant");
				$Unit2_Stock_Fuel=GetData("Unit","ID",$Unite,"Stock_Essence_".$Avion2_Fuel);
				//Bombes
				$Bomb2=true;
				if($Type_Mission_avion !=6 and $Unite_Type !=6)
				{
					if($Avion2_Bombe !=25 and $Avion2_Bombe !=26 and $Avion2_Bombe !=27 and $Avion2_Bombe !=350)
					{
						if($Avion2_Bombe_Nbr >0 and $Avion2_Bombe >0)
							$Unit_Stock_Bombes2=GetData("Unit","ID",$Unite,"Bombes_".$Avion2_Bombe);
						if($Avion2_Bombe_Nbr >0 and $Unit_Stock_Bombes2 < $Avion2_Bombe_Nbr)
							$Bomb2=false;
					}
				}
				//Muns
				$Avion2_ap_cal=round(GetData("Armes","ID",$Avion2_ap,"Calibre"));
				if(!$Avion2_mun)
				{
					$Avion2_ap_mun=GetData("Armes","ID",$Avion2_ap,"Munitions");
					$Avion2_mun=$Avion2_ap_nbr*$Avion2_ap_mun;
				}
				if($Avion2_ap and $Avion2_ap !=5 and $Avion2_ap !=0 and $Avion2_ap !=25 and $Avion2_ap !=26 and $Avion2_ap !=27)
				{
					$Unit2_stock_mun=GetData("Unit","ID",$Unite,"Stock_Munitions_".$Avion2_ap_cal);
				}
				else
				{
					$Unit2_stock_mun=1;
					$Avion2_mun=0;
					$Avion2_ap="Aucune";
				}
				//Mun2
				if($Avion2_as and $Avion2_as !=5 and $Avion2_as !=0 and $Avion2_as !=25 and $Avion2_as !=26 and $Avion2_as !=27)
				{
					$Avion2_as_nom=GetData("Armes","ID",$Avion2_as,"Nom");
					$Avion2_as_nbr=GetData("Avion","ID",$AvionT2,"Arme2_Nbr");
					$Avion2_as_cal=round(GetData("Armes","ID",$Avion2_as,"Calibre"));
					$Avion2_mun_as=GetData("Avion","ID",$AvionT2,"Arme2_Mun");
					if(!$Avion2_mun_as)
					{
						$Avion2_as_mun=GetData("Armes","ID",$Avion2_as,"Munitions");
						$Avion2_mun_as=$Avion2_as_nbr*$Avion2_as_mun;
					}
					$Unit2_stock_mun_as=GetData("Unit","ID",$Unite,"Stock_Munitions_".$Avion2_as_cal);
					$Avion2_as_txt="".$Avion2_as_nbr." ".$Avion2_as_nom." (".$Avion2_as_cal."mm)";
				}
				else
				{
					$Unit2_stock_mun_as=1;
					$Avion2_mun_as=0;
					$Avion2_as_txt="Aucune";
				}
				if($Unit2_Stock_Fuel > $Avion2_a and $Unit2_stock_mun > $Avion2_mun and $Unit2_stock_mun_as > $Avion2_mun_as and $Bomb2)
				{		
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,VitesseH,VitesseB,VitesseA,VitesseP,Alt_ref,Plafond,Robustesse,Blindage,Engine_Nbr,ManoeuvreH,ManoeuvreB,Maniabilite FROM Avion WHERE ID='$AvionT2'")
					or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-avion2');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Avion2=$data['Nom'];
							$Avion2_v=$data['VitesseH'];
							$Avion2_vb=$data['VitesseB'];
							$Avion2_va=$data['VitesseA'];
							$Avion2_vp=$data['VitesseP'];
							$Alt_ref2=$data['Alt_ref'];
							$Avion2_pl=$data['Plafond'];
							$Avion2_r=$data['Robustesse'];
							$Avion2_b=$data['Blindage'];
							$Avion2_e_nbr=$data['Engine_Nbr'];
							$Avion2_mh=$data['ManoeuvreH'];
							$Avion2_mb=$data['ManoeuvreB'];
							$Avion2_mn=$data['Maniabilite'];
						}
						mysqli_free_result($result);
						unset($data);
					}			
					if(!$Avion2_b)
					{
						$Avion2_b=$U_Blindage;
						if(!$Avion2_b)$Avion_2b=$S_Blindage;
					}
					$Avion2_p=round((2000-GetPuissance("Avion",$AvionT2,0,$Avion2_r,1,1,$Avion2_e_nbr))/2);
					$Avion2_ap_nom=GetData("Armes","ID",$Avion2_ap,"Nom");
					if(!$Avion2_Bombe)
						$Avion2_Bombe_txt="Vide";
					else
					{
						if($Avion2_Bombe ==800)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Torpille(s)';
						elseif($Avion2_Bombe ==300)
							$Avion2_Bombe_txt=$Avion2_Bombe_nbr." Charge(s)";
						elseif($Avion2_Bombe ==350)
							$Avion2_Bombe_txt=$Avion2_Bombe_nbr." Réservoir externe";
						elseif($Avion2_Bombe ==400)
							$Avion2_Bombe_txt=$Avion2_Bombe_nbr." Mine(s)";
						elseif($Avion2_Bombe ==80)
							$Avion2_Bombe_txt=$Avion2_Bombe_nbr." Rocket(s)";
						elseif($Avion2_Bombe ==30)
							$Avion2_Bombe_txt=$Avion2_Bombe_nbr." Fusée(s)";
						elseif($Avion2_Bombe ==25)
							$Avion2_Bombe_txt=$Avion2_Bombe_nbr." Caméra";
						elseif($Avion2_Bombe ==26)
							$Avion2_Bombe_txt=$Avion2_Bombe_nbr." Caméra fixe";
						elseif($Avion2_Bombe ==27)
							$Avion2_Bombe_txt=$Avion2_Bombe_nbr." Caméra haute";
						elseif($Avion2_Bombe ==50000)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Cargaison(s) de 8mm';
						elseif($Avion2_Bombe ==15000)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Cargaison(s) de 13mm';
						elseif($Avion2_Bombe ==5000)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Cargaison(s) de 20mm';
						elseif($Avion2_Bombe ==3000)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Cargaison(s) de 30mm';
						elseif($Avion2_Bombe ==1500)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Cargaison(s) de 40mm';
						elseif($Avion2_Bombe ==1200)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Cargaison(s) d\'Octane 87';
						elseif($Avion2_Bombe ==1100)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Cargaison(s) d\'Octane 100';
						elseif($Avion2_Bombe ==100)
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' Parachutistes';
						else
							$Avion2_Bombe_txt=$Avion2_Bombe_Nbr.' x '.$Avion2_Bombe.'kg';
					}
					$Avion2_man=($Avion2_mh+$Avion2_mb)/2;					
					if($Avion2_Bombe_Nbr >0 and $Avion2_Bombe and $Unite_Type !=6)
					{
						$Avion2_Type=GetData("Avion","ID",$AvionT2,"Type");
						$Avion2_masse=GetData("Avion","ID",$AvionT2,"Masse");
						$Avion2_puiss=GetData("Avion","ID",$AvionT2,"Puissance");
						$Avion2_charge=$Avion2_Bombe_Nbr*$Avion2_Bombe;
						$Avion2_a=round($Avion2_a+((($Avion2_masse-$Avion2_charge)/$Avion2_puiss)-($Avion2_masse/$Avion2_puiss))*($Avion2_masse/10));
						//moda
						$moda=1+(2/($Avion2_masse/($Avion2_charge)));
						$Avion2_pl=round($Avion2_pl/$moda);
						$Avion2_v=GetSpeed("Avion",$AvionT2,$Alt_ref2,0,$moda,1,100,0);
						$Avion2_vb=GetSpeed("Avion",$AvionT2,1,0,$moda,1,100,0);
						$Avion2_va=GetSpeedA("Avion",$AvionT2,$Alt_ref2,0,$Avion2_e_nbr,$moda);
						$Avion2_mh=GetMan("Avion",$AvionT2,7000,$Avion2_r,$moda);
						$Avion2_mb=GetMan("Avion",$AvionT2,3000,$Avion2_r,$moda);
						$Avion2_mn=GetMani($Avion2_mn,$Avion2_r,$Avion2_r,$moda);
						$Avion2_ap_txt=$Avion2_ap_nbr.' '.$Avion2_ap_nom.' ('.$Avion2_ap_cal.'mm '.GetMun_txt($Avion2_Mun1).')';
					}
					$AvionT2_Fuel=floor($Unit2_Stock_Fuel/$Avion2_a);
					if($AvionT2_Nbr >$AvionT2_Fuel)
						$AvionT2_Nbr_End=$AvionT2_Fuel;
					else
						$AvionT2_Nbr_End=$AvionT2_Nbr;			
					if($Avion2_mun >0)
					{
						$AvionT2_Mun_1=floor($Unit2_stock_mun/$Avion2_mun);
						if($AvionT2_Nbr_End >$AvionT2_Mun_1)
							$AvionT2_Nbr_End=$AvionT2_Mun_1;
					}
					if($Avion2_mun_as >0)
					{
						$AvionT2_Mun_2=floor($Unit2_stock_mun_as/$Avion2_mun_as);
						if($AvionT2_Nbr_End >$AvionT2_Mun_2)
							$AvionT2_Nbr_End=$AvionT2_Mun_2;
					}
					//$Avions_Select_txt_T2='<option value=\'9992\'>'.$Sqn.' 2 : '.$Avion2.' ('.$Avion2_a.'km)</option>';
					$AvionT2_txt="<Input type='Radio' name='Avion' value='9992'>-".GetAvionIcon($AvionT2,$country,0,$Unite,$Front)." ".$Sqn." 2";
					$AvionT2_Dispo=$AvionT2_Nbr_End."/".$AvionT2_Nbr;
				}
				else
				{
					$AvionT2_txt="<Input type='Radio' name='Avion' value='9992' title='Avion non disponible par manque de carburant ou de munitions' disabled>-".GetAvionIcon($AvionT2,$country,0,$Unite,$Front)." ".$Sqn." 2";
					$AvionT2_Nbr=0;
				}
			}
			else
			{
				if($Crashs_Jour >2)
				{
					$mes.='<p>Votre commandant n\'apprécie guère que vous perdiez tous ses avions par manque de prudence!<br>Vous êtes interdit de vol jusqu\'à ce que vous vous calmiez un peu!</p>';
					$finmission=true;
					$Interdit=true;
				}
				$AvionT2_txt="<Input type='Radio' name='Avion' value='9992' title='Avion non disponible' disabled>-".GetAvionIcon($AvionT2,$country,0,$Unite,$Front)." ".$Sqn." 2";
				$AvionT2_Nbr=0;
			}			
			//Avion3
			if($AvionT3_Nbr >0) //and $Avion3_Limite >=$Crashs_Jour and GetPil($PlayerID,$AvionT3,1) >=$Avion3_XP)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Engine,Autonomie,ArmePrincipale,Arme1_Nbr,Arme1_Mun,ArmeSecondaire FROM Avion WHERE ID='$AvionT3'")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-avion3');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Avion3_Engine=$data['Engine'];
						$Avion3_a=$data['Autonomie'];
						$Avion3_ap=$data['ArmePrincipale'];
						$Avion3_ap_nbr=$data['Arme1_Nbr'];
						$Avion3_mun=$data['Arme1_Mun'];
						$Avion3_as=$data['ArmeSecondaire'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				//Fuel
				if($Avion3_Bombe ==350)
				{
					$Array_Mod=GetAmeliorations($AvionT3);
					$Avion3_a += $Array_Mod[18];
					unset($Array_Mod);
				}
				$Avion3_Fuel=GetData("Moteur","ID",$Avion3_Engine,"Carburant");
				$Unit3_Stock_Fuel=GetData("Unit","ID",$Unite,"Stock_Essence_".$Avion3_Fuel);
				//Bombes
				$Bomb3=true;
				if($Type_Mission_avion !=6 and $Unite_Type !=6)
				{
					if($Avion3_Bombe !=25 and $Avion3_Bombe !=26 and $Avion3_Bombe !=27 and $Avion3_Bombe !=350)
					{
						if($Avion3_Bombe_Nbr >0 and $Avion3_Bombe >0)
							$Unit_Stock_Bombes3=GetData("Unit","ID",$Unite,"Bombes_".$Avion3_Bombe);
						if($Avion3_Bombe_Nbr >0 and $Unit_Stock_Bombes3 < $Avion3_Bombe_Nbr)
							$Bomb3=false;
					}
				}
				//Muns
				$Avion3_ap_cal=round(GetData("Armes","ID",$Avion3_ap,"Calibre"));
				if(!$Avion3_mun)
				{
					$Avion3_ap_mun=GetData("Armes","ID",$Avion3_ap,"Munitions");
					$Avion3_mun=$Avion3_ap_nbr*$Avion3_ap_mun;
				}
				if($Avion3_ap and $Avion3_ap !=5 and $Avion3_ap !=0 and $Avion3_ap !=25 and $Avion3_ap !=26 and $Avion3_ap !=27)
					$Unit3_stock_mun=GetData("Unit","ID",$Unite,"Stock_Munitions_".$Avion3_ap_cal);
				else
				{
					$Unit3_stock_mun=1;
					$Avion3_mun=0;
					$Avion3_ap='Aucune';
				}
				//Muns2
				if($Avion3_as and $Avion3_as !=5 and $Avion3_as !=0 and $Avion3_as !=25 and $Avion3_as !=26 and $Avion3_as !=27)
				{
					$Avion3_as_nom=GetData("Armes","ID",$Avion3_as,"Nom");
					$Avion3_as_nbr=GetData("Avion","ID",$AvionT3,"Arme2_Nbr");
					$Avion3_as_cal=round(GetData("Armes","ID",$Avion3_as,"Calibre"));
					$Avion3_mun_as=GetData("Avion","ID",$AvionT3,"Arme2_Mun");
					if(!$Avion3_mun_as)
					{
						$Avion3_as_mun=GetData("Armes","ID",$Avion3_as,"Munitions");
						$Avion3_mun_as=$Avion3_as_nbr*$Avion3_as_mun;
					}
					$Unit3_stock_mun_as=GetData("Unit","ID",$Unite,"Stock_Munitions_".$Avion3_as_cal);
					$Avion3_as_txt="".$Avion3_as_nbr." ".$Avion3_as_nom." (".$Avion3_as_cal."mm)";
				}
				else
				{
					$Unit3_stock_mun_as=1;
					$Avion3_mun_as=0;
					$Avion3_as_txt='Aucune';
				}				
				if($Unit3_Stock_Fuel >$Avion3_a and $Unit3_stock_mun >$Avion3_mun and $Unit3_stock_mun_as >$Avion3_mun_as and $Bomb3)
				{		
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,VitesseH,VitesseB,VitesseA,VitesseP,Alt_ref,Plafond,Robustesse,Blindage,Engine_Nbr,ManoeuvreH,ManoeuvreB,Maniabilite FROM Avion WHERE ID='$AvionT3'")
					or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-avion3');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Avion3=$data['Nom'];
							$Avion3_v=$data['VitesseH'];
							$Avion3_vb=$data['VitesseB'];
							$Avion3_va=$data['VitesseA'];
							$Avion3_vp=$data['VitesseP'];
							$Alt_ref3=$data['Alt_ref'];
							$Avion3_pl=$data['Plafond'];
							$Avion3_r=$data['Robustesse'];
							$Avion3_b=$data['Blindage'];
							$Avion3_e_nbr=$data['Engine_Nbr'];
							$Avion3_mh=$data['ManoeuvreH'];
							$Avion3_mb=$data['ManoeuvreB'];
							$Avion3_mn=$data['Maniabilite'];
						}
						mysqli_free_result($result);
						unset($data);
					}				
					if(!$Avion3_b)
					{
						$Avion3_b=$U_Blindage;
						if(!$Avion3_b)$Avion_3b=$S_Blindage;
					}
					$Avion3_p=round((2000-GetPuissance("Avion",$AvionT3,0,$Avion3_r,1,1,$Avion3_e_nbr))/2);
					$Avion3_ap_nom=GetData("Armes","ID",$Avion3_ap,"Nom");
					if(!$Avion3_Bombe)
						$Avion3_Bombe_txt='Vide';
					else
					{
						if($Avion3_Bombe ==800)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Torpille(s)';
						elseif($Avion3_Bombe ==300)
							$Avion3_Bombe_txt=$Avion3_Bombe_nbr." Charge(s)";
						elseif($Avion3_Bombe ==350)
							$Avion3_Bombe_txt=$Avion3_Bombe_nbr." Réservoir externe";
						elseif($Avion3_Bombe ==400)
							$Avion3_Bombe_txt=$Avion3_Bombe_nbr." Mine(s)";
						elseif($Avion3_Bombe ==80)
							$Avion3_Bombe_txt=$Avion3_Bombe_nbr." Rocket(s)";
						elseif($Avion3_Bombe ==30)
							$Avion3_Bombe_txt=$Avion3_Bombe_nbr." Fusée(s)";
						elseif($Avion3_Bombe ==25)
							$Avion3_Bombe_txt=$Avion3_Bombe_nbr." Caméra";
						elseif($Avion3_Bombe ==26)
							$Avion3_Bombe_txt=$Avion3_Bombe_nbr." Caméra fixe";
						elseif($Avion3_Bombe ==27)
							$Avion3_Bombe_txt=$Avion3_Bombe_nbr." Caméra haute";
						elseif($Avion3_Bombe ==50000)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Cargaison(s) de 8mm';
						elseif($Avion3_Bombe ==15000)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Cargaison(s) de 13mm';
						elseif($Avion3_Bombe ==5000)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Cargaison(s) de 20mm';
						elseif($Avion3_Bombe ==3000)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Cargaison(s) de 30mm';
						elseif($Avion3_Bombe ==1500)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Cargaison(s) de 40mm';
						elseif($Avion3_Bombe ==1200)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Cargaison(s) d\'Octane 87';
						elseif($Avion3_Bombe ==1100)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Cargaison(s) d\'Octane 100';
						elseif($Avion3_Bombe ==100)
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' Parachutistes';
						else
							$Avion3_Bombe_txt=$Avion3_Bombe_Nbr.' x '.$Avion3_Bombe.'kg';
					}
					$Avion3_man=($Avion3_mh+$Avion3_mb)/2;					
					if($Avion3_Bombe_Nbr >0 and $Avion3_Bombe and $Unite_Type !=6)
					{
						$Avion3_Type=GetData("Avion","ID",$AvionT3,"Type");
						$Avion3_masse=GetData("Avion","ID",$AvionT3,"Masse");
						$Avion3_puiss=GetData("Avion","ID",$AvionT3,"Puissance");
						$Avion3_charge=$Avion3_Bombe_Nbr * $Avion3_Bombe;
						$Avion3_a=round($Avion3_a+((($Avion3_masse-$Avion3_charge)/$Avion3_puiss)-($Avion3_masse/$Avion3_puiss))*($Avion3_masse/10));
						//moda
						$moda=1+(2/($Avion3_masse/($Avion3_charge)));
						$Avion3_pl=round($Avion3_pl/$moda);
						$Avion3_v=GetSpeed("Avion",$AvionT3,$Alt_ref3,0,$moda,1,100,0);
						$Avion3_vb=GetSpeed("Avion",$AvionT3,1,0,$moda,1,100,0);
						$Avion3_va=GetSpeedA("Avion",$AvionT3,$Alt_ref3,0,$Avion3_e_nbr,$moda);
						$Avion3_mh=GetMan("Avion",$AvionT3,7000,$Avion3_r,$moda);
						$Avion3_mb=GetMan("Avion",$AvionT3,3000,$Avion3_r,$moda);
						$Avion3_mn=GetMani($Avion3_mn,$Avion3_r,$Avion3_r,$moda);
						$Avion3_ap_txt=$Avion3_ap_nbr.' '.$Avion3_ap_nom.' ('.$Avion3_ap_cal.'mm '.GetMun_txt($Avion3_Mun1).')';
					}
					$AvionT3_Fuel=floor($Unit3_Stock_Fuel/$Avion3_a);
					if($AvionT3_Nbr >$AvionT3_Fuel)
						$AvionT3_Nbr_End=$AvionT3_Fuel;
					else
						$AvionT3_Nbr_End=$AvionT3_Nbr;
					if($Avion3_mun >0)
					{
						$AvionT3_Mun_1=floor($Unit3_stock_mun/$Avion3_mun);
						if($AvionT3_Nbr_End >$AvionT3_Mun_1)
							$AvionT3_Nbr_End=$AvionT3_Mun_1;
					}
					if($Avion3_mun_as >0)
					{
						$AvionT3_Mun_2=floor($Unit3_stock_mun_as/$Avion3_mun_as);
						if($AvionT3_Nbr_End >$AvionT3_Mun_2)
							$AvionT3_Nbr_End=$AvionT3_Mun_2;
					}
					//$Avions_Select_txt_T3='<option value=\'9993\'>'.$Sqn.' 3 : '.$Avion3.' ('.$Avion3_a.'km)</option>';
					$AvionT3_txt="<Input type='Radio' name='Avion' value='9993'>-".GetAvionIcon($AvionT3,$country,0,$Unite,$Front)." ".$Sqn." 3";
					$AvionT3_Dispo=$AvionT3_Nbr_End."/".$AvionT3_Nbr;
				}
				else
				{
					$AvionT3_txt="<Input type='Radio' name='Avion' value='9993' title='Avion non disponible par manque de carburant ou de munitions' disabled>-".GetAvionIcon($AvionT3,$country,0,$Unite,$Front)." ".$Sqn." 3";
					$AvionT3_Nbr=0;
				}
			}
			else
			{
				if($Crashs_Jour >2)
				{
					$mes.='<p>Votre commandant n\'apprécie guère que vous perdiez tous ses avions par manque de prudence!<br>Vous êtes interdit de vol jusqu\'à ce que vous vous calmiez un peu!</p>';
					$finmission=true;
					$Interdit=true;
				}
				$AvionT3_txt="<Input type='Radio' name='Avion' value='9993' title='Avion non disponible' disabled>-".GetAvionIcon($AvionT3,$country,0,$Unite,$Front)." ".$Sqn." 3";
				$AvionT3_Nbr=0;
			}
		}		
		if($AvionT1_Nbr)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Engine,Autonomie,ArmePrincipale,Arme1_Nbr,Arme1_Mun,ArmeSecondaire FROM Avion WHERE ID='$AvionT1'")
			or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-avion1');
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Avion1_Engine=$data['Engine'];
					$Avion1_a=$data['Autonomie'];
					$Avion1_ap=$data['ArmePrincipale'];
					$Avion1_ap_nbr=$data['Arme1_Nbr'];
					$Avion1_mun=$data['Arme1_Mun'];
					$Avion1_as=$data['ArmeSecondaire'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			//Essence
			if($Avion1_Bombe ==350)
			{
				$Array_Mod=GetAmeliorations($AvionT1);
				$Avion1_a += $Array_Mod[18];
				unset($Array_Mod);
			}
			$Avion1_Fuel=GetData("Moteur","ID",$Avion1_Engine,"Carburant");
			$Unit1_Stock_Fuel=GetData("Unit","ID",$Unite,"Stock_Essence_".$Avion1_Fuel);
			//Bombes
			$Bomb1=true;
			if($Type_Mission_avion !=6 and $Unite_Type !=6)
			{
				if($Avion1_Bombe !=25 and $Avion1_Bombe !=26 and $Avion1_Bombe !=27 and $Avion1_Bombe !=350)
				{
					if($Avion1_Bombe_nbr >0 and $Avion1_Bombe >0)
						$Unit_Stock_Bombes1=GetData("Unit","ID",$Unite,"Bombes_".$Avion1_Bombe);
					if($Avion1_Bombe_nbr >0 and $Unit_Stock_Bombes1 < $Avion1_Bombe_nbr)
						$Bomb1=false;
				}
			}
			//Muns
			$Avion1_ap_cal=round(GetData("Armes","ID",$Avion1_ap,"Calibre"));
			if(!$Avion1_mun)
			{
				$Avion1_ap_mun=GetData("Armes","ID",$Avion1_ap,"Munitions");
				$Avion1_mun=$Avion1_ap_nbr*$Avion1_ap_mun;
			}
			if($Avion1_ap and $Avion1_ap !=5 and $Avion1_ap !=0 and $Avion1_ap !=25 and $Avion1_ap !=26 and $Avion1_ap !=27)
				$Unit1_stock_mun=GetData("Unit","ID",$Unite,"Stock_Munitions_".$Avion1_ap_cal);
			else
			{
				$Unit1_stock_mun=1;
				$Avion1_mun=0;
				$Avion1_ap_txt='Aucune';
			}
			//Mun2
			if($Avion1_as and $Avion1_as !=5 and $Avion1_as !=0 and $Avion1_as !=25 and $Avion1_as !=26 and $Avion1_as !=27)
			{
				$Avion1_as_nom=GetData("Armes","ID",$Avion1_as,"Nom");
				$Avion1_as_nbr=GetData("Avion","ID",$AvionT1,"Arme2_Nbr");
				$Avion1_as_cal=round(GetData("Armes","ID",$Avion1_as,"Calibre"));
				$Avion1_mun_as=GetData("Avion","ID",$AvionT1,"Arme2_Mun");
				if(!$Avion1_mun_as)
				{
					$Avion1_as_mun=GetData("Armes","ID",$Avion1_as,"Munitions");
					$Avion1_mun_as=$Avion1_as_nbr*$Avion1_as_mun;
				}
				$Unit1_stock_mun_as=GetData("Unit","ID",$Unite,"Stock_Munitions_".$Avion1_as_cal);
				$Avion1_as_txt="".$Avion1_as_nbr." ".$Avion1_as_nom." (".$Avion1_as_cal."mm)";
			}
			else
			{
				$Unit1_stock_mun_as=1;
				$Avion1_mun_as=0;
				$Avion1_as_txt='Aucune';
			}
			if($AvionT1_Nbr and $Unit1_Stock_Fuel >$Avion1_a and $Unit1_stock_mun >$Avion1_mun and $Unit1_stock_mun_as >$Avion1_mun_as and $Bomb1)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,VitesseH,VitesseB,VitesseA,VitesseP,Alt_ref,Plafond,Robustesse,Blindage,Engine_Nbr,ManoeuvreH,ManoeuvreB,Maniabilite FROM Avion WHERE ID='$AvionT1'")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-avion1');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Avion1=$data['Nom'];
						$Avion1_v=$data['VitesseH'];
						$Avion1_vb=$data['VitesseB'];
						$Avion1_va=$data['VitesseA'];
						$Avion1_vp=$data['VitesseP'];
						$Alt_ref1=$data['Alt_ref'];
						$Avion1_pl=$data['Plafond'];
						$Avion1_r=$data['Robustesse'];
						$Avion1_b=$data['Blindage'];
						$Avion1_e_nbr=$data['Engine_Nbr'];
						$Avion1_mh=$data['ManoeuvreH'];
						$Avion1_mb=$data['ManoeuvreB'];
						$Avion1_mn=$data['Maniabilite'];
					}
					mysqli_free_result($result);
					unset($data);
				}				
				if(!$Avion1_b)// and $Avion_db =="Avion")
				{
					$Avion1_b=$U_Blindage;
					if(!$Avion1_b)$Avion_1b=$S_Blindage;
				}
				$Avion1_p=round((2000-GetPuissance("Avion",$AvionT1,0,$Avion1_r,1,1,$Avion1_e_nbr))/2);
				$Avion1_ap_nom=GetData("Armes","ID",$Avion1_ap,"Nom");
				if(!$Avion1_Bombe)
					$Avion1_Bombe_txt='Vide';
				else
				{
					if($Avion1_Bombe ==800)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' Torpille(s)';
					elseif($Avion1_Bombe ==300)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Charge(s)";
					elseif($Avion1_Bombe ==350)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Réservoir externe";
					elseif($Avion1_Bombe ==400)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Mine(s)";
					elseif($Avion1_Bombe ==80)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Rocket(s)";
					elseif($Avion1_Bombe ==30)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Fusée(s)";
					elseif($Avion1_Bombe ==25)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Caméra";
					elseif($Avion1_Bombe ==26)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Caméra fixe";
					elseif($Avion1_Bombe ==27)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Caméra haute";
					elseif($Avion1_Bombe ==50000)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr." Cargaison(s) de 8mm";
					elseif($Avion1_Bombe ==15000)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' Cargaison(s) de 13mm';
					elseif($Avion1_Bombe ==5000)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' Cargaison(s) de 20mm';
					elseif($Avion1_Bombe ==3000)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' Cargaison(s) de 30mm';
					elseif($Avion1_Bombe ==1500)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' Cargaison(s) de 40mm';
					elseif($Avion1_Bombe ==1200)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' Cargaison(s) d\'Octane 87';
					elseif($Avion1_Bombe ==1100)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' Cargaison(s) d\'Octane 100';
					elseif($Avion1_Bombe ==100)
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' Parachutistes';
					else
						$Avion1_Bombe_txt=$Avion1_Bombe_nbr.' x '.$Avion1_Bombe.'kg';
				}
				$Avion1_man=($Avion1_mh+$Avion1_mb)/2;				
				/*if($Avion_db =="Avions_Persos")
					$Avions_Select_txt_T1='<option value='.$AvionT1.'>Perso : '.$Avion1.' ('.$Avion1_a.'km)</option>';
				else
				{*/
					if($Avion1_Bombe_nbr >0 and $Avion1_Bombe and $Unite_Type !=6)
					{
						$Avion_db="Avion";
						$Avion1_Type=GetData($Avion_db,"ID",$AvionT1,"Type");
						$Avion1_masse=GetData($Avion_db,"ID",$AvionT1,"Masse");
						$Avion1_puiss=GetData($Avion_db,"ID",$AvionT1,"Puissance");
						$Avion1_charge=$Avion1_Bombe_nbr*$Avion1_Bombe;
						$Avion1_a=round($Avion1_a+((($Avion1_masse-$Avion1_charge)/$Avion1_puiss)-($Avion1_masse/$Avion1_puiss))*($Avion1_masse/10));
						//moda
						$moda=1+(2/($Avion1_masse/($Avion1_charge)));
						$Avion1_pl=round($Avion1_pl/$moda);
						$Avion1_v=GetSpeed($Avion_db,$AvionT1,$Alt_ref1,0,$moda,1,100,0);
						$Avion1_vb=GetSpeed($Avion_db,$AvionT1,1,0,$moda,1,100,0);
						$Avion1_va=GetSpeedA($Avion_db,$AvionT1,$Alt_ref1,0,$Avion1_e_nbr,$moda);
						$Avion1_mh=GetMan($Avion_db,$AvionT1,7000,$Avion1_r,$moda);
						$Avion1_mb=GetMan($Avion_db,$AvionT1,3000,$Avion1_r,$moda);
						$Avion1_mn=GetMani($Avion1_mn,$Avion1_r,$Avion1_r,$moda);
						$Avion1_ap_txt=$Avion1_ap_nbr.' '.$Avion1_ap_nom.' ('.$Avion1_ap_cal.'mm '.GetMun_txt($Avion1_Mun1).')';
					}
					$AvionT1_Fuel=floor($Unit1_Stock_Fuel/$Avion1_a);
					if($AvionT1_Nbr >$AvionT1_Fuel)
						$AvionT1_Nbr_End=$AvionT1_Fuel;
					else
						$AvionT1_Nbr_End=$AvionT1_Nbr;
					if($Avion1_mun >0)
					{
						$AvionT1_Mun_1=floor($Unit1_stock_mun/$Avion1_mun);
						if($AvionT1_Nbr_End >$AvionT1_Mun_1)
							$AvionT1_Nbr_End=$AvionT1_Mun_1;
					}
					if($Avion1_mun_as >0)
					{
						$AvionT1_Mun_2=floor($Unit1_stock_mun_as/$Avion1_mun_as);
						if($AvionT1_Nbr_End >$AvionT1_Mun_2)
							$AvionT1_Nbr_End=$AvionT1_Mun_2;
					}
					//$Avions_Select_txt_T1='<option value=\'9991\'>'.$Sqn.' 1 : '.$Avion1.' ('.$Avion1_a.'km)</option>';
					$AvionT1_txt="<Input type='Radio' name='Avion' value='9991' checked>-".GetAvionIcon($AvionT1,$country,0,$Unite,$Front)." ".$Sqn." 1";
					$AvionT1_Dispo=$AvionT1_Nbr_End."/".$AvionT1_Nbr;
				//}
			}
			else
			{
				$AvionT1_txt="<Input type='Radio' name='Avion' value='9991' title='Avion non disponible par manque de carburant ou de munitions' disabled>-".GetAvionIcon($AvionT1,$country,0,$Unite,$Front)." ".$Sqn." 1";
				$AvionT1_Nbr=0;
			}
		}
		else
		{
			if($Crashs_Jour >2)
			{
				$mes.='<p>Votre commandant n\'apprécie guère que vous perdiez tous ses avions par manque de prudence!<br>Vous êtes interdit de vol jusqu\'à ce que vous vous calmiez un peu!</p>';
				$finmission=true;
				$Interdit=true;
			}
			$AvionT1_txt="<Input type='Radio' name='Avion' value='9991' title='Avion non disponible par manque de stock' disabled>-".GetAvionIcon($AvionT1,$country,0,$Unite,$Front)." ".$Sqn." 1";
			$AvionT1_Nbr=0;
		}
		if($AvionT1_Nbr >0 or $AvionT2_Nbr >0 or $AvionT3_Nbr >0)
		{
			if($Zone ==6 and $Front ==3)
			{
				$Avion1_a*=2;
				$Avion2_a*=2;
				$Avion3_a*=2;
			}
			$Mission_Type=GetMissionType($Type_Mission);
			//***Destination***//
			//Entrainement >10km du terrain
			if($Type_Mission ==98 or $Type_Mission ==99 or $Type_Mission ==100 or $Type_Mission ==103)
			{
				$Lieux='<option value='.$Base.'>La région de '.$Terrain.' (10km)</option>';
				$voyage=true;
			}
			elseif($Type_Mission ==101 or $Type_Mission ==102)
			{
				$Lat_base_min=$Lat_base-1.00;
				$Lat_base_max=$Lat_base+1.00;
				$Long_base_min=$Long_base-1.50;
				$Long_base_max=$Long_base+1.50;
				$con=dbconnecti();
				$resultl=mysqli_query($con,"SELECT DISTINCT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p WHERE l.Flag=p.Pays_ID AND p.Faction='$Faction'
				AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.Zone<>6")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieux');
				mysqli_close($con);
				if($resultl)
				{
					while($datal=mysqli_fetch_array($resultl,MYSQLI_NUM)) 
					{
						$Dist=GetDistance(0,0,$Long_base,$Lat_base,$datal[3],$datal[2]);
						$idata=$datal[1].' ('.$Dist[0].'km)';
						$Lieux.='<option value='.$datal[0].'>'.$idata.'</option>';
					}
					mysqli_free_result($resultl);
					unset($datal);
					$voyage=true;
				}	
				if(!$Lieux)$Lieux='<option value='.$Base.'>La région de '.$Terrain.' (10km)</option>';
			}
			/*elseif($Type_Mission ==9)
			{
				$Lat_base_min=$Lat_base-0.50;
				$Lat_base_max=$Lat_base+0.50;
				$Long_base_min=$Long_base-1.00;
				$Long_base_max=$Long_base+1.00;
				$con=dbconnecti();				
				$resultl=mysqli_query($con,"SELECT DISTINCT Lieu.ID,Lieu.Nom,Lieu.Latitude,Lieu.Longitude FROM Lieu,Pays WHERE Lieu.Flag=Pays.Pays_ID AND Pays.Faction='$Faction'
				AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
				AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') ORDER BY RAND() LIMIT 1")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieux');
				mysqli_close($con);
				if($resultl)
				{
					while($datal=mysqli_fetch_array($resultl,MYSQLI_NUM)) 
					{
						$Dist=GetDistance(0,0,$Long_base,$Lat_base,$datal[3],$datal[2]);
						$idata=$datal[1].' ('.$Dist[0].'km)';
						$Lieux='<option value='.$datal[0].'>'.$idata.'</option>';
					}
					mysqli_free_result($resultl);
					$voyage=true;
				}	
			}*/
			elseif($Type_Mission ==10 or $Type_Mission ==20) //Mission historique
			{
				if($Type_Mission ==20)
				{
					$Type_Mission=$Mission_Type;
					$Lieu_Nom=GetData("Lieu","ID",$Mission_Lieu,"Nom");
					$Dist=GetDistance($Base, $Mission_Lieu);
					$Lieux='<option value='.$Mission_Lieu.'>'.$Lieu_Nom.' ('.$Dist[0].'km)</option>';
					$voyage=true;
				}
				else
				{
					$Type_Mission=$_SESSION['BH_Mission'];
					$BH_Lieu=$_SESSION['BH_Lieu'];
					$BH_Nom=$_SESSION['BH_Nom'];
					$Dist=GetDistance($Base,$BH_Lieu);
					$Lieux='<option value='.$BH_Lieu.'>'.$BH_Nom.' ('.$Dist[0].'km)</option>';
					$voyage=true;
				}
				//$_SESSION['Mission']=$Type_Mission;
			}
			elseif($Type_Mission ==11 or $Type_Mission ==12 or $Type_Mission ==13 or $Type_Mission ==14 or $Type_Mission ==29 or ($Type_Mission ==15 and $Unite_Type ==9)) //Missions Navales
			{
				if($Front ==3)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >67 AND (Port >0 OR Zone=6) AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' ORDER BY RAND()";
				elseif($Front ==1)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >=43 AND Latitude <=50.5 AND Longitude >13 AND Longitude <50 AND (Port >0 OR Zone=6) AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' ORDER BY RAND()";
				elseif($Front ==4)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >50.5 AND Longitude >13 AND Longitude <50 AND (Port >0 OR Zone=6) AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' ORDER BY RAND()";
				elseif($Front ==5)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >60 AND Longitude >13 AND Longitude <50 AND (Port >0 OR Zone=6) AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' ORDER BY RAND()";
				elseif($Front ==2)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude <43 AND Longitude <50 AND (Port >0 OR Zone=6) AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' ORDER BY RAND()";
				else
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >=43 AND Latitude <60 AND Longitude <14 AND (Port >0 OR Zone=6) AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' ORDER BY RAND()";
				$con=dbconnecti();
				$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieuxrand');		
				mysqli_close($con);
				//Destination aléatoire
				if($result)
				{
					$voyage=false;
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data['Longitude'],$data['Latitude']);
						$Dist_chk=($Dist[0]*2)+200;
						if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a)
						{
							$idata=$data['Nom'].' ('.$Dist[0].'km)';
							$Lieux='<option value='.$data['ID'].'>'.$idata.'</option>';
							$titre='Votre commandant vous a désigné pour une mission de <b>'.$Mission_Type.'</b> en direction de <b>'.$data['Nom'].'</b>.';
							$BH_Lieu=$data['ID'];
							$voyage=true;
							//break;
						}
					}
					mysqli_free_result($result);
				}
				else
					$mes.='Erreur d\'import de données.';
			}
			elseif($Type_Mission ==18 or $Type_Mission ==19 or $Type_Mission ==22)
			{
				if($Type_Mission ==19)
					$query="SELECT DISTINCT Lieu.ID,Lieu.Nom,Lieu.Latitude,Lieu.Longitude FROM Lieu,Pilote,Pays WHERE Pilote.MIA=Lieu.ID AND Pilote.Pays=Pays.Pays_ID AND Pays.Faction='$Faction' AND Lieu.Zone=6 ORDER BY Nom ASC";
				else
					$query="SELECT DISTINCT Lieu.ID,Lieu.Nom,Lieu.Latitude,Lieu.Longitude FROM Lieu,Pilote,Pays WHERE Pilote.MIA=Lieu.ID AND Pilote.Pays=Pays.Pays_ID AND Pays.Faction='$Faction' AND Lieu.Zone<>6 ORDER BY Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieuxsave');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data[3],$data[2]);
						$Dist_chk=($Dist[0]*2)+100;
						if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a or $Dist_chk <$Avion4_a or $Dist_chk <$Avion5_a)
						{
							$idata=$data[1].' ('.$Dist[0].'km)';
							$Lieux.='<option value='.$data[0].'>'.$idata.'</option>';
						}
					}
					mysqli_free_result($result);
				}
				else
					$mes.='Erreur d\'import de données.';
				if(!$Lieux)
					$voyage=false;
				else
				{
					$titre='Vous avez planifié une mission de <b>'.$Mission_Type.'</b>. Quel sera votre objectif ?';
					$voyage=true;
				}
			}
			elseif($Type_Mission ==24 or $Type_Mission ==25 or $Type_Mission ==27 or $Type_Mission ==28)
			{
				if($Type_Mission ==27 or $Type_Mission ==28)
				{
					$con=dbconnecti();
					$Cdo=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Pays='$country' AND Front='$Front' AND Commando=1 AND ID<>'$PlayerID'"),0);
					mysqli_close($con);
				}				
				if($Type_Mission ==24 or $Type_Mission ==25 or $Cdo >0)
				{				
					$query="SELECT DISTINCT Lieu.ID,Lieu.Nom,Lieu.Latitude,Lieu.Longitude FROM Lieu,Pays WHERE Lieu.Flag=Pays.Pays_ID AND Pays.Faction<>'$Faction' AND Lieu.Zone<>6 ORDER BY Nom ASC";
					$con=dbconnecti();
					$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieuxpara');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
						{
							$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data[3],$data[2]);
							$Dist_chk=($Dist[0]*2)+200;
							if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a or $Dist_chk <$Avion4_a or $Dist_chk <$Avion5_a)
							{
								$idata=$data[1].' ('.$Dist[0].'km)';
								$Lieux.='<option value='.$data[0].'>'.$idata.'</option>';
							}
						}
						mysqli_free_result($result);
					}
					else
						$mes.="Erreur d'import de données.";
					if(!$Lieux)
						$voyage=false;
					else
					{
						$titre='Vous avez planifié une mission de <b>'.$Mission_Type.'</b>. Quel sera votre objectif ?';
						$voyage=true;
					}
				}
				else
				{
					$mes.="<p>Aucun commando n'est disponible!</p>";
					$voyage=false;
				}
			}
			elseif($Type_Mission ==23) //Ravito
			{
				$query="SELECT DISTINCT Lieu.ID,Lieu.Nom,Lieu.Latitude,Lieu.Longitude FROM Lieu,Pays WHERE Lieu.Flag=Pays.Pays_ID AND Pays.Faction='$Faction' 
				AND Lieu.BaseAerienne >0 AND Lieu.Zone<>6 AND Lieu.ID<>'$Base' ORDER BY Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieuxravit');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data[3],$data[2]);
						$Dist_chk=($Dist[0]*2)+200;
						if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a or $Dist_chk <$Avion4_a or $Dist_chk <$Avion5_a)
						{
							$idata=$data[1].' ('.$Dist[0].'km)';
							$Lieux.='<option value='.$data[0].'>'.$idata.'</option>';
						}
					}
					mysqli_free_result($result);
				}
				else
					$mes.='Erreur d\'import de données.';
				if(!$Lieux)
					$voyage=false;
				else
				{
					$titre='Vous avez planifié une mission de <b>'.$Mission_Type.'</b>. Quel sera votre objectif ?';
					$voyage=true;
				}
			}
			elseif(($Type_Mission >40 and $Type_Mission <50) or ($Type_Mission >400 and $Type_Mission <440))
			{
				/*$Lieux_exclus[]=$Mission_Lieu_D;
				if($Officier >0)
					$Lieux_exclus[]=GetData("Officier","ID",$Officier,"Mission_Lieu_D");
				if($Officier >0)
					$Lieu_Reg_Off1=GetData("Regiment","Officier_ID",$Officier,"Lieu_ID");*/
				$Lieu_Reg_Off=$Lieu_Reg_Off1.",".$Lieu_Reg_Off2;
				$Coord=GetCoord($Front);
				$Lat_base_min=$Coord[0];
				$Lat_base_max=$Coord[1];
				$Long_base_min=$Coord[2];
				$Long_base_max=$Coord[3];
                if($Lat_base <47 and $Long_base >7){
                    $Lat_base_max=46.5;
                }
                elseif($Lat_base >47 and $Long_base >7){
                    $Lat_base_min=46.5;
                }
				$Type_Mission=substr($Type_Mission,1);
				if($Type_Mission ==7 or $Type_Mission ==17)
				{
					$query="SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p,Officier as r 
					WHERE r.Mission_Lieu_D=l.ID AND r.Pays=p.ID AND r.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND r.Front='$Front' AND r.ID NOT IN ('$Officier') AND l.ID NOT IN('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p,Regiment_IA as r
					WHERE r.Mission_Lieu_D=l.ID AND r.Pays=p.ID AND r.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pilote as j,Unit as u,Pays as p
					WHERE j.Unit=u.ID AND p.ID=u.Pays AND u.Mission_Lieu_D=l.ID AND u.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND j.Front='$Front' AND u.ID<>'$Unite' AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Attaque as a WHERE a.Lieu=l.ID AND DATE(a.Date)=DATE(NOW()) AND l.Flag='$country' 
					AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Bombardement as b WHERE b.Lieu=l.ID AND DATE(b.Date)=DATE(NOW()) AND l.Flag='$country' 
					AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					ORDER BY Nom ASC";
				}
				elseif($Type_Mission ==1 or $Type_Mission == 2 or $Type_Mission == 23) //Coop Terrestre
				{
					$query="SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p,Officier as r 
					WHERE r.Mission_Lieu_D=l.ID AND r.Pays=p.ID AND r.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND r.Front='$Front' AND l.Zone<>6 AND r.ID NOT IN ('$Officier') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p,Regiment_IA as r
					WHERE r.Mission_Lieu_D=l.ID AND r.Pays=p.ID AND r.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pilote as j,Unit as u,Pays as p 
					WHERE j.Unit=u.ID AND p.ID=u.Pays AND u.Mission_Lieu_D=l.ID AND u.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND j.Front='$Front' AND l.Zone<>6 AND u.ID<>'$Unite' AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					ORDER BY Nom ASC";
				}
				elseif($Type_Mission ==11 or $Type_Mission ==12 or $Type_Mission ==13 or $Type_Mission ==15) //Coop Navale
				{
					$query="SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p,Officier as r 
					WHERE r.Mission_Lieu_D=l.ID AND r.Pays=p.ID AND r.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND r.Front='$Front' AND r.ID NOT IN ('$Officier') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p,Regiment_IA as r
					WHERE r.Mission_Lieu_D=l.ID AND r.Pays=p.ID AND r.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pilote as j,Unit as u,Pays as p 
					WHERE j.Unit=u.ID AND p.ID=u.Pays AND u.Mission_Lieu_D=l.ID AND u.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND j.Front='$Front' AND u.ID<>'$Unite' AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					ORDER BY Nom ASC";
				}
				else
				{
					$query="SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p,Officier as r 
					WHERE r.Mission_Lieu_D=l.ID AND r.Pays=p.ID AND r.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND r.Front='$Front' AND r.ID NOT IN ('$Officier') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pays as p,Regiment_IA as r
					WHERE r.Mission_Lieu_D=l.ID AND r.Pays=p.ID AND r.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					UNION SELECT l.ID,l.Nom,l.Latitude,l.Longitude FROM Lieu as l,Pilote as j,Unit as u,Pays as p 
					WHERE j.Unit=u.ID AND p.ID=u.Pays AND u.Mission_Lieu_D=l.ID AND u.Mission_Type_D='$Type_Mission' AND p.Faction='$Faction' AND j.Front='$Front' AND u.ID<>'$Unite' AND l.ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2')
					ORDER BY Nom ASC";
				}
				$con=dbconnecti();
				$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieuxcoop');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						/*if(!in_array($data[0],$Lieux_exclus))
						{*/
							$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data[3],$data[2]);
							$Dist_chk=($Dist[0]*2)+100;
							if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a or $Dist_chk <$Avion4_a or $Dist_chk <$Avion5_a)
							{
								$idata=$data[1].' ('.$Dist[0].'km)';
								$Lieux.='<option value='.$data[0].'>'.$idata.'</option>';
							}
						//}
					}
					mysqli_free_result($result);
				}
				else
					$mes.='Erreur d\'import de données.';
				if(!$Lieux)
					$voyage=false;
				else
				{
					$titre='Vous avez planifié une mission de <b>'.$Mission_Type.'</b>. Quel sera votre objectif ?';
					$voyage=true;
				}
			}
			elseif($Type_Mission ==5 and ($Unite_Type ==3 or $Unite_Type ==9))
			{
				if($Front ==3)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >67 ORDER BY Nom ASC";
				elseif($Front ==1 or $Front ==4)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >13.35 AND Longitude <50 AND Latitude >43 ORDER BY Nom ASC";
				elseif($Front ==2)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <50 AND Latitude <43 ORDER BY Nom ASC";
				elseif($Lat_base >60)
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <60 AND Latitude >=43 ORDER BY Nom ASC";
				else
					$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <=14 AND Latitude >=43 ORDER BY Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieuxrecce');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
					{
						$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data['Longitude'],$data['Latitude']);
						$Dist_chk=($Dist[0]*2)+200;
						if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a or $Dist_chk <$Avion4_a or $Dist_chk <$Avion5_a)
						{
							$idata=$data['Nom'].' ('.$Dist[0].'km)';
							$Lieux.='<option value='.$data['ID'].'>'.$idata.'</option>';
						}
					}
					mysqli_free_result($result);
				}
				else
					$mes.="Erreur d'import de données.";
				if(!$Lieux)
					$voyage=false;
				else
				{
					$titre='Vous avez planifié une mission de <b>'.$Mission_Type.'</b>. Quel sera votre objectif ?';
					$voyage=true;
				}
			}			
			else
			{				
				if($Type_Mission !=4) //($Grade[1] >10 or $Commandant ==$PlayerID)
				{
					if($Front ==3)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >67 AND ID<>'$Base' AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' AND ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2') ORDER BY Nom ASC";
					elseif($Front ==1)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >=43 AND Latitude <50.5 AND Longitude >13 AND Longitude <50 AND ID<>'$Base' AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' AND ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2') ORDER BY Nom ASC";
					elseif($Front ==4)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >50.5 AND Longitude >13 AND Longitude <50 AND ID<>'$Base' AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' AND ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2') ORDER BY Nom ASC";
					elseif($Front ==5)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >60 AND Longitude >13 AND Longitude <50 AND ID<>'$Base' AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' AND ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2') ORDER BY Nom ASC";
					elseif($Front ==2)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude <43 AND Longitude <50 AND ID<>'$Base' AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' AND ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2') ORDER BY Nom ASC";
					else
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >=43 AND Latitude <58 AND Longitude <14 AND ID<>'$Base' AND Recce_PlayerID<>'$PlayerID' AND Recce_PlayerID_TAL<>'$PlayerID' AND Recce_PlayerID_TAX<>'$PlayerID' AND ID NOT IN ('$Lieu_Reg_Off1','$Lieu_Reg_Off2') ORDER BY Nom ASC";
					$con=dbconnecti();
					$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieuxcap');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
						{
							$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data['Longitude'],$data['Latitude']);
							$Dist_chk=($Dist[0]*2)+200;
							if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a or $Dist_chk <$Avion4_a or $Dist_chk <$Avion5_a)
							{
								$idata=$data['Nom'].' ('.$Dist[0].'km)';
								$Lieux.='<option value='.$data['ID'].'>'.$idata.'</option>';
							}
						}
						mysqli_free_result($result);
					}
					else
						$mes.='Erreur d\'import de données.';
					//Ajout 13/08/2014
					if($Type_Mission ==7 or $Type_Mission ==17)
						$Lieux.='<option value='.$Base.'>La défense de votre base '.$Terrain.'</option>';
					//End Ajout
					if(!$Lieux)
						$voyage=false;
					else
					{
						$titre='Vous avez planifié une mission de <b>'.$Mission_Type.'</b>. Quel sera votre objectif ?';
						$voyage=true;
						//$no_test_dist=true;
					}
				}
				else
				{
					if($Front ==3)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >67 AND Zone<>6 AND ID<>'$Base' ORDER BY RAND()";
					elseif($Front ==1)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >=43 AND Latitude <=50.5 AND Longitude >13 AND Longitude <50 AND Zone<>6 AND ID<>'$Base' ORDER BY RAND()";
					elseif($Front ==4)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >50.5 AND Longitude >13 AND Longitude <50 AND Zone<>6 AND ID<>'$Base' ORDER BY RAND()";
					elseif($Front ==5)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >60 AND Longitude >13 AND Longitude <50 AND Zone<>6 AND ID<>'$Base' ORDER BY RAND()";
					elseif($Front ==2)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude <43 AND Longitude <50 AND Zone<>6 AND ID<>'$Base' ORDER BY RAND()";
					else
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >=43 AND Latitude <58 AND Longitude <14 AND Zone<>6 AND ID<>'$Base' ORDER BY RAND()";
					$con=dbconnecti();
					$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-lieuxpat');			
					mysqli_close($con);
					//Destination aléatoire
					if($result)
					{
						$voyage=false;
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data['Longitude'],$data['Latitude']);
							$Dist_chk=($Dist[0]*2)+200;
							if($Dist_chk <$Avion1_a or $Dist_chk <$Avion2_a or $Dist_chk <$Avion3_a or $Dist_chk <$Avion4_a or $Dist_chk <$Avion5_a)
							{
								$idata=$data['Nom'].' ('.$Dist[0].'km)';
								$Lieux.='<option value='.$data['ID'].'>'.$idata.'</option>';
								$voyage=true;
								break;
							}
						}
						mysqli_free_result($result);
					}
					else
						$mes.='Erreur d\'import de données.';
					if($voyage)
					{
						if($Grade[1] >4)
							$titre='Vous avez planifié une mission de <b>'.$Mission_Type.'</b> en direction de <b>'.$data['Nom'].'</b>.';
						else
							$titre='Votre commandant vous a désigné pour une mission de <b>'.$Mission_Type.'</b> en direction de <b>'.$data['Nom'].'</b>.';
					}
				} //Cpt
			} //TypeMission
		} //Interdiction de vol			
		//Affichage		
		if($Porte_avions)
			$img.='<img src=\'images/taxip'.$country.'.jpg\' style=\'width:100%;\'>';
		else
			$img.='<img src=\'images/taxi'.$country.'.jpg\' style=\'width:100%;\'>';		
		if($AvionT1_Nbr <1 and $AvionT2_Nbr <1 and $AvionT3_Nbr <1)
		{
			$mes.="<p>Aucun avion de l'unité n'est opérationnel, par manque de stock, de carburant ou de munitions.<br>Veillez au <a href='index.php?view=esc_gestion' class='lien'>stock</a> de votre escadrille.</p>";
			$voyage=false;
		}		
		if($Grade[1] <2 and !$AvionT1_Nbr)
		{
			if($Interdit)
				$mes.='<p>Votre commandant n\'apprécie guère que vous perdiez tous ses avions par manque de prudence!<br>Vous êtes interdit de vol jusqu\'à ce que vous vous calmiez un peu!</p>';	
			else
				$mes.='<td>Avion non disponible<br>par manque de carburant ou de munitions.<br>Contactez votre Staff pour tout problème concernant la gestion.</td>';
			$Avions_Select_txt='';
			$voyage=false;	
		}	
		if($voyage and !$finmission)
		{
			//Set Session Vars
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET S_Mission='$Type_Mission',S_Longitude='$Long_base',S_Latitude='$Lat_base' WHERE ID='$PlayerID'")
			or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : mission1-reset2');
			mysqli_close($con);
			//Avion imposé pour les débutants
			if($Grade[1] <2 and $Unite_Type !=8)
				$Avions_Select_txt='<option value='.$AvionT1.'>'.$Avion1.'</option>';
			else
				$Avions_Select_txt=$Avions_Select_txt_T1.$Avions_Select_txt_T2.$Avions_Select_txt_T3.$Avions_Select_txt_T4.$Avions_Select_txt_T5;			
			$_SESSION['Cr']=true;
			$mes.="<form action='takeoff.php' method='post'>
			<input type='hidden' name='Pilote' value='".$PlayerID."'>
			<input type='hidden' name='Base' value='".$Base."'>
			<input type='hidden' name='a1' value='".$Avion1_a."'>
			<input type='hidden' name='a2' value='".$Avion2_a."'>
			<input type='hidden' name='a3' value='".$Avion3_a."'>
			<input type='hidden' name='an1' value='".$AvionT1_Nbr_End."'>
			<input type='hidden' name='an2' value='".$AvionT2_Nbr_End."'>
			<input type='hidden' name='an3' value='".$AvionT3_Nbr_End."'>
			<input type='hidden' name='Type_M' value=".$Type_Mission.">
			<input type='hidden' name='Crm' value=".$Cr_Mission.">
			<table class='table'>
				<thead><tr><th>Destination <a href='#' class='popup'><img src='images/help.png'><span>La destination est parfois imposée par votre hiérarchie selon la mission choisie. Certaines missions et plus tard certains grades vous permettront de choisir votre destination de vol</span></a></th></tr></thead>
				<tr><td><select name='Cible' class='form-control' style='width: 300px'>".$Lieux."</select></td></tr></table>
			<table class='table table-striped'>
				<thead><tr><th>Avion</th><th>Disponibles</th><th title='Distance maximale pouvant être parcourue par votre avion'>Autonomie <a href='#' class='popup'><img src='images/help.png'><span>Si plusieurs modèles différents composent votre formation, la valeur la plus faible sera utilisée pour déterminer la distance franchissable.</span></a></th><th>Robustesse</th><th>Vitesse max</th><th>Plafond</th><th>Armement</th><th>Soute</th></tr></thead>";
			if($AvionT1_Nbr)
				$mes.="<tr><td>".$AvionT1_txt."</td><td>".$AvionT1_Dispo."</td><td>".$Avion1_a."km</td><td>".$Avion1_r."</td><td>".$Avion1_v."km/h</td><td>".$Avion1_pl."m</td><td>".$Avion1_ap_txt."</td><td>".$Avion1_Bombe_txt."</td></tr>";
			if($AvionT2_Nbr)
				$mes.="<tr><td>".$AvionT2_txt."</td><td>".$AvionT2_Dispo."</td><td>".$Avion2_a."km</td><td>".$Avion2_r."</td><td>".$Avion2_v."km/h</td><td>".$Avion2_pl."m</td><td>".$Avion2_ap_txt."</td><td>".$Avion2_Bombe_txt."</td></tr>";
			if($AvionT3_Nbr)
				$mes.="<tr><td>".$AvionT3_txt."</td><td>".$AvionT3_Dispo."</td><td>".$Avion3_a."km</td><td>".$Avion3_r."</td><td>".$Avion3_v."km/h</td><td>".$Avion3_pl."m</td><td>".$Avion3_ap_txt."</td><td>".$Avion3_Bombe_txt."</td></tr>";
			if($AvionT5_txt)
				$mes.="<tr><td>".$AvionT5_txt." <a href='#' class='popup'><img src='images/help.png'><span>Si cet avion est choisi, les pilotes utiliseront le ".$Sqn." 1, puis le 2 et ensuite le 3. Veillez à ne pas sélectionner plus de pilotes que d'avions disponibles, sinon aucun ne partira en mission avec vous!</span></a></td><td>1</td><td>".$Avion5_a."km</td><td>".$Avion5_r."</td><td>".$Avion5_v."</td><td>".$Avion5_pl."</td><td>".$Avion5_ap_txt."</td><td>".$Avion5_Bombe_txt."</td></tr>";
			if($AvionT4_txt)
				$mes.="<tr><td>".$AvionT4_txt." <a href='#' class='popup'><img src='images/help.png'><span>Si cet avion est choisi, les pilotes utiliseront le ".$Sqn." 1, puis le 2 et ensuite le 3. Veillez à ne pas sélectionner plus de pilotes que d'avions disponibles, sinon aucun ne partira en mission avec vous!</span></a></td><td>1</td><td>".$Avion4_a."km</td><td>".$Avion4_r."</td><td>".$Avion4_v."</td><td>".$Avion4_pl."</td><td>".$Avion4_ap_txt."</td><td>".$Avion4_Bombe_txt."</td></tr>";
			$mes.="</table>";
			if($Type_Mission ==4 or $Type_Mission ==6 or $Type_Mission ==7 or $Type_Mission ==8 or $Type_Mission ==16 or $Type_Mission ==17 or $Type_Mission ==21 or $Type_Mission ==23 or $Type_Mission ==24 or $Type_Mission ==25 or $Type_Mission ==26)
			{
				$query="SELECT ID,Nom,Pays,Unit,Avancement,Pilotage,Acrobatie,Tir,Tactique,Navigation,Vue,Moral,Courage,Reputation FROM Pilote_IA 
				WHERE Unit='$Unite' AND Task=0 AND Cible=0 AND Couverture=0 AND Couverture_Nuit=0 AND Escorte=0 AND Actif=1 AND Moral>0 AND Courage>0 ORDER BY Avancement DESC,Reputation DESC LIMIT 20";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($Data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Avancement=GetAvancement($Data['Avancement'],$country);
						$Reputation=GetReputation($Data['Reputation'],$country);
						$pilotes_txt.="<tr><td>".$Data['Nom']."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$country.$Avancement[1].".png'></td><td>".$Reputation."</td><td>"
						.GetSkillTxt($Data['Pilotage'])."</td><td>".GetSkillTxt($Data['Acrobatie'])."</td><td>".GetSkillTxt($Data['Tir'])."</td><td>".GetSkillTxt($Data['Tactique'])."</td><td>"
						.GetSkillTxt($Data['Navigation'])."</td><td>".GetSkillTxt($Data['Vue'])."</td><td>"
						.GetMoralTxt($Data['Moral'])."</td><td>".GetCourageTxt($Data['Courage'])."</td><td><input type='checkbox' name='ia_pilots[]' value='".$Data['ID']."'></td><tr>";
					}
					mysqli_free_result($result);
				}
				$mes.="<h2>Pilotes</h2><div style='overflow:auto;'><table class='table table-hover'><thead><tr><th>Nom</th><th>Grade</th><th>Reputation</th>
				<th>Pilotage</th><th>Acrobatie</th><th>Tir</th><th>Tactique</th><th>Navigation</th><th>Détection</th>
				<th>Moral</th><th>Courage</th><th>Choisir <a href='#' class='popup'><img src='images/help.png'><span>Plusieurs pilotes peuvent être sélectionnés</span></a></th></tr></thead><tbody>".$pilotes_txt."</tbody></table></div>";
			}
			if($AvionT1_Nbr or $AvionT2_Nbr or $AvionT3_Nbr)
				$menu="<input type='Submit' value='VALIDER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			$menu.="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='99'>
			<input type='Submit' value='ANNULER LA MISSION' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>
			<p class='lead'>Si vous annulez à cet instant, cela vous coûte <img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><br>Vous récupérez la mission dépensée ainsi que les éventuels CT excédants</p>";
			$skills=$popup_txt;
		}
		else
		{
			if(!$Chk_Mission)
			{
				MoveCredits($PlayerID,8,$Cr_Mission);
				UpdateCarac($PlayerID,"Missions_Jour",-1);
				UpdateCarac($PlayerID,"Missions_Max",-1);
			}
			$_SESSION['Cr']=true;
			$_SESSION['Distance']=0;
			SetData("Pilote","S_Mission",98,"ID",$PlayerID);
			if(!$mes)
			{
				$mes='<p>Votre escadrille ne dispose actuellement d\'aucun appareil permettant d\'atteindre l\'objectif désigné par le haut commandement.
				<br>Soit vous êtes trop loin du front, soit aucune reconnaissance n\'a été effectuée par votre faction.</p>';
			}
			$menu="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='98'>
			<input type='Submit' value='ANNULER LA MISSION' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}
	else
	{
		$_SESSION['Distance']=0;
		$_SESSION['Cr']=true;
		if(!$Chk_Mission)
		{
			MoveCredits($PlayerID,8,4);
			UpdateCarac($PlayerID,"Missions_Jour",-1);
			UpdateCarac($PlayerID,"Missions_Max",-1);
		}
		else
		{
			$mes=GetMes("init_mission");
			$view='login';
			session_unset();
			session_destroy();
		}
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
/*Time
$time=microtime();
$time=explode(' ',$time);
$time=$time[1]+$time[0];
$finish=$time;
$total_time=round(($finish-$start),4);
$skills.='<br>Page generated in '.$total_time.' seconds.';*/
usleep(1);
include_once('./index.php');