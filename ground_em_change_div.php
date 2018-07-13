<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
	if($Front !=12)
	{
        dbconnect();
        if(!$Admin){
            $result2 = $dbh->prepare("SELECT Commandant FROM Pays WHERE Pays_ID=:country AND Front=:front");
            $result2->bindParam(':country', $country, 1);
            $result2->bindParam(':front', $Front, 1);
            $result2->execute();
            $data=$result2->fetchObject();
            $Commandant=$data->Commandant;
        }
        include_once('./jfv_inc_em.php');
        if($OfficierEMID ==$Commandant or $Admin)
		{
			$Armee=Insec($_GET['armee']);
			$Division=Insec($_GET['div']);
			if($Division and $Armee)
			{
                if($Armee ==9999)$Armee=0;
                $resetl = $dbh->prepare("UPDATE Division SET Armee=:armee WHERE ID=:division");
                $resetl->bindParam(':armee', $Armee, 1);
                $resetl->bindParam(':division', $Division, 1);
                $resetl->execute();
                if($resetl->rowCount())
                    $_SESSION['msg_em'] = 'La division a changé d\'armée avec succès!';
                else
                    $_SESSION['msg_em_red'] = 'Erreur!';
			}
            header('Location: ./index.php?view=ground_em');
		}
		else
			echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";