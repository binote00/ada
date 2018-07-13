<?require_once('./jfv_inc_sessions.php');
$Officier_em=$_SESSION['Officier_em'];
if($Officier_em >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$Front=GetData("Officier_em","ID",$Officier_em,"Front");
	if($Front !=12)
	{
        include_once('./jfv_txt.inc.php');
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
			if($Off)
			{
                $queryup="UPDATE Officier_em SET Mutation=0,Postuler=0 WHERE ID='$Off'";
                $con=dbconnecti();
                $reset=mysqli_query($con,$queryup);
                mysqli_close($con);
				include_once('./jfv_msg.inc.php');
				$Corps=GetEM_Name($country);
				$Msg="Bonjour Officier,\n Votre demande de mutation a été refusée.\n Vous pouvez néanmoins prendre contact avec nos services pour de plus amples informations.\n\n ".$Corps;
				SendMsgOff($Off,$Officier_em,$Msg,"Demande de mutation",1,1);
				$mes="Un courrier avec votre décision a été envoyé à l'officier";		
				$titre='Etat-Major Terrestre';
				include_once('./default.php');
			}
			else
				echo '[Erreur]';
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';