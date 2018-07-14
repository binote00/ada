<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');

//Check Joueur Valide
if(isset($_SESSION['login']) AND isset($_SESSION['pwd']))
{
	$PlayerID = $_SESSION['PlayerID'];
	//GetData Player
	$date = date('d-m-Y G:i');
	$con = dbconnecti();
	$result = mysqli_query($con, "SELECT adresse,Nom FROM Joueur WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			mail('admin@aubedesaigles.net', 'Virement bancaire', $data['Nom'].' ('.$data['adresse'].')'.' a utilisé le formulaire de virement bancaire le '.$date);
		}
		mysqli_free_result($result);
		unset($data);
	}
	echo "<p>Une demande a été envoyée.<br>Vous recevrez les données bancaires par email sous peu.</p>";
}
else
{
	echo "<font color='#000000' size='4'>Vous devez être connecté pour accéder à cette page!</font>";
}
?>