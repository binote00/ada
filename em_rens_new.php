<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $Admin)
	{
	    //Couleur par défaut des boutons
        for($y=1;$y<=5;$y++){
            $disbtn='disabled_btn'.$y;
            $$disbtn=false;
            for($x=1;$x<=10;$x++){
                $evcol='ev'.$x.$y.'_col';
                $$evcol='primary';
            }
        }
        //Textes
        $ev1_txts=array(
            'Liste de tous les dépôts du front et de leur type de production éventuelle',
            'Indique si une ressource est présente ou absente dans tous les dépôts du front',
            'Estimation du stock de carburant de tous les dépôts du front',
            'Estimation du stock de munitions de tous les dépôts du front',
            'Etat précis des stocks de tous les dépôts du front'
        );
        $ev2_txts=array(
            'Nom et activité des commandants du front',
            'Noms et activité de tous les membres des Etat-Majors du front',
            'Liste des armées du front, de leurs commandants et de leurs bases arrières',
            'Missions de coordination et objectifs du front',
            'Activité de tous les joueurs du front'
        );
        $ev3_txts=array(
            'Terrain de tous les lieux du front visible sur la carte',
            'Etat des infrastructures de tous les lieux du front',
            'Niveaux de DCA et de fortifications de tous les lieux du front',
            'Effectifs des garnisons de tous les lieux du front',
            'Détail des champs de mines de tous les lieux du front'
        );
        $ev4_txts=array(
            'Météo des lieux de la nation d\'origine',
            'Météo des lieux du front revendiqués par la nation et où se trouvent des troupes de la nation',
            'Météo des lieux du front revendiqués par les alliés et où se trouvent des troupes alliées',
            'Météo des lieux désignés par des missions de coordination au moment du passage de date',
            'Météo de tous les lieux du front'
        );
        $ev5_txts=array(
            'Liste des avions avec leur date de fin de production et leur production totale',
            'Taux de production quotidien',
            'Etat des stocks',
            'Quantité en service dans les unités du front',
            'Usine de production'
        );
        $ev6_txts=array(
            'Liste des véhicules avec leur date de fin de production et leur production totale',
            'Taux de production quotidien',
            'Etat des stocks',
            'Quantité en service dans les unités du front',
            'Usine de production'
        );
        $ev7_txts=array(
            'Liste de toutes les unités aériennes actives du front et leur type',
            'Matériel utilisé et pays dans lequel elle opère',
            'Nom du commandant, effectifs et niveau de réputation',
            'Nombre de pilotes et base de l\'unité',
            'Etat de la DCA, du camouflage, des stocks et mission actuelle'
        );
        $ev8_txts=array(
            'Liste de toutes les unités actives du front et leur type',
            'Matériel utilisé et pays dans lequel elle opère',
            'Division d\'appartenance, effectifs et expérience',
            'Demande de mission et matériel équipé',
            'Lieu où se trouve l\'unité'
        );
        $ev9_txts=['+20% de chance de fournir une information aléatoire à l\'espionnage ennemi','+30% de chance de fournir une information aléatoire à l\'espionnage ennemi','+40% de chance de fournir une information aléatoire à l\'espionnage ennemi','+50% de chance de fournir une information aléatoire à l\'espionnage ennemi','+60% de chance de fournir une information aléatoire à l\'espionnage ennemi'];
        $ev10_txts=['-10% espionnage ennemi','-20% espionnage ennemi','-30% espionnage ennemi','-40% espionnage ennemi','-40% espionnage ennemi<br>+10% de chance de fournir une information aléatoire à l\'espionnage ennemi'];
        dbconnect();
        $result=$dbh->prepare("SELECT ev1,ev2,ev3,ev4,ev5,ev6,ev7,ev8,ev9,ev10 FROM Pays WHERE Pays_ID=:country AND Front=:front");
        $result->bindValue('country',$country,1);
        $result->bindValue('front',$Front,1);
        $result->execute();
        $data=$result->fetchObject();
        $ev1_ori=$data->ev1;
        $ev2_ori=$data->ev2;
        $ev3_ori=$data->ev3;
        $ev4_ori=$data->ev4;
        $ev5_ori=$data->ev5;
        $ev6_ori=$data->ev6;
        $ev7_ori=$data->ev7;
        $ev8_ori=$data->ev8;
        $ev9_ori=$data->ev9;
        $ev10_ori=$data->ev10;
	    if(isset($_POST['ev1'])){
            $ev1 = Insec($_POST['ev1']);
            $ev_field='ev1';
        }
        else{
	        $ev1=$ev1_ori;
        }
        if(isset($_POST['ev2'])){
            $ev2 = Insec($_POST['ev2']);
            $ev_field='ev2';
        }
        else{
            $ev2=$ev2_ori;
        }
        if(isset($_POST['ev3'])){
            $ev3 = Insec($_POST['ev3']);
            $ev_field='ev3';
        }
        else{
            $ev3=$ev3_ori;
        }
        if(isset($_POST['ev4'])){
            $ev4 = Insec($_POST['ev4']);
            $ev_field='ev4';
        }
        else{
            $ev4=$ev4_ori;
        }
        if(isset($_POST['ev5'])){
            $ev5 = Insec($_POST['ev5']);
            $ev_field='ev5';
        }
        else{
            $ev5=$ev5_ori;
        }
        if(isset($_POST['ev6'])){
            $ev6 = Insec($_POST['ev6']);
            $ev_field='ev6';
        }
        else{
            $ev6=$ev6_ori;
        }
        if(isset($_POST['ev7'])){
            $ev7 = Insec($_POST['ev7']);
            $ev_field='ev7';
        }
        else{
            $ev7=$ev7_ori;
        }
        if(isset($_POST['ev8'])){
            $ev8 = Insec($_POST['ev8']);
            $ev_field='ev8';
        }
        else{
            $ev8=$ev8_ori;
        }
        if(isset($_POST['ev9'])){
            $ev9 = Insec($_POST['ev9']);
            $ev_field='ev9';
        }
        else{
            $ev9=$ev9_ori;
        }
        if(isset($_POST['ev10'])){
            $ev10 = Insec($_POST['ev10']);
            $ev_field='ev10';
        }
        else{
            $ev10=$ev10_ori;
        }
        if($OfficierEMID ==$Commandant or $Admin){
            if($ev_field){
                $result2=$dbh->prepare("UPDATE Pays SET $ev_field=:ev WHERE Pays_ID=:country AND Front=:front");
                $result2->bindValue('ev',$$ev_field,1);
                $result2->bindValue('country',$country,1);
                $result2->bindValue('front',$Front,1);
                $result2->execute();
            }
            $total=$ev1+$ev2+$ev3+$ev4+$ev5+$ev6+$ev7+$ev8+$ev9+$ev10;
            if($total >=100){
                $disabled_btn5 = 'disabled';
                $disabled_btn4 = 'disabled';
                $disabled_btn3 = 'disabled';
                $disabled_btn2 = 'disabled';
                $disabled_btn1 = 'disabled';
            }
            elseif($total >=90){
                $disabled_btn5 = 'disabled';
                $disabled_btn4 = 'disabled';
                $disabled_btn3 = 'disabled';
                $disabled_btn2 = 'disabled';
            }
            elseif($total >=80){
                $disabled_btn5 = 'disabled';
                $disabled_btn4 = 'disabled';
                $disabled_btn3 = 'disabled';
            }
            elseif($total >=70){
                $disabled_btn5 = 'disabled';
                $disabled_btn4 = 'disabled';
            }
            elseif($total >=60){
                $disabled_btn5 = 'disabled';
            }
        }

        if($ev1 >=50){
            $ev15_col = 'success';
            $ev1_txt=$ev1_txts[0].'<br>'.$ev1_txts[1].'<br>'.$ev1_txts[2].'<br>'.$ev1_txts[3].'<br>'.$ev1_txts[4];
        }elseif($ev1 ==40){
            $ev14_col = 'success';
            $ev1_txt=$ev1_txts[0].'<br>'.$ev1_txts[1].'<br>'.$ev1_txts[2].'<br>'.$ev1_txts[3];
        }elseif($ev1 ==30){
            $ev13_col = 'success';
            $ev1_txt=$ev1_txts[0].'<br>'.$ev1_txts[1].'<br>'.$ev1_txts[2];
        }elseif($ev1 ==20){
            $ev12_col = 'success';
            $ev1_txt=$ev1_txts[0].'<br>'.$ev1_txts[1];
        }elseif($ev1 ==10){
            $ev11_col = 'success';
            $ev1_txt=$ev1_txts[0];
        }
        if($ev2 >=50){
            $ev25_col = 'success';
            $ev2_txt=$ev2_txts[0].'<br>'.$ev2_txts[1].'<br>'.$ev2_txts[2].'<br>'.$ev2_txts[3].'<br>'.$ev2_txts[4];
        }elseif($ev2 ==40){
            $ev24_col = 'success';
            $ev2_txt=$ev2_txts[0].'<br>'.$ev2_txts[1].'<br>'.$ev2_txts[2].'<br>'.$ev2_txts[3];
        }elseif($ev2 ==30){
            $ev23_col = 'success';
            $ev2_txt=$ev2_txts[0].'<br>'.$ev2_txts[1].'<br>'.$ev2_txts[2];
        }elseif($ev2 ==20){
            $ev22_col = 'success';
            $ev2_txt=$ev2_txts[0].'<br>'.$ev2_txts[1];
        }elseif($ev2 ==10){
            $ev21_col = 'success';
            $ev2_txt=$ev2_txts[0];
        }
        if($ev3 >=50){
            $ev35_col = 'success';
            $ev3_txt=$ev3_txts[0].'<br>'.$ev3_txts[1].'<br>'.$ev3_txts[2].'<br>'.$ev3_txts[3].'<br>'.$ev3_txts[4];
        }elseif($ev3 ==40){
            $ev34_col = 'success';
            $ev3_txt=$ev3_txts[0].'<br>'.$ev3_txts[1].'<br>'.$ev3_txts[2].'<br>'.$ev3_txts[3];
        }elseif($ev3 ==30){
            $ev33_col = 'success';
            $ev3_txt=$ev3_txts[0].'<br>'.$ev3_txts[1].'<br>'.$ev3_txts[2];
        }elseif($ev3 ==20){
            $ev32_col = 'success';
            $ev3_txt=$ev3_txts[0].'<br>'.$ev3_txts[1];
        }elseif($ev3 ==10){
            $ev31_col = 'success';
            $ev3_txt=$ev3_txts[0];
        }
        if($ev4 >=50){
            $ev45_col = 'success';
            $ev4_txt=$ev4_txts[0].'<br>'.$ev4_txts[1].'<br>'.$ev4_txts[2].'<br>'.$ev4_txts[3].'<br>'.$ev4_txts[4];
        }elseif($ev4 ==40){
            $ev44_col = 'success';
            $ev4_txt=$ev4_txts[0].'<br>'.$ev4_txts[1].'<br>'.$ev4_txts[2].'<br>'.$ev4_txts[3];
        }elseif($ev4 ==30){
            $ev43_col = 'success';
            $ev4_txt=$ev4_txts[0].'<br>'.$ev4_txts[1].'<br>'.$ev4_txts[2];
        }elseif($ev4 ==20){
            $ev42_col = 'success';
            $ev4_txt=$ev4_txts[0].'<br>'.$ev4_txts[1];
        }elseif($ev4 ==10){
            $ev41_col = 'success';
            $ev4_txt=$ev4_txts[0];
        }
        if($ev5 >=50){
            $ev55_col = 'success';
            $ev5_txt=$ev5_txts[0].'<br>'.$ev5_txts[1].'<br>'.$ev5_txts[2].'<br>'.$ev5_txts[3].'<br>'.$ev5_txts[4];
        }elseif($ev5 ==40){
            $ev54_col = 'success';
            $ev5_txt=$ev5_txts[0].'<br>'.$ev5_txts[1].'<br>'.$ev5_txts[2].'<br>'.$ev5_txts[3];
        }elseif($ev5 ==30){
            $ev53_col = 'success';
            $ev5_txt=$ev5_txts[0].'<br>'.$ev5_txts[1].'<br>'.$ev5_txts[2];
        }elseif($ev5 ==20){
            $ev52_col = 'success';
            $ev5_txt=$ev5_txts[0].'<br>'.$ev5_txts[1];
        }elseif($ev5 ==10){
            $ev51_col = 'success';
            $ev5_txt=$ev5_txts[0];
        }
        if($ev6 >=50){
            $ev65_col = 'success';
            $ev6_txt=$ev6_txts[0].'<br>'.$ev6_txts[1].'<br>'.$ev6_txts[2].'<br>'.$ev6_txts[3].'<br>'.$ev6_txts[4];
        }elseif($ev6 ==40){
            $ev64_col = 'success';
            $ev6_txt=$ev6_txts[0].'<br>'.$ev6_txts[1].'<br>'.$ev6_txts[2].'<br>'.$ev6_txts[3];
        }elseif($ev6 ==30){
            $ev63_col = 'success';
            $ev6_txt=$ev6_txts[0].'<br>'.$ev6_txts[1].'<br>'.$ev6_txts[2];
        }elseif($ev6 ==20){
            $ev62_col = 'success';
            $ev6_txt=$ev6_txts[0].'<br>'.$ev6_txts[1];
        }elseif($ev6 ==10){
            $ev61_col = 'success';
            $ev6_txt=$ev6_txts[0];
        }
        if($ev7 >=50){
            $ev75_col = 'success';
            $ev7_txt=$ev7_txts[0].'<br>'.$ev7_txts[1].'<br>'.$ev7_txts[2].'<br>'.$ev7_txts[3].'<br>'.$ev7_txts[4];
        }elseif($ev7 ==40){
            $ev74_col = 'success';
            $ev7_txt=$ev7_txts[0].'<br>'.$ev7_txts[1].'<br>'.$ev7_txts[2].'<br>'.$ev7_txts[3];
        }elseif($ev7 ==30){
            $ev73_col = 'success';
            $ev7_txt=$ev7_txts[0].'<br>'.$ev7_txts[1].'<br>'.$ev7_txts[2];
        }elseif($ev7 ==20){
            $ev72_col = 'success';
            $ev7_txt=$ev7_txts[0].'<br>'.$ev7_txts[1];
        }elseif($ev7 ==10){
            $ev71_col = 'success';
            $ev7_txt=$ev7_txts[0];
        }
        if($ev8 >=50){
            $ev85_col = 'success';
            $ev8_txt=$ev8_txts[0].'<br>'.$ev8_txts[1].'<br>'.$ev8_txts[2].'<br>'.$ev8_txts[3].'<br>'.$ev8_txts[4];
        }elseif($ev8 ==40){
            $ev84_col = 'success';
            $ev8_txt=$ev8_txts[0].'<br>'.$ev8_txts[1].'<br>'.$ev8_txts[2].'<br>'.$ev8_txts[3];
        }elseif($ev8 ==30){
            $ev83_col = 'success';
            $ev8_txt=$ev8_txts[0].'<br>'.$ev8_txts[1].'<br>'.$ev8_txts[2];
        }elseif($ev8 ==20){
            $ev82_col = 'success';
            $ev8_txt=$ev8_txts[0].'<br>'.$ev8_txts[1];
        }elseif($ev8 ==10){
            $ev81_col = 'success';
            $ev8_txt=$ev8_txts[0];
        }
        if($ev9 >=50){
            $ev95_col = 'success';
            $ev9_txt='+60% de chance de fournir une information aléatoire à l\'espionnage ennemi';
        }elseif($ev9 ==40){
            $ev94_col = 'success';
            $ev9_txt='+50% de chance de fournir une information aléatoire à l\'espionnage ennemi';
        }elseif($ev9 ==30){
            $ev93_col = 'success';
            $ev9_txt='+40% de chance de fournir une information aléatoire à l\'espionnage ennemi';
        }elseif($ev9 ==20){
            $ev92_col = 'success';
            $ev9_txt='+30% de chance de fournir une information aléatoire à l\'espionnage ennemi';
        }elseif($ev9 ==10){
            $ev91_col = 'success';
            $ev9_txt='+20% de chance de fournir une information aléatoire à l\'espionnage ennemi';
        }
        if($ev10 >=50){
            $ev105_col = 'success';
            $ev10_txt='-40% espionnage ennemi<br>+10% de chance de fournir une information aléatoire à l\'espionnage ennemi';
        }elseif($ev10 ==40){
            $ev104_col = 'success';
            $ev10_txt='-40% espionnage ennemi';
        }elseif($ev10 ==30){
            $ev103_col = 'success';
            $ev10_txt='-30% espionnage ennemi';
        }elseif($ev10 ==20){
            $ev102_col = 'success';
            $ev10_txt='-20% espionnage ennemi';
        }elseif($ev10 ==10){
            $ev101_col = 'success';
            $ev10_txt='-10% espionnage ennemi';
        }

	    echo '
        <div class="alert alert-warning">
            <ul>
                <li>100% à répartir</li>
                <li>Les effets s\'appliquent au passage de date pour une durée de 24h</li>
                <li>Si aucun changement n\'est effectué, les effets du jour précédent sont reconduits le jour suivant</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6">
            <h2>Espionnage</h2>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>Domaine</th>
                    <th>Niveau</th>
                </tr>
                </thead>
                <tr>
                    <td><a href="#clp-ev1" data-toggle="collapse" class="lien">Dépôts<i class="caret"></i></a></td>
                    <td>
                     <div class="i-flex">
                        <form action="#" method="post"><input type="hidden" name="ev1" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                        <form action="#" method="post"><input type="hidden" name="ev1" value="10"><input type="submit" class="btn btn-sm btn-'.$ev11_col.'" value="10%" '.$disabled_btn1.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev1" value="20"><input type="submit" class="btn btn-sm btn-'.$ev12_col.'" value="20%" '.$disabled_btn2.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev1" value="30"><input type="submit" class="btn btn-sm btn-'.$ev13_col.'" value="30%" '.$disabled_btn3.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev1" value="40"><input type="submit" class="btn btn-sm btn-'.$ev14_col.'" value="40%" '.$disabled_btn4.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev1" value="50"><input type="submit" class="btn btn-sm btn-'.$ev15_col.'" value="50%" '.$disabled_btn5.'></form>                     
                     </div>
                     <p>'.$ev1_txt.'</p>
                    </td>
                </tr>
                <tr class="collapse" id="clp-ev1"><td colspan="2">
                    <table class="table">
                        <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                        <tr>
                        <td>'.$ev1_txts[0].'</td>
                        <td>'.$ev1_txts[1].'</td>
                        <td>'.$ev1_txts[2].'</td>
                        <td>'.$ev1_txts[3].'</td>
                        <td>'.$ev1_txts[4].'</td>
                        </tr>
                    </table></td>
                </tr>
                <tr>
                    <td><a href="#clp-ev2" data-toggle="collapse" class="lien">Etat-Major<i class="caret"></i></a></td>
                    <td>
                     <div class="i-flex">
                        <form action="#" method="post"><input type="hidden" name="ev2" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                        <form action="#" method="post"><input type="hidden" name="ev2" value="10"><input type="submit" class="btn btn-sm btn-'.$ev21_col.'" value="10%" '.$disabled_btn1.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev2" value="20"><input type="submit" class="btn btn-sm btn-'.$ev22_col.'" value="20%" '.$disabled_btn2.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev2" value="30"><input type="submit" class="btn btn-sm btn-'.$ev23_col.'" value="30%" '.$disabled_btn3.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev2" value="40"><input type="submit" class="btn btn-sm btn-'.$ev24_col.'" value="40%" '.$disabled_btn4.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev2" value="50"><input type="submit" class="btn btn-sm btn-'.$ev25_col.'" value="50%" '.$disabled_btn5.'></form>                     
                     </div>
                     <p>'.$ev2_txt.'</p>
                    </td>
                </tr>
                <tr class="collapse" id="clp-ev2"><td colspan="2">
                    <table class="table">
                        <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                        <tr>
                        <td>'.$ev2_txts[0].'</td>
                        <td>'.$ev2_txts[1].'</td>
                        <td>'.$ev2_txts[2].'</td>
                        <td>'.$ev2_txts[3].'</td>
                        <td>'.$ev2_txts[4].'</td>
                        </tr>
                    </table></td>
                </tr>
                <tr>
                    <td><a href="#clp-ev3" data-toggle="collapse" class="lien">Lieux<i class="caret"></i></a></td>
                    <td>
                     <div class="i-flex">
                        <form action="#" method="post"><input type="hidden" name="ev3" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                        <form action="#" method="post"><input type="hidden" name="ev3" value="10"><input type="submit" class="btn btn-sm btn-'.$ev31_col.'" value="10%" '.$disabled_btn1.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev3" value="20"><input type="submit" class="btn btn-sm btn-'.$ev32_col.'" value="20%" '.$disabled_btn2.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev3" value="30"><input type="submit" class="btn btn-sm btn-'.$ev33_col.'" value="30%" '.$disabled_btn3.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev3" value="40"><input type="submit" class="btn btn-sm btn-'.$ev34_col.'" value="40%" '.$disabled_btn4.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev3" value="50"><input type="submit" class="btn btn-sm btn-'.$ev35_col.'" value="50%" '.$disabled_btn5.'></form>                     
                     </div>
                     <p>'.$ev3_txt.'</p>
                    </td>
                </tr>
                <tr class="collapse" id="clp-ev3"><td colspan="2">
                    <table class="table">
                        <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                        <tr>
                        <td>'.$ev3_txts[0].'</td>
                        <td>'.$ev3_txts[1].'</td>
                        <td>'.$ev3_txts[2].'</td>
                        <td>'.$ev3_txts[3].'</td>
                        <td>'.$ev3_txts[4].'</td>
                        </tr>
                    </table></td>
                </tr>
                <tr>
                    <td><a href="#clp-ev4" data-toggle="collapse" class="lien">Météo<i class="caret"></i></a></td>
                    <td>
                     <div class="i-flex">
                        <form action="#" method="post"><input type="hidden" name="ev4" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                        <form action="#" method="post"><input type="hidden" name="ev4" value="10"><input type="submit" class="btn btn-sm btn-'.$ev41_col.'" value="10%" '.$disabled_btn1.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev4" value="20"><input type="submit" class="btn btn-sm btn-'.$ev42_col.'" value="20%" '.$disabled_btn2.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev4" value="30"><input type="submit" class="btn btn-sm btn-'.$ev43_col.'" value="30%" '.$disabled_btn3.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev4" value="40"><input type="submit" class="btn btn-sm btn-'.$ev44_col.'" value="40%" '.$disabled_btn4.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev4" value="50"><input type="submit" class="btn btn-sm btn-'.$ev45_col.'" value="50%" '.$disabled_btn5.'></form>                     
                     </div>
                     <p>'.$ev4_txt.'</p>
                    </td>
                </tr>
                <tr class="collapse" id="clp-ev4"><td colspan="2">
                    <table class="table">
                        <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                        <tr>
                        <td>'.$ev4_txts[0].'</td>
                        <td>'.$ev4_txts[1].'</td>
                        <td>'.$ev4_txts[2].'</td>
                        <td>'.$ev4_txts[3].'</td>
                        <td>'.$ev4_txts[4].'</td>
                        </tr>
                    </table></td>
                </tr>
                <tr>
                    <td><a href="#clp-ev5" data-toggle="collapse" class="lien">Production aérienne<i class="caret"></i></a></td>
                    <td>
                     <div class="i-flex">
                        <form action="#" method="post"><input type="hidden" name="ev5" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                        <form action="#" method="post"><input type="hidden" name="ev5" value="10"><input type="submit" class="btn btn-sm btn-'.$ev51_col.'" value="10%" '.$disabled_btn1.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev5" value="20"><input type="submit" class="btn btn-sm btn-'.$ev52_col.'" value="20%" '.$disabled_btn2.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev5" value="30"><input type="submit" class="btn btn-sm btn-'.$ev53_col.'" value="30%" '.$disabled_btn3.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev5" value="40"><input type="submit" class="btn btn-sm btn-'.$ev54_col.'" value="40%" '.$disabled_btn4.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev5" value="50"><input type="submit" class="btn btn-sm btn-'.$ev55_col.'" value="50%" '.$disabled_btn5.'></form>                     
                     </div>
                     <p>'.$ev5_txt.'</p>
                    </td>
                </tr>
                <tr class="collapse" id="clp-ev5"><td colspan="2">
                    <table class="table">
                        <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                        <tr>
                        <td>'.$ev5_txts[0].'</td>
                        <td>'.$ev5_txts[1].'</td>
                        <td>'.$ev5_txts[2].'</td>
                        <td>'.$ev5_txts[3].'</td>
                        <td>'.$ev5_txts[4].'</td>
                        </tr>
                    </table></td>
                </tr>
                <tr>
                    <td><a href="#clp-ev6" data-toggle="collapse" class="lien">Production terrestre<i class="caret"></i></a></td>
                    <td>
                     <div class="i-flex">
                        <form action="#" method="post"><input type="hidden" name="ev6" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                        <form action="#" method="post"><input type="hidden" name="ev6" value="10"><input type="submit" class="btn btn-sm btn-'.$ev61_col.'" value="10%" '.$disabled_btn1.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev6" value="20"><input type="submit" class="btn btn-sm btn-'.$ev62_col.'" value="20%" '.$disabled_btn2.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev6" value="30"><input type="submit" class="btn btn-sm btn-'.$ev63_col.'" value="30%" '.$disabled_btn3.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev6" value="40"><input type="submit" class="btn btn-sm btn-'.$ev64_col.'" value="40%" '.$disabled_btn4.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev6" value="50"><input type="submit" class="btn btn-sm btn-'.$ev65_col.'" value="50%" '.$disabled_btn5.'></form>                     
                     </div>
                     <p>'.$ev6_txt.'</p>
                    </td>
                </tr>
                <tr class="collapse" id="clp-ev6"><td colspan="2">
                    <table class="table">
                        <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                        <tr>
                        <td>'.$ev6_txts[0].'</td>
                        <td>'.$ev6_txts[1].'</td>
                        <td>'.$ev6_txts[2].'</td>
                        <td>'.$ev6_txts[3].'</td>
                        <td>'.$ev6_txts[4].'</td>
                        </tr>
                    </table></td>
                </tr>
                <tr>
                    <td><a href="#clp-ev7" data-toggle="collapse" class="lien">Unité aérienne<i class="caret"></i></a></td>
                    <td>
                     <div class="i-flex">
                        <form action="#" method="post"><input type="hidden" name="ev7" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                        <form action="#" method="post"><input type="hidden" name="ev7" value="10"><input type="submit" class="btn btn-sm btn-'.$ev71_col.'" value="10%" '.$disabled_btn1.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev7" value="20"><input type="submit" class="btn btn-sm btn-'.$ev72_col.'" value="20%" '.$disabled_btn2.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev7" value="30"><input type="submit" class="btn btn-sm btn-'.$ev73_col.'" value="30%" '.$disabled_btn3.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev7" value="40"><input type="submit" class="btn btn-sm btn-'.$ev74_col.'" value="40%" '.$disabled_btn4.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev7" value="50"><input type="submit" class="btn btn-sm btn-'.$ev75_col.'" value="50%" '.$disabled_btn5.'></form>                     
                     </div>
                     <p>'.$ev7_txt.'</p>
                    </td>
                </tr>
                <tr class="collapse" id="clp-ev7"><td colspan="2">
                    <table class="table">
                        <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                        <tr>
                        <td>'.$ev7_txts[0].'</td>
                        <td>'.$ev7_txts[1].'</td>
                        <td>'.$ev7_txts[2].'</td>
                        <td>'.$ev7_txts[3].'</td>
                        <td>'.$ev7_txts[4].'</td>
                        </tr>
                    </table></td>
                </tr>
                <tr>
                    <td><a href="#clp-ev8" data-toggle="collapse" class="lien">Unité terrestre<i class="caret"></i></a></td>
                    <td>
                     <div class="i-flex">
                        <form action="#" method="post"><input type="hidden" name="ev8" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                        <form action="#" method="post"><input type="hidden" name="ev8" value="10"><input type="submit" class="btn btn-sm btn-'.$ev81_col.'" value="10%" '.$disabled_btn1.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev8" value="20"><input type="submit" class="btn btn-sm btn-'.$ev82_col.'" value="20%" '.$disabled_btn2.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev8" value="30"><input type="submit" class="btn btn-sm btn-'.$ev83_col.'" value="30%" '.$disabled_btn3.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev8" value="40"><input type="submit" class="btn btn-sm btn-'.$ev84_col.'" value="40%" '.$disabled_btn4.'></form>
                        <form action="#" method="post"><input type="hidden" name="ev8" value="50"><input type="submit" class="btn btn-sm btn-'.$ev85_col.'" value="50%" '.$disabled_btn5.'></form>                     
                     </div>
                     <p>'.$ev8_txt.'</p>
                    </td>
                </tr>
                <tr class="collapse" id="clp-ev8"><td colspan="2">
                    <table class="table">
                        <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                        <tr>
                        <td>'.$ev8_txts[0].'</td>
                        <td>'.$ev8_txts[1].'</td>
                        <td>'.$ev8_txts[2].'</td>
                        <td>'.$ev8_txts[3].'</td>
                        <td>'.$ev8_txts[4].'</td>
                        </tr>
                    </table></td>
                </tr>
            </table>            
            </div>
            <div class="col-md-12 col-lg-6">
                 <h2>Contre-Espionnage</h2>
                 <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Domaine</th>
                        <th>Niveau</th>
                    </tr>
                    </thead>
                    <tr>
                        <td><a href="#clp-ev9" data-toggle="collapse" class="lien">Falsification<i class="caret"></i></a></td>
                        <td>
                         <div class="i-flex">
                            <form action="#" method="post"><input type="hidden" name="ev9" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                            <form action="#" method="post"><input type="hidden" name="ev9" value="10"><input type="submit" class="btn btn-sm btn-'.$ev91_col.'" value="10%" '.$disabled_btn1.'></form>
                            <form action="#" method="post"><input type="hidden" name="ev9" value="20"><input type="submit" class="btn btn-sm btn-'.$ev92_col.'" value="20%" '.$disabled_btn2.'></form>
                            <form action="#" method="post"><input type="hidden" name="ev9" value="30"><input type="submit" class="btn btn-sm btn-'.$ev93_col.'" value="30%" '.$disabled_btn3.'></form>
                            <form action="#" method="post"><input type="hidden" name="ev9" value="40"><input type="submit" class="btn btn-sm btn-'.$ev94_col.'" value="40%" '.$disabled_btn4.'></form>
                            <form action="#" method="post"><input type="hidden" name="ev9" value="50"><input type="submit" class="btn btn-sm btn-'.$ev95_col.'" value="50%" '.$disabled_btn5.'></form>                     
                         </div>
                         <p>'.$ev9_txt.'</p>
                        </td>
                    </tr>
                    <tr class="collapse" id="clp-ev9"><td colspan="2">
                        <table class="table">
                            <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                            <tr>
                            <td>'.$ev9_txts[0].'</td>
                            <td>'.$ev9_txts[1].'</td>
                            <td>'.$ev9_txts[2].'</td>
                            <td>'.$ev9_txts[3].'</td>
                            <td>'.$ev9_txts[4].'</td>
                            </tr>
                        </table></td>
                    </tr>
                    <tr>
                        <td><a href="#clp-ev10" data-toggle="collapse" class="lien">Protection<i class="caret"></i></a></td>
                        <td>
                         <div class="i-flex">
                            <form action="#" method="post"><input type="hidden" name="ev10" value="0"><input type="submit" class="btn btn-sm btn-danger" value="0%"></form>
                            <form action="#" method="post"><input type="hidden" name="ev10" value="10"><input type="submit" class="btn btn-sm btn-'.$ev101_col.'" value="10%" '.$disabled_btn1.'></form>
                            <form action="#" method="post"><input type="hidden" name="ev10" value="20"><input type="submit" class="btn btn-sm btn-'.$ev102_col.'" value="20%" '.$disabled_btn2.'></form>
                            <form action="#" method="post"><input type="hidden" name="ev10" value="30"><input type="submit" class="btn btn-sm btn-'.$ev103_col.'" value="30%" '.$disabled_btn3.'></form>
                            <form action="#" method="post"><input type="hidden" name="ev10" value="40"><input type="submit" class="btn btn-sm btn-'.$ev104_col.'" value="40%" '.$disabled_btn4.'></form>
                            <form action="#" method="post"><input type="hidden" name="ev10" value="50"><input type="submit" class="btn btn-sm btn-'.$ev105_col.'" value="50%" '.$disabled_btn5.'></form>                     
                         </div>
                         <p>'.$ev10_txt.'</p>
                        </td>
                    </tr>
                    <tr class="collapse" id="clp-ev10"><td colspan="2">
                        <table class="table">
                            <tr><th>10%</th><th>20%</th><th>30%</th><th>40%</th><th>50%</th></tr>
                            <tr>
                            <td>'.$ev10_txts[0].'</td>
                            <td>'.$ev10_txts[1].'</td>
                            <td>'.$ev10_txts[2].'</td>
                            <td>'.$ev10_txts[3].'</td>
                            <td>'.$ev10_txts[4].'</td>
                            </tr>
                        </table></td>
                    </tr>
                </table>      
                <div class="alert alert-info"><b>Pourcentages répartis</b><p>'.$total.'/100%</p></div>
            </div>
        </div>';
	}
	else
		PrintNoAccess($country,1);
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';

