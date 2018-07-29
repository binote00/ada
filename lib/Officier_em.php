<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 14:29
 */

class Officier_em
{
    /**
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return DBManager::getData('Officier_em', '*', 'ID', $id, '', '', '', 'OBJECT');
    }
}