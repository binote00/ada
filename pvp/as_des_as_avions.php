<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
//include_once('./jfv_sandbox.inc.php');
include_once('./jfv_txt.inc.php');
include_once('./menu_as_des_as.php');
$Niveau=Insec($_POST['Niveau']);
$Pays=Insec($_POST['Pays']);
if($Niveau >0 and $Pays)
{
	$PlayerID=$_SESSION['PlayerID'];
	/*$Level1=GetRankLevel($Niveau,1);
	$Level4=GetRankLevel($Niveau,4);*/	
	if($PlayerID ==1)
	{
		echo "<h2>Tableau des Avions</h2><table class='table table-striped'>
			<thead><tr>
				<th>Avion</th>
				<th>Pays</th>
				<th>Type</th>
				<th>Robustesse</th>
				<th>Blindage</th>
				<th>Vitesse H</th>
				<th>Vitesse B</th>
				<th>Vitesse A</th>
				<th>Vitesse P</th>
				<th>Man H</th>
				<th>Man B</th>
				<th>Roll</th>
				<th>Plafond</th>
				<th>Date</th>
				<th>Niveau</th>				
				<th>As</th></tr></thead>";
	}
	else
	{
		echo "<h2>Tableau des Avions</h2><table class='table table-striped'>
			<thead><tr>
				<th>Avion</th>
				<th>Pays</th>
				<th>Type</th>
				<th>Date</th>
				<th>Niveau</th>
				<th>As</th></tr></thead>";
	}			
		if($Pays =="all")$Pays="%";
		if($Pays >9 or $Pays ==1)
			$query="SELECT ID,Nom,Type,Pays,Engagement,Rating,Robustesse,VitesseH,VitesseB,VitesseA,VitesseP,Plafond,ManoeuvreH,ManoeuvreB,Maniabilite,Blindage,Prototype,Premium FROM Avion WHERE Pays='$Pays' AND Type IN (1,4,12) AND Rating='$Niveau' ORDER BY Engagement ASC";
		else
			$query="SELECT ID,Nom,Type,Pays,Engagement,Rating,Robustesse,VitesseH,VitesseB,VitesseA,VitesseP,Plafond,ManoeuvreH,ManoeuvreB,Maniabilite,Blindage,Prototype,Premium FROM Avion WHERE Pays LIKE '$Pays' AND Type IN (1,4,12) AND Rating='$Niveau' ORDER BY Engagement ASC";
		$con=dbconnecti();
		$result=mysqli_query($con, $query);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$As="";
				$ID=$data['ID'];
				$Nom=$data['Nom'];
				$Type=GetAvionType($data['Type']);				
				$con=dbconnecti(2);
				$resultwin=mysqli_query($con,"SELECT COUNT(*),Joueur_win FROM Chasse_sandbox WHERE Avion_win='$ID' AND PVP=0 GROUP BY Joueur_win ORDER BY COUNT(*) ASC");
				mysqli_close($con);
				if($resultwin)
				{
					while($datawin=mysqli_fetch_array($resultwin))
					{
						$As=GetData("Joueur","ID",$datawin['Joueur_win'],"Nom")."<br>".$datawin[0]." victoire(s)";
					}
					mysqli_free_result($resultwin);
				}
				if($PlayerID ==1)
					echo "<tr><td>".GetAvionIcon($data['ID'],$data['Pays'],0,0,0,$data['Nom'])."</td><td><img src='".$data['Pays']."20.gif'></td><td>".$Type."</td>
					<td>".$data['Robustesse']."</td><td>".$data['Blindage']."</td><td>".$data['VitesseH']."</td><td>".$data['VitesseB']."</td><td>".$data['VitesseA']."</td><td>".$data['VitesseP']."</td>
					<td>".$data['ManoeuvreH']."</td><td>".$data['ManoeuvreB']."</td><td>".$data['Maniabilite']."</td><td>".$data['Plafond']."</td>
					<td>".$data['Engagement']."</td><td>".$data['Rating']."</td><td>".$As."</td></tr>";
				else
				{
					if($data['Prototype'] >0 or $data['Premium'] >0)
						echo "<tr style='color: red;'><td>".GetAvionIcon($data['ID'],$data['Pays'],0,0,0,$data['Nom'])."</td><td><img src='".$data['Pays']."20.gif'></td><td>".$Type."</td><td>".$data['Engagement']."</td><td>".$data['Rating']."<br>PREMIUM</td><td>".$As."</td></tr>";				
					else
						echo "<tr><td>".GetAvionIcon($data['ID'],$data['Pays'],0,0,0,$data['Nom'])."</td><td><img src='".$data['Pays']."20.gif'></td><td>".$Type."</td><td>".$data['Engagement']."</td><td>".$data['Rating']."</td><td>".$As."</td></tr>";
				}
			}
		}
	echo "</table>";
}
else
{
	echo "<h2>Tableau des Avions</h2><img src='images/gestion_avions".$country. ".jpg'><form action='../index.php?view=as_des_as_avions' method='post'><table class='table'>";
	echo "<thead><tr><th>Niveau</th><th>Pays</th></tr></thead><tr>
	<td align='left'><select name='Niveau' class='form-control' style='width: 100px'>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='6'>6</option>
					<option value='7'>7</option>
					<option value='8'>8</option>
					<option value='9'>9</option>
					<option value='10'>10</option>
				</select></td><td align='left'>
				<select name='Pays' class='form-control' style='width: 300px'>
					<option value='all'>Tous</option>
					<option value='1'>Allemagne</option>
					<option value='2'>Angleterre</option>
					<option value='15'>Bulgarie</option>
					<option value='20'>Finlande</option>
					<option value='4'>France</option>
					<option value='6'>Italie</option>
					<option value='9'>Japon</option>
					<option value='5'>Pays-Bas</option>
					<option value='18'>Roumanie</option>
					<option value='8'>URSS</option>
					<option value='7'>USA</option>
				</select></td></tr>";
	echo "</table><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
}
?>