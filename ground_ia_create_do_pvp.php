<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$Vehicule=Insec($_POST['Ve']);
	$Retraite=Insec($_POST['Nid']);
	echo "<h1>Création d'unité EM</h1>";
	if($Vehicule >0 and $Retraite >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Pays,Type,mobile,Flak,Portee,HP FROM Cible WHERE ID='$Vehicule'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$country=$data['Pays'];
				$Type=$data['Type'];
				$mobile=$data['mobile'];
				$Flak=$data['Flak'];
				$Portee=$data['Portee'];
				if($mobile ==5)
				{
					$HP=$data['HP'];
					$Placement=8;
					$Experience=250;
					$Veh_Nbr=1;
				}
				else
				{
					$Placement=0;
					$Experience=50;
					$Veh_Nbr=GetMaxVeh($Type,$mobile,$Flak,500000);
				}
			}
			mysqli_free_result($result);
		}
		$Front=GetFrontByCoord($Retraite);
		$query2="INSERT INTO Regiment_PVP (Pays,Front,Vehicule_ID,Lieu_ID,Vehicule_Nbr,Placement,HP,Camouflage,Experience,Moral,Distance,Move)";
		$query2.="VALUES ('$country','$Front','$Vehicule','$Retraite','$Veh_Nbr','$Placement','$HP',1,250,100,'$Portee',1)";
		$con=dbconnecti();
		$ok2=mysqli_query($con,$query2);
		mysqli_close($con);
		if($ok2)
			echo "La Compagnie Action a été activée avec succès !<br>".$Veh_Nbr." ".GetVehiculeIcon($Vehicule,$country,0,0,$Front);
		else
			echo "Erreur lors de l'activation de la Compagnie Action !";
		echo "<br><a href='index.php?view=ground_ia_pvp' class='btn btn-default' title='Retour'>Retour au menu</a>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page</h1>";
?>