<?
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	include_once('./jfv_include.inc.php');
	$Encodage=GetData("Joueur","ID",$AccountID,"Encodage");
	if($Encodage ==2)
	{
		$lieuo=Insec($_POST['lieuo']);
		$lieud=Insec($_POST['lieud']);
		$lienr=Insec($_POST['lienr']);
		$lienc=Insec($_POST['lienc']);
		if($lieuo and $lieud and ($lienr or $lienc))
		{
			$query="INSERT INTO Lieux_Links (Lieu1,Lieu2,Route,Train,Encodeur)";
			$query.="VALUES ('$lieuo','$lieud','$lienr','$lienc','$AccountID')";
			$con=dbconnecti(1);
			$ok=mysqli_query($con,$query);
			if($ok)
				echo "Lien encodé avec succès!";
			else
				echo "Erreur d'encodage! ".mysqli_error($con);
			mysqli_close($con);
			echo "<br><a class='btn btn-default' title='Retour' href='index.php?view=db_lien_add'>Retour</a>";
		}
		else
		{
			include_once('./jfv_txt.inc.php');
			if($AccountID ==10)
				$Land=4;
			elseif($AccountID ==1)
				$Land=3;
			else
				$Land="1,2,3,4,5,6,7,8,9,10,15,16,17,18,19,20";
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT ID,Nom,Pays FROM Lieu WHERE Zone<>6 AND Pays IN (".$Land.") ORDER BY Nom ASC");
			mysqli_close($con);
			if($result) 
			{
				while($data=mysqli_fetch_array($result))
				{
					$Lieux.="<option value='".$data['ID']."'>".$data['Nom']." (".GetPays($data['Pays']).")</option>";
				}
			}
			$Lien_route="<select name='lienr' class='form-control'><option value='0'>Aucune route</option><option value='1'>Route Principale</option><option value='2'>Route Secondaire</option><option value='3'>Route Locale</option></select>";
			$Lien_rail="<select name='lienc' class='form-control'><option value='0'>Aucun</option><option value='1'>Voie principale double</option><option value='2'>Voie secondaire simple</option></select>";
			echo "<script src='./js/lib/jquery-1.10.2.min.js'></script><script type='text/javascript' src='./js/ada_ajax.js'></script><h1>Ajout de lien entre les lieux</h1>
			<form action='index.php?view=db_lien_add' method='post'>
			<table class='table' id='t-link'>
				<thead><tr><th>Lieu d'origine</th><th>Lieu de destination</th><th>Type de route</th><th>Chemin de fer</th></tr></thead>
				<tr><td><select name='lieuo' class='form-control' id='a_lieuo'><option value='0'>Aucun</option>".$Lieux."</select></td><td><select name='lieud' class='form-control' id='a_lieud'><option value='0'>Aucun</option></select></td><td>".$Lien_route."</td><td>".$Lien_rail."</td></tr>
			</table>
			<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
			<h2>Informations sur le lieu de départ</h2><p><span id='liens_infos'>Infos...</span></p>";
		}
	}
	else
		echo "Vous n'avez pas le droit d'accéder à cette page!";
}