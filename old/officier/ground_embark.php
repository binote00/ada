<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Reg=Insec($_POST['Unite']);
	$Base=Insec($_POST['Base']);
	if($Reg >0 and $Base >0 and !GetData("Regiment","ID",$Reg,"Fret"))
	{
		$Credits_Veh=Insec($_POST['CT']);
		$country=$_SESSION['country'];
		$Regs="";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT ID FROM Regiment_IA WHERE Lieu_ID='$Base' AND Pays='$country' AND Position=32 AND Placement IN(4,11)");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Regs.="<option value='".$data['ID']."'>".$data['ID']."e Bataillon";
			}
			mysqli_free_result($result);
		}
		echo "<h1>Embarquement</h1>";
		if($Regs)
		{
			echo "<img src='images/embarquement.jpg' style='width:50%;'><br>A bord des navires de la ".$Reg."e flottille<br>
			<form action='index.php?view=ground_ravit1' method='post'>
			<input type='hidden' name='Unite' value='".$Reg."'>
			<input type='hidden' name='Base' value='".$Base."'>
			<input type='hidden' name='Action' value='5432'>
			<input type='hidden' name='Credits_Veh' value='".$Credits_Veh."'>
			<select name='Cie' class='form-control' style='width: 250px'>".$Regs."</select>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		else
			echo "Il n'y a aucune unité prête à être embarquée à cet endroit!<br><a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
	}
	else
		echo "Vos navires emportent déjà une cargaison!<br><a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
}
?>