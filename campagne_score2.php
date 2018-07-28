<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_actus.php');
	if($Date_Campagne >"1941-01-01"){
		$Axe_nations='1,15,20,19,6,9,18';
		$Allie_nations='2,3,4,10,35,5,8,7,17';
		if($country ==2 or $country ==4 or $country ==8)			
			$Lend_Lease_txt="<h2><img src='images/lendlease.png' title='Lend-Lease'> Score Lend-Lease <span><a href='help/aide_lend_lease' title='Aide'><img src='images/help.png' title='Aide'></a></span></h2><b>".$Lend_Lease."</b>";
	}
	else{
		$Axe_nations='1,6';
		$Allie_nations='2,3,4,35,5';
	}
	$Flags_Axe=explode(',',$Axe_nations);
	$Flags_Axe_Nbr=count($Flags_Axe);
	$Flags_Allies=explode(',',$Allie_nations);
	$Flags_Allies_Nbr=count($Flags_Allies);
	foreach($Flags_Axe as $Flag_Axe){
		$Axe_Flags.="<img src='images/flag".$Flag_Axe."p.jpg' title='".GetPays($Flag_Axe)."'> ";
	}
	foreach($Flags_Allies as $Flag_Allies){
		$Allies_Flags.="<img src='images/flag".$Flag_Allies."p.jpg' title='".GetPays($Flag_Allies)."'> ";
	}
	$con=dbconnecti();
	$axe_total=mysqli_result(mysqli_query($con,"SELECT Score FROM Pays WHERE ID=1"),0);
	$allie_total=mysqli_result(mysqli_query($con,"SELECT Score FROM Pays WHERE ID=2"),0);
	if($Lend_Lease_txt)$Lend_Lease=mysqli_result(mysqli_query($con,"SELECT Special_Score FROM Pays WHERE ID='$country'"),0);
	/*$Allie_arctic=mysqli_result(mysqli_query($con,"SELECT SUM(Special_Score) FROM Pays WHERE Pays_ID IN (".$Allie_nations.")"),0);
	$Axe_arctic=mysqli_result(mysqli_query($con,"SELECT SUM(Special_Score) FROM Pays WHERE Pays_ID IN (".$Axe_nations.")"),0);*/
	$result=mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag IN (".$Axe_nations.")");
	$result2=mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag IN (".$Allie_nations.")");
	$axe_afn=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Longitude <50 AND Latitude <43 AND Flag IN (".$Axe_nations.")"),0);
	$allie_afn=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Longitude <50 AND Latitude <43 AND Flag IN (".$Allie_nations.")"),0);
	$axe_west=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Longitude <14 AND Latitude >=43 AND Flag IN (".$Axe_nations.")"),0);
	$allie_west=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Longitude <14 AND Latitude >=43 AND Flag IN (".$Allie_nations.")"),0);
	$axe_est=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Longitude >=14 AND Latitude >=43 AND Longitude <=67 AND Flag IN (".$Axe_nations.")"),0);
	$allie_est=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Longitude >=14 AND Latitude >=43 AND Longitude <=67 AND Flag IN (".$Allie_nations.")"),0);
	$axe_pac=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Longitude >67 AND Flag IN (".$Axe_nations.")"),0);
	$allie_pac=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Longitude >67 AND Flag IN (".$Allie_nations.")"),0);
	$ten_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=10 AND Flag IN (".$Axe_nations.")"),0);
	$ten_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=10 AND Flag IN (".$Allie_nations.")"),0);
	/*$nine_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=9 AND Flag IN (".$Axe_nations.")"),0);
	$nine_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=9 AND Flag IN (".$Allie_nations.")"),0);*/
	$eight_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=8 AND Flag IN (".$Axe_nations.")"),0);
	$eight_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=8 AND Flag IN (".$Allie_nations.")"),0);
	/*$seven_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=7 AND Flag IN (".$Axe_nations.")"),0);
	$seven_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=7 AND Flag IN (".$Allie_nations.")"),0);*/
	$six_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=6 AND Flag IN (".$Axe_nations.")"),0);
	$six_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=6 AND Flag IN (".$Allie_nations.")"),0);
	$five_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=5 AND Flag IN (".$Axe_nations.")"),0);
	$five_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=5 AND Flag IN (".$Allie_nations.")"),0);
	$four_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=4 AND Flag IN (".$Axe_nations.")"),0);
	$four_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=4 AND Flag IN (".$Allie_nations.")"),0);
	$three_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=3 AND Flag IN (".$Axe_nations.")"),0);
	$three_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=3 AND Flag IN (".$Allie_nations.")"),0);
	$two_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=2 AND Flag IN (".$Axe_nations.")"),0);
	$two_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=2 AND Flag IN (".$Allie_nations.")"),0);
	$one_axe=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=1 AND Flag IN (".$Axe_nations.")"),0);
	$one_allie=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Valeurstrat=1 AND Flag IN (".$Allie_nations.")"),0);
	mysqli_close($con);
	if($result){
		while($Data=mysqli_fetch_array($result,MYSQLI_NUM)){
			$Points_Axe_Total=$Data[0];
		}
		mysqli_free_result($result);
	}
	if($result2){
		while($Data2=mysqli_fetch_array($result2,MYSQLI_NUM)){
			$Points_Allie_Total=$Data2[0];
		}
		mysqli_free_result($result2);
	}
