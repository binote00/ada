<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$OfficierEMID=$_SESSION['Officier_em'];
$Officier=$_SESSION['Officier'];
$Pilote_pvp=$_SESSION['Pilote_pvp'];
$Officier_pvp=$_SESSION['Officier_pvp'];
if($PlayerID >0 or $OfficierEMID >0 or $Officier >0 or $Pilote_pvp >0 or $Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_infos.php');
	if(GetData("Joueur","ID",$_SESSION['AccountID'],"Admin") ==1)
	{
		$Tri=Insec($_POST['Tri']);
		if(!$Tri)$Tri=3;
		?>
		<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr><th>Nom</th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="1"><input type='Submit' value='Pays'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="2"><input type='Submit' value='Calibre'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="3"><input type='Submit' value='Degats'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="8"><input type='Submit' value='Pene'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="4"><input type='Submit' value='Multi'></form></th>
		<th>Max</th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="10"><input type='Submit' value='Pratique'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="9"><input type='Submit' value='Portée'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="5"><input type='Submit' value='Enrayage'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="11"><input type='Submit' value='Muns'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="6"><input type='Submit' value='Flak'></form></th>
		<th><form action='index.php?view=aguns' method='post'><input type='hidden' name='Tri' value="7"><input type='Submit' value='Date'></form></th>
		</tr></thead><?
		switch($Tri)
		{
			case 1:
				$Tri="Pays";
			break;
			case 2:
				$Tri="Calibre";
			break;
			case 3:
				$Tri="Degats";
			break;
			case 4:
				$Tri="Multi";
			break;
			case 5:
				$Tri="Enrayage";
			break;
			case 6:
				$Tri="Flak";
			break;
			case 7:
				$Tri="Date";
			break;
			case 8:
				$Tri="Perf";
			break;
			case 9:
				$Tri="Portee";
			break;
			case 10:
				$Tri="Portee_max";
			break;
			case 11:
				$Tri="Munitions";
			break;
		}
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT * FROM Armes ORDER BY $Tri DESC, Nom ASC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{	
				$Max=$data['Degats']*$data['Multi'];
				echo "<tr><td align='left'>".$data['Nom']." (".$data['ID'].")</td><td><img src='images/".$data['Pays']."20.gif'></td><td>".$data['Calibre']."mm</td><td align='right'>".$data['Degats']."</td><td>".$data['Perf']."mm</td><td>x".$data['Multi']."</td>
				<th>".$Max."</th><td>".$data['Portee']."</td><td>".$data['Portee_max']."</td><td>".$data['Enrayage']."</td><td>".$data['Munitions']."</td><td>".$data['Flak']."</td><td>".$data['Date']."</td></tr>";
			}
		}
		?>
		<tr bgcolor="tan">
			<th>Nom</th>
			<th>Pays</th>
			<th>Calibre</th>
			<th>Degats</th>
			<th>Péné AT</th>
			<th>Multi</th>
			<th>Max</th>
			<th>Pratique</th>
			<th>Portee</th>
			<th>Enrayage</th>
			<th>Munitions</th>
			<th>Flak</th>
			<th>Date</th>
		</tr></table></div>
		<?
	}
	else
	{
		?>
		<body bgcolor="#ECDDC1">
		<div align="center" bgcolor="#ECDDC1">
			<table class='table'>
				<tr><td><img src='images/top_secret.gif'></td></tr>
				<tr><td>Ces données sont classifiées.</td></tr>
				<tr><td>Votre rang ne vous permet pas d'accéder à ces informations.</td></tr>
			</table>
		</div>
		</body>
	<?}
}?>