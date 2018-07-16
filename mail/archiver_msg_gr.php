<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
if (isset($_SESSION['AccountID'])) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    include_once __DIR__ . '/../view/menu_messagerie_gr.php';
    $ID = Insec($_POST['msg']);
    if ($ID > 0) {
        $con = dbconnecti(3);
        $ok_up = mysqli_query($con, "UPDATE Ada_Messages SET Archive=1 WHERE ID = $ID");
        mysqli_close($con);
        echo "<p>Message effacé avec succès!</p>";
    } else
        echo "<p>Erreur!</p>";
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";