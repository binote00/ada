<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	/*include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	include_once('./menu_classement.php');
	echo "<div style='overflow:auto; width: 100%;'><table class='table'><tr><th colspan='17' bgcolor='lightyellow'>Les prudents</th></tr>
	<tr class='TitreBleu_bc'><th>N°</th><th>Unité</th><th width='150px'>Nom</th><th>Grade</th><th title='Total des décollages pour des missions de combat'>Décollages</th><th title='Total des combats'>Combats</th>
	<th title='Nombre de fois abattu en vol'>Abattu</th><th title='Nombre de fois blessé'>Blessé</th><td bgcolor='black'></td>
	<th title='Ratio de survie au combat'>Survie</th><th title='Ratio Missions'>Missions</th><th title='Ratio MIA'>MIA</th><th style='color:red;' title='Ratio Pertes'>Pertes</th><th style='color:red;' title='Ratio Pannes sèches'>Pannes</th>
	<th style='color:red;' title='Ratio crashs au décollage'>Crash DEC</th><th title='Ratio crashs à l'atterrissage'>Crash AT</th><th>Rating</th></tr>";
	$i=0;	
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT * FROM Pilote WHERE Actif=0 AND Pays='$country' AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() ORDER BY Avancement DESC LIMIT 200");
	mysqli_close($con);	
	if($result)
	{
		while($Data=mysqli_fetch_array($result))
		{
			$green=0;
			$red=0;
			$Joueur=$Data['ID'];
			$con=dbconnecti(4);
			$Decollages=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=38 AND PlayerID='$Joueur'"),0);
			if($Decollages)
			{
				$Combats=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=1 AND PlayerID='$Joueur'"),0);
				$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=3 AND PlayerID='$Joueur'"),0);
				$Pannes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=4 AND PlayerID='$Joueur'"),0);
				//$Attaques=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type IN(5,6,10) AND PlayerID='$Joueur'"),0);
				$Blesse=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=9 AND PlayerID='$Joueur'"),0);
				$Crash_deco=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=11 AND PlayerID='$Joueur'"),0);
				$Crash_att=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=12 AND PlayerID='$Joueur'"),0);
				$Perdu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=34 AND PlayerID='$Joueur'"),0);
				$MIA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=35 AND PlayerID='$Joueur'"),0);
			}
			mysqli_close($con);
			if($Decollages)
			{
				if($Joueur ==1)$Combats+=1000;
				$i++;
				$Avancement=GetAvancement($Data['Avancement'],$Data['Pays_Origine']);
				$Ratio=GetRatio($Data['ID']);
				if($Ratio[0] >0.3)
				{
					$Ratio[0]="<font color='red'><b>".$Ratio[0]."</b></font>";
					$red += 1;
				}
				elseif($Ratio[0] <0.03)
				{
					$Ratio[0]="<font color='green'><b>".$Ratio[0]."</b></font>";
					$green += 1;
				}
				if($Abattu + $Perdu >0)
				{
					$Rating2=round($Combats / ($Abattu + $Perdu));
					if($Rating2 >10)
					{
						$Rating2="<font color='green'><b>".$Rating2."</b></font>";
						$green += 1;
					}
					elseif($Rating2 <2)
					{
						$Rating2="<font color='red'><b>".$Rating2."</b></font>";
						$red += 1;
					}
				}
				if($Decollages)
				{
					$Ratio_pannes=round($Pannes/$Decollages,2);
					if($Ratio_pannes >0.1)
					{
						$Ratio_pannes="<font color='red'><b>".$Ratio_pannes."</b></font>";
						$red += 1;
					}
					elseif($Ratio_pannes <0.02)
					{
						$Ratio_pannes="<font color='green'><b>".$Ratio_pannes."</b></font>";
						$green += 1;
					}
					$Ratio_MIA=round($MIA/$Decollages,2);
					if($Ratio_MIA >0.2)
					{
						$Ratio_MIA="<font color='red'><b>".$Ratio_MIA."</b></font>";
						$red += 1;
					}
					elseif($Ratio_MIA <0.05)
					{
						$Ratio_MIA="<font color='green'><b>".$Ratio_MIA."</b></font>";
						$green += 1;
					}
					$Ratio_Perdu=round($Perdu/$Decollages,2);
					if($Ratio_Perdu >0.1)
					{
						$Ratio_Perdu="<font color='red'><b>".$Ratio_Perdu."</b></font>";
						$red += 1;
					}
					elseif($Ratio_Perdu <0.02)
					{
						$Ratio_Perdu="<font color='green'><b>".$Ratio_Perdu."</b></font>";
						$green += 1;
					}
					$Ratio_Deco=round($Crash_deco/$Decollages,2);
					if($Ratio_Deco >0.05)
					{
						$Ratio_Deco="<font color='red'><b>".$Ratio_Deco."</b></font>";
						$red += 1;
					}
					elseif($Ratio_Deco <0.02)
					{
						$Ratio_Deco="<font color='green'><b>".$Ratio_Deco."</b></font>";
						$green += 1;
					}
					$Ratio_Att=round($Crash_att/$Decollages,2);
					if($Ratio_Att >0.2)
					{
						$Ratio_Att="<font color='red'><b>".$Ratio_Att."</b></font>";
						$red += 1;
					}
					elseif($Ratio_Att <0.05)
					{
						$Ratio_Att="<font color='green'><b>".$Ratio_Att."</b></font>";
						$green += 1;
					}
					$Bonus_Malus=$green - $red;
					if($Bonus_Malus >4)
						$Bonus_Malus="<font color='green'><b>".$Bonus_Malus."</b></font>";
					elseif($Bonus_Malus <0)
						$Bonus_Malus="<font color='red'><b>".$Bonus_Malus."</b></font>";
				}
				echo "<tr><td>".$i."</td><td><img src='images/unit/unit".$Data['Unit']."p.gif'></td><td><a href='user_public.php?Pilote=".$Data['ID']."' target='_blank'>".$Data['Nom']."</a></td>
				<td title='".$Avancement[0]."'><img src='images/grades/grades".$Data['Pays_Origine'].$Avancement[1].".png'></td><td>".$Decollages."</td><td>".$Combats."</td><td>".$Abattu."</td><td>".$Blesse."</td><td bgcolor='black'></td>
				<td>".$Rating2."</td><td>".$Ratio[0]."</td><td>".$Ratio_MIA." (".$MIA.")</td><td>".$Ratio_Perdu." (".$Perdu.")</td><td>".$Ratio_pannes." (".$Pannes.")</td>
				<td>".$Ratio_Deco." (".$Crash_deco.")</td><td>".$Ratio_Att." (".$Crash_att.")</td><td bgcolor='lightyellow'>".$Bonus_Malus."</td><tr>";
			}
		}
		echo "<tr class='TitreBleu_bc'><th>N°</th><th>Unité</th><th width='150px'>Nom</th><th>Grade</th><th>Décollages</th><th>Combats</th><th>Abattu</th><th>Blessé</th><td bgcolor='black'></td>
		<th>Survie</th><th>Mission</th><th>MIA</th><th>Pertes</th><th title='Pannes sèches'>Pannes</th>
		<th title='décollage'>Crash DEC</th><th title='Atterrissage'>Crash AT</th><th>Rating</th></tr></table>";
	}
	else
		echo "Désolé, aucun pilote actif.";*/
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>