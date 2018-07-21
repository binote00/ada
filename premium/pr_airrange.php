<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
$PlayerID = $_SESSION['PlayerID'];
$OfficierEMID = $_SESSION['Officier_em'];
if ($PlayerID > 0 or $OfficierEMID > 0) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    include_once __DIR__ . '/../view/menu_infos.php';
    $Premium = GetData("Joueur", "ID", $_SESSION['AccountID'], "Premium");
    if ($Premium) {
        include_once __DIR__ . '/../inc/jfv_avions.inc.php';
        $country = $_SESSION['country'];
        dbconnect();
        $resultv = $dbh->prepare("SELECT ID,Nom,LongPiste,Latitude,Longitude FROM Lieu WHERE Flag=:country AND BaseAerienne >0 ORDER BY Nom ASC");
        $resultv->bindValue('country', $country, 1);
        $resultv->execute();
        while ($datav = $resultv->fetchObject()) {
            $lieux .= '<option value="' . $datav->ID . '">' . $datav->Nom . ' [' . $datav->LongPiste . 'm]</option>';
        }
        $result = $dbh->prepare("SELECT ID,Nom,Type,Autonomie,Masse,Bombe,Bombe_Nbr,Puissance FROM Avion WHERE Pays=:country ORDER BY Nom ASC");
        $result->bindValue('country', $country, 1);
        $result->execute();
        while ($data = $result->fetchObject()) {
            $Autonomie_s = 0;
            $Autonomie_t = 0;
            $Autonomie_l = 0;
            $Autonomie = floor(($data->Autonomie / 2) - 200);
            if ($Autonomie < 50) $Autonomie = 50;
            $auto .= '<option value="' . $Autonomie . '">' . $data->Nom . '</option>';
            if ($data->Type == 1 or $data->Type == 2 or $data->Type == 3 or $data->Type == 4 or $data->Type == 7 or $data->Type == 10 or $data->Type == 11 or $data->Type == 12) {
                $Massef_s = $data->Masse + ($data->Bombe * $data->Bombe_Nbr);
                $Massef_t = $data->Masse + $data->Bombe;
                $Poids_Puiss_ori = $data->Masse / $data->Puissance;
                $Poids_Puiss_s = $Massef_s / $data->Puissance;
                $Poids_Puiss_t = $Massef_t / $data->Puissance;
                $Autonomie_s = round($data->Autonomie - (($Poids_Puiss_s - $Poids_Puiss_ori) * ($Massef_s / 10)));
                $Autonomie_t = round(($data->Autonomie / 2) - (($Poids_Puiss_t - $Poids_Puiss_ori) * ($Massef_t / 10)));
                if ($data->Type == 1 or $data->Type == 3 or $data->Type == 4 or $data->Type == 12) {
                    $Array_Mod = GetAmeliorations($data->ID);
                    $Autonomie_l = floor($Autonomie + ($Array_Mod[18] / 2));
                    $auto .= '<option value="' . $Autonomie_l . '">' . $data->Nom . ' - [Avec réservoirs]</option>';
                }
                $auto .= '<option value="' . $Autonomie_t . '">' . $data->Nom . ' - [Bomb. Tac]</option>';
                if ($data->Type == 2 or $data->Type == 7 or $data->Type == 11)
                    $auto .= '<option value="' . $Autonomie_s . '">' . $data->Nom . ' - [Bomb. Strat]</option>';
            }
        }
        echo '<div class="panel panel-war">
                <div class="panel-heading">Portée des aérodromes</div>
                <div class="panel-body">
                    <form action="carte_ground.php" target="_blank">
                        <label for="cible">Aérodrome</label>
                        <select name="cible" id="cible" class="form-control">
                            <option value="">Aucun</option>' . $lieux . '
                        </select>
                        <label for="range">Avion</label>
                        <select name="range" id="range" class="form-control">
                            <option value="0">Aucun</option>' . $auto . $auto_long . $auto_tac . $auto_strat . '
                        </select>
                        <input type="hidden" name="mode" value="17">
                        <input type="submit" class="btn btn-default" value="Valider">
                    </form>
                </div>
            </div>';
    }
}
