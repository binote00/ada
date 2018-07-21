<?php
/*require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{*/
    include_once '../jfv_include.inc.php';
    $country=$_SESSION['country'];
    if(!$country)$country=4;
    include_once __DIR__ . '/../view/menu_infos.php';
    $con=dbconnecti(1);
	$result_s=mysqli_query($con,"SELECT *,DATE_FORMAT(Service,'%d-%m-%Y') as Service FROM Skills_m WHERE Rang >0 ORDER BY Categorie ASC,Nom ASC");
	mysqli_close($con);
	if($result_s)
	{
		while($datas=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
		{
			$Cat_txt=false;
			if(!$datas['Infos'])$datas['Infos']='N/A';
			if($datas['Categorie'] ==1)
				$Cat_txt.='Terrestre';
			elseif($datas['Categorie'] ==2)
				$Cat_txt.='Maritime';
			else
				$Cat_txt.='Mixte';
			if(!$datas['Service'] or $datas['Service']=="00-00-0000")
				$Dispo_txt='Début';
			else
				$Dispo_txt=$datas['Service'];
			//$skill_txt.="<tr><td><img src='/images/skills/skille".$datas['ID'].".png'><br>".$datas['Nom']."</td><td>".$Cat_txt."</td><td>".$Dispo_txt."</td><td>".$datas['Infos']."</td></tr>";
            $skill_txt.="
            <div class='row'>
                <div class='col-xs-2'><img src='images/skills/skille".$datas['ID'].".png'><br>".$datas['Nom']."</div>
                <div class='col-xs-2'>".$Cat_txt."</div>
                <div class='col-xs-2'>".$Dispo_txt."</div>
                <div class='col-xs-6'>".$datas['Infos']."</div>
            </div>";
		}
		mysqli_free_result($result_s);
	}
	$titre='Equipement des unités terrestres et navales';
	/*$mes="<div class='alert alert-warning'>Pour bénéficier de ces équipements, l'unité terrestre doit se trouver sur une usine contrôlée par sa faction.<br>L'unité navale doit se trouver dans un port principal ou une base navale contrôlé par sa faction.</div>
	<div class='row'><div class='col-md-4'>".Afficher_Image('images/scenes/skills_m.jpg','','',100)."</div><div class='col-md-8'><div class='text-left' style='overflow:auto; height:640px;'>
	<table class='table'><thead><tr><th>Equipement</th><th>Catégorie</th><th>Disponibilité</th><th>Description</th></tr></thead>".$skill_txt."</table></div></div></div>";*/
    $mes="<div class='alert alert-warning'>Pour bénéficier de ces équipements, l'unité terrestre doit se trouver sur une usine contrôlée par sa faction.<br>L'unité navale doit se trouver dans un port principal ou une base navale contrôlé par sa faction.</div>
        <div class='row'>
            <div class='col-lg-4 col-md-12'>".Afficher_Image('images/scenes/skills_m.jpg','','',100)."</div>
            <div class='col-lg-8 col-md-12'>
                <div class='row'>
                    <div class='col-xs-2'><h3>Equipement</h3></div>
                    <div class='col-xs-2'><h3>Catégorie</h3></div>
                    <div class='col-xs-2'><h3>Disponibilité</h3></div>
                    <div class='col-xs-6'><h3>Description</h3></div>
                </div>
                <div class='text-left striped' style='overflow-y:auto; overflow-x:hidden; height:640px; width:97%;'>
                    ".$skill_txt."
                </div>
            </div>
        </div>";
	include_once '../default.php';
/*}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';*/