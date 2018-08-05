<?php
/**
 * User: JF
 * Date: 25-07-18
 * Time: 19:33
 */

function getRetreat($country, $front, $division, $division_base = false)
{
    if (!$division) {
        return Get_Retraite($front, $country, 40);
    } elseif (!$division_base) {
        return Division::getById($division)->Base;
    } else {
        return $division_base;
    }
}

/**
 * @param int $regiment_id
 * @return array
 */
function getFlags($regiment_id)
{
    $today = getdate();
    $timers = Regiment_IA::getCombatTimers($regiment_id);
    if ($today['mday'] > $timers->Jour + 1)
        $Combat_flag = false;
    elseif ($today['year'] > $timers->Year_a)
        $Combat_flag = false;
    elseif ($today['mon'] > $timers->Mois)
        $Combat_flag = false;
    elseif ($today['mday'] != $timers->Jour && $today['hours'] >= $timers->Heure)
        $Combat_flag = false;
    else
        $Combat_flag = true;
    if ($today['mday'] > $timers->Jour_m + 1)
        $Move_flag = false;
    elseif ($today['year'] > $timers->Year_m)
        $Move_flag = false;
    elseif ($today['mon'] > $timers->Mois_m)
        $Move_flag = false;
    elseif ($today['mday'] != $timers->Jour_m && $today['hours'] >= $timers->Heure_m)
        $Move_flag = false;
    else
        $Move_flag = true;

    return [$Combat_flag, $Move_flag];
}

function getMatosAndSkills($Unitid, $Veh, $Date_Campagne)
{
    $matos_modal_txt = '';
    $con = dbconnecti(1);
    $result_s=mysqli_query($con,"SELECT ID,Nom,Rang,Infos FROM gnmh_aubedesaiglesnet1.Skills_r");
    $result_m=mysqli_query($con,"SELECT ID,Nom,Infos,Service FROM gnmh_aubedesaiglesnet1.Skills_m");
    mysqli_close($con);
    //Matos & Skills
    $list_matos=Get_Matos_List($Veh->Categorie,$Veh->Type,$Veh->mobile,$Veh->Arme_AT);
    if($result_s)
    {
        while($data=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
        {
            $Skills_r[$data['ID']]="<b>".$data['Nom']." [".$data['Rang']."]</b><br>".$data['Infos'];
        }
        mysqli_free_result($result_s);
    }
    if($result_m)
    {
        while($datam=mysqli_fetch_array($result_m,MYSQLI_ASSOC))
        {
            $Reg_matos[$datam['ID']]='<b>'.$datam['Nom'].'</b><br>'.$datam['Infos'];
            if($datam['Service'] <=$Date_Campagne and in_array($datam['ID'],$list_matos)){
                $matos_modal_txt.='<tr><td><a href="ground_em_ia_matos_do.php?reg='.$Unitid.'&matos='.$datam['ID'].'"><img src="images/skills/skille'.$datam['ID'].'.png"></a><br>'.$datam['Nom'].'</td><td>'.$datam['Infos'].'</td></tr>';
            }
        }
        mysqli_free_result($result_m);
    }
    return '<div class="modal fade" id="modal-ravit" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Gestion du matériel
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </h2>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table"><thead><tr><th>Equipement</th><th>Infos</th></tr></thead>'.$matos_modal_txt.'</table>
                                    </div>
                                </div>
                            </div>
                        </div>';
}
