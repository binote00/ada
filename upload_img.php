<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium)
	{
		$OfficierEMID=$_SESSION['Officier_em'];
		$PlayerID=$_SESSION['PlayerID'];
		if($OfficierEMID)
		{
			$dest="uploader_off";
			$name=GetData("Officier_em","ID",$OfficierEMID,"Nom");
		}
		elseif($PlayerID)
		{
			$dest="uploader";
			$name=GetData("Pilote","ID",$PlayerID,"Nom");
		}
		$mes="<h1>Image de profil personnalisée</h1><h2>".$name."</h2>
		<p>Pour être validée, l'image doit respecter les prérequis suivants :</p>
		<ul>
		<li>Format : jpg</li>
		<li>Hauteur : 300 pixels</li>
		<li>Largeur : 225 pixels</li>
		<li>Taille : 50Ko maximum</li>
		<li>Il est conseillé d'utiliser une image en noir et blanc.</li>
		<li>Evitez les accents, les caractères spéciaux et les espaces dans le nom du fichier.</li>
		<li>Evitez les photos de personnages historiques.</li>
		<li>Le jeu ne convertira pas votre image, attention de la convertir vous-même avant importation.</li>
		</ul>
		<form enctype='multipart/form-data' action='".$dest.".php' method='post'>
			<input type='hidden' name='MAX_FILE_SIZE' value='50000'>
			<input name='fichier' type='file'>
			<p><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p>
		</form>
		<div class='alert alert-warning'>Toute image jugée inappropriée sera retirée.</div>";
		include_once('./index.php');
	}
	else
		echo "Tsss!";
}
else
	echo "Tsss!";
?>