<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID = $_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country = $_SESSION['country'];
	$Regiment = Insec($_POST['Reg']);
	$Base = Insec($_POST['Base']);
	$CT = Insec($_POST['CT']);
	$Placement = Insec($_POST['Place']);
	if($Base >0 and $Regiment)
	{
		$Faction=GetData("Pays","ID",$country,"Faction");
		$choix1='';
		$choix2='';
		//Depot
		$query="SELECT DISTINCT ID,Nom,Longitude,Latitude,ValeurStrat,Zone,Plage,Flag,Port_Ori,Port,Stock_Essence_87,Stock_Essence_100,Stock_Essence_1,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,
		Stock_Munitions_40,Stock_Munitions_50,Stock_Munitions_60,Stock_Munitions_75,Stock_Munitions_90,Stock_Munitions_105,Stock_Munitions_125,Stock_Munitions_150,
		Stock_Bombes_30,Stock_Bombes_50,Stock_Bombes_80,Stock_Bombes_125,Stock_Bombes_250,Stock_Bombes_300,Stock_Bombes_400,Stock_Bombes_500,Stock_Bombes_800,Stock_Bombes_1000,Stock_Bombes_2000
		FROM Lieu WHERE ID='$Base'";
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$Depot_Off=mysqli_result(mysqli_query($con,"SELECT Depot FROM Officier WHERE ID='$OfficierID'"),0);
		$result_reg=mysqli_query($con,"SELECT Fret,Fret_Qty,Vehicule_ID,Vehicule_Nbr FROM Regiment WHERE ID='$Regiment'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : g_decharge-reg');
		$result=mysqli_query($con, $query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : g_decharge-base');
		mysqli_close($con);			
		if($result_reg)
		{
			while($datar=mysqli_fetch_array($result_reg,MYSQLI_ASSOC))
			{
				$Fret = $datar['Fret'];
				$Fret_Qty = $datar['Fret_Qty'];
				$Vehicule_ID = $datar['Vehicule_ID'];
				$Vehicule_Nbr = $datar['Vehicule_Nbr'];
			}
			mysqli_free_result($result_reg);
			unset($datar);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{			
				$Faction_Flag=GetData("Pays","ID",$data['Flag'],"Faction");
				$Port = $data['Port'];
				$Port_Ori = $data['Port_Ori'];
				$Plage = $data['Plage'];
				$Zone = $data['Zone'];
				if($data['ValeurStrat'] >3 and $Faction_Flag ==$Faction and $Zone !=6 and $Placement !=8)
				{
					$depot_info="<div id='col_droite'><table class='table table-striped'>
						<thead><tr><th colspan='2'>Dépôt de ".$data['Nom']."</th></tr></thead>
						<tr><th>Essence 87 Octane</th><td >".$data['Stock_Essence_87']."</td></tr>
						<tr><th>Essence 100 Octane</th><td >".$data['Stock_Essence_100']."</td></tr>
						<tr><th>Essence 130 Octane</th><td >0</td></tr>
						<tr><th>Diesel</th><td >".$data['Stock_Essence_1']."</td></tr>
						<tr><th>Munitions 8mm</th><td >".$data['Stock_Munitions_8']."</td></tr>
						<tr><th>Munitions 13mm</th><td >".$data['Stock_Munitions_13']."</td></tr>
						<tr><th>Munitions 20mm</th><td >".$data['Stock_Munitions_20']."</td></tr>
						<tr><th>Munitions 30mm</th><td >".$data['Stock_Munitions_30']."</td></tr>
						<tr><th>Munitions 40mm</th><td >".$data['Stock_Munitions_40']."</td></tr>
						<tr><th>Munitions 50mm</th><td >".$data['Stock_Munitions_50']."</td></tr>
						<tr><th>Munitions 60mm</th><td >".$data['Stock_Munitions_60']."</td></tr>
						<tr><th>Munitions 75mm</th><td >".$data['Stock_Munitions_75']."</td></tr>
						<tr><th>Munitions 90mm</th><td >".$data['Stock_Munitions_90']."</td></tr>
						<tr><th>Munitions 105mm</th><td >".$data['Stock_Munitions_105']."</td></tr>
						<tr><th>Munitions 125mm</th><td >".$data['Stock_Munitions_125']."</td></tr>
						<tr><th>Munitions 150mm</th><td >".$data['Stock_Munitions_150']."</td></tr>
						<tr><th>Bombes 50kg</th><td >".$data['Stock_Bombes_50']."</td></tr>
						<tr><th>Bombes 125kg</th><td >".$data['Stock_Bombes_125']."</td></tr>
						<tr><th>Bombes 250kg</th><td >".$data['Stock_Bombes_250']."</td></tr>
						<tr><th>Bombes 500kg</th><td >".$data['Stock_Bombes_500']."</td></tr>
						<tr><th>Bombes 1000kg</th><td >".$data['Stock_Bombes_1000']."</td></tr>
						<tr><th>Bombes 2000kg</th><td >".$data['Stock_Bombes_2000']."</td></tr>
						<tr><th>Charges de Profondeur</th><td >".$data['Stock_Bombes_300']."</td></tr>
						<tr><th>Mines</th><td >".$data['Stock_Bombes_400']."</td></tr>
						<tr><th>Torpilles</th><td >".$data['Stock_Bombes_800']."</td></tr>
						<tr><th>Fusées éclairantes</th><td >".$data['Stock_Bombes_30']."</td></tr>
						<tr><th>Rockets</th><td >".$data['Stock_Bombes_80']."</td></tr>
						</table></div>";
					if($Depot_Off ==$Base)
						$depot="<br>Vous ne pouvez pas décharger dans le dépôt d'où vient votre dernière cargaison<br>";
					else
						$depot="<br><Input type='Radio' name='Action' value='".$data['ID']."_depot'>- Dépôt de ".$data['Nom']."<br>";
				}
				elseif($data['ValeurStrat'] >3 and $Zone !=6)
				{
					if($Placement ==8)
						$depot_info="<div id='col_droite'>Vous devez vous trouver au port et non au large pour pouvoir utiliser le dépôt de ".$data['Nom']."</div>";
					else
						$depot_info="<div id='col_droite'>Votre faction doit contrôler et avoir revendiqué le lieu pour pouvoir utiliser le dépôt de ".$data['Nom']."</div>";
				}
			}
			mysqli_free_result($result);
			unset($data);
		}
		$mobile=GetData("Cible","ID",$Vehicule_ID,"mobile");		
		if($Fret !=888 and $Fret !=200)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT ID,Vehicule_ID FROM Regiment WHERE Lieu_ID='$Base' AND Placement='$Placement' AND Pays='$country' AND Vehicule_Nbr >0") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : g_decharge-regs');
			$result2=mysqli_query($con,"SELECT DISTINCT u.ID,u.Nom FROM Unit as u,Pays as p WHERE u.Base='$Base' AND u.Pays=p.Pays_ID AND p.Faction='$Faction' AND u.Etat=1") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : g_decharge-units');
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					if($mobile ==5)
					{
						if($Zone ==6)
							$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."_'>- ".$data['ID']."e Flottille <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'><br>";
						elseif($Faction_Flag == $Faction and (($Port >0 and $Placement ==4) or ($Plage >0 and $Placement ==8)))
							$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."_'>- ".$data['ID']."e Flottille <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'><br>";
					}
					else
						$choix1.="<Input type='Radio' name='Action' value='".$data['ID']."_'>- ".$data['ID']."e Compagnie <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'><br>";
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					if($mobile ==5)
					{
						if($Zone ==6 or ($Zone !=6 and $Faction_Flag == $Faction and (($Port >0 and $Placement ==4) or ($Plage >0 and $Placement ==8))))
							$choix2.="<Input type='Radio' name='Action' value='".$data['ID']."'>- ".$data['Nom']." ".Afficher_Icone($data['ID'],$country)."<br>";
					}
					else
						$choix2.="<Input type='Radio' name='Action' value='".$data['ID']."'>- ".$data['Nom']." ".Afficher_Icone($data['ID'],$country)."<br>";
				}
				mysqli_free_result($result2);
				unset($data);
			}
		}
		//output
		$options='';
		$muns='';
		$titre='Ravitaillement';
		$Livraison_txt='Livraison';
		$choix="<th>Déchargement vers</th></tr><tr><td align='left'><div style='overflow:auto; width: 250px;'>".$choix1.$choix2."</div>";
		if($Fret ==200 and ($Plage or $Port))
		{
			$titre='Débarquement';
			$Livraison_txt='Débarquer';
			$depot_info='';
			$choix="<input type='hidden' name='Qty' value='1'><tr><td><Input type='Radio' name='Action' value='".$Fret_Qty."_tr'>- Débarquer les troupes.<br>";
			$img="<img src='images/debarquer.jpg'>";
		}
		elseif($Fret_Qty >0)
		{
			$qty="<tr><td align='left'><input type='range' name='Quantite' value='".$Fret_Qty."' max='".$Fret_Qty."' min='0' step='1' onchange=\"updateTextInput(this.value,'QtyDech');\">
			<input type='text' name='Qty' id='QtyDech' value='".$Fret_Qty."' size='6' readonly> Quantité</td></tr>";
		}
		if($Fret ==888)
		{
			$depot="";
			if($country ==2 and ($Base ==586 or $Base ==758 or $Base ==815))
				$Lend=true;
			elseif($country ==7 and ($Base ==96 or $Base ==262 or $Base ==264 or $Base ==265 or $Base ==267 or $Base ==269 or $Base ==270 or $Base ==345 or $Base ==586 or $Base ==731 or $Base ==758 or $Base ==815))
				$Lend=true;
			if($Lend)
			{
				$titre='Déchargement';
				$depot_info='';
				$choix="<tr><td><Input type='Radio' name='Action' value='8888'>- Débarquer les équipements Lend-Lease.<br><input type='hidden' name='Qty' value='50000'>";
				$img="<img src='images/lend_lease.jpg'>";
			}
			else
				$depot_info="Ce port n'est pas un port valide de destination Lend-Lease!";
		}
		elseif($Fret <300 and $Fret !=200)
		{
			$muns.="<tr><td align='left'>Type <select name='muns' class='form-control' style='width: 200px'>
					<option value='0'>Standard</option>
					<option value='1'>AP (Perforant courte portée)</option>
					<option value='2'>HE (Explosif)</option>
					<option value='4'>APHE (Perforant explosif)</option>
					<option value='6'>APCR (Perforant moyenne portée)</option>";
			if($Fret >19 and $Date_Campagne >'1944-01-01')
				$muns.="<option value='7'>APDS (Perforant longue portée, uniquement AT)</option>";
			if($Fret >69 and $Date_Campagne >'1941-01-01')
				$muns.="<option value='8'>HEAT (Charge creuse courte portée, uniquement soutien)</option>";
			$muns.='</select><td></tr>';
		}
		if(!$img)$img="<img src='images/logistics.jpg'>";
		$mes="<div id='col_gauche'><form action='index.php?view=ground_decharge1' method='post'>
			<input type='hidden' name='Reg' value='".$Regiment."'>
			<input type='hidden' name='CT' value='".$CT."'>
			<table class='table'>
				<thead><tr><th colspan='2'>".$Livraison_txt."</th></tr></thead>
				".$qty.$muns.$choix.$depot."
					<br><Input type='Radio' name='Action' value='0' checked>- Annuler.<br>
				</td></tr>
			</table>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div>".$depot_info;
		include_once('./default.php');
	}
	else
		echo 'Tsss';
}