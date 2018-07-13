<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
    include_once('./jfv_include.inc.php');
    include_once('./jfv_txt.inc.php');
    include_once('./jfv_inc_em.php');
    if($Admin){
        dbconnect();
        $resultv = $dbh->query("SELECT ID,Nom FROM Cible ORDER BY Nom");
        while($data = $resultv->fetchObject()){
            $lieux.='<option value="'.$data->ID.'">'.$data->Nom.'</option>';
        }
        echo '<h2>Admin - Lieux</h2>
            <script src="./js/lib/jquery-1.10.2.min.js"></script>
            <script src="./js/admin_cibles.js"></script>
            <form action="#">
                <select name="lieu" id="a_lieuo">'.$lieux.'</select>
            </form>
            <div id="lieu_infos"></div>
            <span id="liens_infos"></span>
            ';
    }
}
