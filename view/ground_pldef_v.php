<?php
if($choix){
    $card_pldef='';
    for($i=500;$i<=5000;$i+=500){
        $choix_d='choix_'.$i;
        if($$choix_d){
            $card_pldef.="<div class='col-md-1'>
                            <div class='panel panel-war'>
                                <div class='panel-heading'>".$i."m</div>
                                <div class='panel-body'>".$$choix_d."</div>                                    
                            </div>
                        </div>";
        }
    }
    $mes="<form action='index.php?view=".$dive."' method='post'>
                    <input type='hidden' name='CT' value='".$CT."'>
                    <input type='hidden' name='Veh' value='".$Veh."'>
                    <input type='hidden' name='Reg' value='".$Reg."'>
                    <input type='hidden' name='Pass' value='".$Pass."'>
                    <input type='hidden' name='Line' value='".$Inf_eni."'>
                    <input type='hidden' name='Max_Range' value='".$Max_Range."'>
                    <input type='hidden' name='Mode' value='".$Bomb."'>
                    <h2>Cibles repérées ".GetPlace($Placement)."</h2>
                    <div style='overflow:auto;'>
                        <div class='row'>
                            <div class='col-lg-10 col-md-12'>
                                <div class='col-md-1'>
                                    <div class='panel panel-war'>
                                        <div class='panel-heading'>Ligne de front</div>
                                        <div class='panel-body'>".$choix_100."</div>                                    
                                    </div>
                                </div>".$card_pldef."
                            </div>
                            <div class='col-lg-2 col-md-2'>
                                <div class='panel panel-war'>
                                    <div class='panel-heading'>+5000m</div>
                                    <div class='panel-body'>".$choix_5500."</div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <Input type='Radio' name='Action' value='0' checked>- Annuler l'attaque.<br>
                    ".$Distance_tir.$Repli.$Armement."
                    <input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
                    <div class='alert alert-info'>".$Aide."</div>";
}
else{
    $mes='<h2>Aucune cible n\'a été repérée '.GetPlace($Placement).'</h2><a href="index.php?view=ground_em_ia_list"><span class="btn btn-danger">ANNULER</span></a>';
}
$mes.="<div class='alert alert-info'>".$Aide."</div>";
include_once('../default.php');