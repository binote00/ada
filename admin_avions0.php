<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin == 1)
{
	include_once('./jfv_nomission.inc.php');
	include_once('./menu_infos.php');
?>
<h2>Admin avions</h2>
<form action="index.php?view=output_avions" method="post">
	<table class='table'>
		<thead><tr><th>Pays</th><th>Type</th></thead>
			<td>
				<select name="land" class='form-control' style="width: 200px">
					<option value="all">Tous</option>
						<? DoUniqueSelect("Pays","Pays_ID","Nom",20,"Nom");?>
				</select>
			</td>
			<td>
				<select name="type" class='form-control' style="width: 200px">
					<option value="all">Tous</option>
						<? DoUniqueSelect("Avion_Type","ID","Type",12,"Type");?>
				</select>
			</td>
		</tr>
	</table>
	<input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?}?>