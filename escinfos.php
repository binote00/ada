<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	RetireCandidat($PlayerID,"escinfos");
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");	
	if(!$MIA and $_SESSION['Distance'] ==0)
	{
		$Atelier='';
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Pays,Avancement,Front FROM Pilote WHERE ID='$PlayerID'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Front=$data['Front'];
				$Pays_Origine=$data['Pays'];
				$Avancement=$data['Avancement'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		//$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Type,Reputation,Base,Station_Meteo,Commandant,Officier_Adjoint,Officier_Technique,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,
		Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10,Avion1_Bombe,Avion2_Bombe,Avion3_Bombe,Avion1_Bombe_Nbr,Avion2_Bombe_Nbr,Avion3_Bombe_Nbr,
		Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,Avion1_Mun1,Avion2_Mun1,Avion3_Mun1,
		U_Chargeurs,U_Graisse,U_Purge,U_Moteurs,U_Blindage,U_Camo,Porte_avions,
		Bombes_50,Bombes_125,Bombes_250,Bombes_300,Bombes_400,Bombes_500,Bombes_800,Bombes_1000,Bombes_2000,Avion1_BombeT,Avion2_BombeT,Avion3_BombeT FROM Unit WHERE ID='$Unite'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite_Nom=$data['Nom'];
				$Unite_Type=$data['Type'];
				$Reputation=$data['Reputation'];
				$Base=$data['Base'];
				$Station_Meteo=$data['Station_Meteo'];
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Officier_Technique=$data['Officier_Technique'];
				$Avion1=$data['Avion1'];
				$Avion2=$data['Avion2'];
				$Avion3=$data['Avion3'];
				$Avion1_nbr=$data['Avion1_Nbr'];
				$Avion2_nbr=$data['Avion2_Nbr'];
				$Avion3_nbr=$data['Avion3_Nbr'];
				$Pers1=$data['Pers1'];
				$Pers2=$data['Pers2'];
				$Pers3=$data['Pers3'];
				$Pers4=$data['Pers4'];
				$Pers5=$data['Pers5'];
				$Pers6=$data['Pers6'];
				$Pers7=$data['Pers7'];
				$Pers8=$data['Pers8'];
				$Pers9=$data['Pers9'];
				$Pers10=$data['Pers10'];
				$Stock_Essence_87=$data['Stock_Essence_87'];
				$Stock_Essence_100=$data['Stock_Essence_100'];
				$Stock_Essence_1=$data['Stock_Essence_1'];
				$Stock_Munitions_8=$data['Stock_Munitions_8'];
				$Stock_Munitions_13=$data['Stock_Munitions_13'];
				$Stock_Munitions_20=$data['Stock_Munitions_20'];
				$Stock_Munitions_30=$data['Stock_Munitions_30'];
				$Bombes_50=$data['Bombes_50'];
				$Bombes_125=$data['Bombes_125'];
				$Bombes_250=$data['Bombes_250'];
				$Bombes_300=$data['Bombes_300'];
				$Bombes_400=$data['Bombes_400'];
				$Bombes_500=$data['Bombes_500'];
				$Bombes_800=$data['Bombes_800'];
				$Bombes_1000=$data['Bombes_1000'];
				$Bombes_2000=$data['Bombes_2000'];
				$Avion1_BombeT=$data['Avion1_BombeT'];
				$Avion2_BombeT=$data['Avion2_BombeT'];
				$Avion3_BombeT=$data['Avion3_BombeT'];
				$Avion1_Bombe=$data['Avion1_Bombe'];
				$Avion2_Bombe=$data['Avion2_Bombe'];
				$Avion3_Bombe=$data['Avion3_Bombe'];
				$Avion1_Bombes=$data['Avion1_Bombe_Nbr'];
				$Avion2_Bombes=$data['Avion2_Bombe_Nbr'];
				$Avion3_Bombes=$data['Avion3_Bombe_Nbr'];
				$Avion1_mun=$data['Avion1_Mun1'];
				$Avion2_mun=$data['Avion2_Mun1'];
				$Avion3_mun=$data['Avion3_Mun1'];
				$Chargeurs=$data['U_Chargeurs'];
				$Graisse=$data['U_Graisse'];
				$Purge=$data['U_Purge'];
				$U_Moteurs=$data['U_Moteurs'];
				$U_Blindage=$data['U_Blindage'];
				$U_Camo=$data['U_Camo'];
				$Porte_avions=$data['Porte_avions'];
			}
			mysqli_free_result($result);
			unset($data);
		}						
		//$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,BaseAerienne,Camouflage,QualitePiste,Tour,Zone,LongPiste,Port,Plage FROM Lieu WHERE ID='$Base'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Base_nom=$data['Nom'];
				$Camouflage=$data['Camouflage'];
				$BaseAerienne=$data['BaseAerienne'];
				$QualitePiste=$data['QualitePiste'];
				$LongPiste=$data['LongPiste'];
				$Tour=$data['Tour'];
				$Zone=$data['Zone'];
				$Port=$data['Port'];
				$Plage=$data['Plage'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($Porte_avions >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Taille,HP FROM Cible WHERE ID='$Porte_avions'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Nom_PA=$data['Nom'];
					$LongPiste=$data['Taille'];
					$HP_max_PA=$data['HP'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			$HP_PA=GetData("Regiment_IA","Vehicule_ID",$Porte_avions,"HP");
			//if(!$HP_PA)$HP_PA=GetData("Regiment","Vehicule_ID",$Porte_avions,"HP");
			$QualitePiste=round(($HP_PA/$HP_max_PA)*100);
			$Piste_img="vehicules/vehicule".$Porte_avions.".gif";
			$terrain="Le pont du ".$Nom_PA;
		}
		elseif($BaseAerienne ==3)
		{
			$terrain="Le terrain";
			if($Zone ==8)
				$Piste_img="piste38_".GetQualitePiste_img($QualitePiste).".jpg";
			if($Zone ==0 or $Zone ==2 or $Zone ==3 or $Zone ==9 or $Zone ==11)
				$Piste_img="piste32_".GetQualitePiste_img($QualitePiste).".jpg";
			else
				$Piste_img="piste31_".GetQualitePiste_img($QualitePiste).".jpg";
		}
		elseif($BaseAerienne ==2 or $BaseAerienne ==4)
		{
			$terrain="La piste et son bassin pour hydravion";
			$Piste_img="piste".$BaseAerienne."_".GetQualitePiste_img($QualitePiste).".jpg";
		}
		elseif($BaseAerienne ==1)
		{
			$terrain="La piste";
			$Piste_img="piste".$BaseAerienne."_".GetQualitePiste_img($QualitePiste).".jpg";
		}
		elseif($Port or $Plage)
		{
			$terrain="Le bassin";
			$Piste_img="hydravion.png";
		}
		//Hydra 
		if($Unite_Type ==9)
		{
			if($BaseAerienne ==2)
				$Piste_txt="Le bassin permet le décollage des hydravions. ";
			elseif($Port)
				$Piste_txt="Les infrastructures portuaires permettent le décollage des hydravions. ";
			elseif($Plage)
				$Piste_txt="La plage permet le décollage des hydravions. ";
			else
				$Piste_txt="Le décollage des hydravions n'est pas possible sur cette base! ";
		}
		$LongPiste*=($QualitePiste/100);
		if($BaseAerienne)
		{
			if($QualitePiste <100)
				$Piste_txt.=$terrain.' de votre base est endommagé. '.$terrain.' est praticable à '.$QualitePiste.'% sur une longueur de '.$LongPiste.'m. Etes vous certain de vouloir partir en mission ?';
			else
				$Piste_txt.=$terrain.' de votre base,long de '.$LongPiste.'m,est en parfait état pour un décollage';
		}
		if(!$Graisse)
			$Atelier.="<img src='images/graisse0.gif' alt='Armes dégraissées' title='Armes dégraissées'>";
		else
			$Atelier.="<img src='images/graisse1.gif' alt='Armes graissées' title='Armes graissées'>";
		if($Purge)
			$Atelier.="<br><img src='images/flaps.gif' alt='Circuits purgés' title='Circuits purgés'>";
		if($U_Moteurs)
			$Atelier.="<br><img src='images/moteur.gif' alt='Moteurs réglés' title='Moteurs réglés'>";
		if($U_Blindage)
			$Atelier.="<br><img src='images/blindage.gif' alt='Blindage partiel' title='Blindage partiel'>";
		if($U_Camo)
			$Atelier.="<br><img src='images/camo.gif' alt='Camouflage temporaire' title='Camouflage temporaire'>";
		if($Camouflage >100)
		{
			$Camouflage=100;
			$Camouflage_txt="total";
		}
		elseif($Camouflage >80)
			$Camouflage_txt="supérieur";
		elseif($Camouflage >60)
			$Camouflage_txt="amélioré";
		elseif($Camouflage >40)
			$Camouflage_txt="avancé";
		elseif($Camouflage >20)
			$Camouflage_txt="classique";
		elseif($Camouflage >10)
			$Camouflage_txt="basique";
		else
			$Camouflage_txt="inexistant";
		if($Station_Meteo >10)
		{
			$Station_Meteo=10;
			$Station_Meteo_txt="Hi-Tech";
		}
		elseif($Station_Meteo >8)
			$Station_Meteo_txt="A la pointe";
		elseif($Station_Meteo >6)
			$Station_Meteo_txt="Perfectionnée";
		elseif($Station_Meteo >4)
			$Station_Meteo_txt="Améliorée";
		elseif($Station_Meteo >2)
			$Station_Meteo_txt="Standard";
		elseif($Station_Meteo >1)
			$Station_Meteo_txt="Elémentaire";
		elseif($Station_Meteo >0)
			$Station_Meteo_txt="Embryonnaire";
		else
			$Station_Meteo_txt="Inexistante";
		//Acces Officier
		$Acces_officier=false;
		if($Avancement >9999 or $PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique)
			$Acces_officier=true;
		if($Avancement >24999 or $PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique)
			$Acces_Staff=true;
		//Infos Avions
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,ArmePrincipale,ArmeSecondaire,Arme1_Nbr,Arme2_Nbr,Arme3_Nbr,Arme4_Nbr,Arme5_Nbr,Arme6_Nbr,Engine FROM Avion WHERE ID='$Avion1'");
			$result2=mysqli_query($con,"SELECT Nom,ArmePrincipale,ArmeSecondaire,Arme1_Nbr,Arme2_Nbr,Arme3_Nbr,Arme4_Nbr,Arme5_Nbr,Arme6_Nbr,Engine FROM Avion WHERE ID='$Avion2'");
			$result3=mysqli_query($con,"SELECT Nom,ArmePrincipale,ArmeSecondaire,Arme1_Nbr,Arme2_Nbr,Arme3_Nbr,Arme4_Nbr,Arme5_Nbr,Arme6_Nbr,Engine FROM Avion WHERE ID='$Avion3'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Avion1_nom=$data['Nom'];
					$Avion1_Arme1=$data['ArmePrincipale'];
					$Avion1_Arme2=$data['ArmeSecondaire'];
					$Avion1_Arme1_Nbr=$data['Arme1_Nbr'];
					$Avion1_Arme2_Nbr=$data['Arme2_Nbr'];
					$Avion1_Arme3_Nbr=$data['Arme3_Nbr'];
					$Avion1_Arme4_Nbr=$data['Arme4_Nbr'];
					$Avion1_Arme5_Nbr=$data['Arme5_Nbr'];
					$Avion1_Arme6_Nbr=$data['Arme6_Nbr'];
					$Avion1_Engine=$data['Engine'];
					$Tourelle1_Mun=($Avion1_Arme3_Nbr + $Avion1_Arme4_Nbr + $Avion1_Arme5_Nbr + $Avion1_Arme6_Nbr)*(500*$Chargeurs);
				}
				mysqli_free_result($result);
			}		
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Avion2_nom=$data['Nom'];
					$Avion2_Arme1=$data['ArmePrincipale'];
					$Avion2_Arme2=$data['ArmeSecondaire'];
					$Avion2_Arme1_Nbr=$data['Arme1_Nbr'];
					$Avion2_Arme2_Nbr=$data['Arme2_Nbr'];
					$Avion2_Arme3_Nbr=$data['Arme3_Nbr'];
					$Avion2_Arme4_Nbr=$data['Arme4_Nbr'];
					$Avion2_Arme5_Nbr=$data['Arme5_Nbr'];
					$Avion2_Arme6_Nbr=$data['Arme6_Nbr'];
					$Avion2_Engine=$data['Engine'];
					$Tourelle2_Mun=($Avion2_Arme3_Nbr + $Avion2_Arme4_Nbr + $Avion2_Arme5_Nbr + $Avion2_Arme6_Nbr)*(500*$Chargeurs);
				}
				mysqli_free_result($result2);
			}
			if($result3)
			{
				while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					$Avion3_nom=$data['Nom'];
					$Avion3_Arme1=$data['ArmePrincipale'];
					$Avion3_Arme2=$data['ArmeSecondaire'];
					$Avion3_Arme1_Nbr=$data['Arme1_Nbr'];
					$Avion3_Arme2_Nbr=$data['Arme2_Nbr'];
					$Avion3_Arme3_Nbr=$data['Arme3_Nbr'];
					$Avion3_Arme4_Nbr=$data['Arme4_Nbr'];
					$Avion3_Arme5_Nbr=$data['Arme5_Nbr'];
					$Avion3_Arme6_Nbr=$data['Arme6_Nbr'];
					$Avion3_Engine=$data['Engine'];
					$Tourelle3_Mun=($Avion2_Arme3_Nbr + $Avion2_Arme4_Nbr + $Avion2_Arme5_Nbr + $Avion2_Arme6_Nbr)*(500*$Chargeurs);
				}
				mysqli_free_result($result3);
			}
			unset($data);
			//$Avion1_Arme1_txt=$Avion1_Arme1_Nbr.' '.GetData("Armes","ID",$Avion1_Arme1,"Nom").'<br>('.substr(GetData("Armes","ID",$Avion1_Arme1,"Calibre"),0,3).'mm)';
			if($Avion1_Arme2)
				$Avion1_Arme2_txt=$Avion1_Arme2_Nbr.' '.GetData("Armes","ID",$Avion1_Arme2,"Nom").'<br>('.substr(GetData("Armes","ID",$Avion1_Arme2,"Calibre"),0,3).'mm '.GetMun_txt($Avion1_mun).')';
			else
				$Avion1_Arme2_txt="Aucun";
			if($Avion1_Arme3_Nbr or $Avion1_Arme4_Nbr or $Avion1_Arme5_Nbr or $Avion1_Arme6_Nbr)
				$Avion1_Arme3_txt=$Tourelle1_Mun.' ('.GetMun_txt($Avion1_mun).')';
			else
				$Avion1_Arme3_txt="Aucun";			
			/*if(!$Avion1_Bombes)
			{
				$Avion1_Bombe=GetData("Avion","ID",$Avion1,"Bombe");
				$Avion1_Bombes=GetData("Avion","ID",$Avion1,"Bombe_Nbr");
			}*/
			if($Avion1_Bombes)
			{
				if($Avion1_Bombe ==800)
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_torpille.png'><span>".$Avion1_Bombes." Torpille(s)</span></a>";
				elseif($Avion1_Bombe ==300)
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion1_Bombes." Charge(s) ASM</span></a>";
				elseif($Avion1_Bombe ==350)
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion1_Bombes." Réservoir externe</span></a>";
				elseif($Avion1_Bombe ==400)
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion1_Bombes." Mine(s)</span></a>";
				elseif($Avion1_Bombe ==25)
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion1_Bombes." Caméra portable</span></a>";
				elseif($Avion1_Bombe ==26)
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion1_Bombes." Caméra fixe</span></a>";
				elseif($Avion1_Bombe ==27)
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion1_Bombes." Caméra haute</span></a>";
				elseif($Avion1_Bombe ==30)
					$Avion1_Bombes_txt=$Avion1_Bombes." Fusée(s)";
				elseif($Avion1_Bombe ==80)
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_rocket.png'><span>".$Avion1_Bombes." Rocket(s)</span></a>";
				elseif($Avion1_Bombe ==50000)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 8mm';
				elseif($Avion1_Bombe ==15000)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 13mm';
				elseif($Avion1_Bombe ==5000)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 20mm';
				elseif($Avion1_Bombe ==3000)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 30mm';
				elseif($Avion1_Bombe ==1500)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) de 40mm';
				elseif($Avion1_Bombe ==1200)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) d\'Octane 87';
				elseif($Avion1_Bombe ==1100)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Cargaison(s) d\'Octane 100';
				elseif($Avion1_Bombe ==100)
					$Avion1_Bombes_txt=$Avion1_Bombes.' Parachutistes';
				else
				{
					$Avion1_Bombes_txt=$Avion1_Bombes." x ".$Avion1_Bombe."kg";
					if($Avion1_BombeT)
						$Avion1_Bombes_txt.=' ('.GetBombeType($Avion1_BombeT).')';
					$Avion1_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion1_Bombes_txt."</span></a>";
				}
			}
			else
				$Avion1_Bombes_txt="Vide";			
			//$Avion2_Arme1_txt=$Avion2_Arme1_Nbr.' '.GetData("Armes","ID",$Avion2_Arme1,"Nom").'<br>('.substr(GetData("Armes", "ID", $Avion2_Arme1, "Calibre"),0,3).'mm)';
			if($Avion2_Arme2)
				$Avion2_Arme2_txt=$Avion2_Arme2_Nbr.' '.GetData("Armes","ID",$Avion2_Arme2,"Nom").'<br>('.substr(GetData("Armes", "ID", $Avion2_Arme2, "Calibre"),0,3).'mm '.GetMun_txt($Avion2_mun).')';
			else
				$Avion2_Arme2_txt="Aucun";
			if($Avion2_Arme3_Nbr or $Avion2_Arme4_Nbr or $Avion2_Arme5_Nbr or $Avion2_Arme6_Nbr)
				$Avion2_Arme3_txt=$Tourelle2_Mun.' ('.GetMun_txt($Avion2_mun).')';
			else
				$Avion2_Arme3_txt="Aucun";	
			/*if(!$Avion2_Bombes)
			{
				$Avion2_Bombe=GetData("Avion","ID",$Avion2,"Bombe");
				$Avion2_Bombes=GetData("Avion","ID",$Avion2,"Bombe_Nbr");
			}*/
			if($Avion2_Bombes)
			{
				if($Avion2_Bombe ==800)
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_torpille.png'><span>".$Avion2_Bombes." Torpille(s)</span></a>";
				elseif($Avion2_Bombe ==300)
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion2_Bombes." Charge(s) ASM</span></a>";
				elseif($Avion2_Bombe ==350)
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion2_Bombes." Réservoir externe</span></a>";
				elseif($Avion2_Bombe ==400)
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion2_Bombes." Mine(s)</span></a>";
				elseif($Avion2_Bombe ==25)
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion2_Bombes." Caméra portable</span></a>";
				elseif($Avion2_Bombe ==26)
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion2_Bombes." Caméra fixe</span></a>";
				elseif($Avion2_Bombe ==27)
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion2_Bombes." Caméra haute</span></a>";
				elseif($Avion2_Bombe ==30)
					$Avion2_Bombes_txt=$Avion2_Bombes." Fusée(s)";
				elseif($Avion2_Bombe ==80)
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_rocket.png'><span>".$Avion2_Bombes." Rocket(s)</span></a>";
				elseif($Avion2_Bombe ==50000)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 8mm';
				elseif($Avion2_Bombe ==15000)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 13mm';
				elseif($Avion2_Bombe ==5000)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 20mm';
				elseif($Avion2_Bombe ==3000)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 30mm';
				elseif($Avion2_Bombe ==1500)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) de 40mm';
				elseif($Avion2_Bombe ==1200)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) d\'Octane 87';
				elseif($Avion2_Bombe ==1100)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Cargaison(s) d\'Octane 100';
				elseif($Avion2_Bombe ==100)
					$Avion2_Bombes_txt=$Avion2_Bombes.' Parachutistes';
				else
				{
					$Avion2_Bombes_txt=$Avion2_Bombes." x ".$Avion2_Bombe."kg";
					if($Avion2_BombeT)
						$Avion2_Bombes_txt.=' ('.GetBombeType($Avion2_BombeT).')';
					$Avion2_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion2_Bombes_txt."</span></a>";
				}
			}
			else
				$Avion2_Bombes_txt="Vide";			
			//$Avion3_Arme1_txt=$Avion3_Arme1_Nbr.' '.GetData("Armes","ID",$Avion3_Arme1,"Nom").'<br>('.substr(GetData("Armes", "ID", $Avion3_Arme1, "Calibre"),0,3).'mm)';
			if($Avion3_Arme2)
				$Avion3_Arme2_txt=$Avion3_Arme2_Nbr.' '.GetData("Armes","ID",$Avion3_Arme2,"Nom").'<br>('.substr(GetData("Armes", "ID", $Avion3_Arme2, "Calibre"),0,3).'mm '.GetMun_txt($Avion3_mun).')';
			else
				$Avion3_Arme2_txt="Aucun";
			if($Avion3_Arme3_Nbr or $Avion3_Arme4_Nbr or $Avion3_Arme5_Nbr or $Avion3_Arme6_Nbr)
				$Avion3_Arme3_txt=$Tourelle3_Mun.' ('.GetMun_txt($Avion3_mun).')';
			else
				$Avion3_Arme3_txt="Aucun";	
			/*if(!$Avion3_Bombes)
			{
				$Avion3_Bombe=GetData("Avion","ID",$Avion3,"Bombe");
				$Avion3_Bombes=GetData("Avion","ID",$Avion3,"Bombe_Nbr");
			}*/
			if($Avion3_Bombes)
			{
				if($Avion3_Bombe ==800)
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_torpille.png'><span>".$Avion3_Bombes." Torpille(s)</span></a>";
				elseif($Avion3_Bombe ==300)
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion3_Bombes." Charge(s) ASM</span></a>";
				elseif($Avion3_Bombe ==350)
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion3_Bombes." Réservoir externe</span></a>";
				elseif($Avion3_Bombe ==400)
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion3_Bombes." Mine(s)</span></a>";
				elseif($Avion3_Bombe ==25)
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion3_Bombes." Caméra portable</span></a>";
				elseif($Avion3_Bombe ==26)
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion3_Bombes." Caméra fixe</span></a>";
				elseif($Avion3_Bombe ==27)
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_camera.png'><span>".$Avion3_Bombes." Caméra haute</span></a>";
				elseif($Avion3_Bombe ==30)
					$Avion3_Bombes_txt=$Avion3_Bombes." Fusée(s)";
				elseif($Avion3_Bombe ==80)
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_rocket.png'><span>".$Avion3_Bombes." Rocket(s)</span></a>";
				elseif($Avion3_Bombe ==50000)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 8mm';
				elseif($Avion3_Bombe ==15000)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 13mm';
				elseif($Avion3_Bombe ==5000)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 20mm';
				elseif($Avion3_Bombe ==3000)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 30mm';
				elseif($Avion3_Bombe ==1500)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) de 40mm';
				elseif($Avion3_Bombe ==1200)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) d\'Octane 87';
				elseif($Avion3_Bombe ==1100)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Cargaison(s) d\'Octane 100';
				elseif($Avion3_Bombe ==100)
					$Avion3_Bombes_txt=$Avion3_Bombes.' Parachutistes';
				else
				{
					$Avion3_Bombes_txt=$Avion3_Bombes." x ".$Avion3_Bombe."kg";
					if($Avion3_BombeT)
						$Avion3_Bombes_txt.=' ('.GetBombeType($Avion3_BombeT).')';
					$Avion3_Bombes_txt="<a href='#' class='popup'><img src='images/icon_bomb.png'><span>".$Avion3_Bombes_txt."</span>";
				}
			}
			else
				$Avion3_Bombes_txt="Vide";
		$Sqn=GetSqn($country);		
		$Pers=floor($Reputation/20000)+1;
		if($Pers>10)$Pers=10;
		if($Unite_Type !=8)
			include_once('./menu_escadrille.php');
		else
			echo '<h1>'.$Unite_Nom.'</h1>';
		echo "<div class='row'><div class='col-md-8'>".Afficher_Icone($Unite,$country,$Unite_Nom,1)."</div><div class='col-md-4'><table class='table'><thead><tr><th colspan='2'>Réputation</th></tr></thead>
		<tr><td><a href='#' class='popup'>".$Reputation."<span>Plus le niveau de réputation est élevé, plus votre staff peut recruter de personnel spécialisé et commander des avions derniers modèles pour ses pilotes</span></a></td><td><form action='index.php?view=esc_reput' method='post'><input type='hidden' name='unit' value='".$Unite."'><input type='submit' class='btn btn-sm btn-info' value='Détail'></form></td></tr></table></div></div>";
	/*?><h2>Staff</h2>
	<table class='table'> 
		<thead><tr>
			<th width="30%"><?echo GetStaff($country,1);?> <a href='#' class='popup'><img src='images/help.png'><span>Ce poste est accessible aux unités de chasse possédant au moins 6 pilotes actifs (aucune restriction pour les autres unités)</span></a></th>
			<th width="30%"><?echo GetStaff($country,2);?> <a href='#' class='popup'><img src='images/help.png'><span>Ce poste est accessible aux unités de chasse possédant au moins 3 pilotes actifs (aucune restriction pour les autres unités)</span></a></th>
			<th width="30%"><?echo GetStaff($country,3);?> <a href='#' class='popup'><img src='images/help.png'><span>Ce poste est accessible aux unités de chasse possédant au moins 3 pilotes actifs (aucune restriction pour les autres unités)</span></a></th>
		</tr></thead>
		<tr>
			<td title="Si un poste est à pourvoir, vous pouvez postuler"><?if($Commandant){$Av1=GetAvancement(GetData("Joueur","ID",$Commandant,"Avancement"),$country); echo $Av1[0].' '.GetData("Joueur","ID",$Commandant,"Nom");}else{echo "Poste vacant";}?></td>
			<td title="Si un poste est à pourvoir, vous pouvez postuler"><?if($Officier_Adjoint){$Av2=GetAvancement(GetData("Joueur","ID",$Officier_Adjoint,"Avancement"),$country); echo $Av2[0].' '.GetData("Joueur","ID",$Officier_Adjoint,"Nom");}else{echo "Poste vacant";}?></td>
			<td title="Si un poste est à pourvoir, vous pouvez postuler"><?if($Officier_Technique){$Av3=GetAvancement(GetData("Joueur","ID",$Officier_Technique,"Avancement"),$country); echo $Av3[0].' '.GetData("Joueur","ID",$Officier_Technique,"Nom");}else{echo "Poste vacant";}?></td>
		</tr>
	<?$Postuler=false;
	if($Unite_Type !=8)
	{
		if($Pays_Origine ==$country)
			$Postuler=true;
		elseif($Pays_Origine ==3 and $country ==2)
			$Postuler=true;
		elseif($Pays_Origine ==4 and ($Unite == 395 or $Unite == 396 or $Unite == 553 or $Unite == 734 or $PlayerID == 793))
			$Postuler=true;
	}
	elseif($Pays_Origine ==3 and ($Unite ==999 or $Unite ==999)) //349 & 350 Sqn RAF
		$Postuler=true;
	if($Avancement >499 and $Postuler ==true)
	{
		if($Unite_Type ==1 or $Unite_Type ==4 or $Unite_Type ==12)
		{
			$con=dbconnecti();
			$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Unit='$Unite' AND Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()"),0);
			mysqli_close($con);
			if($Pilotes >2)
			{
				if($Unite_Type ==4 or $country ==3 or $country ==6 or $country ==4) //Aide petites factions
					$Pilotes=6;
			}
		}
		else
			$Pilotes=6;?>
		<tr><td><?if($PlayerID !=$Commandant){ if($Pilotes >5 and $Dispo_EM ==1){echo "<span class='btn btn-default'><a href='postuler_staff.php?poste=1' title='Postuler'>Postuler</a>";}else{echo "<span class='btn btn-danger'>Non Disponible";}}else{echo "<span class='btn btn-warning'><a href='postuler_staff.php?poste=4' title='Démissionner'>Démissionner</a>";}?></span></td>
			<td><?if($PlayerID !=$Officier_Adjoint){ if($Pilotes >2 and $Dispo_EM ==1){echo "<span class='btn btn-default'><a href='postuler_staff.php?poste=2' title='Postuler'>Postuler</a>";}else{echo "<span class='btn btn-danger'>Non Disponible";}}else{echo "<span class='btn btn-warning'><a href='postuler_staff.php?poste=4' title='Démissionner'>Démissionner</a>";}?></span></td>
			<td><?if($PlayerID !=$Officier_Technique){ if($Pilotes >2){echo "<span class='btn btn-default'><a href='postuler_staff.php?poste=3' title='Postuler'>Postuler</a>";}else{echo "<span class='btn btn-danger'>Non Disponible";}}else{echo "<span class='btn btn-warning'><a href='postuler_staff.php?poste=4' title='Démissionner'>Démissionner</a>";}?></span></td>
		</tr>
	<?}?></table><?*/?>
	<h2><?echo $Base_nom;?></h2>
	<table class='table'>
        <thead><tr>                                                                           
            <th width="16%">D.C.A <a href='#' class='popup'><img src='images/help.png'><span>La DCA protège la base contre les attaques aériennes</span></a></th>                                                           
            <th width="16%">Camouflage</th>                         
            <th width="16%">Etat de la piste</th>                                 
            <th width="16%">Etat de la tour</th>                                 
            <th width="16%">Station Météo <a href='#' class='popup'><img src='images/help.png'><span>Le niveau de la station météo augmente la précision des prévisions météo</span></a></th>                                                                                                                                                                                                                                    
            <th width="16%">Personnel spécialisé <a href='#' class='popup'><img src='images/help.png'><span>Plus la réputation de l'unité est élevée, plus vous pourrez assigner de personnel spécialisé. Référez-vous au menu Personnel de l'escadrille pour plus d'informations</span></a></th>                                                                                                                                                                                                                                    
		</tr></thead>
		<tr>
			<td title="Seuls les officiers ont accès à cette information"><?if($Acces_officier){echo "<a title='Retour à votre campement' href='esc_infodca.php?Unite=".$Unite."' target='_blank'><img src='images/vehicules/vehicule16.gif' title='Détail de la DCA'></a>";}else{echo "Inconnu";}?></td>
			<td><a href='#' class='popup'><img src='images/cam<?echo $Camouflage;?>.jpg'><span><b>Camouflage <?echo $Camouflage_txt;?></b><br>Le camouflage diminue les chances de repérer votre base. Chaque attaque réussie sur votre base diminue le camouflage</span></a></td>
			<td><a href='#' class='popup'><img src='images/<?echo $Piste_img;?>'><span>Etat <b><?echo $Piste_txt;?>%</b><br>L'état de la piste influence les risques d'accident au décollage et à l'atterrissage. Réparez la piste dès qu'elle est endommagée!</span></a></td>
			<td><a href='#' class='popup'><img src='images/vehicules/vehicule2.gif'><span>Etat <b><?echo $Tour;?>%</b><br>La tour permet le transfert d'unités sur cette base, l'utilisation des pompiers et diminue le risque d'accidents au décollage et à l'atterrissage</span></a></td>
			<td title="Seuls les officiers ont accès à cette information"><?if($Acces_officier){echo $Station_Meteo_txt.' ('.$Station_Meteo.')';}else{echo "Inconnu";}?></td>
			<td>
			<?
			if($Pers)
			{ 
				for($p=1;$p<=$Pers;$p++)
				{
					$Pers_img='Pers'.$p;
			?>
					<a href='#' class='popup'><img src='images/pers_<?echo $$Pers_img;?>.gif'><span><?echo GetPers_txt($$Pers_img);?></span></a>
			<?	}
			}else{echo "Standard";}?>
			</td>
		</tr>
	</table>
	<h2>Hangar</h2>
	<div style='overflow:auto; width: 100%;'>
	<table class='table table-striped'>
		<thead><tr>                                   
			<th width="10%">Escadrille</th>                                                                         
			<th width="20%">Avions</th>                                                 
			<th width="15%">Armes Principales <a href='#' class='popup'><img src='images/help.png'><span>Cette arme est commandée par le pilote. En mission de bombardement cette arme permet de réduire la riposte de la DCA</span></a></th>			
			<th width="15%">Armes Secondaires <a href='#' class='popup'><img src='images/help.png'><span>Cette arme est commandée par le pilote. Elle peut être utilisée en combat aérien, en attaque au sol ou en attaque navale</span></a></th>
			<th width="9%">Mitrailleurs <a href='#' class='popup'><img src='images/help.png'><span>Cette arme est contrôlée par le membre d'équipage. Elle est utilisée exclusivement en combat aérien défensif</span></a></th>
			<th width="8%">Carburant</th>			
			<th width="10%">Soute <a href='#' class='popup'><img src='images/help.png'><span>Le poids des charges transportées augmente la distance de décollage et les risques de crash à l'atterrissage. Référez-vous aux fichiers Aide en mission</span></a></th>	
			<th width="10%">Atelier <a href='#' class='popup'><img src='images/help.png'><span>Options installées depuis l'atelier de l'escadrille. Ces options sont valables pour tous les avions de l'unité.</span></a></th>	
		</tr></thead>
        <tr>                               
            <td><?echo $Sqn;?> 1</td>
			<td title="Seuls les officiers ont accès à cette information"><?if($Acces_officier){echo $Avion1_nbr;}else{echo "Inconnu";}?>
			<?echo GetAvionIcon($Avion1,$country,0,$Unite,$Front);?></td>
			<td><? echo $Avion1_Arme1_Nbr.' '.GetData("Armes","ID",$Avion1_Arme1,"Nom").'<br>('.substr(GetData("Armes","ID",$Avion1_Arme1,"Calibre"),0,3).'mm '.GetMun_txt($Avion1_mun).')';?></td>
			<td><? echo $Avion1_Arme2_txt;?></td>
			<td><? echo $Avion1_Arme3_txt;?></td>
			<td><? echo GetData("Moteur","ID",$Avion1_Engine,"Carburant").' Octane';?></td><td><? echo $Avion1_Bombes_txt;?></td>
			<td rowspan="3"><? echo $Atelier;?></td>
		</tr>	
        <tr>                               
            <td><?echo $Sqn;?> 2</td>
			<td title="Seuls les officiers ont accès à cette information"><?if($Acces_officier){echo $Avion2_nbr;}else{echo "Inconnu";}?>
			<?echo GetAvionIcon($Avion2,$country,0,$Unite,$Front);?></td>
			<td><? echo $Avion2_Arme1_Nbr.' '.GetData("Armes","ID",$Avion2_Arme1,"Nom").'<br>('.substr(GetData("Armes","ID",$Avion2_Arme1,"Calibre"),0,3).'mm '.GetMun_txt($Avion2_mun).')';?></td>
			<td><? echo $Avion2_Arme2_txt;?></td>
			<td><? echo $Avion2_Arme3_txt;?></td>
			<td><? echo GetData("Moteur","ID",$Avion2_Engine,"Carburant").' Octane';?></td><td><? echo $Avion2_Bombes_txt;?></td>
		</tr>	
        <tr>                               
            <td><?echo $Sqn;?> 3</td>
			<td title="Seuls les officiers ont accès à cette information"><?if($Acces_officier){echo $Avion3_nbr;}else{echo "Inconnu";}?>
			<?echo GetAvionIcon($Avion3,$country,0,$Unite,$Front);?></td>
			<td><? echo $Avion3_Arme1_Nbr.' '.GetData("Armes","ID",$Avion3_Arme1,"Nom").'<br>('.substr(GetData("Armes","ID",$Avion3_Arme1,"Calibre"),0,3).'mm '.GetMun_txt($Avion3_mun).')';?></td>
			<td><? echo $Avion3_Arme2_txt;?></td>
			<td><? echo $Avion3_Arme3_txt;?></td>
			<td><? echo GetData("Moteur","ID",$Avion3_Engine,"Carburant").' Octane';?></td><td><? echo $Avion3_Bombes_txt;?></td>
		</tr>
	</table></div>
<?
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>