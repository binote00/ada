<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$Pilote=Insec($_POST['pil']);
	$Unite=Insec($_POST['mut']);
	$country=$_SESSION['country'];
	if($OfficierEMID >0)
	{
		$con=dbconnecti();	
		$result8=mysqli_query($con,"SELECT Front,Admin FROM Officier_em WHERE ID='$OfficierEMID'");
		mysqli_close($con);
		if($result8)
		{
			while($data8=mysqli_fetch_array($result8, MYSQLI_ASSOC))
			{
				$Front=$data8['Front'];
				$Admin=$data8['Admin'];
			}
			mysqli_free_result($result8);
		}
		$Commandant=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
		//$Officier_Adjoint=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Adjoint_EM");	
		include_once('./menu_em.php');
		if($OfficierEMID ==$Commandant or $Admin)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET Mutation=0 WHERE ID='$Pilote'");
			mysqli_close($con);
			if($reset)
			{
				$Corps=GetEM_Name($country);
				$Msg="Bonjour Pilote,\n Votre demande de mutation a été refusée.\n Vous pouvez néanmoins prendre contact avec nos services pour de plus amples informations.\n\n ".$Corps;
				SendMsgOff($Pilote,$OfficierEMID,$Msg,"Demande de mutation",1,3);
				echo '<div class="alert alert-warning">Un courrier avec votre décision a été envoyé au pilote';
			}
			else
				echo '<div class="alert alert-danger">Une erreur est survenue, veuillez le signaler sur le forum.</div>';
		}
		else
			PrintNoAccess($country,1);
	}
}
else
	echo '<div class="alert alert-danger">Vous devez être connecté pour accéder à cette page!</div>';
?>
