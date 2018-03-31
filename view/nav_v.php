<?php
/**
 * Created by PhpStorm.
 * User: JF
 * Date: 30-10-17
 * Time: 13:19
 */
if($Admin){
    $nav_item_1_txt = 'Admin';
}else{
    $nav_item_1_txt = 'Compte';
}
?>
<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item my-2">
                <a class="nav-link" href="#"><?=$nav_item_1_txt?></a>
            </li>
            <li class="nav-item dropdown my-2">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Personnage
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Profil</a>
                    <a class="dropdown-item" href="#">Messagerie</a>
                </div>
            </li>
            <li class="nav-item dropdown my-2">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Informations
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Alertes</a>
                    <a class="dropdown-item" href="#">Cartes</a>
                    <a class="dropdown-item" href="#">Dépôts</a>
                    <a class="dropdown-item" href="#">Effectifs</a>
                    <a class="dropdown-item" href="#">Production</a>
                    <a class="dropdown-item" href="#">Unités</a>
                    <a class="dropdown-item" href="#">Villes</a>
                    <a class="dropdown-item" href="#">Organigramme</a>
                </div>
            </li>
            <li class="nav-item dropdown my-2">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Actions
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Staff</a>
                    <a class="dropdown-item" href="#">Intelligence</a>
                    <a class="dropdown-item" href="#">Missions</a>
                    <a class="dropdown-item" href="#">Troupes</a>
                </div>
            </li>
            <li class="nav-item dropdown my-2">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Opérations
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Attaques aériennes</a>
                    <a class="dropdown-item" href="#">Attaques navales</a>
                    <a class="dropdown-item" href="#">Bombardements</a>
                    <a class="dropdown-item" href="#">Combats aériens</a>
                    <a class="dropdown-item" href="#">Combats terrestres</a>
                    <a class="dropdown-item" href="#">Combats navals</a>
                </div>
            </li>
            <li class="nav-item dropdown my-2">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Stats
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Score de Campagne</a>
                </div>
            </li>
            <li class="nav-item dropdown my-2">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Aide
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Règles</a>
                    <a class="dropdown-item" href="#">Encyclopédie</a>
                </div>
            </li>
            <li class="nav-item dropdown my-2">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Communauté
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Chat</a>
                    <a class="dropdown-item" href="#">Forum</a>
                    <a class="dropdown-item" href="#">Vocal</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<div class="header-wrap"></div>
<div id="navbar-logo"></div>