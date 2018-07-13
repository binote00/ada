<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(!$Admin)$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1)
{
	include_once('./jfv_txt.inc.php');
	include_once('./menu_infos.php');
	$Pays=Insec($_POST['land']);
	$Type=Insec($_POST['type']);
	$Tri=Insec($_POST['Tri']);
	if(!$Tri)$Tri=14;
	if($Pays == "all")
		$Pays="%";
	if($Type == "all")
		$Type="%";
?>
<div style='overflow:auto; width: 100%;'>
<table class='table'><thead><tr>
		<th>N°</th>
		<th>Avion</th>
		<th>Pays</th>
		<th>Type</th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="12"><input title="Date" type='Submit' value='Date'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="14"><input title="Rating" type='Submit' value='Rating'></form></th>
		<th>Prod</th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="10"><input title="Robustesse" type='Submit' value='HP'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="11"><input title="Blindage" type='Submit' value='Armor'></form></th>
		<th>Taille</th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="5"><input title="Autonomie" type='Submit' value='Range'></form></th>
		<th>Armes</th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="4"><input title="Plafond" type='Submit' value='Ceiling'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="1"><input title="VitesseH" type='Submit' value='VitH'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="2"><input title="VitesseB" type='Submit' value='VitB'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="3"><input title="VitesseA" type='Submit' value='VitA'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="13"><input title="VitesseP" type='Submit' value='VitP'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="6"><input title="Stabilité" type='Submit' value='Stab'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="7"><input title="Roulis" type='Submit' value='Mani'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="8"><input title="Taux Virage H/B" type='Submit' value='ManH/B'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="15"><input title="Radio" type='Submit' value='Radio'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="16"><input title="Nav" type='Submit' value='Nav'></form></th>
		<th><form action='index.php?view=output_avions' method='post'><input type='hidden' name='land' value="all"><input type='hidden' name='type' value="all"><input type='hidden' name='Tri' value="17"><input title="Radar" type='Submit' value='Radar'></form></th>
	</tr></thead>
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
	case 15:
		$Tri="Radio";
	break;
	case 16:
		$Tri="Navigation";
	break;
	case 17:
		$Tri="Radar";
	break;
}
	$i=0;
	if($Pays >9 or $Pays ==1)
		$query="SELECT * FROM Avion WHERE Pays='$Pays' AND Type LIKE '$Type' ORDER BY $Tri DESC, Nom ASC";
	else
		$query="SELECT * FROM Avion WHERE Pays LIKE '$Pays' AND Type LIKE '$Type' ORDER BY $Tri DESC, Nom ASC";
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
			$Engagement=$data['Engagement']."<br>".$data['Fin_Prod'];
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
			if($data['Premium'])
				$Premium_txt="<div class='i-flex premium20'></div> ";
			else
				$Premium_txt="";
			$Arme_txt=$data['Arme1_Nbr']."x ".round(GetData("Armes","ID",$data['ArmePrincipale'],"Calibre"));
			if($data['Arme2_Nbr'])$Arme_txt.="<br>".$data['Arme2_Nbr']."x ".round(GetData("Armes","ID",$data['ArmeSecondaire'],"Calibre"));
			if($data['Bombe_Nbr'])$Arme_txt.="<br>".$data['Bombe_Nbr']."x ".$data['Bombe'];
			$Autonomie_IA=floor(($data['Autonomie']/2)-200);
			if($Autonomie_IA <50)$Autonomie_IA=50;
			if($data['Type'] ==2 or $data['Type'] ==7 or $data['Type'] ==10 or $data['Type'] ==11)
			{
				$Massef_s=$data['Masse']+($data['Bombe']*$data['Bombe_Nbr']);
				$Massef_t=$data['Masse']+$data['Bombe'];
				$Poids_Puiss_ori=$data['Masse']/$data['Puissance'];
				$Poids_Puiss_s=$Massef_s/$data['Puissance'];
				$Poids_Puiss_t=$Massef_t/$data['Puissance'];
				if($data['Type'] ==2 or $data['Type'] ==11)
					$Autonomie_s=round($data['Autonomie']-(($Poids_Puiss_s-$Poids_Puiss_ori)*($Massef_s/10)));
				else
					$Autonomie_s=round(($data['Autonomie']/2)-(($Poids_Puiss_s-$Poids_Puiss_ori)*($Massef_s/10)));
				$Autonomie_t=round(($data['Autonomie']/2)-(($Poids_Puiss_t-$Poids_Puiss_ori)*($Massef_t/10)));
			}
			$Autonomie.="<br>".$Autonomie_IA." IA<br>".$Autonomie_s." Strat<br>".$Autonomie_t." Tac";
			//$Rating_Reco=(((($Autonomie*2) + $Plafond + ($data['Detection']*500) + ($Stab*10) + ($VitesseH*10) + ($VitesseB*10) + ($ManH*10) + ($ManB*10) + ($VitesseP*2) + $Robustesse)/1000)-30)/2;
			//$Rating_Pat_Mar=(((($Autonomie*2) + ($data['Radar']*1000) + ($data['Detection']*500) + ($Stab*5) + ($VitesseH*5) + ($VitesseB*5) + ($Blindage*100) + $Robustesse)/2000)-8)/2;
			$Avion_img=GetAvionIcon($avion,$Pays,0,0,0,$Nom,true);
			$i++;
			echo "<tr><td>".$i."</td>";			
		?>
				<td><? echo $Avion_img;?></td>
				<td><img src='<? echo $Pays;?>20.gif'></td>
				<td><a href="avion_detail.php?avion=<? echo $avion;?>" target="_blank"><? echo $Type;?></a></td>
				<td><? echo $Engagement;?></td>
				<td><? echo $Rating;?></td>
				<th><? echo $Premium_txt.$data['Production'];?></th>
				<td><? echo $Robustesse;?></td>
				<td><? echo $Blindage;?></td>
				<td><? echo $Vis;?></td>
				<td><? echo $Autonomie;?></td>
				<td><?echo $Arme_txt;?></td>
				<td><? echo $Plafond;?></td>
				<td><? echo $VitesseH;?></td>
				<td><? echo $VitesseB;?></td>
				<td><? echo $VitesseA;?></td>
				<td><? echo $VitesseP;?></td>
				<td><? echo $Stab;?></td>
				<td><? echo $Roulis;?></td>
				<td><? echo $ManH." / ".$ManB;?></td>
				<td><?echo $data['Radio'];?></td>
				<td><?echo $data['Navigation'];?></td>
				<td><?echo $data['Radar'];?></td>
			</tr>
		<?
		}
	}
	else
		echo "<b>Désolé, aucun avion</b>";
	echo "</table><hr></div>";
}
?>