<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Front,Pays,Division,Avancement,Credits FROM Officier WHERE ID='$OfficierID'");
	//mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Front=$data['Front'];
			$Pays=$data['Pays'];
			$Division=$data['Division'];
			$Avancement=$data['Avancement'];
			$Credits=$data['Credits'];
		}
		mysqli_free_result($result);
	}
	//$con=dbconnecti();
	$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
	$resultd=mysqli_query($con,"SELECT Nom,Armee,Cdt,Base FROM Division WHERE ID='$Division'");
	$result3=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
	FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Bataillon='$OfficierID' AND r.NoEM=0 ORDER BY r.Lieu_ID ASC,r.Placement ASC");
	$Reg_QG=mysqli_result(mysqli_query($con,"SELECT ID FROM Regiment WHERE Officier_ID='$OfficierID'"),0);
	if(!$Reg_QG)
	{
		$Base_Arriere=Get_Retraite($Front,$country,40);
		$query2="INSERT INTO Regiment (Officier_ID,Pays,Vehicule_ID,Lieu_ID,Vehicule_Nbr,Placement,Camouflage,HP)";
		$query2.="VALUES ('$OfficierID','$Pays',4000,'$Base_Arriere',1,0,1,10000)";
		$create_qg=mysqli_query($con,$query2);
		$Reg_QG=mysqli_insert_id($con);
	}
	$resultr=mysqli_query($con,"SELECT r.Lieu_ID,r.Placement,l.Zone,l.Port,l.NoeudF,l.Plage,l.Flag,l.Flag_Gare,l.Flag_Port,l.Nom as Base FROM Regiment as r,Lieu as l WHERE r.ID='$Reg_QG' AND r.Lieu_ID=l.ID");
	mysqli_close($con);
	if($resultr)
	{
		while($datar=mysqli_fetch_array($resultr,MYSQLI_ASSOC))
		{
			$Base_Nom=$datar['Base'];
			$Placement=$datar['Placement'];
			$Base=$datar['Lieu_ID'];
			$Zone=$datar['Zone'];
			$NoeudF=$datar['NoeudF'];
			$Port=$datar['Port'];
			$Plage=$datar['Plage'];
			$Flag=$datar['Flag'];
			$Flag_Gare=$datar['Flag_Gare'];
			$Flag_Port=$datar['Flag_Port'];
		}
		mysqli_free_result($resultr);
	}
	if($resultd)
	{
		while($datad=mysqli_fetch_array($resultd,MYSQLI_ASSOC))
		{
			$Div_Nom=$datad['Nom'];
			$Div_Armee=$datad['Armee'];
			$Div_Cdt=$datad['Cdt'];
			$Div_Base=$datad['Base'];
		}
		mysqli_free_result($resultd);
	}
	if($result3)
	{
		$today=getdate();
		while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
		{
			if($data3['Move'])
				$Move="<div class='i-flex led_red'></div>"; //Afficher_Image('images/led_red.png','','',10);
			else
				$Move="<div class='i-flex led_green'></div>"; //Afficher_Image('images/led_green.png','','',10);
			if(!$data3['Visible'])
				$Camo_txt=Afficher_Image('images/camouflage.png','','Camoufl�',10);
			else
				$Camo_txt=false;
			if($data3['Bomb_IA'])$Camo_txt.="<a href='#' class='popup'><img src='images/map/noia.png'><span>Ne peut plus �tre cibl� par les bombardements tactiques IA jusque au prochain passage de date</span></a>";
			if($data3['Ravit'])$Camo_txt.="<a href='#' class='popup'><img src='images/map/air_ravit.png'><span>Ravitaill� par air</span></a>";
			if($data3['mobile'] ==5)
			{
				$per_c=round(100/($data3['HP_max']/$data3['HP']));
				if($per_c >99)
					$HP_per="<span class='label label-success'>".$per_c."%</span>";
				elseif($per_c <1)
					$HP_per="<span class='label label-danger'>".$per_c."%</span>";
				else
					$HP_per="<span class='label label-warning'>".$per_c."%</span>";
				if($data3['Categorie'] ==20 or $data3['Categorie'] ==21 or $data3['Categorie'] ==22 or $data3['Categorie'] ==23 or $data3['Categorie'] ==24 or $data3['Categorie'] ==17)
				{
					if($data3['Autonomie'])
						$HP_per.="<span class='label label-warning'><a class='lien' title='Aide' href='help/aide_jours.php' target='_blank'>".$data3['Autonomie']." Jours</a></span>";
					else
						$HP_per.="<span class='label label-danger'><a class='lien' title='Aide' href='help/aide_jours.php' target='_blank'>".$data3['Autonomie']." Jours</a></span>";
				}
			}
			else
				$HP_per=false;
			if($today['mday'] >$data3['Jour']+1)
				$Combat_flag=false;
			elseif($today['mon'] >$data3['Mois'])
				$Combat_flag=false;
			elseif($today['mday']!=$data3['Jour'] and $today['hours']>=$data3['Heure'])
				$Combat_flag=false;
			else
				$Combat_flag=true;
			if($today['mday'] >$data3['Jour_m']+1)
				$Move_flag=false;
			elseif($today['mon'] >$data3['Mois_m'])
				$Move_flag=false;
			elseif($today['mday']!=$data3['Jour_m'] and $today['hours']>=$data3['Heure_m'])
				$Move_flag=false;
			else
				$Move_flag=true;
			if($data3['Position'] ==12)
				$Action="<span class='label label-danger'>En Vol</span>";
			elseif($data3['Atk'] ==1 or $Combat_flag)
			{
				$Action="<span class='text-danger'>En Combat<br>jusque ".$data3['Heure']."</span>";
				if(!$data3['Move'] and !$data3['Atk'])
				{
					$Action.="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$data3['ID']."'><input type='hidden' name='reset' value='9'><input type='hidden' name='Max' value='".$data3['Vehicule_Nbr']."'>
					<a href='#' class='popup'><input type='Submit' value='Fuir' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
					<span>Cette action permettra � l'unit� d'agir, mais r�duira ses effectifs � 1</span></a></form>";
					if($data3['Vehicule_ID']<5000 and $data3['Vehicule_Nbr'] >0 and $data3['Atk_Eni'])
					{
						$Action.="<form action='index.php?view=ground_pl' method='post'>
									<input type='hidden' name='CT' value='0'>
									<input type='hidden' name='distance' value='500'>
									<input type='hidden' name='Action' value='".$data3['Atk_Eni']."_0'>
									<input type='hidden' name='Veh' value='".$data3['Vehicule_ID']."'>
									<input type='hidden' name='Reg' value='".$data3['ID']."'>
									<input type='hidden' name='Pass' value='".$data3['Vehicule_Nbr']."'>
						<a href='#' class='popup'><input type='Submit' value='Riposter' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
						<span>Cette action permettra de tenter de contre-attaquer l'unit� qui vous a engag�</span></a></form>";
					}
				}
			}
			elseif($data3['mobile'] !=5 and ($data3['Move'] ==1 or $Move_flag))
				$Action="<span class='text-danger'>Mouvement<br>jusque ".$data3['Heure_m']."</span>";
			else
				$Action="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data3['ID']."'>
				<input type='Submit' value='Ordres' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			if($data3['Experience'] >249)
				$Exp_txt="<span class='label label-success'>".$data3['Experience']."XP</span>";
			elseif($data3['Experience'] >49)
				$Exp_txt="<span class='label label-primary'>".$data3['Experience']."XP</span>";
			elseif($data3['Experience'] >1)
				$Exp_txt="<span class='label label-warning'>".$data3['Experience']."XP</span>";
			else
				$Exp_txt="<span class='label label-danger'>".$data3['Experience']."XP</span>";
			if($data3['Skill'])
				$Skill_txt="<a href='index.php?view=reg_skills'><img src='images/skills/skillo".$data3['Skill'].".png' style='width:10%;'></a>";
			else
				$Skill_txt="";			
			$Regs_bat.="<tr><td>".$Front_ghq.Afficher_Image('images/div/div'.$data3['Division'].'.png','images/'.$country.'div.png','',0)."</td><td>".$data3['ID']."e</td>
			<td>".$data3['Vehicule_Nbr']." ".GetVehiculeIcon($data3['Vehicule_ID'],$data3['Pays'],0,0,$Front).$Exp_txt.$HP_per.$Skill_txt.$Camo_txt."</td><td>".$Move." ".$data3['Ville']."</td><td>".GetPosGr($data3['Position']).' '.GetPlace($data3['Placement'])."</td><td>".$Action."</td></tr>";		}
		mysqli_free_result($result3);
	}
	echo "<h1>".$OfficierID."e Bataillon</h1>";
	if(!$Division)
	{
		echo "<div class='alert alert-warning'>Votre officier doit faire partie d'une division pour commander des unit�s.
		<br>Votre hi�rarchie examinera votre demande de rejoindre une division et vous affectera des unit�s une fois votre demande valid�e.</div>";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT d.ID,d.Nom,d.Maritime,l.Nom as Base FROM Division as d,Lieu as l WHERE d.Pays='$Pays' AND d.Front='$Front' AND d.Base=l.ID AND d.Active=1 ORDER BY d.Maritime ASC,d.Nom ASC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				if(!$naval_opt and $data['Maritime'])
				{
					$divs.="</optgroup><optgroup label='Flottilles'>";
					$naval_opt=true;
				}
				$divs.="<option value=".$data['ID'].">".$data['Nom']." (".$data['Base'].")</option>";
			}
			mysqli_free_result($result);
			unset($data);
		}
		echo "<form action='choix_division.php' method='post'>
		<input type='hidden' name='Off' value='".$ID."'>
		<h2>Rejoindre une Division <a href='help/aide_division.php' target='_blank'><img src='images/help.png'></a></small></h2>
		<select name='Div' class='form-control' style='width: 300px'><option value='0' selected>- Aucune -</option><optgroup label='Divisions Terrestres'>".$divs."</optgroup></select>
		<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		//Changement de Front
		if(in_array($Base,$Transit_cities))
		{
			$Faction_flag=GetData("Pays","ID",$Flag,"Faction");
			if($Faction_flag ==$Faction)
			{
				$menu_qg="<form action='index.php?view=ground_dig' method='post'><input type='hidden' name='Loc' value='".$Base."'><input type='hidden' name='Reg' value='".$Reg_QG."'>
				<input type='hidden' name='CT_M' value='4'><input type='hidden' name='CT_F' value='8'><select name='Action' class='form-control' style='width: 200px'>";
				if($Base ==344 or $Base ==189 or $Base ==198 or $Base ==201 or $Base ==586)
					$menu_qg.="<option value='301'>Changer de front (1CT)</option>";
				elseif($Base ==2)
					$menu_qg.="<option value='302'>Changer de front (1CT)</option>";
				elseif($Base ==1280 or $Base ==615 or $Base ==619 or $Base ==621 or $Base ==967)
					$menu_qg.="<option value='306'>Changer de front (1CT)</option>";
				elseif($Base ==199 or $Base ==218)
					$menu_qg.="<option value='308'>Changer de front (1CT)</option>";
				elseif($Base ==709)
					$menu_qg.="<option value='309'>Changer de front (1CT)</option>";
				elseif($Base ==1896)
					$menu_qg.="<option value='303'>Changer de front (1CT)</option>";
				elseif($Base ==704 or $Base ==898 or $Base ==2079)
					$menu_qg.="<option value='307'>Changer de front (1CT)</option>";
				elseif($Credits >=40 and $Base ==1567)
					$menu_qg.="<option value='304'>Changer de front (40CT)</option>";
				elseif($Credits >=40 and $Base ==2149)
					$menu_qg.="<option value='305'>Changer de front (40CT)</option>";
				$menu_qg.="</select><input type='Submit' value='Valider' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
				echo "<h2>Changer de Front</h2>".$menu_qg;
			}
		}
	}
	else
	{
		$Grade_Level=GetAvancement($Avancement,$country);
		$Am_Nbr=$Grade_Level[1]-8;
		$Cie_nbr=floor($Avancement/5000)+3;
		if($Div_Cdt)$Div_Cdt_Nom=GetData("Officier","ID",$Div_Cdt,"Nom");
		if($Div_Base)$Base_Arriere=GetData("Lieu","ID",$Div_Base,"Nom");
		$con=dbconnecti();
		$Sections=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sections WHERE OfficierID='$OfficierID'"),0);
		$results=mysqli_query($con,"SELECT SectionID FROM Sections WHERE OfficierID='$OfficierID'");
		mysqli_close($con);
		if($results)
		{
			while($datas=mysqli_fetch_array($results)) 
			{
				$Infos_txt="";
				if($datas['SectionID'] ==7)
				{
					$Infos_txt="<b>Section d'�tat-major</b><br>";
					if($Grade_Level[1] >12)
						$Infos_txt.="Le co�t en CT des actions de bataillon est r�duit de 3 (minimum 1CT)";
					elseif($Grade_Level[1] >10)
						$Infos_txt.="Le co�t en CT des actions de bataillon est r�duit de 2 (minimum 1CT)";
					else
						$Infos_txt.="Le co�t en CT des actions de bataillon est r�duit de 1 (minimum 1CT)";
				}
				elseif($datas['SectionID'] ==6)
				{
					$Infos_txt="<b>Section Topographique</b><br>Le bataillon reconna�t automatiquement le lieu o� il se trouve (une fois par jour lors de la 1e connexion)<br>";
					if($Grade_Level[1] >10)
						$Infos_txt.="Les unit�s du bataillon situ�es sur le m�me lieu sont camoufl�es automatiquement (une fois par jour lors de la 1e connexion, sauf les unit�s ayant d�j� utilis� leur action du jour)<br>";
					if($Grade_Level[1] >9)
						$Infos_txt.="Les unit�s du bataillon situ�es sur le m�me lieu b�n�ficient d'un bonus tactique";
				}
				elseif($datas['SectionID'] ==5)
				{
					$Infos_txt="<b>Section Police</b><br>Une unit� de police militaire (".pow($Grade_Level[1],2)."XP) prot�ge les infrastructures du lieu";
				}
				elseif($datas['SectionID'] ==4)
				{
					$Infos_txt="<b>Section DCA</b><br>Les unit�s de DCA du bataillon situ�es sur le m�me lieu b�n�ficient d'un bonus de d�tection, de pr�cision et de port�e augmentant avec le grade";
				}
				elseif($datas['SectionID'] ==3)
				{
					if($Grade_Level[1] >11)$pc_mun=100;
					elseif($Grade_Level[1] >10)$pc_mun=75;
					elseif($Grade_Level[1] >9)$pc_mun=50;
					else $pc_mun=25;
					$Infos_txt="<b>Section Logistique</b><br>Les unit�s du bataillon situ�es sur le m�me lieu �conomisent ".$pc_mun."% de munitions";
				}
				elseif($datas['SectionID'] ==2)
				{
					$Infos_txt="<b>Section Transmissions</b><br>Les unit�s du bataillon situ�es sur le m�me lieu peuvent demander un appui a�rien<br>";
					if($Grade_Level[1] >10)
						$Infos_txt.="Les unit�s du bataillon situ�es sur le m�me lieu ne risquent plus d'�tre cibl�s par les alli�s<br>";
					if($Grade_Level[1] >9)
						$Infos_txt.="Les unit�s du bataillon situ�es sur le m�me lieu b�n�ficient d'un bonus tactique";
				}
				elseif($datas['SectionID'] ==1)
				{
					$Infos_txt="<b>Section M�dicale</b><br>Les unit�s du bataillon situ�es sur le m�me lieu ignorent l'attrition<br>";
					if($Grade_Level[1] >11)
						$Infos_txt.="Tous les types d'unit� sont concern�s";
					elseif($Grade_Level[1] >10)
						$Infos_txt.="Tous les types d'unit� sont concern�s sauf les blind�s";
					elseif($Grade_Level[1] >9)
						$Infos_txt.="Tous les types d'unit� sont concern�s sauf les v�hicules";
					else
						$Infos_txt.="Tous les types d'unit� sont concern�s sauf les v�hicules et l'artillerie";
				}
				$section_txt.="<tr><td><img src='images/skills/skillpc".$datas['SectionID'].".png'></td><td>".$Infos_txt."</td></tr>";
			}
			mysqli_free_result($results);
			unset($datas);
		}
		if($Faction >0)
		{
			$menu_qg="<form action='index.php?view=ground_dig' method='post'><input type='hidden' name='Loc' value='".$Base."'><input type='hidden' name='Reg' value='".$Reg_QG."'>
			<input type='hidden' name='CT_M' value='4'><input type='hidden' name='CT_F' value='8'><select name='Action' class='form-control' style='width: 200px'>";
			if($Credits >=4)
			{
				$Faction_flag=GetData("Pays","ID",$Flag,"Faction");
				if($Zone !=6)
					$menu_qg.="<option value='15'>D�placer le QG (4CT)</option>";
				if($Faction_flag ==$Faction)
				{
					if($Credits >=8 and $NoeudF >10)
					{
						$Faction_Gare=GetData("Pays","ID",$Flag_Gare,"Faction");
						if($Faction_Gare ==$Faction)
							$menu_qg.="<option value='6'>D�placer le QG en utilisant le r�seau ferroviaire (8CT)</option>";
					}
					if($Credits >=8 and ($Port >0 or $Plage >0 or $Zone ==6))
					{
						$Appareiller=true;
						if($Zone !=6 and $Port)
						{
							$Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
							if($Faction_Port !=$Faction)
								$Appareiller=false;
						}
						if($Appareiller)
							$menu_qg.="<option value='106'>Appareiller (8CT)</option>";
					}
					if(in_array($Base,$Transit_cities))
						echo "<div class='alert alert-danger'>Pour changer de front, votre bataillon ne doit plus faire partie d'une division.<br>Contactez votre Commandant en Chef ou votre Planificateur Strat�gique.</div>";
				}
			}
			$menu_qg.="</select><input type='Submit' value='Valider' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		echo "<table class='table'><tr><thead><th>Division</th><th>Commandant</th><th>Base Arri�re</th></thead></tr>
		<tr><td><img src='images/div/div".$Division.".png' title='".$Div_Nom."'></td><td>".$Div_Cdt_Nom."</td><td>".$Base_Arriere."</td></tr></table>";
		echo "<table class='table'><tr><thead><th>Poste de commandement</th><th>Lieu</th><th>Sections</th><th>Compagnies</th><th>Action</th></thead></tr>
		<tr><td><img src='images/vehicules/vehicule4000.gif' title='Poste de Commandement'></td><td>".$Base_Nom."</td><td>".$Sections."/".$Am_Nbr."</td><td>".$Cie_nbr." max</td><td>".$menu_qg."</td></tr></table>";
		echo "<h2>Sections attach�es au Poste de Commandement</h2>";
		if($section_txt)
			echo "<table class='table'><tr><thead><th>Section</th><th>Effet</th></thead></tr>".$section_txt."</table>";
		else
			echo "<div class='alert alert-warning'>Votre bataillon ne poss�de aucune section</div>";
		if($Am_Nbr and $Sections <$Am_Nbr)
		{
			echo "<form action='index.php?view=ground_sec' method='post'><input type='Submit' value='Ajouter une section' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		echo "<h2>Compagnies du Bataillon</h2>
		<table class='table table-striped'><thead><tr>
			<th>Division</th>
			<th>Compagnie</th>
			<th>Troupes</th>
			<th>Lieu</th>
			<th>Position</th>
			<th>Ordres</th>
		</tr></thead>".$Regs_bat."</table>";
		if(!$Regs_bat)
		{
			if(!$Div_Cdt_Nom)
				echo "<div class='alert alert-warning'>Prenez contact avec votre <a href='index.php?view=ground_em' class='lien'>commandant en chef afin qu'il vous affecte des unit�s.</a></div>";
			else
				echo"<div class='alert alert-warning'>Votre commandant de division <b>".$Div_Cdt_Nom."</b> doit vous affecter des unit�s, prenez contact avec lui.</div>";
		}
	}
}
else
	echo "<h1>Vous devez �tre connect� pour acc�der � cette page!</h1>";
?>