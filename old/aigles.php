<?
include_once('./menu_classement.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
?>
<div><h2>Tableau des victoires par modèle d'avion</h2>
<table class='table'>
	<tr><thead><th>N°</th>
		<th>Nom</th>
		<th>Pays</th>
		<th>Victoires</th>
		<th title='Plus petit = meilleur'>Ratio</th>
		<th>Puissance</th>
		<th>Masse</th>
		<th>Vitesse max</th>
		<th>Taux de roulis</th>
		<th>Rayon de virage</th>
		<th>Robustesse</th>
		<th>Blindage</th>
		<th>Armement</th>
	</thead></tr>
<?
$i=0;
$query="SELECT Avion_win,COUNT(*) FROM Chasse WHERE PVP IN(0,4) GROUP BY Avion_win ORDER BY COUNT(*) DESC";
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
				while($avion_data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
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
						if($datap[0] > 0)
							$ratio=round($data[1]/$datap[0],2);
						else
							$ratio=0;
						mysqli_free_result($resultp);
					}
				}
				mysqli_free_result($result2);
			}
			$i++;
			if($Arme1==$Arme2)
			{
				$Arme1_nom=GetData("Armes","ID",$Arme1,"Nom");
				$Arme1_cal=substr(GetData("Armes","ID",$Arme1,"Calibre"),0,3);
				$Arme1_Nbr += $Arme2_Nbr;
				$Arme2_txt="";
			}
			else
			{
				$Arme1_nom=GetData("Armes","ID",$Arme1,"Nom");
				$Arme1_cal=substr(GetData("Armes","ID",$Arme1,"Calibre"),0,3);
				$Arme2_nom=GetData("Armes","ID",$Arme2,"Nom");
				$Arme2_cal=substr(GetData("Armes","ID",$Arme2,"Calibre"),0,3);
				$Arme2_txt=' + '.$Arme2_Nbr.' '.$Arme2_nom.' ('.$Arme2_cal.'mm)';
			}
			$HP=ucfirst(GetRobustesse($HP));
			$Man=ucfirst(GetManoeuvre($Man));
			$Roll=ucfirst(GetManiabilite($Roll));
			if($Blindage ==0)
				$Blindage="Aucun";
			elseif($Blindage <10)
				$Blindage="Léger";
			else
				$Blindage="Moyen";
	?>
			</tr>
			<tr>
				<td><? echo $i;?></td>
				<td><? echo "<img src='images/avions/avion".$ID.".gif' title='".$Nom."'>";?></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><? echo $data[1];?></td>
				<td><? echo $ratio;?></td>
				<td><? echo $Puiss.'cv';?></td>
				<td><? echo $Masse.'T';?></td>
				<td><? echo $Vit.'km/h';?></td>
				<td><? echo $Roll;?></td>
				<td><? echo $Man;?></td>
				<td><? echo $HP;?></td>
				<td><? echo $Blindage;?></td>
				<td><? echo $Arme1_Nbr.' '.$Arme1_nom.' ('.$Arme1_cal.'mm)'.$Arme2_txt;?></td>
			</tr>
	<?
	}
}
else
	echo "<b>Désolé, aucun destructeur n'a encore émergé dans cette campagne</b>";
echo "</table><hr></div>";
?>