<?
session_unset();
//session_destroy();
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_txt.inc.php');
if(isset($_POST['pil_id']))
	$pil_id=Insec($_POST['pil_id']);
else
	$pil_id="";
if(empty($pil_id))
{
	$mes="<h1>Erreur d'identification!</h1><a href='index.php?view=login' class='btn btn-default'>Recommencer</a>";
	$img="<img src='images/tsss.jpg'>";
}
else
{
	$AccountID=Insec($_POST['ply_id']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT ID,Nom,Front,Pays,Avancement,Reputation,Engagement,Credits,Credits_date,Unit,Actif,Note FROM Pilote WHERE ID='$pil_id'")
	or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : loginp-p');
	mysqli_close($con);
	while($data=mysqli_fetch_array($result))
	{
		$ID=$data['ID'];
		$Nom=$data['Nom'];
		$Front=$data['Front'];
		$country=$data['Pays'];
		$Unite=$data['Unit'];
		$Engagement=$data['Engagement'];
		$Credits=$data['Credits'];
		$Credits_date=$data['Credits_date'];
		$Avancement=$data['Avancement'];
		$Reputation=$data['Reputation'];
		$Actif=$data['Actif'];
		$Note=$data['Note'];
	}
	if($Actif==1)
	{
		$mes="<h1>Ce personnage est désactivé!</h1>Si cela n'est pas dû à une demande de votre part, veuillez contacter l'administrateur à l'adresse suivante : admin@aubedesaigles.net";
		$img="<img src='images/tsss.jpg'>";
	}
	elseif($ID >0 and $ID==$pil_id)
	{
		RetireCandidat($ID,"login");
		$_SESSION['AccountID']=$AccountID;
		$_SESSION['PlayerID']=$ID;
		$_SESSION['country']=$country;
		$_SESSION['Officier']=false;
		$_SESSION['Officier_em']=false;
		$_SESSION['Pilote_pvp']=false;
		$_SESSION['Officier_pvp']=false;
		$Date_Actuelle=date("Y-m-d");
		$Heure=date("H");
		$ok=true;
		//Variable générales
		$con=dbconnecti();
		$Base=mysqli_result(mysqli_query($con,"SELECT Base FROM Unit WHERE ID='$Unite'"),0);
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result=mysqli_query($con,"SELECT Pays,Latitude,Longitude FROM Lieu WHERE ID='$Base'");
		$Objectif=mysqli_result(mysqli_query($con,"SELECT l.Nom FROM Pays as p,Lieu as l WHERE p.Co_Lieu_Mission=l.ID AND p.Pays_ID='$country' AND p.Front='$Front'"),0);
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
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Pays_base=$data['Pays'];
				$Lat_base=$data['Latitude'];
				$Long_base=$data['Longitude'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($Long_base >=67)
			$Front=3;
		elseif($Lat_base >60 and $Long_base >-50)
			$Front=5;
		elseif($Long_base >13.41 and $Lat_base >=50.5 and $Lat_base <60)
			$Front=4;
		elseif((($Long_base >13.41 and $Lat_base >=43) or ($Long_base >21.5 and $Lat_base >=41.6)) and $Lat_base <=50.5)
			$Front=1;
		elseif(($Pays_base==10 or $Pays_base==24 or $Lat_base <43 or ($Pays_base==6 and $Long_base >9)) and $Long_base >=-10 and $Long_base <67)
			$Front=2;
		else
			$Front=0;
		$_SESSION['Saison']=GetSaison($Date_Campagne);
		//Update des crédits en cas de nouvelle journée
		if($Date_Actuelle >$Credits_date)
		{
			if($Heure >6)
			{
				//Credits Bonus fidélité escadrille
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote SET Front='$Front',Credits_date='$Date_Actuelle',Crashs_Jour=0,Missions_Jour=0,Missions_Max=0,
				Intercept=0,Escorte=0,Couverture=0,Couverture_Nuit=0,enis=0,avion_eni=0,S_Chargeurs=1,S_Purge=0,S_Moteurs=0,S_Camo=0,S_Blindage=0,Heure_Mission=0,Skill_Fav=0 WHERE ID='$ID'");
				mysqli_close($con);
				/*$con=dbconnecti(4);
				$resultm=mysqli_query($con,"SELECT Date FROM Events WHERE PlayerID='$ID' AND Event_Type=31 ORDER BY Date DESC LIMIT 1");
				mysqli_close($con);
				if($resultm)
				{
					$data=mysqli_fetch_array($resultm);
					$Date_Mutation=$data[0];
					if($Date_Mutation)
					{
						$con=dbconnecti();
						$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date_Actuelle','$Date_Mutation')"),0);
						mysqli_close($con);
					}
					else
					{
						$con=dbconnecti();
						$Datediff=mysqli_result(mysqli_query($con,"SELECT DATEDIFF('$Date_Actuelle','$Engagement')"),0);
						mysqli_close($con);
					}
					$Credits=24+floor($Datediff/30);
				}*/
				/*$Credits=24+floor($Note/100);
				if($Credits >40)$Credits=40;*/
				$Credits=24;						
				$Credits_Ori=GetData("Pilote","ID",$ID,"Credits");
				if($Credits_Ori >-1)
				{
					$skills= MoveCredits($ID,99,$Credits);
					SetData("Pilote","Credits",$Credits,"ID",$ID);
					//SetData("Pilote","Free",5,"ID",$ID);
				}
				else
				{
					UpdateCarac($ID,"Endurance",$Credits_Ori);
					UpdateCarac($ID,"Missions_Jour",-$Credits_Ori);
					UpdateCarac($ID,"Reputation",$Credits_Ori*50);
					UpdateCarac($ID,"Avancement",$Credits_Ori*50);
					$skills=MoveCredits($ID,99,$Credits);
				}					
				/*Bonus Ratio
				$Ratio=GetRatio($ID);
				if($Ratio[1] >0 and $Ratio[0] >0)
				{
					$Bonus_Ratio=$Ratio[1]/$Ratio[0];
					if($Bonus_Ratio >$Ratio[1]*10)
						$Bonus_Ratio=$Ratio[1]*10;
					$Bonus_Ratio=floor($Bonus_Ratio/100);
					UpdateCarac($ID,"Reputation",$Bonus_Ratio);
				}*/					
				/*Mise à disposition Dispo_EM
				if($ok and $Avancement >4999 and !$Dispo_EM)
				{
					$mes="<p>Le Grand Etat-Major a un besoin urgent d'officiers compétents et motivés pour servir la cause de la nation.<br>Le choix est offert à chacun de répondre à l'appel ou de l'ignorer.</p>
					<p>Les officiers faisant le choix de se mettre à disposition du grand état-major auront la possibilité de :<br>
					- Postuler à une fonction d'Etat-Major de Front.<br>- Postuler à la fonction de Chef ou d'adjoint d'escadrille.<br>- Bénéficier des avantages du grade de Capitaine (ou supérieur).<br>
					Cette mise à disposition sous-entendra l'obligation pour ce pilote d'accepter tout ordre du GHQ concernant une éventuelle affectation là où la nation aura besoin de lui.</p>
					<p>L'officier faisant le choix de ne pas se mettre à disposition du grand état-major ne pourra plus bénéficier de ces avantages, mais restera maître de ses choix d'affectation futurs.
					<br>Notez que ce choix vaut pour la campagne en cours, s'étalant sur une durée d'environ 1 mois. Chaque mois, la question vous sera à nouveau posée.</p>
					<p>Quel est votre choix ?</p>";			
					$img="<img src='images/serment".$country.".jpg'>";
					$menu.="<form action='choix_dispoem.php' method='post'>
					<table><tr><td><Input type='Radio' name='choix' value='1'>Oui, je désire me mettre à disposition du Grand Etat-Major.</td></tr>
					<tr><td><Input type='Radio' name='choix' value='2'>Non, je préfère rester maitre de mon destin, et je renonce aux avantages associés pour la durée de la campagne en cours.</td></tr></table>
					<input type='Submit' value='VALIDER' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
					$ok=false;
				}*/
			}
		}
		if($ok)
		{
			$con=dbconnecti(3);
			$ok=mysqli_query($con,"SELECT c.*,o.Nom,o.Avancement FROM Chat as c,gnmh_aubedesaiglesnet.Officier_em as o WHERE c.PlayerID=o.ID AND c.Front='$Front' AND c.Pays='$country' ORDER BY c.Date DESC LIMIT 10");
			mysqli_close($con);
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
			if($Msg_Off=="")$Msg_Off="Aucun message n'a été posté par votre Etat-Major.";
			if($Objectif)$Objectif_txt="Votre Etat-Major se concentre actuellement sur <b>".$Objectif."</b></br>";
			$intro=$Objectif_txt.$Msg_Help;
			$menu="<table class='table'><thead><tr><th>Communication de l'Etat-Major</th></tr></thead></table>".$Msg_Off."";
			$titre="Bienvenue ".$Nom." !";
			/*$alerte="<div class='alert alert-danger'>Mise à jour importante!
			<br>Les compétences des pilotes ont été réinitialisées, afin de permettre à chacun de choisir les compétences de son personnage en connaissant un peu mieux le jeu. Nous vous conseillons vivement de mettre votre pilote à jour via <a href='index.php?view=user' class='btn btn-default' title='Profil'>son profil</a>
			<br>N'hésitez pas à venir en discuter sur Mumble ou sur <a href='index.php?view=live_chat' class='lien'>le Chat</a>. Nous vous conseillerons avec plaisir.</p>";*/
			$img=Afficher_Image('images/transfer_yes'.$country.'.jpg',"images/image.png","",50);
			$mes=$alerte; //."<h3><div class='btn btn-primary'><a href='http://cheratte.net/aceofaces/forum/viewtopic.php?f=4&t=3529' target='_blank'>Modifications récentes du jeu</a></div></h3>";
		}
	}
}
usleep(1);
include_once('./index.php');
?>