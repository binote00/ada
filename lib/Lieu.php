<?php
/**
 * User: JF
 * Date: 25-07-18
 * Time: 19:01
 */

class Lieu extends Controller
{
    /**
     * @param int $nation
     * @return array|bool|int|mixed|PDOStatement
     */
    public static function getRevendicationScoreByNation($nation)
    {
        $sql = "SELECT COUNT(*) AS countrev FROM Lieu WHERE Flag = ? AND Zone <> 6";
        return DBManager::getDataSQL($sql, $nation, 'OBJECT')->countrev;
    }

    /**
     * @param int $nation
     * @return mixed
     */
    public static function getDcaMaxByNation($nation)
    {
        return self::getSelectByField('Flag', $nation, 'SUM(ValeurStrat) AS sumval')->sumval
            + (self::getRevendicationScoreByNation($nation) * 2);
    }

    /**
     * @param int $nation
     * @return mixed
     */
    public static function getDcaByNation($nation)
    {
        return self::getSelectByField('Flag', $nation, 'SUM(DefenseAA_temp) AS sumdca')->sumdca;
    }
}