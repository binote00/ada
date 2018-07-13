<?/*
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	//include_once('./menu_staff.php');	
	if(($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Rens or $GHQ) and $Front !=12)
	{
		$Action=Insec($_POST['Action']);
		$CT_2=Insec($_POST['CT2']);
		$CT_4=Insec($_POST['CT4']);
		$CT_8=Insec($_POST['CT8']);
		$CT_24=Insec($_POST['CT24']);
		if($Credits >=$CT_2)
		{
			echo "<form action='index.php?view=em_rens2' method='post'>
			<input type='hidden' name='CT2' value='".$CT_2."'>
			<input type='hidden' name='CT4' value='".$CT_4."'>
			<input type='hidden' name='CT8' value='".$CT_8."'>
			<input type='hidden' name='CT24' value='".$CT_24."'>";
			if($Action ==1)
			{
				echo "<h2><img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'> Maquiller le dossier d'un officier</h2>
						<select name='Officier' class='form-control' style='width: 200px'>
							<option value='0' selected>Personne</option>";
				$query="SELECT DISTINCT Pilote.ID,Pilote.Nom,Unit.Nom FROM Pilote,Unit WHERE Pilote.Unit=Unit.ID AND Pilote.Pays='$country' AND Pilote.Front='$Front' AND Pilote.Actif=0 AND Pilote.Hide=0 ORDER BY Pilote.Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						 echo "<option value='".$data[0]."'>".$data[1]." (".$data[2].")</option>";
					}
					mysqli_free_result($result);
				}
				echo "</select>";
			}
			elseif($Action ==2)
			{
				echo "<h2><img src='/images/CT".$CT_8.".png' title='Montant en Crédits Temps que nécessite cette action'> Protéger une unité contre l'espionnage</h2>
						<select name='Unite' class='form-control' style='width: 200px'>
							<option value='0' selected>Aucune</option>";
				$query="SELECT DISTINCT Unit.ID,Unit.Nom FROM Unit WHERE Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Hide=0 ORDER BY Unit.Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						 echo "<option value='".$data[0]."'>".$data[1]."</option>";
					}
					mysqli_free_result($result);
				}
				echo "</select>";
			}
			elseif($Action ==3)
			{
				echo "<h2><img src='/images/CT".$CT_4.".png' title='Montant en Crédits Temps que nécessite cette action'> Obtenir le rapport météo d'un lieu contrôlé par une puissance étrangère</h2>
				<select name='Ville_eni' class='form-control' style='width: 200px'>
					<option value='0' selected>Aucune</option>";
				if($Front ==3)
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag<>'$country' AND Longitude >67 ORDER BY Nom ASC";
				elseif($Front ==2)
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag<>'$country' AND Latitude <43 AND Longitude <50 ORDER BY Nom ASC";
				elseif($Front ==1 or $Front == 4)
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag<>'$country' AND Latitude >43 AND Longitude >13 AND Longitude <50 ORDER BY Nom ASC";
				elseif($Front ==5)
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag<>'$country' AND Latitude >55 AND Longitude <60 ORDER BY Nom ASC";
				else
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag<>'$country' AND Latitude >=43 AND Longitude <14 ORDER BY Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						 echo "<option value='".$data[0]."'>".$data[1]."</option>";
					}
					mysqli_free_result($result);
				}
				echo "</select>";
			}
			elseif($Action ==4)
			{
				echo "<h2><img src='/images/CT".$CT_24.".png' title='Montant en Crédits Temps que nécessite cette action'> Enquêter sur un officier étranger</h2>
				<select name='Officier_eni' class='form-control' style='width: 200px'>
					<option value='0' selected>Personne</option>";
				$query="SELECT DISTINCT Pilote.ID,Pilote.Nom FROM Pilote WHERE Pilote.Pays<>'$country' AND Pilote.Front='$Front' AND Pilote.Actif=0 ORDER BY Pilote.Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						?>
						 <option value="<? echo $data[0];?>"> <? echo $data[1];?> </option>
						<?
					}
					mysqli_free_result($result);
				}
				echo "</select>";
			}
			elseif($Action ==5)
			{
				if($country ==9)
					$Pays_list="2,7";
				elseif($country ==1)
					$Pays_list="2,4,7,8";
				elseif($country ==2)
					$Pays_list="1,6,9";
				elseif($country ==4)
					$Pays_list="1,6";
				elseif($country ==6)
					$Pays_list="2,4";
				elseif($country ==7)
					$Pays_list="1,9";
				elseif($country ==8)
					$Pays_list="1,6";
				else
					$Pays_list="3";
				echo "<h2><img src='/images/CT".$CT_24.".png' title='Montant en Crédits Temps que nécessite cette action'> Enquêter sur une unité étrangère</h2>
				<select name='Unite_eni' class='form-control' style='width: 200px'>
					<option value='0' selected>Aucune</option>";
				$query="SELECT DISTINCT Unit.ID,Unit.Nom FROM Unit WHERE Unit.Pays IN(".$Pays_list.") AND Unit.Etat=1 ORDER BY Unit.Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						?>
						 <option value="<? echo $data[0];?>"> <? echo $data[1];?> </option>
						<?
					}
					mysqli_free_result($result);
				}
				echo "</select>";
			}
			elseif($Action ==6)
			{
				echo "<h2><img src='/images/CT".$CT_2.".png' title='Montant en Crédits Temps que nécessite cette action'> Obtenir le rapport météo d'un lieu contrôlé par nous</h2>
				<select name='Ville' class='form-control' style='width: 200px'>
					<option value='0' selected>Aucune</option>";
				if($Front ==3)
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Longitude >67 ORDER BY Nom ASC";
				elseif($Front ==2)
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude <45 AND Longitude <50 ORDER BY Nom ASC";
				elseif($Front ==1 or $Front ==4)
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >43 AND Longitude >13 AND Longitude <50 ORDER BY Nom ASC";
				elseif($Front ==5)
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >55 AND Longitude >-50 AND Longitude <60 ORDER BY Nom ASC";
				else
					$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >45 AND Longitude <14 ORDER BY Nom ASC";
				$con=dbconnecti();
				$result=mysqli_query($con,$query);
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						?>
						 <option value="<? echo $data[0];?>"> <? echo $data[1];?> </option>
						<?
					}
					mysqli_free_result($result);
				}
				echo "</select>";
			}
			elseif($Action ==7)
			{
				if($country ==9)
					$Pays_list="2,7";
				elseif($country ==1)
					$Pays_list="2,4,7,8";
				elseif($country ==2)
					$Pays_list="1,6,9";
				elseif($country ==4)
					$Pays_list="1,6";
				elseif($country ==6)
					$Pays_list="2,4";
				elseif($country ==7)
					$Pays_list="1,9";
				elseif($country ==8)
					$Pays_list="1,6";
				elseif($country ==3 or $country ==5)
					$Pays_list="1";
                elseif($country >14 or $country <21)
                    $Pays_list="8";
                else
                    $Pays_list="36";
				echo '<h2><img src="/images/CT'.$CT_24.'.png" title="Montant en Crédits Temps que nécessite cette action"> Enquêter sur une usine étrangère sur base de photos</h2>
                <div class="alert alert-warning">Seuls les lieux revendiqués par l\'ennemi et reconnus via une reconnaissance stratégique sont disponibles</div>
				<select name="Usine_eni" class="form-control" style="width: 200px">
					<option value="0" selected>Aucune</option>';
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag IN(".$Pays_list.") AND Recce >0 ORDER BY Nom ASC");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						?>
						 <option value="<?=$data[0];?>"><?=$data[1];?></option>
						<?
					}
					mysqli_free_result($result);
				}
				echo '</select>';
			}
			elseif($Action ==8)
			{
				echo "<h2><img src='/images/CT".$CT_8.".png' title='Montant en Crédits Temps que nécessite cette action'> Camoufler un site de production contre l'espionnage</h2>
				<select name='Usine' class='form-control' style='width: 200px'>
					<option value='0' selected>Aucune</option>";
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND TypeIndus<>'' ORDER BY Nom ASC");
				mysqli_close($con);
				if($result)
				{
					while ($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
					{
						?>
						 <option value="<? echo $data[0];?>"> <? echo $data[1];?> </option>
						<?
					}
					mysqli_free_result($result);
				}
				echo "</select>";
			}
			echo "<p><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></p></form>";
		}
		else
			echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";			
	}
	else
		PrintNoAccess($country,1,4);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";