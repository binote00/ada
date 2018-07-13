<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID=$_SESSION['PlayerID'];
$Encodage=GetData("Joueur","ID",$PlayerID,"Admin");
if($Encodage >0)
{
$Pays=Insec($_POST['country']);
$Lieu=Insec($_POST['lieu']);
$Unite=Insec($_POST['unite']);
$Avion=Insec($_POST['avion']);
$Avion_Nbr=Insec($_POST['avion_nbr']);
$Date=Insec($_POST['datee']);
$Type=Insec($_POST['Typee']);
$Mode=Insec($_POST['Mode']);
$country=Insec($_POST['Nation']);
?>
<head><script type="text/javascript" src="calendarDateInput.js">
/***********************************************
* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
* Script featured on and available at http://www.dynamicdrive.com
* Keep this notice intact for use.
***********************************************/
</script></head>
<?
	if($Date)
	{
		if($Type ==21)
			$Titre="Renfort";
		elseif($Type ==40)
			$Titre="Occupation";
		elseif($Type ==41)
		{
			$con=dbconnecti();
			$Antidate=mysqli_result(mysqli_query($con,"SELECT Date FROM Event_Historique WHERE Type=40 AND Lieu='$Lieu' AND Pays='$Pays'"),0);
			mysqli_close($con);
			if($Antidate >$Date)
				echo "Attention, la date d'occupation de ce lieu est postérieure à la date encodée !";
			$Titre="Mouvement";
		}
		elseif($Type == 43)
			$Titre="Alliance";
		elseif($Type == 50)
			$Titre="Nouvel Avion";
		elseif($Type == 51)
			$Titre="Nouvelle Unité";
		elseif($Type == 52)
			$Titre="Unité Dissoute";
		elseif($Type == 53)
			$Titre="Unité Renommée";
		elseif($Type == 54)
			$Titre="Unité Changée de Type";
		elseif($Type == 55)
			$Titre="Piste Améliorée";
		elseif($Type == 56)
			$Titre="Production Transférée";
		else
			$Titre="Error";			
		if($Type ==41)
		{
			$Nation=GetData("Lieu","ID",$Lieu,"Pays");
			if($Pays ==1 and ($Nation == 6 or $Nation == 15 or $Nation == 18 or $Nation == 19))
				$Pays=$Nation;
			elseif($Pays == 2 and ($Nation == 3 or $Nation == 5 or $Nation == 7))
				$Pays=$Nation;
			elseif($Pays == 4 and ($Nation == 3 or $Nation == 5))
				$Pays=$Nation;
			elseif($Pays == 6 and ($Nation == 1 or $Nation == 8 or $Nation == 15 or $Nation == 18 or $Nation == 19))
				$Pays=$Nation;
			elseif($Pays == 7 and ($Nation == 2 or $Nation == 3 or $Nation == 4 or $Nation == 5))
				$Pays=$Nation;
		}			
		if(!$Avion)
			$Avion=0;
		if(!$Avion_Nbr)
			$Avion_Nbr=0;
		if(!$Lieu)
			$Lieu=0;
		if(!$Unite)
			$Unite=0;
		$query="INSERT INTO Event_Historique (Nom,Date,Type,Lieu,Pays,Unite,Avion,Avion_Nbr)";
		$query.="VALUES ('$Titre','$Date','$Type','$Lieu','$Pays','$Unite','$Avion','$Avion_Nbr')";
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
			echo "<br>".$Titre." / ".$Date." / ".$Mode." / ".$Lieu." / ".$Pays." / ".$Unite." / ".$Avion." / ".$Avion_Nbr;
			echo "<p>Erreur de création d'évènement historique !</p>";
		}
		echo "<br><a title='Retour au menu' href='index.php?view=db_event_h_menu'>Retour au menu</a>";
	}
	elseif($Mode)
	{
		if($Mode ==21)
			$Titre="Renfort";
		elseif($Mode == 40)
			$Titre="Occupation";
		elseif($Mode == 41)
			$Titre="Mouvement";
		elseif($Mode == 43)
			$Titre="Alliance";
		elseif($Mode == 50)
			$Titre="Nouvel Avion";
		elseif($Mode == 51)
			$Titre="Nouvelle Unité";
		elseif($Mode == 52)
			$Titre="Unité Dissoute";
		elseif($Mode == 53)
			$Titre="Unité Renommée";
		elseif($Mode == 54)
			$Titre="Unité Changée de Type";
		elseif($Mode == 55)
			$Titre="Piste Améliorée";
		elseif($Mode == 56)
			$Titre="Production Transférée";
		else
			$Titre="Error";
	?>
	<div align="center">
	<form action="index.php?view=db_event_h_add" method="post">
	<input type='hidden' name='country' value="<?echo $country;?>">
	<input type='hidden' name='Typee' value="<?echo $Mode;?>">
	<table border="1" cellspacing="2" cellpadding="2" bgcolor='#ECDDC1'>
		<tr><td colspan="10" class="TitreBleu_bc">Ajout d'évènement historique</td></tr>
		<tr><th>Nation</th><td align="left"><Input type='Radio' name='country' value='<?echo $country;?>' disabled><?echo GetData("Pays","Pays_ID",$country,"Nom");?><br></td>
		<th>Nom</th><td align="left"><Input type='Radio' name='Typee' value='<?echo $Mode;?>' disabled><?echo $Titre;?><br></td>
		</tr>
		<tr>
			<?if($Mode !=50 and $Mode !=53 and $Mode !=54){?>
			<th>Lieu</th>
			<td align="left">
				<select name='lieu'>
				<? 
					if($Mode ==21)
						echo "<option value='0'>Toute l'unité</option><option value='1'>Flight/Staffel 1</option><option value='2'>Flight/Staffel 2</option><option value='3'>Flight/Staffel 3</option>";
					else
						DoUniqueSelect("Lieu","ID","Nom",3000,"Nom");
				?>
				</select>
			</td>
			<?}if($Mode !=40 and $Mode !=50 and $Mode !=55 and $Mode !=56){?>
			<th>Unité</th>
			<td align="left">
				<select name='unite'>
				<?	$con=dbconnecti();
					$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Unit WHERE Pays='$country' ORDER BY Nom ASC");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
						{
							$Units.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
						}
						mysqli_free_result($result);
						unset($data);
					}
					echo $Units;
				?>
				</select>
			</td>
			<?}if($Mode ==55){?>
			<th>Longueur</th>
			<td align="left">
				<select name='avion'>
					<option value='800'>800</option>
					<option value='1000'>1000</option>
					<option value='1200'>1200</option>
					<option value='1400'>1400</option>
					<option value='1600'>1600</option>
					<option value='1800'>1800</option>
					<option value='2000'>2000</option>
				</select>
			</td>
			<th>Revêtement</th>
			<td align="left">
				<select name='avion_nbr'>
					<option value='1'>Beton/Asphalte</option>
					<option value='3'>Herbe/Terre</option>
				</select>
			</td>
			<?}elseif($Mode ==43){?>
			<th>Faction</th>
			<td align="left">
				<select name='avion_nbr'>
					<option value='1'>Axe</option>
					<option value='2'>Alliés</option>
				</select>
			</td>
			<?}elseif($Mode ==50){?>
			<th>Avion</th>
			<td align="left">
				<select name='avion'>
				<?	$con=dbconnecti();
					$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Avion WHERE Pays='$country' ORDER BY Nom ASC");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
						{
							$Avions.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
						}
						mysqli_free_result($result);
						unset($data);
					}
					echo $Avions;?>
				</select>
			</td>
			<?}elseif($Mode ==53){?>
			<th>Nouvelle Unité</th>
			<td align="left">
				<select name='avion'>
				<? echo $Units;?>
				</select>
			</td>
			<?}elseif($Mode ==54){?>
			<th>Type</th>
			<td align="left">
				<select name='avion_nbr'>
					<option value='1'>Chasse</option>
					<option value='2'>Bombardement</option>
					<option value='3'>Reco</option>
					<option value='4'>Chasse lourde</option>
					<option value='6'>Transport</option>
					<option value='7'>Attaque</option>
					<option value='9'>Patrouille maritime</option>
					<option value='10'>Embarqué</option>
					<option value='12'>Chasse embarquée</option>
				</select>
			</td>
			<?}elseif($Mode ==21 or $Mode ==50){?>
			<th>Avion</th>
			<td align="left">
				<select name='avion'>
				<? DoUniqueSelect("Avion","ID","Nom",1000,"Nom");?>
				</select>
			</td>
			<th>Avion Nbr</th>
			<td align="left">
				<select name='avion_nbr'>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='8' selected>8</option>
					<option value='12'>12</option>
				</select>
			</td>
			<?}?>
			<th>Date</th>
			<td><script>DateInput('datee', true, 'YYYY-MM-DD', '1942-01-01')</script></td>
		</tr>
	</table>
	<hr>
	<input type='Submit' value='VALIDER' onclick='this.disabled=true;this.form.submit();'>
	</form>
	</div>
<?
	}
	else
	{
		echo "Erreur : Aucun type sélectionné !";
		echo "<br><a title='Retour au menu' href='index.php?view=db_event_h_menu'>Retour au menu</a>";
	}	
}
else
	echo "Vous n'avez pas le droit d'accéder à cette page!";
?>