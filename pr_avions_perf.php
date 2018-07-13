<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(!$Admin)$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1)
{
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_air_inc.php');
	include_once('./menu_infos.php');
	$Pays=Insec($_POST['land']);
	$Type=Insec($_POST['type']);
	$Alt=Insec($_POST['alt']);
	if($Pays == "all")
		$Pays="%";
	if($Type == "all")
		$Type="%";
	if($Pays >9 or $Pays ==1)
		$query="SELECT ID,Nom,Pays,Maniabilite,ManoeuvreB,ManoeuvreH,Robustesse,Engine_Nbr FROM Avion WHERE Pays='$Pays' AND Type LIKE '$Type' AND Plafond >='$Alt'";
	else
		$query="SELECT ID,Nom,Pays,Maniabilite,ManoeuvreB,ManoeuvreH,Robustesse,Engine_Nbr FROM Avion WHERE Pays LIKE '$Pays' AND Type LIKE '$Type' AND Plafond >='$Alt'";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			//$Avion_img=GetAvionIcon($data['ID'],$data['Pays'],0,0,0,$data['Nom'],true);
			$Perf=GetMano($data['ManoeuvreB'],$data['ManoeuvreH'],9999,9999,$Alt)+$data['Maniabilite']+(GetSpeed("Avion",$data['ID'],$Alt,0)*2)-(GetPuissance("Avion",$data['ID'],$Alt,$data['Robustesse'],1,1,$data['Engine_Nbr'])/2);
			$avion_perf[$data['ID']]=$Perf;
			//$i++;
			//$avion_perf[$data['ID']]=$Perf;
			//echo "<tr><td>".$i."</td><td>".$Avion_img."</td><td><img src='".$data['Pays']."20.gif'></td><td>".$data['Rating']."</td><td>".$Perf."</td></tr>";
		}
		echo "<table class='table table-striped'><tr><th>Avion</th><th>Perf à ".$Alt."m</th></tr>";
		arsort($avion_perf);
		foreach($avion_perf as $key => $value) 
		{
			echo "<tr><td>".GetData("Avion","ID",$key,"Nom")."</td><td>".$value."</td></tr>";
		}
		echo "</table>";
	}
	else
		echo "<b>Désolé, aucun avion</b>";
}
?>