<?
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');

$ID = Insec($_GET['moteur']);

if(is_numeric($ID))
{
	$con = dbconnecti();
	$query="SELECT * FROM Moteur WHERE ID = '$ID'";
	$result=mysqli_query($con, $query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result)) 
		{
		
			if($data['Type'])
			{
				$Engine_Type = "En ligne";
			}
			else
			{
				$Engine_Type = "En étoile";
			}
			if($data['Compresseur'] == 2)
			{
				$Compresseur = "Haute altitude";
			}
			elseif($data['Compresseur'] == 3)
			{
				$Compresseur = "Basse altitude";
			}
			elseif($data['Compresseur'] > 0)
			{
				$Compresseur = "Oui";
			}		
			else
			{
				$Compresseur = "Non";
			}
			if($data['Injection'] > 0)
			{
				$Injection = "Injection";
			}		
			else
			{
				$Injection = "Carburateur";
			}
			$Pays_nom = GetPays($data['Pays']);
			
?>
<html>
<head>
	<title>Aube des Aigles</title>
	<link href="test.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#000000">	
<div>
	<table bgcolor="#999999" align="center" width="640">
		<tr bgcolor="#000029"><th colspan='4' class='white'><? echo $Nom; ?></th></tr>
		<tr>
			<td><? echo $data['Nom']; ?></td>
			<td></td>
			<td></td>
			<td><? echo $Pays_nom; ?> <img src="images/<? echo $data['Pays']; ?>20.gif"></td>
		</tr>
		<tr bgcolor="#666699"><th colspan='4'>Caractéristiques</th></tr>
		<tr onmouseover="this.style.background='#666699'"  onmouseout="this.style.background='#999999'">
			<th class='dark_bl'>Puissance maximale</th>
			<td class='dark_l'>+/- <? echo $data['Puissance'];?>cv</td>
			<th class='dark_bl'>Masse</th>
			<td class='dark_l'><? echo $data['Masse'];?>kg</td>
		</tr>
		<tr onmouseover="this.style.background='#666699'"  onmouseout="this.style.background='#999999'">
			<th class='dark_bl'>Compresseur</th>
			<td class='dark_l'><? echo $Compresseur;?></td>
			<th class='dark_bl'>Alimentation</th>
			<td class='dark_l'><? echo $Injection;?></td>
		</tr>
		<tr onmouseover="this.style.background='#666699'"  onmouseout="this.style.background='#999999'">
			<th class='dark_bl'>Type</th>
			<td class='dark_l'><? echo $Engine_Type;?></td>
			<th class='dark_bl'>Carburant</th>
			<td class='dark_l'><? echo $data['Carburant'];?> Octane</td>
		</tr>
		<tr bgcolor="#666699"><th colspan='4'>Photo</th></tr>
		<tr width="500"><td colspan='4'><img src="images/moteur<? echo $ID; ?>.jpg"></td>
		</tr>
	</table>
</div>
<hr>
<?
		}
	}
}
else
{
	echo "Tsss";
}
?>
</body>
</html>

