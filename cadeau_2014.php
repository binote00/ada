<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID = $_SESSION['PlayerID'];
$OfficierID = $_SESSION['Officier'];
if($PlayerID > 0 or $OfficierID > 0)
{
	$Cadeau = Insec($_POST['ID']);
	if($Cadeau > 1000)
	{
		if($PlayerID > 0)
		{
			$Note = GetData("Joueur","ID",$PlayerID,"Note");
			switch($Cadeau)
			{
				case 1001:
					if($Note >= 50)
					{
						SetData("Joueur","Slot10",77,"ID",$PlayerID);
						UpdateData("Joueur","Note",-50,"ID",$PlayerID);
						$mes = "Votre pilote arbore son nouvel équipement!";
					}
				break;
				case 1002:
					if($Note >= 50)
					{
						SetData("Joueur","Slot7",74,"ID",$PlayerID);
						UpdateData("Joueur","Note",-50,"ID",$PlayerID);
						$mes = "Votre pilote arbore son nouvel équipement!";
					}
				break;
				case 1003:
					if($Note >= 50)
					{
						SetData("Joueur","Slot1",70,"ID",$PlayerID);
						UpdateData("Joueur","Note",-50,"ID",$PlayerID);
						$mes = "Votre pilote arbore son nouvel équipement!";
					}
				break;
				case 1004:
					if($Note >= 100)
					{
						SetData("Joueur","Slot7",87,"ID",$PlayerID);
						UpdateData("Joueur","Note",-100,"ID",$PlayerID);
						$mes = "Votre pilote arbore son nouvel équipement!";
					}
				break;
				case 1010:
					if($Note >= 100)
					{
						$con = dbconnecti();
						$result = mysqli_query($con, "SELECT Moral,Courage FROM Joueur WHERE ID='$PlayerID'");
						mysqli_close($con);
						if($result)
						{
							while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
							{
								$Moral = $data['Moral'];
								$Courage = $data['Courage'];
							}
							mysqli_free_result($result);
						}
						if($Moral < 255)
							SetData("Joueur", "Moral", 255, "ID", $PlayerID);
						if($Courage < 255)
							SetData("Joueur", "Courage", 255, "ID", $PlayerID);
						UpdateData("Joueur","Note",-100,"ID",$PlayerID);
						$mes = "Votre pilote bénéficie d'une permission bien méritée!";
					}
				break;
				case 1011:
					if($Note >= 250)
					{
						SetData("Joueur","Hide",1,"ID",$PlayerID);
						UpdateData("Joueur","Note",-250,"ID",$PlayerID);
						$mes = "Le dossier de votre pilote est soigneusement maquillé";
					}
				break;
				case 1006:
					if($Note >= 500)
					{
						$Avion_P = GetData("Joueur","ID",$PlayerID,"Avion_Perso");
						if($Avion_P)
						{
							$ID_ref = GetData("Avions_Persos","ID",$Avion_P,"ID_ref");
							$Robustesse = GetData("Avion","ID",$ID_ref,"Robustesse");
							SetData("Avions_Persos","Robustesse",$Robustesse,"ID",$Avion_P);
							SetData("Joueur","S_HP",$Robustesse,"ID",$PlayerID);
							UpdateData("Joueur","Note",-500,"ID",$PlayerID);
							$mes = "L'avion perso de votre pilote est comme neuf!";
						}
					}
				break;
				case 1012:
					if($Note >= 750)
					{
						$con = dbconnecti();
						$result = mysqli_query($con, "SELECT Acrobatie,Bombardement,Navigation,Pilotage,Tactique,Tir,Vue FROM Joueur WHERE ID='$PlayerID'");
						mysqli_close($con);
						if($result)
						{
							while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
							{
								if($data['Acrobatie'] < 75)
									SetData("Joueur", "Acrobatie", 75, "ID", $PlayerID);
								if($data['Bombardement'] < 75)
									SetData("Joueur", "Bombardement", 75, "ID", $PlayerID);
								if($data['Navigation'] < 75)
									SetData("Joueur", "Navigation", 75, "ID", $PlayerID);
								if($data['Pilotage'] < 75)
									SetData("Joueur", "Pilotage", 75, "ID", $PlayerID);
								if($data['Tactique'] < 75)
									SetData("Joueur", "Tactique", 75, "ID", $PlayerID);
								if($data['Tir'] < 75)
									SetData("Joueur", "Tir", 75, "ID", $PlayerID);
								if($data['Vue'] < 75)
									SetData("Joueur", "Vue", 75, "ID", $PlayerID);							
							}
							mysqli_free_result($result);
							unset($data);
							unset($result);
						}
						UpdateData("Joueur","Note",-750,"ID",$PlayerID);
						$mes = "Votre pilote bénéficie d'un entrainement accéléré!";
					}
				break;
				case 1013:
					if($Note >= 1000)
					{
						$con = dbconnecti();
						$result = mysqli_query($con, "SELECT Acrobatie,Bombardement,Navigation,Pilotage,Tactique,Tir,Vue FROM Joueur WHERE ID='$PlayerID'");
						mysqli_close($con);
						if($result)
						{
							while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
							{
								if($data['Acrobatie'] < 100)
									SetData("Joueur", "Acrobatie", 100, "ID", $PlayerID);
								if($data['Bombardement'] < 100)
									SetData("Joueur", "Bombardement", 100, "ID", $PlayerID);
								if($data['Navigation'] < 100)
									SetData("Joueur", "Navigation", 100, "ID", $PlayerID);
								if($data['Pilotage'] < 100)
									SetData("Joueur", "Pilotage", 100, "ID", $PlayerID);
								if($data['Tactique'] < 100)
									SetData("Joueur", "Tactique", 100, "ID", $PlayerID);
								if($data['Tir'] < 100)
									SetData("Joueur", "Tir", 100, "ID", $PlayerID);
								if($data['Vue'] < 100)
									SetData("Joueur", "Vue", 100, "ID", $PlayerID);							
							}
							mysqli_free_result($result);
							unset($data);
							unset($result);
						}
						UpdateData("Joueur","Note",-1000,"ID",$PlayerID);
						$mes = "Votre pilote bénéficie d'un entrainement accéléré!";
					}
				break;
				case 1014:
					if($Note >= 2000)
					{
						$Equipage = GetData("Joueur","ID",$PlayerID,"Equipage");
						if($Equipage > 0)
						{
							$con = dbconnecti();
							$result = mysqli_query($con, "SELECT Bombardement,Mecanique,Navigation,Premiers_Soins,Radar,Radio,Tir,Vue FROM Equipage WHERE ID='$Equipage'");
							mysqli_close($con);
							if($result)
							{
								while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
								{
									if($data['Bombardement'] < 75)
										SetData("Equipage", "Bombardement", 75, "ID", $Equipage);
									if($data['Mecanique'] < 75)
										SetData("Equipage", "Mecanique", 75, "ID", $Equipage);
									if($data['Navigation'] < 75)
										SetData("Equipage", "Navigation", 75, "ID", $Equipage);
									if($data['Premiers_Soins'] < 75)
										SetData("Equipage", "Premiers_Soins", 75, "ID", $Equipage);
									if($data['Radar'] < 75)
										SetData("Equipage", "Radar", 75, "ID", $Equipage);
									if($data['Radio'] < 75)
										SetData("Equipage", "Radio", 75, "ID", $Equipage);
									if($data['Tir'] < 75)
										SetData("Equipage", "Tir", 75, "ID", $Equipage);
									if($data['Vue'] < 75)
										SetData("Equipage", "Vue", 75, "ID", $Equipage);							
								}
								mysqli_free_result($result);
								unset($data);
								unset($result);
							}
							UpdateData("Joueur","Note",-2000,"ID",$PlayerID);
							$mes = "Votre membre d'équipage bénéficie d'un entrainement accéléré!";
						}
					}
				break;
			}
		}
		elseif($OfficierID > 0)
		{
			$Note = GetData("Officier","ID",$OfficierID,"Note");
			switch($Cadeau)
			{
				case 1005:
					if($Note >= 100)
					{
						$con = dbconnecti();
						$up = mysqli_query($con, "UPDATE Regiment SET Moral=255 WHERE Officier_ID='$OfficierID'");
						mysqli_close($con);
						UpdateData("Officier","Note",-100,"ID",$OfficierID);
						$mes = "Le moral de vos troupes est au beau fixe!";
					}
				break;
				case 1007:
					if($Note >= 500)
					{
						$con = dbconnecti();
						$up = mysqli_query($con, "UPDATE Regiment SET Stock_Essence_87=Stock_Essence_87+5000 WHERE Officier_ID='$OfficierID'");
						mysqli_close($con);
						UpdateData("Officier","Note",-500,"ID",$OfficierID);
						$mes = "Vos troupes bénéficient d'un supplément de carburant prioritaire!";
					}
				break;
				case 1015:
					if($Note >= 50)
					{
						UpdateData("Regiment", "Experience", 50, "Officier_ID", $OfficierID, 50);
						UpdateData("Officier","Note",-50,"ID",$OfficierID);
						$mes = "Vos troupes bénéficient d'un entrainement accéléré!";
					}
				break;
				case 1016:
					if($Note >= 500)
					{
						include_once('./jfv_ground.inc.php');
						$con = dbconnecti();
						$result = mysqli_query($con, "SELECT Front,Pays FROM Officier WHERE ID='$OfficierID'");
						mysqli_close($con);
						if($result)
						{
							while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
							{
								$Front = $data['Front'];
								$country = $data['Pays'];
							}
							mysqli_free_result($result);
						}
						$Retraite = Get_Retraite($Front,$country,40);
						if($Retraite > 0)
						{
							$con = dbconnecti();
							$up = mysqli_query($con, "UPDATE Regiment SET Lieu_ID='$Retraite' WHERE Officier_ID='$OfficierID'");
							mysqli_close($con);
							UpdateData("Officier","Note",-500,"ID",$OfficierID);
						}
						$mes = "Vos troupes rejoignent votre base arrière via un transport prioritaire";
					}
				break;
				case 1017:
					if($Note >= 100)
					{
						UpdateData("Officier","Reputation",500,"ID",$OfficierID);
						UpdateData("Officier","Note",-100,"ID",$OfficierID);
						$mes = "Votre officier est récompensé de ses efforts!";
					}
				break;
				case 1018:
					if($Note >= 200)
					{
						UpdateData("Officier","Reputation",1000,"ID",$OfficierID);
						UpdateData("Officier","Note",-200,"ID",$OfficierID);
						$mes = "Votre officier est récompensé de ses efforts!";
					}
				break;
				case 1019:
					if($Note >= 500)
					{
						UpdateData("Officier","Reputation",2500,"ID",$OfficierID);
						UpdateData("Officier","Note",-500,"ID",$OfficierID);
						$mes = "Votre officier est récompensé de ses efforts!";
					}
				break;
			}
		}
		if(!$mes)
			$mes = "Vous n'avez pas suffisamment de points pour choisir cette option!<br><a href='cadeau_2014.php' class='btn btn-default' title='Retour'>Retour</a>";
	}
	else
	{
		if($PlayerID > 0)
		{
			$con = dbconnecti();
			$result = mysqli_query($con, "SELECT Hide,Note,Avion_Perso,Equipage FROM Joueur WHERE ID='$PlayerID'");
			mysqli_close($con);
			if($result)
			{
				while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
				{
					$Hide = $data['Hide'];
					$Note = $data['Note'];
					$Avion_Perso = $data['Avion_Perso'];
					$Equipage = $data['Equipage'];
				}
				mysqli_free_result($result);
			}
			$mes = "<table class='table'><thead><tr><th>Cadeau</th><th>Description</th><th>Coût</th><th>Action</th></tr></thead>
			<tr><td><img src='/images/matos77.gif'><br>Fer à Cheval</td><td>Objet d'inventaire de poche pour votre pilote augmentant grandement la chance<br><i>Cet objet remplacera l'objet de poche actuel</i></td><td class='btn btn-primary'>50</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1001'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/matos74.gif'><br>Gants de pilote</td><td>Objet d'inventaire pour votre pilote augmentant son pilotage<br><i>Cet objet remplacera les gants actuels</i></td><td class='btn btn-primary'>50</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1002'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/matos70.gif'><br>Casque de cuir de vétéran</td><td>Objet d'inventaire pour votre pilote augmentant sa détection et ses chances d'éviter un coup critique<br><i>Cet objet remplacera le casque actuel</i></td><td class='btn btn-primary'>50</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1003'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/matos87.gif'><br>Gants d'As</td><td>Objet d'inventaire pour votre pilote augmentant son pilotage et son adresse au tir<br><i>Cet objet remplacera les gants actuels</i></td><td class='btn btn-primary'>100</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1004'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/perm.png'><br>Permission</td><td>Accorde à votre pilote une permission bien méritée<br>Idéal pour récupérer Moral et Courage!</td><td class='btn btn-primary'>100</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1010'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
			if(!$Hide)
				$mes.="<tr><td><img src='/images/hide.png'><br>Dossier caché</td><td>Dissimule le dossier de votre pilote</td><td class='btn btn-primary'>250</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1011'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
			else
				$mes.="<tr><td><img src='/images/hide.png'><br>Dossier caché</td><td>Dissimule le dossier de votre pilote</td><td class='btn btn-primary'>250</td><td>Dossier déjà dissimulé</td></tr>";
			if($Avion_Perso > 0)
				$mes .="<tr><td><img src='/images/vehicule1000.gif'><br>Avion perso</td><td>Les mécanos réparent votre avion perso en un temps record!</td><td class='btn btn-primary'>500</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1006'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
			else
				$mes .="<tr><td><img src='/images/vehicule1000.gif'><br>Avion perso</td><td>Les mécanos réparent votre avion perso en un temps record!</td><td class='btn btn-primary'>500</td><td>Pas d'avion perso</td></tr>";
			if($Equipage > 0)
				$mes .="<tr><td><img src='/images/obs.png' width='25%' height='25%'><br>Equipage entrainé</td><td>Votre membre d'équipage participe à une scéance de formation accélérée</td><td class='btn btn-primary'>750</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1014'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
			else
				$mes .="<tr><td><img src='/images/obs.png' width='25%' height='25%'><br>Equipage entrainé</td><td>Votre membre d'équipage participe à une scéance de formation accélérée</td><td class='btn btn-primary'>750</td><td>Pas de membre d'équipage</td></tr>";
			$mes .="<tr><td><img src='/images/pilot_l1.png' width='25%' height='25%'><br>Pilote entrainé</td><td>Votre pilote participe à une scéance de formation accélérée</td><td class='btn btn-primary'>1000</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1012'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/pilot_l2.png' width='25%' height='25%'><br>Pilote chevronné</td><td>Votre pilote participe à une scéance de formation accélérée</td><td class='btn btn-primary'>2000</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1013'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
		}
		else
		{
			$Note = GetData("Officier","ID",$OfficierID,"Note");
			$mes ="<table class='table'><thead><tr><th>Cadeau</th><th>Description</th><th>Coût</th><th>Action</th></tr></thead>
			<tr><td><img src='/images/soldat.png' width='25%' height='25%'><br>Troupes entrainées</td><td>Vos troupes suivent une formation accélérée<br><i>Expérience maximum 50</i></td><td class='btn btn-primary'>50</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1015'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/beer_icon.png'><br>Bonus de moral</td><td>Accorde le maximum de moral à vos unités terrestres</td><td class='btn btn-primary'>100</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1005'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/general1.png'><br>Officier réputé</td><td>Accorde un petit bonus de réputation à votre officier</td><td class='btn btn-primary'>100</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1017'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/general5.png'><br>Officier assez réputé</td><td>Accorde un bonus moyen de réputation à votre officier</td><td class='btn btn-primary'>200</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1018'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/general9.png'><br>Officier très réputé</td><td>Accorde un grand bonus de réputation à votre officier</td><td class='btn btn-primary'>500</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1019'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/essence_icon.png'><br>Bonus de carburant</td><td>Accorde un supplément de 5.000L de carburant à vos unités terrestres<br><i>Ne peut en aucun cas dépasser le maximum autorisé</i></td><td class='btn btn-primary'>500</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1007'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>
			<tr><td><img src='/images/train_tr.png'><br>Repli prioritaire</td><td>Permet à vos troupes de se replier sur la base arrière sans pénalités</td><td class='btn btn-primary'>500</td><td><form action='cadeau_2014.php' method='post'><input type='hidden' name='ID' value='1016'><input type='Submit' value='Choisir' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
		}	
		$mes.="</table>";
	}
	$titre = "Cadeaux";
	$img = "<div class='row'><div class='col-md-6'><div class='btn btn-primary'>Points<br>".$Note."</div></div><div class='col-md-6'><img src='images/christmas.jpg'></div></div>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>