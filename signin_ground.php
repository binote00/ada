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
	//$Trait_o=Insec($_POST['Trait_o']);
	$Front=Insec($_POST['Front']);
	$Photo=Insec($_POST['Photo']);
	if($Pays and $Pilote and $Nom and $Front and $Photo) //and $Trait_o
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
		if(!empty($Nom) and !empty($Photo) and !empty($Pays) and !empty($Pilote))
		{
			if(!preg_match("#^[[:alpha:]çéèêüöëêûôùîï'\- ]+$#", $Nom) or $Pseudo_Reserve or strlen($Nom) < 7)
				echo "Le nom de votre officier n'est pas valide ou est déjà utilisé!<br>Le nom du pilote doit comporter au moins 6 lettres, et éventuellement un espace entre le prénom et le nom.";
			else
			{
				$Placement=0;
				/*switch($Pays)
				{
					case 1:
						$Vehicule=103;
						$Veh_Nbr=100;
					break;
					case 2:
						if($Front ==3)
						{
							$Vehicule=5001;
							$Veh_Nbr=1;
						}
						else
						{
							$Vehicule=104;
							$Veh_Nbr=100;
						}
					break;
					case 4:
						$Vehicule=105;
						$Veh_Nbr=100;
					break;
					case 6:
						$Vehicule=106;
						$Veh_Nbr=100;
					break;
					case 7:
						$Vehicule=5001;
						$Veh_Nbr=1;
						$Placement=4;
					break;
					case 8:
						$Base=601;
						$Vehicule=270;
						$Veh_Nbr=100;
					break;
					case 9:
						$Vehicule=5001;
						$Veh_Nbr=1;
						$Placement=4;
					break;
					case 20:
						$Vehicule=334;
						$Veh_Nbr=100;
					break;
					default:
						$Vehicule=48;
						$Veh_Nbr=100;
					break;
				}*/
				$Vehicule=4000;
				$HP=10000;
				$Veh_Nbr=1;
				$Base=Get_Retraite($Front,$Pays,40);
				$Avancement=5000;
				/*if($Trait_o ==15)$Avancement=6000;*/
				if($Front ==10)$Front=0;
				$Nom=ucwords(trim(strtolower($Nom)));
				$Date=date('Y-m-d');
				$con=dbconnecti();
				$Nom=mysqli_real_escape_string($con,$Nom);
				$query="INSERT INTO Officier (Nom,Pays,Engagement,Avancement,Front,Credits,Credits_Date,Photo)";
				$query.="VALUES ('$Nom','$Pays','$Date','$Avancement','$Front',24,'$Date','$Photo')";
				$ok=mysqli_query($con,$query);
				if($ok)
				{
					$ins_id=mysqli_insert_id($con);
					$query_update="UPDATE Joueur SET Officier='$ins_id' WHERE ID='$Pilote'";
					$update_ok=mysqli_query($con,$query_update);
					/*for($x=1;$x<5;$x++)
					{*/
						$query2="INSERT INTO Regiment (Officier_ID,Pays,Vehicule_ID,Lieu_ID,Vehicule_Nbr,Placement,Camouflage,HP)";
						$query2.="VALUES ('$ins_id','$Pays','$Vehicule','$Base','$Veh_Nbr','$Placement',1,'$HP')";
						$ok2=mysqli_query($con,$query2);
					//}
					if(!$update_ok or !$ok2)
					{
						mysqli_close($con);
						$mes.="Erreur de création de votre Officier!";
					}
					else
					{
						$_SESSION['Officier']=$ins_id;
						$mes.="Officier créé avec succès!";
					}
					mail('binote@hotmail.com','Aube des Aigles: Nouvel Officier',$login." / Nom : ".$Nom." / Pays : ".$Pays);					
					$GHQ_Off=GetData("GHQ","Pays",$Pays,"Planificateur");
					if(!$GHQ_Off)$GHQ_Off=1;
					$Sujet="Bienvenue!";
					$Msg="Officier,\n Jeune promu, vous êtes nommé à la tête de votre bataillon. Contactez dès que possible votre officier commandant.\n
					La victoire dépend de votre implication, de celle de chacun. Nous comptons sur vous!\n\r
					Ne pas oublier de lire les aides intégrées au jeu, notamment celle consacrée à Blitzkrieg.";
					SendMsgOff($ins_id,$GHQ_Off,$Msg,$Sujet,1,2);
					SendMsgOff($GHQ_Off,$ins_id,"Un nouvel officier du nom de ".$Nom." a été récemment promu et est actuellement sans affectation","Nouvel officier",2,1);					
					echo "<p>Personnage créé avec succès!<br>Vous avez été versé dans une unité de réserve à l'arrière du front pour parfaire votre entrainement.</p>";
					echo "<p><img src='images/transfer_yes".$Pays.".jpg'></p>";
					if($Front ==3)
						echo "<hr><a title='Accéder au menu' href='index.php?view=ground_menu' class='btn btn-default'>Accéder au menu naval</a>";
					else
						echo "<hr><a title='Accéder au menu' href='index.php?view=ground_menu' class='btn btn-default'>Accéder au menu terrestre</a>";
					exit;
				}
				else
				{
					$mes.="Erreur de création de personnage (".$IP.") ".mysqli_error($con);
					mail('binote@hotmail.com', 'Aube des Aigles: Signin error ground',$mes);
					echo "<p>Erreur de création de Personnage terrestre !</p>";
					exit;
				}
			}
		}
		else
			echo "Remplissez tous les champs du formulaire!";
	}
	else
	{
		echo "<div class='alert alert-warning'>Afin de garantir une ambiance historique cohérente, les noms d'officiers doivent respecter <a href='help/aide_nom_pilote.php' target='_blank'>quelques règles de base</a>.
		<br>Remplissez tous les champs du formulaire et n'oubliez pas de choisir une photo!</div>";
	}
	$titre="Création de votre officier";
	$Off_Front=99;
	$Sec_Front=99;
	$con=dbconnecti();
	$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	$resultj=mysqli_query($con,"SELECT Pilote_id,Parrain FROM Joueur WHERE ID='$AccountID'");
	mysqli_close($con);
	if($resultj)
	{
		while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
		{
			$Pilote_id=$data['Pilote_id'];
			$Parrain=$data['Parrain'];
		}
		mysqli_free_result($resultj);
	}
	if($Pilote_id >0)
		$Off_Front=GetData("Pilote","ID",$Pilote_id,"Front");
	if($Parrain >0)
	{
		$con=dbconnecti();
		$resultj=mysqli_query($con,"SELECT Officier FROM Joueur WHERE ID='$Parrain'");
		mysqli_close($con);
		if($resultj)
		{
			while($data=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
			{
				$Parrain_Off=$data['Officier'];
			}
			mysqli_free_result($resultj);
		}
		if($Parrain_Off >0)
			$Sec_Front=GetData("Officier","ID",$Parrain_Off,"Front");
	}
	?>
	<h1><?echo $titre;?></h1>
	<form action="index.php?view=signin_ground" method="post">
	<input type="hidden" name="country" value="<?echo $Pays;?>">
	<input type='hidden' name='pilote' value="<?echo $Pilote;?>">
	<div class="row"><div class='col-md-6'><h2>Nom de l'officier <a href='help/aide_nom_pilote.php' target='_blank' title='Aide'><img src='images/help.png'></a></h2>
		<input type="text" name="name" size="30" placeholder='John Doe' class='form-control' style='width: 300px' onmouseup='valbtn.disabled=false;' required></div>
		<div class='col-md-6'><h2>Front</h2><select name='Front' class='form-control' style='width: 200px'>
			<?if($Pays ==9)
				echo "<option value='3'>Front Pacifique</option>";
			elseif($Pays ==7)
			{
				if($Off_Front !=0 and $Sec_Front !=0)// and $Parrain_Off_Front!=0 and $Parrain_Sec_Off_Front !=0)
					echo "<option value='10'>Front Ouest</option>";
				if($Off_Front !=3 and $Sec_Front !=3)// and $Parrain_Off_Front!=3 and $Parrain_Sec_Off_Front !=3)
					echo "<option value='3'>Front Pacifique</option>";
			}
			elseif($Pays ==8)
			{
				if($Off_Front !=1 and $Sec_Front !=1)// and $Parrain_Off_Front!=1 and $Parrain_Sec_Off_Front !=1)
					echo "<option value='1'>Front Est</option>";
				if($Off_Front !=4 and $Sec_Front !=4)// and $Parrain_Off_Front!=4 and $Parrain_Sec_Off_Front !=4)
					echo "<option value='4'>Front Nord</option>";
			}
			elseif($Pays ==6)
			{
				if($Off_Front !=2 and $Sec_Front !=2)// and $Parrain_Off_Front!=2 and $Parrain_Sec_Off_Front !=2)
					echo "<option value='2'>Front Méditerranéen</option>";
				if($Off_Front !=0 and $Sec_Front !=0)// and $Parrain_Off_Front!=0 and $Parrain_Sec_Off_Front !=0)
					echo "<option value='10'>Front Ouest</option>";
			}
			elseif($Pays ==4)
			{
				if($Off_Front !=2 and $Sec_Front !=2)// and $Parrain_Off_Front!=2 and $Parrain_Sec_Off_Front !=2)
					echo "<option value='2'>Front Méditerranéen</option>";
				if($Off_Front !=0 and $Sec_Front !=0)// and $Parrain_Off_Front!=0 and $Parrain_Sec_Off_Front !=0)
					echo "<option value='10'>Front Ouest</option>";
			}
			elseif($Pays ==2)
			{
				if($Off_Front !=2 and $Sec_Front !=2)// and $Parrain_Off_Front!=2 and $Parrain_Sec_Off_Front !=2)
					echo "<option value='2'>Front Méditerranéen</option>";
				if($Off_Front !=0 and $Sec_Front !=0)// and $Parrain_Off_Front!=0 and $Parrain_Sec_Off_Front !=0)
					echo "<option value='10'>Front Ouest</option>";
				/*if($Off_Front !=3 and $Sec_Front !=3)// and $Parrain_Off_Front!=3 and $Parrain_Sec_Off_Front !=3)
					echo "<option value='3'>Front Pacifique</option>";*/
			}
			elseif($Pays ==1)
			{
				/*if($Off_Front !=1 and $Sec_Front !=1)// and $Parrain_Off_Front!=1 and $Parrain_Sec_Off_Front !=1)
					echo "<option value='1'>Front Est</option>";
				if($Off_Front !=4 and $Sec_Front !=4)// and $Parrain_Off_Front!=4 and $Parrain_Sec_Off_Front !=4)
					echo "<option value='4'>Front Nord</option>";*/
				if($Off_Front !=2 and $Sec_Front !=2)
					echo "<option value='2'>Front Méditerranéen</option>";
				if($Off_Front !=0 and $Sec_Front !=0)// and $Parrain_Off_Front!=0 and $Parrain_Sec_Off_Front !=0)
					echo "<option value='10'>Front Ouest</option>";
			}?>
		</select></div></div>
		<h2>Photo</h2>
		<table class="table">
				<?
				for($i=1;$i<=8;$i++)
				{
					if($i ==5)
					{
				?>
				<tr>
				<?	}?>
				<td>
					<Input type='Radio' name='Photo' value='<? echo $i;?>'><img src="images/persos/general<?echo $Pays; echo $i;?>.jpg" align="middle"><br>
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
					<Input type='Radio' name='Photo' value='<? echo $i;?>'><img src="images/persos/general<?echo $Pays; echo $i;?>.jpg" align="middle"><br>
				</td>
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
	<input type='Submit' value='VALIDER' id='valbtn' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
<?}?>