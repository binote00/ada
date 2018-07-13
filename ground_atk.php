<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
$Action=Insec($_POST['Action']);
if($Action and ($OfficierID >0 or $OfficierEMID))
{
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	$CT=Insec($_POST['CT']);
	if($OfficierID)
		$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	elseif($OfficierEMID)
		$Credits=GetData("Officier_em","ID",$OfficierEMID,"Credits");
	if(!$CT and $Action !=110)$CT=8;	
	if($Credits >=$CT or $Action ==110)
	{
		$Atk=true;
		$country=$_SESSION['country'];
		$Reg=Insec($_POST['Reg']);
		$Vehicule=Insec($_POST['Veh']);
		$Cible=Insec($_POST['Cible']);
		if($CT)
		{
			$Conso=Insec($_POST['Conso']);
			$con=dbconnecti();
			$Veh_Carbu=mysqli_result(mysqli_query($con,"SELECT Carbu_ID FROM Cible WHERE ID='$Vehicule'"),0);
			$result=mysqli_query($con,"SELECT Vehicule_Nbr,Experience,Placement FROM Regiment_IA WHERE ID='$Reg'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Vehicule_Nbr=$data['Vehicule_Nbr'];
					$Vue=$data['Experience'];
					$Placement=$data['Placement'];
				}
				mysqli_free_result($result);
			}
			UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
			if($Veh_Carbu ==87 or $Veh_Carbu ==1)
				$Stock="Stock_Essence_".$Veh_Carbu;
			else
				$Stock="Moral";
			$Jauge=GetData("Regiment","ID",$Reg,$Stock);
			if($Jauge >=$Conso)
				UpdateData("Regiment",$Stock,-$Conso,"ID",$Reg);
			else
			{
				$Diff=($Conso-$Jauge)/$Conso;
				SetData("Regiment",$Stock,0,"ID",$Reg);
				$Charisme=0;
				if(GetData("Officier","ID",$OfficierID,"Trait") ==6)
					$Charisme=mt_rand(0,1);
				if($Diff >0 and !$Charisme)
				{
					UpdateData("Regiment","Vehicule_Nbr",-$Diff,"ID",$Reg);
					UpdateData("Regiment","Moral",-$Diff,"ID",$Reg);
					AddEventGround(410, $Vehicule,$OfficierID,$Reg,$Cible,$Diff);
					$mes.="<br>Une partie de vos troupes déserte!";
				}
			}
			$DB="Regiment";
			$DBA=0;
		}
		else
		{
			$DB="Regiment_IA";
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Vehicule_Nbr,Experience,Placement FROM Regiment_IA WHERE ID='$Reg'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Vehicule_Nbr=$data['Vehicule_Nbr'];
					$Vue=$data['Experience'];
					$Placement=$data['Placement'];
				}
				mysqli_free_result($result);
			}
			if(!$Vue)$Vue=50;
			$DBA=1;
		}
		$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$result=mysqli_query($con,"SELECT Nom,Zone,ValeurStrat,Camouflage,Meteo,Recce,Garnison,Flag FROM Lieu WHERE ID='$Cible'");
		$result1=mysqli_query($con,"SELECT HP,Blindage_f,Vitesse,Taille,mobile,Reput,Type,Portee FROM Cible WHERE ID='$Vehicule'");
		$resultm=mysqli_query($con,"SELECT Qty,Detect_Axe,Detect_Allie FROM Mines WHERE Lieu_ID='$Cible' AND Zone='$Placement'");
		mysqli_close($con);
		if($resultm)
		{
			while($datam=mysqli_fetch_array($resultm,MYSQLI_ASSOC))
			{
				$Mines=$datam['Qty'];
				$Detect_Axe=$datam['Detect_Axe'];
				$Detect_Allie=$datam['Detect_Allie'];
			}
			mysqli_free_result($resultm);
			if($Faction ==2 and $Detect_Allie)
				$Detect_mines=true;
			elseif($Faction ==1 and $Detect_Axe)
				$Detect_mines=true;
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Lieu_Nom=$data['Nom'];
				$Zone=$data['Zone'];
				$ValStrat=$data['ValeurStrat'];
				$meteo=$data['Meteo'];
				$Camouflage=$data['Camouflage'];
				$Recce=$data['Recce'];
				$Garnison=$data['Garnison'];
				$Flag=$data['Flag'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		switch($Zone)
		{
			case 0:
				$zone_txt="prairie";
				$Malus_Reperer=0;
			break;
			case 1:
				$zone_txt="colline";
				$Malus_Reperer=10;
			break;
			case 2:
				$zone_txt="forêt";
				$Malus_Reperer=20;
			break;
			case 3:
				$zone_txt="colline boisée";
				$Malus_Reperer=50;
			break;
			case 4:
				$zone_txt="montagne";
				$Malus_Reperer=50;
			break;
			case 5:
				$zone_txt="montagne boisée";
				$Malus_Reperer=100;
			break;
			case 6:
				$zone_txt="vague";
				$Malus_Reperer=-$meteo;
			break;
			case 7:
				$zone_txt="maison";
				$Malus_Reperer=50;
			break;
			case 8:
				$zone_txt="dune";
				$Malus_Reperer=10;
			break;
			case 9:
				$zone_txt="jungle";
				$Malus_Reperer=30;
			break;
			case 11:
				$zone_txt="marécage";
				$Malus_Reperer=10;
			break;
		}			
		if($result1)
		{
			while($data=mysqli_fetch_array($result1,MYSQLI_ASSOC))
			{
				$HP=$data['HP'];
				$HP_ori=$HP;
				$Blindage=$data['Blindage_f'];
				$Vitesse=$data['Vitesse'];
				$Taille=$data['Taille'];
				$mobile=$data['mobile'];
				$Reput=$data['Reput'];
				$Type=$data['Type'];
				$Portee=$data['Portee'];				
				$Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type);			
				if(!$Blindage)$Blindage=Get_Blindage($Zone,$Taille,0,2);				
			}
			mysqli_free_result($result1);
			unset($data);
		}		
		if($Action ==110 or $Action ==10)
			$Place=0;
		else
			$Place=$Action;
		if($mobile !=5) //Riposte
		{
			$con=dbconnecti();
			$pj_unit=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Officier_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,c.Arme_Art,c.Arme_AT,c.Arme_Inf FROM Regiment_IA as r,Cible as c 
			WHERE r.Vehicule_ID=c.ID AND c.Portee >500 AND c.Charge=0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Pays='$Flag' AND r.Placement='$Place' AND r.Position IN (1,3,5,10)");
			/*(SELECT r.ID,r.Vehicule_ID,r.Officier_ID,r.Vehicule_Nbr,r.Experience,r.Position,r.Placement,c.Arme_Art,c.Arme_AT,c.Arme_Inf FROM Regiment as r,Cible as c 
			WHERE r.Vehicule_ID=c.ID AND c.Portee >500 AND c.Charge=0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Pays='$Flag' AND r.Placement='$Place' AND r.Position IN (1,3,5,10)) UNION (*/
			mysqli_close($con);
			if($pj_unit)
			{
				$Update_XP_eni=0;
				while($data=mysqli_fetch_array($pj_unit))
				{
					$Reg_eni_r=$data['ID'];
					$EXP=$data['Experience'];
					$Vehicule_ID_r=$data['Vehicule_ID'];
					$Vehicule_Nbr_r=$data['Vehicule_Nbr'];
					$Position_r=$data['Position'];
					$Placement_r=$data['Placement'];
					if($Placement ==$Placement_r or ($Position_r ==5 and $data['Arme_Art']))
					{
						if($Blindage >0 and $data['Arme_AT'])
							$Arme=$data['Arme_AT'];
						elseif($data['Arme_Art'])
							$Arme=$data['Arme_Art'];
						else
							$Arme=$data['Arme_Inf'];										
						$Arme_Cal=round(GetData("Armes","ID",$Arme,"Calibre"));
						if($Arme_Cal >0)
						{
							$Arme_Multi=GetData("Armes","ID",$Arme,"Multi");
							if($data['Officier_ID'])
							{
								$Muns_Stock=GetData("Regiment","ID",$Reg_eni_r,"Stock_Munitions_".$Arme_Cal);
								$Reg_a_ia=1;
							}
							else
							{
								$Muns_Stock=9999;
								$Reg_a_ia=0;
							}
							$Muns_Conso=$data['Vehicule_Nbr']*$Arme_Multi;						
							if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
							{
								if($data['Officier_ID'])
									UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Muns_Conso,"ID",$Reg_eni_r);
								$Tir=mt_rand(0,$EXP);
								$Shoot=$Tir+$meteo-$Malus_Range+$Taille-$Vitesse;
								if($Shoot >1 or $Tir ==$EXP)
								{
									$Degats=round((mt_rand(1,GetData("Armes","ID",$Arme,"Degats"))-$Blindage)*GetShoot($Shoot,$Arme_Multi));
									if($Degats <1)$Degats=mt_rand(1,10);
									$HP-=$Degats;
								}
								if($HP <1)
								{
									$mes.='<br>Le tir ennemi détruit une de vos unités. (<b>'.$Degats.'</b> points de dégats!)';
									UpdateData($DB,"Vehicule_Nbr",-1,"ID",$Reg);
									AddEventGround(400,$Vehicule,$OfficierID,$Reg,$Cible,1,$Reg_eni_r);
									AddGroundAtk($Reg_eni_r,$Reg,$Vehicule_ID_r,$Vehicule_Nbr_r,$Vehicule,$Vehicule_Nbr,$Position_r,4,$Cible,$Placement_r,1,$Reg_a_ia,$DBA);
									$Update_XP_eni+=$Reput;
									$HP=$HP_ori;
									SetData($DB,"HP",$HP,"ID",$Reg);
								}
								else
								{
									$mes.='<br>Le tir ennemi endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
									$Update_XP_eni+=1;
								}
							}
							else
							{
								$mes.="<br>L'ennemi reste étrangement silencieux!";
								break;
							}
							if($Update_XP_eni >0 and $data['Officier_ID'])
								UpdateData("Regiment","Experience",$Update_XP_eni,"ID",$Reg_eni_r);
						}
						else
							$mes.='<br>Avec quoi tirez-vous?';
					}
				}
				mysqli_free_result($pj_unit);
				unset($data);
			}
		}
		$Vehicule_Nbr=GetData($DB,"ID",$Reg,"Vehicule_Nbr"); //Ne pas déplacer		
		//Mines
		if($Mines >0)
		{
			$Malus_Mines=($Taille-$Experience)/2;
			if($Type ==98)
				$Malus_Mines-=25;
			if($Detect_mines)
				$Malus_Mines-=50;
			if((mt_rand(0,$Mines)-$Malus_Mines-$Meteo) <$Mines)
			{
				if($Malus_Mines <1)$Malus_Mines=1;
                $mines_txt='<div class="alert alert-danger">Vos troupes se retrouvent au milieu d\'un champ de mines!';
				$Degats_mine=mt_rand(500,2000)*$Malus_Mines;
				if($Degats_mine <500)$Degats_mine=mt_rand(250,500);
				if($Degats_mine >=$HP)
				{
					$Nbr_out=floor($Degats_mine/$HP);
					if($Nbr_out >$Vehicule_Nbr)$Nbr_out=$Vehicule_Nbr;
					$mines_txt.='<br>Le champ de mines détruit <b>'.$Nbr_out.'</b> troupes !';
					UpdateData($DB,"Vehicule_Nbr",-$Nbr_out,"ID",$Reg);
					/*$HP=$HP_ori;
					SetData($DB,"HP",$HP,$Reg);*/
					$Vehicule_Nbr-=$Nbr_out;
					AddEventGround(420,$Vehicule,$OfficierID,$Reg,$Cible,$Nbr_out,$Place);
				}
				elseif($Degats_mine >0)
                    $mines_txt.='<br>Le champ de mines endommage une de vos unités, lui occasionnant <b>'.$Degats_mine.'</b> points de dégats!';
				else
                    $mines_txt.='<br>L\'élément de votre unité pénètre sur un champ de mines, mais s\'en sort indemne!';
                $mines_txt.='</div>';
			}
		}
		/*if($Mines >0 and $Mines ==$Place)
		{
			$Mines_des=0;
			for($t=1;$t<=10;$t++)
			{
				$Degats=mt_rand(100,10000)-$Blindage;
				if($Degats >$HP)
				{
					$mes.='<br>Un champ de mines détruit une de vos unités ! (<b>'.$Degats.'</b> points de dégats!)';
					UpdateData($DB,"Vehicule_Nbr",-1,"ID",$Reg);
					$HP=$HP_ori;
					SetData($DB,"HP",$HP,$Reg);
					$Vehicule_Nbr-=1;
					$Mines_des+=1;
				}
				elseif($Degats >0)
				{
					$mes.='<br>Un champ de mines endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
					$HP-=$Degats;
				}
				else
					$mes.="<br>L'élément de votre unité pénètre sur un champ de mines, mais s'en sort indemne!";
				if($Vehicule_Nbr <1)
					break;
			}
			if($Mines_des >0)
				AddEventGround(420,$Vehicule,$OfficierID,$Reg,$Cible,$Mines_des,$Place);
		}*/
		if($Vehicule_Nbr >0)
		{			
			$choix1='';
			$choix2='';
			$choix3='';
			$choix4='';
			$choix5='';
			$choix6='';
			$choix7='';
			$choix_dive='Attaquer';
			if($Action !=110)
			{
				if($mobile ==5)
					$Range=$Portee;
				else
					$Range=$Vitesse*50;
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Camouflage FROM $DB as r,Cible as c 
				WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.ID<>'$Reg' AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Visible=1 AND r.Placement='$Place' AND c.Portee <='$Range'");		
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						if($BonusDetect+mt_rand(0,$Vue) >$Malus_Reperer+($data['Camouflage']*50)+($alt/100))
							$choix7.="<Input type='Radio' name='Action' value='".$data['ID']."_'>- ".$choix_dive." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".GetData("Cible","ID",$data['Vehicule_ID'],"Nom")."'> ".$data['Vehicule_Nbr']."<br>";
					}
					mysqli_free_result($result);
					unset($data);
				}
			}
			if($Action ==110 or $Place ==1) //Aérodrome
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID,Pays,Nom,Garnison FROM Unit WHERE Base='$Cible' AND Pays<>'$country' AND Porte_avions=0");
				mysqli_close($con);
				if($result)
				{
					while($datae=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$choix7.="<Input type='Radio' name='Action' value='".$datae['ID']."é'>- ".$choix_dive." <img src='images/vehicules/vehicule107.gif' title='Troupe de défense de la ".$datae['Nom']."'> ".$datae['Garnison']." ".Afficher_Icone($datae['ID'],$datae['Pays'],$datae['Nom'])."<br>";
					}
					mysqli_free_result($result);
					unset($datae);
				}
			}
			if($Recce ==2)
			{
				if($Nuit)
					$Recce=100;
				else
					$Recce=50;
			}			
			switch($Action)
			{
				case 1:
					$DCA_ID=false;
					$choix2="";
					$con=dbconnecti();
					$result2=mysqli_query($con,"SELECT Tour,Camouflage FROM Lieu WHERE ID='$Cible'");
					$result=mysqli_query($con,"SELECT DCA_ID,DCA_Nbr FROM Flak WHERE Lieu='$Cible' AND DCA_Nbr >0");
					$avions_parques=mysqli_result(mysqli_query($con,"SELECT COUNT(Avion1_Nbr + Avion2_Nbr + Avion3_Nbr) FROM Unit WHERE Base='$Cible' AND Etat=1"),0);
					mysqli_close($con);
					if($result2)
					{
						while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							$Tour=$data['Tour'];
							$Camouflage=$data['Camouflage'];
						}
						mysqli_free_result($result2);
						unset($data);
					}
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$DCA_ID=$data['DCA_ID'];
							if($DCA_ID and $BonusDetect + $Vue + $Recce > $Malus_Reperer + $Camouflage)
								$choix2.="<Input type='Radio' name='Action' value='2_".$DCA_ID."'>- ".$choix_dive." <img src='images/aa".$DCA_ID.".png' title='une batterie de DCA'> DCA<br>";
							$DCA_ID=false;
						}
						mysqli_free_result($result);
						unset($data);
					}
					if($avions_parques)
						$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule1000.gif' title='un avion le long de la piste'><br>";
					if($Tour)
						$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule2.gif' title='la tour de contrôle'><br>";
					$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un hangar'><br>";
					$Cible_Atk=1;
				break;
				case 3:
					$hp_gare=GetData("Lieu","ID",$Cible,"NoeudF");
					if($hp_gare >0)
					{
						if($hp_gare >50)
						{
							$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule8.gif' title='les voies'><br>";
							$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
						}
						$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule9.gif' title='le bâtiment principal'><br>";
					}
					else
						$choix1="La gare n'est plus qu'un amoncellement de ruines fumantes!<br>";
					if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + $Vue + $Recce > $Malus_Reperer + mt_rand(10,50) - $meteo)
						$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
					$Cible_Atk=4;
				break;
				case 4:
					$hp_Port=GetData("Lieu","ID",$Cible,"Port");
					if($hp_Port >0)
					{
						if($hp_Port >50)
						{
							if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) - $meteo)
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
							if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) - $meteo)
								$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule11.gif' title='les réserves de carburant'><br>";
						}
						if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) - $meteo)
							$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule12.gif' title='les docks'><br>";
						else
							$choix4="<Input type='Radio' name='Action' value='6'>- ".$choix_dive." au hasard.<br>";
					}
					else
						$choix1="Le port n'est plus qu'un amoncellement de ruines fumantes!<br>";
					if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) - $meteo)
						$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
					$Cible_Atk=6;
				break;
				case 5:
					if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) - $meteo)
						$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." un véhicule<br>";
					if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) - $meteo)
						$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
					if(GetData("Lieu","ID",$Cible,"Pont") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) - $meteo)
					{
						$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." le pont, en enfilade<br>";
						$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." le pont, perpendiculairement<br>";
					}
					$Cible_Atk=5;
				break;
				case 6:
					$Usine_hp=GetData("Lieu","ID",$Cible,"Industrie");
					if($Usine_hp >0)
					{
						if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) - $meteo)
						{
							if($Usine_hp >50)
							{
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule1.gif' title='un entrepôt'><br>";
								$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." <img src='images/vehicules/vehicule3.gif' title='un bâtiment secondaire'><br>";
							}
							$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule4.gif' title='un bâtiment principal'><br>";
						}
						else
							$choix4="<Input type='Radio' name='Action' value='6'>- ".$choix_dive." au hasard.<br>";
					}
					else
						$choix1="L'usine n'est plus qu'un amoncellement de ruines fumantes!<br>";
					
					if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) - $meteo)
						$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
					$Cible_Atk=2;
				break;
				case 7:
					$hp_Radar=GetData("Lieu","ID",$Cible,"Radar");
					if($hp_Radar >0)
					{
						if($hp_Radar >50)
						{
							if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,25) - $meteo)
								$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." <img src='images/vehicules/vehicule13.gif' title='un bâtiment secondaire'><br>";
						}
						elseif($hp_Radar >25)
						{
							if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) - $meteo)
								$choix3="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." <img src='images/vehicules/vehicule15.gif' title='une antenne'><br>";
						}
						if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,20) - $meteo)
							$choix4="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." le bâtiment principal<br>";
						else
							$choix4="<Input type='Radio' name='Action' value='6'>- ".$choix_dive." au hasard.<br>";
					}
					else
						$choix1="La station radar n'est plus qu'un amoncellement de ruines fumantes!<br>";
					if(GetData("Lieu","ID",$Cible,"DefenseAA_temp") >0 and $BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(10,50) - $meteo)
						$choix2="<Input type='Radio' name='Action' value='2'>- ".$choix_dive." <img src='images/vehicules/vehicule16.gif' title='un emplacement de D.C.A'><br>";
					$Cible_Atk=7;
				break;
				case 10: case 110:
					if($Garnison >0)
						$choix1="<Input type='Radio' name='Action' value='1'>- ".$choix_dive." les soldats de la garnison<br>";
					if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) - $meteo)
						$choix3="<Input type='Radio' name='Action' value='3'>- ".$choix_dive." un bâtiment secondaire<br>";
					if($BonusDetect + mt_rand(0,$Vue) + $Recce > $Malus_Reperer + mt_rand(0,10) - $meteo)
						$choix4="<Input type='Radio' name='Action' value='4'>- ".$choix_dive." le bâtiment principal<br>";
					$Cible_Atk=3;
				break;
			}			
			/*$con=dbconnecti();
			$result=mysqli_query($con,"SELECT ID,Vehicule_ID FROM Regiment WHERE Officier_ID='$OfficierID' AND Vehicule_Nbr >0");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$choix_reg.="<Input type='Radio' name='Reg' value='".$data['ID']."'>- ".$data['ID']."e Cie <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'><br>";
				}
			}*/			
			$titre='Attaque de '.$Lieu_Nom;
			$mes="<table class='table'>
			<thead><tr><th>Vos Troupes</th><th>Expérience</th><th>Terrain</th><th>Météo</th></tr></thead>
			<tr><td><img src='images/vehicules/vehicule".$Vehicule.".gif'></td><td>".$Vue."</td><td><img src='images/zone".$Zone.".jpg'></td><td><img src='images/meteo".$meteo.".jpg'></td></tr>
			</table>";
			$intro.="<form action='index.php?view=ground_atk1' method='post'>
			<input type='hidden' name='Meteo' value=".$meteo.">
			<input type='hidden' name='Cible_Atk' value=".$Cible_Atk.">
			<input type='hidden' name='Reg' value='".$Reg."'>
			<input type='hidden' name='Veh' value='".$Vehicule."'>
			<input type='hidden' name='Cible' value='".$Cible."'>
			<input type='hidden' name='DBA' value='".$DBA."'>
			<input type='hidden' name='A110' value='".$Action."'>
			<table class='table'>
				<tr><td align='left'>".$choix1.$choix2.$choix3.$choix4.$choix5.$choix6.$choix7."
					<Input type='Radio' name='Action' value='999' checked>- Annuler l'attaque.<br>
				</td></tr>
				<tr><td align='left'>".$choix_reg."</td></tr>
				</table>
			<input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		else
			$mes.="<div class='alert alert-danger'>L'attaque est stoppée!<br>Soit parce que votre unité a été décimée, soit parce que des unités ennemies non détectées ont empêché vos unités de progresser jusqu'à la caserne ennemie!</div>";
        $mes.=$mines_txt;
        if($mobile ==5)
			$img=Afficher_Image('images/nav_gunfire.jpg',"images/image.png","");
		else
			$img=Afficher_Image('images/attack.jpg',"images/image.png","");
		include_once('./default.php');
	}
	else
		echo "<h6>Vous n'avez pas assez de crédits!</h6>";
		
}