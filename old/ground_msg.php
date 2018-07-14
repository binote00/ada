<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Officier = $_SESSION['Officier'];
if($Officier >0)
{
	$country = $_SESSION['country'];
	$Dest = Insec($_GET['dest']);
	if(GetData("Officier","ID",$Officier,"Pays") == GetData("Officier","ID",$Dest,"Pays"))
	{
?>
<form action='index.php?view=ground_envoyer2' method='post'>
<input type='hidden' name='exp' value="<? echo $Officier; ?>">
<input type='hidden' name='destinataire' value="<? echo $Dest; ?>">
<table class='table'>
	<thead><tr><th colspan="2">La Poste des Armées : Ecrire un message</th></tr></thead>
	<tr><td>Destinataire : </td><td align="left"><? echo GetData("Officier","ID",$Dest,"Nom");?></td></tr>
	<tr><td>Sujet : </td><td align="left"><input type="text" name="Sujet" size="50" class='form-control'></td></tr>
	<tr><td>Message</td><td align="left">
		<textarea name="msg" rows="5" cols="50" class='form-control'>
		</textarea>
	</td></tr>
	<tr><td><input type="Submit" value="Envoyer" class='btn btn-default' onclick="this.disabled=true;this.form.submit();"></td></tr>
</table>
</form>
<?
	}
	else
		echo "<h1>Vous ne pouvez pas contacter cette personne!</span>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
echo "</body></html>";
?>