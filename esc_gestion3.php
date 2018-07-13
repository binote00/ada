<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$PlayerID=$_SESSION['PlayerID'];
$Officier=Insec($_POST['Officier']);
if($PlayerID >0 AND $Officier >0)
{	
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$Cible=Insec($_POST['Cible']);	
	$Cie=Insec($_POST['Cie']);
	$Charge=Insec($_POST['Charge']);
	$Qty=Insec($_POST['Qty']);
	$Ravit_Off=Insec($_POST['Ravit_Off']);	
	if($Cie >0 and $Charge >0 and $Ravit_Off >0 and $Cible >0)
	{
		$Lieu=GetData("Lieu","ID",$Cible,"Nom");
		$Unite=GetData("Unit","ID",$Cie,"Nom");
		if($Charge ==87)
			$Charge="Essence 87 Octane";
		elseif($Charge ==100)
			$Charge="Essence 100 Octane";
		elseif($Charge ==1)
			$Charge="Diesel";
		elseif($Charge ==300)
			$Charge="Charges";
		elseif($Charge ==400)
			$Charge="Mines";
		elseif($Charge ==800)
			$Charge="Torpilles";
		elseif($Charge <151)
			$Charge="Munitions de ".$Charge."mm";
		elseif($Charge >150)
			$Charge="Bombes de ".$Charge."kg";
		else
			$Charge=$Charge."mm";
		$Msg="[Ceci est un message automatique envoyé par le jeu]\n Demande de ".$Qty." ".$Charge." pour unité aérienne ".$Unite." basée à ".$Lieu;
		SendMsgOff($Ravit_Off,$PlayerID,$Msg,"Demande de ravitaillement",3,2);				
		$mes="Votre demande de ravitaillement a été envoyée.";
		if(!$_SESSION['Trans_ravit'])
			UpdateCarac($PlayerID,"Note",1);
		$_SESSION['Trans_ravit']=true;
	}
	else
		$mes="Votre demande de ravitaillement n'a pas pu été réalisée.";
	$img=Afficher_Image('images/radio_naval.jpg','images/image.png','radio',50);
	echo "<h1>Demande de ravitaillement</h1><p>".$mes."</p>".$img;
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>