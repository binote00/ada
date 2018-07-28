<?php
/**
 * User: Binote
 * Date: 30-11-17
 * Time: 10:05
 */

require_once 'jfv_inc_sessions.php';
include_once 'jfv_include.inc.php';
if ($_SESSION['AccountID'] == 1) {
    include_once 'jfv_multi.inc.php';
    include_once 'view/v_admin.php';
} elseif ($_SESSION['Encodage'] == 1) {
    include_once 'jfv_multi.inc.php';
    include_once 'view/v_anim.php';
}