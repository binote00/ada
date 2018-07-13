<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID = $_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country = $_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');		
	//$Tab = Insec($_GET['tab']);
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin)
	{	
		$query="SELECT * FROM Events_em WHERE Event_Type IN (75,114,120,121,122,123,124,130,131,135,136,175,176,177) ORDER BY ID DESC LIMIT 50";
		$con=dbconnecti(4);
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($Classement=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$Event_Type_txt="";
				$Event_Date = substr($Classement['Date'],0,16);
				$Event_Type = $Classement['Event_Type'];
				$Event_Lieu = $Classement['Lieu'];
				$Event_PlayerID = $Classement['PlayerID'];
				$Event_Avion = $Classement['Avion'];
				$Event_Avion_Nbr = $Classement['Avion_Nbr'];
				$Event_Pilote_eni = $Classement['Pilote_eni'];
				if($Event_Type ==135)
					$Event_Pilote_Nom=GetData("Officier","ID",$Event_PlayerID,"Nom");
				elseif($Event_Type ==136)
					$Event_Pilote_Nom=GetData("Officier_em","ID",$Event_PlayerID,"Nom");
				elseif($Event_Type !=75)
				{
					$Unite_Nom=GetData("Unit","ID",$Classement['Unit'],"Nom");
					$Event_Pilote_Nom=GetData("Pilote","ID",$Event_PlayerID,"Nom");
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event_Avion_eni_Nom=GetData("Avion","ID",$Event_Avion_Nbr,"Nom");
				}				
				$Event_Lieu_Nom=GetData("Lieu","ID",$Event_Lieu,"Nom");					
				switch($Event_Type)
				{
					case 31:
						$Event_Unite_Dest_Nom=GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
						if($Unite == $Event_Avion_Nbr)
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été transféré du ".$Unite_Nom." vers le ".$Event_Unite_Dest_Nom.", basé à ".$Event_Lieu_Nom."<br>";
						else
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été transféré du ".$Event_Unite_Dest_Nom." vers le ".$Unite_Nom.", basé à ".$Event_Lieu_Nom."<br>";
					break;
					case 75:
						if($Event_Avion ==1)
							$Sabotage ="endommagé l'aérodrome";
						elseif($Event_Avion ==2)
							$Sabotage ="fait sauter le pont";
						elseif($Event_Avion ==3)
							$Sabotage ="saboté une usine";
						elseif($Event_Avion ==4)
							$Sabotage ="saboté une voie de chemin de fer";
						elseif($Event_Avion ==5)
							$Sabotage ="saboté un radar";
						elseif($Event_Avion ==6)
							$Sabotage ="détruit une batterie de DCA";
						elseif($Event_Avion ==7)
							$Sabotage ="endommagé les fortifications";
						$Event.=$Event_Date." : Des troupes d'assaut ont ".$Sabotage." aux alentours de la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 114:
						$Event.=$Event_Date." : Une attaque a diminué le stock de munitions du dépôt de ".$Event_Lieu_Nom."<br>";
					break;
					case 120:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a saboté <b>".$Event_Avion_Nbr." ".$Event_Avion_Nom."</b>, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 121:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a saboté <b>".$Event_Avion_Nbr." litres de carburant</b>, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 122:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a saboté <b>".$Event_Avion_Nbr." munitions</b>, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 123:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a saboté <b>".$Event_Avion_Nbr." canon de DCA</b>, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 124:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a volé de l'équipement, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 135: case 136:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a brûlé le dépôt de <b>".$Event_Lieu_Nom."</b><br>";
					break;
					case 175:
						$Event.=$Event_Date." : Des troupes d'assaut ont été repoussées par la garnison aux alentours de la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 176:
						$Event.=$Event_Date." : Des troupes d'assaut ont été anéanties par la garnison aux alentours de la base de ".$Event_Lieu_Nom."<br>";
					break;
				}
			}
			mysqli_free_result($result);
		}	
		echo "<h2>Journal des opérations de sabotage</h2><div style='overflow:auto; height: 400px;'><div class='alert alert-warning'>".$Event."</div></div>";
	}
	else
		PrintNoAccess($country,1,4);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>