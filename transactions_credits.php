<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$i=0;
	//Journal
	$con=dbconnecti(2);
	$result=mysqli_query($con,"SELECT `Date`,Credits,Action FROM Porte_Monnaie WHERE PlayerID='$PlayerID' ORDER BY ID DESC LIMIT 20");
	mysqli_close($con);
	if($result)
	{
		while($Classement=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			$afficher=true;
			$Event_Date=substr($Classement['Date'],0,16);
			$Event_Credits=$Classement['Credits'];
			$Event_Action=$Classement['Action'];
			switch($Event_Action)
			{
				//Action : 1=Mission,2=Temps Libre,3=Gestion,4=Décoration,5=Repos,6=Training,7=Mission Histo,
				//8=Mission annulée,9=crash,10=garage,11=Mutation,12=Avion perso
				case 1:
					$Event_Type_txt="Mission";
				break;
				case 2:
					$Event_Type_txt="Temps libre";
				break;
				case 3:
					$Event_Type_txt="Action de gestion";
				break;
				case 4:
					$Event_Type_txt="Décoration";
				break;
				case 5:
					$Event_Type_txt="Repos";
				break;
				case 6:
					$Event_Type_txt="Training";
				break;
				case 7:
					$Event_Type_txt="Bombardement strat";
				break;
				case 8:
					$Event_Type_txt="Mission annulée";
				break;
				case 9:
					$Event_Type_txt="Crash";
				break;
				case 10:
					$Event_Type_txt="Avion perso";
				break;
				case 11:
					$Event_Type_txt="Demande de mutation";
				break;
				case 12:
					$Event_Type_txt="Avion perso";
				break;
				case 13:
					$Event_Type_txt="Equipement";
				break;
				case 14:
					$Event_Type_txt="Postuler à un état-major";
				break;
				case 15:
					$Event_Type_txt="Espionnage";
				break;
				case 90:
					$Event_Type_txt="Avancement";
					if($PlayerID !=1)
						$afficher=false;
				break;
				case 91:
					$Event_Type_txt="Reput";
					if($PlayerID !=1)
						$afficher=false;
				break;
				case 99:
					$Event_Type_txt="CT du jour";
				break;
				default:
					$Event_Type_txt="Debug";
				break;
			}
			if($afficher)
				$Tableau[$i]=$Event_Date." : <b>".$Event_Credits."</b> Credits Temps lors d'un(e) ".$Event_Type_txt."<br>";
			$i++;
		}
		mysqli_free_result($result);
	}
	if(is_array($Tableau))
	{	
		rsort($Tableau);
		$count=count($Tableau);
	}
	for($u=0;$u<$count;$u++)
	{
		$output.=$Tableau[$u];
	}
	$output_hover="<h3>Transactions Credits</h3>".$output;
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>
