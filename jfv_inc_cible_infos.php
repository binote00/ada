<?php
function GetCible_Infos($Vehicule_ID,$Nbr,$Regiment,$HP,$Exp,$Muns,$Fret,$Fret_Qty,$Zone,$Position,$Trait_e,$Avancement,$Front,$Visible,$Battle=false)
{
	global $g_Range;
	global $g_Vitesse;
	global $g_Charge;
	global $g_Fuel;
	global $g_Carbu;
	global $g_Conso;
	global $g_Puissance;
	global $g_Detection;
	global $g_mobile;
	global $g_Arme_AT;
	global $g_Arme_Art;
	global $g_Stock_Art;
	global $g_Stock_AT;
	global $g_Type;
	global $g_Categorie;
	global $g_Amphi;
	global $g_Moves;
	global $country;
    $Output='';
	if($Battle)
		$DB="Regiment_PVP";
	else
		$DB="Regiment";
	if($Vehicule_ID ==5124 and $Front ==2)
	{
		SetData($DB,"Vehicule_ID",5001,"ID",$Regiment);
		if($HP >5000)SetData($DB,"HP",5000,"ID",$Regiment);
		$Vehicule_ID=5001;
	}			
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Vehicule_ID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Nom=$data['Nom'];
			$Taille=$data['Taille'];
			if($Position ==25) //Plongée
			{
				$data['Fuel']/=2;
				$data['Vitesse']/=2;
				$data['Portee']*=2;
			}
			$Autonomie=$data['Fuel'];
			$Blindage=$data['Blindage_f'];					
			$g_Range=$data['Portee'];
			$g_mobile=$data['mobile'];
			$g_Charge=$data['Charge']*$Nbr;
			$g_Carbu=$data['Carbu_ID'];
			$g_Puissance=$data['Puissance'];
			$g_Detection=$data['Detection'];
			$g_Type=$data['Type'];
			$Amphi=$data['Amphi'];
			$g_Categorie=$data['Categorie'];
			$Conso=$data['Conso'];
			$g_Amphi+=$Amphi;
			if($data['HP'])
				$hp_good=round(($HP/$data['HP'])*100);
			else
				$hp_good=0;
			$hp_bad=100-$hp_good;
			if($HP)
				$cur_HP="<br><div class='progress'><div class='progress-bar progress-bar-success' style='width: ".$hp_good."%'>".$hp_good."%</div>
				<div class='progress-bar progress-bar-danger' style='width: ".$hp_bad."%'></div></div>";
				//<div style='background-image: url(../images/nav_hp_100.png); border: 1px solid black; text-align:center;'>".round(($HP / $data['HP'])*100)."%</div>";
			if($g_mobile ==5)
				$g_Conso=$Conso;
			else
				$g_Conso=Get_LandConso($Zone,$Conso);
			$g_Vitesse=Get_LandSpeed($data['Vitesse'],$g_mobile,$Zone,$Position,$g_Type,$hp_good);
			if($data['Fiabilite'] >1)
				$Fiabilite="Très Bonne";
			elseif($data['Fiabilite'] >0)
				$Fiabilite="Bonne";
			elseif($data['Fiabilite'] <-1)
				$Fiabilite="Très Mauvaise";
			elseif($data['Fiabilite'] <0)
				$Fiabilite="Mauvaise";
			else
				$Fiabilite="Moyenne";
			$g_Moves=floor($data['Fuel']/10)+($data['Fiabilite']*5);
			$Stock_Fuel_Max=25000;
			if(!$g_Type or $g_Type ==93 or $g_Type ==96 or $g_Type ==97 or $g_Type ==98 or $g_Type ==90)
			{
				if($Avancement >499999)
					$Max_Veh=250;
				elseif($Avancement >199999)
					$Max_Veh=225;
				elseif($Avancement >99999)
					$Max_Veh=200;
				elseif($Avancement >49999)
					$Max_Veh=175;
				elseif($Avancement >24999)
					$Max_Veh=150;
				elseif($Avancement >9999)
					$Max_Veh=125;
				else
					$Max_Veh=100;
			}
			elseif($g_Type ==94 or $g_Type ==99)
			{
				if($Avancement >199999)
					$Max_Veh=100;
				elseif($Avancement >99999)
					$Max_Veh=90;
				elseif($Avancement >49999)
					$Max_Veh=80;
				elseif($Avancement >24999)
					$Max_Veh=70;
				elseif($Avancement >9999)
					$Max_Veh=60;
				else
					$Max_Veh=50;
			}
			elseif($g_Type ==13 or $g_Type ==95)
			{
				$Max_Veh=1;
			}
			elseif($g_Type ==37)
			{
				$Max_Veh=1;
				$Stock_Fuel_Max=10000;
			}
			elseif($g_Type ==20 or $g_Type ==21)
			{
				$Max_Veh=1;
				$Stock_Fuel_Max=250000;
			}
			elseif($g_Type ==18 or $g_Type ==19)
			{
				if($Avancement >99999)
					$Max_Veh=4;
				elseif($Avancement >49999)
					$Max_Veh=3;
				elseif($Avancement >9999)
					$Max_Veh=2;
				else
					$Max_Veh=1;
				$Stock_Fuel_Max=100000*$Max_Veh;
			}
			elseif($g_Type ==17)
			{
				if($Avancement >199999)
					$Max_Veh=6;
				elseif($Avancement >99999)
					$Max_Veh=5;
				elseif($Avancement >49999)
					$Max_Veh=4;
				elseif($Avancement >9999)
					$Max_Veh=3;
				else
					$Max_Veh=2;
				$Stock_Fuel_Max=10000*$Max_Veh;
			}
			elseif($g_mobile ==5)
			{
				if($Avancement >499999)
					$Max_Veh=10;
				elseif($Avancement >199999)
					$Max_Veh=9;
				elseif($Avancement >99999)
					$Max_Veh=8;
				elseif($Avancement >49999)
					$Max_Veh=7;
				elseif($Avancement >24999)
					$Max_Veh=6;
				elseif($Avancement >9999)
					$Max_Veh=5;
				else
					$Max_Veh=4;
				$Stock_Fuel_Max=10000*$Max_Veh;
			}
			elseif($g_Type ==4 or $g_Type ==6 or $g_Type ==8 or $g_Type ==10 or $g_Type ==11 or $g_Type ==12 or $g_Type ==91 or $g_Type ==92 or $data['Flak'] or $g_mobile ==4)
			{
				if($Avancement >499999)
					$Max_Veh=12;
				elseif($Avancement >199999)
					$Max_Veh=11;
				elseif($Avancement >99999)
					$Max_Veh=10;
				elseif($Avancement >49999)
					$Max_Veh=9;
				elseif($Avancement >24999)
					$Max_Veh=8;
				elseif($Avancement >9999)
					$Max_Veh=7;
				else
					$Max_Veh=6;
			}
			elseif($g_Type ==9)
			{
				if($Avancement >499999)
					$Max_Veh=18;
				elseif($Avancement >199999)
					$Max_Veh=16;
				elseif($Avancement >99999)
					$Max_Veh=14;
				elseif($Avancement >49999)
					$Max_Veh=12;
				elseif($Avancement >24999)
					$Max_Veh=10;
				elseif($Avancement >9999)
					$Max_Veh=8;
				else
					$Max_Veh=6;
			}
			else
			{
				if($Avancement >499999)
					$Max_Veh=24;
				elseif($Avancement >199999)
					$Max_Veh=22;
				elseif($Avancement >99999)
					$Max_Veh=20;
				elseif($Avancement >49999)
					$Max_Veh=18;
				elseif($Avancement >24999)
					$Max_Veh=16;
				elseif($Avancement >9999)
					$Max_Veh=14;
				else
					$Max_Veh=12;
			}
			if($Nbr >$Max_Veh)
			{
				SetData($DB,"Vehicule_Nbr",$Max_Veh,"ID",$Regiment);
				$Nbr=$Max_Veh;
			}					
			$Muns_txt=GetMun_txt($Muns);			
			if($data['Arme_Art'] >0)
			{
				$Arme_cal=round(GetData("Armes","ID",$data['Arme_Art'],"Calibre"));
				$Stock_Max=$data['Arme_Art_mun']*$Nbr;
				if(!$Battle)
				{
					if($Arme_cal ==40 and $Stock_Max >10000)
						$Stock_Max=10000;
					elseif($Arme_cal ==50 and $Stock_Max >10000)
						$Stock_Max=10000;
					elseif($Arme_cal ==75 and $Stock_Max >5000)
						$Stock_Max=5000;
					elseif($Arme_cal ==90 and $Stock_Max >2500)
						$Stock_Max=2500;
					elseif($Arme_cal ==105 and $Stock_Max >1500)
						$Stock_Max=1500;
					elseif($Arme_cal ==125 and $Stock_Max >1000)
						$Stock_Max=1000;
					elseif($Arme_cal ==150 and $Stock_Max >1000)
						$Stock_Max=1000;
					$Stock=GetData($DB,"ID",$Regiment,"Stock_Munitions_".$Arme_cal);
					if($Trait_e ==14)
						$Stock_Max*=1.1;
					if($Stock >$Stock_Max)
					{
						SetData($DB,"Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
						$Stock=$Stock_Max;
					}
					if($g_mobile ==5)
						$g_Arme_Art="<img src='images/icon_turret.png' title='".GetData("Armes","ID",$data['Arme_Art'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
					else
						$g_Arme_Art="<img src='images/icon_gun.png' title='".GetData("Armes","ID",$data['Arme_Art'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
					if($Stock==0)
						$g_Arme_Art.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
				}
				else
				{
					if($g_Categorie ==2 or $g_Categorie ==3 or $g_Categorie ==15 or $g_Type ==8)
						$data['Arme_Art_mun']=floor($data['Arme_Art_mun']/3);
					$Stock_Max=$data['Arme_Art_mun']*$Nbr;
					$Stock=($data['Arme_Art_mun']-GetData("Regiment_PVP","ID",$Regiment,"Stock_Art"))*$Nbr;
					$g_Stock_Art=$Stock;
					$g_Arme_Art="<img src='images/icon_gun.png' title='".GetData("Armes","ID",$data['Arme_Art'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
				}
			}
			else
				$g_Arme_Art="Aucune";
			if($data['Arme_AT'] >0 and $data['Arme_AT'] !=82)
			{
				if($data['Arme_AT'] ==268)
				{
					$Stock_Max=$data['Arme_AT_mun']*$Nbr;
					if(!$Battle)
					{
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Mines");
						if($Stock >$Stock_Max)
						{
							SetData($DB,"Stock_Mines",$Stock_Max,"ID",$Regiment);
							$Stock=$Stock_Max;
						}
					}
					$g_Arme_AT="<img src='images/icon_mine.png' title='".GetData("Armes","ID",$data['Arme_AT'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
				}
				else
				{
					$Arme_cal=round(GetData("Armes","ID",$data['Arme_AT'],"Calibre"));
					if(!$Battle)
					{
						$Stock_Max=$data['Arme_AT_mun']*$Nbr;
						if($Arme_cal ==8 and $Stock_Max >50000)
							$Stock_Max=50000;
						elseif($Arme_cal ==13 and $Stock_Max >30000)
							$Stock_Max=30000;
						elseif($Arme_cal == 20 and $Stock_Max >20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 30 and $Stock_Max >20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 40 and $Stock_Max >10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 50 and $Stock_Max >10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 75 and $Stock_Max >5000)
							$Stock_Max=5000;
						elseif($Arme_cal == 90 and $Stock_Max >2500)
							$Stock_Max=2500;
						elseif($Arme_cal ==105 and $Stock_Max >1500)
							$Stock_Max=1500;
						elseif($Arme_cal ==125 and $Stock_Max >1000)
							$Stock_Max=1000;
						elseif($Arme_cal ==150 and $Stock_Max >1000)
							$Stock_Max=1000;
						elseif($Arme_cal == 300 and $Stock_Max >100)
							$Stock_Max=100;
						elseif($Arme_cal == 530 and $Stock_Max >250)
							$Stock_Max=250;
						elseif($Arme_cal == 610 and $Stock_Max >250)
							$Stock_Max=250;
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Munitions_".$Arme_cal);
						if($Trait_e ==14)
							$Stock_Max*=1.1;
						if($Stock > $Stock_Max)
						{
							SetData($DB,"Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
							$Stock=$Stock_Max;
						}
						if($g_mobile ==5)
							$g_Arme_AT="<img src='images/icon_torpedo.png' title='".GetData("Armes","ID",$data['Arme_AT'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
						else
							$g_Arme_AT="<img src='images/icon_gun.png' title='".GetData("Armes","ID",$data['Arme_AT'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
						if($Stock==0)
							$g_Arme_AT.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
					}
					else
					{
						if($g_Categorie ==2 or $g_Categorie ==3 or $g_Categorie ==15 or $g_Type ==8)
							$data['Arme_AT_mun']=floor($data['Arme_AT_mun']/3);
						$Stock_Max=$data['Arme_AT_mun']*$Nbr;
						$Stock=($data['Arme_AT_mun']-GetData("Regiment_PVP","ID",$Regiment,"Stock_AT"))*$Nbr;
						$g_Stock_AT=$Stock;
						$g_Arme_AT="<img src='images/icon_gun.png' title='".GetData("Armes","ID",$data['Arme_AT'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
					}
				}
			}
			else
				$g_Arme_AT="Aucune";
			if($data['Arme_Inf'] >0)
			{
				if($data['Arme_Inf'] ==223)
				{
					$Stock_Max=$data['Arme_Inf_mun']*$Nbr;
					if(!$Battle)
					{
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Charges");
						if($Stock >$Stock_Max)
						{
							SetData($DB,"Stock_Charges",$Stock_Max,"ID",$Regiment);
							$Stock=$Stock_Max;
						}
					}
					$Arme_Inf=GetData("Armes","ID",$data['Arme_Inf'],"Nom").'<br><span title=\'Munitions\'>'.$Stock.'/'.$Stock_Max.' '.$Muns_txt.'</span>';
				}
				elseif($data['Arme_Inf'] ==136)
				{
					$Stock_Max=$data['Arme_Inf_mun']*$Nbr;
					if(!$Battle)
					{
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Essence_87");
						if($Stock >$Stock_Max)$Stock=$Stock_Max;
					}
					$Arme_Inf="<img src='images/icon_flame.png' title='".GetData("Armes","ID",$data['Arme_Inf'],"Nom")."'><br>".$Stock."/".$Stock_Max;
				}
				else
				{
					$Arme_cal_Inf=round(GetData("Armes","ID",$data['Arme_Inf'],"Calibre"));
					$Arme_cal=$Arme_cal_Inf;
					if(!$Battle)
					{
						$Stock_Max=$data['Arme_Inf_mun']*$Nbr;
						if($Arme_cal ==8 and $Stock_Max >50000)
							$Stock_Max=50000;
						elseif($Arme_cal ==13 and $Stock_Max >30000)
							$Stock_Max=30000;
						elseif($Arme_cal == 20 and $Stock_Max >20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 30 and $Stock_Max >20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 40 and $Stock_Max >10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 50 and $Stock_Max >10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 75 and $Stock_Max >5000)
							$Stock_Max=5000;
						elseif($Arme_cal == 90 and $Stock_Max >2500)
							$Stock_Max=2500;
						elseif($Arme_cal ==105 and $Stock_Max >1500)
							$Stock_Max=1500;
						elseif($Arme_cal ==125 and $Stock_Max >1000)
							$Stock_Max=1000;
						elseif($Arme_cal ==150 and $Stock_Max >1000)
							$Stock_Max=1000;
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Munitions_".$Arme_cal);
						if($Trait_e ==14)
							$Stock_Max*=1.1;
						if($Stock >$Stock_Max)
						{
							SetData($DB,"Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
							$Stock=$Stock_Max;
						}
						$Arme_Inf="<img src='images/icon_inf.png' title='".GetData("Armes","ID",$data['Arme_Inf'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
						if($Stock==0)
							$Arme_Inf.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
					}
					else
						$Arme_Inf="<img src='images/icon_inf.png' title='".GetData("Armes","ID",$data['Arme_Inf'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'>";
				}
			}
			else
				$Arme_Inf="Aucune";
			if($data['Arme_AA'] >0)
			{
				$Arme_cal_AA=round(GetData("Armes","ID",$data['Arme_AA'],"Calibre"));
				if($Arme_cal_Inf !=$Arme_cal_AA)
				{
					$Arme_cal=$Arme_cal_AA;
					if(!$Battle)
					{
						$Stock_Max=$data['Arme_AA_mun']*$Nbr;
						if($Arme_cal ==8 and $Stock_Max >65000)
							$Stock_Max=65000;
						elseif($Arme_cal ==13 and $Stock_Max >50000)
							$Stock_Max=50000;
						elseif($Arme_cal == 20 and $Stock_Max >25000)
							$Stock_Max=25000;
						elseif($Arme_cal == 30 and $Stock_Max >20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 40 and $Stock_Max >15000)
							$Stock_Max=15000;
						elseif($Arme_cal == 50 and $Stock_Max >10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 75 and $Stock_Max >5000)
							$Stock_Max=5000;
						elseif($Arme_cal == 90 and $Stock_Max >2500)
							$Stock_Max=2500;
						elseif($Arme_cal ==105 and $Stock_Max >1500)
							$Stock_Max=1500;
						elseif($Arme_cal ==125 and $Stock_Max >1000)
							$Stock_Max=1000;
						elseif($Arme_cal ==150 and $Stock_Max >1000)
							$Stock_Max=1000;
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Munitions_".$Arme_cal);
						if($Trait_e ==14)
							$Stock_Max*=1.1;
						if($Stock >$Stock_Max)
						{
							SetData($DB,"Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
							$Stock=$Stock_Max;
						}
						$Arme_AA="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
					}
					else
						$Arme_AA="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'>";
				}
				else
					$Arme_AA="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'>";
				if($Stock==0)
					$Arme_AA.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
			}
			else
				$Arme_AA="Aucune";
			if($data['Arme_AA2'] >0 and !$data['Arme_Art'])
			{
				$Arme_cal_AA=round(GetData("Armes","ID",$data['Arme_AA2'],"Calibre"));
				if($Arme_cal_Inf!=$Arme_cal_AA)
				{
					$Arme_cal=$Arme_cal_AA;
					if(!$Battle)
					{
						$Stock_Max=$data['Arme_AA2_mun']*$Nbr;
						if($Arme_cal ==8 and $Stock_Max >50000)
							$Stock_Max=50000;
						elseif($Arme_cal ==13 and $Stock_Max > 30000)
							$Stock_Max=30000;
						elseif($Arme_cal == 20 and $Stock_Max > 20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 30 and $Stock_Max > 20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 40 and $Stock_Max > 10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 50 and $Stock_Max > 10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 75 and $Stock_Max > 5000)
							$Stock_Max=5000;
						elseif($Arme_cal == 90 and $Stock_Max > 2500)
							$Stock_Max=2500;
						elseif($Arme_cal ==105 and $Stock_Max > 1500)
							$Stock_Max=1500;
						elseif($Arme_cal ==125 and $Stock_Max > 1000)
							$Stock_Max=1000;
						elseif($Arme_cal ==150 and $Stock_Max > 1000)
							$Stock_Max=1000;
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Munitions_".$Arme_cal);
						if($Trait_e ==14)
							$Stock_Max*=1.1;
						if($Stock > $Stock_Max)
						{
							SetData($DB,"Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
							$Stock=$Stock_Max;
						}
						$g_Arme_Art="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA2'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
					}
					else
						$g_Arme_Art="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA2'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'>";
				}
				$g_Arme_Art="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA2'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
				if($Stock==0)
					$g_Arme_Art.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
			}
			elseif($data['Arme_AA2'] >0 and !$data['Arme_AT'])
			{
				$Arme_cal_AA=round(GetData("Armes","ID",$data['Arme_AA2'],"Calibre"));
				if($Arme_cal_Inf !=$Arme_cal_AA)
				{
					$Arme_cal=$Arme_cal_AA;
					if(!$Battle)
					{
						$Stock_Max=$data['Arme_AA2_mun']*$Nbr;
						if($Arme_cal ==8 and $Stock_Max >50000)
							$Stock_Max=50000;
						elseif($Arme_cal ==13 and $Stock_Max > 30000)
							$Stock_Max=30000;
						elseif($Arme_cal == 20 and $Stock_Max > 20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 30 and $Stock_Max > 20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 40 and $Stock_Max > 10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 50 and $Stock_Max > 10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 75 and $Stock_Max > 5000)
							$Stock_Max=5000;
						elseif($Arme_cal == 90 and $Stock_Max > 2500)
							$Stock_Max=2500;
						elseif($Arme_cal ==105 and $Stock_Max > 1500)
							$Stock_Max=1500;
						elseif($Arme_cal ==125 and $Stock_Max > 1000)
							$Stock_Max=1000;
						elseif($Arme_cal ==150 and $Stock_Max > 1000)
							$Stock_Max=1000;
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Munitions_".$Arme_cal);
						if($Trait_e ==14)
							$Stock_Max*=1.1;
						if($Stock >$Stock_Max)
						{
							SetData($DB,"Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
							$Stock=$Stock_Max;
						}
						$g_Arme_AT="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA2'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
					}
					else
						$g_Arme_AT="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA2'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'>";
				}
				$g_Arme_AT="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA2'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
				if($Stock==0)
					$g_Arme_AT.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
			}
			if($data['Arme_AA3'] >0 and $Arme_Inf =="Aucune")
			{
				$Arme_cal_AA=round(GetData("Armes","ID",$data['Arme_AA3'],"Calibre"));
				if($Arme_cal_Inf !=$Arme_cal_AA)
				{
					$Arme_cal=$Arme_cal_AA;
					if(!$Battle)
					{
						$Stock_Max=$data['Arme_AA3_mun']*$Nbr;
						if($Arme_cal ==8 and $Stock_Max >65000)
							$Stock_Max=65000;
						elseif($Arme_cal ==13 and $Stock_Max > 50000)
							$Stock_Max=50000;
						elseif($Arme_cal == 20 and $Stock_Max > 30000)
							$Stock_Max=30000;
						elseif($Arme_cal == 30 and $Stock_Max > 25000)
							$Stock_Max=25000;
						elseif($Arme_cal == 40 and $Stock_Max > 20000)
							$Stock_Max=20000;
						elseif($Arme_cal == 50 and $Stock_Max > 10000)
							$Stock_Max=10000;
						elseif($Arme_cal == 75 and $Stock_Max > 5000)
							$Stock_Max=5000;
						elseif($Arme_cal == 90 and $Stock_Max > 2500)
							$Stock_Max=2500;
						elseif($Arme_cal ==105 and $Stock_Max > 1500)
							$Stock_Max=1500;
						elseif($Arme_cal ==125 and $Stock_Max > 1000)
							$Stock_Max=1000;
						elseif($Arme_cal ==150 and $Stock_Max > 1000)
							$Stock_Max=1000;
						$Stock=GetData($DB,"ID",$Regiment,"Stock_Munitions_".$Arme_cal);
						if($Trait_e ==14)
							$Stock_Max*=1.1;
						if($Stock >$Stock_Max)
						{
							SetData($DB,"Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
							$Stock=$Stock_Max;
						}
						$Arme_Inf="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA3'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
					}
					else
						$Arme_Inf="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA3'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'>";
				}
				$Arme_Inf.="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA3'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
				if($Stock==0)
					$Arme_Inf.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
			}					
			if(!$g_Charge)
				$Charge="N/A";
			elseif($Fret)
			{
				//$Charge_actu=$g_Charge/100*$Fret_Qty;
				if($Fret ==1001)
					$Fret="Diesel";
				elseif($Fret ==1087)
					$Fret="Essence 87";
				elseif($Fret ==1100)
					$Fret="Essence 100";
				elseif($Fret ==1)
					$Fret="Troupes";
				elseif($Fret ==80)
					$Fret="Rockets";
				elseif($Fret ==200)
					$Fret="Troupes EM";
				elseif($Fret ==300)
					$Fret="Charges";
				elseif($Fret ==400)
					$Fret="Mines";
				elseif($Fret ==800)
					$Fret="Torpilles";
				elseif($Fret ==888)
					$Fret="Lend-Lease";
				elseif($Fret ==9050 or $Fret ==9125 or $Fret ==9250 or $Fret ==9500)
					$Fret="Bombes de ".substr($Fret,1)."kg";
				elseif($Fret >9999)
					$Fret="Bombes de ".substr($Fret,0,-1)."kg";
				else
					$Fret=$Fret."mm";
				$Charge=$Fret_Qty."<br>".$Fret;
				//$Charge=$Charge_actu.'/'.$g_Charge."<br>".$Fret;
			}
			else
				$Charge='0/'.$g_Charge;
			if($g_Carbu ==1)
			{
				if($Battle)
					$Fuel_txt="<img src='images/diesel_icon.png' title='Diesel'>";
				else
				{
					$g_Fuel=GetData("Regiment","ID",$Regiment,"Stock_Essence_1");
					if($Trait_e ==14)
						$Stock_Fuel_Max*=1.1;
					if($g_Fuel > $Stock_Fuel_Max)
					{
						SetData("Regiment","Stock_Essence_1",$Stock_Fuel_Max,"ID",$Regiment);
						$g_Fuel=$Stock_Fuel_Max;
					}
					if($g_Fuel ==0 or $g_Fuel <($g_Conso*$Nbr))
					{
						$Fuel_txt="<img src='images/diesel_icon_empty.png' title='".$g_Fuel.'/'.$Stock_Fuel_Max." Diesel'>";
						$Fuel_txt.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
					}
					else
						$Fuel_txt="<img src='images/diesel_icon.png' title='".$g_Fuel.'/'.$Stock_Fuel_Max." Diesel'>";
				}
			}
			elseif($g_Carbu ==87)
			{
				if($Battle)
					$Fuel_txt="<img src='images/essence_icon.png' title='Essence'>";
				else
				{
					$g_Fuel=GetData("Regiment","ID",$Regiment,"Stock_Essence_87");
					if($Trait_e ==14)
						$Stock_Fuel_Max*=1.1;
					if($g_Fuel > $Stock_Fuel_Max)
					{
						SetData("Regiment","Stock_Essence_87",$Stock_Fuel_Max,"ID",$Regiment);
						$g_Fuel=$Stock_Fuel_Max;
					}
					if($g_Fuel ==0 or $g_Fuel <($g_Conso*$Nbr))
					{
						$Fuel_txt="<img src='images/essence_icon_empty.png' title='".$g_Fuel.'/'.$Stock_Fuel_Max." Essence'>";
						$Fuel_txt.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
					}
					else
						$Fuel_txt="<img src='images/essence_icon.png' title='".$g_Fuel.'/'.$Stock_Fuel_Max." Essence'>";
				}
			}
			else
			{
				$g_Fuel=GetData($DB,"ID",$Regiment,"Moral");
				if($g_Fuel ==0)
					$Fuel_txt="Moral<br><span class='text-danger' title='Quantité trop faible pour un déplacement'>".$g_Fuel."</span>";
				else
					$Fuel_txt="Moral<br>".$g_Fuel;
				if($g_Fuel <100)
					$Fuel_txt.="<a href='#' class='popup'><img src='images/help.png'><span>Pour remonter le moral vous devez vous trouver sur une caserne occupée par votre faction sans aucune unité ennemie sur cette même caserne. Une option à 4CT apparaitra alors dans les actions du bataillon.</span></a>";
			}						
			if($Position ==2 or $Position ==10)
			{
				$Vitesse_txt="<span class='text-danger'>0 </span>";
				$Taille/=4;
				$g_Range/=2;
			}
			elseif($Position ==3 or $Position ==9)
			{
				$Vitesse_txt="<span class='text-danger'>0 </span>";
				$Taille/=2;
				$g_Range/=2;
			}
			elseif($Position ==26)
			{
				$Vitesse_txt="<span class='text-danger'>0 </span>";
				$g_Range/=2;
			}
			else
				$Vitesse_txt=$g_Vitesse;
			if($Muns ==8)
				$g_Range/=2;
		}
		mysqli_free_result($result);
		unset($data);
		if($Battle)
			$Range=$g_Moves-GetData("Regiment_PVP","ID",$Regiment,"Moves")."/".$g_Moves;
		else
		{
			if($g_Carbu >0 and $Nbr >0 and $g_Conso >0)
			{
				$Range=ceil($Autonomie*$g_Fuel/($g_Conso*$Nbr));
				if($g_mobile ==5 and $HP >0)
				{
					if($Front ==2)
						$Auto_max=$g_Vitesse*12;
					else
						$Auto_max=$g_Vitesse*72;
					if($Range >$Auto_max)
						$Range=round($Auto_max);
				}
				elseif($g_mobile !=4)
				{
					if($Front ==2 and $Autonomie >200)
						$Autonomie=200;
					elseif($Front ==0 and $Autonomie >150)
						$Autonomie=150;
					elseif($Autonomie >300)
						$Autonomie=300;
				}
				if($Range >$Autonomie)
					$Range=$Autonomie;
				$Range.='km';
			}
			else
				$Range="N/A";
		}
		$Veh_Icone=GetVehiculeIcon($Vehicule_ID,$country,0,0,$Front);
		if($Vehicule_ID ==48)
			$Veh_Icone.="<a href='#' class='popup'><img src='images/help.png'><span>Accédez au ravitaillement pour choisir les unités spécifiques à votre nation</span></a>";
		if(!$Visible and $g_mobile !=5)
			$Veh_Icone.=" <img src='images/camouflage.png' title='Camouflé'>";
		if($Position ==8)
			$Veh_Icone.=" <img src='images/souslefeu.png' title='Sous le feu'>";
		//Pos Cons
		if($g_Type ==4 or $g_Type ==9)
			$Pos_cons=3;
		elseif($g_Type ==6 or $g_Type ==8 or $g_Type ==91)
			$Pos_cons=5;
		elseif($g_Categorie ==5 or $g_Categorie ==6)
			$Pos_cons=10;
		elseif($g_Type ==2 or $g_Type ==3 or $g_Type ==5 or $g_Type ==7 or $g_Type ==10)
			$Pos_cons=1;
		if($Pos_cons)
			$Tactical_txt=GetPosGr($Pos_cons);
		if(!$Nbr)
		{
			if($g_mobile ==5)
				$Max_Veh.=" <a href='#' class='popup'><img src='images/help.png'><span>Vous pouvez ravitailler ou changer de troupes dans un port doté de toutes les infrastructures contrôlé par votre faction.</span></a>";
			else
				$Max_Veh.=" <a href='#' class='popup'><img src='images/help.png'><span>Vous pouvez ravitailler ou changer de troupes dans la gare ou le port de la base arrière de votre division. Vous pouvez également ravitailler dans tout lieu de valeur stratégique niveau 4 ou supérieur contrôlé par votre faction et où ne se trouve aucune unité ennemie.</span></a>";
		}
		$Veh_Infos=addslashes("<b>".$Nom."</b><br>".$Veh_Icone."<table><tr><td align='left'>Expérience</td><th align='right'>".$Exp."</th></tr><tr><td align='left'>Blindage</td><th align='right'>".$Blindage."mm</th></tr><tr><td align='left'>Taille</td><th align='right'>".$Taille."</th></tr><tr><td align='left'>Détection</td><th align='right'>".$g_Detection."</th></tr><tr><td align='left'>Portée</td><th align='right'>".$g_Range."</th></tr><tr><td align='left'>Vitesse max</td><th align='right'>".$Vitesse_txt."km/h</th></tr><tr><td align='left'>Autonomie</td><th align='right'>".$Autonomie."km</th></tr><tr><td align='left'>Consommation unitaire</td><th align='right'>".$g_Conso."L/".$Autonomie."km</th></tr><tr><td align='left'>Fiabilité</td><th align='left'>".$Fiabilite."</th></tr><tr><td align='left'>Position préférée</td><th>".$Tactical_txt."</th></tr></table>");
		$Output="<tr><td>".$Regiment."e Cie</td>
		<td><div onMouseover=\"ddrivetip('".$Veh_Infos."','#ECDDC1','300','330')\"; onMouseout='hideddrivetip()'>".$Nbr."/".$Max_Veh." ".$Veh_Icone.$cur_HP."</div></td> 
		<td>".$Arme_Inf."</td>
		<td>".$g_Arme_Art."</td>
		<td>".$g_Arme_AT."</td>
		<td>".$Arme_AA."</td>
		<td>".$Fuel_txt."</td>
		<td>".$Range."</td><td>";				
	}
	return $Output;
}