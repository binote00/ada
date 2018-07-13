<?php
require_once('./jfv_inc_const.php');

function GetSituation($Enis,$avion_eni,$Pays_eni,$Leader=0,$Ailier=0,$avion=0,$pvp=0)
{	
	if($pvp)
	{
		$PlayerID=$_SESSION['Pilote_pvp'];
		$Db_Pilote="Pilote_PVP";
		$country=GetData("Avion","ID",$avion,"Pays");
	}
	else
	{
		$PlayerID=$_SESSION['PlayerID'];
		$Db_Pilote="Pilote";
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Pays,Unit,Front,Avancement,S_Formation,S_Escorte,S_Escorte_nbr,S_Escorteb_nbr,S_Escorteb,S_Leader,S_Mission,Ailier,S_Ailier,Sandbox,S_Avion_db,S_Cible,Avion_Sandbox FROM $Db_Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unite=$data['Unit'];
			$Pays=$data['Pays'];
			$Front=$data['Front'];
			$Front_sandbox=$data['Front_sandbox'];
			$Avancement=$data['Avancement'];
			$Cible=$data['S_Cible'];
			$Formation=$data['S_Formation'];
			$Escorte=$data['S_Escorte'];
			$Escorteb=$data['S_Escorteb'];
			$Escorteb_nbr=$data['S_Escorteb_nbr'];
			$Escorte_nbr=$data['S_Escorte_nbr'];
			$Leader=$data['S_Leader'];
			$Mission_Type=$data['S_Mission'];
			$Ailier=$data['Ailier'];
			$S_Ailier=$data['S_Ailier'];
			$Avion_db=$data['S_Avion_db'];
			$Avion_Sandbox=$data['Avion_Sandbox'];
			$Sandbox=$data['Sandbox'];
		}
		mysqli_free_result($result);
	}
	if($Sandbox)
	{
		if($Avion_db =="Avions_Sandbox" and $Avion_Sandbox)
			$avion=GetData("Avions_Sandbox","ID",$Avion_Sandbox,"ID_ref");
		if($S_Ailier and $avion >0)
			$ailier_txt='<td>'.GetAvionIcon($avion,$Pays,0,$Unite,$Front_sandbox,'Votre Ailier').'</td>';
		$ailier_titre="<th>Votre Ailier</th>";
	}
	elseif($Leader or $Ailier)
	{
		if($Ailier)
			$avion_lead=GetData("Pilote_IA","ID",$Ailier,"Avion");
		if(!$avion_lead)
			$avion_lead=GetData("Unit","ID",$Unite,"Avion1");
		$ailier_txt='<td>'.GetAvionIcon($avion_lead,$Pays,0,$Unite,$Front,'Votre Ailier').'</td>';
		$ailier_titre="<th>Votre Ailier</th>";
	}	
	if($Formation >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Avion FROM Pilote_IA WHERE Cible='$Cible' AND Unit='$Unite' AND Actif=1");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Formation_avion=$data['Avion'];
			}
			mysqli_free_result($result);
		}
		if($Formation_avion)
			$formation_txt='<td>'.$Formation.GetAvionIcon($Formation_avion,$Pays,0,$Unite,$Front,'Votre Formation').'</td>';
		$formation_titre="<th>Votre Formation</th>";
	}
	if($Escorte_nbr >0 and !$pvp)
	{
		$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$result=mysqli_query($con,"SELECT j.Avion,j.Unit,j.Pays FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction='$Faction' AND j.Escorte='$Cible' AND j.Actif=1 GROUP BY j.Avion ORDER BY COUNT(*) DESC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Escorte_avion=$data['Avion'];
				$Escorte=$data['Unit'];
				$Escorte_Pays=$data['Pays'];
			}
			mysqli_free_result($result);
		}
		if(!$Escorte_avion and $Escorte)
		{
			$Escorte_avion=GetData("Unit","ID",$Escorte,"Avion1");
			$Escorte_Pays=$Pays;
		}
		$escorte_txt='<td>'.$Escorte_nbr.GetAvionIcon($Escorte_avion,$Escorte_Pays,0,$Escorte,$Front,'Votre Escorte').'</td>';
		$escorte_titre="<th>Votre Escorte</th>";
	}
	if($Escorteb_nbr >0 and $Escorteb >0 and $Mission_Type !=14)
	{
		$escorteb_txt='<td>'.$Escorteb_nbr.GetAvionIcon($Escorteb,$Pays,0,0,$Front,'Amis').'</td>';
		$escorteb_titre="<th>Vos escortés</th>";
	}	
	if($Enis >0 and $avion_eni >0)
	{
		$Enis_txt='<td>'.$Enis.GetAvionIcon($avion_eni,$Pays_eni,0,0,$Front).'</td>';
		$enis_titre="<th>Non identifié</th>";
	}	
	$situation_avions="<h3>Avions détectés</h3><table class='table'><thead><tr>".$ailier_titre.$formation_titre.$escorte_titre.$escorteb_titre.$enis_titre."</tr></thead>
	<tr>".$ailier_txt.$formation_txt.$escorte_txt.$escorteb_txt.$Enis_txt."</tr></table>";
	return $situation_avions;
}

