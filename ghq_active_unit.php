<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_inc_em.php');
	if($GHQ or $Admin)
	{
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_ground.inc.php');		
		echo "<h1>Activation d'unité</h1>";
		if($Credits >=25 or $Admin)
		{
			$Escadrille=Insec($_POST['Unit']);
			$Typ=Insec($_POST['Type']);
			if($Escadrille and $Typ)
			{				
				$con=dbconnecti();
				$resulta=mysqli_query($con,"SELECT ID,Usine1 FROM Avion WHERE Type='$Typ' AND Pays='$country' AND Premium=0 ORDER BY Rating ASC LIMIT 1");
				$Nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Unit WHERE ID='$Escadrille'"),0);
				$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Escadrille' AND Actif=1"),0);
				if($resulta)
				{
					while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
					{
						$Avion=$data['ID'];
						$Retraite=$data['Usine1'];
					}
					mysqli_free_result($resulta);
				}
				if(!$Retraite)$Retraite=Get_Retraite($Front,$country,40);
				if($Avion >0 and $Retraite)
				{
					$reset_q=mysqli_query($con,"UPDATE Unit SET Base='$Retraite',Commandant=NULL,Officier_Technique=NULL,Officier_Adjoint=NULL,Porte_avions=0,Etat=1,Avion1='$Avion',Avion2='$Avion',Avion3='$Avion',Avion1_Nbr=0,Avion2_Nbr=0,Avion3_Nbr=0,
					Stock_Essence_87=0,Stock_Essence_100=0,Stock_Essence_130=0,Stock_Essence_1=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,
					Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Bombes_30=0,Bombes_50=0,Bombes_80=0,Bombes_125=0,Bombes_250=0,Bombes_300=0,Bombes_400=0,Bombes_500=0,Bombes_800=0,Bombes_1000=0,Bombes_2000=0,
					Mission_Lieu=0,Mission_Type=0,Briefing=NULL,Mission_Lieu_D=0,Mission_Type_D=0,Avion1_Bombe=0,Avion2_Bombe=0,Avion3_Bombe=0,Avion1_Bombe_Nbr=0,Avion2_Bombe_Nbr=0,Avion3_Bombe_Nbr=0,Avion1_BombeT=0,Avion2_BombeT=0,Avion3_BombeT=0,
					Avion1_Mun1=0,Avion2_Mun1=0,Avion3_Mun1=0,Mission_IA=0
					WHERE ID='$Escadrille'");
					if(!$Pilotes or $Pilotes <11)
					{
						$Pilotes_Max=12-$Pilotes;
						$today=date('Y-m-d');	
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
                        elseif($country ==10)
                        {
                            $prenoms=array("Georgios","Ajax","Enée","Hémon","Aléxios","Alexandros","Ambrosios","Anastasios","Antimachos","Antipatros","Apollon","Achilléas","Basileios","Galaktion","Dédalos","David","Démétrios","Dimosthénis","Eleuthérios","Érasmos","Ermès","Euthymios","Eustratios","Zéphyros","Elias","Iraklis","Erodotos","Theodoros","Theophilos","Ioulianos","Ioannis","Klaudios","Kyriakos","Konstantinos","Léandros","Leonidas","Menas","Minos","Nikodemos","Nikolaos","Perseus","Polychronios","Sergios","Soterios","Timotheos","Philippos","Charalampos","Christodoulos","Christophoros","Okeanos");
                            $noms=array("Salakis","Salamis","Sokratis","Sokratos","Antonakis","Papadakis","Rastopopoulos","Kostas","Papadopoulos","Nikos","Vasilis","Dimopoulos","Ioannis","Petridis","Delenikas","Lazaridis","Zacharias","Haros","Dedes");
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
                        elseif($country ==15)
                        {
                            $prenoms=array("Dimitar","Hristo","Georgi","Stoyan","Todor","Nikolay","Lyubomir","Petar","Ivan","Kostadin","Ivan","Aleksandar","Andrei","Boris","Evgeni","Gavrail","Ioan","Krasimir","Lubomir","Milen","Mihail","Ognyan","Pavel","Petar","Radko","Sergei","Stanko","Vasil","Viktor");
                            $noms=array("Draganov","Mihailov","Kostadinov","Ivanov","Petrov","Stoyanov","Petkov","Bogdanov","Nikolov","Georgiev","Dimitrov","Hristov","Iliev","Todorov");
                        }
                        elseif($country ==17)
                        {
                            $prenoms=array("Andreja","Anto","Bogdan","Branko","Cedomir","Danijel","Petar","Ivan","Aleksandar","Andrei","Boris","Sergei","Stanko","Vasilije","Viktor","Dejan","Davor","Dragan","Drago","Emil","Goran","Igor","Ilija","Ivica","Janko","Jovan","Ljubomir","Luka","Marinko","Marko","Milos","Miodrag","Milovan","Miroslav","Mladen","Nenad","Novak","Radomir","Radoslav","Ratko","Slobodan","Stanislav","Stjepan","Tomislav","Vedran","Vladimir","Zivko","Zlatan","Zoran");
                            $noms=array("Antic","Babic","Bogdanovic","Brankovic","Davidovic","Dragic","Dragovic","Filipovic","Gavrilovic","Ilic","Ivanovic","Jankovic","Jovanovic","Kovac","Lukic","Markovic","Mihailovic","Milic","Milojevic","Milosevic","Milovanovic","Nikolic","Novak","Pavlovic","Petrovic","Radic","Stankic","Stankovic","Stefanovic","Stojanovic","Vlahovic","Vukovic","Zivkovic","Zoric","Broz","Franic","Horvat","Juric","Kolar","Marusic","Matic","Pavletic","Petric","Tomcic","Vlasic","Vukoja","Zupan","Boban");
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
						if($Unit_Reputation >250000)
						{
							$Grade=25000;
							$Pilotage=175;
							$Skill=150;
						}
						elseif($Unit_Reputation >100000)
						{
							$Grade=10000;
							$Pilotage=150;
							$Skill=125;
						}
						elseif($Unit_Reputation >50000)
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
						if($Typ ==AVION_CHASSE or $Typ ==AVION_CHASSE_LD or $Typ ==AVION_CHASSE_EMB){
                            $Skills_1=array(1,2,10,18,22,26);
                            $Skills_2=array(3,11,19,23,27,34,39,42);
                            $Skills_3=array(4,12,20,24,28,30,45);
                            $Skills_4=array(5,13,21,25,29,31,36,38,44);
                        }elseif($Typ == AVION_RECO){
                            $Skills_1=array(1,2,6,10,14,18,22);
                            $Skills_2=array(3,7,11,15,19,23,34,39);
                            $Skills_3=array(4,8,12,16,20,24,30,32,35,37);
                            $Skills_4=array(5,9,13,17,21,25,31,36,38,41);
                        }elseif($Typ == AVION_BOMBARDIER or $Typ == AVION_BOMB_LD){
                            $Skills_1=array(1,6,10,14,18,22);
                            $Skills_2=array(7,11,15,19,23,34,39,129,131);
                            $Skills_3=array(8,12,16,20,24,30,32,37,40,43);
                            $Skills_4=array(9,13,17,21,25,31,33,36,38,41);
                        }elseif($Typ == AVION_ATTAQUE or $Typ == AVION_EMBARQUE){
                            $Skills_1=array(1,2,6,10,14,18,22);
                            $Skills_2=array(3,7,11,15,19,23,34,39,42,129,131);
                            $Skills_3=array(4,8,12,16,20,24,30,32,37,40);
                            $Skills_4=array(5,9,13,17,21,25,31,33,36,41,44);
                        }elseif($Typ == AVION_PAT_MAR){
                            $Skills_1=array(1,6,10,14,18,22);
                            $Skills_2=array(7,11,15,19,23,34,39,129,131);
                            $Skills_3=array(8,12,16,20,24,30,32,35,37);
                            $Skills_4=array(9,13,17,21,25,31,36,41);
                        }elseif($Typ == AVION_TRANSPORT){
                            $Skills_1=array(1,2,10,14,18,22);
                            $Skills_2=array(3,11,15,19,23,34,39);
                            $Skills_3=array(4,12,16,20,24,30,37);
                            $Skills_4=array(5,13,17,21,25,31,36,38,41);
                        }
						/*$Skills_1=array(1,2,6,10,14,18,22,26);
						$Skills_2=array(3,7,11,15,19,23,27,34,39,42,129,131);
						$Skills_3=array(4,8,12,16,20,24,28,30,32,35,37,40,43,45);
						$Skills_4=array(5,9,13,17,21,25,29,31,33,36,38,41,44);*/
						for($i=1;$i<=$Pilotes_Max;$i++)
						{
							if($i ==$Pilotes_Max)
							{
								$Grade=500;
								$Pilotage=75;
								$Skill=50;
								$Seed_Rang=10;
							}
							else
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
							$query.="('$Pilote_nom','$country','$today','$Pilotage','$Skill','$Skill','$Skill','$Skill','$Skill','$Grade','$Skill_p','$Escadrille','$Escadrille')";
							if($i <$Pilotes_Max)$query.=",";
						}
						$ok=mysqli_query($con,$query);
						/*if($ok)
						{
							echo "<p>[Debug] Pilotes IA créés avec succès!</p>";
							//$ins_id=mysqli_insert_id($con);
						}
						else
						{
							//$mes.="Erreur de création de pilote historique ".mysqli_error($con);
							echo "<p>[Debug] Erreur de création des pilotes IA !</p>";
						}*/
					}
					else
						$ok=true;
					if($ok)
						$msg_confirm="<div class='alert alert-info'>Vous activez l'unité <b>".$Nom."</b><br>Les pilotes ont été affectés à l'unité</div>";
					else
						$msg_confirm="<div class='alert alert-info'>Vous activez l'unité <b>".$Nom."</b><br>Aucun pilote n'a été affecté à l'unité</div>";
					$resetia=mysqli_query($con,"UPDATE Pilote_IA SET Avion=0,Cible=0,Escorte=0,Couverture=0,Couverture_Nuit=0,Alt=0,Task=0 WHERE Unit='$Escadrille'");
					mysqli_close($con);
					if(!$Admin)
						UpdateData("Officier_em","Credits",-25,"ID",$OfficierEMID);
				}
				else
					$msg_confirm="<div class='alert alert-danger'>Unité non activée !</div>";
				echo Afficher_Icone($Escadrille,$country,$Nom,1).$msg_confirm;
			}
		}
		else
			echo "Vous n'avez pas le temps pour cela!";
		echo "<br><a href='index.php?view=em_unites' class='btn btn-default' title='Retour'>Retour</a>";
	}
	else
		PrintNoAccess($country,1);
}
?>