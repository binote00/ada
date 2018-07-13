<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Battle=Insec($_POST['Battle']);
	$Faction=Insec($_POST['Camp']);
	$Avion=Insec($_POST['Avion']);
	if($Battle and $Faction and $Avion)
	{
		include_once('./jfv_inc_pvp.php');
		$Lieu=GetCiblePVP($Battle);
		if($_SESSION['Pilote_pvp'] >0 and $_SESSION['Distance'] ==0)
		{
			if($Faction ==1)
				$queryr="UPDATE gnmh_aubedesaiglesnet2.Battle_score as b,Avion as a SET b.Pts_Bat_Axe=b.Pts_Bat_Axe-(a.Rating*5) WHERE a.ID='$Avion' AND b.ID='$Battle'";
			else
				$queryr="UPDATE gnmh_aubedesaiglesnet2.Battle_score as b,Avion as a SET b.Pts_Bat_Allies=b.Pts_Bat_Allies-(a.Rating*5) WHERE a.ID='$Avion' AND b.ID='$Battle'";
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Pilote_PVP SET Front_sandbox='$Battle',Pays='$Faction',Avion_Sandbox='$Avion' WHERE ID='".$_SESSION['Pilote_pvp']."'");
			$reset2=mysqli_query($con,$queryr);
			mysqli_close($con);
			echo "<h1>Inscription à la bataille</h1>Inscription de votre pilote validée!<br>Il pilotera un ".GetAvionIcon($Avion,0,0,0,$Front,"",0,true)." lors de la prochaine bataille.";
		}
		elseif($_SESSION['Officier_pvp'] >0)
		{
			$Officier_pvp=$_SESSION['Officier_pvp'];
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Pays,Type,mobile,Flak,Portee,HP FROM Cible WHERE ID='$Avion'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$country=$data['Pays'];
					$Type=$data['Type'];
					$mobile=$data['mobile'];
					$Flak=$data['Flak'];
					$Portee=$data['Portee'];
					if($mobile ==5)
					{
						$HP=$data['HP'];
						$Placement=8;
						$Experience=250;
						$Veh_Nbr=1;
					}
					else
					{
						$Placement=0;
						$Experience=50;
						$Veh_Nbr=GetMaxVeh($Type,$mobile,$Flak,500000);
					}
				}
				mysqli_free_result($result);
			}
			$query2="INSERT INTO Regiment_PVP (Officier_ID,Pays,Front,Vehicule_ID,Lieu_ID,Vehicule_Nbr,Placement,HP,Camouflage,Experience,Moral,Distance,Move)";
			$query2.="VALUES ('$Officier_pvp','$country','$Battle','$Avion','$Lieu','$Veh_Nbr','$Placement','$HP',1,250,100,'$Portee',1)";
			if($Faction ==1)
				$queryr="UPDATE gnmh_aubedesaiglesnet2.Battle_score as b,Cible as c SET b.Pts_Bat_Axe=b.Pts_Bat_Axe-c.Reput WHERE c.ID='$Avion' AND b.ID='$Battle'";
			else
				$queryr="UPDATE gnmh_aubedesaiglesnet2.Battle_score as b,Cible as c SET b.Pts_Bat_Allies=b.Pts_Bat_Allies-c.Reput WHERE c.ID='$Avion' AND b.ID='$Battle'";
			$con=dbconnecti();
			$ok2=mysqli_query($con,$query2);
			$ins_id=mysqli_insert_id($con);
			$reset=mysqli_query($con,"UPDATE Officier_PVP SET Front='$Battle',Pays='$Faction',Division='$ins_id',Note='$Avion' WHERE ID='".$_SESSION['Officier_pvp']."'");
			$reset2=mysqli_query($con,$queryr);
			mysqli_close($con);
			echo "<h1>Inscription à la bataille</h1>";
			if($ok2 and $reset and $reset2 and $ins_id)
			{
				if($Faction ==1)
					UpdateData("Battle_score","Axe_inscrits",1,"ID",$Battle);
				else
					UpdateData("Battle_score","Allies_inscrits",1,"ID",$Battle);
				echo "Inscription de votre officier validée!<br>Il commandera une compagnie de ".GetVehiculeIcon($Avion,0,0,0,$Front)." lors de la prochaine bataille.";
			}
			else
				echo "Erreur lors de l'inscription!";
		}
		else
			echo "<p>Votre personnage n'est pas prêt!</p>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>