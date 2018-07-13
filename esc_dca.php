<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0 and $PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Avancement,Credits FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Avancement=$data['Avancement'];
				$Credits=$data['Credits'];
			}
		}
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Commandant,Officier_Adjoint,Officier_Technique,Porte_avions FROM Unit WHERE ID='$Unite'");
		$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Unit='$Unite'"),0);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Officier_Technique=$data['Officier_Technique'];
				$Porte_avions=$data['Porte_avions'];
			}
		}		
		if($Porte_avions >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT a.ID,a.Nom,a.Calibre,a.Degats,a.Multi,a.Portee 
			FROM Armes as a,Cible as r WHERE r.ID='$Porte_avions' AND (a.ID=r.Arme_AA OR a.ID=r.Arme_AA2 OR a.ID=r.Arme_AA3)");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$dg_max=round($data['Degats']*$data['Multi']);
					$dca_pieces.="<tr><td><img src='images/aa".$data['ID'].".png'><br>".$data['Nom']."</td><td>".round($data['Calibre'])."mm</td>
					<td>".$data['Degats']."-".$dg_max."</td><td>".$data['Portee']."m</td></tr>";
				}
			}
			include_once('./menu_escadrille.php');
			echo "<h2>Porte-avions ".GetData("Cible","ID",$Porte_avions,"Nom")."</h2><p><img src='/images/vehicules/vehicule".$Porte_avions.".gif'></p>";
			echo "<table class='table table-striped'><thead><tr><th>Nom</th><th>Calibre</th><th>Dégats Max</th><th>Plafond</th></tr></thead>";
			echo $dca_pieces."</table>";
		}
		else //if($Pilotes >2)
		{
			$Grade=GetAvancement($Avancement,$country);			
			if($Avancement >24999 or $PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique or $Admin ==1)
			{
				$Date=date('Y-m-d');
				$con=dbconnecti(4);
				$resultm=mysqli_query($con,"SELECT `Date` FROM Events WHERE Event_Type=31 AND PlayerID='$PlayerID' ORDER BY ID DESC LIMIT 1");
				mysqli_close($con);
				if($resultm)
				{
					$data=mysqli_fetch_array($resultm);
					$Date_Mutation=$data[0];
					if($Date_Mutation)
					{
						$con=dbconnecti();
						$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date','$Date_Mutation')"),0);
						mysqli_close($con);
					}
					else
					{
						$con=dbconnecti();
						$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date','2012-09-01')"),0);
						mysqli_close($con);
					}
				}
				if($Datediff >3)
				{
					//GetData Unit
					$con=dbconnecti();
					$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
					$result=mysqli_query($con,"SELECT Nom,Pays,Type,Base,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,Stock_Munitions_40,Stock_Munitions_75,Stock_Munitions_90,Stock_Munitions_105,Stock_Munitions_125
					FROM Unit WHERE ID='$Unite'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Unite_Nom=$data['Nom'];
							$Pays=$data['Pays'];
							$Unite_Type=$data['Type'];
							$Base=$data['Base'];
							$Stock_Munitions_8=$data['Stock_Munitions_8'];
							$Stock_Munitions_13=$data['Stock_Munitions_13'];
							$Stock_Munitions_20=$data['Stock_Munitions_20'];
							$Stock_Munitions_30=$data['Stock_Munitions_30'];
							$Stock_Munitions_40=$data['Stock_Munitions_40'];
							$Stock_Munitions_75=$data['Stock_Munitions_75'];
							$Stock_Munitions_90=$data['Stock_Munitions_90'];
							$Stock_Munitions_105=$data['Stock_Munitions_105'];
							$Stock_Munitions_125=$data['Stock_Munitions_125'];
						}
						mysqli_free_result($result);
					}				
					if($Credits >0)
					{
						$dca_i=0;
						//DCA liste
						if($Unite_Type ==10 or $Unite_Type ==12)
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT Armes.ID,Armes.Nom,Armes.Calibre,Armes.Degats,Armes.Multi,Armes.Portee,Armes.Crew,Armes.Flak,Armes.Transport 
							FROM Armes WHERE Armes.Pays IN(0,'$Pays') AND Armes.Flak>0 AND Armes.Flak<8 AND Armes.Date <='$Date_Campagne' ORDER BY Armes.Flak ASC, Armes.Nom ASC");
							mysqli_close($con);
						}
						else
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT Armes.ID,Armes.Nom,Armes.Calibre,Armes.Degats,Armes.Multi,Armes.Portee,Armes.Crew,Armes.Flak,Armes.Transport 
							FROM Armes WHERE Armes.Pays IN(0,'$Pays') AND Armes.Flak>0 AND Armes.Date <='$Date_Campagne' ORDER BY Armes.Flak ASC, Armes.Nom ASC");
							mysqli_close($con);
						}
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$dca_i++;
								$choix_alt="";
								$Transport="Non";
								$Portee_min=0;
								$flak_id=$data['ID'];
								$flak_ct=$data['Flak']*2;
								$menu_nbr="<option value='0'>0</option>
										<option value='1'>1</option>
										<option value='2'>2</option>
										<option value='3'>3</option>
										<option value='4'>4</option>
										<option value='5'>5</option>
										<option value='6'>6</option>
										<option value='7'>7</option>
										<option value='8'>8</option>
										<option value='9'>9</option>
										<option value='10'>10</option>";
								$menu_skill="<option value='0'>Novice (gratuit)</option>
										<option value='1'>Apte (2 CT - gratuit si 1 Artilleur)</option>
										<option value='2'>Compétent (4 CT - gratuit si 2 Artilleurs)</option>
										<option value='3'>Entraîné (6 CT - gratuit si 3 Artilleurs)</option>
										<option value='4'>Chevronné (8 CT - gratuit si 4 Artilleurs)</option>
										<option value='5'>Vétéran (10 CT - gratuit si 5 Artilleurs)</option>
										<option value='6'>Expert (12 CT - gratuit si 6 Artilleurs)</option>
										<option value='7'>Elite (14 CT - gratuit si 7 Artilleurs)</option>
										<option value='8'>Virtuose (16 CT - gratuit si 8 Artilleurs)</option>";
								if($data['Transport'])
									$Transport="Oui";
								if($data['Portee'] >3999)
									$Portee_min=1500;
								for($i=$Portee_min;$i<= $data['Portee'];$i+=500)
								{
									$choix_alt.="<option value='".$i."'>".$i."m</option>";
								}
								$con=dbconnecti();
								$dca_res=mysqli_query($con,"SELECT DCA_Nbr,DCA_Exp,Alt FROM Flak WHERE Lieu='$Base' AND Unit='$Unite' AND DCA_ID='$flak_id'");
								mysqli_close($con);
								if($dca_res)
								{
									while($data_flak=mysqli_fetch_array($dca_res,MYSQLI_ASSOC))
									{
										$Flak_Exp=floor($data_flak['DCA_Exp']);
										$menu_nbr="<option value='".$data_flak['DCA_Nbr']."' selected>".$data_flak['DCA_Nbr']."</option>".$menu_nbr;
										$menu_skill="<option value='".$Flak_Exp."' selected>".$Flak_Exp." Level</option>".$menu_skill;
										$choix_alt="<option value='".$data_flak['Alt']."' selected>".$data_flak['Alt']."</option>".$choix_alt;
										$total_pieces+=$data_flak['DCA_Nbr'];
									}
									mysqli_free_result($dca_res);
								}
								$dg_max=round($data['Degats']*$data['Multi']);
								if($data['Calibre'] >0)
								{
									$calibre_txt=round($data['Calibre'])."mm";
									$dg_txt=$data['Degats']."-".$dg_max;
								}
								else
								{
									$calibre_txt="N/A";
									$dg_txt="N/A";
								}
								$dca_pieces.="<tr><td><img src='images/aa".$flak_id.".png'><br>".$data['Nom']."</td><td><img src='/images/CT".$flak_ct.".png'></td>
								<td>".$calibre_txt."</td><td>".$dg_txt."</td><td>".$data['Portee']."m</td><td>".$Transport."</td>
								<td><select name='".$dca_i."_Nbr' class='form-control'>".$menu_nbr."</select></td>
								<td><select name='".$dca_i."_Alt' class='form-control'>".$choix_alt."</select></td>
								<td><select name='".$dca_i."_Skill' class='form-control'>".$menu_skill."</select></td>
								<input type='hidden' name='".$dca_i."_Flak' value='".$flak_id."'></tr>";
							}
							mysqli_free_result($result);
						}				
						//Outre-Mer ou anglais
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT Zone,Camions,Latitude,Longitude,Port_Ori,Port,NoeudF_Ori,NoeudF FROM Lieu WHERE ID='$Base'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Zone=$data['Zone'];
								$Camions=$data['Camions'];
								$Base_Lat=$data['Latitude'];
								$Base_Long=$data['Longitude'];
								$Port_ori_base=$data['Port_Ori'];
								$Gare_ori_base=$data['NoeudF_Ori'];
								$Port_base=$data['Port'];
								$Gare_base=$data['NoeudF'];
							}
							mysqli_free_result($result);
							unset($data);
						}
						if(!$Port_ori_base)
							$Port_base=100;
						if(!$Gare_ori_base)
							$Gare_base=100;
						if($Port_base !=100 and $Port_base >=$Gare_base)
							$Inf_base=$Port_base;
						elseif($Gare_base !=100 and $Gare_base >$Port_base)
							$Inf_base=$Gare_base;
						else
							$Inf_base=100;						
						if($Base_Lat <38.2 or $Base_Long >70 or $Pays ==2 or $Zone ==6)
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT COUNT(*),SUM(Port) FROM Lieu WHERE Flag='$country' AND Port_Ori >0 AND Flag_Port='$country'");
							$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND Flag_Gare='$country'");
							mysqli_close($con);
							if($result)
							{
								if($data=mysqli_fetch_array($result,MYSQLI_NUM))
								{
									if($data[0])
										$Efficacite_ravit_port=round($data[1]/$data[0]);
									else
										$Efficacite_ravit_port=0;
								}
							}
							if($result2)
							{
								if($data=mysqli_fetch_array($result2,MYSQLI_NUM))
								{
									if($data[0])
										$Efficacite_ravit=round($data[1]/$data[0]);
									else
										$Efficacite_ravit=0;
								}
							}
							$Efficacite_ravit=round(($Efficacite_ravit+($Efficacite_ravit_port*2))/3);
						}
						else
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND Flag_Gare='$country'");
							mysqli_close($con);
							if($result)
							{
								if($data=mysqli_fetch_array($result,MYSQLI_NUM))
								{
									if($data[0])
										$Efficacite_ravit=round($data[1]/$data[0]);
									else
										$Efficacite_ravit=0;
								}
							}
						}
						//Malus ravitaillement par saison ou terrain
						$Saison=$_SESSION['Saison'];
						if($Base_Long >20 and $Base_Lat >43)		//Front Est
						{
							if($Saison ==2)	// Printemps (boue dégel)
								$Camions+=20;
							elseif($Saison ==1) // Automne
								$Camions+=5;
							elseif($Saison ==0) // Hiver
								$Camions+=25;
						}
						elseif($Base_Lat >55) // Europe du nord
						{
							if($Saison == 0) // Hiver
								$Camions +=25;
						}
						elseif($Base_Lat >43) // Europe continentale
						{
							if($Saison == 0) // Hiver
								$Camions +=10;
						}
						elseif($Base_Lat <33) // Désert
						{
							if($Saison == 3) // Ete (chaleur, pannes)
								$Camions +=5;
						}
						if($Zone ==5 or $Zone ==9)
							$Camions +=20;
						elseif($Zone ==4)
							$Camions +=15;
						elseif($Zone ==3)
							$Camions +=10;
						elseif($Zone ==2 or $Zone ==8)
							$Camions +=5;
						$Efficacite_ravit_muns=round(($Efficacite_ravit-$Camions)*($Inf_base/100),2);
					}
					if($Efficacite_ravit_muns<0)$Efficacite_ravit_muns=0;					
					include_once('./menu_escadrille.php');
		?>
		<h3>Stocks de munitions</h3>
		<table class='table'>
			<thead><tr><th>8mm</th><th>13mm</th><th>20mm</th><th>30mm</th><th>40mm</th><th>75mm</th><th>90mm</th><th>105mm</th><th>125mm</th></tr></thead>
			<tr>
				<td><?echo $Stock_Munitions_8;?></td>
				<td><?echo $Stock_Munitions_13;?></td>
				<td><?echo $Stock_Munitions_20;?></td>
				<td><?echo $Stock_Munitions_30;?></td>
				<td><?echo $Stock_Munitions_40;?></td>
				<td><?echo $Stock_Munitions_75;?></td>
				<td><?echo $Stock_Munitions_90;?></td>
				<td><?echo $Stock_Munitions_105;?></td>
				<td><?echo $Stock_Munitions_125;?></td>
			</tr>
		</table>
		<?			if($Credits >0){?>
						<h3>Gestion des pièces de DCA <a href='#' class='popup'><img src='images/help.png'><span>La production et le ravitaillement doivent être supérieurs à 49%!</span></a></h3>
						<form action='esc_dca2.php' method='post'>
							<input type='hidden' name='Unite' value="<? echo $Unite;?>">
							<table class='table table-hover'>
								<thead><tr><th>Nom</th><th>CT <a href='#' class='popup'><img src='images/help.png'><span>Coût unitaire</span></a></th><th>Munitions</th><th>Dégats Max</th><th>Plafond</th><th>Transport</th><th>Pièces <a href='#' class='popup'><img src='images/help.png'><span>Nombre de pièces actuelles ou en commandes. Le coût sera égal à la différence entre le chiffre indiqué au départ (actuel) et le chiffre sélectionné avant validation (en commande)</span></a></th>
								<th>Altitude <a href='#' class='popup'><img src='images/help.png'><span>Altitude sur laquelle la batterie sera réglée par défaut</span></a></th><th title='Expertise des artilleurs'>Artilleurs</th></tr><thead>
								<?echo $dca_pieces;?>
								<tr><th colspan="10">Nombre de pièces actuel / Maximum possible : <?echo $total_pieces;?> / 10</th></tr>
							</table>
						<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
		<?
					}
				}
				else
				{
					include_once('./menu_escadrille.php');
					echo "<div class='alert alert-danger'>Vous devez faire partie de cette unité depuis plus de 3 jours avant de pouvoir accéder à cette fonctionnalité</div>";
				}
			}
			else
			{
				include_once('./menu_escadrille.php');
				PrintNoAccessPil($country,1,2,3);
			}
		}
		/*else
			echo "<h6>Votre unité manque de personnel pour cela.</h6>";*/
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
//include_once('./index.php');
?>