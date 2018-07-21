<?
require_once('./jfv_inc_sessions.php');
$OfficierID = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if($OfficierID >0 or $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
	?>
	<h1>Simulateur de combat</h1>
	<form action="pr_beat1.php" method="post">
		<table border="0" cellspacing="1" cellpadding="1" bgcolor="#ECDDC1">
			<tr><th colspan="4" class="TitreBleu_bc">Simulateur de combat</th></tr>
			<tr><td colspan="4"><img src="images/tanks.jpg"></td></tr>
			<tr><th bgcolor="tan">Troupe</th>
				<td align="left">
					<select name="avion1" style="width: 150px">
						<?DoSelect("Cible","ID","Nom","Nom","Unit_ok",1);?>
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