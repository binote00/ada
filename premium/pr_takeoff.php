<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_combat.inc.php');
    include_once __DIR__ . '/../view/menu_infos.php';
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$avion=Insec($_POST['avion']);
		$flaps=Insec($_POST['volets']);
		$c_gaz=Insec($_POST['gaz']);
		$meteo=Insec($_POST['meteo']);
		$Helice=Insec($_POST['helice']);
		$Train=Insec($_POST['train']);
		$charge=Insec($_POST['charge']);
		$LongPiste=Insec($_POST['piste']);
		$Zone=Insec($_POST['terrain']);
		$Pilotage=Insec($_POST['pilote']);	
		if($avion)
		{
			if($c_gaz <20)
			{
				$menu="<p>Vous entamez votre course de d�collage, mais le manque de puissance emp�che votre appareil de quitter le sol !</p>";
				$img=Afficher_Image('images/avions/crash'.$avion.'.jpg','images/avions/crash.jpg','crash',50);
			}
			else
			{
				$Avion_db="Avion";
				$Moral=100;
				$Courage=100;	
				$Tour_base=100;
				$QualitePiste=0;
				$HP=GetData($Avion_db,"ID",$avion,"Robustesse");
				$NomAvion=GetData($Avion_db,"ID",$avion,"Nom");
				$ChargeAlaire=GetData($Avion_db,"ID",$avion,"ChargeAlaire");
				$ManoeuvreB=GetMan($Avion_db,$avion,1,$HP,1,1,$flaps);
				$Mani=GetMani($Avion_db,$avion,$HP,1,1,$flaps);
				$Masse=GetData($Avion_db,"ID",$avion,"Masse")+$charge;
				//$Pilotage=GetPilotage($Avion_db, $PlayerID, $avion);
				$Pil_mod=(pow($Pilotage,2)/1000);		
				if($Nuit)
					$meteo_malus=$meteo+85;
				else
					$meteo_malus=$meteo;		
				//Porte-avions et Hydravions
				if($Train ==13 or $Train ==16)
				{
					$QualitePiste=0-$meteo_malus;
					$BaseAerienne=2;
				}
				elseif($Zone ==2)
				{
					$QualitePiste-=$meteo_malus;
					$BaseAerienne=1;
					if($avion ==398)$Masse*=0.8;
				}
				else
					$BaseAerienne=3;
				$Decollage=$Pilotage+($ManoeuvreB/10)-($QualitePiste*10)+($meteo_malus *3)+($Moral/10)+($Courage/10)+($Helice*5)+($Train*5)-((100-$Tour_base)/10);		
				if($flaps <3)$Masse*=(1-($flaps/10));			
				if($BaseAerienne < 3)
					$Takeoff_run=round($Masse/20/$c_gaz*100) -$Pil_mod;
				elseif($meteo_malus <-19 and $meteo_malus !=-70)
					$Takeoff_run=round($Masse/5/$c_gaz*100) -$Pil_mod;
				else
					$Takeoff_run=round($Masse/10/$c_gaz*100) -$Pil_mod;			
				if($Helice ==2)
					$Takeoff_run*=0.75;
				elseif($Helice ==1)
					$Takeoff_run*=0.9;
				if($BaseAerienne >2 and ($Train ==2 or $Train >6))
					$Takeoff_run*=0.9;
				elseif($Porte_avions and ($Train ==2 or $Train >6))
					$Takeoff_run*=0.9;
				if($Takeoff_run > $LongPiste and $Train !=13 and $Train !=16)
					$Decollage=-99999999;
				if($Decollage >0)
				{
					if($Takeoff_run <75)$Takeoff_run=50+($Masse/100);
					$menu="<p>Vous d�collez sans probl�me, au terme d'une course de ".round($Takeoff_run)."m !</p>";
					$img=Afficher_Image('images/avions/decollage'.$avion.'.jpg','images/avions/landing'.$avion.'.jpg','Decollage',50);
				}
				elseif($Decollage <-50)
				{
					if($Decollage ==-99999999)
					{
						if($Porte_avions >0)
							$menu ="<p>Votre avion ne parvient pas � s'arracher du pont d'envol. Passant par dessus bord apr�s une course de ".round($Takeoff_run)."m, vous crashez votre avion en mer !</p>";
						else
							$menu ="<p>Avalant toute la piste (".round($Takeoff_run)."m parcouru / piste de ".$LongPiste."m), votre avion ne parvient pas � s'arracher du sol. Vous vous crashez en bout de piste !</p>";
					}
					else
					{			
						if($QualitePiste !=0 and ($Train ==13 or $Train ==16))
							$menu="<p>Incapable de d�jauger correctement � cause du mauvais temps, votre avion percute une vague de plein fouet !</p>";			
						elseif($QualitePiste !=0)
							$menu="<p>Vous entamez votre course de d�collage, mais vous ne pouvez emp�cher votre avion d'aller dans le d�cor � cause de l'�tat de la piste !</p>";
						elseif($meteo_malus <-49)
							$menu="<p>Vous entamez votre course de d�collage, mais la m�t�o vous oblige � interrompre votre mission. Quelle poisse !</p>";
						else
							$menu="<p>Vous entamez votre course de d�collage lorsqu'un incident vous oblige � interrompre votre mission. Quelle poisse !</p>";
					}
					$img=Afficher_Image('images/avions/crash'.$avion.'.jpg','images/avions/crash.jpg','crash',50);
				}
				else
				{
					$menu="<p>Vous entamez votre course de d�collage lorsqu'un incident vous oblige � interrompre votre mission. Quelle poisse !</p>
					<p>Votre appareil est l�g�rement endommag�, il sera r�parable rapidement !</p>";
					$img=Afficher_Image('images/avions/crash'.$avion.'.jpg','images/avions/crash.jpg','crash',50);
				}
			}
			echo '<br>'.$img;
			echo '<br>'.$mes;
			echo '<br>'.$menu;
		}
		else
		{
			$country=$_SESSION['country'];
			$query="SELECT DISTINCT ID,Nom FROM Avion WHERE Pays='$country' AND Etat=1 ORDER BY Nom ASC";
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				while ($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
				{
					$modele.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
				}
				mysqli_free_result($result);
			}
	?>
	<h2>Test de d�collage</h2>
		<form action="../index.php?view=pr_takeoff" method="post">
		<table class='table table-striped'>
			<tr>
				<th>Pilote</th>
				<td align="left">
					<select name="pilote" class='form-control' style="width: 200px">
						<option value='0'>Bleu</option>
						<option value='25'>Apte</option>
						<option value='50'>Comp�tent</option>
						<option value='75'>Entrain�</option>
						<option value='100'>Chevronn�</option>
						<option value='125'>V�t�ran</option>
						<option value='150'>Expert</option>
						<option value='200'>Virtuose</option>
					</select>
				</td>
				<th>Avion</th>
				<td align="left">
					<select name="avion" class='form-control' style="width: 200px">
					<?echo $modele;?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Charge</th>
				<td align="left">
					<select name="charge" class='form-control' style="width: 200px">
						<option value='0'>Aucune</option>
						<option value='50'>50kg</option>
						<option value='100'>100kg</option>
						<option value='125'>125kg</option>
						<option value='150'>150kg</option>
						<option value='200'>200kg</option>
						<option value='250'>250kg</option>
						<option value='500'>500kg</option>
						<option value='750'>750kg</option>
						<option value='1000'>1000kg</option>
						<option value='1500'>1500kg</option>
						<option value='2000'>2000kg</option>
						<option value='2500'>2500kg</option>
						<option value='3000'>3000kg</option>
						<option value='4000'>4000kg</option>
						<option value='5000'>5000kg</option>
						<option value='6000'>6000kg</option>
						<option value='7000'>7000kg</option>
						<option value='8000'>8000kg</option>
						<option value='9000'>9000kg</option>
						<option value='10000'>10000kg</option>
					</select>
				</td>
				<th>Gaz</th>
				<td align="left">
					<select name="gaz" class='form-control' style="width: 200px">
						<option value='100'>100%</option>
						<option value='95'>95%</option>
						<option value='90'>90%</option>
						<option value='85'>85%</option>
						<option value='80'>80%</option>
						<option value='75'>75%</option>
						<option value='70'>70%</option>
						<option value='65'>65%</option>
						<option value='60'>60%</option>
						<option value='55'>55%</option>
						<option value='50'>50%</option>
						<option value='45'>45%</option>
						<option value='40'>40%</option>
						<option value='35'>35%</option>
						<option value='30'>30%</option>
						<option value='25'>25%</option>
						<option value='20'>20%</option>
						<option value='10'>10%</option>
					</select>
				</td>
				<th>Volets</th>
				<td align="left">
					<select name="volets" class='form-control' style="width: 200px">
						<option value='0'>Rentr�s</option>
						<option value='1'>1 cran</option>
						<option value='2'>2 crans</option>
						<option value='3'>3 crans</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>H�lice</th>
				<td align="left">
					<select name="helice" class='form-control' style="width: 200px">
						<option value='0'>De Base (pas constant)</option>
						<option value='1'>Pas variable manuel</option>
						<option value='2'>Pas variable automatique</option>
					</select>
				</td>
				<th>Train</th>
				<td align="left">
					<select name="train" class='form-control' style="width: 200px">
						<option value='0'>De Base</option>
						<option value='2'>Renforc�</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Terrain</th>
				<td align="left">
					<select name="terrain" class='form-control' style="width: 200px">
						<option value='3'>Piste en terre/herbe/sable</option>
						<option value='1'>Piste en dur</option>
						<option value='2'>Piste de porte-avions</option>
					</select>
				</td>
				<th>Longueur de piste</th>
				<td align="left">
					<select name="piste" class='form-control' style="width: 200px">
						<option value='0'>Aucune</option>
						<option value='200'>200m</option>
						<option value='200'>250m</option>
						<option value='300'>300m</option>
						<option value='400'>400m</option>
						<option value='500'>500m</option>
						<option value='600'>600m</option>
						<option value='700'>700m</option>
						<option value='800'>800m</option>
						<option value='900'>900m</option>
						<option value='1000'>1000m</option>
						<option value='1200'>1200m</option>
						<option value='1400'>1400m</option>
						<option value='1600'>1600m</option>
						<option value='1800'>1800m</option>
						<option value='2000'>2000m</option>
						<option value='2400'>2400m</option>
					</select>
				</td>
				<th>M�t�o</th>
				<td align="left">
					<select name="meteo" class='form-control' style="width: 200px">
						<option value='0'>temps clair, vent nul</option>
						<option value='-5'>temps clair, vent faible</option>
						<option value='-10'>nuageux, vent faible</option>
						<option value='-20'>pluie, vent faible</option>
						<option value='-50'>neige, vent faible</option>
						<option value='-75'>Temp�te</option>
						<option value='-100'>Tornade</option>
					</select>
				</td>
			</tr>
		</table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
	<?
			include_once('./index.php');
		}
	}
}?>