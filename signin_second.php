<?
if(1==1)
{
	//désactivé
}
else
{
	require_once('./jfv_inc_sessions.php');
	//session_unset();
	session_destroy();
	unset($_SESSION['country']);
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$Pays = Insec($_POST['country']);
	$login = Insec($_POST['pseudo']);
	$email = Insec($_POST['email']);
	$Pwd = Insec($_POST['password']);
	$Nom = Insec($_POST['name']);
	$Unite = Insec($_POST['unite']);
	$Photo = Insec($_POST['Photo']);
	$Account = Insec($_POST['pilote']);
	$IP = $_SERVER['REMOTE_ADDR'];
	if($Account and $email)
	{
		if($Pays)
		{
			$Unit=GetTraining($Pays);
			$Pseudo_Reserve=false;
			$Non=false;
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Nom='$Nom'");
			$result2=mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Nom='$Nom'");
			mysqli_close($con);
			if($result) 
			{
				$resultat=mysqli_fetch_row($result);
				if($resultat[0])
					$Pseudo_Reserve=true;
			}
			if($result2) 
			{
				$resultat=mysqli_fetch_row($result2);
				if($resultat[0])
				{
					$Non=true;
					mail('binote@hotmail.com','Aube des Aigles: Creation Doublon Login/Nom '.$login,$Nom.' '.$IP);
				}
			}		
			if(!empty($login) and !empty($Pwd) and !empty($Nom) and !empty($Photo) and !empty($Pays))
			{
				if($Non ==true)
					echo "Les doublons ne sont pas autorisés!<br>L'identifiant et le nom du pilote doivent être différents";
				elseif(!preg_match("#^[[:alpha:]çéèêüöëêûôùîï'\- ]+$#", $Nom) or $Pseudo_Reserve or strlen($Nom) <7)
					echo "Le nom de votre pilote n'est pas valide ou est déjà utilisé!<br>Le nom du pilote doit comporter au moins 6 lettres, et éventuellement un espace entre le prénom et le nom.";
				else
				{
					$Nom=ucwords(trim(strtolower($Nom)));
					$Date=date('Y-m-d');
					$query="INSERT INTO Pilote (Nom,Pays,Engagement,Unit,Photo,Credits_date,IP,Ecole)";
					$query.="VALUES ('$Nom','$Pays','$Date','$Unit','$Photo','$Date','$IP','1')";
					$con=dbconnecti();
					$ok=mysqli_query($con,$query);
					if($ok)
					{
						$ins_id=mysqli_insert_id($con);
						$update_ok=mysqli_query($con,"UPDATE Joueur SET 2nd_Pilot='$ins_id' WHERE ID='$Account'");
						if(!$update_ok)
							$mes.="Erreur de création du second pilote!";
						else
							$mes.="Second pilote créé avec succès!";
						$img="<img src='images/transfer_yes".$Pays.".jpg'>";
						mail('binote@hotmail.com','Aube des Aigles: Nouveau Joueur',$login." / Nom : ".$Nom." / Pays : ".$Pays." / Unite : ".$Unite);
						include_once('./index.php');
						echo "<p>Personnage créé avec succès!<br>Vous avez été versé dans une escadrille de réserve pour parfaire votre entrainement.</p>";
						echo "<p><img src='images/transfer_yes".$Pays.".jpg'></p>";
						$PlayerID=GetData("Pilote","Nom",$Nom,"ID");
						if($Pays ==1)
						{
							$Sujet="Willkommen!";
							$Msg="Fahnenjunker,\n
							Vous venez de vous engager dans la Luftwaffe. Vous avez été versé dans une unité de réserve afin que vous décrochiez votre brevet de pilote. Une fois cette formalité accomplie, vous pourrez rejoindre une unité de combat.\n\r
							Afin de remporter la victoire, nous devons être organisés! Rejoignez le groupe « Luftwaffe » sur le forum afin de communiquer avec les autres pilotes et officiers de l\'Axe.\n
							La victoire dépend de l\'implication de chacun. Nous comptons sur vous!\n\r
							A bientôt sur le forum et dans le ciel.";
						}
						elseif($Pays ==2)
						{
							$Sujet="Welcome!";
							$Msg="Dear Pupil Officer,\n
							Vous venez de vous engager dans la Royal Air Force (RAF). Vous avez été intégré à l\'Operational Training Unit, l\'escadrille école, le temps de vous améliorer et pour que vous puissiez vous entrainez jusqu\'à obtenir votre brevet de pilote. Une fois obtenu,vous pourrez vous engagez dans le Groupe de Combat de votre choix.\n\r
							Vous aurez alors le choix entre plusieurs Groupes:\n
							Fighter Command: Groupe de Chasse et de Reconnaissance\n
							Bomber Command: Groupe de Bombardement et de Chasseurs Lourds\n
							Coastal Command : Groupe de Patrouille Maritime\n
							Vous êtes la relève, le prochain As! Pour gagner, il faut que la RAF s\'organise comme elle l\'a toujours fait! Nous vous demandons donc de vous inscrire sur le forum et de demander votre admission au groupe « Royal Air Force ».\n
							Quand vous arriverez dans la partie réservée aux pilotes anglais, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers le Squadron qui vous correspond.\n
							Faites votre devoir, Nous comptons sur vous, la Grande-Bretagne compte sur vous, la victoire est nôtre!\n\r
							A très bientôt sur le forum et dans les air.
							Pour le Roi.";
						}
						elseif($Pays ==4)
						{
							/*$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
							if($Date_Campagne >"1940-06-21")
							{
								$Sujet="Bienvenue!";
								$Msg="Elève-Pilote,\n
								Vous venez de vous engager dans l\'Armée de l\'Air de Vichy. Vous avez été intégré dans une escadrille de réserve le temps de vous améliorer et pour que vous puissiez vous entrainez jusqu\'à obtenir votre brevet de pilote. Une fois obtenu,vous pourrez vous engagez dans un Groupe de Combat de votre choix.\n\r
								Vous aurez alors le choix entre plusieurs Groupes:\n
								les GC: Groupe de Chasse\n
								les GB: Groupe de Bombardement\n
								les ECN: Escadrille de Chasse de Nuit\n
								les GR: Groupe de Reconnaissance\n\r
								Vous êtes la relève, les prochain As! Il faut que la France s\'organise comme elle l\'a toujours fait! Nous vous demandons donc de vous inscrire sur le forum et de demander votre admission au groupe « Armée de l\'Air ».\n
								Vous pouvez aussi vous inscrire via le service postal du jeu et envoyer un courrier à Léon Piedplu dans la liste des destinataires, qui vous donnera les accès pour nous rejoindre sur le forum.\n
								Quand vous arriverez dans la partie réservée aux pilotes français, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers une escadrille qui vous correspond.\n
								Faites votre devoir, Nous comptons sur vous, la France compte sur vous, la victoire est nôtre!\n\r
								A très bientôt sur le forum et dans les air.";
							}
							else
							{*/
								$Sujet="Bienvenue!";
								$Msg="Elève-Pilote,\n
								Vous venez de vous engager dans l\'Armée de l\'Air Française. Vous avez été intégré dans une escadrille de réserve le temps de vous améliorer et pour que vous puissiez vous entrainez jusqu\'à obtenir votre brevet de pilote. Une fois obtenu,vous pourrez vous engagez dans un Groupe de Combat de votre choix.\n\r
								Vous aurez alors le choix entre plusieurs Groupes:\n
								les GC: Groupe de Chasse\n
								les GB: Groupe de Bombardement\n
								les ECN: Escadrille de Chasse de Nuit\n
								les GR: Groupe de Reconnaissance\n\r
								Vous êtes la relève, les prochain As! Pour gagner, il faut que la France s\'organise comme elle l\'a toujours fait! Nous vous demandons donc de vous inscrire sur le forum et de demander votre admission au groupe « Armée de l\'Air ».\n
								Vous pouvez aussi vous inscrire via le service postal du jeu et envoyer un courrier à Léon Piedplu dans la liste des destinataires, qui vous donnera les accès pour nous rejoindre sur le forum.\n
								Quand vous arriverez dans la partie réservée aux pilotes français, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers une escadrille qui vous correspond.\n
								Faites votre devoir, Nous comptons sur vous, la France compte sur vous, la victoire est nôtre!\n\r
								A très bientôt sur le forum et dans les air.";
							//}
						}
						elseif($Pays ==8)
						{
							$Sujet="Bienvenue!";
							$Msg="Camarade,\n
							Vous venez de vous engager dans l\'Aviation Militaire Soviétique (VVS). Vous avez été intégré à l\'escadrille école, le temps de vous entrainer afin d\'obtenir votre brevet de pilote. Une fois obtenu,vous pourez vous engager dans l\'unité combattante de votre choix.\n\r
							Vous êtes la relève, le prochain As! Nous vous demandons de vous inscrire sur le forum et de demander votre admission au groupe « VVS ».\n
							Quand vous arriverez dans la partie réservée aux pilotes alliés, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers l\'unité qui vous correspond.\n
							Faites votre devoir, Nous comptons sur vous, la Mère Patrie et les Alliés comptent sur vous, la victoire sera nôtre!\n\r
							A très bientôt sur le forum et dans les airs.";
						}
						elseif($Pays ==20)
						{
							$Sujet="Bienvenue!";
							$Msg="Elève-Pilote,\n
							Vous venez de vous engager dans l\'Armée de l\'Air Finlandaise. Vous avez été intégré dans une escadrille de réserve le temps de vous améliorer et pour que vous puissiez vous entrainez jusqu\'à obtenir votre brevet de pilote. Une fois obtenu,vous pourrez vous engagez dans une escadrille de votre choix.\n\r
							Vous êtes la relève, les prochain As! Pour gagner, il faut que notre nation s\'organise. Nous vous demandons donc de vous inscrire sur le forum et de demander votre admission au groupe «Suomen Ilmavoimat».\n
							Quand vous arriverez dans la partie réservée aux pilotes finlandais, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers une escadrille qui vous correspond.\n
							Faites votre devoir, Nous comptons sur vous, la victoire est nôtre!\n\r
							A très bientôt sur le forum et dans les air.";
						}
						SendMsgOff($PlayerID,0,$Msg,$Sujet,0,3);
						$Msg=str_replace("\'","'",$Msg);
						$headers='From: admin@aubedesaigles.net';
						mail($email,'Aube des Aigles: Création Second Pilote',$Msg." \r\n Pour jouer votre second pilote, il vous suffit de vous connecter au jeu et ensuite de cliquer sur le bouton 'Changer de pilote' via le menu 'Compte'",$headers);
						exit;
					}
					else
					{
						$mes.="Erreur de création de personnage (".$IP.") ".mysqli_error($con);
						mail('binote@hotmail.com','Aube des Aigles: Signin error second',$mes);
						echo "<p>Erreur de création de Personnage !</p>";
						exit;
					}
					mysqli_close($con);
				}
			}
		}
		if(!GetData("Joueur","ID",$Account,"2nd_Pilot"))
		{
			/*echo "Afin de garantir une ambiance historique cohérente, les noms des pilotes doivent respecter <a href='aide_nom_pilote.php' target='_blank'>quelques règles de base</a>.
			<br>Remplissez tous les champs du formulaire et n'oubliez pas de choisir une photo!";*/		
			echo "<h1>Création de votre second pilote</h1>
			<form action='index.php?view=signin_second' method='post'>
			<input type='hidden' name='country' value='".$Pays."'>
			<input type='hidden' name='pilote' value='".$Account."'>
			<table class='table'><thead><tr><th>Nom du Pilote <a href='aide_nom_pilote.php' target='_blank' title='Aide'><img src='images/help.png'></a></th></tr></thead>
			<tr><td align='left'><input type='text' title='Le nom du pilote ne peut comporter que des lettres et éventuellement un espace entre le prénom et le nom' name='name' class='form-control' size='30'></td>
			</tr></table>
			<div style='overflow:auto; width: 100%;'><table class='table'><thead><tr><th colspan='6'>Photo</th></tr></thead>";
					for($i=1;$i<=8;$i++)
					{
						if($i ==5)
						{
					?>
					<tr>
					<?	}?>
					<td>
						<Input type='Radio' name='Photo' value='<? echo $i;?>'><img src="images/persos/pilote<?echo $Pays; echo $i;?>.jpg" align="middle"><br>
					</td>
					<?	if($i ==8)
						{
					?>
					</tr>
					<?
						}	
					}
					?>
			</tr>
					<?
			if($Pays !=6 and $Pays !=9 and $Pays !=20)
			{
					for($i=9;$i<=12;$i++)
					{
						if($i ==13)
						{
					?>
					<tr>
					<?	}?>
					<td>
						<Input type='Radio' name='Photo' value='<? echo $i;?>'><img src="images/persos/pilote<?echo $Pays; echo $i;?>.jpg" align="middle"><br>
					</td>
					<?	if($i ==16)
						{
					?>
					</tr>
					<?
						}	
					}
					?>
			</tr><?
			}
			echo "</table></div><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		else
			echo "Vous avez déjà un second pilote!";
	}
}