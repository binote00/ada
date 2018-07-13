<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Armee=Insec($_POST['Action']);
    $Front=Insec($_POST['front']);
    $OfficierEMID=$_SESSION['Officier_em'];
    $country=$_SESSION['country'];
	if($OfficierEMID >0 and $Armee and $country)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Officier_em SET Postuler=1,Mutation='$Armee' WHERE ID='$OfficierEMID'");
        $Cdt=mysqli_result(mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$country' AND Front='$Front'"),0);
        $Nom_Off=mysqli_result(mysqli_query($con,"SELECT Nom FROM Officier_em WHERE ID='$OfficierEMID'"),0);
		mysqli_close($con);
		if($Cdt){
            require_once('./jfv_msg.inc.php');
            $Msg="L\'officier ".$Nom_Off." a postulé pour le commandement d\'une armée.\n Veuillez accepter ou refuser la demande dans le menu Etat-Major / Staff.\n\n ";
            SendMsgOff($Cdt,$OfficierEMID,$Msg,"Demande de mutation",2,1);
            $mes="Votre demande a été transmise à votre Commandant en Chef qui vous donnera réponse sous peu.";
        }
        else{
		    $mes="<div class='alert alert-warning'>Le poste de Commandant en Chef de votre front est vacant. Votre demande a été transmise, mais le délai de réponse risque d'être long. Veuillez exposer votre cas sur le forum.</div>";
        }
        $titre='Postuler';
		$img="<img src='images/poste".$country.".jpg'>";
		include_once('./default.php');
	}
	else
		echo "<h1>Votre personnage n'est pas autorisé à postuler pour ce poste!</h1>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';