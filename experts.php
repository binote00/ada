<?
include_once('./menu_classement.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID = $_SESSION['PlayerID'];
$i = 0;
$query="SELECT ID,PlayerID,AvionID,Pilotage,SUM(Pilotage) FROM (SELECT * FROM XP_Avions ORDER BY Pilotage DESC) AS Tmp GROUP BY Tmp.PlayerID ORDER BY Tmp.Pilotage DESC LIMIT 100";
$query2="SELECT ID,PlayerID,AvionID,Pilotage,SUM(Pilotage) FROM (SELECT * FROM XP_Avions ORDER BY Pilotage DESC) AS Tmp GROUP BY Tmp.PlayerID ORDER BY SUM(Pilotage) DESC LIMIT 100";
$query3="SELECT ID,PlayerID,AvionID,MAX(Pilotage),SUM(Pilotage) FROM XP_Avions GROUP BY AvionID ORDER BY SUM(Pilotage) DESC LIMIT 100";
$con=dbconnecti();
$result=mysqli_query($con,$query);
$result2=mysqli_query($con,$query2);
$result3=mysqli_query($con,$query3);
mysqli_close($con);
if($result)
{
	while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		$i++;
		if($PlayerID >0 and $data['PlayerID'] == $PlayerID)
			$tab1.="<tr bgcolor='LightYellow'>";
		else
			$tab1.="<tr>";
		$tab1.="<td bgcolor='tan'>".$i."</td>
			<td><a href='user_public.php?Pilote=".$data['PlayerID']."' target='_blank'>".GetData('Pilote','ID',$data['PlayerID'],'Nom')."</a></td>
			<td><img src='".GetData('Pilote','ID',$data['PlayerID'],'Pays')."20.gif'></td>
			<td><img src='images/avions/avion".$data['AvionID'].".gif' title='".GetData('Avion','ID',$data['AvionID'],'Nom')."'></td>
			<td>".round($data['Pilotage'],1)."</td>
			<td>".round($data['SUM(Pilotage)'],1)."</td>
		</tr>";
	}
	mysqli_free_result($result);
	unset($data);
}
else
	echo "<b>Désolé, aucun pilote n'a encore émergé dans cette campagne</b>";
$i=0;
if($result2)
{
	while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
	{
		$i++;
		if($PlayerID >0 and $data2['PlayerID'] == $PlayerID)
			$tab2.="<tr bgcolor='LightYellow'>";
		else
			$tab2.="<tr>";
		$tab2.="<td bgcolor='tan'>".$i."</td>
			<td><a href='user_public.php?Pilote=".$data2['PlayerID']."' target='_blank'>".GetData('Pilote','ID',$data2['PlayerID'],'Nom')."</a></td>
			<td><img src='".GetData('Pilote','ID',$data2['PlayerID'],'Pays')."20.gif'></td>
			<td><img src='images/avions/avion".$data2['AvionID'].".gif' title='".GetData('Avion','ID',$data2['AvionID'],'Nom')."'></td>
			<td>".round($data2['SUM(Pilotage)'],1)."</td>
		</tr>";
	}
	mysqli_free_result($result2);
	unset($data2);
}
else
	echo "<b>Désolé, aucun pilote n'a encore émergé dans cette campagne</b>";
$i=0;
if($result3)
{
	while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
	{
		$i++;
		$tab3.="<tr><td bgcolor='tan'>".$i."</td>
			<td><img src='images/avions/avion".$data['AvionID'].".gif' title='".GetData('Avion','ID',$data['AvionID'],'Nom')."'></td>
			<td><img src='".GetData('Avion','ID',$data['AvionID'],'Pays')."20.gif'></td>
			<td>".round($data['SUM(Pilotage)'],1)."</td>
			<td>".round($data['MAX(Pilotage)'],1)."</td>
		</tr>";
	}
	mysqli_free_result($result3);
	unset($data);
}
else
	echo "<b>Désolé, aucun pilote n'a encore émergé dans cette campagne</b>";
?>
<div id="col_gauche">
<table class='table'>
	<tr><th colspan="20" class="TitreBleu_bc">Tableau des heures de vol par modèle d'avion et par pilote</th></tr>
	<tr bgcolor="#CDBDA7">
		<th>N°</th>
		<th>Pilote</th>
		<th>Pays</th>
		<th>Avion</th>
		<th>Heures</th>
		<th>Total</th>
	</tr>
<?
echo $tab1."</table></div>";
?>
<div id="col_gauche">
<table class='table'>
	<tr><th colspan="20" class="TitreBleu_bc">Tableau des heures de vol total par pilote</th></tr>
	<tr bgcolor="#CDBDA7">
		<th>N°</th>
		<th>Pilote</th>
		<th>Pays</th>
		<th>Avion le plus utilisé</th>
		<th>Total</th>
	</tr>
<?
echo $tab2."</table></div>";
?>
<div id="col_droite">
<table class='table'>
	<tr><th colspan="20" class="TitreBleu_bc">Tableau des heures de vol par modèle d'avion</th></tr>
	<tr bgcolor="#CDBDA7">
		<th>N°</th>
		<th>Avion</th>
		<th>Pays</th>
		<th>Total</th>
		<th>Meilleur</th>
		<!--<th>Pilote</th>-->
	</tr>
<?
echo $tab3."</table></div>";
?>