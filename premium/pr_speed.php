<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./menu_infos.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$avion=Insec($_POST['avion']);
		$flaps=Insec($_POST['volets']);
		$gaz=Insec($_POST['gaz']);
		$meteo=Insec($_POST['meteo']);
		$alt=Insec($_POST['altitude']);
		$charge=Insec($_POST['charge']);
		$HP=Insec($_POST['hp']);	
		if($avion)
		{
			if($HP)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID,Nom,Masse,VitesseB,VitesseH,Engine,Alt_ref,Plafond FROM Avion WHERE ID='$avion'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
					{
						$ID=$data['ID'];
						$Nom=$data['Nom'];
						$VitesseB=$data['VitesseB'];
						$VitesseH=$data['VitesseH'];
						$Moteur=$data['Engine'];
						$alt_ref=$data['Alt_ref'];
						$Plafond=$data['Plafond'];
						$Masse=$data['Masse'];
					}
					mysqli_free_result($result);
					unset($data);
				}		
				if($alt >$Plafond)$img="Le plafond maximal de cet avion étant de ".$Plafond."m, l'altitude a été adaptée";
				if(!$alt_ref)
				{
					$Compresseur=GetData("Moteur","ID",$Moteur,"Compresseur");
					if($Compresseur ==3)
						$alt_ref=2000;
					elseif($Compresseur ==2)
						$alt_ref=7500;
					else
						$alt_ref=5000;
				}
				if($alt > $alt_ref)
					$Vit=$VitesseH +((($VitesseH-$VitesseB)/$alt_ref)*($alt_ref-$alt));
				elseif($alt <= $alt_ref)
					$Vit=$VitesseB +((($VitesseH-$VitesseB)/$alt_ref)*$alt);			
				if($HP)
					$moda=100/$HP;
				if($charge)
				{
					$charge_sup=2/($Masse/$charge);
					$moda*=(1+$charge_sup);	
				}
				if($meteo <-50)$moda+=0.2;
				$Speed=round($Vit/$moda*$gaz/100);		
				if($flaps)$Speed*=((10-$flaps)/10);
				if($Speed <0)$Speed=0;				
				$mes="<h2>".$Nom."</h2>
				<table>
				<tr><td colspan='2'><img src='images/avions/vol".$ID.".jpg' alt='".$Nom."'></td>
				<tr><th>Meteo</th><td><img src='images/meteo".$meteo.".gif'></td></tr>
				<tr><th>Altitude</th><td>".$alt."m</td></tr>
				<tr><th>Charge</th><td>".$charge."kg</td></tr>
				<tr><th>Gaz</th><td>".$gaz."%</td></tr>
				<tr><th>Volets</th><td>".$flaps." crans</td></tr>
				</table>
				<p class='lead'>Vitesse estimée : <b>".$Speed."km/h</b></p>";
			}
			else
				$mes="Un avion détruit a forcément une vitesse nulle...";
			echo $mes;
		}
		else
		{
				$country=$_SESSION['country'];
				$con=dbconnecti();
				$query="SELECT DISTINCT ID,Nom,Masse FROM Avion WHERE Pays='$country' AND Etat=1 ORDER BY Nom ASC";
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
					{
						$modele.="<option value='".$data['ID']."'>".$data['Nom']." (".$data['Masse']."kg)</option>";
					}
					mysqli_free_result($result);
				}
	?>
	<h2>Test de vitesse</h2>
		<form action="index.php?view=pr_speed" method="post">
		<table class='table'>
			<tr><th>Avion</th>
				<td align="left">
					<select name="avion" class='form-control' style="width: 250px">
					<?echo $modele;?>
					</select>
			</td></tr>
			<tr>
				<th>Robustesse</th>
				<td align="left">
					<select name="hp" class='form-control' style="width: 200px">
						<option value='100'>100%</option>
						<option value='90'>90%</option>
						<option value='80'>80%</option>
						<option value='70'>70%</option>
						<option value='60'>60%</option>
						<option value='50'>50%</option>
						<option value='40'>40%</option>
						<option value='30'>30%</option>
						<option value='20'>20%</option>
						<option value='10'>10%</option>
					</select>
				</td>
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
			</tr>
			<tr>
				<th>Altitude</th>
				<td align="left">
					<select name="altitude" class='form-control' style="width: 200px">
						<option value='100'>100m</option>
						<option value='500'>500m</option>
						<option value='1000'>1000m</option>
						<option value='2000'>2000m</option>
						<option value='3000'>3000m</option>
						<option value='4000'>4000m</option>
						<option value='5000'>5000m</option>
						<option value='6000'>6000m</option>
						<option value='7000'>7000m</option>
						<option value='8000'>8000m</option>
						<option value='9000'>9000m</option>
						<option value='10000'>10000m</option>
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
			<tr>
				<th>Gaz</th>
				<td align="left">
					<select name="gaz" class='form-control' style="width: 200px">
						<option value='100'>100%</option>
						<option value='90'>90%</option>
						<option value='80'>80%</option>
						<option value='70'>70%</option>
						<option value='60'>60%</option>
						<option value='50'>50%</option>
						<option value='40'>40%</option>
						<option value='30'>30%</option>
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
		</table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'>
		</form>
	<?
		}
		include_once('./index.php');
	}
}?>