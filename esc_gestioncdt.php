<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_avions.inc.php');
	include_once('./jfv_txt.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0)
	{
		$Avancement=GetData("Pilote","ID",$PlayerID,"Avancement");
		$Unite=GetData("Pilote","ID",$PlayerID,"Unit");		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Commandant,Officier_Adjoint,Officier_Technique FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Unite_Nom=$data['Nom'];
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Officier_Technique=$data['Officier_Technique'];
			}
			mysqli_free_result($result);
			unset($data);
		}		
		if($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique or $PlayerID ==1)
		{		
			$Grade=GetAvancement($Avancement,$country);
			include_once('./menu_escadrille.php');
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
	else
	{
		$titre="MIA";
		$mes="<h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
		$img="<img src='images/unites".$country.".jpg'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>