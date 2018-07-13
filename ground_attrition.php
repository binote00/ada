<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_inc_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Terre or $OfficierEMID ==$Adjoint_Terre or $GHQ or $Admin or $Armee >0)
	{
		include_once('./jfv_ground.inc.php');
		echo "<h1>Attrition des Compagnies EM</h1>
		<div class='alert alert-warning'>Les unités terrestres se trouvant sur un lieu revendiqué par l'ennemi subiront une perte d'une unité par jour, sauf dans les cas suivants :
		<br>- La zone où se trouve l'unité est revendiquée par la faction de l'unité.
		<br>- Le véhicule de commandement de la division de l'unité se trouve sur le même lieu.
		<br>- L'unité a reçu un ravitaillement aérien dans les 24h qui précèdent.</div>";
		$Reg_Atr=false;
		$Axe=array(1,6,9,15,18,19,20,24);
		$Allies=array(2,3,4,5,7,8,10,35,36);
		/*if($Admin)
			$query="SELECT r.ID as Reg,r.Pays as Pays_Reg,r.Division,r.Lieu_ID,r.Placement,r.Ravit,p.Faction,l.Flag,l.Flag_Air,l.Flag_Route,l.Flag_Gare,l.Flag_Pont,l.Flag_Port,l.Flag_Usine,l.Flag_Radar,l.Flag_Plage,l.Nom as Lieu_Nom
			FROM Regiment_IA as r,Lieu as l,Cible as c,Pays as p WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays=p.ID
			AND c.mobile NOT IN(4,5) AND l.Flag<>r.Pays AND r.Placement<>8 AND r.Vehicule_Nbr>0 ORDER BY l.Nom ASC";
		else*/if($GHQ)
			$query="SELECT r.ID as Reg,r.Pays as Pays_Reg,r.Division,r.Lieu_ID,r.Placement,r.Ravit,p.Faction,l.Flag,l.Flag_Air,l.Flag_Route,l.Flag_Gare,l.Flag_Pont,l.Flag_Port,l.Flag_Usine,l.Flag_Radar,l.Flag_Plage,l.Nom as Lieu_Nom
			FROM Regiment_IA as r,Lieu as l,Cible as c,Pays as p WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays=p.ID
			AND r.Pays='$country' AND c.mobile NOT IN(4,5) AND l.Flag<>r.Pays AND r.Placement<>8 AND r.Vehicule_Nbr>0 ORDER BY l.Nom ASC";
		elseif($Armee >0)
			$query="SELECT r.ID as Reg,r.Pays as Pays_Reg,r.Division,r.Lieu_ID,r.Placement,r.Ravit,p.Faction,l.Flag,l.Flag_Air,l.Flag_Route,l.Flag_Gare,l.Flag_Pont,l.Flag_Port,l.Flag_Usine,l.Flag_Radar,l.Flag_Plage,l.Nom as Lieu_Nom
			FROM Regiment_IA as r,Lieu as l,Cible as c,Pays as p,Division as d WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays=p.ID AND r.Division=d.ID AND d.Armee='$Armee'
			AND r.Pays='$country' AND r.Front='$Front' AND c.mobile NOT IN(4,5) AND l.Flag<>r.Pays AND r.Placement<>8 AND r.Vehicule_Nbr>0 ORDER BY l.Nom ASC";
		else
			$query="SELECT r.ID as Reg,r.Pays as Pays_Reg,r.Division,r.Lieu_ID,r.Placement,r.Ravit,p.Faction,l.Flag,l.Flag_Air,l.Flag_Route,l.Flag_Gare,l.Flag_Pont,l.Flag_Port,l.Flag_Usine,l.Flag_Radar,l.Flag_Plage,l.Nom as Lieu_Nom
			FROM Regiment_IA as r,Lieu as l,Cible as c,Pays as p WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays=p.ID
			AND r.Pays='$country' AND r.Front='$Front' AND c.mobile NOT IN(4,5) AND l.Flag<>r.Pays AND r.Placement<>8 AND r.Vehicule_Nbr>0 ORDER BY l.Nom ASC";
		$con=dbconnecti();
		$resultat=mysqli_query($con,$query);
		//mysqli_close($con);
		if($resultat)
		{
			while($dataat=mysqli_fetch_array($resultat,MYSQLI_ASSOC)) 
			{
				$Attrition=true;
				$Lieu_ID=$dataat['Lieu_ID'];
				$Pays_Reg=$dataat['Pays_Reg'];
				if($dataat['Placement'] ==1)
					$Flag_Zone=$dataat['Flag_Air'];
				elseif($dataat['Placement'] ==2)
					$Flag_Zone=$dataat['Flag_Route'];
				elseif($dataat['Placement'] ==3)
					$Flag_Zone=$dataat['Flag_Gare'];
				elseif($dataat['Placement'] ==4)
					$Flag_Zone=$dataat['Flag_Port'];
				elseif($dataat['Placement'] ==5)
					$Flag_Zone=$dataat['Flag_Pont'];
				elseif($dataat['Placement'] ==6)
					$Flag_Zone=$dataat['Flag_Usine'];
				elseif($dataat['Placement'] ==7)
					$Flag_Zone=$dataat['Flag_Radar'];
				elseif($dataat['Placement'] ==11)
					$Flag_Zone=$dataat['Flag_Plage'];
				else
					$Flag_Zone=$dataat['Flag'];
				if($dataat['Ravit'])
				{
					echo "La ".$dataat['Reg']."e Cie <img src='images/".$Pays_Reg."20.gif'> située à ".$dataat['Lieu_Nom']." ".GetPlace($dataat['Placement'])." contrôlée par <img src='images/".$Flag_Zone."20.gif'> ne subira pas l'attrition grâce au ravitaillement aérien dont elle a bénéficié.<img src='images/map/air_ravit.png' title='Ravitaillé par air'><br>";
					$Attrition=false;
				}
				elseif(($dataat['Faction'] ==1 and in_array($Flag_Zone,$Axe)) or ($dataat['Faction'] ==2 and in_array($Flag_Zone,$Allies)))
				{
					echo "La ".$dataat['Reg']."e Cie <img src='images/".$Pays_Reg."20.gif'> située à ".$dataat['Lieu_Nom']." ".GetPlace($dataat['Placement'])." contrôlée par <img src='images/".$Flag_Zone."20.gif'> ne subira pas l'attrition grâce au contrôle de la zone par sa faction.<br>";
					$Attrition=false;
				}
				else
				{
					if($dataat['Division']>0)
					{
						$Lieu_Veh_Cdt=false;
						//$con=dbconnecti();
						$Lieu_Veh_Cdt=mysqli_result(mysqli_query($con,"SELECT r.Lieu_ID FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Division=".$dataat['Division']." AND c.Categorie=4 AND r.Vehicule_Nbr=1"),0);
						//mysqli_close($con);
						if($Lieu_Veh_Cdt ==$Lieu_ID)
						{
							echo "La ".$dataat['Reg']."e Cie <img src='images/".$Pays_Reg."20.gif'> située à ".$dataat['Lieu_Nom']." ".GetPlace($dataat['Placement'])." contrôlée par <img src='images/".$Flag_Zone."20.gif'> ne subira pas l'attrition grâce à la présence du véhicule de commandement de sa division.<br>";
							$Attrition=false;
						}
					}
				}
				if($Attrition)
				{
					$Reg_Atr[]=$dataat['Reg'];
					echo "<span class='text-danger'>La ".$dataat['Reg']."e Cie <img src='images/".$Pays_Reg."20.gif'> située à ".$dataat['Lieu_Nom']." ".GetPlace($dataat['Placement'])." contrôlée par <img src='images/".$Flag_Zone."20.gif'> subira l'attrition et perdra 1 unité.</span><br>";
				}
			}
			mysqli_free_result($resultat);
			unset($dataat);
		}
		if(is_array($Reg_Atr))
		{
			if(array_count_values($Reg_Atr) >0)
			{
				$Reg_Atr_in=implode(',',$Reg_Atr);
				//$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Regiment_IA SET Mission_Type_D=23,Mission_Lieu_D=Lieu_ID WHERE ID IN(".$Reg_Atr_in.")");
				//mysqli_close($con);
			}
		}
	}
}
?>