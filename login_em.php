<?
session_unset();
//session_destroy();
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(isset($_POST['off_id']))
	$off_id=Insec($_POST['off_id']);
else
	$off_id='';
if(empty($off_id))
{
	$mes="Erreur d'identification!<br><a href='index.php?view=login'><span>Cliquez ici pour recommencer</span></a>";
	$img="<img src='images/tsss.jpg'>";
}
else
{
    include_once('./jfv_nomission.inc.php');
    include_once('./jfv_msg.inc.php');
    include_once('./jfv_txt.inc.php');
	$AccountID=Insec($_POST['ply_id']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT COUNT(*),ID,Nom,Pays,Front,Reputation,Avancement,Armee,Trait,Credits,Credits_date FROM Officier_em WHERE ID='$off_id'")
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : loginem-p');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result))
		{
			$num=$data[0];
			$ID=$data['ID'];
			$Nom=$data['Nom'];
			$country=$data['Pays'];
			$Front=$data['Front'];
			$Credits_Ori=$data['Credits'];
			$Credits_date=$data['Credits_date'];
			$Reputation=$data['Reputation'];
			$Avancement=$data['Avancement'];
			$Trait_o=$data['Trait'];
            $Armee_o=$data['Armee'];
		}
	}
	if($num >0)
	{
		if($ID ==$off_id)
		{
			/*Trait
			if(!$Trait_o and ($Reputation > 50 or $Avancement > 5100))SendMsgOff($ID,0,"Bonjour,\n Vous pouvez à présent choisir votre trait de spécialisation en vous rendant dans le profil de votre officier.","[NO-REPLY] Promotion");*/
			$_SESSION['AccountID']=$AccountID;
			$_SESSION['Officier_em']=$ID;
			$_SESSION['country']=$country;
			$_SESSION['PlayerID']=false;
			$_SESSION['Officier']=false;
			$con=dbconnecti();
            $Cdt_Front=mysqli_result(mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$country' AND Front='$Front'"),0);
			$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
			$ok=mysqli_query($con,"SELECT c.*,o.Nom,o.Avancement FROM gnmh_aubedesaiglesnet3.Chat as c,Officier_em as o WHERE c.PlayerID=o.ID AND c.Front IN('$Front') AND c.Pays='$country' ORDER BY c.Date DESC LIMIT 10");
			$Objectif=mysqli_result(mysqli_query($con,"SELECT l.Nom FROM Pays as p,Lieu as l WHERE p.Co_Lieu_Mission=l.ID AND p.Pays_ID='$country' AND p.Front='$Front'"),0);
			if($ID ==1 or $Cdt_Front ==$ID){
                $Front=0;
                $Esc_Trans=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit WHERE Type=6 AND Mission_IA=0 AND Pays='$country'"),0);
                $Trains=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Vehicule_ID=424 AND Pays='$country' AND Front='$Front'"),0);
                $Depot_Gare_out=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Flag='$country' AND ValeurStrat >3 AND NoeudF_Ori >0 AND NoeudF <10"),0);
            }
            elseif($Armee_o){
                $Units_idle=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA AS r,Division as d WHERE r.Division=d.ID AND d.Armee='$Armee_o' AND r.Move=0"),0);
            }
			//$resultd=mysqli_query($con,"SELECT o.Nom,o.Avancement,o.Pays,l.ID,l.Nom as lieu FROM Officier as o,Lieu as l WHERE o.Pays='$country' AND o.Front IN('$Front') AND o.Aide >0 AND o.Aide=l.ID");
			mysqli_close($con);
			/*if($resultd)
			{
				while($datad=mysqli_fetch_array($resultd,MYSQLI_ASSOC)) 
				{
					$Grade=GetAvancement($datad['Avancement'],$datad['Pays'],false,1);
					$Msg_Help.="<br><b>".$Grade[0]." ".$datad['Nom']."</b> demande des renforts dans les environs de <b>".$datad['lieu']."</b>";
				}
				mysqli_free_result($resultd);
			}*/
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
                    $Msg_Off.="<div class='panel panel-default'>
                        <div class='panel panel-heading'>".$jour."-".$mois."-".$annee." : <span class='text-primary'>".$Grade[0]." ".$Officier_nom."</span> à tous les officiers</div>
                        <div class='panel panel-body'><i>".nl2br($data['Msg'])."</i></div>
                    </div>";
				}
				mysqli_free_result($ok);
			}
			$_SESSION['Saison']=GetSaison($Date_Campagne);
			$Date_Actuelle=date("Y-m-d");
			$Heure=date("H");
			//Update des crédits en cas de nouvelle journée
			if($Date_Actuelle >$Credits_date and $Heure >6)
			{
				if($Credits_Ori >-1)
				{
					$con=dbconnecti();
					$reset=mysqli_query($con,"UPDATE Officier_em SET Atk=0,Orders=0,Actif=0,Credits='$CT_MAX',Credits_date='$Date_Actuelle' WHERE ID='$ID'");
					mysqli_close($con);
				}
				else
					SetData("Officier_em","Credits",0,"ID",$ID);
			}
			if($Msg_Off =='')$Msg_Off="Aucun message n'a été posté par votre Etat-Major.";
			if($Objectif)$Objectif_txt='<div class="alert alert-info">Votre Etat-Major se concentre actuellement sur <b>'.$Objectif.'</b></div>';
            if($ID ==1 or $Cdt_Front ==$ID) {
                $Info_txt .= '<b>' . $Esc_Trans . '</b> escadrilles de transport sont actuellement inutilisées<br>';
                $Info_txt .= '<b>' . $Trains . '</b> trains de ravitaillement sont actuellement inutilisés<br>';
                $Info_txt .= '<b>' . $Depot_Gare_out . '</b> dépôts ont leur gare détruite<br>';
            }
            if($Armee_o or $Cdt_Front ==$ID){
                $Info_txt .= '<b>' . $Units_idle . '</b> unités sont actuellement en attente de vos ordres<br>';
                $Info_txt.='Pensez à <a class="lien" href="index.php?view=ground_attrition">vérifier l\'attrition de vos troupes</a>';
            }
            elseif(!$Armee_o){
                $Info_txt.='La nation a besoin essentiellement de commandants d\'armées ou de flottes. Contactez votre commandant de front et <a class="lien" href="index.php?view=em_actus">postulez</a>';
            }
            /*if($Front ==2){
                $Alert_msg='<div class="alert alert-danger"><b>28 Juin 2017 :</b> La portée de ravitaillement terrestre des dépôts situés entre Gabès et Alexandrie a été portée à 400km</div>';
            }*/
            $intro=$Objectif_txt.'<div class="alert alert-warning">'.$Info_txt.'</div>'.$Alert_msg;
			$mes="<table class='table'><thead><tr><th>Communication de l'Etat-Major</th></tr></thead></table>".$Msg_Off."</div>";
			$titre='Bienvenue '.$Nom.' !';
			$img=Afficher_Image('images/transfer_yes'.$country.'.jpg','images/image.png','',50);
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
		$mes='Utilisateur inconnu!';
		$img="<img src='images/tsss.jpg'>";
	}
}
include_once('./index.php');
?>