function GetToolbar($chemin, $PlayerID, $avion, $HP, $Mun1, $Mun2, $essence, $meteo, $alt, $Puissance, $Longitude, $Latitude, $Cible, $Mission_Type, $gaz=100, $malus_incident=1, $avion_db="Avion", $flaps=0 ,$pvp=false)
{
	if($pvp)
		$DB_Pil="Pilote_PVP";
	else
		$DB_Pil="Pilote";
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,Unit,Pays,Front,Reputation,Avancement,Stress_Moteur,Stress_Commandes,Stress_Train,Stress_Arme1,Stress_Arme2,
	S_Engine_Nbr,S_Avion_Bombe_Nbr,S_Avion_Bombe,S_Avion_Mun,S_Avion_db,S_Tourelle_Mun,S_Blindage FROM ".$DB_Pil." WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : toolbar-pil');
	$result2=mysqli_query($con,"SELECT Nom,Longitude,Latitude FROM Lieu WHERE ID='$Cible'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : toolbar-cible');
	$result3=mysqli_query($con,"SELECT Nom,Robustesse,ArmePrincipale,ArmeSecondaire,Engine_Nbr,Blindage,Masse,Munitions1,Munitions2 FROM $avion_db WHERE ID='$avion'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : toolbar-avion');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Pilote=$data['Nom'];
			$Unite=$data['Unit'];
			$Pays=$data['Pays'];
			$Front=$data['Front'];
			$Reput=$data['Reputation'];
			$Grade=$data['Avancement'];
			$Stress_Moteur=$data['Stress_Moteur'];
			$Stress_Commandes=$data['Stress_Commandes'];
			$Stress_Train=$data['Stress_Train'];
			$Stress_Arme1=$data['Stress_Arme1'];
			$Stress_Arme2=$data['Stress_Arme2'];
			$Engine_Nbr=$data['S_Engine_Nbr'];
			$Avion_Bombe_nbr=$data['S_Avion_Bombe_Nbr'];
			$Avion_Bombe=$data['S_Avion_Bombe'];
			$Avion_Mun=$data['S_Avion_Mun'];
			$avion_db=$data['S_Avion_db'];
			$Tourelle_Mun=$data['S_Tourelle_Mun'];
			$S_Blindage=$data['S_Blindage'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($result2)
	{
		while($datal=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$NomCible=$datal['Nom'];
			$Cible_long=$datal['Longitude'];
			$Cible_lat=$datal['Latitude'];
		}
		mysqli_free_result($result2);
	}
	if($result3)
	{
		while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
		{
			$avion_nom=$data['Nom'];
			$HPmax=$data['Robustesse'];
			$Arme1Avion=$data['ArmePrincipale'];
			$Arme2Avion=$data['ArmeSecondaire'];
			$Engine_Nbr_Ori=$data['Engine_Nbr'];
			$Blindage=$data['Blindage'];
			$Masse=$data['Masse'];
			$ID_ref=$data['ID_ref'];
			$Mun1_Type=$data['Munitions1'];
			$Mun2_Type=$data['Munitions2'];
		}
		mysqli_free_result($result3);
		unset($data);
	}
	if(!$Blindage)
	{
		$Blindage=$S_Blindage;
		if(!$Blindage)
			$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
	}
	if($Arme1Avion)
		$Arme1_nom=GetData("Armes","ID",$Arme1Avion,"Nom");
	if($Arme2Avion)
		$Arme2_nom=GetData("Armes","ID",$Arme2Avion,"Nom");	
	if($chemin <0)
		$chemin=0;
	if($essence <100)
		$essence_icon='essence100';
	elseif($essence <250)
		$essence_icon='essence250';
	elseif($essence <500)
		$essence_icon='essence500';
	elseif($essence <750)
		$essence_icon='essence750';
	else
		$essence_icon='essence1000';
	if($avion_db =="Avion")
	{
		$Mun1_Type=$Avion_Mun;
		$Mun2_Type=$Avion_Mun;
		$Avion_icon=GetAvionIcon($Avion,$Pays,$PlayerID,$Unite,$Front);
	}
	else
	{
		$ID_ref=GetData($avion_db,"ID",$avion,"ID_ref");
		$Avion_icon=GetAvionIcon($ID_ref,$Pays,$PlayerID,$Unite,$Front);
		$HPmax=GetData("Avion","ID",$ID_ref,"Robustesse");
	}	
	if($Tourelle_Mun)
	{
		$Arme2_nom='Mitrailleurs';
		$Mun2=$Tourelle_Mun;
	}
	$Mun1_icon=round($Mun1/100);
	$Mun2_icon=round($Mun2/100);
	if($Mun1_icon >10)$Mun1_icon=10;
	if($Mun2_icon >10)$Mun2_icon=10;
	if($Mun1_Type ==1)
		$Mun1='<font color=\'purple\'>'.$Mun1.' (AP)</font>';
	elseif($Mun1_Type ==2)
		$Mun1='<font color=\'blue\'>'.$Mun1.' (HE)</font>';
	elseif($Mun1_Type ==3)
		$Mun1='<font color=\'red\'>'.$Mun1.' (I)</font>';
	elseif($Mun1_Type ==4)
		$Mun1='<font color=\'green\'>'.$Mun1.' (APHE)</font>';
	elseif($Mun1_Type ==5)
		$Mun1='<font color=\'orange\'>'.$Mun1.' (API)</font>';
	else
		$Mun1='<font color=\'black\'>'.$Mun1.'</font>';
	if($Mun2_Type ==1)
		$Mun2='<font color=\'purple\'>'.$Mun2.' (AP)</font>';
	elseif($Mun2_Type ==2)
		$Mun2='<font color=\'blue\'>'.$Mun2.' (HE)</font>';
	elseif($Mun2_Type ==3)
		$Mun2='<font color=\'red\'>'.$Mun2.' (I)</font>';
	elseif($Mun2_Type ==4)
		$Mun2='<font color=\'green\'>'.$Mun2.' (APHE)</font>';
	elseif($Mun2_Type ==5)
		$Mun2='<font color=\'orange\'>'.$Mun2.' (API)</font>';
	else
		$Mun2='<font color=\'black\'>'.$Mun2.'</font>';
	if($HPmax >$HP)
	{
		if($HP ==0)
			$moda=99;
		else
			$moda=$HPmax/$HP;		
		if($HP <100)
			$hp_icon='hp100';
		elseif($HP <250)
			$hp_icon='hp250';
		elseif($HP <500)
			$hp_icon='hp500';
		elseif($HP <750)
			$hp_icon='hp750';
		else
			$hp_icon='hp1000';
	}
	else
	{
		$moda=1;
		$hp_icon='hp';
	}
	if($avion_db =="Avion" and $Avion_Bombe_Nbr >0 and $Avion_Bombe)
	{
		$charge_sup=2/($Masse/($Avion_Bombe*$Avion_Bombe_Nbr));
		$moda*=(1+$charge_sup);
	}
	$Speed=GetSpeed($avion_db,$avion,$alt,$meteo,$moda,$malus_incident,$gaz,$flaps);
	if($Speed <0)
		$Speed=0;
	elseif($Speed <50)
		$Speed=50;
	$speed_icon=round($Speed/100);
	$alt_icon=round($alt/1000);
	if($alt_icon >10)$alt_icon=10;
	/*if($Stress_Commandes >10){$Stress_Commandes='<font color=\'red\'>'.$Stress_Commandes.'</font>';}elseif($Stress_Commandes > 5){$Stress_Commandes='<font color=\'orange\'>'.$Stress_Commandes.'</font>';}
	if($Stress_Train >10){$Stress_Train='<font color=\'red\'>'.$Stress_Train.'</font>';}elseif($Stress_Train >5){$Stress_Train='<font color=\'orange\'>'.$Stress_Train.'</font>';}*/
	if($Stress_Arme1 >10){$Arme1_nom='<font color=\'red\'>'.$Arme1_nom.'</font>';}elseif($Stress_Arme1 >5){$Arme1_nom='<font color=\'orange\'>'.$Arme1_nom.'</font>';}
	if($Stress_Arme2 >10){$Arme2_nom='<font color=\'red\'>'.$Arme2_nom.'</font>';}elseif($Stress_Arme2 >5){$Arme2_nom='<font color=\'orange\'>'.$Arme2_nom.'</font>';}
	//$Puissance_c=round((2000-$Puissance)/2);
	if(!$Engine_Nbr)$Engine_Nbr=$Engine_Nbr_Ori;
	$Puissance=round((2000-GetPuissance($avion_db,$avion,$alt,$HP,$moda,$malus_incident,$Engine_Nbr,$gaz))/2);
	if($Puissance <0 and $PlayerID >1)$Puissance=0;
	$P_Unit=round($Puissance/$Engine_Nbr);
	for($im=1;$im<$Engine_Nbr_Ori+1;$im++)
	{
		if($im <=$Engine_Nbr)
		{
			$moteurs_jauges.="<img src='images/jaugep.jpg'>";
			if($im >1)
				$Puissance_txt.=' | '.$P_Unit;
			else
				$Puissance_txt=$P_Unit;
		}
		else
		{
			$moteurs_jauges.="<img src='images/jaugepe.jpg'>";
			if($im >1)
				$Puissance_txt.=' | 0';
			else
				$Puissance_txt='0';
		}
	}
	$icon_soute='';
	if($Avion_Bombe ==300)
	{
		$Avion_Bombe='Charges';
		$icon_soute='icon_mine.png';
	}
	elseif($Avion_Bombe ==400)
	{
		$Avion_Bombe='Mines';
		$icon_soute='icon_mine.png';
	}
	elseif($Avion_Bombe ==800)
	{
		$Avion_Bombe='Torpilles';
		$icon_soute='icon_torpille.png';
	}
	elseif($Avion_Bombe ==26 or $Avion_Bombe ==27)
		$Avion_Bombe='Caméra';
	elseif($Avion_Bombe ==30)
		$Avion_Bombe='Fusées';
	elseif($Avion_Bombe ==80)
		$Avion_Bombe='Rockets';
	elseif($Avion_Bombe ==50000)
		$Avion_Bombe='Fret (8mm)';
	elseif($Avion_Bombe ==15000)
		$Avion_Bombe='Fret (13mm)';
	elseif($Avion_Bombe ==5000)
		$Avion_Bombe='Fret (20mm)';
	elseif($Avion_Bombe ==4500)
		$Avion_Bombe='Fret (30mm)';
	elseif($Avion_Bombe ==4000)
		$Avion_Bombe='Fret (40mm)';
	elseif($Avion_Bombe ==3000)
		$Avion_Bombe='Fret (50mm)';
	elseif($Avion_Bombe ==2900)
		$Avion_Bombe='Fret (60mm)';
	elseif($Avion_Bombe ==2500)
		$Avion_Bombe='Fret (75mm)';
	elseif($Avion_Bombe ==2100)
		$Avion_Bombe='Fret (90mm)';
	elseif($Avion_Bombe ==1200)
		$Avion_Bombe='Fret (87 Octane)';
	elseif($Avion_Bombe ==1100)
		$Avion_Bombe='Fret (100 Octane)';
	elseif($Avion_Bombe ==200 or $Avion_Bombe ==100)
	{
		$Avion_Bombe='Paras';
		$icon_soute='para_icon.png';
	}
	elseif(!$Avion_Bombe)
		$icon_soute='';
	else
	{
		$Avion_Bombe=$Avion_Bombe.'kg';
		$icon_soute='icon_bomb.png';
	}
	if($icon_soute)
		$soute="<img src='images/".$icon_soute."' title='".$Avion_Bombe_nbr." ".$Avion_Bombe."'><br>".$Avion_Bombe_nbr." ".$Avion_Bombe;
	else
		$soute="Vide";
	$TypeMission=GetMissionType($Mission_Type);
	if($Cible_long >67)
		$Carte=3;
	elseif($Cible_lat >52 and $Cible_long >13)
		$Carte=4;
	elseif($Cible_lat >41 and $Cible_long >13)
		$Carte=1;
	elseif($Cible_lat <43 and $Cible_long >=13)
		$Carte=2;
	elseif($Cible_lat <43 and $Cible_long <13)
		$Carte=12;
	else
		$Carte=0;
	$Carte_Bouton="<a href='carte_ground.php?map=".$Carte."&mode=99&cible=".$Cible."' class='btn btn-primary' onclick='window.open(this.href); return false;'>Carte</a>";
	if($Stress_Moteur >10)
	{
		$Stress_Moteur="<div class='i-flex led_red' title='".$Stress_Moteur."'></div>";
		$Puissance_txt='<font color=\'red\'>'.$Puissance_txt.'</font>';
	}
	elseif($Stress_Moteur >5)
	{
		$Stress_Moteur="<div class='i-flex led_orange' title='".$Stress_Moteur."'></div>";
		$Puissance_txt='<font color=\'orange\'>'.$Puissance_txt.'</font>';
	}
	else
		$Stress_Moteur="<div class='i-flex led_green' title='".$Stress_Moteur."'></div>";
	if($Stress_Commandes >10)
		$Stress_Commandes="<div class='i-flex led_red' title='".$Stress_Commandes."'></div>";
	elseif($Stress_Commandes >5)
		$Stress_Commandes="<div class='i-flex led_orange' title='".$Stress_Commandes."'></div>";
	else
		$Stress_Commandes="<div class='i-flex led_green' title='".$Stress_Commandes."'></div>";
	if($Stress_Train >10)
		$Stress_Train="<div class='i-flex led_red' title='".$Stress_Train."'></div>";
	elseif($Stress_Train >5)
		$Stress_Train="<div class='i-flex led_orange' title='".$Stress_Train."'></div>";
	else
		$Stress_Train="<div class='i-flex led_green' title='".$Stress_Train."'></div>";	
	if($PlayerID ==1 or $PlayerID ==2)
		$Jauges='R: '.$Reput.' | A:'.$Grade;
	else	
		$Jauges='';						
	$toolbar="<table class='table'><thead><tr><th>Météo</th><th>Mission</th><th>Objectif</th><th>Distance</th><th>Carte</th></tr></thead>
				<tbody><tr><td><img src='images/meteo".$meteo.".jpg'></td><td>".$TypeMission."</td><td>".$NomCible."</td><td>".$chemin."km</td><td>".$Carte_Bouton."</td></tr></tbody></table>
				<h3>".$avion_nom.' '.$Avion_icon."</h3>
				<table class='table'><thead><tr><th>Robustesse</th><th>Moteur</th><th>Vitesse</th><th>Altitude</th><th>Carburant</th><th title='Armement principal'>".$Arme1_nom."</th><th title='Armement secondaire'>".$Arme2_nom."</th><th>Soute</th><th title='Stress Moteur | Commandes | Train'>Stress</th></tr></thead>
				<tbody><tr><td><img src='images/".$hp_icon.".gif'><br>".$HP."</td><td>".$moteurs_jauges."<br>".$Puissance_txt."</td><td><img src='images/airspeed".$speed_icon.".gif'><br>".$Speed."</td><td><img src='images/alt".$alt_icon.".gif'><br>".$alt."</td><td><img src='images/".$essence_icon.".gif'><br>".floor($essence)."</td>
				<td><img src='images/mun".$Mun1_icon.".gif'><br>".$Mun1."</td><td><img src='images/mun".$Mun2_icon.".gif'><br>".$Mun2."</td><td>".$soute."</td><td title='Stress Moteur | Commandes | Train'>".$Stress_Moteur." | ".$Stress_Commandes." | ".$Stress_Train."</td></tr></tbody>
				</table>";
	return $toolbar;
}

function ShowGaz($avion, $c_gaz, $flaps, $manche, $combat=false, $pvp=false)
{
	if($pvp)
	{
		$Pilote_pvp=$_SESSION['Pilote_pvp'];
		$Avion_db="Avion";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Stress_Commandes,Stress_Train,S_Purge FROM Pilote_PVP WHERE ID='$Pilote_pvp'");
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Stress_Commandes=$data['Stress_Commandes'];
				$Stress_Train=$data['Stress_Train'];
				$Purge=$data['S_Purge']*5;
			}
			mysqli_free_result($result);
			unset($data);
		}
	}
	else
	{
		$PlayerID=$_SESSION['PlayerID'];
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT S_Avion_db,Stress_Commandes,Stress_Train,S_Purge FROM Pilote WHERE ID='$PlayerID'");
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avion_db=$data['S_Avion_db'];
				$Stress_Commandes=$data['Stress_Commandes'];
				$Stress_Train=$data['Stress_Train'];
				$Purge=$data['S_Purge']*5;
			}
			mysqli_free_result($result);
			unset($data);
		}
	}
	if(mt_rand(10,50) >$Stress_Commandes-$Purge)
	{
		$result=mysqli_query($con,"SELECT Type,Volets,Engine,Plafond,VitesseA,VitesseP,Train FROM $Avion_db WHERE ID='$avion'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Type_avion=$data['Type'];
				$Volets=$data['Volets'];
				$Engine=$data['Engine'];
				$Plafond=$data['Plafond'];
				$VitesseA=$data['VitesseA'];
				$VitesseP=$data['VitesseP'];
				$Train=$data['Train'];
			}
			mysqli_free_result($result);
			unset($data);
			$Boost=GetData("Moteur","ID",$Engine,"Boost");
		}
		$choix_flaps='<option value=\'1\'>1 cran</option>';
		if($Volets)
		{
			if($flaps ==2)
				$choix_flaps.='<option value=\'2\' selected>2 crans</option>';
			else
				$choix_flaps.='<option value=\'2\'>2 crans</option>';
		}
		if($Volets >1)
		{
			if($flaps ==3)
				$choix_flaps.='<option value=\'3\' selected>3 crans</option>';
			else
				$choix_flaps.='<option value=\'3\'>3 crans</option>';
		}
		if($Volets ==3)
			$choix_flaps.='<option value=\'4\'>Volets de piqué</option>';		
		if($Boost)
			$choix_gaz='<option value=\'130\'>Boost</option>';
		if($combat !=7)
		{
			for($i=1;$i<11;$i++)
			{
				$iu=$i*10;
				if($iu ==$c_gaz)
					$choix_gaz.="<option value='".$iu."' selected>".$iu."%</option>";
				else
					$choix_gaz.="<option value='".$iu."'>".$iu."%</option>";
			}
		}	
		if(!$combat)
		{
			if($manche >$Plafond)$manche=$Plafond;
			$choix_manche.="<option value='".$manche."' selected>".$manche."m</option>";
			for($i=500;$i<=$Plafond;$i+=500)
			{
				if($i >$Plafond)
					break;
				$choix_manche.="<option value='".$i."'>".$i."m</option>";
			}
		}
		elseif($combat ==2) //low
			$choix_manche.="<option value='100' selected>100m</option>";
		elseif($combat ==3) //rencontre
		{
			if($manche >$Plafond)$manche=$Plafond;
			$VitesseP*=2;
			$base=$manche-$VitesseP;
			if($base <100)$base=100;
			for($i=$base;$i<=$manche+$VitesseA;$i+=500)
			{
				if($i >$Plafond)
					break;
				if($i ==$manche)
					$choix_manche.="<option value='".$i."' selected>".$i."m</option>";
				else
					$choix_manche.="<option value='".$i."'>".$i."m</option>";
			}
		}
		elseif($combat ==4) //pas de haute altitude
		{
			for($i=500;$i<=5000;$i+=500)
			{
				if($i >$Plafond)
					break;
				if($i ==$manche)
					$choix_manche.="<option value='".$i."' selected>".$i."m</option>";
				else
					$choix_manche.="<option value='".$i."'>".$i."m</option>";
			}
		}
		elseif($combat ==5) //chasseur : obligation de suivre l'ennemi
		{
			$manche+=mt_rand(-500,200);
			if($manche >$Plafond)
				$manche=$Plafond;
			elseif($manche <100)
				$manche=100;
			$choix_manche.="<option value='".$manche."' selected>".$manche."m</option>";
		}
		elseif($combat ==6) //interception
		{
			if($manche >$Plafond)
				$manche=$Plafond;
			$VitesseA*=5;
			if($VitesseA <1000)$VitesseA=1000;
			for($i=500;$i<=$VitesseA;$i+=500)
			{
				if($i >$Plafond)
					break;
				if($i ==$manche)
					$choix_manche.="<option value='".$i."' selected>".$i."m</option>";
				else
					$choix_manche.="<option value='".$i."'>".$i."m</option>";
			}
		}
		elseif($combat ==7) //landing
		{
			for($i=1;$i<21;$i++)
			{
				$iu=$i*5;
				if($iu ==$c_gaz)
					$choix_gaz.="<option value='".$iu."' selected>".$iu."%</option>";
				else
					$choix_gaz.="<option value='".$iu."'>".$iu."%</option>";
			}
			$choix_manche.="<option value='100' selected>100m</option>";
			$roues_titre="<th>Train d'atterrissage</th>";
			if($Train ==0)
			{
				if($Stress_Train <10)
				{
					$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>
						<Input type='Radio' name='roues' value='0' checked>- Train rentré.<br>
						<Input type='Radio' name='roues' value='1'>- Train sorti.<br></td>";
				}
				else
				{
					$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>
						<Input type='Radio' name='roues' value='0' checked>- Train rentré.<br>
						<Input type='Radio' name='roues' value='1' disabled>- Train endommagé !<br></td>";
				}
			}
			elseif($Train <6)
			{
				if($Stress_Train <20)
				{
					$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>
						<Input type='Radio' name='roues' value='0' checked>- Train rentré.<br>
						<Input type='Radio' name='roues' value='1'>- Train sorti.<br></td>";
				}
				else
				{
					$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>
						<Input type='Radio' name='roues' value='0' checked>- Train rentré.<br>
						<Input type='Radio' name='roues' value='1' disabled>- Train endommagé !<br></td>";
				}
			}
			else
			{
				if($Stress_Train <10)
					$roues_txt="<input type='hidden' name='roues' value='1'>";
				else
					$roues_txt="<input type='hidden' name='roues' value='0'>";
			}
		}
		elseif($combat ==8) //strafing 2e passe
		{
			if($Type_avion >1)
			{
				$VitesseA=round($VitesseA/2);
				$manche2=$manche+$VitesseA;
				if($manche2 >$Plafond)$manche2=$Plafond;
				$choix_manche.="<option value='".$manche2."'>".$manche2."m</option>";
				$choix_manche.="<option value='".$manche."' selected>".$manche."m</option>";
			}
			$choix_manche.="<option value='100'>100m</option>";
			$choix_manche.="<option value='50'>50m</option>";
		}
		else
		{
			if($manche >$Plafond)$manche=$Plafond;
			$VitesseA=round($VitesseA/2);
			$manche2=$manche+$VitesseA;
			if($manche2 >$Plafond)$manche2=$Plafond;
			$choix_manche.="<option value='".$manche2."'>".$manche2."m</option>";
			$choix_manche.="<option value='".$manche."' selected>".$manche."m</option>";
			$manche2=$manche-$VitesseP;
			if($manche2 <100)$manche2=100;
			$choix_manche.="<option value='".$manche2."'>".$manche2."m</option>";
		}
	}
	else
	{
		$choix_gaz="<option value='".$c_gaz."'>Commandes bloquées</option>";
		$choix_manche="<option value='".$manche."'>Commandes bloquées</option>";
		$choix_flaps="<option value='".$flaps."'>Commandes bloquées</option>";
		$roues_txt="<td><img src='images/lgear.gif' title='commande du train'><br>Train bloqué</td>";
	}
	$gaz='<table class=\'table\'><thead><tr><th>Manche à balai</th><th>Manette des gaz</th><th>Volets</th>'.$roues_titre.'</tr></thead>
			<tr><td><img src=\'images/manche.gif\' title=\'altitude\'>
			<select name=\'Alt\' style=\'width:200px\'>'.$choix_manche.'</select></td>
			<td><img src=\'images/gaz.gif\' title=\'régime moteur\'>
			<select name=\'gaz\' style=\'width:200px\'>'.$choix_gaz.'</select></td>
			<td><img src=\'images/flaps.gif\' title=\'commande des volets\'>
			<select name=\'flaps\' style=\'width:200px\'><option value=\'0\'>Aucun</option>'.$choix_flaps.'</select></td>'.$roues_txt.'</table>';
	return $gaz;
}

