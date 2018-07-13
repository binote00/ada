<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$Skill=Insec($_POST['skill']);
	if($Skill >0)
	{
        include_once('./jfv_txt.inc.php');
        include_once('./jfv_inc_em.php');
		$Coord=GetCoord($Front,$country);
		$Lat_base_min=$Coord[0];
		$Lat_base_max=$Coord[1];
		$Long_base_min=$Coord[2];
		$Long_base_max=$Coord[3];
		$con=dbconnecti();
		$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
		$Credits=mysqli_result(mysqli_query($con,"SELECT Credits FROM Officier_em WHERE ID='".$OfficierEMID."'"),0);
		$result=mysqli_query($con,"SELECT p.*,u.Recrutement FROM Pilote_IA as p,Unit as u,Lieu as l WHERE p.Unit=u.ID AND u.Base=l.ID AND p.Pays='$country' AND p.Actif=1 AND p.Skill='$Skill'
		AND l.Latitude >='$Lat_base_min' AND l.Latitude <='$Lat_base_max' AND l.Longitude >='$Long_base_min' AND l.Longitude <'$Long_base_max'");
		$result_s=mysqli_query($con,"SELECT * FROM Skills WHERE Categorie=1 AND Rang<5 ORDER BY Rang ASC");
		if($result_s)
		{
			while($datas=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
			{
				if(!$datas['Infos'])$datas['Infos']='Bonus de compétence';
				if($datas['Team'])
					$Team_txt='<br>[IA] Une seule compétence nécessaire par escadrille';
				else
					$Team_txt='';
				$datas['Infos'].=$Team_txt;
				if(!$skill0_txt and $datas['ID']==$Skill)$skill0_txt=$datas['Infos'];
				$skill_txt.="<tr><td><img src='/images/skills/skill".$datas['ID'].".png'><br>".$datas['Nom']."</td><td>Combat</td><td>".$datas['Rang']."</td><td>".$datas['Infos']."</td></tr>";
			}
			mysqli_free_result($result_s);
		}
		if($result)
		{
			while($Data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$ID=$Data['ID'];
				$Pilote=$Data['Nom'];
				$Avancement=GetAvancement($Data['Avancement'],$country);
				$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='".$Data['Unit']."' AND Actif=1"),0);
                $Chef=mysqli_result(mysqli_query($con,"SELECT ID FROM Pilote_IA WHERE Unit='".$Data['Unit']."' AND Actif=1 ORDER BY Avancement DESC LIMIT 1"),0);
				if((!$Data['Recrutement'] and !$Admin) or $ID ==$Chef)
                    $bouton="<td><span class='btn btn-sm btn-danger'>Réservé</span></td>";
				elseif(($GHQ or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Commandant) and $Credits >=1 and $Pilotes >=12 and !$Data['Cible'] and !$Data['Avion'])
					$bouton="<td><form action='em_ia_pil_move.php' method='post'><input type='hidden' name='id' value='".$ID."'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Muter' class='btn btn-sm btn-primary' onclick='this.disabled=true;this.form.submit();'></form></td>";
				else
					$bouton="<td><span class='btn btn-sm btn-danger'>Réservé</span></td>";
				if($Premium)
				{
					$pilotes_txt.="<tr><td>".$Pilote."</td><td>".Afficher_Icone($Data['Unit'],$country)."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$country.$Avancement[1].".png'></td><td>".$Data['Reputation']."</td><td><a href='#' class='popup'><img src='images/skills/skill".$Data['Skill']."p.png'><span>".$skill0_txt."</span></a></td>".$bouton."<td>"
					.floor($Data['Pilotage'])."</td><td>".floor($Data['Acrobatie'])."</td><td>".floor($Data['Bombardement'])."</td><td>".floor($Data['Tir'])."</td><td>".floor($Data['Tactique'])."</td><td>".floor($Data['Navigation'])."</td><td>".floor($Data['Vue'])."</td><td>"
					.$Data['Moral']."</td><td>".$Data['Courage']."</td><td>".$Data['Missions']."</td><td>".$Data['Victoires']."</td><tr>";
				}
				else
				{
					$Reputation=GetReputation($Data['Reputation'],$country);
					$pilotes_txt.="<tr><td>".$Pilote."</td><td>".Afficher_Icone($Data['Unit'],$country)."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$country.$Avancement[1].".png'></td><td>".$Reputation."</td><td><a href='#' class='popup'><img src='images/skills/skill".$Data['Skill']."p.png'><span>".$skill0_txt."</span></a></td>".$bouton."<td>"
					.GetSkillTxt($Data['Pilotage'])."</td><td>".GetSkillTxt($Data['Acrobatie'])."</td><td>".GetSkillTxt($Data['Bombardement'])."</td><td>".GetSkillTxt($Data['Tir'])."</td><td>".GetSkillTxt($Data['Tactique'])."</td><td>"
					.GetSkillTxt($Data['Navigation'])."</td><td>".GetSkillTxt($Data['Vue'])."</td><td>"
					.GetMoralTxt($Data['Moral'])."</td><td>".GetCourageTxt($Data['Courage'])."</td><td>".$Data['Missions']."</td><td>".$Data['Victoires']."</td><tr>";
				}
			}
			mysqli_free_result($result);
			mysqli_close($con);
			$titre='Pilotes IA';
			$mes="<div style='overflow:auto;'><table class='table table-hover'><thead><tr><th>Nom</th><th>Unité</th><th>Grade</th><th>Reputation</th><th>Compétence</th><th>Action</th>
			<th>Pilotage</th><th>Acrobatie</th><th>Bombardement</th><th>Tir</th><th>Tactique</th><th>Navigation</th><th>Détection</th>
			<th>Moral</th><th>Courage</th><th>Missions</th><th>Victoires</th></tr></thead><tbody>".$pilotes_txt."</tbody></table></div>";
			$img="<img src='images/pilotes".$country.".jpg'>";
			$mes.="<br><form action='index.php?view=em_ia_pils0' method='post'><input type='Submit' value='Nouvelle recherche' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			$mes.="<div class='alert alert-warning'>Vous pouvez muter les pilotes entre les différentes unités, avec un minimum de 11 et un maximum de 20 pilotes par unité.
			<br>Le pilote le plus gradé de l'unité apportera un bonus de compétence à tous les autres pilotes de son unité. Il peut donc être utile de répartir les meilleurs pilotes entre les différentes unités.
			<br>Certaines compétences de pilotes sont individuelles et d'autres sont applicables à toute l'unité. Ces dernières sont reconnaissables à la bordure verte entourant l'icône de compétence.</div>";
			$menu="<h2>Compétences des pilotes</h2><div class='text-left' style='overflow:auto; height:400px;'><table class='table'><thead><tr><th>Compétence</th><th>Catégorie</th><th>Rang</th><th>Description</th></tr></thead>".$skill_txt."</table></div>";
			include_once('./default.php');
		}
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';