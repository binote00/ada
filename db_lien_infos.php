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
			$con=dbconnecti();
			$Lieu_Nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Lieu WHERE ID='$Lieu'"),0);
			mysqli_close($con);
			$con=dbconnecti(1);
			$result2=mysqli_query($con,"SELECT Lieu1,Lieu2,Route,Train FROM Lieux_Links WHERE Lieu1='$Lieu' OR Lieu2='$Lieu'");
			mysqli_close($con);
			if($result2) 
			{
				while($data=mysqli_fetch_array($result2))
				{
					$lieux_txt="";
					if($data['Route'] ==3)
						$route_txt="Une route locale ";
					elseif($data['Route'] ==2)
						$route_txt="Une route secondaire ";
					elseif($data['Route'] ==1)
						$route_txt="Une route principale ";
					else
						$train_txt="";
					if($data['Train'] ==2)
						$train_txt="Une ligne de chemin de fer secondaire";
					elseif($data['Train'] ==1)
						$train_txt="Une ligne de chemin de fer principale";
					else
						$train_txt="";
					if($data['Lieu1'] ==$Lieu)
						$lieu_txt="<b>".$Lieu_Nom."</b> vers ".GetData("Lieu","ID",$data['Lieu2'],"Nom")." : ";
					else
						$lieu_txt=GetData("Lieu","ID",$data['Lieu1'],"Nom")." vers <b>".$Lieu_Nom."</b> : ";
					$output.=$lieu_txt.$route_txt.$train_txt."<br>";
				}
				mysqli_free_result($result2);
			}
			echo $output;
		}
	}
}
?>