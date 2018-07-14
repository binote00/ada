<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./menu_infos.php');
?>
<h2>Les unités</h2>
<?if($_SESSION['AccountID']){?>
<form action="index.php?view=unitess" method="post">
	<table class='table'>
	<thead><tr><th>Pays</th><th>Type d'unité</th></thead>
	<tr><td>
			<select name="land" class='form-control' style="width: 200px">
				<option value="all">Tous</option>
					<? DoUniqueSelect("Pays","Pays_ID","Nom",20,"Nom");?>
			</select>
		<td>
			<select name="categorie" class='form-control' style="width: 200px">
				<option value="all">Tous</option>
					<? DoUniqueSelect("Avion_Type","ID","Type",12,"Type");?>
					<option value="div">Divisions</option>
			</select>
	</td></tr>
	</table><input type="submit" value="Valider" class='btn btn-default' onclick='this.disabled=true;this.form.submit();'>
</form><?}?>
	<table class='table table-striped'>
	<thead><tr><th>Type</th><th>Avions</th><th>Missions Défensives <a href='help/aide_missions.php' target='_blank' title='Aide à propos du choix des missions'><img src='/images/help.png'></a></th><th>Missions Offensives <a href='help/aide_missions.php' target='_blank' title='Aide à propos du choix des missions'><img src='images/help.png'></a></th></tr></thead>
	<tr><td>Attaque</td><td><img src='images/avions/avion14.gif'><br><img src='images/avions/avion54.gif'><br><img src='images/avions/avion110.gif'><br><img src='images/avions/avion276.gif'></td><td>Aucune</td><td align="left">Appui Rapproché<br>Attaque au sol<br>Attaque de navire<br>Bombardement Naval<br>Bombardement Stratégique<br>Bombardement Tactique<br>Reconnaissance Tactique<br>Torpillage</td></tr>
	<tr><td>Bombardement</td><td><img src='images/avions/avion15.gif'><br><img src='images/avions/avion62.gif'><br><img src='images/avions/avion22.gif'><br><img src='images/avions/avion65.gif'><br><img src='images/avions/avion210.gif'></td><td>Aucune</td><td align="left">Bombardement Naval<br>Bombardement Tactique<br>Bombardement Stratégique<br>Bombardement de Nuit<br>Reconnaissance Tactique<br>Torpillage</td></tr>
	<tr><td>Bombardement lourd</td><td><img src='images/avions/avion285.gif'><br><img src='images/avions/avion484.gif'><br><img src='images/avions/avion279.gif'></td><td>Aucune</td><td align="left">Bombardement Stratégique<br>Bombardement de Nuit</td></tr>
	<tr><td>Chasse</td><td><img src='images/avions/avion3.gif'><br><img src='images/avions/avion57.gif'><br><img src='images/avions/avion10.gif'><br><img src='images/avions/avion39.gif'><br><img src='images/avions/avion215.gif'><br><img src='images/avions/avion304.gif'><br><img src='images/avions/avion311.gif'></td><td>Patrouille</td><td align="left">Appui Rapproché<br>Attaque au sol<br>Attaque de navire<br>Chasse libre<br>Escorte<br>Reconnaissance Tactique<br>Supériorité aérienne</td></tr>
	<tr><td>Chasse lourde</td><td><img src='images/avions/avion27.gif'><br><img src='images/avions/avion109.gif'><br><img src='images/avions/avion43.gif'><br><img src='images/avions/avion147.gif'><br><img src='images/avions/avion218.gif'></td><td>Chasse de nuit<br>Patrouille</td><td align="left">Appui Rapproché<br>Attaque au sol<br>Attaque de navire<br>Bombardement Naval<br>Bombardement Tactique<br>Escorte<br>Harcèlement<br>Reconnaissance Tactique</td></tr>
	<tr><td>Chasse embarquée</td><td><img src='images/avions/avion134.gif'><br><img src='images/avions/avion225.gif'><br><img src='images/avions/avion309.gif'><br><img src='images/avions/avion466.gif'></td><td>Patrouille</td><td align="left">Appui Rapproché<br>Attaque au sol<br>Attaque de navire<br>Bombardement Naval<br>Bombardement Tactique<br>Escorte<br>Reconnaissance Tactique</td></tr>
	<tr><td>Embarqué</td><td><img src='images/avions/avion106.gif'><br><img src='images/avions/avion331.gif'><br><img src='images/avions/avion353.gif'></td><td>Aucune</td><td align="left">Appui Rapproché<br>Attaque au sol<br>Attaque de navire<br>Bombardement Naval<br>Bombardement Stratégique<br>Bombardement Tactique<br>Reconnaissance Tactique<br>Torpillage</td></tr>
	<tr><td>Patrouille maritime</td><td><img src='images/avions/avion100.gif'><br><img src='images/avions/avion103.gif'><br><img src='images/avions/avion155.gif'><br><img src='images/avions/avion114.gif'><br><img src='images/avions/avion349.gif'></td><td>Mouillage de mines</td><td align="left">Bombardement Naval<br>Patrouille ASM<br>Reconnaissance Maritime (tactique)<br>Sauvetage en mer<br>Torpillage</td></tr>
	<tr><td>Reconnaissance</td><td><img src='images/avions/avion90.gif'><br><img src='images/avions/avion48.gif'><br><img src='images/avions/avion21.gif'><br><img src='images/avions/avion67.gif'></td><td>Aucune</td><td align="left">Bombardement Tactique<br>Commando<br>Marquage de Cibles<br>Reconnaissance Stratégique<br>Reconnaissance Tactique<br>Sauvetage</td></tr>
	<tr><td>Transport</td><td><img src='images/avions/avion44.gif'><br><img src='images/avions/avion428.gif'><br><img src='images/avions/avion52.gif'><br><img src='images/avions/avion115.gif'></td><td>Aucune</td><td align="left">Commando<br>Parachutage<br>Ravitaillement<br>Reconnaissance Tactique</td></tr>
	</table>
