<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_air_inc.php');
	include_once('./jfv_avions.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./menu_infos.php');
	if(GetData("Joueur","ID",$_SESSION['AccountID'],"Premium"))
	{
		$avion1=Insec($_POST['avion1']);
		$avion2=Insec($_POST['avion2']);
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT * FROM Avion WHERE ID='$avion1'");
		$result2=mysqli_query($con,"SELECT * FROM Avion WHERE ID='$avion2'");
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Type_1=$data['Type'];
				$Pays_1=$data['Pays'];
				$Nom_1=$data['Nom'];
				$Engagement_1=$data['Engagement'];
				$Equipage_1=$data['Equipage'];
				$Engine_1=$data['Engine'];
				$Engine_Nbr_1=$data['Engine_Nbr'];
				$VitesseH_1=$data['VitesseH'];
				$VitesseB_1=$data['VitesseB'];
				$VitesseA_1=$data['VitesseA'];
				$VitesseP_1=$data['VitesseP'];
				$Alt_ref_1=$data['Alt_ref'];
				$Plafond_1=$data['Plafond'];
				$Autonomie_1=$data['Autonomie'];
				$Masse_1=$data['Masse'];
				$Blindage_1=$data['Blindage'];
				$Bombe_1=$data['Bombe'];
				$Bombe_Nbr_1=$data['Bombe_Nbr'];
				$Maniabilite_1=$data['Maniabilite'];
				$ManoeuvreH_1=$data['ManoeuvreH'];
				$ManoeuvreB_1=$data['ManoeuvreB'];
				$Stab_1=$data['Stabilite'];
				$Robustesse_1=$data['Robustesse'];
				$Engine_Power_1=$data['Puissance'];
				$Arme1_1=$data['ArmePrincipale'];
				$Arme1_Nbr_1=$data['Arme1_Nbr'];
				$Arme2_1=$data['ArmeSecondaire'];
				$Arme2_Nbr_1=$data['Arme2_Nbr'];
				$Arme3_1=$data['ArmeArriere'];
				$Arme3_Nbr_1=$data['Arme3_Nbr'];
				$Arme4_1=$data['ArmeSabord'];
				$Arme4_Nbr_1=$data['Arme4_Nbr'];
				$Arme5_1=$data['TourelleSup'];
				$Arme5_Nbr_1=$data['Arme5_Nbr'];
				$Arme6_1=$data['TourelleVentre'];
				$Arme6_Nbr_1=$data['Arme6_Nbr'];
				$Detection_1=$data['Detection']+$data['Radar'];
				$Vis_1=$data['Visibilite'];
				$Radio_1=$data['Radio'];
				$Radar_1=$data['Radar'];
				$Res_1=$data['Reservoir'];
				$Navi_1=$data['Navigation'];
				$Viseur_1=$data['Viseur'];
				$Volets_1=$data['Volets'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($Arme1_1)
		{
			$resultw1=mysqli_query($con,"SELECT * FROM Armes WHERE ID='$Arme1_1'");
			if($resultw1)
			{
				while($dataw1=mysqli_fetch_array($resultw1,MYSQLI_ASSOC))
				{
					$Arme1_nom_1=$dataw1['Nom'];
					$Arme1_cal_1=substr($dataw1['Calibre'],0,3);
					$Arme1_deg_1=$dataw1['Degats'];
					$Arme1_mult_1=$dataw1['Multi'];
				}
				mysqli_free_result($resultw1);
			}
			$Degats_chass_1_1=$Arme1_deg_1*$Arme1_mult_1*$Arme1_Nbr_1;
			$Degats_tot_1=$Degats_chass_1_1;
		}
		if($Arme2_1)
		{
			if($Arme2_1 ==$Arme1_1 and $Arme1_Nbr_1 ==$Arme2_Nbr_1)
            {
                $Arme2_nom_1=$Arme1_nom_1;
                $Arme2_cal_1=$Arme1_cal_1;
                $Degats_tot_1*=2;
            }
            else{
				$resultw2=mysqli_query($con,"SELECT * FROM Armes WHERE ID='$Arme2_1'");
				if($resultw2)
				{
					while($dataw2=mysqli_fetch_array($resultw2,MYSQLI_ASSOC))
					{
						$Arme2_nom_1=$dataw2['Nom'];
						$Arme2_cal_1=substr($dataw2['Calibre'],0,3);
						$Arme2_deg_1=$dataw2['Degats'];
						$Arme2_mult_1=$dataw2['Multi'];
					}
					mysqli_free_result($resultw2);
				}
				$Degats_chass_2_1=$Arme2_deg_1*$Arme2_mult_1*$Arme2_Nbr_1;
				if($Degats_chass_2_1 >$Degats_chass_1_1)$Degats_chass_1_1=$Degats_chass_2_1;
				$Degats_tot_1+=$Degats_chass_2_1;
			}
		}
		if($Engine_1)
		{
			$Carbu_1=GetData("Moteur","ID",$Engine_1,"Carburant");
			$Engine_1=GetData("Moteur","ID",$Engine_1,"Nom");
		}
		$VitesseTH_1=0;	
		$VitesseAB_1=0;
		$Perf_7000_1=0;
		$Perf_9000_1=0;
		$Perf_12000_1=0;
		$Perf_3000_c_1=0;
		$Perf_5000_c_1=0;
		$Perf_7000_c_1=0;
		$Perf_9000_c_1=0;
		$VitesseLOW_1=GetSpeed("Avion",$avion1,500,0);
		$VitesseMLH_1=GetSpeed("Avion",$avion1,3000,0);
		$VitesseMH_1=GetSpeed("Avion",$avion1,5000,0);
		$VitesseA3000_1=GetSpeedA("Avion",$avion1,3000,0,$Engine_Nbr_1);
		$VitesseA5000_1=GetSpeedA("Avion",$avion1,5000,0,$Engine_Nbr_1);
		$PuissanceB_1=GetPuissance("Avion",$avion1,1,$Robustesse_1,1,1,$Engine_Nbr_1);
		$PuissanceLOW_1=GetPuissance("Avion",$avion1,500,$Robustesse_1,1,1,$Engine_Nbr_1);
		$Puissance3000_1=GetPuissance("Avion",$avion1,3000,$Robustesse_1,1,1,$Engine_Nbr_1);
		$PuissanceH_1=GetPuissance("Avion",$avion1,5000,$Robustesse_1,1,1,$Engine_Nbr_1);
		$Perf_500_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,500)+$Maniabilite_1+($VitesseLOW_1*2)-($PuissanceLOW_1/2);
		$Perf_3000_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,3000)+$Maniabilite_1+($VitesseMLH_1*2)-($Puissance3000_1/2);
		$Perf_5000_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,5000)+$Maniabilite_1+($VitesseMH_1*2)-($PuissanceH_1/2);
		$Perf_3000_f_1=$Perf_3000_1-($VitesseMLH_1*2)+($VitesseP_1*2);
		$Perf_5000_f_1=$Perf_5000_1-($VitesseMH_1*2)+($VitesseP_1*2);
		if($VitesseA5000_1 >668 and $VitesseP_1 >659 and ($VitesseMH_1*2) <($VitesseP_1+$VitesseA5000_1))
			$Perf_5000_c_1=$Perf_5000_1-($VitesseMH_1*2)+$VitesseA5000_1+$VitesseP_1;
		if($VitesseA3000_1 >668 and $VitesseP_1 >659 and ($VitesseMLH_1*2) <($VitesseP_1+$VitesseA3000_1))
			$Perf_3000_c_1=$Perf_3000_1-($VitesseMLH_1*2)+$VitesseA3000_1+$VitesseP_1;
		if($Volets_1)
		{
			$Mani_flaps_1=GetMani($Maniabilite_1,1,9999,$moda,1,$flaps_eni);
			$Perf_500_v_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,500,$moda,1,$Volets_1)+$Mani_flaps_1+($VitesseLOW_1*2)-($PuissanceLOW_1/2);
			$Perf_3000_v_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,3000,$moda,1,$Volets_1)+$Mani_flaps_1+($VitesseMLH_1*2)-($Puissance3000_1/2);
			$Perf_5000_v_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,5000,$moda,1,$Volets_1)+$Mani_flaps_1+($VitesseMH_1*2)-($PuissanceH_1/2);
		}
		else
		{
			$Perf_500_v_1=$Perf_500_1;
			$Perf_3000_v_1=$Perf_3000_1;
			$Perf_5000_v_1=$Perf_5000_1;
		}
		if($Plafond_1 >11999)
			$Perf_12000_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,12000)+$Maniabilite_1+(GetSpeed("Avion",$avion1,12000,0)*2)-(GetPuissance("Avion",$avion1,12000,$Robustesse_1,1,1,$Engine_Nbr_1)/2);
		if($Plafond_1 >8999)
		{
			$VitesseTH_1=GetSpeed("Avion",$avion1,9000,0);
			$VitesseAB_1=GetSpeedA("Avion",$avion1,9000,0,$Engine_Nbr_1);
			$PuissanceTH_1=GetPuissance("Avion",$avion1,9000,$Robustesse_1,1,1,$Engine_Nbr_1);
			$Perf_9000_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,9000)+$Maniabilite_1+($VitesseTH_1*2)-($PuissanceTH_1/2);
			$Perf_9000_f_1=$Perf_9000_1-($VitesseTH_1*2)+($VitesseP_1*2);
			if($VitesseAB_1 >668 and $VitesseP_1 >659 and ($VitesseTH_1*2) <($VitesseP_1+$VitesseAB_1))
				$Perf_9000_c_1=$Perf_9000_1-($VitesseTH_1*2)+$VitesseAB_1+$VitesseP_1;
			if($Volets_1)
				$Perf_9000_v_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,9000,$moda,1,$Volets_1)+$Mani_flaps_1+($VitesseTH_1*2)-($PuissanceTH_1/2);
			else
				$Perf_9000_v_1=$Perf_9000_1;
		}
		if($Plafond_1 >6999)
		{
			$Vitesse7000_1=GetSpeed("Avion",$avion1,7000,0);
			$VitesseA7000_1=GetSpeedA("Avion",$avion1,7000,0,$Engine_Nbr_1);
			$Puissance7000_1=GetPuissance("Avion",$avion1,7000,$Robustesse_1,1,1,$Engine_Nbr_1);
			$Perf_7000_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,7000)+$Maniabilite_1+($Vitesse7000_1*2)-(GetPuissance("Avion",$avion1,7000,$Robustesse_1,1,1,$Engine_Nbr_1)/2);
			$Perf_7000_f_1=$Perf_7000_1-($Vitesse7000_1*2)+($VitesseP_1*2);
			if($VitesseA7000_1 >668 and $VitesseP_1 >659 and ($Vitesse7000_1*2) <($VitesseP_1+$VitesseA7000_1))
				$Perf_7000_c_1=$Perf_7000_1-($Vitesse7000_1*2)+$VitesseA7000_1+$VitesseP_1;
			if($Volets_1)
				$Perf_7000_v_1=GetMano($ManoeuvreB_1,$ManoeuvreH_1,9999,9999,7000,$moda,1,$Volets_1)+$Mani_flaps_1+($Vitesse7000_1*2)-($Puissance7000_1/2);
			else
				$Perf_7000_v_1=$Perf_7000_1;
		}
		//Avion 2
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Type_2=$data['Type'];
				$Pays_2=$data['Pays'];
				$Nom_2=$data['Nom'];
				$Engagement_2=$data['Engagement'];
				$Equipage_2=$data['Equipage'];
				$Engine_2=$data['Engine'];
				$Engine_Nbr_2=$data['Engine_Nbr'];
				$VitesseH_2=$data['VitesseH'];
				$VitesseB_2=$data['VitesseB'];
				$VitesseA_2=$data['VitesseA'];
				$VitesseP_2=$data['VitesseP'];
				$Alt_ref_2=$data['Alt_ref'];
				$Plafond_2=$data['Plafond'];
				$Autonomie_2=$data['Autonomie'];
				$Masse_2=$data['Masse'];
				$Blindage_2=$data['Blindage'];
				$Bombe_2=$data['Bombe'];
				$Bombe_Nbr_2=$data['Bombe_Nbr'];
				$Maniabilite_2=$data['Maniabilite'];
				$ManoeuvreH_2=$data['ManoeuvreH'];
				$ManoeuvreB_2=$data['ManoeuvreB'];
				$Stab_2=$data['Stabilite'];
				$Robustesse_2=$data['Robustesse'];
				$Engine_Power_2=$data['Puissance'];
				$Arme1_2=$data['ArmePrincipale'];
				$Arme1_Nbr_2=$data['Arme1_Nbr'];
				$Arme2_2=$data['ArmeSecondaire'];
				$Arme2_Nbr_2=$data['Arme2_Nbr'];
				$Arme3_2=$data['ArmeArriere'];
				$Arme3_Nbr_2=$data['Arme3_Nbr'];
				$Arme4_2=$data['ArmeSabord'];
				$Arme4_Nbr_2=$data['Arme4_Nbr'];
				$Arme5_2=$data['TourelleSup'];
				$Arme5_Nbr_2=$data['Arme5_Nbr'];
				$Arme6_2=$data['TourelleVentre'];
				$Arme6_Nbr_2=$data['Arme6_Nbr'];
				$Detection_2=$data['Detection']+$data['Radar'];
				$Vis_2=$data['Visibilite'];
				$Radio_2=$data['Radio'];
				$Radar_2=$data['Radar'];
				$Res_2=$data['Reservoir'];
				$Navi_2=$data['Navigation'];
				$Viseur_2=$data['Viseur'];
				$Volets_2=$data['Volets'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		if($Arme1_2)
		{
			$resultw3=mysqli_query($con,"SELECT * FROM Armes WHERE ID='$Arme1_2'");
			if($resultw3)
			{
				while($dataw3=mysqli_fetch_array($resultw3,MYSQLI_ASSOC))
				{
					$Arme1_nom_2=$dataw3['Nom'];
					$Arme1_cal_2=substr($dataw3['Calibre'],0,3);
					$Arme1_deg_2=$dataw3['Degats'];
					$Arme1_mult_2=$dataw3['Multi'];
				}
				mysqli_free_result($resultw3);
			}
			$Degats_chass_1_2=$Arme1_deg_2*$Arme1_mult_2*$Arme1_Nbr_2;
			$Degats_tot_2=$Degats_chass_1_2;
		}
		if($Arme2_2)
		{
			if($Arme2_2 ==$Arme1_2 and $Arme1_Nbr_2 ==$Arme2_Nbr_2)
            {
                $Arme2_nom_2=$Arme1_nom_2;
                $Arme2_cal_2=$Arme1_cal_2;
                $Degats_tot_2*=2;
            }
            else{
				$resultw4=mysqli_query($con,"SELECT * FROM Armes WHERE ID='$Arme2_2'");
				if($resultw4)
				{
					while($dataw4=mysqli_fetch_array($resultw4,MYSQLI_ASSOC))
					{
						$Arme2_nom_2=$dataw4['Nom'];
						$Arme2_cal_2=substr($dataw4['Calibre'],0,3);
						$Arme2_deg_2=$dataw4['Degats'];
						$Arme2_mult_2=$dataw4['Multi'];
					}
					mysqli_free_result($resultw4);
				}
				$Degats_chass_2_2=$Arme2_deg_2*$Arme2_mult_2*$Arme2_Nbr_2;
				if($Degats_chass_2_2 >$Degats_chass_1_2)$Degats_chass_1_2=$Degats_chass_2_2;
				$Degats_tot_2+=$Degats_chass_2_2;
			}
		}
		mysqli_close($con);
		if($Engine_2)
		{
			$Carbu_2=GetData("Moteur","ID",$Engine_2,"Carburant");
			$Engine_2=GetData("Moteur","ID",$Engine_2,"Nom");
		}
		$VitesseTH_2=0;	
		$VitesseAB_2=0;
		$Perf_7000_2=0;
		$Perf_9000_2=0;
		$Perf_12000_2=0;
		$Perf_3000_c_2=0;
		$Perf_5000_c_2=0;
		$Perf_7000_c_2=0;
		$Perf_9000_c_2=0;
		$VitesseLOW_2=GetSpeed("Avion",$avion2,500,0);
		$VitesseMLH_2=GetSpeed("Avion",$avion2,3000,0);
		$VitesseMH_2=GetSpeed("Avion",$avion2,5000,0);
		$VitesseA3000_2=GetSpeedA("Avion",$avion2,3000,0,$Engine_Nbr_2);
		$VitesseA5000_2=GetSpeedA("Avion",$avion2,5000,0,$Engine_Nbr_2);
		$PuissanceB_2=GetPuissance("Avion",$avion2,1,$Robustesse_2,1,1,$Engine_Nbr_2);
		$PuissanceLOW_2=GetPuissance("Avion",$avion2,500,$Robustesse_2,1,1,$Engine_Nbr_2);
		$Puissance3000_2=GetPuissance("Avion",$avion2,3000,$Robustesse_2,1,1,$Engine_Nbr_2);
		$PuissanceH_2=GetPuissance("Avion",$avion2,5000,$Robustesse_2,1,1,$Engine_Nbr_2);
		$Perf_500_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,500)+$Maniabilite_2+($VitesseLOW_2*2)-($PuissanceLOW_2/2);
		$Perf_3000_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,3000)+$Maniabilite_2+($VitesseMLH_2*2)-($Puissance3000_2/2);
		$Perf_5000_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,5000)+$Maniabilite_2+($VitesseMH_2*2)-($PuissanceH_2/2);
		$Perf_3000_f_2=$Perf_3000_2-($VitesseMLH_2*2)+($VitesseP_2*2);
		$Perf_5000_f_2=$Perf_5000_2-($VitesseMH_2*2)+($VitesseP_2*2);
		if($VitesseA5000_2 >668 and $VitesseP_2 >659 and ($VitesseMH_2*2) <($VitesseP_2+$VitesseA5000_2))
			$Perf_5000_c_2=$Perf_5000_2-($VitesseMH_2*2)+$VitesseA5000_2+$VitesseP_2;
		if($VitesseA3000_2 >668 and $VitesseP_2 >659 and ($VitesseMLH_2*2) <($VitesseP_2+$VitesseA3000_2))
			$Perf_3000_c_2=$Perf_3000_2-($VitesseMLH_2*2)+$VitesseA3000_2+$VitesseP_2;
		if($Volets_2)
		{
			$Mani_flaps_2=GetMani($Maniabilite_2,1,9999,$moda,1,$flaps_eni);
			$Perf_500_v_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,500,$moda,1,$Volets_2)+$Mani_flaps_2+($VitesseLOW_2*2)-($PuissanceLOW_2/2);
			$Perf_3000_v_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,3000,$moda,1,$Volets_2)+$Mani_flaps_2+($VitesseMLH_2*2)-($Puissance3000_2/2);
			$Perf_5000_v_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,5000,$moda,1,$Volets_2)+$Mani_flaps_2+($VitesseMH_2*2)-($PuissanceH_2/2);
		}
		else
		{
			$Perf_500_v_2=$Perf_500_2;
			$Perf_3000_v_2=$Perf_3000_2;
			$Perf_5000_v_2=$Perf_5000_2;
		}
		if($Plafond_2 >11999)
			$Perf_22000_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,12000)+$Maniabilite_2+(GetSpeed("Avion",$avion2,12000,0)*2)-(GetPuissance("Avion",$avion2,12000,$Robustesse_2,1,1,$Engine_Nbr_2)/2);
		if($Plafond_2 >8999)
		{
			$VitesseTH_2=GetSpeed("Avion",$avion2,9000,0);
			$VitesseAB_2=GetSpeedA("Avion",$avion2,9000,0,$Engine_Nbr_2);
			$PuissanceTH_2=GetPuissance("Avion",$avion2,9000,$Robustesse_2,1,1,$Engine_Nbr_2);
			$Perf_9000_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,9000)+$Maniabilite_2+($VitesseTH_2*2)-($PuissanceTH_2/2);
			$Perf_9000_f_2=$Perf_9000_2-($VitesseTH_2*2)+($VitesseP_2*2);
			if($VitesseAB_2 >668 and $VitesseP_2 >659 and ($VitesseTH_2*2) <($VitesseP_2+$VitesseAB_2))
				$Perf_9000_c_2=$Perf_9000_2-($VitesseTH_2*2)+$VitesseAB_2+$VitesseP_2;
			if($Volets_2)
				$Perf_9000_v_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,9000,$moda,1,$Volets_2)+$Mani_flaps_2+($VitesseTH_2*2)-($PuissanceTH_2/2);
			else
				$Perf_9000_v_2=$Perf_9000_2;
		}
		if($Plafond_2 >6999)
		{
			$Vitesse7000_2=GetSpeed("Avion",$avion2,7000,0);
			$VitesseA7000_2=GetSpeedA("Avion",$avion2,7000,0,$Engine_Nbr_2);
			$Puissance7000_2=GetPuissance("Avion",$avion2,7000,$Robustesse_2,1,1,$Engine_Nbr_2);
			$Perf_7000_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,7000)+$Maniabilite_2+($Vitesse7000_2*2)-(GetPuissance("Avion",$avion2,7000,$Robustesse_2,1,1,$Engine_Nbr_2)/2);
			$Perf_7000_f_2=$Perf_7000_2-($Vitesse7000_2*2)+($VitesseP_2*2);
			if($VitesseA7000_2 >668 and $VitesseP_2 >659 and ($Vitesse7000_2*2) <($VitesseP_2+$VitesseA7000_2))
				$Perf_7000_c_2=$Perf_7000_2-($Vitesse7000_2*2)+$VitesseA7000_2+$VitesseP_2;
			if($Volets_2)
				$Perf_7000_v_2=GetMano($ManoeuvreB_2,$ManoeuvreH_2,9999,9999,7000,$moda,1,$Volets_2)+$Mani_flaps_2+($Vitesse7000_2*2)-($Puissance7000_2/2);
			else
				$Perf_7000_v_2=$Perf_7000_2;
		}
		//Améliorations
		$Array_Mod=GetAmeliorations($avion1);		
		$Arme20_1=$Array_Mod[3];
		$Bombe50_nbr_1=$Array_Mod[12];
		$Bombe125_nbr_1=$Array_Mod[13];
		$Bombe250_nbr_1=$Array_Mod[14];
		$Bombe500_nbr_1=$Array_Mod[15];
		$Camera_low_1=$Array_Mod[16];
		$Camera_high_1=$Array_Mod[17];
		$Baby_1=$Array_Mod[18];
		$Radar_On_1=$Array_Mod[19];
		$Torpilles_1=$Array_Mod[20];
		$Mines_1=$Array_Mod[21];
		$Bombe1000_nbr_1=$Array_Mod[32];
		$Bombe2000_nbr_1=$Array_Mod[33];
		$Rockets_1=$Array_Mod[35];
		//Charges 1
		$Charge_1=0;
		$Charge1=$Bombe_Nbr_1*$Bombe_1;
		$Masse_full_1=$Masse_1+$Charge1;
		if($Charge1)
			$Autonomie_chg_1=round($Autonomie_1+(($Masse_1/$Engine_Power_1)-($Masse_full_1/$Engine_Power_1))*($Masse_1/10));
		else
			$Autonomie_chg_1=$Autonomie_1;
		$Dist_takeoff_hard_1=round($Masse_full_1/20);
		$Dist_takeoff_mou_sec_1=round($Masse_full_1/10);
		$Dist_takeoff_mou_rain_1=round($Masse_full_1/5);
		$Dist_landing_1=round($Masse_1/15);
		$Vit_mini_1=round(100+sqrt($Masse_1));
		if($Vit_mini_1 >245)$Vit_mini_1=245;
		$Vit_mini_flaps_1=round($Vit_mini_1*0.7);
		$Dist_landing_1_flaps3=round($Masse_1/15*$Vit_mini_flaps_1/$Vit_mini_1);
		$Vit_mini_chg_1=round(100+sqrt($Masse_full_1));

		$Array_Mod=GetAmeliorations($avion2);		
		$Arme20_2=$Array_Mod[3];
		$Bombe50_nbr_2=$Array_Mod[12];
		$Bombe125_nbr_2=$Array_Mod[13];
		$Bombe250_nbr_2=$Array_Mod[14];
		$Bombe500_nbr_2=$Array_Mod[15];
		$Camera_low_2=$Array_Mod[16];
		$Camera_high_2=$Array_Mod[17];
		$Baby_2=$Array_Mod[18];
		$Radar_On_2=$Array_Mod[19];
		$Torpilles_2=$Array_Mod[20];
		$Mines_2=$Array_Mod[21];
		$Bombe1000_nbr_2=$Array_Mod[32];
		$Bombe2000_nbr_2=$Array_Mod[33];
		$Rockets_2=$Array_Mod[35];	
		//Charges 2
		$Charge_2=0;
		$Charge2=$Bombe_Nbr_2*$Bombe_2;
		$Masse_full_2=$Masse_2+$Charge2;
		if($Charge2)
			$Autonomie_chg_2=round($Autonomie_2+(($Masse_2/$Engine_Power_2)-($Masse_full_2/$Engine_Power_2))*($Masse_2/10));
		else
			$Autonomie_chg_2=$Autonomie_2;
		$Dist_takeoff_hard_2=round($Masse_full_2/20);
		$Dist_takeoff_mou_sec_2=round($Masse_full_2/10);
		$Dist_takeoff_mou_rain_2=round($Masse_full_2/5);
		$Dist_landing_2=round($Masse_2/15);
		$Vit_mini_2=round(100+sqrt($Masse_2));
		if($Vit_mini_2 >245)$Vit_mini_2=245;
		$Vit_mini_flaps_2=round($Vit_mini_2*0.7);
		$Dist_landing_2_flaps3=round($Masse_2/15*$Vit_mini_flaps_2/$Vit_mini_2);
		$Vit_mini_chg_2=round(100+sqrt($Masse_full_2));		
		
		if($Arme20_1 and $Arme20_1 !=5 and $Arme1_cal_1 <20 and $Arme2_cal_1 <20)
			$Arme20_1="Canon<br>";
		else
			$Arme20_1="";
		if($Arme20_2 and $Arme20_2 !=5 and $Arme1_cal_2 <20 and $Arme2_cal_2 <20)
			$Arme20_2="Canon<br>";
		else
			$Arme20_2="";
		if($Bombe50_nbr_1 or $Bombe125_nbr_1 or $Bombe250_nbr_1 or $Bombe500_nbr_1 or $Bombe1000_nbr_1 or $Bombe2000_nbr_1)
		{
			$Bombeo_1="Bombes jusqu'à ";
			if($Bombe2000_nbr_1)
				$Bombeo_1.="2000kg<br>";
			elseif($Bombe1000_nbr_1)
				$Bombeo_1.="1000kg<br>";
			elseif($Bombe500_nbr_1)
				$Bombeo_1.="500kg<br>";
			elseif($Bombe250_nbr_1)
				$Bombeo_1.="250kg<br>";
			elseif($Bombe125_nbr_1)
				$Bombeo_1.="125kg<br>";
			elseif($Bombe50_nbr_1)
				$Bombeo_1.="50kg<br>";
		}
		if($Bombe50_nbr_2 or $Bombe125_nbr_2 or $Bombe250_nbr_2 or $Bombe500_nbr_2 or $Bombe1000_nbr_2 or $Bombe2000_nbr_2)
		{
			$Bombeo_2="Bombes jusqu'à ";
			if($Bombe2000_nbr_2)
				$Bombeo_2.="2000kg<br>";
			elseif($Bombe1000_nbr_2)
				$Bombeo_2.="1000kg<br>";
			elseif($Bombe500_nbr_2)
				$Bombeo_2.="500kg<br>";
			elseif($Bombe250_nbr_2)
				$Bombeo_2.="250kg<br>";
			elseif($Bombe125_nbr_2)
				$Bombeo_2.="125kg<br>";
			elseif($Bombe50_nbr_2)
				$Bombeo_2.="50kg<br>";
		}
		if($Torpilles_1)
			$Torpilles_1="Torpille<br>";
		else
			$Torpilles_1="";
		if($Torpilles_2)
			$Torpilles_2="Torpille<br>";
		else
			$Torpilles_2="";
		if($Mines_1)
			$Mines_1="Mines et Charges<br>";
		else
			$Mines_1="";
		if($Mines_2)
			$Mines_2="Mines et Charges<br>";
		else
			$Mines_2="";
		if($Rockets_1)
			$Rockets_1="Rockets<br>";
		else
			$Rockets_1="";
		if($Rockets_2)
			$Rockets_2="Rockets<br>";
		else
			$Rockets_2="";
		if($Camera_low_1 > 25)
			$Camera_low_1="Caméra fixe<br>";
		else
			$Camera_low_1="";
		if($Camera_low_2 > 25)
			$Camera_low_2="Caméra fixe<br>";
		else
			$Camera_low_2="";
		if($Camera_high_1 == 26)
			$Camera_high_1="Caméra fixe<br>";
		elseif($Camera_high_1 == 27)
			$Camera_high_1="Caméra haute altitude<br>";
		else
			$Camera_high_1="";
		if($Camera_high_2 == 26)
			$Camera_high_2="Caméra fixe<br>";
		elseif($Camera_high_2 == 27)
			$Camera_high_2="Caméra haute altitude<br>";
		else
			$Camera_high_2="";
		if($Radar_On_1)
			$Radar_On_1="Radar embarqué<br>";
		else
			$Radar_On_1="";
		if($Radar_On_2)
			$Radar_On_2="Radar embarqué<br>";
		else
			$Radar_On_2="";
		if($Baby_1)
			$Baby_1="Réservoir largable ".$Baby_1."l<br>";
		else
			$Baby_1="";
		if($Baby_2)
			$Baby_2="Réservoir largable ".$Baby_2."l<br>";
		else
			$Baby_2="";
		//Equipements de série
		if($Radio_1 ==2)
			$Radio_1="Longue portée<br>";
		elseif($Radio_1 ==1)
			$Radio_1="Améliorée<br>";
		else
			$Radio_1="<i>Standard</i>";
		if($Radio_2 ==2)
			$Radio_2="Longue portée<br>";
		elseif($Radio_2 ==1)
			$Radio_2="Améliorée<br>";
		else
			$Radio_2="<i>Standard</i>";

		if($Radar_1 ==40)
			$Radar_1="Centimétrique<br>";
		elseif($Radar_1 ==30)
			$Radar_1="Décimétrique évolué<br>";
		elseif($Radar_1 ==20)
			$Radar_1="Décimétrique amélioré<br>";
		elseif($Radar_1 ==10)
			$Radar_1="Décimétrique primitif<br>";
		else
			$Radar_1="<i>Aucun</i>";
		if($Radar_2 ==40)
			$Radar_2="Centimétrique<br>";
		elseif($Radar_2 ==30)
			$Radar_2="Décimétrique évolué<br>";
		elseif($Radar_2 ==20)
			$Radar_2="Décimétrique amélioré<br>";
		elseif($Radar_2 ==10)
			$Radar_2="Décimétrique primitif<br>";
		else
			$Radar_2="<i>Aucun</i>";
		
		if($Navi_1 ==3)
			$Navi_1="Gyroscopique<br>";
		elseif($Navi_1 ==2)
			$Navi_1="A la pointe<br>";
		elseif($Navi_1 ==1)
			$Navi_1="Améliorée<br>";
		else
			$Navi_1="<i>Standard</i>";
		if($Navi_2 == 3)
			$Navi_2="Gyroscopique<br>";
		elseif($Navi_2 == 2)
			$Navi_2="A la pointe<br>";
		elseif($Navi_2 ==1)
			$Navi_2="Améliorée<br>";
		else
			$Navi_2="<i>Standard</i>";

		if($Viseur_1 ==3)
			$Viseur_1="Bombardement<br>";
		elseif($Viseur_1 ==2)
			$Viseur_1="Attaque<br>";
		elseif($Viseur_1 ==1)
			$Viseur_1="Chasse<br>";
		else
			$Viseur_1="<i>Standard</i>";
		if($Viseur_2 == 3)
			$Viseur_2="Bombardement<br>";
		elseif($Viseur_2 == 2)
			$Viseur_2="Attaque<br>";
		elseif($Viseur_2 ==1)
			$Viseur_2="Chasse<br>";
		else
			$Viseur_2="<i>Standard</i>";

		if($Res_1 ==2)
			$Res_1="Grande capacité<br>";
		elseif($Res_1 ==1)
			$Res_1="Auto-obturant<br>";
		else
			$Res_1="<i>Standard</i>";
		if($Res_2 == 2)
			$Res_2="Grande capacité<br>";
		elseif($Res_2 ==1)
			$Res_2="Auto-obturant<br>";
		else
			$Res_2="<i>Standard</i>";

		if($Volets_1 ==3)
			$Volets_1="De piqué<br>";
		elseif($Volets_1 ==2)
			$Volets_1="Automatiques";
		elseif($Volets_1 ==1)
			$Volets_1="Améliorés";
		else
			$Volets_1="<i>Standard</i>";
		if($Volets_2 ==3)
			$Volets_2="De piqué<br>";
		elseif($Volets_2 ==2)
			$Volets_2="Automatiques";
		elseif($Volets_2 ==1)
			$Volets_2="Améliorés";
		else
			$Volets_2="<i>Standard</i>";
			
		$equipment_1=$Arme20_1.$Bombeo_1.$Torpilles_1.$Mines_1.$Rockets_1.$Camera_low_1.$Camera_high_1.$Radar_On_1.$Baby_1;
		$equipment_2=$Arme20_2.$Bombeo_2.$Torpilles_2.$Mines_2.$Rockets_2.$Camera_low_2.$Camera_high_2.$Radar_On_2.$Baby_2;		
		//Output
		echo "<table class='table'>
		<tr>
			<th colspan='3' class='TitreBleu_bc'>Avions à comparer</th>
		</tr>
		<tr bgcolor='lightyellow'>
			<th></th><th>".$Nom_1."</th><th>".$Nom_2."</th>
		</tr>
		<tr>
			<th></th><th><img src='images/".$Pays_1."20.gif'></th><th><img src='images/".$Pays_2."20.gif'></th>
		</tr>
		<tr>
			<th></th><th><img src='images/avions/avion".$avion1.".gif' title='".$Nom_1."'></th><th><img src='images/avions/avion".$avion2.".gif' title='".$Nom_2."'></th>
		</tr>
		<tr>
			<td align='left'>Service</td><td>".$Engagement_1."</td><td>".$Engagement_2."</td>
		</tr>
		<tr>
			<td align='left'>Equipage</td><td>".$Equipage_1."</td><td>".$Equipage_2."</td>
		</tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";			
		echo "<tr><td align='left'>Moteur(s)</td><td>".$Engine_Nbr_1."x ".$Engine_1."</td><td>".$Engine_Nbr_2."x ".$Engine_2."</td></tr>";
		echo "<tr><td align='left'>Carburant</td><td>".$Carbu_1." Octane</td><td>".$Carbu_2." Octane</td></tr>";
		echo "<tr><td align='left'>Navigation de série</td><td>".$Navi_1."</td><td>".$Navi_2."</td></tr>";
		echo "<tr><td align='left'>Radar de série</td><td>".$Radar_1."</td><td>".$Radar_2."</td></tr>";
		echo "<tr><td align='left'>Radio de série</td><td>".$Radio_1."</td><td>".$Radio_2."</td></tr>";
		echo "<tr><td align='left'>Reservoir de série</td><td>".$Res_1."</td><td>".$Res_2."</td></tr>";
		echo "<tr><td align='left'>Viseur de série</td><td>".$Viseur_1."</td><td>".$Viseur_2."</td></tr>";
		echo "<tr><td align='left'>Volets de série</td><td>".$Volets_1."</td><td>".$Volets_2."</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";			
		echo "<tr><td align='left'>Armement</td><td>".$Arme1_Nbr_1." ".$Arme1_nom_1." (".$Arme1_cal_1."mm)</td><td>".$Arme1_Nbr_2." ".$Arme1_nom_2." (".$Arme1_cal_2."mm)</td></tr>";
		if(($Arme2_1 !=5 and $Arme2_1) or ($Arme2_2 !=5 and $Arme2_2))
			echo "<tr><td></td><td>".$Arme2_Nbr_1." ".$Arme2_nom_1." (".$Arme2_cal_1."mm)</td><td>".$Arme2_Nbr_2." ".$Arme2_nom_2." (".$Arme2_cal_2."mm)</td></tr>";
		if($Arme3_1 or $Arme3_2 or $Arme5_1 or $Arme5_2)
		{
			if($Arme3_1)
			{
				$Arme3_nom_1=GetData("Armes","ID",$Arme3_1,"Nom");
				$Arme3_cal_1=substr(GetData("Armes","ID",$Arme3_1,"Calibre"),0,3);
				$Arme3_1="Arrière: ".$Arme3_Nbr_1." ".$Arme3_nom_1." (".$Arme3_cal_1."mm)<br>";
			}
			else
				$Arme3_1="";
			if($Arme4_1)
			{
				$Arme4_nom_1=GetData("Armes","ID",$Arme4_1,"Nom");
				$Arme4_cal_1=substr(GetData("Armes","ID",$Arme4_1,"Calibre"),0,3);
				$Arme4_1="Latéral: ".$Arme4_Nbr_1." ".$Arme4_nom_1." (".$Arme4_cal_1."mm)<br>";
			}
			else
				$Arme4_1="";
			if($Arme5_1)
			{
				$Arme5_nom_1=GetData("Armes","ID",$Arme5_1,"Nom");
				$Arme5_cal_1=substr(GetData("Armes","ID",$Arme5_1,"Calibre"),0,3);
				$Arme5_1="Dorsal: ".$Arme5_Nbr_1." ".$Arme5_nom_1." (".$Arme5_cal_1."mm)<br>";
			}
			else
				$Arme5_1="";
			if($Arme6_1)
			{
				$Arme6_nom_1=GetData("Armes","ID",$Arme6_1,"Nom");
				$Arme6_cal_1=substr(GetData("Armes","ID",$Arme6_1,"Calibre"),0,3);
				$Arme6_1="Ventral: ".$Arme6_Nbr_1." ".$Arme6_nom_1." (".$Arme6_cal_1."mm)";
			}
			else
				$Arme6_1="";
			if($Arme3_2)
			{
				$Arme3_nom_2=GetData("Armes","ID",$Arme3_2,"Nom");
				$Arme3_cal_2=substr(GetData("Armes","ID",$Arme3_2,"Calibre"),0,3);
				$Arme3_2="Arrière: ".$Arme3_Nbr_2." ".$Arme3_nom_2." (".$Arme3_cal_2."mm)<br>";
			}
			else
				$Arme3_2="";
			if($Arme4_2)
			{
				$Arme4_nom_2=GetData("Armes","ID",$Arme4_2,"Nom");
				$Arme4_cal_2=substr(GetData("Armes","ID",$Arme4_2,"Calibre"),0,3);
				$Arme4_2="Latéral: ".$Arme4_Nbr_2." ".$Arme4_nom_2." (".$Arme4_cal_2."mm)<br>";
			}
			else
				$Arme4_2="";
			if($Arme5_2)
			{
				$Arme5_nom_2=GetData("Armes","ID",$Arme5_2,"Nom");
				$Arme5_cal_2=substr(GetData("Armes","ID",$Arme5_2,"Calibre"),0,3);
				$Arme5_2="Dorsal: ".$Arme5_Nbr_2." ".$Arme5_nom_2." (".$Arme5_cal_2."mm)<br>";
			}
			else
				$Arme5_2="";
			if($Arme6_2)
			{
				$Arme6_nom_2=GetData("Armes","ID",$Arme6_2,"Nom");
				$Arme6_cal_2=substr(GetData("Armes","ID",$Arme6_2,"Calibre"),0,3);
				$Arme6_2="Ventral: ".$Arme6_Nbr_2." ".$Arme6_nom_2." (".$Arme6_cal_2."mm)";
			}
			else
				$Arme6_2="";
			echo "<tr><td align='left'>Tourelles</td><td>".$Arme3_1.$Arme4_1.$Arme5_1.$Arme6_1."</td><td>".$Arme3_2.$Arme4_2.$Arme5_2.$Arme6_2."</td></tr>";	
		}
		if($Bombe_Nbr_1 or $Bombe_Nbr_2)
		{
			if($Bombe_Nbr_1 >0)
				$Bombes_1=$Bombe_Nbr_1."x ".$Bombe_1."kg";
			else
				$Bombes_1="Aucune";
			if($Bombe_Nbr_2 >0)
				$Bombes_2=$Bombe_Nbr_2."x ".$Bombe_2."kg";
			else
				$Bombes_2="Aucune";
			echo "<tr><td align='left'>Bombes</td><td>".$Bombes_1."</td><td>".$Bombes_2."</td></tr>";
		}
		echo "<tr><td colspan='3'><hr></td></tr>";			
		echo "<tr><td align='left'>Options</td><td>".$equipment_1."</td><td>".$equipment_2."</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";			
		if($Robustesse_2 == $Robustesse_1)
			echo "<tr><td align='left'>Robustesse</td><td bgcolor='lightyellow'>".$Robustesse_1."</td><td bgcolor='lightyellow'>".$Robustesse_2."</td></tr>";
		elseif($Robustesse_2 > $Robustesse_1)
			echo "<tr><td align='left'>Robustesse</td><td bgcolor='LightCoral'>".$Robustesse_1."</td><td bgcolor='lightgreen'>".$Robustesse_2."</td></tr>";
		else
			echo "<tr><td align='left'>Robustesse</td><td bgcolor='lightgreen'>".$Robustesse_1."</td><td bgcolor='LightCoral'>".$Robustesse_2."</td></tr>";
		if($Blindage_2 == $Blindage_1)
			echo "<tr><td align='left'>Blindage</td><td bgcolor='lightyellow'>".$Blindage_1."</td><td bgcolor='lightyellow'>".$Blindage_2."</td></tr>";
		elseif($Blindage_2 > $Blindage_1)
			echo "<tr><td align='left'>Blindage</td><td bgcolor='LightCoral'>".$Blindage_1."</td><td bgcolor='lightgreen'>".$Blindage_2."</td></tr>";
		else
			echo "<tr><td align='left'>Blindage</td><td bgcolor='lightgreen'>".$Blindage_1."</td><td bgcolor='LightCoral'>".$Blindage_2."</td></tr>";
		if($Engine_Power_2 == $Engine_Power_1)
			echo "<tr><td align='left'>Puissance motrice</td><td bgcolor='lightyellow'>".$Engine_Power_1."</td><td bgcolor='lightyellow'>".$Engine_Power_2."</td></tr>";
		elseif($Engine_Power_2 > $Engine_Power_1)
			echo "<tr><td align='left'>Puissance motrice</td><td bgcolor='LightCoral'>".$Engine_Power_1."</td><td bgcolor='lightgreen'>".$Engine_Power_2."</td></tr>";
		else
			echo "<tr><td align='left'>Puissance motrice</td><td bgcolor='lightgreen'>".$Engine_Power_1."</td><td bgcolor='LightCoral'>".$Engine_Power_2."</td></tr>";			
		if($Plafond_2 == $Plafond_1)
			echo "<tr><td align='left'>Plafond maximal</td><td bgcolor='lightyellow'>".$Plafond_1."m</td><td bgcolor='lightyellow'>".$Plafond_2."m</td></tr>";
		elseif($Plafond_2 > $Plafond_1)
			echo "<tr><td align='left'>Plafond maximal</td><td bgcolor='LightCoral'>".$Plafond_1."m</td><td bgcolor='lightgreen'>".$Plafond_2."m</td></tr>";
		else
			echo "<tr><td align='left'>Plafond maximal</td><td bgcolor='lightgreen'>".$Plafond_1."m</td><td bgcolor='LightCoral'>".$Plafond_2."m</td></tr>";
		if($Autonomie_chg_2 == $Autonomie_chg_1)
			echo "<tr><td align='left'>Autonomie en charge</td><td bgcolor='lightyellow'>".$Autonomie_chg_1."km avec ".$Charge1."kg</td><td bgcolor='lightyellow'>".$Autonomie_chg_2."km avec ".$Charge2."kg</td></tr>";
		elseif($Autonomie_chg_2 > $Autonomie_chg_1)
			echo "<tr><td align='left'>Autonomie en charge</td><td bgcolor='LightCoral'>".$Autonomie_chg_1."km avec ".$Charge1."kg</td><td bgcolor='lightgreen'>".$Autonomie_chg_2."km avec ".$Charge2."kg</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie en charge</td><td bgcolor='lightgreen'>".$Autonomie_chg_1."km avec ".$Charge1."kg</td><td bgcolor='LightCoral'>".$Autonomie_chg_2."km avec ".$Charge2."kg</td></tr>";
		if($Autonomie_2 == $Autonomie_1)
			echo "<tr><td align='left'>Autonomie maximale</td><td bgcolor='lightyellow'>".$Autonomie_1."km</td><td bgcolor='lightyellow'>".$Autonomie_2."km</td></tr>";
		elseif($Autonomie_2 > $Autonomie_1)
			echo "<tr><td align='left'>Autonomie maximale</td><td bgcolor='LightCoral'>".$Autonomie_1."km</td><td bgcolor='lightgreen'>".$Autonomie_2."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie maximale</td><td bgcolor='lightgreen'>".$Autonomie_1."km</td><td bgcolor='LightCoral'>".$Autonomie_2."km</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";
		if($PuissanceTH_2 == $PuissanceTH_1)
			echo "<tr><td align='left'>Accélération à 9000m</td><td bgcolor='lightyellow'>".$PuissanceTH_1."</td><td bgcolor='lightyellow'>".$PuissanceTH_2."</td></tr>";
		elseif($PuissanceTH_2 > $PuissanceTH_1)
			echo "<tr><td align='left'>Accélération à 9000m</td><td bgcolor='LightCoral'>".$PuissanceTH_1."</td><td bgcolor='lightgreen'>".$PuissanceTH_2."</td></tr>";
		else
			echo "<tr><td align='left'>Accélération à 9000m</td><td bgcolor='lightgreen'>".$PuissanceTH_1."</td><td bgcolor='LightCoral'>".$PuissanceTH_2."</td></tr>";
		if($PuissanceH_2 == $PuissanceH_1)
			echo "<tr><td align='left'>Accélération à 5000m</td><td bgcolor='lightyellow'>".$PuissanceH_1."</td><td bgcolor='lightyellow'>".$PuissanceH_2."</td></tr>";
		elseif($PuissanceH_2 > $PuissanceH_1)
			echo "<tr><td align='left'>Accélération à 5000m</td><td bgcolor='LightCoral'>".$PuissanceH_1."</td><td bgcolor='lightgreen'>".$PuissanceH_2."</td></tr>";
		else
			echo "<tr><td align='left'>Accélération à 5000m</td><td bgcolor='lightgreen'>".$PuissanceH_1."</td><td bgcolor='LightCoral'>".$PuissanceH_2."</td></tr>";
		if($PuissanceB_2 == $PuissanceB_1)
			echo "<tr><td align='left'>Accélération au niveau de la mer</td><td bgcolor='lightyellow'>".$PuissanceB_1."</td><td bgcolor='lightyellow'>".$PuissanceB_2."</td></tr>";
		elseif($PuissanceB_2 > $PuissanceB_1)
			echo "<tr><td align='left'>Accélération au niveau de la mer</td><td bgcolor='LightCoral'>".$PuissanceB_1."</td><td bgcolor='lightgreen'>".$PuissanceB_2."</td></tr>";
		else
			echo "<tr><td align='left'>Accélération au niveau de la mer</td><td bgcolor='lightgreen'>".$PuissanceB_1."</td><td bgcolor='LightCoral'>".$PuissanceB_2."</td></tr>";
		if($VitesseH_2 == $VitesseH_1)
			echo "<tr><td align='left'>Vitesse maximale</td><td bgcolor='lightyellow'>".$VitesseH_1." à ".$Alt_ref_1."m</td><td bgcolor='lightyellow'>".$VitesseH_2." à ".$Alt_ref_2."m</td></tr>";
		elseif($VitesseH_2 > $VitesseH_1)
			echo "<tr><td align='left'>Vitesse maximale</td><td bgcolor='LightCoral'>".$VitesseH_1." à ".$Alt_ref_1."m</td><td bgcolor='lightgreen'>".$VitesseH_2." à ".$Alt_ref_2."m</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse maximale</td><td bgcolor='lightgreen'>".$VitesseH_1." à ".$Alt_ref_1."m</td><td bgcolor='LightCoral'>".$VitesseH_2." à ".$Alt_ref_2."m</td></tr>";
		if($VitesseTH_2 == $VitesseTH_1)
			echo "<tr><td align='left'>Vitesse max à 9000m</td><td bgcolor='lightyellow'>".$VitesseTH_1."</td><td bgcolor='lightyellow'>".$VitesseTH_2."</td></tr>";
		elseif($VitesseTH_2 > $VitesseTH_1)
			echo "<tr><td align='left'>Vitesse max à 9000m</td><td bgcolor='LightCoral'>".$VitesseTH_1."</td><td bgcolor='lightgreen'>".$VitesseTH_2."</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max à 9000m</td><td bgcolor='lightgreen'>".$VitesseTH_1."</td><td bgcolor='LightCoral'>".$VitesseTH_2."</td></tr>";
		if($VitesseMH_2 == $VitesseMH_1)
			echo "<tr><td align='left'>Vitesse max à 5000m</td><td bgcolor='lightyellow'>".$VitesseMH_1."</td><td bgcolor='lightyellow'>".$VitesseMH_2."</td></tr>";
		elseif($VitesseMH_2 > $VitesseMH_1)
			echo "<tr><td align='left'>Vitesse max à 5000m</td><td bgcolor='LightCoral'>".$VitesseMH_1."</td><td bgcolor='lightgreen'>".$VitesseMH_2."</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max à 5000m</td><td bgcolor='lightgreen'>".$VitesseMH_1."</td><td bgcolor='LightCoral'>".$VitesseMH_2."</td></tr>";
		if($VitesseMLH_2 == $VitesseMLH_1)
			echo "<tr><td align='left'>Vitesse max à 3000m</td><td bgcolor='lightyellow'>".$VitesseMLH_1."</td><td bgcolor='lightyellow'>".$VitesseMLH_2."</td></tr>";
		elseif($VitesseMLH_2 > $VitesseMLH_1)
			echo "<tr><td align='left'>Vitesse max à 3000m</td><td bgcolor='LightCoral'>".$VitesseMLH_1."</td><td bgcolor='lightgreen'>".$VitesseMLH_2."</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max à 3000m</td><td bgcolor='lightgreen'>".$VitesseMLH_1."</td><td bgcolor='LightCoral'>".$VitesseMLH_2."</td></tr>";
		if($VitesseB_2 == $VitesseB_1)
			echo "<tr><td align='left'>Vitesse max au niveau de la mer</td><td bgcolor='lightyellow'>".$VitesseB_1."</td><td bgcolor='lightyellow'>".$VitesseB_2."</td></tr>";
		elseif($VitesseB_2 > $VitesseB_1)
			echo "<tr><td align='left'>Vitesse max au niveau de la mer</td><td bgcolor='LightCoral'>".$VitesseB_1."</td><td bgcolor='lightgreen'>".$VitesseB_2."</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max au niveau de la mer</td><td bgcolor='lightgreen'>".$VitesseB_1."</td><td bgcolor='LightCoral'>".$VitesseB_2."</td></tr>";			
		if($VitesseAB_2 == $VitesseAB_1)
			echo "<tr><td align='left'>Vitesse ascensionnelle à 9000m</td><td bgcolor='lightyellow'>".$VitesseAB_1."</td><td bgcolor='lightyellow'>".$VitesseAB_2."</td></tr>";
		elseif($VitesseAB_2 > $VitesseAB_1)
			echo "<tr><td align='left'>Vitesse ascensionnelle à 9000m</td><td bgcolor='LightCoral'>".$VitesseAB_1."</td><td bgcolor='lightgreen'>".$VitesseAB_2."</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse ascensionnelle à 9000m</td><td bgcolor='lightgreen'>".$VitesseAB_1."</td><td bgcolor='LightCoral'>".$VitesseAB_2."</td></tr>";
		if($VitesseA_2 == $VitesseA_1)
			echo "<tr><td align='left'>Vitesse ascensionnelle au niveau de la mer</td><td bgcolor='lightyellow'>".$VitesseA_1."</td><td bgcolor='lightyellow'>".$VitesseA_2."</td></tr>";
		elseif($VitesseA_2 > $VitesseA_1)
			echo "<tr><td align='left'>Vitesse ascensionnelle au niveau de la mer</td><td bgcolor='LightCoral'>".$VitesseA_1."</td><td bgcolor='lightgreen'>".$VitesseA_2."</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse ascensionnelle au niveau de la mer</td><td bgcolor='lightgreen'>".$VitesseA_1."</td><td bgcolor='LightCoral'>".$VitesseA_2."</td></tr>";			
		if($VitesseP_2 == $VitesseP_1)
			echo "<tr><td align='left'>Vitesse max en piqué</td><td bgcolor='lightyellow'>".$VitesseP_1."</td><td bgcolor='lightyellow'>".$VitesseP_2."</td></tr>";
		elseif($VitesseP_2 > $VitesseP_1)
			echo "<tr><td align='left'>Vitesse max en piqué</td><td bgcolor='LightCoral'>".$VitesseP_1."</td><td bgcolor='lightgreen'>".$VitesseP_2."</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max en piqué</td><td bgcolor='lightgreen'>".$VitesseP_1."</td><td bgcolor='LightCoral'>".$VitesseP_2."</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";
		if($Maniabilite_2 == $Maniabilite_1)
			echo "<tr><td align='left'>Taux de roulis</td><td bgcolor='lightyellow'>".$Maniabilite_1."</td><td bgcolor='lightyellow'>".$Maniabilite_2."</td></tr>";
		elseif($Maniabilite_2 > $Maniabilite_1)
			echo "<tr><td align='left'>Taux de roulis</td><td bgcolor='LightCoral'>".$Maniabilite_1."</td><td bgcolor='lightgreen'>".$Maniabilite_2."</td></tr>";
		else
			echo "<tr><td align='left'>Taux de roulis</td><td bgcolor='lightgreen'>".$Maniabilite_1."</td><td bgcolor='LightCoral'>".$Maniabilite_2."</td></tr>";
		if($ManoeuvreH_2 == $ManoeuvreH_1)
			echo "<tr><td align='left'>Rayon de virage à haute altitude</td><td bgcolor='lightyellow'>".$ManoeuvreH_1."</td><td bgcolor='lightyellow'>".$ManoeuvreH_2."</td></tr>";
		elseif($ManoeuvreH_2 > $ManoeuvreH_1)
			echo "<tr><td align='left'>Rayon de virage à haute altitude</td><td bgcolor='LightCoral'>".$ManoeuvreH_1."</td><td bgcolor='lightgreen'>".$ManoeuvreH_2."</td></tr>";
		else
			echo "<tr><td align='left'>Rayon de virage à haute altitude</td><td bgcolor='lightgreen'>".$ManoeuvreH_1."</td><td bgcolor='LightCoral'>".$ManoeuvreH_2."</td></tr>";
		if($ManoeuvreB_2 == $ManoeuvreB_1)
			echo "<tr><td align='left'>Rayon de virage à basse altitude</td><td bgcolor='lightyellow'>".$ManoeuvreB_1."</td><td bgcolor='lightyellow'>".$ManoeuvreB_2."</td></tr>";
		elseif($ManoeuvreB_2 > $ManoeuvreB_1)
			echo "<tr><td align='left'>Rayon de virage à basse altitude</td><td bgcolor='LightCoral'>".$ManoeuvreB_1."</td><td bgcolor='lightgreen'>".$ManoeuvreB_2."</td></tr>";
		else
			echo "<tr><td align='left'>Rayon de virage à basse altitude</td><td bgcolor='lightgreen'>".$ManoeuvreB_1."</td><td bgcolor='LightCoral'>".$ManoeuvreB_2."</td></tr>";
		if($Stab_2 == $Stab_1)
			echo "<tr><td align='left'>Stabilité</td><td bgcolor='lightyellow'>".$Stab_1."</td><td bgcolor='lightyellow'>".$Stab_2."</td></tr>";
		elseif($Stab_2 > $Stab_1)
			echo "<tr><td align='left'>Stabilité</td><td bgcolor='LightCoral'>".$Stab_1."</td><td bgcolor='lightgreen'>".$Stab_2."</td></tr>";
		else
			echo "<tr><td align='left'>Stabilité</td><td bgcolor='lightgreen'>".$Stab_1."</td><td bgcolor='LightCoral'>".$Stab_2."</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";	
		if($Vis_2 == $Vis_1)
			echo "<tr><td align='left'>Malus de furtivité</td><td bgcolor='lightyellow'>".$Vis_1."</td><td bgcolor='lightyellow'>".$Vis_2."</td></tr>";
		elseif($Vis_2 < $Vis_1)
			echo "<tr><td align='left'>Malus de furtivité</td><td bgcolor='LightCoral'>".$Vis_1."</td><td bgcolor='lightgreen'>".$Vis_2."</td></tr>";
		else
			echo "<tr><td align='left'>Malus de furtivité</td><td bgcolor='lightgreen'>".$Vis_1."</td><td bgcolor='LightCoral'>".$Vis_2."</td></tr>";
		if($Detection_2 == $Detection_1)
			echo "<tr><td align='left'>Bonus de détection</td><td bgcolor='lightyellow'>".$Detection_1."</td><td bgcolor='lightyellow'>".$Detection_2."</td></tr>";
		elseif($Detection_2 > $Detection_1)
			echo "<tr><td align='left'>Bonus de détection</td><td bgcolor='LightCoral'>".$Detection_1."</td><td bgcolor='lightgreen'>".$Detection_2."</td></tr>";
		else
			echo "<tr><td align='left'>Bonus de détection</td><td bgcolor='lightgreen'>".$Detection_1."</td><td bgcolor='LightCoral'>".$Detection_2."</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";
		if($Dist_takeoff_hard_2 == $Dist_takeoff_hard_1)
			echo "<tr><td align='left'>Décollage sur piste en dur</td><td bgcolor='lightyellow'>".$Dist_takeoff_hard_1."m avec ".$Charge1."kg de charge</td><td bgcolor='lightyellow'>".$Dist_takeoff_hard_2."m avec ".$Charge2."kg de charge</td></tr>";
		elseif($Dist_takeoff_hard_2 < $Dist_takeoff_hard_1)
			echo "<tr><td align='left'>Décollage sur piste en dur</td><td bgcolor='LightCoral'>".$Dist_takeoff_hard_1."m avec ".$Charge1."kg de charge</td><td bgcolor='lightgreen'>".$Dist_takeoff_hard_2."m avec ".$Charge2."kg de charge</td></tr>";
		else
			echo "<tr><td align='left'>Décollage sur piste en dur</td><td bgcolor='lightgreen'>".$Dist_takeoff_hard_1."m avec ".$Charge1."kg de charge</td><td bgcolor='LightCoral'>".$Dist_takeoff_hard_2."m avec ".$Charge2."kg de charge</td></tr>";
		if($Dist_takeoff_mou_sec_2 == $Dist_takeoff_mou_sec_1)
			echo "<tr><td align='left'>Décollage sur terrain par temps sec</td><td bgcolor='lightyellow'>".$Dist_takeoff_mou_sec_1."m avec ".$Charge1."kg de charge</td><td bgcolor='lightyellow'>".$Dist_takeoff_mou_sec_2."m avec ".$Charge2."kg de charge</td></tr>";
		elseif($Dist_takeoff_mou_sec_2 < $Dist_takeoff_mou_sec_1)
			echo "<tr><td align='left'>Décollage sur terrain par temps sec</td><td bgcolor='LightCoral'>".$Dist_takeoff_mou_sec_1."m avec ".$Charge1."kg de charge</td><td bgcolor='lightgreen'>".$Dist_takeoff_mou_sec_2."m avec ".$Charge2."kg de charge</td></tr>";
		else
			echo "<tr><td align='left'>Décollage sur terrain par temps sec</td><td bgcolor='lightgreen'>".$Dist_takeoff_mou_sec_1."m avec ".$Charge1."kg de charge</td><td bgcolor='LightCoral'>".$Dist_takeoff_mou_sec_2."m avec ".$Charge2."kg de charge</td></tr>";
		if($Dist_takeoff_mou_rain_2 == $Dist_takeoff_mou_rain_1)
			echo "<tr><td align='left'>Décollage sur terrain par pluie ou neige</td><td bgcolor='lightyellow'>".$Dist_takeoff_mou_rain_1."m avec ".$Charge1."kg de charge</td><td bgcolor='lightyellow'>".$Dist_takeoff_mou_rain_2."m avec ".$Charge2."kg de charge</td></tr>";
		elseif($Dist_takeoff_mou_rain_2 < $Dist_takeoff_mou_rain_1)
			echo "<tr><td align='left'>Décollage sur terrain par pluie ou neige</td><td bgcolor='LightCoral'>".$Dist_takeoff_mou_rain_1."m avec ".$Charge1."kg de charge</td><td bgcolor='lightgreen'>".$Dist_takeoff_mou_rain_2."m avec ".$Charge2."kg de charge</td></tr>";
		else
			echo "<tr><td align='left'>Décollage sur terrain par pluie ou neige</td><td bgcolor='lightgreen'>".$Dist_takeoff_mou_rain_1."m avec ".$Charge1."kg de charge</td><td bgcolor='LightCoral'>".$Dist_takeoff_mou_rain_2."m avec ".$Charge2."kg de charge</td></tr>";
		if($Vit_mini_2 == $Vit_mini_1)
			echo "<tr><td align='left'>Vitesse minimale d'atterrissage à vide</td><td bgcolor='lightyellow'>".$Vit_mini_1."km/h</td><td bgcolor='lightyellow'>".$Vit_mini_2."km/h</td></tr>";
		elseif($Vit_mini_2 < $Vit_mini_1)
			echo "<tr><td align='left'>Vitesse minimale d'atterrissage à vide</td><td bgcolor='LightCoral'>".$Vit_mini_1."km/h</td><td bgcolor='lightgreen'>".$Vit_mini_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse minimale d'atterrissage à vide</td><td bgcolor='lightgreen'>".$Vit_mini_1."km/h</td><td bgcolor='LightCoral'>".$Vit_mini_2."km/h</td></tr>";
		if($Vit_mini_chg_2 == $Vit_mini_chg_1)
			echo "<tr><td align='left'>Vitesse minimale d'atterrissage en charge</td><td bgcolor='lightyellow'>".$Vit_mini_chg_1."km/h avec ".$Charge1."kg de charge</td><td bgcolor='lightyellow'>".$Vit_mini_chg_2."km/h avec ".$Charge2."kg de charge</td></tr>";
		elseif($Vit_mini_chg_2 < $Vit_mini_chg_1)
			echo "<tr><td align='left'>Vitesse minimale d'atterrissage en charge</td><td bgcolor='LightCoral'>".$Vit_mini_chg_1."km/h avec ".$Charge1."kg de charge</td><td bgcolor='lightgreen'>".$Vit_mini_chg_2."km/h avec ".$Charge2."kg de charge</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse minimale d'atterrissage en charge</td><td bgcolor='lightgreen'>".$Vit_mini_chg_1."km/h avec ".$Charge1."kg de charge</td><td bgcolor='LightCoral'>".$Vit_mini_chg_2."km/h avec ".$Charge2."kg de charge</td></tr>";
		if($Dist_landing_2 == $Dist_landing_1)
			echo "<tr><td align='left'>Distance d'atterrissage à vide,sans volets</td><td bgcolor='lightyellow'>".$Dist_landing_1."m à ".$Vit_mini_1."km/h</td><td bgcolor='lightyellow'>".$Dist_landing_2."m à ".$Vit_mini_2."km/h</td></tr>";
		elseif($Dist_landing_2 < $Dist_landing_1)
			echo "<tr><td align='left'>Distance d'atterrissage à vide,sans volets</td><td bgcolor='LightCoral'>".$Dist_landing_1."m à ".$Vit_mini_1."km/h</td><td bgcolor='lightgreen'>".$Dist_landing_2."m à ".$Vit_mini_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Distance d'atterrissage à vide,sans volets</td><td bgcolor='lightgreen'>".$Dist_landing_1."m à ".$Vit_mini_1."km/h</td><td bgcolor='LightCoral'>".$Dist_landing_2."m à ".$Vit_mini_2."km/h</td></tr>";
		if($Dist_landing_2_flaps3 == $Dist_landing_1_flaps3)
			echo "<tr><td align='left'>Distance d'atterrissage à vide,3 crans de volets</td><td bgcolor='lightyellow'>".$Dist_landing_1_flaps3."m à ".$Vit_mini_flaps_1."km/h</td><td bgcolor='lightyellow'>".$Dist_landing_2_flaps3."m à ".$Vit_mini_flaps_2."km/h</td></tr>";
		elseif($Dist_landing_2_flaps3 < $Dist_landing_1_flaps3)
			echo "<tr><td align='left'>Distance d'atterrissage à vide,3 crans de volets</td><td bgcolor='LightCoral'>".$Dist_landing_1_flaps3."m à ".$Vit_mini_flaps_1."km/h</td><td bgcolor='lightgreen'>".$Dist_landing_2_flaps3."m à ".$Vit_mini_flaps_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Distance d'atterrissage à vide,3 crans de volets</td><td bgcolor='lightgreen'>".$Dist_landing_1_flaps3."m à ".$Vit_mini_flaps_1."km/h</td><td bgcolor='LightCoral'>".$Dist_landing_2_flaps3."m à ".$Vit_mini_flaps_2."km/h</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";
		if($Degats_chass_1_2 == $Degats_chass_1_1)
			echo "<tr><td align='left'>Dégâts maximum contre les chasseurs</td><td bgcolor='lightyellow'>".$Degats_chass_1_1."</td><td bgcolor='lightyellow'>".$Degats_chass_1_2."</td></tr>";
		elseif($Degats_chass_1_2 > $Degats_chass_1_1)
			echo "<tr><td align='left'>Dégâts maximum contre les chasseurs</td><td bgcolor='LightCoral'>".$Degats_chass_1_1."</td><td bgcolor='lightgreen'>".$Degats_chass_1_2."</td></tr>";
		else
			echo "<tr><td align='left'>Dégâts maximum contre les chasseurs</td><td bgcolor='lightgreen'>".$Degats_chass_1_1."</td><td bgcolor='LightCoral'>".$Degats_chass_1_2."</td></tr>";
		if($Degats_tot_2 == $Degats_tot_1)
			echo "<tr><td align='left'>Dégâts maximum contre les autres avions</td><td bgcolor='lightyellow'>".$Degats_tot_1."</td><td bgcolor='lightyellow'>".$Degats_tot_2."</td></tr>";
		elseif($Degats_tot_2 > $Degats_tot_1)
			echo "<tr><td align='left'>Dégâts maximum contre les autres avions</td><td bgcolor='LightCoral'>".$Degats_tot_1."</td><td bgcolor='lightgreen'>".$Degats_tot_2."</td></tr>";
		else
			echo "<tr><td align='left'>Dégâts maximum contre les autres avions</td><td bgcolor='lightgreen'>".$Degats_tot_1."</td><td bgcolor='LightCoral'>".$Degats_tot_2."</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";
		if($Perf_12000_2 == $Perf_12000_1)
			echo "<tr><td align='left'>Performance à 12000m</td><td bgcolor='lightyellow'>".$Perf_12000_1."</td><td bgcolor='lightyellow'>".$Perf_12000_2."</td></tr>";
		elseif($Perf_12000_2 > $Perf_12000_1)
			echo "<tr><td align='left'>Performance à 12000m</td><td bgcolor='LightCoral'>".$Perf_12000_1."</td><td bgcolor='lightgreen'>".$Perf_12000_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance à 12000m</td><td bgcolor='lightgreen'>".$Perf_12000_1."</td><td bgcolor='LightCoral'>".$Perf_12000_2."</td></tr>";
		if($Perf_9000_2 == $Perf_9000_1)
			echo "<tr><td align='left'>Performance à 9000m</td><td bgcolor='lightyellow'>".$Perf_9000_1."</td><td bgcolor='lightyellow'>".$Perf_9000_2."</td></tr>";
		elseif($Perf_9000_2 > $Perf_9000_1)
			echo "<tr><td align='left'>Performance à 9000m</td><td bgcolor='LightCoral'>".$Perf_9000_1."</td><td bgcolor='lightgreen'>".$Perf_9000_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance à 9000m</td><td bgcolor='lightgreen'>".$Perf_9000_1."</td><td bgcolor='LightCoral'>".$Perf_9000_2."</td></tr>";
		if($Perf_9000_c_2 == $Perf_9000_c_1)
			echo "<tr><td align='left'>Boom & Zoom à 9000m</td><td bgcolor='lightyellow'>".$Perf_9000_c_1."</td><td bgcolor='lightyellow'>".$Perf_9000_c_2."</td></tr>";
		elseif($Perf_9000_c_2 > $Perf_9000_c_1)
			echo "<tr><td align='left'>Boom & Zoom à 9000m</td><td bgcolor='LightCoral'>".$Perf_9000_c_1."</td><td bgcolor='lightgreen'>".$Perf_9000_c_2."</td></tr>";
		else
			echo "<tr><td align='left'>Boom & Zoom à 9000m</td><td bgcolor='lightgreen'>".$Perf_9000_c_1."</td><td bgcolor='LightCoral'>".$Perf_9000_c_2."</td></tr>";
		if($Perf_9000_v_2 == $Perf_9000_v_1)
			echo "<tr><td align='left'>Dogfight à 9000m</td><td bgcolor='lightyellow'>".$Perf_9000_v_1."</td><td bgcolor='lightyellow'>".$Perf_9000_v_2."</td></tr>";
		elseif($Perf_9000_v_2 > $Perf_9000_v_1)
			echo "<tr><td align='left'>Dogfight à 9000m</td><td bgcolor='LightCoral'>".$Perf_9000_v_1."</td><td bgcolor='lightgreen'>".$Perf_9000_v_2."</td></tr>";
		else
			echo "<tr><td align='left'>Dogfight à 9000m</td><td bgcolor='lightgreen'>".$Perf_9000_v_1."</td><td bgcolor='LightCoral'>".$Perf_9000_v_2."</td></tr>";
		if($Perf_9000_f_2 == $Perf_9000_f_1)
			echo "<tr><td align='left'>Performance de fuite à 9000m</td><td bgcolor='lightyellow'>".$Perf_9000_f_1."</td><td bgcolor='lightyellow'>".$Perf_9000_f_2."</td></tr>";
		elseif($Perf_9000_f_2 > $Perf_9000_f_1)
			echo "<tr><td align='left'>Performance de fuite à 9000m</td><td bgcolor='LightCoral'>".$Perf_9000_f_1."</td><td bgcolor='lightgreen'>".$Perf_9000_f_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance de fuite à 9000m</td><td bgcolor='lightgreen'>".$Perf_9000_f_1."</td><td bgcolor='LightCoral'>".$Perf_9000_f_2."</td></tr>";
		if($Perf_7000_2 == $Perf_7000_1)
			echo "<tr><td align='left'>Performance à 7000m</td><td bgcolor='lightyellow'>".$Perf_7000_1."</td><td bgcolor='lightyellow'>".$Perf_7000_2."</td></tr>";
		elseif($Perf_7000_2 > $Perf_7000_1)
			echo "<tr><td align='left'>Performance à 7000m</td><td bgcolor='LightCoral'>".$Perf_7000_1."</td><td bgcolor='lightgreen'>".$Perf_7000_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance à 7000m</td><td bgcolor='lightgreen'>".$Perf_7000_1."</td><td bgcolor='LightCoral'>".$Perf_7000_2."</td></tr>";
		if($Perf_7000_c_2 == $Perf_7000_c_1)
			echo "<tr><td align='left'>Boom & Zoom à 7000m</td><td bgcolor='lightyellow'>".$Perf_7000_c_1."</td><td bgcolor='lightyellow'>".$Perf_7000_c_2."</td></tr>";
		elseif($Perf_7000_c_2 > $Perf_7000_c_1)
			echo "<tr><td align='left'>Boom & Zoom à 7000m</td><td bgcolor='LightCoral'>".$Perf_7000_c_1."</td><td bgcolor='lightgreen'>".$Perf_7000_c_2."</td></tr>";
		else
			echo "<tr><td align='left'>Boom & Zoom à 7000m</td><td bgcolor='lightgreen'>".$Perf_7000_c_1."</td><td bgcolor='LightCoral'>".$Perf_7000_c_2."</td></tr>";
		if($Perf_7000_v_2 == $Perf_7000_v_1)
			echo "<tr><td align='left'>Dogfight à 7000m</td><td bgcolor='lightyellow'>".$Perf_7000_v_1."</td><td bgcolor='lightyellow'>".$Perf_7000_v_2."</td></tr>";
		elseif($Perf_7000_v_2 > $Perf_7000_v_1)
			echo "<tr><td align='left'>Dogfight à 7000m</td><td bgcolor='LightCoral'>".$Perf_7000_v_1."</td><td bgcolor='lightgreen'>".$Perf_7000_v_2."</td></tr>";
		else
			echo "<tr><td align='left'>Dogfight à 7000m</td><td bgcolor='lightgreen'>".$Perf_7000_v_1."</td><td bgcolor='LightCoral'>".$Perf_7000_v_2."</td></tr>";
		if($Perf_7000_f_2 == $Perf_7000_f_1)
			echo "<tr><td align='left'>Performance de fuite à 7000m</td><td bgcolor='lightyellow'>".$Perf_7000_f_1."</td><td bgcolor='lightyellow'>".$Perf_7000_f_2."</td></tr>";
		elseif($Perf_7000_f_2 > $Perf_7000_f_1)
			echo "<tr><td align='left'>Performance de fuite à 7000m</td><td bgcolor='LightCoral'>".$Perf_7000_f_1."</td><td bgcolor='lightgreen'>".$Perf_7000_f_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance de fuite à 7000m</td><td bgcolor='lightgreen'>".$Perf_7000_f_1."</td><td bgcolor='LightCoral'>".$Perf_7000_f_2."</td></tr>";
		if($Perf_5000_2 == $Perf_5000_1)
			echo "<tr><td align='left'>Performance à 5000m</td><td bgcolor='lightyellow'>".$Perf_5000_1."</td><td bgcolor='lightyellow'>".$Perf_5000_2."</td></tr>";
		elseif($Perf_5000_2 > $Perf_5000_1)
			echo "<tr><td align='left'>Performance à 5000m</td><td bgcolor='LightCoral'>".$Perf_5000_1."</td><td bgcolor='lightgreen'>".$Perf_5000_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance à 5000m</td><td bgcolor='lightgreen'>".$Perf_5000_1."</td><td bgcolor='LightCoral'>".$Perf_5000_2."</td></tr>";
		if($Perf_5000_c_2 == $Perf_5000_c_1)
			echo "<tr><td align='left'>Boom & Zoom à 5000m</td><td bgcolor='lightyellow'>".$Perf_5000_c_1."</td><td bgcolor='lightyellow'>".$Perf_5000_c_2."</td></tr>";
		elseif($Perf_5000_c_2 > $Perf_5000_c_1)
			echo "<tr><td align='left'>Boom & Zoom à 5000m</td><td bgcolor='LightCoral'>".$Perf_5000_c_1."</td><td bgcolor='lightgreen'>".$Perf_5000_c_2."</td></tr>";
		else
			echo "<tr><td align='left'>Boom & Zoom à 5000m</td><td bgcolor='lightgreen'>".$Perf_5000_c_1."</td><td bgcolor='LightCoral'>".$Perf_5000_c_2."</td></tr>";
		if($Perf_5000_v_2 == $Perf_5000_v_1)
			echo "<tr><td align='left'>Dogfight à 5000m</td><td bgcolor='lightyellow'>".$Perf_5000_v_1."</td><td bgcolor='lightyellow'>".$Perf_5000_v_2."</td></tr>";
		elseif($Perf_5000_v_2 > $Perf_5000_v_1)
			echo "<tr><td align='left'>Dogfight à 5000m</td><td bgcolor='LightCoral'>".$Perf_5000_v_1."</td><td bgcolor='lightgreen'>".$Perf_5000_v_2."</td></tr>";
		else
			echo "<tr><td align='left'>Dogfight à 5000m</td><td bgcolor='lightgreen'>".$Perf_5000_v_1."</td><td bgcolor='LightCoral'>".$Perf_5000_v_2."</td></tr>";
		if($Perf_5000_f_2 == $Perf_5000_f_1)
			echo "<tr><td align='left'>Performance de fuite à 5000m</td><td bgcolor='lightyellow'>".$Perf_5000_f_1."</td><td bgcolor='lightyellow'>".$Perf_5000_f_2."</td></tr>";
		elseif($Perf_5000_f_2 > $Perf_5000_f_1)
			echo "<tr><td align='left'>Performance de fuite à 5000m</td><td bgcolor='LightCoral'>".$Perf_5000_f_1."</td><td bgcolor='lightgreen'>".$Perf_5000_f_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance de fuite à 5000m</td><td bgcolor='lightgreen'>".$Perf_5000_f_1."</td><td bgcolor='LightCoral'>".$Perf_5000_f_2."</td></tr>";
		if($Perf_3000_2 == $Perf_3000_1)
			echo "<tr><td align='left'>Performance à 3000m</td><td bgcolor='lightyellow'>".$Perf_3000_1."</td><td bgcolor='lightyellow'>".$Perf_3000_2."</td></tr>";
		elseif($Perf_3000_2 > $Perf_3000_1)
			echo "<tr><td align='left'>Performance à 3000m</td><td bgcolor='LightCoral'>".$Perf_3000_1."</td><td bgcolor='lightgreen'>".$Perf_3000_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance à 3000m</td><td bgcolor='lightgreen'>".$Perf_3000_1."</td><td bgcolor='LightCoral'>".$Perf_3000_2."</td></tr>";
		if($Perf_3000_c_2 == $Perf_3000_c_1)
			echo "<tr><td align='left'>Boom & Zoom à 3000m</td><td bgcolor='lightyellow'>".$Perf_3000_c_1."</td><td bgcolor='lightyellow'>".$Perf_3000_c_2."</td></tr>";
		elseif($Perf_3000_c_2 > $Perf_3000_c_1)
			echo "<tr><td align='left'>Boom & Zoom à 3000m</td><td bgcolor='LightCoral'>".$Perf_3000_c_1."</td><td bgcolor='lightgreen'>".$Perf_3000_c_2."</td></tr>";
		else
			echo "<tr><td align='left'>Boom & Zoom à 3000m</td><td bgcolor='lightgreen'>".$Perf_3000_c_1."</td><td bgcolor='LightCoral'>".$Perf_3000_c_2."</td></tr>";
		if($Perf_3000_v_2 == $Perf_3000_v_1)
			echo "<tr><td align='left'>Dogfight à 3000m</td><td bgcolor='lightyellow'>".$Perf_3000_v_1."</td><td bgcolor='lightyellow'>".$Perf_3000_v_2."</td></tr>";
		elseif($Perf_3000_v_2 > $Perf_3000_v_1)
			echo "<tr><td align='left'>Dogfight à 3000m</td><td bgcolor='LightCoral'>".$Perf_3000_v_1."</td><td bgcolor='lightgreen'>".$Perf_3000_v_2."</td></tr>";
		else
			echo "<tr><td align='left'>Dogfight à 3000m</td><td bgcolor='lightgreen'>".$Perf_3000_v_1."</td><td bgcolor='LightCoral'>".$Perf_3000_v_2."</td></tr>";
		if($Perf_3000_f_2 == $Perf_3000_f_1)
			echo "<tr><td align='left'>Performance de fuite à 3000m</td><td bgcolor='lightyellow'>".$Perf_3000_f_1."</td><td bgcolor='lightyellow'>".$Perf_3000_f_2."</td></tr>";
		elseif($Perf_3000_f_2 > $Perf_3000_f_1)
			echo "<tr><td align='left'>Performance de fuite à 3000m</td><td bgcolor='LightCoral'>".$Perf_3000_f_1."</td><td bgcolor='lightgreen'>".$Perf_3000_f_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance de fuite à 3000m</td><td bgcolor='lightgreen'>".$Perf_3000_f_1."</td><td bgcolor='LightCoral'>".$Perf_3000_f_2."</td></tr>";
		if($Perf_500_2 ==$Perf_500_1)
			echo "<tr><td align='left'>Performance à 500m</td><td bgcolor='lightyellow'>".$Perf_500_1."</td><td bgcolor='lightyellow'>".$Perf_500_2."</td></tr>";
		elseif($Perf_500_2 >$Perf_500_1)
			echo "<tr><td align='left'>Performance à 500m</td><td bgcolor='LightCoral'>".$Perf_500_1."</td><td bgcolor='lightgreen'>".$Perf_500_2."</td></tr>";
		else
			echo "<tr><td align='left'>Performance à 500m</td><td bgcolor='lightgreen'>".$Perf_500_1."</td><td bgcolor='LightCoral'>".$Perf_500_2."</td></tr>";
		if($Perf_500_v_2 == $Perf_500_v_1)
			echo "<tr><td align='left'>Dogfight à 500m</td><td bgcolor='lightyellow'>".$Perf_500_v_1."</td><td bgcolor='lightyellow'>".$Perf_500_v_2."</td></tr>";
		elseif($Perf_500_v_2 > $Perf_500_v_1)
			echo "<tr><td align='left'>Dogfight à 500m</td><td bgcolor='LightCoral'>".$Perf_500_v_1."</td><td bgcolor='lightgreen'>".$Perf_500_v_2."</td></tr>";
		else
			echo "<tr><td align='left'>Dogfight à 500m</td><td bgcolor='lightgreen'>".$Perf_500_v_1."</td><td bgcolor='LightCoral'>".$Perf_500_v_2."</td></tr>";
		echo '</table>';
	}
}
else
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
?>