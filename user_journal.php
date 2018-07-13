<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	/*$time=microtime();
	$time=explode(' ',$time);
	$time=$time[1]+$time[0];
	$start=$time;*/
	$Datej=Insec($_POST['datej']);
	$today=date('Y-m-d G:i');
	$i=0;	
	//Victoires
	$con=dbconnecti();
	$Pays=mysqli_result(mysqli_query($con,"SELECT Pays FROM Pilote WHERE ID='$PlayerID'"),0);
	$result_vic=mysqli_query($con,"SELECT * FROM Chasse WHERE ((Joueur_win='$PlayerID' AND PVP IN(0,4)) OR (Pilote_loss='$PlayerID' AND PVP=1)) AND TO_DAYS(NOW()) - TO_DAYS(Date) <='$Datej'");
	$result_vic2=mysqli_query($con,"SELECT * FROM Chasse_Probable WHERE TO_DAYS(NOW()) - TO_DAYS(Date) <='$Datej' AND Joueur_win='$PlayerID'");
	$result_vic3=mysqli_query($con,"SELECT Avion,Unite,Lieu,Arme,Date,Cycle,Cible_id FROM Bombardement WHERE TO_DAYS(NOW()) - TO_DAYS(Date) <='$Datej' AND Joueur='$PlayerID'");
	$result_vic4=mysqli_query($con,"SELECT Cible_id,Avion,Unite,Lieu,Arme,Date FROM Attaque WHERE Joueur='$PlayerID' AND TO_DAYS(NOW()) - TO_DAYS(Date) <='$Datej'");
	$result_vic5=mysqli_query($con,"SELECT Avion,Unite,Lieu,Date FROM DCA WHERE TO_DAYS(NOW()) - TO_DAYS(Date) <='$Datej' AND Joueur='$PlayerID'");
	$result_vic6=mysqli_query($con,"SELECT Avion,Unite,Lieu,Date,Escorte,Escorte_nbr FROM Escorte WHERE TO_DAYS(NOW()) - TO_DAYS(Date) <='$Datej' AND Joueur='$PlayerID'");
	$result_vic8=mysqli_query($con,"SELECT Nom,Avion,Unite,Lieu,Date FROM Recce WHERE TO_DAYS(NOW()) - TO_DAYS(Date) <='$Datej' AND Joueur='$PlayerID'");
	mysqli_close($con);
	if($result_vic)
	{
		while($data=mysqli_fetch_array($result_vic,MYSQLI_ASSOC))
		{
			$Date=substr($data['Date'],0,16);
			$Avion=GetData("Avion","ID",$data['Avion_win'],"Nom");
			$Avion_loss=GetData("Avion","ID",$data['Avion_loss'],"Nom");
			$Arme=GetData("Armes","ID",$data['Arme_win'],"Nom");
			//$Lieu=$data['Lieu'];
			if($data['PVP'] ==0)
			{
				$Unit=GetData("Unit","ID",$data['Unite_win'],"Nom");
				$Cocarde=GetData("Avion","ID",$data['Avion_loss'],"Pays");
				$Tableau[$i]=$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez abattu un <b>'.$Avion_loss.'</b> <img src=\''.$Cocarde.'20.gif\'>'; //dans les environs de ".$Lieu."<br>";
			}
			elseif($data['PVP'] ==1)
			{
				$Unit=GetData("Unit","ID",$data['Unite_loss'],"Nom");
				$Tableau[$i]=$Date.' : Pilotant un '.$Avion_loss.' du '.$Unit.', vous avez été abattu par un '.$Avion.', d\'une rafale de '.$Arme; //dans les environs de ".$Lieu."<br>";
			}
			$i++;
		}
		mysqli_free_result($result_vic);
		unset($data);
	}
	else
		$Tableau="Erreur";
	//Victoires_Probables
	if($result_vic2)
	{
		while($data=mysqli_fetch_array($result_vic2,MYSQLI_ASSOC))
		{
			$Date=substr($data['Date'],0,16);
			$Avion=GetData("Avion","ID",$data['Avion_win'],"Nom");
			$Avion_loss=GetData("Avion","ID",$data['Avion_loss'],"Nom");
			$Arme=GetData("Armes","ID",$data['Arme_win'],"Nom");
			//$Lieu = $data['Lieu'];
			if($data['PVP'] ==0)
			{
				$Unit=GetData("Unit","ID",$data['Unite_win'],"Nom");
				$Cocarde=GetData("Avion","ID",$data['Avion_loss'],"Pays");
				$Tableau[$i]=$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez abattu un <b>'.$Avion_loss.'</b>. Cette victoire n\'a pas été confirmée.'; //dans les environs de ".$Lieu."<br>";
			}
			elseif($data['PVP'] ==1)
			{
				$Unit=GetData("Unit","ID",$data['Unite_win'],"Nom");
				$Tableau[$i] =$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez endommagé un '.$Avion_loss; //dans les environs de ".$Lieu."<br>";
			}
			elseif($data['PVP'] ==2)
			{
				$Unit=GetData("Unit","ID",$data['Unite_win'],"Nom");
				$Tableau[$i]=$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez abattu un '.$Avion_loss.' en collaboration. Cette victoire n\'a pas été confirmée.'; //dans les environs de ".$Lieu."<br>";
			}
			$i++;
		}
		mysqli_free_result($result_vic2);
		unset($data);
	}
	else
		$Tableau="Erreur";
	//Bombardement
	if($result_vic3)
	{
		while($data=mysqli_fetch_array($result_vic3,MYSQLI_ASSOC))
		{
			$Arme_id=$data['Arme'];
			$Cycle=$data['Cycle'];
			$Date=substr($data['Date'],0,16);
			$Avion=GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit=GetData("Unit","ID",$data['Unite'],"Nom");
			$Nom=GetData("Cible","ID",$data['Cible_id'],"Nom");
			$Lieu=GetData("Lieu","ID",$data['Lieu'],"Nom");
			if($Arme_id ==800)
				$Arme="à l'aide d'une torpille";
			elseif($Arme_id ==400)
				$Arme="à l'aide d'une mine";
			elseif($Arme_id <49)
				$Arme="d'une rafale de ".GetData("Armes","ID",$Arme_id,"Nom");
			else
				$Arme="à l'aide d'une bombe";
			if(substr($Nom,-1,1) =="x" or substr($Nom,-1,1) =="s")
				$Tableau[$i] =$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez détruit des <b>'.$Nom.'</b> '.$Arme.', dans les environs de '.$Lieu.'.<br>';
			else
				$Tableau[$i] =$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez détruit un <b>'.$Nom.'</b> '.$Arme.', dans les environs de '.$Lieu.'.<br>';
			$i++;
		}
		mysqli_free_result($result_vic3);
		unset($data);
	}
	else
		$Tableau="Erreur";
	//Attaques
	if($result_vic4)
	{
		while($data=mysqli_fetch_array($result_vic4,MYSQLI_ASSOC))
		{
			$Date=substr($data['Date'],0,16);
			$Avion=GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit=GetData("Unit","ID",$data['Unite'],"Nom");
			$Nom=GetData("Cible","ID",$data['Cible_id'],"Nom");
			$Arme_id = $data['Arme'];
			$Lieu=GetData("Lieu","ID",$data['Lieu'],"Nom");
			if($Arme_id == 800)
				$Arme="à l'aide d'une torpille";
			elseif($Arme_id ==400)
				$Arme="à l'aide d'une mine";
			elseif($Arme_id <49)
				$Arme="d'une rafale de ".GetData("Armes","ID",$Arme_id,"Nom");
			else
				$Arme="à l'aide d'une bombe";
			if(substr($Nom,-1,1) == "x" or substr($Nom,-1,1) == "s")
				$Tableau[$i]=$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez détruit des <b>'.$Nom.'</b> '.$Arme.', dans les environs de '.$Lieu.'.<br>';
			else
				$Tableau[$i]=$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez détruit un <b>'.$Nom.'</b> '.$Arme.', dans les environs de '.$Lieu.'.<br>';
			$i++;
		}
		mysqli_free_result($result_vic4);
		unset($data);
	}
	else
		$Tableau="Erreur";
	//DCA
	if($result_vic5)
	{
		while($data=mysqli_fetch_array($result_vic5,MYSQLI_ASSOC))
		{
			$Date=substr($data['Date'],0,16);
			$Avion=GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit=GetData("Unit","ID",$data['Unite'],"Nom");
			$Lieu=GetData("Lieu","ID",$data['Lieu'],"Nom");
			$Tableau[$i]=$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez été abattu par la D.C.A dans les environs de '.$Lieu.'.<br>';
			$i++;
		}
		mysqli_free_result($result_vic5);
		unset($data);
	}
	else
		$Tableau="Erreur";
	//Escorte
	if($result_vic6)
	{
		while($data=mysqli_fetch_array($result_vic6,MYSQLI_ASSOC))
		{
			$Date=substr($data['Date'],0,16);
			$Avion=GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit=GetData("Unit","ID",$data['Unite'],"Nom");
			$Lieu=GetData("Lieu","ID",$data['Lieu'],"Nom");
			if($data['Escorte'] >0)
				$Escorte_nom=GetData("Avion","ID",$data['Escorte'],"Nom");
			else
				$Escorte_nom="navires";
			$Escorte_nbr=$data['Escorte_nbr'];
			$Tableau[$i]=$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez escorté une formation de <b>'.$Escorte_nbr.' '.$Escorte_nom.'</b> vers '.$Lieu.'.<br>';
			$i++;
		}
		mysqli_free_result($result_vic6);
		unset($data);
	}
	else
		$Tableau="Erreur";
	//Recce
	if($result_vic8)
	{
		while($data=mysqli_fetch_array($result_vic8,MYSQLI_ASSOC))
		{
			$Date=substr($data['Date'],0,16);
			$Nom=$data['Nom'];
			$Avion=GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit=GetData("Unit","ID",$data['Unite'],"Nom");
			$Lieu=GetData("Lieu","ID",$data['Lieu'],"Nom");
			if($data['Nom'] ==1)
				$Nom="une cible non identifiée";
			elseif($data['Nom'] ==6)
				$Nom="une flotte non identifiée";
			elseif($data['Nom'] ==15)
				$Nom="des infrastructures";
			elseif($data['Nom'] ==16)
				$Nom="le port";
			elseif($data['Nom'] ==50)
				$Nom="des unités terrestres";
			elseif($data['Nom'] ==56)
				$Nom="des navires";
			if($Nom !=$Lieu)
			{
				$Tableau[$i]=$Date.' : Pilotant un '.$Avion.' du '.$Unit.', vous avez photographié <b>'.$Nom.'</b> dans les environs de '.$Lieu.'.<br>';
				$i++;
			}
		}
		mysqli_free_result($result_vic8);
		unset($data);
	}
	usleep(10);	
	//Journal
	$con=dbconnecti(4);
	$result=mysqli_query($con,"(ELECT * FROM Events WHERE Event_Type IN (4,5,6,7,9,10,11,12,13,14,18,19,22,27,29,30,31,32,33,34,35,36)
	AND PlayerID='$PlayerID' AND TO_DAYS(NOW()) - TO_DAYS(`Date`) <='$Datej'");
	mysqli_close($con);
	if($result)
	{
		while($Classement=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$Event_Type=$Classement['Event_Type'];
			$Event_Date=substr($Classement['Date'],0,16);
			$Event_Avion_Nbr=$Classement['Avion_Nbr'];
			$Event_Lieu_Nom=GetData("Lieu","ID",$Classement['Lieu'],"Nom");
			$Event_Pilote_Nom=GetData("Pilote","ID",$Classement['PlayerID'],"Nom");
			$Event_Avion_Nom=GetData("Avion","ID",$Classement['Avion'],"Nom");
			switch($Event_Type)
			{
				case 1:
					$Event_Avioneni_Nom=GetData("Avion","ID",$Event_Avion_Nbr,"Nom");
					$Event_Type_txt='un combat a été engagé face à un '.$Event_Avioneni_Nom;
				break;
				case 3:
					//$Event_Type_txt="vous avez été abattu.";
				break;
				case 4:
					$Event_Type_txt="vous êtes tombé en panne d'essence.";
				break;
				case 5:
					$Event_Type_txt="vous avez attaqué une cible au sol.";
				break;
				case 6:
					$Event_Type_txt="vous avez bombardé un objectif.";
				break;
				case 7:
					$Event_Type_txt="vous avez effectué une mission de reconnaissance.";
				break;
				case 9:
					$Event_Type_txt="vous avez été blessé.";
				break;
				case 10:
					$Event_Type_txt="vous avez attaqué un navire.";
				break;
				case 11:
					$Event_Type_txt="vous vous êtes crashé au décollage.";
				break;
				case 12:
					$Event_Type_txt="vous vous êtes crashé à l'atterrissage.";
				break;
				case 13:
					$Event_Type_txt='vous avez endommagé les défenses anti-aériennes de '.$Event_Lieu_Nom;
				break;
				case 14:
					$Event_Type_txt='vous avez détruit un hangar de la base de '.$Event_Lieu_Nom;
				break;
				case 18:
					if($Event_Avion_Nbr > 4)
						$aa_type="de gros calibre";
					elseif($Event_Avion_Nbr > 2)
						$aa_type="de calibre moyen";
					elseif($Event_Avion_Nbr >0)
						$aa_type="de faible calibre";
					else
						$aa_type="inexistante";
					$Event_Type_txt='vous avez repéré une DCA '.$aa_type;
				break;
				case 19:
					$Unite_eni_Nom=GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
					$Event_Type_txt='vous avez repéré la base du '.$Unite_eni_Nom;
				break;
				case 22:
					$Tableau[$i] =$Event_Date.' : Vous avez détruit un <b>'.$Event_Avion_Nom.'</b> sur l\'aérodrome de '.$Event_Lieu_Nom.'.<br>';
				break;
				case 27:
					$Tableau[$i]=$Event_Date.' : Pilotant un '.$Event_Avion_Nom.', vous avez endommagé la piste de la base de '.$Event_Lieu_Nom.'<br>';
				break;
				case 29:
					$Tableau[$i]=$Event_Date.' : Pilotant un '.$Event_Avion_Nom.', vous avez endommagé le port de '.$Event_Lieu_Nom.'<br>';
				break;
				case 30:
					$Event_Medal=GetMedal_Name($Pays,$Event_Avion_Nbr);
					if($Event_Avion_Nbr == 0)
						$Tableau[$i] =$Event_Date.' : Vous avez reçu votre brevet de Pilote.<br>';
					else
						$Tableau[$i] =$Event_Date.' : Vous avez été décoré de la <b>'.$Event_Medal.'<b><br>';
				break;
				case 31:
					$Event_Unite_Nom=GetData("Unit","ID",$Classement['Unit'],"Nom");
					$Event_Unite_Dest_Nom=GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
					$Tableau[$i]=$Event_Date.' : Vous avez été transféré du '.$Event_Unite_Nom.' vers le '.$Event_Unite_Dest_Nom.', basé à '.$Event_Lieu_Nom.'<br>';
				break;
				case 32:
					$Grade=GetAvancement(0,$Pays,$Event_Avion_Nbr);
					$Tableau[$i]=$Event_Date.' : Vous avez été promu au grade de <b>'.$Grade[0].'</b><br>';
				break;
				case 33:
					$Event_Avion_Nom=GetData("Pilote","ID",$Classement['Avion'],"Nom");
					$Event.=$Event_Date.' : Vous avez participé à une formation donnée par '.$Event_Avion_Nom.' où vous avez progressé de <b>'.$Event_Avion_Nbr.'</b>.<br>';
				break;
				case 34:
					$Event='Vous avez perdu le contrôle de votre appareil suite à un incident. Un <b>'.$Event_Avion_Nom.'</b> a été perdu.';
				break;
				case 35:
					$Event.=$Event_Date.' : Grièvement blessé, vous errez quelques temps dans la région de '.$Event_Lieu_Nom.'<br>';
				break;
				case 36:
					if(!$Event_Avion_Nbr)
						$Event.=$Event_Date.' : Vous avez été ramené sain et sauf à bord d\'un <b>'.$Event_Avion_Nom.'</b><br>';
					else
					{
						$Event_Rescued_Nom=GetData("Pilote","ID",$Event_Avion_Nbr,"Nom");
						$Event.=$Event_Date.' : Vous avez ramené '.$Event_Rescued_Nom.' sain et sauf à bord de votre <b>'.$Event_Avion_Nom.'</b><br>';
					}
				break;
				case 38:
					$Event.=$Event_Date.' : Vous avez décollé aux commandes d\'un <b>'.$Event_Avion_Nom.'</b>, depuis votre base de '.$Event_Lieu_Nom.'<br>';
				break;
				case 90:
					$Event_Avioneni_Nom=GetData("Avion","ID",$Event_Avion_Nbr,"Nom");
					$Event.=$Event_Date.' : Vous avez engagé un combat PvP face à un <b>'.$Event_Avioneni_Nom.'</b> aux commandes de votre <b>'.$Event_Avion_Nom.'</b>, dans les environs de '.$Event_Lieu_Nom.'<br>';
				break;
			}
			if($Event_Type <20 and $Event_Type_txt !="")
			{
				$Tableau[$i]=$Event_Date.' : Pilotant un '.$Event_Avion_Nom.' dans les environs de '.$Event_Lieu_Nom.', '.$Event_Type_txt.'<br>';
				//$Event=$Event.$Event_Date." : ".$Event_Pilote_Nom.", pilotant un ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom.", ".$Event_Type_txt."<br>";
			}
			$i++;
		}
		mysqli_free_result($result);
		unset($Classement);
	}
	if(is_array($Tableau))
	{	
		rsort($Tableau);
		//print_r($Tableau);
		$count=count($Tableau);
	}
	if(!$count or !$Tableau)
	{
		$count=1;
		$Tableau[0]="Le journal est vide";
	}	
	echo "<h1>Journal Personnel</h1><div style='width:100%'><table class='table table-hover'>";
	for($u=0;$u<$count;$u++)
	{
		echo "<tr><td>".$Tableau[$u]."</td></tr>";
	}
	echo "</table></div>";	
	/*Time
	$time=microtime();
	$time=explode(' ',$time);
	$time=$time[1]+$time[0];
	$finish=$time;
	$total_time=round(($finish - $start),4);
	echo '<br>Page generated in '.$total_time.' seconds.';*/
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>