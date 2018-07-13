<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Pilote=Insec($_POST['id']);
	$Unite=Insec($_POST['unit']);
	if($Pilote >0 and $Unite >0)
	{
		include_once('./jfv_inc_em.php');
		$con=dbconnecti();
		$reset=mysqli_query($con,"UPDATE Pilote_IA SET Avion=0,Cible=0,Couverture=0,Couverture_Nuit=0,Escorte=0,Alt=0,Task=0,Unit='$Unite' WHERE ID='$Pilote'");
        $log=mysqli_query($con,"INSERT INTO gnmh_aubedesaiglesnet2.mutations VALUES ('',$OfficierEMID,$Pilote,$Unite,NOW())");
		mysqli_close($con);
		UpdateData("Officier_em","Credits",-1,"ID",$OfficierEMID);
		$titre="Mutation";
		$mes="Le pilote <b>".GetData("Pilote_IA","ID",$Pilote,"Nom")."</b> a été muté dans le ".Afficher_Icone($Unite,$country)."!";
		$img="<img src='images/pilotes".$country.".jpg'>";
		if($GHQ)
			$menu="<br><form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$Unite."'><input type='Submit' value='Retour unité' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		$menu.="<br><a href='index.php?view=rapports' class='btn btn-default' title='Retour'>Retour menu</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>