function GetSpeed($Avion_db, $avion, $alt, $meteo, $moda=1, $malus_incident=1, $gaz=100, $flaps=0)
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT VitesseB,VitesseH,Engine,Alt_ref FROM $Avion_db WHERE ID='$avion'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$VitesseB=$data['VitesseB'];
			$VitesseH=$data['VitesseH'];
			$Moteur=$data['Engine'];
			$alt_ref=$data['Alt_ref'];
		}
		mysqli_free_result($result);
		unset($data);
	}			
	if(!$alt_ref)
	{
		$Compresseur=GetData("Moteur","ID",$Moteur,"Compresseur");
		if($Compresseur ==3)
			$alt_ref=2000;
		elseif($Compresseur ==2)
			$alt_ref=7500;
		else
			$alt_ref=5000;
	}
	if($alt >$alt_ref)
		$Vit=$VitesseH+((($VitesseH-$VitesseB)/$alt_ref)*($alt_ref-$alt));
	elseif($alt <=$alt_ref)
		$Vit=$VitesseB+((($VitesseH-$VitesseB)/$alt_ref)*$alt);		
	if(!$malus_incident)$malus_incident=1;
	if(!$gaz)$gaz=1;
	if(!$moda)$moda=$Vit;
	if($meteo <-50)$moda+=0.2;
	$Speed=$Vit/$moda*$malus_incident*$gaz/100;
	if($flaps >0)$Speed*=((10-$flaps)/10);	
	if($Speed <0)$Speed=0;
	return round($Speed);
}

