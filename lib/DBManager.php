<?php
/**
 *
 * User: JF
 * Date: 22-10-17
 * Time: 11:15
 */

/**
 * Générateur de requêtes
 */
trait DBManager
{
    use DB;

    /**
     * Générateur de champs de requête SELECT ou WHERE
     *
     * @param array|string $fields
     * @param string $concat [, dans le cas d'un select ou d'un update; AND, OR, etc... dans le cas d'un WHERE]
     * @param string $addtxt [suffixe pour chaque élément de la requête, par exemple un =:toto pour chaque champs du where dans une requête préparée]
     * @param bool|int $addnbr [si le suffixe doit être indicé]
     * @return bool|string
     */
    private static function getSelectFields($fields, $concat, $addtxt = '', $addnbr = false)
    {
        $return = false;
        if (is_array($fields)) {
            $nbr = 0;
            $addtxtfinal = $addtxt;
            foreach ($fields as $field) {
                if ($addnbr) {
                    $addtxtfinal = $addtxt . $nbr;
                }
                if ($nbr > 0) {
                    if ($field == 'NOW()') {
                        $return .= $concat . $field . '=NOW()';
                    } else {
                        $return .= $concat . $field . $addtxtfinal;
                    }
                } else {
                    $return .= $field . $addtxtfinal;
                }
                $nbr++;
            }
        } else {
            $return = $fields . $addtxt;
        }
        return $return;
    }

