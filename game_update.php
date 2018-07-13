<?
require_once('./jfv_inc_sessions.php');
echo "<h1>L'Aube des Aigles est actuellement en cours de mise à jour...accessible très bientôt!</h1>";
session_unset();
session_destroy();
include_once('./index.php');
?>