<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$Division=Insec($_POST['Div']);
	$Base=Insec($_POST['Base']);
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0 and $Division >0 and $Base >0)
	{
		$con=dbconnecti();
		$resetbase=mysqli_query($con,"UPDATE Division SET Base='$Base' WHERE ID='$Division'");
		$resultb=mysqli_query($con,"SELECT a.Nom,l.Nom as Ville FROM Division as a,Lieu as l WHERE a.Base=l.ID AND a.ID='$Division'");
		mysqli_close($con);
		if($resultb)
		{
			while($data=mysqli_fetch_array($resultb))
			{
				$Armee_Nom=$data['Nom'];
				$Base_Nom=$data['Ville'];
			}
			mysqli_free_result($resultb);
		}
		/*$titre="Base arrière";
		$mes="La base arrière de la <b>".$Armee_Nom."</b> a été déplacé à <b>".$Base_Nom."</b>";
		$img="<img src='images/em".$country.".jpg'>";
		include_once('./default.php');*/
        header('Location: ./index.php?view=ground_em');
	}
	else
		echo "<h1>Vous n'êtes pas autorisé à effectuer cette action!</h1>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";