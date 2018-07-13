<?php
require_once('./jfv_inc_sessions.php');
//$OfficierEMID=$_SESSION['Officier_em'];
if(1 ==2)//$OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	$CT=Insec($_POST['ct']);
	$bons=true;
	$con=dbconnecti();
	//$OfficierEM=mysqli_result(mysqli_query($con,"SELECT Officier_em FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
	$result=mysqli_query($con,"SELECT Pays,Front,Credits FROM Officier_em WHERE ID='$OfficierEMID'");
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Pays=$data['Pays'];
			$Front=$data['Front'];
			$Credits=$data['Credits'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	/*$resultem=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Pool_ouvriers FROM Pays WHERE Pays_ID='$Pays' AND Front='$Front'");
	if($resultem)
	{
		while($dataem=mysqli_fetch_array($resultem,MYSQLI_ASSOC))
		{
			$Commandant=$dataem['Commandant'];
			$Officier_Adjoint=$dataem['Adjoint_EM'];
			$Officier_EM=$dataem['Officier_EM'];
			$Bons_actu=$dataem['Pool_ouvriers'];
		}
		mysqli_free_result($resultem);
		unset($dataem);
		if($Commandant >0 and $OfficierEM ==$Commandant)$bons=false;
		if($Officier_Adjoint >0 and $OfficierEM ==$Officier_Adjoint)$bons=false;
		if($Officier_EM >0 and $OfficierEM ==$Officier_EM)$bons=false;
	}*/
	if($Credits >=$CT and $Pays and $bons)
	{
		if($Bons_actu >=255)
			$mes="Le service vous remercie, mais les souscriptions ont été suffisantes pour aujourd'hui!";
		else
		{
			$reset=mysqli_query($con,"UPDATE Pays SET Pool_ouvriers=Pool_ouvriers+10 WHERE Pays_ID='$Pays' AND Front='$Front'");
			$reseto=mysqli_query($con,"UPDATE Officier_em SET Avancement=Avancement+10,Reputation=Reputation+10,Note=Note+2 WHERE ID='$OfficierEMID'");
			mysqli_close($con);
			UpdateData("Officier_em","Credits",-$CT,"ID",$OfficierEMID);
			$mes="Vous mettez votre réputation au service de la vente des bons de guerre.";
		}
		$titre='Bons de guerre';
		$img="<img src='images/bons".$Pays.".jpg'>";
		include_once('./default.php');
	}
	else
		echo "<div class='alert-danger'>Action impossible par manque de temps!</div>";
}