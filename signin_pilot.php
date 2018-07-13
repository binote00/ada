<?
require_once('./jfv_inc_sessions.php');
/*session_destroy();
unset($_SESSION['country']);*/
include_once('./jfv_include.inc.php');
$AccountID=$_SESSION['AccountID'];
$Pays=Insec($_POST['country']);
$Nom=Insec($_POST['name_pil']);
//$Unite=Insec($_POST['unite']);
$Photo=Insec($_POST['Photo']);
if($Pays >0 and $AccountID >0)
{
	$IP=$_SERVER['REMOTE_ADDR'];
	$Pseudo_Reserve=false;
	$Non=false;
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Nom='$Nom'");
	//mysqli_close($con);
	if($result) 
	{
		$resultat=mysqli_fetch_row($result);
		if($resultat[0])
			$Pseudo_Reserve=true;
	}
	//$con=dbconnecti();
	$resultn=mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Nom='$Nom'");
	mysqli_close($con);
	if($resultn) 
	{
		$resultat=mysqli_fetch_row($resultn);
		if($resultat[0])
			$Non=true;
	}		
	if(!empty($Nom) and !empty($Photo) and !empty($Pays))
	{
		if($Non ==true)
			echo "Vous ne pouvez créer qu'un pilote par joueur!";
		elseif(!preg_match("#^[[:alpha:]äçéèêüöëêûôùîï'\- ]+$#", $Nom) or $Pseudo_Reserve or strlen($Nom) < 7)
			echo "Le nom de votre pilote n'est pas valide ou est déjà utilisé!<br>Le nom du pilote doit comporter au moins 6 lettres, et éventuellement un espace entre le prénom et le nom.";
		else
		{
			if($Pays ==9 or $Pays ==7)
				$Front=3;
			elseif($Pays ==4 or $Pays ==6 or $Pays ==10 or $Pays ==17 or $Pays ==24)
				$Front=2;
			elseif($Pays ==8 or $Pays ==15 or $Pays ==18 or $Pays ==19)
				$Front=1;
			elseif($Pays ==20)
				$Front=4;
			else
				$Front=0;
			$Unit=GetTraining($Pays);
			$Nom=ucwords(trim(strtolower($Nom)));
			$Date=date('Y-m-d');
			$con=dbconnecti();
			$Nom=mysqli_real_escape_string($con,$Nom);
			$query="INSERT INTO Pilote (Nom,Pays,Front,Engagement,Unit,Photo,Credits_date,IP,Ecole)";
			$query.="VALUES ('$Nom','$Pays','$Front','$Date','$Unit','$Photo','$Date','$IP','1')";
			$ok=mysqli_query($con,$query);
			if($ok)
			{
				$ins_id=mysqli_insert_id($con);
				mysqli_close($con);
				SetData("Joueur","Pilote_id",$ins_id,"ID",$AccountID);
				$mes.="Personnage créé avec succès!";
				$img="<img src='images/transfer_yes".$Pays.".jpg'>";
				mail('binote@hotmail.com','Aube des Aigles: Nouveau Pilote',$login.' / Nom : '.$Nom.' / Pays : '.$Pays.' / Pilote_id : '.$ins_id.' / Front : '.$Front);
				include_once('./index.php');
				echo "<div class='alert alert-info'>Personnage créé avec succès!<br>Vous avez été versé dans une escadrille de réserve pour parfaire votre entrainement.</div>";
				echo "<p><img src='images/transfer_yes".$Pays.".jpg'></p>";
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
				elseif($Pays ==3)
				{
					$Sujet="Bienvenue!";
					$Msg="Elève Pilote,\n
					Vous venez de vous engager dans l'Aéronautique Militaire Belge. Vous avez été intégré à l\'escadrille école, le temps de vous entrainer afin d\'obtenir votre brevet de pilote. Une fois obtenu,vous pourez vous engager dans l'\unité combattante de votre choix.\n\r
					Vous êtes la relève, le prochain As! Nous vous demandons de vous inscrire sur le forum et de demander votre admission au groupe « Aéronautique Militaire ».\n
					Quand vous arriverez dans la partie réservée aux pilotes alliés, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers l'\unité qui vous correspond.\n
					Faites votre devoir, Nous comptons sur vous, la Belgique et les Alliés comptent sur vous, la victoire sera nôtre!\n\r
					A très bientôt sur le forum et dans les airs.\n\r
					L\'union fait la force ! Vive le Roi ! Vive la Belgique !";
				}
				elseif($Pays ==4)
				{
					/*$Date_Campagne=GetData("Conf_Update","ID",2,"Date");
					if($Date_Campagne > "1940-06-21")
					{
						$Sujet="Bienvenue!";
						$Msg="Elève-Pilote,\n
						Vous venez de vous engager dans l\'Armée de l\'Air de Vichy. Vous avez été intégré dans une escadrille de réserve le temps de vous améliorer et pour que vous puissiez vous entrainez jusqu\'à obtenir votre brevet de pilote. Une fois obtenu,vous pourez vous engager dans un Groupe de Combat de votre choix.\n\r
						Vous aurez alors le choix entre plusieurs Groupes:\n
						les GC: Groupe de Chasse\n
						les GB: Groupe de Bombardement\n
						les ECN: Escadrille de Chasse de Nuit\n
						les GR: Groupe de Reconnaissance\n\r
						Vous êtes la relève, les prochain As! Il faut que la France s\'organise comme elle l\'a toujours fait! Nous vous demandons donc de vous inscrire sur le forum et de demander votre admission au groupe « Armée de l\'Air ».\n
						Vous pouvez aussi vous inscrire via le service postal du jeu et envoyer un courrier à François Morel dans la liste des destinataires, qui vous donnera les accès pour nous rejoindre sur le forum.\n
						Quand vous arriverez dans la partie réservée aux pilotes français, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers une escadrille qui vous correspond.\n
						Faites votre devoir, Nous comptons sur vous, la France compte sur vous, la victoire est nôtre!\n\r
						A très bientôt sur le forum et dans les airs.";
					}
					else
					{*/
						$Sujet="Bienvenue!";
						$Msg="Elève-Pilote,\n
						Vous venez de vous engager dans l\'Armée de l\'Air Française. Vous avez été intégré dans une escadrille de réserve le temps de vous améliorer et pour que vous puissiez vous entrainer jusqu\'à obtenir votre brevet de pilote. Une fois obtenu, vous pourrez vous engager dans un Groupe de Combat de votre choix. \r\n
						Vous aurez alors le choix entre plusieurs Groupes : \r\n
						- les GC : Groupe de Chasse (pour défendre ou escorter les autres escadrilles)\r\n
						- les GB : Groupe de Bombardement (pour détruire les installations et les troupes ennemies)\r\n
						- les ECN : Escadrille de Chasse de Nuit (pour protéger nos infrastructures contre les attaques nocturnes)\r\n
						- les GR : Groupe de Reconnaissance (aucune attaque n\'est possible sans reconnaissance préalable)\r\n
						- les GIA : Groupe de Transport (pour assurer le ravitaillement des unités et mener des missions spéciales)\r\n
						Vous êtes la relève, les prochains As ! Pour gagner, il faut que la France s\'organise comme elle l\'a toujours fait ! Nous vous demandons donc de vous inscrire sur le forum et de demander votre admission au groupe « Armée de l\'Air » \r\n
						Vous pouvez aussi vous inscrire via le service postal du jeu et envoyer un courrier à Crépin Desmarais dans la liste des destinataires, qui vous donnera les accès pour nous rejoindre sur le forum après, bien sûr, vous être inscrit sur le forum. \r\n
						Quand vous arriverez dans la partie réservée aux pilotes français, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers une escadrille qui vous correspond. \r\n
						Faite votre devoir, Nous comptons sur vous, la France compte sur vous, la victoire est nôtre! \r\n
						A très bientôt sur le forum et dans les airs.";
					//}
				}
				elseif($Pays ==7)
				{
					$Sujet="Bienvenue!";
					$Msg="Elève-Pilote,\n
					Vous venez de vous engager dans l\'United States Army Air Force (USAAF). Vous avez été intégré à l\'escadrille école, le temps de vous entrainer afin d\'obtenir votre brevet de pilote. Une fois obtenu,vous pourez vous engager dans l\'unité combattante de votre choix.\n\r
					Vous êtes la relève, le prochain As! Nous vous demandons de vous inscrire sur le forum et de demander votre admission au groupe « USAAF ».\n
					Quand vous arriverez dans la partie réservée aux pilotes alliés, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers l\'unité qui vous correspond.\n
					Faites votre devoir, Nous comptons sur vous, la Patrie et les Alliés comptent sur vous, la victoire sera nôtre!\n\r
					A très bientôt sur le forum et dans les airs.";
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
				elseif($Pays ==9)
				{
					$Sujet="Bienvenue!";
					$Msg="Elève-Pilote,\n
					Vous venez de vous engager dans l\'Armée de l\'Air Impériale. Vous avez été intégré à l\'escadrille école, le temps de vous entrainer afin d\'obtenir votre brevet de pilote. Une fois obtenu,vous pourez vous engager dans l\'unité combattante de votre choix.\n\r
					Vous êtes la relève, le prochain As! Nous vous demandons de vous inscrire sur le forum et de demander votre admission au groupe « DTRK ».\n
					Quand vous arriverez dans la partie réservée aux pilotes alliés, vous pourrez alors vous présenter et nous faire part de vos envies afin que nous puissions vous guider vers l\'unité qui vous correspond.\n
					Faites votre devoir, Nous comptons sur vous, l\'Empereur compte sur vous, la victoire sera nôtre!\n\r
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
				SendMsgOff($ins_id,0,$Msg,$Sujet,0,3);
				$Msg=str_replace("\'","'",$Msg);
				$headers='From: admin@aubedesaigles.net';
				mail($email,'Aube des Aigles: Inscription',$Msg." \r\n Votre identifiant est : ".$login,$headers);
				//exit;
			}
			else
			{
				$mes.="Erreur de création de personnage (".$IP.") ".mysqli_error($con);
				mysqli_close($con);
				mail('binote@hotmail.com','Aube des Aigles: Signin error pilot',$mes);
				include_once('./index.php');
				echo "<p>Erreur de création de Personnage !</p>";
				//exit;
			}
		}
	}
	else
	{
		$avert="<div class='alert alert-warning'>Afin de garantir une ambiance historique cohérente, les noms des pilotes doivent respecter <a href='aide_nom_pilote.php' target='_blank' class='lien'>quelques règles de base</a>.
		<br>Remplissez tous les champs du formulaire et n'oubliez pas de choisir une photo!</div>";
	?>
	<h1>Création de votre pilote</h1>
	<?echo $avert;?>
	<form action="index.php?view=signin_pilot" method="post">
	<input type="hidden" name="country" value="<?echo $Pays;?>">
	<fieldset>
		<h2>Nom du Pilote <a class='bold' href='aide_nom_pilote.php' target='_blank' title='Aide'><img src='images/help.png'></a></h2>
		<input type="text" title="Le nom du pilote ne peut comporter que des lettres et éventuellement un espace entre le prénom et le nom" name='name_pil' size="30" maxlength="30" placeholder='John Doe' class="form-control" style='width: 300px'>
		<table class='table'>
			<tr><th colspan="6"><h2>Photo</h2></th></tr>
					<?
					for($i=1;$i<=8;$i++)
					{
						if($i ==5)
						{
					?>
					<tr>
					<?	}?>
					<td><Input type='Radio' name='Photo' value='<? echo $i;?>'><img src="images/persos/pilote<?echo $Pays; echo $i;?>.jpg" align="middle"><br></td>
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
			if($Pays !=6 and $Pays !=20)
			{
					for($i=9;$i<=16;$i++)
					{
						if($i ==13)
						{
					?>
					<tr>
					<?	}?>
					<td><Input type='Radio' name='Photo' value='<? echo $i;?>'><img src="images/persos/pilote<?echo $Pays; echo $i;?>.jpg" align="middle"><br></td>
					<?	if($i ==16)
						{
					?>
					</tr>
					<?
						}	
					}
					?>
			</tr>
			<?}?>
		</table>
	</fieldset>
	<input type='Submit' value='VALIDER' class="btn btn-default" onclick='this.disabled=true;this.form.submit();'></form>
<?	}
}
?>
