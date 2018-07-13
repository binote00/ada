<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA)
	{
		$con=dbconnecti();
		$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
		$result=mysqli_query($con,"SELECT Unit FROM Pilote WHERE ID='$PlayerID'");
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
        $result_s=mysqli_query($con,"SELECT ID,Infos FROM Skills WHERE Categorie <10");
		//mysqli_close($con);
        if($result_s)
        {
            while($data=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
            {
                if(!$data['Infos'])$data['Infos']="Bonus de compétence";
                $Skills[$data['ID']]=$data['Infos'];
            }
            mysqli_free_result($result_s);
        }
        if($results)
		{
			while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
			{
				$Skills_Pil[]=$data['Skill'];
			}
			mysqli_free_result($results);
		}
		if(is_array($Skills_Pil))
		{
			include_once('./jfv_skills_inc.php');
			/*if(in_array(30,$Skills_Pil))
				$Trompe_la_mort=50;*/
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
			}
			mysqli_free_result($result);
		}		
		/*$query="SELECT * FROM ((SELECT ID,Nom,Pays,Unit,Avancement,Pilotage,Acrobatie,Tir,Tactique,Navigation,Vue,Moral,Courage,Reputation,Actif,Victoires,Missions as Points FROM Pilote WHERE Unit='$Unite') 
		UNION (SELECT ID,Nom,Pays,Unit,Avancement,Pilotage,Acrobatie,Tir,Tactique,Navigation,Vue,Moral,Courage,Reputation,Actif,Victoires,Points FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1)) as t
		WHERE Unit='$Unite' ORDER BY Avancement DESC, Reputation DESC";// LIMIT 20";*/
		//$con=dbconnecti();
		$resultu=mysqli_query($con,"SELECT Nom,Etat,Type FROM Unit WHERE ID='$Unite'");
		$resultp=mysqli_query($con,"SELECT Nom,Pays,Unit,Avancement,Reputation,Moral,Courage,Pilotage,Acrobatie,Bombardement,Tir,Tactique,Navigation,Vue,Ailier,Missions,Victoires/10 as Victoires FROM Pilote WHERE Unit='$Unite'");
		$resultq=mysqli_query($con,"SELECT ID,Nom,Pays,Unit,Avancement,Pilotage,Acrobatie,Bombardement,Tir,Tactique,Navigation,Vue,Moral,Courage,Reputation,Actif,Victoires,Points,Skill FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 ORDER BY Avancement DESC, Reputation DESC");
		mysqli_close($con);
		if($resultu)
		{
			while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
			{
				$Unite_Nom=$datau['Nom'];
				$Etat=$datau['Etat'];
				$Unite_Type=$datau['Type'];
			}
			mysqli_free_result($resultu);
		}
		if($resultp)
		{
			while($datap=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
			{
				$Avancement=$datap['Avancement'];
				$Unite=$datap['Unit'];
				$Reput=$datap['Reputation'];
				$Ailier=$datap['Ailier'];
				$Avancement=GetAvancement($datap['Avancement'],$datap['Pays']);
				$pilotes_txt.="<tr><td>".$datap['Nom']."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$datap['Pays'].$Avancement[1].".png'></td><td>".$datap['Reputation']."</td><td>".$datap['Missions']."</td><td>".floor($datap['Victoires'])."</td><td><img src='images/skills/skill0p.png'></td><td>"
				.GetSkillTxt($datap['Pilotage'])."</td><td>".GetSkillTxt($datap['Acrobatie'])."</td><td>".GetSkillTxt($datap['Bombardement'])."</td><td>".GetSkillTxt($datap['Tir'])."</td><td>".GetSkillTxt($datap['Tactique'])."</td><td>"
				.GetSkillTxt($datap['Navigation'])."</td><td>".GetSkillTxt($datap['Vue'])."</td><td>"
				.GetMoralTxt($datap['Moral'])."</td><td>".GetCourageTxt($datap['Courage'])."</td><td></td><tr>";
			}
			mysqli_free_result($resultp);
		}
		if($resultq)
		{
			while($Data=mysqli_fetch_array($resultq,MYSQLI_ASSOC))
			{
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
					if($Skill_p ==5 and $Data['Acrobatie']<125)
						SetData("Pilote_IA","Acrobatie",125,"ID",$ID);
					elseif($Skill_p ==4 and $Data['Acrobatie']<100)
						SetData("Pilote_IA","Acrobatie",100,"ID",$ID);
					elseif($Skill_p ==3 and $Data['Acrobatie']<75)
						SetData("Pilote_IA","Acrobatie",75,"ID",$ID);
					elseif($Skill_p ==2 and $Data['Acrobatie']<50)
						SetData("Pilote_IA","Acrobatie",50,"ID",$ID);
					elseif($Skill_p ==9 and $Data['Bombardement']<125)
						SetData("Pilote_IA","Bombardement",125,"ID",$ID);
					elseif($Skill_p ==8 and $Data['Bombardement']<100)
						SetData("Pilote_IA","Bombardement",100,"ID",$ID);
					elseif($Skill_p ==7 and $Data['Bombardement']<75)
						SetData("Pilote_IA","Bombardement",75,"ID",$ID);
					elseif($Skill_p ==6 and $Data['Bombardement']<50)
						SetData("Pilote_IA","Bombardement",50,"ID",$ID);
					elseif($Skill_p ==13 and $Data['Vue']<125)
						SetData("Pilote_IA","Vue",125,"ID",$ID);
					elseif($Skill_p ==12 and $Data['Vue']<100)
						SetData("Pilote_IA","Vue",100,"ID",$ID);
					elseif($Skill_p ==11 and $Data['Vue']<75)
						SetData("Pilote_IA","Vue",75,"ID",$ID);
					elseif($Skill_p ==10 and $Data['Vue']<50)
						SetData("Pilote_IA","Vue",50,"ID",$ID);
					elseif($Skill_p ==17 and $Data['Navigation']<125)
						SetData("Pilote_IA","Navigation",125,"ID",$ID);
					elseif($Skill_p ==16 and $Data['Navigation']<100)
						SetData("Pilote_IA","Navigation",100,"ID",$ID);
					elseif($Skill_p ==15 and $Data['Navigation']<75)
						SetData("Pilote_IA","Navigation",75,"ID",$ID);
					elseif($Skill_p ==14 and $Data['Navigation']<50)
						SetData("Pilote_IA","Navigation",50,"ID",$ID);
					elseif($Skill_p ==21 and $Data['Pilotage']<125)
						SetData("Pilote_IA","Pilotage",125,"ID",$ID);
					elseif($Skill_p ==20 and $Data['Pilotage']<100)
						SetData("Pilote_IA","Pilotage",100,"ID",$ID);
					elseif($Skill_p ==19 and $Data['Pilotage']<75)
						SetData("Pilote_IA","Pilotage",75,"ID",$ID);
					elseif($Skill_p ==18 and $Data['Pilotage']<50)
						SetData("Pilote_IA","Pilotage",50,"ID",$ID);
					elseif($Skill_p ==25 and $Data['Tactique']<125)
						SetData("Pilote_IA","Tactique",125,"ID",$ID);
					elseif($Skill_p ==24 and $Data['Tactique']<100)
						SetData("Pilote_IA","Tactique",100,"ID",$ID);
					elseif($Skill_p ==23 and $Data['Tactique']<75)
						SetData("Pilote_IA","Tactique",75,"ID",$ID);
					elseif($Skill_p ==22 and $Data['Tactique']<50)
						SetData("Pilote_IA","Tactique",50,"ID",$ID);
					elseif($Skill_p ==29 and $Data['Tir']<125)
						SetData("Pilote_IA","Tir",125,"ID",$ID);
					elseif($Skill_p ==28 and $Data['Tir']<100)
						SetData("Pilote_IA","Tir",100,"ID",$ID);
					elseif($Skill_p ==27 and $Data['Tir']<75)
						SetData("Pilote_IA","Tir",75,"ID",$ID);
					elseif($Skill_p ==26 and $Data['Tir']<50)
						SetData("Pilote_IA","Tir",50,"ID",$ID);
					SetData("Pilote_IA","Skill",$Skill_p,"ID",$ID);
					$Data['Skill']=$Skill_p;
				}
				if($Unite_Type ==8)
					$bouton="<td></td>";
				elseif($ID ==$Ailier and $Data['Actif'] ==1)
					$bouton="<th>Actuel</th>";
				elseif($Reput >499 and $Data['Actif'] ==1)
					$bouton="<td class='btn btn-default'><a href='ailier.php?pilote=".$ID."' title='Choisir ce pilote comme ailier'>Choisir</a></td>";
				else
					$bouton="<td></td>";
				if($Premium)
				{
					$pilotes_txt.="<tr><td>".$Pilote."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$country.$Avancement[1].".png'></td><td>".$Data['Reputation']."</td><td>".$Data['Points']."</td><td>".$Data['Victoires']."</td><td><a href='#' class='popup'><img src='images/skills/skill".$Data['Skill']."p.png'><span>".$Skills[$Data['Skill']]."</span></a></td><td>"
					.floor($Data['Pilotage'])."</td><td>".floor($Data['Acrobatie'])."</td><td>".floor($Data['Bombardement'])."</td><td>".floor($Data['Tir'])."</td><td>".floor($Data['Tactique'])."</td><td>".floor($Data['Navigation'])."</td><td>".floor($Data['Vue'])."</td><td>"
					.$Data['Moral']."</td><td>".$Data['Courage']."</td>".$bouton."<tr>";
				}
				else
				{
					$Reputation=GetReputation($Data['Reputation'],$country);
					$pilotes_txt.="<tr><td>".$Pilote."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$country.$Avancement[1].".png'></td><td>".$Reputation."</td><td>".$Data['Points']."</td><td>".$Data['Victoires']."</td><td><a href='#' class='popup'><img src='images/skills/skill".$Data['Skill']."p.png'><span>".$Skills[$Data['Skill']]."</span></a></td><td>"
					.GetSkillTxt($Data['Pilotage'])."</td><td>".GetSkillTxt($Data['Acrobatie'])."</td><td>".GetSkillTxt($Data['Bombardement'])."</td><td>".GetSkillTxt($Data['Tir'])."</td><td>".GetSkillTxt($Data['Tactique'])."</td><td>"
					.GetSkillTxt($Data['Navigation'])."</td><td>".GetSkillTxt($Data['Vue'])."</td><td>"
					.GetMoralTxt($Data['Moral'])."</td><td>".GetCourageTxt($Data['Courage'])."</td>".$bouton."<tr>";
				}
			}
			mysqli_free_result($resultq);
			if($Unite_Type !=8)
				include_once('./menu_escadrille.php');
			else
				echo "<h1>".$Unite_Nom."</h1><div class='alert alert-info'>Lorsque vous aurez terminé votre formation et que votre demande de mutation sera validée, vous pourrez gérer les pilotes de votre nouvelle escadrille.</div>";
			echo "<h2>Pilotes</h2><div style='overflow:auto;'><table class='table table-hover'><thead><tr><th>Nom</th><th>Grade</th><th>Reputation</th><th>Score</th><th>Victoires</th>
			<th>Compétence</th><th>Pilotage</th><th>Acrobatie</th><th>Bombardement</th><th>Tir</th><th>Tactique</th><th>Navigation</th><th>Détection</th>
			<th>Moral</th><th>Courage</th><th title='Choisir votre ailier'>Ailier</th></tr></thead><tbody>".$pilotes_txt."</tbody></table></div>
			<div class='row'><div class='col-xs-12 col-sm-6'>
				<div class='alert alert-info'>
					<table class='table'>
						<thead><tr><th>Rang</th><th>Compétence</th><th>Adjectif</th></tr></thead>
						<tr><td>Rang 0</td><td>0-24</td><td>Bleu</td></tr>
						<tr><td>Rang 0</td><td>25-49</td><td>Apte</td></tr>
						<tr><td>Rang I</td><td>50-74</td><td>Compétent</td></tr>
						<tr><td>Rang II</td><td>75-99</td><td>Entraîné</td></tr>
						<tr><td>Rang III</td><td>100-124</td><td>Chevronné</td></tr>
						<tr><td>Rang IV</td><td>125-149</td><td>Vétéran</td></tr>
						<tr><td>Rang V</td><td>150-174</td><td>Expert</td></tr>
						<tr><td>Rang VI</td><td>175-199</td><td>Elite</td></tr>
						<tr><td>Rang VII</td><td>200+</td><td>Virtuose</td></tr>
					</table>
				</div>
			</div></div>";
		}
		else
			echo "<h6>Désolé, votre escadrille ne compte pas de pilote actif.</h6>";
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>