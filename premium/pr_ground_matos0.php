<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 or $OfficierEMID >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_nomission.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$country=$_SESSION['country'];
        include_once __DIR__ . '/../view/menu_infos.php';
?>		<head>
		<script type="text/javascript" src="calendarDateInput.js">
		/***********************************************
		* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
		* Script featured on and available at http://www.dynamicdrive.com
		* Keep this notice intact for use.
		***********************************************/
		</script>
		</head>
		<h2>Simulateur de recrutement</h2>
		<img src="../images/tanks2.jpg">
		<form action="../index.php?view=pr_ground_matos" method="post">
			<table class='table'>
			<thead><tr><th>Officier</th><th>Type</th><th>Date</th></tr></thead>
				<tr><td align="left">
					<select name="Grade" style="width: 150px">
						<option value='5000'><?$Grade=GetAvancement(5000,$country,9,1); echo $Grade[0];?></option>
						<option value='10000'><?$Grade=GetAvancement(10000,$country,10,1); echo $Grade[0];?></option>
						<option value='25000'><?$Grade=GetAvancement(25000,$country,11,1); echo $Grade[0];?></option>
						<option value='50000'><?$Grade=GetAvancement(50000,$country,12,1); echo $Grade[0];?></option>
						<option value='100000'><?$Grade=GetAvancement(100000,$country,13,1); echo $Grade[0];?></option>
						<option value='200000'><?$Grade=GetAvancement(200000,$country,14,1); echo $Grade[0];?></option>
					</select>
				<br><select name="Reput" style="width: 150px">
						<option value='0'>Inconnu</option>
						<option value='1'>Bleu quelque peu aguerri</option>
						<option value='50'>Officier anonyme</option>
						<option value='100'>Reconnu par ses pairs</option>
						<option value='500'>R�put� dans ses rangs</option>
						<option value='1000'>Connu de l'ennemi</option>
						<option value='2000'>Adversaire r�put�</option>
						<option value='5000'>Craint par l'ennemi</option>
						<option value='10000'>Tacticien hors-pair</option>
						<option value='20000'>Grand Strat�ge</option>
						<option value='50000'>H�ros national</option>
						<option value='100000'>R�f�rence historique</option>
						<option value='500000'>L�gende vivante</option>
					</select>
				<br><select name="Pays" style="width: 150px">
							<?DoUniqueSelect("Pays","Pays_ID","Nom",20,"Nom");?>
						</select>
				<br>Mon officier <Input type='Radio' name='Me' value='0' checked>- Non <Input type='Radio' name='Me' value='1'>- Oui<br></td>
					<td align="left">
						<select name="Type" style="width: 150px">
						<option value='6'>Artillerie</option>
						<option value='4'>Artillerie anti-char</option>
						<option value='12'>Artillerie anti-a�rienne</option>
						<option value='11'>Artillerie anti-a�rienne mobile</option>
						<option value='8'>Artillerie mobile</option>
						<option value='5'>Blind� l�ger</option>
						<option value='10'>Blind� lourd</option>
						<option value='7'>Blind� moyen</option>
						<option value='1'>Camion</option>
						<option value='91'>Canon d'assaut</option>
						<option value='93'>Cavalerie</option>
						<option value='9'>Chasseur de char</option>
						<option value='2'>Half-track</option>
						<option value='999'>Infanterie</option>
						<option value='3'>Voiture blind�e</option>
						<option value='14'>Petit navire</option>
						<option value='15'>Corvette</option>
						<option value='16'>Fr�gate</option>
						<option value='17'>Destroyer</option>
						<option value='18'>Croiseur</option>
						<option value='37'>Sous-marin</option>
						<option value='13'>Locomotive</option>
						<option value='998'>Wagon</option>
						</select>
				</td><td><script>DateInput('Date', true, 'YYYY-MM-DD', '1940-05-01')</script></td>
				</tr>
			</table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?
	}
	else
		echo "<img src='../images/top_secret.gif'><div class='alert alert-danger'>Ces donn�es sont classifi�es.<br>Votre rang ne vous permet pas d'acc�der � ces informations.</div>";
}
else
	echo "<img src='../images/top_secret.gif'><div class='alert alert-danger'>Ces donn�es sont classifi�es.<br>Votre rang ne vous permet pas d'acc�der � ces informations.</div>";
?>