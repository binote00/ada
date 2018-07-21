<?php
/*require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{*/
    include_once '../jfv_include.inc.php';
	$country=$_SESSION['country'];
	if(!$country)$country=4;
    include_once __DIR__ . '/../view/menu_infos.php';
    $con=dbconnecti();
	$result_s=mysqli_query($con,"SELECT * FROM Skills WHERE Categorie<6 ORDER BY Categorie ASC,Rang ASC,Nom ASC");
	mysqli_close($con);
	if($result_s)
	{
		while($datas=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
		{
			$Prereq_txt=false;
			$Vers_txt=false;
			if($datas['Categorie']==1)
				$Cat='Combat';
			elseif($datas['Categorie']==2)
				$Cat='Gestion';
			elseif($datas['Categorie']==3)
				$Cat='Commandement';
			elseif($datas['Categorie']==4)
				$Cat='Renseignement';
			elseif($datas['Categorie']==5)
				$Cat='Expert';
			elseif($datas['Categorie']==6)
				$Cat='Cheat';
			if($datas['Prereq1'])
				$Prereq_txt="<img src='/images/skills/skill".$datas['Prereq1'].".png'>";
			if($datas['Prereq2'])
				$Prereq_txt.="<img src='/images/skills/skill".$datas['Prereq2'].".png'>";
			if($datas['Exclusif'])$Vers_txt='Oui';
			if(!$datas['Infos'])$datas['Infos']='Bonus de compétence';
			if($datas['Team'])
				$Team_txt='<br>[IA] Une seule compétence nécessaire par escadrille';
			else
				$Team_txt='';
			if($datas['ID'] ==120)$datas['ID'].=$country;
			$skill_txt.="<tr><td><img src='images/skills/skill".$datas['ID'].".png'><br>".$datas['Nom']."</td><td>".$Cat."</td><td>".$datas['Rang']."</td>
			<th>".$Prereq_txt."</th><th>".$Vers_txt."</th><td>".$datas['Infos'].$Team_txt."</td></tr>";
		}
		mysqli_free_result($result_s);
	}
	$titre='Compétences des pilotes';
	$mes="<div class='row'><div class='col-md-4'>".Afficher_Image('images/pilotes'.$country.'.jpg','','',75)."</div><div class='col-md-8'><div class='text-left' style='overflow:auto; height:640px;'>
	<table class='table'><thead><tr><th>Compétence</th><th>Catégorie</th><th>Rang</th><th>Prérequis</th><th>Exclusif</th><th>Description</th></tr></thead>".$skill_txt."</table></div></div></div>";
	include_once '../default.php';
/*}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';*/