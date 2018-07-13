<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$Officier=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 or $Officier >0 or $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_access.php');
	if($PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Front,Renseignement FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Renseignement=$data['Renseignement'];
				$Front=$data['Front'];
				$Unite=$data['Unit'];
			}
			mysqli_free_result($result);
		}	
		$Base=GetData("Unit","ID",$Unite,"Base"); 
	}
	elseif($OfficierEMID)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front FROM Officier_em WHERE ID='$OfficierEMID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	elseif($Officier)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front FROM Officier WHERE ID='$Officier'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
		}	
	}
	echo "<h1>Bombardements Stratégiques</h1>
	<p class='lead'>Ce tableau ne recense pas les bombardements stratégiques effectués à basse altitude. Ne vous fiez pas à ce tableau pour tirer des conclusions tactiques ou stratégiques!</p>";
	if($OfficierEMID >0) echo "<a href='index.php?view=bombs_ia' class='btn btn-primary'>Bombardements EM</a>";
	echo "<div style='overflow:auto; width: 100%;'><table class='table table-striped table-condensed'><thead><tr>
		<th>Date</th>
		<th>Cycle</th>
		<th>Lieu</th>
		<th>Pays</th>
		<th>Unité</th>
		<th>Pilote crédité</th>
		<th>Avion</th>
		<th>Cible détruite</th>
		<th>Pays cible</th>
		<th>Altitude</th>
	</tr></thead>";
	$con=dbconnecti(); //(1,2,3,4,5,6,7,8,9,10,11,12,13,22,23,25,26,27,28,29,30,31,34,35,36)
	$result=mysqli_query($con,"SELECT a.Date,a.Unite,a.Avion,a.Lieu,a.Pays,a.Cible_id,a.Joueur,a.Cycle,a.Altitude,j.Nom,j.Front,u.Nom as Unite_s,u.Pays as Pays_s,l.Latitude,l.Longitude,l.Nom as Lieu_Nom
	FROM Bombardement as a,Pilote as j,Unit as u,Lieu as l WHERE a.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW() AND a.Cible_id <5000 AND a.Cible_id NOT IN (8,18,52)
	AND a.Joueur=j.ID AND a.Lieu=l.ID AND a.Unite=u.ID GROUP BY a.Date,a.Joueur,a.Cible_id ORDER BY a.ID DESC LIMIT 50");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Front_Lieu=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
			if($Admin or $Front ==$Front_Lieu or $Front ==99)
			{
				$Date=substr($data['Date'],0,16);
				$Unit=$data['Unite'];
				$Avion=$data['Avion'];
				$Lieu=$data['Lieu'];
				$Pays_cible=$data['Pays'];
				$Cible_id=$data['Cible_id'];
				$Joueur=$data['Joueur'];
				$Unite_s=$data['Unite_s'];
				$Pays=$data['Pays_s'];
				$Lieu_Nom=$data['Lieu_Nom'];
				$Cycle=$data['Cycle'];
				$Pilote=$data['Nom'];
				$Front=$data['Front'];
				$Avion_Nom=GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front);
				if($Cible_id)$Cible_s=GetVehiculeIcon($Cible_id,$Pays_cible,0,0,$Front);
				$Avion_unit_img="images/unit/unit".$Unit."p.gif";
				if(is_file($Avion_unit_img))
					$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
				if($Cycle)
					$Cycle_txt="Nuit";
				else
					$Cycle_txt="Jour";
				echo "<tr><td>".$Date."</td><td><img src='images/meteo".$Cycle.".gif' title='".$Cycle_txt."'></td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0 or $Lieu ==$Base)
					echo $Lieu_Nom;
				else
					echo "Inconnu";
				echo "</td><td><img src='".$Pays."20.gif'></td><td>";
				if($country ==$Pays or $Renseignement >150 or $OfficierEMID >0)
					echo $Unite_s;
				else
					echo "Inconnu";
				echo "</td><td>";
				if($country ==$Pays or $Renseignement >200 or $Admin ==1)
					echo $Pilote;
				else
					echo "Inconnu";
				echo "</td><td>".$Avion_Nom."</td><td>";
				if($country ==$Pays or $Renseignement >50 or $OfficierEMID >0 or $Lieu ==$Base)
					echo $Cible_s;
				else
					echo "Inconnu";
				echo "</td><td><img src='".$Pays_cible."20.gif'></td>";
				if(($OfficierEMID >0 or $Lieu ==$Base or $Admin ==1) and $Premium)
					$Dist_txt=$data['Altitude']."m";
				else
					$Dist_txt="<div class='i-flex premium20'></div>";
				echo "<td>".$Dist_txt."</td></tr>";
			}
		}
		mysqli_free_result($result);
	}
	else
		echo "<h6>Désolé, Aucune cible n'a été détruite récemment</h6>";
	echo "</table></div>";
	/*
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT * FROM Bombardement WHERE Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW() ORDER BY ID DESC LIMIT 50");
	if($result)
	{
		$num=mysqli_num_rows($result);
		if($num ==0)
			echo "<h6>Aucun bombardement n'a été effectué récemment!</h6>";
		else
		{
			$i=0;
			while($i <$num) 
			{
				$Date=substr(mysqli_result($result,$i,"Date"),0,16);
				$Unit=mysqli_result($result,$i,"Unite");
				$Avion=mysqli_result($result,$i,"Avion");
				$Lieu=mysqli_result($result,$i,"Lieu");
				$Cycle=mysqli_result($result,$i,"Cycle");
				$Joueur=mysqli_result($result,$i,"Joueur");
				$Pays_cible=mysqli_result($result,$i,"Pays");					
				$Cible_id=mysqli_result($result,$i,"Cible_id");
				//$Cible_s=ucfirst(mysqli_result($result,$i,"Nom"));	
				if($Joueur)
				{
					$Pilote=GetData("Pilote","ID",$Joueur,"Nom");
					$Front=GetData("Pilote","ID",$Joueur,"Front");
				}
				else
					$Pilote="Inconnu";
				//$Cible_s=GetData("Cible","ID",$Cible_id,"Nom");
				//$Avion_Nom=GetData("Avion","ID",$Avion,"Nom");
				$Lieu_Nom=GetData("Lieu","ID",$Lieu,"Nom");
				$Lieu_Pays=GetData("Lieu","ID",$Lieu,"Pays");
				$Unite_s=GetData("Unit","ID",$Unit,"Nom");
				$Pays=GetData("Unit","ID",$Unit,"Pays");						
				if($Cycle)
					$Cycle_txt="Nuit";
				else
					$Cycle_txt="Jour";					
				$Avion_Nom=GetAvionIcon($Avion,$Pays,$Joueur,$Unit,$Front);
				$Cible_s=GetVehiculeIcon($Cible_id,$Pays_cible,0,0,$Front);					
				$Avion_unit_img='images/unit/unit'.$Unit.'p.gif';
				if(is_file($Avion_unit_img))
					$Unite_s="<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
	?>
<tr>
	<td><? echo $Date;?></td>
	<td><? echo "<img src='images/meteo".$Cycle.".gif' title='".$Cycle_txt."'>";?></td>
	<td><? if($country ==$Pays or $country ==$Pays_cible or $Renseignement >50 or $Lieu ==$Base or $OfficierEMID >0){echo $Lieu_Nom;}else{echo "Inconnu";}?></td>
	<td><img src='<? echo $Pays;?>20.gif'></td>
	<td><? if($country ==$Pays or $Renseignement >150 or $OfficierEMID >0){echo $Unite_s;}else{echo "Inconnu";}?></td>
	<td><? if($country ==$Pays or $Renseignement >200 or $OfficierEMID >0){echo $Pilote;}else{echo "Inconnu";}?></td>
	<td><? echo $Avion_Nom;?></td>
	<td><? if($country ==$Pays or $country ==$Pays_cible or $Renseignement >50 or $Lieu ==$Base or $OfficierEMID >0){echo $Cible_s;}else{echo "Inconnu";}?></td>
	<td><img src='<? echo $Pays_cible;?>20.gif'></td>
</tr>
		<?
			$i++;
			}
		}
	}
}
else
	echo "<h6>Désolé, aucun résultat</h6>";
echo "</table></div>";*/
}
?>