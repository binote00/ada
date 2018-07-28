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
