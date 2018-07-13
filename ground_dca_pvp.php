<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_air_inc.php');
	include_once('./jfv_inc_pvp.php');
	$Reg=Insec($_POST['Reg']);
	$Battle=Insec($_POST['Battle']);
	$Faction=Insec($_POST['Camp']);
	if($Reg and $Battle and $Faction)
	{
		$Cible=GetCiblePVP($Battle);
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT Zone,Meteo FROM Lieu WHERE ID='$Cible'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : dca_pvp-cible');
		$result=mysqli_query($con,"SELECT w.Portee_max,r.Vehicule_ID,r.Vehicule_Nbr FROM Regiment_PVP as r,Cible as c,Armes as w WHERE r.Vehicule_ID=c.ID AND c.Arme_AA=w.ID AND r.ID='$Reg'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : dca_pvp-reg');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Plafond=$data['Portee_max'];
				$Veh=$data['Vehicule_ID'];
				$Vehicule_Nbr=$data['Vehicule_Nbr'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Zone=$data['Zone'];
				$Meteo=$data['Meteo'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		//DCA
		if($Vehicule_Nbr)
		{
			$Avions_txt="<Input type='Radio' name='Action' value='9999'>- Tirer au hasard<br>";
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT PlayerID,Avion,Altitude,Target FROM Duels_Candidats_PVP WHERE Lieu='$Cible' ORDER BY Altitude DESC");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$PvP_ID=$data['PlayerID'];
					$Vis_eni=GetVis("Avion",$data['Avion'],$Cible,$Meteo,$data['Altitude'],0);
					$Malus_alt=abs((0-$data['Altitude'])/100);
					$Detect=mt_rand(1,50)+$Meteo+$Vis_eni-$Malus_alt;
					if($Detect >0)
					{
						$Target=GetData("Duels_Candidats","PlayerID",$data['Target'],"Avion");
						$Avion_img='images/avions/avion'.$data['Avion'].'.gif';
						$Target_img='images/avions/avion'.$Target.'.gif';
						if($data['Altitude'] >$Plafond)
							$disabled=" disabled";
						else
							$disabled="";
						if($Target)
							$Avions_txt.="<Input type='Radio' name='Action' value='".$PvP_ID."'".$disabled.">- Tirer sur <img src='".$Avion_img."' title='Avion'> combattant un <img src='".$Target_img."' title='Avion'> volant à environ ".$data['Altitude']."m<br>";
						else
							$Avions_txt.="<Input type='Radio' name='Action' value='".$PvP_ID."'".$disabled.">- Tirer sur <img src='".$Avion_img."' title='Avion'> volant à environ ".$data['Altitude']."m<br>";
					}
				}
				mysqli_free_result($result);					
			}
			else
			{
				$intro.="<p>Vous ne détectez aucun avion</p>";
				$Avions_txt="";
			}
			$intro=GetVehiculeIcon($Veh);
		}
		else
			$intro="<p class='lead'>Votre compagnie a été détruite!</p>";
		$titre="DCA";
		$img='<img src=\'images/ciel'.$Meteo.'.jpg\' style=\'width:100%;\'>';
		$mes="<form action='index.php?view=ground_dca_shoot_pvp' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'>
		".$Avions_txt."<Input type='Radio' name='Action' value='0'>- Annuler<br><input type='Submit' value='TIRER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>
		<br><form action='index.php?view=ground_dca_pvp' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Battle' value='".$Battle."'><input type='hidden' name='Camp' value='".$Faction."'>
		<input type='Submit' value='Scruter le ciel' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>";
		$menu="<a href='index.php?view=ground_menu_pvp' class='btn btn-default' title='Retour'>Retour au menu</a>";
		include_once('./default.php');
	}
}
?>