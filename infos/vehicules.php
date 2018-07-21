<?php
require_once '../jfv_inc_sessions.php';
include_once '../jfv_include.inc.php';
include_once '../jfv_nomission.inc.php';
include_once __DIR__ . '/../view/menu_infos.php';
?>
<h2>Les véhicules</h2>
<form action="index.php?view=vehiculess" method="post">
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
						<? DoUniqueSelect("Veh_Type","ID","Type",37,"Type");?>
				</select>
			</td>
		</tr>
	</table>
	<input type='submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<p><img src="images/tanks.jpg"></p>