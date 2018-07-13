<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	$ID = Insec($_POST['mes']);
	$con=dbconnecti(3);
	$ok=mysqli_query($con,"SELECT DISTINCT `Date`,Expediteur,Sujet,Message,Exp_em FROM Messages WHERE ID='$ID' LIMIT 1");
	mysqli_close($con);
	if($ok)
	{
		while ($data=mysqli_fetch_array($ok)) 
		{
			$Expediteur = $data['Expediteur'];
			$Message = nl2br($data['Message']);
			$Exp_em = $data['Exp_em'];
			if($Expediteur)
			{
				if($Exp_em)
					$Expediteur_nom=GetData("Officier_em","ID",$Expediteur,"Nom");
				else
					$Expediteur_nom=GetData("Pilote","ID",$Expediteur,"Nom");
			}
			else
				$Expediteur_nom="[Animation]";
			$Msg_Off.=$data['Date'].'<br><b>'.$Expediteur_nom.'</b> : '.$data['Sujet'].'<br><hr>'.$Message;
			//Update Lu
			$con=dbconnecti(3);
			$ok_up=mysqli_query($con,"UPDATE Messages SET Lu=1 WHERE ID='$ID'");			
			mysqli_close($con);
		}
	}
	include_once('./menu_messagerie.php');
	?>
<table class='table'>
	<thead><tr><th>Message</th></tr></thead>
	<tr><td align="left"><?echo $Msg_Off;?></td></tr>
</table>
<form action='index.php?view=archiver_msg' method='post'>
<input type='hidden' name='msg' value="<?echo $ID;?>">
<input type="Submit" value="Effacer" class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
</form>
<?if(!$Exp_em){?>
<form action='index.php?view=envoyer' method='post'>
<input type='hidden' name='destinataire' value="<?echo $Expediteur;?>">
<input type='hidden' name='exp' value="<?echo $PlayerID;?>">
<input type='hidden' name='em' value="0">
<table class='table'>
	<thead><tr><th colspan="2">Répondre</th></tr></thead>
	<tr><th>Destinataire</th><td align="left"><?echo $Expediteur_nom;?></td></tr>
	<tr><th>Sujet</th><td align="left"><input type="text" name="Sujet" value="RE: <?echo $data['Sujet'];?>" size="50"></td></tr>
	<tr><th>Message</th><td align="left"><textarea name="msg" rows="5" cols="50"></textarea></td></tr>
	<tr><td><input type="Submit" value="Envoyer" class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
</table></form>
<?
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>