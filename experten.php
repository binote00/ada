<?
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_classement.php');
$avion = Insec($_POST['avion']);
$PlayerID = $_SESSION['PlayerID'];
if($avion > 0)
{
	$i = 0;
	if($avion == 999999)
		$query = "SELECT COUNT(*) as c,Joueur_win,Avion_win FROM Chasse WHERE PVP<>1 GROUP BY Avion_win,Joueur_win ORDER BY c DESC LIMIT 100";
	else
		$query = "SELECT COUNT(*) as c,Joueur_win,Avion_win FROM Chasse WHERE PVP<>1 AND Avion_win = '$avion' GROUP BY Joueur_win ORDER BY c DESC LIMIT 100";
	$con=dbconnecti();
	$result=mysqli_query($con, $query);
	mysqli_close($con);
	if($result)
	{
		echo "<div id='col_gauche'>
		<table border='0' cellspacing='1' cellpadding='4' bgcolor='#ECDDC1'>
			<tr><th colspan='20' class='TitreBleu_bc'>Tableau des victoires par modèle d'avion et par pilote</th></tr>
			<tr bgcolor='#CDBDA7'>
				<th>N°</th>
				<th>Pilote</th>
				<th>Pays</th>
				<th>Avion</th>
				<th>Victoires</th>
			</tr>";
		while($data=mysqli_fetch_array($result))
		{
			$i++;
			if($PlayerID > 0 and $data['Joueur_win'] == $PlayerID)
				echo "<tr bgcolor='LightYellow'>";
			else
				echo "<tr>";
			$Pays_Ori=GetData("Pilote","ID",$data['Joueur_win'],"Pays");
		?>
					<td bgcolor="tan"><? echo $i;?></td>
					<td><a href="user_public.php?Pilote=<?echo $data['Joueur_win'];?>" target="_blank"><? echo GetData("Pilote","ID",$data['Joueur_win'],"Nom");?></a></td>
					<td><img src='<? echo $Pays_Ori;?>20.gif'></td>
					<td><? echo GetAvionIcon($data['Avion_win'],$Pays_Ori,0,0);?></td>
					<td><? echo $data[0];?></td>
				</tr>
		<?
		}
		mysqli_free_result($result);
		unset($result);
		unset($data);
	}
	else
		echo "<b>Désolé, aucun pilote n'a encore émergé dans cette campagne</b>";
	$con=dbconnecti();
	$result2=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Avion WHERE Etat=1 ORDER BY Nom ASC");
	mysqli_close($con);
	if($result2)
	{
		while($data = mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
		{
			$avions.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
		}
		mysqli_free_result($result2);
	}
	echo "</table></div><div id='col_droite'><form action='index.php?view=experten' method='post'><table>
	<tr><td colspan='2'>".Afficher_Image('images/aces.jpg', 'images/image.png', '')."</td></tr>
	<tr><th>Choix du modèle d'avion </th>
		<td align='left'>
			<select name='avion' style='width: 200px'>
			<option value='999999'>Tous</option>
			".$avions."
			</select>
		</td>
	</tr></table><input type='Submit' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></form></div>";
}
else
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Avion WHERE Etat=1 ORDER BY Nom ASC");
	mysqli_close($con);
	if($result)
	{
		while($data = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$avions.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
		}
		mysqli_free_result($result);
	}
	echo "<div id='col_droite'><form action='index.php?view=experten' method='post'><table>
	<tr><td colspan='2'>".Afficher_Image('images/aces.jpg', 'images/image.png', '')."</td></tr>
	<tr><th>Choix du modèle d'avion </th>
		<td align='left'>
			<select name='avion' style='width: 200px'>
			<option value='999999'>Tous</option>
			".$avions."
			</select>
		</td>
	</tr></table><input type='Submit' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></form></div>";
}
?>