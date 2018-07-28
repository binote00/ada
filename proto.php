<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_avions.inc.php');
include_once('./jfv_txt.inc.php');

$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$Avion_P=GetData("Pilote","ID",$PlayerID,"Proto");
	$Credits=GetData("Pilote","ID",$PlayerID,"Credits");
	if($Credits >0 and $Avion_P >0)
	{
		$Credits -= 1;	
		$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
		$Equipage=GetData("Pilote","ID",$PlayerID,"Equipage");			
		//GetData Avion		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Robustesse,Type,Pays,Plafond,Autonomie,VitesseH,VitesseB,VitesseP,VitesseA,Visibilite,
		ArmePrincipale,Arme1_Nbr,Munitions1,ArmeSecondaire,Arme2_Nbr,Munitions2,Bombe,Bombe_Nbr,Avion_BombeT,Blindage,Volets,Moteur,Engine,Navigation,Radar,Radio,Reservoir,
		Verriere,Viseur,Camouflage,Baby,Engine_Nbr,Train,Helice,ID_ref FROM Avions_Persos WHERE ID='$Avion_P'");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Nom = $data['Nom'];
				$Robustesse = $data['Robustesse'];
				$Avion_Type = $data['Type'];
				$Pays = $data['Pays'];
				$Plafond = $data['Plafond'];
				$Autonomie = $data['Autonomie'];
				$VitesseH = $data['VitesseH'];
				$VitesseB = $data['VitesseB'];
				$VitesseP = $data['VitesseP'];
				$VitesseA = $data['VitesseA'];
				$Visibilite = $data['Visibilite'];
				$Arme1 = $data['ArmePrincipale'];
				$Arme2 = $data['ArmeSecondaire'];
				$Arme1_nbr = $data['Arme1_Nbr'];
				$Arme2_nbr = $data['Arme2_Nbr'];
				$Munitions1 = $data['Munitions1'];
				$Munitions2 = $data['Munitions2'];
				$Bombes = $data['Bombe'];
				$Bombes_nbr = $data['Bombe_Nbr'];
				$Avion_BombeT = $data['Avion_BombeT'];
				$Blindage = $data['Blindage'];
				$Engine_Nbr = $data['Engine_Nbr'];
				$Helice = $data['Helice'];
				$Train = $data['Train'];
				$Volets = $data['Volets'];
				$Moteur = $data['Moteur'];
				$MoteurP = $data['Engine'];
				$Navi = $data['Navigation'];
				$Radar = $data['Radar'];
				$Radio = $data['Radio'];
				$Reservoir = $data['Reservoir'];
				$Verriere = $data['Verriere'];
				$Viseur = $data['Viseur'];
				$Camouflage = $data['Camouflage'];
				$Baby_Actu = $data['Baby'];
				$ID_ref = $data['ID_ref'];
			}
			mysqli_free_result($result);
			unset($data);
			unset($result);
		}
		$Arme1_cal=substr(GetData("Armes", "ID", $Arme1, "Calibre"),0,3);
		$Arme1_nom=GetData("Armes","ID",$Arme1,"Nom");
		$Arme2_cal=substr(GetData("Armes", "ID", $Arme2, "Calibre"),0,3);
		$Arme2_nom=GetData("Armes","ID",$Arme2,"Nom");
		$filtre_masse = 25 * $Engine_Nbr;		
		/*if($Equipage)
			$Trait=GetData("Equipage","ID",$Equipage,"Trait");*/		
		$Array_Mod = GetAmeliorations($ID_ref);		
		$Arme8_fus = $Array_Mod[0];
		$Arme8_ailes = $Array_Mod[1];
		$Arme13 = $Array_Mod[2];
		$Arme20 = $Array_Mod[3];
		$Arme8_fus_nbr = $Array_Mod[4];
		$Arme13_fus_nbr = $Array_Mod[5];
		$Arme20_fus_nbr = $Array_Mod[6];
		$Arme8_ailes_nbr = $Array_Mod[7];
		$Arme13_ailes_nbr = $Array_Mod[8];
		$Arme20_ailes_nbr = $Array_Mod[9];
		$Arme8_ailes_max = $Array_Mod[10];
		$Arme13_ailes_max = $Array_Mod[11];
		$Bombe50_nbr = $Array_Mod[12];
		$Bombe125_nbr = $Array_Mod[13];
		$Bombe250_nbr = $Array_Mod[14];
		$Bombe500_nbr = $Array_Mod[15];
		$Camera_low = $Array_Mod[16];
		$Camera_high = $Array_Mod[17];
		$Baby = $Array_Mod[18];
		$Radar_On = $Array_Mod[19];
		$Torpilles = $Array_Mod[20];
		$Mines = $Array_Mod[21];
		$Fret_mun8 = $Array_Mod[22];
		$Fret_mun13 = $Array_Mod[23];
		$Fret_mun20 = $Array_Mod[24];
		$Fret_87 = $Array_Mod[25];
		$Fret_100 = $Array_Mod[26];
		$Fret_50 = $Array_Mod[27];
		$Fret_125 = $Array_Mod[28];
		$Fret_250 = $Array_Mod[29];
		$Fret_500 = $Array_Mod[30];
		$Fret_para = $Array_Mod[31];
		$Bombe1000_nbr = $Array_Mod[32];
		$Bombe2000_nbr = $Array_Mod[33];
		$Rockets = $Array_Mod[35];		
		$Arme8_fus_masse=GetData("Armes","ID",$Arme8_fus,"Masse");
		$Arme8_fus_nom=GetData("Armes","ID",$Arme8_fus,"Nom");
		$Arme8_fus_cal = substr(GetData("Armes", "ID", $Arme8_fus, "Calibre"),0,3);
		$Arme8_ailes_masse=GetData("Armes","ID",$Arme8_ailes,"Masse");
		$Arme8_ailes_nom=GetData("Armes","ID",$Arme8_ailes,"Nom");
		$Arme8_ailes_cal = substr(GetData("Armes", "ID", $Arme8_ailes, "Calibre"),0,3);
		if($Arme13 != 5)
		{
			$Arme13_masse=GetData("Armes","ID",$Arme13,"Masse");
			$Arme13_cal = substr(GetData("Armes", "ID", $Arme13, "Calibre"),0,3);
		}
		if($Arme20 != 5)
		{
			$Arme20_masse=GetData("Armes","ID",$Arme20,"Masse");
			$Arme20_cal = substr(GetData("Armes", "ID", $Arme20, "Calibre"),0,3);
		}
		if($Camera_low != 5)
			$Camera_low_masse=GetData("Armes","ID",$Camera_low,"Masse");
		if($Camera_high != 5)
			$Camera_high_masse=GetData("Armes","ID",$Camera_high,"Masse");
		$Arme13_nom=GetData("Armes","ID",$Arme13,"Nom");
		$Arme20_nom=GetData("Armes","ID",$Arme20,"Nom");
		if($Bombes_nbr == 0)
			$Bombes_txt = "Aucune";
		else
			$Bombes_txt = $Bombes_nbr."x ".$Bombes."kg";
		switch($Camouflage)
		{
			case 0:
				$Camouflage_txt = "Aucun";
			break;
			case 1:
				$Camouflage_txt = "Standard";
			break;
			case 2:
				$Camouflage_txt = "Tons bleus";
			break;
			case 3:
				$Camouflage_txt = "Tons gris";
			break;
			case 4:
				$Camouflage_txt = "Tons noirs";
			break;
			case 5:
				$Camouflage_txt = "Mixte Noir-Gris";
			break;
			case 6:
				$Camouflage_txt = "Mixte Brun-Gris";
			break;
			case 7:
				$Camouflage_txt = "Mixte Bleu-Gris";
			break;
			case 8:
				$Camouflage_txt = "Mixte Gris-Bleu";
			break;
			case 9:
				$Camouflage_txt = "Mixte Gris-Noir";
			break;
			case 10:
				$Camouflage_txt = "Mixte Vert-Gris";
			break;
			case 11:
				$Camouflage_txt = "Mixte Vert-Bleu";
			break;
			case 12:
				$Camouflage_txt = "Mixte Vert-Noir";
			break;
			case 13:
				$Camouflage_txt = "Mixte Vert-Brun-Gris";
			break;
			case 14:
				$Camouflage_txt = "Mixte Vert-Brun-Bleu";
			break;
			case 15:
				$Camouflage_txt = "Mixte Vert-Brun-Noir";
			break;
			case 16:
				$Camouflage_txt = "Mixte Vert-Gris-Gris";
			break;
			case 17:
				$Camouflage_txt = "Mixte Vert-Gris-Bleu";
			break;
			case 18:
				$Camouflage_txt = "Mixte Vert-Noir-Gris";
			break;
			case 19:
				$Camouflage_txt = "Mixte Vert-Noir-Bleu";
			break;
			case 20:
				$Camouflage_txt = "Mixte Sable-Gris";
			break;
			case 21:
				$Camouflage_txt = "Mixte Sable-Bleu";
			break;
			case 22:
				$Camouflage_txt = "Mixte Sable-Vert-Gris";
			break;
			case 23:
				$Camouflage_txt = "Mixte Sable-Vert-Bleu";
			break;
			case 24:
				$Camouflage_txt = "Mixte Sable-Brun-Gris";
			break;
			case 25:
				$Camouflage_txt = "Mixte Sable-Brun-Bleu";
			break;
		}		
		switch($Helice)
		{
			case 0:
				$Helice_txt = "Pas constant";
			break;
			case 1:
				$Helice_txt = "Pas variable manuel";
			break;
			case 2:
				$Helice_txt = "Pas variable automatique";
			break;
		}		
		$Injection=GetData("Moteur","ID",$MoteurP,"Injection");
		$Compresseur=GetData("Moteur","ID",$MoteurP,"Compresseur");
		$Moteur_Nom = '<b>'.GetData("Moteur","ID",$MoteurP,"Nom").'</b>';
		if($Compresseur == 3)
			$Compresseur = "Basse altitude";
		elseif($Compresseur == 2)
			$Compresseur = "Haute altitude";
		elseif($Compresseur == 1)
			$Compresseur = "Compresseur";
		else
			$Compresseur = "";
		$Moteur_Nom.=" (".$Compresseur.")";
		if($Injection)
			$Moteur_sup = "Injection";
		else
			$Moteur_sup = "Carburateur";
		switch($Moteur)
		{
			case 0:
				$Moteur_txt = "De série";
			break;
			case 1:
				$Moteur_txt = "Calibré";
			break;
			case 2:
				$Moteur_txt = "Haut indice d'octane";
			break;
			case 3:
				$Moteur_txt = "Compresseur suralimenté";
			break;
			case 4:
				$Moteur_txt = "Dispositif de surpuissance";
			break;
			case 5:
				$Moteur_txt = "Refroidissement amélioré";
			break;
			case 6:
				$Moteur_txt = $Moteur_sup." amélioré";
			break;
			case 7:
				$Moteur_txt = "Filtre anti-sable";
			break;
		}
		switch($Navi)
		{
			case 0:
				$Navi_txt = "De série";
			break;
			case 1:
				$Navi_txt = "Améliorée";
			break;
			case 2:
				$Navi_txt = "A la pointe";
			break;
			case 3:
				$Navi_txt = "Gyroscopique";
			break;
			default:
				$Navi_txt = "Inconnue";
			break;
		}
		switch($Radar)
		{
			case 0:
				$Radar_txt = "Aucun";
			break;
			case 10:
				$Radar_txt = "Radar décimétrique primitif";
			break;
			case 20:
				$Radar_txt = "Radar décimétrique amélioré";
			break;
			case 30:
				$Radar_txt = "Radar décimétrique évolué";
			break;
			case 60:
				$Radar_txt = "Radar centimétrique";
			break;
			case 80:
				$Radar_txt = "Radar centimétrique amélioré";
			break;
			case 100:
				$Radar_txt = "Radar centimétrique évolué";
			break;
			default:
				$Radar_txt = "Radar inconnu";
			break;
		}
		switch($Radio)
		{
			case 0:
				$Radio_txt = "De série";
			break;
			case 1:
				$Radio_txt = "Radio améliorée";
			break;
			case 2:
				$Radio_txt = "Radio longue portée";
			break;
			case 3:
				$Radio_txt = "Contre-mesures";
			break;
			default:
				$Radio_txt = "Radio inconnue";
			break;
		}
		switch($Reservoir)
		{
			case 0:
				$Reservoir_txt = "Standard";
			break;
			case 1:
				$Reservoir_txt = "Auto-obturant";
			break;
			case 2:
				$Reservoir_txt = "Grande capacité";
			break;
			case 3:
				$Reservoir_txt = "Très grande capacité";
			break;
		}
		if($Baby_Actu == 0)
			$Baby_txt = "Aucun";
		else
			$Baby_txt = "Réservoir externe (".$Baby_Actu." l)";
		switch($Verriere)
		{
			case 0:
				$Verriere_txt = "Standard";
			break;
			case 1:
				$Verriere_txt = "Bombée";
			break;
			case 2:
				$Verriere_txt = "Améliorée";
			break;
			case 3:
				$Verriere_txt = "Goutte d'eau";
			break;
		}
		switch($Viseur)
		{
			case 0:
				$Viseur_txt = "A réflexion standard";
			break;
			case 1:
				$Viseur_txt = "De chasse";
			break;
			case 2:
				$Viseur_txt = "D'attaque";
			break;
			case 3:
				$Viseur_txt = "De bombardement";
			break;
			case 4:
				$Viseur_txt = "Gyroscopique";
			break;
		}
		switch($Munitions1)
		{
			case 0:
				$Munitions1_txt = "Standard";
			break;
			case 1:
				$Munitions1_txt = "AP";
			break;
			case 2:
				$Munitions1_txt = "HE";
			break;
			case 3:
				$Munitions1_txt = "I";
			break;
			case 4:
				$Munitions1_txt = "APHE";
			break;
			case 5:
				$Munitions1_txt = "API";
			break;
		}
		switch($Munitions2)
		{
			case 0:
				$Munitions2_txt = "Standard";
			break;
			case 1:
				$Munitions2_txt = "AP";
			break;
			case 2:
				$Munitions2_txt = "HE";
			break;
			case 3:
				$Munitions2_txt = "I";
			break;
			case 4:
				$Munitions2_txt = "APHE";
			break;
			case 5:
				$Munitions2_txt = "API";
			break;
		}
		$Robustesse_Max=GetData("Avion", "ID", $ID_ref, "Robustesse") + $Cel_mod;
