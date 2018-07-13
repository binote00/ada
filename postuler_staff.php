<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$Poste=Insec($_GET['poste']);
	$country=$_SESSION['country'];
	$Unit=GetData("Pilote","ID",$PlayerID,"Unit");
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,Avancement,Reputation,Commandement,Gestion FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Nom=$data['Nom'];
			$Avancement=$data['Avancement'];
			$Reput=$data['Reputation'];
			$Commandement=$data['Commandement'];
			$Gestion=$data['Gestion'];
		}
		mysqli_free_result($result);
		unset($result);
	}
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,Commandant,Officier_Adjoint,Officier_Technique FROM Unit WHERE ID='$Unit'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unit_Nom=$data['Nom'];
			$Commandant=$data['Commandant'];
			$Officier_Adjoint=$data['Officier_Adjoint'];
			$Officier_Technique=$data['Officier_Technique'];
		}
		mysqli_free_result($result);
		unset($result);
	}				
	switch($Poste)
	{
		case 1:
			$Fonction="Commandant";
			//Tampon date escadrille
			$con=dbconnecti(4);
			$resultm=mysqli_query($con,"SELECT `Date` FROM Events WHERE Event_Type=31 AND PlayerID='$PlayerID' ORDER BY ID DESC LIMIT 1");
			mysqli_close($con);
			if($resultm)
			{
				$data=mysqli_fetch_array($resultm);
				$Date_Mutation=$data[0];
				if($Date_Mutation)
				{
					$con=dbconnecti();
					$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date','$Date_Mutation')"),0);
					mysqli_close($con);
				}
				else
				{
					$con=dbconnecti();
					$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date','2012-09-01')"),0);
					mysqli_close($con);
				}
			}
			if($Avancement >9999 and $Reput >9999 and $Commandement >10 and $Gestion >10 and $Datediff >3)
			{
				$Cdt=$Commandant;
				if($Cdt)
				{
					$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
					$Cdt_Activite=GetData("Pilote","ID",$Cdt,"Credits_date");
					if($Avancement >$Cdt_Avance or $Cdt_Activite+7 <$Date)
					{
						$tr="yes";
						SendMsgOff($Cdt,0,$Nom." a été nommé ".GetStaff($country,1)." du ".$Unit_Nom.". Vous êtes libéré de votre charge.","Remise de commandement",0,3);
					}
					elseif($Avancement == $Cdt_Avance)
					{
						$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
						if($Reput > $Cdt_Reput)
						{
							$tr="yes";
							SendMsgOff($Cdt,0,$Nom." a été nommé ".GetStaff($country,1)." du ".$Unit_Nom.". Vous êtes libéré de votre charge.","Remise de commandement",0,3);
						}
						else
							$tr="no";
					}
					else
						$tr="no";
				}
				else
					$tr="yes";
			}
			else
				$tr="no";
		break;
		case 2:
			$Fonction="Officier_Adjoint";
			if($Avancement >1499 and $country >6)$Avancement+=3500;
			if($Avancement >4999 and $Reput >999 and $Gestion >10)
			{
				$Cdt=$Officier_Adjoint;
				if($Cdt)
				{
					$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
					$Cdt_Activite=GetData("Pilote","ID",$Cdt,"Credits_date");
					if($Avancement > $Cdt_Avance or $Cdt_Activite+7 <$Date)
					{
						$tr="yes";
						SendMsgOff($Cdt,0,$Nom." a été nommé ".GetStaff($country,2)." du ".$Unit_Nom.". Vous êtes libéré de votre charge.","Remise de commandement",0,3);
					}
					elseif($Avancement == $Cdt_Avance)
					{
						$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
						if($Reput > $Cdt_Reput)
						{
							$tr="yes";
							SendMsgOff($Cdt,0,$Nom." a été nommé ".GetStaff($country,2)." du ".$Unit_Nom.". Vous êtes libéré de votre charge.","Remise de commandement",0,3);
						}
						else
							$tr="no";
					}
					else
						$tr="no";
				}
				else
					$tr="yes";
			}
			else
				$tr="no";
		break;
		case 3:
			$Fonction="Officier_Technique";
			if($Avancement >499 and $country >6)$Avancement+=1000;
			if($Avancement >1499 and $Reput >499)
			{
				$Cdt=$Officier_Technique;
				if($Cdt)
				{
					$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
					$Cdt_Activite=GetData("Pilote","ID",$Cdt,"Credits_date");
					if($Avancement > $Cdt_Avance or $Cdt_Activite + 7 < $Date)
					{
						$tr="yes";
						SendMsgOff($Cdt,0,$Nom." a été nommé ".GetStaff($country,3)." du ".$Unit_Nom.". Vous êtes libéré de votre charge.","Remise de commandement",0,3);
					}
					elseif($Avancement == $Cdt_Avance)
					{
						$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
						if($Reput > $Cdt_Reput)
						{
							$tr="yes";
							SendMsgOff($Cdt,0,$Nom." a été nommé ".GetStaff($country,3)." du ".$Unit_Nom.". Vous êtes libéré de votre charge.","Remise de commandement",0,3);
						}
						else
							$tr="no";
					}
					else
						$tr="no";
				}
				else
					$tr="yes";
			}
			else
				$tr="no";
		break;
		case 4:
			$tr="yes";
		break;
	}		
	if($tr =="yes")
	{
		if($Commandant ==$PlayerID)
			$Poste_field="Commandant";
		elseif($Officier_Adjoint ==$PlayerID)
			$Poste_field="Officier_Adjoint";
		elseif($Officier_Technique ==$PlayerID)
			$Poste_field="Officier_Technique";
		if($Poste_field)
			$query_field=",".$Poste_field."=NULL";
		$con=dbconnecti();
		$update_ok=mysqli_query($con,"UPDATE Unit SET $Fonction='$PlayerID',Staff_Date='$Date'".$query_field." WHERE ID='$Unit'");
		mysqli_close($con);
		if($update_ok)
			$mes="Votre demande est acceptée !<br>Félicitations pour vos nouvelles fonctions !";
		else
			$mes="Votre demande a subit la lourdeur de la bureaucratie et votre dossier s'est perdu dans les limbes de l'administration !";
	}
	else
		$mes.="Votre demande est rejetée par votre hiérarchie!";
	$titre="Candidature";
	$img="<img src='images/transfer_".$tr.$country.".jpg'>";
	include_once('./index.php');
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>