function GetVis($Avion_db, $avion, $Cible, $meteo, $alt, $alt_eni=0, $PlayerID=0, $Unit=0)
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Visibilite,Camouflage FROM $Avion_db WHERE ID='$avion'");
	$Zone=mysqli_result(mysqli_query($con,"SELECT Zone FROM Lieu WHERE ID='$Cible'"),0);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_assoc($result))
		{
			$Vis=$data['Visibilite'];
			$Camouflage=$data['Camouflage'];
		}
	}
	if(!$Camouflage and $PlayerID >0)
	{
		$Camouflage=GetData("Pilote","ID",$PlayerID,"S_Camo");
		if(!$Camouflage)
			$Camouflage=GetData("Unit","ID",$Unit,"U_Camo");
	}
	switch($Camouflage)
	{
		case 2:
			$Sup="Bleu";
			$Inf="Bleu";
		break;
		case 3:
			$Sup="Gris";
			$Inf="Gris";
		break;
		case 4:
			$Sup="Noir";
			$Inf="Noir";
		break;
		case 5:
			$Sup="Noir";
			$Inf="Gris";
		break;
		case 6:
			$Sup="Brun";
			$Inf="Gris";
		break;
		case 7:
			$Sup="Bleu";
			$Inf="Gris";
		break;
		case 8:
			$Sup="Gris";
			$Inf="Bleu";
		break;
		case 9:
			$Sup="Gris";
			$Inf="Noir";
		break;
		case 10:
			$Sup="Vert";
			$Inf="Gris";
		break;
		case 11:
			$Sup="Vert";
			$Inf="Bleu";
		break;
		case 12:
			$Sup="Vert";
			$Inf="Noir";
		break;
		case 13:
			$Sup="Vert-Brun";
			$Inf="Gris";
		break;
		case 14:
			$Sup="Vert-Brun";
			$Inf="Bleu";
		break;
		case 15:
			$Sup="Vert-Brun";
			$Inf="Noir";
		break;
		case 16:
			$Sup="Vert-Gris";
			$Inf="Gris";
		break;
		case 17:
			$Sup="Vert-Gris";
			$Inf="Bleu";
		break;
		case 18:
			$Sup="Vert-Noir";
			$Inf="Gris";
		break;
		case 19:
			$Sup="Vert-Noir";
			$Inf="Bleu";
		break;
		case 20:
			$Sup="Sable";
			$Inf="Gris";
		break;
		case 21:
			$Sup="Sable";
			$Inf="Bleu";
		break;
		case 22:
			$Sup="Sable-Vert";
			$Inf="Gris";
		break;
		case 23:
			$Sup="Sable-Vert";
			$Inf="Bleu";
		break;
		case 24:
			$Sup="Sable-Brun";
			$Inf="Gris";
		break;
		case 25:
			$Sup="Sable-Brun";
			$Inf="Bleu";
		break;
		default:
			$Sup="";
			$Inf="";
		break;
	}
	if($alt_eni >=$alt and $alt <9000)
	{
		if($Zone ==8 and $Sup =="Sable")
			$Vis-=($Vis/5);
		elseif($Zone ==7 and $Sup =="Gris")
			$Vis-=($Vis/5);
		elseif($Zone ==6 and $Sup =="Bleu")
			$Vis-=($Vis/5);
		elseif($Zone ==5 and ($Sup =="Vert-Gris" or $Sup =="Vert-Noir" or $Sup =="Vert-Brun"))
			$Vis-=($Vis/5);
		elseif($Zone ==4 and ($Sup =="Brun" or $Sup =="Vert-Gris" or $Sup =="Vert-Noir" or $Sup =="Vert-Brun"))
			$Vis-=($Vis/5);
		elseif($Zone ==3 and $Sup =="Vert-Brun")
			$Vis-=($Vis/5);
		elseif($Zone ==2 and ($Sup =="Vert" or $Sup =="Vert-Brun"))
			$Vis-=($Vis/5);
		elseif($Zone ==1 and $Sup =="Brun")
			$Vis-=($Vis/5);
		elseif($Zone ==0 and $Sup =="Vert")
			$Vis-=($Vis/5);
		elseif($Zone ==9 and $Sup =="Vert")
			$Vis-=($Vis/5);
		elseif($Zone ==11 and $Sup =="Vert")
			$Vis-=($Vis/5);
		elseif($Zone ==8 and strstr($Sup,"Sable"))
			$Vis-=($Vis/10);
		elseif($Zone ==7 and strstr($Sup,"Gris"))
			$Vis-=($Vis/10);
		elseif($Zone ==5 and (strstr($Sup,"Vert") or strstr($Sup,"Brun") or strstr($Sup,"Noir") or strstr($Sup,"Gris")))
			$Vis-=($Vis/10);
		elseif($Zone ==4 and (strstr($Sup,"Vert") or strstr($Sup,"Brun") or strstr($Sup,"Noir") or strstr($Sup,"Gris")))
			$Vis-=($Vis/10);
		elseif($Zone ==3 and (strstr($Sup,"Vert") or strstr($Sup,"Brun")))
			$Vis-=($Vis/10);
		elseif($Zone ==2 and (strstr($Sup,"Vert") or strstr($Sup,"Brun")))
			$Vis-=($Vis/10);
		elseif($Zone ==1 and strstr($Sup,"Brun"))
			$Vis-=($Vis/10);
		elseif($Zone ==0 and strstr($Sup,"Vert"))
			$Vis-=($Vis/10);
		elseif($Zone ==9 and strstr($Sup,"Vert"))
			$Vis-=($Vis/10);
		elseif($Zone ==11 and strstr($Sup,"Vert"))
			$Vis-=($Vis/10);
		if($meteo < -84 and strstr($Sup,"Noir"))
			$Vis-=($Vis/10);			
	}
	elseif($alt_eni <$alt and $alt <9000)
	{
		if($meteo <-84 and $Inf =="Noir")
			$Vis-=($Vis/10);
		elseif($meteo <-9 and $Inf =="Gris")
			$Vis-=($Vis/10);
		elseif($meteo >-6 and $Inf =="Bleu")
			$Vis-=($Vis/10);
	}
	return $Vis;
}

