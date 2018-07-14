<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA)
	{
		$rap=false;
		$con=dbconnecti();
		$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result=mysqli_query($con,"SELECT Unit,Front,Avancement,Credits FROM Pilote WHERE ID='$PlayerID'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Front=$data['Front'];
				$Avancement=$data['Avancement'];
				$Credits=$data['Credits'];
			}
			mysqli_free_result($result);
		}
		$result2=mysqli_query($con,"SELECT u.Nom,u.Type,u.Base,u.Commandant,u.Officier_Adjoint,u.Avion1,u.Avion2,u.Avion3,u.Avion1_Nbr,u.Avion2_Nbr,u.Avion3_Nbr,l.Latitude,l.Longitude
		FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.ID='$Unite'");
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Unite_Nom=$data2['Nom'];
				$Unite_Type=$data2['Type'];
				$Base=$data2['Base'];
				$Commandant=$data2['Commandant'];
				$Officier_Adjoint=$data2['Officier_Adjoint'];
				$Avion1=$data2['Avion1'];
				$Avion2=$data2['Avion2'];
				$Avion3=$data2['Avion3'];
				$Avion1_Nbr=$data2['Avion1_Nbr'];
				$Avion2_Nbr=$data2['Avion2_Nbr'];
				$Avion3_Nbr=$data2['Avion3_Nbr'];
				$Long_base=$data2['Longitude'];
				$Lat_base=$data2['Latitude'];
			}
			mysqli_free_result($result2);
		}
		if($Avion1 >0)
		{
			$Avion1_m=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Task>0 AND Avion='$Avion1' AND Actif=1"),0);
			$Avion1_Nbr -=$Avion1_m;
		}
		if($Avion2 >0 and $Avion1 !=$Avion2)
		{
			$Avion2_m=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Task>0 AND Avion='$Avion2' AND Actif=1"),0);
			$Avion2_Nbr -=$Avion2_m;
		}
		if($Avion3 >0 and $Avion2 !=$Avion3 and $Avion2 !=$Avion3)
		{
			$Avion3_m=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Task>0 AND Avion='$Avion3' AND Actif=1"),0);
			$Avion3_Nbr -=$Avion3_m;
		}
		//$resultm=mysqli_query($con,"SELECT Nom,Lieu,Type_Mission FROM Event_Historique WHERE Date='$Date_Campagne' AND Type_Mission >0 AND Pays='$country' AND Unite='$Unite_Type'");
		mysqli_close($con);
		/*if($resultm)
		{
			while($data=mysqli_fetch_array($resultm,MYSQLI_ASSOC))
			{
				$MH_Nom=$data['Nom'];
				$MH_Lieu=$data['Lieu'];
				$MH_Mission=$data['Type_Mission'];
			}
			mysqli_free_result($resultm);
		}
		if(!$MH_Nom)$MH_Nom="Aucune";
		$MissionH_Lieu=GetData("Lieu","ID",$MH_Lieu,"Nom");
		if(!$MissionH_Lieu)$MissionH_Lieu="<i>Aucune</i>";
		$MissionH_Type=GetMissionType($MH_Mission);
		if(!$MissionH_Type)$MissionH_Type="<i>Indéfini</i>";*/
		$TM="Type_Mission".$Unite_Type;
		$LM="Lieu_Mission".$Unite_Type;
		$MissionF_Lieu=GetData("Lieu","ID",GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,$LM),"Nom");
		if(!$MissionF_Lieu)$MissionF_Lieu="<i>Aucune</i>";	
		$MissionF_Type=GetMissionType(GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,$TM));
		if(!$MissionF_Type)$MissionF_Type="<i>Indéfini</i>";
		if($Unite_Type !=8)
			include_once('./menu_escadrille.php');
		else
			echo '<h1>'.$Unite_Nom."</h1><div class='alert alert-info'>Lorsque vous aurez terminé votre formation et que votre demande de mutation sera validée, vous pourrez gérer les missions de votre nouvelle escadrille.</div>";
		$query="SELECT ID,Nom,Avancement,Reputation,Missions,Couverture,Escorte,Couverture_Nuit,Cible,Task,Avion,Points,Endurance
		FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 ORDER BY Missions DESC,Avancement DESC,Reputation DESC";
		$con=dbconnecti();
		$result3=mysqli_query($con,$query);
		mysqli_close($con);
		if($result3)
		{
			if($Unite_Type ==1 or $Unite_Type ==4 or $Unite_Type ==12)
			{
				$tasks="<th>Escorte</th><th>Couverture</th><th>Couv. Nuit</th><th>Rappeler</th></tr></thead>";
				$form="<form action='index.php?view=esc_rappeler' method='post'>";
			}
			elseif($Unite_Type ==3)
			{
				if($Credits >1 and ($Avion1_Nbr >0 or $Avion2_Nbr >0 or $Avion3_Nbr >0) and $Long_base and $Lat_base)
				{
					$Sqn=GetSqn($country);
					$Avions="<select name='avions' class='form-control' style='width: 300px'>";
					if($Avion1_Nbr >0)
						$Avions.="<option value='1'>".$Sqn." 1 (".$Avion1_Nbr."x ".GetData("Avion","ID",$Avion1,"Nom").")</option>";
					if($Avion2_Nbr >0)
						$Avions.="<option value='2'>".$Sqn." 2 (".$Avion2_Nbr."x ".GetData("Avion","ID",$Avion2,"Nom").")</option>";
					if($Avion3_Nbr >0)
						$Avions.="<option value='3'>".$Sqn." 3 (".$Avion3_Nbr."x ".GetData("Avion","ID",$Avion3,"Nom").")</option>";
					$Avions.="</select>";
					$Changes="<select name='task' class='form-control' style='width: 200px'>
					<option value='0'>Aucune</option>
					<option value='1'>Observation</option>
					<option value='2'>Pathfinder</option>
					<option value='4'>Sauvetage</option>
					<option value='5'>Veille</option>
					</select>";
					$Lieux="<select name='cible' class='form-control' style='width: 200px'><option value='".$Base."'>Votre base</option>";
					if($Front == 3)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >67 AND Zone<>6 AND ID<>'$Base' ORDER BY Nom ASC";
					elseif($Front == 2)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <60 AND Latitude <43 AND Zone<>6 AND ID<>'$Base' ORDER BY Nom ASC";
					elseif($Front ==1)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >13 AND Latitude >41 AND Latitude <=50.5 AND Zone<>6 AND ID<>'$Base' ORDER BY Nom ASC";
					elseif($Front == 4)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >13 AND Latitude > 50.5 AND Zone<>6 AND ID<>'$Base' ORDER BY Nom ASC";
					elseif($Front == 5)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <60 AND Latitude >60 AND Zone<>6 AND ID<>'$Base' ORDER BY Nom ASC";
					else
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <=14 AND Latitude >=43 AND Latitude <60 AND Zone<>6 AND ID<>'$Base' ORDER BY Nom ASC";
					$con=dbconnecti();
					$resultr=mysqli_query($con,$query) or die(mysqli_error($con));
					mysqli_close($con);
					if($resultr)
					{
						while($data=mysqli_fetch_array($resultr,MYSQLI_ASSOC)) 
						{
							$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data['Longitude'],$data['Latitude']);
							if($Dist[0] <250)
								$Lieux.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
						}
						mysqli_free_result($resultr);
						unset($data);
					}
					$Lieux.="</select>";
					$form="<form action='index.php?view=esc_task' method='post'><input type='hidden' name='a1' value='".$Avion1_Nbr."'><input type='hidden' name='a2' value='".$Avion2_Nbr."'><input type='hidden' name='a3' value='".$Avion3_Nbr."'>";
					$valid="<table class='table'><thead><tr><th>Mission <a href='help/aide_task.php' target='_blank' title='Aide'><img src='images/help.png'></a></th><th>Objectif</th><th>Avion</th></tr></thead><tr><td>".$Changes."</td><td>".$Lieux."</td><td>".$Avions."</td></tr></table>
					<img src='/images/CT2.png' title='Credits Temps nécessaires pour exécuter cette action'><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				$tasks="<th>Mission</th><th>Lieu</th><th colspan='2'>Assigner</th></tr></thead>";
			}
			elseif($Unite_Type ==9)
			{
				if($Credits >1 and ($Avion1_Nbr >0 or $Avion1_Nbr >0 or $Avion1_Nbr >0) and $Long_base and $Lat_base)
				{
					$Sqn=GetSqn($country);
					$Avions="<select name='avions' class='form-control' style='width: 300px'>";
					if($Avion1_Nbr >0)
						$Avions.="<option value='1'>".$Sqn." 1 (".$Avion1_Nbr."x ".GetData("Avion","ID",$Avion1,"Nom").")</option>";
					if($Avion2_Nbr >0)
						$Avions.="<option value='2'>".$Sqn." 2 (".$Avion2_Nbr."x ".GetData("Avion","ID",$Avion2,"Nom").")</option>";
					if($Avion3_Nbr >0)
						$Avions.="<option value='3'>".$Sqn." 3 (".$Avion3_Nbr."x ".GetData("Avion","ID",$Avion3,"Nom").")</option>";
					$Avions.="</select>";
					$Changes="<select name='task' class='form-control' style='width: 200px'>
					<option value='0'>Aucune</option>
					<option value='4'>Sauvetage</option>
					<option value='6'>Veille navale</option>
					</select>";
					$Max_Range=500;
					$Lieux="<select name='cible' class='form-control' style='width: 200px'><option value='".$Base."'>Votre base</option>";
					if($Front ==3)
					{
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >67 AND (Zone=6 OR Port_Ori>0) AND ID<>'$Base' ORDER BY Nom ASC";
						$Max_Range=1000;
					}
					elseif($Front ==2)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <60 AND Latitude <45 AND (Zone=6 OR Port_Ori>0) AND ID<>'$Base' ORDER BY Nom ASC";
					elseif($Front ==1)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >13 AND Latitude >41 AND Latitude <=50.5 AND (Zone=6 OR Port_Ori>0) AND ID<>'$Base' ORDER BY Nom ASC";
					elseif($Front ==4)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >13 AND Latitude >50.5 AND (Zone=6 OR Port_Ori>0) AND ID<>'$Base' ORDER BY Nom ASC";
					elseif($Front ==5)
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <60 AND Latitude >60 AND (Zone=6 OR Port_Ori>0) AND ID<>'$Base' ORDER BY Nom ASC";
					else
						$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude <=14 AND Latitude >=43 AND Latitude <60 AND (Zone=6 OR Port_Ori>0) AND ID<>'$Base' ORDER BY Nom ASC";
					$con=dbconnecti();
					$resultr=mysqli_query($con,$query) or die(mysqli_error($con));
					mysqli_close($con);
					if($resultr)
					{
						while($data=mysqli_fetch_array($resultr,MYSQLI_ASSOC)) 
						{
							$Dist=GetDistance(0,0,$Long_base,$Lat_base,$data['Longitude'],$data['Latitude']);
							if($Dist[0] <$Max_Range)
								$Lieux.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
						}
						mysqli_free_result($resultr);
						unset($data);
					}
					$Lieux.="</select>";
					$form="<form action='index.php?view=esc_task' method='post'><input type='hidden' name='a1' value='".$Avion1_Nbr."'><input type='hidden' name='a2' value='".$Avion2_Nbr."'><input type='hidden' name='a3' value='".$Avion3_Nbr."'>";
					$valid="<table class='table'><thead><tr><th>Mission <a href='help/aide_task.php' target='_blank' title='Aide'><img src='images/help.png'></a></th><th>Objectif</th><th>Avion</th></tr></thead><tr><td>".$Changes."</td><td>".$Lieux."</td><td>".$Avions."</td></tr></table>
					<img src='/images/CT2.png' title='Credits Temps nécessaires pour exécuter cette action'><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				$tasks="<th>Mission</th><th>Lieu</th><th colspan='2'>Assigner</th></tr></thead>";
			}
			else
				$tasks="<th colspan='4'></th></tr></thead>";
			//Loop Pilotes IA
			while($Data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$Avancement=GetAvancement($Data['Avancement'],$country);
				if(!$Premium)
					$Reputation=GetReputation($Data['Reputation'],$country);
				else
					$Reputation=$Data['Reputation'];
				if($Data['Avion'])
					$Avion_img=GetAvionIcon($Data['Avion'],$country,0,$Unite,$Front);
				else
					$Avion_img="";
				if($Data['Endurance'] >8)
					$fatigue_icon='<img src="images/fatigue9.png" title="Très fatigué">';
				elseif($Data['Endurance'] >5)
					$fatigue_icon='<img src="images/fatigue6.png" title="Fatigué">';
				elseif($Data['Endurance'] >2)
					$fatigue_icon='<img src="images/fatigue3.png" title="Légèrement fatigué">';
				else
					$fatigue_icon='<img src="images/fatigue0.png" title="En pleine forme">';
				$pils.="<tr><td>".$Data['Nom']."</td><td title='".$Avancement[0]."'><img src='images/grades/grades".$country.$Avancement[1].".png'></td><td>".$Reputation."</td><td>".$fatigue_icon."</td><td>".$Data['Points']."</td><th>".$Data['Missions']."</th><td>".$Avion_img."</td>";
				if(($Unite_Type ==1 or $Unite_Type == 4 or $Unite_Type ==12))
				{
					if($Data['Couverture'] >0 and ($Data['Couverture'] ==$Data['Cible']))
						$Couverture=GetData("Lieu","ID",$Data['Couverture'],"Nom");
					else
						$Couverture="";
					if($Data['Escorte'] >0 and ($Data['Escorte'] ==$Data['Cible']))
						$Escorte=GetData("Lieu","ID",$Data['Escorte'],"Nom");
					else
						$Escorte="";
					if($Data['Couverture_Nuit'] >0 and ($Data['Couverture_Nuit'] ==$Data['Cible']))
						$Couverture_Nuit=GetData("Lieu","ID",$Data['Couverture_Nuit'],"Nom");
					else
						$Couverture_Nuit="";
					if($Data['Cible'] or $Data['Couverture'] or $Data['Escorte'] or $Data['Couverture_Nuit'])
					{
						$pils.="<td>".$Escorte."</td><td>".$Couverture."</td><td>".$Couverture_Nuit."</td><td><input type='checkbox' name='ia_pilots[]' value='".$Data['ID']."'></td></tr>";
						$rap=true;
					}
					else
						$pils.="<td>".$Escorte."</td><td>".$Couverture."</td><td>".$Couverture_Nuit."</td><td></td></tr>";
				}
				elseif($Unite_Type ==3 or $Unite_Type ==9)
				{
					if($Data['Task'])
					{
						$Task=GetTask($Data['Task']);
						$Objectif=GetData("Lieu","ID",$Data['Cible'],"Nom");
					}
					else
					{
						$Task="";
						$Objectif="";
					}
					$pils.="<td>".$Task."</td><td>".$Objectif."</td><td colspan='2'><input type='checkbox' name='ia_pilots[]' value='".$Data['ID']."'></td></tr>";
				}
				else
					$pils.="<td colspan='4'></td></tr>";
			}
			mysqli_free_result($result3);
			if($rap)
			{
				if(!$form)$form="<form action='index.php?view=esc_rappeler' method='post'>";
				$valid="<input type='Submit' value='RAPPELER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}
			echo "<h2>Missions</h2><table class='table table-striped'><thead><tr><th>Nom</th><th>Implication</th><th>Type</th><th>Cible</th></tr></thead>
					<tr><td>Mission d'Etat-Major</td><td>Front</td><td>".$MissionF_Type."</td><td>".$MissionF_Lieu."</td></tr></table>";
					//<tr><td>".$MH_Nom."</td><td>Historique</td><td>".$MissionH_Type."</td><td>".$MissionH_Lieu."</td></tr>
			echo $form."<table class='table table-hover'><thead><tr><th>Nom</th><th>Grade</th><th>Reputation</th><th>Fatigue</th><th>Score</th><th>Missions</th><th>Avion 
			<a href='#' class='popup'><img src='images/help.png'><span>Si un avion est indiqué, le pilote est considéré en vol avec cet avion</span></a></th>".$tasks.$pils."</table>".$valid;

		}
		else
			echo "<h6>Désolé, votre escadrille ne compte pas de pilote actif.</h6>";
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>