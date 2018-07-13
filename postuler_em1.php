<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
    $Poste = Insec($_POST['poste']);
    $Pays = Insec($_POST['country']);
    $Front = Insec($_POST['Front']);
    $Off = Insec($_POST['off']);
    $OfficierEMID=$_SESSION['Officier_em'];
    if($OfficierEMID >0 and $Off ==$OfficierEMID and $Poste >0 and $Pays >0)
	{
        include_once('./jfv_txt.inc.php');
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Officier_em SET Postuler='$Poste',Mutation=9999 WHERE ID='$OfficierEMID'");
        $Cdt=mysqli_result(mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$Pays' AND Front='$Front'"),0);
        $Nom_Off=mysqli_result(mysqli_query($con,"SELECT Nom FROM Officier_em WHERE ID='$OfficierEMID'"),0);
		mysqli_close($con);
		if($Cdt){
            require_once('./jfv_msg.inc.php');
            $Msg="L\'officier <b>".$Nom_Off."</b> a postulé pour le poste de <b>".GetPosteEM($Poste)."</b>.\n Veuillez accepter ou refuser la demande dans le menu Etat-Major / Staff.\n\n ";
            SendMsgOff($Cdt,$OfficierEMID,$Msg,"Demande de mutation",2,1);
            $mes="Votre demande a été transmise à votre Commandant en Chef qui vous donnera réponse sous peu.";
        }
        else{
		    $mes="<div class='alert alert-warning'>Le poste de Commandant en Chef de votre front est vacant. Votre demande a été transmise, mais le délai de réponse risque d'être long. Veuillez exposer votre cas sur le forum.</div>";
            $txt="<b>".$Nom."</b> (ID : ".$OfficierEMID.") postule pour le poste de <b>".GetPosteEM($Poste)."</b> de la nation <b>".GetPays($Pays)."</b> sur le front <b>".GetFront($Front)."</b>";
            mail('binote@hotmail.com','Aube des Aigles: Un officier EM postule',$txt);
        }
        $titre='Postuler à une fonction d\'Etat-Major';
		$img="<img src='images/poste".$Pays.".jpg'>";
		include_once('./default.php');
	}
	else
		echo "<h1>Votre personnage n'est pas autorisé à postuler pour ce poste!</h1>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';