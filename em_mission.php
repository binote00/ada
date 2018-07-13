<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $Admin)
	{
        if($_SESSION['msg_em'])
            $Alert = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_em'].'</div>';
        $_SESSION['msg_em'] = false;
        $Coord=GetCoord($Front,$country);
		$Lat_base_min=$Coord[0];
		$Lat_base_max=$Coord[1];
		$Long_base_min=$Coord[2];
		$Long_base_max=$Coord[3];
		if($G_Treve or ($G_Treve_Med and $Front ==2) or ($G_Treve_Est_Pac and ($Front ==1 or $Front ==4 or $Front ==3)))
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max' AND Pays='$country' AND Flag='$country' ORDER BY Nom ASC";
		else
			$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max' ORDER BY Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		$result2=mysqli_query($con,"SELECT DISTINCT * FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$Lieux.="<option value=".$data['ID'].">".$data['Nom']."</option>";
			}
			mysqli_free_result($result);
		}		
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
			{
				$Briefing=$data['Briefing'];
				$Type_Mission1=$data['Type_Mission1'];
				$Type_Mission2=$data['Type_Mission2'];
				$Type_Mission3=$data['Type_Mission3'];
				$Type_Mission4=$data['Type_Mission4'];
				$Type_Mission5=$data['Type_Mission5'];
				$Type_Mission6=$data['Type_Mission6'];
				$Type_Mission7=$data['Type_Mission7'];
				$Type_Mission8=$data['Type_Mission8'];
				$Type_Mission9=$data['Type_Mission9'];
				$Type_Mission10=$data['Type_Mission10'];
				$Type_Mission12=$data['Type_Mission12'];
				$Lieu_Mission1=$data['Lieu_Mission1'];
				$Lieu_Mission2=$data['Lieu_Mission2'];
				$Lieu_Mission3=$data['Lieu_Mission3'];
				$Lieu_Mission4=$data['Lieu_Mission4'];
				$Lieu_Mission5=$data['Lieu_Mission5'];
				$Lieu_Mission6=$data['Lieu_Mission6'];
				$Lieu_Mission7=$data['Lieu_Mission7'];
				$Lieu_Mission8=$data['Lieu_Mission8'];
				$Lieu_Mission9=$data['Lieu_Mission9'];
				$Lieu_Mission10=$data['Lieu_Mission10'];
				$Lieu_Mission12=$data['Lieu_Mission12'];
				$Co_Heure_Mission=$data['Co_Heure_Mission'];
				$Co_Lieu_Mission=$data['Co_Lieu_Mission'];
			}
			mysqli_free_result($result2);
			/*if($Co_Heure_Mission and $Co_Lieu_Mission)
				$Coordo_actu=GetData("Lieu","ID",$Co_Lieu_Mission,"Nom")." à ".$Co_Heure_Mission."h";	
			else
				$Coordo_actu="Aucune";*/
			echo $Alert."<h2>Missions de front</h2><table class='table table-striped'>
			<thead><tr><th>Unités</th><th>Mission actuelle</th><th>Objectif</th><th><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'> Changer de mission</th><th>Annuler</th></tr></thead>";			
			for($iu=1;$iu <13;$iu++)
			{
				if($iu !=8 and $iu !=11)
				{
					$TM="Type_Mission".$iu;
					$LM="Lieu_Mission".$iu;
					echo "<tr><td>".GetAvionType($iu)."</td><td>".GetMissionType($$TM)."</td><td>".GetData("Lieu","ID",$$LM,"Nom")."</td>
					<td><form action='index.php?view=em_mission1' method='post'><input type='hidden' name='type' value='".$iu."'><input type='Submit' value='Changer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td>
					<td><form action='index.php?view=em_mission1' method='post'><input type='hidden' name='type' value='".$iu."'><input type='hidden' name='reset' value='3'><input type='Submit' value='Annuler' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
					</tr>";
				}
			}
			echo "</table>";
		}
		echo "<form action='index.php?view=em_mission1' method='post'><input type='hidden' name='reset' value='1'>
		<input type='Submit' value='Annuler toutes les missions de front' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
		/*echo "<form action='index.php?view=em_mission1' method='post'>
				<input type='hidden' name='reset' value='2'>
				<table class='table'>
				<thead><tr><th>Coordination actuelle</th><th colspan='3'>Changer</th></tr></thead>
				<tr><td>".$Coordo_actu."</td><td><select name='lieu' class='form-control' style='width: 200px'>	
				".$Lieux."</select></td>
				<td><select name='heure' class='form-control' style='width: 100px'>	
				<option value='6'>6h</option>
				<option value='7'>7h</option>
				<option value='8'>8h</option>
				<option value='9'>9h</option>
				<option value='10'>10h</option>
				<option value='11'>11h</option>
				<option value='12'>12h</option>
				<option value='13'>13h</option>
				<option value='14'>14h</option>
				<option value='15'>15h</option>
				<option value='16'>16h</option>
				<option value='17'>17h</option>
				<option value='18'>18h</option>
				<option value='19'>19h</option>
				<option value='20'>20h</option>
				<option value='21'>21h</option>
				<option value='22'>22h</option>
				<option value='23'>23h</option>
				</select></td><td><input type='Submit' value='Changer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr></table></form>";*/		
		echo "<h2>Briefing</h2><form action='index.php?view=em_mission1' method='post'><input type='hidden' name='reset' value='11'>
				<textarea name='Briefing' class='form-control' rows='5' cols='50' maxlength='1000'>".nl2br($Briefing)."</textarea>
				<input type='Submit' value='Valider le Briefing' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'> (1000 caractères max)</form>";
	/*<hr><form action='index.php?view=em_mission1' method='post'><table class='table'>
		<tr><th class="TitreBleu_bc" colspan="2">Choix du type d'unité (1 CT)</th></tr>
			<td align="left"><select name="type" style="width: 100px">	
				<? 	
				//$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Etat >0 GROUP BY Unit.Type ORDER BY Avion_Type.Type ASC";
				if($Front == 3)
					$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Longitude >67 ORDER BY Avion_Type.Type ASC";
				elseif($Front == 2)
					$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Latitude <45 AND Lieu.Longitude <50 ORDER BY Avion_Type.Type ASC";
				elseif($Front == 1)
					$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Latitude >44 AND Lieu.Longitude >13 AND Lieu.Longitude <60 ORDER BY Avion_Type.Type ASC";
				else
					$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Latitude >=45 AND Lieu.Longitude <14 ORDER BY Avion_Type.Type ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						?>
						 <option value="<? echo $data[0];?>"><? echo $data[1];?></option>
						<?
					}
					mysqli_free_result($result);
				}
				?>
		</select></td></tr></table>
	<input type='Submit' value='Définir une mission de front' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>*/
		/*Escortes et couvertures en cours
		echo "<h2>Missions en cours <a href='aide_missions_liste.php' target='_blank'><img src='images/help.png'></a></h2><table class='table table-striped'>				
				<thead><tr>
					<th>Lieu</th>
					<th>Occupant</th>
					<th>Couvertures</th>
					<th>Escortes</th>
					<th>Reco</th>
					<th>Cibles</th>
					<th>Demande</th>
				</tr></thead>";
		$query="SELECT ID,Nom,Occupant,Zone,Recce,
		(SELECT COUNT(*) FROM Pilote WHERE Pilote.Couverture=Lieu.ID AND Lieu.Occupant='$country' AND Pilote.Front='$Front') AS Couverturer,
		(SELECT COUNT(*) FROM Pilote WHERE Pilote.Escorte=Lieu.ID AND Pilote.Pays='$country' AND Pilote.Front='$Front') AS Escorter,
		(SELECT COUNT(*) FROM Pilote WHERE Lieu.Recce_PlayerID=Pilote.ID AND Pilote.Pays='$country' AND Pilote.Front='$Front') AS Reco,
		(SELECT Unit.Mission_Type_D FROM Unit WHERE Unit.Mission_Lieu_D=Lieu.ID AND Unit.Pays='$country' LIMIT 1) AS Demandes,
		(SELECT COUNT(*) FROM Regiment WHERE Regiment.Lieu_ID=Lieu.ID AND Regiment.Pays<>'$country' AND Regiment.Visible=1) AS PJ_Ground
		FROM Lieu ORDER BY Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			while($Data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				if($Data['Couverturer'] or $Data['Escorter'] or $Data['Demandes'] or $Data['Reco'] or $Data['PJ_Ground'])
				{
					if($Data['Zone'] ==6)
					{
						if($Data['PJ_Ground'])
						{
							$Cible='Unités navales';
							$Recce='Tactique';
						}
						else
							$Recce='Non';
						$Data['Nom']="<span class='text-primary'>".$Data['Nom']."</span>";
					}
					else
					{
						if($Data['Recce'] ==2)
							$Recce='<b>Eclairé</b>';
						elseif($Data['Recce'] ==1)
							$Recce='<b>Oui</b>';
						elseif($Data['PJ_Ground'])
							$Recce='Tactique';
						else
							$Recce='Non';
						if($Data['PJ_Ground'])
							$Cible='Unités terrestres';
					}						
					if(!$Data['PJ_Ground'])
					{
						if($Data['Recce'])
							$Cible='Infrastructures';
						else
							$Cible='Aucune';
					}
					if(!$Data['Escorter'])$Data['Escorter']='Inconnu';
					if(!$Data['Couverturer'])$Data['Couverturer']='Inconnu';
					$Demandes=GetMissionType($Data['Demandes']);
					echo "<tr><td>".$Data['Nom']."</td><td><img src='".$Data['Occupant']."20.gif'></td><td>".$Data['Couverturer']."</td><td>".$Data['Escorter']."</td><td>".$Recce."</td><td>".$Cible."</td><td>".$Demandes."</td></tr>";
				}
			}
			mysqli_free_result($result);
			unset($Data);
		}
		echo "</table>";*/
	}
	else
		PrintNoAccess($country,1);
	/*Compte-rendu de missions
	$Dateref=date('Y-m-d');
	$con=dbconnecti();
	$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE DATEDIFF(Last_Attack,'$Dateref') <8 ORDER BY Nom ASC";
	$result=mysqli_query($con, $query) or die(mysqli_error($con));
	if($result)
	{
		while($data=mysqli_fetch_array($result)) 
		{
			$Lieux=$Lieux."<option value=".$data['ID'].">".$data['Nom']."</option>";
		}
	}
<form action='detail_attaque.php' method='get' target="_blank">
<table class='table'>
	<tr bgcolor="tan"><th>Compte-rendu de bataille</th></tr>
	<tr><td align="left"><select name="id" style="width: 150px"><?echo $Lieux;?></select></td></tr>
	<tr><td align="left"><select name="date" style="width: 150px">
		<option value='0'>Aujourd'hui</option>
		<option value='1'>Hier</option>
		<option value='2'>Avant-hier</option>
		<option value='3'>Il y a 3 jours</option>
		<option value='4'>Il y a 4 jours</option>
		<option value='5'>Il y a 5 jours</option>
		<option value='6'>Il y a 6 jours</option>
		<option value='7'>Il y a 7 jours</option>
	</select></td></tr>
</table><input type='Submit' value='Voir le compte-rendu'></form>
<?*/
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';