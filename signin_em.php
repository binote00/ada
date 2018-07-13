<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$AccountID=Insec($_POST['pilote']);
$Pays=Insec($_POST['country']);
$Nom=Insec($_POST['name']);
$Photo=Insec($_POST['Photo']);
if($AccountID >0 and $Pays >0 and !$_SESSION['creation_off_em'])
{
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	if($Pays and $AccountID and $Nom and $Photo)
	{
		$Pseudo_Reserve=false;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT COUNT(*) FROM Pilote,Pilote_IA,Officier,Officier_em WHERE Nom='$Nom'");
		mysqli_close($con);
		if($result) 
		{
			$resultat=mysqli_fetch_row($result);
			if($resultat[0])
				$Pseudo_Reserve=true;
			mysqli_free_result($result);
		}		
		if(!empty($Nom) and !empty($Photo) and !empty($Pays) and !empty($AccountID))
		{
			if(!preg_match("#^[[:alpha:]çéèêüöëêûôùîï'\- ]+$#", $Nom) or $Pseudo_Reserve or strlen($Nom) < 7)
				echo "Le nom de votre personnage n'est pas valide ou est déjà utilisé!<br>Le nom du personnage doit comporter au moins 6 lettres, et éventuellement un espace entre le prénom et le nom.";
			else
			{
				$Avancement=25000;
				$Nom=ucwords(trim(strtolower($Nom)));
				$Date=date('Y-m-d');
				$con=dbconnecti();
				$Nom=mysqli_real_escape_string($con,$Nom);
				$query="INSERT INTO Officier_em (Nom,Pays,Engagement,Avancement,Front,Credits,Credits_Date,Photo)";
				$query.="VALUES ('$Nom','$Pays','$Date','$Avancement',12,24,'$Date','$Photo')";
				$ok=mysqli_query($con,$query);
				if($ok)
				{
					$ins_id=mysqli_insert_id($con);
					$query_update="UPDATE Joueur SET Officier_em='$ins_id' WHERE ID='$AccountID'";
					$update_ok=mysqli_query($con,$query_update);
					if(!$update_ok or !$ok)
					{
						mysqli_close($con);
						$mes.="Erreur de création de votre Officier d'état-major!";
					}
					else
					{
						$_SESSION['Officier_em']=$ins_id;
						$mes.='Officier créé avec succès!';
						mail('binote@hotmail.com','Aube des Aigles: Nouvel Officier EM',$AccountID." / Nom : ".$Nom." / Pays : ".$Pays);
						$GHQ_Off=GetData("GHQ","Pays",$Pays,"Planificateur");
					}
					if(!$GHQ_Off)$GHQ_Off=1;
					$Sujet='Bienvenue!';
					$Msg="Officier,\n Jeune promu, vous êtes amené à commander des troupes au combat ou à intégrer un poste à l\'état-major.\n
					Pour postuler, demander à rejoindre un front via le profil de votre officier et postulez ensuite via l\'organigramme de l\'état-major.\n
					Contactez dès que possible votre officier commandant via la messagerie privée du jeu afin d'obtenir son aide.\n\r
					Il est vivement conseillé de s\'inscrire sur le forum et demander l\'accès aux parties privées afin de participer à la planification avec les membres de votre faction.";
					SendMsgOff($ins_id,$GHQ_Off,$Msg,$Sujet,1,1);
					SendMsgOff($GHQ_Off,$ins_id,"Un nouvel officier du nom de ".$Nom." a été récemment promu et est actuellement sans affectation","Nouvel officier EM",1,1);
					$_SESSION['creation_off_em']=true;
					include_once('./index.php');
					echo "<div class='alert alert-warning'>Personnage créé avec succès!</div>";
					echo "<p><img src='images/transfer_yes".$Pays.".jpg'></p>";
					echo "<hr><a title='Accéder au menu' href='index.php?view=news' class='btn btn-default'>Accéder au menu</a>";
					exit;
				}
				else
				{
					$mes.="Erreur de création de personnage EM (".$IP.") ".mysqli_error($con);
					mail('binote@hotmail.com','Aube des Aigles: Signin error EM',$mes);
					echo '<p>Erreur de création du personnage Officier !</p>';
					exit;
				}
			}
		}
		else
			echo 'Remplissez tous les champs du formulaire!';
	}
	else
	{
		echo "<div class='alert alert-warning'>Afin de garantir une ambiance historique cohérente, les noms d'officiers doivent respecter <a href='aide_nom_pilote.php' target='_blank'>quelques règles de base</a>.
		<br>Remplissez tous les champs du formulaire et n'oubliez pas de choisir une photo!</div>";
	}
	echo "<h1>Création de votre officier d'état-major</h1>
	<form action='index.php?view=signin_em' method='post'>
	<input type='hidden' name='country' value='".$Pays."'>
	<input type='hidden' name='pilote' value='".$AccountID."'>
	<h2>Nom de l'officier <a href='aide_nom_pilote.php' target='_blank' title='Aide'><img src='images/help.png'></a></h2>
	<input type='text' name='name' size='30' placeholder='John Doe' class='form-control' style='width: 300px' onmouseup='valbtn.disabled=false;' required>
	<h2>Photo</h2><table class='table'>";
	for($i=1;$i<=8;$i++)
	{
		if($i ==5)
			echo "<tr>";
		echo "<td><Input type='Radio' name='Photo' value='".$i."'><img src='images/persos/general".$Pays.$i.".jpg' align='middle'><br></td>";
		if($i ==8)
			echo "</tr>";
	}
	if($Pays !=6 and $Pays !=9 and $Pays !=20)
	{
		for($i=9;$i<=12;$i++)
		{
			if($i ==13)
				echo "<tr>";
			echo "<td><Input type='Radio' name='Photo' value='".$i."'><img src='images/persos/general".$Pays.$i.".jpg' align='middle'><br></td>";
			if($i ==16)
				echo "</tr>";
		}
	}
	echo "</table><input type='submit' value='VALIDER' id='valbtn' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
}