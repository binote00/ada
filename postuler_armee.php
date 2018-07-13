<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Poste = Insec($_POST['poste']);
	$country = Insec($_POST['country']);
	$Front = Insec($_POST['Front']);
	$Off = Insec($_POST['off']);
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0 and $Off ==$OfficierEMID and $Poste ==20)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Front,Reputation,Avancement FROM Officier_em WHERE ID='$OfficierEMID'");
		$resulta=mysqli_query($con,"SELECT a.ID,a.Nom,l.Nom as Ville FROM Armee as a,Lieu as l WHERE l.ID=a.Base AND a.Pays='$country' AND a.Front='$Front' AND ISNULL(a.Cdt)");
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
		if($resulta)
		{
			while($dataa=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
			{
				$armee_list.="<tr><td>".$dataa['Nom']."</td><td>".$dataa['Ville']."</td><td><Input type='Radio' name='Action' value='".$dataa['ID']."'></td></tr>";
			}
			mysqli_free_result($resulta);
			unset($dataa);
		}
		if($armee_list)
			$table_armee="<form action='index.php?view=postuler_armee1' method='post'><input type='hidden' name='front' value='".$Front."'>
			<table class='table table-striped'><thead><tr><th>Armée</th><th>Base</th><th>Postuler</th></tr></thead>".$armee_list."</table>
			<input type='Submit' value='Postuler' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
		else
			$table_armee="Aucune armée n'est disponible sur ce front!";
		$titre='Postuler';
		$mes='<h2>Liste des armées sur votre front</h2>'.$table_armee;
		$img="<img src='images/poste".$country.".jpg'>";
		include_once('./default.php');
	}
	else
		echo "<h1>Votre personnage n'est pas autorisé à postuler pour ce poste!</h1>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';