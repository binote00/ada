<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	$country = $_SESSION['country'];
	include_once('./menu_messagerie.php');
	include_once('./jfv_access.php');
	//Msg list
	$Msg=false;
	if($Admin)
		$query="SELECT ID,Reception,Expediteur,`Date`,Sujet,Lu,Exp_em FROM Messages WHERE Reception IN (0,'$PlayerID') AND Rec_em='0' ORDER BY ID DESC LIMIT 100";
	else
		$query="SELECT ID,Reception,Expediteur,`Date`,Sujet,Lu,Exp_em FROM Messages WHERE Reception='$PlayerID' AND Archive='0' AND Rec_em='0' ORDER BY ID DESC LIMIT 50";
	$con=dbconnecti(3);
	//$msc=microtime(true);
	$ok=mysqli_query($con,$query);
	//$msc=microtime(true)-$msc;
	mysqli_close($con);
	if($ok)
	{
		while ($data = mysqli_fetch_array($ok)) 
		{
			if($data['Expediteur'])
			{
				if($data['Exp_em'])
					$Expediteur=GetData("Officier_em","ID",$data['Expediteur'],"Nom");
				else
					$Expediteur=GetData("Pilote","ID",$data['Expediteur'],"Nom");
			}
			else
				$Expediteur="[Animation]";
			$Msg_Off=$data['Date'].' '.$Expediteur.' : '.$data['Sujet'];				
			if($data['Lu'])
				$Lire="<input type='Submit' value='Lire (lu)' class='btn btn-success'></form>";
			else
				$Lire="<input type='Submit' value='Lire (non lu)' class='btn btn-default'></form>";
			if($Admin and $data['Reception'] == 0)
			{
				$Msg.="<tr><td align='left'>[ANIM] ".$Msg_Off."</td>
						<td><form action='index.php?view=messages' method='post'><input type='hidden' name='mes' value='".$data['ID']."'>".$Lire."</td></tr>"; 
			}
			else
			{
				$Msg.="<tr><td align='left'>".$Msg_Off."</td>
						<td><form action='index.php?view=messages' method='post'><input type='hidden' name='mes' value='".$data['ID']."'>".$Lire."</td></tr>"; 
			}
		}
		mysqli_free_result($ok);
		unset($ok);
	}
	else
		$Msg.="<tr><td align='left'>Vide</td></tr>";		
	//Send list
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Pilote WHERE Pays='$country' AND Actif='0' AND ID<>'$PlayerID' ORDER BY Nom ASC");
	$result2=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Officier_em WHERE Pays='$country' AND ID<>'$OfficierEMID' ORDER BY Nom ASC");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result)) 
		{
			 $send_list.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
		}
		mysqli_free_result($result);
	}
	if($result2)
	{
		while($data2=mysqli_fetch_array($result2)) 
		{
			 $send_list.="<option value='".$data2['ID']."_'>[EM] ".$data2['Nom']."</option>";
		}
		mysqli_free_result($result2);
	}
	echo"<table class='table'>
	<thead><tr><th colspan='2'>Boite de réception</th></tr></thead></table>
	<div style='overflow:auto; width:50%; height: 400px;'><table class='table'>
	<tr>".$Msg."</table></div>";	
	if($Admin or $Anim)
	{
		echo"<form action='index.php?view=envoyer' method='post'>
		<input type='hidden' name='em' value='0'>
		<table class='table'><thead><tr><th>Ecrire un message</th></tr></thead></table>
		<table border='0' cellspacing='2' cellpadding='5' bgcolor='#ECDDC1'>
			<tr><td>Expéditeur : </td><td align='left'><select name='exp' style='width: 200px'>
			<option value='".$PlayerID."'>Votre Pilote</option>
			<option value='0'>Un PNJ (précisez le dans la signature du message)</option>
			</select></td></tr>
			<tr><td>Destinataire : </td><td align='left'><select name='destinataire' style='width: 200px'>".$send_list."</select></td></tr>
			<tr><td>Sujet : </td><td align='left'><input type='text' name='Sujet' size='50'></td></tr>
			<tr><td>Message</td><td align='left'><textarea name='msg' rows='5' cols='50'></textarea></td></tr>
			<tr><td><input type='Submit' value='Envoyer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
		</table></form>";
	}
	else
	{
		echo"<form action='index.php?view=envoyer' method='post'>
		<input type='hidden' name='exp' value='".$PlayerID."'>
		<input type='hidden' name='em' value='0'>
		<table class='table'><thead><tr><th>Ecrire un message</th></tr></thead></table>
		<table border='0' cellspacing='2' cellpadding='5' bgcolor='#ECDDC1'>
			<tr><td>Destinataire : </td><td align='left'><select name='destinataire' style='width: 200px'>".$send_list."</select></td></tr>
			<tr><td>Sujet : </td><td align='left'><input type='text' name='Sujet' size='50'></td></tr>
			<tr><td>Message</td><td align='left'><textarea name='msg' rows='5' cols='50'></textarea></td></tr>
			<tr><td><input type='Submit' value='Envoyer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
		</table></form>";
	}
 	/*if($msc > 5)
	{
		mail('binote@hotmail.com','Aube des Aigles: Slow Messagerie',$msc.' secondes pour Pilote '.$PlayerID);
		echo "<p class='lead'>L'affichage de cette page est trop lent sur votre système. Veuillez vider le cache de votre navigateur internet et/ou utiliser une connexion plus stable.</p>";
	}*/
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>