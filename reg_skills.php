<?
/*require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{*/
    include_once('./jfv_include.inc.php');
	$country=$_SESSION['country'];
    if(!$country)$country=4;
    include_once('./menu_infos.php');
    $tri='Rang';
    $cat='';
    if(isset($_GET['tri'])){
        $white_list=array('Rang','Nom');
        if(in_array($_GET['tri'],$white_list))
            $tri=Insec($_GET['tri']);
    }
    if(isset($_GET['cat'])){
        $white_list=array('AT','Art','Art_mob','DCA','Inf','MG','Mob','Tank','Nav','Sub');
        if(in_array($_GET['cat'],$white_list)){
            if($_GET['cat'] == 'Tank')
                $cat=' AND (Tank=1 OR Mob=1)';
            else
                $cat=' AND '.Insec($_GET['cat']).'=1';
        }
    }
    if(!isset($_GET['o']) || $_GET['o'] != 'ASC'){
        $order='ASC';
    }else{
        $order='DESC';
    }
    $con=dbconnecti(1);
    $result_s=mysqli_query($con,"SELECT * FROM Skills_r WHERE Rang<5".$cat." ORDER BY $tri $order");
    mysqli_close($con);
	if($result_s)
	{
		while($datas=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
		{
			$Cat_txt=false;
			if(!$datas['Infos'])$datas['Infos']='N/A';
			if($datas['AT'])$Cat_txt.='<br>Anti-tank';
			if($datas['Art'])$Cat_txt.='<br>Artillerie';
			elseif($datas['Art_mob'])$Cat_txt.='<br>Artillerie mobile';
			if($datas['DCA'])$Cat_txt.='<br>DCA';
			if($datas['Inf'])$Cat_txt.='<br>Infanterie';
			if($datas['MG'])$Cat_txt.='<br>Mitrailleuse';
			if($datas['Mob'])$Cat_txt.='<br>Motorisé<br>Blindé';
			elseif($datas['Tank'])$Cat_txt.='<br>Blindé';
			if($datas['Nav'])$Cat_txt.='<br>Navire';
			if($datas['Sub'])$Cat_txt.='<br>Sous-marin';
			//$skill_txt.="<tr><td><img src='/images/skills/skillo".$datas['ID'].".png'><br>".$datas['Nom']."</td><td>".$Cat_txt."</td><td>".$datas['Rang']."</td><td>".$datas['Infos']."</td></tr>";
			$skill_txt.="
            <div class='row'>
                <div class='col-xs-2'><img src='/images/skills/skillo".$datas['ID'].".png'><br>".$datas['Nom']."</div>
                <div class='col-xs-3'>".$Cat_txt."</div>
                <div class='col-xs-1'>".$datas['Rang']."</div>
                <div class='col-xs-6'>".$datas['Infos']."</div>
            </div>";
		}
		mysqli_free_result($result_s);
	}
	$titre='Compétences des unités terrestres';
	/*$mes="<div class='row'><div class='col-md-4'>".Afficher_Image('images/scenes/skills_r.jpg','','',75)."</div>
    <div class='col-md-8'><div class='text-left' style='overflow:auto; height:640px;'>
	<table class='table'><thead><tr><th>Compétence</th><th>Catégorie</th><th>Rang</th><th>Description</th></tr></thead>".$skill_txt."</table></div></div></div>";*/
    $mes="<div class='row'>
        <div class='col-md-4 col-sm-12'>".Afficher_Image('images/scenes/skills_r.jpg','','',75)."</div>
        <div class='col-md-8 col-sm-12'>
            <div class='row'>
                <div class='col-xs-2'><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."'><h3>Compétence</h3></a></div>
                <div class='col-xs-3'>
                    <a data-toggle='collapse' class='lien' href='#cat-clp'><h3>Catégorie</h3></a>
                    <div class='collapse' id='cat-clp'>
                        <ul>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=AT'>Anti-tank</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=Art'>Artillerie</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=Art_mob'>Artillerie mobile</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=DCA'>DCA</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=Inf'>Infanterie</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=MG'>Mitrailleuse</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=Mob'>Motorisé</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=Tank'>Blindé</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=Nav'>Navire</a></li>
                            <li><a class='lien' href='index.php?view=reg_skills&tri=Nom&o=".$order."&cat=Sub'>Sous-marin</a></li>
                        </ul>                       
                    </div>
                </div>
                <div class='col-xs-1'><a class='lien' href='index.php?view=reg_skills&tri=Rang&o=".$order."'><h3>Rang</h3></a></div>
                <div class='col-xs-6'><h3>Description</h3></div>
            </div>
            <div class='text-left striped' style='overflow-y:auto; overflow-x:hidden; height:640px; width:97%;'>
	            ".$skill_txt."
	        </div>
	    </div>
	</div>";
	include_once('./default.php');
/*}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';*/