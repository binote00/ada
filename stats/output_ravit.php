<?
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID)
{
	include_once('jfv_include.inc.php');
	//include_once('menu_classement.php');
	$PlayerID=$_SESSION['PlayerID'];
	$country=$_SESSION['country'];
	$OfficierEMID=$_SESSION['Officier_em'];
	$Access=false;
	if($PlayerID >0)$Renseignement=GetData("Pilote","ID",$PlayerID,"Renseignement");
	if($country ==$Pays or $Renseignement >150 or $Admin or $OfficierEMID >0)$Access=true;
	echo "<h1>Missions de Ravitaillement</h1>
	<p class='lead'>Ce tableau ne recense que les missions de ravitaillement réussies par des pilotes joueurs. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>";
	if($OfficierEMID >0) echo "<a href='index.php?view=para_ia' class='btn btn-primary'>Parachutages EM</a>";
	echo "<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr>
		<th>Date</th>
		<th>Lieu</th>
		<th>Pays</th>
		<th>Unité</th>
		<th>Pilote</th>
		<th>Avion</th>
		<th>Bénéficiaire</th>
		<th>Cargaison</th>
	</tr></thead>";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT * FROM Ravitaillements ORDER BY ID DESC LIMIT 50");
		if($result)
		{
			$num=mysqli_num_rows($result);
			if($num ==0)
				echo "<p>Aucune unité n'a encore été ravitaillée dans cette campagne!</p>";
			else
			{
				$i=0;
				while($i <$num) 
				{
					$Date=substr(mysqli_result($result,$i,"Date"),0,16);
					$Unit=mysqli_result($result,$i,"Unite");
					$Avion=mysqli_result($result,$i,"Avion");
					$Unite_Cible=mysqli_result($result,$i,"Unite_Cible");
					$Fret=mysqli_result($result,$i,"Cargaison");
					$Joueur=mysqli_result($result,$i,"PlayerID");
					$Cible_Type=mysqli_result($result,$i,"Cible_Type");					
					$Pilote=GetData("Pilote","ID",$Joueur,"Nom");
					$Lieu=GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");
					//$Avion_Nom=GetData("Avion","ID",$Avion,"Nom");
					$Unite_s=GetData("Unit","ID",$Unit,"Nom");
					$Pays=GetData("Unit","ID",$Unit,"Pays");
					$Front=GetData("Pilote","ID",$Joueur,"Front");					
					if($Cible_Type)
						$Unite_Cible_s=GetData("Regiment","ID",$Unite_Cible,"ID").'e Compagnie';
					else
					{
						$Unite_Cible_s=GetData("Unit","ID",$Unite_Cible,"Nom");
						$Cible_unit_img="images/unit/unit".$Unite_Cible."p.gif";
						if(is_file($Cible_unit_img))
							$Unite_Cible_s="<img src='".$Cible_unit_img."' title='".$Unite_Cible_s."'>";
					}
					$Avion_Nom=GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front);
					$Avion_unit_img="images/unit/unit".$Unit."p.gif";
					if(is_file($Avion_unit_img))$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";					
					switch($Fret)
					{
						case 50: case 125: case 250: case 500:
							$Fret="Bombes";
						break;
						case 1100: case 1200:
							$Fret="Carburant";
						break;
						case 2500: case 5000: case 15000: case 50000:
							$Fret="Munitions";
						break;
					}
						
					?>
			<tr>
				<td><? echo $Date;?></td>
				<td><?if($Pays ==$country or $Renseignement >200){echo $Lieu;}else{echo "Inconnu";}?></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><? if($Access){echo $Unite_s;}else{echo "Inconnu";}?></td>
				<td><? if($Access){echo $Pilote;}else{echo "Inconnu";}?></td>
				<td><? echo $Avion_Nom;?></td>
				<td><?if($Access){echo $Unite_Cible_s;}else{echo "Inconnu";}?></td>
				<td><?if($Pays ==$country or $Renseignement >250){echo $Fret;}else{echo "Inconnue";}?></td>
			</tr>
					<?
					$i++;
				}
			}
		}
		else
			echo "<h6>Désolé, aucun résultat</h6>";
	echo "</table></div>";
}
?>