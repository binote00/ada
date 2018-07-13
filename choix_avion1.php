<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Plane=Insec($_POST['avion']);
if(isset($_SESSION['AccountID']) AND $Plane >0)
{
	$PlayerID=Insec($_POST['Joueur']);
	if(!$PlayerID)
	{
		$methode=Insec($_POST['methode']);
		$PlayerID=$_SESSION['PlayerID'];
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result=mysqli_query($con,"SELECT Nom,Pays,Unit,Avancement,Reputation,Credits,S_Mission FROM Pilote WHERE ID='$PlayerID'");
		$result2=mysqli_query($con,"SELECT Production,Stock,Fin_Prod FROM Avion WHERE ID='$Plane'");
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
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Pilote_Nom=$data['Nom'];
				$Pays=$data['Pays'];
				$Unite=$data['Unit'];
				$Avancement=$data['Avancement'];
				$Reput=$data['Reputation'];
				$Credits=$data['Credits'];
				$SMission=$data['S_Mission'];
			}
			mysqli_free_result($result);
		}
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Production=$data2['Production'];
				$Stock=$data2['Stock'];
				$Fin_Prod=$data2['Fin_Prod'];
			}
			mysqli_free_result($result2);
		}
		if(is_array($Skills_Pil))
		{
			if(in_array(104,$Skills_Pil))
				$Mecano3=true;
		}
		if($Fin_Prod <$Date_Campagne or $Mecano3)
		{
			$Cred_Min=10;
			$Total=0;
		}
		else
		{
			$con=dbconnecti(4);
			$Perdu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type IN (11,12,34) AND PlayerID='$PlayerID' AND Avion='$Plane' AND Avion_Nbr=1"),0);
			mysqli_close($con);
			$con=dbconnecti();
			$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Plane' AND PVP=1 AND Pilote_loss='$PlayerID'"),0);
			$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Plane' AND Joueur='$PlayerID'"),0);
			mysqli_close($con);			
			$Total=$Perdu+$Abattu+$DCA;
			if($Total >=($Production/20))$Total=250;
			$Cred_Min=15+ceil($Total/10);		
		}
		$Reput_Min=11000+$Total;
		$Cout_Reput=0-1000-$Total;
	}
	else
	{
		$Type=Insec($_POST['tp']);
		if($Type)$Mission=127;
	}
	if(($Credits >=$Cred_Min and $methode ==1) or ($Reput >=$Reput_Min and $methode ==2) or $Mission ==127 or $SMission ==999)
	{		
		//Prod
		$con=dbconnecti();
		$Abattu_t=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Plane' AND PVP=1"),0);
		$DCA_t=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Plane'"),0);
		mysqli_close($con);
		$con=dbconnecti(4);
		$Crash_t=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$Plane' AND Avion_Nbr >0"),0);
		mysqli_close($con);		
		if(($DCA_t + $Abattu_t + $Crash_t) >$Stock and $Mission !=127 and $SMission !=999)
		{
			$tr="no";
			$mes.="Cet avion n'est plus disponible,car tous les modèles produits ont été détruits.";
		}
		elseif($Reput >999 or $Avancement >999 or $Mission ==127 or $SMission ==999)
		{
			$query_insert="INSERT INTO Avions_Persos (Nom,Pays,Engagement,Type,Etat,Equipage,Engine,Engine_Nbr,Puissance,Masse,ChargeAlaire,VitesseH,VitesseB,VitesseA,VitesseP,Alt_ref,Stabilite,Maniabilite,ManoeuvreH,ManoeuvreB,Robustesse,
			Detection,Plafond,Autonomie,Visibilite,Blindage,ArmePrincipale,Arme1_Nbr,Arme1_Mun,ArmeSecondaire,Arme2_Nbr,Arme2_Mun,ArmeArriere,Arme3_Nbr,ArmeSabord,Arme4_Nbr,TourelleSup,Arme5_Nbr,TourelleVentre,Arme6_Nbr,Bombe,Bombe_Nbr,
			Helice,Train,PareBrise,Carburant,Compresseur,Injection,Moteur,Verriere,Viseur,Volets,Voilure,Reservoir,Cellule,Baby,Navigation,Radio,Radar)
			SELECT Nom,Pays,Engagement,Type,Etat,Equipage,Engine,Engine_Nbr,Puissance,Masse,ChargeAlaire,VitesseH,VitesseB,VitesseA,VitesseP,Alt_ref,Stabilite,Maniabilite,ManoeuvreH,ManoeuvreB,Robustesse,
			Detection,Plafond,Autonomie,Visibilite,Blindage,ArmePrincipale,Arme1_Nbr,Arme1_Mun,ArmeSecondaire,Arme2_Nbr,Arme2_Mun,ArmeArriere,Arme3_Nbr,ArmeSabord,Arme4_Nbr,TourelleSup,Arme5_Nbr,TourelleVentre,Arme6_Nbr,Bombe,Bombe_Nbr,
			Helice,Train,PareBrise,Carburant,Compresseur,Injection,Moteur,Verriere,Viseur,Volets,Voilure,Reservoir,Cellule,Baby,Navigation,Radio,Radar
			FROM Avion WHERE ID='$Plane'";
			$con=dbconnecti();
			$ok=mysqli_query($con,$query_insert);
			if(!$ok)
			{
				$tr="no";
				$mes.="<br>Erreur de mise à jour de l'avion (Error code 11)!";
				mail('binote@hotmail.com','Aube des Aigles: Erreur Avion perso ',$Pilote_Nom.' '.$mes);
			}
			else
			{
				$ins_id=mysqli_insert_id($con);
				if($Type ==2)
					$query_update="UPDATE Pilote SET Proto='$ins_id' WHERE ID='$PlayerID'";
				else
					$query_update="UPDATE Pilote SET Avion_Perso='$ins_id' WHERE ID='$PlayerID'";
				$update_ok=mysqli_query($con,$query_update);
				if($update_ok)
				{
					//correspondance avion série et Skip bombs
					$id_ok=mysqli_query($con,"UPDATE Avions_Persos SET ID_ref='$Plane',Bombe=0,Bombe_Nbr=0 WHERE ID='$ins_id'");
					if($id_ok)
					{
						$Avion_Nom=GetData("Avions_Persos","ID",$ins_id,"Nom");
						if($Plane !=44)
						{
							if($Type ==2)
								$Msg=$Pilote_Nom." a reçu son nouveau prototype,un ".$Avion_Nom;
							else
								$Msg=$Pilote_Nom." a reçu son nouvel appareil personnalisé,un ".$Avion_Nom;
							//mail('binote@hotmail.com','Aube des Aigles: Avion perso ',$Msg);
						}
						$mes.='Votre avion personnalisé,un <b>'.$Avion_Nom.'</b>,vous attend dans son hangar !';
						$img ="<img src='images/avions/garage".$Plane.".jpg'>";
						$menu="<a title='Accéder au hangar' href='index.php?view=garage' class='btn btn-default'>Accéder au hangar</a>";
						if($Mission !=127 and $SMission !=999)
						{
							if($methode ==1)
								$credits_txt=MoveCredits($PlayerID,12,-$Cred_Min);
							elseif($methode ==2)
								UpdateCarac($PlayerID,"Reputation",$Cout_Reput);
						}
					}
					else
					{
						$tr="no";
						$mes.="<br>Erreur de mise à jour de l'avion ! ((Error code 12)";
						mail('binote@hotmail.com','Aube des Aigles: Erreur Avion perso ',$Pilote_Nom.' '.$mes);
					}
				}
				else
				{
					$tr="no";
					$mes.="<br>Erreur de mise à jour du pilote ! (Error code 13)";
					mail('binote@hotmail.com','Aube des Aigles: Erreur Avion perso ',$Pilote_Nom.' '.$mes);
				}
			}
		}
		else
		{
			$tr="no";
			$mes.="Votre réputation n'est pas suffisante pour posséder un avion personnalisé!";
		}
	}
	else
	{
		$tr="no";
		$mes.="Vous ne possédez pas la réputation ou les crédits suffisants pour changer d'avion personnalisé!";
	}
	if($tr == "no")
		$img="<img src='images/transfer_".$tr.$Pays.".jpg'>";
}
else
{
	$mes="Vous n'avez pas accès à cette page!";
	$img="<img src='images/tsss.jpg'>";
}
include_once('./index.php');
?>