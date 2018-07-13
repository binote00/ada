<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$country=$_SESSION['country'];
	if($_SESSION['Distance'] ==0)
	{
		$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
		if($Premium)
		{
			$date=date('Y-m-d');
			$query="SELECT DISTINCT ID,Nom FROM Lieu ORDER BY Nom ASC";
			$con=dbconnecti();
			$result=mysqli_query($con,$query) or die(mysqli_error($con));
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
				{
					$Lieux.="<option value=".$data['ID'].">".$data['Nom']."</option>";
				}
				mysqli_free_result($result);
				unset($data);
			}
			echo "<h1>Archives</h1><h2>Compte-rendu pour la date demandée</h2>
			<form action='index?view=stats' method='post'>
			<Input type='Radio' name='Mode' value='0' checked>- Aujourd'hui<br>
			<Input type='Radio' name='Mode' value='1'>- Hier<br>
			<Input type='Radio' name='Mode' value='2'>- Avant-hier<br>
			<Input type='Radio' name='Mode' value='3'>- Cette semaine<br>
			<input type='Submit' class='btn btn-default' value='Voir le compte-rendu' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		else
			echo "<h1>Archives</h1><h2>Information Premium</h2><img src='images/premium.png' title='Information Premium'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>