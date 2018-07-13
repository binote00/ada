<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_avions.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_as_des_as.php');
$ID=Insec($_POST['avion']);
$ID_ref=Insec($_POST['ref']);
$arme1=Insec($_POST['arme1']);
$arme2=Insec($_POST['arme2']);
$muns1=Insec($_POST['muns1']);
$muns2=Insec($_POST['muns2']);
$blindage=Insec($_POST['blindage']);
$bombes=Insec($_POST['bombes']);
$bombe_type=Insec($_POST['bombe_type']);
$camouflage=Insec($_POST['camouflage']);
$cellule=Insec($_POST['cellule']);
$helice=Insec($_POST['helice']);
$volets=Insec($_POST['volets']);
$moteur=Insec($_POST['moteur']);
$navi=Insec($_POST['navi']);
$cockpit=Insec($_POST['cockpit']);
//$parebrise=Insec($_POST['parebrise']);
$radar=Insec($_POST['radar']);
$radio=Insec($_POST['radio']);
$reservoir=Insec($_POST['reservoir']);
$reservoirl=Insec($_POST['reservoirl']);
$train=Insec($_POST['train']);
$voilure=Insec($_POST['voilure']);
$verriere=Insec($_POST['verriere']);
$viseur=Insec($_POST['viseur']);
$reparer=Insec($_POST['reparer']);
$robmax=Insec($_POST['robmax']);
$init_g=Insec($_POST['init']);
$del_g=Insec($_POST['del']);
if(isset($_SESSION['AccountID']) AND !empty($_POST) AND $ID >0)
{
	$PlayerID=$_SESSION['PlayerID'];
	if($del_g >0)
	{
		DeleteData("Avions_Sandbox","ID",$ID);
		SetData("Joueur","Avion_Sandbox",0,"ID",$PlayerID);
		$mes="Votre avion personnel a été supprimé avec succès!";
		$img=Afficher_Image('images/avions/garage'.$ID_ref.'.jpg','images/avions/vol'.$ID_ref.'.jpg',"Avion perso");
	}
	elseif($init_g >0)
	{
		$Robustesse=0;
		$Cellule_ori=GetData("Avions_Sandbox","ID",$ID,"Cellule");
		if($Cellule_ori ==1 or $Cellule_ori ==7 or $Cellule_ori ==11)
			$Robustesse=-100;
		elseif($Cellule_ori ==2 or $Cellule_ori ==8 or $Cellule_ori ==12)
			$Robustesse=100;
		if($Robustesse !=0)
		{
			UpdateData("Avions_Sandbox","Robustesse",$Robustesse,"ID",$ID);
			UpdateData("Joueur","S_HP",$Robustesse,"ID",$PlayerID);
		}
		$con=dbconnecti();
		$query_insert="UPDATE Avion,Avions_Sandbox SET 
		Avions_Sandbox.Puissance=Avion.Puissance,
		Avions_Sandbox.Masse=Avion.Masse,
		Avions_Sandbox.ChargeAlaire=Avion.ChargeAlaire,
		Avions_Sandbox.VitesseH=Avion.VitesseH,
		Avions_Sandbox.VitesseB=Avion.VitesseB,
		Avions_Sandbox.VitesseA=Avion.VitesseA,
		Avions_Sandbox.VitesseP=Avion.VitesseP,
		Avions_Sandbox.Alt_ref=Avion.Alt_ref,
		Avions_Sandbox.Stabilite=Avion.Stabilite,
		Avions_Sandbox.Maniabilite=Avion.Maniabilite,
		Avions_Sandbox.ManoeuvreH=Avion.ManoeuvreH,
		Avions_Sandbox.ManoeuvreB=Avion.ManoeuvreB,
		Avions_Sandbox.Detection=Avion.Detection,
		Avions_Sandbox.Plafond=Avion.Plafond,
		Avions_Sandbox.Autonomie=Avion.Autonomie,
		Avions_Sandbox.Visibilite=Avion.Visibilite,
		Avions_Sandbox.Blindage=Avion.Blindage,
		Avions_Sandbox.ArmePrincipale=Avion.ArmePrincipale,
		Avions_Sandbox.Arme1_Nbr=Avion.Arme1_Nbr,
		Avions_Sandbox.Arme1_Mun=Avion.Arme1_Mun,
		Avions_Sandbox.Munitions1=Avion.Munitions1,
		Avions_Sandbox.ArmeSecondaire=Avion.ArmeSecondaire,
		Avions_Sandbox.Munitions2=Avion.Munitions2,
		Avions_Sandbox.Arme2_Nbr=Avion.Arme2_Nbr,
		Avions_Sandbox.Arme2_Mun=Avion.Arme2_Mun,
		Avions_Sandbox.Bombe=0,
		Avions_Sandbox.Bombe_Nbr=0,
		Avions_Sandbox.Avion_BombeT=Avion.Avion_BombeT,
		Avions_Sandbox.Baby=Avion.Baby,
		Avions_Sandbox.Helice=Avion.Helice,
		Avions_Sandbox.Train=Avion.Train,
		Avions_Sandbox.Cellule=Avion.Cellule,
		Avions_Sandbox.Volets=Avion.Volets,
		Avions_Sandbox.Voilure=Avion.Voilure,
		Avions_Sandbox.Verriere=Avion.Verriere,
		Avions_Sandbox.Viseur=Avion.Viseur,
		Avions_Sandbox.Moteur=Avion.Moteur,
		Avions_Sandbox.Reservoir=Avion.Reservoir,
		Avions_Sandbox.Camouflage=Avion.Camouflage,
		Avions_Sandbox.Radar=Avion.Radar,
		Avions_Sandbox.Radio=Avion.Radio,
		Avions_Sandbox.Navigation=Avion.Navigation
		WHERE Avions_Sandbox.ID_ref=Avion.ID AND Avions_Sandbox.ID='$ID'";
		$ok=mysqli_query($con,$query_insert);
		if(!$ok)
			$mes.="Erreur de mise à jour de l'avion !";
		else
		{
			$mes="Votre avion personnel a été remis au standard de série avec succès!";
			$img=Afficher_Image('images/avions/garage'.$ID_ref.'.jpg','images/avions/vol'.$ID_ref.'.jpg',"Avion perso");
		}
	}
	else
	{
		$Free=GetData("Joueur","ID",$PlayerID,"Free");
		if($Free >=0)
		{
			$kilos=0;
			//GetData Avion		
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Robustesse,Type,Pays,Plafond,Autonomie,VitesseH,VitesseB,VitesseP,VitesseA,ManoeuvreH,ManoeuvreB,Maniabilite,Visibilite,
			ArmePrincipale,Arme1_Nbr,Munitions1,ArmeSecondaire,Arme2_Nbr,Munitions2,Bombe,Bombe_Nbr,Avion_BombeT,Blindage,Cellule,Volets,Moteur,Navigation,Radar,Radio,Reservoir,
			Voilure,Verriere,Detection,Viseur,Camouflage,Baby,ChargeAlaire,Puissance,Masse,Engine_Nbr,Train,Helice FROM Avions_Sandbox WHERE ID='$ID'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Nom=$data['Nom'];
					$Robustesse_ori=$data['Robustesse'];
					$Type=$data['Type'];
					$Pays=$data['Pays'];
					$Plafond=$data['Plafond'];
					$Autonomie=$data['Autonomie'];
					$VitesseH=$data['VitesseH'];
					$VitesseB=$data['VitesseB'];
					$VitesseP=$data['VitesseP'];
					$VitesseA=$data['VitesseA'];
					$ManH=$data['ManoeuvreH'];
					$ManB=$data['ManoeuvreB'];
					$Mani=$data['Maniabilite'];
					$Vis=$data['Visibilite'];
					$arme1_ori=$data['ArmePrincipale'];
					$arme2_ori=$data['ArmeSecondaire'];
					$arme1_nbr_ori=$data['Arme1_Nbr'];
					$arme2_nbr_ori=$data['Arme2_Nbr'];
					$Munitions1=$data['Munitions1'];
					$Munitions2=$data['Munitions2'];
					$Bombe_ori=$data['Bombe'];
					$Bombe_nbr=$data['Bombe_Nbr'];
					$Avion_BombeT=$data['Avion_BombeT'];
					$Blindage_ori=$data['Blindage'];
					$Cellule_ori=$data['Cellule'];
					$Engine_Nbr=$data['Engine_Nbr'];
					$Helice_ori=$data['Helice'];
					$Train_ori=$data['Train'];
					$Volets_ori=$data['Volets'];
					$Moteur_ori=$data['Moteur'];
					$navi_ori=$data['Navigation'];
					$radar_ori=$data['Radar'];
					$radio_ori=$data['Radio'];
					$Reservoir_ori=$data['Reservoir'];
					$Voilure_ori=$data['Voilure'];
					$Verriere_ori=$data['Verriere'];
					$Det_ori=$data['Detection'];
					$Viseur_ori=$data['Viseur'];
					$Camouflage_ori=$data['Camouflage'];
					$Baby_Actu=$data['Baby'];
					$Surf_Alaire=$data['ChargeAlaire'];
					$Puissance=$data['Puissance'];
					$Masse=$data['Masse'];
				}
				mysqli_free_result($result);
				unset($data);
			}	
			if($Puissance and $Masse and $Surf_Alaire)
			{
				$Autonomie_serie=GetData("Avion","ID",$ID_ref,"Autonomie");
				$Robustesse_serie=GetData("Avion","ID",$ID_ref,"Robustesse");
				$Poids_Puiss_ori=$Masse / $Puissance;
				$Rap_Puiss_ori=$Puissance / $Masse;
				$Chg_Alaire_ori=$Masse / ($Surf_Alaire / 100);
				$Surf_Alaire_ori=$Surf_Alaire;
				$VitesseH_ori=$VitesseH;
				$VitesseA_ori=$VitesseA;
				$Autonomie_ori=$Autonomie;
				$Plafond_ori=$Plafond;
				$Puissance_ori=$Puissance;
				$ManH_ori=$ManH;
				$ManB_ori=$ManB;
				$Mani_ori=$Mani;
				$Robustesse=$Robustesse_ori;
				$Autonomie=0;
			}
			else
				exit;			
			if($reparer and $Free >0)
			{
				$Robustesse=$robmax;
				UpdateCarac($PlayerID,"Free",-1);		
			}
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
			//Arme Principale
			if($arme1 >0)
			{
				$arme1_masse=GetData("Armes","ID",$arme1_ori,"Masse");
				$arme_kilos=$arme1_masse * $arme1_nbr_ori;
			
				switch($arme1)
				{
					case 1: //2mg 8
						$Armep=$Arme8_fus;		
						$Armep_nbr=$Arme8_fus_nbr;
					break;
					case 2: //2mg 13
						$Armep=$Arme13;
						$Armep_nbr=$Arme13_fus_nbr;
					break;
					case 3: //1cn 20
						$Armep=$Arme20;
						$Armep_nbr=$Arme20_fus_nbr;
					break;
					case 4: //Aucune
						$Armep=5;
						$Armep_nbr=0;
					break;
				}
				if($Armep != $arme1_ori)
					$Arme1_Mun=GetData("Armes","ID",$Armep,"Munitions") * $Armep_nbr;
				$Armep_masse=GetData("Armes","ID",$Armep,"Masse") * $Armep_nbr;
				$kilos=$kilos - $arme_kilos + $Armep_masse;
			}
			else
			{
				$Armep=$arme1_ori;
				$Armep_nbr=$arme1_nbr_ori;
			}
			//Arme Secondaire
			if($arme2 >0)
			{
				$arme2_masse=GetData("Armes","ID",$arme2_ori,"Masse");
				$arme2_kilos=$arme2_masse * $arme2_nbr_ori;
				switch($arme2)
				{
					case 1: //2mg 8
						$Armes=$Arme8_ailes;
						$Armes_nbr=$Arme8_ailes_nbr;
					break;
					case 2: //4mg 8
						$Armes=$Arme8_ailes;
						$Armes_nbr=4;
					break;
					case 3: //6mg 8
						$Armes=$Arme8_ailes;
						$Armes_nbr=6;
					break;
					case 4: //2mg 13
						$Armes=$Arme13;
						$Armes_nbr=$Arme13_ailes_nbr;
					break;
					case 5: //4mg 13
						$Armes=$Arme13;
						$Armes_nbr=$Arme13_ailes_max;
					break;
					case 6: //2cn 20
						$Armes=$Arme20;
						$Armes_nbr=$Arme20_ailes_nbr;
					break;
					case 7: //Aucune
						$Armes=5;
						$Armes_nbr=0;
					break;
					case 8: //camera
						$Armes=$Camera_low;
						$Armes_nbr=1;
					break;
					case 9: //camera
						$Armes=$Camera_high;
						$Armes_nbr=1;
					break;
					case 10: //10+mg 8
						$Armes=$Arme8_ailes;
						$Armes_nbr=$Arme8_ailes_max;
					break;
					case 16: //8mg 8
						$Armes=$Arme8_ailes;
						$Armes_nbr=8;
					break;
				}
				if($Armes != $arme2_ori)
					$Arme2_Mun=GetData("Armes","ID",$Armes,"Munitions") * $Armes_nbr;
				$Armes_masse=GetData("Armes","ID",$Armes,"Masse") * $Armes_nbr;
				$kilos=$kilos - $arme2_kilos + $Armes_masse;
			}
			else
			{
				$Armes=$arme2_ori;
				$Armes_nbr=$arme2_nbr_ori;
			}
			//Munitions
			if($muns1 >0)
				$Mun1=$muns1 -1;
			else
				$Mun1=$Munitions1;
			if($muns2 >0)
				$Mun2=$muns2 -1;
			else
				$Mun2=$Munitions2;
			//Blindage
			if($blindage >0)
			{
				switch($blindage)
				{
					case 1:
						$Blinde=0;
					break;
					case 2:
						$Blinde=8;
					break;
					case 3:
						$Blinde=13;
					break;
					case 4:
						$Blinde=16;
					break;
					case 5:
						$Blinde=19;
					break;
					case 6:
						$Blinde=22;
					break;
					case 7:
						$Blinde=25;
					break;
				}
				$kilos=$kilos +(($Blinde - $Blindage_ori)*25);
			}
			else
				$Blinde=$Blindage_ori;
			//Bombes
			if($bombes)
			{
				if(is_numeric($bombes))
				{
					//Fret
					if($bombes > 11)
					{
						switch($bombes)
						{
							case 12:
								$Bombe_new=50;
								$Bombe_new_nbr=$Fret_50;
							break;
							case 13:
								$Bombe_new=125;
								$Bombe_new_nbr=$Fret_125;
							break;
							case 14:
								$Bombe_new=250;
								$Bombe_new_nbr=$Fret_250;
							break;
							case 15:
								$Bombe_new=500;
								$Bombe_new_nbr=$Fret_500;
							break;
							case 16:
								$Bombe_new=50000;
								$Bombe_new_nbr=$Fret_mun8;
							break;
							case 17:
								$Bombe_new=15000;
								$Bombe_new_nbr=$Fret_mun13;
							break;
							case 18:
								$Bombe_new=5000;
								$Bombe_new_nbr=$Fret_mun20;
							break;
							case 19:
								$Bombe_new=1200;
								$Bombe_new_nbr=$Fret_87;
							break;
							case 20:
								$Bombe_new=1100;
								$Bombe_new_nbr=$Fret_100;
							break;
							case 21:
								$Bombe_new=100;
								$Bombe_new_nbr=$Fret_para;
							break;
						}
					}
					elseif($bombes ==1)
					{
						$Bombes_ori_kg=$Bombe_ori * $Bombe_nbr;
						$kilos -= $Bombes_ori_kg;	
						if($Bombes_ori_kg)
						{
							$VitesseH /= 0.9;
							$VitesseB /= 0.9;
							$VitesseA /= 0.75;
							$ManH += 5;
							$ManB += 5;
							$Mani += 5;
							$Plafond += 1000;
						}
					}
				}
				else
				{
					$Bombe_new=strstr($bombes,"_",true);
					$Bombe_new_nbr=substr($bombes,strpos($bombes,"_")+1);
					$Bombes_ori_kg=$Bombe_ori * $Bombe_nbr;
					$Bombes_new_kg=$Bombe_new * $Bombe_new_nbr;
					$kilos=$kilos - $Bombes_ori_kg + $Bombes_new_kg;	
					if(!$Bombes_ori_kg)
					{
						$VitesseH *= 0.9;
						$VitesseB *= 0.9;
						$VitesseA *= 0.75;
						$ManH -= 5;
						$ManB -= 5;
						$Mani -= 5;
						$Plafond -= 1000;
					}
				}
			}
			else
			{
				$Bombe_new=$Bombe_ori;
				$Bombe_new_nbr=$Bombe_nbr;
			}
			//BombesT
			if($bombe_type)
				$bombe_type -= 1;
			else
				$bombe_type=$Avion_BombeT;
			//Cellule
			if($cellule >0)
			{
				switch($cellule)
				{
					case 1: case 7: case 10: //0kg,rob +0
						if($Cellule_ori == 0 or $Cellule_ori == 6 or $Cellule_ori == 9)
						{
							$Cel_poids=0;
						}
						elseif($Cellule_ori ==1 or $Cellule_ori == 7 or $Cellule_ori ==10)
						{
							$Robustesse -= 100;
							$Cel_poids=-200;
							$VitesseP *= 0.9;
						}
						else
						{
							$Robustesse += 100;
							$Cel_poids=200;
							$VitesseP *= 1.1;
						}
					break;
					case 2: case 8: case 11: //+200kg,rob +100
						if($Cellule_ori == 0 or $Cellule_ori == 6 or $Cellule_ori == 9)
						{
							$Robustesse += 100;
							$Cel_poids=200;
							$VitesseP *= 1.1;
						}
						elseif($Cellule_ori ==1 or $Cellule_ori == 7 or $Cellule_ori ==10)
						{
							$Cel_poids=0;
						}
						else
						{
							$Robustesse -= 200;
							$Cel_poids=-400;
							$VitesseP *= 0.8;
						}
					break;
					case 3: case 9: case 12: //-200,rob -100
						if($Cellule_ori == 0 or $Cellule_ori == 6 or $Cellule_ori == 9)
						{
							$Robustesse -= 100;
							$Cel_poids=-200;
							$VitesseP *= 0.9;
						}
						elseif($Cellule_ori ==1 or $Cellule_ori == 7 or $Cellule_ori ==10)
						{
							$Robustesse += 200;
							$Cel_poids=400;
							$VitesseP *= 1.2;
						}
						else
						{
							$Cel_poids=0;
						}
					break;
				}
				$cellule -= 1;
				$kilos += $Cel_poids;
			}
			else
				$cellule=$Cellule_ori;
			//Helice
			if($helice >0)
			{
				switch($helice)
				{
					case 1: 
						if($Helice_ori == 0)
						{
							$Hel_poids=0;
						}
						elseif($Helice_ori ==1)
						{
							$Hel_poids=-10;
							$Autonomie -= 50;
						}
						else
						{
							$Hel_poids=-25;
							$Autonomie -= 100;
							$Plafond -= 250;
						}
					break;
					case 2: 
						if($Helice_ori == 0)
						{
							$Hel_poids=10;
							$Autonomie += 50;
						}
						elseif($Helice_ori ==1)
						{
							$Hel_poids=0;
						}
						else
						{
							$Hel_poids=-15;
							$Autonomie -= 50;
							$Plafond -= 250;
						}
					break;
					case 3: 
						if($Helice_ori == 0)
						{
							$Hel_poids=25;
							$Autonomie += 100;
							$Plafond += 250;
						}
						elseif($Helice_ori ==1)
						{
							$Hel_poids=15;
							$Autonomie += 50;
							$Plafond += 250;
						}
						else
						{
							$Hel_poids=0;
						}
					break;
				}
				$helice -= 1;
				$kilos += $Hel_poids;
			}
			else
				$helice=$Helice_ori;
			//Volets
			if($volets >0)
			{
				switch($volets)
				{
					case 1: 
						if($Volets_ori == 0)
						{
							$Volets_poids=0;
						}
						elseif($Volets_ori ==1)
						{
							$Volets_poids=-50;
						}
						elseif($Volets_ori == 2)
						{
							$Volets_poids=-100;
							$ManH -= 5;
							$ManB -= 5;
							$Mani -= 1;
						}
						else
						{
							$Volets_poids=-200;
							$ManB -= 10;
							$Mani -= 1;
						}
					break;
					case 2: 
						if($Volets_ori == 0)
						{
							$Volets_poids=50;
						}
						elseif($Volets_ori ==1)
						{
							$Volets_poids=0;
						}
						elseif($Volets_ori == 2)
						{
							$Volets_poids=-50;
							$ManH -= 5;
							$ManB -= 5;
							$Mani -= 1;
						}
						else
						{
							$Volets_poids=-200;
							$ManB -= 10;
							$Mani -= 1;
						}
					break;
					case 3: 
						if($Volets_ori == 0)
						{
							$Volets_poids=100;
							$ManH += 5;
							$ManB += 5;
							$Mani += 1;
						}
						elseif($Volets_ori ==1)
						{
							$Volets_poids=50;
							$ManH += 5;
							$ManB += 5;
							$Mani += 1;
						}
						elseif($Volets_ori == 2)
						{
							$Volets_poids=0;
						}
						else
						{
							$Volets_poids=-100;
							$ManH += 5;
							$ManB -= 5;
						}
					break;
					case 4: 
						if($Volets_ori == 0)
						{
							$Volets_poids=200;
							$ManB += 10;
							$Mani += 1;
						}
						elseif($Volets_ori ==1)
						{
							$Volets_poids=150;
							$ManB += 10;
							$Mani += 1;
						}
						elseif($Volets_ori == 2)
						{
							$Volets_poids=100;
							$ManH -= 5;
							$ManB += 5;
						}
						else
						{
							$Volets_poids=0;
						}
					break;
				}
				$volets -= 1;
				$kilos += $Volets_poids;
			}
			else
				$volets=$Volets_ori;
			//Moteur
			if($moteur >0)
			{
				$filtre_masse=25 * $Engine_Nbr;
				if($Moteur_ori == 7 and $moteur != 8)
				{
					$VitesseH /= 0.97;
					$VitesseB /= 0.97;
					$VitesseA /= 0.90;
					$VitesseP /= 0.97;	
				}
				switch($moteur)
				{
					case 1:
						if($Moteur_ori == 0)
						{
							$Moteur_poids=0;
						}
						elseif($Moteur_ori ==1)
						{
							$Moteur_poids=0;
							$Puissance -= 5;
						}
						elseif($Moteur_ori == 2)
						{
							$Moteur_poids=0;
							$Puissance -= 10;
							$Autonomie += 100;
						}
						elseif($Moteur_ori == 3)
						{
							$Moteur_poids=-100;
							$Puissance -= 50;
							$Plafond -= 250;
							$Autonomie += 50;
						}
						elseif($Moteur_ori == 4)
						{
							$Moteur_poids=-200;
							$Autonomie += 200;
						}
						elseif($Moteur_ori == 5)
						{
							$Moteur_poids=-100;
						}
						elseif($Moteur_ori == 6)
						{
							$Moteur_poids=-250;
							$Puissance -= 50;
							$Autonomie += 100;
						}
						elseif($Moteur_ori == 7)
						{
							$Moteur_poids=-$filtre_masse;
						}
					break;
					case 2:
						if($Moteur_ori == 0)
						{
							$Moteur_poids=0;
							$Puissance += 5;
						}
						elseif($Moteur_ori ==1)
						{
							$Moteur_poids=0;
						}
						elseif($Moteur_ori == 2)
						{
							$Moteur_poids=0;
							$Puissance -= 5;
							$Autonomie += 100;
						}
						elseif($Moteur_ori == 3)
						{
							$Moteur_poids=-100;
							$Puissance -= 45;
							$Plafond -= 250;
							$Autonomie += 50;
						}
						elseif($Moteur_ori == 4)
						{
							$Moteur_poids=-200;
							$Puissance += 5;
							$Autonomie += 200;
						}
						elseif($Moteur_ori == 5)
						{
							$Moteur_poids=-100;
							$Puissance += 5;
						}
						elseif($Moteur_ori == 6)
						{
							$Moteur_poids=-250;
							$Puissance -= 95;
							$Autonomie += 100;
						}
						elseif($Moteur_ori == 7)
						{
							$Moteur_poids=-$filtre_masse;
						}
					break;
					case 3:
						if($Moteur_ori ==0)
						{
							$Moteur_poids=0;
							$Puissance +=10;
							$Autonomie -=100;
						}
						elseif($Moteur_ori ==1)
						{
							$Moteur_poids=0;
							$Puissance +=5;
							$Autonomie -=100;
						}
						elseif($Moteur_ori ==2)
						{
							$Moteur_poids=0;
						}
						elseif($Moteur_ori ==3)
						{
							$Moteur_poids=-100;
							$Puissance -=40;
							$Plafond -=250;
							$Autonomie -=50;
						}
						elseif($Moteur_ori ==4)
						{
							$Moteur_poids=-200;
							$Puissance +=10;
							$Autonomie +=100;
						}
						elseif($Moteur_ori ==5)
						{
							$Moteur_poids=-100;
							$Puissance +=10;
							$Autonomie -=100;
						}
						elseif($Moteur_ori ==6)
						{
							$Moteur_poids=-250;
							$Puissance -=90;
						}
						elseif($Moteur_ori ==7)
						{
							$Moteur_poids=-$filtre_masse;
						}
					break;
					case 4:
						if($Moteur_ori ==0)
						{
							$Moteur_poids=100;
							$Puissance +=50;
							$Autonomie -=50;
							$Plafond +=250;
						}
						elseif($Moteur_ori ==1)
						{
							$Moteur_poids=100;
							$Puissance +=45;
							$Autonomie -=50;
							$Plafond +=250;
						}
						elseif($Moteur_ori ==2)
						{
							$Moteur_poids=100;
							$Puissance +=40;
							$Autonomie +=50;
							$Plafond +=250;
						}
						elseif($Moteur_ori ==3)
						{
							$Moteur_poids=0;
						}
						elseif($Moteur_ori == 4)
						{
							$Moteur_poids=-100;
							$Puissance +=50;
							$Autonomie +=150;
							$Plafond +=250;
						}
						elseif($Moteur_ori ==5)
						{
							$Moteur_poids=0;
							$Puissance +=50;
							$Plafond +=250;
						}
						elseif($Moteur_ori ==6)
						{
							$Moteur_poids=-150;
							$Puissance -=50;
							$Autonomie +=50;
							$Plafond +=250;
						}
						elseif($Moteur_ori ==7)
						{
							$Moteur_poids=100-$filtre_masse;
						}
					break;
					case 5:
						if($Moteur_ori ==0)
						{
							$Moteur_poids=200;
							$Autonomie -=200;
						}
						elseif($Moteur_ori ==1)
						{
							$Moteur_poids=200;
							$Puissance -=5;
							$Autonomie -=200;
						}
						elseif($Moteur_ori ==2)
						{
							$Moteur_poids=200;
							$Puissance -=10;
							$Autonomie -=100;
						}
						elseif($Moteur_ori ==3)
						{
							$Moteur_poids=100;
							$Puissance -=50;
							$Autonomie -=150;
							$Plafond -=250;
						}
						elseif($Moteur_ori ==4)
						{
							$Moteur_poids=0;
						}
						elseif($Moteur_ori ==5)
						{
							$Moteur_poids=100;
						}
						elseif($Moteur_ori ==6)
						{
							$Moteur_poids=-50;
							$Puissance -=100;
							$Autonomie -=100;
						}
						elseif($Moteur_ori ==7)
						{
							$Moteur_poids=200-$filtre_masse;
						}
					break;
					case 6:
						if($Moteur_ori ==0)
						{
							$Moteur_poids=100;
						}
						elseif($Moteur_ori ==1)
						{
							$Moteur_poids=100;
							$Puissance -=5;
						}
						elseif($Moteur_ori ==2)
						{
							$Moteur_poids=100;
							$Puissance -=10;
							$Autonomie +=100;
						}
						elseif($Moteur_ori ==3)
						{
							$Moteur_poids=0;
							$Puissance -=50;
							$Autonomie +=50;
							$Plafond -=250;
						}
						elseif($Moteur_ori ==4)
						{
							$Moteur_poids=-100;
							$Autonomie +=200;
						}
						elseif($Moteur_ori ==5)
						{
							$Moteur_poids=0;
						}
						elseif($Moteur_ori ==6)
						{
							$Moteur_poids=-150;
							$Puissance -=100;
							$Autonomie +=100;
						}
						elseif($Moteur_ori ==7)
						{
							$Moteur_poids=100-$filtre_masse;
						}
					break;
					case 7:
						if($Moteur_ori ==0)
						{
							$Moteur_poids=250;
							$Puissance += 100;
							$Autonomie -= 100;
						}
						elseif($Moteur_ori ==1)
						{
							$Moteur_poids=250;
							$Puissance += 95;
							$Autonomie -= 100;
						}
						elseif($Moteur_ori ==2)
						{
							$Moteur_poids=250;
							$Puissance +=90;
						}
						elseif($Moteur_ori ==3)
						{
							$Moteur_poids=150;
							$Puissance += 50;
							$Autonomie -= 50;
							$Plafond -= 250;
						}
						elseif($Moteur_ori ==4)
						{
							$Moteur_poids=50;
							$Puissance += 100;
							$Autonomie += 100;
						}
						elseif($Moteur_ori ==5)
						{
							$Moteur_poids=150;
							$Puissance += 100;
							$Autonomie -= 100;
						}
						elseif($Moteur_ori ==6)
						{
							$Moteur_poids=0;
						}
						elseif($Moteur_ori ==7)
						{
							$Moteur_poids=250-$filtre_masse;
							$Autonomie -=100;
						}
					break;
					case 8:
						if($Moteur_ori ==0)
						{
							$Moteur_poids=$filtre_masse;
						}
						elseif($Moteur_ori ==1)
						{
							$Moteur_poids=$filtre_masse;
							$Puissance -=5;
						}
						elseif($Moteur_ori ==2)
						{
							$Moteur_poids=$filtre_masse;
							$Puissance -= 10;
							$Autonomie += 100;
						}
						elseif($Moteur_ori ==3)
						{
							$Moteur_poids=100-$filtre_masse;
							$Puissance -= 50;
							$Plafond -= 250;
							$Autonomie += 50;
						}
						elseif($Moteur_ori ==4)
						{
							$Moteur_poids=200-$filtre_masse;
							$Autonomie +=200;
						}
						elseif($Moteur_ori ==5)
						{
							$Moteur_poids=100-$filtre_masse;
						}
						elseif($Moteur_ori ==6)
						{
							$Moteur_poids=250-$filtre_masse;
							$Puissance -= 50;
							$Autonomie += 100;
						}
						elseif($Moteur_ori ==7)
						{
							$Moteur_poids=0;
						}
						if($Moteur_ori !=7)
						{
							$VitesseH *=0.97;
							$VitesseB *=0.97;
							$VitesseA *=0.90;
							$VitesseP *=0.97;
						}			
					break;
				}
				$moteur -=1;
				$kilos +=$Moteur_poids;
			}
			else
				$moteur=$Moteur_ori;
			/*PareBrise
			$PareBrise_ori=GetData("Avions_Sandbox","ID",$ID,"PareBrise");
			if($parebrise >0)
			{
				switch($parebrise)
				{
					case 1:
						$Blindep=0;
					break;
					case 2:
						$Blindep=8;
					break;
					case 3:
						$Blindep=12;
					break;
					case 4:
						$Blindep=16;
					break;
				}
				$kilos += (($Blindep - $PareBrise_ori)*50);
			}
			else
				$Blindep=$PareBrise_ori;*/
			//Navi
			if($navi)
			{
				switch($navi)
				{
					case 1:
						if($navi_ori == 0)
							$navi_poids=0;
						elseif($navi_ori ==1)
							$navi_poids=-200;
					break;
					case 2:
						if($navi_ori == 0)
							$navi_poids=200;
						elseif($navi_ori ==1)
							$navi_poids=0;
					break;
				}
				$navi -= 1;
				$kilos += $navi_poids;
			}	
			else
				$navi=$navi_ori;
			//Radar
			if($radar)
			{
				switch($radar)
				{
					case 1:
						if($radar_ori == 0)
							$radar_poids=0;
						elseif($radar_ori ==10)
							$radar_poids=-50;
						elseif($radar_ori == 20)
							$radar_poids=-250;
						elseif($radar_ori == 30)
							$radar_poids=-500;
					break;
					case 2:
						if($radar_ori == 0)
							$radar_poids=50;
						elseif($radar_ori ==10)
							$radar_poids=0;
						elseif($radar_ori == 20)
							$radar_poids=-200;
						elseif($radar_ori == 30)
							$radar_poids=-450;
					break;
					case 3:
						if($radar_ori == 0)
							$radar_poids=250;
						elseif($radar_ori ==10)
							$radar_poids=200;
						elseif($radar_ori == 20)
							$radar_poids=0;
						elseif($radar_ori == 30)
							$radar_poids=-250;
					break;
					case 4:
						if($radar_ori == 0)
							$radar_poids=500;
						elseif($radar_ori ==10)
							$radar_poids=450;
						elseif($radar_ori == 20)
							$radar_poids=250;
						elseif($radar_ori == 30)
							$radar_poids=0;
					break;
				}
				$radar=($radar-1)*10;
				$kilos += $radar_poids;
			}
			else
				$radar=$radar_ori;
			//Radio
			if($radio)
			{
				switch($radio)
				{
					case 1:
						if($radio_ori == 0)
							$radio_poids=0;
						elseif($radio_ori ==1)
							$radio_poids=-100;
						elseif($radio_ori == 2)
							$radio_poids=-200;
						elseif($radio_ori == 3)
							$radio_poids=-300;
					break;
					case 2:
						if($radio_ori == 0)
							$radio_poids=100;
						elseif($radio_ori ==1)
							$radio_poids=0;
						elseif($radio_ori == 2)
							$radio_poids=-100;
						elseif($radio_ori == 3)
							$radio_poids=-200;
					break;
					case 3:
						if($radio_ori == 0)
							$radio_poids=200;
						elseif($radio_ori ==1)
							$radio_poids=100;
						elseif($radio_ori == 2)
							$radio_poids=0;
						elseif($radio_ori == 3)
							$radio_poids=-100;
					break;
					case 4:
						if($radio_ori == 0)
							$radio_poids=300;
						elseif($radio_ori ==1)
							$radio_poids=200;
						elseif($radio_ori == 2)
							$radio_poids=100;
						elseif($radio_ori == 3)
							$radio_poids=0;
					break;
				}
				$radio -= 1;
				$kilos += $radio_poids;
			}	
			else
				$radio=$radio_ori;
			//Reservoir
			if($reservoir >0)
			{
				switch($reservoir)
				{
					case 1:
						if($Reservoir_ori == 0)
						{
							$reservoir_poids=0;
						}
						elseif($Reservoir_ori ==1)
						{
							$reservoir_poids=0;
						}
						elseif($Reservoir_ori == 2)
						{
							$reservoir_poids=-200;
							$Autonomie -= 200;
						}
						else
						{
							$reservoir_poids=-500;
							$Autonomie -= 500;
						}
					break;
					case 2:
						if($Reservoir_ori == 0)
						{
							$reservoir_poids=0;
						}
						elseif($Reservoir_ori ==1)
						{
							$reservoir_poids=0;
						}
						elseif($Reservoir_ori == 2)
						{
							$reservoir_poids=-200;
							$Autonomie -= 200;
						}
						else
						{
							$reservoir_poids=-500;
							$Autonomie -= 500;
						}
					break;
					case 3:
						if($Reservoir_ori == 0)
						{
							$reservoir_poids=200;
							$Autonomie += 200;
						}
						elseif($Reservoir_ori ==1)
						{
							$reservoir_poids=200;
							$Autonomie += 200;
						}
						elseif($Reservoir_ori == 2)
						{
							$reservoir_poids=0;
						}
						else
						{
							$reservoir_poids=-300;
							$Autonomie -= 300;
						}
					break;
					case 4:
						if($Reservoir_ori == 0)
						{
							$reservoir_poids=500;
							$Autonomie += 500;
						}
						elseif($Reservoir_ori ==1)
						{
							$reservoir_poids=500;
							$Autonomie += 500;
						}
						elseif($Reservoir_ori == 2)
						{
							$reservoir_poids=300;
							$Autonomie += 300;
						}
						else
						{
							$reservoir_poids=0;
						}
					break;
				}
				$reservoir -= 1;
				$kilos += $reservoir_poids;
			}
			else
				$reservoir=$Reservoir_ori;
			//Reservoirl
			if($reservoirl ==1)
			{
				$reservoirl=0;
				$Autonomie -= $Baby;
				$kilos -= ceil($Baby/2);
			}
			elseif($reservoirl >0)
			{
				$kilos += ceil($reservoirl/2);
				$Autonomie += $reservoirl;
			}
			else
				$reservoirl=$Baby_Actu;
			//Train
			if($train >0)
			{
				switch($train)
				{
					case 1:
						if($Train_ori == 0)
							$Train_poids=0;
						elseif($Train_ori ==1)
							$Train_poids=-10;
						elseif($Train_ori == 2)
							$Train_poids=-25;
					break;
					case 2:
						if($Train_ori == 0)
							$Train_poids=10;
						elseif($Train_ori ==1)
							$Train_poids=0;
						elseif($Train_ori == 2)
							$Train_poids=-15;
					break;
					case 3:
						if($Train_ori == 0)
							$Train_poids=25;
						elseif($Train_ori ==1)
							$Train_poids=10;
						elseif($Train_ori == 2)
							$Train_poids=0;
					break;
					case 7:
						if($Train_ori == 7)
						{
							$Train_poids=0;
						}
						elseif($Train_ori == 8)
						{
							$Train_poids=-10;
						}
						elseif($Train_ori == 9)
						{
							$Train_poids=-25;
							$VitesseH -= 5;
							$VitesseB -= 5;
						}
					break;
					case 8:
						if($Train_ori == 7)
						{
							$Train_poids=10;
						}
						elseif($Train_ori == 8)
						{
							$Train_poids=0;
						}
						elseif($Train_ori == 9)
						{
							$Train_poids=-15;
							$VitesseH -= 5;
							$VitesseB -= 5;
						}
					break;
					case 9:
						if($Train_ori == 7)
						{
							$Train_poids=25;
							$VitesseH += 5;
							$VitesseB += 5;
						}
						elseif($Train_ori == 8)
						{
							$Train_poids=10;
							$VitesseH += 5;
							$VitesseB += 5;
						}
						elseif($Train_ori == 9)
						{
							$Train_poids=0;
						}
					break;
				}
				if($train < 7)
					$train -= 1;
				$kilos += $Train_poids;
			}
			else
				$train=$Train_ori;
			//Voilure
			if($voilure >0)
			{
				switch($voilure)
				{
					case 1: case 6:
						if($Voilure_ori == 0 or $Voilure_ori == 6)
						{
							$Voilure_poids=0;
						}
						elseif($Voilure_ori ==1 or $Voilure_ori == 7)
						{
							$Voilure_poids=50;
							$VitesseB -= 10;
							$VitesseH += 10;
							//$ManH=$ManH +10;
							$Vis += 2;
							$Surf_Alaire += 200;
						}
						elseif($Voilure_ori == 2 or $Voilure_ori == 8)
						{
							$Voilure_poids=-50;
							$VitesseB += 10;
							$VitesseH -= 10;
							//$ManH=$ManH -10;
							$Vis -= 2;
							$Surf_Alaire -= 200;
						}
						elseif($Voilure_ori == 3 or $Voilure_ori == 9)
						{
							$Voilure_poids=100;
							$VitesseB -= 10;
							$VitesseH -= 10;
							//$ManH=$ManH +10;
							//$ManB=$ManB +10;
							$Autonomie += 100;
						}
					break;
					case 2: case 7:
						if($Voilure_ori == 0 or $Voilure_ori == 6)
						{
							$Voilure_poids=-50;
							$VitesseB += 10;
							$VitesseH -= 10;
							//$ManH=$ManH -10;
							$Vis -= 2;
							$Surf_Alaire -= 200;
						}
						elseif($Voilure_ori ==1 or $Voilure_ori == 7)
						{
							$Voilure_poids=0;
						}
						elseif($Voilure_ori == 2 or $Voilure_ori == 8)
						{
							$Voilure_poids=-50;
							$VitesseB += 20;
							$VitesseH -= 20;
							//$ManH=$ManH -20;
							$Vis -= 4;
							$Surf_Alaire -= 400;
						}
						elseif($Voilure_ori == 3 or $Voilure_ori == 9)
						{
							$Voilure_poids=+50;
							$VitesseH -= 20;
							//$ManB=$ManB +10;
							$Autonomie += 100;
							$Vis -= 2;
							$Surf_Alaire -= 200;
						}
					break;
					case 3: case 8:
						if($Voilure_ori == 0 or $Voilure_ori == 6)
						{
							$Voilure_poids=50;
							$VitesseB -= 10;
							$VitesseH += 10;
							//$ManH=$ManH +10;
							$Vis += 2;
							$Surf_Alaire += 200;
						}
						elseif($Voilure_ori ==1 or $Voilure_ori == 7)
						{
							$Voilure_poids=100;
							$VitesseB -= 20;
							$VitesseH += 20;
							//$ManH=$ManH +20;
							$Vis += 4;
							$Surf_Alaire += 400;
						}
						elseif($Voilure_ori == 2 or $Voilure_ori == 8)
						{
							$Voilure_poids=0;
						}
						elseif($Voilure_ori == 3 or $Voilure_ori == 9)
						{
							$Voilure_poids=+150;
							$VitesseB -= 20;
							//$ManH=$ManH +20;
							//$ManB=$ManB +10;
							$Autonomie += 100;
							$Vis += 2;
							$Surf_Alaire += 200;
						}
					break;
					case 4: case 9:
						if($Voilure_ori == 0 or $Voilure_ori == 6)
						{
							$Voilure_poids=-100;
							$VitesseB += 10;
							$VitesseH += 10;
							//$ManH=$ManH -10;
							//$ManB=$ManB -10;
							$Autonomie -= 100;
						}
						elseif($Voilure_ori ==1 or $Voilure_ori == 7)
						{
							$Voilure_poids=-50;
							$VitesseH += 20;
							//$ManB=$ManB -10;
							$Vis += 2;
							$Surf_Alaire += 200;
							$Autonomie -= 100;
						}
						elseif($Voilure_ori == 2 or $Voilure_ori == 8)
						{
							$Voilure_poids=-150;
							$VitesseB += 20;
							//$ManH=$ManH -20;
							//$ManB=$ManB -10;
							$Vis -= 2;
							$Surf_Alaire -= 200;
							$Autonomie -= 100;
						}
						elseif($Voilure_ori == 3 or $Voilure_ori == 9)
						{
							$Voilure_poids=0;
						}
					break;
				}
				$voilure -= 1;
				$kilos += $Voilure_poids;
			}
			else
				$voilure=$Voilure_ori;
			//Verriere
			if($verriere >0)
			{
				switch($verriere)
				{
					case 1: 
						if($Verriere_ori ==1)
							$Det=-5;
						elseif($Verriere_ori == 2)
							$Det=-10;
						else
							$Det=0;
					break;
					case 2: 
						if($Verriere_ori ==1)
							$Det=0;
						elseif($Verriere_ori == 2)
							$Det=-5;
						else
							$Det=5;
					break;
					case 3: 
						if($Verriere_ori ==1)
							$Det=5;
						elseif($Verriere_ori == 2)
							$Det=0;
						else
							$Det=10;
					break;
				}
				$verriere -= 1;
				$Detection=$Det_ori + $Det;
			}
			else
			{
				$verriere=$Verriere_ori;
				$Detection=$Det_ori;
			}
			//Viseur
			if($viseur >0)
				$viseur -= 1;
			else
				$viseur=$Viseur_ori;
			//Camouflage
			if($camouflage ==0)
				$camouflage=$Camouflage_ori;			
			$Massef=$Masse + $kilos;
			//$Poids_Puiss=round($Massef/$Puissance,2);
			$Poids_Puiss=$Massef / $Puissance;
			//$Rap_Puiss=round($Puissance/$Massef,2);
			$Rap_Puiss=$Puissance / $Massef;
			$Diff_Rap=$Rap_Puiss - $Rap_Puiss_ori;
			$Diff_Puiss=$Poids_Puiss - $Poids_Puiss_ori;
			$Chg_Alaire=$Massef / ($Surf_Alaire/100);
			$Diff_Surf_Alaire=($Surf_Alaire - $Surf_Alaire_ori)/100;
			$Diff_Chg_Alaire=$Chg_Alaire - $Chg_Alaire_ori;
			$VitesseH=round($VitesseH - ($Diff_Puiss*10)+ ($Diff_Chg_Alaire/10));
			$VitesseB=round($VitesseB - ($Diff_Puiss*10)+ ($Diff_Chg_Alaire/10));
			$VitesseA=round($VitesseA - ($Diff_Puiss*20) - $Diff_Chg_Alaire);
			//if($Massef != $Masse or $Plafond_ori != $Plafond)
				$Plafond=round($Plafond - ($Diff_Puiss*1000));
			/*if($Type ==1 or $Type ==12)
				$Autonomie=round($Autonomie - ($Diff_Puiss*250)); //500
			elseif($Type ==4 or $Type ==7 or $Masse <6000)
				$Autonomie=round($Autonomie - ($Diff_Puiss*500));
			else
				$Autonomie=round($Autonomie - ($Diff_Puiss*2000));*/
			//$Autonomie=round($Autonomie - ($Diff_Puiss*($Massef/10)));
			//if($Massef != $Masse)
				$Autonomie=round($Autonomie_ori - ($Diff_Puiss*($Massef/10)) + $Autonomie);
			/*elseif($Autonomie)
				$Autonomie=$Autonomie_ori + $Autonomie;
			else
				$Autonomie=$Autonomie_ori;*/
			if($Autonomie > $Autonomie_serie *2)
				$Autonomie=$Autonomie_serie *2;
			$ManB=round($ManB - $Diff_Chg_Alaire + ($Diff_Rap*25) - $Diff_Surf_Alaire); //($Rap_Puiss*100)-$Chg_Alaire
			$ManH=round($ManH - $Diff_Chg_Alaire + ($Diff_Rap*25) - $Diff_Surf_Alaire);
			$Mani=round($Mani - $Diff_Chg_Alaire);
			if($Robustesse > $Robustesse_serie + 200)
				$Robustesse=$Robustesse_serie + 200;			
			$query="UPDATE Avions_Sandbox SET ChargeAlaire='$Surf_Alaire',ArmePrincipale='$Armep',Arme1_Nbr='$Armep_nbr',Arme1_Mun='$Arme1_Mun',ArmeSecondaire='$Armes',Arme2_Nbr='$Armes_nbr',Arme2_Mun='$Arme2_Mun',Blindage='$Blinde',Munitions1='$Mun1',Munitions2='$Mun2',Bombe='$Bombe_new',Bombe_Nbr='$Bombe_new_nbr',
			Cellule='$cellule',Camouflage='$camouflage',Helice='$helice',Volets='$volets',Moteur='$moteur',Navigation='$navi',Radar='$radar',Radio='$radio',Reservoir='$reservoir',Baby='$reservoirl',Train='$train',Voilure='$voilure',Verriere='$verriere',Viseur='$viseur',
			Robustesse='$Robustesse',Detection='$Detection',Autonomie='$Autonomie',Plafond='$Plafond',VitesseH='$VitesseH',VitesseB='$VitesseB',ManoeuvreH='$ManH',ManoeuvreB='$ManB',Visibilite='$Vis',Puissance='$Puissance',Masse='$Massef',
			VitesseA='$VitesseA',VitesseP='$VitesseP',Maniabilite='$Mani',Avion_BombeT='$bombe_type' WHERE ID='$ID'";
			$con=dbconnecti();
			$ok=mysqli_query($con,$query);
			mysqli_close($con);
			if($ok)
			{
				$mes.="<p><b>Avion personnel mis à jour!</b></p><hr>";
				$img.="<table class='table table-striped'>
							<thead><tr><th></th><th>Avant changements</th><th>Après changements</th></tr></thead>
							<tr><th>Robustesse</th><th>".$Robustesse_ori."</th><th>".$Robustesse."</th></tr>
							<tr><th>Masse au décollage</th><th>".$Masse." kg</th><th>".$Massef." kg</th></tr>
							<tr><th>Puissance</th><th>".$Puissance_ori." cv</th><th>".$Puissance." cv</th></tr>
							<tr><th title='Vitesse maximale à altitude moyenne'>Vitesse maximale</th><th>".$VitesseH_ori." km/h</th><th>".$VitesseH." km/h</th></tr>
							<tr><th title='Taux de montée'>Vitesse ascensionnelle</th><th>".$VitesseA_ori."</th><th>".$VitesseA."</th></tr>
							<tr><th title='Distance franchissable'>Autonomie maximale</th><th>".$Autonomie_ori." km</th><th title='Si négative,votre avion ne pourra pas décoller. Vous devez réduire sa masse !'>".$Autonomie." km</th></tr>
							<tr><th title='Altitude maximale'>Plafond maximal</th><th>".$Plafond_ori." m</th><th>".$Plafond." m</th></tr>
							<tr><th title='Manoeuvrabilité au-dessus de 6000m'>Manoeuvrabilité haute</th><th>".$ManH_ori."</th><th>".$ManH."</th></tr>
							<tr><th title='Manoeuvrabilité au-dessous de 6000m'>Manoeuvrabilité basse</th><th>".$ManB_ori."</th><th>".$ManB."</th></tr>
							<tr><th>Taux de roulis</th><th>".$Mani_ori."</th><th>".$Mani."</th></tr>
							<tr><th title='Plus le rapport poids/puissance est petit,plus votre avion est puissant'>Rapport poids/puissance</th><th>".round($Poids_Puiss_ori,2)."</th><th>".round($Poids_Puiss,2)."</th></tr>
							</table>
							<p class='lead'>Plus le rapport poids/puissance est petit,plus l'avion est puissant<br>Si l'autonomie est négative,votre avion ne pourra pas décoller. Vous devez réduire sa masse !</p>";
				$img=Afficher_Image('images/avions/coupe'.$ID_ref.'.jpg','images/avions/garage'.$ID_ref.'.jpg',$Nom);
				//$img='<table><tr><td><img src=\'images/coupe'.$ID_ref.'.gif\'></td></tr></table>';
				SetData("Joueur","S_HP",$Robustesse,"ID",$PlayerID);
			}
			else
			{
				$mes.='<br>Erreur de mise à jour';
				$img.='';
			}
		}
		else
			header("Location: ./tsss.php");
	}
	include_once('./index.php');
}
else
	header("Location: ./tsss.php");
