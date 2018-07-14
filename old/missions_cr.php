<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID = $_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{	
	$country = $_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin)
	{
		/*Compte-rendu de missions
		$Dateref = date('Y-m-d');
		$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
		if($GHQ or $Admin)
		{
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Last_Attack IS NOT NULL ORDER BY Nom ASC";
			$query2="SELECT DISTINCT ID,Nom,Occupant FROM Lieu WHERE Last_Attack='$Date_Campagne' ORDER BY Nom ASC";
		}
		elseif($Front == 3)
		{
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Last_Attack IS NOT NULL AND Longitude >67 ORDER BY Nom ASC";
			$query2="SELECT DISTINCT ID,Nom,Occupant FROM Lieu WHERE Last_Attack='$Date_Campagne' AND Longitude >67 ORDER BY Nom ASC";
		}
		elseif($Front == 2)
		{
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Last_Attack IS NOT NULL AND Latitude <45 AND Longitude <50 ORDER BY Nom ASC";
			$query2="SELECT DISTINCT ID,Nom,Occupant FROM Lieu WHERE Last_Attack='$Date_Campagne' AND Lieu.Latitude <45 AND Lieu.Longitude <50 ORDER BY Nom ASC";
		}
		elseif($Front == 1 or $Front == 4)
		{
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Last_Attack IS NOT NULL AND Latitude >=43 AND Longitude >14 ORDER BY Nom ASC";
			$query2="SELECT DISTINCT ID,Nom,Occupant FROM Lieu WHERE Last_Attack='$Date_Campagne' AND Latitude >=43 AND Longitude >14 ORDER BY Nom ASC";
		}
		elseif($Front == 5)
		{
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Last_Attack IS NOT NULL AND Latitude >60 ORDER BY Nom ASC";
			$query2="SELECT DISTINCT ID,Nom,Occupant FROM Lieu WHERE Last_Attack='$Date_Campagne' AND Latitude >60 ORDER BY Nom ASC";
		}
		else
		{
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Last_Attack IS NOT NULL AND Latitude >=45 AND Latitude <60 AND Longitude <14 ORDER BY Nom ASC";
			$query2="SELECT DISTINCT ID,Nom,Occupant FROM Lieu WHERE Last_Attack='$Date_Campagne' AND Latitude >=43 AND Latitude <60 AND Longitude <14 ORDER BY Nom ASC";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query) or die(mysqli_error($con));
		$result2=mysqli_query($con,$query2) or die(mysqli_error($con));
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$Lieux.="<option value=".$data['ID'].">".$data['Nom']."</option>";
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($result2)
		{
			while ($data=mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
			{
				$ID=$data['ID'];
				/*$DCA=$data['DCA'];
				$Recce=$data['Recce'];
				$Escorte=$data['Escorte'];
				$Patrouille=$data['Patrouille'];
				$Attaque=$data['Attaque'];
				$Bombardement=$data['Bombardement'];
				$Chasse=$data['Chasse'];
				$Sup=$data['Sup'];*/
				$con=dbconnecti(4);
				$DCA_endom=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Feed WHERE Event_Type=78 AND Lieu='$ID' AND DATE(Date)='$Dateref'"),0);
				mysqli_close($con);
				$con=dbconnecti();
				$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Lieu='$ID' AND DATE(DCA.Date)='$Dateref'"),0);
				$Recce=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce WHERE DATE(Recce.Date)='$Dateref' AND Lieu='$ID'"),0);
				$Attaque=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Attaque WHERE Lieu='$ID' AND DATE(Attaque.Date)='$Dateref'"),0);
				$Bombardement=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement WHERE Lieu='$ID' AND DATE(Bombardement.Date)='$Dateref'"),0);
				$Chasse=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Lieu='$ID' AND DATE(Chasse.Date)='$Dateref'"),0);
				$Escorte=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Escorte WHERE Lieu='$ID' AND DATE(Escorte.Date)='$Dateref'"),0);
				$Patrouille=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Patrouille WHERE Lieu='$ID' AND DATE(Patrouille.Date)='$Dateref'"),0);					
				$Sup=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse,Avion,Unit WHERE Lieu='$ID' AND Chasse.Avion_win=Avion.ID AND Avion.Type=1
				AND Chasse.Unite_win=Unit.ID AND Unit.Pays='$country' AND DATE(Chasse.Date)='$Dateref'"),0);
				mysqli_close($con);			
				$war=IsWar($data['Occupant'], $country);
				if(!$war)
					$Escorte="Inconnu";
				else
					$Patrouille="Inconnu";

				if($DCA >0 or $DCA_endom >0 or $Recce >0 or $Escorte >0 or $Patrouille >0 or $Attaque >0 or $Bombardement >0 or $Chasse >0 or $Sup >0)
				{
					$table_data.="<tr><th align='left'>".$data['Nom']."</th><td><img src='".$data['Occupant']."20.gif'></td>
					<td>".$Recce."</td><td>".$Escorte."</td><td>".$Patrouille."</td><td>".$Attaque."</td><td>".$Bombardement."</td><td>".$DCA."</td><td>".$DCA_endom."</td>
					<td>".$Chasse."</td><td>".$Sup."</td></tr>";
				}
			}
		}*/
		?>
		<h2>Compte-rendu général des Opérations pour la journée en cours</h2>
		<form action='index.php?view=detail_attaque' method='post'>
		<table class='table'>
			<tr><td align="left">
					<select name="id" class='form-control' style="width: 200px">
					<?echo $Lieux;?>
					</select>
				</td>
				<td rowspan="2"><input type='Submit' value='Voir le compte-rendu' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td>
			</tr>
			<tr><td align="left">
					<select name="date" class='form-control' style="width: 200px">
						<option value='0'>Aujourd'hui</option>
						<option value='1'>Hier</option>
						<option value='2'>Avant-hier</option>
						<option value='3'>Il y a 3 jours</option>
						<option value='4'>Il y a 4 jours</option>
						<option value='5'>Il y a 5 jours</option>
						<option value='6'>Il y a 6 jours</option>
						<option value='7'>Il y a 7 jours</option>
					</select>
				</td>
			</tr>
		</table>
		</form>
		<h2>Compte-rendu général des Opérations pour la journée en cours</h2>
		<table class='table'>
			<thead><tr><th>Lieu</th><th>Occupant</th><th>Reconnaissance</th><th>Escortes</th><th>Patrouilles</th><th>Cibles détruites</th><th>Cibles bombardées</th><th>Abattus par la DCA</th><th>Endommagés par la DCA</th><th>Avions Abattus</th><th>Couverture réduite</th></tr></thead>
			<?echo $table_data;?>
		</table>
<?	}
	else
		PrintNoAccess($country,1,4);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>