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
		<h3>Encodage �v�nement historique</h3>
		<form action='index.php?view=db_event_h_add' method='post'>
		<table class='table'>
			<tr><td align='left'>
				<Input type='Radio' name='Mode' value='50'>Avion : Nouveau mod�le<br>
				<Input type='Radio' name='Mode' value='56'>Avion : Production Transf�r�e<br>
				<Input type='Radio' name='Mode' value='43'>Nation : Alliance<br>
				<Input type='Radio' name='Mode' value='42'>Nation : Capitulation<br>
				<Input type='Radio' name='Mode' value='55'>Piste : Am�lior�e<br>
				<Input type='Radio' name='Mode' value='51'>Unit� : Cr�ation<br>
				<Input type='Radio' name='Mode' value='54'>Unit� : Changement de Type<br>
				<Input type='Radio' name='Mode' value='52'>Unit� : Dissolution<br>
				<Input type='Radio' name='Mode' value='41'>Unit� : Mouvement<br>
				<Input type='Radio' name='Mode' value='21'>Unit� : Renfort avion<br>
				<Input type='Radio' name='Mode' value='53'>Unit� : Renomm�e<br>
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
		echo "Vous n'avez pas le droit d'acc�der � cette page!";
}
else
	echo "<h1>Vous devez �tre connect� pour acc�der � cette page!</h1>";
?>