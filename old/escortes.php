<head>
	<title>Aube des Aigles</title>
	<link href="test.css" rel="stylesheet" type="text/css">
</head>
<div>
<br>
<table  bgcolor="#999999" border="0" rules=rows cellspacing="1" cellpadding="5">
	<tr><th colspan="9" bgcolor="LightSteelBlue">Tableau des Missions d'Escorte</th></tr>
	<tr>
		<th bgcolor="CadetBlue">Date</th>
		<th bgcolor="CadetBlue">Avion</th>
		<th bgcolor="CadetBlue">Unité</th>
		<th bgcolor="CadetBlue">Avions ou Navires escortés</th>
		<th bgcolor="CadetBlue">Lieu</th>
<?
include_once('./jfv_include.inc.php');
$ID = Insec($_GET['pilote']);
$PlayerID = $_SESSION['PlayerID'];
if($PlayerID == $ID or $PlayerID == 1)
{
	$con = dbconnecti();
	$result=mysqli_query($con, "SELECT * FROM Escorte WHERE Joueur = '$ID' ORDER BY Date DESC");
	mysqli_close($con);
	if($result)
	{
		$num=mysqli_num_rows($result);

		if($num==0)
		{
			echo "<b><center>Désolé, aucune mission réussie enregistrée à ce jour.</center></b>";
		}
		else
		{
			$i=0;
			while ($i < $num) 
			{

				$Date=str_replace("2012","1940",mysqli_result($result,$i,"Date"));
				$Avion_win = mysqli_result($result,$i,"Avion");
				$Unite_win = mysqli_result($result,$i,"Unite");
				$Escorte_nbr = mysqli_result($result,$i,"Escorte_nbr");
				$Escorte = mysqli_result($result,$i,"Escorte");					
				$Escorte_Nom = GetData("Avion","ID",$Escorte,"Nom");
				$Lieu = GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");
				$Avion_Nom = GetData("Avion","ID",$Avion_win,"Nom");
				$Unite_Nom = GetData("Unit","ID",$Unite_win,"Nom");
				
				$Avion_img = "images/avion".$Avion_win.".gif";
				$Avion_unit_img = "images/unit".$Unite_win."p.gif";
				$Escorte_img = "images/avion".$Escorte.".gif";
				if(is_file($Avion_img))
				{
					$Avion_Nom = "<img src='".$Avion_img."' title='".$Avion_Nom."'>";
				}
				if(is_file($Avion_unit_img))
				{
					$Unite_Nom = "<img src='".$Avion_unit_img."' title='".$Unite_Nom."'>";
				}
				if(is_file($Escorte_img))
				{
					$Escorte_Nom = "<img src='".$Escorte_img."' title='".$Escorte_Nom."'>";
				}

			?>
		</tr>
		<tr>
			<td><? echo $Date;?></td>
			<td><? echo $Avion_Nom;?></td>
			<td><? echo $Unite_Nom;?></td>
			<td><? echo $Escorte_nbr." ".$Escorte_Nom;?></td>
			<td><? echo $Lieu;?></td>
		</tr>
				<?
				$i++;
			}
		}
	}
	else
	{
		echo "<b>Désolé, aucune attaque réussie enregistrée à ce jour.</b>";
	}
}
//mysqli_free_result($result);
?>
</table>
<hr>
</div>
