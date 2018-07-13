<?php
/**
 * Created by PhpStorm.
 * User: Binote
 * Date: 21-09-17
 * Time: 09:57
 */

require_once('./jfv_inc_sessions.php');
require_once('./jfv_include.inc.php');

$Nation_IA=0;
if(is_array($nations_alert)){
    if(count($nations_alert) >1)
        $Nation_IA = $nations_alert[0];
}
if($Nation_IA >0 and $Cible >0 and $Longitude_c and $Latitude_c){
    $lat_min = $Latitude_c-10;
    $lat_max = $Latitude_c+10;
    $long_min = $Longitude_c -20;
    $long_max = $Longitude_c +20;
    dbconnect();
    $result_ia = $dbh->query("SELECT COUNT(*) FROM Pilote_IA WHERE Couverture=$Cible AND Cible=$Cible AND Avion >0 AND Alt <=3000");
    if($result_ia->fetchColumn()){
        $mail_msg = $Cible.' est déjà couverte par des chasseurs';
    }
    else{
        $result = $dbh->prepare("SELECT u.ID,u.Nom,(a.Autonomie/2) as Autonomie,l.Nom as Ville,l.Latitude,l.Longitude,u.Avion1
                            FROM Unit as u,Lieu as l,Avion as a WHERE u.Base=l.ID AND u.Avion1=a.ID
                            AND (l.Latitude BETWEEN $lat_min AND $lat_max) AND (l.Longitude BETWEEN $long_min AND $long_max) 
                            AND u.Pays=:country AND u.Type=1 AND (Avion1_Nbr+Avion2_Nbr+Avion3_Nbr >0) AND u.Mission_IA=0 AND l.Meteo >-50
                            ");
        $result->bindValue('country',$Nation_IA,1);
        $result->execute();
        while($data = $result->fetchObject()){
            $Distance_ia=GetDistance(0,0,$data->Longitude,$data->Latitude,$Longitude_c,$Latitude_c);
            if($Distance_ia[0] < $data->Autonomie){
                $result2 = $dbh->prepare("UPDATE Pilote_IA SET Couverture=:cible,Alt=3000,Cible=:cible,Avion=:avion,Moral=100,Courage=100,Endurance=0 WHERE Unit=:unit");
                $result2->bindValue('cible',$Cible,1);
                $result2->bindValue('avion',$data->Avion1,1);
                $result2->bindValue('unit',$data->ID,1);
                $result2->execute();
                $mail_msg .= $data->Nom.' a envoyé ses pilotes en couverture sur la ville '.$data->Ville;
                break;
            }
            else{
                $mail_msg.= $data->Nom.' est hors de portée de '.$data->Ville;
            }
        }
    }
    mail('binote@hotmail.com','DEBUG : COUVERTURE IA',$mail_msg);
}