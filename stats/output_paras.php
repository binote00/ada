<?
include_once('./jfv_include.inc.php');
//include_once('./menu_classement.php');
$country=$_SESSION['country'];
$OfficierEMID=$_SESSION['Officier_em'];
echo "<h1>Missions de Parachutage</h1>
<p class='lead'>Ce tableau ne recense que les missions de parachutage réussies par des pilotes joueurs. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>";
if($OfficierEMID >0) echo "<a href='index.php?view=para_ia' class='btn btn-primary'>Parachutages EM</a>";
echo "<div style='overflow:auto; width: 100%;'>
<table class='table table-striped'>
	<thead><tr>
		<th>Date</th>
		<th>Cycle</th>
		<th>Lieu</th>
		<th>Pays</th>
		<th>Unité</th>
		<th>Pilote</th>
		<th>Avion</th>
	</tr></thead>";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT * FROM Parachutages ORDER BY ID DESC LIMIT 50");
		if($result)
		{
			$num=mysqli_num_rows($result);
			if($num ==0)
				echo "<p>Aucune unité n'a encore été parachutée dans cette campagne!</p>";
			else
			{
				$i=0;
				while($i <$num) 
				{
					$Date = substr(mysqli_result($result,$i,"Date"),0,16);
					$Unit = mysqli_result($result,$i,"Unite");
					$Avion = mysqli_result($result,$i,"Avion");
					$Cycle = mysqli_result($result,$i,"Cycle");
					$Joueur = mysqli_result($result,$i,"Joueur");
					$Pilote=GetData("Pilote","ID",$Joueur,"Nom");
					$Lieu=GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");
					//$Avion_Nom=GetData("Avion","ID",$Avion,"Nom");
					$Unite_s=GetData("Unit","ID",$Unit,"Nom");
					$Pays=GetData("Unit","ID",$Unit,"Pays");
					$Front=GetData("Pilote","ID",$Joueur,"Front");
					$Avion_Nom = GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front);
					$Avion_unit_img="images/unit/unit".$Unit."p.gif";
					if(is_file($Avion_unit_img))
						$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
					$Cible_unit_img="images/unit/unit".$Unite_Cible."p.gif";
					if(is_file($Cible_unit_img))
						$Unite_Cible_s="<img src='".$Cible_unit_img."' title='".$Unite_Cible_s."'>";
					if($Cycle)
						$Cycle_txt="Nuit";
					else
						$Cycle_txt="Jour";
					?>
			<tr>
				<td><? echo $Date;?></td>
				<td><? echo "<img src='images/meteo".$Cycle.".gif' title='".$Cycle_txt."'>";?></td>
				<td><?if($Pays ==$country or $Admin){echo $Lieu;}else{echo "Inconnu";}?></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><? echo $Unite_s;?></td>
				<td><? echo $Pilote;?></td>
				<td><? echo $Avion_Nom;?></td>
			</tr>
					<?
					$i++;
				}
			}
		}
		else
			echo "<h>Désolé, aucun résultat</h>";
echo "</table></div>";
?>
