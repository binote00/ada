<?require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 or $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_access.php');
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
	if($Renseignement >100 or $Admin)
		$query="SELECT c.Date,c.Joueur_win,c.Pilote_loss,c.Unite_win,c.Unite_loss,c.Avion_win,c.Avion_loss,c.Lieu,c.Cycle,c.Altitude,c.PVP,c.Latitude,c.Longitude,u.Nom,u.Pays 
		FROM Chasse as c,Unit as u WHERE c.PVP<>3 AND c.Unite_win=u.ID ORDER BY c.ID DESC LIMIT 50";
	else
		$query="SELECT c.Date,c.Joueur_win,c.Pilote_loss,c.Unite_win,c.Unite_loss,c.Avion_win,c.Avion_loss,c.Lieu,c.Cycle,c.Altitude,c.PVP,c.Latitude,c.Longitude,u.Nom,u.Pays 
		FROM Chasse as c,Unit as u WHERE c.PVP<>3 AND c.Unite_win=u.ID AND (u.Pays='$country' OR c.Unite_loss='$Unite') ORDER BY c.ID DESC LIMIT 50";
	$con=dbconnecti();
	$query2=mysqli_query($con,$query);
	//mysqli_close($con);
	if($query2)
	{
		while($data2=mysqli_fetch_assoc($query2))
		{
			$Front_Lieu=GetFrontByCoord(0,$data2['Latitude'],$data2['Longitude']);
			if($Admin or $Front ==$Front_Lieu)
			{
				$Date=substr($data2['Date'],0,16);
				$Unite_win=$data2['Nom'];
				$Pays_win=$data2['Pays'];
				$Unite_loss=$data2['Unite_loss'];
				$Avion_win=$data2['Avion_win'];		
				//$con=dbconnecti();
				$query3=mysqli_query($con,"SELECT Pays,Nom FROM Unit WHERE ID='$Unite_loss' ORDER BY ID DESC LIMIT 50");
				$Avion_win=mysqli_result(mysqli_query($con,"SELECT Nom FROM Avion WHERE ID='$Avion_win' ORDER BY ID DESC LIMIT 50"),0);
				//mysqli_close($con);
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
				if($data2['PVP'] ==4)
				{
					if($Pays_loss ==$country or $Renseignement >200 or $Admin)
						$Pilote_loss=GetData("Pilote_IA","ID",$data2['Pilote_loss'],"Nom");
					else
						$Pilote_loss="Inconnu";	
					$Pilote_win="<b>".GetData("Pilote","ID",$data2['Joueur_win'],"Nom")."</b>";
					$Avion_win=GetAvionIcon($data2['Avion_win'],$Pays_win,0,$data2['Unite_win'],$Front_Lieu);
					$Avion_loss=GetAvionIcon($data2['Avion_loss'],$Pays_loss,$data2['Pilote_loss'],$data2['Unite_loss'],$Front_Lieu);
				}
				elseif($data2['PVP'] ==1)
				{
					if($Pays_loss ==$country or $Renseignement >200 or $Admin)
						$Pilote_loss="<b>".GetData("Pilote","ID",$data2['Pilote_loss'],"Nom")."</b>";
					else
						$Pilote_loss="Inconnu";			
					$Pilote_win=GetData("Pilote_IA","ID",$data2['Joueur_win'],"Nom");
					if(!$Pilote_win)
						$Pilote_win="Pilote IA";
					$Avion_win=GetAvionIcon($data2['Avion_win'],$Pays_win,0,$data2['Unite_win'],$Front_Lieu);
					$Avion_loss=GetAvionIcon($data2['Avion_loss'],$Pays_loss,$data2['Pilote_loss'],$data2['Unite_loss'],$Front_Lieu);
				}
				elseif($data2['PVP'] ==2 or $data2['PVP'] ==3)
				{
					$Pilote_win="<b>".GetData("Pilote","ID",$data2['Joueur_win'],"Nom")."</b>";
					$Pilote_loss="<b>".GetData("Pilote","ID",$data2['Pilote_loss'],"Nom")."</b>";
					$Avion_win=GetAvionIcon($data2['Avion_win'],$Pays_win,$data2['Joueur_win'],$data2['Unite_win'],$Front_Lieu);
					$Avion_loss=GetAvionIcon($data2['Avion_loss'],$Pays_loss,$data2['Pilote_loss'],$data2['Unite_loss'],$Front_Lieu);
				}
				else
				{
					if($Pays_win ==$country or $Renseignement >200 or $Admin)
						$Pilote_win="<b>".GetData("Pilote","ID",$data2['Joueur_win'],"Nom")."</b>";
					else
						$Pilote_win="Inconnu";			
					$Pilote_loss=GetData("Pilote_IA","ID",$data2['Pilote_loss'],"Nom");
					if(!$Pilote_loss)
						$Pilote_loss="Pilote IA";
					$Avion_win=GetAvionIcon($data2['Avion_win'],$Pays_win,$data2['Joueur_win'],$data2['Unite_win'],$Front_Lieu);
					$Avion_loss=GetAvionIcon($data2['Avion_loss'],$Pays_loss,0,$data2['Unite_loss'],$Front_Lieu);
				}			
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
				if($data2['Cycle'])
					$Cycle_txt="Nuit";
				else
					$Cycle_txt="Jour";
				$liste.="<tr><td>".$Date."</td><td><img src='images/meteo".$data2['Cycle'].".gif' title='".$Cycle_txt."'></td><td>".$Lieu."</td>
					<td><img src='".$Pays_win."20.gif'></td><td>".$Unite_win_txt."</td><td>".$Pilote_win."</td><td>".$Avion_win."</td>
					<td>".$Avion_loss."</td><td>".$Pilote_loss."</td><td>".$Unite_loss_txt."</td><td><img src='".$Pays_loss."20.gif'></td>
					<td>".$data2['Altitude']."m</td></tr>";
			}
		}
		mysqli_free_result($query2);
	}
	echo "<h1>Tableau de Chasse</h1>
	<p class='lead'>Ce tableau ne recense que les victoires confirmées impliquant des pilotes joueurs. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>
	<a href='index.php?view=output_chasse_ia' class='btn btn-primary'>Combats aériens</a>
	<a href='index.php?view=output_probable' class='btn btn-primary'>Victoires probables</a>
	<div style='overflow:auto; width: 100%;'><table class='table table-striped table-condensed'>
	<thead><tr><th>Date</th><th>Cycle</th><th>Lieu</th><th>Pays</th><th>Unité</th><th>Pilote crédité</th><th>Avion</th>
	<th>Avion Abattu</th><th>Pilote Abattu</th><th>Unité</th><th>Pays</th><th>Altitude</th></thead>".$liste."</table></div>";
}?>