<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$Rang_skill=1;
	$skill_txt="<optgroup label='Rang 1'>";
	$con=dbconnecti();
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
	$titre="Pilotes IA";
	$mes.="<div class='row'><div class='col-md-6'><h2>Recherche de pilotes</h2>Compétence <form action='index.php?view=em_ia_pils' method='post'><select name='skill' class='form-control' style='width: 300px'>".$skill_txt."</optgroup></select>
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div>
	<div class='col-md-6'><h2>Compétences des pilotes</h2><div class='text-left' style='overflow:auto; height:400px;'><table class='table'><thead><tr><th>Compétence</th><th>Rang</th><th>Description</th></tr></thead>".$skills_txt."</table></div><div>";
	$img="<img src='images/pilotes".$country.".jpg'>";
	include_once('./default.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>