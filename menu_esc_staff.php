<h1><?echo $Unite_Nom;?></h1>
<h2>Gestion de l'unité</h2>
<a class='btn btn-default' title="Gestion du parc avion" href='index.php?view=esc_gestioncdt0_a'>Avions</a>
<a class='btn btn-default' title="Gestion de l'armement des avions" href='index.php?view=esc_gestioncdt0_r'>Armement</a>
<a class='btn btn-default' title="Gestion de la base" href='index.php?view=esc_gestioncdt0_b'>Base</a>
<!--<a class='btn btn-default' title="Gestion des pilotes" href='index.php?view=esc_gestioncdt0_p'>Pilotes</a>-->
<?if($PlayerID == $Commandant){?>
<a class='btn btn-warning' title="Privilèges du Commandant" href='index.php?view=esc_gestioncdt0_c'>Urgence</a><?}?>
