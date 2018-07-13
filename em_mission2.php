<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Unite_Type = Insec($_POST['Unite']);
	$OfficierEMID = $_SESSION['Officier_em'];
	if($OfficierEMID >0 AND $Unite_Type >0)
	{
		$Cible = Insec($_POST['Cible']);
		$Type = Insec($_POST['Type']);
		$country = $_SESSION['country'];
		if($Cible and $Type and $country)
		{
			$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
			$con=dbconnecti();	
			$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
			mysqli_close($con);
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Commandant=$data['Commandant'];
					$Officier_Adjoint=$data['Adjoint_EM'];
				}
				mysqli_free_result($result2);
			}
			if(($Commandant ==$OfficierEMID or $Officier_Adjoint ==$OfficierEMID) and $Front !=12)
			{
				SetDoubleData("Pays","Lieu_Mission".$Unite_Type,$Cible,"Pays_ID",$country,"Front",$Front);
				SetDoubleData("Pays","Type_Mission".$Unite_Type,$Type,"Pays_ID",$country,"Front",$Front);
				//mail('binote@hotmail.com','Aube des Aigles: Mission Faction',"Faction ".$country." / Joueur ".$PlayerID." / Cible ".$Cible." / Type ".$Type);
				//MoveCredits($PlayerID,3,-1);
				UpdateCarac($OfficierEMID,"Credits",-1,"Officier_em");
				UpdateCarac($OfficierEMID,"Avancement",2);
				UpdateCarac($OfficierEMID,"Reputation",1);
                $_SESSION['msg_em'] = 'Vos ordres ont été exécutés';
				/*$menu ="<a class='btn btn-default' title='Retour' href='index.php?view=em_mission'>Retour</a>";
				$img="<img src='images/mission".$country.".jpg'>";
				include_once('./index.php');*/
                header('Location : index.php?view=em_mission');
			}
			else
				echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
		}
	}
	else
		echo "<h1>Vous n'avez rien à faire ici !</h1>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';