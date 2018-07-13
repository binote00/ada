<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $Admin)
	{	
		if($OfficierEMID ==$Commandant or $Admin)
			$Menu_Cdt="<li><a class='btn btn-primary' href='index.php?view=em_mutation'>Mutations</a></li>";
		echo "<h2>Effectifs</h2>
		<ul class='list-inline'>
			<li><a class='btn btn-primary' href='index.php?view=em_personnel_ia'>Pilotes en service</a></li>
			<li><a class='btn btn-primary' href='index.php?view=em_avions'>Avions en service</a></li>
			<li><a class='btn btn-primary' href='index.php?view=em_personnel'>Commandants en service</a></li>
			<li><a class='btn btn-primary' href='index.php?view=em_recrues'>Jeunes recrues</a></li>
			<li><a class='btn btn-primary' href='index.php?view=em_commando'>Commandos</a></li>
			<li><a class='btn btn-primary' href='index.php?view=em_mia'>Pilotes fatigués et MIA</a></li>
			".$Menu_Cdt."
		</ul>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>