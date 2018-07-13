<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Division=Insec($_POST['Div']);
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0 and $Division)
	{
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_ground.inc.php');
        if($Front ==99)
        {
            if($country ==8 or $country ==17 or $country ==18 or $country ==19)
                $Front=1;
            elseif($country ==6 or $country ==10)
                $Front=2;
            elseif($country ==20)
                $Front=5;
            elseif($country ==9)
                $Front=3;
            else
                $Front=0;
        }
		$con=dbconnecti();
		$resulta=mysqli_query($con,"SELECT a.ID,a.Nom,a.Cdt,l.Nom as Ville FROM Armee as a,Lieu as l WHERE l.ID=a.Base AND a.Pays='$country' AND a.Front='$Front'");
		mysqli_close($con);
		if($resulta)
		{
			while($dataa=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
			{
				if($dataa['Cdt'] >0)
					$Cdt='Oui';
				else
					$Cdt='Aucun';
				$armee_list.="<tr><td>".$dataa['Nom']."</td><td>".$dataa['Ville']."</td><td>".$Cdt."</td><td><Input type='Radio' name='Action' value='".$dataa['ID']."'></td></tr>";
			}
			mysqli_free_result($resulta);
		}
		if($armee_list)
			$table_armee="<form action='index.php?view=ground_em_change_div' method='post'><input type='hidden' name='Div' value='".$Division."'>
			<table class='table table-striped'><thead><tr><th>Armée</th><th>Base</th><th>Commandant</th><th>Choisir</th></tr></thead>
			<tr><td>Réserve</td><td>".GetData("Lieu","ID",Get_Retraite($Front,$country,40),"Nom")."</td><td>".GetGenStaff($country,1)."</td><td><Input type='Radio' name='Action' value='0'></td></tr>
			".$armee_list."</table>
			<input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
		else
			$table_armee="Aucune armée n'est disponible sur ce front!";
		$titre='Affectation';
		$mes='<h2>Liste des armées sur votre front</h2>'.$table_armee;
		$img="<img src='images/coop".$country.".jpg'>";
		include_once('./default.php');
	}
	else
		echo "<h1>Votre personnage n'est pas autorisé à postuler pour ce poste!</h1>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";