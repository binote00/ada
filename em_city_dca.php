<?php
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
    require_once('./jfv_include.inc.php');
    include_once('./jfv_txt.inc.php');
    include_once('./jfv_inc_em.php');
    if($Premium) {

        include_once('./jfv_ground.inc.php');
        include_once('./jfv_combat.inc.php');

        $cible = Insec($_GET['id']);
        $Matos_mun=array(1,2,6,7,8);
        $txt_aide_shoot='Bonus <b>maximal</b> pour toucher l\'avion dans les circonstances actuelles<br>Si la valeur est négative, un tir critique sera nécessaire pour toucher la cible';

        function GetDgSim($degats, $blindage, $shoot, $dca_mult, $matos, $mun, $cal, $alt, $perf, $range, $range_max){
            $degats_min = ((($degats/2)-$blindage)*GetShoot($shoot,1));
            $degats_max = (($degats-$blindage)*GetShoot($shoot,$dca_mult));
            if($matos ==22){
                $degats_min*=1.1;
                $degats_max*=1.1;
            }
            $degats_min = round(Get_Dmg($mun,$cal,$blindage,$alt,$degats_min,$perf,$range,$range_max));
            $degats_max = round(Get_Dmg($mun,$cal,$blindage,$alt,$degats_max,$perf,$range,$range_max));
            $degats_crit=$degats_max+($degats*$dca_mult);
            return '
            <div class="row">
                <div class="col-4">
                    Min
                </div>
                <div class="col-8 text-success">
                    '.$degats_min.'
                </div>
                <div class="col-4">
                    Max
                </div>
                <div class="col-8 text-warning">
                    '.$degats_max.'
                </div>
                <div class="col-4">
                    Crit
                </div>
                <div class="col-8 text-danger">
                    '.$degats_crit.'
                </div>
            </div>';
        }

        dbconnect();
        //Lieu
        $resultLieu = $dbh->prepare("SELECT Nom,Meteo FROM Lieu WHERE ID=:idl");
        $resultLieu->bindValue(':idl', $cible, 1);
        $resultLieu->execute();
        $datal = $resultLieu->fetchObject();
        $lieuNom = $datal->Nom;
        $lieuMeteo = $datal->Meteo;

        //Stats
        $result = $dbh->prepare("
        SELECT r.ID,r.Pays,r.Bataillon,r.Vehicule_ID,r.Officier_ID,r.Experience,r.Vehicule_Nbr,r.Skill,r.Matos,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile,p.Nom AS paysNom
        FROM Regiment_IA as r,Cible as c,Pays as p 
		WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction <> :faction 
		AND c.Flak >0 AND r.Lieu_ID= :cible AND r.Vehicule_Nbr >0 AND c.Portee >= :alt AND r.Position IN(1,5,21) 
		ORDER BY c.Categorie ASC, r.Experience DESC, r.Vehicule_Nbr DESC");
        $resulta = $dbh->prepare("SELECT Nom,Calibre,Perf,Degats,Multi,Portee,Portee_max FROM Armes WHERE ID=:ida");
        //$resultpx = $dbh->query("SELECT ID,Pays,Nom,Blindage,VitesseB,Volets,Visibilite FROM Avion WHERE ID IN (14,15)");
        //$resultpa = $dbh->query("SELECT ID,Pays,Nom,Blindage,VitesseB,Volets,Visibilite FROM Avion WHERE ID IN (14,18,19,56)");

        if(IsAllie($country) or $Admin){
            //500m Axe
            $result->execute(array(':cible' => $cible, ':faction' => 1, ':alt' => 500));
            while($data = $result->fetchObject()) {
                $infos=false;
                //Sélection DCA
                if($data->Arme_AA3){
                    $dcaID = $data->Arme_AA3;
                }
                else{
                    $dcaID = $data->Arme_AA;
                }
                $resulta->bindParam(':ida', $dcaID, 1);
                $resulta->execute();
                $dataa = $resulta->fetchObject();
                $dcaID = $dataa->Nom;

                //Simulation
                if($dataa->Portee >500)
                    $Malus_Range=5+(($dataa->Portee-500)/100);
                if($dataa->Calibre >40 and $data->Experience<50)
                    $infos.='Possibilité que la DCA semble étrangement silencieuse';
                if($data->Vehicule_Nbr >20)
                    $data->Vehicule_Nbr = 20;
                $Rafale=$data->Vehicule_Nbr;
                if($data->Skill ==30)
                {
                    $Detect+=10;
                    $Bonus_2passe=$data->Experience+50;
                    if($Rafale <($data->Vehicule_Nbr/2))$Rafale+=1;
                }
                $dca_mult=$dataa->Multi*$Rafale;
                if($dca_mult >90)$dca_mult=90;
                $Detect=$data->Experience+$lieuMeteo-$Malus_Range;
                if(!$Detect){
                    $infos.='<br>La défense anti-aérienne ouvrira le feu à l\'aveuglette';
                }
                else{
                    $DCA_Shoots=min($data->Vehicule_Nbr,12); //Nbr Tirs
                    if(in_array($data->Matos,$Matos_mun))
                        $Mun_dca=$data->Matos;
                    else
                        $Mun_dca=0;
                    //$Shoot_Dca=($data->Experience/2)+$dca_mult;
                    $Shoot_Dca=$data->Experience+$dca_mult;
                    if($data->Matos ==3)$Shoot_Dca+=2;
                    elseif($data->Matos ==9)$Shoot_Dca+=5;
                    elseif($data->Matos ==12)$Shoot_Dca+=10;
                    elseif($data->Matos ==22)$Shoot_Dca+=5;
                    $Shoot_base=$Shoot_Dca+$lieuMeteo-$Malus_Range+$Bonus_2passe;
                    $resultpx = $dbh->query("SELECT ID,Pays,Nom,Blindage,Robustesse,VitesseB,Volets,Visibilite FROM Avion WHERE ID IN (14,30,56,13,15,16,29,110,65) ORDER BY Robustesse ASC");
                    while($datapx = $resultpx->fetchObject()){
                        $Shoot=$Shoot_base+$datapx->Visibilite-($datapx->VitesseB/20)-100;
                        $infos.='<tr>
                                 <td>'.GetAvionIcon($datapx->ID,$datapx->Pays,0,0,0,$datapx->Nom).'<br><span class="badge badge-warning">'.$datapx->Robustesse.'</span></td>
                                 <td><a href="#" class="popup"><b>'.$Shoot.'</b><span>'.$txt_aide_shoot.'</span></a><br>
                                   '.GetDgSim($dataa->Degats, $datapx->Blindage, $Shoot, $dca_mult, $data->Matos, $Mun_dca, $dataa->Calibre, 500, $dataa->Perf, $dataa->Portee, $dataa->Portee_max).'
                                 </td>
                                 <td><a href="#" class="popup"><b>'.($Shoot-50).'</b><span>'.$txt_aide_shoot.'</span></a><br>
                                   '.GetDgSim($dataa->Degats, $datapx->Blindage, $Shoot-50, $dca_mult, $data->Matos, $Mun_dca, $dataa->Calibre, 500, $dataa->Perf, $dataa->Portee, $dataa->Portee_max).'
                                  </td>
                                 <td><a href="#" class="popup"><b>'.($Shoot-100).'</b><span>'.$txt_aide_shoot.'</span></a><br>
                                   '.GetDgSim($dataa->Degats, $datapx->Blindage, $Shoot-100, $dca_mult, $data->Matos, $Mun_dca, $dataa->Calibre, 500, $dataa->Perf, $dataa->Portee, $dataa->Portee_max).'
                                  </td>
                                 <td><a href="#" class="popup"><b>'.($Shoot-150).'</b><span>'.$txt_aide_shoot.'</span></a><br>
                                   '.GetDgSim($dataa->Degats, $datapx->Blindage, $Shoot-150, $dca_mult, $data->Matos, $Mun_dca, $dataa->Calibre, 500, $dataa->Perf, $dataa->Portee, $dataa->Portee_max).'
                                  </td>
                              </tr>';
                    }
                }
                //Affichage
                if($data->Experience >249)
                    $Exp_txt="<span class='badge badge-success'>".$data->Experience."XP</span>";
                elseif($data->Experience >49)
                    $Exp_txt="<span class='badge badge-primary'>".$data->Experience."XP</span>";
                elseif($data->Experience >1)
                    $Exp_txt="<span class='badge badge-warning'>".$data->Experience."XP</span>";
                else
                    $Exp_txt="<span class='badge badge-danger'>".$data->Experience."XP</span>";
                $txt500x.='<tr>
                        <td><button href="#" class="btn btn-secondary" data-toggle="collapse" data-target="#txtaxe'.$data->ID.'">'.$data->ID.'e</button></td>
                        <td><img src="images/'.$data->Pays.'20.gif" alt="'.$data->paysNom.'"></td>
                        <td>'.$data->Vehicule_Nbr.' '.GetVehiculeIcon( $data->Vehicule_ID, $data->Pays, $data->Officier_ID, 0, 0, $data->ID.'e Cie').$Exp_txt.'</td>
                        <td>'.$dcaID.'</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table class="table table-striped collapse" id="txtaxe'.$data->ID.'">
                                <thead>
                                <tr>
                                    <th rowspan="2">Avion</th>
                                    <th colspan="4">Pilote</th>
                                </tr>
                                <tr>
                                    <th>Apte</th>
                                    <th>Entrainé</th>
                                    <th>Chevronné</th>
                                    <th>Vétéran</th>                                
                                </tr>
                                </thead>'.$infos.'
                             </table>
                         </td>
                    </tr>';
            }
        }
        if(IsAxe($country) or $Admin){
            //500m Allié
            $result->execute(array(':cible' => $cible, ':faction' => 2, ':alt' => 500));
            while($data = $result->fetchObject()) {
                $infos=false;
                $Shoot=0;
                //Sélection DCA
                if($data->Arme_AA3){
                    $dcaID = $data->Arme_AA3;
                }
                else{
                    $dcaID = $data->Arme_AA;
                }
                $resulta->bindParam(':ida', $dcaID, 1);
                $resulta->execute();
                $dataa = $resulta->fetchObject();
                $dcaID = $dataa->Nom;

                //Simulation
                if($dataa->Portee >500)
                    $Malus_Range=5+(($dataa->Portee-500)/100);
                if($dataa->Calibre >40 and $data->Experience<50)
                    $infos.='Possibilité que la DCA semble étrangement silencieuse';
                if($data->Vehicule_Nbr >20)
                    $data->Vehicule_Nbr = 20;
                $Rafale=$data->Vehicule_Nbr;
                $Detect=$data->Experience+$lieuMeteo-$Malus_Range;
                if($data->Skill ==30)
                {
                    $Detect+=10;
                    $Bonus_2passe=$data->Experience+50;
                    if($Rafale <($data->Vehicule_Nbr/2))$Rafale+=1;
                }
                $dca_mult=$dataa->Multi*$Rafale;
                if($dca_mult >90)$dca_mult=90;
                if(!$Detect){
                    $infos.='<br>La défense anti-aérienne ouvrira le feu à l\'aveuglette';
                }
                else{
                    $DCA_Shoots=min($data->Vehicule_Nbr,12); //Nbr Tirs
                    if(in_array($data->Matos,$Matos_mun))
                        $Mun_dca=$data->Matos;
                    else
                        $Mun_dca=0;
                    //$Shoot_Dca=($data->Experience/2)+$dca_mult;
                    $Shoot_Dca=$data->Experience+$dca_mult;
                    if($data->Matos ==3)$Shoot_Dca+=2;
                    elseif($data->Matos ==9)$Shoot_Dca+=5;
                    elseif($data->Matos ==12)$Shoot_Dca+=10;
                    elseif($data->Matos ==22)$Shoot_Dca+=5;
                    $Shoot_base=$Shoot_Dca+$lieuMeteo-$Malus_Range+$Bonus_2passe;
                    $resultpx = $dbh->query("SELECT ID,Pays,Nom,Blindage,Robustesse,VitesseB,Volets,Visibilite FROM Avion WHERE ID IN (18,19,22,72,77,150,74,80,62) ORDER BY Robustesse ASC");
                    while($datapx = $resultpx->fetchObject()){
                        $Shoot=$Shoot_base+$datapx->Visibilite-($datapx->VitesseB/20)-100;
                        $infos.='<tr>
                                 <td>'.GetAvionIcon($datapx->ID,$datapx->Pays,0,0,0,$datapx->Nom).'<br><span class="badge badge-warning">'.$datapx->Robustesse.'</span></td>
                                 <td><a href="#" class="popup"><b>'.$Shoot.'</b><span>'.$txt_aide_shoot.'</span></a><br>
                                   '.GetDgSim($dataa->Degats, $datapx->Blindage, $Shoot, $dca_mult, $data->Matos, $Mun_dca, $dataa->Calibre, 500, $dataa->Perf, $dataa->Portee, $dataa->Portee_max).'
                                 </td>
                                 <td><a href="#" class="popup">'.($Shoot-50).'<span>'.$txt_aide_shoot.'</span></a><br>
                                   '.GetDgSim($dataa->Degats, $datapx->Blindage, $Shoot-50, $dca_mult, $data->Matos, $Mun_dca, $dataa->Calibre, 500, $dataa->Perf, $dataa->Portee, $dataa->Portee_max).'
                                  </td>
                                 <td><a href="#" class="popup">'.($Shoot-100).'<span>'.$txt_aide_shoot.'</span></a><br>
                                   '.GetDgSim($dataa->Degats, $datapx->Blindage, $Shoot-100, $dca_mult, $data->Matos, $Mun_dca, $dataa->Calibre, 500, $dataa->Perf, $dataa->Portee, $dataa->Portee_max).'
                                  </td>
                                 <td><a href="#" class="popup">'.($Shoot-150).'<span>'.$txt_aide_shoot.'</span></a><br>
                                   '.GetDgSim($dataa->Degats, $datapx->Blindage, $Shoot-150, $dca_mult, $data->Matos, $Mun_dca, $dataa->Calibre, 500, $dataa->Perf, $dataa->Portee, $dataa->Portee_max).'
                                  </td>
                              </tr>';
                    }
                }
                //Affichage
                if($data->Experience >249)
                    $Exp_txt="<span class='badge badge-success'>".$data->Experience."XP</span>";
                elseif($data->Experience >49)
                    $Exp_txt="<span class='badge badge-primary'>".$data->Experience."XP</span>";
                elseif($data->Experience >1)
                    $Exp_txt="<span class='badge badge-warning'>".$data->Experience."XP</span>";
                else
                    $Exp_txt="<span class='badge badge-danger'>".$data->Experience."XP</span>";
                $txt500a.='<tr>
                        <td><a href="#" class="btn btn-secondary" data-toggle="collapse" data-target="#txtallies'.$data->ID.'">'.$data->ID.'e</a></td>
                        <td><img src="images/'.$data->Pays.'20.gif" alt="'.$data->paysNom.'"></td>
                        <td>'.$data->Vehicule_Nbr.' '.GetVehiculeIcon( $data->Vehicule_ID, $data->Pays, $data->Officier_ID, 0, 0, $data->ID.'e Cie').$Exp_txt.'</td>
                        <td>'.$dcaID.'</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table class="table table-striped collapse" id="txtallies'.$data->ID.'">
                                <thead>
                                <tr>
                                    <th rowspan="2">Avion</th>
                                    <th colspan="4">Pilote</th>
                                </tr>
                                <tr>
                                    <th>Apte</th>
                                    <th>Entrainé</th>
                                    <th>Chevronné</th>
                                    <th>Vétéran</th>                                
                                </tr>
                                </thead>'.$infos.'
                             </table>
                         </td>
                    </tr>';
            }
        }
        //OUTPUT
        echo '
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta name="description" content="">
                <meta name="author" content="JF Binote">
                <title>Aube des Aigles : DCA</title>
                <link href="css/bs4/bootstrap.min.css" rel="stylesheet">
                <link href="css/avion.css" rel="stylesheet">
                <style>
                    table{text-align: center;}
                    th{text-align: center;}
                </style>
            </head>
            <body>
                <main class="container-fluid">
                    <h1>'.$lieuNom.'</h1>
                    <h2>Efficacité de la DCA à 500m</h2>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Unité</th>
                            <th>Nation</th>
                            <th>Troupes</th>
                            <th>DCA</th>
                        </tr>
                        </thead>
                        '.$txt500x.'
                        '.$txt500a.'
                    </table>               
                </main>
                <footer class="bg-inverse text-center text-success">&copy;JF-2017</footer>
                <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
                <script>window.jQuery || document.write(\'<script src="../../assets/js/vendor/jquery.min.js"><\/script>\')</script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
                <script src="js/bs4/bootstrap.min.js"></script>
            </body>
        </html>';
    }
    else
        echo 'Contenu réservé aux utilisateurs Premium!';
}
else
    echo '<img src="images/top_secret.gif"><div class="alert alert-danger">Ces données sont classifiées.<br>Votre rang ne vous permet pas d\'accéder à ces informations.</div>';

