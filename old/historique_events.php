<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
//include_once('./menu_actus.php');
	//Events_Historiques
	$query="SELECT ID,Nom,Points_Allies,Points_Axe FROM Event_Historique WHERE Date='$Date_Campagne' AND Type_Mission >0 ORDER BY ID DESC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($Data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			if(strrpos($Data['Nom'],"("))
			{
				$Event_ID=$Data['ID'];
				$Nom=$Data['Nom'];
			}
			$Points_Allies+=$Data['Points_Allies'];
			$Points_Axe+=$Data['Points_Axe'];
		}
		mysqli_free_result($result);
		unset($Data);
		$Event_Nom=substr_replace($Nom,"",strpos($Nom,"("));
		//$img="<img src='images/event".$Event_ID.".jpg'>";
		$img="<img src='images/event7316.jpg'>";
	}
	if($Event_Nom =="")
		$Event_Nom="Aucune bataille historique aujourd'hui";
	if($Event_ID =="")
		$img="<img src='images/event_repos.jpg'>";
?>
<h1>Evènements historiques</h1>
<h2><? echo $Event_Nom;?></h2>
<?echo $img;?>
<h3><table cellpadding='5'>
<tr><td><img src='120.gif'> Axe <img src='620.gif'></td><td align='right'> <? echo $Points_Axe;?></td></tr>
<tr><td><img src='220.gif'> Alliés <img src='820.gif'></td><td align='right'> <? echo $Points_Allies;?></td></tr></table></h3>
<h2>Missions historiques précédentes</h2>
	<table class="table table-striped" width="1024">
	<thead><tr><th>Date</th><th>Bataille</th><th>Score Axe</th><th>Score Alliés</th></tr></thead>
<?
	//Events_Historiques
	$query="SELECT ID,Nom,SUM(Points_Allies),SUM(Points_Axe),`Date`,Lieu FROM Event_Historique WHERE Points_Allies >0 OR Points_Axe >0 GROUP BY `Date` ORDER BY `Date` DESC";
	$con=dbconnecti();
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if($result)
	{
		while($Data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
		{
			if(strrpos($Data['Nom'],"("))
			{
				//$Event_ID=$Data['ID'];
				$Nom=$Data['Nom'];
				$Pays=GetPays($Data['Pays']);
				$Lieu=GetData("Lieu","ID",$Data['Lieu'],"Nom");
				$Date=$Data['Date'];
				//$Mission=GetMissionType($Data['Type_Mission']);
				//$Unite=GetAvionType($Data['Unite']);
			}
			/*echo "<tr>
			<td>".$Date."</td><td>".$Nom."</td><th>".$Lieu."</th><td>".$Pays."</td><td>".$Unite."</td><td>".$Mission."</td>
			</tr>";*/
			$Points_Allies=$Data['SUM(Points_Allies)'];
			$Points_Axe=$Data['SUM(Points_Axe)'];
			$test=strpos($Nom,"(");
			$Event_Nom=substr_replace($Nom,"",$test);
			echo "<tr><td>".$Date."</td><td>".$Event_Nom."</td><th>".$Points_Axe."</th><th>".$Points_Allies."</th>";
			if($Lieu <0)
			{
				echo "<td><form>
				<input type='button' value='Détail' onclick='window.open('detail_attaque.php?id=".$Lieu."&date=".$Date."','Detail','width=640,height=800,scrollbars=1')'>
				</form></td>";
			}
			echo "</tr>";
		}
	}
echo "</table>";
?>