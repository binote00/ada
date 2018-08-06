<?php
require_once './jfv_inc_sessions.php';
if(isset($_SESSION['AccountID']))
{	
	$PlayerID=$_SESSION['PlayerID'];
	$OfficierEMID=$_SESSION['Officier_em'];
	include_once './jfv_include.inc.php';
	$Unite=Insec($_POST['Unite']);
	if(($PlayerID >0 or $OfficierEMID >0) AND $Unite >0)
	{
        $_SESSION['esc'] = $Unite;
		$Option=Insec($_POST['mode']);
		if($Option ==13)
		{
			$armee=Insec($_POST['Armee']);
			SetData("Unit","Armee",$armee,"ID",$Unite);
            $_SESSION['msg_esc'] = 'Le commandement de l\'unité a été transféré au commandant d\'armée!';
            header( 'Location : index.php?view=em_ia');
		}
		elseif($Option ==9)
		{
			SetData("Unit","Ravit",1,"ID",$Unite);
            $_SESSION['msg_esc'] = 'Votre demande de ravitaillement a été envoyée au planificateur stratégique!';
            header( 'Location : index.php?view=em_ia');
		}
		elseif($Option ==8)
		{
			SetData("Unit","Ravit",2,"ID",$Unite);
            $_SESSION['msg_esc'] = 'Votre demande de mise à niveau du matériel a été envoyée au planificateur stratégique!';
            header( 'Location : index.php?view=em_ia');
		}
		elseif($Option ==7)
		{
			SetData("Unit","Ravit",0,"ID",$Unite);
            $_SESSION['msg_esc'] = 'Vous signalez au commandant de front que la demande a été traitée!';
            header( 'Location : index.php?view=em_ia');
		}
		elseif($Option ==11)
		{
			SetData("Unit","NoEM",1,"ID",$Unite);
            $_SESSION['msg_esc'] = 'Vous réservez cette unité pour le GHQ!';
            header( 'Location : index.php?view=em_ia');
		}
		elseif($Option ==12)
		{
			SetData("Unit","NoEM",0,"ID",$Unite);
            $_SESSION['msg_esc'] = 'Vous signalez au commandant de front que cette unité est à nouveau disponible pour le front!';
            header( 'Location : index.php?view=em_ia');
		}
		else
		{
			$Flight=Insec($_POST['flight']);
			$CT_Replace=Insec($_POST['CT']);
			$country=$_SESSION['country'];
			include_once './jfv_txt.inc.php';
			if($OfficierEMID >0)
				$DB='Officier_em';
			elseif($PlayerID >0){
				$DB='Pilote';
				$OfficierEMID=$PlayerID;
			}
			$con=dbconnecti();
            $Credits=mysqli_result(mysqli_query($con,"SELECT CT FROM Unit WHERE ID='$Unite'"),0);
			$result=mysqli_query($con,"SELECT Avancement,Front FROM $DB WHERE ID='$OfficierEMID'");
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Avancement=$data['Avancement'];
					$Front=$data['Front'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($Credits >=$CT_Replace and $Flight >0 and $CT_Replace >0)
			{
				$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
				$result=mysqli_query($con,"SELECT Nom,Type,Reputation,Base,Pays FROM Unit WHERE ID='$Unite'");
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$Unite_Nom=$data['Nom'];
						$Unite_Type=$data['Type'];
						$Unite_Reput=$data['Reputation'];
						$Unite_Base=$data['Reputation'];
						$Unite_Pays=$data['Pays'];
					}
					mysqli_free_result($result);
					unset($data);
				}
				$modele=false;
				if($country ==$Unite_Pays)
				{
					$Bonus=substr($Date_Campagne,2,2)-39;
					$Level=round($Unite_Reput/1000)+$Bonus+10; //+10 à la place de +1
					$Level_txt=' de niveau '.$Level;
					if($Option ==2)
					{
						if(IsAxe($country))
							$Allies=array(1,6,9,15,18,19,20,24);
						else
							$Allies=array(2,3,4,5,7,8,10,35,36);
						if($Unite_Type ==1)
							$query="SELECT DISTINCT ID,Nom,Type,Rating,Production,Stock,Usine1,DATE_FORMAT(Fin_Prod,'%d-%m-%Y') as Fin_Prod,Lease,Reserve FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Premium=0 AND Type IN(1,5) AND ((Engagement <='$Date_Campagne' AND Rating BETWEEN 0 AND '$Level') OR (Fin_Prod <='$Date_Campagne')) ORDER BY Rating DESC";
						else
							$query="SELECT DISTINCT ID,Nom,Type,Rating,Production,Stock,Usine1,DATE_FORMAT(Fin_Prod,'%d-%m-%Y') as Fin_Prod,Lease,Reserve FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Premium=0 AND Type='$Unite_Type' AND ((Engagement <='$Date_Campagne' AND Rating BETWEEN 0 AND '$Level') OR (Fin_Prod <='$Date_Campagne')) ORDER BY Rating DESC";
						$result=mysqli_query($con,$query);
						if($result)
						{
							while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
							{
								$Abattu=0;
								$DCA=0;
								$Service1=0;
								$Service2=0;
								$Service3=0;
								$Plane=$data['ID'];
								$Production=floor($data['Stock']);
								$Usine1=$data['Usine1'];
								$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Plane' AND PVP=1"),0);
								$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Plane'"),0);
								$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$Plane' AND Etat=1"),0);
								$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$Plane' AND Etat=1"),0);
								$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$Plane' AND Etat=1"),0);
								$resultu1=mysqli_query($con,"SELECT Nom,Flag,Flag_Usine FROM Lieu WHERE ID='$Usine1'");
                                $xp_avion=floor(mysqli_result(mysqli_query($con,"SELECT Exp FROM XP_Avions_IA WHERE Unite='$Unite' AND AvionID='$Plane'"),0))/10;
								if($resultu1)
								{
									while($datau1=mysqli_fetch_array($resultu1,MYSQLI_ASSOC))
									{
										$Usine1_Nom=$datau1['Nom'];
										$Usine1_Flag=$datau1['Flag'];
										$Usine1_Flag_Usine=$datau1['Flag_Usine'];
									}
									mysqli_free_result($resultu1);
								}
								if($data['Lease'] >0 and $data['Fin_Prod'] >$Date_Campagne)
								{
									if(in_array($Usine1_Flag,$Allies) and in_array($Usine1_Flag_Usine,$Allies))
										$lend_lease=true;
									else
										$lend_lease=false;
								}
								else
									$lend_lease=true;
								if($lend_lease)
								{
									$con4=dbconnecti(4);
									$Perdu=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$Plane' AND Avion_Nbr >0"),0);
									mysqli_close($con4);
									$Total_Pertes=$DCA+$Abattu+$Perdu+$Service1+$Service2+$Service3-$data['Reserve'];
									//if($Front ==99)$Production.='<br>'.$Usine1_Nom;
									$Dispos=$Production-$Total_Pertes;									
									if($Dispos >0)
										$modele.="<tr><td><input type='radio' name='avionc' value='".$data['ID']."'></td><td>".GetAvionIcon($data['ID'],$Unite_Pays,0,$Unite,$Front,$data['Nom'],true,false,true)."</td><td>".$data['Fin_Prod']."</td><td>".$data['Rating']."</td><td>".$xp_avion."</td><td>".$Dispos."</td></tr>";
									elseif($Front ==99)
										$modele.="<tr><td class='text-danger'>Rupture de Stock!</td><td>".GetAvionIcon($data['ID'],$Unite_Pays,0,$Unite,$Front,$data['Nom'],true,false,true)."</td><td>".$data['Fin_Prod']."</td><td>".$data['Rating']."</td><td>".$xp_avion."</td><td class='text-danger'>0</td></tr>";
								}
								else
									$modele.="<tr><td class='text-danger'>Lend-Lease!</td><td>".GetAvionIcon($data['ID'],$Unite_Pays,0,$Unite,$Front,$data['Nom'],true,false,true)."</td><td>".$data['Fin_Prod']."</td><td>".$data['Rating']."</td><td>".$xp_avion."</td><td class='text-danger'>0</td></tr>";
							}
							mysqli_free_result($result);
						}
					}
					else
					{
						if($Unite_Type ==1)
							$query="SELECT DISTINCT ID,Nom,Type,Rating,Production,Stock,DATE_FORMAT(Fin_Prod,'%d-%m-%Y') as Fin_Prod,Reserve FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Premium=0 AND Type IN (1,5) AND Fin_Prod<'$Date_Campagne' ORDER BY Rating DESC";
						else
							$query="SELECT DISTINCT ID,Nom,Type,Rating,Production,Stock,DATE_FORMAT(Fin_Prod,'%d-%m-%Y') as Fin_Prod,Reserve FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Premium=0 AND Type='$Unite_Type' AND Fin_Prod<'$Date_Campagne' ORDER BY Rating DESC";
						$resultu=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : em_gestion-cmodele');
						if($resultu)
						{
							while($data=mysqli_fetch_array($resultu,MYSQLI_ASSOC)) 
							{
								$Plane=$data['ID'];
								$Production=floor($data['Stock']);
								$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$Plane' AND PVP=1"),0);
								$DCAp=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$Plane'"),0);
								$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$Plane' AND Etat=1"),0);
								$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$Plane' AND Etat=1"),0);
								$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$Plane' AND Etat=1"),0);
                                $xp_avion=floor(mysqli_result(mysqli_query($con,"SELECT Exp FROM XP_Avions_IA WHERE Unite='$Unite' AND AvionID='$Plane'"),0))/10;
                                $con4=dbconnecti(4);
                                $Perdu=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$Plane' AND Avion_Nbr >0"),0);
                                mysqli_close($con4);
								$Total_Pertes=$DCAp+$Abattu+$Perdu+$Service1+$Service2+$Service3-$data['Reserve'];
								$Dispos=$Production-$Total_Pertes;
								$Type_mod=GetAvionType($data['Type']);
								if($Dispos >0)
									$modele.="<tr><td><input type='radio' name='avionc' value='".$data['ID']."'></td><td>".GetAvionIcon($data['ID'],$Unite_Pays,0,$Unite,$Front,$data['Nom'],true,false,true)."</td><td>".$data['Fin_Prod']."</td><td>".$data['Rating']."</td><td>".$xp_avion."</td><td>".$Dispos."</td></tr>";
								else
									$modele.="<tr><td class='text-danger'>Rupture de Stock!</td><td>".GetAvionIcon($data['ID'],$Unite_Pays,0,$Unite,$Front,$data['Nom'],true,false,true)."</td><td>".$data['Fin_Prod']."</td><td>".$data['Rating']."</td><td>".$xp_avion."</td><td class='text-danger'>0</td></tr>";
							}
							mysqli_free_result($resultu);
						}
					}
					mysqli_close($con);
				}
				if($modele)
				{
					$Sqn=GetSqn($country);
					$mes='<div class="alert alert-info">Cette unité possède une réputation de <b>'.$Unite_Reput.'</b> ce qui lui donne accès à du matériel <a href="#" class="popup"><b>'.$Level_txt.'</b><span>Le matériel dont la production est terminée est automatiquement accessible, peu importe son niveau</span></a></div>';
					$mes.="<form action='index.php?view=em_gestion2' method='post'>
					<input type='hidden' name='unitec' value='".$Unite."'>
					<input type='hidden' name='nau' value='".$Unite."'>
					<input type='hidden' name='nas' value='".$Flight."'>
					<input type='hidden' name='sqnc' value='".$Flight."'>
					<input type='hidden' name='CT' value='".$CT_Replace."'>
					<table class='table table-striped'><thead><tr><th>Choisir</th><th>Avion</th><th>Date <a href='#' class='popup'><img src='images/help.png'><span>Si cette date est dépassée, la production est terminée et l'avion peut être choisi peu importe son niveau</span></a></th><th>Niveau</th><th>Expérience <a href='#' class='popup'><img src='images/help.png'><span>Expérience de l'unité sur ce modèle</span></a></th><th>Stock disponible</th></tr></thead>".$modele."</table>
                    <div class='alert alert-warning'><img src='/images/CT".$CT_Replace.".png' title='Montant en Crédits Temps que nécessite cette action'> Remplacer un modèle d'avion pour le <b>".$Sqn." ".$Flight."</b> de cette unité</div>
		            <input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
					$mes.="<br><div class='alert alert-info'>Cette action forcera tous les pilotes de cette unité à rentrer à la base. La mission d'unité sera également annulée.</div>";
				}
				else
					$mes="<div class='alert alert-danger'>Aucun avion n'est disponible actuellement.<br>Seuls les modèles dont la production est terminée sont disponibles en remplacement des modèles existants.</div>
					<br><a href='index.php?view=em_missions' class='btn btn-default' title='Retour'>Retour</a>";
			}
			else
				$mes='Tsss';
		}
		$titre='Gestion du parc avion';
		$img="<img src='images/gestion_avions".$country.".jpg'><h2>".Afficher_Icone($Unite,$country,$Unite_Nom).$Unite_Nom."</h2>";
		if($OfficierEMID >0)
			$mes.="<br><form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$Unite."'><input type='submit' value='Retour unité' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	}
	else
		echo "<img src='images/top_secret.gif'>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
include_once './index.php';