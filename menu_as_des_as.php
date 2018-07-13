<?
$PlayerID = $_SESSION['PlayerID'];
if($PlayerID > 0)
{
	$Free = GetData("Joueur","ID",$PlayerID,"Free");
?>
<h1>As des As</h1>
	<a class="btn btn-default" href="index.php?view=tableau_chasse">Tableau de chasse</a>
	<!--<a class="btn btn-default" href="index.php?view=as_sandbox">Le classement</a>-->
	<a class="btn btn-default" href="index.php?view=as_des_as_avions">Les avions</a>
	<a class="btn btn-default" href="index.php?view=as_des_as_init">La mission</a>
	<a class="btn btn-default" href="index.php?view=garage_sandbox">Le garage</a>
	<p>Ce mode de jeu est un défouloir où rien n'a de conséquence sur la campagne en cours.<br>Faites vous plaisir !</p>
	<p class='lead'>Points AS des AS : <b><?echo $Free;?></b></p>
<?}?>
