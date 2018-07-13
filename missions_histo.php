<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_inc_const.php');
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_actus.php');
	echo $img="<img src='images/event_oc_paris.jpg'>";
?>
	<table align="center" border="0" bgcolor="#ECDDC1" cellspacing='2' cellpadding='2' width="800">
	<tr><td align="center" colspan="8"><h2>Missions historiques planifiées</h2></td></tr>
	<tr bgcolor="tan"><th>Date</th><th>Bataille</th><th>Lieu</th><th>Nation</th><th>Unités</th><th>Mission</th></tr>
<?
	//Events_Historiques
	$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT ID,Nom,Date,Lieu,Pays,Unite,Type_Mission FROM Event_Historique WHERE Type_Mission >0 AND Date >='$Date_Campagne' ORDER BY Date ASC, Pays ASC"); //GROUP BY Date
	mysqli_close($con);
	if($result)
	{
		while($Data=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
			$Pays_txt=false;
			if(strrpos($Data['Nom'],"("))
			{
				$Event_ID=$Data['ID'];
				$Nom=$Data['Nom'];
				if($Data['Date'] !=$Date)
					echo "<tr><td colspan='8'><hr></td></tr>";
				$Date=$Data['Date'];
				$Lieu=$Data['Lieu'];
				$Pays=$Data['Pays'];
				$Type_Unite=$Data['Unite'];
				$Type_Mission=$Data['Type_Mission'];
				$test=strpos($Nom,"(");
				$Event_Nom=substr_replace($Nom,"",$test);
				$Pays_txt="<img src='".$Pays."20.gif'>";
				/*$con=dbconnecti();
				$result2=mysqli_query($con, "SELECT Pays FROM Event_Historique WHERE Type_Mission > 0 and Date='$Date' GROUP BY Pays ORDER BY Pays ASC");
				mysqli_close($con);
				if($result2)
				{
					while($Data2=mysqli_fetch_array($result2, MYSQLI_ASSOC)) 
					{
						$Pays=$Data2['Pays'];
						$Pays_txt .= "<img src='".$Pays."20.gif'>";
					}
					mysqli_free_result($result2);
				}*/
				
			}
			if($Lieu)
				$Lieu_Nom=GetData("Lieu","ID",$Lieu,"Nom");
			else
				$Lieu_Nom="Front";
			?>
			<tr><td><? echo $Date;?></td><td><? echo $Event_Nom;?></td><th><? echo $Lieu_Nom;?></th><td><? echo $Pays_txt;?></td><td><? echo GetAvionType($Type_Unite);?></td><td><? echo GetMission_Type($Type_Mission);?></td></tr>
			<?	
		}
	}
	echo "</table>";
}
?>