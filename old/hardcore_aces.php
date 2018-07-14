<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_classement.php');
?>
<div>
<table border="0" cellspacing="1" cellpadding="5" bgcolor="#ECDDC1">
	<tr><th colspan="9" class="TitreBleu_bc">Tableau des As des As</th></tr>
	<tr bgcolor="#CDBDA7">
		<th>N°</th>
		<th>Pilote</th>
		<th>Unité</th>
		<th>Grade</th>
		<th>Pays</th>
		<th>Victoires</th>
		<th>Score</th>
	</tr>
<?
$PlayerID = $_SESSION['PlayerID'];
if($PlayerID)
{
	$Nom = GetData("Joueur","ID",$PlayerID,"Nom");

	$con = dbconnecti();
	$query="SELECT * FROM ((SELECT ID,Nom,Pays,Unit,Avancement,Victoires,Abattu FROM Joueur WHERE Victoires > 0) UNION (SELECT ID,Nom,Pays,Unit,Avancement,Victoires*100,Abattu FROM Pilote WHERE Victoires > 0)) as t WHERE Victoires > Abattu ORDER BY Victoires - Abattu DESC, Victoires DESC LIMIT 100";
	$result=mysqli_query($con, $query);
	mysqli_close($con);
	if($result)
	{
		$num=mysqli_num_rows($result);

		if($num==0)
		{
			echo "<b>Désolé, aucun as n'a encore émergé dans cette campagne</b>";
		}
		else
		{
			$i=0;
			while ($i < $num) 
			{
				$ID=mysqli_result($result,$i,"ID");
				$Victoires=mysqli_result($result,$i,"Victoires");
				$Abattu=mysqli_result($result,$i,"Abattu");
				$Pilote=mysqli_result($result,$i,"Nom");
				$Unit=mysqli_result($result,$i,"Unit");
				$Avancement=mysqli_result($result,$i,"Avancement");
				$Pays=mysqli_result($result,$i,"Pays");

				$Unite=GetData("Unit","ID",$Unit,"Nom");
				$Grade=GetAvancement($Avancement,$Pays);
				
				$Avion_unit_img = "images/unit".$Unit."p.gif";
				if(is_file($Avion_unit_img))
				{
					$Unite = "<img src='".$Avion_unit_img."' title='".$Unite."'>";
				}
			
				if($Pilote == $Nom)
					echo "<tr bgcolor='LightYellow'>";
				else
					echo "<tr>";
				if(!$Abattu)
				{
					$Victoires /= 100;
				}
				else
				{
					$Pilote = "<b>".$Pilote."</b>";
					$con = dbconnecti();
					$Victoires = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse WHERE Joueur_win='$ID' AND PVP<>1"),0);
					mysqli_close($con);
				}
				$Score = $Victoires - $Abattu;
	?>
					<td><? echo $i+1;?></td>
					<td><? echo $Pilote;?></td>
					<td><? echo $Unite;?></td>
					<td><img title="<?echo $Grade[0];?>" src="images/pgrades<? echo $Pays.$Grade[1]; ?>.gif"></td>
					<td><img src='<? echo $Pays;?>20.gif'></td>
					<td><? echo $Victoires;?></td>
					<td bgcolor='lightyellow'><? echo $Score;?></td>
				</tr>
	<?
				$i++;
			}
		}
	}
}
else
{
	echo "<p>Vous devez être connecté pour accéder à cette page!</p>";
}
?>
</table>
<hr>
</div>
