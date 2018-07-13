<?
/*require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$Poste = Insec($_GET['poste']);
	$PlayerID = $_SESSION['PlayerID'];
	$country = $_SESSION['country'];
	if($PlayerID >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Front,Credits,Missions_Jour,Missions_Max,Reputation,Avancement,Commandement,Renseignement FROM Pilote WHERE ID='$PlayerID'");
		mysqli_close($con);
		if($result)
		{
			while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Nom = $data['Nom'];
				$Front = $data['Front'];
				$Credits = $data['Credits'];
				$Missions_Jour = $data['Missions_Jour'];
				$Missions_Max = $data['Missions_Max'];
				$Reput = $data['Reputation'];
				$Avancement = $data['Avancement'];
				$Commandement = $data['Commandement'];
				$Renseignement = $data['Renseignement'];
			}
			mysqli_free_result($result);
			unset($data);
		}		
			//Init Officiers
			$con=dbconnecti();	
			$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Cdt_Chasse,Cdt_Bomb,Cdt_Reco,Cdt_Atk,Officier_Rens FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
			mysqli_close($con);
			if($result2)
			{
				while($data = mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Commandant = $data['Commandant'];
					$Officier_Adjoint = $data['Adjoint_EM'];
					$Officier_EM = $data['Officier_EM'];
					$Cdt_Chasse = $data['Cdt_Chasse'];
					$Cdt_Bomb = $data['Cdt_Bomb'];
					$Cdt_Reco = $data['Cdt_Reco'];
					$Cdt_Atk = $data['Cdt_Atk'];
					$Officier_Rens = $data['Officier_Rens'];
				}
				mysqli_free_result($result2);
			}
			/*if($Commandant == $PlayerID)
				SetDoubleData("Pays","Commandant",0,"Pays_ID",$country,"Front",$Front);
			elseif($Officier_Adjoint == $PlayerID)
				SetDoubleData("Pays","Adjoint_EM",0,"Pays_ID",$country,"Front",$Front);
			elseif($Officier_EM == $PlayerID)
				SetDoubleData("Pays","Officier_EM",0,"Pays_ID",$country,"Front",$Front);
			elseif($Cdt_Chasse == $PlayerID)
				SetDoubleData("Pays","Cdt_Chasse",0,"Pays_ID",$country,"Front",$Front);
			elseif($Cdt_Bomb == $PlayerID)
				SetDoubleData("Pays","Cdt_Bomb",0,"Pays_ID",$country,"Front",$Front);
			elseif($Cdt_Reco == $PlayerID)
				SetDoubleData("Pays","Cdt_Reco",0,"Pays_ID",$country,"Front",$Front);
			elseif($Cdt_Atk == $PlayerID)
				SetDoubleData("Pays","Cdt_Atk",0,"Pays_ID",$country,"Front",$Front);
			elseif($Officier_Rens == $PlayerID)
				SetDoubleData("Pays","Officier_Rens",0,"Pays_ID",$country,"Front",$Front);*/
