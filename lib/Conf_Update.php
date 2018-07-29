<?php
/**
 * User: JF
 * Date: 25-07-18
 * Time: 18:52
 */

class Conf_Update
{
    /**
     * @return mixed
     */
    public static function getCampaignDate()
    {
        return DBManager::getData('Conf_Update', 'Date', 'ID',2, '', '', '', 'OBJECT')->Date;
    }
}