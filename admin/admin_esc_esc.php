<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');

if($_SESSION['PlayerID'] == 1)
{
	$Unite = Insec($_POST['unit']);
	if($Unite > 0)
	{
?>
<div>
<br>
<table class='table'>
	<tr><th colspan="7" class="TitreBleu_bc">Tableau des Missions d'Escorte</th></tr>
	<tr bgcolor="#CDBDA7">
		<th>Date</th>
		<th>Pilote</th>
		<th>Avion</th>
		<th>Unité</th>
		<th>Pays</th>
		<th>Avions escortés</th>
		<th>Lieu</th>
	</tr>
		<?
		$country = Insec($_SESSION['country']);
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT * FROM Escorte WHERE Unite='$Unite' ORDER BY ID DESC LIMIT 100");
		mysqli_close($con);
		if($result)
		{
			$num=mysqli_num_rows($result);
			if($num==0)
				echo "<b><center>Désolé, aucun résultat</center></b>";
			else
			{
				$i=0;
				while ($i < $num) 
				{
					$Date = substr(mysqli_result($result,$i,"Date"),0,16);
					$Avion = mysqli_result($result,$i,"Avion");
					$Escorte = mysqli_result($result,$i,"Escorte");
					$Escorte_nbr = mysqli_result($result,$i,"Escorte_nbr");
					$Pilote=GetData("Pilote","ID",mysqli_result($result,$i,"Joueur"),"Nom");
					$Lieu=GetData("Lieu","ID",mysqli_result($result,$i,"Lieu"),"Nom");
					//$Avion_Nom=GetData("Avion","ID",$Avion,"Nom");
					$Unite_s=GetData("Unit","ID",$Unite,"Nom");
					$Pays=GetData("Unit","ID",$Unite,"Pays");
					$Escorte_Nom=GetData("Avion","ID",$Escorte,"Nom");
					$Front=GetData("Pilote","ID",mysqli_result($result,$i,"Joueur"),"Front");
					$Avion_Nom = GetAvionIcon($Avion,$Pays,$Pilote,$Unite,$Front);
					$Avion_unit_img = "images/unit/unit".$Unite."p.gif";
					$Escorte_img = "images/avions/avion".$Escorte.".gif";
					if(is_file($Avion_unit_img))
						$Unite_s = "<img src='".$Avion_unit_img."' title='".$Unite_s."'>";
					if(is_file($Escorte_img))
						$Escorte_Nom = "<img src='".$Escorte_img."' title='".$Escorte_Nom."'>";
				?>
			<tr>
				<td><? echo $Date;?></td>
				<td><? echo $Pilote;?></td>
				<td><? echo $Avion_Nom;?></td>
				<td><? echo $Unite_s;?></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><? echo $Escorte_nbr." ".$Escorte_Nom;?></td>
				<td><? echo $Lieu;?></td>
			</tr>
					<?
					$i++;
				}
			}
		}
		else
			echo "<b><center>Désolé, aucun résultat</center></b>";
//mysqli_free_result($result);
		echo "</table><hr></div>";
	}
	else
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Unit ORDER BY Nom ASC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				 $Units .= "<option value='".$data['ID']."'>".$data['Nom']."</option>";
			}
			mysqli_free_result($result);
			unset($data);
		}
		echo "<div><form action='admin_esc_esc.php' method='post'>
			<table border='0' cellspacing='2' cellpadding='5' bgcolor='#ECDDC1'>
			<tr><th bgcolor='tan'>Sélection d'unité</th></tr>
			<tr><td align='left'><select name='unit' style='width: 150px'>".$Units."</select></td></tr>
			</table><input type='Submit' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></form></div>";
	}
}?>