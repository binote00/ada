<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_ground.inc.php');
include_once('./jfv_txt.inc.php');
$OfficierID = $_SESSION['Officier'];
if($OfficierID > 0)
{
	$Cible = Insec($_POST['Cible']);
	$Credits_rally = 2;
	$Credits = GetData("Officier","ID",$OfficierID,"Credits");
	$Trait_o = GetData("Officier","ID",$OfficierID,"Trait");
	if($Trait_o == 6)
		$Credits_rally = 1;
	if($Cible and $Credits >= $Credits_rally)
	{
		$bonus = 0;
		$Division = GetData("Officier","ID",$OfficierID,"Division");
		$Division_Cdt = GetData("Division","ID",$Division,"Cdt");
		if($Division_Cdt == $OfficierID)
		{
			$con = dbconnecti();
			$result = mysqli_query($con, "SELECT r.ID FROM Officier as o, Regiment as r WHERE r.Officier_ID = o.ID AND o.Division = '$Division' AND r.Lieu_ID = '$Cible' AND r.Moral < 206");
			mysqli_close($con);
			if($result)
			{
				while($data = mysqli_fetch_array($result, MYSQLI_NUM))
				{
					UpdateData("Regiment","Moral",50,"ID",$data[0]);
					$bonus +=1;
				}
				mysqli_free_result($result);
			}
			$mes = "Vous ralliez ".$bonus." Compagnies de votre division !";			
			$bonus *= 10;
			UpdateData("Officier","Avancement",$bonus,"ID",$OfficierID);
			UpdateData("Officier","Reputation",$bonus,"ID",$OfficierID);
			UpdateCarac($OfficierID, "Credits", -$Credits_rally, "Officier");			
			$titre = "Ralliement";
			$img = "<img src='images/rally.jpg'>";
			$menu = "<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
			include_once('./default.php');
		}
		else
			echo "Tsss";
	}
	else
		echo "Tsss";
}	
?>