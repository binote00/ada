<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Faction=Insec($_POST['Camp']);
	$Mode=Insec($_POST['Type']);
	$Battle=Insec($_POST['Battle']);
	$i=0;
	if($Mode ==8)
		$Bomb=1;
	elseif($Mode ==6)
	{
		$Reco=true;
		$Mode=5;
	}
	elseif($Mode ==2)
		$Reco=true;
	if($Battle ==1)
	{
		$Date_Campagne="1940-05-10";
		$Zones=array(1,2,5);
		if($Faction ==1)
		{
			$Veh_excl="20,21,32,58,107,121,126,132,166,268,300,412";
			$Pays=1;
		}
		else
		{
			$Veh_excl="677";
			$Pays="3,5";
		}
	}
	if(is_array($Zones))
	{
		foreach($Zones as $Place)
		{
			$Place_txt.="<option value='".$Place."'>".GetPlace($Place)."</option>";
			$i++;
		}
		unset($Zones);
		unset($Veh);
	}
	$Lieu=GetCiblePVP($Battle);
	if($Reco)
		$page="ground_reco_pvp";
	else
		$page="ground_pldef_pvp";
	/*if($Mode >16 and $Mode <25)
		$query="SELECT * FROM Cible WHERE Pays IN(".$Pays.") AND Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Categorie='$Mode' ORDER BY Reput ASC, HP ASC, Nom ASC"; //Navires
	elseif($Mode ==2)
		$query="SELECT * FROM Cible WHERE Pays IN(".$Pays.") AND Date <='$Date_Campagne' AND Unit_ok=1 AND Categorie=2 AND Detection >10 AND mobile NOT IN (4,5) AND ID NOT IN (".$Veh_excl.") ORDER BY Reput ASC, HP ASC, Nom ASC";
	elseif($Mode ==3)
		$query="SELECT * FROM Cible WHERE Pays IN(".$Pays.") AND Date <='$Date_Campagne' AND Unit_ok=1 AND Categorie IN(2,3) AND mobile NOT IN (4,5) AND Arme_AT >0 AND ID NOT IN (".$Veh_excl.") ORDER BY Reput ASC, HP ASC, Nom ASC";
	else
		$query="SELECT * FROM Cible WHERE Pays IN(".$Pays.") AND Date <='$Date_Campagne' AND Unit_ok=1 AND Categorie='$Mode' AND mobile NOT IN (4,5) AND ID NOT IN (".$Veh_excl.") ORDER BY Reput ASC, HP ASC, Nom ASC";*/	
	
	if($Mode ==2)
		$query="SELECT c.*,r.ID as Reg,r.Placement FROM Regiment_PVP as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Pays IN(".$Pays.") AND r.Lieu_ID='$Lieu' AND c.Categorie=2 AND c.Detection >10 AND c.mobile NOT IN (4,5) ORDER BY r.Placement ASC";
	elseif($Mode ==3)
		$query="SELECT c.*,r.ID as Reg,r.Placement FROM Regiment_PVP as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Pays IN(".$Pays.") AND r.Lieu_ID='$Lieu' AND c.Categorie IN(2,3) AND c.mobile NOT IN (4,5) AND c.Arme_AT >0 ORDER BY r.Placement ASC";
	else
		$query="SELECT c.*,r.ID as Reg,r.Placement FROM Regiment_PVP as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Pays IN(".$Pays.") AND r.Lieu_ID='$Lieu' AND c.Categorie='$Mode' AND c.mobile NOT IN (4,5) ORDER BY r.Placement ASC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$mobile=$data['mobile'];
			$Fiabilite=$data['Fiabilite'];
			$Arme_Inf="";
			$Arme_Art="";
			$Arme_AT="";
			$Arme_AA="";
			if($data['Arme_Inf'])
				$Arme_Inf=GetData("Armes","ID",$data['Arme_Inf'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_Inf'],"Calibre"))."mm)<br>".$data['Arme_Inf_mun']."muns";
			elseif($data['Arme_AA3'])
				$Arme_Inf=GetData("Armes","ID",$data['Arme_AA3'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA3'],"Calibre"))."mm)<br>".$data['Arme_Inf_mun']."muns";
			if($data['Arme_Art'])
				$Arme_Art=GetData("Armes","ID",$data['Arme_Art'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_Art'],"Calibre"))."mm)<br>".$data['Arme_Art_mun']."muns";
			elseif($data['Arme_AA2'])
				$Arme_Art=GetData("Armes","ID",$data['Arme_AA2'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA2'],"Calibre"))."mm)<br>".$data['Arme_Art_mun']."muns";
			if($data['Arme_AT'])
				$Arme_AT=GetData("Armes","ID",$data['Arme_AT'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AT'],"Calibre"))."mm)<br>".$data['Arme_AT_mun']."muns";
			if($data['Arme_AA'])
				$Arme_AA=GetData("Armes","ID",$data['Arme_AA'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA'],"Calibre"))."mm)<br>".$data['Arme_AA_mun']."muns";
			$HP=$data['HP'];
			$Portee=$data['Portee'];
			$Blindage=$data['Blindage_f'];
			$Vitesse=$data['Vitesse'];
			$Taille=$data['Taille'];
			$Detection=$data['Detection'];
			$Autonomie=$data['Fuel'];
			$Charge=$data['Charge'];
			if(!$Charge)
				$Charge="Aucune";
			else
				$Charge.="kg/l";
			if($data['Carbu_ID'] ==1)
				$Fuel="Diesel";
			elseif($data['Carbu_ID'] ==87)
				$Fuel="Essence";
			else
				$Fuel="Moral";
			if($data['Type'] ==99)
				$data['Nom'].=" (Aide à neutraliser les saboteurs)";
			$Reput=$data['Reput'];
			$mest.="<tr><td>".GetPlace($data['Placement'])."</td>
			<td><Input type='Radio' name='Reg' value='".$data['Reg']."'><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'><br>".$Usine1_Nom."</td>
			<td>".$HP."</td>
			<td>".$Arme_Inf."</td>
			<td>".$Arme_Art."</td>
			<td>".$Arme_AT."</td>
			<td>".$Arme_AA."</td>
			<td>".$Portee."</td>
			<td>".$Blindage."</td>
			<td>".$Vitesse."</td>
			<td>".$Taille."</td>
			<td>".$Detection."</td>
			<td>".$Autonomie." (".$Fuel.")</td>
			<td>".$Fiabilite."</td>
			<td><img src='/images/CT".$Reput.".png' title='Points de Faction pour recruter cette unité'></td></tr>";
		}
		mysqli_free_result($result);
		unset($data);
	}
	include_once('./pvp_city_ground.php');
	echo "<form action='index.php?view=".$page."' method='post'><input type='hidden' name='Bomb' value='".$Bomb."'>
	<input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'>
	<h2>Choix de la Compagnie</h2>
	<div style='overflow:auto; height: 400px;'><table class='table table-striped'>
		<thead><tr>  
		<th width='5%'>Zone</th>
		<th width='10%'>Matériel</th>
		<th width='2%'>Robustesse</th>
		<th width='5%'>Armement</th>  
		<th width='5%'>Soutien</th>  
		<th width='5%'>Anti-tank</th>
		<th width='5%'>DCA</th>
		<th width='2%'>Portee</th>
		<th width='2%'>Blindage</th>
		<th width='2%'>Vitesse</th>
		<th width='2%'>Taille</th>
		<th width='2%'>Détection</th> 
		<th width='2%'>Autonomie</th> 
		<th width='2%'>Fiabilite</th>
		<th width='2%'>Coût</th></tr></thead>".$mest."</table></div>
	<p><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form> <a href='index.php?view=ground_menu_pvp' class='btn btn-warning' title='Retour'>Annuler</a>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>