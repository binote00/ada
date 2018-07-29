<?php
/**
 * User: JF
 * Date: 25-07-18
 * Time: 19:09
 */

class Controller
{
    use DBManager;

    protected static function getClassName()
    {
        return get_called_class();
    }

    /**
     * @param $id
     * @return mixed
     */
    protected static function getById($id)
    {
        return DBManager::getData(self::getClassName(), '*', 'ID', $id, '', '', '', 'OBJECT');
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    protected static function getByField($field, $value)
    {
        return DBManager::getData(self::getClassName(), '*', $field, $value, '', '', '', 'OBJECT');
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param string $select
     * @return mixed
     */
    protected static function getSelectByField($field, $value, $select)
    {
        return DBManager::getData(self::getClassName(), $select, $field, $value, '', '', '', 'OBJECT');
    }

    /**
     * @param int $id
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    protected static function setById($id, $field, $value)
    {
        return DBManager::setData(self::getClassName(), $field, $value, 'ID', $id);
    }
}