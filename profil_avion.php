<?php
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");	
	if($Premium >0 and $PlayerID ==1)
	{
		echo "<h1>Apparence de votre avion personnel</h1>";
		$Profil=Insec($_POST['profil_avion']);
		$Avion=Insec($_POST['avion']);
		if($Profil >0 and $Avion >0)
		{
			SetData("Pilote","Profil_avion",$Profil,"ID",$PlayerID);
			echo "Votre avion personnel a été mis à jour ! <p><img src='images/profils/profil".$Avion."_".$Profil.".png'></p>";
		}
		else
		{		
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Credits,Avion_Perso,Front,Profil_avion FROM Pilote WHERE ID='$PlayerID'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Front=$data['Front'];
					$Avion_P=$data['Avion_Perso'];
					$Profil_avion_ori=$data['Profil_avion'];
					$Credits=$data['Credits'];
				}
				mysqli_free_result($result);
				unset($result);
			}
			if($Avion_P >0)
			{
				$Avion=GetAvionImg("Avions_Persos",$Avion_P);
				echo "<h2>Apparence actuelle</h2><img src='images/profils/profil".$Avion."_".$Profil_avion_ori.".png'><h2>Choix de l'apparence</h2>
				<form action='index.php?view=profil_avion' method='post'><input type='hidden' name='avion' value='".$Avion."'>";
				for($i=1;$i<10;$i++)
				{
					$img="profil".$Avion."_".$i.".png";
					if(is_file('images/profils/'.$img))
						echo "<input type='Radio' name='profil_avion' value='".$i."'><img src='images/profils/".$img."'><br>";
				}	
				echo "<br><input type='Submit' class='btn btn-default' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></form>
				<p class='lead'>Ce choix est purement esthétique et n'influe en rien sur les capacités de l'avion, notamment sur son camouflage!</p>";
			}
			else
				echo "Vous n'avez pas d'avion personnalisé!";
		}
	}
	else
		echo "Cette option est réservée aux utilisateurs Premium!";
}
else
	header("Location: ./tsss.php");
?>