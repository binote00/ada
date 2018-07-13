<?
require_once('./jfv_inc_sessions.php');
$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Joueur = Insec($_POST['joueur']);
	$Credits = Insec($_POST['ct']);
	$Avancement = Insec($_POST['grade']);
	$Reputation = Insec($_POST['reput']);
	$Front = Insec($_POST['front']);
	$EM = Insec($_POST['em']);
	if($Joueur >0)
	{
		if($Front !=99)
		{
			if($EM >0)
			{
				$Pays=GetData("Officier","ID",$Joueur,"Pays");
				if($EM ==1)
				{
					$con=dbconnecti();
					$ok_up1=mysqli_query($con,"UPDATE Pays SET Officier_Terre='$Joueur' WHERE Pays_ID='$Pays' AND Front='$Front'");
					mysqli_close($con);
				}
				elseif($EM ==2)
				{
					$con=dbconnecti();
					$ok_up1=mysqli_query($con,"UPDATE Pays SET Officier_Log='$Joueur' WHERE Pays_ID='$Pays' AND Front='$Front'");
					mysqli_close($con);
				}
			}
			$query="UPDATE Officier SET Credits=Credits+'$Credits' AND Avancement=Avancement+'$Avancement' AND Reputation=Reputation+'$Reputation' AND Front='$Front' WHERE ID='$Joueur'";
		}
		else
			$query="UPDATE Officier SET Credits=Credits+'$Credits' AND Avancement=Avancement+'$Avancement' AND Reputation=Reputation+'$Reputation' WHERE ID='$Joueur'";
		$con=dbconnecti();
		$ok_up=mysqli_query($con,$query);
		mysqli_close($con);
	}
	else
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT DISTINCT ID,Nom,Pays FROM Officier WHERE Actif=0 ORDER BY Nom ASC");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				 $Joueurs.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
			}
			mysqli_free_result($result);
			unset($data);
		}
		echo "<form action='admin_mod_o.php' method='post'>
			<table class='table'>
			<thead><tr><th>Sélection de Pilote</th></tr></thead>
			<tr><td>Pilote</td<td align='left'><select name='joueur' class='form-control' style='width: 200px'>".$Joueurs."</select></td></tr>
			<tr><td>Bonus Credits</td><td>
				<select name='ct'>
					<option value='0'>0</option>
					<option value='2'>2</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='8'>8</option>
					<option value='10'>10</option>
					<option value='12'>12</option>
					<option value='15'>15</option>
					<option value='24'>24</option>
					<option value='40'>40</option>
			</select></td></tr>
			<tr><td>Bonus Grade</td><td>
				<select name='grade'>
					<option value='0'>0</option>
					<option value='500'>500</option>
					<option value='1000'>1000</option>
					<option value='2000'>2000</option>
					<option value='5000'>5000</option>
					<option value='10000'>10000</option>
			</select></td></tr>
			<tr><td>Bonus Reput</td><td>
				<select name='reput'>
					<option value='0'>0</option>
					<option value='500'>500</option>
					<option value='1000'>1000</option>
					<option value='2000'>2000</option>
					<option value='5000'>5000</option>
					<option value='10000'>10000</option>
			</select></td></tr>
			<tr><td>Front</td><td>
				<select name='front'>
					<option value='99'>Ne pas changer</option>
					<option value='1'>Est</option>
					<option value='2'>Med</option>
					<option value='0'>Ouest</option>
					<option value='3'>Pacifique</option>
			</select></td></tr>
			<tr><td>Etat-Major</td><td>
				<select name='em'>
					<option value='0'>Ne pas changer</option>
					<option value='1'>Commandant en chef</option>
					<option value='2'>Officier Logistique</option>
			</select></td></tr>
			</table><input type='Submit' value='VALIDER' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
	}
}
?>