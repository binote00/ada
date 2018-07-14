<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if(isset($_SESSION['AccountID']) AND $OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$OfficierID=Insec($_GET['id']);
	if(!$OfficierID)
	{
		$OfficierID=$_SESSION['Officier'];
		$Pays=$_SESSION['country'];
	}
	else
		$Pays=GetData("Officier","ID",$OfficierID,"Pays");
	echo "<h1>Pertes </h1><table class='table table-striped'>		
		<thead><tr><th>Véhicule</th><th>Production</th><th>Pertes Totales Nation</th><th>Pertes Totales Aérien</th><th>Pertes Totales Combats</th><th>Pertes Officier</th></tr></thead>";	
	$con=dbconnecti();
	$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	$resultreg=mysqli_query($con,"SELECT ID FROM Regiment WHERE Officier_ID='$OfficierID'");
	$result=mysqli_query($con,"SELECT ID,Production,Retrait FROM Cible WHERE Pays IN(0,'$Pays') AND `Date` <'".$Date_Campagne."' AND Production>0 ORDER BY `Date` DESC");
	mysqli_close($con);
	if($resultreg)
	{
		while($datareg=mysqli_fetch_array($resultreg))
		{
			$Regs[]=$datareg['ID']; 
		}
		mysqli_free_result($resultreg);
	}
	$Regiments=implode(',',$Regs);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Perdus=0;
			$Perdus2=0;
			$Pertes=0;
			$Pertes2=0;
			//$Pertes3=0;
			$Total=0;
			$Veh=$data['ID'];		
			$con=dbconnecti(4);
			$Perdus=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$Veh'"),0);
			$Perdus2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$Veh'"),0);
			//$Pertes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Unit IN (".$Regiments.") AND Pilote_eni='$Veh'"),0);
			$Pertes=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (401,405,415,605,615) AND Unit IN (".$Regiments.") AND Avion='$Veh'"),0);
			$Pertes2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,404,420) AND Unit IN (".$Regiments.") AND Avion='$Veh' AND PlayerID='$OfficierID'"),0);
			//$Pertes3=mysqli_result(mysqli_query($con,"SELECT SUM(Kills) FROM gnmh_aubedesaiglesnet.Ground_Cbt WHERE Veh_b='$Veh' AND Reg_b IN(".$Regiments.")"),0);
			mysqli_close($con);				
			$Total_Perdus=$Perdus+$Perdus2;
			$Total=$Pertes+$Pertes2; //+$Pertes3;
			if($Total >0)
			{
				if($Total >=$data['Production']/5)
					$Total="<span class='text-danger'>".$Total."</span>";
				elseif($Total >=$data['Production']/20 and $Total>($Total_Perdus/2))
					$Total="<span class='text-danger'>".$Total."</span>";
				if($Total_Perdus >=$data['Production']/1.5)
					$Total_Perdus="<span class='text-danger'>".$Total_Perdus."</span>";
				elseif($Total_Perdus >=$data['Production']/2)
					$Total_Perdus="<span class='text-warning'>".$Total_Perdus."</span>";
				/*if($data['Retrait'] <$Date_Campagne)
					$Total_Perdus="<span class='text-success'>".$Total_Perdus."</span>";*/
				if($Premium)
					echo "<tr><th>".GetVehiculeIcon($Veh,$Pays,0,0,$Front)."</th><th>".$data['Production']."</th><th>".$Total_Perdus."</th><th>".$Perdus."</th><th>".$Perdus2."</th><th>".$Total."</th></tr>";
				else
					echo "<tr><th>".GetVehiculeIcon($Veh,$Pays,0,0,$Front)."</th><th><img src='images/premium50.png' title='Information Premium'></th><th><img src='images/premium50.png' title='Information Premium'></th><th>".$Total."</th><th>".$Pertes."</th><th>".$Pertes2."</th></tr>";
			}
		}
		mysqli_free_result($result);
	}
	else
		echo "Erreur";
	echo "</table><div class='alert alert-warning'>Si les pertes totales de l'officier sont supérieures à 20% de la production ou supérieures à la moitié des pertes totales de la nation tout en étant supérieures à 5% de la production, le véhicule est inaccessible (couleur rouge). Votre officier ne peut donc plus créer ou renforcer ses compagnies avec ce véhicule.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>