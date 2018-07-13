<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
/*if($PlayerID ==1 or $PlayerID ==2)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
$avion=Insec($_POST['Avion']);
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$meteo=Insec($_POST['Meteo']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$alt=Insec($_POST['Alt']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$Cible_Atk_Post=Insec($_POST['Cible_Atk']);
$base=Insec($_POST['Base']);
$PlayerID=$_SESSION['PlayerID'];
//Check Joueur Valide
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $avion >0 AND $base >0 AND !empty($_POST))
{
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_rencontre.inc.php');
	$country=$_SESSION['country'];
	$Chk_Decollage=$_SESSION['Decollage0'];
	$Saison=$_SESSION['Saison'];
	$finmission=false;
	if($Chk_Decollage){
		$mes='<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>';
		UpdateCarac($PlayerID,"Free",-1);
		MoveCredits($PlayerID,90,-1);
		UpdateCarac($PlayerID,"Reputation",-10);
		UpdateCarac($PlayerID,"Avancement",-10);
		mail('binote@hotmail.com',"Aube des Aigles: Init Mission F5 (takeoff) : ".$PlayerID , "Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}
	if($c_gaz <20){
		$mes.="<br>Votre moteur ne délivre plus suffisamment de puissance pour vous maintenir en vol. Le crash est inévitable !";
		$finmission=true;
	}
	else
	{	
		if($Cible_Atk_Post)
			SetData("Pilote","S_Cible_Atk",$Cible_Atk_Post,"ID",$PlayerID);
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,S_Avion_db,S_Mission,S_Longitude,S_Latitude,S_Cible,S_HP,S_Nuit,S_Avion_Bombe,S_Avion_Bombe_Nbr,S_Essence,Sandbox FROM Pilote WHERE ID='$PlayerID'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav0-player');
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
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
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Avion_db=$data['S_Avion_db'];
				$Mission_Type=$data['S_Mission'];
				$Cible=$data['S_Cible'];
				$Longitude=$data['S_Longitude'];
				$Latitude=$data['S_Latitude'];
				$HP=$data['S_HP'];
				$Nuit=$data['S_Nuit'];
				$Avion_Bombe=$data['S_Avion_Bombe'];
				$Avion_Bombe_nbr=$data['S_Avion_Bombe_Nbr'];
				$essence=$data['S_Essence'];
				$Sandbox=$data['Sandbox'];
			}
			mysqli_free_result($result);
			unset($data);
		}	
		$result2=mysqli_query($con,"SELECT Type,Masse,Moteur,Plafond,Engine_Nbr,Train,Helice,ManoeuvreB FROM $Avion_db WHERE ID='$avion'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav0-avion');
		$result3=mysqli_query($con,"SELECT Zone,Tour,BaseAerienne,QualitePiste,LongPiste,Meteo FROM Lieu WHERE ID='$base'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : nav0-base');
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Type_avion=$data['Type'];
				$Masse=$data['Masse'];
				$Moteur=$data['Moteur'];
				$Plafond=$data['Plafond'];
				$Engine_Nbr=$data['Engine_Nbr'];
				$ManoeuvreB=$data['ManoeuvreB'];
				$Helice=$data['Helice'];
				$Train=$data['Train'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		//GetData Lieu		
		if($result3)
		{
			while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$Zone_base=$data['Zone'];
				$Tour_base=$data['Tour'];
				$BaseAerienne=$data['BaseAerienne'];
				$Meteo_Ori=$data['Meteo'];
				if($Sandbox)
					$QualitePiste=100;
				else
					$QualitePiste=$data['QualitePiste'];
				$LongPiste=$data['LongPiste']*($QualitePiste/100);
			}
			mysqli_free_result($result3);
			unset($data);
		}
		if(is_array($Skills_Pil))
			include_once('./jfv_skills_inc.php');
		if(($Avion_db =="Avion" or $Type_avion ==6) and $Avion_Bombe)
			$Masse+=($Avion_Bombe*$Avion_Bombe_nbr);		
		$avion_img=GetAvionImg($Avion_db,$avion);
		//Decollage
		$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,$Sandbox,$Pilotage);
		$Pil_mod=(pow($Pilotage,2)/1000);		
		if($Nuit)
			$meteo_malus=$meteo+85;
		else
			$meteo_malus=$meteo;		
		//Porte-avions et Hydravions
		$Porte_avions=GetData("Unit","ID",$Unite,"Porte_avions");
		if($Porte_avions >0)
		{
			$Placement_pa=GetData("Regiment_IA","Vehicule_ID",$Porte_avions,"Placement");
			if($Placement_pa ==8 or $Zone_base ==6)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,Taille,HP FROM Cible WHERE ID='$Porte_avions'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Nom_PA=$data['Nom'];
						$LongPiste_PA=$data['Taille'];
						$HP_max_PA=$data['HP'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				$HP_PA=GetData("Regiment_IA","Vehicule_ID",$Porte_avions,"HP");
				if(!$HP_PA)$HP_PA=GetData("Regiment","Vehicule_ID",$Porte_avions,"HP");
				if(!$HP_PA){
					$QualitePiste=100;
					$Tour_base=100;
				}
				else{
					$QualitePiste=round(($HP_PA/$HP_max_PA)*100);
					$Tour_base=round(($HP_PA/$HP_max_PA)*100);
				}
				$LongPiste=$LongPiste_PA*($QualitePiste/100);
				$QualitePiste_final=100-$QualitePiste-$meteo_malus;
				$BaseAerienne=1;
				if($avion == 398)$Masse*=0.8;
			}
			elseif($BaseAerienne)
			{
				$QualitePiste_final=100-$QualitePiste;
				$intro.="Votre porte-avions étant ammarré au port, vous utilisez la piste d'envol de la base aérienne terrestre<br>";
			}
			else
			{
				$intro.= "Votre porte-avions étant ammarré au port, il ne vous est pas possible de décoller!<br>";
				$finmission=true;
			}
		}
		elseif($Zone_base ==6 or $Train ==13 or $Train ==16)
			$QualitePiste_final=0-$meteo_malus;
		else
		{
			$QualitePiste_final=100-$QualitePiste;
			//Sable
			if($Zone_base ==8 and $Avion_db !="Avion")
			{
				if($Moteur !=7)
				{
					UpdateCarac($PlayerID,"Stress_Moteur",50);
					$Pilotage-=50;
					$intro.='Du sable encrasse votre moteur!<br>';
				}
			}
		}
		if(!$finmission)
		{
			$Incident=GetIncident($PlayerID,1,$Saison,$Zone_base,$Avion_db,$avion,$c_gaz);
			$Decollage=$Pilotage+($ManoeuvreB/10)-($QualitePiste_final*10)+($meteo_malus*3)+$Incident[1]+($Moral/10)+($Courage/10)+($Helice*5)+($Train*5)-((100-$Tour_base)/10);
			if($Type_avion ==8 and $meteo_malus <20 and $Decollage <1)$Decollage=1;
			if($flaps <3)$Masse*=(1-($flaps/10));			
			if($BaseAerienne <3)
				$Takeoff_run=round($Masse/20/$c_gaz*100)-$Pil_mod;
			elseif($meteo_malus <-19 and $meteo_malus !=-70)
				$Takeoff_run=round($Masse/5/$c_gaz*100)-$Pil_mod;
			else
				$Takeoff_run=round($Masse/10/$c_gaz*100)-$Pil_mod;			
			if($Helice ==2)
				$Takeoff_run*=0.75;
			elseif($Helice ==1)
				$Takeoff_run*=0.9;
			if($BaseAerienne >2 and ($Train ==2 or $Train >6))
				$Takeoff_run*=0.9;
			elseif($Porte_avions and ($Train ==2 or $Train >6))
				$Takeoff_run*=0.9;
			if($Takeoff_run >$LongPiste and $Train !=13 and $Train !=16)
				$Decollage=-99999999;
			//if($Decollage <=0)mail('binote@hotmail.com','Aube des Aigles: Décollage foiré',$PlayerID.' décolle avec un score de '.$Decollage.', un niveau de pilotage de '.$Pilotage.', des gaz ouverts à '.$c_gaz.'%, une météo de '.$Meteo_Ori.' (prévision de '.$meteo.') et une longueur de piste de '.$LongPiste.'m, une qualité de piste de '.$QualitePiste_final.' et une distance de course de '.$Takeoff_run.'m');
			if($Decollage >0 or $Admin)
			{
				if($Takeoff_run <75)$Takeoff_run=50+($Masse/100);
				$intro.="<p>Vous décollez sans problème, au terme d'une course de ".round($Takeoff_run)."m !</p>";
				if(!$Chk_Decollage and !$Sandbox)
				{
					//UpdateCarac($PlayerID,"Pilotage",1);
					UpdateCarac($PlayerID,"Moral",1);
					AddPilotage($Avion_db,$avion,$PlayerID,1);
					//Avion retiré au stock, sauf si avion perso
					if($Unite !=GetTraining($country))
					{
						if($Avion_db =="Avion")
						{
							RetireAvionFromUnit($Unite,$avion);
							$x_nbr=1;
						}
						else
							$x_nbr=0;
						//AddEvent($Avion_db,38,$avion,$PlayerID,$Unite,$base,$x_nbr,$Cible);
					}
				}
				elseif($Sandbox)
					AddPilotage_Sandbox($Avion_db,$avion,$PlayerID,1);
			}
			elseif($Decollage <-50)
			{
				if($Decollage ==-99999999)
				{
					if($Porte_avions >0)
						$intro.="<p>Votre avion ne parvient pas à s'arracher du pont d'envol. Passant par dessus bord après une course de ".round($Takeoff_run)."m, vous crashez votre avion en mer !</p>";
					else
						$intro.="<p>Avalant toute la piste (".round($Takeoff_run)."m parcouru / piste de ".$LongPiste."m), votre avion ne parvient pas à s'arracher du sol. Vous vous crashez en bout de piste !</p>";
				}
				else
				{			
					if($Incident[1] <-49)
						$intro.='<p>Vous entamez votre course de décollage lorsqu\'<b>'.$Incident[0].'</b> vous oblige à interrompre votre mission. Quelle poisse !</p>';
					elseif($QualitePiste !=0 and ($Train ==13 or $Train ==16))
						$intro.="<p>Incapable de déjauger correctement à cause du mauvais temps, votre avion percute une vague de plein fouet !</p>";			
					elseif($QualitePiste <75)
						$intro.="<p>Vous entamez votre course de décollage, mais vous ne pouvez empêcher votre avion d'aller dans le décor à cause de l'état de la piste !</p>";
					elseif($meteo_malus <-49)
						$intro.="<p>Vous entamez votre course de décollage, mais la météo vous oblige à interrompre votre mission. Quelle poisse !</p>";
					elseif($Incident[1])
						$intro.='<p>Vous entamez votre course de décollage lorsqu\'<b>'.$Incident[0].'</b> vous oblige à interrompre votre mission. Quelle poisse !</p>';
				}
				$intro.='<p>Votre appareil est gravement endommagé, c\'est une perte totale pour l\'escadrille !</p>';
				$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
				if(!$Chk_Decollage and !$Sandbox)
				{
					AddEvent($Avion_db,11,$avion,$PlayerID,$Unite,$base,1,abs($meteo_malus));
					UpdateCarac($PlayerID,"Moral",-10);
					UpdateCarac($PlayerID,"Reputation",-10);
					UpdateCarac($PlayerID,"Avancement",-2);
					UpdateData("Unit","Reputation",-10,"ID",$Unite,0,3);
					if($Mission_Type <98)UpdateData("Lieu","QualitePiste",-1,"ID",$base);
					//Avion retiré au stock, sauf si avion perso
					if($Avion_db =='Avion'){
						RetireAvionFromUnit($Unite,$avion);
						UpdateCarac($PlayerID,"Crashs_Jour",1);
					}
				}
				$finmission=true;
			}
			else
			{
				$intro.='<p>Vous entamez votre course de décollage lorsqu\'<b>'.$Incident[0].'</b> vous oblige à interrompre votre mission. Quelle poisse !</p>
				<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>';
				//$skills.="<br>[Score de décollage: ".$Decollage."]
				//<br>(Pilotage: ".$Pilotage."; Qualité de la piste : -".$QualitePiste."; Maniabilité de l'avion: ".$Mani."; Manoeuvrabilité de l'avion: ".$ManoeuvreB."; Malus Météo: ".$Meteo[1]."; Incident Technique: ".$Incident[1].")";
				$img.=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
				if(!$Chk_Decollage and !$Sandbox)
				{
					AddEvent($Avion_db,11,$avion,$PlayerID,$Unite,$base,0,abs($meteo_malus));
					UpdateCarac($PlayerID,"Moral",-10);
					//MoveCredits($PlayerID,9,2);
				}
				$finmission=true;
			}
			unset($Incident);
			$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$meteo,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission_Type,$c_gaz,1,$Avion_db,$flaps);
		}
	}
	if(!$finmission)
	{
		$Puissance=GetPuissance($Avion_db,$avion,0,$HP,1,1,$Engine_Nbr); //GetData("Avion","ID",$avion,"Puissance");
		$intro.='<p>Votre objectif se trouve à une distance de <b>'.$Distance.' km</b>. Vous grimpez à l\'altitude indiquée sur votre plan de vol pour sélectionner votre régime de croisière</p>';
		$chemin=$Distance;
		$gaz_menu=ShowGaz($avion,$c_gaz,$flaps,$alt);
		$_SESSION['Decollage']=true;
		$_SESSION['Distance']=$Distance;
		$img=Afficher_Image('images/avions/vol'.$avion_img.'.jpg','images/avions/vol'.$avion_img.'.jpg',$avion_img);
		$titre='Régime de croisière';
		$mes='<form action=\'nav.php\' method=\'post\'>
				<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
				<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
				<input type=\'hidden\' name=\'Meteo\' value='.$meteo.'>
				<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
				<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
				<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
				<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
				<input type=\'hidden\' name=\'Enis\' value=\'0\'>'.$gaz_menu.'
				<input type=\'submit\' title=\'Choisissez votre régime de croisière\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
	}
	elseif($c_gaz <20){
		$_SESSION['Decollage0']=true;
		$_SESSION['Distance']=0;
		include_once('./end_mission.php');
	}
	else{
		$_SESSION['Decollage0']=true;
		$_SESSION['Distance']=0;
		$mes.='<p class="lead">FIN DE MISSION</p>';
		$menu.="<form action='index.php?view=user' method='post'><input type='submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}	
	include_once('./index.php');
}