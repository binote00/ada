<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID = $_SESSION['Officier'];

if($PlayerID)
{
	$Reg = Insec($_POST['Reg']);
	$Veh = Insec($_POST['Veh']);
	$Cible = Insec($_POST['Cible']);
	$Conso = Insec($_POST['Conso']);
	$Bomb = Insec($_POST['Bomb']);
	$country = $_SESSION['country'];
	$choix= "";

	$Range = GetData("Cible","ID",$Veh,"Vitesse") * 100;
	$dive = "ground_pl";
	
	$Veh_Nbr = GetData("Regiment","ID",$Reg,"Vehicule_Nbr");
	$Veh_Carbu = GetData("Cible","ID",$Veh,"Carbu_ID");
	if($Veh_Carbu == 87 or $Veh_Carbu == 1)
	{
		$Stock = "Stock_Essence_".$Veh_Carbu;
		//$Conso = $Veh_Nbr * 10;
	}
	else
	{
		$Stock = "Moral";
		//$Conso = 1;
	}
	$Jauge = GetData("Regiment","ID",$Reg,$Stock);
	if($Jauge >= $Conso)
		UpdateData("Regiment",$Stock,-$Conso,"ID",$Reg);
	else
	{
		$Trait = GetData("Officier","ID",$PlayerID,"Trait");
		$Diff = ($Conso - $Jauge) / 10;
		SetData("Regiment",$Stock,0,"ID",$Reg);
		$Charisme = 0;
		if($Trait == 6)
			$Charisme = mt_rand(0,1);
		if($Diff > 0 and !$Charisme)
		{
			UpdateData("Regiment","Vehicule_Nbr",-$Diff,"ID",$Reg);
			UpdateData("Regiment","Moral",-$Diff,"ID",$Reg);
			$Veh_Nbr = GetData("Regiment","ID",$Reg,"Vehicule_Nbr");
			AddEvent("Cible", 410, $Veh, $PlayerID, $Reg, $Cible, $Diff);
			echo "<br>Une partie de vos troupes déserte!";
		}
	}
	
	if($Veh_Nbr)
	{
		//Scan Pos
		$con = dbconnecti();
		$result = mysqli_query($con, "SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr FROM Regiment as r,Cible as c 
		WHERE r.Vehicule_ID = c.ID AND c.Portee <= '$Range'
		AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr > 0 AND r.Officier_ID <> '$PlayerID' AND r.Pays <> '$country' AND r.Visible=1");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$choix .="<Input type='Radio' name='Action' value='".$data['ID']."_".$data['Vehicule_ID']."'>- ".$Vehicule_Nbr."<img src='images/vehicule".$data['Vehicule_ID'].".gif'> <b>".$data['ID']."e Cie</b><br>";
			}
		}
		
		echo "<html><head>
			<title>Aube des Aigles : Combat Terrestre</title>
			<link href='test.css' rel='stylesheet' type='text/css'></head>
		<body background='images/bg_papier1.gif'>";
		echo Afficher_Image('images/assault.jpg', "images/image.png", "");
		echo "<div align='center'><form action='".$dive."' method='post'>
			<input type='hidden' name='Veh' value='".$Veh."'>
			<input type='hidden' name='Reg' value='".$Reg."'>
			<input type='hidden' name='Bomb' value='".$Bomb."'>
			<table border='1' cellspacing='2' cellpadding='2' bgcolor='#ECDDC1'>
				<tr><th colspan='8' class='TitreBleu_bc'>Cibles repérées</th></tr>
				<tr><td align='left'>".$choix."
						<Input type='Radio' name='Action' value='0' checked>- Annuler l'attaque.<br>
				</td></tr>
			</table>
			<input type='Submit' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></form></div><hr>";
		if($Bomb)
			echo "<p><i>Aide : Vous ne pouvez attaquer qu'une cible repérée (via une reco terrestre ou aérienne) située à portée.<br>Plus votre unité aura une portée longue, plus elle pourra attaquer une cible éloignée</i></p>";
		else
			echo "<p><i>Aide : Vous ne pouvez attaquer qu'une cible repérée (via une reco terrestre ou aérienne) située à portée.<br>Plus votre unité est rapide (vitesse modifiée en fonction du terrain et du système de propulsion), plus elle pourra attaquer une cible éloignée</i></p>";
		echo "</body></html>";
	}
	else
		echo "<br>Ne disposant plus d'aucune troupe, vous n'avez d'autre choix que de rejoindre vos positions de départ!";
}
?>