<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 14:26
 */

class Officier
{
    /**
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return DBManager::getData('Officier', '*', 'ID', $id, '', '', '', 'OBJECT');
    }
}