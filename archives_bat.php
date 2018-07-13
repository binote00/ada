<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
//Check Joueur Valide
if(isset($_SESSION['AccountID']))
{			
	$PlayerID = $_SESSION['PlayerID'];
	if($PlayerID > 0)
	{
		$ID = Insec($_POST['id']);
		$Date = date(Insec($_POST['date']));
		$Dateref = date('Y-m-d');
		$country = $_SESSION['country'];
		$Lieu_Target = GetData("Lieu","ID",$ID,"Nom");
		$Occupant = GetData("Lieu","ID",$ID,"Occupant");
		$war = IsWar($Occupant, $country);		
		/*Campagne
		if(strlen($Datediff)>1)
		{
			$Dateref = $Datediff;
			$DateDebut = GetData("Conf_Update","ID",2,"Date");
			$DateFin = GetData("Conf_Update","ID",1,"Date");
			$con = dbconnecti();
			$Datediff = mysqli_result(mysqli_query($con, "SELECT DATEDIFF('$DateDebut', '$DateFin')"),0);
			mysqli_close($con);
		}*/
	?>
	<div id="tab_menu">
	<h2>Détail de l'attaque en la date du <?echo $Date;?> sur <?echo $Lieu_Target;?></h2>
	<ol>
	<li><a href="#tab_resume" class='lien'>Récapitulatif</a></li>
	<li><a href="#tab_recce" class='lien'>La Reconnaissance</a></li>
	<li><a href="#tab_escortes" class='lien'>Les Escortes</a></li>
	<li><a href="#tab_bombs" class='lien'>Le Bombardement</a></li>
	<li><a href="#tab_attaques" class='lien'>L'Attaque au sol</a></li>
	<li><a href="#tab_patrouilles" class='lien'>Les Patrouilles</a></li>
	<li><a href="#tab_dca" class='lien'>La DCA</a></li>
	<li><a href="#tab_chasse" class='lien'>La Chasse</a></li>
	</ol>
	</div>
	<?			
		$con = dbconnecti();
		$Date = mysqli_real_escape_string($con, $Date);
		$DCA = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM DCA WHERE Lieu='$ID' AND DATE(Date)='$Date'"),0);
		$Recce = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Recce WHERE DATE(Date)='$Date' AND Lieu='$ID'"),0);
		if($war)
			$Escorte = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Escorte WHERE Lieu='$ID' AND DATE(Date)='$Date'"),0);
		else
			$Escorte = "?";
		if($Occupant == $country)
			$Patrouille = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Patrouille WHERE Lieu='$ID' AND DATE(Date)='$Date'"),0);
		else
			$Patrouille = "?";
		$Attaque = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Attaque WHERE Lieu='$ID' AND DATE(Date)='$Date'"),0);
		$Bombardement = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Bombardement WHERE Lieu='$ID' AND DATE(Date)='$Date'"),0);
		$Intercept = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Intercept WHERE Lieu='$ID' AND DATE(Date)='$Date'"),0);
		$Chasse = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Chasse WHERE Lieu='$ID' AND DATE(Date)='$Date'"),0);
		mysqli_close($con);
	?>
	<div id="tab_resume" align="center">
	<table class='table'>
	<thead><tr><th colspan="6">Récapitulatif</th></tr></thead>
	<tr><td>Reconnaissance :</td><td> <?echo $Recce;?></td><td>Interceptions :</td><td> <?echo $Intercept;?></td></tr>
	<tr><td>Escortes :</td><td> <?echo $Escorte;?></td><td>Patrouilles :</td><td> <?echo $Patrouille;?></td></tr>
	<tr><td>Cibles détruites :</td><td> <?echo $Attaque;?></td><td>Avions abattus par la DCA :</td><td> <?echo $DCA;?></td></tr>
	<tr><td>Cibles bombardées :</td><td> <?echo $Bombardement;?></td><td>Avions abattus par la chasse :</td><td> <?echo $Chasse;?></td></tr>
	</table></div>
	<?if($Recce){?>
	<div id="tab_recce"><h2>Reconnaissance</h2>
	<table class='table table-striped'>
		<thead><tr>
			<th>Date</th>
			<th>Unité</th>
			<th>Avion</th>
			<th>Pilote</th></tr></thead>
			<?			
			$con = dbconnecti();
			$query2 = mysqli_query($con, "SELECT DISTINCT * FROM Recce WHERE DATE(`Date`)='$Date' AND Lieu='$ID' ORDER BY ID DESC, Joueur ASC");
			mysqli_close($con);
			if($query2)
			{
				while($data2 = mysqli_fetch_array($query2, MYSQLI_ASSOC))
				{
					$Date = substr($data2['Date'],0,16);
					$Joueur_win = GetData("Pilote","ID",$data2['Joueur'],"Nom");
					$Avion_win = GetData("Avion","ID",$data2['Avion'],"Nom");
					$Unite_win = GetData("Unit","ID",$data2['Unite'],"Nom");									
					$Avion_img = "images/avions/avion".$data2['Avion'].".gif";
					$Avion_unit_img = "images/unit/unit".$data2['Unite']."p.gif";
					if(is_file($Avion_img))
						$Avion_win = "<img src='".$Avion_img."' title='".$Avion_win."'>";
					if(is_file($Avion_unit_img))
						$Unite_win = "<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
					?>
					<tr>
						<td><? echo $Date;?></td>
						<td><? echo $Unite_win;?></td>
						<td><? echo $Avion_win;?></td>
						<td><? if($Renseignement > 200){echo $Joueur_win;}else{echo "Inconnu";}?></td>
					</tr>
					<?
				}
				mysqli_free_result($query2);
			}
			else
				echo "<b>Désolé, aucune escorte enregistrée à ce jour.</b>";
	?>
	</table></div>
	<?}if($Bombardement){?>
	<div id="tab_bombs"><h2>Bombardements</h2>
	<table class='table table-striped'>
		<thead><tr>
			<th>Date</th>
			<th>Unité</th>
			<th>Avion</th>
			<th>Pilote</th>
			<th>Cible détruite</th></tr></thead>
			<?
			$con = dbconnecti();
			$query2 = mysqli_query($con, "SELECT DISTINCT * FROM Bombardement WHERE Lieu = '$ID' AND DATE(`Date`)='$Date' ORDER BY ID DESC, Joueur ASC");
			mysqli_close($con);
			if($query2)
			{
				while($data2=mysqli_fetch_assoc($query2))
				{
					$Date = substr($data2['Date'],0,16);
					$Cible_detruite = ucfirst($data2['Nom']);
					$Joueur_win = GetData("Pilote","ID",$data2['Joueur'],"Nom");
					$Avion_win = GetData("Avion","ID",$data2['Avion'],"Nom");
					$Unite_win = GetData("Unit","ID",$data2['Unite'],"Nom");
					$Cible_img = "images/vehicules/vehicule".$data2['Cible_id'].".gif";
					$Avion_img = "images/avions/avion".$data2['Avion'].".gif";
					$Avion_unit_img = "images/unit/unit".$data2['Unite']."p.gif";
					if(is_file($Cible_img))
						$Cible_detruite = "<img src='".$Cible_img."' title='".$Cible_detruite."'>";
					if(is_file($Avion_img))
						$Avion_win = "<img src='".$Avion_img."' title='".$Avion_win."'>";
					if(is_file($Avion_unit_img))
						$Unite_win = "<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
					?>
					<tr>
						<td><? echo $Date;?></td>
						<td><? echo $Unite_win;?></td>
						<td><? echo $Avion_win;?></td>
						<td><? if($Renseignement > 200){echo $Joueur_win;}else{echo "Inconnu";}?></td>
						<td><? echo $Cible_detruite;?></td>
					</tr>
					<?
				}
				mysqli_free_result($query2);
			}
			else
				echo "<b>Désolé, aucun bombardement enregistrée à ce jour.</b>";
	?>
	</table></div>
	<?}if($Escorte > 0 and $Escorte != "?"){?>
	<div id="tab_escortes"><h2>Escortes</h2>
	<table class='table table-striped'>
		<thead><tr>
			<th>Date</th>
			<th>Unité</th>
			<th>Avion</th>
			<th>Pilote</th></tr></thead>
			<?			
			$con = dbconnecti();
			$query2 = mysqli_query($con, "SELECT * FROM Escorte WHERE Lieu='$ID' AND DATE(`Date`)='$Date'  ORDER BY ID DESC, Joueur ASC");
			mysqli_close($con);
			if($query2)
			{
				while($data2=mysqli_fetch_array($query2))
				{
					$Pays_win = GetData("Pilote","ID",$data2['Joueur'],"Pays");
					if($Pays_win == $country)
					{
						$Date = substr($data2['Date'],0,16);
						$Joueur_win = GetData("Pilote","ID",$data2['Joueur'],"Nom");
						$Avion_win = GetData("Avion","ID",$data2['Avion'],"Nom");
						$Unite_win = GetData("Unit","ID",$data2['Unite'],"Nom");										
						$Avion_img = "images/avions/avion".$data2['Avion'].".gif";
						$Avion_unit_img = "images/unit/unit".$data2['Unite']."p.gif";
						if(is_file($Avion_img))
							$Avion_win = "<img src='".$Avion_img."' title='".$Avion_win."'>";
						if(is_file($Avion_unit_img))
							$Unite_win = "<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
						?>
						<tr>
							<td><? echo $Date;?></td>
							<td><? echo $Unite_win;?></td>
							<td><? echo $Avion_win;?></td>
							<td><? echo $Joueur_win;?></td>
						</tr>
						<?
					}
				}
				mysqli_free_result($query2);
			}
			else
				echo "<b>Désolé, aucune escorte enregistrée à ce jour.</b>";
	?>
	</table></div>
	<?}if($Attaque){?>
	<div id="tab_attaques"><h2>Attaques au sol</h2>
	<table class='table table-striped'>
		<thead><tr>
			<th>Date</th>
			<th>Unité</th>
			<th>Avion</th>
			<th>Pilote</th>
			<th>Cible détruite</th></tr></thead>
			<?			
			$con = dbconnecti();
			$query1 = mysqli_query($con, "SELECT * FROM Attaque WHERE Lieu = '$ID' AND Type > 0 AND DATE(`Date`)='$Date' ORDER BY ID DESC, Joueur ASC");
			mysqli_close($con);
			if($query1)
			{
				while($data1=mysqli_fetch_assoc($query1))
				{
					$Date = substr($data1['Date'],0,16);
					$Cible_detruite = ucfirst($data1['Nom']);
					$Joueur_win = GetData("Pilote","ID",$data1['Joueur'],"Nom");
					$Avion_win = GetData("Avion","ID",$data1['Avion'],"Nom");
					$Unite_win = GetData("Unit","ID",$data1['Unite'],"Nom");
					$Cible_img = 'images/vehicules/vehicule'.$data1['Cible_id'].'.gif';
					$Avion_img = "images/avions/avion".$data1['Avion'].".gif";
					$Avion_unit_img = "images/unit/unit".$data1['Unite']."p.gif";
					if(is_file($Cible_img))
						$Cible_detruite = "<img src='".$Cible_img."' title='".$Cible_detruite."'>";
					if(is_file($Avion_img))
						$Avion_win = "<img src='".$Avion_img."' title='".$Avion_win."'>";
					if(is_file($Avion_unit_img))
						$Unite_win = "<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
					?>
					<tr>
						<td><? echo $Date;?></td>
						<td><? echo $Unite_win;?></td>
						<td><? echo $Avion_win;?></td>
						<td><? if($Renseignement > 200){echo $Joueur_win;}else{echo "Inconnu";}?></td>
						<td><? echo $Cible_detruite;?></td>
					</tr>
					<?
				}
				mysqli_free_result($query1);
			}
			else
				echo "<b>Désolé, aucune attaque enregistrée à ce jour.</b>";
	?>
	</table></div>
	<?}if($Patrouille > 0 and $Patrouille != "?" and $Datediff){?>
	<div id="tab_patrouilles"><h2>Patrouilles</h2>
	<table class='table table-striped'>
		<thead><tr>
			<th>Date</th>
			<th>Unité</th>
			<th>Avion</th>
			<th>Pilote</th></tr></thead>
			<?			
			$con = dbconnecti();
			$query2 = mysqli_query($con, "SELECT * FROM Patrouille WHERE Lieu='$ID' AND DATE(`Date`)='$Date'  ORDER BY ID DESC, Joueur ASC");
			mysqli_close($con);
			if($query2)
			{
				while($data2=mysqli_fetch_array($query2))
				{
					$Pays_win = GetData("Pilote","ID",$data2['Joueur'],"Pays");
					if($Pays_win == $country)
					{
						$Date = substr($data2['Date'],0,16);
						$Joueur_win = GetData("Pilote","ID",$data2['Joueur'],"Nom");
						$Avion_win = GetData("Avion","ID",$data2['Avion'],"Nom");
						$Unite_win = GetData("Unit","ID",$data2['Unite'],"Nom");										
						$Avion_img = "images/avions/avion".$data2['Avion'].".gif";
						$Avion_unit_img = "images/unit/unit".$data2['Unite']."p.gif";
						if(is_file($Avion_img))
							$Avion_win = "<img src='".$Avion_img."' title='".$Avion_win."'>";
						if(is_file($Avion_unit_img))
							$Unite_win = "<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
						?>
						<tr>
							<td><? echo $Date;?></td>
							<td><? echo $Unite_win;?></td>
							<td><? echo $Avion_win;?></td>
							<td><? echo $Joueur_win;?></td>
						</tr>
						<?
					}
				}
				mysqli_free_result($query2);
			}
			else
				echo "<b>Désolé, aucune patrouille enregistrée à ce jour.</b>";
	?>
	</table></div>
	<?}if($DCA){?>
	<div id="tab_dca"><h2>D.C.A</h2>
	<table class='table table-striped'>
		<thead><tr>
			<th>Date</th>
			<th>Unité</th>
			<th>Avion</th>
			<th>Pilote</th>
			<th>Canon</th></tr></thead>
			<?
			$con = dbconnecti();
			$query1 = mysqli_query($con, "SELECT DISTINCT * FROM DCA WHERE Lieu = '$ID' AND DATE(`Date`)='$Date' ORDER BY ID DESC, Joueur ASC");
			mysqli_close($con);
			if($query1)
			{
				while($data1=mysqli_fetch_assoc($query1))
				{
					$Date = substr($data1['Date'],0,16);
					$Joueur_win = GetData("Pilote","ID",$data1['Joueur'],"Nom");
					$Avion_win = GetData("Avion","ID",$data1['Avion'],"Nom");
					$Unite_win = GetData("Unit","ID",$data1['Unite'],"Nom");
					$Arme = $data1['Arme'];
					$Canon = GetData("Armes","ID",$data1['Arme'],"Nom");					
					$Avion_img = "images/avions/avion".$data1['Avion'].".gif";
					$Avion_unit_img = "images/unit/unit".$data1['Unite']."p.gif";
					if(is_file($Avion_img))
						$Avion_win = "<img src='".$Avion_img."' title='".$Avion_win."'>";
					if(is_file($Avion_unit_img))
						$Unite_win = "<img src='".$Avion_unit_img."' title='".$Unite_win."'>";
					?>
					<tr>
						<td><? echo $Date;?></td>
						<td><? echo $Unite_win;?></td>
						<td><? echo $Avion_win;?></td>
						<td><? if($country == $data1['Pays'] or $Renseignement > 200){echo $Joueur_win;}else{echo "Inconnu";}?></td>
						<td><? echo $Canon;?></td>
					</tr>
					<?
				}
				mysqli_free_result($query1);
			}
			else
				echo "<b>Désolé, aucun avion abattu par la DCA enregistrée à ce jour.</b>";
	?>
	</table></div>
	<?}if($Chasse){?>
	<div id="tab_chasse"><h2>Chasse</h2>
	<table class='table table-striped'>
		<thead><tr>
			<th>Date</th>
			<th>Lieu</th>
			<th>Pays</th>
			<th>Unité</th>
			<th>Pilote crédité</th>
			<th>Avion</th>
			<th>Avion Abattu</th>
			<th>Pilote Abattu</th>
			<th>Unité</th>
			<th>Pays</th>
			</tr></thead>
			<?
			$con = dbconnecti();
			$query2 = mysqli_query($con, "SELECT DISTINCT * FROM Chasse WHERE Lieu = '$ID' AND DATE(`Date`)='$Date' ORDER BY ID DESC");
			mysqli_close($con);
			if($query2)
			{
				while($data2=mysqli_fetch_assoc($query2))
				{
					$Date = substr($data2['Date'],0,16);
					$Avion_win = GetData("Avion","ID",$data2['Avion_win'],"Nom");
					$Unite_win = GetData("Unit","ID",$data2['Unite_win'],"Nom");
					$Pays_win = GetData("Unit","ID",$data2['Unite_win'],"Pays");
					$Unite_loss = GetData("Unit","ID",$data2['Unite_loss'],"Nom");
					$Pays_loss = GetData("Unit","ID",$data2['Unite_loss'],"Pays");
					$Lieu = GetData("Lieu","ID",$data2['Lieu'],"Nom");
					if(!$Lieu)$Lieu="Inconnu";
					$as_win = false;
					$as_loss = false;
					$Avion_img_win = false;
					$Avion_img_loss = false;
					if($data2['PVP'] == 1)
					{
						if($Pays_loss == $country or $Renseignement > 200)
							$Pilote_loss = "<b>".GetData("Pilote","ID",$data2['Pilote_loss'],"Nom")."</b>";
						else
							$Pilote_loss = "Inconnu";
						$Pilote_win = "Inconnu";
						$Avion_loss = GetData("Avion","ID",$data2['Avion_loss'],"Nom");
						
						if($data2['Joueur_win'] != 4 and $data2['Joueur_win'] != 147 and $data2['Joueur_win'] != 148 and $data2['Joueur_win'] != 149 and $data2['Joueur_win'] != 150 and $data2['Joueur_win'] != 460)
						{
							$Pilote_win = GetData("Pilote_IA","ID",$data2['Joueur_win'],"Nom");
							$Avion_img_win = "images/avions/avion".$data2['Avion_win']."_as".$data2['Joueur_win'].".gif";
							$as_win = true;
						}
					}
					elseif($data2['PVP'] > 1)
					{
						$Pilote_win = "<b>".GetData("Pilote","ID",$data2['Joueur_win'],"Nom")."</b>";
						$Pilote_loss = "<b>".GetData("Pilote","ID",$data2['Pilote_loss'],"Nom")."</b>";
						$Avion_loss = GetData("Avion","ID",$data2['Avion_loss'],"Nom");
						
						if($data2['Pilote_loss'] != 4 and $data2['Pilote_loss'] != 147 and $data2['Pilote_loss'] != 148 and $data2['Pilote_loss'] != 149 and $data2['Pilote_loss'] != 150 and $data2['Joueur_win'] != 460)
						{
							$Avion_img_loss = "images/avions/avion".$data2['Avion_loss']."_as".$data2['Pilote_loss'].".gif";
							$as_loss = true;
						}
					}
					else
					{
						if($Pays_win == $country or $Renseignement > 200)
							$Pilote_win = "<b>".GetData("Pilote","ID",$data2['Joueur_win'],"Nom")."</b>";
						else
							$Pilote_win = "Inconnu";
						$Pilote_loss = "Inconnu";
						$Avion_loss = GetData("Avion","ID",$data2['Avion_loss'],"Nom");
						
						if($data2['Pilote_loss'] != 4 and $data2['Pilote_loss'] != 147 and $data2['Pilote_loss'] != 148 and $data2['Pilote_loss'] != 149 and $data2['Pilote_loss'] != 150 and $data2['Joueur_win'] != 460)
						{
							$Pilote_loss = GetData("Pilote_IA","ID",$data2['Pilote_loss'],"Nom");
							$Avion_img_loss = "images/avions/avion".$data2['Avion_loss']."_as".$data2['Pilote_loss'].".gif";
							$as_loss = true;
						}
					}
					if(!is_file($Avion_img_win))
						$Avion_img_win = "images/avions/avion".$data2['Avion_win'].".gif";
					if(!is_file($Avion_img_loss))
						$Avion_img_loss = "images/avions/avion".$data2['Avion_loss'].".gif";
					if(!$as_win and !$as_loss)
					{
						$Avion_img_loss = "images/avions/avion".$data2['Avion_loss'].".gif";
						$Avion_img_win = "images/avions/avion".$data2['Avion_win'].".gif";
					}
					$Avion_unit_win_img = "images/unit/unit".$data2['Unite_win']."p.gif";
					$Avion_unit_loss_img = "images/unit/unit".$data2['Unite_loss']."p.gif";
					if(is_file($Avion_img_win))
						$Avion_win = "<img src='".$Avion_img_win."' title='".$Avion_win."'>";
					if(is_file($Avion_img_loss))
						$Avion_loss = "<img src='".$Avion_img_loss."' title='".$Avion_loss."'>";
					if(is_file($Avion_unit_win_img))
						$Unite_win = "<img src='".$Avion_unit_win_img."' title='".$Unite_win."'>";
					if(is_file($Avion_unit_loss_img))
						$Unite_loss = "<img src='".$Avion_unit_loss_img."' title='".$Unite_loss."'>";
					?>
					<tr>
						<td><? echo $Date;?></td>
						<td><? echo $Lieu;?></td>
						<td><img src='<? echo $Pays_win;?>20.gif'></td>
						<td><? echo $Unite_win;?></td>
						<td><? echo $Pilote_win;?></td>
						<td><? echo $Avion_win;?></td>
						<td><? echo $Avion_loss;?></td>
						<td><? echo $Pilote_loss;?></td>
						<td><? echo $Unite_loss;?></td>
						<td><img src='<? echo $Pays_loss;?>20.gif'></td>
					</tr>
					<?
				}
				mysqli_free_result($query2);
			}
		}
		echo "</table></div><div id='tab_ground'><h2>Pertes Terrestre</h2>";
		$Veh_Axe = "<table class='table'>";
		$Veh_Allie = "<table class='table'>";
		$con = dbconnecti(4);
		$Veh_Atk = mysqli_query($con, "SELECT COUNT(Avion_Nbr),Unit,Avion,Date FROM Events_Ground WHERE Event_Type IN (400,401,405,420,605) AND Lieu='$ID' AND DATE(Date)='$Date' GROUP BY Avion");
		//$Veh_Bomb = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Events_Ground WHERE Lieu='$ID' AND Event_Type IN (405) AND DATE(Date)='$Date'"),0);
		//$Veh_Mines = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Events_Ground WHERE Lieu='$ID' AND Event_Type IN (420) AND DATE(Date)='$Date'"),0);
		mysqli_close($con);
		if($Veh_Atk)
		{
			while($data_veh = mysqli_fetch_array($Veh_Atk))
			{
				$Date = substr($data_veh['Date'],0,11);
				$Pays_veh = GetData("Regiment","ID",$data_veh['Unit'],"Pays");
				if($Pays_veh == 1)
					$Veh_Axe .= '<tr><td><img src=\''.$Pays_veh.'20.gif\'></td><td>'.$data_veh[0].' '.GetVehiculeIcon($data_veh['Avion'], $Pays_veh, 0, 0, $Front).'</td></tr>';
				elseif($Pays_veh == 4)
					$Veh_France .= '<tr><td><img src=\''.$Pays_veh.'20.gif\'></td><td>'.$data_veh[0].' '.GetVehiculeIcon($data_veh['Avion'], $Pays_veh, 0, 0, $Front).'</td></tr>';
				elseif($Pays_veh == 6)
					$Veh_Ita .= '<tr><td><img src=\''.$Pays_veh.'20.gif\'></td><td>'.$data_veh[0].' '.GetVehiculeIcon($data_veh['Avion'], $Pays_veh, 0, 0, $Front).'</td></tr>';
				else
					$Veh_Allie .= '<tr><td><img src=\''.$Pays_veh.'20.gif\'></td><td>'.$data_veh[0].' '.GetVehiculeIcon($data_veh['Avion'], $Pays_veh, 0, 0, $Front).'</td></tr>';
			}
			mysqli_free_result($Veh_Atk);
		}
		echo "<tr><td>".$Veh_Axe.$Veh_France.$Veh_Ita."</table></td><td>".$Veh_Allie."</table></div>";
	}
	else
	{
		echo "<table class='table'>
			<tr><td><img src='images/top_secret.gif'></td></tr>
			<tr><td>Ces données sont classifiées.</td></tr>
			<tr><td>Votre rang ne vous permet pas d'accéder à ces informations.</td></tr>
		</table>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>