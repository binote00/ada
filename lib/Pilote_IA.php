<?php
/**
 * User: JF
 * Date: 12-08-18
 * Time: 07:45
 */

class Pilote_IA extends Controller
{
    /**
     * @param $unitid
     * @return array|bool|int|mixed|PDOStatement
     */
    public static function resetPilotsByUnit($unitid)
    {
        return DBManager::getDataSQL("UPDATE Pilote_IA SET Avion=0,Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Alt=0,Task=0 WHERE Unit=?", $unitid, 'COUNT');
    }

}