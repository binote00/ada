<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 xor $OfficierEMID >0)
{
    $country=$_SESSION['country'];
    include_once('./jfv_include.inc.php');
    include_once('./jfv_ground.inc.php');
    include_once('./jfv_txt.inc.php');
    /*if($OfficierID >0)
    {
        $con=dbconnecti();
        $result=mysqli_query($con,"SELECT Front,Nom FROM Officier WHERE ID='$OfficierID'");
        mysqli_close($con);
        if($result)
        {
            while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
            {
                $Front=$data['Front'];
                $Off_Nom=$data['Nom'];
            }
            mysqli_free_result($result);
        }
    }
    elseif($OfficierEMID >0)
    {*/
    $con=dbconnecti();
    $result=mysqli_query($con,"SELECT Front,Armee FROM Officier_em WHERE ID='$OfficierEMID'");
    if($result){
        while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $Front=$data['Front'];
            $Armee=$data['Armee'];
        }
        mysqli_free_result($result);
    }
    $result2=mysqli_query($con,"SELECT Commandant,Adjoint_Terre,Officier_Mer,Officier_Log,Officier_Rens,Officier_EM FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
    if($result2){
        while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
            $Commandant=$data['Commandant'];
            $Adjoint_Terre=$data['Adjoint_Terre'];
            $Officier_Mer=$data['Officier_Mer'];
            $Officier_Log=$data['Officier_Log'];
            $Officier_Rens=$data['Officier_Rens'];
            $Officier_EM=$data['Officier_EM'];
        }
        mysqli_free_result($result2);
    }
    if($Tab =='EM'){
        if($_SESSION['msg_em'])
            $Alert = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_em'].'</div>';
        if($_SESSION['msg_em_red'])
            $Alert = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_em_red'].'</div>';
        $_SESSION['msg_em'] = false;
        $_SESSION['msg_em_red'] = false;
        if($OfficierEMID >0 and $Front !=12 and ($OfficierEMID ==$Commandant or $Admin))
        {
            $bat_list_pretxt='<div>';
            $Coord=GetCoord($Front,$country);
            $Lat_base_min=$Coord[0];
            $Lat_base_max=$Coord[1];
            $Long_base_min=$Coord[2];
            $Long_base_max=$Coord[3];
            if($Front ==99)
            {
                $querydiv="SELECT d.ID,d.Nom,d.Cdt,d.atk,d.def,d.Front,d.Armee,d.Maritime,l.Nom as Ville FROM Division as d,Lieu as l WHERE d.Base=l.ID AND d.Pays='$country' AND d.Active=1 ORDER BY d.Cdt DESC,d.Nom ASC";
                $queryarmee="SELECT a.ID,a.Nom,a.Cdt,a.Front,a.Maritime,a.Objectif,a.limite_ouest,a.limite_nord,a.limite_est,a.limite_sud,l.Nom as Ville FROM Armee as a,Lieu as l WHERE a.Base=l.ID AND a.Pays='$country' AND a.Active=1 ORDER BY a.Maritime ASC,a.Cdt DESC,a.Nom ASC";
                //$querybat="SELECT o.ID,o.Nom,o.Front,o.Division,o.Avancement,(SELECT COUNT(*) FROM Regiment_IA as r WHERE r.Bataillon=o.ID) as Regs,DATE_FORMAT(o.Credits_Date,'%d-%m-%Y') as Activite FROM Officier as o WHERE o.Pays='$country' AND o.Actif=0 ORDER BY o.Credits_Date DESC,o.Nom ASC";
                //$querymut="SELECT ID,Nom,Pays,Avancement,Reputation,Mutation FROM Officier WHERE Pays='$country' AND Front IN (0,1,2,3,4,5) AND Mutation >0 ORDER BY Avancement DESC";
                if($Admin)
                    $querymut2="SELECT ID,Nom,Pays,Avancement,Reputation,Mutation,Postuler FROM Officier_em WHERE Pays='$country' AND Mutation >0 ORDER BY Avancement DESC";
            }
            else
            {
                $querydiv="SELECT d.ID,d.Nom,d.Cdt,d.atk,d.def,d.Armee,d.Maritime,l.Nom as Ville FROM Division as d,Lieu as l WHERE d.Base=l.ID AND d.Pays='$country' AND d.Front='$Front' AND d.Active=1 ORDER BY d.Cdt DESC,d.Nom ASC";
                $queryarmee="SELECT a.ID,a.Nom,a.Cdt,a.Front,a.Maritime,a.Objectif,a.limite_ouest,a.limite_nord,a.limite_est,a.limite_sud,l.Nom as Ville FROM Armee as a,Lieu as l WHERE a.Base=l.ID AND a.Pays='$country' AND a.Front='$Front' AND a.Active=1 ORDER BY a.Maritime ASC,a.Cdt DESC,a.Nom ASC";
                /*$querybat="SELECT o.ID,o.Nom,o.Front,o.Division,o.Avancement,(SELECT COUNT(*) FROM Regiment_IA as r WHERE r.Bataillon=o.ID) as Regs,DATE_FORMAT(o.Credits_Date,'%d-%m-%Y') as Activite FROM Officier as o WHERE o.Pays='$country' AND o.Front='$Front' AND o.Actif=0 AND
                o.Credits_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY o.Credits_Date DESC,o.Nom ASC";*/
                //$querymut="SELECT ID,Nom,Pays,Avancement,Reputation,Mutation FROM Officier WHERE Pays='$country' AND Front='$Front' AND Mutation >0 ORDER BY Avancement DESC";
                $querymut2="SELECT ID,Nom,Pays,Avancement,Reputation,Mutation,Postuler FROM Officier_em WHERE Pays='$country' AND Front='$Front' AND Mutation >0 ORDER BY Avancement DESC";
            }
            $Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
            $Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
            $resultd=mysqli_query($con,$querydiv);
            $resulta=mysqli_query($con,$queryarmee);
            /* 22-01-2017 --
            $resultb=mysqli_query($con,$querybat);
            $resultm=mysqli_query($con,$querymut);*/
            $resultm2=mysqli_query($con,$querymut2);
            $resultl=mysqli_query($con,"SELECT DISTINCT l.ID,l.Nom,l.Latitude,l.Longitude,l.ValeurStrat,l.Flag,l.Port_Ori,p.Faction FROM Lieu as l
			LEFT JOIN Pays as p ON l.Flag=p.ID
			WHERE (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') ORDER BY l.NOM ASC");
            if($Front !=99)
            {
                $result=mysqli_query($con,"SELECT lieu_atk1,lieu_atk2,lieu_def FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
                if($result)
                {
                    while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                    {
                        $lieu_atk1=$data['lieu_atk1'];
                        $lieu_atk2=$data['lieu_atk2'];
                        $lieu_def=$data['lieu_def'];
                    }
                    mysqli_free_result($result);
                    unset($data);
                }
                if($lieu_atk1 >0)
                    $lieu_atk1=GetData("Lieu","ID",$lieu_atk1,"Nom");
                else
                    $lieu_atk1="Aucun";
                if($lieu_atk2 >0)
                    $lieu_atk2=GetData("Lieu","ID",$lieu_atk2,"Nom");
                else
                    $lieu_atk2="Aucun";
                if($lieu_def >0)
                    $lieu_def=GetData("Lieu","ID",$lieu_def,"Nom");
                else
                    $lieu_def="Aucun";
            }
            if($resultl)
            {
                $Lieux_cdt="<option value='999999'>- Aucun -</option><option value='0'>- Annuler -</option><optgroup label='Objectifs'>";
                while($datal=mysqli_fetch_array($resultl,MYSQLI_NUM))
                {
                    $Lieux_cdt.='<option value='.$datal[0].'>'.$datal[1].'</option>';
                    if($datal[4] >1)
                        $Lieux_obj.='<option value='.$datal[0].'>'.$datal[1].'</option>';
                    if($datal[4] >=4 and $datal[7] ==$Faction)
                    {
                        if($datal[6])
                            $base_list_naval.='<option value='.$datal[0].'>'.$datal[1].'</option>';
                        $base_list_terre.='<option value='.$datal[0].'>'.$datal[1].'</option>';
                    }
                }
                mysqli_free_result($resultl);
                unset($datal);
            }
            /* 22-01-2017 --
            if($resultb)
            {
                while($datab=mysqli_fetch_array($resultb))
                {
                    $Regs_max=floor($datab['Avancement']/5000)+3;
                    $Avancement=GetAvancement($datab['Avancement'],$country,0,1);
                    if($datab['Division'])
                    {
                        $div_icon="<img src='images/div/div".$datab['Division'].".png'>";
                        $Action="<form action='index.php?view=quit_division' method='post'><input type='hidden' name='Off' value='".$datab['ID']."'>
                        <input type='Submit' value='Retirer de la division' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
                        $Nommer="<form action='index.php?view=promote_division' method='post'><input type='hidden' name='Off' value='".$datab['ID']."'><input type='hidden' name='Div' value='".$datab['Division']."'>
                        <input type='Submit' value='Nommer Commandant' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                    else
                    {
                        $Action='';
                        $Nommer='';
                        $div_icon='';
                    }
                    $bat_nbr++;
                    $bat_list.="<tr><td>".$datab['ID']."e</td><td><img src='images/grades/ranks".$country.$Avancement[1].".png' title='".$Avancement[0]."'> ".$datab['Nom']."</td><td>".GetFront($datab['Front'])."</td><td>".$datab['Regs']."/".$Regs_max."</td><td>".$datab['Activite']."</td><td>".$div_icon."</td><td><div class='row'><div class='col-md-6'>".$Action."</div><div class='col-md-6'>".$Nommer."</div></div></td></tr>";
                }
                mysqli_free_result($resultb);
                unset($datab);
                if($bat_nbr)
                    $bat_list_pretxt="<div style='height:400px; overflow:auto;'>";
            }*/
            if($resulta)
            {
                while($data=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
                {
                    $List_div=false;
                    $List_esc=false;
                    $modal_txt=false;
                    if($data['Maritime'] ==1)
                        $base_list=$base_list_naval;
                    else
                        $base_list=$base_list_terre;
                    $resultdl=mysqli_query($con,"SELECT ID,Nom FROM Division WHERE Armee='".$data['ID']."'");
                    $resultul=mysqli_query($con,"SELECT ID,Pays,Nom FROM Unit WHERE Armee='".$data['ID']."' ORDER BY Type ASC");
                    $resultcl=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA r,Division d WHERE r.Division=d.ID AND d.Armee='".$data['ID']."'"),0);
                    if($resultdl)
                    {
                        while($datad=mysqli_fetch_array($resultdl,MYSQLI_ASSOC))
                        {
                            $List_div.=Afficher_Image('images/div/div'.$datad['ID'].'.png','images/'.$country.'div.png',$datad['Nom'],0);
                        }
                        mysqli_free_result($resultdl);
                    }
                    if($resultul)
                    {
                        while($datau=mysqli_fetch_array($resultul,MYSQLI_ASSOC))
                        {
                            $List_esc.=Afficher_Icone($datau['ID'],$datau['Pays'],$datau['Nom']);
                        }
                        mysqli_free_result($resultul);
                    }
                    if($List_div)$List_div='<h3>Divisions</h3>'.$List_div;
                    if($List_esc)$List_esc='<h3>Escadrilles</h3>'.$List_esc;
                    $armee_list.="<tr><td>".$data['Nom']."<br><a href='#clp-".$data['ID']."' data-toggle='collapse' class='bn btn-sm btn-primary'>Troupes</a><div class='collapse' id='clp-".$data['ID']."'>".$List_div."<hr>".$List_esc."</div></td>
                        <td>".$data['Ville']."<form action='index.php?view=ar_chg_base' method='post'><input type='hidden' name='Armee' value='".$data['ID']."'>
						<select name='Base' class='form-control' style='width: 150px'><option value='0'>Ne pas changer</option>".$base_list."</select>
						<input type='submit' value='Changer' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form></td>";
                    if($data['Cdt'])
                    {
                        $result=mysqli_query($con,"SELECT Nom,Armee,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='".$data['Cdt']."'");
                        if($result)
                        {
                            while($datao=mysqli_fetch_array($result,MYSQLI_ASSOC))
                            {
                                $Nom_Cdt=$datao['Nom'];
                                $Armee_Cdt=$datao['Armee'];
                                $Activite=$datao['Activite'];
                            }
                            mysqli_free_result($result);
                        }
                        $armee_list.="<td>".$Nom_Cdt."<br><i>".$Activite."</i><form action='index.php?view=quit_armee' method='post'><input type='hidden' name='Armee' value='".$Armee_Cdt."'><input type='hidden' name='Off' value='".$data['Cdt']."'>
						<input type='submit' value='Retirer' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form></td>";
                        $modal_txt='<table class="table table-striped"><thead><tr><th>Consigne</th><th>Lieu</th></tr></thead>';
                        if($data['Objectif']){
                            //$obj_txt = mysqli_result(mysqli_query($con,"SELECT Nom FROM Lieu WHERE ID=".$data['Objectif']),0);
                            $modal_txt.='<tr><th>Objectif</th><td>'.GetData("Lieu","ID",$data['Objectif'],"Nom");
                        }
                        else
                            $modal_txt.='<tr><th>Objectif</th><td>Aucun';
                        $modal_txt.='<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value='.$data['ID'].'>
						<select name="obj" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>'.$Lieux_obj.'</select>
						<input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
                        if($data['limite_ouest'])
                            $modal_txt.='<tr><th>Limite Ouest</th><td>'.GetData("Lieu","ID",$data['limite_ouest'],"Nom");
                        else
                            $modal_txt.='<tr><th>Limite Ouest</th><td>Aucune';
                        $modal_txt.='<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value='.$data['ID'].'>
						<select name="lo" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>'.$Lieux_obj.'</select>
						<input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
                        if($data['limite_nord']){
                            $modal_txt.='<tr><th>Limite Nord</th><td>'.GetData("Lieu","ID",$data['limite_nord'],"Nom");
                        }
                        else
                            $modal_txt.='<tr><th>Limite Nord</th><td>Aucune';
                        $modal_txt.='<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value='.$data['ID'].'>
						<select name="ln" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>'.$Lieux_obj.'</select>
						<input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
                        if($data['limite_est'])
                            $modal_txt.='<tr><th>Limite Est</th><td>'.GetData("Lieu","ID",$data['limite_est'],"Nom");
                        else
                            $modal_txt.='<tr><th>Limite Est</th><td>Aucune';
                        $modal_txt.='<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value='.$data['ID'].'>
						<select name="le" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>'.$Lieux_obj.'</select>
						<input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
                        if($data['limite_sud'])
                            $modal_txt.='<tr><th>Limite Sud</th><td>'.GetData("Lieu","ID",$data['limite_sud'],"Nom");
                        else
                            $modal_txt.='<tr><th>Limite Sud</th><td>Aucune';
                        $modal_txt.='<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value='.$data['ID'].'>
						<select name="ls" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>'.$Lieux_obj.'</select>
						<input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
                        $modal_txt.='</table>';
                        $modal.='<div class="modal fade" id="modal-'.$data['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="modal-title">Objectifs de la '.$data['Nom'].'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h2>
                                            </div>
                                            <div class="modal-body">'.$modal_txt.' </div>
                                        </div>
                                    </div>
                                </div>';
                    }
                    else
                        $armee_list.='<td></td>';
                    $armee_list.='<td>'.$resultcl.'</td><td><a href="#modal-'.$data['ID'].'" data-toggle="modal" data-target="#modal-'.$data['ID'].'" class="btn btn-sm btn-default">Ordres</a></td>';
                    //<form action="index.php?view=ar_chg_obj0" method="post"><input type="hidden" name="armee" value="'.$Armee_Cdt.'"><input type="submit" value="Ordres" class="btn btn-sm btn-default" onclick="this.disabled=true;this.form.submit();"></form>
                    if($Front ==99)
                    {
                        if($country ==1)
                            $fronts_list="<option value='5'>Arctique</option><option value='1'>Est</option><option value='2'>Méditerranéen</option>
										<option value='4'>Nord</option><option value='0'>Ouest</option>";
                        elseif($country ==2)
                            $fronts_list="<option value='5'>Arctique</option><option value='2'>Méditerranéen</option><option value='0'>Ouest</option><option value='3'>Pacifique</option>";
                        elseif($country ==4)
                            $fronts_list="<option value='2'>Méditerranéen</option><option value='0'>Ouest</option>";
                        elseif($country ==6)
                            $fronts_list="<option value='1'>Est</option><option value='2'>Méditerranéen</option><option value='0'>Ouest</option>";
                        elseif($country ==7)
                            $fronts_list="<option value='5'>Arctique</option><option value='2'>Méditerranéen</option><option value='0'>Ouest</option><option value='3'>Pacifique</option>";
                        elseif($country ==8)
                            $fronts_list="<option value='5'>Arctique</option><option value='1'>Est</option><option value='4'>Nord</option>";
                        else
                            $fronts_list='';
                        $armee_list.="<td>".GetFront($data['Front'])."<form action='index.php?view=ghq_chg_armee' method='post'><input type='hidden' name='Div' value='".$data['ID']."'>
						<select name='Front' class='form-control' style='width: 200px'><option value='99'>Aucun</option>".$fronts_list."</select>
						<input type='submit' value='Changer' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form></td>";
                    }
                }
                mysqli_free_result($resulta);
            }
            if($resultd)
            {
                if(!$Admin)$query9_add=" AND a.Front='$Front'";
                $result9=mysqli_query($con,"SELECT a.ID,a.Nom,a.Base,l.Nom AS BaseNom,o.Nom AS Cdt FROM Armee a
                    LEFT JOIN Lieu l ON a.Base=l.ID
                    LEFT JOIN Officier_em o ON a.Cdt=o.ID
                    WHERE a.Pays='$country'".$query9_add." AND a.Active=1");
                if($result9)
                {
                    $i=0;
                    while($data9=mysqli_fetch_array($result9,MYSQLI_ASSOC)){
                        $i++;
                        $divs[$i]['ID']=$data9['ID'];
                        $divs[$i]['Nom']=$data9['Nom'];
                        $divs[$i]['BaseNom']=$data9['BaseNom'];
                        $divs[$i]['Cdt']=$data9['Cdt'];
                    }
                    mysqli_free_result($result9);
                }
                //if($Admin)var_dump(get_defined_vars());
                while($data=mysqli_fetch_array($resultd,MYSQLI_ASSOC))
                {
                    $div_nbr_bat=0;
                    $Division_d=$data['ID'];
                    if($data['Maritime'] ==1)
                        $base_list=$base_list_naval;
                    else
                        $base_list=$base_list_terre;
                    if($data['Armee']){
                        $Armee_txt=GetData("Armee","ID",$data['Armee'],"Nom");
                        $btn_col='danger';
                        $div_nbr_bat=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Division=".$data['ID']),0);
                    }
                    else{
                        $Armee_txt='Aucune';
                        $btn_col='warning';
                    }
                    //Modal
                    $Divisions_txt='';
                    if(is_array($divs)){
                        foreach($divs as $div){
                            $Divisions_txt.='<tr><td><a class="lien" href="ground_em_change_div.php?armee='.$div['ID'].'&div='.$data['ID'].'">'.$div['Nom'].'</a></td><td>'.$div['BaseNom'].'</td><td>'.$div['Cdt'].'</td></tr>';
                        }
                    }
                    $Divisions_modal='<div class="modal fade" id="modal-div-'.$data['ID'].'" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title">Gestion de la division
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </h2>
                                            <a class="btn btn-default" href="ground_em_change_div.php?armee=9999&div='.$data['ID'].'">Affecter à la réserve</a>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped"><thead><tr><th>Armée</th><th>Base</th><th>Commandant</th></tr></thead>'.$Divisions_txt.'</table>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    //End Modal
                    $Armee_txt.=$Divisions_modal.'<br><a href="#" data-toggle="modal" data-target="#modal-div-'.$data['ID'].'" class="btn btn-'.$btn_col.' btn-sm">Changer</a>';
                    /*$Armee_txt.="<form action='index.php?view=ground_em_change_div1' method='post'><input type='hidden' name='Div' value='".$Division_d."'>
							<input type='submit' value='Changer' class='btn btn-".$btn_col." btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";*/
                    $div_list.="<tr><td>".Afficher_Image('images/div/div'.$Division_d.'.png','images/'.$country.'div.png',$data['Nom'],0)."</td>
					<td>".$data['Ville']."<form action='index.php?view=div_chg_base' method='post'><input type='hidden' name='Div' value='".$Division_d."'>
						<select name='Base' class='form-control' style='width: 150px'><option value='0'>Ne pas changer</option>".$base_list."</select>
						<input type='submit' value='Changer' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>
					</td>";
                    /*if($data['Cdt'])
                    {
                        //$con=dbconnecti();
                        $result=mysqli_query($con,"SELECT Nom,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier WHERE ID='".$data['Cdt']."'");
                        //mysqli_close($con);
                        if($result)
                        {
                            while($datao=mysqli_fetch_array($result,MYSQLI_ASSOC))
                            {
                                $Nom_Cdt=$datao['Nom'];
                                $Activite=$datao['Activite'];
                            }
                            mysqli_free_result($result);
                        }
                        $div_list.="<td>".$Nom_Cdt."<br><i>".$Activite."</i><form action='index.php?view=quit_division' method='post'><input type='hidden' name='Off' value='".$data['Cdt']."'>
                        <input type='Submit' value='Retirer' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form></td>";
                    }
                    else
                        $div_list.="<td></td>";*/
                    $div_list.='<td>'.$Armee_txt.'</td><td>'.$div_nbr_bat.'</td>';
                    if($Front ==99)
                        $div_list.="<td>".GetFront($data['Front'])."<form action='index.php?view=ghq_chg_div' method='post'><input type='hidden' name='Div' value='".$Division_d."'>
						<select name='Front' class='form-control' style='width: 200px'><option value='99'>Aucun</option><option value='5'>Arctique</option><option value='1'>Est</option><option value='2'>Méditerranéen</option><option value='4'>Nord</option><option value='0'>Ouest</option><option value='3'>Pacifique</option></select>
						<input type='submit' value='Changer' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form></td>";
                    elseif($data['atk'])
                        $div_list.="<td>".GetData("Lieu","ID",$data['atk'],"Nom")."</td>";
                    else
                        $div_list.='<td></td>';
                }
                mysqli_free_result($resultd);
            }
            if($Front ==99)
            {
                echo '<h1>Etat-Major</h1>';
                if($armee_list)
                    echo "<div class='row'><a data-toggle='collapse' href='#em-armee'><h2>Liste des Armées<span class='caret'></span></h2></a><div class='collapse' id='em-armee'>
					<table class='table table-striped'><thead><tr><th>Nom</th><th>Base</th><th>Commandant</th><th>Bataillons</th><th>Objectifs</th><th>Front <a href='#' class='popup'><img src='images/help.png'><span>Changer de front changera la base arrière et supprimera toutes les divisions associées à l'armée</span></a></th></tr></thead>".$armee_list."</table></div></div>";
                if($div_list)
                    echo "<div class='row'><a data-toggle='collapse' href='#em-div'><h2>Liste des Divisions<span class='caret'></span></h2></a><div class='collapse' id='em-div'>
					<table class='table table-striped'><thead><tr><th>Nom</th><th>Base</th><th>Armee</th><th>Bataillons</th><th>Front <a href='#' class='popup'><img src='images/help.png'><span>Changer de front changera la base arrière et supprimera toutes les troupes IA ainsi que tous les officiers de la division</span></a></th></tr></thead>".$div_list."</table></div></div>";
                echo "<h2>Effectifs des divisions</h2><a class='btn btn-primary' href='index.php?view=ground_em_div'>Compagnies</a>";
            }
            else
            {
                echo "<h1>Etat-Major</h1>".$Alert."<div class='row'><div class='col-lg-6 col-md-12'><form action='index.php?view=officier_chat' method='post'>
					<input type='hidden' name='Officier' value='".$OfficierEMID."'>
					<input type='hidden' name='Pays' value='".$country."'>
					<input type='hidden' name='Front' value='".$Front."'>
					<table class='table'>
						<thead><tr><th colspan='2'>Ordre du jour</th></tr></thead>
						<tr><td>Envoyer l'ordre du jour aux officiers de votre front (250 caractères max.)<td></tr>
						<tr><td><textarea name='officier_msg' rows='5' cols='50' class='form-control'></textarea></td></tr>
						<tr><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
					</table></form>
					</div><div class='col-lg-6 col-md-12'>
					<form action='index.php?view=ground_em_orders' method='post'>
					<input type='hidden' name='Officier' value='".$OfficierEMID."'>
					<input type='hidden' name='Pays' value='".$country."'>
					<input type='hidden' name='Front' value='".$Front."'>
					<table class='table table-striped'>
					<thead><tr><th>Dénomination</th><th>Changement</th><th>Actuel</th></tr></thead>
					<tr><td align='left'>Objectif prioritaire à défendre</td><td><select name='pdef' class='form-control' style='width: 200px'>".$Lieux_cdt."</optgroup></select></td><td>".$lieu_def."</td></tr>
					<tr><td align='left'>Objectif prioritaire à attaquer</td><td><select name='patk1' class='form-control' style='width: 200px'>".$Lieux_cdt."</optgroup></select></td><td>".$lieu_atk1."</td></tr>
					<tr><td align='left'>Objectif secondaire à attaquer</td><td><select name='patk2' class='form-control' style='width: 200px'>".$Lieux_cdt."</optgroup></select></td><td>".$lieu_atk2."</td></tr>
					<tr><td colspan='4'><input type='submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
					</table></form></div></div>
					<a class='btn btn-primary' href='carte_ground.php?map=".$Front."&o=".$OfficierEMID."&mode=19' target='_blank'>Carte stratégique</a>
					<a data-toggle='collapse' href='#em-armee'><h2>Liste des Armées<span class='caret'></span></h2></a><div class='collapse' id='em-armee'><table class='table table-striped'>
					<thead><tr><th>Nom</th><th>Base</th><th>Commandant</th><th>Bataillons</th><th>Objectifs</th><th>Front <a href='#' class='popup'><img src='images/help.png'><span>Changer de front changera la base arrière et supprimera toutes les divisions associées à l'armée</span></a></th></tr></thead>".$armee_list."</table>".$modal."</div>
					<a data-toggle='collapse' href='#em-div'><h2>Liste des Divisions<span class='caret'></span></h2></a><div class='collapse' id='em-div'><table class='table table-striped'><thead><tr><th>Nom</th><th>Base</th><th>Armee</th><th>Unités</th><th>Cible <a href='#' class='popup'><img src='images/help.png'><span>Objectif défini par le commandant de division</span></a></th></tr></thead>".$div_list."</table></div>
					<h2>Effectifs des divisions</h2><a class='btn btn-primary' href='index.php?view=ground_em_div'>Compagnies</a>";
            }
            /* 22-01-2017 --
            echo"<h2>Liste des Bataillons</h2><fieldset>".$bat_list_pretxt."<table class='table table-striped'>
                <thead><tr><th>Nom</th><th>Commandant</th><th>Front</th><th>Compagnies</th><th>Activité</th><th>Division</th><th>Action</th></tr></thead>".$bat_list."</table></div></fieldset>";*/
            /*<div class='row'><div class='col-md-6'><a class='btn btn-primary' href='index.php?view=ground_em_div'>Compagnies EM</a></div>
            <div class='col-md-6'><a class='btn btn-primary' href='index.php?view=ground_em_pj'>Compagnies PJ</a></div></div>";*/
            if(($Admin or $Front !=99) and ($resultm or $resultm2))
            {
                /* 22/01/2017 --
                if($resultm)
                {
                    while($data=mysqli_fetch_array($resultm,MYSQLI_ASSOC))
                    {
                        $Grade=GetAvancement($data['Avancement'],$data['Pays'],0,1);
                        $Rep=GetReputOfficier($data['Reputation']);
                        echo "<tr><td align='left'>".$data['Nom']."</td>
                            <td><img src='".$data['Pays']."20.gif'></td>
                            <td><img title='".$Grade[0]."' src='images/grades/ranks".$data['Pays'].$Grade[1].".png'></td>
                            <td><img title='".$Rep[0]."' src='images/general".$Rep[1].".png'></td><td>".GetData("Division","ID",$data['Mutation'],"Nom")."(".GetData("Lieu","ID",GetData("Division","ID",$data['Mutation'],"Base"),"Nom").")</td>
                            <td><form action='index.php?view=ground_mutation1' method='post'>
                            <input type='hidden' name='off' value='".$data['ID']."'>
                            <input type='hidden' name='mut' value='".$data['Mutation']."'>
                            <input type='Submit' value='Accepter' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'>
                            </form></td>
                            <td><form action='index.php?view=ground_mutation2' method='post'>
                            <input type='hidden' name='off' value='".$data['ID']."'>
                            <input type='Submit' value='Refuser' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
                            </form></td></tr>";
                    }
                    mysqli_free_result($resultm);
                }*/
                if($resultm2)
                {
                    while($datam=mysqli_fetch_array($resultm2,MYSQLI_ASSOC))
                    {
                        $Grade=GetAvancement($datam['Avancement'],$datam['Pays'],0,1);
                        $Rep=GetReputOfficier($datam['Reputation']);
                        if($datam['Mutation'] ==9999){
                            $Poste_txt = GetPosteEM($datam['Postuler']);
                        }
                        else{
                            $Poste_txt = GetData("Armee","ID",$datam['Mutation'],"Nom")."(".GetData("Lieu","ID",GetData("Armee","ID",$datam['Mutation'],"Base"),"Nom").")";
                        }
                        $dem_mut_txt.="<tr><td align='left'>".$datam['Nom']."</td>
							<td><img src='".$datam['Pays']."20.gif'></td>
							<td><img title='".$Grade[0]."' src='images/grades/ranks".$datam['Pays'].$Grade[1].".png'></td>
							<td><img title='".$Rep[0]."' src='images/general".$Rep[1].".png'></td><td>".$Poste_txt."</td>
							<td><form action='index.php?view=armee_mutation1' method='post'>
							<input type='hidden' name='off' value='".$datam['ID']."'>
							<input type='hidden' name='mut' value='".$datam['Mutation']."'>
							<input type='submit' value='Accepter' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'>
							</form></td>
							<td><form action='index.php?view=armee_mutation2' method='post'>
							<input type='hidden' name='off' value='".$datam['ID']."'>
							<input type='submit' value='Refuser' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
							</form></td></tr>";
                    }
                    mysqli_free_result($resultm2);
                }
                if($dem_mut_txt){
                    echo '<div>
                            <h2>Demandes de Mutation</h2>
                            <fieldset>
                                <table class="table">
                                <thead><tr>
                                    <th>Nom</th>
                                    <th>Pays</th>
                                    <th>Grade</th>
                                    <th>Réputation</th>
                                    <th>Mutation demandée</th>
                                    <th colspan="2">Action</th>
                                </tr></thead>'.$dem_mut_txt.'
                                </table>					        
                            </fieldset>
                        </div>';
                }
            }
        }
        elseif($OfficierEMID >0 and $OfficierEMID ==$Officier_Log and $Front !=12){
            $result3=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,l.Nom as Ville,r.Placement,r.Position,r.Division,r.Pays,r.Move
			FROM Regiment_IA as r,Lieu as l WHERE r.Lieu_ID=l.ID AND r.Pays='$country' AND r.Front='$Front' AND r.Division=0 AND r.Vehicule_ID IN (424,5001,5124,5392) ORDER BY Lieu_ID ASC");
            mysqli_close($con);
            echo "<h1>Etat-Major</h1><div class='row'><div class='col-md-6'><form action='index.php?view=officier_chat' method='post'>
				<input type='hidden' name='Officier' value='".$OfficierEMID."'>
				<input type='hidden' name='Pays' value='".$country."'>
				<input type='hidden' name='Front' value='".$Front."'>
				<table class='table'>
					<thead><tr><th colspan='2'>Ordre du jour</th></tr></thead>
					<tr><td>Envoyer l'ordre du jour aux officiers de votre front (250 caractères max.)<td></tr>
					<tr><td><textarea name='officier_msg' rows='5' cols='50' class='form-control'></textarea></td></tr>
					<tr><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
				</table></form>";
            if($result3){
                echo "<h2>Liste des unités de ravitaillement</h2><div style='overflow:auto; height: 640px;'><table class='table'><thead><tr>
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
                        $Move="<div class='i-flex led_red'></div>"; //Afficher_Image('images/led_red.png','','',10);
                    else
                        $Move="<div class='i-flex led_green'></div>"; //Afficher_Image('images/led_green.png','','',10);
                    $Action="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data3['ID']."'>
					<input type='Submit' value='Ordres' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    echo "<tr><td>".$data3['ID']."e</td><td>".$data3['Vehicule_Nbr']." ".GetVehiculeIcon($data3['Vehicule_ID'],$data3['Pays'],0,0,$Front)."</td><td>".$Move." ".$data3['Ville']."</td><td>".GetPosGr($data3['Position']).' '.GetPlace($data3['Placement'])."</td><td>".$Action."</td></tr>";
                }
                mysqli_free_result($result3);
            }
            echo '</table></div>';
        }
        elseif($Armee){
            $Coord=GetCoord($Front,$country);
            $Lat_base_min=$Coord[0];
            $Lat_base_max=$Coord[1];
            $Long_base_min=$Coord[2];
            $Long_base_max=$Coord[3];
            $resulta=mysqli_query($con,"SELECT a.ID,a.Nom,a.Cdt,a.Front,a.Maritime,a.Objectif,a.limite_ouest,a.limite_nord,a.limite_est,a.limite_sud,l.Longitude,l.Nom as Ville FROM Armee as a,Lieu as l WHERE a.Base=l.ID AND a.ID=$Armee");
            if($resulta){
                $dataa=mysqli_fetch_array($resulta);
                $resultd=mysqli_query($con, "SELECT ID,Nom FROM Lieu WHERE Flag='$country' AND ValeurStrat >3 AND Latitude BETWEEN $Lat_base_min AND $Lat_base_max AND Longitude BETWEEN $Long_base_min AND $Long_base_max ORDER BY Nom ASC");
                if($resultd){
                    while($datad=mysqli_fetch_array($resultd)){
                        $depots_list.='<option value="'.$datad['ID'].'">'.$datad['Nom'].'</option>';
                    }
                    mysqli_free_result($resultd);
                }
                $resultt=mysqli_query($con,"SELECT ID,`Type` FROM Veh_Type WHERE Actif=1 ORDER BY `Type` ASC");
                if($resultt){
                    while($datat=mysqli_fetch_array($resultt)){
                        $veh_types.='<option value="'.$datat['ID'].'">'.$datat['Type'].'</option>';
                    }
                    mysqli_free_result($resultt);
                }
                if($Front ==2 and $dataa['Longitude'] <12)
                    $front_carte=12;
                else
                    $front_carte=$Front;
                echo '<h2>Ordres du '.GetGenStaff($country,1).'</h2>
                        <div class="panel panel-war">
                            <div class="panel-heading">'.$dataa['Nom'].'</div>
                            <div class="panel-body">
                                <div class="row"><div class="col-sm-6"><b>Objectif</b></div><div class="col-sm-6">'.Getdata("Lieu","ID",$dataa['Objectif'],"Nom").'</div></div>
                                <div class="row"><div class="col-sm-6"><b>Limite Nord</b></div><div class="col-sm-6">'.Getdata("Lieu","ID",$dataa['limite_nord'],"Nom").'</div></div>
                                <div class="row"><div class="col-sm-6"><b>Limite Sud</b></div><div class="col-sm-6">'.Getdata("Lieu","ID",$dataa['limite_sud'],"Nom").'</div></div>
                                <div class="row"><div class="col-sm-6"><b>Limite Est</b></div><div class="col-sm-6">'.Getdata("Lieu","ID",$dataa['limite_est'],"Nom").'</div></div>
                                <div class="row"><div class="col-sm-6"><b>Limite Ouest</b></div><div class="col-sm-6">'.Getdata("Lieu","ID",$dataa['limite_ouest'],"Nom").'</div></div>
                                <div class="row"><div class="col-sm-6"><b>Base Arrière</b></div><div class="col-sm-6">'.$dataa['Ville'].'</div></div>
                            </div>
                        </div>';
                echo '<div class="row"><div class="col-sm-4">';
                echo "<h3>Visionner la carte</h3>
                    <div class='panel panel-war' style='max-width:320px;'><div class='panel-heading'>Carte Opérationnelle</div>
                    <div class='panel-body'><div class='row'><div class='col-sm-6'>
                    <a href='carte_ground.php?map=".$front_carte."&mode=18&o=".$dataa['Objectif']."&n=".$dataa['limite_nord']."&s=".$dataa['limite_sud']."&e=".$dataa['limite_est']."&w=".$dataa['limite_ouest']."' 
                    class='btn btn-default' target='_blank'><img src='images/map_icon.gif' title='Visualiser sur la carte'></a></div>
                    <div class='col-sm-6'><img src='images/map/range50.png'> Objectif<br><img src='images/map/range50r.png'> Limites</div></div></div></div></div>";
                echo '
                <div class="col-sm-4">
                    <h3>Demande Logistique</h3>
                    <div class="panel panel-war">
                        <div class="panel-heading">Demande de ravitaillement</div>
                        <div class="panel-body"><form action="ground_em_dem" method="post">
                            <input type="hidden" name="army" value="'.$Armee.'">
                            <label for="depot">Dépôt à ravitailler</label>
                            <select name="depot" id="depot" class="form-control" style="max-width:300px;"><option value="">Aucun</option>'.$depots_list.'</select>
                            <label for="res">Ressource</label>
                            <select name="res" id="res" class="form-control" style="max-width:300px;"><option value="">Aucun</option><option value="8">8mm</option>
                            <option value="13">13mm</option><option value="20">20mm</option><option value="30">30mm</option>
                            <option value="40">40mm</option><option value="50">50mm</option><option value="60">60mm</option><option value="75">75mm</option>
                            <option value="90">90mm</option><option value="105">105mm</option><option value="125">125mm</option><option value="150">150mm</option>
                            <option value="87">Essence</option><option value="1">Diesel</option></select>
                            <br><input type="submit" value="Commander" class="btn btn-sm btn-warning" onclick="this.disabled=true;this.form.submit();"></form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h3>Demande Commandant</h3>
                    <div class="panel panel-war">
                        <div class="panel-heading">Demande de renforts</div>
                        <div class="panel-body"><form action="ground_em_dem" method="post">
                            <input type="hidden" name="army" value="'.$Armee.'">
                            <label for="troop">Troupes</label>
                            <select name="troop" id="troop" class="form-control" style="max-width:300px;"><option value="">Aucune</option>'.$veh_types.'</select>
                            <br><input type="submit" value="Commander" class="btn btn-sm btn-warning" onclick="this.disabled=true;this.form.submit();"></form>
                        </div>
                    </div>
                </div>';
            }
        }
        else{
            PrintNoAccess($country,1);
        }
        /*elseif(($Commandant >0 or $Officier_Mer >0 or $Adjoint_Terre >0 or $Officier_Log >0 or $Officier_Rens >0) and $Front !=12)
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
                $CO_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect stratégique du jeu,<br>vous pouvez créer un officier d'état-major sur la page de connexion du jeu et postuler <a href='index.php?view=em_actus' class='lien'>ici</a>";
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
                $AO_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect stratégique du jeu,<br>vous pouvez créer un officier d'état-major sur la page de connexion du jeu et postuler <a href='index.php?view=em_actus' class='lien'>ici</a>";
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
                $MO_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect stratégique du jeu,<br>vous pouvez créer un officier d'état-major sur la page de connexion du jeu et postuler <a href='index.php?view=em_actus' class='lien'>ici</a>";
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
                $OL_txt="Aucun officier n'occupe ce poste sur ce front<br><br>Si vous êtes intéressé par l'aspect logistique du jeu,<br>vous pouvez créer un officier d'état-major sur la page de connexion du jeu et postuler <a href='index.php?view=em_actus' class='lien'>ici</a>";
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
            echo "<h1>Etat-Major</h1><table class='table'><thead><tr><th>Commandant en Chef</th><th>Officier Terrestre</th><th>Officier Maritime</th><th>Officier Logistique</th><th>Officier Renseignement</th><th>Officier Infrastructures</th></tr></thead><tr><td>".$CO_txt."</td><td>".$AO_txt."</td><td>".$MO_txt."</td><td>".$OL_txt."</td><td>".$OR_txt."</td><td>".$OI_txt."</td></tr></table>";
        }
        else{
            include_once('./menu_em.php');
            echo "<h2>Etat-Major</h2><div class='alert alert-info'>Aucun officier n'occupe de poste à l'état-major sur ce front.<br>Si vous êtes intéressé par l'aspect stratégique du jeu, vous pouvez créer un officier d'état-major sur la page de connexion du jeu et postuler <a href='index.php?view=em_actus' class='lien'>ici</a></div>";
        }*/
    }
    elseif($OfficierID >0)
    {
        $Division=GetData("Officier","ID",$OfficierID,"Division");
        if($Division)$Cdt_Div=GetData("Division","ID",$Division,"Cdt");
        if($Division and $Cdt_Div >0 and $Cdt_Div ==$OfficierID)
        {
            $con=dbconnecti();
            $result=mysqli_query($con,"SELECT Nom,Base,repli,rally,atk,hatk,def,ravit FROM Division WHERE ID='$Division'");
            //mysqli_close($con);
            if($result)
            {
                while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                {
                    $Div_Nom=$data['Nom'];
                    $Base=$data['Base'];
                    $repli=$data['repli'];
                    $rally=$data['rally'];
                    $ravit=$data['ravit'];
                    $atk=$data['atk'];
                    $hatk=$data['hatk'];
                    $def=$data['def'];
                }
                mysqli_free_result($result);
            }
            if($repli >0)
                $repli=GetData("Lieu","ID",$repli,"Nom");
            else
                $repli="Aucun";
            if($rally >0)
                $rally=GetData("Lieu","ID",$rally,"Nom");
            else
                $rally="Aucun";
            if($atk >0)
                $atk=GetData("Lieu","ID",$atk,"Nom");
            else
                $atk="Aucun";
            if($def >0)
                $def=GetData("Lieu","ID",$def,"Nom");
            else
                $def="Aucun";
            if($ravit >0)
                $ravit=GetData("Lieu","ID",$ravit,"Nom");
            else
                $ravit="Aucun";
            $Coord=GetCoord($Front,$country);
            $Lat_base_min=$Coord[0];
            $Lat_base_max=$Coord[1];
            $Long_base_min=$Coord[2];
            $Long_base_max=$Coord[3];
            $Divisions="<select name='bat' class='form-control' style='width: 150px'><option value='0'>Ne rien changer</option><option value='9999'>Aucune</option>";
            $Lieux="<option value='999999'>Aucun</option><option value='0'>Annuler</option>";
            $query_lieux="SELECT DISTINCT ID,Nom,Latitude,Longitude FROM Lieu WHERE (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') ORDER BY NOM ASC";
            //$con=dbconnecti();
            $resultl=mysqli_query($con,$query_lieux);
            /*$result2=mysqli_query($con,"SELECT o.Nom as Officer,o.Avancement,r.ID,r.Vehicule_ID,r.Vehicule_Nbr,l.Nom as Ville,r.Placement,r.Position,o.Division
            FROM Officier as o,Regiment as r,Lieu as l WHERE r.Officier_ID=o.ID AND r.Lieu_ID=l.ID AND o.Division='$Division'");*/
            $result3=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Pays,r.Experience,r.Skill,l.ID as City_ID,l.Nom as Ville,l.Latitude,l.Longitude,l.Zone,r.Placement,r.Position,r.Division,r.Bataillon,r.Move,r.HP,r.Moral,r.Visible,r.Ravit,r.Bomb_IA,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,c.Categorie,c.mobile,c.Fuel,c.Type,c.HP as HP_max
			FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays='$country' AND r.Division='$Division' AND r.NoEM=0 ORDER BY r.Lieu_ID ASC,r.Placement ASC");
            $result9=mysqli_query($con,"SELECT o.ID,o.Nom,o.Avancement,(SELECT COUNT(*) FROM Regiment_IA as r WHERE r.Bataillon=o.ID) as Regs,DATE_FORMAT(o.Credits_Date,'%d-%m-%Y') as Activite FROM Officier as o WHERE o.Pays='$country' AND o.Front='$Front' AND o.Actif=0 AND o.Division='$Division' AND
			o.Credits_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() AND ((SELECT COUNT(*) FROM Regiment_IA as r WHERE r.Bataillon=o.ID)<floor((o.Avancement/5000)+3)) ORDER BY o.Nom ASC");
            $result8=mysqli_query($con,"SELECT o.ID,o.Nom,o.Avancement,(SELECT COUNT(*) FROM Regiment_IA as r WHERE r.Bataillon=o.ID) as Regs,DATE_FORMAT(o.Credits_Date,'%d-%m-%Y') as Activite FROM Officier as o WHERE o.Division='$Division' ORDER BY o.Nom ASC");
            mysqli_close($con);
            if($result8)
            {
                while($data8=mysqli_fetch_array($result8,MYSQLI_ASSOC))
                {
                    $Regs_max=floor($data8['Avancement']/5000)+3;
                    $Avancement=GetAvancement($data8['Avancement'],$country,0,1);
                    $bat_list.="<tr><td>".$data8['ID']."e</td><td><img src='images/grades/grades".$country.$Avancement[1].".png' title='".$Avancement[0]."'> ".$data8['Nom']."</td><td>".$data8['Regs']."/".$Regs_max."</td><td>".$data8['Activite']."</td></tr>";
                }
                mysqli_free_result($result8);
            }
            if($result9)
            {
                while($data9=mysqli_fetch_array($result9,MYSQLI_ASSOC))
                {
                    $Regs_max=floor($data9['Avancement']/5000)+3;
                    $Avancement=GetAvancement($data9['Avancement'],$country,0,1);
                    $Divisions.="<option value='".$data9['ID']."'>".$data9['Nom']." (".$data9['Regs']."/".$Regs_max.")</option>";
                    //$bat_list.="<tr><td>".$data9['ID']."e</td><td><img src='images/grades/grades".$country.$Avancement[1].".png' title='".$Avancement[0]."'> ".$data9['Nom']."</td><td>".$data9['Regs']."/".$Regs_max."</td><td>".$data9['Activite']."</td></tr>";
                }
                mysqli_free_result($result9);
                $Divisions.="</select>";
            }
            if($resultl)
            {
                while($datal=mysqli_fetch_array($resultl,MYSQLI_NUM))
                {
                    //$Dist=GetDistance(0,0,$Long_base,$Lat_base,$datal[3],$datal[2]);
                    //$idata=$datal[1].' ('.$Dist[0].'km)';
                    $Lieux.='<option value='.$datal[0].'>'.$datal[1].'</option>';
                }
                mysqli_free_result($resultl);
                unset($datal);
            }
            /*if($result2)
            {
                while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
                {
                    $Regs_div.="<tr><td>".$data['ID']."e</td><td>".$data['Officer']."</td><td align='left'>".$data['Vehicule_Nbr']." <img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'></td><td>".$data['Ville']."</td><td>".GetPosGr($data['Position']).' '.GetPlace($data['Placement'])."</td><td></td></tr>";
                }
                mysqli_free_result($result2);
            }*/
            if($result3)
            {
                $today=getdate();
                while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
                {
                    if($data3['Bataillon'])
                        $Off_Bat=GetData("Officier","ID",$data3['Bataillon'],"Nom")." (Bat)";
                    else
                        $Off_Bat=$Off_Nom." (Div)";
                    if($data3['Type']!=95)
                        $Off_Bat.="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$data3['ID']."'>".$Divisions."
						<input type='Submit' value='Changer' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
                    if($data3['Categorie'] ==4)
                        $Veh_Cdt=true;
                    if($data3['Move'])
                        $Move=Afficher_Image('images/led_red.png','','',10);
                    else
                        $Move=Afficher_Image('images/led_green.png','','',10);
                    if(!$data3['Visible'])
                        $Camo_txt=Afficher_Image('images/camouflage.png','','Camouflé',10);
                    else
                        $Camo_txt=false;
                    if($data3['Bomb_IA'])$Camo_txt.="<a href='#' class='popup'><img src='images/map/noia.png'><span>Ne peut plus être ciblé par les bombardements tactiques IA jusque au prochain passage de date</span></a>";
                    if($data3['Ravit'])$Camo_txt.="<a href='#' class='popup'><img src='images/map/air_ravit.png'><span>Ravitaillé par air</span></a>";
                    if($data3['mobile'] ==5)
                    {
                        $per_c=round(100/($data3['HP_max']/$data3['HP']));
                        if($per_c >99)
                            $HP_per="<span class='label label-success'>".$per_c."%</span>";
                        elseif($per_c <1)
                            $HP_per="<span class='label label-danger'>".$per_c."%</span>";
                        else
                            $HP_per="<span class='label label-warning'>".$per_c."%</span>";
                        if($data3['Categorie'] ==20 or $data3['Categorie'] ==21 or $data3['Categorie'] ==22 or $data3['Categorie'] ==24 or $data3['Categorie'] ==17)
                        {
                            if($data3['Autonomie'])
                                $HP_per.="<span class='label label-warning'><a class='lien' title='Aide' href='help/aide_jours.php' target='_blank'>".$data3['Autonomie']." Jours</a></span>";
                            else
                                $HP_per.="<span class='label label-danger'><a class='lien' title='Aide' href='help/aide_jours.php' target='_blank'>".$data3['Autonomie']." Jours</a></span>";
                        }
                    }
                    else
                        $HP_per=false;
                    if($today['mday'] >$data3['Jour']+1)
                        $Combat_flag=false;
                    elseif($today['mon'] >$data3['Mois'])
                        $Combat_flag=false;
                    elseif($today['year'] >$data3['Year_a'])
                        $Move_flag=false;
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
                            $Action.="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$data3['ID']."'><input type='hidden' name='reset' value='9'><input type='hidden' name='Max' value='".$data3['Vehicule_Nbr']."'>
							<a href='#' class='popup'><input type='Submit' value='Fuir' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
							<span>Cette action permettra à l'unité d'agir, mais réduira ses effectifs à 1</span></a></form>";
                            if($data3['Vehicule_ID']<5000 and $data3['Vehicule_Nbr'] >0 and $data3['Atk_Eni'])
                            {
                                $Action.="<form action='index.php?view=ground_pl' method='post'>
											<input type='hidden' name='CT' value='0'>
											<input type='hidden' name='distance' value='500'>
											<input type='hidden' name='Action' value='".$data3['Atk_Eni']."_0'>
											<input type='hidden' name='Veh' value='".$data3['Vehicule_ID']."'>
											<input type='hidden' name='Reg' value='".$data3['ID']."'>
											<input type='hidden' name='Pass' value='".$data3['Vehicule_Nbr']."'>
								<a href='#' class='popup'><input type='Submit' value='Riposter' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
								<span>Cette action permettra de tenter de contre-attaquer l'unité qui vous a engagé</span></a></form>";
                            }
                        }
                    }
                    elseif($data3['mobile'] !=5 and ($data3['Move'] ==1 or $Move_flag))
                        $Action="<span class='text-danger'>Mouvement<br>jusque ".$data3['Heure_m']."</span>";
                    elseif($data3['Bataillon'])
                    {
                        $Action="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data3['ID']."'>
						<input type='Submit' value='".$data3['Bataillon']." Bat' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                    else
                        $Action="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data3['ID']."'>
						<input type='Submit' value='Ordres' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    if($data3['Experience'] >249)
                        $Exp_txt="<span class='label label-success'>".$data3['Experience']."XP</span>";
                    elseif($data3['Experience'] >49)
                        $Exp_txt="<span class='label label-primary'>".$data3['Experience']."XP</span>";
                    elseif($data3['Experience'] >1)
                        $Exp_txt="<span class='label label-warning'>".$data3['Experience']."XP</span>";
                    else
                        $Exp_txt="<span class='label label-danger'>".$data3['Experience']."XP</span>";
                    if($data3['Skill'])
                        $Skill_txt="<a href='index.php?view=reg_skills'><img src='images/skills/skillo".$data3['Skill'].".png' style='width:10%;'></a>";
                    else
                        $Skill_txt="";
                    $Regs_div.="<tr><td>".$data3['ID']."e</td><td>".$Off_Bat."</td><td>".$data3['Vehicule_Nbr']." ".GetVehiculeIcon($data3['Vehicule_ID'],$data3['Pays'],0,0,$Front).$Exp_txt.$HP_per.$Skill_txt.$Camo_txt."</td>
					<td>".$Move." ".$data3['Ville']."</td><td>".GetPosGr($data3['Position']).' '.GetPlace($data3['Placement'])."</td><td>".$Action."</td></tr>";
                }
                mysqli_free_result($result3);
            }
            echo "<h1>".$Div_Nom."</h1><h2>Ordres à la Division</h2><form action='index.php?view=ground_div_orders' method='post'>
				<input type='hidden' name='Officier' value='".$OfficierID."'>
				<input type='hidden' name='Division' value='".$Division."'>
				<table class='table table-striped' style='width:50%; overflow:auto;'>
					<thead><tr><th>Dénomination</th><th>Changement</th><th>Actuel</th></tr></thead>
					<tr><td align='left'>Base arrière</td><td>Via le véhicule de commandement</td><td>".GetData("Lieu","ID",$Base,"Nom")."</td></tr>
					<tr><td align='left'>Point de repli</td><td><select name='prepli'>".$Lieux."</select></td><td>".$repli."</td></tr>
					<tr><td align='left'>Point de ralliement</td><td><select name='prally'>".$Lieux."</select></td><td>".$rally."</td></tr>
					<tr><td align='left'>Point de ravitaillement</td><td><select name='pravit'>".$Lieux."</select></td><td>".$ravit."</td></tr>
					<tr><td align='left'>Objectif à défendre</td><td><select name='pdef'>".$Lieux."</select></td><td>".$def."</td></tr>
					<tr><td align='left'>Objectif à attaquer</td><td><select name='patk'>".$Lieux."</select></td><td>".$atk."</td></tr>
					<tr><td align='left'>Heure de l'attaque</td><td><select name='hatk'>
					<option value='6'>6h</option>
					<option value='7'>7h</option>
					<option value='8'>8h</option>
					<option value='9'>9h</option>
					<option value='10'>10h</option>
					<option value='11'>11h</option>
					<option value='12'>12h</option>
					<option value='13'>13h</option>
					<option value='14'>14h</option>
					<option value='15'>15h</option>
					<option value='16'>16h</option>
					<option value='17'>17h</option>
					<option value='18'>18h</option>
					<option value='19'>19h</option>
					<option value='20'>20h</option>
					<option value='21'>21h</option>
					<option value='22'>22h</option>
					<option value='23'>23h</option>
					</select></td><td>".$hatk."h</td></tr>			
					<tr><td colspan='4'><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></td></tr>
				</table></form>";
            if(!$Veh_Cdt)
                echo "<form action='index.php?view=ground_div_orders' method='post'><input type='hidden' name='Division' value='".$Division."'><input type='hidden' name='Mode' value='1'>
					<a href='#' class='popup'><img src='images/help.png'><span>Le véhicule de commandement est indispensable pour rallier les troupes de sa division, revendiquer une zone ou définir la base arrière de la division</span></a>
					<input type='submit' value='Créer le véhicule de commandement' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
            echo"<h2>Liste des Bataillons</h2><div style='height:400px; overflow:auto;'><table class='table'>
				<thead><tr><th>Nom</th><th>Commandant</th><th>Bataillons</th><th>Activité</th></tr></thead>".$bat_list."</table></div>";
            echo "<div class='alert alert-warning'>Vous pouvez affecter des unités à vos commandants de bataillons via le bouton Ordres des unités de votre division.
			<br>Les unités assignées à un bataillon apparaissent avec le numéro du bataillon en orange.</div>
			<h2>Bataillons de la Division</h2>
				<table class='table table-striped'><thead><tr>
					<th>Bataillon</th>
					<th>Commandant</th>
					<th>Troupes</th>
					<th>Lieu</th>
					<th>Position</th>
					<th>Ordres</th>
				</tr></thead>".$Regs_div."</table>";
        }
        else
        {
            /*echo "<html><head><title>Aube des Aigles : Etat-Major Terrestre</title><link href='test.css' rel='stylesheet' type='text/css'></head>
            <body background='images/bg_papier1.gif'><a title='Retour au menu' href='ground_menu.php'>Retour au menu</a><hr>";*/
            if($Division >0)
            {
                $con=dbconnecti();
                /*$result1=mysqli_query($con,"SELECT Nom,Avancement,Photo FROM Officier WHERE ID='$Cdt_Div'");
                $result2=mysqli_query($con,"SELECT o.Nom as Officer,o.Avancement,r.ID,r.Vehicule_ID,l.Nom as Ville
                FROM Officier as o,Regiment as r,Lieu as l WHERE r.Officier_ID=o.ID AND r.Lieu_ID=l.ID AND o.Division='$Division'");*/
                $resultd=mysqli_query($con,"SELECT Nom,Base,repli,rally,atk,hatk,def,ravit,Cdt FROM Division WHERE ID='$Division'");
                mysqli_close($con);
                if($resultd)
                {
                    while($data=mysqli_fetch_array($resultd,MYSQLI_ASSOC))
                    {
                        $Div_Nom=$data['Nom'];
                        $Retraite=$data['Base'];
                        $repli=$data['repli'];
                        $rally=$data['rally'];
                        $ravit=$data['ravit'];
                        $atk=$data['atk'];
                        $hatk=$data['hatk'];
                        $def=$data['def'];
                    }
                    mysqli_free_result($resultd);
                }
                if($repli >0)
                    $repli=GetData("Lieu","ID",$repli,"Nom");
                else
                    $repli="Aucun";
                if($rally >0)
                    $rally=GetData("Lieu","ID",$rally,"Nom");
                else
                    $rally="Aucun";
                if($atk >0)
                    $atk=GetData("Lieu","ID",$atk,"Nom");
                else
                    $atk="Aucun";
                if($def >0)
                    $def=GetData("Lieu","ID",$def,"Nom");
                else
                    $def="Aucun";
                if($ravit >0)
                    $ravit=GetData("Lieu","ID",$ravit,"Nom");
                else
                    $ravit="Aucun";
                if($Retraite)$Retraite_txt=GetData("Lieu","ID",$Retraite,"Nom");
                if($result1)
                {
                    while($datad=mysqli_fetch_array($result1,MYSQLI_ASSOC))
                    {
                        $Nomd=$datad['Nom'];
                        $Photod=$datad['Photo'];
                        $Graded=GetAvancement($datad['Avancement'],$country,0,1);
                    }
                    mysqli_free_result($result1);
                }
                //$Grade=GetAvancement($Avancement,$Pays,0,1);
                echo "<h1><img src='images/div/div".$Division.".png'> ".$Div_Nom."</h1>";
                echo "<div class='row'><div class='col-md-3'><table class='table table-striped'>
				<thead><tr><th colspan='2'>Ordres du Commandant <a href='index.php?view=aide_blitz' title='Fonctionnement de la chaine de commandement'><img src='images/help.png'></a></th></tr></thead>
				<tr><td align='left'>Point de repli</td><td>".$repli."</td></tr>
				<tr><td align='left'>Point de ralliement</td><td>".$rally."</td></tr>
				<tr><td align='left'>Point de ravitaillement</td><td>".$ravit."</td></tr>
				<tr><td align='left'>Objectif à défendre</td><td>".$def."</td></tr>
				<tr><td align='left'>Objectif à attaquer</td><td>".$atk."</td></tr>
				<tr><td align='left'>Heure de l'attaque</td><td>".$hatk."h</td></tr>
				<tr><td align='left'>Base arrière</td><th>".$Retraite_txt."</th></tr>
				<tr><td align='center' colspan='2'>".$carte_txt."</td></tr>
				<tr><td align='center' colspan='2'><div class='btn btn-primary'><a href='index.php?view=ground_news'>Ordre du jour</a></div></td></tr></table></div>";
                echo "<div class='col-md-9'><table class='table'><thead><tr><th>Officier d'Etat-Major</th><th>Commandant de Division</th></tr></thead><tr>
				<td>".Afficher_Image("images/persos/general".$country.$Photoo.".jpg","images/persos/general".$country."1.jpg",$Nomo,50)."<h3>".$Gradeo[0]." ".$Nomo."</h3></td>
				<td>".Afficher_Image("images/persos/general".$country.$Photod.".jpg","images/persos/general".$country."1.jpg",$Nomd,50)."<h3>".$Graded[0]." ".$Nomd."</h3></td>
				</tr></table></div></div>";
                /*echo"<h2>Compagnies</h2>
                <div style='overflow:auto; height: 640px;'><table class='table table-striped'><thead><th>Compagnies</th><th>Troupes</th><th>Cantonnement</th><th>Officier</th></tr></thead>";
                if($result2)
                {
                    while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
                    {
                        $Regs_div.="<tr><td>".$data['ID']."e</td><td><img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'></td><td>".$data['Ville']."</td><td align='left'>".$data['Officer']."</td></tr>";
                    }
                    mysqli_free_result($result2);
                }
                echo $Regs_div."</table></div>";*/
            }
            else
                echo "<h1>Division</h1><div class='alert alert-danger'>Votre officier doit faire partie d'une division pour accéder à ces informations.</div>";
        }
    }
}
else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';