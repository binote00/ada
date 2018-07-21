<?
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	include_once('./jfv_include.inc.php');
	if(!$Admin)$Admin=GetData("Joueur","ID",$AccountID,"Admin");
	if($Admin ==1)
	{
		include_once('./jfv_txt.inc.php');
		include_once('./menu_infos.php');
		$Tri=Insec($_POST['Tri']);
		if(!$Tri)$Tri=12;
	?>
	<div style='overflow:auto; width: 100%;'>
	<table class='table'>
		<th colspan="20" class="TitreBleu_bc">Tableau des Avions</th>
		<tr bgcolor="#CDBDA7">
			<th>N°</th>
			<th>Avion</th>
			<th>Pays</th>
			<th>Type</th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="12"><input title="Date" type='Submit' value='Date'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="14"><input title="Rating" type='Submit' value='Rating'></form></th>
			<th>Puissance</th>
			<th>Masse</th>
			<th>Taille</th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="1"><input title="VitesseH" type='Submit' value='VitesseH'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="2"><input title="VitesseB" type='Submit' value='VitesseB'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="3"><input title="VitesseA" type='Submit' value='VitesseA'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="13"><input title="VitesseP" type='Submit' value='VitesseP'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="4"><input title="Plafond" type='Submit' value='Plafond'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="5"><input title="Autonomie" type='Submit' value='Autonomie'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="6"><input title="Stabilité" type='Submit' value='Stabilite'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="7"><input title="Roulis" type='Submit' value='Maniabilite'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="8"><input title="Taux Virage H" type='Submit' value='ManoeuvreH'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="9"><input title="Taux Virage B" type='Submit' value='ManoeuvreB'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="10"><input title="Robustesse" type='Submit' value='Robustesse'></form></th>
			<th><form action='../index.php?view=output_avions' method='post'><input type='hidden' name='Tri' value="11"><input title="Blindage" type='Submit' value='Blindage'></form></th>
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
		$query="SELECT * FROM Avion WHERE Engagement BETWEEN '1940-06-01' AND '1941-01-01' ORDER BY $Tri DESC,Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
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
					<td><a href="../avion_detail.php?avion=<? echo $avion;?>" target="_blank" rel='noreferrer'><? echo $Type;?></a></td>
					<td><? echo $Engagement;?></td>
					<td><? echo $Rating;?></td>
					<td><? echo $Puissance;?></td>
					<td><? echo $Masse;?></td>
					<td><? echo $Vis;?></td>
					<td><? echo $VitesseH;?></td>
					<td><? echo $VitesseB;?></td>
					<td><? echo $VitesseA;?></td>
					<td><? echo $VitesseP;?></td>
					<td><? echo $Plafond;?></td>
					<td><? echo $Autonomie;?></td>
					<td><? echo $Stab;?></td>
					<td><? echo $Roulis;?></td>
					<td><? echo $ManH;?></td>
					<td><? echo $ManB;?></td>
					<td><? echo $Robustesse;?></td>
					<td><? echo $Blindage;?></td>
				</tr>
			<?
			}
		}
		else
			echo "<b>Désolé, aucun avion</b>";
		echo "</table><hr></div>";
	}
}
?>