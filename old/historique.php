<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
//include_once('./menu_actus.php');

	/*Events_Historiques
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
		unset($result);
		unset($Data);
		$Event_Nom=substr_replace($Nom,"",strpos($Nom,"("));
		$img="<img src='images/event".$Event_ID.".jpg'>";
	}
	if($Event_Nom =="")
		$Event_Nom="Aucune bataille historique aujourd'hui";
	if($Event_ID =="")
		$img="<img src='images/event_repos.jpg'>";
	*/
?>
<!--<div>
	<table align="center" border="0" bgcolor="#ECDDC1" width="640">
	<tr><td align="center" colspan="3"><h2><? echo $Event_Nom;?></h2></td></tr>
	<tr align="center"><td><h3><img src='120.gif'> Axe <img src='620.gif'></h3></td><td></td><td><h3><img src='220.gif'> Alliés <img src='420.gif'></h3></td></tr>
	<tr><th>Score</th><td></td><th>Score</th></tr>
	<tr><th><? echo $Points_Axe;?></th><td></td><th><? echo $Points_Allies;?></th></tr>
	</table>
	<table align="center" border="1" bgcolor="#ECDDC1">
	<tr><td align="center" colspan="3"><? echo $img;?></td></tr>
	</table>
</div>-->
<h2>Missions historiques déjà encodées</h2>
<div style='overflow:auto; height: 480px;'>
	<table align="center" border="0" bgcolor="#ECDDC1" width="1024">
	<!--<tr><td align="center" colspan="5"><h2>Missions historiques précédentes</h2></td></tr>
	<tr><th>Date</th><th>Bataille</th><th>Score Axe</th><td></td><th>Score Alliés</th><th>Détail</th></tr>-->
<?
	//Events_Historiques
	//$query="SELECT ID,Nom,SUM(Points_Allies),SUM(Points_Axe),Date,Lieu FROM Event_Historique WHERE Points_Allies >0 OR Points_Axe >0 GROUP BY Date ORDER BY Date DESC";
	$query="SELECT DISTINCT ID,Nom,`Date`,Pays,Lieu,Unite,Type_Mission FROM Event_Historique WHERE Type < 3 ORDER BY ID DESC";
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
				$Pays=GetPays($Data['Pays']);
				$Lieu=GetData("Lieu","ID",$Data['Lieu'],"Nom");
				$Date=$Data['Date'];
				$Mission=GetMissionType($Data['Type_Mission']);
				$Unite=GetAvionType($Data['Unite']);
			}
			echo "<tr>
			<td>".$Date."</td><td>".$Nom."</td><th>".$Lieu."</th><td>".$Pays."</td><td>".$Unite."</td><td>".$Mission."</td>
			</tr>";
			/*$Points_Allies=$Data['SUM(Points_Allies)'];
			$Points_Axe=$Data['SUM(Points_Axe)'];
			$test=strpos($Nom,"(");
			$Event_Nom=substr_replace($Nom,"",$test);
			?>
			<tr>
			<td><? echo $Date;?></td><td><? echo $Event_Nom;?></td><th><? echo $Points_Axe;?></th><td></td><th><? echo $Points_Allies;?></th>
			<?if($Lieu){?>
			<td>
				<form>
					<input type="button" value="Détail" onclick="window.open('detail_attaque.php?id=<? echo $Lieu; ?>&date=<?echo $Date;?>','Detail','width=640,height=800,scrollbars=1')">
				</form>
			</td>
			<?}?>
			</tr>
			<?	*/
		}
	}
	echo "</table></div>";
?>