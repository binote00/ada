<?php
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$ID=Insec($_GET['infra']);
	if(is_numeric($ID))
	{
		$con=dbconnecti();
		$ID=mysqli_real_escape_string($con,$ID);
		$result=mysqli_query($con,"SELECT Nom FROM Cible WHERE ID='$ID'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result)) 
			{			
				$titre=$data['Nom'].' <img src="images/vehicules/vehicule'.$ID.'.gif">';
			}
			mysqli_free_result($result);
		}
		$img='<img src="images/cibles/cibles'.$ID.'.jpg">';
		switch($ID)
		{
			case 1:
				$mes="Le hangar contient les stocks de munitions et de carburant d'un aérodrome. La destruction d'un hangar fait baisser la quantité de munitions et/ou de carburant d'une unité aérienne occupant l'aérodrome.";
			break;
			case 2:
				$mes="Infrastructure permettant à plusieurs unités aériennes d'occuper efficacement un aérodrome. La tour permet également de fournir des informations aux pilotes en vol.
						<br>Une tour en bon état facilite le décollage et l'atterrissage pour tous les pilotes décollant de l'aérodrome.
						<br>Si la tour est détruite, les pompiers de l'aérodrome ne pourront plus intervenir, de même que les escadrilles ne pourront plus être déplacées depuis ou vers cette base.
						<br>L'état-major aérien peut réparer la tour d'un aérodrome en utilisant des ouvriers ou des bons de guerre.
						<br>Les officiers commandants les escadrilles occupantes peuvent réparer la tour via les crédits mutualisés.";
			break;
			case 3:
				$mes="L'entrepôt contient les stocks de munitions d'un dépôt. La destruction d'un entrepôt fait baisser la quantité de munitions disponible dans le dépôt du lieu associé.";
			break;
			case 4:
				$mes="Infrastructure nécessaire à la production des avions et des véhicules, ainsi qu'au fonctionnement des raffineries.
						<br>Chaque modèle d'avion ou de véhicule possède une ou plusieurs usines de production associées. Si une de ces usines est détruite, la production de ce modèle s'arrête, le rendant indisponible. Les différents états-majors ont accès aux informations de production.
						<br>Les raffineries permettent de produire du carburant nécessaire aux avions, navires et véhicules. Si l'usine du lieu où se trouve la raffinerie est détruite, la raffinerie arrêtera de produire du carburant.
						<br>Le planificateur stratégique est responsable de la gestion des usines, de leur production à l'éventuelle réparation en utilisant des ouvriers ou des bons de guerre.";
			break;
			case 5:
				$mes="Infrastructure nécessaire à la production des avions et des véhicules, ainsi qu'au fonctionnement des raffineries.
						<br>L'usine principale doit être détruite pour que l'usine puisse perdre totalement sa valeur de production.
						<br>Chaque modèle d'avion ou de véhicule possède une ou plusieurs usines de production associées. Si une de ces usines est détruite, la production de ce modèle s'arrête, le rendant indisponible. Les différents états-majors ont accès aux informations de production.
						<br>Les raffineries permettent de produire du carburant nécessaire aux avions, navires et véhicules. Si l'usine du lieu où se trouve la raffinerie est détruite, la raffinerie arrêtera de produire du carburant.
						<br>Le planificateur stratégique est responsable de la gestion des usines, de leur production à l'éventuelle réparation en utilisant des ouvriers ou des bons de guerre.";
			break;
			case 7:
				$mes="La caserne est l'infrastructure représentant la nation contrôlant un lieu ainsi que le niveau de fortification. Elle contient la garnison et permet également aux troupes alliées présentes d'accéder à certaines options d'assistance.
					<br>La destruction du bâtiment principal permet de réduire le niveau de fortification du lieu, et donc la défense d'une éventuelle garnison <a href='aide_garnison.php' target='_blank' title='Aide'><img src='images/help.png'></a>
					<br>Lorsque la fortication est au maximum (niveau 100) et que la garnison comporte plus de 50 hommes, les unités ennemies présentent sur le lieu ne peuvent se déplacer, soumise au feu de l'artillerie des forts. Faire baisser le niveau de fortifications par un bombardement permet d'annuler cet effet.";
			break;
			case 9:
				$mes="La gare joue un rôle essentiel dans l'efficacité du ravitaillement.
						<br>Une gare en bon état (supérieur à 10%) est nécessaire pour tout déplacement ferroviaire depuis ou vers un lieu.
						<br>Les différents états-majors peuvent réparer une gare en utilisant des ouvriers ou des bons de guerre.
						<br>Les troupes du génie terrestre peuvent également saboter une gare.";
			break;
			case 10:
				$mes="Le pont permet aux unités terrestres de traverser un fleuve rapidement. 
                        <br>Si le pont est détruit, les unités ennemies ne peuvent se rendre sur les autres zones du lieu.
						<br>Un pont en bon état est nécessaire pour pouvoir atteindre la caserne d'un lieu, et ainsi le revendiquer.
						<br>Les troupes du génie terrestre peuvent réparer ou saboter un pont.";
			break;
			case 11:
				$mes="Cette infrastructure contient les stocks de carburant d'un dépôt. La destruction des réserves de carburant fait baisser la quantité de carburant disponible dans le dépôt du lieu associé.";
			break;
			case 12:
				$mes="Infrastructure essentielle du port, les docks doivent être détruits pour que le port puisse perdre sa valeur opérationnelle.
						<br>Le port joue un rôle essentiel dans l'efficacité du ravitaillement, particulièrement dans le ravitaillement outre-mer.
						<br>Les différents états-majors peuvent réparer un port en utilisant des ouvriers ou des bons de guerre.
						<br>Les troupes du génie terrestre peuvent également saboter un port.";
			break;
			case 15:
				$mes="Les radars terrestres améliorent les capacités de détection des appareils lors des vols sur le front sur lequel ils sont situés, à condition d'être en état de marche (infrastructure supérieure à 50%).
						<br>Le radar terrestre augmente les chances d'interception en vol pour la chasse et la chasse de nuit (joueur comme IA).
						<br>Lors d'un vol dans des conditions météo optimales, un avion est capable de détecter un autre avion volant jusqu'à 3000m sous lui et jusqu'à 2000m au-dessus de lui. Chaque radar en état de marche permet d'ajouter 50m dans les deux directions, avec un maximum de 1000m.
						<br>S'il existe au moins 10 radars terrestres en bon état dans une zone du front, cela permet à l'état-major aérien de repérer les raids ennemis (via le menu Etat-Major - Alerte Radar) entre le moment où le pilote ennemi décolle et le moment où il atterrit, sauf si ce dernier vole au ras du sol ou au ras de l'eau.
						<br>L'état-major aérien peut réparer les radars en utilisant des ouvriers ou des bons de guerre.";
			break;
			case 16:
				$mes="La DCA de site intervient pour défendre toute infrastructure située sur un lieu (usine, port, gare, caserne, pont, etc...) jusqu'à une altitude de 10.000m
						<br>A partir du niveau 5, la DCA de site est flanquée de projecteurs offrant un bonus pour toucher les avions de nuit.
						<br>L'état-major aérien peut améliorer ou réparer la DCA de site d'un lieu en utilisant des ouvriers ou des bons de guerre.";
			break;
			case 66:
				$mes="Infrastructure indispensable à toute opération aérienne.
						<br>La piste est partagée par toutes les unités aériennes occupant le lieu.
						<br>Les officiers commandants les escadrilles occupantes peuvent réparer ou saboter la piste.
						<br>L'état-major aérien peut réparer et agrandir une piste en utilisant des ouvriers ou des bons de guerre.
						<br>La taille maximale des pistes dépend de la situation géographique et du terrain. Plus le terrain est plat et dégagé, plus la piste pourra être longue.";
			break;
		}
		$mes='<fieldset>'.$mes.'</fieldset>';
		include_once('./default_blank.php');
	}
	else
		echo 'Tsss';
}
?>