?>
<h1>Hangar de votre prototype</h1>
<h2><? echo $Nom; ?></h2>
<? echo Afficher_Image('images/avions/coupe'.$ID_ref.'.gif', 'images/avions/garage'.$ID_ref.'.jpg', $Nom, 75);?>
<form action="proto1.php" method="post">
<input type='hidden' name='avion' value="<? echo $Avion_P;?>">
<input type='hidden' name='ref' value="<? echo $ID_ref;?>">
<input type='hidden' name='robmax' value="<? echo $Robustesse_Max;?>">
	<table class='table'><thead><tr>
	<?
	if($Robustesse < $Robustesse_Max)
	{
			$Rob_diff = $Robustesse_Max - $Robustesse;
			$Rob_Credits = ceil($Rob_diff/100);
			if($Equipage)
			{
				$Meca=GetData("Equipage","ID",$Equipage,"Mecanique");
				$Mult = 1 - ($Meca/1000);
				$Rob_Credits = round($Rob_diff/100*$Mult)+1;
			}
	?>
		<th>Robustesse</th>
		<th>Réparer <a href='#' class='popup'><img src='images/help.png'><span>Répare la robustesse. Le coût est modifié par la compétence Mécanique de votre équipage de même que par le niveau de fourniture des pièces</span></a></th>
	<?}?>
		<th colspan="2">Supprimer votre prototype</th>
	</tr></thead>
	<tr>
	<?
	if($Robustesse < $Robustesse_Max)
	{
	?>
		<td><?echo $Robustesse." / ".$Robustesse_Max;?></td>
		<td>
			<select name="reparer" style="width: 150px">
					<option value='0'>Ne rien changer</option>
					<?if($Rob_Credits and $Credits > $Rob_Credits-1){?>
					<option value='<?echo $Rob_Credits?>'>+<?echo $Rob_diff;?> (<?echo $Rob_Credits;?> Crédits)</option>
					<?}if($Rob_diff > 99 and $Credits >0){?>
					<option value='1'>+100 (1 Crédit)</option>
					<?}if($Rob_diff > 199 and $Credits > 1){?>
					<option value='2'>+200 (2 Crédits)</option>
					<?}if($Rob_diff > 299 and $Credits > 2){?>
					<option value='3'>+300 (3 Crédits)</option>
					<?}if($Rob_diff > 399 and $Credits > 3){?>
					<option value='4'>+400 (4 Crédits)</option>
					<?}if($Rob_diff > 499 and $Credits > 4){?>
					<option value='5'>+500 (5 Crédits)</option>
					<?}?>
			</select>
		</td>
	<?}?>
		<td colspan="2">
			<Input type='Radio' name='init' value='0' checked>- Non
			<Input type='Radio' name='init' value='1'>- Oui
		</td>
	</tr></table>
	<table class='table table-striped'>
	<thead><tr><th colspan="4">Structure</th></tr></thead>
	<tr>
		<th>Verrière</th>
		<th></th>
		<th>Cockpit</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Verriere_txt;?></td>
		<td align="left">
			<select name="verriere" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<option value='2'>Bombée (Détection +5)</option>
				<?if($Date_Campagne > "1940-08-01"){?>
				<option value='3'>Améliorée (Détection +10)</option>
				<?}if($Date_Campagne > "1942-01-01"){?>
				<option value='4'>Goutte d'eau (Détection +30)</option>
				<?}?>
			</select>
		</td>
		<td><? echo $PareBrise_txt;?></td>
		<td align="left">
			<select name="cockpit" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>Blindage</th>
		<th></th>
		<th>Réservoir interne</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Blindage;?>mm</td>
		<td align="left">
			<select name="blindage" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Aucun</option>
				<option value='2'>8mm (200kg)</option>
				<option value='3'>13mm (325kg)</option>
				<option value='4'>16mm (400kg)</option>
				<option value='5'>19mm (475kg)</option>
				<option value='6'>22mm (550kg)</option>
				<option value='7'>25mm (625kg)</option>
			</select>
		</td>
		<td><? echo $Reservoir_txt;?></td>
		<td align="left">
			<select name="reservoir" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<option value='2'>Auto-obturant (Résistance partielle aux balles incendiaires)</option>
				<?if($Bombe500_nbr >0 or $Bombe250_nbr >0 or $Bombe125_nbr >0){?>
				<option value='3'>Grande capacité (200kg ; Autonomie +200)</option>
				<?}if($Bombe500_nbr >0){?>
				<option value='4'>Très grande capacité (500kg ; Autonomie +500)</option>
				<?}?>
			</select>
		</td>		
	</tr></table>
	<table class='table table-striped'>
	<thead><th colspan="4">Moteur <?echo $Moteur_Nom;?></th></tr></thead>
	<tr>
		<th>Moteur</th>
		<th></th>
		<th>Hélice</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Moteur_txt;?></td>
		<td align="left">
			<select name="moteur" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>De série</option>
				<option value='2'>Calibré (Puissance +5; Incidents -5%)</option>
				<option value='3'>Haut indice d'octane (Puissance +10; Décollage +5; Autonomie -100; Incidents -5%)</option>
				<?
				$Compresseur=GetData("Moteur","ID",$MoteurP,"Compresseur");
				if($Compresseur)
				{?>
				<option value='4'>Compresseur suralimenté (100kg; Puissance +50; Plafond +250; Autonomie -50; Incidents +5%)</option>
				<?}				
				$Boost=GetData("Moteur","ID",$MoteurP,"Boost");
				if($Boost)
				{?>
				<option value='5'>Système de surpuissance (200kg; Boost Puissance temporaire; Autonomie -200; Incidents +10%)</option>
				<?}?>
				<option value='6'>Refroidissement amélioré (100kg; Incidents -10%)</option>
				<option value='7'><?echo $Moteur_sup;?> amélioré (250kg; Puissance +100; Autonomie -100)</option>
				<option value='8'>Filtre anti-sable (<?echo $filtre_masse;?>kg; Incidents -50% dans le désert)</option>
			</select>
		</td>
		<td><? echo $Helice_txt;?></td>
		<td align="left">
			<select name="helice" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Pas constant</option>
				<option value='2'>Pas variable manuel (10kg ; Atterrissage/Décollage +5, Autonomie +50)</option>
				<option value='3'>Pas variable automatique (25kg ; Atterrissage/Décollage +10, Autonomie +100, Plafond +250)</option>
			</select>
		</td>
	</tr></table>
	<table class='table table-striped'>
	<thead><th colspan="4">Armement</th></tr></thead>
	<tr>
		<th>Arme Principale</th>
		<th></th>
		<th>Arme Secondaire</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Arme1_nbr." ".$Arme1_nom." (".$Arme1_cal."mm)";?></td>
		<td align="left">
			<select name="arme1" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Arme8_fus_nbr >0)
				{?>
				<option value='1'><? echo $Arme8_fus_nbr." ".$Arme8_fus_nom." (".$Arme8_fus_cal."mm)";?> (<?echo $Arme8_fus_masse*$Arme8_fus_nbr;?>kg)</option>
				<?}
				if($Arme13_fus_nbr >0)
				{?>
				<option value='2'><? echo $Arme13_fus_nbr." ".$Arme13_nom." (".$Arme13_cal."mm)";?> (<?echo $Arme13_masse*$Arme13_fus_nbr;?>kg)</option>
				<?}
				if($Arme20_fus_nbr >0)
				{?>
				<option value='3'><? echo $Arme20_fus_nbr." ".$Arme20_nom." (".$Arme20_cal."mm)";?> (<?echo $Arme20_masse*$Arme20_fus_nbr;?>kg)</option>
				<?}?>
				<option value='7'>Aucune</option>
			</select>
		</td>
		<td><? echo $Arme2_nbr." ".$Arme2_nom." (".$Arme2_cal."mm)";?></td>
		<td align="left">
			<select name="arme2" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Arme8_ailes_nbr >0)
				{?>
				<option value='1'><? echo $Arme8_ailes_nbr." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*$Arme8_ailes_nbr;?>kg)</option>
				<?}if($Arme8_ailes_max > $Arme8_ailes_nbr and $Arme8_ailes_max == 4)
				{?>
				<option value='2'><? echo "4 ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*4;?>kg)</option>
				<?}if($Arme8_ailes_max > $Arme8_ailes_nbr and $Arme8_ailes_max == 6)
				{?>
				<option value='3'><? echo "6 ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*6;?>kg)</option>
				<?}if($Arme8_ailes_max > $Arme8_ailes_nbr and $Arme8_ailes_max == 8)
				{?>
				<option value='16'><? echo "8 ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*8;?>kg)</option>
				<?}if($Arme8_ailes_max > $Arme8_ailes_nbr and $Arme8_ailes_max > 8)
				{?>
				<option value='10'><? echo $Arme8_ailes_max." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*$Arme8_ailes_max;?>kg)</option>
				<?}if($Arme13_ailes_nbr >0)
				{?>
				<option value='4'><? echo $Arme13_ailes_nbr." ".$Arme13_nom." (".$Arme13_cal."mm)";?> (<?echo $Arme13_masse*$Arme13_ailes_nbr;?>kg)</option>
				<?}if($Arme13_ailes_max > $Arme13_ailes_nbr)
				{?>
				<option value='5'><? echo $Arme13_ailes_max." ".$Arme13_nom." (".$Arme13_cal."mm)";?> (<?echo $Arme13_masse*$Arme13_ailes_max;?>kg)</option>
				<?}
				if($Arme20_ailes_nbr >0)
				{?>
				<option value='6'><? echo $Arme20_ailes_nbr." ".$Arme20_nom." (".$Arme20_cal."mm)";?> (<?echo $Arme20_masse*$Arme20_ailes_nbr;?>kg)</option>
				<?}
				if($Camera_low != 5)
				{?>
				<option value='8'>1 Caméra portative (<?echo $Camera_low_masse;?>kg ; Basse altitude uniquement)</option>
				<?}
				if($Camera_high != 5)
				{?>
				<option value='9'>1 Caméra fixe (<?echo $Camera_high_masse;?>kg)</option>
				<?}?>
				<option value='7'>Aucune</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>Munitions Arme Principale</th>
		<th></th>
		<th>Munitions Arme Secondaire</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Munitions1_txt;?></td>
		<td align="left">
			<select name="muns1" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<option value='2'>AP (Perforant, chance d'ignorer un blindage lourd à courte portée)</option>
				<option value='3'>HE (Explosif, dégâts supplémentaires importants contre cibles non blindées)</option>
				<option value='4'>Incendiaire (chance de dégâts supplémentaires contre cibles non blindées)</option>
				<option value='5'>APHE (Chance d'ignorer un blindage léger. Dégâts supplémentaires importants si blindage perforé)</option>
				<option value='6'>API (Chance d'ignorer un blindage léger. Dégâts supplémentaires dans la durée si blindage perforé)</option>
			</select>
		</td>
		<td><? echo $Munitions2_txt;?></td>
		<td align="left">
			<select name="muns2" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<option value='2'>AP (Perforant, chance d'ignorer un blindage lourd à courte portée)</option>
				<option value='3'>HE (Explosif, dégâts supplémentaires importants contre cibles non blindées)</option>
				<option value='4'>Incendiaire (chance de dégâts supplémentaires contre cibles non blindées)</option>
				<option value='5'>APHE (Chance d'ignorer un blindage léger. Dégâts supplémentaires importants si blindage perforé)</option>
				<option value='6'>API (Chance d'ignorer un blindage léger. Dégâts supplémentaires dans la durée si blindage perforé)</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>Bombes</th>
		<th></th>
		<th>Type de Bombes</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Bombes_txt;?></td>
		<td align="left">
			<select name="bombes" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Aucune</option>
				<?
				if($Bombe50_nbr >0)
				{
					for($ib = 1; $ib <= $Bombe50_nbr; $ib++)
					{
						$ibn = "50_".$ib;
						$bombe_kg = $ib*50;
						$bombes_combo50_txt = $bombes_combo50_txt."<option value='".$ibn."'>".$ib." bombes de 50kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo50_txt;
				}
				if($Bombe125_nbr >0)
				{					
					for($ib = 1; $ib <= $Bombe125_nbr; $ib++)
					{
						$ibn = "125_".$ib;
						$bombe_kg = $ib*125;
						$bombes_combo125_txt = $bombes_combo125_txt."<option value='".$ibn."'>".$ib." bombes de 125kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo125_txt;
				}
				if($Bombe250_nbr >0)
				{
					for($ib = 1; $ib <= $Bombe250_nbr; $ib++)
					{
						$ibn = "250_".$ib;
						$bombe_kg = $ib*250;
						$bombes_combo250_txt = $bombes_combo250_txt."<option value='".$ibn."'>".$ib." bombes de 250kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo250_txt;
				}
				if($Bombe500_nbr >0)
				{
					for($ib = 1; $ib <= $Bombe500_nbr; $ib++)
					{
						$ibn = "500_".$ib;
						$bombe_kg = $ib*500;
						$bombes_combo500_txt = $bombes_combo500_txt."<option value='".$ibn."'>".$ib." bombes de 500kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo500_txt;
				}
				if($Bombe1000_nbr >0)
				{
					for($ib = 1; $ib <= $Bombe1000_nbr; $ib++)
					{
						$ibn = "1000_".$ib;
						$bombe_kg = $ib*1000;
						$bombes_combo1000_txt = $bombes_combo1000_txt."<option value='".$ibn."'>".$ib." bombes de 1000kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo1000_txt;
				}
				if($Torpilles >0)
				{
					for($ib = 1; $ib <= $Torpilles; $ib++)
					{
						$ibn = "800_".$ib;
						$bombe_kg = $ib*800;
						$bombes_combo800_txt = $bombes_combo800_txt."<option value='".$ibn."'>".$ib." torpille(s)(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo800_txt;
				}
				if($Mines >0)
				{
					for($ib = 1; $ib <= $Mines; $ib++)
					{
						$ibn = "300_".$ib;
						$bombe_kg = $ib*300;
						$bombes_combo300_txt = $bombes_combo400_txt."<option value='".$ibn."'>".$ib." mine(s)(".$bombe_kg."kg)</option>";
						$ibn = "400_".$ib;
						$bombe_kg = $ib*400;
						$bombes_combo400_txt = $bombes_combo400_txt."<option value='".$ibn."'>".$ib." mine(s)(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo300_txt;
					echo $bombes_combo400_txt;
				}
				if($Rockets >0)
				{
					for($ib = 1; $ib <= $Rockets; $ib++)
					{
						$ibn = "80_".$ib;
						$bombe_kg = $ib*80;
						$bombes_combo80_txt.="<option value='".$ibn."'>".$ib." rocket(s)(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo80_txt;
				}
				/*if($Camera_low !=5)
				{?>
				<option value='7'>1 Caméra portative (<?echo $Camera_low_masse;?>kg ; Basse altitude uniquement)</option>
				<?}
				if($Camera_high !=5)
				{?>
				<option value='8'>1 Caméra fixe (<?echo $Camera_high_masse;?>kg)</option>
				<?}*/
				if($Bombe125_nbr >0 or $Bombe250_nbr >0 or $Bombe500_nbr >0){?>	
				<option value='30_10'>10 fusées éclairantes (300 kg)</option>
				<?}
				if($Avion_Type ==6){
					if($Fret_50 >0)
					{?>
					<option value='12'><? echo $Fret_50." bombes de 50kg (Fret)";?> (<?echo $Fret_50*50;?>kg)</option>
					<?}
					if($Fret_125 >0)
					{?>
					<option value='13'><? echo $Fret_125." bombes de 125kg (Fret)";?> (<?echo $Fret_125*125;?>kg)</option>
					<?}
					if($Fret_250 >0)
					{?>
					<option value='14'><? echo $Fret_250." bombes de 250kg (Fret)";?> (<?echo $Fret_250*250;?>kg)</option>
					<?}
					if($Fret_500 >0)
					{?>
					<option value='15'><? echo $Fret_500." bombes de 500kg (Fret)";?> (<?echo $Fret_500*500;?>kg)</option>
					<?}
					if($Fret_mun8 >0)
					{?>
					<option value='16'><? echo $Fret_mun8*50000; echo " munitions de 8mm";?> (<?echo $Fret_mun8*1000;?>kg)</option>
					<?}
					if($Fret_mun13 >0)
					{?>
					<option value='17'><? echo $Fret_mun13*15000; echo " munitions de 13mm";?> (<?echo $Fret_mun13*1000;?>kg)</option>
					<?}
					if($Fret_mun20 >0)
					{?>
					<option value='18'><? echo $Fret_mun20*5000; echo " munitions de 20mm";?> (<?echo $Fret_mun20*1000;?>kg)</option>
					<?}
					if($Fret_87 >0)
					{?>
					<option value='19'><? echo $Fret_87*1200; echo " litres d'octane 87";?> (<?echo $Fret_87*1000;?>kg)</option>
					<?}
					if($Fret_100 >0)
					{?>
					<option value='20'><? echo $Fret_100*1100; echo " litres d'octane 100";?> (<?echo $Fret_100*1000;?>kg)</option>
					<?}
					if($Fret_para >0)
					{?>
					<option value='21'><? echo $Fret_para." parachutistes";?> (<?echo $Fret_para*100;?>kg)</option>
					<?}
				}?>
			</select>
		</td>
		<td><? echo GetBombeT($Avion_BombeT);?></td>
		<td align="left">
			<select name="bombe_type" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<option value='2'>Incendiaire (Efficace contre les petits bâtiments et les véhicules non blindés)</option>
				<option value='3'>Anti-personnel(Efficace contre les soldats et les chevaux)</option>
				<option value='4'>Anti-tank (Efficace contre les véhicules)</option>
				<option value='5'>Anti-navire (Efficace contre les navires)</option>
				<option value='6'>Anti-bâtiment (Efficace contre les bâtiments)</option>
				<option value='7'>Anti-piste (Efficace contre les pistes)</option>
			</select>
		</td>
	</tr></table>
	<table class='table table-striped'>
	<thead><th colspan="4">Equipements</th></tr></thead>
	<tr>
		<th>Radio</th>
		<th></th>
		<th>Navigation</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Radio_txt;?></td>
		<td align="left">
			<select name="radioo" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>De série</option>
				<option value='2'>Améliorée (100kg)</option>
				<option value='3'>Longue portée (200kg)</option>
				<?if($Date_Campagne > "1942-01-01"){?>
				<option value='4'>Contre-mesures (300kg)</option>
				<?}?>
			</select>
		</td>
		<td><? echo $Navi_txt;?></td>
		<td align="left">
			<select name="navi" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>De série</option>
				<option value='2'>Amélioré (200kg)</option>
				<?if($Date_Campagne > "1944-01-01"){?>
				<option value='4'>Gyroscopique</option>
				<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Camouflage</th>
		<th></th>
		<th>Viseur</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Camouflage_txt;?></td>
		<td align="left">
			<select name="camouflage" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<option value='2'>Tons bleus</option>
				<option value='3'>Tons gris</option>
				<option value='4'>Tons noirs</option>
				<option value='5'>Mixte Noir-Gris</option>
				<option value='6'>Mixte Brun-Gris</option>
				<option value='7'>Mixte Bleu-Gris</option>
				<option value='8'>Mixte Gris-Bleu</option>
				<option value='9'>Mixte Gris-Noir</option>
				<option value='10'>Mixte Vert-Gris</option>
				<option value='11'>Mixte Vert-Bleu</option>
				<option value='12'>Mixte Vert-Noir</option>
				<option value='13'>Mixte Vert-Brun-Gris</option>
				<option value='14'>Mixte Vert-Brun-Bleu</option>
				<option value='15'>Mixte Vert-Brun-Noir</option>
				<option value='16'>Mixte Vert-Gris-Gris</option>
				<option value='17'>Mixte Vert-Gris-Bleu</option>
				<option value='18'>Mixte Vert-Noir-Gris</option>
				<option value='19'>Mixte Vert-Noir-Bleu</option>
				<option value='20'>Mixte Sable-Gris</option>
				<option value='21'>Mixte Sable-Bleu</option>
				<option value='22'>Mixte Sable-Vert-Gris</option>
				<option value='23'>Mixte Sable-Vert-Bleu</option>
				<option value='24'>Mixte Sable-Brun-Gris</option>
				<option value='25'>Mixte Sable-Brun-Bleu</option>
			</select>
		</td>
		<td><? echo $Viseur_txt;?></td>
		<td align="left">
			<select name="viseur" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>A réflexion standard</option>
				<option value='2'>De chasse (Tir air-air +10, Bombardement -10)</option>
				<option value='3'>D'attaque (Tir air-sol +10, Tir air-air -10)</option>
				<option value='4'>De bombardement (Bombardement +10, Tir air-air -10)</option>
				<?if($Date_Campagne > "1944-01-01"){?>
				<option value='5'>Gyroscopique (Tir +20, Bombardement +20)</option>
				<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Radar</th>
		<th></th>
		<th>Réservoir largable</th>
		<th></th>
	</tr>
	<tr>
		<td><? echo $Radar_txt;?></td>
		<td align="left">
			<select name="radar" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Aucun</option>
				<?if($Radar_On){?>
				<option value='2'>Radar Décimétrique primitif (50kg)</option>
				<?if($Date_Campagne > "1942-01-01"){?>
				<option value='3'>Radar Décimétrique amélioré (250kg)</option>
				<?}if($Date_Campagne > "1944-01-01"){?>
				<option value='4'>Radar Décimétrique évolué (500kg)</option>
				<?}}?>
			</select>
		</td>
		<td><? echo $Baby_txt;?></td>
		<td align="left">
			<select name="reservoirl" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Aucun</option>
				<?if($Baby >0){?>
				<option value='<?echo $Baby;?>'>Réservoir externe (<?$Baby_kg = ceil($Baby/2); echo $Baby_kg;?>kg; Autonomie +<?echo $Baby;?>km)</option>
				<?}?>
			</select>
		</td>		
	</tr>
	</table>
	<input type="submit" value="Valider" class='btn btn-default' onclick='this.disabled=true;this.form.submit();'> <img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'></form>
<?
	}
	else
		header("Location: ./tsss.php");
}
else
	header("Location: ./tsss.php");
?>