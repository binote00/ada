<?
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if($AccountID >0)
{
	include_once('./jfv_include.inc.php');
	$Encodage=GetData("Joueur","ID",$AccountID,"Encodage");
	if($Encodage ==2)
	{
		$Lieu=Insec($_GET['id']);
		if($Lieu)
		{
			$output="<option value='0'>Aucun</option>";
			$con=dbconnecti(1);
			$resultl=mysqli_query($con,"SELECT Lieu1,Lieu2 FROM Lieux_Links WHERE Lieu1='$Lieu' OR Lieu2='$Lieu'");
			mysqli_close($con);
			if($resultl) 
			{
				while($datal=mysqli_fetch_array($resultl))
				{
					if($datal['Lieu1'] ==$Lieu)
						$lieux_exclus[]=$datal['Lieu2'];
					else
						$lieux_exclus[]=$datal['Lieu1'];
				}
				mysqli_free_result($resultl);
			}
			if(is_array($lieux_exclus))
			{
				if(array_count_values($lieux_exclus) >0)
					$lieux_exclus_imp=implode(',',$lieux_exclus);
			}
			$con=dbconnecti();
			$resulto=mysqli_query($con,"SELECT ID,Nom,Longitude,Latitude FROM Lieu WHERE ID='$Lieu'");
			$result=mysqli_query($con,"SELECT ID,Nom,Longitude,Latitude FROM Lieu WHERE Zone<>6 AND ID NOT IN (".$Lieu.",".$lieux_exclus_imp.") ORDER BY Nom ASC");
			mysqli_close($con);
			if($resulto) 
			{
				while($datao=mysqli_fetch_array($resulto))
				{
					$longa=$datao['Longitude'];
					$lata=$datao['Latitude'];
				}
				mysqli_free_result($resulto);
			}
			if($result) 
			{
				while($data=mysqli_fetch_array($result))
				{
					$Dist=GetDistance(0,0,$longa,$lata,$data['Longitude'],$data['Latitude']);
					if($Dist[0] <=100)
						$output.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
				}
				mysqli_free_result($result);
			}
			echo $output;
		}
	}
}
?>