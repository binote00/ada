<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once './jfv_include.inc.php';
	include_once './jfv_txt.inc.php';
	include_once './jfv_inc_em.php';
	include_once './menu_em.php';
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin)
	{
		if($Premium)$Legend=true;
		if($GHQ)$Front_title='Front';
		$Sqn=GetSqn($country);
        if($_SESSION['msg_esc'])
            $Alert = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_esc'].'</div>';
        elseif($_SESSION['msg_esc_red'])
            $Alert = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_esc'].'</div>';
        $_SESSION['msg_esc'] = false;
        $_SESSION['msg_esc_red'] = false;
        $tri=Insec($_POST['tri']);
		if(!$tri)
			$querytri='';
		else
			$querytri="l.Longitude DESC,l.Latitude DESC,";
		if($Type >0)
			$units_list=$Type;
		elseif($GHQ)
			$Type=96;
		else
			$units_list="1,2,3,4,7,9,10,11,12";
		if($Type ==98)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Base=l.ID AND u.Pays='$country' AND u.Avion1_Nbr=0 AND u.Avion2_Nbr=0 AND u.Avion3_Nbr=0 AND u.Active_Date <='$Date_Campagne' ORDER BY u.Type DESC, u.Nom ASC";
		elseif($Type ==95)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Base=l.ID AND u.Pays='$country' AND u.Garnison <50 ORDER BY ".$querytri."u.Nom ASC";
		elseif($Type ==97)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Base=l.ID AND u.Pays='$country' AND u.Ravit >0 ORDER BY ".$querytri."u.Nom ASC";
		elseif($Type ==96)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Base=l.ID AND u.Pays='$country' AND u.NoEM >0 ORDER BY ".$querytri."u.Nom ASC";
		elseif($GHQ >0)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Etat=1 AND u.Base=l.ID ORDER BY ".$querytri."u.Type DESC,u.Reputation DESC,u.Nom ASC";
		elseif($Front ==3)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Etat=1 AND u.NoEM=0 AND u.Base=l.ID AND l.Longitude >67 ORDER BY u.Commandant DESC,u.Type DESC,u.Nom ASC";
		elseif($Front ==2)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Etat=1 AND u.NoEM=0 AND u.Base=l.ID AND l.Latitude <43 AND l.Longitude <67 ORDER BY u.Commandant DESC,u.Type DESC,u.Nom ASC";
		elseif($Front ==1)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Etat=1 AND u.NoEM=0 AND u.Base=l.ID AND l.Latitude >41 AND l.Latitude <=50.5 AND l.Longitude >14 ORDER BY u.Commandant DESC,u.Type DESC,u.Nom ASC";
		elseif($Front ==4)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Etat=1 AND u.NoEM=0 AND u.Base=l.ID AND l.Latitude >50.5 AND l.Longitude >14 ORDER BY u.Commandant DESC,u.Type DESC,u.Nom ASC";
		elseif($Front ==5)
			$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
			WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Etat=1 AND u.NoEM=0 AND u.Base=l.ID AND l.Latitude >60 AND l.Longitude <60 ORDER BY u.Commandant DESC,u.Type DESC,u.Nom ASC";
		else
		{
			if($country ==7)
				$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
				WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Etat=1 AND u.NoEM=0 AND u.Base=l.ID AND l.Latitude <60 AND l.Longitude <=14 ORDER BY u.Commandant DESC,u.Type DESC,u.Nom ASC";
			else
				$query="SELECT u.*,l.Latitude,l.Longitude,l.QualitePiste,l.BaseAerienne,l.Zone,l.Flag,l.Flag_Air,l.Flag_Port,l.Nom as Base_Nom FROM Unit as u,Lieu as l 
				WHERE u.Type IN (".$units_list.") AND u.Pays='$country' AND u.Etat=1 AND u.NoEM=0 AND u.Base=l.ID AND l.Latitude >=43 AND l.Latitude <60 AND l.Longitude <=14 ORDER BY u.Commandant DESC,u.Type DESC,u.Nom ASC";
		}
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$result=mysqli_query($con,$query);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$Anti_Lag_Count++;
				$Skill_Moy=0;
				if($Legend and $Anti_Lag_Count >20)$Legend=false;
				$Unite=$data['ID'];
				$Base_Nom=$data['Base_Nom'];
				$QualitePiste=$data['QualitePiste'];
				$Unite_Nom=$data['Nom'];
				$Unite_Type=$data['Type'];
				$Commandant_u=$data['Commandant'];
				$Officier_Adjoint_u=$data['Officier_Adjoint'];
				$Officier_Technique=$data['Officier_Technique'];
				$Pilotes_max=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1"),0);
				$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Courage >0 AND Moral >0 AND Actif=1"),0);
				if($Unite_Type ==9)
					$Faction_Air=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Port']."'"),0);
				else
					$Faction_Air=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Air']."'"),0);
				$Faction_Flag=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag']."'"),0);
				$Pays_nom=GetPays($data['Pays']);
                if($data['Armee']){
                    $btn_color_ordres = "warning";
                }
                else{
                    $btn_color_ordres = "default";
                }
				if($GHQ)
				{
					$Front_unit=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
					$Front_unit_txt=GetFront($Front_unit);
					if(($Faction !=$Faction_Flag or $Faction !=$Faction_Air) and !$data['Porte_avions'])$Front_unit_txt.="<img src='images/mortar.png' title='Sous le feu'>";
					if($Type >0)$Front_nbr[$Front_unit]+=1;
					if(!$data['Etat'])
					{
						if($data['Active_Date'] <=$Date_Campagne)
						{
                            $Front_unit_txt.="<form action='index.php?view=ghq_active_unit' method='post'><input type='hidden' name='Unit' value='".$Unite."'><input type='hidden' name='Type' value='".$Unite_Type."'>
                            <input type='submit' value='Activer' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
						}
						else
							$Front_unit_txt.="<span class='text-danger'>Activer</span>";
					}
					elseif(!$data['Mission_IA'])
						$Front_unit_txt.="<form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$Unite."'>
						<input type='submit' value='Ordre' class='btn btn-sm btn-".$btn_color_ordres."' onclick='this.disabled=true;this.form.submit();'></form>";
					else
						$Front_unit_txt.="<br><span class='label label-danger'>En vol</span>";
				}
				elseif(($Faction !=$Faction_Flag or $Faction !=$Faction_Air) and !$data['Porte_avions'])
					$Front_unit_txt="<img src='images/mortar.png' title='Sous le feu'>";
				else
					$Front_unit_txt='';
				if($data['Armee'])
				    $Front_unit_txt.="<span class='label label-danger'>".GetData("Armee","ID",$data['Armee'],"Nom")."</span>";
				$Cdt='';
				$OT='';
				$OA='';
				$img_unit=Afficher_Icone($data['ID'],$data['Pays'],$Unite_Nom);
				$Detail_txt="<a href='rapport_unite.php?unite=".$data['ID']."'><div class='i-flex dossier' title='Détail'></div></a>";
				if($data['Type'] ==9)
					$Piste='<span class="badge">'.$Base_Nom.'</span><br><span class="label label-success">100%</span>';
				elseif($data['Type'] ==10 or $data['Type'] ==12)
				{
					if($data['Porte_avions'] >0)
					{
						$resultpa=mysqli_query($con,"SELECT r.HP,c.Nom,c.Taille,c.HP as HP_max FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Vehicule_ID='".$data['Porte_avions']."'");
						if($resultpa)
						{
							while($datapa=mysqli_fetch_array($resultpa,MYSQLI_ASSOC))
							{
							    if($datapa['HP'])
    								$Piste_pa=round(100/($datapa['HP_max']/$datapa['HP']));
							    else
                                    $Piste_pa='0';
								$Piste="<i>".$datapa['Nom']."</i><br><b>".$Piste_pa."%</b>";
							}
							mysqli_free_result($resultpa);		
						}
						else
                            $Piste='<span class="badge">'.$Base_Nom.'</span><br><span class="label label-danger">Détruit</span>';
					}
					else
                        $Piste='<span class="badge">'.$Base_Nom.'</span><br><span class="label label-success">100%</span>';
				}
				else
					$Piste='<span class="badge">'.$Base_Nom."</span><br><img src='images/base".$data['BaseAerienne'].$data['Zone'].".png'><span class='label label-".ColorNbr($QualitePiste,100)."'>".$QualitePiste."%</span>";
				if($Pilotes >0)
				{
					if($data['Type'] ==2 or $data['Type'] ==7 or $data['Type'] ==10 or $data['Type'] ==11){
						$Skill_Moy=mysqli_result(mysqli_query($con,"SELECT AVG(Pilotage+Bombardement)/2 FROM Pilote_IA WHERE Unit='".$data['ID']."' AND Actif=1"),0);
					}
					elseif($data['Type'] ==3 or $data['Type'] ==6 or $data['Type'] ==9){
						$Skill_Moy=mysqli_result(mysqli_query($con,"SELECT AVG(Pilotage+Vue)/2 FROM Pilote_IA WHERE Unit='".$data['ID']."' AND Actif=1"),0);
					}
					else{
						$Skill_Moy=mysqli_result(mysqli_query($con,"SELECT AVG(Pilotage+Tactique+Tir+Vue)/4 FROM Pilote_IA WHERE Unit='".$data['ID']."' AND Actif=1"),0);
					}
				}
				if($Commandant_u >0)
				{
                    $btn_color_ordres = "warning";
					$resultc=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite,
					(SELECT Credits_Date BETWEEN NOW() - INTERVAL 7 DAY AND NOW()) as Too_Late FROM Pilote WHERE ID='$Commandant_u'");
					if($resultc)
					{
						while($datac=mysqli_fetch_array($resultc,MYSQLI_ASSOC))
						{
							$Av1=GetAvancement($datac['Avancement'],$country);
							$Cdt=$Av1[0]."<br>".$datac['Nom'];
							if(!$datac['Too_Late'] and ($OfficierEMID ==$Commandant or $Admin))
							{
								$bouton_virer="<form action='em_gestioncdt1.php' method='post'><input type='hidden' name='Mutation_Cdt' value=".$Commandant_u.">
												<img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Virer' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
								$Cdt.="<br><i><span class='text-danger'>".$datac['Activite']."</span></i>".$bouton_virer;
							}
							else
								$Cdt.="<br><i>".$datac['Activite']."</i>";
						}
						mysqli_free_result($resultc);
					}
				}
				elseif($OfficierEMID ==$Commandant and $data['Etat'] >0)
				{
					if($data['Mission_IA'])
						$Cdt='<span class="label label-danger">En vol</span>';
					elseif($data['NoEM'])
						$Cdt='<span class="label label-danger">GHQ</span>';
					else
						$Cdt="<form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$Unite."'>
						<input type='Submit' value='Ordre' class='btn btn-sm btn-".$btn_color_ordres."' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				elseif($Type ==97)
				{
					if($data['Ravit'] ==2)
						$Cdt='<img src="images/em_upgrade.png">';
					elseif($data['Ravit'] ==1)
						$Cdt='<img src="images/poil_icon.png">';
				}
				if($Officier_Adjoint_u or $Officier_Technique or $Commandant_u){
                    $img_unit.='<a href="#" class="popup"><img src="images/obs.png" alt="Pilote Joueur" style="width: 10%;"><span>Cette unité comporte des pilotes joueurs dans ses effectifs</span></a>';
                }
                if($Admin){
                    $img_unit.='<form action="admin_esc_free.php" method="post">
                                    <input type="hidden" name="id" value="'.$Unite.'">
                                    <input type="hidden" name="type" value="'.$Type.'">
                                    <input class="btn btn-sm btn-info" type="submit" value="Free">
                                </form>';
				}
                if($Premium){
                    $Pil_Skills='';
                    $skill_txt='';
                    $Pilotes_result=mysqli_query($con,"SELECT s.ID,s.Infos,p.Courage,p.Moral,p.Actif FROM Pilote_IA AS p,Skills as s WHERE s.ID=p.Skill AND s.Team=1 AND p.Unit='$Unite' AND p.Actif=1");
                    if($Pilotes_result)
                    {
                        while($datap=mysqli_fetch_array($Pilotes_result,MYSQLI_ASSOC))
                        {
                            if($datap['Courage'] >0 and $datap['Moral'] >0)
                                $skill_txt.="<a href='#' class='popup'><img src='images/skills/skill".$datap['ID']."p.png'><span>".substr($datap['Infos'],strpos($datap['Infos'], '['),strlen($datap['Infos']))."</span></a>";
                            else
                                $skill_txt.="<a href='#' class='popup'><img class='img_opa' src='images/skills/skill".$datap['ID']."p.png'><span>".substr($datap['Infos'],strpos($datap['Infos'], '['),strlen($datap['Infos']))."</span></a>";
                        }
                        mysqli_free_result($Pilotes_result);
                    }
                    if($skill_txt){
                        $Pil_Skills='<br>'.$skill_txt;
                    }
                }
				$MaxFlight=GetMaxFlight($data['Type'],$data['Reputation'],0);
				$txt.="<tr><th>".$img_unit."<br>".$Unite_Nom."</th><td>".$Front_unit_txt."</td> 
				<td class='hidden-sm-down'>".$Cdt."</td><td><span class='label label-".ColorNbr($Pilotes,$Pilotes_max)."'>".$Pilotes."/".$Pilotes_max."</span>".$Pil_Skills."</td><td>".$data['Reputation']."</td><td>".round($Skill_Moy)."</td><td>".$Piste."</td>
				<td>".GetAvionIcon($data['Avion1'],$country,0,$data['ID'],$Front,false,$Legend)."<br><span class='label label-".ColorNbr($data['Avion1_Nbr'],$MaxFlight)."'>".$data['Avion1_Nbr']."/".$MaxFlight."</span></td>
				<td>".GetAvionIcon($data['Avion2'],$country,0,$data['ID'],$Front,false,$Legend)."<br><span class='label label-".ColorNbr($data['Avion2_Nbr'],$MaxFlight)."'>".$data['Avion2_Nbr']."/".$MaxFlight."</span></td>
				<td>".GetAvionIcon($data['Avion3'],$country,0,$data['ID'],$Front,false,$Legend)."<br><span class='label label-".ColorNbr($data['Avion3_Nbr'],$MaxFlight)."'>".$data['Avion3_Nbr']."/".$MaxFlight."</span></td>
				<td>".$Detail_txt."</td></tr>";
			}
			mysqli_free_result($result);
		}
		mysqli_close($con);
		//Output
		echo "<p><a class='btn btn-default' href='index.php?view=em_unites'>Tout</a>";
		if($Type ==7)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_7'>Attaque</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_unites_7'>Attaque</a>";
		if($Type ==2)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_2'>Bombardier</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_unites_2'>Bombardier</a>";
		if($Type ==11)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_11'>Bombardier lourd</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_unites_11'>Bombardier lourd</a>";
		if($Type ==1)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_1'>Chasse</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_unites_1'>Chasse</a>";
		if($Type ==12)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_12'>Chasse embarquée</a>";
		elseif($country ==2 or $country ==7 or $country ==9)
			echo "<a class='btn btn-default' href='index.php?view=em_unites_12'>Chasse embarquée</a>";
		if($Type ==4)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_4'>Chasse lourde</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_unites_4'>Chasse lourde</a>";
		if($Type ==10)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_10'>Embarqué</a>";
		elseif($country ==2 or $country ==7 or $country ==9)
			echo "<a class='btn btn-default' href='index.php?view=em_unites_10'>Embarqué</a>";
		if($Type ==9)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_9'>Pat Mar</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_unites_9'>Pat Mar</a>";
		if($Type ==3)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_3'>Reco</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_unites_3'>Reco</a>";
		if($Type ==6)
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_6'>Transport</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_unites_6'>Transport</a>";
		if($Type ==95)
		{
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_95'>Troupes</a>";
			$legende='Unités dont les troupes de défense ont besoin de renforts';
		}
		else
			echo "<a class='btn btn-warning' href='index.php?view=em_unites_95'>Troupes</a>";
		if($Type ==98)
		{
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_98'>Demob</a>";
			$legende='Unités ne possédant plus aucun avion opérationnel';
		}
		elseif($GHQ or $Admin)
			echo "<a class='btn btn-warning' href='index.php?view=em_unites_98'>Demob</a>";
		if($Type ==97)
		{
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_97'>Ravit</a>";
			$legende="Unités demandant un ravitaillement <img src='images/poil_icon.png'> ou un changement de matériel <img src='images/em_upgrade.png'>";
		}
        elseif($GHQ or $Admin)
			echo "<a class='btn btn-warning' href='index.php?view=em_unites_97'>Ravit</a>";
		if($Type ==96)
		{
			echo "<a class='btn btn-primary' href='index.php?view=em_unites_96'>GHQ</a>";
			$legende="Unités réservées au GHQ";
		}
		else
			echo "<a class='btn btn-warning' href='index.php?view=em_unites_96'>GHQ</a>";
		echo '</p>';
		if($GHQ)
		{
			for($x=0;$x<6;$x++)
			{
				if(!$Front_nbr[$x])
					$Front_nbr[$x]=0;
			}
			echo "<div class='i-flex'><form action='#' method='post'><input type='hidden' name='tri' value='0'><input type='submit' value='Ouest' class='btn btn-sm btn-primary' onclick='this.disabled=true;this.form.submit();'></form><span class='badge'>".$Front_nbr[0]."</span>
			<form action='#' method='post'><input type='hidden' name='tri' value='1'><input type='submit' value='Est' class='btn btn-sm btn-primary' onclick='this.disabled=true;this.form.submit();'></form><span class='badge'>".$Front_nbr[1]."</span>
			<form action='#' method='post'><input type='hidden' name='tri' value='4'><input type='submit' value='Nord' class='btn btn-sm btn-primary' onclick='this.disabled=true;this.form.submit();'></form><span class='badge'>".$Front_nbr[4]."</span>
			<form action='#' method='post'><input type='hidden' name='tri' value='2'><input type='submit' value='Med' class='btn btn-sm btn-primary' onclick='this.disabled=true;this.form.submit();'></form><span class='badge'>".$Front_nbr[2]."</span>
			<form action='#' method='post'><input type='hidden' name='tri' value='3'><input type='submit' value='Pacifique' class='btn btn-sm btn-primary' onclick='this.disabled=true;this.form.submit();'></form><span class='badge'>".$Front_nbr[3]."</span>
			<form action='#' method='post'><input type='hidden' name='tri' value='5'><input type='submit' value='Arctique' class='btn btn-sm btn-primary' onclick='this.disabled=true;this.form.submit();'></form><span class='badge'>".$Front_nbr[5]."</span></div>";
			/*echo "<h2>Répartition par fronts</h2>
			<span class='label label-primary'>Est</span><span class='badge'>".$Front_nbr[0]."</span>
			<span class='label label-primary'>Est</span><span class='badge'>".$Front_nbr[1]."</span>
			<span class='label label-primary'>Nord</span><span class='badge'>".$Front_nbr[4]."</span>
			<span class='label label-primary'>Med</span><span class='badge'>".$Front_nbr[2]."</span>
			<span class='label label-primary'>Pacifique</span><span class='badge'>".$Front_nbr[3]."</span>
			<span class='label label-primary'>Arctique</span><span class='badge'>".$Front_nbr[5]."</span>";*/
		}
		//<th>".GetStaff($country,2)."</th><th>".GetStaff($country,3)."</th>
        if($Alert)
    		echo $Alert;
        else
            echo "<p class='lead'>".$legende."</p>
            <table class='table table-dt table-striped table-condensed'><thead><tr><th>Unité</th><th>".$Front_title."</th>
            <th class='hidden-sm-down'>".GetStaff($country,1)."</th><th>Pilotes</th><th>Réput</th><th>Exp</th>
            <th>Piste</th><th>".$Sqn." 1</th><th>".$Sqn." 2</th><th>".$Sqn." 3</th><th>Détail</th></tr></thead>".$txt."</table>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';