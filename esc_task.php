<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA)
	{
		$Task=Insec($_POST['task']);
		$Objectif=Insec($_POST['cible']);
		$Flight=Insec($_POST['avions']);
		$Avion_1=Insec($_POST['a1']);
		$Avion_2=Insec($_POST['a2']);
		$Avion_3=Insec($_POST['a3']);
		$ia_pilots=Insec($_POST['ia_pilots']);
		if($Objectif >0 and $Flight >0 and $ia_pilots)
		{
			if($Task >0)
			{
				if($Flight ==1)
					$AvionDispo=$Avion_1;
				elseif($Flight ==2)
					$AvionDispo=$Avion_2;
				else
					$AvionDispo=$Avion_3;
				$Unite=GetData("Pilote","ID",$PlayerID,"Unit");
				$Avion=GetData("Unit","ID",$Unite,"Avion".$Flight);
				$Avion_Engine=GetData("Avion","ID",$Avion,"Engine");
				$Avion_Fuel=GetData("Moteur","ID",$Avion_Engine,"Carburant");
				$Unit_Stock_Fuel=GetData("Unit","ID",$Unite,"Stock_Essence_".$Avion_Fuel);
				$AvionDispoMax=$Unit_Stock_Fuel/500;
				if($AvionDispo >$AvionDispoMax)$AvionDispo=$AvionDispoMax;
				if($AvionDispo >4)$AvionDispo=4;
				if($AvionDispo < count($ia_pilots))
				{
					$mes.="<br>Il n'y a pas suffisamment d'avions disponibles pour le nombre de pilotes sélectionnés. Les pilotes excédentaires resteront à la base.";
					$ia_pilots=array_slice($ia_pilots,0,$AvionDispo,true);
				}
				if(is_array($ia_pilots))
				{
					$Formation=count($ia_pilots);
					$ia_pilotes=implode(',',$ia_pilots);
					if($Formation >0 and $ia_pilotes)
					{
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible='$Objectif',Missions=Missions+1,Avion='$Avion',Task='$Task' WHERE ID IN (".$ia_pilotes.")") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esc_task-cible-ia');
						mysqli_close($con);
						$mes.="<br><b>".$Formation."</b> pilotes sont assignés à la mission de <b>".GetTask($Task)."</b> au-dessus de ".GetData("Lieu","ID",$Objectif,"Nom");
					}
				}
			}
			else
			{
				$Formation=count($ia_pilots);
				$ia_pilotes=implode(',',$ia_pilots);
				if($Formation >0 and $ia_pilotes)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Couverture=0,Escorte=0,Couverture_Nuit=0,Avion=0,Task=0 WHERE ID IN (".$ia_pilotes.")") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : esc_task-cible-ia');
					mysqli_close($con);
					$mes.="<br><b>".$Formation."</b> pilotes sont rappelés à la base";
				}
			}
			MoveCredits($PlayerID,5,-2);
		}
		else
			$mes="Une erreur est survenue!";
		echo "<h1>Assignation de mission</h1><p>".$mes."</p><a href='index.php?view=esc_missions' class='btn btn-default' title='Retour'>Retour</a>";
	}
	else
	{
		echo "<h6>Vous la reverrez un jour votre escadrille...</h6>";
		echo "<img src='images/unites".$country.".jpg'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>