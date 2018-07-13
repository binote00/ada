<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium)
		$Pr_txt="<option value='31'>Le mois dernier</option><option value='92'>Le trimestre dernier</option><option value='366'>L'année dernière</option>";
	if($Admin)
		$Pr_txt="<option value='2000'>Le début du jeu</option>";
	echo "<h1>Journal Personnel</h1><img src='images/journal.jpg'><h3>Depuis</h3>
	<form action='index?view=user_journal' method='post'>
	<select name='datej' class='form-control' style='width: 200px'><option value='8'>La semaine dernière</option>".$Pr_txt."</select>
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
	<p class='lead'><img src='images/premium50.png' title='Information Premium'>Les utilisateurs Premium peuvent consulter le journal de leur pilote au-delà d'une semaine, jusqu'au début du jeu.</p>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>