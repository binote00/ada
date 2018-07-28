<?php
session_unset();
$mes="<b>Votre mission a été réinitialisée.</b>
<br>- Ne pas quitter le site en cours de mission
<br>- Ne pas recharger les pages du site en cours de mission.
<br>- Ne pas ouvrir d'autres pages du site en cours de mission, sauf si vous le faites dans une nouvelle fenêtre ou un nouvel onglet.
<br>- Ne pas utiliser les boutons 'retour', 'précédent' ou 'reculer d'une page' de votre navigateur. Utilisez l'interface du site.
<br>- Il se peut qu'un problème de connection soit la cause de ce message.
";
$img="<table align='center'><tr><td align='center'><img src='images/tsss.jpg'></td></tr></table>";
$menu="<br><b>Veuillez utiliser le menu de navigation du site !</b>";
$skills='';
include_once './index.php';