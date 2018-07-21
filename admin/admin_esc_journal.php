<?
require_once('./jfv_inc_sessions.php');
if($_SESSION['AccountID'] ==1)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Unite=Insec($_POST['unit']);
	if($Unite)
	{
	//Journal d'unité
		$country=2;
		$con=dbconnecti();
		$resultu=mysqli_query($con,"SELECT Nom,Base FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($resultu)
		{
			while($data=mysqli_fetch_array($resultu,MYSQLI_ASSOC)) 
			{
				$Unite_Nom=$data['Nom'];
				$Unite_Base=$data['Base'];
			}
			mysqli_free_result($resultu);
			unset($data);
		}		
		if($Tab =="attaques")
		{
			$in="23,24,28";
			$in_base="72,73,75,76,77,114,142,272,273";
			$query="(SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events WHERE Unit='$Unite' AND Event_Type IN (".$in.") AND `Date` >'2014-10-01') 
			UNION ALL (SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Lieu='$Unite_Base' AND Event_Type IN (".$in_base.") AND `Date` >'2014-10-01')
			UNION ALL (SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Pilote_eni='$Unite' AND Event_Type IN(222,223,224)) ORDER BY ID DESC LIMIT 100";
		}
		elseif($Tab =="missions")
		{
			$in="3,4,6,7,10,11,12,13,14,15,16,17,18,19,24,26,27,29,34,35,36";
			$in_em="70,71,79,80,81,82,83,84,85,86,87,88,89,90,95,96,97,120,121,122,123,124,179,180,181,188,189,191,192";
			$query="(SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events WHERE Unit='$Unite' AND Event_Type IN (".$in.") AND `Date` >'2014-10-01') 
			UNION ALL (SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Unit='$Unite' AND Event_Type IN (".$in_em.") AND `Date` >'2014-10-01')";
		}
		elseif($Tab =="pilotes")
		{
			$in="3,4,5,9,11,12,30,31,32,33,34";
			$in_em="90,96,97,179,192";
			$query="(SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events WHERE Unit='$Unite' AND Event_Type IN (".$in.") AND `Date` >'2014-10-01') 
			UNION ALL (SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Unit='$Unite' AND Event_Type IN (".$in_em.") AND `Date` >'2014-10-01')";
		}
		elseif($Tab =="ravit")
		{
			$in_em="101,102,103,104,105,106,107,111,112,113,114,137,139,141"; //25
			$in_base="72,73,272,273";
			$query="(SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Unit='$Unite' AND Event_Type IN (".$in_em.") AND `Date` >'2014-10-01') 
			UNION ALL (SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Lieu='$Unite_Base' AND Event_Type IN (".$in_base.") AND `Date` >'2014-10-01')";
		}
		else
		{
			$in="3,18,19,23,24,28,30,31,32,33,34,35,36,41";
			$in_em="101,102,103,104,105,106,107,111,112,113,114,137,139,141,192";
			$in_base="72,73,75,76,77,142,272,273";
			$query="(SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events WHERE Unit='$Unite' AND Event_Type IN (".$in.") AND `Date` >'2014-10-01') 
			UNION ALL (SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Unit='$Unite' AND Event_Type IN (".$in_em.") AND `Date` >'2014-10-01')
			UNION ALL (SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Lieu='$Unite_Base' AND Event_Type IN (".$in_base.") AND `Date` >'2014-10-01')
			UNION ALL (SELECT ID,Event_Type,`Date`,Lieu,PlayerID,Avion,Avion_Nbr,Pilote_eni FROM Events_em WHERE Pilote_eni='$Unite' AND Event_Type IN(222,223,224)) ORDER BY ID DESC LIMIT 100";
		}
		$con=dbconnecti(4);
		$msc=microtime(true);
		$result=mysqli_query($con,$query);
		$msc=microtime(true)-$msc;
		mysqli_close($con);
		if($result)
		{
			while($Classement=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
			{
				$Event_Type_txt="";
				$Event_Type=$Classement['Event_Type'];
				$Event_Date=substr($Classement['Date'],0,16);
				$Event_Lieu=$Classement['Lieu'];
				$Event_PlayerID=$Classement['PlayerID'];
				$Event_Avion=$Classement['Avion'];
				$Event_Avion_Nbr=$Classement['Avion_Nbr'];
				$Event_Pilote_eni=$Classement['Pilote_eni'];				
				$Event_Lieu_Nom=GetData("Lieu","ID",$Event_Lieu,"Nom");
				if($Event_Type == 222)
				{
					$Event_Pilote_Nom=GetData("Officier","ID",$Event_PlayerID,"Nom");
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
				}
				elseif($Event_Type > 200)
				{
					$Event_Pilote_Nom=GetData("Officier","ID",$Event_PlayerID,"Nom");
					$Event_Avion_Nom=GetData("Cible","ID",$Event_Avion,"Nom");
				}
				else
				{
					$Event_Pilote_Nom=GetData("Pilote","ID",$Event_PlayerID,"Nom");
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event_Avion_eni_Nom=GetData("Avion","ID",$Event_Avion_Nbr,"Nom");
				}
				switch($Event_Type)
				{
					case 1:
						$Event_Type_txt="";
					break;
					case 3:
						$Event_Type_txt="a été abattu. Un <b>".$Event_Avion_Nom."</b> a été perdu.";
					break;
					case 4:
						$Event_Type_txt="est tombé en panne d'essence.";
					break;
					case 5:
						$Event_Type_txt="a attaqué une cible au sol.";
					break;
					case 6:
						$Event_Type_txt="a bombardé un objectif.";
					break;
					case 7:
						$Event_Type_txt="a effectué une mission de reconnaissance.";
					break;
					case 9:
						$Event_Type_txt="a été blessé.";
					break;
					case 10:
						$Event_Type_txt="a attaqué un navire.";
					break;
					case 11:
						if($Event_Avion_Nbr ==1)
							$Event_Type_txt="s'est crashé au décollage. Un <b>".$Event_Avion_Nom."</b> a été perdu.";
						else
							$Event_Type_txt="s'est crashé au décollage.";
					break;
					case 12:
						if($Event_Avion_Nbr ==1)
							$Event_Type_txt="s'est crashé à l'atterrissage. Un <b>".$Event_Avion_Nom."</b> a été perdu.";
						else
							$Event_Type_txt="s'est crashé à l'atterrissage.";
					break;
					case 13:
						$Event_Type_txt="a endommagé les défenses anti-aériennes.";
					break;
					case 14:
						$Event_Type_txt="a détruit un hangar.";
					break;
					case 15:
						$Event_Type_txt="a endommagé la gare.";
					break;
					case 16:
						$Event_Type_txt="a endommagé une usine.";
					break;
					case 17:
						$Event_Type_txt="a détruit un pont à ".$Event_Lieu_Nom;
					break;
					case 18:
						if($Event_Avion_Nbr >4)
							$aa_type="de gros calibre";
						elseif($Event_Avion_Nbr >2)
							$aa_type="de calibre moyen";
						elseif($Event_Avion_Nbr >0)
							$aa_type="de faible calibre";
						else
							$aa_type="inexistante";
						$Event_Type_txt="a repéré une DCA ".$aa_type;
					break;
					case 19:
						$Unite_eni_Nom=GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
						$Event_Type_txt="a repéré la base du ".$Unite_eni_Nom;
					break;
					case 21:
						if($Event_Avion_Nbr >1)
							$Event.=$Event_Date." : <b>".$Event_Avion_Nbr." ".$Event_Avion_Nom."</b> sont arrivés en renfort sur l'aérodrome de ".$Event_Lieu_Nom."<br>";
						else
							$Event.=$Event_Date." : <b>".$Event_Avion_Nbr." ".$Event_Avion_Nom."</b> est arrivé en renfort sur l'aérodrome de ".$Event_Lieu_Nom."<br>";
					break;
					case 22:
						if($Classement['Unit'] !=$Unite)
							$Event.=$Event_Date." : Un <i>".$Event_Avion_Nom."</i> a été détruit par l'ennemi sur notre aérodrome de ".$Event_Lieu_Nom."<br>";
						else
							$Event.=$Event_Date." : ".$Event_Pilote_Nom.", a détruit un <i>".$Event_Avion_Nom."</i> sur l'aérodrome de ".$Event_Lieu_Nom."<br>";
					break;
					case 23:
						$Event.=$Event_Date." : <b>Un ".$Event_Avion_Nom." a détruit un canon anti-aérien sur notre base de ".$Event_Lieu_Nom."</b><br>";
					break;
					case 24:
						$Event.=$Event_Date." : <b>Un ".$Event_Avion_Nom." a détruit un hangar sur notre base de ".$Event_Lieu_Nom.". Nos stocks de munitions et d'essence ont été réduits !</b><br>";
					break;
					case 25:
						$Event.=$Event_Date." : <i>Notre unité a reçu du ravitaillement en essence et en munitions</i>.<br>";
					break;
					case 27:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom.", pilotant un ".$Event_Avion_Nom.", a endommagé la piste de la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 28:
						$Event.=$Event_Date." : <b>Un ".$Event_Avion_Nom." a endommagé la piste de notre base de ".$Event_Lieu_Nom."</b><br>";
					break;
					case 29:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom.", pilotant un ".$Event_Avion_Nom.", a endommagé les infrastructures portuaires de ".$Event_Lieu_Nom."<br>";
					break;
					case 30:
						$Pays_medal=GetData("Joueur","ID",$Event_PlayerID,"Pays_Origine");
						$Medal_Name=GetMedal_Name($Pays_medal, $Event_Avion_Nbr);
						if($Event_Avion_Nbr)
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été décoré de la <b>".$Medal_Name."</b>.<br>";
						else
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a reçu son ".$Medal_Name.".<br>";
					break;
					case 31:
						$Event_Unite_Dest_Nom=GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
						if($Unite == $Event_Avion_Nbr)
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été transféré du ".$Unite_Nom." vers le ".$Event_Unite_Dest_Nom.", basé à ".$Event_Lieu_Nom."<br>";
						else
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été transféré du ".$Event_Unite_Dest_Nom." vers le ".$Unite_Nom.", basé à ".$Event_Lieu_Nom."<br>";
					break;
					case 32:
						$Grade=GetAvancement(0,$country,$Event_Avion_Nbr);
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été promu au grade de <b>".$Grade[0]."</b>.<br>";
					break;
					case 33:
						$Event_Avion_Nom=GetData("Pilote","ID",$Event_Avion,"Nom");
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a participé à une formation donnée par ".$Event_Avion_Nom." où il a progressé d'environ <b>".$Event_Avion_Nbr."</b>.<br>";
					break;
					case 34:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a perdu le contrôle de son appareil suite à un incident. Un <b>".$Event_Avion_Nom."</b> a été perdu.<br>";
					break;
					case 35:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." est déclaré <b>disparu</b> à bord de son ".$Event_Avion_Nom.", dans la région de ".$Event_Lieu_Nom."<br>";
					break;
					case 36:
						if(!$Event_Avion_Nbr)
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été ramené sain et sauf à bord d'un ".$Event_Avion_Nom."<br>";
						else
						{
							$Event_Rescued_Nom=GetData("Pilote","ID",$Event_Avion_Nbr,"Nom");
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a ramené ".$Event_Rescued_Nom." sain et sauf à bord de son <b>".$Event_Avion_Nom."</b><br>";
						}
					break;
					case 37:
						$Event.=$Event_Date." : ".$Event_Avion_Nbr." ".$Event_Avion_Nom." ont été transférés du Flight ".$Event_PlayerID." au Flight ".$Event_Lieu."<br>";
					break;
					case 38:
						$Pilote_eni=GetData("Lieu","ID",$Event_Pilote_eni,"Nom");
						if(!$Event_Avion_Nbr)
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a décollé aux commandes de son avion personnel, un ".$Event_Avion_Nom.", depuis notre base de ".$Event_Lieu_Nom;
						else
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a décollé aux commandes d'un ".$Event_Avion_Nom." depuis notre base de ".$Event_Lieu_Nom;
						if($Pilote_eni)
							$Event.=", en direction de ".$Pilote_eni."<br>";
						else
							$Event.="<br>";
					break;
					case 39:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a commandé <b>".$Event_Avion_Nbr." ".$Event_Avion_Nom."</b>, arrivés sur notre base de ".$Event_Lieu_Nom."<br>";
					break;
					case 41:
						$Event.=$Event_Date." : <b>Notre unité a fait mouvement vers la base de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 72:
						$Pilote_eni=GetData("Lieu","ID",$Event_Pilote_eni,"Nom");
						$Event.=$Event_Date." : Le ravitaillement en carburant de notre base de ".$Event_Lieu_Nom." a été diminué de <b>".$Event_Avion_Nbr."%</b> suite à une attaque de ".$Event_Pilote_Nom." à bord d'un ".$Event_Avion_Nom." dans les environs de ".$Pilote_eni."<br>";
					break;
					case 73:
						$Pilote_eni=GetData("Lieu","ID",$Event_Pilote_eni,"Nom");
						$Event.=$Event_Date." : Le ravitaillement en munitions de notre base de ".$Event_Lieu_Nom." a été diminué de <b>".$Event_Avion_Nbr."%</b> suite à une attaque de ".$Event_Pilote_Nom." à bord d'un ".$Event_Avion_Nom." dans les environs de ".$Pilote_eni."<br>";
					break;
					case 76:
						$Event.=$Event_Date." : La batterie de DCA <b>".GetData("Armes","ID",$Event_Pilote_eni,"Nom")."</b> a tiré <b>".$Event_Avion_Nbr." obus</b> suite à une attaque de ".$Event_Pilote_Nom." à bord d'un ".$Event_Avion_Nom." sur notre base de ".$Event_Lieu_Nom."<br>";
					break;
					case 80:
						if($Event_Pilote_eni)
						{
							$Pilote_eni=GetData("Pilote","ID",$Event_Pilote_eni,"Nom");
							$Event.=$Event_Date." : ".$Pilote_eni." a été abattu par ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." lors d'une escorte dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
						}
						else
							$Event.=$Event_Date." : Un de nos avions escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été abattu par un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 81:
						if($Event_Pilote_eni)
						{
							$Pilote_eni=GetData("Pilote","ID",$Event_Pilote_eni,"Nom");
							if($Pilote_eni == $Event_Pilote_Nom)
								$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été abattu à bord de son ".$Event_Avion_eni_Nom." lors d'une patrouille dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
							else
								$Event.=$Event_Date." : ".$Pilote_eni." a été abattu par un ".$Event_Avion_Nom." lors d'une patrouille dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
						}
						else
							$Event.=$Event_Date." : Une de nos patrouilles escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été abattue par un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 82:
						$Event.=$Event_Date." : Un de nos avions escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a abattu un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 83:
						$Event.=$Event_Date." : Une de nos patrouilles escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a abattu un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 84:
						if($Event_Pilote_eni)
						{
							$Pilote_eni=GetData("Pilote","ID",$Event_Pilote_eni,"Nom");
							$Event.=$Event_Date." : ".$Pilote_eni." a intercepté ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." lors d'une patrouille, dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
						}
						else
							$Event.=$Event_Date." : Notre patrouille composée de ".$Event_Avion_eni_Nom." a intercepté ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					/*case 84:
						$Pilote=GetData("Pilote","ID",$Event_Pilote_eni,"Nom");
						$Event.=$Event_Date." : ".$Pilote." a escorté avec succès ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;*/
					case 85:
						$Event.=$Event_Date." : Nos avions escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." ont tenu a distance les avions ennemis dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 86:
						$Pilote_eni=GetData("Pilote","ID",$Event_Pilote_eni,"Nom");
						$Event.=$Event_Date." : ".$Pilote_eni." escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été abattu par un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 87:
						if($Event_Pilote_eni)
						{
							$Pilote_eni=GetData("Pilote","ID",$Event_Pilote_eni,"Nom");
							$Event.=$Event_Date." : ".$Pilote_eni." a escorté avec succès ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." lors d'une mission au-dessus de ".$Event_Lieu_Nom."</b>.<br>";
						}
						else
							$Event.=$Event_Date." : Un de nos avions escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été abattu par un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 88:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a abattu un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.", diminuant la couverture aérienne ennemie</b>.<br>";
					break;
					case 89:
						$Pilote_eni=GetData("Pilote","ID",$Event_Pilote_eni,"Nom");
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a bénéficié d'une reconnaissance effectuée par ".$Pilote_eni." dans les environs de ".$Event_Lieu_Nom.".<br>";
					break;
					case 95:
						$Event.=$Event_Date." : ".$Escorte_nbr." de nos avions ont été appelés à l'aide par ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom.", alors qu'il été poursuivi par l'ennemi en rentrant à sa base de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 96:
						$Pilote_eni=GetData("Pilote_IA","ID",$Event_Pilote_eni,"Nom");
						$Event.=$Event_Date." : ".$Pilote_eni." escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été abattu par un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 97:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été abattu lors d'un sauvetage à bord d'un <b>".$Event_Avion_Nom."</b> au-dessus de ".$Event_Lieu_Nom."<br>";
					break;
					case 101:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a commandé <b>".$Event_Avion_Nbr." litres de ".$Event_Avion." Octane</b>, arrivés sur notre base de ".$Event_Lieu_Nom."<br>";
					break;
					case 102:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a commandé <b>".$Event_Avion_Nbr." munitions de ".$Event_Avion."mm</b>, arrivés sur notre base de ".$Event_Lieu_Nom."<br>";
					break;
					case 103:
						if($Event_Avion ==800)
							$bb_txt="torpilles";
						elseif($Event_Avion ==80)
							$bb_txt="rockets";
						elseif($Event_Avion ==300)
							$bb_txt="charges";
						elseif($Event_Avion ==400)
							$bb_txt="mines";
						else
							$bb_txt="bombes de ".$Event_Avion."kg";
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a commandé <b>".$Event_Avion_Nbr." ".$bb_txt."</b>, arrivés sur votre base de ".$Event_Lieu_Nom."<br>";
					break;
					case 104:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a commandé <b>".$Event_Avion_Nbr."x ".GetData("Armes","ID",$Event_Avion,"Nom")."</b>, arrivés sur notre base de ".$Event_Lieu_Nom."<br>";
					break;			
					case 105:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a modifié <b>".$Event_Avion_Nbr."x ".GetData("Armes","ID",$Event_Avion,"Nom")."</b> sur notre base de ".$Event_Lieu_Nom."<br>";
					break;			
					case 106:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a démantelé <b>".$Event_Avion_Nbr."x ".GetData("Armes","ID",$Event_Avion,"Nom")."</b> sur notre base de ".$Event_Lieu_Nom."<br>";
					break;			
					case 111:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a livré <b>".$Event_Avion_Nbr." litres de ".$Event_Avion." Octane</b>, arrivés sur notre base de ".$Event_Lieu_Nom."<br>";
					break;
					case 112:
						if($Event_Avion >9999)
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a livré <b>".$Event_Avion_Nbr." bombes de ".substr($Event_Avion,0,-1)."kg</b>, arrivés sur notre base de ".$Event_Lieu_Nom."<br>";
						elseif($Event_Avion >9000)
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a livré <b>".$Event_Avion_Nbr." bombes de ".substr($Event_Avion,1)."kg</b>, arrivés sur notre base de ".$Event_Lieu_Nom."<br>";
						else
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." a livré <b>".$Event_Avion_Nbr." munitions de ".$Event_Avion."mm</b>, arrivés sur notre base de ".$Event_Lieu_Nom."<br>";
					break;
					case 113:
						if($Event_Avion ==800)
							$bb_txt="torpilles";
						elseif($Event_Avion ==80)
							$bb_txt="rockets";
						elseif($Event_Avion ==300)
							$bb_txt="charges";
						elseif($Event_Avion ==400)
							$bb_txt="mines";
						else
							$bb_txt="bombes de ".$Event_Avion."kg";
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a livré <b>".$Event_Avion_Nbr." ".$bb_txt."</b>, arrivés sur votre base de ".$Event_Lieu_Nom."<br>";
					break;
					case 120:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a saboté <b>".$Event_Avion_Nbr." ".$Event_Avion_Nom."</b>, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 121:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a saboté <b>".$Event_Avion_Nbr." litres de carburant</b>, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 122:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a saboté <b>".$Event_Avion_Nbr." munitions</b>, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 123:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a saboté <b>".$Event_Avion_Nbr." canon de DCA</b>, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 124:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." a volé de l'équipement, sur la base de ".$Event_Lieu_Nom."<br>";
					break;
					case 180:
						if($Event_Pilote_eni)
						{
							$Pilote_eni=GetData("Pilote_IA","ID",$Event_Pilote_eni,"Nom");
							$Avion_eni=GetData("Avion","ID",$Event_Avion_Nbr,"Nom");
							$Event.=$Event_Date." : ".$Pilote_eni." a été abattu par ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." lors d'une escorte dans les environs de ".$Event_Lieu_Nom."</b>. Un <b>".$Avion_eni."</b> a été perdu!<br>";
						}
						else
							$Event.=$Event_Date." : Un de nos avions escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été abattu par un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 181:
						if($Event_Pilote_eni)
						{
							$Pilote_eni=GetData("Pilote_IA","ID",$Event_Pilote_eni,"Nom");
							if($Pilote_eni == $Event_Pilote_Nom)
								$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été abattu à bord de son ".$Event_Avion_eni_Nom." lors d'une patrouille dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
							else
								$Event.=$Event_Date." : ".$Pilote_eni." a été abattu lors d'une patrouille dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
						}
						else
							$Event.=$Event_Date." : Une de nos patrouilles escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été abattue par un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 188:
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a abattu un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.", diminuant la couverture aérienne ennemie</b>.<br>";
					break;
					case 189:
						$Event_Pilote_Nom=GetData("Pilote_IA","ID",$Event_Pilote,"Nom");
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a abattu un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.", diminuant la couverture aérienne ennemie</b>.<br>";
					break;
					case 191:
						$Event_Pilote_Nom=GetData("Pilote_IA","ID",$Event_Pilote,"Nom");
						if($Event_Pilote_eni)
						{
							$Pilote_eni=GetData("Pilote_IA","ID",$Event_Pilote_eni,"Nom");
							if($Pilote_eni == $Event_Pilote_Nom)
								$Event.=$Event_Date." : ".$Event_Pilote_Nom." a été abattu à bord de son ".$Event_Avion_eni_Nom." lors d'une patrouille dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
							else
								$Event.=$Event_Date." : ".$Pilote_eni." a été abattu lors d'une patrouille dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
						}
						else
							$Event.=$Event_Date." : Une de nos patrouilles escortant ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été abattue par un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom."</b>.<br>";
					break;
					case 192:
						$Event_Pilote_Nom=GetData("Pilote_IA","ID",$Event_Pilote,"Nom");
						$Event.=$Event_Date." : ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a abattu un ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.", diminuant la couverture aérienne ennemie</b>.<br>";
					break;
					case 222:
						$Event.=$Event_Date." : Un <i>".$Event_Avion_Nom."</i> a été détruit par une attaque terrestre de l'ennemi sur notre aérodrome de ".$Event_Lieu_Nom."<br>";
					break;
					case 223:
						$Event.=$Event_Date." : <b>Un ".$Event_Avion_Nom." a détruit un canon anti-aérien sur notre base de ".$Event_Lieu_Nom."</b><br>";
					break;
					case 224:
						$Event.=$Event_Date." : <b>Un ".$Event_Avion_Nom." a détruit un hangar sur notre base de ".$Event_Lieu_Nom.". Nos stocks de munitions et d'essence ont été réduits !</b><br>";
					break;
					case 272:
						$Pilote_eni=GetData("Lieu","ID",$Event_Pilote_eni,"Nom");
						$Event.=$Event_Date." : Le ravitaillement en carburant de notre base de ".$Event_Lieu_Nom." a été diminué de <b>".$Event_Avion_Nbr."%</b> suite à une attaque terrestre de ".$Event_Pilote_Nom." à bord d'un ".$Event_Avion_Nom." dans les environs de ".$Pilote_eni."<br>";
					break;
					case 273:
						$Pilote_eni=GetData("Lieu","ID",$Event_Pilote_eni,"Nom");
						$Event.=$Event_Date." : Le ravitaillement en munitions de notre base de ".$Event_Lieu_Nom." a été diminué de <b>".$Event_Avion_Nbr."%</b> suite à une attaque terrestre de ".$Event_Pilote_Nom." à bord d'un ".$Event_Avion_Nom." dans les environs de ".$Pilote_eni."<br>";
					break;
				}
				if($Event_Type <20 and $Event_Type >1 and $Event_Type !=5 and $Event_Type !=6 and $Event_Type !=7)
					$Event.=$Event_Date." : ".$Event_Pilote_Nom.", pilotant un ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom.", ".$Event_Type_txt."<br>";
			}
			mysqli_free_result($result);
		}	
		//$Event=str_replace("2012","1940",$Event);
		include_once('./menu_journal.php');
		echo "<div id='esc_journal'><table class='table'><tr><th bgcolor='tan'>Journal d'unité</th></tr><tr><td rowspan='20' align='left'>".$Event."</td></tr></table></div>".$msc;
	}
	else
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT DISTINCT ID,Nom FROM Unit ORDER BY Nom ASC");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
			{
				 $Units.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
			}
			mysqli_free_result($result);
			unset($data);
		}
		echo "<h1>Journal d'unité</h1>
			<form action='../index.php?view=admin_esc_journal' method='post'>
			<select name='unit' style='width: 150px'>" .$Units."</select>
			<p><input type='Submit' class='btn btn-default' value='VALIDER' onclick='this.disabled=true;this.form.submit();'></p>
			</form>";
	}
}
?>