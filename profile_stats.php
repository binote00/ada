<?
require_once('./jfv_inc_sessions.php');
$ID = $_SESSION['PlayerID'];	
$Pays = $_SESSION['country'];
?>
<h1>Statistiques du pilote</h1>
<form>
	<input type="button" value="Expérience" title="Votre expérience sur les différents modèles d'avions" class="btn btn-default" onclick="location.href='index.php?view=user_exp'">
	<input type="button" value="Pertes" title="Vos pertes sur les différents modèles d'avions" class="btn btn-default" onclick="location.href='index.php?view=user_pertes'">
</form>
<h2>Missions</h2>
<form>
	<input type="button" value="Victoires" title="Victoires aériennes" class="btn btn-default" onclick="window.open('victoires.php?pilote=<? echo $ID; ?>','Victoires','width=1024,height=800,scrollbars=1')">
	<input type="button" value="Défaites" title="Abattu en combat aérien" class="btn btn-default" onclick="window.open('loose.php?pilote=<? echo $ID; ?>','Défaites','width=1024,height=800,scrollbars=1')">
	<input type="button" value="Attaques au sol" title="Cibles au sol détruites"  class="btn btn-default" onclick="window.open('attaques.php?pilote=<? echo $ID; ?>','Attaques','width=680,height=800,scrollbars=1')">
	<input type="button" value="Bombardements" title="Cibles bombardées"  class="btn btn-default" onclick="window.open('bombs.php?pilote=<? echo $ID; ?>','Bombardements','width=680,height=800,scrollbars=1')">
	<input type="button" value="Reconnaissance" title="Missions de reconnaissance réussies"  class="btn btn-default" onclick="window.open('recces.php?pilote=<? echo $ID; ?>','Recos','width=680,height=800,scrollbars=1')">
	<input type="button" value="Abattu par la D.C.A" title="Abattu par la D.C.A ennemie" class="btn btn-default" onclick="window.open('dca.php?pilote=<? echo $ID; ?>','DCA','width=680,height=800,scrollbars=1')">
</form>
<h2>Cartes des victoires</h2>
<form>
	<?if($Pays == 20){?>
	<input type="button" value="Carélie" title="Carte des victoires en Carélie" class="btn btn-default" onclick="window.open('cartepos_finland.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Nord-Est" title="Carte des victoires Europe Nord-Est" class="btn btn-default" onclick="window.open('cartepos_nord_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<?}elseif($Pays == 9){?>
	<input type="button" value="Pacifique" title="Carte des victoires dans le Pacifique" class="btn btn-default" onclick="window.open('cartepos_pacifique.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<?}elseif($Pays == 4){?>
	<input type="button" value="Europe Ouest" title="Carte des victoires Europe Ouest" class="btn btn-default" onclick="window.open('cartepos.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Mediterranée Ouest" title="Carte des victoires Mediterranée Ouest" class="btn btn-default" onclick="window.open('cartepos_med.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Mediterranée Est" title="Carte des victoires Mediterranée Est" class="btn btn-default" onclick="window.open('cartepos_med_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<?}elseif($Pays == 6){?>
	<input type="button" value="Europe Ouest" title="Carte des victoires Europe Ouest" class="btn btn-default" onclick="window.open('cartepos.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Sud-Est" title="Carte des victoires Europe Sud-Est" class="btn btn-default" onclick="window.open('cartepos_sud_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Mediterranée Ouest" title="Carte des victoires Mediterranée Ouest" class="btn btn-default" onclick="window.open('cartepos_med.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Mediterranée Est" title="Carte des victoires Mediterranée Est" class="btn btn-default" onclick="window.open('cartepos_med_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<?}elseif($Pays == 7){?>
	<input type="button" value="Europe Nord" title="Carte des victoires Europe Nord" class="btn btn-default" onclick="window.open('cartepos_nord_ouest.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Ouest" title="Carte des victoires Europe Ouest" class="btn btn-default" onclick="window.open('cartepos.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Pacifique" title="Carte des victoires dans le Pacifique" class="btn btn-default" onclick="window.open('cartepos_pacifique.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<?}elseif($Pays == 8){?>
	<input type="button" value="Carélie" title="Carte des victoires en Carélie" class="btn btn-default" onclick="window.open('cartepos_finland.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Nord-Est" title="Carte des victoires Europe Nord-Est" class="btn btn-default" onclick="window.open('cartepos_nord_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Sud-Est" title="Carte des victoires Europe Sud-Est" class="btn btn-default" onclick="window.open('cartepos_sud_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<?}elseif($Pays == 2){?>
	<input type="button" value="Europe Nord" title="Carte des victoires Europe Nord" class="btn btn-default" onclick="window.open('cartepos_nord_ouest.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Ouest" title="Carte des victoires Europe Ouest" class="btn btn-default" onclick="window.open('cartepos.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Mediterranée Ouest" title="Carte des victoires Mediterranée Ouest" class="btn btn-default" onclick="window.open('cartepos_med.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Mediterranée Est" title="Carte des victoires Mediterranée Est" class="btn btn-default" onclick="window.open('cartepos_med_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Pacifique" title="Carte des victoires dans le Pacifique" class="btn btn-default" onclick="window.open('cartepos_pacifique.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<?}else{?>
	<input type="button" value="Europe Nord" title="Carte des victoires Europe Nord" class="btn btn-default" onclick="window.open('cartepos_nord_ouest.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Ouest" title="Carte des victoires Europe Ouest" class="btn btn-default" onclick="window.open('cartepos.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Nord-Est" title="Carte des victoires Europe Nord-Est" class="btn btn-default" onclick="window.open('cartepos_nord_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Europe Sud-Est" title="Carte des victoires Europe Sud-Est" class="btn btn-default" onclick="window.open('cartepos_sud_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Mediterranée Ouest" title="Carte des victoires Mediterranée Ouest" class="btn btn-default" onclick="window.open('cartepos_med.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<input type="button" value="Mediterranée Est" title="Carte des victoires Mediterranée Est" class="btn btn-default" onclick="window.open('cartepos_med_est.php?pilote=<?echo $ID;?>','Carte','width=1280,height=720,scrollbars=1')">
	<?}?>
</form>