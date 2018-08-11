<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 14:32
 */

class Pays extends Controller
{
    /**
     * @param int $id
     * @param int $front
     * @return mixed
     */
    public static function getByIdAndByFront($id, $front)
    {
        return DBManager::getData('Pays', '*', ['Pays_ID', 'Front'], [$id, $front], '', '', '', 'OBJECT');
    }

    /**
     * @param int $id
     * @return mixed
     */
    public static function getFaction($id)
    {
        return self::getSelectByField('ID', $id, 'Faction')->Faction;
    }

}