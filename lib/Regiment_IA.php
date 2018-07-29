<?php
/**
 * User: JF
 * Date: 22-07-18
 * Time: 15:34
 */

class Regiment_IA extends \Ada_Controller\Controller
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

    public static function getCombatTimers($id)
    {
        $select = "Atk_time,
                    DATE_FORMAT(Atk_time,'%e') as Jour,
                    DATE_FORMAT(Atk_time,'%Hh%i') as Heure,
                    DATE_FORMAT(Atk_time,'%m') as Mois,
                    DATE_FORMAT(Atk_time,'%Y') as Year_a,
                    Move_time,
                    DATE_FORMAT(Move_time,'%e') as Jour_m,
                    DATE_FORMAT(Move_time,'%Hh%i') as Heure_m,
                    DATE_FORMAT(Move_time,'%m') as Mois_m,
                    DATE_FORMAT(Move_time,'%Y') as Year_m";
        return self::getSelectByField('ID', $id, $select);
    }
}