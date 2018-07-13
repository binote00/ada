<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Tete=Insec($_POST['1']);
$Visage=Insec($_POST['2']);
$Dos=Insec($_POST['3']);
$Torse=Insec($_POST['4']);
$Ceinture=Insec($_POST['5']);
$Poignet=Insec($_POST['6']);
$Mains=Insec($_POST['7']);
$Arme=Insec($_POST['8']);
$Pieds=Insec($_POST['9']);
$Poche=Insec($_POST['10']);
$Sac=Insec($_POST['11']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND isset($Dos) AND isset($Torse))
{
	$country=$_SESSION['country'];
	$con=dbconnecti(1);
	$CT=mysqli_result(mysqli_query($con,"SELECT SUM(Cout) FROM Matos WHERE ID IN('$Tete','$Visage','$Dos','$Torse','$Ceinture','$Poignet','$Mains','$Arme','$Pieds','$Poche','$Sac')"),0);
	mysqli_close($con);
	$Credits=GetData("Pilote","ID",$PlayerID,"Credits");	
	if($Credits >=$CT)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Slot1,Slot2,Slot3,Slot4,Slot5,Slot6,Slot7,Slot8,Slot9,Slot10,Slot11 FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				if(!$Tete)
					$Tete=$data['Slot1'];
				if(!$Visage)
					$Visage=$data['Slot2'];
				if(!$Dos)
					$Dos=$data['Slot3'];
				if(!$Torse)
					$Torse=$data['Slot4'];
				if(!$Ceinture)
					$Ceinture=$data['Slot5'];
				if(!$Poignet)
					$Poignet=$data['Slot6'];
				if(!$Mains)
					$Mains=$data['Slot7'];
				if(!$Arme)
					$Arme=$data['Slot8'];
				if(!$Pieds)
					$Pieds=$data['Slot9'];
				if(!$Poche)
					$Poche=$data['Slot10'];
				if(!$Sac)
					$Sac=$data['Slot11'];
			}
			mysqli_free_result($result);
			unset($result);
		}
		//Set Slot
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote SET Slot1='$Tete',Slot2='$Visage',Slot3='$Dos',Slot4='$Torse',Slot5='$Ceinture',Slot6='$Poignet',Slot7='$Mains',Slot8='$Arme',Slot9='$Pieds',Slot10='$Poche',Slot11='$Sac' WHERE ID='$PlayerID'");
		mysqli_close($con);
		$mes="Vous recevez votre nouvel équipement";
		$img=Afficher_Image('images/equip_valider.jpg','images/equip_valider.jpg','Equipement validé!',50);
		if($CT)MoveCredits($PlayerID,13,-$CT);
	}
	else
	{
		$mes="<h6>Vous ne disposez pas de suffisamment de crédits!</h6>";
		$img=Afficher_Image('images/transfer_no'.$country.'.jpg','images/equip_valider.jpg','Equipement validé!');
	}
	$skills.="<a class='btn btn-default' href='index.php?view=inventaire'>Retour à l'inventaire</a>";
	$titre="Equipement";
}
include_once('./index.php');
?>