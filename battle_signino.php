<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Faction=Insec($_POST['Camp']);
	$Battle=Insec($_POST['Battle']);
	$i=0;
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Battle ==1)
	{
		$Date_Campagne="1940-05-10";
		if($Faction ==1)
		{
			/*$Veh_excl="20,21,32,58,80,82,107,113,120,121,124,132,166,234,247,268,300,412";
			$Pays=1;*/
			$Veh="103,108,128,129,130,131,133,153,235,242,298";
			if($Premium)$Veh.="198,359";
		}
		else
		{
			/*$Veh_excl="677";
			$Pays="3,5";*/
			$Veh="691,693,694,695,696,698,700,615,618,675,676,678";
			if($Premium)$Veh.="674,692,697";
		}
	}
	elseif($Battle ==2)
	{
		$Date_Campagne="1940-05-12";
		if($Faction ==1)
		{
			$Veh="22,23,30,103,108,119,129,133,153,215,235,242,359";
			if($Premium)$Veh.="29,122,157";
		}
		else
		{
			$Veh="26,37,62,691,693,696,700,701,702,27,38,41,61,87,105,143,145,147,174";
			if($Premium)$Veh.="362,692,703,146,169";
		}
	}
	if($Faction ==1)
		$Pts_Bat=GetData("Battle_score","ID",$Battle,"Pts_Bat_Axe");
	else
		$Pts_Bat=GetData("Battle_score","ID",$Battle,"Pts_Bat_Allies");
	//$query="SELECT * FROM Cible WHERE Pays IN(".$Pays.") AND Date <='$Date_Campagne' AND Unit_ok=1 AND Reput <='$Pts_Bat' AND Categorie IN(2,3,5,6,8,9,15) AND mobile NOT IN (4,5) AND ID NOT IN (".$Veh_excl.") ORDER BY Reput ASC,HP ASC,Nom ASC";
	$query="SELECT * FROM Cible WHERE ID IN(".$Veh.") ORDER BY Reput ASC,HP ASC,Nom ASC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Arme_Inf="";
			$Arme_Art="";
			$Arme_AT="";
			$Arme_AA="";
			if($data['Categorie'] ==2 or $data['Categorie'] ==3 or $data['Categorie'] ==15 or $data['Type'] ==8)
			{
				$data['Arme_Art_mun']=floor($data['Arme_Art_mun']/3);
				$data['Arme_AT_mun']=floor($data['Arme_AT_mun']/3);
			}
			if($data['Arme_Inf'])
				$Arme_Inf=GetData("Armes","ID",$data['Arme_Inf'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_Inf'],"Calibre"))."mm)<br> tirs illimités";
			elseif($data['Arme_AA3'])
				$Arme_Inf=GetData("Armes","ID",$data['Arme_AA3'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA3'],"Calibre"))."mm)<br> tirs illimités";
			if($data['Arme_Art'])
				$Arme_Art=GetData("Armes","ID",$data['Arme_Art'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_Art'],"Calibre"))."mm)<br><span class='text-danger'>".$data['Arme_Art_mun']." tirs</span>";
			elseif($data['Arme_AA2'])
				$Arme_Art=GetData("Armes","ID",$data['Arme_AA2'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA2'],"Calibre"))."mm)<br> tirs illimités";
			if($data['Arme_AT'])
				$Arme_AT=GetData("Armes","ID",$data['Arme_AT'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AT'],"Calibre"))."mm)<br><span class='text-danger'>".$data['Arme_AT_mun']." tirs</span>";
			if($data['Arme_AA'])
				$Arme_AA=GetData("Armes","ID",$data['Arme_AA'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA'],"Calibre"))."mm)<br> tirs illimités";
			$HP=$data['HP'];
			$Autonomie=floor($data['Fuel']/10)+($data['Fiabilite']*5);
			if($data['Carbu_ID'] ==1)
				$Fuel="Diesel";
			elseif($data['Carbu_ID'] ==87)
				$Fuel="Essence";
			else
				$Fuel="Moral";
			if($data['Type'] ==99)
				$data['Nom'].=" (Aide à neutraliser les saboteurs)";
			$mest.="<tr><td><Input type='Radio' name='Avion' value='".$data['ID']."'><img src='/images/CT".$data['Reput'].".png' title='Points de Faction pour recruter cette unité'><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'></td>
			<td>".$Autonomie." (".$Fuel.")</td>
			<td>".$HP."</td>
			<td>".$data['Blindage_f']."</td>
			<td>".$Arme_Inf."</td>
			<td>".$Arme_Art."</td>
			<td>".$Arme_AT."</td>
			<td>".$Arme_AA."</td>
			<td>".$data['Portee']."</td>
			<td>".$data['Vitesse']."</td>
			<td>".$data['Detection']."</td>
			<td>".$data['Taille']."</td>
			</tr>";
		}
		mysqli_free_result($result);
		unset($data);
	}
	echo "<h1>Préparation de la bataille</h1><p class='lead'>Votre faction dispose encore de <b>".$Pts_Bat." Points</b> pour cette bataille</p><form action='index.php?view=battle_in' method='post'>
	<input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'>
	<h2>Choix de la Compagnie</h2>
	<div style='overflow:auto; height: 600px;'><table class='table table-striped'>
		<thead><tr>  
		<th width='10%'>Matériel</th>
		<th width='2%'>Autonomie <a href='#' class='popup'><img src='images/help.png'><span>Nombre de déplacements autorisés par bataille</span></a></th>
		<th width='2%'>Robustesse <a href='#' class='popup'><img src='images/help.png'><span>Capacité primordiale pour encaisser les dégâts avant la destruction</span></a></th>
		<th width='2%'>Blindage <a href='#' class='popup'><img src='images/help.png'><span>Capacité primordiale pour absorber les dégâts lors des tirs ennemis</span></a></th>
		<th width='5%'>Armement <a href='#' class='popup'><img src='images/help.png'><span>Armement contre les unités non blindées</span></a></th>  
		<th width='5%'>Soutien <a href='#' class='popup'><img src='images/help.png'><span>Armement utilisé lors des bombardements</span></a></th>  
		<th width='5%'>Anti-tank <a href='#' class='popup'><img src='images/help.png'><span>Armement contre les unités blindées</span></a></th>
		<th width='5%'>DCA <a href='#' class='popup'><img src='images/help.png'><span>Armement exclusivement défensif contre les attaques aériennes</span></a></th>
		<th width='2%'>Portée <a href='#' class='popup'><img src='images/help.png'><span>Caractéristique primordiale pour atteindre les cibles éloignées et pour se tenir à distance des ennemis</span></a></th>
		<th width='2%'>Vitesse <a href='#' class='popup'><img src='images/help.png'><span>Caractéristique primordiale pour se déplacer rapidement et attaquer les cibles éloignées</span></a></th>
		<th width='2%'>Détection <a href='#' class='popup'><img src='images/help.png'><span>Caractéristique primordiale pour la reconnaissance</span></a></th> 
		<th width='2%'>Taille <a href='#' class='popup'><img src='images/help.png'><span>Caractéristique primordiale pour ne pas être détecté ou pour réussir une embuscade. La valeur la plus faible est la meilleure.</span></a></th>
		</tr></thead>".$mest."</table></div>
	<p><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form> <a href='index.php?view=battles' class='btn btn-warning' title='Retour'>Annuler</a>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>