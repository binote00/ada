<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');

$Pers=Insec($_POST['Pers_char']);
$Slot=Insec($_POST['Pers_slot']);
$Unite=Insec($_POST['Unite']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $Unite >0)
{
    $CT4=4;
	$country=$_SESSION['country'];
    $con=dbconnecti();
    $result2=mysqli_query($con,"SELECT Commandant,Officier_Adjoint FROM Unit WHERE ID='$Unite'");
    $results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
    mysqli_close($con);
    if($result2)
    {
        while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
            $Commandant=$data['Commandant'];
            $Officier_Adjoint=$data['Officier_Adjoint'];
        }
        mysqli_free_result($result2);
    }
    if($results)
    {
        while($data=mysqli_fetch_array($results,MYSQLI_ASSOC)){
            $Skills_Pil[]=$data['Skill'];
        }
        mysqli_free_result($results);
    }
    if(is_array($Skills_Pil)){
        if(in_array(107,$Skills_Pil))
            $Organisateur2=true;
        if($Organisateur2){
            $CT4=2;
        }
    }
    $Credits_Ori=GetData("Pilote","ID",$PlayerID,"Credits");
	if($Credits_Ori >=$CT4 and ($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint))
	{
		if($Pers){
			$Slot='Pers'.$Slot;
			SetData("Unit",$Slot,$Pers,"ID",$Unite);
		}
		$Credits=-$CT4;
		$credits_txt=MoveCredits($PlayerID,3,$Credits);
		UpdateCarac($PlayerID,"Avancement",-$Credits);
		//UpdateCarac($PlayerID,"Gestion",-$Credits);
		//UpdateCarac($PlayerID,"Commandement",-$Credits);
		if(GetData("Pilote","ID",$PlayerID,"Credits") <0)
			SetData("Pilote","Endurance",0,"ID",$PlayerID);
		if(!$mes)$mes='<p>Vos ordres ont été exécutés!</p>';
		$img='<img src="images/transfer_yes'.$country.'.jpg">';
		include_once('./index.php');
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';