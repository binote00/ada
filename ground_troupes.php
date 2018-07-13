<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./jfv_ground.inc.php');
$OfficierID = $_SESSION['Officier'];
if($OfficierID > 0)
{	
	$country = $_SESSION['country'];
	$Front = GetData("Officier","ID",$OfficierID,"Front");
	$con = dbconnecti();	
	$result2 = mysqli_query($con, "SELECT Officier_Terre,Adjoint_Terre,Officier_Mer FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
	mysqli_close($con);
	if($result2)
	{
		while($data = mysqli_fetch_array($result2, MYSQLI_ASSOC))
		{
			$Officier_Terre = $data['Officier_Terre'];
			$Adjoint_Terre = $data['Adjoint_Terre'];
			$Officier_Mer = $data['Officier_Mer'];
		}
		mysqli_free_result($result2);
	}
	if($OfficierID == $Officier_Terre or $OfficierID == $Adjoint_Terre or $OfficierID == $Officier_Mer or $OfficierID == 1 or $OfficierID == 116)
	{
		$units = '';
		if($OfficierID == 1)
			$query="SELECT r.*,c.Nom as Cible,l.Nom as ville,o.Nom as leader,o.Division,o.Credits_Date FROM Regiment as r,Cible as c,Lieu as l,Officier as o 
			WHERE r.Lieu_ID = l.ID AND r.Officier_ID = o.ID AND r.Vehicule_ID = c.ID 
			AND r.Pays='$country' AND o.Actif=0 ORDER BY r.Lieu_ID ASC, r.Placement ASC, r.ID ASC";
		else
			$query="SELECT r.*,c.Nom as Cible,l.Nom as ville,o.Nom as leader,o.Division,o.Credits_Date FROM Regiment as r,Cible as c,Lieu as l,Officier as o 
			WHERE r.Lieu_ID = l.ID AND r.Officier_ID = o.ID AND r.Vehicule_ID = c.ID 
			AND r.Pays='$country' AND o.Front='$Front' AND o.Actif=0 ORDER BY r.Lieu_ID ASC, r.Placement ASC, r.ID ASC";
		$con = dbconnecti();
		$result = mysqli_query($con, $query);
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result))
			{
				switch($data['Placement'])
				{
					case 1:
						$Placement = "Aérodrome";
					break;
					case 2:
						$Placement = "Route";
					break;
					case 3:
						$Placement = "Gare";
					break;
					case 4:
						$Placement = "Port";
					break;
					case 5:
						$Placement = "Pont";
					break;
					case 6:
						$Placement = "Usine";
					break;
					case 7:
						$Placement = "Radar";
					break;
					default:
						$Placement = "Caserne";
					break;
				}
				if($data['Division'])
					$div = "<img src='images/div".$data['Division'].".png'>";
				else
					$div = '';
				$units.= "<tr><td align='left'>".$div."<br>".$data['ID']."e Compagnie</td>
				<td><img src='".$data['Pays']."20.gif'></td>
				<td>".$data['ville']."</td>
				<td>".$Placement."</td>
				<td>".GetPosGr($data['Position'])."</td>
				<td>".$data['Vehicule_Nbr']."</td>
				<td><img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif' title='".$data['Cible']."'></td>
				<td>".$data['leader']."</td>
				<td>".$data['Credits_Date']."</td>
				</tr>";		
			}
			mysqli_free_result($result);
		}
		else
			echo "Désolé, aucune unité terrestre recensée.";
?>
<head>
	<title>Aube des Aigles : Détail des Troupes</title>
	<link href="test.css" rel="stylesheet" type="text/css">
</head>
<body background="images/bg_papier1.gif">
<div>
	<table border="1" cellspacing="2" cellpadding="5" bgcolor="#ECDDC1" width="1024" align="center">
		<tr bgcolor='lightyellow'><th width='200px'>Unité</th><th width='50px'>Nation</th><th width='100px'>Lieu</th><th>Zone</th><th>Position</th><th width='50px'>Effectifs</th><th>Troupes</th><th>Officier</th><th>Activité</th></tr>
		<?echo $units;?>
	</table>
</div>
</body>
<?	}
	else
	{
	?>
<head>
	<title>Aube des Aigles : Détail des Troupes</title>
	<link href="test.css" rel="stylesheet" type="text/css">
</head>
	<body bgcolor="#ECDDC1"><div><table class='table'>
			<tr><td><img src='images/top_secret.gif'></td></tr>
			<tr><td>Ces données sont classifiées.</td></tr>
			<tr><td>Votre rang ne vous permet pas d'accéder à ces informations.</td></tr>
	</table></div></body>
	<?
	}	
}?>
