<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Depot=Insec($_POST['Lieu']);
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0 and $Depot >0)
	{
		$con=dbconnecti(4);
		$resultb=mysqli_query($con,"SELECT e.*,u.Nom,u.Pays,l.Nom as Ville,p.Nom as Off FROM Events_ravit as e,gnmh_aubedesaiglesnet.Unit as u,gnmh_aubedesaiglesnet.Lieu as l,gnmh_aubedesaiglesnet.Pilote as p
		WHERE e.Unit=u.ID AND e.Pilote_eni=l.ID AND e.PlayerID=p.ID AND e.Pilote_eni='$Depot' AND e.Event_Type IN (101,102,103) ORDER BY e.ID DESC LIMIT 50");
		mysqli_close($con);
		if($resultb)
		{
			while($data=mysqli_fetch_array($resultb))
			{
				$Ville=$data['Ville'];
				if($data['Event_Type'] ==101)
					$Cat="Carburant";
				elseif($data['Event_Type'] ==102)
					$Cat="Munitions";
				elseif($data['Event_Type'] ==103)
					$Cat="Bombes";
				if($data['Avion'] ==87)
					$Type_rav="Essence 87 Octane";
				elseif($data['Avion'] ==100)
					$Type_rav="Essence 100 Octane";
				elseif($data['Avion'] ==1)
					$Type_rav="Diesel";
				elseif($data['Avion'] >=8 and $data['Avion'] <=200)
					$Type_rav="Obus de ".$data['Avion']."mm";
				else
					$Type_rav="Bombes de ".$data['Avion']."kg";
				$journal_txt.="<tr><td>".$data['Date']."</td><td>".$Cat."</td><td>".$Type_rav."</td><td>".$data['Avion_Nbr']."</td><td>".Afficher_Icone($data['Unit'],$data['Pays'],$data['Nom'])."</td><td>".$data['Off']."</td></tr>";
			}
			mysqli_free_result($resultb);
		}
		$titre="Ravitaillement provenant du dépôt";
		if($journal_txt)
			$mes="<h2>Dépôt de ".$Ville."</h2><table class='table table-striped'><thead><tr><th>Date</th><th>Catégorie</th><th>Type</th><th>Quantité</th><th>Unité</th><th>Officier</th></tr></thead>".$journal_txt."</table>";
		else
			$mes="<p>Aucune demande de ravitaillement n'a été effectuée vers ce dépôt</p>";
		$img="<img src='images/gestion_bombs".$country.".jpg'>";
		$menu="<a class='btn btn-default' title='Retour' href='index.php?view=em_depots'>Retour</a>";
		include_once('./default.php');
	}
	else
		echo "<h1>Vous n'êtes pas autorisé à effectuer cette action!</h1>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>