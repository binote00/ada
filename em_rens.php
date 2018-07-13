<?
/*require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');	
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Rens or $GHQ)
	{
		include_once('./jfv_ground.inc.php');
		$Credits=GetData("Officier_em","ID",$OfficierEMID,"Credits");
		$CT2=2;
		$CT4=4;
		$CT8=8;
		$CT24=24;
		if(IsSkill(207,$OfficierEMID,true))
		{
			$CT2-=1;
			$CT4-=2;
			$CT8-=4;
			$CT24-=8;
		}
		if($Credits >0)
		{
?>
			<form action='index.php?view=em_rens1' method='post'>
			<input type='hidden' name='CT2' value='<?echo $CT2;?>'>
			<input type='hidden' name='CT4' value='<?echo $CT4;?>'>
			<input type='hidden' name='CT8' value='<?echo $CT8;?>'>
			<input type='hidden' name='CT24' value='<?echo $CT24;?>'>
			<table class='table table-800'>
			<thead><tr><th>Renseignement</th></tr></thead>
			<tr><td align="left">
				<?if($Credits >=$CT2){?>
				<Input type='Radio' name='Action' value='6' title='Renseignement'><img src='/images/CT<?echo $CT2;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Obtenir le rapport météo d'un lieu contrôlé par nous<br>
				<?}?>
			</td></tr>
			</table>
			<table class='table table-800'>
			<thead><tr><th>Contre-espionnage</th></tr></thead>
			<tr><td align="left">
				<?if($Credits >=$CT4){?>
				<Input type='Radio' name='Action' value='1' title='Contre-espionnage'><img src='/images/CT<?echo $CT4;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Maquiller le dossier d'un officier<br>
				<?}if($Credits >=$CT8){?>
				<Input type='Radio' name='Action' value='2' title='Contre-espionnage'><img src='/images/CT<?echo $CT8;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Protéger une unité contre l'espionnage<br>
				<?}if($Credits >=$CT8){?>
				<Input type='Radio' name='Action' value='8' title='Contre-espionnage'><img src='/images/CT<?echo $CT8;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Camoufler un site de production contre l'espionnage<br>
				<?}?>
			</td></tr>
			</table>
			<table class='table table-800'>
			<thead><tr><th>Espionnage</th></tr></thead>
			<tr><td align="left">
				<Input type='Radio' name='Action' value='3' title='Espionnage'><img src='/images/CT<?echo $CT4;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Obtenir le rapport météo d'un lieu contrôlé par une puissance étrangère<br>
				<?if($Credits >=$CT24){?>
				<Input type='Radio' name='Action' value='4' title='Espionnage'><img src='/images/CT<?echo $CT24;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Enquêter sur un officier étranger<br>
				<?}if($Credits >=$CT24){?>
				<Input type='Radio' name='Action' value='5' title='Espionnage'><img src='/images/CT<?echo $CT24;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Enquêter sur une unité étrangère<br>
				<?}if($Credits >=$CT24){?>
				<Input type='Radio' name='Action' value='7' title='Espionnage'><img src='/images/CT<?echo $CT24;?>.png' title='Montant en Crédits Temps que nécessite cette action'>- Obtenir des informations sur une usine étrangère, sur base de photos <a href='#' class='popup'><img src='images/help.png'><span>Une reconnaissance stratégique préalable du lieu est nécessaire</span></a><br>
				<?}?>
			</td></tr>
			</table>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?
		}
		else
			echo "<pVous ne disposez pas de suffisamment de temps pour faire cela!</p>";
	}
	else
		PrintNoAccess($country,1,4);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";*/