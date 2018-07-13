<?
require_once('./jfv_inc_sessions.php');
$Officier_em=$_SESSION['Officier_em'];
if($Officier_em >0)
{
	$country=$_SESSION['country'];
    require_once('./jfv_include.inc.php');
	$Front=GetData("Officier_em","ID",$Officier_em,"Front");
	if($Front !=12)
	{
        $Off = Insec($_POST['off']);
        $Armee = Insec($_POST['mut']);
        include_once('./jfv_txt.inc.php');
		$con=dbconnecti();
        $Commandant=mysqli_result(mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$country' AND Front='$Front'"),0);
        $Postuler=mysqli_result(mysqli_query($con,"SELECT Postuler FROM Officier_em WHERE ID='$Off'"),0);
		mysqli_close($con);
		if($Officier_em ==$Commandant or $Admin)
		{
		    if(!$Postuler)$Postuler=1;
			if($Off and $Armee and $Postuler)
			{
                require_once('./jfv_msg.inc.php');
				if($Armee ==9999){
				    if($Postuler ==21)
                        $Poste='Commandant';
                    elseif($Postuler ==2)
                        $Poste='Adjoint_EM';
                    elseif($Postuler ==3)
                        $Poste='Officier_EM';
                    elseif($Postuler ==6)
                        $Poste='Adjoint_Terre';
                    elseif($Postuler ==7)
                        $Poste='Officier_Mer';
                    elseif($Postuler ==8)
                        $Poste='Officier_Log';
                    else
                        exit;
                    $queryup="UPDATE Officier_em SET Armee=0,Mutation=0,Postuler=0 WHERE ID='$Off'";
                    $queryup2="UPDATE Pays SET $Poste='$Off' WHERE Pays_ID='$country' AND Front='$Front'";
                }else{
				    $queryup="UPDATE Officier_em SET Armee='$Armee',Mutation=0,Postuler=0 WHERE ID='$Off'";
                    $queryup2="UPDATE Armee SET Cdt='$Off' WHERE ID='$Armee'";
                }
				$con=dbconnecti();
				$reset=mysqli_query($con,$queryup);
				$reset2=mysqli_query($con,$queryup2);
				mysqli_close($con);
				if($reset)
				{
					$Corps=GetEM_Name($country);
					$Msg="Bonjour Officier,\n Votre demande de mutation a été acceptée.\n Votre nouvelle affectation prend effet immédiatement.\n\n ".$Corps;
					SendMsgOff($Off,$Officier_em,$Msg,"Demande de mutation",1,1);
					$mes="Un courrier avec votre décision a été envoyé à l'officier";
				}
				else
					$mes='Une erreur est survenue, veuillez le signaler sur le forum.';
				$titre='Demande de mutation';
			}
			else
				$mes='[Erreur]';
            include_once('./default.php');
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';