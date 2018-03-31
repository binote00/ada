<?php
/**
 * Created by PhpStorm.
 * User: JF
 * Date: 22-10-17
 * Time: 11:15
 */

trait DBManager
{
    use DB;
    /**
     * Générateur de champs de requête SELECT ou WHERE
     * @param array|string $fields
     * @param string $concat [, dans le cas d'un select ou d'un update; AND, OR, etc... dans le cas d'un WHERE]
     * @param string $addtxt [suffixe pour chaque élément de la requête, par exemple un =:toto pour chaque champs du where dans une requête préparée]
     * @param bool|int $addnbr [si le suffixe doit être indicé]
     * @return bool|string
     */
    private static function getSelectFields($fields, $concat, $addtxt='', $addnbr=false)
    {
        $return=false;
        if(is_array($fields)){
            $nbr=0;
            foreach($fields as $field){
                if($addnbr){
                    $addtxt.=$nbr;
                }
                if($nbr >0){
                    $return.=$concat.$field.$addtxt;
                }else{
                    $return.=$field.$addtxt;
                }
                $nbr++;
            }
        }else{
            $return=$fields.$addtxt;
        }
        return $return;
    }

    /**
     * Générateur de champs de requête UPDATE
     * @param array|string $fields
     * @param array|string $values
     * @return bool|string
     */
    private static function getUpdateFields($fields, $values)
    {
        $return=false;
        if(is_array($fields) && is_array($values)){
            $return.='(';
            foreach($fields as $field){
                $return.=$field.',';
            }
            $return.=') VALUES (';
            foreach($values as $value){
                $return.='\''.$value.'\',';
            }
            $return.=')';
        }else{
            $return=$fields.'='.$values;
        }
        return $return;
    }

    /**
     * Générateur de bindParam
     * @param resource $result
     * @param array|string $params
     */
    private static function bindParams($result, $params)
    {
        if(is_array($params)){
            $nbr=0;
            foreach($params as $param){
                $result->bindParam('field'.$nbr, $param);
                $nbr++;
            }
        }else{
            $result->bindParam('field', $params);
        }
    }

    /**
     * Générateur de résultat [SELECT FROM WHERE]
     * @param string $table
     * @param array|string $selectField
     * @param array|string $whereField
     * @param array|mixed $whereValue
     * @return PDOStatement
     */
    public static function getData($table, $selectField, $whereField, $whereValue)
    {
        $selectFields = self::getSelectFields($selectField,',');
        $whereFields = self::getSelectFields($whereField, ' AND ','=:field',true);
        $dbh = DB::dbconnect();
        $result = $dbh->prepare("SELECT $selectFields FROM $table WHERE $whereFields");
        self::bindParams($result,$whereValue);
        $result->execute();
        return $result;
    }

    /**
     * Générateur de Mise à jour [UPDATE SET WHERE]
     * @param string $table
     * @param array|string $setField
     * @param array|string $values
     * @param array|string $whereField
     * @param array|mixed $whereValue
     * @return mixed
     */
    public static function setData($table, $setField, $values, $whereField, $whereValue)
    {
        $setFields = self::getUpdateFields($setField, $values);
        $whereFields = self::getSelectFields($whereField, ' AND ','=:field',true);
        $dbh = DB::dbconnect();
        $result = $dbh->prepare("UPDATE $table SET $setFields WHERE $whereFields");
        self::bindParams($result,$whereValue);
        $result->execute();
        return $result;
    }
}