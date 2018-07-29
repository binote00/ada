<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 13:42
 */

class Joueur
{
    /**
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return DBManager::getData('Joueur', '*', 'ID', $id, '', '', '', 'OBJECT');
    }
}