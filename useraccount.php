<?
require_once('./jfv_inc_sessions.php');
$AccountID=$_SESSION['AccountID'];
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	$result=mysqli_query($con,"SELECT adresse,login,Pays,Premium,DATE_FORMAT(Engagement,'%d-%m-%Y') as Engagement,DATE_FORMAT(Premium_date,'%d-%m-%Y') as Premium_date,Courier,Pilote_id,Officier_bonus,Officier_em,Admin FROM Joueur WHERE ID='$AccountID'");
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Email=$data['adresse'];
			$login=$data['login'];
            $Pays=$data['Pays'];
            $Engagement=$data['Engagement'];
			$Premium=$data['Premium'];
			$Premium_date=$data['Premium_date'];
			$Courier=$data['Courier'];
			$Pilote_id=$data['Pilote_id'];
			$Officier_em=$data['Officier_em'];
            $Officier_bonus=$data['Officier_bonus'];
			$Admin=$data['Admin'];
		}
		if($Premium)
			$Prem_date='Jusqu\'au '.$Premium_date;
		else
			$Prem_date='Non';
		if($Pilote_id)
		{
			$result2=mysqli_query($con,"SELECT Nom,Reputation,Pays,DATE_FORMAT(Engagement,'%d-%m-%Y') as Engagement,Photo,Photo_Premium,Note,Simu FROM Pilote WHERE ID='$Pilote_id'");
			if($result2)
			{
				while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Nom=$data['Nom'];
					$Reputation=$data['Reputation'];
					$Engagement=$data['Engagement'];
					$Pays_pilote=$data['Pays'];
					$Photo=$data['Photo'];
					$Photo_Premium=$data['Photo_Premium'];
					$Note=$data['Note'];
					$Simu=$data['Simu'];
				}
				mysqli_free_result($result2);
				unset($data);
			}
			if($Simu >0)
				$Simu='Non';
			else
				$Simu='Oui';
		}
		/*if($Ground_Officer)
		{
			$result2=mysqli_query($con,"SELECT Nom,Pays,Photo,Note FROM Officier WHERE ID='$Ground_Officer'");
			if($result2)
			{
				while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Nom_off=$data2['Nom'];
					$Pays_off=$data2['Pays'];
					$Photo_off=$data2['Photo'];
					$Note3=$data2['Note'];
				}
				mysqli_free_result($result2);
				unset($data2);
			}
		}*/
		if($Officier_em)
		{
			$result2=mysqli_query($con,"SELECT Nom,Pays,Photo,Photo_Premium,Note FROM Officier_em WHERE ID='$Officier_em'");
			if($result2)
			{
				while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Nom_off_em=$data2['Nom'];
					$Pays_off_em=$data2['Pays'];
					$Photo_off_em=$data2['Photo'];
					$Photo_Premium_off_em=$data2['Photo_Premium'];
					$Note2=$data2['Note'];
				}
				mysqli_free_result($result2);
				unset($data2);
			}
		}
        if($Officier_bonus)
        {
            $result2=mysqli_query($con,"SELECT Nom,Pays,Photo,Photo_Premium,Note FROM Officier_em WHERE ID='$Officier_bonus'");
            if($result2)
            {
                while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
                {
                    $Nom_off_bonus=$data2['Nom'];
                    $Pays_off_bonus=$data2['Pays'];
                    $Photo_off_bonus=$data2['Photo'];
                    $Photo_Premium_off_bonus=$data2['Photo_Premium'];
                    $Note2=$data2['Note'];
                }
                mysqli_free_result($result2);
                unset($data2);
            }
        }
		$Note+=($Note2+$Note3);
		$img_pilote='images/persos/pilote'.$Pays_pilote.$Photo.'.jpg';
		$img_off='images/persos/general'.$Pays_off_em.$Photo_off_em.'.jpg';
        $img_off_bonus='images/persos/general'.$Pays_off_bonus.$Photo_off_bonus.'.jpg';
		if($Premium >0)
		{
			if($Photo_Premium ==1)
				$img_pilote='uploads/Pilote/'.$Pilote_id.'_photo.jpg';
			if($Photo_Premium_off_em ==1)
				$img_off='uploads/Officier/'.$Officier_em.'_photo.jpg';
            if($Photo_Premium_off_bonus ==1)
                $img_off_bonus='uploads/Officier/'.$Officier_bonus.'_photo.jpg';
		}
        include_once('view/account.php');
