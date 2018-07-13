<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
if(isset($_SESSION['AccountID']))
{
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		$country=$_SESSION['country'];
		$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
		$con=dbconnecti();	
		$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Adjoint_EM'];
				$Officier_EM=$data['Officier_EM'];
			}
			mysqli_free_result($result2);
		}
		include_once('./menu_em.php');
		//include_once('./menu_staff.php');
		if(($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_EM or $GHQ) and $Front !=12)
		{
			if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint)
			{
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
							{
								if($country ==4)
									$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Longitude <50 AND Lieu.Latitude <41 ORDER BY Avion_Type.Type ASC";
								else
									$query="SELECT DISTINCT Avion_Type.ID,Avion_Type.Type FROM Unit,Lieu,Avion_Type WHERE Unit.Type=Avion_Type.ID AND Unit.Pays='$country' AND Unit.Etat=1 AND Unit.Base=Lieu.ID AND Lieu.Longitude <50 AND Lieu.Latitude <43 ORDER BY Avion_Type.Type ASC";
							}
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
								while ($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
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
			<?}if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_EM){?>
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
									$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude <43 AND Longitude <50 AND Zone<>6 ORDER BY Nom ASC";
							}
							elseif($Front ==1)
								$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >41 AND Latitude <=50.5 AND Longitude >13 AND Longitude <67 AND Zone<>6 ORDER BY Nom ASC";
							elseif($Front ==4)
								$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >50.5 AND Longitude > 13 AND Longitude <67 AND Zone<>6 ORDER BY Nom ASC";
							elseif($Front ==5)
								$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >60 AND Longitude >-50 AND Longitude <60 AND Zone<>6 ORDER BY Nom ASC";
							else
								$query="SELECT DISTINCT ID,Nom FROM Lieu WHERE Flag='$country' AND Latitude >=43 AND Latitude <60 AND Longitude <14 AND Zone<>6 ORDER BY Nom ASC";
							$con=dbconnecti();
							$result=mysqli_query($con,$query);
							mysqli_close($con);
							if($result)
							{
								while ($data=mysqli_fetch_array($result,MYSQLI_NUM)) 
								{
									?>
									 <option value="<? echo $data[0];?>"><? echo $data[1];?></option>
									<?
								}
								mysqli_free_result($result);
							}
				?></select></td></tr></table><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form><?
			}
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>