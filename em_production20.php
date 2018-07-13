<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Officier_Rens or $OfficierEMID ==$Officier_Log or $GHQ or $Admin)
	{
		?><h2>Production de véhicules</h2>
			<form action='index.php?view=em_production2' method='post'><table class='table'><thead><tr><th>Type</th></tr></thead>
				<tr><td><select name='type' class='form-control' style='width: 200px'><?echo DoUniqueSelect("Veh_Type","ID","Type",1000,"Type");?></select></td></tr>
			</table><input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form><?
	}
	else
		PrintNoAccess($country,1,4,6);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>