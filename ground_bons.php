<?php
require_once('./jfv_inc_sessions.php');
//$OfficierEMID=$_SESSION['Officier_em'];
if(1 ==2)//$OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./menu_actus.php');
	$bons=true;
	$con=dbconnecti();
	$resulte=mysqli_query($con,"SELECT Pays,Credits,Front FROM Officier_em WHERE ID='$OfficierEMID'");
	if($resulte)
	{
		while($datae=mysqli_fetch_array($resulte,MYSQLI_ASSOC))
		{
			$Credits=$datae['Credits'];
			$Front=$datae['Front'];
			$Pays=$datae['Pays'];
		}
		mysqli_free_result($resulte);
	}
	//$OfficierEM=mysqli_result(mysqli_query($con,"SELECT Officier_em FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
	$resultem=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Pool_ouvriers FROM Pays WHERE Pays_ID='$Pays' AND Front='$Front'");
	mysqli_close($con);
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
		/*if($Commandant >0 and $OfficierEM ==$Commandant)$bons=false;
		if($Officier_Adjoint >0 and $OfficierEM ==$Officier_Adjoint)$bons=false;
		if($Officier_EM >0 and $OfficierEM ==$Officier_EM)$bons=false;*/
		if($Commandant >0 and $OfficierEMID ==$Commandant)
			$bons_ct=4;
		elseif($Officier_EM >0 and $OfficierEMID ==$Officier_EM)
			$bons_ct=2;
		else
			$bons_ct=8;
	}
	echo "<h1></h1><h2>Bons de guerre</h2><p><img src='images/bons".$Pays.".jpg'></p>";
	if($Credits >=$bons_ct and $bons)
	{
		if($Bons_actu >=255)
			echo "<div class='alert-danger'>Les souscriptions ont été suffisantes pour aujourd'hui!</div>";
		else
			echo "<form action='index.php?view=ground_bons1' method='post'><input type='hidden' name='ct' value='".$bons_ct."'>
			<img src='/images/CT".$bons_ct.".png' title='Coût de cette action en Crédits Temps'><input type='Submit' value='Souscrire' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	else
		echo "<div class='alert-danger'>Vous n'avez pas le temps pour cela!</div>";
	echo "<div class='alert alert-warning'>Les bons de guerre permettent à l'état-major de disposer de moyens supplémentaires pour améliorer les infrastructures et la production des usines.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";