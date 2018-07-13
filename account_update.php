<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_msg.inc.php');
include_once('./jfv_txt.inc.php');
if(isset($_SESSION['AccountID']))
{
    $AccountID = $_SESSION['AccountID'];
	$email=Insec($_POST['email']);
	$Pwd=Insec($_POST['password']);
	$reset=Insec($_POST['reset']);
	//$pilote=Insec($_POST['resetsec']);
    if($pilote >0 and $reset ==1)
    {
        $con=dbconnecti();
        $result=mysqli_query($con,"SELECT Pays,Unit FROM Pilote WHERE ID='$pilote'");
        if($result)
        {
            while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
            {
                $Pays=$data['Pays'];
                $Unit=$data['Unit'];
            }
            mysqli_free_result($result);
            unset($data);
        }
        $Training_unit=GetTraining($Pays);
        if($Unit !=$Training_unit)
        {
            $reset1=mysqli_query($con,"UPDATE Unit SET Commandant=NULL WHERE Commandant='$pilote' LIMIT 1");
            $reset2=mysqli_query($con,"UPDATE Unit SET Officier_Technique=NULL WHERE Officier_Technique='$pilote' LIMIT 1");
            $reset3=mysqli_query($con,"UPDATE Unit SET Officier_Adjoint=NULL WHERE Officier_Adjoint='$pilote' LIMIT 1");
        }
        $reset1=mysqli_query($con,"UPDATE Pilote SET Actif=1,Front=0,Unit='$Training_unit',Ailier=0,Mutation=0,Ecole=0,Couverture=0,Couverture_Nuit=0,Escorte=0,Commando=0 WHERE ID='$pilote'");
        $reset3=mysqli_query($con,"UPDATE Joueur SET Pilote_id=0 WHERE Pilote_id='$pilote' LIMIT 1");
        mysqli_close($con);
        $mes.='<br>Votre pilote prend sa retraite!';
        RetireCandidat($PlayerID,"delog");
        session_unset();
        session_destroy();
    }
    else
    {
        dbconnect();
        if($reset){
            $newLand=Insec($_POST['newLand']);
            if($reset ==6) {
                $queryreset = "UPDATE Joueur SET Pays=:land,Actif=0,Pilote_id=NULL,Officier=NULL,Officier_em=NULL,Officier_bonus=NULL WHERE ID=:account";
                $mail_txt = 'COMPTE MODIFIE';
            }elseif($reset ==5){
                $queryreset = "UPDATE Joueur SET Actif=1 WHERE ID=:account";
                $mail_txt = 'COMPTE DESACTIVE';
            }elseif($reset ==4){
                $queryreset = "UPDATE Joueur SET Officier_bonus=NULL WHERE ID=:account";
                $mail_txt = 'OFF PREMIUM DESACTIVE';
            }elseif($reset ==3){
                $queryreset = "UPDATE Joueur SET Officier_em=NULL WHERE ID=:account";
                $mail_txt = 'OFFICIER DESACTIVE';
            }elseif($reset ==2){
                $queryreset = "UPDATE Joueur SET Pilote_id=NULL WHERE ID=:account";
                $mail_txt = 'PILOTE DESACTIVE';
            }
            $num=$dbh->prepare($queryreset);
            $num->bindValue('account',$AccountID,1);
            if($reset ==6){
                $num->bindValue('land',$newLand,1);
            }
            $num->execute();
            mail('binote@hotmail.com', $mail_txt, $AccountID.' a modifié son compte depuis '.$_SERVER['REMOTE_ADDR']);
            session_unset();
            session_destroy();
            header( 'Location : ./index.php');
        }
        if($email)
        {
            if(!IsValidEmail($email))
                $mes.="<br>Votre adresse email n'a pas été mise à jour!<br>Vérifiez la validité de vos données.";
            else{
                $num=$dbh->prepare("UPDATE Joueur SET adresse=:email WHERE ID=:account");
                $num->bindValue('email',$email,2);
                $num->bindValue('account',$AccountID,1);
                $num->execute();
                if(is_object($num))
                    $mes.="<br>Votre adresse email a été mise à jour!";
                else
                    $mes.="<br>Votre adresse email n'a pas été mise à jour!<br>Vérifiez la validité de vos données.";
            }
            header( 'Location : ./index.php?view=compte');
        }
        if($Pwd)
        {
            $Pwd=password_hash($Pwd,PASSWORD_DEFAULT);
            $num=$dbh->prepare("UPDATE Joueur SET Mdp=:mdp WHERE ID=:account");
            $num->bindValue('mdp',$Pwd,2);
            $num->bindValue('account',$AccountID,1);
            $num->execute();
            if(is_object($num))
                $mes.="<br>Votre mot de passe a été mis à jour!";
            else
                $mes.="<br>Votre mot de passe n'a pas été mis à jour!<br>Vérifiez la validité de vos données.";
            header( 'Location : ./index.php?view=compte');
        }
        /*if($login){
            $num=$dbh->prepare("UPDATE Joueur SET login=:login WHERE ID=:account");
            $num->bindValue('login',$login,2);
            $num->bindValue('account',$AccountID,1);
            $num->execute();
            if(is_object($num))
                $mes.="<br>Votre login a été mis à jour!";
            else
                $mes.="<br>Votre login n'a pas été mis à jour!<br>Vérifiez la validité de vos données.";
        }
        if($simu ==1 or $simu ==0){
            $num=$dbh->prepare("UPDATE Joueur SET Simu=:simu WHERE ID=:account");
            $num->bindValue('simu',$simu,1);
            $num->bindValue('account',$AccountID,1);
            $num->execute();
            if(is_object($num)){
                $mes.="<br>Vous avez désactivé le mode simulation!";
            }
            else
                $mes.="<br>L'activation du mode simulation n'a pas été réalisée!<br>Vérifiez la validité de vos données.";
        }
        if($courier){
            $num=$dbh->prepare("UPDATE Joueur SET Courier=:courier WHERE ID=:account");
            $num->bindValue('courier',$courier,1);
            $num->bindValue('account',$AccountID,1);
            $num->execute();
            if(is_object($num))
                $mes.="<br>Vous avez activé le transfert du courier de la poste des armées vers votre boite email!";
            else
                $mes.="<br>L'activation n'a pas été réalisée!<br>Vérifiez la validité de vos données.";
        }*/
    }
    //$img=Afficher_Image('images/account'.$country.'.jpg','images/account'.$country.'.jpg','Compte');
}
else
    header( 'Location : ./index.php');
//include_once('./index.php');