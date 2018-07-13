<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$country =$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0 and $PlayerID >0)
	{
		$Acces_officier=false;
		$Acces_Staff=false;
		$Acces_Cdt=false;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Unit,Avancement FROM Pilote WHERE ID='$PlayerID'");
		if($result)
		{
			while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$Unite=$data['Unit'];
				$Avancement=$data['Avancement'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		$resultu=mysqli_query($con,"SELECT Nom,Commandant,Officier_Adjoint,Officier_Technique,Type FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($resultu)
		{
			while($data=mysqli_fetch_array($resultu, MYSQLI_ASSOC))
			{
				$Unite_Nom=$data['Nom'];
				$Unite_Type=$data['Type'];
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Officier_Technique=$data['Officier_Technique'];
			}
			mysqli_free_result($resultu);
			unset($data);
		}
		if($Unite_Type !=8)
			include_once('./menu_escadrille.php');
		else
			echo '<h1>'.$Unite_Nom."</h1><div class='alert alert-info'>Lorsque vous aurez terminé votre formation et que votre demande de mutation sera validée, vous pourrez gérer les différents aspects de votre nouvelle escadrille.</div>";
		echo "<h2>Gestion</h2><div class='row'><div class='col-md-6'><img src='images/staff".$country.".jpg' style='width:100%;'></div></div>";		
	}
	else
		echo "<h1>MIA</h1><img src='images/unites".$country.".jpg'><h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>
