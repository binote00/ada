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
	$Faction=Insec($_POST['Camp']);
	$Battle=Insec($_POST['Battle']);
	$Position=Insec($_POST['Pos']);
	$Placement=Insec($_POST['Zone']);
	$Distance=Insec($_POST['Range']);
	$Cible=GetCiblePVP($Battle);
	if($Distance >0)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Position=4,Move=1,Camouflage=1,Visible=1,Distance='$Distance',Moves=Moves+1 WHERE ID='$Reg'");
		mysqli_close($con);
		$mes='<br>Vos troupes se déplacent';
		$img="prepare";
	}
	if($Position ==1)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Position=1,Move=1,Camouflage=2,Visible=0 WHERE ID='$Reg'");
		mysqli_close($con);
		$mes='<br>Vos troupes se placent en position défensive';
		$img="defense".$country;
	}
	elseif($Position ==2)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Position=2,Visible=0,Move=0,Camouflage=4 WHERE ID='$Reg'");
		mysqli_close($con);
		$mes='<br>Vos troupes se retranchent sur leurs positions';
		$img="digin";
	}
	elseif($Position ==3)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Position=3,Visible=0,Move=0,Camouflage=4 WHERE ID='$Reg'");
		mysqli_close($con);
		$mes='<br>Vos troupes se placent en embuscade';
		$img="ambush".$country;
	}
	elseif($Position ==5)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Position=5,Visible=0,Move=0,Camouflage=1 WHERE ID='$Reg'");
		mysqli_close($con);
		$mes='<br>Vos troupes se placent en appui';
		$img="appui".$country;
	}
	elseif($Position ==10)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Position=10,Visible=0,Move=0,Camouflage=4 WHERE ID='$Reg'");
		mysqli_close($con);
		$mes='<br>Vos troupes se retranchent sur leurs positions et établissent une ligne de défense';
		$img="digin";
	}
	elseif($Position ==99)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Position=0,Move=1,Camouflage=1,Visible=1 WHERE ID='$Reg'");
		mysqli_close($con);
		$mes='<br>Vos troupes se préparent à faire mouvement';
		$img="prepare";
	}
	if($Placement >0)
	{
		if($Placement ==10)$Placement=0;
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Regiment_PVP SET Position=0,Visible=1,Move=1,Camouflage=1,Placement=".$Placement.",Moves=Moves+1,Bomb_IA=0 WHERE ID='$Reg'");
		mysqli_close($con);
		$mes='<br>Vos troupes se positionnent '.GetPlace($Placement);
		$img="defense";
	}
	$titre="Mouvement";
	$img=Afficher_Image('images/'.$img.'.jpg',"images/image.png","");
	$menu="<a href='index.php?view=ground_menu_pvp' class='btn btn-default' title='Retour'>Retour au menu</a>";
	include_once('./default.php');
}
?>