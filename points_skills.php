<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Cadeau=Insec($_POST['ID']);
	$CT=Insec($_POST['CT']);
	if($Cadeau)
	{
		$Skill_Pts=GetData("Pilote","ID",$PlayerID,"Skill_Pts");
		if($Skill_Pts >=$CT)
		{
			$query="INSERT INTO Skills_Pil (PlayerID, Skill) VALUES ('$PlayerID','$Cadeau')";
			$con=dbconnecti();
			$ok=mysqli_query($con,$query);
			mysqli_close($con);
			UpdateData("Pilote","Skill_Pts",-$CT,"ID",$PlayerID);
			if(!$ok)
			{
				$msg.="Erreur de mise à jour".mysqli_error($con);
				mail('binote@hotmail.com','Aube des Aigles: Ajout Skill Pilote Error',$msg);
			}
			$mes="Votre pilote reçoit sa nouvelle compétence!";
			$img='<img src=\'images/promo'.$country.'.jpg\'>';
		}
		else
			$mes="Vous n'avez pas suffisamment de points pour choisir cette compétence!<br><a href='points_skills.php' class='btn btn-default' title='Retour'>Retour</a>";
		$titre="Compétence";
		$menu="<a href='index.php?view=user' class='btn btn-default' title='Retour'>Retour au profil</a>";
		include_once('./index.php');
	}
	else
	{
		$CT1=1;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Skill_Pts,Skill_Cat FROM Pilote WHERE ID='$PlayerID'");
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
		$result_s=mysqli_query($con,"SELECT * FROM Skills WHERE Categorie <6 ORDER BY Rang DESC,Categorie ASC");
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
		if($Skill_Cat)
		{
			if($Skill_Cat ==1)
				$Spec_txt="Combat";
			elseif($Skill_Cat ==2)
				$Spec_txt="Gestion";
			elseif($Skill_Cat ==3)
				$Spec_txt="Commandement";
			elseif($Skill_Cat ==4)
				$Spec_txt="Renseignement";
			else
				$Spec_txt="Erreur";
		}
		else
			$Spec_txt="Aucune";
		if($result_s)
		{
			while($datas=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
			{
				$do_it=false;
				$Exclusif=false;
				if($Skill_Cat ==$datas['Categorie'])
				{
					$CT1=$datas['Rang'];
					$Exclusif=true;
				}
				else
					$CT1=$datas['Rang']*2;					
				if($Skill_Pts >=$CT1 and (!$datas['Exclusif'] or ($datas['Exclusif'] and $Exclusif)))
				{
					if(!is_array($Skills_Pil))
					{
						if($datas['Rang'] ==1)
							$do_it=true;
					}
					elseif(!in_array($datas['ID'],$Skills_Pil))
					{
						if(!$datas['Prereq1'] and !$datas['Prereq2'])
							$do_it=true;
						else
						{
							$do_it=true;
							if($datas['Prereq1'] and !in_array($datas['Prereq1'],$Skills_Pil))
								$do_it=false;
							if($datas['Prereq2'] and !in_array($datas['Prereq2'],$Skills_Pil))
								$do_it=false;
						}
					}
					if($do_it)
					{
						if(!$datas['Infos'])$datas['Infos']="Bonus de compétence";
						$skill_txt.="<tr><td><img src='/images/skills/skill".$datas['ID'].".png'><br>".$datas['Nom']."</td><td>".$datas['Infos']."</td><td class='btn btn-primary'>".$CT1."</td><td><form action='points_skills.php' method='post'><input type='hidden' name='ID' value='".$datas['ID']."'><input type='hidden' name='CT' value='".$CT1."'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
					}
				}
			}
			mysqli_free_result($result_s);
		}
		$mes="<table class='table'><thead><tr><th>Compétence</th><th>Description</th><th>Coût</th><th>Action</th></tr></thead>".$skill_txt."</table>";
	}
	$titre="Points de Compétences";
	$img="<div class='row'><div class='col-md-6'><div class='btn btn-primary'>Points<br>".$Skill_Pts."</div><br>Certaines actions en jeu peuvent vous faire gagner des points.</div>
	<div class='col-md-6'><h3>Spécialisation <span><a href='help/aide_skills_p.php'><img src='images/help.png'></a></span></h3>".$Spec_txt."</div></div>";
	include_once('./default.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>