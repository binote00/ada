<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{	
	include_once('./jfv_include.inc.php');
	$OfficierEMID=$_SESSION['Officier_em'];
	$Unite=Insec($_POST['Unite']);
	if($OfficierEMID >0 AND $Unite >0)
	{
		include_once('./jfv_air_inc.php');
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_access.php');
		include_once('./jfv_inc_em.php');
		$Long_Mission=0;
		$Cible=Insec($_POST['Cible']);
		$Type=Insec($_POST['Type']);
		$Avion1=Insec($_POST['Avion1']);
		$Avion2=Insec($_POST['Avion2']);
		$Avion3=Insec($_POST['Avion3']);
		$Avion1nbr=Insec($_POST['Avion1nbr']);
		$Avion2nbr=Insec($_POST['Avion2nbr']);
		$Avion3nbr=Insec($_POST['Avion3nbr']);
		$Mission_alt=Insec($_POST['Altitude']);
		$Mission_Flight=Insec($_POST['Flight']);
		$Cible_Atk=Insec($_POST['Cible_Atk']);
		$Bomb_Form=Insec($_POST['Bombs']);
		$Paras=Insec($_POST['Paras']);
		$Zoneb=Insec($_POST['Zoneb']);
		$Long_Mission=Insec($_POST['Long']);
		$Reset=Insec($_POST['reset']);
		$country=$_SESSION['country'];
		$_SESSION['esc'] = $Unite;
		if($Long_Mission)
			$CT=2;
		else
			$CT=1;
		/*
		 * Reset 1 - Annuler Mission
		*/
		if($Reset ==1)
		{
			$con=dbconnecti();
			$reset=mysqli_query($con,"UPDATE Unit SET Mission_Lieu=0,Mission_Type=0 WHERE ID='$Unite'");
			$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Alt=0,Cible=0,Avion=0,Task=0 WHERE Unit='$Unite'");
			mysqli_close($con);
            $_SESSION['msg_esc'] = 'Vous annulez la mission d\'unité en cours et rappelez les pilotes à la base!';
            header( 'Location : index.php?view=em_ia');
		}
        /*
         * Reset 3 - Annuler Demande Mission
        */
		elseif($Reset ==3)
		{
			SetData("Unit","Mission_Lieu_D",0,"ID",$Unite);
			SetData("Unit","Mission_Type_D",0,"ID",$Unite);
            $_SESSION['msg_esc'] = 'Vous annulez la demande de mission en cours!';
            header( 'Location : index.php?view=em_ia');
		}
        /*
         * Reset 4 - Réorganiser Troupes de défense
        */
		elseif($Reset ==4)
		{
			$CT_Discount=Get_CT_Discount($Avancement);
			if($GHQ)$CT_Discount+=4;
			$CT_Refit=12-$CT_Discount;
			if($Credits >=$CT_Refit)
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Unit SET Garnison=50 WHERE ID='$Unite'");
				mysqli_close($con);	
				UpdateData("Officier_em","Credits",-$CT_Refit,"ID",$OfficierEMID);
				UpdateCarac($OfficierEMID,"Avancement",12,"Officier_em");
				UpdateCarac($OfficierEMID,"Note",12,"Officier_em");
                $_SESSION['msg_esc'] = 'Les troupes de défense sont réorganisées.';
			}
            header( 'Location : index.php?view=em_ia');
		}
        /*
         * Reset 5 - Rappeler les pilotes à la base
        */
		elseif($Reset ==5)
		{
			$CT_Discount=Get_CT_Discount($Avancement);
			if($GHQ)$CT_Discount+=4;
			$CT_Refit=12-$CT_Discount;
			if($Trait==1)$CT_Refit-=2;
			if($Credits >=$CT_Refit)
			{
				$con=dbconnecti();
				$reset=mysqli_query($con,"UPDATE Pilote_IA SET Moral=100,Courage=100,Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Cible=0,Avion=0,Alt=0,Endurance=0 WHERE Unit='$Unite'");
				$reset2=mysqli_query($con,"UPDATE Unit SET Mission_Lieu=0,Mission_Type=0,Mission_IA=1 WHERE ID='$Unite'");
				mysqli_close($con);	
				UpdateData("Officier_em","Credits",-$CT_Refit,"ID",$OfficierEMID);
				UpdateCarac($OfficierEMID,"Avancement",12,"Officier_em");
				UpdateCarac($OfficierEMID,"Note",12,"Officier_em");
                $_SESSION['msg_esc'] = 'Les pilotes de l\'escadrille sont rappelés à terre pour faire la fête au mess!<br>Aucun avion, et surtout aucun pilote, n\'est en vol jusqu\'à nouvel ordre.</div>';
                header( 'Location : index.php?view=rapports');
			}
			else
                header( 'Location : index.php?view=em_ia');
		}
        /*
         * Mission
        */
		elseif($Cible >0 and $Type >0 and $Mission_Flight >0)
		{
			$Corps=GetEM_Name($country);
            /*
             * Reset 6 - Demande de mission
            */
			if($Reset ==6)
			{
				SetData("Unit","Mission_Lieu_D",$Cible,"ID",$Unite);
				SetData("Unit","Mission_Type_D",$Type,"ID",$Unite);
                $_SESSION['msg_esc'] = 'Le '.$Corps.' vous informe que votre demande de mission a été validée.';
                header( 'Location : index.php?view=em_ia');
			}
			else
			{
				include_once('./jfv_map.inc.php');
				include_once('./jfv_combat.inc.php');
				function AddAtk_IA($Cible,$Unite,$Pilotes,$Avion,$Arme,$Alt,$Target,$Cycle,$DCA,$Escorte,$Couverture)
				{
					$date=date('Y-m-d G:i');
					$query="INSERT INTO Attaque_ia (`Date`, Lieu, Unite, Pilotes, Avion, Arme, Altitude, Target, Cycle, DCA, Escorte, Couverture)
					VALUES ('$date','$Cible','$Unite','$Pilotes','$Avion','$Arme','$Alt','$Target','$Cycle','$DCA','$Escorte','$Couverture')";
					$con=dbconnecti();
					$ok=mysqli_query($con,$query);
					if(!$ok){
						$msg='Erreur de mise à jour : Lieu='.$Cible.' / Unite='.$Unite.' / Date='.$date.' '.mysqli_error($con);
						mail('binote@hotmail.com','Aube des Aigles: AddAtk_IA Error',$msg);
					}
                    mysqli_close($con);
				}
				/*function AddIntercept($Cible,$Unite,$Pilotes,$Avion,$Alt,$Escorte,$Couverture,$Avion_Nbr,$renc_nbr)
				{
					$date=date('Y-m-d G:i');
					$query="INSERT INTO Intercept (Date, Lieu, Unite, Pilotes, Avion, Altitude, Escorte, Couverture, Enis, Combats)
					VALUES ('$date','$Cible','$Unite','$Pilotes','$Avion','$Alt','$Escorte','$Couverture','$Avion_Nbr','$renc_nbr')";
					$con=dbconnecti();
					$ok=mysqli_query($con,$query);
					mysqli_close($con);
					if(!$ok)
					{
						$msg.="Erreur de mise à jour : Lieu=".$Lieu." / Unite=".$Unite." / Date=".$date." ".mysqli_error($con);
						mail('binote@hotmail.com','Aube des Aigles: AddIntercept Error',$msg);
					}
				}*/
				$Gain_Reput=0;
				$renc_nbr=1; //Nombre minimum de combats si la chasse ennemie couvre la cible
				if($Mission_Flight ==3){
					$Avion=$Avion3;
					$Avion_Nbr_max=$Avion3nbr;
				}
				elseif($Mission_Flight ==2){
					$Avion=$Avion2;
					$Avion_Nbr_max=$Avion2nbr;
				}
				else{
					$Avion=$Avion1;
					$Avion_Nbr_max=$Avion1nbr;
				}
				$con=dbconnecti();
				$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
				$resultl=mysqli_query($con,"SELECT Nom,Latitude,Longitude,Zone,BaseAerienne,Camouflage,Meteo,DefenseAA_temp,ValeurStrat,Flag,Fortification FROM Lieu WHERE ID='$Cible'");
				$resulta=mysqli_query($con,"SELECT Type,Engine,Engine_Nbr,Robustesse,Stabilite,Visibilite,Blindage,ArmeSecondaire,Volets,Radar,ManoeuvreB,ManoeuvreH,Maniabilite,VitesseP FROM Avion WHERE ID='$Avion'");
				$resultu=mysqli_query($con,"SELECT Nom,Avion2,Avion3,Base,Porte_avions,Mission_IA FROM Unit WHERE ID='$Unite'");
				if($resultu)
				{
					while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
					{
						$Nom_Unite=$datau['Nom'];
						$Base=$datau['Base'];
						$Avion2_dca=$datau['Avion2'];
						$Avion3_dca=$datau['Avion3'];
						$Porte_avions=$datau['Porte_avions'];
						$Mission_IA=$datau['Mission_IA'];
					}
					mysqli_free_result($resultu);
				}
				if($resultl)
				{
					while($data=mysqli_fetch_array($resultl,MYSQLI_ASSOC))
					{
						$Nom_Lieu=$data['Nom'];
						$Zone=$data['Zone'];
						$BaseAerienne=$data['BaseAerienne'];
						$Camouflage=$data['Camouflage'];
						$Latitude_c=$data['Latitude'];
						$Longitude_c=$data['Longitude'];
						$meteo=$data['Meteo'];
						$DefenseAA=$data['DefenseAA_temp'];
						$ValStrat=$data['ValeurStrat'];
						$Fortification=$data['Fortification'];
						$Pays_eni=$data['Flag'];
					}
					mysqli_free_result($resultl);
				}
				if($resulta)
				{
					while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
					{
						$HP_avion=$data['Robustesse'];
						$Engine=$data['Engine'];
						$Engine_Nbr=$data['Engine_Nbr'];
						$Blindage=$data['Blindage'];
						$Stab=$data['Stabilite'];
						$Camera=$data['ArmeSecondaire'];
						$VisAvion=$data['Visibilite'];
						$Type_avion=$data['Type'];
						$Volets=$data['Volets'];
						$Radar_Bonus=$data['Radar'];
						$ManB=$data['ManoeuvreB'];
						$ManH=$data['ManoeuvreH'];
						$Mani=$data['Maniabilite'];
						$VitesseP=$data['VitesseP'];
					}
					mysqli_free_result($resulta);
				}
				$resultb=mysqli_query($con,"SELECT Latitude,Longitude FROM Lieu WHERE ID='$Base'");
				$Carbu=mysqli_result(mysqli_query($con,"SELECT Carburant FROM gnmh_aubedesaiglesnet1.Moteur WHERE ID='$Engine'"),0);
				mysqli_close($con);
				if($resultb){
					while($datab=mysqli_fetch_array($resultb,MYSQLI_ASSOC)){
						$Latitude_b=$datab['Latitude'];
						$Longitude_b=$datab['Longitude'];
					}
					mysqli_free_result($resultb);
				}
				if(!$Mission_IA)
				{
					if($Type ==16 or $Type ==27){
						$Nuit=true;
						$meteo-=85;
					}
					if($Long_Mission)
						$moda=1.1;
					else
						$moda=1;
					//Get Data Avion
					$ManAvion_lead=GetMano($ManH,$ManB,1,9999,$Mission_alt,$moda);
					$ManiAvion_lead=GetMani($Mani,1,9999,$moda);
					$PuissAvion_lead=GetPuissance("Avion",$Avion,$Mission_alt,9999,$moda,1,$Engine_Nbr);
					$VitAvion_lead=GetSpeed("Avion",$Avion,$Mission_alt,$meteo,$moda);
					$VitesseP=GetSpeedPi($VitesseP,$Engine_Nbr);
					$VitesseA=GetSpeedA("Avion",$avion,$Mission_alt,$meteo,$Engine_Nbr,$moda);
					$Distance=GetDistance(0,0,$Longitude_b,$Latitude_b,$Longitude_c,$Latitude_c);
					$chemin=$Distance[0];
					if($chemin <10)$chemin=10;
					if($Type ==16 or $Type ==8 or $Type ==23 or $Type ==24 or $Type ==27)
					{
						$renc_nbr=$chemin/50; //Plus la distance entre la base et la cible est grande, plus la chasse ennemie pourra intervenir lors des missions stratégiques
						/*$Long_par_km=$Distance[3]/$Distance[0];
						$Lat_par_km=$Distance[4]/$Distance[0];
						$SensH=$Distance[1];
						$SensV=$Distance[2];*/
					}
					if($Type ==16 or $Type ==23 or $Type ==24 or $Type ==27)
					{
						$Mission_alt_min=$Mission_alt-1000-($meteo*2)-($Radar_Bonus*100);
						$Mission_alt_max=$Mission_alt+2000+($meteo*2)+($Radar_Bonus*100);
						$Couv_field='Couverture_Nuit';
					}
					else
					{
						$Mission_alt_min=$Mission_alt-1500-($meteo*2);
						$Mission_alt_max=$Mission_alt+3000+($meteo*2);
						$Couv_field='Couverture';
						$Radar_Bonus=0;
					}
					if($Porte_avions)
					{
						$Auto_min=1;
						if($Long_Mission)$Auto_min=2;
						UpdateData("Regiment_IA","Autonomie",-$Auto_min,"Vehicule_ID",$Porte_avions);
						$resetconso=1;
					}
					else
					{
					    //Conso
						$Lat_min=$Latitude_b-2;
						$Lat_max=$Latitude_b+2;
						$Long_min=$Longitude_b-5;
						$Long_max=$Longitude_b+5;
						$Conso=($Engine_Nbr*$chemin*$Avion_Nbr_max);///10;
						if($Carbu ==100){
							$Stock_var='Stock_Essence_100';
							$Octane=' Octane 100';
						}
						elseif($Carbu ==1){
							$Stock_var='Stock_Essence_1';
							$Octane=' Diesel';
						}
						else{
							$Stock_var='Stock_Essence_87';
							$Octane=' Octane 87';
						}
						$con=dbconnecti();
						$getflotted=mysqli_result(mysqli_query($con,"SELECT d.ID FROM Regiment_IA as r,Depots as d,Pays as p WHERE r.Pays=p.Pays_ID AND p.Faction='$Faction' AND r.Lieu_ID='$Base' AND r.ID=d.Reg_ID AND d.".$Stock_var." >='$Conso'"),0);
						if(!$getflotted){
							$getdepot=mysqli_result(mysqli_query($con,"SELECT l.ID FROM Lieu as l,Pays as p WHERE l.Flag=p.Pays_ID AND p.Faction='$Faction' AND
							(l.ID='$Base' OR ((l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max'))) AND l.".$Stock_var." >='$Conso' ORDER BY l.".$Stock_var." DESC LIMIT 1"),0);
							$resetconso=mysqli_query($con,"UPDATE Lieu SET ".$Stock_var."=".$Stock_var."-'$Conso' WHERE ID='".$getdepot."'");
						}
						else
							$resetconso=mysqli_query($con,"UPDATE Depots SET ".$Stock_var."=".$Stock_var."-'$Conso' WHERE ID='".$getflotted."'");
						mysqli_close($con);
					}
					if(!$resetconso)
						$mes.="<div class='alert alert-danger'>Le ".$Corps." vous informe que votre ordre de mission n'a pas été validé par manque de carburant dans les dépôts!<br>".$Conso."L".$Octane." étaient nécessaires pour cette mission.</div>";
					else
					{
						AddXPAvionIA($Avion,$Unite,1);
						$con=dbconnecti();
						$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Actif='1'"),0);
						$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Cible='$Cible' AND j.".$Couv_field."='$Cible' AND j.Avion >0 AND p.Faction<>'$Faction' AND j.Actif='1' AND (j.Alt BETWEEN '$Mission_alt_min' AND '$Mission_alt_max')"),0);
						if(!$Radar_Bonus)
							$Escorte_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Cible='$Cible' AND j.Escorte='$Cible' AND j.Avion >0 AND p.Faction='$Faction' AND j.Actif='1' AND (j.Alt BETWEEN '$Mission_alt_min' AND '$Mission_alt_max')"),0);
						if(!$Pilotes or $Pilotes <11) //Génération de nouveaux pilotes si l'unité n'en possède aucun ou -11
						{
							$Pilotes_Max=12-$Pilotes;
							$today=date('Y-m-d');
							$Unit_Reputation=GetData("Unit","ID",$Unite,"Reputation");
							if($country ==1)
							{
								$prenoms=array("Fritz","Heinrich","Adolf","Andreas","Siegfried","Heinz","Lothar","Wilhelm","Friedrich","Jozef","Helmut","Joachim","Hans","Rudolf","Albert","Bernhard","Christoph","Konrad","Dieter","Eberhard","Eckhard","Egon","Edmund","Emil","Erich","Ernst","Erwin","Eugen","Felix","Franz","Friedhelm","Georg","Gerald","Gert","Gottfried","Gunther","Gustav","Harald","Herbert","Herman","Horst","Hubert","Jochen","Johan","Jurgen","Karl","Karsten","Kurt","Lars","Lorenz","Ludwig","Manfred","Lukas","Markus","Martin","Matthias","Max","Nikolaus","Oskar","Oswald","Ralph","Reinhard","Rodolf","Rudiger","Sebastian","Joseph","Siegbert","Siegmund","Stefan","Theodor","Thomas","Thorben","Torsten","Ulrich","Uwe","Viktor","Volk","Waldemar","Walter","Werner","Wilfried","Wolfgang","Wolfram","Wulf","Otto","Ottmar","Gerhard","Klaus");
								$noms=array("Bach","Hirsch","Jung","Schmidt","Schulz","Stein","Steiner","Strauss","Maier","Hermann","Dietrich","Gottlieb","Wulf","Fritz","Links","Linker","Allofs","Berger","Neumann","Rosenberg","Friedrich","Feldmann","Eberhard","Hammerstein","Hennings","Hohenberg","Herman","Klein","Ehrlich","Hartmann","Fromm","Huber","Krauss","Ackermann","Bauer","Baumann","Becker","Eisenmann","Fischer","Hoffmann","Jäger","Kauffmann","Keller","Koch","Krämer","Meyer","Müller","Pfeiffer","Rebmann","Schäfer","Schneider","Schreiber","Schumacher","Vogt","Wagner","Weber","Weidmann","Zimmermann","Lehmann","Meister","Bodmann","Richter","Aldermann","Bergmann","Herzog","Schwartz","Wolff","Zimmer","Waldener","Wiesemann","Böhm","Pohl","Bayer","Adenauer","Sachs","Lindeberg","Rosenthal","Fuchs","Altmann","Baumgartner","Baumgarten","Berck","Bischoff","Burgmeister","Eberling","Falkenbach","Wohlfahrt","Kohler","Walter");
							}
							elseif($country ==2 or $country ==7)
							{
								$prenoms=array("Adam","Adrian","Al","Alan","Alexander","Alexis","Alfred","Albert","Allan","Ambrose","Andrew","Andy","Anthony","Archibald","Arnold","Arthur","Ashley","Austin","Baldric","Baldwin","Barney","Barrett","Bart","Basil","Beau","Benjamin","Benny","Bertrand","Bill","Billy","Blake","Bob","Brad","Bradley","Brendon","Brian","Brice","Bruce","Bryan","Buck","Burke","Cameron","Carl","Carter","Casey","Cassius","Casimir","Cedric","Chad","Charles","Charlie","Chester","Chris","Christian","Christopher","Chuck","Clarence","Clay","Clifford","Clinton","Connor","Cooper","Damon","Dan","Danny","Darrell","David","Dean","Denis","Denzel","Derek","Dominic","Donald","Donovan","Douglas","Dudley","Dustin","Dwayne","Earl","Eddy","Edward","Edmund","Elijah","Elliott","Elmer","Elroy","Elton","Elvis","Emmett","Eric","Ernest","Errol","Ethan","Eugene","Evan","Felix","Ferdinand","Francis","Fred","Geoffrey","George","Gerald","Gerard","Gilbert","Glenn","Gordon","Graeme","Graham","Hardy","Harold","Harry","Harvey","Henry","Herman","Homer","Horace","Howard","Hubert","Ian","Indiana","Irvin","Jack","James","Jamie","Jared","Jason","Jasper","Jay","Jefferson","Jeremy","Jerry","Jesse","Jim","Joel","Joseph","John","Johnnie","Jonathan","Jordan","Justin","Kelvin","Kenneth","Kevin","Kyle","Lambert","Lamar","Larry","Laurel","Lee","Leon","Leonard","Lewis","Layton","Lyndon","Linford","Louis","Lucas","Luke","Malcolm","Mark","Martin","Marvin","Matt","Matthew","Max","Michael","Mike","Mitchell","Montgomery","Morris","Murphy","Murray","Nathan","Neil","Neville","Newton","Nicholas","Nick","Nigel","Norbert","Oliver","Orson","Oswald","Oscar","Oswald","Patrick","Pat","Paul","Peter","Pete","Philip","Phil","Porter","Prosper","Ralph","Randy","Raymond","Richard","Rick","Ritchie","Rob","Robert","Robin","Rodolph","Roger","Rolph","Roy","Russell","Ryan","Sam","Samuel","Scott","Sebastian","Sean","Seymour","Sheldon","Sherman","Silvester","Sonny","Simon","Steven","Stephen","Stewart","Stuart","Sydney","Taylor","Ted","Terence","Theodore","Thomas","Tim","Timothy","Todd","Tom","Travis","Trevor","Tyron","Uther","Vance","Vernon","Vincent","Vince","Waldo","Walker","Wallace","Walter","Warren","Wayne","Wesley","Will","William","Winston","Woodrow","Xavier","Zack","Morgan","Owen","Frank","Franck");
								$noms=array("Alexander","Adams","Addams","Anderson","Austin","Baldwin","Barclay","Barnes","Beckett","Bishop","Blossom","Blair","Blake","Blythe","Brown","Bush","Chase","Connor","Cook","Cooke","Cooper","Cole","Johnson","Jones","MacAdams","Smith","Simons","MacIntyre","Rose","Boyd","Bradley","Brady","Brand","Brice","Brody","Brooke","Brooks","Byron","Byrne","Burton","Barton","Black","Callahan","Camron","Carter","Channing","Chadwick","Clark","Clayton","Clinton","Coleman","Collin","Connell","Connor","Cooper","Curtis","Dalton","Darwin","David","Davis","Douglas","Dawson","Dean","Delaney","Denver","Denis","Dexter","Dixon","Drake","Driscoll","Dudley","Duke","Chambers","Jordan","Edison","Eliott","Emerson","Evans","Flanagan","Fletcher","Ford","Forrest","Flower","Fox","Fraser","Freeman","Fulton","Gallagher","Garnet","Garrett","Gavin","Goodwin","Graham","Gray","Grant","Grey","Gordon","Green","Hall","Hamilton","Hammond","Hank","Hardy","Harmon","Heaven","Henderson","Hewitt","Hill","Hodge","Hodgson","Holden","Howard","Irvine","Jackson","Jefferson","Joyce","Johnson","Keane","Keaton","Keegan","Kelly","Kennedy","Kendall","Kent","Kerry","King","Kingsley","Kingston","Kinley","Kinsley","Kirby","Knox","Lake","Lee","Lemoine","Leland","Leroy","Lewis","Lindon","Long","Mackenzie","Maddox","Maitland","Major","Mallory","Marshall","Martin","Mitchell","Morris","Murphy","Murray","Miller","Maxwell","Napier","Nash","Norton","Nowell","Norris","Page","Palmer","Payton","Oswald","Paxton","Pearce","Patton","Percy","Prince","Quinn","Raleigh","Randall","Read","Red","Rex","Ryan","Richard","Richards","Roberts","Robinson","Scott","Saxon","Scarlett","Sparrow","Spencer","Sterling","Stevens","Stephen","Taylor","Travis","Wilson","Porter","Walker","Wallace","Ward","Wayne","Weaver","Webster","White","Williams","Wills","Wilson","Winchester","Young","Lowe","Whitmore","Fenton","Crawford","Howe","Wood");
							}
							elseif($country ==4)
							{
								$prenoms=array("Abel","Adam","Achille","Adolphe","Adrien","Aimé","Albert","Alexandre","Alphonse","Ambroise","Amaury","Amédée","André","Antoine","Antonin","Armand","Arthur","Auguste","Augustin","Aurélien","Axel","Aymeric","Baptiste","Barnabé","Bathélemy","Basile","Bastien","Baudouin","Benjamin","Benoit","Bernard","Bertrand","Blaise","Boniface","Burno","Camille","Cédric","Célestin","Charles","Christian","Christophe","Claude","Clément","Colin","Constant","Constantin","Corentin","Cyriaque","Cyril","Damien","Daniel","David","Denis","Désiré","Didier","Dieudonné","Edouard","Emile","Emilien","Eric","Ernest","Etienne","Eugène","Eustache","Fabien","Fabrice","Felix","Ferdinand","Fernand","Firmin","Florent","Fortuné","Francis","François","Frédéric","Gabriel","Gaël","Gaston","Gaspard","Gautier","Georges","Gérald","Germain","Ghislain","Gervais","Gilles","Grégoire","Guillaume","Gustave","Guy","Gérard","Gilbert","Hector","Henri","Honoré","Hubert","Hugues","Innocent","Isidore","Jacques","Jean","Jérémie","Jérôme","Jocelyn","Joël","Jonathan","Joseph","Josselin","Julien","Jules","Justin","Lambert","Laurent","Léon","Léopold","Lionel","Louis","Lucien","Ludovic","Marc","Marcel","Marcelin","Manu","Marius","Martin","Matthieu","Maurice","Max","Maxime","Martial","Michel","Nathan","Nestor","Nicolas","Noël","Norbert","Octave","Olivier","Oscar","Pascal","Patrice","Paul","Patrick","Philippe","Pierre","Prosper","Quentin","Raoul","Raphael","Raymond","Régis","Rémi","Renaud","René","Richard","Robert","Rodolphe","Roger","Roland","Romain","Romuald","Samuel","Sébastien","Serge","Séverin","Simon","Stanislas","Stéphane","Sylvain","Tanguy","Théodore","Théophile","Thibault","Thierry","Thomas","Timothée","Tristan","Urbain","Uther","Valentin","Valéry","Victor","Vincent","Vital","Xavier","Yannick","Yves","Yvon");
								$noms=array("André","Bernard","Blanc","Bonnet","Boulanger","Carpentier","Charpentier","Chevalier","Clément","Colin","David","Dubois","Dumont","Dupond","Dupont","Durand","Fournier","François","Fontaine","Gauthier","Garnier","Girard","Guérin","Henry","Gérard","Lambert","Laurent","Legrand","Lefevre","Leroy","Mathieu","Maréchal","Marchal","Martin","Masson","Mercier","Meunier","Michel","Moreau","Morel","Morin","Nicolas","Perrin","Barbier","Brun","Dumas","Leroux","Pierre","Renard","Arnaud","Rolland","Caron","Giraud","Leclerc","Vidal","Bourgeois","Renaud","Lemoine","Picard","Gaillard","Philippe","Lacroix","Dupuis","Olivier","Louis","Charles","Rivière","Guillaume","Moulin","Dumoulin","Berger","Lecompte","Menard","Deschamps","Vasseur","Jacquet","Collet","Prevost","Poirier","Huet","Pons","Carré","Perrot","Barre","Boucher","Bailly","Hervé","Poulain","Etienne","Lebrun","Pasquier","Cordier","Humbert","Gillet","Bouvier","Levèque","Jacob","Germain","Millet","Lesage","Leblanc","Alexandre","Perrier","Bertin","Pelletier","Bouchet","Lemaitre","Pichon","Pignon","Lamy","Georges","Devaux","Delvaux","Langlois","Tessier","Joubert","Legros","Guichard","Carlier","Delattre","Lejeune","Sauvage","Michaud","Leduc","Buisson","Laporte","Courtois","Vaillant","Lefort","Couturier","Bourdon","Dupré","Lacombe","Laroche","Petit","Richard","Robert","Robin","Rousseau","Roussel","Roux","Simon","Thomas","Vincent","Denis","Lemaire","Duval","Joly","Roger","Roche","Roy","Noël","Lucas","Marchand","Dufour","Blanchard","Marie");
							}
							elseif($country ==8)
							{
								$prenoms=array("Andreï","Alekseï","Anatoly","Alexandr","Branislav","Boris","Bogdan","Damir","Dmitri","Gennady","Georgy","Igor","Ivan","Jaroslav","Leonid","Lubomir","Ludomir","Maxim","Miroslav","Mischa","Nikolaï","Oleg","Osip","Pavel","Piotr","Radomir","Radoslav","Rasim","Ratimir","Ruslan","Sambor","Stanimir","Stanislav","Rinat","Sacha","Sergeï","Timofey","Vadim","Valery","Venimir","Viktor","Vitomir","Vladan","Vladimir","Vladislav","Volodia","Yefim","Yegor","Yuri","Youri","Zinovy");
								$noms=array("Smirnov","Ivanov","Kuznetsov","Popov","Sokolov","Lebedev","Kozlov","Novikov","Morozov","Petrov","Volkov","Solovyov","Vasilyev","Zaytsev","Pavlov","Semyonov","Golubev","Vinogradov","Bogdanov","Vorobyov","Stepanov","Melnyk","Kovalenko","Bondarenko","Shevchenko","Kovalchuk","Kravchenko","Tkachenko","Lysenko","Rudenko","Savchenko","Petrenko","Marchenko","Moroz","Shevchuk","Beridze","Mammadov","Aliyev","Hasanov","Huseynov","Guliyev","Hajiyev","Kozlov");
							}
							elseif($country ==9)
							{
								$prenoms=array("Ado","Aiichiro","Akahiko","Akainu","Akamaru","Aki","Aoki","Arata","Asayoshi","Atsuhiko","Ayahito","Bunta","Bunjiro","Chikatoshi","Choji","Chojiro","Daigo","Daiki","Daisuke","Eiichiro","Fubuki","Fumihiko","Gaku","Genjiro","Genkishi","Gosuke","Hachi","Harunobu","Hayate","Hidan","Hikari","Hikaru","Hinata","Hirobumi","Hiroki","Hiromi","Hitoshi","Hirosuke","Hotaru","Ichiro","Iemoto","Ikura","Issei","Iwao","Izumi","Jiro","Junji","Kagami","Kaito","Kansai","Katsumi","Katsuo","Kaze","Kazuo","Kaïdo","Kazuki","Kaneyoshi","Keigo","Keiji","Keisuke","Ken","Kenzo","Kenshi","Kenshin","Kintaro","Kiseki","Koda","Kokei","Komei","Koseki","Kuma","Kuniyoshi","Kurogane","Makoto","Masamune","Masaru","Masashi","Masato","Masatoshi","Minato","Muro","Nagato","Nagatsu","Natsume","Neji","Nobunaga","Nowaki","Orochimaru","Oichiro","Ringo","Rintaro","Ryo","Ryu","Saburo","Sai","Satoshi","Shino","Shinobu","Shun","Suzaku","Shingo","Shigeru","Takeshi","Takahiro","Tatsuki","Tatsumi","Taku","Takumi","Tetsu","Toshiro","Taro","Toshi","Takeo","Tsuyoshi","Ukyo","Waichiro","Wataru","Yamamoto","Yamato","Yoshiro","Yusuke","Zakuro","Zenjiro","Zuko");
								$noms=array("Suzuki","Satou","Takahashi","Yamato","Yamamoto","Tanaka","Watanabe","Itou","Nakamura","Kobayashi","Saitou","Ito","Kato","Yoshida","Yamada","Sasaki","Yamagushi","Matsumoto","Inoue","Kimura","Hayashi","Shimizu","Yamazaki","Mori","Abe","Ikeda","Hashimoto","Yamashita","Ishikawa","Nakajima","Maeda","Fujita","Ogawa","Goto","Okada","Hasegawa","Murakami","Kondo","Ishii","Sakamoto","Endo","Aoki","Fuji","Nishimura","Fukuda","Ota","Miura","Fujiwara","Okamoto","Matsuda","Nakagawa","Nakano","Harada","Ono","Tamura","Takeushi","Kaneko","Wada","Nakayama","Ishida","Morita","Shibata","Sakai","Kudo","Yokoyama","Miyazaki","Miyamoto","Uchida","Takagi","Ando","Ohno","Maruyama");
							}
							elseif($country ==6)
							{
								$prenoms=array("Abelardo","Adolfo","Adriano","Agostino","Antonio","Alberto","Aldo","Angelo","Bartolomeo","Benito","Bernardo","Bonifacio","Bruno","Calogero","Carlo","Celestino","Cesare","Claudio","Clemente","Costantino","Davide","Demetrio","Desiderio","Diego","Dino","Donatello","Emilio","Enzo","Ernesto","Erico","Fabiano","Fabio","Fabrizio","Fausto","Felice","Fernando","Filippo","Flaminio","Flavio","Francesco","Franco","Gabriele","Gaetano","Galeazzo","Galliano","Gennaro","Gentile","Giacomo","Gilberto","Gino","Giordano","Giorgio","Giovanni","Giulio","Giuseppe","Giuliano","Giustino","Goffredo","Graziano","Gottardo","Gregorio","Guido","Gustavo","Guglielmo","Ignazio","Landolfo","Leandro","Leonardo","Leopoldo","Libero","Liborio","Livio","Lombardo","Lorenzo","Luciano","Lucio","Luigi","Lisandro","Manfredo","Manuele","Marcello","Marco","Mariano","Mario","Martino","Massimo","Mauro","Maurizio","Michele","Moreno","Natale","Nerio","Nicola","Onorato","Onofrio","Orfeo","Orlando","Osvaldo","Ottavio","Ovidio","Paolo","Pasquale","Patrizio","Pellegrino","Pietro","Pino","Placido","Primo","Prospero","Raffaele","Raffaelo","Raimondo","Renato","Renzo","Riccardo","Rinaldo","Roberto","Rocco","Rodolfo","Rodrigo","Rolando","Romeo","Rosario","Romano","Salvatore","Sandro","Santino","Sebastiano","Serafino","Sergio","Severino","Silvio","Silvestro","Simone","Stefano","Teo","Tino","Tonio","Tristano","Ubaldo","Uberto","Umberto","Urbano","Valentino","Valerio","Valente","Vincenzo","Virgilio","Vitale","Vittorio","Zaccaria","Gandolfo","Alessio","Aurelio","Arrigo","Armando","Carmelo","Cosimo","Dario","Emiliano","Gianni");
								$noms=array("Rossi","Russo","Ferrari","Bianchi","Romano","Colombo","Ricci","Marino","Greco","Bruno","Gallo","Conti","DeLuca","DiLuca","Mancini","Costa","Giordano","Rizzo","Lombardi","Moretti","Dacosta","Agnelli","Esposito","Fontana","Barbieri","Santoro","Mariani","Rinaldi","Caruso","Ferrara","Galli","Martini","Leone","Gentile","Martinelli","Vitale","Lombardo","Coppola","De Santis","Marchetti","Conte","Ferraro","Ferri","Fabbri","Bianco","Marini","Grasso","Valentini","Messina","Sala","Gatti","Pellegrini","Palumbo","Sanna","Farina","Rizzi","Monti","Cattaneo","Morelli","Amato","Sivestri","Mazza","Testa","Albanese","Grassi","Pellegrino","Carbone","Giuliani","Benedetti","Barone","Rossetti","Caputo","Montanari","Guerra","Palmieri","Bernardi","Martino","Fiore","Ferretti","Bellini","Riva","Donati","Battaglia","Sartori","Neri","Costantini","Milani","Pagano","Ruggiero","Ruggeri","Orlando","Negri","Mantovani","Fellini");
							}
							elseif($country ==3)
							{
								$prenoms=array("Abel","Adam","Achille","Adolphe","Adrien","Aimé","Albert","Alexandre","Alphonse","Ambroise","Amaury","Amédée","André","Antoine","Antonin","Armand","Arthur","Auguste","Augustin","Aurélien","Axel","Aymeric","Baptiste","Barnabé","Bathélemy","Basile","Bastien","Baudouin","Benjamin","Benoit","Bernard","Bertrand","Blaise","Boniface","Burno","Camille","Cédric","Célestin","Charles","Christian","Christophe","Claude","Clément","Colin","Constant","Constantin","Corentin","Cyriaque","Cyril","Damien","Daniel","David","Denis","Désiré","Didier","Dieudonné","Edouard","Emile","Emilien","Eric","Ernest","Etienne","Eugène","Eustache","Fabien","Fabrice","Felix","Ferdinand","Fernand","Firmin","Florent","Fortuné","Francis","François","Frédéric","Gabriel","Gaël","Gaston","Gaspard","Gautier","Georges","Gérald","Germain","Ghislain","Gervais","Gilles","Grégoire","Guillaume","Gustave","Guy","Gérard","Gilbert","Hector","Henri","Honoré","Hubert","Hugues","Innocent","Isidore","Jacques","Jean","Jérémie","Jérôme","Jocelyn","Joël","Jonathan","Joseph","Josselin","Julien","Jules","Justin","Lambert","Laurent","Léon","Léopold","Lionel","Louis","Lucien","Ludovic","Marc","Marcel","Marcelin","Manu","Marius","Martin","Matthieu","Maurice","Max","Maxime","Martial","Michel","Nathan","Nestor","Nicolas","Noël","Norbert","Octave","Olivier","Oscar","Pascal","Patrice","Paul","Patrick","Philippe","Pierre","Prosper","Quentin","Raoul","Raphael","Raymond","Régis","Rémi","Renaud","René","Richard","Robert","Rodolphe","Roger","Roland","Romain","Romuald","Samuel","Sébastien","Serge","Séverin","Simon","Stanislas","Stéphane","Sylvain","Tanguy","Théodore","Théophile","Thibault","Thierry","Thomas","Timothée","Tristan","Urbain","Uther","Valentin","Valéry","Victor","Vincent","Vital","Xavier","Yannick","Yves","Yvon","Jacob","Arie","Nicolaas","Alexander","Pieter","Bart","Frederik","Dirk","Christiaan","Ivo","Ludo","Marc","Ruud","Martijn","Alfons","Willem","Jan","Frans","Koen","Hans","Gert","Gustav","Herman","Hubert","Johannes","Jurgen","Carl","Kurt","Martin","Matthias","Max","Ralph","Sebastian","Joseph","Stefan","Thomas","Walter","Jeroen","Michiel","Wim","Mathieu","Peter","Paul","Joop","Henri","Hans","Harry","Maurice","Jef","Theodoor","Mathijs","Leo","Jos");
								$noms=array("André","Bernard","Blanc","Bonnet","Boulanger","Carpentier","Charpentier","Chevalier","Clément","Colin","David","Dubois","Dumont","Dupond","Dupont","Durand","Fournier","François","Fontaine","Gauthier","Garnier","Girard","Guérin","Henry","Gérard","Lambert","Laurent","Legrand","Lefevre","Leroy","Mathieu","Maréchal","Marchal","Martin","Masson","Mercier","Meunier","Michel","Moreau","Morel","Morin","Nicolas","Perrin","Barbier","Brun","Dumas","Leroux","Pierre","Renard","Arnaud","Rolland","Caron","Giraud","Leclerc","Vidal","Bourgeois","Renaud","Lemoine","Picard","Gaillard","Philippe","Lacroix","Dupuis","Olivier","Louis","Charles","Rivière","Guillaume","Moulin","Dumoulin","Berger","Lecompte","Menard","Deschamps","Vasseur","Jacquet","Collet","Prevost","Poirier","Huet","Pons","Carré","Perrot","Barre","Boucher","Bailly","Hervé","Poulain","Etienne","Lebrun","Pasquier","Cordier","Humbert","Gillet","Bouvier","Levèque","Jacob","Germain","Millet","Lesage","Leblanc","Alexandre","Perrier","Bertin","Pelletier","Bouchet","Lemaitre","Pichon","Pignon","Lamy","Georges","Devaux","Delvaux","Langlois","Tessier","Joubert","Legros","Guichard","Carlier","Delattre","Lejeune","Sauvage","Michaud","Leduc","Buisson","Laporte","Courtois","Vaillant","Lefort","Couturier","Bourdon","Dupré","Lacombe","Laroche","Petit","Richard","Robert","Robin","Rousseau","Roussel","Roux","Simon","Thomas","Vincent","Denis","Lemaire","Duval","Joly","Roger","Roche","Roy","Noël","Lucas","Marchand","Dufour","Blanchard","Marie","Steenkamp","Van de Putte","Prins","Van Overveldt","Neuman","Nieuwenhuijs","Muller","Mosselman","Moens","Van Loo","Van Leeuwen","Lambrechts","Lamberts","Kuiper","Kluit","Van Kempen","Coopmans","De Jong","De Jongh","Van Houten","Van Hoorn","Hoffmann","Van Heusden","Hasselman","Haan","De Witt","Groskamp","De Graaf","De Pauw","Van Eyk","Engelbrecht","Van der Elst","Eeckhout","Van Eck","Van Dijk","Van Doorne","Donker","Van Dam","Cramer","Costerman","Coenen","De Clercq","Bakker","Wijnaendts","Vandewall","Westerman","Vos","De Vries","Van der Voort","Van Bommel","Visser","Verloren","Vermeer","Verhagen","Verbrugge","Van de Velde","Janssens","Bakeland","Jacobs","Maas","Maes","Janssen","Huysmans","Huisman","Peeters","Theunissen","Van Rooy","Poels","Basten","Vandeven","Vanderheijden","Driessen","Snijders","Cremers","Kuijper","Nelissen","Goossens","Cuijpers","Vogels","Boers","De Ruijter","Smeets","Smets");
							}
							elseif($country ==5)
							{
								$prenoms=array("Hendrik","Petrus","Jacobus","Antonius","Gerardus","Adrianus","Hendrikus","Franciscus","Theodorus","Jacob","Arie","Nicolaas","Alexander","Pieter","Bart","Frederik","Dirk","Christiaan","Ivo","Ludo","Marc","Ruud","Martijn","Alfons","Willem","Jan","Frans","Koen","Hans","Gert","Gustav","Herman","Hubert","Johannes","Jurgen","Carl","Kurt","Martin","Matthias","Max","Ralph","Sebastian","Joseph","Stefan","Thomas","Walter","Jeroen","Michiel","Wim","Mathieu","Peter","Paul","Joop","Henri","Hans","Harry","Maurice","Jef","Theodoor","Mathijs","Leo","Jos");
								$noms=array("Steenkamp","Van de Putte","Prins","Van Overveldt","Neuman","Nieuwenhuijs","Muller","Mosselman","Moens","Van Loo","Van Leeuwen","Lambrechts","Lamberts","Kuiper","Kluit","Van Kempen","Coopmans","De Jong","De Jongh","Van Houten","Van Hoorn","Hoffmann","Van Heusden","Hasselman","Haan","De Witt","Groskamp","De Graaf","De Pauw","Van Eyk","Engelbrecht","Van der Elst","Eeckhout","Van Eck","Van Dijk","Van Doorne","Donker","Van Dam","Cramer","Costerman","Coenen","De Clercq","Bakker","Wijnaendts","Vandewall","Westerman","Vos","De Vries","Van der Voort","Van Bommel","Visser","Verloren","Vermeer","Verhagen","Verbrugge","Van de Velde","Janssens","Bakeland","Jacobs","Maas","Maes","Janssen","Huysmans","Huisman","Peeters","Theunissen","Van Rooy","Poels","Basten","Vandeven","Vanderheijden","Driessen","Snijders","Cremers","Kuijper","Nelissen","Goossens","Cuijpers","Vogels","Boers","De Ruijter","Smeets","Smets");
							}
							elseif($country ==18)
							{
								$prenoms=array("Alin","Alexandru","Adrian","Adam","Dragomir","Doru","Danut","Daniel","Cristian","Costica","Cosmin","Corneliu","Claudiu","Cezar","Aurel","Anton","Andrei","Mircea","Martin","Marius","Marin","Marian","Lucian","Liviu","Laurentiu","Iulian","Ionel","Ioan","Grigor","Gabriel","Florin","Felix","Eugen","Emil","Dumitru","Constantin","Ilie","Ion","Mihai","Petru","Pop","Vlad","Radu","Vladoiu","Viorel","Victor","Vasil","Valeriu","Valentin","Teodor","Stefan","Sorin","Silviu","Sandu","Razvan","Paul","Ovidiu","Nicu","Nicolae","Neculai");
								$noms=array("Alexandrescu","Alimanescu","Anghel","Antonescu","Balanescu","Balasko","Berbec","Blas","Bobescu","Botezariu","Brutar","Bucatar","Carnatar","Cioban","Constantinescu","Danielescu","Dragoman","Duca","Forascu","Gavril","Georgiu","Gheorghe","Gheorghiu","Gherman","Gregoriu","Ioans","Ionescu","Laptar","Luca","Lupescu","Lupu","Macelar","Maiorescu","Martinescu","Matescu","Melitaru","Moldavan","Morariu","Munteanu","Negrescu","Negus","Negustor","Palariar","Petre","Petrescu","Pietru","Popescu","Pretorian","Roman","Rotaru","Russescu","Sarbescu","Spaniol","Tampiar","Tesador","Tesator","Tomescu","Turcus","Vacarescu","Vulpescu","Walkil","Zidar");
							}
							elseif($country ==19)
							{
								$prenoms=array("Tamas","Tibor","Timot","Titusz","Tobias","Ugor","Ulaszlo","Valter","Viktor","Vladimir","Zoltan","Zsigmond","Zsolt","Narcisz","Orban","Oszka","Otto","Paszkal","Peter","Piusz","Rajmund","Rezso","Robert","Sandor","Sebestyen","Surany","Szabolcs","Szaniszlo","Silveszter","Lajos","Laszlo","Lorand","Lukacs","Mark","Matyas","Mihaly","Miklos","Miksa","Mozes","Kalman","Kardos","Kemenes","Kolos","Konrad","Konstantin","Kristof","Krisztian","Istvan","Izsak","Jakab","Janos","Jeromos","Jonas","Jozsef","Jozsua","Jusztin","Egon","Emanuel","Emil","Erik","Ervin","Fabian","Farkas","Ferenc","Florian","Fodor","Gabor","Gaspar","Gusztav","Gyorgy","Hilariusz","Balazs","Barabas","Barnabas","Benjamin","Bogdan","Bonifac","Bruno","Csak","Damjan","Daniel","David","Demeter","Dominik","Domotor","Abel","Abraham","Adam","Adolf","Adorjan","Agoston","Aladar","Alajos","Albert","Alfonz","Alfred","Anaztaz","Andras","Anzelm","Arisztid","Armand","Armin","Arnold","Artur","Attila","Aurel");
								$noms=array("Nagy","Szabo","Kovacs","Toth","Horvath","Kiss","Molnar","Varga","Farkas","Olah","Papp","Balogh","Meszaros","Fulop","Nemeth","Takacs","Gal","Juhasz","Magyar","Racz");
							}
							elseif($country ==20)
							{
								$prenoms=array("Mikko","Miika","Juho","Luukas","Teemu","Santtu","Aleksi","Eetu","Tuukka","Riku","Oskar","Veeti","Otto","Joona","Antto","Perttu","Jaari","Aarne","Aki","Ari","Christian","Einari","Hanno","Hannu","Heikki","Heino","Heimo","Henrik","Lisakki","Lisari","Ilmari","Ilmo","Ilpo","Jaakima","Jaakoppi","Jalmari","Jami","Jari","Jarkko","Jukka","Juhani","Kaapi","Kaapo","Kaj","Kalevi","Karri","Kauko","Kimi","Kustavi","Lari","Matti","Mikki","Olavi","Olle","Paavo","Petter","Paivo","Reino","Reko","Sakari","Samppa","Sami","Sulevi","Teppo","Ukko","Uuno","Valto","Veeti","Veikko","Ville","Voitto","Yrjana");
								$noms=array("Korhonen","Laine","Virtanen","Kinnunen","Nieminen","Makinen","Makela","Jarvinen","Salmi","Lehtinen","Heikkila","Heikkinen","Heinonen","Karjalainen","Lehtonen","Tuominen","Koskinen","Laitinen","Mustonen","Ahonen","Hakkinen");
							}
							elseif($country ==35)
							{
								$prenoms=array("Henrik","Hendrik","Magnus","Karl","Gustav","Martin","Sander","Simen","Marius","Jonas","Andreas","Thomas","Eirik","Fredrik","Robin","Harald","Joakim","Sindre","Aleksander","Petter","Daniel","Ole","Ole-Martin","Gunnar","Ole-Gunnar","Jan","Arne","Bjarne","Karl","Per","Bjorn","Lars","Kjell","Knut","Svein","Hans","Geir","Tor","Morten","Rune","Trond","Harald","Olav","Rolf","Leif");
								$noms=array("Jorgensen","Magnussen","Hansen","Johansen","Olsen","Larsen","Andersen","Nilsen","Pedersen","Kristiansen","Jensen","Karlsen","Johnsen","Eriksen","Berg","Petersen","Jacobsen","Andreasen","Hagen","Lund","Hendriksen","Sorensen");
							}
							$query="INSERT INTO Pilote_IA (Nom,Pays,Engagement,Pilotage,Acrobatie,Navigation,Tactique,Tir,Vue,Avancement,Skill,Unit,Unit_Ori)";		
							if($Unit_Reputation >50000)
							{
								$Grade=5000;
								$Pilotage=125;
								$Skill=100;
							}
							elseif($Unit_Reputation >25000)
							{
								$Grade=2000;
								$Pilotage=100;
								$Skill=75;
							}
							elseif($Unit_Reputation >10000)
							{
								$Grade=500;
								$Pilotage=75;
								$Skill=50;
							}
							else
							{
								$Grade=0;
								$Pilotage=50;
								$Skill=25;
							}
							$Skills_1=array(1,2,6,10,14,18,22,26);
							$Skills_2=array(3,7,11,15,19,23,27,34,39,42,129,131);
							$Skills_3=array(4,8,12,16,20,24,28,30,32,35,37,40,43,45);
							$Skills_4=array(5,9,13,17,21,25,29,31,33,36,38,41,44);
							for($i=1;$i<=$Pilotes_Max;$i++)
							{
								$Seed_Rang=mt_rand(0,10);
								if($Seed_Rang ==10)
									$Skill_p=$Skills_4[mt_rand(0,count($Skills_4)-1)];
								elseif($Seed_Rang >=8)
									$Skill_p=$Skills_3[mt_rand(0,count($Skills_3)-1)];
								elseif($Seed_Rang >=5)
									$Skill_p=$Skills_2[mt_rand(0,count($Skills_2)-1)];
								else
									$Skill_p=$Skills_1[mt_rand(0,count($Skills_1)-1)];
								$Pilote_nom=$prenoms[array_rand($prenoms)]." ".$noms[array_rand($noms)];
								if($i ==1)$query.="VALUES ";
								$query.="('$Pilote_nom','$country','$today','$Pilotage','$Skill','$Skill','$Skill','$Skill','$Skill','$Grade','$Skill_p','$Unite','$Unite')";
								if($i <$Pilotes_Max)$query.=",";
							}
							$ok=mysqli_query($con,$query);
							/*if($ok)
								$mail="<p>[Debug] Pilotes IA créés avec succès!</p>";
							else
								$mail="<p>[Debug] Erreur de création des pilotes IA !</p>";*/
							//mysqli_close($con);
							if($ok)
								$mes.='Les pilotes IA sont créés avec succès!<br>';
							else
								$mes.='[ERREUR] Erreur de création des pilotes IA !';
						}
                        $skillsp=mysqli_query($con,"SELECT Skill FROM Pilote_IA WHERE Unit='$Unite' AND Moral >0 AND Courage >0 AND Endurance <10 AND Actif=1 ORDER BY Reputation DESC LIMIT ".$Avion_Nbr_max."");
						$resultp=mysqli_query($con,"SELECT ID,Nom,Pilotage,Acrobatie,Bombardement,Tactique,Tir,Vue,Avion,Alt,Skill FROM Pilote_IA WHERE Unit='$Unite' AND Moral >0 AND Courage >0 AND Endurance <10 AND Actif=1 ORDER BY Reputation DESC LIMIT ".$Avion_Nbr_max."");
						$xp_avion=floor(mysqli_result(mysqli_query($con,"SELECT Exp FROM XP_Avions_IA WHERE Unite='$Unite' AND AvionID='$Avion''"),0))/10;
						if(!$Escorte_Nbr)$intro.='<div class="alert alert-danger"><b>Aucun chasseur d\'escorte n\'est au rendez-vous!</b></div>';
						$Unites_Esc=false;
						$Unites_Couv=false;
						$Pilotes_Esc=false;
						$Pilotes_Couv=false;
						$Pilotes_Vic=false;
						$Avions_Down=false;
						$Enis_ori=$Enis;
						if($Enis >20)$Enis_calc=20;
						$Esc_ori=$Escorte_Nbr;
                        if($Escorte_Nbr >20)$Escorte_Nbr_calc=20;
						if($skillsp)
						{
                            while($datap=mysqli_fetch_array($skillsp,MYSQLI_ASSOC)) //Chaque pilote de l'esc
                            {
                                $Skill_ailier=$datap['Skill'];
                                //To Edit
                                if($Skill_ailier ==1)
                                    $Bonus_ailier=5;
                                elseif($Skill_ailier ==30)
                                    $Bonus_esq_dca=5;
                                elseif($Skill_ailier ==32)
                                    $Bonus_repere=true;
                                elseif($Skill_ailier ==33)
                                    $Bonus_bomb=5;
                                elseif($Skill_ailier ==34)
                                {
                                    $Bonus_renc=5;
                                    $Bonus_renc_txt=true;
                                }
                                elseif($Skill_ailier ==35)
                                    $Bonus_photo=5;
                                elseif($Skill_ailier ==37)
                                    $renc_nbr-=1;
                                elseif($Skill_ailier ==38 and $Nuit)
                                    $Bonus_nuit=10;
                                elseif($Skill_ailier ==39)
                                    $Bonus_steady=5;
                                elseif($Skill_ailier ==40)
                                    $Bonus_pique=5;
                                elseif($Skill_ailier ==41)
                                    $Bonus_esq_dca=10;
                                elseif($Skill_ailier ==42)
                                {
                                    $Bonus_tac=5;
                                    $Bonus_tac_txt=true;
                                }
                                elseif($Skill_ailier ==43)
                                    $Bonus_Mitr=5;
                                elseif($Skill_ailier ==129)
                                    $Bonus_torp=5;
                                elseif($Skill_ailier ==131)
                                    $Bonus_asm=5;
                            }
                        }
						if($resultp)
						{
                            if($Enis >0) //Si aucun chasseur ennemi, pas de combat
                            {
                                if (IsAxe($country))
                                    $pays_esc = "1,6,9,15,18,19,20,24";
                                else
                                    $pays_esc = "2,3,4,5,7,8,10,35";
                                if ($renc_nbr < 1) $renc_nbr = 1;
                                for ($i = 1; $i <= $renc_nbr; $i++) //On répète le nombre de combats en fonction de la distance parcourue jusqu'à la cible (toujours 1 si mission tactique)
                                {
                                    while ($data = mysqli_fetch_array($resultp, MYSQLI_ASSOC)) //Chaque avion de l'escadrille attaquante
                                    {
                                        $Nom_ailier = $data['Nom'];
                                        if ($Enis > 0) //Si aucun chasseur ennemi, pas de combat
                                        {
                                            $Bonus_tir = 0;
                                            $Bonus_init = 0;
                                            $Bonus_esq = 0;
                                            $Bonus_esq_dca = 0;
                                            $Bonus_fou = false;
                                            $Pilotage_ailier = $data['Pilotage'] + $xp_avion;
                                            if ($Type_avion == 1 or $Type_avion == 3 or $Type_avion == 5 or $Type_avion == 7 or $Type_avion == 10 or $Type_avion == 12)
                                                $Acrobatie_ailier = $data['Acrobatie'] + $xp_avion;
                                            else
                                                $Acrobatie_ailier = $xp_avion;
                                            $Tactique_ailier = $data['Tactique'] + $xp_avion;
                                            $Vue_ailier = $data['Vue'];
                                            $Tir_ailier = $data['Tir'];
                                            $Bomb_ailier = $data['Bombardement'];
                                            $Skill_ailier = $data['Skill'];
                                            if ($Skill_ailier == 30)
                                                $Bonus_esq_dca = 5;
                                            elseif ($Skill_ailier == 31)
                                                $Bonus_fou = true;
                                            elseif ($Skill_ailier == 36) {
                                                $Bonus_esq = 10;
                                                $Bonus_esq_txt = true;
                                            } elseif ($Skill_ailier == 44) {
                                                $Bonus_init = 10;
                                                $Bonus_init_txt = true;
                                            } elseif ($Skill_ailier == 45)
                                                $Bonus_tir = 5;
                                            $resultia = mysqli_query($con, "SELECT j.ID,j.Nom,j.Pilotage,j.Acrobatie,j.Tactique,j.Tir,j.Vue,j.Unit,j.Avion,j.Alt,j.Baby,j.Skill,j.Endurance,u.Mission_Flight,
                                            a.ManoeuvreB,a.ManoeuvreH,a.Maniabilite,a.Robustesse,a.Arme1_Nbr,a.Arme2_Nbr,a.Volets,a.Engine_Nbr,a.VitesseP,a.VitesseA,
                                            w.Degats,w.Multi,w.Perf,w.Enrayage,w2.Degats as Degats_S,w2.Multi as Multi_S,w2.Perf as Perf_S,w2.Enrayage as Enrayage_S
                                            FROM Pilote_IA as j,Pays as p,Unit as u,Avion as a,Armes as w,Armes as w2 
                                            WHERE j.Unit=u.ID AND j.Pays=p.ID AND j.Cible='$Cible' AND j." . $Couv_field . "='$Cible' AND j.Avion >0 AND j.Avion=a.ID
                                            AND a.ArmePrincipale=w.ID AND a.ArmeSecondaire=w2.ID AND p.Faction<>'$Faction' AND j.Actif=1 AND (j.Alt BETWEEN '$Mission_alt_min' AND '$Mission_alt_max') ORDER BY RAND() LIMIT 1");
                                            if($resultia) //Chasseurs ennemis
                                            {
                                                while($dataia = mysqli_fetch_array($resultia, MYSQLI_ASSOC)) {
                                                    if($Enis >0){
                                                        $Escorte_choc = false;
                                                        $Enrayage_arme_1 = true;
                                                        $Enrayage_arme_2 = true;
                                                        $flaps_eni = 0;
                                                        $Pilot_lead = 0;
                                                        $Pilot_eni_skill = 0;
                                                        $Bonus_ia = 0;
                                                        $Bonus_renc_ia = 0;
                                                        $Bonus_steady_ia = 0;
                                                        $Bonus_tac_ia = 0;
                                                        $Bonus_init_ia = 0;
                                                        $Degats = 0;
                                                        $Unites_Couv[] = $dataia['Unit'];
                                                        $Pilotes_Couv[] = $dataia['ID'];
                                                        $avion_ia = $dataia['Avion'];
                                                        if ($dataia['Baby'])
                                                            $moda = 1.1;
                                                        else
                                                            $moda = 1;
                                                        if ($dataia['Skill'] == 1)
                                                            $Bonus_ia = 5;
                                                        elseif ($dataia['Skill'] == 34)
                                                            $Bonus_renc_ia = 5;
                                                        elseif ($dataia['Skill'] == 39)
                                                            $Bonus_steady_ia = 5;
                                                        elseif ($dataia['Skill'] == 42)
                                                            $Bonus_tac_ia = 5;
                                                        elseif ($dataia['Skill'] == 44)
                                                            $Bonus_init_ia = 10;
                                                        elseif ($dataia['Skill'] == 45)
                                                            $Bonus_tir_ia = 5;
                                                        if ($dataia['Skill'] == 38 and $Nuit)
                                                            $Bonus_nuit_ia = 10;
                                                        if ($dataia['Tactique'] > 50 or $dataia['Acrobatie'] > 50) $flaps_eni = $dataia['Volets'];
                                                        if (mt_rand(0, 100) > $dataia['Enrayage'] + $dataia['Arme1_Nbr']) $Enrayage_arme_1 = false;
                                                        if (mt_rand(0, 100) > $dataia['Enrayage_S'] + $dataia['Arme2_Nbr']) $Enrayage_arme_2 = false;
                                                        $ManAvion_eni = GetMano($dataia['ManoeuvreB'], $dataia['ManoeuvreH'], 9999, 9999, $Mission_alt, $moda, 1, $flaps_eni);
                                                        $ManiAvion_eni = GetMani($dataia['Maniabilite'], 1, 9999, $moda, 1, $flaps_eni);
                                                        $PuissAvioneni = GetPuissance("Avion", $avion_ia, $Mission_alt, 9999, $moda, 1, $dataia['Engine_Nbr']);
                                                        $VitPeni = GetSpeedPi($dataia['VitesseP'], $dataia['Engine_Nbr']);
                                                        $VitAeni = GetSpeedA("Avion", $dataia['Avion'], $Mission_alt, $meteo, $dataia['Engine_Nbr'], $moda);
                                                        $VitAvioneni = GetSpeed("Avion", $avion_ia, $Mission_alt, $meteo, $moda);
                                                        if ($Mission_alt > 500) {
                                                            if ($VitAeni > 668 and $VitPeni > 659 and ($VitAvioneni * 2) < ($VitPeni + $VitAeni) and $dataia['Alt'] >= $Mission_alt) //Boom & Zoom
                                                                $VitAvionenif = $VitAvioneni + ($VitAeni / 2) + ($VitPeni / 2);
                                                            else
                                                                $VitAvionenif = $VitAvioneni * 2;
                                                        } else
                                                            $VitAvionenif = $VitAvioneni * 2;
                                                        if (!$xp_avion_ia) $xp_avion_ia = floor(mysqli_result(mysqli_query($con, "SELECT Exp FROM XP_Avions_IA WHERE Unite=".$dataia['Unit']." AND AvionID=".$avion_ia), 0)) / 10;
                                                        if ($Escorte_Nbr and (($Escorte_Nbr + mt_rand(-2, 3) + $Bonus_tac + $Bonus_init + $Bonus_renc) >= ($Enis_calc + mt_rand(-2, 3) + $Bonus_tac_ia + $Bonus_init_ia + $Bonus_renc_ia))) //Si l'escorte gagne son duel tactique face à la couverture défensive, elle encaissera les pertes à la place de l'unité escortée
                                                        {
                                                            $Escorte_choc = true;
                                                            $intro .= "<p>L'escorte parvient à tenir les chasseurs ennemis éloignés de " . $Nom_ailier . "</p>";
                                                            $resultesc = mysqli_query($con, "SELECT j.ID,j.Nom,j.Pilotage,j.Acrobatie,j.Tactique,j.Tir,j.Vue,j.Unit,j.Avion,j.Alt,j.Baby,j.Skill,j.Endurance,u.Mission_Flight,a.ManoeuvreB,a.ManoeuvreH,a.Maniabilite,a.Robustesse,a.Engine_Nbr,a.VitesseP
                                                            FROM Pilote_IA as j,Pays as p,Unit as u,Avion as a WHERE j.Unit=u.ID AND j.Pays=p.ID AND j.Cible='$Cible' AND j.Escorte='$Cible' AND j.Avion >0 AND j.Avion=a.ID AND p.Faction='$Faction' AND j.Actif=1 AND (j.Alt BETWEEN '$Mission_alt_min' AND '$Mission_alt_max') ORDER BY RAND() LIMIT 1");
                                                            if ($resultesc) {
                                                                while ($dataesc = mysqli_fetch_array($resultesc, MYSQLI_ASSOC)) {
                                                                    $Bonus_esc = 0;
                                                                    $Bonus_renc_esc = 0;
                                                                    $Bonus_steady_esc = 0;
                                                                    $Bonus_tac_esc = 0;
                                                                    $Bonus_tir_esc = 0;
                                                                    $Bonus_init_esc = 0;
                                                                    $Bonus_nuit_esc = 0;
                                                                    $Avion_esc = $dataesc['Avion'];
                                                                    $Pilote_esc = $dataesc['ID'];
                                                                    $Unit_esc = $dataesc['Unit'];
                                                                    $Mission_Flight_esc = $dataesc['Mission_Flight'];
                                                                    if ($dataesc['Baby'])
                                                                        $moda = 1.1;
                                                                    else
                                                                        $moda = 1;
                                                                    if ($dataesc['Skill'] == 1)
                                                                        $Bonus_esc = 5;
                                                                    elseif ($dataesc['Skill'] == 34)
                                                                        $Bonus_renc_esc = 5;
                                                                    elseif ($dataesc['Skill'] == 39)
                                                                        $Bonus_steady_esc = 5;
                                                                    elseif ($dataesc['Skill'] == 42)
                                                                        $Bonus_tac_esc = 5;
                                                                    elseif ($dataesc['Skill'] == 44)
                                                                        $Bonus_init_esc = 10;
                                                                    elseif ($dataesc['Skill'] == 45)
                                                                        $Bonus_tir_esc = 5;
                                                                    if ($dataesc['Skill'] == 38 and $Nuit)
                                                                        $Bonus_nuit_esc = 10;
                                                                    $ManAvion_esc = GetMano($dataesc['ManoeuvreB'], $dataesc['ManoeuvreH'], 9999, 9999, $Mission_alt, $moda);
                                                                    $ManiAvion_esc = GetMani($dataesc['Maniabilite'], 1, 9999, $moda);
                                                                    $PuissAvion_esc = GetPuissance("Avion", $dataesc['Avion'], $Mission_alt, 9999, $moda, 1, $dataesc['Engine_Nbr']);
                                                                    $VitAvion_esc = GetSpeed("Avion", $dataesc['Avion'], $Mission_alt, $meteo, $moda);
                                                                    $VitP_esc = GetSpeedPi($dataesc['VitesseP'], $dataesc['Engine_Nbr']);
                                                                    $VitA_esc = GetSpeedA("Avion", $dataesc['Avion'], $Mission_alt, $meteo, $dataesc['Engine_Nbr'], $moda);
                                                                    if ($Mission_alt > 500) {
                                                                        if ($VitA_esc > 668 and $VitP_esc > 659 and ($VitAvion_esc * 2) < ($VitP_esc + $VitA_esc) and $dataesc['Alt'] >= $dataia['Alt']) //Boom & Zoom
                                                                            $VitAvion_esc += ($VitA_esc / 2) + ($VitP_esc / 2);
                                                                        else
                                                                            $VitAvion_esc *= 2;
                                                                    } else
                                                                        $VitAvion_esc *= 2;
                                                                    if (!$xp_avion_esc) $xp_avion_esc = floor(mysqli_result(mysqli_query($con, "SELECT Exp FROM XP_Avions_IA WHERE Unite='$Unit_esc' AND AvionID='$Avion_esc''"), 0)) / 10;
                                                                    $rand_pil_lead = (mt_rand(1, $dataesc['Pilotage'] + $xp_avion_esc)) * (1 - ($dataesc['Endurance'] * 0.1));
                                                                    $rand_tac_lead = (mt_rand(1, $dataesc['Tactique'] + $xp_avion_esc)) * (1 - ($dataesc['Endurance'] * 0.1));
                                                                    $rand_acr_lead = (mt_rand(0, $dataesc['Acrobatie'] / 10)) * (1 - ($dataesc['Endurance'] * 0.1));
                                                                    $rand_vue_lead = (mt_rand(1, $dataesc['Vue'])) * (1 - ($dataia['Endurance'] * 0.1));
                                                                    $Pilot_lead = $rand_pil_lead + $rand_tac_lead + ($rand_acr_lead/10) + $rand_vue_lead + $meteo + (($ManAvion_esc + $ManiAvion_esc + $VitAvion_esc - ($PuissAvion_esc / 2)) / 5) + $Escorte_Nbr_calc + $Bonus_esc + $Bonus_renc_esc + $Bonus_tac_esc + $Bonus_init_esc + $Bonus_nuit_esc;
                                                                    $Tir_allie = $dataesc['Tir'] + $Bonus_steady_esc + $Bonus_tir_esc;
                                                                    $Unites_Esc[] = $Unit_esc;
                                                                    $Pilotes_Esc[] = $Pilote_esc;
                                                                    if ($Premium) {
                                                                        $intro .= '<div class="row"><div class="col-md-6"><b>Escorte ' . round($Pilot_lead) . '</b>
                                                                        <br><img src="images/pr_skill_pil.png"> ' . round($rand_pil_lead + $rand_tac_lead + $rand_vue_lead + ($rand_acr_lead / 10))
                                                                            . ' <img src="images/pr_skill_plane.png"> ' . round(($ManAvion_esc + $ManiAvion_esc + $VitAvion_esc - ($PuissAvion_esc / 2)) / 5)
                                                                            . ' <img src="images/skills/skill0p.png"> ' . round($Bonus_esc + $Bonus_renc_esc + $Bonus_tac_esc + $Bonus_init_esc + $Bonus_nuit_esc + $Escorte_Nbr_calc) . '</div>';
                                                                    } else
                                                                        $debug_intro .= '<div class="row"><div class="col-md-6"><b>Escorte ' . round($Pilot_lead) . '</b>
                                                                        <br><img src="images/pr_skill_pil.png"> ' . round($rand_pil_lead + $rand_tac_lead + $rand_vue_lead + ($rand_acr_lead / 10))
                                                                            . ' <img src="images/pr_skill_plane.png"> ' . round(($ManAvion_esc + $ManiAvion_esc + $VitAvion_esc - ($PuissAvion_esc / 2)) / 5)
                                                                            . ' <img src="images/skills/skill0p.png"> ' . round($Bonus_esc + $Bonus_renc_esc + $Bonus_tac_esc + $Bonus_init_esc + $Bonus_nuit_esc + $Escorte_Nbr_calc) . '</div>';
                                                                }
                                                                mysqli_free_result($resultesc);
                                                            }
                                                        } else {
                                                            if ($Escorte_Nbr)
                                                                $intro .= "<p>L'escorte est débordée par les chasseurs ennemis qui attaquent " . $Nom_ailier . "</p>";
                                                            elseif ($Type != 4 and $Type != 7 and $Type != 17)
                                                                $Bonus_no_escorte = 25 * $Enis_calc;
                                                            $rand_pil_lead = mt_rand(1, $Pilotage_ailier);
                                                            $rand_tac_lead = mt_rand(1, $Tactique_ailier);
                                                            $rand_acr_lead = mt_rand(0, $Acrobatie_ailier / 10);
                                                            $rand_vue_lead = mt_rand(1, $Vue_ailier);
                                                            if ($Mission_alt > 500) {
                                                                if ($Type == 7) {
                                                                    if ($VitesseA > 40 and $VitesseP > 659 and ($VitAvion_lead * 2) < ($VitesseP + $VitesseA) and $Mission_alt >= $dataia['Alt']) //Boom & Zoom
                                                                        $VitAvion_leadf = $VitAvion_lead + ($VitesseA / 2) + ($VitesseP / 2);
                                                                    else
                                                                        $VitAvion_leadf = $VitAvion_lead * 2;
                                                                } else {
                                                                    $VitAvion_leadf = $VitesseP * 2;
                                                                    $VitAvionenif = $VitPeni * 2;
                                                                }
                                                            } else {
                                                                $VitAvion_leadf = $VitAvion_lead;
                                                                $VitAvionenif = $VitAvioneni;
                                                            }
                                                            $Pilot_lead = $rand_pil_lead + $rand_tac_lead + ($rand_acr_lead/10) + $rand_vue_lead + $meteo + (($ManAvion_lead + $VitAvion_leadf + $ManiAvion_lead - ($PuissAvion_lead / 2)) / 5) + $Bonus_ailier + $Bonus_renc + $Bonus_tac + $Bonus_init + $Bonus_nuit;
                                                            $Tir_allie = $Tir_ailier + $Bonus_steady + $Bonus_tir;
                                                            $Bonus_init_ia += $Enis_calc;
                                                            $Bonus_tir_ia += $Enis_calc;
                                                            if ($Premium) {
                                                                $intro .= '<div class="row"><div class="col-md-6"><b>' . $Nom_ailier . ' ' . round($Pilot_lead) . '</b>
                                                                <br><img src="images/pr_skill_pil.png"> ' . round($rand_pil_lead + $rand_tac_lead + $rand_vue_lead + ($rand_acr_lead / 10))
                                                                    . ' <img src="images/pr_skill_plane.png"> ' . round(($ManAvion_lead + $ManiAvion_lead + $VitAvion_leadf - ($PuissAvion_lead / 2)) / 5)
                                                                    . ' <img src="images/skills/skill0p.png"> ' . round($Bonus_ailier + $Bonus_renc + $Bonus_tac + $Bonus_init + $Bonus_nuit) . '</div>';
                                                            } else
                                                                $debug_intro .= '<div class="row"><div class="col-md-6"><b>' . $Nom_ailier . ' ' . round($Pilot_lead) . '</b>
                                                                <br><img src="images/pr_skill_pil.png"> ' . round($rand_pil_lead + $rand_tac_lead + $rand_vue_lead + ($rand_acr_lead / 10))
                                                                    . ' <img src="images/pr_skill_plane.png"> ' . round(($ManAvion_lead + $ManiAvion_lead + $VitAvion_leadf - ($PuissAvion_lead / 2)) / 5)
                                                                    . ' <img src="images/skills/skill0p.png"> ' . round($Bonus_ailier + $Bonus_renc + $Bonus_tac + $Bonus_init + $Bonus_nuit) . '</div>';
                                                        }
                                                        $rand_pil_eni = (mt_rand(1, $dataia['Pilotage'] + $xp_avion_ia)) * (1 - ($dataia['Endurance'] * 0.1));
                                                        $rand_tac_eni = (mt_rand(1, $dataia['Tactique'] + $xp_avion_ia)) * (1 - ($dataia['Endurance'] * 0.1));
                                                        $rand_acr_eni = (mt_rand(0, $dataia['Acrobatie'] / 10)) * (1 - ($dataia['Endurance'] * 0.1));
                                                        $rand_vue_eni = (mt_rand(1, $dataia['Vue'])) * (1 - ($dataia['Endurance'] * 0.1));
                                                        $Pilot_eni_skill = $rand_pil_eni + $rand_tac_eni + ($rand_acr_eni/10) + $rand_vue_eni
                                                            + $meteo + (($ManAvion_eni + $ManiAvion_eni + $VitAvionenif - ($PuissAvioneni / 2)) / 5) + $Radar_Bonus + $Enis_calc + $Bonus_renc_ia + $Bonus_tac_ia + $Bonus_init_ia + $Bonus_nuit_ia + $Bonus_no_escorte;
                                                        //if($dataia['Unit']==608 or $dataia['Unit']==626)$Pilot_eni_skill=0;
                                                        if ($Premium) {
                                                            $intro .= '<div class="col-md-6"><b>Couverture ' . round($Pilot_eni_skill) . '</b>
                                                            <br><img src="images/pr_skill_pil.png"> ' . round($rand_pil_eni + $rand_tac_eni + $rand_vue_eni + ($rand_acr_eni / 10))
                                                                . ' <img src="images/pr_skill_plane.png"> ' . round(($ManAvion_eni + $ManiAvion_eni + $VitAvionenif - ($PuissAvioneni / 2)) / 5)
                                                                . ' <img src="images/skills/skill0p.png"> ' . round($Enis_calc + $Bonus_renc_ia + $Bonus_tac_ia + $Bonus_init_ia + $Bonus_nuit_ia + $Radar_Bonus) . '</div></div>';
                                                        } else
                                                            $debug_intro .= '<div class="col-md-6"><b>Couverture ' . round($Pilot_eni_skill) . '</b>
                                                            <br><img src="images/pr_skill_pil.png"> ' . round($rand_pil_eni + $rand_tac_eni + $rand_vue_eni + ($rand_acr_eni / 10))
                                                                . ' <img src="images/pr_skill_plane.png"> ' . round(($ManAvion_eni + $ManiAvion_eni + $VitAvionenif - ($PuissAvioneni / 2)) / 5)
                                                                . ' <img src="images/skills/skill0p.png"> ' . round($Enis_calc + $Bonus_renc_ia + $Bonus_tac_ia + $Bonus_init_ia + $Bonus_nuit_ia + $Radar_Bonus) . '</div></div>';
                                                        if ($Type != 8 and $Type != 16) {
                                                            if ($Bonus_fou and mt_rand(0, 1) == 1) {
                                                                AddEvent("Avion", 284, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                $intro .= "<br>Malgré la présence de la chasse ennemie, <b>" . $Nom_ailier . "</b> arrive sur l'objectif, grâce à sa compétence de Fou Volant!";
                                                                $Avion_Nbr += 1;
                                                                $Fou_txt = true;
                                                            } elseif ($Pilot_lead > ($Pilot_eni_skill + 25) and $Tir_allie > mt_rand(10, 150)) {
                                                                if ($Escorte_choc and $Escorte_Nbr) {
                                                                    AddEvent("Avion", 281, $Avion_esc, $Pilote_esc, $Unit_esc, $Cible, $avion_ia, $dataia['ID']);
                                                                    $Pilotes_Vic[] = $Pilote_esc;
                                                                    UpdateData("Unit", "Avion" . $dataia['Mission_Flight'] . "_Nbr", -1, "ID", $dataia['Unit']);
                                                                    $intro .= "<br><span class='text-primary'>Après que son escorte ait abattu un chasseur ennemi, <b>" . $Nom_ailier . "</b> arrive sur l'objectif!</span>";
                                                                } else {
                                                                    AddEvent("Avion", 282, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                    $intro .= "<br><span class='text-success'>Après avoir abattu un chasseur ennemi, <b>" . $Nom_ailier . "</b> arrive sur l'objectif!</span>";
                                                                    if ($Bonus_tir) $Bonus_tir_txt = true;
                                                                }
                                                                $Avions_Down[] = $avion_ia;
                                                                $Enis -= 1;
                                                                $Avion_Nbr += 1;
                                                                $reset_00 = mysqli_query($con, "UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Alt=0,Cible=0,Avion=0,Moral=0,Courage=0,Mission=0,Baby=0 WHERE ID='" . $dataia['ID'] . "'");
                                                            } elseif ($Pilot_eni_skill > ($Pilot_lead + 25) and ($dataia['Tir'] + $Bonus_steady_ia + $Bonus_tir_ia) > (mt_rand(10, 150) + $Bonus_esq)) {
                                                                if ($Escorte_choc and $Escorte_Nbr and $Type != 4 and $Type != 7 and $Type != 17) {
                                                                    AddEvent("Avion", 280, $avion_ia, $dataia['ID'], $dataia['Unit'], $Cible, $Avion_esc, $Pilote_esc);
                                                                    UpdateData("Unit", "Avion" . $Mission_Flight_esc . "_Nbr", -1, "ID", $Unit_esc);
                                                                    $Pilotes_Vic[] = $dataia['ID'];
                                                                    $Avions_Down[] = $Avion_esc;
                                                                    $intro .= "<span class='text-danger'>Un avion de l'escorte est abattu par un chasseur ennemi!</span>";
                                                                    $Escorte_Nbr -= 1;
                                                                    $Avion_Nbr += 1;
                                                                    $reset_00 = mysqli_query($con, "UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Alt=0,Cible=0,Avion=0,Moral=0,Courage=0,Mission=0 WHERE ID='$Pilote_esc'");
                                                                } else {
                                                                    if ($Type == 4 or $Type == 7 or $Type == 17) {
                                                                        if (($dataia['Degats_S'] > $dataia['Degats']) and !$Enrayage_arme_2)
                                                                            $Degats = ((mt_rand(1, $dataia['Degats_S']) * $dataia['Multi_S']) - $Blindage) * $dataia['Arme2_Nbr'];
                                                                        elseif (!$Enrayage_arme_1)
                                                                            $Degats = ((mt_rand(1, $dataia['Degats']) * $dataia['Multi']) - $Blindage) * $dataia['Arme1_Nbr'];
                                                                        else{
                                                                            $Degats =0;
                                                                            $debug_intro.='<br>Enrayage sur '.$Nom_ailier;
                                                                        }
                                                                    } else {
                                                                        if (($dataia['Perf'] >= $Blindage) and !$Enrayage_arme_1)
                                                                            $Degats = (mt_rand(1, $dataia['Degats']) - $Blindage) * $dataia['Multi'] * $dataia['Arme1_Nbr'];
                                                                        if (($dataia['Perf_S'] >= $Blindage) and $dataia['Degats_S'] and !$Enrayage_arme_2)
                                                                            $Degats += (mt_rand(1, $dataia['Degats_S']) - $Blindage) * $dataia['Multi_S'] * $dataia['Arme2_Nbr'];
                                                                        else {
                                                                            $Degats=0;
                                                                            $debug_intro.='<br>Enrayage sur '.$Nom_ailier;
                                                                        }
                                                                    }
                                                                    if ($Degats >0 and $Degats >= $HP_avion) {
                                                                        AddEvent("Avion", 283, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                        UpdateData("Unit", "Avion" . $Mission_Flight . "_Nbr", -1, "ID", $Unite);
                                                                        $Pilotes_Vic[] = $dataia['ID'];
                                                                        $Avions_Down[] = $Avion;
                                                                        $intro .= "<br><span class='text-danger'><b>" . $Nom_ailier . "</b> a été abattu par la chasse ennemie!</span>";
                                                                        $debug_intro .= $Nom_ailier . " a été abattu (" . $Degats . ") par la chasse ennemie!<br>";
                                                                        $reset_00 = mysqli_query($con, "UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Alt=0,Cible=0,Avion=0,Moral=0,Courage=0,Mission=0,Baby=0 WHERE ID='" . $data['ID'] . "'");
                                                                    } elseif($Degats >0) {
                                                                        AddEvent("Avion", 285, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                        $reset_00 = mysqli_query($con, "UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Alt=0,Cible=0,Avion=0,Mission=0,Baby=0 WHERE ID='" . $data['ID'] . "'");
                                                                        $intro .= '<br><span class="text-danger"><b>' . $Nom_ailier . '</b> a été sérieusement endommagé par la chasse ennemie et contraint de faire demi-tour!</span>';
                                                                        $debug_intro .= $Nom_ailier . ' a été endommagé (' . $Degats . ') par la chasse ennemie et contraint de faire demi-tour!<br>';
                                                                    }
                                                                    else{
                                                                        AddEvent("Avion", 284, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                        $intro .= '<br>Malgré la présence de la chasse ennemie, ' . $Nom_ailier . ' arrive sur l\'objectif légèrement endommagé!';
                                                                        $Avion_Nbr += 1;
                                                                    }
                                                                }
                                                            } else {
                                                                AddEvent("Avion", 284, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                $intro .= '<br>Malgré la présence de la chasse ennemie, ' . $Nom_ailier . ' arrive sur l\'objectif en évitant ses assaillants!';
                                                                $Avion_Nbr += 1;
                                                            }
                                                        } else  //Bomb strat
                                                        {
                                                            if ($Pilot_lead + $Bonus_Mitr > $Pilot_eni_skill and $Tir_allie + $Bonus_Mitr > mt_rand(10, 100)) {
                                                                if ($Escorte_choc and $Escorte_Nbr) {
                                                                    AddEvent("Avion", 281, $Avion_esc, $Pilote_esc, $Unit_esc, $Cible, $avion_ia, $dataia['ID']);
                                                                    UpdateData("Unit", "Avion" . $dataia['Mission_Flight'] . "_Nbr", -1, "ID", $dataia['Unit']);
                                                                    $Pilotes_Vic[] = $Pilote_esc;
                                                                    $intro .= "<br><span class='text-primary'>Après que son escorte ait abattu un chasseur ennemi, " . $Nom_ailier . " arrive sur l'objectif!</span>";
                                                                } else {
                                                                    AddEvent("Avion", 282, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                    $intro .= "<br><span class='text-success'>Après avoir abattu un chasseur ennemi, " . $Nom_ailier . " arrive sur l'objectif!</span>";
                                                                    if ($Bonus_tir) $Bonus_tir_txt = true;
                                                                }
                                                                $Avions_Down[] = $avion_ia;
                                                                $Enis -= 1;
                                                                $Avion_Nbr += 1;
                                                                $reset_00 = mysqli_query($con, "UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Alt=0,Cible=0,Avion=0,Moral=0,Courage=0,Mission=0,Baby=0 WHERE ID='" . $dataia['ID'] . "'");
                                                            } elseif ($Escorte_choc and $Escorte_Nbr and $Pilot_eni_skill > $Pilot_lead and $dataia['Tir'] + $Bonus_steady_ia + $Bonus_tir_ia > mt_rand(10, 100) + $Bonus_esq) {
                                                                AddEvent("Avion", 280, $avion_ia, $dataia['ID'], $dataia['Unit'], $Cible, $Avion_esc, $Pilote_esc);
                                                                UpdateData("Unit", "Avion" . $Mission_Flight_esc . "_Nbr", -1, "ID", $Unit_esc);
                                                                $Pilotes_Vic[] = $dataia['ID'];
                                                                $Avions_Down[] = $Avion_esc;
                                                                $intro .= "<br><span class='text-danger'>Un avion de l'escorte est abattu par un chasseur ennemi!</span>";
                                                                $Escorte_Nbr -= 1;
                                                                $reset_00 = mysqli_query($con, "UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Alt=0,Cible=0,Avion=0,Moral=0,Courage=0,Mission=0,Baby=0 WHERE ID='$Pilote_esc'");
                                                            } elseif ($Pilot_eni_skill > $Pilot_lead + $Bonus_Mitr) {
                                                                if ($dataia['Tir'] + $Radar_Bonus + $Bonus_steady_ia + $Bonus_tir_ia > mt_rand(0, 100) + $Bonus_esq) {
                                                                    if ($dataia['Perf'] >= $Blindage and !$Enrayage_arme_1)
                                                                        $Degats = (mt_rand(1, $dataia['Degats']) - $Blindage) * $dataia['Multi'] * $dataia['Arme1_Nbr'];
                                                                    if ($dataia['Degats_S'] > 0 and $dataia['Perf_S'] >= $Blindage and !$Enrayage_arme_2)
                                                                        $Degats += (mt_rand(1, $dataia['Degats_S']) - $Blindage) * $dataia['Multi_S'] * $dataia['Arme2_Nbr'];
                                                                    if (!$Degats)
                                                                        $debug_intro .= '<br>Enrayage sur ' . $Nom_ailier;
                                                                    if (($Degats >0 and $Degats >= $HP_avion) or ($Degats > 0 and ($Degats > ($HP_avion / 2)) and ($dataia['Tir'] > 100))) {
                                                                        AddEvent("Avion", 283, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                        UpdateData("Unit", "Avion" . $Mission_Flight . "_Nbr", -1, "ID", $Unite);
                                                                        $Pilotes_Vic[] = $dataia['ID'];
                                                                        $Avions_Down[] = $Avion;
                                                                        $intro .= "<br><span class='text-danger'><b>" . $Nom_ailier . "</b> a été abattu par la chasse ennemie!</span>";
                                                                        $debug_intro .= $Nom_ailier . " a été abattu (" . $Degats . ") par la chasse ennemie!<br>";
                                                                        $reset_00 = mysqli_query($con, "UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Alt=0,Cible=0,Avion=0,Moral=0,Courage=0,Mission=0,Baby=0 WHERE ID='" . $data['ID'] . "'");
                                                                    } elseif($Degats >0) {
                                                                        AddEvent("Avion", 285, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                        $reset_00 = mysqli_query($con, "UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Task=0,Alt=0,Cible=0,Avion=0,Mission=0,Baby=0 WHERE ID='" . $data['ID'] . "'");
                                                                        $intro .= "<br><span class='text-danger'><b>" . $Nom_ailier . "</b> a été endommagé par la chasse ennemie et contraint de faire demi-tour!</span>";
                                                                        $debug_intro .= $Nom_ailier . " a été endommagé (" . $Degats . ") par la chasse ennemie et contraint de faire demi-tour!<br>";
                                                                    } else {
                                                                        AddEvent("Avion", 284, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                        $intro .= "<br>Malgré la présence de la chasse ennemie, " . $Nom_ailier . " arrive sur l'objectif!";
                                                                        $Avion_Nbr += 1;
                                                                    }
                                                                } else {
                                                                    AddEvent("Avion", 284, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                    $intro .= "<br>Malgré la présence de la chasse ennemie, " . $Nom_ailier . " arrive sur l'objectif!";
                                                                    $Avion_Nbr += 1;
                                                                }
                                                            } else {
                                                                AddEvent("Avion", 284, $Avion, $data['ID'], $Unite, $Cible, $avion_ia, $dataia['ID']);
                                                                $intro .= "<br>Malgré la présence de la chasse ennemie, " . $Nom_ailier . " arrive sur l'objectif!";
                                                                $Avion_Nbr += 1;
                                                            }
                                                        }//end bomb_strat
                                                    } else {
                                                        $intro .= "<br>" . $Nom_ailier . " arrive sur l'objectif sans encombre!";
                                                        $Avion_Nbr += 1;
                                                    }
                                                }//end while pilote_ia
                                            }
                                            mysqli_free_result($resultia);
                                        } else {
                                            $intro .= "<br>" . $Nom_ailier . " arrive sur l'objectif sans encombre!";
                                            $Avion_Nbr += 1;
                                        }
                                    }//end while pilote
                                    if ($Avion_Nbr > $Avion_Nbr_max) $Avion_Nbr = $Avion_Nbr_max;
                                }
                            }
							else
							{
								$intro.='<div class="alert alert-info"><b>Le ciel semble vide de chasseurs ennemis!</b></div>';
								$Avion_Nbr=$Avion_Nbr_max;
							}
							mysqli_free_result($resultp);
						}
						$skills_m='<h2>Compétences des pilotes en mission</h2>';
						if($Bonus_ailier)$skills_m.="<img src='images/skills/skill1.png'>";
						if($Fou_txt)$skills_m.="<img src='images/skills/skill31.png'>";
						if($Bonus_repere)$skills_m.="<img src='images/skills/skill32.png'>";
						if($Bonus_bomb)$skills_m.="<img src='images/skills/skill33.png'>";
						if($Bonus_renc_txt)$skills_m.="<img src='images/skills/skill34.png'>";
						if($Bonus_esq_txt)$skills_m.="<img src='images/skills/skill36.png'>";
						if($Skill_ailier ==37)$skills_m.="<img src='images/skills/skill37.png'>";
						if($Bonus_nuit)$skills_m.="<img src='images/skills/skill38.png'>";
						if($Bonus_steady)$skills_m.="<img src='images/skills/skill39.png'>";
						if($Bonus_esq_dca)$skills_m.="<img src='images/skills/skill41.png'>";
						if($Bonus_tac_txt)$skills_m.="<img src='images/skills/skill42.png'>";
						if($Bonus_Mitr)$skills_m.="<img src='images/skills/skill43.png'>";
						if($Bonus_init_txt)$skills_m.="<img src='images/skills/skill44.png'>";
						if($Bonus_tir_txt)$skills_m.="<img src='images/skills/skill45.png'>";
						if(is_array($Unites_Couv) and $Enis_ori)
						{
							$Unites_Couv=array_unique($Unites_Couv);
							$U_Count=array_count_values($Unites_Couv);
							if($U_Count >0)
							{
								$Units_Couv_in=implode(',',$Unites_Couv);
								$reset=mysqli_query($con,"UPDATE Unit SET Reputation=Reputation+".$Enis_ori." WHERE ID IN(".$Units_Couv_in.")");
								unset($Unites_Couv);
							}
						}
						if(is_array($Unites_Esc) and $Esc_ori)
						{
							$Unites_Esc=array_unique($Unites_Esc);
							$E_Count=array_count_values($Unites_Esc);
							if($E_Count >0)
							{
								$Units_Esc_in=implode(',',$Unites_Esc);
								$reset=mysqli_query($con,"UPDATE Unit SET Reputation=Reputation+".$Esc_ori." WHERE ID IN(".$Units_Esc_in.")");
								unset($Unites_Esc);
								if($Type ==8 or $Type ==16)
									mail('binote@hotmail.com','Aube des Aigles: Escortes em_ia1','Unités en escorte : '.$Units_Esc_in);
							}
						}
						if(is_array($Pilotes_Couv) and $Enis_ori)
						{
							$Pilotes_Couv=array_unique($Pilotes_Couv);
							$PC_Count=array_count_values($Pilotes_Couv);
							if($PC_Count >0)
							{
								$Pilotes_Couv_in=implode(',',$Pilotes_Couv);
								$reset=mysqli_query($con,"UPDATE Pilote_IA SET Reputation=Reputation+10,Points=Points+10,Avancement=Avancement+10,Moral=Moral+10,Missions=Missions+1,Endurance=Endurance+1 WHERE ID IN(".$Pilotes_Couv_in.")");
								unset($Pilotes_Couv);
							}
						}
						if(is_array($Pilotes_Esc) and $Esc_ori)
						{
							$Pilotes_Esc=array_unique($Pilotes_Esc);
							$PE_Count=array_count_values($Pilotes_Esc);
							if($PE_Count >0)
							{
								$Pilotes_Esc_in=implode(',',$Pilotes_Esc);
								$reset=mysqli_query($con,"UPDATE Pilote_IA SET Reputation=Reputation+10,Points=Points+10,Avancement=Avancement+10,Moral=Moral+10,Missions=Missions+1,Endurance=Endurance+1 WHERE ID IN(".$Pilotes_Esc_in.")");
								unset($Pilotes_Esc);
							}
						}
						if(is_array($Pilotes_Vic))
						{
							$PV_Count=array_count_values($Pilotes_Vic);
							if($PV_Count >0)
							{
								$Pilotes_Vic_in=implode(',',$Pilotes_Vic);
								$resetv=mysqli_query($con,"UPDATE Pilote_IA SET Reputation=Reputation+10,Points=Points+10,Avancement=Avancement+10,Moral=Moral+10,Victoires=Victoires+1 WHERE ID IN(".$Pilotes_Vic_in.")");
								unset($Pilotes_Vic);
							}
						}
						/*$report.=$Nom_Lieu." (".$OfficierEMID_Nom.") ==> Enis_Ori ".$Enis_ori." / Esc_ori ".$Esc_ori." / Units_Couv ".$Units_Couv_in." / Unites_Esc ".$Units_Esc_in." / Pilotes_Couv ".$Pilotes_Couv_in." / Pilotes_Esc ".$Pilotes_Esc_in." / Pilotes_Vic ".$Pilotes_Vic_in;
						mail('binote@hotmail.com','Aube des Aigles: Bomb/Atk IA',$report);*/
						$Task=$Cible;
						if($Type ==4)
							$Mission='Escorte';
						elseif($Type ==17)
							$Mission='Couverture_Nuit';
						elseif($Type ==7)
							$Mission='Couverture';
						elseif($Type ==32){
							$Mission='Task';
							$Task=5;
						}
						else
							$Mission='Mission';
						if($Avion_Nbr >0)
						{
							if($Pilote_id)$Front_Pilote=mysqli_result(mysqli_query($con,"SELECT Front FROM Pilote WHERE ID='$Pilote_id'"),0);
							$reset_0=mysqli_query($con,"UPDATE Pilote_IA SET Escorte=0,Couverture=0,Couverture_Nuit=0,Alt=0,Cible=0,Avion=0,Mission=0,Baby=0,Task=0 WHERE Unit='$Unite'");
							$reset_ia=mysqli_query($con,"UPDATE Pilote_IA SET $Mission='$Task',Alt='$Mission_alt',Cible='$Cible',Avion='$Avion',Reputation=Reputation+10,Points=Points+10,Avancement=Avancement+10,Moral=Moral+10,Missions=Missions+1,Baby='$Long_Mission' WHERE Unit='$Unite' AND Moral >0 AND Courage >0 AND Actif=1 ORDER BY RAND() LIMIT ".$Avion_Nbr."");
							$pilotes_ia=mysqli_affected_rows($con);
							if($Front_Pilote ==$Front)$Pil_Front=true;
						}
						if($pilotes_ia >0 and $Avion_Nbr >0)
						{
							include_once('./jfv_ground.inc.php');
							$Matos_mun=array(1,2,6,7,8);
							$mes.="<div class='alert alert-warning'>Le ".$Corps." vous informe que votre ordre de mission a été validé.<br><b>".$Avion_Nbr_max."</b> avions étaient disponibles";
							if(!$Porte_avions)$mes.=" et un stock de <b>".$Conso."L".$Octane."</b> de carburant leur a été attribué depuis le dépôt de <b>".GetData("Lieu","ID",$getdepot,"Nom")."</b>.";
							$mes.="<br><b>".$pilotes_ia."</b> pilotes de l'escadrille se joignent à la ".$Mission;
							if($Avion_Nbr ==$Avion_Nbr_max)$mes.="<br>Les pilotes et leurs avions arrivent sans encombre sur l'objectif.";
							$mes.='</div>';
							if($Avion_Nbr >$pilotes_ia)$Avion_Nbr=$pilotes_ia;
							if($Type ==15) //reco strat
							{

								if($Camera ==25 or $Camera ==26 or $Camera ==27){ //Bonus Camera
									if($Mission_alt <=GetData("Armes","ID",$Camera,"Portee"))
										$Bonus_Camera=GetData("Armes","ID",$Camera,"Enrayage");
									else
										$Bonus_Camera=0;
								}
								$Malus_Reperer=GetMalusReperer($Zone,$Camouflage);
								if($meteo <-49)$Bonus_Camera=0;
								$Photo_shoot=mt_rand($Avion_Nbr,50)+$Bonus_Camera+($Stab/10)+($meteo*2)-($Mission_alt/100)-$Malus_Reperer+$Bonus_ailier+$Bonus_steady+$Bonus_photo+$Avion_Nbr;
								if($Photo_shoot){
									$Gain_Reput=20;
									SetData("Lieu","Recce",1,"ID",$Cible);
									$mes.='<div class="alert alert-success">Le lieu a été reconnu avec succès!</div>';
									if($Bonus_photo)$skills_m.="<img src='images/skills/skill35.png'>";
									if($BaseAerienne >0 and $Photo_shoot >50)
									{
										$resultu=mysqli_query($con,"SELECT ID,Nom,Pays FROM Unit WHERE Base='$Cible'");
										if($resultu)
										{
											while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC)) 
											{
												$Nom_unit.=Afficher_Icone($datau['ID'],$datau['Pays'],$datau['Nom'])." ";
												$Units_Recce[]=$datau['ID'];
											}
											mysqli_free_result($resultu);
										}
										if($Nom_unit)
											$mes.='<br>Le rapport renseigne également que ce terrain abrite les unités suivantes : '.$Nom_unit;
										if(is_array($Units_Recce))
										{
											if(array_count_values($Units_Recce) >0)
											{
												$Units_Recce_in=implode(',',$Units_Recce);
												$reset=mysqli_query($con,"UPDATE Unit SET Recce=1 WHERE ID IN(".$Units_Recce_in.")");
												unset($Units_Recce);
											}
										}
										$Gain_Reput+=10;
									}
									UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,114);
								}
								else
									$mes.='<div class="alert alert-danger">Les conditions n\'étaient pas suffisamment favorables, la mission a échoué!';
							}
							elseif($Type ==5) //reco tactique
							{
								$alerte_reco=false;
								/*$Patrol_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Cible='$Cible' AND j.Actif=1"),0);								
								if($Patrol_Nbr >0 and !$Escorte_Nbr)
									$mes.="<br><b>La couverture de chasse ennemie empêche les avions de reconnaissance d'atteindre leur objectif! Pour pouvoir passer la couverture, vous devez placer une escorte de chasse à l'altitude d'attaque.</b>";
								else
								{*/
									//DCA
									$Malus_Range=$Mission_alt/100;
									$Unit_table='Regiment_IA';
									$query="SELECT r.ID,r.Pays,r.Bataillon,r.Vehicule_ID,r.Officier_ID,r.Experience,r.Vehicule_Nbr,r.Skill,r.Matos,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment_IA as r,Cible as c,Pays as p 
									WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
									AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee >='$Mission_alt' AND r.Position IN(1,5,21) ORDER BY r.Experience DESC LIMIT 2";
									$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb-ok_dca');
									if($result)
									{
										$Flak_IA_Ground=true;
										$intro.='<div class="alert alert-warning"><b>La défense anti-aérienne ouvre le feu!</b></div>';
										while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
										{
											$Shoot_Dca=0;
											$Sec_DCA=false;
											if($data['Arme_AA3'] >0 and $Mission_alt <=100)
												$DCA_ID=$data['Arme_AA3'];
											elseif($data['Arme_AA2'] >0 and $Mission_alt <4000)
												$DCA_ID=$data['Arme_AA2'];
											else
												$DCA_ID=$data['Arme_AA'];
											$DCA_Unit=$data['ID'];
											$DCA_EXP=$data['Experience'];	
											$DCA_Nbr=$data['Vehicule_Nbr'];
											$DCA_Vehicule_ID=$data['Vehicule_ID'];
											if($DCA_Nbr >25)$DCA_Nbr=25;
											/*if($data['Bataillon']){
												$Sec_DCA=GetAvancement(mysqli_result(mysqli_query($con,"SELECT o.Avancement FROM Sections as s,Officier as o,Regiment as r 
												WHERE s.OfficierID=".$data['Bataillon']." AND s.SectionID=4 AND s.OfficierID=o.ID AND r.Officier_ID=s.OfficierID AND r.Lieu_ID='$Cible' AND o.Actif=0"),0)
												,$data['Pays'],0,1);
											}*/
											$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$DCA_ID'");
											if($resulta)
											{
												while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
												{
													$dca_cal=round($data3['Calibre']);
													$Arme_Multi=$data3['Multi'];
													$DCA_dg=$data3['Degats'];
													$Arme_Perf=$data3['Perf'];
													$Range=$data3['Portee'];
													$Arme_Portee_Max=$data3['Portee_max']+($Sec_DCA[1]*100);
												}
												mysqli_free_result($resulta);
											}
											if($Range >$Mission_alt)$Malus_Range+=(($Range-$Mission_alt)/100);
											if($dca_cal)
											{
												if($dca_cal >40 and $Mission_alt <501 and $Type_avion !=11 and mt_rand(0,$DCA_EXP)<50)
													$intro.='<br>La défense anti-aérienne semble étrangement silencieuse!';
												else
												{
													$Rafale=mt_rand(1,$DCA_Nbr);
													if($data['Skill'] ==30)
													{
														$Detect_bonus=10;
														$Bonus_2passe=$DCA_EXP+50;
														if($Rafale <($DCA_Nbr/2))$Rafale+=1;
													}
                                                    $dca_mult=$Arme_Multi*$Rafale;
													if($dca_mult >90)$dca_mult=90;
													if($Flak_IA_Ground)
														$DCA_mun=9999;
													else
														$DCA_mun=GetData($Unit_table,"ID",$DCA_Unit,"Stock_Munitions_".$dca_cal);
													if($DCA_mun >=$dca_mult)
													{
														if(!$Flak_IA_Ground)
															UpdateData($Unit_table,"Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$DCA_Unit);
														if($Mission_alt <501)
															$Detect=1;
														else
															$Detect=mt_rand(0,$DCA_EXP)+$meteo-$Malus_Range+$Detect_bonus+$Sec_DCA[1];
														//Trait Anti-aérien
														/*if($Flak_PJ_Ground)
														{
															if(IsSkill(30,$data['Officier_ID']))
															{
																$Detect+=10;
																$Bonus_2passe=$DCA_EXP+50;
															}
														}
														else*/
														if($Detect >0)
														{			
															//DCA sur Formation
															if($DCA_Nbr >0 and $Avion_Nbr >0)
															{
																$Formation_abattue=0;
																$DCA_Shoots=min($DCA_Nbr,$Avion_Nbr);
																if(in_array($data['Matos'],$Matos_mun))
																	$Mun_dca=$data['Matos'];
																else
																	$Mun_dca=$data['Muns'];
																$resultp=mysqli_query($con,"SELECT ID,Nom,Pilotage,Tactique,Skill FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 AND Moral>0 AND Courage>0 ORDER BY RAND() LIMIT ".$DCA_Shoots."");
																if($resultp)
																{
																	while($dataa=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
																	{
																		$Pilote_ia_dca=$dataa['ID'];
																		$Nom_pilote_ia=$dataa['Nom'];
																		$Tactique_dca=$dataa['Tactique']+$xp_avion;
																		$Pilotage_dca=$dataa['Pilotage']+$xp_avion;
																		$Shoot_Dca=mt_rand(0,$DCA_EXP)+$dca_mult;
																		if($data['Matos'] ==3)$Shoot_Dca+=2;
																		elseif($data['Matos'] ==9)$Shoot_Dca+=5;
																		elseif($data['Matos'] ==12)$Shoot_Dca+=10;
																		elseif($data['Matos'] ==22)$Shoot_Dca+=5;
																		$Shoot=$Shoot_Dca+$meteo+$VisAvion-$Malus_Range-$Tactique_dca-$Pilotage_dca-($VitAvion_lead/20)+$Bonus_2passe+$Sec_DCA[1];
																		if($dataa['Skill']==30)$Shoot-=5;
																		if($dataa['Skill']==41)$Shoot-=10;
																		$debug_intro.="<br>Shoot=".$Shoot." (+Shoot_Dca=".$Shoot_Dca.", -meteo=".$meteo.", +VisAvion=".$VisAvion.", -Malus_Range=".$Malus_Range.
																		", -Tactique_dca=".$Tactique_dca.", -Pilotage_dca=".$Pilotage_dca.", -VitAvion_lead/20=".$VitAvion_lead.", +Bonus_2passe=".$Bonus_2passe;
                                                                        $Shoot = Crit_Fumble($Shoot,$DCA_EXP);
                                                                        if($Shoot >1)
																		{
																			$Degats=(mt_rand(1,$DCA_dg)-$Blindage)*GetShoot($Shoot,$dca_mult);
																			if($data['Matos'] ==22)$Degats*=1.1;
																			$Degats=round(Get_Dmg($Mun_dca,$dca_cal,$Blindage,$Mission_alt,$Degats,$Arme_Perf,$Range,$Arme_Portee_Max));
																			//if($Mission_alt <4500)$Degats+=ceil($VisAvion);
                                                                            $Degats = Crit_Fumble($Degats,$DCA_dg*$dca_mult);
																			if($Degats >$HP_avion)
																			{
																				$intro.="<br>L'explosion met le feu à l'avion de <b>".$Nom_pilote_ia."</b>, ne lui laissant pas d'autre choix que de sauter en parachute!";
																				$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0 WHERE ID='$Pilote_ia_dca'");
																				if($Mission_Flight ==3)
																					$Avion3_Nbr_dca+=1;
																				elseif($Mission_Flight ==2)
																					$Avion2_Nbr_dca+=1;
																				else
																					$Avion1_Nbr_dca+=1;
																				$Avion_Nbr-=1;
																				$Formation_abattue+=1;
																				$Avions_Down_DCA[]=$Avion;
																				WoundPilotIA($Pilote_ia_dca);
																			}
																			elseif($Degats >$HP_avion/2)
																			{
																				$Avion_Nbr-=1;
																				$intro.="<br>L'explosion endommage sévèrement l'avion de ".$Nom_pilote_ia.", le forçant à faire demi-tour!";
																			}
																			elseif($Degats >0)
																				$intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", mais il peut heureusement continuer sa mission!";
																			else
																				$intro.="<br>Le blindage de l'avion de ".$Nom_pilote_ia." le protège des tirs ennemis, il peut continuer sa mission!";
																			if($Admin)$intro.="(Dégâts= ".$Degats.")";
																		}
																		else
																			$intro.="<br>La dca encadre l'avion de ".$Nom_pilote_ia." sans le toucher, il peut continuer sa mission!";
																	}
																	mysqli_free_result($resultp);
																	if($Formation_abattue >0)
																	{
																		$reset=mysqli_query($con,"UPDATE Unit SET Avion1_Nbr=Avion1_Nbr-'$Avion1_Nbr_dca',Avion2_Nbr=Avion2_Nbr-'$Avion2_Nbr_dca',Avion3_Nbr=Avion3_Nbr-'$Avion3_Nbr_dca' WHERE ID='$Unite'");
																		$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Experience=Experience+5 WHERE ID='$DCA_Unit'");
																		if(!$Flak_IA_Ground)
																			AddEvent("Avion",380,$Avion,$Unite,$DCA_Unit,$Cible,$DCA_Vehicule_ID,$DCA_ID);
																		else
																			AddEvent("Avion",381,$Avion,$Unite,$DCA_Unit,$Cible,$DCA_Vehicule_ID,$DCA_ID);
																	}
																}
															}
														}
														else
														{
															$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
															$debug_intro.="[Détection DCA aveuglette]= ".$Detect;
														}
													}
													else
														$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
												}//DCA silencieuse
											}
											else
												$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
										}
										mysqli_free_result($result);
									} //End DCA
									if($Avion_Nbr >0)
									{
										$Malus_Reperer_reg=GetMalusReperer($Zone,0);
										$Bonus_Camera=0;
										if($meteo >-50)
										{
											include_once('./jfv_avions.inc.php');
											$Array_Mod=GetAmeliorations($Avion);
											if($Array_Mod[17] >5)
											{
												if($Array_Mod[17] ==27)
													$Bonus_Camera=75;
												elseif($Array_Mod[17] ==26)
												{
													if($Mission_alt <=6000)
														$Bonus_Camera=50;
												}
											}
											elseif($Array_Mod[16] >5)
											{
												if($Array_Mod[16] ==26)
												{
													if($Mission_alt <=6000)
														$Bonus_Camera=50;
												}
												elseif($Array_Mod[16] ==25)
												{
													if($Mission_alt <=1000)
														$Bonus_Camera=10;
												}
											}
											$Bonus_Cam_Max=$Mission_alt/100;
											if($Bonus_Camera >$Bonus_Cam_Max)$Bonus_Camera=$Bonus_Cam_Max;
										}
										if($Type_avion ==1 or $Type_avion ==4 or $Type_avion ==12)
										{
											if($Zone ==6)
												$Zoneb=8;
											else
												$Zoneb=0;
											$Query_txt_rec=" AND r.Placement=".$Zoneb;
										}
										//Unités sur place
										/*$pj_unit=mysqli_query($con,"(SELECT r.ID,r.Officier_ID,r.Camouflage,r.Visible,r.Skill,c.Type,c.Taille,c.Detection,c.ID as navire FROM Regiment as r,Cible as c,Pays as p 
										WHERE r.Lieu_ID='$Cible'".$Query_txt_rec." AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position<>25 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction')
										UNION (SELECT r.ID,r.Officier_ID,r.Camouflage,r.Visible,r.Skill,c.Type,c.Taille,c.Detection,c.ID as navire FROM Regiment_IA as r,Cible as c,Pays as p 
										WHERE r.Lieu_ID='$Cible'".$Query_txt_rec." AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position<>25 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction')") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : reco-reg');*/
										$pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Camouflage,r.Visible,r.Skill,r.Matos,r.Pays,c.Type,c.Taille,c.Detection,c.ID as navire FROM Regiment_IA as r,Cible as c,Pays as p 
										WHERE r.Lieu_ID='$Cible'".$Query_txt_rec." AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position<>25 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : reco-reg');
										if($pj_unit)
										{
											$nbr_units_pj=0;				
											while($data=mysqli_fetch_array($pj_unit))
											{
												if($data['ID'] >0)
												{
													if(!$data['Camouflage'])$data['Camouflage']=1;
													if($data['Skill'] ==29 or $data['Skill'] ==25 or $data['Skill'] ==6)$data['Camouflage']*=1.1;
													elseif($data['Skill'] ==126 or $data['Skill'] ==129 or $data['Skill'] ==51)$data['Camouflage']*=1.2;
													elseif($data['Skill'] ==127 or $data['Skill'] ==130 or $data['Skill'] ==80)$data['Camouflage']*=1.3;
													elseif($data['Skill'] ==128 or $data['Skill'] ==131 or $data['Skill'] ==81)$data['Camouflage']*=1.4;
													if($data['Matos'] ==11)$data['Camouflage']*=1.1;
													$Taille=$data['Taille']/$data['Camouflage'];
													if($data['Type'] ==4 or $data['Type'] ==9 or $data['Type'] ==11 or $data['Type'] ==12)
													{
														if($data['Skill']==81 and mt_rand(0,100)<25)
															$Taille=1;
														elseif($data['Skill']==80 and mt_rand(0,100)<20)
															$Taille=1;
														elseif($data['Skill']==51 and mt_rand(0,100)<15)
															$Taille=1;
														elseif($data['Skill']==6 and mt_rand(0,100)<10)
															$Taille=1;
													}
													if($Taille >=2)
													{
                                                        $Vue_ailier_recce=mysqli_result(mysqli_query($con,"SELECT Vue FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 AND Moral>0 AND Courage>0 AND Cible='$Cible' ORDER BY RAND() LIMIT 1"),0) + $xp_avion;
														$Shoot=mt_rand(0,$Vue_ailier_recce)+($Stab/10)-($Malus_Reperer_reg*$data['Camouflage'])+$Taille+($meteo*3)-($Mission_alt/10)+$Bonus_ailier+$Bonus_steady;
														$Photo_shoot=mt_rand(0,50)+$Bonus_Camera+($Stab/10)+($meteo*3)-($Mission_alt/100)-($Malus_Reperer_reg*$data['Camouflage'])+$Bonus_steady+$Bonus_photo;
														$debug_intro.="<br>Shoot=".$Shoot." (+Vue_ailier=".$Vue_ailier_recce.", -meteo*3=".$meteo.", +Taille=".$Taille.", -Malus_Reperer_reg=".$Malus_Reperer_reg.
														", -Mission_alt/10=".$Mission_alt." +Stab/10=".$Stab." +Bonus_ailier+Bonus_steady)
														<br>Photo_shoot=".$Photo_shoot." (+Rand=0,50; -meteo*3=".$meteo.", +Bonus_Camera=".$Bonus_Camera.", -Malus_Reperer_reg=".$Malus_Reperer_reg.
														", -Mission_alt/100=".$Mission_alt." +Stab/10=".$Stab." +Bonus_steady+Bonus_photo)";
                                                        /**
                                                         * 23/09/17
                                                         */
														if($country ==2 or $country ==4){
														    $Shoot-=25;
                                                            $Photo_shoot-=25;
                                                        }
														if($Shoot >1 or $Photo_shoot >1)
														{
															if(!$data['Visible'])
															{
																if(!$data['Officier_ID'])
																	SetData("Regiment_IA","Visible",1,"ID",$data['ID']);
																else
																	SetData("Regiment","Visible",1,"ID",$data['ID']);
																$nbr_units_pj++;
															}
															$icons_unites.="<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
																	<fieldset class='veh_reco'>
																		<div class='row veh_body'>
																			<img src='images/vehicules/vehicule".$data['navire'].".gif'>
																		</div>
																	</fieldset></div>";
														}
													}
													if($data['Officier_ID'] >0 and $Mission_alt <2000)
													{
														if($data['Detection'] >-$meteo)
															$alerte_reco[] =$data['Officier_ID'];
													}
													if(in_array($data['Pays'], $Nations_IA)){
                                                        $nations_alert[]=$data['Pays'];
                                                    }
												}
											}
											mysqli_free_result($pj_unit);
											if($Skill_ailier ==35)$skills_m.="<img src='images/skills/skill35.png'>";
											if($icons_unites)
												$mes.="<h2>Unités repérées</h2><div class='row'>".$icons_unites."</div>";
											if($Zone ==6)
											{
												if($nbr_units_pj >0)
													$intro.='<div class="alert alert-warning"><b>Vous repérez au moins '.$nbr_units_pj.' navire(s) ennemi(s)</b></div>';
												else
													$intro.='<div class="alert alert-danger"><b>Vous ne parvenez à identifier aucun navire ennemi</b></div>';
											}
											else
											{
												if($nbr_units_pj >0)
                                                    $intro.='<div class="alert alert-warning"><b>Vous repérez au moins '.$nbr_units_pj.' unités ennemies</b></div>';
												else
													$intro.='<div class="alert alert-danger"><b>Vous ne parvenez à identifier aucune unité ennemie</b></div>';
											}
										}
										else
											$intro.='<div class="alert alert-danger">Vous ne détectez aucune cible digne d\'intérêt</div>';
										if($alerte_reco)
										{
											include_once('./jfv_msg.inc.php');
											$off_alerte=array_unique($alerte_reco);
											$off_count=count($off_alerte);
											for($x=0;$x<$off_count-1;$x++) 
											{
												if($off_alerte[$x] >0)
													SendMsgOff($off_alerte[$x],0,"Une reconnaissance aérienne ennemie a été détectée dans les environs de ".$Nom_Lieu,"Rapport de reconnaissance",0,2);
											}
											unset($alerte_reco);
											unset($off_alerte);
										}
										UpdateData("Unit","Reputation",$nbr_units_pj,"ID",$Unite,0,113);
										/**
                                         * IA
										*/
										if(mt_rand(0,1) ==0)
    										include_once('./em_ia1_ia.php');
									}
									else
										$mes.='<div class="alert alert-danger">Tous les avions de reconnaissance ont été abattus ou refoulés! La mission est un échec!</b></div>';
								//}
							}
							elseif($Type ==2 or $Type ==12) //bomb tactique ou naval
							{
								if($Type_avion ==2 or $Type_avion ==7 or $Type_avion ==10)
									$CT_Mult=1;
								else
									$CT_Mult=2;
								if($Zoneb){
									if($Zoneb ==10)$Zoneb=0;
									$Query_txt_tac=" AND r.Placement=".$Zoneb;
								}
                                if($Bomb_Form ==800)$VisAvion/=5;
                                elseif($Volets ==3)$VisAvion/=10;
								if(!$Officier_Adjoint and $OfficierEMID ==$Commandant)$EM_CT=1;
								elseif(!$Officier_Adjoint and !$Commandant)$EM_CT=1;
								if($Bomb_Form >499)
									$CT=GetModCT(8*$CT_Mult,$country,$EM_CT,0,$Pil_Front);
								elseif($Bomb_Form >249 or $Bomb_Form ==80)
									$CT=GetModCT(6*$CT_Mult,$country,$EM_CT,0,$Pil_Front);
								elseif($Bomb_Form >124)
									$CT=GetModCT(5*$CT_Mult,$country,$EM_CT,0,$Pil_Front);
								else
									$CT=GetModCT(4*$CT_Mult,$country,$EM_CT,0,$Pil_Front);
								if($Credits >=$CT)
								{
									$Patrol_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Cible='$Cible' AND j.Actif=1"),0);
									/*if($Patrol_Nbr >0 and !$Escorte_Nbr)
										$mes.="<br><b>La couverture de chasse ennemie empêche les avions d'attaque d'atteindre leur objectif! Pour pouvoir passer la couverture, vous devez placer une escorte de chasse à l'altitude d'attaque.</b>";
									else
									{*/
										//DCA
										$Malus_Range=$Mission_alt/100;
										$Unit_table='Regiment_IA';
										$query="SELECT r.ID,r.Pays,r.Bataillon,r.Vehicule_ID,r.Officier_ID,r.Experience,r.Vehicule_Nbr,r.Skill,r.Matos,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment_IA as r,Cible as c,Pays as p 
										WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
										AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee >='$Mission_alt' AND r.Position IN(1,5,21) ORDER BY c.Categorie ASC,r.Experience DESC,r.Vehicule_Nbr ASC LIMIT 2";
										$result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb-ok_dca');
										if($result)
										{
											$Flak_IA_Ground=true;
											$intro.='<div class="alert alert-warning"><b>La défense anti-aérienne ouvre le feu!</b></div>';
											while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
											{
												$Shoot_Dca=0;
												$Sec_DCA=false;
												if($data['Arme_AA3'] >0 and $Mission_alt <=500 and $Type_avion !=2)
													$DCA_ID=$data['Arme_AA3'];
												elseif($data['Arme_AA2'] >0 and $Mission_alt <4000)
													$DCA_ID=$data['Arme_AA2'];
												else
													$DCA_ID=$data['Arme_AA'];
												$DCA_Unit=$data['ID'];
												$DCA_EXP=$data['Experience'];	
												$DCA_Nbr=$data['Vehicule_Nbr'];
												$DCA_Vehicule_ID=$data['Vehicule_ID'];
												if($DCA_Nbr >20)$DCA_Nbr=20;
												/*if($data['Bataillon'])
												{
													$Sec_DCA=GetAvancement(mysqli_result(mysqli_query($con,"SELECT o.Avancement FROM Sections as s,Officier as o,Regiment as r 
													WHERE s.OfficierID=".$data['Bataillon']." AND s.SectionID=4 AND s.OfficierID=o.ID AND r.Officier_ID=s.OfficierID AND r.Lieu_ID='$Cible' AND o.Actif=0"),0)
													,$data['Pays'],0,1);
												}*/
												$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='$DCA_ID'");
												if($resulta)
												{
													while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
													{
														$dca_cal=round($data3['Calibre']);
														$Arme_Multi=$data3['Multi'];
														$DCA_dg=$data3['Degats'];
														$Arme_Perf=$data3['Perf'];
														$Range=$data3['Portee'];
														$Arme_Portee_Max=$data3['Portee_max']+($Sec_DCA[1]*100);
													}
													mysqli_free_result($resulta);
												}
												if($Range >$Mission_alt)$Malus_Range+=(($Range-$Mission_alt)/100);
												if($dca_cal)
												{
													if($dca_cal >40 and $Mission_alt <501 and $Type_avion !=11 and mt_rand(0,$DCA_EXP)<50)
														$intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
													else
													{
														$Rafale=mt_rand(1,$DCA_Nbr);
														if($data['Skill'] ==30)
														{
															$Detect_bonus=10;
															$Bonus_2passe=$DCA_EXP+50;
															if($Rafale <($DCA_Nbr/2))$Rafale+=1;
														}
														$dca_mult=$Arme_Multi*$Rafale;
														if($dca_mult >90)$dca_mult=90;
														if($Flak_IA_Ground)
															$DCA_mun=9999;
														else
															$DCA_mun=GetData($Unit_table,"ID",$DCA_Unit,"Stock_Munitions_".$dca_cal);
														if($DCA_mun >=$dca_mult)
														{
															if(!$Flak_IA_Ground)
																UpdateData($Unit_table,"Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$DCA_Unit);
															$Detect=$DCA_EXP+$meteo-$Malus_Range+$Detect_bonus+$Sec_DCA[1];
															//Trait Anti-aérien
															/*if($Flak_PJ_Ground)
															{
																if(IsSkill(30,$data['Officier_ID']))
																{
																	$Detect+=10;
																	$Bonus_2passe=$DCA_EXP+50;
																}
															}*/
															if($Detect >0)
															{			
																//DCA sur Formation
																if($DCA_Nbr >0 and $Avion_Nbr >0)
																{
																	$Formation_abattue=0;
																	$DCA_Shoots=min($DCA_Nbr,$Avion_Nbr);
																	if(in_array($data['Matos'],$Matos_mun))
																		$Mun_dca=$data['Matos'];
																	else
																		$Mun_dca=$data['Muns'];
																	$resultp=mysqli_query($con,"SELECT ID,Nom,Pilotage,Tactique,Skill FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 AND Moral>0 AND Courage>0 AND Cible='$Cible' ORDER BY RAND() LIMIT ".$DCA_Shoots."");
																	if($resultp)
																	{
																		while($dataa=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
																		{
																			$Pilote_ia_dca=$dataa['ID'];
																			$Nom_pilote_ia=$dataa['Nom'];
																			$Tactique_dca=$dataa['Tactique']+$xp_avion;
																			$Pilotage_dca=$dataa['Pilotage']+$xp_avion;
																			$Shoot_Dca=mt_rand(0,$DCA_EXP)+$dca_mult;
																			if($data['Matos'] ==3)$Shoot_Dca+=2;
																			elseif($data['Matos'] ==9)$Shoot_Dca+=5;
																			elseif($data['Matos'] ==12)$Shoot_Dca+=10;
																			elseif($data['Matos'] ==22)$Shoot_Dca+=5;
																			$Shoot=$Shoot_Dca+$meteo+$VisAvion-$Malus_Range-$Tactique_dca-$Pilotage_dca-($VitAvion_lead/20)+$Bonus_2passe+$Sec_DCA[1];
																			if($dataa['Skill']==30)$Shoot-=5;
																			if($dataa['Skill']==41)$Shoot-=10;
																			$debug_intro.="<br>Shoot=".$Shoot." (+Shoot_Dca=".$Shoot_Dca.", -meteo=".$meteo.", +VisAvion=".$VisAvion.", -Malus_Range=".$Malus_Range.
																			", -Tactique_dca=".$Tactique_dca.", -Pilotage_dca=".$Pilotage_dca.", -VitAvion_lead/20=".$VitAvion_lead.", +Bonus_2passe=".$Bonus_2passe;
                                                                            $Shoot = Crit_Fumble($Shoot,$DCA_EXP);
																			if($Shoot >1)
																			{
																				$Degats=(mt_rand(1,$DCA_dg)-$Blindage)*GetShoot($Shoot,$dca_mult);
																				if($data['Matos'] ==22)$Degats*=1.1;
																				$Degats=round(Get_Dmg($Mun_dca,$dca_cal,$Blindage,$Mission_alt,$Degats,$Arme_Perf,$Range,$Arme_Portee_Max));
                                                                                $Degats = Crit_Fumble($Degats,$DCA_dg*$dca_mult);
                                                                                //if($Mission_alt <4500)$Degats+=ceil($VisAvion);
																				if($Degats >$HP_avion)
																				{
																					$intro.="<br>L'explosion met le feu à l'avion de <b>".$Nom_pilote_ia."</b>, ne lui laissant pas d'autre choix que de sauter en parachute!";
																					$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0 WHERE ID='$Pilote_ia_dca'");
																					if($Mission_Flight ==3)
																						$Avion3_Nbr_dca+=1;
																					elseif($Mission_Flight ==2)
																						$Avion2_Nbr_dca+=1;
																					else
																						$Avion1_Nbr_dca+=1;
																					$Avion_Nbr-=1;
																					$Formation_abattue+=1;
																					$Avions_Down_DCA[]=$Avion;
																					WoundPilotIA($Pilote_ia_dca);
																				}
																				elseif($Degats >$HP_avion/2)
																				{
																					$Avion_Nbr-=1;
																					$intro.="<br>L'explosion endommage sévèrement l'avion de ".$Nom_pilote_ia.", le forçant à faire demi-tour!";
																				}
																				elseif($Degats >0)
																					$intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", mais il peut heureusement continuer sa mission!";
																				else
																					$intro.="<br>Le blindage de l'avion de ".$Nom_pilote_ia." le protège des tirs ennemis, il peut continuer sa mission!";
																				if($Admin)$intro.='(Dégâts= '.$Degats.')';
																			}
																			else
																				$intro.="<br>La dca encadre l'avion de ".$Nom_pilote_ia." sans le toucher, il peut continuer sa mission!";
																		}
																		mysqli_free_result($resultp);
																		if($Formation_abattue >0)
																		{
																			$reset=mysqli_query($con,"UPDATE Unit SET Avion1_Nbr=Avion1_Nbr-'$Avion1_Nbr_dca',Avion2_Nbr=Avion2_Nbr-'$Avion2_Nbr_dca',Avion3_Nbr=Avion3_Nbr-'$Avion3_Nbr_dca' WHERE ID='$Unite'");
																			$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Experience=Experience+5 WHERE ID='$DCA_Unit'");
																			if(!$Flak_IA_Ground)
																				AddEvent("Avion",380,$Avion,$Unite,$DCA_Unit,$Cible,$DCA_Vehicule_ID,$DCA_ID);
																			else
																				AddEvent("Avion",381,$Avion,$Unite,$DCA_Unit,$Cible,$DCA_Vehicule_ID,$DCA_ID);
																		}
																	}
																}
															}
															else
															{
																$intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
																$debug_intro.="[Détection DCA aveuglette]= ".$Detect;
															}
														}
														else
															$intro.='<br>La défense anti-aérienne semble étrangement silencieuse!';
													}//DCA silencieuse
												}
												else
													$debug_intro.=" [Dca_cal de DCA_ID ".$DCA_ID."]= ".$dca_cal;
											}
											mysqli_free_result($result);
										} //End DCA
										if($Avion_Nbr >0)
										{
											$Avions_Bomb=$Avion_Nbr;
											$Malus_Reperer_reg=GetMalusReperer($Zone,0);
											if($Bomb_Form >800 and $Type ==2)$Bomb_Form=500;
											//Unités sur place
											if($Bomb_Form ==800)
												$pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Placement,r.Position,r.Skill,c.Taille,c.Blindage_f,c.Blindage_l,c.Blindage_t,c.Blindage_a,c.Detection,c.mobile,c.Type,c.Flak,c.Vitesse,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,
												r.Fret,r.Fret_Qty,r.HP,c.Nom,c.ID as Veh,c.HP as HP_max FROM Regiment_IA as r,Cible as c,Pays as p 
												WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position NOT IN(11,25) AND r.Visible=1 AND r.Placement=8 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Bomb_IA=0") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : emia1-tac-reg');
												/*(SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Placement,r.Position,r.Skill,c.Taille,c.Blindage_f,c.Blindage_l,c.Blindage_t,c.Blindage_a,c.Detection,c.mobile,c.Type,c.Flak,c.Vitesse,
												r.Fret,r.Fret_Qty,r.HP,c.Nom,c.ID as Veh,c.HP as HP_max FROM Regiment as r,Cible as c,Pays as p 
												WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position NOT IN(11,25) AND r.Visible=1 AND r.Placement=8 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Bomb_IA=0) UNION (*/
											else
												$pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Placement,r.Position,r.Skill,c.Taille,c.Blindage_f,c.Blindage_l,c.Blindage_t,c.Blindage_a,c.Detection,c.mobile,c.Type,c.Flak,c.Vitesse,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,
												r.Fret,r.Fret_Qty,r.HP,c.Nom,c.ID as Veh,c.HP as HP_max FROM Regiment_IA as r,Cible as c,Pays as p 
												WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position NOT IN(11,25) AND r.Visible=1".$Query_txt_tac." AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Bomb_IA=0") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : emia1-tac-reg');
												/*(SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Placement,r.Position,r.Skill,c.Taille,c.Blindage_f,c.Blindage_l,c.Blindage_t,c.Blindage_a,c.Detection,c.mobile,c.Type,c.Flak,c.Vitesse,
												r.Fret,r.Fret_Qty,r.HP,c.Nom,c.ID as Veh,c.HP as HP_max FROM Regiment as r,Cible as c,Pays as p 
												WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position NOT IN(11,25) AND r.Visible=1".$Query_txt_tac." AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Bomb_IA=0) UNION (*/
											if($Bomb_Form ==20)
											{
												$Canon_Nbr=1;
												$Gun_ID=220;
												include_once('./jfv_avions.inc.php');
												$Array_Mod=GetAmeliorations($Avion);
												if($Array_Mod[3])
												{
													$Gun_ID=$Array_Mod[3];
													if($Array_Mod[9] >$Array_Mod[6])
														$Canon_Nbr=$Array_Mod[9];
													else
														$Canon_Nbr=$Array_Mod[6];
												}
												$resultw=mysqli_query($con,"SELECT Nom,Multi,Degats,Perf FROM Armes WHERE ID='$Gun_ID'");
												if($resultw)
												{
													while($dataw=mysqli_fetch_array($resultw))
													{
														$Canon_Perf=$dataw['Perf'];
														$Canon_Degats=$dataw['Degats'];
														$Canon_Multi=$dataw['Multi']*$Canon_Nbr;
													}
													mysqli_free_result($resultw);
												}
											}
											elseif($Bomb_Form ==80)
											{
												$Gun_ID=177;
												include_once('./jfv_avions.inc.php');
												$Array_Mod=GetAmeliorations($Avion);
												$Canon_Nbr=$Array_Mod[35];
												$Canon_Perf=120;
												$Canon_Degats=1800;
												$Canon_Multi=$Canon_Nbr;
											}
											if($pj_unit)
											{
												while($data=mysqli_fetch_array($pj_unit))
												{
													if($data['ID'] >0 and $Avions_Bomb >0)
													{
                                                        $Query_Pils='';
                                                        if(is_array($Pilotes_away)){
                                                            $Pilotes_awayz=implode(',',$Pilotes_away);
                                                            $Query_Pils=" AND ID NOT IN (".$Pilotes_awayz.")";
                                                        }
                                                        $resultp=mysqli_query($con,"SELECT ID,Nom,Pilotage,Bombardement FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 AND Moral>0 AND Courage>0 AND Cible='$Cible'".$Query_Pils." ORDER BY RAND() LIMIT 1");
                                                        if($resultp) {
                                                            while ($datap = mysqli_fetch_array($resultp, MYSQLI_ASSOC)) {
                                                                $Nom_pilote_ia_bomb = $datap['Nom'];
                                                                $Bomb_ailier_bomb = $datap['Bombardement'] + $xp_avion;
                                                                $Pilotes_away[]=$datap['ID'];
                                                            }
                                                        }
                                                        /*if($data['Flak'] and mt_rand(0,300) < ($data['Experience']-$Bonus_esq_dca)){ //TODO: terminer unités DCA repousse attaque
                                                            if($data['Arme_AA3'] >0 and $Mission_alt <=500 and $Type_avion !=2)
                                                                $DCA_Gun_ID=$data['Arme_AA3'];
                                                            elseif($data['Arme_AA2'] >0 and $Mission_alt <4000)
                                                                $DCA_Gun_ID=$data['Arme_AA2'];
                                                            else
                                                                $DCA_Gun_ID=$data['Arme_AA'];
                                                            $resultdca=mysqli_query($con,"SELECT Nom,Multi,Degats,Perf FROM Armes WHERE ID='$DCA_Gun_ID'");
                                                            if($resultdca)
                                                            {
                                                                while($datadca=mysqli_fetch_array($resultdca))
                                                                {
                                                                    $DCA_Perf=$datadca['Perf'];
                                                                    $DCA_Degats=$datadca['Degats'];
                                                                    $DCA_Multi=$datadca['Multi']*$Canon_Nbr;
                                                                }
                                                                mysqli_free_result($resultdca);
                                                            }
                                                        }*/
														$rand_tir=11;
														$Bombs_Hit=1;
														$Bomb_mult=1;
														$def_c=0;
                                                        $bl_max=30;
														if($data['HP'] >0)
															$HP_eni=$data['HP'];
														else
															$HP_eni=$data['HP_max'];
														if($Bomb_Form ==800)
														{
															$Esquive=mt_rand(1,$data['Experience'])+$data['Vitesse'];
															$def_c=$data['Blindage_f'];
															if($data['Officier_ID'] >0){
																if(IsSkill(38,$data['Officier_ID']))$Esquive+=25;
															}
															elseif($data['Skill'] ==36)
																$Esquive+=10;
															elseif($data['Skill'] ==147)
																$Esquive+=15;
															elseif($data['Skill'] ==148)
																$Esquive+=20;
															elseif($data['Skill'] ==149)
																$Esquive+=25;
															$rand_tir=mt_rand(0,$Bomb_ailier_bomb);
															$Shoot=$rand_tir+($Stab/10)+$meteo-$Esquive-($Mission_alt/100)+$Bonus_ailier+$Bonus_steady+$Bonus_torp+$Bonus_bomb;
															$msg_hit=$Nom_pilote_ia_bomb.' effectue un torpillage, ';
															if($Bonus_torp)$skills_m.="<img src='images/skills/skill129.png'>";
														}
														elseif($Volets ==3) //Bomb en Piqué
														{
                                                            $bl_max=50;
															$Bomb_mult=1.5;
															if(!$data['Blindage_t'])
																$def_c=$data['Blindage_f']/2;
															else
																$def_c=$data['Blindage_t'];
															$Esquive=mt_rand(0,$data['Experience']/5);
															$rand_tir=mt_rand(0,$Bomb_ailier_bomb);
															$Shoot=$rand_tir+($meteo/2)-$Esquive+50+$Bonus_ailier+$Bonus_pique+$Bonus_bomb;
															$msg_hit=$Nom_pilote_ia_bomb.' effectue une attaque en piqué, ';
															if($Bonus_pique)$skills_m.="<img src='images/skills/skill40.png'>";
														}
														else
														{
															$local_hit=mt_rand(0,10);
															if($local_hit ==10)
																$def_c=$data['Blindage_a'];
															elseif($local_hit >7)
																$def_c=$data['Blindage_l'];
															elseif($local_hit >3)
																$def_c=$data['Blindage_t'];
															else
																$def_c=$data['Blindage_f'];
															if($Type ==2 and $Zone !=6){
																if($Pays_eni ==$data['Pays'] and $data['Placement'] ==0 and $Fortification >0)
																	$def_c+=Get_Blindage($Zone,$data['Taille'],$Fortification,$data['Position']);
																elseif(!$Fortification and $data['Position'] ==2)
																	$def_c+=Get_Blindage($Zone,$data['Taille'],0,2);
															}
															if($Bomb_Form ==80) //rockets
																$Esquive=mt_rand(0,$data['Experience']/5)+($data['Vitesse']/10);
															elseif($Bomb_Form ==20) //gun
																$Esquive=mt_rand(0,$data['Experience']/5);
															else //bombs
																$Esquive=mt_rand(0,$data['Experience']/5)+($data['Vitesse']/2);
															if($data['Officier_ID'] >0){
																if(IsSkill(38,$data['Officier_ID']))$Esquive+=25;
															}
															$rand_tir=mt_rand(0,$Bomb_ailier_bomb);
															$Shoot=$rand_tir+($Stab/10)+$meteo-$Esquive-($Mission_alt/100)+$Bonus_ailier+$Bonus_steady+$Bonus_bomb+$Canon_Multi;
															$msg_hit=$Nom_pilote_ia_bomb.' effectue une attaque, ';
														}
														if($Bomb_Form !=20 and $Bomb_Form !=80)
														{
															$Canon_Perf=$Bomb_Form*$Bomb_mult;
															$Canon_Degats=$Bomb_Form*$Bomb_mult;
															if($Bomb_Form ==800)
                                                                $Canon_Multi=300-$def_c;
															elseif($data['mobile'] ==5)
                                                                $Canon_Multi=$Bomb_Form-$def_c;
															else{
																$Canon_Multi=$bl_max-$def_c;
																$def_c*=4;
															}
                                                            if($Canon_Multi >100)$Canon_Multi=100;
															if($Canon_Multi <1)$Canon_Multi=1;
														}
														if($data['Position'] ==8 and $Bonus_repere)$Shoot+=5;
														$debug_intro.="<br>Shoot Bomb Tac=".$Shoot." (+rand_tir=".$rand_tir.", -meteo=".$meteo.", -Esquive=".$Esquive.
														", +Stab/10=".$Stab.", -alt/100=".$Mission_alt.") mult_deg".$Canon_Multi.", def_c".$def_c;
														if($rand_tir >1 and ($Shoot >0 or $rand_tir ==$Bomb_ailier_bomb))
														{
															$Degats=0;
															if($Bombs_Hit >0)
															{
																if($Canon_Perf >$def_c)
																	$Degats+=(mt_rand(1,$Canon_Degats)*$Canon_Multi);
																if($Degats <1)$Degats=mt_rand(1,10);
															}
															else
																$msghit.='L\'attaque manque de précision!';
															$HP_eni-=$Degats;
															if($HP_eni <1)
															{
																$Gain_Reput+=2;
																$Tues=1;
																/*if($data['Officier_ID'] >0)
																{
																	$resulto=mysqli_query($con,"SELECT Transit,Avancement FROM Officier WHERE ID='".$data['Officier_ID']."'");
																	if($resulto)
																	{
																		while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
																		{
																			$Transit=$datao['Transit'];
																			$Avancement_Off_eni=$datao['Avancement'];
																		}
																		mysqli_free_result($resulto);
																		unset($datao);
																	}
																	if($data['Vehicule_Nbr'] >25)
																	{
																		$Tues=floor(1+(($Degats-$data['HP_max'])/$data['HP_max']));
																		if($data['Position'] ==2 and $Tues >1)$Tues=floor($Tues/2);
																		if($Tues >25)$Tues=25;
																	}
																	if($data['Fret'] >0)
																	{
																		if($data['Fret'] ==888)
																			UpdateData("Pays","Special_Score",-1,"ID",$data['Pays']);
																		elseif($data['Fret'] ==200 and $data['Fret_Qty'] >0)
																		{
																			if($data['Vehicule_Nbr'] <2)
																			{
																				$reset=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0,HP=0,Position=6 WHERE ID='".$data['ID']."'");
																				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Moral=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Experience=0,Skill=0,Visible=0 WHERE ID='".$data['Fret_Qty']."'");
																			}
																		}
																		elseif($data['Fret_Qty'] >0)
																		{
																			$Perte_Stock=$data['Fret_Qty']/$data['Vehicule_Nbr'];
																			UpdateData("Regiment","Fret_Qty",-$Perte_Stock,"ID",$data['ID']);
																		}
																	}
																	if($data['mobile'] ==5)
																		$HP_new=$data['HP_max'];
																	else
																		$HP_new=0;
																	$Nbr_end=$data['Vehicule_Nbr']-$Tues;
																	if($data['Position'] ==11) //unités en transit
																		$cible_pos_finale=11;
																	else
																	{
																		if($Nbr_end <(GetMaxVeh($data['Type'],$data['mobile'],$data['Flak'],25000)/2))
																			$cible_pos_finale=8;
																		elseif($data['mobile'] !=5 and ($data['Position'] ==4 or $data['Position'] ==0))
																			$cible_pos_finale=2;
																		else
																			$cible_pos_finale=$data['Position'];
																	}
																	if($Transit >0 and $Nbr_end <1)
																	{
																		$reset=mysqli_query($con,"UPDATE Regiment SET Experience=0,Skill=0,Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
																		Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
																		Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0,Position=6,Vehicule_Nbr=0,Visible=0 WHERE Officier_ID='$Transit' AND Vehicule_Nbr>0 ORDER BY RAND() LIMIT 1");
																		AddEventGround(411,$Avion,$Transit,$data['ID'],$Cible,1,$data['Vehicule_ID']);
																	}
																	$reset=mysqli_query($con,"UPDATE Regiment SET Position='$cible_pos_finale',HP='$HP_new',Vehicule_Nbr='$Nbr_end',Visible=0,Bomb_IA=1,Experience=Experience+1 WHERE ID='".$data['ID']."'");;
																	AddEventGround(602,$Avion,0,$data['ID'],$Cible,$Tues,$data['Vehicule_ID']);
																}
																else
																{*/
																	if($data['Vehicule_ID'] >4999)
																	{
																		if($data['Vehicule_Nbr'] ==1)
																			$query_reset_ia="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Position=6,HP=0,Fret=0,Fret_Qty=0,Visible=0,Bomb_IA=1 WHERE ID='".$data['ID']."'";
																		else
																		{
																			$HP_new=$data['HP_max'];
																			$Nbr_end=$data['Vehicule_Nbr']-1;
																			$query_reset_ia="UPDATE Regiment_IA SET Position=8,HP='$HP_new',Vehicule_Nbr='$Nbr_end',Visible=0,Bomb_IA=1,Mission_Lieu_D='$Cible',Mission_Type_D=7,Experience=Experience+1 WHERE ID='".$data['ID']."'";
																		}
																	}
																	elseif($data['Vehicule_ID'] ==424) //Train
																		$query_reset_ia="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,HP=0,Fret=0,Fret_Qty=0,Visible=0,Bomb_IA=1,Position=6 WHERE ID='".$data['ID']."'";
																	elseif($data['Placement'] ==8) //Transit EM
																	{
																		$Nbr_end=floor($data['Vehicule_Nbr']*0.75);
																		$query_reset_ia="UPDATE Regiment_IA SET Position=8,Vehicule_Nbr='$Nbr_end',Bomb_IA=1,Mission_Lieu_D='$Cible',Mission_Type_D=7 WHERE ID='".$data['ID']."'";
																	}
																	else
																	{
																		$query_tac_add_ia=false;
																		if($data['Vehicule_Nbr'] >25)
																		{
																			$Tues=floor(1+(($Degats-$data['HP_max'])/$data['HP_max']));
																			if($data['Position'] ==2 and $Tues >1)$Tues=floor($Tues/2);
																			if($Tues >25)$Tues=25;
																		}
																		$Nbr_end=$data['Vehicule_Nbr']-$Tues;
																		if($data['Position'] ==11)
																		{
																			$query_tac_add_ia=",Bomb_IA=1";
																			$cible_pos_finale=11;
																		}
																		else
																		{
																			if($Nbr_end < (GetMaxVeh($data['Type'],$data['mobile'],$data['Flak'],25000)/2))
																			{
																				$query_tac_add_ia=",Bomb_IA=1";
																				$cible_pos_finale=8;
																			}
																			else
																				$cible_pos_finale=$data['Position'];
																		}
																		$query_reset_ia="UPDATE Regiment_IA SET Position='$cible_pos_finale',Vehicule_Nbr='$Nbr_end'".$query_tac_add_ia.",Mission_Lieu_D='$Cible',Mission_Type_D=7,Experience=Experience+1 WHERE ID='".$data['ID']."'";
																	}
																	$reset=mysqli_query($con,$query_reset_ia);;
																	AddEventGround(702,$Avion,0,$data['ID'],$Cible,$Tues,$data['Vehicule_ID']);
																//}
																$msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts. ".$Tues." <b>".$data['Nom']."</b> détruit!</p>";
															}
															elseif($Degats >1)
															{
																//Dégats persistants grosses unités navales
																if($data['mobile'] ==5 and $Degats >10)
																{
																	$Gain_Reput+=1;
																	if($data['Officier_ID'] >0)
																		$DB='Regiment';
																	else
																		$DB='Regiment_IA';
																	UpdateData($DB,"HP",-$Degats,"ID",$data['ID']);
																	$HP_final=GetData($DB,"ID",$data['ID'],"HP");
																	if($HP_final <1)
																	{
																		$msghit="<p>".$msg_hit." , occasionnant ".round($Degats)." dégâts, achève le <b>".$data['Nom']."</b>!</p>";
																		AddVictoire_atk("Avion",$data['ID'],$data['Vehicule_ID'],$Avion,0,$Unite,$Cible,$Bomb_Form,$data['Pays'],0,$Mission_alt,$Nuit,$Degats);
																		$reset=mysqli_query($con,"UPDATE $DB SET Position=8,HP='$hp_ori',Vehicule_Nbr=Vehicule_Nbr-1,Bomb_IA=1,Experience=Experience+1 WHERE ID='".$data['ID']."'");;
																	}
																	else
																	{
																		$msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts et endommageant le <b>".$data['Nom']."</b>!</p>";
																		if($data['Officier_ID'] >0)
																			AddEventGround(609,$Avion,0,$data['ID'],$Cible,$Degats,$data['Vehicule_ID']);
																		else
																		{
																			AddEventGround(709,$Avion,0,$data['ID'],$Cible,$Degats,$data['Vehicule_ID']);
																			$reset=mysqli_query($con,"UPDATE Regiment_IA SET Mission_Type_D=7,Mission_Lieu_D='$Cible',Experience=Experience+1 WHERE ID='".$data['ID']."'");;
																		}
																	}
																}
																else
																	$msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts, n'a pas détruit le <b>".$data['Nom']."</b>!</p>";
															}
															else
																$msghit="<p>".$msg_hit.", mais le <b>".$data['Nom']."</b> ne semble pas endommagé!</p>";
														}
														else
														{
															if($Bomb_Form ==20 or $Bomb_Form ==80)
																$Arme_txt='Le tir de '.$Nom_pilote_ia_bomb;
															else
																$Arme_txt='La bombe de '.$Nom_pilote_ia_bomb;
															if($Shoot <-100)
																$msghit="<p>".$Arme_txt." explose très loin à côté de la cible. Cette attaque est totalement manquée!</p>";
															elseif($Shoot <-50)
																$msghit="<p>".$Arme_txt." explose à côté de la cible. Cette attaque a manqué de précision!</p>";
															else
																$msghit="<p>".$Arme_txt." explose juste à côté de la cible. Quel manque de chance!</p>";
															if($Admin)$msghit.=" (Shoot = ".$Shoot.")";
														}
														$mes.='<div class="alert alert-warning">'.$msghit.'</div>';
                                                        if(in_array($data['Pays'], $Nations_IA)){
                                                            $nations_alert[]=$data['Pays'];
                                                        }
													}
													$Avions_Bomb-=1;
												}
												mysqli_free_result($pj_unit);
												if($Gain_Reput)UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,112);
											}
											else
												$intro.='<div class="alert alert-warning">Vous ne détectez aucune cible digne d\'intérêt</div>';
										}
										else
											$mes.='<div class="alert alert-danger">Tous les avions d\'attaque ont été abattus ou refoulés! La mission est un échec!</div>';
									//}
									AddAtk_IA($Cible,$Unite,$Avion_Nbr,$Avion,$Bomb_Form,$Mission_alt,0,$Cycle,$DCA_Nbr,$Escorte_Nbr,$Patrol_Nbr);
                                    /**
                                     * IA
                                     */
                                    include_once('./em_ia1_ia.php');
								}
							}
							elseif($Type ==29) //ASM
							{
								if(!$Officier_Adjoint and $OfficierEMID ==$Commandant)$EM_CT=1;
								elseif(!$Officier_Adjoint and !$Commandant)$EM_CT=1;
								$CT=GetModCT(4,$country,$EM_CT);
								if($Credits >=$CT)
								{
									$Patrol_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND p.Faction<>'$Faction' AND j.Couverture='$Cible' AND j.Cible='$Cible' AND j.Actif=1"),0);
                                    //DCA
                                    $Flak_IA_Ground=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c,Pays as p 
                                    WHERE r.Lieu_ID='$Cible' AND r.Vehicule_ID=c.ID AND r.Pays=p.Pays_ID AND p.Faction<>'$Faction' 
                                    AND c.Flak >0 AND c.Portee>='$Mission_alt' AND r.Vehicule_Nbr >0 AND r.Position IN(1,5,21)"),0);
                                    if($Flak_PJ_Ground or $Flak_IA_Ground)
                                    {
                                        $Malus_Range=$Mission_alt/100;
                                        if($Flak_IA_Ground){
                                            $query="SELECT r.ID,r.Vehicule_ID,r.Officier_ID,r.Experience,r.Vehicule_Nbr,r.Skill,c.Arme_AA,c.Arme_AA2,c.Arme_AA3,c.Portee,c.mobile FROM Regiment_IA as r,Cible as c,Pays as p 
                                            WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' 
                                            AND c.Flak >0 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Portee >='$Mission_alt' AND r.Position IN(1,5) ORDER BY r.Experience DESC LIMIT 2";
                                            $Unit_table='Regiment_IA';
                                        }
                                        $result=mysqli_query($con,$query) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : asm-ok_dca');
                                        if($result)
                                        {
                                            $intro.='<div class="alert alert-warning"><b>La défense anti-aérienne ouvre le feu!</b></div>';
                                            while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                                            {
                                                $Shoot_Dca=0;
                                                if($data['Arme_AA3'] >0 and $Mission_alt <=500)
                                                    $DCA_ID=$data['Arme_AA3'];
                                                elseif($data['Arme_AA2'] >0 and $Mission_alt <4000)
                                                    $DCA_ID=$data['Arme_AA2'];
                                                else
                                                    $DCA_ID=$data['Arme_AA'];
                                                $DCA_Unit=$data['ID'];
                                                $DCA_EXP=$data['Experience'];
                                                $DCA_Nbr=$data['Vehicule_Nbr'];
                                                $DCA_Vehicule_ID=$data['Vehicule_ID'];
                                                if($data['mobile'] ==5) //Navire
                                                    $Range=GetData("Armes","ID",$DCA_ID,"Portee");
                                                else
                                                    $Range=$data['Portee'];
                                                if($Range >$Mission_alt)
                                                    $Malus_Range+=(($Range-$Mission_alt)/100);
                                                $dca_cal=round(GetData("Armes","ID",$DCA_ID,"Calibre"));
                                                if($dca_cal)
                                                {
                                                    if($dca_cal >40 and $Mission_alt <501 and $Type_avion !=11 and mt_rand(0,$DCA_EXP)<50)
                                                        $intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
                                                    else
                                                    {
                                                        $dca_mult=GetData("Armes","ID",$DCA_ID,"Multi")*mt_rand(1,$DCA_Nbr);
                                                        if($Flak_IA_Ground)
                                                            $DCA_mun=9999;
                                                        else
                                                            $DCA_mun=GetData($Unit_table,"ID",$DCA_Unit,"Stock_Munitions_".$dca_cal);
                                                        if($DCA_mun >=$dca_mult)
                                                        {
                                                            if(!$Flak_IA_Ground)
                                                                UpdateData($Unit_table,"Stock_Munitions_".$dca_cal,-$dca_mult,"ID",$DCA_Unit);
                                                            $Detect=$DCA_EXP+$meteo-$Malus_Range;
                                                            //Trait Anti-aérien
                                                            if($data['Skill'] ==30){
                                                                $Detect+=10;
                                                                $Bonus_2passe=$DCA_EXP+50;
                                                            }
                                                            if($Detect >0)
                                                            {
                                                                //DCA sur Formation
                                                                if($DCA_Nbr >0 and $Avion_Nbr >0)
                                                                {
                                                                    $Formation_abattue=0;
                                                                    $DCA_Shoots=min($DCA_Nbr,$Avion_Nbr);
                                                                    /*if($Mission_alt <5000 and $VitAvion_lead <$Shoot_Dca)
                                                                        $Shoot_Dca+=((5000-$Mission_alt)/50);*/
                                                                    $DCA_dg=GetData("Armes","ID",$DCA_ID,"Degats");
                                                                    $resultp=mysqli_query($con,"SELECT ID,Nom,Pilotage,Tactique,Skill FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 AND Moral>0 AND Courage>0 ORDER BY RAND() LIMIT ".$DCA_Shoots."");
                                                                    if($resultp)
                                                                    {
                                                                        while($dataa=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
                                                                        {
                                                                            $Pilote_ia_dca=$dataa['ID'];
                                                                            $Nom_pilote_ia=$dataa['Nom'];
                                                                            $Tactique_dca=$dataa['Tactique']+$xp_avion;
                                                                            $Pilotage_dca=$dataa['Pilotage']+$xp_avion;
                                                                            $Shoot_Dca=mt_rand(0,$DCA_EXP/2)+$dca_mult;
                                                                            $Shoot=$Shoot_Dca+$meteo+$VisAvion-$Malus_Range-$Tactique_dca-$Pilotage_dca-($VitAvion_lead/20)+$Bonus_2passe;
                                                                            if($dataa['Skill']==30)$Shoot-=5;
                                                                            if($dataa['Skill']==41)$Shoot-=10;
                                                                            $debug_intro.="<br>Shoot=".$Shoot." (+Shoot_Dca=".$Shoot_Dca.", -meteo=".$meteo.", +VisAvion=".$VisAvion.", -Malus_Range=".$Malus_Range.
                                                                            ", -Tactique_dca=".$Tactique_dca.", -Pilotage_dca=".$Pilotage_dca.", -VitAvion_lead/20=".$VitAvion_lead.", +Bonus_2passe=".$Bonus_2passe;
                                                                            if($Shoot >1)
                                                                            {
                                                                                $Degats=round((mt_rand(1,$DCA_dg)-$Blindage)*GetShoot($Shoot,$dca_mult));
                                                                                //if($Mission_alt <4500)$Degats+=ceil($VisAvion);
                                                                                if($Degats >$HP_avion)
                                                                                {
                                                                                    $intro.="<br>L'explosion met le feu à l'avion de <b>".$Nom_pilote_ia."</b>, ne lui laissant pas d'autre choix que de sauter en parachute!";
                                                                                    $reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0 WHERE ID='$Pilote_ia_dca'");
                                                                                    if($Mission_Flight ==3)
                                                                                        $Avion3_Nbr_dca+=1;
                                                                                    elseif($Mission_Flight ==2)
                                                                                        $Avion2_Nbr_dca+=1;
                                                                                    else
                                                                                        $Avion1_Nbr_dca+=1;
                                                                                    $Avion_Nbr-=1;
                                                                                    $Formation_abattue+=1;
                                                                                    $Avions_Down_DCA[]=$Avion;
                                                                                    WoundPilotIA($Pilote_ia_dca);
                                                                                }
                                                                                elseif($Degats >0)
                                                                                    $intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", mais il peut heureusement continuer sa mission!";
                                                                                else
                                                                                    $intro.="<br>Le blindage de l'avion de ".$Nom_pilote_ia." le protège des tirs ennemis, il peut continuer sa mission!";
                                                                                if($Admin)$intro.="(Dégâts= ".$Degats.")";
                                                                            }
                                                                            else
                                                                                $intro.="<br>La dca encadre l'avion de ".$Nom_pilote_ia." sans le toucher, il peut continuer sa mission!";
                                                                        }
                                                                        mysqli_free_result($resultp);
                                                                        if($Formation_abattue >0)
                                                                        {
                                                                            $reset=mysqli_query($con,"UPDATE Unit SET Avion1_Nbr=Avion1_Nbr-'$Avion1_Nbr_dca',Avion2_Nbr=Avion2_Nbr-'$Avion2_Nbr_dca',Avion3_Nbr=Avion3_Nbr-'$Avion3_Nbr_dca' WHERE ID='$Unite'");
                                                                            $reset2=mysqli_query($con,"UPDATE Regiment_IA SET Experience=Experience+5 WHERE ID='$DCA_Unit'");
                                                                            if(!$Flak_IA_Ground)
                                                                                AddEvent("Avion",380,$Avion,$Unite,$DCA_Unit,$Cible,$DCA_Vehicule_ID,$DCA_ID);
                                                                            else
                                                                                AddEvent("Avion",381,$Avion,$Unite,$DCA_Unit,$Cible,$DCA_Vehicule_ID,$DCA_ID);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $intro.="<br>La défense anti-aérienne ouvre le feu à l'aveuglette, ne semblant pas vous avoir repéré.";
                                                                $debug_intro.="[Détection DCA aveuglette]= ".$Detect;
                                                            }
                                                        }
                                                        else
                                                            $intro.="<br>La défense anti-aérienne semble étrangement silencieuse!";
                                                    }//DCA silencieuse
                                                }
                                            }
                                            mysqli_free_result($result);
                                        }
                                    }
                                    if($Avion_Nbr >0)
                                    {
                                        $Avions_Bomb=$Avion_Nbr;
                                        $Malus_Reperer_reg=GetMalusReperer($Zone,0);
                                        $Bomb_Form=400;
                                        //Unités sur place
                                        $pj_unit=mysqli_query($con,"SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Placement,r.Position,r.Visible,c.Taille,c.Blindage_f,c.Blindage_l,c.Blindage_t,c.Blindage_a,c.Detection,c.mobile,c.Vitesse,
                                        r.HP,c.Nom,c.ID as Veh,c.HP as HP_max FROM Regiment_IA as r,Cible as c,Pays as p 
                                        WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position=25 AND r.Placement=8 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Bomb_IA=0") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : emia1-asm-reg');
                                        /*(SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Experience,r.Placement,r.Position,r.Visible,c.Taille,c.Blindage_f,c.Blindage_l,c.Blindage_t,c.Blindage_a,c.Detection,c.mobile,c.Vitesse,
                                        r.HP,c.Nom,c.ID as Veh,c.HP as HP_max FROM Regiment as r,Cible as c,Pays as p
                                        WHERE r.Lieu_ID='$Cible' AND r.Pays=p.ID AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Position=25 AND r.Placement=8 AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Bomb_IA=0) UNION (*/
                                        if($pj_unit)
                                        {
                                            while($data=mysqli_fetch_array($pj_unit))
                                            {
                                                if(mt_rand(0,$Vue_ailier)+$Radar_Bonus+$Bonus_asm+$Meteo+$data['Taille']-mt_rand(0,$data['Experience']) >0)
                                                    $Detect_asm=true;
                                                else
                                                    $Detect_asm=false;
                                                if(($data['Visible'] >0 or $Detect_asm) and $data['ID'] >0 and $Avions_Bomb >0)
                                                {
                                                    $rand_tir=11;
                                                    $Bombs_Hit=1;
                                                    $def_c=0;
                                                    if($data['HP'] >0)
                                                        $HP_eni=$data['HP'];
                                                    else
                                                        $HP_eni=$data['HP_max'];
                                                    $Esquive=$data['Blindage_f'];
                                                    $def_c=$data['Blindage_f'];
                                                    if($data['Officier_ID'] >0){
                                                        if(IsSkill(38,$data['Officier_ID']))$Esquive+=25;
                                                    }
                                                    if(!$Bomb_ailier)$Bomb_ailier=25;
                                                    $rand_tir=mt_rand(0,$Bomb_ailier);
                                                    $Shoot=$rand_tir+($Stab/10)+$meteo-$Esquive-($Mission_alt/100)+$Bonus_ailier+$Bonus_steady+$Bonus_asm;
                                                    $msg_hit="Un avion effectue un grenadage, ";
                                                    if($data['Position'] ==8 and $Bonus_repere)$Shoot+=5;
                                                    $debug_intro.="<br>Shoot ASM=".$Shoot." (+rand_tir=".$rand_tir.", -meteo=".$meteo.", -Esquive=".$Esquive.
                                                    ", +Stab/10=".$Stab.", -alt/100=".$Mission_alt."), def_c".$def_c;
                                                    if($Shoot >0 or $rand_tir ==$Bomb_ailier)
                                                    {
                                                        $Degats=1;
                                                        if($Bombs_Hit >0)
                                                            $Degats+=(mt_rand(1,400)*20);
                                                        else
                                                            $msghit.="L'attaque manque de précision!";
                                                        if($Degats <1)$Degats=mt_rand(1,10);
                                                        $HP_eni-=$Degats;
                                                        if($HP_eni <1)
                                                        {
                                                            $Gain_Reput+=2;
                                                            $Tues=1;
                                                            if($data['Officier_ID'] >0)
                                                            {
                                                                $HP_new=$data['HP_max'];
                                                                $Nbr_end=$data['Vehicule_Nbr']-1;
                                                                $reset=mysqli_query($con,"UPDATE Regiment SET Position=8,HP='$HP_new',Vehicule_Nbr='$Nbr_end',Visible=0,Bomb_IA=1 WHERE ID='".$data['ID']."'");;
                                                                AddEventGround(607,$Avion,0,$data['ID'],$Cible,1,$data['Vehicule_ID']);
                                                            }
                                                            else
                                                            {
                                                                if($data['Vehicule_Nbr'] ==1)
                                                                    $query_reset_ia="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Position=8,HP=0,Fret=0,Fret_Qty=0,Visible=1,Bomb_IA=1 WHERE ID='".$data['ID']."'";
                                                                else{
                                                                    $HP_new=$data['HP_max'];
                                                                    $Nbr_end=$data['Vehicule_Nbr']-1;
                                                                    $query_reset_ia="UPDATE Regiment_IA SET Position=8,HP='$HP_new',Vehicule_Nbr='$Nbr_end',Visible=1,Bomb_IA=1,Mission_Lieu_D='$Cible',Mission_Type_D=7,Experience=Experience+1 WHERE ID='".$data['ID']."'";
                                                                }
                                                                $reset=mysqli_query($con,$query_reset_ia);;
                                                                AddEventGround(707,$Avion,0,$data['ID'],$Cible,1,$data['Vehicule_ID']);
                                                            }
                                                            $msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts. ".$Tues." <b>".$data['Nom']."</b> détruit!</p>";
                                                        }
                                                        else
                                                        {
                                                            //Dégats persistants grosses unités navales
                                                            if($data['mobile'] ==5){
                                                                $Gain_Reput+=1;
                                                                if($data['Officier_ID'] >0)
                                                                    $DB='Regiment';
                                                                else
                                                                    $DB='Regiment_IA';
                                                                UpdateData($DB,"HP",-$Degats,"ID",$data['ID']);
                                                                $HP_final=GetData($DB,"ID",$data['ID'],"HP");
                                                                if($HP_final <1){
                                                                    $msghit="<p>".$msg_hit." , occasionnant ".round($Degats)." dégâts, achève le <b>".$data['Nom']."</b>!</p>";
                                                                    AddVictoire_atk("Avion",$data['ID'],$data['Vehicule_ID'],$Avion,0,$Unite,$Cible,$Bomb_Form,$data['Pays'],0,$Mission_alt,$Nuit,$Degats);
                                                                    $reset=mysqli_query($con,"UPDATE $DB SET Position=8,HP='$hp_ori',Vehicule_Nbr=Vehicule_Nbr-1,Bomb_IA=1,Visible=1,Experience=Experience+1 WHERE ID='".$data['ID']."'");;
                                                                }
                                                                else{
                                                                    $msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts et endommageant le <b>".$data['Nom']."</b>!</p>";
                                                                    if($data['Officier_ID'] >0)
                                                                        AddEventGround(609,$Avion,0,$data['ID'],$Cible,$Degats,$data['Vehicule_ID']);
                                                                    else{
                                                                        AddEventGround(709,$Avion,0,$data['ID'],$Cible,$Degats,$data['Vehicule_ID']);
                                                                        $reset=mysqli_query($con,"UPDATE Regiment_IA SET Mission_Type_D=7,Mission_Lieu_D='$Cible',Bomb_IA=1,Visible=1,Experience=Experience+1 WHERE ID='".$data['ID']."'");;
                                                                    }
                                                                }
                                                            }
                                                            else
                                                                $msghit="<p>".$msg_hit." occasionnant ".round($Degats)." dégâts, n'a pas détruit le <b>".$data['Nom']."!</p>";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $Arme_txt='La charge de profondeur';
                                                        if($Shoot <-100)
                                                            $msghit="<p>".$Arme_txt." explose très loin à côté de la cible. Cette attaque est totalement manquée!</p>";
                                                        elseif($Shoot <-50)
                                                            $msghit="<p>".$Arme_txt." explose à côté de la cible. Cette attaque a manqué de précision!</p>";
                                                        else
                                                            $msghit="<p>".$Arme_txt." explose juste à côté de la cible. Quel manque de chance!</p>";
                                                    }
                                                    $mes.='<div class="alert alert-warning">'.$msghit.'</div>';
                                                }
                                                $Avions_Bomb-=1;
                                            }
                                            mysqli_free_result($pj_unit);
                                            UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,129);
                                            if(!$msg_hit)$mes.='<div class="alert alert-danger">Vous ne détectez aucun sous-marin!</div>';
                                        }
                                        else
                                            $mes.='<div class="alert alert-danger">Vous ne détectez aucun sous-marin!</div>';
                                    }
                                    else
                                        $mes.='<div class="alert alert-danger"><b>Tous les avions d\'attaque ont été abattus ou refoulés! La mission est un échec!</b></div>';
									AddAtk_IA($Cible,$Unite,$Avion_Nbr,$Avion,$Bomb_Form,$Mission_alt,0,$Cycle,$DCA_Nbr,$Escorte_Nbr,$Patrol_Nbr);
								}
							}
							elseif($Type ==8 or $Type ==16 or $Type ==23 or $Type ==24 or $Type ==27) //Bomb Strat, Parachutage
							{
								if(!$Officier_Adjoint and $OfficierEMID ==$Commandant)$EM_CT=1;
								elseif(!$Officier_Adjoint and !$Commandant)$EM_CT=1;
								if($Bomb_Form >999)
									$CT=GetModCT(4,$country,$EM_CT);
								elseif($Bomb_Form >499)
									$CT=GetModCT(2,$country,$EM_CT);
								else
									$CT=1;
								if($Credits >=$CT)
								{
                                    //DCA
									$Dca_on=false;
									if($Cible_Atk ==1)
									{
										$Alt_Flak_min=$alt-1000;
										$Alt_Flak_max=$alt+1000;
										$Projo=0;
										$result=mysqli_query($con,"SELECT COUNT(*),DCA_ID FROM Flak WHERE Lieu='$Cible' AND (Alt BETWEEN '$Alt_Flak_min' AND '$Alt_Flak_max')");
										if($result){
											while($data=mysqli_fetch_array($result))
											{
												if($data[1] ==63 and $alt <=3000)
													$Projo+=10;
												elseif($data[1] ==64 and $alt <=6000)
													$Projo+=10;
												elseif($data[1] ==65 and $alt <=10000)
													$Projo+=10;
												$OK_DCA=$data[0];
											}
											mysqli_free_result($result);
										}
										if($OK_DCA){
											$result=mysqli_query($con,"SELECT DCA_ID,DCA_Exp,Unit,DCA_Nbr FROM Flak WHERE Lieu='$Cible' AND DCA_Nbr >0 AND (Alt BETWEEN '$Alt_Flak_min' AND '$Alt_Flak_max') ORDER BY DCA_Exp DESC LIMIT 1") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : bomb-ok_dca');
											if($result){
												while($data=mysqli_fetch_array($result))
												{
													$Arme1=$data['DCA_ID'];
													if($data['DCA_Exp'] >4)
														$Arme2=$data['DCA_ID'];
													if($data['DCA_Exp'] >6)
														$Arme3=$data['DCA_ID'];
													$DCA_Unit=$data['Unit'];
													$Dca_max=$data['DCA_Exp']*25;
													$DefenseAA=$data['DCA_Nbr'];
												}
												mysqli_free_result($result);
											}
										}
										
									}
									if($OK_DCA and $DefenseAA)
										$Dca_on=true;
									elseif($DefenseAA >0)
									{
										$DCA_guns=GetDCA($Pays_eni,$DefenseAA);
										$hgun=$DCA_guns[0];
										$gun=$DCA_guns[1];
										$mg=$DCA_guns[2];
										if($Mission_alt >=7000){
											$Arme1=$hgun;
											$Arme2=5;
											$Arme3=5;
											$Flak=15;
										}
										elseif($Mission_alt <2000){
											$Arme1=$gun;
											$Arme2=$gun;
											$Arme3=$mg;
											$Flak=3;
										}
										else{
											$Arme1=$hgun;
											$Arme2=$gun;
											$Arme3=5;
											$Flak=14;
										}
										$Dca_max=10+($DefenseAA*10);
										$Dca_on=true;
									}
									if($Dca_on)
									{
										if($Arme1 or $Arme2 or $Arme3)
										{
											$dca_site_hit=false;
											if(!$Blindage)$Blindage=GetData("Unit","ID",$Unite,"U_Blindage");
											$VisAvion=$VisAvion/($Mission_alt/1000);
											for($dca_shoot=1;$dca_shoot<4;$dca_shoot++)
											{
												$Arme_dca="Arme".$dca_shoot;
												if($$Arme_dca !=5)
												{
													//DCA sur Formation
													if($Avion_Nbr >0)
													{
														$Formation_abattue=0;
														$DCA_Shoots=min($DefenseAA,$Avion_Nbr);
														$result_dca=mysqli_query($con,"SELECT Degats,Multi FROM Armes WHERE ID='".$$Arme_dca."'");
														if($result_dca){
															while($data=mysqli_fetch_array($result_dca,MYSQLI_ASSOC))
															{
																$DCA_dg=$data['Degats'];
																$dca_mult=$data['Multi'];
															}
															mysqli_free_result($result_dca);
														}
														$resultp=mysqli_query($con,"SELECT ID,Nom,Pilotage,Tactique,Skill FROM Pilote_IA WHERE Unit='$Unite' AND Actif=1 AND Moral>0 AND Courage>0 ORDER BY RAND() LIMIT ".$DCA_Shoots.""); //AND p.Cible='$Cible' AND p.Mission='$Cible'
														if($resultp){
															while($dataa=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
															{
																$Pilote_ia_dca=$dataa['ID'];
																$Nom_pilote_ia=$dataa['Nom'];
																$Tactique_dca=$dataa['Tactique']+$xp_avion;
																$Pilotage_dca=$dataa['Pilotage']+$xp_avion;
																$Shoot_Dca=0;
																$Shoot_Dca=mt_rand(0,$Dca_max);
																$Shoot=$Shoot_Dca+$meteo+$VisAvion-($Mission_alt/100)-($Tactique_dca/10)-($Pilotage_dca/10)-($VitAvion_lead/10)+$Projo+$Bonus_Passe;
																$Bonus_Passe+=5;
																if($dataa['Skill']==41)$Shoot-=10;
																if($Shoot >1){
																	$dca_site_hit=true;
																	$Degats=round((mt_rand(1,$DCA_dg)-pow($Blindage,2))*GetShoot($Shoot,$dca_mult));
																	//AddEvent("Avion",179,$Avion_dca,$Pilote_ia_dca,$Unite,$Cible,2,$Pays_eni);
																	if($Degats >$HP_avion){
																		$intro.="<br>L'explosion met le feu à l'avion de <b>".$Nom_pilote_ia."</b>, ne lui laissant pas d'autre choix que de sauter en parachute!";
																		$reset=mysqli_query($con,"UPDATE Pilote_IA SET Cible=0,Escorte=0,Couverture=0,Avion=0,Alt=0,Moral=0,Courage=0,Task=0,Mission=0 WHERE ID='$Pilote_ia_dca'");
																		if($Mission_Flight ==3)
																			$Avion3_Nbr_dca+=1;
																		elseif($Mission_Flight ==2)
																			$Avion2_Nbr_dca+=1;
																		else
																			$Avion1_Nbr_dca+=1;
																		$Avion_Nbr-=1;
																		$Formation_abattue+=1;
																		$Avions_Down_DCA[]=$Avion;
																		WoundPilotIA($Pilote_ia_dca);
																	}
																	elseif($Degats >100 and ($Type ==8 or $Type ==16)){
																		$intro.="<br>L'explosion endommage l'avion de ".$Nom_pilote_ia.", il doit faire demi-tour et renoncer à sa mission!";
																		$Avion_Nbr-=1;
																		//(".$Degats_theo."/".$HP_avion." || Shoot=".$Shoot." => rand ".$Shoot_Dca." + vis ".$VisAvion." - vit ".$VitAvion_lead.")
																	}
																	else
																		$intro.="<br>L'explosion endommage légèrement l'avion de ".$Nom_pilote_ia.", il peut heureusement continuer sa mission!";
																}
																else
																	$intro.="<br>La dca encadre l'avion de ".$Nom_pilote_ia." sans le toucher, il peut continuer sa mission!";
															}
															mysqli_free_result($resultp);
														}
														if($Formation_abattue >0){
															$reset=mysqli_query($con,"UPDATE Unit SET Avion1_Nbr=Avion1_Nbr-'$Avion1_Nbr_dca',Avion2_Nbr=Avion2_Nbr-'$Avion2_Nbr_dca',Avion3_Nbr=Avion3_Nbr-'$Avion3_Nbr_dca' WHERE ID='$Unite'");
														}
													}
												}
											}
											if($dca_site_hit)
											{
												if($OK_DCA)
													AddEventFeed(78,$Avion,0,$Unite,$Cible,4,$Pays_eni); //1=DCA Aérodrome PJ
												else
													AddEventFeed(78,$Avion,0,$Unite,$Cible,3,$Pays_eni);
											}
										}
										else
											$intro.="<br>La dca reste silencieuse!";
									}
									//Ravitaillement
									if($Type ==23 and $Avion_Nbr >0)
									{
										$mes.='<div class="alert alert-warning"><b>'.$Avion_Nbr.'</b> avions participent au ravitaillement.</div>';
										$reset=mysqli_query($con,"UPDATE Regiment_IA SET Ravit=1,Mission_Lieu_D=0,Mission_Type_D=0 WHERE Pays='$country' AND Lieu_ID='$Cible' AND Vehicule_Nbr >0 AND Ravit=0 AND Placement NOT IN (6,8,9) ORDER BY RAND() LIMIT ".$Avion_Nbr."");
										$reset_esc=mysqli_affected_rows($con);
										if($reset_esc >0)
											UpdateData("Unit","Reputation",$reset_esc,"ID",$Unite,0,115);
										$msghit.="<br><b>".$reset_esc." unités ont été ravitaillées!</b>";
									}
									elseif($Type ==24 and $Avion_Nbr >0) //Parachutage
									{
										$mes.='<div class="alert alert-warning"><b>'.$Avion_Nbr.'</b> avions larguent les parachutistes.</div>';
										$reset=mysqli_query($con,"UPDATE Regiment_IA SET Lieu_ID='$Cible',Position=4,Placement=0,Camouflage=1,Visible=0,Move=1,Mission_Lieu_D=0,Mission_Type_D=0,Bomb_IA=0 WHERE ID='$Paras'");
										$reset_esc=mysqli_affected_rows($con);
										if($reset_esc){
											$Heure=date('H');
											AddEventFeed(119,$Unite,$OfficierID,$Paras,$Cible,1,$Heure);
											UpdateData("Unit","Reputation",10,"ID",$Unite,0,116);
											$msghit.="<br>Les parachutistes de la ".$Paras."e Compagnie arrivent à destination!";
										}
									}
									elseif($Type ==27 and $Avion_Nbr >0) //Parachutage Cdo
									{
										$mes.='<div class="alert alert-warning"><b>'.$Avion_Nbr.'</b> avions larguent les commandos.</div>';
										$Polices=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Type=99"),0);
										$Polices+=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sections as s,Officier as o,Regiment as r,Pays as p WHERE s.SectionID=5 AND s.OfficierID=o.ID AND r.Officier_ID=s.OfficierID AND r.Pays=p.ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND o.Actif=0"),0);
										$reset=mysqli_query($con,"UPDATE Regiment_IA SET Lieu_ID='$Cible',Position=4,Placement=0,Camouflage=2,Visible=0,Move=1,Mission_Lieu_D=0,Mission_Type_D=0,Bomb_IA=0 WHERE ID='$Paras'");
										$reset_esc=mysqli_affected_rows($con);
										if($reset_esc){
											if($Polices or $ValStrat >6){
												$Heure=date('H');
												AddEventFeed(119,$Unite,$OfficierID,$Paras,$Cible,2,$Heure);
											}
											UpdateData("Unit","Reputation",20,"ID",$Unite,0,116);
											$msghit.="<br>Les commandos de la ".$Paras."e Compagnie arrivent à destination!";
										}
									}								
									elseif($Avion_Nbr >0) //Bombardement strat
									{
										$mes.='<div class="alert alert-warning"><b>'.$Avion_Nbr.'</b> avions participent au bombardement.</div>';
										if($Cible_Atk ==1)
										{
											$nom_c="la piste";
											$def_c=20;
											//$arme_c=17;
											$hp_c=10000;
											$rep_c=15;
											$type_c=30;
											$piste=true;
											$tank=66;
										}
										elseif($Cible_Atk ==2)
										{
											$nom_c="l'usine";
											$def_c=50;
											$arme_c=GetDCA($Pays_eni,$DefenseAA,2);
											$hp_c=5000+(5000*$ValStrat);
											$rep_c=20;
											$cam_c=0;
											$type_c=27;
											$usine=true;
											$tank=5;
										}
										elseif($Cible_Atk ==3)
										{
											$nom_c="les avions";
											$def_c=5;
											$hp_c=1000;
											$rep_c=10;
											$avion_parque=true;
											$type_c=90;
										}
										elseif($Cible_Atk == 4)
										{
											$nom_c="la gare";
											$def_c=20;
											$arme_c=GetDCA($Pays_eni,$DefenseAA,2);
											$hp_c=2000+(2000*$ValStrat);
											$rep_c=10;
											$cam_c=0;
											$type_c=28;
											$gare=true;
											$tank=9;
										}
										elseif($Cible_Atk ==5)
										{
											$nom_c="le pont";
											$def_c=50;
											$arme_c=GetDCA($Pays_eni,$DefenseAA,2);
											$hp_c=2500+(2500*$ValStrat);
											$rep_c=20;
											$cam_c=0;
											$type_c=29;
											$pont=true;
											$tank=10;
										}	
										elseif($Cible_Atk ==6)
										{
											$nom_c="les infrastuctures portuaires";
											$def_c=50;
											$arme_c=GetDCA($Pays_eni,$DefenseAA,2);
											$hp_c=5000+(5000*$ValStrat);
											$rep_c=30;
											$cam_c=0;
											$port=true;
											$tank=12;
										}
										elseif($Cible_Atk ==7)
										{
											$nom_c="la station radar";
											$def_c=50;
											$arme_c=GetDCA($Pays_eni,$DefenseAA,2);
											$hp_c=2500+(2500*$ValStrat);
											$rep_c=20;
											$cam_c=20;
											$radar=true;
											$tank=14;
										}
										elseif($Cible_Atk ==8)
										{
											$nom_c="le dépôt";
											$def_c=20;
											$arme_c=GetDCA($Pays_eni,$DefenseAA,2);
											$hp_c=1000+(1000*$ValStrat);
											$rep_c=10;
											$cam_c=0;
											$type_c=24;
											$depot=true;
											$tank=3;
										}
										else
										{
											$nom_c="la caserne";
											if($Fortification >10)
												$def_c=$Fortification;
											else
												$def_c=10;
											$arme_c=GetDCA($Pays_eni,$DefenseAA,2);
											$hp_c=2500+(2500*$ValStrat);
											$rep_c=5;
											$cam_c=0;
											$type_c=34;
											$caserne=true;
											$tank=7;
										}
										include_once('./jfv_avions.inc.php');
										$Array_Mod=GetAmeliorations($Avion);
										if($Array_Mod[32] and $Bomb_Form ==1000)
											$Bombs=$Array_Mod[32];
										elseif($Array_Mod[15] and $Bomb_Form ==500)
											$Bombs=$Array_Mod[15];
										elseif($Array_Mod[14] and $Bomb_Form ==250)
											$Bombs=$Array_Mod[14];
										elseif($Array_Mod[13] and $Bomb_Form ==125)
											$Bombs=$Array_Mod[13];
										else
											$Bombs=1;
										if(!$Bomb_ailier)$Bomb_ailier=25;
										$rand_tir=mt_rand(0,$Bomb_ailier);
										$Shoot=$rand_tir+($Stab/5)+$meteo-($Mission_alt/100)+$Bonus_ailier+$Bonus_steady+$Bonus_bomb+$Bonus_nuit;
										if($Shoot >0 and $rand_tir >0)
										{
											$Bombs_Hit=GetShoot($Shoot,$Bombs);
											if($Avion_Nbr >1){
												$msg_formation='les bombes de la formation';
												if($Shoot >100){
													$mult_deg=101-$def_c;
													$Bombs_Hit_Formation=$Bombs_Hit*$Avion_Nbr;
												}
												elseif($Shoot >50){
													$mult_deg=101-$def_c;
													$Bombs_Hit_Formation=$Bombs_Hit*mt_rand(1,$Avion_Nbr);
												}
												elseif($Shoot >25){
													$mult_deg=61-$def_c;
													$Bombs_Hit_Formation=$Bombs_Hit*mt_rand(1,$Avion_Nbr);
												}
												elseif($Shoot >10){
													$mult_deg=31-$def_c;
													$Bombs_Hit_Formation=$Bombs_Hit*mt_rand(1,$Avion_Nbr/2);
												}
												else{
													$mult_deg=21-$def_c;
													$Bombs_Hit_Formation=$Bombs_Hit;
												}
											}
											else
												$msg_formation='les bombes';
											if($Admin ==1)
												$debug_strat.="<br> Shoot=".$Shoot." (Bombs_Hit_Formation=".$Bombs_Hit_Formation." / Bombs_Hit=".$Bombs_Hit." / Avion_Nbr=".$Avion_Nbr." / Bombs=".$Bombs;
											if($Bombs_Hit >=$Bombs)
												$msg_hit="Toutes ".$msg_formation." explosent sur la cible !";
											elseif($Bombs_Hit)
												$msg_hit="Il semblerait que ".$msg_formation." explosent sur la cible, mais certaines en sont bien loin!";
											else
												$msg_hit="Hélas, ".$msg_formation." explosent carrément à côté de la cible !";
											if($mult_deg <1)$mult_deg=1;
											$Degats=1;
											if($Bombs_Hit >0){
												if(!$Bombs_Hit_Formation)$Bombs_Hit_Formation=$Bombs_Hit;							
												if($Bomb_Form ==800)
													$res_d=$def_c;
												else
													$res_d=$def_c*4;
												for($i=1;$i<=$Bombs_Hit_Formation;$i++)
												{
													if($Bomb_Form >$res_d)
														$Degats+=(mt_rand(1,$Bomb_Form)*$mult_deg);
													else
														$Degats+=1;
													$debug_strat.="<br> Dégâts=".$Degats." (Bombe ".$Bomb_Form." * Mult_deg ".$mult_deg.") / Def=".$res_d;
												}
											}
											else
												$msghit.='<br>Le bombardement manque de précision!';
											if($Degats <1)$Degats=mt_rand(1,10);					
											$hp_c-=$Degats;
											if($hp_c <1)
											{
												$msghit='<p>'.$msg_hit.' , occasionnant '.round($Degats).' dégâts. Votre objectif est détruit!</p>';
												$Deg_bonus=$Degats/10000;
												if($Deg_bonus >50)$Deg_bonus=50;
												if($gare){
													$Damage=floor(0-($rep_c/5)-$Deg_bonus);
													UpdateData("Lieu","NoeudF",$Damage,"ID",$Cible);
													$Gain_Reput=5+($ValStrat*3);
													UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,110);
													$msghit.="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
												}
												elseif($usine){
													$Gain_Reput=15+($ValStrat*10);
													UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite);
													$Damage=floor(0-($rep_c/5)-$Deg_bonus);
													UpdateData("Lieu","Industrie",$Damage,"ID",$Cible);
													$msghit.="<br>Votre attaque diminue le potentiel de production de l'ennemi!";
												}
												elseif($caserne){
													$Gain_Reput=1+($ValStrat*5);
													UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,110);
													UpdateData("Lieu","Fortification",-10,"ID",$Cible);
													$msghit.="<br>Votre attaque diminue le moral des troupes de l'ennemi!";
												}
												elseif($pont){
													$Damage=floor(0-($rep_c/5)-$Deg_bonus);
													if($Damage >99){
														$Gain_Reput=13+($ValStrat*8);
														$msghit.="<br>Le pont est totalement détruit!";
													}
													else{
														$Gain_Reput=10+($ValStrat*5);
														$msghit.="<br>Le pont est endommagé!";
													}
													UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,110);
													SetData("Lieu","Pont",$Damage,"ID",$Cible);
												}
												elseif($port){
													$Gain_Reput=15+($ValStrat*10);
													UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,110);
													$Damage=floor(0-($rep_c/5)-$Deg_bonus);
													UpdateData("Lieu","Port",$Damage,"ID",$Cible);
													$msghit.="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
													if($ValStrat >3){
														$stock_rand=mt_rand(1,3);
														$stock_qty=-$Degats;
														switch($stock_rand)
														{
															case 1:
																$stock="Stock_Essence_1";
															break;
															case 2:
																$stock="Stock_Essence_87";
															break;
															case 3:
																$stock="Stock_Essence_100";
															break;
														}
														UpdateData("Lieu",$stock,$stock_qty,"ID",$Cible);
													}
												}
												elseif($radar){
													$Gain_Reput=15+($ValStrat*10);
													UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,110);
													$Damage=floor(0-($rep_c/5)-$Deg_bonus);
													UpdateData("Lieu","Radar",$Damage,"ID",$Cible);
													$msghit.="<br>Votre attaque diminue le potentiel de détection de l'ennemi!";
												}
												elseif($depot){
													$stock_rand=mt_rand(1,15);
													$stock_qty=-$Degats;
													switch($stock_rand)
													{
														case 1:
															$stock="Stock_Munitions_8";
														break;
														case 2:
															$stock="Stock_Munitions_13";
														break;
														case 3:
															$stock="Stock_Munitions_20";
														break;
														case 4:
															$stock="Stock_Munitions_30";
														break;
														case 5:
															$stock="Stock_Munitions_40";
														break;
														case 6:
															$stock="Stock_Munitions_50";
														break;
														case 7:
															$stock="Stock_Munitions_60";
														break;
														case 8:
															$stock="Stock_Munitions_75";
														break;
														case 9:
															$stock="Stock_Munitions_90";
														break;
														case 10:
															$stock="Stock_Munitions_105";
														break;
														case 11:
															$stock="Stock_Munitions_125";
														break;
														case 12:
															$stock="Stock_Munitions_150";
														break;
														case 13:
															$stock="Stock_Essence_1";
														break;
														case 14:
															$stock="Stock_Essence_87";
														break;
														case 15:
															$stock="Stock_Essence_100";
														break;
													}
													$Gain_Reput=5+($ValStrat*5);
													UpdateData("Unit","Reputation",$Gain_Reput,"ID",$Unite,0,110);
													AddEvent("Avion",117,$Avion,0,$Unite,$Cible,$stock_rand,$stock_qty);
													UpdateData("Lieu",$stock,$stock_qty,"ID",$Cible);
													if($Admin)$stock_detail_txt=" (".substr($stock,strrpos($stock,"_")).")";
													if($Premium)$stock_dg_txt="<br>La quantité détruite estimée est de ".mt_rand($Degats-100,$Degats+100);
													$msghit="<br>Le bombardement détruit un entrepôt, réduisant les stocks".$stock_detail_txt." de l'ennemi!".$stock_dg_txt;
												}
												elseif($piste){
													if($Deg_bonus >10)$Deg_bonus=10;
													$result=mysqli_query($con,"SELECT ID FROM Unit WHERE Base='$Cible' AND Etat=1");
													if($result){
														while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
														{
															$Unite_loss=$data['ID'];
															AddEvent("Avion",28,$Avion,0,$Unite_loss,$Cible);
														}
														mysqli_free_result($result);
													}
													$Damage=floor(0-(mt_rand(0,$Bomb_Form)/10)-$Deg_bonus);
													UpdateData("Lieu","QualitePiste",$Damage,"ID",$Cible);
													UpdateData("Unit","Reputation",10,"ID",$Unite,0,110);
												}
												elseif($avion_parque){
													$Avion_nbr_det=$Degats/2000;
													if($Avion_nbr_det >$Bombs_Hit)$Avion_nbr_det=$Bombs_Hit;
                                                    $flights=array(1,2,3);
                                                    $result=mysqli_query($con,"SELECT ID,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Mission_Flight,Mission_Lieu_D
                                                    FROM Unit WHERE Base='$Cible' AND Etat=1 AND (Avion1_Nbr + Avion2_Nbr + Avion3_Nbr) >0 ORDER BY RAND() LIMIT 1");
													if($result){
														while($data=mysqli_fetch_array($result))
														{
                                                            $Unit_det=$data['ID'];
                                                            $Avionp_1=$data['Avion1'];
                                                            $Avionp_2=$data['Avion2'];
                                                            $Avionp_3=$data['Avion3'];
                                                            $Avionn_1=$data['Avion1_Nbr'];
                                                            $Avionn_2=$data['Avion2_Nbr'];
                                                            $Avionn_3=$data['Avion3_Nbr'];
														    if($data['Mission_Lieu_D'])
                                                                $Flight_det=array_rand(array_diff($flights,[$data['Mission_Flight']]));
														    else
                                                                $Flight_det=mt_rand(1,3);
                                                            $Avion_det='Avionp_'.$Flight_det;
                                                            $Avion_det_qty='Avionn_'.$Flight_det;
                                                            if($$Avion_det_qty > 0 and $Avion_nbr_det > $$Avion_det_qty)
                                                                $Avion_nbr_det = $$Avion_det_qty;
														}
														mysqli_free_result($result);
													}
													if($Unit_det and $Flight_det and $Avion_nbr_det){
														UpdateData("Unit","Avion".$Flight_det."_Nbr",-$Avion_nbr_det,"ID",$Unit_det);
														//AddEvent("Avion",142,$Avion_det,$PlayerID,$Unit_det,$Cible,$Nbr_det);
														$tank=10000+$$Avion_det;
														$msghit.="<br>Le bombardement détruit ".$Avion_nbr_det." ".GetAvionIcon($Avion_det)." au sol.";
													}
												}
												if($Nuit)
													$MLD=17;
												else
													$MLD=7;
												$resetlieu=mysqli_query($con,"UPDATE Lieu SET Mission_Lieu_D='$Cible',Mission_Type_D='$MLD' WHERE ID='$Cible'");;
												AddVictoire_Bomb("Avion",$type_c,$tank,$Avion,0,$Unite,$Cible,$Bomb_Form,$Nuit,$Pays_eni,$Mission_alt);
											}
											else{
												if($Premium)
													$msghit.="<br>Le bombardement manque de puissance (Dégâts=".$Degats."), la cible n'est pas détruite!";
												else
													$msghit.="<br>Le bombardement manque de puissance, la cible n'est pas détruite!";
											}
										}
										else{
											if($Premium)
												$msghit.="<br>Le bombardement manque de précision (Tir=".$Shoot."), les bombes tombent à côté de la cible!";
											else
												$msghit.="<br>Le bombardement manque de précision, les bombes tombent à côté de la cible!";
										}
									}
									else
										$msghit.="<br><b>Tous les avions ont été abattus par la DCA ou refoulés! La mission est un échec total!</b>";
									$mes.='<div class="alert alert-warning">'.$msghit.'</div>';
									AddAtk_IA($Cible,$Unite,$Avion_Nbr,$Avion,$Bomb_Form,$Mission_alt,$tank,$Nuit,$DCA_Shoots,$Escorte_Nbr,$Patrol_Nbr);
									if($Type ==8 or $Type ==16)AddEventFeed(205,$Avion,$OfficierEMID,$Unite,$Cible,$Nuit,$tank);
								}
							}
							$reset=mysqli_query($con,"UPDATE Unit SET Reputation=Reputation+1,Mission_Lieu='$Cible',Mission_Type='$Type',Mission_alt='$Mission_alt',Mission_Flight='$Mission_Flight',Mission_IA=1,Date_Mission=NOW() WHERE ID='$Unite'");
							mysqli_close($con);
						}
						elseif($Enis >0){
							$mes.="<div class='alert alert-danger'>Le ".$Corps." vous informe que votre ordre de mission n'a pas été validé, aucun pilote n'a pu accomplir sa mission!</div>";
							SetData("Unit","Mission_IA",1,"ID",$Unite);
						}
						else{
							$Sqn=GetSqn($country);
							$mes.="<div class='alert alert-danger'>Le ".$Corps." vous informe que votre ordre de mission n'a pas été validé, l'escadrille ne possède pas suffisamment de pilotes aptes à voler.<br>Veillez à sélectionner un ".$Sqn." possédant suffisamment d'avions, le ".$Sqn." actuel (".$Mission_Flight.") en possède ".$Avion_Nbr." sur ".$Avion_Nbr_max." alors que vous avez sélectionné ".$Pilotes." pilotes, dont ".$pilotes_ia." ont accompli leur mission.</div>";
						}
						if($mes)mail('binote@hotmail.com','Aube des Aigles: Mission Air IA '.$Type.' / Nation '.$country,"<html>Officier EM = ".$OfficierEMID." lance une mission ".GetMissionType($Type)." avec l'unité ".$Nom_Unite." sur ".$Nom_Lieu." à l'altitude ".$Mission_alt."m<br>".$intro.$debug_intro.$mes.$msghit.$skills.$debug_strat."</html>","Content-type: text/html; charset=utf-8");
					}
					if($OfficierEMID >0)
					{
					    //Coût en CT
						if($Trait ==13 and ($Type ==7 or $Type==17))$CT=0;
						elseif($Trait ==2 and $Type ==23)$CT=0;
						elseif($Trait ==16 and $Type ==4)$CT=0;
						elseif($Trait ==3 and ($Type ==5 or $Type==2 or $Type==12))$CT-=1;
						elseif($Trait ==4 and ($Type ==15 or $Type==8 or $Type==16))$CT-=1;
						if($CT<1)$CT=1;
						if($CT){
							UpdateCarac($OfficierEMID,"Avancement",$CT,"Officier_em");
							UpdateData("Officier_em","Credits",-$CT,"ID",$OfficierEMID);
						}
					}
					if(is_array($Avions_Down_DCA))
					{
						$Tableau_Compteur_DCA=array_count_values($Avions_Down_DCA);
						$Avions_Down_DCA_u=array_unique($Avions_Down_DCA);
						foreach($Avions_Down_DCA_u as $Avion_DCA_D)
						{
							AddEvent("Avion",231,$Avion_DCA_D,$OfficierEMID,$Unite,$Cible,$Tableau_Compteur_DCA[$Avion_DCA_D],$country);
							$Avions_txt.=$Tableau_Compteur_DCA[$Avion_DCA_D]." ".GetAvionIcon($Avion_DCA_D,0,0,0,$Front)."<br>";
						}
						unset($Avions_Down_DCA);
						unset($Avions_Down_DCA_u);
						unset($Tableau_Compteur_DCA);
					}
					if(is_array($Avions_Down))
					{
						$Tableau_Compteur=array_count_values($Avions_Down);
						$Avions_Down_u=array_unique($Avions_Down);
						foreach($Avions_Down_u as $Avion_D)
						{
							AddEvent("Avion",221,$Avion_D,$OfficierEMID,$Unite,$Cible,$Tableau_Compteur[$Avion_D],$country);
							$Avions_txt.=$Tableau_Compteur[$Avion_D]." ".GetAvionIcon($Avion_D,0,0,0,$Front)."<br>";
						}
						unset($Avions_Down);
						unset($Avions_Down_u);
						unset($Tableau_Compteur);
						$down_txt.="<h2>Résumé des pertes</h2>".$Avions_txt;
					}
				}//Mission_IA
                $menu.="<div class='row'><div class='col-md-6'>".$down_txt."</div><div class='col-md-6'>".$skills_m."</div></div>";
                if($Armee)
                    $menu.="<br><a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour</a>";
                else
                    $menu.="<br><a href='index.php?view=em_missions' class='btn btn-default' title='Retour'>Retour</a>";
			}
		}
        if($Admin){
            $menu.=" <a href='index.php?view=rapports' class='btn btn-default' title='Retour'>Retour menu</a>";
            $skills.=$debug_strat;
        }
		$titre='Ordre de mission';
		$img=Afficher_Image('images/avions/mission'.$Avion.'.jpg','images/avions/vol'.$Avion.'.jpg')."<h2>".$Nom_Unite."</h2>";
	}
	else
		echo "<img src='images/top_secret.gif'>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');