function GetPuissance($Avion_db, $avion, $alt, $HP, $moda=1, $malus_incident=1, $Engine_Nbr=1, $gaz=100)
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Robustesse,Puissance,Engine,Engine_Nbr,Masse,Alt_ref FROM $Avion_db WHERE ID='$avion'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$HPmax=$data['Robustesse'];
			$Puiss=$data['Puissance'];
			$Alt_ref=$data['Alt_ref'];
			$Moteur=$data['Engine'];
			$Engine_Nbr_Ori=$data['Engine_Nbr'];
			$Masse=$data['Masse'];
		}
		mysqli_free_result($result);
	}
	$Compresseur=GetData("Moteur","ID",$Moteur,"Compresseur");
	if($HP ==0)$HP=0.0001;
	if($malus_incident ==0)$malus_incident=1;
	if($moda ==1 and $HP <9999)
	{
		if($HPmax >$HP)
			$moda=$HPmax/$HP;
	}	
	if($Compresseur ==2)
	{
		if($alt <$Alt_ref)
			$Puiss=floor($Puiss/(1+(($Alt_ref-$alt)/10000)));
	}
	elseif($Compresseur ==3)
	{
		if($alt >$Alt_ref)
			$Puiss=floor($Puiss/(1+(($alt-$Alt_ref)/10000)));
	}
	elseif($Compresseur ==1)
	{
		if($alt >$Alt_ref)
			$Puiss=floor($Puiss/(1+(($alt-$Alt_ref)/10000)));
		elseif($alt <$Alt_ref)
			$Puiss=floor($Puiss/(1+(($Alt_ref-$alt)/20000)));
	}
	else
	{
		if($alt >$Alt_ref)
			$Puiss=floor($Puiss/(1+(($alt-$Alt_ref)/5000)));
		elseif($alt <$Alt_ref)
			$Puiss=floor($Puiss/(1+(($Alt_ref-$alt)/10000)));
	}
	if($Engine_Nbr <$Engine_Nbr_Ori)
	{
		$Engine_Diff=$Engine_Nbr_Ori-$Engine_Nbr;
		if($Engine_Diff)
			$Puiss-=(($Puiss/$Engine_Nbr_Ori)*$Engine_Diff);
	}
	$Puiss*=($gaz/100);
	if(!$Puiss)$Puiss=0.0001;
	$Puissance=$Masse/$Puiss*100;
	$Puissancef=round($Puissance*$moda/$malus_incident);
	return $Puissancef;
}

