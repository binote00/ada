<?php
require_once('./jfv_inc_sessions.php');
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_air_inc.php'); //OK
	include_once('./jfv_nav.inc.php'); //OK
	include_once('./jfv_avions.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Battle=Insec($_POST['Battle']);
	$Faction=Insec($_POST['Camp']);
	$Type_Mission=Insec($_POST['Type_M']);
	$Avion=Insec($_POST['Avion']);
	$Saison=3;
	$Chk_Decollage=$_SESSION['Decollage'];
	$_SESSION['done']=false;
	$finmission=false;
	$today=getdate();
	$Heure=$today['hours'];
	$Base=GetBasePVP($Battle,$Avion,$Faction);
	$Cible=GetCiblePVP($Battle);
	$avion_img=GetAvionImg("Avion",$Avion);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Reputation,S_Mission,Navigation,Vue,Moral,Courage,Avancement,Ailier,S_Ailier,Equipage,Pays,Heure_Mission,S_Longitude,S_Latitude,S_Chargeurs
	FROM Pilote_PVP WHERE ID='$Pilote_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff_pvp-player');
	$resulta=mysqli_query($con,"SELECT Nom,Robustesse,Type,VitesseA,Plafond,Autonomie,ArmePrincipale,Arme1_Nbr,Arme1_Mun,ArmeSecondaire,Arme2_Nbr,Arme2_Mun,Arme3_Nbr,Arme4_Nbr,Arme5_Nbr,Arme6_Nbr,Bombe,Bombe_Nbr,Avion_BombeT,Engine_Nbr,Train,Helice,ManoeuvreB,Equipage,Baby,Radio 
	FROM Avion WHERE ID='$Avion'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff_pvp-avion');
	$resultl=mysqli_query($con,"SELECT Nom,Zone,Port_Ori,NoeudF_Ori,NoeudR,Plage,Recce,Pont_Ori,Industrie,Radar_Ori,BaseAerienne FROM Lieu WHERE ID='$Cible'")
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff_pvp-cible');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Reputation=$data['Reputation'];
			$Navigation=$data['Navigation'];
			$Vue=$data['Vue'];
			$Moral=$data['Moral'];
			$Courage=$data['Courage'];
			$Avance=$data['Avancement'];
			$Equipage=$data['Equipage'];
			$Pays_Origine=$data['Pays'];
			$Heure_Mission=$data['Heure_Mission'];
			//$Mission_Type=$data['S_Mission'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$Chargeurs=$data['S_Chargeurs'];
			if($Sandbox)
				$Ailier=$data['S_Ailier'];
			else
				$Ailier=$data['Ailier'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($resulta)
	{
		while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
		{
			$NomAvion=$data['Nom'];
			$HP=$data['Robustesse'];
			$Type_avion=$data['Type'];
			$VitesseA=$data['VitesseA'];
			$Plafond=$data['Plafond'];
			$Autonomie=$data['Autonomie'];
			$Arme1Avion=$data['ArmePrincipale'];
			$Arme2Avion=$data['ArmeSecondaire'];
			$Arme1Avion_nbr=$data['Arme1_Nbr'];
			$Arme2Avion_nbr=$data['Arme2_Nbr'];
			$Arme3Avion_nbr=$data['Arme3_Nbr'];
			$Arme4Avion_nbr=$data['Arme4_Nbr'];
			$Arme5Avion_nbr=$data['Arme5_Nbr'];
			$Arme6Avion_nbr=$data['Arme6_Nbr'];
			$Mun1=$data['Arme1_Mun'];
			$Mun2=$data['Arme2_Mun'];
			$Engine_Nbr=$data['Engine_Nbr'];
			$Equipage_Nbr=$data['Equipage'];
			$ManoeuvreB=$data['ManoeuvreB'];
			$Helice=$data['Helice'];
			$Train=$data['Train'];
			$Baby=$data['Baby'];
			$Radio_a=$data['Radio'];
			$Avion_Bombe=$data['Bombe'];
			$Avion_Bombe_nbr=$data['Bombe_Nbr'];
		}
		mysqli_free_result($resulta);
		unset($data);
	}	
	if($resultl)
	{
		while($data=mysqli_fetch_array($resultl,MYSQLI_ASSOC))
		{
			$NomCible=$data['Nom'];
			$Zone=$data['Zone'];
			$Cible_Port=$data['Port_Ori'];
			$NoeudR=$data['NoeudR'];
			$Plage=$data['Plage'];
			$Recce_Base=1;
			$Cible_Pont=$data['Pont_Ori'];
			$Cible_NoeudF=$data['NoeudF_Ori'];
			$Cible_Industrie=$data['Industrie'];
			$Cible_Radar=$data['Radar_Ori'];
			$Cible_BaseAerienne=$data['BaseAerienne'];
		}
		mysqli_free_result($resultl);
		unset($data);
	}
    $Mission_Type=GetMissionPVP($Battle,$Type_avion,$Faction);
	if(!$Mission_Type)$Mission_Type=3;
	if($Base)
	{
		$con=dbconnecti();
        $reset=mysqli_query($con,"UPDATE Pilote_PVP SET S_Escorte=0,S_Escorte_nbr=0,S_Escorteb=0,S_Escorteb_nbr=0,S_Leader=0,S_Ailier=3297,enis=0,avion_eni=0,S_Mission='$Mission_Type' WHERE ID='$Pilote_pvp'")
        or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff_pvp-resetsand');
		$result3=mysqli_query($con,"SELECT Nom,Zone FROM Lieu WHERE ID='$Base'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoffpvp-base');
		mysqli_close($con);
		if($result3)
		{
			while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$terrain=$data['Nom'];
				$Zone_base=$data['Zone'];
			}
			mysqli_free_result($result3);
			unset($data);
		}
	}
	if(!$Mun1 and $Arme1Avion_nbr)
		$Mun1=$Arme1Avion_nbr*GetData("Armes","ID",$Arme1Avion,"Munitions");
	if(!$Mun2 and $Arme2Avion_nbr)
		$Mun2=$Arme2Avion_nbr*GetData("Armes","ID",$Arme2Avion,"Munitions");
	if($Equipage_Nbr >1)
	{
		$Endu_Eq=GetData("Equipage_PVP","ID",$Equipage,"Endurance");
		if($Equipage and $Endu_Eq)
			$Equipage_Nom=GetData("Equipage_PVP","ID",$Equipage,"Nom");
	}
	$Ailier_Avion_Nom=$NomAvion;	
	//Distance Objectif, coordonnées carte
	$Dist=GetDistance($Base,$Cible);
	$Distance=$Dist[0];
	if($Distance <30)$Distance=30;
	$_SESSION['SensH']=$Dist[1];
	$_SESSION['SensV']=$Dist[2];
	$_SESSION['Long_par_km']=$Dist[3]/$Distance;
	$_SESSION['Lat_par_km']=$Dist[4]/$Distance;
	unset($Dist);
	//Atk-Bomb
	if($Mission_Type ==1 or $Mission_Type ==2)
	{			
		SetData("Pilote_PVP","S_Cible_Atk",6,"ID",$Pilote_pvp);
		switch($Mission_Type)
		{
			case 1:
				$intro="<p>Votre mission consiste à attaquer des cibles mobiles au sol à l'aide de vos armes de bord.</p>";
				$Mission_Type_txt="appui rapproché";
			break;
			case 2:
				$intro="<p>Votre mission consiste à attaquer des cibles mobiles au sol à l'aide de <b>bombes</b>.</p>";
				$Mission_Type_txt="attaque à la bombe";
			break;
		}
		$intro.='<p>Aujourd\'hui, votre cible est située dans les environs de <b>'.$NomCible.'</b></p>';
	}
	elseif($Mission_Type ==5)
	{
		$intro.="<p>Votre mission consiste à détecter la présence éventuelle d'unités ennemies, et ramener ces informations à votre base.</p>";
		$Mission_Type_txt="reconnaissance";
	}
	elseif($Mission_Type ==15)
	{
		$intro.="<p>Votre mission consiste à prendre des photos de l'objectif, et à les ramener à votre base.</p>";
		$Mission_Type_txt="reconnaissance";
	}
	elseif($Mission_Type ==7 or $Mission_Type ==17)
	{
		$intro.='<p>Votre mission consiste à patrouiller jusqu\'à '.$NomCible.' et intercepter tous les avions rencontrés.</p>';
		$Mission_Type_txt="chasse";
	}
	elseif($Mission_Type ==3)
	{
		$intro.='<p>Votre mission consiste à patrouiller jusqu\'à '.$NomCible.' et engager ou non les avions, à votre guise.</p>';
		$Mission_Type_txt="chasse";
	}
	elseif($Mission_Type == 26)
	{
		$intro.='<p>Votre mission consiste à patrouiller jusqu\'à '.$NomCible.' et éliminer un maximum de chasseurs ennemis.</p>';
		$Mission_Type_txt="chasse";
	}
	elseif($Mission_Type ==4)
	{
		$Escorteb=GetEscorte($Battle,$Faction);
		$Escorteb_nbr=mt_rand(6,12);
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET S_Escorteb='$Escorteb',S_Escorteb_nbr='$Escorteb_nbr' WHERE ID='$Pilote_pvp'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff_pvp-reset2');
		mysqli_close($con);
		$intro="<br>Votre mission consiste à escorter une formation alliée de ".$Escorteb_nbr." ".GetData("Avion","ID",$Escorteb,"Nom")." jusqu'à l'objectif.";
		$Mission_Type_txt="escorte";
	}
	elseif($Mission_Type ==9)
	{
		$Distance=10;
		$intro.="<br>Votre mission consiste à intercepter la formation ennemie.";
		$Mission_Type_txt="interception";
	}
	elseif($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13)
	{
		if($Zone !=6 and !$Cible_Port and !$Plage){
			$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée que sur un objectif maritime !</p>";
			$finmission=true;
		}
		else
			$cible_bomb=20;
		$img=Afficher_Image('images/transfer_no'.$country.'.jpg', 'images/image.png','');
	}
	elseif($Mission_Type ==14)
	{
		if($Zone !=6 and !$Cible_Port){
			$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée que sur un objectif maritime !</p>";
			$finmission=true;
		}
		else{
			$intro='<p>Votre mission consiste à mouiller des mines maritimes dans les environs de '.$NomCible.'.</p>';
			$Mission_Type_txt="mouillage de mines";
		}
	}
	elseif($Mission_Type ==29)
	{
		if($Zone !=6 and !$Cible_Port and !$Plage){
			$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée que sur un objectif maritime !</p>";
			$finmission=true;
		}
		else{
			$intro='<p>Votre mission consiste à patrouiller dans les environs de '.$NomCible.' et à attaquer tout sous-marin ennemi détecté.</p>';
			$Mission_Type_txt="patrouille ASM";
		}
	}
	elseif($Mission_Type ==23)
	{
		$intro="<p>Votre mission consiste à amener votre cargaison intacte jusqu'à votre destination.</p>";
		$Mission_Type_txt="ravitaillement";
	}
	elseif($Mission_Type ==6 or $Mission_Type ==8 or $Mission_Type ==16 or $Mission_Type ==31)
	{
		if($Zone ==6){
			$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée sur un objectif maritime !</p>";
			$finmission=true;
		}
		else
		{
			//Sélection Cible statique
			$cible_select_titre='Cible';
			$cible_select_txt="<select name='Cible_Atk' class='form-control' style='width: 300px'>";				
			if($Recce_Base >0)
			{
				if($Cible_Port)
				{
					$cible_bomb=6;
					$cible_b=" port";
					$cible_select_txt.="<option value='".$cible_bomb."'>le".$cible_b."</option>";
				}
				if($Cible_Pont)
				{
					$cible_bomb=5;
					$cible_b=" pont";
					$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
				}
				if($Cible_BaseAerienne)
				{
					$cible_bomb=1;
					$cible_b=" aérodrome";
					$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
				}
				if($Cible_NoeudF)
				{
					$cible_bomb=4;
					$cible_b="e gare";
					$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
				}
				if($Cible_Industrie and $Mission_Type !=31)
				{
					$cible_bomb=2;
					$cible_b="e usine";
					$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
				}
				if($Cible_Radar)
				{
					$cible_bomb=7;
					$cible_b=" radar";
					$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
				}
			}
			$cible_select_txt.='<option value=\'3\'>une caserne</option></select>';
			$intro='<br>Votre objectif est '.$NomCible.'. Votre commandant fait appel à votre sens tactique pour sélectionner la cible adéquate.';
			switch($Mission_Type)
			{
				case 6:
					$intro.="<p>Votre mission consiste à attaquer une cible statique au sol à l'aide de vos armes de bord.</p>";
					$Mission_Type_txt="attaque";
				break;
				case 8: case 16:
					$intro.="<p>Votre mission consiste à attaquer une cible statique au sol à l'aide de <b>bombes</b>.</p>";
					$Mission_Type_txt="bombardement";
				break;
				case 31:
					$intro.="<p>Votre mission consiste à abattre les chasseurs de nuit ennemis et attaquer les cibles statiques au sol à l'aide de vos armes de bord.</p>";
					$Mission_Type_txt="harcèlement";
				break;
			}
			$Mission_Type_txt="bombardement";
			unset($cible_b);
			unset($cible_bomb);
		}
	}		
	/*Escorte Amie
	if(!$Chk_Decollage)
	{		
		//Nuit
		if($Mission_Type ==16 or $Mission_Type ==17 or $Mission_Type == 21 or $Mission_Type == 22 or $Mission_Type == 25 or $Mission_Type == 27 or $Mission_Type == 28 or $Mission_Type == 31)
			$Nuit=true;
		else
			$Nuit=false;
		if($Mission_Type ==1 or $Mission_Type ==2 or $Mission_Type ==5 or $Mission_Type ==6 or $Mission_Type ==8 or $Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13 or $Mission_Type ==15 or $Mission_Type ==24)
			$intro.="<br>Aucune escorte n'est disponible pour vous accompagner jusqu'à l'objectif.";
		elseif($Mission_Type ==7 or $Mission_Type ==9 or $Mission_Type ==23)
			$intro.="<br><b>Aucun chasseur allié en patrouille n'est disponible pour votre escorte</b>";
	}*/
	$Meteo=GetMeteo($Saison,$Latitude,$Longitude,0,$Nuit);
	$terrain_txt='du terrain de <b>'.$terrain.'</b>';
	if(!$finmission)
	{
		if($Autonomie_max)
			$Autonomie_min=min($Autonomie_max);
		if($Autonomie_min >10)
			$Autonomie=$Autonomie_min;
		$img='<img src=\'images/avions/decollage'.$avion_img.'.jpg\' style=\'width:100%;\'>';
		//Formations
		if($Mission_Type ==1 or $Mission_Type ==2 or $Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13)
			$Formation=0;
		elseif($Mission_Type ==8 or $Mission_Type ==16)
		{	
			if(!$Formation)
			{
				//Formations Bombardiers
				if($Battle ==1)
					$Formation=mt_rand(3,6);
				if($Equipage and $Endu_Eq)
				{
					$Radio=floor(GetData("Equipage_PVP","ID",$Equipage,"Radio")/50)+$Radio_a;
					$Formation+=$Radio;
				}
			}
		}
		elseif($Mission_Type ==3 or $Mission_Type ==5 or $Mission_Type ==15 or $Mission_Type ==18 or $Mission_Type ==19 or $Mission_Type ==22 or $Mission_Type ==27 or $Mission_Type ==28 or $Mission_Type ==29 or $Mission_Type ==31)
			$Formation=0;
		//Patrouilleurs et reco en solo
		$intro.='<br>Vous vous préparez à décoller '.$terrain_txt.' aux commandes de votre <b>'.$NomAvion.'</b>';
		if($Type_avion ==9 or $Type_avion ==3)$Formation=0;			
		if($Equipage and $Endu_Eq)
			$intro.='<p><b>'.$Equipage_Nom.'</b> est à bord et procède aux dernières vérifications</p>';
		if($Formation >0)
			$intro.='<p>Votre formation est composée de <b>'.$Formation.' '.$Ailier_Avion_Nom.'</b></p>';
		elseif($Ailier and $Ailier_Avion)
			$intro.='<p>Votre ailier <b>'.$Ailier_Nom.'</b> vous accompagne, à bord d\'un <b>'.$Ailier_Avion_Nom.'</b></p>';
		$intro.='<p>Les prévisions météo sont les suivantes: <b>'.$Meteo[0].'</b></p>';
		$Tourelle_Mun=($Arme3Avion_nbr + $Arme4Avion_nbr + $Arme5Avion_nbr + $Arme6Avion_nbr)*(500*$Chargeurs);
		$meteo_malus=$Meteo[1];
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET S_HP='$HP',Stress_Commandes=0,Stress_Train=0,Stress_Moteur=0,Stress_Arme1=0,Stress_Arme2=0,S_Avion_db='Avion',S_Cible='$Cible',S_Engine_Nbr='$Engine_Nbr',
		S_Avion_Mun=1,S_Avion_Bombe='$Avion_Bombe',S_Avion_Bombe_Nbr='$Avion_Bombe_nbr',S_Avion_BombeT='$Avion_BombeT',S_Baby='$Baby',S_Equipage_Nbr='$Equipage_Nbr',S_Nuit='$Nuit',S_Formation='$Formation',
		S_Meteo='$meteo_malus',S_Tourelle_Mun='$Tourelle_Mun',S_Essence='$Autonomie',Missions=Missions+1 WHERE ID='$Pilote_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff_pvp-reset');
		mysqli_close($con);
		$Puissance=GetPuissance("Avion",$Avion,0,$HP,1,1,$Engine_Nbr);
		$chemin=$Distance;
		$alt=mt_rand(0,1000)+$VitesseA*5;
		if($alt >$Plafond)$alt=$Plafond;
		$gaz_menu=ShowGaz($Avion,0,0,$alt,6,true);
		$_SESSION['Decollage']=true;
		$_SESSION['Distance']=$Distance;
		$date=date('Y-m-d G:i');
		$querybattle="INSERT INTO Battle (Battle, Pilote, Avion, Mission, Date)
		VALUES ('$Battle','$Pilote_pvp','$Avion','$Mission_Type','$date')";
		$con=dbconnecti(2);
		$ok=mysqli_query($con,$querybattle) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff_pvp-battle');
		mysqli_close($con);
		$titre="décollage <a href='aide_takeoff.php' target='_blank' title='Aide décollage'><img src='images/help.png'></a>";
		$mes.='<form action=\'index.php?view=nav0_pvp\' method=\'post\'>
				<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
				<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
				<input type=\'hidden\' name=\'Meteo\' value='.$Meteo[1].'>
				<input type=\'hidden\' name=\'Avion\' value='.$Avion.'>
				<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
				<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
				<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
				<input type=\'hidden\' name=\'Base\' value='.$Base.'>
				<input type=\'hidden\' name=\'Camp\' value='.$Faction.'>
				<input type=\'hidden\' name=\'Battle\' value='.$Battle.'>';
		if($cible_select_titre)$mes.='<table class=\'table\'><thead><tr><th>'.$cible_select_titre.'</th></tr></thead><tr><td>'.$cible_select_txt.'</td></tr>';
		$mes.='<tr>'.$gaz_menu.'</tr></table>';
		$mes.='<input type=\'Submit\' title=\'Mettez les gaz pour décoller!\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'> <a href=\'aide_takeoff.php\' target=\'_blank\' title=\'Aide décollage\'><img src=\'images/help.png\'></a></form>';
	}
	else
	{
		$_SESSION['Decollage']=true;
		$_SESSION['Distance']=0;
		$mes.="<p class='lead'>FIN DE MISSION</p>";
		$menu.="<form action='index.php?view=profil_pvp' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
include_once('./default.php');