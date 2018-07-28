<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Regiment=Insec($_POST['Reg']);		
	if($Regiment >0 or $Admin)
	{
		$country=$_SESSION['country'];
		if(!$Regiment)
			$Veh=19;
		else
			$Veh=GetData("Regiment","ID",$Regiment,"Vehicule_ID");
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Reputation,Avancement,Front FROM Officier WHERE ID='$OfficierID'");
		$result2=mysqli_query($con,"SELECT Categorie,mobile FROM Cible WHERE ID='$Veh'");
		mysqli_close($con);
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Categorie=$data2['Categorie'];
				$mobile=$data2['mobile'];
			}
			mysqli_free_result($result2);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Reputation=$data['Reputation'];
				$Avancement=$data['Avancement'];
				$Front=$data['Front'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		$DCAm_ic=83;
		$HT_ic=448;
		$Artm_ic=251;
		$CC_ic=256;
		$Bld_ic=318;
		$CA_ic=189;
		if($country ==1)
		{
			$Art_ic=198;
			$DCA_ic=157;
			$AT_ic=134;
			$DCAm_ic=81;
			$HT_ic=121;
			$VB_ic=120;
			$Blg_ic=125;
			$Artm_ic=512;
			$CC_ic=257;
			$Blm_ic=167;
			$Bld_ic=387;
			$CA_ic=190;
		}
		elseif($country ==2)
		{
			$Art_ic=150;
			$DCA_ic=187;
			$AT_ic=186;
			$DCAm_ic=85;
			$HT_ic=91;
			$VB_ic=181;
			$Blg_ic=24;
			$Artm_ic=259;
			$CC_ic=501;
			$Blm_ic=260;
			$Bld_ic=397;
			$CA_ic=525;
		}
		elseif($country ==4)
		{
			$Art_ic=145;
			$DCA_ic=87;
			$AT_ic=146;
			$HT_ic=148;
			$VB_ic=61;
			$Blg_ic=169;
			$CC_ic=173;
			$Blm_ic=172;
		}
		elseif($country ==6)
		{
			$Art_ic=183;
			$DCA_ic=339;
			$AT_ic=158;
			$DCAm_ic=351;
			$VB_ic=60;
			$Blg_ic=79;
			$Artm_ic=413;
			$CC_ic=355;
			$Blm_ic=45;
			$CA_ic=357;
		}
		elseif($country ==7)
		{
			$Art_ic=482;
			$DCA_ic=526;
			$AT_ic=491;
			$DCAm_ic=548;
			$HT_ic=477;
			$VB_ic=585;
			$Blg_ic=492;
			$Artm_ic=483;
			$CC_ic=542;
			$Blm_ic=473;
		}
		elseif($country ==8)
		{
			$Art_ic=279;
			$DCA_ic=282;
			$AT_ic=368;
			$DCAm_ic=370;
			$HT_ic=448;
			$VB_ic=291;
			$Blg_ic=286;
			$Artm_ic=372;
			$CC_ic=528;
			$Blm_ic=308;
			$Bld_ic=322;
			$CA_ic=406;
		}
		elseif($country ==9)
		{
			$Art_ic=570;
			$DCA_ic=569;
			$AT_ic=567;
			$VB_ic=598;
			$Blg_ic=600;
			$CC_ic=612;
			$Blm_ic=608;
			$CA_ic=610;
		}
		$forma="<form action='index.php?view=change_materiel' method='post'>
				<input type='hidden' name='Reg' value='".$Regiment."'>
				<input type='hidden' name='Cat' value='";
		$formb="'><input type='Submit' value='Choix' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		//Get Vehicules
		if($Categorie ==13) //Loco
			$cats.="<tr><td>Locomotive</td><td>".GetVehiculeIcon(56,$country,0,0,$Front)."</td><td>".$forma."13".$formb."</td></tr>";
		elseif($Categorie ==17) //Sub
			$cats.="<tr><td>Sous-marin</td><td>".GetVehiculeIcon(5022,$country,0,0,$Front)."</td><td>".$forma."37".$formb."</td></tr>";
		elseif($mobile ==4)
			$cats.="<tr><td>Wagon</td><td>".GetVehiculeIcon(72,$country,0,0,$Front)."</td><td>".$forma."998".$formb."</td></tr>";
		elseif($mobile ==5)
		{
			$cats.="<tr><td>Petit navire</td><td>".GetVehiculeIcon(5002,$country,0,0,$Front)." ".GetVehiculeIcon(5000,$country,0,0,$Front)." ".GetVehiculeIcon(5001,$country,0,0,$Front)."</td><td>".$forma."14".$formb."</td></tr>
					<tr><td>Corvette</td><td>".GetVehiculeIcon(5003,$country,0,0,$Front)."</td><td>".$forma."15".$formb."</td></tr>";
			if($Avancement >6000)
				$cats.="<tr><td>Frégate</td><td>".GetVehiculeIcon(5004,$country,0,0,$Front)."</td><td>".$forma."16".$formb."</td></tr>";
			else
				$cats.="<tr><td>Frégate</td><td>".GetVehiculeIcon(5004,$country,0,0,$Front)."</td><td><img src='images/grades/ranks".$country."10.png'></td></tr>";
			if($Avancement >10000)
				$cats.="<tr><td>Destroyer</td><td>".GetVehiculeIcon(5005,$country,0,0,$Front)."</td><td>".$forma."17".$formb."</td></tr>";
			else
				$cats.="<tr><td>Destroyer</td><td>".GetVehiculeIcon(5005,$country,0,0,$Front)."</td><td><img src='images/grades/ranks".$country."11.png'></td></tr>";
			if($Avancement >25000)
				$cats.="<tr><td>Croiseur léger</td><td>".GetVehiculeIcon(5007,$country,0,0,$Front)."</td><td>".$forma."18".$formb."</td></tr>";
			else
				$cats.="<tr><td>Croiseur léger</td><td>".GetVehiculeIcon(5007,$country,0,0,$Front)."</td><td><img src='images/grades/ranks".$country."12.png'></td></tr>";
		}
		//elseif($Division_Cdt ==$OfficierID)//95
		else
		{
			$cats.="<tr><td>Infanterie</td><td>".GetVehiculeIcon(48,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='30' aria-valuemin='0' aria-valuemax='100' style='width: 30%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='250' aria-valuemin='0' aria-valuemax='25000' style='width: 1%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='5' aria-valuemin='0' aria-valuemax='200' style='width: 2.5%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='500' style='width: 10%'></div></div></td>
				<td>".$forma."999".$formb."</td></tr>
					<tr><td>Cavalerie et infanterie motorisée</td><td>".GetVehiculeIcon(52,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='25' aria-valuemin='0' aria-valuemax='100' style='width: 25%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='250' aria-valuemin='0' aria-valuemax='25000' style='width: 1%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='25' aria-valuemin='0' aria-valuemax='200' style='width: 12.5%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='500' style='width: 20%'></div></div></td>
				<td>".$forma."93".$formb."</td></tr>
					<tr><td>Camion logistique</td><td>".GetVehiculeIcon(118,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='2.5' aria-valuemin='0' aria-valuemax='100' style='width: 2.5%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='25000' style='width: 0%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='200' style='width: 30%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='300' aria-valuemin='0' aria-valuemax='500' style='width: 60%'></div></div></td>
				<td>".$forma."1".$formb."</td></tr>
					<tr><td>Artillerie</td><td>".GetVehiculeIcon($Art_ic,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='6' aria-valuemin='0' aria-valuemax='100' style='width: 6%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='10000' aria-valuemin='0' aria-valuemax='25000' style='width: 40%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='5' aria-valuemin='0' aria-valuemax='200' style='width: 2.5%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='500' style='width: 10%'></div></div></td>
				<td>".$forma."6".$formb."</td></tr>
					<tr><td>Artillerie anti-aérienne</td><td>".GetVehiculeIcon($DCA_ic,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='6' aria-valuemin='0' aria-valuemax='100' style='width: 6%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='5000' aria-valuemin='0' aria-valuemax='25000' style='width: 20%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='10' aria-valuemin='0' aria-valuemax='200' style='width: 5%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='500' style='width: 20%'></div></div></td>
				<td>".$forma."12".$formb."</td></tr>
					<tr><td>Artillerie anti-char</td><td>".GetVehiculeIcon($AT_ic,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='9' aria-valuemin='0' aria-valuemax='100' style='width: 9%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='7500' aria-valuemin='0' aria-valuemax='25000' style='width: 30%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='10' aria-valuemin='0' aria-valuemax='200' style='width: 5%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='500' style='width: 20%'></div></div></td>
				<td>".$forma."4".$formb."</td></tr>";
			$cats.="<tr><td>Artillerie anti-aérienne mobile</td><td>".GetVehiculeIcon($DCAm_ic,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='1500' aria-valuemin='0' aria-valuemax='100' style='width: 3%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='5000' aria-valuemin='0' aria-valuemax='25000' style='width: 20%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='200' style='width: 30%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='250' aria-valuemin='0' aria-valuemax='500' style='width: 50%'></div></div></td>";
			if($Avancement >5000 or $Reputation >100)
				$cats.="<td>".$forma."11".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."10.png'><img src='images/general2.png'></td></tr>";
			$cats.="<tr><td>Half-track</td><td>".GetVehiculeIcon($HT_ic,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='6' aria-valuemin='0' aria-valuemax='100' style='width: 6%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='250' aria-valuemin='0' aria-valuemax='25000' style='width: 1%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='200' style='width: 25%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='250' aria-valuemin='0' aria-valuemax='500' style='width: 50%'></div></div></td>";
			if($Avancement >5000 or $Reputation >100)
				$cats.="<td>".$forma."2".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."10.png'><img src='images/general2.png'></td></tr>";
			$cats.="<tr><td>Voiture blindée</td><td>".GetVehiculeIcon($VB_ic,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='12' aria-valuemin='0' aria-valuemax='100' style='width: 12%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='250' aria-valuemin='0' aria-valuemax='25000' style='width: 1%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='80' aria-valuemin='0' aria-valuemax='200' style='width: 40%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='300' aria-valuemin='0' aria-valuemax='500' style='width: 60%'></div></div></td>";
			if($Avancement >5000 or $Reputation >500)
				$cats.="<td>".$forma."3".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."10.png'><img src='images/general3.png'></td></tr>";
			$cats.="<tr><td>Blindé léger</td><td>".GetVehiculeIcon($Blg_ic,$country,0,0,$Front)."</td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='20' aria-valuemin='0' aria-valuemax='100' style='width: 20%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='500' aria-valuemin='0' aria-valuemax='25000' style='width: 2%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='200' style='width: 20%'></div></div></td>
				<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='200' aria-valuemin='0' aria-valuemax='500' style='width: 40%'></div></div></td>";
			if($Avancement >5000 or $Reputation >500)
				$cats.="<td>".$forma."5".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."10.png'><img src='images/general3.png'></td></tr>";
			$cats.="<tr><td>Artillerie mobile</td><td>".GetVehiculeIcon($Artm_ic,$country,0,0,$Front)."</td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='6' aria-valuemin='0' aria-valuemax='100' style='width: 6%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='10000' aria-valuemin='0' aria-valuemax='25000' style='width: 40%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='200' style='width: 20%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='150' aria-valuemin='0' aria-valuemax='500' style='width: 30%'></div></div></td>";						
			if($Avancement >5000 or $Reputation >1000)
				$cats.="<td>".$forma."8".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."10.png'><img src='images/general4.png'></td></tr>";
			$cats.="<tr><td>Chasseur de chars</td><td>".GetVehiculeIcon($CC_ic,$country,0,0,$Front)."</td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='12' aria-valuemin='0' aria-valuemax='100' style='width: 12%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='7500' aria-valuemin='0' aria-valuemax='25000' style='width: 30%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='200' style='width: 20%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='200' aria-valuemin='0' aria-valuemax='500' style='width: 40%'></div></div></td>";
			if($Avancement >5000 or $Reputation >1000)
				$cats.="<td>".$forma."9".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."10.png'><img src='images/general4.png'></td></tr>";
			$cats.="<tr><td>Blindé moyen</td><td>".GetVehiculeIcon($Blm_ic,$country,0,0,$Front)."</td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100' style='width: 50%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='2500' aria-valuemin='0' aria-valuemax='25000' style='width: 10%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='30' aria-valuemin='0' aria-valuemax='200' style='width: 15%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='200' aria-valuemin='0' aria-valuemax='500' style='width: 40%'></div></div></td>";
			if($Avancement >5000 or $Reputation >1000)
				$cats.="<td>".$forma."7".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."10.png'><img src='images/general4.png'></td></tr>";
			$cats.="<tr><td>Blindé lourd</td><td>".GetVehiculeIcon($Bld_ic,$country,0,0,$Front)."</td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: 60%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='5000' aria-valuemin='0' aria-valuemax='25000' style='width: 20%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='20' aria-valuemin='0' aria-valuemax='200' style='width: 10%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='150' aria-valuemin='0' aria-valuemax='500' style='width: 30%'></div></div></td>";
			if($Avancement >25000 or $Reputation >2000)
				$cats.="<td>".$forma."10".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."12.png'><img src='images/general5.png'></td></tr>";
			$cats.="<tr><td>Canon d'assaut</td><td>".GetVehiculeIcon($CA_ic,$country,0,0,$Front)."</td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100' style='width: 50%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='7500' aria-valuemin='0' aria-valuemax='25000' style='width: 30%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='30' aria-valuemin='0' aria-valuemax='200' style='width: 15%'></div></div></td>
					<td><div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='200' aria-valuemin='0' aria-valuemax='500' style='width: 40%'></div></div></td>";
			if($Avancement >10000 or $Reputation >2000)
				$cats.="<td>".$forma."91".$formb."</td></tr>";
			else
				$cats.="<td><img src='images/grades/ranks".$country."11.png'><img src='images/general5.png'></td></tr>";
		}
		"<div class='progress'><div class='progress-bar-success' role='progressbar' aria-valuenow='100000' aria-valuemin='0' aria-valuemax='250000' style='width: 40%'></div></div>";									
		$titre="Hangar";
		if($mobile ==5)
			$titre_up="<thead><tr>  
				<th>Type</th>
				<th>Exemple</th>
				<th>Prérequis</th></tr></thead>";		
		else
			$titre_up="<thead><tr>  
				<th>Type</th>
				<th>Exemple</th>
				<th>Valeur défensive moyenne</th>
				<th>Valeur offensive moyenne</th>
				<th>Vitesse moyenne</th>
				<th>Autonomie moyenne</th>
				<th>Choix</th></tr></thead>";		
		$mes="<h2>Catégories disponibles <a href='#' class='popup'><img src='images/help.png'><span>Convertissez votre bataillon si vous voulez changer de catégorie</span></a></h2>
		<div style='overflow:auto; height: 600px;'><table class='table table-striped'>".$titre_up.$cats."</table></div>";
		include_once('./default.php');
	}
}
?>