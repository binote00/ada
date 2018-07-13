<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID=$_SESSION['PlayerID'];
$Encodage=GetData("Joueur","ID",$PlayerID,"Encodage");
if($Encodage >0)
{
$Pays=Insec($_POST['country']);
$Pilote=Insec($_POST['pilote']);
$Pilote_nom=Insec($_POST['nom']);
$Unit=Insec($_POST['unite']);
$Avancement=Insec($_POST['grade']);
$Vic=Insec($_POST['victoires']);
$Engagement=Insec($_POST['engagement']);
$Retrait=Insec($_POST['retrait']);
$Mutation1=Insec($_POST['mutation1']);
$Mutation2=Insec($_POST['mutation2']);
$Muter1=Insec($_POST['muter1']);
$Muter2=Insec($_POST['muter2']);
$Mutation_unit1=Insec($_POST['mutation_unit1']);
$Mutation_unit2=Insec($_POST['mutation_unit2']);
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
	if($Pilote and $Retrait)
	{
		/*if(!empty($Pilote) and !empty($Pays) and !empty($Unit) and !empty($Engagement) and !empty($Vic))
		{*/
			if(!preg_match("#^[[:alpha:]äçéèêüöëêûôùîï'\- ]+$#",$Pilote_nom) or strlen($Pilote_nom) <7)
			//elseif(!preg_match("#^[[:alpha:]']+$#", $Nom))
			{
				echo "Le nom de votre pilote n'est pas valide ou est déjà utilisé!<br>Le nom du pilote doit comporter au moins 6 lettres, et éventuellement un espace entre le prénom et le nom.";
			}
			elseif(strval(intval($Vic)) !=strval($Vic))
			{
				echo $Vic."<br>";
				echo "Le nombre de victoires doit être un nombre.";				
			}
			else
			{
				if($Engagement >"1940-05-10")
					$Actif=0;
				else
					$Actif=1;
				$Pilotage=200+$Vic;
				if($Pilotage >250)$Pilotage=250;
				$Skill=150+$Vic;
				if($Avancement ==16) 
					$Grade=1000000;
				elseif($Avancement == 15)
					$Grade=500000;
				elseif($Avancement == 14)
					$Grade=200000;
				elseif($Avancement == 13)
					$Grade=100000;
				elseif($Avancement == 12)
					$Grade=50000;
				elseif($Avancement == 11)
					$Grade=25000;
				elseif($Avancement == 10)
					$Grade=10000;
				elseif($Avancement == 9)
					$Grade=5000;
				elseif($Avancement == 8)
					$Grade=3000;
				elseif($Avancement == 7)
					$Grade=2000;
				elseif($Avancement == 6)
					$Grade=1500;
				elseif($Avancement == 5)
					$Grade=1000;
				elseif($Avancement == 4)
					$Grade=500;
				elseif($Avancement == 3)
					$Grade=300;
				elseif($Avancement == 2)
					$Grade=200;
				elseif($Avancement == 1)
					$Grade=100;
				else
					$Grade=0;
				$Reput=(1000*$Vic)+($Grade*2);
				$Pilote_nom=ucwords(trim(strtolower($Pilote_nom)));
				$query="UPDATE Pilote_IA SET Nom='$Pilote_nom',Engagement='$Engagement',Actif='$Actif',Pilotage='$Pilotage',Acrobatie='$Skill',Navigation='$Skill',Tactique='$Skill',Tir='$Skill',Vue='$Skill',Reputation='$Reput',Avancement='$Grade',Unit='$Unit',Unit_Ori='$Unit',Victoires='$Vic' WHERE ID='$Pilote'";
				$con=dbconnecti();
				$ok=mysqli_query($con, $query);
				if($ok)
				{
					$mes.="Personnage mis à jour avec succès!";
					echo "<p>Pilote mis à jour avec succès!<br>";
					$ins_id=GetData("Pilote_IA","Nom",$Pilote_nom,"ID");
				}
				else
				{
					$mes.="Erreur de mise à jour  de personnage ".mysqli_error($con);
					echo "<p>Erreur de mise à jour de Personnage !</p>";
				}
				//mysqli_close($con);
				if($Retrait <"1945-09-01" and $ins_id)
				{
					$con=dbconnecti();
					$query="INSERT INTO Event_Historique (Nom,Date,Type,Pays,Unite,Avion)";
					$query.="VALUES ('Mort','$Retrait','65','$Pays','$Unit','$ins_id')";
					$ok=mysqli_query($con,$query);
					if($ok)
					{
						$mes.="Date de retrait encodée avec succès!";
						echo "<p>Date de retrait encodée avec succès!<br>";
					}
					else
					{
						$mes.="Erreur d'encodage de la date de retrait<br>".mysqli_error($con);
						echo "<p>Erreur d'encodage de la date de retrait!</p>";
					}
				}
				if($Mutation1 ==1 and $ins_id)
				{
					$con=dbconnecti();
					$query="INSERT INTO Event_Historique (Nom,Date,Type,Pays,Unite,Avion)";
					$query.="VALUES ('Mutation','$Muter1','31','$Pays','$Mutation_unit1','$ins_id')";
					$ok=mysqli_query($con,$query);
					if($ok)
						$mes.="Date de mutation 1 encodée avec succès!";
					else
						$mes.="Erreur d'encodage de la date de mutation 1 <br>".mysqli_error($con);
				}
				if($Mutation2 ==1 and $ins_id)
				{
					$con=dbconnecti();
					$query="INSERT INTO Event_Historique (Nom,Date,Type,Pays,Unite,Avion)";
					$query.="VALUES ('Mutation','$Muter2','31','$Pays','$Mutation_unit2','$ins_id')";
					$ok=mysqli_query($con,$query);
					if($ok)
						$mes.="Date de mutation 2 encodée avec succès!";
					else
						$mes.="Erreur d'encodage de la date de mutation 2 <br>".mysqli_error($con);
				}
				$mes.="<a title='Retour à votre campement' href='index.php?view=db_as&pays=".$Pays."'>Retour à la liste des as</a>";
				echo "<a title='Retour à votre campement' href='index.php?view=db_as&pays=".$Pays."'>Retour à la liste des as</a>";
				exit;
			}
		/*}
		else
		{
			echo "Remplissez tous les champs du formulaire!";
		}*/
	}
	?>
	<div align="center">
	<form action="index.php?view=db_as_modif" method="post">
	<input type='hidden' name='country' value="<?echo $Pays;?>">
	<input type='hidden' name='pilote' value="<?echo $Pilote;?>">
	<table border="1" cellspacing="2" cellpadding="2" bgcolor='#ECDDC1'>
		<tr><td colspan="8" class="TitreBleu_bc">Modification d'as historique</td></tr>
		<tr>
			<th>Nom du pilote</th>
			<td align="left">
				<input type="text" title="Le nom du pilote ne peut comporter que des lettres et éventuellement un espace entre le prénom et le nom" name="nom" size="50" value="<?echo $Pilote_nom;?>">
			</td>
			<th>Nation</th>
			<td align="left">
				<?echo GetPays($Pays);?>
			<!--<select name='country'>
					<option value="1">Allemagne</option>
					<option value="2">Angleterre</option>
					<option value="3">Belgique</option>
					<option value="4">France</option>
					<option value="6">Italie</option>
					<option value="9">Japon</option>
					<option value="8">URSS</option>
					<option value="7">USA</option>
				</select>-->
			</td>
		</tr>
		<tr>
			<th>Date d'engagement</th><td><script>DateInput('engagement', true, 'YYYY-MM-DD', '<?echo $Engagement;?>')</script></td>
			<th>Grade</th>
			<td align="left">
				<select name='grade'>
					<option value="1" <?if($Avancement >= GetAvancement(0, $Pays, 0)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 0); echo $Grade[0];?></option>
					<option value="2" <?if($Avancement >= GetAvancement(0, $Pays, 1)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 1); echo $Grade[0];?></option>
					<option value="3" <?if($Avancement >= GetAvancement(0, $Pays, 2)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 2); echo $Grade[0];?></option>
					<option value="4" <?if($Avancement >= GetAvancement(0, $Pays, 3)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 3); echo $Grade[0];?></option>
					<option value="5" <?if($Avancement >= GetAvancement(0, $Pays, 4)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 4); echo $Grade[0];?></option>
					<option value="6" <?if($Avancement >= GetAvancement(0, $Pays, 5)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 5); echo $Grade[0];?></option>
					<option value="7" <?if($Avancement >= GetAvancement(0, $Pays, 6)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 6); echo $Grade[0];?></option>
					<option value="8" <?if($Avancement >= GetAvancement(0, $Pays, 7)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 7); echo $Grade[0];?></option>
					<option value="9" <?if($Avancement >= GetAvancement(0, $Pays, 8)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 8); echo $Grade[0];?></option>
					<option value="10" <?if($Avancement >= GetAvancement(0, $Pays, 9)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 9); echo $Grade[0];?></option>
					<option value="11" <?if($Avancement >= GetAvancement(0, $Pays, 10)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 10); echo $Grade[0];?></option>
					<option value="12" <?if($Avancement >= GetAvancement(0, $Pays, 11)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 11); echo $Grade[0];?></option>
					<option value="13" <?if($Avancement >= GetAvancement(0, $Pays, 12)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 12); echo $Grade[0];?></option>
					<option value="14" <?if($Avancement >= GetAvancement(0, $Pays, 13)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 13); echo $Grade[0];?></option>
					<option value="15" <?if($Avancement >= GetAvancement(0, $Pays, 14)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 14); echo $Grade[0];?></option>
					<option value="16" <?if($Avancement >= GetAvancement(0, $Pays, 15)){echo "selected";}?>><?$Grade=GetAvancement(0, $Pays, 15); echo $Grade[0];?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Date de retrait</th><td><script>DateInput('retrait', true, 'YYYY-MM-DD', '1945-09-01')</script></td>
			<th>Victoires</th>
			<td align="left">
				<input type="text" size="2" maxlength="2" name="victoires" value="<?echo $Vic;?>">
			</td>
		</tr>
		<tr>
			<th>Unité de départ</th>
			<td align="left">
			<select name='unite'>
			<?if(!$Unit){?>
				<?DoSelect("Unit", "ID", "Nom", "Nom", "Pays", $Pays);?>
			<?}else{?>
				<option value="<?echo $Unit;?>"><?echo GetData("Unit","ID",$Unit,"Nom");?></option>
			<?}?>
			</select>
			</td>
		</tr>
		<tr><th colspan="10" bgcolor="lightyellow">Mutations</th></tr>
		<tr>
			<th>Mutation 1</th>
			<td align="left"><select name='mutation_unit1'>
				<?DoSelect("Unit", "ID", "Nom", "Nom", "Pays", $Pays);?>
			</select></td>
			<td><script>DateInput('muter1', true, 'YYYY-MM-DD', '1940-07-01')</script></td>
			<td>
			<Input type='Radio' name='mutation1' value='0' checked>- Non<br>
			<Input type='Radio' name='mutation1' value='1'>- Oui<br>
			</td>
		</tr>
		<tr>
			<th>Mutation 2</th>
			<td align="left"><select name='mutation_unit2'>
				<?DoSelect("Unit", "ID", "Nom", "Nom", "Pays", $Pays);?>
			</select></td>
			<td><script>DateInput('muter2', true, 'YYYY-MM-DD', '1943-01-01')</script></td>
			<td>
			<Input type='Radio' name='mutation2' value='0' checked>- Non<br>
			<Input type='Radio' name='mutation2' value='1'>- Oui<br>
			</td>
		</tr>
	</table>
	<hr><input type='Submit' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></form></div>	
	<div><table><tr><th colspan='2'>Mutations existantes</th></tr>
<?
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Date,Unite,Type FROM Event_Historique WHERE Type IN (31,65) AND Avion='$Pilote'");
	mysqli_close($con);
	while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		if($data['Type'] ==65)
			echo '<tr><td>'.$data['Date'].'</td><td>Décès</td></tr>';
		else
			echo '<tr><td>'.$data['Date'].'</td><td>'.GetData("Unit","ID",$data['Unite'],"Nom").'</td></tr>';
	}
	echo "</table></div>";
}
else
	echo "Vous n'avez pas le droit d'accéder à cette page!";
?>