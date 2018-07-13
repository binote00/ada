<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	if($PlayerID >0)
	{
		$country=$_SESSION['country'];
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Reputation,Avancement,Unit,Credits,Avion_Perso,Front FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Reputation=$data['Reputation'];
				$Avancement=$data['Avancement'];
				$Unite=$data['Unit'];
				$Front=$data['Front'];
				$Avion_P=$data['Avion_Perso'];
				$Credits=$data['Credits'];
			}
			mysqli_free_result($result);
		}
		if($Avancement > $Reputation)
			$Level=$Avancement;
		else
			$Level=$Reputation;
		if($Level >999)
		{
			$Level/=5000;
			$Reputation-=11000;
			if($Reputation >100)
				$Cr_txt="Suffisante";
			else
				$Cr_txt="<span class='text-danger'>Insuffisante</span>";
			$con=dbconnecti();
			$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
			$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
			$result=mysqli_query($con,"SELECT Avion1,Avion2,Avion3,Type FROM Unit WHERE ID='$Unite'");
			//mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Avion1=$data['Avion1'];
					$Avion2=$data['Avion2'];
					$Avion3=$data['Avion3'];
					$Unit_Type=$data['Type'];
				}
				mysqli_free_result($result);
			}
			if($Avion_P)
				$skills="<i>Vous possédez déjà un avion personnalisé. En choisir un nouveau supprimera l'ancien!</i>";
			/*$con=dbconnecti(4);
			$Crash=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type IN (11,12) AND PlayerID='$PlayerID' AND Avion_Nbr=1"),0);
			$Perdu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=34 AND PlayerID='$PlayerID'"),0);
			mysqli_close($con);*/			
			//$Cred_Min=15+ceil(($Crash+$Perdu)/10);
			$modele='';
			if($PlayerID ==1)
				$query="SELECT DISTINCT ID,Nom,Type,Fin_Prod FROM Avion ORDER BY Nom ASC";
			elseif($Unit_Type ==1)
				$query="SELECT DISTINCT ID,Nom,Type,Fin_Prod FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Type IN (1,5) AND Premium IN (0,'$Premium') AND ((Rating BETWEEN 0 AND '$Level') OR (Fin_Prod <= '$Date_Campagne') OR ID IN (".$Avion1.",".$Avion2.",".$Avion3.")) ORDER BY Nom ASC";
			else
				$query="SELECT DISTINCT ID,Nom,Type,Fin_Prod FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Type='$Unit_Type' AND Premium IN (0,'$Premium') AND ((Rating BETWEEN 0 AND '$Level') OR (Fin_Prod <= '$Date_Campagne') OR ID IN (".$Avion1.",".$Avion2.",".$Avion3.")) ORDER BY Nom ASC";
			//$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
				{
					$Type=GetAvionType($data['Type']);
					$Reserve="";
					if($data['Fin_Prod'] <$Date_Campagne)
						$Reserve=" - Réserve";
					$modele.="<option value='".$data['ID']."'>".$data['Nom']." ( ".$Type.$Reserve." )</option>";
				}
				mysqli_free_result($result);
			}
	?>
	<h1>Choix de votre avion personnel</h1>
	<div class='alert alert-info'>L'avion personnel est un avion que votre pilote peut personnaliser via l'ajout de divers équipements et l'optimisation de certains paramètres.
	<br>Plus votre pilote sera réputé, plus il aura accès à des avions performants.</div>
	<form action="choix_avion1.php" method="post">
		<table border="0" cellspacing="1" cellpadding="1" bgcolor="#ECDDC1">
		<tr><td colspan="2"><img src="images/choix_avion<? echo $country;?>.jpg"></td></tr>
		<tr><th>Choix du modèle</th>
			<td align="left">
				<select class="form-control" name="avion">
					<?echo $modele;?>
				</select>
			</td>
		</tr>
		<?if($PlayerID ==1){?>
			<tr><th>Choix du joueur</th>
			<td align="left">
				<select class="form-control" name="Joueur">
					<?
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Pilote WHERE Actif=0 ORDER BY Nom ASC");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
							{
								?>
								 <option value="<? echo $data['ID'];?>"> <? echo $data['Nom'];?> </option>
								<?
							}
							mysqli_free_result($result);
						}
					?>
				</select>
			</td>
			<td>
				<select class="form-control" name="tp">
					<option value="1">Avion Perso</option>
					<option value="2">Prototype</option>
				</select>		
			</td>
			</tr>
		<?}else{?>
		<tr><th>Choix de la dépense</th>
			<td align="left">
				<Input type='Radio' name='methode' value='1' checked>- Crédits Temps<br>
				<Input type='Radio' name='methode' value='2'>- Réputation (<?echo $Cr_txt;?>)<br>
			</td>
		</tr>
		<?}?>
		</table>
		<br><input type='Submit' class="btn btn-default" value='VALIDER' onclick='this.disabled=true;this.form.submit();'>
	</form>
	<?
			$skills.="<div class='alert alert-warning'>Changer d'avion personnalisé coûte,au choix au minimum <img src='/images/CT15.png'> pour un avion toujours en production et <img src='/images/CT10.png'> pour un avion de réserve
						<br>Vous pouvez également choisir de sacrifier un peu de la réputation de votre pilote au lieu de vos Crédits Temps</div>";
			echo $skills;
		}
		else
			echo "<div class='alert alert-danger'>Vous ne possédez pas de suffisamment de réputation!</div>";
	}
}
else
	header("Location: ./tsss.php");
?>