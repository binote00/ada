<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];	
	$con=dbconnecti();	
	$result=mysqli_query($con,"SELECT ID,Nom,Pays,Front,Unit,Avancement,Pilotage,Navigation,Tir,Vue,Acrobatie,Bombardement,Tactique,Gestion FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Front=$data['Front'];
			$Avancement=$data['Avancement'];
			$Grade=GetAvancement($Avancement,$data['Pays']);
			$Plyr="<tr><th>".$data['Nom']."</th>
			<td><img title='".$Grade[0]."' src='images/grades/grades".$data['Pays'].$Grade[1].".png'></td>
			<td><img src='images/unit/unit".$data['Unit']."p.gif'></td>
			<td>".GetSkillTxt($data['Acrobatie'])."</td>
			<td>".GetSkillTxt($data['Tir'])."</td>
			<td>".GetSkillTxt($data['Bombardement'])."</td>
			<td>".GetSkillTxt($data['Vue'])."</td>
			<td>".GetSkillTxt($data['Gestion'])."</td>
			<td>".GetSkillTxt($data['Navigation'])."</td>
			<td>".GetSkillTxt($data['Pilotage'])."</td>
			<td>".GetSkillTxt($data['Tactique'])."</td></tr>";				
		}
		mysqli_free_result($result);
	}
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');	
	if($Avancement >999)
	{			
		if($PlayerID ==1 or $PlayerID ==2)
		{
			$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Pays,Pilote.Unit,Pilote.Pilotage,Pilote.Navigation,Pilote.Tir,Pilote.Vue,Pilote.Acrobatie,Pilote.Bombardement,Pilote.Tactique,Pilote.Gestion,Pilote.Avancement,Unit.Nom 
			FROM Pilote,Unit WHERE Unit.ID=Pilote.Unit AND Pilote.Ecole >0 AND Pilote.Actif=0 AND Pilote.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
			ORDER BY Pilote.Avancement DESC, Pilote.Pilotage DESC";
		}
		else
		{
			$query="SELECT Pilote.ID,Pilote.Nom,Pilote.Pays,Pilote.Unit,Pilote.Pilotage,Pilote.Navigation,Pilote.Tir,Pilote.Vue,Pilote.Acrobatie,Pilote.Bombardement,Pilote.Tactique,Pilote.Gestion,Pilote.Avancement,Unit.Nom 
			FROM Pilote,Unit WHERE Pilote.Pays='$country' AND Unit.ID=Pilote.Unit AND Pilote.Ecole >0 AND Pilote.Actif=0 AND Pilote.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
			ORDER BY Pilote.Avancement DESC, Pilote.Pilotage DESC";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			echo "<h2>Pilotes disponibles pour une formation</h2><table class='table table-striped'>
			<thead><tr><th width='150px'>Nom</th><th>Grade</th><th>Unité</th><th>Acrobatie</th><th>Tir</th><th>Bombardement</th><th>Détection</th><th>Gestion</th><th>Navigation</th><th>Pilotage</th><th>Tactique</th></tr></thead>";
			while($data=mysqli_fetch_array($result))
			{
				$Avancement=GetAvancement($data['Avancement'],$data['Pays']);			
				echo "<tr><td><a href='user_public.php?Pilote=".$data['ID']."' style='color:#4171ac;' target='_blank'>".$data[1]."</a></td>
				<td><img title='".$Avancement[0]."' src='images/grades/grades".$data['Pays'].$Avancement[1].".png'></td>
				<td>".Afficher_Icone($data['Unit'],$data['Pays'],$data['Nom'])."</td>
				<td>".GetSkillTxt($data['Acrobatie'])."</td>
				<td>".GetSkillTxt($data['Tir'])."</td>
				<td>".GetSkillTxt($data['Bombardement'])."</td>
				<td>".GetSkillTxt($data['Vue'])."</td>
				<td>".GetSkillTxt($data['Gestion'])."</td>
				<td>".GetSkillTxt($data['Navigation'])."</td>
				<td>".GetSkillTxt($data['Pilotage'])."</td>
				<td>".GetSkillTxt($data['Tactique'])."</td></tr>";				
			}
			echo "</table>";
			echo "<h2>Votre pilote</h2><table class='table table-striped'>
				<thead><tr><th width='150px'>Nom</th><th>Grade</th><th>Unité</th><th>Acrobatie</th><th>Tir</th><th>Bombardement</th><th>Détection</th><th>Gestion</th><th>Navigation</th><th>Pilotage</th><th>Tactique</th></tr></thead>
				".$Plyr."</table>";
		}
		else
			echo "<h6>Désolé, aucun pilote n'est disponible pour une formation.</h6>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>