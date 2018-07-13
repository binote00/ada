<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	$Pays=Insec($_GET['pays']);
	$Encodage=GetData("Joueur","ID",$PlayerID,"Encodage");
	if($Encodage >0)
	{
		$Date=date('Y-m-d G:i');
		if($PlayerID !=1)
		{
			$IP=$_SERVER['REMOTE_ADDR'];
			$con=dbconnecti(2);
			$query="INSERT INTO Encodeurs (PlayerID,Date,IP)";
			$query.="VALUES ('$PlayerID','$Date','$IP')";
			$ok=mysqli_query($con, $query);
			mysqli_close($con);
			if(!$ok)
			{
				$msg.='Erreur insert '.mysqli_error($con);
				mail('binote@hotmail.com','Aube des Aigles: Encodeur Insert Error',$msg);
			}
		}
		?>
		<h1>Les as</h1>
		<form action="index.php?view=db_as_add" method="post">
		<input type='hidden' name='country' value="<?echo $Pays;?>">
		<input type="Submit" value="Nouveau" class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>
		<table class='table'>
			<thead><tr>
				<th>N°</th>
				<th>Pilote</th>
				<th>Pays</th>
				<th>Unité</th>
				<th>Grade</th>
				<th>Victoires</th>
				<th>Actions</th>
			</tr></thead>
		<?
		$con=dbconnecti();
		$Pays=mysqli_real_escape_string($con,$Pays);
		$query="SELECT ID,Nom,Pays,Unit,Avancement,Engagement,Victoires FROM Pilote_IA WHERE Pays='$Pays' AND Pilotage >200 AND Unit_Ori >0 ORDER BY Victoires DESC, Unit DESC, Avancement DESC";
		$result=mysqli_query($con,$query);
		if($result)
		{
			$num=mysqli_num_rows($result);
			if($num ==0)
				echo "<b>Désolé, aucun as n'a encore été encodé pour cette nation</b>";
			else
			{
				$i=0;
				while($i <$num) 
				{
					$ID=mysqli_result($result,$i,"ID");
					$Victoires=mysqli_result($result,$i,"Victoires");
					$Pilote=mysqli_result($result,$i,"Nom");
					$Unit=mysqli_result($result,$i,"Unit");
					$Avancement=mysqli_result($result,$i,"Avancement");
					$Engagement=mysqli_result($result,$i,"Engagement");
					$Pays=mysqli_result($result,$i,"Pays");
					$Unite_nom=GetData("Unit","ID",$Unit,"Nom");
					$Grade=GetAvancement($Avancement,$Pays);			
					$Avion_unit_img="images/unit/unit".$Unit."p.gif";
					if(is_file($Avion_unit_img))
						$Unite="<img src='".$Avion_unit_img."' title='".$Unite_nom."'><br>".$Unite_nom;
					else
						$Unite=$Unite_nom;
					echo "<tr>";?>
						<td><? echo $i+1;?></td>
						<td><? echo $Pilote;?></td>
						<td><img src='<? echo $Pays;?>20.gif'></td>
						<td><? echo $Unite;?></td>
						<td><? echo $Grade[0];?></td>
						<td><? echo $Victoires;?></td>
						<td><form action="index.php?view=db_as_modif" method="post">
						<input type='hidden' name='pilote' value="<?echo $ID;?>">
						<input type='hidden' name='nom' value="<?echo $Pilote;?>">
						<input type='hidden' name='country' value="<?echo $Pays;?>">
						<input type='hidden' name='victoires' value="<?echo $Victoires;?>">
						<input type='hidden' name='engagement' value="<?echo $Engagement;?>">
						<input type='hidden' name='unite' value="<?echo $Unit;?>">
						<input type='hidden' name='grade' value="<?echo $Avancement;?>">
						<input type="Submit" value="Modifier" class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
						</form></td></tr>
		<?			$i++;
				}
			}
		}
		else
			echo "<b>Désolé, aucun as n'a encore été encodé pour cette nation</b>";
		echo "</table></div>";
	}
	else
		echo "Vous n'avez pas le droit d'accéder à cette page!";
}
else
	echo "Vous n'avez pas le droit d'accéder à cette page!";
?>