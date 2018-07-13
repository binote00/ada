<?php
require_once('./jfv_inc_sessions.php');
$AccountID = $_SESSION['AccountID'];
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,adresse,Premium,DATE_FORMAT(Premium_date,'%d-%m-%Y') as Premium_date,Admin FROM Joueur WHERE ID='$AccountID'");
	mysqli_close($con);
	if($result)
	{
		while($data = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Nom = $data['Nom'];
			$adresse = $data['adresse'];
			$Premium = $data['Premium'];
			$Premium_date = $data['Premium_date'];
			$Admin = $data['Admin'];
		}
		mysqli_free_result($result);
	}	
	if($Premium)
	{
		$Premium="Oui";
		$Prem_date="<tr><td>Date d'expiration</td><th>".$Premium_date."</th></tr>";
	}
	else
		$Premium="Non";
	echo "<h1>Premium</h1><div id='col_droite'><table class='table table-striped'>
		<thead><tr><th colspan='2'>Compte</th></tr></thead>
		<tr><td>Nom du pilote</td><th>".$Nom."</th></tr>
		<tr><td>Adresse E-mail</td><th>".$adresse."</th></tr>
		<tr><td>Premium</td><th>".$Premium."</th></tr>
		".$Prem_date."<tr><td colspan='2' align='center'><img src='/images/premium.png'></td></tr></table></div>
		<div id='col_droite'><table class='table table-striped'>
		<thead><tr><th>Fonctionnalités Premium</th></tr></thead>
		<tr><td align='left'><a href='/Premium/jaugesprofil.jpg' target='_blank' rel='noreferrer' class='lien'>- Jauges de progression</a></td></tr>
		<tr><td align='left'><a href='/Premium/comparateur.jpg' target='_blank' rel='noreferrer' class='lien'>- Comparateur de performances d'avions</a></td></tr>
		<tr><td align='left'><a href='/Premium/comparateur_v.jpg' target='_blank' rel='noreferrer' class='lien'>- Comparateur de véhicules</a></td></tr>
		<tr><td align='left'><a href='/Premium/speedtest.jpg' target='_blank' rel='noreferrer' class='lien'>- Calculateur de vitesse</a></td></tr>
		<tr><td align='left'><a href='/Premium/landingtest.jpg' target='_blank' rel='noreferrer' class='lien'>- Simulateur d'atterrissage et de décollage</a></td></tr>
		<tr><td align='left'><a href='/Premium/champtir.jpg' target='_blank' rel='noreferrer' class='lien'>- Champ de tir</a></td></tr>
		<tr><td align='left'><a href='/Premium/photoprofil.jpg' target='_blank' rel='noreferrer' class='lien'>- Photo de profil personnalisée</a></td></tr>
		<tr><td align='left'><a href='/Premium/premium_cp.jpg' target='_blank' rel='noreferrer' class='lien'>- Comparateurs de pilotes et d'officiers</a></td></tr>
		<tr><td align='left'>- Simulateur de déplacement des unités aériennes et terrestres</td></tr>
		<tr><td align='left'>- Simulateur de recrutement des troupes terrestres et navales</td></tr>
		<tr><td align='left'>- Accès aux prototypes</td></tr>
		<tr><td align='left'>- Nombreuses statistiques supplémentaires</td></tr>
		</table></div>";	
	echo "<div id='profil_decorations'>
	<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>
	<table class='table'>
	<thead><tr><th>Souscription au compte Premium</th></tr></thead>
	<tr><td><input type='hidden' name='on0' value='Options'>Options</td></tr>
	<tr><td><select name='os0' class='form-control' style='width:50%'>
		<option value='1 mois -'>1 mois - €2,50</option>
		<option value='3 mois -'>3 mois - €6,00</option>
		<option value='6 mois -'>6 mois - €10,00</option>
		<option value='1 an -'>1 an - €15,00</option>
		<option value='A vie -'>A vie - €50,00</option>
	</select> </td></tr>
	</table>
	<div class='alert alert-danger'>Veuillez préciser l'adresse email d'inscription ou le nom d'un de vos personnages en communication si l'adresse email du payement est différente de <b>".$adresse."</b></div>
	<input type='hidden' name='item_number' value='".$AccountID."'>
	<input type='hidden' name='cmd' value='_s-xclick'>
	<input type='hidden' name='hosted_button_id' value='BPBED6U5TM6DS'>
	<input type='hidden' name='currency_code' value='EUR'>
	<input type='image' src='https://www.paypalobjects.com/fr_FR/BE/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !'>
	<img alt='' border='0' src='https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif' width='1' height='1'>
	</form>";
	echo "<div class='alert alert-info'>Si vous préférez utiliser un virement bancaire européen, contactez <b>premium@aubedesaigles.net</b></div>";
	if($Admin)
	{
		echo "<h2>Joueurs Premium</h2>";
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Nom,adresse,DATE_FORMAT(Premium_date,'%d-%m-%Y') as date_premium FROM Joueur WHERE Premium=1 ORDER BY Premium_date ASC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				echo "<br>".$data['adresse']." (".$data['Nom'].") - ".$data['date_premium'];
			}
			mysqli_free_result($result);
		}
	}
}
else
{
	echo "<div id='col_droite'><table class='table'>
		<thead><tr><th colspan='2'>Fonctionnalités<br>Premium</th></tr></thead>
		<tr><td align='left'><a href='/Premium/jaugesprofil.jpg' target='_blank' class='lien'>* Jauges de progression</a></td></tr>
		<tr><td align='left'><a href='/Premium/comparateur.jpg' target='_blank' class='lien'>* Comparateur de performances d'avions</a></td></tr>
		<tr><td align='left'><a href='/Premium/comparateur_v.jpg' target='_blank' class='lien'>* Comparateur de véhicules</a></td></tr>
		<tr><td align='left'><a href='/Premium/speedtest.jpg' target='_blank' class='lien'>* Calculateur de vitesse</a></td></tr>
		<tr><td align='left'><a href='/Premium/landingtest.jpg' target='_blank' class='lien'>* Simulateur d'atterrissage et de décollage</a></td></tr>
		<tr><td align='left'><a href='/Premium/champtir.jpg' target='_blank' class='lien'>* Champ de tir</a></td></tr>
		<tr><td align='left'><a href='/Premium/photoprofil.jpg' target='_blank' class='lien'>* Photo de profil personnalisée</a></td></tr>
		<tr><td align='left'>* Simulateur de recrutement</td></tr>
		<tr><td align='left'>* Nombreuses statistiques supplémentaires</td></tr>
		</table></div>";
	?><div align='center'><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- Test -->
				<ins class="adsbygoogle"
					 style="display:block"
					 data-ad-client="ca-pub-2250934311983740"
					 data-ad-slot="4633206569"
					 data-ad-format="auto"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script></div>
<?}?>