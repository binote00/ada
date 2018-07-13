<?
session_start();
include_once('jfv_include.inc.php');
include_once('jfv_txt.inc.php');

//Check Joueur Valide
if(isset($_SESSION['login']) AND isset($_SESSION['pwd']))
{
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

	$PlayerID = $_SESSION['PlayerID'];
	$i = 0;
	
	/*$query_union="SELECT * FROM ((SELECT Date,Avion_Win,Unit_win,Lieu FROM Chasse WHERE Joueur_win='$PlayerID') 
	UNION (SELECT Date,Avion,Unite,Lieu FROM Attaque WHERE Joueur='$PlayerID')) 
	UNION (SELECT Date,Avion,Unite,Lieu FROM DCA WHERE Joueur='$PlayerID')) 
	UNION (SELECT Date,Avion,Unite,Lieu FROM Escorte WHERE Joueur='$PlayerID')) 
	UNION (SELECT Date,Avion_win,Unite_win,Lieu FROM Intercept WHERE Joueur_win='$PlayerID')) 
	UNION (SELECT Date,Avion,Unite,Lieu FROM Recce WHERE Joueur='$PlayerID')) 
	as t WHERE Joueur_win='$PlayerID' ORDER BY Date DESC";
	$result_union = mysql_query($query_union);
	if($result_union)
	{
		while($data = mysql_fetch_array($result_union))
		{
			$Date = $data['Date'];
			$Avion = $data['Avion_win'];
			$Unit = $data['Unit_win'];
			$Lieu = $data['Lieu'];
			$Tableau = $Tableau.$Date." Vous avez effectué une mission avec succès au sein de l'unité ".$Unit." à bord d'un ".$Avion." dans les environs de ".$Lieu."<br>";
		}
	}
	else
	{
		$Tableau = "Erreur";
	}*/

	//Victoires
	dbconnect();
	$query_vic = "SELECT * FROM Chasse WHERE Joueur_win='$PlayerID' OR Pilote_loss='$PlayerID' ORDER BY Date DESC";
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
			else
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
			if($Arme_id > 49)
			{
				$Arme = "à l'aide d'une bombe";
			}
			else
			{
				$Arme = "d'une rafale de ".GetData("Armes","ID",$Arme_id,"Nom");
			}
			$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez détruit un <b>".$Nom."</b> ".$Arme.", dans les environs de ".$Lieu.".<br>";
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
			$Escorte_nom = GetData("Avion","ID",$data['Escorte'],"Nom");
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
			$Tableau[$i] = $Date." : Pilotant un ".$Avion." du ".$Unit.", vous avez photographié un <b>".$Nom."</b> dans les environs de ".$Lieu.".<br>";
			$i++;
		}
	}
	
	//Journal
	dbconnect();
	$query="SELECT * FROM Events WHERE PlayerID='$PlayerID' ORDER BY Date DESC";
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
					$Event_Type_txt = "Vous avez engagé le combat face à un ".$Event_Avioneni_Nom;
				break;
				case 3:
					//$Event_Type_txt = "Vous avez été abattu.";
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
				case 22:
					$Tableau[$i] = $Event_Date." : Vous avez détruit un <b>".$Event_Avion_Nom."</b> sur l'aérodrome de ".$Event_Lieu_Nom.".<br>";
				break;
				case 30:
					$Event_Unite_Nom = GetData("Unit","ID",$Event_Unit,"Nom");
					$Event_Medal = GetMedal_Name($country, $Event_Avion_Nbr);
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
					$Event_Unite_Nom = GetData("Unit","ID",$Event_Unit,"Nom");
					$Event_Promo = GetAvancement($Event_Avion_Nbr, $country);
					$Tableau[$i] = $Event_Date." : Vous avez été promu au grade de ".$Event_Promo[0]."<br>";
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
	
	//Multi-Pages
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
	$boucle = $premiereEntree + 25;
?>
<div id="esc_journal">
	<table border="0" cellspacing="2" cellpadding="5" bgcolor="#ECDDC1">
		<tr>
			<th bgcolor="tan">Journal Personnel</th>
		</tr>
		<?	for($u=$premiereEntree;$u<$boucle;$u++)
			{
		?>
		<tr>
			<td><? echo str_replace("2012","1940",$Tableau[$u]);?></td>
		</tr>
		<?
		}
		?>
	</table>
</div>
<?
	echo '<p align="center">Page : ';
	for($i=1; $i<=$nombreDePages; $i++)
	{
		 if($i==$pageActuelle)
		 {
			 echo ' [ '.$i.' ] '; 
		 }	
		 else
		 {
			  echo ' <a href="user_journal_test.php?page='.$i.'">'.$i.'</a> ';
		 }
	}
	echo '</p>';
	
	//Time
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $start), 4);
	echo 'Page generated in '.$total_time.' seconds.';
}
else
{
	echo "<center><font color='#000000' size='4'>Vous devez être connecté pour accéder à cette page!</font></center>";
}
?>