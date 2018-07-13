<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$AccountID=$_SESSION['AccountID'];
$Pilote=Insec($_POST['pilote']);
$Pays=Insec($_POST['country']);
if($AccountID >0 and $Pilote >0 and $Pays >0)
{
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_msg.inc.php');
	$Nom=Insec($_POST['name']);
	if($Pays and $Pilote and $Nom)
	{
		$Pseudo_Reserve=false;
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT COUNT(*) FROM Pilote,Pilote_IA,Officier,Officier_em,Officier_PVP WHERE Nom='$Nom'");
		mysqli_close($con);
		if($result) 
		{
			$resultat=mysqli_fetch_row($result);
			if($resultat[0])
				$Pseudo_Reserve=true;
			mysqli_free_result($result);
		}		
		if(!empty($Nom) and !empty($Pays) and !empty($Pilote))
		{
			if(!preg_match("#^[[:alpha:]çéèêüöëêûôùîï'\- ]+$#", $Nom) or $Pseudo_Reserve or strlen($Nom) < 7)
				echo "Le nom de votre officier n'est pas valide ou est déjà utilisé!<br>Le nom du pilote doit comporter au moins 6 lettres, et éventuellement un espace entre le prénom et le nom.";
			else
			{
				$Nom=ucwords(trim(strtolower($Nom)));
				$Date=date('Y-m-d');
				$con=dbconnecti();
				$Nom=mysqli_real_escape_string($con,$Nom);
				$query="INSERT INTO Officier_PVP (Nom,Pays,Engagement,Avancement,Front,Credits,Credits_Date,Photo)";
				$query.="VALUES ('$Nom','$Pays','$Date',5000,0,24,'$Date',1)";
				$ok=mysqli_query($con,$query);
				if($ok)
				{
					$ins_id=mysqli_insert_id($con);
					$query_update="UPDATE Joueur SET Officier_pvp='$ins_id' WHERE ID='$Pilote'";
					$update_ok=mysqli_query($con,$query_update);
					if(!$update_ok)
					{
						mysqli_close($con);
						$mes.="Erreur de création de votre Officier!";
					}
					else
					{
						$_SESSION['Officier_pvp']=$ins_id;
						$mes.="Officier créé avec succès!";
					}
					mail('binote@hotmail.com','Aube des Aigles: Nouvel Officier PVP',$login." / Nom : ".$Nom." / Pays : ".$Pays);					
					echo "<p>Personnage créé avec succès!</p>";
					echo "<p><img src='images/transfer_yes".$Pays.".jpg'></p>";
					echo "<hr><a title='Accéder au menu' href='index.php?view=ground_menu_pvp' class='btn btn-default'>Accéder au menu action</a>";
					exit;
				}
				else
				{
					$mes.="Erreur de création de personnage (".$IP.") ".mysqli_error($con);
					mail('binote@hotmail.com', 'Aube des Aigles: Signin error ground pvp',$mes);
					echo "<p>Erreur de création de Personnage terrestre !</p>";
					exit;
				}
			}
		}
		else
			echo "Remplissez tous les champs du formulaire!";
	}
	$titre="Création de votre officier";
	?>
	<h1><?echo $titre;?></h1>
	<form action="index.php?view=signin_ground_pvp" method="post">
	<input type="hidden" name="country" value="<?echo $Pays;?>">
	<input type='hidden' name='pilote' value="<?echo $Pilote;?>">
	<table class='table'><thead><tr><th>Nom de l'officier <a href='aide_nom_pilote.php' target='_blank' title='Aide'><img src='images/help.png'></a></th></tr></thead>
	<tr><td align="left"><input type="text" title="Le nom de l'officier ne peut comporter que des lettres et éventuellement un espace entre le prénom et le nom" name="name" size="30" class='form-control' onmouseup='valbtn.disabled=false;' required></td></tr></table>
	<input type='Submit' value='VALIDER' id='valbtn' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?}?>