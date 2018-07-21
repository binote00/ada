<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(!$Admin)
	$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin == 1)
{
	include_once('./jfv_txt.inc.php');
    include_once __DIR__ . '/view/menu_infos.php';
    $Tri = Insec($_POST['Tri']);
	if(!$Tri)$Tri=1;
?>
<div style='overflow:auto; width: 100%;'>
	<table class='table table-striped'>
	<thead><tr>
		<th>Nom</th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="1"><input type='Submit' value='Pays'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="2"><input type='Submit' value='Reput'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="4"><input type='Submit' value='Robustesse'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="7"><input type='Submit' value='Vitesse'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="3"><input type='Submit' value='Blindage'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="5"><input type='Submit' value='Taille'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="6"><input type='Submit' value='Detection'></form></th>
		<th>Arme_Inf</th>
		<th>Arme_Art</th>
		<th>Arme_AT</th>
		<th>Arme_AA</th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="9"><input type='Submit' value='Portee'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="12"><input type='Submit' value='Autonomie'></form></th>
		<th>Carbu</th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="8"><input type='Submit' value='Charge'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="10"><input type='Submit' value='Type'></form></th>
		<th><form action='index.php?view=anav' method='post'><input type='hidden' name='Tri' value="11"><input type='Submit' value='Cat'></form></th>
		<th>Production</th>
	</tr></thead>
<?
	switch($Tri)
	{
		case 1:
			$Tri="Pays";
		break;
		case 2:
			$Tri="Reput";
		break;
		case 3:
			$Tri="Blindage_f";
		break;
		case 4:
			$Tri="HP";
		break;
		case 5:
			$Tri="Taille";
		break;
		case 6:
			$Tri="Detection";
		break;
		case 7:
			$Tri="Vitesse";
		break;
		case 8:
			$Tri="Charge";
		break;
		case 9:
			$Tri="Portee";
		break;
		case 10:
			$Tri="Type";
		break;
		case 11:
			$Tri="Categorie";
		break;
		case 12:
			$Tri="Fuel";
		break;
	}
	$con=dbconnecti();
	//$result=mysqli_query($con,"SELECT ID,Pays,Nom,Vitesse,Taille,Detection,Range,Arme_Inf,Arme_Art,Arme_AT,Arme_AA,Blindage_f,Fuel,Carbu_ID,Charge,Type,Categorie,Reput,HP FROM Cible WHERE Unit_ok=1 ORDER BY $Tri DESC, Nom ASC");
	$result=mysqli_query($con,"SELECT * FROM Cible WHERE Unit_ok=1 AND mobile=5 ORDER BY $Tri DESC, Nom ASC");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{	
			$Art_Dg=GetData("Armes","ID",$data['Arme_Art'],"Degats");
			$AT_Dg=GetData("Armes","ID",$data['Arme_AT'],"Degats");
			$CT=0;
			if($data['mobile'] == 2 or $data['mobile'] == 6)$CT=1;
			$CT+=round(($data['Blindage_f']*50)/200 + $data['Portee']/1000 + $data['Vitesse']/50 + $data['Detection']/60 + $data['Charge']/1000 + $data['Fuel']/200 + (($Art_Dg+$AT_Dg)/1000) - ($data['Taille']/50),1); //+ Degats/1000
?>		
		<tr>
			<td align="left"><img src="images/vehicules/vehicule<?echo $data['ID'];?>.gif" title="<? echo $data['Nom']; ?>"></td>
			<td><img src="images/<? echo $data['Pays']; ?>20.gif"></td>
			<td><? echo $data['Reput'].'/'.$CT;?></td>
			<td><? echo $data['HP'].'/'.round(($data['Blindage_f']*50)+100);?></td>
			<td><? echo $data['Vitesse'].' ('.$data['mobile'].')';?></td>
			<td><? echo $data['Blindage_f']."mm";?></td>
			<td><? echo $data['Taille'];?></td>
			<td><? echo $data['Detection'];?></td>
			<td><? echo GetData("Armes","ID",$data['Arme_Inf'],"Nom").'<br>('.$data['Arme_Inf'].' - '.$data['Arme_Inf_mun'].'muns - '.GetData("Armes","ID",$data['Arme_Inf'],"Degats")*GetData("Armes","ID",$data['Arme_Inf'],"Multi").'Dg)';?></td>
			<td><? echo GetData("Armes","ID",$data['Arme_Art'],"Nom").'<br>('.$data['Arme_Art'].' - '.$data['Arme_Art_mun'].'muns - '.$Art_Dg.'Dg)';?></td>
			<td><? echo GetData("Armes","ID",$data['Arme_AT'],"Nom").'<br>('.$data['Arme_AT'].' - '.$data['Arme_AT_mun'].'muns - '.$AT_Dg.'Dg)';?></td>
			<td><? echo GetData("Armes","ID",$data['Arme_AA'],"Nom").'<br>('.$data['Arme_AA'].' - '.$data['Arme_AA_mun'].'muns - '.GetData("Armes","ID",$data['Arme_AA'],"Degats")*GetData("Armes","ID",$data['Arme_AA'],"Multi").'Dg)';?></td>
			<td><? echo $data['Portee'];?></td>
			<td><? echo $data['Fuel']."km";?></td>
			<td><? echo $data['Carbu_ID'];?></td>
			<td><? echo $data['Charge']."kg";?></td>
			<td><? echo $data['Type'];?></td>
			<td><? echo $data['Categorie'];?></td>
			<td><? echo $data['Production'];?></td>
		</tr>
<?
		}
	}
?>
		<tr bgcolor="tan">
			<th>Nom</th>
			<th>Pays</th>
			<th>Reput</th>
			<th>Robustesse</th>
			<th>Vitesse</th>
			<th>Blindage</th>
			<th>Taille</th>
			<th>Detection</th>
			<th>Arme_Inf</th>
			<th>Arme_Art</th>
			<th>Arme_AT</th>
			<th>Arme_AA</th>
			<th>Portee</th>
			<th>Autonomie</th>
			<th>Carbu</th>
			<th>Charge</th>
			<th>Type</th>
			<th>Cat</th>
			<th>Production</th>
		</tr>
	</table>
</div><hr>
<?
}
else
{
	?>
	<body bgcolor="#ECDDC1">
	<div align="center" bgcolor="#ECDDC1">
		<table class='table'>
			<tr><td><img src='images/top_secret.gif'></td></tr>
			<tr><td>Ces donn�es sont classifi�es.</td></tr>
			<tr><td>Votre rang ne vous permet pas d'acc�der � ces informations.</td></tr>
		</table>
	</div>
	</body>
<?}?>