<?php
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	$date=date('Y-m-d G:i');
	include_once('./jfv_include.inc.php');
	$con=dbconnecti();
	$reset1=mysqli_query($con,"UPDATE Joueur SET Chat_date='$date' WHERE ID='$AccountID'");
	if(!$Faction and $country)
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
	mysqli_close($con);
	//if($AccountID <8)$Faction=0;
	echo "<h1>Le Mess</h1><h2>Messagerie instantanée privée de Faction</h2><div class='row'><div class='col-md-4'><form action='index.php?view=campagne_chat' method='post'>
	<input type='hidden' name='Camp' value='".$Faction."'>
	<input type='text' name='Mes' size='50' class='form-control' placeholder='Ecrivez votre message ici'></div>
	<div class='col-md-2'><input type='Submit' value='Chat' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form></div>
	<div class='col-md-2'><form action='index.php?view=live_chatf' method='post'><input type='Submit' value='Actualiser' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form></div>
	<div class='col-md-2'><a href='mumble://mumble11.omgserv.com:11136/?version=1.2.10' title='Canal Mumble du jeu' class='btn btn-primary' target='_blank'><img src='images/mumble.png'> Mumble</a></div></div>
	<div style='overflow:auto; width:100%; height:400px;'><div class='row'><div class='col-md-2'>".$Connectes."</div><div class='col-md-10'><p>Bienvenue! Vous pouvez communiquer ici avec les officiers de votre faction.</p>".$Chat_faction."</div></div></div>
	<br><div class='i-flex led_green'></div> Connecté sur le chat
	<br><div class='i-flex led_orange'></div> Connecté sur le jeu
	<p><div class='btn btn-primary'><a href='index.php?view=live_chat'>Accéder au Chat Public</a></div></p>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>