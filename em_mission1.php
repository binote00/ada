<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$Unite_Type=Insec($_POST['type']);
$Reset=Insec($_POST['reset']);
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0 AND ($Unite_Type >0 or $Reset >0))
{
	$country=$_SESSION['country'];
	$Co_Heure=Insec($_POST['heure']);
	$Co_Lieu=Insec($_POST['lieu']);
	$Briefing=Insec($_POST['Briefing']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Front,Credits FROM Officier_em WHERE ID='$OfficierEMID'");
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Front=$data['Front'];
			$Credits=$data['Credits'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
	mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Commandant=$data['Commandant'];
			$Officier_Adjoint=$data['Adjoint_EM'];
		}
		mysqli_free_result($result2);
	}
	include_once('./menu_em.php');
	if(($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint) and $Front !=12)
	{
		if($Reset ==2)
		{
			SetDoubleData("Pays","Co_Heure_Mission",$Co_Heure,"Pays_ID",$country,"Front",$Front);
			SetDoubleData("Pays","Co_Lieu_Mission",$Co_Lieu,"Pays_ID",$country,"Front",$Front);
            $_SESSION['msg_em'] = 'Heure et lieu de coordination définis!';
            header('Location: ./index.php?view=em_mission');
		}
		elseif($Reset ==3)
		{
			SetDoubleData("Pays","Lieu_Mission".$Unite_Type,0,"Pays_ID",$country,"Front",$Front);
			SetDoubleData("Pays","Type_Mission".$Unite_Type,0,"Pays_ID",$country,"Front",$Front);
			$_SESSION['msg_em'] = 'Mission d\'Etat-Major réinitialisée!';
            header('Location: ./index.php?view=em_mission');
		}
		elseif($Reset ==11)
		{
			SetDoubleData("Pays","Briefing",$Briefing,"Pays_ID",$country,"Front",$Front);
            $_SESSION['msg_em'] = 'Briefing d\'Etat-Major rédigé!';
            header('Location: ./index.php?view=em_mission');
		}
        elseif($Reset ==1)
        {
            for($i=1;$i<11;$i++)
            {
                SetDoubleData("Pays","Lieu_Mission".$i,0,"Pays_ID",$country,"Front",$Front);
                SetDoubleData("Pays","Type_Mission".$i,0,"Pays_ID",$country,"Front",$Front);
            }
            $_SESSION['msg_em'] = 'Missions d\'Etat-Major réinitialisées!';
            header('Location: ./index.php?view=em_mission');
        }
		else
		{
			if($Credits <1)
				echo "<h6>Vous ne disposez pas de suffisamment de Crédits Temps pour assigner une mission à votre front !</h6>";
			else
			{
				$Lieux='';
				$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
				$Lands=GetAllies($Date_Campagne);
				if(IsAxe($country))
				{
					$Allies=$Lands[0];
					$Axe=$Lands[1];
				}
				else
				{
					$Axe=$Lands[0];
					$Allies=$Lands[1];
				}
				if($Front ==3) //$Date_Campagne >"1941-12-06"
				{
					$query_off="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Allies.") OR Zone=6) AND Longitude >67 ORDER BY Nom ASC";
					$query_def="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Axe.") OR Zone=6) AND Longitude >67 ORDER BY Nom ASC";
					$query_free="SELECT DISTINCT ID,Nom FROM Lieu WHERE Longitude >67 ORDER BY Nom ASC";
				}
				elseif($Front ==1 or $Front ==4) //$Date_Campagne >"1941-06-21"
				{
					$query_off="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Allies.") OR Zone=6) AND Latitude >41.5 AND Longitude >13.35 ORDER BY Nom ASC";
					$query_def="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Axe.") OR Zone=6) AND Latitude >41.5 AND Longitude >13.35 ORDER BY Nom ASC";
					$query_free="SELECT DISTINCT ID,Nom FROM Lieu WHERE Longitude >13.35 AND Latitude >41.5 ORDER BY Nom ASC";
				}
				elseif($Front ==5)
				{
					$query_off="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Allies.") OR Zone=6) AND Latitude >60 AND Longitude <60 ORDER BY Nom ASC";
					$query_def="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Axe.") OR Zone=6) AND Latitude >60 AND Longitude <60 ORDER BY Nom ASC";
					$query_free="SELECT DISTINCT ID,Nom FROM Lieu WHERE Longitude <60 AND Latitude >60 ORDER BY Nom ASC";
				}
				elseif($Front ==2)
				{
					$query_off="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Allies.") OR Zone=6) AND Latitude <45.1 AND Longitude <50 ORDER BY Nom ASC";
					$query_def="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Axe.") OR Zone=6) AND Latitude <45 AND Longitude <50 ORDER BY Nom ASC";
					$query_free="SELECT DISTINCT ID,Nom FROM Lieu WHERE Longitude <50 AND Latitude <45 ORDER BY Nom ASC";
				}
				else
				{
					$query_off="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Allies.") OR Zone=6) AND Latitude >=43 AND Latitude <60 AND Longitude <=14 ORDER BY Nom ASC";
					$query_def="SELECT DISTINCT ID,Nom FROM Lieu WHERE (Occupant IN (".$Axe.") OR Zone=6) AND Latitude >=43 AND Latitude <60 AND Longitude <=14 ORDER BY Nom ASC";
					$query_free="SELECT DISTINCT ID,Nom FROM Lieu WHERE Longitude <=14 AND Latitude >=43 AND Latitude <60 ORDER BY Nom ASC";
				}
				$Carte_Bouton="<a href='carte_ground.php?map=".$Front."&mode=1' class='btn btn-primary' onclick='window.open(this.href); return false;'>Voir la carte</a>";
				//Lieux offensifs
				$con=dbconnecti();
				$result_off=mysqli_query($con,$query_free) or die(mysqli_error($con));
				//$result_def=mysqli_query($con,$query_free) or die(mysqli_error($con));
				//$result_off=mysqli_query($con,$query_off) or die(mysqli_error($con));
				//$result_def=mysqli_query($con,$query_def) or die(mysqli_error($con));
				//$result_free=mysqli_query($con,$query_free) or die(mysqli_error($con));
				mysqli_close($con);
				if($result_off)
				{
					while($data=mysqli_fetch_array($result_off,MYSQLI_ASSOC)) 
					{
						$Lieux_off.="<option value=".$data['ID'].">".$data['Nom']."</option>";
                        $Lieux_def.="<option value=".$data['ID'].">".$data['Nom']."</option>";
					}
					mysqli_free_result($result_off);
				}	
				/*Lieux défensifs
				if($result_def)
				{
					while($data=mysqli_fetch_array($result_def,MYSQLI_ASSOC)) 
					{
                        $Lieux_def.="<option value=".$data['ID'].">".$data['Nom']."</option>";
					}
					mysqli_free_result($result_def);
				}	
				if($result_free)
				{
					while($data=mysqli_fetch_array($result_free,MYSQLI_ASSOC)) 
					{
						$Lieux_free.="<option value=".$data['ID'].">".$data['Nom']."</option>";
					}
					mysqli_free_result($result_free);
				}*/
				switch($Unite_Type)
				{
					case 1:
						$choix1="<option value='1'>Appui rapproché</option>";
						$choix6="<option value='6'>Attaque au sol</option>";
						$choix11="<option value='11'>Attaque de navire</option>";
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix4="<option value='4'>Escorte</option>";
						$choix7="<option value='7'>Patrouille défensive</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						//$choix26="<option value='26'>Supériorité aérienne</option>";
					break;
					case 2:
						$choix12="<option value='12'>Bombardement naval</option>";
						$choix8="<option value='8'>Bombardement stratégique de jour</option>";
						$choix16="<option value='16'>Bombardement stratégique de nuit</option>";
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						$choix13="<option value='13'>Torpillage</option>";
					break;
					case 3:
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix21="<option value='21'>Marquage de cible</option>";
						$choix15="<option value='15'>Reconnaissance stratégique</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						//Prévoir mission brouillage radio
					break;
					case 4:
						$choix1="<option value='1'>Appui rapproché</option>";
						$choix6="<option value='6'>Attaque au sol</option>";
						$choix11="<option value='11'>Attaque de navire</option>";
						$choix12="<option value='12'>Bombardement naval</option>";
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix17="<option value='17'>Chasse de nuit</option>";
						$choix4="<option value='4'>Escorte</option>";
						$choix31="<option value='31'>Harcèlement (nuit)</option>";
						$choix7="<option value='7'>Patrouille défensive</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
					break;
					case 6:
						$choix24="<option value='24'>Parachutage de jour</option>";
						$choix25="<option value='25'>Parachutage de nuit</option>";
						$choix23="<option value='23'>Ravitaillement</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
					break;
					case 7:
						$choix1="<option value='1'>Appui rapproché</option>";
						$choix6="<option value='6'>Attaque au sol</option>";
						$choix11="<option value='11'>Attaque de navire</option>";
						$choix12="<option value='12'>Bombardement naval</option>";
						$choix8="<option value='8'>Bombardement stratégique de jour</option>";
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						$choix13="<option value='13'>Torpillage</option>";
					break;
					case 8:
					break;
					case 9:
						$choix12="<option value='12'>Bombardement naval</option>";
						$choix29="<option value='29'>Patrouille ASM</option>";
						$choix14="<option value='14'>Mouillage de mines</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						$choix15="<option value='15'>Reconnaissance stratégique</option>";
						$choix13="<option value='13'>Torpillage</option>";
						$choix19="<option value='19'>Sauvetage en mer</option>";
					break;
					case 10:
						$choix1="<option value='1'>Appui rapproché</option>";
						$choix6="<option value='6'>Attaque au sol</option>";
						$choix11="<option value='11'>Attaque de navire</option>";
						$choix12="<option value='12'>Bombardement naval</option>";
						$choix8="<option value='8'>Bombardement stratégique de jour</option>";
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
						$choix13="<option value='13'>Torpillage</option>";
					break;
					case 12:
						$choix1="<option value='1'>Appui rapproché</option>";
						$choix6="<option value='6'>Attaque au sol</option>";
						$choix11="<option value='11'>Attaque de navire</option>";
						$choix12="<option value='12'>Bombardement naval</option>";
						$choix2="<option value='2'>Bombardement tactique</option>";
						$choix4="<option value='4'>Escorte</option>";
						$choix7="<option value='7'>Patrouille défensive</option>";
						$choix5="<option value='5'>Reconnaissance tactique</option>";
					break;
				}
				echo '<p>'.$Carte_Bouton.'</p>';
				if($choix1 or $choix2 or $choix4 or $choix5 or $choix6 or $choix7 or $choix8 or $choix11 or $choix12 or $choix13 or $choix15 or $choix16 or $choix21 or $choix23 or $choix24 or $choix25 or $choix26 or $choix29 or $choix31)
				{
					echo "<h2>Missions Offensives</h2>
					<form action='em_mission2.php' method='post'>
					<input type='hidden' name='Unite' value='".$Unite_Type."'>
					<table class='table'>
						<thead><tr><th>Mission</th><th>Cible</th></tr></thead>
						<tr><td>
								<select name='Type' class='form-control' style='width: 200px'>
								".$choix1.$choix6.$choix11.$choix12.$choix2.$choix8.$choix16.$choix4.$choix21.$choix29.$choix24.$choix25.$choix23.$choix15.$choix5.$choix26.$choix13.$choix31."
								</select>			
							</td><td>
								<select name='Cible' class='form-control' style='width: 200px'>
									".$Lieux_off."
								</select>
						</td></tr>
					</table><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}
				if($choix1 or $choix2 or $choix4 or $choix5 or $choix7 or $choix11 or $choix12 or $choix14 or $choix17 or $choix23 or $choix29)
				{
					echo "<h2>Missions Défensives</h2>
					<form action='em_mission2.php' method='post'>
					<input type='hidden' name='Unite' value='".$Unite_Type."'>
					<table class='table'>
						<thead><tr><th>Mission</th><th>Cible</th></tr></thead>
						<tr><td>
								<select name='Type' class='form-control' style='width: 200px'>
								".$choix17.$choix14.$choix7.$choix29.$choix23.$choix5."
								</select>			
							</td><td>
								<select name='Cible' class='form-control' style='width: 200px'>
									".$Lieux_def."
								</select>
							</td></tr>
					</table>
					<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}
			}//End Credits
		}//End Reset
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";