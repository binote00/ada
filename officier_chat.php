<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_msg.inc.php');
	$Msg=htmlspecialchars(Insec($_POST['officier_msg']));
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0 AND $Msg)
	{
		$Officier = Insec($_POST['Officier']);
		$country = Insec($_POST['Pays']);
		$Front = Insec($_POST['Front']);
		$Alerte = Insec($_POST['Alerte']);	
		include_once('./menu_em.php');
		$date=date('Y-m-d G:i');
		$con=dbconnecti(3);
		$ok=mysqli_query($con,"INSERT INTO Chat (PlayerID, Pays, Front, Date, Msg) VALUES ('$Officier','$country','$Front','$date','$Msg')");
		mysqli_close($con);
		if($ok)
		{
			if(!$_SESSION['Off_Chat'] and strlen($Msg) >50)
				UpdateCarac($OfficierEMID,"Note",1,"Officier_em");
			$_SESSION['Off_Chat']=true;
			echo '<div class="alert alert-success">Message posté avec succès!</div>';
			echo "<p><a class='btn btn-default' title='Retour' href='index.php?view=ground_news'>Retour</a></p>";
		}
		else
		{
			echo '<div class="alert alert-danger">Erreur d\'envoi de message!</div>';
			echo "<p><a class='btn btn-default' title='Retour' href='index.php?view=ground_news'>Retour</a></p>";
		}
	}
	else
		echo "Tsss";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";