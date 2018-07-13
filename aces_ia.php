<?
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
if($_SESSION['AccountID'] >0)
{
	$i=0;
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		echo "<h1>Classement des As</h1><div style='overflow:auto; width: 100%;'><table class='table table-striped'>
			<thead><tr><th>N°</th><th>Pilote</th><th>Unité</th><th>Grade</th><th>Pays</th><th title='Victoires confirmées'>Victoires</th></tr></thead>";
		$query="SELECT ID,Nom,Pays,Unit,Avancement,Victoires FROM Pilote_IA WHERE Victoires >0 AND Unit >0 AND Actif=1 ORDER BY Victoires DESC LIMIT 150";
		$con=dbconnecti();
		$msc=microtime(true);
		$result=mysqli_query($con,$query);
		$msc=microtime(true)-$msc;
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$i++;
				$Grade=GetAvancement($data['Avancement'],$data['Pays']);
				echo "<td>".$i."</td><td>".$data['Nom']."</a></td><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td>
				<td><img title='".$Grade[0]."' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td><td><img src='".$data['Pays']."20.gif'></td>
				<td title='".$data['Victories']."'>".$data['Victoires']."</td>
				<td>".$Avion."</td></tr>";
			}
			if($msc >5)
			{
				mail('binote@hotmail.com', 'Aube des Aigles: Slow Aces' , $msc.' secondes pour officier '.$PlayerID);
				echo "<p class='lead'>L'affichage de cette page est trop lent sur votre système. Veuillez vider le cache de votre navigateur internet et/ou utiliser une connexion plus stable.</p>";
			}
		}
		else
			echo "<b>Désolé, aucun as n'a encore émergé dans cette campagne</b>";
		echo "<tfoot><tr><th>N°</th><th>Pilote</th><th>Unité</th><th>Grade</th><th>Pays</th><th title='Victoires confirmées'>Victoires</th></tr></tfoot></table></div>";
	}
	else
		echo "<table class='table'><tr><td><img src='images/acces_premium.png'></td></tr><tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr></table>";
}
?>