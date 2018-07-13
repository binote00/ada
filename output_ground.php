<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
//include_once('./menu_classement.php');
?>
<h1>Assauts terrestres</h1><div style='overflow:auto; width: 100%;'>
<table class='table table-striped'>
	<thead><tr>
		<th>Date</th>
		<th>Lieu</th>
		<th>Pays Attaquant</th>
		<th>Unité</th>
		<th>Attaquant</th>
		<th>Destruction</th>
		<th>Pays Cible</th>
	</tr></thead>
		<?
		$PlayerID = $_SESSION['PlayerID'];	
		$country = $_SESSION['country'];
		if($PlayerID > 0)
			$Renseignement = GetData("Pilote","ID",$PlayerID,"Renseignement");
		$con = dbconnecti(4);
		$result = mysqli_query($con, "SELECT * FROM Events_em WHERE Event_Type IN (212,215,222,223,224,229,230) AND DATE(`Date`) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
		UNION SELECT * FROM Events_ravit WHERE Event_Type=114 AND DATE(`Date`) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");
		mysqli_close($con);
		if($result)
		{
			$num = mysqli_num_rows($result);
			if($num==0)
				echo "<p>Aucune cible n'a encore été détruite dans cette campagne!</p>";
			else
			{
				$i=0;
				while ($i < $num) 
				{
					$Pays_eni = false;
					$Type = mysqli_result($result,$i,"Event_Type");
					$Date = substr(mysqli_result($result,$i,"Date"),0,16);
					$Lieu = mysqli_result($result,$i,"Lieu");
					$Veh_Nbr = mysqli_result($result,$i,"Avion_Nbr");
					$Reg = mysqli_result($result,$i,"Unit");
					$Veh = mysqli_result($result,$i,"Avion");
					$Reg_eni = mysqli_result($result,$i,"Pilote_eni");					
					$Lieu_Nom = GetData("Lieu","ID",$Lieu,"Nom");					
					if($Type == 114) //Veh_Nbr => 1 = Atk Air, 2 = Bomb Air, 3 = Atk Terre, 4 = Bomb Air IA
					{
						$Pays = GetData("Lieu","ID",$Lieu,"Flag");
						if($Veh_Nbr == 1 or $Veh_Nbr == 2)
						{
							$Off = false;
							$Pays_eni = GetData("Unit","ID",$Reg,"Pays");
							$Off_eni = GetAvionIcon($Veh,$Pays,0,0, 0);
							$Veh_Nbr = $Reg_eni;
							$Reg_eni = $Reg;
						}
						elseif($Veh_Nbr == 3)
						{
							$Pays_eni = GetData("Regiment","ID",$Reg,"Pays");
							$Off_eni = GetVehiculeIcon($Veh,$Pays_eni,0,0,0);
							$Veh_Nbr = $Reg_eni;
							$Reg_eni = $Reg;
						}
						elseif($Veh_Nbr == 4)
						{
							$Off = false;
							$Pays_eni = GetData("Unit","ID",$Reg,"Pays");
							$Off_eni = GetAvionIcon($Veh,$Pays,0,0, 0);
							$Veh_Nbr = $Reg_eni;
							$Reg_eni = $Reg;
						}
						$Veh = 3;
					}
					elseif($Type == 420)
					{
						$Off = mysqli_result($result,$i,"PlayerID");
						$Off_eni = "Mines";
						$Reg_eni = 0;
						$Pays_eni = 0;
						$Pays = GetData("Regiment","ID",$Reg_eni,"Pays");
					}
					elseif($Type == 229)
					{
						if($Veh_Nbr == 5)
						{
							$Veh_Nbr = $Reg_eni;
							$Off_eni = GetVehiculeIcon($Veh,$Pays,0,0,0);
							$Off = mysqli_result($result,$i,"PlayerID");
							$Reg_eni = mysqli_result($result,$i,"Unit");
							$Pays = GetData("Lieu","ID",$Lieu,"Flag");
							$Pays_eni = GetData("Regiment","ID",$Reg_eni,"Pays");
							$Veh = 11;
						}
						elseif($Veh_Nbr == 2) //Destruction terrestre
						{
							$Pays = GetData("Lieu","ID",$Lieu,"Flag");
							$Pays_eni = GetData("Regiment","ID",$Reg,"Pays");
							$Off_eni = GetVehiculeIcon($Veh,$Pays_eni,0,0,0);
							$Veh_Nbr = $Reg_eni;
							$Reg_eni = $Reg;
							$Veh = 12;
						}
					}
					elseif($Type == 230)
					{
						$Off_eni = GetVehiculeIcon($Veh,$Pays,0,0,0);
						$Off = mysqli_result($result,$i,"PlayerID");
						$Veh = 16;
						$Pays = GetData("Lieu","ID",$Lieu,"Flag");
						$Pays_eni = GetData("Regiment","ID",$Reg_eni,"Pays");
						$Veh_Nbr = 1;
					}
					elseif($Type < 225 and $Type > 210)
					{
						$Reg_eni = mysqli_result($result,$i,"Unit");
						$Reg = mysqli_result($result,$i,"Pilote_eni");
						$Off_eni = GetData("Officier","ID",mysqli_result($result,$i,"PlayerID"),"Nom");
						$Pays_eni = GetData("Regiment","ID",$Reg_eni,"Pays");
						$Off = 0;
						if($Type == 222)
							$Pays = GetData("Unit","ID",$Reg,"Pays");
						else
							$Pays = GetData("Lieu","ID",$Lieu,"Flag");
						if($Type == 212)
							$Veh = 2;
						elseif($Type == 215)
							$Veh = 9;
						elseif($Type == 224)
							$Veh = 1;
					}
					else
					{
						$Off = mysqli_result($result,$i,"PlayerID");
						if($Reg_eni)
							$Off_eni = GetData("Regiment","ID",$Reg_eni,"Officier_ID");
						$Off_eni = GetData("Officier","ID",$Off_eni,"Nom");
					}
					if(!$Pays)
						$Pays = GetData("Regiment","ID",$Reg,"Pays");
					if(!$Off_eni)
						$Off_eni = "Officier IA";
					if($Off)
						$Off = GetData("Officier","ID",$Off,"Nom");
					else
						$Off = "Officier IA";				
					if($Reg_eni)
						$Cie_eni = $Reg_eni.'e Cie';
					else
						$Cie_eni = 'Cie IA';
					if($Reg)
						$Cie = $Reg.'e Cie';
					else
						$Cie = 'Cie IA';						
					if($Type == 223)
					{
						$Cible = "<img src='images/aa".$Veh_Nbr.".png'>";
						$Veh_Nbr = 1;
					}
					elseif($Type == 222)
						$Cible = GetAvionIcon($Veh,$Pays,0,0, 0);
					else
						$Cible = GetVehiculeIcon($Veh,$Pays,0,0,0);
		?>
	<tr>
		<td><? echo $Date;?></td>
		<td><? if($Renseignement > 100 or $Pays == $country or $Pays_eni == $country or $PlayerID == 1){echo $Lieu_Nom;}else{echo "Inconnu";}?></td>
		<td><img src='<? echo $Pays_eni;?>20.gif'></td>
		<td><? echo $Cie_eni;?></td>
		<td><? echo $Off_eni;?></td>
		<td><? echo $Veh_Nbr.' '.$Cible;?></td>
		<td><img src='<? echo $Pays;?>20.gif'></td>
	</tr>
			<?
			$i++;
		}
	}
}
else
	echo "<h6>Désolé, Aucune cible n'a encore été détruite</h6>";
echo "</table></div>";
?>
