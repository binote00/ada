<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	$titre="Sections du Poste de Commandement";
	$mes="<table class='table'><thead><tr><th>Section</th><th>Infos</th><th>Choix</th></tr></thead>
	<tr><td><img src='images/skills/skillpc1.png'></td><td><b>Section M�dicale</b><br>Les unit�s du bataillon situ�es sur le m�me lieu ignorent l'attrition
	<br>Uniquement pour l'infanterie au grade le plus bas, tous les types d'unit�s au grade le plus �lev�.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='1'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc2.png'></td><td><b>Section Transmissions</b><br>Les unit�s du bataillon situ�es sur le m�me lieu peuvent demander un appui a�rien
	<br>Bonus tactique et protection a�rienne aux grades plus �lev�s.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='2'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc3.png'></td><td><b>Section Logistique</b><br>Les unit�s du bataillon situ�es sur le m�me lieu �conomisent des munitions.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='3'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc4.png'></td><td><b>Section DCA</b><br>Les unit�s de DCA du bataillon situ�es sur le m�me lieu b�n�ficient d'un bonus de d�tection, de pr�cision et de port�e augmentant avec le grade.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='4'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc5.png'></td><td><b>Section Police</b><br>Une unit� de police militaire dont l'exp�rience augmente avec le grade prot�ge les infrastructures du lieu.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='5'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc6.png'></td><td><b>Section Topographique</b><br>Le bataillon reconna�t automatiquement le lieu o� il se trouve (une fois par jour lors de la 1e connexion).
	<br>Bonus tactique et protection contre la d�tection aux grades plus �lev�s.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='6'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	<tr><td><img src='images/skills/skillpc7.png'></td><td><b>Section d'�tat-major</b><br>Le co�t en CT des actions de bataillon est r�duit. La r�duction augmente avec le grade, minimum 1CT.</td>
	<td><form action='index.php?view=ground_sec1' method='post'><input type='hidden' name='Sec' value='7'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
	</table>";
	include_once('./default.php');
}
else
	echo "<h1>Vous devez �tre connect� pour acc�der � cette page!</h1>";
?>