    /**
     * Générateur de champs de requête INSERT
     *
     * @param array|string $fields
     * @return bool|string
     */
    private static function getInsertFields($fields)
    {
        $return = false;
        $values = false;
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $return .= ',' . $field;
                if ($field == 'NOW()') {
                    $values .= ',NOW()';
                } else {
                    $values .= ',?';
                }
            }
            $return = '(' . substr($return, 1) . ') VALUES (' . substr($values, 1) . ')';
        } else {
            $return = $fields . '=' . $values;
        }
        return $return;
    }

    /**
     * Générateur de bindParam
     *
     * @param PDOStatement $result
     * @param array|string $params
     * @param bool $update
     */
    private static function bindParams($result, $params, $update = false)
    {
        $tag = ':field';
        if ($update) {
            $tag = ':data';
        }
        if (is_array($params)) {
            $nbr = 0;
            foreach ($params as &$param) {
                if ($param != 'NOW()') {
                    $result->bindParam($tag . $nbr, $param, 2);
                }
                $nbr++;
            }
        } else {
            $result->bindParam($tag, $params, 2);
        }
    }

    /**
     * Générateur de bindValue [utilisez des points d'interrogation comme paramètre fictif]
     *
     * @param PDOStatement $result
     * @param array|string $params
     */
    private static function bindValues($result, $params)
    {
        if (is_array($params)) {
            $nbr = 1;
            foreach ($params as $param) {
                if ($param != 'NOW()') {
                    $result->bindValue($nbr, $param, 2);
                }
                $nbr++;
            }
        } else {
            $result->bindValue(1, $params, 2);
        }
    }

    /**
     * @param array|string $selectField
     * @return bool|string
     */
    private static function returnSelectFields($selectField)
    {
        if ($selectField == '*') {
            return '*';
        } else {
            return self::getSelectFields($selectField, ',');
        }
    }

    /**
     * Générateur de résultat [SELECT FROM WHERE]
     *
     * @param string $table
     * @param array|string $selectField
     * @param array|string $whereField
     * @param array|mixed $whereValue
     * @param string $order
     * @param string $orderBy
     * @param mixed $limit
     * @param string $fetch [object or array or multi]
     * @return mixed
     */
    public static function getData($table, $selectField, $whereField, $whereValue, $order = '', $orderBy = 'DESC', $limit = '', $fetch = 'RESULT')
    {
        $whereFields = '';
        if ($order) {
            $order = ' ORDER BY ' . $order . ' ' . $orderBy;
        }
        if (is_array($limit)) {
            if ($limit[0] < 0) $limit[0] = 0;
            $order .= ' LIMIT ' . $limit[0] . ', ' . $limit[1];
        } elseif ($limit) {
            $order .= ' LIMIT ' . $limit;
        }
        $dbh = DB::connect();
        $selectFields = self::returnSelectFields($selectField);
        if ($whereField && $whereValue) {
            $whereFields = "WHERE " . self::getSelectFields($whereField, ' AND ', '=:field', true);
        }
        $query = "SELECT $selectFields FROM $table " . $whereFields . $order;
        //echo '[GETDATA-QUERY]'.$query.' / '.print_r($whereValue, true).' /';
        $result = $dbh->prepare($query);
        if ($whereValue) {
            self::bindParams($result, $whereValue);
        }
        $result->execute();
        if ($fetch == 'OBJECT' || $selectField == '*') {
            $data = $result->fetchObject();
        } elseif ($fetch == 'CLASS') {
            $data = $result->fetchAll(PDO::FETCH_CLASS, ucfirst($table));
        } elseif ($fetch == 'COUNT') {
            $data = $result->rowCount();
        } elseif ($fetch == 'ALL') {
            $data = $result->fetchAll();
        } else {
            $data = $result;
        }
        return $data;
    }

    /**
     * @param string $sql [utilisez des points d'interrogation comme paramètre fictif]
     * @param array|string $params
     * @param string $fetch
     * @return array|bool|int|mixed|PDOStatement
     */
    public static function getDataSQL($sql, $params = '', $fetch = 'RESULT')
    {
        $dbh = DB::connect();
        $result = $dbh->prepare($sql);
        if ($params) {
            self::bindValues($result, $params);
        }
        $result->execute();
        if ($fetch == 'OBJECT') {
            $data = $result->fetchObject();
        } elseif ($fetch == 'COUNT') {
            $data = $result->rowCount();
        } elseif ($fetch == 'ALL') {
            $data = $result->fetchAll();
        } else {
            $data = $result;
        }
        return $data;
    }

    /**
     * Générateur de Mise à jour [UPDATE SET WHERE OR INSERT INTO]
     *
     * @param string $table
     * @param array|string $setField
     * @param array|string $values
     * @param array|string $whereField
     * @param array|mixed $whereValue
     * @return mixed
     */
    public static function setData($table, $setField, $values, $whereField = '', $whereValue = '')
    {
        $dbh = DB::connect();
        if ($whereField and $whereValue) {
            $setFields = self::getSelectFields($setField, $values, '=:data', true);
            $whereFields = self::getSelectFields($whereField, ' AND ', '=:field', true);
            $query = "UPDATE $table SET $setFields WHERE $whereFields";
            $result = $dbh->prepare($query);
            self::bindParams($result, $whereValue);
            self::bindParams($result, $values, true);
            $result->execute();
            $return = $result->rowCount();
        } else {
            $exist = self::getData($table, 'id', $setField, $values, '', 'DESC', '', 'COUNT');
            if (!$exist) {
                $setFields = self::getInsertFields($setField);
                $query = "INSERT INTO " . $table . $setFields;
                //echo $query;
                $result = $dbh->prepare($query);
                $result->execute($values);
                $return = $dbh->lastInsertId();
            } else {
                $return = false;
            }
        }
        return $return;
    }

    /**
     * @param string $table
     * @param string $setField
     * @param int $modif
     * @param string $operator [+,-,*,/]
     * @param string $whereField
     * @param string $whereValue
     * @return int
     */
    public static function updateData($table, $setField, $modif, $operator = '+', $whereField = '', $whereValue = '')
    {
        $dbh = DB::connect();
        $whereFields = self::getSelectFields($whereField, ' AND ', '=:field', true);
        $query = "UPDATE $table SET $setField=:data".$operator."$modif WHERE $whereFields";
        $result = $dbh->prepare($query);
        self::bindParams($result, $whereValue);
        self::bindParams($result, $setField, true);
        $result->execute();
        return $result->rowCount();
    }

    /**
     * Convertisseur de date SQL
     *
     * @param string $date
     * @param string $mode
     * @param string $alias
     * @return string
     */
    public static function SQLDateFormat($date, $mode = 'LOG', $alias = '')
    {
        if (!$alias) {
            $alias = $date;
        }
        if ($mode == 'BIRTH') {
            return 'DATE_FORMAT(' . $date . ',\'%d-%m-%Y\') AS ' . $alias;
        } else {
            return 'DATE_FORMAT(' . $date . ',\'%d-%m %H:%i\') AS ' . $alias;
        }
    }
}