<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$con=dbconnecti(4);
	$resulta=mysqli_query($con,"SELECT * FROM Events_Pertes WHERE Event_Type IN(11) AND Date BETWEEN CURDATE() - INTERVAL 30 DAY AND NOW() ORDER BY ID DESC"); //12,34,222
	mysqli_close($con);
	if($resulta)
	{
		while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC)) 
		{
			$meteo_txt=false;
			if($data['Avion_Nbr'] ==1)
				$Perte_txt="<img src='images/ia_down.png' title='Perte totale'>";
			else
				$Perte_txt="<img src='images/pers_17.gif' title='Avion sauvé'>";
			if($data['Pilote_eni'] !=0)
			{
				$data['Pilote_eni']=0-$data['Pilote_eni'];
				if($data['Pilote_eni'] ==-100)
					$meteo_txt="<br>Seul un aveugle ou un idiot décolle par ce temps";
				elseif($data['Pilote_eni'] <-70)
					$meteo_txt="<br>Météo optimale pour un crash";
				elseif($data['Pilote_eni'] <-49)
					$meteo_txt="<br>Météo idéale pour un crash";
				elseif($data['Pilote_eni'] <-19)
					$meteo_txt="<br>Météo idéale pour un crash sur piste en herbe ou en terre, optimale sur porte-avions";
				else
					$meteo_txt="<br>Le pilote est soit un débutant, soit il a manqué de chance";
			}
			if($data['Event_Type'] ==11)
			{
				$takeoff_nbr++;
				$takeoff_txt.="<tr><td>".$data['Date']."</td><td>".$Perte_txt."</td><td>".GetData("Pilote","ID",$data['PlayerID'],"Nom")."</td><td>".GetAvionIcon($data['Avion'])."</td><td><img src='images/meteo".$data['Pilote_eni'].".jpg'>".$meteo_txt."</td></tr>";
			}
			/*elseif($data['Event_Type'] ==12)
			{
				$landing_nbr++;
				$landing_txt.="<tr><td>".$data['Date']."</td><td>".$Perte_txt."</td><td>".GetData("Pilote","ID",$data['PlayerID'],"Nom")."</td><td>".GetAvionIcon($data['Avion'])."</td><td></td></tr>";
			}
			elseif($data['Event_Type'] ==34)
			{
				$deco_nbr++;
				$deco_txt.="<tr><td>".$data['Date']."</td><td>".$Perte_txt."</td><td>".GetData("Pilote","ID",$data['PlayerID'],"Nom")."</td><td>".GetAvionIcon($data['Avion'])."</td><td></td></tr>";
			}
			elseif($data['Event_Type'] ==222)
			{
				$destr_nbr++;
				$destr_txt.="<tr><td>".$data['Date']."</td><td>".$Perte_txt."</td><td>".GetData("Pilote","ID",$data['PlayerID'],"Nom")."</td><td>".GetAvionIcon($data['Avion'])."</td><td></td></tr>";
			}*/
		}
		mysqli_free_result($resulta);
		unset($data);
	}
	echo "<h1>Crashs des 30 derniers jours</h1>
	<div class='row'><h2>Au décollage (Total ".$takeoff_nbr.")</h2>
	<table class='table'><thead><tr><th>Date</th><th>Perte</th><th>Pilote</th><th>Avion</th><th>Météo</th></tr></thead>".$takeoff_txt."</table></div>";
	/*<div class='col-md-6'><h2>A l'atterrissage</h2>
	<table class='table'><thead><tr><th>Date</th><th>Perte</th><th>Pilote</th><th>Avion</th></tr></thead>".$landing_txt."</table></div></div>";*/
	//<tr><td colspan='5'>Crashs à l'atterrissage (".$landing_nbr.")</td></tr>".$landing_txt."</table>";
	/*<tr><td colspan='5'>Pertes dues à une panne d'essence ou une déconnexion (".$deco_nbr.")</td></tr>".$deco_txt."
	<tr><td colspan='5'>Avions détruits au sol (".$destr_nbr.")</td></tr>".$destr_txt."</table>";*/
}
?>