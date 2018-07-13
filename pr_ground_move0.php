<?php
require_once('./jfv_inc_sessions.php');
if($_SESSION['AccountID'])
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$OfficierID=$_SESSION['Officier'];
	$OfficierEMID=$_SESSION['Officier_em'];
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if(($OfficierID >0 or $OfficierEMID) and $Premium >0)
	{	
		if(!$Lieu)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT ID,Nom FROM Lieu WHERE Zone<>6 ORDER BY Nom ASC");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Lieux.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
				}
				mysqli_free_result($result);
			}
			$titre="<h1>Simulation de déplacement</h1>";
			$mes="<form action='index.php?view=pr_ground_move' method='post'><table class='table'><thead><tr><th>Lieu de départ</th></tr></thead>
				<tr><td><select name='Lieu' class='form-control' style='width: 200px'>".$Lieux."</select></td></tr>
			</table><input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		$img=Afficher_Image('images/move_front'.$country.'.jpg',"images/image.png","");
		include_once('./default.php');
	}
	else
		echo "Tsss";
}
?>