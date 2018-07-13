<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		include_once('./jfv_include.inc.php');
		include_once('./jfv_txt.inc.php');
		$country=$_SESSION['country'];
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Avancement,Credits,Front,Pays,Trait,Armee FROM Officier_em WHERE ID='$OfficierEMID'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avancement=$data['Avancement'];
				$Credits=$data['Credits'];
				$Front=$data['Front'];
				$Trait=$data['Trait'];
				$country=$data['Pays'];
				$Armee=$data['Armee'];
			}
			mysqli_free_result($result);
			unset($data);
			$CT_Discount=Get_CT_Discount($Avancement);
			if($Premium)$Legend=true;
		}
		if(!$MIA)
		{
			$Unite=Insec($_POST['Unite']);
			$Cible=Insec($_POST['Cible']);
			$Mission_Flight=Insec($_POST['Flight']);
			if($Unite)
			{
				//$con=dbconnecti();
				$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens,Cdt_Chasse,Cdt_Bomb,Cdt_Reco,Cdt_Atk FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
				$result=mysqli_query($con,"SELECT Nom,Type,Base,Armee,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Mission_Lieu,Mission_Type,Mission_alt,Mission_Lieu_D,Mission_Type_D FROM Unit WHERE ID='$Unite'");
				$Pilotes_max=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Actif='1'"),0);
				$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Courage >0 AND Moral >0 AND Actif=1"),0);
				mysqli_close($con);
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
						$Base=$data['Base'];
						$Avion1=$data['Avion1'];
						$Avion2=$data['Avion2'];
						$Avion3=$data['Avion3'];
						$Avion1_Nbr=$data['Avion1_Nbr'];
						$Avion2_Nbr=$data['Avion2_Nbr'];
						$Avion3_Nbr=$data['Avion3_Nbr'];
						$Mission_Lieu=$data['Mission_Lieu'];
						$Mission_Type=$data['Mission_Type'];
						$Mission_alt=$data['Mission_alt'];
						$Mission_Lieu_D=$data['Mission_Lieu_D'];
						$Mission_Type_D=$data['Mission_Type_D'];
						$Unit_Armee=$data['Armee'];
					}
					mysqli_free_result($result);
					unset($data);
				}
			}
			if($Credits >0 and $Base >0 and $Cible >0 and $Mission_Flight >0)
			{
				if(($OfficierEMID >0 and ($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk)) 
					or $Admin ==1 or ($Armee >0 and ($Unit_Armee ==$Armee)))
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Pays,Longitude,Latitude FROM Lieu WHERE ID='$Base'");
					mysqli_close($con);
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
					$Mission_Lieu=GetData("Lieu","ID",$Mission_Lieu,"Nom");
					if(!$Mission_Lieu)$Mission_Lieu="<i>Aucune</i>";
					$Mission_Type=GetMissionType($Mission_Type);
					if(!$Mission_Type)$Mission_Type="<i>Indéfini</i>";
					$Sqn=GetSqn($country);
					$CT_Refit=36-$CT_Discount;
					if($Trait==14)$CT_Refit-=2;
					//Output
					echo "<h1>".Afficher_Icone($Unite,$country).$Unite_Nom."</h1><h2><small><img src='images/base14.png' title='Base actuelle'> ".$Base_Nom." <img src='images/".$Base_Pays."20.gif' title='".GetPays($Base_Pays)."'></small></h2>";
					echo "<table class='table'><thead><tr><th>".$Sqn." 1</th><th>".$Sqn." 2</th><th>".$Sqn." 3</th></tr></thead>
					<tr><td>".$Avion1_Nbr." ".GetAvionIcon($Avion1,$country,0,$Unite,$Front,false,$Legend)."</td><td>".$Avion2_Nbr." ".GetAvionIcon($Avion2,$country,0,$Unite,$Front,false,$Legend)."</td><td>".$Avion3_Nbr." ".GetAvionIcon($Avion3,$country,0,$Unite,$Front,false,$Legend)."</td></tr></table>";
					echo "<p><img src='images/obs.png' style='width:5%;'> <b>".$Pilotes."</b>/".$Pilotes_max." pilotes en état de vol <a href='#' class='popup'><img src='images/help.png'><span>Les unités commandées par des pilotes joueurs peuvent remonter le moral et le courage de leurs pilotes. Vous pouvez également le faire si vous disposez de ".$CT_Refit." CT</span></a></p>";
					if($Credits <1)
						echo "<h6>Vous ne disposez pas de suffisamment de Crédits Temps pour assigner une mission à votre unité !<h6>";
					else
					{
						$Coord=GetCoord($Front,$country);
						$Lat_base_min=$Coord[0];
						$Lat_base_max=$Coord[1];
						$Long_base_min=$Coord[2];
						$Long_base_max=$Coord[3];
						$query="SELECT ID,Nom,Industrie,NoeudF,Pont,Port,Radar,QualitePiste,ValeurStrat,BaseAerienne FROM Lieu WHERE ID='$Cible'";
						$con=dbconnecti();
						$result=mysqli_query($con,$query) or die(mysqli_error($con));
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
							{
								$Nom_cible=$data['Nom'];
								$Usine=$data['Industrie'];
								$Gare=$data['NoeudF'];
								$Pont=$data['Pont'];
								$Port=$data['Port'];
								$Radar=$data['Radar'];
								$Piste=$data['QualitePiste'];
								$BaseAerienne=$data['BaseAerienne'];
								$ValStrat=$data['ValeurStrat'];
							}
							mysqli_free_result($result);
							unset($data);
						}
						else
							$mes.="Erreur d'import de données.";
						if($Cible)
						{
							include_once('./jfv_avions.inc.php');
							if($Unite_Type ==2 or $Unite_Type ==11)$Bomb_nuit_choix="<option value='16'>Bombardement stratégique de nuit</option>";
							$Avion_Var="Avion".$Mission_Flight;
							$Plafond=GetData("Avion","ID",$$Avion_Var,"Plafond");
							$Array_Mod=GetAmeliorations($$Avion_Var);
							$Bombe125_nbr=$Array_Mod[13];
							$Bombe250_nbr=$Array_Mod[14];
							$Bombe500_nbr=$Array_Mod[15];
							$Bombe1000_nbr=$Array_Mod[32];
							echo "<h2>Créer une Mission de Bombardement</h2>
								<form action='em_ia1.php' method='post'>
								<input type='hidden' name='Unite' value='".$Unite."'>
								<input type='hidden' name='Cible' value='".$Cible."'>
								<input type='hidden' name='Flight' value='".$Mission_Flight."'>
								<input type='hidden' name='Avion1' value='".$Avion1."'>
								<input type='hidden' name='Avion2' value='".$Avion2."'>
								<input type='hidden' name='Avion3' value='".$Avion3."'>
								<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
								<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
								<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
								<table class='table'><tr><th>Objectif</th><th>".$Nom_cible."</th></tr><tr><th>Choix de la Cible</th><td align='left'><select name='Cible_Atk' class='form-control' style='width: 300px'><option value='9'>La caserne</option>";
							if($Piste >0)
								echo "<option value='1'>L'aérodrome</option>";
							if($Gare >0)
								echo "<option value='4'>La gare</option>";
							if($Pont >0)
								echo "<option value='5'>Le pont</option>";
							if($Port >0)
								echo "<option value='6'>Le port</option>";
							if($Radar >0)
								echo "<option value='7'>Le radar</option>";
							if($Usine >0)
								echo "<option value='2'>L'usine</option>";
							if($ValStrat >3)
								echo "<option value='8'>Le dépôt</option>";
							if($BaseAerienne >0)
								echo "<option value='3'>Les avions sur l'aérodrome</option>";
							echo "</select></td></tr><tr><th>Choix de l'altitude de Mission</th><td align='left'><select name='Bombs' class='form-control' style='width: 300px'><option value='50'>Bombes de 50kg (1CT)</option>";
							if($Bombe125_nbr >=1 and $Credits >=1)
								echo "<option value='125'>Bombes de 125kg (1CT)</option>";
							if($Bombe250_nbr >=1 and $Credits >=1)
								echo "<option value='250'>Bombes de 250kg (1CT)</option>";
							if($Bombe500_nbr >=1 and $Credits >=2)
								echo "<option value='500'>Bombes de 500kg (2CT)</option>";
							if($Bombe1000_nbr >=1 and $Credits >=4)
								echo "<option value='1000'>Bombes de 1000kg (4CT)</option>";
							echo "</select></td></tr><tr><th>Choix du Type de Mission</th><td align='left'><select name='Type' class='form-control' style='width: 300px'>
								<option value='8'>Bombardement stratégique de jour</option>".$Bomb_nuit_choix."</select></td></tr><tr>
								<th>Choix de l'altitude de Mission</th><td align='left'><select name='Altitude' class='form-control' style='width: 300px'>";
							if($Plafond >=10000)
								echo "<option value='10000'>Haute altitude (10000m)</option>";
							if($Plafond >=9000)
								echo "<option value='9000'>Haute altitude (9000m)</option>";
							if($Plafond >=8000)
								echo "<option value='8000'>Haute altitude (8000m)</option>";
							if($Plafond >=7000)
								echo "<option value='7000'>Haute altitude (7000m)</option>";
							if($Plafond >=6000)
								echo "<option value='6000'>Haute altitude (6000m)</option>";
							if($Plafond >=5000)
								echo "<option value='5000'>Moyenne altitude (5000m)</option>";
							if($Plafond >=4000)
								echo "<option value='4000'>Moyenne altitude (4000m)</option>";
							echo "<option value='3000' selected>Moyenne altitude (3000m)</option></select></td></tr>
							<tr><td><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
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
include_once('./index.php');
?>