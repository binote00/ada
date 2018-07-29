<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 14:32
 */

class Pays
{
    /**
     * @param int $id
     * @return mixed
     */
    public static function getById($id)
    {
        return DBManager::getData('Pays', '*', 'ID', $id, '', '', '', 'OBJECT');
    }

    /**
     * @param int $id
     * @param int $front
     * @return mixed
     */
    public static function getByIdAndByFront($id, $front)
    {
        return DBManager::getData('Pays', '*', ['ID', 'Front'], [$id, $front], '', '', '', 'OBJECT');
    }

}