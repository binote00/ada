<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country = $_SESSION['country'];
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $PlayerID >0 and $country)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Pilote.Unit,Pilote.Front,Unit.Nom,Unit.Type FROM Pilote,Unit WHERE Pilote.ID='$PlayerID' AND Pilote.Unit=Unit.ID");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite = $data['Unit'];
				$Front = $data['Front'];
				$Unite_Nom = $data['Nom'];
				$Unite_Type = $data['Type'];
			}
			mysqli_free_result($result);
			unset($data);
		}		
		$con=dbconnecti();
		$vic_tot = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE PVP<>1 AND Unite_win='$Unite'"),0);
		$def_tot = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE PVP=1 AND Unite_loss='$Unite'"),0);
		$atk_tot = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque WHERE Unite='$Unite'"),0);
		$bomb_tot = mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement WHERE Unite='$Unite'"),0);
		$Reput_Sqn = mysqli_result(mysqli_query($con,"SELECT Reputation FROM Unit WHERE ID='$Unite'"),0);
		mysqli_close($con);		
		$des_tot=$atk_tot+$bomb_tot;		
		include_once('./menu_escadrille.php');
		echo "<table class='table table-striped'>
					<thead><tr><th colspan='2'>Score</th></tr></thead>
					<tr><td>Réputation</td><td>".$Reput_Sqn."</td></tr>
					<tr><td>Total de victoires</td><td>".$vic_tot."</td></tr>
					<tr><td>Total de défaites</td><td>".$def_tot."</td></tr>
					<tr><td>Total de destructions</td><td>".$des_tot."</td></tr>
				</table>";						
		if($Unite_Type ==1 or $Unite_Type ==4 or $Unite_Type ==12)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT * FROM Chasse WHERE Unite_win='$Unite' OR Unite_loss='$Unite' AND PVP NOT IN (2,3) ORDER BY ID DESC LIMIT 100");
			mysqli_close($con);
			if($result)
			{
				echo "<h3>Tableau de Chasse</h3>
				<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
				<thead><tr>
				<th>Date</th>
				<th>Pilote crédité</th>
				<th>Avion</th>
				<th>Unité</th>
				<th>Pays</th>
				<th>Avion Abattu</th>
				<th>Pilote Abattu</th>
				<th>Unité</th>
				<th>Pays</th>
				<th>Lieu</th>
				<th>Altitude</th>
				<th>Cycle</th></tr></thead>
				";
				$num = mysqli_num_rows($result);
				if($num ==0)
					echo "<h6>Désolé, aucun avion abattu récemment</h6>";
				else
				{
					$i=0;
					while($i <$num) 
					{
						$Date=substr(mysqli_result($result,$i,"Date"),0,16);
						$Alt=mysqli_result($result,$i,"Altitude");
						$Cycle=mysqli_result($result,$i,"Cycle");
						$Unite_win=mysqli_result($result,$i,"Unite_win");
						$Unite_loss=mysqli_result($result,$i,"Unite_loss");
						$Joueur_win=mysqli_result($result,$i,"Joueur_win");
						$Joueur_loss=mysqli_result($result,$i,"Pilote_loss");
						$Avion_win=mysqli_result($result,$i,"Avion_win");
						$Avion_loss=mysqli_result($result,$i,"Avion_loss");
						$Pays_win=GetData("Unit","ID",$Unite_win,"Pays");
						$Pays_loss=GetData("Unit","ID",$Unite_loss,"Pays");
						$PVP=mysqli_result($result,$i,"PVP");
						if($PVP ==1)
						{
							$Pilote_win=GetData("Pilote_IA","ID",$Joueur_win,"Nom");
							$Pilote_loss=GetData("Pilote","ID",$Joueur_loss,"Nom");
							$Avion_win = GetAvionIcon($Avion_win,$Pays_win,0,$Unite_win);
							$Avion_loss = GetAvionIcon($Avion_loss,$Pays_loss,$Pilote_loss,$Unite_loss);
						}
						elseif($PVP >1)
						{
							$Pilote_win=GetData("Pilote","ID",$Joueur_win,"Nom");
							$Pilote_loss=GetData("Pilote","ID",$Joueur_loss,"Nom");
							$Avion_win = GetAvionIcon($Avion_win,$Pays_win,$Pilote_win,$Unite_win);
							$Avion_loss = GetAvionIcon($Avion_loss,$Pays_loss,$Pilote_loss,$Unite_loss);
						}
						else
						{
							$Pilote_win=GetData("Pilote","ID",$Joueur_win,"Nom");
							$Pilote_loss=GetData("Pilote_IA","ID",$Joueur_loss,"Nom");
							if(!$Pilote_loss)
								$Pilote_loss=GetData("Pilote","ID",$Joueur_loss,"Nom");
							$Avion_win = GetAvionIcon($Avion_win,$Pays_win,$Pilote_win,$Unite_win);
							$Avion_loss = GetAvionIcon($Avion_loss,$Pays_loss,0,$Unite_loss);
						}
						if($Cycle)
							$Cycle_txt="Nuit";
						else
							$Cycle_txt="Jour";
						$Unite_win_txt=GetData("Unit","ID",$Unite_win,"Nom");
						$unit_win_img = "images/unit/unit".$Unite_win."p.gif";
						if(is_file($unit_win_img))
							$Unite_win_txt = "<img src='".$unit_win_img."' title='".$Unite_win_txt."'>";
						$Unite_loss_txt=GetData("Unit","ID",$Unite_loss,"Nom");
						$unit_loss_img = "images/unit/unit".$Unite_loss."p.gif";
						if(is_file($unit_loss_img))
							$Unite_loss_txt = "<img src='".$unit_loss_img."' title='".$Unite_loss_txt."'>";
						$Lieu=GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");
						if(!$Lieu){$Lieu="Inconnu";}						
						?>
						<tr>
							<td><? echo $Date;?></td>
							<td><? echo $Pilote_win;?></td>
							<td><? echo $Avion_win;?></td>
							<td><? echo $Unite_win_txt;?></td>
							<td><img src='<? echo $Pays_win;?>20.gif'></td>
							<td><? echo $Avion_loss;?></td>
							<td><? echo $Pilote_loss;?></td>
							<td><? echo $Unite_loss_txt;?></td>
							<td><img src='<? echo $Pays_loss;?>20.gif'></td>
							<td><? echo $Lieu;?></td>
							<td><? echo $Alt;?>m</td>
							<td><img src='images/meteo<?echo $Cycle;?>.gif' title='<?echo $Cycle_txt;?>'></td>
						</tr>
						<?
						$i++;
					}
				}
			}
			else
				echo "<b>Désolé, aucune victoire enregistrée</b>";
		}
		elseif($Unite_Type == 2 or $Unite_Type == 7 or $Unite_Type == 9 or $Unite_Type == 10 or $Unite_Type == 11)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"(SELECT Cible_id,Avion,Joueur,Unite,Lieu,Arme,`Date`,Pays FROM Attaque WHERE Unite='$Unite') UNION (SELECT Cible_id,Avion,Joueur,Unite,Lieu,Arme,`Date`,Pays FROM Bombardement WHERE Unite='$Unite') ORDER BY ID DESC LIMIT 100");
			mysqli_close($con);
			if($result)
			{
				echo "<h3>Destructions</h3>
				<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
				<thead><tr>
				<th>Date</th>
				<th>Pilote</th>
				<th>Avion</th>
				<th>Cible</th>
				<th>Pays</th>
				<th>Lieu</th>
				<th>Arme</th></tr>
				";
				while($data2=mysqli_fetch_assoc($result))
				{
					$Date = substr($data2['Date'],0,16);
					$Joueur=GetData("Pilote","ID",$data2['Joueur'],"Nom");
					$Lieu=GetData("Lieu","ID",$data2['Lieu'],"Nom");					
					if($data2['Arme'] ==800)
						$Arme="Torpille";
					elseif($data2['Arme'] ==400)
						$Arme="Mine";					
					elseif($data2['Arme'] >49)
						$Arme="Bombe";
					else
						$Arme=GetData("Armes","ID",$data2['Arme'],"Nom");									
					$Avion_win = GetAvionIcon($data2['Avion'],$country,$data2['Joueur'],$data2['Unite'],$Front);
					$Cible_detruite = GetVehiculeIcon($data2['Cible_id'],$data2['Pays'],0,0,$Front);
					
					?>
					<tr>
						<td><? echo $Date;?></td>
						<td><? echo $Joueur;?></td>
						<td><? echo $Avion_win;?></td>
						<td><? echo $Cible_detruite;?></td>
						<td><img src='<? echo $data2['Pays'];?>20.gif'></td>
						<td><? echo $Lieu;?></td>
						<td><? echo $Arme;?></td>
					</tr>
					<?
				}
			}
			else
				echo "<h6>Désolé, aucune destruction enregistrée</h6>";
		}
		elseif($Unite_Type ==3)
		{
			echo "<h3>Reconnaissances</h3>
				<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
				<thead><tr>
					<th>Date</th>
					<th>Pilote</th>
					<th>Avion</th>
					<th>Pays</th>
					<th>Cible photographiée</th>
					<th>Lieu</th></tr>";
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT * FROM Recce WHERE Unite='$Unite' ORDER BY ID DESC LIMIT 50");
			mysqli_close($con);
			if($result)
			{
				while($data = mysqli_fetch_assoc($result))
				{
					$Date = substr($data['Date'],0,16);
					$Unit = $data['Unite'];
					$Avion = $data['Avion'];
					$Cible_s = ucfirst($data['Nom']);
					$Pilote=GetData("Pilote","ID",$data['Joueur'],"Nom");
					$Pays=GetData("Unit","ID",$Unit,"Pays");
					$Lieu=GetData("Lieu","ID",$data['Lieu'],"Nom");
					$Avion_Nom=GetAvionIcon($Avion,$Pays);
					$Avion_unit_img='images/unit/unit'.$Unit.'p.gif';
					if(is_file($Avion_unit_img))
					{
						$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
					}
					echo "<tr><td>".$Date."</td>
						<td>".$Pilote."</td>
						<td>".$Avion_Nom."</td>
						<td><img src='".$Pays."20.gif'></td>
						<td>".$Cible_s."</td>
						<td>".$Lieu."</td></tr>";
				}
				mysqli_free_result($result);
			}
			else
				echo "<h6>Désolé, aucun résultat</h6>";
			echo '</table>';
		}
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
echo "</table>";
?>
