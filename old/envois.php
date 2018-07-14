<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country = $_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_access.php');
	include_once('./menu_messagerie.php');
	if($Admin)
		$query="SELECT ID,Reception,Expediteur,`Date`,Sujet,Lu,Rec_em FROM Messages WHERE Expediteur IN(0,'$PlayerID') ORDER BY ID DESC LIMIT 50";
	else
		$query="SELECT ID,Reception,Expediteur,`Date`,Sujet,Lu,Rec_em FROM Messages WHERE Expediteur='$PlayerID' AND `Date` > '".$Date_msg_db."' ORDER BY ID DESC LIMIT 50";
	$con=dbconnecti(3);
	$ok=mysqli_query($con,$query);
	mysqli_close($con);
	if($ok)
	{
		while($data=mysqli_fetch_array($ok)) 
		{
			if($data['Reception'])
			{
				if($data['Rec_em'])
					$Reception_nom=GetData("Officier_em","ID",$data['Reception'],"Nom");
				else
					$Reception_nom=GetData("Pilote","ID",$data['Reception'],"Nom");
			}
			else
				$Reception_nom="[Animation]";
			$Msg_Off = $data['Date'].' '.$Reception_nom.' : '.$data['Sujet'];
			if($data['Lu'])
				$Lire="<input type='Submit' value='Lire (lu)' class='btn btn-success'></form></td></tr>";
			else
				$Lire="<input type='Submit' value='Lire (non lu)' class='btn btn-default'></form></td></tr>";
			if($Admin and $data['Expediteur'] ==0)
				$Msg.="<tr><td align='left'>[ANIM] ".$Msg_Off."</td><td><form action='index.php?view=msg_envoi' method='post'><input type='hidden' name='mes' value='".$data['ID']."'>".$Lire;
			else
				$Msg.="<tr><td align='left'>".$Msg_Off."</td><td><form action='index.php?view=msg_envoi' method='post'><input type='hidden' name='mes' value='".$data['ID']."'>".$Lire;
		}
		mysqli_free_result($ok);
	}
	else
		$Msg="<tr><td align='left'>Vide</td></tr>";
	echo "<table class='table'><thead><tr><th colspan='2'>Boite d'envoi</th></tr></thead></table>";
	echo "<div style='overflow:auto; height: 600px;'><table>".$Msg."</table></div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>