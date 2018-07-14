<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID=Insec($_POST['Pilote']);
$cible=Insec($_POST['Cible']);
$base=Insec($_POST['Base']);
$Front_sandbox=Insec($_POST['Front']);
//$terrain=Insec($_POST['Terrain']);
$avion=Insec($_POST['Avion']);
$Cr_Mission=Insec($_POST['Crm']);
$Type_Mission=Insec($_POST['Type_M']);
//Check Joueur Valide
if(isset($_SESSION['AccountID']) AND $PlayerID >0 and $avion >0 and $base >0 AND !empty($_POST))
{
	include_once('./jfv_air_inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_avions.inc.php');
	$autonomie1=Insec($_POST['a1']);
	$autonomie2=Insec($_POST['a2']);
	$autonomie3=Insec($_POST['a3']);
	$AvionDispo1=Insec($_POST['an1']);
	$AvionDispo2=Insec($_POST['an2']);
	$AvionDispo3=Insec($_POST['an3']);
	$Sandbox=Insec($_POST['sandbox']);
	$ia_pilots=Insec($_POST['ia_pilots']);
	$country=$_SESSION['country'];
	$Saison=$_SESSION['Saison'];
	$Chk_Decollage=$_SESSION['Decollage'];
	$finmission=false;	
	if($Sandbox)
	{
		$PlayerID=$_SESSION['PlayerID'];
		if(strpos($avion,"_") !==false)
		{
			$avion=strstr($avion,'_',true);
			$skills.="<br> avion=".$avion;
			$Sandbox=2;
		}
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote SET S_Escorte=0,S_Escorte_nbr=0,S_Escorteb=0,S_Escorteb_nbr=0,S_Equipage_Nbr=1,S_Leader=0,S_Ailier=4,enis=0,avion_eni=0,S_Mission=3,Skill_Ins=0 WHERE ID='$PlayerID'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-resetsand');
		mysqli_close($con);
		UpdateCarac($PlayerID,"Free",-1);
		UpdateCarac($PlayerID,"As_Missions",1);
	}
	if($Chk_Decollage and !$Sandbox)
	{
		$mes='<p><b>Le rafraichissement de page est enregistré par le serveur. Plus vous effectuez cette action, plus vous êtes pénalisé.</b></p>';
		UpdateCarac($PlayerID,"Free",-1);
		//mail('binote@hotmail.com',"Aube des Aigles: Init Mission F5 (takeoff) : ".$PlayerID,"Joueur ".$PlayerID." (IP ".$_SERVER['REMOTE_ADDR'].") depuis la page ".$_SERVER['HTTP_REFERER']." a tenté de charger la page ".$_SERVER['REQUEST_URI']." en utilisant ".$_SERVER['HTTP_USER_AGENT']);
	}
	$con=dbconnecti();
	$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
	$result=mysqli_query($con,"SELECT Unit,Reputation,Front,Missions_Max,S_Mission,Vue,Moral,Courage,Avancement,Ailier,S_Ailier,Equipage,Pays,Heure_Mission,S_Longitude,S_Latitude,S_Chargeurs
	FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-player');
	$result3=mysqli_query($con,"SELECT Nom,Zone,Meteo FROM Lieu WHERE ID='$base'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-base');
	//mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unite=$data['Unit'];
			$Front=$data['Front'];
			$Missions_Max=$data['Missions_Max'];
			$Reputation=$data['Reputation'];
			$Moral=$data['Moral'];
			$Courage=$data['Courage'];
			$Avance=$data['Avancement'];
			$Equipage=$data['Equipage'];
			$Pays=$data['Pays'];
			$Heure_Mission=$data['Heure_Mission'];
			$Mission_Type=$data['S_Mission'];
			$Longitude=$data['S_Longitude'];
			$Latitude=$data['S_Latitude'];
			$Chargeurs=$data['S_Chargeurs'];
			if($Sandbox)
				$Ailier=$data['S_Ailier'];
			else
				$Ailier=$data['Ailier'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($result3)
	{
		while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
		{
			$terrain=$data['Nom'];
			$Zone_base=$data['Zone'];
			$Meteo=$data['Meteo'];
		}
		mysqli_free_result($result3);
		unset($data);
	}
	//Si l'avion perso n'est pas choisi par le joueur
	if($avion ==9991 or $avion ==9992 or $avion ==9993 or $Sandbox ==1)
		$Avion_db="Avion";
	elseif($Sandbox ==2)
		$Avion_db="Avions_Sandbox";
	else
		$Avion_db="Avions_Persos";
	$AvionDispo=$AvionDispo1;
//Si pas avion perso, avion de l'unité sélectionné en fonction du choix du joueur (pour correspondance bombes par défaut du Cdt)
	if($Avion_db =="Avion")
	{
		switch($avion)
		{
			case 9991:
				//$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Type,Reputation,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Avion1_Bombe,Avion1_Bombe_Nbr,Avion1_BombeT,Avion1_Mun1,U_Chargeurs FROM Unit WHERE ID='$Unite'")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-bomb');
				//mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Unite_Type=$data['Type'];
						$Unite_Reput=$data['Reputation'];
						$avion=$data['Avion1'];
						$Avion1=$data['Avion1'];
						$Avion2=$data['Avion2'];
						$Avion3=$data['Avion3'];
						$Avion_Nbr=$data['Avion1_Nbr'];
						$Avion1_Nbr=$data['Avion1_Nbr'];
						$Avion2_Nbr=$data['Avion2_Nbr'];
						$Avion3_Nbr=$data['Avion3_Nbr'];
						$Avion1_Bombe=$data['Avion1_Bombe'];
						$Avion_Bombe=$data['Avion1_Bombe'];
						$Avion_Bombe_nbr=$data['Avion1_Bombe_Nbr'];
						$Avion_BombeT=$data['Avion1_BombeT'];
						$Avion_Mun=$data['Avion1_Mun1'];
						$Chargeurs=$data['U_Chargeurs'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				$Autonomie=$autonomie1;
			break;
			case 9992:
				//$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Type,Reputation,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Avion2_Bombe,Avion2_Bombe_Nbr,Avion2_BombeT,Avion2_Mun1,U_Chargeurs FROM Unit WHERE ID='$Unite'")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-bomb');
				//mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Unite_Type=$data['Type'];
						$Unite_Reput=$data['Reputation'];
						$avion=$data['Avion2'];
						$Avion1=$data['Avion1'];
						$Avion2=$data['Avion2'];
						$Avion3=$data['Avion3'];
						$Avion_Nbr=$data['Avion2_Nbr'];
						$Avion1_Nbr=$data['Avion1_Nbr'];
						$Avion2_Nbr=$data['Avion2_Nbr'];
						$Avion3_Nbr=$data['Avion3_Nbr'];
						$Avion2_Bombe=$data['Avion2_Bombe'];
						$Avion_Bombe=$data['Avion2_Bombe'];
						$Avion_Bombe_nbr=$data['Avion2_Bombe_Nbr'];
						$Avion_BombeT=$data['Avion2_BombeT'];
						$Avion_Mun=$data['Avion2_Mun1'];
						$Chargeurs=$data['U_Chargeurs'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				$Autonomie=$autonomie2;
				$AvionDispo=$AvionDispo2;
			break;
			case 9993:
				//$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Type,Reputation,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Avion3_Bombe,Avion3_Bombe_Nbr,Avion3_BombeT,Avion3_Mun1,U_Chargeurs FROM Unit WHERE ID='$Unite'")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-bomb');
				//mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Unite_Type=$data['Type'];
						$Unite_Reput=$data['Reputation'];
						$avion=$data['Avion3'];
						$Avion1=$data['Avion1'];
						$Avion2=$data['Avion2'];
						$Avion3=$data['Avion3'];
						$Avion_Nbr=$data['Avion3_Nbr'];
						$Avion1_Nbr=$data['Avion1_Nbr'];
						$Avion2_Nbr=$data['Avion2_Nbr'];
						$Avion3_Nbr=$data['Avion3_Nbr'];
						$Avion3_Bombe=$data['Avion3_Bombe'];
						$Avion_Bombe=$data['Avion3_Bombe'];
						$Avion_Bombe_nbr=$data['Avion3_Bombe_Nbr'];
						$Avion_BombeT=$data['Avion3_BombeT'];
						$Avion_Mun=$data['Avion3_Mun1'];
						$Chargeurs=$data['U_Chargeurs'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				$Autonomie=$autonomie3;
				$AvionDispo=$AvionDispo3;
			break;
			default:
				//$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Bombe,Bombe_Nbr,Avion_BombeT,Munitions2,Autonomie FROM Avion WHERE ID='$avion'")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-bomb');
				$result2=mysqli_query($con,"SELECT Type,Reputation,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Avion1_Bombe,Avion2_Bombe,Avion3_Bombe FROM Unit WHERE ID='$Unite'")
				or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-unit');
				//mysqli_close($con);
				if($result2)
				{
					while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
					{
						$Unite_Type=$data['Type'];
						$Unite_Reput=$data['Reputation'];
						$Avion1=$data2['Avion1'];
						$Avion2=$data2['Avion2'];
						$Avion3=$data2['Avion3'];
						$Avion1_Nbr=$data2['Avion1_Nbr'];
						$Avion2_Nbr=$data2['Avion2_Nbr'];
						$Avion3_Nbr=$data2['Avion3_Nbr'];
						$Avion1_Bombe=$data['Avion1_Bombe'];
						$Avion2_Bombe=$data['Avion2_Bombe'];
						$Avion3_Bombe=$data['Avion3_Bombe'];
					}
					mysqli_free_result($result2);
				}
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Avion_Bombe=$data['Bombe'];
						$Avion_Bombe_nbr=$data['Bombe_Nbr'];
						$Avion_BombeT=$data['Avion_BombeT'];
						$Avion_Mun=$data['Munitions2'];
						$Autonomie=$data['Autonomie'];
					}
					mysqli_free_result($result);
					unset($data);
				}
			break;
		}
	}
	else
	{
		//$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Bombe,Bombe_Nbr,Avion_BombeT,Autonomie FROM $Avion_db WHERE ID='$avion'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-bomb');
		$result2=mysqli_query($con,"SELECT Type,Reputation,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Avion1_Bombe,Avion2_Bombe,Avion3_Bombe,Avion1_Bombe_Nbr,Avion2_Bombe_Nbr,Avion3_Bombe_Nbr FROM Unit WHERE ID='$Unite'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-unit');
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avion_Bombe=$data['Bombe'];
				$Avion_Bombe_nbr=$data['Bombe_Nbr'];
				$Avion_BombeT=$data['Avion_BombeT'];
				$Autonomie=$data['Autonomie'];
			}
			mysqli_free_result($result);
			unset($data);
			unset($result);
		}
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Unite_Type=$data['Type'];
				$Unite_Reput=$data['Reputation'];
				$Avion1=$data2['Avion1'];
				$Avion2=$data2['Avion2'];
				$Avion3=$data2['Avion3'];
				$Avion1_Nbr=$data2['Avion1_Nbr'];
				$Avion2_Nbr=$data2['Avion2_Nbr'];
				$Avion3_Nbr=$data2['Avion3_Nbr'];
				$Avion1_Bombe=$data['Avion1_Bombe'];
				$Avion2_Bombe=$data['Avion2_Bombe'];
				$Avion3_Bombe=$data['Avion3_Bombe'];
				$Avion1_Bombe_Nbr=$data['Avion1_Bombe_Nbr'];
				$Avion2_Bombe_Nbr=$data['Avion2_Bombe_Nbr'];
				$Avion3_Bombe_Nbr=$data['Avion3_Bombe_Nbr'];
			}
			mysqli_free_result($result2);
		}
		if($Zone_base ==6 and $Front ==3)$Autonomie*=2;
	}
	$Autonomie_max=array($Autonomie);
	//$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,Robustesse,Type,Plafond,ArmePrincipale,Arme1_Nbr,Arme1_Mun,ArmeSecondaire,Arme2_Nbr,Arme2_Mun,Arme3_Nbr,Arme4_Nbr,Arme5_Nbr,Arme6_Nbr,Engine_Nbr,Train,Helice,ManoeuvreB,Equipage,Baby 
	FROM $Avion_db WHERE ID='$avion'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-avion');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$NomAvion=$data['Nom'];
			$HP=$data['Robustesse'];
			$Type_avion=$data['Type'];
			$Plafond=$data['Plafond'];
			$Arme1Avion=$data['ArmePrincipale'];
			$Arme2Avion=$data['ArmeSecondaire'];
			$Arme1Avion_nbr=$data['Arme1_Nbr'];
			$Arme2Avion_nbr=$data['Arme2_Nbr'];
			$Arme3Avion_nbr=$data['Arme3_Nbr'];
			$Arme4Avion_nbr=$data['Arme4_Nbr'];
			$Arme5Avion_nbr=$data['Arme5_Nbr'];
			$Arme6Avion_nbr=$data['Arme6_Nbr'];
			$Mun1=$data['Arme1_Mun'];
			$Mun2=$data['Arme2_Mun'];
			$Engine_Nbr=$data['Engine_Nbr'];
			$Equipage_Nbr=$data['Equipage'];
			$ManoeuvreB=$data['ManoeuvreB'];
			$Helice=$data['Helice'];
			$Train=$data['Train'];
			$Baby=$data['Baby'];
		}
		mysqli_free_result($result);
		unset($data);
	}	
	if(!$Mun1 and $Arme1Avion_nbr)
		$Mun1=$Arme1Avion_nbr*GetData("Armes","ID",$Arme1Avion,"Munitions");
	if(!$Mun2 and $Arme2Avion_nbr)
		$Mun2=$Arme2Avion_nbr*GetData("Armes","ID",$Arme2Avion,"Munitions");
	$avion_img=GetAvionImg($Avion_db,$avion);	
	$Grade_a=GetAvancement($Avance,$Pays);
	SetData("Pilote","S_Avancement_mission",$Grade_a[1],"ID",$PlayerID);
	unset($Grade_a);
	//Set Session Vars
	if(!$Chk_Decollage or $Sandbox)
	{
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote SET S_HP='$HP',S_Tourelle_Mun=0,Stress_Commandes=0,Stress_Train=0,Stress_Moteur=0,Stress_Arme1=0,Stress_Arme2=0,S_Avion_db='$Avion_db',S_Cible='$cible',S_Engine_Nbr='$Engine_Nbr',S_Avion_Mun='$Avion_Mun',S_Avion_Bombe='$Avion_Bombe',S_Avion_Bombe_Nbr='$Avion_Bombe_nbr',S_Avion_BombeT='$Avion_BombeT',S_Baby='$Baby',S_Equipage_Nbr='$Equipage_Nbr' 
		WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-reset');
		mysqli_close($con);
	}	
	if($Autonomie <10)
	{
		$img='<img src=\'images/avions/garage'.$avion_img.'.jpg\' style=\'width:100%;\'>';
		$mes.='<p>Votre avion ne peut pas décoller car sa masse est trop importante.<br>Retournez au hangar pour l\'alléger!</p>';
		$finmission=true;
	}
	elseif(!$cible)
	{
		$img='<img src=\'images/avions/garage'.$avion_img.'.jpg\' style=\'width:100%;\'>';
		$mes.='<p>Aucune cible définie, échec de la mission!</p>';
		$finmission=true;
	}
	else
	{
		$today=getdate();
		$Heure=$today['hours'];
		if($ia_pilots)
		{
			$pilots_nbr=count($ia_pilots);
			if($Avion_db !="Avion")
			{
				if($Avion1_Nbr >=$pilots_nbr)
				{
					$Ailier_Avion=$Avion1;
					$AvionDispo=$Avion1_Nbr;
					$Ailier_Bombe=$Avion1_Bombe;
					$Ailier_Bombe_Nbr=$Avion1_Bombe_Nbr;
					if(!$autonomie1)
					{
						$autonomie1=GetData("Avion","ID",$Avion1,"Autonomie");
						if($Avion1_Bombe ==350)
						{
							$Array_Mod=GetAmeliorations($Avion1);
							$autonomie1+=$Array_Mod[18];
							unset($Array_Mod);
						}
						if($Zone_base ==6 and $Front ==3)
							$autonomie1*=2;
					}
					$Autonomie_max[]=$autonomie1;
				}
				elseif($Avion2_Nbr >=$pilots_nbr)
				{
					$Ailier_Avion=$Avion2;
					$AvionDispo=$Avion2_Nbr;
					$Ailier_Bombe=$Avion2_Bombe;
					$Ailier_Bombe_Nbr=$Avion2_Bombe_Nbr;
					if(!$autonomie2)
					{
						$autonomie2=GetData("Avion","ID",$Avion2,"Autonomie");
						if($Avion2_Bombe ==350)
						{
							$Array_Mod=GetAmeliorations($Avion2);
							$autonomie2+=$Array_Mod[18];
							unset($Array_Mod);
						}
						if($Zone_base ==6 and $Front ==3)
							$autonomie1*=2;
					}
					$Autonomie_max[]=$autonomie2;
				}
				elseif($Avion3_Nbr >=$pilots_nbr)
				{
					$Ailier_Avion=$Avion3;
					$AvionDispo=$Avion3_Nbr;
					$Ailier_Bombe=$Avion3_Bombe;
					$Ailier_Bombe_Nbr=$Avion3_Bombe_Nbr;
					if(!$autonomie3)
					{
						$autonomie3=GetData("Avion","ID",$Avion3,"Autonomie");
						if($Avion3_Bombe ==350)
						{
							$Array_Mod=GetAmeliorations($Avion3);
							$autonomie3+=$Array_Mod[18];
							unset($Array_Mod);
						}
						if($Zone_base ==6 and $Front ==3)
							$autonomie1*=2;
					}
					$Autonomie_max[]=$autonomie3;
				}
				elseif($Avion1_Nbr)
				{
					$Ailier_Avion=$Avion1;
					$AvionDispo=$Avion1_Nbr;
					$Ailier_Bombe=$Avion1_Bombe;
					$Ailier_Bombe_Nbr=$Avion1_Bombe_Nbr;
					$Autonomie_max[]=$autonomie1;
				}
				else
					$AvionDispo=0;
			}
			else
				$Ailier_Avion=$avion_img;
			$MaxFlight=GetMaxFlight($Unite_Type,$Unite_Reput,0);
			if($AvionDispo >$MaxFlight)$AvionDispo=$MaxFlight;
			if($AvionDispo <$pilots_nbr)
			{
				$ia_pilots=array_slice($ia_pilots,0,$AvionDispo,true);
				$mes.="<p>Il n'y a pas suffisamment d'avions disponibles pour le nombre de pilotes sélectionnés. Les pilotes excédentaires resteront à la base.</p>";
			}
			if(is_array($ia_pilots))			
				$ia_pilotes=implode(',',$ia_pilots);
			if($ia_pilotes)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible='$cible',Missions=Missions+1,Avion='$Ailier_Avion' WHERE ID IN (".$ia_pilotes.")") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-cible-ia');
				mysqli_close($con);
			}
			$Formation=count($ia_pilots);
			if($Ailier)$Ailier_Avion_Nom=GetData("Avion","ID",$Ailier_Avion,"Nom");
		}		
		//Equipage
		if($Equipage_Nbr >1)
		{
			$Endu_Eq=GetData("Equipage","ID",$Equipage,"Endurance");
			if($Equipage and $Endu_Eq)
				$Equipage_Nom=GetData("Equipage","ID",$Equipage,"Nom");
		}
		elseif($Type_avion !=3 and $Ailier)
		{
			$Ailier_Nom=GetData("Pilote_IA","ID",$Ailier,"Nom");
			/*elseif(GetAvionType($avion) ==10 or $Avance >4999)
				$Ailier_Avion=GetData("Avion","ID",GetData("Unit","ID",$Unite,"Avion3"),"Nom");
			else
				$Ailier_Avion=GetData("Avion","ID",GetData("Unit","ID",$Unite,"Avion1"),"Nom");*/
		}
		if($Sandbox or !$Ailier_Avion_Nom)$Ailier_Avion_Nom=$NomAvion;	
		//Distance Objectif, coordonnées carte
		$Dist=GetDistance($base,$cible);
		$Distance=$Dist[0];
		if($Distance <10)$Distance=10;
		$_SESSION['SensH']=$Dist[1];
		$_SESSION['SensV']=$Dist[2];
		if($Mission_Type <90)
		{
			$_SESSION['Long_par_km']=$Dist[3]/$Distance;
			$_SESSION['Lat_par_km']=$Dist[4]/$Distance;
		}
		unset($Dist);		
		//Choix cible mission		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Zone,Port,NoeudF,NoeudR,Plage,Recce FROM Lieu WHERE ID='$cible'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-cible');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$NomCible=$data['Nom'];
				$Zone=$data['Zone'];
				$Cible_Port=$data['Port'];
				$NoeudF=$data['NoeudF'];
				$NoeudR=$data['NoeudR'];
				$Plage=$data['Plage'];
				$Recce_Base=$data['Recce'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		//Atk-Bomb
		if($Mission_Type ==1 or $Mission_Type ==2)
		{			
			if($Zone ==6)
			{
				$mes.="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée sur un objectif maritime !</p>";
				$finmission=true;
			}
			else
			{
				$con=dbconnecti();
				//$pj_unit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$cible' AND Vehicule_Nbr >0 AND Visible=1"),0);
				$pj_unit2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$cible' AND Vehicule_Nbr >0 AND Visible=1"),0);
				mysqli_close($con);
				//$pj_unit+=$pj_unit2;
				if($pj_unit)
				{
					$cible_bomb=6; //Unité joueur sur place
					SetData("Pilote","S_Cible_Atk",$cible_bomb,"ID",$PlayerID);
				}
				switch($Mission_Type)
				{
					case 1:
						$intro="<p>Votre mission consiste à attaquer des cibles mobiles au sol à l'aide de vos armes de bord.</p>";
						$Mission_Type_txt="appui rapproché";
					break;
					case 2:
						$intro="<p>Votre mission consiste à attaquer des cibles mobiles au sol à l'aide de <b>bombes</b>.</p>";
						$Mission_Type_txt="attaque à la bombe";
					break;
				}
				//$mis.="<br>Aujourd'hui, votre cible est <b> un".$cible_b."</b> située près de <b>".$NomCible."</b>";
				$intro.='<p>Aujourd\'hui, votre cible est située dans les environs de <b>'.$NomCible.'</b></p>';
			}
		}
		elseif($Mission_Type ==5)
		{
			$intro.="<p>Votre mission consiste à détecter la présence éventuelle d'unités ennemies, et ramener ces informations à votre base.</p>";
			$Mission_Type_txt="reconnaissance";
		}
		elseif($Mission_Type ==15)
		{
			$intro.="<p>Votre mission consiste à prendre des photos de l'objectif, et à les ramener à votre base.</p>";
			$Mission_Type_txt="reconnaissance";
		}
		elseif($Mission_Type ==7 or $Mission_Type ==17)
		{
			$intro.='<p>Votre mission consiste à patrouiller jusqu\'à '.$NomCible.' et intercepter tous les avions rencontrés.</p>';
			$Mission_Type_txt="chasse";
		}
		elseif($Mission_Type ==3)
		{
			$intro.='<p>Votre mission consiste à patrouiller jusqu\'à '.$NomCible.' et engager ou non les avions, à votre guise.</p>';
			$Mission_Type_txt="chasse";
		}
		elseif($Mission_Type == 26)
		{
			$intro.='<p>Votre mission consiste à patrouiller jusqu\'à '.$NomCible.' et éliminer un maximum de chasseurs ennemis.</p>';
			$Mission_Type_txt="chasse";
		}
		elseif($Mission_Type ==4)
		{
			if($country ==3)
				$Escorteb=18;
			else
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID FROM Unit WHERE Mission_Lieu_D='$cible' AND Mission_Type_D=4 AND Pays='$country'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Unit_Demande=$data['ID'];
					}
					mysqli_free_result($result);
				}				
				if(!$Unit_Demande)$Unit_type=GetData("Unit","ID",$Unite,"Type");
				$Escorteb=Random_Escort($PlayerID,$country,$Unit_type,$Unit_Demande,$Longitude,$Latitude);
			}
			$Escorteb_nom=GetData("Avion","ID",$Escorteb,"Nom");
			$Escorteb_nbr=mt_rand(6,12);
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET S_Escorteb='$Escorteb',S_Escorteb_nom='$Escorteb_nom',S_Escorteb_nbr='$Escorteb_nbr' WHERE ID='$PlayerID'")
			or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-reset2');
			mysqli_close($con);
			$intro='<br>Votre mission consiste à escorter une formation de <b>'.$Escorteb_nom.'</b> jusqu\'à l\'objectif.';
			$Mission_Type_txt="escorte";
		}
		elseif($Mission_Type >100)
		{
			$intro.="<br>Votre mission consiste à vous perfectionner au maniement de votre appareil.";
			$Mission_Type_txt="entrainement";
		}
		elseif($Mission_Type >97)
		{
			$Distance=10;
			$intro.="<br>Votre mission consiste à vous perfectionner au maniement de votre appareil.";
			$Mission_Type_txt="entrainement";
		}
		elseif($Mission_Type ==9)
		{
			$Distance=10;
			$intro.="<br>Votre mission consiste à intercepter la formation ennemie.";
			$Mission_Type_txt="interception";
		}
		elseif($Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13)
		{
			if($Zone !=6 and !$Cible_Port and !$Plage)
			{
				$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée que sur un objectif maritime !</p>";
				$finmission=true;
			}
			else
			{
				$con=dbconnecti();
				//$Nav_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$cible' AND Vehicule_Nbr >0 AND Visible=1 AND Position<>25 AND Pays<>'$country' AND Vehicule_ID >4999"),0);
				$Nav_eni_ia=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$cible' AND Vehicule_Nbr >0 AND Visible=1 AND Position<>25 AND Pays<>'$country' AND (Vehicule_ID >4999 OR Transit_Veh=5000)"),0);
				mysqli_close($con);
				if($Nav_eni or $Nav_eni_ia)$cible_bomb=20;
				if(!$cible_bomb and !$Nav_eni and !$Nav_eni_ia)
				{
					$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Aucun navire n'a été reconnu à cet endroit !</p>";
					$finmission=true;
				}
			}
			$img=Afficher_Image('images/transfer_no'.$country.'.jpg', 'images/image.png','');
		}
		elseif($Mission_Type ==14)
		{
			if($Zone !=6 and !$Cible_Port)
			{
				$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée que sur un objectif maritime !</p>";
				$finmission=true;
			}
			else
			{
				$intro='<p>Votre mission consiste à mouiller des mines maritimes dans les environs de '.$NomCible.'.</p>';
				$Mission_Type_txt="mouillage de mines";
			}
		}
		elseif($Mission_Type ==29)
		{
			if($Zone !=6 and !$Cible_Port and !$Plage)
			{
				$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée que sur un objectif maritime !</p>";
				$finmission=true;
			}
			else
			{
				$intro='<p>Votre mission consiste à patrouiller dans les environs de '.$NomCible.' et à attaquer tout sous-marin ennemi détecté.</p>';
				$Mission_Type_txt="patrouille ASM";
			}
		}
		elseif($Mission_Type ==23)
		{
			$intro="<p>Votre mission consiste à amener votre cargaison intacte jusqu'à votre destination.</p>";
			$Mission_Type_txt="ravitaillement";
		}
		elseif($Mission_Type ==24 or $Mission_Type ==25)
		{
			if($Zone ==6)
			{
				$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée sur un objectif maritime !</p>";
				$finmission=true;
			}
			elseif($Formation >2)
			{
				if($Heure <10)
				{
					$mes="<p>Il est trop tôt pour larguer des parachutistes, attendez au moins 10h !</p>";
					$finmission=true;
				}
				else
				{
					//Sélection Paras
					$cible_select_txt="<select name='Cible_Atk' class='form-control' style='width: 300px'>";				
					/*$con=dbconnecti();
					$result=mysqli_query($con,"SELECT ID,Nom FROM Officier WHERE Pays='$country' AND Front='$Front' AND Para_Lieu>0");
					mysqli_close($con);
					if($result)
					{
						while($dataa=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Cdo_ID=$dataa['ID'];
							$Cdo_Nom=$dataa['Nom'];
							$cible_select_txt.="<option value='".$Cdo_ID."'>".$Cdo_Nom."</option>";
						}
						mysqli_free_result($result);
						unset($dataa);
					}
					$cible_select_txt.='</select>';*/
					if(!$Cdo_ID)
					{
						$mes="<p>Aucun parachutiste ne vous attend sur l'aérodrome !</p>";
						$finmission=true;
					}
					else
					{
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pilote SET S_Cible_Atk='$Cdo_ID',S_Avion_Bombe=100,S_Avion_Bombe_Nbr=10 WHERE ID='$PlayerID'");
						mysqli_close($con);
						$cible_select_titre="Parachutistes";
						$intro="<p>Votre mission consiste à larguer des parachutistes sur votre objectif.</p>";
						$Mission_Type_txt="parachutage";
					}
				}
			}
			else
			{
				$mes="<p>Vous devez sélectionner 4 pilotes et disposer de 4 avions de transport en état de vol pour transporter une unité terrestre !</p>";
				$finmission=true;
			}			
		}
		elseif($Mission_Type ==27 or $Mission_Type ==28)
		{
			if($Zone ==6)
			{
				$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée sur un objectif maritime !</p>";
				$finmission=true;
			}
			else
			{
				//Sélection Pilote
				$cible_select_titre="Commando";
				$cible_select_txt="<select name='Cible_Atk' class='form-control' style='width: 300px'>";				
				//GetData Cdo
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT ID,Nom FROM Pilote WHERE Pays='$country' AND Front='$Front' AND Commando=1 AND ID<>'$PlayerID'");
				mysqli_close($con);
				if($result)
				{
					while($dataa=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Cdo_ID=$dataa['ID'];
						$Cdo_Nom=$dataa['Nom'];
						$cible_select_txt.="<option value='".$Cdo_ID."'>".$Cdo_Nom."</option>";
					}
					mysqli_free_result($result);
					unset($dataa);
				}
				$cible_select_txt.='</select>';
				SetData("Pilote","S_Cible_Atk",$Cdo_ID,"ID",$PlayerID);
				$intro.="<p>Votre mission consiste à larguer un commando sur votre objectif.</p>";
				$Mission_Type_txt="commando";
			}
		}
		elseif($Mission_Type ==6 or $Mission_Type ==8 or $Mission_Type ==16 or $Mission_Type ==31)
		{
			if($Zone ==6)
			{
				$mes="<p>Vous sélectionnez n'importe quoi comme objectif...<br>Cette mission ne peut être réalisée sur un objectif maritime !</p>";
				$finmission=true;
			}
			else
			{
				if($Avance >499)
				{
					//Sélection Cible statique
					$cible_select_titre="Cible";
					$cible_select_txt="<select name='Cible_Atk' class='form-control' style='width: 300px'>";				
					//GetData Lieu
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Port,Pont,NoeudF,Industrie,Radar,BaseAerienne,Recce FROM Lieu WHERE ID='$cible'");
					mysqli_close($con);
					if($result)
					{
						while($dataa=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Cible_Port=$dataa['Port'];
							$Cible_Pont=$dataa['Pont'];
							$Cible_NoeudF=$dataa['NoeudF'];
							$Cible_Industrie=$dataa['Industrie'];
							$Cible_Radar=$dataa['Radar'];
							$Cible_BaseAerienne=$dataa['BaseAerienne'];
							$Recce_Base=$dataa['Recce'];
						}
						mysqli_free_result($result);
						unset($dataa);
					}
					if($Recce_Base >0)
					{
						if($Cible_Port)
						{
							$cible_bomb=6;
							$cible_b=" port";
							$cible_select_txt.="<option value='".$cible_bomb."'>le".$cible_b."</option>";
						}
						if($Cible_Pont)
						{
							$cible_bomb=5;
							$cible_b=" pont";
							$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
						}
						if($Cible_BaseAerienne)
						{
							$cible_bomb=1;
							$cible_b=" aérodrome";
							$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
						}
						if($Cible_NoeudF)
						{
							$cible_bomb=4;
							$cible_b="e gare";
							$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
						}
						if($Cible_Industrie and $Mission_Type !=31)
						{
							$cible_bomb=2;
							$cible_b="e usine";
							$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
						}
						if($Cible_Radar)
						{
							$cible_bomb=7;
							$cible_b=" radar";
							$cible_select_txt.="<option value='".$cible_bomb."'>un".$cible_b."</option>";
						}
					}
					$cible_select_txt.='<option value=\'3\'>une caserne</option></select>';
					$intro='<br>Votre objectif est '.$NomCible.'. Votre commandant fait appel à votre sens tactique pour sélectionner la cible adéquate.';
					SetData("Pilote","S_Cible_Atk",0,"ID",$PlayerID);
				}
				else
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT BaseAerienne,Pont,NoeudF,Industrie FROM Lieu WHERE ID='$cible'");
					mysqli_close($con);
					if($result)
					{
						while($dataa=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Cible_BaseAerienne=$dataa['BaseAerienne'];
							$Cible_Pont=$dataa['Pont'];
							$Cible_NoeudF=$dataa['NoeudF'];
							$Cible_Industrie=$dataa['Industrie'];
						}
						mysqli_free_result($result);
						unset($dataa);
					}
					//Check cible statique
					if($Cible_BaseAerienne and $Recce_Base)
					{
						$cible_bomb=1;
						$cible_b=" aérodrome";
					}
					elseif($Cible_Pont and $Recce_Base)
					{
						$cible_bomb=5;
						$cible_b=" pont";
					}
					elseif($Cible_NoeudF and $Recce_Base)
					{
						$cible_bomb=4;
						$cible_b="e gare";
					}
					elseif($Cible_Industrie and $Recce_Base)
					{
						$cible_bomb=2;
						$cible_b="e usine";
					}
					else
					{
						$cible_bomb=3;
						$cible_b="e caserne";
					}
					$intro='<p>Aujourd\'hui, votre cible est <b> un'.$cible_b.'</b> située près de '.$NomCible.'</p>';
					SetData("Pilote","S_Cible_Atk",$cible_bomb,"ID",$PlayerID);
				}
				switch($Mission_Type)
				{
					case 6:
						$intro.="<p>Votre mission consiste à attaquer une cible statique au sol à l'aide de vos armes de bord.</p>";
						$Mission_Type_txt="attaque";
					break;
					case 8: case 16:
						$intro.="<p>Votre mission consiste à attaquer une cible statique au sol à l'aide de <b>bombes</b>.</p>";
						$Mission_Type_txt="bombardement";
					break;
					case 31:
						$intro.="<p>Votre mission consiste à abattre les chasseurs de nuit ennemis et attaquer les cibles statiques au sol à l'aide de vos armes de bord.</p>";
						$Mission_Type_txt="harcèlement";
					break;
				}
				$Mission_Type_txt="bombardement";
				unset($cible_b);
				unset($cible_bomb);
			}
		}		
		//Escorte Amie
		if(!$Chk_Decollage)
		{		
			if($Mission_Type ==16 or $Mission_Type ==17 or $Mission_Type == 21 or $Mission_Type == 22 or $Mission_Type == 25 or $Mission_Type == 27 or $Mission_Type == 28 or $Mission_Type == 31)
				$Nuit=true;
			else
				$Nuit=false;
			//TODO : popup avec composition détaillée de l'escorte (patrouille_live)
			if($Mission_Type ==1 or $Mission_Type ==2 or $Mission_Type ==5 or $Mission_Type ==6 or $Mission_Type ==8 or $Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13 or $Mission_Type ==15 or $Mission_Type ==24)
			{			
				$con=dbconnecti();
				$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction='$Faction' AND j.Escorte='$cible' AND j.Alt<='$Plafond' AND j.Actif=1"),0);
				//$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Escorte='$cible' AND S_alt<='$Plafond' AND ID<>'$PlayerID'"),0);
				mysqli_close($con);
				if($Escorte_nbr >0)
				{					
					$con=dbconnecti();
					/*$result=mysqli_query($con,"SELECT j.ID,j.Nom,j.Unit,j.Pays,j.S_alt,l.Avion FROM Pilote as j,Patrouille_live as l,Pays as p WHERE j.ID=l.Joueur AND j.Pays=p.ID
					AND j.Escorte='$cible' AND j.S_alt<='$Plafond' AND p.Faction='$Faction'");*/
					$result=mysqli_query($con,"SELECT j.ID,j.Nom,j.Unit,j.Pays,j.Alt,j.Avion FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Escorte='$cible' AND j.Alt<='$Plafond' AND p.Faction='$Faction' AND j.Actif=1");
					mysqli_close($con);
					if($result)
					{
						$intro.="<div style='overflow:auto; height: 150px;'><table><tr><th colspan='10'>".$Escorte_nbr." Chasseurs en escorte sur votre cible</th></tr>";
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$intro.="<tr><th>".$data['Nom']." ".GetAvionIcon($data['Avion'],$data['Pays'],$data['ID'],$data['Unit'],$Front)."</th><td>".Afficher_Icone($data['Unit'],$data['Pays'])."</td><td>".$data['Alt']."m</td></tr>";
						}
						mysqli_free_result($result);
						$intro.="</table></div>";
					}
					$con=dbconnecti();
					$Cible_Escorte=mysqli_result(mysqli_query($con,"SELECT j.Unit FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction='$Faction' AND j.Escorte='$cible' AND j.Alt<='$Plafond' AND j.Actif=1 GROUP BY j.Unit ORDER BY COUNT(*) DESC"),0);
					//$Cible_Escorte=mysqli_result(mysqli_query($con,"SELECT Unit FROM Pilote WHERE Escorte='$cible' AND S_alt<='$Plafond' GROUP BY Unit ORDER BY COUNT(*) DESC"),0);
					mysqli_close($con);
					$Escorte_nom=GetData("Unit","ID",$Cible_Escorte,"Nom");
					if($Escorte_nbr >0)
					{
						SetData("Pilote","S_Escorte",$Cible_Escorte,"ID",$PlayerID);
						SetData("Pilote","S_Escorte_nbr",$Escorte_nbr,"ID",$PlayerID);
						SetData("Pilote","S_Escorte_nom",$Escorte_nom,"ID",$PlayerID);
						$intro.='<br>Votre escorte est constituée de <b>'.$Escorte_nbr.' chasseurs du '.$Escorte_nom.'</b>';
					}
					else
					{
						if($Escorte_nom)
							$intro.='<br>Les chasseurs du '.$Escorte_nom.' devant assurer votre escorte ne sont pas au rendez-vous!</b>';
						else
							$intro.="<br>Les chasseurs devant assurer votre escorte ne sont pas au rendez-vous!</b>";
					}
				}
				else
					$intro.="<br>Aucune escorte n'est disponible pour vous accompagner jusqu'à l'objectif.";
				/*elseif($Mission_Type !=15)
				{
					$Escorte=Random_Escort_Eni($country,$Longitude,$Latitude);
					if(!$Escorte[0])
						$intro.="<br><b>Aucun chasseur n'est disponible pour votre escorte</b>";
					else
					{
						$Radio=0;
						if($Equipage and $Endu_Eq)
						{
							$Radio_a=GetData($Avion_db,"ID",$avion,"Radio");
							$Radio=floor(GetData("Equipage","ID",$Equipage,"Radio")/20)+$Radio_a;
						}
						$Escorte_avion_nom=GetData("Avion","ID",$Escorte[0],"Nom");
						$Escorte_max=2+$Radio;
						$Escorte_nbr=mt_rand(2,$Escorte_max);
						SetData("Pilote","S_Escorte",$Escorte[1],"ID",$PlayerID);
						SetData("Pilote","S_Escorte_nbr",$Escorte_nbr,"ID",$PlayerID);
						$intro.='<br>Votre escorte est constituée de <b>'.$Escorte_nbr.' '.$Escorte_avion_nom.' (IA)</b>';
					}
				}*/
			}
			elseif($Mission_Type ==7 or $Mission_Type ==9 or $Mission_Type ==23)
			{
				if($cible)
				{
					$con=dbconnecti();
					$Escorte_nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote as j,Pays as p WHERE j.Pays=p.ID AND p.Faction='$Faction' AND j.Couverture='$cible' AND j.ID<>'$PlayerID'"),0);
					mysqli_close($con);
					if($Escorte_nbr >0)
					{
						$con=dbconnecti();
						$Cible_Escorte=mysqli_result(mysqli_query($con,"SELECT Unit FROM Pilote as j,Pays as p WHERE j.Pays=p.ID AND p.Faction='$Faction' AND j.Couverture='$cible' AND j.ID<>'$PlayerID' GROUP BY j.Unit ORDER BY COUNT(*) DESC"),0);
						mysqli_close($con);
						if($Cible_Escorte)
						{
							$Escorte_nom=GetData("Unit","ID",$Cible_Escorte,"Nom");
							$intro.='<br>Votre escorte est constituée de <b>'.$Escorte_nbr.' chasseurs du '.$Escorte_nom.'</b>';
							$con=dbconnecti();
							$reset=mysqli_query($con,"UPDATE Pilote SET S_Escorte_nbr='$Escorte_nbr',S_Escorte_nom='$Escorte_nom',S_Escorte='$Cible_Escorte' WHERE ID='$PlayerID'");
							mysqli_close($con);
						}
						else
							$intro.="<br><b>Aucun chasseur allié en patrouille n'est disponible pour votre escorte</b>";
					}
					else
						$intro.="<br><b>Aucun chasseur allié en patrouille n'est disponible pour votre escorte</b>";
				}
			}
			else
				$intro.="<p><b>Cette mission s'effectue sans escorte</b></p>";
		}			
		if($Mission_Type >97 and $Mission_Type <103 and $Reputation <50)
			$intro.='<p>Votre moniteur vous accompagne dans un avion d\'entrainement biplace, vous facilitant le décollage.</p>';
		if(GetData("Unit","ID",$Unite,"Porte_avions"))
			$terrain_txt="du ".GetData("Cible","ID",$Porte_avions,"Nom");
		else
			$terrain_txt='du terrain de <b>'.$terrain.'</b>';
	}
	if(!$finmission)
	{
		if($Autonomie_max)
			$Autonomie_min=min($Autonomie_max);
		if($Autonomie_min >10)
			$Autonomie=$Autonomie_min;
		$img='<img src=\'images/avions/decollage'.$avion_img.'.jpg\' style=\'width:100%;\'>';
		//Formations
		if($Mission_Type ==1 or $Mission_Type ==2 or $Mission_Type ==11 or $Mission_Type ==12 or $Mission_Type ==13)
			$Formation=0;
		elseif($Mission_Type ==8 or $Mission_Type ==16)
		{	
			if(!$Formation)
			{
				//Formations Bombardiers
				if($Date_Campagne >"1943-01-01")
					$Formation=mt_rand(1,12)*3;
				elseif($Date_Campagne >"1941-12-01")
					$Formation=mt_rand(1,8)*3;
				elseif($Date_Campagne >"1940-06-30")
					$Formation=mt_rand(1,4)*3;
				else
					$Formation=3;
				if($Equipage and $Endu_Eq)
				{
					$Radio_a=GetData($Avion_db,"ID",$avion,"Radio");
					$Radio=floor(GetData("Equipage","ID",$Equipage,"Radio")/50)+$Radio_a;
					$Formation += $Radio;
				}
			}
		}
		elseif($Mission_Type ==3 or $Mission_Type ==5 or $Mission_Type ==15 or $Mission_Type ==18 or $Mission_Type ==19 or $Mission_Type ==22 or $Mission_Type ==27 or $Mission_Type ==28 or $Mission_Type ==29 or $Mission_Type ==31)
			$Formation=0;
		//Patrouilleurs et reco en solo
		$intro.='<br>Vous vous préparez à décoller '.$terrain_txt.' aux commandes de votre <b>'.$NomAvion.'</b>';
		if($Type_avion ==9 or $Type_avion ==3)$Formation=0;			
		if($Mission_Type ==98)
			$intro.="<p>Votre instructeur vous conseille d'ouvrir les gaz en grand</p>";
		elseif($Mission_Type ==101)
			$intro.="<p>Votre instructeur vous informe qu'un avion chargé de bombes a besoin d'une piste plus longue pour décoller</p>";
		elseif($Mission_Type ==102)
			$intro.="<p>Votre instructeur vous informe que votre avion consommera plus de carburant à pleine vitesse et par mauvais temps</p>";
		elseif($Mission_Type ==103)
			$intro.="<p>Votre base vous informe que votre adversaire simulera un combat aérien avec vous au-dessus de l'aérodrome</p>";
		if($Equipage and $Endu_Eq)
			$intro.='<p><b>'.$Equipage_Nom.'</b> est à bord et procède aux dernières vérifications</p>';
		if($Formation >0)
			$intro.='<p>Votre formation est composée de <b>'.$Formation.' '.$Ailier_Avion_Nom.'</b></p>';
		elseif($Ailier and $Ailier_Avion)
			$intro.='<p>Votre ailier <b>'.$Ailier_Nom.'</b> vous accompagne, à bord d\'un <b>'.$Ailier_Avion_Nom.'</b></p>';
		/*$Prev=GetMeteo(0,0,0,$Meteo,$Nuit);
		$intro.='<p>Les prévisions météo sont les suivantes: <b>'.$Prev[0].'</b></p>';*/
		if(!$Chk_Decollage or $Sandbox)
		{
			$Tourelle_Mun=($Arme3Avion_nbr + $Arme4Avion_nbr + $Arme5Avion_nbr + $Arme6Avion_nbr)*(500*$Chargeurs);
			if($Nuit)$Meteo-=85;
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote SET S_Nuit='$Nuit',S_Formation='$Formation',S_Meteo='$Meteo',S_Tourelle_Mun='$Tourelle_Mun',S_Essence='$Autonomie',Sandbox='$Sandbox' WHERE ID='$PlayerID'");
			mysqli_close($con);			
			//Stock Unite
			if($PlayerID >0 and $Unite !=GetTraining($country) and !$Sandbox)
			{
				//Stock Formation
				if($Formation >0)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT a.ArmePrincipale,a.ArmeSecondaire,m.Carburant FROM Avion as a,gnmh_aubedesaiglesnet1.Moteur as m WHERE a.Engine=m.ID AND a.ID='$Ailier_Avion'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : takeoff-avion_form');
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Arme1Avion_Ailier=$data['ArmePrincipale'];
							$Arme2Avion_Ailier=$data['ArmeSecondaire'];
							$Carburant=$data['Carburant'];
						}
						mysqli_free_result($result);
						unset($data);
					}
					if($Carburant)
					{
						$Stock_Essence='Stock_Essence_'.$Carburant;	
						$Essence_stock=0-($Autonomie*$Formation);
						UpdateData("Unit",$Stock_Essence,$Essence_stock,"ID",$Unite);
						if($PlayerID ==1)$skills.="Les stocks de ".$Carburant." Octane de l'unité ont été réduits de ".$Essence_stock;
					}
					if($Arme1Avion_Ailier >0 and $Arme1Avion_Ailier !=5 and $Arme1Avion_Ailier !=25 and $Arme1Avion_Ailier !=26 and $Arme1Avion_Ailier !=27)
					{
						$Calibre1=round(GetData("Armes","ID",$Arme1Avion_Ailier,"Calibre"));
						if($Calibre1)
						{
							$Stock1='Stock_Munitions_'.$Calibre1;
							$Mun1_stock=0-($Mun1*$Formation);
							UpdateData("Unit",$Stock1,$Mun1_stock,"ID",$Unite);
							if($PlayerID ==1)$skills.="<br>Les stocks de ".$Calibre1."mm de l'unité ont été réduits de ".$Mun1_stock;
						}
					}
					if($Arme2Avion_Ailier !=5 and $Arme2Avion_Ailier !=0 and $Arme2Avion_Ailier !=25 and $Arme2Avion_Ailier !=26 and $Arme2Avion_Ailier !=27)
					{
						$Calibre2=round(GetData("Armes","ID",$Arme2Avion_Ailier,"Calibre"));
						if($Calibre2)
						{
							$Stock2='Stock_Munitions_'.$Calibre2;
							$Mun2_stock=0-($Mun2*$Formation);
							UpdateData("Unit",$Stock2,$Mun2_stock,"ID",$Unite);
							if($PlayerID ==1)$skills.="<br>Les stocks de ".$Calibre2."mm de l'unité ont été réduits de ".$Mun2_stock;
						}
					}
					if($Ailier_Bombe and $Ailier_Bombe_Nbr)
						UpdateData("Unit","Bombes_".$Ailier_Bombe,-$Ailier_Bombe_Nbr,"ID",$Unite);
				}
				//Stock Solo
				$Engine=GetData($Avion_db,"ID",$avion,"Engine");
				if($Engine)
				{
					$Carburant=GetData("Moteur","ID",$Engine,"Carburant");
					$Stock_Essence='Stock_Essence_'.$Carburant;	
					$Essence_stock=0-$Autonomie;
					UpdateData("Unit",$Stock_Essence,$Essence_stock,"ID",$Unite);
					//$skills.="Les stocks de ".$Carburant." Octane de l'unité ont été réduits de ".$Autonomie;
				}
				if($Avion_Bombe >0 and $Avion_Bombe < 4001 and $Avion_Bombe_nbr >0 and $Mission_Type <23 and $Type_avion !=6 and $Avion_Bombe !=25 and $Avion_Bombe !=26 and $Avion_Bombe !=27 and $Avion_Bombe !=350)
					UpdateData("Unit","Bombes_".$Avion_Bombe,-$Avion_Bombe_nbr,"ID",$Unite);
				if($Arme1Avion >0 and $Arme1Avion !=5 and $Arme1Avion !=25 and $Arme1Avion !=26 and $Arme1Avion !=27)
				{
					$Calibre1=round(GetData("Armes","ID",$Arme1Avion,"Calibre"));
					if($Calibre1)
					{
						$Stock1='Stock_Munitions_'.$Calibre1;
						$Mun1_stock=0-$Mun1;
						UpdateData("Unit",$Stock1,$Mun1_stock,"ID",$Unite);
						//$skills.="Les stocks de ".$Calibre1."mm de l'unité ont été réduits de ".$Mun1;
					}
				}
				if($Arme2Avion !=5 and $Arme2Avion !=0 and $Arme2Avion !=25 and $Arme2Avion !=26 and $Arme2Avion !=27)
				{
					$Calibre2=round(GetData("Armes","ID",$Arme2Avion,"Calibre"));
					if($Calibre2)
					{
						$Stock2='Stock_Munitions_'.$Calibre2;
						$Mun2_stock=0-$Mun2;
						UpdateData("Unit",$Stock2,$Mun2_stock,"ID",$Unite);
						//$skills.="Les stocks de ".$Calibre2."mm de l'unité ont été réduits de ".$Mun2;
					}
				}
			}
			//Remboursement mission front
			$Cr_Mission-=2;
			if($Type_Mission >97)
				MoveCredits($PlayerID,6,$Cr_Mission);
			elseif($Unite_Type !=3 and $Unite_Type !=6 and $Unite_Type !=8 and $Unite_Type !=9)
			{
				$Cr_Mission-=2;
				$Co_Heure_Mission=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Co_Heure_Mission");
				if($Missions_Max >2 and $Heure_Mission == $Heure and $Co_Heure_Mission == $Heure and $_SESSION['BH_Lieu'] >0 and $_SESSION['BH_Lieu'] ==$cible)
					MoveCredits($PlayerID,7,$Cr_Mission);
			}
		}
		$Puissance=GetPuissance($Avion_db,$avion,0,$HP,1,1,$Engine_Nbr); //GetData("Avion","ID",$avion,"Puissance");
		//$mes.='<p>Votre objectif se trouve à une distance de <b>'.$Distance.' km</b>. Vous grimpez à l\'altitude de croisière indiquée sur votre plan de vol</p>';
		$chemin=$Distance;
		//Altitude
		if($Mission_Type >97)
			$gaz_menu=ShowGaz($avion,$c_gaz,$flaps,$alt,4);
		else
		{
			$alt=mt_rand(0,1000)+GetData($Avion_db,"ID",$avion,"VitesseA")*5;
			if($alt >$Plafond)$alt=$Plafond;
			$gaz_menu=ShowGaz($avion,$c_gaz,$flaps,$alt,6);
		}
		$_SESSION['Decollage']=true;
		$_SESSION['Distance']=$Distance;
		$titre="décollage <a href='help/aide_takeoff.php' target='_blank' title='Aide décollage'><img src='images/help.png'></a>";
		$mes.='<form action=\'nav0.php\' method=\'post\'>
				<input type=\'hidden\' name=\'Chemin\' value='.$chemin.'>
				<input type=\'hidden\' name=\'Distance\' value='.$Distance.'>
				<input type=\'hidden\' name=\'Meteo\' value='.$Meteo.'>
				<input type=\'hidden\' name=\'Avion\' value='.$avion.'>
				<input type=\'hidden\' name=\'Mun1\' value='.$Mun1.'>
				<input type=\'hidden\' name=\'Mun2\' value='.$Mun2.'>
				<input type=\'hidden\' name=\'Puissance\' value='.$Puissance.'>
				<input type=\'hidden\' name=\'Base\' value='.$base.'>';
		if($cible_select_titre)$mes.='<table class=\'table\'><thead><tr><th>'.$cible_select_titre.'</th></tr></thead><tr><td>'.$cible_select_txt.'</td></tr>';
		$mes.='<tr>'.$gaz_menu.'</tr></table>';
		$mes.='<input type=\'Submit\' title=\'Mettez les gaz pour décoller!\' value=\'CONTINUER\' class=\'btn btn-default\' onclick=\'this.disabled=true;this.form.submit();\'> <a href=\'help/aide_takeoff.php\' target=\'_blank\' title=\'Aide décollage\'><img src=\'images/help.png\'></a></form>';
	}
	else
	{
		$_SESSION['Decollage']=true;
		$_SESSION['Distance']=0;
		$mes.="<p class='lead'>FIN DE MISSION</p>";
		$menu.="<form action='index.php?view=user' method='post'><input type='Submit' value='TERMINER LA MISSION' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
usleep(1);
include_once('./index.php');
?>