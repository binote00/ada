<?
/*require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{*/
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$resulta=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion"),0);
	//$resultw=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Armes"),0);
	$resultv=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Cible"),0);
	$resultl=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu"),0);
	$resultp=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA"),0);
	$resultr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Vehicule_ID <5000"),0);
	$resultn=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Vehicule_ID >4999"),0);
	$resultnn=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID >4999"),0);
	$resultvn=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID <5000"),0);
	$resultvav=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr+Avion2_Nbr+Avion3_Nbr) FROM Unit"),0);
	$resultu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit"),0);
	$resultj=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Joueur"),0);
	$resultsa=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM (SELECT ID FROM Attaque UNION ALL SELECT ID FROM Attaque_ia) as x"),0);
	$resultsb=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement"),0);
	$resultsc=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse"),0);
	$resultsr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce"),0);
	$resultcb=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ground_Cbt"),0);
	mysqli_close($con);
	$menu='<div class="row"><div class="col-md-6"><h2>Présentation</h2>
	<div class="videoWrapper"><iframe width="640" height="480" sandbox="allow-scripts" src="https://www.youtube.com/embed/2-s5Iq3rvNA"></iframe></div>
	<a href="https://www.facebook.com/AubeDesAigles/" class="lien" target="_blank"><img src="images/facebook.png" alt="Page Facebook Aube des Aigles"> Visitez notre page Facebook !</a></div>
	<div class="col-md-6"><h2>Statistiques</h2><table class="table  table-striped">
	<tr><th>Modèles d\'avions</th><th>'.$resulta.'</th></tr>
	<tr><th>Modèles de véhicules</th><th>'.$resultv.'</th></tr>
	<tr><th>Villes</th><th>'.$resultl.'</th></tr>
	<tr><th colspan="2"></th></tr>
	<tr><th>Unités aériennes</th><th>'.$resultu.'</th></tr>
	<tr><td>Avions</td><th>'.$resultvav.'</th></tr>
	<tr><td>Pilotes</td><th>'.$resultp.'</th></tr>
	<tr><th>Unités terrestres</th><th>'.$resultr.'</th></tr>
	<tr><td>Troupes terrestres</td><th>'.$resultvn.'</th></tr>
	<tr><th>Unités navales</th><th>'.$resultn.'</th></tr>
	<tr><td>Navires</td><th>'.$resultnn.'</th></tr>
	<tr><th colspan="2"></th></tr>
	<tr><th>Joueurs</th><th>'.$resultj.'</th></tr>
	<tr><td>Attaques aériennes</td><th>'.$resultsa.'</th></tr>
	<tr><td>Bombardements aériens</td><th>'.$resultsb.'</th></tr>
	<tr><td>Reconnaissances aériennes</td><th>'.$resultsr.'</th></tr>
	<tr><td>Victoires aériennes</td><th>'.$resultsc.'</th></tr>
	<tr><td>Combats terrestres</td><th>'.$resultcb.'</th></tr>
	</table></div></div>';
	/*if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_NUM))
		{
			if(GetData("Pays","ID",$data[2],"Faction") != GetData("Pays","ID",$data[3],"Faction"))
				echo $data[1]." ".$data[0]." (".$data[2]." ".$data[3].")<br>";
		}
		mysqli_free_result($result);
	}*/
//}