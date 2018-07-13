<div id="fb-root"></div>
<!--<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>-->
<?	
			include_once('./jfv_include.inc.php');
			$PlayerID = Insec($_SESSION['PlayerID']);
			$Dist = $_SESSION['Distance'];
			echo "<table border='0' cellpadding='0' cellspacing='0'><tr><td>";
			if($Dist == 0 or (!isset($_SESSION['login']) AND !isset($_SESSION['pwd'])))
			{
				echo "<a href='index.php' target=''><img src='buttons/accueil_bouton.png' border='0' id='button1' alt='home'></a><br>";
			}
			if(!isset($_SESSION['login']) AND !isset($_SESSION['pwd']))
			{
				echo "<a href='index.php?view=login' target=''><img src='buttons/login_bouton.png' border='0' id='button11' alt='login'></a><br>";
			}
			else
			{
				echo "<a href='index.php?view=delog' target=''><img src='buttons/logout_bouton.png' border='0' id='button11' alt='delog'></a><br>";
				if($PlayerID and $Dist == 0)
				{
					$con = dbconnecti(3);
					$ok = mysqli_query($con, "SELECT COUNT(*) FROM Messages WHERE Reception='$PlayerID' AND Lu='0'");
					mysqli_close($con);
					if($ok)
					{
						while ($data=mysqli_fetch_array($ok, MYSQLI_NUM)) 
						{
							if($data[0] > 0)
							{
								echo "<a title='Vous avez du courrier' href='index.php?view=messagerie'><img src='images/icone_courrier.gif' alt='Nouveau Message'></a><br>";
							}
						}
					}
				}
			}
			if($Dist == 0)
			{
				echo "<a href='index.php?view=compte' target=''><img src='buttons/compte_bouton.png' border='0' id='button3' alt='compte'></a><br>";
				echo "</td></tr>
				<tr><td><hr></td></tr>
				<tr><td>";	
				if($PlayerID == 0)
				{
					echo "<a href='index.php?view=ranking' target=''><img src='buttons/ranking_bouton.png' border='0' id='button5' alt='classements'></a><br>
					<a href='index.php?view=infos' target=''><img src='buttons/infos_bouton.png' border='0' id='button6' alt='infos'></a><br>
					<a href='index.php?view=news' target=''><img src='buttons/news_bouton.png' border='0' id='button7' alt='news'></a><br>";
				}
				else
				{
					echo "<a href='index.php?view=user' target=''><img src='buttons/profil_bouton.png' border='0' id='button1' alt='Profil'></a><br>
					<a href='index.php?view=mission_start' target=''><img src='buttons/missions_bouton.png' border='0' id='button2' alt='Mission'></a><br>
					<a href='index.php?view=escadrille' target=''><img src='buttons/escadrille_bouton.png' border='0' id='button3' alt='Squadron'></a><br>
					<a href='index.php?view=em_staff' target=''><img src='buttons/em_bouton.png' border='0' id='button4' alt='Staff'></a><br>
					</td></tr>
					<tr><td><hr></td></tr>
					<tr><td>
					<a href='index.php?view=ranking' target=''><img src='buttons/ranking_bouton.png' border='0' id='button5' alt='classements'></a><br>
					<a href='index.php?view=infos' target=''><img src='buttons/infos_bouton.png' border='0' id='button6' alt='infos'></a><br>
					<a href='index.php?view=news' target=''><img src='buttons/news_bouton.png' border='0' id='button7' alt='news'></a><br>";
				}
				echo "</td></tr>
				<tr><td><hr></td></tr>
				<tr><td>";
				echo "<a href='index.php?view=regles' target=''><img src='buttons/regles_bouton.png' border='0' id='button8' alt='Règles'></a><br>
				<a href='http://cheratte.net/aceofaces/forum/index.php' target='_blank'><img src='buttons/forum_bouton.png' border='0' id='button9' alt='Forum'></a><br>";
				if($PlayerID == 1)
					echo "<a href='index.php?view=as_des_as_init' target=''><img src='buttons/as_des_as_bouton.png' border='0' id='button11' alt='Détente'></a><br>";
				if(GetData("Joueur","ID",$PlayerID,"Encodage") > 0)
					echo "<a href='index.php?view=db_pilotes' target=''><img src='buttons/contact_bouton.png' border='0' id='button10' alt='Tools'></a><br>";
				echo "</td></tr></table>";
				
				echo "<table width='152'>
				<tr><td colspan='4'><hr></td></tr>
				<tr bgcolor='#ECDDC1'><th class='Titrelgy_bc'>".GetData("Conf_Update","ID",2,"Date")."</th></tr>
				<tr bgcolor='#ECDDC1'><td align='center'>";
				
				PrintOnlinePlayers($PlayerID);
				if($PlayerID == 1)
				{
					//GetData Enis	PVP	
					$con = dbconnecti();
					$result = mysqli_query($con, "SELECT PlayerID,Lieu,Avion FROM Duels_Candidats WHERE PlayerID<>'$PlayerID'");
					mysqli_close($con);
					if($result)
					{
						while($data = mysqli_fetch_array($result, MYSQLI_ASSOC))
						{
							$En_PlayerID = $data['PlayerID'];
							$En_Lieu = $data['Lieu'];
							$En_Avion = $data['Avion'];
							echo "<p>".GetData("Pilote","ID",$En_PlayerID,"Nom")." ( ".GetData("Avion","ID",$En_Avion,"Nom")." ) : ".GetData("Lieu","ID",$En_Lieu,"Nom")."</p>";
						}
						mysqli_free_result($result);
						unset($data);
						unset($result);
					}
				}
				echo "</td></tr><tr><td colspan='4'><hr></td></tr>";
	?>
	<tr><td>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="G9MSGYVP7MWMJ">
		<input type="image" src="https://www.paypalobjects.com/fr_FR/BE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
		<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
		</form>
	</td></tr>
	<tr><td align="center">
		<font size="1"><a href="index.php?view=credits">Credits</a><br>Coded by <a href="mailto:admin@aubedesaigles.net">JF Binote</a></font>
	</td></tr>
	<tr><td colspan="4"><hr></td></tr>
	<tr><td width="76">
		<div class="fb-like" data-href="http://aubedesaigles.net" data-send="false" data-layout="button_count" data-width="120" data-show-faces="false" data-font="arial"></div>
		<!--<a href="https://www.facebook.com/AubeDesAigles" title="Page Facebook du jeu" target="_blank"><img src="images/icone_facebook.png"></a>-->
		<a href="mumble://mumble11.omgserv.com:11136/?version=1.2.3" title="Canal Mumble du jeu" target="_blank"><img src="images/mumble.png"></a>
	</td></tr>
	<?if($PlayerID > 1){?>
	<tr>
		<td><iframe src="http://mumbleviewer.omgserv.com/?id=21020&size=8&font=Verdana&color=000000&bgcolor=FFFFFF" scrolling="vertical" frameborder="0" height="250" width="150" style="-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;"></iframe></td>
	</tr>
	<?}?>
	<tr><td colspan="4"><hr></td></tr>
	<tr><td align="center"><font size="1">Partenaires</font></td></tr>
	<tr><td><a href="http://www.lemeilleurjeu.net/" target="_blank" title="Votez pour le meilleur des jeux en ligne"><img border="0" src="http://www.lemeilleurjeu.net/images/promo/88x31.gif" width="88" height="31" alt="Meilleurs jeux en ligne gratuits"></a></td></tr>
	<tr><td><a href="http://www.portaildesjeux.com" target="_blank" title="Jeux"><img src="http://www.portaildesjeux.com/images/portaildesjeux.gif" border="0" alt="jeux gratuits"></a></td></tr>
	<tr><td><a href="http://www.sitacados.com/jeux-mmorpg-fiches-56-p1.html" title="Jeux MMORPG gratuit en ligne ou à télécharger" target="_blank">MMORPG</a></td></tr>
	<tr><td><a href="http://www.sitafamille.com/jeux-de-role-strategie-r166.html" title="Jeux de stratégie gratuits en ligne" target="_blank">Jeux de stratégie</a></td></tr>
	<tr><td><a title="Jeux Gratuits" href="http://www.divertissez-vous.com/" target="_blank"><img src="http://www.divertissez-vous.com/autopromo/div88x31.gif" border="0" alt="Jeux Gratuits sur Divertissez-Vous.com"></a></td></tr>
	<tr><td><a href="http://mes-jeux-gratuits.fr" title="jeux gratuits" target="_blank"><img src="http://mes-jeux-gratuits.fr/88.gif" border="0" alt="jeux gratuits"></a></td></tr>
	<tr><td><a href="http://www.jeux-de-mmo.com" target="_blank">MMORPG gratuits</a></td></tr>
	<tr><td><a href="http://www.tourdejeu.net" target="_blank"><img src="http://www.tourdejeu.net/images/boutonanim.gif" width="88" height="31"></a></td></tr>
<?}?></table>