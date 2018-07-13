<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$cat=Insec($_POST['cat']);
$engage1=Insec($_POST['date1']);
$engage2=Insec($_POST['date2']);
$alt_form=Insec($_POST['alt']);
$country_form=Insec($_POST['country']);
if(is_numeric($cat) and $engage1 and $engage2)
{
    include_once('./jfv_air_inc.php');
    include_once('./jfv_txt.inc.php');
    include_once('./jfv_combat.inc.php');
    include_once('./jfv_avions.inc.php');

    function GetSpeeds($alt, $alt_ref, $plafond, $VitesseH, $VitesseB){
        if($plafond >= $alt){
            if($alt > $alt_ref)
                $Vit = $VitesseH+((($VitesseH-$VitesseB)/$alt_ref)*($alt_ref-$alt));
            elseif($alt <=$alt_ref)
                $Vit = $VitesseB+((($VitesseH-$VitesseB)/$alt_ref)*$alt);
        }
        else{
            $Vit = 0;
        }
        return $Vit;
    }

    function GetPuiss($puissance, $compressor, $alt, $alt_ref){
        if($compressor ==2)
        {
            if($alt <$alt_ref)
                $Puiss=floor($puissance/(1+(($alt_ref-$alt)/10000)));
        }
        elseif($compressor ==3)
        {
            if($alt >$alt_ref)
                $Puiss=floor($puissance/(1+(($alt-$alt_ref)/10000)));
        }
        elseif($compressor ==1)
        {
            if($alt >=$alt_ref)
                $Puiss=floor($puissance/(1+(($alt-$alt_ref)/10000)));
            elseif($alt <$alt_ref)
                $Puiss=floor($puissance/(1+(($alt_ref-$alt)/20000)));
        }
        else
        {
            if($alt >=$alt_ref)
                $Puiss=floor($puissance/(1+(($alt-$alt_ref)/5000)));
            elseif($alt <$alt_ref)
                $Puiss=floor($puissance/(1+(($alt_ref-$alt)/10000)));
        }
        return $Puiss;
    }

    if(!$country_form){
        $country_form='%';
    }

    dbconnect();
    $result = $dbh->prepare("SELECT a.*,DATE_FORMAT(a.`Engagement`,'%d-%m-%Y') AS Engage FROM Avion AS a 
    WHERE `Type`=:cat AND Engagement BETWEEN :engage1 AND :engage2 AND Plafond >=:alt AND Pays LIKE :country ORDER BY Rating DESC, Engagement DESC");
    $result->bindParam(':country', $country_form, 1);
    $result->bindParam(':cat', $cat, 1);
    $result->bindParam(':engage1', $engage1, 2);
    $result->bindParam(':engage2', $engage2, 2);
    $result->bindParam(':alt', $alt_form, 1);
    $result->execute();

    while($data = $result->fetchObject()){
        $id_avion = $data->ID;
        $type_avion = $data->Type;
        $pays_avion = $data->Pays;
        $card_head = '<img src="images/'.$pays_avion.'20.gif" alt=""> '.$data->Nom;
        $engagement = $data->Engage;
        $equipage = $data->Equipage;
        $alt_ref = $data->Alt_ref;
        $puissance = $data->Puissance;
        $masse = $data->Masse;
        $autonomie = $data->Autonomie;
        $plafond = $data->Plafond;
        $vitesse_h = $data->VitesseH;
        $vitesse_b = $data->VitesseB;
        $vitesse_a = $data->VitesseA;
        $vitesse_p = $data->VitesseP;
        $man_h = $data->ManoeuvreH;
        $man_b = $data->ManoeuvreB;
        $mani = $data->Maniabilite;
        $stab = $data->Stabilite;
        $robustesse = $data->Robustesse;
        $blindage = $data->Blindage;
        $cellule = $data->Cellule;
        $flaps = $data->Volets;
        $train = $data->Train;
        $voilure = $data->Voilure;
        $verriere = $data->Verriere;
        $radio = $data->Radio;
        $radar = $data->Radar;
        $nav = $data->Navigation;
        $reservoir = $data->Reservoir;
        $arme1 = $data->ArmePrincipale;
        $arme1_nbr = $data->Arme1_Nbr;
        $arme2 = $data->ArmeSecondaire;
        $arme2_nbr = $data->Arme2_Nbr;
        $arme3 = $data->ArmeArriere;
        $arme3_nbr = $data->Arme3_Nbr;
        $arme4 = $data->ArmeSabord;
        $arme4_nbr = $data->Arme4_Nbr;
        $arme5 = $data->TourelleSup;
        $arme5_nbr = $data->Arme5_Nbr;
        $arme6 = $data->TourelleVentre;
        $arme6_nbr = $data->Arme6_Nbr;
        $arme1_mun = $data->Arme1_Mun;
        $arme2_mun = $data->Arme2_Mun;
        $engine = $data->Engine;
        $engine_nbr = $data->Engine_Nbr;
        $bombe = $data->Bombe;
        $bombe_nbr = $data->Bombe_Nbr;
        //Weapons
        if($arme1)
        {
            $resultw = $dbh->prepare("SELECT * FROM Armes WHERE ID=:arme");
            $resultw->bindParam(':arme', $arme1, 1);
            $resultw->execute();
            if($resultw)
            {
                while($dataw1 = $resultw->fetchObject())
                {
                    $Arme1_nom = $dataw1->Nom;
                    $Arme1_cal = substr($dataw1->Calibre,0,3);
                    $Arme1_deg = $dataw1->Degats;
                    $Arme1_mult = $dataw1->Multi;
                    $Arme1_muns = $dataw1->Munitions;
                    $Arme1_fiab = 100-$dataw1->Enrayage;
                    $Arme1_perf = $dataw1->Perf;
                    $Arme1_range = $dataw1->Portee;
                }
            }
            $Degats_chass_1=$Arme1_deg*$Arme1_mult*$arme1_nbr;
            $Degats_tot=$Degats_chass_1;
            if($arme1_mun)$Arme1_muns=$arme1_mun;
        }
        if($arme2)
        {
            if($arme2 !=$arme1 or $arme1_nbr !=$arme2_nbr)
            {
                $resultw->bindParam(':arme', $arme2, 1);
                $resultw->execute();
                if($resultw)
                {
                    while($dataw2 = $resultw->fetchObject())
                    {
                        $Arme2_nom = $dataw2->Nom;
                        $Arme2_cal = substr($dataw2->Calibre,0,3);
                        $Arme2_deg = $dataw2->Degats;
                        $Arme2_mult = $dataw2->Multi;
                        $Arme2_muns = $dataw2->Munitions;
                        $Arme2_fiab = 100-$dataw2->Enrayage;
                        $Arme2_perf = $dataw2->Perf;
                        $Arme2_range = $dataw2->Portee;
                    }
                }
                $Degats_chass_2=$Arme2_deg*$Arme2_mult*$arme2_nbr;
                $Degats_tot+=$Degats_chass_2;
            }
            else
            {
                $Arme2_nom=$Arme1_nom;
                $Arme2_cal=$Arme1_cal;
                $Degats_tot*=2;
            }
            if($arme2_mun)$Arme2_muns=$arme2_mun;
        }
        if($arme3)
        {
            if($arme3 !=$arme1 or $arme1_nbr !=$arme3_nbr)
            {
                $resultw->bindParam(':arme', $arme3, 1);
                $resultw->execute();
                if($resultw)
                {
                    while($dataw3 = $resultw->fetchObject())
                    {
                        $Arme3_nom = $dataw3->Nom;
                        $Arme3_cal = substr($dataw3->Calibre,0,3);
                        $Arme3_deg = $dataw3->Degats;
                        $Arme3_mult = $dataw3->Multi;
                        $Arme3_muns = $dataw3->Munitions;
                        $Arme3_fiab = 100-$dataw3->Enrayage;
                        $Arme3_perf = $dataw3->Perf;
                        $Arme3_range = $dataw3->Portee;
                    }
                }
                $Degats_3=$Arme3_deg*$Arme3_mult*$arme3_nbr;
            }
            else
            {
                $Arme3_nom=$Arme1_nom;
                $Arme3_cal=$Arme1_cal;
                $Degats_3=$Degats_chass_1;
            }
        }
        if($arme4)
        {
            if($arme3 !=$arme4 or $arme4_nbr !=$arme3_nbr)
            {
                $resultw->bindParam(':arme', $arme4, 1);
                $resultw->execute();
                if($resultw)
                {
                    while($dataw4 = $resultw->fetchObject())
                    {
                        $Arme4_nom = $dataw4->Nom;
                        $Arme4_cal = substr($dataw4->Calibre,0,3);
                        $Arme4_deg = $dataw4->Degats;
                        $Arme4_mult = $dataw4->Multi;
                        $Arme4_muns = $dataw4->Munitions;
                        $Arme4_fiab = 100-$dataw4->Enrayage;
                        $Arme4_perf = $dataw4->Perf;
                        $Arme4_range = $dataw4->Portee;
                    }
                }
                $Degats_4=$Arme4_deg*$Arme4_mult*$arme4_nbr;
            }
            else
            {
                $Arme4_nom=$Arme3_nom;
                $Arme4_cal=$Arme3_cal;
                $Degats_4=$Degats_3;
            }
        }
        if($arme6)
        {
            if($arme3 !=$arme6 or $arme6_nbr !=$arme3_nbr)
            {
                $resultw->bindParam(':arme', $arme6, 1);
                $resultw->execute();
                if($resultw)
                {
                    while($dataw6 = $resultw->fetchObject())
                    {
                        $Arme6_nom = $dataw6->Nom;
                        $Arme6_cal = substr($dataw6->Calibre,0,3);
                        $Arme6_deg = $dataw6->Degats;
                        $Arme6_mult = $dataw6->Multi;
                        $Arme6_muns = $dataw6->Munitions;
                        $Arme6_fiab = 100-$dataw6->Enrayage;
                        $Arme6_perf = $dataw6->Perf;
                        $Arme6_range = $dataw6->Portee;
                    }
                }
                $Degats_6=$Arme6_deg*$Arme6_mult*$arme6_nbr;
            }
            else
            {
                $Arme6_nom=$Arme3_nom;
                $Arme6_cal=$Arme3_cal;
                $Degats_6=$Degats_3;
            }
        }
        if($arme5)
        {
            if($arme3 !=$arme5 or $arme5_nbr !=$arme3_nbr)
            {
                $resultw->bindParam(':arme', $arme5, 1);
                $resultw->execute();
                if($resultw)
                {
                    while($dataw5 = $resultw->fetchObject())
                    {
                        $Arme5_nom = $dataw5->Nom;
                        $Arme5_cal = substr($dataw5->Calibre,0,3);
                        $Arme5_deg = $dataw5->Degats;
                        $Arme5_mult = $dataw5->Multi;
                        $Arme5_muns = $dataw5->Munitions;
                        $Arme5_fiab = 100-$dataw5->Enrayage;
                        $Arme5_perf = $dataw5->Perf;
                        $Arme5_range = $dataw5->Portee;
                    }
                }
                $Degats_5=$Arme5_deg*$Arme5_mult*$arme5_nbr;
            }
            else
            {
                $Arme5_nom=$Arme3_nom;
                $Arme5_cal=$Arme3_cal;
                $Degats_5=$Degats_3;
            }
        }
        if($engine)
        {
            $resulte = $dbh->prepare("SELECT `Type`,Nom,Fiabilite,Compresseur,Injection,Carburant FROM gnmh_aubedesaiglesnet1.Moteur WHERE ID=:moteur");
            $resulte->bindParam(':moteur', $engine, 1);
            $resulte->execute();
            if($resulte){
                while($datae = $resulte->fetchObject()){
                    $engine_type = $datae->Type;
                    $engine_name = $datae->Nom;
                    $compresseur = $datae->Compresseur;
                    $injection = $datae->Injection;
                    $fiabilite = $datae->Fiabilite;
                    $carbu = $datae->Carburant;
                }
            }
        }
        $Degats_chass_1_p = $Degats_chass_1/50;
        if($arme2_nbr){
            $Degats_chass_2_p = ($Degats_chass_2/50);
        }
        if($arme3_nbr){
            $Degats_3_p = ($Degats_3/50);
        }
        if($arme4_nbr){
            $Degats_4_p = ($Degats_4/50);
        }
        if($arme5_nbr){
            $Degats_5_p = ($Degats_5/50);
        }
        if($arme6_nbr){
            $Degats_6_p = ($Degats_6/50);
        }

        //Labels
        if($engine_type ==1)
            $engine_type="En ligne";
        elseif($engine_type ==2)
            $engine_type="A réaction";
        elseif($engine_type ==3)
            $engine_type="Fusée";
        else
            $engine_type="En étoile";
        if($compresseur ==3)
            $compresseur="Basse altitude";
        elseif($compresseur ==2)
            $compresseur="Haute altitude";
        elseif($compresseur ==1)
            $compresseur="Simple";
        else
            $compresseur="Pas de compresseur";
        if($injection)
            $injection="Injection";
        else
            $injection="Carburateur";
        switch($cellule)
        {
            case 0:case 1:case 2:
            $Cellule_txt="Monocoque";
            break;
            case 6:case 7:case 8:
            $Cellule_txt="Entoilée";
            break;
            case 9:case 10:case 11:
            $Cellule_txt="Mixte";
            break;
        }
        switch($train)
        {
            case 0:
                $Train_txt="Escamotable manuel";
                break;
            case 1:
                $Train_txt="Escamotable hydraulique";
                break;
            case 2:
                $Train_txt="Escamotable renforcé";
                break;
            case 7:
                $Train_txt="Fixe";
                break;
            case 8:
                $Train_txt="Fixe renforcé";
                break;
            case 9:
                $Train_txt="Fixe caréné";
                break;
            case 13:
                $Train_txt="Coque";
                break;
            case 16:
                $Train_txt="Flotteurs";
                break;
        }
        switch($voilure)
        {
            case 0:case 1:case 2:case 3:case 4:
            $Voilure_txt = "Cantilever";
            break;
            case 5:case 6:case 7:case 8:
            $Voilure_txt = "Haubanée";
            break;
        }
        switch($verriere)
        {
            case 0:
                $Verriere_txt = "A montants";
                break;
            case 1:
                $Verriere_txt = "Bombée";
                break;
            case 2:
                $Verriere_txt = "Améliorée";
                break;
            case 3:
                $Verriere_txt = "Goutte d'eau";
                break;
        }
        switch($radio)
        {
            case 0:
                $Radio_txt="Basique";
                break;
            case 1:
                $Radio_txt="Améliorée";
                break;
            case 2:
                $Radio_txt="Longue portée";
                break;
            case 3:
                $Radio_txt="Contre-mesures";
                break;
            default:
                $Radio_txt="Inconnue";
                break;
        }
        switch($radar)
        {
            case 0:
                $Radar_txt="Aucun";
                break;
            case 10:
                $Radar_txt="Radar décimétrique primitif";
                break;
            case 20:
                $Radar_txt="Radar décimétrique amélioré";
                break;
            case 30:
                $Radar_txt="Radar décimétrique évolué";
                break;
            case 40:
                $Radar_txt="Radar centimétrique";
                break;
            case 50:
                $Radar_txt="Radar centimétrique amélioré";
                break;
            case 60:
                $Radar_txt="Radar centimétrique évolué";
                break;
            default:
                $Radar_txt="Radar inconnu";
                break;
        }
        switch($nav)
        {
            case 0:
                $Navi_txt="Basique";
                break;
            case 1:
                $Navi_txt="Améliorée";
                break;
            case 2:
                $Navi_txt="A la pointe";
                break;
            case 3:
                $Navi_txt="Gyroscopique";
                break;
            default:
                $Navi_txt="Inconnue";
                break;
        }
        switch($reservoir)
        {
            case 0:
                $Reservoir_txt="Standard";
                break;
            case 1:
                $Reservoir_txt="Auto-obturant";
                break;
            case 2:
                $Reservoir_txt="Grande capacité";
                break;
            case 3:
                $Reservoir_txt="Très grande capacité";
                break;
        }

        //Stuff
        $Array_Mod=GetAmeliorations($id_avion);
        $Bombe50_nbr=$Array_Mod[12];
        $Bombe125_nbr=$Array_Mod[13];
        $Bombe250_nbr=$Array_Mod[14];
        $Bombe500_nbr=$Array_Mod[15];
        $Bombe1000_nbr=$Array_Mod[32];
        $Bombe2000_nbr=$Array_Mod[33];
        $Camera_low=$Array_Mod[16];
        $Camera_high=$Array_Mod[17];
        $Baby=$Array_Mod[18];
        $Radar_On=$Array_Mod[19];
        $Torpilles=$Array_Mod[20];
        $Mines=$Array_Mod[21];
        $Rockets=$Array_Mod[35];

        if($Camera_low > 25)
            $Camera_low="Fixe<br>";
        else
            $Camera_low="";
        if($Camera_high == 26)
            $Camera_high="Fixe<br>";
        elseif($Camera_high == 27)
            $Camera_high="Haute altitude<br>";
        else
            $Camera_high="";

        //Charge
        $bombs_array = [
            2000 => (2000*$Bombe2000_nbr),
            1000 => (1000*$Bombe1000_nbr),
            500 => (500*$Bombe500_nbr),
            250 => (250*$Bombe250_nbr),
            125 => (125*$Bombe125_nbr),
            50 => (50*$Bombe50_nbr),
        ];
        asort($bombs_array);
        foreach($bombs_array as $key => $value){
            $charge = $value;
            $bomb = $key;
        }
        $Masse_full=$masse+$charge;
        if($charge)
            $Autonomie_chg=round($autonomie+(($masse/$puissance)-($Masse_full/$puissance))*($masse/10));
        else
            $Autonomie_chg=$autonomie;
        $autonomie_chg_p = $Autonomie_chg / 100;
        $autonomie_p = ($autonomie/100) - $autonomie_chg_p;
        $robustesse_p = $robustesse/50;
        $blindage_p = $blindage;

        //IA
        $autonomie_ia = floor(($autonomie/2)-200);
        if($autonomie_ia <50)$autonomie_ia=50;
        if($Baby)$autonomie_long = floor($autonomie_ia+($Baby/2));
        if($type_avion ==2 or $type_avion ==7 or $type_avion ==10 or $type_avion ==11){
            $Massef_s=$masse+($bombe_nbr*$bombe);
            $Massef_t=$masse+$bomb;
            $Poids_Puiss_ori=$masse/$puissance;
            $Poids_Puiss_s=$Massef_s/$puissance;
            $Poids_Puiss_t=$Massef_t/$puissance;
            if($type_avion ==2 or $type_avion ==11)
                $autonomie_s = round($autonomie-(($Poids_Puiss_s-$Poids_Puiss_ori)*($Massef_s/10)));
            else
                $autonomie_s = round(($autonomie/2)-(($Poids_Puiss_s-$Poids_Puiss_ori)*($Massef_s/10)));
            $autonomie_t = round(($autonomie/2)-(($Poids_Puiss_t-$Poids_Puiss_ori)*($Massef_t/10)));
            if($autonomie_s <50)$autonomie_s = 50;
        }
        else{
            $autonomie_t = 0;
            $autonomie_s = 0;
            $autonomie_long_p = 0;
        }
        $autonomie_t_p = $autonomie_t / 30;
        $autonomie_s_p = $autonomie_s / 30;
        $autonomie_ia_p = $autonomie_ia / 30;
        $autonomie_long_p = $autonomie_long / 30;

        //Perfs
        $i=$alt_form;
        //for($i=9000; $i >=0; $i-=2000){
            if($plafond >=$i){
                $puiss = GetPuiss($puissance, $compresseur, $i, $alt_ref);
                $puiss_p = $puiss/70;
                $puiss_empty = $masse / $puiss;
                $puiss_full = $Masse_full / $puiss;
                $puiss_full_p = $puiss_full*10;
                $puiss_empty_p = ($puiss_empty*10)-$puiss_full_p;
                $vitesse = GetSpeeds($i, $alt_ref, $plafond, $vitesse_h, $vitesse_b);
                if($flaps >0)
                    $vitesse_flaps = $vitesse*((10-$flaps)/10);
                $vitf_p = $vitesse_flaps/10;
                $vit_p = ($vitesse/10)-$vitf_p;
                if($i >=$alt_ref)
                    $vitessea = floor($vitesse_a/($i/$alt_ref));
                else
                    $vitessea = $vitesse_a;
                $vita_p = $vitessea/20;
                $mano = GetMano($man_h, $man_b,1500,1500, $i,1,1, 0);
                $mano_flaps = GetMano($man_h, $man_b,1500,1500, $i,1,1, $flaps);
                $mano_p = $mano/2.5;
                $mano_flaps_p = ($mano_flaps/2.5) - $mano_p;
                $mani_flaps = GetMani($mani,1,9999,1,1,$flaps);
                $perf = $mano+$mani+($vitesse*2)+($puiss/20);
                $perf_v = $mano_flaps+$mani_flaps+($vitesse*2)+($puiss/20); //Dogfight
                $perf_f = $perf-($vitesse*2)+($vitesse_p*2); //fuite
                $perf_p = $perf/25;
                $perf_vp = $perf_v/25;
                $perf_fp = $perf_f/25;
                if($vitessea >668 and $vitesse_p >659 and ($vitesse*2) <($vitesse_p+$vitessea)){
                    $perf_c = $perf-($vitesse*2)+$vitessa+$vitesse_p; //Boom & Zoom
                    $perf_cp = $perf_c/25;
                    $bar_perf_c.='            
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: '.$perf_cp.'%" aria-valuenow="'.$perf_c.'" aria-valuemin="0" aria-valuemax="2500">'.$card_head.'</div>
                    </div>';
                }
                $bar_puiss.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$puiss_p.'%" aria-valuenow="'.$puiss.'" aria-valuemin="0" aria-valuemax="7000">'.$card_head.'</div>'.$puiss.'
            </div>';
                $bar_ppuiss.='
            <div class="progress">
                <div class="progress-bar bg-danger" role="progressbar" style="width: '.$puiss_full_p.'%" aria-valuenow="'.$puiss_full.'" aria-valuemin="0" aria-valuemax="10">'.$card_head.'</div>';
                if($charge){
                    $bar_ppuiss.='<div class="progress-bar bg-info" role="progressbar" style="width: '.$puiss_p.'%" aria-valuenow="'.$puiss_empty.'" aria-valuemin="0" aria-valuemax="10">A Vide</div>';
                    $bar_bombs.='            
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: '.($charge/70).'%" aria-valuenow="'.$charge.'" aria-valuemin="0" aria-valuemax="7000">'.$card_head.'</div>'.$charge.'
                    </div>';
                }
                $bar_ppuiss.='</div>';
                $bar_speed.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$vitf_p.'%" aria-valuenow="'.$vitesse_flaps.'" aria-valuemin="0" aria-valuemax="1000">'.$card_head.'</div>
                <div class="progress-bar bg-warning" role="progressbar" style="width: '.$vit_p.'%" aria-valuenow="'.$vitesse.'" aria-valuemin="0" aria-valuemax="1000">Rentrés</div>'.round($vitesse).'
            </div>';
                $bar_speeda.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$vita_p.'%" aria-valuenow="'.$vitessea.'" aria-valuemin="0" aria-valuemax="2000">'.$card_head.'</div>'.round($vitessea).'
            </div>';
                $bar_man.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$mano_p.'%" aria-valuenow="'.$mano.'" aria-valuemin="0" aria-valuemax="250">'.$card_head.'</div>
                <div class="progress-bar bg-warning" role="progressbar" style="width: '.$mano_flaps_p.'%" aria-valuenow="'.$mano_flaps.'" aria-valuemin="0" aria-valuemax="250">Volets</div>
            </div>';
                $bar_perf.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$perf_p.'%" aria-valuenow="'.$perf.'" aria-valuemin="0" aria-valuemax="2500">'.$card_head.'</div>
            </div>';
                $bar_perf_v.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$perf_vp.'%" aria-valuenow="'.$perf_v.'" aria-valuemin="0" aria-valuemax="2500">'.$card_head.'</div>
            </div>';
                $bar_perf_f.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$perf_fp.'%" aria-valuenow="'.$perf_f.'" aria-valuemin="0" aria-valuemax="2500">'.$card_head.'</div>
            </div>';
                $bar_plafond.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.($plafond/150).'%" aria-valuenow="'.$plafond.'" aria-valuemin="0" aria-valuemax="15000">'.$card_head.'</div>'.$plafond.'
            </div>';
                $bar_autonomie.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.($autonomie/60).'%" aria-valuenow="'.$autonomie.'" aria-valuemin="0" aria-valuemax="6000">'.$card_head.'</div>'.$autonomie.'
            </div>';
                $bar_autonomie_ia.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$autonomie_ia_p.'%" aria-valuenow="'.$autonomie_ia.'" aria-valuemin="0" aria-valuemax="3000">'.$card_head.'</div>'.$autonomie_ia.'
            </div>';
                $bar_autonomie_long.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$autonomie_long_p.'%" aria-valuenow="'.$autonomie_long.'" aria-valuemin="0" aria-valuemax="3000">'.$card_head.'</div>'.$autonomie_long.'
            </div>';
                $bar_autonomie_s.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$autonomie_s_p.'%" aria-valuenow="'.$autonomie_s.'" aria-valuemin="0" aria-valuemax="3000">'.$card_head.'</div>'.$autonomie_s.'
            </div>';
                $bar_autonomie_t.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$autonomie_t_p.'%" aria-valuenow="'.$autonomie_t.'" aria-valuemin="0" aria-valuemax="3000">'.$card_head.'</div>'.$autonomie_t.' <i> (avec '.$bomb.'kg de bombes)</i>
            </div>';
                $bar_degats.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.($Degats_tot/100).'%" aria-valuenow="'.$Degats_tot.'" aria-valuemin="0" aria-valuemax="10000">'.$card_head.'</div>'.$Degats_tot.'
            </div>';
                $bar_hp.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.($robustesse/60).'%" aria-valuenow="'.$robustesse.'" aria-valuemin="0" aria-valuemax="6000">'.$card_head.'</div>';
                if($blindage) {
                    $bar_hp .='<div class="progress-bar bg-warning" role="progressbar" style="width: '.$blindage.'%" aria-valuenow="'.$blindage.'" aria-valuemin="0" aria-valuemax="25">Blindage</div>';
                }
            $bar_hp.='</div>';
            }
        //}
    }
    //Output?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="JF Binote">
        <title>Aube des Aigles : Avion</title>
        <link href="css/bs4/bootstrap.min.css" rel="stylesheet">
        <link href="css/avion.css" rel="stylesheet">
    </head>
    <body>
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">Aube des Aigles</a>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#section-perfs">Performances</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="header-wrap">
        <section id="tableau">
            <div class="card" id="profil">
                <h2 class="card-header text-center"><?=GetAvionType($cat)." à ".$alt_form."m";?></h2>
                <div class="card-block">
                    <section class="row" id="section-perfs">
                        <h3>Performances</h3>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-pl"><h4>Plafond</h4></a>
                                        <div class="col-12 collapse" id="barre-pl">
                                            <?=$bar_plafond;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-au"><h4>Autonomie</h4></a>
                                        <div class="col-12 collapse" id="barre-au">
                                            <?=$bar_autonomie;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-ac"><h4>Rayon d'action Chasse/Reco</h4></a>
                                        <div class="col-12 collapse" id="barre-ac">
                                            <?=$bar_autonomie_ia;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-ar"><h4>Rayon d'action Chasse/Reco avec réservoir</h4></a>
                                        <div class="col-12 collapse" id="barre-ar">
                                            <?=$bar_autonomie_ia;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-as"><h4>Rayon d'action Stratégique</h4></a>
                                        <div class="col-12 collapse" id="barre-as">
                                            <?=$bar_autonomie_s;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-at"><h4>Rayon d'action Tactique</h4></a>
                                        <div class="col-12 collapse" id="barre-at">
                                            <?=$bar_autonomie_t;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-wp"><h4>Dégâts max des armes</h4></a>
                                        <div class="col-12 collapse" id="barre-wp">
                                            <?=$bar_degats;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-bo"><h4>Charge max de bombes</h4></a>
                                        <div class="col-12 collapse" id="barre-bo">
                                            <?=$bar_bombs;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-hp"><h4>Robustesse</h4></a>
                                        <div class="col-12 collapse" id="barre-hp">
                                            <?=$bar_hp;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-vt"><h4>Vitesse max</h4></a>
                                        <div class="col-12 collapse" id="barre-vt">
                                            <?=$bar_speed;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-va"><h4>Vitesse ascensionnelle à vide</h4></a>
                                        <div class="col-12 collapse" id="barre-va">
                                            <?=$bar_speeda;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-mn"><h4>Manoeuvrabilité</h4></a>
                                        <div class="col-12 collapse" id="barre-mn">
                                            <?=$bar_man;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-cb"><h4>Combat aérien</h4></a>
                                        <div class="col-12 collapse" id="barre-cb">
                                            <?=$bar_perf;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-bz"><h4>Boom & Zoom</h4></a>
                                        <div class="col-12 collapse" id="barre-bz">
                                            <?=$bar_perf_c;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-df"><h4>Dogfight</h4></a>
                                        <div class="col-12 collapse" id="barre-df">
                                            <?=$bar_perf_v;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a data-toggle="collapse" href="#barre-fu"><h4>Fuite</h4></a>
                                        <div class="col-12 collapse" id="barre-fu">
                                            <?=$bar_perf_f;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
    <footer class="bg-inverse text-center text-success">&copy;JF-2017</footer>
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="js/bs4/bootstrap.min.js"></script>
    </body>
    </html>
    <?
}
else{?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="JF Binote">
        <title>Aube des Aigles : Avion</title>
        <link href="css/bs4/bootstrap.min.css" rel="stylesheet">
        <link href="css/avion.css" rel="stylesheet">
    </head>
<body>
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">Aube des Aigles</a>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#section-compare">Comparateur</a>
                </li>
            </ul>
        </div>
    </nav>
<div class="container">
    <div class="header-wrap"></div>
    <section id="section-compare">
        <form action="?" method="post">
            <fieldset>
                <legend>Comparateur d'avions</legend>
                <div class="row">
                    <div class="col-12">
                        <label for="cat">Catégorie d'avion</label><br>
                        <select class="form-check-inline" name="cat" id="cat">
                            <option value="7">Attaque</option>
                            <option value="10">Avion embarqué</option>
                            <option value="1">Chasseur</option>
                            <option value="2">Bombardier</option>
                            <option value="11">Bombardier lourd</option>
                            <option value="12">Chasseur embarqué</option>
                            <option value="4">Chasseur lourd</option>
                            <option value="9">Patrouille maritime</option>
                            <option value="3">Reconnaissance</option>
                            <option value="6">Transport</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="country">Pays</label><br>
                        <select class="form-check-inline" name="country" id="country">
                            <option value="0">Tous</option>
                            <option value="1">Allemagne</option>
                            <option value="2">Angleterre</option>
                            <option value="3">Belgique</option>
                            <option value="15">Bulgarie</option>
                            <option value="20">Finlande</option>
                            <option value="4">France</option>
                            <option value="10">Grèce</option>
                            <option value="19">Hongrie</option>
                            <option value="6">Italie</option>
                            <option value="9">Japon</option>
                            <option value="35">Norvège</option>
                            <option value="5">Pays-Bas</option>
                            <option value="18">Roumanie</option>
                            <option value="8">URSS</option>
                            <option value="7">USA</option>
                            <option value="17">Yougoslavie</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="alt">Altitude</label><br>
                        <select class="form-check-inline" name="alt" id="alt">
                            <?
                            for($i=0; $i<=10000; $i+=500){
                                echo '<option value="'.$i.'">'.$i.'m</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <label for="date1">Date d'entrée en service du modèle d'avion le moins récent</label>
                        <input class="form-check-inline" type="date" name="date1" min="1930-01-01" max="1945-12-31" value="1930-01-01">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="date2">Date d'entrée en service du modèle d'avion le plus récent</label>
                        <input class="form-check-inline" type="date" name="date2" min="1940-01-01" max="1945-12-31" value="1940-05-01">
                    </div>
                </div>
                <input class="form-control-sm btn btn-secondary" type="submit" value="Valider">
            </fieldset>
        </form>
    </section>
</div>
    <footer class="bg-inverse text-center text-success">&copy;JF-2017</footer>
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="js/bs4/bootstrap.min.js"></script>
</body>
    </html>
<?}
