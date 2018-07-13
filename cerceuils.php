<?
include_once('./menu_classement.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
?>
<div><h2>Tableau des avions le plus souvent abattus</h2>
<table class='table'>
	<tr><thead><th>N°</th>
		<th>Nom</th>
		<th>Pays</th>
		<th>Défaites</th>
		<th title='Plus petit = meilleur'>Ratio</th>
		<th>Puissance</th>
		<th>Masse</th>
		<th>Vitesse max</th>
		<th>Taux de roulis</th>
		<th>Rayon de virage</th>
		<th>Robustesse</th>
		<th>Blindage</th>
	</thead></tr>
<?
$i=0;
$query="SELECT Avion_loss,COUNT(*) FROM Chasse WHERE PVP IN(0,4) GROUP BY Avion_loss ORDER BY COUNT(*) DESC";
$con=dbconnecti();
$result=mysqli_query($con,$query);
mysqli_close($con);
if($result)
{
	while($data=mysqli_fetch_array($result,MYSQLI_NUM))
	{
			$ID = $data[0];
			$con=dbconnecti();
			$result2=mysqli_query($con,"SELECT ID,Nom,Pays,Puissance,Masse,VitesseH,Maniabilite,ManoeuvreH,Robustesse,Detection,Visibilite,Blindage,ArmePrincipale,Arme1_Nbr,ArmeSecondaire,Arme2_Nbr FROM Avion WHERE ID='$ID'");
			mysqli_close($con);
			if($result2)
			{
				while($avion_data = mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$ID = $avion_data['ID'];
					$Nom = $avion_data['Nom'];
					$Pays = $avion_data['Pays'];
					$Vit = $avion_data['VitesseH'];
					$Puiss = $avion_data['Puissance'];
					$Masse = round($avion_data['Masse']/1000,1);
					$Roll = $avion_data['Maniabilite'];
					$Man = $avion_data['ManoeuvreH'];
					$HP = $avion_data['Robustesse'];
					$Blindage = $avion_data['Blindage'];
					$Arme1 = $avion_data['ArmePrincipale'];
					$Arme1_Nbr = $avion_data['Arme1_Nbr'];
					$Arme2 = $avion_data['ArmeSecondaire'];
					$Arme2_Nbr = $avion_data['Arme2_Nbr'];
					$Detection = $avion_data['Detection'];
					$Visibilite = $avion_data['Visibilite'];
					//$Stabilite = $avion_data['Stabilite'];
					$con=dbconnecti(4);
					$resultp=mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=1 AND (Avion='$ID' OR Avion_Nbr='$ID')");
					mysqli_close($con);
					if($resultp)
					{
						$datap = mysqli_fetch_array($resultp,MYSQLI_NUM);
						if($datap[0])
						{
							$ratio=round($data[1]/$datap[0],2); 
						}
						mysqli_free_result($resultp);
					}
				}
				mysqli_free_result($result2);
			}
			$i++;
			$HP=ucfirst(GetRobustesse($HP));
			$Man=ucfirst(GetManoeuvre($Man));
			$Roll=ucfirst(GetManiabilite($Roll));
			if($Blindage == 0)
				$Blindage="Aucun";
			elseif($Blindage < 10)
				$Blindage="Léger";
			else
				$Blindage="Moyen";
	?>
			<tr>
				<td><? echo $i;?></td>
				<td><img src='images/avions/avion<? echo $ID;?>.gif' title='<? echo $Nom;?>'></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><? echo $data[1];?></td>
				<td><? echo $ratio;?></td>
				<td><? echo $Puiss."cv";?></td>
				<td><? echo $Masse."T";?></td>
				<td><? echo $Vit."km/h";?></td>
				<td><? echo $Roll;?></td>
				<td><? echo $Man;?></td>
				<td><? echo $HP;?></td>
				<td><? echo $Blindage;?></td>
			</tr>
	<?
	}
}
else
	echo "<b>Désolé, aucun destructeur n'a encore émergé dans cette campagne</b>";
echo "</table><hr></div>";
?>

