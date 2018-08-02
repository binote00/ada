<?php
require_once 'jfv_inc_sessions.php';
if(isset($_SESSION['AccountID']))
{
	$Unite=false;
	$OfficierEMID=$_SESSION['Officier_em'];
	$PlayerID=$_SESSION['PlayerID'];
	if($OfficierEMID >0 or $PlayerID >0)
	{
		$country=$_SESSION['country'];
		include_once 'jfv_include.inc.php';
		include_once 'jfv_txt.inc.php';
		if($OfficierEMID >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Front,Avancement,Admin FROM Officier_em WHERE ID='$OfficierEMID'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
				{
					$Front=$data['Front'];
					$Avancement=$data['Avancement'];
					$Admin=$data['Admin'];
				}
				mysqli_free_result($result);
			}
		}
		elseif($PlayerID >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Front,Avancement,Admin FROM Pilote WHERE ID='$PlayerID'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
				{
					$Front=$data['Front'];
					$Avancement=$data['Avancement'];
					$Admin=$data['Admin'];
				}
				mysqli_free_result($result);
			}
		}
		$con=dbconnecti();	
		$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2, MYSQLI_ASSOC))
			{
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Adjoint_EM'];
				$Officier_EM=$data['Officier_EM'];
				$Officier_Rens=$data['Officier_Rens'];
			}
			mysqli_free_result($result2);
		}
		if($Admin ==1 or ($OfficierEMID >0 and ($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_EM or $OfficierEMID ==$Officier_Rens)))
			$Unite=Insec($_GET['unite']);
		if(!$Unite and $PlayerID >0)
		{
			$Unite=GetData("Pilote","ID",$PlayerID,"Unit");
			$inner=true;
		}
		$noaccess=false;		
		$con=dbconnecti();
		$Unite=mysqli_real_escape_string($con,$Unite);
		$result=mysqli_query($con,"SELECT Nom,Pays,Etat,Base_Ori,Base,Commandant,Officier_Adjoint,Officier_Technique,Avion1_Ori,Avion2_Ori,Avion3_Ori,Avion1,Avion2,Avion3,Avion1_Nbr,Avion3_Nbr,Avion2_Nbr,
		Reputation,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Essence_1,Stock_Essence_87,Stock_Essence_100 FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($result)
		{
			while($data2=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
			{
				$Unite_Nom=$data2['Nom'];
				$Pays=$data2['Pays'];
				$Etat=$data2['Etat'];
				$Base_Ori=$data2['Base_Ori'];
				$Base=$data2['Base'];
				$Cdt=$data2['Commandant'];
				$Adjoint=$data2['Officier_Adjoint'];
				$Tech=$data2['Officier_Technique'];
				$Unite_Avion1=$data2['Avion1_Ori'];
				$Unite_Avion2=$data2['Avion2_Ori'];
				$Unite_Avion3=$data2['Avion3_Ori'];
				$Avion1=$data2['Avion1'];
				$Avion2=$data2['Avion2'];
				$Avion3=$data2['Avion3'];
				$Avion1_Nbr=$data2['Avion1_Nbr'];
				$Avion2_Nbr=$data2['Avion2_Nbr'];
				$Avion3_Nbr=$data2['Avion3_Nbr'];
				$Reputation=$data2['Reputation'];
				$Stock_Munitions_8=$data2['Stock_Munitions_8'];
				$Stock_Munitions_13=$data2['Stock_Munitions_13'];
				$Stock_Munitions_20=$data2['Stock_Munitions_20'];
				$Stock_Essence_1=$data2['Stock_Essence_1'];
				$Stock_Essence_87=$data2['Stock_Essence_87'];
				$Stock_Essence_100=$data2['Stock_Essence_100'];
			}
			mysqli_free_result($result);
		}		
		if($country ==$Pays or $Admin ==1)
		{
			if($Admin ==1 or ($OfficierEMID >0 and ($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_EM))
			or ($PlayerID >0 and ($Cdt ==$PlayerID or $Adjoint ==$PlayerID)))
			{		
				if($Admin)
					$Activity=" (".$Etat.") Réputation=".$Reputation." / Base : ".GetData("Lieu","ID",$Base,"Nom")." / Commandant : ".GetData("Pilote","ID",$Cdt,"Nom")." ".GetData("Pilote","ID",$Cdt,"ID")." / Adjoint : ".GetData("Pilote","ID",$Adjoint,"Nom")." / Ravit : ".GetData("Pilote","ID",$Tech,"Nom").
					"<br> 8mm : ".$Stock_Munitions_8." / 13mm : ".$Stock_Munitions_13." / 20mm : ".$Stock_Munitions_20." / Octane 87 : ".$Stock_Essence_87." / Octane 100 : ".$Stock_Essence_100." / Diesel : ".$Stock_Essence_1.
					"<br>".$Avion1_Nbr." ".GetAvionIcon($Avion1,$Pays,0,$Unite,$Front,0,true)."<br>".$Avion2_Nbr." ".GetAvionIcon($Avion2,$Pays,0,$Unite,$Front,0,true)."<br>".$Avion3_Nbr." ".GetAvionIcon($Avion3,$Pays,0,$Unite,$Front,0,true);
				$Dotation_Base="1 ".GetAvionIcon($Unite_Avion1,$Pays,0,$Unite,$Front)."<br>2 ".GetAvionIcon($Unite_Avion2,$Pays,0,$Unite,$Front)."<br>3 ".GetAvionIcon($Unite_Avion3,$Pays,0,$Unite,$Front);
				$Base_ori=GetData("Lieu","ID",$Base_Ori,"Nom");
				//Journal
				$con=dbconnecti();
				$result_unit=mysqli_query($con,"SELECT `Date`,Lieu,Pays,Type,Avion,Avion_Nbr FROM Event_Historique WHERE Unite='$Unite' AND Type IN(21,41,51,52,53,54,57) ORDER BY `Date` DESC");
				mysqli_close($con);
				if($result_unit)
				{
					while($Data=mysqli_fetch_array($result_unit, MYSQLI_ASSOC)) 
					{
						$Date=$Data['Date'];
						$Lieu=$Data['Lieu'];
						$Type_e=$Data['Type'];
						if($Type_e ==41)
						{
							$Lieu_Nom=GetData("Lieu","ID",$Lieu,"Nom");
							$Ville_Pays=GetData("Lieu","ID",$Lieu,"Pays");
							$Lieu_Pays=GetPays($Ville_Pays);
							$Event.="<br>".$Date." : Mouvement vers ".$Lieu_Nom." <img src='".$Ville_Pays."20.gif' title='".$Lieu_Pays."'>"; 
						}
						elseif($Type_e ==51)
						{
							$Lieu_Nom=GetData("Lieu","ID",$Lieu,"Nom");
							$Event.="<br>".$Date." : Unité activée à ".$Lieu_Nom; 
						}
						elseif($Type_e ==52)
						{
							$Event.="<br>".$Date." : Unité dissoute"; 
						}
						elseif($Type_e ==53)
						{
							$Nouveau_Nom=GetData("Unit","ID",$Data['Avion'],"Nom");
							$Event.="<br>".$Date." : Unité reformée en tant que ".$Nouveau_Nom; 
						}
						elseif($Type_e ==54)
						{
							$Event.="<br>".$Date." : Unité reformée en tant qu'unité de <b>".GetAvionType($Data['Avion_Nbr'])."</b>";
						}
						elseif($Type_e ==57 and $Lieu)
						{
							$Event.="<br>".$Date." : Unité embarque à bord du <b>".GetData("Cible","ID",$Lieu,"Nom")."</b>";
						}
						elseif($Type_e ==21)
						{
							$Sqn=GetSqn($country);
							if($Lieu)
								$Event.="<br>".$Date." : Mise à niveau de l'équipement (".$Sqn." ".$Lieu.")<br>".GetAvionIcon($Data['Avion'],$Data['Pays'],0,$Unite,$Front); 
							else
								$Event.="<br>".$Date." : Mise à niveau de l'équipement des 3 ".$Sqn."<br>".GetAvionIcon($Data['Avion'],$Data['Pays'],0,$Unite,$Front); 
						}
					}
					mysqli_free_result($result_unit);
				}
			}
			else
				$noaccess=true;
		}
		else
			$noaccess=true;
		if($noaccess)
		{
			$mes="Ces données sont classifiées. Votre rang ne vous permet pas d'accéder à ces informations.";
			include_once 'view/menu_escadrille.php';
			echo "<table class='table'>
				<tr><td><img src='images/top_secret.gif'></td></tr>
				<tr><td>Ces données sont classifiées.</td> </tr>
				<tr><td>Votre rang ne vous permet pas d'accéder à ces informations.</td></tr>
			</table>";
		}
		elseif($inner)
		{
			echo "<h1>".$Unite_Nom.$Activity."</h1><h2>Archives</h2>
			<div style='overflow:auto; height: 640px;'><table class='table'>
			<tr><td>".$Event."</td></tr>
			<tr><td><hr></td></tr>
			<tr><th>Dotation d'origine</th></tr>
			<tr><td>".$Dotation_Base."</td></tr>
			<tr><th>Base de formation : ".$Base_ori."</th></tr>
			</table></div>";
		}
		else
		{
			$Pilotes_IA="<h3>Pilotes IA</h3><table class='table'><tr><th>Nom</th><th>Grade</th><th>Reput</th><th>Exp</th><th>Missions</th><th>Victoires</th><th>Points</th></tr>";
			$con=dbconnecti();
			$resultp=mysqli_query($con,"SELECT Nom,Pilotage,Reputation,Avancement,Missions,Victoires,Points FROM Pilote_IA WHERE Unit='$Unite' AND Actif='1'");
			mysqli_close($con);
			if($resultp)
			{
				while($dataa=mysqli_fetch_array($resultp, MYSQLI_ASSOC))
				{
					$Pilotes_IA.="<tr><td>".$dataa['Nom']."</td><td>".$dataa['Avancement']."</td><td>".$dataa['Reputation']."</td><td>".$dataa['Pilotage']."</td><td>".$dataa['Missions']."</td><td>".$dataa['Victoires']."</td><td>".$dataa['Points']."</td><td></tr>";
				}
				mysqli_free_result($resultp);
			}
			$Pilotes_IA.="</table>";
			$mes="<h1>".$Unite_Nom."</h1>".$Activity.$Pilotes_IA."<h2>Archives</h2>
			<div style='overflow:auto; height: 640px;'><table class='table'>
			<tr><td>".$Event."</td></tr>
			<tr><td><hr></td></tr>
			<tr><th>Dotation d'origine</th></tr>
			<tr><td>".$Dotation_Base."</td></tr>
			<tr><th>Base de formation : ".$Base_ori."</th></tr></table></div>";
			include_once 'default_blank.php';
		}
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";