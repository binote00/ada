<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Unite=Insec($_POST['Unite']);
$Cible=Insec($_POST['Cible']);
$Type=Insec($_POST['Type']);
$Mission_alt=Insec($_POST['Altitude']);
$Mission_Flight=Insec($_POST['Flight']);
$Briefing=Insec($_POST['Briefing']);
$Reset=Insec($_POST['reset']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $Unite >0)
{	
	$country=$_SESSION['country'];
	include_once('./jfv_txt.inc.php');
	if($Reset ==1)
	{
		SetData("Unit","Mission_Lieu",0,"ID",$Unite);
		SetData("Unit","Mission_Type",0,"ID",$Unite);
		$mes="Vous annulez la mission d'unité en cours!";
	}
	elseif($Reset ==3)
	{
		SetData("Unit","Mission_Lieu_D",0,"ID",$Unite);
		SetData("Unit","Mission_Type_D",0,"ID",$Unite);
		$mes="Vous annulez la demande de mission en cours!";
	}
	else
	{
		$Corps=GetEM_Name($country);
		if($Reset ==5)
		{
			SetData("Unit","Mission_Lieu_D",$Cible,"ID",$Unite);
			SetData("Unit","Mission_Type_D",$Type,"ID",$Unite);
			$mes="<p>Le ".$Corps." vous informe que votre demande de mission a été validée.";
		}
		/*else
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Unit SET Mission_Lieu='$Cible',Mission_Type='$Type',Mission_alt='$Mission_alt',Mission_Flight='$Mission_Flight',Briefing='$Briefing' WHERE ID='$Unite'");
			mysqli_close($con);
			$mes="<p>Le ".$Corps." vous informe que votre ordre de mission a été validé.";
		}*/
		//mail('binote@hotmail.com','Aube des Aigles: Mission Unité',"Unité ".$Unite." / Joueur ".$PlayerID." / Cible ".$Cible." / Type ".$Type);
		$Commandement=GetData("Pilote","ID",$PlayerID,"Commandement");
		if($Commandement <100)
			$credits_txt=MoveCredits($PlayerID,3,-1);
		UpdateCarac($PlayerID,"Avancement",1);
		UpdateCarac($PlayerID,"Gestion",1);
		UpdateCarac($PlayerID,"Commandement",1);
	}
	$titre="Ordre de mission";
	$img="<img src='images/mission".$country.".jpg'>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>