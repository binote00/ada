<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin >0)
{
	/*include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Cat=Insec($_POST['Cat']);*/
	$Battle=Insec($_POST['Battle']);
	$Reset=Insec($_POST['Reset']);
	include_once('./jfv_inc_pvp.php');
	$Cible=GetCiblePVP($Battle);
	if($Battle >0 and $Reset ==2)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_PVP SET Avion_Sandbox=0,Front_sandbox=0,Pays=0 WHERE Front_sandbox='$Battle'");
		$reset2=mysqli_query($con,"UPDATE Officier_PVP SET Division=0,Front=0,Pays=0,Note=0 WHERE Front='$Battle'");
		$reset3=mysqli_query($con,"UPDATE gnmh_aubedesaiglesnet2.Battle_score SET Allies=0,Axe=0,Pts_Bat_Axe=200,Pts_Bat_Allies=200,Allies_inscrits=0,Axe_inscrits=0 WHERE ID='$Battle'");
		$reset4=mysqli_query($con,"DELETE FROM Regiment_PVP WHERE Lieu_ID='$Cible' AND Front='$Battle'");
		$reset5=mysqli_query($con,"DELETE FROM DCA_Cbt_PVP WHERE Lieu='$Cible'");
		$reset6=mysqli_query($con,"DELETE FROM Air_Cbt_PVP WHERE Lieu='$Cible'");
		$reset7=mysqli_query($con,"DELETE FROM Ground_Cbt_PVP WHERE Lieu='$Cible'");
		$reset7=mysqli_query($con,"DELETE FROM gnmh_aubedesaiglesnet5.Events_Battle WHERE Lieu='$Cible'");
		mysqli_close($con);
	}
	elseif($Battle >0 and $Reset ==3)
	{
		$date=date('Y-m-d G:i');
		$con=dbconnecti();
		$reset4=mysqli_query($con,"UPDATE gnmh_aubedesaiglesnet2.Battle_score SET Bat_Date=DATE_ADD(NOW(),INTERVAL 15 MINUTE) WHERE ID='$Battle'");
		mysqli_close($con);
	}
	include_once('./default.php');
	/*
	if($Cat >0 and $Battle >0)
	{
		if($Battle ==1)
		{
			$Date_Campagne="1940-05-10";
			$Pays="1,3,5";
			$Nid=109;
		}
		$titre="Hangar";
		$titre_up="<thead><tr>  
				<th width='10%'>Matériel</th>     
				<th width='1%'>Détail</th>     
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
				<th width='2%'>Charge</th>
				<th width='2%'>Dispo</th>
				<th width='5%'>Créer</th></tr></thead>";		
		$mes="<h2>Matériel disponible</h2><div style='overflow:auto; height: 600px;'><table class='table table-striped'>".$titre_up;
		if($Cat >16 and $Cat <25)
			$query="SELECT * FROM Cible WHERE Pays IN(".$Pays.") AND Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Categorie='$Cat' ORDER BY Reput ASC, HP ASC, Nom ASC"; //Navires
		else
			$query="SELECT * FROM Cible WHERE Pays IN(".$Pays.") AND Date <='$Date_Campagne' AND Unit_ok=1 AND mobile NOT IN (4,5) AND Type NOT IN (90,92,96,98) AND Categorie='$Cat' ORDER BY Reput ASC, HP ASC, Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$ID=$data['ID'];
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
				$mes.="<tr><td><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'><br>".$Usine1_Nom."</td>
				<td><form><input type='button' value='Détail' class='btn btn-primary' onclick=\"window.open('cible.php?cible=".$data['ID']."','Fiche','width=820,height=840,scrollbars=1')\"></form></td>
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
				<td>".$Charge."</td>
				<td>".$Reste."</td>";
				$mes.="<td><form action='index.php?view=ground_ia_create_do_pvp' method='post'>
				<input type='hidden' name='Ve' value='".$data['ID']."'>
				<input type='hidden' name='Nid' value='".$Nid."'>
				<input type='Submit' value='".$Reput." CT' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
			}
			mysqli_free_result($result);
			unset($data);
		}
		$mes.="</table></div>";
		include_once('./default.php');
	}*/
}
else
	echo "Vous devez être connecté!";
?>