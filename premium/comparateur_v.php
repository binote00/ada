<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID or $OfficierEMID)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./menu_infos.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{	
	?>
		<h2>Troupes à comparer</h2>
		<img src="images/tanks.jpg">
		<form action="index.php?view=comparateur_v1" method="post">
			<table class='table'>
				<thead><tr><th>Troupe 1</th><th>Troupe 2</th></tr></thead>
				<tr><td align="left">
						<select name="avion1" class='form-control' style="width: 200px">
							<?DoSelect("Cible", "ID", "Nom", "Nom", "Unit_ok", 1);?>
						</select>
				</td><td align="left">
						<select name="avion2" class='form-control' style="width: 200px">
							<?DoSelect("Cible", "ID", "Nom", "Nom", "Unit_ok", 1);?>
						</select>
				</td></tr>
			</table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
	<?
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
?>