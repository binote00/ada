<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$Dest=Insec($_GET['dest']);
	if(GetData("Pilote","ID",$PlayerID,"Pays") ==GetData("Pilote","ID",$Dest,"Pays"))
	{
		$titre="La Poste des Armées";
		$mes="<form action='envoyer2.php' method='post'>
		<input type='hidden' name='exp' value='".$PlayerID."'>
		<input type='hidden' name='destinataire' value='".$Dest."'>
		<table class='table'>
			<thead><tr><th colspan='2'>Ecrire un message</th></tr></thead>
			<tr><th>Destinataire</th><td align='left'>".GetData("Pilote","ID",$Dest,"Nom")."</td></tr>
			<tr><th>Sujet</th><td align='left'><input type='text' name='Sujet' class='form-control' size='50' style='width:300px;'></td></tr>
			<tr><th>Message</th><td align='left'>
				<textarea name='msg' class='form-control' rows='5' cols='50'>
				</textarea>
			</td></tr>
			<tr><td><input type='Submit' value='Envoyer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
		</table></form>";
		include_once('./default_blank.php');
	}
	else
		echo "<h1>Vous ne pouvez pas contacter cette personne!</h1>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>