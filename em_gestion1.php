<?php
require_once './jfv_inc_sessions.php';
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		$country=$_SESSION['country'];
		include_once './jfv_include.inc.php';
		include_once './jfv_txt.inc.php' ;
		$Unites_Type=Insec($_POST['type']);
		$Lieu_g=Insec($_POST['lieu']);
		include_once './jfv_inc_em.php' ;
		include_once './menu_em.php' ;
		//include_once './menu_staff.php';
		if(($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_EM or $GHQ) and $Front !=12)
		{
			if($Lieu_g and ($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_EM))
			{
				$query="SELECT ID,Nom,Zone,DefenseAA,DefenseAA_temp,ValeurStrat,BaseAerienne,Industrie,Radar,Port,NoeudF,LongPiste,QualitePiste,Tour,Recce,Auto_repare,
				Flag,Flag_Air,Flag_Gare,Flag_Port,Flag_Usine,Flag_Radar FROM Lieu WHERE ID='$Lieu_g' ORDER BY Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
					{
						$ID_lieu=$data['ID'];
						$Nom_lieu=$data['Nom'];
						$Zone=$data['Zone'];
						$DCA=$data['DefenseAA'];
						$DCA_temp=$data['DefenseAA_temp'];
						$ValeurStrat=$data['ValeurStrat'];
						$Industrie=$data['Industrie'];
						$BaseAerienne=$data['BaseAerienne'];
						$QualitePiste=$data['QualitePiste'];
						$Tour=$data['Tour'];
						$Gare=$data['NoeudF'];
						$Port=$data['Port'];
						$Radar=$data['Radar'];
						$LongPiste=$data['LongPiste'];
						$Flag=$data['Flag'];
						$Flag_Air=$data['Flag_Air'];
						$Flag_Gare=$data['Flag_Gare'];
						$Flag_Port=$data['Flag_Port'];
						$Flag_Usine=$data['Flag_Usine'];
						$Flag_Radar=$data['Flag_Radar'];
						$Recce=$data['Recce'];
						$Auto_repare=$data['Auto_repare'];
					}
					mysqli_free_result($result);
				}
				$DCA_p=10-$DCA_temp;
				$Ouvriers=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Pool_ouvriers");				
				$toolbar="<h2>Amélioration des infrastructures de ".$Nom_lieu."</h2><form action='index.php?view=em_gestion2' method='post'><input type='hidden' name='lieu' value='".$ID_lieu."'>
				<table class='table'><thead><tr><th colspan='3'>Le front dispose de ".$Ouvriers." ouvriers disponibles</th></tr></thead>";
				if($DCA and $Flag ==$country)
				{
					$toolbar.="<tr><td align='left'>- Augmenter la DCA de <select name='dca' style='width: 50px'><option value='0'>0</option>";
					if($DCA_temp >0)
					{
						$Cout_Fort=(10*$DCA_temp);
						if($DCA_p >=1 and $Ouvriers >=$Cout_Fort)
							$toolbar.="<option value='1'>1 (".$Cout_Fort." ouvriers)</option>";
						$Cout_Fort=(20*$DCA_temp);
						if($DCA_p >=2 and $Ouvriers >=$Cout_Fort)
							$toolbar.="<option value='2'>2 (".$Cout_Fort." ouvriers)</option>";
						$Cout_Fort=(30*$DCA_temp);
						if($DCA_p >=3 and $Ouvriers >=$Cout_Fort)
							$toolbar.="<option value='3'>3 (".$Cout_Fort." ouvriers)</option>";
						$Cout_Fort=(40*$DCA_temp);
						if($DCA_p >=4 and $Ouvriers >=$Cout_Fort)
							$toolbar.="<option value='4'>4 (".$Cout_Fort." ouvriers)</option>";
					}
					else
					{
						$Cout_Fort=10*($DCA_temp+1);
						if($DCA_p >=1 and $Ouvriers >=$Cout_Fort)
							$toolbar.="<option value='1'>1 (".$Cout_Fort." ouvriers)</option>";
					}
					$toolbar.="</select>niveau</td></tr>";
				}
				/*if($DCA and $Flag ==$country)
				{
					$toolbar.="<tr><td align='left'>Envoyer
					<select name='dca' style='width: 100px'>		
						<option value='0'>0 batteries</option>";	
						if($DCA_p >=1 and $Ouvriers >=10){
						$toolbar.="<option value='1'>1 batterie (10 ouvriers)</option>";
						}if($DCA_p >=2 and $Ouvriers >=20){
						$toolbar.="<option value='2'>2 batteries (20 ouvriers)</option>";
						}if($DCA_p >=3 and $Ouvriers >=30){
						$toolbar.="<option value='3'>3 batteries (30 ouvriers)</option>";
						}if($DCA_p >=4 and $Ouvriers >=40){
						$toolbar.="<option value='4'>4 batteries (40 ouvriers)</option>";
						}if($DCA_p >=5 and $Ouvriers >=50)
							$toolbar.="<option value='5'>5 batteries (50 ouvriers)</option>";
					$toolbar.="</select>de DCA en renfort</td></tr>";
				}*/
				if($Radar >0 and $Radar <100 and $Flag ==$country and $Flag_Radar ==$country)
				{
					$toolbar.="<tr><td align='left'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Envoyer
					<select name='radar' style='width: 100px'><option value='0'>0</option>";
						if($Radar <90 and $Credits >=2 and $Ouvriers >19){
						$toolbar.="<option value='1'>20</option>";
						}if($Radar <80 and $Credits >=2 and $Ouvriers >39){
						$toolbar.="<option value='2'>40</option>";
						}if($Radar <70 and $Credits >=2 and $Ouvriers >59){
						$toolbar.="<option value='3'>60</option>";
						}if($Radar <60 and $Credits >=2 and $Ouvriers >79){
						$toolbar.="<option value='4'>80</option>";
						}if($Radar <50 and $Credits >=2 and $Ouvriers >99)
							$toolbar.="<option value='5'>100</option>";
					$toolbar.="</select>ouvriers pour accélérer la réparation du radar</td></tr>";
				}
				if($Gare >0 and $Gare <100 and $Flag ==$country and $Flag_Gare ==$country)
				{
					$toolbar.="<tr><td align='left'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Envoyer
					<select name='gare' style='width: 100px'><option value='0'>0</option>";
						if($Gare <90 and $Credits >=2 and $Ouvriers >19){
						$toolbar.="<option value='1'>20</option>";
						}if($Gare <80 and $Credits >=2 and $Ouvriers >39){
						$toolbar.="<option value='2'>40</option>";
						}if($Gare <70 and $Credits >=2 and $Ouvriers >59){
						$toolbar.="<option value='3'>60</option>";
						}if($Gare <60 and $Credits >=2 and $Ouvriers >79){
						$toolbar.="<option value='4'>80</option>";
						}if($Gare <50 and $Credits >=2 and $Ouvriers >99)
							$toolbar.="<option value='5'>100</option>";
					$toolbar.="</select>ouvriers pour accélérer la réparation du noeud ferroviaire</td></tr>";
				}
				if($Port >0 and $Port <100 and $Flag ==$country and $Flag_Port ==$country)
				{
					$toolbar.="<tr><td align='left'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Envoyer
					<select name='port' style='width: 100px'><option value='0'>0</option>";
						if($Port <90 and $Credits >=2 and $Ouvriers >19){
						$toolbar.="<option value='1'>20</option>";
						}if($Port <80 and $Credits >=2 and $Ouvriers >39){
						$toolbar.="<option value='2'>40</option>";
						}if($Port <70 and $Credits >=2 and $Ouvriers >59){
						$toolbar.="<option value='3'>60</option>";
						}if($Port <60 and $Credits >=2 and $Ouvriers >79){
						$toolbar.="<option value='4'>80</option>";
						}if($Port <50 and $Credits >=2 and $Ouvriers >99)
							$toolbar.="<option value='5'>100</option>";
					$toolbar.="</select>ouvriers pour accélérer la réparation du port</td></tr>";
				}
				if($Industrie >0 and $Industrie <100 and $Flag ==$country and $Flag_Usine ==$country)
				{
					$toolbar.="<tr><td align='left'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Envoyer
					<select name='usine' style='width: 100px'><option value='0'>0</option>";
						if($Industrie <90 and $Credits >=2 and $Ouvriers >19)
							$toolbar.="<option value='1'>20</option>";
						if($Industrie <80 and $Credits >=2 and $Ouvriers >39)
							$toolbar.="<option value='2'>40</option>";
						if($Industrie <70 and $Credits >=2 and $Ouvriers >59)
							$toolbar.="<option value='3'>60</option>";
						if($Industrie <60 and $Credits >=2 and $Ouvriers >79)
							$toolbar.="<option value='4'>80</option>";
						if($Industrie <50 and $Credits >=2 and $Ouvriers >99)
							$toolbar.="<option value='5'>100</option>";
					$toolbar.="</select>ouvriers pour accélérer la réparation de l'usine</td></tr>";
				}
				if($BaseAerienne and $Flag ==$country and $Flag_Air ==$country)
				{
					if($Zone ==3 or $Zone ==4 or $Zone ==5 or $Zone ==7 or $Zone ==9)
						$LongPisteMax=1151;
					elseif($Zone ==1 or $Zone ==2 or $Zone ==11)
						$LongPisteMax=1351;
					else
						$LongPisteMax=1951;
					if($Tour <100)
					{
						$toolbar.="<tr><td align='left'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'>- Envoyer
						<select name='tour' style='width: 100px'><option value='0'>0</option>";
							if($Tour <90 and $Credits >=2 and $Ouvriers >9){
							$toolbar.="<option value='1'>10</option>";
							}if($Tour <80 and $Credits >=2 and $Ouvriers >19){
							$toolbar.="<option value='2'>20</option>";
							}if($Tour <70 and $Credits >=2 and $Ouvriers >29){
							$toolbar.="<option value='3'>30</option>";
							}if($Tour <60 and $Credits >=2 and $Ouvriers >39){
							$toolbar.="<option value='4'>40</option>";
							}if($Tour <50 and $Credits >=2 and $Ouvriers >49)
								$toolbar.="<option value='5'>50</option>";
						$toolbar.="</select> ouvriers pour accélérer la réparation de la tour</td></tr>";
					}
					if($LongPiste <$LongPisteMax and $QualitePiste ==100)
					{
						$toolbar.="<tr><td align='left'><img src='images/CT30.png' title='Montant en Crédits Temps que nécessite cette action'>- Envoyer
						<select name='piste' style='width: 100px'><option value='0'>0</option>";	
						if($Credits >=30 and $Ouvriers >99)
							$toolbar.="<option value='1'>100</option>";
						$toolbar.="</select> ouvriers pour agrandir la piste (".$LongPiste."m actuellement) <a href='#' class='popup'><img src='images/help.png'><span>La piste doit être à 100% pour pouvoir agrandir</span></a></td></tr>";	
					}
				}
				if($Recce >0 and $Flag ==$country)
				{
					$CT_Discount=Get_CT_Discount($Avancement);
					$con=dbconnecti();
					$Observation=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Cible='$Lieu_g' AND Task=1 AND Avion>0 AND Pays<>'$country' AND Actif=1"),0);
					mysqli_close($con);
					$Cr_cam=7+$Observation-$CT_Discount;
					if($Cr_cam <1)$Cr_cam=1;
					if($Credits >$Cr_cam)
						$toolbar.="<tr><td align='left'><img src='images/CT".$Cr_cam.".png' title='Montant en Crédits Temps que nécessite cette action'>- Ordonner de camoufler le site d'urgence<br>
								<Input type='Radio' name='recce' value='0' checked>- Non<br><Input type='Radio' name='recce' value='1'>- Oui<br></td></tr>";
					else
						$toolbar.="<tr><td align='left'><img src='images/CT".$Cr_cam.".png' title='Montant en Crédits Temps que nécessite cette action'>- Ordonner de camoufler le site d'urgence<br>
						La présence d'avions d'observation ennemis empêche le camouflage ou vous ne disposez pas de suffisamment de CT pour effectuer cette action</td></tr>";
				}
				if($ValeurStrat)
				{
					if($Auto_repare)
						$Auto_repare="Réparation activée";
					else
						$Auto_repare="Réparation annulée";
					$toolbar.="<tr><td align='left' title='Si le lieu a une valeur stratégique non nulle et que depuis au moins 3 jours ce lieu est vierge de toute attaque, les infrastructures détruites (0%) sont remises à 1%'>- Annuler la réparation automatique des infrastructures (actuellement : ".$Auto_repare.")<br>
							<Input type='Radio' name='auto_repa' value='0' checked>- Ne rien changer<br><Input type='Radio' name='auto_repa' value='2'>- Non<br><Input type='Radio' name='auto_repa' value='1'>- Oui<br></td></tr>";
				}
				$toolbar.="</table><input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form><hr>";
				$Cible=$Lieu_g;
				include_once 'em_city_ground.php';
			}
			elseif($Unites_Type and ($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint))
			{
				$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
				$Sqn=GetSqn($country);
				if($Front ==3)
					$query_unit="SELECT u.* FROM Unit as u,Lieu as l WHERE u.Type='$Unites_Type' AND u.Pays='$country' AND u.Etat=1 AND u.Base=l.ID AND l.Longitude >67 ORDER BY u.Nom ASC";
				elseif($Front ==2)
					$query_unit="SELECT u.* FROM Unit as u,Lieu as l WHERE u.Type='$Unites_Type' AND u.Pays='$country' AND u.Etat=1 AND u.Base=l.ID AND l.Longitude <67 AND l.Latitude <41 ORDER BY u.Nom ASC";
				elseif($Front ==1)
				{
					if($country ==8)
						$query_unit="SELECT u.* FROM Unit as u,Lieu as l WHERE u.Type='$Unites_Type' AND u.Pays='$country' AND u.Etat=1 AND u.Base=l.ID AND (l.Longitude BETWEEN 14 AND 70) AND (l.Latitude BETWEEN 40.38 AND 50.5) ORDER BY u.Nom ASC";
					else
						$query_unit="SELECT u.* FROM Unit as u,Lieu as l WHERE u.Type='$Unites_Type' AND u.Pays='$country' AND u.Etat=1 AND u.Base=l.ID AND (l.Longitude BETWEEN 14 AND 70) AND (l.Latitude BETWEEN 41 AND 50.5) ORDER BY u.Nom ASC";
				}
				elseif($Front ==4)
					$query_unit="SELECT u.* FROM Unit as u,Lieu as l WHERE u.Type='$Unites_Type' AND u.Pays='$country' AND u.Etat=1 AND u.Base=l.ID AND (l.Longitude BETWEEN 14 AND 50) AND l.Latitude >50.5 ORDER BY u.Nom ASC";
				elseif($Front ==5)
					$query_unit="SELECT u.* FROM Unit as u,Lieu as l WHERE u.Type='$Unites_Type' AND u.Pays='$country' AND u.Etat=1 AND u.Base=l.ID AND (l.Longitude BETWEEN -50 AND 60) AND l.Latitude >58 ORDER BY u.Nom ASC";
				else
					$query_unit="SELECT u.* FROM Unit as u,Lieu as l WHERE u.Type='$Unites_Type' AND u.Pays='$country' AND u.Etat=1 AND u.Base=l.ID AND l.Longitude <14 AND l.Latitude >=43 AND l.Latitude <60 ORDER BY u.Nom ASC";
				$con=dbconnecti();
				$result_unit=mysqli_query($con,$query_unit);
				mysqli_close($con);
				if($result_unit)
				{
					while($Data=mysqli_fetch_array($result_unit,MYSQLI_ASSOC)) 
					{
						$Unite=$Data['ID'];
						$Unite_Nom=$Data['Nom'];
						$Unite_Type=$Data['Type'];
						$Avion1=$Data['Avion1'];
						$Avion2=$Data['Avion2'];
						$Avion3=$Data['Avion3'];
						$Avion1_Nbr=$Data['Avion1_Nbr'];
						$Avion2_Nbr=$Data['Avion2_Nbr'];
						$Avion3_Nbr=$Data['Avion3_Nbr'];
						$Avion1_nom=GetData("Avion","ID",$Avion1,"Nom");
						$Avion2_nom=GetData("Avion","ID",$Avion2,"Nom");
						$Avion3_nom=GetData("Avion","ID",$Avion3,"Nom");						
						$Units.="<option value='".$Unite."'>".$Unite_Nom."</option>";
						$flights.="<table class='table'><thead><tr><th colspan='3'>".$Unite_Nom."</th></tr></thead>
								<tr><th>".$Sqn." 1</th><th>".$Sqn." 2</th><th>".$Sqn." 3</th></tr><tr>
									<td>".$Avion1_Nbr." ".GetAvionIcon($Avion1,$country,0,$Unite,$Front)."</td>
									<td>".$Avion2_Nbr." ".GetAvionIcon($Avion2,$country,0,$Unite,$Front)."</td>
									<td>".$Avion3_Nbr." ".GetAvionIcon($Avion3,$country,0,$Unite,$Front)."</td>
								</tr></table>";
					}
					mysqli_free_result($result_unit);
				}
				$modele='';
				if($Unites_Type ==1)
					$query="SELECT DISTINCT ID,Nom,Type FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Type IN (1,5) AND Fin_Prod<'$Date_Campagne' ORDER BY Nom ASC";
				else
					$query="SELECT DISTINCT ID,Nom,Type FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Type='$Unites_Type' AND Fin_Prod<'$Date_Campagne' ORDER BY Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : em_gestion-cmodele');
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
					{
						$Plane=$data['ID'];
						//Prod
						$con=dbconnecti();
						$Production=mysqli_result(mysqli_query($con,"SELECT Stock FROM Avion WHERE ID='$Plane'"),0);
						$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Plane' AND PVP=1"),0);
						$DCAp=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Plane'"),0);
						$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$Plane' AND Etat=1"),0);
						$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$Plane' AND Etat=1"),0);
						$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$Plane' AND Etat=1"),0);
						mysqli_close($con);
						$con=dbconnecti(4);
						$Perdu=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$Plane' AND Avion_Nbr >0"),0);
						mysqli_close($con);								
						if(($DCAp + $Abattu + $Perdu + $Service1 + $Service2 + $Service3) <$Production)
						{
							$Type=GetAvionType($data['Type']);
							$modele.="<option value='".$data['ID']."'>".$data['Nom']." ( ".$Type." )</option>";
						}
					}
					mysqli_free_result($result);
				}
				/*?>
		<form action='index.php?view=em_gestion2' method='post'>
		<h2>Transférer des avions entre les unités</h2>
		<table class='table'>
			<thead><tr><th>Avions</th><th>Du</th><th>Au</th><th><img src='images/CT40.png' title='Montant en Crédits Temps que nécessite cette action'> Intervertir la dotation complète</th></tr></thead>
			<tr><td>
				<select name="nbr" class='form-control' style="width: 200px">
					<option value="0">0</option>
				<?if($Credits >=2){?>
					<option value="1">1 (2 CT)</option>
				<?}if($Credits >=4){?>
					<option value="2">2 (4 CT)</option>
				<?}if($Credits >=6){?>
					<option value="3">3 (6 CT)</option>
				<?}if($Credits >=8){?>
					<option value="4">4 (8 CT)</option>
				<?}if($Credits >=10){?>
					<option value="5">5 (10 CT)</option>
				<?}if($Credits >=12){?>
					<option value="6">6 (12 CT)</option>
				<?}if($Credits >=14){?>
					<option value="7">7 (14 CT)</option>
				<?}if($Credits >=16){?>
					<option value="8">8 (16 CT)</option>
				<?}if($Credits >=18){?>
					<option value="9">9 (18 CT)</option>
				<?}if($Credits >=20){?>
					<option value="10">10 (20 CT)</option>
				<?}?>
				</select></td>
				<td><select name="unitea" class='form-control' style="width: 200px">		
					<?echo $Units;?>
				</select></td>
				<td><select name="uniteb" class='form-control' style="width:200px">		
					<?echo $Units;?>
				</select></td>
				<td><Input type='Radio' name='intervertir' value='0' checked>- Non<br></td>
			</tr>
			<tr>
				<td><input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td>
				<td><select name="sqna" class='form-control' style="width: 200px">		
					<option value="2"><?echo $Sqn?> 2</option>
					<option value="3"><?echo $Sqn?> 3</option>
				</select></td>
				<td><select name="sqnb" class='form-control' style="width: 200px">		
					<option value="1"><?echo $Sqn?> 1</option>
					<option value="2"><?echo $Sqn?> 2</option>
					<option value="3"><?echo $Sqn?> 3</option>
				</select></td>
				<?if($Credits >=40){?>
				<td><Input type='Radio' name='intervertir' value='1'>- Oui<br></td>
				<?}?>
			</tr>
		</table></form>
		<p><i>Le Transfert ne fonctionne qu'entre deux <?echo $Sqn;?> équipés du même modèle d'avion<br>
		Un(e) <?echo $Sqn;?> ne peut contenir plus de <?echo GetMaxFlight($Unites_Type,0,0);?> avions à la suite d'un transfert<br>
		Intervertir permet d'échanger intégralement la dotation entre 2 <?echo $Sqn;?>, peu importe les avions utilisés. Il existe cependant une limite de 1000km entre les unités pour cette action.</i></p>
	<?*/if($modele){?>
	<h2><img src='images/CT12.png' title='Montant en Crédits Temps que nécessite cette action'> Remplacer un modèle d'avion par de l'équipement de réserve</h2>
		<form action='index.php?view=em_gestion2' method='post'>
		<table class='table'>
			<thead><tr><th colspan='2'>Unité</th><th>Avion</th></tr></thead>
			<tr>
				<td><select name="unitec" class='form-control' style="width:200px"><?=$Units?></select></td>
				<td><select name="sqnc" class='form-control' style="width: 200px">		
					<option value="1"><?=$Sqn?> 1</option>
					<option value="2"><?=$Sqn?> 2</option>
					<option value="3"><?=$Sqn?> 3</option>
				</select></td>
				<td><select name="avionc" class='form-control' style="width: 300px"><?=$modele?></select></td>
			</tr>
		</table><input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
	<?}?>
	<hr>
	<h2>Unités</h2>
	<div style='overflow:auto; height: 400px;'>
	<?=$flights?>
	</div>
	<?
			}
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";