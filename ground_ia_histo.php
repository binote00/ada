<?php
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0) {
    include_once('./jfv_include.inc.php');
    include_once('./jfv_ground.inc.php');
    include_once('./jfv_txt.inc.php');
    $country = $_SESSION['country'];
    $Unit = Insec($_GET['id']);
    $Type = Insec($_GET['type']);

    $menu_cat_list="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list'>Retour</a>";

    dbconnect();
    $resultu=$dbh->prepare("SELECT Pays,Vehicule_ID,Combats FROM Regiment_IA WHERE ID=:reg");
    $resultu->bindValue('reg',$Unit,1);
    $resultu->execute();
    $datau=$resultu->fetchObject();
    $paysu=$datau->Pays;
    $veh=$datau->Vehicule_ID;
    $cbt=$datau->Combats;
    $resultu->closeCursor();

    if($paysu ==$country){
        if($Type ==2){
            $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_ia_histo&type=2&id=".$Unit."'>Attaques aériennes</a>";
            $query="SELECT DISTINCT e.*,DATE_FORMAT(e.`Date`,'%d-%m-%Y à %Hh%i') as `Datec`,l.Nom as Ville FROM gnmh_aubedesaiglesnet4.Events_Ground as e,gnmh_aubedesaiglesnet.Lieu as l 
                WHERE e.Lieu=l.ID AND e.Event_Type IN (381,502,702) AND e.Unit=:reg ORDER BY ID DESC";
        }
        else
            $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_ia_histo&type=2&id=".$Unit."'>Attaques aériennes</a>";
        if($Type ==1 or !$Type){
            $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_ia_histo&type=1&id=".$Unit."'>Combats</a>";
            $query="SELECT DATE_FORMAT(`Date`,'%d-%m-%Y : %Hh%i') as `Date`,Reg_a,Veh_a,Veh_Nbr_a,Pos_a,Reg_b,Veh_b,Veh_Nbr_b,Pos_b,Kills,Place,Distance,Reg_a_ia,Reg_b_ia,l.Nom as Ville
              FROM Ground_Cbt as g,Lieu as l WHERE g.Lieu=l.ID AND (g.Reg_a=:rega OR g.Reg_b=:rega) ORDER BY g.ID DESC";
        }
        else
            $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_ia_histo&type=1&id=".$Unit."'>Combats</a>";

        if($Type ==2){
            $result2=$dbh->prepare($query);
            $result2->bindValue('reg',$Unit,1);
            $result2->execute();
            while($data=$result2->fetchObject()){
                if($data->Event_Type ==381){
                    if($PlayerID)
                        $Pays_win=GetData("Pilote","ID",$data->PlayerID,"Pays");
                    else
                        $Pays_win=GetData("Avion","ID",$data->Avion,"Pays");
                    $liste_bia.='<tr><td>'.$data->Datec.'</td><td>'.$data->Ville.'</td>
                            <td><img src="'.$Pays_win.'20.gif"></td><td>'.GetAvionIcon($data->Avion,0).'</td><td>'.GetVehiculeIcon($data->Avion_Nbr,$paysu,0,0,$Front_Lieu).'</td><td><img src="images/ia_down.png" title="Avion abattu"></td></tr>';
                }
                elseif($data->Event_Type ==502 or $data->Event_Type ==702){
                    if($PlayerID)
                        $Pays_win=GetData("Pilote","ID",$data->PlayerID,"Pays");
                    else
                        $Pays_win=GetData("Avion","ID",$data->Avion,"Pays");
                    $liste_bia.='<tr><td>'.$data->Datec.'</td><td>'.$data->Ville.'</td>
                            <td><img src="'.$Pays_win.'20.gif"></td><td>'.GetAvionIcon($data->Avion,$Pays_win).'</td><td>'.GetVehiculeIcon($data->Pilote_eni,$paysu,0,0,$Front_Lieu).'</td><td>'.$data->Avion_Nbr.' perte(s)</td></tr>';
                }
            }
            $result2->closeCursor();
            if($liste_bia){
                $liste_ia='<h2>Attaques aériennes</h2><div style="overflow:auto; width: 100%;"><table class="table table-striped">
                            <thead><tr><th>Date</th><th>Lieu</th><th>Pays</th><th>Avion</th><th>Troupes</th><th>Rapport</th></tr></thead>'.$liste_bia.'</table></div>';
            }
        }
        elseif($Type ==1){
            $result=$dbh->prepare($query);
            $result->bindValue('rega',$Unit,1);
            $result->execute();
            while($data=$result->fetchObject()) {
                $Dist = false;
                if ($data->Reg_a)
                    $Reg_a = $data->Reg_a . 'e';
                else
                    $Reg_a = 'Garnison/IA';
                if ($data->Reg_b)
                    $Reg_b = $data->Reg_b . 'e';
                else
                    $Reg_b = 'Garnison/IA';
                if ($data->Reg_a_ia)
                    $DB_Reg = 'Regiment_IA';
                else
                    $DB_Reg = 'Regiment';
                if ($data->Reg_b_ia)
                    $DB_Regb = 'Regiment_IA';
                else
                    $DB_Regb = 'Regiment';
                if ($data->Reg_a != $Unit)
                    $Pays_win = GetData($DB_Reg, "ID", $data->Reg_a, "Pays");
                else
                    $Pays_win = $paysu;
                if ($data->Reg_b != $Unit)
                    $Pays_loss = GetData($DB_Regb, "ID", $data->Reg_b, "Pays");
                else
                    $Pays_loss = $paysu;
                $Veh_Nbr_a = $data->Veh_Nbr_a;
                $Veh_Nbr_b = $data->Veh_Nbr_b;
                $Pos_b = GetPosGr($data->Pos_b);
                $Pos_a = GetPosGr($data->Pos_a);
                //$Place=GetPlace($data->Place);
                $Veh_a = GetVehiculeIcon($data->Veh_a, $Pays_win, 0, 0, $Front_Lieu);
                $Veh_b = GetVehiculeIcon($data->Veh_b, $Pays_loss, 0, 0, $Front_Lieu);
                $liste_tia .= '<tr><td>' . $data->Date . '</td><td>'.$data->Ville.'</td><td><img src="images/map/place' . $data->Place . '.png" alt="' . GetPlace($data->Place) . '"></td>
                <td><img src="' . $Pays_win . '20.gif"></td><td>' . $Reg_a . '</td><td>' . $Pos_a . '</td><td>' . $Veh_Nbr_a . ' ' . $Veh_a . '</td>
                <td>' . $Veh_Nbr_b . ' ' . $Veh_b . '</td><td>' . $Pos_b . '</td><td>' . $Reg_b . '</td><td><img src="' . $Pays_loss . '20.gif"></td>
                <td>' . $data->Kills . '</td></tr>';
            }
            if($liste_tia){
                $liste_ia='<h2>Combats</h2><div style="overflow:auto; width: 100%;"><table class="table table-striped">
                            <thead><tr><th>Date</th><th>Lieu</th><th>Zone</th><th>Pays</th><th>Unité</th><th>Position</th><th>Troupes</th>
                            <th>Troupes</th><th>Position</th><th>Unité</th><th>Pays</th><th>Pertes</th></tr></thead>'.$liste_tia.'</table></div>';
            }
        }
    }
    $titre='Historique du '.$Unit.'e Bataillon';
    $panel_unit='<div class="panel panel-war">
                    <div class="panel-heading text-center">'.$Unit.'e</div>
                    <div class="panel-body text-center">
                        <div>'.GetVehiculeIcon($veh,$paysu).'</div>
                        <div class="badge badge-info mt-2">'.$cbt.' Combats</div>
                    </div>
                 </div>';
    $mes=$panel_unit.$menu_cat_list.$liste_ia;
    include_once('./default.php');
}
