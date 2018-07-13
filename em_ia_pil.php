<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	$Unite=Insec($_POST['Unite']);
	$con=dbconnecti();
	$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
	$Credits=mysqli_result(mysqli_query($con,"SELECT Credits FROM Officier_em WHERE ID='".$OfficierEMID."'"),0);
	$Recrutement=mysqli_result(mysqli_query($con,"SELECT Recrutement FROM Unit WHERE ID='$Unite'"),0);
	$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1"),0);
	$result=mysqli_query($con,"SELECT * FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 ORDER BY Avancement DESC,Reputation DESC");
    $result_s=mysqli_query($con,"SELECT ID,Infos FROM Skills WHERE Categorie <10");
    mysqli_close($con);
    if($result_s)
    {
        while($data=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
        {
            if(!$data['Infos'])$data['Infos']="Bonus de compétence";
            $Skills[$data['ID']]=$data['Infos'];
        }
        mysqli_free_result($result_s);
    }
	if($result)
	{
		while($Data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
		    $i++;
			$ID=$Data['ID'];
			$Pilote=$Data['Nom'];
			$Avancement=GetAvancement($Data['Avancement'],$country);
			if(!$Data['Skill'])
			{
				$Skills_1=array(1,2,6,10,14,18,22,26);
				$Skills_2=array(3,7,11,15,19,23,27,34,39,42,129,131);
				$Skills_3=array(4,8,12,16,20,24,28,30,32,35,37,40,43,45);
				$Skills_4=array(5,9,13,17,21,25,29,31,33,36,38,41,44);
				$Seed_Rang=mt_rand(0,10);
				if($Seed_Rang ==10)
					$Skill_p=$Skills_4[mt_rand(0,count($Skills_4)-1)];
				elseif($Seed_Rang >=8)
					$Skill_p=$Skills_3[mt_rand(0,count($Skills_3)-1)];
				elseif($Seed_Rang >=5)
					$Skill_p=$Skills_2[mt_rand(0,count($Skills_2)-1)];
				else
					$Skill_p=$Skills_1[mt_rand(0,count($Skills_1)-1)];
				SetData("Pilote_IA","Skill",$Skill_p,"ID",$ID);
				$Data['Skill']=$Skill_p;
			}
			if($Data['Skill'] ==5 and $Data['Acrobatie']<125)
				SetData("Pilote_IA","Acrobatie",125,"ID",$ID);
			elseif($Data['Skill'] ==4 and $Data['Acrobatie']<100)
				SetData("Pilote_IA","Acrobatie",100,"ID",$ID);
			elseif($Data['Skill'] ==3 and $Data['Acrobatie']<75)
				SetData("Pilote_IA","Acrobatie",75,"ID",$ID);
			elseif($Data['Skill'] ==2 and $Data['Acrobatie']<50)
				SetData("Pilote_IA","Acrobatie",50,"ID",$ID);
			elseif($Data['Skill'] ==9 and $Data['Bombardement']<125)
				SetData("Pilote_IA","Bombardement",125,"ID",$ID);
			elseif($Data['Skill'] ==8 and $Data['Bombardement']<100)
				SetData("Pilote_IA","Bombardement",100,"ID",$ID);
			elseif($Data['Skill'] ==7 and $Data['Bombardement']<75)
				SetData("Pilote_IA","Bombardement",75,"ID",$ID);
			elseif($Data['Skill'] ==6 and $Data['Bombardement']<50)
				SetData("Pilote_IA","Bombardement",50,"ID",$ID);
			elseif($Data['Skill'] ==13 and $Data['Vue']<125)
				SetData("Pilote_IA","Vue",125,"ID",$ID);
			elseif($Data['Skill'] ==12 and $Data['Vue']<100)
				SetData("Pilote_IA","Vue",100,"ID",$ID);
			elseif($Data['Skill'] ==11 and $Data['Vue']<75)
				SetData("Pilote_IA","Vue",75,"ID",$ID);
			elseif($Data['Skill'] ==10 and $Data['Vue']<50)
				SetData("Pilote_IA","Vue",50,"ID",$ID);
			elseif($Data['Skill'] ==17 and $Data['Navigation']<125)
				SetData("Pilote_IA","Navigation",125,"ID",$ID);
			elseif($Data['Skill'] ==16 and $Data['Navigation']<100)
				SetData("Pilote_IA","Navigation",100,"ID",$ID);
			elseif($Data['Skill'] ==15 and $Data['Navigation']<75)
				SetData("Pilote_IA","Navigation",75,"ID",$ID);
			elseif($Data['Skill'] ==14 and $Data['Navigation']<50)
				SetData("Pilote_IA","Navigation",50,"ID",$ID);
			elseif($Data['Skill'] ==21 and $Data['Pilotage']<125)
				SetData("Pilote_IA","Pilotage",125,"ID",$ID);
			elseif($Data['Skill'] ==20 and $Data['Pilotage']<100)
				SetData("Pilote_IA","Pilotage",100,"ID",$ID);
			elseif($Data['Skill'] ==19 and $Data['Pilotage']<75)
				SetData("Pilote_IA","Pilotage",75,"ID",$ID);
			elseif($Data['Skill'] ==18 and $Data['Pilotage']<50)
				SetData("Pilote_IA","Pilotage",50,"ID",$ID);
			elseif($Data['Skill'] ==25 and $Data['Tactique']<125)
				SetData("Pilote_IA","Tactique",125,"ID",$ID);
			elseif($Data['Skill'] ==24 and $Data['Tactique']<100)
				SetData("Pilote_IA","Tactique",100,"ID",$ID);
			elseif($Data['Skill'] ==23 and $Data['Tactique']<75)
				SetData("Pilote_IA","Tactique",75,"ID",$ID);
			elseif($Data['Skill'] ==22 and $Data['Tactique']<50)
				SetData("Pilote_IA","Tactique",50,"ID",$ID);
			elseif($Data['Skill'] ==29 and $Data['Tir']<125)
				SetData("Pilote_IA","Tir",125,"ID",$ID);
			elseif($Data['Skill'] ==28 and $Data['Tir']<100)
				SetData("Pilote_IA","Tir",100,"ID",$ID);
			elseif($Data['Skill'] ==27 and $Data['Tir']<75)
				SetData("Pilote_IA","Tir",75,"ID",$ID);
			elseif($Data['Skill'] ==26 and $Data['Tir']<50)
				SetData("Pilote_IA","Tir",50,"ID",$ID);
			if(!$GHQ and $OfficierEMID !=$Officier_Adjoint and $OfficierEMID !=$Commandant)
                $bouton="<td><span class='label label-danger'>Désactivé</td>";
			elseif(!$Recrutement or $i ==1 or $Pilotes <12)
                $bouton="<td><span class='label label-danger'>Réservé</td>";
            elseif($Credits <1)
                $bouton="<td><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'></td>";
			elseif(($GHQ or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Commandant) and $Credits >=1 and $Pilotes >=12 and !$Data['Cible'] and !$Data['Avion'])
				$bouton="<td><form action='em_ia_pil_move.php' method='post'><input type='hidden' name='id' value='".$ID."'><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Muter' class='btn btn-primary btn-sm' onclick='this.disabled=true;this.form.submit();'></form></td>";
			else
				$bouton="<td><span class='label label-danger'>En vol</td>";
			if(!$Data['Infos'])$Data['Infos']="Bonus de compétence";
			if($Data['Endurance'] >8)
				$fatigue_icon='<img src="images/fatigue9.png" title="Très fatigué">';
			elseif($Data['Endurance'] >5)
				$fatigue_icon='<img src="images/fatigue6.png" title="Fatigué">';
			elseif($Data['Endurance'] >2)
				$fatigue_icon='<img src="images/fatigue3.png" title="Légèrement fatigué">';
			else
				$fatigue_icon='<img src="images/fatigue0.png" title="En pleine forme">';
			if($Premium)
			{
				$pilotes_txt.="<tr><td>".$Pilote."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$country.$Avancement[1].".png'></td><td>".$Data['Reputation']."</td><td><a href='#' class='popup'><img src='images/skills/skill".$Data['Skill']."p.png'><span>".$Skills[$Data['Skill']]."</span></a></td>".$bouton."<td>"
				.floor($Data['Pilotage'])."</td><td>".floor($Data['Acrobatie'])."</td><td>".floor($Data['Bombardement'])."</td><td>".floor($Data['Tir'])."</td><td>".floor($Data['Tactique'])."</td><td>".floor($Data['Navigation'])."</td><td>".floor($Data['Vue'])."</td><td>"
				.$Data['Moral']."</td><td>".$Data['Courage']."</td><td>".$fatigue_icon."</td><td>".$Data['Missions']."</td><td>".$Data['Victoires']."</td><tr>";
			}
			else
			{
				$Reputation=GetReputation($Data['Reputation'],$country);
				$pilotes_txt.="<tr><td>".$Pilote."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$country.$Avancement[1].".png'></td><td>".$Reputation."</td><td><a href='#' class='popup'><img src='images/skills/skill".$Data['Skill']."p.png'><span>".$Skills[$Data['Skill']]."</span></a></td>".$bouton."<td>"
				.GetSkillTxt($Data['Pilotage'])."</td><td>".GetSkillTxt($Data['Acrobatie'])."</td><td>".GetSkillTxt($Data['Bombardement'])."</td><td>".GetSkillTxt($Data['Tir'])."</td><td>".GetSkillTxt($Data['Tactique'])."</td><td>"
				.GetSkillTxt($Data['Navigation'])."</td><td>".GetSkillTxt($Data['Vue'])."</td><td>"
				.GetMoralTxt($Data['Moral'])."</td><td>".GetCourageTxt($Data['Courage'])."</td><td>".$fatigue_icon."</td><td>".$Data['Missions']."</td><td>".$Data['Victoires']."</td><tr>";
			}
		}
		mysqli_free_result($result);
		$mes="<h2>".Afficher_Icone($Unite,$country)." Pilotes</h2><div style='overflow:auto;'><table class='table table-hover'><thead><tr><th>Nom</th><th>Grade</th><th>Reputation</th><th>Compétence</th><th>Action</th>
		<th><a href='#' class='popup'>Pilotage<span>Caractéristique importante dans toutes les situations</span></a></th><th><a href='#' class='popup'>Acrobatie<span>Caractéristique utilisée par les avions légers lors des combats aériens et également face à la DCA</span></a></th><th><a href='#' class='popup'>Bombardement<span>Caractéristique utilisée lors des bombardements ainsi que des torpillages</span></a></th><th><a href='#' class='popup'>Tir<span>Caractéristique utilisée lors des combats aériens ou des mitraillages</span></a></th><th><a href='#' class='popup'>Tactique<span>Caractéristique utilisée lors des combats aériens, mais également face à la DCA</span></a></th><th><a href='#' class='popup'>Navigation<span>Caractéristique permettant d'augmenter l'autonomie des avions de l'unité</span></a></th><th><a href='#' class='popup'>Détection<span>Caractéristique utilisée lors des reconnaissances, mais également lors des combats aériens</span></a></th>
		<th><a href='#' class='popup'>Moral<span>Influe légèrement sur les actions. Le moral doit être supérieur à 0 pour que le pilote puisse partir en mission</span></a></th><th><a href='#' class='popup'>Courage<span>Influe légèrement sur les actions en combat. Le courage doit être supérieur à 0 pour que le pilote puisse partir en mission</span></a></th><th><a href='#' class='popup'>Fatigue<span>Influe fortement sur les actions en combat.</span></a></th><th>Missions</th><th>Victoires</th></tr></thead><tbody>".$pilotes_txt."</tbody></table></div>";
		$img="<img src='images/pilotes".$country.".jpg'>";
		$menu="<div class='alert alert-info'><h3>En mission</h3>Les compétences entourées d'un contour vert affectent l'unité tout entière.<br>Le pilote le plus haut gradé fera bénéficier les autres pilotes d'une partie de ses compétences.</div>
		<ul class='list-inline'><li><form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$Unite."'><input type='Submit' value='Retour unité' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></li>
		<li><a href='index.php?view=rapports' class='btn btn-default' title='Retour'>Retour menu</a></li></ul>";
		include_once('./index.php');
	}
	else
		echo "<h6>Désolé, votre escadrille ne compte pas de pilote actif.</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>