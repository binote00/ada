<?
require_once('./jfv_inc_sessions.php');
//$PlayerID=$_SESSION['PlayerID'];
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID'])) // AND $PlayerID >0)
{
	if($OfficierEMID >0)
	/*$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0)*/
	{	
		include_once('./jfv_include.inc.php');
		include_once('./jfv_txt.inc.php');
		$country=$_SESSION['country'];
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Front,Avancement,Credits,Admin FROM Officier_em WHERE ID='$OfficierEMID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Front=$data['Front'];
				$Avancement=$data['Avancement'];
				$Credits=$data['Credits'];
				$Admin=$data['Admin'];
			}
			mysqli_free_result($result);
			unset($result);
		}
		if($Front ==99)
		{
			$con=dbconnecti(3);
			$ok=mysqli_query($con,"SELECT c.*,o.Nom,o.Avancement FROM Chat as c,Officier as o WHERE c.PlayerID=o.ID AND c.Pays='$country' ORDER BY c.Date DESC LIMIT 25");
			mysqli_close($con);
			if($ok)
			{
				while($data=mysqli_fetch_array($ok,MYSQLI_ASSOC)) 
				{
					$Grade=GetAvancement($data['Avancement'],$country);
					$annee=substr($data['Date'],0,4);
					$mois=substr($data['Date'],5,2);
					$jour=substr($data['Date'],8,2);
					$Msg_Off.="<p>".$jour."-".$mois."-".$annee.". Du <b>".$Grade[0]." ".$data['Nom']."</b> à tous les officiers du front ".GetFront($data['Front']).":<p><i>".nl2br($data['Msg'])."</i></p></p><hr>";
				}
				mysqli_free_result($ok);
			}
			if($Msg_Off =="")$Msg_Off="Aucun message n'a été posté par les états-majors.";
			echo "<table class='table'><thead><tr><th>Communications des états-majors</th></tr></thead><tr><td width='100%' rowspan='10' align='left'><div style='overflow:auto; height: 300px;'>".$Msg_Off."</div></td></tr></table>";
		}
		elseif($Front ==12)
			$Msg_Off="Vous ne faites partie d'aucun état-major.";
		else
		{
			//Chat Off
			$con=dbconnecti(3);
			$ok=mysqli_query($con,"SELECT c.*,o.Nom,o.Avancement FROM Chat as c,Officier as o WHERE c.PlayerID=o.ID AND c.Front='$Front' AND c.Pays='$country' ORDER BY c.Date DESC LIMIT 10");
			mysqli_close($con);
			if($ok)
			{
				while($data=mysqli_fetch_array($ok,MYSQLI_ASSOC)) 
				{
					$Grade=GetAvancement($data['Avancement'],$country);
					$annee=substr($data['Date'],0,4); //str_replace("2012","1940",substr($data['Date'],0,4));
					$mois=substr($data['Date'],5,2);
					$jour=substr($data['Date'],8,2);
					$Msg_Off.="<p>".$jour."-".$mois."-".$annee.". Du <b>".$Grade[0]." ".$data['Nom']."</b> à tous les officiers:<p><i>".nl2br($data['Msg'])."</i></p></p><hr>";
				}
				mysqli_free_result($ok);
			}
			if($Msg_Off =="")$Msg_Off="Aucun message n'a été posté par votre Etat-Major.";
			$con=dbconnecti();	
			$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Cdt_Chasse,Cdt_Bomb,Cdt_Reco,Cdt_Atk,Officier_Rens,Adjoint_Terre,Officier_Mer FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
			mysqli_close($con);
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Commandant=$data['Commandant'];
					$Officier_Adjoint=$data['Adjoint_EM'];
					$Officier_EM=$data['Officier_EM'];
					$Cdt_Chasse=$data['Cdt_Chasse'];
					$Cdt_Bomb=$data['Cdt_Bomb'];
					$Cdt_Reco=$data['Cdt_Reco'];
					$Cdt_Atk=$data['Cdt_Atk'];
					$Officier_Rens=$data['Officier_Rens'];
					$Adjoint_Terre=$data['Adjoint_Terre'];
					$Officier_Mer=$data['Officier_Mer'];
				}
				mysqli_free_result($result2);
			}
			include_once('./menu_em.php');	
			if($OfficierEMID ==$Commandant)
			{
				echo "<form action='index.php?view=officier_chat' method='post'>
					<input type='hidden' name='Officier' value='".$OfficierEMID."'>
					<input type='hidden' name='Pays' value='".$country."'>
					<input type='hidden' name='Front' value='".$Front."'>
					<table class='table'>
						<thead><tr><th colspan='2'>Ordre du jour</th></tr></thead>
						<tr><td rowspan='3'><img src='images/staff".$country.".jpg'></td><td>Envoyer l'ordre du jour aux officiers de votre front (250 caractères max.)<td></tr>
						<tr><td><textarea name='officier_msg' rows='5' cols='50' class='form-control'></textarea></td></tr>
						<tr><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
					</table></form>";
			}
			echo "<table class='table'><thead><tr><th>Communications de l'état-major</th></tr></thead><tr><td width='100%' rowspan='10' align='left'><div style='overflow:auto; height: 300px;'>".$Msg_Off."</div></td></tr></table>";
		}
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
?>
