<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
    include_once('./jfv_include.inc.php');
    dbconnect();
    $resulta = $dbh->prepare("SELECT Admin FROM Joueur WHERE ID=:accountid");
    $resulta->bindValue('accountid',$_SESSION['AccountID'],1);
    $resulta->execute();
    $dataa = $resulta->fetchObject();
    if($dataa->Admin){
        echo '<h1>Admin - Véhicules</h1>
            <h2>Ajouter un véhicule</h2>
            <form action="">
                <input type="button" class="btn btn-success" value="Ajouter">
            </form>
            <h2>Modifier un véhicule existant</h2>
            <form action="#">
                <label for="pays">Nation</label>
                <select name="pays" id="a_pays" class="form-control"><option value="">Choisissez...</option><option value="1">Allemagne</option><option value="2">Angleterre</option><option value="4">France</option><option value="6">Italie</option><option value="9">Japon</option><option value="8">URSS</option><option value="7">USA</option></select>
                <label for="veh">Modèle</label>
                <select name="veh" id="a_lieuo" class="form-control"></select>
            </form>
            <h3>Détail du modèle</h3>
            <div id="cible_infos"></div>
            ';
    }
}