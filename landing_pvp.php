<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$chemin=Insec($_POST['Chemin']);
$Distance=Insec($_POST['Distance']);
$Meteo=Insec($_POST['Meteo']);
$alt=Insec($_POST['Alt']);
$avion=Insec($_POST['Avion']);
$Mun1=Insec($_POST['Mun1']);
$Mun2=Insec($_POST['Mun2']);
$Puissance=Insec($_POST['Puissance']);
$c_gaz=Insec($_POST['gaz']);
$flaps=Insec($_POST['flaps']);
$roues=Insec($_POST['roues']);
$Battle=Insec($_POST['Battle']);
$Faction=Insec($_POST['Camp']);
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if(isset($_SESSION['AccountID']) AND $Pilote_pvp >0 AND $avion >0 AND !empty($_POST))
{	
	include_once('./jfv_air_inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_rencontre.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Saison=$_SESSION['Saison'];
	$Chk_landing=$_SESSION['atterr'];
	$ventre=false;
	$alt=1;
	$base=GetBasePVP($Battle,$avion,$Faction);
	if($Chk_landing)
	{
		$mes="<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>";
		//mail("binote@hotmail.com","Aube des Aigles: Init Mission F5 (landing) : ".$Pilote_pvp,"Joueur ".$Pilote_pvp." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
		$add_stock=false;
	}
	else
		$add_stock=true;
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Moral,Courage,Pilotage,Missions,S_Avion_db,S_Mission,S_Cible,S_Longitude,S_Latitude,S_Avion_Bombe_Nbr,S_Avion_Bombe,S_HP,S_Essence,Simu,Sandbox FROM Pilote_PVP WHERE ID='$Pilote_pvp'") or die ('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : landing-player');
	$resultl=mysqli_query($con,"SELECT Nom,Zone,QualitePiste,Tour,Port_Ori,BaseAerienne,LongPiste,Plage FROM Lieu WHERE ID='$base'");
	mysqli_close($con);
	if($resultl)
	{
		while($data=mysqli_fetch_array($resultl,MYSQLI_ASSOC))
		{
			$terrain=$data['Nom'];
			$Zone_base=$data['Zone'];
			$Tour=$data['Tour'];
			$QualitePiste=$data['QualitePiste'];
			$BaseAerienne=$data['BaseAerienne'];
			$LongPiste=$data['LongPiste'];
			$Port=$data['Port_Ori'];
			$Plage=$data['Plage'];
		}
		mysqli_free_result($resultl);
		unset($data);
	}
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Avion_db=$data['S_Avion_db'];
			$Mission=$data['S_Mission'];
			$Moral=$data['Moral'];
			$Courage=$data['Courage'];
			$Pilotage=75; //$data['Pilotage'];
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
	//GetData Avion	!! Ne pas combiner avec un autre query le même dbconnecti => Bug
	$con=dbconnecti();
	$result2=mysqli_query($con,"SELECT Nom,Type,Robustesse,Masse,ChargeAlaire,ArmePrincipale,ArmeSecondaire,Helice,Train FROM $Avion_db WHERE ID='$avion'") or die ('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : landing-avion');
	mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$NomAvion=$data['Nom'];
			$Type_avion=$data['Type'];
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
	if($Type_avion ==10 or $Type_avion ==12)$Porte_avions=true;
	$moda=$Robustesse/$HP;
	$modav=$moda;
	if($Avion_Bombe_Nbr >0 and $Avion_Bombe >0 and $Avion_Bombe !=30)
	{
		$Masse+=($Avion_Bombe*$Avion_Bombe_nbr);
		$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_Nbr));
		$moda=$moda+$charge_sup;
		$modav=$moda-$charge_sup;
	}		
	$avion_img=GetAvionImg($Avion_db,$avion);
	$Meteo=GetMeteo($Saison,$Latitude,$Longitude);
	$Incident=GetIncident($Pilote_pvp,1,$Saison,$Zone_base,$Avion_db,$avion,$c_gaz,true);
	$malus_incident=(100+$Incident[1])/100;
	$ManoeuvreB=GetMan($Avion_db,$avion,$alt,$HP,$moda,$malus_incident,$flaps);
	$Speed=GetSpeed($Avion_db,$avion,$alt,$Meteo[1],$modav,$malus_incident,$c_gaz,$flaps);	
	$QualitePiste=0;
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
	if($Incident[1])$intro.="Vous constatez <b>".$Incident[0]."</b>!<br>";		
	$toolbar=GetToolbar($chemin,$Pilote_pvp,$avion,$HP,$Mun1,$Mun2,$essence,$Meteo[1],$alt,$Puissance,$Longitude,$Latitude,$Cible,$Mission,$c_gaz,$malus_incident,$Avion_db,$flaps,true);
	if($Speed >50)
	{
		//Edit 28.12.2014 : Faciliter l'atterrissage des avions à grande surface alaire
		$Masse-=($ChargeAlaire/2);
		if($Masse <0)$Masse=1;
		//Hydravions
		if($Train ==13 or $Train ==16)$QualitePiste=0-$Meteo[1];		
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
		$Landing=$Pilotage+($ManoeuvreB/5)-($QualitePiste*10)+$Meteo[1]+$Incident[1]+($Moral/10)+($Courage/10)+($Helice*5)+($Train*5)-($Speed/2)-($Malus_Speed*10);		
		if($Helice ==2)
			$Landing_run*=0.75;
		elseif($Helice ==1)
			$Landing_run*=0.9;
		if($Zone_base ==6 or $Porte_avions)
			$Landing_run/=2;
		if($Porte_avions >0)
		{
			$intro.='Vous approchez de votre porte-avions aux commandes de votre <b>'.$NomAvion.'</b>, en vue de l\'appontage.';
			if($Masse <10000)$Exp_pa=25;
		}
		else
			$intro.='Vous approchez du terrain de <b>'.$terrain.'</b> aux commandes de votre <b>'.$NomAvion.'</b>, en vue de l\'atterrissage.';
		if($Landing_run <50)$Landing_run=50;
		if($Landing_run >$LongPiste and $Train !=13 and $Train !=16)
			$Landing=-99999999;	
		elseif($Landing <0 and $Speed <$Vit_mini and $Simu)
			$Landing=-99999998;
		$intro.='<br><br>La météo est la suivante : <b>'.$Meteo[0].'</b>.';
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
			UpdateData("Pilote_PVP","Landings",1,"ID",$Pilote_pvp);
			UpdateData("Pilote_PVP","Points",5,"ID",$Pilote_pvp);
		}
		elseif($Landing <-100)
		{
			$pomp=false;
			$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
			$Pompiers=mt_rand(0,5);			
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
					$perte=true;
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
					$perte=true;
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
					if($Meteo[1] <-50)
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
					$perte=true;
				}
				else
				{
					$intro.='<p>Vous entamez votre descente lorsque '.$Incident[0].' vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
					<p>Votre appareil est gravement endommagé, c\'est une perte totale pour l\'escadrille !</p>';
					$perte=true;
				}
			}
			UpdateData("Pilote_PVP","Points",-2,"ID",$Pilote_pvp);
		}
		else
		{
			if($Incident[0] =="aucun incident notable")
			{
				if($Meteo[1] <-50)
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
		}
	}
	else
	{
		$intro.='<p>Vous entamez votre descente, mais votre vitesse trop lente ('.$Speed.'km/h) vous oblige à vous poser en catastrophe. Vous êtes blessé, mais néanmoins en vie!</p>
		<p>Votre appareil est légèrement endommagé, il sera réparable rapidement !</p>';
		$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');	
	}
	if($perte)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Unite_Intercept=0,Escorte=0,Couverture=0,Avion_Sandbox=0 WHERE ID='$Pilote_pvp'");
		mysqli_close($con);
	}
	//La Cible est supprimée (alerte radar)
	SetData("Pilote_PVP","S_Cible",0,"ID",$Pilote_pvp);	
	$mes.="<p class='lead'>FIN DE MISSION</p>";
	$titre='Atterrissage';
	$_SESSION['atterr']=true;
	$_SESSION['Distance']=0;
	$_SESSION['PVP']=false;
	$menu.="<form action='index.php?view=profil_pvp' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	SetData("Pilote_PVP","S_Unite_Intercept",0,"ID",$Pilote_pvp);
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
include_once('./default.php');