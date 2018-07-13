<?php
require_once('./jfv_inc_sessions.php');
/*if($OfficierID ==1)
{
echo"<pre>";
print_r($_POST);
print_r($_SESSION);
echo"</pre>";
}*/
$OfficierID=$_SESSION['Officier'];
//Check Joueur Valide
if(2==1) //isset($_SESSION['AccountID']) and $OfficierID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_combat.inc.php');
	if($_SESSION['Distance'] ==0)
	{		
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT Front,Credits,Avancement,Trait,Heure_Mission FROM Officier WHERE ID='$OfficierID'") or die('Le jeu a rencontré une erreur,merci de le signaler sur le forum avec la référence suivante : ugi-off');
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
				$Credits=$data['Credits'];
				$Avancement=$data['Avancement'];
				$Trait_e=$data['Trait'];
				$Heure_Mission=$data['Heure_Mission'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		//GetData Troupes
		function GetCible_Infos($Vehicule_ID,$Nbr,$Regiment,$HP,$Exp,$Muns,$Fret,$Fret_Qty,$Zone,$Position,$Trait_e,$Avancement,$Front,$Visible)
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
			global $g_Type;
			global $g_Categorie;
			global $g_Amphi;
			global $country;
			if($Vehicule_ID ==5124 and $Front ==2)
			{
				SetData("Regiment","Vehicule_ID",5001,"ID",$Regiment);
				if($HP >5000)SetData("Regiment","HP",5000,"ID",$Regiment);
				$Vehicule_ID=5001;
			}			
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Vehicule_ID'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Vehicule=$data['ID'];
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
						SetData("Regiment","Vehicule_Nbr",$Max_Veh,"ID",$Regiment);
						$Nbr=$Max_Veh;
					}					
					$Muns_txt=GetMun_txt($Muns);			
					if($data['Arme_Art'] >0)
					{
						$Arme_cal=round(GetData("Armes","ID",$data['Arme_Art'],"Calibre"));
						$Stock_Max=$data['Arme_Art_mun']*$Nbr;
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
						$Stock=GetData("Regiment","ID",$Regiment,"Stock_Munitions_".$Arme_cal);
						if($Trait_e ==14)
							$Stock_Max*=1.1;
						if($Stock > $Stock_Max)
						{
							SetData("Regiment","Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
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
						$g_Arme_Art="Aucune";
					if($data['Arme_AT'] >0 and $data['Arme_AT'] !=82)
					{
						if($data['Arme_AT'] ==268)
						{
							$Stock_Max=$data['Arme_AT_mun']*$Nbr;
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Mines");
							if($Stock > $Stock_Max)
							{
								SetData("Regiment","Stock_Mines",$Stock_Max,"ID",$Regiment);
								$Stock=$Stock_Max;
							}
							$g_Arme_AT="<img src='images/icon_mine.png' title='".GetData("Armes","ID",$data['Arme_AT'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
						}
						else
						{
							$Arme_cal=round(GetData("Armes","ID",$data['Arme_AT'],"Calibre"));
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
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Munitions_".$Arme_cal);
							if($Trait_e ==14)
								$Stock_Max*=1.1;
							if($Stock > $Stock_Max)
							{
								SetData("Regiment","Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
								$Stock=$Stock_Max;
							}
							if($g_mobile ==5)
								$g_Arme_AT="<img src='images/icon_torpedo.png' title='".GetData("Armes","ID",$data['Arme_AT'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
							else
								$g_Arme_AT="<img src='images/icon_gun.png' title='".GetData("Armes","ID",$data['Arme_AT'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
							if($Stock==0)
								$g_Arme_AT.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
						}
					}
					else
						$g_Arme_AT="Aucune";
					if($data['Arme_Inf'] >0)
					{
						if($data['Arme_Inf'] ==223)
						{
							$Stock_Max=$data['Arme_Inf_mun']*$Nbr;
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Charges");
							if($Stock > $Stock_Max)
							{
								SetData("Regiment","Stock_Charges",$Stock_Max,"ID",$Regiment);
								$Stock=$Stock_Max;
							}
							$Arme_Inf=GetData("Armes","ID",$data['Arme_Inf'],"Nom").'<br><span title=\'Munitions\'>'.$Stock.'/'.$Stock_Max.' '.$Muns_txt.'</span>';
						}
						elseif($data['Arme_Inf'] ==136)
						{
							$Stock_Max=$data['Arme_Inf_mun']*$Nbr;
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Essence_87");
							if($Stock > $Stock_Max)$Stock=$Stock_Max;
							$Arme_Inf="<img src='images/icon_flame.png' title='".GetData("Armes","ID",$data['Arme_Inf'],"Nom")."'><br>".$Stock."/".$Stock_Max;
						}
						else
						{
							$Arme_cal_Inf=round(GetData("Armes","ID",$data['Arme_Inf'],"Calibre"));
							$Arme_cal=$Arme_cal_Inf;
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
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Munitions_".$Arme_cal);
							if($Trait_e ==14)
								$Stock_Max*=1.1;
							if($Stock >$Stock_Max)
							{
								SetData("Regiment","Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
								$Stock=$Stock_Max;
							}
							$Arme_Inf="<img src='images/icon_inf.png' title='".GetData("Armes","ID",$data['Arme_Inf'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
							if($Stock==0)
								$Arme_Inf.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a>";
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
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Munitions_".$Arme_cal);
							if($Trait_e ==14)
								$Stock_Max*=1.1;
							if($Stock > $Stock_Max)
							{
								SetData("Regiment","Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
								$Stock=$Stock_Max;
							}
						}
						$Arme_AA="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
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
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Munitions_".$Arme_cal);
							if($Trait_e ==14)
								$Stock_Max*=1.1;
							if($Stock > $Stock_Max)
							{
								SetData("Regiment","Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
								$Stock=$Stock_Max;
							}
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
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Munitions_".$Arme_cal);
							if($Trait_e ==14)
								$Stock_Max*=1.1;
							if($Stock > $Stock_Max)
							{
								SetData("Regiment","Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
								$Stock=$Stock_Max;
							}
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
							$Stock=GetData("Regiment","ID",$Regiment,"Stock_Munitions_".$Arme_cal);
							if($Trait_e ==14)
								$Stock_Max*=1.1;
							if($Stock > $Stock_Max)
							{
								SetData("Regiment","Stock_Munitions_".$Arme_cal,$Stock_Max,"ID",$Regiment);
								$Stock=$Stock_Max;
							}
						}
						$Arme_Inf="<img src='images/icon_flak.png' title='".GetData("Armes","ID",$data['Arme_AA3'],"Nom")." (".$Arme_cal."mm) ".$Muns_txt."'><br>".$Stock."/".$Stock_Max;
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
						$Charge.=$Fret_Qty."<br>".$Fret;
						//$Charge.=$Charge_actu.'/'.$g_Charge."<br>".$Fret;
					}
					else
						$Charge.='0/'.$g_Charge;						
					if($g_Carbu ==1)
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
							$Fuel_txt.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a><br>".$g_Fuel;
						}
						else
							$Fuel_txt="<img src='images/diesel_icon.png' title='".$g_Fuel.'/'.$Stock_Fuel_Max." Diesel'><br>".$g_Fuel;
					}
					elseif($g_Carbu ==87)
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
							$Fuel_txt.="<a href='#' class='popup'><img src='images/help.png'><span>Le ravitaillement est possible dans la gare ou le port dont la valeur stratégique est au moins égale à 4. Dans tous les cas il vaut mieux utiliser les transmissions et contacter un officier ravitailleur,cela coûtera moins cher.</span></a><br>".$g_Fuel;
						}
						else
							$Fuel_txt="<img src='images/essence_icon.png' title='".$g_Fuel.'/'.$Stock_Fuel_Max." Essence'><br>".$g_Fuel;
					}
					else
					{
						$g_Fuel=GetData("Regiment","ID",$Regiment,"Moral");
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
				<td>".$Charge."</td>
				<td>".$Range."</td><td>";				
				return $Output;
			}
		}	//End Function		
		$CT_2=2;
		$CT_4=4;
		$CT_8=8;
		$CT_Tir=20;
		$CT_Repa=$CT_MAX;
		$CT_Repa_piste=20;
		$Heure=date('H');
		if(!$Retraite)
		{
			if($Division >0)
				$Retraite=GetData("Division","ID",$Division,"Base");
			else
				$Retraite=Get_Retraite($Front,$country,30);
		}
		if($Heure_Mission ==$Heure)
		{
			/*if($Division >0)
			{
				if(GetData("Division","ID",$Division,"hatk") ==$Heure and $Cible ==GetData("Division","ID",$Division,"atk"))
					$CT_8=8;
				else
					$CT_8=16;
			}
			else*/
				$CT_8=16;
		}
		else
			$CT_8=8;
		if($Trait_e ==16)
		{
			$CT_4-=1;
			$CT_8-=1;
		}
		elseif($Trait_e ==19)
		{
			$CT_Tir=18;
		}
		elseif($Trait_e ==21 and $g_Type ==21)
		{
			$CT_2=1;
			$CT_4-=1;
			$CT_8-=1;
		}
		elseif($Trait_e ==24)
		{
			$CT_Repa-=2;
			$CT_Repa_piste-=2;
		}
		if($Cible !=$Retraite)
		{
			if($g_mobile ==5 and $Zone ==6)
			{
				if($Front ==3)
					$Limit_Stack=32;
				else
					$Limit_Stack=24;
			}
			else
				$Limit_Stack=20;
			$con=dbconnecti();
			$Stack=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
			mysqli_close($con);
			if($Stack >$Limit_Stack)
			{
				$Bonus=$Stack-$Limit_Stack;
				$CT_2+=$Bonus;
				$CT_4+=$Bonus;
				$CT_8+=$Bonus;
				$Alerte="<br><b>Trop d'unités occupent cette zone,vos actions sont ralenties!</b>";
			}
		}		
		//GetData Regiments
		$Regs="";
		$Bat_Veh_Nbr=0;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT ID,Lieu_ID,Vehicule_ID,Vehicule_Nbr,Camouflage,Move,Position,Placement,Experience,Stock_Essence_1,Stock_Essence_87,Moral,Fret,Fret_Qty,Muns,HP,Visible,Atk_H,Atk,Atk_time,DATE_FORMAT(Atk_time,'%e') as Jour,DATE_FORMAT(Atk_time,'%Hh%i') as Heure FROM Regiment WHERE Officier_ID='$OfficierID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Conso_m=0;
				$Reg_ID=$data['ID'];
				$Move=$data['Move'];
				$Position=$data['Position'];
				$Placement=$data['Placement'];
				$Vehicule_ID=$data['Vehicule_ID'];
				if(!$Recce)
					$Recce=GetData("Lieu","ID",$data['Lieu_ID'],"Recce");
				if(!$Flag)
					$Flag=GetData("Lieu","ID",$data['Lieu_ID'],"Flag");
				if(!$Zone)
					$Zone=GetData("Lieu","ID",$data['Lieu_ID'],"Zone");
				if(!$Pont)
					$Pont=GetData("Lieu","ID",$data['Lieu_ID'],"Pont");
				if(!$Pont_Ori)
					$Pont_Ori=GetData("Lieu","ID",$data['Lieu_ID'],"Pont_Ori");
				if($data['Vehicule_Nbr'] ==0 and ($data['HP'] >0 or $data['Experience'] >0))
				{
					SetData("Regiment","HP",0,"ID",$data['ID']);
					if($data['Experience'] >0 and $Trait_e !=11)
					{
						SetData("Regiment","Experience",0,"ID",$data['ID']);
						$data['Experience'] =0;
					}
				}
				$Bat_Veh_Nbr +=$data['Vehicule_Nbr'];
				$Regs.=GetCible_Infos($data['Vehicule_ID'],$data['Vehicule_Nbr'],$data['ID'],$data['HP'],$data['Experience'],$data['Muns'],$data['Fret'],$data['Fret_Qty'],$Zone,$Position,$Trait_e,$Avancement,$Front,$data['Visible']);
				if($data['HP'] ==0 and $g_mobile ==5)SetData("Regiment","Vehicule_Nbr",0,"ID",$data['ID']);				
				$Vitesses[] =$g_Vitesse;
				if($g_Carbu >0)
				{
					//$Conso_m=Get_LandConso($Zone,$g_Conso);
					$Conso=$data['Vehicule_Nbr']*$g_Conso*2;
					$Conso_txt=$Conso." Fuel";
				}
				else
				{
					$Conso=50;
					$Conso_txt="50 Moral";
				}
				//nécessaire pour option réparer dans menu principale
				if($g_Type ==98 or $g_Type ==92 or $g_Categorie ==16 or $g_Categorie ==19)
					$Genies=true;
				else
					$Genies=false;
				if($g_mobile ==5)
				{
					$PA_Esc="";
					$Tr_Cie="";
					$CT_2=2;
					$con=dbconnecti();
					$result10=mysqli_query($con,"SELECT ID,Pays,Nom,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE Porte_avions='$Vehicule_ID' AND Etat=1");
					$resulttr=mysqli_query($con,"SELECT ID,Vehicule_ID FROM Regiment WHERE Officier_ID='$Transit'");
					mysqli_close($con);
					if($result10)
					{
						while($data10=mysqli_fetch_array($result10,MYSQLI_ASSOC))
						{
							$PA_Esc.="<tr><td>".Afficher_Icone($data10['ID'],$data10['Pays'])." ".$data10['Nom']."</td><td>"
							.$data10['Avion1_Nbr']."x ".GetAvionIcon($data10['Avion1'],$data10['Pays'],0,$data10['ID'],$Front)."</td><td>"
							.$data10['Avion2_Nbr']."x ".GetAvionIcon($data10['Avion2'],$data10['Pays'],0,$data10['ID'],$Front)."</td><td>"
							.$data10['Avion3_Nbr']."x ".GetAvionIcon($data10['Avion3'],$data10['Pays'],0,$data10['ID'],$Front)."</td></tr>";
						}
						mysqli_free_result($result10);
					}
					if($resulttr)
					{
						while($datatr=mysqli_fetch_array($resulttr,MYSQLI_ASSOC))
						{
							$Tr_Cie.="<td>".$datatr['ID']."e Cie ".GetVehiculeIcon($datatr['Vehicule_ID'],$country,0,0,$Front)."</td>";
						}
						mysqli_free_result($resulttr);
					}
					if($Tr_Cie)
						$Tr_Cie="<tr>".$Tr_Cie."</tr>";
					if($PA_Esc or $Tr_Cie)
						$PA_Esc_final="<table class='table'><thead><tr><th colspan='4'>Transporte</th></tr></thead>".$PA_Esc.$Tr_Cie."</table>";
				}
				/*MAJ
				$Telephone=true;
				//END MAJ*/
				if(!$Telephone and $Position !=11 and $Position !=6 and $Position !=8 and $Position !=9)
				{
					if($Heure >1 and $Heure <7)
						$Canada=true;
					else
						$Canada=false;
					//if($G_Treve and $Front!=1 and $Front!=4 and $Front!=3)$G_Treve=false;
					if($Faction >0)
					{				
						if($data['Atk_H'] ==$Heure)
							$CT_8_final=$CT_8*2;
						else
							$CT_8_final=$CT_8;
						if($data['Vehicule_Nbr'] >0 and $Atk ==false)
							$Regs.="<form action='index.php?view=ground_consignes' method='post'>
							<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
							<input type='Submit' value='Consignes' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>";
						if($g_mobile !=4 and $g_mobile !=5) //terrestre
						{							
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_4 and $data['Camouflage'] <2 and $Move and $Atk ==false and !$g_Charge and $g_Fuel >=$Conso and $g_Detection >10 and !$Canada and !$G_Treve)
								$Regs.="<form action='index.php?view=ground_reco1' method='post'>
								<input type='hidden' name='CT' value='".$CT_4."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
								<img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
								<input type='Submit' value='Reco' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_4 and $data['Camouflage'] <2 and $Move and $Atk ==false and !$g_Charge and $g_Fuel >0 and $g_Categorie ==5 and $Cible !=$Retraite and !$Canada and !$G_Treve)
								$Regs.="<form action='index.php?view=ground_assaut' method='post'>
								<input type='hidden' name='CT' value='".$CT_4."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
								<img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
								<input type='Submit' value='Assaut' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_8_final and $data['Camouflage'] <2 and $Move and $Atk ==false and !$g_Charge and $g_Fuel >=$Conso and $g_Vitesse >1 and !$Canada and !$G_Treve
							 and $g_Type !=95 and $g_Type !=1 and $g_Type !=4 and $g_Type !=6 and $g_Type !=8 and $g_Type !=11 and $g_Type !=12)
								$Regs.="<form action='index.php?view=ground_pldef' method='post'>
								<input type='hidden' name='CT' value='".$CT_8_final."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='0'>
								<img src='/images/CT".$CT_8_final.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
								<input type='Submit' value='Attaquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_8_final and $Atk ==false and $g_Type !=95 and !$g_Charge and $g_Range >2500 and $g_Arme_Art !="Aucune" and $Position !=2 and $Position !=3 and $Position !=10 and !$Canada and !$G_Treve)
								$Regs.="<form action='index.php?view=ground_pldef' method='post'>
								<input type='hidden' name='CT' value='".$CT_8_final."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='1'>
								<img src='/images/CT".$CT_8_final.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Bombarder' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if(($data['Vehicule_ID'] ==403 or $data['Vehicule_ID'] ==372) and $data['Vehicule_Nbr'] >0 and $Credits >=$CT_4 and $Atk ==false and $Position !=2 and $Position !=3 and $Position !=10)
								$Regs.="<form action='index.php?view=ground_smoke' method='post'>
								<input type='hidden' name='CT' value='".$CT_4."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Fumée' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_8_final and $data['Camouflage'] <2 and $Cible !=$Retraite and $Atk ==false and $Move and $g_Type !=95 and !$g_Charge and $g_Fuel >=$Conso and $Recce >0 and $Flag !=$country and !$Canada and !$G_Treve)
								$Regs.="<form action='index.php?view=ground_orders' method='post'>
								<input type='hidden' name='CT' value='".$CT_8_final."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
								<img src='/images/CT".$CT_8_final.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
								<input type='Submit' value='Détruire' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >24 and $Credits >=$CT_2 and $Placement ==0 and $Atk ==false and $g_Categorie ==5 and $Flag ==$country and $Division >0)
								$Regs.="<form action='index.php?view=ground_garrison' method='post'>
								<input type='hidden' name='CT' value='".$CT_2."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT".$CT_2.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/vehicules/vehicule110.gif' title='Cette action vous retirera 25 hommes de troupe et renforcera la garnison locale'>
								<input type='Submit' value='Garnison' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
						}
						elseif($g_mobile ==5)
						{
							if($Zone ==6 or $Placement ==8 or $Position ==25)
							{
								if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_4 and $Move and $Atk ==false and !$g_Charge and $g_Fuel >=$Conso and ($g_Vitesse >25 or $g_Detection >10) and ($data['Camouflage']<2 or $Position ==25) and !$Canada and !$G_Treve)
									$Regs.="<form action='index.php?view=ground_reco1' method='post'>
									<input type='hidden' name='CT' value='".$CT_4."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
									<img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
									<input type='Submit' value='Reco' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							}
							if($Zone ==6 or $Placement ==8)
							{
								if($data['Vehicule_Nbr'] >0 and $Credits >=12 and $data['Camouflage'] <2 and $g_Vitesse >1 and $Move and $g_Type >14 and $g_Type !=21 and !$g_Charge and $Atk ==false and $g_Fuel >=$Conso and !$Canada and !$G_Treve)
									$Regs.="<form action='index.php?view=ground_pldef' method='post'>
									<input type='hidden' name='CT' value='12'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='0'>
									<img src='/images/CT12.png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
									<input type='Submit' value='Attaquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
								if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_Tir and $Atk ==false and $g_Type !=21 and !$g_Charge and $g_Range >2500 and $g_Arme_Art !="Aucune" and $Position !=25 and !$Canada and !$G_Treve)
									$Regs.="<form action='index.php?view=ground_pldef' method='post'>
									<input type='hidden' name='CT' value='".$CT_Tir."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='1'>
									<img src='/images/CT".$CT_Tir.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Tirer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
								if($data['Vehicule_Nbr'] >0 and $Credits >=4 and $data['Camouflage'] <2 and $g_Vitesse >1 and $Move and $g_Type <18 and $g_Arme_Inf !="Aucune" and !$g_Charge and $Atk ==false and $g_Fuel >=$Conso and !$Canada and !$G_Treve)
									$Regs.="<form action='index.php?view=ground_asm' method='post'>
									<input type='hidden' name='CT' value='4'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
									<img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
									<input type='Submit' value='ASM' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
								if($Placement ==8)
								{
									if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_8_final and $data['Camouflage'] !=2 and $Cible !=$Retraite and $Atk ==false and $Move and $g_Type !=21 and !$g_Charge and $g_Fuel >=$Conso and $Recce >0 and $Flag !=$country and $g_Range >2500 and $g_Arme_Art !="Aucune" and $Position !=25 and !$Canada and !$G_Treve)
										$Regs.="<form action='index.php?view=ground_orders' method='post'>
										<input type='hidden' name='CT' value='".$CT_8_final."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
										<img src='/images/CT".$CT_8_final.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
										<input type='Submit' value='Bombarder' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
								}
							}
							if($data['Vehicule_Nbr'] >0 and $Credits >=12 and $g_Vitesse >1 and $Move and $g_Type >16 and $g_Arme_AT !="Aucune" and !$g_Charge and $Atk ==false and $g_Fuel >=$Conso and !$Canada and !$G_Treve)
							{
								if($Position ==25)
									$Regs.="<form action='index.php?view=ground_pldef' method='post'>
									<input type='hidden' name='CT' value='16'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='2'>
									<img src='/images/CT16.png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
									<input type='Submit' value='Torpiller' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
								elseif(($Zone ==6 or $Placement ==8) and $data['Camouflage'] <2)
									$Regs.="<form action='index.php?view=ground_pldef' method='post'>
									<input type='hidden' name='CT' value='12'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='2'>
									<img src='/images/CT12.png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
									<input type='Submit' value='Torpiller' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							}
							if($Port >0 or $Plage >0 or $Detroit >0)
							{
								if($Mines_m >0)
								{
									if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_MAX and $Genies and $Atk ==false and $Move and $Placement ==8 and $Position !=25)
										$Regs.="<form action='index.php?view=ground_deminer' method='post'>
										<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
										<img src='/images/CT".$CT_MAX.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Déminer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
								}
								if($data['Vehicule_Nbr'] >0 and $Credits >=30 and $Arme_AT ==268 and $Atk ==false and $Move and $Position !=25 and !$Enis)
									$Regs.="<form action='index.php?view=ground_miner' method='post'>
									<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
									<img src='/images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Miner' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							}
							if($data['Fret'] ==200)
							{
								$CT_2*=2;
								if($Enis)$CT_2*=2;
							}
							/*if($g_Categorie ==18) //transport de troupes
							{
								if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_2 and $Atk ==false and !$data['Fret'] and $Zone !=6 and (($Port >0 and $Placement ==4) or ($Plage >0 and $Placement ==8)))
									$Regs.="<form action='index.php?view=ground_embark' method='post'>
									<input type='hidden' name='Unite' value='".$data['ID']."'><input type='hidden' name='Base' value='".$data['Lieu_ID']."'><input type='hidden' name='CT' value='".$CT_2."'>
									<img src='/images/CT".$CT_2.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Embarquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
								if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_2 and $Atk ==false and $g_Charge and $data['Fret'] ==200 and !$Canada and !$G_Treve and $Position !=3 and $Position !=5 and $Position !=25 and $Zone !=6 and (($Port >0 and $Placement ==4) or ($Plage >0 and $Placement ==8)))
									$Regs.="<form action='index.php?view=ground_decharge' method='post'>
									<input type='hidden' name='CT' value='".$CT_2."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Base' value='".$data['Lieu_ID']."'><input type='hidden' name='Place' value='".$Placement."'>
									<img src='/images/CT".$CT_2.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Débarquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							}*/
							if($data['Vehicule_Nbr'] >0 and $Credits >=1 and $data['Fret'] !=1 and $Atk ==false)
								$Regs.="<form action='index.php?view=ground_saborder' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'>
								<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Saborder' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
						}
						if($g_mobile !=5)
						{
							if($data['Vehicule_Nbr'] >0 and $Credits >=30 and $Genies and $g_Type !=92 and $Cible !=$Retraite and $Flag ==$country and $Placement !=0 and $Atk ==false and $Position !=3 and $Position !=4 and $Position !=5 and $Position !=10 and !$Mines and !$Enis)
								$Regs.="<form action='index.php?view=ground_mine' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Miner' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_MAX and $Genies and $g_Type !=92 and $Atk ==false and $Mines >0 and $Placement >0 and $Mines ==$Placement and $Position !=3 and $Position !=4 and $Position !=5 and $Position !=10 and !$Enis)
								$Regs.="<form action='index.php?view=ground_demine' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT".$CT_MAX.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Déminer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=8 and $Genies and $g_Type !=92 and $Atk ==false and $Pont_Ori ==100 and $Pont <100 and $Placement ==5 and $Position !=3 and $Position !=4 and $Position !=5 and $Position !=10)
								$Regs.="<form action='index.php?view=ground_ponter' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Ponter' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=30 and $Genies and $g_Type !=92 and $Atk ==false and $Faction_flag ==$Faction and $Fortification <50 and $Placement ==0 and $Position !=3 and $Position !=4 and $Position !=5 and $Position !=10)
								$Regs.="<form action='index.php?view=ground_fort' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Fortifier' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=30 and $Genies and $Atk ==false and $Pont >0 and $Placement ==5 and $Position !=4 and $Position !=3 and $Position !=5 and $Position !=10)
								$Regs.="<form action='index.php?view=ground_deponter' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Détruire le pont' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=30 and $Genies and $Atk ==false and $NoeudF >0 and $Placement ==3 and $Position !=4 and $Position !=3 and $Position !=5 and $Position !=10)
								$Regs.="<form action='index.php?view=ground_saboter_gare' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Saboter la gare' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=30 and $Genies and $Atk ==false and $Port >0 and $Placement ==4 and $Position !=3 and $Position !=4 and $Position !=5 and $Position !=10 and $g_mobile !=5)
								$Regs.="<form action='index.php?view=ground_saboter_port' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Saboter le port' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_Repa_piste and $Genies and $g_Type !=92 and $Atk ==false and $Cible_base >0 and $QualitePiste <100 and $Placement ==1 and $Position !=3 and $Position !=4 and $Position !=5 and $Position !=10 and !$Enis)
								$Regs.="<form action='index.php?view=ground_repare_piste' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='CT' value='".$CT_Repa_piste."'>
								<img src='/images/CT".$CT_Repa_piste.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Réparer la piste' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_Repa and $Genies and $g_Type !=92 and $Atk ==false and $NoeudF_Ori ==100 and $NoeudF <100 and $Placement ==3 and $Position !=3 and $Position !=4 and $Position !=5 and $Position !=10 and !$Enis)
								$Regs.="<form action='index.php?view=ground_repare_gare' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='CT' value='".$CT_Repa."'>
								<img src='/images/CT".$CT_Repa.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Réparer la gare' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_Repa and $Genies and $g_Type !=92 and $Atk ==false and $Port_Ori ==100 and $Port <100 and $Placement ==4 and $Position !=3 and $Position !=4 and $Position !=5 and $Position !=10 and !$Enis)
								$Regs.="<form action='index.php?view=ground_repare_port' method='post'>
								<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='CT' value='".$CT_Repa."'>
								<img src='/images/CT".$CT_Repa.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Réparer le port' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							if($data['Vehicule_Nbr'] >0 and $g_Type ==95 and $Credits >=2 and $Cible !=$Retraite and $Atk ==false)
								$Regs.="<form action='index.php?view=ground_rally' method='post'>
								<input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
								<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Rallier' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
						}
					}
					if($Credits >=4 and $Atk ==false and ($Cible ==$Retraite or ($g_mobile ==5 and $Port >10 and $Port_level >1)) and $Position !=11 and $Faction_flag ==$Faction and !$Enis and (($Port >10 and $Placement ==4) or ($NoeudF >10 and $Placement ==3)))
						$Regs.="<form action='index.php?view=ground_garage' method='post'>
						<input type='hidden' name='Reg' value='".$data['ID']."'>
						<img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Garage' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
					elseif($OfficierID==3)
						$Regs.="<br>Atk=".$Atk."Port=".$Port."Faction_flag=".$Faction_flag."Faction=".$Faction."NoeudF=".$NoeudF."Placement=".$Placement."Enis=".$Enis."Retraite=".$Retraite;
					if($Credits >=1 and $Atk ==false and ($Cible ==$Retraite or $ValeurStrat >3) and $Position !=11 and $Faction_flag ==$Faction and !$Enis and (($Port >10 and $Placement ==4) or ($NoeudF >10 and $Placement ==3)))
						$Regs.="<form action='index.php?view=ground_ravit' method='post'>
						<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Base' value='".$data['Lieu_ID']."'>
						<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Ravitailler' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
					if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_2 and $Atk ==false and $g_Charge and $data['Fret'] !=200 and $data['Vehicule_ID'] !=5000 and $Position !=3 and $Position !=5 and $Position !=25)
						$Regs.="<form action='index.php?view=ground_decharge' method='post'>
						<input type='hidden' name='CT' value='".$CT_2."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Base' value='".$data['Lieu_ID']."'><input type='hidden' name='Place' value='".$Placement."'>
						<img src='/images/CT".$CT_2.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Décharger' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				$Regs.="</td></tr>";
				$Base=$data['Lieu_ID'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		sort($Vitesses);
		$Speed_min=$Vitesses[0];		
?>
<div id="esc_infos">
<?if($g_mobile ==5){
	$Bat=GetEsc($country);
?>
	<h2><?echo $Bat;?></h2>
    <div class='alert alert-warning'><?echo $Bat.' '.GetPosGr($Position).' '.GetPlace($Placement,1).$Alerte;?></div>
	<?echo $PA_Esc_final;?>
	<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
		<thead><tr>         
			<th>Flottille</th>                         
			<th>Navires</th>    
			<th>ASM / DCA <a href='#' class='popup'><img src='images/help.png'><span>Grenades sous-marines ou DCA basse altitude</span></a></th>                            
			<th>Principal <a href='#' class='popup'><img src='images/help.png'><span>Utilisé lors des bombardements / DCA haute altitude pour les porte-avions</span></a></th>                            
			<th>Torpilles / Mines <a href='#' class='popup'><img src='images/help.png'><span>Lance-torpilles ou Mines</span></a></th>                            
			<th>DCA <a href='#' class='popup'><img src='images/help.png'><span>Uniquement défensif. Utilisé contre les attaques aériennes</span></a></th>
			<th>Carburant</th>
			<th>Fret <a href='#' class='popup'><img src='images/help.png'><span>Capacité de transport de fret</span></a></th>
			<th>Rayon d'action <a href='#' class='popup'><img src='images/help.png'><span>Distance aller retour possible avec le carburant actuel</span></a></th>
			<th>Actions des Flottilles</th>
		</tr></thead>
<?}else{?>
	<h2>Bataillon</h2>
    <div class='alert alert-warning'><?echo GetPosGr($Position).' '.GetPlace($Placement).$Alerte;?></div>
	<?if($Placement ==2 or $Placement ==3 or $Placement ==5 or $Placement ==0) 
		echo "<div class='alert alert-danger'>En stationnant sur la caserne, la gare, le pont ou le noeud routier, vous risquez de créer un embouteillage empêchant les autres unités de se ravitailler ou de se déplacer.<br>Privilégez les autres zones si possible.</div>";
	?>
	<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
		<thead><tr>                                   
			<th>Unité</th>                         
			<th>Véhicules / Troupes</th>                         
			<th>Armement <a href='#' class='popup'><img src='images/help.png'><span>Utilisé contre les cibles non blindées</span></a></th>                            
			<th>Soutien <a href='#' class='popup'><img src='images/help.png'><span>Utilisé lors des bombardements et des démolitions</span></a></th>                            
			<th>Anti-tank <a href='#' class='popup'><img src='images/help.png'><span>Utilisé contre les cibles blindées</span></a></th>                            
			<th>DCA <a href='#' class='popup'><img src='images/help.png'><span>Uniquement défensif. Utilisé contre les attaques aériennes</span></a></th>
			<th>Carburant<a href='#' class='popup'><img src='images/help.png'><span>Maximum 25000 litres</span></a></th>
			<th>Charge <a href='#' class='popup'><img src='images/help.png'><span>Capacité de transport de fret</span></a></th>
			<th>Rayon d'action <a href='#' class='popup'><img src='images/help.png'><span>Distance aller retour possible avec le carburant actuel</span></a></th>
			<th>Actions des Compagnies</th>
		</tr></thead>
	<?}echo $Regs;?>
	</table></div>
</div>
<?
	}
	else
	{		
		echo "Peut-être le reverrez-vous un jour votre Bataillon...";
		echo '<img src=\'images/unites'.$country.'.jpg\'>';
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>