<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_as_des_as.php');
	$country=$_SESSION['country'];
	$Free=GetData("Joueur","ID",$PlayerID,"Free");
	$Admin=GetData("Joueur","ID",$PlayerID,"Admin");
	$Front=Insec($_POST['Front']);	
	if($_SESSION['Distance'] ==0 and ($Free >0 or $Admin))
	{
		$_SESSION['Decollage']=false;
		$_SESSION['done']=false;
		$Sandbox=1;
		$Score=0;
		$As_Missions=GetData("Joueur","ID",$PlayerID,"As_Missions");
		$con=dbconnecti();
		$Liste_kills=mysqli_query($con,"SELECT c.Pilote_loss,a.* FROM aubedesaiglesnet6.Chasse_sandbox as c, Avion as a WHERE c.Joueur_win='$PlayerID' AND c.PVP=0 AND c.Avion_loss=a.ID");
		mysqli_close($con);
		if($Liste_kills)
		{
			while($data_kills=mysqli_fetch_array($Liste_kills,MYSQLI_ASSOC))
			{
				$Calibre1=GetData("Armes","ID",$data_kills['ArmePrincipale'],"Calibre")*20;
				$Calibre2=GetData("Armes","ID",$data_kills['ArmeSecondaire'],"Calibre")*20;
				$kills_score=(($data_kills['ManoeuvreH']*10) + ($data_kills['ManoeuvreB']*10) + ($data_kills['Maniabilite']*20) + ($data_kills['Robustesse']*2) + ($data_kills['Detection']*20) 
				+ ($data_kills['VitesseH']*2) + ($data_kills['VitesseB']*2) + $data_kills['VitesseA'] + $data_kills['VitesseP'] 
				+ ($data_kills['Blindage']*10) + ($data_kills['Plafond']/10) + ($data_kills['Arme1_Nbr']*$Calibre1) + ($data_kills['Arme2_Nbr']*$Calibre2))/100;
				if($data_kills['Pilote_loss'] ==4)
					$Score += $kills_score;
				elseif($data_kills['Pilote_loss'] ==147)
					$Score += ($kills_score*2.5);
				elseif($data_kills['Pilote_loss'] ==148)
					$Score += ($kills_score*3.5);
				elseif($data_kills['Pilote_loss'] ==149)
					$Score += ($kills_score*5);
				elseif($data_kills['Pilote_loss'] ==150)
					$Score += ($kills_score*6);
				elseif($data_kills['Pilote_loss'] ==460)
					$Score +=($kills_score*7);
				else
					$Score +=($kills_score*8);
			}
			mysqli_free_result($Liste_kills);
		}
		//$Level=1+(floor($Score/10000));		
		if($Score >10000000 and $As_Missions >50000)
			$Level=10;
		elseif($Score >5000000 and $As_Missions >25000)
			$Level=9;
		elseif($Score >1000000 and $As_Missions >10000)
			$Level=8;
		elseif($Score >500000 and $As_Missions >5000)
			$Level=7;
		elseif($Score >200000 and $As_Missions >2000)
			$Level=6;
		elseif($Score >100000 and $As_Missions >1000)
			$Level=5;
		elseif($Score >50000 and $As_Missions >500)
			$Level=4;
		elseif($Score >20000 and $As_Missions >250)
			$Level=3;
		elseif($Score >10000 and $As_Missions >100)
			$Level=2;
		else
			$Level=1;		
		if($country ==20)
			$query3="SELECT DISTINCT ID,Nom,Type,Pays FROM Avion WHERE (Pays='$country' OR ID IN (10,11,63,146)) AND Etat=1 AND Type IN (1,4,12) AND Prototype=0 AND Premium=0 AND Rating BETWEEN 1 AND $Level ORDER BY NOM ASC";
		else
			$query3="SELECT DISTINCT ID,Nom,Type,Pays FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Type IN (1,4,12) AND Premium=0 AND Rating BETWEEN 1 AND $Level ORDER BY NOM ASC";
		
		if($Front ==1)
		{
			$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Occupant='$country' AND Latitude >57.8 AND Latitude <62.66 AND Longitude >24 AND Longitude <33 AND LongPiste_Ori >790 ORDER BY RAND() LIMIT 1";
			$query2="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >24 AND Longitude <33 AND Latitude > 57.8 AND Latitude <62.66 ORDER BY RAND() LIMIT 1";
		}
		elseif($Front ==3)
		{
			$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Occupant='$country' AND Longitude >67 AND LongPiste_Ori >790 ORDER BY RAND() LIMIT 1";
			$query2="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >67 ORDER BY RAND() LIMIT 1";
		}
		elseif($Front ==2)
		{
			if($country ==1 or $country ==4)
				$countryb=6;
			else
				$countryb=$country;
			$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Occupant='$countryb' AND Latitude <35 AND Longitude >20 AND Longitude <28.3 AND LongPiste_Ori >790 ORDER BY RAND() LIMIT 1";
			$query2="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude > 20 AND Longitude <28.3 AND Latitude <35 ORDER BY RAND() LIMIT 1";
		}
		else
		{
			$query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Occupant='$country' AND Latitude >49 AND Latitude <52 AND Longitude >-1.65 AND Longitude <3.5 AND LongPiste_Ori >790 ORDER BY RAND() LIMIT 1";
			$query2="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Longitude >-1.65 AND Longitude <3.5 AND Latitude >49 AND Latitude <52 ORDER BY RAND() LIMIT 1";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		$result2=mysqli_query($con,$query2);
		$result3=mysqli_query($con,$query3);
		mysqli_close($con);
		if($result3)
		{
			while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC)) 
			{
				$Type=GetAvionType($data['Type']);
				$Avions.="<option value='".$data['ID']."'>".$data['Nom']." ( ".$Type." - ".GetPays($data['Pays']).")</option>";
			}
			mysqli_free_result($result3);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$Latitude_base=$data['Latitude'];
				$Longitude_base=$data['Longitude'];
				$Bases.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
			}
			mysqli_free_result($result);
		}
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
			{
				$Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data2['Longitude'],$data2['Latitude']);
				if($Distance[0] <250)
				{				
					$Cibles .= "<option value='".$data2['ID']."'>".$data2['Nom']." (".$Dist[0]."km)</option>";
				}
			}
			mysqli_free_result($result2);
		}
		//Avion perso
		$Avion_Sandbox=GetData("Joueur","ID",$PlayerID,"Avion_Sandbox");
		if($Avion_Sandbox >0)
		{
			//$Avion_perso=GetData("Avions_Sandbox","ID",$Avion_Sandbox,"ID_ref");
			$Avion_perso_nom=GetData("Avions_Sandbox","ID",$Avion_Sandbox,"Nom");
			$Avions .= "<option value='".$Avion_Sandbox."_'>".$Avion_perso_nom." (Avion personnel)</option>";
		}
		SetData("Joueur","Front_sandbox",$Front,"ID",$PlayerID);
?>
	<h2>Simulation de combat</h2>
	<img src="../images/as_des_as.jpg">
	<form action="../takeoff.php" method="post">
	<input type="hidden" name="pilote" value="<?echo $PlayerID;?>">
	<input type="hidden" name="sandbox" value="1">
	<table class='table'>
	<thead><tr><th>Avion</th><th>Base</th><th>Cible</th></tr></thead>
	<tr><td align="left"><select name="Avion" class='form-control' style="width: 300px"><?echo $Avions;?></select></td>
	<td><select name="Base" class='form-control' style="width: 200px"><?echo $Bases;?></select></td>
	<td align="left"><select name="Cible" class='form-control' style="width: 200px"><?echo $Cibles;?></select></td></tr>
	</table>
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?
	}
}
else
	echo "<h1>Vous devez �tre connect� pour acc�der � cette page!</h1>";
?>