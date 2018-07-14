<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	$titre="Sections du Poste de Commandement";
	$mes="<table class='table'><thead><tr><th>Section</th><th>Infos</th><th>Choix</th></tr></thead>
	<tr><td><img src='images/skills/skillpc1.png'></td><td><b>Section Médicale</b><br>Les unités du bataillon situées sur le même lieu ignorent l'attrition
	<br>Uniquement pour l'infanterie au grade le plus bas, tous les types d'unités au grade le plus élevé.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='1'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc2.png'></td><td><b>Section Transmissions</b><br>Les unités du bataillon situées sur le même lieu peuvent demander un appui aérien
	<br>Bonus tactique et protection aérienne aux grades plus élevés.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='2'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc3.png'></td><td><b>Section Logistique</b><br>Les unités du bataillon situées sur le même lieu économisent des munitions.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='3'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc4.png'></td><td><b>Section DCA</b><br>Les unités de DCA du bataillon situées sur le même lieu bénéficient d'un bonus de détection, de précision et de portée augmentant avec le grade.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='4'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc5.png'></td><td><b>Section Police</b><br>Une unité de police militaire dont l'expérience augmente avec le grade protège les infrastructures du lieu.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='5'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc6.png'></td><td><b>Section Topographique</b><br>Le bataillon reconnaît automatiquement le lieu où il se trouve (une fois par jour lors de la 1e connexion).
	<br>Bonus tactique et protection contre la détection aux grades plus élevés.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='6'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc7.png'></td><td><b>Section d'état-major</b><br>Le coût en CT des actions de bataillon est réduit. La réduction augmente avec le grade, minimum 1CT.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='7'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	</table>";
	include_once('./default.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>