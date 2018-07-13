<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Poste = Insec($_POST['poste']);
	$country = Insec($_POST['country']);
	$Front = Insec($_POST['Front']);
	$Off = Insec($_POST['off']);
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0 and $Off ==$OfficierEMID)
	{
        include_once('./jfv_msg.inc.php');
        include_once('./jfv_txt.inc.php');
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Front,Reputation,Avancement FROM Officier_em WHERE ID='$OfficierEMID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Nom=$data['Nom'];
				$Front_off=$data['Front'];
				$Reput=$data['Reputation'];
				$Avancement=$data['Avancement'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		SetData("Officier_em","Postuler",$Poste,"ID",$OfficierEMID);
		if($Front !=$Front_off)
			$Front_chg_txt='<br>Changement de front!';
		$txt="<b>".$Nom."</b> (ID : ".$OfficierEMID.") postule pour le poste de <b>".GetPosteEM($Poste)."</b> de la nation <b>".GetPays($country)."</b> sur le front <b>".GetFront($Front).$Front_chg_txt."</b>";
		mail('binote@hotmail.com','Aube des Aigles: Un officier EM postule',$txt);
		$titre="Postuler à une fonction d'état-major";
		$mes="<p>Votre demande est envoyée à un administrateur. Vous recevrez une réponse sous peu.</p>";
		$img="<img src='images/poste".$country.".jpg'>";
		include_once('./default.php');
	}
	else
		echo "<h1>Votre personnage n'est pas autorisé à postuler pour ce poste!</h1>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';