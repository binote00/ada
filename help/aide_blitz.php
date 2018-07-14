<div class='row'>
	<div id="logo" style="float:left;"><img src="images/Logo_ada.png"></div>
	<div id="tab_menu" style="margin-left : 100px;">
	<h1>Aide sur la partie terrestre et navale</h1>
	<ol>
	<li><a href="#tab_command" class='lien'>La chaîne de commandement</a></li>
	<li><a href="#tab_goal" class='lien'>Principes de conquête</a></li>
	<li><a href="#tab_zones" class='lien'>Les Zones</a></li>
	<li><a href="#tab_dep" class='lien'>Le Déplacement</a></li>
	<li><a href="#tab_reco" class='lien'>La Reconnaissance</a></li>
	<li><a href="#tab_atk" class='lien'>L'Attaque</a></li>
	<li><a href="#tab_bomb" class='lien'>Le Bombardement</a></li>
	<li><a href="#tab_destru" class='lien'>La Destruction</a></li>
	<li><a href="#tab_assaut" class='lien'>L'Assaut</a></li>
	<li><a href="#tab_air" class='lien'>Les Attaques aériennes</a></li>
	<li><a href="#tab_appui" class='lien'>L'Appui aérien</a></li>
	<li><a href="#tab_ravit" class='lien'>Le Ravitaillement</a></li>
	<li><a href="#tab_pos" class='lien'>Les Positions</a></li>
	<li><a href="#tab_naval" class='lien'>La guerre navale</a></li>
	</ol>
	</div>
