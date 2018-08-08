<?php
include_once 'jfv_inc_sessions.php';
if (isset($_SESSION['AccountID'])) {
    include_once 'jfv_include.inc.php';
    if ($_SESSION['Distance'] == 0) {
        $Premium = GetData("Joueur", "ID", $_SESSION['AccountID'], "Premium");
        if ($Premium) {
            echo "<h1>Archives</h1><h2>Compte-rendu pour la date demandée</h2>
			<form action='index.php?view=stats' method='post'>
			<Input type='Radio' name='Mode' value='0' checked>- Aujourd'hui<br>
			<Input type='Radio' name='Mode' value='1'>- Hier<br>
			<Input type='Radio' name='Mode' value='2'>- Avant-hier<br>
			<Input type='Radio' name='Mode' value='3'>- Cette semaine<br>
			<input type='submit' class='btn btn-default' value='Voir le compte-rendu' onclick='this.disabled=true;this.form.submit();'></form>";
        } else
            echo "<h1>Archives</h1><h2>Information Premium</h2><img src='images/premium.png' title='Information Premium'>";
    }
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";