function AddPilotage($Avion_db, $avion, $PlayerID, $Modif=1)
{
	if($Avion_db =="Avions_Persos")
		$avion=GetData($Avion_db,"ID",$avion,"ID_ref");
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Pilotage FROM XP_Avions WHERE PlayerID='$PlayerID' AND AvionID='$avion'");
	if($result)
	{
		$Classement=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$Score=$Classement['Pilotage'];
		if($Score)
		{
			if($Modif !=0)
			{
				//Dégressivité
				if($Modif >0)
				{
					if($Score >240)
						$Modif=$Modif/1000000;
					elseif($Score >230)
						$Modif=$Modif/100000;
					elseif($Score >220)
						$Modif=$Modif/10000;
					elseif($Score >210)
						$Modif=$Modif/1000;
					elseif($Score >200)
						$Modif=$Modif/200;
					elseif($Score >175)
						$Modif=$Modif/100;
					elseif($Score >150)
						$Modif=$Modif/50;
					elseif($Score >125)
						$Modif=$Modif/20;
					elseif($Score >100)
						$Modif=$Modif/10;
					elseif($Score >75)
						$Modif=$Modif/5;
					elseif($Score >50)
						$Modif=$Modif/2;
				}
				$Score+=$Modif;
				$ok=mysqli_query($con,"UPDATE XP_Avions SET Pilotage='$Score' WHERE PlayerID='$PlayerID' AND AvionID='$avion'");
				if(!$ok)
				{
					$msg.="Erreur de mise à jour UpdateCarac ".mysqli_error($con);
					mail('binote@hotmail.com','Aube des Aigles: AddPilotage Error',$msg);
				}
			}
		}
		else
		{
			$query="INSERT INTO XP_Avions (PlayerID, AvionID, Pilotage) VALUES ('$PlayerID','$avion','$Modif')";
			$ok=mysqli_query($con,$query);
			if(!$ok)
			{
				$msg.="Erreur de mise à jour du Pilotage de l'avion ".$avion." du joueur ".$PlayerID."<br>".mysqli_error($con);
				mail('binote@hotmail.com','Aube des Aigles: AddPilotage Error',$msg);
			}
		}
	}
}

