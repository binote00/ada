<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	$Front=GetData("Pilote",$PlayerID,"ID","Front");	
	$con=dbconnecti();	
	$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
	mysqli_close($con);
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Commandant=$data['Commandant'];
			$Officier_Adjoint=$data['Adjoint_EM'];
			$Officier_EM=$data['Officier_EM'];
			$Officier_Rens=$data['Officier_Rens'];
		}
		mysqli_free_result($result2);
	}	
	include_once('./menu_em.php');
	if($PlayerID ==1 or $Admin)
	{
		include_once('./jfv_ground.inc.php');
		if($PlayerID ==1)
			$query="SELECT r.*,c.Nom FROM Regiment as r,Cible as c WHERE r.Vehicule_ID=c.ID ORDER BY r.Lieu_ID ASC, r.Placement ASC";
		else
			$query="SELECT r.*,c.Nom FROM Regiment as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Pays='$country' ORDER BY r.Lieu_ID ASC, r.Placement ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			echo "<table border='0' cellspacing='1' cellpadding='5' bgcolor='#ECDDC1'><tr><th colspan='12' bgcolor='lightyellow'>Unités terrestres</th></tr>
			<tr class='TitreBleu_bc'><th width='150px'>Nom</th><th>Nation</th><th>Effectifs</th><th>Troupes</th><th>Lieu</th><th>Placement</th></tr>";
			echo $Plyr;
			while($data=mysqli_fetch_array($result))
			{
				$Lieu=GetData("Lieu","ID",$data['Lieu_ID'],"Nom");
				$Placement=GetPlace($data['Placement']);
				echo "<tr><td align='left'>".$data['ID']."e Compagnie</td>
				<td><img src='".$data['Pays']."20.gif'></td>
				<td>".$data['Vehicule_Nbr']."</td>
				<td><img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Nom']."'></td>
				<td>".$Lieu."</td><td>".$Placement."</td></tr>";			
			}
			echo "</table>";
		}
		else
			echo "Désolé, aucune unité terrestre recensée.";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
{
	echo "Vous devez être connecté pour accéder à cette page!";
}
?>