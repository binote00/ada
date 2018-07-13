<?
include_once('./menu_classement.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
?>
<div><h2>Tableau des avions ayant abattu le plus de joueurs</h2>
<table class='table'>
	<tr><thead><th>N°</th>
		<th>Nom</th>
		<th>Pays</th>
		<th>Défaites</th>
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
$con=dbconnecti();
$result=mysqli_query($con,"SELECT Avion_win,COUNT(*) FROM Chasse WHERE PVP=1 GROUP BY Avion_win ORDER BY COUNT(*) DESC");
mysqli_close($con);
if($result)
{
	while($data=mysqli_fetch_array($result))
	{
			$ID = $data[0];
			$con=dbconnecti();
			//$result2=mysqli_query($con,"SELECT Nom,Pays,Puissance,Masse,VitesseH,Maniabilite,ManoeuvreH,Robustesse,Detection,Visibilite,Blindage,ArmePrincipale,,Arme1_Nbr,ArmeSecondaire,Arme2_Nbr FROM Avion WHERE ID='$ID'");
			$result2=mysqli_query($con,"SELECT * FROM Avion WHERE ID='$ID'");
			if($result2)
			{
				while($avion_data=mysqli_fetch_array($result2))
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
				}
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
			</tr>
			<tr>
				<td><? echo $i;?></td>
				<td><img src='images/avions/avion<? echo $ID;?>.gif' title='<? echo $Nom;?>'></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><? echo $data[1];?></td>
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