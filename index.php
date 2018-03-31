<?php
require_once('./inc/jfv_inc_sessions.php');
include_once('./inc/jfv_include.inc.php');
include_once('./inc/jfv_txt.inc.php');
//include_once('./inc/jfv_events.inc.php');
include_once('./inc/index.inc.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aube des Aigles est un jeu de gestion et de strategie multi-joueurs gratuit par navigateur ayant pour cadre la seconde guerre mondiale (1939-1945)">
    <meta name="author" content="JF Binote">
    <title>Aube des Aigles</title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link href="css/bs4/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>
<?php
include_once('./view/nav_v.php');
include_once('./view/'.$view.'.php');
?>
<div id="container">
    <div id="def-header"><?=$def_header?></div>
    <div id="def-body">
        <div id="def-nav"><?=$def_nav?></div>
        <h1><?=$def_title?></h1>
        <div class="row">
            <div class="col-12 col-lg-6" id="def-left"><?=$def_left?></div>
            <div class="col-12 col-lg-6" id="def-right"><?=$def_right?></div>
        </div>
        <div id="def-content"><?=$def_content?></div>
        <div id="def-footer"><?=$def_footer?></div>
    </div>
</div>
<div class="bg-inverse fixed-bottom">
    <div class="ww-date">
        <span class="ww-date-day"><?=$Dated?></span>
        <span class="ww-date-month"><?=$Datem?></span>
        <span class="ww-date-year"><?=$Datey?></span>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="js/bs4/bootstrap.min.js"></script>
</body>
</html>