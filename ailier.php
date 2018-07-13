<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	$ID=Insec($_GET['pilote']);
	$country=$_SESSION['country'];
	$con=dbconnecti();	
	$result=mysqli_query($con,"SELECT Unit,Reputation,Avancement FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unit=$data['Unit'];
			$Reput=$data['Reputation'];
			$Grade=$data['Avancement'];
		}
		mysqli_free_result($result);
	}
	/*if(strpos($ID,"_") !==false)
	{
		$ID=strstr($ID,'_',true);
		$Pilote_db="Pilote";
	}*/
	//$Pilotage=GetData("Pilote","ID",$PlayerID,"Pilotage");
	if($Reput >499)
	{
		$con=dbconnecti();
		$ID=mysqli_real_escape_string($con,$ID);
		$result=mysqli_query($con,"SELECT Nom,Unit,Reputation,Avancement FROM Pilote_IA WHERE ID='$ID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Ailier_nom=$data['Nom'];
				$Ailier_Unit=$data['Unit'];
				$Reput_Ailier=$data['Reputation'];
				$Grade_Ailier=$data['Avancement'];
			}
			mysqli_free_result($result);
		}
		if($Reput >$Reput_Ailier and $Grade >$Grade_Ailier and $Ailier_Unit ==$Unit)
		{
			$con=dbconnecti();
			$update_ok=mysqli_query($con,"UPDATE Pilote SET Ailier='$ID' WHERE ID='$PlayerID'");
			mysqli_close($con);
			if($update_ok)
			{
				$mes=$Ailier_nom.' est votre nouvel ailier !';
				$img='<img src=\'images/pilotes'.$country.'.jpg\'>';
			}
		}
		else
		{
			$tr="no";
			$mes.=$Ailier_nom.' refuse d\'être votre ailier, il s\'estime trop bon pour ça!';
		}
	}
	else
	{
		$tr="no";
		$mes.="Votre réputation n'est pas suffisante pour pouvoir choisir votre ailier!";
	}
	if($tr =="no")$img='<img src=\'images/transfer_'.$tr.$country.'.jpg\'>';
	include_once('./index.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>