</div>
<hr>
<div> <!-- style='overflow:auto; height: 640px;'-->
	<div id="tab_command">
		<h1>1 - Fonctionnement de la chaîne de commandement</h1>
		<div class='alert alert-info'>L'officier peut faire le choix d'occuper un poste à l'état-major (plus d'informations <a href="index.php?view=regles#tab_em" class="lien">ici</a>) ou de commander une armée.
		<br>Une armée composée de divisions elles-mêmes composées de compagnies, unité de base comprenant des troupes ou des navires. Le commandant d'armée reçoit son commandement et ses troupes de l'état-major.
		<br>Outre les unités terrestres et navales, le commandant d'armée peut recevoir le commandement d'unités aériénnes tactiques (chasse, reconnaissance et attaque). Les unités aériennes stratégiques étant l'exclusivité de l'état-major aérien.
		<br></div>
		<!--<div class='alert alert-info'>Chaque officier actif (joueur) commande un bataillon faisant partie d'une Division sous l'autorité d'un commandant de division (joueur également).
		<br>La division fait partie d'une armée (commandée par un joueur) elle-même faisant partie d'un front (commandé par un état-major de joueurs).</div>-->

		<!--<p>Les commandants de division ont toute autorité pour ordonner les mouvements des bataillons sous leurs ordres. 
		<p>Ils donnent leurs ordres via le menu de commandement, en définissant :
		<ul><li>Un point de ralliement (là où toutes les unités de la division doivent se rendre)</li>
		<li>Un point de repli (là où les unités de la division doivent se rendre en cas de défaite)</li>
		<li>Un point de ravitaillement (là où le ravitailleur et les unités à ravitailler se rendront pour procéder au ravitaillement)</li>
		<li>Eventuellement un objectif à défendre ou attaquer, et dans ce dernier cas l'heure du début de l'attaque.</li></ul></p>-->

		<p>Les officiers doivent en principe suivre les consignes de leur état-major en ce qui concerne les déplacements.
		<br>- Chaque jour ils veilleront à rejoindre le point de ralliement, à moins que le commandant ait spécifié d'autres ordres, soit par message privé, soit via l'ordre du jour.
		<br>- S'ils ont besoin d'être ravitaillés, ils prendront soin de prendre contact avec leur hiérarchie afin de demander le ravitaillement du dépôt concerné.
		<br>- S'ils pensent devoir se replier, il est préférable qu'ils demandent l'autorisation à leur commandant <u>avant</u> d'effectuer leur retraite, afin de ne pas laisser un allié sans couverture ou un lieu non défendu.
		<br>- En cas d'objectif à attaquer défini, ils prendront soin d'attaquer à l'heure prévue par le commandant, et le cas échéant de signaler à leur commandant qu'ils attaquent, en attendant son autorisation.</p>
	</div>

	<div id="tab_goal">
		<h1>2 - La revendication des lieux</h1>
		<div class='alert alert-info'>Les lieux sont divisés en une ou plusieurs <a href="#tab_zones" class='lien'>zones</a>.
		<br>Les lieux peuvent être revendiqués par les différentes factions. La revendication d'un lieu entraîne le contrôle des infrastructures.</div>
		<p>- Pour pouvoir revendiquer un lieu, il est nécessaire de contrôler la caserne et certaines autres zones dans le cas de villes d'importance stratégique supérieure (cfr. plus bas)
		<br>- Le contrôle indique qu'une faction possède des unités sur une zone alors que la faction adverse n'en possède pas.
		<br>- L'occupation indique quelle nation occupe historiquement la localité. Cette information est purement informative et n'influence pas le jeu.</p>
		<p><b>Conditions de revendication des villes d'importance stratégique</b>
		<br>0 : la caserne suffit
		<br>1-3	: la caserne et une autre zone, suivant l'ordre d'importance des zones
		<br>4-5	: la caserne et deux autres zones, suivant l'ordre d'importance des zones
		<br>6-9	: la caserne et trois autres zones, suivant l'ordre d'importance des zones
		<br>10 : toutes les zones</p>
		<p><b>Ordre d'importance des zones</b>
		<br>1- Pont
		<br>2- Port
		<br>3- Gare
		<br>4- Noeud Routier
		<br>5- Aérodrome
		<br>6- Usine
		<br>7- Radar
		<br>8- Plage</p>
		<p><!--Les unités pouvant revendiquer un lieu sont les bataillons commandés par des officiers joueurs et les véhicules de commandement contrôlés par l'EM.-->
		<br>Les unités de reconnaissance peuvent revendiquer les zones d'un lieu reconnu, sauf la caserne. Ces unités ne peuvent revendiquer une zone s'ils sont en transit, en déroute ou sous le feu ou si bien sur des unités ennemies occupent la zone.
		<br>De plus, ces unités ne peuvent revendiquer un aérodrome que si aucune unité aérienne ennemie ne les occupe.</p>
		<h3>L'attrition</h3>
		Les unités terrestres se trouvant sur un lieu revendiqué par l'ennemi subiront une perte d'une unité par jour, sauf dans les cas suivants :
		<br>- La zone où se trouve l'unité est revendiquée par la faction de l'unité.
		<br>- Le véhicule de commandement de la division de l'unité se trouve sur le même lieu.
		<br>- L'unité a reçu un ravitaillement aérien dans les 24h qui précèdent.
	</div>

	<div id="tab_zones">
		<h1>3 - Les zones</h1>
		<div class='alert alert-info'>Chaque lieu est divisé en une ou plusieurs zones, dont au minimum celle de la caserne.
		<br>Les autres zones dépendent de la présence des infrastructures sur le lieu donné. Elles peuvent être un noeud routier, une gare, un port, un aérodrome, un pont stratégique, une zone industrielle, une plage ou un radar</div>
		<p><h3>La caserne</h3>
		C'est l'infrastructure qu'il est nécessaire de contrôler pour pouvoir revendiquer un lieu. C'est également dans cette infrastructure que les troupes contrôlant le lieu peuvent récupérer du moral.
		<br>Elle ne peut pas être détruite par une attaque aérienne, bien que la destruction du bâtiment principal réduise le niveau de fortification de la garnison.
		<br>Elle ne peut pas être détruite par une attaque terrestre, bien que la destruction du bâtiment principal réduise le niveau de fortification de la garnison.
		<br>Elle ne peut pas être réparée par les troupes du génie.
		<br>La DCA protégeant le site est gérée par l'état-major du front.
		<br>Il n'est pas possible de revendiquer un lieu si la caserne est une zone de combat.
		<br>Les unités présentes sur une caserne que leur nation contrôle bénéficient d'un bonus défensif en fonction du niveau de fortification.
		<br>Si le lieu comporte un pont, la caserne n'est pas accessible par une nation de la faction opposée à celle qui contrôle le lieu si le pont est détruit.
		<br>Si des unités aériennes sont présentes sur l'aérodrome du lieu lorsque la caserne de ce dernier est revendiquée par l'ennemi, tous les avions et les stocks de ces unités seront détruits.</p>

		<p><h3>L'aérodrome</h3>
		C'est l'infrastructure qu'il est nécessaire de contrôler pour pouvoir utiliser ou transférer des unités aériennes sur le lieu.
		<br>Elle peut être détruite par une attaque aérienne.
		<br>Elle peut être détruite par une attaque terrestre.
		<br>Elle peut être minée.
		<br>Elle ne peut pas être réparée par les troupes du génie.
		<br>La DCA protégeant le site est gérée par les unités aériennes utilisant l'aérodrome.
		<br>Si le lieu est revendiqué par l'ennemi, les unités aériennes éventuellement encore présentes sur l'aérodrome ne peuvent pas décoller.
		<br>Si le lieu comporte un pont, l'aérodrome n'est pas accessible par une nation de la faction opposée à celle qui contrôle le lieu si le pont est détruit.
		<br>Si des unités aériennes sont présentes sur l'aérodrome du lieu, il est nécessaire d'éliminer les gardes de ces unités afin de pouvoir revendiquer l'aérodrome. Lorsque ces gardes sont éliminés, les avions présents peuvent être détruits.</p>

		<p><h3>La gare</h3>
		Si cette infrastructure est détruite, les troupes terrestres ne peuvent pas utiliser le transport ferroviaire de ou vers ce lieu. Son état a également une grande influence sur le taux de ravitaillement des troupes (terrestres et aériennes) de la région.
		<br>Elle peut être détruite par une attaque aérienne.
		<br>Elle peut être détruite par une attaque terrestre.
		<br>Elle peut être minée.
		<br>Elle peut être réparée et détruite par les troupes du génie.
		<br>La DCA protégeant le site est gérée par l'état-major du front.
		<br>Il n'est pas possible d'utiliser le réseau ferroviaire si la gare de départ est une zone de combat.
		<br>Si le lieu comporte un pont, la gare n'est pas accessible par une nation de la faction opposée à celle qui contrôle le lieu si le pont est détruit.
		</p>

		<p><h3>Le pont</h3>
		Si cette infrastructure est détruite, les troupes terrestres ne peuvent pas utiliser le déplacement ferroviaire et les troupes terrestres ennemies ne peuvent rejoindre les autres zones du lieu.
		<br>Elle peut être détruite par une attaque aérienne.
		<br>Elle peut être détruite par une attaque terrestre.
		<br>Elle peut être minée.
		<br>Elle peut être réparée et détruite par les troupes du génie.
		<br>La DCA protégeant le site est gérée par l'état-major du front.</p>

		<p><h3>Le port</h3>
		C'est l'infrastructure qu'il est nécessaire de contrôler pour pouvoir recevoir des renforts terrestres sur le lieu. Son état a également une grande influence sur le taux de ravitaillement des troupes (terrestres et aériennes) de la région (doublé s'il s'agit d'une île).
		<br>Elle peut être détruite par une attaque aérienne.
		<br>Elle peut être détruite par une attaque terrestre.
		<br>Elle peut être minée.
		<br>Elle peut être réparée par les troupes du génie.
		<br>La DCA protégeant le site est gérée par l'état-major du front.
		<br>Les ports importants (dotés de toutes les infrastructures ou de valeur stratégique 4 ou supérieure) riposteront automatiquement à toute attaque navale adverse contre des navires alliés sur le même lieu.
		<br>Il n'est pas possible d'utiliser le ravitaillement ou d'accéder au garage si le port est une zone de combat.
		<br>Si le lieu comporte un pont, le port n'est pas accessible par une nation de la faction opposée à celle qui contrôle le lieu si le pont est détruit.</p>

		<p><h3>La route</h3>
		C'est l'infrastructure qu'il est nécessaire de contrôler pour pouvoir se déplacer rapidement depuis le lieu.
		<br>Elle ne peut pas être détruite par une attaque aérienne.
		<br>Elle ne peut pas être détruite par une attaque terrestre.
		<br>Elle ne peut pas être minée.
		<br>Elle ne peut pas être réparée par les troupes du génie.
		<br>La DCA protégeant le site est gérée par l'état-major du front.
		<br>Si la route est une zone de combat, les unités ne bénéficient pas du bonus du déplacement.
		<br>Si le lieu comporte un pont, la route n'est pas accessible par une nation de la faction opposée à celle qui contrôle le lieu si le pont est détruit.</p>
	</div>

	<div id="tab_dep">
		<h1>4 - Le Déplacement</h1>
		<div class='alert alert-info'>Le déplacement s'effectue de lieu à lieu ou de zone à zone au sein d'un même lieu.
		<br>Le déplacement de lieu à lieu est un mouvement stratégique où l'unité parcourt un nombre de kilomètres parfois important, tandis que le déplacement de zone à zone est un repositionnement tactique sur un même lieu.</div>
		<p><h3>Déplacement via le réseau ferroviaire</h3>
		Le déplacement via le réseau ferroviaire ne peut se faire qu'entre 2 lieux possédant une gare, contrôlés et revendiqués par votre faction.
		<br>Il est nécessaire de placer votre unité sur la gare du lieu de départ.
		<br>Il n'est pas possible d'utiliser le réseau ferroviaire si la gare de départ est une zone de combat.
		<br>Il n'est pas possible d'utiliser le réseau ferroviaire si le lieu de départ ou le lieu de destination possèdent un pont détruit.
		<br>Les officiers commandants des convois ferroviaires peuvent transporter les bataillons alliés via le réseau ferroviaire.</p>

		<h3>Déplacement par ses propres moyens</h3>
		<p>Le déplacement via ses propres moyens peut se faire vers n'importe quel lieu à portée d'autonomie de vos troupes.
		<br>Ce déplacement effectué entre deux lieux possédant un noeud routier permet d'annuler l'éventuel malus de terrain et de doubler l'autonomie (à condition que le noeud routier ne soit pas une zone de combat et à concurrence d'un maximum de 250km). Pour bénéficier de ce bonus, il est nécessaire de placer votre Bataillon sur le noeud routier avant d'effectuer le déplacement.
		<!--<br>La marche forcée augmente l'autonomie des unités à pieds (x2), en contrepartie d'un malus de moral.
		<br>Le coût en déplacement sera doublé (toujours à concurrence du maximum de CT) si le lieu de départ n'est pas contrôlé par votre faction.-->
		<br>Les déplacements de lieu en lieu ne sont pas possibles depuis un lieu forteresse contrôlé par l'ennemi.
		<br>La distance de déplacement sera considérablement réduite si le lieu de destination est contrôlé par l'ennemi (malus de 50% à l'autonomie, doublé si la zone de départ est une zone de combat).
		<br>Sur certains fronts les conditions climatiques peuvent fortement modifier les déplacements, référez vous à <a href='aide_meteo.php' target='_blank' rel='noreferrer' class='lien'>l'aide dédiée à la météo</a>
		<br>Référez-vous à la partie guerre navale concernant les spécificités du déplacement naval.</p>

		<h2>Les distances</h2>
		<p>Chaque unité possède une autonomie en km représentant la distance maximale qu'elle peut parcourir lors d'un déplacement.
		<br>Les différents bonus et malus peuvent modifier cette distance. La nature du terrain, la revendication par l'ennemi, l'utilisation du train, d'un noeud routier, de certaines compétences ou matériel.
		<br>Le front sur lequel évolue l'unité apporte lui aussi une limite maximale à la distance autorisée. Pour le front ouest, la limite est de 50km pour toute unité terrestre non motorisée, 60 si elle est motorisée ou si elle possède certaines compétences augmentant son autonomie. La distance de déplacement ne peut jamais être inférieur à 25km, et ce sur tous les fronts.</p>
		<p>Prenons comme exemple une unité d'infanterie dont l'autonomie de base est de 100km. Son déplacement en montagne vers un lieu revendiqué par l'ennemi sera de 25km (50% de malus de terrain et 50% de malus dû au contrôle par l'ennemi).
		<br>Un autre exemple avec une unité motorisée dont l'autonomie de base est de 400km. Son déplacement en forêt vers un lieu revendiqué par l'ennemi sera de 60km, et de 30 si son lieu de départ est une zone de combat.
		S'il s'agissait d'une plaine, son déplacement serait de 240km ou de 120km depuis une zone de combat. La réduction du front ouest amènerait la distance à 60km dans les deux cas.
		<br>Le jeu calcule automatiquement les distances pour vos unités et la carte vous permet de visualiser les possibilités de déplacement pour chaque unité.</p>

		<p><h4><small>Tableau des malus de déplacement en fonction du terrain:</small></h4><img src='images/old/blitz_move.jpg' title='Malus déplacements'></p>

		<h2>La Consommation</h2>
		<p>La valeur indiquée représente la quantité de carburant nécessaire pour un déplacement d'un véhicule ou navire au maximum de son autonomie.
		<br>La nature du terrain et la météo peuvent influencer la consommation. Le rayon d'action indique la distance maximum pouvant être parcourue avec la quantité de carburant disponible.</p>

		<h2>Effectuer une retraite</h2>
		<!--Coûte 12 CT si le bataillon est retranché ou en embuscade, 6 CT sinon. Si une Cie ne dispose pas de suffisamment de carburant (ou de moral) pour effectuer sa retraite, une partie des troupes peut déserter ou être capturé par l'ennemi.-->
		<p>Effectuer une retraite amène automatiquement le bataillon à sa base arrière, indiquée dans le profil sous les informations de la Division.
		<br>Attention que la retraite entraine une série de pénalités : 25% des effectifs, 50% des stocks, 10% de l'expérience et 100% du Moral.</p>

		<h2>Changement de front</h2>
		<p>Le planificateur stratégique de chaque nation est la seule personne habilitée à changer une unité de front.
		<br>Pour ce faire, l'unité doit être amenée sur une ville transit (indiquée sur la carte par ce symbole <img src="/images/map_transit.png">) par le commandant d'unité qui pourra ensuite faire une demande de changement de front qui parviendra au planificateur stratégique.</p>
	</div>

	<div id="tab_reco">
		<h1>5 - La reconnaissance</h1>
		<div class='alert alert-info'>La reconnaissance permet de révéler les infrastructures d'un lieu ainsi que les éventuelles unités ennemies présentes.
		<br>Une reconnaissance est nécessaire à toute attaque.</div>
		<p>Pour effectuer une reconnaissance, l'unité doit posséder une capacité de détection supérieure à 10.
		<br>Seules les unités présentes dans la même zone du même lieu que l'unité de reconnaissance (par exemple la gare de Paris) peuvent être découverts.</p>
		<p>Les unités ennemies en position défensive, retranchées ou en embuscade possédant une portée supérieure à 499 ont une chance de tirer sur l'unité de reconnaissance. La probabilité dépend du niveau d'expérience de l'unité en embuscade.
		Les unités en embuscade tirent automatiquement et font un maximum de dégâts.</p>

		<p>Facteurs influençant la réussite de la reco :
		<ul><li>-Expérience tactique de l'unité</li>
		<li>-Bonus de détection de l'unité</li>
		<li>-Taille de la cible (modifié par son camouflage)</li>
		<b>vs</b>
		<li>-Terrain</li>
		<li>-Météo</li>
		<li>-Bonus camouflage (position défensive x2, embuscade x2, retranchement ou ligne x4)</li>
		<li>-Expérience tactique de la cible (retranchement ou ligne x2, autre que position défensive ou embuscade /2)</li></ul></p>

		<p>Toute cible détectée passe en statut "visible", c'est à dire "peut être ciblée par une attaque terrestre ou aérienne".
		<!--<br>Lorsqu'une unité joueur subit une attaque causant des pertes importantes, le statut visible est automatiquement annulé dans certains cas:
		<br>-Le statut visible d'un Bataillon joueur est automatiquement annulé dès qu'une de Compagnie appartenant au Bataillon est totalement détruite.
		<br>-Le statut visible d'une Compagnie joueur est automatiquement annulé lorsqu'elle subit un bombardement terrestre ou naval si elle se trouve en position défensive lors du bombardement.
		<br>-Le statut visible d'une Compagnie joueur est automatiquement annulé lorsqu'elle subit une attaque aérienne si au moins une troupe/véhicule est détruit et qu'une des trois conditions suivantes est remplie:
		<br>1.Il s'agit d'un second passage du bombardier sur cette même Compagnie lors de ce bombardement.
		<br>2.Il ne reste plus qu'un seul troupe/véhicule dans la Compagnie.
		<br>3.Le nombre de troupe/véhicule restant est inférieur à la moitié des effectifs complets de la Compagnie.--></p>
	</div>

	<div id="tab_atk">
		<h1>6 - L'attaque</h1>
		<div class='alert alert-info'>Les unités peuvent attaquer une infrastructure ou une unité ennemie préalablement détectée.<br>Il existe un grand nombre d'attaques différentes selon le type d'unité utilisé.</div>
		<!--<h2>Spécificités des bataillons joueurs</h2>
		<h4><small>Chaque heure, l'unité peut attaquer une cible pour un certain montant en CT (en général 8).</h4></small>
		<p>Toute attaque ou bombardement supplémentaire effectué dans la même heure par la même unité voit son coût doublé.
		Exception faite des attaques ou bombardements sur la cible désignée dans les Ordres du Commandant, à l'heure prévue.</p>
		<h2>Spécificités des compagnies d'état-major</h2>-->
		<h2>Conditions d'attaque</h2>
		<ul>
			<li>Chaque jour, une unité (qui n'est pas déjà en mode combat, cfr plus bas) peut attaquer une cible pour un certain montant en CT dépendant de la composition de l'unité.</li>
			<li>Seules les cibles visibles peuvent être attaquées. Attaquer rend "visible" l'unité attaquante.</li>
			<li>Il n'est pas possible d'attaquer depuis une position défensive, retranchée, en embuscade ou en ligne.</li>
			<li>Pour pouvoir cibler une unité spécifique lors d'une attaque, l'unité attaquante doit posséder une <a href="#tab_allonge" class='lien'><b>allonge</b></a> égale ou supérieure à la portée de l'unité ciblée.</li>
		</ul>
		<div class='alert alert-warning'>L'unité attaquante passe alors en "mode combat" pour 24h. Elle ne peut plus ni attaquer, ni se déplacer, ni changer de position.</div>
		<p id="tab_allonge"><b>L'allonge de raid</b></p>
		La valeur de base d'allonge de raid d'une unité est indiquée sur la fiche correspondant à son matériel dans l'encyclopédie du jeu.
		<p>L'allonge de raid peut être modifiée par les paramètres suivants :</p>
		<ul>
			<li>L'expérience de l'unité augmente légèrement l'allonge de raid (maximum 500m).</li>
			<li>Le contrôle du lieu (via une revendication) augmente l'allonge de raid.</li>
			<li>La compétence tactique éventuelle de l'unité (icône kaki).</li>
			<li>L'équipement éventuel de l'unité (icône jaune).</li>
			<li>Le type de terrain (cfr plus haut, le tableau dans la section 4 Déplacement).</li>
			<li>Les conditions spécifiques à certaines unités (voir plus bas).</li>
		</ul>

		<p><u>Modificateurs d'allonge de raid spécifiques à certaines unités</u></p>
		<p>Bonus pour l'infanterie en cas d'attaque en terrain difficile (+3000 en allonge de raid si non détecté en forêt, montagne, marais ou jungle)
		<br>Bonus pour la cavalerie en cas d'attaque (+100% allonge de raid, automatique)
		<br>Bonus pour les unités motorisées en cas d'attaque en terrain plat (allonge doublée en plaine ou dans le désert contre les unités non protégées par une ligne si l'attaquant n'est pas détecté)</p>
		<h2>Phases d'une attaque</h2>
		<ul>
			<li><a href="#tab_init" class='lien'>Initiative</a></li>
			<li><a href="#tab_couv" class='lien'>Couverture éventuelle</a></li>
			<li><a href="#tab_tir" class='lien'>Attaque et riposte (tir)</a></li>
			<li><a href="#tab_dg" class='lien'>Dégâts éventuels</a></li>
		</ul>
		<h3 id="tab_init">L'initiative</h3>
		<h4><small>Initiative : L'unité possédant la valeur d'initiative la plus élevée tirera la première.</h4></small>
		Obtenue par comparaison de la Portée ou de la Vitesse(X50) + Tactique <=vs=> Portée ennemie + Tactique ennemie. 
		<p>Les unités en embuscade bénéficient d'un bonus d'initiative.
		<br>les unités attaquant à courte portée reçoivent un malus d'initiative, sauf si l'unité en défense est sous le feu ou clouée au sol.
		<br>les unités immobiles (retranchées, en ligne, en appui, clouées au sol) perdent automatiquement l'initiative.
		<br>les unités alliées en appui sur la même zone apportent un bonus d'initiative à l'unité attaquante.
		<br>les unités alliées en ligne, en appui, en embuscade ou en position défensive sur la même zone apportent un bonus d'initiative à l'unité en défense.
		<br>Une unité terrestre attaquante réussissant sa percée et infligeant des pertes à l'unité qu'elle cible la forcera à passer en mode combat pour 24h</p>
		<p><b>Règles d'initiative et consignes de tir</b></p>
		<p>Distance d'attaque = distance de tir définie par l'attaquant
		<br>Distance de riposte = distance de tir définie par le défenseur</p>
		<p><u>Si l'attaquant a l'initiative</u>
		<br>La distance de tir de l'attaquant sera celle définie par l'attaquant (distance d'attaque).
		<br>Si "Se replier dès que possible" est défini, le défenseur ne pourra riposter qu'à la distance d'attaque (si ses consignes de tir et la portée l'autorisent). 
		<br>Si la distance de riposte est inférieure à la distance d'attaque et que la consigne de tir sont "Ne pas riposter à distance supérieure", le défenseur ne ripostera pas.
		<br>Si "Continuer l'attaque quoi qu'il arrive" est défini, l'unité attaquante poursuivra son attaque et le défenseur pourra riposter à la distance de riposte.</p>
		<p><u>Si le défenseur a l'initiative</u>
		<br>La distance de tir de l'attaquant sera la distance de riposte si cette distance est supérieure à la distance d'attaque, sinon la distance de tir sera celle définie par l'attaquant.
		<br>Si "Se replier si nécessaire" est défini et si une unité de couverture ennemie détruit une des unités attaquante, "continuer l'attaque quoi qu'il arrive" se transforme automatiquement en "Se replier dès que possible".
		<br>Si la distance de riposte est inférieure à la distance d'attaque et que la consigne de tir sont "Ne pas riposter à distance supérieure", le défenseur ne ripostera pas.
		<br>Si "Se replier dès que possible" est défini et que l'attaquant n'est pas parvenu à la distance d'attaque car la distance de riposte est supérieure à la distance d'attaque, l'attaquant ne tirera pas. 
		<br>Le défenseur ripostera à la distance d'attaque si la consigne "Toujours riposter" est définie.</p>

		<p><u>Distance d'attaque maximale en fonction du type de zone et de la météo</u></p>
		<p>Maritime = 20000m
		<br>Maritime sous la pluie ou les nuages = 10000m
		<br>Maritime sous la tempête ou de nuit = 5000m
		<br>Forêt, Urbain, Jungle ou Marais = 500m
		<br>Forêt, Urbain, Jungle ou Marais sous la tempête ou de nuit = 200m
		<br>Collines ou Montagnes (non boisées) = 1000m
		<br>Collines ou Montagnes (non boisées) sous la pluie ou les nuages = 700m
		<br>Collines ou Montagnes (non boisées) sous la tempête ou de nuit = 500m
		<br>Plaines ou désert = 2500m
		<br>Plaines ou désert sous la pluie ou les nuages = 1500m
		<br>Plaines ou désert sous la tempête ou de nuit = 500m</p>

		<h3 id="tab_couv">Couverture des unités alliées</h3>
		<p>Une unité possédant une arme de soutien (artillerie par exemple) en appui aura une chance de couvrir l'unité attaquée en tirant sur l'unité attaquante
		<br>Une unité mobile en position défensive (pas retranchement ni embuscade !) aura une chance de riposter en cas d'attaque ennemie sur une unité alliée au même lieu (et sur la même position !)
		<br>Une unité d'artillerie anti-char ou de chasseurs de chars en embuscade, formant une ligne ou retranchée ripostera contre les unités attaquantes mobiles.
		<br>Une unité d'infanterie formant une ligne (ou retranchée) annulera toute possibilité d'attaquer une autre unité alliée sur la même position qu'elle. L'attaquant aura alors le choix d'attaquer les unités d'infanterie retranchées en première ligne. Une exception cependant, les unités mobiles auront une chance d''effectuer une percée. L'expérience des unités et le nombre d'unités en ligne favorisent la défense face à une percée. L'expérience de l'unité attaquante et sa vitesse (modifiée) favorisent les chances de percée.
		<br>L'arme utilisée pour l'attaque sera : Anti-Tank si l'ennemi est blindé, sinon Soutien si la portée est inférieure à 3000, sinon Armement par défaut</p>
		<p><img src='images/old/bz_couverture.jpg' title='Attaque'></p>

		<h3 id="tab_tir">Le tir</h3>
        <p>Dans le jeu, l'attaque est scindée en différentes tentatives de tir de la part de l'unité gagnant l'initiative, suivie d'une riposte de l'autre unité.
        <br>Le succès ou l'échec du tir est déterminé par le résultat de la différence entre les différents bonus et malus. Au-delà de ce résultat, certaines conditions peuvent amener à la réussite ou l'échec automatique du tir.
        <h5>Bonus de tir</h5>
        <ul>
            <li>L'expérience de l'unité</li>
            <li>Les éventuels bonus de visée dus au matériel ou aux compétences</li>
            <li>La taille de la cible, pouvant être modifiée par le camouflage ou la position de la cible</li>
            <li>La fiabilité du matériel</li>
        </ul>
        <h5>Malus de tir</h5>
        <ul>
            <li>La distance de tir</li>
            <li>La vitesse de la cible, modifiée par la position de la cible et par la nature du terrain<br>Une unité à court de carburant ou de moral aura toujours une vitesse nulle</li>
            <li>L'expérience de la cible, pouvant être modifiée par la position de la cible</li>
            <li>La météo</li>
            <li>La fiabilité du matériel</li>
        </ul>
        <h5>Critique et échec</h5>
        <p>Il existe toujours une petite chance de réussite critique permettant à l'unité attaquante de réussir un tir peu importe les bonus/malus, tout comme il existe toujours une petite chance d'échec critique menant à l'échec du tir peu importe les bonus/malus
        <br>Certaines compétences tactiques d'unité permettent à l'unité attaquée d'avoir une chance d'éviter totalement un tir ou plus rarement une attaque. Dans ce dernier cas tous les tirs sont alors considérés comme ratés</p>
		<h3 id="tab_dg">Les dégâts</h3>
		<p>Les dégâts sont soumis aux mêmes règles que lors des attaques aériennes (cfr règles de base).
		<br>Spécificité : les unités de petite taille (après modification de camouflage) bénéficient d'un bonus de 'blindage' dans certaines zones, du au couvert.
		<br>-Les forêts et collines boisées, montagnes, montagnes boisées et les jungles accordent un blindage de 8 pour les unités dont la taille est inférieure à 2, et de 4 pour les unités dont la taille est inférieure à 3.
		<br>-Les zones urbaines accordent un blindage de 13 pour les unités dont la taille est inférieure à 2, de 8 pour les unités dont la taille est inférieure à 3, et de 4 pour les unités dont la taille est inférieure à 5.
		<br>Toute attaque consomme du carburant et des munitions.
		<br>Toute attaque (ou riposte) réussie accorde un bonus de moral à l'unité.
		<br>Toute destruction d'une partie de l'unité entraine une perte de moral.
		<br>Toute unité dont les effectifs sont réduits à 0 voit son expérience et son moral réduits également à 0.
	</div>
	<div class='alert alert-warning'>Une unité terrestre attaquante réussissant sa percée et infligeant des pertes à l'unité qu'elle cible la forcera à passer en "mode combat" pour 24h, l'empêchant d'agir durant cette période.</div>

	<div id="tab_bomb">
		<h1>7 - Le bombardement</h1>

		<h2>Conditions du bombardement</h2>
		<ul>
			<li>Chaque jour, une unité possédant une arme de soutien dont la portée est supérieure à 2500 peut bombarder une cible située sur le même lieu pour un certain montant en CT.</li>
			<li>Seules les cibles visibles peuvent être bombardées. Bombarder rend "visible" l'unité attaquante.</li>
			<li>Il n'est pas possible de bombarder depuis une position retranchée, en embuscade ou en ligne.</li>
			<li>Lors d'un bombardement, l'unité attaquante a toujours l'initiative.</li>
			<li>Une unité ne peut être bombardée par de l'artillerie terrestre ou navale qu'une seule fois toutes les 24h.</li>
		</ul>

		<div class='alert alert-warning'>A la différence de l'attaque, le bombardement ne force pas l'unité ciblée à passer en mode combat.</div>

		<h2>Riposte éventuelle</h2>
		<p>Les <a href='#' class='popup'>unités d'artillerie<span>les navires ou les unités terrestres possédant une arme de soutien d'une portée supérieure à 2500m</span></a> en appui sur le même lieu riposteront automatiquement si leur portée est au moins <a href='#' class='popup'>égale à la moitié<span>Le double de l'expérience de l'unité est ajouté à la valeur de la portée</span></a> de celle de l'unité attaquante.
		<br>La compétence Artilleur Expert augmente la portée de riposte, tout comme celle du tir.
		<br>Le nombre de tirs de riposte peut varier (référez-vous aux fiches de l'encyclopédie) et être augmenté par la compétence Contre-Batterie.</p>

		<h2>Dégâts</h2>
		<p>Les dégâts dépendent du type de munition et de la cible.<br>Dans le cas où le bombardement réduit la force de l'unité en-dessous de 25% de son effectif maximal, l'unité bombardée passe en position "Sous le feu".</p>
		<h3>Conditions particulières</h3>
		<ul>
			<li>Les unités retranchées dont la Taille est inférieur à 2 ne subissent que la moitié des dégâts lors d'un bombardement d'artillerie.</li>
			<li>Les unités de petite taille (après modification de camouflage) bénéficient d'un bonus de 'blindage' dans certaines zones, du au couvert.</li>
			<li>Les forêts et collines boisées, montagnes, montagnes boisées et les jungles accordent un blindage de 8 pour les unités dont la taille est inférieure à 2, et de 4 pour les unités dont la taille est inférieure à 3.</li>
			<li>Les zones urbaines accordent un blindage de 13 pour les unités dont la taille est inférieure à 2, de 8 pour les unités dont la taille est inférieure à 3, et de 4 pour les unités dont la taille est inférieure à 5.</li>
		</ul>

		<h2>Portée de bombardement</h2>
		<p>La portée (autant pour l'unité attaquante que pour les éventuelles ripostes) peut être modifiée par les paramètres suivants :</p>
		<ul>
			<li>La position : Cloué au sol, En Ligne, Embuscade ou Retranché, divisent la portée par 2.</li>
			<li>La météo peut réduire la portée jusqu'à la diminuer de moitié dans le cas d'une tempête.</li>
			<li>Le type de munition : (par exemple : la munition HEAT divise la portée par 2).</li>
			<li>L'expérience de l'unité augmente légèrement la portée (maximum 500m).</li>
			<li>Le contrôle du lieu (via une revendication) augmente la portée de 500m.</li>
			<li>Les compétences de l'officier commandant (par exemple : Artilleur Expert augmente la portée de 10%).</li>
			<li>Le type de terrain (voir plus bas).</li>
			<li>Les conditions spécifiques à certaines unités (voir plus bas).</li>
		</ul>
		<h3>Portée maximale en fonction du type de zone et de la météo</h3>
		<p>Maritime = 20000m
		<br>Maritime sous la pluie ou les nuages = 10000m
		<br>Maritime sous la tempête ou de nuit = 5000m
		<br>Forêt, Urbain, Jungle ou Marais = 500m
		<br>Forêt, Urbain, Jungle ou Marais sous la tempête ou de nuit = 200m
		<br>Collines ou Montagnes (non boisées) = 1000m
		<br>Collines ou Montagnes (non boisées) sous la pluie ou les nuages = 700m
		<br>Collines ou Montagnes (non boisées) sous la tempête ou de nuit = 500m
		<br>Plaines ou désert = 2500m
		<br>Plaines ou désert sous la pluie ou les nuages = 1500m
		<br>Plaines ou désert sous la tempête ou de nuit = 500m</p>

		<h3>Modificateurs de portée spécifiques à certaines unités</h3>
		<p>Bonus en mer en cas de bombardement (maximum 2300m)
		<br>Bonus pour les sous-marins en plongée en cas de torpillage (+100%)</p>
	</div>

	<div id="tab_destru">
		<h1>8 - La destruction</h1>
		<div class='alert alert-info'>La destruction est une action permettant de détruire les infrastructures ennemies, tel que : la DCA, l'aérodrome, les usines, la gare, etc...
		<br>La destruction permet également d'attaquer la caserne, soit en attaquant directement la garnison, soit en tentant de réduire les éventuelles fortifications.</div>

		<p>Pour effectuer une destruction, il est nécessaire de posséder une <b>arme de soutien</b>.
		<br>Le lieu doit également <b>être reconnu</b>, soit via une reco stratégique aérienne, soit via une reco terrestre.
		<br>Comme pour l'attaque, il n'est pas possible d'effectuer une destruction depuis une position retranchée, en ligne ou en embuscade.</p>

		<p>L'unité attaquante a toujours l'initiative lors d'une destruction.
		<br>Effectuer une destruction rend "visible" l'unité attaquante.</p>
		<div class='alert alert-danger'>Lors d'une destruction, les éventuelles mines posées <b>sur la zone où se trouve l'unité attaquante</b> affectent l'unité attaquante.</div>

		<p>Les autres paramètres sont identiques à une attaque classique.</p>
	</div>

	<div id="tab_assaut">
		<h1>9 - L'assaut</h1>
		<div class='alert alert-info'>L'assaut est une attaque spéciale réservée aux unités d'infanterie de tout type.</div>

		<p>Cet assaut est cependant soumis à certaines conditions :
		<ul><li>l'infanterie ne doit pas être retranchée ou en ligne.</li>
		<li>l'infanterie doit posséder un Moral positif.</li>
		<li>l'infanterie ne peut pas être déjà engagée dans un combat (offensif comme défensif).</li>
		<li>l'infanterie ne pourra attaquer que les unités ennemies "sous le feu".</li></ul></p>

		<p>La position "sous le feu" est une position (au même titre que "en ligne, retranché, en mouvement, en déroute, etc...") qui est automatiquement appliquée à toute unité terrestre ciblée par un bombardement terrestre ou touchée par un bombardement aérien. L'effet est automatique et annule donc la position précédente de l'unité.
		<br>La position "sous le feu" peut être annulée par tout changement de position ou tout mouvement de l'unité ciblée.
		<br>Si une unité sous le feu est attaquée, sa position passe à "cloué au sol", sauf si l'unité attaquante est une unité d'infanterie.
		<br>Lors d'un assaut, les unités d'infanterie doublent les dégâts infligés aux unités adverses.
		<br>Lors d'un assaut en zone urbaine, montagneuse ou forestière : si l'unité d'infanterie attaquante n'a pas été détectée par l'ennemi, elle bénéficie d'un bonus d'allonge de raid de 3km.</p>
	</div>

	<div id="tab_air">
		<h1>10 - Les attaques aériennes</h1>
		<div class='alert alert-info'>Les unités de DCA d'une faction défendent automatiquement toutes les unités alliées présentes sur un lieu. 
		Chaque attaque aérienne est ciblée par un maximum de 2 unités de DCA, les plus expérimentées en premier.</div>

		<p>La position "sous le feu" est une position (au même titre que "en ligne, retranché, en mouvement, en déroute, etc...") qui est automatiquement appliquée à toute unité terrestre ciblée par un bombardement terrestre ou touchée par un bombardement aérien. L'effet est automatique et annule donc la position précédente de l'unité.
		<br>La position "sous le feu" peut être annulée par tout changement de position ou tout mouvement de l'unité ciblée.
		<br>Si une unité sous le feu est attaquée, sa position passe à "cloué au sol", sauf si l'unité attaquante est une unité d'infanterie.</p>
	</div>

	<div id="tab_appui">
		<h1>11 - L'appui aérien</h1>
		<p><h4><small>Chaque officier peut demander un appui aérien (réalisé par d'autres joueurs) via les transmissions. Ces missions sont appelées missions de coopération.</h4></small></p>
		Chaque bataillon ne peut effectuer qu'une seule demande à la fois, une nouvelle demande remplaçant la précédente. La demande ne peut concerner que le lieu où se trouve le bataillon.
	</div>

	<div id="tab_ravit">
		<h1>12 - Le ravitaillement</h1>

		<!--<h2>Les unités joueurs</h2>
		<p>Les unités composées de véhicules pouvant transporter des charges peuvent "charger" des munitions (ou du carburant dans le cas des camions-citernes) entre les différents dépôts de la faction.
		<br>Une fois au front, ces unités peuvent "décharger" leur fret et approvisionner toute unité de leur faction sur place (terrestre comme aérienne), ou stocker dans un dépôt contrôlé par leur faction.</p>

		<p>Chaque officier peut demander du ravitaillement à un autre joueur en utilisant le formulaire de demande, accessible via les transmissions.
		<br>Le ravitaillement via un autre joueur est plus efficace que de se ravitailler directement dans un dépôt.</p>

		<p>Les chargements peuvent être livrés en partie ou en totalité.
		<br>Lors de la livraison de munitions, le livreur peut spécifier le type de munitions à livrer au moment du déchargement, ce qui changera le type de munitions de l'unité livrée (cette option ne fonctionne que pour les unités terrestres ou navales).</p>

		<h2>Les unités EM</h2>-->
		<h2>Les besoins des unités</h2>
		<p>Certaines unités nécessitent des quantités de carburant et/ou de munitions pour pouvoir se déplacer ou effectuer une action offensive. La quantité nécessaire à chaque unité est indiquée dans le menu d'ordres.
		<br>Tous les dépôts contrôlés par la faction de l'unité et situés dans un certain rayon autour d'elle peuvent lui fournir automatiquement ce dont elle a besoin. La quantité de carburant et de munitions disponibles est également indiquée dans le menu d'ordres.
		<br>Si aucun dépôt ne possède les quantités suffisantes, l'unité ne peut effectuer d'action offensive jusqu'à ce qu'un dépôt soit ravitaillé. Les dépôts peuvent être ravitaillés par les trains, les cargos ou les camions logistiques.
		<br>Les unités de transport aérien peuvent ravitailler les unités pour 24h, l'unité recevant alors ce symbole <img src='images/map/air_ravit.png'>

		<h3>Exemple de troupe</h3>
		24/24 <img src='images/vehicules/vehicule172.gif' title='B1bis'>
		<br><img src='images/skills/skillo2.png'><img src='/images/skills/skille6.png'>
		<span class='label label-primary' title='Consommation déplacement'>864L  Octane 87</span><span class='label label-info' title='Consommation attaque'>1200 obus de 75mm</span>

		<p>Dans le menu d'ordre des unités terrestres, sous l'icône du véhicule ou de la troupe, des informations indiquent les consommations en carburant (en bleu sur l'exemple) et en munitions (en kaki sur l'exemple) de l'unité.</p>
		S'il n'y a rien, l'unité n'a pas besoin de carburant ou de munitions pour être opérationnelle.</p>

		<h3>Exemple d'unité aérienne</h3>
		<table class='table'><thead><tr><th>Requis Escadrille 1 (Bréguet 691)</th><th>Requis Escadrille 2 (Bréguet 691)</th><th>Requis Escadrille 3 (Bréguet 691)</th></tr></thead>
		<tr><td><span class='label label-primary'>6300L Octane 87</span></td><td><span class='label label-primary'>8400L Octane 87</span></td><td><span class='label label-primary'>8400L Octane 87</span></td></tr></table>

		<p>Au niveau des unités aériennes, chaque escadrille n'a besoin que de la quantité et du type de carburant indiqué pour chaque mission. Il n'y a aucun prérequis en munitions pour les unités aériennes.</p>

		<h2>Les dépôts</h2>
		Les dépôts contiennent toutes les ressources nécessaires aux troupes, qu'elles soient terrestres, aériennes ou navales. Chaque lieu d'importance stratégique 4 ou supérieure contient un dépôt.
		<br>Les dépôts peuvent ravitailler les unités situées dans un certain rayon autour d'eux (200km sur les fronts ouest et med). Seule les unités de la faction contrôlant le lieu du dépôt peuvent s'y ravitailler.
		<br>Lorsque plusieurs dépôts sont en mesure de ravitailler une unité, c'est le dépôt possédant le plus gros stock qui est sélectionné.
		<br>Les stocks des dépôts peuvent être réduits par l'ennemi, soit via une destruction terrestre, soit via un sabotage commando, soit via un bombardement aérien ou naval.
		<br>Les commandants de front ont la possibilité de brûler un dépôt allié afin d'éviter qu'il ne tombe aux mains de l'ennemi.
	</div>

	<div id="tab_pos">
		<h1>13 - Les positions</h1>

		<p><h3>La position défensive</h3>
		<ul><li>Vitesse /2</li>
		<li>Expérience tactique lors d'une attaque /2</li>
		<li>Expérience tactique lors d'une défense x2</li>
		<li>Camouflage x2</li>
		<li>Retraite à coût réduit</li>
		<li>Déplacement automatique (camouflage) en cas de bombardement</li>
		<li>Chance de contre-attaque en cas de reco</li>
		<li>Chance de contre-attaque en cas d'attaque ennemie, si l'unité est une unité mobile (sur roues ou chenilles, avec une vitesse supérieure à 10km/h)</li>
		<li>Les unités de DCA couvriront les unités de leur faction située sur le même lieu contre les attaques aériennes</li></ul></p>

		<p><h3>Le retranchement</h3>
		<ul><li>Vitesse nulle (immobilisé)</li>
		<li>Attaque impossible</li>
		<li>Expérience tactique lors d'une défense x4</li>
		<li>Camouflage x4</li>
		<li>Les unités retranchées dont la Taille est inférieure à 2 (comme l'infanterie non mécanisée) ne subissent que la moitié des dégâts lors d'un bombardement d'artillerie ou lors d'un bombardement aérien.</li>
		<li>Les unités retranchées peuvent bénéficier d'un bonus de 'blindage' doublé lorsqu'elles sont situées dans une caserne fortifiée contrôlée par leur nation. Ce bonus est effectif uniquement en défense lors d'une attaque terrestre ou lors d'un bombardement aérien, terrestre ou naval.</li>
		<li>Il est impossible de se retrancher lorsqu'il neige, bien que les unités déjà retranchées continueront de bénéficier du retranchement.</li></ul></p>

		<p><h3>L'embuscade</h3>
		<ul><li>Vitesse nulle (immobilisé)</li>
		<li>Expérience tactique lors d'une défense x2</li>
		<li>Camouflage x4</li>
		<li>Contre-attaque automatique en cas de reco ennemie</li>
		<li>Contre-artillerie en cas de bombardement</li>
		<li>Les unités d'artillerie anti-char et les chasseurs de chars embusqués riposteront contre les unités attaquantes mobiles.</li>
		<li>L'embuscade est plus efficace à courte portée.</li>
		<li>L'embuscade n'est pas possible dans les zones de plaines, sur les plages et dans les zones désertiques.</li>
		<li>Une unité en embuscade non préalablement détectée a des chances de "prendre l'ennemi de flanc". Ses chances de viser le blindage arrière sont de 50%, le latéral de 30% et le frontal de 20%.
		Si l'unité a été préalablement détectée, ses chances de viser le blindage frontal sont de 60%, le latéral de 35% et l'arrière de 5%. L'expérience de l'unité modifie le % de manière favorable.</li></ul></p>

		<p><h3>L'appui</h3>
		<ul><li>Vitesse nulle (immobilisé)</li>
		<li>Expérience tactique lors d'une défense /2</li>
		<li>Chance de contre-attaque en cas d'attaque ennemie, si l'unité possède une arme de soutien</li>
		<li>Contre-artillerie en cas de bombardement</li>
		<li>Les unités de DCA couvriront les unités de leur faction située sur le même lieu contre les attaques aériennes</li></ul></p>

		<p><h3>La ligne de défense</h3>
		<ul><li>Vitesse nulle (immobilisé)</li>
		<li>Attaque impossible</li>
		<li>Expérience tactique lors d'une défense x2</li>
		<li>Camouflage x4</li>
		<li>Les unités en ligne de défense dont la Taille est inférieur à 2 ne subissent que la moitié des dégâts lors d'un bombardement d'artillerie.</li>
		<li>Les unités en ligne peuvent bénéficier d'un bonus de 'blindage' lorsqu'elles sont situées dans une caserne fortifiée contrôlée par leur nation. Ce bonus est effectif uniquement en défense lors d'une attaque terrestre ou lors d'un bombardement aérien, terrestre ou naval.</li>
		<li>Les unités d'infanterie en ligne de défense protègent les unités alliées sur leur position de toute attaque terrestre (à condition d'avoir un moral supérieur à 50).</li>
		<li>Les unités d'artillerie anti-char et les chasseurs de chars en ligne de défense riposteront contre les unités attaquantes mobiles.</li></ul></p>

        <p><h3>Sentinelle</h3>
            <ul><li>Vitesse /2</li>
                <li>Camouflage x2</li>
                <li>Attaque impossible</li>
                <li>Seules les unités de reconnaissance peuvent utiliser cette position.</li>
                <li>Les unités en sentinelle ont une chance de détecter les unités ennemies traversant leur zone.</li>
            </ul></p>

		<p><h3>En mouvement</h3>
		<ul><li>Position automatique en cas de retraite, mouvement ou reco</li>
		<li>Expérience tactique lors d'une défense /2</li></ul></p>

		<p><h3>En déroute</h3>
		<ul><li>Vitesse nulle (immobilisé)</li>
		<li>Attaque impossible</li>
		<li>Expérience tactique lors d'une défense /2</li></ul></p>

		<p><h3>En transit</h3>
		<ul><li>Vitesse nulle (immobilisé) dans le cas d'une unité joueur</li>
		<li>Attaque impossible</li>
		<li>Dans le cas d'une unité joueur en transit, son sort est lié à celui des barges qui la transportent.</li>
		<li>Dans le cas d'une unité EM, elle peut être déplacée sur les lieux maritimes et les ports alliés. Elle se défendra comme une barge classique.</li>
		<li>Pour l'embarquement et le désembarquement, référez vous à <a href='aide_transit.php' target='_blank' rel='noreferrer' class='lien'>l'aide dédiée au transit</a></li>
		</ul></p>

		<p><h3>Cloué au sol</h3>
		<ul><li>Vitesse nulle (immobilisé)</li>
		<li>Attaque impossible</li>
		<li>Camouflage x2</li></ul></p>

		<p><h3>Sous le feu</h3>
		<ul><li>Vitesse nulle (immobilisé)</li>
		<li>Attaque impossible</li>
		<li>Expérience tactique lors d'une défense /2</li>
		<li>Dégâts reçus doublés en cas d'assaut ennemi</li></ul></p>

		<p><h4><small><img src='images/camouflage.png'> Indique que votre unité n'a pas été repérée par l'ennemi</small></h4>
		<h4><small><img src='images/souslefeu.png'> Indique que votre unité est sous le feu ennemi</small></h4></p>
	</div>

	<div id="tab_naval">
		<h1>14 - Spécificités de la guerre navale</h1>

		<h3>Fonctionnement de la chaine de commandement</h3>
		<h4><small>Chaque officier actif (joueur) fait partie d'une flotte (ou Kantai, Task Force, Fleet, etc...) sous l'autorité d'un commandant (joueur également).</h4></small>

		<!--<p>Les commandants de flotte ont toute autorité pour ordonner les mouvements des escadres (Escadre, Sentai, Squadron, Squadra, etc...) sous leurs ordres. 
		<br>Ils donnent leurs ordres via le menu de commandement, en définissant :
		<ul><li> Un point de ralliement (là où tous les navires de la flotte doivent se rendre)</li>
		<li> Un point de repli (là où les navires doivent se rendre en cas de défaite)</li>
		<li> Un point de ravitaillement (là où le ravitailleur et les navires à ravitailler se rendront pour procéder au ravitaillement)</li>
		<li> Eventuellement un objectif à défendre ou attaquer, et dans ce dernier cas l'heure du début de l'attaque.</li></ul></p>

		<p>Les officiers (joueurs commandants d'escadre) doivent suivre les ordres de leur commandant en ce qui concerne les déplacements.
		<br>- Chaque jour ils veilleront à rejoindre le point de ralliement, à moins que le commandant ait spécifié d'autres ordres, soit par message privé, soit via l'ordre du jour.
		<br>- S'ils ont besoin d'être ravitaillés, ils se rendront au point de ravitaillement en prenant soin de prendre contact avec leur ravitailleur via les transmissions. Il vaut mieux qu'ils préviennent leur commandant avant de quitter leur position.
		<br>- S'ils pensent devoir se replier, ils demandent l'autorisation à leur commandant <u>avant</u> d'effectuer leur retraite, afin de ne pas laisser un allié sans couverture par exemple.
		<br>- En cas d'objectif à attaquer défini, ils prendront soin d'attaquer à l'heure prévue par le commandant, et le cas échéant de signaler à leur commandant qu'ils attaquent, en attendant son autorisation.</p>-->

		<h3>Le déplacement</h3>

		<!--<p class="lead">Appareiller</p>
		Déplacer vos navires entre les lieux maritimes, les ports ou les plages.
		<br>Veillez à posséder une quantité suffisante de carburant. Le mauvais temps et la distance influent sur la consommation, référez-vous au rayon d'action pour connaitre la distance pouvant être parcourue par votre unité.
		<br>Comme pour la partie terrestre, la distance maximale pouvant être parcourue lors d'un déplacement est égale à l'autonomie la plus faible parmi les unités composant votre escadre.
		<br>Référez-vous à la partie terrestre concernant les modificateurs du coût de déplacement.-->
		
		Vous pouvez déplacer vos navires entre les lieux maritimes, les ports ou les plages.
		<br>Les navires utilisent un système de <a class='lien' title='Aide' href='aide_jours.php' target='_blank' rel='noreferrer'>jours de mer</a> pour leurs déplacements.
		<br>Il n'est pas possible de se déplacer directement de port à port, le passage par un lieu maritime est obligatoire.
		<br>La météo influe sur les déplacements. Les tornades empêcheront tous les navires de se déplacer, tandis que les tempêtes feront obstacle aux plus petits.

		<h4>Limite d'unités par zone</h4>
		<!--Attention que chaque zone maritime peut contenir un nombre maximum d'unités navales de la même faction en même temps. La limite est fixée à 32 (8 joueurs) pour le front Pacifique et 24 (6 joueurs) pour les autres fronts.-->
		Attention que chaque zone maritime peut contenir un nombre maximum d'unités navales de la même faction en même temps.
		<br>Les unités navales sont limitées à 10 par zone maritime dans le Pacifique et 5 sur les autres fronts. Les sous-marins ne sont pas compris dans cette limite.

		<h3>Le ravitaillement</h3>
		Les navires utilisent un système de <a class='lien' title='Aide' href='aide_jours.php' target='_blank' rel='noreferrer'>jours de mer</a> pour leurs déplacements.
		<br>Lors de leur ravitaillement au port ou en mer via un cargo, le navire reçoit tout le carburant et les munitions nécessaires à sa mission.
		<br>Certains petits navires (comme les cargos ou les patrouilleurs) n'ont pas besoin d'être ravitaillés pour pouvoir opérer.
		
		<!--<p class="lead">Ecran de fumée (camoufler les navires au port)</p>
		Annuler l'effet d'une reconnaissance ennemie.</p>

		<p class="lead">Effectuer des manoeuvres d'entrainement</p>
		Uniquement accessible dans votre port d'attache, permet d'augmenter l'expérience de vos navires jusqu'à un maximum de 50.</p>

		<p class="lead">Poser des filets anti-torpilles</p>
		Immobilise le navire en le protégeant contre les attaques à la torpille.
		<br>Action uniquement disponible au port.</p>

		<p class="lead">Quadriller la zone maritime</p>
		Action similaire à la revendication terrestre; les troupes de votre faction combattront avec l´avantage du terrain.</p>

		<p class="lead">Rentrer au port</p>
		Action possible lorsque vous approchez d'un port ami.</p>

		<p class="lead">Retirer les filets anti-torpilles</p>
		Permet à votre navire de se déplacer à nouveau, ne le protégeant plus contre les attaques à la torpille.
		<br>Action uniquement disponible au port.</p>

		<p class="lead">Se positionner au large</p>
		Action possible lorsque vous vous trouvez dans un port et que vous voulez rejoindre la zone maritime au large de ce port.
		<br>Si cette zone est occupée par des navires ennemis, tout ravitaillement au port est impossible.</p>

		<p class="lead">Saborder votre dernier navire et attendre les sauveteurs</p>
		Action de désespoir vous permettant de rejoindre votre port d'attache, si votre flotte a été anéantie.</p>

		<p class="lead">Surface / En Plongée</p>
		Actions réservées aux sous-marins.
		<br>En surface, la visibilité du sous-marin augmente ainsi que sa vitesse.
		<br>En plongée, la visibilité du sous-marin diminue ainsi que sa vitesse, mais la distance à laquelle il peut engager l'ennemi augmente.</p>-->

		<h3>Formations tactiques navales</h3>

		<p class="lead">Formation dispersée</p>
		<p>Une attaquee ennemie ne peut cibler qu'un seul navire à la fois (perte maximale par attaque : 1 navire).
		<br>Aucun effet contre les attaques aériennes.</p>

		<p class="lead">Formation d'escorte (équivalent de la ligne en terrestre)</p>
        <p>Augmente la protection des navires alliés situés dans la même zone face à un torpillage.
		<br>Les navires d'escorte riposteront avec leurs canons de DCA contre les attaques aériennes visant tout navire allié sur le même lieu.</p>

		<p class="lead">Formation d'évasion</p>
        <p>Un écran de fumée protégera le navire s'il est attaqué en surface.
		<br>Riposte impossible.
		<br>Aucun effet contre les attaques aériennes ou sous-marines.</p>

		<p class="lead">Formation d'appui (équivalent de l'appui en terrestre)</p>
        <p>Les armes de soutien des navires riposteront à toute attaque en surface sur des unités alliées sur le même lieu (à condition d'avoir la portée suffisante).</p>

		<p class="lead">Formation ASM</p>
        <p>Toute attaque sous-marine sera suivie d'une riposte ASM, à condition que les navires possèdent des charges de profondeur.
        <br>Les navires en position ASM limitent la portée de tir des sous-marins ennemis en plongée.</p>

		<p class="lead">Interdiction</p>
        <p>Tout navire ennemi sur la même zone que votre flotte ne peut quitter la zone s'il est plus lent que votre flotte en interdiction.
		<br>Seuls les navires pouvant se déplacer à 40km/h ou plus peuvent utiliser l'interdiction
		<br>Les navires dont la vitesse est égale ou supérieure à 40km/h peuvent ignorer l'interdiction si un navire allié génère un écran de fumée dans la zone où ils se trouvent</p>

		<p class="lead">Surface / En Plongée</p>
        <p>Actions réservées aux sous-marins.
		<br>En surface, la visibilité du sous-marin augmente ainsi que sa vitesse.
		<br>En plongée, la visibilité du sous-marin diminue ainsi que sa vitesse, mais la distance à laquelle il peut engager l'ennemi augmente.</p>

		<h3>Armement</h3>

		Les navires possèdent différents types d'armement:

		<ul><li>ASM / DCA</li>
		Grenades sous-marines ASM pour les navires possédant des charges de profondeur, dans le cas contraire il s'agit d'une DCA complémentaire spécialisée dans les attaques à basse altitude (0-1000m).
		<li>Principal</li>
		Armement principal de la plupart des navires, sauf pour les porte-avions où il s'agit d'une DCA complémentaire spécialisée dans les attaques à haute altitude (>4000m).
		<li>Mines</li>
		Armement passif permettant de miner une zone (voir description du mouilleur de mines).
		<li>Torpilles</li>
		Armement nécessaire au torpillage, cible uniquement d'autres navires.
		<li>DCA</li>
		DCA principale du navire, intervenant aux altitudes basses et moyennes (0-4000m)</ul>


		<h3>Actions des flottilles</h3>

		<p class="lead"> Reco</p>
		Permet de détecter la présence éventuelle de navires de surface ennemis dans la zone maritime où se trouve votre flotte
		<br>Localise les navires pour une éventuelle attaque aérienne</p>

		<p class="lead"> Traque ASM</p>
		Permet de décecter la présence éventuelle de sous-marins ennemis dans la zone maritime où se trouve votre flotte
		<br>Si un ou plusieurs sous-marins sont détectés, un grenadage automatique est opéré</p>

		<p class="lead"> Torpiller</p>
		Permet d'utiliser les torpilles du navire (ou du sous-marin) pour attaquer un navire adverse.
		<br>Un torpillage en surface pourra déclencher une riposte ennemie, en fonction des unités présentes, des formations adoptées et des couvertures mises en place.
		<br>Un torpillage en plongée (sous-marin uniquement) pourra déclencher une riposte ASM (grenadage), en fonction des unités présentes, des formations adoptées et des couvertures mises en place.
		<br>Le torpillage en plongée possède une portée supérieure. Si la cible n'est pas protégée par des navires ASM, la portée sera de 20km.
		<br>Si la météo n'est pas à l'orage, un navire endommagé à +50% peut être ciblé par une torpille sans autre prérequis.</p>

		<p class="lead"> Tirer</p>
		Fonctionne comme le bombardement terrestre, à savoir une salve réalisée à l'aide de l'arme de soutien, à la portée maximale.
		<br>Les formations adoptées et les éventuelles couvertures mises en place peuvent déclencher des ripostes de type "contre-batterie". 2 navires au maximum peuvent participer au tir de contre-batterie.
		<br>La contre-batterie interviendra si le navire ennemi en appui possède une portée supérieure ou égale à celle du navire attaquant en pleine mer ou supérieure ou égale à la moitié de la portée du navire attaquant en bordure d'une plage ou d'un port.</p>

		<p class="lead"> Bombarder</p>
		Fonctionne comme la destruction terrestre, à savoir une salve réalisée à l'aide de l'arme de soutien, en vue de détruire les infrastructures (et non les unités) ennemies.
		<br>Cette option n'est disponible que sur une zone portuaire.</p>

    	<h3>Unités navales</h3>

		<p class="lead"> Le cargo de haute mer</p>
		Identique au cargo, ce dernier possède cependant une autonomie accrue et une plus grande résistance lui permettant de naviguer sur les longues distances en eau profonde de l'océan.<br>Ce cargo ne peut naviguer en mer méditerranée.</p>

		<p class="lead"> Le dragueur de mines</p>
		Ce petit navire permet de déminer une zone maritime, comme le ferait une unité de génie pour une zone terrestre.
		<br>Les dragueurs de mines (et tout autre navire avec la caractéristique "démineur") peuvent retirer les mines à concurrence de 20% de la zone par action de déminage.</p>

		<!--<p class="lead"> Le navire de débarquement</p>
		Ce petit navire permet de transporter des troupes (unités terrestres contrôlées par d'autres joueurs) pour ensuite les débarquer dans un port ami ou sur une plage tenue par l'ennemi. Le débarquement est une action coûtant 40 CT.
		<br>Une fois débarquées, ces troupes sont placées sur la zone du lieu de destination en position retranchée, et leur total de CT est ramené à 0 pendant 24h.
		<br>Pour rappel : les unités de transport aérien peuvent effectuer le même type d'action via des parachutages.</p>-->

		<p class="lead"> Le mouilleur de mines</p>
		Ce navire permet de miner certaines zones maritimes, soit une zone côtière, soit un détroit ou un canal.
		<br>Le mouilleur de mines ne peut miner une zone que si aucune unité ennemies n'est présente sur cette zone en même temps que lui.
        <br>A chaque action de minage, 10% de la zone est minée et un marqueur est placé sur la zone pour signaler la présence de mines aux alliés.
        <img src="images/map/icone_mines_m.png" alt="Présence de mines dans la zone maritime">
		<br>Une fois posées, les mines peuvent endommager chaque navire de déplaçant depuis cette zone, peu importe la nation du navire.
		<br>Les zones pouvant être minées sont visibles par toutes les factions via la présence d'une icône adaptée.
        <img src="images/map/icone_detroit.png" alt="Zone maritime pouvant être minée">
		<br>Les dragueurs de mines (et tout autre navire avec la caractéristique "démineur") peuvent retirer les skill d'une zone à concurrence de 20% de la zone par action de déminage.</p>

		<h2>Différences avec le combat terrestre</h2>
		<ul><li>Les lieux maritimes ne comportent qu'une zone, où tous les navires se retrouvent et peuvent s'engager si leur vitesse et leur armement le leur permet.</li>
		<li>Les navires importants possèdent une "barre de vie" (en %) visible dans le menu principal de gestion des flottes. Chaque dégât reçu fait baisser cette valeur. Une fois arrivée à 0%, le navire est coulé.</li>
		<li>Des formations de combat spécifiques à la guerre navale remplacement les positions tactiques terrestres.</li>
		<!--<li>Les transmissions fonctionnent de la même manière, mis à part qu'il est évidemment impossible de demander un transport ou un ravitaillement ferroviaire depuis votre unité navale.</li>-->
		<li>Le ravitaillement des unités navales fonctionne différemment, elles possèdent une quantité de <a class='lien' title='Aide' href='aide_jours.php' target='_blank' rel='noreferrer'>jours de ravitaillement</a> pouvant être acquis dans les ports de leurs factions.</li></ul>
	</div>
</div>