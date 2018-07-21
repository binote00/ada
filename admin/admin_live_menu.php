<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
	if($Admin >0)
	{
		echo '<h1>Events Live</h1>';
		?><form action='index.php?view=admin_mod_live_move' method='post'>
			<select name='Nation'>
			<?DoUniqueSelect('Pays','Pays_ID','Nom',20,'Nom');?>
			</select>
			<input type='Submit' value='Déplacer une unité' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
		</form><?
		echo "<br><form action='index.php?view=admin_mod_p' method='post'>
		<input type='Submit' value='Modifier un Pilote' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
		</form>";
		echo "<br><form action='index.php?view=admin_mod_o' method='post'>
		<input type='Submit' value='Modifier un Officier' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
		</form>";
	}
	else
		echo "<h1>Vous n'avez pas le droit d'accéder à cette page!</h1>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>