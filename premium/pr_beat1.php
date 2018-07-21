<?
require_once('./jfv_inc_sessions.php');
$OfficierID = $_SESSION['Officier'];
$OfficierEMID = $_SESSION['Officier_em'];
if($OfficierID >0 or $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium >0)
	{
		$Veh=Insec($_POST['avion1']);		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Arme_Inf,Arme_AT,Arme_Art FROM Cible WHERE ID='$Veh'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{					
				$Arme_Inf = $data['Arme_Inf'];	
				$Arme_AT = $data['Arme_AT'];	
				$Arme_Art = $data['Arme_Art'];	
			}
			mysqli_free_result($result);		
		}
		$con=dbconnecti();
		//$result1=mysqli_query($con,"SELECT Nom,Degats,Multi,Calibre FROM Armes WHERE ID='$Arme_Inf'");
		$result2=mysqli_query($con,"SELECT Nom,Degats,Multi,Calibre FROM Armes WHERE ID='$Arme_AT'");
		//$result3=mysqli_query($con,"SELECT Nom,Degats,Multi,Calibre FROM Armes WHERE ID='$Arme_Art'");
		mysqli_close($con);
		/*if($result1)
		{
			while($data=mysqli_fetch_array($result1,MYSQLI_ASSOC))
			{					
				$Arme_Inf_Nom = $data['Nom'];	
				$Arme_Inf_Degats = $data['Degats'];	
				$Arme_Inf_Multi = $data['Multi'];	
				$Arme_Inf_Calibre = $data['Calibre'];	
			}
			mysqli_free_result($result1);		
		}*/
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{					
				$Arme_AT_Nom = $data['Nom'];	
				$Arme_AT_Degats = $data['Degats'];	
				$Arme_AT_Multi = $data['Multi'];	
				$Arme_AT_Calibre = $data['Calibre'];	
			}
			mysqli_free_result($result2);
	
		}
		/*if($result3)
		{
			while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{					
				$Arme_Art_Nom = $data['Nom'];	
				$Arme_Art_Degats = $data['Degats'];	
				$Arme_Art_Multi = $data['Multi'];	
				$Arme_Art_Calibre = $data['Calibre'];	
			}
			mysqli_free_result($result3);			
		}
		unset($data);*/				
		$i=0;
		$con=dbconnecti();
		$resultc=mysqli_query($con,"SELECT ID,Nom,Blindage_f,HP FROM Cible WHERE unit_ok=1 ORDER BY HP DESC");
		mysqli_close($con);
		if($result)
		{
			while($datac = mysqli_fetch_array($resultc,MYSQLI_ASSOC))
			{
				$i++;
				$HE_Inf=false;
				$AP_Inf=false;
				$HE_AT=false;
				$AP_AT=false;
				$HE_Art=false;
				$AP_Art=false;
				$Degats_HEAT=0;
				$Degats_HEAT_400=0;
				$Degats_APHE=0;
				//$Nom_eni = $datac['Nom'];
				$Blindage_eni = $datac['Blindage_f'];
				$HP_eni = $datac['HP'];
				$Nom_eni="<img src='images/vehicules/vehicule".$datac['ID'].".gif' title='".$datac['Nom']."'>";				
				/*$Degats_HE_Inf=round(($Arme_Inf_Degats-$Blindage_eni)*$Arme_Inf_Multi);
				$Degats_AP_Inf=round($Arme_Inf_Degats*$Arme_Inf_Multi);
				if($Degats_HE_Inf > $HP_eni)
					$HE_Inf="<img src='images/cible3.gif'>";
				if($Degats_AP_Inf > $HP_eni)
					$AP_Inf="<img src='images/cible3.gif'>";;*/
				$Degats=$Arme_AT_Degats*$Arme_AT_Multi;
				if($Blindage_eni)
				{
					$Degats_HE=$Arme_AT_Degats*$Arme_AT_Multi*0.5;
					//$Degats_AP=$Arme_AT_Degats*$Arme_AT_Multi*2;
					if($Arme_AT_Calibre > $Blindage_eni)
						$Degats_APHE=$Arme_AT_Degats*$Arme_AT_Multi*2;
					$Degats_APC=$Arme_AT_Degats*$Arme_AT_Multi*2;
					$Degats_APCR=$Arme_AT_Degats*$Arme_AT_Multi*2;
					$Degats_APCR_900=$Arme_AT_Degats*$Arme_AT_Multi/2;
					if($Arme_AT_Calibre >19)
						$Degats_APDS=$Arme_AT_Degats*$Arme_AT_Multi*2;
					if($Arme_AT_Calibre >69)
					{
						$Degats_HEAT=$Arme_AT_Degats*$Arme_AT_Multi*2;
						$Degats_HEAT_400=$Arme_AT_Degats*$Arme_AT_Multi;
					}
				}
				else
				{
					$Degats_HE=$Arme_AT_Degats*$Arme_AT_Multi*2;
					//$Degats_AP=$Arme_AT_Degats*$Arme_AT_Multi;
					$Degats_APHE=$Arme_AT_Degats*$Arme_AT_Multi*0.9;
					$Degats_APC=$Arme_AT_Degats*$Arme_AT_Multi*0.9;
					$Degats_APCR=$Arme_AT_Degats*$Arme_AT_Multi;
					$Degats_APCR_900=$Arme_AT_Degats*$Arme_AT_Multi*0.9;
					if($Arme_AT_Calibre >19)
						$Degats_APDS=$Arme_AT_Degats*$Arme_AT_Multi*0.5;
					if($Arme_AT_Calibre >69)
					{
						$Degats_HEAT=$Arme_AT_Degats*$Arme_AT_Multi*2;
						$Degats_HEAT_400=$Arme_AT_Degats*$Arme_AT_Multi*2;
					}
				}
				if($Degats > $HP_eni)
					$Dg_AT="<img src='images/cible3.gif'>";
				if($Degats_HE > $HP_eni)
					$HE_AT="<img src='images/cible3.gif'>";
				if($Degats_AP > $HP_eni)
					$AP_AT="<img src='images/cible3.gif'>";
				if($Degats_APHE > $HP_eni)
					$APHE_AT="<img src='images/cible3.gif'>";
				if($Degats_APC > $HP_eni)
					$APC_AT="<img src='images/cible3.gif'>";
				if($Degats_APCR > $HP_eni)
					$APCR_AT="<img src='images/cible3.gif'>";
				if($Degats_APDS > $HP_eni)
					$APDS_AT="<img src='images/cible3.gif'>";
				if($Degats_HEAT > $HP_eni)
					$HEAT_AT="<img src='images/cible3.gif'>";
				/*$Degats_HE_Art=round(($Arme_Art_Degats - $Blindage_eni)*$Arme_Art_Multi);
				$Degats_AP_Art=round($Arme_Art_Degats*$Arme_Art_Multi);
				if($Degats_HE_Art >$HP_eni)
					$HE_Art="<img src='images/cible3.gif'>";
				if($Degats_AP_Art >$HP_eni)
					$AP_Art=true;*/
				$output.="<tr><td>".$i."- ".$Nom_eni."</td><td>".$HP_eni."HP</td><td>".$Degats." Max ".$Dg_AT."</td><td>".$Degats_HE." Max HE ".$HE_AT."</td><td>".$Degats_APHE." Max APHE ".$APHE_AT."</td>
				<td>".$Degats_APC." Max APC ".$APC_AT."</td><td>".$Degats_APCR." Max APCR ".$APCR_AT."</td><td>".$Degats_APDS." Max APDS ".$APDS_AT."</td><td>".$Degats_HEAT." Max HEAT ".$HEAT_AT."</td></tr>";
				
			}
			mysqli_free_result($resultc);		
		}		
		//Output
		echo "<h1>Simulateur de Combat</h1>";
		echo "<table class='table'>
		<thead><tr><th colspan='12'>Troupes</th></tr></thead>
		<tr><th>Troupes</th><th>HP</th><th>AP</th><th>HE<br>Si pas blindé</th><th>APHE<br>Si blindé et percé</th><th>APC<br>Si blindé à -500m</th><th>APCR<br>Si blindé à -900m</th><th>APDS<br>Si blindé</th><th>HEAT<br>Si blindé à -400m</th></tr>";
		echo $output."</table>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
?>