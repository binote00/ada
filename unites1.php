<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./menu_infos.php');
	$Pays=Insec($_POST['land']);
	$Type=Insec($_POST['categorie']);
	if($Type =="div")
	{
		include_once('./jfv_txt.inc.php');
		if($Pays =="all")
			$query="SELECT ID,Pays,Nom,Front FROM Division WHERE Active=1 ORDER BY Front,Pays,Nom ASC";
		else
			$query="SELECT ID,Pays,Nom,Front FROM Division WHERE Pays='$Pays' AND Active=1 ORDER BY Front,Pays,Nom ASC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($result)
		{
			echo "<h2>Divisions</h2>";
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Div_Nom=$data['Nom'];
				$Div_Front=GetFront($data['Front']);
				echo "<img src='".$data['Pays']."20.gif'> - Front ".$Div_Front." - ".$Div_Nom." <img src='images/div/div".$data['ID'].".png' title='".$Div_Nom."'><br>";
			}
			mysqli_free_result($result);
		}
	}
	else
	{
		if($Pays =="all")
			$query="SELECT ID,Pays,Nom,Reputation FROM Unit WHERE Type='$Type' ORDER BY Reputation DESC LIMIT 100";
		elseif($Type =="all")
			$query="SELECT ID,Pays,Nom,Reputation FROM Unit WHERE Type<>8 AND Pays='$Pays' ORDER BY Reputation DESC LIMIT 100";
		else
			$query="SELECT ID,Pays,Nom,Reputation FROM Unit WHERE Type='$Type' AND Pays='$Pays' ORDER BY Reputation DESC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		//mysqli_close($con);
		if($result)
		{
			echo "<h2>Unités</h2><table class='table table-striped'><thead><tr><th>Escadrille</th><th>Nation</th><th>Réputation</th><th>As</th><th>Avion</th></tr></thead>";
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$As_txt="";
				$Avion_txt="";
				$as=mysqli_query($con,"SELECT Nom,Avancement,Victoires FROM Pilote_IA WHERE Unit='".$data['ID']."' AND Victoires >0 AND Actif=1 ORDER BY Victoires DESC LIMIT 1");
				$aviont=mysqli_result(mysqli_query($con,"SELECT AvionID FROM XP_Avions_IA WHERE Unite='".$data['ID']."' ORDER BY Exp DESC LIMIT 1"),0);
				$datas=mysqli_fetch_array($as,MYSQLI_ASSOC);
				if($datas)
				{
					$Grade=GetAvancement($datas['Avancement'],$data['Pays']);
					$As_txt="<img src='images/grades/grades".$data['Pays'].$Grade[1].".png' title='".$Grade[0]."'> ".$datas['Nom']."<br>".$datas['Victoires']." victoires";
				}
				if($aviont and $data['Pays']==$country)$Avion_txt=GetAvionIcon($aviont,$data['Pays'],0,$data['ID'],0);
				echo "<tr><td>".Afficher_Icone($data['ID'],$data['Pays'],$data['Nom'])."</td><td><img src='images/".$data['Pays']."20.gif'></td>
				<td>".$data['Reputation']."</td><td>".$As_txt."</td><th>".$Avion_txt."</th></tr>";
			}
		}
		else
			echo "<b>Désolé, aucune unité active ne correspond à votre recherche</b>";
		echo "</table>";
	}
}
?>