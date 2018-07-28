<?
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_as_des_as.php');
$i=0;
$PlayerID=$_SESSION['PlayerID'];
echo "<h2>Tableau des As</h2><table class='table table-striped'>
	<thead><tr>
		<th>N°</th>
		<th>Pilote</th>
		<th>Unité</th>
		<th>Grade</th>
		<th>Pays</th>
		<th>Niveau</th>
		<th>Missions</th>
		<th title='Victoires confirmées'>Victoires</th>
		<th>Défaites</th>
		<th>Ratio Score</th>
		<th title='Score'>Kill Score</th>
		<th title='Avion le plus souvent utilisé par le pilote'>Avion</th></tr></thead>";
//(SELECT IF(Victories>Defaites,Victories - Defaites,0)) AS Diff,	
$query="SELECT ID,Nom,Pays,Unit,Avancement,As_Missions,
(SELECT COUNT(*) FROM aubedesaiglesnet6.Chasse_sandbox as c WHERE c.Joueur_win=Joueur.ID AND c.PVP=0) AS Victories,
(SELECT COUNT(*) FROM aubedesaiglesnet6.Chasse_sandbox as c WHERE c.Pilote_loss=Joueur.ID AND c.PVP=1) AS Defaites,
(SELECT Victories - Defaites) AS Diff,
(SELECT IF(Defaites>0,Victories/Defaites,Victories)) AS Ratio_base,
(SELECT (Ratio_base * 100) + Victories + Diff) AS Ratio
FROM Joueur WHERE Joueur.As_Missions > 0 AND Joueur.Actif=0 AND Joueur.Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY Ratio DESC, Victories DESC, Defaites ASC, As_Missions ASC LIMIT 10";
$con=dbconnecti();
$result=mysqli_query($con, $query);
mysqli_close($con);
/*(SELECT COUNT(*) FROM aubedesaiglesnet6.Chasse_sandbox as c WHERE c.Joueur_win=Joueur.ID AND c.Pilote_loss=4 AND c.PVP=0) AS Novices,
(SELECT COUNT(*) FROM aubedesaiglesnet6.Chasse_sandbox as c WHERE c.Joueur_win=Joueur.ID AND c.Pilote_loss=147 AND c.PVP=0) AS Inconnus,
(SELECT COUNT(*) FROM aubedesaiglesnet6.Chasse_sandbox as c WHERE c.Joueur_win=Joueur.ID AND c.Pilote_loss=148 AND c.PVP=0) AS Officiers,
(SELECT COUNT(*) FROM aubedesaiglesnet6.Chasse_sandbox as c WHERE c.Joueur_win=Joueur.ID AND c.Pilote_loss=149 AND c.PVP=0) AS Veterans,
(SELECT COUNT(*) FROM aubedesaiglesnet6.Chasse_sandbox as c WHERE c.Joueur_win=Joueur.ID AND c.Pilote_loss=150 AND c.PVP=0) AS Experts,
(SELECT COUNT(*) FROM aubedesaiglesnet6.Chasse_sandbox as c WHERE c.Joueur_win=Joueur.ID AND c.Pilote_loss=460 AND c.PVP=0) AS Aces,*/
if($result)
{
	while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
			$ID=$data['ID'];
			$Unit=$data['Unit'];
			if($data['Victories'] or $data['Defaites'] or $data['As_Missions'])
			{						
				$Pays=$data['Pays'];
				$Avion_id=0;
				$Score=0;
				//$Score=$data['Novices'] + ($data['Inconnus'] * 2.5) + ($data['Officiers'] * 3.5) + ($data['Veterans'] * 5) + ($data['Experts'] * 6) + ($data['Aces'] * 7);
				$con=dbconnecti();
				$Avion_result=mysqli_query($con,"SELECT Avion_win,COUNT(*) AS nb FROM aubedesaiglesnet6.Chasse_sandbox WHERE Joueur_win='$ID' GROUP BY Avion_win ORDER BY nb DESC,Date DESC LIMIT 1");
				$Liste_kills=mysqli_query($con,"SELECT c.Pilote_loss,a.* FROM aubedesaiglesnet6.Chasse_sandbox as c, Avion as a WHERE c.Joueur_win='$ID' AND c.PVP=0 AND c.Avion_loss=a.ID");
				$Unite=mysqli_result(mysqli_query($con,"SELECT Nom FROM Unit WHERE ID='$Unit'"),0);
				mysqli_close($con);
				if($Avion_result)
				{
					while($data_avion=mysqli_fetch_array($Avion_result,MYSQLI_ASSOC))
					{
						$Avion_id=$data_avion['Avion_win'];
					}
					mysqli_free_result($Avion_result);
				}
				if($Liste_kills)
				{
					while($data_kills=mysqli_fetch_array($Liste_kills,MYSQLI_ASSOC))
					{
						$Calibre1=GetData("Armes","ID",$data_kills['ArmePrincipale'],"Calibre")*20;
						$Calibre2=GetData("Armes","ID",$data_kills['ArmeSecondaire'],"Calibre")*20;
						$kills_score=(($data_kills['ManoeuvreH']*10) + ($data_kills['ManoeuvreB']*10) + ($data_kills['Maniabilite']*20) + ($data_kills['Robustesse']*2) + ($data_kills['Detection']*20) 
						+ ($data_kills['VitesseH']*2) + ($data_kills['VitesseB']*2) + $data_kills['VitesseA'] + $data_kills['VitesseP'] 
						+ ($data_kills['Blindage']*10) + ($data_kills['Plafond']/10) + ($data_kills['Arme1_Nbr']*$Calibre1) + ($data_kills['Arme2_Nbr']*$Calibre2))/100;
						if($data_kills['Pilote_loss'] ==4)
							$Score +=$kills_score;
						elseif($data_kills['Pilote_loss'] ==147)
							$Score +=($kills_score*2.5);
						elseif($data_kills['Pilote_loss'] ==148)
							$Score +=($kills_score*3.5);
						elseif($data_kills['Pilote_loss'] ==149)
							$Score +=($kills_score*5);
						elseif($data_kills['Pilote_loss'] ==150)
							$Score +=($kills_score*6);
						elseif($data_kills['Pilote_loss'] ==460)
							$Score +=($kills_score*7);
						else
							$Score +=($kills_score*8);
					}
					mysqli_free_result($Liste_kills);
				}
				//$Level=1+(floor($Score/5000));
				$As_Missions=GetData("Joueur","ID",$ID,"As_Missions");
				if($Score > 10000000 and $As_Missions > 50000)
					$Level=10;
				elseif($Score > 5000000 and $As_Missions > 25000)
					$Level=9;
				elseif($Score > 1000000 and $As_Missions > 10000)
					$Level=8;
				elseif($Score > 500000 and $As_Missions > 5000)
					$Level=7;
				elseif($Score > 200000 and $As_Missions > 2000)
					$Level=6;
				elseif($Score > 100000 and $As_Missions > 1000)
					$Level=5;
				elseif($Score > 50000 and $As_Missions > 500)
					$Level=4;
				elseif($Score > 20000 and $As_Missions > 250)
					$Level=3;
				elseif($Score > 10000 and $As_Missions > 100)
					$Level=2;
				else
					$Level=1;
				if($Avion_id)
					$Avion=GetAvionIcon($Avion_id,$Pays);
				else
					$Avion="";
				$Grade=GetAvancement($data['Avancement'],$data['Pays']);
				$Avion_unit_img="images/unit/unit".$data['Unit']."p.gif";
				if(is_file($Avion_unit_img))
					$Unite="<img src='".$Avion_unit_img."' title='".$Unite."'>";				
				$i++;					
				if($ID == $PlayerID)
					echo "<tr bgcolor='LightYellow'>";
				else
					echo "<tr>";
				echo "<td>".$i."</td>";				
				$Ratio=round($data['Ratio'],2);
				echo "<td><a href='user_public.php?Pilote=".$ID."' target='_blank' class='lien'>".$data['Nom']."</a></td><td>".$Unite."</td>
				<td><img title='".$Grade[0]."' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td><td><img src='".$Pays."20.gif'></td>
				<td>".$Level."</td><td>".$data['As_Missions']."</td><td>".$data['Victories']."</td><td>".$data['Defaites']."</td><td>".$Ratio."</td><td>".round($Score)."</td>
				<td>".$Avion."</td></tr>";
			}
	}
}
else
	echo "<b>Désolé, aucun as n'a encore émergé dans cette campagne</b>";
echo "<tr><th>N°</th>
		<th>Pilote</th>
		<th>Unité</th>
		<th>Grade</th>
		<th>Pays</th>
		<th>Niveau</th>
		<th>Missions</th>
		<th title='Victoires confirmées'>Victoires</th>
		<th>Défaites</th>
		<th>Ratio Score</th>
		<th>Kill Score</th>
		<th title='Avion le plus souvent utilisé par le pilote'>Avion</th></tr>
	</table></div>";
?>