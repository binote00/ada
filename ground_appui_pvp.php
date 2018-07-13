<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Reg=Insec($_POST['Reg']);
	$Mode=Insec($_POST['Mode']);
	if($Mode >0)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Mission_Type_D=".$Mode." WHERE ID='$Reg'");
		mysqli_close($con);
		$mes="<br>Vous appelez vos alliés à l'aide!";
		$img="radio_terre";
	}
	$titre="Transmissions";
	$img=Afficher_Image('images/'.$img.'.jpg',"images/image.png","");
	$menu="<a href='index.php?view=ground_menu_pvp' class='btn btn-default' title='Retour'>Retour au menu</a>";
	include_once('./default.php');
}
?>