<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin >0)
{
	echo "<h1>Outils</h1>
	<form action='index.php?view=ground_ia_create_pvp' method='post'>
	<input type='hidden' name='Reset' value='2'>
	<select name='Battle' class='form-control' style='width: 200px'><option value='1'>05/40 - Maastricht</option><option value='2'>05/40 - Hannut</option></select>
	<input type='Submit' value='R�initialiser la bataille' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
	</form>
	<form action='index.php?view=ground_ia_create_pvp' method='post'>
	<input type='hidden' name='Reset' value='3'>
	<select name='Battle' class='form-control' style='width: 200px'><option value='1'>05/40 - Maastricht</option><option value='2'>05/40 - Hannut</option></select>
	<input type='Submit' value='Mettre fin � la bataille en cours' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
	</form><hr>";
	/*$Battle=1;
	if($Battle ==1)
		$cat_em.="<option value='8'>Artillerie</option>";
	if($Battle ==1)
		$cat_em.="<option value='2'>Blind�s l�gers</option>";
	if($Battle ==1)
		$cat_em.="<option value='3'>Blind�s</option>";
	if($Battle ==1)
		$cat_em.="<option value='9'>Canon AT</option>";
	if($Battle ==1)
		$cat_em.="<option value='15'>Canon DCA</option>";
	if($Battle ==1)
		$cat_em.="<option value='5'>Infanterie</option>";
	if($Battle ==1)
		$cat_em.="<option value='6'>Mitrailleuse</option>";
		$cat_em.="<option value='22'>Corvettes</option>";
		$cat_em.="<option value='23'>Croiseurs l�gers</option>";
		$cat_em.="<option value='24'>Croiseurs lourds</option>";
		$cat_em.="<option value='20'>Cuirass�s</option>";
		$cat_em.="<option value='21'>Porte-avions</option>";
		$cat_em.="<option value='17'>Sous-marins</option>";
	echo "<h1>Cr�ation de Compagnie Action</h1>
	<form action='index.php?view=ground_ia_create_pvp' method='post'>
	<select name='Battle' class='form-control' style='width: 200px'><option value='1'>05/40 - Maastricht</option></select>
	<select name='Cat' class='form-control' style='width: 200px'>".$cat_em."</select>
	<input type='Submit' value='Cr�er' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form><hr>";*/
}