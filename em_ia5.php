<?php
require_once './jfv_inc_sessions.php';
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		include_once './jfv_include.inc.php';
		include_once './jfv_txt.inc.php';
		$country=$_SESSION['country'];
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Avancement,Front,Pays,Armee FROM Officier_em WHERE ID='$OfficierEMID'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avancement=$data['Avancement'];
				$Front=$data['Front'];
				$country=$data['Pays'];
				$Armee=$data['Armee'];
			}
			mysqli_free_result($result);
			unset($data);
			//$CT_Discount=Get_CT_Discount($Avancement);
		}
		if(!$MIA)
		{
			$Unite=Insec($_POST['Unite']);
			$Cible=Insec($_POST['Cible']);
			$Mission_Flight=Insec($_POST['Flight']);
			if($Unite)
			{
				$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens,Cdt_Chasse,Cdt_Bomb,Cdt_Reco,Cdt_Atk FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
				$result=mysqli_query($con,"SELECT Nom,Type,Armee,Base,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Mission_alt,CT FROM Unit WHERE ID='$Unite'");
				if($result2)
				{
					while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
					{
						$Commandant=$data['Commandant'];
						$Officier_Adjoint=$data['Adjoint_EM'];
						$Officier_EM=$data['Officier_EM'];
						$Officier_Rens=$data['Officier_Rens'];
						$Cdt_Chasse=$data['Cdt_Chasse'];
						$Cdt_Bomb=$data['Cdt_Bomb'];
						$Cdt_Reco=$data['Cdt_Reco'];
						$Cdt_Atk=$data['Cdt_Atk'];
					}
					mysqli_free_result($result2);
				}
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Unite_Nom=$data['Nom'];
						$Unite_Type=$data['Type'];
						$Unite_Armee=$data['Armee'];
						$Base=$data['Base'];
						$Avion1=$data['Avion1'];
						$Avion2=$data['Avion2'];
						$Avion3=$data['Avion3'];
						$Avion1_Nbr=$data['Avion1_Nbr'];
						$Avion2_Nbr=$data['Avion2_Nbr'];
						$Avion3_Nbr=$data['Avion3_Nbr'];
						$Mission_alt=$data['Mission_alt'];
                        $Credits=$data['CT'];
					}
					mysqli_free_result($result);
					unset($data);
				}
			}
			if($Credits >0 and $Base >0 and $Cible >0 and $Mission_Flight >0)
			{
				if(($OfficierEMID >0 and ($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk or $Unite_Armee ==$Armee)) or $Admin ==1)
				{	
					$result=mysqli_query($con,"SELECT Nom,Pays,Longitude,Latitude FROM Lieu WHERE ID='$Base'");
					$Pilotes_max=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Actif='1'"),0);
					$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Courage >0 AND Moral >0 AND Actif=1"),0);
					$Pilote_id=mysqli_result(mysqli_query($con,"SELECT Pilote_id FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
					if($Pilote_id)$Front_Pilote=mysqli_result(mysqli_query($con,"SELECT Front FROM Pilote WHERE ID='$Pilote_id'"),0);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Base_Nom=$data['Nom'];
							$Base_Pays=$data['Pays'];
							$Longitude_base=$data['Longitude'];
							$Latitude_base=$data['Latitude'];
						}
						mysqli_free_result($result);
						unset($data);
					}				
					$Sqn=GetSqn($country);
					//Output
					echo "<h1><img src='images/unit/unit".$Unite."p.gif'> ".$Unite_Nom."</h1><h2><small><img src='images/base14.png' title='Base actuelle'> ".$Base_Nom." <img src='images/".$Base_Pays."20.gif' title='".GetPays($Base_Pays)."'></small></h2>";
					echo "<table class='table'><thead><tr><th>".$Sqn." 1</th><th>".$Sqn." 2</th><th>".$Sqn." 3</th></tr></thead>
					<tr><td>".$Avion1_Nbr." ".GetAvionIcon($Avion1,$country,0,$Unite,$Front)."</td><td>".$Avion2_Nbr." ".GetAvionIcon($Avion2,$country,0,$Unite,$Front)."</td><td>".$Avion3_Nbr." ".GetAvionIcon($Avion3,$country,0,$Unite,$Front)."</td></tr></table>";
					echo "<p><img src='images/obs.png' style='width:5%;'> <b>".$Pilotes."</b>/".$Pilotes_max." pilotes en état de vol <a href='#' class='popup'><img src='images/help.png'><span>Les unités commandées par des pilotes joueurs peuvent remonter le moral et le courage de leurs pilotes. Vous pouvez également le faire si vous disposez de ".$CT_Refit." CT</span></a></p>";
					if($Credits <1)
						echo "<h6>Vous ne disposez pas de suffisamment de Crédits Temps pour assigner une mission à votre unité !<h6>";
					else
					{
						if($Front_Pilote ==$Front)$Pil_Front=true;
						$Coord=GetCoord($Front,$country);
						$Lat_base_min=$Coord[0];
						$Lat_base_max=$Coord[1];
						$Long_base_min=$Coord[2];
						$Long_base_max=$Coord[3];
						$query="SELECT Nom,Zone,NoeudF_Ori,NoeudR,Industrie,BaseAerienne,Port_Ori,Pont_Ori,Radar_Ori,Plage FROM Lieu WHERE ID='$Cible'";
						$result=mysqli_query($con,$query) or die(mysqli_error($con));
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
							{
								$Nom_cible=$data['Nom'];
								$Zone=$data['Zone'];
								$NoeudF_Ori=$data['NoeudF_Ori'];
								$NoeudR=$data['NoeudR'];
								$Industrie=$data['Industrie'];
								$BaseAerienne=$data['BaseAerienne'];
								$Port_Ori=$data['Port_Ori'];
								$Pont_Ori=$data['Pont_Ori'];
								$Radar_Ori=$data['Radar_Ori'];
								$Plage=$data['Plage'];
							}
							mysqli_free_result($result);
							unset($data);
						}
						else
							$mes.="Erreur d'import de données.";
						if($Cible)
						{
							if($Zone ==6){
								$Miss_txt='Navale';
								$Mission_Type=12;
							}
							else{
								$Miss_txt='Tactique';
								$Mission_Type=2;
							}
							include_once './jfv_avions.inc.php';
							$Avion_Var='Avion'.$Mission_Flight;
							$Array_Mod=GetAmeliorations($$Avion_Var);
							$Canon=$Array_Mod[3];
							$Bombe50_nbr=$Array_Mod[12];
							$Bombe125_nbr=$Array_Mod[13];
							$Bombe250_nbr=$Array_Mod[14];
							$Bombe500_nbr=$Array_Mod[15];
							$Torpille=$Array_Mod[20];
							$Rockets=$Array_Mod[35];
							$Zones_txt="<select name='Zoneb' class='form-control' style='width: 300px'>";
							if($Unite_Type ==2 or $Unite_Type ==7 or $Unite_Type ==10){
								$CT_Mult=1;
								$Zones_txt.="<option value='0'>Toutes</option>";
							}
							else
								$CT_Mult=2;
							if($Zone ==6)
								$Zones_txt.="<option value='8'>Au large</option>";
							else{
								$Zones_txt.="<option value='10'>Caserne</option>";
								if($BaseAerienne)
									$Zones_txt.="<option value='1'>Aérodrome</option>";
								if($NoeudF_Ori)
									$Zones_txt.="<option value='3'>Gare</option>";
								if($NoeudR)
									$Zones_txt.="<option value='2'>Noeud Routier</option>";
								if($Plage)
									$Zones_txt.="<option value='11'>Plage</option>";
								if($Pont_Ori)
									$Zones_txt.="<option value='5'>Fleuve</option>";
								if($Port_Ori)
									$Zones_txt.="<option value='4'>Port</option>";
								if($Radar)
									$Zones_txt.="<option value='7'>Station radar</option>";
								if($Industrie)
									$Zones_txt.="<option value='6'>Zone industrielle</option>";
								if($Plage or $Port_Ori)
									$Zones_txt.="<option value='8'>Au large</option>";
							}
							$Zones_txt.='</select>';
							if(!$Officier_Adjoint and $OfficierEMID ==$Commandant)$EM_CT=1;
							elseif(!$Officier_Adjoint and !$Commandant)$EM_CT=1;
							echo "<h2>Créer une Mission de Bombardement ".$Miss_txt."</h2>
								<form action='em_ia1.php' method='post'>
								<input type='hidden' name='Type' value='".$Mission_Type."'>
								<input type='hidden' name='Unite' value='".$Unite."'>
								<input type='hidden' name='Cible' value='".$Cible."'>
								<input type='hidden' name='Altitude' value='500'>
								<input type='hidden' name='Flight' value='".$Mission_Flight."'>
								<input type='hidden' name='Avion1' value='".$Avion1."'>
								<input type='hidden' name='Avion2' value='".$Avion2."'>
								<input type='hidden' name='Avion3' value='".$Avion3."'>
								<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
								<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
								<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
								<table class='table'><thead><tr><th>Objectif</th><th>Type de Bombe</th><th>Zone</th></tr></thead>";
							echo "<tr><th>".$Nom_cible."</th><td align='left'><select name='Bombs' class='form-control' style='width: 300px'>";
							if($Canon >=1 and $Credits >=GetModCT(4*$CT_Mult,$country,$EM_CT,0,$Pil_Front))
								echo "<option value='20'>Canon (".GetModCT(4*$CT_Mult,$country,$EM_CT,0,$Pil_Front)."CT)</option>";
							if($Rockets >=1 and $Credits >=GetModCT(6*$CT_Mult,$country,$EM_CT,0,$Pil_Front))
								echo "<option value='80'>Rockets (".GetModCT(6*$CT_Mult,$country,$EM_CT,0,$Pil_Front)."CT)</option>";
							if($Bombe50_nbr >=1 and $Credits >=GetModCT(4*$CT_Mult,$country,$EM_CT,0,$Pil_Front))
								echo "<option value='50'>Bombes de 50kg (".GetModCT(4*$CT_Mult,$country,$EM_CT,0,$Pil_Front)."CT)</option>";
							if($Bombe125_nbr >=1 and $Credits >=GetModCT(5*$CT_Mult,$country,$EM_CT,0,$Pil_Front))
								echo "<option value='125'>Bombes de 125kg (".GetModCT(5*$CT_Mult,$country,$EM_CT,0,$Pil_Front)."CT)</option>";
							if($Bombe250_nbr >=1 and $Credits >=GetModCT(6*$CT_Mult,$country,$EM_CT,0,$Pil_Front))
								echo "<option value='250'>Bombes de 250kg (".GetModCT(6*$CT_Mult,$country,$EM_CT,0,$Pil_Front)."CT)</option>";
							if($Bombe500_nbr >=1 and $Credits >=GetModCT(8*$CT_Mult,$country,$EM_CT,0,$Pil_Front))
								echo "<option value='500'>Bombes de 500kg (".GetModCT(8*$CT_Mult,$country,$EM_CT,0,$Pil_Front)."CT)</option>";
							if($Torpille >=1 and $Credits >=GetModCT(8*$CT_Mult,$country,$EM_CT,0,$Pil_Front) and ($Zone ==6 or $Plage or $Port_Ori))
								echo "<option value='800'>Torpille (".GetModCT(8*$CT_Mult,$country,$EM_CT,0,$Pil_Front)."CT)</option>";
							echo "</option></select></td><td>".$Zones_txt."</td></tr>
							<tr><td><input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
							</table></form>";
						}
					}
				}
				else
					echo "<img src='images/top_secret.gif'>";
			}
			else
				echo "<div class='alert alert-danger'>Vous manquez de temps pour donner vos ordres...</div>";
		}
		else
			echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
	}
	else
		echo "<img src='images/top_secret.gif'>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once './index.php';