<?
    $to      = 'jfvanass@gmail.com';
    $subject = 'RE : Candidature Développeur backend';
    $message = '<html>
    <body><p>Monsieur Vanass,
	<br><br>Nous accusons bonne réception de votre candidature. nous sommes au regret de vous annoncer qu\'elle n\'a pas été retenue.
	<br>Nous vous remercions de l\'intérêt que vous nous avez porté et vous souhaitons bonne chance dans votre recherche professionnelle.
	<br><br>Bien à vous,
    <br><br><br>Mitchell Séverine
	<br><br><table><tr><td><img src="http://www.aubedesaigles.net/logo_eteamsys.jpg"></td><td>
	</td></tr></table></body></html>';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: Séverine Mitchell <severine@eteamsys.lu>' . "\r\n" . 'Reply-To: severine@eteamsys.lu' . "\r\n";
	
	/*	
	<b>Bephoto sprl</b>
	<br>Avenue du Commerce, 50
	<br>1420 Braine-l\'Alleud
	<br>Belgique
	<br>Bureau : 028889957
	<br>GSM : 0496120758
	*/

    mail($to, $subject, $message, $headers);
	echo 'mail envoyé!';
?>