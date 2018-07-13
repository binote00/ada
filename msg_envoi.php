<?
require_once('./jfv_inc_sessions.php');
$Officier = $_SESSION['PlayerID'];
if($Officier > 0)
{
	include_once('./jfv_include.inc.php');
	$ID = Insec($_POST['mes']);
	$con=dbconnecti(3);
	$ok=mysqli_query($con,"SELECT DISTINCT `Date`,Reception,Sujet,Message,Rec_em FROM Messages WHERE ID='$ID' LIMIT 1");
	mysqli_close($con);
	if($ok)
	{
		while ($data = mysqli_fetch_array($ok)) 
		{
			$Reception = $data['Reception'];
			$Message = nl2br($data['Message']);
			if($Reception)
			{
				if($data['Rec_em'])
					$Reception_nom=GetData("Officier_em","ID",$Reception,"Nom");
				else
					$Reception_nom=GetData("Pilote","ID",$Reception,"Nom");
			}
			else
				$Reception_nom="[Animation]";
			$Msg_Off.=$data['Date'].'<br> Adressé à '.$Reception_nom.' : '.$data['Sujet'].'<br><hr>'.$Message;
		}
	}
	include_once('./menu_messagerie.php');
	?>
<table class='table'>
	<thead><tr><th>La Poste des Armées : Message</th></tr></thead>
	<tr><td align="left"><?echo $Msg_Off;?></td></tr>
</table>
	<?
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>