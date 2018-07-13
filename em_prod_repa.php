<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID = $_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) and $OfficierEMID)
{
	include_once('./jfv_include.inc.php');
	$Credits=GetData("Officier_em","ID",$OfficierEMID,"Credits");
	$country=$_SESSION['country'];
	$Avion=Insec($_POST['avion']);
    $Type=Insec($_POST['Type']);
	$CT=Insec($_POST['CT']);
	if(!$CT)$CT=8;
	if($Credits>=$CT)
	{
		UpdateData("Avion","Reserve",1,"ID",$Avion);
		UpdateData("Officier_em","Credits",-$CT,"ID",$OfficierEMID);
		UpdateCarac($OfficierEMID,"Avancement",10,"Officier_em");
		$mes='Vos ordres permettent de réparer un avion.';
		$img_txt='repare'.$country;
	}
	else
	{
		$mes='Vous manquez de temps! La réparation est impossible.';
		$img_txt='transfer_no'.$country;
	}
	/*$titre="Atelier";
	$img="<img src='images/".$img_txt.".jpg'>";
	$menu="<a class='btn btn-default' title='Retour' href='index.php?view=em_production0'>Retour</a>";
	if(!$mes)$mes="Vous avez le sentiment du devoir accompli.";
	include_once('./index.php');*/
    header('Location: ./index.php?view=em_production_'.$Type.'');
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';