/*			switch($Poste)
			{
				case 1:
					$Fonction="Commandant";
					if($Avancement > 49999 and $Reput > 49999 and $Commandement > 100 and $Missions_Jour < 6 and $Missions_Max < 6 and $Credits > 23)
					{
						$Creditsr=-24;
						$Cdt=$Commandant;
						if($Cdt)
						{
							$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
							if($Avancement > $Cdt_Avance)
							{
								$tr="yes";
								SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,1).". Vous êtes libéré de votre charge.","Remise de commandement");
							}
							elseif($Avancement == $Cdt_Avance)
							{
								$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
								if($Reput > $Cdt_Reput)
								{
									$tr="yes";
									SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,1).". Vous êtes libéré de votre charge.","Remise de commandement");
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
					$Fonction="Adjoint_EM";
					if($Avancement > 24999 and $Reput > 9999 and $Commandement > 50 and $Missions_Jour < 6 and $Missions_Max < 6 and $Credits > 11)
					{
						$Creditsr=-12;
						$Cdt=$Officier_Adjoint;
						if($Cdt)
						{
							$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
							if($Avancement > $Cdt_Avance)
							{
								$tr="yes";
								SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,2).". Vous êtes libéré de votre charge.","Remise de commandement");
							}
							elseif($Avancement == $Cdt_Avance)
							{
								$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
								if($Reput > $Cdt_Reput)
								{
									$tr="yes";
									SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,2).". Vous êtes libéré de votre charge.","Remise de commandement");
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
					$Fonction="Officier_EM";
					if($Avancement > 4999 and $Reput > 1999 and $Missions_Jour < 6 and $Missions_Max < 6 and $Credits > 1)
					{
						$Creditsr=-2;
						$Cdt=$Officier_EM;
						if($Cdt)
						{
							$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
							if($Avancement > $Cdt_Avance)
							{
								$tr="yes";
								SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,3).". Vous êtes libéré de votre charge.","Remise de commandement");
							}
							elseif($Avancement == $Cdt_Avance)
							{
								$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
								if($Reput > $Cdt_Reput)
								{
									$tr="yes";
									SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,3).". Vous êtes libéré de votre charge.","Remise de commandement");
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
				case 9:
					$Fonction="Cdt_Chasse";
					if($Avancement > 4999 and $Reput > 1999 and $Missions_Jour < 6 and $Missions_Max < 6 and $Credits > 1)
					{
						$Creditsr=-2;
						$Cdt=$Cdt_Chasse;
						if($Cdt)
						{
							$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
							if($Avancement > $Cdt_Avance)
							{
								$tr="yes";
								SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,9).". Vous êtes libéré de votre charge.","Remise de commandement");
							}
							elseif($Avancement == $Cdt_Avance)
							{
								$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
								if($Reput > $Cdt_Reput)
								{
									$tr="yes";
									SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,9).". Vous êtes libéré de votre charge.","Remise de commandement");
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
				case 10:
					$Fonction="Cdt_Bomb";
					if($Avancement > 4999 and $Reput > 1999 and $Missions_Jour < 6 and $Missions_Max < 6 and $Credits > 1)
					{
						$Creditsr=-2;
						$Cdt=$Cdt_Bomb;
						if($Cdt)
						{
							$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
							if($Avancement > $Cdt_Avance)
							{
								$tr="yes";
								SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,10).". Vous êtes libéré de votre charge.","Remise de commandement");
							}
							elseif($Avancement == $Cdt_Avance)
							{
								$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
								if($Reput > $Cdt_Reput)
								{
									$tr="yes";
									SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,10).". Vous êtes libéré de votre charge.","Remise de commandement");
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
				case 11:
					$Fonction="Cdt_Reco";
					if($Avancement > 4999 and $Reput > 1999 and $Missions_Jour < 6 and $Missions_Max < 6 and $Credits > 1)
					{
						$Creditsr=-2;
						$Cdt=$Cdt_Reco;
						if($Cdt)
						{
							$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
							if($Avancement > $Cdt_Avance)
							{
								$tr="yes";
								SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,11).". Vous êtes libéré de votre charge.","Remise de commandement");
							}
							elseif($Avancement == $Cdt_Avance)
							{
								$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
								if($Reput > $Cdt_Reput)
								{
									$tr="yes";
									SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,11).". Vous êtes libéré de votre charge.","Remise de commandement");
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
				case 12:
					$Fonction="Cdt_Atk";
					if($Avancement > 4999 and $Reput > 1999 and $Missions_Jour < 6 and $Missions_Max < 6 and $Credits > 1)
					{
						$Creditsr=-2;
						$Cdt=$Cdt_Atk;
						if($Cdt)
						{
							$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
							if($Avancement > $Cdt_Avance)
							{
								$tr="yes";
								SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,12).". Vous êtes libéré de votre charge.","Remise de commandement");
							}
							elseif($Avancement == $Cdt_Avance)
							{
								$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
								if($Reput > $Cdt_Reput)
								{
									$tr="yes";
									SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,12).". Vous êtes libéré de votre charge.","Remise de commandement");
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
					$Fonction="Officier_Rens";
					if($Avancement > 4999 and $Reput > 1999 and $Renseignement > 75 and $Missions_Jour < 6 and $Missions_Max < 6 and $Credits > 1)
					{
						$Creditsr=-2;
						$Cdt=$Officier_Rens;
						if($Cdt)
						{
							$Cdt_Avance=GetData("Pilote","ID",$Cdt,"Avancement");
							if($Avancement > $Cdt_Avance)
							{
								$tr="yes";
								SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,4).". Vous êtes libéré de votre charge.","Remise de commandement");
							}
							elseif($Avancement == $Cdt_Avance)
							{
								$Cdt_Reput=GetData("Pilote","ID",$Cdt,"Reputation");
								if($Reput > $Cdt_Reput)
								{
									$tr="yes";
									SendMsg($Cdt,0,"L'officier ".$Nom." a été nommé ".GetStaff($country,4).". Vous êtes libéré de votre charge.","Remise de commandement");
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
			}
		if($tr =="yes")
		{
			if($Poste ==1)
			{
				UpdateCarac($PlayerID,"Duperie",-50);
				UpdateCarac($PlayerID,"Reputation",1000);
			}
			elseif($Poste ==2)
			{
				UpdateCarac($PlayerID,"Duperie",-10);
				UpdateCarac($PlayerID,"Reputation",500);
			}
			elseif($Poste ==4)
			{
				UpdateCarac($PlayerID,"Duperie",10);
				UpdateCarac($PlayerID,"Reputation",-1000);
			}
			/*$Unit=GetData("Pilote","ID",$PlayerID,"Unit");
			$Commandant_UT=GetData("Unit","ID",$Unit,"Commandant");
			$Officier_Adjoint_UT=GetData("Unit","ID",$Unit,"Officier_Adjoint");
			$Officier_Technique=GetData("Unit","ID",$Unit,"Officier_Technique");
			if($Commandant_UT ==$PlayerID)
			{
				SetData("Unit","Commandant",NULL,"ID",$Unit);
			}
			elseif($Officier_Adjoint_UT ==$PlayerID)
			{
				SetData("Unit","Officier_Adjoint",NULL,"ID",$Unit);
			}
			elseif($Officier_Technique ==$PlayerID)
			{
				SetData("Unit","Officier_Technique",NULL,"ID",$Unit);
			}*/
/*			$con=dbconnecti();
			$update_ok=mysqli_query($con,"UPDATE Pays SET $Fonction='$PlayerID' WHERE Pays_ID='$country' AND Front='$Front'");
			mysqli_close($con);
			if($update_ok)
				$mes.="Votre demande est acceptée !<br>Félicitations pour vos nouvelles fonctions !";
			else
				$mes.="Votre demande a subit la lourdeur de la bureaucratie et votre dossier s'est perdu dans les limbes de l'administration !";
			$skills=MoveCredits($PlayerID,14,$Creditsr);
			UpdateCarac($PlayerID,"Missions_Jour",1);
			UpdateCarac($PlayerID,"Missions_Max",1);
		}
		else
			$mes.="<p>Votre demande est rejetée par votre hiérarchie!</p>";
		$img="<img src='images/transfer_".$tr.$country.".jpg'>";
		include_once('./index.php');
	}
	else
		echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
*/
?>