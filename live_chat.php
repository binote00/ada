<?php
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	$date=date('Y-m-d G:i');
	include_once('./jfv_include.inc.php');
	//$Chat_open=Insec($_GET['txt']);
	$con=dbconnecti();
	$reset1=mysqli_query($con,"UPDATE Joueur SET Chat_date='$date' WHERE ID='$AccountID'");
	mysqli_close($con);
	if(!$Admin)$Chat_open_b="";
	echo "<h1>La Cantine</h1><h2>Messagerie instantanée publique</h2>
	<div class='row'>
		<div class='col-md-4 col-sm-6'>
			<form action='index.php?view=campagne_chat' method='post'><input type='hidden' name='Camp' value='0'>
			<input type='text' name='Mes' size='50' placeholder='Ecrivez votre message ici' class='form-control' onmouseup='chatbtn.disabled=false;' required>
		</div>
		<div class='col-md-2 col-sm-6'><input type='Submit' value='Chat' class='btn btn-primary' id='chatbtn' onclick='this.disabled=true;this.form.submit();'></form></div>
		<div class='col-md-2 col-sm-6'><form action='index.php?view=live_chat' method='post'><input type='Submit' value='Actualiser' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
		<div class='col-md-2 col-sm-6'><a href='mumble://mumble11.omgserv.com:11136/' title='Canal Mumble du jeu (mot de passe : bf109)' class='btn btn-primary' target='_blank'><img src='images/mumble.png'> Mumble</a></div>
	</div>
	<div style='overflow:auto; width:100%; height:400px;'><div class='row'><div class='col-md-2'>".$Connectes."</div><div id='messages' class='col-md-10'><p>Bienvenue sur le Chat de l'Aube des Aigles !<div class='alert alert-warning'>Si vous ne voyez pas beaucoup de messages ici c'est simplement parce que la majorité des joueurs préfèrent utiliser le serveur <a class='lien' href='https://www.mumble.com/'>mumble</a> du jeu<br>Le mot de passe mumble est <b>bf109</b></div>".$Chat_open.$Chat_open_b."</div></div></div>
	<br><img src='images/led_green.png'> Connecté sur le chat
	<br><img src='images/led_orange.png'> Connecté sur le jeu
	<p><div class='btn btn-primary'><a href='index.php?view=live_chatf'>Accéder au Chat Privé de Faction (Le Mess)</a></div></p>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>
