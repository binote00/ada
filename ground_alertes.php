<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($Commandant ==$OfficierEMID or $Adjoint_Terre ==$OfficierEMID or $Officier_Mer ==$OfficierEMID or $Officier_Rens ==$OfficierEMID or $Armee or $GHQ or $Admin) 
	{
		include_once('./jfv_ground.inc.php');
		$Coord=GetCoord($Front);
		$Lat_base_min=$Coord[0];
		$Lat_base_max=$Coord[1];
		$Long_base_min=$Coord[2];
		$Long_base_max=$Coord[3];
		$Event="";
		/*if($Admin ==1 and $country ==1)
			//$query="SELECT DISTINCT e.* FROM gnmh_aubedesaiglesnet4.Events_Ground as e WHERE ((e.Event_Type IN (400,401,405,431,455,465,605) AND e.Unit=0) OR (e.Event_Type=509)) ORDER BY e.ID DESC LIMIT 50";
			$query="SELECT * FROM gnmh_aubedesaiglesnet4.Events_Ground as e WHERE (e.Event_Type IN (401,405,431,455,465,505,605) AND e.Unit=0) OR e.Event_Type IN (280,281,282,283,284,380,381,457,465,466,467,502,506,507,509,602,607,609,702,707,709)
			OR (e.Event_Type IN (400,450) AND (e.Pilote_eni=0 OR e.PlayerID=0)) ORDER BY e.ID DESC LIMIT 100";
		else*/
			/*$query="SELECT * FROM ((SELECT DISTINCT e.* FROM gnmh_aubedesaiglesnet4.Events_Ground as e,gnmh_aubedesaiglesnet.Lieu as l WHERE e.Lieu=l.ID 
			AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
			AND ((e.Event_Type IN (400,401,405,431,455,465,605) AND e.Unit=0) OR (e.Event_Type IN (466,509))) AND e.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW())
			UNION (SELECT DISTINCT e.* FROM gnmh_aubedesaiglesnet4.Events_Ground as e,gnmh_aubedesaiglesnet.Lieu as l WHERE e.Lieu=l.ID 
			AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
			AND e.Event_Type=509 AND e.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW()))as a ORDER BY a.ID DESC";*/
		if($Armee)
		{
			$con=dbconnecti();
			$Armee_Nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Armee WHERE ID='$Armee'"),0);
			$resultregs=mysqli_query($con,"SELECT r.ID FROM Regiment_IA as r LEFT JOIN Division as d ON r.Division=d.ID WHERE d.Armee='$Armee'");
            $resultescs=mysqli_query($con,"SELECT ID FROM Unit WHERE Armee='$Armee'");
			mysqli_close($con);
			if($resultregs)
			{
				while($datal=mysqli_fetch_array($resultregs,MYSQLI_ASSOC)) 
				{
					$regs[]=$datal['ID'];
				}
				mysqli_free_result($resultregs);
				if(is_array($regs))
					$Units_Armee=implode(',',$regs);
			}
            if($resultescs)
            {
                while($datae=mysqli_fetch_array($resultescs,MYSQLI_ASSOC))
                {
                    $escs[]=$datae['ID'];
                }
                mysqli_free_result($resultescs);
                if(is_array($escs))
                    $Esc_Armee=implode(',',$escs);
            }
			if($Units_Armee)
				$query="SELECT DISTINCT e.*,DATE_FORMAT(e.`Date`,'%d-%m-%Y à %Hh%i') as `Datec`,l.Nom as Ville FROM gnmh_aubedesaiglesnet4.Events_Ground as e,gnmh_aubedesaiglesnet.Lieu as l WHERE e.Lieu=l.ID 
				AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
				AND (
				(e.Event_Type IN (381,401,405,420,431,455,465,502,505,605,702,707,708,709) AND e.Unit IN (".$Units_Armee.")) OR 
				(e.Event_Type IN (381) AND e.PlayerID IN (".$Esc_Armee.")) OR 
				(e.Event_Type IN (380,466,467,506,507,509,602,607,609,708) AND e.Pilote_eni IN (".$Units_Armee.")) 
				)
				AND e.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW()
				UNION
                SELECT DISTINCT e.*,DATE_FORMAT(e.`Date`,'%d-%m-%Y à %Hh%i') as `Datec`,l.Nom as Ville FROM gnmh_aubedesaiglesnet4.Events_Ground_Stats as e,gnmh_aubedesaiglesnet.Lieu as l WHERE e.Lieu=l.ID 
				AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
				AND e.Event_Type IN (401,420) AND e.Unit IN (".$Units_Armee.")
				AND e.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW()				
				ORDER BY Date DESC LIMIT 100";
			$Armee_txt=' de la '.$Armee_Nom;
		}
		else
			$query="SELECT DISTINCT e.*,DATE_FORMAT(e.`Date`,'%d-%m-%Y à %Hh%i') as `Datec`,l.Nom as Ville FROM gnmh_aubedesaiglesnet4.Events_Ground as e,gnmh_aubedesaiglesnet.Lieu as l WHERE e.Lieu=l.ID 
			AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
			AND (e.Event_Type IN (380,381,401,405,420,431,455,457,465,466,467,502,505,506,507,509,602,605,607,609,702,707,708,709))  
			AND e.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW()
            UNION 
            SELECT DISTINCT e.*,DATE_FORMAT(e.`Date`,'%d-%m-%Y à %Hh%i') as `Datec`,l.Nom as Ville FROM gnmh_aubedesaiglesnet4.Events_Ground_Stats as e,gnmh_aubedesaiglesnet.Lieu as l WHERE e.Lieu=l.ID 
			AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
			AND (e.Event_Type IN (401,420))  
			AND e.Date BETWEEN NOW() - INTERVAL 15 DAY AND NOW()
			ORDER BY Date DESC LIMIT 100"; //OR (e.Event_Type IN (400,450) AND (e.Pilote_eni=0 OR e.PlayerID=0))
		if($query)
		{
			$con=dbconnecti(4);
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				//$con=dbconnecti();
				while($Classement=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Event_Type_txt="";
					//$Event_Date=substr($Classement['Date'],0,16);
					$Event_Date=$Classement['Datec'];
					$Event_Type=$Classement['Event_Type'];
					$Event_Lieu=$Classement['Lieu'];
					$Event_Avion=$Classement['Avion'];
					$Event_Pilote_eni=$Classement['Pilote_eni'];
					$Event_PlayerID=$Classement['PlayerID'];
					$Event_Lieu_Nom=$Classement['Ville'];
					if($Event_PlayerID >0)
						$DB_Reg='Regiment';
					else
						$DB_Reg='Regiment_IA';
					if($Event_Type >=280 and $Event_Type <=284)
					{
						/*$resultpl=mysqli_query("SELECT Nom,Pays FROM Pilote_IA WHERE ID='$Event_PlayerID'");
						$resulteni=mysqli_query("SELECT Nom,Pays FROM Pilote_IA WHERE ID='$Event_Pilote_eni'");
						$Event_Avion_Nom=mysqli_result(mysqli_query("SELECT Nom FROM Avion WHERE ID='$Event_Avion'"),0);
						$Event_Avion_eni_Nom=mysqli_result(mysqli_query("SELECT Nom FROM Avion WHERE ID='$Event_Avion_Nbr'"),0);
						if($resultpl)
						{
							while($datap=mysqli_fetch_array($resultpl,MYSQLI_ASSOC))
							{
								$Event_Pilote_Nom=$datap['Nom'];
								$Pays=$datap['Pays'];
							}
							mysqli_free_result($resultpl);
						}
						if($resulteni)
						{
							while($datae=mysqli_fetch_array($resulteni,MYSQLI_ASSOC))
							{
								$Pilote_eni=$datap['Nom'];
								$Pays_eni=$datap['Pays'];
							}
							mysqli_free_result($resulteni);
						}*/	
						$Event_Pilote_Nom=GetData("Pilote_IA","ID",$Event_PlayerID,"Nom");
						$Pays=GetData("Pilote_IA","ID",$Event_PlayerID,"Pays");				
						$Pays_eni=GetData("Pilote_IA","ID",$Event_Pilote_eni,"Pays");
						$Pilote_eni=GetData("Pilote_IA","ID",$Event_Pilote_eni,"Nom");					
						$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
						$Event_Avion_eni_Nom=GetData("Avion","ID",$Event_Avion_Nbr,"Nom");
					}
					elseif($Event_Type !=380 and $Event_Type !=381 and $Event_Type !=402 and $Event_Type !=403 and $Event_Type !=409 and $Event_Type !=502 and $Event_Type !=509)
						//$Event_Avion_Nom=mysqli_result(mysqli_query("SELECT Nom FROM Cible WHERE ID='$Event_Avion'"),0);
						$Event_Avion_Nom=GetData("Cible","ID",$Event_Avion,"Nom");
					switch($Event_Type)
					{
						case 280:
							$Event.=$Event_Date." : Le chasseur d'escorte ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été <span class='text-warning'>abattu</span> par le chasseur en couverture ".$Pilote_eni." à bord de son ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.".<br>";
						break;
						case 281:
							$Event.=$Event_Date." : Le chasseur en couverture ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été <span class='text-warning'>abattu</span> par le chasseur d'escorte ".$Pilote_eni." à bord de son ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.".<br>";
						break;
						case 282:
							$Event.=$Event_Date." : Le chasseur en couverture ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été <span class='text-warning'>abattu</span> par ".$Pilote_eni." à bord de son ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.".<br>";
						break;
						case 283:
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été <span class='text-warning'>abattu</span> par le chasseur en couverture ".$Pilote_eni." à bord de son ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.".<br>";
						break;
						case 284:
							$Event.=$Event_Date." : ".$Event_Pilote_Nom." à bord de son ".$Event_Avion_Nom." a été <span class='text-danger'>intercepté</span> par le chasseur en couverture ".$Pilote_eni." à bord de son ".$Event_Avion_eni_Nom." dans les environs de ".$Event_Lieu_Nom.".<br>";
						break;
						case 380:
							$Pays_eni=GetData("Regiment","ID",$Classement['Unit'],"Pays");
							$Pays=GetData("Unit","ID",$Event_PlayerID,"Pays");
							$Event.=$Event_Date." : La DCA rapprochée <b>".GetData("Cible","ID",$Classement['Avion_Nbr'],"Nom")."</b> <img src='".$Pays_eni."20.gif'> de la <b>".$Classement['Unit']."e Cie</b>
							a abattu un ".GetData("Avion","ID",$Event_Avion,"Nom")." <img src='".$Pays."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 381:
							$Pays_eni=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Pays=GetData("Unit","ID",$Event_PlayerID,"Pays");
							$Event.=$Event_Date." : La DCA rapprochée <b>".GetData("Cible","ID",$Classement['Avion_Nbr'],"Nom")."</b> <img src='".$Pays_eni."20.gif'> de la <b>".$Classement['Unit']."e Cie</b>
							a abattu un ".GetData("Avion","ID",$Event_Avion,"Nom")." <img src='".$Pays."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 400:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b> <img src='".$Pays_eni."20.gif'> 
							ont été détruits lors d'une riposte dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 401:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b>
							ont été détruits par une attaque de la ".$Classement['Pilote_eni']."e Cie<img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 402:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> a été détruit suite à un bombardement 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 403:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Pilote","ID",$Event_PlayerID,"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> a été détruit suite à une attaque aérienne 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 404:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b>
							ont été détruits lors d'un assaut de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 405:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Event_Avion_Nom."</b> a été détruit lors d'un bombardement d'artillerie de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 406:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b>
							ont été détruits lors d'un torpillage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 407:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b>
							ont été détruits lors d'un grenadage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 408:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b>
							ont été détruits lors d'une reconnaissance armée de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 409:
							if($Event_Avion < 1000)
							{
								$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
								$Pays=GetData("Avion","ID",$Event_Avion,"Pays");
							}
							else
							{
								$Event_Avion_Nom=GetData("Avions_Persos","ID",$Event_Avion,"Nom");
								$Pays=GetData("Avions_Persos","ID",$Event_Avion,"Pays");
							}
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Un ".$Event_Avion_Nom." <img src='".$Pays."20.gif'> a endommagé un <b>".$Event_Target."</b> de la <b>".$Classement['Unit']."e Cie</b> <img src='".$Pays_eni."20.gif'> pour ".$Classement['Avion_Nbr']." dégâts lors d'une attaque aérienne dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
                        case 420:
                            $Pays=GetData($DB_Reg,"ID",$Classement['Unit'],"Pays");
                            $Event_Target=GetData("Cible","ID",$Event_Avion,"Nom");
                            $Event.=$Event_Date." : La ".$Classement['Unit']."e Cie <img src='".$Pays."20.gif'> a perdu <b>".$Classement['Avion_Nbr']." ".$Event_Target."</b> en sautant sur des mines dans les environs de ".$Event_Lieu_Nom."<br>";
                            break;
						case 431:
							/*if(!$Classement['Unit'])
								$Pays_eni=0;
							else*/
							$Pays=GetData($DB_Reg,"ID",$Classement['Unit'],"Pays");
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : La ".$Classement['Unit']."e Cie <img src='".$Pays."20.gif'> a été la cible d'un bombardement d'artillerie de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 450:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b> 
							<img src='".$Pays_eni."20.gif'> lors d'une riposte dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 455:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b>
							lors d'un bombardement d'artillerie de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 456:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b>
							lors d'un torpillage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 457:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b>
							lors d'un grenadage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 461:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : </b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b>
							lors d'une attaque de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 465:
							$Pays_eni=GetData("Regiment_IA","ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b>
							lors d'un bombardement d'artillerie de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 466:
							$Pays_eni=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b>
							lors d'un torpillage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 467:
							$Pays_eni=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b>
							lors d'un grenadage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 502:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							if(!$Event_Avion_Nom)$Event_Avion_Nom="avion";
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Pilote","ID",$Event_PlayerID,"Pays");
							$Pays=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> <img src='".$Pays."20.gif'> a été détruit suite à un bombardement 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 505:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b>
							ont été détruits lors d'un bombardement de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 506:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b>
							ont été détruits lors d'un torpillage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 507:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."x ".$Event_Avion_Nom."</b>
							ont été détruits lors d'un grenadage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 509:
							if($Event_Avion <1000)
							{
								$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
								$Pays=GetData("Avion","ID",$Event_Avion,"Pays");
							}
							else
							{
								$Event_Avion_Nom=GetData("Avions_Persos","ID",$Event_Avion,"Nom");
								$Pays=GetData("Avions_Persos","ID",$Event_Avion,"Pays");
							}
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Un ".$Event_Avion_Nom." <img src='".$Pays."20.gif'> a endommagé un <b>".$Event_Target."</b> <img src='".$Pays_eni."20.gif'> pour ".$Classement['Avion_Nbr']." dégâts lors d'une attaque aérienne dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 602:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							if(!$Event_Avion_Nom)$Event_Avion_Nom="avion";
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
							$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> <img src='".$Pays."20.gif'> a été détruit suite à un bombardement 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 607:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							if(!$Event_Avion_Nom)$Event_Avion_Nom="avion";
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
							$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> <img src='".$Pays."20.gif'> a été détruit suite à un grenadage 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 605:
							$Pays_eni=GetData($DB_Reg,"ID",$Classement['Pilote_eni'],"Pays");
							$Event.=$Event_Date." : <b>".$Event_Avion_Nom."</b> a été détruit lors d'un bombardement d'artillerie (navale ou IA) <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 609:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							if(!$Event_Avion_Nom)$Event_Avion_Nom="avion";
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
							$Pays=GetData("Regiment","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> <img src='".$Pays."20.gif'> a été endommagé suite à un bombardement 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 702:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							if(!$Event_Avion_Nom)$Event_Avion_Nom="avion";
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
							$Pays=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> <img src='".$Pays."20.gif'> a été détruit suite à un bombardement 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 707:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							if(!$Event_Avion_Nom)$Event_Avion_Nom="avion";
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
							$Pays=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> <img src='".$Pays."20.gif'> a été détruit suite à un grenadage 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 708:
							$Pays_eni=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : <b>".$Classement['Avion_Nbr']."</b> dégâts ont été occasionnés au <b>".$Event_Avion_Nom."</b>
							lors d'un torpillage de la ".$Classement['Pilote_eni']."e Cie <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
						case 709:
							$Event_Avion_Nom=GetData("Avion","ID",$Event_Avion,"Nom");
							if(!$Event_Avion_Nom)$Event_Avion_Nom="avion";
							$Event_Target=GetData("Cible","ID",$Event_Pilote_eni,"Nom");
							$Pays_eni=GetData("Avion","ID",$Event_Avion,"Pays");
							$Pays=GetData("Regiment_IA","ID",$Classement['Unit'],"Pays");
							$Event.=$Event_Date." : Le <b>".$Event_Target."</b> <img src='".$Pays."20.gif'> a été endommagé suite à un bombardement 
							d'un ".$Event_Avion_Nom." <img src='".$Pays_eni."20.gif'> dans les environs de ".$Event_Lieu_Nom."<br>";
						break;
					}
				}
				mysqli_free_result($result);
			}
		}
		if($Event)
			echo '<h2>Rapports des troupes'.$Armee_txt.'</h2><fieldset>'.$Event.'</fieldset>';
		else
			echo "<h2>Rapports des troupes".$Armee_txt."</h2><div class='alert alert-info'>Aucun rapport n'a été transmis récemment</div>";

	}
	else
		PrintNoAccess($country,1,4,7,8);
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';