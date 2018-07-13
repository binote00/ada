<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$ID=Insec($_GET['unite']);
	if(is_numeric($ID))
	{
		$con=dbconnecti();
		$ID=mysqli_real_escape_string($con,$ID);
		$result=mysqli_query($con,"SELECT Nom,Base_Ori,Pays,Reputation,Avion1_Ori,Avion2_Ori,Avion3_Ori FROM Unit WHERE ID='$ID'");
		$resultas=mysqli_query($con,"SELECT Nom,Avancement,Victoires FROM Pilote_IA WHERE Unit='$ID' AND Victoires >0 AND Actif=1 ORDER BY Victoires DESC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$Nom=$data['Nom'];
				$Pays=$data['Pays'];
				$Reputation=$data['Reputation'];
				$Base_Ori=$data['Base_Ori'];
				$Avion1=$data['Avion1_Ori'];
				$Avion2=$data['Avion2_Ori'];
				$Avion3=$data['Avion3_Ori'];
			}
			mysqli_free_result($result);
		}
		if($Pays ==$_SESSION['country'])
			$Base_nom=GetData("Lieu","ID",$Base_Ori,"Nom");
		else
			$Base_nom="Inconnu";
		$Pays_nom=GetPays($Pays);
	?>
	<html><head><title>Aube des Aigles - Unités</title><link href="css/test.css" rel="stylesheet" type="text/css"></head><hr>
	<div><table bgcolor="#999999" align="center" width="500">
			<tr bgcolor="#000029"><th align="left"><img src="images/<?echo $Pays;?>20.gif">
			</th><th colspan="2" class="white"><? echo $Nom; ?></th><th align="right"><img src="images/<?echo $Pays;?>20.gif"></tr>
			<tr bgcolor="#666699"><th colspan='4'>Insigne</th></tr>
			<tr width="500"><td colspan='4'><img src="images/unit/unit<? echo $ID; ?>.gif"></td></tr>
			<tr bgcolor="#666699"><th colspan='4'>Informations</th></tr>
			<tr onmouseover="this.style.background='#666699'" onmouseout="this.style.background='#999999'">
				<th class='dark_bl'>Pays</th> 
				<td class='dark_l'><?echo $Pays_nom;?></td>
				<th class='dark_bl'>Avion 1</th>
				<td class='dark_l'><?echo GetAvionIcon($Avion1,$Pays,0,$ID,0);?></td>
			</tr>
			<tr onmouseover="this.style.background='#666699'" onmouseout="this.style.background='#999999'">
				<th class='dark_bl'>Base</th>
				<td class='dark_l'><?echo $Base_nom;?></td>
				<th class='dark_bl'>Avion 2</th>
				<td class='dark_l'><?echo GetAvionIcon($Avion2,$Pays,0,$ID,0);?></td>
			</tr>
			<tr onmouseover="this.style.background='#666699'" onmouseout="this.style.background='#999999'">
				<th class='dark_bl'>Réputation</th>
				<td class='dark_l'><?echo $Reputation;?></td>
				<th class='dark_bl'>Avion 3</th>
				<td class='dark_l'><?echo GetAvionIcon($Avion3,$Pays,0,$ID,0);?></td>
			</tr>
			<tr bgcolor="#666699"><th colspan='4'>As</th></tr>
	<?//As
		if($resultas)
		{
			$num=mysqli_num_rows($resultas);
			if($num)
			{
				$i=0;
				while($i <$num) 
				{

					$Victoires=mysqli_result($resultas,$i,"Victoires");
					$Pilote=mysqli_result($resultas,$i,"Nom");
					$Avancement=mysqli_result($resultas,$i,"Avancement");
					$Grade=GetAvancement($Avancement,$Pays);
					?></tr><tr onmouseover="this.style.background='#666699'" onmouseout="this.style.background='#999999'">
						<td colspan="2"><? echo $Pilote;?></td><td><? echo $Grade[0];?></td><td><? echo $Victoires;?></td></tr><?
					$i++;
				}
			}
		}
		else
			echo "<b>Désolé, aucun résultat</b>";
		echo "</table></div></body></html>";
	}
	else
		echo "Tsss";
}
?>