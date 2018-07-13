<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA)
	{
		$ia_pilots=Insec($_POST['ia_pilots']);
		if($ia_pilots)
		{
			$Formation=count($ia_pilots);
			$ia_pilotes=implode(',',$ia_pilots);
			if($Formation >0 and $ia_pilotes)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Avion=0,Task=0,Couverture=0,Escorte=0,Couverture_Nuit=0 WHERE ID IN (".$ia_pilotes.")") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esc_task-cible-ia');
				mysqli_close($con);
				$mes.="<br><b>".$Formation."</b> pilotes sont rappelés à la base";
			}
		}
		else
			$mes="Une erreur est survenue!";
		echo "<h1>Rappeler les pilotes</h1><p>".$mes."</p><a href='index.php?view=esc_missions' class='btn btn-default' title='Retour'>Retour</a>";
	}
	else
	{
		echo "<h6>Vous la reverrez un jour votre escadrille...</h6>";
		echo "<img src='images/unites".$country.".jpg'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>