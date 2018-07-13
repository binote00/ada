<?/*
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		$country=$_SESSION['country'];
		include_once('./jfv_include.inc.php');
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_inc_em.php');
		include_once('./menu_em.php');
		//include_once('./menu_staff.php');
		if($OfficierEMID ==$Commandant and $Front !=12) // or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk)
		{
			$Sqn=GetSqn($country);
			$Unit_Type="1,2,3,4,5,6,7,8,9,10,11,12";
			if($Front ==3)
			{
				$query_unit="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND u.Type IN (".$Unit_Type.") AND l.Longitude >67 AND l.Zone<>6 AND l.Tour >49 ORDER BY Nom ASC";
				$query_unit2="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND l.Longitude >67 ORDER BY Nom ASC";
			}
			elseif($Front ==2)
			{
				$query_unit="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND u.Type IN (".$Unit_Type.") AND l.Longitude <45 AND l.Latitude <43 AND l.Zone<>6 AND l.Tour >49 ORDER BY Nom ASC";
				$query_unit2="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country AND u.Etat=1 AND l.Longitude <45 AND l.Latitude <43 ORDER BY Nom ASC";
			}
			elseif($Front == 5)
			{
				$query_unit="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND u.Type IN (".$Unit_Type.") AND l.Longitude <67 AND l.Latitude >60 AND l.Zone<>6 AND l.Tour >49 ORDER BY Nom ASC";
				$query_unit2="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country AND u.Etat=1 AND l.Longitude <67 AND l.Latitude >60 ORDER BY Nom ASC";
			}
			elseif($Front ==1 or $Front ==4)
			{
				if($country ==8)
				{
					$query_unit="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND u.Type IN (".$Unit_Type.") AND l.Longitude >14 AND l.Latitude >35 AND l.Zone<>6 AND l.Tour >49 ORDER BY Nom ASC";
					$query_unit2="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND l.Longitude >14 AND l.Latitude >35 ORDER BY Nom ASC";
				}
				else
				{
					$query_unit="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND u.Type IN (".$Unit_Type.") AND l.Longitude >14 AND l.Latitude >43 AND l.Zone<>6 AND l.Tour >49 ORDER BY Nom ASC";
					$query_unit2="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND l.Longitude >14 AND l.Latitude >43 ORDER BY Nom ASC";
				}
			}
			else
			{
				$query_unit="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND u.Priorite=0 AND l.Longitude <14 AND l.Latitude >=43 AND l.Latitude <60 AND l.Zone<>6 AND l.Tour >49 ORDER BY Nom ASC";
				$query_unit2="SELECT u.ID,u.Nom FROM Unit as u,Lieu as l WHERE u.Base=l.ID AND u.Pays='$country' AND u.Etat=1 AND l.Longitude <14 AND l.Latitude >=43 AND l.Latitude <60 ORDER BY Nom ASC";
			}
			$con=dbconnecti();
			$result_unit=mysqli_query($con,$query_unit);
			$result_unit2=mysqli_query($con,$query_unit2);
			mysqli_close($con);
			if($result_unit)
			{
				while($Data=mysqli_fetch_array($result_unit,MYSQLI_NUM)) 
				{
					$Unite=$Data[0];
					$Unite_Nom=$Data[1];
					$Units.="<option value='".$Unite."'>".$Unite_Nom."</option>";
				}
				mysqli_free_result($result_unit);
				unset($Data);
			}
			if($result_unit2)
			{
				while($Data=mysqli_fetch_array($result_unit2,MYSQLI_NUM)) 
				{
					$Unite=$Data[0];
					$Unite_Nom=$Data[1];
					$Unites.="<option value='".$Unite."'>".$Unite_Nom."</option>";
				}
				mysqli_free_result($result_unit2);
				unset($Data);
			}				
			echo "<h2>Gestion des pilotes</h2>";
			if($Credits >=8){?>
			<form action='index.php?view=em_gestioncdt2' method='post'>
			<table class='table'>
				<thead><tr><th><img src='/images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'> Donner l'ordre de déménager une unité <a href='#' class='popup'><img src='images/help.png'><span>La tour de la base de départ ne doit pas être endommagée à plus de 50%</span></a></th></tr></thead>
					<tr><td><select name="unitet" class='form-control' style='width: 300px'>		
							<option value="0">Aucune</option>
							<?echo $Units;?>
				</select></td></tr>
			</table><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
			<?}else
				echo "<p class='lead'>Déménager une unité sur le front nécessite <img src='/images/CT8.png'></p>";
			if($OfficierEMID ==$Commandant){?>				
			<hr><form action='em_gestioncdt1.php' method='post'>
			<table class='table'>
				<thead><tr><th title="Ce pilote sera muté dans une unité de réserve"><img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'> Muter un pilote vers l'unité école</th></tr></thead>
					<td><select name='Mutation_Cdt' class='form-control' style='width: 300px'>
							<option value="0" selected>Personne</option>
							<?
								$query="SELECT DISTINCT Pilote.ID,Pilote.Nom,Unit.Nom FROM Pilote,Unit WHERE Unit.ID=Pilote.Unit AND Pilote.Pays='$country' AND Pilote.Front='$Front' AND Unit.Type<>8 ORDER BY Pilote.Nom ASC";
								$con=dbconnecti();
								$result=mysqli_query($con,$query);
								mysqli_close($con);
								if($result)
								{
									while($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
									{
										$Cdts.="<option value='".$data[0]."'>".$data[1]." (".$data[2].")"."</option>";
									}
									mysqli_free_result($result);
								}
								echo $Cdts;
							?>
					</select></td></tr>
			</table><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>		
			}
		}
		else
			$No_access=1;
		if($OfficierEMID ==$Commandant and $Front !=12) // or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_EM)
		{
			/if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint)
			//{
				?>
				<h2>Gestion du parc avions</h2>
				<form action='index.php?view=em_gestion1' method='post'>
				<table class='table'><thead><tr><th>Type d'unité</th></tr></thead>
					<tr><td><select name="type" class='form-control' style="width: 200px">		
							<option value="0">Aucun</option>
							<? 	
							if($Front ==3)
								$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Longitude >67 ORDER BY Avion_Type.Type ASC";
							elseif($Front ==2)
								$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Longitude <50 AND Lieu.Latitude <43 ORDER BY Avion_Type.Type ASC";
							elseif($Front ==1)
								$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Longitude >13 AND Lieu.Latitude >41 AND Lieu.Latitude <=50.5 ORDER BY Avion_Type.Type ASC";
							elseif($Front ==4)
								$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Longitude >13 AND Lieu.Latitude >50.5 ORDER BY Avion_Type.Type ASC";
							elseif($Front ==5)
								$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Longitude <60 AND Lieu.Latitude >60 ORDER BY Avion_Type.Type ASC";
							else
								$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Latitude >=43 AND Lieu.Latitude <60 AND Lieu.Longitude <14 ORDER BY Avion_Type.Type ASC";
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
				<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
			<?/*}if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_EM){?>
				<h2>Gestion des infrastructures</h2>
				<form action='index.php?view=em_gestion1' method='post'>
				<table class='table'><thead><tr><th>Lieu</th></tr></thead>
					<tr><td><select name="lieu" class='form-control' style="width: 200px">
							<? 	
							if($Front ==3)
								$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Longitude >67 AND Zone<>6 ORDER BY Nom ASC";
							elseif($Front ==2)
							{
								if($country ==4)
									$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude <41 AND Longitude <50 AND Zone<>6 ORDER BY Nom ASC";
								else
									$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude <45 AND Longitude <50 AND Zone<>6 ORDER BY Nom ASC";
							}
							elseif($Front ==1)
								$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >41 AND Latitude <= 50.5 AND Longitude >13 AND Longitude <67 AND Zone<>6 ORDER BY Nom ASC";
							elseif($Front ==4)
								$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >50.5 AND Longitude >13 AND Longitude <67 AND Zone<>6 ORDER BY Nom ASC";
							elseif($Front ==5)
								$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >58 AND Longitude >-50 AND Longitude <60 AND Zone<>6 ORDER BY Nom ASC";
							else
							{
								if($country ==7)
									$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude <58 AND Longitude <14 AND Zone<>6 ORDER BY Nom ASC";
								else
									$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >=45 AND Latitude <58 AND Longitude <14 AND Zone<>6 ORDER BY Nom ASC";
							}
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
				?></select></td></tr></table><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form><?
			}*/
/*		}
		else
			$No_access+=1;
		if($No_access >1)
			PrintNoAccess($country,1);
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');*/
?>