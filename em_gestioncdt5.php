<?/*
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Priorite=Insec($_POST['Priorite']);
if(isset($_SESSION['AccountID']) AND isset($Priorite))
{	
	include_once('./jfv_txt.inc.php');
	$PlayerID=$_SESSION['PlayerID'];
	$country=$_SESSION['country'];
	$Front=GetData("Pilote","ID",$PlayerID,"Front");
	$Commandant=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
	$Adjoint_EM=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Adjoint_EM");	
	if($PlayerID >0 and ($PlayerID ==$Commandant or $PlayerID ==$Adjoint_EM))
	{		
		if($Priorite)
		{
			SetData("Unit","Priorite",1,"ID",$Priorite);
			UpdateCarac($PlayerID,"Missions_Max",1);
			$credits_txt=MoveCredits($PlayerID,3,-1);
		}
		if(GetData("Pilote","ID",$PlayerID,"Credits") <0)
			SetData("Pilote","Endurance",0,"ID",$PlayerID);
		if(!$mes)$mes="Vos ordres ont été exécutés!";
		$img="<img src='images/transfer_yes".$country.".jpg'>";
		$menu="<a href='index.php?view=em_gestioncdt' class='btn btn-default' title='Retour à l Etat-Major'>Retour</a>";
	}
	else
		$mes="<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');*/
?>