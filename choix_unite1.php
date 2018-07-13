<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	$Pilote=Insec($_POST['Pilote']);
	$Unite=Insec($_POST['unit']);
	$country=$_SESSION['country'];
	if($Pilote >0 and $Unite >0 and $country)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,Unit,Reputation,Credits,Crashs_Jour,Missions_Jour,Avancement,Front FROM Pilote WHERE ID='$Pilote'");
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
        $Cdt=mysqli_result(mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$country'"),0);
		mysqli_close($con);
		if($results)
		{
			while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
			{
				$Skills_Pil[]=$data['Skill'];
			}
			mysqli_free_result($results);
		}
		$con=dbconnecti(4);
		$Crash=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type IN (11,12) AND PlayerID='$Pilote' AND Avion_Nbr=1"),0);
		$Perdu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=34 AND PlayerID='$Pilote'"),0);
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
                $Nom_pil=$data['Nom'];
				$Unite_orig=$data['Unit'];
				$Reput=$data['Reputation'];
				$Credits=$data['Credits'];
				$Crashs=$data['Crashs_Jour'];
				$Missions_Jour=$data['Missions_Jour'];
				$Avancement=$data['Avancement'];
				$Front_Ori=$data['Front'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		$Cr_Mut=5;
		if(is_array($Skills_Pil))
		{
			if(in_array(114,$Skills_Pil))
				$Cr_Mut=0;
		}
		/*$Type_Unit_Ori=GetData("Unit","ID",$Unite_orig,"Type");
		if($Type_Unit_Ori ==8 or $Avancement <100000){*/
			/*$Cr_Mut=15+ceil(($Crash+$Perdu)/10);
			$Priorite=GetData("Unit","ID",$Unite,"Priorite");
			if($Note >100)
				$Cr_Mut=10;
			if($Priorite)
				$Cr_Mut=5;
			$con=dbconnecti();
			$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Unit='$Unite'"),0);
			mysqli_close($con);
			if($Pilotes >=12){
				$mes.="Votre demande est refusée par votre hiérarchie! <br>Cette unité a fermé son recrutement.";
			}
			else*/if($Credits >=$Cr_Mut and $Missions_Jour <6){
				$mes.='<div class="alert alert-warning">Votre demande a été transmise à votre hiérarchie <br>Vous serez bientôt averti de leur décision</div>';
				SetData("Pilote","Mutation",$Unite,"ID",$Pilote);
				MoveCredits($Pilote,11,-$Cr_Mut);
				UpdateCarac($Pilote,"Missions_Jour",1);
				UpdateCarac($Pilote,"Missions_Max",1);
                if($Cdt){
                    require_once('./jfv_msg.inc.php');
                    $Msg="Le pilote ".$Nom_pil." a effectué une demande de mutation.\n Veuillez accepter ou refuser la demande dans le menu Etat-Major Air / Effectifs / Mutation.\n\n ";
                    SendMsgOff($Cdt,$OfficierEMID,$Msg,"Demande de mutation",3,1);
                }
			}
			else
				$mes.='<div class="alert alert-danger">Votre demande est refusée par votre hiérarchie! <br>Vous n\'êtes pas jugé suffisamment apte pour rejoindre cette unité</div>';
			$img=Afficher_Image('images/em'.$country.'.jpg','images/em4.jpg','Etat-Major');
		/*}
		else
		{
			$Ratio=GetRatio($Pilote);
			if($Crashs_Jour >2)
			{
				$tr="no";
				$mes.="Votre demande est refusée par votre hiérarchie!<br>Vous démolissez tellement d'avions,que personne ne veut de vous dans son escadrille!";
			}
			elseif($Ratio[0] >$Ratio_Unit)
			{
				$tr="no";
				$mes.="Votre demande est rejetée par le commandant de l'unité de destination.<br>Il n'accepte pas les pilotes ayant un taux de perte aussi élevé.";
			}
			else
			{
				$Cr_Mut=15+ceil(($Crash+$Perdu)/10);
				$Priorite=GetData("Unit","ID",$Unite,"Priorite");
				if($Note >100)
					$Cr_Mut=10;
				if($Priorite)
					$Cr_Mut=5;
				$con=dbconnecti();
				$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Unit='$Unite'"),0);
				mysqli_close($con);
				if(($Avancement >99 OR $Reput >49) and $Credits >=$Cr_Mut and $Pilotes <12 and $Missions_Jour <6)
				{
					$con=dbconnecti();
					$ok=mysqli_query($con,"UPDATE Pilote SET Unit='$Unite' WHERE ID='$Pilote'");
					mysqli_close($con);
					if(!$ok)
					{
						$tr="no";
						$mes.="Erreur de mise à jour !";
					}
					else
					{
						//GetData Unit
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT Nom,Base FROM Unit WHERE ID='$Unite'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Unite_Nom=$data['Nom'];
								$Base=$data['Base'];
							}
							mysqli_free_result($result);
						}
						//GetData Unit_Ori
						$con=dbconnecti();
						$result=mysqli_query($con,"SELECT Commandant,Officier_Adjoint,Officier_Technique FROM Unit WHERE ID='$Unite_orig'");
						$result2=mysqli_query($con,"SELECT Nom,Latitude,Longitude FROM Lieu WHERE ID='$Base'");
						mysqli_close($con);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
							{
								$Commandant=$data['Commandant'];
								$Officier_Adjoint=$data['Officier_Adjoint'];
								$Officier_Technique=$data['Officier_Technique'];
							}
							mysqli_free_result($result);
						}
						//GetData Base
						if($result2)
						{
							while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								$Base_Nom=$data['Nom'];
								$Lat_base=$data['Latitude'];
								$Long_base=$data['Longitude'];
							}
							mysqli_free_result($result2);
						}

						$tr="yes";
						$mes.='Votre demande est acceptée par votre hiérarchie!
						<br><br>Votre nouvelle unité est le <b>'.$Unite_Nom.'</b><br><br>Vous êtes maintenant basé à '.$Base_Nom
						.'<br><br>Si vous possédiez un ailier dans votre ancienne unité,vous devez en choisir un nouveau parmi les pilotes de votre nouvelle unité.';
						$skills=MoveCredits($Pilote,11,-$Cr_Mut);
						UpdateCarac($Pilote,"Missions_Jour",1);
						UpdateCarac($Pilote,"Missions_Max",1);
						//Journal
						AddEvent($Avion_db,31,1,$Pilote,$Unite,$Base,$Unite_orig);
						//Officiers
						if($Commandant ==$Pilote)
						{
							SetData("Unit","Commandant",NULL,"ID",$Unite_orig);
						}
						elseif($Officier_Adjoint ==$Pilote)
						{
							SetData("Unit","Officier_Adjoint",NULL,"ID",$Unite_orig);
						}
						elseif($Officier_Technique ==$Pilote)
						{
							SetData("Unit","Officier_Technique",NULL,"ID",$Unite_orig);
						}

						//Front
						if($Long_base >67)
						{
							$Front=3;
							$mes.="<p>Votre unité opère sur le front Pacifique.</p>";
						}
						elseif($Long_base >14 and $Lat_base >44)
						{
							//Est
							$Front=1;
							$mes.="<p>Votre unité opère sur le front de l'Est.</p>";
						}
						elseif($Lat_base <45)
						{
							//Med
							$Front=2;
							$mes.="<p>Votre unité opère sur le front Méditerranéen.</p>";
						}
						else
						{
							//Ouest
							$Front=0;
							$mes.="<p>Votre unité opère sur le front Ouest</p>";
						}
						if($Avancement >4999 and $Front_Ori !=$Front)
						{
							$Commandant=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
							$Officier_Adjoint=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Adjoint_EM");
							$Officier_EM=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Officier_EM");
							$Officier_Rens=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Officier_Rens");
							if($Commandant ==$PlayerID)
							{
								SetDoubleData("Pays","Commandant",0,"Pays_ID",$country,"Front",$Front);
							}
							elseif($Officier_Adjoint ==$PlayerID)
							{
								SetDoubleData("Pays","Adjoint_EM",0,"Pays_ID",$country,"Front",$Front);
							}
							elseif($Officier_EM ==$PlayerID)
							{
								SetDoubleData("Pays","Officier_EM",0,"Pays_ID",$country,"Front",$Front);
							}
							elseif($Officier_Rens ==$PlayerID)
							{
								SetDoubleData("Pays","Officier_Rens",0,"Pays_ID",$country,"Front",$Front);
							}
						}
						//Set Vars Joueur
						$con=dbconnecti();
						$reset=mysqli_query($con,"UPDATE Pilote SET Front='$Front',Ailier=0,Escorte=0,Couverture=0,Ecole=0 WHERE ID='$Pilote'");
						mysqli_close($con);
					}
				}
				else
				{
					$tr="no";
					$mes.="Votre demande est refusée par votre hiérarchie!";
				}
			}
			$img.='<img src=\'images/transfer_'.$tr.$country.'.jpg\'>';
		}*/
	}
	else
	{
		$mes='<div class="alert alert-danger">Vous devez sélectionner une unité!</div>';
		$img ='<img src="images/transfer_no'.$country.'.jpg">';
		$menu="<a title='Demande de mutation' href='index.php?view=transfer' class='lien'>Accéder au formulaire de demande de mutation</a>";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
include_once('./index.php');