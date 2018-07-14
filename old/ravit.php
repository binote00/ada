<head>
	<title>Aube des Aigles</title>
	<link href="test.css" rel="stylesheet" type="text/css">
</head>
<div>
<br>
<table  bgcolor="#999999" border="0" rules=rows cellspacing="1" cellpadding="5">
	<tr><th colspan="9" bgcolor="LightSteelBlue">Tableau des Missions de Reconnaissance</th></tr>
	<tr>
		<th bgcolor="CadetBlue">Date</th>
		<th bgcolor="CadetBlue">Avion</th>
		<th bgcolor="CadetBlue">Unité</th>
		<th bgcolor="CadetBlue">Cible photographiée</th>
		<th bgcolor="CadetBlue">Lieu</th>
	</tr>
		<?
		include_once('./jfv_include.inc.php');
		$ID=Insec($_GET['pilote']);
		dbconnect();
		$query="SELECT * FROM Recce WHERE Joueur = '$ID' ORDER BY Date DESC";
		$result=mysql_query($query);
		mysql_close();
		if($result)
		{
			$num=mysql_numrows($result);

			if($num==0)
			{
				echo "<b>Désolé, aucune mission réussie enregistrée à ce jour.</b>";
			}
			else
			{
				$i=0;
				while ($i < $num) 
				{

					$Date=str_replace("2012","1940",mysql_result($result,$i,"Date"));
					$Unit = mysql_result($result,$i,"Unite");
					$Avion = mysql_result($result,$i,"Avion");
					$Cible_detruite = mysql_result($result,$i,"Nom");
					$Avion_win = GetData("Avion","ID",$Avion,"Nom");
					$Unite_win = GetData("Unit","ID",$Unit,"Nom");
					$Lieu = GetData("Lieu","ID",mysql_result($result,$i,"Lieu"),"Nom");
					
					$Avion_img = "images/avion".$Avion.".gif";
					$Avion_unit_img = "images/unit".$Unit."p.gif";
					if(is_file($Avion_img))
					{
						$Avion_win = "<img src='".$Avion_img."' title='".$Avion_win."'>";
					}
					if(is_file($Avion_unit_img))
					{
						$Unite_win = "<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
					}
		?>
	<tr>
		<td><? echo $Date;?></td>
		<td><? echo $Avion_win;?></td>
		<td><? echo $Unite_win;?></td>
		<td><? echo $Cible_detruite;?></td>
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
//mysql_free_result($result);
?>
</table>
<hr>
</div>
