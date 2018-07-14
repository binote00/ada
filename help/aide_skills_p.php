<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Skill_Pts,Skill_Cat FROM Pilote WHERE ID='$PlayerID'");
	$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
	$result_s=mysqli_query($con,"SELECT * FROM Skills WHERE Categorie <10 ORDER BY Categorie ASC,Rang ASC");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Skill_Pts=$data['Skill_Pts'];
			$Skill_Cat=$data['Skill_Cat'];
		}
		mysqli_free_result($result);
	}
	if($results)
	{
		while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
		{
			$Skills_Pil[]=$data['Skill'];
		}
		mysqli_free_result($results);
	}
	if($result_s)
	{
		while($datas=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
		{
			$Prereq_txt=false;
			$Vers_txt=false;
			if($Skill_Cat ==$datas['Categorie'])
				$CT1="<td class='btn btn-primary'>".$datas['Rang'];
			else
				$CT1="<td class='btn btn-primary'>".$datas['Rang']*2;		
			if(is_array($Skills_Pil))
			{
				if(in_array($datas['ID'],$Skills_Pil))
					$CT1="<td class='btn btn-danger'>0";
			}
			if($datas['Categorie']==1)
				$Cat="Combat";
			elseif($datas['Categorie']==2)
				$Cat="Gestion";
			elseif($datas['Categorie']==3)
				$Cat="Commandement";
			elseif($datas['Categorie']==4)
				$Cat="Renseignement";
			elseif($datas['Categorie']==5)
				$Cat="Expert";
			elseif($datas['Categorie']==6)
				$Cat="Premium";
			if($datas['Prereq1'])
				$Prereq_txt="<img src='/images/skills/skill".$datas['Prereq1'].".png'>";
			if($datas['Prereq2'])
				$Prereq_txt.="<img src='/images/skills/skill".$datas['Prereq2'].".png'>";
			if($datas['Exclusif'])
				$Vers_txt="Oui";
			if(!$datas['Infos'])$datas['Infos']="Bonus de compétence";
			$skill_txt.="<tr><td><img src='/images/skills/skill".$datas['ID'].".png'><br>".$datas['Nom']."</td><td>".$Cat."</td><td>".$datas['Rang']."</td>
			<th>".$Prereq_txt."</th><th>".$Vers_txt."</th>".$CT1."</td><td>".$datas['Infos']."</td></tr>";
		}
		mysqli_free_result($result_s);
	}
	$mes="<div class='alert alert-info'>Si vous avez des questions, n'hésitez pas à venir en discuter sur Mumble ou sur <a href='index.php?view=live_chat' class='lien'>le Chat</a>. Nous vous conseillerons avec plaisir.</div>
	<table class='table'><thead><tr><th>Compétence</th><th>Catégorie</th><th>Rang</th><th>Prérequis</th><th>Exclusif <a href='#' class='popup'><img src='images/help.png'><span>La spécialisation dans cette catégorie est requise</span></a></th><th>Coût</th><th>Description</th></tr></thead>".$skill_txt."</table>";
	$titre="Compétences du pilote";
	$img="<div class='row'>
	<div class='col-md-4'><div class='btn btn-primary'>Points<br>".$Skill_Pts."</div></div>
	<div class='col-md-8'><div class='alert alert-warning'>La spécialisation dans une catégorie apporte une réduction de 50% sur le coût des compétences et permet d'accéder aux compétences exclusives de cette catégorie</div></div></div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./default.php');
?>