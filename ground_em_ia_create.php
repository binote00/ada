<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once './jfv_include.inc.php';
    include_once './jfv_ground.inc.php';
    include_once './jfv_txt.inc.php';
    $Cat = Insec($_POST['Cat']);
    $mode = Insec($_POST['Mode']);
    $Reg = Insec($_POST['Reg']);
    $Lieu = Insec($_POST['Lieu']);
    if ($Cat > 0 and ($Reg > 0 or $mode != 1)) {
        $Date_Campagne = Conf_Update::getCampaignDate();
        $Faction = Pays::getFaction($country);
        $Lease_Score = Pays::getSelectByField('ID', $id, 'Special_Score')->Special_Score;
        $OfficierEM = Officier_em::getById($OfficierEMID);
        $Reputation = $OfficierEM->Reputation;
        $Avancement = $OfficierEM->Avancement;
        $Front_ori = $OfficierEM->Front;
        $Nid = Get_Retraite($Front_ori, $country, 40, $Cat);
        $table = '';
        $Credits_ori = 50;
        $con = dbconnecti();
        if ($Cat == 100 || $Cat == 14 || $Cat == 19 || $Cat == 25 || $Cat == 30) //navires de soutien
            $query = "SELECT * FROM Cible WHERE ID IN(5001,5002,5204,5205,5124,5392) ORDER BY Reput ASC,HP ASC,Nom ASC";
        elseif ($Cat > 16 and $Cat < 25)
            $query = "SELECT * FROM Cible WHERE Pays=? AND Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Categorie=? ORDER BY Reput ASC,HP ASC,Nom ASC"; //Navires
        else
            $query = "SELECT * FROM Cible WHERE Pays=? AND Date <='$Date_Campagne' AND Unit_ok=1 AND mobile NOT IN (4,5) AND Categorie=? AND Premium=0 AND (Lease=0 OR Lease <='$Lease_Score') ORDER BY Reput ASC,HP ASC,Nom ASC";
        $result = DBManager::getDataSQL($query, [$country, $Cat], 'ALL');
//        $result = mysqli_query($con, $query);
//		if($result)
//		{
//			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
//			{
            foreach($result as $data) {
				$ID=$data['ID'];
				$mobile=$data['mobile'];
				$Fiabilite=$data['Fiabilite'];
				if($data['mobile'] !=MOBILE_RAIL && $data['Production'] >0 && $data['Reput'] >1)
				{
					$Usines=1;
					$Indus1=GetData("Lieu","ID",$data['Usine1'],"Industrie");
					if($data['Usine2'])
					{
						$Indus2=GetData("Lieu","ID",$data['Usine2'],"Industrie");
						$Indus1+=$Indus2;
						$Usines++;
					}
					if($data['Usine3'])
					{
						$Indus3=GetData("Lieu","ID",$data['Usine3'],"Industrie");
						$Indus1+=$Indus3;
						$Usines++;
					}
					$Prod=$Indus1/$Usines;
					if($Prod <50)
						$Reste=0;
					else
					{
						//$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$ID'"),0);
						$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$ID'"),0);
						if($Cat ==20 || $Cat ==21 || $Cat ==24)
						{
							$Perdus=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Vehicule_ID='$ID' AND Vehicule_Nbr=0"),0);
							$Perdus2=0;
						}
						else
						{
							$con4=dbconnecti(4);
							$Perdus=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$ID'"),0);
							$Perdus2=mysqli_result(mysqli_query($con4,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$ID'"),0);
							if($data['Categorie'] ==5 || $data['Categorie'] ==6)
								$Perdus3=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$ID'"),0);
							mysqli_close($con4);
						}
						$Reste=floor($data['Stock']-$Service-$Service2-$Perdus-$Perdus2-$Perdus3+$data['Repare']);
						if($Reste+$Service+$Service2 >$data['Stock'])$Reste=floor($data['Stock']-$Service-$Service2);
					}
				}
				else
					$Reste=100;
				$btn_class='default';
				$Arme_Inf='';
				$Arme_Art='';
				$Arme_AT='';
				$Arme_AA='';
				if($data['Arme_Inf'])
					$Arme_Inf=GetData("Armes","ID",$data['Arme_Inf'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_Inf'],"Calibre"))."mm)<br>".$data['Arme_Inf_mun']."muns";
				elseif($data['Arme_AA3'])
					$Arme_Inf=GetData("Armes","ID",$data['Arme_AA3'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA3'],"Calibre"))."mm)<br>".$data['Arme_Inf_mun']."muns";
				if($data['Arme_Art'])
					$Arme_Art=GetData("Armes","ID",$data['Arme_Art'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_Art'],"Calibre"))."mm)<br>".$data['Arme_Art_mun']."muns";
				elseif($data['Arme_AA2'])
					$Arme_Art=GetData("Armes","ID",$data['Arme_AA2'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA2'],"Calibre"))."mm)<br>".$data['Arme_Art_mun']."muns";
				if($data['Arme_AT'])
					$Arme_AT=GetData("Armes","ID",$data['Arme_AT'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AT'],"Calibre"))."mm)<br>".$data['Arme_AT_mun']."muns";
				if($data['Arme_AA'])
					$Arme_AA=GetData("Armes","ID",$data['Arme_AA'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA'],"Calibre"))."mm)<br>".$data['Arme_AA_mun']."muns";
				$HP=$data['HP'];
				$Portee=$data['Portee'];
				$Blindage=$data['Blindage_f'];
				$Vitesse=$data['Vitesse'];
				$Taille=$data['Taille'];
				$Detection=$data['Detection'];
				$Autonomie=$data['Fuel'];
				$Charge=$data['Charge'];
				if(!$Charge)
					$Charge='Aucune';
				else
					$Charge.='kg/l';
				if($data['Carbu_ID'] ==1)
					$Fuel='Diesel';
				elseif($data['Carbu_ID'] ==87)
					$Fuel='Essence';
				else
					$Fuel='Moral';
				if($data['Type'] ==99)
					$data['Nom'].=' (Aide à neutraliser les saboteurs)';
				$Reput=$data['Reput'];
				if($mobile ==MOBILE_WATER && $Reput >=$CT_MAX)
				{
					if($data['Categorie'] ==23)
						$Reput=40;
					else
						$Reput=$CT_MAX;
				}
				else
				{
					/*if($Reste <10 and $mobile !=4 and $mobile !=5)
						$Reput*=2;
					else*/if($mode==1 &&($data['Usine1'] ==$Lieu || ($data['Usine2'] >0 && $data['Usine2'] ==$Lieu) || ($data['Usine3'] >0 && $data['Usine3'] ==$Lieu)))
					{
						$Reput=1;
						$btn_class='primary';
					}
				}
				if($Credits_ori >=$Reput)
				{
                    $Lands=GetAllies($Date_Campagne);
                    if(IsAxe($country))
                        $Allies=explode(",",$Lands[1]);
                    else
                        $Allies=explode(",",$Lands[0]);
					if($data['Usine1'])
					{
						$resultu1=mysqli_query($con,"SELECT Nom,Flag,Flag_Usine FROM Lieu WHERE ID='".$data['Usine1']."'");
						if($resultu1)
						{
							while($datau1=mysqli_fetch_array($resultu1,MYSQLI_ASSOC))
							{
								$Usine1_Nom=$datau1['Nom'];
								$Usine1_Flag=$datau1['Flag'];
								$Usine1_Flag_Usine=$datau1['Flag_Usine'];
							}
							mysqli_free_result($resultu1);
						}
					}
					if(($Cat ==5 || $Cat ==100) && $Nid && (!$data['Usine1'] || !in_array($Usine1_Flag,$Allies) || !in_array($Usine1_Flag_Usine,$Allies))) //Infanterie ou navires soutien
					{
						$resultu1=mysqli_query($con,"SELECT Nom,Flag,Flag_Usine FROM Lieu WHERE ID='".$Nid."'");
						if($resultu1)
						{
							while($datau2=mysqli_fetch_array($resultu1,MYSQLI_ASSOC))
							{
								$Usine1_Nom=$datau2['Nom'];
								$Usine1_Flag=$datau2['Flag'];
								$Usine1_Flag_Usine=$datau2['Flag_Usine'];
							}
							mysqli_free_result($resultu1);
						}
					}
					elseif($data['Usine1'])
						$Nid=$data['Usine1'];
					$Reste_txt=$Reste;
					if($data['Lease'])
					{
						if(in_array($Usine1_Flag,$Allies,true) && in_array($Usine1_Flag_Usine,$Allies,true))
							$lend_lease=true;
						else
							$lend_lease=false;
						$Reste_txt.="<a href='#' class='popup'><img src='images/lendlease.png'><span>".$data['Lease']." points Lend-Lease nécessaires<br>Total actuel de la nation <b>".$Lease_Score."</b></span></a>";
					}
					else
						$lend_lease=true;
					$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Nid' AND r.Position<>25 AND r.Placement IN(4,6) AND r.Vehicule_Nbr >0"),0);
					if(!$lend_lease)
                        $Creer_txt=Output::popup('Lend-Lease', 'Ce matériel nécessite '.$data['Lease'].' Points de Lend-Lease', 'danger');
					elseif($Front !=$Front_ori || $Enis >0)
                        $Creer_txt=Output::popup('Combat', 'Votre usine est sous le feu des troupes terrestres ennemies, tout recrutement est impossible !', 'danger');
					elseif($Reste >3 || $mobile ==4 || ($Reste >0 && $mobile ==5))
					{
                        if($data['Pays'] >0 && (!$Nid || !in_array($Usine1_Flag,$Allies,true) || !in_array($Usine1_Flag_Usine,$Allies,true)))
						    if(!$data['Production'])
                                $Creer_txt=Output::popup('Pas ici', 'Cette unité ne peut être recrutée que sur la base arrière du front '.GetData("Lieu","ID",$Nid,"Nom"), 'danger');
						    else
                                $Creer_txt=Output::popup('Occupé', 'Votre usine est occupée par les troupes ennemies, tout recrutement est impossible !', 'danger');
						else
						{
							if($mode==1)
								$Creer_txt="<form action='index.php?view=ground_em_ia_upgrade' method='post'>
								<input type='hidden' name='Ve' value='".$data['ID']."'>
								<input type='hidden' name='Cr' value='".$Reput."'>
								<input type='hidden' name='Reg' value='".$Reg."'>
								<input type='submit' value='".$Reput." CT' class='btn btn-".$btn_class."' onclick='this.disabled=true;this.form.submit();'></form>";
							elseif($OfficierEMID >0)
								$Creer_txt="<form action='index.php?view=ground_em_ia_create_do' method='post'>
								<input type='hidden' name='Ve' value='".$data['ID']."'>
								<input type='hidden' name='Cr' value='".$Reput."'>
								<input type='hidden' name='Nid' value='".$Nid."'>
								<input type='submit' value='".$Reput." CT' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
						}
					}
					elseif($Prod <50)
						$Creer_txt=Output::popup('Usines '.$Prod.'%', 'Votre nation doit réparer les usines détruites', 'danger');
					else
						$Creer_txt=Output::popup($Reste.' dispo', 'Votre nation doit réparer les modèles détruits', 'danger');
					$table.="<tr><td><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'><br>".$Usine1_Nom."</td>
					<td><form><input type='button' value='Détail' class='btn btn-primary btn-sm' onclick=\"window.open('cible.php?cible=".$data['ID']."','Fiche','width=820,height=840,scrollbars=1')\"></form></td>
					<td>".$Reste_txt."</td>
					<td>".$Creer_txt."</td>
					<td>".$HP."</td>
					<td>".$Arme_Inf."</td>
					<td>".$Arme_Art."</td>
					<td>".$Arme_AT."</td>
					<td>".$Arme_AA."</td>
					<td>".$Portee."</td>
					<td>".$Blindage."</td>
					<td>".$Vitesse."</td>
					<td>".$Taille."</td>
					<td>".$Detection."</td>
					<td>".$Autonomie."</td>
					<td>".$Fiabilite."</td>
					<td>".$Charge."</td></tr>";
				}
				else
				{
                    $table.="<tr><td><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'></td>
					<td><form><input type='button' value='Détail' class='btn btn-primary btn-sm' onclick=\"window.open('cible.php?cible=".$data['ID']."','Fiche','width=820,height=840,scrollbars=1')\"></form></td>
					<td>".$Reste."</td>
					<td class='btn btn-danger'>".$Reput." CT</td>
					<td>".$HP."</td>
					<td>".$Arme_Inf."</td>
					<td>".$Arme_Art."</td>
					<td>".$Arme_AT."</td>
					<td>".$Arme_AA."</td>
					<td>".$Portee."</td>
					<td>".$Blindage."</td>
					<td>".$Vitesse."</td>
					<td>".$Taille."</td>
					<td>".$Detection."</td>
					<td>".$Autonomie."</td>
					<td>".$Fiabilite."</td>
					<td>".$Charge."</td></tr>";
				}
			}
//			mysqli_free_result($result);
//			unset($data);
//		}

        //Output
        $titre = 'Hangar';
        $mes = Output::ShowAdvert('Changer le matériel de cette unité ramènera son expérience à une valeur de 50 et lui attribuera une nouvelle compétence tactique aléatoire de niveau 1', 'info');
        $mes .= '<h2>Matériel disponible</h2>';
        if ($table) {
            $titre_up = "<thead><tr>  
				<th width='10%'>Matériel</th>     
				<th width='1%'>Détail</th> 
				<th width='2%'>Dispo</th>
				<th width='2%'>Créer</th>
				<th width='2%'>Robustesse</th>                                           
				<th width='5%'>Armement</th>  
				<th width='5%'>Soutien</th>  
				<th width='5%'>Anti-tank</th>
				<th width='5%'>DCA</th>
				<th width='2%'>Portee</th>
				<th width='2%'>Blindage</th>                            
				<th width='2%'>Vitesse</th>                            
				<th width='2%'>Taille</th>                            
				<th width='2%'>Détection</th> 
				<th width='2%'>Autonomie</th> 
				<th width='2%'>Fiabilite</th>
				<th width='2%'>Charge</th>
				</tr></thead>";
            $mes .= '<table class="table table-dt table-striped table-responsive">' . $titre_up . $table . '</table>';
        }
        if ($OfficierEMID)
            $menu = Output::linkBtn('index.php?view=ground_em_ia_list', 'Retour');
        else
            $menu = Output::linkBtn('index.php?view=ground_bat', 'Retour');
        include_once './default.php';
	}
	else
		echo 'Tsss!';
}
else
	echo 'Vous devez être connecté!';