<?php
require_once('./jfv_inc_sessions.php');
$Pilote_pvp=$_SESSION['Pilote_pvp'];
if($Pilote_pvp >0)
{
	include_once('./jfv_inc_pvp.php');
	$con=dbconnecti();
	$reset=mysqli_query($con,"UPDATE Pilote_PVP SET enis=0,avion_eni=0,S_Unite_Intercept=0,Escorte=0,Couverture=0,Points=Points-1,Abattu=Abattu+1,Avion_Sandbox=0,S_HP=0 WHERE ID='$Pilote_pvp'");
	mysqli_close($con);
	AddAirCbtPVP($Pilote_eni,$avion_eni,$Pilote_pvp,$avion,$Cible,$alt,$Dist_shoot);
	$_SESSION['Distance']=0;
	$_SESSION['Done']=false;
	RetireCandidatPVP($Pilote_pvp,"end_mission");
	if(!$img)$img=Afficher_Image('images/avions/crash'.$avion_img.'.jpg','images/avions/crash.jpg','crash');
	$intro.="<p>La chance vous sourit! Vous parvenez à rejoindre les lignes amies sans trop de difficultés.</p>";
	$mes.="<p>Vous ne parvenez pas à maintenir votre avion en vol.<br>Vous êtes obligé de l'abandonner en sautant en parachute!</p><p><b>FIN DE MISSION</b></p>";
	$menu.="<form action='index.php?view=profil_pvp' method='post'>
		<input type='submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
}