function AddXPAvionIA($Avion,$Unite,$Modif=1)
{
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Exp FROM XP_Avions_IA WHERE Unite='$Unite' AND AvionID='$Avion'");
	if($result)
	{
		$Classement=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$Score=$Classement['Exp'];
		if($Score)
		{
			if($Modif !=0)
			{
				if($Modif >0)
				{
					if($Score >240)
						$Modif=$Modif/1000000;
					elseif($Score >230)
						$Modif=$Modif/100000;
					elseif($Score >220)
						$Modif=$Modif/10000;
					elseif($Score >210)
						$Modif=$Modif/1000;
					elseif($Score >200)
						$Modif=$Modif/200;
					elseif($Score >175)
						$Modif=$Modif/100;
					elseif($Score >150)
						$Modif=$Modif/50;
					elseif($Score >125)
						$Modif=$Modif/20;
					elseif($Score >100)
						$Modif=$Modif/10;
					elseif($Score >75)
						$Modif=$Modif/5;
					elseif($Score >50)
						$Modif=$Modif/2;
				}
				$Score+=$Modif;
				$ok=mysqli_query($con,"UPDATE XP_Avions_IA SET Exp='$Score' WHERE Unite='$Unite' AND AvionID='$Avion'");
				if(!$ok)
				{
					$msg.="Erreur de mise à jour Update ".mysqli_error($con);
					mail('binote@hotmail.com','Aube des Aigles: AddXPAvionIA Error',$msg);
				}
			}
		}
		else
		{
			$query="INSERT INTO XP_Avions_IA (Unite,AvionID,Exp) VALUES ('$Unite','$Avion','$Modif')";
			$ok=mysqli_query($con,$query);
			if(!$ok)
			{
				$msg.="Erreur de mise à jour Exp avion ".$Avion." de l'unité ".$Unite."<br>".mysqli_error($con);
				mail('binote@hotmail.com','Aube des Aigles: AddXPAvionIA Error',$msg);
			}
		}
	}
}

