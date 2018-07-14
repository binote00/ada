<div>
<br>
<table border="0" cellspacing="1" cellpadding="5" bgcolor="#ECDDC1">
	<th colspan="10" class="TitreBleu_bc">Tableau des Missions d'Interception</th>
	<tr bgcolor="#CDBDA7">
		<th>Date</th>
		<th>Pilote intercepteur</th>
		<th>Avion intercepteur</th>
		<th>Unité</th>
		<th>Pays</th>
		<th bgcolor='tan'></th>
		<th>Avions interceptés</th>
		<th>Unité interceptée</th>
		<th>Pays</th>
		<th>Lieu</th>
		<?
		include_once('./jfv_include.inc.php');
		$Unite = Insec($_GET['id']);
		$PlayerID = $_SESSION['PlayerID'];
		$country = $_SESSION['country'];
		$con = dbconnecti();
		$query="SELECT * FROM Intercept WHERE Unite_win='$Unite' ORDER BY ID DESC LIMIT 50";
		$result=mysqli_query($con, $query);
		mysqli_close($con);
		if($result)
		{
			$num=mysqli_num_rows($result);

			if($num==0)
			{
				echo "<b><center>Désolé, aucun résultat</center></b>";
			}
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
					$Pilote = GetData("Joueur","ID",mysqli_result($result,$i,"Joueur_win"),"Nom");
					$Lieu = GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");
					$Avion = GetData("Avion","ID",$Avion_win,"Nom");
					$Unite = GetData("Unit","ID",$Unite_win,"Nom");
					$Pays = GetData("Unit","ID",$Unite_win,"Pays");
					if($Avion_loss == 0)
					{
						$Avion_loss_nom = "Inconnu";
					}
					else
					{
						$Avion_loss_nom = GetData("Avion","ID",$Avion_loss,"Nom");
					}
					if($Unite_loss == 0)
					{
						$Unite_loss_nom = "Inconnue";
						if($Pays == 1)
						{
							$Pays_eni = 4;
						}
						else
						{
							$Pays_eni = 1;
						}
					}
					else
					{
						$Unite_loss_nom = GetData("Unit","ID",$Unite_loss,"Nom");
						$Pays_eni = GetData("Unit","ID",$Unite_loss,"Pays");
					}
					
					$Avion_img_win = "images/avion".$Avion_win.".gif";
					$Avion_img_loss = "images/avion".$Avion_loss.".gif";
					$Avion_unit_win_img = "images/unit".$Unite_win."p.gif";
					$Avion_unit_loss_img = "images/unit".$Unite_loss."p.gif";
					if(is_file($Avion_img_win))
					{
						$Avion = "<img src='".$Avion_img_win."' title='".$Avion."'>";
					}
					if(is_file($Avion_img_loss))
					{
						$Avion_loss_nom = "<img src='".$Avion_img_loss."' title='".$Avion_loss_nom."'>";
					}				
					if(is_file($Avion_unit_win_img))
					{
						$Unite = "<img src='".$Avion_unit_win_img."' title='".$Unite."'>";
					}
					if(is_file($Avion_unit_loss_img))
					{
						$Unite_loss_nom = "<img src='".$Avion_unit_loss_img."' title='".$Unite_loss_nom."'>";
					}				
		?>
	</tr>
	<tr>
		<td><? echo $Date;?></td>
		<td><? echo $Pilote;?></td>
		<td><? echo $Avion;?></td>
		<td><? echo $Unite;?></td>
		<td><img src='<? echo $Pays;?>20.gif'></td>
		<td bgcolor='tan'></td>
		<td><? echo $Avion_loss_nom;?></td>
		<td><? echo $Unite_loss_nom;?></td>
		<td><img src='<? echo $Pays_eni;?>20.gif'></td>
		<td><? if($country == $Pays or $PlayerID == 1){echo $Lieu;}else{echo "Inconnu";}?></td>
	</tr>
			<?
			$i++;
		}
	}
}
else
{
	echo "<b>Désolé, aucun résultat</b>";
}
?>
</table>
<hr>
</div>
