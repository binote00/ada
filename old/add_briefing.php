//Check Joueur Valide
if(isset($_SESSION['login']) AND isset($_SESSION['pwd']) AND $Msg)
{
	$Officier = Insec($_POST['Officier']);
	$Unite = Insec($_POST['Unite']);
	if($Unite > 99999)
		include_once('./menu_em.php');
	else
		include_once('./menu_escadrille.php');
	$date=date('Y-m-d G:i');
	dbconnect();
	$query="INSERT INTO Chat (PlayerID, Unit, date, Msg)
	VALUES ('$Officier','$Unite','$date','$Msg')";
	$ok=mysql_query($query);
	mysql_close();
	if($ok)
	{
		echo "Message post� avec succ�s!";
		echo "<br><a title='Retour � l'�cran d'Escadrille' href='index.php?view=esc_infos'>Retour � l'Escadrille</a>";
	}
	else
	{
		echo "Erreur d'envoi de message!";
		echo "<br><a title='Retour � l'�cran d'Escadrille' href='index.php?view=esc_infos'>Retour � l'Escadrille</a>";
	}
}
else
{
	echo "<font color='#000000' size='4'>Vous devez �tre connect� pour acc�der � cette page!</font>";
}
?>