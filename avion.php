<?php
require_once './jfv_inc_sessions.php';
include_once './jfv_include.inc.php';
$ID = Insec($_GET['avion']);
dbconnect();
$resavions = $dbh->query("SELECT ID,Nom FROM Avion ORDER BY Nom ASC");
while ($dataa = $resavions->fetchObject()) {
    $avions .= '<option value="' . $dataa->ID . '">' . $dataa->Nom . '</option>';
}
$header = '
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
    <body>';
$footer = '
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write(\'<script src="../../assets/js/vendor/jquery.min.js"><\/script>\')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="js/bs4/bootstrap.min.js"></script>
    </body>
    </html>';
if (is_numeric($ID)) {
    include_once './jfv_air_inc.php';
    include_once './jfv_txt.inc.php';
    include_once './jfv_combat.inc.php';
    include_once './jfv_avions.inc.php';

    /**
     * @param int $alt
     * @param int $alt_ref
     * @param int $plafond
     * @param int $VitesseH
     * @param int $VitesseB
     * @return float|int
     */
    function GetSpeeds($alt, $alt_ref, $plafond, $VitesseH, $VitesseB){
        if ($alt_ref <= 0) return 0;
        if ($plafond >= $alt) {
            if ($alt > $alt_ref)
                $Vit = $VitesseH + ((($VitesseH - $VitesseB) / $alt_ref) * ($alt_ref - $alt));
            elseif ($alt <= $alt_ref)
                $Vit = $VitesseB + ((($VitesseH - $VitesseB) / $alt_ref) * $alt);
        } else {
            $Vit = 0;
        }
        return $Vit;
    }

    /**
     * @param int $puissance
     * @param int $compressor
     * @param int $alt
     * @param int $alt_ref
     * @return float
     */
    function GetPuiss($puissance, $compressor, $alt, $alt_ref)
    {
        if ($compressor == 2) {
            if ($alt < $alt_ref)
                $Puiss = floor($puissance / (1 + (($alt_ref - $alt) / 10000)));
        } elseif ($compressor == 3) {
            if ($alt > $alt_ref)
                $Puiss = floor($puissance / (1 + (($alt - $alt_ref) / 10000)));
        } elseif ($compressor == 1) {
            if ($alt >= $alt_ref)
                $Puiss = floor($puissance / (1 + (($alt - $alt_ref) / 10000)));
            elseif ($alt < $alt_ref)
                $Puiss = floor($puissance / (1 + (($alt_ref - $alt) / 20000)));
        } else {
            if ($alt >= $alt_ref)
                $Puiss = floor($puissance / (1 + (($alt - $alt_ref) / 5000)));
            elseif ($alt < $alt_ref)
                $Puiss = floor($puissance / (1 + (($alt_ref - $alt) / 10000)));
        }
        return $Puiss;
    }

    $result = $dbh->prepare("SELECT a.*,DATE_FORMAT(a.`Engagement`,'%d-%m-%Y') AS Engage FROM Avion AS a WHERE ID=:id");
    $result->bindParam(':id', $ID, 1);
    $result->execute();
    while($data = $result->fetchObject()){
        $card_head = $data->Nom;
        $type_avion = $data->Type;
        $pays_avion = $data->Pays;
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
                $Arme2_fiab=$Arme1_fiab;
                $Arme2_range=$Arme1_range;
                $Arme2_muns=$Arme1_muns;
                $Degats_chass_2=$Degats_chass_1;
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
        $Degats_chass_1_p = $Degats_chass_1/100;
        if($arme2_nbr){
            $Degats_chass_2_p = ($Degats_chass_2/100);
        }
        if($arme3_nbr){
            $Degats_3_p = ($Degats_3/100);
        }
        if($arme4_nbr){
            $Degats_4_p = ($Degats_4/100);
        }
        if($arme5_nbr){
            $Degats_5_p = ($Degats_5/100);
        }
        if($arme6_nbr){
            $Degats_6_p = ($Degats_6/100);
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
        if($flaps ==3){
            $flaps_txt = 'Volets de piqué';
        }
        elseif($flaps){
            $flaps_txt = ''.($flaps+1).' crans';
        }
        else{
            $flaps_txt = '1 cran';
        }

        //Stuff
        $Array_Mod=GetAmeliorations($ID);
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
        $charge=$bombe_nbr*$bombe;
        $Masse_full=$masse+$charge;
        if($charge)
            $Autonomie_chg=round($autonomie+(($masse/$puissance)-($Masse_full/$puissance))*($masse/10));
        else
            $Autonomie_chg=$autonomie;
        $autonomie_chg_p = $Autonomie_chg / 60;
        $autonomie_p = ($autonomie/60) - $autonomie_chg_p;
        $robustesse_p = $robustesse/50;
        $blindage_p = $blindage;

        //IA
        $autonomie_ia = floor(($autonomie/2)-200);
        if($autonomie_ia <50)$autonomie_ia=50;
        if($Baby)$autonomie_long = floor($autonomie_ia+($Baby/2));
        if($type_avion ==2 or $type_avion ==7 or $type_avion ==10 or $type_avion ==11){
            $Massef_s=$masse+$Masse_full;
            $Massef_t=$masse+$bombe;
            $Poids_Puiss_ori=$masse/$puissance;
            $Poids_Puiss_s=$Massef_s/$puissance;
            $Poids_Puiss_t=$Massef_t/$puissance;
            if($type_avion ==2 or $type_avion ==11)
                $autonomie_s = round($autonomie-(($Poids_Puiss_s-$Poids_Puiss_ori)*($Massef_s/10)));
            else
                $autonomie_s = round(($autonomie/2)-(($Poids_Puiss_s-$Poids_Puiss_ori)*($Massef_s/10)));
            $autonomie_t = round(($autonomie/2)-(($Poids_Puiss_t-$Poids_Puiss_ori)*($Massef_t/10)));
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
        for($i=10000; $i >=0; $i-=1000){
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
                    <div class="progress-bar bg-info" role="progressbar" style="width: '.$perf_cp.'%" aria-valuenow="'.$perf_c.'" aria-valuemin="0" aria-valuemax="2500">Boom & Zoom à '.$i.'m</div>
                </div>';
                }
                $bar_puiss.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$puiss_p.'%" aria-valuenow="'.$puiss.'" aria-valuemin="0" aria-valuemax="7000">'.$i.'m</div>
            </div>';
                $bar_ppuiss.='
            <div class="progress">
                <div class="progress-bar bg-danger" role="progressbar" style="width: '.$puiss_full_p.'%" aria-valuenow="'.$puiss_full.'" aria-valuemin="0" aria-valuemax="10">Pleine charge à '.$i.'m</div>';
                if($charge){
                    $bar_ppuiss.='<div class="progress-bar bg-info" role="progressbar" style="width: '.$puiss_p.'%" aria-valuenow="'.$puiss_empty.'" aria-valuemin="0" aria-valuemax="10">A Vide</div>';
                }
                $bar_ppuiss.='</div>';
                $bar_speed.='
            <div class="progress">';
                if($flaps){
                    $bar_speed.='<div class="progress-bar bg-info" role="progressbar" style="width: '.$vitf_p.'%" aria-valuenow="'.$vitesse_flaps.'" aria-valuemin="0" aria-valuemax="1000">Volets sortis à '.$i.'m</div>';
                    $bar_speed.='<div class="progress-bar bg-warning" role="progressbar" style="width: '.$vit_p.'%" aria-valuenow="'.$vitesse.'" aria-valuemin="0" aria-valuemax="1000">Rentrés</div>';
                }else{
                    $bar_speed.='<div class="progress-bar bg-info" role="progressbar" style="width: '.$vit_p.'%" aria-valuenow="'.$vitesse.'" aria-valuemin="0" aria-valuemax="1000">'.$i.'m</div>';
                }
                $bar_speed.='</div>';
                $bar_speeda.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$vita_p.'%" aria-valuenow="'.$vitessea.'" aria-valuemin="0" aria-valuemax="2000">Grimper à '.$i.'m</div>
            </div>';
                $bar_man.='
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$mano_p.'%" aria-valuenow="'.$mano.'" aria-valuemin="0" aria-valuemax="250">Manoeuvre volets rentrés à '.$i.'m</div>
                <div class="progress-bar bg-warning" role="progressbar" style="width: '.$mano_flaps_p.'%" aria-valuenow="'.$mano_flaps.'" aria-valuemin="0" aria-valuemax="250">Volets</div>
            </div>';
                $bar_perf.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$perf_p.'%" aria-valuenow="'.$perf.'" aria-valuemin="0" aria-valuemax="2500">Combat à '.$i.'m</div>
            </div>';
                $bar_perf_v.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$perf_vp.'%" aria-valuenow="'.$perf_v.'" aria-valuemin="0" aria-valuemax="2500">Dogfight à '.$i.'m</div>
            </div>';
                $bar_perf_f.='            
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" style="width: '.$perf_fp.'%" aria-valuenow="'.$perf_f.'" aria-valuemin="0" aria-valuemax="2500">Fuite à '.$i.'m</div>
            </div>';
            }
        }
    }
    echo $header;
    //Output?>
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">Aube des Aigles</a>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item my-2">
                    <a class="nav-link" href="#section-engine">Moteur</a>
                </li>
                <li class="nav-item my-2">
                    <a class="nav-link" href="#section-struct">Structure</a>
                </li>
                <li class="nav-item my-2">
                    <a class="nav-link" href="#section-weapons">Armement</a>
                </li>
                <li class="nav-item my-2">
                    <a class="nav-link" href="#section-stuff">Equipement</a>
                </li>
                <li class="nav-item my-2">
                    <a class="nav-link" href="#section-perfs">Performances</a>
                </li>
                <li class="nav-item">
                    <form action="avion.php" class="form-inline my-2 my-lg-0">
                        <select name="avion" id="avion" class="form-control">
                            <option value="<?=$ID?>"><?=$card_head;?></option>
                            <?=$avions;?>
                        </select>
                        <input type="submit" value="Changer" class="btn btn-default my-2">
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="header-wrap"></div>
        <section id="tableau">
            <div class="card" id="profil">
                <h2 class="card-header text-center"><?=$card_head;?></h2>
                <div class="card-block">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 text-center">
                            <div id="slider" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#slider" data-slide-to="0" class="active"></li>
                                    <li data-target="#slider" data-slide-to="1"></li>
                                    <li data-target="#slider" data-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active">
                                        <img class="d-block img-fluid mx-auto" src="images/avions/vol<?=$ID;?>.jpg" alt="slide 0">
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block img-fluid mx-auto" src="images/avions/decollage<?=$ID;?>.jpg" alt="slide 1">
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block img-fluid mx-auto" src="images/avions/garage<?=$ID;?>.jpg" alt="slide 2">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#slider" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 text-center">
                            <table class="table table-striped">
                                <tr>
                                    <th>Nation</th>
                                    <td><img src="images/<?=$pays_avion?>20.gif" alt="<?=GetPays($pays_avion);?>"></td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td><?=GetAvionType($type_avion);?></td>
                                </tr>
                                <tr>
                                    <th>Engagement</th>
                                    <td><?=$engagement;?></td>
                                </tr>
                                <tr>
                                    <th>Equipage</th>
                                    <td><?=$equipage;?></td>
                                </tr>
                                <tr>
                                    <td class="text-center" colspan="2"><img src="images/avions/avion<?=$ID;?>.gif" alt="icône de l'avion"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <section class="row" id="section-engine">
                        <h3>Motorisation</h3>
                        <div class="col-12">
                            <span class="badge badge-pill badge-info"><?=$engine_nbr."x ".$engine_name;?></span>
                            <table class="table table-condensed table-striped hidden-sm-down">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Alim</th>
                                    <th>Compresseur</th>
                                    <th>Carburant</th>
                                    <th>Fiabilité</th>
                                    <th>Puissance</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td><?=$engine_type;?></td>
                                    <td><?=$injection;?></td>
                                    <td><?=$compresseur;?></td>
                                    <td><?=$carbu;?> Octane</td>
                                    <td><?=$fiabilite;?>%</td>
                                    <td><?=($puissance/$engine_nbr);?>cv</td>
                                </tr>
                            </table>
                            <table class="table table-condensed table-striped hidden-md-up">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Alim</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td><?=$engine_type;?></td>
                                    <td><?=$injection;?></td>
                                </tr>
                            </table>
                            <table class="table table-condensed table-striped hidden-md-up">
                                <thead>
                                <tr>
                                    <th>Compresseur</th>
                                    <th>Carburant</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td><?=$compresseur;?></td>
                                    <td><?=$carbu;?> Octane</td>
                                </tr>
                            </table>
                            <table class="table table-condensed table-striped hidden-md-up">
                                <thead>
                                <tr>
                                    <th>Fiabilité</th>
                                    <th>Puissance</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td><?=$fiabilite;?>%</td>
                                    <td><?=($puissance/$engine_nbr);?>cv</td>
                                </tr>
                            </table>
                            <div class="row">
                                <a data-toggle="collapse" href="#barre-puissance"><h4>Puissance</h4></a>
                                <div class="col-12 collapse" id="barre-puissance">
                                    <?=$bar_puiss;?>
                                </div>
                            </div>
                            <div class="row">
                                <a data-toggle="collapse" href="#barre-pp"><h4>Rapport Poids/Puissance</h4></a>
                                <div class="col-12 collapse" id="barre-pp">
                                    <?=$bar_ppuiss;?>;
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="row" id="section-struct">
                        <h3>Structure</h3>
                        <div class="col-12">
                            <table class="table table-condensed table-striped hidden-sm-down">
                                <thead>
                                <tr>
                                    <th>Revêtement</th>
                                    <th>Voilure</th>
                                    <th>Volets</th>
                                    <th>Train</th>
                                    <th>Verrière</th>
                                    <th>Réservoir</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td><?=$Cellule_txt;?></td>
                                    <td><?=$Voilure_txt;?></td>
                                    <td><?=$flaps_txt;?></td>
                                    <td><?=$Train_txt;?></td>
                                    <td><?=$Verriere_txt;?></td>
                                    <td><?=$Reservoir_txt;?></td>
                                </tr>
                            </table>
                            <table class="table table-condensed table-striped hidden-md-up">
                                <thead>
                                <tr>
                                    <th>Revêtement</th>
                                    <th>Voilure</th>
                                    <th>Volets</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td><?=$Cellule_txt;?></td>
                                    <td><?=$Voilure_txt;?></td>
                                    <td><?=$flaps_txt;?></td>
                                </tr>
                            </table>
                            <table class="table table-condensed table-striped hidden-md-up">
                                <thead>
                                <tr>
                                    <th>Train</th>
                                    <th>Verrière</th>
                                    <th>Réservoir</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td><?=$Train_txt;?></td>
                                    <td><?=$Verriere_txt;?></td>
                                    <td><?=$Reservoir_txt;?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: <?=$robustesse_p;?>%" aria-valuenow="<?=$robustesse;?>" aria-valuemin="0" aria-valuemax="5000">Robustesse</div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?=$blindage_p;?>%" aria-valuenow="<?=$blindage;?>" aria-valuemin="0" aria-valuemax="25">Blindage</div>
                            </div>
                        </div>
                    </section>
                    <section class="row" id="section-weapons">
                        <h3>Armement</h3>
                        <div class="col-12">
                            <h4>Armes offensives</h4>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <img src="images/graisse1.gif" alt=""><br>
                                    <span class="badge badge-pill badge-info"><?=$arme1_nbr."x ".$Arme1_nom." (".$Arme1_cal."mm)";?></span>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <img src="images/graisse1.gif" alt=""><br>
                                    <?if($arme2_nbr){?>
                                        <span class="badge badge-pill badge-info"><?=$arme2_nbr."x ".$Arme2_nom." (".$Arme2_cal."mm)";?></span>
                                    <?}else{?>
                                        <span class="badge badge-pill badge-danger">Aucune</span>
                                    <?}?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-condensed table-striped hidden-sm-down">
                                        <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Calibre</th>
                                            <th>Dégâts</th>
                                            <th>Portée</th>
                                            <th>Fiabilité</th>
                                            <th>Munitions</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><?=$arme1_nbr."x ".$Arme1_nom;?></td>
                                            <td><?=$Arme1_cal;?>mm</td>
                                            <td><?=round($Arme1_cal)." - ".($Arme1_deg*$Arme1_mult);?></td>
                                            <td><?=$Arme1_range;?>m</td>
                                            <td><?=$Arme1_fiab;?>%</td>
                                            <td><?=$Arme1_muns;?></td>
                                        </tr>
                                        <?if($arme2_nbr and $arme2 !=5 and $arme2 !=25 and $arme2 !=26 and $arme2 !=27){?>
                                        <tr>
                                            <td><?=$arme2_nbr."x ".$Arme2_nom;?></td>
                                            <td><?=$Arme2_cal;?>mm</td>
                                            <td><?=round($Arme2_cal)." - ".($Arme2_deg*$Arme2_mult);?></td>
                                            <td><?=$Arme2_range;?>m</td>
                                            <td><?=$Arme2_fiab;?>%</td>
                                            <td><?=$Arme2_muns;?></td>
                                        </tr>
                                        <?}?>
                                    </table>
                                    <table class="table table-condensed table-striped hidden-md-up">
                                        <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Calibre</th>
                                            <th>Dégâts</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><?=$arme1_nbr."x ".$Arme1_nom;?></td>
                                            <td><?=$Arme1_cal;?>mm</td>
                                            <td><?=round($Arme1_cal)." - ".($Arme1_deg*$Arme1_mult);?></td>
                                        </tr>
                                        <?if($arme2_nbr and $arme2 !=5 and $arme2 !=25 and $arme2 !=26 and $arme2 !=27){?>
                                            <tr>
                                                <td><?=$arme2_nbr."x ".$Arme2_nom;?></td>
                                                <td><?=$Arme2_cal;?>mm</td>
                                                <td><?=round($Arme2_cal)." - ".($Arme2_deg*$Arme2_mult);?></td>
                                            </tr>
                                        <?}?>
                                    </table>
                                    <table class="table table-condensed table-striped hidden-md-up">
                                        <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Portée</th>
                                            <th>Fiabilité</th>
                                            <th>Munitions</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><?=$arme1_nbr."x ".$Arme1_nom;?></td>
                                            <td><?=$Arme1_range;?>m</td>
                                            <td><?=$Arme1_fiab;?>%</td>
                                            <td><?=$Arme1_muns;?></td>
                                        </tr>
                                        <?if($arme2_nbr and $arme2 !=5 and $arme2 !=25 and $arme2 !=26 and $arme2 !=27){?>
                                            <tr>
                                                <td><?=$arme2_nbr."x ".$Arme2_nom;?></td>
                                                <td><?=$Arme2_range;?>m</td>
                                                <td><?=$Arme2_fiab;?>%</td>
                                                <td><?=$Arme2_muns;?></td>
                                            </tr>
                                        <?}?>
                                    </table>
                                </div>
                            </div>
                            <h4>Armes défensives</h4>
                            <div class="row">
                                <div class="col-6 col-sm-3">
                                    <span class="badge badge-pill badge-default">Arrière</span><br><img src="images/graisse1.gif" alt="3"><br>
                                    <?if($arme3_nbr){?>
                                        <span class="badge badge-pill badge-info"><?=$arme3_nbr."x ".$Arme3_nom." (".$Arme3_cal."mm)";?></span>
                                    <?}else{?>
                                        <span class="badge badge-pill badge-danger">Aucune</span>
                                    <?}?>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <span class="badge badge-pill badge-default">Latéral</span><br><img src="images/graisse1.gif" alt="4"><br>
                                    <?if($arme4_nbr){?>
                                        <span class="badge badge-pill badge-info"><?=$arme4_nbr."x ".$Arme4_nom." (".$Arme4_cal."mm)";?></span>
                                    <?}else{?>
                                        <span class="badge badge-pill badge-danger">Aucune</span>
                                    <?}?>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <span class="badge badge-pill badge-default">Dorsal</span><br><img src="images/graisse1.gif" alt="5"><br>
                                    <?if($arme5_nbr){?>
                                        <span class="badge badge-pill badge-info"><?=$arme5_nbr."x ".$Arme5_nom." (".$Arme5_cal."mm)";?></span>
                                    <?}else{?>
                                        <span class="badge badge-pill badge-danger">Aucune</span>
                                    <?}?>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <span class="badge badge-pill badge-default">Ventral</span><br><img src="images/graisse1.gif" alt="6"><br>
                                    <?if($arme6_nbr){?>
                                        <span class="badge badge-pill badge-info"><?=$arme6_nbr."x ".$Arme6_nom." (".$Arme6_cal."mm)";?></span>
                                    <?}else{?>
                                        <span class="badge badge-pill badge-danger">Aucune</span>
                                    <?}?>
                                </div>
                            </div>
                            <?if($arme3_nbr || $arme5_nbr){?>
                            <table class="table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Calibre</th>
                                    <th>Dégâts</th>
                                    <th>Portée</th>
                                    <th>Fiabilité</th>
                                    <th>Munitions</th>
                                </tr>
                                </thead>
                                <?if($arme3_nbr){?>
                                    <tr>
                                        <td><?=$Arme3_nom;?></td>
                                        <td><?=$Arme3_cal;?>mm</td>
                                        <td><?=round($Arme3_cal)." - ".($Arme3_deg*$Arme3_mult);?></td>
                                        <td><?=$Arme3_range;?>m</td>
                                        <td><?=$Arme3_fiab;?>%</td>
                                        <td><?=$Arme3_muns;?></td>
                                    </tr>
                                <?}?>
                                <?if($arme4_nbr and $arme4 != $arme3){?>
                                    <tr>
                                        <td><?=$Arme4_nom;?></td>
                                        <td><?=$Arme4_cal;?>mm</td>
                                        <td><?=round($Arme2_cal)." - ".($Arme4_deg*$Arme4_mult);?></td>
                                        <td><?=$Arme4_range;?>m</td>
                                        <td><?=$Arme4_fiab;?>%</td>
                                        <td><?=$Arme4_muns;?></td>
                                    </tr>
                                <?}?>
                                <?if($arme5_nbr and $arme5 != $arme3){?>
                                    <tr>
                                        <td><?=$Arme5_nom;?></td>
                                        <td><?=$Arme5_cal;?>mm</td>
                                        <td><?=round($Arme2_cal)." - ".($Arme5_deg*$Arme5_mult);?></td>
                                        <td><?=$Arme5_range;?>m</td>
                                        <td><?=$Arme5_fiab;?>%</td>
                                        <td><?=$Arme5_muns;?></td>
                                    </tr>
                                <?}?>
                                <?if($arme6_nbr and $arme6 != $arme3){?>
                                    <tr>
                                        <td><?=$Arme6_nom;?></td>
                                        <td><?=$Arme6_cal;?>mm</td>
                                        <td><?=round($Arme6_cal)." - ".($Arme6_deg*$Arme6_mult);?></td>
                                        <td><?=$Arme6_range;?>m</td>
                                        <td><?=$Arme6_fiab;?>%</td>
                                        <td><?=$Arme6_muns;?></td>
                                    </tr>
                                <?}?>
                            </table>
                            <?}?>
                            <a data-toggle="collapse" href="#barre-dg"><h4>Dégâts armes de bord</h4></a>
                            <div class="col-12 collapse" id="barre-dg">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 109.72%" aria-valuenow="10972" aria-valuemin="0" aria-valuemax="10000">4x MG151/20</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 91.6%" aria-valuenow="9160" aria-valuemin="0" aria-valuemax="10000">4x Hispano MkII</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 82.88%" aria-valuenow="8288" aria-valuemin="0" aria-valuemax="10000">8x Browning M2</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 62.16%" aria-valuenow="6216" aria-valuemin="0" aria-valuemax="10000">6x Browning M2</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 54.86%" aria-valuenow="5486" aria-valuemin="0" aria-valuemax="10000">2x MG151/20</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 31.46%" aria-valuenow="3146" aria-valuemin="0" aria-valuemax="10000">2x ShVAK</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 24.32%" aria-valuenow="2432" aria-valuemin="0" aria-valuemax="10000">8x Browning M1919</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 9.68%" aria-valuenow="968" aria-valuemin="0" aria-valuemax="10000">2x Breda SAFAT</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?=$Degats_chass_1_p;?>%" aria-valuenow="<?=$Degats_chass_1;?>" aria-valuemin="0" aria-valuemax="10000">Principal</div>
                                </div>
                                <?if($arme2_nbr){?>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?=$Degats_chass_2_p;?>%" aria-valuenow="<?=$Degats_chass_2;?>" aria-valuemin="0" aria-valuemax="10000">Secondaire</div>
                                </div>
                                <?}if($arme3_nbr){?>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?=$Degats_3_p;?>%" aria-valuenow="<?=$Degats_3;?>" aria-valuemin="0" aria-valuemax="10000">Arrière</div>
                                </div>
                                <?}if($arme4_nbr){?>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?=$Degats_4_p;?>%" aria-valuenow="<?=$Degats_4;?>" aria-valuemin="0" aria-valuemax="10000">Latéral</div>
                                </div>
                                <?}if($arme5_nbr){?>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?=$Degats_5_p;?>%" aria-valuenow="<?=$Degats_5;?>" aria-valuemin="0" aria-valuemax="10000">Dorsal</div>
                                </div>
                                <?}if($arme6_nbr){?>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?=$Degats_6_p;?>%" aria-valuenow="<?=$Degats_6;?>" aria-valuemin="0" aria-valuemax="10000">Ventral</div>
                                </div>
                                <?}?>
                            </div>
                            <h4>Bombes</h4>
                            <div class="row">
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">2000kg</span><br><img src="images/icon_bomb.png" alt="2000"><br>
                                    <span class="badge badge-pill badge-info"><?=$Bombe2000_nbr;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">1000kg</span><br><img src="images/icon_bomb.png" alt="1000"><br>
                                    <span class="badge badge-pill badge-info"><?=$Bombe1000_nbr;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">500kg</span><br><img src="images/icon_bomb.png" alt="500"><br>
                                    <span class="badge badge-pill badge-info"><?=$Bombe500_nbr;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">250kg</span><br><img src="images/icon_bomb.png" alt="250"><br>
                                    <span class="badge badge-pill badge-info"><?=$Bombe250_nbr;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">125kg</span><br><img src="images/icon_bomb.png" alt="125"><br>
                                    <span class="badge badge-pill badge-info"><?=$Bombe125_nbr;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">50kg</span><br><img src="images/icon_bomb.png" alt="50"><br>
                                    <span class="badge badge-pill badge-info"><?=$Bombe50_nbr;?></span>
                                </div>
                            </div>
                            <h4>Charges</h4>
                            <div class="row">
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">Torpilles</span><br><img src="images/icon_torpille.png" alt="torpille"><br>
                                    <span class="badge badge-pill badge-info"><?=$Torpilles;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">Mines</span><br><img src="images/icon_mines.png" alt="Mines"><br>
                                    <span class="badge badge-pill badge-info"><?=$Mines;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">Rockets</span><br><img src="images/icon_rockets.png" alt="Rockets"><br>
                                    <span class="badge badge-pill badge-info"><?=$Rockets;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">Réservoir</span><br><img src="images/icon_baby.png" alt="Réservoir"><br>
                                    <span class="badge badge-pill badge-info"><?=$Baby;?>L</span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">Caméra fixe</span><br><img src="images/icon_cameras.png" alt="Caméra"><br>
                                    <span class="badge badge-pill badge-info"><?=$Camera_high;?></span>
                                </div>
                                <div class="col-4 col-sm-2">
                                    <span class="badge badge-pill badge-default">Caméra</span><br><img src="images/icon_cameras.png" alt="Caméra"><br>
                                    <span class="badge badge-pill badge-info"><?=$Camera_low;?></span>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="row" id="section-stuff">
                        <h3>Equipements</h3>
                        <div class="col-12">
                            <table class="table table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th>Radio</th>
                                    <th>Radar</th>
                                    <th>Navigation</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td><?=$Radio_txt;?></td>
                                    <td><?=$Radar_txt;?></td>
                                    <td><?=$Navi_txt;?></td>
                                </tr>
                            </table>
                        </div>
                    </section>
                    <section class="row" id="section-perfs">
                        <h3>Performances</h3>
                        <div class="col-12">
                            <div class="row">
                                <a data-toggle="collapse" href="#barre-vt"><h4>Vitesse</h4></a>
                                <div class="col-12 collapse" id="barre-vt">
                                    <?=$bar_speed;?>
                                </div>
                            </div>
                            <div class="row">
                                <a data-toggle="collapse" href="#barre-va"><h4>Vitesse ascensionnelle</h4></a>
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
                            <div class="row">
                                <a data-toggle="collapse" href="#barre-at"><h4>Autonomie</h4></a>
                                <div class="col-12 collapse" id="barre-at">
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?=$autonomie_chg_p;?>%" aria-valuenow="<?=$Autonomie_chg;?>" aria-valuemin="0" aria-valuemax="6000">Pleine charge</div>
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?=$autonomie_p;?>%" aria-valuenow="<?=$autonomie;?>" aria-valuemin="0" aria-valuemax="6000">A vide</div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?=$autonomie_ia_p;?>%" aria-valuenow="<?=$autonomie_ia;?>" aria-valuemin="0" aria-valuemax="3000">Chasse/Reco</div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?=$autonomie_long_p;?>%" aria-valuenow="<?=$autonomie_long;?>" aria-valuemin="0" aria-valuemax="3000">Avec réservoir</div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?=$autonomie_s_p;?>%" aria-valuenow="<?=$autonomie_s;?>" aria-valuemin="0" aria-valuemax="3000">Stratégique</div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?=$autonomie_t_p;?>%" aria-valuenow="<?=$autonomie_t;?>" aria-valuemin="0" aria-valuemax="3000">Tactique</div>
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
<?
    echo $footer;
}
else{echo $header;?>
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
        <a class="navbar-brand" href="#">Aube des Aigles</a>
        <div class="collapse navbar-collapse" id="navbar">
        </div>
    </nav>
    <div class="container">
        <div class="header-wrap"></div>
        <div class="card">
            <div class="card-header text-center"><h2>Encyclopédie des avions</h2></div>
            <div class="card-block">
                <form action="avion.php" name="avions" class="form-inline my-2 my-lg-0">
                    <label for="avions" class="col-form-label m-auto"><h4>Avion</h4></label>
                    <select name="avion" id="avion" class="form-control">
                        <?=$avions;?>
                    </select>
                    <input type="submit" value="Choisir" class="btn btn-outline-secondary my-2">
                </form>
                <div class="row"><img src="images/Logo_ada.png" alt="Logo Aube des Aigles" class="img-fluid mx-auto"></div>
            </div>
        </div>
    </div>
<?echo $footer;
}