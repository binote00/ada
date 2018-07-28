<?php
/**
 * User: JF
 * Date: 28-07-18
 * Time: 12:08
 */

if($ground_em_ia_naval == true) //Navire
{
    $Long_range=false;
    $Dist_max=500;
    if(!$Vehicule_Nbr)
        $Placements='<span class="text-danger">Remorqué</span>';
    else
        $Placements='<span class="text-primary">En mer</span>';
    if(!$Move and $Meteo >-75 and $Lieu and $Autonomie >0) //Déplacement
    {
        if($Position !=25)
        {
            $con=dbconnecti();
            $resultsmoke=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu' AND r.Position=37 AND c.Arme_Art>0 AND r.Vehicule_Nbr>0"),0);
            //$resulti2=mysqli_query($con,"SELECT COUNT(*),MAX(c.Vitesse) FROM Regiment as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Position=27 AND c.Type>14 AND r.Vehicule_Nbr>0 AND c.Vitesse>35 AND r.HP>c.HP/2");
            $Enis_Interdiction=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Position=27 AND c.Type>14 AND r.Vehicule_Nbr>0 AND c.Vitesse>35 AND r.HP>c.HP/2"),0);
            mysqli_close($con);
            if($resultsmoke)$smoke_txt="<br>Des navires alliés tenteront de créer un écran de fumée pour tenter de forcer le passage.";
            if($Enis_Interdiction and $Vehicule_Nbr >0)
                $output_dest="<div class='alert alert-danger'>Des navires ennemis en interdiction tentent d'empêcher tout déplacement".$smoke_txt."!</div>";
            if($G_Treve or ($G_Treve_Med and $Front ==2) or ($G_Treve_Est_Pac and ($Front ==1 or $Front ==4 or $Front ==3)))$query_treve=" AND Flag='$country'";
        }
        if($Lieu ==1984 or $Lieu ==1986 or $Lieu ==1987 or $Lieu ==1988) //Cote Espagnole
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE (Port >0 OR Zone=6 OR Plage=1) AND Latitude <50 AND Longitude <-5.35 AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Lieu ==198 or $Lieu ==199 or $Lieu ==449 or $Lieu ==459 or $Lieu ==500 or $Lieu ==507 or $Lieu ==701 or $Lieu ==750 or $Lieu ==819 or $Lieu ==1113 or $Lieu ==1180 or $Lieu ==1181 or $Lieu ==1562 or $Lieu ==2550 or $Lieu ==2854 or $Lieu ==2909 or $Lieu ==2910 or $Lieu ==2924 or $Lieu ==2925) //Mer Adriatique
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE ID IN(198,199,449,459,500,506,507,701,750,819,1113,1180,1181,1562,2550,2854,2909,2910,2924,2925) AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Lieu ==269 or $Lieu ==494 or $Lieu ==593 or $Lieu ==731 or $Lieu ==942 or $Lieu ==1123 or $Lieu ==1609 or $Lieu ==2302 or $Lieu ==2551) //Mer Irlande
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE ID IN(269,494,495,593,731,942,1123,1154,1609,2302,2551) AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Lieu ==2026 or $Lieu ==2016 or $Lieu ==2011 or $Lieu ==2030 or $Lieu ==2031 or $Lieu ==2027 or $Lieu ==2028) //Corne Afrique
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >32 AND Longitude <46 AND Latitude <30 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Lieu ==279 or $Lieu ==280 or $Lieu ==295 or $Lieu ==296 or $Lieu ==312 or $Lieu ==470 or $Lieu ==488 or $Lieu ==489 or $Lieu ==490 or $Lieu ==592 or $Lieu ==729 or $Lieu ==730 or $Lieu ==880 or $Lieu ==952 or $Lieu ==1138 or $Lieu ==1364 or $Lieu ==1614 or $Lieu ==2540 or $Lieu ==3386) //Pas de Mer Irlande depuis l'est de l'angleterre
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE ((Longitude >0.53 AND Longitude <14 AND Latitude >44 AND Latitude <51.39) OR (Longitude >-1.62 AND Longitude <14 AND Latitude >=51.39 AND Latitude <55) OR (Longitude >-4 AND Longitude <14 AND Latitude >44 AND Latitude >=55)) AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Lieu ==912) //Port-Saïd vers Med-Est et Suez
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >27 AND Latitude <35 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',2016,2026) ORDER BY Nom ASC";
        elseif($Lieu ==2015) //Suez vers Port-Saïd et Mer Rouge
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >32 AND Latitude <31.3 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu') ORDER BY Nom ASC";
        elseif($Front ==2)
        {
            if($Longitude <15.6 and $Latitude <45.3) //Pas de Mer Adriatique si on vient de l'ouest du détroit de Messine
                $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-5.51 AND Longitude <73 AND Latitude <44.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',198,199,449,458,459,500,505,506,507,701,750,819,1113,1180,1181,1562,2550) ORDER BY Nom ASC";
            elseif($country ==2 or $country ==5 or $country ==7){
                if($Latitude >30.35)
                    $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-5.51 AND Longitude <40 AND Latitude <44.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',262,2015,2026) ORDER BY Nom ASC";
                else
                    $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-5.51 AND Longitude <73 AND Latitude <44.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',262) ORDER BY Nom ASC";
            }
            else
                $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-5.51 AND Longitude <36 AND Latitude <44.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',262,2015,2016,2026) ORDER BY Nom ASC";
        }
        elseif($Front ==1)
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >13 AND Longitude <45 AND Latitude >41 AND Latitude<=50.5 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Front ==4)
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >13 AND Longitude <45 AND Latitude >50.5 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Front ==5)
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-50 AND Longitude <60 AND Latitude >60 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Front ==3)
        {
            $Long_range=true;
            /*if($country ==9 and $Date_Campagne >'1941-12-01' and $Date_Campagne <'1941-12-08')
                $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >67 AND (((Port >0 OR Plage=1) AND Flag=9) OR Zone=6) AND ID<>'$Lieu' ORDER BY Nom ASC";
            else*/if($Date_Campagne <'1941-01-01')
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Flag='$country' AND Longitude >67 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif(!$Faction)
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Flag='$country' AND Longitude >67 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Longitude <99.5)
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >67 AND Longitude <100.5 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Longitude >99.5)
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >99.4 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
        else
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >67 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
        }
        elseif($Lieu ==344 or $Lieu ==503) //Gibraltar
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <-5.34 AND Latitude <50 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
        elseif($Latitude >67)
        {
            $Long_range=true;
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <60 AND Latitude >60 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
        }
        elseif($Longitude <-45)
        {
            $Long_range=true;
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <-10 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
        }
        elseif($Longitude <=-10)
        {
            if($Lieu ==1992)
                $Dist_max=800;
            else
                $Dist_max=1000;
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <=-8 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
        }
        elseif($Longitude <-0.5 and $Longitude >-10 and $Latitude >39.99 and $Latitude <48) //Golfe de Gascogne
        {
            $Dist_max=306;
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <-0.5 AND Latitude <48.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
        }
        elseif($Longitude <-5.5 and $Latitude <45) //Côte Ouest Afrique
        {
            $Dist_max=521;
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <-5.51 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
        }
        elseif($Longitude >9 and $Latitude >53.8) //Mer Baltique
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >7.99 AND Latitude >53.8 AND (Zone=6 OR Port>0 OR Plage=1) ORDER BY Nom ASC";
        elseif($Longitude >=7 and $Longitude <=11.5 and $Latitude >=57) //Skagerrak + Kattegat
        {
            $Dist_max=250;
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >=4.5 AND Longitude <14 AND Latitude >55.5 AND (Zone=6 OR Port>0 OR Plage=1) ORDER BY Nom ASC";
        }
        else
        {
            if($Lieu ==3155)$Lieu_exc=730;
            elseif($Lieu ==730)$Lieu_exc=3155;
            if($Longitude <=-8)
                $Dist_max=425;
            else
                $Dist_max=250;
            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <=8.6 AND Latitude >44 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN(".$Lieu.$Lieu_exc.") ORDER BY Nom ASC";
        }
        if($Long_range)
        {
            if($Transit_Veh ==5000)
                $Dist_max=1200;
            else
                $Dist_max=$Fuel; //1200
            if($Dist_max >2400)$Dist_max=2400;
        }
        $con=dbconnecti();
        $result=mysqli_query($con,$query);
        mysqli_close($con);
        if($result)
        {
            while($data2=mysqli_fetch_array($result))
            {
                $lieux_obj.='<option value="'.$data2[0].'">'.$data2[1].'</option>';
                $coord=0;
                $CT_front=0;
                $Distance=GetDistance(0,0,$Longitude,$Latitude,$data2[2],$data2[3]);
                if($Distance[0] <=$Dist_max)
                {
                    if($Zone !=6 and $data2['Zone'] !=6 and $Lieu !=2015 and $data2['ID'] !=2015) //Exception Suez
                        $bla=false;//Pas de déplacement de port à port
                    else
                    {
                        $Impass=$data2[4];
                        $sensh='';
                        $sensv='';
                        if($Longitude >$data2[2])
                        {
                            $sensh='Ouest';
                            $coord+=2;
                            if($Impass ==2 or $Impass ==3 or $Impass ==4 or $Impass_ori ==6 or $Impass_ori ==7 or $Impass_ori ==8)
                                $CT_front=4;
                        }
                        elseif($Longitude < $data2[2])
                        {
                            $sensh='Est';
                            $coord+=1;
                            if($Impass ==6 or $Impass ==7 or $Impass ==8 or $Impass_ori ==2 or $Impass_ori ==3 or $Impass_ori ==4)
                                $CT_front=4;
                        }
                        if($sensh)
                        {
                            if($Latitude >$data2[3]+0.5)
                            {
                                $sensv='Sud';
                                $coord+=20;
                                if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
                                    $CT_front=4;
                            }
                            elseif($Latitude <$data2[3]-0.5)
                            {
                                $sensv='Nord';
                                $coord+=10;
                                if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
                                    $CT_front=4;
                            }
                        }
                        else
                        {
                            if($Latitude >$data2[3])
                            {
                                $sensv='Sud';
                                $coord+=20;
                                if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
                                    $CT_front=4;
                            }
                            elseif($Latitude <$data2[3])
                            {
                                $sensv='Nord';
                                $coord+=10;
                                if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
                                    $CT_front=4;
                            }
                        }
                        $sens=$sensv.' '.$sensh;
                        if(!$CT_front)
                        {
                            if($data2['Zone'] ==6)
                                $icone="<img src='images/zone".$data2['Zone'].".jpg'>";
                            else
                                $icone="<a href='#' class='popup'><img src='images/map/lieu_port".$data2['Flag'].".png'><span><b>Port</b> Le port doit être contrôlé par votre faction pour pouvoir bénéficier des infrastructures.</span></a>";
                            $modal_conso='<div class="alert alert-danger">Le déplacement rendra l\'unité inaccessible pendant 24h';
                            $modal_conso.='<br>L\'unité arrivera en mouvement, pensez à changer sa position une fois arrivé à destination';
                            $choix="<tr><td><a href='#' class='lien' data-toggle='modal' data-target='#modal-dest-".$data2[0]."'>".$data2[1]."</a></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
                            $lieux_modal.='<div class="modal fade" id="modal-dest-'.$data2[0].'" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title">Déplacement
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </h2>
                                                                </div>
                                                                <div class="modal-body">
                                                                <img class="img-flex" src="images/nav_evade.jpg">
                                                                <div class="alert alert-warning">La '.$Cie.'e flottille composée de '.$Vehicule_Nbr.' '.$Veh_Nom.' se déplacera vers <b>'.$data2[1].'</b></div>
                                                                <form action="ground_em_ia_go.php" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu.'"><input type="hidden" name="cible" value="'.$data2[0].'"><input class="btn btn-danger" type="submit" value="confirmer"></form>'.$modal_conso.'</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';
                            if($coord ==1) //Est
                                $Est_txt.=$choix;
                            elseif($coord ==2) //Ouest
                                $Ouest_txt.=$choix;
                            elseif($coord ==10) //Nord
                                $Nord_txt.=$choix;
                            elseif($coord ==20) //Sud
                                $Sud_txt.=$choix;
                            elseif($coord ==11) //NE
                                $NE_txt.=$choix;
                            elseif($coord ==21) //SE
                                $SE_txt.=$choix;
                            elseif($coord ==12) //NO
                                $NO_txt.=$choix;
                            elseif($coord ==22) //SO
                                $SO_txt.=$choix;
                        }
                    }
                }
                elseif($Admin)
                {
                    if($data2['Zone'] ==6)
                        $icone="<img src='images/zone".$data2['Zone'].".jpg'>";
                    else
                        $icone="<img src='images/map/lieu_port".$data2['Flag'].".png'>";
                    $choix="<tr><td>".$data2[1]."</td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
                    if($coord ==1) //Est
                        $Est_txt.=$choix;
                    elseif($coord ==2) //Ouest
                        $Ouest_txt.=$choix;
                    elseif($coord ==10) //Nord
                        $Nord_txt.=$choix;
                    elseif($coord ==20) //Sud
                        $Sud_txt.=$choix;
                    elseif($coord ==11) //NE
                        $NE_txt.=$choix;
                    elseif($coord ==21) //SE
                        $SE_txt.=$choix;
                    elseif($coord ==12) //NO
                        $NO_txt.=$choix;
                    elseif($coord ==22) //SO
                        $SO_txt.=$choix;
                }
            }
            mysqli_free_result($result);
        }
    }
    if($Vehicule ==5001 or $Vehicule ==5124 or $Vehicule ==5392) //cargos
    {
        $Divisions='Etat-Major';
        $Positions='En position';
        $Pos_titre='Fret';
        if($Vehicule ==5392)
            $Pos_ori="Cargaison";
        elseif(!$Division_d)
        {
            if(!$Fret)
                $Pos_ori="Vide";
            elseif($Fret ==1001)
                $Pos_ori="250000L Diesel";
            elseif($Fret ==1087)
                $Pos_ori="250000L Essence 87";
            elseif($Fret ==1100)
                $Pos_ori="250000L Essence 100";
            elseif($Fret ==1)
                $Pos_ori="Troupes";
            elseif($Fret ==930)
                $Pos_ori="10000 Fusées";
            elseif($Fret ==80)
                $Pos_ori="5000 Rockets";
            elseif($Fret ==200)
                $Pos_ori="Troupes IA";
            elseif($Fret ==300)
                $Pos_ori="1250 Charges";
            elseif($Fret ==400)
                $Pos_ori="1250 Mines";
            elseif($Fret ==800)
                $Pos_ori="500 Torpilles";
            elseif($Fret ==888)
                $Pos_ori="Lend-Lease";
            elseif($Fret ==1200)
                $Pos_ori="Obus de 200mm";
            elseif($Fret ==9050 or $Fret ==9125 or $Fret ==9250 or $Fret ==9500)
                $Pos_ori="Bombes de ".substr($Fret,1)."kg";
            elseif($Fret > 9999)
                $Pos_ori="Bombes de ".substr($Fret,0,-1)."kg";
            else
                $Pos_ori="Obus de ".$Fret."mm";
        }
        else
        {
            if(!$Fret)
                $Pos_ori="Vide";
            elseif($Fret ==1001)
                $Pos_ori="Diesel";
            elseif($Fret ==1087)
                $Pos_ori="Essence 87";
            elseif($Fret ==1100)
                $Pos_ori="Essence 100";
            elseif($Fret ==200)
                $Pos_ori="Troupes IA";
            elseif($Fret ==300)
                $Pos_ori="Charges";
            elseif($Fret ==400)
                $Pos_ori="Mines";
            elseif($Fret ==800)
                $Pos_ori="Torpilles";
            elseif($Fret ==888)
                $Pos_ori="Lend-Lease";
            elseif($Fret ==1200)
                $Pos_ori="Obus de 200mm";
            elseif($Fret >9000 or $Fret ==80 or $Fret ==930 or $Fret ==1)
                $Pos_ori="Fret incompatible";
            else
                $Pos_ori="Obus de ".$Fret."mm";
        }
        if($Vehicule_Nbr >0)
        {
            if($Vehicule ==5392) //Flottille ravit
            {
                $con=dbconnecti();
                $resultd=mysqli_query($con,"SELECT d.* FROM Regiment_IA as r,Depots as d WHERE r.ID=d.Reg_ID AND r.ID='$Unit'");
                mysqli_close($con);
                if($resultd)
                {
                    while($datad=mysqli_fetch_array($resultd))
                    {
                        $flr_info="<h3>Cargaison de la flottille</h3><div style='overflow:auto;'><table class='table'>
                                        <thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
                                        <th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th></tr></thead>
                                        <tr><td>".$datad['Stock_Essence_87']."</td><td>".$datad['Stock_Essence_100']."</td><td>".$datad['Stock_Essence_1']."</td><td>".$datad['Stock_Munitions_8']."</td><td>".$datad['Stock_Munitions_13']."</td>
                                        <td>".$datad['Stock_Munitions_20']."</td><td>".$datad['Stock_Munitions_30']."</td><td>".$datad['Stock_Munitions_40']."</td><td>".$datad['Stock_Munitions_50']."</td><td>".$datad['Stock_Munitions_60']."</td>
                                        <td>".$datad['Stock_Munitions_75']."</td><td>".$datad['Stock_Munitions_90']."</td><td>".$datad['Stock_Munitions_105']."</td><td>".$datad['Stock_Munitions_125']."</td><td>".$datad['Stock_Munitions_150']."</td></tr></table></div>";
                    }
                    mysqli_free_result($resultd);
                }
                else
                    $flr_info="<h3>Cargaison de la flottille</h3><div class='alert alert-danger'>Une erreur est survenue, veuillez le signaler sur le forum</div>";
            }
            if($ValeurStrat >3 and $Faction ==$Faction_Flag and !$Move) //Dépot
            {
                $depot_info="<h3>Dépôt de ".$Ville."</h3><div style='overflow:auto;'><table class='table'>
                                <thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
                                <th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th>
                                <th>Charges de Profondeur</th><th>Mines</th><th>Torpilles</th><th>Rockets</th><th>Fusées</th></tr></thead>
                                <tr><td>".$Stock_Essence_87."</td><td>".$Stock_Essence_100."</td><td>".$Stock_Essence_1."</td><td>".$Stock_Munitions_8."</td><td>".$Stock_Munitions_13."</td>
                                <td>".$Stock_Munitions_20."</td><td>".$Stock_Munitions_30."</td><td>".$Stock_Munitions_40."</td><td>".$Stock_Munitions_50."</td><td>".$Stock_Munitions_60."</td>
                                <td>".$Stock_Munitions_75."</td><td>".$Stock_Munitions_90."</td><td>".$Stock_Munitions_105."</td><td>".$Stock_Munitions_125."</td><td>".$Stock_Munitions_150."</td>
                                <td>".$Stock_Bombes_300."</td><td>".$Stock_Bombes_400."</td><td>".$Stock_Bombes_800."</td><td>".$Stock_Bombes_80."</td><td>".$Stock_Bombes_30."</td></tr>
                                </table></div>";
                if(!$Division_d)
                {
                    if($Stock_Munitions_8 >500000)
                        $Fret_options.="<option value='8'>500000 cartouches de 8mm</option>";
                    elseif($Stock_Munitions_8)
                        $Fret_options.="<option value='8' disabled>500000 cartouches de 8mm</option>";
                    if($Stock_Munitions_13 >250000)
                        $Fret_options.="<option value='13'>250000 cartouches de 13mm</option>";
                    elseif($Stock_Munitions_13)
                        $Fret_options.="<option value='13' disabled>250000 cartouches de 13mm</option>";
                    if($Stock_Munitions_20 >=100000)
                        $Fret_options.="<option value='20'>100000 obus de 20mm</option>";
                    elseif($Stock_Munitions_20)
                        $Fret_options.="<option value='20' disabled>100000 obus de 20mm</option>";
                    if($Stock_Munitions_30 >=50000)
                        $Fret_options.="<option value='30'>50000 obus de 30mm</option>";
                    elseif($Stock_Munitions_30)
                        $Fret_options.="<option value='30' disabled>50000 obus de 30mm</option>";
                    if($Stock_Munitions_40 >=25000)
                        $Fret_options.="<option value='40'>25000 obus de 40mm</option>";
                    elseif($Stock_Munitions_40)
                        $Fret_options.="<option value='40' disabled>25000 obus de 40mm</option>";
                    if($Stock_Munitions_50 >=15000)
                        $Fret_options.="<option value='50'>15000 obus de 50mm</option>";
                    elseif($Stock_Munitions_50)
                        $Fret_options.="<option value='50' disabled>15000 obus de 50mm</option>";
                    if($Stock_Munitions_60 >=10000)
                        $Fret_options.="<option value='60'>10000 obus de 60mm</option>";
                    elseif($Stock_Munitions_60)
                        $Fret_options.="<option value='60' disabled>10000 obus de 60mm</option>";
                    if($Stock_Munitions_75 >=7500)
                        $Fret_options.="<option value='75'>7500 obus de 75mm</option>";
                    elseif($Stock_Munitions_75)
                        $Fret_options.="<option value='75' disabled>7500 obus de 75mm</option>";
                    if($Stock_Munitions_90 >=5000)
                        $Fret_options.="<option value='90'>5000 obus de 90mm</option>";
                    elseif($Stock_Munitions_90)
                        $Fret_options.="<option value='90' disabled>5000 obus de 90mm</option>";
                    if($Stock_Munitions_105 >=3750)
                        $Fret_options.="<option value='105'>3750 obus de 105mm</option>";
                    elseif($Stock_Munitions_105)
                        $Fret_options.="<option value='105' disabled>3750 obus de 105mm</option>";
                    if($Stock_Munitions_125 >=2500)
                        $Fret_options.="<option value='125'>2500 obus de 125mm</option>";
                    elseif($Stock_Munitions_125)
                        $Fret_options.="<option value='125' disabled>2500 obus de 125mm</option>";
                    if($Stock_Munitions_150 >=1000)
                        $Fret_options.="<option value='150'>1000 obus de 150mm</option>";
                    elseif($Stock_Munitions_150 >=1000)
                        $Fret_options.="<option value='150' disabled>1000 obus de 150mm</option>";
                    if($Stock_Munitions_200 >500)
                        $Fret_options.="<option value='1200'>500 obus de 200mm</option>";
                    if($Stock_Munitions_300 >375)
                        $Fret_options.="<option value='310'>375 obus de 300mm</option>";
                    if($Stock_Munitions_360 >250)
                        $Fret_options.="<option value='360'>250 obus de 360mm</option>";
                    if($Stock_Bombes_50 >10000)
                        $Fret_options.="<option value='9050'>10000 bombes de 50kg</option>";
                    if($Stock_Bombes_125 >5000)
                        $Fret_options.="<option value='9125'>5000 bombes de 125kg</option>";
                    if($Stock_Bombes_250 >2500)
                        $Fret_options.="<option value='9250'>2500 bombes de 250kg</option>";
                    if($Stock_Bombes_500 >1000)
                        $Fret_options.="<option value='9500'>1000 bombes de 500kg</option>";
                    if($Stock_Bombes_1000 >500)
                        $Fret_options.="<option value='10000'>500 bombes de 1000kg</option>";
                    if($Stock_Bombes_2000 >250)
                        $Fret_options.="<option value='11000'>250 bombes de 2000kg</option>";
                    if($Stock_Bombes_300 >1250)
                        $Fret_options.="<option value='300'>1250 charges de profondeur</option>";
                    if($Stock_Bombes_400 >1250)
                        $Fret_options.="<option value='400'>1250 mines</option>";
                    if($Stock_Bombes_80 >5000)
                        $Fret_options.="<option value='80'>5000 rockets</option>";
                    if($Stock_Bombes_800 >500)
                        $Fret_options.="<option value='800'>500 torpilles</option>";
                    if($Stock_Bombes_30 >10000)
                        $Fret_options.="<option value='930'>10000 fusées éclairantes</option>";
                    if($Stock_Essence_87 >=250000)
                        $Fret_options.="<option value='1087'>250000L Essence 87 Octane</option>";
                    elseif($Stock_Essence_87)
                        $Fret_options.="<option value='1087' disabled>250000L Essence 87 Octane</option>";
                    if($Stock_Essence_100 >=250000)
                        $Fret_options.="<option value='1100'>250000L Essence 100 Octane</option>";
                    elseif($Stock_Essence_100)
                        $Fret_options.="<option value='1100' disabled>250000L Essence 100 Octane</option>";
                    if($Stock_Essence_1 >=250000)
                        $Fret_options.="<option value='1001'>250000L de Diesel</option>";
                    elseif($Stock_Essence_1)
                        $Fret_options.="<option value='1001' disabled>250000L de Diesel</option>";
                    if($Fret ==888 and $Vehicule ==5124 and ($Lieu ==269 or $Lieu ==270 or $Lieu ==731 or $Lieu ==586 or $Lieu ==758 or $Lieu ==815))
                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='Dech' value='888'><input type='hidden' name='base' value='".$Lieu."'><input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                    elseif($Fret >0 and $Fret !=888 and $Vehicule !=5392)
                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='Dech' value='".$Fret."'><input type='hidden' name='base' value='".$Lieu."'><input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                }
                elseif($Division_d and $Ordres_Div)
                {
                    if($Stock_Munitions_8 >10000)
                        $Fret_options.="<option value='8'>10000 cartouches de 8mm</option>";
                    if($Stock_Munitions_13 >5000)
                        $Fret_options.="<option value='13'>5000 cartouches de 13mm</option>";
                    if($Stock_Munitions_20 >2000)
                        $Fret_options.="<option value='20'>2000 obus de 20mm</option>";
                    if($Stock_Munitions_30 >1000)
                        $Fret_options.="<option value='30'>1000 obus de 30mm</option>";
                    if($Stock_Munitions_40 >500)
                        $Fret_options.="<option value='40'>500 obus de 40mm</option>";
                    if($Stock_Munitions_50 >300)
                        $Fret_options.="<option value='50'>300 obus de 50mm</option>";
                    if($Stock_Munitions_60 >200)
                        $Fret_options.="<option value='60'>200 obus de 60mm</option>";
                    if($Stock_Munitions_75 >150)
                        $Fret_options.="<option value='75'>150 obus de 75mm</option>";
                    if($Stock_Munitions_90 >100)
                        $Fret_options.="<option value='90'>100 obus de 90mm</option>";
                    if($Stock_Munitions_105 >75)
                        $Fret_options.="<option value='105'>75 obus de 105mm</option>";
                    if($Stock_Munitions_125 >50)
                        $Fret_options.="<option value='125'>50 obus de 125mm</option>";
                    if($Stock_Munitions_150 >20)
                        $Fret_options.="<option value='150'>20 obus de 150mm</option>";
                    if($Stock_Bombes_300 >25)
                        $Fret_options.="<option value='300'>25 charges de profondeur</option>";
                    if($Stock_Bombes_400 >25)
                        $Fret_options.="<option value='400'>25 mines</option>";
                    if($Stock_Bombes_800 >10)
                        $Fret_options.="<option value='800'>10 torpilles</option>";
                    if($Stock_Essence_87 >50000)
                        $Fret_options.="<option value='1087'>50000L Essence 87 Octane</option>";
                    if($Stock_Essence_1 >50000)
                        $Fret_options.="<option value='1001'>50000L de Diesel</option>";
                    /*if($Fret)
                    {
                        $Regs_div=false;
                        $con=dbconnecti();
                        $result9=mysqli_query($con,"SELECT r.ID FROM Regiment as r,Officier as o WHERE r.Officier_ID=o.ID AND r.Lieu_ID='$Lieu' AND o.Division='$Division_d'");
                        mysqli_close($con);
                        if($result9)
                        {
                            while($data9=mysqli_fetch_array($result9,MYSQLI_ASSOC))
                            {
                                $Regs_div.="<option value='".$data9['ID']."'>".$data9['ID']."e Cie</option>";
                            }
                            mysqli_free_result($result9);
                        }
                        if($Regs_div)
                            $Decharger="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='Dech' value='".$Fret."'>
                            <select name='Reg_div' class='form-control' style='width: 150px'>".$Regs_div."</select>
                            <input type='Submit' value='Ravitailler' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                    }*/
                }
                if($Vehicule ==5124 and $Longitude <-52 and ($country ==2 or $country ==7))
                {
                    $LLease=true;
                    $Fret_options.="<option value='888'>Lend-Lease</option>";
                }
            }
            if($Pos_ori ==888 or $LLease)
            {
                if($country==7)
                    $UK_Lend_txt=" Bristol, Glasgow et Liverpool pour l'Empire Britannique,";
                $depot_info.="<p class='lead'>Le Lend-Lease permet de fournir du matériel aux nations alliées via les ports d'Arkhangelsk et Mourmansk pour l'URSS,".$UK_Lend_txt." Casablanca pour la France.
                            <br>Le matériel Lend-Lease est indiqué dans l'encyclopédie par le symbole <img src='images/lendlease.png' title='Lend-Lease'></p>";
            }
            /*if($Division_d and !$Ordres_Div)
            {
                $Lieux_txt="<select name='cible' class='form-control' style='width: 200px'><option value='0'>Rester à ".$Ville."</option>";
                $Positions="<select name='fret' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option></select><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Seul le commandant de flottille peut gérer cette option</span></a>";
            }
            else*/
            //Update : 03/02/2018 $Zone ==6 => $Placement ==8
            if($Placement ==8 and !$Port_Ori and !$Move)
            {
                $con=dbconnecti();
                $resultgr=mysqli_query($con,"SELECT r.ID,c.Nom FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Lieu' AND r.Pays='$country' AND c.Categorie IN(17,20,21,22,23,24) AND r.Autonomie < c.Autonomie");
                mysqli_close($con);
                if($resultgr)
                {
                    while($datagr=mysqli_fetch_array($resultgr,MYSQLI_ASSOC))
                    {
                        $Regs_gr.="<option value='".$datagr['ID']."'>Ravitailler ".$datagr['Nom']." (".$datagr['ID']."e)</option>";
                    }
                    mysqli_free_result($resultgr);
                }
                if($Regs_gr)
                    $Atk_Options="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='Reg_gr' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option>".$Regs_gr."</select><input class='btn btn-sm btn-warning' type='submit' value='Ravitailler un navire' onclick='this.disabled=true;this.form.submit();'></form></td>
                                <td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Ravitailler un navire en pleine mer</span></a></td></tr>";
            }
            elseif($Vehicule ==5392)
                $Atk_Options="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='fretd' value='1'><select name='fret' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option>".$Fret_options."</select><input class='btn btn-sm btn-warning' type='submit' value='Charger' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une quantité minimale est nécessaire dans le dépôt pour pouvoir transporter du fret</span></a></td></tr>";
            else
                $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='Reg_div' value='".$Division_d."'><select name='fret' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option>".$Fret_options."</select><input class='btn btn-sm btn-warning' type='submit' value='Charger' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une quantité minimale est nécessaire dans le dépôt pour pouvoir transporter du fret</span></a></td></tr>";
        }
    }
    elseif(($Ordres_Cdt or $Ordres_Mer or $Ordres_Armee or $Ordres_Div or $Ordres_Bat) and (!$GHQ or $Admin or $Nation_IA))
    {
        $Meteo_Move_Limit=-75;
        if($Type_Veh ==21)
            $PA_IA=true;
        elseif($Type_Veh ==37)
            $Sub_IA=true;
        elseif($Type_Veh ==15 or $Type_Veh ==17)
            $ASM=true;
        elseif($Type_Veh ==14 and $Detection >10)
            $Patrouilleur=true;
        elseif($Type_Veh ==20)
            $Meteo_Move_Limit=-100;
        if($Type_Veh ==17 or $Type_Veh ==18 or $Type_Veh ==19)
            $Torp_IA=true;
        if(!$Move and $Vehicule_Nbr >0 and $Position !=34 and $Autonomie >0 and $HP >0)
        {
            if($Meteo >$Meteo_Move_Limit)
            {
                $CT_Bomb=2-$Sec_EM;
                $CT_Torp=4-$Sec_EM;
                if($Sub_IA)
                {
                    $Pos_torp=28;
                    $Atk_Options.="<tr><td><form action='index.php?view=ground_reco1' method='post'><input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Veh' value='".$Vehicule."'><input type='hidden' name='Cible' value='".$Lieu."'><input type='hidden' name='Conso' value='0'>
                                    <input type='submit' value='Reco' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>1 Jour</td><td>".$Range."m</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tente de révéler les unités présentes sur la même zone.</span></a></td></tr>";
                    if($Zone ==6 and $Autonomie >9 and $Vehicule ==5387)
                    {
                        $con=dbconnecti();
                        $resultgr=mysqli_query($con,"SELECT r.ID,c.Nom FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Lieu' AND r.Pays='$country' AND c.Categorie IN(17,20,21,22,23,24) AND r.Autonomie < c.Autonomie AND r.ID<>'$Unit'");
                        mysqli_close($con);
                        if($resultgr)
                        {
                            while($datagr=mysqli_fetch_array($resultgr,MYSQLI_ASSOC))
                            {
                                $Regs_gr.="<option value='".$datagr['ID']."'>Ravitailler ".$datagr['Nom']." (".$datagr['ID']."e Cie)</option>";
                            }
                            mysqli_free_result($resultgr);
                        }
                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='renf' value='6'><select name='Reg_gr' class='form-control' style='width: 150px'><option value='0'>Ne pas ravitailler</option>".$Regs_gr."</select></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>1 Jour</td><td>".$Range."m</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Ravitailler un navire en pleine mer</span></a></td></tr>";
                    }
                }
                elseif($Patrouilleur)
                {
                    $Atk_Options.="<tr><td><form action='index.php?view=ground_reco1' method='post'>
                                    <input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Veh' value='".$Vehicule."'><input type='hidden' name='Cible' value='".$Lieu."'><input type='hidden' name='Conso' value='0'>
                                    <input type='submit' value='Reco' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>1 Jour</td><td>".$Range."m</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tente de révéler les unités présentes sur la même zone.<br>Action comptant comme action du jour</span></a></td></tr>";
                }
                elseif($ASM)
                {
                    if($Zone ==6 or $Placement ==8)
                        $Atk_Options.="<tr><td><form action='index.php?view=ground_asm' method='post'><input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Veh' value='".$Vehicule."'><input type='hidden' name='Cible' value='".$Lieu."'><input type='hidden' name='Conso' value='0'>
                                    <input type='submit' value='Grenadage' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                    <td>1 Jour</td>
                                    <td>".$Range."m</td>
                                    <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tentative de repérage et de grenadage d'éventuels sous-marins ennemis présents dans la même zone.</span></a></td></tr>";
                }
                elseif($Categorie ==25 and $Detroit >0 and $Credits >=4 and $Mines_m <100) //Mouilleur mines
                {
                    $Atk_Options.="<tr><td><form action='index.php?view=ground_miner' method='post'>
                                <input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Cible' value='".$Lieu."'>
                                <input type='submit' value='Miner' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                <td><img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                <td>1 Jour</td>
                                <td>".$Range."m</td>
                                <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Mouiller des mines marines dans la zone</span></a></td></tr>";
                }
                elseif($Categorie ==19 and $Detroit >0 and $Credits >=8) //Dragueur mines
                {
                    $Atk_Options.="<tr><td><form action='index.php?view=ground_deminer' method='post'>
                                <input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Cible' value='".$Lieu."'>
                                <input type='Submit' value='Deminer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                <td><img src='images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                <td>1 Jour</td>
                                <td>".$Range."m</td>
                                <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Détecter et éventuellement retirer des mines marines dans la zone</span></a></td></tr>";
                }
                elseif($HP >10000 and !$PA_IA and $Credits >=$CT_Bomb and $Autonomie >0 and !$Canada)
                {
                    if($Zone ==6 or $Placement ==PLACE_LARGE){
                        //Range
                        if($Matos ==8)$Range /=2;
                        if($Position ==2 or $Position ==3 or $Position ==9 or $Position ==10 or $Position ==26)$Range /=2;
                        if($Skill ==73)
                            $Range*=1.25;
                        elseif($Skill ==72)
                            $Range*=1.2;
                        elseif($Skill ==47)
                            $Range*=1.15;
                        elseif($Skill ==15)
                            $Range*=1.1;
                        if($Meteo <-69)$Range /=2;
                        if($Flag ==$country)$Range +=500;
                        if($Zone ==6)$Range+=($Experience*9);
                        if($Meteo <-69)
                            $Max_Range=5000;
                        elseif($Meteo <-9)
                            $Max_Range=10000;
                        else
                            $Max_Range=20000;
                        if($Range >$Max_Range)$Range=$Max_Range;
                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='CT' value='".$CT_Bomb."'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='30'>
                                    <input type='submit' value='Engagement' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><img src='images/CT".$CT_Bomb.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                    <td>1 Jour</td>
                                    <td>".$Range."m</td>
                                    <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Bombardement visant une unité ennemie sur le même lieu. Compte comme action du jour.</span></a></td></tr>";
                    }
                    if($Zone !=6 and $Flag !=$country)
                        $Pos_txt.="<option value='29'>Bombardement des fortifications (Action du jour)</option>";
                    if($Faction !=$Faction_Flag and $Zone !=6 and $Garnison and $Recce)
                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='33'>
                                    <input type='submit' value='Bombardement' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                    <td>1 Jour</td>
                                    <td>".$Range."m</td>
                                    <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Bombardement visant la caserne ou la garnison sur le même lieu. Compte comme action du jour.</span></a></td></tr>";
                }
                if((($Torp_IA and $Credits >=$CT_Torp) or $Sub_IA) and ($Zone ==6 or $Placement ==8) and $Arme_AT and $HasHostiles)
                {
                    //Range
                    if($Sub_IA)
                    {
                        $con=dbconnecti();
                        $Rudels=mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Lieu' AND r.Pays='$country' AND r.Placement=8 AND r.Vehicule_Nbr >0 AND c.Type=37");
                        mysqli_close($con);
                    }
                    if($Matos ==8)$Range/=2;
                    if($Position ==26)
                        $Range/=2;
                    elseif($Position ==25 or $Position ==28)
                        $Range*=2;
                    if($Meteo <-69)
                        $Max_Range=500;
                    elseif($Meteo <-9)
                        $Max_Range=1000;
                    else
                        $Max_Range=2000;
                    if($Range >$Max_Range)$Range=$Max_Range;
                    if($Placement ==4)
                        $Range=20000; //Au port tous les navires de surface peuvent être ciblés
                    elseif($Skill ==43)
                        $Range*=(1+((5*$Rudels)/10));
                    elseif($Skill ==168)
                        $Range*=(1+((10*$Rudels)/10));
                    elseif($Skill ==169)
                        $Range*=(1+((15*$Rudels)/10));
                    elseif($Skill ==170)
                        $Range*=(1+((20*$Rudels)/10));
                    $Range=round($Range);
                    if($Torp_IA){
                        $Pos_torp=40;
                        $Torp_CT="<img src='images/CT".$CT_Torp.".png' title='Montant en Crédits Temps que nécessite cette action'>";
                    }
                    else
                        $CT_Torp=0;
                    if($Credits >=$CT_Torp or $Sub_IA)
                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='CT' value='".$CT_Torp."'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='".$Pos_torp."'>
                                    <input type='submit' value='Torpillage' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'>".$Torp_CT."<a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                    <td>1 Jour</td>
                                    <td>".$Range."m</td>
                                    <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Vous ne pourrez cibler que les unités détectées au préalable par une reco. Vérifiez la présence d'unités détectées dans la fenêtre de situation.</span></a></td></tr>";
                }
            }
            elseif($Meteo <=$Meteo_Move_Limit)
                $txt_help.="<div class='alert alert-danger'>Le mauvais temps interdit tout appareillage!</div>";
        }
        $Pos_titre='Position';
        $Pos_ori=GetPosGr($Position);
        if($Position ==34)
            $Positions='<span class="text-danger">En cale sèche</span>';
        elseif($Sub_IA)
        {
            if(!$Positions)
            {
                $Positions="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'>
                            <select name='pos' class='form-control' style='max-width:200px; display:inline;'><option value='0'>Ne rien changer</option>";
                if($Position !=34)$Positions.="<option value='25'>Plongée</option>";
                //$Positions.="</select><input class='btn btn-sm btn-warning' type='submit' value='Changer' onclick='this.disabled=true;this.form.submit();'></form>";
            }
        }
        elseif($Transit_Veh ==5000)
        {
            if($Flag_Port)$Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
            if($Flag_Plage)$Faction_Plage=GetData("Pays","ID",$Flag_Plage,"Faction");
            if(!$Move and $Port >10 and $Faction ==$Faction_Flag and $Faction ==$Faction_Port)
                $Atk_Options='<tr><td><form action="index.php?view=ground_em_ia_go" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu.'"><input type="hidden" name="reset" value="7"><input class="btn btn-sm btn-warning" type="submit" value="Décharger" onclick="this.disabled=true;this.form.submit();"></form></td><td><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>';
            else
                $txt_help.="<div class='alert alert-warning'>Ces troupes peuvent être débarquées dans un port aux infrastructures non détruites dont le lieu et le port sont contrôlés par votre faction</div>";
            $Positions="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='pos' class='form-control' style='max-width:200px; display:inline;'>
                                    <option value='0'>Ne rien changer</option>
                                    <option value='20'>Dispersé (navire isolé ou sans protection)</option>
                                    <option value='22'>Evasion (navire rapide désirant éviter le combat)</option>";
            $barges_txt='<br>'.GetVehiculeIcon(5000,$country,0,0,$Front,"Transporté par des barges");
            if(!$Move and $Zone !=6 and $Plage and $Placement ==8 and ($Amphi or $Faction_Plage ==$Faction))
            {
                $con=dbconnecti();
                //$Unitsb_Plage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement=11 AND r.Vehicule_Nbr >0"),0);
                $Unitsa_Plage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction='$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement=11 AND i.Vehicule_Nbr >0"),0);
                mysqli_close($con);
                //$Unitsa_Plage+=$Unitsb_Plage;
                if($Unitsa_Plage >4)
                    $txt_help.="<div class='alert alert-danger'>Un trop grand nombre de troupes se trouvent déjà sur la plage, empêchant tout débarquement</div>";
                else
                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='reset' value='8'>
                                <input type='submit' value='Débarquer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
            }
        }
        elseif($Pas_libre)
            $Positions='<span class="text-danger">Immobilisé</span>';
        elseif(!$Autonomie)
        {
            $Positions="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='pos' class='form-control' style='max-width:200px; display:inline;'>
                                    <option value='0'>Ne rien changer</option>
                                    <option value='20'>Dispersé (navire isolé ou sans protection)</option>
                                    <option value='22'>Evasion (navire rapide désirant éviter le combat)</option>";
        }
        else
        {
            $Positions="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='pos' class='form-control' style='max-width:200px; display:inline;'>
                                    <option value='0'>Ne rien changer</option>
                                    <option value='20'>Dispersé (navire isolé ou sans protection)</option>
                                    <option value='21'>Escorte (protection des autres navires)</option>
                                    <option value='22'>Evasion (navire rapide désirant éviter le combat)</option>".$Pos_txt;
            if($Arme_Art >0 and $Credits >=1)
                $Positions.="<option value='23'>Appui (1CT - Ripostera avec son artillerie en cas d'attaque de surface)</option>";
            if($ASM and $Credits >=1)
                $Positions.="<option value='24'>ASM (1CT - Ripostera avec ses grenades en cas d'attaque sous-marine)</option>";
            if($Vitesse >35 and !$Move)
            {
                if($HP >$HP_max/2)
                    $Positions.="<option value='27'>Interdiction (Action du jour - Tentera d'empêcher les navires ennemis de fuir)</option>";
                else
                    $txt_help.="<div class='alert alert-danger'>Le navire est trop endommagé pour pouvoir être utilisé comme navire d'interdiction</div>";
            }
            if($Arme_Art >0 and $Zone ==6 and !$Move)
                $Positions.="<option value='37'>Fumigène (Action du jour - Permet aux navires alliés de quitter la zone de combat)</option>";
            elseif($Zone !=6 and $Placement ==4 and !$Move)
                $Positions.="<option value='26'>Filet anti-torpilles (Action du jour)</option>";
        }
        if($Position !=34 and !$Pas_libre)
            $Positions.="</select><input class='btn btn-sm btn-warning' type='submit' value='Changer' onclick='this.disabled=true;this.form.submit();'></form>";
        else
            $Positions.='</select></form>';
        if($PA_IA or $Hydra)
        {
            $depot_info="<div class='panel panel-war'><div class='panel-heading'>Avions à bord</div><div class='panel-body'><table class='table table-striped'>";
            if($Hydra)
            {
                $depot_info.="<tr><td><a href='#' class='popup'>".$Avions."/".$Hydra_Nbr."<span>Nombre d'avions disponibles/maximum.<br>Récupérer des avions perdus est possible via l'action de ravitaillement dans un port.</span></a>
                            ".GetAvionIcon($Hydra,$country,0,0,$Front)."</td><td></tr>";
                if($Avions and $Autonomie)
                    $depot_info.="<tr><td><form action='index.php?view=ground_em_ia_hydra' method='post'><input type='hidden' name='Lieu' value='".$Lieu."'>
                                <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une mission aérienne consomme 1 jour d'autonomie du navire</span></a>
                                <input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Hydra' value='".$Hydra."'>
                                <input type='submit' value='Mission aérienne' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
            }
            else
            {
                $con=dbconnecti();
                $result10=mysqli_query($con,"SELECT ID,Pays,Nom,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Mission_IA FROM Unit WHERE Porte_avions='$Vehicule' AND Etat=1");
                mysqli_close($con);
                if($result10)
                {
                    while($data10=mysqli_fetch_array($result10,MYSQLI_ASSOC))
                    {
                        if(!$data10['Mission_IA'])
                            $Esc_PA_txt="<form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$data10['ID']."'>
                                        <input type='submit' value='".$data10['Nom']."' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                        else
                            $Esc_PA_txt="<span class='label label-danger'>En vol</span>";
                        $depot_info.="<tr><td>".$Esc_PA_txt."</td><td>"
                            .$data10['Avion1_Nbr']."x ".GetAvionIcon($data10['Avion1'],$data10['Pays'],0,$data10['ID'],$Front)."</td><td>"
                            .$data10['Avion2_Nbr']."x ".GetAvionIcon($data10['Avion2'],$data10['Pays'],0,$data10['ID'],$Front)."</td><td>"
                            .$data10['Avion3_Nbr']."x ".GetAvionIcon($data10['Avion3'],$data10['Pays'],0,$data10['ID'],$Front)."</td></tr>";
                    }
                    mysqli_free_result($result10);
                }
            }
            $depot_info.='</table></div></div>';
        }
    }
    elseif($Ordres_Adjoint and $Transit_Veh ==5000 and (!$GHQ or $Admin or $Nation_IA))
    {
        $Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
        if(!$Move and $Port >10 and $Faction ==$Faction_Flag and $Faction ==$Faction_Port)
        {
            $con=dbconnecti();
            $Unitsa_Port=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction='$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement=4 AND i.Vehicule_Nbr >0"),0);
            mysqli_close($con);
            if($Unitsa_Plage >4)
                $txt_help.="<div class='alert alert-danger'>Un trop grand nombre de troupes se trouvent déjà dans le port, empêchant tout débarquement</div>";
            else
                $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='reset' value='7'>
                            <input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
        }
        else
            $txt_help.="<div class='alert alert-warning'>Ces troupes peuvent être débarquées dans un port aux infrastructures non détruites dont le lieu et le port sont contrôlés par votre faction</div>";
        $Positions="<select name='pos' class='form-control' style='max-width:200px; display:inline;'>
                                <option value='0'>Ne rien changer</option>
                                <option value='20'>Dispersé (navire isolé ou sans protection)</option>
                                <option value='22'>Evasion (navire rapide désirant éviter le combat)</option>";
        $barges_txt='<br>'.GetVehiculeIcon(5000,$country,0,0,$Front,"Transporté par des barges");
        if($Amphi and $Zone !=6 and $Plage and $Placement ==8)
        {
            $con=dbconnecti();
            //$Unitsb_Plage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement=11 AND r.Vehicule_Nbr >0"),0);
            $Unitsa_Plage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction='$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement=11 AND i.Vehicule_Nbr >0"),0);
            mysqli_close($con);
            //$Unitsa_Plage+=$Unitsb_Plage;
            if($Unitsa_Plage)
                $txt_help.="<div class='alert alert-danger'>Un trop grand nombre de troupes se trouvent déjà sur la plage, empêchant tout débarquement</div>";
            else
                $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='reset' value='8'>
                            <input type='submit' value='Débarquer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
        }
    }
    //Renforts
    if(!$Transit_Veh)
    {
        $Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
        if($Port >0 and $Faction ==$Faction_Flag and $Faction ==$Faction_Port and $Credits >=4)
        {
            $Port_ok=false;
            if($Port_level ==3)$Port_ok=true;
            elseif($Port_level ==2 and ($Type_Veh <20 or $Type_Veh ==37))$Port_ok=true;
            elseif($Port_level ==1 and ($Type_Veh ==14 or $Type_Veh ==15))$Port_ok=true;
            if($Port_ok)
            {
                $CT_cale=4;
                $con=dbconnecti();
                if($Type_Veh>17 and $Type_Veh<22)
                {
                    $coules=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ground_Cbt WHERE Veh_b='$Vehicule'"),0);
                    if($coules>0)$CT_cale=CT_MAX;
                }
                //$Enis_Port=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement=4 AND r.Vehicule_Nbr >0"),0);
                $Enis2_Port=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction<>'$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement=4 AND i.Vehicule_Nbr >0"),0);
                mysqli_close($con);
                $Enis_Port_combi=$Enis_Port+$Enis2_Port;
                if($Enis_Port_combi)
                    $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Ce navire ne peut être réparé sur une zone de combat</span></a></td></tr>';
                elseif(!$Move and $Credits >=$CT_cale and (
                        ($HP<$HP_max and $HP>0) or
                        ($Vehicule_Nbr<1 and ($Vehicule ==5001 or $Vehicule ==5124 or $Type_Veh ==37 or $Type_Veh ==14)) or
                        ($Vehicule_Nbr<4 and ($Type_Veh ==15 or $Type_Veh ==16)) or
                        ($Vehicule_Nbr<2 and $Type_Veh ==17)
                    ))
                    $Renforts_txt='<tr><td>
                                            <form action="index.php?view=ground_em_ia_go" method="post">
                                                <input type="hidden" name="renf" value="2">
                                                <input type="hidden" name="Unit" value="'.$Unit.'">
                                                <input class="btn btn-sm btn-warning" type="submit" value="Réparer">
                                            </form>
                                        </td>
                                        <td><div class="i-flex"><img src="images/CT'.$CT_cale.'.png" title="Credits Temps nécessaires pour exécuter cette action"><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></div></td>
                                        <td><a href="#" class="popup"><div class="i-flex help_icon"></div><span>Des infrastructures portuaires sous le contrôle de votre faction sont nécessaires pour permettre les réparations des navires à cet endroit</span></a></td>
                                   </tr>';
                elseif(!$Move and $Credits ==CT_MAX and $Vehicule_Nbr<1 and !$HP and ($Type_Veh>17 and $Type_Veh<22))
                    $Renforts_txt='<tr><td>
                                            <form action="index.php?view=ground_em_ia_go" method="post">
                                                <input type="hidden" name="renf" value="4">
                                                <input type="hidden" name="Unit" value="'.$Unit.'">
                                                <input class="btn btn-sm btn-warning" type="submit" value="Cale Sèche">
                                            </form>
                                        </td>
                                        <td><div class="i-flex"><img src="images/CT50.png" title="Credits Temps nécessaires pour exécuter cette action"><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></div></td>
                                        <td><a href="#" class="popup"><div class="i-flex help_icon"></div><span>Des infrastructures portuaires sous le contrôle de votre faction sont nécessaires pour permettre les réparations des navires à cet endroit</span></a></td>
                                   </tr>';
                else
                    $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Ce navire ne peut être réparé actuellement</span></a></td></tr>';
            }
            else
                $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Les infrastructures de ce port ne permettent pas de réparations pour des navires de ce type</span></a></td></tr>';
        }
        else
            $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Des infrastructures portuaires sous le contrôle de votre faction sont nécessaires pour permettre les réparations des navires à cet endroit</span></a></td></tr>';
        if($HP_max)
            $hp_good=round(($HP/$HP_max)*100);
        else
            $hp_good=0;
        $hp_bad=100-$hp_good;
        if($HP)
            $Cur_HP="<br><div class='progress'><div class='progress-bar progress-bar-success' style='width: ".$hp_good."%'>".$hp_good."%</div><div class='progress-bar progress-bar-danger' style='width: ".$hp_bad."%'></div></div>";
        if($Categorie ==20 or $Categorie ==21 or $Categorie ==22 or $Categorie ==23 or $Categorie ==24 or $Categorie ==17){
            $Autonomie_txt='<a href="#" class="popup">
                            <b class="badge">'.$Autonomie.'/'.$Jours_max.' Jours</b>
                            <span>
                                <h3>L\'autonomie des navires</h3>
                                <p>Les unités maritimes de premier ordre possèdent une autonomie exprimée en jours de mer représentant le temps durant lequel ils peuvent opérer en mer sans devoir retourner au port pour se ravitailler.</p>
                                <ul>
                                    <li>Chaque action du jour réduit cette valeur de 1. D\'autres actions également comme le déplacement, la riposte de la DCA, l\'envoi des avions de bord en mission, la riposte depuis une position d\'appui ou encore toute action offensive (engagement, bombardement côtier, etc...) réduit cette valeur de 1.</li>
                                    <li>Lorsque cette valeur atteint 0, le navire ne peut plus effectuer aucune action offensive ni choisir de position tactique (excepté la dispersion et l\'écran de fumée).</li>
                                    <li>Dès que le navire rejoint un port allié de valeur 4 ou supérieur, il peut se ravitailler (<img src="images/action_jour_icon.png" alt="Action du Jour"> + <img src="images/CT4.png" alt="Crédits Temps">) et remonter la valeur de jours en mer à son maximum.</li>
                                    <li>Les navires cargos peuvent ravitailler les navires de leur nation en mer, cette action comptant comme action du jour.</li>
                                </ul>
                            </span>
                        </a>';
        }
    }
    if($Front == 2 and $Longitude <12)
        $front_carte=12;
    else
        $front_carte=$Front;
    $Carte_Bouton.="<div class='btn btn-sm btn-primary'><a href='carte_ground.php?map=".$front_carte."&mode=10&cible=".$Lieu."&reg=".$Unit."' onclick='window.open(this.href); return false;'>Voir la carte</a></div>";
}