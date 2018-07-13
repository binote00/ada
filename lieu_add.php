<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID = $_SESSION['PlayerID'];
if($PlayerID > 0)
	$Encodage = GetData("Joueur","ID",$PlayerID,"Encodage");
if($Encodage == 2)
{
$Pays = Insec($_POST['country']);
$Nom = Insec($_POST['nom']);
$Zone = Insec($_POST['zone']);
$Map = Insec($_POST['map']);
$Route = Insec($_POST['route']);
$Gare = Insec($_POST['gare']);
$Pont = Insec($_POST['pont']);
$Port = Insec($_POST['port']);
$Piste = Insec($_POST['piste']);
$Longitude = Insec($_POST['lon']);
$Latitude = Insec($_POST['lat']);
$occupationp = Insec($_POST['occupationp']);
$liberationp = Insec($_POST['liberationp']);
$occupation = Insec($_POST['occupation']);
$liberation = Insec($_POST['liberation']);
$Occuper = Insec($_POST['occuper']);
$Liberer = Insec($_POST['liberer']);
?>
<head><script type="text/javascript" src="calendarDateInput.js">
/***********************************************
* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
* Script featured on and available at http://www.dynamicdrive.com
* Keep this notice intact for use.
***********************************************/
</script></head>
<?
	if($Nom)
	{
		$Pseudo_Reserve = false;
		$con = dbconnecti();
		$result = mysqli_query($con, "SELECT COUNT(*) FROM Lieu WHERE Nom='$Nom'");
		if($result) 
		{
			$resultat=mysqli_fetch_row($result);
			if($resultat[0])
			{
				$Pseudo_Reserve = true;
			}
		}
		mysqli_close($con);		
		if(!preg_match("#^[[:alpha:]çéèêüöëêûôùîï'\- ]+$#", $Nom) or strlen($Nom) < 3)
		{
			$Pseudo_Reserve = true;
			echo "Le nom du lieu n'est pas valide ou est déjà utilisé!<br>Le nom du lieu doit comporter au moins 3 lettres, et éventuellement un espace.";
		}		
		if(!is_numeric($Longitude))
		{
			$Pseudo_Reserve = true;
			echo "La Longitude doit être un nombre.";				
		}
		if(!is_numeric($Latitude))
		{
			$Pseudo_Reserve = true;
			echo "La Latitude doit être un nombre.";				
		}
		/*if(!empty($Pilote) and !empty($Pays) and !empty($Unit) and !empty($Engagement) and !empty($Vic))
		{*/
			if(!$Pseudo_Reserve)
			{
				$Port_level = 0;
				if($Port)
				{
					$Port_Ori = 100;
					$Port = 100;
					$Port_level = 1;
				}
				if($Pont)
				{
					$Pont_Ori = 100;
					$Pont = 100;
				}
				if($Gare)
				{
					$NoeudF = 100;
					$NoeudF_Ori = 100;
				}
				if($Piste)
				{
					$QualitePiste = 100;
					$Tour = 100;
					$LongPiste = 800;
				}
				$query="INSERT INTO Lieu (Nom,Longitude,Latitude,Pays,Occupant,Zone,Map,DefenseAA,DefenseAA_temp,NoeudR,NoeudF,NoeudF_Ori,Pont_Ori,Pont,Port_Ori,Port,Port_level,BaseAerienne,Base_Ori,QualitePiste,Tour,LongPiste,LongPiste_Ori,Flag)";
				$query.="VALUES ('$Nom','$Longitude','$Latitude','$Pays','$Pays','$Zone','$Map',2,2,'$Route','$NoeudF','$NoeudF_Ori','$Pont_Ori','$Pont','$Port_Ori','$Port','$Port_level','$Piste','$Piste','$QualitePiste','$Tour','$LongPiste','$LongPiste','$Pays')";
				$con = dbconnecti();
				$ok=mysqli_query($con, $query);
				if($ok)
				{
					$mes.="Lieu créé avec succès!";
					echo "<p>Lieu créé avec succès!<br>";
					$ins_id = mysqli_insert_id($con);
				}
				else
				{
					$mes.="Erreur de création de Lieu ".mysqli_error($con);
					echo "<p>Erreur de création de Lieu !</p>";
				}
				mysqli_close($con);
				if($occupation == 1 and $ins_id)
				{
					$query="INSERT INTO Event_Historique (Nom,Date,Type,Lieu,Pays)";
					$query.="VALUES ('Occupation','$Occuper','40','$ins_id','$occupationp')";
					$con = dbconnecti();
					$ok=mysqli_query($con, $query);
					if($ok)
						$mes.="Date d'occupation encodée avec succès!";
					else
						$mes.="Erreur d'encodage de la date d'occupation <br>".mysqli_error($con);
				}
				if($liberation == 1 and $ins_id)
				{
					$query="INSERT INTO Event_Historique (Nom,Date,Type,Lieu,Pays)";
					$query.="VALUES ('Occupation','$Liberer','40','$ins_id','$liberationp')";
					$con = dbconnecti();
					$ok=mysqli_query($con, $query);
					if($ok)
						$mes.="Date de libération encodée avec succès!";
					else
						$mes.="Erreur d'encodage de la date de libération <br>".mysqli_error($con);
				}
				exit;
			}
	}
	elseif($PlayerID != 1)
	{
		$Date=date('Y-m-d G:i');
		$IP = $_SERVER['REMOTE_ADDR'];
		$query="INSERT INTO Encodeurs (PlayerID, Date, IP)";
		$query.="VALUES ('$PlayerID','$Date','$IP')";
		$con = dbconnecti();
		$ok=mysqli_query($con, $query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg.='Erreur insert '.mysqli_error($con);
			mail ('binote@hotmail.com', 'Aube des Aigles: Encodeur Insert Error' , $msg);
		}
	}
	?>
	<h1>Ajout de lieu</h1>
	<form action="index.php?view=db_lieu_add" method="post">
	<input type='hidden' name='country' value="<?echo $Pays;?>">
	<table class='table'>
		<tr>
			<th>Nom du lieu</th>
			<td colspan="2" align="left">
				<input type="text" name="nom" size="50">
			</td>
			<th>Nation d'origine</th>
			<td align="left">
				<select name='country'>
				<? DoUniqueSelect("Pays", "Pays_ID", "Nom");?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Longitude (décimale)</th>
			<td align="left"><input type="number" name="lon" size="5" maxlength="5" step="any" /></td>
			<th>Latitude (décimale)</th>
			<td align="left"><input type="number" name="lat" size="5" maxlength="5" step="any" /></td>
		</tr>
		<tr>
			<th>Terrain</th>
			<td align="left">
				<select name='zone'>
					<option value="0">Plaine</option>
					<option value="1">Colline</option>
					<option value="2">Forêt</option>
					<option value="3">Colline boisée</option>
					<option value="4">Montagne</option>
					<option value="5">Montagne boisée</option>
					<option value="6">Maritime</option>
					<option value="7">Urbaine</option>
					<option value="8">Désert</option>
					<option value="9">Jungle</option>
					<option value="11">Marécage</option>
				</select>
			</td>
			<th>Image</th>
			<td align="left">
				<select name='map'>
					<option value="0">Campagne</option>
					<option value="1">Village</option>
					<option value="2">Aérodrome</option>
					<option value="3">Village et aérodrome</option>
					<option value="4">Ville</option>
					<option value="5">Ville et aérodrome</option>
					<option value="6">Ville fluviale</option>
					<option value="7">Fortification</option>
					<option value="8">Ville portuaire</option>
					<option value="9">Zone industrielle</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Port</th>
			<td align="left">
				<select name='port'>
					<option value="0">Non</option>
					<option value="1">Oui</option>
				</select>
			</td>
			<th>Aérodrome</th>
			<td align="left">
				<select name='piste'>
					<option value="0">Aucun</option>
					<option value="3">Piste en herbe/terre</option>
					<option value="1">Piste en dur</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Noeud ferroviaire</th>
			<td align="left">
				<select name='gare'>
					<option value="0">Non</option>
					<option value="1">Oui</option>
				</select>
			</td>
			<th>Noeud routier</th>
			<td align="left">
				<select name='route'>
					<option value="0">Non</option>
					<option value="1">Oui</option>
				</select>
			</td>
			<th>Pont</th>
			<td align="left">
				<select name='pont'>
					<option value="0">Non</option>
					<option value="1">Oui</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Occupation</th>
			<td align="left"><select name='occupationp'>
				<? echo DoUniqueSelect("Pays", "Pays_ID", "Nom");?>
			</select></td>
			<td><script>DateInput('occuper', true, 'YYYY-MM-DD', '1940-10-01')</script></td>
			<td>
			<Input type='Radio' name='occupation' value='0' checked>- Non<br>
			<Input type='Radio' name='occupation' value='1'>- Oui<br>
			</td>
		</tr>
		<tr>
			<th>Libération</th>
			<td align="left"><select name='liberationp'>
				<? echo DoUniqueSelect("Pays", "Pays_ID", "Nom");?>
			</select></td>
			<td><script>DateInput('liberer', true, 'YYYY-MM-DD', '1944-07-01')</script></td>
			<td>
			<Input type='Radio' name='liberation' value='0' checked>- Non<br>
			<Input type='Radio' name='liberation' value='1'>- Oui<br>
			</td>
		</tr>
	</table>
	<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?
}
else
	echo "Vous n'avez pas le droit d'accéder à cette page!";
?>