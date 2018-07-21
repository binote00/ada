<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
require_once __DIR__ . '/../jfv_include.inc.php';
$PlayerID=$_SESSION['PlayerID'];
if(!$Admin)$Admin=GetData("Joueur","ID",$PlayerID,"Admin");
if($Admin ==1)
{
	include_once __DIR__ . '/../view/menu_infos.php';
	$Pays=Insec($_POST['land']);
	$Type=Insec($_POST['type']);
	$Tri=Insec($_POST['Tri']);
	if(!$Tri)
		$Tri=14;
	if($Pays =="all")
		$Pays="%";
	if($Type =="all")
		$Type="%";
?>
<div style='overflow:auto; width: 100%;'>
<table class='table'>
	<th colspan="20" class="TitreBleu_bc">Tableau des Avions</th>
	<tr bgcolor="#CDBDA7">
		<th>N°</th>
		<th>Avion</th>
		<th>Pays</th>
		<th>Type</th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="12"><input title="Date" type='Submit' value='Date'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="14"><input title="Rating" type='Submit' value='Rating'></form></th>
		<th>Rating Test</th>
		<th>Taille</th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="10"><input title="Robustesse" type='Submit' value='Robustesse'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="11"><input title="Blindage" type='Submit' value='Blindage'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="5"><input title="Autonomie" type='Submit' value='Autonomie'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="4"><input title="Plafond" type='Submit' value='Plafond'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="1"><input title="VitesseH" type='Submit' value='VitesseH'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="2"><input title="VitesseB" type='Submit' value='VitesseB'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="3"><input title="VitesseA" type='Submit' value='VitesseA'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="13"><input title="VitesseP" type='Submit' value='VitesseP'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="6"><input title="Stabilit�" type='Submit' value='Stabilite'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="7"><input title="Roulis" type='Submit' value='Maniabilite'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="8"><input title="Taux Virage H" type='Submit' value='ManoeuvreH'></form></th>
		<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Pays' value="all"><input type='hidden' name='Type' value="all"><input type='hidden' name='Tri' value="9"><input title="Taux Virage B" type='Submit' value='ManoeuvreB'></form></th>
	</tr>
<?
switch($Tri)
{
	case 1:
		$Tri="VitesseH";
	break;
	case 2:
		$Tri="VitesseB";
	break;
	case 3:
		$Tri="VitesseA";
	break;
	case 4:
		$Tri="Plafond";
	break;
	case 5:
		$Tri="Autonomie";
	break;
	case 6:
		$Tri="Stabilite";
	break;
	case 7:
		$Tri="Maniabilite";
	break;
	case 8:
		$Tri="ManoeuvreH";
	break;
	case 9:
		$Tri="ManoeuvreB";
	break;
	case 10:
		$Tri="Robustesse";
	break;
	case 11:
		$Tri="Blindage";
	break;
	case 12:
		$Tri="Engagement";
	break;
	case 13:
		$Tri="VitesseP";
	break;
	case 14:
		$Tri="Rating";
	break;
}
	$i=0;
	if($Pays > 9 or $Pays ==1)
		$query="SELECT * FROM Avion WHERE Pays='$Pays' AND Type LIKE '$Type' ORDER BY $Tri DESC, Nom ASC";
	else
		$query="SELECT * FROM Avion WHERE Pays LIKE '$Pays' AND Type LIKE '$Type' ORDER BY $Tri DESC, Nom ASC";
	$con=dbconnecti();
	$result=mysqli_query($con, $query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Nom=$data['Nom'];
			$Pays=$data['Pays'];
			$Type=GetAvionType($data['Type']);
			$Engagement=$data['Engagement'];
			$Puissance=$data['Puissance'];
			$Masse=$data['Masse'];
			$VitesseH=$data['VitesseH'];
			$VitesseB=$data['VitesseB'];
			$VitesseA=$data['VitesseA'];
			$VitesseP=$data['VitesseP'];
			$Stab=$data['Stabilite'];
			$Roulis=$data['Maniabilite'];
			$ManH=$data['ManoeuvreH'];
			$ManB=$data['ManoeuvreB'];
			$Robustesse=$data['Robustesse'];
			$Plafond=$data['Plafond'];
			$Autonomie=$data['Autonomie'];
			$Vis=$data['Visibilite'];
			$Blindage=$data['Blindage'];
			$Rating=$data['Rating'];
			$avion=$data['ID'];
			//$Rating_Reco=(((($Autonomie*2) + $Plafond + ($data['Detection']*500) + ($Stab*10) + ($VitesseH*10) + ($VitesseB*10) + ($ManH*10) + ($ManB*10) + ($VitesseP*2) + $Robustesse)/1000)-30)/2;
			//$Rating_Pat_Mar=(((($Autonomie*2) + ($data['Radar']*1000) + ($data['Detection']*500) + ($Stab*5) + ($VitesseH*5) + ($VitesseB*5) + ($Blindage*100) + $Robustesse)/2000)-8)/2;
			$Avion_img="images/avions/avion".$avion.".gif";
			if(is_file($Avion_img))
				$Avion="<img src='".$Avion_img."' title='".$Nom."'>";
			else
				$Avion=$Nom;			
			$i++;
			echo "<tr><td>".$i."</td>";			
		?>
				<td><? echo $Avion;?></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><a href="../avion_detail.php?avion=<? echo $avion;?>" target="_blank"><? echo $Type;?></a></td>
				<td><? echo $Engagement;?></td>
				<td><? echo $Rating;?></td>
				<td><? echo $Rating_Test;?></td>
				<td><? echo $Vis;?></td>
				<td><? echo $Robustesse;?></td>
				<td><? echo $Blindage;?></td>
				<td><? echo $Autonomie;?></td>
				<td><? echo $Plafond;?></td>
				<td><? echo $VitesseH;?></td>
				<td><? echo $VitesseB;?></td>
				<td><? echo $VitesseA;?></td>
				<td><? echo $VitesseP;?></td>
				<td><? echo $Stab;?></td>
				<td><? echo $Roulis;?></td>
				<td><? echo $ManH;?></td>
				<td><? echo $ManB;?></td>
			</tr>
		<?
		}
	}
	else
		echo "<b>Désolé, aucun avion</b>";
	echo "</table><hr></div>";
}