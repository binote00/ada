<?require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 or $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_access.php');
	if($Premium)
	{
		$country=$_SESSION['country'];
		if($PlayerID >0)
		{
			$con=dbconnecti();
			$query=mysqli_query($con,"SELECT Front,Renseignement,Unit FROM Pilote WHERE ID='$PlayerID'");
			mysqli_close($con);
			if($query)
			{
				while($data=mysqli_fetch_assoc($query))
				{
					$Renseignement=$data['Renseignement'];
					$Unite=$data['Unit'];
					$Front=$data['Front'];
				}
				mysqli_free_result($query);
			}
			$Base=GetData("Unit","ID",$Unite,"Base");
		}
		elseif($OfficierEMID >0)
		{
			$Renseignement=101;
			$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
		}
		if($Admin)
			$query="SELECT c.Date,c.Joueur_win,c.Pilote_loss,c.Unite_win,c.Unite_loss,c.Avion_win,c.Avion_loss,c.Lieu,c.PVP,u.Nom,u.Pays,l.Latitude,l.Longitude
			FROM Chasse_Probable as c,Unit as u,Lieu as l WHERE c.Pilote_loss >0 AND c.Unite_win=u.ID AND l.ID=c.Lieu ORDER BY c.ID DESC LIMIT 50";
		else
			$query="SELECT c.Date,c.Joueur_win,c.Pilote_loss,c.Unite_win,c.Unite_loss,c.Avion_win,c.Avion_loss,c.Lieu,c.PVP,u.Nom,u.Pays,l.Latitude,l.Longitude
			FROM Chasse_Probable as c,Unit as u,Lieu as l WHERE c.Pilote_loss >0 AND c.Unite_win=u.ID AND l.ID=c.Lieu AND (u.Pays='$country' OR c.Unite_loss='$Unite') ORDER BY c.ID DESC LIMIT 50";
		$con=dbconnecti();
		$query2=mysqli_query($con,$query);
		mysqli_close($con);
		if($query2)
		{
			while($data2=mysqli_fetch_assoc($query2))
			{
				$Front_Lieu=GetFrontByCoord(0,$data2['Latitude'],$data2['Longitude']);
				if($Admin or $Front ==$Front_Lieu or $data2['Pays'] ==$country)
				{
					$Date=substr($data2['Date'],0,16);
					$Unite_win=$data2['Nom'];
					$Pays_win=$data2['Pays'];
					$Unite_loss=$data2['Unite_loss'];
					$Avion_win=$data2['Avion_win'];		
					$con=dbconnecti();
					$query3=mysqli_query($con,"SELECT Pays,Nom FROM Unit WHERE ID='$Unite_loss' ORDER BY ID DESC LIMIT 50");
					$Avion_win=mysqli_result(mysqli_query($con,"SELECT Nom FROM Avion WHERE ID='$Avion_win' ORDER BY ID DESC LIMIT 50"),0);
					mysqli_close($con);
					if($query3)
					{
						while($data3=mysqli_fetch_assoc($query3))
						{
							$Unite_loss=$data3['Nom'];
							$Pays_loss=$data3['Pays'];
						}
						mysqli_free_result($query3);
					}
					if($Renseignement >50 or $data2['Lieu'] ==$Base or $data2['Unite_win'] ==$Unite or $data2['Unite_loss'] ==$Unite or $Admin)
						$Lieu=GetData("Lieu","ID",$data2['Lieu'],"Nom");
					else
						$Lieu="Inconnu";
					if($data2['PVP'] ==1) //Endommagé
						$Categorie="Endommagé";
					elseif($data2['PVP'] ==2)
						$Categorie="Collaboration";
					else
						$Categorie="Probable";
					$Avion_win=GetAvionIcon($data2['Avion_win'],$Pays_win,$data2['Joueur_win'],$data2['Unite_win'],$Front_Lieu);
					$Avion_loss=GetAvionIcon($data2['Avion_loss'],$Pays_loss,$data2['Pilote_loss'],$data2['Unite_loss'],$Front_Lieu);
					if($Pays_win ==$country or $Renseignement >200 or $Admin)
						$Pilote_win="<b>".GetData("Pilote","ID",$data2['Joueur_win'],"Nom")."</b>";
					else
						$Pilote_win="Inconnu";			
					if($Renseignement >200 or $Admin)
						$Pilote_loss=GetData("Pilote_IA","ID",$data2['Pilote_loss'],"Nom");
					else
						$Pilote_loss="Inconnu";
					if($Pays_win ==$country or $Renseignement >150 or $Admin >0)
					{
						$Avion_unit_win_img="images/unit/unit".$data2['Unite_win']."p.gif";
						if(is_file($Avion_unit_win_img))
							$Unite_win_txt="<img src='".$Avion_unit_win_img."' title='".$Unite_win."'>";
						else
							$Unite_win_txt=$Unite_win;
					}
					else
						$Unite_win_txt="Inconnue";		
					if($Pays_loss ==$country or $Renseignement >150 or $data2['Lieu'] ==$Base or $Admin >0)
					{
						$Avion_unit_loss_img="images/unit/unit".$data2['Unite_loss']."p.gif";
						if(is_file($Avion_unit_loss_img))
							$Unite_loss_txt="<img src='".$Avion_unit_loss_img."' title='".$Unite_loss."'>";
						else
							$Unite_loss_txt=$Unite_loss;
					}
					else
						$Unite_loss_txt="Inconnue";
					$liste.="<tr><td>".$Date."</td><td>".$Lieu."</td>
						<td><img src='".$Pays_win."20.gif'></td><td>".$Unite_win_txt."</td><td>".$Pilote_win."</td><td>".$Avion_win."</td>
						<td>".$Avion_loss."</td><td>".$Pilote_loss."</td><td>".$Unite_loss_txt."</td><td><img src='".$Pays_loss."20.gif'></td><td>".$Categorie."</td></tr>";
				}
			}
			mysqli_free_result($query2);
		}
		echo "<h1>Tableau de Chasse</h1>
		<p class='lead'>Ce tableau ne recense que les avions ennemis endommagés, abattus en collaboration ou les victoires probables. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>
		<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
		<thead><tr><th>Date</th><th>Lieu</th><th>Pays</th><th>Unité</th><th>Pilote</th><th>Avion</th>
		<th>Avion Probable</th><th>Pilote Probable</th><th>Unité</th><th>Pays</th><th>Type</th></thead>".$liste."</table></div>";
	}
	else
		echo "<table class='table'><tr><td><img src='images/acces_premium.png'></td></tr><tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr></table>";
}?>