<?php
/**
 * User: JF
 * Date: 25-07-18
 * Time: 19:00
 */

class Cible extends \Ada_Controller\Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public static function getById($id)
    {
        return parent::getById($id);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    public static function getByField($field, $value)
    {
        return parent::getByField($field, $value);
    }
}