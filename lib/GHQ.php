<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 14:31
 */

class GHQ extends Controller
{
    /**
     * @param int $country
     * @return mixed
     */
    public static function getPlanif($country)
    {
        return DBManager::getData('Officier_em', 'Planificateur', 'Pays', $country, '', '', '', 'OBJECT')->Planificateur;
    }

    /**
     * @param int $country
     * @param int $officierid
     * @return bool
     */
    public static function isPlanif($country, $officierid)
    {
        $planif = self::getPlanif($country);
        if($officierid == $planif) {
            return true;
        } else {
            return false;
        }
    }
}