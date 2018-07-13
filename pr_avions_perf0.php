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
<form action="index.php?view=pr_avions_perf" method="post">
	<table class='table'>
		<thead><tr><th>Pays</th><th>Type</th><th>Altitude</th></thead>
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
			<td>
				<select name="alt" class='form-control' style="width: 200px">
					<option value="500">500m</option>
					<option value="1000">1000m</option>
					<option value="2000">2000m</option>
					<option value="3000">3000m</option>
					<option value="4000">4000m</option>
					<option value="5000">5000m</option>
					<option value="6000">6000m</option>
					<option value="7000">7000m</option>
					<option value="8000">8000m</option>
					<option value="9000">9000m</option>
					<option value="10000">10000m</option>
					<option value="11000">11000m</option>
					<option value="12000">12000m</option>
					<option value="13000">13000m</option>
				</select>
			</td>
		</tr>
	</table>
	<input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?}?>