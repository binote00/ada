<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	$alerte_txt="<p class='lead'>Ce mode de jeu est actuellement en test.<br>En tant que testeur, vous êtes invité à signaler tout bug ou à faire part de vos remarques sur le forum</p>";
	$date=date('Y-m-d G:i');
	$con=dbconnecti();
	$reset1=mysqli_query($con,"UPDATE Officier_PVP SET Con_date='$date' WHERE ID='$Officier_pvp'");
	$result=mysqli_query($con,"SELECT Front,Pays,Division,Note,Atk,Con_date FROM Officier_PVP WHERE ID='$Officier_pvp'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_menu-off');
	$result_chato=mysqli_query($con,"SELECT Nom FROM Officier_PVP WHERE Con_date BETWEEN NOW() - INTERVAL 5 MINUTE AND NOW()") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_menu-chatoff');
	$result_chatp=mysqli_query($con,"SELECT Nom FROM Pilote_PVP WHERE Con_date BETWEEN NOW() - INTERVAL 5 MINUTE AND NOW()") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_menu-chatpil');
	mysqli_close($con);
	if($result_chato)
	{
		while($datacd=mysqli_fetch_array($result_chato,MYSQLI_ASSOC))
		{
			$Connectesb.="<br><img src='images/led_green.png'>".$datacd['Nom'];
		}
		mysqli_free_result($result_chato);
		unset($datacd);
	}
	if($result_chatp)
	{
		while($datacp=mysqli_fetch_array($result_chatp,MYSQLI_ASSOC))
		{
			$Connectesb.="<br><img src='images/led_green.png'>".$datacp['Nom'];
		}
		mysqli_free_result($result_chatp);
		unset($datacp);
	}
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Battle=$data['Front'];
			$Faction=$data['Pays'];
			$Reg=$data['Division'];
			$Veh=$data['Note'];
			$Atk=$data['Atk'];
			$Con_date=$data['Con_date'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	$con=dbconnecti(5);
	$resultc=mysqli_query($con,"SELECT ID,Battle,PlayerID,Mode,Faction,Msg,DATE_FORMAT(BDate,'%d-%m %H:%i') as Date_Chat FROM Bchat WHERE Battle IN(0,'$Battle') ORDER BY ID DESC LIMIT 10") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_menu-off');
	mysqli_close($con);
	if($resultc)
	{
		while($datac=mysqli_fetch_array($resultc,MYSQLI_ASSOC))
		{
			$Battle_chat=$datac['Battle'];
			$Faction_chat=$datac['Faction'];
			$Chat_txt=nl2br($datac['Msg']);
			if($datac['Mode']==1)
				$DB_Chatter="Pilote_PVP";
			elseif($datac['Mode']==2)
				$DB_Chatter="Officier_PVP";
			elseif($datac['Mode']==9)
				$DB_Chatter="ADMIN";
			$Chatter=GetData($DB_Chatter,"ID",$datac['PlayerID'],"Nom");
			if(!$Battle_chat)
			{
				$Chat_open_b.="<br>".$datac['Date_Chat']." : [".$Chatter."] >".$Chat_txt;
			}
			elseif($Faction_chat ==$Faction)
			{
				$Chat_faction_b.="<br>".$datac['Date_Chat']." : [".$Chatter."] >".$Chat_txt;
			}
		}
		mysqli_free_result($resultc);
		unset($datac);
	}
	if($Reg >0 and $Veh >0)
	{
		if($Battle and $Faction)
		{
			$con=dbconnecti(2);
			$result3=mysqli_query($con,"SELECT Bat_Date,Allies_inscrits,Axe_inscrits,DATE_FORMAT(Bat_Date,'%d-%m-%Y %H:%i') as Date_txt FROM Battle_score WHERE ID='$Battle'");
			mysqli_close($con);
			if($result3)
			{
				while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					$Depart=$data3['Bat_Date'];
					$Date_txt=$data3['Date_txt'];
					$Allies_inscrits=$data3['Allies_inscrits'];
					$Axe_inscrits=$data3['Axe_inscrits'];
				}
				mysqli_free_result($result3);
			}
			if($date >=$Depart and $Allies_inscrits >1 and $Axe_inscrits >1)
			{
				include_once('./jfv_txt.inc.php');
				include_once('./jfv_nav.inc.php');
				include_once('./jfv_map.inc.php');
				include_once('./jfv_ground.inc.php');
				//include_once('./jfv_combat.inc.php');
				include_once('./jfv_inc_pvp.php');
				include_once('./jfv_inc_cible_infos.php');
				$start=true;
				$Regs="";
				$CT_2=0;
				$CT_4=0;
				$CT_8=0;
				$CT_8_final=0;
				$Axe_up=0;
				$Allies_up=0;
				$Cible=GetCiblePVP($Battle);
				$Front=GetFrontPVP($Battle);
				if($Faction ==1)
					$Pays_Allies="1,6,9,15,18,19,20";
				else
					$Pays_Allies="2,3,4,5,7,8,10,35,36";
				$con=dbconnecti();
				$resultvic=mysqli_query($con,"SELECT r.ID,r.Moves,r.Stock_AT,r.Stock_Art,r.Vehicule_Nbr,r.Pays,c.Fuel,c.Fiabilite,c.Arme_Art,c.Arme_AT,c.Arme_Art_mun,c.Arme_AT_mun FROM 
				Regiment_PVP as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Front='$Battle' AND r.Officier_ID >0")
				 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_menu-regvic');
				$resultl=mysqli_query($con,"SELECT Nom,Pays,Zone,Map,Meteo,Meteo_Hour,Latitude,Longitude,Occupant,ValeurStrat,DefenseAA_temp,BaseAerienne,QualitePiste,Industrie,Pont_Ori,Pont,Radar,Radar_Ori,Port,Port_Ori,Port_level,NoeudR,NoeudF,NoeudF_Ori,
				Mines,Mines_m,Fortification,Garnison,Recce,Flag,Plage,Detroit,Flag_Air,Flag_Route,Flag_Gare,Flag_Port,Flag_Pont,Flag_Usine,Flag_Radar,Flag_Plage,Fleuve FROM Lieu WHERE ID='$Cible'")
				 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_menu-lieu');
				$result=mysqli_query($con,"SELECT ID,Lieu_ID,Vehicule_ID,Vehicule_Nbr,Camouflage,Move,Position,Placement,Experience,Stock_Essence_1,Stock_Essence_87,Moral,Muns,HP,Visible,Atk,Distance,Stock_AT,Stock_Art,Moves FROM Regiment_PVP WHERE ID='$Reg'")
				 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_menu-reg');
				$resultal=mysqli_query($con,"SELECT ID,Mission_Type_D FROM Regiment_PVP WHERE Pays IN (".$Pays_Allies.") AND Lieu_ID='$Cible' AND r.Front='$Battle' AND r.Officier_ID >0 AND Mission_Type_D >0");
				$resultev=mysqli_query($con,"SELECT * FROM gnmh_aubedesaiglesnet5.Events_Battle WHERE Reg='$Reg'");
				mysqli_close($con);
				if($resultvic)
				{
					while($datavic=mysqli_fetch_array($resultvic,MYSQLI_ASSOC))
					{
						$Moves_tmp=floor($datavic['Fuel']/10)+($datavic['Fiabilite']*5);
						if($datavic['Arme_Art'])
							$Stock_Art_tmp=($datavic['Arme_Art_mun']-$datavic['Stock_Art'])*$datavic['Vehicule_Nbr'];
						else
							$Stock_Art_tmp=1;
						/*if($datavic['Arme_AT'])
							$Stock_AT_tmp=($datavic['Arme_AT_mun']-$datavic['Stock_AT'])*$datavic['Vehicule_Nbr'];*/
						if($datavic['Vehicule_Nbr'])
						{
							if($datavic['Pays'] ==1 or $datavic['Pays'] ==6 or $datavic['Pays'] ==9 or $datavic['Pays'] ==15 or $datavic['Pays'] ==18 or $datavic['Pays'] ==20)
								$Axe_up++;
							elseif($datavic['Pays'] ==2 or $datavic['Pays'] ==3 or $datavic['Pays'] ==4 or $datavic['Pays'] ==5 or $datavic['Pays'] ==7 or $datavic['Pays'] ==8 or $datavic['Pays'] ==10)
								$Allies_up++;
							/*if($datavic['Moves'] >=$Moves_tmp or !$Stock_Art_tmp)
							{
							}*/
						}
					}
					mysqli_free_result($resultvic);
					unset($datavic);
					$Infos.="<br>Les Alliés possèdent ".$Allies_up." Cie en état de se battre<br>L'Axe possède ".$Axe_up." Cie en état de se battre";
				}
				/*if(!$Allies_up or !$Axe_up)
					$Fin_Battle=true;
				else
				{*/
					if($resultev)
					{
						while($dataev=mysqli_fetch_array($resultev,MYSQLI_ASSOC))
						{
							if($dataev['Event'] ==1)
								$Events_txt.="<br>Votre Compagnie subit <b>".$dataev['Degats']."</b> dégâts lors d'une attaque de la ".$dataev['Reg']."e Compagnie";
							elseif($dataev['Event'] ==2)
								$Events_txt.="<br>Votre Compagnie subit <b>".$dataev['Degats']."</b> dégâts lors d'un bombardement de la ".$dataev['Reg']."e Compagnie";
						}
						mysqli_free_result($resultev);
						unset($dataev);
					}
					if($resultal)
					{
						while($datal=mysqli_fetch_array($resultal,MYSQLI_ASSOC))
						{
							if($datal['Mission_Type_D'] ==1)
								$Mission_Type_D="e attaque imminente!";
							elseif($datal['Mission_Type_D'] ==2)
								$Mission_Type_D=" bombardement imminent!";
							elseif($datal['Mission_Type_D'] ==3)
								$Mission_Type_D="e couverture de la DCA!";
							elseif($datal['Mission_Type_D'] ==4)
								$Mission_Type_D="e protection!";
							elseif($datal['Mission_Type_D'] ==5)
								$Mission_Type_D="e reconnaissance!";
							$Alertes.="<br>La ".$datal['ID']."e Cie demande un".$Mission_Type_D;
						}
						mysqli_free_result($resultal);
						unset($datal);
					}
					if($resultl)//Lieu
					{
						while($data=mysqli_fetch_array($resultl,MYSQLI_ASSOC))
						{
							$Cible_nom=$data['Nom'];
							$Pays_Ori=$data['Pays'];
							$Cible_DefenseAA=$data['DefenseAA_temp'];
							$ValeurStrat=$data['ValeurStrat'];
							$Cible_base=$data['BaseAerienne'];
							$QualitePiste=$data['QualitePiste'];
							$Usine=$data['Industrie'];
							$Pont=$data['Pont'];
							$Pont_Ori=$data['Pont_Ori'];
							$Port=$data['Port'];
							$Port_Ori=$data['Port_Ori'];
							$Port_level=$data['Port_level'];
							$Radar=$data['Radar'];
							$Radar_Ori=$data['Radar_Ori'];
							$NoeudR=$data['NoeudR'];
							$NoeudF=$data['NoeudF'];
							$NoeudF_Ori=$data['NoeudF_Ori'];
							$Zone=$data['Zone'];
							$Map=$data['Map'];
							$Plage=$data['Plage'];
							$Fleuve=$data['Fleuve'];
							$Detroit=$data['Detroit'];
							$Occupant=$data['Occupant'];
							$Mines=$data['Mines'];
							$Mines_m=$data['Mines_m'];
							$Fortification=$data['Fortification'];
							$Recce=$data['Recce'];
							$Flag=$data['Flag'];
							$Flag_Air=$data['Flag_Air'];
							$Flag_Route=$data['Flag_Route'];
							$Flag_Gare=$data['Flag_Gare'];
							$Flag_Port=$data['Flag_Port'];
							$Flag_Pont=$data['Flag_Pont'];
							$Flag_Usine=$data['Flag_Usine'];
							$Flag_Radar=$data['Flag_Radar'];
							$Flag_Plage=$data['Flag_Plage'];
							$Garnison=$data['Garnison'];
							//Infos Terrain, Météo
							$Meteo=GetMeteo($_SESSION['Saison'],$data['Latitude'],$data['Longitude'],$data['Meteo']);
							$Meteo_malus=$Meteo[1];
							$Meteo_txt=$Meteo[0];
							$Zone_txt=GetZone($Zone);
							$Region='zone '.$Zone_txt;
						}
						mysqli_free_result($resultl);
						unset($data);
					}
					$Faction_flag=GetData("Pays","ID",$Flag,"Faction");
					if($g_mobile ==5 and $Zone ==6)
					{
						if($Front ==3)
							$Limit_Stack=32;
						else
							$Limit_Stack=24;
					}
					else
						$Limit_Stack=20;
					//GetData Regiments
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Move_ok=false;
							$Conso_m=0;
							$Reg_ID=$data['ID'];
							$Move=$data['Move'];
							$Position=$data['Position'];
							$Placement=$data['Placement'];
							$Vehicule_ID=$data['Vehicule_ID'];
							if($data['Distance'])
								$Distance=$data['Distance'];
							else
								$Distance=$g_Range;
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
								SetData("Regiment_PVP","HP",0,"ID",$data['ID']);
								if($data['Experience'] >0 and $Trait_e !=11)
								{
									SetData("Regiment_PVP","Experience",0,"ID",$data['ID']);
									$data['Experience']=0;
								}
							}
							$Bat_Veh_Nbr+=$data['Vehicule_Nbr'];
							$Regs.=GetCible_Infos($data['Vehicule_ID'],$data['Vehicule_Nbr'],$data['ID'],$data['HP'],$data['Experience'],$data['Muns'],$data['Fret'],$data['Fret_Qty'],$Zone,$Position,$Trait_e,$Avancement,$Front,$data['Visible'],true);
							if($data['HP'] ==0 and $g_mobile ==5)
								SetData("Regiment_PVP","Vehicule_Nbr",0,"ID",$data['ID']);				
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
							if($data['Moves'] <$g_Moves)$Move_ok=true;
							/*nécessaire pour option réparer dans menu principale
							if($g_Type ==98 or $g_Type ==92 or $g_Categorie ==16 or $g_Categorie ==19)
								$Genies=true;
							else
								$Genies=false;
							if($g_mobile ==5)
							{
								$PA_Esc="";
								$Tr_Cie="";
								$con=dbconnecti();
								$result10=mysqli_query($con,"SELECT ID,Pays,Nom,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE Porte_avions='$Vehicule_ID' AND Etat=1");
								$resulttr=mysqli_query($con,"SELECT ID,Vehicule_ID FROM Regiment_PVP WHERE Officier_ID='$Transit'");
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
							}*/
							if($data['Vehicule_Nbr'] >0 and $Position !=11 and $Position !=6)
							{
								if($Faction >0)
								{	
									if($Atk ==false and !$Canada and !$G_Treve and $start)
									{
										$Regs.="<br><form action='index.php?view=ground_menu_pvp' method='post'><input type='Submit' value='Observer' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>";
										$Demandes_txt="
										<form action='index.php?view=ground_appui_pvp' method='post'><input type='hidden' name='Mode' value='1'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Attaquer' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>
										<form action='index.php?view=ground_appui_pvp' method='post'><input type='hidden' name='Mode' value='2'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Bombarder' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>
										<form action='index.php?view=ground_appui_pvp' method='post'><input type='hidden' name='Mode' value='3'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='DCA' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>
										<form action='index.php?view=ground_appui_pvp' method='post'><input type='hidden' name='Mode' value='4'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Protéger' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>
										<form action='index.php?view=ground_appui_pvp' method='post'><input type='hidden' name='Mode' value='5'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Reco' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>";
									}
									/*if($data['Vehicule_Nbr'] >0 and $Atk ==false)
										$Regs.="<form action='index.php?view=ground_consignes_pvp' method='post'>
										<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
										<input type='Submit' value='Consignes' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";*/
									if($g_mobile !=4 and $g_mobile !=5) //terrestre
									{
										if($g_Categorie ==15 and $Atk ==false and !$Canada and !$G_Treve and $start)
											$Action_txt.="<form action='index.php?view=ground_dca_pvp' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'>
											<img src='/images/ammo2_icon.png' title='Cette action coûte 1 tir'><input type='Submit' value='DCA' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($data['Camouflage'] <2 and $Move and $Atk ==false and !$g_Charge and $Move_ok and $g_Detection >10 and !$Canada and !$G_Treve and $start)
											$Action_txt.="<form action='index.php?view=ground_reco_pvp' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'>
											<img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Reco' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										/*if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_4 and $data['Camouflage'] <2 and $Move and $Atk ==false and !$g_Charge and $g_Fuel >0 and $g_Categorie ==5 and $Cible !=$Retraite and !$Canada and !$G_Treve and $start)
											$Action_txt.="<form action='index.php?view=ground_assaut_pvp' method='post'>
											<input type='hidden' name='CT' value='".$CT_4."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
											<img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
											<input type='Submit' value='Assaut' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";*/
										if($data['Camouflage'] <2 and $Move and $Atk ==false and !$g_Charge and $Move_ok and $g_Vitesse >1 and !$Canada and !$G_Treve
										 and $g_Type !=95 and $g_Type !=1 and $g_Type !=6 and $g_Type !=8 and $start)
											$Action_txt.="<form action='index.php?view=ground_pldef_pvp' method='post'>
											<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'><input type='hidden' name='Bomb' value='0'>
											<img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><img src='/images/ammo2_icon.png' title='Cette action coûte 1 tir'>
											<input type='Submit' value='Attaquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($data['Vehicule_Nbr'] >0 and $Atk ==false and $g_Type !=95 and !$g_Charge and $g_Range >2500 and $g_Arme_Art !="Aucune" and $g_Stock_Art and $Position !=2 and $Position !=3 and $Position !=10 and !$Canada and !$G_Treve and $start)
											$Action_txt.="<form action='index.php?view=ground_pldef_pvp' method='post'>
											<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'><input type='hidden' name='Bomb' value='1'>
											<img src='/images/ammo2_icon.png' title='Cette action coûte 1 tir'><input type='Submit' value='Bombarder' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										/*if(($data['Vehicule_ID'] ==403 or $data['Vehicule_ID'] ==372) and $data['Vehicule_Nbr'] >0 and $Credits >=$CT_4 and $Atk ==false and $Position !=2 and $Position !=3 and $Position !=10 and $start)
											$Action_txt.="<form action='index.php?view=ground_smoke_pvp' method='post'>
											<input type='hidden' name='CT' value='".$CT_4."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'>
											<img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Fumée' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";*/
										if($Cible_base >0 and $Placement !=1 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='1'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Aérodrome' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($NoeudR and $Placement !=2 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='2'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Route' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($NoeudF_Ori and $Placement !=3 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='3'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Gare' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Port_Ori and $Placement !=4 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='4'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Port' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if(($Pont_Ori or $Fleuve) and $Placement !=5 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='5'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Fleuve' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Usine and $Placement !=6 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='6'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Usine' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Radar_Ori and $Placement !=7 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='7'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Radar' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Plage and $Placement !=11 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='11'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Plage' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Placement !=0 and $Move)
											$Move_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Zone' value='10'><input type='hidden' name='Reg' value='".$data['ID']."'><img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Caserne' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Position !=11)
											$Pos_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Pos' value='99'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Mouvement' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Position !=2 and $Meteo[1] !=-50 and $Meteo[1] !=-135 and $Position !=11)
											$Pos_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Pos' value='2'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Retranché' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Position !=1 and $Position !=11)
											$Pos_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Pos' value='1'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Défensive' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Position !=3 and $Zone !=8 and $Zone !=0 and $Zone !=6 and $Position !=11)
											$Pos_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Pos' value='3'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Embuscade' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										if($Position !=10 and $Position !=11)
											$Pos_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Pos' value='10'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='En ligne' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";										
										//Move Range
										if($Move_ok)
										{
											$Min_Range=$Distance-500;
											if($Min_Range >500)
											{
												$Pos_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Range' value='".$Min_Range."'><input type='hidden' name='Reg' value='".$data['ID']."'>
												<img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='Se rapprocher' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
											}
											$Max_Range=$Distance+500;
											if($Max_Range <$g_Range)
											{
												$Pos_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Range' value='".$Max_Range."'><input type='hidden' name='Reg' value='".$data['ID']."'>
												<img src='/images/oil_icon.png' title='Cette action coûte 1 déplacement'><input type='Submit' value='S éloigner' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";										
											}
										}
										/*if($Position !=5 and $Position !=11)					
											$Pos_txt.="<form action='index.php?view=ground_move_pvp' method='post'><input type='hidden' name='Pos' value='5'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='Submit' value='Appui' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";*/			
									}
									/*elseif($g_mobile ==5)
									{
										if($Zone ==6 or $Placement ==8 or $Position ==25)
										{
											if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_4 and $Move and $Atk ==false and !$g_Charge and $g_Fuel >=$Conso and ($g_Vitesse >25 or $g_Detection >10) and ($data['Camouflage']<2 or $Position ==25) and !$Canada and !$G_Treve and $start)
												$Regs.="<form action='index.php?view=ground_reco_pvp' method='post'>
												<input type='hidden' name='CT' value='".$CT_4."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'>
												<img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
												<input type='Submit' value='Reco' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										}
										if($Zone ==6 or $Placement ==8)
										{
											if($data['Vehicule_Nbr'] >0 and $Credits >=12 and $data['Camouflage'] <2 and $g_Vitesse >1 and $Move and $g_Type >14 and $g_Type !=21 and !$g_Charge and $Atk ==false and $g_Fuel >=$Conso and !$Canada and !$G_Treve and $start)
												$Regs.="<form action='index.php?view=ground_pldef_pvp' method='post'>
												<input type='hidden' name='CT' value='12'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='0'>
												<img src='/images/CT12.png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
												<input type='Submit' value='Attaquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
											if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_Tir and $Atk ==false and $g_Type !=21 and !$g_Charge and $g_Range >2500 and $g_Arme_Art !="Aucune" and $Position !=25 and !$Canada and !$G_Treve and $start)
												$Regs.="<form action='index.php?view=ground_pldef_pvp' method='post'>
												<input type='hidden' name='CT' value='".$CT_Tir."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='1'>
												<img src='/images/CT".$CT_Tir.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Tirer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
											if($data['Vehicule_Nbr'] >0 and $Credits >=4 and $data['Camouflage'] <2 and $g_Vitesse >1 and $Move and $g_Type <18 and $g_Arme_Inf !="Aucune" and !$g_Charge and $Atk ==false and $g_Fuel >=$Conso and !$Canada and !$G_Treve and $start)
												$Regs.="<form action='index.php?view=ground_asm_pvp' method='post'>
												<input type='hidden' name='CT' value='4'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
												<img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
												<input type='Submit' value='ASM' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
											if($Placement ==8)
											{
												if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_8_final and $data['Camouflage'] !=2 and $Cible !=$Retraite and $Atk ==false and $Move and $g_Type !=21 and !$g_Charge and $g_Fuel >=$Conso and $Recce >0 and $Flag !=$country and $g_Range >2500 and $g_Arme_Art !="Aucune" and $Position !=25 and !$Canada and !$G_Treve and $start)
													$Regs.="<form action='index.php?view=ground_orders_pvp' method='post'>
													<input type='hidden' name='CT' value='".$CT_8_final."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'>
													<img src='/images/CT".$CT_8_final.".png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
													<input type='Submit' value='Bombarder' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
											}
										}
										if($data['Vehicule_Nbr'] >0 and $Credits >=12 and $g_Vitesse >1 and $Move and $g_Type >16 and $g_Arme_AT !="Aucune" and !$g_Charge and $Atk ==false and $g_Fuel >=$Conso and !$Canada and !$G_Treve and $start)
										{
											if($Position ==25)
												$Regs.="<form action='index.php?view=ground_pldef_pvp' method='post'>
												<input type='hidden' name='CT' value='16'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='2'>
												<img src='/images/CT16.png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
												<input type='Submit' value='Torpiller' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
											elseif(($Zone ==6 or $Placement ==8) and $data['Camouflage'] <2)
												$Regs.="<form action='index.php?view=ground_pldef_pvp' method='post'>
												<input type='hidden' name='CT' value='12'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'><input type='hidden' name='Conso' value='".$Conso."'><input type='hidden' name='Bomb' value='2'>
												<img src='/images/CT12.png' title='Montant en Crédits Temps que nécessite cette action'> + <img src='/images/oil_icon.png' title='".$Conso_txt."'>
												<input type='Submit' value='Torpiller' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										}
										if($Port >0 or $Plage >0 or $Detroit >0)
										{
											if($Mines_m >0)
											{
												if($data['Vehicule_Nbr'] >0 and $Credits >=40 and $Genies and $Atk ==false and $Move and $Placement ==8 and $Position !=25)
													$Regs.="<form action='index.php?view=ground_deminer_pvp' method='post'>
													<input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
													<img src='/images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Déminer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
											}
										}
										if($data['Fret'] ==200)
										{
											$CT_2*=2;
											if($Enis)$CT_2*=2;
										}
										if($g_Categorie ==18) //transport de troupes
										{
											if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_2 and $Atk ==false and !$data['Fret'] and $Zone !=6 and (($Port >0 and $Placement ==4) or ($Plage >0 and $Placement ==8)))
												$Regs.="<form action='index.php?view=ground_embark_pvp' method='post'>
												<input type='hidden' name='Unite' value='".$data['ID']."'><input type='hidden' name='Base' value='".$data['Lieu_ID']."'><input type='hidden' name='CT' value='".$CT_2."'>
												<img src='/images/CT".$CT_2.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Embarquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
											if($data['Vehicule_Nbr'] >0 and $Credits >=$CT_2 and $Atk ==false and $g_Charge and $data['Fret'] ==200 and !$Canada and !$G_Treve and $Position !=3 and $Position !=5 and $Position !=25 and $Zone !=6 and (($Port >0 and $Placement ==4) or ($Plage >0 and $Placement ==8)))
												$Regs.="<form action='index.php?view=ground_decharge_pvp' method='post'>
												<input type='hidden' name='CT' value='".$CT_2."'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Base' value='".$data['Lieu_ID']."'><input type='hidden' name='Place' value='".$Placement."'>
												<img src='/images/CT".$CT_2.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Débarquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
										}
										if($data['Vehicule_Nbr'] >0 and $Credits >=1 and $data['Fret'] !=1 and $Atk ==false)
											$Regs.="<form action='index.php?view=ground_saborder_pvp' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'><input type='hidden' name='Veh' value='".$data['Vehicule_ID']."'>
											<img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Saborder' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
									}*/
									if($g_mobile !=5)
									{
										if($data['Vehicule_Nbr'] >0 and $g_Type ==95 and $Credits >=2 and $Cible !=$Retraite and $Atk ==false)
											$Regs.="<form action='index.php?view=ground_rally_pvp' method='post'>
											<input type='hidden' name='Cible' value='".$data['Lieu_ID']."'>
											<img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Rallier' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
									}
								}
							}
							else
							{
								$Deroute=true;
							}
							$Regs.="</td></tr>";
						}
						mysqli_free_result($result);
						unset($data);
					}
					include_once('./pvp_city_ground.php');
				//}
				if($Fin_Battle)
				{
					if(!$Allies_up)
					{
						$Infos="L'Axe remporte cette bataille!";
						$img=Afficher_Image("images/battle/battle_victory1.jpg","images/image.png","Victoire de l'Axe!",50);
					}
					elseif(!$Axe_up)
					{
						$Infos="Les Alliés remportent cette bataille!";
						$img=Afficher_Image("images/battle/battle_victory2.jpg","images/image.png","Victoire des Alliés!",50);
					}
					else
						$Infos="Cette bataille se termine sur un match nul!";
					echo "<h1>Fin de la bataille</h1>".$Infos.$img;
				}
				elseif($Deroute)
					echo "<p class='lead'>Vos troupes en déroute ne peuvent plus participer à cette bataille</p>";
				else
				{
					$Speed_min=$g_Vitesse;
					?>
					<div id="esc_infos">
					<?if($g_mobile ==5){
						$Bat=GetEsc($country);
					?>
						<h2><?echo $Bat;?></h2>
						<p class="lead"><?echo $Bat.' '.GetPosGr($Position).' '.GetPlace($Placement,1).' à une distance de '.$Distance.'m'.$Alerte;?></p>
						<?echo $PA_Esc_final;?>
						<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
							<thead><tr>         
								<th>Flottille</th>                         
								<th>Navires</th>    
								<th title="Grenades sous-marines ou DCA basse altitude">ASM / DCA <span title='Grenades sous-marines ou DCA basse altitude'><img src='images/help.png'></th>                            
								<th title="Utilisé lors des bombardements / DCA haute altitude pour les porte-avions">Principal <span title='Utilisé lors des bombardements / DCA haute altitude pour les porte-avions'><img src='images/help.png</span></a></th>                            
								<th title="Lance-torpilles ou Mines">Torpilles / Mines <span title='Lance-torpilles ou Mines'><img src='images/help.png'></th>                            
								<th title="Uniquement défensif. Utilisé contre les attaques aériennes">DCA <span title='Uniquement défensif. Utilisé contre les attaques aériennes'><img src='images/help.png'></th>
								<th>Carburant</th>
								<th title="Déplacements">Autonomie <span title='Nombre de déplacements disponibles dans cette bataille'><img src='images/help.png'></th>
								<th>Consignes</th>
							</tr></thead>
					<?}else{?>
						<h2>Bataillon</h2>
						<p class="lead"><?echo GetPosGr($Position).' '.GetPlace($Placement).' à une distance de '.$Distance.'m'.$Alerte;?></p>
						<div style='overflow:auto; width: 100%;'><table class='table table-striped'>
							<thead><tr>                                   
								<th>Unité</th>                         
								<th>Véhicules / Troupes</th>                         
								<th title="Utilisé contre les cibles non blindées">Armement <span title='Utilisé contre les cibles non blindées'><img src='images/help.png</span></a></th>                            
								<th title="Utilisé lors des bombardements et des démolitions">Soutien <span title='Utilisé lors des bombardements et des démolitions'><img src='images/help.png</span></a></th>                            
								<th title="Utilisé contre les cibles blindées">Anti-tank <span title='Utilisé contre les cibles blindées'><img src='images/help.png</span></a></th>                            
								<th title="Uniquement défensif. Utilisé contre les attaques aériennes">DCA <span title='Uniquement défensif. Utilisé contre les attaques aériennes'><img src='images/help.png</span></a></th>
								<th title='Maximum 25000 litres'>Carburant</th>
								<th title="Déplacements">Autonomie <span title='Nombre de déplacements disponibles dans cette bataille'><img src='images/help.png</span></a></th>
								<th>Consignes</th>
							</tr></thead>
						<?}echo $Regs;?>
					</table></div></div><?
				}
				echo "<p class='text-primary'>".$Alertes."</p><div class='row'><div class='col-md-3'><h2>Déplacement</h2>".$Move_txt."</div><div class='col-md-3'><h2>Position</h2>".$Pos_txt."</div><div class='col-md-3'><h2>Actions</h2>".$Action_txt."</div><div class='col-md-3'><h2>Demandes</h2>".$Demandes_txt."</div></div>
				<h2>Messagerie instantanée</h2><div class='row'><div class='col-md-2'><form action='index.php?view=battle_chat' method='post'>
				<input type='hidden' name='Battle' value='".$Battle."'>
				<input type='hidden' name='Camp' value='".$Faction."'>
				<input type='text' name='Mes' size='50' class='form-control'></div>
				<div class='col-md-2'><input type='Submit' value='Chat' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
				<div class='col-md-2'><form action='index.php?view=ground_menu_pvp' method='post'><input type='Submit' value='Refresh' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>
				<div style='overflow:auto; width:100%; height:200px;'><div class='row'><div class='col-md-2'>".$Connectesb."</div><div class='col-md-10'><p>".$Chat_faction_b."</p></div></div></div><h2>Evènements</h2>".$Events_txt;
			}
			else
			{
				echo "<h1>Préparation de la mission</h1>La bataille n'a pas encore commencé. Elle débutera le <b>".$Date_txt."</b>".$alerte_txt;
				echo "<h2>Messagerie instantanée</h2><div class='row'><div class='col-md-2'><form action='index.php?view=battle_chat' method='post'>
				<input type='hidden' name='Battle' value='0'>
				<input type='hidden' name='Camp' value='0'>
				<input type='text' name='Mes' size='50' class='form-control'></div>
				<div class='col-md-2'><input type='Submit' value='Chat' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
				<div class='col-md-2'><form action='index.php?view=ground_menu_pvp' method='post'><input type='Submit' value='Refresh' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>
				<div style='overflow:auto; width:100%; height:200px;'><div class='row'><div class='col-md-2'>".$Connectesb."</div><div class='col-md-10'><p>".$Chat_open_b."</p></div></div></div>";
			}
		}
		else
		{
			echo "<h1>Préparation de la mission</h1>La bataille n'a pas encore commencé. Elle débutera le <b>".$Date_txt."</b>".$alerte_txt;
			echo "<h2>Messagerie instantanée</h2><div class='row'><div class='col-md-2'><form action='index.php?view=battle_chat' method='post'>
			<input type='hidden' name='Battle' value='0'>
			<input type='hidden' name='Camp' value='0'>
			<input type='text' name='Mes' size='50' class='form-control'></div>
			<div class='col-md-2'><input type='Submit' value='Chat' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
			<div class='col-md-2'><form action='index.php?view=ground_menu_pvp' method='post'><input type='Submit' value='Refresh' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>
			<div style='overflow:auto; width:100%; height:200px;'><div class='row'><div class='col-md-2'>".$Connectesb."</div><div class='col-md-10'><p>".$Chat_open_b."</p></div></div></div>";
		}
	}
	else
	{
		echo "<h1>Préparation de la mission</h1>Pour participer à une bataille, vous devez vous inscrire <a href='index.php?view=battles' class='lien'>ici</a>".$alerte_txt;
		echo "<h2>Messagerie instantanée</h2><div class='row'><div class='col-md-4'><form action='index.php?view=battle_chat' method='post'>
		<input type='hidden' name='Battle' value='0'>
		<input type='hidden' name='Camp' value='0'>
		<input type='text' name='Mes' size='50' class='form-control'></div>
		<div class='col-md-2'><input type='Submit' value='Chat' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div>
		<div class='col-md-2'><form action='index.php?view=ground_menu_pvp' method='post'><input type='Submit' value='Refresh' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div>
		<div style='overflow:auto; width:100%; height:200px;'><div class='row'><div class='col-md-2'>".$Connectesb."</div><div class='col-md-10'><p>".$Chat_open_b."</p></div></div></div>";
		/*echo "<h1>Combat terrestre</h1><img src='images/champ_tir.jpg'><h2><b>Choix de la mission</b></h2>
		<form action='index.php?view=ground_def_pvp' method='post'>
		<select name='Camp' class='form-control' style='width: 300px'>
			<option value='2' selected>Alliés</option>
			<option value='1'>Axe</option>
		</select>
		<select name='Battle' class='form-control' style='width: 300px'>
			<option value='1'>05/1940 - Maastricht</option>
		</select>
		<select name='Type' class='form-control' style='width: 300px'>
			<option value='8'>[Artillerie] Bombardement</option>
			<option value='3'>[Blindés] Attaque</option>
			<option value='2'>[Blindés] Reconnaissance</option>
			<option value='5'>[Infanterie] Assaut</option>
			<option value='6' selected>[Infanterie] Reconnaissance</option>
		</select>
		<p><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form>";*/
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';