/*
?>
	<h1>Informations du Compte</h1>
		<table class='table'>
		<thead><tr><th>Login</th><th>Email</th><th>Inscription</th><th>Premium <a href="index.php?view=abo"><img src='images/help.png'></a></th><th>Points de Coopération <a href="points_coop.php"><img src='images/help.png'></a></th></tr></thead>
			<tr><td><?=$login;?></td><td><?=$Email;?></td><td><?=$Engagement;?></td><td><?=$Prem_date;?></td><td><?=$Note;?></td></tr>
		</table>
<?
		echo "<table class='table'><thead><tr><th>Personnage</th><th>Nom</th><th>Pays</th><th>Photo</th></tr></thead>";
		if($Pilote_id)
			echo "<tr><td><img title='Pilote' src='images/new_aviateur.png'></td><th>".$Nom."</th><td><img src='images/flag".$Pays."p.jpg'></td><td><img src='".$img_pilote."'></td></tr>";
		if($Officier_em)
			echo "<tr><td><img title='Officier EM' src='images/new_em.png'></td><th>".$Nom_off_em."</th><td><img src='images/flag".$Pays_off_em."p.jpg'></td><td><img src='".$img_off."'></td></tr>";
		echo "</table>";
?>

		<h2>Modifications du compte</h2>
		<form action="account_update.php" method="post">
		<table class='table'>
			<thead><tr><th>Email</th><th>Mot de Passe</th><!--<th>Mode simulation</th>--><th>Réception du courrier par email</th></tr></thead>
			<tr>
				<td><input type="text" name="email" size="50" value="<?=$Email?>" class='form-control' onmouseup='valbtn.disabled=false;' required></td>
				<td><input type="password" name="password" size="30" class='form-control' onmouseup='valbtn.disabled=false;' required></td>
				<td>
				<?if($Courier){?>
					<Input type='Radio' name='courier' value='0'>- Non (par défaut)<br>
					<Input type='Radio' name='courier' value='1' checked>- Oui<br>
				<?}else{?>
					<Input type='Radio' name='courier' value='0' checked>- Non (par défaut)<br>
					<Input type='Radio' name='courier' value='1'>- Oui<br>
				<?}?>
				</td>
			</tr>
		</table>			
		<input type='Submit' value='VALIDER' id='valbtn' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form><hr>
?>
	<h2>Horaires de jeu <a href='#' class='popup'><img src='images/help.png'><span>Afin de faciliter la coordination au sein de votre faction</span></a></h2>
	<form action='horaires.php' method='post'>
	<div style='overflow:auto; width: 100%;'><table class='table'>
		<thead><tr><th>Jour</th><th>Sauf</th><th>De</th><th>à</th></tr></thead>
		<tr><td><?=$Dispos;?></td></tr>
		<tr><td><select name='jour' class='form-control' style='width: 300px'>
				<option value='0' selected>Ne rien changer</option>
				<option value='tous'>Tous les jours</option>
				<option value='we'>Les week-ends (samedi et dimanche)</option>
				<option value='sem'>Les jours de semaine (lundi au vendredi)</option>
				<option value='lu'>Lundi</option>
				<option value='ma'>Mardi</option>
				<option value='me'>Mercredi</option>
				<option value='je'>Jeudi</option>
				<option value='ve'>Vendredi</option>
				<option value='sa'>Samedi</option>
				<option value='di'>Dimanche</option>
			</select>
			</td><td><select name='sauf' class='form-control' style='width: 300px'>
				<option value='0' selected>Ne rien changer</option>
				<option value='aucun'>Aucun</option>
				<option value='lu'>Lundi</option>
				<option value='ma'>Mardi</option>
				<option value='me'>Mercredi</option>
				<option value='je'>Jeudi</option>
				<option value='ve'>Vendredi</option>
				<option value='sa'>Samedi</option>
				<option value='di'>Dimanche</option>
			</select>
			</td><td><select name='hdebut' class='form-control' style='width: 150px'>
				<option value='0' selected>Ne rien changer</option>
				<option value='9'>9h</option>
				<option value='10'>10h</option>
				<option value='11'>11h</option>
				<option value='12'>12h</option>
				<option value='13'>13h</option>
				<option value='14'>14h</option>
				<option value='15'>15h</option>
				<option value='16'>16h</option>
				<option value='17'>17h</option>
				<option value='18'>18h</option>
				<option value='19'>19h</option>
				<option value='20'>20h</option>
				<option value='21'>21h</option>
				<option value='22'>22h</option>
				<option value='23'>23h</option>
			</select>
			</td><td><select name='hfin' class='form-control' style='width: 150px'>
				<option value='0' selected>Ne rien changer</option>
				<option value='10'>10h</option>
				<option value='11'>11h</option>
				<option value='12'>12h</option>
				<option value='13'>13h</option>
				<option value='14'>14h</option>
				<option value='15'>15h</option>
				<option value='16'>16h</option>
				<option value='17'>17h</option>
				<option value='18'>18h</option>
				<option value='19'>19h</option>
				<option value='20'>20h</option>
				<option value='21'>21h</option>
				<option value='22'>22h</option>
				<option value='23'>23h</option>
				<option value='24'>00h</option>
			</select>		
		</td></tr></table><input type='Submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></div></form>
<?
        */
    }
	else{
		echo "<h1>Un problème est survenu lors de la récupération des données de votre profil</h1>";
		echo "<h6>Si le problème persiste, contactez un administrateur via le forum</h6>";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';