<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$country=$_SESSION['country'];
	$Unite_ravit=Insec($_POST['Unit']);
	$Lieu=Insec($_POST['Cible']);
	$Charge=Insec($_POST['Charge']);
	$Qty=Insec($_POST['Qty']);
	$Ravit_Off=Insec($_POST['Ravit_Off']);
	if($Lieu >0 and $Charge >0 and $Ravit_Off >0 and $Unite_ravit >0)
	{
		$con=dbconnecti();
		$resetu=mysqli_query($con,"UPDATE Unit SET Mission_Type_D=23,Mission_Lieu_D='$Lieu' WHERE ID='$Unite_ravit'");
		mysqli_close($con);
		$Lieu_Nom=GetData("Lieu","ID",$Lieu,"Nom");
		$Unite=GetData("Unit","ID",$Unite_ravit,"Nom");
		if($Charge ==87)
			$Charge="Essence 87 Octane";
		elseif($Charge ==100)
			$Charge="Essence 100 Octane";
		elseif($Charge ==1)
			$Charge="Diesel";
		elseif($Charge ==80)
			$Charge="Rockets";
		elseif($Charge ==300)
			$Charge="Charges";
		elseif($Charge ==400)
			$Charge="Mines";
		elseif($Charge ==800)
			$Charge="Torpilles";
		elseif($Charge ==50 or $Charge ==125 or $Charge ==250 or $Charge ==500)
			$Charge="Bombes de ".$Charge."kg";
		else
			$Charge=$Charge."mm ";
		$msg="Demande de ".$Qty." ".$Charge." pour mon unité ".$Unite." basée à ".$Lieu_Nom;		
		SendMsgOff($Ravit_Off,$PlayerID,$msg,"Demande de ravitaillement",3,3);				
		$mes="<p>Votre demande de ravitaillement a été envoyée.</p>";
	}
	else
		$mes="<p>Votre demande de ravitaillement n'a pas pu été réalisée.</p>";

	echo "<h1>Demande de ravitaillement<h1>".Afficher_Image('images/radio_naval.jpg','images/image.png','radio', 50).$mes;
	include_once('./index.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>