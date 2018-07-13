<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 or $OfficierEMID >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
	include_once('./menu_infos.php');
?>
	<h2>Champ de tir</h2>
	<img src="images/champ_tir.jpg">
	<form action="index.php?view=pr_tir1" method="post">
		<table class='table'>
			<tr><th>Arme</th>
				<td align="left">
					<select name="arme" style="width: 150px">
						<?DoSelect("Armes","ID","Nom","Nom","Arme",1);?>
					</select>
			</td><th>Cible</th>
				<td align="left">
					<select name="cible" style="width: 150px">
						<?DoSelect("Cible","ID","Nom","Nom","Unit_ok",1);?>
					</select>
			</td></tr>
		<tr>
			<th>Type de munition</th>
			<td align="left">
				<select name="mun" style="width: 150px">
					<option value='0'>Standard</option>
					<option value='1'>AP (Perforant courte portée)</option>
					<option value='2'>HE (Explosif)</option>
					<option value='4'>APHE (Perforant explosif)</option>
					<option value='6'>APCR (Perforant moyenne portée)</option>
					<option value='8'>HEAT (Charge creuse courte portée)</option>
				</select>
			</td>
			<th>Distance</th>
			<td align="left">
				<select name="distance" style="width: 150px">
					<option value='100'>100m</option>
					<option value='250'>250m</option>
					<option value='500'>500m</option>
					<option value='600'>600m</option>
					<option value='700'>700m</option>
					<option value='800'>800m</option>
					<option value='900'>900m</option>
					<option value='1000'>1000m</option>
					<option value='1500'>1500m</option>
					<option value='2000'>2000m</option>
					<option value='2500'>2500m</option>
					<option value='3000'>3000m</option>
					<option value='5000'>5000m</option>
					<option value='10000'>10000m</option>
					<option value='15000'>15000m</option>
					<option value='20000'>20000m</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Expérience du tireur</th>
			<td align="left">
				<select name="exp" style="width: 150px">
					<option value='10'>Bleu</option>
					<option value='50'>Compétent</option>
					<option value='100'>Entrainé</option>
					<option value='150'>Chevronné</option>
					<option value='200'>Vétéran</option>
					<option value='255'>Expert</option>
				</select>
			</td>
			<th>Position de la cible</th>
			<td align="left">
				<select name="position" style="width: 150px">
					<option value='0'>Position</option>
					<option value='1'>Défensive</option>
					<option value='2'>Retranché</option>
					<option value='3'>Embuscade</option>
					<option value='4'>Mouvement</option>
					<option value='5'>Appui</option>
					<option value='6'>Déroute</option>
					<option value='8'>Sous le feu</option>
					<option value='10'>Ligne</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Météo</th>
			<td align="left">
				<select name="meteo" style="width: 150px">
					<option value='0'>temps clair, vent nul</option>
					<option value='-5'>temps clair, vent faible</option>
					<option value='-10'>nuageux, vent faible</option>
					<option value='-20'>pluie, vent faible</option>
					<option value='-50'>neige, vent faible</option>
					<option value='-75'>Tempête</option>
					<option value='-100'>Tornade</option>
				</select>
			</td>
			<th>Terrain</th>
			<td align="left">
				<select name="terrain" style="width: 150px">
					<option value='0'>Plaine</option>
					<option value='1'>Colline</option>
					<option value='3'>Colline boisée</option>
					<option value='8'>Désert</option>
					<option value='2'>Forêt</option>
					<option value='9'>Jungle</option>
					<option value='11'>Marécages</option>
					<option value='6'>Maritime</option>
					<option value='4'>Montagne</option>
					<option value='5'>Montagne boisée</option>
					<option value='7'>Zone urbaine</option>
				</select>
			</td>
		</tr>
		</table>
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
	<?
	$Dist_shoot=Insec($_POST['distance']);
	$Tir_base=Insec($_POST['exp']);
	$Munition=Insec($_POST['mun']);
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
?>