﻿<?require_once('./jfv_inc_sessions.php');
$Officier_em=$_SESSION['Officier_em'];
if($Officier_em >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Front=GetData("Officier_em","ID",$Officier_em,"Front");
	if($Front !=12)
	{
		$con=dbconnecti();	
		$result2=mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Commandant=$data['Commandant'];
			}
			mysqli_free_result($result2);
		}
		if($Officier_em ==$Commandant or $Admin)
		{
			$Off = Insec($_POST['off']);
			$Unite = Insec($_POST['mut']);
			if($Off and $Unite)
			{
				include_once('./jfv_msg.inc.php');
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Officier SET Division='$Unite',Mutation=0 WHERE ID='$Off'");
				mysqli_close($con);
				if($reset)
				{
					$Corps=GetEM_Name($country);
					$Msg="Bonjour Officier,\n Votre demande de mutation a été acceptée.\n Votre nouvelle affectation prend effet immédiatement.\n\n ".$Corps;
					SendMsgOff($Off,$Officier_em,$Msg,"Demande de mutation",1,2);
					$mes="Un courrier avec votre décision a été envoyé à l'officier";
				}
				else
					$mes="Une erreur est survenue, veuillez le signaler sur le forum.";
				$titre="Demande de mutation";
				include_once('./default.php');
			}
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>