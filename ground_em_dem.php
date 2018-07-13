<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
    include_once('./jfv_include.inc.php');
    $Armee=Insec($_POST['army']);
    $Depot=Insec($_POST['depot']);
    $Ressource=Insec($_POST['res']);
    $Troops=Insec($_POST['troop']);
    $OfficierEMID=$_SESSION['Officier_em'];
    if($OfficierEMID >0 and $Armee >0 and (($Depot >0 and $Ressource >0) or $Troops))
    {
        include_once('./jfv_txt.inc.php');
        $country=$_SESSION['country'];
        $con=dbconnecti();
        $Front=mysqli_result(mysqli_query($con,"SELECT Front FROM Officier_em WHERE ID='$OfficierEMID'"),0);
        $armee_nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Armee WHERE ID='$Armee'"),0);
        if($Troops){
            $resultem=mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
            if($resultem){
                while($dataem=mysqli_fetch_array($resultem,MYSQLI_ASSOC)){
                    $Officier_Log=$dataem['Commandant'];
                }
                mysqli_free_result($resultem);
                unset($dataem);
            }
            $Func_txt=GetGenStaff($country,1);
            $Dem_txt = 'Renfort';
            $res_txt=GetData("Veh_Type","ID",$Troops,"Type");
            $Msg = 'Le commandant de la <b>'.$armee_nom.'</b> demande un renfort de <b>'.$res_txt.'</b>';
        }else{
            $depot_nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Lieu WHERE ID='$Depot'"),0);
            $resultem=mysqli_query($con,"SELECT Commandant,Officier_Log FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
            if($resultem){
                while($dataem=mysqli_fetch_array($resultem,MYSQLI_ASSOC)){
                    $Officier_Log=$dataem['Officier_Log'];
                    if(!$Officier_Log)
                        $Officier_Log=$dataem['Commandant'];
                }
                mysqli_free_result($resultem);
                unset($dataem);
            }
            if($Ressource ==1){
                $res_txt = 'Diesel';
            }
            elseif($Ressource ==87){
                $res_txt = 'Essence Octane 87';
            }
            else{
                $res_txt = $Ressource.'mm';
            }
            $Func_txt=GetGenStaff($country,6);
            $Dem_txt = 'Ravitaillement';
            $Msg = 'Le commandant de la <b>'.$armee_nom.'</b> demande un ravitaillement de <b>'.$res_txt.'</b> dans le dépôt de <b>'.$depot_nom.'</b>';
        }
        mysqli_close($con);
        if($Officier_Log){
            require_once('./jfv_msg.inc.php');
            SendMsgOff($Officier_Log,$OfficierEMID,$Msg,"Demande de ".$Dem_txt,1,1);
            $_SESSION['msg_em'] = 'Demande envoyée au '.$Func_txt.' du front!';
        }
        else{
            $_SESSION['msg_em_red'] = 'Votre demande a été annulée car aucun joueur n\'occupe la fonction '.$Func_txt.' sur ce front!';
        }
        header('Location: ./index.php?view=ground_em');
    }
    else
        echo '<h1>Vous n\'êtes pas autorisé à effectuer cette action!</h1>';
}
else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';