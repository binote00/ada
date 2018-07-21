<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_as_des_as.php');
$PlayerID = $_SESSION['PlayerID'];
if($PlayerID > 0)
{
	$country = $_SESSION['country'];
	$Free = GetData("Joueur","ID",$PlayerID,"Free");
	$Admin = GetData("Joueur","ID",$PlayerID,"Admin");	
	if($_SESSION['Distance'] == 0 and ($Free > 0 or $Admin))
	{
		if($country == 1)
			$Fronts = "<option value='0'>La Manche</option><option value='1'>Leningrad</option><option value='2'>Afrique du Nord</option>";
		elseif($country == 2)
			$Fronts = "<option value='0'>La Manche</option><option value='2'>Afrique du Nord</option>";
		elseif($country == 4)
			$Fronts = "<option value='2'>Afrique du Nord</option>";
		elseif($country == 6)
			$Fronts = "<option value='2'>Afrique du Nord</option>";
		elseif($country == 8 or $country == 20)
			$Fronts = "<option value='1'>Leningrad</option>";
		elseif($country == 7 or $country == 9)
			$Fronts = "<option value='3'>Pacifique</option>";
?>
<h2>Simulation de combat</h2>
<div class='row'><div class='col-md-6'><img src="../images/wall_map.jpg" style="width:100%;"></div><div class='col-md-6'>
<form action="../index.php?view=as_des_as" method="post">
<select name="Front" class='form-control' style="width: 300px"><?echo $Fronts;?></select>
<br><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
</div></div>
<?
	}
	else
		echo "Vous devez poss�der des points AS des AS pour participer";
}
?>