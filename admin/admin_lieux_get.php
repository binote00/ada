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
        $lieu=$_GET['id'];
        if($lieu){
            include_once('./jfv_txt.inc.php');
            $resultv = $dbh->prepare("SELECT * FROM Lieu WHERE ID=:id");
            $resultv->bindValue('id',$lieu,1);
            $resultv->execute();
            $data = $resultv->fetchObject();
            $Base_air = $data->BaseAerienne;
            for($i=0;$i<=2000;$i+=10){
                if($data->LongPiste_Ori ==$i)
                    $piste_sel.='<option value="'.$i.'" selected>'.$i.'</option>';
                else
                    $piste_sel.='<option value="'.$i.'">'.$i.'</option>';
            }
            if($Base_air){
                $airfield_txt='<img src="images/piste'.$Base_air.'_'.GetQualitePiste_img($data->QualitePiste).'.jpg" alt="Base aérienne">';
                if($Base_air ==1)
                    $pister_sel='<option value="0">Aucune</option><option value="1" selected>Dur</option><option value="3">Herbe</option><option value="2">Hydravion</option>';
                elseif($Base_air ==2)
                    $pister_sel='<option value="0">Aucune</option><option value="1">Dur</option><option value="3">Herbe</option><option value="2" selected>Hydravion</option>';
                elseif($Base_air ==3)
                    $pister_sel='<option value="0">Aucune</option><option value="1">Dur</option><option value="3" selected>Herbe</option><option value="2">Hydravion</option>';
            }else{
                $airfield_txt='<img src="images/piste32_'.GetQualitePiste_img($data->QualitePiste).'.jpg" alt="Base aérienne">';
                $pister_sel='<option value="0">Aucune</option><option value="1">Dur</option><option value="3">Herbe</option><option value="2">Hydravion</option>';
            }
            if($data->NoeudR){
                $route_sel='<option value="0">Non</option><option value="1" selected>Oui</option>';
                $route_txt='Présent';
            }
            else{
                $route_sel='<option value="0">Non</option><option value="1">Oui</option>';
            }
            if($data->NoeudF_Ori){
                $gare_sel='<option value="0">Non</option><option value="1" selected>Oui</option>';
                $gare_txt=$data->NoeudF.'%';
            }
            else{
                $gare_sel='<option value="0">Non</option><option value="1">Oui</option>';
            }
            if($data->Pont_Ori){
                $pont_sel='<option value="0">Non</option><option value="1" selected>Oui</option>';
                $pont_txt=$data->Pont.'%';
            }
            else{
                $pont_sel='<option value="0">Non</option><option value="1">Oui</option>';
            }
            if($data->Port_Ori){
                $port_sel='<option value="0">Non</option><option value="1" selected>Oui</option>';
                $port_txt=$data->Port.'%';
            }
            else{
                $port_sel='<option value="0">Non</option><option value="1">Oui</option>';
            }
            if($data->Radar_Ori){
                $radar_sel='<option value="0">Non</option><option value="1" selected>Oui</option>';
                $radar_txt=$data->Radar.'%';
            }
            else{
                $radar_sel='<option value="0">Non</option><option value="1">Oui</option>';
            }
            if($data->TypeIndus){
                $usine_sel='<option value="0">Non</option><option value="1" selected>Oui</option>';
                $usine_txt=$data->Industrie.'%';
            }
            else{
                $usine_sel='<option value="0">Non</option><option value="1">Oui</option>';
            }
            if($data->Oil){
                $oil_txt='Level '.$data->Oil;
            }
            for($i=0;$i<=20;$i++){
                if($data->Oil ==$i)
                    $oil_sel.='<option value="'.$i.'" selected>'.$i.'</option>';
                else
                    $oil_sel.='<option value="'.$i.'">'.$i.'</option>';
            }
            for($i=0;$i<=100;$i+=10){
                if($data->Fortification ==$i)
                    $fort_sel.='<option value="'.$i.'" selected>'.$i.'</option>';
                else
                    $fort_sel.='<option value="'.$i.'">'.$i.'</option>';
            }
            $max_garnison=($data->ValeurStrat*200)+100;
            if($data->Garnison ==$max_garnison)
                $gar_sel_txt='<span class="text-danger"> Maximum!</span>';
            for($i=0;$i<=$max_garnison;$i+=10){
                if($data->Garnison ==$i)
                    $garnison_sel.='<option value="'.$i.'" selected>'.$i.'</option>';
                else
                    $garnison_sel.='<option value="'.$i.'">'.$i.'</option>';
            }
            echo '
            <div class="panel panel-war">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-3"><img src="images/flag'.$data->Pays.'p.jpg" alt="Nation Origine"></div>
                        <div class="col-sm-6">'.$data->Nom.'</div>
                        <div class="col-sm-3"><img src="images/flag'.$data->Flag.'p.jpg" alt="Revendication"></div>
                    </div>                
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-2"><img src="images/strat'.$data->ValeurStrat.'.png" alt=""></div>
                    </div>
                    <form action="admin_lieux_mod.php" method="post">
                    <div class="row">
                        <div class="col-sm-3"><img src="images/icone_fort.gif" alt="Fortifications"></div>
                        <div class="col-sm-3"><select name="fort" id="fort">'.$fort_sel.'</select></div>
                        <div class="col-sm-3"><img src="images/vehicules/vehicule107.gif" alt="Garnison"></div>
                        <div class="col-sm-3"><select name="garnison" id="garnison">'.$garnison_sel.'</select>'.$gar_sel_txt.'</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">'.$airfield_txt.'<br>'.$data->LongPiste.'m/'.$data->LongPiste_Ori.'m</div>
                        <div class="col-sm-3">
                            <select name="piste" id="piste">'.$piste_sel.'</select>
                            <select name="pister" id="pister">'.$pister_sel.'</select>
                        </div>
                        <div class="col-sm-3"><img src="images/vehicules/vehicule15.gif" alt="Radar"><br>'.$radar_txt.'</div>
                        <div class="col-sm-3"><select name="radar" id="radar">'.$radar_sel.'</select></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><img src="images/map/lieu_route'.$data->Flag_Route.'.png" alt="Noeud Routier"><br>'.$route_txt.'</div>
                        <div class="col-sm-3"><select name="route" id="route">'.$route_sel.'</select></div>
                        <div class="col-sm-3"><img src="images/vehicules/vehicule9.gif" alt="Gare"><br>'.$gare_txt.'</div>
                        <div class="col-sm-3"><select name="gare" id="gare">'.$gare_sel.'</select></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><img src="images/vehicules/vehicule10.gif" alt="Pont"><br>'.$pont_txt.'</div>
                        <div class="col-sm-3"><select name="pont" id="pont">'.$pont_sel.'</select></div>
                        <div class="col-sm-3"><img src="images/vehicules/vehicule12.gif" alt="Port"><br>'.$port_txt.'</div>
                        <div class="col-sm-3"><select name="port" id="port">'.$port_sel.'</select></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><img src="images/vehicules/vehicule5.gif" alt="Usine"><br>'.$usine_txt.'</div>
                        <div class="col-sm-3"><select name="usine" id="usine">'.$usine_sel.'</select></div>
                        <div class="col-sm-3"><img src="images/map/icone_oil.gif" alt="Raffinerie"><br>'.$oil_txt.'</div>
                        <div class="col-sm-3"><select name="oil" id="oil">'.$oil_sel.'</select></div>
                    </div>
                    <hr><input type="submit" class="btn btn-danger" value="Modifier"></form>
                </div>
             </div>';
        }
        else{
            echo 'Aucun lieu sélectionné';
        }
    }
}