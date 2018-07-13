<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_avions.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$con=dbconnecti();
	$resultp=mysqli_query($con,"SELECT Unit,Credits,Avion_Perso,Equipage FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($resultp)
	{
		while($datap=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
		{
			$Unit=$datap['Unit'];
			$Avion_P=$datap['Avion_Perso'];
			$Credits=$datap['Credits'];
			$Equipage=$datap['Equipage'];
		}
		mysqli_free_result($resultp);
	}
	if($Credits >0 and $Avion_P >0)
	{
		$Credits-=1;	
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result=mysqli_query($con,"SELECT Nom,Robustesse,Type,Pays,Plafond,Autonomie,VitesseH,VitesseB,VitesseP,VitesseA,Visibilite,
		ArmePrincipale,Arme1_Nbr,Munitions1,ArmeSecondaire,Arme2_Nbr,Munitions2,Bombe,Bombe_Nbr,Avion_BombeT,Blindage,Cellule,Volets,Moteur,Engine,Navigation,Radar,Radio,Reservoir,
		Voilure,Verriere,Viseur,Camouflage,Baby,Engine_Nbr,Train,Helice,ID_ref FROM Avions_Persos WHERE ID='$Avion_P'");
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
		mysqli_close($con);
		if($results)
		{
			while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
			{
				$Skills_Pil[]=$data['Skill'];
			}
			mysqli_free_result($results);
		}
		if(is_array($Skills_Pil))
		{
			if(in_array(105,$Skills_Pil))
				$Mecano4=true;
			elseif(in_array(104,$Skills_Pil))
				$Mecano3=true;
			elseif(in_array(103,$Skills_Pil))
				$Mecano2=true;
			elseif(in_array(102,$Skills_Pil))
				$Mecano1=true;
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Nom=$data['Nom'];
				$Robustesse=$data['Robustesse'];
				$Avion_Type=$data['Type'];
				$Pays=$data['Pays'];
				$Plafond=$data['Plafond'];
				$Autonomie=$data['Autonomie'];
				$VitesseH=$data['VitesseH'];
				$VitesseB=$data['VitesseB'];
				$VitesseP=$data['VitesseP'];
				$VitesseA=$data['VitesseA'];
				$Visibilite=$data['Visibilite'];
				$Arme1=$data['ArmePrincipale'];
				$Arme2=$data['ArmeSecondaire'];
				$Arme1_nbr=$data['Arme1_Nbr'];
				$Arme2_nbr=$data['Arme2_Nbr'];
				$Munitions1=$data['Munitions1'];
				$Munitions2=$data['Munitions2'];
				$Bombes=$data['Bombe'];
				$Bombes_nbr=$data['Bombe_Nbr'];
				$Avion_BombeT=$data['Avion_BombeT'];
				$Blindage=$data['Blindage'];
				$Cellule=$data['Cellule'];
				$Engine_Nbr=$data['Engine_Nbr'];
				$Helice=$data['Helice'];
				$Train=$data['Train'];
				$Volets=$data['Volets'];
				$Moteur=$data['Moteur'];
				$MoteurP=$data['Engine'];
				$Navi=$data['Navigation'];
				$Radar=$data['Radar'];
				$Radio=$data['Radio'];
				$Reservoir=$data['Reservoir'];
				$Voilure=$data['Voilure'];
				$Verriere=$data['Verriere'];
				$Viseur=$data['Viseur'];
				$Camouflage=$data['Camouflage'];
				$Baby_Actu=$data['Baby'];
				$ID_ref=$data['ID_ref'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		$con=dbconnecti();
		$Avion_xp=mysqli_result(mysqli_query($con,"SELECT Pilotage FROM XP_Avions WHERE PlayerID='$PlayerID' AND AvionID='$ID_ref'"),0);
		mysqli_close($con);
		if(!$Avion_xp)$Avion_xp=0;			
		$Arme1_cal=substr(GetData("Armes","ID",$Arme1,"Calibre"),0,3);
		$Arme1_nom=GetData("Armes","ID",$Arme1,"Nom");
		$Arme2_cal=substr(GetData("Armes","ID",$Arme2,"Calibre"),0,3);
		$Arme2_nom=GetData("Armes","ID",$Arme2,"Nom");
		$filtre_masse=25*$Engine_Nbr;		
		if($Equipage)$Trait_e=GetData("Equipage","ID",$Equipage,"Trait");
		if($Trait_e ==5)$Avion_xp+=25;	
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
		$Camera_low=$Array_Mod[16];
		$Camera_high=$Array_Mod[17];
		$Baby=$Array_Mod[18];
		$Radar_On=$Array_Mod[19];
		$Torpilles=$Array_Mod[20];
		$Mines=$Array_Mod[21];
		$Fret_mun8=$Array_Mod[22];
		$Fret_mun13=$Array_Mod[23];
		$Fret_mun20=$Array_Mod[24];
		$Fret_mun30=$Array_Mod[36];
		$Fret_mun40=$Array_Mod[37];
		$Fret_87=$Array_Mod[25];
		$Fret_100=$Array_Mod[26];
		$Fret_50=$Array_Mod[27];
		$Fret_125=$Array_Mod[28];
		$Fret_250=$Array_Mod[29];
		$Fret_500=$Array_Mod[30];
		$Fret_para=$Array_Mod[31];
		$Bombe1000_nbr=$Array_Mod[32];
		$Bombe2000_nbr=$Array_Mod[33];
		$Flaps=$Array_Mod[34];
		$Rockets=$Array_Mod[35];		
		$Arme8_fus_masse=GetData("Armes","ID",$Arme8_fus,"Masse");
		$Arme8_fus_nom=GetData("Armes","ID",$Arme8_fus,"Nom");
		$Arme8_fus_cal=substr(GetData("Armes","ID",$Arme8_fus,"Calibre"),0,3);
		$Arme8_ailes_masse=GetData("Armes","ID",$Arme8_ailes,"Masse");
		$Arme8_ailes_nom=GetData("Armes","ID",$Arme8_ailes,"Nom");
		$Arme8_ailes_cal=substr(GetData("Armes","ID",$Arme8_ailes,"Calibre"),0,3);
		if($Arme13 !=5)
		{
			$Arme13_masse=GetData("Armes","ID",$Arme13,"Masse");
			$Arme13_cal=substr(GetData("Armes","ID",$Arme13,"Calibre"),0,3);
		}
		if($Arme20 !=5)
		{
			$Arme20_masse=GetData("Armes","ID",$Arme20,"Masse");
			$Arme20_cal=substr(GetData("Armes","ID",$Arme20,"Calibre"),0,3);
		}
		if($Camera_low !=5)
			$Camera_low_masse=GetData("Armes","ID",$Camera_low,"Masse");
		if($Camera_high !=5)
			$Camera_high_masse=GetData("Armes","ID",$Camera_high,"Masse");
		$Arme13_nom=GetData("Armes","ID",$Arme13,"Nom");
		$Arme20_nom=GetData("Armes","ID",$Arme20,"Nom");
		if($Bombes_nbr ==0)
			$Bombes_txt="Aucune";
		else
			$Bombes_txt=$Bombes_nbr."x ".$Bombes."kg";
		switch($Camouflage)
		{
			case 0:
				$Camouflage_txt="Aucun";
			break;
			case 1:
				$Camouflage_txt="Standard";
			break;
			case 2:
				$Camouflage_txt="Tons bleus";
			break;
			case 3:
				$Camouflage_txt="Tons gris";
			break;
			case 4:
				$Camouflage_txt="Tons noirs";
			break;
			case 5:
				$Camouflage_txt="Mixte Noir-Gris";
			break;
			case 6:
				$Camouflage_txt="Mixte Brun-Gris";
			break;
			case 7:
				$Camouflage_txt="Mixte Bleu-Gris";
			break;
			case 8:
				$Camouflage_txt="Mixte Gris-Bleu";
			break;
			case 9:
				$Camouflage_txt="Mixte Gris-Noir";
			break;
			case 10:
				$Camouflage_txt="Mixte Vert-Gris";
			break;
			case 11:
				$Camouflage_txt="Mixte Vert-Bleu";
			break;
			case 12:
				$Camouflage_txt="Mixte Vert-Noir";
			break;
			case 13:
				$Camouflage_txt="Mixte Vert-Brun-Gris";
			break;
			case 14:
				$Camouflage_txt="Mixte Vert-Brun-Bleu";
			break;
			case 15:
				$Camouflage_txt="Mixte Vert-Brun-Noir";
			break;
			case 16:
				$Camouflage_txt="Mixte Vert-Gris-Gris";
			break;
			case 17:
				$Camouflage_txt="Mixte Vert-Gris-Bleu";
			break;
			case 18:
				$Camouflage_txt="Mixte Vert-Noir-Gris";
			break;
			case 19:
				$Camouflage_txt="Mixte Vert-Noir-Bleu";
			break;
			case 20:
				$Camouflage_txt="Mixte Sable-Gris";
			break;
			case 21:
				$Camouflage_txt="Mixte Sable-Bleu";
			break;
			case 22:
				$Camouflage_txt="Mixte Sable-Vert-Gris";
			break;
			case 23:
				$Camouflage_txt="Mixte Sable-Vert-Bleu";
			break;
			case 24:
				$Camouflage_txt="Mixte Sable-Brun-Gris";
			break;
			case 25:
				$Camouflage_txt="Mixte Sable-Brun-Bleu";
			break;
		}		
		if($Cellule <7)
		{
			$Cellule_zero=1;
			$Cellule_un=2;
			$Cellule_deux=3;
			$Cellule_txt_zero="Monocoque Standard";
			$Cellule_txt_un="Monocoque Renforcée";
			$Cellule_txt_deux="Monocoque Affinée";
		}
		elseif($Cellule <10)
		{
			$Cellule_zero=7;
			$Cellule_un=8;
			$Cellule_deux=9;
			$Cellule_txt_zero="Entoilée Standard";
			$Cellule_txt_un="Entoilée Renforcée";
			$Cellule_txt_deux="Entoilée Affinée";
		}
		else
		{
			$Cellule_zero=10;
			$Cellule_un=11;
			$Cellule_deux=12;
			$Cellule_txt_zero="Mixte Standard";
			$Cellule_txt_un="Mixte Renforcée";
			$Cellule_txt_deux="Mixte Affinée";
		}
		$Cel_mod=0;
		switch($Cellule)
		{
			case 0:
				$Cellule_txt="Monocoque Standard";
			break;
			case 1:
				$Cellule_txt="Monocoque Renforcée";
				$Cel_mod=100;
			break;
			case 2:
				$Cellule_txt="Monocoque Affinée";
				$Cel_mod=-100;
			break;
			case 6:
				$Cellule_txt="Entoilée Standard";
			break;
			case 7:
				$Cellule_txt="Entoilée Renforcée";
				$Cel_mod=100;
			break;
			case 8:
				$Cellule_txt="Entoilée Affinée";
				$Cel_mod=-100;
			break;
			case 9:
				$Cellule_txt="Mixte Standard";
			break;
			case 10:
				$Cellule_txt="Mixte Renforcée";
				$Cel_mod=100;
			break;
			case 11:
				$Cellule_txt="Mixte Affinée";
				$Cel_mod=-100;
			break;
		}
		switch($Volets)
		{
			case 0:
				$Volets_txt="Standard";
			break;
			case 1:
				$Volets_txt="Améliorés";
			break;
			case 2:
				$Volets_txt="Automatiques";
			break;
			case 3:
				$Volets_txt="De piqué";
			break;
		}
		switch($Helice)
		{
			case 0:
				$Helice_txt="Pas constant";
			break;
			case 1:
				$Helice_txt="Pas variable manuel";
			break;
			case 2:
				$Helice_txt="Pas variable automatique";
			break;
		}
		$Injection=GetData("Moteur","ID",$MoteurP,"Injection");
		$Compresseur=GetData("Moteur","ID",$MoteurP,"Compresseur");
		$Moteur_Nom='<b>'.GetData("Moteur","ID",$MoteurP,"Nom").'</b>';
		if($Compresseur ==3)
			$Compresseur="Basse altitude";
		elseif($Compresseur ==2)
			$Compresseur="Haute altitude";
		elseif($Compresseur ==1)
			$Compresseur="Compresseur";
		else
			$Compresseur="Pas de compresseur";
		$Moteur_Nom.=" (".$Compresseur.")";		
		if($Injection)
			$Moteur_sup="Injection";
		else
			$Moteur_sup="Carburateur";
		switch($Moteur)
		{
			case 0:
				$Moteur_txt="De série";
			break;
			case 1:
				$Moteur_txt="Calibré";
			break;
			case 2:
				$Moteur_txt="Haut indice d'octane";
			break;
			case 3:
				$Moteur_txt="Compresseur suralimenté";
			break;
			case 4:
				$Moteur_txt="Dispositif de surpuissance";
			break;
			case 5:
				$Moteur_txt="Refroidissement amélioré";
			break;
			case 6:
				$Moteur_txt=$Moteur_sup." amélioré";
			break;
			case 7:
				$Moteur_txt="Filtre anti-sable";
			break;
		}
		switch($Navi)
		{
			case 0:
				$Navi_txt="De série";
			break;
			case 1:
				$Navi_txt="Améliorée";
			break;
			case 2:
				$Navi_txt="A la pointe";
			break;
			case 3:
				$Navi_txt="Gyroscopique";
			break;
			default:
				$Navi_txt="Inconnue";
			break;
		}
		switch($Radar)
		{
			case 0:
				$Radar_txt="Aucun";
			break;
			case 10:
				$Radar_txt="Radar décimétrique primitif";
			break;
			case 20:
				$Radar_txt="Radar décimétrique amélioré";
			break;
			case 30:
				$Radar_txt="Radar décimétrique évolué";
			break;
			case 40:
				$Radar_txt="Radar centimétrique";
			break;
			case 50:
				$Radar_txt="Radar centimétrique amélioré";
			break;
			case 60:
				$Radar_txt="Radar centimétrique évolué";
			break;
			default:
				$Radar_txt="Radar inconnu";
			break;
		}
		switch($Radio)
		{
			case 0:
				$Radio_txt="De série";
			break;
			case 1:
				$Radio_txt="Radio améliorée";
			break;
			case 2:
				$Radio_txt="Radio longue portée";
			break;
			case 3:
				$Radio_txt="Contre-mesures";
			break;
			default:
				$Radio_txt="Radio inconnue";
			break;
		}
		//$PareBrise=GetData("Avions_Persos","ID",$Avion_P,"PareBrise");
		switch($PareBrise)
		{
			case 0:
				$PareBrise_txt="Plexiglas";
			break;
			case 8:
				$PareBrise_txt="Blindé 8mm";
			break;
			case 12:
				$PareBrise_txt="Blindé 12mm";
			break;
			case 16:
				$PareBrise_txt="Blindé 16mm";
			break;
		}
		switch($Reservoir)
		{
			case 0:
				$Reservoir_txt="Standard";
			break;
			case 1:
				$Reservoir_txt="Auto-obturant";
			break;
			case 2:
				$Reservoir_txt="Grande capacité";
			break;
			case 3:
				$Reservoir_txt="Très grande capacité";
			break;
		}
		if($Baby_Actu ==0)
			$Baby_txt="Aucun";
		else
			$Baby_txt="Réservoir externe (".$Baby_Actu." l)";
		if($Train <7)
		{
			$Train_zero=1;
			$Train_un=2;
			$Train_deux=3;
			$Train_txt_zero="Escamotable manuel";
			$Train_txt_un="Escamotable hydraulique";
			$Train_txt_un_ext=" (+10kg ; Atterrissage/Décollage +10)";
			$Train_txt_deux="Escamotable renforcé";
			$Train_txt_deux_ext=" (+25kg ; Atterrissage/Décollage +25)";
		}
		elseif($Train ==13)
		{
			$Train_zero=0;
			$Train_un=0;
			$Train_deux=0;
			$Train_txt_zero="Coque standard";
			$Train_txt_un="Coque standard";
			$Train_txt_un_ext="";
			$Train_txt_deux="";
			$Train_txt_deux_ext="";
		}
		elseif($Train ==16)
		{
			$Train_zero=0;
			$Train_un=0;
			$Train_deux=0;
			$Train_txt_zero="Flotteurs de série";
			$Train_txt_un="Flotteurs de série";
			$Train_txt_un_ext="";
			$Train_txt_deux="";
			$Train_txt_deux_ext="";
		}
		else
		{
			$Train_zero=7;
			$Train_un=8;
			$Train_deux=9;
			$Train_txt_zero="Fixe";
			$Train_txt_un="Fixe renforcé";
			$Train_txt_un_ext=" (+10kg ; Atterrissage/Décollage +10)";
			$Train_txt_deux="Fixe caréné";
			$Train_txt_deux_ext=" (+25kg ; Vitesse +5 ; Atterrissage/Décollage -5)";
		}
		switch($Train)
		{
			case 0:
				$Train_txt="Escamotable manuel";
			break;
			case 1:
				$Train_txt="Escamotable hydraulique";
			break;
			case 2:
				$Train_txt="Escamotable renforcé";
			break;
			case 7:
				$Train_txt="Fixe";
			break;
			case 8:
				$Train_txt="Fixe renforcé";
			break;
			case 9:
				$Train_txt="Fixe caréné";
			break;
			case 13:
				$Train_txt="Coque";
			break;
			case 16:
				$Train_txt="Flotteurs";
			break;
		}
		if($Voilure <6)
		{
			$Voilure_zero=1;
			$Voilure_un=2;
			$Voilure_deux=3;
			$Voilure_trois=4;
			$Voilure_txt_zero="Cantilever Standard";
			$Voilure_txt_un="Cantilever Raccourcie";
			$Voilure_txt_deux="Cantilever Allongée";
			$Voilure_txt_trois="Cantilever Fine";
		}
		else
		{
			$Voilure_zero=6;
			$Voilure_un=7;
			$Voilure_deux=8;
			$Voilure_trois=9;
			$Voilure_txt_zero="Haubanée Standard";
			$Voilure_txt_un="Haubanée Raccourcie";
			$Voilure_txt_deux="Haubanée Allongée";
			$Voilure_txt_trois="Haubanée Fine";
		}
		switch($Voilure)
		{
			case 0:
				$Voilure_txt="Cantilever Standard";
			break;
			case 1:
				$Voilure_txt="Cantilever Raccourcie";
			break;
			case 2:
				$Voilure_txt="Cantilever Allongée";
			break;
			case 3:
				$Voilure_txt="Cantilever Fine";
			break;
			case 5:
				$Voilure_txt="Haubanée Standard";
			break;
			case 6:
				$Voilure_txt="Haubanée Raccourcie";
			break;
			case 7:
				$Voilure_txt="Haubanée Allongée";
			break;
			case 8:
				$Voilure_txt="Haubanée Fine";
			break;
		}
		switch($Verriere)
		{
			case 0:
				$Verriere_txt="Standard";
			break;
			case 1:
				$Verriere_txt="Bombée";
			break;
			case 2:
				$Verriere_txt="Améliorée";
			break;
			case 3:
				$Verriere_txt="Goutte d'eau";
			break;
		}
		switch($Viseur)
		{
			case 0:
				$Viseur_txt="A réflexion standard";
			break;
			case 1:
				$Viseur_txt="De chasse";
			break;
			case 2:
				$Viseur_txt="D'attaque";
			break;
			case 3:
				$Viseur_txt="De bombardement";
			break;
			case 4:
				$Viseur_txt="Gyroscopique";
			break;
		}
		switch($Munitions1)
		{
			case 0:
				$Munitions1_txt="Standard";
			break;
			case 1:
				$Munitions1_txt="AP";
			break;
			case 2:
				$Munitions1_txt="HE";
			break;
			case 3:
				$Munitions1_txt="I";
			break;
			case 4:
				$Munitions1_txt="APHE";
			break;
			case 5:
				$Munitions1_txt="API";
			break;
		}
		switch($Munitions2)
		{
			case 0:
				$Munitions2_txt="Standard";
			break;
			case 1:
				$Munitions2_txt="AP";
			break;
			case 2:
				$Munitions2_txt="HE";
			break;
			case 3:
				$Munitions2_txt="I";
			break;
			case 4:
				$Munitions2_txt="APHE";
			break;
			case 5:
				$Munitions2_txt="API";
			break;
		}
		$Robustesse_Max=GetData("Avion","ID",$ID_ref,"Robustesse")+$Cel_mod;
?>
<h1>Hangar de votre avion personnel</h1>
<h2><?echo $Nom;?></h2>
<div class='row'><div class='col-md-6'><?echo Afficher_Image('images/coupe'.$ID_ref.'.gif','images/avions/garage'.$ID_ref.'.jpg',$Nom);?></div><div class='col-md-6'><?echo "<b>Expérience sur ce modèle</b> <a href='#' class='popup'><img src='images/help.png'><span>Plus votre pilote sera expérimenté, plus il aura accès à différentes options</span></a><br>".round($Avion_xp)." Heures de vol";?></div></div>
<form action="garage1.php" method="post">
<input type='hidden' name='avion' value="<?echo $Avion_P;?>">
<input type='hidden' name='ref' value="<?echo $ID_ref;?>">
<input type='hidden' name='robmax' value="<?echo $Robustesse_Max;?>">
<table class='table'><thead><tr>
	<?
	$Base=GetData("Unit","ID",$Unit,"Base");
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Zone,Latitude,Longitude,Citernes,Camions,Port,Port_Ori,NoeudF,NoeudF_Ori,Flag FROM Lieu WHERE ID='$Base'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Zone=$data['Zone'];
			$Base_Lat=$data['Latitude'];
			$Base_Long=$data['Longitude'];
			$Citernes=$data['Citernes'];
			$Camions=$data['Camions'];
			$Port_ori_base=$data['Port_Ori'];
			$Gare_ori_base=$data['NoeudF_Ori'];
			$Flag=$data['Flag'];
		}
		mysqli_free_result($result);
	}
	if($Port_ori_base)
		$Port_base=$data['Port'];
	else
		$Port_base=100;
	if($Gare_ori_base)
		$Gare_base=$data['NoeudF'];
	else
		$Gare_base=100;
	if($Port_base !=100 and $Port_base >$Gare_base)
		$Inf_base=$Port_base;
	elseif($Gare_base !=100 and $Gare_base >$Port_base)
		$Inf_base=$Gare_base;
	else
		$Inf_base=100;
	//Outre-Mer ou anglais
	if($Base_Lat <38.2 or $Base_Long >70 or $country ==2 or $country ==9 or $Zone ==6)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT COUNT(*),SUM(Port) FROM Lieu WHERE Flag='$Flag' AND Port_Ori >0 AND Flag_Port='$Flag'");
		$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$Flag' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$Flag'");
		mysqli_close($con);
		if($result)
		{
			if($data=mysqli_fetch_array($result,MYSQLI_NUM))
			{
				if($data[0] >0)
					$Efficacite_ravit_port=round($data[1]/$data[0]);
				else
					$Efficacite_ravit_port=0;
			}
		}
		if($result2)
		{
			if($data=mysqli_fetch_array($result2,MYSQLI_NUM))
			{
				if($data[0] >0)
					$Efficacite_ravit=round($data[1]/$data[0]);
				else
					$Efficacite_ravit=0;
			}
		}
		$Efficacite_ravit=round(($Efficacite_ravit+($Efficacite_ravit_port*2))/3);
	}
	else
	{
		$Lat_base_min=$Base_Lat-1;
		$Lat_base_max=$Base_Lat+1;
		$Long_base_min=$Base_Long-3;
		$Long_base_max=$Base_Long+3;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$country'");
		$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND Flag_Gare='$country' 
		AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max')");
		mysqli_close($con);
		if($result)
		{
			if($data=mysqli_fetch_array($result,MYSQLI_NUM))
			{
				if($data[0] >0)
					$Efficacite_ravit1=round($data[1]/$data[0]);
				else
					$Efficacite_ravit1=0;
			}
			mysqli_free_result($result);
		}
		if($result2)
		{
			if($data2=mysqli_fetch_array($result2,MYSQLI_NUM))
			{
				if($data2[0] >0)
					$Efficacite_ravit2=round($data2[1]/$data2[0]);
				else
					$Efficacite_ravit2=0;
			}
			mysqli_free_result($result2);
		}
		$Efficacite_ravit=round(($Efficacite_ravit1+($Efficacite_ravit2*2))/3);
	}			
	//Malus ravitaillement par saison ou terrain
	$Saison=GetSaison($Date_Campagne);
	if($Base_Long >20 and $Base_Lat >43)		//Front Est
	{
		if($Saison ==2)	// Printemps (boue dégel)
		{
			$Citernes +=20;
			$Camions +=20;
		}
		elseif($Saison ==1) // Automne
		{
			$Citernes +=10;
			$Camions +=10;
		}
		elseif($Saison ==0) // Hiver
		{
			$Citernes +=25;
			$Camions +=25;
		}
	}
	elseif($Base_Lat >55) // Europe du nord
	{
		if($Saison ==0) // Hiver
		{
			$Citernes +=25;
			$Camions +=25;
		}
	}
	elseif($Base_Lat >43) // Europe continentale
	{
		if($Saison ==0) // Hiver
		{
			$Citernes +=10;
			$Camions +=10;
		}
	}
	elseif($Base_Lat <33) // Désert
	{
		if($Saison ==3) // Ete (chaleur, pannes)
		{
			$Citernes +=5;
			$Camions +=5;
		}
	}
	if($Zone ==5 or $Zone ==9 or $Zone ==11)
	{
		$Citernes +=20;
		$Camions +=20;
	}
	elseif($Zone ==4)
	{
		$Citernes +=15;
		$Camions +=15;
	}
	elseif($Zone ==3)
	{
		$Citernes +=10;
		$Camions +=10;
	}
	elseif($Zone ==2 or $Zone ==8)
	{
		$Citernes +=5;
		$Camions +=5;
	}
	$Efficacite_ravit=($Efficacite_ravit-$Camions)*($Inf_base/100);
	if($Efficacite_ravit <0)$Efficacite_ravit=0;
	$malus_effic=($Efficacite_ravit/100);
	if($malus_effic)
		$malus_ravit=round(1/$malus_effic);
	else
		$malus_ravit=24;
	if($malus_ravit <1)$malus_ravit=1;			
	if($Robustesse <$Robustesse_Max)
	{
		if($malus_effic >0)
		{
			if($Mecano4)
			{
				$coutb_1=1;
				$coutb_2=2;
				$coutb_3=3;
				$coutb_4=4;
				$coutb_5=5;
			}
			elseif($Mecano2)
			{
				$coutb_1=1;
				$coutb_2=3;
				$coutb_3=5;
				$coutb_4=6;
				$coutb_5=8;
			}
			else
			{
				$coutb_1=2;
				$coutb_2=4;
				$coutb_3=6;
				$coutb_4=8;
				$coutb_5=10;
			}
			$cout_1=round($coutb_1/$malus_effic);
			$cout_2=round($coutb_2/$malus_effic);
			$cout_3=round($coutb_3/$malus_effic);
			$cout_4=round($coutb_4/$malus_effic);
			$cout_5=round($coutb_5/$malus_effic);
			$Rob_diff=$Robustesse_Max-$Robustesse;
			if($Equipage)
			{
				$Meca=GetData("Equipage","ID",$Equipage,"Mecanique");
				$Mult=1-($Meca/5000);
				$Rob_Credits=round((($Rob_diff/200*$Mult)+1)/$malus_effic);
			}
			else
				$Rob_Credits=ceil(($Rob_diff/200)/$malus_effic);
		}
		else
		{
			if($Mecano4)
			{
				$cout_1=2;
				$cout_2=4;
				$cout_3=6;
				$cout_4=8;
				$cout_5=10;
				$Rob_Credits=10;
			}
			elseif($Mecano2)
			{
				$cout_1=4;
				$cout_2=8;
				$cout_3=12;
				$cout_4=16;
				$cout_5=20;
				$Rob_Credits=20;
			}
			else
			{
				$cout_1=8;
				$cout_2=16;
				$cout_3=24;
				$cout_4=32;
				$cout_5=40;
				$Rob_Credits=40;
			}
		}
		if($Rob_Credits <1)$Rob_Credits=40;
	?>
		<th>Robustesse</th>
		<th>Réparer <a href='#' class='popup'><img src='images/help.png'><span>Répare la robustesse. Le coût est modifié par la compétence Mécanique de votre équipage de même que par le niveau de fourniture des pièces</span></a></th>
	<?}?>
		<th>Mettre au standard de série <a href='#' class='popup'><img src='images/help.png'><span>Réinitialise toutes les options au standard du modèle de série</span></a></th>
		<th>Supprimer cet avion</th>
	</tr></thead>
	<input type='hidden' name='ravit' value="<?echo $malus_ravit;?>">
	<tr>
	<?
	if($Robustesse <$Robustesse_Max)
	{
	?>
		<td><?echo $Robustesse." / ".$Robustesse_Max;?></td>
		<td>
			<select name="reparer" style="width: 150px">
					<option value='0'>Ne rien changer</option>
					<?if($Rob_Credits and $Credits >$Rob_Credits-1){?>
					<option value='<?echo $Rob_Credits?>'>+<?echo $Rob_diff;?> (<?echo $Rob_Credits;?> Crédits)</option>
					<?}if($Rob_diff >199 and $Credits >$cout_1){?>
					<option value='<?echo $cout_1;?>'>+200 (<?echo $cout_1;?> Crédit)</option>
					<?}if($Rob_diff >399 and $Credits >$cout_2){?>
					<option value='<?echo $cout_2;?>'>+400 (<?echo $cout_2;?> Crédits)</option>
					<?}if($Rob_diff >599 and $Credits >$cout_3){?>
					<option value='<?echo $cout_3;?>'>+600 (<?echo $cout_3;?> Crédits)</option>
					<?}if($Rob_diff >799 and $Credits >$cout_4){?>
					<option value='<?echo $cout_4;?>'>+800 (<?echo $cout_4;?> Crédits)</option>
					<?}if($Rob_diff >999 and $Credits >$cout_5){?>
					<option value='<?echo $cout_5;?>'>+1000 (<?echo $cout_5;?> Crédits)</option>
					<?}?>
			</select>
		</td>
	<?}?>
		<td>
			<Input type='Radio' name='init' value='0' checked>- Non
			<Input type='Radio' name='init' value='1'>- Oui
		</td>
		<td>
			<Input type='Radio' name='del' value='0' checked>- Non
			<Input type='Radio' name='del' value='1'>- Oui
		</td>
	</tr></table>
	<hr>
	<table class='table table-striped'><thead><tr><th colspan="4">Structure</th></tr></thead>
	<tr>
		<th>Cellule</th><th></th>
		<th>Hyper-sustentateurs</th><th></th>
	</tr>
	<tr>
		<td><?echo $Cellule_txt;?></td>
		<td align="left">
			<select name="cellule" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='<?echo $Cellule_zero;?>' disabled><?echo $Cellule_txt_zero;?></option>
				<?if($Avion_xp >1000){?>
				<option value='<?echo $Cellule_un;?>' disabled><?echo $Cellule_txt_un;?> (200kg ; Robustesse +100)</option>
				<option value='<?echo $Cellule_deux;?>' disabled><?echo $Cellule_txt_deux;?> (-200kg ; Robustesse -100 ; Vitesse Piqué -10%)</option>
				<?}?>
			</select>
		</td>
		<td><?echo $Volets_txt;?></td>
		<td align="left">
			<select name="volets" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Avion_xp >10 or $Mecano1){?>
				<option value='1'>Standard</option>
				<?}if($Flaps and ($Avion_xp >10 or $Mecano1)){?>
				<option value='2'>Améliorés (50kg ; permet 2 crans)</option>
				<?}if($Flaps >1 and ($Avion_xp >50 or $Mecano3)){?>
				<option value='3'>Automatiques (100kg ; permet 3 crans, Manoeuvrabilité +5, Maniabilité +5)</option>
				<?}if($Flaps >2 and ($Avion_xp >10 or $Mecano1)){?>
				<option value='4'>De piqué (200kg ; permet 4 crans, Manoeuvrabilité basse +10, Maniabilité +5)</option>
				<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Voilure</th><th></th>
		<th>Train</th><th></th>
	</tr>
	<tr>
		<td><?echo $Voilure_txt;?></td>
		<td align="left">
			<select name="voilure" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='<? echo $Voilure_zero;?>'><?echo $Voilure_txt_zero;?></option>
				<?if($Avion_xp >100 or $Mecano4){?>
				<option value='<? echo $Voilure_un;?>'><?echo $Voilure_txt_un;?> (-50kg, Vitesse basse +10, Vitesse haute -10, Visibilité -2)</option>
				<?}if($Avion_xp >100 or $Mecano4){?>
				<option value='<? echo $Voilure_deux;?>'><?echo $Voilure_txt_deux;?> (50kg, Vitesse haute +10, Vitesse basse -10, Visibilité +2)</option>
				<?}if($Avion_xp >1000){?>
				<option value='<? echo $Voilure_trois;?>' disabled><?echo $Voilure_txt_trois;?> (-100kg, Vitesse +10, Autonomie -100, Armement secondaire désactivé)</option>
				<?}?>
			</select>
		</td>
		<td><?echo $Train_txt;?></td>
		<td align="left">
			<select name="train" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Avion_xp >10 or $Mecano1){?>
				<option value='<?echo $Train_zero;?>'><?echo $Train_txt_zero;?></option>
				<option value='<?echo $Train_un;?>'><?echo $Train_txt_un.$Train_txt_un_ext;?></option>
				<option value='<?echo $Train_deux;?>'><?echo $Train_txt_deux.$Train_txt_deux_ext;?></option>
				<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Verrière</th><th></th>
		<th>Cockpit</th><th></th>
	</tr>
	<tr>
		<td><?echo $Verriere_txt;?></td>
		<td align="left">
			<select name="verriere" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<?if($Avion_xp >25 or $Mecano3){?>
				<option value='2'>Bombée (Détection +5)</option>
				<?}if($Date_Campagne >"1940-08-01" and ($Avion_xp >50 or $Mecano4)){?>
				<option value='3'>Améliorée (Détection +10)</option>
				<?}if($Date_Campagne >"1942-01-01" and $Avion_xp >100){?>
				<option value='4'>Goutte d'eau (Détection +30)</option>
				<?}?>
			</select>
		</td>
		<td><?echo $PareBrise_txt;?></td>
		<td align="left">
			<select name="cockpit" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>Blindage</th><th></th>
		<th>Réservoir interne</th><th></th>
	</tr>
	<tr>
		<td><?echo $Blindage;?>mm</td>
		<td align="left">
			<select name="blindage" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Avion_xp >10 or $Mecano1){?>
				<option value='1'>Aucun</option>
				<?}if($Avion_xp >10 or $Mecano1){?>
				<option value='2'>8mm (200kg)</option>
				<?}if($Avion_xp >25 or $Mecano3){?>
				<option value='3'>13mm (325kg)</option>
				<?}if($Avion_xp >50 or $Mecano4){?>
				<option value='4'>16mm (400kg)</option>
				<?}if($Avion_xp >75){?>
				<option value='5'>19mm (475kg)</option>
				<?}if($Avion_xp >100 and $Avion_Type >1){?>
				<option value='6'>22mm (550kg)</option>
				<?}if($Avion_xp >125 and ($Avion_Type >1 or $Mecano3)){?>
				<option value='7'>25mm (625kg)</option>
				<?}?>
			</select>
		</td>
		<td><?echo $Reservoir_txt;?></td>
		<td align="left">
			<select name="reservoir" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<?if($Avion_xp >50 or $Mecano3){?>
				<option value='2'>Auto-obturant (Résistance partielle aux balles incendiaires)</option>
				<?}if(($Bombe500_nbr >0 or $Bombe250_nbr >0 or $Bombe125_nbr >0) and $Avion_xp >50){?>
				<option value='3'>Grande capacité (200kg ; Autonomie +200)</option>
				<?}if($Bombe500_nbr >0 and $Avion_xp >100){?>
				<option value='4'>Très grande capacité (500kg ; Autonomie +500)</option>
				<?}?>
			</select>
		</td>		
	</tr></table>
	<table class='table table-striped'><thead><tr><th colspan="4">Moteur <?echo $Moteur_Nom;?></th></tr></thead>
	<tr>
		<th>Moteur</th><th></th>
		<th>Hélice</th><th></th>
	</tr>
	<tr>
		<td><?echo $Moteur_txt;?></td>
		<td align="left">
			<select name="moteur" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>De série</option>
				<?if($Avion_xp >10 or $Mecano1){?>
				<option value='2'>Calibré (Puissance +5; Incidents -5%)</option>
				<?}if($Avion_xp >50 or $Mecano3){?>
				<option value='3'>Haut indice d'octane (Puissance +10; Décollage +5; Autonomie -100; Incidents -5%)</option>
				<?}
				$Compresseur=GetData("Moteur","ID",$MoteurP,"Compresseur");
				if($Compresseur and $Avion_xp >100)
				{?>
				<option value='4'>Compresseur suralimenté (100kg; Puissance +50; Plafond +250; Autonomie -50; Incidents +5%)</option>
				<?}				
				$Boost=GetData("Moteur","ID",$MoteurP,"Boost");
				if($Boost and $Avion_xp >100)
				{?>
				<option value='5'>Système de surpuissance (200kg; Boost Puissance temporaire; Autonomie -200; Incidents +10%)</option>
				<?}if($Avion_xp >10 or $Mecano1){?>
				<option value='6'>Refroidissement amélioré (100kg; Incidents -10%)</option>
				<?}if($Avion_xp >100 or $Mecano4){?>
				<option value='7'><?echo $Moteur_sup;?> amélioré (250kg; Puissance +100; Autonomie -100)</option>
				<?}?>
				<option value='8'>Filtre anti-sable (<?echo $filtre_masse;?>kg; Incidents -50% dans le désert)</option>
			</select>
		</td>
		<td><?echo $Helice_txt;?></td>
		<td align="left">
			<select name="helice" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Avion_xp >10 or $Mecano1){?>
				<option value='1'>Pas constant</option>
				<option value='2'>Pas variable manuel (10kg ; Atterrissage/Décollage +5, Autonomie +50)</option>
				<?}if($Avion_xp >50 or $Mecano3){?>
				<option value='3'>Pas variable automatique (25kg ; Atterrissage/Décollage +10, Autonomie +100, Plafond +250)</option>
				<?}?>
			</select>
		</td>
	</tr></table>
	<table class='table table-striped'><thead><tr><th colspan="4">Armement</th></tr></thead>
	<tr>
		<th>Arme Principale</th><th></th>
		<th>Arme Secondaire</th><th></th>
	</tr>
	<tr>
		<td><?echo $Arme1_nbr." ".$Arme1_nom." (".$Arme1_cal."mm)";?></td>
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
		<td><?echo $Arme2_nbr." ".$Arme2_nom." (".$Arme2_cal."mm)";?></td>
		<td align="left">
			<select name="arme2" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Arme8_ailes_nbr >0)
				{?>
				<option value='1'><?echo $Arme8_ailes_nbr." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*$Arme8_ailes_nbr;?>kg)</option>
				<?}if($Arme8_ailes_max >$Arme8_ailes_nbr and $Arme8_ailes_max ==4)
				{?>
				<option value='2'><?echo "4 ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*4;?>kg)</option>
				<?}if($Arme8_ailes_max >$Arme8_ailes_nbr and $Arme8_ailes_max ==6)
				{?>
				<option value='3'><?echo "6 ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*6;?>kg)</option>
				<?}if($Arme8_ailes_max >$Arme8_ailes_nbr and $Arme8_ailes_max ==8)
				{?>
				<option value='16'><?echo "8 ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*8;?>kg)</option>
				<?}if($Arme8_ailes_max >$Arme8_ailes_nbr and $Arme8_ailes_max >8)
				{?>
				<option value='10'><?echo $Arme8_ailes_max." ".$Arme8_ailes_nom." (".$Arme8_ailes_cal."mm)";?> (<?echo $Arme8_ailes_masse*$Arme8_ailes_max;?>kg)</option>
				<?}if($Arme13_ailes_nbr >0)
				{?>
				<option value='4'><?echo $Arme13_ailes_nbr." ".$Arme13_nom." (".$Arme13_cal."mm)";?> (<?echo $Arme13_masse*$Arme13_ailes_nbr;?>kg)</option>
				<?}if($Arme13_ailes_max >$Arme13_ailes_nbr)
				{?>
				<option value='5'><?echo $Arme13_ailes_max." ".$Arme13_nom." (".$Arme13_cal."mm)";?> (<?echo $Arme13_masse*$Arme13_ailes_max;?>kg)</option>
				<?}
				if($Arme20_ailes_nbr >0)
				{?>
				<option value='6'><?echo $Arme20_ailes_nbr." ".$Arme20_nom." (".$Arme20_cal."mm)";?> (<?echo $Arme20_masse*$Arme20_ailes_nbr;?>kg)</option>
				<?}
				if($Camera_low !=5)
				{?>
				<option value='8'>1 Caméra portative (<?echo $Camera_low_masse;?>kg ; Basse altitude uniquement)</option>
				<?}
				if($Camera_high !=5)
				{?>
				<option value='9'>1 Caméra fixe (<?echo $Camera_high_masse;?>kg)</option>
				<?}?>
				<option value='7'>Aucune</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>Munitions Arme Principale</th><th></th>
		<th>Munitions Arme Secondaire</th><th></th>
	</tr>
	<tr>
		<td><?echo $Munitions1_txt;?></td>
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
		<td><?echo $Munitions2_txt;?></td>
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
		<th>Bombes</th><th></th>
		<th>Type de Bombes</th><th></th>
	</tr>
	<tr>
		<td><?echo $Bombes_txt;?></td>
		<td align="left">
			<select name="bombes" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Aucune</option>
				<?
				if($Bombe50_nbr >0)
				{
					for($ib=1;$ib<=$Bombe50_nbr;$ib++)
					{
						$ibn="50_".$ib;
						$bombe_kg=$ib*50;
						$bombes_combo50_txt.="<option value='".$ibn."'>".$ib." bombes de 50kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo50_txt;
				}
				if($Bombe125_nbr >0)
				{					
					for($ib=1;$ib<=$Bombe125_nbr;$ib++)
					{
						$ibn="125_".$ib;
						$bombe_kg=$ib*125;
						$bombes_combo125_txt.="<option value='".$ibn."'>".$ib." bombes de 125kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo125_txt;
				}
				if($Bombe250_nbr >0)
				{
					for($ib=1;$ib<=$Bombe250_nbr;$ib++)
					{
						$ibn="250_".$ib;
						$bombe_kg=$ib*250;
						$bombes_combo250_txt.="<option value='".$ibn."'>".$ib." bombes de 250kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo250_txt;
				}
				if($Bombe500_nbr >0)
				{
					for($ib=1;$ib<=$Bombe500_nbr;$ib++)
					{
						$ibn="500_".$ib;
						$bombe_kg=$ib*500;
						$bombes_combo500_txt.="<option value='".$ibn."'>".$ib." bombes de 500kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo500_txt;
				}
				if($Bombe1000_nbr >0)
				{
					for($ib=1;$ib<=$Bombe1000_nbr;$ib++)
					{
						$ibn="1000_".$ib;
						$bombe_kg=$ib*1000;
						$bombes_combo1000_txt.="<option value='".$ibn."'>".$ib." bombes de 1000kg(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo1000_txt;
				}
				if($Torpilles >0)
				{
					for($ib=1;$ib<=$Torpilles;$ib++)
					{
						$ibn="800_".$ib;
						$bombe_kg=$ib*800;
						$bombes_combo800_txt.="<option value='".$ibn."'>".$ib." torpille(s)(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo800_txt;
				}
				if($Mines >0)
				{
					for($ib=1;$ib<=$Mines;$ib++)
					{
						$ibn="300_".$ib;
						$bombe_kg=$ib*300;
						$bombes_combo300_txt.="<option value='".$ibn."'>".$ib." charge(s)(".$bombe_kg."kg)</option>";
						$ibn="400_".$ib;
						$bombe_kg=$ib*400;
						$bombes_combo400_txt.="<option value='".$ibn."'>".$ib." mine(s)(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo300_txt;
					echo $bombes_combo400_txt;
				}
				if($Rockets >0)
				{
					for($ib=1;$ib<=$Rockets;$ib++)
					{
						$ibn="80_".$ib;
						$bombe_kg=$ib*80;
						$bombes_combo80_txt.="<option value='".$ibn."'>".$ib." rocket(s)(".$bombe_kg."kg)</option>";
					}
					echo $bombes_combo80_txt;
				}
				/*if($Bombe50_nbr >0)
				{?>
				<option value='2'><?echo $Bombe50_nbr." bombes de 50kg";?> (<?echo $Bombe50_nbr*50;?>kg)</option>
				<?}
				if($Bombe125_nbr >0)
				{?>
				<option value='3'><?echo $Bombe125_nbr." bombes de 125kg";?> (<?echo $Bombe125_nbr*125;?>kg)</option>
				<?}
				if($Bombe250_nbr >0)
				{?>
				<option value='4'><?echo $Bombe250_nbr." bombes de 250kg";?> (<?echo $Bombe250_nbr*250;?>kg)</option>
				<?}
				if($Bombe500_nbr >0)
				{?>
				<option value='5'><?echo $Bombe500_nbr." bombes de 500kg";?> (<?echo $Bombe500_nbr*500;?>kg)</option>
				<?}
				if($Bombe1000_nbr >0)
				{?>
				<option value='6'><?echo $Bombe1000_nbr." bombes de 1000kg";?> (<?echo $Bombe1000_nbr*1000;?>kg)</option>
				<?}
				if($Torpilles >0)
				{?>
				<option value='9'><?echo $Torpilles." torpille(s)";?> (<?echo $Torpilles*800;?>kg)</option>
				<?}
				if($Mines >0)
				{?>
				<option value='10'><?echo $Mines." mine(s)";?> (<?echo $Mines*400;?>kg)</option>
				<?}
				if($Camera_low !=5)
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
					if($Fret_mun30 >0)
					{?>
					<option value='22'><? echo $Fret_mun30*3000; echo " munitions de 30mm";?> (<?echo $Fret_mun30*1000;?>kg)</option>
					<?}
					if($Fret_mun40 >0)
					{?>
					<option value='23'><? echo $Fret_mun40*1500; echo " munitions de 40mm";?> (<?echo $Fret_mun40*1000;?>kg)</option>
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
		<td><?echo GetBombeT($Avion_BombeT);?></td>
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
	<table class='table table-striped'><thead><tr><th colspan="4">Equipements</th></tr></thead>
	<tr>
		<th>Radio</th><th></th>
		<th>Navigation</th><th></th>
	</tr>
	<tr>
		<td><?echo $Radio_txt;?></td>
		<td align="left">
			<select name="radio" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Avion_xp >10 or $Mecano1){?>
				<option value='1'>De série</option>
				<option value='2'>Améliorée (100kg)</option>
				<?}if($Avion_xp >25 or $Mecano3){?>
				<option value='3'>Longue portée (200kg)</option>
				<?}if($Date_Campagne >"1942-01-01" and $Avion_xp >50){?>
				<option value='4'>Contre-mesures (300kg)</option>
				<?}?>
			</select>
		</td>
		<td><?echo $Navi_txt;?></td>
		<td align="left">
			<select name="navi" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<?if($Avion_xp >10 or $Mecano1){?>
				<option value='1'>De série</option>
				<?}if($Avion_xp >25 or $Mecano3){?>
				<option value='2'>Amélioré (200kg)</option>
				<?}if($Date_Campagne >"1944-01-01" and $Avion_xp >50){?>
				<option value='4'>Gyroscopique</option>
				<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Camouflage</th><th></th>
		<th>Viseur</th><th></th>
	</tr>
	<tr>
		<td><?echo $Camouflage_txt;?></td>
		<td align="left">
			<select name="camouflage" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Standard</option>
				<?if($Avion_xp >10 or $Mecano1){?>
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
				<?}?>
			</select>
		</td>
		<td><?echo $Viseur_txt;?></td>
		<td align="left">
			<select name="viseur" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>A réflexion standard</option>
				<?if($Avion_xp >10 or $Mecano1){?>
				<option value='2'>De chasse (Tir air-air +10, Bombardement -10)</option>
				<option value='3'>D'attaque (Tir air-sol +10, Tir air-air -10)</option>
				<option value='4'>De bombardement (Bombardement +10, Tir air-air -10)</option>
				<?}if($Date_Campagne >"1944-01-01" and $Avion_xp >100){?>
				<option value='5'>Gyroscopique (Tir +20, Bombardement +20)</option>
				<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Radar</th><th></th>
		<th>Réservoir largable</th><th></th>
	</tr>
	<tr>
		<td><?echo $Radar_txt;?></td>
		<td align="left">
			<select name="radar" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Aucun</option>
				<?if($Radar_On){
					if($Avion_xp >10 or $Mecano1){?>
				<option value='2'>Radar Décimétrique primitif (50kg)</option>
				<?}if($Date_Campagne >"1942-01-01" and ($Avion_xp >25 or $Mecano4)){?>
				<option value='3'>Radar Décimétrique amélioré (250kg)</option>
				<?}if($Date_Campagne >"1943-01-01" and $Avion_xp >50){?>
				<option value='4'>Radar Décimétrique évolué (500kg)</option>
				<?}if($Date_Campagne >"1944-01-01" and $Avion_xp >75){?>
				<option value='5'>Radar Centimétrique (1000kg)</option>
				<?}}?>
			</select>
		</td>
		<td><?echo $Baby_txt;?></td>
		<td align="left">
			<select name="reservoirl" style="width: 150px">
				<option value='0'>Ne rien changer</option>
				<option value='1'>Aucun</option>
				<?if($Baby >0 and ($Avion_xp >10 or $Mecano1)){?>
				<option value='<?echo $Baby;?>'>Réservoir externe (<?$Baby_kg=ceil($Baby/2); echo $Baby_kg;?>kg; Autonomie +<?echo $Baby;?>km)</option>
				<?}?>
			</select>
		</td>		
	</tr></table>
	<p class='lead'>Fourniture des pièces <?echo round($Efficacite_ravit);?>% <a href='#' class='popup'><img src='images/help.png'><span>Le % influe sur le coût en CT</span></a></p>
	<input type="submit" value="Valider" class='btn btn-default' onclick='this.disabled=true;this.form.submit();'> <img src='/images/CT<?echo $malus_ravit;?>.png' title='Montant en Crédits Temps que nécessite cette action'></form>
<?
	}
	else
		header("Location: ./tsss.php");
}
else
	header("Location: ./tsss.php");
?>