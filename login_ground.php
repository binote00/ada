<?
session_unset();
/*
//session_destroy();
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_msg.inc.php');
include_once('./jfv_txt.inc.php');
if(isset($_POST['off_id']))
	$off_id=Insec($_POST['off_id']);
else
	$off_id="";
if(empty($off_id))
{
	$mes="Erreur d'identification!<br><a href='index.php?view=login'><span>Cliquez ici pour recommencer</span></a>";
	$img="<img src='images/tsss.jpg'>";
}
else
{
	$AccountID=Insec($_POST['ply_id']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT COUNT(*),ID,Nom,Pays,Front,Division,Reputation,Avancement,Trait,Heure_Para,Credits,Credits_date FROM Officier WHERE ID='$off_id'")
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : loging-p');
	mysqli_close($con);
	while($data=mysqli_fetch_array($result))
	{
		$num=$data[0];
		$ID=$data['ID'];
		$Nom=$data['Nom'];
		$country=$data['Pays'];
		$Front=$data['Front'];
		$Credits_Ori=$data['Credits'];
		$Credits_date=$data['Credits_date'];
		$Division=$data['Division'];
		$Reputation=$data['Reputation'];
		$Avancement=$data['Avancement'];
		$Trait_o=$data['Trait'];
		$Heure_Para=$data['Heure_Para'];
	}
	if($num >0)
	{
		if($ID ==$off_id)
		{
			//Trait
			//if(!$Trait_o and ($Reputation >50 or $Avancement >5100))SendMsgOff($ID,0,"Bonjour,\n Vous pouvez à présent choisir votre trait de spécialisation en vous rendant dans le profil de votre officier.","Promotion",0,2);
			$_SESSION['AccountID']=$AccountID;
			$_SESSION['Officier']=$ID;
			$_SESSION['country']=$country;
			$_SESSION['PlayerID']=false;
			$_SESSION['Officier_em']=false;
			$_SESSION['Pilote_pvp']=false;
			$_SESSION['Officier_pvp']=false;
			$con=dbconnecti();
			$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
			$ok=mysqli_query($con,"SELECT c.*,o.Nom,o.Avancement FROM gnmh_aubedesaiglesnet3.Chat as c,Officier_em as o WHERE c.PlayerID=o.ID AND c.Front IN('$Front') AND c.Pays='$country' ORDER BY c.Date DESC LIMIT 10");
			$Objectif=mysqli_result(mysqli_query($con,"SELECT l.Nom FROM Pays as p,Lieu as l WHERE p.Co_Lieu_Mission=l.ID AND p.Pays_ID='$country' AND p.Front='$Front'"),0);
			$results=mysqli_query($con,"SELECT SectionID FROM Sections WHERE OfficierID='$ID'");
			if($results)$Reg_QG_Base=mysqli_result(mysqli_query($con,"SELECT Lieu_ID FROM Regiment WHERE Officier_ID='$ID'"),0);
			$resultd=mysqli_query($con,"SELECT o.Nom,o.Avancement,o.Pays,l.ID,l.Nom as lieu FROM Officier as o,Lieu as l WHERE o.Pays='$country' AND o.Front IN('$Front') AND o.Aide >0 AND o.Aide=l.ID");
			mysqli_close($con);
			if($resultd)
			{
				while($datad=mysqli_fetch_array($resultd,MYSQLI_ASSOC)) 
				{
					$Grade=GetAvancement($datad['Avancement'],$datad['Pays'],false,1);
					$Msg_Help.="<br><b>".$Grade[0]." ".$datad['Nom']."</b> demande des renforts dans les environs de <b>".$datad['lieu']."</b>";
				}
				mysqli_free_result($resultd);
			}
			if($results)
			{
				$Grade_Level=GetAvancement($Avancement,$country);
				while($datas=mysqli_fetch_array($results)) 
				{
					if($datas['SectionID'] ==6)
					{
						$Section_Topo=true;
						//if($Grade_Level[1] >10)$Infos_txt.="Les unités du bataillon situées sur le même lieu sont camouflées automatiquement (une fois par jour lors de la 1e connexion, sauf les unités ayant déjà utilisé leur action du jour)<br>";
						//if($Grade_Level[1] >9)$Infos_txt.="Les unités du bataillon situées sur le même lieu bénéficient d'un bonus tactique";
					}
				}
			}
			if($ok)
			{
				while($data=mysqli_fetch_array($ok,MYSQLI_ASSOC)) 
				{
					$Officier=$data['PlayerID'];
					$date=$data['Date'];
					$Officier_nom=$data['Nom'];
					$Grade=GetAvancement($data['Avancement'],$country);
					$annee=substr($date,0,4); //str_replace("2012","1940",substr($date,0,4));
					$mois=substr($date,5,2);
					$jour=substr($date,8,2);
					$Msg_Off.="<p>".$jour."-".$mois."-".$annee.". Du <b>".$Grade[0]." ".$Officier_nom."</b> à tous les officiers:<p><i>".nl2br($data['Msg'])."</i></p></p><hr>";
				}
				mysqli_free_result($ok);
			}
			SetData("Officier","Actif",0,"ID",$ID);
			$_SESSION['Saison']=GetSaison($Date_Campagne);
			$Date_Actuelle=date("Y-m-d");
			$Heure=date("H");
			//Update des crédits en cas de nouvelle journée
			if($Date_Actuelle >$Credits_date and $Heure >6 and $Heure_Para <$Heure)
			{
				if($Credits_Ori >-1)
				{
					if($Section_Topo)
						SetData("Lieu","Recce",1,"ID",$Reg_QG_Base);
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Officier SET Atk=0,Orders=0,Credits=40,Credits_date='$Date_Actuelle',Heure_Para=0,Heure_Mission=0 WHERE ID='$ID'");
					$reset2=mysqli_query($con,"UPDATE Regiment SET Atk_H=0,Atk=0 WHERE Officier_ID='$ID'");
					mysqli_close($con);
				}
				else
					SetData("Officier","Credits",0,"ID",$ID);
			}
			//Msg EM
			if($Msg_Off =="")$Msg_Off="Aucun message n'a été posté par votre Etat-Major.";
			if($off_id ==1 or (!$Trait_o and $Reputation ==0 and $Avancement ==5000))
				$annonce="<div class='alert alert-warning'>De nouvelles options de jeu apparaitront <b>si vous faites partie d'une division</b>.<br>Vous pouvez postuler via <a href='index.php?view=ground_profile' class='lien'>le profil de votre officier</a>.
							Une fois dans une division, lisez les ordres du jour et utilisez <a href='index.php?view=ground_appui' class='lien'>les transmissions</a></div>";
			else
				$annonce="<div class='alert alert-danger'>Une mise à jour importante de l'officier est actuellement en test.<br>Plus d'informations sur le forum du jeu.</div>";
			$intro=$Objectif_txt.$Msg_Help;
			$mes="<h3><div class='btn btn-primary'><a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=4&t=3529' target='_blank'>Modifications récentes du jeu</a></div></h3>".$annonce."<div id='esc_journal'>
				<table class='table'>
					<thead><tr><th>Communication de l'Etat-Major</th></tr></thead>
					<tr><td width='50%' align='left' valign='top'>".$Msg_Off."</td></tr>
				</table></div>";
			$titre="Bienvenue ".$Nom." !";
			$img=Afficher_Image('images/transfer_yes'.$country.'.jpg',"images/image.png","",50);
		}
		else
		{
			$titre="Erreur d'identification!";
			$mes="<a href='index.php?view=login' class='btn btn-warning'>Recommencer</a>";
			$img="<img src='images/tsss.jpg'>";
		}
	}
	else
	{
		$mes="Utilisateur inconnu!";
		$img="<img src='images/tsss.jpg'>";
	}
}
include_once('./index.php');
?>