<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)$Premium=GetData("Joueur","ID",$AccountID,"Premium");
if($Premium >0)
{
	include_once('./jfv_txt.inc.php');
	$Tri=Insec($_POST['Tri']);
	if(!$Tri)$Tri=8;
	?>
	<h1>Tableau des Missions</h1>
	<div style='overflow:auto; width: 100%;'>
	<table class='table table-striped'>
		<thead><tr>
			<th>N°</th>
			<th>Pilote</th>
			<th>Unité</th>
			<th>Grade</th>
			<th>Pays</th>
			<!--<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="9"><input title="Missions de patrouille défensive" type='Submit' value='Patrouille'></form></th>
			<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="2"><input title="Missions d'escorte" type='Submit' value='Escorte'></form></th>
			<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="4"><input title="Missions de Sauvetage" type='Submit' value='Sauvetage'></form></th>
			<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="10"><input title="Missions de Ravitaillement" type='Submit' value='Ravit'></form></th>-->
			<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="3"><input title="Missions de Reconnaissance, tactique ou stratégique" type='Submit' value='Recos'></form></th>
			<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="5"><input title="Bombardements Stratégiques de Jour" type='Submit' value='Raids de Jour'></form></th>
			<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="6"><input title="Bombardements Stratégiques de Nuit" type='Submit' value='Raids de Nuit'></form></th>
			<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="11"><input title="Bombardements Tactiques et Navals" type='Submit' value='Attaques'></form></th>
			<!--<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="7"><input title="Missions totales" type='Submit' value='Total'></form></th>-->
			<th><form action='index.php?view=missions' method='post'><input type='hidden' name='Tri' value="8"><input title="Score" type='Submit' value='Points'></form></th>
		</tr></thead>
	<?
	switch($Tri)
	{
		case 2:
			$Tri="Escortes";
		break;
		case 3:
			$Tri="Recce";
		break;
		case 4:
			$Tri="Sauvetage";
		break;
		case 5:
			$Tri="Raids_Bomb";
		break;
		case 6:
			$Tri="Raids_Bomb_Nuit";
		break;
		case 7:
			$Tri="Mission";
		break;
		case 8:
			$Tri="Missions";
		break;
		case 9:
			$Tri="Patrouille";
		break;
		case 10:
			$Tri="Ravit";
		break;
		case 11:
			$Tri="Dive";
		break;
	}
		$i=0;
		$PlayerID=$_SESSION['PlayerID'];
		/*$query="SELECT ID,Nom,Pays,Unit,Avancement,Raids_Bomb,Raids_Bomb_Nuit,Dive,Missions,
		(SELECT COUNT(*) FROM Recce WHERE Recce.Joueur=Joueur.ID) AS Recce, 
		(SELECT COUNT(*) FROM Escorte WHERE Escorte.Joueur=Joueur.ID) AS Escortes, 
		(SELECT COUNT(*) FROM Patrouille WHERE Patrouille.Joueur=Joueur.ID) AS Patrouille,
		(SELECT COUNT(*) FROM Sauvetage WHERE Sauvetage.PlayerID=Joueur.ID) AS Sauvetage,
		(SELECT COUNT(*) FROM Ravitaillements WHERE Ravitaillements.PlayerID=Joueur.ID) AS Ravit,
		(SELECT Recce + Escortes + Patrouille + Raids_Bomb + Raids_Bomb_Nuit + Sauvetage + Dive + Ravit) AS Mission,
		(SELECT Escortes*50 + Recce*50 + Patrouille*30 + Raids_Bomb*70 + Raids_Bomb_Nuit*50 + Sauvetage*50 + Dive*10 + Ravit*30) AS Points
		FROM Joueur WHERE Missions >0 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 15 DAY AND CURDATE() ORDER BY $Tri DESC LIMIT 50";*/
		$query="SELECT ID,Nom,Pays,Unit,Avancement,Raids_Bomb,Raids_Bomb_Nuit,Dive,Missions,
		(SELECT COUNT(*) FROM Recce WHERE Recce.Joueur=Pilote.ID) AS Recce
		FROM Pilote WHERE Missions >0 AND Actif=0 AND Credits_date BETWEEN CURDATE() - INTERVAL 15 DAY AND CURDATE() ORDER BY $Tri DESC LIMIT 50";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				/*if($data['Points'] <1844674407370955153)
				{*/
				$ID=$data['ID'];
				$Pilote=$data['Nom'];
				$Pays=$data['Pays'];
				$Unit=$data['Unit'];
				$Avancement=$data['Avancement'];
				/*$Missions=$data['Mission'];
				$Mission_escorte=$data['Escortes'];
				$Mission_patrouille=$data['Patrouille'];
				$Mission_sauvetage=$data['Sauvetage'];
				$Mission_ravit=$data['Ravit'];*/
				$Mission_recce=$data['Recce'];
				$Raids_Jour=$data['Raids_Bomb'];
				$Raids_Nuit=$data['Raids_Bomb_Nuit'];
				$Dive=$data['Dive'];
				$Points=$data['Missions'];
				$Unit_m=GetData("Unit","ID",$Unit,"Nom");
				$Grade=GetAvancement($Avancement,$Pays);		
				if($PlayerID >0 and $ID ==$PlayerID)
					echo "<tr bgcolor='LightYellow'>";
				else
					echo "<tr>";			
				$i++;
				echo "<td>".$i."</td>";
			?>
					<td><a href="user_public.php?Pilote=<?echo $data['ID'];?>" target="_blank" class='lien'><? echo $Pilote;?></a></td>
					<td><? echo Afficher_Icone($Unit,$Pays,$Unit_m);?></td>
					<td><img title="<?echo $Grade[0];?>" src="images/grades/grades<? echo $Pays.$Grade[1]; ?>.png"></td>
					<td><img src='<? echo $Pays;?>20.gif'></td>
					<td><? echo $Mission_recce;?></td>
					<td><? echo $Raids_Jour;?></td>
					<td><? echo $Raids_Nuit;?></td>
					<td><? echo $Dive;?></td>
					<td><? echo $Points;?></td>
				</tr>
			<?
				//}
			}
		}
		else
			echo "<b>Désolé, aucune mission n'a encore été effectuée dans cette campagne</b>";
	echo "</table></div>";
}
else
{
	echo "<table class='table'>
		<tr><td><img src='images/acces_premium.png'></td></tr>
		<tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr>
	</table>";
}
?>