?>
<div class='row'><div class='col-md-6'><h2>Axe</h2><?echo $Axe_Flags;?></div><div class='col-md-6'><h2>Alliés</h2><?echo $Allies_Flags;?></div></div>
<h2>Score de Campagne <span><a href='help/aide_score.php' title='Aide'><img src='images/help.png' title='Aide'></a></span></h2>
<div class='alert alert-warning'>La faction possédant le score le plus élevé à la fin de la campagne gagne la guerre et remporte la partie.
<br>Chaque lieu stratégique contrôlé par une faction lui rapporte quotidiennement des points en fonction de sa valeur. Dès qu'un lieu stratégique est capturé par la faction adverse, les points changent de camp.</div>
<table class='table table-hover'>
	<thead><tr><th>Lieux Stratégiques</th><th>Axe</th><th>Alliés</th></tr></thead>
	<tr><th><img src='images/strat10.png' title='Lieux de valeur stratégique 10'></th><td><?echo $ten_axe;?></td><td><?echo $ten_allie;?></td></tr>
	<!--<tr><th>9</th><td><?echo $nine_axe;?></td><td><?echo $nine_allie;?></td></tr>-->
	<tr><th><img src='images/strat8.png' title='Lieux de valeur stratégique 8'></th><td><?echo $eight_axe;?></td><td><?echo $eight_allie;?></td></tr>
	<!--<tr><th>7</th><td><?echo $seven_axe;?></td><td><?echo $seven_allie;?></td></tr>-->
	<tr><th><img src='images/strat6.png' title='Lieux de valeur stratégique 6'></th><td><?echo $six_axe;?></td><td><?echo $six_allie;?></td></tr>
	<tr><th><img src='images/strat5.png' title='Lieux de valeur stratégique 5'></th><td><?echo $five_axe;?></td><td><?echo $five_allie;?></td></tr>
	<tr><th><img src='images/strat4.png' title='Lieux de valeur stratégique 4'></th><td><?echo $four_axe;?></td><td><?echo $four_allie;?></td></tr>
	<tr><th><img src='images/strat3.png' title='Lieux de valeur stratégique 3'></th><td><?echo $three_axe;?></td><td><?echo $three_allie;?></td></tr>
	<tr><th><img src='images/strat2.png' title='Lieux de valeur stratégique 2'></th><td><?echo $two_axe;?></td><td><?echo $two_allie;?></td></tr>
	<tr><th><img src='images/strat1.png' title='Lieux de valeur stratégique 1'></th><td><?echo $one_axe;?></td><td><?echo $one_allie;?></td></tr>
	<tr><th colspan='3'><hr></th></tr>
	<tr><th>Front Ouest</th><th><?=$axe_west;?></th><th><?=$allie_west;?></th></tr>
	<tr><th>Front Mediterranéen</th><th><?=$axe_afn;?></th><th><?=$allie_afn;?></th></tr>
	<?if($Date_Campagne >"1941-01-01"){?>
	<tr><th>Front Est</th><th><?=$axe_est;?></th><th><?=$allie_est;?></th></tr>
	<tr><th>Front Pacifique</th><th><?=$axe_pac;?></th><th><?=$allie_pac;?></th></tr>
	<?}?>
	<tr><th colspan='3'><hr></th></tr>
	<tr><th>Bonus Quotidien</th><th><?=$Points_Axe_Total;?></th><th><?=$Points_Allie_Total;?></th></tr>
	<tr class='warning'><th>Score Total</th><th><?=$axe_total;?></th><th><?=$allie_total;?></th></tr>
</table>
<div class='row'><div class='col-md-6'><img src='images/campagne_score.jpg' style='width:100%;'></div><div class='col-md-6'><?echo $Lend_Lease_txt;?></div></div>
<?
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";