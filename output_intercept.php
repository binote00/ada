<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
//include_once('./menu_classement.php');
?>
<h1>Missions d'Interception</h1>
<div style='overflow:auto; width: 100%;'>
<table class='table table-striped'>
	<thead><tr>
		<th>Date</th>
		<th>Lieu</th>
		<th>Pays</th>
		<th>Unité</th>
		<th>Pilote intercepteur</th>
		<th>Avion intercepteur</th>
		<th>Avions interceptés</th>
		<th>Unité interceptée</th>
		<th>Pays</th>
	</tr></thead>
		<?
		$PlayerID = $_SESSION['PlayerID'];
		$country = $_SESSION['country'];
		if($PlayerID > 0)
		{
			$Renseignement = GetData("Pilote","ID",$PlayerID,"Renseignement");
			$Unite = GetData("Pilote","ID",$PlayerID,"Unit");
		}
		$con = dbconnecti();
		$result = mysqli_query($con, "SELECT * FROM Intercept ORDER BY ID DESC LIMIT 50");
		if($result)
		{
			$num = mysqli_num_rows($result);
			if($num == 0)
				echo "<h6>Aucune interception n'a encore été effectuée dans cette campagne!</h6>";
			else
			{
				$i=0;
				while ($i < $num) 
				{

					$Date = substr(mysqli_result($result,$i,"Date"),0,16);
					$Avion_loss = mysqli_result($result,$i,"Avion_loss");
					$Unite_loss = mysqli_result($result,$i,"Unite_loss");
					$Unite_win = mysqli_result($result,$i,"Unite_win");
					$Avion_win = mysqli_result($result,$i,"Avion_win");
					$Joueur_win = mysqli_result($result,$i,"Joueur_win");
					$Pilote = GetData("Pilote","ID",$Joueur_win,"Nom");
					$Lieu = GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");
					$Unite = GetData("Unit","ID",$Unite_win,"Nom");
					$Pays = GetData("Unit","ID",$Unite_win,"Pays");
					if($Avion_loss == 0)
						$Avion_loss_nom = "Inconnu";
					else
						$Avion_loss_nom = GetData("Avion","ID",$Avion_loss,"Nom");
					if($Unite_loss == 0)
					{
						$Unite_loss_nom = "Inconnue";
						if($Pays == 1)
							$Pays_eni = 4;
						else
							$Pays_eni = 1;
					}
					else
					{
						$Unite_loss_nom = GetData("Unit","ID",$Unite_loss,"Nom");
						$Pays_eni = GetData("Unit","ID",$Unite_loss,"Pays");
					}					
					$Front = GetData("Pilote","ID",$Joueur_win,"Front");							
					$Avion = GetAvionIcon($Avion_win,$Pays,$Joueur_win,$Unite_win,$Front);
					$Avion_loss_nom = GetAvionIcon($Avion_loss,$Pays_eni,0,$Unite_loss,$Front,$Avion_loss_nom);					
					$Avion_unit_win_img = 'images/unit/unit'.$Unite_win.'p.gif';
					$Avion_unit_loss_img = 'images/unit/unit'.$Unite_loss.'p.gif';
					if(is_file($Avion_unit_win_img))
						$Unite = "<img src='".$Avion_unit_win_img."' title='".$Unite."'>";
					if(is_file($Avion_unit_loss_img))
						$Unite_loss_nom = "<img src='".$Avion_unit_loss_img."' title='".$Unite_loss_nom."'>";
		?>
	<tr>
		<td><? echo $Date;?></td>
		<td><? if($country == $Pays or $Unit == $Unite or $Renseignement > 250 or $PlayerID == 1){echo $Lieu;}else{echo "Inconnu";}?></td>
		<td><img src='<? echo $Pays;?>20.gif'></td>
		<td><? if($country == $Pays or $Renseignement > 150 or $PlayerID == 1){echo $Unite;}else{echo "Inconnu";}?></td>
		<td><? if($country == $Pays or $Renseignement > 200 or $PlayerID == 1){echo $Pilote;}else{echo "Inconnu";}?></td>
		<td><? echo $Avion;?></td>
		<td><? echo $Avion_loss_nom;?></td>
		<td><? if($country == $Pays_eni or $Renseignement > 150 or $PlayerID == 1){echo $Unite_loss_nom;}else{echo "Inconnu";}?></td>
		<td><img src='<? echo $Pays_eni;?>20.gif'></td>
	</tr>
			<?
			$i++;
		}
	}
}
else
	echo "<h6>Désolé, aucun résultat</h6>";
echo "</table></div>";
?>
