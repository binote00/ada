<?
/*require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');

$PlayerID = $_SESSION['PlayerID'];
if($PlayerID > 0)
{
	$country = $_SESSION['country'];
	if(!$Front)
		$Front=GetData("Pilote","ID",$PlayerID,"Front");
	//Chat Off
	$EM = "99999".$country;
	$EM_front = "99".$Front."99".$country;
	$con=dbconnecti(3);
	$ok2=mysqli_query($con,"SELECT PlayerID,Date,Unit,Msg FROM Chat WHERE (Unit='$EM_front' OR Unit='$EM') ORDER BY ID DESC LIMIT 20");
	mysqli_close($con);
	if($ok2)
	{
		while ($data = mysqli_fetch_array($ok2,MYSQLI_ASSOC)) 
		{
			$Officier_txt = $data['PlayerID'];
			$date = $data['Date'];
			//GetData Base
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Avancement FROM Officier_em WHERE ID='$Officier_txt'");
			mysqli_close($con);
			if($result)
			{
				while($data2=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Officier_nom=$data2['Nom'];
					$Avancement=$data2['Avancement'];
				}
				mysqli_free_result($result);
			}
			$Grade = GetAvancement($Avancement,$country);
			$annee = substr($date,0,4);
			$mois = substr($date,5,2);
			$jour = substr($date,8,2);
			//if($data['Unit'] == $EM)
				$Msg_Off .= "<p>".$jour."-".$mois."-".$annee.". Du <b>".$Grade[0]." ".$Officier_nom." (Officier d'Etat-Major)</b> à tous les pilotes:<p><i>".nl2br($data['Msg'])."</i></p></p>";
			//else $Msg_Unit .= "<p>".$jour."-".$mois."-".$annee.". Du <b>".$Grade[0]." ".$Officier_nom." (Staff d'escadrille)</b> à l'escadrille:<p><i>".nl2br($data['Msg'])."</i></p></p>";
		}
		mysqli_free_result($ok2);
	}
	//Output
	if($Msg_Off =="")
		$Msg_Off = "Aucun message n'a été posté par votre Etat-Major.";
	echo "<h1>Ordre du jour</h1>
		<table class='table'>
			<thead><tr><th>Communication de l'Etat-Major</th></tr></thead>
			<tr><td align='left' valign='top'>".$Msg_Off."</td></tr>
		</table>";
}
else
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";*/
?>