<?php
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Cible = Insec($_POST['Cible']);
	$Regiment = Insec($_POST['Reg']);
	$CT = Insec($_POST['CT']);
	if(!$CT)$CT=2;
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	if($Cible and $Regiment and $Credits >=$CT)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,ValeurStrat,Garnison FROM Lieu WHERE ID='$Cible'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Nom_Ville=$data['Nom'];
				$Garnison=$data['Garnison'];
				if($data['ValeurStrat'])
					$Max_Garnison=$data['ValeurStrat']*200;
				else
					$Max_Garnison=100;
			}
			mysqli_free_result($result);
		}
		if($Garnison+25 <=$Max_Garnison)
		{
			UpdateData("Lieu","Garnison",25,"ID",$Cible,$Max_Garnison);
			UpdateData("Regiment","Vehicule_Nbr",-25,"ID",$Regiment);		
			UpdateData("Officier","Avancement",25,"ID",$OfficierID);
			UpdateCarac($OfficierID,"Credits",-$CT,"Officier");
			$mes="25 Soldats de votre ".$Regiment."e Compagnie rejoignent la garnison de ".$Nom_Ville." !";
		}
		else
			$mes="La garnison de ".$Nom_Ville." ne peut contenir d'avantage de troupes !";		
		$titre="Garnison";
		$img="<img src='images/garnison.jpg'>";
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}	
?>