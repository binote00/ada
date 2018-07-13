<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./jfv_txt.inc.php');
$Pilote_ID=Insec($_GET['Pilote']);
if(isset($_SESSION['AccountID']) and is_numeric($Pilote_ID))
{
		$PlayerID=$_SESSION['PlayerID'];
		$Officier_em=$_SESSION['Officier_em'];
		$country=$_SESSION['country'];
		$con=dbconnecti();
		$Pilote_ID=mysqli_real_escape_string($con,$Pilote_ID);
		$result=mysqli_query($con,"SELECT * FROM Pilote WHERE ID='$Pilote_ID'");
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$Pilote_ID' AND actif=0");
		$resultc=mysqli_query($con,"SELECT Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
		$medals=mysqli_query($con,"SELECT Medal FROM Pil_medals WHERE PlayerID='$Pilote_ID'");
		$Brevet_Pilote=mysqli_result(mysqli_query($con,"SELECT COUNT(ID) FROM Skills_Pil WHERE PlayerID='$Pilote_ID' AND Skill=120"),0);
		mysqli_close($con);
		if($resultc)
		{
			while($datac=mysqli_fetch_array($resultc,MYSQLI_ASSOC))
			{
				$Admin=$datac['Admin'];
			}
			mysqli_free_result($resultc);
		}
		if($results)
		{
			while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
			{
				$Skills_Pil[]=$data['Skill'];
			}
			mysqli_free_result($results);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$ID=$data['ID'];
				$Nom=$data['Nom'];
				$Pays=$data['Pays'];
				$Pays_Ori=$data['Pays'];
				$Unit=$data['Unit'];
				$Engagement=$data['Engagement'];
				$Reputation=$data['Reputation'];
				$Avancement=$data['Avancement'];
				$Renseignement=$data['Renseignement'];
				$Duperie=$data['Duperie'];
				$Avion_Perso=$data['Avion_Perso'];
				$Proto=$data['Proto'];
				$Vic=$data['Victoires'];
				$Vic_atk=$data['Victoires_atk'];
				$Raids=$data['Raids_Bomb'];
				$Raids_Nuit=$data['Raids_Bomb_Nuit'];
				$Dive=$data['Dive'];
				$kreta=$data['kreta'];
				$Photo=$data['Photo'];
				$Photo_Premium=$data['Photo_Premium'];
				$Actif=$data['Actif'];
				$Hide=$data['Hide'];
				$Dispo_Jour=$data['Dispo_Jour'];
				$Dispo_Sauf=$data['Dispo_Sauf'];
				$Dispo_Debut=$data['Dispo_Debut'];
				$Dispo_Fin=$data['Dispo_Fin'];
				$Credits_date=$data['Credits_date'];
				$medal0=$data['medal0'];
			}
			mysqli_free_result($result);
			unset($data);
			if($medals)
			{
				while($datam=mysqli_fetch_array($medals,MYSQLI_ASSOC))
				{
					$medal_txt=GetMedal_Name($Pays_Ori,$datam['Medal']);
					$medals_txt.="<img title='".$medal_txt."' src='images/pmedal".$Pays_Ori.$datam['Medal'].".gif'>";
				}
				mysqli_free_result($medals);
			}
			if(is_array($Skills_Pil))
			{
				foreach($Skills_Pil as $Skill_P)
				{
					if($Skill_P ==120)
						$Skills_txt.="<img src='images/skills/skill".$Skill_P.$Pays_Ori.".png'>";
					else
						$Skills_txt.="<img src='images/skills/skill".$Skill_P.".png'>";
				}
				unset($Skills_Pil);
			}
			$Premium=GetData("Joueur","Pilote_id",$ID,"Premium");
			$con=dbconnecti(4);
			//$Combats=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=1 AND PlayerID='$Pilote_ID'"),0);
			$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=3 AND PlayerID='$Pilote_ID'"),0);
			$Pannes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=4 AND PlayerID='$Pilote_ID'"),0);
			$Blesse=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=9 AND PlayerID='$Pilote_ID'"),0);
			$Perdu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Pertes WHERE Event_Type=34 AND PlayerID='$Pilote_ID'"),0);
			$MIA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events WHERE Event_Type=35 AND PlayerID='$Pilote_ID'"),0);
			mysqli_close($con);			
			$con=dbconnecti();
			$Recce=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce WHERE Joueur='$Pilote_ID'"),0);
			$Escorte=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Escorte WHERE Joueur='$Pilote_ID'"),0);
			$Patrouille=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Patrouille WHERE Joueur='$Pilote_ID'"),0);
			$Sauvetage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sauvetage WHERE PlayerID='$Pilote_ID'"),0);
			$Ravit=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ravitaillements WHERE PlayerID='$Pilote_ID'"),0);
			$Paras=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Parachutages WHERE Joueur='$Pilote_ID'"),0);
			$Vic=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Joueur_win='$Pilote_ID' AND PVP<>1"),0);
			$Probable=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse_Probable WHERE Joueur_win='$Pilote_ID' AND PVP=0"),0);
			$Endommage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse_Probable WHERE Joueur_win='$Pilote_ID' AND PVP=1"),0);
			$Collaboration=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse_Probable WHERE Joueur_win='$Pilote_ID' AND PVP=2"),0);
			$Defaites=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Pilote_loss='$Pilote_ID' AND PVP IN (1,2)"),0);
			$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Joueur='$Pilote_ID'"),0);
			$Heures=round(mysqli_result(mysqli_query($con,"SELECT SUM(Pilotage) FROM XP_Avions WHERE PlayerID='$Pilote_ID'"),0));
			if($Heures)
				$XP=mysqli_result(mysqli_query($con,"SELECT AvionID FROM XP_Avions WHERE PlayerID='$Pilote_ID' ORDER BY Pilotage DESC LIMIT 1"),0);
			//Victoires_Probables
			$Atk_res=mysqli_query($con,"SELECT COUNT(*) FROM Attaque WHERE Joueur='$Pilote_ID' AND Type >0 GROUP BY Lieu,Date");
			if(mysqli_num_rows($Atk_res))
				$Atk=mysqli_result($Atk_res,0);
			else
				$Atk=0;
			mysqli_close($con);			
			$Grade=GetAvancement($Avancement,$Pays_Ori);
			$Grade_img='images/grades/grades'.$Pays_Ori.$Grade[1].'.png';
			if(!is_file($Grade_img))
				$Grade_img='images/grades/grades'.$Pays_Ori.$Grade[1].'.gif';
			if(!is_file($Grade_img))
				$Grade_img='images/grades/grades'.$Pays_Ori.$Grade[1].'.jpg';
			$Unite=GetData("Unit","ID",$Unit,"Nom");			
			$Missions=$Recce + $Escorte + $Patrouille + $Raids + $Raids_Nuit + $Sauvetage + $Dive + $Ravit + $Paras + $Atk;
			/*if($Abattu2 >$Abattu)$Abattu=$Abattu2;*/
			if($Abattu or $Perdu)
				$Rating_combat=round($Combats/($Abattu+$Perdu),2);
			if($MIA)
				$Rating_mia=$Missions/$MIA;
			//Horaires
			if($Dispo_Jour =="tous")
				$Dispos="Tous les jours";
			else
			{
				if($Dispo_Jour == "we")
					$Dispos="Week-end";
				elseif($Dispo_Jour == "sem")
					$Dispos="Semaine";
				elseif($Dispo_Jour == "lu")
					$Dispos="Lundi";
				elseif($Dispo_Jour == "ma")
					$Dispos="Mardi";
				elseif($Dispo_Jour == "me")
					$Dispos="Mercredi";
				elseif($Dispo_Jour == "je")
					$Dispos="Jeudi";
				elseif($Dispo_Jour == "ve")
					$Dispos="Vendredi";
				elseif($Dispo_Jour == "sa")
					$Dispos="Samedi";
				elseif($Dispo_Jour == "di")
					$Dispos="Dimanche";
			}
			if($Dispo_Sauf != "aucun")
			{
				if($Dispo_Sauf == "lu")
					$Dispos.=" sauf Lundi";
				elseif($Dispo_Sauf == "ma")
					$Dispos.=" sauf Mardi";
				elseif($Dispo_Sauf == "me")
					$Dispos.=" sauf Mercredi";
				elseif($Dispo_Sauf == "je")
					$Dispos.=" sauf Jeudi";
				elseif($Dispo_Sauf == "ve")
					$Dispos.=" sauf Vendredi";
				elseif($Dispo_Sauf == "sa")
					$Dispos.=" sauf Samedi";
				elseif($Dispo_Sauf == "di")
					$Dispos.=" sauf Dimanche";
			}
			$Dispos.='<br>de '.$Dispo_Debut.'h à '.$Dispo_Fin.'h';			
			if(!$Hide or $PlayerID ==$Pilote_ID or $PlayerID ==1)
			{
				if(($Pays ==$country and $Renseignement >100) or ($Renseignement > $Duperie and $Renseignement >100) or $PlayerID ==$Pilote_ID or $PlayerID ==1)
				{
					//Journal Mutations
					$con=dbconnecti(4);
					$resultj=mysqli_query($con,"SELECT Date,Lieu,Unit,Avion_Nbr FROM Events WHERE Event_Type=31 AND PlayerID='$Pilote_ID' ORDER BY ID DESC LIMIT 50");
					mysqli_close($con);
					if($resultj)
					{
						while($Classement=mysqli_fetch_array($resultj,MYSQLI_ASSOC)) 
						{
							$Event_Date=substr($Classement['Date'],0,16);
							$Event_Lieu=$Classement['Lieu'];
							$Event_Avion_Nbr=$Classement['Avion_Nbr'];
							$Event_Unit_Nom=GetData("Unit","ID",$Classement['Unit'],"Nom");
							$Event_Lieu_Nom=GetData("Lieu","ID",$Event_Lieu,"Nom");
							$Event_Unite_Dest_Nom=GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
							if($Unite ==$Event_Avion_Nbr)
								$Event.=$Event_Date.' : '.$Nom.' a été transféré du '.$Event_Unit_Nom.' vers le '.$Event_Unite_Dest_Nom.', basé à '.$Event_Lieu_Nom.'<br>';
							else
								$Event.=$Event_Date.' : '.$Nom.' a été transféré du <b>'.$Event_Unite_Dest_Nom.'</b> vers le <b>'.$Event_Unit_Nom.'</b>, basé à '.$Event_Lieu_Nom.'<br>';
						}
						mysqli_free_result($resultj);
						unset($Classement);
					}
				}
				else
					$Event="Vous ne parvenez pas à en trouver trace dans les dossiers à votre disposition";
			}
			elseif($Renseignement >100)
				$Event="Ce dossier a été maquillé, de toute évidence!";
			else
			{
				$date=date('Y-m-d');
				$bluff=mt_rand (0,1000);
				$Lieu_bluff=GetData("Lieu","ID",$bluff,"Nom");
				$Event=$date.' : '.$Nom.' a été transféré au '.$Unite.', basé à '.$Lieu_bluff.'<br>';
			}			
			if($Premium and $Photo_Premium ==1)
				$Photo="<img src='uploads/Pilote/".$Pilote_ID."_photo.jpg'>";
			/*elseif($Premium)
			{
				$Photo="<a href='upload_img.php'><img src='images/persos/pilote".$Pays_Ori.$Photo.".jpg' title='Changer la photo de profil'></a>";
			}*/
			else
				$Photo="<img src='images/persos/pilote".$Pays_Ori.$Photo.".jpg'>";
			if($Admin)
			{
				if($Second_P)
					$Nom.="<br>(".GetData("Joueur","ID",$Second_P,"Nom").")";
				$Engagement.="<br>(".$Credits_date.")";
			}				
			$Ratio=GetRatio($ID,$Missions);
			$Perdus=$Perdu+$Abattu;
			$mes="<h1>".$Nom."</h1><div id='col_gauche'>
				<table class='table'>
				<tr><td rowspan='3'>".$Photo."</td><td>".Afficher_Icone($Unit,$Pays,$Unite,1)."</td></tr>
				<tr><th>Engagement</th></tr>
				<tr><td>";
				if($Actif)$mes.='Retraité'; else $mes.=$Engagement;
			$mes.="</td></tr>
				<tr><td align='center'><img src='".$Grade_img."' title='".$Grade[0]."'></td>";
			if($Premium)
			{
				if($Admin)
					$prem="<th><img src='images/premium.png' title='Premium'>".$Premium_date."</th>";
				else
					$prem="<th><img src='images/premium.png' title='Premium'></th>";
			}
			/*if(!$Actif and $Pays ==$country and $PlayerID !=$ID)
				$mes.="</tr><tr><th>Service Postal<br><a href='msg.php?dest=".$ID."'><img src='images/icone_courrier.gif' alt='Service Postal' title='Service Postal'></a></th>".$prem."</tr>";
			else*/
				$mes.=$prem."<tr>";
			$mes.="</table></div>";
			$mes.="<div id='col_gauche'><table class='table table-striped'>
			<thead><tr><th>Statistiques</th></tr></thead>
			<tr><th align='left'><a href='victoires.php?pilote=".$ID."' target='_blank'>Victoires confirmées</a></th><td width='120px' align='right' title='Nombre divisé par 10 pour plus de réalisme'>".round($Vic/10,1)."</td><td></td><th align='left'>Heures de Vol</th><td width='120px' align='right'>".$Heures."</td></tr>
			<tr><th align='left'>Victoires probables</th><td width='120px' align='right' title='Nombre divisé par 10 pour plus de réalisme'>".floor($Probable/10)."</td><td></td><th align='left'>Missions accomplies</th><td width='120px' align='right'>".$Missions."</td></tr>
			<tr><th align='left'>Victoires partagées</th><td width='120px' align='right' title='Nombre divisé par 10 pour plus de réalisme'>".floor($Collaboration/10)."</td><td></td><th align='left'>Combats aériens</th><td width='120px' align='right'>".$Combats."</td></tr>
			<tr><th align='left'>Ennemis endommagés</th><td width='120px' align='right' title='Nombre divisé par 10 pour plus de réalisme'>".floor($Endommage/10)."</td><td></td><th align='left'>Avions perdus</th><td width='120px' align='right'>".$Perdus."</td></tr>
			<tr><th align='left'><a href='attaques.php?pilote=".$ID."' target='_blank'>Destructions</a></th><td width='120px' align='right'>".$Vic_atk."</td><td></td><th align='left'><a href='loose.php?pilote=".$ID."' target='_blank'>Abattu en vol</a></th><td width='120px' align='right'>".$Defaites."</td></tr>
			<tr><th align='left'>Ratio Missions</th><td width='120px' align='right' title='Plus bas=meilleur'>".$Ratio[0]."</td><td></td><th align='left'><a href='dca.php?pilote=".$ID."' target='_blank'>Abattu par la DCA</a></th><td width='120px' align='right'>".$DCA."</td></tr>
			<tr><th align='left'>Ratio Combat</th><td width='120px' align='right' title='Plus haut=meilleur'>".$Rating_combat."</td><td></td><th align='left'>MIA</th><td width='120px' align='right'>".$MIA."</td></tr>
			<tr><th align='left'>Pannes</th><td width='120px' align='right'>".$Pannes."</td><td></td><th align='left'>Blessé</th><td width='120px' align='right'>".$Blesse."</td></tr>
			<tr><td colspan='5'><hr></td></tr>";
			if($Avion_Perso)
			{
				$Avion_Pays=GetData('Avions_Persos','ID',$Avion_Perso,'Pays');
				$Avion_ID=GetData('Avions_Persos','ID',$Avion_Perso,'ID_ref');
				$mes.="<tr><th align='left'>Avion personnel</th><td colspan='3'>".GetAvionIcon($Avion_ID,$Avion_Pays,$ID)."</tr>";
			}
			if($Proto)
			{
				$Proto=GetData('Avions_Persos','ID',$Proto,'ID_ref');
				$mes.="<tr><th align='left'>Prototype</th><td colspan='3'>".GetAvionIcon($Proto,$Pays)."</tr>";
			}
			if($XP)
				$mes.="<tr><th align='left'>Avion favori</th><td colspan='3'>".GetAvionIcon($XP,$Pays)."</tr>";
			$mes.="</table></div><div id='profil_decorations'><h2>Compétences</h2>".$Skills_txt."<h2>Brevets et Décorations</h2>";
				/*for($i=0;$i<=18;$i++)
				{
					$medal_txt=GetMedal_Name($Pays_Ori,$i);
					$medal='medal'.$i;
					if($$medal >0)
					{
						$u++;
						if($u ==7 or $u ==13)
							$mes.="<tr>";
							
						if($i ==14 and $Pays_Ori ==1)
							$mes.="<img title='".$medal_txt."' src='images/pmedal".$Pays_Ori.$i."_".$medal14.".gif'>";
						elseif($i ==15 and $Pays_Ori ==1)
							$mes.="<img title='".$medal_txt."' src='images/pmedal".$Pays_Ori.$i."_".$medal15.".gif'>";
						else				
							$mes.="<img title='".$medal_txt."' src='images/pmedal".$Pays_Ori.$i.".gif'>";						
					}
				}*/
				if($Brevet_Pilote)
					$mes.="<img src='images/pmedal".$Pays_Ori."0.gif'>";
				$mes.=$medals_txt;
				if($Event)
					$mes.="</div><h2>Mutations</h2><p>".$Event."</p>";
			include_once('./default_blank.php');	
		}
		else
		{
			echo "<h1>Un problème est survenu lors de la récupération des données de votre profil</h1>";
			echo "<h2>Si le problème persiste, contactez un administrateur via le forum</h2>";
		}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>