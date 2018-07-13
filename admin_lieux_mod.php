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
        $lieu=$_POST['lieu'];
        $resultl = $dbh->prepare("SELECT ValeurStrat FROM Lieu WHERE ID=:lieu");
        $resultl->bindValue('lieu',$lieu,1);
        $resultl->execute();
        $datal = $resultl->fetchObject();
        $ValeurStrat = $datal->ValeurStrat;
        $query_add='';
        if(isset($_POST['piste']) && isset($_POST['pister'])){
            if($_POST['piste'] >2000)$_POST['piste']=2000;
            if($_POST['pister'] >3)$_POST['piste']=3;
            $uplieu = $dbh->prepare("UPDATE Lieu SET Base_Ori=:pister,BaseAerienne=:pister,Tour=100,QualitePiste=100,LongPiste=:piste,LongPiste_Ori=:piste WHERE ID=:id");
            $uplieu->bindValue('pister',$_POST['pister'],1);
            $uplieu->bindValue('piste',$_POST['piste'],1);
        }
        else{
            if(isset($_POST['fort'])){
                if($_POST['fort'] >100)$_POST['fort']=100;
                $query_add.=",Fortification=".Insec($_POST['fort']);
            }
            if(isset($_POST['garnison'])){
                $garnison_max=($ValeurStrat*200)+100;
                if($_POST['garnison'] >$garnison_max)$_POST['garnison']=$garnison_max;
                $query_add.=",Garnison=".Insec($_POST['garnison']);
            }
            if(isset($_POST['gare'])){
                $query_add.=",NoeudF_Ori=100";
            }
            if(isset($_POST['port'])){
                $query_add.=",Port_Ori=100";
            }
            if(isset($_POST['pont'])){
                $query_add.=",Pont_Ori=100";
            }
            if(isset($_POST['radar'])){
                $query_add.=",Radar_Ori=100";
            }
            if(isset($_POST['usine'])){
                $query_add.=",TypeIndus='x',Industrie=100";
            }
            if(isset($_POST['oil'])){
                if($_POST['oil'] >10)$_POST['oil']=10;
                $query_add.=",Oil=".Insec($_POST['oil']);
            }
            if(isset($_POST['route'])){
                $query_add.=",NoeudR=1";
            }
            $uplieu = $dbh->prepare("UPDATE Lieu SET Travel=1".$query_add." WHERE ID=:id");
        }
        $uplieu->bindValue('id',$lieu,1);
        $uplieu->execute();
        header('Location: ./index.php?view=admin_lieux');
    }
}