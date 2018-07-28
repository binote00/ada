<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0 xor $OfficierID >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_combat.inc.php');
	$country=$_SESSION['country'];
	$Reg=Insec($_POST['Reg']);
	/*if($OfficierID >0)
	{
		$con=dbconnecti();
		$resulto=mysqli_query($con,"SELECT Front,Credits,Trait FROM Officier WHERE ID='$OfficierID'");
		//mysqli_close($con);
		if($resulta)
		{
			while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
			{
				$Front=$datao['Front'];
				$Credits=$datao['Credits'];
				$Trait_o=$datao['Trait'];
			}
			mysqli_free_result($resulto);
		}
	}*/
    $con=dbconnecti();
	/*if($OfficierEMID >0)
	{*/
		$resulto=mysqli_query($con,"SELECT Front,Credits,Trait FROM Officier_em WHERE ID='$OfficierEMID'");
		if($resulta)
		{
			while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
			{
				$Front=$datao['Front'];
				$Credits=$datao['Credits'];
				$Trait=$datao['Trait'];
			}
			mysqli_free_result($resulto);
		}
	//}
	$Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
	$resultr=mysqli_query($con,"SELECT Lieu_ID,Pays,Experience,Vehicule_Nbr,Position,Placement,Muns,Skill FROM Regiment_IA WHERE ID='$Reg'");
	if($resultr)
	{
		while($datar=mysqli_fetch_array($resultr,MYSQLI_ASSOC))
		{
			$Cible=$datar['Lieu_ID'];
			$Pays=$datar['Pays'];
			$Reg_exp=$datar['Experience'];
			$Vehicule_Nbr=$datar['Vehicule_Nbr'];
			$Position=$datar['Position'];
			$Placement=$datar['Placement'];
			$Muns=$datar['Muns'];
			$Skill=$datar['Skill'];
		}
		mysqli_free_result($resultr);
	}
	$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$Pays'"),0);
	$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Position<>25 AND r.Vehicule_Nbr >0"),0);
	$result=mysqli_query($con,"SELECT l.Nom,l.Zone,l.Pont_Ori,l.Pont,l.Radar_Ori,l.Radar,l.Port_Ori,l.Port,l.NoeudF_Ori,l.NoeudF,l.Garnison,l.Fortification,l.Recce,l.Flag,l.Map,p.Faction 
    FROM Lieu as l,Pays as p WHERE l.ID='$Cible' AND l.Flag=p.ID");
	$resultm=mysqli_query($con,"SELECT Qty,Detect_Axe,Detect_Allie FROM Mines WHERE Lieu_ID='$Cible' AND Zone='$Placement'");
	if($resultm)
	{
		while($datam=mysqli_fetch_array($resultm,MYSQLI_ASSOC))
		{
			$Mines=$datam['Qty'];
			$Detect_Axe=$datam['Detect_Axe'];
			$Detect_Allie=$datam['Detect_Allie'];
		}
		mysqli_free_result($resultm);
		if($Faction ==2 and $Detect_Allie)
			$Detect_mines=true;
		elseif($Faction ==1 and $Detect_Axe)
			$Detect_mines=true;
	}
	if($result)
	{
		$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
        $Cible_nom=$data['Nom'];
        $Zone=$data['Zone'];
        $Pont_Ori=$data['Pont_Ori'];
        $Pont=$data['Pont'];
        $Port_Ori=$data['Port_Ori'];
        $Port=$data['Port'];
        $Radar_Ori=$data['Radar_Ori'];
        $Radar=$data['Radar'];
        $NoeudF_Ori=$data['NoeudF_Ori'];
        $NoeudF=$data['NoeudF'];
        $Garnison=$data['Garnison'];
        $Fortification=$data['Fortification'];
        $Map=$data['Map'];
        $Recce=$data['Recce'];
        $Flag=$data['Flag'];
        $Faction_Flag=$data['Faction'];
		mysqli_free_result($result);
		unset($data);
	}
	mysqli_close($con);
	if($Credits >=4 and $Faction_Flag ==$Faction and $Fortification <50 and $Placement ==0 and !$Enis)
		$mes.="<form action='index.php?view=ground_fort' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Fortifier' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	else
		$help_txt.="<img src='images/CT4.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Fortifier</b> possible sur la caserne d'un lieu revendiqué par sa faction à condition que les fortifications soient inférieures au niveau 5 et qu'aucune troupe ennemie ne soit présente<br>";
	if($Credits >=4 and $Pont >0 and $Placement ==5 and ($Recce or $Faction_Flag ==$Faction))
		$mes.="<form action='index.php?view=ground_deponter' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Détruire le pont' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	else
		$help_txt.="<img src='images/CT4.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Détruire le pont</b> possible sur le fleuve d'un lieu reconnu ou revendiqué par sa faction<br>";
	if($Credits >=4 and $Pont_Ori ==100 and $Pont <100 and $Placement ==5 and ($Recce or $Faction_Flag ==$Faction))
		$mes.="<form action='index.php?view=ground_ponter' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Ponter' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	else
		$help_txt.="<img src='images/CT4.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Ponter</b> possible sur le fleuve d'un lieu reconnu ou revendiqué par sa faction<br>";
	if($Credits >=4 and $NoeudF >0 and $Placement ==3 and $Faction_Flag ==$Faction)
		$mes.="<form action='index.php?view=ground_saboter_gare' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Saboter la gare' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	else
		$help_txt.="<img src='images/CT4.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Saboter la gare</b> possible sur la gare d'un lieu revendiqué par sa faction<br>";
    if($Credits >=8 and $NoeudF_Ori ==100 and $NoeudF <100 and $Placement ==3 and $Faction_Flag ==$Faction)
        $mes.="<form action='index.php?view=ground_repare_gare' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Réparer la gare' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
    else
        $help_txt.="<img src='images/CT8.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Réparer la gare</b> possible sur la gare d'un lieu revendiqué par sa faction<br>";
	if($Credits >=4 and $Port >0 and $Placement ==4 and $Faction_Flag ==$Faction)
		$mes.="<form action='index.php?view=ground_saboter_port' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Saboter le port' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	else
		$help_txt.="<img src='images/CT4.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Saboter le port</b> possible sur le port d'un lieu revendiqué par sa faction<br>";
    if($Credits >=8 and $Port_Ori ==100 and $Port <100 and $Placement ==4 and $Faction_Flag ==$Faction)
        $mes.="<form action='index.php?view=ground_repare_port' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Réparer le port' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
    else
        $help_txt.="<img src='images/CT8.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Réparer le port</b> possible sur le port d'un lieu revendiqué par sa faction<br>";
	if($Credits >=4 and $Faction_Flag ==$Faction and ($Zone ==0 or $Zone ==1 or $Zone ==7 or $Zone ==8) and !$Enis)
		$mes.="<form action='index.php?view=ground_mine' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Miner' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	else
		$help_txt.="<img src='images/CT4.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Miner</b> possible sur un lieu (de type <img src='images/zone0.jpg'><img src='images/zone1.jpg'><img src='images/zone7.jpg'><img src='images/zone8.jpg'>) revendiqué par sa faction à condition qu'aucune troupe ennemie ne soit présente<br>";
	if($Credits >=8 and ($Zone ==0 or $Zone ==1 or $Zone ==7 or $Zone ==8) and !$Enis and $Recce)
		$mes.="<form action='index.php?view=ground_demine' method='post'>
		<input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Cible' value='".$Cible."'>
		<img src='images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Déminer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	else
		$help_txt.="<img src='images/CT8.png' title='Credits Temps nécessaires pour exécuter cette action'><b>Déminer</b> possible sur un lieu (de type <img src='images/zone0.jpg'><img src='images/zone1.jpg'><img src='images/zone7.jpg'><img src='images/zone8.jpg'>) reconnu à condition qu'aucune troupe ennemie ne soit présente<br>";
	$titre='Génie';
	if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
		$img='images/lieu/lieu'.$Cible.'.jpg';
	else
	{
		if($Zone ==8)
		{
			if($Map ==0 or $Map ==1)
				$img='images/dune_sea.jpg';
			elseif($Map ==2 or $Map ==3)
				$img='images/desert_airfield.jpg';
		}
		elseif($Zone ==9)
		{
			if($Map ==0 or $Map ==1)
				$img='images/jungle.jpg';
			elseif($Map ==2 or $Map ==3)
				$img='images/pacific_airfield.jpg';
			elseif($Map ==8)
				$img='images/jungle_port.jpg';
		}
		elseif($Pays_Ori ==8 and $Map ==1 or $Map==3)
			$img='images/lieu/russian_town.jpg';
		if(!$img)$img='images/lieu/objectif'.$Map.'.jpg';
	}
	$img="<img src='".$img."'><h2>".$Cible_nom."</h2>";
	if($help_txt)
		$mes.="<div class='alert alert-info'>".$help_txt."</div>";
	if($OfficierEMID >0)		
		$menu="<hr><a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour au menu</a>
		<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='submit' value='Retour unité' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
	elseif($OfficierID >0)
		$menu="<hr><a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
	include_once('./default.php');
}