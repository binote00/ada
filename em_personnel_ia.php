<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$Nbr_Pl=0;
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	include_once('./menu_em_ia.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $GHQ or $Admin)
	{	
		$Pays_q=$country;
		$Unite_Type=GetAvionType($Tab);
		$Coord=GetCoord($Front,$country);
		$Lat_base_min=$Coord[0];
		$Lat_base_max=$Coord[1];
		$Long_base_min=$Coord[2];
		$Long_base_max=$Coord[3];
		echo "<h2>Pilotes ".$Unite_Type."</h2>"; //<div style='overflow:auto; height: 640px;'>
		$table="<div style='overflow:auto; width: 100%;'><table class='table table-striped'><thead><tr><th>Unité</th><th>Nom</th><th>Grade</th><th>Réputation</th><th>Compétence</th></tr></thead>";		
		/*if($Admin)
		{
			//$Pays_q="%";
			$query="SELECT p.ID,p.Nom,p.Unit,p.Avancement,p.Reputation,p.Pays,p.Skill,u.Type,u.Nom,u.Commandant,u.Officier_Adjoint,u.Officier_Technique FROM Pilote_IA as p,Unit as u WHERE u.ID=p.Unit
			AND u.Type='$Tab' ORDER BY u.Pays ASC,u.Type ASC,u.Reputation DESC,u.Nom ASC,p.Avancement DESC,p.Reputation DESC";
		}
		else*/if($GHQ)
		{
			$query="SELECT p.ID,p.Nom,p.Unit,p.Avancement,p.Reputation,p.Pays,p.Skill,u.Type,u.Nom,u.Commandant,u.Officier_Adjoint,u.Officier_Technique FROM Pilote_IA as p,Unit as u WHERE p.Pays='$Pays_q' AND u.ID=p.Unit AND u.Pays='$Pays_q'
			AND p.Actif=1 AND u.Type='$Tab' ORDER BY u.Type ASC,u.Reputation DESC,u.Nom ASC,p.Avancement DESC,p.Reputation DESC";
		}
		else
		{
			$query="SELECT p.ID,p.Nom,p.Unit,p.Avancement,p.Reputation,p.Pays,p.Skill,u.Type,u.Nom,u.Commandant,u.Officier_Adjoint,u.Officier_Technique FROM Pilote_IA as p,Unit as u,Lieu as l WHERE p.Pays='$Pays_q' AND u.ID=p.Unit AND u.Base=l.ID AND u.Pays='$Pays_q'
			AND p.Actif=1 AND u.Type='$Tab' AND l.Latitude >='$Lat_base_min' AND l.Latitude <='$Lat_base_max' AND l.Longitude >='$Long_base_min' AND l.Longitude <'$Long_base_max' ORDER BY u.Type ASC,u.Reputation DESC,u.Nom ASC,p.Avancement DESC,p.Reputation DESC";
		}
		$Rang_skill=1;
		$skill_txt="<optgroup label='Rang 1'>";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		$results=mysqli_query($con,"SELECT * FROM Skills WHERE Categorie=1 AND Rang<5 ORDER BY Rang ASC,Nom ASC");
		mysqli_close($con);
		if($results)
		{
			while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
			{
				if($Rang_skill !=$data['Rang'])
					$skill_txt.="</optgroup><optgroup label='Rang ".$data['Rang']."'>";
				$Rang_skill=$data['Rang'];
				$skill_txt.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
				if(!$data['Infos'])$data['Infos']="Bonus de compétence";
				if($data['Team'])
					$Team_txt="<br>[IA] Une seule compétence nécessaire par escadrille";
				else
					$Team_txt="";
				$data['Infos'].=$Team_txt;
				$skills_txt.="<tr><td><img src='/images/skills/skill".$data['ID'].".png'><br>".$data['Nom']."</td><td>".$data['Rang']."</td><td>".$data['Infos']."</td></tr>";
			}
			mysqli_free_result($results);
		}
		if($result)
		{
			while($Data=mysqli_fetch_array($result))
			{
				$Dispos='';
				$Avancement=GetAvancement($Data['Avancement'],$Data['Pays']);
				$Reputation=GetReputation($Data['Reputation'],$Data['Pays']);
				$table.="<tr><td>".Afficher_Icone($Data[2],$Data['Pays'],$Data['Nom'])."<br>".$Data['Nom']."</td><td>".$Data[1]."</td>
				<td title='".$Avancement[0]."'><img src='images/grades/grades".$Data['Pays'].$Avancement[1].".png'></td><td>".$Reputation."</td><td><img src='images/skills/skill".$Data['Skill'].".png'></td></tr>";
				$Nbr_Pl+=1;
			}
		}
		echo "<div class='row'><div class='col-md-6'><b>Compétence</b> <form action='index.php?view=em_ia_pils' method='post'><select name='skill' class='form-control' style='width: 300px'>".$skill_txt."</optgroup></select><input type='Submit' value='Chercher' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>";
		if($Nbr_Pl)echo "<h3>Total ".$Nbr_Pl."</h3>".$table."</table></div>";
		else echo "<div class='alert alert-warning'>Vous pouvez muter les pilotes entre les différentes unités, avec un minimum de 11 et un maximum de 20 pilotes par unité.
		<br>Le pilote le plus gradé de l'unité apportera un bonus de compétence à tous les autres pilotes de son unité. Il peut donc être utile de répartir les meilleurs pilotes entre les différentes unités.
		<br>Certaines compétences de pilotes sont individuelles et d'autres sont applicables à toute l'unité. Ces dernières sont reconnaissables à la bordure verte entourant l'icône de compétence.</div>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>