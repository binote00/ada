<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];	
if($PlayerID >0 or $Admin)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$country=$_SESSION['country'];
		?><h1>Escadrilles</h1>
		<div style='overflow:auto; width: 100%;'>
		<table class='table table-striped table-condensed'>
			<thead><tr>
				<th>N°</th>
				<th>Unité</th>
				<th>Pays</th>
				<th>Commandant</th>
				<th>Réputation</th>
				<th>Type</th>
				<th>Victoires</th>
				<!--<th>Pilotes</th>
				<th>Escorte</th>
				<th>Patrouille</th>
				<th>Reconnaissance</th>
				<th>Attaque</th>
				<th>Bombardement</th>
				<th>Ravitaillement</th>
				<th>Sauvetage</th>
				<th  title="Les missions de chasse libre ne sont pas prises en compte dans ce total">Total</th>-->
			</tr></thead>
		<?
		$i=0;
		$con=dbconnecti();
		$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
		$result=mysqli_query($con,"SELECT ID,Nom,Pays,Reputation,Commandant,Type FROM Unit WHERE Reputation >0 AND Pays IN (1,2,3,4,6,7,8,9) AND Etat=1 ORDER BY Reputation DESC LIMIT 50");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$ID=$data['ID'];
				$Unite_Nom=$data['Nom'];
				$Pays=$data['Pays'];
				$Reputation=$data['Reputation'];
				$Type=GetAvionType($data['Type']);
				$Commandant=$data['Commandant'];
				if($Premium)
				{
					if($Commandant >0)
						$Cdt=GetData("Pilote","ID",$Commandant,"Nom");
					else
						$Cdt="";
					//$con=dbconnecti();
					$Victoires=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE PVP IN (0,4) AND Unite_win='$ID'"),0);
					/*$Mission_atk=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque WHERE Unite='$ID' AND Type >0"),0);
					$Mission_bomb=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement WHERE Unite='$ID'"),0);
					$Mission_recce=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce WHERE Unite='$ID'"),0);
					$Mission_escorte=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Escorte WHERE Unite='$ID'"),0);
					$Mission_patrouille=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Patrouille WHERE Unite='$ID'"),0);
					$Mission_ravit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ravitaillements WHERE Unite='$ID'"),0);
					$Mission_save=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sauvetage WHERE Unite='$ID'"),0);
					$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Unit='$ID'"),0);*/
					//mysqli_close($con);
					//$Total=$Mission_atk + $Mission_recce + $Mission_escorte + $Mission_bomb + $Mission_patrouille + $Mission_ravit + $Mission_save;			
					/*$i++;*/
				}
				else
				{
					$Cdt="<div class='i-flex premium20'></div>";
					$Victoires="<div class='i-flex premium20'></div>";
				}
				/*$Avion_unit_img='images/unit/unit'.$ID.'p.gif';
				if(is_file($Avion_unit_img))
					$Unite_Nom="<img src='".$Avion_unit_img."' title='".$Unite_Nom."'><br><b>".$Unite_Nom.'</b>';*/
				echo "<tr>";			
				$i++;
				/*if($i ==1)
					echo "<th bgcolor='gold' title='Les pilotes de cette unité inspirent une très grande crainte à leurs ennemis'>".$i.'</th>';
				elseif($i ==2)
				{
					echo "<th bgcolor='silver' title='Les pilotes de cette unité inspirent une grande crainte à leurs ennemis'>".$i.'</th>';
				}
				elseif($i ==3)
				{
					echo "<th bgcolor='tan' title='Les pilotes de cette unité inspirent la crainte à leurs ennemis'>".$i."</th>";
				}
				else
				{*/
					echo '<td>'.$i.'</td>';
				//}			
				echo "<td>".Afficher_Icone($ID,$Pays,$Unite_Nom)."<br><b>".$Unite_Nom."</b></td><td><img src='".$Pays."20.gif'></td><td>".$Cdt."</td><th>".$Reputation."</th><td>".$Type."</td><td>".$Victoires."</td></tr>";
				/* if($country == $Pays or $Renseignement > 200){echo $Pilotes;}else{echo "Inconnu";}?></td>
				<td><? echo $Mission_escorte;?></td>
				<td><? echo $Mission_patrouille;?></td>
				<td><? echo $Mission_recce;?></td>
				<td><? echo $Mission_atk;?></td>
				<td><? echo $Mission_bomb;?></td>
				<td><? echo $Mission_ravit;?></td>
				<td><? echo $Mission_save;?></td>
				<td><? echo $Total;*/
			}
		}
		echo "</table></div>";
	}
	else
	{
		echo "<table class='table'>
			<tr><td><img src='images/acces_premium.png'></td></tr>
			<tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr>
		</table>";
	}
}
?>
