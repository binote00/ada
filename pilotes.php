<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
//include_once('./menu_classement.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0 or $Admin)
{
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$Tri=Insec($_POST['Tri']);
		if(!$Tri)$Tri=9;
		?>
		<h1>Pilotes</h1>
		<a href='index.php?view=medals' class='btn btn-primary'>Décorations</a> <a href='index.php?view=missions' class='btn btn-primary'>Missions</a>
		<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
			<thead><tr><th>N°</th><th>Nom</th><th>Pays</th><th>Unité</th>
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="10"><input type='Submit' value='Grade'></form></th>
				<!--<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="1"><input type='Submit' value='Acrobatie'></form></th>
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="2"><input type='Submit' value='Adresse au Tir'></form></th>
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="3"><input type='Submit' value='Bombarder'></form></th>
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="4"><input type='Submit' value='Détection'></form></th>
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="5"><input type='Submit' value='Gestion'></form></th>
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="6"><input type='Submit' value='Navigation'></form></th>
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="7"><input type='Submit' value='Pilotage'></form></th>
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="8"><input type='Submit' value='Tactique'></form></th>-->
				<th><form action='index.php?view=pilotes' method='post'><input type='hidden' name='Tri' value="9"><input type='Submit' value='Reputation'></form></th>
				<?if($PlayerID ==1){?><th>Points</th><th>XP</th><?}?>
			</tr></thead>
		<?
		switch($Tri)
		{
			/*case 1:
				$Tri="Acrobatie";
			break;
			case 2:
				$Tri="Tir";
			break;
			case 3:
				$Tri="Bombardement";
			break;
			case 4:
				$Tri="Vue";
			break;
			case 5:
				$Tri="Gestion";
			break;
			case 6:
				$Tri="Navigation";
			break;
			case 7:
				$Tri="Pilotage";
			break;
			case 8:
				$Tri="Tactique";
			break;*/
			case 10:
				$Tri="Avancement";
			break;
			default:
				$Tri="Reputation";
			break;
		}
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT ID,Nom,Pays,Unit,Pilotage,Navigation,Tir,Vue,Acrobatie,Bombardement,Tactique,Gestion,Avancement,Reputation,Skill_Pts,Exp_Pts
		FROM Pilote WHERE Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() AND $Tri >10 ORDER BY $Tri DESC LIMIT 100");
		mysqli_close($con);
		if($result)
		{
			$i=1;
			while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$Grade=GetAvancement($data['Avancement'],$data['Pays']);
				if($PlayerID ==1)
				{
					/*if($data['Avancement'] >$data['Reputation'])
						$Level=$data['Avancement'];
					else
						$Level=$data['Reputation'];
					$Level=floor($Level/5000);*/
					echo "<tr><td>".$i."</td><td align='left'><a href='user_public.php?Pilote=".$data['ID']."' target='_blank' class='lien'>".$data['Nom']."</a></td>
						<td><img src='".$data['Pays']."20.gif'></td>
						<td>".Afficher_Icone($data['Unit'],$data['Pays'],GetData('Unit','ID',$data['Unit'],'Nom'))."</td>
						<td><img title='".$Grade[0]."(".$data['Avancement'].")' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td>
						<td>".$data['Reputation']."</td><td>".$data['Skill_Pts']."</td><td>".$data['Exp_Pts']."</td></tr>";
						/*<td>".round($data['Acrobatie'],2)."</td>
						<td>".round($data['Tir'],2)."</td>
						<td>".round($data['Bombardement'],2)."</td>
						<td>".round($data['Vue'],2)."</td>
						<td>".round($data['Gestion'],2)."</td>
						<td>".round($data['Navigation'],2)."</td>
						<td>".round($data['Pilotage'],2)."</td>
						<td>".round($data['Tactique'],2)."</td>*/
				}
				else
				{
					echo "<tr><td>".$i."</td><td align='left'><a href='user_public.php?Pilote=".$data['ID']."' target='_blank' class='lien'>".$data['Nom']."</a></td>
						<td><img src='".$data['Pays']."20.gif'></td>
						<td>".Afficher_Icone($data['Unit'],$data['Pays'],GetData('Unit','ID',$data['Unit'],'Nom'))."</td>
						<td><img title='".$Grade[0]."' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td>
						<td>".GetReputation($data['Reputation'],$data['Pays'])."</td></tr>";
						/*<td>".GetSkillTxt($data['Acrobatie'])."</td>
						<td>".GetSkillTxt($data['Tir'])."</td>
						<td>".GetSkillTxt($data['Bombardement'])."</td>
						<td>".GetSkillTxt($data['Vue'])."</td>
						<td>".GetSkillTxt($data['Gestion'])."</td>
						<td>".GetSkillTxt($data['Navigation'])."</td>
						<td>".GetSkillTxt($data['Pilotage'])."</td>
						<td>".GetSkillTxt($data['Tactique'])."</td>*/
				}
				$i++;
			}
		}
		echo "</table></div>";
	}
	else
	{
		echo "<table class='table'>
			<tr><td><img src='images/acces_premium.png'></td></tr>
			<tr><td>Ces statistiques sont réservées aux utilisateurs Premium</td></tr>
		</table>";
	}
}
?>