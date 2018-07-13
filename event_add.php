<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID=$_SESSION['PlayerID'];
$Encodage=GetData("Joueur","ID",$PlayerID,"Encodage");
if($Encodage ==2)
{
$Pays=Insec($_POST['country']);
$Nom=Insec($_POST['nom']);
$Lieu=Insec($_POST['lieu']);
$Mission=Insec($_POST['mission']);
$Unite=Insec($_POST['unite']);
$Date=Insec($_POST['datee']);
$Front=Insec($_POST['front']);
?>
<head>
<script type="text/javascript" src="calendarDateInput.js">
/***********************************************
* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
* Script featured on and available at http://www.dynamicdrive.com
* Keep this notice intact for use.
***********************************************/
</script>
</head>
<?
	if($Nom)
	{
		$query="INSERT INTO Event_Historique (Nom,Date,Type,Lieu,Pays,Unite,Avion_Nbr,Type_Mission)";
		$query.="VALUES ('$Nom','$Date',1,'$Lieu','$Pays','$Unite','$Front','$Mission')";
		$con=dbconnecti();
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if($ok)
		{
			$mes.="Evènement historique créé avec succès!";
			echo "<p>Evènement historique créé avec succès!<br>";
		}
		else
		{
			$mes.="Erreur de création d'évènement historique : ".mysqli_error($con);
			echo "<p>Erreur de création d'évènement historique !</p>";
		}
		exit;
	}
	elseif($PlayerID !=1)
	{
		$Date=date('Y-m-d G:i');
		$IP=$_SERVER['REMOTE_ADDR'];
		$query="INSERT INTO Encodeurs (PlayerID, Date, IP)";
		$query.="VALUES ('$PlayerID','$Date','$IP')";
		$con=dbconnecti(2);
		$ok=mysqli_query($con,$query);
		mysqli_close($con);
		if(!$ok)
		{
			$msg.='Erreur insert '.mysqli_error($con);
			mail('binote@hotmail.com','Aube des Aigles: Encodeur Insert Error',$msg);
		}
		//$Pays_all=DoUniqueSelect("Pays","Pays_ID","Nom");
	}
	?>
	<div align="center">
	<form action="index.php?view=db_event_add" method="post">
	<input type='hidden' name='country' value="<?echo $Pays;?>">
	<table border="1" cellspacing="2" cellpadding="2" bgcolor='#ECDDC1'>
		<tr><td colspan="10" class="TitreBleu_bc">Ajout d'évènement historique</td></tr>
		<tr>
			<th>Front</th>
			<td align="left">
				<select name='front'>
					<option value='0'>Ouest</option>
					<option value='1'>Est</option>
					<option value='2'>Méditerranéen</option>
					<option value='3'>Pacifique</option>
				</select>
			</td>
			<th title='Vérifiez la nation occupant le lieu à la date choisie'>Lieu</th>
			<td align="left">
				<select name='lieu'>
				<? DoUniqueSelect("Lieu","ID","Nom",2000,"Nom");?>
				</select>
			</td>
			<th title='Une seule ligne. Evitez les apostrophes. Ne pas oublier la mission entre parenthèses!'>Nom de l'évènement</th>
			<td colspan="5" align="left">
				<input type="text" name="nom" size="50" maxlength="50">
			</td>
		</tr>
		<tr>
			<th title='Ne pas sélectionner de nation non jouable'>Nation</th>
			<td align="left">
				<select name='country'>
				<? DoUniqueSelect("Pays","Pays_ID","Nom",20,"Nom");?>
				</select>
			</td>
			<th title='Ne pas sélectionner Entrainement'>Type d'unité</th>
			<td align="left">
				<select name='unite'>
				<? DoUniqueSelect("Avion_Type","ID","Type");?>
				</select>
			</td>
			<th title='Vérifiez la correspondance des missions pour le type d unité choisi'>Mission</th>
			<td align="left">
				<select name='mission'>
					<option value='1'>Appui rapproché</option>
					<option value='6'>Attaque au sol</option>
					<option value='11'>Attaque de navire</option>
					<option value='12'>Bombardement naval</option>
					<option value='2'>Bombardement tactique</option>
					<option value='8'>Bombardement stratégique de jour</option>
					<option value='16'>Bombardement stratégique de nuit</option>
					<option value='3'>Chasse libre</option>
					<option value='17'>Chasse de nuit</option>
					<option value='4'>Escorte</option>
					<option value='21'>Marquage de cible</option>
					<option value='14'>Mouillage de mines</option>
					<option value='24'>Parachutage de jour</option>
					<option value='25'>Parachutage de nuit</option>
					<option value='7'>Patrouille défensive</option>
					<option value='23'>Ravitaillement</option>
					<option value='5'>Reconnaissance tactique</option>
					<option value='15'>Reconnaissance stratégique</option>
					<!--<option value='26'>Supériorité aérienne</option>-->
					<option value='13'>Torpillage</option>
				</select>
			</td>
			<th>Date</th>
			<td><script>DateInput('datee', true, 'YYYY-MM-DD', '1940-10-01')</script></td>
		</tr>
	</table><hr>
	Passez la souris sur les intitulés afin de prendre note des remarques AVANT de valider !<br>
	<input type='Submit' value='VALIDER' onclick='this.disabled=true;this.form.submit();'>
	</form></div><hr>
<?
include_once('./historique.php');
}
else
	echo "Vous n'avez pas le droit d'accéder à cette page!";
?>