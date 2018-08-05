<?php
/**
 * User: JF
 * Date: 04-08-18
 * Time: 13:09
 */

if(!$Ordres_Mer){
    $menu_cat_list="<p><a class='btn btn-default' href='index.php?view=ground_em_ia_list'>Tout</a>";
    if($Veh->Type ==8)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_8'>Artillerie</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_8'>Artillerie</a>";
    if($Veh->Type ==9)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_9'>Anti-Tank</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_9'>Anti-Tank</a>";
    if($Veh->Type ==2)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_2'>Blindé Léger</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_2'>Blindé Léger</a>";
    if($Veh->Type ==3)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_3'>Blindé</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_3'>Blindé</a>";
    if($Veh->Type ==15)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_15'>DCA</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_15'>DCA</a>";
    if($Veh->Type ==5)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_5'>Infanterie</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_5'>Infanterie</a>";
    if($Veh->Type ==6)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_6'>Mitrailleuse</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_6'>Mitrailleuse</a>";
    if($Veh->Type ==13)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_13'>Train</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_13'>Train</a>";
    if($Veh->Type ==1)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_1'>Camion</a>";
    else
        $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_1'>Camion</a>";
}
if($Veh->Type ==21)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_21'>Porte-avions</a>";
elseif($country ==2 or $country ==7 or $country ==9)
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_21'>Porte-avions</a>";
if($Veh->Type ==20)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_20'>Cuirassé</a>";
else
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_20'>Cuirassé</a>";
if($Veh->Type ==24)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_24'>Croiseur Ld</a>";
else
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_24'>Croiseur Ld</a>";
if($Veh->Type ==23)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_23'>Croiseur Lg</a>";
else
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_23'>Croiseur Lg</a>";
if($Veh->Type ==22)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_22'>Corvette</a>";
else
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_22'>Corvette</a>";
if($Veh->Type ==17)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_17'>Sous-marin</a>";
else
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_17'>Sous-marin</a>";
if($Veh->Type ==100)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_100'>Soutien</a>";
else
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_100'>Soutien</a>";
if($Veh->Type ==10)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_10'>Cargo</a>";
else
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_10'>Cargo</a>";
/*if($Veh->Type ==4)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_4'>Command</a>";
else
    $menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_4'>Command</a>";*/
if($Veh->Type ==89)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_89'>Réserve</a>";
elseif(!$Ordres_Mer)
    $menu_cat_list.="<a class='btn btn-info' href='index.php?view=ground_em_ia_list_89'>Réserve</a>";
if($Veh->Type ==95)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
else
    $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
if($Veh->Type ==91)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_91'>Mission</a>";
elseif($Admin)
    $menu_cat_list.="<a class='btn btn-success' href='index.php?view=ground_em_ia_list_91'>Mission</a>";
if(!$Ordres_Mer){
    if($Veh->Type ==92)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_92'>Danger</a>";
    elseif($GHQ or $Premium)
        $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_92'>Danger</a>";
    if($Veh->Type ==93)
        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_93'>Repli</a>";
    elseif($GHQ or $Premium)
        $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_93'>Repli</a>";
}
if($Veh->Type ==96)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_96'>Camo</a>";
elseif($GHQ or $Premium)
    $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_96'>Camo</a>";
if($Veh->Type ==88)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_88'>Attente</a>";
elseif($Admin)
    $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_88'>Attente</a>";
if($Veh->Type ==94)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_94'>Réparer</a>";
else
    $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_94'>Réparer</a>";
if($Veh->Type ==98)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_98'>Demob</a>";
elseif(!$Ordres_Mer)
    $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_98'>Demob</a>";
if($Veh->Type ==90)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_90'>GHQ</a>";
elseif($GHQ)
    $menu_cat_list.="<a class='btn btn-danger' href='index.php?view=ground_em_ia_list_90'>GHQ</a>";
if($Veh->Type ==97)
    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_97'>Move</a>";
elseif($GHQ)
    $menu_cat_list.="<a class='btn btn-danger' href='index.php?view=ground_em_ia_list_97'>Move</a>";
$menu_cat_list.='</p>';