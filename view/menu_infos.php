<?php
$OfficierID = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
$PlayerID = $_SESSION['PlayerID'];
include_once '../inc/jfv_access.php';
?>
<div class='row'><div class='col-lg-6 col-md-12'>
<h1>Encyclopédie</h1>
<p><a class="btn btn-default" href="index.php?view=view/carte">Cartes</a>
<a class="btn btn-default" href="index.php?view=avions">Avions</a>
<a class="btn btn-default" href="index.php?view=infos/vehicules">Véhicules</a>
<a class="btn btn-default" href="index.php?view=infos/cibles">Infrastructures</a>
<a class="btn btn-default" href="index.php?view=infos/unites">Unités</a>
<a class="btn btn-default" href="index.php?view=infos/grades">Grades</a>
<a class="btn btn-default" href="index.php?view=infos/decos">Décorations</a>
<a class="btn btn-default" href="index.php?view=infos/pil_skills">Compétences pilotes</a>
<a class="btn btn-default" href="index.php?view=infos/reg_skills">Compétences unités</a>
<a class="btn btn-default" href="index.php?view=infos/reg_matos">Equipements unités</a></p>
</div><?if($_SESSION['AccountID']){?><div class='col-lg-6 col-md-12'>
<h1>Outils Premium</h1>
<?if($Premium >0){
if($PlayerID or $OfficierEMID){?>
<p><a class="btn btn-primary" href="index.php?view=premium/comparateur">Comparateur avion</a>
    <a class="btn btn-primary" href="index.php?view=premium/pr_airrange">Portée Aérodrome</a>
<?}if($PlayerID){?>
<a class="btn btn-primary" href="index.php?view=premium/pr_takeoff">Takeoff Test</a>
<a class="btn btn-primary" href="index.php?view=premium/pr_landing">Landing Test</a>
<a class="btn btn-primary" href="index.php?view=premium/pr_speed">Speed Test</a>
<a class="btn btn-primary" href="index.php?view=premium/pr_demenager">Déplacement unité aérienne</a>
<a class="btn btn-primary" href="index.php?view=premium/pr_air_kills0">Comparateur pilote</a>
<?}?>
</p>
<?if($OfficierID or $OfficierEMID){?>
<p><a class="btn btn-primary" href="index.php?view=premium/comparateur_v">Comparateur véhicule</a>
<a class="btn btn-primary" href="index.php?view=premium/pr_tir">Champ de tir</a>
<a class="btn btn-primary" href="index.php?view=premium/pr_ground_matos0">Recruteur</a>
<?//<a class="btn btn-primary" href="index.php?view=premium/pr_ground_move0">Déplacement unité terrestre</a>
}if($OfficierID){?>
<a class="btn btn-primary" href="index.php?view=premium/pr_ground_kills0">Comparateur officier</a>
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
<div class='alert alert-danger'>Ces outils sont accessibles aux utilisateurs <a href='index.php?view=premium/abo' class='lien'>Premium</a><br>Bien qu'ils ne donnent aucun avantage en jeu, ils permettent de réaliser tests et statistiques aidant à mieux en maîtriser les mécanismes.</div>
<?}?>
<?if($Admin >0){?>
<a class="btn btn-default" href="index.php?view=admin/admin_avions0">Admin avions</a>
<a class="btn btn-default" href="index.php?view=admin/admin_veh0">Admin vehicules</a>
<a class="btn btn-default" href="index.php?view=admin/admin_guns">Admin armes</a>
<a class="btn btn-default" href="index.php?view=admin/admin_dispo">Admin dispos</a>
<?}?>
</div><?}?>
</div>
