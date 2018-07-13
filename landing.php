<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
/*if($_SESSION['PlayerID'] ==1 or $_SESSION['PlayerID'] ==2)
{
	echo"<pre>";
	print_r($_POST);
	print_r($_SESSION);
	echo"</pre>";
}*/
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
//$Meteo=Insec($_POST['Meteo']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$roues=Insec($_POST['roues']);
$PlayerID=$_SESSION['PlayerID'];
//Check Joueur Valide
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $avion >0 AND !empty($_POST))
{	
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_rencontre.inc.php');
	$Saison=$_SESSION['Saison'];
	$Chk_landing=$_SESSION['atterr'];
	$ventre=false;
	$alt=1;
	if($Chk_landing)
	{
		$mes="<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>";
		//mail("binote@hotmail.com","Aube des Aigles: Init Mission F5 (landing) : ".$PlayerID,"Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
		$add_stock=false;
	}
	else
		$add_stock=true;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Unit,Moral,Courage,S_Avion_db,S_Mission,S_Cible,S_Longitude,S_Latitude,S_Avion_Bombe_Nbr,S_Avion_Bombe,S_HP,S_Essence,Simu,Sandbox FROM Pilote WHERE ID='$PlayerID'") or die ('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : landing-player');
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
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unite=$data['Unit'];
			$Avion_db=$data['S_Avion_db'];
			$Mission=$data['S_Mission'];
			$Moral=$data['Moral'];
			$Courage=$data['Courage'];
			$Cible=$data['S_Cible'];
			$Avion_Bombe_Nbr=$data['S_Avion_Bombe_Nbr'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$essence=$data['S_Essence'];
			$HP=$data['S_HP'];
			$Simu=$data['Simu'];
			$Sandbox=$data['Sandbox'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if(is_array($Skills_Pil))
	{
		include_once('./jfv_skills_inc.php');
		if(in_array(52,$Skills_Pil))
			$Pos_Enclume=5;
		if(in_array(130,$Skills_Pil))
			$Pers_Sup=1;
	}
	//GetData Avion	!! Ne pas combiner avec un autre query le même dbconnecti => Bug
	$con=dbconnecti();
	$result2=mysqli_query($con,"SELECT Nom,Robustesse,Masse,ChargeAlaire,ArmePrincipale,ArmeSecondaire,Helice,Train FROM $Avion_db WHERE ID='$avion'") or die ('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : landing-avion');
	//mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$NomAvion=$data['Nom'];
			$Robustesse=$data['Robustesse'];
			$Masse=$data['Masse'];
			$ChargeAlaire=$data['ChargeAlaire'];
			$Arme1Avion=$data['ArmePrincipale'];
			$Arme2Avion=$data['ArmeSecondaire'];
			$Helice=$data['Helice'];
			$Train=$data['Train'];
		}
		mysqli_free_result($result2);
		unset($data);
	}
	//$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Type,Base,Porte_avions,Avion1,Avion2,Avion3,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'");
	//mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$unit_type=$data['Type'];
			$base=$data['Base'];
			$Porte_avions=$data['Porte_avions'];
			$Avion1=$data['Avion1'];
			$Avion2=$data['Avion2'];
			$Avion3=$data['Avion3'];
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
		}
		$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
		$Personnel=array_count_values($Pers);
		mysqli_free_result($result);
	}
	//$con=dbconnecti();
	$resultb=mysqli_query($con,"SELECT Nom,Zone,QualitePiste,Tour,Port_Ori,BaseAerienne,LongPiste,Plage,Meteo FROM Lieu WHERE ID='$base'");
	mysqli_close($con);
	if($resultb)
	{
		while($data=mysqli_fetch_array($resultb,MYSQLI_ASSOC))
		{
			$terrain=$data['Nom'];
			$Zone_base=$data['Zone'];
			$Tour=$data['Tour'];
			$QualitePiste=$data['QualitePiste'];
			$BaseAerienne=$data['BaseAerienne'];
			$LongPiste=$data['LongPiste'];
			$Port=$data['Port_Ori'];
			$Plage=$data['Plage'];
			$Previsions=$data['Meteo'];
		}
		mysqli_free_result($resultb);
		unset($data);
	}
	if($Avion_db !="Avion")
	{
		$ID_ref=GetData($Avion_db,"ID",$avion,"ID_ref");
		$Robustesse=GetData("Avion","ID",$ID_ref,"Robustesse");
	}
	$moda=$Robustesse/$HP;
	$modav=$moda;
	if($Avion_db =="Avion" and $Avion_Bombe_Nbr >0 and $Avion_Bombe >0 and $Avion_Bombe !=30)
	{
		$Masse+=($Avion_Bombe*$Avion_Bombe_nbr);
		$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_Nbr));
		$moda=$moda+$charge_sup;
		$modav=$moda-$charge_sup;
	}		
	$avion_img=GetAvionImg($Avion_db,$avion);
	$Incident=GetIncident($PlayerID,1,$Saison,$Zone_base,$Avion_db,$avion,$c_gaz);
	$malus_incident=(100+$Incident[1])/100;
	$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion,0,$Pilotage);
	$ManoeuvreB=GetMan($Avion_db,$avion,$alt,$HP,$moda,$malus_incident,$flaps);
	$Speed=GetSpeed($Avion_db,$avion,$alt,$Previsions,$modav,$malus_incident,$c_gaz,$flaps);	
	if($Sandbox)$QualitePiste=100;
	if($Porte_avions >0)
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
		if(!$HP_PA)
		{
			$QualitePiste=100;
			$Tour=100;
		}
		else
		{
			$QualitePiste=round(($HP_PA/$HP_max_PA)*100);
			$Tour=round(($HP_PA/$HP_max_PA)*100);
		}
		$LongPiste=$LongPiste_PA*($QualitePiste/100);
		$QualitePiste=100-$QualitePiste-$Previsions;
	}
	else
	{
		$LongPiste*=($QualitePiste/100);
		if($unit_type !=8)
			$QualitePiste=100-$QualitePiste;
		else
			$QualitePiste=0;
	}	
	if($roues)
	{
		switch($Train)
		{
			case 1:
				$Train=10;
			break;
			case 2:
				$Train=25;
			break;
			case 8:
				$Train=10;
			break;
			case 9:
				$Train=-5;
			break;
			case 13: case 16:
				if($Zone_base ==6 or $Port or $BaseAerienne ==2 or $Plage ==1)
				{
					$Train=50;
					$LongPiste=3000;
				}
				else
					$Train=-50;
			break;
			default:
				$Train=1;
			break;
		}
	}
	else
	{
		if($Train <5)
			$intro.="Votre train n'est pas sorti, vous devez atterrir sur le ventre!<br>";
		else
			$intro.="Votre train est endommagé, vous devez atterrir sur le ventre!<br>";
		$Train=-50;
	}	
	if($Incident[1])
		$intro.="Vous constatez <b>".$Incident[0]."</b>!<br>";		
	$toolbar=GetToolbar($chemin,$PlayerID,$avion,$HP,$Mun1,$Mun2,$essence,$Previsions,$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission,$c_gaz,$malus_incident,$Avion_db,$flaps);
	if($Speed >50)
	{
		//Edit 28.12.2014 : Faciliter l'atterrissage des avions à grande surface alaire
		$Masse-=($ChargeAlaire/2);
		if($Masse <0)$Masse=1;
		//Hydravions
		if($Train ==13 or $Train ==16)$QualitePiste=0-$Previsions;		
		if($Speed >250)$Speed*=2;
		//Piste
		$Pil_mod=(pow($Pilotage,2)/1000);		
		if($unit_type !=8)
			$Vit_mini=floor(((100+sqrt($Masse))*(1-($flaps/10)))-($Pilotage/10));
		else
			$Vit_mini=100;
		if($Vit_mini >245)$Vit_mini=245;
		$Landing_run=round($Masse/15*$Speed/$Vit_mini)-$Pil_mod;
		//Edit 27.04.2013
		if($Speed <$Vit_mini)$Malus_Speed=$Vit_mini-$Speed;			
		$Landing=$Pilotage+($ManoeuvreB/5)-($QualitePiste*10)+$Previsions+$Incident[1]+($Moral/10)+($Courage/10)+($Helice*5)+($Train*5)-($Speed/2)-((100-$Tour)/10)-($Malus_Speed*10);		
		if($Helice ==2)
			$Landing_run*=0.75;
		elseif($Helice ==1)
			$Landing_run*=0.9;
		if($Zone_base ==6 or $Porte_avions)
			$Landing_run/=2;
		if($Porte_avions >0)
		{
			$intro.='Vous approchez de votre porte-avions aux commandes de votre <b>'.$NomAvion.'</b>, en vue de l\'appontage.';
			if($Masse <10000)
			{
				$Exp_pa=mt_rand(0,GetPil_PA($PlayerID));
				if($Exp_pa >25)$Landing_run=50;
			}
		}
		else
			$intro.='Vous approchez du terrain de <b>'.$terrain.'</b> aux commandes de votre <b>'.$NomAvion.'</b>, en vue de l\'atterrissage.';
		if($Landing_run <50)$Landing_run=50;
		if($Landing_run >$LongPiste and $Train !=13 and $Train !=16)
			$Landing=-99999999;	
		elseif($Landing <0 and $Speed <$Vit_mini and $Simu)
			$Landing=-99999998;
		/*$Meteo=GetMeteo($Saison,0,0,$Previsions);
		$intro.='<br><br>La météo est la suivante : <b>'.$Meteo[0].'</b>.';*/
		/*if($Landing_run >1000 or $Speed >250 or $Speed <$Vit_mini or $Landing <0)
		{
			$skilles.="<br>[Score à l'atterrissage: ".$Landing."] sur l'avion ".$NomAvion."
			<br>(Pilotage: ".$Pilotage."; Qualité de la piste *10 : -".$QualitePiste."; Manoeuvrabilité de l'avion /5: ".$ManoeuvreB."; Train *5 : ".$Train."; Hélice *5 : ".$Helice."; 
			Malus Météo : ".$Meteo[1]."; Incident Technique: ".$Incident[1]." - Vitesse /2 : ".$Speed." Gaz : ".$c_gaz." Flaps : ".$flaps." /modav : ".$modav."
			<br>Landing_run : ".$Landing_run."/".$LongPiste." m // Masse : ".$Masse." // Speed : ".$Speed." / Vit_mini : ".$Vit_mini." // Pil_mod : ".$Pil_mod;
			//$skilles.="'<pre>".print_r($GLOBALS).'</pre>';
			error_log($skilles,1,'binote@hotmail.com','Landing : Debug');
		}*/
		if($Landing >0 and !$Chk_landing)
		{
			if($Porte_avions >0)
			{
				if($Exp_pa >200)
					$intro.="<br>En véritable pilote de porte-avions, vous accrochez le 3e brin et stoppez l'avion sous les applaudissements des hommes de bord.";
				elseif($Exp_pa >150)
					$intro.="<br>Vous accrochez le 3e brin de justesse, l'avion s'immobilisant en douceur.";
				elseif($Exp_pa >100)
					$intro.="<br>Vous accrochez le 2e brin, l'avion s'immobilisant lourdement sur le pont.";
				elseif($Exp_pa >75)
					$intro.="<br>Vous accrochez le 2e brin de justesse, l'avion s'immobilisant lourdement sur le pont.";
				elseif($Exp_pa >50)
					$intro.="<br>Vous accrochez le 1e brin, l'avion s'immobilisant très lourdement sur le pont.";
				elseif($Exp_pa >25)
					$intro.="<br>Vous accrochez le 1e brin de justesse, l'avion s'immobilisant très lourdement sur le pont.";
				else
					$intro.="<br>Malheureusement, vous ne parvenez pas à accrocher un brin de freinage!";
				$intro.="<p><b>Vous appontez sans problème, au terme d'une course de ".round($Landing_run)."m !</b></p>";
			}
			elseif($Train ==13 or $Train ==16)
				$intro.="<p><b>Vous amerrissez sans problème, au terme d'une course de ".round($Landing_run)."m !</b></p>";
			else
				$intro.="<p><b>Vous atterrissez sans problème, au terme d'une course de ".round($Landing_run)."m !</b></p>";
			$img=Afficher_Image('images/avions/landing'.$avion_img.'.jpg','images/avions/decollage'.$avion_img.'.jpg',$NomAvion);
			if(!$Sandbox)
			{
				if($Simu)
				{
					//UpdateCarac($PlayerID,"Pilotage",2);
					UpdateCarac($PlayerID,"Reputation",2);
					UpdateCarac($PlayerID,"Moral",20);
					AddPilotage($Avion_db,$avion,$PlayerID,2);
				}
				else
				{
					//UpdateCarac($PlayerID,"Pilotage",1);
					UpdateCarac($PlayerID,"Reputation",1);
					UpdateCarac($PlayerID,"Moral",10);
					AddPilotage($Avion_db,$avion,$PlayerID,1);
				}
			}
		}
		elseif($Landing <-100)
		{
			$pomp=false;
			$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
			if(!$Sandbox)
			{
				UpdateCarac($PlayerID,"Endurance",-1);
				UpdateCarac($PlayerID,"Moral",-5);
				UpdateCarac($PlayerID,"Reputation",-1);
				UpdateData("Lieu","QualitePiste",-1,"ID",$base);
			}
			else
				UpdateCarac($PlayerID,"Free",-1);			
			if($Tour >49)
				$Pompiers=($Personnel[17]+$Pers_Sup)*$Bonus_Pers;
			else
				$Pompiers=0;
			$Pompiers+=$Pos_Enclume;
			if($Landing ==-99999999)
			{
				if($Pompiers >mt_rand(0,10) or $Mission >97)
				{
					if($Porte_avions >0)
						$intro.="<p>Avalant toute la piste (".round($Landing_run)."m parcouru / piste de ".$LongPiste."m), vous ne parvenez pas à accrocher le brin de freinage !
						<br>Grace à la barrière de sécurité et à l'intervention rapide des pompiers du navire, l'avion est réparable!</p>";
					else
						$intro.="<p>Avalant toute la piste (".round($Landing_run)."m parcouru / piste de ".$LongPiste."m), votre avion ne parvient pas à freiner suffisamment. Vous vous crashez en bout de piste !
						<br>Votre appareil est gravement endommagé, mais grace à l'intervention des pompiers de l'escadrille, l'avion est réparable!</p>";
					$pomp=true;
				}
				else
				{
					if($Porte_avions >0)
						$intro.="<p>Avalant toute la piste (".round($Landing_run)."m parcouru / piste de ".$LongPiste."m), vous ne parvenez pas à accrocher le brin de freinage ! Passant par dessus bord, vous crashez votre avion en mer !</p>";
					else
						$intro.="<p>Avalant toute la piste (".round($Landing_run)."m parcouru / piste de ".$LongPiste."m), votre avion ne parvient pas à freiner suffisamment. Vous vous crashez en bout de piste !</p>";
				}
			}
			elseif($Landing ==-99999998)
			{
				if($Pompiers >mt_rand(0,10) or $Mission >97)
				{
					$intro.="<p>Vous entamez votre descente, mais votre vitesse trop lente (".$Speed."km/h au lieu des ".$Vit_mini."km/h requis) vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!
					<br>Votre appareil est gravement endommagé, mais grace à l'intervention des pompiers de l'escadrille, l'avion est réparable!</p>";
					$pomp=true;
				}
				else
				{
					$intro.="<p>Vous entamez votre descente, mais votre vitesse trop lente (".$Speed."km/h au lieu des ".$Vit_mini."km/h requis) vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!
					<p>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
				}
			}
			else
			{							
				if($Pompiers >mt_rand(0,10) or $Mission >97)
				{
					$intro.="<p>Vous entamez votre descente lorsqu'un incident mécanique vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
					<p>Votre appareil est gravement endommagé, mais grace à l'intervention des pompiers de l'escadrille, l'avion est réparable!</p>";
					$pomp=true;
				}
				elseif($Incident[0] =="aucun incident notable")
				{
					if($Previsions <-50)
					{
						if($Porte_avions >0)
						{
							$intro.="<p>Vous entamez votre descente, mais le mauvais temps vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
							<p>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
						}
						else
						{
							$intro.="<p>Vous entamez votre descente, mais le mauvais temps vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
							<p>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
						}
					}
					else
					{
						$intro.="<p>Vous entamez votre descente lorsqu'un incident mécanique vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
						<p>Votre appareil est gravement endommagé, c'est une perte totale pour l'escadrille !</p>";
					}
				}
				else
				{
					$intro.='<p>Vous entamez votre descente lorsque '.$Incident[0].' vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
					<p>Votre appareil est gravement endommagé, c\'est une perte totale pour l\'escadrille !</p>';
				}
			}
			if(!$pomp and !$Sandbox and $unit_type !=8)
			{
				if($Avion_db =="Avions_Persos")
				{
					$HP+=$Landing;
					if($HP <0)$HP=0;
					SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
					AddEvent("Avions_Persos",12,$avion,$PlayerID,$Unite,$base,0,$HP);
				}
				else
					AddEvent("Avion",12,$avion,$PlayerID,$Unite,$base,1,$HP);
				$add_stock=false;
			}
		}
		else
		{
			if($Incident[0] =="aucun incident notable")
			{
				if($Previsions <-50)
				{
					if($Porte_avions >0)
					{
						$intro.="<p>Vous entamez votre descente, mais le mauvais temps vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
						<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>";
					}
					else
					{
						$intro.="<p>Vous entamez votre descente, mais le mauvais temps vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
						<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>";
					}
				}
				else
				{
					$intro.="<p>Vous entamez votre descente lorsqu'un léger incident vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
					<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>";
				}
			}
			else
			{
				$intro.='<p>Vous entamez votre descente lorsque '.$Incident[0].' vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
				<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>';
			}
			$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
			if(!$Sandbox and $unit_type !=8)
			{
				AddEvent($Avion_db,12,$avion,$PlayerID,$Unite,$base,0,$HP);
				UpdateCarac($PlayerID,"Endurance",-1);
				UpdateCarac($PlayerID,"Moral",-5);
			}
		}
	}
	else
	{
		$intro.='<p>Vous entamez votre descente, mais votre vitesse trop lente ('.$Speed.'km/h) vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
		<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>';
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');	
		if(!$Sandbox and $unit_type !=8)
		{
			if($Avion_db =="Avions_Persos")
			{
				$HP-=mt_rand(100,500);
				if($HP <0)$HP=0;
				SetData("Avions_Persos","Robustesse",$HP,"ID",$avion);
			}
			AddEvent($Avion_db,12,$avion,$PlayerID,$Unite,$base,0,$HP);
			UpdateCarac($PlayerID,"Endurance",-1);
			UpdateCarac($PlayerID,"Moral",-5);
			UpdateData("Lieu","QualitePiste",-1,"ID",$base);
		}
	}
	//La Cible est supprimée (alerte radar)
	SetData("Pilote","S_Cible",0,"ID",$PlayerID);	
	//Avion ajouté au stock, sauf si avion perso
	if($add_stock and $Unite !=GetTraining($country) and !$Sandbox)
	{
		if($Avion_db =="Avion")AddAvionToUnit($Unite,$avion);
		//Récupération des munitions, essence pour l'unité
		$Engine=GetData($Avion_db,"ID",$avion,"Engine");
		if($Engine)
		{
			$Carburant=GetData("Moteur","ID",$Engine,"Carburant");
			$Stock_Essence='Stock_Essence_'.$Carburant;
			UpdateData("Unit",$Stock_Essence,$essence,"ID",$Unite);
		}
		if($Arme1Avion >0 and $Arme1Avion !=5 and $Arme1Avion !=25 and $Arme1Avion !=26 and $Arme1Avion !=27)
		{
			$Cal1=round(GetData("Armes","ID",$Arme1Avion,"Calibre"));
			$Calibre1='Stock_Munitions_'.$Cal1;
			UpdateData("Unit",$Calibre1,$Mun1,"ID",$Unite);
		}
		if($Arme2Avion >0 and $Arme2Avion !=5 and $Arme2Avion !=25 and $Arme2Avion !=26 and $Arme2Avion !=27)
		{
			$Cal2=round(GetData("Armes","ID",$Arme2Avion,"Calibre"));
			$Calibre2='Stock_Munitions_'.$Cal2;
			UpdateData("Unit",$Calibre2,$Mun2,"ID",$Unite);
		}
	}
	$mes.="<p class='lead'>FIN DE MISSION</p>";
	$titre="Atterrissage";
	$_SESSION['atterr']=true;
	if($Sandbox)
	{
		$_SESSION['Distance']=0;
		$_SESSION['PVP']=false;
		$menu.="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	else
	{
		if($Landing >-100)
			UpdateCarac($PlayerID,"Missions_Jour",-1);
		if(!$Chk_landing)
		{
			if($Mission ==127)
			{
				UpdateCarac($PlayerID,"Reputation",50);
				UpdateCarac($PlayerID,"Avancement",10);
				$mes.='<p><b>Vous avez ramené un avion capturé à l\'ennemi jusqu\'à votre base.<br>Voulez-vous l\'utiliser en tant qu\'avion personnel ?</b></p>';
				$menu.='<form action=\'choix_avion1.php\' method=\'post\'>
				<input type=\'hidden\' name=\'avion\' value='.$avion.'>
				<input type=\'Submit\' value=\'OUI\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'></form>';
				$menu.="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='0'><input type='Submit' value='NON' class=\'btn btn-danger\' onclick='this.disabled=true;this.form.submit();'></form>";
				$skills.="<p class='lead'>Attention! Si vous répondez oui, votre avion personnel actuel sera remplacé!</p>";
			}
			elseif($Mission <90)
			{
				if($Avion_db !="Avion")$avion=$ID_ref;
				if($avion ==$Avion1 or $avion ==$Avion2 or $avion ==$Avion3)
					$skills=MoveCredits($PlayerID,1,2);
				else
					$skills=MoveCredits($PlayerID,1,1);
				UpdateCarac($PlayerID,"Reputation",10);
				UpdateCarac($PlayerID,"Avancement",5);
				$menu.="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='0'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}
			else
				$menu.="<form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='0'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}
	SetData("Pilote","S_Unite_Intercept",0,"ID",$PlayerID);
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
usleep(1);
include_once('./index.php');
?>