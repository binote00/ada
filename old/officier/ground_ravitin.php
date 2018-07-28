<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Regs="";
	$Cie="";
	$country=$_SESSION['country'];
	$con=dbconnecti();
	$result2=mysqli_query($con,"SELECT Credits,Avancement,Division,Front FROM Officier WHERE ID='$OfficierID'");
	$result=mysqli_query($con,"SELECT * FROM Regiment WHERE Officier_ID='$OfficierID' AND Vehicule_Nbr >0");
	mysqli_close($con);
	if($result2)
	{
		while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Credits=$data2['Credits'];
			$Avancement=$data2['Avancement'];
			$Division=$data2['Division'];
			$Front=$data2['Front'];
		}
		mysqli_free_result($result2);
	}		
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Regs.="<tr><td>".$data['ID']."e Cie</td><td><img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'></td><td>".$data['Vehicule_Nbr']."/".$Max_Veh."</td>
			<td>".$data['Stock_Munitions_8']."/50000</td><td>".$data['Stock_Munitions_13']."/30000</td><td>".$data['Stock_Munitions_20']."/20000</td><td>".$data['Stock_Munitions_30']."/20000</td>
			<td>".$data['Stock_Munitions_40']."/10000</td><td>".$data['Stock_Munitions_50']."/10000</td><td>".$data['Stock_Munitions_60']."/10000</td><td>".$data['Stock_Munitions_75']."/5000</td>
			<td>".$data['Stock_Munitions_90']."/2500</td><td>".$data['Stock_Munitions_105']."/1500</td><td>".$data['Stock_Munitions_125']."/1000</td><td>".$data['Stock_Munitions_150']."/1000</td>
			<td>".$data['Stock_Essence_87']."/25000</td><td>".$data['Stock_Essence_1']."</td></tr>";
			$Cie.="<option value='".$data['ID']."'>".$data['ID']."e Cie</option>";
		}
		mysqli_free_result($result);
		unset($data);
	}		
	echo "<h1>Ravitaillement</h1><h2>Stocks des Compagnies</h2>
		<div style='overflow:auto; width: 100%;'><table class='table'><thead><tr><th>Cie</th><th>Véhicules / Troupes</th><th>Effectifs</th>
		<th>8mm</th><th>13mm</th><th>20mm</th><th>30mm</th><th>40mm</th><th>50mm</th><th>60mm</th><th>75mm</th><th>90mm</th><th>105mm</th><th>125mm</th><th>150mm</th>
		<th>Essence</th><th>Diesel</th></tr></thead>".$Regs."</table></div>";
	if($Credits >=2)
	{
		echo "<h2>Gestion des stocks <img src='/images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'></h2>
			<form action='index.php?view=ground_ravitin1' method='post'>
			Type <select name='Type_Stock' class='form-control' style='width: 200px'><option value='8'>8mm</option><option value='13'>13mm</option><option value='20'>20mm</option><option value='30'>30mm</option>
			<option value='40'>40mm</option><option value='50'>50mm</option><option value='60'>60mm</option><option value='75'>75mm</option><option value='90'>90mm</option><option value='105'>105mm</option>
			<option value='125'>125mm</option><option value='150'>150mm</option><option value='1087'>Essence</option><option value='1001'>Diesel</option></select>
			Compagnie transférant <select name='Cie_ori' class='form-control' style='width: 200px'>".$Cie."</select>
			Compagnie recevant <select name='Cie_dest' class='form-control' style='width: 200px'>".$Cie."</select>
			<input type='Submit' value='Transférer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	else
		echo "Vous manquez de temps pour cela !";
}?>