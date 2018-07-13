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
	$Action=Insec($_POST['Action']);
	$Cible=GetCiblePVP($Battle);
	$con=dbconnecti();
	$result2=mysqli_query($con,"SELECT Zone,Meteo FROM Lieu WHERE ID='$Cible'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : dca_pvp-cible');
	$result=mysqli_query($con,"SELECT c.Portee,c.Arme_AA,r.Vehicule_ID,r.Vehicule_Nbr,r.Experience FROM Regiment_PVP as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.ID='$Reg'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : dca_pvp_s-reg');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Plafond=$data['Portee'];
			$Veh=$data['Vehicule_ID'];
			$Vehicule_Nbr=$data['Vehicule_Nbr'];
			$Exp=50; //$data['Experience'];
			$Arme_AA=$data['Arme_AA'];
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
		if($Action ==9999)
		{
			$mes="Vous tirez, mais à votre grand étonnement rien n'explose dans le ciel!";
			UpdateData("Regiment_PVP","Stock_Art",1,"ID",$Reg);
			SetData("Regiment_PVP","Visible",1,"ID",$Reg);
		}
		elseif($Action)
		{
			include_once('./jfv_combat.inc.php');
			include_once('./jfv_ground.inc.php');
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT PlayerID,Avion,Altitude,HP FROM Duels_Candidats_PVP WHERE PlayerID='$Action'");
			$result3=mysqli_query($con,"SELECT Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$Arme_AA'");
			mysqli_close($con);
			if($result3)
			{
				while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
				{
					$Arme_Cal=round($data3['Calibre']);
					$mult=$data3['Multi'];
					$Arme_Degats=$data3['Degats'];
					$Arme_Perf=$data3['Perf'];
					$Arme_Portee=$data3['Portee'];
					$Arme_Portee_Max=$data3['Portee_max'];
				}
				mysqli_free_result($result3);
			}	
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$PvP_ID=$data['PlayerID'];
					if($PvP_ID)
					{
						$Vis_eni=GetVis("Avion",$data['Avion'],$Cible,$Meteo,$data['Altitude'],0);
						$Malus_alt=abs((0-$data['Altitude'])/100);
						$Shoot=mt_rand(0,$Exp)-$Malus_alt+($Meteo*2)+($Vis_eni/2)+$mult;
						if($data['Altitude'] >$Plafond)
							$mes.="La cible est trop haute à présent, vous la ratez de peu!";
						elseif($Shoot >0)
						{
							$Blindage_emb=GetData("Avion","ID",$data['Avion'],"Blindage");
							$Degats=(mt_rand(1,$Arme_Degats)-$Blindage_emb)*GetShoot($Shoot,$mult);
							$Degats=round(Get_Dmg($data['Muns'],$Arme_Cal,$Blindage_emb,$data['Altitude'],$Degats,$Arme_Perf,$Arme_Portee,$Arme_Portee_Max));
							$data['HP']-=$Degats;
							SetData("Duels_Candidats_PVP","HP",$data['HP'],"PlayerID",$PvP_ID);
							SetData("Pilote_PVP","S_HP",$data['HP'],"ID",$PvP_ID);
							if(!$data['HP'])
							{
								AddDCACbtPVP($Cible,$PvP_ID,$data['Avion'],$data['Altitude'],$Nuit,$Veh,$Arme_AA,$Degats);
								$mes.="Votre tir touche la cible de plein fouet! L'avion s'abat en flammes!  (".$Degats." dégâts)";
								$img=Afficher_Image('images/battle/hitdca.jpg',"images/image.png","Avion abattu!");
							}
							elseif($Degats >10)
							{
								$mes.="Votre tir touche la cible! (".$Degats." dégâts)";
								$img=Afficher_Image('images/hit.jpg',"images/image.png","Touché!");
							}
							else
								$mes.="Votre tir passe non loin de la cible et l'endommage légèrement!";
						}
						else
							$mes.="Votre tir passe loin à côté de la cible!";
						$mes.="(Tir = ".$Shoot." => Taille = ".$Vis_eni." / Meteo = ".$Meteo." / Altitutde = -".$Malus_alt." / Rafale = ".$mult.")";
					}
					else
						$mes.="La cible est trop éloignée à présent, vous la ratez de peu!";
				}
				mysqli_free_result($result);					
			}
			else
				$mes.="La cible est trop éloignée à présent, votre tir part dans les nuages!";
			UpdateData("Regiment_PVP","Stock_Art",1,"ID",$Reg);
			SetData("Regiment_PVP","Visible",1,"ID",$Reg);
		}
		else
			$mes.="Vous annulez votre tir";
		$intro=GetVehiculeIcon($Veh);
	}
	else
		$mes="<p class='lead'>Votre compagnie a été détruite!</p>";
	$titre="DCA";
	if(!$img)$img=Afficher_Image("images/ciel".$Meteo.".jpg","images/image.png","Touché!");
	$menu="<a href='index.php?view=ground_menu_pvp' class='btn btn-default' title='Retour'>Retour au menu</a>";
	include_once('./default.php');
}?>