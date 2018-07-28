<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	/*include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];*/
	if(!$Front)$Front=GetData("Officier","ID",$OfficierID,"Front");
	echo "<iframe width='100%' height='800' src='./carte_ground.php?map=".$Front."&mode=1&frame=1'></iframe>";
	echo "<a href='carte_ground.php?map=".$Front."&mode=1' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte (dans un onglet)'></a>";
	/*$mes="<a href='carte_ground.php?map=".$Front."&mode=1' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte'></a>";
	$titre="Carte";
	$img="<img src='images/cartes/carte_ground.jpg'>";
	include_once('./default.php');*/
}
?>