<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if ($OfficierEMID > 0) {
    include_once './jfv_include.inc.php';
    include_once './jfv_ground.inc.php';
    include_once './jfv_txt.inc.php';
    include_once './ground/inc/lib.php';
    $Ordre_ok = false;
    $country = $_SESSION['country'];
    $Unit = Insec($_POST['Reg']);
    if (!$Unit) {
        $Unit = $_SESSION['reg'];
        if ($_SESSION['msg'])
            $Alert = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' . $_SESSION['msg'] . '</div>';
        elseif ($_SESSION['msg_red'])
            $Alert = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' . $_SESSION['msg_red'] . '</div>';
        $_SESSION['reg'] = false;
        $_SESSION['msg'] = false;
        $_SESSION['msg_red'] = false;
    }
    $Regiment = Regiment_IA::getById($Unit);
    $Player = Joueur::getById($_SESSION['AccountID']);
    $Admin = $Player->Admin;
    $Officier = Officier_em::getById($OfficierEMID);
    $Pays = $Officier->Pays;
    $Front = $Officier->Front;
    $Armee = $Officier->Armee;
    $Trait = $Officier->Trait;
    if ($Front == 99) {
        $GHQ = GHQ::isPlanif($Pays, $OfficierEMID);
    } else {
        $Pays_front = Pays::getByIdAndByFront($country, $Front);
        if (is_object($Pays_front)) {
            $Commandant = $Pays_front->Commandant;
            $Adjoint_Terre = $Pays_front->Adjoint_Terre;
            $Officier_Mer = $Pays_front->Officier_Mer;
            $Officier_Log = $Pays_front->Officier_Log;
        }
    }
    if ($GHQ || $Admin) {
        $Ordres_Cdt = true;
        $Ordre_ok = true;
        $Ordres_Div = true;
        $GHQ = true;
        if (in_array($country, $Nations_IA)) {
            $Nation_IA = true;
        }
    } elseif ($Commandant > 0 && ($Commandant == $OfficierEMID)) {
        $Ordres_Cdt = true;
        $Ordre_ok = true;
    } elseif ($Adjoint_Terre > 0 && ($Adjoint_Terre == $OfficierEMID)) {
        $Ordres_Adjoint = true;
        $Ordre_ok = true;
    } elseif ($Officier_Mer > 0 && ($Officier_Mer == $OfficierEMID)) {
        $Ordres_Mer = true;
        $Ordre_ok = true;
    } elseif ($Officier_Log > 0 && ($Officier_Log == $OfficierEMID)) {
        $Ordres_Log = true;
        $Ordre_ok = true;
    }
    if ($Armee > 0) {
        $Division = $Regiment->Division;
        if ($Division) {
            $Division_c = Division::getById($Division);
            $Div_Armee = $Division_c->Armee;
            $Div_Base = $Division_c->Base;
        }
        if ($Div_Armee == $Armee) {
            $Ordres_Armee = true;
            $Ordre_ok = true;
        }
    }
    if($Ordre_ok && $Regiment->ID > 0)
    {
        $Premium = $Player->Premium;
        $Faction = Pays::getFaction($country);
        $Date_Campagne = Conf_Update::getCampaignDate();
        if (!$Regiment) {
            $Regiment = Regiment_IA::getById($Unit);
        }
        $Veh = Cible::getById($Regiment->Vehicule_ID);
        $Lieu = Lieu::getById($Regiment->Lieu_ID);
        $Faction_Flag = Pays::getFaction($Lieu->Flag);

        //Get Basic Data
        $today=getdate();
        $Retraite = getRetreat($country, $Front, $Regiment->Division, $Div_Base);
        $Mois=substr($Date_Campagne,5,2);
        $Lands=GetAllies($Date_Campagne);
        if(IsAxe($country))
            $Allies=$Lands[1];
        else
            $Allies=$Lands[0];
        //Get Flags
        $Lieu->Flags = getFlags($Regiment->ID);
        $Combat_flag = $Lieu->Flags[0];
        $Move_flag = $Lieu->Flags[1];
        if($Regiment->Position ==12 || $Regiment->Atk ==1 || $Combat_flag)$Pas_libre=true;
        //Calc Stats & Bonuses
        if($Regiment->Matos ==25)$Veh->Amphi=true;
        $Range=$Veh->Portee;
        $Ravit=$Regiment->Ravit;
        $Autonomie=$Regiment->Autonomie;
        $Range+=($Regiment->Experience*2);
        $Max_Veh=GetMaxVeh($Veh->Type,$Veh->mobile,$Veh->Flak,500000);
        $Stock=floor($Veh->Stock);
        if($Veh->Categorie !=20 && $Veh->Categorie !=21 && $Veh->Categorie !=22 && $Veh->Categorie !=23 && $Veh->Categorie !=24 && $Veh->Categorie !=17 || ($Veh->mobile ==5 && !$Regiment->Vehicule_Nbr))$Autonomie=1;
        //Matos & Skills
        $matos_modal = getMatosAndSkills($Unit, $Veh, $Date_Campagne);

        //Process
        if($OfficierEMID ==$Commandant || $GHQ || $Admin || $Ordres_Mer)
        {
            include_once 'view/menu_cat_list.php';
        }
        if($Regiment->Vehicule_Nbr >0 && (!$GHQ || $Admin || $Nation_IA)) //Demande mission & Situation unités de la zone
        {
            if(!$Regiment->Mission_Lieu_D)
                $Mission_Lieu_D='<i>Aucune</i>';
            else
                $Mission_Lieu_D=GetData("Lieu","ID",$Regiment->Mission_Lieu_D,"Nom");
            if(!$Regiment->Mission_Type_D)
                $Mission_Type_D_txt='<i>Indéfini</i>';
            else
                $Mission_Type_D_txt=GetMissionType($Regiment->Mission_Type_D);
            if($Faction >0)
            {
                $dem_sup=false;
                $con=dbconnecti();
                //$Enis_lieu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Regiment->Lieu_ID' AND r.Visible=1 AND r.Vehicule_Nbr >0"),0);
                $Enis_IA_lieu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Regiment->Lieu_ID' AND r.Visible=1 AND r.Vehicule_Nbr >0"),0);
                $result_allies=mysqli_query($con,"SELECT r.ID,r.Pays,r.Vehicule_ID,r.Position,r.Division,r.Bataillon,r.Transit_Veh,r.Move,r.NoEM,r.Skill,r.Matos,p.Faction,c.mobile,c.Categorie,c.Vitesse,c.Arme_AT,c.Arme_Art,c.Portee,
                r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,
                r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m
                FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND (p.Faction='$Faction' OR r.Visible=1) AND r.Lieu_ID='$Regiment->Lieu_ID' AND r.Placement='$Regiment->Placement' AND r.Vehicule_Nbr >0");
                if($result_allies)
                {
                    while($data=mysqli_fetch_array($result_allies,MYSQLI_ASSOC))
                    {
                        $Div_units=false;
                        $Ordres_Armee_Ici=false;
                        $skill_icons=false;
                        if($data['Faction'] ==$Faction)
                        {
                            $Def_txt='Aucun';
                            $Init_txt='Aucun';
                            if($data['Position'] ==1)
                            {
                                if(($data['mobile'] ==1 or $data['mobile'] ==2 or $data['mobile'] ==6 or $data['mobile'] ==7) and $data['Vitesse'] >10)
                                    $Def_txt='Contre-attaque';
                                if($data['Categorie'] ==15)
                                    $Def_txt='Anti-aérien';
                            }
                            elseif($data['Position'] ==10)
                            {
                                if($data['Categorie'] ==5 or $data['Categorie'] ==6 or $data['Categorie'] ==9)
                                    $Def_txt='En ligne';
                                elseif($data['mobile'] ==3 and $data['Arme_AT'] >0)
                                    $Def_txt='En ligne';
                            }
                            elseif($data['Position'] ==3)
                            {
                                if($data['Arme_AT'] >0)
                                    $Def_txt='Embuscade';
                                elseif($data['Arme_Art'] >0)
                                    $Def_txt='Contre-batterie';
                            }
                            elseif($data['Position'] ==5)
                            {
                                if($data['Arme_Art'] >0)
                                    $Def_txt='Contre-batterie';
                                if($data['Categorie'] ==15)
                                    $Def_txt='Anti-aérien';
                            }
                            elseif($data['Position'] ==14)
                                $Def_txt='Sentinelle';
                            elseif($data['Position'] ==21)
                                $Def_txt='Escorte';
                            elseif($data['Position'] ==23)
                                $Def_txt='Contre-batterie';
                            elseif($data['Position'] ==24)
                                $Def_txt='ASM';
                            if($data['Division'] and $data['Pays']==$country)
                            {
                                $Armee_unit=mysqli_result(mysqli_query($con,"SELECT Armee FROM Division WHERE ID=".$data['Division']),0);
                                $Div_units="<img src='images/div/div".$data['Division'].".png'>";
                                if($Armee_unit ==$Armee)$Ordres_Armee_Ici=true;
                                if($Ordres_Cdt or $Ordres_Armee_Ici)
                                    $Div_units="<a class='btn btn-default btn-xs' href='index.php?view=ground_em_div&id=".$data['Division']."'>".$Div_units."</a>";
                            }
                            if(($Ordres_Cdt or $Ordres_Armee_Ici) and $data['Pays']==$country)
                            {
                                if(!$data['Officier_ID'])
                                {
                                    if($data['Move'])
                                        $Led="<span class='i-flex led_red'></span>";
                                    else
                                        $Led="<span class='i-flex led_green'></span>";
                                }
                                else
                                    $Led=false;
                                if($today['mday'] >$data['Jour']+1)
                                    $Combat_flag=false;
                                elseif($today['mon'] >$data['Mois'])
                                    $Combat_flag=false;
                                elseif($today['year'] >$data['Year_a'])
                                    $Combat_flag=false;
                                elseif($today['mday']!=$data['Jour'] and $today['hours']>=$data['Heure'])
                                    $Combat_flag=false;
                                else
                                    $Combat_flag=true;
                                if($today['mday'] >$data['Jour_m']+1)
                                    $Move_flag=false;
                                elseif($today['mon'] >$data['Mois_m'])
                                    $Move_flag=false;
                                elseif($today['year'] >$data['Year_m'])
                                    $Combat_flag=false;
                                elseif($today['mday']!=$data['Jour_m'] and $today['hours']>=$data['Heure_m'])
                                    $Move_flag=false;
                                else
                                    $Move_flag=true;
                                if($data['Position'] ==12)
                                    $Cie_ID="<span class='label label-danger'>En Vol</span>";
                                elseif($data['Atk'] ==1 or $Combat_flag)
                                    $Cie_ID="<span class='text-danger'>En Combat<br>jusque ".$data['Heure']."</span>";
                                elseif($data['mobile'] !=5 and ($data['Move'] ==1 or $Move_flag))
                                    $Cie_ID="<span class='text-danger'>Mouvement<br>jusque ".$data['Heure_m']."</span>";
                                elseif($data['NoEM'])
                                    $Cie_ID="<span class='btn btn-danger'>GHQ</span>";
                                elseif(!$OfficierEMID and $data['Bataillon'] !=$OfficierID)
                                    $Cie_ID="<span class='btn btn-default'>".$data['ID']."</span>";
                                else
                                {
                                    if($data['ID']!=$Unit)
                                        $Cie_ID="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'>".$Led."
                                        <input type='Submit' value='".$data['ID']."' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                                    else
                                        $Cie_ID="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'>".$Led."
                                        <input type='Submit' value='".$data['ID']."' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>";
                                    if($data['Skill'])
                                        $skill_icons="<a href='#' class='popup'><img src='images/skills/skillo".$data['Skill'].".png' style='max-width:20%;'><span>".$Skills_r[$data['Skill']]."</span></a>";
                                    else
                                        $skill_icons='';
                                    if($data['Matos'])
                                        $skill_icons.="<a href='#' class='popup'><img src='images/skills/skille".$data['Matos'].".png' style='max-width:20%;'><span>".$Reg_matos[$data['Matos']]."</span></a>";
                                }
                            }
                            else
                                $Cie_ID=$data['ID'].'e ';
                            $units_allies.="<tr><td>".$Cie_ID."</td><td>".$Div_units."</td>
                            <td><img src='".$data['Pays']."20.gif'></td>
                            <td>".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Regiment->Front).$skill_icons."</td>
                            <td>".GetPosGr($data['Position'])."</td>
                            <td>".$Def_txt."</td><td>".$Init_txt."</td></tr>";
                        }
                        else
                        {
                            if($Range >=$data['Portee'] and $Veh->Type !=1 and $Veh->Type !=4 and $Veh->Type !=12 and $Veh->Type !=13 and $Veh->Categorie !=6)
                                $Init_txt="<a href='#' class='popup'><div class='i-flex led_green'></div><span>A portée d'une attaque</span></a>";
                            elseif($Veh->Categorie ==6 or $Veh->Type ==1 or $Veh->Type ==4 or $Veh->Type ==12 or $Veh->Type ==13)
                                $Init_txt="<a href='#' class='popup'><div class='i-flex led_red'></div><span>Votre unité ne peut pas effectuer d'attaque sur cette cible</span></a>";
                            else
                                $Init_txt="<a href='#' class='popup'><div class='i-flex led_red'></div><span>Actuellement hors de portée</span></a>";
                            if($data['Position'] ==8)
                                $Appui='Sous le feu';
                            else
                                $Appui='Inconnu';
                            if($data['Transit_Veh'] ==5000)$data['Vehicule_ID']=5000;
                            $units_allies.="<tr><td>Inconnu</td><td>Inconnu</td>
                            <td><img src='".$data['Pays']."20.gif'></td>
                            <td>".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Regiment->Front)."</td>
                            <td>Inconnu</td><td>Inconnu</td><td>".$Init_txt."</td></tr>";
                            $HasHostiles=true;
                        }
                    }
                    mysqli_free_result($result_allies);
                    if($units_allies)
                    {
                        if($Lieu->Meteo <-69)
                            $Meteo_help_txt='Portée de bombardement réduite de moitié<br>Portée d\'attaque réduite de 75%<br>Déplacement naval impossible';
                        elseif($Lieu->Meteo <-9)
                            $Meteo_help_txt='Portée d\'attaque réduite de moitié';
                        else
                            $Meteo_help_txt='Météo clémente pour une attaque';
                        $units_print="<div class='panel panel-war'>
                        <div class='panel-heading'>
                            <div class='row'>
                                <div class='col-md-4'><a href='#' class='popup'><form action='index.php?view=em_city_ground' method='post'><input type='hidden' name='id' value='".$Lieu->ID."'><input type='submit' value='".$Lieu->Nom."' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form><span>Visualiser la situation de ".$Lieu->Nom."</span></a></div>
                                <div class='col-md-4 text-center'>Situation ".GetPlace($Regiment->Placement)."</div>
                                <div class='col-md-4 text-center'><a href='#' class='popup'><img src='images/meteo".$Lieu->Meteo.".gif'><span>".$Meteo_help_txt."</span></a></div>
                            </div>
                        </div>";
                        $Couv_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Cible='$Regiment->Lieu_ID' AND j.Couverture='$Regiment->Lieu_ID' AND j.Avion >0 AND p.Faction='$Faction' AND j.Actif='1' AND (j.Alt BETWEEN 100 AND 3000)"),0);
                        if($Couv_Nbr)
                            $units_air_cover='<div class="alert alert-warning" style="max-width: 600px;"><img src="images/ia_combat.png"> <b>'.$Couv_Nbr.'</b> avions de chasse couvrent les unités situées à '.$Lieu->Nom.'</div>';
                        else
                            $units_air_cover='<div class="alert alert-danger">Aucun avion de chasse ne couvre les unités situées à '.$Lieu->Nom.'. Demandez un appui aérien!</div>';
                        $units_print.="<div class='panel-body' style='overflow:auto;'>".$units_air_cover."<table class='table table-condensed table-striped'><thead><tr><th>Unité</th><th>Division</th><th>Pays</th><th>Troupes</th><th>Position</th><th>Défense</th><th>Attaque</th></tr></thead>".$units_allies."</table></div></div>";
                    }
                }
                $Enis_lieu+=$Enis_IA_lieu;
                if($Regiment->Vehicule_ID >=5000 and ($Lieu->Zone ==6 or $Lieu->Plage or $Lieu->Port_Ori))
                {
                    if($Enis_lieu)
                        $dem_sup.="<option value='11'>Attaque Navale (Demande un mitraillage des unités navales ennemies détectées)</option><option value='12'>Bombardement naval (Demande un bombardement des unités navales ennemies détectées)</option><option value='13'>Torpillage (Demande un torpillage des unités navales ennemies détectées)</option>";
                    $dem_sup.="<option value='7'>Patrouille (Demande à la chasse de protéger votre unité des attaques aériennes)</option><option value='29'>Patrouille ASM (Demande une intervention anti-sous-marine)</option><option value='5'>Reco tactique (Demande une identification des éventuelles unités terrestres ennemies)</option>";
                }
                elseif($Regiment->Vehicule_ID <5000)
                {
                    if($Enis_lieu and $Lieu->Zone !=6)
                        $dem_sup.="<option value='1'>Appui rapproché (Demande un mitraillage des unités terrestres ennemies détectées)</option><option value='2'>Bombardement tactique (Demande un bombardement des unités terrestres ennemies détectées)</option>";
                    $dem_sup.="<option value='7'>Patrouille (Demande à la chasse de protéger votre unité des attaques aériennes)</option><option value='5'>Reco tactique (Demande une identification des éventuelles unités terrestres ennemies)</option>";
                    if($Lieu->Zone !=6 and $Faction_Flag !=$Faction)
                        $dem_sup.="<option value='15'>Reco stratégique (Demande une identification des infrastructures)</option>";
                }
                if($Regiment->Mission_Type_D)
                {
                    $output_dem.="<div class='panel panel-war'><div class='panel-heading'>Demande d'appui aérien en cours</div><div class='panel-body'>
                            <form action='index.php?view=ground_em_ia_go' method='post'>
                            <input type='hidden' name='reset' value='3'>
                            <input type='hidden' name='Unit' value='".$Unit."'>
                            <table class='table'>
                            <thead><tr><th>Mission</th><th>Lieu</th><th></th></tr></thead>
                            <tr><td>".$Mission_Type_D_txt."</td><td>".$Mission_Lieu_D."</td>
                            <td><input type='submit' value='Annuler' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></td></tr></table></form></div></div>";
                }
                elseif($Regiment->Position !=12 and $Regiment->Position !=13){
                    $output_dem.="<div class='panel panel-war'><div class='panel-heading'>Demande d'appui aérien</div><div class='panel-body'>
                    <form action='index.php?view=ground_em_ia_go' method='post'>
                    <input type='hidden' name='Unit' value='".$Unit."'>
                    <input type='hidden' name='Cible_dem' value='".$Lieu->ID."'>
                    <input type='hidden' name='reset' value='5'>
                    <select name='Type_dem' class='form-control' style='max-width:200px; display:inline;'>".$dem_sup."</select>
                    <input type='submit' value='Valider' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></div></div>";
                }
            }
        }
        if($Regiment->Vehicule_ID >5000 || $Regiment->Transit_Veh ==5000) //Navire
        {
            $ground_em_ia_naval = true;
            include_once 'ground_em_ia_naval.php';
        }
        elseif($Regiment->Vehicule_ID ==424) //Train
        {
            $ground_em_ia_train = true;
            include_once 'ground_em_ia_train.php';
        }
        elseif($Ordres_Cdt || $Ordres_Adjoint || $Ordres_Div || $Ordres_Armee || $Ordres_Bat || ($Veh->Type ==TYPE_TRUCK && $Ordres_Log)) // Terrestre
        {
            $Autos=GetAuto($Regiment->Front,$Lieu->Latitude,$Lieu->Longitude);
            $Autonomie_Max=$Autos[0];
            $Autonomie_Mini=$Autos[1];
            $Dist_train_max=$Autos[2];
            if($Regiment->Placement ==PLACE_PLAGE)$Faction_Plage=GetData("Pays","ID",$Lieu->Flag_Plage,"Faction");
            if($Regiment->Placement ==PLACE_PLAGE && $Faction_Plage !=$Faction)
                $Placements=Output::ShowAdvert('Cette unité ne peut quitter la plage tant que vos troupes ne contrôlent pas la zone','danger');
            elseif($Regiment->Atk && $Regiment->mobile ==MOBILE_FOOT)
                $Placements=Output::ShowAdvert('Vos troupes ne peuvent se déplacer directement après une attaque','danger');
            elseif($Regiment->Position !=12 && $Regiment->Position !=13)
            {
                if(!$Pas_libre)
                {
                    if(($Lieu->Pont_Ori || $Lieu->Fleuve) && !$Lieu->Pont && !$Veh->Amphi)
                    {
                        if($Lieu->Flag_Pont)$Faction_Pont=GetData("Pays","ID",$Lieu->Flag_Pont,"Faction");
                        if($Faction_Pont !=$Faction)
                            $Pont_detruit=true;
                        else
                            $Pont_strat=true;
                        if($Pont_detruit and !$Pont_strat)
                            $Pont_block=true;
                    }
                    $Placements="<form action='ground_em_ia_go.php' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='zone' class='form-control' style='max-width:200px; display:inline;'>
                                <option value='0'>Ne rien changer</option>";
                    if(!$Pont_block)
                        $Placements.="<option value='10'>Caserne</option>";
                    if($Lieu->BaseAerienne >0 and !$Pont_block)
                        $Placements.="<option value='1'>Aérodrome</option>";
                    if($Lieu->NoeudF_Ori >0 and !$Pont_block)
                        $Placements.="<option value='3'>Gare</option>";
                    if($Lieu->Plage >0)
                        $Placements.="<option value='11'>Plage</option>";
                    if($Lieu->Pont_Ori >0)
                        $Placements.="<option value='5'>Pont</option>";
                    elseif($Lieu->Fleuve)
                        $Placements.="<option value='5'>Fleuve</option>";
                    if($Lieu->Port_Ori >0 and !$Pont_block)
                        $Placements.="<option value='4'>Port</option>";
                    if($Lieu->Radar_Ori >0 and !$Pont_block)
                        $Placements.="<option value='7'>Radar</option>";
                    if($Lieu->NoeudR >0 and !$Pont_block)
                        $Placements.="<option value='2'>Route</option>";
                    if($Lieu->Industrie >0 and !$Pont_block)
                        $Placements.="<option value='6'>Usine</option>";
                    $Placements.='</select><input class="btn btn-sm btn-warning" type="submit" value="Changer" onclick="this.disabled=true;this.form.submit();"></form>';
                    if(!$Faction_Gare)$Faction_Gare=GetData("Pays","ID",$Lieu->Flag_Gare,"Faction");
                    if($Regiment->Placement ==3 and $Faction_Flag ==$Faction and $Faction_Gare ==$Faction)
                        $Placement_help='Les déplacements depuis une gare contrôlée par votre faction vers une autre gare contrôlée par votre faction sont de <b>'.$Dist_train_max.'km</b> sur ce front<br>Le niveau d\'infrastructure de la gare de départ et de la gare d\'arrivée doivent être supérieurs à 10% pour bénéficier de ce type de déplacement. Actuellement la gare de départ a un niveau de <b>'.$Lieu->NoeudF.'%</b>';
                }
                else
                    $Placements='<span class="text-danger">En combat</span>';
            }
            else
                $Placements='<span class="text-warning">En transit</span>';
            /*Lieux*/
            if(!$Regiment->Move && $Regiment->Position !=12 && $Regiment->Position !=13)
            {
                $Rasputitsa=false;
                $Merzlota=false;
                $Mousson=false;
                $Skill_auto_bonus=false;
                $Enis_combi=0;
                if(($Lieu->Pays ==URSS or $Lieu->Pays ==FIN) and ($Mois ==11 or $Mois ==3)) //Rasputitsa
                    $Rasputitsa=true;
                elseif($Regiment->Front ==BEL)
                {
                    if(($Lieu->Longitude <=90 and ($Mois ==7 or $Mois ==8)) or ($Lieu->Longitude >90 and ($Mois ==8 or $Mois ==9)))
                        $Mousson=true;
                }
                if(($Lieu->Pays ==URSS or $Lieu->Pays ==FIN) and ($Mois ==12 or $Mois ==1 or $Mois ==2)) //Merzlota
                    $Merzlota=true;
                if($Lieu->NoeudR >0 and $Regiment->Placement ==PLACE_ROUTE and !$Rasputitsa and !$Mousson and !$Enis_combi)
                    $Zone_calc=0;
                else
                    $Zone_calc=$Lieu->Zone;
                $Autonomie_Min=Get_LandSpeed($Veh->Fuel,$Veh->mobile,$Zone_calc,0,$Veh->Type,0,0,$Veh->Amphi,$Regiment->Front);
                if($Regiment->Skill ==44 or $Regiment->Skill ==131)
                    $Ravit=2;
                elseif($Veh->mobile ==MOBILE_FOOT)
                {
                    if($Regiment->Skill ==23)$Autonomie_Min*=1.1;
                    elseif($Regiment->Skill ==114)$Autonomie_Min*=1.2;
                    elseif($Regiment->Skill ==115)$Autonomie_Min*=1.3;
                    elseif($Regiment->Skill ==116)$Autonomie_Min*=1.4;
                    $Skill_auto_bonus=true;
                }
                if($Regiment->Matos ==14)$Autonomie_Min*=1.5;
                elseif($Regiment->Matos ==15)$Autonomie_Min*=1.1;
                elseif($Regiment->Matos ==30)$Autonomie_Min*=1.5;
                elseif($Regiment->Matos ==28)$Autonomie_Min*=2;
                elseif($Regiment->Matos ==24 and $Lieu->Zone ==8)$Autonomie_Min*=1.5;
                if(($Veh->Type ==97 or $Veh->Mountain) and ($Zone_calc ==1 or $Zone_calc ==4 or $Zone_calc ==5)) //Montagnards
                    $Skill_auto_bonus=true;
                if(($Regiment->mobile ==1 or $Regiment->mobile ==2 or $Regiment->mobile ==6 or $Regiment->mobile ==7) and $Zone_calc ==0) //Mobile
                    $Skill_auto_bonus=true;
                $Lat_min=$Lieu->Latitude-6;
                $Lat_max=$Lieu->Latitude+6;
                $Long_min=$Lieu->Longitude-7;
                $Long_max=$Lieu->Longitude+7;
                if($Lieu->Fortification >=100 and $Lieu->Garnison >50 and $Regiment->Vehicule_Nbr >0 and $Faction !=$Faction_Flag and $Regiment->Position !=6)
                {
                    $output_dest="<div class='alert alert-danger'>Les forts ennemis contrôlant la région empêchent tout déplacement de l'unité!</div>";
                    $Autonomie_Min=0;
                    //Fuite
                    if(!$Regiment->Atk)
                    {
                        $output_dest.="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='reset' value='9'><input type='hidden' name='Max' value='".$Regiment->Vehicule_Nbr."'>
                        <a href='#' class='popup'><input type='submit' value='Fuir' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
                        <span>Cette action permettra à l'unité d'agir, mais réduira ses effectifs à 1</span></a></form>";
                    }
                }
                elseif($Autonomie_Min <$Autonomie_Mini)$Autonomie_Min=$Autonomie_Mini;
                $Auto_Log=GetAutoLog($Regiment->Front,$Lieu->Latitude,$Lieu->Longitude);
                $con=dbconnecti();
                //$Enis2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Regiment->Lieu_ID' AND r.Placement='$Regiment->Placement' AND r.Vehicule_Nbr >0"),0);
                $Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction<>'$Faction' AND i.Lieu_ID='$Lieu->ID' AND i.Placement='$Regiment->Placement' AND i.Vehicule_Nbr >0"),0);
                $Enis_combi=$Enis+$Enis2;
                if($G_Treve or ($G_Treve_Med and $Regiment->Front ==FRONT_MED) or ($G_Treve_Est_Pac and ($Regiment->Front ==FRONT_EST or $Regiment->Front ==FRONT_NORD or $Regiment->Front ==FRONT_PAC)))$query_treve=" AND Flag IN (".$Allies.")";
                if($Regiment->Front ==FRONT_MED)
                {
                    if($Lieu->ID ==343 or $Lieu->ID ==344 or $Lieu->ID ==445 or $Lieu->ID ==529 or $Lieu->ID ==2863 or $Lieu->ID ==2864 or $Lieu->ID ==2882 or $Lieu->ID ==2884 or $Lieu->ID ==2888 or $Lieu->ID ==2889 or $Lieu->ID ==2890 or $Lieu->ID ==2891 or $Lieu->ID ==2893 or $Lieu->ID ==2925) //Iles isolées
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID='$Lieu->ID'";
                    elseif($Lieu->ID ==903 or $Lieu->ID ==910 or $Lieu->ID ==1090 or $Lieu->ID ==1288 or $Lieu->ID ==1653) //Crête
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (903,910,1090,1288,1653) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->ID ==435 or $Lieu->ID ==450 or $Lieu->ID ==465 or $Lieu->ID ==1644 or $Lieu->ID ==1647 or $Lieu->ID ==2127 or $Lieu->ID ==2953 or $Lieu->ID ==2954 or $Lieu->ID ==2955 or $Lieu->ID ==2956) //Sardaigne
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (435,450,465,1644,1647,2127,2953,2954,2955,2956) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->ID ==2306 or $Lieu->ID ==2307 or $Lieu->ID ==2308 or $Lieu->ID ==2309 or $Lieu->ID ==2310 or $Lieu->ID ==2957 or $Lieu->ID ==2958) //Corse
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (2306,2307,2308,2309,2310,2957,2958) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->Latitude >36.7 and $Lieu->Latitude <38.2 and $Lieu->Longitude >12.5 and $Lieu->Longitude <=15.55) //Sicile
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Latitude BETWEEN 36.7 AND 38.2) AND (Longitude BETWEEN 12.5 AND 15.56) AND ID<>'$Lieu->ID'".$query_treve." ORDER BY Nom ASC";
                    elseif($Lieu->Latitude >36.6 and $Lieu->Longitude >19 and $Lieu->Longitude <26) //Grèce
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude >36.6 AND Longitude >19 AND Longitude <26 AND Zone<>6 AND Pays NOT IN (2,4,6)".$query_treve." AND ID NOT IN ('$Lieu->ID',2863,2864,2888,2889,2890,2891,2893) ORDER BY Nom ASC";
                    elseif($Lieu->Pays ==6 and $Lieu->Latitude >38.2 and $Lieu->Longitude <19) //Italie
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Latitude BETWEEN 38.2 AND 45.5) AND (Longitude BETWEEN -2 AND 50) AND Zone<>6 AND Pays NOT IN (10,24)".$query_treve." AND ID NOT IN ('$Lieu->ID',435,450,465,1644,1647,2127,2306,2307,2308,2309,2310) ORDER BY Nom ASC";
                    elseif($Lieu->Longitude >34 and $Lieu->Longitude <45) //Moyen-Orient
                    {
                        $Autonomie_Max=100;
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude <37.3 AND Longitude >34 AND Longitude <50 AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',529) ORDER BY Nom ASC";
                    }
                    elseif($Lieu->Latitude <36.7 or ($Lieu->Longitude <12 and $Lieu->Latitude <37.3 and $Lieu->Pays !=6)) //AFN
                    {
                        $Autonomie_Max=100;
                        if($Lieu->Latitude <33 and $Lieu->Longitude <34 and $Lieu->Longitude >11.22)
                        {
                            if($Lieu->Longitude <25.16 and $Lieu->Latitude >31.12)
                                $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND 25.16) AND (Latitude BETWEEN '$Lat_min' AND 33.15) AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
                            elseif($Lieu->Longitude >25.16 and $Lieu->Latitude >31.12)
                                $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 25.16 AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND 33) AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
                            else
                                $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND 33.15) AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',343,445,529,678,903,910,1090,1288,1653)";
                        }
                        else
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude <37.3 AND Longitude >-8 AND Longitude <50 AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
                    }
                    else
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC LIMIT 100";
                }
                elseif($Regiment->Front ==FRONT_EST or $Regiment->Front ==FRONT_NORD or $Regiment->Front ==FRONT_ARCTIC)
                {
                    if($Lieu->Pays ==FIN)
                    {
                        if($Lat_min <60)$Lat_min=60;
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',1252) ORDER BY Nom ASC";
                    }
                    elseif($Lieu->Latitude <46 and $Lieu->Latitude>44.40 and $Lieu->Longitude >33 and $Lieu->Longitude <36.5) //Crimée
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 33 AND 36.5) AND (Latitude BETWEEN 44.4 AND 46.5) AND Zone<>6".$query_treve." AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->Latitude <47 and $Lieu->Latitude>41 and $Lieu->Longitude >37 and $Lieu->Longitude <48) //Caucase
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 37 AND 50) AND (Latitude BETWEEN 41 AND 48) AND Zone<>6".$query_treve." AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->Pays ==8)
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',1252) ORDER BY Nom ASC";
                    else
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',1252) ORDER BY Nom ASC";
                }
                elseif($Regiment->Front ==FRONT_PAC)
                {
                    if($Lieu->ID ==1610 or $Lieu->ID ==1618 or $Lieu->ID ==1637 or $Lieu->ID ==1869 or $Lieu->ID ==1894) //Ceylan
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1610,1618,1637,1869,1894) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->ID ==1369 or $Lieu->ID ==1722 or $Lieu->ID ==1723 or $Lieu->ID ==1859 or $Lieu->ID ==1882 or $Lieu->ID ==1883 or $Lieu->ID ==1885 or $Lieu->ID ==1886 or $Lieu->ID ==1890) //Malaisie
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1369,1722,1723,1859,1882,1883,1885,1886,1890) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->Longitude >90 and $Lieu->Longitude <110 and $Lieu->Latitude >1.20 and $Lieu !=1870 and $Lieu !=1903) //Continent
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 90 AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',1754,1809,1870,1900) ORDER BY Nom ASC";
                    elseif($Lieu->Longitude <90 and $Lieu->Latitude >9) //Inde
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND 90) AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu->ID',1754,1809,1870,1900) ORDER BY Nom ASC";
                    elseif($Lieu->ID ==1368 or $Lieu->ID ==1556 or $Lieu->ID ==1582 or $Lieu->ID ==1776 or $Lieu->ID ==1803 or $Lieu->ID ==1805 or $Lieu->ID ==1811 or $Lieu->ID ==1857 or $Lieu->ID ==2379 or $Lieu->ID ==2380 or $Lieu->ID ==2381) //Japon
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1368,1556,1582,1776,1803,1805,1811,1857,2379,2380,2381) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->ID ==1583 or $Lieu->ID ==1800 or $Lieu->ID ==1801 or $Lieu->ID ==1804) //Formose
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1583,1800,1801,1804) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->ID ==1569 or $Lieu->ID ==1570 or $Lieu->ID ==1571 or $Lieu->ID ==1764 or $Lieu->ID ==1881 or $Lieu->ID ==1888 or $Lieu->ID ==1889 or $Lieu->ID ==2353 or $Lieu->ID ==2354 or $Lieu->ID ==2355 or $Lieu->ID ==2356 or $Lieu->ID ==2357) //Philippines
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1569,1570,1571,1764,1881,1888,1889,2353,2354,2355,2356,2357) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->ID ==1370 or $Lieu->ID ==1574 or $Lieu->ID ==1575 or $Lieu->ID ==1576 or $Lieu->ID ==1613 or $Lieu->ID ==1892 or $Lieu->ID ==1895 or $Lieu->ID ==2358) //Java
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1370,1574,1575,1576,1613,1892,1895,2358) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->ID ==1365 or $Lieu->ID ==1809 or $Lieu->ID ==1873 or $Lieu->ID ==1887) //Sumatra
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1365,1809,1873,1887) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->ID ==1573 or $Lieu->ID ==1763 or $Lieu->ID ==1865 or $Lieu->ID ==1866 or $Lieu->ID ==1972 or $Lieu->ID ==2163 or $Lieu->ID ==2214) //Australie
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1573,1763,1865,1866,1972,2163,2214) AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    else
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID='$Lieu->ID'";
                }
                else
                {
                    if($Lieu->Pays ==1 or $Lieu->Pays ==3 or $Lieu->Pays ==4 or $Lieu->Pays ==5 or $Lieu->Pays ==6 or $Lieu->Pays ==36)
                    {
                        if($Long_max >14)$Long_max=14;
                        if($Lat_min <41)$Lat_min=41;
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 AND Pays IN(1,3,4,5,6,36) AND ID NOT IN ('$Lieu->ID',704,896) ORDER BY Nom ASC";
                    }
                    elseif($Lieu->Pays ==2)
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude >49 AND Longitude <14 AND Zone<>6 AND Pays=2 AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->Pays==7)
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude <-52 OR Longitude >235) AND Zone<>6 AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                    elseif($Lieu->Pays==35)
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude >58 AND Longitude >4.5 AND Zone<>6 AND ID<>'$Lieu->ID' ORDER BY Nom ASC";
                }
                if(!$Pont_block and ($Skill_auto_bonus or $Regiment->Matos ==14 or $Regiment->Matos ==15 or $Regiment->Matos ==28 or $Regiment->Matos ==30))
                    $Autonomie_Max*=1.2;
                $result=mysqli_query($con,$query) or (mail(EMAIL_LOG,'ADA DEBUG : EMPTY QUERY', 'ground_em_ia_2254 : '.$query.' '.$Regiment->Front.' '.$Lieu->Pays.' '.$Lieu->ID.' '.$Long_min.' '.$Long_max.' '.$Lat_min.' '.$Lat_max));
                $resultdepot=mysqli_query($con,"SELECT DISTINCT l.ID,l.Longitude,l.Latitude,l.Stock_Essence_87,l.Stock_Essence_100,l.Stock_Essence_1,l.Stock_Munitions_13,l.Stock_Munitions_20,l.Stock_Munitions_30,l.Stock_Munitions_40,l.Stock_Munitions_50,l.Stock_Munitions_60,l.Stock_Munitions_75,l.Stock_Munitions_90,l.Stock_Munitions_105,l.Stock_Munitions_125,l.Stock_Munitions_150,l.Stock_Munitions_200,l.Stock_Munitions_300,l.Stock_Munitions_360
                FROM Lieu as l,Pays as p WHERE l.ValeurStrat >3 AND (l.NoeudF_Ori=100 OR l.Port_Ori=100) AND l.Flag=p.Pays_ID AND p.Faction='$Faction' AND
                (l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max')
                UNION SELECT DISTINCT l.ID,l.Longitude,l.Latitude,d.Stock_Essence_87,d.Stock_Essence_100,d.Stock_Essence_1,l.Stock_Munitions_13,l.Stock_Munitions_20,l.Stock_Munitions_30,l.Stock_Munitions_40,l.Stock_Munitions_50,l.Stock_Munitions_60,l.Stock_Munitions_75,l.Stock_Munitions_90,l.Stock_Munitions_105,l.Stock_Munitions_125,l.Stock_Munitions_150,l.Stock_Munitions_200,l.Stock_Munitions_300,l.Stock_Munitions_360
                FROM Depots as d,Regiment_IA as r,Lieu as l,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu->ID' AND l.ID=r.Lieu_ID AND r.Placement=8 AND r.Vehicule_ID=5392 AND r.Vehicule_Nbr >0");
                if($resultdepot)
                {
                    while($datad=mysqli_fetch_array($resultdepot,MYSQLI_ASSOC))
                    {
                        $Distance_depot=GetDistance(0,0,$Lieu->Longitude,$Lieu->Latitude,$datad['Longitude'],$datad['Latitude']);
                        if($Distance_depot[0] <=$Auto_Log)
                        {
                            $depot_id = $datad['ID'];
                            $Depots_region[]=$datad['ID'];
                            $Stock_87[$depot_id]=$datad['Stock_Essence_87'];
                            $Stock_100[$depot_id]=$datad['Stock_Essence_100'];
                            $Stock_1[$depot_id]=$datad['Stock_Essence_1'];
                            $Stock_13[$depot_id]=$datad['Stock_Munitions_13'];
                            $Stock_20[$depot_id]=$datad['Stock_Munitions_20'];
                            $Stock_30[$depot_id]=$datad['Stock_Munitions_30'];
                            $Stock_40[$depot_id]=$datad['Stock_Munitions_40'];
                            $Stock_50[$depot_id]=$datad['Stock_Munitions_50'];
                            $Stock_60[$depot_id]=$datad['Stock_Munitions_60'];
                            $Stock_75[$depot_id]=$datad['Stock_Munitions_75'];
                            $Stock_90[$depot_id]=$datad['Stock_Munitions_90'];
                            $Stock_105[$depot_id]=$datad['Stock_Munitions_105'];
                            $Stock_125[$depot_id]=$datad['Stock_Munitions_125'];
                            $Stock_150[$depot_id]=$datad['Stock_Munitions_150'];
                        }
                    }
                    mysqli_free_result($resultdepot);
                }
                $Stock_87_array=Array_max($Stock_87, true);
                $Stock_87_max=$Stock_87_array[0];
                $Stock_87_city=$Stock_87_array[1];
                $Stock_100_array=Array_max($Stock_100, true);
                $Stock_100_max=$Stock_100_array[0];
                $Stock_100_city=$Stock_100_array[1];
                $Stock_1_array=Array_max($Stock_1, true);
                $Stock_1_max=$Stock_1_array[0];
                $Stock_1_city=$Stock_1_array[1];
                $Stock_13_array=Array_max($Stock_13, true);
                $Stock_13_max=$Stock_13_array[0];
                $Stock_13_city=$Stock_13_array[1];
                $Stock_20_array=Array_max($Stock_20, true);
                $Stock_20_max=$Stock_20_array[0];
                $Stock_20_city=$Stock_20_array[1];
                $Stock_30_array=Array_max($Stock_30, true);
                $Stock_30_max=$Stock_30_array[0];
                $Stock_30_city=$Stock_30_array[1];
                $Stock_40_array=Array_max($Stock_40, true);
                $Stock_40_max=$Stock_40_array[0];
                $Stock_40_city=$Stock_40_array[1];
                $Stock_50_array=Array_max($Stock_50, true);
                $Stock_50_max=$Stock_50_array[0];
                $Stock_50_city=$Stock_50_array[1];
                $Stock_60_array=Array_max($Stock_60, true);
                $Stock_60_max=$Stock_60_array[0];
                $Stock_60_city=$Stock_60_array[1];
                $Stock_75_array=Array_max($Stock_75, true);
                $Stock_75_max=$Stock_75_array[0];
                $Stock_75_city=$Stock_75_array[1];
                $Stock_90_array=Array_max($Stock_90, true);
                $Stock_90_max=$Stock_90_array[0];
                $Stock_90_city=$Stock_90_array[1];
                $Stock_105_array=Array_max($Stock_105, true);
                $Stock_105_max=$Stock_105_array[0];
                $Stock_105_city=$Stock_105_array[1];
                $Stock_125_array=Array_max($Stock_125, true);
                $Stock_125_max=$Stock_125_array[0];
                $Stock_125_city=$Stock_125_array[1];
                $Stock_150_array=Array_max($Stock_150, true);
                $Stock_150_max=$Stock_150_array[0];
                $Stock_150_city=$Stock_150_array[1];
                if($Veh->Type ==93)
                    $Vehicule_Nbr_Conso=ceil($Regiment->Vehicule_Nbr/10);
                else
                    $Vehicule_Nbr_Conso=$Regiment->Vehicule_Nbr;
                $Conso_move=($Autonomie_Min*$Vehicule_Nbr_Conso)/5;
                $Stock_carbu_city='';
                if($Nation_IA or !$Veh->Carbu_ID)
                {
                    $Octane1='';
                    $Colorc1="warning";
                    $Stock_carbu=65000;
                }
                elseif($Veh->Carbu_ID ==100)
                {
                    $Octane1=" Octane 100";
                    $Colorc1="danger";
                    $Stock_carbu=$Stock_100_max;
                    $Stock_carbu_city=$Stock_100_city;
                }
                elseif($Veh->Carbu_ID ==1)
                {
                    $Octane1=" Diesel";
                    $Colorc1="success";
                    $Stock_carbu=$Stock_1_max;
                    $Stock_carbu_city=$Stock_1_city;
                }
                elseif($Veh->Carbu_ID ==87)
                {
                    $Octane1=" Octane 87";
                    $Colorc1="primary";
                    $Stock_carbu=$Stock_87_max;
                    $Stock_carbu_city=$Stock_87_city;
                }
                else{
                    $Octane1='';
                    $Colorc1="warning";
                    $Stock_carbu=0;
                }
                $Carte_Log="<a href='carte_ground.php?map=".$Regiment->Front."&mode=12&cible=".$Lieu->ID."' class='btn btn-sm btn-primary' onclick='window.open(this.href); return false;'>Carte logistique</a>";
                $Dist_max_ori=$Autonomie_Min;
                if($result)
                {
                    while($data=mysqli_fetch_array($result))
                    {
                        $CT_city=0;
                        $coord=0;
                        $Train_move=false;
                        $train_txt='';
                        $Distance=GetDistance(0,0,$Lieu->Longitude,$Lieu->Latitude,$data[2],$data[3]);
                        $Dist_km=$Distance[0];
                        $lieux_obj.='<option value="'.$data[0].'">'.$data[1].' ('.$Dist_km.'km)</option>';
                        $Faction_Dest=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag']."'"),0);
                        if($data['NoeudR'])
                            $Faction_Dest_Route=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Route']."'"),0);
                        if($data['NoeudF'])
                            $Faction_Dest_Gare=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Gare']."'"),0);
                        if($Faction_Flag ==$Faction and $Lieu->NoeudF >10 and $data['NoeudF'] >10 and $Regiment->Placement ==3 and !$Enis_combi and $Faction_Dest_Gare ==$Faction and $Faction_Dest ==$Faction and !$Pont_block)
                        {
                            $Dist_max=$Dist_train_max;
                            $train_txt=" - Train";
                            $Train_move=true;
                        }
                        elseif($data['NoeudR'] and $Lieu->NoeudR >0 and $Regiment->Placement ==2 and !$Rasputitsa and !$Enis_combi and $Faction_Dest_Route ==$Faction)
                            $Dist_max=$Autonomie_Min*2;
                        else
                            $Dist_max=$Autonomie_Min;
                        if(!$Train_move)
                        {
                            if($Veh->Type ==6 and !$Veh->Mountain and ($data['Zone'] ==4 or $data['Zone'] ==5)) //L'artillerie non motorisée ne peut pas aller en montagne
                                $Dist_km=999;
                            if(($Rasputitsa and $Regiment->Matos !=24) or ($Merzlota and $Veh->mobile!=3))
                            {
                                if($Rasputitsa and $Lieu->Zone!=2 and $Lieu->Zone!=3 and $Lieu->Zone!=4 and $Lieu->Zone!=5 and $Lieu->Zone!=7 and $Regiment->mobile!=3)
                                    $Dist_km*=1.25;
                            }
                            if($Faction !=$Faction_Dest)
                            {
                                if($Enis_combi and $Trait !=16)
                                    $Dist_km*=2;
                                else
                                    $Dist_km*=1.5;
                                if($Premium and $Dist_km <=$Dist_max and $Dist_km <=$Autonomie_Max)$txt_help_dist.="<br>La distance à parcourir vers ".$data[1]." est augmentée de 50% (<span class='text-danger'>".$Dist_km."</span>km) car ".$data[1]." est contrôlé par l'ennemi. (1)";
                            }
                            if($Dist_max >$Autonomie_Max)$Dist_max=$Autonomie_Max;
                        }
                        if($Dist_km <=$Dist_max)
                        {
                            //Affichage en rouge si destination hors de portée des dépôts
                            $dest_txt_css='text-danger';
                            if(is_array($Depots_region)){
                                $Depots_list=implode(",",$Depots_region);
                                $resultdr=mysqli_query($con,"SELECT Nom,Longitude,Latitude FROM Lieu WHERE ID IN (".$Depots_list.")");
                                if($resultdr){
                                    while($datadr=mysqli_fetch_array($resultdr)){
                                        $Distance_depot_region=GetDistance(0,0,$data[2],$data[3],$datadr['Longitude'],$datadr['Latitude']);
                                        if($Distance_depot_region[0] <=$Auto_Log){
                                            $dest_txt_css='text-primary';
                                        }
                                    }
                                    mysqli_free_result($resultdr);
                                }
                            }
                            $Impass=$data['Impass'];
                            $sensh='';
                            $sensv='';
                            if($Premium and $Dist_km <=$Dist_max)$txt_help_dist.="<br>La distance à parcourir vers ".$data[1]." est de <span class='text-primary'>".$Dist_km."km</span> et l'autonomie de l'unité est de ".$Dist_max.", permettant le déplacement vers ce lieu.";
                            if($Lieu->Longitude >$data[2])
                            {
                                $sensh='Ouest';
                                $coord+=2;
                                if($Impass ==2 or $Impass ==3 or $Impass ==4 or $Lieu->Impass ==6 or $Lieu->Impass ==7 or $Lieu->Impass ==8)
                                    $CT_city=999;
                            }
                            elseif($Lieu->Longitude <$data[2])
                            {
                                $sensh='Est';
                                $coord+=1;
                                if($Impass ==6 or $Impass ==7 or $Impass ==8 or $Lieu->Impass ==2 or $Lieu->Impass ==3 or $Lieu->Impass ==4)
                                    $CT_city=999;
                            }
                            if($sensh)
                            {
                                if($Lieu->Latitude >$data[3]+0.25)
                                {
                                    $sensv='Sud';
                                    $coord+=20;
                                    if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Lieu->Impass ==4 or $Lieu->Impass ==5 or $Lieu->Impass ==6)
                                        $CT_city=999;
                                }
                                elseif($Lieu->Latitude <$data[3]-0.25)
                                {
                                    $sensv='Nord';
                                    $coord+=10;
                                    if($Impass == 4 or $Impass == 5 or $Impass == 6 or $Lieu->Impass ==1 or $Lieu->Impass == 2 or $Lieu->Impass == 8)
                                        $CT_city=999;
                                }
                            }
                            else
                            {
                                if($Lieu->Latitude >$data[3])
                                {
                                    $sensv='Sud';
                                    $coord+=20;
                                    if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Lieu->Impass ==4 or $Lieu->Impass ==5 or $Lieu->Impass ==6)
                                        $CT_city=999;
                                }
                                elseif($Lieu->Latitude <$data[3])
                                {
                                    $sensv='Nord';
                                    $coord+=10;
                                    if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Lieu->Impass ==1 or $Lieu->Impass ==2 or $Lieu->Impass ==8)
                                        $CT_city=999;
                                }
                            }
                            $sens=$sensv.' '.$sensh;
                            if($data['NoeudR'])$sens.=" - route";
                            if($CT_city <999)
                            {
                                if($Regiment->Placement ==3 and $data['NoeudF'])
                                    $icone="<a href='#' class='popup'><img src='images/rail.gif' title='Noeud Ferroviaire'><span><b>Noeud Ferroviaire</b> Les unités se déplaçant entre deux noeuds ferroviaires contrôlés par leur faction doublent leur distance de déplacement et ignorent les pénalités de déplacement dues au relief.</span></a>";
                                elseif($data['NoeudR'] and !$Rasputitsa)
                                    $icone="<a href='#' class='popup'><img src='images/route.gif'><span><b>Noeud Routier</b><ul><li>Les unités se déplaçant depuis un noeud routier ne subissent pas les malus dus au terrain.</li><li>Les unités se déplaçant entre deux noeuds routiers contrôlés par leur faction doublent leur distance de déplacement.</li><li>Les unités ennemies présentent sur le noeud routier (transformant la zone en zone de combat) annulent automatiquement tout bonus de déplacement.</li></ul></span></a>";
                                else
                                    $icone="<img src='images/zone".$data['Zone'].".jpg'>";
                                if(($Stock_carbu >=($Dist_max*$Vehicule_Nbr_Conso/10)) or $Ravit)
                                {
                                    $modal_conso='<div class="alert alert-danger">Le déplacement rendra l\'unité inaccessible pendant 24h';
                                    if($Veh->mobile!=4 and $Veh->mobile!=5 and $Veh->Carbu_ID)$modal_conso.=' et consommera '.$Conso_move.'L '.$Octane1;
                                    if($Veh->mobile!=3 and $Veh->mobile!=4)$modal_conso.='<br>L\'unité arrivera en mouvement, pensez à changer sa position une fois arrivé à destination';
                                    if($dest_txt_css =='text-danger')$modal_conso.='<br><b>ATTENTION</b> : Cette destination se trouve hors de portée du ravitaillement de nos dépôts !';
                                    //$Lieux.="<option class='".$dest_txt_css."' value='".$data[0]."'>".$data[1]." (".$Dist_km."km ".$sens.$train_txt.")</option>";
                                    $choix="<tr><td><a href='#' class='".$dest_txt_css."' data-toggle='modal' data-target='#modal-dest-".$data[0]."'><img src='images/".$data['Flag']."20.gif'> ".$data[1]."</a></td><td>".$icone."</td><td>".$Dist_km."km</td></tr>";
                                    $lieux_modal.='<div class="modal fade" id="modal-dest-'.$data[0].'" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h2 class="modal-title">Déplacement
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                </h2>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img class="img-flex" src="images/move_front'.$country.'.jpg">
                                                                <div class="alert alert-warning">Le '.$Regiment->ID.'e bataillon composé de '.$Regiment->Vehicule_Nbr.' '.$Veh->Nom.' se déplacera vers <b>'.$data[1].'</b></div>
                                                                <form action="ground_em_ia_go.php" method="post"><input type="hidden" name="depot_ravit" value="'.$Stock_carbu_city.'"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu->ID.'"><input type="hidden" name="cible" value="'.$data[0].'"><input class="btn btn-danger" type="submit" value="confirmer"></form>'.$modal_conso.'</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';
                                }
                                else
                                    $choix="<tr><td><img src='images/".$data['Flag']."20.gif'> ".$data[1]."</td><td>".$icone."</td><td>".$Dist_km."km <a href='#' class='popup'><img src='images/map/carbu_icon_empty.png'><span>Pénurie de carburant dans le dépôt</span></a></td></tr>";
                                if($coord ==1) //Est
                                    $Est_txt.=$choix;
                                elseif($coord == 2) //Ouest
                                    $Ouest_txt.=$choix;
                                elseif($coord ==10) //Nord
                                    $Nord_txt.=$choix;
                                elseif($coord == 20) //Sud
                                    $Sud_txt.=$choix;
                                elseif($coord ==11) //NE
                                    $NE_txt.=$choix;
                                elseif($coord == 21) //SE
                                    $SE_txt.=$choix;
                                elseif($coord ==12) //NO
                                    $NO_txt.=$choix;
                                elseif($coord == 22) //SO
                                    $SO_txt.=$choix;
                            }
                        }
                        elseif($Premium and $Dist_km <=$Autonomie_Max)
                        {
                            $txt_help_dist.="<br>La distance à parcourir vers ".$data[1]." est de <span class='text-danger'>".$Dist_km."km</span> et l'autonomie de l'unité est de ".$Dist_max.", ne permettant pas le déplacement vers ce lieu.";
                            if(!$Train_move)$txt_help_dist.=" Aucun noeud ferroviaire n'est utilisé.";
                        }
                    }
                    mysqli_free_result($result);
                }
                mysqli_close($con);
                $Carte_Bouton.="<div class='btn btn-sm btn-primary'><a href='carte_ground.php?map=".$Regiment->Front."&mode=10&cible=".$Lieu->ID."&reg=".$Unit."' onclick='window.open(this.href); return false;'>Voir la carte</a></div>";
                //Retraite stratégique
                if($Regiment->Lieu_ID !=$Retraite and (!$Regiment->Vehicule_Nbr or (!$lieux_modal and !$Enis_combi)))
                {
                    $Retraite_ghq=$Retraite;
                    $Retraite_ok=true;
                }
                if($GHQ and $Veh->Type ==95 and (!$Regiment->Vehicule_Nbr or (!$lieux_modal and !$Enis_combi)))
                {
                    $Retraite_ghq=Get_Retraite($Regiment->Front,$country,40);
                    $Retraite_ok=true;
                }
                if($Retraite_ok){
                    $Retraite_ghq_Nom=GetData("Lieu","ID",$Retraite_ghq,"Nom");
                    $choix="<a href='#' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#modal-dest-".$Retraite_ghq."_'>Retraite vers ".$Retraite_ghq_Nom."</a>";
                    $lieux_modal.='<div class="modal fade" id="modal-dest-'.$Retraite_ghq.'_" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h2 class="modal-title">Déplacement
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                </h2>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img class="img-flex" src="images/move_front'.$country.'.jpg">
                                                                <div class="alert alert-warning">Le '.$Regiment->ID.'e bataillon composé de '.$Regiment->Vehicule_Nbr.' '.$Veh->Nom.' effectuera une retraite vers <b>'.$Retraite_ghq_Nom.'</b></div>
                                                                <form action="ground_em_ia_go.php" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu->ID.'"><input type="hidden" name="cible" value="'.$Retraite_ghq.'_"><input class="btn btn-danger" type="submit" value="confirmer"></form><div class="alert alert-danger"><b>ATTENTION</b><br>Les troupes seront perdues, de même que l\'expérience et la compétence de l\'unité!</div></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';
                    $Centre_txt.=$choix;
                }
                if($Veh->Type ==92 or $Veh->Type ==96) //Paras
                {
                    if($Lieu->BaseAerienne >0 and $Regiment->Placement ==1)
                    {
                        $con=dbconnecti();
                        $Trans_esc=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit WHERE Base='$Regiment->Lieu_ID' AND Type=6 AND Pays='$country' AND Etat=1"),0);
                        mysqli_close($con);
                        if($Trans_esc)
                        {
                            if($Veh->Type ==92)
                                $Para_pos=13;
                            else
                                $Para_pos=12;
                            $Decharger.="<br><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='pos' value='".$Para_pos."'><input type='submit' value='Parachutage' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                        }
                        else
                            $Decharger.="<div class='alert alert-info'>Les unités parachutistes présentes sur un aérodrome peuvent être parachutées par des escadrilles de transport de leur nation basées sur le même aérodrome.</div>";
                    }
                    else
                        $Decharger.="<div class='alert alert-info'>Les unités parachutistes présentes sur un aérodrome peuvent être parachutées par des escadrilles de transport de leur nation basées sur le même aérodrome.</div>";
                }
                //Renforts
                if($Regiment->Placement ==6 and (($Lieu->ID ==$Veh->Usine1 and $Veh->Usine1 >0) or ($Lieu->ID ==$Veh->Usine2 and $Veh->Usine2 >0) or ($Lieu->ID ==$Veh->Usine3 and $Veh->Usine3 >0)))$Sur_usine=true;
                if(!$Regiment->Move && $Regiment->Vehicule_Nbr <$Max_Veh && (($Lieu->ID ==$Retraite && $Retraite >0) || $Sur_usine))
                {
                    $con=dbconnecti(4);
                    $Perdus=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$Regiment->Vehicule_ID'"),0);
                    $Perdus2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$Regiment->Vehicule_ID'"),0);
                    if($Veh->Categorie ==5 or $Veh->Categorie ==6)
                        $Perdus3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$Regiment->Vehicule_ID'"),0);
                    mysqli_close($con);
                    $con=dbconnecti();
                    //$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$Regiment->Vehicule_ID'"),0);
                    $Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$Regiment->Vehicule_ID'"),0);
                    $Enis_oq=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Regiment->Lieu_ID' AND r.Placement='$Regiment->Placement' AND r.Vehicule_Nbr >0"),0);
                    $Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Regiment->Lieu_ID' AND r.Placement='$Regiment->Placement' AND r.Vehicule_Nbr >0 AND p.Faction='$Faction'"),0);
                    mysqli_close($con);
                    if($Veh->Production >0)
                    {
                        $Perdus=$Perdus+$Perdus2+$Perdus3;
                        if($Veh->Repare >$Perdus)$Veh->Repare=$Perdus;
                        $Reste=$Stock-$Service-$Service2-$Perdus+$Veh->Repare;
                        if($Reste+$Service+$Service2 >$Stock)$Reste=$Stock-$Service-$Service2;
                        if($Max_Veh >25)
                        {
                            $up_renf=floor($Max_Veh/10);
                            $down_exp=floor($up_renf/10);
                        }
                        else
                        {
                            $up_renf=floor($Max_Veh/4);
                            $down_exp=floor($up_renf/2);
                        }
                        if($up_renf >$Stock)$up_renf=$Stock;
                        if($up_renf <1)$up_renf=1;
                    }
                    else
                        $Reste=0;
                    if($Enis_oq >0)
                        $Renforts_txt='<tr><td class="text-danger" colspan="3">Base arrière sous le feu ennemi!</td></tr>';
                    elseif($Embout >5)
                        $Renforts_txt='<tr><td class="text-danger" colspan="3">Trop de troupes sur cette zone!</td></tr>';
                    elseif($Veh->Type ==95)
                    {
                        if(!$Regiment->Vehicule_Nbr)
                        {
                            if($Regiment->CT >=$Veh->Reput){
                                $form = new Form();
                                $Renforts_txt = '<tr><td>' .
                                    $form->CreateForm('ground_em_ia_go.php', 'POST', '')
                                        ->AddInput('Unit', '', 'hidden', $Unit)
                                        ->AddInput('Max', '', 'hidden', $Max_Veh)
                                        ->AddInput('renf', '', 'hidden', 1)
                                        ->EndForm('Renforts', 'warning btn-sm', 'A') . '</td>
                                    <td>'.$Reste.'</td>
                                    <td>'.$up_renf.'</td>
                                </tr>';
                            }
                            else
                                $Renforts_txt='<tr><td colspan="3">Crédits Temps insuffisants ('.$Regiment->CT.'/'.$Veh->Reput.')</td></tr>';
                        }
                    }
                    elseif($Reste >0)
                    {
                        $Reput_Renf_ori=$Veh->Reput;
                        $Usine1_Nom=GetData("Lieu","ID",$Veh->Usine1,"Nom");
                        if(!$Retraite_Nom)$Retraite_Nom=GetData("Lieu","ID",$Retraite,"Nom");
                        if($Sur_usine or ($Trait ==2 and ($Veh->Type ==92 or $Veh->Type ==96 or $Veh->Type ==97)))
                            $Veh->Reput=1;
                        elseif($Trait ==3 and ($Veh->Categorie ==1 or $Veh->Categorie ==2 or $Veh->Categorie ==3 or $Veh->Type ==8))
                            $Veh->Reput=floor($Veh->Reput/2);
                        elseif($Trait ==4 and ($Veh->Categorie ==5 or $Veh->Categorie ==6 or $Veh->Type ==6 or $Veh->Type ==12))
                            $Veh->Reput=floor($Veh->Reput/2);
                        if($Regiment->CT >=$Veh->Reput){
                            $form = new Form();
                            $Renforts_txt = '<tr><td>' .
                                $form->CreateForm('ground_em_ia_go.php', 'POST', '')
                                    ->AddInput('Unit', '', 'hidden', $Unit)
                                    ->AddInput('Max', '', 'hidden', $Max_Veh)
                                    ->AddInput('renf', '', 'hidden', 1)
                                    ->EndForm('Renforts', 'warning btn-sm', 'A') . '</td>
                                    <td>'.$Reste.'</td>
                                    <td>'.Output::popup($up_renf, 'Pour se renforcer, l\'unité doit se trouver sur la base arrière <b>'.$Retraite_Nom.'</b> pour se renforcer de <b>'.$up_renf.'</b>, ou sur l\'usine de production <b>'.$Usine1_Nom.'</b> pour se renforcer au <b>maximum</b> (en fonction des stocks disponibles)').'</td>
                                </tr>';
                        }
                        else
                            $Renforts_txt='<tr><td colspan="3">Crédits Temps insuffisants ('.$Regiment->CT.'/'.$Veh->Reput.')</td></tr>';
                    }
                    else
                        $Renforts_txt='<tr><td colspan="3">Troupes non disponibles ('.$Regiment->CT.'/'.$Veh->Reput.')<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Votre nation doit réparer les véhicules concernés</span></a></td></tr>';
                }
                elseif($Regiment->Vehicule_Nbr <$Max_Veh)
                {
                    if(!$Retraite_Nom)$Retraite_Nom=GetData("Lieu","ID",$Retraite,"Nom");
                    $Usine1_Nom=GetData("Lieu","ID",$Veh->Usine1,"Nom");
                    $Renforts_txt='<tr><td colspan="3" class="text-center text-warning">Effectifs incomplets<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Pour se renforcer, l\'unité doit se trouver sur la base arrière <b>'.$Retraite_Nom.'</b> pour un coût de <b>'.$Veh->Reput.'CT</b>, ou sur l\'usine de production <b>'.$Usine1_Nom.'</b> pour un coût de <b>1CT</b>. Compte comme une action du jour.</span></a></td></tr>';
                }
                else
                    $Renforts_txt='<tr><td colspan="3" class="text-success text-center">Effectifs au maximum</td></tr>';
            }
            elseif(!$Regiment->Move && $Regiment->Position ==12){
                $Decharger.="<br><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='pos' value='4'><input type='submit' value='Annuler le Parachutage' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
            }elseif(!$Regiment->Move)
                $Lieux_txt='Pas avant demain';
            if(!$GHQ || $Admin || $Nation_IA)
            {
                //Revendication
                if ($Nation_IA && $Faction == ALLIES && $Lieu->Pays != $country) {
                    $revendication = false;
                } elseif(($Veh->Type ==95 || $Veh->Detection >10) && $Regiment->Position !=6 && $Regiment->Position !=11 && $Regiment->Position !=12 && $Regiment->Position !=13 && $Regiment->Position !=14 && !$Enis_combi && !$Regiment->Move && $Regiment->Vehicule_Nbr >0) {
                    $revendication = true;
                }
                if($revendication == true)
                {
                    if($Lieu->Recce || !$Lieu->ValeurStrat || $Regiment->Placement >0)
                    {
                        if($Veh->Type ==95)
                            $Rev_mode=2;
                        else
                            $Rev_mode=3;
                        if($Regiment->Placement >0)
                        {
                            if($Regiment->Placement ==1)
                            {
                                $con=dbconnecti();
                                $Faction_Place=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$Lieu->Flag_Air'"),0);
                                $Esc_Oqp=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit as u,Pays as p WHERE u.Base='$Regiment->Lieu_ID' AND u.Pays=p.ID AND p.Faction<>'$Faction' AND Etat=1 AND Garnison >0"),0);
                                mysqli_close($con);
                            }
                            elseif($Regiment->Placement ==2)
                                $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Route,"Faction");
                            elseif($Regiment->Placement ==3)
                                $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Gare,"Faction");
                            elseif($Regiment->Placement ==4)
                                $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Port,"Faction");
                            elseif($Regiment->Placement ==5)
                                $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Pont,"Faction");
                            elseif($Regiment->Placement ==6)
                                $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Usine,"Faction");
                            elseif($Regiment->Placement ==7)
                                $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Radar,"Faction");
                            elseif($Regiment->Placement ==11)
                                $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Plage,"Faction");
                            if($Faction !=$Faction_Place && !$Esc_Oqp)
                                $Atk_Options.="<tr><td><form action='ground_em_ia_go.php' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='cible' value='".$Lieu->ID."'><input type='hidden' name='rev' value='".$Rev_mode."'>
                                    <input type='submit' value='Revendiquer' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Revendiquer compte comme action du jour</span></a></div></td>
                                    <td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Revendiquer un lieu stratégique nécessite que le lieu soit reconnu soit via une reco terrestre ou une reco stratégique.<br>Les lieux non stratégiques peuvent être revendiqués sans reco préalable.<br>Pour revendiquer une caserne, la garnison doit être éliminée au préalable.</span></a></td></tr>";
                            elseif($Regiment->Placement ==1 && $Esc_Oqp)
                                $Atk_Options.="<div class='alert alert-danger'>Des avions ennemis occupent l'aérodrome</div>";
                        }
                        elseif($Regiment->Placement ==0 && $Faction_Flag !=$Faction && $Lieu->Garnison <1 && ($Lieu->Recce || !$Lieu->ValeurStrat))
                        {
                            $Rev_ok=false;
                            $Faction_Ori=GetData("Pays","ID",$Lieu->Pays,"Faction");
                            if($Faction ==$Faction_Ori)
                            {
                                $Pays_Rev=$Lieu->Pays;
                                $Faction_Rev=$Faction_Ori;
                            }
                            else
                            {
                                $Pays_Rev=$country;
                                $Faction_Rev=$Faction;
                            }
                            if($Lieu->Flag_Pont && !$Faction_Pont)$Faction_Pont=GetData("Pays","ID",$Lieu->Flag_Pont,"Faction");
                            if($Lieu->Flag_Port)$Faction_Port=GetData("Pays","ID",$Lieu->Flag_Port,"Faction");
                            if($Lieu->Flag_Gare)$Faction_Gare=GetData("Pays","ID",$Lieu->Flag_Gare,"Faction");
                            if($Lieu->Flag_Route)$Faction_Route=GetData("Pays","ID",$Lieu->Flag_Route,"Faction");
                            if($Lieu->Flag_Air)$Faction_Air=GetData("Pays","ID",$Lieu->Flag_Air,"Faction");
                            if($Lieu->Flag_Usine)$Faction_Usine=GetData("Pays","ID",$Lieu->Flag_Usine,"Faction");
                            if($Lieu->Flag_Radar)$Faction_Radar=GetData("Pays","ID",$Lieu->Flag_Radar,"Faction");
                            if($Lieu->Flag_Plage)$Faction_Plage=GetData("Pays","ID",$Lieu->Flag_Plage,"Faction");
                            if($Lieu->ValeurStrat ==10)
                            {
                                $Rev_ok=true;
                                if(($Lieu->Pont_Ori or $Lieu->Fleuve) and $Faction_Pont !=$Faction_Rev)
                                    $Rev_ok=false;
                                if($Lieu->Port_Ori and $Faction_Port !=$Faction_Rev)
                                    $Rev_ok=false;
                                if($Lieu->NoeudF_Ori and $Faction_Gare !=$Faction_Rev)
                                    $Rev_ok=false;
                                if($Lieu->NoeudR and $Faction_Route !=$Faction_Rev)
                                    $Rev_ok=false;
                                if($Cible_base and $Faction_Air !=$Faction_Rev)
                                    $Rev_ok=false;
                                if($Usine and $Faction_Usine !=$Faction_Rev)
                                    $Rev_ok=false;
                                if($Lieu->Radar_Ori and $Faction_Radar !=$Faction_Rev)
                                    $Rev_ok=false;
                                if($Lieu->Plage and $Faction_Plage !=$Faction_Rev)
                                    $Rev_ok=false;
                            }
                            elseif($Lieu->ValeurStrat >5)
                            {
                                //3 zones
                                $Rev_part=0;
                                if($Lieu->Pont_Ori or $Lieu->Fleuve)
                                {
                                    if($Faction_Pont ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->Port_Ori and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve)))
                                {
                                    if($Faction_Port ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->NoeudF_Ori and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori)))
                                {
                                    if($Faction_Gare ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->NoeudR and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori)))
                                {
                                    if($Faction_Route ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Cible_base and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori and !$Lieu->NoeudR)))
                                {
                                    if($Faction_Air ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Usine and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori and !$Lieu->NoeudR and !$Cible_base)))
                                {
                                    if($Faction_Usine ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->Radar_Ori and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori and !$Lieu->NoeudR and !$Cible_base and !$Usine)))
                                {
                                    if($Faction_Radar ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->Plage and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori and !$Lieu->NoeudR and !$Cible_base and !$Usine and !$Lieu->Radar_Ori)))
                                {
                                    if($Faction_Plage ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Rev_part >=2)
                                    $Rev_ok=true;
                            }
                            elseif($Lieu->ValeurStrat >3)
                            {
                                //2 zones
                                $Rev_part=0;
                                if($Lieu->Pont_Ori or $Lieu->Fleuve)
                                {
                                    if($Faction_Pont ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->Port_Ori and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve)))
                                {
                                    if($Faction_Port ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->NoeudF_Ori and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori)))
                                {
                                    if($Faction_Gare ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->NoeudR and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori)))
                                {
                                    if($Faction_Route ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Cible_base and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori and !$Lieu->NoeudR)))
                                {
                                    if($Faction_Air ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Usine and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori and !$Lieu->NoeudR and !$Cible_base)))
                                {
                                    if($Faction_Usine ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->Radar_Ori and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori and !$Lieu->NoeudR and !$Cible_base and !$Usine)))
                                {
                                    if($Faction_Radar ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Lieu->Plage and ($Rev_part or (!$Lieu->Pont_Ori and !$Lieu->Fleuve and !$Lieu->Port_Ori and !$Lieu->NoeudF_Ori and !$Lieu->NoeudR and !$Cible_base and !$Usine and !$Lieu->Radar_Ori)))
                                {
                                    if($Faction_Plage ==$Faction_Rev)
                                        $Rev_part+=1;
                                }
                                if($Rev_part >=1)
                                    $Rev_ok=true;
                            }
                            elseif($Lieu->ValeurStrat >0)
                            {
                                if($Lieu->Pont_Ori or $Lieu->Fleuve)
                                {
                                    if($Faction_Pont ==$Faction_Rev)
                                        $Rev_ok=true;
                                }
                                elseif($Lieu->Port_Ori)
                                {
                                    if($Faction_Port ==$Faction_Rev)
                                        $Rev_ok=true;
                                }
                                elseif($Lieu->NoeudF_Ori)
                                {
                                    if($Faction_Gare ==$Faction_Rev)
                                        $Rev_ok=true;
                                }
                                elseif($Lieu->NoeudR)
                                {
                                    if($Faction_Route ==$Faction_Rev)
                                        $Rev_ok=true;
                                }
                                elseif($Cible_base)
                                {
                                    if($Faction_Air ==$Faction_Rev)
                                        $Rev_ok=true;
                                }
                                elseif($Usine)
                                {
                                    if($Faction_Usine ==$Faction_Rev)
                                        $Rev_ok=true;
                                }
                                elseif($Lieu->Radar_Ori)
                                {
                                    if($Faction_Radar ==$Faction_Rev)
                                        $Rev_ok=true;
                                }
                                elseif($Lieu->Plage)
                                {
                                    if($Faction_Plage ==$Faction_Rev)
                                        $Rev_ok=true;
                                }
                                else
                                    $Rev_ok=true;
                            }
                            else
                                $Rev_ok=true;
                            if($Rev_ok)
                                $Atk_Options.="<tr><td><form action='ground_em_ia_go.php' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='cible' value='".$Lieu->ID."'><input type='hidden' name='rev' value='3'>
                                    <input type='submit' value='Revendiquer' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Revendiquer compte comme action du jour</span></a></div></td>
                                    <td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Revendiquer un lieu stratégique nécessite que le lieu soit reconnu soit via une reco tactique ou une reco stratégique.<br>Les lieux non stratégique peuvent être revendiqués sans reco préalable.<br>Pour revendiquer une caserne, la garnison doit être éliminée au préalable.</span></a></td></tr>";
                            else
                                $Atk_Options.="<div class='alert alert-danger'>Les zones nécessaires à la revendication de la caserne ne sont pas sous contrôle de votre faction</div>";
                        }
                    }
                    elseif($Faction !=$Faction_Flag)
                        $Atk_Options.="<div class='alert alert-danger'>Une reconnaissance stratégique ou terrestre est une condition préalable à la revendication</div>";
                }
            }
            //Position
            $Pos_ori=GetPosGr($Regiment->Position);
            //Camions ravit
            if($Veh->Type ==1)
            {
                $Fret_options='';
                $Divisions='Etat-Major';
                $Bl_conso="<span class='label label-default' title='Charge possible'>".$Veh->Charge."kg</span>";
                if($Faction ==$Faction_Flag and $Regiment->Vehicule_Nbr >0)
                {
                    if(($Regiment->Placement ==4 or $Regiment->Placement ==11) and !$Regiment->Fret)
                    {
                        $Pos_titre='Transit';
                        $Positions='<form action="index.php?view=ground_em_ia_go" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><select name="pos" class="form-control" style="max-width:200px; display:inline;"><option value="0">Ne rien changer</option><option value="11">En transit (compte comme action du jour)</option></select>
                                    <input class="btn btn-sm btn-warning" type="submit" value="Changer" onclick="this.disabled=true;this.form.submit();"></form>';
                        $txt_help.="<div class='alert alert-info'>Vous pouvez embarquer cette unité sur des navires de transport via la position 'En transit'<br>Si cette unité possède du fret, vous ne pouvez pas réaliser cette action. Déchargez d'abord son fret</div>";
                    }
                    elseif($Lieu->ValeurStrat >3 and !$Regiment->Move)
                    {
                        $Pos_titre='Fret';
                        $Mult_Camion=$Veh->Charge*$Regiment->Vehicule_Nbr/1000;
                        $Qty_carbu_camion=floor(1000*$Mult_Camion);
                        $Mult_Camion_txt="<input type='hidden' name='Mult' value='".$Mult_Camion."'>";
                        $depot_info="<h3>Dépôt de ".$Lieu->Nom."</h3><div style='overflow:auto;'><table class='table'>
                            <thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
                            <th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th>
                            <th>Charges de Profondeur</th><th>Mines</th><th>Torpilles</th><th>Rockets</th><th>Fusées</th></tr></thead>
                            <tr><td>".$Lieu->Stock_Essence_87."</td><td>".$Lieu->Stock_Essence_100."</td><td>".$Lieu->Stock_Essence_1."</td><td>".$Lieu->Stock_Munitions_8."</td><td>".$Lieu->Stock_Munitions_13."</td>
                            <td>".$Lieu->Stock_Munitions_20."</td><td>".$Lieu->Stock_Munitions_30."</td><td>".$Lieu->Stock_Munitions_40."</td><td>".$Lieu->Stock_Munitions_50."</td><td>".$Lieu->Stock_Munitions_60."</td>
                            <td>".$Lieu->Stock_Munitions_75."</td><td>".$Lieu->Stock_Munitions_90."</td><td>".$Lieu->Stock_Munitions_105."</td><td>".$Lieu->Stock_Munitions_125."</td><td>".$Lieu->Stock_Munitions_150."</td>
                            <td>".$Lieu->Stock_Bombes_300."</td><td>".$Lieu->Stock_Bombes_400."</td><td>".$Lieu->Stock_Bombes_800."</td><td>".$Lieu->Stock_Bombes_80."</td><td>".$Lieu->Stock_Bombes_30."</td></tr>
                            </table></div>";
                        if($Lieu->Stock_Munitions_8 >(2000*$Mult_Camion))
                            $Fret_options.="<option value='8'>".floor(2000*$Mult_Camion)." cartouches de 8mm</option>";
                        else
                            $Fret_options.="<option value='8' disabled>".floor(2000*$Mult_Camion)." cartouches de 8mm</option>";
                        if($Lieu->Stock_Munitions_13 >$Qty_carbu_camion)
                            $Fret_options.="<option value='13'>".$Qty_carbu_camion." cartouches de 13mm</option>";
                        if($Lieu->Stock_Munitions_20 >(20000/50*$Mult_Camion))
                            $Fret_options.="<option value='20'>".floor(400*$Mult_Camion)." obus de 20mm</option>";
                        if($Lieu->Stock_Munitions_30 >(10000/50*$Mult_Camion))
                            $Fret_options.="<option value='30'>".floor(200*$Mult_Camion)." obus de 30mm</option>";
                        if($Lieu->Stock_Munitions_40 >(5000/50*$Mult_Camion))
                            $Fret_options.="<option value='40'>".floor(100*$Mult_Camion)." obus de 40mm</option>";
                        if($Lieu->Stock_Munitions_50 >(3000/50*$Mult_Camion))
                            $Fret_options.="<option value='50'>".floor(60*$Mult_Camion)." obus de 50mm</option>";
                        if($Lieu->Stock_Munitions_60 >(2000/50*$Mult_Camion))
                            $Fret_options.="<option value='60'>".floor(40*$Mult_Camion)." obus de 60mm</option>";
                        if($Lieu->Stock_Munitions_75 >(1500/50*$Mult_Camion))
                            $Fret_options.="<option value='75'>".floor(30*$Mult_Camion)." obus de 75mm</option>";
                        if($Lieu->Stock_Munitions_90 >(1000/50*$Mult_Camion))
                            $Fret_options.="<option value='90'>".floor(20*$Mult_Camion)." obus de 90mm</option>";
                        if($Lieu->Stock_Munitions_105 >(750/50*$Mult_Camion))
                            $Fret_options.="<option value='105'>".floor(15*$Mult_Camion)." obus de 105mm</option>";
                        if($Lieu->Stock_Munitions_125 >(500/50*$Mult_Camion))
                            $Fret_options.="<option value='125'>".floor(10*$Mult_Camion)." obus de 125mm</option>";
                        if($Lieu->Stock_Munitions_150 >(4*$Mult_Camion))
                            $Fret_options.="<option value='150'>".floor(4*$Mult_Camion)." obus de 150mm</option>";
                        if($Lieu->Stock_Bombes_50 >(2000/50*$Mult_Camion))
                            $Fret_options.="<option value='9050'>".floor(40*$Mult_Camion)." bombes de 50kg</option>";
                        if($Lieu->Stock_Bombes_125 >(1000/50*$Mult_Camion))
                            $Fret_options.="<option value='9125'>".floor(20*$Mult_Camion)." bombes de 125kg</option>";
                        if($Lieu->Stock_Bombes_250 >(500/50*$Mult_Camion))
                            $Fret_options.="<option value='9250'>".floor(10*$Mult_Camion)." bombes de 250kg</option>";
                        if($Lieu->Stock_Bombes_500 >(4*$Mult_Camion))
                            $Fret_options.="<option value='9500'>".floor(4*$Mult_Camion)." bombes de 500kg</option>";
                        if($Lieu->Stock_Bombes_1000 >(2*$Mult_Camion))
                            $Fret_options.="<option value='10000'>".floor(2*$Mult_Camion)." bombes de 1000kg</option>";
                        if($Lieu->Stock_Bombes_2000 >$Mult_Camion)
                            $Fret_options.="<option value='11000'>".floor($Mult_Camion)." bombes de 2000kg</option>";
                        if($Lieu->Stock_Bombes_300 >(250/50*$Mult_Camion))
                            $Fret_options.="<option value='300'>".floor(5*$Mult_Camion)." charges de profondeur</option>";
                        if($Lieu->Stock_Bombes_400 >(250/50*$Mult_Camion))
                            $Fret_options.="<option value='400'>".floor(5*$Mult_Camion)." mines</option>";
                        if($Lieu->Stock_Bombes_80 >(1000/50*$Mult_Camion))
                            $Fret_options.="<option value='80'>".floor(20*$Mult_Camion)." rockets</option>";
                        if($Lieu->Stock_Bombes_800 >(2*$Mult_Camion))
                            $Fret_options.="<option value='800'>".floor(2*$Mult_Camion)." torpilles</option>";
                        if($Lieu->Stock_Bombes_30 >(10000/50*$Mult_Camion))
                            $Fret_options.="<option value='930'>".floor(200*$Mult_Camion)." fusées éclairantes</option>";
                        if($Lieu->Stock_Essence_87 >$Qty_carbu_camion)
                            $Fret_options.="<option value='1087'>".$Qty_carbu_camion."L Essence 87 Octane</option>";
                        else
                            $Fret_options.="<option value='1087' disabled>".$Qty_carbu_camion."L Essence 87 Octane</option>";
                        if($Lieu->Stock_Essence_100 >$Qty_carbu_camion)
                            $Fret_options.="<option value='1100'>".$Qty_carbu_camion."L Essence 100 Octane</option>";
                        else
                            $Fret_options.="<option value='1100' disabled>".$Qty_carbu_camion."L Essence 100 Octane</option>";
                        if($Lieu->Stock_Essence_1 >$Qty_carbu_camion)
                            $Fret_options.="<option value='1001'>".$Qty_carbu_camion."L de Diesel</option>";
                        else
                            $Fret_options.="<option value='1001' disabled>".$Qty_carbu_camion."L de Diesel</option>";
                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'>".$Mult_Camion_txt."<input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><select name='fret' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option>".$Fret_options."</select><input type='submit' value='Charger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une quantité minimale est nécessaire dans le dépôt pour pouvoir transporter du fret<br>Charger du fret remplace l'éventuelle cargaison existante qui est perdue.</span></a></td></tr>";
                    }
                    if(!$Regiment->Fret)
                        $Pos_ori="Vide";
                    elseif($Regiment->Fret ==1001)
                        $Pos_ori=$Qty_carbu_camion."L Diesel";
                    elseif($Regiment->Fret ==1087)
                        $Pos_ori=$Qty_carbu_camion."L Essence 87";
                    elseif($Regiment->Fret ==1100)
                        $Pos_ori=$Qty_carbu_camion."L Essence 100";
                    elseif($Regiment->Fret ==1)
                        $Pos_ori="Troupes";
                    elseif($Regiment->Fret ==930)
                        $Pos_ori="Fusées";
                    elseif($Regiment->Fret ==80)
                        $Pos_ori="Rockets";
                    elseif($Regiment->Fret ==200)
                        $Pos_ori="Troupes IA";
                    elseif($Regiment->Fret ==300)
                        $Pos_ori="Charges";
                    elseif($Regiment->Fret ==400)
                        $Pos_ori="Mines";
                    elseif($Regiment->Fret ==800)
                        $Pos_ori="Torpilles";
                    elseif($Regiment->Fret ==1200)
                        $Pos_ori="Obus de 200mm";
                    elseif($Regiment->Fret ==9050 or $Regiment->Fret ==9125 or $Regiment->Fret ==9250 or $Regiment->Fret ==9500)
                        $Pos_ori="Bombes de ".substr($Regiment->Fret,1)."kg";
                    elseif($Regiment->Fret >9999)
                        $Pos_ori="Bombes de ".substr($Regiment->Fret,0,-1)."kg";
                    else
                        $Pos_ori="Obus de ".$Regiment->Fret."mm";
                    if($Regiment->Fret and $Faction ==$Faction_Flag and $Lieu->ValeurStrat >3 and !$Regiment->Move)
                        $Atk_Options.="<tr><td>".$Pos_ori."<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='Dech' value='".$Regiment->Fret."'><input type='hidden' name='base' value='".$Lieu->ID."'>".$Mult_Camion_txt."<input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                }
            }
            elseif($Veh->Type !=95)
            {
                $Pos_titre='Position';
                if($Regiment->Position !=12 && $Regiment->Position !=13)
                {
                    if(!$Pas_libre)
                    {
                        if(!$GHQ || $Admin || $Nation_IA)
                        {
                            $Positions="<form action='ground_em_ia_go.php' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='pos' class='form-control' style='max-width:200px; display:inline;'><option value='0'>Ne rien changer</option>";
                            if(!$Regiment->Move)
                                $Positions.="<option value='2'>Retranché (conseillé pour les unités isolées)</option>";
                            if(!$Regiment->Move && $Veh->Detection >10)
                                $Positions.="<option value='14'>Sentinelle [Consomme l'action du jour]</option>";
                            if(!$Regiment->Move || (($Veh->mobile ==MOBILE_WHEEL || $Veh->mobile ==MOBILE_TRACK || $Veh->mobile ==6) && $Veh->Type !=TYPE_ART))
                            {
                                if(!$Regiment->Atk)$Positions.="<option value='5'>Appui (conseillé pour l'artillerie à vocation défensive ou la DCA)</option>";
                                if($Lieu->Zone !=0 && $Lieu->Zone !=8 && $Regiment->Vehicule_Nbr >0)
                                    $Positions.="<option value='3'>Embuscade (conseillé pour l'artillerie AT)</option>";
                                $Positions.="<option value='1'>Défensive (conseillé pour les unités motorisées ou la DCA)</option>
                                    <option value='10'>Ligne (conseillé pour l'infanterie ou l'artillerie AT)</option>
                                    <option value='4'>Mouvement (conseillé avant tout assaut ou attaque)</option>";
                                if($Regiment->Placement ==PLACE_PORT || $Regiment->Placement ==PLACE_PLAGE)
                                {
                                    /*if($Veh->Type ==90)
                                        $Positions.="<option value='32'>En attente de transit (compte comme action du jour)</option>";
                                    else*/
                                    $Positions.="<option value='11'>En transit (compte comme action du jour)</option>";
                                    $txt_help.="<div class='alert alert-info'>Vous pouvez embarquer cette unité sur des navires de transport via la position 'En transit'</div>";
                                }
                                $Positions.='</select><input class="btn btn-sm btn-warning" type="submit" value="Changer" onclick="this.disabled=true;this.form.submit();"></form>';
                            }
                            else
                                $Positions.='</select></form>';
                        }
                    }
                    else
                        $Positions='<span class="text-danger">En combat</span>';
                }
                else
                    $Positions='<span class="text-danger">En transit</span>';
                //Atk
                if(!$Regiment->Move and $Regiment->Vehicule_Nbr >0 and (!$GHQ or $Admin or $Nation_IA))
                {
                    if($Veh->Carbu_ID){
                        $Conso_tot=$Veh->Conso*$Regiment->Vehicule_Nbr;
                        if($Conso_tot <$Conso_move)$Conso_tot=$Conso_move;
                    }else{
                        $Conso_tot=0;
                    }
                    if(!$Canada) //Actions offensives
                    {
                        $con=dbconnecti();
                        $Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu->ID' AND p.Faction='$Faction' AND r.Vehicule_Nbr >0 AND r.Placement='$Regiment->Placement'"),0);
                        if($Embout <=GetEmboutMax($Lieu->ValeurStrat,$Regiment->Placement))
                        {
                            if($Veh->Arme_AT){
                                $Arme_Cal=round(GetData("Armes","ID",$Veh->Arme_AT,"Calibre"));
                                $Var_Stock='Stock_'.$Arme_Cal.'_max';
                                $Stock_AT=$$Var_Stock;
                                $Var_Stock_City='Stock_'.$Arme_Cal.'_city';
                                $Stock_Arme_City=$$Var_Stock_City;
                                $Conso_mun_at=floor($Veh->Arme_AT_mun/10)*$Regiment->Vehicule_Nbr;
                                if($OfficierID and $Sec_Log){
                                    $Conso_mun_at=floor($Conso_mun_at((100-($Avancement/500))*0,01));
                                    if($Conso_mun_at <0)$Conso_mun_at=0;
                                }
                            }
                            if($Veh->Arme_Art){
                                $Arme_Cal=round(GetData("Armes","ID",$Veh->Arme_Art,"Calibre"));
                                $Var_Stock='Stock_'.$Arme_Cal.'_max';
                                $Stock_Art=$$Var_Stock;
                                $Var_Stock_City='Stock_'.$Arme_Cal.'_city';
                                $Stock_Arme_City=$$Var_Stock_City;
                                if($Veh->Type ==6 or $Veh->Type ==8)
                                    $Conso_mun_art=$Veh->Arme_Art_mun*$Regiment->Vehicule_Nbr;
                                else
                                    $Conso_mun_art=floor($Veh->Arme_Art_mun/10)*$Regiment->Vehicule_Nbr;
                                if($OfficierID and $Sec_Log){
                                    $Conso_mun_art=floor($Conso_mun_art((100-($Avancement/500))*0,01));
                                    if($Conso_mun_art <0)$Conso_mun_art=0;
                                }
                            }
                            $CT_Spec_Blitz = 0;
                            $CT_Spec = 0;
                            if(!$Pas_libre and $Regiment->Position !=2 and $Regiment->Position !=3 and $Regiment->Position !=10 and $Regiment->Position !=14 and
                                (($Veh->Arme_Art and ($Stock_Art >=$Conso_mun_art or $Ravit)) or ($Veh->Arme_AT and ($Stock_AT >=$Conso_mun_at or $Ravit)))
                            ) //Arti
                            {
                                if($Regiment->CT >=$CT_Spec)
                                {
                                    if($Regiment->Matos ==8)$Range /=2;
                                    if($Lieu->Flag ==$country)$Range +=500;
                                    if($Veh->Categorie ==8){
                                        //Range
                                        if($Regiment->Position ==2 or $Regiment->Position ==3 or $Regiment->Position ==9 or $Regiment->Position ==10 or $Regiment->Position ==14 or $Regiment->Position ==26)$Range /=2;
                                        if($Regiment->Skill ==73)
                                            $Range*=1.25;
                                        elseif($Regiment->Skill ==72)
                                            $Range*=1.2;
                                        elseif($Regiment->Skill ==47)
                                            $Range*=1.15;
                                        elseif($Regiment->Skill ==15)
                                            $Range*=1.1;
                                        if($Lieu->Meteo <-69)$Range /=2;
                                        if($Lieu->Zone ==6)$Range+=($Regiment->Experience*9);
                                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='conso_mun' value='".$Conso_mun_art."'><input type='hidden' name='pos' value='34'>
                                                        <input type='submit' value='Bombardement' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                                        <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                                        <td>".$Conso_mun_art."x ".$Arme_Cal."mm</td>
                                                        <td>".$Range."m</td>
                                                        <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tir à distance sur les unités détectées situées sur le même lieu. L'unité passera en mode combat pour une durée de 24h.</span></a></td></tr>";
                                        if($Lieu->Recce)
                                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='conso_mun' value='".$Conso_mun_art."'><input type='hidden' name='pos' value='35'>
                                                        <input type='submit' value='Detruire' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                                        <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                                        <td>".$Conso_mun_art."x ".$Arme_Cal."mm</td>
                                                        <td>".$Range."m</td>
                                                        <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tir à distance sur la garnison ou les fortifications du lieu.</span></a></td></tr>";
                                    }
                                    elseif($Veh->Categorie ==15 and $Arme_Cal >74){
                                        if($Lieu->Meteo <-69)$Range /=2;
                                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='conso_mun' value='".$Conso_mun_at."'><input type='hidden' name='pos' value='34'>
                                                        <input type='submit' value='Tirer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                                        <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                                        <td>".$Conso_mun_at."x ".$Arme_Cal."mm</td>
                                                        <td>".$Range."m</td>
                                                        <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tir direct à distance sur les unités détectées situées sur le même lieu. L'unité passera en mode combat pour une durée de 24h.</span></a></td></tr>";
                                    }
                                }
                            }
                            if(!$Pas_libre and ($Veh->Categorie ==2 or $Veh->Categorie ==3 or $Veh->Categorie ==7 or $Veh->Type ==11) and ($Regiment->Position ==4 or $Regiment->Position ==0) and $Veh->Arme_AT and $HasHostiles)
                            {
                                if($Stock_AT >=$Conso_mun_at or $Ravit)
                                {
                                    //$Bl_conso="<span class='label label-".$Colorc1."' title='Consommation attaque ou reco'>".$Conso_tot."L ".$Octane1."</span>";
                                    if($Stock_carbu >=$Conso_tot or $Ravit)
                                    {
                                        $units_eni_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Regiment->Lieu_ID' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3,5,10) AND r.Placement='$Regiment->Placement'"),0);
                                        $units_allies_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Regiment->Lieu_ID' AND p.Faction='$Faction' AND r.Vehicule_Nbr >0 AND r.Position=5 AND r.Placement='$Regiment->Placement'"),0);
                                        $bonus_init=$units_allies_zone-$units_eni_zone;
                                        //Range
                                        $Vitesse=Get_LandSpeed($Veh->Vitesse,$Veh->mobile,$Lieu->Zone,0,$Veh->Type,0,$Veh->Sol_meuble);
                                        if($Lieu->Flag ==$country)$Vitesse+=10;
                                        if($Regiment->Matos ==10)$Vitesse*=1.1;
                                        elseif($Regiment->Matos ==14)$Vitesse*=1.5;
                                        elseif($Regiment->Matos ==30)$Vitesse/=1.25;
                                        $Range=($Vitesse*100)+($Regiment->Experience*2);
                                        if($Regiment->Position ==2 or $Regiment->Position ==3 or $Regiment->Position ==9 or $Regiment->Position ==10 or $Regiment->Position ==14)$Range/=2;
                                        if($Veh->mobile ==7)$Range*=2;
                                        if($Veh->mobile ==3 and !$Visible and ($Lieu->Zone ==2 or $Lieu->Zone ==3 or $Lieu->Zone ==4 or $Lieu->Zone ==5 or $Lieu->Zone ==7 or $Lieu->Zone ==10)) //Bonus infanterie en terrain difficile
                                        {
                                            $Range_bonus=$Range*2;
                                            $Range_txt="<a href='#' class='popup'><i class='text-danger'>".round($Range_bonus)."m</i><span>En cas d'attaque surprise</span></a>";
                                        }
                                        elseif(($Veh->mobile ==1 or $Veh->mobile ==2 or $Veh->mobile ==6 or $Veh->mobile ==7) and ($Lieu->Zone ==0 or $Lieu->Zone ==8)) //Bonus véhicules en terrain plat
                                        {
                                            $Range_bonus=$Range*2;
                                            $Range_txt="<a href='#' class='popup'><i class='text-danger'>".round($Range_bonus)."m</i><span>En cas d'attaque surprise si l'ennemi ne possède pas d'unités en ligne</span></a>";
                                        }
                                        if($Regiment->Skill ==19 or $Regiment->Skill ==62 or $Regiment->Skill ==102 or $Regiment->Skill ==103)
                                        {
                                            if($Regiment->Skill ==19){
                                                $Range*=1.1;
                                                $Pass*=1.15;
                                            }
                                            elseif($Regiment->Skill ==62){
                                                $Range*=1.15;
                                                $Pass*=1.3;
                                            }
                                            elseif($Regiment->Skill ==102){
                                                $Range*=1.20;
                                                $Pass*=1.45;
                                            }
                                            elseif($Regiment->Skill ==103){
                                                $Range*=1.25;
                                                $Pass*=1.6;
                                            }
                                        }
                                        $Range=round($Range);
                                        //Init
                                        $Init=$Regiment->Experience+(($Veh->Radio*5)+($Veh->Tourelle*5))+$bonus_init;
                                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='conso_mun' value='".$Conso_mun_at."'><input type='hidden' name='pos' value='36'>
                                        <input type='submit' value='Attaque' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'>											
                                        </form></td><td><div class='i-flex'><a href='#' class='popup'><div class='action-jour' title='Compte comme action du jour'></div><span>La reconnaissance compte comme action du jour</span></a></div></td>
                                        <td><img src='images/oil_icon.png' title='".$Conso_tot."L'> + ".$Conso_mun_at."x ".$Arme_Cal."mm</td>
                                        <td><a href='#' class='popup'>".$Range."m<span>Portée de Tir - Allonge de Raid</span></a><br>".$Range_txt."</td>
                                        <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Attaque des unités détectées situées sur la même zone. Compte comme action du jour. L'unité passera en mode combat pour une durée de 24h.<br>Une percée réussie infligeant des dégâts à l'unité attaquée forcera cette dernière à passer en mode combat pour 24h.</span></a><a href='#' class='popup'>".$Init."<span>Modificateur d'initiative et de tactique<br>Ce modificateur est augmenté par la présence d'unités alliées en appui sur la même zone que votre unité, et diminué par la présence d'unités ennemies en ligne, en appui, en embuscade ou en position défensive sur la même zone que votre unité</a></td></tr>";
                                    }
                                    else
                                        $Atk_Options.="<div class='alert alert-danger'>Une attaque nécessite ".$Conso_tot."L de carburant dans le dépôt</div>";
                                }
                                else
                                    $Atk_Options.="<div class='alert alert-danger'>Une attaque nécessite ".$Conso_mun_at." munitions dans le dépôt, ou un ravitaillement aérien ou naval</div>";
                            }
                            elseif(!$Pas_libre and $Veh->Categorie ==5 and $Regiment->Vehicule_Nbr >=10 and ($Regiment->Position ==4 or $Regiment->Position ==0) and $HasHostiles)
                            {
                                //Range
                                $Vitesse=Get_LandSpeed($Veh->Vitesse,$Veh->mobile,$Lieu->Zone,0,$Veh->Type,0,$Veh->Sol_meuble);
                                if($Lieu->Flag ==$country)$Vitesse+=10;
                                if($Regiment->Matos ==10)$Vitesse*=1.1;
                                elseif($Regiment->Matos ==14)$Vitesse*=1.5;
                                elseif($Regiment->Matos ==30)$Vitesse/=1.25;
                                $Range=($Vitesse*100)+($Regiment->Experience*2);
                                if($Regiment->Position ==2 or $Regiment->Position ==3 or $Regiment->Position ==9 or $Regiment->Position ==10 or $Regiment->Position ==14)$Range/=2;
                                if($Veh->mobile ==7)$Range*=2;
                                if($Veh->mobile ==3 and !$Visible and ($Lieu->Zone ==2 or $Lieu->Zone ==3 or $Lieu->Zone ==4 or $Lieu->Zone ==5 or $Lieu->Zone ==7 or $Lieu->Zone ==10)) //Bonus infanterie en terrain difficile
                                {
                                    $Range_bonus=$Range*2;
                                    $Range_txt="<a href='#' class='popup'><i class='text-danger'>".round($Range_bonus)."m</i><span>En cas d'attaque surprise</span></a>";
                                }
                                elseif(($Veh->mobile ==1 or $Veh->mobile ==2 or $Veh->mobile ==6 or $Veh->mobile ==7) and ($Lieu->Zone ==0 or $Lieu->Zone ==8)) //Bonus véhicules en terrain plat
                                {
                                    $Range_bonus=$Range*2;
                                    $Range_txt="<a href='#' class='popup'><i class='text-danger'>".round($Range_bonus)."m</i><span>En cas d'attaque surprise si l'ennemi ne possède pas d'unités en ligne</span></a>";
                                }
                                $Range=round($Range);
                                $inf_eni_routed_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Lieu_ID='$Regiment->Lieu_ID' AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (6,7,8,9) AND r.Placement='$Regiment->Placement' AND c.Categorie=5"),0);
                                if($inf_eni_routed_zone)
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='conso_mun' value='0'><input type='hidden' name='pos' value='39'>
                                    <input type='submit' value='Dispersion' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'>
                                    </form></td><td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td><td>".$Conso_tot."L</td><td><a href='#' class='popup'>".$Range."m<span>Portée de Tir - Allonge de Raid</span></a><br>".$Range_txt."</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tentative de dispersion des unités d'infanterie ennemies désorganisées situées sur la même zone.</span></a></td></tr>";
                                $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='conso_mun' value='0'><input type='hidden' name='pos' value='36'>
                                <input type='submit' value='Attaque' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'>
                                </form></td><td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td><td>".$Conso_tot."L</td><td><a href='#' class='popup'>".$Range."m<span>Portée de Tir - Allonge de Raid</span></a><br>".$Range_txt."</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Assaut d'infanterie contre les unités ennemies situées sur la même zone.</span></a></td></tr>";
                            }
                            elseif($Pas_libre)
                                $Atk_Options.='<div class="alert alert-danger">Attaque impossible actuellement</div>';
                            elseif($Regiment->Position !=4 and $Veh->Categorie !=6 and $Veh->Categorie !=8 and $Veh->Categorie !=9 and $Veh->Categorie !=15)
                                $Atk_Options.='<div class="alert alert-danger">Pour pouvoir attaquer, l\'unité doit être en mouvement</div>';
                            if($Faction_Flag !=$Faction and ($Veh->Categorie ==2 or $Veh->Categorie ==3 or $Veh->Categorie ==5 or $Veh->Categorie ==7) and ($Regiment->Position ==4 or $Regiment->Position ==0) and ($Regiment->Placement ==1 or $Regiment->Placement ==0))
                            {
                                if($Lieu->Recce or !$Lieu->ValeurStrat)
                                {
                                    if($Regiment->Placement ==0 and $Lieu->Garnison)
                                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='pos' value='38'>
                                        <input type='submit' value='Assaut' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                        <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td><td>0</td><td>".$Range."m</td><td>N/A</td></tr>";
                                    elseif($Regiment->Placement ==1)
                                    {
                                        $Esc_Oqp=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit as u,Pays as p WHERE u.Base='$Regiment->Lieu_ID' AND u.Pays=p.ID AND p.Faction<>'$Faction' AND Etat=1 AND Garnison >0"),0);
                                        if($Esc_Oqp)
                                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu->ID."'><input type='hidden' name='pos' value='48'>
                                            <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Assaut des troupes défendant l'aérodrome</span></a><input type='submit' value='Assaut' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                            <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td><td>0</td><td>".$Range."m</td><td>N/A</td></tr>";
                                    }
                                }
                                else
                                    $Atk_Options.="<div class='alert alert-danger'>Une reconnaissance stratégique ou terrestre est une condition préalable à l'assaut de garnison ou d'aérodrome</div>";
                            }
                            elseif(!$Atk_Options and ($Veh->Categorie ==6 or $Veh->Categorie ==9 or $Veh->Categorie ==15))
                                $Atk_Options='<div class="alert alert-warning">Cette unité est exclusivement défensive</div>';
                        }
                        else
                            $txt_help.="<div class='alert alert-danger'><strong>Embouteillage!</strong> Trop d'unités occupent cette zone!<br>Toute attaque ou bombardement est impossible tant que l'embouteillage persiste!</div>";
                        mysqli_close($con);
                    }
                    if($Veh->Detection >10 and !$Canada)
                    {
                        if($Embout <=GetEmboutMax($Lieu->ValeurStrat,$Regiment->Placement))
                        {
                            if($Veh->Carbu_ID and !$Bl_conso)
                                $Bl_conso="<span class='label label-".$Colorc1."' title='Consommation attaque ou reco'>".$Conso_tot."L ".$Octane1."</span>";
                            if($Stock_carbu >=$Conso_tot or $Ravit)
                            {
                                $Atk_Options.="<tr><td><form action='index.php?view=ground_reco1' method='post'>
                                <input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Veh' value='".$Regiment->Vehicule_ID."'><input type='hidden' name='Cible' value='".$Lieu->ID."'><input type='hidden' name='Conso' value='0'>
                                <input type='submit' value='Reco' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>La reconnaissance compte comme action du jour</span></a></div></td>
                                <td>".$Conso_tot."L</td>
                                <td>".$Range."m</td>
                                <td>N/A</td></tr>";
                            }
                            else
                                $Atk_Options.="<p class='lead'>Une reconnaissance nécessite ".$Conso_tot."L de carburant</p>";
                        }
                        else
                            $Atk_Options.="<div class='alert alert-danger'><strong>Embouteillage!</strong> Trop d'unités occupent cette zone!<br>Toute reconnaissance est impossible tant que l'embouteillage persiste!</div>";
                    }
                    if(!$Regiment->Move && $Veh->Type ==98 && $Regiment->Vehicule_Nbr >0)
                    {
                        if($Regiment->Position !=11 and $Regiment->Position !=6 and $Regiment->Position !=8 and $Regiment->Position !=9 and $Regiment->Position !=14)
                        {
                            $Atk_Options.="<tr><td><form action='index.php?view=ground_genie' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                            <input type='submit' value='Génie' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                            <td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                        }
                    }
                }
                //END MAJ
            }
            else{
                $Positions='Déjà effectué';
                $Pos_titre='Action';
            }
        }

        //Compétence Tactique
        if($Regiment->Skill)
        {
            if($Regiment->Vehicule !=424 && $Veh->Type !=1)
            {
                $con1=dbconnecti(1);
                $resultsk=mysqli_query($con1,"SELECT Nom,Rang,Infos FROM Skills_r WHERE ID='$Regiment->Skill'");
                if($resultsk)
                {
                    while($datask=mysqli_fetch_array($resultsk,MYSQLI_ASSOC))
                    {
                        $Skills_Rang=$datask['Rang'];
                        $Skills_Infos="<b>".$datask['Nom']."</b><br>".$datask['Infos'];
                    }
                    mysqli_free_result($resultsk);
                }
                if($Skills_Rang <4 and $Regiment->Experience >199)
                    $next_skill=mysqli_result(mysqli_query($con1,"SELECT ID FROM Skills_r WHERE Prereq1='$Skill'"),0);
                elseif($Skills_Rang <3 and $Regiment->Experience >149)
                    $next_skill=mysqli_result(mysqli_query($con1,"SELECT ID FROM Skills_r WHERE Prereq1='$Skill'"),0);
                elseif($Skills_Rang <2 and $Regiment->Experience >100)
                    $next_skill=mysqli_result(mysqli_query($con1,"SELECT ID FROM Skills_r WHERE Prereq1='$Skill'"),0);
                elseif($Skills_Rang <1 and $Regiment->Experience >49)
                    $next_skill=mysqli_result(mysqli_query($con1,"SELECT ID FROM Skills_r WHERE Prereq1='$Skill'"),0);
                mysqli_close($con1);
                if($next_skill){
                    $con=dbconnecti();
                    $update_skill=mysqli_query($con,"UPDATE Regiment_IA SET Skill='$next_skill' WHERE ID='$Unit'");
                    mysqli_close($con);
                    $Regiment->Skill=$next_skill;
                }
            }
            $Skill_txt="<a href='#' class='popup'><img src='images/skills/skillo".$Regiment->Skill.".png' style='max-width:15%;'><span>".$Skills_Infos."</span></a>";
        }
        elseif($Regiment->Vehicule !=424 && $Veh->Type !=TYPE_TRUCK && $Regiment->Experience >49)
        {
            if($Veh->Type ==37) //Sub
            {
                $Skills_1=array(25,32,35,37,43);
                $Skills_2=array(129,135,144,150,168);
                $Skills_3=array(130,136,145,151,169);
                $Skills_4=array(131,137,146,152,170);
            }
            elseif($Veh->Type ==21) //PA
            {
                $Skills_1=array(25,30,36);
                $Skills_2=array(129,123,147);
                $Skills_3=array(130,124,148);
                $Skills_4=array(131,125,149);
            }
            elseif($Veh->Type ==20 or $Veh->Type ==19 or $Veh->Type ==18) //Cuirassé & Croiseur
            {
                $Skills_1=array(15,22,25,30,31,33,34,35,36,38,41);
                $Skills_2=array(47,65,129,123,132,138,141,144,147,153,162);
                $Skills_3=array(72,108,130,124,133,139,142,145,148,154,163);
                $Skills_4=array(73,109,131,125,134,140,143,146,149,155,164);
            }
            elseif($Veh->Type ==15 or $Veh->Type ==16 or $Veh->Type ==17) //Escorteurs
            {
                $Skills_1=array(25,30,35,36,37,39,40,42);
                $Skills_2=array(129,123,144,147,150,156,159,165);
                $Skills_3=array(130,124,145,148,151,157,160,166);
                $Skills_4=array(131,125,146,149,152,158,161,167);
            }
            elseif($Veh->Type ==14) //Pt navires
            {
                $Skills_1=array(25,35,36);
                $Skills_2=array(129,144,147);
                $Skills_3=array(130,145,148);
                $Skills_4=array(131,146,149);
            }
            elseif($Veh->Categorie ==6) //MG
            {
                $Skills_1=array(3,4,6,7,9,11,13,14,23,25,29);
                $Skills_2=array(48,49,51,52,54,56,58,59,114,129);
                $Skills_3=array(74,76,80,82,86,90,94,96,115,130);
                $Skills_4=array(40,75,77,81,83,87,91,95,97,116,131);
            }
            elseif($Veh->Type ==4) //Canon AT
            {
                $Skills_1=array(3,6,9,11,12,14,25);
                $Skills_2=array(48,51,54,56,57,59,129);
                $Skills_3=array(74,80,86,90,92,96,130);
                $Skills_4=array(40,75,81,87,91,93,97,131);
            }
            elseif($Veh->Type ==6)
            {
                $Skills_1=array(6,8,9,12,15,22,25,28);
                $Skills_2=array(47,51,53,54,57,65,67,129);
                $Skills_3=array(72,80,84,86,92,108,112,130);
                $Skills_4=array(40,73,81,85,87,93,109,113,131);
            }
            elseif($Veh->Type ==8)
            {
                $Skills_1=array(6,8,9,15,20,22,25,28);
                $Skills_2=array(47,51,53,54,57,63,65,67,129);
                $Skills_3=array(72,80,84,86,104,108,112,130);
                $Skills_4=array(40,73,81,85,87,105,109,113,131);
            }
            elseif($Veh->Type ==9)
            {
                $Skills_1=array(1,2,3,5,6,9,10,16,18,19,21,24,25);
                $Skills_2=array(45,46,48,50,51,54,55,60,61,62,64,66,129);
                $Skills_3=array(68,70,74,78,80,86,88,98,100,102,106,110,130);
                $Skills_4=array(40,69,71,75,79,81,87,89,99,101,103,107,111,131);
            }
            elseif($Veh->Type ==12)
            {
                $Skills_1=array(6,9,12,14,25,30);
                $Skills_2=array(51,54,57,59,123,129);
                $Skills_3=array(80,86,92,96,124,130);
                $Skills_4=array(40,81,87,93,97,125,131);
            }
            elseif($Veh->Type ==7 or $Veh->Type ==10 or $Veh->Type ==91)
            {
                $Skills_1=array(1,2,5,6,9,10,16,18,19,21,24,25);
                $Skills_2=array(45,46,50,51,54,55,60,61,62,64,66,129);
                $Skills_3=array(68,70,78,80,86,88,98,100,102,106,110,130);
                $Skills_4=array(40,69,71,79,81,87,89,99,101,103,107,111,131);
            }
            elseif($Veh->Type ==11)
            {
                $Skills_1=array(1,2,5,6,9,10,16,18,19,21,25,30);
                $Skills_2=array(45,46,50,51,54,55,60,61,62,64,123,129);
                $Skills_3=array(68,70,78,80,86,88,98,100,102,106,124,130);
                $Skills_4=array(40,69,71,79,81,87,89,99,101,103,107,125,131);
            }
            elseif($Veh->Type ==2 or $Veh->Type ==3 or $Veh->Type ==5 or $Veh->Type ==93)
            {
                $Skills_1=array(1,2,5,6,9,10,16,18,19,21,25);
                $Skills_2=array(45,46,50,51,54,55,60,61,62,64,129);
                $Skills_3=array(68,70,78,80,86,88,98,100,102,106,130);
                $Skills_4=array(40,69,71,79,81,87,89,99,101,103,107,131);
            }
            else //Inf
            {
                $Skills_1=array(3,4,6,7,9,11,13,14,17,23,25,26,29);
                $Skills_2=array(48,49,51,52,54,56,58,59,114,117,120,126,129);
                $Skills_3=array(74,76,80,82,86,90,94,96,115,118,121,127,130);
                $Skills_4=array(40,75,77,81,83,87,91,95,97,116,119,122,128,131);
            }
            if($Regiment->Experience >199)
                $Skill_p=$Skills_4[mt_rand(0,count($Skills_4)-1)];
            elseif($Regiment->Experience >149)
                $Skill_p=$Skills_3[mt_rand(0,count($Skills_3)-1)];
            elseif($Regiment->Experience >99)
                $Skill_p=$Skills_2[mt_rand(0,count($Skills_2)-1)];
            elseif($Regiment->Experience >49)
                $Skill_p=$Skills_1[mt_rand(0,count($Skills_1)-1)];
            else
                $Skill_p=0;
            if($Skill_p >0)
            {
                $Regiment->Skill=$Skill_p;
                $con=dbconnecti();
                $update_skill=mysqli_query($con,"UPDATE Regiment_IA SET Skill='$Skill_p' WHERE ID='$Unit'");
                mysqli_close($con);
                $Skill_txt="<a href='#' class='popup'><img src='images/skills/skillo".$Skill_p.".png' style='max-width:15%;'><span>".GetData("Skills_r","ID",$Skill_p,"Infos")."</span></a>";
            }
        }
        elseif($Regiment->Vehicule !=424 && $Veh->Type !=TYPE_TRUCK)
            $Upgrade_txt="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Cette unité est éprouvée par les combats. Elle peut être améliorée sur une usine produisant du matériel plus performant.</div>";

        //Equipement
        if($Veh->Type ==93 && $Regiment->Matos ==28){
            $con=dbconnecti();
            $update_skill=mysqli_query($con,"UPDATE Regiment_IA SET Matos=11 WHERE ID='$Unit'");
            mysqli_close($con);
        }
        $next_skill=11;

        if($GHQ) //Menu GHQ
        {
            if($Regiment->Vehicule !=424 && $Veh->Type !=95)
            {
                $ghq_txt="Front actuel <b>".GetFront($Regiment->Front)."</b><div class='row'>";
                if($Lieu->Industrie && $Regiment->Placement ==6 && !$Regiment->Move && $Faction ==$Faction_Flag)
                {
                    if(!$Faction_Usine)$Faction_Usine=GetData("Pays","ID",$Lieu->Flag_Usine,"Faction");
                    if($Faction ==$Faction_Usine)
                    {
                        $ghq_txt.="<div class='col-md-4'><form action='index.php?view=ground_em_ia_create' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                        <input type='hidden' name='Cat' value='".$Veh->Categorie."'><input type='hidden' name='Mode' value='1'><input type='hidden' name='Lieu' value='".$Lieu->ID."'>
                        <input type='submit' value='Améliorer' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form></div>";
                    }
                }
                if($Regiment->NoEM)
                {
                    $Mode_res_GHQ=12;
                    $Mode_res_GHQ_txt='Libérer EM';
                }
                else
                {
                    $Mode_res_GHQ=11;
                    $Mode_res_GHQ_txt='Réservé GHQ';
                }
                $ghq_txt.="<div class='col-md-4'><form action='ghq_reserve.php' method='post'><input type='hidden' name='mode' value='".$Mode_res_GHQ."'><input type='hidden' name='Reg' value='".$Unit."'>
                <a href='#' class='popup'><div class='help_icon'></div><span>Réserver cette unité pour le GHQ</span></a><input type='submit' value='".$Mode_res_GHQ_txt."' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form></div>";
                if(in_array($Lieu->ID,$Transit_cities) && $Veh->Categorie !=4 && !$Regiment->Move)
                    $ghq_txt.="<div class='col-md-4'><form action='index.php?view=ghq_change_front' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                    <input type='hidden' name='Front' value='".$Regiment->Front."'><input type='hidden' name='Transit' value='".$Lieu->ID."'>
                    <input type='submit' value='Changer de front' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form></div>";
                $ghq_txt.='</div>';
            }
        }
        elseif(!$Regiment->Move) //Demander changement de front
        {
            if(in_array($Lieu->ID,$Transit_cities) && $Veh->Categorie !=4)
                $ghq_txt.="<br><form action='index.php?view=ground_change_front' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                <input type='hidden' name='Front' value='".$Regiment->Front."'><input type='hidden' name='Transit' value='".$Lieu->ID."'>
                <input type='submit' value='Demander le changement de front' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form> <span title='Une demande sera envoyée au planificateur stratégique. Le changement de front retirera automatiquement la Cie de sa division actuelle'><div class='i-flex help_icon'></div></span></a>";
        }
        $titre='Commandement des unités IA';
        $mes.=$menu_cat_list;
        //Hangar & Garage
        if($Veh->mobile != MOBILE_RAIL && $Veh->mobile != MOBILE_WATER && $Veh_Type != TYPE_TRUCK && !$Regiment->Move)
        {
            if($Veh->Arme_AT || $Veh->Arme_Art || $Veh->Categorie == CAT_DCA)
            {
                $title_conso_atk='Consommation attaque';
                if($Veh->Categorie == CAT_DCA && $Veh->Arme_AA)
                {
                    //$Lieu->Stock_AA;
                    $Arme_Cal=round(GetData("Armes","ID",$Veh->Arme_AA,"Calibre"));
                    $Var_Stock_AA='Stock_'.$Arme_Cal.'_max';
                    $Stock_Arme=$$Var_Stock_AA;
                    $Var_Stock_City='Stock_'.$Arme_Cal.'_city';
                    $Stock_Arme_City=$$Var_Stock_City;
                    $Conso_Arme=$Veh->Arme_AA_mun*$Regiment->Vehicule_Nbr;
                    $title_conso_atk='Consommation DCA';
                }
                elseif($Veh->Arme_AT)
                {
                    $Stock_Arme=$Stock_AT;
                    $Conso_Arme=$Conso_mun_at;
                }
                elseif($Veh->Arme_Art)
                {
                    $Stock_Arme=$Stock_Art;
                    $Conso_Arme=$Conso_mun_art;
                }
                if(!$Stock_Arme && !$Ravit && $Regiment->Move)
                    $depot_stock_mun='Mise à jour demain';
                elseif(!$Stock_Arme && !$Ravit)
                    $depot_stock_mun='<span class="text-danger">Vide!</span>';
                else
                    $depot_stock_mun=$Stock_Arme;
            }
            if($Lieu->ValeurStrat >=2 && $Lieu->Industrie && $Regiment->Placement == PLACE_USINE && $Faction ==$Faction_Flag)
            {
                if(!$Faction_Usine)
                    $Faction_Usine=GetData("Pays","ID",$Lieu->Flag_Usine,"Faction");
                if($Faction ==$Faction_Usine)
                {
                    if($Regiment->Experience <100 || $GHQ)
                        $Ravit_Options.="<div class='col-md-6'>
                        <form action='index.php?view=ground_em_ia_create' method='post'>
                            <input type='hidden' name='Reg' value='".$Unit."'>
                            <input type='hidden' name='Cat' value='".$Veh->Categorie."'>
                            <input type='hidden' name='Mode' value='1'>
                            <input type='hidden' name='Lieu' value='".$Lieu->ID."'>
                            <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Changer le matériel de l'unité.<br>L'expérience sera ramenée à 50 et une nouvelle compétence tactique de niveau 1 sera attribuée.</span></a>
                            <input type='submit' value='Hangar' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'>
                        </form></div>";
                    $Ravit_Options.="<div class='col-md-6'>
                    <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Doter l'unité d'un équipement amélioré.<br>Cet équipement peut être changé plusieurs fois sur toute usine.</span></a>
                    <a href='#' data-toggle='modal' data-target='#modal-ravit'><span class='btn btn-sm btn-danger'>Garage</span></a></div></div>";
                    /*$Ravit_Options.="<div class='col-md-6'><form action='index.php?view=ground_em_ia_matos' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                    <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Doter l'unité d'un équipement amélioré.<br>Cet équipement peut être changé plusieurs fois sur toute usine.</span></a>
                    <input type='submit' value='Garage' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></div></div>";*/
                }
            }
        }
        elseif($Veh->mobile ==5 && !$Regiment->Move)
        {
            if($Lieu->ValeurStrat >=2 && $Lieu->Port_Ori && $Lieu->Port >0 && $Faction ==$Faction_Flag)
            {
                if(!$Faction_Port)
                    $Faction_Port=GetData("Pays","ID",$Lieu->Flag_Port,"Faction");
                if($Faction ==$Faction_Port)
                {
                    if(($Lieu->ValeurStrat >3 or $Port_ok) and !$Enis_Port_combi and $Regiment->Vehicule_Nbr >0 and ($Veh->Type ==20 or $Veh->Type ==21 or $Veh->Type ==15 or $Veh->Type ==16 or $Veh->Type ==17 or $Veh->Type ==18 or $Veh->Type ==19 or $Veh->Type ==37))
                        $Renforts_txt.='<tr><td>
                                                <form action="index.php?view=ground_em_ia_go" method="post">
                                                    <input type="hidden" name="renf" value="5">
                                                    <input type="hidden" name="Unit" value="'.$Unit.'">
                                                    <input type="hidden" name="Max" value="'.$Max_Veh.'">
                                                    <input class="btn btn-sm btn-warning" type="submit" value="Ravitailler">
                                                </form>
                                            </td>
                                            <td><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></td>
                                            <td><a href="#" class="popup"><div class="i-flex help_icon"></div><span>Permet de récupérer le maximum de jours de mer</span></a></td>
                                       </tr>';
                    if($Port_ok && !$Enis_Port_combi){
                        if($Regiment->Experience <100)
                            $Renforts_txt.="<tr><td><form action='index.php?view=ground_em_ia_create' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                            <input type='hidden' name='Cat' value='".$Veh->Categorie."'><input type='hidden' name='Mode' value='1'><input type='hidden' name='Lieu' value='".$Lieu->ID."'>
                            <input type='submit' value='Hangar' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Changer le matériel de l'unité.<br>L'expérience sera ramenée à 50 et une nouvelle compétence tactique de niveau 1 sera attribuée.</span></a></td></tr>";
                        $Renforts_txt.="<tr><td><a href='#' data-toggle='modal' data-target='#modal-ravit'><span class='btn btn-sm btn-danger'>Garage</span></a></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Doter l'unité d'un équipement amélioré.<br>Cet équipement peut être changé plusieurs fois sur toute usine.</span></a></td></tr>";
                    }
                }
            }
        }
        if($Ravit ==1)$Conso_txt.="<br><a href='#' class='popup'><img src='images/map/air_ravit.png'><span>Ravitaillée par air</span></a>";
        //Begin Help Txt
        if($Lieu->Zone ==0 || $Lieu->Zone ==8)
        {
            if($Veh->mobile ==5)
                $Zone_help_txt="Portée d'attaque maximale 2000m";
            else
            {
                $Zone_help_txt="Portée d'attaque maximale 4000m";
                if($Veh->Categorie ==2 || $Veh->Categorie ==3 || $Veh->mobile ==7)
                    $Zone_help_txt.="<br>Terrain propice à l'attaque surprise pour cette unité";
            }
        }
        elseif($Lieu->Zone ==2 || $Lieu->Zone ==3 || $Lieu->Zone ==4 || $Lieu->Zone ==5 || $Lieu->Zone ==7 || $Lieu->Zone ==10)
        {
            if($Lieu->Zone ==4)
                $Zone_help_txt="Portée d'attaque maximale 1000m<br>";
            else
                $Zone_help_txt="Portée d'attaque maximale 500m<br>";
            if($Veh->mobile ==3)
                $Zone_help_txt.="Terrain propice à l'attaque surprise pour cette unité<br>Terrain propice à l'embuscade";
            elseif($Veh->mobile ==5)
                $Zone_help_txt="Portée d'attaque maximale 2km";
            else
                $Zone_help_txt.="Terrain propice à l'embuscade";
        }
        elseif($Lieu->Zone ==1)
            $Zone_help_txt="Portée d'attaque maximale 1000m";
        elseif($Lieu->Zone ==6)
            $Zone_help_txt="Portée d'attaque maximale 20km";
        else
            $Zone_help_txt="Ce terrain n'offre aucun avantage à cette unité";
        if($Regiment->Position ==1)
        {
            $Pos_help_txt="Bonus tactique offensif réduit de moitié<br>Bonus tactique défensif doublé<br>Camoufle automatiquement l'unité suite à un bombardement d'artillerie";
            if($Veh->Flak)$Pos_help_txt.="<br>Défend les unités de sa faction sur le même lieu face aux attaques aériennes";
        }
        elseif($Regiment->Position ==2)
            $Pos_help_txt="Attaque impossible<br>Vitesse nulle<br>Bonus tactique défensif quadruplé<br>Réduction de dégâts lors des bombardements";
        elseif($Regiment->Position ==3)
        {
            $Pos_help_txt="Portée de bombardement, d'attaque et de raid réduite de moitié<br>Vitesse nulle<br>Bonus tactique défensif doublé";
            if($Veh->Arme_AT)$Pos_help_txt.="<br>Riposte contre les reconnaissances et les attaques de véhicules";
        }
        elseif($Regiment->Position ==4)
            $Pos_help_txt="Vitesse maximale<br>Bonus tactique défensif réduit de moitié";
        elseif($Regiment->Position ==5)
        {
            $Pos_help_txt="Vitesse nulle<br>Bonus tactique défensif réduit de moitié";
            if($Veh->Categorie ==8)$Pos_help_txt.="<br>Contre-batterie sur toutes les zones du lieu";
            if($Veh->Flak)$Pos_help_txt.="<br>Défend les unités de sa faction sur le même lieu face aux attaques aériennes";
        }
        elseif($Regiment->Position ==6)
            $Pos_help_txt="Attaque impossible<br>Bonus tactique défensif réduit de moitié";
        elseif($Regiment->Position ==7)
            $Pos_help_txt="Bonus tactique offensif réduit de moitié<br>Bonus tactique défensif réduit de moitié";
        elseif($Regiment->Position ==8)
            $Pos_help_txt="Bonus tactique offensif réduit de moitié<br>Vitesse nulle<br>Bonus tactique défensif réduit de moitié<br>Vulnérable aux bombardements et aux assauts d'infanterie";
        elseif($Regiment->Position ==9)
            $Pos_help_txt="Bonus tactique offensif réduit de moitié<br>Vitesse nulle<br>Portée de bombardement, d'attaque et de raid réduite de moitié";
        elseif($Regiment->Position ==10)
        {
            $Pos_help_txt="Attaque impossible<br>Bonus tactique défensif doublé<br>Vitesse nulle<br>Portée de bombardement, d'attaque et de raid réduite de moitié";
            if($Veh->Categorie ==5 || $Veh->Categorie ==6 || $Veh->Categorie ==9)$Pos_help_txt.="<br>Chance de protéger les unités alliées contre les attaques terrestres";
            if($Veh->Arme_AT)$Pos_help_txt.="<br>Riposte contre les attaques de véhicules";
        }
        elseif($Regiment->Position ==11)
            $Pos_help_txt="Attaque impossible<br>Bonus tactique défensif réduit de moitié<br>Vitesse nulle";
        elseif($Regiment->Position ==14)
            $Pos_help_txt="Chance de détecter toute unité ennemie pénétrant sur la même zone<br>Attaque impossible<br>Portée de bombardement, d'attaque et de raid réduite de moitié<br>Vitesse réduite de moitié";
        elseif($Regiment->Position ==20)
            $Pos_help_txt="Un navire maximum sera perdu lors d'une attaque";
        elseif($Regiment->Position ==21)
        {
            $Pos_help_txt="Augmente la protections des navires alliés situés dans la même zone face à un torpillage";
            if($Veh->Flak)$Pos_help_txt.="<br>Défend les unités de sa faction sur le même lieu face aux attaques aériennes";
        }
        elseif($Regiment->Position ==22)
            $Pos_help_txt="Un écran de fumée protégera le navire s'il est attaqué";
        elseif($Regiment->Position ==23 && $Veh->Arme_Art)
            $Pos_help_txt="Contre-batterie sur toutes les zones du lieu";
        elseif($Regiment->Position ==24)
            $Pos_help_txt="Augmente la protections des navires alliés situés dans la même zone face à un torpillage";
        elseif($Regiment->Position ==25)
            $Pos_help_txt="Portée de torpillage doublée";
        elseif($Regiment->Position ==26)
            $Pos_help_txt="Attaque impossible<br>Vitesse nulle<br>Bonus tactique défensif réduit de moitié<br>Portée de bombardement, d'attaque et de raid réduite de moitié";
        else
            $Pos_help_txt="Cette position n'offre aucun avantage à cette unité";
        if($Regiment->Vehicule_ID <5000 && $Regiment->Vehicule_ID !=424)
        {
            if(!$Regiment->Move){
                if($Regiment->Placement ==1)
                    $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Air,"Faction");
                elseif($Regiment->Placement ==2)
                    $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Route,"Faction");
                elseif($Regiment->Placement ==3)
                    $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Gare,"Faction");
                elseif($Regiment->Placement ==4)
                    $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Port,"Faction");
                elseif($Regiment->Placement ==5)
                    $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Pont,"Faction");
                elseif($Regiment->Placement ==6)
                    $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Usine,"Faction");
                elseif($Regiment->Placement ==7)
                    $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Radar,"Faction");
                elseif($Regiment->Placement ==11)
                    $Faction_Place=GetData("Pays","ID",$Lieu->Flag_Plage,"Faction");
                else
                    $Faction_Place=$Faction_Flag;
                if($Faction !=$Faction_Place)
                    $Alert_dep.="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Attrition: </strong>Cette zone est revendiquée par l'ennemi. En restant sur cette zone votre unité subira l'attrition et perdra des troupes chaque jour!</div>";
            }
            if(!$Alert_dep){
                if(!$Regiment->Move and $Lieu->NoeudR >0 and $Lieu->NoeudF >10 and $Regiment->Placement !=3)
                    $Alert_dep.="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Déplacement: </strong>Pensez à déplacer l'unité sur la route ou la gare avant tout déplacement, afin de bénéficier du déplacement ferroviaire ou du bonus de noeud routier!</div>";
                elseif(!$Regiment->Move and $Lieu->NoeudR >0 and $Regiment->Placement !=2 and $Regiment->Placement !=3)
                    $Alert_dep.="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Déplacement: </strong>Pensez à déplacer l'unité sur la route avant tout déplacement, afin de bénéficier du bonus de noeud routier!</div>";
                if($Pont_block)
                    $Alert_dep.="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <strong>Déplacement: </strong>La destruction du pont vous empêche de rejoindre l'autre rive!</div>";
            }
        }
        //End Help Txt
        if($Regiment->Experience >249)
            $Exp_txt="<span class='label label-success'>".$Regiment->Experience."XP</span>";
        elseif($Regiment->Experience >49)
            $Exp_txt="<span class='label label-primary'>".$Regiment->Experience."XP</span>";
        elseif($Regiment->Experience >1)
            $Exp_txt="<span class='label label-warning'>".$Regiment->Experience."XP</span>";
        else
            $Exp_txt="<span class='label label-danger'>".$Regiment->Experience."XP</span>";
        if($Regiment->Matos)$Skill_txt.="<a href='#' class='popup'><img src='images/skills/skille".$Regiment->Matos.".png' style='max-width:15%;'><span>".$Reg_matos[$Regiment->Matos]."</span></a>";
        if(!$Retraite_Nom)$Retraite_Nom=GetData("Lieu","ID",$Retraite,"Nom");
        if($Regiment->Bataillon)$Bat_Nbr=$Regiment->Bataillon.'e Bat';
        if($Regiment->Position==0 || $Regiment->Position==4)$txt_intro_help="<div class='alert alert-danger'><b>Cette unité est dans une position vulnérable</b>. Veillez à mettre l'unité en position adéquate après tout déplacement.</div>";
        //Output
        //--Divisions--
        if(($Admin || $Ordres_Cdt
                || ($Ordres_Mer && !$GHQ && $Veh->mobile ==5)
                || ($Ordres_Adjoint && $Veh->mobile!=5)
            ) && $Veh->Type !=95 && $Veh->Type !=1 && $Veh->mobile!=4 && $Regiment->Vehicule_ID !=5392 && $Regiment->Vehicule_ID !=5001 && $Regiment->Vehicule_ID !=5124){
            //Division & Armées
            if($Veh->mobile ==5)
                $Mar_bool='1';
            else
                $Mar_bool='0';
            $con=dbconnecti();
            $result9=mysqli_query($con,"SELECT d.ID,d.Nom,d.Base,l.Nom AS BaseNom,a.Nom AS Armee,o.Nom AS Cdt FROM Division d
            LEFT JOIN Lieu l ON d.Base=l.ID
            LEFT JOIN Armee a ON d.Armee=a.ID
            LEFT JOIN Officier_em o ON a.Cdt=o.ID
            WHERE d.Pays='$country' AND d.Front='$Regiment->Front' AND d.Active=1 AND d.Maritime=".$Mar_bool." ORDER BY a.Cdt DESC, d.Armee DESC, l.Nom ASC");
            mysqli_close($con);
            if($result9)
            {
                while($data9=mysqli_fetch_array($result9,MYSQLI_ASSOC))
                {
                    $Divisions_txt.='<tr><td><a href="ground_em_ia_div.php?id='.$Unit.'&div='.$data9['ID'].'">'.Afficher_Image('images/div/div'.$data9['ID'].'.png','images/'.$country.'div.png',$data9['Nom'],0).'</a><br>'.$data9['Nom'].'</td><td>'.$data9['BaseNom'].'</td><td>'.$data9['Armee'].'</td><td>'.$data9['Cdt'].'</td></tr>';
                }
                mysqli_free_result($result9);
            }
        }
        elseif($Ordres_Armee){
            $con=dbconnecti();
            $result9=mysqli_query($con,"SELECT d.ID,d.Nom,d.Base,l.Nom AS BaseNom,a.Nom AS Armee,o.Nom AS Cdt FROM Division d
            LEFT JOIN Lieu l ON d.Base=l.ID
            LEFT JOIN Armee a ON d.Armee=a.ID
            LEFT JOIN Officier_em o ON a.Cdt=o.ID
            WHERE d.Pays='$country' AND d.Armee='$Armee' AND d.Active=1 ORDER BY l.Nom ASC");
            mysqli_close($con);
            if($result9)
            {
                while($data9=mysqli_fetch_array($result9,MYSQLI_ASSOC))
                {
                    $Divisions_txt.='<tr><td><a href="ground_em_ia_div.php?id='.$Unit.'&div='.$data9['ID'].'">'.Afficher_Image('images/div/div'.$data9['ID'].'.png','images/'.$country.'div.png',$data9['Nom'],0).'</a><br>'.$data9['Nom'].'</td><td>'.$data9['BaseNom'].'</td><td>'.$data9['Armee'].'</td><td>'.$data9['Cdt'].'</td></tr>';
                }
                mysqli_free_result($result9);
            }
        }
        if($Divisions_txt){
            $Divisions_pre='<a href="#" data-toggle="modal" data-target="#modal-div">';
            $Divisions_end='</a>';
            $Divisions_txt='<table class="table table-striped"><thead><tr><th>Division</th><th>Base</th><th>Armee</th><th>Commandant</th></tr></thead><tbody class="text-black">'.$Divisions_txt.'</tbody></table>';
            $Divisions_modal='<div class="modal fade" id="modal-div" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Gestion de la division
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </h2>
                                        <a class="btn btn-default" href="ground_em_ia_div.php?id='.$Unit.'&div=9999">Affecter à la réserve</a>
                                    </div>
                                    <div class="modal-body">
                                    '.$Divisions_txt.'
                                    </div>
                                </div>
                            </div>
                        </div>';
        }
        $Divisions=$Divisions_pre.Afficher_Image('images/div/div'.$Regiment->Division.'.png','images/'.$country.'div.png','',0).$Divisions_end.$Divisions_modal;
        //--End Divisions--
        $Base_txt='<br><a href="#" class="popup">'.$Retraite_Nom.'<span>Lieu où cette unité peut se ravitailler.<br>La base arrière est définie par la <b>division</b> à laquelle appartient l\'unité.<br>Si l\'unité ne fait pas partie d\'une division, la base arrière est celle du front.</span></a>';
        if($Regiment->Placement ==5 && !$Lieu->Pont)$Etat_Pont_txt='<br><div class="alert alert-danger">Le pont est détruit !</div>';
        //Menu GHQ
        if($ghq_txt){
            $ghq_txt='<div class="col-md-6 col-sm-12"><div class="panel panel-war" style="margin-top:10px; width: 300px;">
                            <div class="panel-heading">Planificateur Stratégique</div>
                            <div class="panel-body">'.$ghq_txt.'</div>
                        </div></div>';
        }
        //Objectif unité
        if($lieux_obj){
            if(!$Regiment->objectif)
                $objectif_nom='Aucun';
            else
                $objectif_nom=GetData("Lieu","ID",$Regiment->objectif,"Nom");
            $Mission_txt='<div class="panel panel-war" style="margin-top:10px; width: 300px;">
                            <div class="panel-heading">Objectif</div>
                            <div class="panel-body">
                                <h4><a href="#" class="popup"><b class="label label-primary">'.$objectif_nom.'</b><span>Destination à atteindre par cette unité</span></a></h4>
                                <form action="ground_em_ia_obj.php"><input type="hidden" name="id" value="'.$Unit.'">
                                    <select name="obj" class="form-control" style="width: 200px; display:inline;">
                                        <option value="">Aucun</option>'.$lieux_obj.'
                                    </select>
                                    <input type="submit" class="btn btn-sm btn-warning" value="Changer"></form>
                            </div>
                        </div>';
        }
        //Unit Commands
        if($Veh->mobile ==5){
            $Ravit_txt=$Autonomie_txt;
            if($Renforts_txt){
                $Ravit_txt.='<table class="table table-striped table-condensed">
                                <thead><tr><th>#</th><th>Coût</th><th>Aide</th></tr></thead>
                                '.$Renforts_txt.'
                            </table>';
            }
        }
        elseif($Regiment->Vehicule_Nbr <=0){
            $Ravit_txt='<div class="alert alert-danger">Unité en déroute</div>';
            if($Renforts_txt){
                $Ravit_txt.='<table class="table table-striped table-condensed">
                            <thead><tr>
                                <th>#</th>
                                <th>Stock</th>
                                <th>Conso</th>
                            </tr></thead>
                            '.$Renforts_txt.'
                        </table>';
            }
            $Upgrade_txt='<div class="alert alert-danger">Unité en déroute</div>';
        }
        else{
            if(!$Arme_Cal){
                $arme_ravit_txt='<tr><td colspan="3" class="text-success text-center">Ne nécessite pas de ravitaillement en munitions</td></tr>';
            }
            else{
                if($Stock_Arme_City) {
                    $Stock_mun_txt = Output::popup($depot_stock_mun, GetData('Lieu', 'ID', $Stock_Arme_City, 'Nom'));
                } else {
                    $Stock_mun_txt = $depot_stock_mun;
                }
                $arme_ravit_txt='<tr>
                                <td>'.$Arme_Cal.'mm</td>
                                <td>'.$Stock_mun_txt.'</td>
                                <td>'.$Conso_Arme.'</td>
                            </tr>';
            }
            if(!$Octane1){
                $carbu_ravit_txt='<tr><td colspan="3" class="text-success text-center">Ne nécessite pas de ravitaillement en carburant</td></tr>';
            }
            else{
                if($Stock_carbu_city) {
                    $Stock_carbu_txt = Output::popup($Stock_carbu, GetData('Lieu', 'ID', $Stock_carbu_city, 'Nom'));
                } else {
                    $Stock_carbu_txt = $Stock_carbu;
                }
                $carbu_ravit_txt='<tr>
                                <td>'.$Octane1.'</td>
                                <td>'.$Stock_carbu_txt.'</td>
                                <td>'.$Conso_move.'</td>
                            </tr>';
            }
            $Ravit_txt='<table class="table table-striped table-condensed">
                            <thead><tr>
                                <th>#</th>
                                <th>Stock</th>
                                <th>Conso</th>
                            </tr></thead>
                            '.$carbu_ravit_txt.$arme_ravit_txt.$Renforts_txt.'
                        </table>';
            if($Regiment->Transit_Veh !=5000)$ravit_title_extra=' ('.$Auto_Log.'km)';
        }
        if($Atk_Options){
            $Atk_txt=' <table class="table table-striped table-condensed">
                            <thead><tr>
                                <th>Action</th>
                                <th>Coût</th>
                                <th>Conso</th>
                                <th>Dist</th>
                                <th>Aide</th>
                            </tr></thead>
                            '.$Atk_Options.'
                         </table>';
        }
        else{
            $Atk_txt=$Upgrade_txt;
        }
        if($Regiment->Vehicule_Nbr <=($Max_Veh/10))
            $colorNbr = 'danger';
        elseif($Regiment->Vehicule_Nbr <=($Max_Veh/50))
            $colorNbr = 'warning';
        elseif($Regiment->Vehicule_Nbr <$Max_Veh)
            $colorNbr = 'primary';
        else
            $colorNbr = 'success';
        if($Admin && !$Regiment->Move){
            dbconnect();
            $reslieux=$dbh->query("SELECT ID,Nom,Flag FROM Lieu ORDER BY Nom");
            while($lieux_admin=$reslieux->fetchObject()){
                $lieux_admin_output.='<option value="'.$lieux_admin->ID.'">'.$lieux_admin->Nom.'</option>';
            }
            if($lieux_admin_output){
                $admin_lieux_txt='<form action="admin/admin_cie_move.php" method="post"><select name="dest" id="dest" style="width:200px">'.$lieux_admin_output.'</select>
                    <input type="hidden" name="reg" value="'.$Unit.'">
                    <input type="submit" value="Déplacer" class="btn btn-sm btn-danger">
                    </form>';
            }
            if($Regiment->Vehicule_Nbr <$Max_Veh){
                $admin_lieux_txt.='<form action="admin/admin_cie_full.php" method="post"><input type="hidden" name="reg" value="'.$Unit.'"><input type="hidden" name="Max" value="'.$Max_Veh.'"><input type="submit" value="Heal" class="btn btn-sm btn-danger"></form>';
            }
            $Admin_panel='<div class="panel panel-war text-center"><div class="panel-heading">Admin</div><div class="panel-body">'.$admin_lieux_txt.'</div></div>';
        }
        $mes.=$Alert.$Admin_panel.$matos_modal.'
          <div class="panel panel-war text-center">
            <div class="panel-heading"><div class="row"><div class="col-sm-4">'.$Lieu->Nom.'<br><a href="#" class="popup"><img src="images/zone'.$Lieu->Zone.'.jpg"><span>'.$Zone_help_txt.'</span></a></div><div class="col-sm-4">'.$Veh->Nom.'<br><div class="badge">'.$Regiment->ID.'e</div></div><div class="col-sm-4">'.$Divisions.'</div></div></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        '.$output_dem.'
                        <div class="panel panel-war" style="min-width:350px;">
                            <div class="panel-heading">Actions</div>
                            <div class="panel-body">
                                '.$Atk_txt.'
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        '.GetVehiculeIcon($Regiment->Vehicule_ID,$country,0,0,$Regiment->Front).'
                        <div class="flex-center" style="min-height:2em;"><span class="label label-'.$colorNbr.'">'.$Regiment->Vehicule_Nbr.'/'.$Max_Veh.'</span></div><div class="flex-center" style="min-height:2em;">'.$Exp_txt.'</div>'.$Skill_txt.$Cur_HP.$barges_txt.$Conso_txt.'
                        <div class="flex-center mt-2" style="min-height:2em;"><a class="btn btn-sm btn-primary" href="index.php?view=ground_ia_histo&type=1&id='.$Unit.'">Historique</a></div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="panel panel-war">
                            <div class="panel-heading">Ravitaillement'.$ravit_title_extra.'</div>
                            <div class="panel-body">
                                '.$Ravit_txt.$Ravit_Options.$Carte_Log.'
                            </div>
                            <div class="panel-footer"><span class="badge">Base Arrière</span>'.$Base_txt.'</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="panel panel-war">
                            <div class="panel-heading">Zone</div>
                            <div class="panel-body">
                                <a href="#" class="popup"><b class="badge">'.GetPlace($Regiment->Placement).'</b><span>'.$Placement_help.'</span></a>'.$Etat_Pont_txt.'<br>'.$Placements.'
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="panel panel-war">
                            <div class="panel-heading">'.$Pos_titre.'</div>
                            <div class="panel-body">
                                <a href="#" class="popup"><b class="badge">'.$Pos_ori.'</b><span>'.$Pos_help_txt.'</span></a><br>'.$Positions.'
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>';
        $mes.=$Alert_dep.$flr_info."
        <div class='row'><div class='col-lg-12 col-md-12'>".$Decharger.$depot_info.$units_print.$txt_help."</div></div>";
        if($choix)
        {
            $mes.="<h2>Destinations</h2>
            <div class='row'>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Nord Ouest</div>
                <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$NO_txt."</table></div></div></div></div>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Nord</div>
                <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$Nord_txt."</table></div></div></div></div>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Nord Est</div>
                <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$NE_txt."</table></div></div></div></div>
            </div>
            <div class='row'>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Ouest</div>
                <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$Ouest_txt."</table></div></div></div></div>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='alert alert-warning text-center'><a href='#' class='popup'>Distance maximale : ".$Dist_max_ori."km ".$train_txt."<span><ul><li>Un seul déplacement par jour, peu importe la distance parcourue</li><li>Le déplacement compte comme action du jour</li><li>Distance doublée de noeud routier à noeud routier contrôlés par votre faction</li></span></ul></a></div>
                <div class='flex-center'>".$Carte_Bouton."</div><div class='flex-center mt-2'>".$Centre_txt."</div><div class='flex-center' style='min-height: 160px;'>".GetVehiculeIcon($Regiment->Vehicule_ID,$country,0,0,$Regiment->Front)."</div><span class='badge flex-center'>".$Lieu->Nom."</span></div>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Est</div>
                <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$Est_txt."</table></div></div></div></div>
            </div>
            <div class='row'>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Sud Ouest</div>
                <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$SO_txt."</table></div></div></div></div>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Sud</div>
                <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$Sud_txt."</table></div></div></div></div>
                <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Sud Est</div>
                <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$SE_txt."</table></div></div></div></div>
            </div>
            <div class='row'><div class='col-md-6 col-sm-12'>".$Mission_txt."</div>".$ghq_txt."</div>";
            $mes.=$lieux_modal;
        }
        elseif($output_dest)
            $mes.=$output_dest;
        if($GHQ)
            $mes.="<hr><form action='index.php?view=ground_em_ia_list_95' method='post'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
        elseif($OfficierEMID >0)
            $mes.="<hr><form action='index.php?view=ground_em_ia_list' method='post'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
        else
            $mes.="<hr><form action='index.php?view=ground_div' method='post'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
        $mes.=$txt_help_dist;
        include_once './default.php';
    }
    else {
        echo Output::ShowAdvert('Vous n\'êtes pas autorisé à commander cette unité!', 'danger');
    }
}
else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';