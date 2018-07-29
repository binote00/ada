<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 14:31
 */

class GHQ
{
    /**
     * @param int $country
     * @return mixed
     */
    public static function getByCountry($country)
    {
        return DBManager::getData('Officier_em', '*', 'Pays', $country, '', '', '', 'OBJECT');
    }

    /**
     * @param int $country
     * @param int $officier
     * @return bool
     */
    public static function isPlanif($country, $officier)
    {
        $ghq = self::getByCountry($country);
        if($officier == $ghq->Planificateur) {
            return true;
        } else {
            return false;
        }
    }
}