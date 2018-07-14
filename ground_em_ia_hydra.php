<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		$country=$_SESSION['country'];
		include_once('./jfv_include.inc.php');
		include_once('./jfv_inc_em.php');
		include_once('./jfv_txt.inc.php');
		$Base=Insec($_POST['Lieu']);
		$Reg=Insec($_POST['Reg']);
		$Avion1=Insec($_POST['Hydra']);
		if($Credits >0 and $Reg >0 and $Base >0 and $Avion1 >0)
		{
			if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Mer or $GHQ or $Armee or $Admin)
			{
				if($country ==5 or $country ==15 or $country ==18 or $country ==19)$Nation_IA=true;
				if($Premium)$Legend=true;
				include_once('./jfv_avions.inc.php');
				$CT_Discount=Get_CT_Discount($Avancement);
				if($GHQ)$CT_Discount+=4;
				$con=dbconnecti();
				$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
				$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
				$result=mysqli_query($con,"SELECT Nom,Pays,Longitude,Latitude,Zone,Flag,Meteo FROM Lieu WHERE ID='$Base'");
				$resultr=mysqli_query($con,"SELECT Autonomie FROM Regiment_IA WHERE ID='$Reg'");
				$result1=mysqli_query($con,"SELECT Nom,Type,Puissance,Engine,Engine_Nbr,Masse,Autonomie,Plafond,Bombe,Bombe_Nbr,Train,Usine1,Usine2,Usine3,Lease,Rating FROM Avion WHERE ID='$Avion1'");
				mysqli_close($con);
				if($result1)
				{
					while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
					{
						$Avion1_nom=$data1['Nom'];
						$Avion1_rating=$data1['Rating'];
						$Avion1_a=floor((($data1['Autonomie']/2)-200)+$Nav_Moy);
						if($Avion1_a <50)$Avion1_a=50;
						$Avion1_mot=$data1['Engine'];
						$Avion1_en=$data1['Engine_Nbr'];
						$Avion1_p=$data1['Plafond'];
						$Avion1_bombs=$data1['Bombe'];
						$Avion1_bombs_nbr=$data1['Bombe_Nbr'];
						$Train1=$data1['Train'];
						$Array_Mod=GetAmeliorations($Avion1);
						if($Array_Mod[13] >0 or $Array_Mod[14] >0 or $Array_Mod[15] >0)$Avion1_btac=true;
						if($Array_Mod[16] >0 or $Array_Mod[17] >0)$Avion1_rec=true;
						$Avion1_a_l=floor($Avion1_a+($Array_Mod[18]/2));
						$Avion1_p_l=$Avion1_p-$Array_Mod[18];
						$Massef1_s=$data1['Masse']+($data1['Bombe']*$data1['Bombe_Nbr']);
						$Massef1_t=$data1['Masse']+$data1['Bombe'];
						$Poids_Puiss_ori1=$data1['Masse']/$data1['Puissance'];
						$Poids_Puiss1_s=$Massef1_s/$data1['Puissance'];
						$Poids_Puiss1_t=$Massef1_t/$data1['Puissance'];
						if($data1['Type'] ==2 or $data1['Type'] ==11)
							$Avion1_a_s=round($data1['Autonomie']-(($Poids_Puiss1_s-$Poids_Puiss_ori1)*($Massef1_s/10)));
						else
							$Avion1_a_s=round(($data1['Autonomie']/2)-(($Poids_Puiss1_s-$Poids_Puiss_ori1)*($Massef1_s/10)));
						$Avion1_a_t=round(($data1['Autonomie']/2)-(($Poids_Puiss1_t-$Poids_Puiss_ori1)*($Massef1_t/10)));
						$Avion1_u1=$data1['Usine1'];
						$Avion1_u2=$data1['Usine2'];
						$Avion1_u3=$data1['Usine3'];
						$Avion1_Lease=$data1['Lease'];
					}
					mysqli_free_result($result1);
					unset($data1);
				}
				if($resultr)
				{
					while($data=mysqli_fetch_array($resultr,MYSQLI_ASSOC))
					{
						$Jours=$data['Autonomie'];
						$Mission_IA=$data['Move'];
					}
					mysqli_free_result($resultr);
				}
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Base_Nom=$data['Nom'];
						$Base_Pays=$data['Pays'];
						$Longitude_base=$data['Longitude'];
						$Latitude_base=$data['Latitude'];
						$Zone=$data['Zone'];
						$Meteo=$data['Meteo'];
						$Flag=$data['Flag'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				$Front=GetFrontByCoord(0,$Latitude_base,$Longitude_base);
				//Output
				echo "<h1>Aviation embarquée</h1><h2><small><img src='images/zone6.jpg' title='Base actuelle'> ".$Base_Nom." <img src='images/".$Base_Pays."20.gif' title='".GetPays($Base_Pays)."'></small></h2>";
				echo GetAvionIcon($Avion1,$country,0,0,$Front,false,$Legend);
				if(!$GHQ or $Admin)
				{
					//Missions
					$choix17="";
					$choix15="";
					$choix7="";
					$choix29="<option value='29'>ASM</option>";
					$choix5="<option value='5'>Reconnaissance tactique</option>";
					$choix32="<option value='32'>Veille</option>";
					$Mis_list="5,29";
					$Hydravion=true;
					if($Credits <1)
						echo "<h6>Vous ne disposez pas de suffisamment de Crédits Temps pour assigner une mission à votre unité !<h6>";
					elseif($Meteo >-50 and !$Mission_IA)
					{
							$Plafonds=array($Avion1_p,$Avion2_p,$Avion3_p);
							$Autonomies=array($Avion1_a,$Avion2_a,$Avion3_a);
							$Autonomies_strat=array($Avion1_a_s,$Avion2_a_s,$Avion3_a_s);
							$Autonomies_tac=array($Avion1_a_t,$Avion2_a_t,$Avion3_a_t);
							$Autonomies_long=array($Avion1_a_l,$Avion2_a_l,$Avion3_a_l);
							$Plafonds_long=array($Avion1_p_l,$Avion2_p_l,$Avion3_p_l);
							$Plafond_max=min(array_filter($Plafonds));
							$Autonomie_max=min(array_filter($Autonomies));
							$Plafond_long_max=min(array_filter($Plafonds_long));
							$Autonomie_strat_max=min(array_filter($Autonomies_strat));
							$Autonomie_tac_max=min(array_filter($Autonomies_tac));
							$Autonomie_long_max=min(array_filter($Autonomies_long));
							if($Autonomie_strat_max <50)$Autonomie_strat_max=50;
							if($Autonomie_tac_max <50)$Autonomie_tac_max=50;
							echo "<br><span class='label label-primary'>".$Jours." Jours de ravitaillement</span> <a href='help/aide_jours.php' target='_blank' title='Cliquez pour aide'><img src='images/help.png'></a> 
							<div class='alert alert-info'>Une mission consomme 1 jour de ravitaillement du navire</div>";
							$Flight_txt="<option value='1' selected>1</option>";
							$Coord=GetCoord($Front,$country);
							$Lat_base_min=$Coord[0];
							$Lat_base_max=$Coord[1];
							$Long_base_min=$Coord[2];
							$Long_base_max=$Coord[3];
							if($Flight_txt and !$Canada)
							{
								if($Credits >=4)
								{
									$Lieuxasm="";
									$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max' AND (Zone=6 OR Port_Ori >0 OR Plage >0) ORDER BY Nom ASC";
									$con=dbconnecti();
									$result=mysqli_query($con,$query) or die(mysqli_error($con));
									mysqli_close($con);
									if($result)
									{
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
										{
											$Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data['Longitude'],$data['Latitude']);
											if($data['ID']==$Base)$Dist[0]=10;
											if($Dist[0] <=$Autonomie_max)
												$Lieuxasm.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
											/*if($Dist[0] <=$Autonomie_long_max)
												$Lieuxlong.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";*/
										}
										mysqli_free_result($result);
										unset($data);
									}
									else
										$mes.="Erreur d'import de données.";
									if($Lieuxasm)
									{
										$Tac_Output_txt.="<h3>ASM</h3><span class='label label-warning'>Autonomie ".$Autonomie_max."km</span>
											<form action='ground_em_ia_hydra1.php' method='post'>
											<input type='hidden' name='Avion' value='".$Avion1."'>
											<input type='hidden' name='Base' value='".$Base."'>
											<input type='hidden' name='Reg' value='".$Reg."'>
											<input type='hidden' name='Type' value='29'>
											<input type='hidden' name='Altitude' value='500'>
											<table class='table'>
												<tr><th>Cible</th><th></th></tr><tr><td><select name='Cible' class='form-control'>".$Lieuxasm."</select></td>
												<td><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='ASM' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
											</table></form>";
										$Tac_Output_txt.="<h3>Reconnaissance</h3><span class='label label-warning'>Autonomie ".$Autonomie_max."km</span>
											<form action='ground_em_ia_hydra1.php' method='post'>
											<input type='hidden' name='Avion' value='".$Avion1."'>
											<input type='hidden' name='Base' value='".$Base."'>
											<input type='hidden' name='Reg' value='".$Reg."'>
											<input type='hidden' name='Type' value='5'>
											<table class='table'>
												<tr><th>Cible</th><th>Altitude</th><th></th></tr><tr><td><select name='Cible' class='form-control'>".$Lieuxasm."</select></td>
												<td><select name='Altitude' class='form-control'>
													<option value='100'>Basse altitude (100m)</option>
													<option value='500'>Basse altitude (500m)</option>
													<option value='1000'>Basse altitude (1000m)</option>
													<option value='2000'>Altitude moyenne (2000m)</option>
												<td><img src='/images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Reco' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
										</table></form>";
									}
									else
										$Tac_Output_txt.="<h3>ASM</h3>L'unité n'a pas l'autonomie pour atteindre la zone maritime la plus proche!";
									//Output Missions
									if($Tac_Output_txt)
										echo "<h2>Missions Tactiques</h2>".$Tac_Output_txt;
									else
										echo "<div class='alert alert-danger'>Vos avions, dotés d'une autonomie maximale de ".$Autonomie_max."km n'ont pas de cibles à leur portée dans cette région</div>";
								}
								else
									echo "<div class='alert alert-danger'>Vous manquez de temps pour donner vos ordres!</div>";
							}
							else
								echo "<div class='alert alert-danger'>Si les stocks de carburant sont vides, vous devez ravitailler les dépôts proches en utilisant les trains et ou les cargos EM<br>Sur un lieu côtier, vous pouvez également placer un cargo EM au large qui jouera le rôle de dépôt flottant.</div>";
							//Demandes en cours
							$txt="";
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT * FROM ((SELECT DISTINCT 1 as tri,l.Nom,l.Zone,u.Mission_Type_D,p.Pays_ID,u.Nom as Unite,l.Recce,l.ID FROM Unit as u,Lieu as l,Pays as p
							WHERE (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND u.Pays=p.Pays_ID AND u.Mission_Lieu_D >0 AND u.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' AND u.Mission_Lieu_D=l.ID)
							UNION ALL (SELECT DISTINCT 2 as tri,l.Nom,l.Zone,r.Mission_Type_D,r.Pays,r.ID as Unite,l.Recce,l.ID FROM Lieu as l,Regiment_IA as r,Pays as p 
							WHERE r.Pays=p.Pays_ID AND r.Front='$Front' AND r.Mission_Lieu_D=l.ID AND r.Mission_Lieu_D >0 AND r.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max'))) a ORDER BY tri,Nom ASC");
							/*UNION ALL (SELECT DISTINCT 3 as tri,l.Nom,l.Zone,o.Mission_Type_D,p.Pays_ID,o.Nom as Unite,l.Recce,l.ID FROM Officier as o,Lieu as l,Pays as p
							WHERE o.Pays=p.Pays_ID AND o.Front='$Front' AND o.Mission_Lieu_D >0 AND o.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' AND o.Mission_Lieu_D=l.ID)*/
							if($result)
							{
								while($Data=mysqli_fetch_array($result,MYSQLI_NUM)) 
								{
									if($Data[2] ==6)
									{
										//$con=dbconnecti();
										$Nav_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Data[6]' AND Pays<>'$country' AND Vehicule_Nbr >0 AND Visible=1"),0);
										//mysqli_close($con);
										if($Nav_eni >0)
											$Recce='<b>Oui</b>';
										else
											$Recce='Non';
									}
									else
									{
										if($Data[6] ==2)
											$Recce='<b>Eclairé</b>';
										elseif($Data[6] ==1)
											$Recce='<b>Oui</b>';
										else
											$Recce='Non';
									}
									if(is_numeric($Data[5]))$Data[5].="e Cie";
									$txt.="<tr><td>".$Data[1]."</td><td><img src='images/zone".$Data[2].".jpg'></td><td>".GetMissionType($Data[3])."</td><td><img src='".$Data[4]."20.gif' title='".$Data[5]."'> ".$Data[5]."</td><td>".$Recce."</td></tr>";
								}
								mysqli_free_result($result);
							}
							mysqli_close($con);
							if(!$txt)$txt="<tr><td colspan='5'>Aucune demande actuellement</td></tr>";
							if($Admin)$Front_txt="(".GetFront($Front).", Latmin=".$Lat_base_min.", Latmax=".$Lat_base_max.", Longmin=".$Long_base_min.", Longmax=".$Long_base_max.")";
							echo "<hr><h1>Le Front</h1>".$Front_txt."<h2>Demandes de mission en cours</h2><table class='table table-striped'>
								<thead><tr>
								<th>Lieu</th>
								<th>Zone</th>
								<th>Mission demandée</th>
								<th>Unité demandeuse</th>
								<th>Status Reco</th></tr></thead>";
							echo $txt.'</table>';
					}
					elseif($Meteo <-49)
						echo "<div class='alert alert-danger'><img src='images/meteo".$Meteo.".gif'> La météo exécrable empêche tout décollage!</div>";
				}//GHQ
			}
			else
				echo "<img src='images/top_secret.gif'>";
		}
		else
			echo "<div class='alert alert-danger'>Vous manquez de temps pour donner vos ordres...</div>";
	}
	else
		echo "<img src='images/top_secret.gif'>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>