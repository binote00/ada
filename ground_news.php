<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($PlayerID >0 or $OfficierID >0 or $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_em.php');
	if($Front ==99)$Front="0,1,2,3,4,5";
	$con=dbconnecti();
	$ok=mysqli_query($con,"SELECT c.*,o.Nom,o.Avancement FROM gnmh_aubedesaiglesnet3.Chat as c,Officier_em as o WHERE c.PlayerID=o.ID AND c.Front IN('$Front') AND c.Pays='$country' ORDER BY c.Date DESC LIMIT 10");
	$Objectif=mysqli_result(mysqli_query($con,"SELECT l.Nom FROM Pays as p,Lieu as l WHERE p.Co_Lieu_Mission=l.ID AND p.Pays_ID='$country' AND p.Front='$Front'"),0);
	//$resultd=mysqli_query($con,"SELECT o.Nom,o.Avancement,o.Pays,l.ID,l.Nom as lieu FROM Officier as o,Lieu as l WHERE o.Pays='$country' AND o.Front IN('$Front') AND o.Aide >0 AND o.Aide=l.ID");
	if($Front !=99)$result=mysqli_query($con,"SELECT lieu_atk1,lieu_atk2,lieu_def FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$lieu_atk1=$data['lieu_atk1'];
			$lieu_atk2=$data['lieu_atk2'];
			$lieu_def=$data['lieu_def'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	if($lieu_atk1 >0)
		$lieu_atk1=GetData("Lieu","ID",$lieu_atk1,"Nom");
	else
		$lieu_atk1="Aucun";
	if($lieu_atk2 >0)
		$lieu_atk2=GetData("Lieu","ID",$lieu_atk2,"Nom");
	else
		$lieu_atk2="Aucun";
	if($lieu_def >0)
		$lieu_def=GetData("Lieu","ID",$lieu_def,"Nom");
	else
		$lieu_def="Aucun";
	/*if($resultd)
	{
		while($datad=mysqli_fetch_array($resultd,MYSQLI_ASSOC)) 
		{
			$Grade=GetAvancement($datad['Avancement'],$datad['Pays'],false,1);
			$Msg_Help.="<br><b>".$Grade[0]." ".$datad['Nom']."</b> demande des renforts dans les environs de <b>".$datad['lieu']."</b>";
		}
		mysqli_free_result($resultd);
	}*/
	if($ok)
	{
		while($data=mysqli_fetch_array($ok,MYSQLI_ASSOC)) 
		{
			$Officier=$data['PlayerID'];
			$date=$data['Date'];
			$Officier_nom=$data['Nom'];
			$Grade=GetAvancement($data['Avancement'],$country);
			$annee=substr($date,0,4); //str_replace("2012","1940",substr($date,0,4));
			$mois=substr($date,5,2);
			$jour=substr($date,8,2);
			$Msg_Off.="<div class='panel panel-default'>
                <div class='panel panel-heading'>".$jour."-".$mois."-".$annee." : <span class='text-primary'>".$Grade[0]." ".$Officier_nom."</span> à tous les officiers</div>
                <div class='panel panel-body'><i>".nl2br($data['Msg'])."</i></div>
            </div>";
		}
		mysqli_free_result($ok);
	}
	if($Msg_Off =="")$Msg_Off="Aucun message n'a été posté par votre Etat-Major.";
	if($Objectif)$Objectif_txt="Votre Etat-Major se concentre actuellement sur <b>".$Objectif."</b></br>";
	$Objectif_txt.="<table class='table'><thead><tr><th>Objectif Prioritaire</th><th>Objectif Secondaire</th><th>Défense Prioritaire</th></tr></thead>
	<tr><td>".$lieu_atk1."</td><td>".$lieu_atk2."</td><td>".$lieu_def."</td></tr></table>";
	echo $Objectif_txt.$Msg_Help."<hr><h2>Communication de l'Etat-Major</h2>".$Msg_Off;
}
else
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
?>