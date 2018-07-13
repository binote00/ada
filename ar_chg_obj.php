<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Armee=Insec($_POST['Armee']);
	$Objectif=Insec($_POST['obj']);
    $Limite_ouest=Insec($_POST['lo']);
    $Limite_nord=Insec($_POST['ln']);
    $Limite_est=Insec($_POST['le']);
    $Limite_sud=Insec($_POST['ls']);
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0 and $Armee >0 and ($Objectif >0 or $Limite_ouest >0 or $Limite_nord >0 or $Limite_est >0 or $Limite_sud >0))
	{
        $qnbr=0;
        $qpre='';
        $queryadd='';
		if($Objectif){
            $queryadd.="Objectif='$Objectif'";
            $qnbr+=1;
        }
		if($Limite_ouest) {
		    if($qnbr >0)$qpre=',';
            $queryadd.=$qpre."limite_ouest='$Limite_ouest'";
            $qnbr+=1;
        }
        if($Limite_nord) {
            if($qnbr >0)$qpre=',';
            $queryadd.=$qpre."limite_nord='$Limite_nord'";
            $qnbr+=1;
        }
        if($Limite_est) {
            if($qnbr >0)$qpre=',';
            $queryadd.=$qpre."limite_est='$Limite_est'";
            $qnbr+=1;
        }
        if($Limite_sud) {
            if($qnbr >0)$qpre=',';
            $queryadd.=$qpre."limite_sud='$Limite_sud'";
        }
        $con=dbconnecti();
        $reset=mysqli_query($con,"UPDATE Armee SET ".$queryadd." WHERE ID='$Armee'");
		mysqli_close($con);
        header('Location: ./index.php?view=ground_em');
	}
	else
		echo '<h1>Vous n\'êtes pas autorisé à effectuer cette action!</h1>';
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';