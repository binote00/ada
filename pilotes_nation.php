<?
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
//include_once('./menu_classement.php');
$Tri=Insec($_POST['Tri']);
if(!$Tri)$Tri=9;
?>
<h1>Caractéristiques des Pilotes de votre Nation</h1>
<div style='overflow:auto; width: 100%;'>
<table class='table table-striped'>
	<thead><tr>
		<th>N°</th>
		<th>Nom</th>
		<th>Pays</th>
		<th>Unité</th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="10"><input type='Submit' value='Grade'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="1"><input type='Submit' value='Acrobatie'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="2"><input type='Submit' value='Adresse au Tir'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="3"><input type='Submit' value='Bombarder'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="4"><input type='Submit' value='Détection'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="5"><input type='Submit' value='Gestion'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="6"><input type='Submit' value='Navigation'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="7"><input type='Submit' value='Pilotage'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="8"><input type='Submit' value='Tactique'></form></th>
		<th><form action='index.php?view=pilotes_nation' method='post'><input type='hidden' name='Tri' value="9"><input type='Submit' value='Reputation'></form></th>
	</tr></thead>
<?
switch($Tri)
{
	case 1:
		$Tri="Acrobatie";
	break;
	case 2:
		$Tri="Tir";
	break;
	case 3:
		$Tri="Bombardement";
	break;
	case 4:
		$Tri="Vue";
	break;
	case 5:
		$Tri="Gestion";
	break;
	case 6:
		$Tri="Navigation";
	break;
	case 7:
		$Tri="Pilotage";
	break;
	case 8:
		$Tri="Tactique";
	break;
	case 9:
		$Tri="Reputation";
	break;
	case 10:
		$Tri="Avancement";
	break;
}
$PlayerID=$_SESSION['PlayerID'];
$country=$_SESSION['country'];
$con=dbconnecti();
$result=mysqli_query($con,"SELECT ID,Nom,Pays,Unit,Pilotage,Navigation,Tir,Vue,Acrobatie,Bombardement,Tactique,Gestion,Avancement,Reputation 
FROM Pilote WHERE Pays='$country' AND Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() AND Actif=0 AND $Tri > 10 ORDER BY $Tri DESC LIMIT 100");
mysqli_close($con);
if($result)
{
	$i=1;
	while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		$Grade=GetAvancement($data['Avancement'],$data['Pays']);
		if($PlayerID ==1 or $PlayerID ==2)
		{
			echo "<tr><td>".$i."</td><td align='left'><a href='user_public.php?Pilote=".$data['ID']."' target='_blank' class='lien'>".$data['Nom']."</a></td>
				<td><img src='".$data['Pays']."20.gif'></td>
				<td>".Afficher_Icone($data['Unit'],$data['Pays'],GetData('Unit','ID',$data['Unit'],'Nom'))."</td>
				<td><img title='".$Grade[0]."(".$data['Avancement'].")' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td>
				<td>".round($data['Acrobatie'],2)."</td>
				<td>".round($data['Tir'],2)."</td>
				<td>".round($data['Bombardement'],2)."</td>
				<td>".round($data['Vue'],2)."</td>
				<td>".round($data['Gestion'],2)."</td>
				<td>".round($data['Navigation'],2)."</td>
				<td>".round($data['Pilotage'],2)."</td>
				<td>".round($data['Tactique'],2)."</td>
				<td>".$data['Reputation']."</td></tr>";
		}
		else
		{
			echo "<tr><td>".$i."</td><td align='left'><a href='user_public.php?Pilote=".$data['ID']."' target='_blank' class='lien'>".$data['Nom']."</a></td>
				<td><img src='".$data['Pays']."20.gif'></td>
				<td>".Afficher_Icone($data['Unit'],$data['Pays'],GetData('Unit','ID',$data['Unit'],'Nom'))."</td>
				<td><img title='".$Grade[0]."' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td>
				<td>".GetSkillTxt($data['Acrobatie'])."</td>
				<td>".GetSkillTxt($data['Tir'])."</td>
				<td>".GetSkillTxt($data['Bombardement'])."</td>
				<td>".GetSkillTxt($data['Vue'])."</td>
				<td>".GetSkillTxt($data['Gestion'])."</td>
				<td>".GetSkillTxt($data['Navigation'])."</td>
				<td>".GetSkillTxt($data['Pilotage'])."</td>
				<td>".GetSkillTxt($data['Tactique'])."</td>
				<td>".GetReputation($data['Reputation'],$data['Pays'])."</td></tr>";
		}
		$i++;
	}
}
echo "</table></div>";
?>
