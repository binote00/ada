<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID = $_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	$Off = Insec($_POST['Off']);
	$Div = Insec($_POST['Div']);
	if($Div >0 and $Off >0)
	{
		SetData("Division","Cdt",$Off,"ID",$Div);
		$mes="Vous avez nommé un nouveau commandant de division.";
		$menu="<a href='index.php?view=ground_em' class='btn btn-default' title='Retour'>Retour</a>";
	}
	else
		$mes="Erreur";
	$titre="Gestion des unités terrestres";
	include_once('./default.php');
}
?>