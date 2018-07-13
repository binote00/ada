<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	$Encodage=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
	if($Encodage > 0)
	{
	?>
		<h3>Encodage évènement historique</h3>
		<form action='index.php?view=db_event_h_add' method='post'>
		<table class='table'>
			<tr><td align='left'>
				<Input type='Radio' name='Mode' value='50'>Avion : Nouveau modèle<br>
				<Input type='Radio' name='Mode' value='56'>Avion : Production Transférée<br>
				<Input type='Radio' name='Mode' value='43'>Nation : Alliance<br>
				<Input type='Radio' name='Mode' value='42'>Nation : Capitulation<br>
				<Input type='Radio' name='Mode' value='55'>Piste : Améliorée<br>
				<Input type='Radio' name='Mode' value='51'>Unité : Création<br>
				<Input type='Radio' name='Mode' value='54'>Unité : Changement de Type<br>
				<Input type='Radio' name='Mode' value='52'>Unité : Dissolution<br>
				<Input type='Radio' name='Mode' value='41'>Unité : Mouvement<br>
				<Input type='Radio' name='Mode' value='21'>Unité : Renfort avion<br>
				<Input type='Radio' name='Mode' value='53'>Unité : Renommée<br>
				<Input type='Radio' name='Mode' value='40'>Ville : Occupation<br>
			</td></tr>		
			<tr><th>Nation</th>
			<td align="left">
				<select name='Nation'>
				<? DoUniqueSelect("Pays","Pays_ID","Nom",20,"Nom");?>
				</select>
			</td></tr>
		</table>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
	<?
	}
	else
		echo "Vous n'avez pas le droit d'accéder à cette page!";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>