<?php
/**
 * User: JF
 * Date: 12-08-18
 * Time: 08:11
 */

class Debug
{
    /**
     * @param string $title
     * @param string $msg
     */
    public static function mail($title, $msg)
    {
        $msg = $_SERVER['PHP_SELF'] . ' from ' . $_SERVER['HTTP_REFERER'] . '\n' . $msg;
        mail(EMAIL_LOG, 'Aube des Aigles: ' . $title, $msg);
    }
}