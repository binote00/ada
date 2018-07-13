<?
require_once('./jfv_inc_sessions.php');
$PlayerID = $_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_avions.inc.php');
	$Unite=GetData("Pilote","ID",$PlayerID,"Unit");
	$Avion1=GetData("Unit","ID",$Unite,"Avion1");
	$Avion2=GetData("Unit","ID",$Unite,"Avion2");
	$Avion3=GetData("Unit","ID",$Unite,"Avion3");
	UpdateData("Pilote","Credits",-2,"ID",$PlayerID);
	echo "<p>Le mécano vous explique en détails les possibilités d'amélioration des avions de l'unité.</p>";	
	for($u=1;$u<4;$u++)
	{
		switch($u)
		{
			case 1:
				$ID_ref=$Avion1;
			break;
			case 2:
				$ID_ref=$Avion2;
			break;
			case 3:
				$ID_ref=$Avion3;
			break;
		}
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
		$Avion_nom=GetData("Avion","ID",$ID_ref,"Nom");
		$Arme1=GetData("Avion","ID",$ID_ref,"ArmePrincipale");
		$Arme1_nbr=GetData("Avion","ID",$ID_ref,"Arme1_Nbr");
		$Arme1_cal=substr(GetData("Armes","ID",$Arme1,"Calibre"),0,3);
		$Arme1_nom=GetData("Armes","ID",$Arme1,"Nom");
		$Arme2=GetData("Avion","ID",$ID_ref,"ArmeSecondaire");
		$Arme2_nbr=GetData("Avion","ID",$ID_ref,"Arme2_Nbr");
		$Arme2_cal=substr(GetData("Armes","ID",$Arme2,"Calibre"),0,3);
		$Arme2_nom=GetData("Armes","ID",$Arme2,"Nom");		
		$Arme8_fus_masse=GetData("Armes","ID",$Arme8_fus,"Masse");
		$Arme8_fus_nom=GetData("Armes","ID",$Arme8_fus,"Nom");
		$Arme8_fus_cal=substr(GetData("Armes","ID",$Arme8_fus,"Calibre"),0,3);
		$Arme8_ailes_masse=GetData("Armes","ID",$Arme8_ailes,"Masse");
		$Arme8_ailes_nom=GetData("Armes","ID",$Arme8_ailes,"Nom");
		$Arme8_ailes_cal=substr(GetData("Armes","ID",$Arme8_ailes,"Calibre"),0,3);
		if($Arme13 != 5)
		{
			$Arme13_masse=GetData("Armes","ID",$Arme13,"Masse");
			$Arme13_cal=substr(GetData("Armes","ID",$Arme13,"Calibre"),0,3);
		}
		if($Arme20 != 5)
		{
			$Arme20_masse=GetData("Armes","ID",$Arme20,"Masse");
			$Arme20_cal=substr(GetData("Armes","ID",$Arme20,"Calibre"),0,3);
		}
		if($Camera_high != 5)
			$Camera_high_masse=GetData("Armes","ID",$Camera_high,"Masse");
		$Arme13_nom=GetData("Armes","ID",$Arme13,"Nom");
		$Arme20_nom=GetData("Armes","ID",$Arme20,"Nom");
?>
	<table class='table'>
	<thead><tr><th colspan="6"><? echo $Avion_nom;?></th><tr></thead>
	<tr class="TitreBleu_bc">
		<th>Arme Principale</th>
		<th>Arme Secondaire</th>
	</tr>
	<tr><td><? echo $Arme1_nbr." ".$Arme1_nom." (".$Arme1_cal."mm)";?></td><td><? echo $Arme2_nbr." ".$Arme2_nom." (".$Arme2_cal."mm)";?></td></tr>
				<?if($Arme8_fus_nbr >0){?>
				<tr><td><? echo $Arme8_fus_nbr." ".$Arme8_fus_nom." (".$Arme8_fus_cal."mm)";?> (<?echo $Arme8_fus_masse*$Arme8_fus_nbr;?>kg)</td>
				<?}if($Arme8_ailes_nbr >0){?>
				<td><? echo $Arme8_ailes_nbr." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*2;?>kg)</td></tr>
				<?}if($Arme13_fus_nbr >0){?>
				<tr><td><? echo $Arme13_fus_nbr." ".$Arme13_nom." (".$Arme13_cal."mm)";?> (<?echo $Arme13_masse*$Arme13_fus_nbr;?>kg)</td>
				<?}if($Arme8_ailes_max >3)
				{$Arme8_ailes_nbr = $Arme8_ailes_nbr*2;?>
				<td><? echo $Arme8_ailes_nbr." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*4;?>kg)</td></tr>
				<?}if($Arme20_fus_nbr >0){?>
				<tr><td><? echo $Arme20_fus_nbr." ".$Arme20_nom." (".$Arme20_cal."mm)";?> (<?echo $Arme20_masse*$Arme20_fus_nbr;?>kg)</td>
				<?}if($Arme8_ailes_max >5){?>
				<td><? echo $Arme8_ailes_max." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*6;?>kg)</td></tr>
				<?}if($Camera_low !=5){?>
				<tr><td>1 Caméra portative (Basse altitude uniquement)</td>
				<?}if($Arme13_ailes_max >0){?>
				<td><? echo $Arme13_ailes_nbr." ".$Arme13_nom." (".$Arme13_cal."mm)";?> (<?echo $Arme13_masse*$Arme13_ailes_nbr;?>kg)</td></tr>
				<?}if($Camera_high !=5){?>
				<tr><td>1 Caméra fixe (<?echo $Camera_high_masse;?>kg)</td>
				<?}if($Arme13_ailes_max >3){?>
				<td><? echo $Arme13_ailes_max." ".$Arme13_nom." (".$Arme13_cal."mm)";?> (<?echo $Arme13_masse*$Arme13_ailes_nbr*2;?>kg)</td></tr>
				<td></td>
				<?}if($Arme20_ailes_nbr >0){?>
				<td><? echo $Arme20_ailes_nbr." ".$Arme20_nom." (".$Arme20_cal."mm)";?> (<?echo $Arme20_masse*$Arme20_ailes_nbr;?>kg)</td></tr>
				<?}?>
				<?if($Baby >1)
				{?>
				<tr><th colspan="2">Réservoir Supplémentaire</th></tr>
				<tr><td colspan="2">Sous les ailes (500kg; Autonomie +200)</td></tr>
				<?}
				elseif($Baby >0)
				{?>
				<tr><th colspan="2">Réservoir Supplémentaire</th></tr>
				<tr><td colspan="2">Sous fuselage (200kg; Autonomie +100)</td></tr>
				<?}?>
				<tr><th colspan="2">Bombes ou Charges supplémentaires</th></tr>
				<?if($Bombe50_nbr >0){?>
				<td><? echo $Bombe50_nbr." bombes de 50kg";?> (<?echo $Bombe50_nbr*50;?>kg)</td>
				<?}if($Bombe1000_nbr >0){?>
				<tr><td><? echo $Bombe1000_nbr." bombes de 1000kg";?> (<?echo $Bombe1000_nbr*1000;?>kg)</td></tr>
				<?}if($Bombe125_nbr >0){?>
				<td><? echo $Bombe125_nbr." bombes de 125kg";?> (<?echo $Bombe125_nbr*125;?>kg)</td>
				<?}if($Bombe2000_nbr >0){?>
				<tr><td><? echo $Bombe2000_nbr." bombes de 2000kg";?> (<?echo $Bombe2000_nbr*2000;?>kg)</td></tr>
				<?}if($Bombe250_nbr >0){?>
				<td><? echo $Bombe250_nbr." bombes de 250kg";?> (<?echo $Bombe250_nbr*250;?>kg)</td>
				<?}if($Camera_low !=5){?>
				<tr><td>1 Caméra portative (Basse altitude uniquement)</td></tr>
				<?}if($Bombe500_nbr >0){?>
				<td><? echo $Bombe500_nbr." bombes de 500kg";?> (<?echo $Bombe500_nbr*500;?>kg)</td>
				<?}if($Camera_high !=5){?>
				<tr><td>1 Caméra fixe (<?echo $Camera_high_masse;?>kg)</td></tr>
				<?}?>		
		</td>		
	</tr></table>
<?
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>