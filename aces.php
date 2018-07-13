<?
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
if($_SESSION['AccountID'] >0)
{
	$i=0;
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		echo "<h1>Classement des As</h1><a href='index.php?view=aces_ia' class='btn btn-primary'>Pilotes IA</a>
		<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
			<thead><tr><th>N°</th><th>Pilote</th><th>Unité</th><th>Grade</th><th>Pays</th><th>Points</th>
				<th title='Victoires confirmées'>Victoires</th>
				<th title='Défaites'>Defaites</th>
				<th title='Victoires non confirmées'>Probables</th>
				<th title='Avions ennemis endommagés'>Endommagés</th>
				<th title='Avions de Transport de tous types'>Transports</th>
				<th title='Avions de Reconnaissance de tous types'>Reconn.</th>
				<th title='Bombardiers Monomoteurs et Avions d'Assaut'>B. Légers</th>
				<th title='Bombardiers Bimoteurs et Trimoteurs'>B. Moyens</th>
				<th title='Bombardiers Quadrimoteurs'>B. Lourds</th>
				<th title='Avions de Chasse monomoteurs'>Chasseurs</th>
				<th title='Chasseurs-Bombardiers'>Ch.-Bomb.</th>
				<th title='Avions de Chasse bimoteurs et Chasseurs de Nuit'>Ch. Lourds</th>
				<th title='Avion le plus souvent utilisé par le pilote'>Avion favori</th></tr></thead>";
			$query="SELECT ID,Nom,Pays,Unit,Avancement, 
			(SELECT COUNT(*) FROM Avion,Chasse WHERE Chasse.Joueur_win=Pilote.ID AND Avion.ID=Chasse.Avion_loss AND Avion.Type=6 AND Chasse.PVP IN(0,2,4)) AS Transports,
			(SELECT COUNT(*) FROM Avion,Chasse WHERE Chasse.Joueur_win=Pilote.ID AND Avion.ID=Chasse.Avion_loss AND Avion.Type IN (3,9) AND Chasse.PVP IN(0,2,4)) AS Reco,
			(SELECT COUNT(*) FROM Avion,Chasse WHERE Chasse.Joueur_win=Pilote.ID AND Avion.ID=Chasse.Avion_loss AND Avion.Type IN (7,10) AND Chasse.PVP IN(0,2,4)) AS Bomb,
			(SELECT COUNT(*) FROM Avion,Chasse WHERE Chasse.Joueur_win=Pilote.ID AND Avion.ID=Chasse.Avion_loss AND Avion.Type=2 AND Chasse.PVP IN(0,2,4)) AS Bim,
			(SELECT COUNT(*) FROM Avion,Chasse WHERE Chasse.Joueur_win=Pilote.ID AND Avion.ID=Chasse.Avion_loss AND Avion.Type IN (1,12) AND Chasse.PVP IN(0,2,4)) AS Chasseurs,
			(SELECT COUNT(*) FROM Avion,Chasse WHERE Chasse.Joueur_win=Pilote.ID AND Avion.ID=Chasse.Avion_loss AND Avion.Type=5 AND Chasse.PVP IN(0,2,4)) AS Jabos,
			(SELECT COUNT(*) FROM Avion,Chasse WHERE Chasse.Joueur_win=Pilote.ID AND Avion.ID=Chasse.Avion_loss AND Avion.Type=4 AND Chasse.PVP IN(0,2,4)) AS Zerstorer,
			(SELECT COUNT(*) FROM Avion,Chasse WHERE Chasse.Joueur_win=Pilote.ID AND Avion.ID=Chasse.Avion_loss AND Avion.Type=11 AND Chasse.PVP IN(0,2,4)) AS Quadri,
			(SELECT COUNT(*) FROM Chasse WHERE Chasse.Pilote_loss=Pilote.ID AND Chasse.PVP IN(1,2)) AS Defaites,
			(SELECT COUNT(*) FROM Chasse_Probable WHERE Chasse_Probable.Joueur_win=Pilote.ID AND Chasse_Probable.PVP=0) AS Probable,
			(SELECT COUNT(*) FROM Chasse_Probable WHERE Chasse_Probable.Joueur_win=Pilote.ID AND Chasse_Probable.PVP=1) AS Endommage,
			(SELECT Transports + (Reco*2) + (Bomb*3) + (Bim*5) + (Chasseurs*10) + (Jabos*10) + (Zerstorer*5) + (Quadri*15) - (Defaites*10)) AS Points,
			(SELECT Transports + Reco + Bomb + Bim + Chasseurs + Jabos + Zerstorer + Quadri) AS Victories
			FROM Pilote WHERE Reputation > 500 AND Avancement >500 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY Points DESC LIMIT 150";
		//$result=mysqli_query($con,"SELECT ID,Nom,Pays,Unit,Avancement,Victoires FROM Pilote WHERE Victoires > 0 ORDER BY Victoires DESC LIMIT 150");
		$con=dbconnecti();
		$msc=microtime(true);
		$result=mysqli_query($con,$query);
		$msc=microtime(true)-$msc;
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avion_id=false;
				$ID=$data['ID'];
				$Unit=$data['Unit'];
				//$Victoires=$data['Victoires'];
				$Transports=$data['Transports'];
				$Reco=$data['Reco'];
				$Bomb=$data['Bomb'];
				$Bi=$data['Bim'];
				$Chasseurs=$data['Chasseurs'];
				$Jabos=$data['Jabos'];
				$Zerstorer=$data['Zerstorer'];
				$Quadri=$data['Quadri'];
				$Points=$data['Points'];
				$Victoires=floor($data['Victories']/10);
				$Probable=floor($data['Probable']/10);
				$Endommage=floor($data['Endommage']/10);
				$Defaites=$data['Defaites'];						
				if($Points)
				{
					//$con=dbconnecti();
					$Avion_result=mysqli_query($con,"SELECT Avion_win,COUNT(*) AS nb FROM Chasse WHERE Joueur_win='$ID' AND PVP IN(0,2,4) GROUP BY Avion_win ORDER BY nb DESC,Date DESC LIMIT 1");
					$Unite=mysqli_result(mysqli_query($con,"SELECT Nom FROM Unit WHERE ID='$Unit'"),0);
					$SetDataPts=mysqli_query($con,"UPDATE Pilote SET Victoires='$Points' WHERE ID='$ID'");
					//mysqli_close($con);
					if($Avion_result)
					{
						while($data_avion=mysqli_fetch_array($Avion_result, MYSQLI_ASSOC))
						{
							$Avion_id=$data_avion['Avion_win'];
						}
						mysqli_free_result($Avion_result);
					}
					if($Avion_id)
						$Avion=GetAvionIcon($Avion_id,$data['Pays']);
					else
						$Avion="";
					$Grade=GetAvancement($data['Avancement'],$data['Pays']);
					$Avion_unit_img="images/unit/unit".$data['Unit']."p.gif";
					if(is_file($Avion_unit_img))
						$Unite="<img src='".$Avion_unit_img."' title='".$Unite."'>";				
					$i++;					
					if($ID ==$PlayerID)
						echo "<tr bgcolor='LightYellow'>";
					else
						echo "<tr>";
					echo "<td>".$i."</td><td><a href='user_public.php?Pilote=".$ID."' target='_blank' rel='noreferrer' class='lien'>".$data['Nom']."</a></td><td>".$Unite."</td>
					<td><img title='".$Grade[0]."' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td><td><img src='".$data['Pays']."20.gif'></td>
					<td>".$Points."</td>
					<td title='".$data['Victories']."'>".$Victoires."</td>
					<td style='color:red;'>".$Defaites."</td>				 
					<td>".$Probable."</td>
					<td>".$Endommage."</td>
					<td>".$Transports."</td>
					<td>".$Reco."</td>
					<td>".$Bomb."</td>
					<td>".$Bi."</td>
					<td>".$Quadri."</td>
					<td>".$Chasseurs."</td>
					<td>".$Jabos."</td>
					<td>".$Zerstorer."</td>
					<td>".$Avion."</td></tr>";
				}
			}
			if($msc >5)
			{
				mail('binote@hotmail.com', 'Aube des Aigles: Slow Aces' , $msc.' secondes pour officier '.$PlayerID);
				echo "<p class='lead'>L'affichage de cette page est trop lent sur votre système. Veuillez vider le cache de votre navigateur internet et/ou utiliser une connexion plus stable.</p>";
			}
		}
		else
			echo "<b>Désolé, aucun as n'a encore émergé dans cette campagne</b>";
		echo "<tfoot><tr><th>N°</th><th>Pilote</th><th>Unité</th><th>Grade</th><th>Pays</th><th>Points</th>
				<th title='Victoires confirmées'>Victoires</th>
				<th title='Défaites'>Defaites</th>
				<th title='Victoires non confirmées'>Probables</th>
				<th title='Avions ennemis endommagés'>Endommagés</th>
				<th title='Avions de Transport de tous types'>Transports</th>
				<th title='Avions de Reconnaissance de tous types'>Reconn.</th>
				<th title='Bombardiers Monomoteurs et Avions d'Assaut'>B. Légers</th>
				<th title='Bombardiers Bimoteurs et Trimoteurs'>B. Moyens</th>
				<th title='Bombardiers Quadrimoteurs'>B. Lourds</th>
				<th title='Avions de Chasse monomoteurs'>Chasseurs</th>
				<th title='Chasseurs-Bombardiers'>Ch.-Bomb.</th>
				<th title='Avions de Chasse bimoteurs et Chasseurs de Nuit'>Ch. Lourds</th>
				<th title='Avion le plus souvent utilisé par le pilote'>Avion</th></tr></tfoot>
			</table></div>";
	}
	else
		echo "<table class='table'><tr><td><img src='images/acces_premium.png'></td></tr><tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr></table>";
}
?>