<?php
if($_SESSION['AccountID'])
{
	include_once '../jfv_include.inc.php';
	include_once '../jfv_txt.inc.php';
	include_once '../jfv_inc_em.php';
	/*Type : 1=Bataille, 2=Bombardement, 40=Occupation, 41=Mouvement, 21=Renfort, 50=Nouvel Avion, 51=Nouvelle Unité
	function Echo_Event($Date,$Type,$Lieu,$Pays,$Unit=0,$avion=0,$avion_Nbr=0)
	{
		switch($Type)
		{
			case 40://Occupation
				$mes.="<p><img src='images/zone7.jpg' alt='ville'> Ce soir, la ville de <b>".GetData("Lieu","ID",$Lieu,"Nom")."</b> passera sous contrôle des troupes ".Pluriel(GetData("Pays","ID",$Pays,"adj"))." <img src='images/".$Pays."20.gif'></p>";
			break;
			case 42://Capitulation
				$mes.="<p><img src='images/".$Pays."20.gif' alt='avion'> <b>".GetData("Pays","ID",$Pays,"Nom")."</b> a capitulé. Leurs anciennes possessions territoriales passeront sous le contrôle de leurs ennemis dès demain.</p>";
			break;
			case 43://Alliance
				if($avion_Nbr ==1)
					$faction="Axe";
				elseif($avion_Nbr ==2)
					$faction="Alliés";
				else
					$faction="Neutre";
				$mes.="<p><img src='images/".$Pays."20.gif' alt='avion'> <b>".GetData("Pays","ID",$Pays,"Nom")."</b> a rejoint la faction <b>".$faction."</b></p>";
			break;
			/*case 50://Nouvel Avion
				$mes.="<p>Un nouveau modèle d'avion est entré en activité :
				<br>le <b>".GetData("Avion","ID",$avion,"Nom")."</b> <img src='images/avions/avion".$avion.".gif' alt='avion'></p>";
			break;*/
			/*case 51://Nouvelle Unité
				$mes.="<p>Une nouvelle unité est apparue sur le champ de bataille :
				<br>le <b>".GetData("Unit","ID",$Unit,"Nom")."</b> <img src='images/unit/unit".$Unit."p.gif' alt='unité'></p>";
			break;*/
			/*case 60://Victoire PNJ
				//mail('binote@hotmail.com','Aube des Aigles: Do_Event Nouvelle Victoire '.$Date,'As '.$avion.' a abattu '.$avion_Nbr.' avions ennemis ce jour');
			break;
			case 61://Promotion PNJ
				//SetData("Pilote_IA","Avancement",$Lieu,"ID",$avion);
				//mail('binote@hotmail.com','Aube des Aigles: Do_Event Nouvelle Promotion '.$Date,'As '.$avion.' a été promu au grade supérieur ce jour');
			break;
			case 62:/*Reput PNJ
				SetData("Pilote_IA","Reputation",$Lieu,"ID",$avion);
				mail('binote@hotmail.com','Aube des Aigles: Do_Event Nouvelle Fonction '.$Date,'As '.$avion.' a été promu à une fonction supérieure ce jour');
			break;
			case 64:Blesse PNJ
				SetData("Pilote_IA","Actif",0,"ID",$avion);
				mail('binote@hotmail.com','Aube des Aigles: Do_Event As Blessé '.$Date,'As '.$avion.' a été blessé ce jour');
			break;*/
			/*case 65://Mort PNJ
				$mes.="<p><img src='images/as.jpg' alt='as'> L'as <b>".GetData("Pilote_IA","ID",$avion,"Nom")."</b> a été tué au combat.</p>";
			break;
		}
		return $mes;
	}*/
	$today=getdate();
	$con=dbconnecti(4);
	$result=mysqli_query($con,"SELECT Event_Type,Avion,Lieu,Avion_Nbr,PlayerID,DATE_FORMAT(`Date`,'%e') as Jour,DATE_FORMAT(`Date`,'%Hh%i') as Heure FROM Events_Feed WHERE Event_Type IN (44,116,118,119,200,201,202,205,206,320,321,432) AND (DATEDIFF(NOW(),`Date`)<2) ORDER BY ID DESC"); //DATE(Date)=DATE(NOW())");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Front_Lieu=GetFrontByCoord($data['Lieu']);
			if($Admin or $Front_Lieu ==$Front or $Front ==99)
			{
				if(($data['Jour'] <$today['mday']) or ($data['Jour'] >27 and $today['mday'] ==1))
					$datep='<i>Hier</i> à ';
				else
					$datep='';
				if($data['Event_Type'] ==44 and !$data['PlayerID'])
					$mes.="<p>".$datep.$data['Heure']." <img src='images/zone7.jpg' alt='ville'> La ville de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b> a été revendiquée par les troupes ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"),true)." <img src='images/".$data['Avion']."20.gif'></p>";
				elseif($data['Event_Type'] ==116)
					$mes.="<p>".$datep.$data['Heure']." Des troupes ont débarqué dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==118)
					$mes.="<p>".$datep.$data['Heure']." Une unité a débarqué dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==119)
					$mes.="<p>".$datep.$data['Heure']." Une unité a été parachutée dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==200)
				{
					if($data['PlayerID'] >0)
						$mes.="<p>".$datep.$data['Heure']." Des troupes ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"),true)." <img src='images/".$data['Avion']."20.gif'> ont été repérées faisant mouvement dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
					else
						$mes.="<p>".$datep.$data['Heure']." Des unités ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"),true)." <img src='images/".$data['Avion']."20.gif'> ont été repérées faisant mouvement dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				}
				elseif($data['Event_Type'] ==201)
					$mes.="<p>".$datep.$data['Heure']." Des navires ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"))." <img src='images/".$data['Avion']."20.gif'> ont été repérés faisant mouvement au large de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==202)
					$mes.="<p>".$datep.$data['Heure']." Des navires ".Pluriel(GetData("Pays","ID",$data['Avion'],"adj"))." <img src='images/".$data['Avion']."20.gif'> ont été repérés faisant mouvement au large de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==205 or $data['Event_Type'] ==206)
					$mes.="<p>".$datep.$data['Heure']." <img src='images/zone7.jpg'> La ville de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b> a été bombardée</p>";
				elseif($data['Event_Type'] ==320)
					$mes.="<p>".$datep.$data['Heure']." Un convoi commandé par ".GetData("Officier","ID",$data['PlayerID'],"Nom")." a débarqué du matériel dans le port de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==321)
					$mes.="<p>".$datep.$data['Heure']." Un convoi a débarqué du matériel dans le port de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				elseif($data['Event_Type'] ==432)
					$mes.="<p>".$datep.$data['Heure']." Des troupes ".Pluriel(GetData("Pays","ID",$data['Avion_Nbr'],"adj"),true)." <img src='images/".$data['Avion_Nbr']."20.gif'> ont été repérées débarquant sur les plages de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
				$Date_ref=$data['Date'];
			}
		}
		mysqli_free_result($result);
	}
	$con=dbconnecti();
	$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
	//$result=mysqli_query($con,"SELECT DISTINCT `Date`,Type,Lieu,Pays,Unite,Avion,Avion_Nbr FROM Event_Historique WHERE Type IN (42,43,51,65) AND `Date`='$Date_Campagne' ORDER BY Type ASC"); //(40,42,43,51,65)
	$result1=mysqli_query($con,"SELECT Lieu,DATE_FORMAT(`Date`,'%Hh%i') as Heure FROM Parachutages WHERE DATE(`Date`)=DATE(NOW())");
	$result2=mysqli_query($con,"SELECT ID,Nom FROM Cible WHERE Unit_ok=1 AND `Date`='$Date_Campagne'");
	$result3=mysqli_query($con,"SELECT ID,Nom FROM Avion WHERE Engagement='$Date_Campagne'");
	mysqli_close($con);
	if($result1)
	{
		while($data=mysqli_fetch_array($result1,MYSQLI_ASSOC))
		{
			$mes.="<p>".$data['Heure']." Des parachutistes ont été largués dans les environs de <b>".GetData("Lieu","ID",$data['Lieu'],"Nom")."</b></p>";
		}
		mysqli_free_result($result1);
	}
	/*if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$mes.=Echo_Event($data['Date'],$data['Type'],$data['Lieu'],$data['Pays'],$data['Unite'],$data['Avion'],$data['Avion_Nbr']);
		}
		mysqli_free_result($result);
	}*/
	if($result3)
	{
		while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
		{
			$mes.="<p>Un nouveau modèle d'avion est entré en service :
			<br>le <b>".$data3['Nom']."</b> <a href='avion.php?avion=".$data3['ID']."' target='_blank' rel='noreferrer'><img src='images/avions/avion".$data3['ID'].".gif' alt='avion'></a></p>";
		}
		mysqli_free_result($result3);
	}
	if($result2)
	{
		while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$mes.="<p>Un nouveau modèle de véhicule est entré en service :
			<br>le <b>".$data2['Nom']."</b> <a href='cible.php?cible=".$data2['ID']."' target='_blank' rel='noreferrer'><img src='images/vehicules/vehicule".$data2['ID'].".gif' alt='véhicule'></a></p>";
		}
		mysqli_free_result($result2);
	}
	if(!$mes)$mes='Aucune nouvelle ce jour sur ce front.';
	if($Front ==3)
		$Front_txt='Pacifique';
	elseif($Front ==2)
		$Front_txt='Méditerranée';
	elseif($Front ==1)
		$Front_txt='Est';
	elseif($Front ==4)
		$Front_txt='Nord';
	elseif($Front ==5)
		$Front_txt='Arctique';
	elseif($Front ==12)
		$Front_txt='Réserve';
	elseif($Front ==99)
		$Front_txt='Planification Stratégique';
	elseif($Front ==0)
		$Front_txt='Ouest';
	else
		$Front_txt='Erreur';
}
echo "<h1>Nouvelles du Front ".$Front_txt."</h1>
<div class='row'><div class='col-md-6'><img src='images/infos_front.jpg' alt='nouvelles du front' style='width:100%;'></div><div class='col-md-6'>".$mes."</div></div>";