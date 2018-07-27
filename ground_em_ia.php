<?php
require_once './jfv_inc_sessions.php';
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 xor $OfficierEMID >0)
{
    include_once './jfv_include.inc.php';
    include_once './jfv_ground.inc.php';
    include_once './jfv_txt.inc.php';
    $Ordre_ok=false;
    $country=$_SESSION['country'];
    $Unit=Insec($_POST['Reg']);
    if(!$Unit){
        $Unit=$_SESSION['reg'];
        if($_SESSION['msg'])
            $Alert='<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg'].'</div>';
        elseif($_SESSION['msg_red'])
            $Alert='<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_red'].'</div>';
        $_SESSION['reg']=false;
        $_SESSION['msg']=false;
        $_SESSION['msg_red']=false;
    }
    if($OfficierID >0)
    {
        $con=dbconnecti();
        $Admin=mysqli_result(mysqli_query($con,"SELECT Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
        $resulto=mysqli_query($con,"SELECT Avancement,Front,Credits FROM Officier WHERE ID = $OfficierID");
        if($resulto)
        {
            while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
            {
                $Avancement=$datao['Avancement'];
                $Front=$datao['Front'];
                $Credits=$datao['Credits'];
            }
            mysqli_free_result($resulto);
        }
    }
    elseif($OfficierEMID >0)
    {
        $con=dbconnecti();
        $Admin=mysqli_result(mysqli_query($con,"SELECT Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
        $resulto=mysqli_query($con,"SELECT Front,Pays,Armee,Credits FROM Officier_em WHERE ID = $OfficierEMID");
        if($resulto)
        {
            while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
            {
                $Front=$datao['Front'];
                $Pays=$datao['Pays'];
                $Armee=$datao['Armee'];
                $Credits=$datao['Credits'];
                $Trait=$datao['Trait'];
            }
            mysqli_free_result($resulto);
        }
        if($Front ==99)
        {
            $Planificateur=mysqli_result(mysqli_query($con,"SELECT Planificateur FROM GHQ WHERE Pays = $Pays"),0);
            if($OfficierEMID ==$Planificateur)
                $GHQ=true;
        }
        else
        {
            $result2=mysqli_query($con,"SELECT Commandant,Adjoint_Terre,Officier_Mer,Officier_Log FROM Pays WHERE Pays_ID = $country AND Front = $Front");
            if($result2)
            {
                while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
                {
                    $Commandant=$data['Commandant'];
                    $Adjoint_Terre=$data['Adjoint_Terre'];
                    $Officier_Mer=$data['Officier_Mer'];
                    $Officier_Log=$data['Officier_Log'];
                }
                mysqli_free_result($result2);
            }
        }
    }
    if($GHQ or $Admin)
    {
        $Ordres_Cdt=true;
        $Ordre_ok=true;
        $Ordres_Div=true;
        $GHQ=true;
        if($country ==3 or $country ==5 or $country ==10 or $country ==15 or $country ==17 or $country ==18 or $country ==19 or $country ==20 or $country ==35)$Nation_IA=true;
    }
    elseif($Commandant >0 and ($Commandant ==$OfficierEMID))
    {
        $Ordres_Cdt=true;
        $Ordre_ok=true;
    }
    elseif($Adjoint_Terre >0 and ($Adjoint_Terre ==$OfficierEMID))
    {
        $Ordres_Adjoint=true;
        $Ordre_ok=true;
    }
    elseif($Officier_Mer >0 and ($Officier_Mer ==$OfficierEMID))
    {
        $Ordres_Mer=true;
        $Ordre_ok=true;
    }
    elseif($Officier_Log >0 and ($Officier_Log ==$OfficierEMID))
    {
        $Ordres_Log=true;
        $Ordre_ok=true;
    }
    if($OfficierID >0 or $Armee >0)
    {
        $resultreg=mysqli_query($con,"SELECT Division,Bataillon FROM Regiment_IA WHERE ID = $Unit");
        if($resultreg)
        {
            while($datar=mysqli_fetch_array($resultreg,MYSQLI_ASSOC))
            {
                $Division=$datar['Division'];
                $Bataillon=$datar['Bataillon'];
            }
            mysqli_free_result($resultreg);
        }
        if($Division)
        {
            $resultdiv=mysqli_query($con,"SELECT Cdt,Armee,Base FROM Division WHERE ID = $Division");
            if($resultdiv)
            {
                while($data=mysqli_fetch_array($resultdiv,MYSQLI_ASSOC))
                {
                    $Division_Cdt=$data['Cdt'];
                    $Div_Armee=$data['Armee'];
                    $Div_Base=$data['Base'];
                }
                mysqli_free_result($resultdiv);
            }
        }
        if($Division_Cdt >0 and $Division_Cdt ==$OfficierID)
        {
            $Ordres_Div=true;
            $Ordre_ok=true;
        }
        elseif($Bataillon >0 and $Bataillon ==$OfficierID)
        {
            $Ordre_ok=true;
            $Ordres_Bat=true;
        }
        elseif($Div_Armee ==$Armee)
        {
            $Ordres_Armee=true;
            $Ordre_ok=true;
        }
    }
    if($Ordre_ok and $Unit >0)
    {
        $today=getdate();
        $Premium=mysqli_result(mysqli_query($con,"SELECT Premium FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
        $Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID = $country"),0);
        $Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
        $result5=mysqli_query($con,"SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Front,r.Division,r.Bataillon,r.Lieu_ID,r.Position,r.Placement,r.Move,r.Fret,r.HP,r.Moral,r.Experience,r.Skill,r.Matos,r.Mission_Lieu_D,r.Mission_Type_D,r.Transit_Veh,r.Autonomie,r.Ravit,r.NoEM,r.Avions,r.objectif,r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m,
		c.Nom as Veh_Nom,c.Type,c.HP as HP_max,c.Categorie,c.Fiabilite,c.Reput,c.Flak,c.Production,c.Stock,c.Repare,c.mobile,c.Fuel,c.Vitesse,c.Detection,c.Amphi,c.Carbu_ID,c.Conso,c.Arme_Art_mun,c.Arme_AT_mun,c.Arme_AA_mun,c.Arme_AT,c.Arme_Art,c.Arme_AA,c.Usine1,c.Usine2,c.Usine3,c.Hydra,c.Hydra_Nbr,c.Charge,c.Mountain,c.Portee,c.Sol_meuble,c.Radio,c.Tourelle,c.Autonomie as Jours_max,
		l.Pays,l.Occupant,l.Longitude,l.Latitude,l.Zone,l.BaseAerienne,l.Fleuve,l.Plage,l.Detroit,l.NoeudR,l.Port,l.Port_level,l.NoeudF,l.NoeudF_Ori,l.Pont_Ori,l.Pont,l.Port_Ori,l.Radar_Ori,l.Industrie,l.Impass,l.ValeurStrat,l.Meteo,l.Fortification,l.Flag,l.Flag_Air,l.Flag_Route,l.Flag_Gare,l.Flag_Pont,l.Flag_Port,l.Flag_Usine,l.Flag_Radar,l.Flag_Plage,l.Nom as Ville,
		l.Stock_Essence_87,l.Stock_Essence_100,l.Stock_Essence_1,l.Stock_Munitions_8,l.Stock_Munitions_13,l.Stock_Munitions_20,l.Stock_Munitions_30,l.Stock_Munitions_40,l.Stock_Munitions_50,l.Stock_Munitions_60,
		l.Stock_Munitions_75,l.Stock_Munitions_90,l.Stock_Munitions_105,l.Stock_Munitions_125,l.Stock_Munitions_150,l.Stock_Munitions_200,l.Stock_Munitions_300=0,l.Stock_Munitions_360,l.Garnison,l.Mines_m,
		l.Stock_Bombes_30,l.Stock_Bombes_50,l.Stock_Bombes_80,l.Stock_Bombes_125,l.Stock_Bombes_250,l.Stock_Bombes_300,l.Stock_Bombes_400,l.Stock_Bombes_500,l.Stock_Bombes_800,l.Stock_Bombes_1000,l.Stock_Bombes_2000,l.Recce
		FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.ID='$Unit'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_em_ia-res5');
        $result_s=mysqli_query($con,"SELECT ID,Nom,Rang,Infos FROM gnmh_aubedesaiglesnet1.Skills_r");
        $result_m=mysqli_query($con,"SELECT ID,Nom,Infos,Service FROM gnmh_aubedesaiglesnet1.Skills_m");
        mysqli_close($con);
        if($result5)
        {
            $data=mysqli_fetch_array($result5);
            if(!is_array($data))exit;
            else{
                $Type_Veh=$data['Type'];
                $HP_max=$data['HP_max'];
                $Reput_Renf=$data['Reput'];
                $Categorie=$data['Categorie'];
                $Fiabilite=$data['Fiabilite'];
                $Flak=$data['Flak'];
                $Production=$data['Production'];
                $Stock=floor($data['Stock']);
                $Repare=$data['Repare'];
                $mobile=$data['mobile'];
                $Fuel=$data['Fuel'];
                $Vitesse=$data['Vitesse'];
                $Detection=$data['Detection'];
                $Range=$data['Portee'];
                $Autonomie=$data['Autonomie'];
                $Jours_max=$data['Jours_max'];
                $Sol_meuble=$data['Sol_meuble'];
                $Radio=$data['Radio'];
                $Tourelle=$data['Tourelle'];
                $Hydra=$data['Hydra'];
                $Hydra_Nbr=$data['Hydra_Nbr'];
                $Avions=$data['Avions'];
                $Lieu=$data['Lieu_ID'];
                $Front=$data['Front'];
                $Pays_Ori=$data['Pays'];
                $Occupant=$data['Occupant'];
                $Flag=$data['Flag'];
                $Flag_Air=$data['Flag_Air'];
                $Flag_Gare=$data['Flag_Gare'];
                $Flag_Plage=$data['Flag_Plage'];
                $Flag_Pont=$data['Flag_Pont'];
                $Flag_Port=$data['Flag_Port'];
                $Flag_Radar=$data['Flag_Radar'];
                $Flag_Route=$data['Flag_Route'];
                $Flag_Usine=$data['Flag_Usine'];
                $Cie=$data['ID'];
                $Division_d=$data['Division'];
                $Bataillon=$data['Bataillon'];
                $HP=$data['HP'];
                $Move=$data['Move'];
                $BaseAerienne=$data['BaseAerienne'];
                $Fleuve=$data['Fleuve'];
                $Plage=$data['Plage'];
                $Detroit=$data['Detroit'];
                $NoeudR=$data['NoeudR'];
                $NoeudF=$data['NoeudF'];
                $Port=$data['Port'];
                $Port_level=$data['Port_level'];
                $NoeudF_Ori=$data['NoeudF_Ori'];
                $Pont=$data['Pont'];
                $Pont_Ori=$data['Pont_Ori'];
                $Port_Ori=$data['Port_Ori'];
                $Radar_Ori=$data['Radar_Ori'];
                $Industrie=$data['Industrie'];
                $Latitude=$data['Latitude'];
                $Longitude=$data['Longitude'];
                $Impass_ori=$data['Impass'];
                $Zone=$data['Zone'];
                $Meteo=$data['Meteo'];
                $Fortification=$data['Fortification'];
                $Garnison=$data['Garnison'];
                $Mines_m=$data['Mines_m'];
                $Vehicule_Nbr=$data['Vehicule_Nbr'];
                $Vehicule=$data['Vehicule_ID'];
                $Veh_Nom=$data['Veh_Nom'];
                $Placement=$data['Placement'];
                $Position=$data['Position'];
                $Recce=$data['Recce'];
                $Fret=$data['Fret'];
                $Moral=$data['Moral'];
                $Experience=$data['Experience'];
                $Skill=$data['Skill'];
                $Matos=$data['Matos'];
                $Transit_Veh=$data['Transit_Veh'];
                $Amphi=$data['Amphi'];
                $Carbu=$data['Carbu_ID'];
                $Conso=$data['Conso'];
                $Ravit=$data['Ravit'];
                $NoEM=$data['NoEM'];
                $Atk=$data['Atk'];
                $Usine1=$data['Usine1'];
                $Usine2=$data['Usine2'];
                $Usine3=$data['Usine3'];
                $Arme_AA=$data['Arme_AA'];
                $Arme_AT=$data['Arme_AT'];
                $Arme_Art=$data['Arme_Art'];
                $AT_muns=$data['Arme_AT_mun'];
                $Art_muns=$data['Arme_Art_mun'];
                $AA_muns=$data['Arme_AA_mun'];
                $Charge=$data['Charge'];
                $Mountain=$data['Mountain'];
                $Stock_Munitions_8=$data['Stock_Munitions_8'];
                $Stock_Munitions_13=$data['Stock_Munitions_13'];
                $Stock_Munitions_20=$data['Stock_Munitions_20'];
                $Stock_Munitions_30=$data['Stock_Munitions_30'];
                $Stock_Munitions_40=$data['Stock_Munitions_40'];
                $Stock_Munitions_50=$data['Stock_Munitions_50'];
                $Stock_Munitions_60=$data['Stock_Munitions_60'];
                $Stock_Munitions_75=$data['Stock_Munitions_75'];
                $Stock_Munitions_90=$data['Stock_Munitions_90'];
                $Stock_Munitions_105=$data['Stock_Munitions_105'];
                $Stock_Munitions_125=$data['Stock_Munitions_125'];
                $Stock_Munitions_150=$data['Stock_Munitions_150'];
                $Stock_Bombes_30=$data['Stock_Bombes_30'];
                $Stock_Bombes_50=$data['Stock_Bombes_50'];
                $Stock_Bombes_125=$data['Stock_Bombes_125'];
                $Stock_Bombes_250=$data['Stock_Bombes_250'];
                $Stock_Bombes_500=$data['Stock_Bombes_500'];
                $Stock_Bombes_80=$data['Stock_Bombes_80'];
                $Stock_Bombes_300=$data['Stock_Bombes_300'];
                $Stock_Bombes_400=$data['Stock_Bombes_400'];
                $Stock_Bombes_800=$data['Stock_Bombes_800'];
                $Stock_Bombes_1000=$data['Stock_Bombes_1000'];
                $Stock_Bombes_2000=$data['Stock_Bombes_2000'];
                $Stock_Essence_1=$data['Stock_Essence_1'];
                $Stock_Essence_87=$data['Stock_Essence_87'];
                $Stock_Essence_100=$data['Stock_Essence_100'];
                $ValeurStrat=$data['ValeurStrat'];
                $Ville=$data['Ville'];
                $Mission_Lieu_D=$data['Mission_Lieu_D'];
                $Mission_Type_D=$data['Mission_Type_D'];
                $objectif=$data['objectif'];
            }
            $Mois=substr($Date_Campagne,5,2);
            $Lands=GetAllies($Date_Campagne);
            if(IsAxe($country))
                $Allies=$Lands[1];
            else
                $Allies=$Lands[0];
            $Faction_Flag=GetData("Pays","ID",$Flag,"Faction");
            if($Matos ==25)$Amphi=true;
            $Range+=($Experience*2);
            $Max_Veh=GetMaxVeh($Type_Veh,$mobile,$Flak,500000);
            if($Categorie !=20 and $Categorie !=21 and $Categorie !=22 and $Categorie !=23 and $Categorie !=24 and $Categorie !=17 or ($mobile ==5 and !$Vehicule_Nbr))$Autonomie=1;
            if(!$Division_d)
                $Retraite=Get_Retraite($Front,$country,40);
            else
                $Retraite=GetData("Division","ID",$Division_d,"Base");
            if($today['mday'] >$data['Jour']+1)
                $Combat_flag=false;
            elseif($today['year'] >$data['Year_a'])
                $Combat_flag=false;
            elseif($today['mon'] >$data['Mois'])
                $Combat_flag=false;
            elseif($today['mday']!=$data['Jour'] and $today['hours']>=$data['Heure'])
                $Combat_flag=false;
            else
                $Combat_flag=true;
            if($today['mday'] >$data['Jour_m']+1)
                $Move_flag=false;
            elseif($today['year'] >$data['Year_m'])
                $Combat_flag=false;
            elseif($today['mon'] >$data['Mois_m'])
                $Move_flag=false;
            elseif($today['mday']!=$data['Jour_m'] and $today['hours']>=$data['Heure_m'])
                $Move_flag=false;
            else
                $Move_flag=true;
            if($data['Position'] ==12 or $data['Atk'] ==1 or $Combat_flag)$Pas_libre=true;

            //Matos & Skills
            $list_matos=Get_Matos_List($Categorie,$Type_Veh,$mobile,$Arme_AT);
            if($result_s)
            {
                while($data=mysqli_fetch_array($result_s,MYSQLI_ASSOC))
                {
                    $Skills_r[$data['ID']]="<b>".$data['Nom']." [".$data['Rang']."]</b><br>".$data['Infos'];
                }
                mysqli_free_result($result_s);
            }
            if($result_m)
            {
                while($datam=mysqli_fetch_array($result_m,MYSQLI_ASSOC))
                {
                    $Reg_matos[$datam['ID']]='<b>'.$datam['Nom'].'</b><br>'.$datam['Infos'];
                    if($datam['Service'] <=$Date_Campagne and in_array($datam['ID'],$list_matos)){
                        $matos_modal_txt.='<tr><td><a href="ground_em_ia_matos_do.php?reg='.$Unit.'&matos='.$datam['ID'].'"><img src="images/skills/skille'.$datam['ID'].'.png"></a><br>'.$datam['Nom'].'</td><td>'.$datam['Infos'].'</td></tr>';
                    }
                }
                mysqli_free_result($result_m);
            }
            $matos_modal='<div class="modal fade" id="modal-ravit" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Gestion du matériel
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </h2>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table"><thead><tr><th>Equipement</th><th>Infos</th></tr></thead>'.$matos_modal_txt.'</table>
                                    </div>
                                </div>
                            </div>
                        </div>';

            if($OfficierEMID ==$Commandant or $GHQ or $Admin or $Ordres_Mer)
            {
                $Type=$Type_Veh;
                if(!$Ordres_Mer){
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
                }
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
                elseif(!$Ordres_Mer)
                    $menu_cat_list.="<a class='btn btn-info' href='index.php?view=ground_em_ia_list_89'>Réserve</a>";
                if($Type ==95)
                    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
                else
                    $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_95'>Transit</a>";
                if($Type ==91)
                    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_91'>Mission</a>";
                elseif($Admin)
                    $menu_cat_list.="<a class='btn btn-success' href='index.php?view=ground_em_ia_list_91'>Mission</a>";
                if(!$Ordres_Mer){
                    if($Type ==92)
                        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_92'>Danger</a>";
                    elseif($GHQ or $Premium)
                        $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_92'>Danger</a>";
                    if($Type ==93)
                        $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_93'>Repli</a>";
                    elseif($GHQ or $Premium)
                        $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_93'>Repli</a>";
                }
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
                elseif(!$Ordres_Mer)
                    $menu_cat_list.="<a class='btn btn-warning' href='index.php?view=ground_em_ia_list_98'>Demob</a>";
                if($Type ==90)
                    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_90'>GHQ</a>";
                elseif($GHQ)
                    $menu_cat_list.="<a class='btn btn-danger' href='index.php?view=ground_em_ia_list_90'>GHQ</a>";
                if($Type ==97)
                    $menu_cat_list.="<a class='btn btn-primary' href='index.php?view=ground_em_ia_list_97'>Move</a>";
                elseif($GHQ)
                    $menu_cat_list.="<a class='btn btn-danger' href='index.php?view=ground_em_ia_list_97'>Move</a>";
                $menu_cat_list.='</p>';
            }
            if($Vehicule_Nbr >0 and (!$GHQ or $Admin or $Nation_IA)) //Demande mission & Situation unités de la zone
            {
                if(!$Mission_Lieu_D)
                    $Mission_Lieu_D='<i>Aucune</i>';
                else
                    $Mission_Lieu_D=GetData("Lieu","ID",$Mission_Lieu_D,"Nom");
                if(!$Mission_Type_D)
                    $Mission_Type_D_txt='<i>Indéfini</i>';
                else
                    $Mission_Type_D_txt=GetMissionType($Mission_Type_D);
                if($Faction >0)
                {
                    $dem_sup=false;
                    $con=dbconnecti();
                    //$Enis_lieu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Visible=1 AND r.Vehicule_Nbr >0"),0);
                    $Enis_IA_lieu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Visible=1 AND r.Vehicule_Nbr >0"),0);
                    $result_allies=mysqli_query($con,"SELECT r.ID,r.Pays,r.Vehicule_ID,r.Position,r.Division,r.Bataillon,r.Transit_Veh,r.Move,r.NoEM,r.Skill,r.Matos,p.Faction,c.mobile,c.Categorie,c.Vitesse,c.Arme_AT,c.Arme_Art,c.Portee,
                    r.Atk,r.Atk_time,DATE_FORMAT(r.Atk_time,'%e') as Jour,DATE_FORMAT(r.Atk_time,'%Hh%i') as Heure,DATE_FORMAT(r.Atk_time,'%m') as Mois,DATE_FORMAT(r.Atk_time,'%Y') as Year_a,
                    r.Move_time,DATE_FORMAT(r.Move_time,'%e') as Jour_m,DATE_FORMAT(r.Move_time,'%Hh%i') as Heure_m,DATE_FORMAT(r.Move_time,'%m') as Mois_m,DATE_FORMAT(r.Move_time,'%Y') as Year_m
                    FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND (p.Faction='$Faction' OR r.Visible=1) AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0");
                    /*if($OfficierID)
                    {
                        $result_sections=mysqli_query($con,"SELECT SectionID FROM Regiment as r,Sections as s WHERE s.OfficierID=r.Officier_ID AND r.Lieu_ID='$Lieu' AND s.OfficierID='$OfficierID'");
                        if($result_sections)
                        {
                            while($datas=mysqli_fetch_array($result_sections,MYSQLI_ASSOC))
                            {
                                if(in_array(2,$datas))$Sec_Trans=true;
                                if(in_array(3,$datas))$Sec_Log=true;
                                if(in_array(7,$datas))$Sec_EM=true;
                            }
                            mysqli_free_result($result_sections);
                        }
                    }*/
                    if($result_allies)
                    {
                        while($data=mysqli_fetch_array($result_allies,MYSQLI_ASSOC))
                        {
                            $Div_units=false;
                            $Ordres_Armee_Ici=false;
                            $skill_icons=false;
                            if($data['Faction'] ==$Faction)
                            {
                                $Def_txt='Aucun';
                                $Init_txt='Aucun';
                                if($data['Position'] ==1)
                                {
                                    if(($data['mobile'] ==1 or $data['mobile'] ==2 or $data['mobile'] ==6 or $data['mobile'] ==7) and $data['Vitesse'] >10)
                                        $Def_txt='Contre-attaque';
                                    if($data['Categorie'] ==15)
                                        $Def_txt='Anti-aérien';
                                }
                                elseif($data['Position'] ==10)
                                {
                                    if($data['Categorie'] ==5 or $data['Categorie'] ==6 or $data['Categorie'] ==9)
                                        $Def_txt='En ligne';
                                    elseif($data['mobile'] ==3 and $data['Arme_AT'] >0)
                                        $Def_txt='En ligne';
                                }
                                elseif($data['Position'] ==3)
                                {
                                    if($data['Arme_AT'] >0)
                                        $Def_txt='Embuscade';
                                    elseif($data['Arme_Art'] >0)
                                        $Def_txt='Contre-batterie';
                                }
                                elseif($data['Position'] ==5)
                                {
                                    if($data['Arme_Art'] >0)
                                        $Def_txt='Contre-batterie';
                                    if($data['Categorie'] ==15)
                                        $Def_txt='Anti-aérien';
                                }
                                elseif($data['Position'] ==14)
                                    $Def_txt='Sentinelle';
                                elseif($data['Position'] ==21)
                                    $Def_txt='Escorte';
                                elseif($data['Position'] ==23)
                                    $Def_txt='Contre-batterie';
                                elseif($data['Position'] ==24)
                                    $Def_txt='ASM';
                                if($data['Division'] and $data['Pays']==$country)
                                {
                                    $Armee_unit=mysqli_result(mysqli_query($con,"SELECT Armee FROM Division WHERE ID=".$data['Division']),0);
                                    $Div_units="<img src='images/div/div".$data['Division'].".png'>";
                                    if($Armee_unit ==$Armee)$Ordres_Armee_Ici=true;
                                    if($Ordres_Cdt or $Ordres_Armee_Ici)
                                        $Div_units="<a class='btn btn-default btn-xs' href='index.php?view=ground_em_div&id=".$data['Division']."'>".$Div_units."</a>";
                                }
                                if(($Ordres_Cdt or $Ordres_Armee_Ici) and $data['Pays']==$country)
                                {
                                    if(!$data['Officier_ID'])
                                    {
                                        if($data['Move'])
                                            $Led="<span class='i-flex led_red'></span>";
                                        else
                                            $Led="<span class='i-flex led_green'></span>";
                                    }
                                    else
                                        $Led=false;
                                    if($today['mday'] >$data['Jour']+1)
                                        $Combat_flag=false;
                                    elseif($today['mon'] >$data['Mois'])
                                        $Combat_flag=false;
                                    elseif($today['year'] >$data['Year_a'])
                                        $Combat_flag=false;
                                    elseif($today['mday']!=$data['Jour'] and $today['hours']>=$data['Heure'])
                                        $Combat_flag=false;
                                    else
                                        $Combat_flag=true;
                                    if($today['mday'] >$data['Jour_m']+1)
                                        $Move_flag=false;
                                    elseif($today['mon'] >$data['Mois_m'])
                                        $Move_flag=false;
                                    elseif($today['year'] >$data['Year_m'])
                                        $Combat_flag=false;
                                    elseif($today['mday']!=$data['Jour_m'] and $today['hours']>=$data['Heure_m'])
                                        $Move_flag=false;
                                    else
                                        $Move_flag=true;
                                    if($data['Position'] ==12)
                                        $Cie_ID="<span class='label label-danger'>En Vol</span>";
                                    elseif($data['Atk'] ==1 or $Combat_flag)
                                        $Cie_ID="<span class='text-danger'>En Combat<br>jusque ".$data['Heure']."</span>";
                                    elseif($data['mobile'] !=5 and ($data['Move'] ==1 or $Move_flag))
                                        $Cie_ID="<span class='text-danger'>Mouvement<br>jusque ".$data['Heure_m']."</span>";
                                    elseif($data['NoEM'])
                                        $Cie_ID="<span class='btn btn-danger'>GHQ</span>";
                                    elseif(!$OfficierEMID and $data['Bataillon'] !=$OfficierID)
                                        $Cie_ID="<span class='btn btn-default'>".$data['ID']."</span>";
                                    else
                                    {
                                        if($data['ID']!=$Unit)
                                            $Cie_ID="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'>".$Led."
                                            <input type='Submit' value='".$data['ID']."' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                                        else
                                            $Cie_ID="<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$data['ID']."'>".$Led."
                                            <input type='Submit' value='".$data['ID']."' class='btn btn-primary' onclick='this.disabled=true;this.form.submit();'></form>";
                                        if($data['Skill'])
                                            $skill_icons="<a href='#' class='popup'><img src='images/skills/skillo".$data['Skill'].".png' style='max-width:20%;'><span>".$Skills_r[$data['Skill']]."</span></a>";
                                        else
                                            $skill_icons='';
                                        if($data['Matos'])
                                            $skill_icons.="<a href='#' class='popup'><img src='images/skills/skille".$data['Matos'].".png' style='max-width:20%;'><span>".$Reg_matos[$data['Matos']]."</span></a>";
                                    }
                                }
                                else
                                    $Cie_ID=$data['ID'].'e ';
                                $units_allies.="<tr><td>".$Cie_ID."</td><td>".$Div_units."</td>
                                <td><img src='".$data['Pays']."20.gif'></td>
                                <td>".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front).$skill_icons."</td>
                                <td>".GetPosGr($data['Position'])."</td>
                                <td>".$Def_txt."</td><td>".$Init_txt."</td></tr>";
                            }
                            else
                            {
                                if($Range >=$data['Portee'] and $Type_Veh !=1 and $Type_Veh !=4 and $Type_Veh !=12 and $Type_Veh !=13 and $Categorie !=6)
                                    $Init_txt="<a href='#' class='popup'><div class='i-flex led_green'></div><span>A portée d'une attaque</span></a>";
                                elseif($Categorie ==6 or $Type_Veh ==1 or $Type_Veh ==4 or $Type_Veh ==12 or $Type_Veh ==13)
                                    $Init_txt="<a href='#' class='popup'><div class='i-flex led_red'></div><span>Votre unité ne peut pas effectuer d'attaque sur cette cible</span></a>";
                                else
                                    $Init_txt="<a href='#' class='popup'><div class='i-flex led_red'></div><span>Actuellement hors de portée</span></a>";
                                if($data['Position'] ==8)
                                    $Appui='Sous le feu';
                                else
                                    $Appui='Inconnu';
                                if($data['Transit_Veh'] ==5000)$data['Vehicule_ID']=5000;
                                $units_allies.="<tr><td>Inconnu</td><td>Inconnu</td>
                                <td><img src='".$data['Pays']."20.gif'></td>
                                <td>".GetVehiculeIcon($data['Vehicule_ID'],$data['Pays'],0,0,$Front)."</td>
                                <td>Inconnu</td><td>Inconnu</td><td>".$Init_txt."</td></tr>";
                                $HasHostiles=true;
                            }
                        }
                        mysqli_free_result($result_allies);
                        if($units_allies)
                        {
                            if($Meteo <-69)
                                $Meteo_help_txt='Portée de bombardement réduite de moitié<br>Portée d\'attaque réduite de 75%<br>Déplacement naval impossible';
                            elseif($Meteo <-9)
                                $Meteo_help_txt='Portée d\'attaque réduite de moitié';
                            else
                                $Meteo_help_txt='Météo clémente pour une attaque';
                            $units_print="<div class='panel panel-war'>
                            <div class='panel-heading'>
                                <div class='row'>
                                    <div class='col-md-4'><a href='#' class='popup'><form action='index.php?view=em_city_ground' method='post'><input type='hidden' name='id' value='".$Lieu."'><input type='submit' value='".$Ville."' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form><span>Visualiser la situation de ".$Ville."</span></a></div>
                                    <div class='col-md-4 text-center'>Situation ".GetPlace($Placement)."</div>
                                    <div class='col-md-4 text-center'><a href='#' class='popup'><img src='images/meteo".$Meteo.".gif'><span>".$Meteo_help_txt."</span></a></div>
                                </div>
                            </div>";
                            $Couv_Nbr=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Cible='$Lieu' AND j.Couverture='$Lieu' AND j.Avion >0 AND p.Faction='$Faction' AND j.Actif='1' AND (j.Alt BETWEEN 100 AND 3000)"),0);
                            if($Couv_Nbr)
                                $units_air_cover='<div class="alert alert-warning" style="max-width: 600px;"><img src="images/ia_combat.png"> <b>'.$Couv_Nbr.'</b> avions de chasse couvrent les unités situées à '.$Ville.'</div>';
                            else
                                $units_air_cover='<div class="alert alert-danger">Aucun avion de chasse ne couvre les unités situées à '.$Ville.'. Demandez un appui aérien!</div>';
                            $units_print.="<div class='panel-body' style='overflow:auto;'>".$units_air_cover."<table class='table table-condensed table-striped'><thead><tr><th>Unité</th><th>Division</th><th>Pays</th><th>Troupes</th><th>Position</th><th>Défense</th><th>Attaque</th></tr></thead>".$units_allies."</table></div></div>";
                        }
                    }
                    $Enis_lieu+=$Enis_IA_lieu;
                    if($Vehicule >=5000 and ($Zone ==6 or $Plage or $Port_Ori))
                    {
                        if($Enis_lieu)
                            $dem_sup.="<option value='11'>Attaque Navale (Demande un mitraillage des unités navales ennemies détectées)</option><option value='12'>Bombardement naval (Demande un bombardement des unités navales ennemies détectées)</option><option value='13'>Torpillage (Demande un torpillage des unités navales ennemies détectées)</option>";
                        $dem_sup.="<option value='7'>Patrouille (Demande à la chasse de protéger votre unité des attaques aériennes)</option><option value='29'>Patrouille ASM (Demande une intervention anti-sous-marine)</option><option value='5'>Reco tactique (Demande une identification des éventuelles unités terrestres ennemies)</option>";
                    }
                    elseif($Vehicule <5000)
                    {
                        if($Enis_lieu and $Zone !=6)
                            $dem_sup.="<option value='1'>Appui rapproché (Demande un mitraillage des unités terrestres ennemies détectées)</option><option value='2'>Bombardement tactique (Demande un bombardement des unités terrestres ennemies détectées)</option>";
                        $dem_sup.="<option value='7'>Patrouille (Demande à la chasse de protéger votre unité des attaques aériennes)</option><option value='5'>Reco tactique (Demande une identification des éventuelles unités terrestres ennemies)</option>";
                        if($Zone !=6 and $Faction_Flag !=$Faction)
                            $dem_sup.="<option value='15'>Reco stratégique (Demande une identification des infrastructures)</option>";
                    }
                    if($Mission_Type_D)
                    {
                        $output_dem.="<div class='panel panel-war'><div class='panel-heading'>Demande d'appui aérien en cours</div><div class='panel-body'>
                                <form action='index.php?view=ground_em_ia_go' method='post'>
                                <input type='hidden' name='reset' value='3'>
                                <input type='hidden' name='Unit' value='".$Unit."'>
                                <table class='table'>
                                <thead><tr><th>Mission</th><th>Lieu</th><th></th></tr></thead>
                                <tr><td>".$Mission_Type_D_txt."</td><td>".$Mission_Lieu_D."</td>
                                <td><input type='submit' value='Annuler' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></td></tr></table></form></div></div>";
                    }
                    elseif($Position !=12 and $Position !=13){
                        $output_dem.="<div class='panel panel-war'><div class='panel-heading'>Demande d'appui aérien</div><div class='panel-body'>
                        <form action='index.php?view=ground_em_ia_go' method='post'>
                        <input type='hidden' name='Unit' value='".$Unit."'>
                        <input type='hidden' name='Cible_dem' value='".$Lieu."'>
                        <input type='hidden' name='reset' value='5'>
                        <select name='Type_dem' class='form-control' style='max-width:200px; display:inline;'>".$dem_sup."</select>
                        <input type='submit' value='Valider' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></div></div>";
                    }
                }
            }
            if($Vehicule >5000 or $Transit_Veh ==5000) //Navire
            {
                $Long_range=false;
                $Dist_max=500;
                if(!$Vehicule_Nbr)
                    $Placements='<span class="text-danger">Remorqué</span>';
                else
                    $Placements='<span class="text-primary">En mer</span>';
                if(!$Move and $Meteo >-75 and $Lieu and $Autonomie >0) //Déplacement
                {
                    if($Position !=25)
                    {
                        $con=dbconnecti();
                        $resultsmoke=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu' AND r.Position=37 AND c.Arme_Art>0 AND r.Vehicule_Nbr>0"),0);
                        //$resulti2=mysqli_query($con,"SELECT COUNT(*),MAX(c.Vitesse) FROM Regiment as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Position=27 AND c.Type>14 AND r.Vehicule_Nbr>0 AND c.Vitesse>35 AND r.HP>c.HP/2");
                        $Enis_Interdiction=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Position=27 AND c.Type>14 AND r.Vehicule_Nbr>0 AND c.Vitesse>35 AND r.HP>c.HP/2"),0);
                        mysqli_close($con);
                        if($resultsmoke)$smoke_txt="<br>Des navires alliés tenteront de créer un écran de fumée pour tenter de forcer le passage.";
                        if($Enis_Interdiction and $Vehicule_Nbr >0)
                            $output_dest="<div class='alert alert-danger'>Des navires ennemis en interdiction tentent d'empêcher tout déplacement".$smoke_txt."!</div>";
                        if($G_Treve or ($G_Treve_Med and $Front ==2) or ($G_Treve_Est_Pac and ($Front ==1 or $Front ==4 or $Front ==3)))$query_treve=" AND Flag='$country'";
                    }
                    if($Lieu ==1984 or $Lieu ==1986 or $Lieu ==1987 or $Lieu ==1988) //Cote Espagnole
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE (Port >0 OR Zone=6 OR Plage=1) AND Latitude <50 AND Longitude <-5.35 AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Lieu ==198 or $Lieu ==199 or $Lieu ==449 or $Lieu ==459 or $Lieu ==500 or $Lieu ==507 or $Lieu ==701 or $Lieu ==750 or $Lieu ==819 or $Lieu ==1113 or $Lieu ==1180 or $Lieu ==1181 or $Lieu ==1562 or $Lieu ==2550 or $Lieu ==2854 or $Lieu ==2909 or $Lieu ==2910 or $Lieu ==2924 or $Lieu ==2925) //Mer Adriatique
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE ID IN(198,199,449,459,500,506,507,701,750,819,1113,1180,1181,1562,2550,2854,2909,2910,2924,2925) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Lieu ==269 or $Lieu ==494 or $Lieu ==593 or $Lieu ==731 or $Lieu ==942 or $Lieu ==1123 or $Lieu ==1609 or $Lieu ==2302 or $Lieu ==2551) //Mer Irlande
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE ID IN(269,494,495,593,731,942,1123,1154,1609,2302,2551) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Lieu ==2026 or $Lieu ==2016 or $Lieu ==2011 or $Lieu ==2030 or $Lieu ==2031 or $Lieu ==2027 or $Lieu ==2028) //Corne Afrique
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >32 AND Longitude <46 AND Latitude <30 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Lieu ==279 or $Lieu ==280 or $Lieu ==295 or $Lieu ==296 or $Lieu ==312 or $Lieu ==470 or $Lieu ==488 or $Lieu ==489 or $Lieu ==490 or $Lieu ==592 or $Lieu ==729 or $Lieu ==730 or $Lieu ==880 or $Lieu ==952 or $Lieu ==1138 or $Lieu ==1364 or $Lieu ==1614 or $Lieu ==2540 or $Lieu ==3386) //Pas de Mer Irlande depuis l'est de l'angleterre
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE ((Longitude >0.53 AND Longitude <14 AND Latitude >44 AND Latitude <51.39) OR (Longitude >-1.62 AND Longitude <14 AND Latitude >=51.39 AND Latitude <55) OR (Longitude >-4 AND Longitude <14 AND Latitude >44 AND Latitude >=55)) AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Lieu ==912) //Port-Saïd vers Med-Est et Suez
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >27 AND Latitude <35 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',2016,2026) ORDER BY Nom ASC";
                    elseif($Lieu ==2015) //Suez vers Port-Saïd et Mer Rouge
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >32 AND Latitude <31.3 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu') ORDER BY Nom ASC";
                    elseif($Front ==2)
                    {
                        if($Longitude <15.6 and $Latitude <45.3) //Pas de Mer Adriatique si on vient de l'ouest du détroit de Messine
                            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-5.51 AND Longitude <73 AND Latitude <44.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',198,199,449,458,459,500,505,506,507,701,750,819,1113,1180,1181,1562,2550) ORDER BY Nom ASC";
                        elseif($country ==2 or $country ==5 or $country ==7){
                            if($Latitude >30.35)
                                $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-5.51 AND Longitude <40 AND Latitude <44.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',262,2015,2026) ORDER BY Nom ASC";
                            else
                                $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-5.51 AND Longitude <73 AND Latitude <44.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',262) ORDER BY Nom ASC";
                        }
                        else
                            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-5.51 AND Longitude <36 AND Latitude <44.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN ('$Lieu',262,2015,2016,2026) ORDER BY Nom ASC";
                    }
                    elseif($Front ==1)
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >13 AND Longitude <45 AND Latitude >41 AND Latitude<=50.5 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Front ==4)
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >13 AND Longitude <45 AND Latitude >50.5 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Front ==5)
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >-50 AND Longitude <60 AND Latitude >60 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Front ==3)
                    {
                        $Long_range=true;
                        /*if($country ==9 and $Date_Campagne >'1941-12-01' and $Date_Campagne <'1941-12-08')
                            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >67 AND (((Port >0 OR Plage=1) AND Flag=9) OR Zone=6) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        else*/if($Date_Campagne <'1941-01-01')
                            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Flag='$country' AND Longitude >67 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif(!$Faction)
                            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Flag='$country' AND Longitude >67 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Longitude <99.5)
                            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >67 AND Longitude <100.5 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Longitude >99.5)
                            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >99.4 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                        else
                            $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >67 AND (Port >0 OR Zone=6 OR Plage=1)".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                    }
                    elseif($Lieu ==344 or $Lieu ==503) //Gibraltar
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <-5.34 AND Latitude <50 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Latitude >67)
                    {
                        $Long_range=true;
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <60 AND Latitude >60 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    }
                    elseif($Longitude <-45)
                    {
                        $Long_range=true;
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <-10 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    }
                    elseif($Longitude <=-10)
                    {
                        if($Lieu ==1992)
                            $Dist_max=800;
                        else
                            $Dist_max=1000;
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <=-8 AND (Zone=6 OR Port >0 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    }
                    elseif($Longitude <-0.5 and $Longitude >-10 and $Latitude >39.99 and $Latitude <48) //Golfe de Gascogne
                    {
                        $Dist_max=306;
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <-0.5 AND Latitude <48.5 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    }
                    elseif($Longitude <-5.5 and $Latitude <45) //Côte Ouest Afrique
                    {
                        $Dist_max=521;
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <-5.51 AND (Port >0 OR Zone=6 OR Plage=1) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    }
                    elseif($Longitude >9 and $Latitude >53.8) //Mer Baltique
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >7.99 AND Latitude >53.8 AND (Zone=6 OR Port>0 OR Plage=1) ORDER BY Nom ASC";
                    elseif($Longitude >=7 and $Longitude <=11.5 and $Latitude >=57) //Skagerrak + Kattegat
                    {
                        $Dist_max=250;
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude >=4.5 AND Longitude <14 AND Latitude >55.5 AND (Zone=6 OR Port>0 OR Plage=1) ORDER BY Nom ASC";
                    }
                    else
                    {
                        if($Lieu ==3155)$Lieu_exc=730;
                        elseif($Lieu ==730)$Lieu_exc=3155;
                        if($Longitude <=-8)
                            $Dist_max=425;
                        else
                            $Dist_max=250;
                        $query="SELECT ID,Nom,Longitude,Latitude,Impass,Port,Zone,Flag FROM Lieu WHERE Longitude <=8.6 AND Latitude >44 AND (Port >0 OR Zone=6 OR Plage=1) AND ID NOT IN(".$Lieu.$Lieu_exc.") ORDER BY Nom ASC";
                    }
                    if($Long_range)
                    {
                        if($Transit_Veh ==5000)
                            $Dist_max=1200;
                        else
                            $Dist_max=$Fuel; //1200
                        if($Dist_max >2400)$Dist_max=2400;
                    }
                    $con=dbconnecti();
                    $result=mysqli_query($con,$query);
                    mysqli_close($con);
                    if($result)
                    {
                        while($data2=mysqli_fetch_array($result))
                        {
                            $lieux_obj.='<option value="'.$data2[0].'">'.$data2[1].'</option>';
                            $coord=0;
                            $CT_front=0;
                            $Distance=GetDistance(0,0,$Longitude,$Latitude,$data2[2],$data2[3]);
                            if($Distance[0] <=$Dist_max)
                            {
                                if($Zone !=6 and $data2['Zone'] !=6 and $Lieu !=2015 and $data2['ID'] !=2015) //Exception Suez
                                    $bla=false;//Pas de déplacement de port à port
                                else
                                {
                                    $Impass=$data2[4];
                                    $sensh='';
                                    $sensv='';
                                    if($Longitude >$data2[2])
                                    {
                                        $sensh='Ouest';
                                        $coord+=2;
                                        if($Impass ==2 or $Impass ==3 or $Impass ==4 or $Impass_ori ==6 or $Impass_ori ==7 or $Impass_ori ==8)
                                            $CT_front=4;
                                    }
                                    elseif($Longitude < $data2[2])
                                    {
                                        $sensh='Est';
                                        $coord+=1;
                                        if($Impass ==6 or $Impass ==7 or $Impass ==8 or $Impass_ori ==2 or $Impass_ori ==3 or $Impass_ori ==4)
                                            $CT_front=4;
                                    }
                                    if($sensh)
                                    {
                                        if($Latitude >$data2[3]+0.5)
                                        {
                                            $sensv='Sud';
                                            $coord+=20;
                                            if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
                                                $CT_front=4;
                                        }
                                        elseif($Latitude <$data2[3]-0.5)
                                        {
                                            $sensv='Nord';
                                            $coord+=10;
                                            if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
                                                $CT_front=4;
                                        }
                                    }
                                    else
                                    {
                                        if($Latitude >$data2[3])
                                        {
                                            $sensv='Sud';
                                            $coord+=20;
                                            if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
                                                $CT_front=4;
                                        }
                                        elseif($Latitude <$data2[3])
                                        {
                                            $sensv='Nord';
                                            $coord+=10;
                                            if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
                                                $CT_front=4;
                                        }
                                    }
                                    $sens=$sensv.' '.$sensh;
                                    if(!$CT_front)
                                    {
                                        if($data2['Zone'] ==6)
                                            $icone="<img src='images/zone".$data2['Zone'].".jpg'>";
                                        else
                                            $icone="<a href='#' class='popup'><img src='images/map/lieu_port".$data2['Flag'].".png'><span><b>Port</b> Le port doit être contrôlé par votre faction pour pouvoir bénéficier des infrastructures.</span></a>";
                                        $modal_conso='<div class="alert alert-danger">Le déplacement rendra l\'unité inaccessible pendant 24h';
                                        $modal_conso.='<br>L\'unité arrivera en mouvement, pensez à changer sa position une fois arrivé à destination';
                                        $choix="<tr><td><a href='#' class='lien' data-toggle='modal' data-target='#modal-dest-".$data2[0]."'>".$data2[1]."</a></td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
                                        $lieux_modal.='<div class="modal fade" id="modal-dest-'.$data2[0].'" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title">Déplacement
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </h2>
                                                                </div>
                                                                <div class="modal-body">
                                                                <img class="img-flex" src="images/nav_evade.jpg">
                                                                <div class="alert alert-warning">La '.$Cie.'e flottille composée de '.$Vehicule_Nbr.' '.$Veh_Nom.' se déplacera vers <b>'.$data2[1].'</b></div>
                                                                <form action="ground_em_ia_go.php" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu.'"><input type="hidden" name="cible" value="'.$data2[0].'"><input class="btn btn-danger" type="submit" value="confirmer"></form>'.$modal_conso.'</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';
                                        if($coord ==1) //Est
                                            $Est_txt.=$choix;
                                        elseif($coord ==2) //Ouest
                                            $Ouest_txt.=$choix;
                                        elseif($coord ==10) //Nord
                                            $Nord_txt.=$choix;
                                        elseif($coord ==20) //Sud
                                            $Sud_txt.=$choix;
                                        elseif($coord ==11) //NE
                                            $NE_txt.=$choix;
                                        elseif($coord ==21) //SE
                                            $SE_txt.=$choix;
                                        elseif($coord ==12) //NO
                                            $NO_txt.=$choix;
                                        elseif($coord ==22) //SO
                                            $SO_txt.=$choix;
                                    }
                                }
                            }
                            elseif($Admin)
                            {
                                if($data2['Zone'] ==6)
                                    $icone="<img src='images/zone".$data2['Zone'].".jpg'>";
                                else
                                    $icone="<img src='images/map/lieu_port".$data2['Flag'].".png'>";
                                $choix="<tr><td>".$data2[1]."</td><td>".$icone."</td><td>".$Distance[0]."km</td></tr>";
                                if($coord ==1) //Est
                                    $Est_txt.=$choix;
                                elseif($coord ==2) //Ouest
                                    $Ouest_txt.=$choix;
                                elseif($coord ==10) //Nord
                                    $Nord_txt.=$choix;
                                elseif($coord ==20) //Sud
                                    $Sud_txt.=$choix;
                                elseif($coord ==11) //NE
                                    $NE_txt.=$choix;
                                elseif($coord ==21) //SE
                                    $SE_txt.=$choix;
                                elseif($coord ==12) //NO
                                    $NO_txt.=$choix;
                                elseif($coord ==22) //SO
                                    $SO_txt.=$choix;
                            }
                        }
                        mysqli_free_result($result);
                    }
                }
                if($Vehicule ==5001 or $Vehicule ==5124 or $Vehicule ==5392) //cargos
                {
                    $Divisions='Etat-Major';
                    $Positions='En position';
                    $Pos_titre='Fret';
                    if($Vehicule ==5392)
                        $Pos_ori="Cargaison";
                    elseif(!$Division_d)
                    {
                        if(!$Fret)
                            $Pos_ori="Vide";
                        elseif($Fret ==1001)
                            $Pos_ori="250000L Diesel";
                        elseif($Fret ==1087)
                            $Pos_ori="250000L Essence 87";
                        elseif($Fret ==1100)
                            $Pos_ori="250000L Essence 100";
                        elseif($Fret ==1)
                            $Pos_ori="Troupes";
                        elseif($Fret ==930)
                            $Pos_ori="10000 Fusées";
                        elseif($Fret ==80)
                            $Pos_ori="5000 Rockets";
                        elseif($Fret ==200)
                            $Pos_ori="Troupes IA";
                        elseif($Fret ==300)
                            $Pos_ori="1250 Charges";
                        elseif($Fret ==400)
                            $Pos_ori="1250 Mines";
                        elseif($Fret ==800)
                            $Pos_ori="500 Torpilles";
                        elseif($Fret ==888)
                            $Pos_ori="Lend-Lease";
                        elseif($Fret ==1200)
                            $Pos_ori="Obus de 200mm";
                        elseif($Fret ==9050 or $Fret ==9125 or $Fret ==9250 or $Fret ==9500)
                            $Pos_ori="Bombes de ".substr($Fret,1)."kg";
                        elseif($Fret > 9999)
                            $Pos_ori="Bombes de ".substr($Fret,0,-1)."kg";
                        else
                            $Pos_ori="Obus de ".$Fret."mm";
                    }
                    else
                    {
                        if(!$Fret)
                            $Pos_ori="Vide";
                        elseif($Fret ==1001)
                            $Pos_ori="Diesel";
                        elseif($Fret ==1087)
                            $Pos_ori="Essence 87";
                        elseif($Fret ==1100)
                            $Pos_ori="Essence 100";
                        elseif($Fret ==200)
                            $Pos_ori="Troupes IA";
                        elseif($Fret ==300)
                            $Pos_ori="Charges";
                        elseif($Fret ==400)
                            $Pos_ori="Mines";
                        elseif($Fret ==800)
                            $Pos_ori="Torpilles";
                        elseif($Fret ==888)
                            $Pos_ori="Lend-Lease";
                        elseif($Fret ==1200)
                            $Pos_ori="Obus de 200mm";
                        elseif($Fret >9000 or $Fret ==80 or $Fret ==930 or $Fret ==1)
                            $Pos_ori="Fret incompatible";
                        else
                            $Pos_ori="Obus de ".$Fret."mm";
                    }
                    if($Vehicule_Nbr >0)
                    {
                        if($Vehicule ==5392) //Flottille ravit
                        {
                            $con=dbconnecti();
                            $resultd=mysqli_query($con,"SELECT d.* FROM Regiment_IA as r,Depots as d WHERE r.ID=d.Reg_ID AND r.ID='$Unit'");
                            mysqli_close($con);
                            if($resultd)
                            {
                                while($datad=mysqli_fetch_array($resultd))
                                {
                                    $flr_info="<h3>Cargaison de la flottille</h3><div style='overflow:auto;'><table class='table'>
                                        <thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
                                        <th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th></tr></thead>
                                        <tr><td>".$datad['Stock_Essence_87']."</td><td>".$datad['Stock_Essence_100']."</td><td>".$datad['Stock_Essence_1']."</td><td>".$datad['Stock_Munitions_8']."</td><td>".$datad['Stock_Munitions_13']."</td>
                                        <td>".$datad['Stock_Munitions_20']."</td><td>".$datad['Stock_Munitions_30']."</td><td>".$datad['Stock_Munitions_40']."</td><td>".$datad['Stock_Munitions_50']."</td><td>".$datad['Stock_Munitions_60']."</td>
                                        <td>".$datad['Stock_Munitions_75']."</td><td>".$datad['Stock_Munitions_90']."</td><td>".$datad['Stock_Munitions_105']."</td><td>".$datad['Stock_Munitions_125']."</td><td>".$datad['Stock_Munitions_150']."</td></tr></table></div>";
                                }
                                mysqli_free_result($resultd);
                            }
                            else
                                $flr_info="<h3>Cargaison de la flottille</h3><div class='alert alert-danger'>Une erreur est survenue, veuillez le signaler sur le forum</div>";
                        }
                        if($ValeurStrat >3 and $Faction ==$Faction_Flag and !$Move) //Dépot
                        {
                            $depot_info="<h3>Dépôt de ".$Ville."</h3><div style='overflow:auto;'><table class='table'>
                                <thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
                                <th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th>
                                <th>Charges de Profondeur</th><th>Mines</th><th>Torpilles</th><th>Rockets</th><th>Fusées</th></tr></thead>
                                <tr><td>".$Stock_Essence_87."</td><td>".$Stock_Essence_100."</td><td>".$Stock_Essence_1."</td><td>".$Stock_Munitions_8."</td><td>".$Stock_Munitions_13."</td>
                                <td>".$Stock_Munitions_20."</td><td>".$Stock_Munitions_30."</td><td>".$Stock_Munitions_40."</td><td>".$Stock_Munitions_50."</td><td>".$Stock_Munitions_60."</td>
                                <td>".$Stock_Munitions_75."</td><td>".$Stock_Munitions_90."</td><td>".$Stock_Munitions_105."</td><td>".$Stock_Munitions_125."</td><td>".$Stock_Munitions_150."</td>
                                <td>".$Stock_Bombes_300."</td><td>".$Stock_Bombes_400."</td><td>".$Stock_Bombes_800."</td><td>".$Stock_Bombes_80."</td><td>".$Stock_Bombes_30."</td></tr>
                                </table></div>";
                            if(!$Division_d)
                            {
                                if($Stock_Munitions_8 >500000)
                                    $Fret_options.="<option value='8'>500000 cartouches de 8mm</option>";
                                elseif($Stock_Munitions_8)
                                    $Fret_options.="<option value='8' disabled>500000 cartouches de 8mm</option>";
                                if($Stock_Munitions_13 >250000)
                                    $Fret_options.="<option value='13'>250000 cartouches de 13mm</option>";
                                elseif($Stock_Munitions_13)
                                    $Fret_options.="<option value='13' disabled>250000 cartouches de 13mm</option>";
                                if($Stock_Munitions_20 >=100000)
                                    $Fret_options.="<option value='20'>100000 obus de 20mm</option>";
                                elseif($Stock_Munitions_20)
                                    $Fret_options.="<option value='20' disabled>100000 obus de 20mm</option>";
                                if($Stock_Munitions_30 >=50000)
                                    $Fret_options.="<option value='30'>50000 obus de 30mm</option>";
                                elseif($Stock_Munitions_30)
                                    $Fret_options.="<option value='30' disabled>50000 obus de 30mm</option>";
                                if($Stock_Munitions_40 >=25000)
                                    $Fret_options.="<option value='40'>25000 obus de 40mm</option>";
                                elseif($Stock_Munitions_40)
                                    $Fret_options.="<option value='40' disabled>25000 obus de 40mm</option>";
                                if($Stock_Munitions_50 >=15000)
                                    $Fret_options.="<option value='50'>15000 obus de 50mm</option>";
                                elseif($Stock_Munitions_50)
                                    $Fret_options.="<option value='50' disabled>15000 obus de 50mm</option>";
                                if($Stock_Munitions_60 >=10000)
                                    $Fret_options.="<option value='60'>10000 obus de 60mm</option>";
                                elseif($Stock_Munitions_60)
                                    $Fret_options.="<option value='60' disabled>10000 obus de 60mm</option>";
                                if($Stock_Munitions_75 >=7500)
                                    $Fret_options.="<option value='75'>7500 obus de 75mm</option>";
                                elseif($Stock_Munitions_75)
                                    $Fret_options.="<option value='75' disabled>7500 obus de 75mm</option>";
                                if($Stock_Munitions_90 >=5000)
                                    $Fret_options.="<option value='90'>5000 obus de 90mm</option>";
                                elseif($Stock_Munitions_90)
                                    $Fret_options.="<option value='90' disabled>5000 obus de 90mm</option>";
                                if($Stock_Munitions_105 >=3750)
                                    $Fret_options.="<option value='105'>3750 obus de 105mm</option>";
                                elseif($Stock_Munitions_105)
                                    $Fret_options.="<option value='105' disabled>3750 obus de 105mm</option>";
                                if($Stock_Munitions_125 >=2500)
                                    $Fret_options.="<option value='125'>2500 obus de 125mm</option>";
                                elseif($Stock_Munitions_125)
                                    $Fret_options.="<option value='125' disabled>2500 obus de 125mm</option>";
                                if($Stock_Munitions_150 >=1000)
                                    $Fret_options.="<option value='150'>1000 obus de 150mm</option>";
                                elseif($Stock_Munitions_150 >=1000)
                                    $Fret_options.="<option value='150' disabled>1000 obus de 150mm</option>";
                                if($Stock_Munitions_200 >500)
                                    $Fret_options.="<option value='1200'>500 obus de 200mm</option>";
                                if($Stock_Munitions_300 >375)
                                    $Fret_options.="<option value='310'>375 obus de 300mm</option>";
                                if($Stock_Munitions_360 >250)
                                    $Fret_options.="<option value='360'>250 obus de 360mm</option>";
                                if($Stock_Bombes_50 >10000)
                                    $Fret_options.="<option value='9050'>10000 bombes de 50kg</option>";
                                if($Stock_Bombes_125 >5000)
                                    $Fret_options.="<option value='9125'>5000 bombes de 125kg</option>";
                                if($Stock_Bombes_250 >2500)
                                    $Fret_options.="<option value='9250'>2500 bombes de 250kg</option>";
                                if($Stock_Bombes_500 >1000)
                                    $Fret_options.="<option value='9500'>1000 bombes de 500kg</option>";
                                if($Stock_Bombes_1000 >500)
                                    $Fret_options.="<option value='10000'>500 bombes de 1000kg</option>";
                                if($Stock_Bombes_2000 >250)
                                    $Fret_options.="<option value='11000'>250 bombes de 2000kg</option>";
                                if($Stock_Bombes_300 >1250)
                                    $Fret_options.="<option value='300'>1250 charges de profondeur</option>";
                                if($Stock_Bombes_400 >1250)
                                    $Fret_options.="<option value='400'>1250 mines</option>";
                                if($Stock_Bombes_80 >5000)
                                    $Fret_options.="<option value='80'>5000 rockets</option>";
                                if($Stock_Bombes_800 >500)
                                    $Fret_options.="<option value='800'>500 torpilles</option>";
                                if($Stock_Bombes_30 >10000)
                                    $Fret_options.="<option value='930'>10000 fusées éclairantes</option>";
                                if($Stock_Essence_87 >=250000)
                                    $Fret_options.="<option value='1087'>250000L Essence 87 Octane</option>";
                                elseif($Stock_Essence_87)
                                    $Fret_options.="<option value='1087' disabled>250000L Essence 87 Octane</option>";
                                if($Stock_Essence_100 >=250000)
                                    $Fret_options.="<option value='1100'>250000L Essence 100 Octane</option>";
                                elseif($Stock_Essence_100)
                                    $Fret_options.="<option value='1100' disabled>250000L Essence 100 Octane</option>";
                                if($Stock_Essence_1 >=250000)
                                    $Fret_options.="<option value='1001'>250000L de Diesel</option>";
                                elseif($Stock_Essence_1)
                                    $Fret_options.="<option value='1001' disabled>250000L de Diesel</option>";
                                if($Fret ==888 and $Vehicule ==5124 and ($Lieu ==269 or $Lieu ==270 or $Lieu ==731 or $Lieu ==586 or $Lieu ==758 or $Lieu ==815))
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='Dech' value='888'><input type='hidden' name='base' value='".$Lieu."'><input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                                elseif($Fret >0 and $Fret !=888 and $Vehicule !=5392)
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='Dech' value='".$Fret."'><input type='hidden' name='base' value='".$Lieu."'><input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                            }
                            elseif($Division_d and $Ordres_Div)
                            {
                                if($Stock_Munitions_8 >10000)
                                    $Fret_options.="<option value='8'>10000 cartouches de 8mm</option>";
                                if($Stock_Munitions_13 >5000)
                                    $Fret_options.="<option value='13'>5000 cartouches de 13mm</option>";
                                if($Stock_Munitions_20 >2000)
                                    $Fret_options.="<option value='20'>2000 obus de 20mm</option>";
                                if($Stock_Munitions_30 >1000)
                                    $Fret_options.="<option value='30'>1000 obus de 30mm</option>";
                                if($Stock_Munitions_40 >500)
                                    $Fret_options.="<option value='40'>500 obus de 40mm</option>";
                                if($Stock_Munitions_50 >300)
                                    $Fret_options.="<option value='50'>300 obus de 50mm</option>";
                                if($Stock_Munitions_60 >200)
                                    $Fret_options.="<option value='60'>200 obus de 60mm</option>";
                                if($Stock_Munitions_75 >150)
                                    $Fret_options.="<option value='75'>150 obus de 75mm</option>";
                                if($Stock_Munitions_90 >100)
                                    $Fret_options.="<option value='90'>100 obus de 90mm</option>";
                                if($Stock_Munitions_105 >75)
                                    $Fret_options.="<option value='105'>75 obus de 105mm</option>";
                                if($Stock_Munitions_125 >50)
                                    $Fret_options.="<option value='125'>50 obus de 125mm</option>";
                                if($Stock_Munitions_150 >20)
                                    $Fret_options.="<option value='150'>20 obus de 150mm</option>";
                                if($Stock_Bombes_300 >25)
                                    $Fret_options.="<option value='300'>25 charges de profondeur</option>";
                                if($Stock_Bombes_400 >25)
                                    $Fret_options.="<option value='400'>25 mines</option>";
                                if($Stock_Bombes_800 >10)
                                    $Fret_options.="<option value='800'>10 torpilles</option>";
                                if($Stock_Essence_87 >50000)
                                    $Fret_options.="<option value='1087'>50000L Essence 87 Octane</option>";
                                if($Stock_Essence_1 >50000)
                                    $Fret_options.="<option value='1001'>50000L de Diesel</option>";
                                /*if($Fret)
                                {
                                    $Regs_div=false;
                                    $con=dbconnecti();
                                    $result9=mysqli_query($con,"SELECT r.ID FROM Regiment as r,Officier as o WHERE r.Officier_ID=o.ID AND r.Lieu_ID='$Lieu' AND o.Division='$Division_d'");
                                    mysqli_close($con);
                                    if($result9)
                                    {
                                        while($data9=mysqli_fetch_array($result9,MYSQLI_ASSOC))
                                        {
                                            $Regs_div.="<option value='".$data9['ID']."'>".$data9['ID']."e Cie</option>";
                                        }
                                        mysqli_free_result($result9);
                                    }
                                    if($Regs_div)
                                        $Decharger="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='Dech' value='".$Fret."'>
                                        <select name='Reg_div' class='form-control' style='width: 150px'>".$Regs_div."</select>
                                        <input type='Submit' value='Ravitailler' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                                }*/
                            }
                            if($Vehicule ==5124 and $Longitude <-52 and ($country ==2 or $country ==7))
                            {
                                $LLease=true;
                                $Fret_options.="<option value='888'>Lend-Lease</option>";
                            }
                        }
                        if($Pos_ori ==888 or $LLease)
                        {
                            if($country==7)
                                $UK_Lend_txt=" Bristol, Glasgow et Liverpool pour l'Empire Britannique,";
                            $depot_info.="<p class='lead'>Le Lend-Lease permet de fournir du matériel aux nations alliées via les ports d'Arkhangelsk et Mourmansk pour l'URSS,".$UK_Lend_txt." Casablanca pour la France.
                            <br>Le matériel Lend-Lease est indiqué dans l'encyclopédie par le symbole <img src='images/lendlease.png' title='Lend-Lease'></p>";
                        }
                        /*if($Division_d and !$Ordres_Div)
                        {
                            $Lieux_txt="<select name='cible' class='form-control' style='width: 200px'><option value='0'>Rester à ".$Ville."</option>";
                            $Positions="<select name='fret' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option></select><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Seul le commandant de flottille peut gérer cette option</span></a>";
                        }
                        else*/
                        //Update : 03/02/2018 $Zone ==6 => $Placement ==8
                        if($Placement ==8 and !$Port_Ori and !$Move)
                        {
                            $con=dbconnecti();
                            $resultgr=mysqli_query($con,"SELECT r.ID,c.Nom FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Lieu' AND r.Pays='$country' AND c.Categorie IN(17,20,21,22,23,24) AND r.Autonomie < c.Autonomie");
                            mysqli_close($con);
                            if($resultgr)
                            {
                                while($datagr=mysqli_fetch_array($resultgr,MYSQLI_ASSOC))
                                {
                                    $Regs_gr.="<option value='".$datagr['ID']."'>Ravitailler ".$datagr['Nom']." (".$datagr['ID']."e)</option>";
                                }
                                mysqli_free_result($resultgr);
                            }
                            if($Regs_gr)
                                $Atk_Options="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='Reg_gr' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option>".$Regs_gr."</select><input class='btn btn-sm btn-warning' type='submit' value='Ravitailler un navire' onclick='this.disabled=true;this.form.submit();'></form></td>
                                <td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Ravitailler un navire en pleine mer</span></a></td></tr>";
                        }
                        elseif($Vehicule ==5392)
                            $Atk_Options="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='fretd' value='1'><select name='fret' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option>".$Fret_options."</select><input class='btn btn-sm btn-warning' type='submit' value='Charger' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une quantité minimale est nécessaire dans le dépôt pour pouvoir transporter du fret</span></a></td></tr>";
                        else
                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='Reg_div' value='".$Division_d."'><select name='fret' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option>".$Fret_options."</select><input class='btn btn-sm btn-warning' type='submit' value='Charger' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une quantité minimale est nécessaire dans le dépôt pour pouvoir transporter du fret</span></a></td></tr>";
                    }
                }
                elseif(($Ordres_Cdt or $Ordres_Mer or $Ordres_Armee or $Ordres_Div or $Ordres_Bat) and (!$GHQ or $Admin or $Nation_IA))
                {
                    $Meteo_Move_Limit=-75;
                    if($Type_Veh ==21)
                        $PA_IA=true;
                    elseif($Type_Veh ==37)
                        $Sub_IA=true;
                    elseif($Type_Veh ==15 or $Type_Veh ==17)
                        $ASM=true;
                    elseif($Type_Veh ==14 and $Detection >10)
                        $Patrouilleur=true;
                    elseif($Type_Veh ==20)
                        $Meteo_Move_Limit=-100;
                    if($Type_Veh ==17 or $Type_Veh ==18 or $Type_Veh ==19)
                        $Torp_IA=true;
                    if(!$Move and $Vehicule_Nbr >0 and $Position !=34 and $Autonomie >0 and $HP >0)
                    {
                        if($Meteo >$Meteo_Move_Limit)
                        {
                            $CT_Bomb=2-$Sec_EM;
                            $CT_Torp=4-$Sec_EM;
                            if($Sub_IA)
                            {
                                $Pos_torp=28;
                                $Atk_Options.="<tr><td><form action='index.php?view=ground_reco1' method='post'><input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Veh' value='".$Vehicule."'><input type='hidden' name='Cible' value='".$Lieu."'><input type='hidden' name='Conso' value='0'>
                                    <input type='submit' value='Reco' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>1 Jour</td><td>".$Range."m</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tente de révéler les unités présentes sur la même zone.</span></a></td></tr>";
                                if($Zone ==6 and $Autonomie >9 and $Vehicule ==5387)
                                {
                                    $con=dbconnecti();
                                    $resultgr=mysqli_query($con,"SELECT r.ID,c.Nom FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Lieu' AND r.Pays='$country' AND c.Categorie IN(17,20,21,22,23,24) AND r.Autonomie < c.Autonomie AND r.ID<>'$Unit'");
                                    mysqli_close($con);
                                    if($resultgr)
                                    {
                                        while($datagr=mysqli_fetch_array($resultgr,MYSQLI_ASSOC))
                                        {
                                            $Regs_gr.="<option value='".$datagr['ID']."'>Ravitailler ".$datagr['Nom']." (".$datagr['ID']."e Cie)</option>";
                                        }
                                        mysqli_free_result($resultgr);
                                    }
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='renf' value='6'><select name='Reg_gr' class='form-control' style='width: 150px'><option value='0'>Ne pas ravitailler</option>".$Regs_gr."</select></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>1 Jour</td><td>".$Range."m</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Ravitailler un navire en pleine mer</span></a></td></tr>";
                                }
                            }
                            elseif($Patrouilleur)
                            {
                                $Atk_Options.="<tr><td><form action='index.php?view=ground_reco1' method='post'>
                                    <input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Veh' value='".$Vehicule."'><input type='hidden' name='Cible' value='".$Lieu."'><input type='hidden' name='Conso' value='0'>
                                    <input type='submit' value='Reco' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>1 Jour</td><td>".$Range."m</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tente de révéler les unités présentes sur la même zone.<br>Action comptant comme action du jour</span></a></td></tr>";
                            }
                            elseif($ASM)
                            {
                                if($Zone ==6 or $Placement ==8)
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_asm' method='post'><input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Veh' value='".$Vehicule."'><input type='hidden' name='Cible' value='".$Lieu."'><input type='hidden' name='Conso' value='0'>
                                    <input type='submit' value='Grenadage' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                    <td>1 Jour</td>
                                    <td>".$Range."m</td>
                                    <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tentative de repérage et de grenadage d'éventuels sous-marins ennemis présents dans la même zone.</span></a></td></tr>";
                            }
                            elseif($Categorie ==25 and $Detroit >0 and $Credits >=4 and $Mines_m <100) //Mouilleur mines
                            {
                                $Atk_Options.="<tr><td><form action='index.php?view=ground_miner' method='post'>
                                <input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Cible' value='".$Lieu."'>
                                <input type='submit' value='Miner' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                <td><img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                <td>1 Jour</td>
                                <td>".$Range."m</td>
                                <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Mouiller des mines marines dans la zone</span></a></td></tr>";
                            }
                            elseif($Categorie ==19 and $Detroit >0 and $Credits >=8) //Dragueur mines
                            {
                                $Atk_Options.="<tr><td><form action='index.php?view=ground_deminer' method='post'>
                                <input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Cible' value='".$Lieu."'>
                                <input type='Submit' value='Deminer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                <td><img src='images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                <td>1 Jour</td>
                                <td>".$Range."m</td>
                                <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Détecter et éventuellement retirer des mines marines dans la zone</span></a></td></tr>";
                            }
                            elseif($HP >10000 and !$PA_IA and $Credits >=$CT_Bomb and $Autonomie >0 and !$Canada)
                            {
                                if($Zone ==6 or $Placement ==PLACE_LARGE){
                                    //Range
                                    if($Matos ==8)$Range /=2;
                                    if($Position ==2 or $Position ==3 or $Position ==9 or $Position ==10 or $Position ==26)$Range /=2;
                                    if($Skill ==73)
                                        $Range*=1.25;
                                    elseif($Skill ==72)
                                        $Range*=1.2;
                                    elseif($Skill ==47)
                                        $Range*=1.15;
                                    elseif($Skill ==15)
                                        $Range*=1.1;
                                    if($Meteo <-69)$Range /=2;
                                    if($Flag ==$country)$Range +=500;
                                    if($Zone ==6)$Range+=($Experience*9);
                                    if($Meteo <-69)
                                        $Max_Range=5000;
                                    elseif($Meteo <-9)
                                        $Max_Range=10000;
                                    else
                                        $Max_Range=20000;
                                    if($Range >$Max_Range)$Range=$Max_Range;
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='CT' value='".$CT_Bomb."'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='30'>
                                    <input type='submit' value='Engagement' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><img src='images/CT".$CT_Bomb.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                    <td>1 Jour</td>
                                    <td>".$Range."m</td>
                                    <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Bombardement visant une unité ennemie sur le même lieu. Compte comme action du jour.</span></a></td></tr>";
                                }
                                if($Zone !=6 and $Flag !=$country)
                                    $Pos_txt.="<option value='29'>Bombardement des fortifications (Action du jour)</option>";
                                if($Faction !=$Faction_Flag and $Zone !=6 and $Garnison and $Recce)
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='33'>
                                    <input type='submit' value='Bombardement' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                    <td>1 Jour</td>
                                    <td>".$Range."m</td>
                                    <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Bombardement visant la caserne ou la garnison sur le même lieu. Compte comme action du jour.</span></a></td></tr>";
                            }
                            if((($Torp_IA and $Credits >=$CT_Torp) or $Sub_IA) and ($Zone ==6 or $Placement ==8) and $Arme_AT and $HasHostiles)
                            {
                                //Range
                                if($Sub_IA)
                                {
                                    $con=dbconnecti();
                                    $Rudels=mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Lieu' AND r.Pays='$country' AND r.Placement=8 AND r.Vehicule_Nbr >0 AND c.Type=37");
                                    mysqli_close($con);
                                }
                                if($Matos ==8)$Range/=2;
                                if($Position ==26)
                                    $Range/=2;
                                elseif($Position ==25 or $Position ==28)
                                    $Range*=2;
                                if($Meteo <-69)
                                    $Max_Range=500;
                                elseif($Meteo <-9)
                                    $Max_Range=1000;
                                else
                                    $Max_Range=2000;
                                if($Range >$Max_Range)$Range=$Max_Range;
                                if($Placement ==4)
                                    $Range=20000; //Au port tous les navires de surface peuvent être ciblés
                                elseif($Skill ==43)
                                    $Range*=(1+((5*$Rudels)/10));
                                elseif($Skill ==168)
                                    $Range*=(1+((10*$Rudels)/10));
                                elseif($Skill ==169)
                                    $Range*=(1+((15*$Rudels)/10));
                                elseif($Skill ==170)
                                    $Range*=(1+((20*$Rudels)/10));
                                $Range=round($Range);
                                if($Torp_IA){
                                    $Pos_torp=40;
                                    $Torp_CT="<img src='images/CT".$CT_Torp.".png' title='Montant en Crédits Temps que nécessite cette action'>";
                                }
                                else
                                    $CT_Torp=0;
                                if($Credits >=$CT_Torp or $Sub_IA)
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='CT' value='".$CT_Torp."'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='".$Pos_torp."'>
                                    <input type='submit' value='Torpillage' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'>".$Torp_CT."<a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                    <td>1 Jour</td>
                                    <td>".$Range."m</td>
                                    <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Vous ne pourrez cibler que les unités détectées au préalable par une reco. Vérifiez la présence d'unités détectées dans la fenêtre de situation.</span></a></td></tr>";
                            }
                        }
                        elseif($Meteo <=$Meteo_Move_Limit)
                            $txt_help.="<div class='alert alert-danger'>Le mauvais temps interdit tout appareillage!</div>";
                    }
                    $Pos_titre='Position';
                    $Pos_ori=GetPosGr($Position);
                    if($Position ==34)
                        $Positions='<span class="text-danger">En cale sèche</span>';
                    elseif($Sub_IA)
                    {
                        if(!$Positions)
                        {
                            $Positions="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'>
                            <select name='pos' class='form-control' style='max-width:200px; display:inline;'><option value='0'>Ne rien changer</option>";
                            if($Position !=34)$Positions.="<option value='25'>Plongée</option>";
                            //$Positions.="</select><input class='btn btn-sm btn-warning' type='submit' value='Changer' onclick='this.disabled=true;this.form.submit();'></form>";
                        }
                    }
                    elseif($Transit_Veh ==5000)
                    {
                        if($Flag_Port)$Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
                        if($Flag_Plage)$Faction_Plage=GetData("Pays","ID",$Flag_Plage,"Faction");
                        if(!$Move and $Port >10 and $Faction ==$Faction_Flag and $Faction ==$Faction_Port)
                            $Atk_Options='<tr><td><form action="index.php?view=ground_em_ia_go" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu.'"><input type="hidden" name="reset" value="7"><input class="btn btn-sm btn-warning" type="submit" value="Décharger" onclick="this.disabled=true;this.form.submit();"></form></td><td><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>';
                        else
                            $txt_help.="<div class='alert alert-warning'>Ces troupes peuvent être débarquées dans un port aux infrastructures non détruites dont le lieu et le port sont contrôlés par votre faction</div>";
                        $Positions="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='pos' class='form-control' style='max-width:200px; display:inline;'>
                                    <option value='0'>Ne rien changer</option>
                                    <option value='20'>Dispersé (navire isolé ou sans protection)</option>
                                    <option value='22'>Evasion (navire rapide désirant éviter le combat)</option>";
                        $barges_txt='<br>'.GetVehiculeIcon(5000,$country,0,0,$Front,"Transporté par des barges");
                        if(!$Move and $Zone !=6 and $Plage and $Placement ==8 and ($Amphi or $Faction_Plage ==$Faction))
                        {
                            $con=dbconnecti();
                            //$Unitsb_Plage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement=11 AND r.Vehicule_Nbr >0"),0);
                            $Unitsa_Plage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction='$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement=11 AND i.Vehicule_Nbr >0"),0);
                            mysqli_close($con);
                            //$Unitsa_Plage+=$Unitsb_Plage;
                            if($Unitsa_Plage >4)
                                $txt_help.="<div class='alert alert-danger'>Un trop grand nombre de troupes se trouvent déjà sur la plage, empêchant tout débarquement</div>";
                            else
                                $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='reset' value='8'>
                                <input type='submit' value='Débarquer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                        }
                    }
                    elseif($Pas_libre)
                        $Positions='<span class="text-danger">Immobilisé</span>';
                    elseif(!$Autonomie)
                    {
                        $Positions="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='pos' class='form-control' style='max-width:200px; display:inline;'>
                                    <option value='0'>Ne rien changer</option>
                                    <option value='20'>Dispersé (navire isolé ou sans protection)</option>
                                    <option value='22'>Evasion (navire rapide désirant éviter le combat)</option>";
                    }
                    else
                    {
                        $Positions="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='pos' class='form-control' style='max-width:200px; display:inline;'>
                                    <option value='0'>Ne rien changer</option>
                                    <option value='20'>Dispersé (navire isolé ou sans protection)</option>
                                    <option value='21'>Escorte (protection des autres navires)</option>
                                    <option value='22'>Evasion (navire rapide désirant éviter le combat)</option>".$Pos_txt;
                        if($Arme_Art >0 and $Credits >=1)
                            $Positions.="<option value='23'>Appui (1CT - Ripostera avec son artillerie en cas d'attaque de surface)</option>";
                        if($ASM and $Credits >=1)
                            $Positions.="<option value='24'>ASM (1CT - Ripostera avec ses grenades en cas d'attaque sous-marine)</option>";
                        if($Vitesse >35 and !$Move)
                        {
                            if($HP >$HP_max/2)
                                $Positions.="<option value='27'>Interdiction (Action du jour - Tentera d'empêcher les navires ennemis de fuir)</option>";
                            else
                                $txt_help.="<div class='alert alert-danger'>Le navire est trop endommagé pour pouvoir être utilisé comme navire d'interdiction</div>";
                        }
                        if($Arme_Art >0 and $Zone ==6 and !$Move)
                            $Positions.="<option value='37'>Fumigène (Action du jour - Permet aux navires alliés de quitter la zone de combat)</option>";
                        elseif($Zone !=6 and $Placement ==4 and !$Move)
                            $Positions.="<option value='26'>Filet anti-torpilles (Action du jour)</option>";
                    }
                    if($Position !=34 and !$Pas_libre)
                        $Positions.="</select><input class='btn btn-sm btn-warning' type='submit' value='Changer' onclick='this.disabled=true;this.form.submit();'></form>";
                    else
                        $Positions.='</select></form>';
                    if($PA_IA or $Hydra)
                    {
                        $depot_info="<div class='panel panel-war'><div class='panel-heading'>Avions à bord</div><div class='panel-body'><table class='table table-striped'>";
                        if($Hydra)
                        {
                            $depot_info.="<tr><td><a href='#' class='popup'>".$Avions."/".$Hydra_Nbr."<span>Nombre d'avions disponibles/maximum.<br>Récupérer des avions perdus est possible via l'action de ravitaillement dans un port.</span></a>
                            ".GetAvionIcon($Hydra,$country,0,0,$Front)."</td><td></tr>";
                            if($Avions and $Autonomie)
                                $depot_info.="<tr><td><form action='index.php?view=ground_em_ia_hydra' method='post'><input type='hidden' name='Lieu' value='".$Lieu."'>
                                <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une mission aérienne consomme 1 jour d'autonomie du navire</span></a>
                                <input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Hydra' value='".$Hydra."'>
                                <input type='submit' value='Mission aérienne' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td></tr>";
                        }
                        else
                        {
                            $con=dbconnecti();
                            $result10=mysqli_query($con,"SELECT ID,Pays,Nom,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Mission_IA FROM Unit WHERE Porte_avions='$Vehicule' AND Etat=1");
                            mysqli_close($con);
                            if($result10)
                            {
                                while($data10=mysqli_fetch_array($result10,MYSQLI_ASSOC))
                                {
                                    if(!$data10['Mission_IA'])
                                        $Esc_PA_txt="<form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$data10['ID']."'>
                                        <input type='submit' value='".$data10['Nom']."' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                                    else
                                        $Esc_PA_txt="<span class='label label-danger'>En vol</span>";
                                    $depot_info.="<tr><td>".$Esc_PA_txt."</td><td>"
                                        .$data10['Avion1_Nbr']."x ".GetAvionIcon($data10['Avion1'],$data10['Pays'],0,$data10['ID'],$Front)."</td><td>"
                                        .$data10['Avion2_Nbr']."x ".GetAvionIcon($data10['Avion2'],$data10['Pays'],0,$data10['ID'],$Front)."</td><td>"
                                        .$data10['Avion3_Nbr']."x ".GetAvionIcon($data10['Avion3'],$data10['Pays'],0,$data10['ID'],$Front)."</td></tr>";
                                }
                                mysqli_free_result($result10);
                            }
                        }
                        $depot_info.='</table></div></div>';
                    }
                }
                elseif($Ordres_Adjoint and $Transit_Veh ==5000 and (!$GHQ or $Admin or $Nation_IA))
                {
                    $Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
                    if(!$Move and $Port >10 and $Faction ==$Faction_Flag and $Faction ==$Faction_Port)
                    {
                        $con=dbconnecti();
                        $Unitsa_Port=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction='$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement=4 AND i.Vehicule_Nbr >0"),0);
                        mysqli_close($con);
                        if($Unitsa_Plage >4)
                            $txt_help.="<div class='alert alert-danger'>Un trop grand nombre de troupes se trouvent déjà dans le port, empêchant tout débarquement</div>";
                        else
                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='reset' value='7'>
                            <input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                    }
                    else
                        $txt_help.="<div class='alert alert-warning'>Ces troupes peuvent être débarquées dans un port aux infrastructures non détruites dont le lieu et le port sont contrôlés par votre faction</div>";
                    $Positions="<select name='pos' class='form-control' style='max-width:200px; display:inline;'>
                                <option value='0'>Ne rien changer</option>
                                <option value='20'>Dispersé (navire isolé ou sans protection)</option>
                                <option value='22'>Evasion (navire rapide désirant éviter le combat)</option>";
                    $barges_txt='<br>'.GetVehiculeIcon(5000,$country,0,0,$Front,"Transporté par des barges");
                    if($Amphi and $Zone !=6 and $Plage and $Placement ==8)
                    {
                        $con=dbconnecti();
                        //$Unitsb_Plage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement=11 AND r.Vehicule_Nbr >0"),0);
                        $Unitsa_Plage=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction='$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement=11 AND i.Vehicule_Nbr >0"),0);
                        mysqli_close($con);
                        //$Unitsa_Plage+=$Unitsb_Plage;
                        if($Unitsa_Plage)
                            $txt_help.="<div class='alert alert-danger'>Un trop grand nombre de troupes se trouvent déjà sur la plage, empêchant tout débarquement</div>";
                        else
                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='reset' value='8'>
                            <input type='Submit' value='Débarquer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                    }
                }
                //Renforts
                if(!$Transit_Veh)
                {
                    $Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
                    if($Port >0 and $Faction ==$Faction_Flag and $Faction ==$Faction_Port and $Credits >=4)
                    {
                        $Port_ok=false;
                        if($Port_level ==3)$Port_ok=true;
                        elseif($Port_level ==2 and ($Type_Veh <20 or $Type_Veh ==37))$Port_ok=true;
                        elseif($Port_level ==1 and ($Type_Veh ==14 or $Type_Veh ==15))$Port_ok=true;
                        if($Port_ok)
                        {
                            $CT_cale=4;
                            $con=dbconnecti();
                            if($Type_Veh>17 and $Type_Veh<22)
                            {
                                $coules=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ground_Cbt WHERE Veh_b='$Vehicule'"),0);
                                if($coules>0)$CT_cale=40;
                            }
                            //$Enis_Port=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement=4 AND r.Vehicule_Nbr >0"),0);
                            $Enis2_Port=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction<>'$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement=4 AND i.Vehicule_Nbr >0"),0);
                            mysqli_close($con);
                            $Enis_Port_combi=$Enis_Port+$Enis2_Port;
                            if($Enis_Port_combi)
                                $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Ce navire ne peut être réparé sur une zone de combat</span></a></td></tr>';
                            elseif(!$Move and $Credits >=$CT_cale and (
                                    ($HP<$HP_max and $HP>0) or
                                    ($Vehicule_Nbr<1 and ($Vehicule ==5001 or $Vehicule ==5124 or $Type_Veh ==37 or $Type_Veh ==14)) or
                                    ($Vehicule_Nbr<4 and ($Type_Veh ==15 or $Type_Veh ==16)) or
                                    ($Vehicule_Nbr<2 and $Type_Veh ==17)
                                ))
                                $Renforts_txt='<tr><td>
                                            <form action="index.php?view=ground_em_ia_go" method="post">
                                                <input type="hidden" name="renf" value="2">
                                                <input type="hidden" name="Unit" value="'.$Unit.'">
                                                <input class="btn btn-sm btn-warning" type="submit" value="Réparer">
                                            </form>
                                        </td>
                                        <td><div class="i-flex"><img src="images/CT'.$CT_cale.'.png" title="Credits Temps nécessaires pour exécuter cette action"><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></div></td>
                                        <td><a href="#" class="popup"><div class="i-flex help_icon"></div><span>Des infrastructures portuaires sous le contrôle de votre faction sont nécessaires pour permettre les réparations des navires à cet endroit</span></a></td>
                                   </tr>';
                            elseif(!$Move and $Credits >=40 and $Vehicule_Nbr<1 and !$HP and ($Type_Veh>17 and $Type_Veh<22))
                                $Renforts_txt='<tr><td>
                                            <form action="index.php?view=ground_em_ia_go" method="post">
                                                <input type="hidden" name="renf" value="4">
                                                <input type="hidden" name="Unit" value="'.$Unit.'">
                                                <input class="btn btn-sm btn-warning" type="submit" value="Cale Sèche">
                                            </form>
                                        </td>
                                        <td><div class="i-flex"><img src="images/CT40.png" title="Credits Temps nécessaires pour exécuter cette action"><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></div></td>
                                        <td><a href="#" class="popup"><div class="i-flex help_icon"></div><span>Des infrastructures portuaires sous le contrôle de votre faction sont nécessaires pour permettre les réparations des navires à cet endroit</span></a></td>
                                   </tr>';
                            else
                                $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Ce navire ne peut être réparé actuellement</span></a></td></tr>';
                        }
                        else
                            $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Les infrastructures de ce port ne permettent pas de réparations pour des navires de ce type</span></a></td></tr>';
                    }
                    else
                        $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Des infrastructures portuaires sous le contrôle de votre faction sont nécessaires pour permettre les réparations des navires à cet endroit</span></a></td></tr>';
                    if($HP_max)
                        $hp_good=round(($HP/$HP_max)*100);
                    else
                        $hp_good=0;
                    $hp_bad=100-$hp_good;
                    if($HP)
                        $Cur_HP="<br><div class='progress'><div class='progress-bar progress-bar-success' style='width: ".$hp_good."%'>".$hp_good."%</div><div class='progress-bar progress-bar-danger' style='width: ".$hp_bad."%'></div></div>";
                    if($Categorie ==20 or $Categorie ==21 or $Categorie ==22 or $Categorie ==23 or $Categorie ==24 or $Categorie ==17){
                        $Autonomie_txt='<a href="#" class="popup">
                            <b class="badge">'.$Autonomie.'/'.$Jours_max.' Jours</b>
                            <span>
                                <h3>L\'autonomie des navires</h3>
                                <p>Les unités maritimes de premier ordre possèdent une autonomie exprimée en jours de mer représentant le temps durant lequel ils peuvent opérer en mer sans devoir retourner au port pour se ravitailler.</p>
                                <ul>
                                    <li>Chaque action du jour réduit cette valeur de 1. D\'autres actions également comme le déplacement, la riposte de la DCA, l\'envoi des avions de bord en mission, la riposte depuis une position d\'appui ou encore toute action offensive (engagement, bombardement côtier, etc...) réduit cette valeur de 1.</li>
                                    <li>Lorsque cette valeur atteint 0, le navire ne peut plus effectuer aucune action offensive ni choisir de position tactique (excepté la dispersion et l\'écran de fumée).</li>
                                    <li>Dès que le navire rejoint un port allié de valeur 4 ou supérieur, il peut se ravitailler (<img src="images/action_jour_icon.png" alt="Action du Jour"> + <img src="images/CT4.png" alt="Crédits Temps">) et remonter la valeur de jours en mer à son maximum.</li>
                                    <li>Les navires cargos peuvent ravitailler les navires de leur nation en mer, cette action comptant comme action du jour.</li>
                                </ul>
                            </span>
                        </a>';
                    }
                }
                if($Front == 2 and $Longitude <12)
                    $front_carte=12;
                else
                    $front_carte=$Front;
                $Carte_Bouton.="<div class='btn btn-sm btn-primary'><a href='carte_ground.php?map=".$front_carte."&mode=10&cible=".$Lieu."&reg=".$Unit."' onclick='window.open(this.href); return false;'>Voir la carte</a></div>";
            }
            elseif($Vehicule ==424) //Train
            {
                $Auto_train=500; //Autonomie Trains
                $Divisions='Etat-Major';
                $Placements='Gare';
                $Positions='En position';
                if(!$Move and ($Ordres_Cdt or $Ordres_Adjoint or $Ordres_Log) and $Lieu and $NoeudF >10)
                {
                    $CT_front=0;
                    $Stock_field=false;
                    if($Fret){
                        if($Fret ==1001)
                            $Stock_field=',Stock_Essence_1';
                        elseif($Fret ==1087)
                            $Stock_field=',Stock_Essence_87';
                        elseif($Fret ==1100)
                            $Stock_field=',Stock_Essence_100';
                        elseif($Fret ==930)
                            $Stock_field=',Stock_Bombes_30';
                        elseif($Fret ==80)
                            $Stock_field=',Stock_Bombes_80';
                        elseif($Fret ==300)
                            $Stock_field=',Stock_Bombes_300';
                        elseif($Fret ==400)
                            $Stock_field=',Stock_Bombes_400';
                        elseif($Fret ==800)
                            $Stock_field=',Stock_Bombes_800';
                        elseif($Fret ==1200)
                            $Stock_field=',Stock_Munitions_200';
                        elseif($Fret ==9050 or $Fret ==9125 or $Fret ==9250 or $Fret ==9500)
                            $Stock_field0='Stock_Bombes_'.substr($Fret,1);
                        elseif($Fret >9999)
                            $Stock_field0='Stock_Bombes_'.substr($Fret,0,-1);
                        else
                            $Stock_field0='Stock_Munitions_'.$Fret;
                        if(!$Stock_field)$Stock_field=','.$Stock_field0;
                    }
                    if($Lieu ==2306 or $Lieu ==2307) //Corse
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE ID IN (2306,2307) AND ID<>'$Lieu' ORDER BY Nom ASC";
                    elseif($Front ==2)
                    {
                        $Auto_train=250;
                        if($country ==4)
                        {
                            if($Longitude >35)
                                $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude <36.5 AND Longitude >35 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                            else
                                $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude <37.3 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                        }
                        elseif($Longitude >44)
                        {
                            $Auto_train=500;
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude <44 AND Longitude <50 AND Zone<>6 AND Flag=".$Pays_Ori." AND NoeudF >10 AND ID NOT IN ('$Lieu') ORDER BY Nom ASC";
                        }
                        elseif($Latitude >36.5 and $Longitude >19) //Grèce
                        {
                            if($country ==2)
                                $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude >36.5 AND Latitude <42 AND Longitude >19 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                            else
                            {
                                if(GetData("Lieu","ID",1219,"Flag") !=2)
                                    $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude >36.5 AND Latitude <43.5 AND Longitude >19 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                                else
                                    $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude >39 AND Latitude <43.5 AND Longitude >19 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                            }
                        }
                        elseif($Latitude <33) //AFN
                        {
                            if($country ==2)
                            {
                                if(GetData("Lieu","ID",889,"Flag") !=2 and $Longitude >25)
                                    $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude < 33 AND Longitude >25 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                                else
                                    $query="SELECT l.ID,l.Nom,l.Longitude,l.Latitude,l.NoeudR,l.Impass,l.ValeurStrat FROM Lieu as l WHERE l.Latitude <33 AND l.Longitude >10 AND Zone<>6 AND l.Flag IN (".$Allies.") AND l.NoeudF >10 AND l.ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                            }
                            else
                            {
                                if(GetData("Lieu","ID",889,"Flag") ==2 and $Longitude <25.2)
                                    $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude <33 AND Longitude >10 AND Longitude <25.2 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                                else
                                    $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude <33 AND Longitude >10 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653,2306,2307) ORDER BY Nom ASC";
                            }
                        }
                        else
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Latitude <46 AND Longitude <50 AND Zone<>6 AND Flag=".$Pays_Ori." AND NoeudF >10 AND ID NOT IN ('$Lieu',343,436,2306,2307) ORDER BY Nom ASC";
                    }
                    elseif($Front ==1)
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE (Latitude BETWEEN 41 AND 52) AND (Longitude BETWEEN 13 AND 52) AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',1252) ORDER BY Nom ASC";
                    elseif($Front ==4 or $Front ==5)
                        $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE (Latitude BETWEEN 50.4 AND 70) AND (Longitude BETWEEN 13 AND 65) AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',1252) ORDER BY Nom ASC";
                    elseif($Front ==3)
                    {
                        if($country ==7)
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Longitude >235 AND Zone<>6 AND Flag=7 AND NoeudF >10 AND ID NOT IN ('$Lieu') ORDER BY Nom ASC";
                        elseif($country ==2)
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE (Latitude BETWEEN 9.6 AND 32) AND (Longitude BETWEEN 67 AND 97) AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu') ORDER BY Nom ASC";
                        else
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE ID='$Lieu'";
                    }
                    else
                    {
                        if($Pays_Ori ==1 or $Pays_Ori ==3 or $Pays_Ori ==4 or $Pays_Ori ==5 or $Pays_Ori ==6 or $Pays_Ori ==36){
                            $Auto_train=250;
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Pays<>2 AND Latitude >41 AND Longitude <14 AND Zone<>6 AND Flag IN (".$Allies.") AND NoeudF >10 AND ID NOT IN ('$Lieu',704,896) ORDER BY Nom ASC";
                        }
                        elseif($Pays_Ori ==2){
                            $Auto_train=250;
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Pays=".$Pays_Ori." AND Latitude >49 AND Longitude <14 AND Zone<>6 AND Flag=".$Pays_Ori." AND NoeudF >10 AND ID<>'$Lieu' ORDER BY Nom ASC";
                        }
                        elseif($Pays_Ori ==7)
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,Impass,ValeurStrat".$Stock_field." FROM Lieu WHERE Pays=".$Pays_Ori." AND Latitude >30 AND Zone<>6 AND Flag=".$Pays_Ori." AND NoeudF >10 AND ID<>'$Lieu' ORDER BY Nom ASC";
                    }
                    $con=dbconnecti();
                    $result=mysqli_query($con,$query);
                    mysqli_close($con);
                    if($result)
                    {
                        while($data=mysqli_fetch_array($result,MYSQLI_NUM))
                        {
                            //$Battle=$data[1];
                            $lieux_obj.='<option value="'.$data[0].'">'.$data[1].'</option>';
                            $coord=0;
                            $CT_front=0;
                            $Distance=GetDistance(0,0,$Longitude,$Latitude,$data[2],$data[3]);
                            if($Distance[0]<$Auto_train)
                            {
                                $Impass=$data[5];
                                $sensh='';
                                $sensv='';
                                $depot_icon=false;
                                if($Longitude >$data[2])
                                {
                                    $sensh='Ouest';
                                    $coord+=2;
                                    if($Impass ==2 or $Impass ==3 or $Impass ==4 or $Impass_ori ==6 or $Impass_ori ==7 or $Impass_ori ==8)
                                        $CT_front=4;
                                }
                                elseif($Longitude <$data[2])
                                {
                                    $sensh='Est';
                                    $coord+=1;
                                    if($Impass ==6 or $Impass ==7 or $Impass ==8 or $Impass_ori ==2 or $Impass_ori ==3 or $Impass_ori ==4)
                                        $CT_front=4;
                                }
                                if($sensh)
                                {
                                    if($Latitude >$data[3]+0.5)
                                    {
                                        $sensv='Sud';
                                        $coord+=20;
                                        if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
                                            $CT_front=4;
                                    }
                                    elseif($Latitude <$data[3]-0.5)
                                    {
                                        $sensv='Nord';
                                        $coord+=10;
                                        if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
                                            $CT_front=4;
                                    }
                                }
                                else
                                {
                                    if($Latitude >$data[3])
                                    {
                                        $sensv='Sud';
                                        $coord+=20;
                                        if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
                                            $CT_front=4;
                                    }
                                    elseif($Latitude <$data[3])
                                    {
                                        $sensv='Nord';
                                        $coord+=10;
                                        if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
                                            $CT_front=4;
                                    }
                                }
                                $sens=$sensv.' '.$sensh;
                                if($data[6] >3){
                                    $sens.=' - Dépôt';
                                    $depot_icon="<img src='images/depot_icon.png' title='".$data[7]."'>";
                                }
                                if($Admin)
                                $skills.='<br>Stock à '.$data[1].' : '.$data[7].' '.$Stock_field.' '.$Fret;
                                if(!$CT_front)
                                {
                                    $modal_conso='<p>Le déplacement rendra l\'unité inaccessible pendant 24h';
                                    $choix="<tr><td><a href='#' class='lien' data-toggle='modal' data-target='#modal-dest-".$data[0]."'>".$data[1]."</a></td><td>".$depot_icon."</td><td>".$Distance[0]."km</td></tr>";
                                    $lieux_modal.='<div class="modal fade" id="modal-dest-'.$data[0].'" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title">Déplacement
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </h2>
                                                                </div>
                                                                <div class="modal-body">
                                                                Le train se déplacera vers <b>'.$data[1].'</b>
                                                                <form action="index.php?view=ground_em_ia_go" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu.'"><input type="hidden" name="cible" value="'.$data[0].'"><input class="btn btn-danger" type="submit" value="confirmer"></form>'.$modal_conso.'</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';
                                    if($coord ==1) //Est
                                        $Est_txt.=$choix;
                                    elseif($coord ==2) //Ouest
                                        $Ouest_txt.=$choix;
                                    elseif($coord ==10) //Nord
                                        $Nord_txt.=$choix;
                                    elseif($coord ==20) //Sud
                                        $Sud_txt.=$choix;
                                    elseif($coord ==11) //NE
                                        $NE_txt.=$choix;
                                    elseif($coord ==21) //SE
                                        $SE_txt.=$choix;
                                    elseif($coord ==12) //NO
                                        $NO_txt.=$choix;
                                    elseif($coord ==22) //SO
                                        $SO_txt.=$choix;
                                }
                            }
                        }
                        mysqli_free_result($result);
                    }
                }
                //Depot
                $Faction_Gare=GetData("Pays","ID",$Flag_Gare,"Faction");
                if($ValeurStrat >3 and $Faction ==$Faction_Flag and $Faction ==$Faction_Gare and $Vehicule_Nbr >0)
                {
                    $depot_info="<h3>Dépôt de ".$Ville."</h3><div style='overflow:auto;'><table class='table'>
                        <thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
                        <th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th></tr></thead>
                        <tr><td>".$Stock_Essence_87."</td><td>".$Stock_Essence_100."</td><td>".$Stock_Essence_1."</td><td>".$Stock_Munitions_8."</td><td>".$Stock_Munitions_13."</td>
                        <td>".$Stock_Munitions_20."</td><td>".$Stock_Munitions_30."</td><td>".$Stock_Munitions_40."</td><td>".$Stock_Munitions_50."</td><td>".$Stock_Munitions_60."</td>
                        <td>".$Stock_Munitions_75."</td><td>".$Stock_Munitions_90."</td><td>".$Stock_Munitions_105."</td><td>".$Stock_Munitions_125."</td><td>".$Stock_Munitions_150."</td>
                        </tr></table></div>";
                    if($Stock_Munitions_8 >100000)
                        $Fret_options.="<option value='8'>100000 cartouches de 8mm</option>";
                    if($Stock_Munitions_13 >50000)
                        $Fret_options.="<option value='13'>50000 cartouches de 13mm</option>";
                    if($Stock_Munitions_20 >20000)
                        $Fret_options.="<option value='20'>20000 obus de 20mm</option>";
                    if($Stock_Munitions_30 >10000)
                        $Fret_options.="<option value='30'>10000 obus de 30mm</option>";
                    if($Stock_Munitions_40 >5000)
                        $Fret_options.="<option value='40'>5000 obus de 40mm</option>";
                    if($Stock_Munitions_50 >3000)
                        $Fret_options.="<option value='50'>3000 obus de 50mm</option>";
                    if($Stock_Munitions_60 >2000)
                        $Fret_options.="<option value='60'>2000 obus de 60mm</option>";
                    if($Stock_Munitions_75 >1500)
                        $Fret_options.="<option value='75'>1500 obus de 75mm</option>";
                    if($Stock_Munitions_90 >1000)
                        $Fret_options.="<option value='90'>1000 obus de 90mm</option>";
                    if($Stock_Munitions_105 >750)
                        $Fret_options.="<option value='105'>750 obus de 105mm</option>";
                    if($Stock_Munitions_125 >500)
                        $Fret_options.="<option value='125'>500 obus de 125mm</option>";
                    if($Stock_Munitions_150 >200)
                        $Fret_options.="<option value='150'>200 obus de 150mm</option>";
                    if($Stock_Bombes_50 >2000)
                        $Fret_options.="<option value='9050'>2000 bombes de 50kg</option>";
                    if($Stock_Bombes_125 >1000)
                        $Fret_options.="<option value='9125'>1000 bombes de 125kg</option>";
                    if($Stock_Bombes_250 >500)
                        $Fret_options.="<option value='9250'>500 bombes de 250kg</option>";
                    if($Stock_Bombes_500 >200)
                        $Fret_options.="<option value='9500'>200 bombes de 500kg</option>";
                    if($Stock_Bombes_1000 >100)
                        $Fret_options.="<option value='10000'>100 bombes de 1000kg</option>";
                    if($Stock_Bombes_2000 >50)
                        $Fret_options.="<option value='11000'>50 bombes de 2000kg</option>";
                    if($Stock_Bombes_300 >250)
                        $Fret_options.="<option value='300'>250 charges de profondeur</option>";
                    if($Stock_Bombes_400 >250)
                        $Fret_options.="<option value='400'>250 mines</option>";
                    if($Stock_Bombes_80 >1000)
                        $Fret_options.="<option value='80'>1000 rockets</option>";
                    if($Stock_Bombes_800 >100)
                        $Fret_options.="<option value='800'>100 torpilles</option>";
                    if($Stock_Bombes_30 >10000)
                        $Fret_options.="<option value='930'>10000 fusées éclairantes</option>";
                    if($Stock_Essence_87 >50000)
                        $Fret_options.="<option value='1087'>50000L Essence 87 Octane</option>";
                    if($Stock_Essence_100 >50000)
                        $Fret_options.="<option value='1100'>50000L Essence 100 Octane</option>";
                    if($Stock_Essence_1 >50000)
                        $Fret_options.="<option value='1001'>50000L de Diesel</option>";
                }
                elseif($Admin)
                    $skills="Faction=".$Faction." / Faction_Flag=".$Faction_Flag." / Faction_Gare=".$Faction_Gare;
                $Conso_txt="<span class='label label-default' title='Charge possible'>".$Charge."kg</span>";
                $Atk_Options="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'>
                <select name='fret' class='form-control' style='max-width:200px;'><option value='0'>Ne rien charger</option>".$Fret_options."</select><input type='submit' value='Charger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td>0</td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une quantité minimale est nécessaire dans le dépôt pour pouvoir transporter du fret</span></a></td></tr>";
                $Pos_titre='Fret';
                if(!$Fret)
                    $Pos_ori="Vide";
                elseif($Fret ==1001)
                    $Pos_ori="50000L Diesel";
                elseif($Fret ==1087)
                    $Pos_ori="50000L Essence 87";
                elseif($Fret ==1100)
                    $Pos_ori="50000L Essence 100";
                elseif($Fret ==1)
                    $Pos_ori="Troupes";
                elseif($Fret ==930)
                    $Pos_ori="10000 Fusées";
                elseif($Fret ==80)
                    $Pos_ori="1000 Rockets";
                elseif($Fret ==200)
                    $Pos_ori="Troupes IA";
                elseif($Fret ==300)
                    $Pos_ori="250 Charges";
                elseif($Fret ==400)
                    $Pos_ori="250 Mines";
                elseif($Fret ==800)
                    $Pos_ori="100 Torpilles";
                elseif($Fret ==1200)
                    $Pos_ori="Obus de 200mm";
                elseif($Fret ==9050 or $Fret ==9125 or $Fret ==9250 or $Fret ==9500)
                    $Pos_ori="Bombes de ".substr($Fret,1)."kg";
                elseif($Fret >9999)
                    $Pos_ori="Bombes de ".substr($Fret,0,-1)."kg";
                else
                    $Pos_ori="Obus de ".$Fret."mm";
                if($Fret and $Faction ==$Faction_Flag and $Faction ==$Faction_Gare and $ValeurStrat >3)
                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='Dech' value='".$Fret."'><input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td>0</td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                if($Vehicule_Nbr <1 and $NoeudF >0 and $Faction == $Faction_Flag and $Faction ==$Faction_Gare and $Credits >=4)
                    $Renforts_txt='<tr><td>
                                            <form action="index.php?view=ground_em_ia_go" method="post">
                                                <input type="hidden" name="renf" value="3">
                                                <input type="hidden" name="Unit" value="'.$Unit.'">
                                                <input class="btn btn-sm btn-warning" type="submit" value="Réparer">
                                            </form>
                                        </td>
                                        <td><div class="i-flex"><img src="images/CT4.png" title="Credits Temps nécessaires pour exécuter cette action"><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></div></td>
                                   </tr>';
                else
                    $Renforts_txt='<tr><td colspan="3" class="text-center text-danger">Réparation impossible<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Une gare en bon état contrôlée par votre faction est nécessaire pour la réparation</span></a></td></tr>';
            }
            elseif($Ordres_Cdt or $Ordres_Adjoint or $Ordres_Div or $Ordres_Armee or $Ordres_Bat or ($Type_Veh ==1 and $Ordres_Log)) // Terrestre
            {
                $Autos=GetAuto($Front,$Latitude,$Longitude);
                $Autonomie_Max=$Autos[0];
                $Autonomie_Mini=$Autos[1];
                $Dist_train_max=$Autos[2];
                if($Placement ==11)$Faction_Plage=GetData("Pays","ID",$Flag_Plage,"Faction");
                if($Placement ==11 and $Faction_Plage !=$Faction)
                    $Placements="<div class='alert alert-danger'>Cette unité ne peut quitter la plage tant que vos troupes ne contrôlent pas la zone</div>";
                elseif($Atk and $mobile ==3)
                    $Placements="<div class='alert alert-danger'>Vos troupes ne peuvent se déplacer directement après une attaque</div>";
                elseif($Position !=12 and $Position !=13)
                {
                    if(!$Pas_libre)
                    {
                        if(($Pont_Ori || $Fleuve) && !$Pont && !$Amphi)
                        {
                            if($Flag_Pont)$Faction_Pont=GetData("Pays","ID",$Flag_Pont,"Faction");
                            if($Faction_Pont !=$Faction)
                                $Pont_detruit=true;
                            else
                                $Pont_strat=true;
                            if($Pont_detruit and !$Pont_strat)
                                $Pont_block=true;
                        }
                        $Placements="<form action='ground_em_ia_go.php' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='zone' class='form-control' style='max-width:200px; display:inline;'>
                                    <option value='0'>Ne rien changer</option>";
                        if(!$Pont_block)
                            $Placements.="<option value='10'>Caserne</option>";
                        if($BaseAerienne >0 and !$Pont_block)
                            $Placements.="<option value='1'>Aérodrome</option>";
                        if($NoeudF_Ori >0 and !$Pont_block)
                            $Placements.="<option value='3'>Gare</option>";
                        if($Plage >0)
                            $Placements.="<option value='11'>Plage</option>";
                        if($Pont_Ori >0)
                            $Placements.="<option value='5'>Pont</option>";
                        elseif($Fleuve)
                            $Placements.="<option value='5'>Fleuve</option>";
                        if($Port_Ori >0 and !$Pont_block)
                            $Placements.="<option value='4'>Port</option>";
                        if($Radar_Ori >0 and !$Pont_block)
                            $Placements.="<option value='7'>Radar</option>";
                        if($NoeudR >0 and !$Pont_block)
                            $Placements.="<option value='2'>Route</option>";
                        if($Industrie >0 and !$Pont_block)
                            $Placements.="<option value='6'>Usine</option>";
                        $Placements.='</select><input class="btn btn-sm btn-warning" type="submit" value="Changer" onclick="this.disabled=true;this.form.submit();"></form>';
                        if(!$Faction_Gare)$Faction_Gare=GetData("Pays","ID",$Flag_Gare,"Faction");
                        if($Placement ==3 and $Faction_Flag ==$Faction and $Faction_Gare ==$Faction)
                            $Placement_help='Les déplacements depuis une gare contrôlée par votre faction vers une autre gare contrôlée par votre faction sont de <b>'.$Dist_train_max.'km</b> sur ce front<br>Le niveau d\'infrastructure de la gare de départ et de la gare d\'arrivée doivent être supérieurs à 10% pour bénéficier de ce type de déplacement. Actuellement la gare de départ a un niveau de <b>'.$data['NoeudF'].'%</b>';
                    }
                    else
                        $Placements='<span class="text-danger">En combat</span>';
                }
                else
                    $Placements='<span class="text-warning">En transit</span>';
                /*Lieux*/
                if(!$Move and $Position !=12 and $Position !=13)
                {
                    $Fiabilite=0;
                    $Rasputitsa=false;
                    $Merzlota=false;
                    $Mousson=false;
                    $Skill_auto_bonus=false;
                    $Enis_combi=0;
                    if(($Pays_Ori ==8 or $Pays_Ori ==20) and ($Mois ==11 or $Mois ==3)) //Rasputitsa
                        $Rasputitsa=true;
                    elseif($Front ==3)
                    {
                        if(($Longitude <=90 and ($Mois ==7 or $Mois ==8)) or ($Longitude >90 and ($Mois ==8 or $Mois ==9)))
                            $Mousson=true;
                    }
                    if(($Pays_Ori ==8 or $Pays_Ori ==20) and ($Mois ==12 or $Mois ==1 or $Mois ==2)) //Merzlota
                        $Merzlota=true;
                    if($NoeudR >0 and $Placement ==2 and !$Rasputitsa and !$Mousson and !$Enis_combi)
                        $Zone_calc=0;
                    else
                        $Zone_calc=$Zone;
                    $Autonomie_Min=Get_LandSpeed($Fuel,$mobile,$Zone_calc,0,$Type_Veh,0,0,$Amphi,$Front);
                    if($Skill ==44 or $Skill ==131)
                        $Ravit=2;
                    elseif($mobile ==3)
                    {
                        if($Skill ==23)$Autonomie_Min*=1.1;
                        elseif($Skill ==114)$Autonomie_Min*=1.2;
                        elseif($Skill ==115)$Autonomie_Min*=1.3;
                        elseif($Skill ==116)$Autonomie_Min*=1.4;
                        $Skill_auto_bonus=true;
                    }
                    if($Matos ==14)$Autonomie_Min*=1.5;
                    elseif($Matos ==15)$Autonomie_Min*=1.1;
                    elseif($Matos ==30)$Autonomie_Min*=1.5;
                    elseif($Matos ==28)$Autonomie_Min*=2;
                    elseif($Matos ==24 and $Zone ==8)$Autonomie_Min*=1.5;
                    if(($Type_Veh ==97 or $Mountain) and ($Zone_calc ==1 or $Zone_calc ==4 or $Zone_calc ==5)) //Montagnards
                        $Skill_auto_bonus=true;
                    if(($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7) and $Zone_calc ==0) //Mobile
                        $Skill_auto_bonus=true;
                    $Lat_min=$Latitude-6;
                    $Lat_max=$Latitude+6;
                    $Long_min=$Longitude-7;
                    $Long_max=$Longitude+7;
                    if($Fortification >=100 and $Garnison >50 and $Vehicule_Nbr >0 and $Faction !=$Faction_Flag and $Position !=6)
                    {
                        $output_dest="<div class='alert alert-danger'>Les forts ennemis contrôlant la région empêchent tout déplacement de l'unité!</div>";
                        $Autonomie_Min=0;
                        //Fuite
                        if(!$Atk)
                        {
                            $output_dest.="<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='reset' value='9'><input type='hidden' name='Max' value='".$Vehicule_Nbr."'>
                            <a href='#' class='popup'><input type='Submit' value='Fuir' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
                            <span>Cette action permettra à l'unité d'agir, mais réduira ses effectifs à 1</span></a></form>";
                        }
                    }
                    elseif($Autonomie_Min <$Autonomie_Mini)$Autonomie_Min=$Autonomie_Mini;
                    $Auto_Log=GetAutoLog($Front,$Latitude,$Longitude);
                    $con=dbconnecti();
                    //$Enis2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
                    $Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction<>'$Faction' AND i.Lieu_ID='$Lieu' AND i.Placement='$Placement' AND i.Vehicule_Nbr >0"),0);
                    $Enis_combi=$Enis+$Enis2;
                    if($G_Treve or ($G_Treve_Med and $Front ==2) or ($G_Treve_Est_Pac and ($Front ==1 or $Front ==4 or $Front ==3)))$query_treve=" AND Flag IN (".$Allies.")";
                    if($Front ==2)
                    {
                        if($Lieu ==343 or $Lieu ==344 or $Lieu ==445 or $Lieu ==529 or $Lieu ==2863 or $Lieu ==2864 or $Lieu ==2882 or $Lieu ==2884 or $Lieu ==2888 or $Lieu ==2889 or $Lieu ==2890 or $Lieu ==2891 or $Lieu ==2893 or $Lieu ==2925) //Iles isolées
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID='$Lieu'";
                        elseif($Lieu ==903 or $Lieu ==910 or $Lieu ==1090 or $Lieu ==1288 or $Lieu ==1653) //Crête
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (903,910,1090,1288,1653) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Lieu ==435 or $Lieu ==450 or $Lieu ==465 or $Lieu ==1644 or $Lieu ==1647 or $Lieu ==2127 or $Lieu ==2953 or $Lieu ==2954 or $Lieu ==2955 or $Lieu ==2956) //Sardaigne
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (435,450,465,1644,1647,2127,2953,2954,2955,2956) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Lieu ==2306 or $Lieu ==2307 or $Lieu ==2308 or $Lieu ==2309 or $Lieu ==2310 or $Lieu ==2957 or $Lieu ==2958) //Corse
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (2306,2307,2308,2309,2310,2957,2958) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Latitude >36.7 and $Latitude <38.2 and $Longitude >12.5 and $Longitude <=15.55) //Sicile
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Latitude BETWEEN 36.7 AND 38.2) AND (Longitude BETWEEN 12.5 AND 15.56) AND ID<>'$Lieu'".$query_treve." ORDER BY Nom ASC";
                        elseif($Latitude >36.6 and $Longitude >19 and $Longitude <26) //Grèce
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude >36.6 AND Longitude >19 AND Longitude <26 AND Zone<>6 AND PAYS NOT IN (2,4,6)".$query_treve." AND ID NOT IN ('$Lieu',2863,2864,2888,2889,2890,2891,2893) ORDER BY Nom ASC";
                        elseif($Pays_Ori ==6 and $Latitude >38.2 and $Longitude <19) //Italie
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Latitude BETWEEN 38.2 AND 45.5) AND (Longitude BETWEEN -2 AND 50) AND Zone<>6 AND PAYS NOT IN (10,24)".$query_treve." AND ID NOT IN ('$Lieu',435,450,465,1644,1647,2127,2306,2307,2308,2309,2310) ORDER BY Nom ASC";
                        elseif($Longitude >34 and $Longitude <45) //Moyen-Orient
                        {
                            $Autonomie_Max=100;
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude <37.3 AND Longitude >34 AND Longitude <50 AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',529) ORDER BY Nom ASC";
                        }
                        elseif($Latitude <36.7 or ($Longitude <12 and $Latitude <37.3 and $Pays_Ori !=6)) //AFN
                        {
                            $Autonomie_Max=100;
                            if($Latitude <33 and $Longitude <34 and $Longitude >11.22)
                            {
                                if($Longitude <25.16 and $Latitude >31.12)
                                    $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND 25.16) AND (Latitude BETWEEN '$Lat_min' AND 33.15) AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
                                elseif($Longitude >25.16 and $Latitude >31.12)
                                    $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 25.16 AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND 33) AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
                                else
                                    $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND 33.15) AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653)";
                            }
                            else
                                $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude <37.3 AND Longitude >-8 AND Longitude <50 AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC";
                        }
                        else
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',343,445,529,678,903,910,1090,1288,1653) ORDER BY Nom ASC LIMIT 100";
                    }
                    elseif($Front ==1 or $Front ==4 or $Front ==5)
                    {
                        if($Pays_Ori ==20)
                        {
                            if($Lat_min <60)$Lat_min=60;
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',1252) ORDER BY Nom ASC";
                        }
                        elseif($Latitude <46 and $Latitude>44.40 and $Longitude >33 and $Longitude <36.5) //Crimée
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 33 AND 36.5) AND (Latitude BETWEEN 44.4 AND 46.5) AND Zone<>6".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Latitude <47 and $Latitude>41 and $Longitude >37 and $Longitude <48) //Caucase
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 37 AND 50) AND (Latitude BETWEEN 41 AND 48) AND Zone<>6".$query_treve." AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Pays_Ori ==8)
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',1252) ORDER BY Nom ASC";
                        else
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',1252) ORDER BY Nom ASC";
                    }
                    elseif($Front ==3)
                    {
                        if($Lieu ==1610 or $Lieu ==1618 or $Lieu ==1637 or $Lieu ==1869 or $Lieu ==1894) //Ceylan
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1610,1618,1637,1869,1894) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Lieu ==1369 or $Lieu ==1722 or $Lieu ==1723 or $Lieu ==1859 or $Lieu ==1882 or $Lieu ==1883 or $Lieu ==1885 or $Lieu ==1886 or $Lieu ==1890) //Malaisie
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1369,1722,1723,1859,1882,1883,1885,1886,1890) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Longitude >90 and $Longitude <110 and $Latitude >1.20 and $Lieu !=1870 and $Lieu !=1903) //Continent
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN 90 AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',1754,1809,1870,1900) ORDER BY Nom ASC";
                        elseif($Longitude <90 and $Latitude >9) //Inde
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND 90) AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6".$query_treve." AND ID NOT IN ('$Lieu',1754,1809,1870,1900) ORDER BY Nom ASC";
                        elseif($Lieu ==1368 or $Lieu ==1556 or $Lieu ==1582 or $Lieu ==1776 or $Lieu ==1803 or $Lieu ==1805 or $Lieu ==1811 or $Lieu ==1857 or $Lieu ==2379 or $Lieu ==2380 or $Lieu ==2381) //Japon
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1368,1556,1582,1776,1803,1805,1811,1857,2379,2380,2381) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Lieu ==1583 or $Lieu ==1800 or $Lieu ==1801 or $Lieu ==1804) //Formose
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1583,1800,1801,1804) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Lieu ==1569 or $Lieu ==1570 or $Lieu ==1571 or $Lieu ==1764 or $Lieu ==1881 or $Lieu ==1888 or $Lieu ==1889 or $Lieu ==2353 or $Lieu ==2354 or $Lieu ==2355 or $Lieu ==2356 or $Lieu ==2357) //Philippines
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1569,1570,1571,1764,1881,1888,1889,2353,2354,2355,2356,2357) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Lieu ==1370 or $Lieu ==1574 or $Lieu ==1575 or $Lieu ==1576 or $Lieu ==1613 or $Lieu ==1892 or $Lieu ==1895 or $Lieu ==2358) //Java
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1370,1574,1575,1576,1613,1892,1895,2358) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Lieu ==1365 or $Lieu ==1809 or $Lieu ==1873 or $Lieu ==1887) //Sumatra
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1365,1809,1873,1887) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Lieu ==1573 or $Lieu ==1763 or $Lieu ==1865 or $Lieu ==1866 or $Lieu ==1972 or $Lieu ==2163 or $Lieu ==2214) //Australie
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID IN (1573,1763,1865,1866,1972,2163,2214) AND ID<>'$Lieu' ORDER BY Nom ASC";
                        else
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE ID='$Lieu'";
                    }
                    else
                    {
                        if($Pays_Ori ==1 or $Pays_Ori ==3 or $Pays_Ori ==4 or $Pays_Ori ==5 or $Pays_Ori ==6 or $Pays_Ori ==36)
                        {
                            if($Long_max >14)$Long_max=14;
                            if($Lat_min <41)$Lat_min=41;
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude BETWEEN '$Long_min' AND '$Long_max') AND (Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND Zone<>6 AND Pays IN(1,3,4,5,6,36) AND ID NOT IN ('$Lieu',704,896) ORDER BY Nom ASC";
                        }
                        elseif($Pays_Ori ==2)
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude >49 AND Longitude <14 AND Zone<>6 AND Pays=2 AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Pays_Ori==7)
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE (Longitude <-52 OR Longitude >235) AND Zone<>6 AND ID<>'$Lieu' ORDER BY Nom ASC";
                        elseif($Pays_Ori==35)
                            $query="SELECT ID,Nom,Longitude,Latitude,NoeudR,NoeudF,Occupant,Flag,Flag_Route,Flag_Gare,Zone,Impass FROM Lieu WHERE Latitude >58 AND Longitude >4.5 AND Zone<>6 AND ID<>'$Lieu' ORDER BY Nom ASC";
                    }
                    if(!$Pont_block and ($Skill_auto_bonus or $Matos ==14 or $Matos ==15 or $Matos ==28 or $Matos ==30))
                        $Autonomie_Max*=1.2;
                    $result=mysqli_query($con,$query) or (mail('binote@hotmail.com','ADA DEBUG : EMPTY QUERY', 'ground_em_ia_2254 : '.$query.' '.$Front.' '.$Pays_Ori.' '.$Lieu.' '.$Long_min.' '.$Long_max.' '.$Lat_min.' '.$Lat_max));
                    $resultdepot=mysqli_query($con,"SELECT DISTINCT l.ID,l.Longitude,l.Latitude,l.Stock_Essence_87,l.Stock_Essence_100,l.Stock_Essence_1,l.Stock_Munitions_13,l.Stock_Munitions_20,l.Stock_Munitions_30,l.Stock_Munitions_40,l.Stock_Munitions_50,l.Stock_Munitions_60,l.Stock_Munitions_75,l.Stock_Munitions_90,l.Stock_Munitions_105,l.Stock_Munitions_125,l.Stock_Munitions_150,l.Stock_Munitions_200,l.Stock_Munitions_300,l.Stock_Munitions_360
                    FROM Lieu as l,Pays as p WHERE l.ValeurStrat >3 AND (l.NoeudF_Ori=100 OR l.Port_Ori=100) AND l.Flag=p.Pays_ID AND p.Faction='$Faction' AND
                    (l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max')
                    UNION SELECT DISTINCT l.ID,l.Longitude,l.Latitude,d.Stock_Essence_87,d.Stock_Essence_100,d.Stock_Essence_1,l.Stock_Munitions_13,l.Stock_Munitions_20,l.Stock_Munitions_30,l.Stock_Munitions_40,l.Stock_Munitions_50,l.Stock_Munitions_60,l.Stock_Munitions_75,l.Stock_Munitions_90,l.Stock_Munitions_105,l.Stock_Munitions_125,l.Stock_Munitions_150,l.Stock_Munitions_200,l.Stock_Munitions_300,l.Stock_Munitions_360
                    FROM Depots as d,Regiment_IA as r,Lieu as l,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu' AND l.ID=r.Lieu_ID AND r.Placement=8 AND r.Vehicule_ID=5392 AND r.Vehicule_Nbr >0");
                    if($resultdepot)
                    {
                        while($datad=mysqli_fetch_array($resultdepot,MYSQLI_ASSOC))
                        {
                            $Distance_depot=GetDistance(0,0,$Longitude,$Latitude,$datad['Longitude'],$datad['Latitude']);
                            if($Distance_depot[0] <=$Auto_Log)
                            {
                                $Depots_region[]=$datad['ID'];
                                $Stock_87[]=$datad['Stock_Essence_87'];
                                $Stock_100[]=$datad['Stock_Essence_100'];
                                $Stock_1[]=$datad['Stock_Essence_1'];
                                $Stock_13[]=$datad['Stock_Munitions_13'];
                                $Stock_20[]=$datad['Stock_Munitions_20'];
                                $Stock_30[]=$datad['Stock_Munitions_30'];
                                $Stock_40[]=$datad['Stock_Munitions_40'];
                                $Stock_50[]=$datad['Stock_Munitions_50'];
                                $Stock_60[]=$datad['Stock_Munitions_60'];
                                $Stock_75[]=$datad['Stock_Munitions_75'];
                                $Stock_90[]=$datad['Stock_Munitions_90'];
                                $Stock_105[]=$datad['Stock_Munitions_105'];
                                $Stock_125[]=$datad['Stock_Munitions_125'];
                                $Stock_150[]=$datad['Stock_Munitions_150'];
                            }
                        }
                        mysqli_free_result($resultdepot);
                    }
                    $Stock_87_max=Array_max($Stock_87);
                    $Stock_100_max=Array_max($Stock_100);
                    $Stock_1_max=Array_max($Stock_1);
                    $Stock_13_max=Array_max($Stock_13);
                    $Stock_20_max=Array_max($Stock_20);
                    $Stock_30_max=Array_max($Stock_30);
                    $Stock_40_max=Array_max($Stock_40);
                    $Stock_50_max=Array_max($Stock_50);
                    $Stock_60_max=Array_max($Stock_60);
                    $Stock_75_max=Array_max($Stock_75);
                    $Stock_90_max=Array_max($Stock_90);
                    $Stock_105_max=Array_max($Stock_105);
                    $Stock_125_max=Array_max($Stock_125);
                    $Stock_150_max=Array_max($Stock_150);
                    if($Type_Veh ==93)
                        $Vehicule_Nbr_Conso=ceil($Vehicule_Nbr/10);
                    else
                        $Vehicule_Nbr_Conso=$Vehicule_Nbr;
                    $Conso_move=($Autonomie_Min*$Vehicule_Nbr_Conso)/5;
                    if($Nation_IA or !$Carbu)
                    {
                        $Octane1='';
                        $Colorc1="warning";
                        $Stock_carbu=65000;
                    }
                    elseif($Carbu ==100)
                    {
                        $Octane1=" Octane 100";
                        $Colorc1="danger";
                        $Stock_carbu=$Stock_100_max;
                    }
                    elseif($Carbu ==1)
                    {
                        $Octane1=" Diesel";
                        $Colorc1="success";
                        $Stock_carbu=$Stock_1_max;
                    }
                    elseif($Carbu ==87)
                    {
                        $Octane1=" Octane 87";
                        $Colorc1="primary";
                        $Stock_carbu=$Stock_87_max;
                    }
                    else{
                        $Octane1='';
                        $Colorc1="warning";
                        $Stock_carbu=0;
                    }
                    $Carte_Log="<a href='carte_ground.php?map=".$Front."&mode=12&cible=".$Lieu."' class='btn btn-sm btn-primary' onclick='window.open(this.href); return false;'>Carte logistique</a>";
                    $Dist_max_ori=$Autonomie_Min;
                    if($result)
                    {
                        while($data=mysqli_fetch_array($result))
                        {
                            $CT_city=0;
                            $coord=0;
                            $Train_move=false;
                            $train_txt='';
                            $Distance=GetDistance(0,0,$Longitude,$Latitude,$data[2],$data[3]);
                            $Dist_km=$Distance[0];
                            $lieux_obj.='<option value="'.$data[0].'">'.$data[1].' ('.$Dist_km.'km)</option>';
                            $Faction_Dest=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag']."'"),0);
                            if($data['NoeudR'])
                                $Faction_Dest_Route=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Route']."'"),0);
                            if($data['NoeudF'])
                                $Faction_Dest_Gare=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='".$data['Flag_Gare']."'"),0);
                            if($Faction_Flag ==$Faction and $NoeudF >10 and $data['NoeudF'] >10 and $Placement ==3 and !$Enis_combi and $Faction_Dest_Gare ==$Faction and $Faction_Dest ==$Faction and !$Pont_block)
                            {
                                $Dist_max=$Dist_train_max;
                                $train_txt=" - Train";
                                $Train_move=true;
                            }
                            elseif($data['NoeudR'] and $NoeudR >0 and $Placement ==2 and !$Rasputitsa and !$Enis_combi and $Faction_Dest_Route ==$Faction)
                                $Dist_max=$Autonomie_Min*2;
                            else
                                $Dist_max=$Autonomie_Min;
                            if(!$Train_move)
                            {
                                if($Type_Veh ==6 and !$Mountain and ($data['Zone'] ==4 or $data['Zone'] ==5)) //L'artillerie non motorisée ne peut pas aller en montagne
                                    $Dist_km=999;
                                if(($Rasputitsa and $Matos !=24) or ($Merzlota and $mobile !=3))
                                {
                                    if($Rasputitsa and $Zone!=2 and $Zone!=3 and $Zone!=4 and $Zone!=5 and $Zone!=7 and $mobile!=3)
                                        $Dist_km*=1.25;
                                }
                                if($Faction !=$Faction_Dest)
                                {
                                    if($Enis_combi and $Trait !=16)
                                        $Dist_km*=2;
                                    else
                                        $Dist_km*=1.5;
                                    if($Premium and $Dist_km <=$Dist_max and $Dist_km <=$Autonomie_Max)$txt_help_dist.="<br>La distance à parcourir vers ".$data[1]." est augmentée de 50% (<span class='text-danger'>".$Dist_km."</span>km) car ".$data[1]." est contrôlé par l'ennemi. (1)";
                                }
                                if($Dist_max >$Autonomie_Max)$Dist_max=$Autonomie_Max;
                            }
                            if($Dist_km <=$Dist_max)
                            {
                                //Affichage en rouge si destination hors de portée des dépôts
                                $dest_txt_css='text-danger';
                                if(is_array($Depots_region)){
                                    $Depots_list=implode(",",$Depots_region);
                                    $resultdr=mysqli_query($con,"SELECT Nom,Longitude,Latitude FROM Lieu WHERE ID IN (".$Depots_list.")");
                                    if($resultdr){
                                        while($datadr=mysqli_fetch_array($resultdr)){
                                            $Distance_depot_region=GetDistance(0,0,$data[2],$data[3],$datadr['Longitude'],$datadr['Latitude']);
                                            if($Distance_depot_region[0] <=$Auto_Log){
                                                $dest_txt_css='text-primary';
                                            }
                                        }
                                        mysqli_free_result($resultdr);
                                    }
                                }
                                $Impass=$data['Impass'];
                                $sensh='';
                                $sensv='';
                                if($Premium and $Dist_km <=$Dist_max)$txt_help_dist.="<br>La distance à parcourir vers ".$data[1]." est de <span class='text-primary'>".$Dist_km."km</span> et l'autonomie de l'unité est de ".$Dist_max.", permettant le déplacement vers ce lieu.";
                                if($Longitude >$data[2])
                                {
                                    $sensh='Ouest';
                                    $coord+=2;
                                    if($Impass ==2 or $Impass ==3 or $Impass ==4 or $Impass_ori ==6 or $Impass_ori ==7 or $Impass_ori ==8)
                                        $CT_city=999;
                                }
                                elseif($Longitude <$data[2])
                                {
                                    $sensh='Est';
                                    $coord+=1;
                                    if($Impass ==6 or $Impass ==7 or $Impass ==8 or $Impass_ori ==2 or $Impass_ori ==3 or $Impass_ori ==4)
                                        $CT_city=999;
                                }
                                if($sensh)
                                {
                                    if($Latitude >$data[3]+0.25)
                                    {
                                        $sensv='Sud';
                                        $coord+=20;
                                        if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
                                            $CT_city=999;
                                    }
                                    elseif($Latitude <$data[3]-0.25)
                                    {
                                        $sensv='Nord';
                                        $coord+=10;
                                        if($Impass == 4 or $Impass == 5 or $Impass == 6 or $Impass_ori ==1 or $Impass_ori == 2 or $Impass_ori == 8)
                                            $CT_city=999;
                                    }
                                }
                                else
                                {
                                    if($Latitude >$data[3])
                                    {
                                        $sensv='Sud';
                                        $coord+=20;
                                        if($Impass ==1 or $Impass ==2 or $Impass ==8 or $Impass_ori ==4 or $Impass_ori ==5 or $Impass_ori ==6)
                                            $CT_city=999;
                                    }
                                    elseif($Latitude <$data[3])
                                    {
                                        $sensv='Nord';
                                        $coord+=10;
                                        if($Impass ==4 or $Impass ==5 or $Impass ==6 or $Impass_ori ==1 or $Impass_ori ==2 or $Impass_ori ==8)
                                            $CT_city=999;
                                    }
                                }
                                $sens=$sensv.' '.$sensh;
                                if($data['NoeudR'])$sens.=" - route";
                                if($CT_city <999)
                                {
                                    if($Placement ==3 and $data['NoeudF'])
                                        $icone="<a href='#' class='popup'><img src='images/rail.gif' title='Noeud Ferroviaire'><span><b>Noeud Ferroviaire</b> Les unités se déplaçant entre deux noeuds ferroviaires contrôlés par leur faction doublent leur distance de déplacement et ignorent les pénalités de déplacement dues au relief.</span></a>";
                                    elseif($data['NoeudR'] and !$Rasputitsa)
                                        $icone="<a href='#' class='popup'><img src='images/route.gif'><span><b>Noeud Routier</b><ul><li>Les unités se déplaçant depuis un noeud routier ne subissent pas les malus dus au terrain.</li><li>Les unités se déplaçant entre deux noeuds routiers contrôlés par leur faction doublent leur distance de déplacement.</li><li>Les unités ennemies présentent sur le noeud routier (transformant la zone en zone de combat) annulent automatiquement tout bonus de déplacement.</li></ul></span></a>";
                                    else
                                        $icone="<img src='images/zone".$data['Zone'].".jpg'>";
                                    if(($Stock_carbu >=($Dist_max*$Vehicule_Nbr_Conso/10)) or $Ravit)
                                    {
                                        $modal_conso='<div class="alert alert-danger">Le déplacement rendra l\'unité inaccessible pendant 24h';
                                        if($mobile !=4 and $mobile !=5 and $Carbu)$modal_conso.=' et consommera '.$Conso_move.'L '.$Octane1;
                                        if($mobile !=3 and $mobile !=4)$modal_conso.='<br>L\'unité arrivera en mouvement, pensez à changer sa position une fois arrivé à destination';
                                        if($dest_txt_css =='text-danger')$modal_conso.='<br><b>ATTENTION</b> : Cette destination se trouve hors de portée du ravitaillement de nos dépôts !';
                                        //$Lieux.="<option class='".$dest_txt_css."' value='".$data[0]."'>".$data[1]." (".$Dist_km."km ".$sens.$train_txt.")</option>";
                                        $choix="<tr><td><a href='#' class='".$dest_txt_css."' data-toggle='modal' data-target='#modal-dest-".$data[0]."'><img src='images/".$data['Flag']."20.gif'> ".$data[1]."</a></td><td>".$icone."</td><td>".$Dist_km."km</td></tr>";
                                        $lieux_modal.='<div class="modal fade" id="modal-dest-'.$data[0].'" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title">Déplacement
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </h2>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img class="img-flex" src="images/move_front'.$country.'.jpg">
                                                                    <div class="alert alert-warning">Le '.$Cie.'e bataillon composé de '.$Vehicule_Nbr.' '.$Veh_Nom.' se déplacera vers <b>'.$data[1].'</b></div>
                                                                    <form action="ground_em_ia_go.php" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu.'"><input type="hidden" name="cible" value="'.$data[0].'"><input class="btn btn-danger" type="submit" value="confirmer"></form>'.$modal_conso.'</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';
                                    }
                                    else
                                        $choix="<tr><td><img src='images/".$data['Flag']."20.gif'> ".$data[1]."</td><td>".$icone."</td><td>".$Dist_km."km <a href='#' class='popup'><img src='images/map/carbu_icon_empty.png'><span>Pénurie de carburant dans le dépôt</span></a></td></tr>";
                                    if($coord ==1) //Est
                                        $Est_txt.=$choix;
                                    elseif($coord == 2) //Ouest
                                        $Ouest_txt.=$choix;
                                    elseif($coord ==10) //Nord
                                        $Nord_txt.=$choix;
                                    elseif($coord == 20) //Sud
                                        $Sud_txt.=$choix;
                                    elseif($coord ==11) //NE
                                        $NE_txt.=$choix;
                                    elseif($coord == 21) //SE
                                        $SE_txt.=$choix;
                                    elseif($coord ==12) //NO
                                        $NO_txt.=$choix;
                                    elseif($coord == 22) //SO
                                        $SO_txt.=$choix;
                                }
                            }
                            elseif($Premium and $Dist_km <=$Autonomie_Max)
                            {
                                $txt_help_dist.="<br>La distance à parcourir vers ".$data[1]." est de <span class='text-danger'>".$Dist_km."km</span> et l'autonomie de l'unité est de ".$Dist_max.", ne permettant pas le déplacement vers ce lieu.";
                                if(!$Train_move)$txt_help_dist.=" Aucun noeud ferroviaire n'est utilisé.";
                            }
                        }
                        mysqli_free_result($result);
                    }
                    mysqli_close($con);
                    $Carte_Bouton.="<div class='btn btn-sm btn-primary'><a href='carte_ground.php?map=".$Front."&mode=10&cible=".$Lieu."&reg=".$Unit."' onclick='window.open(this.href); return false;'>Voir la carte</a></div>";
                    //Retraite stratégique
                    if($Lieu !=$Retraite and (!$Vehicule_Nbr or (!$lieux_modal and !$Enis_combi)))
                    {
                        $Retraite_ghq=$Retraite;
                        $Retraite_ok=true;
                    }
                    if($GHQ and $Type_Veh ==95 and (!$Vehicule_Nbr or (!$lieux_modal and !$Enis_combi)))
                    {
                        $Retraite_ghq=Get_Retraite($Front,$country,40);
                        $Retraite_ok=true;
                    }
                    if($Retraite_ok){
                        $Retraite_ghq_Nom=GetData("Lieu","ID",$Retraite_ghq,"Nom");
                        $choix="<a href='#' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#modal-dest-".$Retraite_ghq."_'>Retraite vers ".$Retraite_ghq_Nom."</a>";
                        $lieux_modal.='<div class="modal fade" id="modal-dest-'.$Retraite_ghq.'_" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title">Déplacement
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </h2>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img class="img-flex" src="images/move_front'.$country.'.jpg">
                                                                    <div class="alert alert-warning">Le '.$Cie.'e bataillon composé de '.$Vehicule_Nbr.' '.$Veh_Nom.' effectuera une retraite vers <b>'.$Retraite_ghq_Nom.'</b></div>
                                                                    <form action="ground_em_ia_go.php" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><input type="hidden" name="base" value="'.$Lieu.'"><input type="hidden" name="cible" value="'.$Retraite_ghq.'_"><input class="btn btn-danger" type="submit" value="confirmer"></form><div class="alert alert-danger"><b>ATTENTION</b><br>Les troupes seront perdues, de même que l\'expérience et la compétence de l\'unité!</div></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';
                        $Centre_txt.=$choix;
                    }
                    if($Type_Veh ==92 or $Type_Veh ==96) //Paras
                    {
                        if($BaseAerienne >0 and $Placement ==1)
                        {
                            $con=dbconnecti();
                            $Trans_esc=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit WHERE Base='$Lieu' AND Type=6 AND Pays='$country' AND Etat=1"),0);
                            mysqli_close($con);
                            if($Trans_esc)
                            {
                                if($Type_Veh ==92)
                                    $Para_pos=13;
                                else
                                    $Para_pos=12;
                                $Decharger.="<br><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='".$Para_pos."'><input type='submit' value='Parachutage' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                            }
                            else
                                $Decharger.="<div class='alert alert-info'>Les unités parachutistes présentes sur un aérodrome peuvent être parachutées par des escadrilles de transport de leur nation basées sur le même aérodrome.</div>";
                        }
                        else
                            $Decharger.="<div class='alert alert-info'>Les unités parachutistes présentes sur un aérodrome peuvent être parachutées par des escadrilles de transport de leur nation basées sur le même aérodrome.</div>";
                    }
                    //Renforts
                    if($Placement ==6 and (($Lieu ==$Usine1 and $Usine1 >0) or ($Lieu ==$Usine2 and $Usine2 >0) or ($Lieu ==$Usine3 and $Usine3 >0)))$Sur_usine=true;
                    if($Credits >=1 and $Vehicule_Nbr <$Max_Veh and (($Lieu ==$Retraite and $Retraite >0) or $Sur_usine))
                    {
                        $con=dbconnecti(4);
                        $Perdus=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$Vehicule'"),0);
                        $Perdus2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$Vehicule'"),0);
                        if($Categorie ==5 or $Categorie ==6)
                            $Perdus3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$Vehicule'"),0);
                        mysqli_close($con);
                        $con=dbconnecti();
                        //$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$Vehicule'"),0);
                        $Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$Vehicule'"),0);
                        $Enis_oq=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
                        $Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND p.Faction='$Faction'"),0);
                        mysqli_close($con);
                        if($Production >0)
                        {
                            $Perdus=$Perdus+$Perdus2+$Perdus3;
                            if($Repare >$Perdus)$Repare=$Perdus;
                            $Reste=$Stock-$Service-$Service2-$Perdus+$Repare;
                            if($Reste+$Service+$Service2 >$Stock)$Reste=$Stock-$Service-$Service2;
                            if($Max_Veh >25)
                            {
                                $up_renf=floor($Max_Veh/10);
                                $down_exp=floor($up_renf/10);
                            }
                            else
                            {
                                $up_renf=floor($Max_Veh/4);
                                $down_exp=floor($up_renf/2);
                            }
                            if($up_renf >$Stock)$up_renf=$Stock;
                            if($up_renf <1)$up_renf=1;
                        }
                        else
                            $Reste=0;
                        if($Enis_oq >0)
                            $Renforts_txt='<tr><td class="text-danger" colspan="3">Base arrière sous le feu ennemi!</td></tr>';
                        elseif($Embout >5)
                            $Renforts_txt='<tr><td class="text-danger" colspan="3">Trop de troupes sur cette zone!</td></tr>';
                        elseif($Type_Veh ==95)
                        {
                            if(!$Vehicule_Nbr)
                            {
                                if($Credits >=$Reput_Renf){
                                    $Renforts_txt='<tr><td>
                                                            <form action="ground_em_ia_go.php" method="post">
                                                                <input type="hidden" name="renf" value="1">
                                                                <input type="hidden" name="Unit" value="'.$Unit.'">
                                                                <input type="hidden" name="Max" value="'.$Max_Veh.'">
                                                                <img src="images/CT'.$Reput_Renf.'.png" title="Credits Temps nécessaires pour exécuter cette action">
                                                                <input class="btn btn-sm btn-warning" type="submit" value="Renforts">
                                                            </form>
                                                        </td>
                                                        <td>'.$Reste.'</td>
                                                        <td>'.$up_renf.'</td>
                                                   </tr>';
                                }
                                else
                                    $Renforts_txt='<tr><td colspan="3">Crédits Temps insuffisants ('.$Credits.'/'.$Reput_Renf.')</td></tr>';
                            }
                        }
                        elseif($Reste >0)
                        {
                            $Reput_Renf_ori=$Reput_Renf;
                            $Usine1_Nom=GetData("Lieu","ID",$Usine1,"Nom");
                            if(!$Retraite_Nom)$Retraite_Nom=GetData("Lieu","ID",$Retraite,"Nom");
                            if($Sur_usine or ($Trait ==2 and ($Type_Veh ==92 or $Type_Veh ==96 or $Type_Veh ==97)))
                                $Reput_Renf=1;
                            elseif($Trait ==3 and ($Categorie ==1 or $Categorie ==2 or $Categorie ==3 or $Type_Veh ==8))
                                $Reput_Renf=floor($Reput_Renf/2);
                            elseif($Trait ==4 and ($Categorie ==5 or $Categorie ==6 or $Type_Veh ==6 or $Type_Veh ==12))
                                $Reput_Renf=floor($Reput_Renf/2);
                            if($Credits >=$Reput_Renf){
                                $Renforts_txt='<tr><td>
                                                       <form action="ground_em_ia_go.php" method="post">
                                                                <input type="hidden" name="renf" value="1">
                                                                <input type="hidden" name="Unit" value="'.$Unit.'">
                                                                <input type="hidden" name="Max" value="'.$Max_Veh.'">
                                                                <img src="images/CT'.$Reput_Renf.'.png" title="Credits Temps nécessaires pour exécuter cette action">
                                                                <input class="btn btn-sm btn-warning" type="submit" value="Renforts">
                                                       </form>
                                                    </td>
                                                    <td>'.$Reste.'</td>
                                                    <td>'.$up_renf.'<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Pour se renforcer, l\'unité doit se trouver sur la base arrière <b>'.$Retraite_Nom.'</b> pour un coût de <b>'.$Reput_Renf_ori.'CT</b>, ou sur l\'usine de production <b>'.$Usine1_Nom.'</b> pour un coût de <b>1CT</b>. Compte comme une action du jour.</span></a></td>
                                               </tr>';
                            }
                            else
                                $Renforts_txt='<tr><td colspan="3">Crédits Temps insuffisants ('.$Credits.'/'.$Reput_Renf.')</td></tr>';
                        }
                        else
                            $Renforts_txt='<tr><td colspan="3">Troupes non disponibles ('.$Credits.'/'.$Reput_Renf.')<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Votre nation doit réparer les véhicules concernés</span></a></td></tr>';
                    }
                    elseif($Vehicule_Nbr <$Max_Veh)
                    {
                        if(!$Retraite_Nom)$Retraite_Nom=GetData("Lieu","ID",$Retraite,"Nom");
                        $Usine1_Nom=GetData("Lieu","ID",$Usine1,"Nom");
                        $Renforts_txt='<tr><td colspan="3" class="text-center text-warning">Effectifs incomplets<a href="#" class="popup"><div class="i-flex help_icon"></div><span>Pour se renforcer, l\'unité doit se trouver sur la base arrière <b>'.$Retraite_Nom.'</b> pour un coût de <b>'.$Reput_Renf.'CT</b>, ou sur l\'usine de production <b>'.$Usine1_Nom.'</b> pour un coût de <b>1CT</b>. Compte comme une action du jour.</span></a></td></tr>';
                    }
                    else
                        $Renforts_txt='<tr><td colspan="3" class="text-success text-center">Effectifs au maximum</td></tr>';
                }
                elseif(!$Move and $Position ==12){
                    $Decharger.="<br><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='pos' value='4'><input type='submit' value='Annuler le Parachutage' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
                }elseif(!$Move)
                    $Lieux_txt='Pas avant demain';
                if(!$GHQ or $Admin or $Nation_IA)
                {
                    //Revendication
                    if ($Nation_IA && $Pays_Ori != $country) {
                        $revendication = false;
                    } elseif(($Type_Veh ==95 or $Detection >10) and $Position !=6 and $Position !=11 and $Position !=12 and $Position !=13 and $Position !=14 and $Credits >=2 and !$Enis_combi and !$Move and $Vehicule_Nbr >0) {
                        $revendication = true;
                    }
                    if($revendication == true)
                    {
                        if($Recce or !$ValeurStrat or $Placement >0)
                        {
                            if($Type_Veh ==95)
                                $Rev_mode=2;
                            else
                                $Rev_mode=3;
                            if($Placement >0)
                            {
                                if($Placement ==1)
                                {
                                    $con=dbconnecti();
                                    $Faction_Place=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$Flag_Air'"),0);
                                    $Esc_Oqp=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit as u,Pays as p WHERE u.Base='$Lieu' AND u.Pays=p.ID AND p.Faction<>'$Faction' AND Etat=1 AND Garnison >0"),0);
                                    mysqli_close($con);
                                }
                                elseif($Placement ==2)
                                    $Faction_Place=GetData("Pays","ID",$Flag_Route,"Faction");
                                elseif($Placement ==3)
                                    $Faction_Place=GetData("Pays","ID",$Flag_Gare,"Faction");
                                elseif($Placement ==4)
                                    $Faction_Place=GetData("Pays","ID",$Flag_Port,"Faction");
                                elseif($Placement ==5)
                                    $Faction_Place=GetData("Pays","ID",$Flag_Pont,"Faction");
                                elseif($Placement ==6)
                                    $Faction_Place=GetData("Pays","ID",$Flag_Usine,"Faction");
                                elseif($Placement ==7)
                                    $Faction_Place=GetData("Pays","ID",$Flag_Radar,"Faction");
                                elseif($Placement ==11)
                                    $Faction_Place=GetData("Pays","ID",$Flag_Plage,"Faction");
                                if($Faction !=$Faction_Place and !$Esc_Oqp)
                                    $Atk_Options.="<tr><td><form action='ground_em_ia_go.php' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='cible' value='".$Lieu."'><input type='hidden' name='rev' value='".$Rev_mode."'>
                                        <input type='submit' value='Revendiquer' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></td>
                                        <td><div class='i-flex'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Revendiquer compte comme action du jour</span></a></div></td>
                                        <td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Revendiquer un lieu stratégique nécessite que le lieu soit reconnu soit via une reco terrestre ou une reco stratégique.<br>Les lieux non stratégiques peuvent être revendiqués sans reco préalable.<br>Pour revendiquer une caserne, la garnison doit être éliminée au préalable.</span></a></td></tr>";
                                elseif($Placement ==1 and $Esc_Oqp)
                                    $Atk_Options.="<div class='alert alert-danger'>Des avions ennemis occupent l'aérodrome</div>";
                            }
                            elseif($Placement ==0 and $Faction_Flag !=$Faction and $Garnison <1 and ($Recce or !$ValeurStrat))
                            {
                                $Rev_ok=false;
                                $Faction_Ori=GetData("Pays","ID",$Pays_Ori,"Faction");
                                if($Faction ==$Faction_Ori)
                                {
                                    $Pays_Rev=$Pays_Ori;
                                    $Faction_Rev=$Faction_Ori;
                                }
                                else
                                {
                                    $Pays_Rev=$country;
                                    $Faction_Rev=$Faction;
                                }
                                if($Flag_Pont and !$Faction_Pont)$Faction_Pont=GetData("Pays","ID",$Flag_Pont,"Faction");
                                if($Flag_Port)$Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
                                if($Flag_Gare)$Faction_Gare=GetData("Pays","ID",$Flag_Gare,"Faction");
                                if($Flag_Route)$Faction_Route=GetData("Pays","ID",$Flag_Route,"Faction");
                                if($Flag_Air)$Faction_Air=GetData("Pays","ID",$Flag_Air,"Faction");
                                if($Flag_Usine)$Faction_Usine=GetData("Pays","ID",$Flag_Usine,"Faction");
                                if($Flag_Radar)$Faction_Radar=GetData("Pays","ID",$Flag_Radar,"Faction");
                                if($Flag_Plage)$Faction_Plage=GetData("Pays","ID",$Flag_Plage,"Faction");
                                if($ValeurStrat ==10)
                                {
                                    $Rev_ok=true;
                                    if(($Pont_Ori or $Fleuve) and $Faction_Pont !=$Faction_Rev)
                                        $Rev_ok=false;
                                    if($Port_Ori and $Faction_Port !=$Faction_Rev)
                                        $Rev_ok=false;
                                    if($NoeudF_Ori and $Faction_Gare !=$Faction_Rev)
                                        $Rev_ok=false;
                                    if($NoeudR and $Faction_Route !=$Faction_Rev)
                                        $Rev_ok=false;
                                    if($Cible_base and $Faction_Air !=$Faction_Rev)
                                        $Rev_ok=false;
                                    if($Usine and $Faction_Usine !=$Faction_Rev)
                                        $Rev_ok=false;
                                    if($Radar_Ori and $Faction_Radar !=$Faction_Rev)
                                        $Rev_ok=false;
                                    if($Plage and $Faction_Plage !=$Faction_Rev)
                                        $Rev_ok=false;
                                }
                                elseif($ValeurStrat >5)
                                {
                                    //3 zones
                                    $Rev_part=0;
                                    if($Pont_Ori or $Fleuve)
                                    {
                                        if($Faction_Pont ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Port_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve)))
                                    {
                                        if($Faction_Port ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($NoeudF_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori)))
                                    {
                                        if($Faction_Gare ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($NoeudR and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori)))
                                    {
                                        if($Faction_Route ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Cible_base and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR)))
                                    {
                                        if($Faction_Air ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Usine and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base)))
                                    {
                                        if($Faction_Usine ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Radar_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base and !$Usine)))
                                    {
                                        if($Faction_Radar ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Plage and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base and !$Usine and !$Radar_Ori)))
                                    {
                                        if($Faction_Plage ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Rev_part >=2)
                                        $Rev_ok=true;
                                }
                                elseif($ValeurStrat >3)
                                {
                                    //2 zones
                                    $Rev_part=0;
                                    if($Pont_Ori or $Fleuve)
                                    {
                                        if($Faction_Pont ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Port_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve)))
                                    {
                                        if($Faction_Port ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($NoeudF_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori)))
                                    {
                                        if($Faction_Gare ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($NoeudR and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori)))
                                    {
                                        if($Faction_Route ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Cible_base and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR)))
                                    {
                                        if($Faction_Air ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Usine and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base)))
                                    {
                                        if($Faction_Usine ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Radar_Ori and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base and !$Usine)))
                                    {
                                        if($Faction_Radar ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Plage and ($Rev_part or (!$Pont_Ori and !$Fleuve and !$Port_Ori and !$NoeudF_Ori and !$NoeudR and !$Cible_base and !$Usine and !$Radar_Ori)))
                                    {
                                        if($Faction_Plage ==$Faction_Rev)
                                            $Rev_part+=1;
                                    }
                                    if($Rev_part >=1)
                                        $Rev_ok=true;
                                }
                                elseif($ValeurStrat >0)
                                {
                                    if($Pont_Ori or $Fleuve)
                                    {
                                        if($Faction_Pont ==$Faction_Rev)
                                            $Rev_ok=true;
                                    }
                                    elseif($Port_Ori)
                                    {
                                        if($Faction_Port ==$Faction_Rev)
                                            $Rev_ok=true;
                                    }
                                    elseif($NoeudF_Ori)
                                    {
                                        if($Faction_Gare ==$Faction_Rev)
                                            $Rev_ok=true;
                                    }
                                    elseif($NoeudR)
                                    {
                                        if($Faction_Route ==$Faction_Rev)
                                            $Rev_ok=true;
                                    }
                                    elseif($Cible_base)
                                    {
                                        if($Faction_Air ==$Faction_Rev)
                                            $Rev_ok=true;
                                    }
                                    elseif($Usine)
                                    {
                                        if($Faction_Usine ==$Faction_Rev)
                                            $Rev_ok=true;
                                    }
                                    elseif($Radar_Ori)
                                    {
                                        if($Faction_Radar ==$Faction_Rev)
                                            $Rev_ok=true;
                                    }
                                    elseif($Plage)
                                    {
                                        if($Faction_Plage ==$Faction_Rev)
                                            $Rev_ok=true;
                                    }
                                    else
                                        $Rev_ok=true;
                                }
                                else
                                    $Rev_ok=true;
                                if($Rev_ok)
                                    $Atk_Options.="<tr><td><form action='ground_em_ia_go.php' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='cible' value='".$Lieu."'><input type='hidden' name='rev' value='3'>
                                        <input type='submit' value='Revendiquer' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></td>
                                        <td><div class='i-flex'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Revendiquer compte comme action du jour</span></a></div></td>
                                        <td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Revendiquer un lieu stratégique nécessite que le lieu soit reconnu soit via une reco tactique ou une reco stratégique.<br>Les lieux non stratégique peuvent être revendiqués sans reco préalable.<br>Pour revendiquer une caserne, la garnison doit être éliminée au préalable.</span></a></td></tr>";
                                else
                                    $Atk_Options.="<div class='alert alert-danger'>Les zones nécessaires à la revendication de la caserne ne sont pas sous contrôle de votre faction</div>";
                            }
                        }
                        elseif($Faction !=$Faction_Flag)
                            $Atk_Options.="<div class='alert alert-danger'>Une reconnaissance stratégique ou terrestre est une condition préalable à la revendication</div>";
                    }
                }
                //Position
                $Pos_ori=GetPosGr($Position);
                //Camions ravit
                if($Type_Veh ==1)
                {
                    $Fret_options='';
                    $Divisions='Etat-Major';
                    $Bl_conso="<span class='label label-default' title='Charge possible'>".$Charge."kg</span>";
                    if($Faction ==$Faction_Flag and $Vehicule_Nbr >0)
                    {
                        if(($Placement ==4 or $Placement ==11) and !$Fret)
                        {
                            $Pos_titre='Transit';
                            $Positions='<form action="index.php?view=ground_em_ia_go" method="post"><input type="hidden" name="Unit" value="'.$Unit.'"><select name="pos" class="form-control" style="max-width:200px; display:inline;"><option value="0">Ne rien changer</option><option value="11">En transit (compte comme action du jour)</option></select>
                                        <input class="btn btn-sm btn-warning" type="submit" value="Changer" onclick="this.disabled=true;this.form.submit();"></form>';
                            $txt_help.="<div class='alert alert-info'>Vous pouvez embarquer cette unité sur des navires de transport via la position 'En transit'<br>Si cette unité possède du fret, vous ne pouvez pas réaliser cette action. Déchargez d'abord son fret</div>";
                        }
                        elseif($ValeurStrat >3 and !$Move)
                        {
                            $Pos_titre='Fret';
                            $Mult_Camion=$Charge*$Vehicule_Nbr/1000;
                            $Qty_carbu_camion=floor(1000*$Mult_Camion);
                            $Mult_Camion_txt="<input type='hidden' name='Mult' value='".$Mult_Camion."'>";
                            $depot_info="<h3>Dépôt de ".$Ville."</h3><div style='overflow:auto;'><table class='table'>
                                <thead><tr><th>Essence 87 Octane</th><th>Essence 100 Octane</th><th>Diesel</th><th>Munitions 8mm</th><th>Munitions 13mm</th><th>Munitions 20mm</th><th>Munitions 30mm</th><th>Munitions 40mm</th>
                                <th>Munitions 50mm</th><th>Munitions 60mm</th><th>Munitions 75mm</th><th>Munitions 90mm</th><th>Munitions 105mm</th><th>Munitions 125mm</th><th>Munitions 150mm</th>
                                <th>Charges de Profondeur</th><th>Mines</th><th>Torpilles</th><th>Rockets</th><th>Fusées</th></tr></thead>
                                <tr><td>".$Stock_Essence_87."</td><td>".$Stock_Essence_100."</td><td>".$Stock_Essence_1."</td><td>".$Stock_Munitions_8."</td><td>".$Stock_Munitions_13."</td>
                                <td>".$Stock_Munitions_20."</td><td>".$Stock_Munitions_30."</td><td>".$Stock_Munitions_40."</td><td>".$Stock_Munitions_50."</td><td>".$Stock_Munitions_60."</td>
                                <td>".$Stock_Munitions_75."</td><td>".$Stock_Munitions_90."</td><td>".$Stock_Munitions_105."</td><td>".$Stock_Munitions_125."</td><td>".$Stock_Munitions_150."</td>
                                <td>".$Stock_Bombes_300."</td><td>".$Stock_Bombes_400."</td><td>".$Stock_Bombes_800."</td><td>".$Stock_Bombes_80."</td><td>".$Stock_Bombes_30."</td></tr>
                                </table></div>";
                            if($Stock_Munitions_8 >(2000*$Mult_Camion))
                                $Fret_options.="<option value='8'>".floor(2000*$Mult_Camion)." cartouches de 8mm</option>";
                            else
                                $Fret_options.="<option value='8' disabled>".floor(2000*$Mult_Camion)." cartouches de 8mm</option>";
                            if($Stock_Munitions_13 >$Qty_carbu_camion)
                                $Fret_options.="<option value='13'>".$Qty_carbu_camion." cartouches de 13mm</option>";
                            if($Stock_Munitions_20 >(20000/50*$Mult_Camion))
                                $Fret_options.="<option value='20'>".floor(400*$Mult_Camion)." obus de 20mm</option>";
                            if($Stock_Munitions_30 >(10000/50*$Mult_Camion))
                                $Fret_options.="<option value='30'>".floor(200*$Mult_Camion)." obus de 30mm</option>";
                            if($Stock_Munitions_40 >(5000/50*$Mult_Camion))
                                $Fret_options.="<option value='40'>".floor(100*$Mult_Camion)." obus de 40mm</option>";
                            if($Stock_Munitions_50 >(3000/50*$Mult_Camion))
                                $Fret_options.="<option value='50'>".floor(60*$Mult_Camion)." obus de 50mm</option>";
                            if($Stock_Munitions_60 >(2000/50*$Mult_Camion))
                                $Fret_options.="<option value='60'>".floor(40*$Mult_Camion)." obus de 60mm</option>";
                            if($Stock_Munitions_75 >(1500/50*$Mult_Camion))
                                $Fret_options.="<option value='75'>".floor(30*$Mult_Camion)." obus de 75mm</option>";
                            if($Stock_Munitions_90 >(1000/50*$Mult_Camion))
                                $Fret_options.="<option value='90'>".floor(20*$Mult_Camion)." obus de 90mm</option>";
                            if($Stock_Munitions_105 >(750/50*$Mult_Camion))
                                $Fret_options.="<option value='105'>".floor(15*$Mult_Camion)." obus de 105mm</option>";
                            if($Stock_Munitions_125 >(500/50*$Mult_Camion))
                                $Fret_options.="<option value='125'>".floor(10*$Mult_Camion)." obus de 125mm</option>";
                            if($Stock_Munitions_150 >(4*$Mult_Camion))
                                $Fret_options.="<option value='150'>".floor(4*$Mult_Camion)." obus de 150mm</option>";
                            if($Stock_Bombes_50 >(2000/50*$Mult_Camion))
                                $Fret_options.="<option value='9050'>".floor(40*$Mult_Camion)." bombes de 50kg</option>";
                            if($Stock_Bombes_125 >(1000/50*$Mult_Camion))
                                $Fret_options.="<option value='9125'>".floor(20*$Mult_Camion)." bombes de 125kg</option>";
                            if($Stock_Bombes_250 >(500/50*$Mult_Camion))
                                $Fret_options.="<option value='9250'>".floor(10*$Mult_Camion)." bombes de 250kg</option>";
                            if($Stock_Bombes_500 >(4*$Mult_Camion))
                                $Fret_options.="<option value='9500'>".floor(4*$Mult_Camion)." bombes de 500kg</option>";
                            if($Stock_Bombes_1000 >(2*$Mult_Camion))
                                $Fret_options.="<option value='10000'>".floor(2*$Mult_Camion)." bombes de 1000kg</option>";
                            if($Stock_Bombes_2000 >$Mult_Camion)
                                $Fret_options.="<option value='11000'>".floor($Mult_Camion)." bombes de 2000kg</option>";
                            if($Stock_Bombes_300 >(250/50*$Mult_Camion))
                                $Fret_options.="<option value='300'>".floor(5*$Mult_Camion)." charges de profondeur</option>";
                            if($Stock_Bombes_400 >(250/50*$Mult_Camion))
                                $Fret_options.="<option value='400'>".floor(5*$Mult_Camion)." mines</option>";
                            if($Stock_Bombes_80 >(1000/50*$Mult_Camion))
                                $Fret_options.="<option value='80'>".floor(20*$Mult_Camion)." rockets</option>";
                            if($Stock_Bombes_800 >(2*$Mult_Camion))
                                $Fret_options.="<option value='800'>".floor(2*$Mult_Camion)." torpilles</option>";
                            if($Stock_Bombes_30 >(10000/50*$Mult_Camion))
                                $Fret_options.="<option value='930'>".floor(200*$Mult_Camion)." fusées éclairantes</option>";
                            if($Stock_Essence_87 >$Qty_carbu_camion)
                                $Fret_options.="<option value='1087'>".$Qty_carbu_camion."L Essence 87 Octane</option>";
                            else
                                $Fret_options.="<option value='1087' disabled>".$Qty_carbu_camion."L Essence 87 Octane</option>";
                            if($Stock_Essence_100 >$Qty_carbu_camion)
                                $Fret_options.="<option value='1100'>".$Qty_carbu_camion."L Essence 100 Octane</option>";
                            else
                                $Fret_options.="<option value='1100' disabled>".$Qty_carbu_camion."L Essence 100 Octane</option>";
                            if($Stock_Essence_1 >$Qty_carbu_camion)
                                $Fret_options.="<option value='1001'>".$Qty_carbu_camion."L de Diesel</option>";
                            else
                                $Fret_options.="<option value='1001' disabled>".$Qty_carbu_camion."L de Diesel</option>";
                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'>".$Mult_Camion_txt."<input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><select name='fret' class='form-control' style='width: 150px'><option value='0'>Ne rien charger</option>".$Fret_options."</select><input type='submit' value='Charger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Une quantité minimale est nécessaire dans le dépôt pour pouvoir transporter du fret<br>Charger du fret remplace l'éventuelle cargaison existante qui est perdue.</span></a></td></tr>";
                        }
                        if(!$Fret)
                            $Pos_ori="Vide";
                        elseif($Fret ==1001)
                            $Pos_ori=$Qty_carbu_camion."L Diesel";
                        elseif($Fret ==1087)
                            $Pos_ori=$Qty_carbu_camion."L Essence 87";
                        elseif($Fret ==1100)
                            $Pos_ori=$Qty_carbu_camion."L Essence 100";
                        elseif($Fret ==1)
                            $Pos_ori="Troupes";
                        elseif($Fret ==930)
                            $Pos_ori="Fusées";
                        elseif($Fret ==80)
                            $Pos_ori="Rockets";
                        elseif($Fret ==200)
                            $Pos_ori="Troupes IA";
                        elseif($Fret ==300)
                            $Pos_ori="Charges";
                        elseif($Fret ==400)
                            $Pos_ori="Mines";
                        elseif($Fret ==800)
                            $Pos_ori="Torpilles";
                        elseif($Fret ==1200)
                            $Pos_ori="Obus de 200mm";
                        elseif($Fret ==9050 or $Fret ==9125 or $Fret ==9250 or $Fret ==9500)
                            $Pos_ori="Bombes de ".substr($Fret,1)."kg";
                        elseif($Fret >9999)
                            $Pos_ori="Bombes de ".substr($Fret,0,-1)."kg";
                        else
                            $Pos_ori="Obus de ".$Fret."mm";
                        if($Fret and $Faction ==$Faction_Flag and $ValeurStrat >3 and !$Move)
                            $Atk_Options.="<tr><td>".$Pos_ori."<form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='Dech' value='".$Fret."'><input type='hidden' name='base' value='".$Lieu."'>".$Mult_Camion_txt."<input type='submit' value='Décharger' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                    }
                }
                elseif($Type_Veh !=95)
                {
                    $Pos_titre='Position';
                    if($Position !=12 and $Position !=13)
                    {
                        if(!$Pas_libre)
                        {
                            if(!$GHQ or $Admin or $Nation_IA)
                            {
                                $Positions="<form action='ground_em_ia_go.php' method='post'><input type='hidden' name='Unit' value='".$Unit."'><select name='pos' class='form-control' style='max-width:200px; display:inline;'><option value='0'>Ne rien changer</option>";
                                if(!$Move)
                                    $Positions.="<option value='2'>Retranché (conseillé pour les unités isolées)</option>";
                                if(!$Move and $Detection >10)
                                    $Positions.="<option value='14'>Sentinelle [Consomme l'action du jour]</option>";
                                if(!$Move or (($mobile ==1 or $mobile ==2 or $mobile ==6) and $Type_Veh !=6))
                                {
                                    if(!$Atk)$Positions.="<option value='5'>Appui (conseillé pour l'artillerie à vocation défensive ou la DCA)</option>";
                                    if($Zone !=0 and $Zone !=8 and $Vehicule_Nbr >0)
                                        $Positions.="<option value='3'>Embuscade (conseillé pour l'artillerie AT)</option>";
                                    $Positions.="<option value='1'>Défensive (conseillé pour les unités motorisées ou la DCA)</option>
                                        <option value='10'>Ligne (conseillé pour l'infanterie ou l'artillerie AT)</option>
                                        <option value='4'>Mouvement (conseillé avant tout assaut ou attaque)</option>";
                                    if($Placement ==4 or $Placement ==11)
                                    {
                                        /*if($Type_Veh ==90)
                                            $Positions.="<option value='32'>En attente de transit (compte comme action du jour)</option>";
                                        else*/
                                        $Positions.="<option value='11'>En transit (compte comme action du jour)</option>";
                                        $txt_help.="<div class='alert alert-info'>Vous pouvez embarquer cette unité sur des navires de transport via la position 'En transit'</div>";
                                    }
                                    $Positions.='</select><input class="btn btn-sm btn-warning" type="submit" value="Changer" onclick="this.disabled=true;this.form.submit();"></form>';
                                }
                                else
                                    $Positions.='</select></form>';
                            }
                        }
                        else
                            $Positions='<span class="text-danger">En combat</span>';
                    }
                    else
                        $Positions='<span class="text-danger">En transit</span>';
                    //Atk
                    if(!$Move and $Vehicule_Nbr >0 and $Credits >=1 and (!$GHQ or $Admin or $Nation_IA))
                    {
                        if($Carbu){
                            $Conso_tot=$Conso*$Vehicule_Nbr;
                            if($Conso_tot <$Conso_move)$Conso_tot=$Conso_move;
                        }else{
                            $Conso_tot=0;
                        }
                        if($Credits >=4 and !$Canada) //Actions offensives
                        {
                            $con=dbconnecti();
                            $Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction='$Faction' AND r.Vehicule_Nbr >0 AND r.Placement='$Placement'"),0);
                            if($Embout <=GetEmboutMax($ValeurStrat,$Placement))
                            {
                                if($Arme_AT){
                                    $Arme_Cal=round(GetData("Armes","ID",$Arme_AT,"Calibre"));
                                    $Var_Stock='Stock_'.$Arme_Cal.'_max';
                                    $Stock_AT=$$Var_Stock;
                                    $Conso_mun_at=floor($AT_muns/10)*$Vehicule_Nbr;
                                    if($OfficierID and $Sec_Log){
                                        $Conso_mun_at=floor($Conso_mun_at((100-($Avancement/500))*0,01));
                                        if($Conso_mun_at <0)$Conso_mun_at=0;
                                    }
                                }
                                if($Arme_Art){
                                    $Arme_Cal=round(GetData("Armes","ID",$Arme_Art,"Calibre"));
                                    $Var_Stock='Stock_'.$Arme_Cal.'_max';
                                    $Stock_Art=$$Var_Stock;
                                    if($Type_Veh ==6 or $Type_Veh ==8)
                                        $Conso_mun_art=$Art_muns*$Vehicule_Nbr;
                                    else
                                        $Conso_mun_art=floor($Art_muns/10)*$Vehicule_Nbr;
                                    if($OfficierID and $Sec_Log){
                                        $Conso_mun_art=floor($Conso_mun_art((100-($Avancement/500))*0,01));
                                        if($Conso_mun_art <0)$Conso_mun_art=0;
                                    }
                                }
                                if($OfficierEMID)
                                    $CT_Spec=2+floor($Reput_Renf/10)-$Fiabilite;
                                else
                                    $CT_Spec=4+floor($Reput_Renf/10)-$Sec_EM-$Fiabilite;
                                if($CT_Spec <1)$CT_Spec=1;
                                $CT_Spec_Blitz=$CT_Spec-2;
                                if($CT_Spec_Blitz <1)$CT_Spec_Blitz=1;
                                if(!$Pas_libre and $Position !=2 and $Position !=3 and $Position !=10 and $Position !=14 and
                                    (($Arme_Art and ($Stock_Art >=$Conso_mun_art or $Ravit)) or ($Arme_AT and ($Stock_AT >=$Conso_mun_at or $Ravit)))
                                ) //Arti
                                {
                                    if($Credits >=$CT_Spec)
                                    {
                                        if($Matos ==8)$Range /=2;
                                        if($Flag ==$country)$Range +=500;
                                        if($Categorie ==8){
                                            //Range
                                            if($Position ==2 or $Position ==3 or $Position ==9 or $Position ==10 or $Position ==14 or $Position ==26)$Range /=2;
                                            if($Skill ==73)
                                                $Range*=1.25;
                                            elseif($Skill ==72)
                                                $Range*=1.2;
                                            elseif($Skill ==47)
                                                $Range*=1.15;
                                            elseif($Skill ==15)
                                                $Range*=1.1;
                                            if($Meteo <-69)$Range /=2;
                                            if($Zone ==6)$Range+=($Experience*9);
                                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='conso_mun' value='".$Conso_mun_art."'><input type='hidden' name='pos' value='34'>
                                                            <input type='submit' value='Bombardement' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                                            <td><div class='i-flex'><img src='images/CT".$CT_Spec.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                                            <td>".$Conso_mun_art."x ".$Arme_Cal."mm</td>
                                                            <td>".$Range."m</td>
                                                            <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tir à distance sur les unités détectées situées sur le même lieu. L'unité passera en mode combat pour une durée de 24h.</span></a></td></tr>";
                                            if($Recce)
                                                $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='conso_mun' value='".$Conso_mun_art."'><input type='hidden' name='pos' value='35'>
                                                            <input type='submit' value='Detruire' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                                            <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                                            <td>".$Conso_mun_art."x ".$Arme_Cal."mm</td>
                                                            <td>".$Range."m</td>
                                                            <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tir à distance sur la garnison ou les fortifications du lieu.</span></a></td></tr>";
                                        }
                                        elseif($Categorie ==15 and $Arme_Cal >74){
                                            if($Meteo <-69)$Range /=2;
                                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='conso_mun' value='".$Conso_mun_at."'><input type='hidden' name='pos' value='34'>
                                                            <input type='submit' value='Tirer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                                            <td><div class='i-flex'><img src='images/CT".$CT_Spec.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td>
                                                            <td>".$Conso_mun_at."x ".$Arme_Cal."mm</td>
                                                            <td>".$Range."m</td>
                                                            <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tir direct à distance sur les unités détectées situées sur le même lieu. L'unité passera en mode combat pour une durée de 24h.</span></a></td></tr>";
                                        }
                                    }
                                }
                                if(!$Pas_libre and ($Categorie ==2 or $Categorie ==3 or $Categorie ==7 or $Type_Veh ==11) and ($Position ==4 or $Position ==0) and $Arme_AT and $Credits >=$CT_Spec_Blitz and $HasHostiles)
                                {
                                    if($Stock_AT >=$Conso_mun_at or $Ravit)
                                    {
                                        //$Bl_conso="<span class='label label-".$Colorc1."' title='Consommation attaque ou reco'>".$Conso_tot."L ".$Octane1."</span>";
                                        if($Stock_carbu >=$Conso_tot or $Ravit)
                                        {
                                            $units_eni_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (1,3,5,10) AND r.Placement='$Placement'"),0);
                                            $units_allies_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction='$Faction' AND r.Vehicule_Nbr >0 AND r.Position=5 AND r.Placement='$Placement'"),0);
                                            $bonus_init=$units_allies_zone-$units_eni_zone;
                                            //Range
                                            $Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type_Veh,0,$Sol_meuble);
                                            if($Flag ==$country)$Vitesse+=10;
                                            if($Matos ==10)$Vitesse*=1.1;
                                            elseif($Matos ==14)$Vitesse*=1.5;
                                            elseif($Matos ==30)$Vitesse/=1.25;
                                            $Range=($Vitesse*100)+($Experience*2);
                                            if($Position ==2 or $Position ==3 or $Position ==9 or $Position ==10 or $Position ==14)$Range/=2;
                                            if($mobile ==7)$Range*=2;
                                            if($mobile ==3 and !$Visible and ($Zone ==2 or $Zone ==3 or $Zone ==4 or $Zone ==5 or $Zone ==7 or $Zone ==10)) //Bonus infanterie en terrain difficile
                                            {
                                                $Range_bonus=$Range*2;
                                                $Range_txt="<a href='#' class='popup'><i class='text-danger'>".round($Range_bonus)."m</i><span>En cas d'attaque surprise</span></a>";
                                            }
                                            elseif(($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7) and ($Zone ==0 or $Zone ==8)) //Bonus véhicules en terrain plat
                                            {
                                                $Range_bonus=$Range*2;
                                                $Range_txt="<a href='#' class='popup'><i class='text-danger'>".round($Range_bonus)."m</i><span>En cas d'attaque surprise si l'ennemi ne possède pas d'unités en ligne</span></a>";
                                            }
                                            if($Skill ==19 or $Skill ==62 or $Skill ==102 or $Skill ==103)
                                            {
                                                if($Skill ==19){
                                                    $Range*=1.1;
                                                    $Pass*=1.15;
                                                }
                                                elseif($Skill ==62){
                                                    $Range*=1.15;
                                                    $Pass*=1.3;
                                                }
                                                elseif($Skill ==102){
                                                    $Range*=1.20;
                                                    $Pass*=1.45;
                                                }
                                                elseif($Skill ==103){
                                                    $Range*=1.25;
                                                    $Pass*=1.6;
                                                }
                                            }
                                            $Range=round($Range);
                                            //Init
                                            $Init=$Experience+(($Radio*5)+($Tourelle*5))+$bonus_init;
                                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='conso_mun' value='".$Conso_mun_at."'><input type='hidden' name='pos' value='36'>
                                            <input type='submit' value='Attaque' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'>											
                                            </form></td><td><div class='i-flex'><img src='images/CT".$CT_Spec_Blitz.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour' title='Compte comme action du jour'></div><span>La reconnaissance compte comme action du jour</span></a></div></td>
                                            <td><img src='images/oil_icon.png' title='".$Conso_tot."L'> + ".$Conso_mun_at."x ".$Arme_Cal."mm</td>
                                            <td><a href='#' class='popup'>".$Range."m<span>Portée de Tir - Allonge de Raid</span></a><br>".$Range_txt."</td>
                                            <td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Attaque des unités détectées situées sur la même zone. Compte comme action du jour. L'unité passera en mode combat pour une durée de 24h.<br>Une percée réussie infligeant des dégâts à l'unité attaquée forcera cette dernière à passer en mode combat pour 24h.</span></a><a href='#' class='popup'>".$Init."<span>Modificateur d'initiative et de tactique<br>Ce modificateur est augmenté par la présence d'unités alliées en appui sur la même zone que votre unité, et diminué par la présence d'unités ennemies en ligne, en appui, en embuscade ou en position défensive sur la même zone que votre unité</a></td></tr>";
                                        }
                                        else
                                            $Atk_Options.="<div class='alert alert-danger'>Une attaque nécessite ".$Conso_tot."L de carburant dans le dépôt</div>";
                                    }
                                    else
                                        $Atk_Options.="<div class='alert alert-danger'>Une attaque nécessite ".$Conso_mun_at." munitions dans le dépôt, ou un ravitaillement aérien ou naval</div>";
                                }
                                elseif(!$Pas_libre and $Categorie ==5 and $Vehicule_Nbr >=10 and ($Position ==4 or $Position ==0) and $Credits >=$CT_Spec and $HasHostiles)
                                {
                                    //Range
                                    $Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type_Veh,0,$Sol_meuble);
                                    if($Flag ==$country)$Vitesse+=10;
                                    if($Matos ==10)$Vitesse*=1.1;
                                    elseif($Matos ==14)$Vitesse*=1.5;
                                    elseif($Matos ==30)$Vitesse/=1.25;
                                    $Range=($Vitesse*100)+($Experience*2);
                                    if($Position ==2 or $Position ==3 or $Position ==9 or $Position ==10 or $Position ==14)$Range/=2;
                                    if($mobile ==7)$Range*=2;
                                    if($mobile ==3 and !$Visible and ($Zone ==2 or $Zone ==3 or $Zone ==4 or $Zone ==5 or $Zone ==7 or $Zone ==10)) //Bonus infanterie en terrain difficile
                                    {
                                        $Range_bonus=$Range*2;
                                        $Range_txt="<a href='#' class='popup'><i class='text-danger'>".round($Range_bonus)."m</i><span>En cas d'attaque surprise</span></a>";
                                    }
                                    elseif(($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7) and ($Zone ==0 or $Zone ==8)) //Bonus véhicules en terrain plat
                                    {
                                        $Range_bonus=$Range*2;
                                        $Range_txt="<a href='#' class='popup'><i class='text-danger'>".round($Range_bonus)."m</i><span>En cas d'attaque surprise si l'ennemi ne possède pas d'unités en ligne</span></a>";
                                    }
                                    $Range=round($Range);
                                    $inf_eni_routed_zone=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Vehicule_Nbr >0 AND r.Position IN (6,7,8,9) AND r.Placement='$Placement' AND c.Categorie=5"),0);
                                    if($inf_eni_routed_zone)
                                        $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='conso_mun' value='0'><input type='hidden' name='pos' value='39'>
                                        <input type='submit' value='Dispersion' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'>
                                        </form></td><td><div class='i-flex'><img src='images/CT".$CT_Spec.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td><td>".$Conso_tot."L</td><td><a href='#' class='popup'>".$Range."m<span>Portée de Tir - Allonge de Raid</span></a><br>".$Range_txt."</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Tentative de dispersion des unités d'infanterie ennemies désorganisées situées sur la même zone.</span></a></td></tr>";
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='conso_mun' value='0'><input type='hidden' name='pos' value='36'>
                                    <input type='submit' value='Attaque' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'>
                                    </form></td><td><div class='i-flex'><img src='images/CT".$CT_Spec.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td><td>".$Conso_tot."L</td><td><a href='#' class='popup'>".$Range."m<span>Portée de Tir - Allonge de Raid</span></a><br>".$Range_txt."</td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Assaut d'infanterie contre les unités ennemies situées sur la même zone.</span></a></td></tr>";
                                }
                                elseif($Pas_libre)
                                    $Atk_Options.='<div class="alert alert-danger">Attaque impossible actuellement</div>';
                                elseif($Position !=4 and $Categorie !=6 and $Categorie !=8 and $Categorie !=9 and $Categorie !=15)
                                    $Atk_Options.='<div class="alert alert-danger">Pour pouvoir attaquer, l\'unité doit être en mouvement</div>';
                                if($Faction_Flag !=$Faction and ($Categorie ==2 or $Categorie ==3 or $Categorie ==5 or $Categorie ==7) and ($Position ==4 or $Position ==0) and ($Placement ==1 or $Placement ==0))
                                {
                                    if($Recce or !$ValeurStrat)// and $Credits >=$CT_Spec_Blitz)
                                    {
                                        if($Placement ==0 and $Garnison)
                                            $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='38'>
                                            <input type='submit' value='Assaut' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                            <td><div class='i-flex'><img src='images/CT".$CT_Spec_Blitz.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td><td>0</td><td>".$Range."m</td><td>N/A</td></tr>";
                                        elseif($Placement ==1)
                                        {
                                            $Esc_Oqp=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit as u,Pays as p WHERE u.Base='$Lieu' AND u.Pays=p.ID AND p.Faction<>'$Faction' AND Etat=1 AND Garnison >0"),0);
                                            if($Esc_Oqp)
                                                $Atk_Options.="<tr><td><form action='index.php?view=ground_em_ia_go' method='post'><input type='hidden' name='Unit' value='".$Unit."'><input type='hidden' name='base' value='".$Lieu."'><input type='hidden' name='pos' value='48'>
                                                <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Assaut des troupes défendant l'aérodrome</span></a><input type='submit' value='Assaut' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                                <td><div class='i-flex'><img src='images/CT".$CT_Spec_Blitz.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></div></td><td>0</td><td>".$Range."m</td><td>N/A</td></tr>";
                                        }
                                    }
                                    else
                                        $Atk_Options.="<div class='alert alert-danger'>Une reconnaissance stratégique ou terrestre est une condition préalable à l'assaut de garnison ou d'aérodrome</div>";
                                }
                                elseif(!$Atk_Options and ($Categorie ==6 or $Categorie ==9 or $Categorie ==15))
                                    $Atk_Options='<div class="alert alert-warning">Cette unité est exclusivement défensive</div>';
                            }
                            else
                                $txt_help.="<div class='alert alert-danger'><strong>Embouteillage!</strong> Trop d'unités occupent cette zone!<br>Toute attaque ou bombardement est impossible tant que l'embouteillage persiste!</div>";
                            mysqli_close($con);
                        }
                        //Reco
                        if($OfficierEMID)
                        {
                            if($Trait ==13)
                                $Cr_reco=1-$Fiabilite;
                            else
                                $Cr_reco=2-$Fiabilite;
                        }
                        else
                            $Cr_reco=4-$Sec_EM-$Fiabilite;
                        if($Cr_reco <1)$Cr_reco=1;
                        if($Detection >10 and $Credits >=$Cr_reco and !$Canada)
                        {
                            if($Embout <=GetEmboutMax($ValeurStrat,$Placement))
                            {
                                if($Carbu and !$Bl_conso)
                                    $Bl_conso="<span class='label label-".$Colorc1."' title='Consommation attaque ou reco'>".$Conso_tot."L ".$Octane1."</span>";
                                if($Stock_carbu >=$Conso_tot or $Ravit)
                                {
                                    $Atk_Options.="<tr><td><form action='index.php?view=ground_reco1' method='post'>
                                    <input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Unit."'><input type='hidden' name='Veh' value='".$Vehicule."'><input type='hidden' name='Cible' value='".$Lieu."'><input type='hidden' name='Conso' value='0'>
                                    <input type='submit' value='Reco' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                    <td><div class='i-flex'><a href='#' class='popup'><div class='action-jour'></div><span>La reconnaissance compte comme action du jour</span></a></div></td>
                                    <td>".$Conso_tot."L</td>
                                    <td>".$Range."m</td>
                                    <td>N/A</td></tr>";
                                }
                                else
                                    $Atk_Options.="<p class='lead'>Une reconnaissance nécessite ".$Conso_tot."L de carburant</p>";
                            }
                            else
                                $Atk_Options.="<div class='alert alert-danger'><strong>Embouteillage!</strong> Trop d'unités occupent cette zone!<br>Toute reconnaissance est impossible tant que l'embouteillage persiste!</div>";
                        }
                        if($Credits >=4 and $Type_Veh ==98 and $Vehicule_Nbr >0)
                        {
                            if($Position !=11 and $Position !=6 and $Position !=8 and $Position !=9 and $Position !=14)
                            {
                                $Atk_Options.="<tr><td><form action='index.php?view=ground_genie' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                                <input type='submit' value='Génie' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form></td>
                                <td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td>0</td><td>N/A</td><td>N/A</td></tr>";
                            }
                        }
                    }
                    //END MAJ
                }
                else{
                    $Positions='Déjà effectué';
                    $Pos_titre='Action';
                }
            }
            //Compétence Tactique
            if($Skill)
            {
                if($Vehicule !=424 and $Type_Veh !=1)
                {
                    $con1=dbconnecti(1);
                    $resultsk=mysqli_query($con1,"SELECT Nom,Rang,Infos FROM Skills_r WHERE ID='$Skill'");
                    if($resultsk)
                    {
                        while($datask=mysqli_fetch_array($resultsk,MYSQLI_ASSOC))
                        {
                            $Skills_Rang=$datask['Rang'];
                            $Skills_Infos="<b>".$datask['Nom']."</b><br>".$datask['Infos'];
                        }
                        mysqli_free_result($resultsk);
                    }
                    if($Skills_Rang <4 and $Experience >199)
                        $next_skill=mysqli_result(mysqli_query($con1,"SELECT ID FROM Skills_r WHERE Prereq1='$Skill'"),0);
                    elseif($Skills_Rang <3 and $Experience >149)
                        $next_skill=mysqli_result(mysqli_query($con1,"SELECT ID FROM Skills_r WHERE Prereq1='$Skill'"),0);
                    elseif($Skills_Rang <2 and $Experience >100)
                        $next_skill=mysqli_result(mysqli_query($con1,"SELECT ID FROM Skills_r WHERE Prereq1='$Skill'"),0);
                    elseif($Skills_Rang <1 and $Experience >49)
                        $next_skill=mysqli_result(mysqli_query($con1,"SELECT ID FROM Skills_r WHERE Prereq1='$Skill'"),0);
                    mysqli_close($con1);
                    if($next_skill){
                        $con=dbconnecti();
                        $update_skill=mysqli_query($con,"UPDATE Regiment_IA SET Skill='$next_skill' WHERE ID='$Unit'");
                        mysqli_close($con);
                        $Skill=$next_skill;
                    }
                }
                $Skill_txt="<a href='#' class='popup'><img src='images/skills/skillo".$Skill.".png' style='max-width:15%;'><span>".$Skills_Infos."</span></a>";
            }
            elseif($Vehicule !=424 and $Type_Veh !=1 and $Experience >49)
            {
                if($Type_Veh ==37) //Sub
                {
                    $Skills_1=array(25,32,35,37,43);
                    $Skills_2=array(129,135,144,150,168);
                    $Skills_3=array(130,136,145,151,169);
                    $Skills_4=array(131,137,146,152,170);
                }
                elseif($Type_Veh ==21) //PA
                {
                    $Skills_1=array(25,30,36);
                    $Skills_2=array(129,123,147);
                    $Skills_3=array(130,124,148);
                    $Skills_4=array(131,125,149);
                }
                elseif($Type_Veh ==20 or $Type_Veh ==19 or $Type_Veh ==18) //Cuirassé & Croiseur
                {
                    $Skills_1=array(15,22,25,30,31,33,34,35,36,38,41);
                    $Skills_2=array(47,65,129,123,132,138,141,144,147,153,162);
                    $Skills_3=array(72,108,130,124,133,139,142,145,148,154,163);
                    $Skills_4=array(73,109,131,125,134,140,143,146,149,155,164);
                }
                elseif($Type_Veh ==15 or $Type_Veh ==16 or $Type_Veh ==17) //Escorteurs
                {
                    $Skills_1=array(25,30,35,36,37,39,40,42);
                    $Skills_2=array(129,123,144,147,150,156,159,165);
                    $Skills_3=array(130,124,145,148,151,157,160,166);
                    $Skills_4=array(131,125,146,149,152,158,161,167);
                }
                elseif($Type_Veh ==14) //Pt navires
                {
                    $Skills_1=array(25,35,36);
                    $Skills_2=array(129,144,147);
                    $Skills_3=array(130,145,148);
                    $Skills_4=array(131,146,149);
                }
                elseif($Categorie ==6) //MG
                {
                    $Skills_1=array(3,4,6,7,9,11,13,14,23,25,29);
                    $Skills_2=array(48,49,51,52,54,56,58,59,114,129);
                    $Skills_3=array(74,76,80,82,86,90,94,96,115,130);
                    $Skills_4=array(40,75,77,81,83,87,91,95,97,116,131);
                }
                elseif($Type_Veh ==4) //Canon AT
                {
                    $Skills_1=array(3,6,9,11,12,14,25);
                    $Skills_2=array(48,51,54,56,57,59,129);
                    $Skills_3=array(74,80,86,90,92,96,130);
                    $Skills_4=array(40,75,81,87,91,93,97,131);
                }
                elseif($Type_Veh ==6)
                {
                    $Skills_1=array(6,8,9,12,15,22,25,28);
                    $Skills_2=array(47,51,53,54,57,65,67,129);
                    $Skills_3=array(72,80,84,86,92,108,112,130);
                    $Skills_4=array(40,73,81,85,87,93,109,113,131);
                }
                elseif($Type_Veh ==8)
                {
                    $Skills_1=array(6,8,9,15,20,22,25,28);
                    $Skills_2=array(47,51,53,54,57,63,65,67,129);
                    $Skills_3=array(72,80,84,86,104,108,112,130);
                    $Skills_4=array(40,73,81,85,87,105,109,113,131);
                }
                elseif($Type_Veh ==9)
                {
                    $Skills_1=array(1,2,3,5,6,9,10,16,18,19,21,24,25);
                    $Skills_2=array(45,46,48,50,51,54,55,60,61,62,64,66,129);
                    $Skills_3=array(68,70,74,78,80,86,88,98,100,102,106,110,130);
                    $Skills_4=array(40,69,71,75,79,81,87,89,99,101,103,107,111,131);
                }
                elseif($Type_Veh ==12)
                {
                    $Skills_1=array(6,9,12,14,25,30);
                    $Skills_2=array(51,54,57,59,123,129);
                    $Skills_3=array(80,86,92,96,124,130);
                    $Skills_4=array(40,81,87,93,97,125,131);
                }
                elseif($Type_Veh ==7 or $Type_Veh ==10 or $Type_Veh ==91)
                {
                    $Skills_1=array(1,2,5,6,9,10,16,18,19,21,24,25);
                    $Skills_2=array(45,46,50,51,54,55,60,61,62,64,66,129);
                    $Skills_3=array(68,70,78,80,86,88,98,100,102,106,110,130);
                    $Skills_4=array(40,69,71,79,81,87,89,99,101,103,107,111,131);
                }
                elseif($Type_Veh ==11)
                {
                    $Skills_1=array(1,2,5,6,9,10,16,18,19,21,25,30);
                    $Skills_2=array(45,46,50,51,54,55,60,61,62,64,123,129);
                    $Skills_3=array(68,70,78,80,86,88,98,100,102,106,124,130);
                    $Skills_4=array(40,69,71,79,81,87,89,99,101,103,107,125,131);
                }
                elseif($Type_Veh ==2 or $Type_Veh ==3 or $Type_Veh ==5 or $Type_Veh ==93)
                {
                    $Skills_1=array(1,2,5,6,9,10,16,18,19,21,25);
                    $Skills_2=array(45,46,50,51,54,55,60,61,62,64,129);
                    $Skills_3=array(68,70,78,80,86,88,98,100,102,106,130);
                    $Skills_4=array(40,69,71,79,81,87,89,99,101,103,107,131);
                }
                else //Inf
                {
                    $Skills_1=array(3,4,6,7,9,11,13,14,17,23,25,26,29);
                    $Skills_2=array(48,49,51,52,54,56,58,59,114,117,120,126,129);
                    $Skills_3=array(74,76,80,82,86,90,94,96,115,118,121,127,130);
                    $Skills_4=array(40,75,77,81,83,87,91,95,97,116,119,122,128,131);
                }
                if($Experience >199)
                    $Skill_p=$Skills_4[mt_rand(0,count($Skills_4)-1)];
                elseif($Experience >149)
                    $Skill_p=$Skills_3[mt_rand(0,count($Skills_3)-1)];
                elseif($Experience >99)
                    $Skill_p=$Skills_2[mt_rand(0,count($Skills_2)-1)];
                elseif($Experience >49)
                    $Skill_p=$Skills_1[mt_rand(0,count($Skills_1)-1)];
                else
                    $Skill_p=0;
                if($Skill_p >0)
                {
                    $Skill=$Skill_p;
                    $con=dbconnecti();
                    $update_skill=mysqli_query($con,"UPDATE Regiment_IA SET Skill='$Skill_p' WHERE ID='$Unit'");
                    mysqli_close($con);
                    $Skill_txt="<a href='#' class='popup'><img src='images/skills/skillo".$Skill_p.".png' style='max-width:15%;'><span>".GetData("Skills_r","ID",$Skill_p,"Infos")."</span></a>";
                }
            }
            elseif($Vehicule !=424 and $Type_Veh !=1)
                $Upgrade_txt="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Cette unité est éprouvée par les combats. Elle peut être améliorée sur une usine produisant du matériel plus performant.</div>";
            //Equipement
            if($Type_Veh ==93 and $Matos ==28){
                $con=dbconnecti();
                $update_skill=mysqli_query($con,"UPDATE Regiment_IA SET Matos=11 WHERE ID='$Unit'");
                mysqli_close($con);
            }
            $next_skill=11;
            if($GHQ) //Menu GHQ
            {
                if($Vehicle !=424 and $Type_Veh !=95)
                {
                    $ghq_txt="Front actuel <b>".GetFront($Front)."</b><div class='row'>";
                    if($Credits >0 and $Industrie and $Placement ==6 and !$Move and $Faction ==$Faction_Flag)
                    {
                        if(!$Faction_Usine)$Faction_Usine=GetData("Pays","ID",$Flag_Usine,"Faction");
                        if($Faction ==$Faction_Usine)
                        {
                            $ghq_txt.="<div class='col-md-4'><form action='index.php?view=ground_em_ia_create' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                            <input type='hidden' name='Cat' value='".$Categorie."'><input type='hidden' name='Mode' value='1'><input type='hidden' name='Lieu' value='".$Lieu."'>
                            <input type='submit' value='Améliorer' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form></div>";
                        }
                    }
                    if($NoEM)
                    {
                        $Mode_res_GHQ=12;
                        $Mode_res_GHQ_txt='Libérer EM';
                    }
                    else
                    {
                        $Mode_res_GHQ=11;
                        $Mode_res_GHQ_txt='Réservé GHQ';
                    }
                    $ghq_txt.="<div class='col-md-4'><form action='ghq_reserve.php' method='post'><input type='hidden' name='mode' value='".$Mode_res_GHQ."'><input type='hidden' name='Reg' value='".$Unit."'>
                    <a href='#' class='popup'><div class='help_icon'></div><span>Réserver cette unité pour le GHQ</span></a><input type='submit' value='".$Mode_res_GHQ_txt."' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form></div>";
                    if(in_array($Lieu,$Transit_cities) and $Categorie !=4 and !$Move)
                        $ghq_txt.="<div class='col-md-4'><form action='index.php?view=ghq_change_front' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                        <input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='Transit' value='".$Lieu."'>
                        <input type='submit' value='Changer de front' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form></div>";
                    $ghq_txt.='</div>';
                }
            }
            elseif(!$Move) //Demander changement de front
            {
                if(in_array($Lieu,$Transit_cities) and $Categorie !=4)
                    $ghq_txt.="<br><form action='index.php?view=ground_change_front' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                    <input type='hidden' name='Front' value='".$Front."'><input type='hidden' name='Transit' value='".$Lieu."'>
                    <input type='submit' value='Demander le changement de front' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form> <span title='Une demande sera envoyée au planificateur stratégique. Le changement de front retirera automatiquement la Cie de sa division actuelle'><div class='i-flex help_icon'></div></span></a>";
            }
            $titre='Commandement des unités IA';
            $mes.=$menu_cat_list;
            //Hangar & Garage
            if($mobile !=4 and $mobile !=5 and $Veh_Type !=1 and !$Move)
            {
                if($Arme_AT or $Arme_Art or $Categorie ==15)
                {
                    $title_conso_atk='Consommation attaque';
                    if($Categorie ==15 and $Arme_AA)
                    {
                        //$Stock_AA;
                        $Arme_Cal=round(GetData("Armes","ID",$Arme_AA,"Calibre"));
                        $Var_Stock_AA='Stock_'.$Arme_Cal.'_max';
                        $Stock_Arme=$$Var_Stock_AA;
                        $Conso_Arme=$AA_muns*$Vehicule_Nbr;
                        $title_conso_atk='Consommation DCA';
                    }
                    elseif($Arme_AT)
                    {
                        $Stock_Arme=$Stock_AT;
                        $Conso_Arme=$Conso_mun_at;
                    }
                    elseif($Arme_Art)
                    {
                        $Stock_Arme=$Stock_Art;
                        $Conso_Arme=$Conso_mun_art;
                    }
                    if(!$Stock_Arme and !$Ravit and ($Move or !$Credits))
                        $depot_stock_mun='Mise à jour demain';
                    elseif(!$Stock_Arme and !$Ravit)
                        $depot_stock_mun='<span class="text-danger">Vide!</span>';
                    else
                        $depot_stock_mun=$Stock_Arme;
                }
                if($Credits >0 and $ValeurStrat >=2 and $Industrie and $Placement ==6 and $Faction ==$Faction_Flag)
                {
                    if(!$Faction_Usine)
                        $Faction_Usine=GetData("Pays","ID",$Flag_Usine,"Faction");
                    if($Faction ==$Faction_Usine)
                    {
                        if($Experience <100 or $GHQ)
                            $Ravit_Options.="<div class='col-md-6'><form action='index.php?view=ground_em_ia_create' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                            <input type='hidden' name='Cat' value='".$Categorie."'><input type='hidden' name='Mode' value='1'><input type='hidden' name='Lieu' value='".$Lieu."'>
                            <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Changer le matériel de l'unité.<br>L'expérience sera ramenée à 50 et une nouvelle compétence tactique de niveau 1 sera attribuée.</span></a>
                            <input type='submit' value='Hangar' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></div>";
                        $Ravit_Options.="<div class='col-md-6'>
                        <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Doter l'unité d'un équipement amélioré.<br>Cet équipement peut être changé plusieurs fois sur toute usine.</span></a>
                        <a href='#' data-toggle='modal' data-target='#modal-ravit'><span class='btn btn-sm btn-danger'>Garage</span></a></div></div>";
                        /*$Ravit_Options.="<div class='col-md-6'><form action='index.php?view=ground_em_ia_matos' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                        <a href='#' class='popup'><div class='i-flex help_icon'></div><span>Doter l'unité d'un équipement amélioré.<br>Cet équipement peut être changé plusieurs fois sur toute usine.</span></a>
                        <input type='submit' value='Garage' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></div></div>";*/
                    }
                }
            }
            elseif($mobile ==5 and !$Move)
            {
                if($Credits >0 and $ValeurStrat >=2 and $Port_Ori and $Port >0 and $Faction ==$Faction_Flag)
                {
                    if(!$Faction_Port)
                        $Faction_Port=GetData("Pays","ID",$Flag_Port,"Faction");
                    if($Faction ==$Faction_Port)
                    {
                        if(($ValeurStrat >3 or $Port_ok) and !$Enis_Port_combi and $Vehicule_Nbr >0 and ($Type_Veh ==20 or $Type_Veh ==21 or $Type_Veh ==15 or $Type_Veh ==16 or $Type_Veh ==17 or $Type_Veh ==18 or $Type_Veh ==19 or $Type_Veh ==37))
                            $Renforts_txt.='<tr><td>
                                                    <form action="index.php?view=ground_em_ia_go" method="post">
                                                        <input type="hidden" name="renf" value="5">
                                                        <input type="hidden" name="Unit" value="'.$Unit.'">
                                                        <input type="hidden" name="Max" value="'.$Max_Veh.'">
                                                        <input class="btn btn-sm btn-warning" type="submit" value="Ravitailler">
                                                    </form>
                                                </td>
                                                <td><div class="i-flex"><img src="images/CT4.png" title="Credits Temps nécessaires pour exécuter cette action"><a href="#" class="popup"><div class="action-jour"></div><span>Compte comme action du jour</span></a></div></td>
                                                <td><a href="#" class="popup"><div class="i-flex help_icon"></div><span>Permet de récupérer le maximum de jours de mer</span></a></td>
                                           </tr>';
                        if($Port_ok and !$Enis_Port_combi){
                            if($Experience <100)
                                $Renforts_txt.="<tr><td><form action='index.php?view=ground_em_ia_create' method='post'><input type='hidden' name='Reg' value='".$Unit."'>
                                <input type='hidden' name='Cat' value='".$Categorie."'><input type='hidden' name='Mode' value='1'><input type='hidden' name='Lieu' value='".$Lieu."'>
                                <input type='submit' value='Hangar' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Changer le matériel de l'unité.<br>L'expérience sera ramenée à 50 et une nouvelle compétence tactique de niveau 1 sera attribuée.</span></a></td></tr>";
                            $Renforts_txt.="<tr><td><a href='#' data-toggle='modal' data-target='#modal-ravit'><span class='btn btn-sm btn-danger'>Garage</span></a></td><td><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a></td><td><a href='#' class='popup'><div class='i-flex help_icon'></div><span>Doter l'unité d'un équipement amélioré.<br>Cet équipement peut être changé plusieurs fois sur toute usine.</span></a></td></tr>";
                        }
                    }
                }
            }
            if($Ravit ==1)$Conso_txt.="<br><a href='#' class='popup'><img src='images/map/air_ravit.png'><span>Ravitaillée par air</span></a>";
            //Begin Help Txt
            if($Zone ==0 or $Zone ==8)
            {
                if($mobile ==5)
                    $Zone_help_txt="Portée d'attaque maximale 2000m";
                else
                {
                    $Zone_help_txt="Portée d'attaque maximale 4000m";
                    if($Categorie ==2 or $Categorie ==3 or $mobile ==7)
                        $Zone_help_txt.="<br>Terrain propice à l'attaque surprise pour cette unité";
                }
            }
            elseif($Zone ==2 or $Zone ==3 or $Zone ==4 or $Zone ==5 or $Zone ==7 or $Zone ==10)
            {
                if($Zone ==4)
                    $Zone_help_txt="Portée d'attaque maximale 1000m<br>";
                else
                    $Zone_help_txt="Portée d'attaque maximale 500m<br>";
                if($mobile ==3)
                    $Zone_help_txt.="Terrain propice à l'attaque surprise pour cette unité<br>Terrain propice à l'embuscade";
                elseif($mobile ==5)
                    $Zone_help_txt="Portée d'attaque maximale 2km";
                else
                    $Zone_help_txt.="Terrain propice à l'embuscade";
            }
            elseif($Zone ==1)
                $Zone_help_txt="Portée d'attaque maximale 1000m";
            elseif($Zone ==6)
                $Zone_help_txt="Portée d'attaque maximale 20km";
            else
                $Zone_help_txt="Ce terrain n'offre aucun avantage à cette unité";
            if($Position ==1)
            {
                $Pos_help_txt="Bonus tactique offensif réduit de moitié<br>Bonus tactique défensif doublé<br>Camoufle automatiquement l'unité suite à un bombardement d'artillerie";
                if($Flak)$Pos_help_txt.="<br>Défend les unités de sa faction sur le même lieu face aux attaques aériennes";
            }
            elseif($Position ==2)
                $Pos_help_txt="Attaque impossible<br>Vitesse nulle<br>Bonus tactique défensif quadruplé<br>Réduction de dégâts lors des bombardements";
            elseif($Position ==3)
            {
                $Pos_help_txt="Portée de bombardement, d'attaque et de raid réduite de moitié<br>Vitesse nulle<br>Bonus tactique défensif doublé";
                if($Arme_AT)$Pos_help_txt.="<br>Riposte contre les reconnaissances et les attaques de véhicules";
            }
            elseif($Position ==4)
                $Pos_help_txt="Vitesse maximale<br>Bonus tactique défensif réduit de moitié";
            elseif($Position ==5)
            {
                $Pos_help_txt="Vitesse nulle<br>Bonus tactique défensif réduit de moitié";
                if($Categorie ==8)$Pos_help_txt.="<br>Contre-batterie sur toutes les zones du lieu";
                if($Flak)$Pos_help_txt.="<br>Défend les unités de sa faction sur le même lieu face aux attaques aériennes";
            }
            elseif($Position ==6)
                $Pos_help_txt="Attaque impossible<br>Bonus tactique défensif réduit de moitié";
            elseif($Position ==7)
                $Pos_help_txt="Bonus tactique offensif réduit de moitié<br>Bonus tactique défensif réduit de moitié";
            elseif($Position ==8)
                $Pos_help_txt="Bonus tactique offensif réduit de moitié<br>Vitesse nulle<br>Bonus tactique défensif réduit de moitié<br>Vulnérable aux bombardements et aux assauts d'infanterie";
            elseif($Position ==9)
                $Pos_help_txt="Bonus tactique offensif réduit de moitié<br>Vitesse nulle<br>Portée de bombardement, d'attaque et de raid réduite de moitié";
            elseif($Position ==10)
            {
                $Pos_help_txt="Attaque impossible<br>Bonus tactique défensif doublé<br>Vitesse nulle<br>Portée de bombardement, d'attaque et de raid réduite de moitié";
                if($Categorie ==5 or $Categorie ==6 or $Categorie ==9)$Pos_help_txt.="<br>Chance de protéger les unités alliées contre les attaques terrestres";
                if($Arme_AT)$Pos_help_txt.="<br>Riposte contre les attaques de véhicules";
            }
            elseif($Position ==11)
                $Pos_help_txt="Attaque impossible<br>Bonus tactique défensif réduit de moitié<br>Vitesse nulle";
            elseif($Position ==14)
                $Pos_help_txt="Chance de détecter toute unité ennemie pénétrant sur la même zone<br>Attaque impossible<br>Portée de bombardement, d'attaque et de raid réduite de moitié<br>Vitesse réduite de moitié";
            elseif($Position ==20)
                $Pos_help_txt="Un navire maximum sera perdu lors d'une attaque";
            elseif($Position ==21)
            {
                $Pos_help_txt="Augmente la protections des navires alliés situés dans la même zone face à un torpillage";
                if($Flak)$Pos_help_txt.="<br>Défend les unités de sa faction sur le même lieu face aux attaques aériennes";
            }
            elseif($Position ==22)
                $Pos_help_txt="Un écran de fumée protégera le navire s'il est attaqué";
            elseif($Position ==23 and $Arme_Art)
                $Pos_help_txt="Contre-batterie sur toutes les zones du lieu";
            elseif($Position ==24)
                $Pos_help_txt="Augmente la protections des navires alliés situés dans la même zone face à un torpillage";
            elseif($Position ==25)
                $Pos_help_txt="Portée de torpillage doublée";
            elseif($Position ==26)
                $Pos_help_txt="Attaque impossible<br>Vitesse nulle<br>Bonus tactique défensif réduit de moitié<br>Portée de bombardement, d'attaque et de raid réduite de moitié";
            else
                $Pos_help_txt="Cette position n'offre aucun avantage à cette unité";
            if($Vehicule <5000 and $Vehicule !=424)
            {
                if(!$Move){
                    if($Placement ==1)
                        $Faction_Place=GetData("Pays","ID",$Flag_Air,"Faction");
                    elseif($Placement ==2)
                        $Faction_Place=GetData("Pays","ID",$Flag_Route,"Faction");
                    elseif($Placement ==3)
                        $Faction_Place=GetData("Pays","ID",$Flag_Gare,"Faction");
                    elseif($Placement ==4)
                        $Faction_Place=GetData("Pays","ID",$Flag_Port,"Faction");
                    elseif($Placement ==5)
                        $Faction_Place=GetData("Pays","ID",$Flag_Pont,"Faction");
                    elseif($Placement ==6)
                        $Faction_Place=GetData("Pays","ID",$Flag_Usine,"Faction");
                    elseif($Placement ==7)
                        $Faction_Place=GetData("Pays","ID",$Flag_Radar,"Faction");
                    elseif($Placement ==11)
                        $Faction_Place=GetData("Pays","ID",$Flag_Plage,"Faction");
                    else
                        $Faction_Place=$Faction_Flag;
                    if($Faction !=$Faction_Place)
                        $Alert_dep.="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <strong>Attrition: </strong>Cette zone est revendiquée par l'ennemi. En restant sur cette zone votre unité subira l'attrition et perdra des troupes chaque jour!</div>";
                }
                if(!$Alert_dep){
                    if(!$Move and $NoeudR >0 and $NoeudF >10 and $Placement !=3)
                        $Alert_dep.="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Déplacement: </strong>Pensez à déplacer l'unité sur la route ou la gare avant tout déplacement, afin de bénéficier du déplacement ferroviaire ou du bonus de noeud routier!</div>";
                    elseif(!$Move and $NoeudR >0 and $Placement !=2 and $Placement !=3)
                        $Alert_dep.="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Déplacement: </strong>Pensez à déplacer l'unité sur la route avant tout déplacement, afin de bénéficier du bonus de noeud routier!</div>";
                    if($Pont_block)
                        $Alert_dep.="<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Déplacement: </strong>La destruction du pont vous empêche de rejoindre l'autre rive!</div>";
                }
            }
            //End Help Txt
            if($Experience >249)
                $Exp_txt="<span class='label label-success'>".$Experience."XP</span>";
            elseif($Experience >49)
                $Exp_txt="<span class='label label-primary'>".$Experience."XP</span>";
            elseif($Experience >1)
                $Exp_txt="<span class='label label-warning'>".$Experience."XP</span>";
            else
                $Exp_txt="<span class='label label-danger'>".$Experience."XP</span>";
            if($Matos)$Skill_txt.="<a href='#' class='popup'><img src='images/skills/skille".$Matos.".png' style='max-width:15%;'><span>".$Reg_matos[$Matos]."</span></a>";
            if(!$Retraite_Nom)$Retraite_Nom=GetData("Lieu","ID",$Retraite,"Nom");
            if($Bataillon)$Bat_Nbr=$Bataillon.'e Bat';
            if($Position==0 or $Position==4)$txt_intro_help="<div class='alert alert-danger'><b>Cette unité est dans une position vulnérable</b>. Veillez à mettre l'unité en position adéquate après tout déplacement.</div>";
            //Output
            //--Divisions--
            if(($Admin or $Ordres_Cdt
                    or ($Ordres_Mer and !$GHQ and $mobile ==5)
                    or ($Ordres_Adjoint and $mobile !=5)
                ) and $Type_Veh !=95 and $Type_Veh !=1 and $mobile !=4 and $Vehicule !=5392 and $Vehicule !=5001 and $Vehicule !=5124){
                //Division & Armées
                if($mobile ==5)
                    $Mar_bool='1';
                else
                    $Mar_bool='0';
                $con=dbconnecti();
                $result9=mysqli_query($con,"SELECT d.ID,d.Nom,d.Base,l.Nom AS BaseNom,a.Nom AS Armee,o.Nom AS Cdt FROM Division d
                LEFT JOIN Lieu l ON d.Base=l.ID
                LEFT JOIN Armee a ON d.Armee=a.ID
                LEFT JOIN Officier_em o ON a.Cdt=o.ID
                WHERE d.Pays='$country' AND d.Front='$Front' AND d.Active=1 AND d.Maritime=".$Mar_bool." ORDER BY a.Cdt DESC, d.Armee DESC, l.Nom ASC");
                mysqli_close($con);
                if($result9)
                {
                    while($data9=mysqli_fetch_array($result9,MYSQLI_ASSOC))
                    {
                        $Divisions_txt.='<tr><td><a href="ground_em_ia_div.php?id='.$Unit.'&div='.$data9['ID'].'">'.Afficher_Image('images/div/div'.$data9['ID'].'.png','images/'.$country.'div.png',$data9['Nom'],0).'</a><br>'.$data9['Nom'].'</td><td>'.$data9['BaseNom'].'</td><td>'.$data9['Armee'].'</td><td>'.$data9['Cdt'].'</td></tr>';
                    }
                    mysqli_free_result($result9);
                }
            }
            elseif($Ordres_Armee){
                $con=dbconnecti();
                $result9=mysqli_query($con,"SELECT d.ID,d.Nom,d.Base,l.Nom AS BaseNom,a.Nom AS Armee,o.Nom AS Cdt FROM Division d
                LEFT JOIN Lieu l ON d.Base=l.ID
                LEFT JOIN Armee a ON d.Armee=a.ID
                LEFT JOIN Officier_em o ON a.Cdt=o.ID
                WHERE d.Pays='$country' AND d.Armee='$Armee' AND d.Active=1 ORDER BY l.Nom ASC");
                mysqli_close($con);
                if($result9)
                {
                    while($data9=mysqli_fetch_array($result9,MYSQLI_ASSOC))
                    {
                        $Divisions_txt.='<tr><td><a href="ground_em_ia_div.php?id='.$Unit.'&div='.$data9['ID'].'">'.Afficher_Image('images/div/div'.$data9['ID'].'.png','images/'.$country.'div.png',$data9['Nom'],0).'</a><br>'.$data9['Nom'].'</td><td>'.$data9['BaseNom'].'</td><td>'.$data9['Armee'].'</td><td>'.$data9['Cdt'].'</td></tr>';
                    }
                    mysqli_free_result($result9);
                }
            }
            if($Divisions_txt){
                $Divisions_pre='<a href="#" data-toggle="modal" data-target="#modal-div">';
                $Divisions_end='</a>';
                $Divisions_txt='<table class="table table-striped"><thead><tr><th>Division</th><th>Base</th><th>Armee</th><th>Commandant</th></tr></thead><tbody class="text-black">'.$Divisions_txt.'</tbody></table>';
                $Divisions_modal='<div class="modal fade" id="modal-div" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title">Gestion de la division
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </h2>
                                            <a class="btn btn-default" href="ground_em_ia_div.php?id='.$Unit.'&div=9999">Affecter à la réserve</a>
                                        </div>
                                        <div class="modal-body">
                                        '.$Divisions_txt.'
                                        </div>
                                    </div>
                                </div>
                            </div>';
            }
            $Divisions=$Divisions_pre.Afficher_Image('images/div/div'.$Division_d.'.png','images/'.$country.'div.png',$data['Nom'],0).$Divisions_end.$Divisions_modal;
            //--End Divisions--
            $Base_txt='<br><a href="#" class="popup">'.$Retraite_Nom.'<span>Lieu où cette unité peut se ravitailler.<br>La base arrière est définie par la <b>division</b> à laquelle appartient l\'unité.<br>Si l\'unité ne fait pas partie d\'une division, la base arrière est celle du front.</span></a>';
            if($Placement ==5 and !$Pont)$Etat_Pont_txt='<br><div class="alert alert-danger">Le pont est détruit !</div>';
            //Menu GHQ
            if($ghq_txt){
                $ghq_txt='<div class="col-md-6 col-sm-12"><div class="panel panel-war" style="margin-top:10px; width: 300px;">
                                <div class="panel-heading">Planificateur Stratégique</div>
                                <div class="panel-body">'.$ghq_txt.'</div>
                            </div></div>';
            }
            //Objectif unité
            if($lieux_obj){
                if(!$objectif)
                    $objectif_nom='Aucun';
                else
                    $objectif_nom=GetData("Lieu","ID",$objectif,"Nom");
                $Mission_txt='<div class="panel panel-war" style="margin-top:10px; width: 300px;">
                                <div class="panel-heading">Objectif</div>
                                <div class="panel-body">
                                    <h4><a href="#" class="popup"><b class="label label-primary">'.$objectif_nom.'</b><span>Destination à atteindre par cette unité</span></a></h4>
                                    <form action="ground_em_ia_obj.php"><input type="hidden" name="id" value="'.$Unit.'">
                                        <select name="obj" class="form-control" style="width: 200px; display:inline;">
                                            <option value="">Aucun</option>'.$lieux_obj.'
                                        </select>
                                        <input type="submit" class="btn btn-sm btn-warning" value="Changer"></form>
                                </div>
                            </div>';
            }
            //Unit Commands
            if($mobile ==5){
                $Ravit_txt=$Autonomie_txt;
                if($Renforts_txt){
                    $Ravit_txt.='<table class="table table-striped table-condensed">
                                    <thead><tr><th>#</th><th>Coût</th><th>Aide</th></tr></thead>
                                    '.$Renforts_txt.'
                                </table>';
                }
            }
            elseif($Vehicule_Nbr <=0){
                $Ravit_txt='<div class="alert alert-danger">Unité en déroute</div>';
                if($Renforts_txt){
                    $Ravit_txt.='<table class="table table-striped table-condensed">
                                <thead><tr>
                                    <th>#</th>
                                    <th>Stock</th>
                                    <th>Conso</th>
                                </tr></thead>
                                '.$Renforts_txt.'
                            </table>';
                }
                $Upgrade_txt='<div class="alert alert-danger">Unité en déroute</div>';
            }
            else{
                if(!$Arme_Cal){
                    $arme_ravit_txt='<tr><td colspan="3" class="text-success text-center">Ne nécessite pas de ravitaillement en munitions</td></tr>';
                }
                else{
                    $arme_ravit_txt='<tr>
                                    <td>'.$Arme_Cal.'mm</td>
                                    <td>'.$depot_stock_mun.'</td>
                                    <td>'.$Conso_Arme.'</td>
                                </tr>';
                }
                if(!$Octane1){
                    $carbu_ravit_txt='<tr><td colspan="3" class="text-success text-center">Ne nécessite pas de ravitaillement en carburant</td></tr>';
                }
                else{
                    $carbu_ravit_txt='<tr>
                                    <td>'.$Octane1.'</td>
                                    <td>'.$Stock_carbu.'</td>
                                    <td>'.$Conso_move.'</td>
                                </tr>';
                }
                $Ravit_txt='<table class="table table-striped table-condensed">
                                <thead><tr>
                                    <th>#</th>
                                    <th>Stock</th>
                                    <th>Conso</th>
                                </tr></thead>
                                '.$carbu_ravit_txt.$arme_ravit_txt.$Renforts_txt.'
                            </table>';
                if($Transit_Veh !=5000)$ravit_title_extra=' ('.$Auto_Log.'km)';
            }
            if($Atk_Options){
                $Atk_txt=' <table class="table table-striped table-condensed">
                                <thead><tr>
                                    <th>Action</th>
                                    <th>Coût</th>
                                    <th>Conso</th>
                                    <th>Dist</th>
                                    <th>Aide</th>
                                </tr></thead>
                                '.$Atk_Options.'
                             </table>';
            }
            else{
                $Atk_txt=$Upgrade_txt;
            }
            if($Vehicule_Nbr <=($Max_Veh/10))
                $colorNbr = 'danger';
            elseif($Vehicule_Nbr <=($Max_Veh/50))
                $colorNbr = 'warning';
            elseif($Vehicule_Nbr <$Max_Veh)
                $colorNbr = 'primary';
            else
                $colorNbr = 'success';
            if($Admin and !$Move){
                dbconnect();
                $reslieux=$dbh->query("SELECT ID,Nom,Flag FROM Lieu ORDER BY Nom");
                while($lieux_admin=$reslieux->fetchObject()){
                    $lieux_admin_output.='<option value="'.$lieux_admin->ID.'">'.$lieux_admin->Nom.'</option>';
                }
                if($lieux_admin_output){
                    $admin_lieux_txt='<form action="admin/admin_cie_move.php" method="post"><select name="dest" id="dest" style="width:200px">'.$lieux_admin_output.'</select>
                        <input type="hidden" name="reg" value="'.$Unit.'">
                        <input type="submit" value="Déplacer" class="btn btn-sm btn-danger">
                        </form>';
                }
                if($Vehicule_Nbr <$Max_Veh){
                    $admin_lieux_txt.='<form action="admin/admin_cie_full.php" method="post"><input type="hidden" name="reg" value="'.$Unit.'"><input type="hidden" name="Max" value="'.$Max_Veh.'"><input type="submit" value="Heal" class="btn btn-sm btn-danger"></form>';
                }
                $Admin_panel='<div class="panel panel-war text-center"><div class="panel-heading">Admin</div><div class="panel-body">'.$admin_lieux_txt.'</div></div>';
            }
            $mes.=$Alert.$Admin_panel.$matos_modal.'
              <div class="panel panel-war text-center">
                <div class="panel-heading"><div class="row"><div class="col-sm-4">'.$Ville.'<br><a href="#" class="popup"><img src="images/zone'.$Zone.'.jpg"><span>'.$Zone_help_txt.'</span></a></div><div class="col-sm-4">'.$Veh_Nom.'<br><div class="badge">'.$Cie.'e</div></div><div class="col-sm-4">'.$Divisions.'</div></div></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            '.$output_dem.'
                            <div class="panel panel-war" style="min-width:350px;">
                                <div class="panel-heading">Actions</div>
                                <div class="panel-body">
                                    '.$Atk_txt.'
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            '.GetVehiculeIcon($Vehicule,$country,0,0,$Front).'
                            <div class="flex-center" style="min-height:2em;"><span class="label label-'.$colorNbr.'">'.$Vehicule_Nbr.'/'.$Max_Veh.'</span></div><div class="flex-center" style="min-height:2em;">'.$Exp_txt.'</div>'.$Skill_txt.$Cur_HP.$barges_txt.$Conso_txt.'
                            <div class="flex-center mt-2" style="min-height:2em;"><a class="btn btn-sm btn-primary" href="index.php?view=ground_ia_histo&type=1&id='.$Unit.'">Historique</a></div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="panel panel-war">
                                <div class="panel-heading">Ravitaillement'.$ravit_title_extra.'</div>
                                <div class="panel-body">
                                    '.$Ravit_txt.$Ravit_Options.$Carte_Log.'
                                </div>
                                <div class="panel-footer"><span class="badge">Base Arrière</span>'.$Base_txt.'</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="panel panel-war">
                                <div class="panel-heading">Zone</div>
                                <div class="panel-body">
                                    <a href="#" class="popup"><b class="badge">'.GetPlace($Placement).'</b><span>'.$Placement_help.'</span></a>'.$Etat_Pont_txt.'<br>'.$Placements.'
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="panel panel-war">
                                <div class="panel-heading">'.$Pos_titre.'</div>
                                <div class="panel-body">
                                    <a href="#" class="popup"><b class="badge">'.$Pos_ori.'</b><span>'.$Pos_help_txt.'</span></a><br>'.$Positions.'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>';
            $mes.=$Alert_dep.$flr_info."
            <div class='row'><div class='col-lg-12 col-md-12'>".$Decharger.$depot_info.$units_print.$txt_help."</div></div>";
            if($choix)
            {
                $mes.="<h2>Destinations</h2>
                <div class='row'>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Nord Ouest</div>
                    <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$NO_txt."</table></div></div></div></div>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Nord</div>
                    <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$Nord_txt."</table></div></div></div></div>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Nord Est</div>
                    <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$NE_txt."</table></div></div></div></div>
                </div>
                <div class='row'>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Ouest</div>
                    <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$Ouest_txt."</table></div></div></div></div>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='alert alert-warning text-center'><a href='#' class='popup'>Distance maximale : ".$Dist_max_ori."km ".$train_txt."<span><ul><li>Un seul déplacement par jour, peu importe la distance parcourue</li><li>Le déplacement compte comme action du jour</li><li>Distance doublée de noeud routier à noeud routier contrôlés par votre faction</li></span></ul></a></div>
                    <div class='flex-center'>".$Carte_Bouton."</div><div class='flex-center mt-2'>".$Centre_txt."</div><div class='flex-center' style='min-height: 160px;'>".GetVehiculeIcon($Vehicule,$country,0,0,$Front)."</div><span class='badge flex-center'>".$Ville."</span></div>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Est</div>
                    <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$Est_txt."</table></div></div></div></div>
                </div>
                <div class='row'>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Sud Ouest</div>
                    <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$SO_txt."</table></div></div></div></div>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Sud</div>
                    <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$Sud_txt."</table></div></div></div></div>
                    <div class='col-md-4 col-sm-4 col-xs-12'><div class='panel panel-war text-center'><div class='panel-heading'>Sud Est</div>
                    <div class='panel-body'><div class='text-left' style='overflow:auto; height:200px;'><table class='table table-hover table-condensed'>".$SE_txt."</table></div></div></div></div>
                </div>
                <div class='row'><div class='col-md-6 col-sm-12'>".$Mission_txt."</div>".$ghq_txt."</div>";
                $mes.=$lieux_modal;
            }
            elseif($output_dest)
                $mes.=$output_dest;
            if($GHQ)
                $mes.="<hr><form action='index.php?view=ground_em_ia_list_95' method='post'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
            elseif($OfficierEMID >0)
                $mes.="<hr><form action='index.php?view=ground_em_ia_list' method='post'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
            else
                $mes.="<hr><form action='index.php?view=ground_div' method='post'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
            $mes.=$txt_help_dist;
            include_once './default.php';
        }
    }
    else
        echo "<div class='alert alert-danger'>Vous n'êtes pas autorisé à commander cette unité!</div>";
}
else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';