<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
/*if($PlayerID ==1 or $PlayerID ==2)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
$Action=Insec($_POST['Action']);
$MIA=Insec($_POST['MIA']);
$meteo=Insec($_POST['meteo']);
$Avion_det1=Insec($_POST['Av1']);
$Avion_det2=Insec($_POST['Av2']);
$Avion_det3=Insec($_POST['Av3']);
$Unitsa=Insec($_POST['Unitsa']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $_SESSION['mia2'] ==false AND $MIA >0 AND !empty($_POST))
{	
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_map.inc.php');
	$_SESSION['mia2'] =true;
	$_SESSION['mia_status'] =false;
	$Credits=false;	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Unit,Equipage,Reputation,Renseignement,Courage,Moral,S_Nuit,Slot11 FROM Pilote WHERE ID='$PlayerID'");
	$result2=mysqli_query($con,"SELECT,ValeurStrat,Camouflage,BaseAerienne,QualitePiste,Zone,Flag FROM Lieu WHERE ID='$MIA'");
	mysqli_close($con);
	if($result2)
	{
		while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$ValeurStrat=$data2['ValeurStrat'];
			$Camouflage=$data2['Camouflage'];
			$BaseAerienne=$data2['BaseAerienne'];
			$QualitePiste=$data2['QualitePiste'];
			$Zone=$data2['Zone'];
			$Flag=$data2['Flag'];
		}
		mysqli_free_result($result2);
	}
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unite=$data['Unit'];
			$Equipage=$data['Equipage'];
			$Reputation=$data['Reputation'];
			$Renseignement=$data['Renseignement'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Nuit=$data['S_Nuit'];
			$Slot11=$data['Slot11'];
		}
		mysqli_free_result($result);
	}
	if($Equipage)$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");
	if($Equipage and $Endu_Eq >0)
	{
		$Equipage_Nom=GetData("Equipage","ID",$Equipage,"Nom");
		$Courage_Eq=GetData("Equipage","ID",$Equipage,"Courage");
		//$Trait=GetData("Equipage","ID",$Equipage,"Trait");
	}
	$infos=false;
	$retour=false;
	$sabotage=false;
	$end_mission=false;
	switch($Action)
	{
		//Ville
		case 1:
			$Rens=$Renseignement-($Nuit*100);
			if($Rens >0)
			{
				$Renseignement/=2;
				$infos=true;
			}
			else
				$mes.="<p>Vous ne parvenez à rien découvrir d'intéressant en ville.</p>";
			$img=Afficher_Image("images/lieu/objectif_nuit5.jpg","images/image.png","Nuit");
			$Credits=-2;
		break;
		case 2:
			$Rens=$Renseignement-($Nuit*100);
			if($Rens >0)
				$infos=true;
			else
				$mes.="<p>Vous ne parvenez à rien découvrir d'intéressant en ville.</p>";
			$img=Afficher_Image("images/lieu/objectif_nuit5.jpg","images/image.png","Nuit");
			$Credits=-4;
		break;
		case 3:
			$avion_escape=$Avion_det1;
			$rescue=true;
			$Credits=-1;
			SetData("Pilote","S_Mission",127,"ID",$PlayerID);
		break;
		case 4: 
			$avion_escape=$Avion_det2;
			$rescue=true;
			$Credits=-1;
			SetData("Pilote","S_Mission",127,"ID",$PlayerID);
		break;
		case 5:
			$avion_escape=$Avion_det3;		
			$rescue=true;
			$Credits=-1;
			SetData("Pilote","S_Mission",127,"ID",$PlayerID);
		break;
		//Sabotage
		case 6:
			$sabotage=true;
			$Credits=-12;
			UpdateData("Unit","Avion1_Nbr",-1,"ID",$Unitsa);
			$mes.="<p>Votre sabotage a réussi, l'avion ennemi est détruit!</p>";
			AddEvent("Avion",120,GetData("Unit","ID",$Unitsa,"Avion1"),$PlayerID,$Unitsa,$MIA,1,$Unite);
		break;
		case 7:
			$sabotage=true;
			$Credits=-12;
			UpdateData("Unit","Avion2_Nbr",-1,"ID",$Unitsa);
			$mes.="<p>Votre sabotage a réussi, l'avion ennemi est détruit!</p>";
			AddEvent("Avion",120,GetData("Unit","ID",$Unitsa,"Avion2"),$PlayerID,$Unitsa,$MIA,1,$Unite);
		break;
		case 8:
			$sabotage=true;
			$Credits=-12;
			UpdateData("Unit","Avion3_Nbr",-1,"ID",$Unitsa);
			$mes.="<p>Votre sabotage a réussi, l'avion ennemi est détruit!</p>";
			AddEvent("Avion",120,GetData("Unit","ID",$Unitsa,"Avion3"),$PlayerID,$Unitsa,$MIA,1,$Unite);
		break;
		case 9:
			$sabotage=true;
			$Credits=-4;
			/*Matos random
			$con=dbconnecti(1);
			$Item=mysqli_result(mysqli_query($con,"SELECT ID FROM Matos WHERE Pays IN (0,'$Flag') AND Reput_mini <='$Reputation' ORDER BY RAND() LIMIT 1"),0);
			mysqli_close($con);
			AddToCoffre($Unite,$Item);
			AddEvent("Avion",124,44,$PlayerID,$Unite,$MIA,$Item);*/
			$img=Afficher_Image("images/mia_depot.jpg","images/image.png","Campement");
			$mes.="<p>Votre vol a réussi, vous récupérez une caisse d'équipement!</p>";
		break;
		case 11:
			$sabotage=true;
			$Credits=-8;
			UpdateData("Lieu","DefenseAA_temp",-1,"ID",$MIA);
			UpdateData("Lieu","Camouflage",-10,"ID",$MIA);
			$mes.="<p>Votre sabotage a réussi, le canon de DCA ennemi est détruit!</p>";
			AddEvent("Avion",123,44,$PlayerID,$Unitsa,$MIA,1,$Unite);
		break;
		case 12:
			$sabotage=true;
			$Credits=-12;
			$octane=mt_rand(0,3);
			$Brule=mt_rand(-5000,0);
			if($Slot11 ==75)
				$Brule*=2;
			elseif($Slot11 ==76)
				$Brule*=4;
			if($octane ==3)
				$octane=1;
			elseif($octane ==2)
				$octane=130;
			elseif($octane ==1)
				$octane=100;
			else
				$octane=87;
			UpdateData("Unit","Stock_Essence_".$octane,$Brule,"ID",$Unitsa);
			UpdateData("Lieu","Camouflage",-10,"ID",$MIA);
			$mes.="<p>Votre sabotage a réussi, le stock de carburant est en feu!</p>";
			AddEvent("Avion",121,44,$PlayerID,$Unitsa,$MIA,-$Brule,$Unite);
		break;
		case 13:
			$sabotage=true;
			$Credits=-12;
			$muns=mt_rand(0,3);
			$Brule=mt_rand(-5000,0);
			if($Slot11 ==75)
				$Brule*=2;
			elseif($Slot11 ==76)
				$Brule*=4;
			if($muns == 3)
				$muns=30;
			elseif($muns ==2)
				$muns=20;
			elseif($muns ==1)
				$muns=13;
			else
				$muns=8;
			UpdateData("Unit","Stock_Munitions_".$muns,$Brule,"ID",$Unitsa);
			UpdateData("Lieu","Camouflage",-10,"ID",$MIA);
			$mes.="<p>Votre sabotage a réussi, le stock de munitions est en feu!</p>";
			AddEvent("Avion",122,44,$PlayerID,$Unitsa,$MIA,-$Brule,$Unite);
		break;
		//Retour
		case 10:
			$mes.="<p>Vous revenez à votre campement.</p>";
			$img=Afficher_Image("images/mia_tent.jpg","images/image.png","Campement");
			$Credits=-1;
		break;
	}		
	$credits_txt=MoveCredits($PlayerID,1,$Credits);	
	if($sabotage)
	{
		if($Slot11 ==75 or $Slot11 ==76)
			SetData("Pilote","Slot11",0,"ID",$PlayerID);
		UpdateCarac($PlayerID,"Avancement",-$Credits);
		UpdateCarac($PlayerID,"Reputation",-$Credits);
		UpdateCarac($PlayerID,"Moral",50);
		UpdateCarac($PlayerID,"Courage",100);
		if(!$img)$img=Afficher_Image("images/explosion_nuit.jpg","images/image.png","Campement");
	}	
	if($rescue)
	{
		//GetData Avion	Escape	
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Robustesse,Engine_Nbr,Train,Helice,ChargeAlaire,ManoeuvreB,ManoeuvreH,Maniabilite FROM Avion WHERE ID='$avion_escape'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$NomAvion=$data['Nom'];
				$HP=$data['Robustesse'];
				$Engine_Nbr=$data['Engine_Nbr'];
				$ChargeAlaire=$data['ChargeAlaire'];
				$Helice=$data['Helice'];
				$Train=$data['Train'];
				$ManB=$data['ManoeuvreB'];
				$ManH=$data['ManoeuvreH'];
				$Mani=$data['Maniabilite'];
			}
			mysqli_free_result($result);
			unset($data);
		}		
		$ManoeuvreB=GetMano($ManB,$ManH,$HP,$HP,1,1,1,$flaps);
		$Mani=GetMani($Mani,$HP,$HP,1,1,$flaps);
		$Pilotage=GetPilotage("Avion",$PlayerID,$avion_escape);				
		if(!$QualitePiste)
		{
			switch($Zone)
			{
				case 0:
					$mes.="En rase campagne";
					$QualitePiste=mt_rand(20,80);
				break;
				case 1:
					$mes.="Dans ces collines";
					$QualitePiste=mt_rand(10,70);
				break;
				case 2:
					$mes.="Dans une clairière";
					$QualitePiste=mt_rand(10,80);
				break;
				case 3:
					$mes.="Dans ces collines boisées";
					$QualitePiste=mt_rand(0,60);
				break;
				case 4:
					$mes.="Dans ces montagnes";
					$QualitePiste=mt_rand(0,30);
				break;
				case 5:
					$mes.="Dans ces montagnes boisées";
					$QualitePiste=mt_rand(0,10);
				break;
				case 6:
					$mes.="<br>Depuis le plan d'eau";
					$QualitePiste=mt_rand(90,100) - $meteo;
				break;
				case 7:
					$mes.="Depuis la zone urbaine";
					$QualitePiste=mt_rand(10,90);
				break;
				case 8:
					$mes.="En plein désert";
					$QualitePiste=mt_rand(20,80);
				break;
			}
		}
		else
			$ChargeAlaire=0;
		$Piste=100-$QualitePiste;
		//Decollage
		$Decollage=mt_rand(10,$Pilotage) + $meteo + ($ManoeuvreB/10) - ($ChargeAlaire/20) - $Piste + ($Moral/10) + ($Courage/10) + ($Helice*5) + ($Train*5);
		$mes.=', vous vous préparez à décoller aux commandes du <b>'.$NomAvion.'</b></p>';
		if($Decollage >0)
		{
			if($PlayerID ==1 or $PlayerID ==2)
			{
				$skills.="<br>[Score au décollage: ".$Decollage."]
				<br>(Pilotage: ".$Pilotage."; Qualité de la piste : -".$Piste."; ChargeAlaire /20: -".$ChargeAlaire."; Manoeuvrabilité de l'avion /10: ".$ManoeuvreB.")";
			}
			$mes.="<p><b>Vous décollez sans problème.</b></p>";
			$img=Afficher_Image('images/avions/decollage'.$avion_escape.'.jpg','images/avions/vol'.$avion_escape.'.jpg',$NomAvion);
			//UpdateCarac($PlayerID,"Pilotage",2);
			AddPilotage("Avion",$avion_escape,$PlayerID,1);
			UpdateCarac($PlayerID,"Reputation",2);
			UpdateCarac($PlayerID,"Moral",10);
			$retour=true;
		}
		elseif($Decollage <-50)
		{
			if($PlayerID ==1 or $PlayerID ==2)
			{
				$skills.="<br>[Score au décollage: ".$Decollage."]
				<br>(Pilotage: ".$Pilotage."; Qualité de la piste : -".$Piste."; ChargeAlaire /10: -".$ChargeAlaire."; Manoeuvrabilité de l'avion /10: ".$ManoeuvreB.")";
			}
			$mes.="<p>Vous entamez votre course de décollage, mais l'état lamentable du sol vous fait capoter, écrasant votre appareil dans un arbre. Quelle poisse !</p>
			<p>Votre appareil est gravement endommagé, c'est une perte totale !</p>";
			$img.="<img src='images/crash".$avion_escape.".jpg' style='width:100%;'>";
			//AddEvent($Avion_db,12,$avion,$PlayerID,$Unite,$base,1);
			//SetData("Pilote","Credits",0,"ID",$PlayerID);
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Endurance=Endurance-1,Moral=Moral-5,Reputation=Reputation-1,MIA='$MIA',Missions_Max=6,Commando=0 WHERE ID='$PlayerID'");
			mysqli_close($con);	
			AddEvent("Avion",35,$avion_escape,$PlayerID,$Unite,$MIA);
			UpdateCarac($PlayerID,"Crashs_Jour",1);
			//Eviter abattu DCA
			$HP=1;
			$end_mission=true;
		}
		else
		{
			if($PlayerID ==1 or $PlayerID ==2)
			{
				$skills.="<br>[Score au décollage: ".$Decollage."]
				<br>(Pilotage: ".$Pilotage."; Qualité de la piste : -".$Piste."; ChargeAlaire /10: -".$ChargeAlaire."; Manoeuvrabilité de l'avion /10: ".$ManoeuvreB.")";
			}
			$mes.="<p>Vous parvenez à décoller malgré l'état lamentable du sol. Il s'en est fallu de peu !<br>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>";
			$HP=$HP-mt_rand(1,50);
			if($HP < 1)$HP=1;
			$img.="<img src='images/crash".$avion_escape.".jpg' style='width:100%;'>";
			//AddEvent($Avion_db,12,$avion,$PlayerID,$Unite,$base,0);
			UpdateCarac($PlayerID,"Endurance",-1);
			UpdateCarac($PlayerID,"Moral",-5);
			$retour=true;
		}
	}
	if($infos)
	{
		//Zone Objectif
		switch($Zone)
		{
			case 0:
				$zone_txt="de plaines";
				$Malus_Reperer=0;
			break;
			case 1:
				$zone_txt="vallonnée";
				$Malus_Reperer=10;
			break;
			case 2:
				$zone_txt="forestière";
				$Malus_Reperer=20;
			break;
			case 3:
				$zone_txt="de collines boisées";
				$Malus_Reperer=50;
			break;
			case 4:
				$zone_txt="montagneuse";
				$Malus_Reperer=50;
			break;
			case 5:
				$zone_txt="de montagnes boisées";
				$Malus_Reperer=100;
			break;
			case 6:
				$mes="maritime";
				$Malus_Reperer=0;
			break;
			case 7:
				$zone_txt="urbaine";
				$Malus_Reperer=50;
			break;
			case 8:
				$zone_txt="désertique";
				$Malus_Reperer=0;
			break;
			case 9:
				$zone_txt="de jungle";
				$Malus_Reperer=30;
			break;
			case 11:
				$zone_txt="marécageuse";
				$Malus_Reperer=10;
			break;
		}
		$Shoot=$Renseignement + $meteo + ($Courage/10) - $Malus_Reperer;			
		if($Shoot >0)
		{
			UpdateCarac($PlayerID, "Renseignement", 1);
			$aa_type="aucune présence de défenses ennemies.";
			$Cible_nom=GetData("Lieu","ID",$MIA,"Nom");
			$Cible_indus=GetData("Lieu","ID",$MIA,"Industrie");
			if($Cible_indus >0)
			{
				if($Shoot >25)
				{
					if($Cible_indus <100)
						$Cible_ind_txt="<br>une zone industrielle endommagée";
				}
				else
					$Cible_ind_txt="<br>une zone industrielle";
				if($Shoot >100)
				{
					$con=dbconnecti();
					$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
					$resultj=mysqli_query($con,"SELECT ID,Nom FROM Avion WHERE Etat=1 AND Fin_Prod >'$Date_Campagne' AND (Usine1='$MIA' OR Usine2='$MIA' OR Usine3='$MIA')");
					mysqli_close($con);
					if($resultj)
					{
						while($Classement=mysqli_fetch_array($resultj,MYSQLI_ASSOC)) 
						{
							if($Renseignement >150)
								$Cible_avions_txt.="<p><img src='images/avions/garage".$Classement['ID'].".jpg' title='".$Classement['Nom']."'></p>";
							else
								$Cible_avions_txt.="<p><img src='images/avions/garage".$Classement['ID'].".jpg' title='Non identifié'></p>";
						}
					}
					if($Cible_avions_txt)
						$Cible_avions_txt=", où vous repérez les avions suivants : ".$Cible_avions_txt;
					$Cible_ind_txt.=$Cible_avions_txt;
				}
			}
			else
				$Cible_ind_txt="";
			if($Shoot >10+$Camouflage)
			{
				if($BaseAerienne >0)
				{
					if($Shoot >25)
					{
						if($QualitePiste <100)
							$Cible_base_txt="<br>un aérodrome endommagé";
						else
							$Cible_base_txt="<br>un aérodrome";
					}
					else
						$Cible_base_txt="<br>un aérodrome";
				}
				else
					$Cible_base_txt="";
			}				
			if($Shoot >25+$Camouflage)
			{
				$Cible_DefenseAA=GetData("Lieu","ID",$MIA,"DefenseAA_temp");
				if($Cible_DefenseAA >4)
					$aa_type="des défenses anti-aériennes de gros calibre";
				elseif($Cible_DefenseAA >2)
					$aa_type="des défenses anti-aériennes de calibre moyen";
				elseif($Cible_DefenseAA >0)
					$aa_type="des défenses anti-aériennes de faible calibre";
				else
					$aa_type="une absence totale de défense anti-aérienne";
			}
			//Reco unités ennemies sur la base
			if($Shoot >50+$Camouflage)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID,Nom FROM Unit WHERE Base='$MIA'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
					{
						$Nom_unit=$Nom_unit." ".$data['Nom'];
						$ID_Unit=$data['ID'];
					}
					mysqli_free_result($result);
				}
				if($Nom_unit)
					$Unitz='<br>Vous repérez également les emblèmes des unités suivantes : '.$Nom_unit;
			}
			$mes.='<p>Vous parvenez à noter que <b>'.$Cible_nom.'</b> se trouve dans une zone '.$zone_txt.'<br> 
			Vous repérez clairement '.$aa_type.$Cible_ind_txt.$Cible_base_txt.$Unitz.'</p>';
			if(!$img)$img="<img src='images/mia_ville.jpg' style='width:100%;'>";
		}
	}	
	if($retour)
	{
		$alt=mt_rand(1000,5000);
		$essence=500+mt_rand(0,250);
		$Mun1=0;
		$Mun2=0;
		$Enis=0;
		//Chemin_Retour();
		$_SESSION['Distance'] =0;
		$_SESSION['naviguer'] =false;
		$_SESSION['PVP'] =false;
		$_SESSION['done'] =true;
		$chemin=$Distance;
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote SET S_Cible_Atk=0,S_Avion_db='Avion',MIA=0,S_Avion_Bombe=0,S_Avion_Bombe_Nbr=0,S_HP='$HP',S_Engine_Nbr='$Engine_Nbr',S_Mission=999,S_Essence='$essence' WHERE ID='$PlayerID'");
		mysqli_close($con);
		SetData("Pilote","S_Mission",127,"ID",$PlayerID);
		$mes.="<p>Vous prenez le chemin du retour en direction de votre base</p>";
		$Engine_Nbr=GetData("Avion","ID",$avion_escape,"Engine_Nbr");
		$Puissance=GetPuissance("Avion",$avion_escape,$alt,$HP,1,1,$Engine_Nbr);
		$menu.="<form action='nav.php' method='post'>
		<input type='hidden' name='Chemin' value=".$chemin.">
		<input type='hidden' name='Distance' value=".$Distance.">
		<input type='hidden' name='Meteo' value=".$meteo.">
		<input type='hidden' name='Avion' value=".$avion_escape.">
		<input type='hidden' name='Mun1' value=".$Mun1.">
		<input type='hidden' name='Mun2' value=".$Mun2.">
		<input type='hidden' name='Puissance' value=".$Puissance.">
		<input type='hidden' name='Enis' value=".$Enis.">
		<table class='table'>
			<tr><td colspan='8'>Chemin retour</td></tr>
			<tr>".ShowGaz($avion_escape,$c_gaz,$flaps,$alt)."</tr></table>
		<input type='Submit' value='CONTINUER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	else
	{
		$menu="<a title='Retour à votre campement' href='index.php?view=mission_start' class='btn btn-default'>Retour à votre campement</a>";
		$mes.="<p><b>FIN DE MISSION</b></p>";
	}	
	if($end_mission)
	{
		if($_SESSION['PVP'])
			RetireCandidat($PlayerID,"end_mission");
		$img="<img src='images/crash".$avion_escape.".jpg' style='width:100%;'>";
		if($HP <1)
		{
			//Tableau de chasse
			AddVictoire_atk($Avion_db,0,16,$avion_escape,$PlayerID,$Unite,$MIA,$Arme1,$country,1,$alt,$Nuit,$Degats);
			AddEvent($Avion_db,3,$avion,$PlayerID,$Unite,$MIA);
			UpdateCarac($PlayerID,"Crashs_Jour",1);
			UpdateCarac($PlayerID,"Abattu",1);
		}
		else
			AddEvent($Avion_db,34,$avion_escape,$PlayerID,$Unite,$MIA);
		UpdateCarac($PlayerID,"Reputation",-10);
		$mes.="<p><b>FIN DE MISSION</b></p>";
		$menu="<form action='promotion.php' method='post'>
			<input type='hidden'  name='Blesse'  value=".$blesse.">
			<input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
include_once('./index.php');
?>