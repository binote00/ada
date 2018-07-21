<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID=$_SESSION['PlayerID'];
$country=$_SESSION['country'];
include_once('./menu_infos.php');
//if(isset($_SESSION['AccountID']))
if(1==2)
{
	$MIA=GetData("Joueur","ID",$PlayerID,"MIA");	
	if(!$MIA and $_SESSION['Distance'] ==0 and $PlayerID >0)
	{
		//GetData Player
		$con=dbconnecti();
		$result=mysqli_query($con, "SELECT Reputation,Avancement,Credits,Missions_Jour,Victoires FROM Joueur WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Reputation=$data['Reputation'];
				$Avancement=$data['Avancement'];
				$Credits=$data['Credits'];
				$Missions_Jour=$data['Missions_Jour'];
				$Victoires=$data['Victoires'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if(($Reputation >9999 and $Avancement >4999 and $Credits >7 and $Missions_Jour <6 and $Victoires >4) or $PlayerID ==1)
		{						
			echo "En tant que pilote, vous avez accès à certains dossiers de renseignements généraux. 
			<p> En tant qu'officier réputé, vous avez l'autorisation de tester certains avions en vol et d'évaluer leurs performances dans le centre d'essai.</p>";

?>
<h2>Simulation de combat</h2>
<form action="index.php?view=testcombat" method="post">
	<table class='table'>
	<tr><td colspan="2"><img src="images/miss_leader1.jpg"></td></tr>
	<tr><th align="left">Votre avion</th><td align="left">
			<select name="avion" style="width: 200px">
					<?
						if($PlayerID ==1)
							$query="SELECT DISTINCT ID,Nom,Type FROM Avion ORDER BY Nom ASC";
						else
						{
							$ID_ref=GetData("Avions_Persos","ID",GetData("Joueur","ID",$PlayerID,"Avion_Perso"),"ID_ref");
							$query="SELECT DISTINCT ID,Nom,Type FROM Avion WHERE ID='$ID_ref' OR (Pays='$country' AND Etat=1) ORDER BY Nom ASC";
						}
						$con=dbconnecti();
						$result=mysqli_query($con, $query);
						mysqli_close($con);
						if($result)
						{
							while ($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
							{
								$Type=GetAvionType($data['Type']);
								?>
								 <option value="<? echo $data['ID'];?>"> <? echo $data['Nom']." ( ".$Type." )";?> </option>
								<?
							}
							mysqli_free_result($result);
						}
					?>
			</select>
	</td></tr>
	<tr><th align="left">L'avion de votre adversaire</th><td align="left">
			<select name="avion_eni" style="width: 200px">
					<?
						if($PlayerID ==1)
							$query="SELECT DISTINCT ID,Nom,Type FROM Avion ORDER BY Nom ASC";
						else
							$query="SELECT DISTINCT ID,Nom,Type FROM Avion WHERE Pays='$country' AND Etat=1 ORDER BY Nom ASC";
						$con=dbconnecti();
						$result=mysqli_query($con, $query);
						mysqli_close($con);
						if($result)
						{
							while ($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
							{
								$Type=GetAvionType($data['Type']);
								?>
								 <option value="<? echo $data['ID'];?>"> <? echo $data['Nom']." ( ".$Type." )";?> </option>
								<?
							}
							mysqli_free_result($result);
						}
					?>
			</select>
	</td></tr>
	<tr><th align="left">Votre adversaire</th><td align="left">
			<select name="eni" style="width: 200px">
				<option value="4">Un élève-pilote</option>
				<option value="147">Un pilote breveté</option>
				<option value="148">Un Pilote confirmé</option>
				<option value="149">Un pilote d'essai du centre</option>
			<?if($Reputation > 19999){?>
				<option value="150">Un as de la chasse</option>
			<?}?>
			</select>
	</td></tr>
	<tr><td align="left">Altitude</td><td align="left"><select name="alt"><option value="5000">Altitude moyenne</option><option value="10000">Haute altitude</option><option value="100">Basse altitude</option></select></td></tr>
	<tr><td align="left">Meteo</td><td align="left"><select name="meteo"><option value="0">Jour, Temps clair</option><option value="-100">Nuit</option></select></td></tr>
	</table>
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?
		}
		else
			echo "En tant que pilote, vous avez accès à certains dossiers de renseignements généraux.";
	}
	else
	{
		echo "<h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
		echo "<img src='images/unites".$country.".jpg'>";
	}
}
?>