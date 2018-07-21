<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
include_once('./jfv_include.inc.php');
include_once('./jfv_ground.inc.php');
include_once('./jfv_combat.inc.php');
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 or $OfficierEMID >0)
{	
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium > 0)
	{
        include_once __DIR__ . '/../view/menu_infos.php';
		$Arme=Insec($_POST['arme']);
		$Vehicule=Insec($_POST['cible']);
		$Dist_shoot=Insec($_POST['distance']);
		$Tir_base=Insec($_POST['exp']);
		$Munition=Insec($_POST['mun']);
		$Meteo=Insec($_POST['meteo']);
		$Zone=Insec($_POST['terrain']);
		$Pos_eni=Insec($_POST['position']);	
		if($Pos_eni ==2 or $Pos_eni ==10)
			$Camouflage_eni=4;
		elseif($Pos_eni ==3 or $Pos_eni ==1)
			$Camouflage_eni=2;
		else
			$Camouflage_eni=1;
		//Get Vehicule
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Vehicule'");
		$resultw=mysqli_query($con,"SELECT * FROM Armes WHERE ID='$Arme'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2, MYSQLI_ASSOC))
			{
				$Veh_Nom_eni=$data['Nom'];
				$Pays=$data['Pays'];
				$HP_eni=$data['HP'];
				$HP_ori_eni=$HP_eni;
				$Blindage_eni=$data['Blindage_f'];
				$Vitesse_eni=$data['Vitesse'];
				$Taille_eni=$data['Taille'];
				$mobile_eni=$data['mobile'];
				$Reput_eni=$data['Reput'];
				$Type_eni=$data['Type'];
				$Range_eni=$data['Portee'];
				$Carbu_eni=$data['Carbu_ID'];
				$Categorie_eni=$data['Categorie'];
				if($Carbu_eni ==87 and !$Essence_eni)
					$Move_eni=0;
				elseif($Carbu_eni ==1 and !$Diesel_eni)
					$Move_eni=0;
				elseif(!$Carbu_eni and !$Moral_eni)
					$Move_eni=0;		
				$Cam_eni=$Taille_eni/$Camouflage_eni;
				if(!$Move_eni)
					$Vitesse_eni=0;
				else
				{
					$Vitesse_eni=Get_LandSpeed($Vitesse_eni,$mobile_eni,$Zone,$Pos_eni,$Type_eni);
					if($Flag ==$Pays_eni)
						$Vitesse_eni+=10;
					if(!$Blindage_eni)
						$Blindage_eni=Get_Blindage($Zone,$Cam_eni,0,$Pos_eni);
				}
			}
			mysqli_free_result($result2);
		}
		if($resultw)
		{
			while($dataw=mysqli_fetch_array($resultw, MYSQLI_ASSOC))
			{
				$Arme_Nom=$dataw['Nom'];
				$Arme_Cal=round($dataw['Calibre']);
				$Arme_Multi=$dataw['Multi'];
				$Arme_Dg=$dataw['Degats'];
				$Arme_Perf=$dataw['Perf'];
				$Arme_Range=$dataw['Portee'];
				$Arme_Range_Max=$dataw['Portee_max'];
			}
			mysqli_free_result($resultw);
		}
		$mes.="<h2>Champ de tir</h2>
		<p>L'arme <b>".$Arme_Nom."</b> peut envoyer ".$Arme_Multi." obus de ".$Arme_Cal."mm par tir a une distance maximale de ".$Arme_Range_Max."m et est capable de percer un blindage de ".$Arme_Perf."mm à 500m à l'aide d'une munition standard.
		<br>Sa portée de tir pratique est d'environ ".$Arme_Range."m.</p>
		<p>Tir à l'aide d'un <b>".$Arme_Nom."</b> sur un ".GetVehiculeIcon($Vehicule, $Pays)." à une distance d'environ <b>".$Dist_shoot."m</b></p>";
		if($Munition ==1)
			$Mun_txt="perforant";
		elseif($Munition ==2)
			$Mun_txt="explosif";
		elseif($Munition ==3)
			$Mun_txt="brisant";
		elseif($Munition ==4)
		{
			if($Arme_Cal >=19)
				$Mun_txt="flèche";
			else
			{
				$Mun_txt="perforant";
				$Munition=1;
			}
		}
		elseif($Munition ==5)
		{
			if($Arme_Cal >=69)
				$Mun_txt="HEAT";
			else
			{
				$Mun_txt="classique";
				$Munition=0;
			}
		}
		else
			$Mun_txt="classique";
		$Update_Nbr_eni=0;
		$Update_Reput=0;
		$Update_xp=0;	
		for($t=1;$t<=10;$t++)
		{
			$Tir=mt_rand(0,$Tir_base);
			$Defense_tir=$Vitesse_eni+mt_rand(0,$Tactique_eni)-$Cam_eni-$Meteo;
			if($Tir >1 and ($Tir >$Defense_tir or $Tir ==$Tir_base))
			{
				$Base_Dg=mt_rand($Arme_Cal,$Arme_Dg);
				if($Tir ==$Tir_base)
					$Base_Dg=$Arme_Dg;
				$Dispers=GetShoot($Tir,$Arme_Multi);
				$Degats=$Base_Dg*$Dispers;
				$Degats=round(Get_Dmg($Munition,$Arme_Cal,$Blindage_eni,$Dist_shoot,$Degats,$Arme_Perf,$Arme_Range,$Arme_Range_Max));
				if($Dispers >1)
					$final_txt="nt";
				else
					$final_txt="";
				if($Degats >$HP_eni)
				{
					$mes.="<p><b>Tir ".$t."</b> Votre tir envoie ".$Arme_Multi." obus ".$Mun_txt." de ".$Arme_Cal."mm à une distance de ".$Dist_shoot."m, dont ".$Dispers." touche".$final_txt." la cible en lui occasionnant <b>".$Degats."</b> dégâts! <b>La cible est détruite!</b></p>";
					$HP_eni=$HP_ori_eni;
				}
				elseif($Degats >10)
					$mes.="<p><b>Tir ".$t."</b> Votre tir envoie ".$Arme_Multi." obus ".$Mun_txt." de ".$Arme_Cal."mm à une distance de ".$Dist_shoot."m, dont ".$Dispers." touche".$final_txt." la cible et lui occasionne".$final_txt." <b>".$Degats."</b> dégâts!</p>";
				else
					$mes.="<p><b>Tir ".$t."</b> Votre tir envoie ".$Arme_Multi." obus ".$Mun_txt." de ".$Arme_Cal."mm à une distance de ".$Dist_shoot."m, dont ".$Dispers." touche".$final_txt." la cible, mais le blindage n'a pas été percé!</p>";
			}
			else
				$mes.="<p><b>Tir ".$t."</b> Votre tir envoie ".$Arme_Multi." obus ".$Mun_txt." de ".$Arme_Cal."mm à une distance de ".$Dist_shoot."m, mais tous ratent la cible!</p>";
		}
		echo $mes;
		echo "<br><a href='../index.php?view=pr_tir' class='btn btn-default' title='Recommencer'>Recommencer</a>";
	}
	else
	{
		echo "<table class='table'>
				<tr><td><img src='../images/top_secret.gif'></td></tr>
				<tr><td>Ces données sont classifiées.</td> </tr>
				<tr><td>Votre rang ne vous permet pas d'accéder à ces informations.</td></tr>
			</table>";
	}
}
?>
