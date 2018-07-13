<?
require_once('./jfv_inc_sessions.php');
$Officier=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
$PlayerID=$_SESSION['PlayerID'];
if($Officier >0 or $OfficierEMID >0 or $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./menu_messagerie_gr.php');
	if($Officier >0)
		$query="SELECT ID,Reception,Expediteur,DATE_FORMAT(`Date`,'%d-%m-%Y à %Hh%i') AS `Date`,Sujet,Lu,Rec_em FROM Ada_Messages WHERE Expediteur='$Officier' AND Exp_em='2' AND `Date` >'".$Date_msg_db."' ORDER BY ID DESC LIMIT 50";
	elseif($OfficierEMID >0)
		$query="SELECT ID,Reception,Expediteur,DATE_FORMAT(`Date`,'%d-%m-%Y à %Hh%i') AS `Date`,Sujet,Lu,Rec_em FROM Ada_Messages WHERE Expediteur='$OfficierEMID' AND Exp_em='1' AND `Date` >'".$Date_msg_db."' ORDER BY ID DESC LIMIT 50";
	elseif($PlayerID >0)
		$query="SELECT ID,Reception,Expediteur,DATE_FORMAT(`Date`,'%d-%m-%Y à %Hh%i') AS `Date`,Sujet,Lu,Rec_em FROM Ada_Messages WHERE Expediteur='$PlayerID' AND Exp_em='3' AND `Date` >'".$Date_msg_db."' ORDER BY ID DESC LIMIT 50";
	$con=dbconnecti(3);
	$ok=mysqli_query($con,$query);
	mysqli_close($con);
	if($ok)
	{
		while($data=mysqli_fetch_array($ok)) 
		{
			if($data['Reception'])
			{
				if($data['Rec_em'] ==1)
					$Reception_nom=GetData("Officier_em","ID",$data['Reception'],"Nom");
				elseif($data['Rec_em'] ==3)
					$Reception_nom=GetData("Pilote","ID",$data['Reception'],"Nom");
				elseif($data['Rec_em'] ==2)
					$Reception_nom=GetData("Officier","ID",$data['Reception'],"Nom");
				else
					$Reception_nom="[No-Reply]";
			}
			else
				$Reception_nom="[No-Reply]";
			$Msg_Off=$data['Date'].' : <b>'.$Reception_nom.'</b><hr>'.$data['Sujet'];
			if($data['Lu'])
				$Lire="<input type='Submit' value='Lire (lu)' class='btn btn-success'></form></td>";
			else
				$Lire="<input type='Submit' value='Lire (non lu)' class='btn btn-default'></form></td>";
			if($Admin and $data['Expediteur'] ==0)
				$Msg.="<tr><td><form action='index.php?view=ground_msg_envoi' method='post'><input type='hidden' name='mes' value='".$data['ID']."'>".$Lire."<td align='left'>[ANIM] ".$Msg_Off."</td></tr>";
			else
				$Msg.="<tr></td><td><form action='index.php?view=ground_msg_envoi' method='post'><input type='hidden' name='mes' value='".$data['ID']."'>".$Lire."<td align='left'>".$Msg_Off."</td></tr>";
		}
		mysqli_free_result($ok);
	}
	else
		$Msg="<tr><td align='left'>Vide</td></tr>";
	echo "<table class='table'><thead><tr><th>Boite d'envoi</th></tr></thead></table>";
	echo "<div style='overflow:auto; height: 600px;'><table class='table table-striped'>".$Msg."</table></div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>