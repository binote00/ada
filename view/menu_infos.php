<?php
$OfficierID = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
$PlayerID = $_SESSION['PlayerID'];
include_once './jfv_access.php';
?>
<div class='row'><div class='col-lg-6 col-md-12'>
<h1>Encyclopédie</h1>
<p><a class="btn btn-default" href="index.php?view=carte">Cartes</a>
<a class="btn btn-default" href="index.php?view=avions">Avions</a>
<a class="btn btn-default" href="index.php?view=vehicules">Véhicules</a>
<a class="btn btn-default" href="index.php?view=cibles">Infrastructures</a>
<a class="btn btn-default" href="index.php?view=unites">Unités</a>
<a class="btn btn-default" href="index.php?view=grades">Grades</a>
<a class="btn btn-default" href="index.php?view=decos">Décorations</a>
<a class="btn btn-default" href="index.php?view=pil_skills">Compétences pilotes</a>
<a class="btn btn-default" href="index.php?view=reg_skills">Compétences unités</a>
<a class="btn btn-default" href="index.php?view=reg_matos">Equipements unités</a></p>
</div><?if($_SESSION['AccountID']){?><div class='col-lg-6 col-md-12'>
<h1>Outils Premium</h1>
<?if($Premium >0){
if($PlayerID or $OfficierEMID){?>
<p><a class="btn btn-primary" href="index.php?view=comparateur">Comparateur avion</a>
    <a class="btn btn-primary" href="index.php?view=pr_airrange">Portée Aérodrome</a>
<?}if($PlayerID){?>
<a class="btn btn-primary" href="index.php?view=pr_takeoff">Takeoff Test</a>
<a class="btn btn-primary" href="index.php?view=pr_landing">Landing Test</a>
<a class="btn btn-primary" href="index.php?view=pr_speed">Speed Test</a>
<a class="btn btn-primary" href="index.php?view=pr_demenager">Déplacement unité aérienne</a>
<a class="btn btn-primary" href="index.php?view=pr_air_kills0">Comparateur pilote</a>
<?}?>
</p>
<?if($OfficierID or $OfficierEMID){?>
<p><a class="btn btn-primary" href="index.php?view=comparateur_v">Comparateur véhicule</a>
<a class="btn btn-primary" href="index.php?view=pr_tir">Champ de tir</a>
<a class="btn btn-primary" href="index.php?view=pr_ground_matos0">Recruteur</a>
<?//<a class="btn btn-primary" href="index.php?view=pr_ground_move0">Déplacement unité terrestre</a>
}if($OfficierID){?>
<a class="btn btn-primary" href="index.php?view=pr_ground_kills0">Comparateur officier</a>
<?}?>
</p>
<?}else{?>
<p><a class="btn btn-primary" href="">Comparateur avion</a>
<a class="btn btn-primary" href="">Takeoff Test</a>
<a class="btn btn-primary" href="">Landing Test</a>
<a class="btn btn-primary" href="">Speed Test</a>
<a class="btn btn-primary" href="">Déplacement unité aérienne</a>
<a class="btn btn-primary" href="">Comparateur pilote</a></p>
<p><a class="btn btn-primary" href="">Comparateur véhicule</a>
<a class="btn btn-primary" href="">Champ de tir</a>
<a class="btn btn-primary" href="">Recruteur</a>
<a class="btn btn-primary" href="">Déplacement unité terrestre</a>
<a class="btn btn-primary" href="">Comparateur officier</a></p>
<div class='alert alert-danger'>Ces outils sont accessibles aux utilisateurs <a href='index.php?view=abo' class='lien'>Premium</a><br>Bien qu'ils ne donnent aucun avantage en jeu, ils permettent de réaliser tests et statistiques aidant à mieux en maîtriser les mécanismes.</div>
<?}?>
<?if($Admin >0){?>
<a class="btn btn-default" href="index.php?view=admin/admin_avions0">Admin avions</a>
<a class="btn btn-default" href="index.php?view=aveh0">Admin vehicules</a>
<a class="btn btn-default" href="index.php?view=aguns">Admin armes</a>
<a class="btn btn-default" href="index.php?view=adispo">Admin dispos</a>
<?}?>
</div><?}?>
</div>
