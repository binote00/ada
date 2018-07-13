<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if($PlayerID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$Nom=Insec($_POST['name']);
	$Spec=Insec($_POST['Specialisation']);
	$Trait_e=Insec($_POST['Trait_e']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Avancement,Reputation,Equipage FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Avancement=$data['Avancement'];
			$Reputation=$data['Reputation'];
			$Equipage=$data['Equipage'];
		}
		mysqli_free_result($result);
	}
	if(!$Equipage and ($Reputation >499 or $Avancement >499))
	{		
		if(!empty($Nom))
		{
			if(!preg_match("#^[[:alpha:]çéèêüöëêûôùîï'\- ]+$#", $Nom))
				echo "Le nom n'est pas valide ou est déjà utilisé!<br>Le nom ne peut comporter que des lettres et éventuellement un espace entre le prénom et le nom.";
			else
			{
				$Bomb=0; $Vue=0; $Endurance=0; $Meca=0; $Navigation=0; $Radar=0; $Aid=0; $Radio=0; $Tir=0;
				switch($Spec)
				{
					case 1:
						$Bomb=mt_rand(10,50);
					break;
					case 2:
						$Vue=mt_rand(10,50);
					break;
					case 3:
						$Endurance=1;
					break;
					case 4:
						$Meca=10;
					break;
					case 5:
						$Navigation=mt_rand(10,50);
					break;
					case 6:
						$Aid=10;
					break;
					case 7:
						$Radio=10;
					break;
					case 8:
						$Tir=mt_rand(10,50);
					break;
					case 9:
						$Radar=10;
					break;
				}
				$Aid +=10;
				$Bomb +=10;
				$Vue +=10;
				$Meca +=10;
				$Navigation +=10;
				$Radio +=10;
				$Radar +=10;
				$Tir +=10;
				$Endurance +=10;				
				$Nom=ucfirst(trim($Nom));
				$Date=date('Y-m-d');				
				$con=dbconnecti();
				$Nom=mysqli_real_escape_string($con,$Nom);
				$query="INSERT INTO Equipage (Nom,Pays,Engagement,Trait,Radio,Radar,Navigation,Tir,Bombardement,Vue,Mecanique,Premiers_Soins,Endurance,Courage,Moral,
				Reputation,Avancement,Missions,Victoires,Abattu,medal0,ID_ref)";
				$query.="VALUES ('$Nom','$country','$Date','$Trait_e','$Radio','$Radar','$Navigation','$Tir','$Bomb','$Vue','$Meca','$Aid','$Endurance',100,100,0,0,0,0,0,0,'$PlayerID')";
				$ok=mysqli_query($con,$query);
				if($ok)
				{
					$ins_id=mysqli_insert_id($con);
					if(!$con)
						$con=dbconnecti();
					$update_ok=mysqli_query($con,"UPDATE Pilote SET Equipage='$ins_id' WHERE ID='$PlayerID'");
					if($update_ok)
					{
						$titre="Création";
						$mes.="Membre d'équipage créé avec succès!";
						$img="<img src='images/transfer_yes".$country.".jpg'>";
						//mail('binote@hotmail.com','Aube des Aigles: Nouveau Membre Equipage',"Nom : ".$Nom." / Pays : ".$country." / Joueur : ".$PlayerID);
					}
					else
					{
						echo "Erreur de création de Membre d'équipage!";
						$mes.="Erreur de création de Membre d'équipage update_ok ".mysqli_error($con);
						mail ('binote@hotmail.com','Aube des Aigles: Création Equipage Error',$mes);
					}
				}
				else
				{
					echo "Erreur de création de Membre d'équipage!";
					$mes.="Erreur de création de Membre d'équipage ok ".mysqli_error($con);
					mail ('binote@hotmail.com','Aube des Aigles: Création Equipage Error',$mes);
				}
				mysqli_close($con);
				include_once('./index.php');
				exit;
			}
		}
		else
		{
			//echo "N'oubliez pas de lui choisir un nom!";
		?>
		<h1>Création de votre membre d'équipage</h1>
		<form action="index.php?view=choix_equipage" method="post">
		<input type="hidden" name="country" value="<?echo $country;?>">
			<p><img src='images/pilotes<?echo $country;?>.jpg'></p>
			<label for="name">Nom</label><input type="text" title="Le nom ne peut comporter que des lettres et éventuellement un espace entre le prénom et le nom" name="name" placeholder="John Doe" size="30" class="form-control" style="width:300px;">
			<label for="Specialisation">Spécialisation</label> <a href='aide_equipage.php' target='_blank'><img src='images/help.png'></a></th><td align="left">
					<select name="Specialisation" class="form-control" style="width:300px;">
						<option value='0' checked>- Aucune -</option>
						<optgroup label="Spécialisations">
						<option value='1' title='Bombardement horizontal uniquement'>Bombardement</option>
						<option value='2' title='Détection visuelle uniquement'>Détection</option>
						<option value='3' title='Accorde un bonus de 1 point à la création'>Endurance</option>
						<option value='4' title='Compétence spécifique permettant de limiter légèrement les dégâts encaissés par votre avion en mission'>Mécanique</option>
						<option value='5'>Navigation</option>
						<option value='6' title="Compétence spécifique permettant d'annuler les effets d'une blessure légère en mission">Premiers Secours</option>
						<option value='7' title='Compétence spécifique offrant un bonus lors des vols avec escorte'>Radio</option>
						<option value='8' title='Canons et mitrailleuses uniquement'>Tir</option>
						<option value='9' title="Compétence spécifique permettant d'utiliser les radars embarqués">Utilisation des radars</option>
						</optgroup>
					</select>
			<label for="Trait_e" title="Les effets du Trait ne s'appliquent qu'au membre d'équipage">Trait</label> <a href='aide_equipage.php' target='_blank'><img src='images/help.png'></a>
					<select name="Trait_e" class="form-control" style="width:300px;">
						<option value='0' checked>- Aucun -</option>
						<optgroup label="Traits">
						<option value='1' title="Léger bonus dans toutes ses actions">Chanceux</option>
						<option value='2' title="Courage minimum de 100 en toutes circonstances">Nerfs d'acier</option>
						<option value='3' title="Diminue de moitié ses chances de mourir ou d'être blessé">Dur à cuire</option>
						<option value='4' title="Double les effets de son entrainement">Esprit Vif</option>
						<option value='5' title="Autorise des options supplémentaires pour votre avion personnel">Ingénieux</option>
						<option value='6' title="Obéira toujours aux ordres, même si effrayé ou démoralisé">Loyal</option>
						<option value='7' title="Sa capacité de détection n'est pas affectée par la distance de la cible">Oeil de Lynx</option>
						<option value='8' title="Moral minimum de 100 en toutes circonstances">Optimiste</option>
						</optgroup>
					</select>
		<p><input type="Submit" class="btn btn-default" value="VALIDER" class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></p>
		<div class='alert alert-info'>Le membre d'équipage vous accompagnera dans vos missions à bord de tout avion multiplaces.<br>Il vous assistera dans vos tâches et vous pourrez lui donner certains ordres.</div>
	<?
		}
	}
	else
		echo "Votre réputation n'est pas suffisante pour commander à un équipage!";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>