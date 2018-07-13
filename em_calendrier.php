<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$Front=GetData("Pilote","ID",$PlayerID,"Front");
	$Renseignement=GetData("Pilote","ID",$PlayerID,"Renseignement");
	$Avancement=GetData("Pilote","ID",$PlayerID,"Avancement");
	$con=dbconnecti();	
	$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
	mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Commandant=$data['Commandant'];
			$Officier_Adjoint=$data['Adjoint_EM'];
			$Officier_EM=$data['Officier_EM'];
			$Officier_Rens=$data['Officier_Rens'];
		}
		mysqli_free_result($result2);
	}	
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');
	if($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_EM or $PlayerID ==$Officier_Rens or $Avancement >4999 or $Renseignement >100)
	{	
		$con=dbconnecti();
		$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Unit,Pilote.Avancement,Pilote.Credits_date,Pilote.Missions_Jour,Pilote.Credits,Pilote.Pays,Pilote.MIA,Pilote.Commando,
		Pilote.Actif,Pilote.Dispo_Jour,Pilote.Dispo_Sauf,Pilote.Dispo_Debut,Pilote.Dispo_Fin
		FROM Pilote WHERE Pilote.Pays ='$country' AND Pilote.Actif=0 AND Pilote.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() ORDER BY Pilote.Unit DESC, Pilote.Avancement DESC, Pilote.Nom DESC";
		$result=mysqli_query($con, $query);
		mysqli_close($con);
		if($result)
		{
			while($Data=mysqli_fetch_array($result))
			{
				$Dispos='';
				if($Data['Dispo_Debut'])
				{
					$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);
					//MIA
					$MIA=$Data['MIA'];
					if(!$MIA)
					{
						if($Data['Commando'])
							$MIA="Commando";
						elseif($Data['Actif'])
							$MIA="Retraité";
						else
							$MIA="Actif";
					}
					else
						$MIA="MIA<br>".GetData("Lieu","ID",$MIA,"Nom");
					$Pilote="<tr><td><a href='user_public.php?Pilote=".$Data[0]."' style='color:#4171ac;' target='_blank'>".$Data[1]."</a></td><td title='".$Avancement[0]."'><img src='images/grades/grades".$Data['Pays'].$Avancement[1].".png'></td>
					<td>".Afficher_Icone($Data[2],$country,$Unite_Nom)."</td><td>".$MIA."</td>
					<td>".$Data['Missions_Jour']."</td><td>".$Data['Credits']."</td><td>".$Data['Credits_date']."</td><td>de ".$Data['Dispo_Debut']."h à ".$Data['Dispo_Fin']."h</td><tr>";
						//Horaires
					if($Data['Dispo_Jour'] =="tous")
					{
						if($Data['Dispo_Sauf'] !="lu")
							$Lundi.=$Pilote;
						if($Data['Dispo_Sauf'] !="ma")
							$Mardi.=$Pilote;
						if($Data['Dispo_Sauf'] !="me")
							$Mercredi.=$Pilote;
						if($Data['Dispo_Sauf'] !="je")
							$Jeudi.=$Pilote;
						if($Data['Dispo_Sauf'] !="ve")
							$Vendredi.=$Pilote;
						if($Data['Dispo_Sauf'] !="sa")
							$Samedi.=$Pilote;
						if($Data['Dispo_Sauf'] !="di")
							$Dimanche.=$Pilote;
					}
					else
					{
						if($Data['Dispo_Jour'] =="we")
						{
							if($Data['Dispo_Sauf'] !="sa")
								$Samedi.=$Pilote;
							if($Data['Dispo_Sauf'] !="di")
								$Dimanche.=$Pilote;
						}
						elseif($Data['Dispo_Jour'] =="sem")
						{
							if($Data['Dispo_Sauf'] !="lu")
								$Lundi.=$Pilote;
							if($Data['Dispo_Sauf'] !="ma")
								$Mardi.=$Pilote;
							if($Data['Dispo_Sauf'] !="me")
								$Mercredi.=$Pilote;
							if($Data['Dispo_Sauf'] !="je")
								$Jeudi.=$Pilote;
							if($Data['Dispo_Sauf'] !="ve")
								$Vendredi.=$Pilote;
						}
						elseif($Data['Dispo_Jour'] =="lu")
							$Lundi.=$Pilote;
						elseif($Data['Dispo_Jour'] =="ma")
							$Mardi.=$Pilote;
						elseif($Data['Dispo_Jour'] =="me")
							$Mercredi.=$Pilote;
						elseif($Data['Dispo_Jour'] =="je")
							$Jeudi.=$Pilote;
						elseif($Data['Dispo_Jour'] =="ve")
							$Vendredi.=$Pilote;
						elseif($Data['Dispo_Jour'] =="sa")
							$Samedi.=$Pilote;
						elseif($Data['Dispo_Jour'] =="di")
							$Dimanche.=$Pilote;
					}
				}
			}
			echo "<h2>Calendrier</h2><div style='overflow:auto; height: 640px;'>
			<table class='table table-hover'><thead><tr><th>Nom</th><th>Grade</th><th>Unité</th><th>Statut</th><th title='Missions tentées aujourdhui'>Sorties</th><th>Credits</th><th title='Disponibilité GHQ (ne concerne que les officiers)'>Dispo EM</th><th>Activité</th><th>Horaires</th></tr></thead>";
			echo "<tr><th colspan='14' bgcolor='lightyellow'>Lundi</th></tr>".$Lundi;
			echo "<tr><th colspan='14' bgcolor='lightyellow'>Mardi</th></tr>".$Mardi;
			echo "<tr><th colspan='14' bgcolor='lightyellow'>Mercredi</th></tr>".$Mercredi;
			echo "<tr><th colspan='14' bgcolor='lightyellow'>Jeudi</th></tr>".$Jeudi;
			echo "<tr><th colspan='14' bgcolor='lightyellow'>Vendredi</th></tr>".$Vendredi;
			echo "<tr><th colspan='14' bgcolor='lightyellow'>Samedi</th></tr>".$Samedi;
			echo "<tr><th colspan='14' bgcolor='lightyellow'>Dimanche</th></tr>".$Dimanche;
			echo "</table>";
		}
		else
			echo "<h6>Désolé, votre nation ne compte pas de pilote actif</h6>";
	}
	else
	{
		echo"<table class='table'>
			<tr><td><img src='images/top_secret.gif'></td></tr>
			<tr><td>Ces données sont classifiées.</td> </tr>
			<tr><td>Votre rang ne vous permet pas d'accéder à ces informations.</td></tr>
		</table>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>