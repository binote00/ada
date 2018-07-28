<?php
require_once('./jfv_inc_sessions.php');
$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$Action=Insec($_POST['Action']);
	$Move_Type=Insec($_POST['Move_Type']);
	$Cible=strstr($Action,"_",true);
	$CT_move=substr($Action,strpos($Action,"_")+1);
	$Credits_Ori=GetData("Officier","ID",$OfficierID,"Credits");	
	if($Cible >0 and $CT_move >0 and $Credits_Ori >=$CT_move)
	{	
		$Veh_Nbr_sum=1;
		if($Move_Type ==6)
		{
			$Placement=3;
			$mobile=4;
			$img="train_transport";
		}
		elseif($Move_Type ==124)
		{
			$Placement=5;
			$mobile=8;
			$img="peniche_transport";
		}
		elseif($Move_Type ==106)
		{
			$Placement=8;
			$mobile=5;
			$Veh_Nbr_sum=0;
			$img="/lieu/objectif10";
			$Base=GetData("Regiment","Officier_ID",$OfficierID,"Lieu_ID");
			$Transit=GetData("Officier","ID",$OfficierID,"Transit");
			$con=dbconnecti();
			$result1=mysqli_query($con,"SELECT Latitude,Longitude FROM Lieu WHERE ID='$Base'");
			$result2=mysqli_query($con,"SELECT Latitude,Longitude,Meteo FROM Lieu WHERE ID='$Cible'");
			//$Embout2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment WHERE Lieu_ID='$Cible' AND Vehicule_Nbr>0 AND Placement=8 AND Position<>25"),0);
			$Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Cible' AND Vehicule_Nbr>0 AND Placement=8 AND Position<>25"),0);
			mysqli_close($con);
			if($result1)
			{
				while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
				{
					$Latitude_base=$data1['Latitude'];
					$Longitude_base=$data1['Longitude'];
				}
				mysqli_free_result($result1);
			}
			if($result2)
			{
				while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Latitude=$data2['Latitude'];
					$Longitude=$data2['Longitude'];
					$Meteo=$data2['Meteo'];
				}
				mysqli_free_result($result2);
			}
			//$Embout+=$Embout2+4;
			if(($Longitude >65 and $Embout >30) or ($Longitude <66 and $Embout >20))
			{
				$mes="Vous ne pouvez rejoindre votre destination du à un trop grand nombre de navires sur la zone d'arrivée!<br>";
				$CT_move=$CT_MAX;
			}
			else
			{
				$mes='<p>Votre convoi arrive à destination!</p>';	
				$Distance=GetDistance(0,0,$Longitude_base,$Latitude_base,$Longitude,$Latitude);
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Stock_Essence_1,r.Stock_Essence_87,r.Fret,r.Fret_Qty,c.Conso,c.Carbu_ID,c.Fuel,c.Type FROM Regiment as r,Cible as c 
				WHERE r.Vehicule_ID=c.ID AND r.Officier_ID='$OfficierID'");
				//mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Vehicule=$data['Vehicule_ID'];
						$Veh_Nbr=$data['Vehicule_Nbr'];
						$Stock1=$data['Stock_Essence_1'];
						$Stock87=$data['Stock_Essence_87'];
						$Fret=$data['Fret'];
						$Fret_Qty=$data['Fret_Qty'];						
						$Veh_Carbu=$data['Carbu_ID'];
						$Conso=$data['Conso'];
						$Autonomie=$data['Fuel'];
						$Type_navire=$data['Type'];
						if($Vehicule !=4000)
						{
							$Conso_base=ceil(($Conso-$Meteo)*$Distance[0]/$Autonomie);
							if($Veh_Carbu ==87)
							{
								$Jauge=$Stock87;
								$Stock="Stock_Essence_87";
								$Conso=$Veh_Nbr*$Conso_base;
							}
							elseif($Veh_Carbu ==1)
							{
								$Jauge=$Stock1;
								$Stock="Stock_Essence_1";
								$Conso=$Veh_Nbr*$Conso_base;
							}
							if($Jauge >=$Conso)
							{
								if($OfficierID !=264 and $OfficierID !=265)
									UpdateData("Regiment",$Stock,-$Conso,"ID",$data['ID']);
								$Veh_Nbr_sum+=$Veh_Nbr;
								if($Type_navire ==21) //Porte-avions
								{
									//$con=dbconnecti();
									$reset2=mysqli_query($con,"UPDATE Pilote_IA as p,Unit as u SET p.Cible=0,p.Couverture=0,p.Couverture_Nuit=0,p.Escorte=0,p.Avion=0,p.Alt=0,p.Task=0,u.Couverture=0,u.Escorte=0,u.Base='$Lieu' 
									WHERE p.Unit=u.ID AND u.Porte_avions='$Vehicule'");
									//mysqli_close($con);
								}
								if($Transit >0)
								{
									//$con=dbconnecti();
									$reset2=mysqli_query($con,"UPDATE Regiment SET Lieu_ID='$Cible',Camouflage=1,Position=11,Placement=9,Visible=0 WHERE Officier_ID='$Transit'");
									//mysqli_close($con);
								}
								elseif($Fret ==200 and $Fret_Qty)
								{
									//$con=dbconnecti();
									$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Lieu_ID='$Cible',Camouflage=1,Position=11,Placement=9,Visible=0 WHERE ID='$Fret_Qty'");
									//mysqli_close($con);
								}
							}
							else
							{
								//$mes.="<br>[DEBUG] Consommation de la ".$data['ID']."e Cie : ".$Conso." sur son stock de ".$Jauge;
								$Diff=round(($Conso-$Jauge)/$Conso_base);
								SetData("Regiment",$Stock,0,"ID",$data['ID']);
								$Charisme=0;
								if($Trait_e ==6)$Charisme=mt_rand(0,1);
								if($Diff >0 and !$Charisme)
								{
									UpdateData("Regiment","Vehicule_Nbr",-$Diff,"ID",$data['ID']);
									UpdateData("Regiment","Moral",-$Diff,"ID",$data['ID']);
									AddEventGround(410,$Vehicule,$OfficierID,$data['ID'],$data['Lieu_ID'],$Diff);
									$Veh_Nbr_sum+=$Veh_Nbr-$Diff;
									$mes.="<br>Par manque de carburant, une partie des navires de la ".$data['ID']."e Flotille ne peut plus suivre le convoi!";
								}
							}
						}
					}			
				}
				mysqli_close($con);
			}
		}
		else
		{
			$img="move_front".$country;
			$Veh_Nbr_sum=0;
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Stock_Essence_1,r.Stock_Essence_87,r.Moral,r.Lieu_ID,r.Placement,c.Conso,c.Carbu_ID,c.Fuel,c.mobile FROM Regiment as r,Cible as c 
			WHERE r.Vehicule_ID=c.ID AND r.Officier_ID='$OfficierID'");
			//mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Lieu=$data['Lieu_ID'];
					$Vehicule=$data['Vehicule_ID'];
					$Veh_Nbr=$data['Vehicule_Nbr'];					
					$Stock87=$data['Stock_Essence_87'];
					$Stock1=$data['Stock_Essence_1'];
					$Moral=$data['Moral'];
					$Placement=$data['Placement'];
					$Veh_Carbu=$data['Carbu_ID'];
					$Conso=$data['Conso'];
					$Autonomie=$data['Fuel'];
					$mobile=$data['mobile'];
					if($Vehicule !=4000)
					{
						$Zone=mysqli_result(mysqli_query($con,"SELECT Zone FROM Lieu WHERE ID='$Lieu'"),0);
						if($mobile !=3)$furtif_echec=true;
						$Conso_base=ceil(Get_LandConso($Zone,$Conso)*$Distance/$Autonomie);
						if($Conso_base <2)$Conso_base=2;
						if($Veh_Carbu ==87)
						{
							$Jauge=$Stock87;
							$Stock="Stock_Essence_87";
							$Conso=$Veh_Nbr*$Conso_base;
						}
						elseif($Veh_Carbu ==1)
						{
							$Jauge=$Stock1;
							$Stock="Stock_Essence_1";
							$Conso=$Veh_Nbr*$Conso_base;
						}
						else
						{
							$Jauge=$Moral;
							$Stock="Moral";
							$Conso=10;
							$Conso_base=10;
						}
						if($Jauge >=$Conso)
						{
							UpdateData("Regiment",$Stock,-$Conso,"ID",$data['ID']);
							$Veh_Nbr_sum+=$Veh_Nbr;
						}
						else
						{
							//$mes.="<br>[DEBUG] Consommation de la ".$data['ID']."e Cie : ".$Conso." sur son stock de ".$Jauge;
							$Diff=round(($Conso-$Jauge)/$Conso_base);
							SetData("Regiment",$Stock,0,"ID",$data['ID']);
							$Charisme=0;
							if($Trait_e ==6)$Charisme=mt_rand(0,1);
							if($Diff >0 and !$Charisme)
							{
								UpdateData("Regiment","Vehicule_Nbr",-$Diff,"ID",$data['ID']);
								UpdateData("Regiment","Moral",-$Diff,"ID",$data['ID']);
								AddEventGround(410,$Vehicule,$OfficierID,$data['ID'],$data['Lieu_ID'],$Diff);
								$Veh_Nbr_sum+=$Veh_Nbr-$Diff;
								$mes.="<br>Une partie des troupes de la ".$data['ID']."e Cie déserte!";
							}
						}
					}
				}				
			}
			mysqli_close($con);
		}
		if($Veh_Nbr_sum >0 or $Vehicule ==4000)
		{
			if($mobile ==4)
				$Placement=3;
			elseif($mobile ==8)
				$Placement=5;
			elseif($mobile ==5)
			{
				$Placement=8;
				if($Type_navire !=37)
				{
					$con=dbconnecti();
					$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
					$result1=mysqli_query($con,"SELECT Zone,Garnison,Port_Ori,ValeurStrat,Meteo,Flag FROM Lieu WHERE ID='$Cible'");
					mysqli_close($con);
					if($result1)
					{
						while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
						{
							$Zone=$data1['Zone'];
							$Garnison=$data1['Garnison'];
							$Port_Ori=$data1['Port_Ori'];
							$ValeurStrat=$data1['ValeurStrat'];
							$Meteo=$data1['Meteo'];
							$Flag=$data1['Flag'];
						}
						mysqli_free_result($result1);
					}
					if($Zone !=6 and ($Port_Ori or $Garnison >0))
					{
						$Faction_Cible=GetData("Pays","ID",$Flag,"Faction");
						if($Faction !=$Faction_Cible)
							AddEventFeed(201,$country,$OfficierID,$Placement,$Cible);
					}
					else
					{
						$con=dbconnecti();
						$Detection=mysqli_result(mysqli_query($con,"SELECT MAX(j.Vue) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Cible='$Cible' AND j.Task=5 AND j.Avion>0 AND p.Faction<>'$Faction' AND j.Actif=1"),0);
						mysqli_close($con);
						if($Detection >0)
						{
							if(($Detection+mt_rand(-10,10))>(mt_rand(-10,10)-$Meteo))
								AddEventFeed(202,$country,$OfficierID,$Placement,$Cible);
						}
					}
				}
			}
			else
			{
				$con=dbconnecti();
				$result1=mysqli_query($con,"SELECT Zone,NoeudR,Garnison,Flag FROM Lieu WHERE ID='$Cible'");
				mysqli_close($con);
				if($result1)
				{
					while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
					{
						$Zone=$data1['Zone'];
						$NoeudR=$data1['NoeudR'];
						$Garnison=$data1['Garnison'];
						$Flag=$data1['Flag'];
					}
					mysqli_free_result($result1);
				}
				if(!$furtif_echec and !$Garnison and ($Zone ==2 or $Zone ==3 or $Zone ==5 or $Zone ==10))$furtif=true;
				if(!$furtif)
				{
					$Faction=GetData("Pays","ID",$country,"Faction");
					$Faction_Cible=GetData("Pays","ID",$Flag,"Faction");
					if($Faction !=$Faction_Cible)
						AddEventFeed(200,$country,$OfficierID,$Placement,$Cible);
				}					
				if($Placement ==2)
				{					
					if($NoeudR ==1)
						$Placement=2;
					else
						$Placement=0;
				}
				else
					$Placement=0;
			}
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Regiment SET Placement='$Placement',Position=4,Move=1,Camouflage=1,Visible=0,Lieu_ID='$Cible',Bomb_IA=0,Bomb_PJ=0,Arti_IA=0,Atk_IA=0 WHERE Officier_ID='$OfficierID'");
			$reset_dem=mysqli_query($con,"UPDATE Officier SET Mission_Lieu_D=0,Mission_Type_D=0 WHERE ID='$OfficierID'");
			mysqli_close($con);
			if(!$mes)$mes='<p>Vos troupes arrivent à destination!</p>';		
		}
		else
			$mes.="<br>Vous retournez à votre point de départ, aucune de vos troupes n'ayant pu poursuivre!";
		$img=Afficher_Image('images/'.$img.'.jpg',"images/image.png","");
		if($CT_move and !$Admin)UpdateData("Officier","Credits",-$CT_move,"ID",$OfficierID);
	}
	else
		$mes="<p>Vous rebroussez chemin.</p>";
	$titre="Déplacement";	
	$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
	include_once('./default.php');
}
?>
