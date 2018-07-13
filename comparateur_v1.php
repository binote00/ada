<?
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID xor $OfficierEMID)
{
	include_once('./jfv_include.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_ground.inc.php');
		include_once('./menu_infos.php');
		$Veh1=Insec($_POST['avion1']);
		$Veh2=Insec($_POST['avion2']);		
		function Aut_max($Autonomie,$Naval=0)
		{
			if($Autonomie >250 and $Naval ==0)$Autonomie=250;
			return $Autonomie;
		}		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Veh1'");
		$result2=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Veh2'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{			
				$Type_1=$data['Type'];
				$Pays_1=$data['Pays'];
				$Nom_1=$data['Nom'];
				$Engagement_1=$data['Date'];
				$Vitesse_1=$data['Vitesse'];				
				$Blindage_f_1=$data['Blindage_f'];
				$Blindage_l_1=$data['Blindage_l'];
				$Blindage_a_1=$data['Blindage_a'];
				$Blindage_t_1=$data['Blindage_t'];
				$Blindage_p_1=$data['Blindage_p'];
				$Robustesse_1=$data['HP'];
				$Detection_1=$data['Detection'];
				$Vis_1=$data['Taille'];	
				$Portee_1=$data['Portee'];
				$Charge_1=$data['Charge'];
				$Autonomie_1=$data['Fuel'];
				$Fiabilite_1=$data['Fiabilite'];
				$mobile1=$data['mobile'];
				$Allonge_1=round($data['Vitesse']*0.05,2);				
				$Aut_noeud_1=$Autonomie_1*2;
				$Aut_col_1=Get_LandSpeed($data['Fuel'],$data['mobile'],1);
				$Aut_for_1=Get_LandSpeed($data['Fuel'],$data['mobile'],2); //2,3
				$Aut_mont_1=Get_LandSpeed($data['Fuel'],$data['mobile'],4,$Type_1); //4,5,9,10
				$Aut_urb_1=Get_LandSpeed($data['Fuel'],$data['mobile'],7);
				$Aut_des_1=Get_LandSpeed($data['Fuel'],$data['mobile'],8);
				$Aut_mar_1=Get_LandSpeed($data['Fuel'],$data['mobile'],9);
				$Vit_col_1=Get_LandSpeed($data['Vitesse'],$data['mobile'],1);
				$Vit_for_1=Get_LandSpeed($data['Vitesse'],$data['mobile'],2); //2,3
				$Vit_mont_1=Get_LandSpeed($data['Vitesse'],$data['mobile'],4,$Type_1); //4,5,9,10
				$Vit_urb_1=Get_LandSpeed($data['Vitesse'],$data['mobile'],7);
				$Vit_des_1=Get_LandSpeed($data['Vitesse'],$data['mobile'],8);
				$Vit_mar_1=Get_LandSpeed($data['Vitesse'],$data['mobile'],9);
				$Conso_1=$data['Conso'];
				$Conso_col_1=Get_LandConso(1,$data['Conso']);
				$Conso_for_1=Get_LandConso(2,$data['Conso']);
				$Conso_mont_1=Get_LandConso(4,$data['Conso']);
				$Conso_des_1=Get_LandConso(8,$data['Conso']);
				$Conso_mar_1=Get_LandConso(9,$data['Conso']);				
				if($data['mobile'] ==1)
					$Prop_1="Roues";
				elseif($data['mobile'] ==2)
					$Prop_1="Chenilles";
				elseif($data['mobile'] ==6)
					$Prop_1="Roues 4x4";
				elseif($data['mobile'] ==7)
				{
					$Prop_1="Monté";
					$Allonge_1*=2;
				}
				elseif($data['mobile'] ==4)
					$Prop_1="Rail";
				elseif($data['mobile'] ==5)
				{
					$Prop_1="Maritime";
					$Naval_1=1;
					$Aut_noeud_1=0;
				}
				else
					$Prop_1="Non motorisé";
				if($data['Carbu_ID'] ==1)
					$Carbu_1="Diesel";
				elseif($data['Carbu_ID'] ==87)
					$Carbu_1="Essence";
				else
					$Carbu_1="Moral";	
				$Bonus_Tactique_1=($data['Radio']*5)+($data['Tourelle']*5);
				if($data['Arme_Inf'])
				{
					$Inf_Cal_1=round(GetData("Armes","ID",$data['Arme_Inf'],"Calibre")).'mm';
					$Inf_Nom_1=GetData("Armes","ID",$data['Arme_Inf'],"Nom");
					$Inf_Dg_1=GetData("Armes","ID",$data['Arme_Inf'],"Degats") * GetData("Armes","ID",$data['Arme_Inf'],"Multi");
				}
				elseif($data['Arme_AA3'])
				{
					$Inf_Dg_1=GetData("Armes","ID",$data['Arme_AA3'],"Degats") * GetData("Armes","ID",$data['Arme_AA2'],"Multi");
					$Inf_Cal_1=round(GetData("Armes","ID",$data['Arme_AA3'],"Calibre")).'mm';
					$Inf_Nom_1=GetData("Armes","ID",$data['Arme_AA3'],"Nom");
				}				
				if($data['Arme_Art'])
				{
					$Art_Dg_1=GetData("Armes","ID",$data['Arme_Art'],"Degats") * GetData("Armes","ID",$data['Arme_Art'],"Multi");
					$Art_Cal_1=round(GetData("Armes","ID",$data['Arme_Art'],"Calibre")).'mm';
					$Art_Nom_1=GetData("Armes","ID",$data['Arme_Art'],"Nom");
				}
				elseif($data['Arme_AA2'])
				{
					$Art_Dg_1=GetData("Armes","ID",$data['Arme_AA2'],"Degats") * GetData("Armes","ID",$data['Arme_AA2'],"Multi");
					$Art_Cal_1=round(GetData("Armes","ID",$data['Arme_AA2'],"Calibre")).'mm';
					$Art_Nom_1=GetData("Armes","ID",$data['Arme_AA2'],"Nom");
				}				
				if($data['Arme_AT'])
				{
					$AT_Dg_1=GetData("Armes","ID",$data['Arme_AT'],"Degats") * GetData("Armes","ID",$data['Arme_AT'],"Multi");
					$AT_Cal_1=round(GetData("Armes","ID",$data['Arme_AT'],"Calibre")).'mm';
					$AT_Nom_1=GetData("Armes","ID",$data['Arme_AT'],"Nom");
					$AT_Perf_1=GetData("Armes","ID",$data['Arme_AT'],"Perf");
				}				
				if($data['Arme_AA'])
				{
					$AA_Dg_1=GetData("Armes","ID",$data['Arme_AA'],"Degats") * GetData("Armes","ID",$data['Arme_AA'],"Multi");
					$AA_Cal_1=round(GetData("Armes","ID",$data['Arme_AA'],"Calibre")).'mm';
					$AA_Nom_1=GetData("Armes","ID",$data['Arme_AA'],"Nom");
				}
			}
			mysqli_free_result($result);
			unset($data);
		}				
		//Avion 2
		if($result2)
		{
			while ($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Type_2=$data['Type'];
				$Pays_2=$data['Pays'];
				$Nom_2=$data['Nom'];
				$Engagement_2=$data['Date'];
				$Vitesse_2=$data['Vitesse'];
				$Blindage_f_2=$data['Blindage_f'];
				$Blindage_l_2=$data['Blindage_l'];
				$Blindage_a_2=$data['Blindage_a'];
				$Blindage_t_2=$data['Blindage_t'];
				$Blindage_p_2=$data['Blindage_p'];
				$Robustesse_2=$data['HP'];				
				$Detection_2=$data['Detection'];
				$Vis_2=$data['Taille'];	
				$Portee_2=$data['Portee'];
				$Charge_2=$data['Charge'];
				$Autonomie_2=$data['Fuel'];
				$Fiabilite_2=$data['Fiabilite'];
				$mobile2=$data['mobile'];
				$Allonge_2=round($data['Vitesse']*0.05,2);				
				$Aut_noeud_2=$Autonomie_2*2;
				$Aut_col_2=Get_LandSpeed($data['Fuel'],$data['mobile'],1);
				$Aut_for_2=Get_LandSpeed($data['Fuel'],$data['mobile'],2); //2,3
				$Aut_mont_2=Get_LandSpeed($data['Fuel'],$data['mobile'],4,$Type_1); //4,5,9,10
				$Aut_urb_2=Get_LandSpeed($data['Fuel'],$data['mobile'],7);
				$Aut_des_2=Get_LandSpeed($data['Fuel'],$data['mobile'],8);
				$Aut_mar_2=Get_LandSpeed($data['Fuel'],$data['mobile'],9);
				$Vit_col_2=Get_LandSpeed($data['Vitesse'],$data['mobile'],1);
				$Vit_for_2=Get_LandSpeed($data['Vitesse'],$data['mobile'],2); //2,3
				$Vit_mont_2=Get_LandSpeed($data['Vitesse'],$data['mobile'],4,$Type_2); //4,5,9,10
				$Vit_urb_2=Get_LandSpeed($data['Vitesse'],$data['mobile'],7);
				$Vit_des_2=Get_LandSpeed($data['Vitesse'],$data['mobile'],8);
				$Vit_mar_2=Get_LandSpeed($data['Vitesse'],$data['mobile'],9);
				$Conso_2=$data['Conso'];
				$Conso_col_2=Get_LandConso(1,$data['Conso']);
				$Conso_for_2=Get_LandConso(2,$data['Conso']);
				$Conso_mont_2=Get_LandConso(4,$data['Conso']);
				$Conso_des_2=Get_LandConso(8,$data['Conso']);
				$Conso_mar_2=Get_LandConso(9,$data['Conso']);				
				if($data['mobile'] ==1)
					$Prop_2="Roues";
				elseif($data['mobile'] ==2)
					$Prop_2="Chenilles";
				elseif($data['mobile'] ==6)
					$Prop_2="Roues 4x4";
				elseif($data['mobile'] ==7)
				{
					$Prop_2="Monté";
					$Allonge_2*=2;
				}
				elseif($data['mobile'] ==4)
					$Prop_2="Rail";
				elseif($data['mobile'] ==5)
				{
					$Prop_2="Maritime";
					$Naval_2=1;
					$Aut_noeud_2=0;
				}
				else
					$Prop_2="Non motorisé";				
				if($data['Carbu_ID'] ==1)
					$Carbu_2="Diesel";
				elseif($data['Carbu_ID'] ==87)
					$Carbu_2="Essence";
				else
					$Carbu_2="Moral";		
				$Bonus_Tactique_2=($data['Radio']*5)+($data['Tourelle']*5);
				if($data['Arme_Inf'])
				{
					$Inf_Cal_2=round(GetData("Armes","ID",$data['Arme_Inf'],"Calibre")).'mm';
					$Inf_Nom_2=GetData("Armes","ID",$data['Arme_Inf'],"Nom");
					$Inf_Dg_2=GetData("Armes","ID",$data['Arme_Inf'],"Degats") * GetData("Armes","ID",$data['Arme_Inf'],"Multi");
				}
				elseif($data['Arme_AA3'])
				{
					$Inf_Dg_2=GetData("Armes","ID",$data['Arme_AA3'],"Degats") * GetData("Armes","ID",$data['Arme_AA3'],"Multi");
					$Inf_Cal_2=round(GetData("Armes","ID",$data['Arme_AA3'],"Calibre")).'mm';
					$Inf_Nom_2=GetData("Armes","ID",$data['Arme_AA3'],"Nom");
				}				
				if($data['Arme_Art'])
				{
					$Art_Dg_2=GetData("Armes","ID",$data['Arme_Art'],"Degats") * GetData("Armes","ID",$data['Arme_Art'],"Multi");
					$Art_Cal_2=round(GetData("Armes","ID",$data['Arme_Art'],"Calibre")).'mm';
					$Art_Nom_2=GetData("Armes","ID",$data['Arme_Art'],"Nom");
				}
				elseif($data['Arme_AA2'])
				{
					$Art_Dg_2=GetData("Armes","ID",$data['Arme_AA2'],"Degats") * GetData("Armes","ID",$data['Arme_AA2'],"Multi");
					$Art_Cal_2=round(GetData("Armes","ID",$data['Arme_AA2'],"Calibre")).'mm';
					$Art_Nom_2=GetData("Armes","ID",$data['Arme_AA2'],"Nom");
				}				
				if($data['Arme_AT'])
				{
					$AT_Dg_2=GetData("Armes","ID",$data['Arme_AT'],"Degats") * GetData("Armes","ID",$data['Arme_AT'],"Multi");
					$AT_Cal_2=round(GetData("Armes","ID",$data['Arme_AT'],"Calibre")).'mm';
					$AT_Nom_2=GetData("Armes","ID",$data['Arme_AT'],"Nom");
					$AT_Perf_2=GetData("Armes","ID",$data['Arme_AT'],"Perf");
				}				
				if($data['Arme_AA'])
				{
					$AA_Dg_2=GetData("Armes","ID",$data['Arme_AA'],"Degats") * GetData("Armes","ID",$data['Arme_AA'],"Multi");
					$AA_Cal_2=round(GetData("Armes","ID",$data['Arme_AA'],"Calibre")).'mm';
					$AA_Nom_2=GetData("Armes","ID",$data['Arme_AA'],"Nom");
				}
			}
			mysqli_free_result($result2);
			unset($data);
		}		
		function HPTxt($Robustesse,$mobile)
		{
			if($mobile ==5)
				return "Inconnu";
			else
				return $Robustesse;
		}
		//Output
		echo "<h1>Troupes à comparer</h1><div style='overflow:auto;'><table class='table table-hover'>
		<thead><tr><th></th><th>".$Nom_1."</th><th>".$Nom_2."</th></tr></thead>
		<tr><th></th><th><img src='images/".$Pays_1."20.gif'></th><th><img src='images/".$Pays_2."20.gif'></th></tr>
		<tr><th></th><th><img src='images/vehicules/vehicule".$Veh1.".gif' title='".$Nom_1."'></th><th><img src='images/vehicules/vehicule".$Veh2.".gif' title='".$Nom_2."'></th></tr>
		<tr><td align='left'>Service</td><td>".$Engagement_1."</td><td>".$Engagement_2."</td></tr>";
		echo "<tr><td align='left'>Propulsion</td><td>".$Prop_1."</td><td>".$Prop_2."</td></tr>";
		echo "<tr><td align='left'>Carburant</td><td>".$Carbu_1."</td><td>".$Carbu_2."</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";	
		echo "<tr><th align='left'>Armement de base</th><th>".$Inf_Nom_1."</th><th>".$Inf_Nom_2."</th></tr>";
		echo "<tr><td align='left'>Calibre</td><td>".$Inf_Cal_1."</td><td>".$Inf_Cal_2."</td></tr>";
		echo "<tr><td align='left'>Dégâts max</td><td>".$Inf_Dg_1."</td><td>".$Inf_Dg_2."</td></tr>";
		if($Art_Cal_1 or $Art_Cal_2)
		{
			echo "<tr><th align='left'>Armement de soutien</th><th>".$Art_Nom_1."</th><th>".$Art_Nom_2."</th></tr>";
			echo "<tr><td align='left'>Calibre</td><td>".$Art_Cal_1."</td><td>".$Art_Cal_2."</td></tr>";
			echo "<tr><td align='left'>Dégâts max</td><td>".$Art_Dg_1."</td><td>".$Art_Dg_2."</td></tr>";
		}
		if($AT_Cal_1 or $AT_Cal_2)
		{
			if(!$AT_Perf_1)
				$AT_Perf_1=0;
			if(!$AT_Perf_2)
				$AT_Perf_2=0;
			if($AT_Cal_1 >19)
			{
				$APDS1=true;
				$AT_Perf_1_apds=($AT_Perf_1*1.5)."mm";
			}
			else
				$APDS1=false;
			if($AT_Cal_2 >19)
			{
				$APDS2=true;
				$AT_Perf_2_apds=($AT_Perf_2*1.5)."mm";
			}
			else
				$APDS2=false;
			echo "<tr><th align='left'>Armement anti-char</th><th>".$AT_Nom_1."</th><th>".$AT_Nom_2."</th></tr>";
			echo "<tr><td align='left'>Calibre</td><td>".$AT_Cal_1."</td><td>".$AT_Cal_2."</td></tr>";
			echo "<tr><td align='left'>Dégâts max</td><td>".$AT_Dg_1."</td><td>".$AT_Dg_2."</td></tr>";
			echo "<tr><td align='left'>Pénétration à 500m AP/APHE</td><td>".$AT_Perf_1."mm</td><td>".$AT_Perf_2."mm</td></tr>";
			if($APDS1 or $APDS2)
				echo "<tr><td align='left'>Pénétration à 500m APCR/APDS</td><td>".$AT_Perf_1_apds."</td><td>".$AT_Perf_2_apds."</td></tr>";
			if($Portee_1 >999 or $Portee_2 >999)
			{
				echo "<tr><td align='left'>Pénétration à 1000m AP/APHE</td><td>".Get_Perf(1000,$AT_Cal_1,$AT_Perf_1,$Portee_1)."mm</td><td>".Get_Perf(1000,$AT_Cal_2,$AT_Perf_2,$Portee_2)."mm</td></tr>";
				if($APDS1 or $APDS2)
					echo "<tr><td align='left'>Pénétration à 1000m APDS</td><td>".Get_Perf(1000,$AT_Cal_1,$AT_Perf_1_apds,$Portee_1)."mm</td><td>".Get_Perf(1000,$AT_Cal_2,$AT_Perf_2_apds,$Portee_2)."mm</td></tr>";
			}
			if($Portee_1 >1499 or $Portee_2 >1499)
			{
				echo "<tr><td align='left'>Pénétration à 1500m AP/APHE</td><td>".Get_Perf(1500,$AT_Cal_1,$AT_Perf_1,$Portee_1)."mm</td><td>".Get_Perf(1500,$AT_Cal_2,$AT_Perf_2,$Portee_2)."mm</td></tr>";
				if($APDS1 or $APDS2)
					echo "<tr><td align='left'>Pénétration à 1500m APDS</td><td>".Get_Perf(1500,$AT_Cal_1,$AT_Perf_1_apds,$Portee_1)."mm</td><td>".Get_Perf(1500,$AT_Cal_2,$AT_Perf_2_apds,$Portee_2)."mm</td></tr>";
			}
		}
		echo "<tr><th align='left'>Armement anti-aérien</th><th>".$AA_Nom_1."</th><th>".$AA_Nom_2."</th></tr>";
		echo "<tr><td align='left'>Calibre</td><td>".$AA_Cal_1."</td><td>".$AA_Cal_2."</td></tr>";
		echo "<tr><td align='left'>Dégâts max</td><td>".$AA_Dg_1."</td><td>".$AA_Dg_2."</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";	
		if($Portee_2 == $Portee_1)
			echo "<tr><td align='left'>Portée de tir max</td><td bgcolor='lightyellow'>".$Portee_1."m</td><td bgcolor='lightyellow'>".$Portee_2."m</td></tr>";
		elseif($Portee_2 > $Portee_1)
			echo "<tr><td align='left'>Portée de tir max</td><td bgcolor='LightCoral'>".$Portee_1."m</td><td bgcolor='lightgreen'>".$Portee_2."m</td></tr>";
		else
			echo "<tr><td align='left'>Portée de tir max</td><td bgcolor='lightgreen'>".$Portee_1."m</td><td bgcolor='LightCoral'>".$Portee_2."m</td></tr>";
		if($Allonge_2 == $Allonge_1)
			echo "<tr><td align='left'>Allonge max de raid</td><td bgcolor='lightyellow'>".$Allonge_1."km</td><td bgcolor='lightyellow'>".$Allonge_2."km</td></tr>";
		elseif($Allonge_2 > $Allonge_1)
			echo "<tr><td align='left'>Allonge max de raid</td><td bgcolor='LightCoral'>".$Allonge_1."km</td><td bgcolor='lightgreen'>".$Allonge_2."km</td></tr>";
		else
			echo "<tr><td align='left'>Allonge max de raid</td><td bgcolor='lightgreen'>".$Allonge_1."km</td><td bgcolor='LightCoral'>".$Allonge_2."km</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";	
		if($Robustesse_2 == $Robustesse_1)
			echo "<tr><td align='left'>Robustesse</td><td bgcolor='lightyellow'>".HPTxt($Robustesse_1,$mobile1)."</td><td bgcolor='lightyellow'>".HPTxt($Robustesse_2,$mobile2)."</td></tr>";
		elseif($Robustesse_2 > $Robustesse_1)
			echo "<tr><td align='left'>Robustesse</td><td bgcolor='LightCoral'>".HPTxt($Robustesse_1,$mobile1)."</td><td bgcolor='lightgreen'>".HPTxt($Robustesse_2,$mobile2)."</td></tr>";
		else
			echo "<tr><td align='left'>Robustesse</td><td bgcolor='lightgreen'>".HPTxt($Robustesse_1,$mobile1)."</td><td bgcolor='LightCoral'>".HPTxt($Robustesse_2,$mobile2)."</td></tr>";
		if($Blindage_f_2 == $Blindage_f_1)
			echo "<tr><td align='left'>Blindage frontal</td><td bgcolor='lightyellow'>".$Blindage_f_1."mm</td><td bgcolor='lightyellow'>".$Blindage_f_2."mm</td></tr>";
		elseif($Blindage_f_2 > $Blindage_f_1)
			echo "<tr><td align='left'>Blindage frontal</td><td bgcolor='LightCoral'>".$Blindage_f_1."mm</td><td bgcolor='lightgreen'>".$Blindage_f_2."mm</td></tr>";
		else
			echo "<tr><td align='left'>Blindage frontal</td><td bgcolor='lightgreen'>".$Blindage_f_1."mm</td><td bgcolor='LightCoral'>".$Blindage_f_2."mm</td></tr>";
		if($Blindage_l_2 == $Blindage_l_1)
			echo "<tr><td align='left'>Blindage latéral</td><td bgcolor='lightyellow'>".$Blindage_l_1."mm</td><td bgcolor='lightyellow'>".$Blindage_l_2."mm</td></tr>";
		elseif($Blindage_l_2 > $Blindage_l_1)
			echo "<tr><td align='left'>Blindage latéral</td><td bgcolor='LightCoral'>".$Blindage_l_1."mm</td><td bgcolor='lightgreen'>".$Blindage_l_2."mm</td></tr>";
		else
			echo "<tr><td align='left'>Blindage latéral</td><td bgcolor='lightgreen'>".$Blindage_l_1."mm</td><td bgcolor='LightCoral'>".$Blindage_l_2."mm</td></tr>";
		if($Blindage_a_2 == $Blindage_a_1)
			echo "<tr><td align='left'>Blindage arrière</td><td bgcolor='lightyellow'>".$Blindage_a_1."mm</td><td bgcolor='lightyellow'>".$Blindage_a_2."mm</td></tr>";
		elseif($Blindage_a_2 > $Blindage_a_1)
			echo "<tr><td align='left'>Blindage arrière</td><td bgcolor='LightCoral'>".$Blindage_a_1."mm</td><td bgcolor='lightgreen'>".$Blindage_a_2."mm</td></tr>";
		else
			echo "<tr><td align='left'>Blindage arrière</td><td bgcolor='lightgreen'>".$Blindage_a_1."mm</td><td bgcolor='LightCoral'>".$Blindage_a_2."mm</td></tr>";
		if($Blindage_t_2 == $Blindage_t_1)
			echo "<tr><td align='left'>Blindage toit</td><td bgcolor='lightyellow'>".$Blindage_t_1."mm</td><td bgcolor='lightyellow'>".$Blindage_t_2."mm</td></tr>";
		elseif($Blindage_t_2 > $Blindage_t_1)
			echo "<tr><td align='left'>Blindage toit</td><td bgcolor='LightCoral'>".$Blindage_t_1."mm</td><td bgcolor='lightgreen'>".$Blindage_t_2."mm</td></tr>";
		else
			echo "<tr><td align='left'>Blindage toit</td><td bgcolor='lightgreen'>".$Blindage_t_1."mm</td><td bgcolor='LightCoral'>".$Blindage_t_2."mm</td></tr>";
		if($Blindage_p_2 ==$Blindage_p_1)
			echo "<tr><td align='left'>Blindage plancher</td><td bgcolor='lightyellow'>".$Blindage_p_1."mm</td><td bgcolor='lightyellow'>".$Blindage_p_2."mm</td></tr>";
		elseif($Blindage_p_2 >$Blindage_p_1)
			echo "<tr><td align='left'>Blindage plancher</td><td bgcolor='LightCoral'>".$Blindage_p_1."mm</td><td bgcolor='lightgreen'>".$Blindage_p_2."mm</td></tr>";
		else
			echo "<tr><td align='left'>Blindage plancher</td><td bgcolor='lightgreen'>".$Blindage_p_1."mm</td><td bgcolor='LightCoral'>".$Blindage_p_2."mm</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";
		if($Autonomie_2 ==$Autonomie_1)
			echo "<tr><td align='left'>Autonomie max en plaine (sur mer pour les navires)</td><td bgcolor='lightyellow'>".Aut_max($Autonomie_1,$Naval_1)."km</td><td bgcolor='lightyellow'>".Aut_max($Autonomie_2,$Naval_2)."km</td></tr>";
		elseif($Autonomie_2 >$Autonomie_1)
			echo "<tr><td align='left'>Autonomie max en plaine (sur mer pour les navires)</td><td bgcolor='LightCoral'>".Aut_max($Autonomie_1,$Naval_1)."km</td><td bgcolor='lightgreen'>".Aut_max($Autonomie_2,$Naval_2)."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie max en plaine (sur mer pour les navires)</td><td bgcolor='lightgreen'>".Aut_max($Autonomie_1,$Naval_1)."km</td><td bgcolor='LightCoral'>".Aut_max($Autonomie_2,$Naval_2)."km</td></tr>";
		if($Aut_noeud_2 ==$Aut_noeud_1)
			echo "<tr><td align='left'>Autonomie max sur noeuds routiers</td><td bgcolor='lightyellow'>".Aut_max($Aut_noeud_1)."km</td><td bgcolor='lightyellow'>".Aut_max($Aut_noeud_2)."km</td></tr>";
		elseif($Aut_noeud_2 >$Aut_noeud_1)
			echo "<tr><td align='left'>Autonomie max sur noeuds routiers</td><td bgcolor='LightCoral'>".Aut_max($Aut_noeud_1)."km</td><td bgcolor='lightgreen'>".Aut_max($Aut_noeud_2)."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie max sur noeuds routiers</td><td bgcolor='lightgreen'>".Aut_max($Aut_noeud_1)."km</td><td bgcolor='LightCoral'>".Aut_max($Aut_noeud_2)."km</td></tr>";
		if($Aut_col_2 ==$Aut_col_1)
			echo "<tr><td align='left'>Autonomie max sur collines</td><td bgcolor='lightyellow'>".Aut_max($Aut_col_1)."km</td><td bgcolor='lightyellow'>".Aut_max($Aut_col_2)."km</td></tr>";
		elseif($Aut_col_2 >$Aut_col_1)
			echo "<tr><td align='left'>Autonomie max sur collines</td><td bgcolor='LightCoral'>".Aut_max($Aut_col_1)."km</td><td bgcolor='lightgreen'>".Aut_max($Aut_col_2)."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie max sur collines</td><td bgcolor='lightgreen'>".Aut_max($Aut_col_1)."km</td><td bgcolor='LightCoral'>".Aut_max($Aut_col_2)."km</td></tr>";
		if($Aut_for_2 ==$Aut_for_1)
			echo "<tr><td align='left'>Autonomie max en forêt</td><td bgcolor='lightyellow'>".Aut_max($Aut_for_1)."km</td><td bgcolor='lightyellow'>".Aut_max($Aut_for_2)."km</td></tr>";
		elseif($Aut_for_2 >$Aut_for_1)
			echo "<tr><td align='left'>Autonomie max en forêt</td><td bgcolor='LightCoral'>".Aut_max($Aut_for_1)."km</td><td bgcolor='lightgreen'>".Aut_max($Aut_for_2)."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie max en forêt</td><td bgcolor='lightgreen'>".Aut_max($Aut_for_1)."km</td><td bgcolor='LightCoral'>".Aut_max($Aut_for_2)."km</td></tr>";
		if($Aut_mont_2 ==$Aut_mont_1)
			echo "<tr><td align='left'>Autonomie max en montagne</td><td bgcolor='lightyellow'>".Aut_max($Aut_mont_1)."km</td><td bgcolor='lightyellow'>".Aut_max($Aut_mont_2)."km</td></tr>";
		elseif($Aut_mont_2 >$Aut_mont_1)
			echo "<tr><td align='left'>Autonomie max en montagne</td><td bgcolor='LightCoral'>".Aut_max($Aut_mont_1)."km</td><td bgcolor='lightgreen'>".Aut_max($Aut_mont_2)."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie max en montagne</td><td bgcolor='lightgreen'>".Aut_max($Aut_mont_1)."km</td><td bgcolor='LightCoral'>".Aut_max($Aut_mont_2)."km</td></tr>";
		if($Aut_urb_2 ==$Aut_urb_1)
			echo "<tr><td align='left'>Autonomie max en zone urbaine</td><td bgcolor='lightyellow'>".Aut_max($Aut_urb_1)."km</td><td bgcolor='lightyellow'>".Aut_max($Aut_urb_2)."km</td></tr>";
		elseif($Aut_urb_2 >$Aut_urb_1)
			echo "<tr><td align='left'>Autonomie max en zone urbaine</td><td bgcolor='LightCoral'>".Aut_max($Aut_urb_1)."km</td><td bgcolor='lightgreen'>".Aut_max($Aut_urb_2)."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie max en zone urbaine</td><td bgcolor='lightgreen'>".Aut_max($Aut_urb_1)."km</td><td bgcolor='LightCoral'>".Aut_max($Aut_urb_2)."km</td></tr>";
		if($Aut_des_2 ==$Aut_des_1)
			echo "<tr><td align='left'>Autonomie max dans le désert</td><td bgcolor='lightyellow'>".Aut_max($Aut_des_1)."km</td><td bgcolor='lightyellow'>".Aut_max($Aut_des_2)."km</td></tr>";
		elseif($Aut_des_2 >$Aut_des_1)
			echo "<tr><td align='left'>Autonomie max dans le désert</td><td bgcolor='LightCoral'>".Aut_max($Aut_des_1)."km</td><td bgcolor='lightgreen'>".Aut_max($Aut_des_2)."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie max dans le désert</td><td bgcolor='lightgreen'>".Aut_max($Aut_des_1)."km</td><td bgcolor='LightCoral'>".Aut_max($Aut_des_2)."km</td></tr>";
		if($Aut_mar_2 ==$Aut_mar_1)
			echo "<tr><td align='left'>Autonomie max dans les marais</td><td bgcolor='lightyellow'>".Aut_max($Aut_mar_1)."km</td><td bgcolor='lightyellow'>".Aut_max($Aut_mar_2)."km</td></tr>";
		elseif($Aut_mar_2 >$Aut_mar_1)
			echo "<tr><td align='left'>Autonomie max dans les marais</td><td bgcolor='LightCoral'>".Aut_max($Aut_mar_1)."km</td><td bgcolor='lightgreen'>".Aut_max($Aut_mar_2)."km</td></tr>";
		else
			echo "<tr><td align='left'>Autonomie max dans les marais</td><td bgcolor='lightgreen'>".Aut_max($Aut_mar_1)."km</td><td bgcolor='LightCoral'>".Aut_max($Aut_mar_2)."km</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";
		if($Conso_2 ==$Conso_1)
			echo "<tr><td align='left'>Consommation sur route</td><td bgcolor='lightyellow'>".$Conso_1."L</td><td bgcolor='lightyellow'>".$Conso_2."L</td></tr>";
		elseif($Conso_2 <$Conso_1)
			echo "<tr><td align='left'>Consommation sur route</td><td bgcolor='LightCoral'>".$Conso_1."L</td><td bgcolor='lightgreen'>".$Conso_2."L</td></tr>";
		else
			echo "<tr><td align='left'>Consommation sur route</td><td bgcolor='lightgreen'>".$Conso_1."L</td><td bgcolor='LightCoral'>".$Conso_2."L</td></tr>";
		if($Conso_col_2 ==$Conso_col_1)
			echo "<tr><td align='left'>Consommation sur collines</td><td bgcolor='lightyellow'>".$Conso_col_1."L</td><td bgcolor='lightyellow'>".$Conso_col_2."L</td></tr>";
		elseif($Conso_col_2 <$Conso_col_1)
			echo "<tr><td align='left'>Consommation sur collines</td><td bgcolor='LightCoral'>".$Conso_col_1."L</td><td bgcolor='lightgreen'>".$Conso_col_2."L</td></tr>";
		else
			echo "<tr><td align='left'>Consommation sur collines</td><td bgcolor='lightgreen'>".$Conso_col_1."L</td><td bgcolor='LightCoral'>".$Conso_col_2."L</td></tr>";
		if($Conso_for_2 ==$Conso_for_1)
			echo "<tr><td align='left'>Consommation en forêt</td><td bgcolor='lightyellow'>".$Conso_for_1."L</td><td bgcolor='lightyellow'>".$Conso_for_2."L</td></tr>";
		elseif($Conso_for_2 <$Conso_for_1)
			echo "<tr><td align='left'>Consommation en forêt</td><td bgcolor='LightCoral'>".$Conso_for_1."L</td><td bgcolor='lightgreen'>".$Conso_for_2."L</td></tr>";
		else
			echo "<tr><td align='left'>Consommation en forêt</td><td bgcolor='lightgreen'>".$Conso_for_1."L</td><td bgcolor='LightCoral'>".$Conso_for_2."L</td></tr>";
		if($Conso_mont_2 ==$Conso_mont_1)
			echo "<tr><td align='left'>Consommation en montagne</td><td bgcolor='lightyellow'>".$Conso_mont_1."L</td><td bgcolor='lightyellow'>".$Conso_mont_2."L</td></tr>";
		elseif($Conso_mont_2 <$Conso_mont_1)
			echo "<tr><td align='left'>Consommation en montagne</td><td bgcolor='LightCoral'>".$Conso_mont_1."L</td><td bgcolor='lightgreen'>".$Conso_mont_2."L</td></tr>";
		else
			echo "<tr><td align='left'>Consommation en montagne</td><td bgcolor='lightgreen'>".$Conso_mont_1."L</td><td bgcolor='LightCoral'>".$Conso_mont_2."L</td></tr>";
		if($Conso_des_2 ==$Conso_des_1)
			echo "<tr><td align='left'>Consommation dans le désert</td><td bgcolor='lightyellow'>".$Conso_des_1."L</td><td bgcolor='lightyellow'>".$Conso_des_2."L</td></tr>";
		elseif($Conso_des_2 <$Conso_des_1)
			echo "<tr><td align='left'>Consommation dans le désert</td><td bgcolor='LightCoral'>".$Conso_des_1."L</td><td bgcolor='lightgreen'>".$Conso_des_2."L</td></tr>";
		else
			echo "<tr><td align='left'>Consommation dans le désert</td><td bgcolor='lightgreen'>".$Conso_des_1."L</td><td bgcolor='LightCoral'>".$Conso_des_2."L</td></tr>";
		if($Conso_mar_2 ==$Conso_mar_1)
			echo "<tr><td align='left'>Consommation dans les marais</td><td bgcolor='lightyellow'>".$Conso_mar_1."L</td><td bgcolor='lightyellow'>".$Conso_mar_2."L</td></tr>";
		elseif($Conso_mar_2 <$Conso_mar_1)
			echo "<tr><td align='left'>Consommation dans les marais</td><td bgcolor='LightCoral'>".$Conso_mar_1."L</td><td bgcolor='lightgreen'>".$Conso_mar_2."L</td></tr>";
		else
			echo "<tr><td align='left'>Consommation dans les marais</td><td bgcolor='lightgreen'>".$Conso_mar_1."L</td><td bgcolor='LightCoral'>".$Conso_mar_2."L</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";	
		if($Vitesse_2 ==$Vitesse_1)
			echo "<tr><td align='left'>Vitesse max sur route (sur mer pour les navires)</td><td bgcolor='lightyellow'>".$Vitesse_1."km/h</td><td bgcolor='lightyellow'>".$Vitesse_2."km/h</td></tr>";
		elseif($Vitesse_2 >$Vitesse_1)
			echo "<tr><td align='left'>Vitesse max sur route (sur mer pour les navires)</td><td bgcolor='LightCoral'>".$Vitesse_1."km/h</td><td bgcolor='lightgreen'>".$Vitesse_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max sur route (sur mer pour les navires)</td><td bgcolor='lightgreen'>".$Vitesse_1."km/h</td><td bgcolor='LightCoral'>".$Vitesse_2."km/h</td></tr>";
		if($Vit_col_2 ==$Vit_col_1)
			echo "<tr><td align='left'>Vitesse max sur collines</td><td bgcolor='lightyellow'>".$Vit_col_1."km/h</td><td bgcolor='lightyellow'>".$Vit_col_2."km/h</td></tr>";
		elseif($Vit_col_2 >$Vit_col_1)
			echo "<tr><td align='left'>Vitesse max sur collines</td><td bgcolor='LightCoral'>".$Vit_col_1."km/h</td><td bgcolor='lightgreen'>".$Vit_col_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max sur collines</td><td bgcolor='lightgreen'>".$Vit_col_1."km/h</td><td bgcolor='LightCoral'>".$Vit_col_2."km/h</td></tr>";
		if($Vit_for_2 ==$Vit_for_1)
			echo "<tr><td align='left'>Vitesse max en forêt</td><td bgcolor='lightyellow'>".$Vit_for_1."km/h</td><td bgcolor='lightyellow'>".$Vit_for_2."km/h</td></tr>";
		elseif($Vit_for_2 >$Vit_for_1)
			echo "<tr><td align='left'>Vitesse max en forêt</td><td bgcolor='LightCoral'>".$Vit_for_1."km/h</td><td bgcolor='lightgreen'>".$Vit_for_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max en forêt</td><td bgcolor='lightgreen'>".$Vit_for_1."km/h</td><td bgcolor='LightCoral'>".$Vit_for_2."km/h</td></tr>";
		if($Vit_mont_2 ==$Vit_mont_1)
			echo "<tr><td align='left'>Vitesse max en montagne</td><td bgcolor='lightyellow'>".$Vit_mont_1."km/h</td><td bgcolor='lightyellow'>".$Vit_mont_2."km/h</td></tr>";
		elseif($Vit_mont_2 >$Vit_mont_1)
			echo "<tr><td align='left'>Vitesse max en montagne</td><td bgcolor='LightCoral'>".$Vit_mont_1."km/h</td><td bgcolor='lightgreen'>".$Vit_mont_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max en montagne</td><td bgcolor='lightgreen'>".$Vit_mont_1."km/h</td><td bgcolor='LightCoral'>".$Vit_mont_2."km/h</td></tr>";
		if($Vit_urb_2 ==$Vit_urb_1)
			echo "<tr><td align='left'>Vitesse max en zone urbaine</td><td bgcolor='lightyellow'>".$Vit_urb_1."km/h</td><td bgcolor='lightyellow'>".$Vit_urb_2."km/h</td></tr>";
		elseif($Vit_urb_2 >$Vit_urb_1)
			echo "<tr><td align='left'>Vitesse max en zone urbaine</td><td bgcolor='LightCoral'>".$Vit_urb_1."km/h</td><td bgcolor='lightgreen'>".$Vit_urb_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max en zone urbaine</td><td bgcolor='lightgreen'>".$Vit_urb_1."km/h</td><td bgcolor='LightCoral'>".$Vit_urb_2."km/h</td></tr>";
		if($Vit_des_2 ==$Vit_des_1)
			echo "<tr><td align='left'>Vitesse max dans le désert</td><td bgcolor='lightyellow'>".$Vit_des_1."km/h</td><td bgcolor='lightyellow'>".$Vit_des_2."km/h</td></tr>";
		elseif($Vit_des_2 >$Vit_des_1)
			echo "<tr><td align='left'>Vitesse max dans le désert</td><td bgcolor='LightCoral'>".$Vit_des_1."km/h</td><td bgcolor='lightgreen'>".$Vit_des_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max dans le désert</td><td bgcolor='lightgreen'>".$Vit_des_1."km/h</td><td bgcolor='LightCoral'>".$Vit_des_2."km/h</td></tr>";
		if($Vit_mar_2 ==$Vit_mar_1)
			echo "<tr><td align='left'>Vitesse max dans les marais</td><td bgcolor='lightyellow'>".$Vit_mar_1."km/h</td><td bgcolor='lightyellow'>".$Vit_mar_2."km/h</td></tr>";
		elseif($Vit_mar_2 >$Vit_mar_1)
			echo "<tr><td align='left'>Vitesse max dans les marais</td><td bgcolor='LightCoral'>".$Vit_mar_1."km/h</td><td bgcolor='lightgreen'>".$Vit_mar_2."km/h</td></tr>";
		else
			echo "<tr><td align='left'>Vitesse max dans les marais</td><td bgcolor='lightgreen'>".$Vit_mar_1."km/h</td><td bgcolor='LightCoral'>".$Vit_mar_2."km/h</td></tr>";
		echo "<tr><td colspan='3'><hr></td></tr>";
		if($Vis_2 ==$Vis_1)
			echo "<tr><td align='left'>Malus de furtivité</td><td bgcolor='lightyellow'>".$Vis_1."</td><td bgcolor='lightyellow'>".$Vis_2."</td></tr>";
		elseif($Vis_2 <$Vis_1)
			echo "<tr><td align='left'>Malus de furtivité</td><td bgcolor='LightCoral'>".$Vis_1."</td><td bgcolor='lightgreen'>".$Vis_2."</td></tr>";
		else
			echo "<tr><td align='left'>Malus de furtivité</td><td bgcolor='lightgreen'>".$Vis_1."</td><td bgcolor='LightCoral'>".$Vis_2."</td></tr>";
		if($Detection_2 ==$Detection_1)
			echo "<tr><td align='left'>Bonus de détection</td><td bgcolor='lightyellow'>".$Detection_1."</td><td bgcolor='lightyellow'>".$Detection_2."</td></tr>";
		elseif($Detection_2 >$Detection_1)
			echo "<tr><td align='left'>Bonus de détection</td><td bgcolor='LightCoral'>".$Detection_1."</td><td bgcolor='lightgreen'>".$Detection_2."</td></tr>";
		else
			echo "<tr><td align='left'>Bonus de détection</td><td bgcolor='lightgreen'>".$Detection_1."</td><td bgcolor='LightCoral'>".$Detection_2."</td></tr>";
		if($Bonus_Tactique_2 ==$Bonus_Tactique_1)
			echo "<tr><td align='left'>Bonus Tactique</td><td bgcolor='lightyellow'>".$Bonus_Tactique_1."</td><td bgcolor='lightyellow'>".$Bonus_Tactique_2."</td></tr>";
		elseif($Bonus_Tactique_2 >$Bonus_Tactique_1)
			echo "<tr><td align='left'>Bonus Tactique</td><td bgcolor='LightCoral'>".$Bonus_Tactique_1."</td><td bgcolor='lightgreen'>".$Bonus_Tactique_2."</td></tr>";
		else
			echo "<tr><td align='left'>Bonus Tactique</td><td bgcolor='lightgreen'>".$Bonus_Tactique_1."</td><td bgcolor='LightCoral'>".$Bonus_Tactique_2."</td></tr>";
		if($Fiabilite_2 ==$Fiabilite_1)
			echo "<tr><td align='left'>Fiabilité (Bonus CT)</td><td bgcolor='lightyellow'>".$Fiabilite_1."</td><td bgcolor='lightyellow'>".$Fiabilite_2."</td></tr>";
		elseif($Fiabilite_2 >$Fiabilite_1)
			echo "<tr><td align='left'>Fiabilité (Bonus CT)</td><td bgcolor='LightCoral'>".$Fiabilite_1."</td><td bgcolor='lightgreen'>".$Fiabilite_2."</td></tr>";
		else
			echo "<tr><td align='left'>Fiabilité (Bonus CT)</td><td bgcolor='lightgreen'>".$Fiabilite_1."</td><td bgcolor='LightCoral'>".$Fiabilite_2."</td></tr>";
		if($Charge_2 ==$Charge_1)
			echo "<tr><td align='left'>Charge utile</td><td bgcolor='lightyellow'>".$Charge_1."kg</td><td bgcolor='lightyellow'>".$Charge_2."kg</td></tr>";
		elseif($Charge_2 >$Charge_1)
			echo "<tr><td align='left'>Charge utile</td><td bgcolor='LightCoral'>".$Charge_1."kg</td><td bgcolor='lightgreen'>".$Charge_2."kg</td></tr>";
		else
			echo "<tr><td align='left'>Charge utile</td><td bgcolor='lightgreen'>".$Charge_1."kg</td><td bgcolor='LightCoral'>".$Charge_2."kg</td></tr>";
		echo '</table></div>';
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
?>