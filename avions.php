<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./menu_infos.php');
?>
<h2>Les avions</h2>
<form action="index.php?view=avionss" method="post">
	<table class='table'>
		<thead><tr><th>Pays</th><th>Type</th><th>Niveau</th></thead>
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
				<select name="level" class='form-control' style="width: 200px">
					<option value="all">Tous</option>
					<?
					for($i=0;$i<=13;$i++)
					{
						echo "<option value='".$i."'>".$i."</option>";
					}
					?>
				</select>
			</td>			
		</tr>
	</table>
<input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();' align='middle'></form>
<h2><img src="images/avions.jpg"></h2>