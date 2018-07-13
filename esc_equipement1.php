<?
/*require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
if(isset($_SESSION['AccountID']))
{
	$PlayerID = $_SESSION['PlayerID'];
	$country = $_SESSION['country'];
	$Action = Insec($_POST['Action']);
	$Item = Insec($_POST['Item']);
	$Coffre = Insec($_POST['Slot']);
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] == 0)
	{
		if($Item)
		{
			$img="<img src='images/esc_equipment.jpg'>";
			$Slot="Slot".GetData("Matos","ID",$Item,"Slot");
			$Coffre="Coffre".$Coffre;
			if($Action)
			{
				$con=dbconnecti();
				$query3="UPDATE Pilote SET $Slot='$Item' WHERE ID='$PlayerID'";
				$ok3=mysqli_query($con,$query3);
				mysqli_close($con);
				if($ok3)
				{
					$Unite=GetData("Pilote","ID",$PlayerID,"Unit");
					SetData("Unit",$Coffre,0,"ID",$Unite);
					$mes="Matériel mis à jour<br>Votre pilote a reçu ".GetData("Matos","ID",$Item,"Nom")."<br><a href='index.php?view=esc_equipement'>Retour à l'équipement d'unité</a>";
					$skills=MoveCredits($PlayerID,13,-12);
				}
				else
					$mes="Mise à joue échouée ".mysqli_error($con);
			}
			else
			{
				$Unite=GetData("Pilote","ID",$PlayerID,"Unit");
				SetData("Unit",$Coffre,0,"ID",$Unite);
				$mes = "Matériel mis à jour<br>".GetData("Matos","ID",$Item,"Nom")." supprimé de l'inventaire.<br><a href='index.php?view=esc_equipement'>Retour à l'équipement d'unité</a>";
			}
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";						
	}
	else
	{
		//MIA
		$mes="Peut-être la reverrez-vous un jour votre escadrille...";
		$img="<table border='1' align='center'><tr><td><img src='images/unites".$country.".jpg'></td></tr></table>";
	}
}
else
	echo "<font color='#000000' size='4'>Vous devez être connecté pour accéder à cette page!</font>";
include_once('./index.php');*/
?>