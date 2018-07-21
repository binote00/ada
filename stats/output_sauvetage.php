<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
//include_once('./menu_classement.php');
?>
<h1>Missions de Sauvetage</h1>
<p class='lead'>Ce tableau ne recense que les missions de sauvetage réussies par des pilotes joueurs. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>
<div style='overflow:auto; width: 100%;'>
<table class='table table-striped'>
	<thead><tr>
		<th>Date</th>
		<th>Cycle</th>
		<th>Lieu</th>
		<th>Pays</th>
		<th>Unité</th>
		<th>Pilote</th>
		<th>Avion</th>
		<th>Pilote sauvé</th>
	</tr></thead>
		<?
		$PlayerID=$_SESSION['PlayerID'];
		$country=$_SESSION['country'];
		if($PlayerID >0)
			$Renseignement=GetData("Pilote","ID",$PlayerID,"Renseignement");
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT * FROM Sauvetage ORDER BY ID DESC LIMIT 50");
		if($result)
		{
			$num=mysqli_num_rows($result);
			if($num ==0)
				echo "<h6>Aucun pilote n'a encore été sauvé dans cette campagne!</h6>";
			else
			{
				$i=0;
				while($i <$num) 
				{
					$Date=substr(mysqli_result($result,$i,"Date"),0,16);
					$Unit=mysqli_result($result,$i,"Unite");
					$Avion=mysqli_result($result,$i,"Avion");
					$Cycle=mysqli_result($result,$i,"Cycle");
					$Joueur=mysqli_result($result,$i,"PlayerID");
					$Pilote=GetData("Pilote","ID",$Joueur,"Nom");
					$Pilote_sauve=GetData("Pilote","ID",mysqli_result($result,$i,"MIA"),"Nom");
					$Lieu=GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");
					//$Avion_Nom=GetData("Avion","ID",$Avion,"Nom");
					$Unite_s=GetData("Unit","ID",$Unit,"Nom");
					$Pays=GetData("Unit","ID",$Unit,"Pays");
					$Front=GetData("Pilote","ID",$Joueur,"Front");
					$Avion_Nom=GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front);
					$Avion_unit_img="images/unit/unit".$Unit."p.gif";
					if(is_file($Avion_unit_img))
						$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
					if($Cycle)
						$Cycle_txt="Nuit";
					else
						$Cycle_txt="Jour";
				?>
			<tr>
				<td><? echo $Date;?></td>
				<td><? echo "<img src='images/meteo".$Cycle.".gif' title='".$Cycle_txt."'>";?></td>
				<td><? if($country == $Pays or $Renseignement >150){echo $Lieu;}else{echo "Inconnu";}?></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><? if($country == $Pays or $Renseignement >150){echo $Unite_s;}else{echo "Inconnu";}?></td>
				<td><? if($country == $Pays or $Renseignement >200){echo $Pilote;}else{echo "Inconnu";}?></td>
				<td><? echo $Avion_Nom;?></td>
				<td><? if($country == $Pays or $Renseignement >200){echo $Pilote_sauve;}else{echo "Inconnu";}?></td>
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
