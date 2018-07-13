<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country = $_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_access.php');
	include_once('./menu_messagerie.php');	
	//Send list
	if($Admin)
		$query="SELECT DISTINCT ID,Nom FROM Pilote WHERE ID<>'$PlayerID' ORDER BY Nom ASC";
	else
		$query="SELECT DISTINCT ID,Nom FROM Pilote WHERE Pays='$country' AND ID<>'$PlayerID' ORDER BY Nom ASC";
	$con=dbconnecti();
	$result=mysqli_query($con, $query);
	$result2=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Officier_em WHERE Pays='$country' AND ID<>'$OfficierEMID' ORDER BY Nom ASC");
	mysqli_close($con);
	if($result)
	{
		while($data = mysqli_fetch_array($result)) 
		{
			 $send_list.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
		}
		mysqli_free_result($result);
	}
	if($result2)
	{
		while($data2 = mysqli_fetch_array($result2)) 
		{
			 $send_list.="<option value='".$data2['ID']."_'>[EM] ".$data2['Nom']."</option>";
		}
		mysqli_free_result($result2);
	}
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
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>