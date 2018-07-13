<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	$Off=Insec($_POST['Off']);
	$Division=Insec($_POST['Div']);
	if($Off and $Division)
	{
		$country=$_SESSION['country'];
		$con=dbconnecti();	
		$Front=mysqli_result(mysqli_query($con,"SELECT Front FROM Division WHERE ID='$Division'"),0);
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
		if(!$Commandant)$Commandant=GetDoubleData("Officier_em","Pays",$country,"Front",99,"ID");
		if($Commandant)
		{
			include_once('./jfv_msg.inc.php');
			SendMsgOff($Commandant,$Off,$Msg,"Demande de mutation",2,1);
			SetData("Officier","Mutation",$Division,"ID",$Off);
			$mes="Votre demande de mutation a été prise en compte et sera examinée par l'état-major.";
		}
		else
			$mes="L'état-major de ce front n'est pas actif, veuillez le signaler sur le forum.";
		$img="<img src='images/em".$country.".jpg' style='width:50%;'>";
	}
	$titre="Demande de mutation";
	include_once('./index.php');
}
?>