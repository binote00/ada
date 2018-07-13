<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Action=Insec($_POST['Action']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 and isset($Action))
{
	include_once('./jfv_avions.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Unit,Avancement,Reputation,Moral,Courage,Credits,Missions_Max,Equipage,MIA,Slot1,Slot2,Slot4,Slot7,Slot8,Slot10,Slot11 FROM Pilote WHERE ID='$PlayerID'")
	 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esc_at2-ply');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unite=$data['Unit'];
			$Avancement=$data['Avancement'];
			$Reputation=$data['Reputation'];
			$Moral=$data['Moral'];
			$Courage=$data['Courage'];
			$Credits=$data['Credits'];
			$Missions_Max=$data['Missions_Max'];
			$Equipage=$data['Equipage'];
			$MIA=$data['MIA'];
			$Slot1=$data['Slot1'];
			$Slot2=$data['Slot2'];
			$Slot4=$data['Slot4'];
			$Slot7=$data['Slot7'];
			$Slot8=$data['Slot8'];
			$Slot10=$data['Slot10'];
			$Slot11=$data['Slot11'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($Action <1 or ($Credits <1 and $Missions_Max >5))
	{
		$mes="<p>Vous ne savez pas lire?<br>Quand il n'y en a plus, il n'y en a plus!</p>";
		$menu="<a class='btn btn-default' title='Retour à l\'escadrille' href='index.php?view=escadrille'>Retour à l'escadrille</a>";
		$img="<img src='images/tsss.jpg'>";
		$Action=0;
	}
	else
	{
		if(!$MIA and $_SESSION['Distance'] ==0)
		{
			$CT8=Insec($_POST['CT8']);
			$CT4=Insec($_POST['CT4']);
			$CT2=Insec($_POST['CT2']);
			if($Equipage)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Trait,Mecanique FROM Equipage WHERE ID='$Equipage'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Trait_e=$data['Trait'];
						$Mecanique=$data['Mecanique'];
					}
					mysqli_free_result($result);
					unset($data);
				}
			}			
			$con=dbconnecti();
			$resultu=mysqli_query($con,"SELECT Nom,Base,Type,Commandant,Avion1,Avion2,Avion3,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'")
			 or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esc_at2-unit');
			mysqli_close($con);
			if($resultu)
			{
				while($data=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
				{
					$Unite_Nom=$data['Nom'];
					$Base=$data['Base'];
					$Unite_Type=$data['Type'];
					$Commandant=$data['Commandant'];
					$Avion1=$data['Avion1'];
					$Avion2=$data['Avion2'];
					$Avion3=$data['Avion3'];
					$Pers1=$data['Pers1'];
					$Pers2=$data['Pers2'];
					$Pers3=$data['Pers3'];
					$Pers4=$data['Pers4'];
					$Pers5=$data['Pers5'];
					$Pers6=$data['Pers6'];
					$Pers7=$data['Pers7'];
					$Pers8=$data['Pers8'];
					$Pers9=$data['Pers9'];
					$Pers10=$data['Pers10'];
				}
				mysqli_free_result($resultu);
			}
			$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
			$Personnel=array_count_values($Pers);	
			$Grade_a=GetAvancement($Avancement, $country);
			//$_SESSION['Avancement_mission']=$Grade_a[1];
			SetData("Pilote","S_Avancement_mission",$Grade_a[1],"ID",$PlayerID);
			unset($Grade_a);	
			$Credits=0;
			switch($Action)
			{
				case 1:
					$Credits=-$CT2;
					UpdateCarac($PlayerID,"Reputation",1);
					UpdateCarac($PlayerID,"Avancement",1);
					UpdateCarac($PlayerID,"Gestion",1);
					SetData("Unit","U_Graisse",1,"ID",$Unite);
					$mes="<p>Le mécanos graissent les armes des avions de l'unité.</p>";
					$img_txt='gestion_muns'.$country;
					break;
				case 2:
					$Credits=-$CT2;
					UpdateCarac($PlayerID,"Reputation",1);
					UpdateCarac($PlayerID,"Avancement",1);
					UpdateCarac($PlayerID,"Gestion",1);
					SetData("Unit","U_Graisse",0,"ID",$Unite);
					$mes="<p>Le mécanos dégraissent les armes des avions de l'unité.</p>";
					$img_txt='gestion_muns'.$country;
					break;
				case 3:
					$Credits=-$CT2;
					UpdateCarac($PlayerID,"Gestion",1);
					UpdateCarac($PlayerID,"Avancement",1);
					UpdateCarac($PlayerID,"Gestion",1);
					SetData("Unit","U_Purge",1,"ID",$Unite);
					$mes="<p>Le mécanos purgent les circuits hydrauliques des avions de l'unité.</p>";
					$img_txt='repare'.$country;
					break;
				case 4:
					$Credits=-$CT2;
					UpdateCarac($PlayerID,"Gestion",1);
					UpdateCarac($PlayerID,"Avancement",1);
					UpdateCarac($PlayerID,"Gestion",1);
					SetData("Unit","U_Chargeurs",2,"ID",$Unite);
					$mes="<p>Le mécanos approvisionnent les mitrailleurs des avions de l'unité.</p>";
					$img_txt='gestion_muns'.$country;
					break;
				case 5:
					$Credits=-$CT4;
					UpdateCarac($PlayerID,"Gestion",2);
					UpdateCarac($PlayerID,"Avancement",2);
					UpdateCarac($PlayerID,"Gestion",2);
					SetData("Unit","U_Moteurs",5,"ID",$Unite);
					$mes="<p>Le mécanos optimisent les moteurs des avions de l'unité.</p>";
					$img_txt='repare'.$country;
					break;
				case 6:
					$Credits=-$CT4;
					UpdateCarac($PlayerID,"Gestion",2);
					UpdateCarac($PlayerID,"Avancement",2);
					UpdateCarac($PlayerID,"Gestion",2);
					$Zone=GetData("Lieu","ID",$Base,"Zone");
					if($Zone == 1)
						$Camo=6;
					elseif($Zone == 6)
						$Camo=2;
					elseif($Zone == 7)
						$Camo=8;
					elseif($Zone == 8)
						$Camo=21;
					elseif($Zone == 5 or $Zone == 0 or $Zone == 9 or $Zone == 11)
						$Camo=11;
					elseif($Zone == 4 or $Zone == 3 or $Zone == 2)
						$Camo=14;
					SetData("Unit","U_Camo",$Camo,"ID",$Unite);
					$mes="<p>Le mécanos appliquent un camouflage temporaire sur les avions de l'unité.</p>";
					$img_txt='repare'.$country;
					break;
				case 7:
					$Credits=-$CT4;
					UpdateCarac($PlayerID,"Gestion",2);
					UpdateCarac($PlayerID,"Avancement",2);
					UpdateCarac($PlayerID,"Gestion",2);
					SetData("Unit","U_Blindage",2,"ID",$Unite);
					$mes="<p>Le mécanos installent un blindage rudimentaire dans le cockpit des avions de l'unité.</p>";
					$img_txt='repare'.$country;
					break;
				case 12:
					$Credits=-1;
					SetData("Pilote","S_Graisse",1,"ID",$PlayerID);
					$mes="<p>Votre mécano graisse les armes de votre avion.</p>";
					$img_txt='gestion_muns'.$country;
					break;
				case 13:
					$Credits=-1;
					SetData("Pilote","S_Graisse",0,"ID",$PlayerID);
					$mes="<p>Votre mécano dégraisse les armes de votre avion.</p>";
					$img_txt='gestion_muns'.$country;
					break;
				case 14:
					$Credits=-1;
					SetData("Pilote","S_Purge",1,"ID",$PlayerID);
					$mes="<p>Votre mécano purge les circuits hydrauliques de votre avion.</p>";
					$img_txt='repare'.$country;
					break;
				case 15:
					$Credits=-1;
					if($Trait_e ==5)
						$up=3;
					else
						$up=2;
					SetData("Pilote","S_Chargeurs",$up,"ID",$PlayerID);
					$mes="<p>Votre mécano approvisionne le(s) mitrailleur(s) de votre appareil.</p>";
					$img_txt='gestion_muns'.$country;
					break;
				case 16:
					$Credits=-$CT2;
					if($Trait_e ==5)
						$up=10;
					else
						$up=5;
					SetData("Pilote","S_Moteurs",$up,"ID",$PlayerID);
					$mes="<p>Votre mécano optimise le(s) moteur(s) de votre avion.</p>";
					$img_txt='repare'.$country;
					break;
				case 17:
					$Credits=-$CT2;
					$Zone=GetData("Lieu","ID",$Base,"Zone");
					if($Zone ==1)
						$Camo=6;
					elseif($Zone ==6)
						$Camo=2;
					elseif($Zone ==7)
						$Camo=8;
					elseif($Zone ==8)
						$Camo=21;
					elseif($Zone ==5 or $Zone ==0 or $Zone ==9 or $Zone ==11)
						$Camo=11;
					elseif($Zone ==4 or $Zone ==3 or $Zone ==2)
						$Camo=14;
					SetData("Pilote","S_Camo",$Camo,"ID",$PlayerID);
					$mes="<p>Votre mécano applique un camouflage temporaire sur votre avion.</p>";
					$img_txt='repare'.$country;
					break;
				case 18:
					$Credits=-$CT2;
					if($Trait_e ==5)
						$up=4;
					else
						$up=2;
					SetData("Pilote","S_Blindage",$up,"ID",$PlayerID);
					$mes="<p>Votre mécano installe un blindage rudimentaire dans le cockpit de votre avion.</p>";
					$img_txt='repare'.$country;
					break;
				case 8:
					$Credits=-1;
					$mes="<p>L'armurier vous explique en détails les possibilités d'armement des avions de l'unité.</p>";
					$garage="<table class='table'>";
					for($u=1;$u<4;$u++)
					{
						switch($u)
						{
							case 1:
								$ID_ref=$Avion1;
							break;
							case 2:
								$ID_ref=$Avion2;
							break;
							case 3:
								$ID_ref=$Avion3;
							break;
						}
						$con=dbconnecti();
						$resulta=mysqli_query($con,"SELECT Nom,ArmePrincipale,Arme1_Nbr,ArmeSecondaire,Arme2_Nbr,Bombe,Bombe_Nbr FROM Avion WHERE ID='$ID_ref'");
						if($resulta)
						{
							while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
							{
								$Avion_nom=$data['Nom'];
								$Arme1=$data['ArmePrincipale'];
								$Arme1_nbr=$data['Arme1_Nbr'];
								$Arme2=$data['ArmeSecondaire'];
								$Arme2_nbr=$data['Arme2_Nbr'];
								$Bombe=$data['Bombe'];
								$Bombe_Nbr=$data['Bombe_Nbr'];
							}
							mysqli_free_result($resulta);
						}											
						if($Arme1 >0 and $Arme1 !=5)
							$result3=mysqli_query($con,"SELECT Nom,Calibre,Munitions,Degats,Multi,Enrayage,Portee FROM Armes WHERE ID='$Arme1'");
						if($Arme2 >0 and $Arme2 !=5)
							$result2=mysqli_query($con,"SELECT Nom,Calibre,Munitions,Degats,Multi,Enrayage,Portee FROM Armes WHERE ID='$Arme2'");
						mysqli_close($con);
						if($result3)
						{
							while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
							{
								$Arme1_nom=$data['Nom'];
								$Arme1_cal=round($data['Calibre']);
								$Arme1_chargeur=$data['Munitions'];
								$Arme1_dg=$data['Degats'];
								$Arme1_cadence=$data['Multi']*60;
								$Arme1_enrayage=100-$data['Enrayage'];
								$Arme1_portee=$data['Portee'];
							}
							mysqli_free_result($result3);
						}	
						if($result2)
						{
							while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								$Arme2_nom=$data['Nom'];
								$Arme2_cal=round($data['Calibre']);
								$Arme2_chargeur=$data['Munitions'];
								$Arme2_dg=$data['Degats'];
								$Arme2_cadence=$data['Multi']*60;
								$Arme2_enrayage=100-$data['Enrayage'];
								$Arme2_portee=$data['Portee'];
							}
							mysqli_free_result($result2);
						}
						unset($data);
						if($Arme2 ==25 or $Arme2 ==26 or $Arme2 ==27)
							$Arme2_enrayage=99;						
						$garage.="<tr><th colspan='8'>".$Avion_nom."</th><tr>
								<tr><td>Arme Principale</td><th>".$Arme1_nom."</th><td>".$Arme1_cal."mm</td><td>".$Arme1_chargeur."</td><td>".$Arme1_dg."</td><td>".$Arme1_portee."m</td><td>".$Arme1_cadence." cp/min</td><td>".$Arme1_enrayage."%</td></tr>
								<tr><td>Arme Secondaire</td><th>".$Arme2_nom."</th><td>".$Arme2_cal."mm</td><td>".$Arme2_chargeur."</td><td>".$Arme2_dg."</td><td>".$Arme2_portee."m</td><td>".$Arme2_cadence." cp/min</td><td>".$Arme2_enrayage."%</td></tr>
								";
						if($Bombe_Nbr)
						{
							if($Bombe ==800)
							{
								$Bombe="533mm";
								$Arme_nom="Torpille";
								$Bombe_Portee="4km";
							}
							elseif($Bombe ==400)
							{
								$Bombe=$Bombe."kg";
								$Arme_nom="Mine";
								$Bombe_Portee="N/A";
							}
							else
							{
								$Bombe=$Bombe."kg";
								$Arme_nom="Bombe";
								$Bombe_Portee="N/A";
							}
							$Bombe_Dg=$Bombe*30;
							$garage.="<tr><td>Soute</td><th>".$Arme_nom."</th><td>".$Bombe."</td><td>".$Bombe_Nbr."</td><td>".$Bombe_Dg."</td><td>".$Bombe_Portee."</td><td>N/A</td><td>N/A</td></tr>";
						}
					}
					$garage.="<tr><thead><th colspan='2'>Arme</th><th>Calibre</th><th>Munitions</th><th>Dégats</th><th>Portée</th><th>Cadence</th><th>Fiabilité</th></thead><tr></table></div>";
					$menu=$garage;
					$img_txt="mecano";
					UpdateCarac($PlayerID,"Renseignement",1);				
				break;
				case 9:
					$Credits=-1;
					$Cons=false;
					$Sqn=GetSqn($country);
					$con=dbconnecti();
					$result1=mysqli_query($con,"SELECT Nom,Puissance,Autonomie,Engine,Engine_Nbr FROM Avion WHERE ID='$Avion1'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : escat2-pl1');
					$result2=mysqli_query($con,"SELECT Nom,Puissance,Autonomie,Engine,Engine_Nbr FROM Avion WHERE ID='$Avion2'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : escat2-pl2');
					$result3=mysqli_query($con,"SELECT Nom,Puissance,Autonomie,Engine,Engine_Nbr FROM Avion WHERE ID='$Avion3'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : escat2-pl3');
					mysqli_close($con);
					if($result3)
					{
						while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
						{
							$Nom3=$data3['Nom'];
							$Autonomie3=$data3['Autonomie'];
							$Engine3=$data3['Engine'];
							$Puissance3=$data3['Puissance']/$data3['Engine_Nbr'];
						}
						mysqli_free_result($result3);
					}
					if($result2)
					{
						while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							$Nom2=$data2['Nom'];
							$Autonomie2=$data2['Autonomie'];
							$Engine2=$data2['Engine'];
							$Puissance2=$data2['Puissance']/$data2['Engine_Nbr'];
						}
						mysqli_free_result($result2);
					}
					if($result1)
					{
						while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
						{
							$Nom1=$data1['Nom'];
							$Autonomie1=$data1['Autonomie'];
							$Engine1=$data1['Engine'];
							$Puissance1=$data1['Puissance']/$data1['Engine_Nbr'];
						}
						mysqli_free_result($result1);
					}
					$con=dbconnecti(1);
					$result1=mysqli_query($con,"SELECT Nom,Fiabilite,Compresseur,Injection,Carburant FROM Moteur WHERE ID='$Engine1'");
					$result2=mysqli_query($con,"SELECT Nom,Fiabilite,Compresseur,Injection,Carburant FROM Moteur WHERE ID='$Engine2'");
					$result3=mysqli_query($con,"SELECT Nom,Fiabilite,Compresseur,Injection,Carburant FROM Moteur WHERE ID='$Engine3'");
					mysqli_close($con);
					if($result3)
					{
						while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
						{
							$Engine_Nom3=$data3['Nom'];
							$Fiabilite3=$data3['Fiabilite'];
							$Compresseur3=Get_Compresseur($data3['Compresseur']);
							$Injection3=Get_Injection($data3['Injection']);
							$Carburant3=$data3['Carburant'];
						}
						mysqli_free_result($result3);
					}
					if($result2)
					{
						while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
						{
							$Engine_Nom2=$data2['Nom'];
							$Fiabilite2=$data2['Fiabilite'];
							$Compresseur2=Get_Compresseur($data2['Compresseur']);
							$Injection2=Get_Injection($data2['Injection']);
							$Carburant2=$data2['Carburant'];
						}
						mysqli_free_result($result2);
					}
					if($result1)
					{
						while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
						{
							$Engine_Nom1=$data1['Nom'];
							$Fiabilite1=$data1['Fiabilite'];
							$Compresseur1=Get_Compresseur($data1['Compresseur']);
							$Injection1=Get_Injection($data1['Injection']);
							$Carburant1=$data1['Carburant'];
						}
						mysqli_free_result($result1);
					}
					$rand_info=mt_rand(1,16);
					switch($rand_info)
					{
						case 1:
							$Data_Avion="ManoeuvreB";
							$Txt_comp=" vire plus court à basse altitude";
						break;
						case 2:
							$Data_Avion="ManoeuvreH";
							$Txt_comp=" vire plus court à basse altitude";
						break;
						case 3:
							$Data_Avion="Maniabilite";
							$Txt_comp=" possède un meilleur taux de roulis";
						break;
						case 4:
							$Data_Avion="Stabilite";
							$Txt_comp=" est plus stable";
						break;
						case 5:
							$Data_Avion="VitesseB";
							$Txt_comp=" est plus rapide à basse altitude";
						break;
						case 6:
							$Data_Avion="VitesseH";
							$Txt_comp=" est plus rapide à haute altitude";
						break;
						case 7:
							$Data_Avion="Plafond";
							$Txt_comp=" peut voler plus haut";
						break;
						case 8:
							$Data_Avion="ArmePrincipale";
							$Txt_comp="<p>Votre mécano attire votre attention sur le fait que vos armes s'enrayent plus facilement avec l'altitude</p>";
							$Cons=true;
						break;
						case 9:
							$Data_Avion="ArmePrincipale";
							$Txt_comp="<p>Votre mécano attire votre attention sur le fait que les manoeuvres de combat consomment beaucoup de carburant</p>";
							$Cons=true;
						break;
						case 10:
							$Data_Avion="ArmePrincipale";
							$Txt_comp="<p>Votre mécano attire votre attention sur le fait que la chaleur favorise les pannes moteur</p>";
							$Cons=true;
						break;
						case 11:
							$Data_Avion="ArmePrincipale";
							$Txt_comp="<p>Votre mécano attire votre attention sur le fait que les moteurs à injection directe possèdent un avantage lors des piqués</p>";
							$Cons=true;
						break;
						case 12:
							$Data_Avion="ArmePrincipale";
							$Txt_comp="<p>Votre mécano attire votre attention sur le fait que les moteurs en ligne refroidis par liquide sont plus fragiles</p>";
							$Cons=true;
						break;
						case 13:
							$Data_Avion="ArmePrincipale";
							$Txt_comp="<p>Votre mécano attire votre attention sur le fait que les moteurs en étoile refroidis par air sont plus résistants</p>";
							$Cons=true;
						break;
						case 14:
							$Data_Avion="ArmePrincipale";
							$Txt_comp="<p>Votre mécano attire votre attention sur le fait qu'un régime moteur trop élevé favorise les pannes moteur</p>";
							$Cons=true;
						break;
						case 15:
							$Data_Avion="Visibilite";
							$Txt_comp=" représente une cible bien plus grande";
						break;
						case 16:
							$Data_Avion="Moteur";
							$Txt_comp="<p>Votre mécano attire votre attention sur le fait qu'un moteur en ligne à refroidissement par liquide résiste mal au froid</p>";
							$Cons=true;
						break;
					}
					if($Cons)
						$mes=$Txt_comp;
					else
					{
						$IDAvion=mt_rand(1,3);
						$Avionx="Avion".$IDAvion;
						$Nomx="Nom".$IDAvion;
						$Avion_nom=$$Nomx;
						$Data=GetData("Avion","ID",$$Avionx,$Data_Avion);
						$con=dbconnecti();
						$ok=mysqli_query($con,"SELECT DISTINCT ID,Nom,".$Data_Avion." FROM Avion WHERE Pays='$country' AND Etat=1 ORDER BY RAND() LIMIT 1");
						mysqli_close($con);
						if($ok)
						{
							while($data=mysqli_fetch_array($ok)) 
							{
								$ID_comp=$data['ID'];
								$Nom_comp=$data['Nom'];
								$Data_comp=$data[2];
							}
							mysqli_free_result($ok);
						}
						if($Data >=$Data_comp)
							$mes.='<p>Votre mécano vous glisse à l\'oreille que le '.$Avion_nom.$Txt_comp.' que le '.$Nom_comp.'</p>';
						else
							$mes.='<p>Votre mécano vous glisse à l\'oreille que le '.$Nom_comp.$Txt_comp.' que le '.$Avion_nom.'</p>';
					}
					$mes.="<p>Votre mécano vous montre ensuite les moteurs équipant les avions de l'escadrille.</p>";
					$menu="<table class='table'>
								<tr><thead><th></th><th>".$Sqn." 1</th><th>".$Sqn." 2</th><th>".$Sqn." 3</th></thead></tr>
								<tr bgcolor='lightyellow'><td></td><td>".$Engine_Nom1."</td><td>".$Engine_Nom2."</td><td>".$Engine_Nom3."</td></tr>
								<tr><td>Carburant</td><td>".$Carburant1." Octane</td><td>".$Carburant2." Octane</td><td>".$Carburant3." Octane</td></tr>
								<tr><td>Système d'alimentation</td><td>".$Injection1."</td><td>".$Injection2."</td><td>".$Injection3."</td></tr>
								<tr><td>Compresseur</td><td>".$Compresseur1."</td><td>".$Compresseur2."</td><td>".$Compresseur3."</td></tr>
								<tr><td>Puissance unitaire</td><td>".$Puissance1."cv</td><td>".$Puissance2."cv</td><td>".$Puissance3."cv</td></tr>
								<tr><td>Fiabilité</td><td>".$Fiabilite1."%</td><td>".$Fiabilite2."%</td><td>".$Fiabilite3."%</td></tr>
								<tr><td colspan='4'><hr><td></tr>
								<tr><td>Autonomie</td><td>".$Autonomie1."km</td><td>".$Autonomie2."km</td><td>".$Autonomie3."km</td></tr>
							</table>";
					$img_txt='moteur'.$country;
					UpdateCarac($PlayerID,"Renseignement",1);	
				break;
				case 11:
					$Credits=-1;
					$Sqn=GetSqn($country);
					$mes="<p>Vous examinez chaque avion en détail.</p>";
					for($u=1;$u<4;$u++)
					{
						switch($u)
						{
							case 1:
								$ID_ref=$Avion1;
							break;
							case 2:
								$ID_ref=$Avion2;
							break;
							case 3:
								$ID_ref=$Avion3;
							break;
						}
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT Nom,ArmePrincipale,Arme1_Nbr,ArmeSecondaire,Arme2_Nbr,Autonomie,Reservoir FROM Avion WHERE ID='$ID_ref'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Avion_nom=$data['Nom'];
								$Arme1=$data['ArmePrincipale'];
								$Arme1_nbr=$data['Arme1_Nbr'];
								$Arme2=$data['ArmeSecondaire'];
								$Arme2_nbr=$data['Arme2_Nbr'];
								$Autonomie=$data['Autonomie'];
								$Reservoir=$data['Reservoir'];
							}
							mysqli_free_result($result);
						}						
						if($Reservoir)
							$Reservoir="(auto-obturant)";
						else
							$Reservoir="";
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
						$Bombe1000_nbr=$Array_Mod[32];
						$Bombe2000_nbr=$Array_Mod[33];
						$Rockets=$Array_Mod[35];												
						$Arme1_cal=round(GetData("Armes", "ID", $Arme1, "Calibre"));
						$Arme1_chargeur=GetData("Armes","ID",$Arme1,"Munitions");
						$Arme1_nom=GetData("Armes","ID",$Arme1,"Nom");
						$Arme2_cal=round(GetData("Armes", "ID", $Arme2, "Calibre"));
						$Arme2_chargeur=GetData("Armes","ID",$Arme2,"Munitions");
						$Arme2_nom=GetData("Armes","ID",$Arme2,"Nom");						
						$Arme8_fus_masse=GetData("Armes","ID",$Arme8_fus,"Masse");
						$Arme8_fus_nom=GetData("Armes","ID",$Arme8_fus,"Nom");
						$Arme8_fus_cal=round(GetData("Armes", "ID", $Arme8_fus, "Calibre"));
						$Arme8_fus_chargeur=GetData("Armes","ID",$Arme8_fus,"Munitions");
						$Arme8_ailes_masse=GetData("Armes","ID",$Arme8_ailes,"Masse");
						$Arme8_ailes_nom=GetData("Armes","ID",$Arme8_ailes,"Nom");
						$Arme8_ailes_cal=round(GetData("Armes", "ID", $Arme8_ailes, "Calibre"));
						$Arme8_ailes_chargeur=GetData("Armes","ID",$Arme8_ailes,"Munitions");
						if($Arme13 != 5)
						{
							$Arme13_masse=GetData("Armes","ID",$Arme13,"Masse");
							$Arme13_cal=round(GetData("Armes", "ID", $Arme13, "Calibre"));
							$Arme13_chargeur=GetData("Armes","ID",$Arme13,"Munitions");
						}
						if($Arme20 != 5)
						{
							$Arme20_masse=GetData("Armes","ID",$Arme20,"Masse");
							$Arme20_cal=round(GetData("Armes", "ID", $Arme20, "Calibre"));
							$Arme20_chargeur=GetData("Armes","ID",$Arme20,"Munitions");
						}
						if($Camera_high !=5)
							$Camera_high_masse=GetData("Armes","ID",$Camera_high,"Masse");
						$Arme13_nom=GetData("Armes","ID",$Arme13,"Nom");
						$Arme20_nom=GetData("Armes","ID",$Arme20,"Nom");
						$garage=$garage."<table class='table'>
								<tr><thead><th colspan='6'>".$Sqn." ".$u."</th></thead><tr>
								<tr bgcolor='lightyellow'><th colspan='6'>".$Avion_nom."</th><tr>
								<tr><td colspan='2'>Réservoir principal ".$Autonomie." litres ".$Reservoir."</td></tr>
								<tr class='TitreBleu_bc'>
									<th>Arme Principale (choix)</th>
									<th>Arme Secondaire (choix)</th>
								</tr>";
						if($Arme8_fus_nbr >0)
						{
							$garage.='<tr><td>'.$Arme8_fus_nbr.' '.$Arme8_fus_nom.' ('.$Arme8_fus_cal.'mm / '.$Arme8_fus_chargeur.' coups)</td>';
						}
						if($Arme8_ailes_nbr >0)
						{
							$garage.='<td>'.$Arme8_ailes_nbr.' '.$Arme8_ailes_nom.' ('.$Arme8_ailes_cal.'mm / '.$Arme8_ailes_chargeur.' coups)</td></tr>';
						}
						if($Arme13_fus_nbr >0)
						{
							$garage.='<tr><td>'.$Arme13_fus_nbr.' '.$Arme13_nom.' ('.$Arme13_cal.'mm / '.$Arme13_chargeur.' coups)</td>';
						}
						if($Arme8_ailes_max > 3)
						{
							$Arme8_ailes_nbr=$Arme8_ailes_nbr*2;
							$garage.='<td>'.$Arme8_ailes_nbr.' '.$Arme8_ailes_nom.' ('.$Arme8_ailes_cal.'mm / '.$Arme8_ailes_chargeur.' coups)</td></tr>';
						}
						if($Arme20_fus_nbr >0)
						{
							$garage.='<tr><td>'.$Arme20_fus_nbr.' '.$Arme20_nom.' ('.$Arme20_cal.'mm / '.$Arme20_chargeur.' coups)</td>';
						}
						if($Arme8_ailes_max > 5)
						{
							$garage.='<td>'.$Arme8_ailes_max.' '.$Arme8_ailes_nom.' ('.$Arme8_ailes_cal.'mm / '.$Arme8_ailes_chargeur.' coups)</td></tr>';
						}
						if($Camera_low != 5)
						{
							$garage.="<tr><td>1 Caméra portative (Basse altitude uniquement)</td>";
						}
						if($Arme13_ailes_max >0)
						{
							$garage.='<td>'.$Arme13_ailes_nbr.' '.$Arme13_nom.' ('.$Arme13_cal.'mm / '.$Arme13_chargeur.' coups)</td></tr>';
						}
						if($Camera_high != 5)
						{
							$garage.='<tr><td>1 Caméra fixe ('.$Camera_high_masse.'kg)</td>';
						}
						if($Arme13_ailes_max > 3)
						{
							$garage.='<td>'.$Arme13_ailes_max.' '.$Arme13_nom.' ('.$Arme13_cal.'mm / '.$Arme13_chargeur.' coups)</td></tr>';			
						}
						if($Arme20_ailes_nbr >0)
						{
							$garage.='<td>'.$Arme20_ailes_nbr.' '.$Arme20_nom.' ('.$Arme20_cal.'mm / '.$Arme20_chargeur.' coups)</td></tr>';
						}
						if($Baby)
						{
							$garage.="<tr><td bgcolor='tan' colspan='2'>Réservoir largable</td></tr>";
							$garage.="<tr><td colspan='2'>".$Baby." litres</td></tr>";
						}
						$garage.="<tr><td bgcolor='tan' colspan='2'>Options de Bombes ou Charges supplémentaires</td></tr>";
						if($Bombe50_nbr >0)
						{
							$garage.="<tr><td colspan='2'>".$Bombe50_nbr." bombes de 50kg</td></tr>";
						}
						if($Bombe125_nbr >0)
						{
							$garage.="<tr><td colspan='2'>".$Bombe125_nbr." bombes de 125kg</td></tr>";
						}
						if($Bombe250_nbr >0)
						{
							$garage.="<tr><td colspan='2'>".$Bombe250_nbr." bombes de 250kg</td></tr>";
						}
						if($Bombe500_nbr >0)
						{
							$garage.="<tr><td colspan='2'>".$Bombe500_nbr." bombes de 500kg</td></tr>";
						}
						if($Bombe1000_nbr >0)
						{
							$garage.="<tr><td colspan='2'>".$Bombe1000_nbr." bombes de 1000kg</td></tr>";
						}
						if($Bombe2000_nbr >0)
						{
							$garage.="<tr><td colspan='2'>".$Bombe2000_nbr." bombes de 2000kg</td></tr>";
						}
						if($Torpilles >0)
						{
							$garage.="<tr><td colspan='2'>".$Torpilles." torpilles</td></tr>";
						}
						if($Mines >0)
						{
							$garage.="<tr><td colspan='2'>".$Mines." charges</td></tr><tr><td colspan='2'>".$Mines." mines</td></tr>";
						}
						if($Rockets >0)
						{
							$garage.="<tr><td colspan='2'>".$Rockets." rockets</td></tr>";
						}
						if($Camera_low != 5)
						{
							$garage.="<tr><td colspan='2'>1 Caméra portative (Basse altitude uniquement)</td></tr>";
						}
						if($Camera_high != 5)
						{
							$garage.="<tr><td colspan='2'>1 Caméra fixe (".$Camera_high_masse."kg)</td></tr>";
						}
						if($Radar_On)
						{
							$garage.="<tr><td colspan='2'>1 Radar embarqué</td></tr>";
						}
						$garage.='</td></tr></table>';
					}
					$menu=$garage;
					$img_txt='/avions/garage'.$Avion1;
					UpdateCarac($PlayerID,"Renseignement",1);				
				break;
				case 41:
					$Avion=$Avion1;
					if($Avion1)
					{
						$Reserve=GetData("Avion","ID",$Avion,"Reserve");				
						$Stock=floor(GetData("Avion","ID",$Avion,"Stock"));				
						$con=dbconnecti();
						$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Avion' AND PVP=1"),0);
						$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Avion'"),0);
						$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$Avion' AND Etat=1"),0);
						$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$Avion' AND Etat=1"),0);
						$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$Avion' AND Etat=1"),0);
						mysqli_close($con);
						$con=dbconnecti(4);
						$Perdu=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$Avion' AND Avion_Nbr >0"),0);
						mysqli_close($con);
						$Total=$DCA+$Abattu+$Perdu;
						$Service=$Service1+$Service2+$Service3;
						$Reste=$Stock-$Total-$Service+$Reserve;
						if($Reste+$Service >$Stock)$Reste=$Stock-$Service;
						if($Reste <1)$Reste=0;				
						if($Total >$Stock)
							$Repa=0;
						else
							$Repa=$Stock-$DCA-$Perdu-$Service-$Reste;
					}
					else
						$Repa=0;
					if($Repa >0)
					{
						$Equipage=GetData("Pilote","ID",$PlayerID,"Equipage");
						UpdateCarac($Equipage,"Mecanique",5,"Equipage");
						UpdateData("Avion","Reserve",1,"ID",$Avion);
						$Credits=-$CT8;
						//UpdateCarac($PlayerID,"Missions_Max",1);
						UpdateCarac($PlayerID,"Reputation",10);
						UpdateCarac($PlayerID,"Avancement",10);
						$mes="Votre mécano se met directement au travail.";
						$img_txt="repare".$country;
					}
					else
					{
						$mes="Votre mécano vous signale que la réparation est impossible.";
						$img_txt='transfer_no'.$country;
					}
				break;
				case 42:
					$Avion=$Avion2;
					if($Avion2)
					{
						$Reserve=GetData("Avion","ID",$Avion,"Reserve");		
						$Production=floor(GetData("Avion","ID",$Avion,"Stock"));				
						$con=dbconnecti();
						$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Avion' AND PVP=1"),0);
						$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Avion'"),0);
						$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$Avion' AND Etat=1"),0);
						$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$Avion' AND Etat=1"),0);
						$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$Avion' AND Etat=1"),0);
						mysqli_close($con);
						$con=dbconnecti(4);
						$Perdu=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$Avion' AND Avion_Nbr >0"),0);
						mysqli_close($con);
						$Total=$DCA + $Abattu + $Perdu;
						$Service=$Service1 + $Service2 + $Service3;
						$Reste=$Production - $Total - $Service + $Reserve;
						if($Reste+$Service >$Production)$Reste=$Production-$Service;
						if($Reste <1)$Reste=0;				
						if($Total >$Production)
							$Repa=0;
						else
							$Repa=$Production-$DCA-$Perdu-$Service-$Reste;
					}
					else
						$Repa=0;
					if($Repa >0)
					{
						$Equipage=GetData("Pilote","ID",$PlayerID,"Equipage");
						UpdateCarac($Equipage,"Mecanique",5,"Equipage");
						UpdateData("Avion","Reserve",1,"ID",$Avion);
						$Credits=-$CT8;
						//UpdateCarac($PlayerID,"Missions_Max",1);
						UpdateCarac($PlayerID,"Reputation",10);
						UpdateCarac($PlayerID,"Avancement",10);
						$mes="Votre mécano se met directement au travail.";
						$img_txt="repare".$country;
					}
					else
					{
						$mes="Votre mécano vous signale que la réparation est impossible.";
						$img_txt='transfer_no'.$country;
					}
					break;
				case 43:
					$Avion=$Avion3;
					if($Avion3)
					{
						$Reserve=GetData("Avion","ID",$Avion,"Reserve");				
						$Production=floor(GetData("Avion","ID",$Avion,"Stock"));				
						$con=dbconnecti();
						$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Avion' AND PVP=1"),0);
						$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Avion'"),0);
						$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$Avion' AND Etat=1"),0);
						$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$Avion' AND Etat=1"),0);
						$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$Avion' AND Etat=1"),0);
						mysqli_close($con);
						$con=dbconnecti(4);
						$Perdu=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$Avion' AND Avion_Nbr >0"),0);
						mysqli_close($con);
						$Total=$DCA + $Abattu + $Perdu;
						$Service=$Service1 + $Service2 + $Service3;
						$Reste=$Production - $Total - $Service + $Reserve;
						if($Reste <1)$Reste=0;				
						if($Total >$Production)
							$Repa=0;
						else
							$Repa=$Production-$DCA-$Perdu-$Service-$Reste;
					}
					else
						$Repa=0;
					if($Repa >0)
					{
						$Equipage=GetData("Pilote","ID",$PlayerID,"Equipage");
						UpdateCarac($Equipage,"Mecanique",5,"Equipage");
						UpdateData("Avion","Reserve",1,"ID",$Avion);
						$Credits=-$CT8;
						//UpdateCarac($PlayerID,"Missions_Max",1);
						UpdateCarac($PlayerID,"Reputation",10);
						UpdateCarac($PlayerID,"Avancement",10);
						$mes="Votre mécano se met directement au travail.";
						$img_txt="repare".$country;
					}
					else
					{
						$mes="Votre mécano vous signale que la réparation est impossible.";
						$img_txt='transfer_no'.$country;
					}
					break;
				default:
					$mes="<p>Vous ne savez pas lire?</p>";
					$menu="<a class='btn btn-default' title='Retour à l\'escadrille' href='index.php?view=escadrille'>Retour à l'escadrille</a>";
					$img="<img src='images/tsss.jpg'>";
				break;
			}
			if($Action)
			{
				if($Credits)$credits_txt=MoveCredits($PlayerID,2,$Credits);
				$skills=$msg.'<br>'.$credits_txt;
				$titre="Atelier";
				$img="<img src='images/".$img_txt.".jpg'>";
				$menu.="<br><form action='promotion.php' method='post'><input type='hidden' name='Blesse' value='-1'><input type='Submit' class='btn btn-default' value='Retour'></form>";
				//$menu.="<a title='Retour à l\'escadrille' href='index.php?view=escadrille'>Retour à l'escadrille</a>";
				if(!$mes)$mes="Vous avez le sentiment du devoir accompli.";
			}
		}
		else
		{
			$mes="<h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
			$img="<img src='images/unites".$country.".jpg'>";
		}
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>