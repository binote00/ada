<?php
require_once './jfv_inc_sessions.php';
$OfficierID = $_SESSION['Officier_em'];
if ($OfficierID > 0) {
    //$country=$_SESSION['country'];
    include_once './jfv_include.inc.php';
    //include_once './jfv_txt.inc.php';
    if (!$Front) $Front = GetData("Officier_em", "ID", $OfficierID, "Front");
    /*$Cible=GetData("Regiment","Officier_ID",$OfficierID,"Lieu_ID");
    $Latitude_front=GetData("Lieu","ID",$Cible,"Latitude");
    $Longitude_front=GetData("Lieu","ID",$Cible,"Longitude");*/
    /*$mes="<h2>Cartes logistiques</h2><p>Cartes référençant les dépôts, ainsi que les gares et/ou les ports.</p>";
    if($Front ==1 or $Front ==4)
    {
        $mes.="<table class='table'><thead><tr><th>Global</th><th>Nord</th><th>Sud</th><th>Arctique</th></tr></thead><tr>
        <td><a href='carte_rail.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Globale'></a></td>
        <td><a href='carte_rail_nord_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Nord'></a></td>
        <td><a href='carte_rail_sud_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Sud'></a></td>
        <td><a href='carte_arctic.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Arctique'></a></td></tr></table>";
        $mes.="<h2>Cartes des opérations</h2>
        <table class='table'><thead><tr><th>Front Nord-Est</th><th>Front Sud-Est</th></tr></thead><tr>
        <td><a href='carte_nord_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Nord'></a></td>
        <td><a href='carte_sud_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Est'></a></td></tr></table>";
    }
    elseif($Front ==5)
    {
        $mes.="<table class='table'><thead><tr><th>Nord</th><th>Arctique</th></tr></thead><tr>
        <td><a href='carte_rail_nord_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Nord'></a></td>
        <td><a href='carte_arctic.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Arctique'></a></td></tr></table>";
    }
    elseif($Front ==2)
    {
        $mes.="<table class='table'><thead><tr><th>Méditerranée</th><th>Océan Indien</th></tr></thead><tr>
        <td><a href='carte_rail_med.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Med'></a></td>
        <td><a href='carte_arab.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Indien'></a></td></tr></table>";
        $mes.="<h2>Cartes des opérations</h2>
        <table class='table'><thead><tr><th>Méditerranée Ouest</th><th>Méditerranée Est</th></tr></thead><tr>
        <td><a href='carte_med.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Ouest'></a></td>
        <td><a href='carte_med_est.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Est'></a></td></tr></table>";
    }
    elseif($Front ==3)
    {
        $mes.="<table class='table'><thead><tr><th>Océan Pacifique</th><th>Océan Indien</th><th>Amérique du Nord</th></tr></thead><tr>
        <td><a href='carte_pacifique.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Pacifique'></a></td>
        <td><a href='carte_arab.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Indien'></a></td></tr></table>
        <td><a href='carte_usa.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte des USA'></a></td></tr></table>";
        $mes.="<h2>Carte des opérations</h2>
        <table class='table'><thead><tr><th>Océan Pacifique</th></tr></thead><tr>
        <td><a href='carte_pacifique.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Pacifique'></a></td></tr></table>";
    }
    else
    {
        $mes.="<table class='table'><thead><tr><th>Ouest</th><th>Atlantique</th><th>Arctique</th></tr></thead><tr>
        <td><a href='carte_rail_ouest.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Ouest'></a></td>
        <td><a href='carte_atlantic.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Atlantique'></a></td>
        <td><a href='carte_arctic.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Arctique'></a></td></tr></table>";
        $mes.="<h2>Carte des opérations</h2>
        <table class='table'><thead><tr><th>Europe de l'Ouest</th></tr></thead><tr>
        <td><a href='carte_ouest.php?longit=".$Longitude_front."&latit=".$Latitude_front."' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte Ouest'></a></td></tr></table>";
    }*/
    echo "<iframe width='100%' height='800' src='./carte_ground.php?map=" . $Front . "&mode=8&frame=1'></iframe>";
    /*$mes.="<a href='carte_ground.php?map=".$Front."&mode=1' class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Accéder à la carte du front'></a>";
    $titre="Cartes";
    $img="<img src='images/wall_map.jpg'>";
    include_once'./default.php';*/
}