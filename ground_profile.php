<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
$Officier_em=$_SESSION['Officier_em'];
$AccountID=$_SESSION['AccountID'];
if($AccountID >0 and $Officier_em >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$mes='.';
	if($Officier_em >0){
		$DB_Off='Officier_em';
		$OfficierID=$Officier_em;
		$aide_trait='aide_trait_officier_em';
	}
	elseif($OfficierID >0){
		$DB_Off='Officier';
		$aide_trait='aide_trait_officier';
	}
	$con=dbconnecti();
	$resultj=mysqli_query($con,"SELECT Premium,Beta,Officier_bonus,Officier_em FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
	$result=mysqli_query($con,"SELECT *,DATE_FORMAT(`Engagement`,'%d-%m-%Y') as Engagement FROM $DB_Off WHERE ID='$OfficierID'");
	mysqli_close($con);
	if($resultj){
		while($dataj=mysqli_fetch_array($resultj,MYSQLI_ASSOC)){
			$Premium=$dataj['Premium'];
			$Beta=$dataj['Beta'];
            $Officier_bonus=$dataj['Officier_bonus'];
            $Officier_em_ori=$dataj['Officier_em'];
		}
		mysqli_free_result($resultj);
	}
	if($result){
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)){
			$ID=$data['ID'];
			$Nom=$data['Nom'];
			$Pays=$data['Pays'];
			$Front=$data['Front'];
			$Engagement=$data['Engagement'];
			$Reputation=$data['Reputation'];
			$Avancement=$data['Avancement'];
			$Trait_o=$data['Trait'];
			$Skill1=$data['Skill1'];
			$Skill2=$data['Skill2'];
			$Skill3=$data['Skill3'];
			$Skill4=$data['Skill4'];
			$medal=$data['medal'];
			$Credits=$data['Credits'];
			$Photo=$data['Photo'];
			$Photo_Premium=$data['Photo_Premium'];
			$Division=$data['Division'];
			$kreta=$data['kreta'];
			$afrika=$data['afrika'];
			$ost=$data['Ost'];
			$camp_fr=$data['camp_fr'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($Officier_em_ori ==$Officier_em){
	    $Front_Premium = GetData("Officier_em","ID",$Officier_bonus,"Front");
    }
    else{
        $Front_Premium = GetData("Officier_em","ID",$Officier_em_ori,"Front");
    }
    if($Front_Premium ==0)$Front_Premium=10;
	if(!$Officier_em){
		/*if(!$Premium)
		{
			$kills="<img src='images/premium50.png' title='Information Premium'>";
			$loose="<img src='images/premium50.png' title='Information Premium'>";
		}
		else
		{
			$con=dbconnecti();
			$result2=mysqli_query($con,"SELECT ID,Vehicule_ID FROM Regiment WHERE Officier_ID='$OfficierID'");
			if($result2)
			{
				$kills="<div style='overflow:auto; width:100%; height: 350px;'>";
				$loose="<div style='overflow:auto; width:100%; height: 350px;'>";
				while($data=mysqli_fetch_array($result2, MYSQLI_ASSOC))
				{
					$unit_id=$data['ID'];
					//$con=dbconnecti();
					$resultk=mysqli_query($con,"SELECT SUM(Kills),Veh_b FROM Ground_Cbt WHERE Reg_a='$unit_id' GROUP BY Veh_b ORDER BY SUM(Kills) DESC");
					$resultp=mysqli_query($con,"SELECT SUM(Kills),Veh_b FROM Ground_Cbt WHERE Reg_b='$unit_id' GROUP BY Veh_b ORDER BY SUM(Kills) DESC");
					//mysqli_close($con);
					if($resultk)
					{
						$kills.="<b>Destructions ".$unit_id."e Cie</b><br>";
						while($datak=mysqli_fetch_array($resultk))
						{
							$kills.=$datak[0]." <img src='images/vehicules/vehicule".$datak['Veh_b'].".gif'><br>";
						}
						mysqli_free_result($resultk);
					}
					if($resultp)
					{
						$loose.="<b>Pertes ".$unit_id."e Cie</b><br>";
						while($datap=mysqli_fetch_array($resultp))
						{
							$loose.=$datap[0]." <img src='images/vehicules/vehicule".$datap['Veh_b'].".gif'><br>";
						}
						mysqli_free_result($resultp);
					}
					$units.=$data['ID']."e Cie ".GetVehiculeIcon($data['Vehicule_ID'], $Pays, 0, 0, $Front)."<br>";
					//$units.=$data['ID']."e Cie <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'><br>";
				}
				mysqli_free_result($result2);
				unset($data);
				$kills.="</div>";
				$loose.="</div>";
			}
			mysqli_close($con);
		}*/
	}
	else{
		$Planificateur=GetData("GHQ","Pays",$Pays,"Planificateur");
		if($ID ==$Planificateur)$GHQ=true;
	}
	if(!$Officier_em) //Temporaire
	{
		if($Skill4)
			$Skill4=GetSpec_txt($Skill4).' (Campagne)';
		else
			$Skill4='';
		if($Skill1)
			$Skill1=GetSpec_txt($Skill1).' (Réputation)';
		elseif($Reputation >2000)
		{
			$menu_skill='';
			if($Officier_em >0)
			{
				for($i=500;$i <509;$i++){
					$menu_skill.="<option value='".$i."'>".GetSpec_txt($i)."</option>";
				}
			}
			else
			{
				for($i=1;$i <9;$i++){
					$menu_skill.="<option value='".$i."'>".GetSpec_txt($i)."</option>";
				}
				for($i=15;$i <22;$i++){
					$menu_skill.="<option value='".$i."'>".GetSpec_txt($i)."</option>";
				}
			}
			$Skill1="<form action='index.php?view=ground_spec' method='post'>
			<input type='hidden' name='Off' value='".$ID."'>
			<input type='hidden' name='Skill' value='1'>
						<select name='Trait_o' class='form-control' style='width: 200px' title='Cette spécialisation avancée de votre officier affectera toutes les troupes sous ses ordres'>
							<option value='0' selected>Aucun</option>".$menu_skill."
							<option value='40'>Ravitaillement favorisé</option>
							<option value='41'>Stocks optimisés</option>
						</select>
			<input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";		
		}
		else
			$Skill1='';
		if($Skill2)
			$Skill2=GetSpec_txt($Skill2).' (Réputation)';
		elseif($Reputation >10000)
		{
			$menu_skill='';
			if($Officier_em >0){
				for($i=500;$i <509;$i++){
					$menu_skill.="<option value='".$i."'>".GetSpec_txt($i)."</option>";
				}
			}
			else{
				for($i=1;$i <44;$i++){
					if($i != $Skill1)
						$menu_skill.="<option value='".$i."'>".GetSpec_txt($i)."</option>";
				}
			}
			$Skill2="<form action='index.php?view=ground_spec' method='post'>
			<input type='hidden' name='Off' value='".$ID."'>
			<input type='hidden' name='Skill' value='2'>
						<select name='Trait_o' class='form-control' style='width: 200px' title='Cette spécialisation avancée de votre officier affectera toutes les troupes sous ses ordres'>
							<option value='0' selected>Aucun</option>".$menu_skill."
						</select>
			<input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";		
		}
		else
			$Skill2='';
		if($Skill3)
			$Skill3=GetSpec_txt($Skill3).' (Réputation)';
		elseif($Reputation >25000)
		{
			$menu_skill='';
			if($Officier_em >0){
				for($i=500;$i <509;$i++){
					$menu_skill.="<option value='".$i."'>".GetSpec_txt($i)."</option>";
				}
			}
			else{
				for($i=1;$i <44;$i++){
					if($i !=$Skill1 and $i !=$Skill2)
						$menu_skill.="<option value='".$i."'>".GetSpec_txt($i)."</option>";
				}
			}
			$Skill3="<form action='index.php?view=ground_spec' method='post'>
			<input type='hidden' name='Off' value='".$ID."'>
			<input type='hidden' name='Skill' value='3'>
						<select name='Trait_o' class='form-control' style='width: 200px' title='Cette spécialisation avancée de votre officier affectera toutes les troupes sous ses ordres'>
							<option value='0' selected>Aucun</option>".$menu_skill."
						</select>
			<input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";		
		}
		else
			$Skill3='';
	}
	//Attaques victorieuses
	if(!$Officier_em)
	{
		$Rang_promo=0;
		$con=dbconnecti(4);
		$Atk_vic=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (401,404,405,406,407) AND PlayerID='$OfficierID'"),0);
		mysqli_close($con);
		if($Reputation >1000000 and $Atk_vic >50000 and $Avancement >500000)
			$Rang_promo=10;
		elseif($Reputation >500000 and $Atk_vic >10000 and $Avancement >500000)
			$Rang_promo=9;
		elseif($Reputation >250000 and $Atk_vic >5000 and $Avancement >200000)
			$Rang_promo=8;
		elseif($Reputation >100000 and $Atk_vic >2000 and $Avancement >100000)
			$Rang_promo=7;
		elseif($Reputation >50000 and $Atk_vic >1000 and $Avancement >50000)
			$Rang_promo=6;
		elseif($Reputation >20000 and $Atk_vic >500 and $Avancement >25000)
			$Rang_promo=5;
		elseif($Reputation >10000 and $Atk_vic >250 and $Avancement >10000)
			$Rang_promo=4;
		elseif($Reputation >5000 and $Atk_vic >100)
			$Rang_promo=3;
		elseif($Reputation >2500 and $Atk_vic >50)
			$Rang_promo=2;
		elseif($Reputation >1000 and $Atk_vic >25)
			$Rang_promo=1;
		if($Rang_promo >0)SetData("Officier","medal",$Rang_promo,"ID",$OfficierID);
	}
	else
	{
		if($Reputation >1000000 and $Avancement >1000000)
			$Rang_promo=10;
		elseif($Reputation >500000 and $Avancement >500000)
			$Rang_promo=9;
		elseif($Reputation >250000 and $Avancement >500000)
			$Rang_promo=8;
		elseif($Reputation >100000 and $Avancement >200000)
			$Rang_promo=7;
		elseif($Reputation >50000 and $Avancement >100000)
			$Rang_promo=6;
		elseif($Reputation >20000 and $Avancement >50000)
			$Rang_promo=5;
		elseif($Reputation >10000 and $Avancement >50000)
			$Rang_promo=4;
		elseif($Reputation >5000 and $Avancement >30000)
			$Rang_promo=3;
		elseif($Reputation >2500 and $Avancement >25000)
			$Rang_promo=2;
		elseif($Reputation >1000 and $Avancement >25000)
			$Rang_promo=1;
		if($Rang_promo >0)SetData("Officier_em","medal",$Rang_promo,"ID",$Officier_em);
	}
	//Trait
	if(!$Trait_o and ($Reputation >50 or $Avancement >5100))$Trait_on=true;	
	$Grade=GetAvancement($Avancement,$Pays,0,1);
	$Rep=GetReputOfficier($Reputation);
	if($Premium)
	{
		if($Avancement > 500000)
			$Avancement_Max=1000000;
		elseif($Avancement > 200000)
			$Avancement_Max=500000;
		elseif($Avancement > 100000)
			$Avancement_Max=200000;
		elseif($Avancement > 50000)
			$Avancement_Max=100000;
		elseif($Avancement > 25000)
			$Avancement_Max=50000;
		elseif($Avancement > 10000)
			$Avancement_Max=25000;
		elseif($Avancement > 5000)
			$Avancement_Max=10000;
		else
			$Avancement_Max=5000;
			
		if($Reputation > 100000)
			$Reputation_Max=500000;
		elseif($Reputation > 50000)
			$Reputation_Max=100000;
		elseif($Reputation > 20000)
			$Reputation_Max=50000;
		elseif($Reputation > 10000)
			$Reputation_Max=20000;
		elseif($Reputation > 5000)
			$Reputation_Max=10000;
		elseif($Reputation > 2000)
			$Reputation_Max=5000;
		elseif($Reputation > 1000)
			$Reputation_Max=2000;
		elseif($Reputation > 500)
			$Reputation_Max=1000;
		elseif($Reputation > 100)
			$Reputation_Max=500;
		elseif($Reputation > 50)
			$Reputation_Max=100;
		elseif($Reputation > 1)
			$Reputation_Max=50;
		else
			$Reputation_Max=1;
		$Avancement_Max_Bar=$Avancement_Max/($Avancement_Max/100);
		$Reputation_Max_Bar=$Reputation_Max/($Reputation_Max/100);
		$Avancement_Bar=$Avancement/($Avancement_Max/100);
		$Reputation_Bar=$Reputation/($Reputation_Max/100);		
		if($Admin){
			$PrintRep=$Reputation;
			$PrintAv=$Avancement;
		}
		else{
			$PrintRep='';
			$PrintAv='';
		}
		$Bar_pc=round($Avancement_Bar,1,PHP_ROUND_HALF_DOWN); 
		$Bar_rep_pc=round($Reputation_Bar,1,PHP_ROUND_HALF_DOWN);
		if($Bar_pc <0)$Bar_pc=0;
		if($Bar_rep_pc <0)$Bar_rep_pc=0;
		if($Photo_Premium ==1)
			$Photo="<img src='uploads/Officier/".$Officier_em."_photo.jpg' style='width:100%;'>";
		else
			$Photo="<a href='upload_img.php'><img src='images/persos/general".$Pays.$Photo.".jpg' title='Changer la photo de profil'></a>";
	}
	else
		$Photo="<img class='img-flex' src='images/persos/general".$Pays.$Photo.".jpg'>";
	echo "<h1>".$Nom."</h1><div class='row'>
	<table class='table'><thead><tr><th>Grade</th><th>Réputation</th><th>Engagement</th></tr></thead>
	<tr><td><a href='#' class='popup'><img src='images/grades/ranks".$Pays.$Grade[1].".png' title='".$Grade[0]."'>
	<span><b>".$Grade[0]."</b><ul><li>permet une réduction du coût en Crédits Temps de certaines actions.</li></ul></span></a></td>
	<td><img title='".$Rep[0]."' src='images/general".$Rep[1].".png'></td>
	<td>".$Engagement."</td>
	</tr></table></div>
	<div class='row'><div class='col-md-6'>
	<div class='row'><div class='col-md-6'>".$Photo."</div><div class='col-md-6'>";
	if($Premium)
	{?>
	<table class='table'>
		<thead><tr><th>Premium</th></tr></thead>
		<tr><th>Avancement</th></tr>
		<tr><td><div class="progress">
		  <div class="progress-bar" role="progressbar" aria-valuenow="<?=$Avancement_Bar?>" aria-valuemin="0" aria-valuemax="<?=$Avancement_Max_Bar?>" style="width:<?=$Bar_pc?>%;"><?=$Bar_pc?>%</div>
		</div></td></tr>
		<tr><th>Réputation</th></tr>
		<tr><td><div class="progress">
		  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?=$Reputation_Bar?>" aria-valuemin="0" aria-valuemax="<?=$Reputation_Max_Bar?>" style="width:<?=$Bar_rep_pc?>%;"><?=$Bar_rep_pc?>%</div>
		</div></td></tr>
	</table>
	<?}?>
	<?/*if($Skill1 or $Skill2 or $Skill3 or $Skill4 or $Reputation >10000){?>
	<h2><small>Spécialisations <a href='aide_spec_officier.php' target='_blank'><img src='images/help.png'></a></small></h2>
	<?echo $Skill4;?><br><?echo $Skill1;?><br><?echo $Skill2;?><br><?echo $Skill3;}*/?></div></div></div>
	<?if(!$Officier_em){/*?>
	<div class='col-md-6'>
	<div id="col_droite">
		<?
		if($Division){?>	
		<table class='table'>
			<thead><tr><th colspan='2'>Division <a href='aide_division.php' target='_blank' title='Aide'><img src='images/help.png'></a></th></tr></thead>
			<tr><td rowspan='2'><img src='images/div/div<?echo $Division;?>.png'></td><td><?echo GetData("Division","ID",$Division,"Nom");?></td></tr>		
			<tr><td><?echo 'Base : '.GetData("Lieu","ID",GetData("Division","ID",$Division,"Base"),"Nom");?></td></tr>
		</table>
		<?}else{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT d.ID,d.Nom,l.Nom as Base FROM Division as d,Lieu as l WHERE d.Pays='$Pays' AND d.Front='$Front' AND d.Base=l.ID AND d.Active=1");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
				{
					$divs.="<option value=".$data['ID'].">".$data['Nom']." (".$data['Base'].")</option>";
				}
				mysqli_free_result($result);
				unset($data);
			}
			echo "<form action='choix_division.php' method='post'>
			<input type='hidden' name='Off' value='".$ID."'>
			<h2><small>Rejoindre une Division <a href='aide_division.php' target='_blank'><img src='images/help.png'></a></small></h2>
			<select name='Div' class='form-control' style='width: 300px' title='Votre demande sera examinée par votre hiérarchie'><option value='0' selected>Aucune</option>".$divs."</select>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";	
		}?>
		<table class='table'>
			<thead><tr><th>Unités</th><th colspan="2">Statistiques</th></tr></thead>
			<tr><td align="left"><?echo $units;?></td><td align="left"><?echo $kills;?></td><td align="left"><?echo $loose;?></td></tr>
		</table>
	</div>
	</div><?*/}elseif($GHQ){
    echo "<div id='col_droite'><h2><small>Démission</small></h2><form action='index.php?view=em_changer_front' method='post'>
			<input type='hidden' name='front' value='12'>
			<img src='/images/CT24.png' title='Cette action consommera tous vos CT restants, avec un minimum de 24'><input type='submit' value='Démission' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>
			<div class='alert alert-danger'>L'officier qui change de front abandonne automatiquement tout poste qu'il occupe à l'état-major.<br>Cette action consommera tous vos Crédits Temps restants, avec un minimum de 24.</div>
		</div>";
    }
	elseif($Credits >=24 and !$GHQ){
        $Date_Campagne = GetData("Conf_Update","ID",2,"Date");
		switch($country)
		{
			case 1:
                if($Front_Premium !=5 and $Date_Campagne >'1940-11-30')
                    $fronts.="<option value='5'>Front Arctique</option>";
			    if($Front_Premium !=0)
				    $fronts="<option value='10' selected>Front Ouest</option>";
			    if($Front_Premium !=1 and $Date_Campagne >'1940-11-30')
                    $fronts.="<option value='1'>Front Est</option>";
                if($Front_Premium !=2)
                    $fronts.="<option value='2'>Front Med</option>";
                if($Front_Premium !=4 and $Date_Campagne >'1940-11-30')
                    $fronts.="<option value='4'>Front Nord</option>";
			break;
			case 2:
                if($Front_Premium !=0)
                    $fronts="<option value='10' selected>Front Ouest</option>";
                if($Front_Premium !=2)
                    $fronts.="<option value='2'>Front Med</option>";
                if($Front_Premium !=3 and $Date_Campagne >'1940-11-30')
                    $fronts.="<option value='3'>Front Pacifique</option>";
			break;
			case 4:
                if($Front_Premium !=0)
                    $fronts="<option value='10' selected>Front Ouest</option>";
                if($Front_Premium !=2)
                    $fronts.="<option value='2'>Front Med</option>";
			break;
			case 6:
                if($Front_Premium !=1 and $Date_Campagne >'1940-11-30')
                    $fronts.="<option value='1'>Front Est</option>";
                if($Front_Premium !=0)
                    $fronts="<option value='10'>Front Ouest</option>";
                if($Front_Premium !=2)
                    $fronts.="<option value='2' selected>Front Med</option>";
			break;
			case 7:
                if($Front_Premium !=0)
                    $fronts="<option value='10'>Front Ouest</option>";
                if($Front_Premium !=3)
                    $fronts.="<option value='3' selected>Front Pacifique</option>";
			break;
			case 8:
				$fronts="<option value='5'>Front Arctique</option><option value='1'>Front Est</option><option value='4'>Front Nord</option>";
			break;
			case 9:
				$fronts="<option value='3'>Front Pacifique</option>";
			break;
			default:
				$fronts='';
			break;
		}
		echo "<div id='col_droite'><h2><small>Changement de front</small></h2><form action='index.php?view=em_changer_front' method='post'>
			<select name='front' class='form-control' style='width: 200px'><option value='0'>Ne rien changer</option>".$fronts."</select>
			<img src='/images/CT24.png' title='Cette action consommera tous vos CT restants, avec un minimum de 24'><input type='submit' value='Changer' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>
			<div class='alert alert-danger'>L'officier qui change de front abandonne automatiquement tout poste qu'il occupe à l'état-major.<br>Cette action consommera tous vos Crédits Temps restants, avec un minimum de 24.</div>
		</div>";
	}?></div>
	<div id="profil_decorations">
		<table class='table'>
			<thead><tr><th>Brevets et Décorations</th></tr></thead>
			<tr><td>
			<?
				if($Beta)
					echo "<img src='images/pmedal".$Pays."13.gif'>";
				if($medal)
				{
					$mes='';
					for($i=1;$i<=$medal;$i++)
					{
						$medal_txt=GetMedal_Name($Pays,$i,1);
						if(($Pays ==1 and $i ==3) or ($Pays ==2 and $i >2 and $i <6))
							$mes.="<img title='".$medal_txt."' src='images/pmedal".$Pays.$i."t.gif'>";
						else
							$mes.="<img title='".$medal_txt."' src='images/pmedal".$Pays.$i.".gif'>";
					}
					echo $mes;
				}
				if($kreta >0 or $afrika ==1 or $ost >0 or $camp_fr >0)
				{
					if($kreta >0)
					{
						if($Pays ==1)
							$kreta_txt="<img title='Armelband Kreta' src='images/pkreta.gif'>";
						elseif($Pays ==8)
							$kreta_txt="<img title='Orden Kutuzova' src='images/kutuzov".$kreta.".png'>";
					}
					if($afrika ==1)
					{
						if($Pays ==1)
							$afrika_txt="<img title='Armelband Afrika' src='images/pafrika.gif'>";
						elseif($Pays ==8)
							$afrika_txt="<img title='Orden Alexander Nevsky' src='images/nevsky.png'>";
                        elseif($Pays ==6)
                            $afrika_txt="<img title='Campagne Med' src='images/pmedal617.gif'>";
                        else
                            $afrika_txt="<img title='Campagne Med' src='images/pmedal".$Pays."15.gif'>";
					}
					if($ost >0)
					{
						if($Pays ==1)
							$ost_txt="<img title='Ost Front' src='images/pmedal118.gif'>";
						elseif($Pays ==8)
							$ost_txt="<img title='Orden Khmelnitsky' src='images/khmelnitsky".$ost.".png'>";
					}
                    if($camp_fr >0)
                    {
                        if($Pays ==1)
                            $camp_fr_txt="<img title='West Front' src='images/pmedal116.gif'>";
                        else
                            $camp_fr_txt="<img title='Campagne Ouest' src='images/pmedal".$Pays."14.gif'>";
                    }
					echo $ost_txt.' '.$afrika_txt.' '.$kreta_txt.' '.$camp_fr_txt;
				}
			?>
			</td></tr>
		</table>
	</div>
<?
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';