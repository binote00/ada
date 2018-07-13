<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Regiment=Insec($_POST['Reg']);
	$Type=Insec($_POST['Cat']);
	if(($Regiment >0 or $Admin) and $Type >0)
	{
		$vehs="";
		$country=$_SESSION['country'];
		if(!$Regiment and $Admin)$Regiment=1;
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$Special_Score=mysqli_result(mysqli_query($con,"SELECT Special_Score FROM Pays WHERE ID='$country'"),0);
		$result=mysqli_query($con,"SELECT Reputation,Avancement,Credits,Trait,Division,Admin,Front FROM Officier WHERE ID='$OfficierID'");
		$resultreg=mysqli_query($con,"SELECT ID FROM Regiment WHERE Officier_ID='$OfficierID'");
		$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE Officier='$OfficierID'"),0);
		mysqli_close($con);
		if($resultreg)
		{
			while($datareg=mysqli_fetch_array($resultreg))
			{
				$Regs[]=$datareg['ID']; 
			}
			mysqli_free_result($resultreg);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Reputation=$data['Reputation'];
				$Avancement=$data['Avancement'];
				$Trait_o=$data['Trait'];
				$Credits_ori=$data['Credits'];
				$Division=$data['Division'];
				$Admin=$data['Admin'];
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		$Year=substr($Date_Campagne,2,2);
		$Regiments=implode(',',$Regs);
		/*if($Admin ==1)
		{
			SetData("Officier","Credits",$CT_MAX,"ID",$OfficierID);
			$Credits_ori=$CT_MAX;
		}*/
		//Get Vehicules
		$Reputation=sqrt($Reputation)*50;
		if($Trait_o ==8)
			$Reputation+=500;
		if(!$Division)
		{
			$Avancement=5000;
			$Reputation=99;
		}
		if($Type ==13 or $Type ==17) //Loco ou Sub
			$query="SELECT * FROM Cible WHERE Pays IN (0,'$country') AND `Date` <='$Date_Campagne' AND Unit_ok=1 AND Type='$Type' AND Premium IN(0,'$Premium') AND Reput <='$Avancement'/500 ORDER BY Reput ASC,HP ASC,Nom ASC";
		elseif($Type ==998) //Wagons
			$query="SELECT * FROM Cible WHERE Pays IN (0,'$country') AND `Date` <='$Date_Campagne' AND Unit_ok=1 AND mobile=4 AND Type<>13 AND Premium IN(0,'$Premium') AND Reput <='$Avancement'/1000 ORDER BY Reput ASC,HP ASC,Nom ASC";
		elseif($Type ==999) //Inf
			$query="SELECT * FROM Cible WHERE Pays IN (0,'$country') AND `Date` <='$Date_Campagne' AND Unit_ok=1 AND mobile=3 AND Type NOT IN (4,12,90) AND Premium IN(0,'$Premium') AND ((HP <='$Reputation') OR (Reput <='$Avancement'/1000) OR ('$Year' >(YEAR(`Date`)+1))) ORDER BY Reput ASC,HP ASC,Nom ASC";
		elseif($Type >13 and $Type <19)
		{
			if($country ==7 or $country ==9)
				$query="SELECT * FROM Cible WHERE Pays IN (0,'$country') AND `Date` <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Type='$Type' AND Premium IN(0,'$Premium') AND ID NOT IN (5005,5006,5007,5008,5009,5022) AND Reput <='$Avancement'/1000 ORDER BY Reput ASC,HP ASC,Nom ASC";
			elseif($Front ==2)
				$query="SELECT * FROM Cible WHERE Pays IN (0,'$country') AND `Date` <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Type='$Type' AND Premium IN(0,'$Premium') AND Reput <='$Avancement'/1000 AND ID<>'5124' ORDER BY Reput ASC,HP ASC,Nom ASC";
			else
				$query="SELECT * FROM Cible WHERE Pays IN (0,'$country') AND `Date` <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Type='$Type' AND Premium IN(0,'$Premium') AND Reput <='$Avancement'/1000 ORDER BY Reput ASC,HP ASC,Nom ASC"; //'$Date_Campagne' > DATE_ADD(`Date`,INTERVAL 1 YEAR)
		}
		else
			$query="SELECT * FROM Cible WHERE Pays IN (0,'$country') AND `Date` <='$Date_Campagne' AND Unit_ok=1 AND Type='$Type' AND mobile NOT IN (4,5) AND Premium IN(0,'$Premium') AND ((HP <='$Reputation') OR (Reput <='$Avancement'/1000) OR ('$Date_Campagne' > DATE_ADD(`Date`,INTERVAL 1 YEAR))) ORDER BY Reput ASC,HP ASC,Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$ID=$data['ID'];
				$mobile=$data['mobile'];
				$Bourrin=false;
				if(!$data['Lease'] or $Special_Score >=$data['Lease'])
					$lend_lease=true;
				else
					$lend_lease=false;
				if($mobile !=4 and $mobile !=5)
				{
					$Usines=1;
					$Indus1=GetData("Lieu","ID",$data['Usine1'],"Industrie");
					if($data['Usine2'])
					{
						$Indus2=GetData("Lieu","ID",$data['Usine2'],"Industrie");
						$Indus1+=$Indus2;
						$Usines++;
					}
					if($data['Usine3'])
					{
						$Indus3=GetData("Lieu","ID",$data['Usine3'],"Industrie");
						$Indus1+=$Indus3;
						$Usines++;
					}
					$Prod=$Indus1/$Usines;
					if($Prod <50)
					{
						$Reste=0;
						$con=dbconnecti(4);
						$Pertes=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (401,405,415,605,615) AND Avion='$ID' AND Unit IN(".$Regiments.",".$Regiment.")"),0);
						$Pertes2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,404,410,420) AND Avion='$ID' AND Unit IN(".$Regiments.",".$Regiment.") AND PlayerID='$OfficierID'"),0);
						mysqli_close($con);						
					}
					else
					{
						$con=dbconnecti();
						$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$ID'"),0);
						$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$ID'"),0);
						mysqli_close($con);
						$con=dbconnecti(4);
						$Perdus=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$ID'"),0);
						$Perdus2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$ID'"),0);
						if($data['Categorie'] ==5 or $data['Categorie'] ==6)
							$Perdus3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$ID'"),0);
						//$Pertes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Unit IN(".$Regiments.",".$Regiment.") AND Pilote_eni='$ID'"),0);
						$Pertes=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (401,405,415,605,615) AND Unit IN(".$Regiments.",".$Regiment.") AND Avion='$ID'"),0);
						$Pertes2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,404,410,420) AND Unit IN(".$Regiments.",".$Regiment.") AND Avion='$ID' AND PlayerID='$OfficierID'"),0);
						mysqli_close($con);
						$Reste=$data['Stock']-$Service-$Service2-$Perdus-$Perdus2-$Perdus3+$data['Repare'];
						if($Reste+$Service+$Service2 >$data['Stock'])$Reste=$data['Stock']-$Service-$Service2;
					}
					$Perdus_indiv=$Pertes+$Pertes2;
					if($Perdus_indiv >0 and $Perdus_indiv >=$data['Production']/5)
						$Bourrin=true;
					if(!$data['Production'] or $data['Reput'] <3)
						$Reste=10;
					if($data['Retrait'] <$Date_Campagne)
						$Reste=10;
				}
				if(!$Bourrin and ($Perdus_indiv <$data['Production'] or $mobile ==4 or $mobile ==5))
				{
					$Arme_Inf="";
					$Arme_Art="";
					$Arme_AT="";
					$Arme_AA="";
					$Art="";
					$AA="";
					$AT="";
					$Couv_Ligne=false;
					$Malus_Off=false;
					$Malus_Def=false;
					$Malus_Raid=false;
					if($data['Categorie'] ==5 or $data['Categorie'] ==6 or $data['Categorie'] ==9 or $data['Categorie'] ==15)
						$Couv_Ligne=true;
					if($data['mobile'] ==1 or $data['mobile'] ==3)
						$Malus_Raid=5;
					$Bonus_Tactique=($data['Radio']+$data['Tourelle'])*5;
					/*if($data['Arme_Inf'])
						$Arme_Inf=GetData("Armes","ID",$data['Arme_Inf'],"Degats");*/
					if($data['Arme_Art'])
					{
						$Arme_Art=(GetData("Armes","ID",$data['Arme_Art'],"Degats")/625)+($data['Portee']/250)+$data['Optics']+($data['Arme_Art_mun']/10);
						$Art="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Arme_Art."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Arme_Art."%'></div></div>";
					}
					else
						$Malus_Off+=10;
					if($data['Arme_AT'])
					{
						$Arme_AT=(GetData("Armes","ID",$data['Arme_AT'],"Degats")/625)+(GetData("Armes","ID",$data['Arme_AT'],"Perf")/6.37)+($data['Portee']/500)+$data['Optics']-($data['Taille']/25.5);
						$AT="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Arme_AT."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Arme_AT."%'></div></div>";
					}
					else
					{
						$Malus_Off+=10;
						$Malus_Def+=10;
					}
					if($data['Arme_AA'])
					{
						if(!$Flak_pr)
							$data['Portee']=GetData("Armes","ID",$data['Arme_AA'],"Portee");
						$Arme_AA=(GetData("Armes","ID",$data['Arme_AA'],"Degats")/1250)+($data['Flak']*40)+($data['Portee']/500)+$data['Optics']+($data['Arme_AT_mun']/100);
						$AA="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Arme_AA."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Arme_AA."%'></div></div>";
					}
					else
						$Malus_Def+=10;
					/*$Max_Veh=GetMaxVeh($Type, $mobile, $data['Flak'], $Avancement);
					$HP_Cie=$HP*$Max_Veh;
					$HP_Cie_pr=$HP_Cie*0.002;*/				
					$Def=($data['HP']/233.33)+($data['Blindage_f']/8.5)+($data['Vitesse']/10)+($Bonus_Tactique/2.5)-($data['Taille']/12.75)+($Couv_Ligne*10)-$Malus_Def;
					$Off=($data['Vitesse']/5)+($Bonus_Tactique/2.5)+($data['Optics']*2)+($data['Fiabilite']*2)+($data['Conso']/25)-$Malus_Off;
					$Raid=($data['Fuel']/10)+($data['Conso']/25)+$data['Fiabilite']+($data['Vitesse']/10)-$Malus_Raid;
					$Reco=$data['Detection']+($data['Vitesse']/5)-($data['Taille']/25.5)+($data['Fuel']/50)+($data['Fiabilite']*2);
					if($data['Charge'])
					{
						$Off=0;
						$Raid=0;
						$Reco=0;
					}
					if($data['Para'])$Raid+=25;
					$Def="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Def."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Def."%'></div></div>";
					$Off="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Off."' aria-valuemin='0' aria-valuemax='50' style='width: ".$Off."%'></div></div>";
					$Raid="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Raid."' aria-valuemin='0' aria-valuemax='500' style='width: ".$Raid."%'></div></div>";
					$Reco="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Reco."' aria-valuemin='0' aria-valuemax='50' style='width: ".$Reco."%'></div></div>";
					if($data['Type'] ==99)
						$data['Nom'].=" (Aide à neutraliser les saboteurs)";
					elseif($data['Type'] ==98 or $data['Type'] ==92 or $data['Categorie'] ==16 or $data['Categorie'] ==19)
						$data['Nom'].=" (Minage,déminage,sabotage,réparation)";
					elseif($data['Type'] ==97)
						$data['Nom'].=" (Déplacement doublé dans montagnes et collines)";							
					$Reput=$data['Reput'];
					if($Reste <10 and $mobile !=4 and $mobile !=5)$Reput*=2;
					if($Credits_ori >=$Reput)
					{
						$vehs.="<tr><td><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'><br><form><input type='button' value='Détail' class='btn btn-primary' onclick=\"window.open('cible.php?cible=".$data['ID']."','Fiche','width=820,height=840,scrollbars=1')\"></form></td>";
						if($Admin){$AT.="<br>Grade=".$Reput*1000;$Art.="<br>Reput=".$data['HP'];}
						$vehs.="<td>".$AT."</td><td>".$AA."</td><td>".$Art."</td><td>".$Def."</td><td>".$Off."</td><td>".$Raid."</td><td>".$Reco."</td>";
						if(!$lend_lease)
							$vehs.="<td class='text-danger'><a href='#' class='popup'>Lend-Lease<span>Ce matériel nécessite ".$data['Lease']." Points de Lend-Lease.<br>Votre nation en possède ".$Special_Score."</span></td></tr>";
						elseif($Reste >3 or $mobile ==4 or $mobile ==5)
						{
							$vehs.="<td><form action='change_materiel1.php' method='post'>
							<input type='hidden' name='Rg' value='".$Regiment."'>
							<input type='hidden' name='Ve' value='".$data['ID']."'>
							<input type='hidden' name='Cr' value='".$Reput."'>
							<input type='Submit' value='".$Reput." CT' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
						}
						elseif($Prod <50)
							$vehs.="<td class='text-danger' title='Votre nation doit réparer les usines détruites'>Usines ".$Prod."%</td></tr>";
						else
							$vehs.="<td class='text-danger' title='Votre nation doit réparer les modèles détruits'>".$Reste." Dispo</td></tr>";
					}
					else
					{
						$vehs.="<tr><td><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'><br><form><input type='button' value='Détail' class='btn btn-warning' onclick=\"window.open('cible.php?cible=".$data['ID']."','Fiche','width=820,height=840,scrollbars=1')\"></form></td>
						<td colspan='7'></td><td class='btn btn-danger'>".$Reput." CT</td></tr>";
					}
				}
			}
			mysqli_free_result($result);
			unset($data);
		}
		else
			$vehs.="<tr><td colspan='16'>Aucun véhicule de cette catégorie n'est disponible</td></tr>";
		$titre="Hangar";
		if($vehs)
		{
			$titre_up="<thead><tr>
					<th width='10%'>Matériel</th>
					<th width='10%'>Anti-Tank</th>
					<th width='10%'>Anti-aérien</th>
					<th width='10%'>Bombardement</th>
					<th width='10%'>Défensif</th>
					<th width='10%'>Offensif</th>
					<th width='10%'>Raid</th>
					<th width='10%'>Reco</th>
					<th width='5%'>Changer l'équipement</th></tr></thead>";
			$mes="<h2>Matériel disponible <a href='#' class='popup'><img src='images/help.png'><span>Changer de matériel réinitialise l expérience.</span></a></h2>
			<div style='overflow:auto; height: 600px;'><table class='table table-striped'>".$titre_up.$vehs."</table></div>";
		}
		else
			$mes="Aucun véhicule de cette catégorie n'est disponible<br>Soit aucun véhicule de cette catégorie n'est actuellement disponible pour votre nation
		, soit votre officier manque de grade ou de réputation pour acquérir les modèles disponibles.<br>Contactez votre planificateur stratégique pour de plus amples informations.";
		if($Admin)
			$mes.="<br>Avancement=".$Avancement."<br>Réputation=".$Reputation."<br>Year=".$Year;
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour</a>";
		include_once('./default.php');
	}
}
?>