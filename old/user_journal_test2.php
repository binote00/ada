<?
session_start();
include_once('jfv_include.inc.php');
include_once('jfv_txt.inc.php');

//Check Joueur Valide
if(isset($_SESSION['login']) AND isset($_SESSION['pwd']))
{
	$PlayerID = $_SESSION['PlayerID'];
	$Pays_Origine = GetData("Joueur","ID",$PlayerID,"Pays_Origine");
	$i = 0;
	
	//Victoires
	dbconnect();
	$query_vic = "SELECT * FROM Chasse WHERE (Joueur_win='$PlayerID' AND PVP=0) OR (Pilote_loss='$PlayerID' AND PVP=1) ORDER BY Date DESC";
	$result_vic=mysql_query($query_vic);
	if($result_vic)
	{
		while($data = mysql_fetch_array($result_vic))
		{
			$Date = substr($data['Date'],0,16);
			$Avion = GetData("Avion","ID",$data['Avion_win'],"Nom");
			$Avion_loss = GetData("Avion","ID",$data['Avion_loss'],"Nom");
			$Arme = GetData("Armes","ID",$data['Arme_win'],"Nom");
			//$Lieu = $data['Lieu'];
			if($data['PVP'] == 0)
			{
				$Unit = GetData("Unit","ID",$data['Unite_win'],"Nom");
				$Cocarde = GetData("Avion","ID",$data['Avion_loss'],"Pays");
				$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez abattu un <b>".$Avion_loss."</b>, d'une rafale de ".$Arme." <img src='".$Cocarde."20.gif'>"; //dans les environs de ".$Lieu."<br>";
			}
			elseif($data['PVP'] == 1)
			{
				$Unit = GetData("Unit","ID",$data['Unite_loss'],"Nom");
				$Tableau[$i] = $Date." : Pilotant un ".$Avion_loss." du ".$Unit.", vous avez été abattu par un ".$Avion.", d'une rafale de ".$Arme; //dans les environs de ".$Lieu."<br>";
			}
			$i++;
		}	
	}
	else
	{
		$Tableau = "Erreur";
	}

	//Victoires_Probables
	dbconnect();
	$query_vic = "SELECT * FROM Chasse_Probable WHERE Joueur_win='$PlayerID' ORDER BY Date DESC";
	$result_vic=mysql_query($query_vic);
	if($result_vic)
	{
		while($data = mysql_fetch_array($result_vic))
		{
			$Date = substr($data['Date'],0,16);
			$Avion = GetData("Avion","ID",$data['Avion_win'],"Nom");
			$Avion_loss = GetData("Avion","ID",$data['Avion_loss'],"Nom");
			$Arme = GetData("Armes","ID",$data['Arme_win'],"Nom");
			//$Lieu = $data['Lieu'];
			if($data['PVP'] == 0)
			{
				$Unit = GetData("Unit","ID",$data['Unite_win'],"Nom");
				$Cocarde = GetData("Avion","ID",$data['Avion_loss'],"Pays");
				$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez abattu un <b>".$Avion_loss."</b>, d'une rafale de ".$Arme." Cette victoire n'a pas été confirmée."; //dans les environs de ".$Lieu."<br>";
			}
			elseif($data['PVP'] == 1)
			{
				$Unit = GetData("Unit","ID",$data['Unite_win'],"Nom");
				$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez endommagé un ".$Avion_loss.", d'une rafale de ".$Arme; //dans les environs de ".$Lieu."<br>";
			}
			elseif($data['PVP'] == 2)
			{
				$Unit = GetData("Unit","ID",$data['Unite_win'],"Nom");
				$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez abattu un ".$Avion_loss." en collaboration, d'une rafale de ".$Arme." Cette victoire n'a pas été confirmée."; //dans les environs de ".$Lieu."<br>";
			}
			$i++;
		}	
	}
	else
	{
		$Tableau = "Erreur";
	}
	
	//Attaques
	dbconnect();
	$query_vic = "SELECT Nom,Avion,Unite,Lieu,Arme,Date FROM Attaque WHERE Joueur='$PlayerID' ORDER BY Date DESC";
	$result_vic=mysql_query($query_vic);
	if($result_vic)
	{
		while($data = mysql_fetch_array($result_vic))
		{
			$Date = substr($data['Date'],0,16);
			$Nom = $data['Nom'];
			$Avion = GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit = GetData("Unit","ID",$data['Unite'],"Nom");
			$Arme_id = $data['Arme'];
			$Lieu = GetData("Lieu","ID",$data['Lieu'],"Nom");
			if($Arme_id == 800)
			{
				$Arme = "à l'aide d'une torpille";
			}
			elseif($Arme_id == 400)
			{
				$Arme = "à l'aide d'une mine";
			}
			elseif($Arme_id < 49)
			{
				$Arme = "d'une rafale de ".GetData("Armes","ID",$Arme_id,"Nom");
			}
			else
			{
				$Arme = "à l'aide d'une bombe";
			}
			if(substr($Nom,-1,1) == "x" or substr($Nom,-1,1) == "s")
			{
				$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez détruit des <b>".$Nom."</b> ".$Arme.", dans les environs de ".$Lieu.".<br>";
			}
			else
			{
				$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez détruit un <b>".$Nom."</b> ".$Arme.", dans les environs de ".$Lieu.".<br>";
			}
			$i++;
		}
	}
	else
	{
		$Tableau = "Erreur";
	}
	//DCA
	dbconnect();
	$query_vic = "SELECT Avion,Unite,Lieu,Date FROM DCA WHERE Joueur='$PlayerID' ORDER BY Date DESC";
	$result_vic=mysql_query($query_vic);
	if($result_vic)
	{
		while($data = mysql_fetch_array($result_vic))
		{
			$Date = substr($data['Date'],0,16);
			$Avion = GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit = GetData("Unit","ID",$data['Unite'],"Nom");
			$Lieu = GetData("Lieu","ID",$data['Lieu'],"Nom");
			$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez été abattu par la D.C.A dans les environs de ".$Lieu.".<br>";
			$i++;
		}
	}
	else
	{
		$Tableau = "Erreur";
	}
	
	//Escorte
	dbconnect();
	$query_vic = "SELECT Avion,Unite,Lieu,Date,Escorte,Escorte_nbr FROM Escorte WHERE Joueur='$PlayerID' ORDER BY Date DESC";
	$result_vic=mysql_query($query_vic);
	if($result_vic)
	{
		while($data = mysql_fetch_array($result_vic))
		{
			$Date = substr($data['Date'],0,16);
			$Avion = GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit = GetData("Unit","ID",$data['Unite'],"Nom");
			$Lieu = GetData("Lieu","ID",$data['Lieu'],"Nom");
			if($data['Escorte'] > 0)
			{
				$Escorte_nom = GetData("Avion","ID",$data['Escorte'],"Nom");
			}
			else
			{
				$Escorte_nom = "navires";
			}
			$Escorte_nbr = $data['Escorte_nbr'];
			$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez escorté une formation de <b>".$Escorte_nbr." ".$Escorte_nom."</b> vers ".$Lieu.".<br>";
			$i++;
		}
	}
	else
	{
		$Tableau = "Erreur";
	}
	
	//Intercept
	dbconnect();
	$query_vic = "SELECT Date,Avion_win,Unite_win,Lieu FROM Intercept WHERE Joueur_win='$PlayerID' ORDER BY Date DESC";
	$result_vic=mysql_query($query_vic);
	if($result_vic)
	{
		while($data = mysql_fetch_array($result_vic))
		{
			$Date = substr($data['Date'],0,16);
			$Avion = GetData("Avion","ID",$data['Avion_win'],"Nom");
			$Unit = GetData("Unit","ID",$data['Unite_win'],"Nom");
			$Lieu = GetData("Lieu","ID",$data['Lieu'],"Nom");
			$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez intercepté une formation ennemie au-dessus de ".$Lieu.".<br>";
			$i++;
		}
	}
	//Recce
	dbconnect();
	$query_vic = "SELECT Nom,Avion,Unite,Lieu,Date FROM Recce WHERE Joueur='$PlayerID' ORDER BY Date DESC";
	$result_vic=mysql_query($query_vic);
	if($result_vic)
	{
		while($data = mysql_fetch_array($result_vic))
		{
			$Date = substr($data['Date'],0,16);
			$Nom = $data['Nom'];
			$Avion = GetData("Avion","ID",$data['Avion'],"Nom");
			$Unit = GetData("Unit","ID",$data['Unite'],"Nom");
			$Lieu = GetData("Lieu","ID",$data['Lieu'],"Nom");
			if($Nom != $Lieu)
			{
				$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez photographié un <b>".$Nom."</b> dans les environs de ".$Lieu.".<br>";
				$i++;
			}
		}
	}
	
	//Journal
	dbconnect();
	$query="SELECT * FROM Events WHERE PlayerID='$PlayerID' AND Event_Type IN(1,4,5,6,7,9,10,11,12,13,14,18,19,22,27,29,30,31,32,33,34,35,36,38,90) ORDER BY Date DESC";
	$result=mysql_query($query);
	if($result)
	{
		while($Classement=mysql_fetch_array($result)) 
		{
			$Event_Type=$Classement['Event_Type'];
			$Event_Date=substr($Classement['Date'],0,16);
			$Event_Lieu=$Classement['Lieu'];
			$Event_Lieu_Nom = GetData("Lieu","ID",$Event_Lieu,"Nom");
			$Event_PlayerID=$Classement['PlayerID'];
			$Event_Pilote_Nom = GetData("Joueur","ID",$Event_PlayerID,"Nom");
			$Event_Avion=$Classement['Avion'];
			$Event_Avion_Nbr=$Classement['Avion_Nbr'];
			$Event_Unit = $Classement['Unit'];
			$Event_Avion_Nom = GetData("Avion","ID",$Event_Avion,"Nom");
			switch($Event_Type)
			{
				case 1:
					$Event_Avioneni_Nom = GetData("Avion","ID",$Event_Avion_Nbr,"Nom");
					$Event_Type_txt = "un combat a été engagé face à un ".$Event_Avioneni_Nom;
				break;
				case 3:
					//$Event_Type_txt = "vous avez été abattu.";
				break;
				case 4:
					$Event_Type_txt = "vous êtes tombé en panne d'essence.";
				break;
				case 5:
					$Event_Type_txt = "vous avez attaqué une cible au sol.";
				break;
				case 6:
					$Event_Type_txt = "vous avez bombardé un objectif.";
				break;
				case 7:
					$Event_Type_txt = "vous avez effectué une mission de reconnaissance.";
				break;
				case 9:
					$Event_Type_txt = "vous avez été blessé.";
				break;
				case 10:
					$Event_Type_txt = "vous avez attaqué un navire.";
				break;
				case 11:
					$Event_Type_txt = "vous vous êtes crashé au décollage.";
				break;
				case 12:
					$Event_Type_txt = "vous vous êtes crashé à l'atterrissage.";
				break;
				case 13:
					$Event_Type_txt = "vous avez endommagé les défenses anti-aériennes de ".$Event_Lieu_Nom;
				break;
				case 14:
					$Event_Type_txt = "vous avez détruit un hangar de la base de ".$Event_Lieu_Nom;
				break;
				case 18:
					if($Event_Avion_Nbr > 4)
					{
						$aa_type = "de gros calibre";
					}
					elseif($Event_Avion_Nbr > 2)
					{
						$aa_type = "de calibre moyen";
					}
					elseif($Event_Avion_Nbr > 0)
					{
						$aa_type = "de faible calibre";
					}
					else
					{
						$aa_type = "inexistante";
					}					
					$Event_Type_txt = "vous avez repéré une DCA ".$aa_type;
				break;
				case 19:
					$Unite_eni_Nom = GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
					$Event_Type_txt = "vous avez repéré la base du ".$Unite_eni_Nom;
				break;
				case 22:
					$Tableau[$i] = $Event_Date." : Vous avez détruit un <b>".$Event_Avion_Nom."</b> sur l'aérodrome de ".$Event_Lieu_Nom.".<br>";
				break;
				case 27:
					$Tableau[$i] = $Event_Date." : Pilotant un ".$Event_Avion_Nom.", vous avez endommagé la piste de la base de ".$Event_Lieu_Nom."<br>";
				break;
				case 29:
					$Tableau[$i] = $Event_Date." : Pilotant un ".$Event_Avion_Nom.", vous avez endommagé la port de ".$Event_Lieu_Nom."<br>";
				break;
				case 30:
					$Event_Medal = GetMedal_Name($Pays_Origine, $Event_Avion_Nbr);
					if($Event_Avion_Nbr == 0)
					{
						$Tableau[$i] = $Event_Date." : Vous avez reçu votre brevet de Pilote.<br>";
					}
					else
					{
						$Tableau[$i] = $Event_Date." : Vous avez été décoré de la <b>".$Event_Medal."<b><br>";
					}
				break;
				case 31:
					$Event_Unite_Nom = GetData("Unit","ID",$Event_Unit,"Nom");
					$Event_Unite_Dest_Nom = GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
					$Tableau[$i] = $Event_Date." : Vous avez été transféré du ".$Event_Unite_Nom." vers le ".$Event_Unite_Dest_Nom.", basé à ".$Event_Lieu_Nom."<br>";
				break;
				case 32:
					$Grade = GetAvancement(0, $Pays_Origine, $Event_Avion_Nbr);
					$Tableau[$i] = $Event_Date." : Vous avez été promu au grade de <b>".$Grade[0]."</b><br>";
				break;
				case 33:
					$Event_Avion_Nom = GetData("Joueur","ID",$Event_Avion,"Nom");
					$Event = $Event.$Event_Date." : Vous avez participé à une formation donnée par ".$Event_Avion_Nom." où vous avez progressé de <b>".$Event_Avion_Nbr."</b>.<br>";
				break;
				case 34:
					$Event = "Vous avez perdu le contrôle de votre appareil suite à un incident. Un <b>".$Event_Avion_Nom."</b> a été perdu.";
				break;
				case 35:
					$Event = $Event.$Event_Date." : Grièvement blessé, vous errez quelques temps dans la région de ".$Event_Lieu_Nom."<br>";
				break;
				case 36:
					if(!$Event_Avion_Nbr)
					{
						$Event = $Event.$Event_Date." : Vous avez été ramené sain et sauf à bord d'un <b>".$Event_Avion_Nom."</b><br>";
					}
					else
					{
						$Event_Rescued_Nom = GetData("Joueur","ID",$Event_Avion_Nbr,"Nom");
						$Event = $Event.$Event_Date." : Vous avez ramené ".$Event_Rescued_Nom." sain et sauf à bord de votre <b>".$Event_Avion_Nom."</b><br>";
					}
				break;
				case 38:
					$Event = $Event.$Event_Date." : Vous avez décollé aux commandes d'un <b>".$Event_Avion_Nom."</b>, depuis votre base de ".$Event_Lieu_Nom."<br>";
				break;
				case 90:
					$Event_Avioneni_Nom = GetData("Avion","ID",$Event_Avion_Nbr,"Nom");
					$Event = $Event.$Event_Date." : Vous avez engagé un combat PvP face à un <b>".$Event_Avioneni_Nom."</b> aux commandes de votre <b>".$Event_Avion_Nom."</b>, dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
			}
			if($Event_Type < 20 and $Event_Type_txt != "")
			{
				$Tableau[$i] = $Event_Date." : Pilotant un ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom.", ".$Event_Type_txt."<br>";
				//$Event = $Event.$Event_Date." : ".$Event_Pilote_Nom.", pilotant un ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom.", ".$Event_Type_txt."<br>";
			}
			$i++;
		}
		mysql_free_result($result);
	}
	//mysql_close();
	if(is_array($Tableau))
	{	
		rsort($Tableau);
		//print_r($Tableau);
		$count = count($Tableau);
	}
	
	/*Multi-Pages
	$nombreDePages=ceil($count/25);
	if(isset($_GET['page']))
	{
		 $pageActuelle=intval($_GET['page']);	 
		 if($pageActuelle>$nombreDePages) 
		 {
			  $pageActuelle=$nombreDePages;
		 }
	}
	else 
	{
		 $pageActuelle=1;  
	}
	$premiereEntree=($pageActuelle-1)*25;
	$boucle = $premiereEntree + 25;*/
?>
<div id="esc_journal">
	<table border="0" cellspacing="2" cellpadding="5" bgcolor="#ECDDC1">
		<tr>
			<th bgcolor="tan">Journal Personnel</th>
		</tr>
		<?	for($u=0;$u<$count;$u++)
			{
		?>
		<tr>
			<td><? echo $Tableau[$u];?></td>
		</tr>
		<?
		}
		?>
	</table>
</div>
<?
	/*echo '<p align="center">Page : ';
	for($i=1; $i<=$nombreDePages; $i++)
	{
		 if($i==$pageActuelle)
		 {
			 echo ' [ '.$i.' ] '; 
		 }	
		 else
		 {
			  echo ' <a href="user_journal.php?page='.$i.'">'.$i.'</a> ';
		 }
	}
	echo '</p>';*/
}
else
{
	echo "<center><font color='#000000' size='4'>Vous devez être connecté pour accéder à cette page!</font></center>";
}
?>