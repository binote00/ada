<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 or $OfficierEMID >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		include_once('./menu_infos.php');
		$country=$_SESSION['country'];
		$Date_Campagne=Insec($_POST['Date']);
		$Avancement=Insec($_POST['Grade']);
		$Reputation=Insec($_POST['Reput']);
		$Pays=Insec($_POST['Pays']);
		$Mon_off=Insec($_POST['Me']);
		$Type=Insec($_POST['Type']);
		if($Date_Campagne and $Type >0)
		{
			if($Mon_off)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Reputation,Avancement,Pays FROM Officier WHERE ID='$OfficierID'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Reputation=$data['Reputation'];
						$Avancement=$data['Avancement'];
						$Pays=$data['Pays'];
					}
					mysqli_free_result($result);
					unset($data);
				}
			}
			if($OfficierID ==1)
			{
				$Pays_txt="";
				$Avancement=500000;
				$Reputation=500000;
				$Date_Campagne="1945-09-01";
			}
			else
				$Pays_txt="Pays IN (0,'$Pays') AND";
			//Get Vehicules
			$Reputation=sqrt($Reputation)*50;
			if($Type ==13 or $Type ==17) //Loco ou Sub
				$query="SELECT * FROM Cible WHERE ".$Pays_txt." Date <='$Date_Campagne' AND Unit_ok=1 AND Type='$Type' AND Reput <='$Avancement'/500 ORDER BY Reput ASC, HP ASC, Nom ASC";
			elseif($Type ==998) //Wagons
				$query="SELECT * FROM Cible WHERE ".$Pays_txt." Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=4 AND Type<>13 AND Reput <='$Avancement'/1000 ORDER BY Reput ASC, HP ASC, Nom ASC";
			elseif($Type ==999) //Inf
				$query="SELECT * FROM Cible WHERE ".$Pays_txt." Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=3 AND Type NOT IN (4,12,90) AND ((HP <='$Reputation') OR (Reput <='$Avancement'/1000)) ORDER BY Reput ASC, HP ASC, Nom ASC";
			elseif($Type >13 and $Type <19)
			{
				if($Pays ==7 or $Pays ==9)
					$query="SELECT * FROM Cible WHERE ".$Pays_txt." Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Type='$Type' AND ID NOT IN (5005,5006,5007,5008,5009,5022) AND Reput <='$Avancement'/1000 ORDER BY Reput ASC, HP ASC, Nom ASC";
				elseif($Front ==2)
					$query="SELECT * FROM Cible WHERE ".$Pays_txt." Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Type='$Type' AND Reput <='$Avancement'/1000 AND ID<>'5124' ORDER BY Reput ASC, HP ASC, Nom ASC";
				else
					$query="SELECT * FROM Cible WHERE ".$Pays_txt." Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Type='$Type' AND Reput <='$Avancement'/1000 ORDER BY Reput ASC, HP ASC, Nom ASC";
			}
			elseif($Division_Cdt ==$OfficierID)
				$query="SELECT * FROM Cible WHERE ".$Pays_txt." Date <='$Date_Campagne' AND Unit_ok=1 AND Type='$Type' AND mobile NOT IN (4,5) AND ((HP <='$Reputation') OR (Reput <='$Avancement'/1000)) ORDER BY Reput ASC, HP ASC, Nom ASC";
			else
				$query="SELECT * FROM Cible WHERE ".$Pays_txt." Date <='$Date_Campagne' AND Unit_ok=1 AND Type='$Type' AND mobile NOT IN (4,5) AND Type NOT IN (90,95) AND ((HP <='$Reputation') OR (Reput <='$Avancement'/1000)) ORDER BY Reput ASC, HP ASC, Nom ASC";
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$ID=$data['ID'];
					if($mobile !=4 and $mobile !=5)
					{
						if($Pays ==$country)
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
						}
						else
							$Prod=50;
						if($Prod <50)
						{
							$Reste=0;
							$con=dbconnecti(4);
							$Perdus_indiv=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (401,405,415,605,615) AND Avion='$ID' AND Unit='$Regiment'"),0);
							$Perdus_indiv2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,404,410,420) AND Avion='$ID' AND Unit='$Regiment' AND PlayerID='$OfficierID'"),0);
							/*$Perdus_indiv=mysqli_result(mysqli_query($con,"(SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,402,403,404,405,410,420,605) AND Avion='$ID' AND Unit='$Regiment') 
							UNION (SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN(402,403) AND Pilote_eni='$Regiment' AND Avion='$ID')"),0);*/
							mysqli_close($con);
						}
						else
						{
							$con=dbconnecti();
							//$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$ID'"),0);
							$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$ID'"),0);
							mysqli_close($con);
							$con=dbconnecti(4);
							$Perdus=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$ID'"),0);
							$Perdus2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$ID'"),0);
							if($data['Categorie'] ==5 or $data['Categorie'] ==6)
								$Perdus3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$ID'"),0);
							$Perdus_indiv=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (401,405,415,605,615) AND Avion='$ID' AND Unit='$Regiment'"),0);
							$Perdus_indiv2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,404,410,420) AND Avion='$ID' AND Unit='$Regiment' AND PlayerID='$OfficierID'"),0);
							//UNION (SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN(402,403) AND Avion='$ID' AND Pilote_eni='$Regiment')"),0);
							mysqli_close($con);
							$Reste=$data['Stock']-$Service-$Service2-$Perdus-$Perdus2-$Perdus3+$data['Repare'];
							if($Reste+$Service+$Service2 >$data['Stock'])$Reste=$data['Stock']-$Service-$Service2;
						}
						$Perdus_indiv+=$Perdus_indiv2;
						if(!$data['Production'] or $data['Reput'] <3)$Reste=10;
					}
					if($Perdus_indiv <$data['Production'] or $mobile ==4 or $mobile ==5)
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
						if($data['Arme_Art'] and !$data['Charge'])
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
							if(!$Flak_pr)$data['Portee']=GetData("Armes","ID",$data['Arme_AA'],"Portee");
							$Arme_AA=(GetData("Armes","ID",$data['Arme_AA'],"Degats")/1250)+($data['Flak']*40)+($data['Portee']/500)+$data['Optics']+($data['Arme_AT_mun']/100);
							$AA="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Arme_AA."' aria-valuemin='0' aria-valuemax='100' style='width: ".$Arme_AA."%'></div></div>";
						}
						else
							$Malus_Def+=10;
						/*$Max_Veh=GetMaxVeh($Type,$mobile,$data['Flak'],$Avancement);
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
						/*$HP=$data['HP'];
						$Portee_pr=round($data['Portee']*0.01);
						$Optics_pr=round($data['Optics']*0.1);
						$Fiabilite_pr=$data['Fiabilite']*10;
						$Conso_pr=round($data['Conso']*0.2);
						$Fuel_pr=round($data['Fuel']*0.2);
						$Det_pr=$data['Detection']*2;
						$Taille_pr=round($data['Taille']*0.39);
						$Tactique_pr=$Bonus_Tactique*2;
						if($data['Arme_Inf'])
							$Arme_Inf=GetData("Armes","ID",$data['Arme_Inf'],"Degats");
						if($data['Arme_Art'])
						{
							$Arme_Art=GetData("Armes","ID",$data['Arme_Art'],"Degats");
							$Arme_Art_pr=$Arme_Art*0.004;
							$Arme_Art_muns_pr=round($data['Arme_Art_mun']*0.5);
							$Art="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Arme_Art."' aria-valuemin='0' aria-valuemax='25000' style='width: ".$Arme_Art_pr."%'>Dégâts</div></div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Portee']."' aria-valuemin='0' aria-valuemax='10000' style='width: ".$Portee_pr."%'>Portée</div></div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Optics']."' aria-valuemin='0' aria-valuemax='10' style='width: ".$Bonus_Tir_pr."%; min-width: 20px;'>Tir</div></div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Arme_Art_mun']."' aria-valuemin='0' aria-valuemax='200' style='width: ".$Arme_Art_muns_pr."%; min-width: 20px;'>Muns</div></div>";
						}
						if($data['Arme_AT'])
						{
							$Arme_AT=GetData("Armes","ID",$data['Arme_AT'],"Degats");
							$Arme_AT_Perf=GetData("Armes","ID",$data['Arme_AT'],"Perf");
							$Arme_AT_pr=$Arme_AT*0.004;
							$Arme_AT_Perf_pr=round($Arme_AT_Perf*0.39);
							$AT="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Arme_AT."' aria-valuemin='0' aria-valuemax='25000' style='width: ".$Arme_AT_pr."%'>Dégâts</div></div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Portee']."' aria-valuemin='0' aria-valuemax='10000' style='width: ".$Portee_pr."%'>Portée</div></div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Arme_AT_Perf."' aria-valuemin='0' aria-valuemax='255' style='width: ".$Arme_AT_Perf_pr."%'>Pénétration</div></div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Optics']."' aria-valuemin='0' aria-valuemax='10' style='width: ".$Bonus_Tir_pr."%; min-width: 20px;'>Tir</div></div>
							<br><div class='progress'><div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".$data['Taille']."' aria-valuemin='0' aria-valuemax='255' style='width: ".$Taille_pr."%'>Taille</div></div>";
						}
						if($data['Arme_AA'])
						{
							$Arme_AA=GetData("Armes","ID",$data['Arme_AA'],"Degats");
							$Flak_pr=100*$data['Flak'];
							$Arme_AA_pr=$Arme_AA*0.004;
							$Arme_AA_muns_pr=round($data['Arme_AA_mun']*0.001);
							if(!$Flak_pr)
							{
								$data['Portee']=GetData("Armes","ID",$data['Arme_AA'],"Portee");
								$Portee_pr=round($data['Portee']*0.01);
							}
							$AA="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Flak']."' aria-valuemin='0' aria-valuemax='1' style='width: ".$Flak_pr."%'></div>Couverture</div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Arme_AA."' aria-valuemin='0' aria-valuemax='25000' style='width: ".$Arme_AA_pr."%'></div>Dégâts</div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Portee']."' aria-valuemin='0' aria-valuemax='10000' style='width: ".$Portee_pr."%'></div>Portée</div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Optics']."' aria-valuemin='0' aria-valuemax='10' style='width: ".$Bonus_Tir_pr."%'></div>Tir</div>
							<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Arme_AA_mun']."' aria-valuemin='0' aria-valuemax='10000' style='width: ".$Arme_AA_muns_pr."%'></div>Muns</div>";
						}
						$Blindage_pr=$data['Blindage_f']*0.39;
						$HP_pr=$data['HP']*0.0125;
						$Max_Veh=GetMaxVeh($Type,$mobile,$data['Flak'],$Avancement);
						$HP_Cie=$HP*$Max_Veh;
						$HP_Cie_pr=$HP_Cie*0.002;					
						if($data['Categorie'] ==5 or $data['Categorie'] ==6)$Couv_Ligne=true;
						$Couv_pr=100*$Couv_Ligne;
						$Def="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Couv_Ligne."' aria-valuemin='0' aria-valuemax='1' style='width: ".$Couv_pr."%'></div>Couverture</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['HP']."' aria-valuemin='0' aria-valuemax='8000' style='width: ".$HP_pr."%'></div>Robustesse</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$HP_Cie."' aria-valuemin='0' aria-valuemax='50000' style='width: 40%'>Cie</div></div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Blindage_f']."' aria-valuemin='0' aria-valuemax='255' style='width: ".$Blindage_pr."%'></div>Blindage</div>
						<br><div class='progress'><div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".$data['Taille']."' aria-valuemin='0' aria-valuemax='255' style='width: ".$Taille_pr."%'></div>Taille</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Vitesse']."' aria-valuemin='0' aria-valuemax='100' style='width: ".$data['Vitesse']."%'></div>Vitesse</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Bonus_Tactique."' aria-valuemin='0' aria-valuemax='50' style='width: ".$Tactique_pr."%'></div>Tactique</div>";
						$Off="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$Bonus_Tactique."' aria-valuemin='0' aria-valuemax='50' style='width: ".$Tactique_pr."%'></div>Tactique</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Vitesse']."' aria-valuemin='0' aria-valuemax='100' style='width: ".$data['Vitesse']."%'></div>Vitesse</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Optics']."' aria-valuemin='0' aria-valuemax='10' style='width: ".$Bonus_Tir_pr."%'></div>Tir</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Fiabilite']."' aria-valuemin='-5' aria-valuemax='5' style='width: ".$Fiabilite_pr."%'></div>Fiabilité</div>
						<br><div class='progress'><div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".$data['Conso']."' aria-valuemin='0' aria-valuemax='500' style='width: ".$Conso_pr."%'></div>Conso</div>";
						$Raid="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Fuel']."' aria-valuemin='0' aria-valuemax='500' style='width: ".$Fuel_pr."%'></div>Autonomie</div>
						<br><div class='progress'><div class='progress-bar progress-bar-danger' role='progressbar' aria-valuenow='".$data['Conso']."' aria-valuemin='0' aria-valuemax='500' style='width: ".$Conso_pr."%'></div>Conso</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Fiabilite']."' aria-valuemin='-5' aria-valuemax='5' style='width: ".$Fiabilite_pr."%'></div>Fiabilité</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Vitesse']."' aria-valuemin='0' aria-valuemax='100' style='width: ".$data['Vitesse']."%'></div>Vitesse</div>";
						$Reco="<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Detection']."' aria-valuemin='0' aria-valuemax='50' style='width: ".$Det_pr."%'></div>Détection</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Vitesse']."' aria-valuemin='0' aria-valuemax='100' style='width: ".$data['Vitesse']."%'></div>Vitesse</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Fuel']."' aria-valuemin='0' aria-valuemax='500' style='width: ".$Fuel_pr."%'></div>Autonomie</div>
						<br><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$data['Fiabilite']."' aria-valuemin='-5' aria-valuemax='5' style='width: ".$Fiabilite_pr."%'></div>Fiabilité</div>";*/					
						if($data['Type'] ==99)
							$data['Nom'].=" (Aide à neutraliser les saboteurs)";
						elseif($data['Type'] ==98 or $data['Type'] ==92 or $data['Categorie'] ==16 or $data['Categorie'] ==19)
							$data['Nom'].=" (Minage,déminage,sabotage,réparation)";
						elseif($data['Type'] ==97)
							$data['Nom'].=" (Déplacement doublé dans montagnes et collines)";							
						$Reput=$data['Reput'];
						if($Reste <10 and $mobile !=4 and $mobile !=5)$Reput*=2;
						$vehs.="<tr><td><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'><br>
						<form><input type='button' value='Détail' class='btn btn-primary' onclick=\"window.open('cible.php?cible=".$data['ID']."','Fiche','width=820,height=840,scrollbars=1')\"></form></td>";
						if($data['Premium'] >0 and $data['Premium'] != $Premium)
							$vehs.="<td><div class='i-flex premium20'></div></td>";
						elseif($Reste >3 or $mobile ==4 or $mobile ==5)
						{
							if($data['Type'] == 10 and $Avancement >=25000 and $Reputation >=5000)
								$Cat=true;
							elseif($data['Type'] ==91 and $Avancement >=10000 and $Reputation >=2000)
								$Cat=true;
							elseif($data['Type'] ==7 and $Avancement >=5000 and $Reputation >=2000)
								$Cat=true;
							elseif($data['Type'] ==7 and $Avancement >=5000 and $Reputation >=2000)
								$Cat=true;
							elseif(($data['Type'] ==2 or $data['Type'] ==3 or $data['Type'] ==5 or $data['Type'] ==8 or $data['Type'] ==9) and $Avancement >=5000 and $Reputation >=1000)
								$Cat=true;
							elseif($data['Type'] ==11 and $Avancement >=5000 and $Reputation >=100)
								$Cat=true;
							elseif(($data['Type'] ==1 or $data['Type'] ==4 or $data['Type'] ==6 or $data['Type'] ==12 or $data['Type'] ==93) and $Avancement >=0 and $Reputation >=0)
								$Cat=true;
							elseif($data['Type'] >12 and $data['Type'] <19)
								$Cat=true;
							elseif(!$data['Type'])
								$Cat=true;
							else
								$Cat=false;
							if($Cat)
								$vehs.="<td><span class='btn btn-default'>".$Reput." CT</span></td>";
							else
								$vehs.="<td><span title='Votre officier manque de réputation pour accéder à cette catégorie'>Catégorie inaccessible</span></td>";
						}
						elseif($Prod <50)
							$vehs.="<td><span title='Votre nation doit réparer les usines détruites'>Usines ".$Prod."%</span></td>";
						else
							$vehs.="<td><span title='Votre nation doit réparer les modèles détruits'>".$Reste." Dispo</span></td>";
						$vehs.="<td>".$AT."</td>";
						$vehs.="<td>".$AA."</td>";
						$vehs.="<td>".$Art."</td>";
						$vehs.="<td>".$Def."</td>
						<td>".$Off."</td>
						<td>".$Raid."</td>
						<td>".$Reco."</td></tr>";
					}
				}
				mysqli_free_result($result);
				unset($data);
			}
			$titre="Hangar";		
			if($vehs)
			{
				$titre_up="<thead><tr>
						<th width='10%'>Matériel</th>
						<th width='5%'>Coût</th>
						<th width='10%'>Anti-Tank</th>
						<th width='10%'>Anti-aérien</th>
						<th width='10%'>Bombardement</th>
						<th width='10%'>Défensif</th>
						<th width='10%'>Offensif</th>
						<th width='10%'>Raid</th>
						<th width='10%'>Reco</th></tr></thead>";		
				$mes="<h2>Matériel disponible <a href='#' class='popup'><img src='images/help.png'><span>Changer de matériel réinitialise l expérience.</span></a></h2>
				<div style='overflow:auto; height: 600px;'><table class='table table-striped'>".$titre_up.$vehs."</table></div>";
			}
			else
				$mes="Aucun véhicule de cette catégorie n'est disponible";
			include_once('./default.php');
		}
	}
}
?>