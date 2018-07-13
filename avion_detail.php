<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_avions.inc.php');
	$Encodage=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");	
	if($Encodage >0)
	{
		$ID_ref=Insec($_GET['avion']);
		$country=$_SESSION['country'];
		$con=dbconnecti();
		$ID_ref=mysqli_real_escape_string($con,$ID_ref);
		$result2=mysqli_query($con,"SELECT Nom,Type,Engagement,Puissance,Masse,ArmePrincipale,ArmeSecondaire,Arme1_Nbr,Arme2_Nbr,Arme1_Mun,Arme2_Mun,Autonomie,Reservoir,Bombe,Bombe_Nbr FROM Avion WHERE ID='$ID_ref'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Avion_nom=$data['Nom'];
				$Type_Avion=$data['Type'];
				$Date_A=$data['Engagement'];
				$Autonomie=$data['Autonomie'];
				$Arme1=$data['ArmePrincipale'];
				$Arme2=$data['ArmeSecondaire'];
				$Arme1_nbr=$data['Arme1_Nbr'];
				$Arme2_nbr=$data['Arme2_Nbr'];
				$Arme1_chargeur=$data['Arme1_Mun'];
				$Arme2_chargeur=$data['Arme2_Mun'];
				$Reservoir=$data['Reservoir'];
				$Autonomie_IA=floor(($data['Autonomie']/2)-200);
				if($Autonomie_IA <50)$Autonomie_IA=50;
				if($Type_Avion ==2 or $Type_Avion ==7 or $Type_Avion ==10 or $Type_Avion ==11)
				{
					$Massef_s=$data['Masse']+($data['Bombe']*$data['Bombe_Nbr']);
					$Massef_t=$data['Masse']+$data['Bombe'];
					$Poids_Puiss_ori=$data['Masse']/$data['Puissance'];
					$Poids_Puiss_s=$Massef_s/$data['Puissance'];
					$Poids_Puiss_t=$Massef_t/$data['Puissance'];
					$Autonomie_s=round($data['Autonomie']-(($Poids_Puiss_s-$Poids_Puiss_ori)*($Massef_s/10)));
					$Autonomie_t=round(($data['Autonomie']/2)-(($Poids_Puiss_t-$Poids_Puiss_ori)*($Massef_t/10)));
				}
			}
			mysqli_free_result($result2);
			unset($data);
		}
		if($Reservoir)
			$Reservoir="(auto-obturant)";
		else
			$Reservoir="";
		$Array_Mod=GetAmeliorations($ID_ref);			
		$Arme8_fus=$Array_Mod[0];
		$Arme8_ailes=$Array_Mod[1];
		$Arme13=$Array_Mod[2];
		$Arme20=$Array_Mod[3];
		$Arme8_fus_nbr=$Array_Mod[4];
		$Arme13_fus_nbr=$Array_Mod[5];
		$Arme20_fus_nbr=$Array_Mod[6];
		$Arme8_ailes_nbr=$Array_Mod[7];
		$Arme13_ailes_nbr=$Array_Mod[8];
		$Arme20_ailes_nbr=$Array_Mod[9];
		$Arme8_ailes_max=$Array_Mod[10];
		$Arme13_ailes_max=$Array_Mod[11];
		$Bombe50_nbr=$Array_Mod[12];
		$Bombe125_nbr=$Array_Mod[13];
		$Bombe250_nbr=$Array_Mod[14];
		$Bombe500_nbr=$Array_Mod[15];
		$Bombe1000_nbr=$Array_Mod[32];
		$Bombe2000_nbr=$Array_Mod[33];
		$Camera_low=$Array_Mod[16];
		$Camera_high=$Array_Mod[17];
		$Baby=$Array_Mod[18];
		$Radar_On=$Array_Mod[19];
		$Torpilles=$Array_Mod[20];
		$Mines=$Array_Mod[21];
		$Rockets=$Array_Mod[35];
		$Arme1_cal=round(GetData("Armes","ID",$Arme1,"Calibre"));
		if(!$Arme1_chargeur)
			$Arme1_chargeur=GetData("Armes","ID",$Arme1,"Munitions")*$Arme1_nbr;
		$Arme1_nom=GetData("Armes","ID",$Arme1,"Nom");
		$Arme2_cal=round(GetData("Armes","ID",$Arme2,"Calibre"));
		if(!$Arme2_chargeur)
			$Arme2_chargeur=GetData("Armes","ID",$Arme2,"Munitions")*$Arme2_nbr;
		$Arme2_nom=GetData("Armes","ID",$Arme2,"Nom");			
		$Arme8_fus_masse=GetData("Armes","ID",$Arme8_fus,"Masse");
		$Arme8_fus_nom=GetData("Armes","ID",$Arme8_fus,"Nom");
		$Arme8_fus_cal=round(GetData("Armes","ID",$Arme8_fus,"Calibre"));
		$Arme8_fus_chargeur=GetData("Armes","ID",$Arme8_fus,"Munitions");
		$Arme8_ailes_masse=GetData("Armes","ID",$Arme8_ailes,"Masse");
		$Arme8_ailes_nom=GetData("Armes","ID",$Arme8_ailes,"Nom");
		$Arme8_ailes_cal=round(GetData("Armes","ID",$Arme8_ailes,"Calibre"));
		$Arme8_ailes_chargeur=GetData("Armes","ID",$Arme8_ailes,"Munitions");
		if($Arme13 != 5)
		{
			$Arme13_masse=GetData("Armes","ID",$Arme13,"Masse");
			$Arme13_cal=round(GetData("Armes","ID",$Arme13,"Calibre"));
			$Arme13_chargeur=GetData("Armes","ID",$Arme13,"Munitions");
		}
		if($Arme20 !=5)
		{
			$Arme20_masse=GetData("Armes","ID",$Arme20,"Masse");
			$Arme20_cal=round(GetData("Armes","ID",$Arme20,"Calibre"));
			$Arme20_chargeur=GetData("Armes","ID",$Arme20,"Munitions");
		}
		if($Camera_high !=5)
			$Camera_high_masse=GetData("Armes","ID",$Camera_high,"Masse");
		$Arme13_nom=GetData("Armes","ID",$Arme13,"Nom");
		$Arme20_nom=GetData("Armes","ID",$Arme20,"Nom");
		$mes="<div align='center'><h2>".$Avion_nom."</h2></div>";
		$garage.="<table border='1' cellspacing='1' cellpadding='1' bgcolor='#ECDDC1'>
				<tr><td>Date de mise en service : ".$Date_A."</td><td>Réservoir principal ".$Autonomie." litres ".$Reservoir."</td></tr>
				<tr bgcolor='lightyellow'><th>Arme Principale (choix)</th><th>Arme Secondaire (choix)</th></tr>";
		$garage.= "<tr><td>".$Arme1_nbr." ".$Arme1_nom." (".$Arme1_cal."mm / ".$Arme1_chargeur." coups) - de série</td><td>".$Arme2_nbr." ".$Arme2_nom." (".$Arme2_cal."mm / ".$Arme2_chargeur." coups) - de série</td></tr>";
		if($Arme8_fus_nbr >0)
			$garage.= "<tr><td>".$Arme8_fus_nbr." ".$Arme8_fus_nom." (".$Arme8_fus_cal."mm / ".$Arme8_fus_chargeur." coups par arme)</td>";
		if($Arme8_ailes_nbr >0)
			$garage.= "<td>".$Arme8_ailes_nbr." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm / ".$Arme8_ailes_chargeur." coups par arme)</td></tr>";
		if($Arme13_fus_nbr >0)
			$garage.= "<tr><td>".$Arme13_fus_nbr." ".$Arme13_nom." (".$Arme13_cal."mm / ".$Arme13_chargeur." coups par arme)</td>";
		if($Arme8_ailes_max >3)
		{
			$Arme8_ailes_nbr=$Arme8_ailes_nbr*2;
			$garage.= "<td>".$Arme8_ailes_nbr." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm / ".$Arme8_ailes_chargeur." coups par arme)</td></tr>";
		}
		if($Arme20_fus_nbr >0)
			$garage.= "<tr><td>".$Arme20_fus_nbr." ".$Arme20_nom." (".$Arme20_cal."mm / ".$Arme20_chargeur." coups par arme)</td>";
		if($Arme8_ailes_max >5)
			$garage.= "<td>".$Arme8_ailes_max." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm / ".$Arme8_ailes_chargeur." coups par arme)</td></tr>";
		if($Camera_low !=5)
			$garage.= "<tr><td>1 Caméra portative (Basse altitude uniquement)</td>";
		if($Arme13_ailes_max >0)
			$garage.= "<td>".$Arme13_ailes_nbr." ".$Arme13_nom." (".$Arme13_cal."mm / ".$Arme13_chargeur." coups par arme)</td></tr>";
		if($Camera_high !=5)
			$garage.= "<tr><td>1 Caméra fixe (".$Camera_high_masse."kg)</td>";
		if($Arme13_ailes_max >3)
			$garage.= "<td>".$Arme13_ailes_max." ".$Arme13_nom." (".$Arme13_cal."mm / ".$Arme13_chargeur." coups par arme)</td></tr>";			
		if($Arme20_ailes_nbr >0)
			$garage.= "<td>".$Arme20_ailes_nbr." ".$Arme20_nom." (".$Arme20_cal."mm / ".$Arme20_chargeur." coups par arme)</td></tr>";
		if($Baby)
		{
			$Autonomie_l=floor($Autonomie_IA+($Array_Mod[18]/2));
			$garage.="<tr><td bgcolor='tan' colspan='2'>Réservoir largable</td></tr><tr><td>".$Baby." litres</td><td>Autonomie avec réservoir ".$Autonomie_l."km</td></tr>";
		}
		if($Autonomie_IA)
		{
			$garage.="<tr><td bgcolor='tan' colspan='2'>Autonomie</td></tr><tr><td>".$Autonomie_IA."km</td>";
			if($Autonomie_s or $Autonomie_t)$garage.="<td>".$Autonomie_s."km Strat / ".$Autonomie_t."km Tac</td>";
			$garage.="</tr>";
		}
		$garage.= "<tr><td bgcolor='tan' colspan='2'>Options de Bombes ou Charges supplémentaires</td></tr>";
		if($Bombe50_nbr >0)
			$garage.= "<tr><td colspan='2'>".$Bombe50_nbr." bombes de 50kg</td></tr>";
		if($Bombe125_nbr >0)
			$garage.= "<tr><td colspan='2'>".$Bombe125_nbr." bombes de 125kg</td></tr>";
		if($Bombe250_nbr >0)
			$garage.= "<tr><td colspan='2'>".$Bombe250_nbr." bombes de 250kg</td></tr>";
		if($Bombe500_nbr >0)
			$garage.= "<tr><td colspan='2'>".$Bombe500_nbr." bombes de 500kg</td></tr>";
		if($Bombe1000_nbr >0)
			$garage.= "<tr><td colspan='2'>".$Bombe1000_nbr." bombes de 1000kg</td></tr>";
		if($Bombe2000_nbr >0)
			$garage.= "<tr><td colspan='2'>".$Bombe2000_nbr." bombes de 2000kg</td></tr>";
		if($Torpilles >0)
			$garage.= "<tr><td colspan='2'>".$Torpilles." torpilles</td></tr>";
		if($Mines >0)
			$garage.= "<tr><td colspan='2'>".$Mines." mines ou charges</td></tr>";
		if($Rockets >0)
			$garage.= "<tr><td colspan='2'>".$Rockets." rockets</td></tr>";
		if($Camera_low !=5)
			$garage.= "<tr><td colspan='2'>1 Caméra portative (Basse altitude uniquement)</td></tr>";
		if($Camera_high !=5)
			$garage.= "<tr><td colspan='2'>1 Caméra fixe (".$Camera_high_masse."kg)</td></tr>";
		if($Radar_On)
			$garage.= "<tr><td colspan='2'>1 Radar embarqué</td></tr>";
		$garage.="</td></tr></table></div><br>";
		echo "<html><body background='images/bg_papier1.gif'><div align='center'>".$mes.$garage."</div></body></html>";
		$ID_vehi=10000+$ID_ref;
		echo "<div align='center'><table border='1' bgcolor='#ECDDC1'>
		<tr><td>Icone de base</td><td><img src='images/avions/avion".$ID_ref.".gif'></td></tr>
		<tr><td>Icone du front med</td><td><img src='images/avions/avion".$ID_ref."_f2.gif'></td></tr>
		<tr><td>Icone du front est</td><td><img src='images/avions/avion".$ID_ref."_f1.gif'></td></tr>
		<tr><td>Icone du front pac</td><td><img src='images/avions/avion".$ID_ref."_f3.gif'></td></tr>
		<tr><td>Icone au sol</td><td><img src='images/vehicules/vehicule".$ID_vehi.".gif'></td></tr>
		<tr><td>Garage</td><td><img src='images/avions/garage".$ID_ref.".jpg'></td></tr>
		<tr><td>Décollage</td><td><img src='images/avions/decollage".$ID_ref.".jpg'></td></tr>
		<tr><td>En Vol</td><td><img src='images/avions/vol".$ID_ref.".jpg'></td></tr>
		<tr><td>Atterrissage (facultatif)</td><td><img src='images/avions/landing".$ID_ref.".jpg'></td></tr>
		<tr><td>Crash</td><td><img src='images/avions/crash".$ID_ref.".jpg'></td></tr>
		<tr><td>Vol en formation (facultatif)</td><td><img src='images/avions/formation".$ID_ref.".jpg'></td></tr>
		<tr><td>Attaque en piqué (facultatif)</td><td><img src='images/avions/pique".$ID_ref.".jpg'></td></tr>
		<tr><td>Coupe transversale (facultatif)</td><td><img src='images/avions/coupe".$ID_ref.".jpg'></td></tr>
		</table></div>";
	}
}
?>