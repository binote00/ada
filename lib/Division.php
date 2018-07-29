<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 15:36
 */

class Division
{
    /**
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return DBManager::getData('Division', '*', 'ID', $id, '', '', '', 'OBJECT');
    }
}