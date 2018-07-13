<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_air_inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./menu_infos.php');
	if(!$Premium)$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium)
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
		if(!$Pilotage)$Pilotage=GetPilotage($Avion_db,$PlayerID,$avion);
		if($avion)
		{
			$Avion_db="Avion";
			$Moral=100;
			$Courage=100;	
			$con=dbconnecti();	
			$result2=mysqli_query($con,"SELECT Robustesse,Nom,Masse,ChargeAlaire,ManoeuvreB,ManoeuvreH,Maniabilite FROM $Avion_db WHERE ID='$avion'");
			mysqli_close($con);
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$HP=$data['Robustesse'];
					$NomAvion=$data['Nom'];
					$Masse=$data['Masse'];
					$ChargeAlaire=$data['ChargeAlaire'];
					$ManB=$data['ManoeuvreB'];
					$ManH=$data['ManoeuvreH'];
					$Mani=$data['Maniabilite'];
				}
				mysqli_free_result($result2);
			}
			$ManoeuvreB=GetMano($ManH,$ManB,$HP,$HP,1,1,1,$flaps);
			$Mani=GetMani($Mani,$HP,$HP,1,1,$flaps);
				//$Zone=GetData("Lieu","ID",$Cible,"Zone");
				/*for($Zone=0;$Zone<9;$Zone++)
				{*/
					switch($Zone)
					{
						case 0:
							$mes='<br>Vous tentez d\'atterrir en rase campagne aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(20,80);
						break;
						case 1:
							$mes='<br>Vous tentez d\'atterrir dans ces collines aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(10,70);
						break;
						case 2:
							$mes='<br>Vous tentez d\'atterrir dans une clairière aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(10,80);
						break;
						case 3:
							$mes='<br>Vous tentez d\'atterrir dans ces collines boisées aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(0,60);
						break;
						case 4:
							$mes='<br>Vous tentez d\'atterrir dans ces montagnes aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(0,30);
						break;
						case 5:
							$mes='<br>Vous tentez d\'atterrir dans ces montagnes boisées aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(0,10);
						break;
						case 6:
							$mes='<br>Vous tentez d\'amerrir aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(90,100) - $meteo;
							//$ChargeAlaire /=10;
						break;
						case 7:
							$mes='<br>Vous tentez d\'atterrir dans cette zone urbaine aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(10,90);
						break;
						case 8:
							$mes='<br>Vous tentez d\'atterrir en plein désert aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(20,80);
						break;
						case 9:
							$mes='<br>Vous tentez d\'atterrir en pleine jungle aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(0,20);
						break;
						case 11:
							$mes='<br>Vous tentez d\'atterrir en plein marécage aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=mt_rand(0,20);
						break;
						case 12: case 15:
							$mes='<br>Vous tentez d\'atterrir aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=0;
						break;
						case 13:
							$mes='<br>Vous tentez d\'atterrir aux commandes de votre <b>'.$NomAvion.'</b>.';
							$QualitePiste=0;
						break;
					}				
					if($Zone <12)
					{
						$Piste=(100-$QualitePiste)*2;
						$Landing=$Pilotage + $ManoeuvreB - ($ChargeAlaire/20) - ($Piste*2) + ($Moral/10) + ($Courage/10) + ($Helice*5) + ($Train*20) - $c_gaz + $meteo;
						if($Landing <-100)
						{
							$menu='<b>Crash total !</b>';
							$img=Afficher_Image('images/avions/crash'.$avion.'.jpg', 'images/crash.jpg', 'Crash', 50);
						}
						elseif($Landing <-50)
						{
							$menu='<b>Crash !</b>';
							$img=Afficher_Image('images/avions/crash'.$avion.'.jpg', 'images/crash.jpg', 'Crash', 50);
						}
						elseif($Landing <-0)
						{
							$menu='<b>Crash, mais de justesse !</b>';
							$img=Afficher_Image('images/avions/crash'.$avion.'.jpg', 'images/crash.jpg', 'Crash', 50);
						}
						else
						{
							$menu='<b>Avion posé avec succès !</b>';
							$img=Afficher_Image('images/avions/landing'.$avion.'.jpg', 'images/avions/decollage'.$avion.'.jpg', 'Atterrissage', 50);
						}
					}
					else
					{
						$Masse+=$charge;
						//Edit 28.12.2014 : Faciliter l'atterrissage des avions à grande surface alaire
						$Masse-=($ChargeAlaire/2);
						$Speed=GetSpeed($Avion_db,$avion,1,$meteo,1,1,$c_gaz,$flaps);
						$Pil_mod=(pow($Pilotage,2)/1000);		
						$Vit_mini=((100+sqrt($Masse))*(1-($flaps/10)))-($Pilotage/10);
						if($Vit_mini >245)$Vit_mini=245;
						$Landing_run=round($Masse/15*$Speed/$Vit_mini)-$Pil_mod;						
						$Landing=$Pilotage+($ManoeuvreB/5)-($QualitePiste*10) + $meteo + ($Moral/10) + ($Courage/10) + ($Helice*5) + ($Train*5) - ($Speed/2);					
						$img=Afficher_Image('images/avions/crash'.$avion.'.jpg','images/crash.jpg','Crash',50);
						if($Helice ==2)
							$Landing_run*=0.75;
						elseif($Helice ==1)
							$Landing_run*=0.9;
						elseif($Zone ==15)
							$Landing_run/=2;
						if($Landing_run >$LongPiste)
							$menu="Crash après une course de ".round($Landing_run)."m";
						elseif($Speed <$Vit_mini)
							$menu="Crash du à une vitesse trop lente de ".$Speed."km/h (au lieu de ".round($Vit_mini)."km/h)";					
						elseif($Landing <0)
							$menu="Crash du à la météo ou à un manque d'expérience de pilotage";
						else
						{
							$img=Afficher_Image('images/avions/landing'.$avion.'.jpg','images/avions/decollage'.$avion.'.jpg','Atterrissage',50);
							$menu="Atterrissage réussi, après une course de ".round($Landing_run)."m à la vitesse de ".$Speed."km/h";
						}
					}
				//}
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
	<h2>Test d'atterrissage</h2>
		<form action="index.php?view=pr_landing" method="post">
		<table class='table table-striped'>
			<tr>
				<th>Pilote</th>
				<td align="left">
					<select name="pilote" class='form-control' style="width: 200px">
						<option value='<?echo $Pilotage;?>'>Votre pilote</option>
						<option value='0'>Bleu</option>
						<option value='25'>Apte</option>
						<option value='50'>Compétent</option>
						<option value='75'>Entrainé</option>
						<option value='100'>Chevronné</option>
						<option value='125'>Vétéran</option>
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
						<option value='0'>Rentrés</option>
						<option value='1'>1 cran</option>
						<option value='2'>2 crans</option>
						<option value='3'>3 crans</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Hélice</th>
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
						<option value='2'>Renforcé</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Terrain</th>
				<td align="left">
					<select name="terrain" class='form-control' style="width: 200px">
						<option value='13'>Piste en terre/herbe/sable</option>
						<option value='12'>Piste en dur</option>
						<option value='15'>Piste de porte-avions</option>
						<option value='0'>Campagne</option>
						<option value='1'>Colline</option>
						<option value='3'>Colline boisée</option>
						<option value='8'>Désert</option>
						<option value='2'>Forêt</option>
						<option value='9'>Jungle</option>
						<option value='11'>Marécages</option>
						<option value='4'>Montagne</option>
						<option value='5'>Montagne boisée</option>
						<option value='7'>Zone urbaine</option>
					</select>
				</td>
				<th>Longueur de piste</th>
				<td align="left">
					<select name="piste" class='form-control' style="width: 200px">
						<option value='0'>Aucune</option>
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
				<th>Météo</th>
				<td align="left">
					<select name="meteo" class='form-control' style="width: 200px">
						<option value='0'>temps clair, vent nul</option>
						<option value='-5'>temps clair, vent faible</option>
						<option value='-10'>nuageux, vent faible</option>
						<option value='-20'>pluie, vent faible</option>
						<option value='-50'>neige, vent faible</option>
						<option value='-75'>Tempête</option>
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