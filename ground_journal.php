<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$OfficierID=$_SESSION['Officier'];
/*if($OfficierID ==1)
	$OfficierID=Insec($_GET['id']);*/
/*if($OfficierID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$resultl=mysqli_query($con,"SELECT DISTINCT ID FROM Regiment WHERE Officier_ID='$OfficierID'");
	mysqli_close($con);
	if($resultl)
	{
		while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
		{
			$regs[]=$datal['ID'];
		}
		mysqli_free_result($resultl);
		$reg=implode(',',$regs);
	}
	$query ="(SELECT * FROM Events_Ground WHERE Unit IN (".$reg.") AND Event_Type NOT IN (381) AND `Date` BETWEEN NOW() - INTERVAL 30 DAY AND NOW()) 
	UNION ALL (SELECT * FROM Events_Ground WHERE Pilote_eni IN (".$reg.") AND Event_Type IN (406,407,450,455,456,457,461) AND `Date` BETWEEN NOW() - INTERVAL 30 DAY AND NOW())
	UNION ALL (SELECT * FROM Events_Ground_Stats WHERE Pilote_eni IN (".$reg.") AND Event_Type IN (400,401,404,405) AND `Date` BETWEEN NOW() - INTERVAL 30 DAY AND NOW())
	UNION ALL (SELECT * FROM Events_Ground_Stats WHERE Unit IN (".$reg.") AND Event_Type IN (402,403,410,415,420,605,615) AND `Date` BETWEEN NOW() - INTERVAL 30 DAY AND NOW())
	ORDER BY ID DESC LIMIT 100";
	//239,301,302,311,312,276,376,377,400,401,402,403,404,405,406,407,408,409,410,420,430,431,450,455,456,457,461,509
	//Journal
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
			$Event_Date=substr($Classement['Date'],0,16);
			$Event_Type=$Classement['Event_Type'];
			$Event_Lieu=$Classement['Lieu'];
			$Event_Avion=$Classement['Avion'];
			$Event_Pilote_eni=$Classement['Pilote_eni'];
			$Event_PlayerID=$Classement['PlayerID'];
			if($Event_Type !=276 and $Event_Type !=301 and $Event_Type !=302 and $Event_Type !=311 and $Event_Type !=312 and $Event_Type !=376 and $Event_Type !=377 and $Event_Type !=378 and $Event_Type !=402 and $Event_Type !=403 and $Event_Type !=409)
				$Event_Avion_Nom=GetData("Cible","ID",$Event_Avion,"Nom");
			$Event_Lieu_Nom=GetData("Lieu","ID",$Event_Lieu,"Nom");			
			switch($Event_Type)
			{
				case 239:
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> ont été livrés sur notre base de ".$Event_Lieu_Nom."<br>";
				break;
				case 301:
					if($Event_Avion ==1)
						$Carbu="de Diesel";
					else
						$Carbu="d'Octane ".$Event_Avion;
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." litres ".$Carbu." ont été livrés à la <b>".$Classement['Unit']."e Cie</b> sur notre base de ".$Event_Lieu_Nom."<br>";
				break;
				case 302:
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." munitions de ".$Event_Avion."mm ont été livrés à la <b>".$Classement['Unit']."e Cie</b> sur notre base de ".$Event_Lieu_Nom."<br>";
				break;
				case 311:
					$Event.=$Event_Date." : La ".$Classement['Pilote_eni']."e Cie a livré <b>".$Classement['Avion_Nbr']." litres de ".$Event_Avion." Octane</b> à la ".$Classement['Unit']."e Cie, dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 312:
					if($Event_Avion ==800)
						$Mun_txt="Torpilles";
					elseif($Event_Avion ==300)
						$Mun_txt="Charges";
					elseif($Event_Avion ==400)
						$Mun_txt="Mines";
					else
						$Mun_txt="munitions de ".$Event_Avion."mm";
					$Event.=$Event_Date." : La ".$Classement['Pilote_eni']."e Cie a livré <b>".$Classement['Avion_Nbr']." ".$Mun_txt."</b> à la ".$Classement['Unit']."e Cie, dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 276:
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event_Pilote_Nom=GetData("Pilote","ID",$Event_PlayerID,"Nom");
					$Event.=$Event_Date." : La DCA de couverture <b>".GetData("Armes","ID",$Event_Pilote_eni,"Nom")."</b> de la <b>".$Classement['Unit']."e Cie</b> a tiré <b>".$Classement['Avion_Nbr']." obus</b> suite à une attaque aérienne de ".$Event_Pilote_Nom." à bord d'un ".$Event_Avion_Nom." aux alentours de ".$Event_Lieu_Nom."<br>";
				break;
				case 376:
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event_Pilote_Nom=GetData("Pilote","ID",$Event_PlayerID,"Nom");
					$Event.=$Event_Date." : La DCA rapprochée <b>".GetData("Armes","ID",$Event_Pilote_eni,"Nom")."</b> de la <b>".$Classement['Unit']."e Cie</b> a tiré <b>".$Classement['Avion_Nbr']." obus</b> suite à une attaque aérienne de ".$Event_Pilote_Nom." à bord d'un ".$Event_Avion_Nom." aux alentours de ".$Event_Lieu_Nom."<br>";
				break;
				case 377:
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event.=$Event_Date." : La DCA rapprochée <b>".GetData("Cible","ID",$Event_Pilote_eni,"Nom")."</b> de la <b>".$Classement['Unit']."e Cie</b> a endommagé un ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 378:
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event.=$Event_Date." : La DCA de couverture <b>".GetData("Cible","ID",$Event_Pilote_eni,"Nom")."</b> de la <b>".$Classement['Unit']."e Cie</b> a endommagé un ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 380:
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event.=$Event_Date." : La DCA rapprochée <b>".GetData("Cible","ID",$Event_Pilote_eni,"Nom")."</b> de la <b>".$Classement['Unit']."e Cie</b> a endommagé un ".$Event_Avion_Nom." dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 400:
					if($Classement['Pilote_eni'] >0)
					{
						$Unit_eni=$Classement['Pilote_eni']."e Cie";
						$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					}
					else
					{
						$Unit_eni="Cie EM";
						$Pays_eni=0;
					}
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					ont été détruits par une riposte de la <b>".$Unit_eni."</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 401:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					ont été détruits par une attaque de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 402:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
					$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
					$Event.=$Event_Date." : 1 ".$Event_Target." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'> ont été détruits suite à un bombardement 
					d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 403:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
					$Pays_eni=GetData("Pilote","ID",$Event_PlayerID,"Pays");
					$Event.=$Event_Date." : 1 ".$Event_Target." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'> ont été détruits suite à une attaque aérienne 
					d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 404:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					ont été détruits lors d'un assaut de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 405:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					ont été détruits lors d'un bombardement d'artillerie de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 406:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					ont été détruits lors d'un torpillage de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 407:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					ont été détruits lors d'un grenadage de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 408:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					ont été détruits lors d'une reconnaissance armée de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 409:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event.=$Event_Date." : Un ".$Event_Avion_Nom." a endommagé un navire de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'> pour ".$Classement['Avion_Nbr']." dégâts lors d'une attaque aérienne dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 402:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
					$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Target." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'> a été endommagé suite à un bombardement 
					d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 410:
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b>
					ont déserté dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 420:
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b>
					ont sauté sur des mines dans les environs de ".$Event_Lieu_Nom."<br>";			
				break;
				case 430:
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b>
					ont réussi une percée face à ".$Classement['Pilote_eni']." unités ennemies, dans les environs de ".$Event_Lieu_Nom."<br>";			
				break;
				case 431:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : La <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					a été la cible d'un bombardement d'artillerie de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 450:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." dégâts ont été occasionnés au ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					lors d'une riposte de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 455:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." dégâts ont été occasionnés au ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					lors d'un bombardement d'artillerie de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 456:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." dégâts ont été occasionnés au ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					lors d'un torpillage de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 457:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." dégâts ont été occasionnés au ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					lors d'un grenadage de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 461:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." dégâts ont été occasionnés au ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					lors d'une attaque de la <b>".$Classement['Pilote_eni']."e Cie</b> <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 602:
					$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
					if(!$Event_Avion_Nom)$Event_Avion_Nom="avion";
					$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
					$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Target." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'> a été détruit suite à un bombardement 
					d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
				case 605:
					$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
					$Pays_eni=GetData("Regiment_IA","ID",$Classement['Pilote_eni'],"Pays");
					$Event.=$Event_Date." : ".$Classement['Avion_Nbr']." ".$Event_Avion_Nom." de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays."20.gif'>
					ont été détruits lors d'un bombardement d'artillerie (navale ou IA) <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
				break;
			}
		}
		mysqli_free_result($result);
	}
	if($Event)
		echo "<h1>Journal de Campagne</h1><div style='overflow:auto; height: 640px;'><table class='table'><tr><td rowspan='20' align='left'>".$Event."</td></tr></table></div>".$msc;
	else
		echo "<h1>Journal de Campagne</h1>Aucun évènement récent.";
	if($msc >5)
	{
		mail('binote@hotmail.com','Aube des Aigles: Slow GroundJournal',$msc.' secondes pour officier '.$OfficierID.' ('.$reg.')');
		echo "<p class='lead'>L'affichage de cette page est trop lent sur votre système. Veuillez vider le cache de votre navigateur internet et/ou utiliser une connexion plus stable.</p>";
	}
}
else
	echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
*/?>