function AddPilotage_Sandbox($Avion_db, $avion, $PlayerID, $Modif=1)
{
	if($Avion_db =="Avions_Sandbox")
		$avion=GetData($Avion_db,"ID",$avion,"ID_ref");
	$con=dbconnecti(2);
	$result=mysqli_query($con,"SELECT Pilotage FROM XP_Avions_sandbox WHERE PlayerID='$PlayerID' AND AvionID='$avion'");
	if($result)
	{
		$Classement=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$Score=$Classement['Pilotage'];
		if($Score)
		{
			if($Modif !=0)
			{
				$Score+=$Modif;
				$ok=mysqli_query($con,"UPDATE XP_Avions_sandbox SET Pilotage='$Score' WHERE PlayerID='$PlayerID' AND AvionID='$avion''");
				if(!$ok)
				{
					$msg.="Erreur de mise à jour UpdateCarac ".mysqli_error($con);
					mail('binote@hotmail.com','Aube des Aigles: AddPilotage Sandbox Error',$msg);
				}
			}
		}
		else
		{
			$query="INSERT INTO XP_Avions_sandbox (PlayerID, AvionID, Pilotage) VALUES ('$PlayerID','$avion','$Modif')";
			$ok=mysqli_query($con,$query);
			if(!$ok)
			{
				$msg.="Erreur de mise à jour du Pilotage de l'avion ".$avion." du joueur ".$PlayerID."<br>".mysqli_error($con);
				mail('binote@hotmail.com','Aube des Aigles: AddPilotage Sandbox Error',$msg);
			}
		}
	}
}

function IsAilier($Player,$Leader)
{
	$reput=GetData("Pilote","ID",$Player,"Reputation");
	if($reput <500 and $Leader)
		$ailier=true;
	else
		$ailier=false;
	return $ailier;
}