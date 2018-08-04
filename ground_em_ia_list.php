<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Officier_Mer or $GHQ or $Admin or $Armee >0)
	{
		$today=getdate();
		if($OfficierEMID ==$Commandant or $GHQ or $Admin)
		{
             if($GHQ)
				$GHQ_Front_Titre='<th>Front</th>';
			else
                $Front_query="AND r.Front='$Front'";
			if($Type ==10)
				$cat_list="26,38";
			elseif($Type ==100)
				$cat_list="19,25,30";
			elseif($Type ==14)
				$cat_list="0,14,17,18,19,20,21,22,23,24";
			elseif($Type >0)
				$cat_list=$Type;
			else
				$cat_list="0,1,2,3,4,5,6,7,8,9,13,14,15,16,17,18,19,20,21,22,23,24,25,26,30,38";
			$menu_cat_list="<p><a class='btn btn-default' href='index.php?view=ground_em_ia_list'>Tout</a>";
			if($Type ==8)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_8'>Artillerie</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_8'>Artillerie</a>";
			if($Type ==9)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_9'>Anti-Tank</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_9'>Anti-Tank</a>";
			if($Type ==2)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_2'>Blindé Léger</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_2'>Blindé Léger</a>";
			if($Type ==3)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_3'>Blindé</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_3'>Blindé</a>";
			if($Type ==15)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_15'>DCA</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_15'>DCA</a>";
			if($Type ==5)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_5'>Infanterie</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_5'>Infanterie</a>";
			if($Type ==6)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_6'>Mitrailleuse</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_6'>Mitrailleuse</a>";
			if($Type ==13)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_13'>Train</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_13'>Train</a>";
			if($Type ==1)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_1'>Camion</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_1'>Camion</a>";
			if($Type ==21)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_21'>Porte-avions</a>";
			elseif($country ==2 or $country ==7 or $country ==9)
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_21'>Porte-avions</a>";
			if($Type ==20)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_20'>Cuirassé</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_20'>Cuirassé</a>";
			if($Type ==24)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_24'>Croiseur Ld</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_24'>Croiseur Ld</a>";
			if($Type ==23)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_23'>Croiseur Lg</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_23'>Croiseur Lg</a>";
			if($Type ==22)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_22'>Corvette</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_22'>Corvette</a>";
			if($Type ==17)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_17'>Sous-marin</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_17'>Sous-marin</a>";
			if($Type ==100)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_100'>Soutien</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_100'>Soutien</a>";
			if($Type ==10)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_10'>Cargo</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_10'>Cargo</a>";
			/*if($Type ==4)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_4'>Command</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_4'>Command</a>";*/
			if($Type ==89)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_89'>Réserve</a>";
			else
				$menu_cat_list.="<a class='btn btn-info' href='index.php?view=ground_em_ia_list_89'>Réserve</a>";
			if($Type ==95)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
			else
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
			if($Type ==91)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_91'>Mission</a>";
			elseif($Admin)
				$menu_cat_list.="<a class='btn btn-success' href='index.php?view=ground_em_ia_list_91'>Mission</a>";
			if($Type ==92)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_92'>Danger</a>";
			elseif($GHQ or $Premium)
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_92'>Danger</a>";
			if($Type ==93)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_93'>Repli</a>";
			elseif($GHQ or $Premium)
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_93'>Repli</a>";
			if($Type ==96)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_96'>Camo</a>";
			elseif($GHQ or $Premium)
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_96'>Camo</a>";
            if($Type ==88)
                $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_88'>Attente</a>";
            elseif($Admin)
                $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_88'>Attente</a>";
			if($Type ==94)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_94'>Réparer</a>";
			else
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_94'>Réparer</a>";
			if($Type ==98)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_98'>Demob</a>";
			else
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_98'>Demob</a>";
			if($Type ==90)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_90'>GHQ</a>";
			elseif($GHQ)
				$menu_cat_list.="<a class='btn btn-danger' href='index.php?view=ground_em_ia_list_90'>GHQ</a>";
			if($Type ==97)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_97'>Move</a>";
			elseif($GHQ)
				$menu_cat_list.="<a class='btn btn-danger' href='index.php?view=ground_em_ia_list_97'>Move</a>";
			$menu_cat_list.="</p>";
			/*if($Admin ==1)
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,l.Nom as Ville,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,c.Categorie,c.mobile,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND c.Categorie IN (".$cat_list.") ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			else*/
			if($Type ==98)
			{
				if($GHQ)
					$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
					FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Moral=0 AND r.NoEM=0 ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
				else
					$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
					FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND r.Vehicule_Nbr=0 AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==97)
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Dem_Front>0 ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			elseif($Type ==96)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités détectées par l'ennemi</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND r.Visible=1 AND r.NoEM=0 ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==95)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités en transit</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND r.Transit_Veh=5000 AND r.NoEM=0 ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==94)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités demandant une réparation</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND r.HP<c.HP AND c.mobile=5 AND r.NoEM=0 ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==93)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités demandant un repli afin d'éviter la destruction</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND c.mobile NOT IN(4,5) AND c.Categorie NOT IN(4) AND r.Vehicule_Nbr >0 AND r.NoEM=0
				AND CASE WHEN c.Categorie IN(5,6) THEN r.Vehicule_Nbr <25 ELSE r.Vehicule_Nbr <5 END ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==92)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités vulnérables</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND r.Position IN(0,4,6,8,9) AND r.Vehicule_Nbr >0 AND r.NoEM=0 ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==90)
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND r.NoEM=1 ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			elseif($Type ==89)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités ne faisant pas partie d'une division ou d'une armée</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND r.Division IS NULL AND r.Vehicule_ID NOT IN(424,5124) ORDER BY r.Front ASC,l.Nom ASC";
			}
            elseif($Type ==88)
                $query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Move=0 AND r.Move_time > NOW() - INTERVAL 1 DAY ORDER BY Move_time ASC,r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			elseif($Type ==91)
			{
				if($Front ==99)
				{
					if($country ==1 or $country ==8)
						$Front=1;
					elseif($country ==6)
						$Front=2;
					elseif($country ==2)
						$Front=3;
					elseif($country ==7)
						$Front=0;
				}
				$con=dbconnecti();
				$result2=mysqli_query($con,"SELECT l.ID,l.Nom,l.Latitude,l.Longitude,l.Zone FROM Pays as p,Lieu as l WHERE p.Co_Lieu_Mission=l.ID AND p.Pays_ID='$country' AND p.Front='$Front'");
				mysqli_close($con);
				if($result2)
				{
					while($Data=mysqli_fetch_array($result2,MYSQLI_ASSOC)) 
					{
						$Co_Lieu_Mission=$Data['ID'];
						$Co_Lieu_Mission_Nom=$Data['Nom'];
						$Co_Lieu_Mission_Lat=$Data['Latitude'];
						$Co_Lieu_Mission_Long=$Data['Longitude'];
					}
					mysqli_free_result($result2);
				}
				$menu_cat_list.="<p class='lead'>Unités dont l'autonomie permet un déplacement vers <b>".$Co_Lieu_Mission_Nom."</b>. Le déplacement ferroviaire, les noeuds routiers, la revendication et la présence éventuelle d'ennemis ne sont pas pris en compte.</p>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Fret,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,r.Autonomie,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==14)
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Autonomie,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' ".$Front_query." AND c.Categorie IN (".$cat_list.") AND r.NoEM=0 ORDER BY c.Type DESC,l.Nom ASC,r.Division,r.Bataillon ASC";
			elseif($GHQ)
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Fret,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Autonomie,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND c.Categorie IN (".$cat_list.") ORDER BY r.Front ASC,l.Nom ASC,r.Division,r.Bataillon ASC";
			else
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Fret,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Autonomie,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND c.Categorie IN (".$cat_list.") AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
		}
		elseif($OfficierEMID ==$Adjoint_Terre or $Armee >0)
		{
			if($Armee >0)
				$Armee_query=",Division as d WHERE d.ID=r.Division AND d.Armee='$Armee' AND";
			else
				$Armee_query=" WHERE r.Division IS NULL AND";
			if($Type >0)
				$cat_list=$Type;
			else
				$cat_list="0,1,2,3,4,5,6,7,8,9,15,16";
			$menu_cat_list="<p><a class='btn btn-default' href='index.php?view=ground_em_ia_list'>Tout</a>";
			if($Type ==8)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_8'>Artillerie</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_8'>Artillerie</a>";
			if($Type ==9)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_9'>Anti-Tank</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_9'>Anti-Tank</a>";
			if($Type ==2)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_2'>Blindé Léger</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_2'>Blindé Léger</a>";
			if($Type ==3)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_3'>Blindé</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_3'>Blindé</a>";
			if($Type ==15)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_15'>DCA</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_15'>DCA</a>";
			if($Type ==5)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_5'>Infanterie</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_5'>Infanterie</a>";
			if($Type ==6)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_6'>Mitrailleuse</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_6'>Mitrailleuse</a>";
			/*if($Type ==4)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_4'>Command</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_4'>Command</a>";*/
			if($OfficierEMID ==$Adjoint_Terre){
                if($Type ==89)
                    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_89'>Réserve</a>";
                else
                    $menu_cat_list.="<a class='btn btn-info' href='index.php?view=ground_em_ia_list_89'>Réserve</a>";
            }
			if($Type ==95)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
			else
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
			if($Type ==92)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_92'>Danger</a>";
			elseif($Premium)
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_92'>Danger</a>";
			if($Type ==96)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_96'>Camo</a>";
			elseif($Premium)
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_96'>Camo</a>";
			$menu_cat_list.="</p>";
			if($Type ==96)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités détectées par l'ennemi</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c".$Armee_query." r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND r.Visible=1 AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==95)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités en transit</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c".$Armee_query." r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND c.mobile IN (1,2,3,6,7) AND r.Transit_Veh=5000 AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==93)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités demandant un repli afin d'éviter la destruction</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c".$Armee_query." r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND r.Vehicule_Nbr >0 AND r.NoEM=0
				AND CASE WHEN c.Categorie IN(5,6) THEN r.Vehicule_Nbr <25 ELSE r.Vehicule_Nbr <5 END ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==92)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités sous le feu</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c".$Armee_query." r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND r.Position IN(0,4,6,8,9) AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==89)
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND r.Division=0 AND r.Vehicule_ID NOT IN(424,5124) ORDER BY l.Nom ASC";
			else
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Front,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c".$Armee_query." r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND c.mobile IN (1,2,3,6,7) AND c.Categorie IN (".$cat_list.") AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
		}
		elseif($OfficierEMID ==$Officier_Mer)
		{
			if($Type ==10)
				$cat_list="26,38";
			elseif($Type ==100)
				$cat_list="19,25,30";
			elseif($Type >0)
				$cat_list=$Type;
			else
				$cat_list="0,14,17,18,19,20,21,22,23,24";
			$menu_cat_list="<p><a class='btn btn-default' href='index.php?view=ground_em_ia_list'>Tout</a>";
			if($Type ==21)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_21'>Porte-avions</a>";
			elseif($country ==2 or $country ==7 or $country ==9)
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_21'>Porte-avions</a>";
			if($Type ==20)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_20'>Cuirassé</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_20'>Cuirassé</a>";
			if($Type ==24)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_24'>Croiseur Ld</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_24'>Croiseur Ld</a>";
			if($Type ==23)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_23'>Croiseur Lg</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_23'>Croiseur Lg</a>";
			if($Type ==22)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_22'>Corvette</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_22'>Corvette</a>";
			if($Type ==17)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_17'>Sous-marin</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_17'>Sous-marin</a>";
			if($Type ==100)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_100'>Soutien</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_100'>Soutien</a>";
			if($Type ==10)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_10'>Cargo</a>";
			else
				$menu_cat_list.="<a class='btn btn-default' href='index.php?view=ground_em_ia_list_10'>Cargo</a>";
			if($Type ==95)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
			else
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
			if($Type ==96)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_96'>Camo</a>";
			elseif($Premium)
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_96'>Camo</a>";
			if($Type ==94)
				$menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_94'>Réparer</a>";
			else
				$menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_94'>Réparer</a>";
			$menu_cat_list.="</p>";
			if($Type ==95)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités en transit</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Fret,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,r.Atk_Eni,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND r.Transit_Veh=5000 AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==96)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités détectées par l'ennemi</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Fret,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,r.Atk_Eni,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND r.Visible=1 AND c.mobile=5 AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			elseif($Type ==94)
			{
				$Intro_txt="<div class='alert alert-danger'>Unités demandant une réparation</div>";
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Fret,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,r.Atk_Eni,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND r.HP<c.HP AND c.mobile=5 AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
			}
			else
				$query3="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Pays,r.Front,r.Fret,r.Experience,r.Skill,r.Matos,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,r.Atk_Eni,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,r.Autonomie,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
				FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND c.mobile=5 AND c.Categorie IN (".$cat_list.") AND r.NoEM=0 ORDER BY l.Nom ASC,r.Division,r.Bataillon ASC";
		}
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result3=mysqli_query($con,$query3) or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : gemial-regia-414');
        $result_s=mysqli_query($con,"SELECT ID,Nom,Infos FROM gnmh_aubedesaiglesnet1.Skills_r WHERE Rang<5");
        $result_m=mysqli_query($con,"SELECT ID,Nom,Infos FROM gnmh_aubedesaiglesnet1.Skills_m WHERE Rang>0");
        //mysqli_close($con);
        if($result_s)
        {
            while($data=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
            {
                $Skills[$data['ID']]='<b>'.$data['Nom'].'</b><br>'.$data['Infos'];
            }
            mysqli_free_result($result_s);
        }
        if($result_m)
        {
            while($datam=mysqli_fetch_array($result_m,MYSQLI_ASSOC))
            {
                $Reg_matos[$datam['ID']]='<b>'.$datam['Nom'].'</b><br>'.$datam['Infos'];
            }
            mysqli_free_result($result_m);
        }
		$EM_units='';
		$dca_em=0;
		$at_em=0;
		$art_em=0;
		$blg_em=0;
		$bl_em=0;
		$mg_em=0;
		$inf_em=0;
		$cr_em=0;
		$cl_em=0;
		$ca_em=0;
		$bb_em=0;
		$pa_em=0;
		$sm_em=0;
		$nv_em=0;
		$tr_em=0;
		if($result3)
		{
			while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$Doit=false;
				$Skill_txt=false;
				if($Type ==91)
				{
					$Dist_u=GetDistance(0,0,$Co_Lieu_Mission_Long,$Co_Lieu_Mission_Lat,$data3['Longitude'],$data3['Latitude']);
					$Autonomie_Veh=Get_LandSpeed($data3['Fuel'],$data3['mobile'],$data3['Zone'],0,$data3['Type'],0,0,0,$Front);
					if($data3['mobile'] ==3)
					{
						if($data3['Skill'] ==23)$Autonomie_Veh*=1.1;
						elseif($data3['Skill'] ==114)$Autonomie_Veh*=1.2;
						elseif($data3['Skill'] ==115)$Autonomie_Veh*=1.3;
						elseif($data3['Skill'] ==116)$Autonomie_Veh*=1.4;
					}
					if($data3['Matos'] ==14)$Autonomie_Veh*=1.5;
					elseif($data3['Matos'] ==15)$Autonomie_Veh*=1.1;
                    elseif($data3['Matos'] ==28)$Autonomie_Veh*=2;
                    elseif($data3['Matos'] ==30)$Autonomie_Veh*=1.5;
					if($Dist_u[0] <=$Autonomie_Veh)$Doit=true;
				}
				else
					$Doit=true;
				if($Doit)
				{
					$Moral_Chk=$data3['Moral'];
					if($GHQ)
					{
						$Moral_Chk=1;
						$Front_unit=GetFrontByCoord(0,$data3['Latitude'],$data3['Longitude']);
						$Front_unit_txt=GetFront($Front_unit);
						if($Type >0)
							$Front_nbr[$Front_unit] +=1;
					}
					if($data3['Categorie'] ==14 or $data3['Categorie'] ==30 or $data3['Categorie'] ==38 or $data3['Type'] ==14)
						$nv_em+=1;
					elseif($data3['Categorie'] ==15)
						$dca_em +=1;
					elseif($data3['Categorie'] ==9)
						$at_em +=1;
					elseif($data3['Categorie'] ==8)
						$art_em +=1;
					elseif($data3['Categorie'] ==6)
						$mg_em +=1;
					elseif($data3['Categorie'] ==5)
						$inf_em +=1;
					elseif($data3['Categorie'] ==2)
						$blg_em +=1;
					elseif($data3['Categorie'] ==3)
						$bl_em +=1;
					elseif($data3['Categorie'] ==1)
						$tr_em +=1;
					elseif($data3['Categorie'] ==17)
						$sm_em +=1;
					elseif($data3['Categorie'] ==20)
						$bb_em +=1;
					elseif($data3['Categorie'] ==21)
						$pa_em +=1;
					elseif($data3['Categorie'] ==22)
						$cr_em +=1;
					elseif($data3['Categorie'] ==23)
						$cl_em +=1;
					elseif($data3['Categorie'] ==24)
						$ca_em +=1;
					if($data3['Vehicule_ID'] <5000 or ($data3['Vehicule_ID'] >4999 and $Moral_Chk >0))
					{
                        if(!$Armee and $data3['Division']){
                            $btn_color_ordres='warning';
                        }
                        else{
                            $btn_color_ordres='default';
                        }
						if($data3['Move'])
							$Move=Afficher_Image('images/led_red.png','','',10);
						else
							$Move=Afficher_Image('images/led_green.png','','',10);
						if(!$data3['Visible'])
							$Camo_txt="<img class='img-flex-icon' src='images/camouflage.png' title='Camouflé'>";
						else
							$Camo_txt=false;
						if($data3['Bomb_IA'])$Camo_txt.="<a href='#' class='popup'><img src='images/map/noia.png'><span>Ne peut plus être ciblé par les bombardements tactiques IA jusque au prochain passage de date</span></a>";
						if($data3['Ravit'])$Camo_txt.="<a href='#' class='popup'><img src='images/map/air_ravit.png'><span>Ravitaillé par air</span></a>";
						if($data3['mobile'] ==5)
						{
							if($data3['HP'])
								$per_c=round(100/($data3['HP_max']/$data3['HP']));
							else
								$per_c=0;
							if($per_c >99)
								$HP_per="<span class='label label-success'>".$per_c."%</span>";
							elseif($per_c <1)
								$HP_per="<span class='label label-danger'>".$per_c."%</span>";
							else
								$HP_per="<span class='label label-warning'>".$per_c."%</span>";
							if($data3['Categorie'] ==20 or $data3['Categorie'] ==21 or $data3['Categorie'] ==22 or $data3['Categorie'] ==23 or $data3['Categorie'] ==24 or $data3['Categorie'] ==17)
							{
								if($data3['Autonomie'])
									$HP_per.="<span class='label label-warning'><a class='lien' title='Aide' href='help/aide_jours.php' target='_blank'>".$data3['Autonomie']." Jours</a></span>";
								else
									$HP_per.="<span class='label label-danger'><a class='lien' title='Aide' href='help/aide_jours.php' target='_blank'>".$data3['Autonomie']." Jours</a></span>";
							}
						}
						else
							$HP_per=false;
						if($GHQ)
						{
							$Front_ghq=GetFront($data3['Front'])."</td><td>";
							if(!$data3['Moral'])
								$Camo_txt.=" ".Afficher_Image('images/calimero.png','',"Démoralisé",10);
						}
						if($data3['Vehicule_ID'] ==5124 and $data3['Fret'])
						{
							if(!$data3['Fret'])
								$Camo_txt.="<br>Vide";
							elseif($data3['Fret'] ==1001)
								$Camo_txt.="<br>250000L Diesel";
							elseif($data3['Fret'] ==1087)
								$Camo_txt.="<br>250000L Essence 87";
							elseif($data3['Fret'] ==1100)
								$Camo_txt.="<br>250000L Essence 100";
							elseif($data3['Fret'] ==1)
								$Camo_txt.="<br>Troupes";
							elseif($data3['Fret'] ==930)
								$Camo_txt.="<br>10000 Fusées";
							elseif($data3['Fret'] ==80)
								$Camo_txt.="<br>5000 Rockets";
							elseif($data3['Fret'] ==200)
								$Camo_txt.="<br>Troupes IA";
							elseif($data3['Fret'] ==300)
								$Camo_txt.="<br>1250 Charges";
							elseif($data3['Fret'] ==400)
								$Camo_txt.="<br>1250 Mines";
							elseif($data3['Fret'] ==800)
								$Camo_txt.="<br>500 Torpilles";
							elseif($data3['Fret'] ==888)
								$Camo_txt.="<br>Lend-Lease";
							elseif($data3['Fret'] ==1200)
								$Camo_txt.="<br>Obus de 200mm";
							elseif($data3['Fret'] ==9050 or $data3['Fret'] ==9125 or $data3['Fret'] ==9250 or $data3['Fret'] ==9500)
								$Camo_txt.="<br>Bombes de ".substr($data3['Fret'],1)."kg";
							elseif($data3['Fret'] > 9999)
								$Camo_txt.="<br>Bombes de ".substr($data3['Fret'],0,-1)."kg";
							else
								$Camo_txt.="<br>Obus de ".$data3['Fret']."mm";
						}
						//$date_diff=date_diff($data3['Atk_time'],$today);
						/*$bla=$today-$data3['Atk_time'];
						$date_diff.=$bla;*/
						if($today['mday'] >$data3['Jour']+1)
							$Combat_flag=false;
						elseif($today['mon'] >$data3['Mois'])
							$Combat_flag=false;
                        elseif($today['year'] >$data3['Year_a'])
                            $Combat_flag=false;
						elseif($today['mday']!=$data3['Jour'] and $today['hours']>=$data3['Heure'])
							$Combat_flag=false;
						else
							$Combat_flag=true;
						if($today['mday'] >$data3['Jour_m']+1)
							$Move_flag=false;
						elseif($today['mon'] >$data3['Mois_m'])
							$Move_flag=false;
                        elseif($today['year'] >$data3['Year_m'])
                            $Move_flag=false;
						elseif($today['mday']!=$data3['Jour_m'] and $today['hours']>=$data3['Heure_m'])
							$Move_flag=false;
						else
							$Move_flag=true;
						if($data3['Position'] ==12)
							$Action="<span class='label label-danger'>En Vol</span>";
						elseif($data3['Atk'] ==1 or $Combat_flag)
						{
							$Action="<span class='text-danger'>En Combat<br>jusque ".$data3['Heure']."</span>";
							if(!$data3['Move'] and !$data3['Atk'])
							{
							    if($data3['Vehicule_Nbr'] >1)
							        $help_txt_fuir='Cette action permettra à l\'unité d\'agir, mais réduira ses effectifs à 1';
							    elseif($data3['Vehicule_ID'] >5000)
                                    $help_txt_fuir='Cette action permettra à l\'unité d\'agir, mais réduira sa robustesse de 50%';
								$Action.="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$data3['ID']."'><input type='hidden' name='reset' value='9'><input type='hidden' name='Max' value='".$data3['Vehicule_Nbr']."'>
								<a href='#' class='popup'><input type='submit' value='Fuir' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'>
								<span>".$help_txt_fuir."</span></a></form>";
								if($data3['Vehicule_ID']<5000 and $data3['Vehicule_Nbr'] >0 and $data3['Atk_Eni'])
								{
									$Action.="<form action='index.php?view=ground_pl' method='post'>
												<input type='hidden' name='CT' value='0'>
												<input type='hidden' name='distance' value='500'>
												<input type='hidden' name='Action' value='".$data3['Atk_Eni']."_0'>
												<input type='hidden' name='Veh' value='".$data3['Vehicule_ID']."'>
												<input type='hidden' name='Reg' value='".$data3['ID']."'>
												<input type='hidden' name='Pass' value='".$data3['Vehicule_Nbr']."'>
									<a href='#' class='popup'><input type='submit' value='Riposter' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
									<span>Cette action permettra de tenter de contre-attaquer l'unité qui vous a engagé</span></a></form>";
								}
							}
						}
						elseif($data3['mobile'] !=5 and ($data3['Move'] ==1 or $Move_flag))
						{
							if(!$data3['Heure_m'])$data3['Heure_m']="9h00";
							$Action="<span class='text-danger'>Mouvement<br>jusque ".$data3['Heure_m']."</span>";
						}
						else
							$Action="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data3['ID']."'>
							<input type='Submit' value='Ordres' class='btn btn-sm btn-".$btn_color_ordres."' onclick='this.disabled=true;this.form.submit();'></form>";
						if($data3['Experience'] >249)
							$Exp_txt="<span class='label label-success'>".$data3['Experience']."XP</span>";
						elseif($data3['Experience'] >49)
							$Exp_txt="<span class='label label-primary'>".$data3['Experience']."XP</span>";
						elseif($data3['Experience'] >1)
							$Exp_txt="<span class='label label-warning'>".$data3['Experience']."XP</span>";
						else
							$Exp_txt="<span class='label label-danger'>".$data3['Experience']."XP</span>";
						if($data3['Skill'])
							$Skill_txt="<a href='#' class='popup'><img class='img-flex-icon' src='images/skills/skillo".$data3['Skill'].".png'><span>".$Skills[$data3['Skill']]."</span></a>";
						else
							$Skill_txt='';
						if($data3['Matos'])
                            $Skill_txt.="<a href='#' class='popup'><img class='img-flex-icon' src='images/skills/skille".$data3['Matos'].".png'><span>".$Reg_matos[$data3['Matos']]."</span></a>";
						/*if($data3['Bataillon'])
							$Bataillon=$data3['Bataillon']."e";
						else
							$Bataillon="";*/
                        if($Admin and $Type >0){
                            $admin_menu = '<th>Admin</th>';
                            $admin_col = '<td><div class="caret" data-toggle="collapse" data-target="#clp-'.$data3['ID'].'"></div>
                                            <div class="collapse" id="clp-'.$data3['ID'].'">
                                                <form action="ground_em_ia_admin.php" method="post">
                                                    <input type="hidden" name="id" value="'.$data3['ID'].'">
                                                    <input type="hidden" name="mode" value="1">
                                                    <input class="btn btn-sm btn-info" type="submit" value="Hide">
                                                </form>
                                                <form action="ground_em_ia_admin.php" method="post">
                                                    <input type="hidden" name="id" value="'.$data3['ID'].'">
                                                    <input type="hidden" name="mode" value="2">
                                                    <input class="btn btn-sm btn-info" type="submit" value="See">
                                                </form>
                                                <form action="ground_em_ia_admin.php" method="post">
                                                    <input type="hidden" name="id" value="'.$data3['ID'].'">
                                                    <input type="hidden" name="mode" value="3">
                                                    <input class="btn btn-sm btn-info" type="submit" value="Free">
                                                </form>
                                            </div>
                                        </td>';
                        }
                        $Division_txt = Afficher_Image('images/div/div'.$data3['Division'].'.png','images/'.$country.'div.png','',0);
                        $EM_units.="<tr>".$admin_col."<td>".$Front_ghq.$Division_txt."</td><td>".$data3['ID']."e</td><td>".$Action."</td>
						<td>".$data3['Vehicule_Nbr']." ".GetVehiculeIcon($data3['Vehicule_ID'],$data3['Pays'],0,0,$Front).$Exp_txt.$HP_per.$Skill_txt.$Camo_txt."</td><td>".$Move." ".$data3['Ville']."</td><td>".GetPosGr($data3['Position']).' '.GetPlace($data3['Placement'])."</td></tr>";
					}
				}
			}
			mysqli_free_result($result3);
		}
		if($GHQ)
		{
			for($x=0;$x<6;$x++)
			{
				if(!$Front_nbr[$x])
					$Front_nbr[$x]=0;
			}
			$GHQ_Rep_txt="<h2>Répartition par fronts</h2>
			<span class='label label-primary'>Ouest</span><span class='badge'>".$Front_nbr[0]."</span>
			<span class='label label-primary'>Est</span><span class='badge'>".$Front_nbr[1]."</span>
			<span class='label label-primary'>Nord</span><span class='badge'>".$Front_nbr[4]."</span>
			<span class='label label-primary'>Med</span><span class='badge'>".$Front_nbr[2]."</span>
			<span class='label label-primary'>Pacifique</span><span class='badge'>".$Front_nbr[3]."</span>
			<span class='label label-primary'>Arctique</span><span class='badge'>".$Front_nbr[5]."</span>";
			//Création Cie EM
			if(!$Type and $Credits >0)
			{
				$Quota1=GetQuota($country,$Front,$Date_Campagne,1);
				$Quota2=GetQuota($country,$Front,$Date_Campagne,2);
				$Quota3=GetQuota($country,$Front,$Date_Campagne,3);
				$Quota5=GetQuota($country,$Front,$Date_Campagne,5);
				$Quota6=GetQuota($country,$Front,$Date_Campagne,6);
				$Quota8=GetQuota($country,$Front,$Date_Campagne,8);
				$Quota9=GetQuota($country,$Front,$Date_Campagne,9);
				$Quota15=GetQuota($country,$Front,$Date_Campagne,15);
				$Quota17=GetQuota($country,$Front,$Date_Campagne,17);
				$Quota20=GetQuota($country,$Front,$Date_Campagne,20);
				$Quota21=GetQuota($country,$Front,$Date_Campagne,21);
				$Quota22=GetQuota($country,$Front,$Date_Campagne,22);
				$Quota23=GetQuota($country,$Front,$Date_Campagne,23);
				$Quota24=GetQuota($country,$Front,$Date_Campagne,24);
				$Quota100=GetQuota($country,$Front,$Date_Campagne,100);
				if($Quota8 >$art_em)
					$cat_em.="<option value='8'>Artillerie (".$art_em."/".$Quota8.")</option>";
				elseif($Quota8)
					$cat_em.="<option value='8' disabled>Artillerie (".$art_em."/".$Quota8.")</option>";
				if($Quota2 >$blg_em)
					$cat_em.="<option value='2'>Blindés légers (".$blg_em."/".$Quota2.")</option>";
				elseif($Quota2)
					$cat_em.="<option value='2' disabled>Blindés légers (".$blg_em."/".$Quota2.")</option>";
				if($Quota3 >$bl_em)
					$cat_em.="<option value='3'>Blindés (".$bl_em."/".$Quota3.")</option>";
				elseif($Quota3)
					$cat_em.="<option value='3' disabled>Blindés (".$bl_em."/".$Quota3.")</option>";
				if($Quota1 >$tr_em)
					$cat_em.="<option value='1'>Camions (".$tr_em."/".$Quota1.")</option>";
				elseif($Quota1)
					$cat_em.="<option value='1' disabled>Camions (".$tr_em."/".$Quota1.")</option>";
				if($Quota9 >$at_em)
					$cat_em.="<option value='9'>Canon AT (".$at_em."/".$Quota9.")</option>";
				elseif($Quota9)
					$cat_em.="<option value='9' disabled>Canon AT (".$at_em."/".$Quota9.")</option>";
				if($Quota15 >$dca_em)
					$cat_em.="<option value='15'>Canon DCA (".$dca_em."/".$Quota15.")</option>";
				elseif($Quota15)
					$cat_em.="<option value='15' disabled>Canon DCA (".$dca_em."/".$Quota15.")</option>";
				if($Quota5 >$inf_em)
					$cat_em.="<option value='5'>Infanterie (".$inf_em."/".$Quota5.")</option>";
				elseif($Quota5)
					$cat_em.="<option value='5' disabled>Infanterie (".$inf_em."/".$Quota5.")</option>";
				if($Quota6 >$mg_em)
					$cat_em.="<option value='6'>Mitrailleuse (".$mg_em."/".$Quota6.")</option>";
				elseif($Quota6)
					$cat_em.="<option value='6' disabled>Mitrailleuse (".$mg_em."/".$Quota6.")</option>";
				if($Quota100 >$nv_em)
					$cat_em.="<option value='100'>Navires de soutien (".$nv_em."/".$Quota100.")</option>";
				elseif($Quota100)
					$cat_em.="<option value='100' disabled>Navires de soutien (".$nv_em."/".$Quota100.")</option>";
				if($Quota22 >$cr_em)
					$cat_em.="<option value='22'>Corvettes (".$cr_em."/".$Quota22.")</option>";
				elseif($Quota22)
					$cat_em.="<option value='22' disabled>Corvettes (".$cr_em."/".$Quota22.")</option>";
				if($Quota23 >$cl_em)
					$cat_em.="<option value='23'>Croiseurs légers (".$cl_em."/".$Quota23.")</option>";
				elseif($Quota23)
					$cat_em.="<option value='23' disabled>Croiseurs légers (".$cl_em."/".$Quota23.")</option>";
				if($Quota24 >$ca_em)
					$cat_em.="<option value='24'>Croiseurs lourds (".$ca_em."/".$Quota24.")</option>";
				elseif($Quota24)
					$cat_em.="<option value='24' disabled>Croiseurs lourds (".$ca_em."/".$Quota24.")</option>";
				if($Quota20 >$bb_em)
					$cat_em.="<option value='20'>Cuirassés (".$bb_em."/".$Quota20.")</option>";
				elseif($Quota20)
					$cat_em.="<option value='20' disabled>Cuirassés (".$bb_em."/".$Quota20.")</option>";
				if($Quota21 >$pa_em)
					$cat_em.="<option value='21'>Porte-avions (".$pa_em."/".$Quota21.")</option>";
				elseif($Quota21)
					$cat_em.="<option value='21' disabled>Porte-avions (".$pa_em."/".$Quota21.")</option>";
				if($Quota17 >$sm_em)
					$cat_em.="<option value='17'>Sous-marins (".$sm_em."/".$Quota17.")</option>";
				elseif($Quota17)
					$cat_em.="<option value='17' disabled>Sous-marins (".$sm_em."/".$Quota17.")</option>";
				echo "<hr><h2>Création de Compagnie EM</h2>
				<form action='index.php?view=ground_em_ia_create' method='post'>
				<select name='Cat' class='form-control' style='width: 200px'>".$cat_em."</select>
				<input type='Submit' value='Créer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'><a href='#' class='popup'><img src='images/help.png'><span>Unités indépendantes pouvant être attachées à une division ou placées en réserve du front. Une fois créées, ces unités apparaissent dans la liste des Compagnies EM. Un quota est imposé par nation et par front.</span></a></form><hr>";
			}
		}
		if($GHQ and $Type ==98)
			echo "<form action='index.php?view=ghq_remob' method='post'><input type='Submit' value='Remonter le moral' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form><hr>";
		elseif($GHQ)
			echo "<div class='row'><div class='col-md-2'><a class='btn btn-primary' href='index.php?view=em_vehs'>Véhicules en service</a></div><div class='col-md-2'><form action='index.php?view=ground_attrition' method='post'><input type='Submit' value='Attrition des unités' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div><hr>";
		elseif($Armee)
			echo "<div class='row'><div class='col-md-2'><a class='btn btn-primary' href='index.php?view=ground_em_div'>Armée</a></div><div class='col-md-2'><form action='index.php?view=ground_attrition' method='post'><input type='Submit' value='Attrition des unités' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div><hr>";
		else
			echo "<div class='row'><div class='col-md-2'><form action='index.php?view=ground_attrition' method='post'><input type='Submit' value='Attrition des unités' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form></div></div><hr>";
		if($EM_units)
		{
			//<div style='overflow:auto; height: 640px;'>
			echo '<h2>Liste des Bataillons</h2>
				'.$menu_cat_list.$Intro_txt.$GHQ_Rep_txt.'
				<div><table class="table table-condensed"><thead><tr>'.$admin_menu.$GHQ_Front_Titre.'
					<th>Division</th>
					<th>Bataillon</th>
					<th>Action</th>
					<th>Troupes</th>
					<th>Lieu <a href="#" class="popup"><img src="images/help.png"><span>Rouge si le déplacement quotidien a déjà été effectué, vert sinon</span></a></th>
					<th>Position</th>
				</tr></thead>'.$EM_units.'</table></div>';
		}
		else
			echo '<h2>Liste des Bataillons</h2>'.$menu_cat_list;
	}
	elseif($OfficierEMID ==$Officier_Log)
	{
		$con=dbconnecti();
		$result3=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,l.Nom as Ville,r.Placement,r.Position,r.Division,r.Bataillon,r.Pays,r.Move
		FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Front='$Front' AND r.Division IS NULL AND (r.Vehicule_ID IN (424,5001,5124) OR c.Categorie=1) ORDER BY l.Nom ASC");
		mysqli_close($con);
		if($result3)
		{
			echo "<h2>Liste des Compagnies EM</h2>
				<div style='overflow:auto; height: 640px;'><table class='table table-condensed'><thead><tr>
					<th>Compagnie</th>
					<th>Troupes</th>
					<th>Lieu</th>
					<th>Position</th>
					<th colspan='2'>Action</th>
				</tr></thead>";
			while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$Division_d=$data3['Division'];
				if($data3['Move'])
					$Move=Afficher_Image('images/led_red.png','','',10);
				else
					$Move=Afficher_Image('images/led_green.png','','',10);
				$Action="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data3['ID']."'>
				<input type='Submit' value='Ordres' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				echo "<tr><td>".$data3['ID']."e</td><td>".$data3['Vehicule_Nbr']." ".GetVehiculeIcon($data3['Vehicule_ID'],$data3['Pays'],0,0,$Front)."</td><td>".$Move." ".$data3['Ville']."</td><td>".GetPosGr($data3['Position']).' '.GetPlace($data3['Placement'])."</td><td>".$Action."</td></tr>";
			}
			mysqli_free_result($result3);
		}
		echo '</table></div>';
	}
	elseif($Commandant >0 or $Officier_Mer >0 or $Adjoint_Terre >0 or $Officier_Log >0)
	{
		if($Commandant >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Avancement,Photo,Photo_Premium FROM Officier_em WHERE ID='$Commandant'");
			mysqli_close($con);
			if($result)
			{
				while($datao=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Nomo=$datao['Nom'];
					$Photoo=$datao['Photo'];
					$Photo_Premium_Cdt=$datao['Photo_Premium'];
					$Gradeo=GetAvancement($datao['Avancement'],$country,0,1);
				}
				mysqli_free_result($result);
			}
			if($Photo_Premium_Cdt)
				$CO_txt=Afficher_Image("uploads/Officier/".$Commandant."_photo.jpg","images/persos/general".$country.$Photoo.".jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
			else
				$CO_txt=Afficher_Image("images/persos/general".$country.$Photoo.".jpg","images/persos/general".$country."1.jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
		}
		else
			$CO_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect stratégique du jeu,<br>vous pouvez postuler pour cette fonction <a href='index.php?view=em_actus' class='lien'>ici</a>";
		if($Adjoint_Terre >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Avancement,Photo,Photo_Premium FROM Officier_em WHERE ID='$Adjoint_Terre'");
			mysqli_close($con);
			if($result)
			{
				while($datao=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Nomo=$datao['Nom'];
					$Photoo=$datao['Photo'];
					$Photo_Premium_Adj=$datao['Photo_Premium'];
					$Gradeo=GetAvancement($datao['Avancement'],$country,0,1);
				}
				mysqli_free_result($result);
			}
			if($Photo_Premium_Adj)
				$AO_txt=Afficher_Image("uploads/Officier/".$Adjoint_Terre."_photo.jpg","images/persos/general".$country.$Photoo.".jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
			else
				$AO_txt=Afficher_Image("images/persos/general".$country.$Photoo.".jpg","images/persos/general".$country."1.jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
		}
		else
			$AO_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect stratégique du jeu,<br>vous pouvez postuler pour cette fonction <a href='index.php?view=em_actus' class='lien'>ici</a>";
		if($Officier_Mer >0)
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Avancement,Photo,Photo_Premium FROM Officier_em WHERE ID='$Officier_Mer'");
			mysqli_close($con);
			if($result)
			{
				while($datao=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Nomo=$datao['Nom'];
					$Photoo=$datao['Photo'];
					$Photo_Premium_Mer=$datao['Photo_Premium'];
					$Gradeo=GetAvancement($datao['Avancement'],$country,0,1);
				}
				mysqli_free_result($result);
			}
			if($Photo_Premium_Mer)
				$MO_txt=Afficher_Image("uploads/Officier/".$Officier_Mer."_photo.jpg","images/persos/general".$country.$Photoo.".jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
			else
				$MO_txt=Afficher_Image("images/persos/general".$country.$Photoo.".jpg","images/persos/general".$country."1.jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
		}
		else
			$MO_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect stratégique du jeu,<br>vous pouvez postuler pour cette fonction <a href='index.php?view=em_actus' class='lien'>ici</a>";
		if($Officier_Log >0)
		{
			$con=dbconnecti();
			$result2=mysqli_query($con,"SELECT Nom,Avancement,Photo,Photo_Premium FROM Officier_em WHERE ID='$Officier_Log'");
			mysqli_close($con);
			if($result2)
			{
				while($datal=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Noml=$datal['Nom'];
					$Photol=$datal['Photo'];
					$Photo_Premium_Log=$datal['Photo_Premium'];
					$Gradel=GetAvancement($datal['Avancement'],$country,0,1);
				}
				mysqli_free_result($result2);
			}
			if($Photo_Premium_Log)
				$OL_txt=Afficher_Image("uploads/Officier/".$Officier_Log."_photo.jpg","images/persos/general".$country.$Photoo.".jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
			else
				$OL_txt=Afficher_Image("images/persos/general".$country.$Photol.".jpg","images/persos/general".$country."1.jpg",$Noml,50)."<h3>".$Gradel[0]." ".$Noml."</h3>";
		}
		else
			$OL_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect logistique du jeu,<br>vous pouvez postuler pour cette fonction <a href='index.php?view=em_actus' class='lien'>ici</a>";
		if($Officier_Rens >0)
		{
			$con=dbconnecti();
			$result2=mysqli_query($con,"SELECT Nom,Avancement,Photo,Photo_Premium FROM Officier_em WHERE ID='$Officier_Rens'");
			mysqli_close($con);
			if($result2)
			{
				while($datar=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Nomr=$datar['Nom'];
					$Photor=$datar['Photo'];
					$Photo_Premium_Rens=$datar['Photo_Premium'];
					$Grader=GetAvancement($datar['Avancement'],$country,0,1);
				}
				mysqli_free_result($result2);
			}
			if($Photo_Premium_Rens)
				$OR_txt=Afficher_Image("uploads/Officier/".$Officier_Rens."_photo.jpg","images/persos/general".$country.$Photoo.".jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
			else
				$OR_txt=Afficher_Image("images/persos/general".$country.$Photor.".jpg","images/persos/general".$country."1.jpg",$Nomr,50)."<h3>".$Grader[0]." ".$Nomr."</h3>";
		}
		else
			$OR_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect logistique du jeu,<br>vous pouvez créer un officier d'état-major sur la page de connexion du jeu et postuler <a href='index.php?view=em_actus' class='lien'>ici</a>";
		if($Officier_EM >0)
		{
			$con=dbconnecti();
			$result2=mysqli_query($con,"SELECT Nom,Avancement,Photo,Photo_Premium FROM Officier_em WHERE ID='$Officier_EM'");
			mysqli_close($con);
			if($result2)
			{
				while($datar=mysqli_fetch_array($result2,MYSQLI_ASSOC))
				{
					$Nomr=$datar['Nom'];
					$Photor=$datar['Photo'];
					$Photo_Premium_EM=$datar['Photo_Premium'];
					$Grader=GetAvancement($datar['Avancement'],$country,0,1);
				}
				mysqli_free_result($result2);
			}
			if($Photo_Premium_EM)
				$OI_txt=Afficher_Image("uploads/Officier/".$Officier_EM."_photo.jpg","images/persos/general".$country.$Photoo.".jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3>";
			else
				$OI_txt=Afficher_Image("images/persos/general".$country.$Photor.".jpg","images/persos/general".$country."1.jpg",$Nomr,50)."<h3>".$Grader[0]." ".$Nomr."</h3>";
		}
		else
			$OI_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect gestion des infrastructures du jeu,<br>vous pouvez créer un officier d'état-major sur la page de connexion du jeu et postuler <a href='index.php?view=em_actus' class='lien'>ici</a>";
		echo "<h1>Etat-Major</h1><table class='table table-condensed'><thead><tr><th>Commandant en Chef</th><th>Officier Terrestre</th><th>Officier Maritime</th><th>Officier Logistique</th><th>Officier Renseignement</th><th>Officier Infrastructures</th></tr></thead><tr><td>".$CO_txt."</td><td>".$AO_txt."</td><td>".$MO_txt."</td><td>".$OL_txt."</td><td>".$OR_txt."</td><td>".$OI_txt."</td></tr></table>";
		if($OfficierEMID)
		{
			echo "<h2>Commandants d'armées</h2>			
			<div class='alert alert-warning'>Vous pouvez également postuler à une fonction de <a href='#' class='popup'><b>Commandant d'armée</b><span>Le commandant d'armée donne les ordres quotidiens aux unités que lui attribue son commandant de front, tels que les déplacements et les actions offensives. Veiller à l'approvisionnement de ses troupes et à la communication avec l'état-major est recommandé.</span></a> si vous voulez prendre le contrôle d'une armée (troupes terrestres) ou d'une flotte (troupes navales).<br>La nomination sera validée ou non par le Commandant en Chef du front ou le Planificateur Stratégique.
			<br>En cas de changement de front, veillez à demander votre mutation via le profil de votre officier <b>avant</b> de postuler.</div>
			<form action='index.php?view=postuler_armee' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$country."'>
			<input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='20'><input type='Submit' value='Postuler' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
		}
	}
	elseif($OfficierEMID)
	{
		echo "<h2>Etat-Major</h2>
		<div class='alert alert-danger'>Aucun officier n'occupe de poste à l'état-major sur ce front.<br>Si vous êtes intéressé par l'aspect stratégique du jeu, vous pouvez postuler pour cette fonction <a href='index.php?view=em_actus' class='lien'>ici</a></div>
		<div class='alert alert-warning'>Vous pouvez également postuler à une fonction de <a href='#' class='popup'><b>Commandant d'armée</b><span>Le commandant d'armée donne les ordres quotidiens aux unités que lui attribue son commandant de front, tels que les déplacements et les actions offensives. Veiller à l'approvisionnement de ses troupes et à la communication avec l'état-major est recommandé.</span></a> si vous voulez prendre le contrôle d'une armée (troupes terrestres) ou d'une flotte (troupes navales).<br>La nomination sera validée ou non par le Commandant en Chef du front ou le Planificateur Stratégique.
		<br>En cas de changement de front, veillez à demander votre mutation via le profil de votre officier <b>avant</b> de postuler.</div>
		<form action='index.php?view=postuler_armee' method='post'><input type='hidden' name='off' value='".$OfficierEMID."'><input type='hidden' name='country' value='".$country."'>
		<input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='poste' value='20'><input type='Submit' value='Postuler' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';