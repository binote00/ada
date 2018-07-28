<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
    $OfficierEMID=$_SESSION['Officier_em'];
    if($OfficierEMID >0)
    {
        $country=$_SESSION['country'];
        include_once('./jfv_include.inc.php');
        include_once('./jfv_inc_em.php');
        include_once('./jfv_txt.inc.php');
        //$Unite=Insec($_POST['Unit']);
        $Unite=919;
        if(!$Unite){
            $Unite = $_SESSION['esc'];
            if($_SESSION['msg_esc'])
                $Alert = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_esc'].'</div>';
            elseif($_SESSION['msg_esc_red'])
                $Alert = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_esc_red'].'</div>';
            $_SESSION['esc'] = false;
            $_SESSION['msg_esc'] = false;
            $_SESSION['msg_esc_red'] = false;
        }
        if($Unite)
        {
            $con=dbconnecti();
            $result=mysqli_query($con,"SELECT Nom,Type,Reputation,Base,Commandant,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Mission_Lieu,Mission_Type,Mission_alt,Mission_Flight,Mission_Lieu_D,Mission_Type_D,Mission_IA,Porte_avions,Ravit,NoEM,Garnison,Armee,
            Date_Mission,DATE_FORMAT(Date_Mission,'%e') as Jour_m,DATE_FORMAT(Date_Mission,'%Hh%i') as Heure_m,DATE_FORMAT(Date_Mission,'%m') as Mois_m 
            FROM Unit WHERE ID='$Unite'")
            or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : emia-unit');
            mysqli_close($con);
            if($result)
            {
                while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                {
                    $Unite_Nom=$data['Nom'];
                    $Unite_Type=$data['Type'];
                    $Unite_Reput=$data['Reputation'];
                    $Base=$data['Base'];
                    $Cdt=$data['Commandant'];
                    $Avion1=$data['Avion1'];
                    $Avion2=$data['Avion2'];
                    $Avion3=$data['Avion3'];
                    $Avion1_Nbr=$data['Avion1_Nbr'];
                    $Avion2_Nbr=$data['Avion2_Nbr'];
                    $Avion3_Nbr=$data['Avion3_Nbr'];
                    $Mission_Lieu=$data['Mission_Lieu'];
                    $Mission_Type=$data['Mission_Type'];
                    $Mission_alt=$data['Mission_alt'];
                    $Mission_Flight=$data['Mission_Flight'];
                    $Mission_Lieu_D=$data['Mission_Lieu_D'];
                    $Mission_Type_D=$data['Mission_Type_D'];
                    $Mission_IA=$data['Mission_IA'];
                    $Porte_avions=$data['Porte_avions'];
                    $Unite_Ravit=$data['Ravit'];
                    $NoEM=$data['NoEM'];
                    $Garnison_Esc=$data['Garnison'];
                    $Unit_Armee=$data['Armee'];
                }
                mysqli_free_result($result);
                unset($data);
            }
        }
        if($Credits >0 and $Base >0 and !$Mission_IA)
        {
            if($OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk)$Off_EM_Sub=true;
            if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk or $GHQ or $Admin or ($Armee >0 and ($Unit_Armee ==$Armee)))
            {
                if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $Admin) //Menu Missions
                {
                    $menu_units_ia="<p><span class='btn btn-info'>Missions ></span>
					<a class='btn btn-default' href='index.php?view=em_missions_7'>Attaque</a>
					<a class='btn btn-default' href='index.php?view=em_missions_2'>Bombardier</a>
					<a class='btn btn-default' href='index.php?view=em_missions_11'>Bombardier lourd</a>
					<a class='btn btn-default' href='index.php?view=em_missions_1'>Chasse</a>
					<a class='btn btn-default' href='index.php?view=em_missions_12'>Chasse embarquée</a>
					<a class='btn btn-default' href='index.php?view=em_missions_4'>Chasse lourde</a>
					<a class='btn btn-default' href='index.php?view=em_missions_10'>Embarqué</a>
					<a class='btn btn-default' href='index.php?view=em_missions_9'>Pat Mar</a>
					<a class='btn btn-default' href='index.php?view=em_missions_3'>Reco</a>
					<a class='btn btn-default' href='index.php?view=em_missions_6'>Transport</a>
					<a class='btn btn-warning' href='index.php?view=em_missions_99'>Mission</a>
					</p>";
                }
                elseif($Armee)
                {
                    $menu_units_ia="<p>
					<a class='btn btn-default' href='index.php?view=em_missions_7'>Attaque</a>
					<a class='btn btn-default' href='index.php?view=em_missions_1'>Chasse</a>
					<a class='btn btn-default' href='index.php?view=em_missions_3'>Reco</a>
					</p>";
                }
                if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $GHQ or $Admin) //Menu Unites
                {
                    $menu_mission="<p><span class='btn btn-info'>Unités ></span> ";
                    if($Unite_Type ==7)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_7'>Attaque</a>";
                    else
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_7'>Attaque</a>";
                    if($Unite_Type ==2)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_2'>Bombardier</a>";
                    else
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_2'>Bombardier</a>";
                    if($Unite_Type ==11)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_11'>Bombardier lourd</a>";
                    else
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_11'>Bombardier lourd</a>";
                    if($Unite_Type ==1)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_1'>Chasse</a>";
                    else
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_1'>Chasse</a>";
                    if($Unite_Type ==12)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_12'>Chasse embarquée</a>";
                    elseif($country ==2 or $country ==7 or $country ==9)
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_12'>Chasse embarquée</a>";
                    if($Unite_Type ==4)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_4'>Chasse lourde</a>";
                    else
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_4'>Chasse lourde</a>";
                    if($Unite_Type ==10)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_10'>Embarqué</a>";
                    elseif($country ==2 or $country ==7 or $country ==9)
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_10'>Embarqué</a>";
                    if($Unite_Type ==9)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_9'>Pat Mar</a>";
                    else
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_9'>Pat Mar</a>";
                    if($Unite_Type ==3)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_3'>Reco</a>";
                    else
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_3'>Reco</a>";
                    if($Unite_Type ==6)
                        $menu_mission.="<a class='btn btn-primary' href='index.php?view=em_unites_6'>Transport</a>";
                    else
                        $menu_mission.="<a class='btn btn-default' href='index.php?view=em_unites_6'>Transport</a>";
                    $menu_mission.="</p>";
                }
                if($country ==3 or $country ==5 or $country ==10 or $country ==15 or $country ==18 or $country ==19 or $country ==35)$Nation_IA=true;
                if($Premium)$Legend=true;
                include_once('./jfv_avions.inc.php');
                $CT_Discount=Get_CT_Discount($Avancement);
                $MaxFlight=GetMaxFlight($Unite_Type,$Unite_Reput,0);
                if($GHQ)$CT_Discount+=4;
                $con=dbconnecti();
                $Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
                $Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
                if($Pilote_id)$Front_Pilote=mysqli_result(mysqli_query($con,"SELECT Front FROM Pilote WHERE ID='$Pilote_id'"),0);
                $Pilotes_max=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Actif='1'"),0);
                $Pilotes_fatigues=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Unit='$Unite' AND Endurance >0"),0);
                $result=mysqli_query($con,"SELECT Nom,Pays,Longitude,Latitude,Zone,Industrie,BaseAerienne,QualitePiste,NoeudF_Ori,NoeudR,Pont_Ori,Port_Ori,Plage,Port,Radar_Ori,Flag_Plage,Flag_Port,Flag_Air,Flag,Meteo FROM Lieu WHERE ID='$Base'");
                $result1=mysqli_query($con,"SELECT Nom,Type,Puissance,Engine,Engine_Nbr,Masse,Autonomie,Plafond,Bombe,Bombe_Nbr,Train,Usine1,Usine2,Usine3,Lease,Rating FROM Avion WHERE ID='$Avion1'");
                $result2=mysqli_query($con,"SELECT Nom,Type,Puissance,Engine,Engine_Nbr,Masse,Autonomie,Plafond,Bombe,Bombe_Nbr,Train,Usine1,Usine2,Usine3,Lease,Rating FROM Avion WHERE ID='$Avion2'");
                $result3=mysqli_query($con,"SELECT Nom,Type,Puissance,Engine,Engine_Nbr,Masse,Autonomie,Plafond,Bombe,Bombe_Nbr,Train,Usine1,Usine2,Usine3,Lease,Rating FROM Avion WHERE ID='$Avion3'");
                $xp_avion1="<br><a href='#' class='popup'>Expérience de l'unité sur ce modèle <b>".(floor(mysqli_result(mysqli_query($con,"SELECT Exp FROM XP_Avions_IA WHERE Unite='$Unite' AND AvionID='$Avion1'"),0))/10)."</b><span>Bonus de pilotage et de tactique lors des missions</span></a>";
                $xp_avion2="<br><a href='#' class='popup'>Expérience de l'unité sur ce modèle <b>".(floor(mysqli_result(mysqli_query($con,"SELECT Exp FROM XP_Avions_IA WHERE Unite='$Unite' AND AvionID='$Avion2'"),0))/10)."</b><span>Bonus de pilotage et de tactique lors des missions</span></a>";
                $xp_avion3="<br><a href='#' class='popup'>Expérience de l'unité sur ce modèle <b>".(floor(mysqli_result(mysqli_query($con,"SELECT Exp FROM XP_Avions_IA WHERE Unite='$Unite' AND AvionID='$Avion3'"),0))/10)."</b><span>Bonus de pilotage et de tactique lors des missions</span></a>";
                $Pilotes_res=mysqli_query($con,"SELECT COUNT(*),AVG(Navigation) FROM Pilote_IA WHERE Unit='$Unite' AND Courage >0 AND Moral >0 AND Actif=1");
                $Pilotes_result=mysqli_query($con,"SELECT s.ID,s.Infos,p.Courage,p.Moral,p.Actif FROM Pilote_IA AS p,Skills as s WHERE s.ID=p.Skill AND s.Team=1 AND p.Unit='$Unite' AND p.Actif=1");
                mysqli_close($con);
                if($Pilotes_result)
                {
                    $Pilotes=mysqli_result($Pilotes_res,0);
                    $Nav_Moy=mysqli_result($Pilotes_res,1);
                    while($datap=mysqli_fetch_array($Pilotes_result,MYSQLI_ASSOC))
                    {
                        if($datap['Courage'] >0 and $datap['Moral'] >0)
                            $skill_txt.="<a href='#' class='popup'><img src='images/skills/skill".$datap['ID']."p.png'><span>".$datap['Infos']."</span></a>";
                        else
                            $skill_txt.="<a href='#' class='popup'><img class='img_opa' src='images/skills/skill".$datap['ID']."p.png'><span>".$datap['Infos']."</span></a>";
                    }
                    mysqli_free_result($Pilotes_result);
                }
                if(!$skill_txt)$skill_txt='Aucune';
                if($result3)
                {
                    while($data3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
                    {
                        if($Avion3_Nbr >0)
                        {
                            $Avion3_nom=$data3['Nom'];
                            $Avion3_rating=$data3['Rating'];
                            $Avion3_a=floor((($data3['Autonomie']/2)-200)+$Nav_Moy);
                            if($Avion3_a <50)$Avion3_a=50;
                            $Avion3_mot=$data3['Engine'];
                            $Avion3_en=$data3['Engine_Nbr'];
                            $Avion3_p=$data3['Plafond'];
                            $Avion3_bombs=$data3['Bombe'];
                            $Avion3_bombs_nbr=$data3['Bombe_Nbr'];
                            $Train3=$data3['Train'];
                            $Array_Mod=GetAmeliorations($Avion3);
                            $Avion3_a_l=floor($Avion3_a+($Array_Mod[18]/2));
                            $Avion3_p_l=$Avion3_p-$Array_Mod[18];
                            if($Array_Mod[13] >0 or $Array_Mod[14] >0 or $Array_Mod[15] >0 or $Array_Mod[35] >0)$Avion3_btac=true;
                            if($Array_Mod[16] >0 or $Array_Mod[17] >0)$Avion3_rec=true;
                            /*if($Array_Mod[33])$Masse_sup3=$Array_Mod[33]*2000;
                            elseif($Array_Mod[32])$Masse_sup3=$Array_Mod[32]*1000;
                            elseif($Array_Mod[20])$Masse_sup3=$Array_Mod[20]*800;
                            elseif($Array_Mod[15])$Masse_sup3=$Array_Mod[15]*500;
                            elseif($Array_Mod[14])$Masse_sup3=$Array_Mod[14]*250;
                            elseif($Array_Mod[13])$Masse_sup3=$Array_Mod[13]*125;
                            elseif($Array_Mod[12])$Masse_sup3=$Array_Mod[12]*50;*/
                            $Massef3_s=$data3['Masse']+($data3['Bombe']*$data3['Bombe_Nbr']);
                            $Massef3_t=$data3['Masse']+$data3['Bombe'];
                            $Poids_Puiss_ori3=$data3['Masse']/$data3['Puissance'];
                            $Poids_Puiss3_s=$Massef3_s/$data3['Puissance'];
                            $Poids_Puiss3_t=$Massef3_t/$data3['Puissance'];
                            if($data3['Type'] ==2 or $data3['Type'] ==11)
                                $Avion3_a_s=round($data3['Autonomie']-(($Poids_Puiss3_s-$Poids_Puiss_ori3)*($Massef3_s/10)));
                            else
                                $Avion3_a_s=round(($data3['Autonomie']/2)-(($Poids_Puiss3_s-$Poids_Puiss_ori3)*($Massef3_s/10)));
                            $Avion3_a_t=round(($data3['Autonomie']/2)-(($Poids_Puiss3_t-$Poids_Puiss_ori3)*($Massef3_t/10)));
                        }
                        $Avion3_u1=$data3['Usine1'];
                        $Avion3_u2=$data3['Usine2'];
                        $Avion3_u3=$data3['Usine3'];
                        $Avion3_Lease=$data3['Lease'];
                    }
                    mysqli_free_result($result3);
                    unset($data3);
                }
                if($result2)
                {
                    while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
                    {
                        if($Avion2_Nbr >0)
                        {
                            $Avion2_nom=$data2['Nom'];
                            $Avion2_rating=$data2['Rating'];
                            $Avion2_a=floor((($data2['Autonomie']/2)-200)+$Nav_Moy);
                            if($Avion2_a <50)$Avion2_a=50;
                            $Avion2_mot=$data2['Engine'];
                            $Avion2_en=$data2['Engine_Nbr'];
                            $Avion2_p=$data2['Plafond'];
                            $Avion2_bombs=$data2['Bombe'];
                            $Avion2_bombs_nbr=$data2['Bombe_Nbr'];
                            $Train2=$data2['Train'];
                            $Array_Mod=GetAmeliorations($Avion2);
                            if($Array_Mod[13] >0 or $Array_Mod[14] >0 or $Array_Mod[15] >0 or $Array_Mod[35] >0)$Avion2_btac=true;
                            if($Array_Mod[16] >0 or $Array_Mod[17] >0)$Avion2_rec=true;
                            $Avion2_a_l=floor($Avion2_a+($Array_Mod[18]/2));
                            $Avion2_p_l=$Avion2_p-$Array_Mod[18];
                            $Massef2_s=$data2['Masse']+($data2['Bombe']*$data2['Bombe_Nbr']);
                            $Massef2_t=$data2['Masse']+$data2['Bombe'];
                            $Poids_Puiss_ori2=$data2['Masse']/$data2['Puissance'];
                            $Poids_Puiss2_s=$Massef2_s/$data2['Puissance'];
                            $Poids_Puiss2_t=$Massef2_t/$data2['Puissance'];
                            if($data2['Type'] ==2 or $data2['Type'] ==11)
                                $Avion2_a_s=round($data2['Autonomie']-(($Poids_Puiss2_s-$Poids_Puiss_ori2)*($Massef2_s/10)));
                            else
                                $Avion2_a_s=round(($data2['Autonomie']/2)-(($Poids_Puiss2_s-$Poids_Puiss_ori2)*($Massef2_s/10)));
                            $Avion2_a_t=round(($data2['Autonomie']/2)-(($Poids_Puiss2_t-$Poids_Puiss_ori2)*($Massef2_t/10)));
                        }
                        $Avion2_u1=$data2['Usine1'];
                        $Avion2_u2=$data2['Usine2'];
                        $Avion2_u3=$data2['Usine3'];
                        $Avion2_Lease=$data2['Lease'];
                    }
                    mysqli_free_result($result2);
                    unset($data2);
                }
                if($result1)
                {
                    while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
                    {
                        if($Avion1_Nbr >0)
                        {
                            $Avion1_nom=$data1['Nom'];
                            $Avion1_rating=$data1['Rating'];
                            $Avion1_a=floor((($data1['Autonomie']/2)-200)+$Nav_Moy);
                            if($Avion1_a <50)$Avion1_a=50;
                            $Avion1_mot=$data1['Engine'];
                            $Avion1_en=$data1['Engine_Nbr'];
                            $Avion1_p=$data1['Plafond'];
                            $Avion1_bombs=$data1['Bombe'];
                            $Avion1_bombs_nbr=$data1['Bombe_Nbr'];
                            $Train1=$data1['Train'];
                            $Array_Mod=GetAmeliorations($Avion1);
                            if($Array_Mod[13] >0 or $Array_Mod[14] >0 or $Array_Mod[15] >0 or $Array_Mod[35] >0)$Avion1_btac=true;
                            if($Array_Mod[16] >0 or $Array_Mod[17] >0)$Avion1_rec=true;
                            $Avion1_a_l=floor($Avion1_a+($Array_Mod[18]/2));
                            $Avion1_p_l=$Avion1_p-$Array_Mod[18];
                            $Massef1_s=$data1['Masse']+($data1['Bombe']*$data1['Bombe_Nbr']);
                            $Massef1_t=$data1['Masse']+$data1['Bombe'];
                            $Poids_Puiss_ori1=$data1['Masse']/$data1['Puissance'];
                            $Poids_Puiss1_s=$Massef1_s/$data1['Puissance'];
                            $Poids_Puiss1_t=$Massef1_t/$data1['Puissance'];
                            if($data1['Type'] ==2 or $data1['Type'] ==11)
                                $Avion1_a_s=round($data1['Autonomie']-(($Poids_Puiss1_s-$Poids_Puiss_ori1)*($Massef1_s/10)));
                            else
                                $Avion1_a_s=round(($data1['Autonomie']/2)-(($Poids_Puiss1_s-$Poids_Puiss_ori1)*($Massef1_s/10)));
                            $Avion1_a_t=round(($data1['Autonomie']/2)-(($Poids_Puiss1_t-$Poids_Puiss_ori1)*($Massef1_t/10)));
                        }
                        $Avion1_u1=$data1['Usine1'];
                        $Avion1_u2=$data1['Usine2'];
                        $Avion1_u3=$data1['Usine3'];
                        $Avion1_Lease=$data1['Lease'];
                    }
                    mysqli_free_result($result1);
                    unset($data1);
                }
                if($result)
                {
                    while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                    {
                        $Base_Nom=$data['Nom'];
                        $Base_Pays=$data['Pays'];
                        $Longitude_base=$data['Longitude'];
                        $Latitude_base=$data['Latitude'];
                        $Zone=$data['Zone'];
                        $Meteo=$data['Meteo'];
                        $Usine=$data['Industrie'];
                        $Port=$data['Port'];
                        $Plage=$data['Plage'];
                        $NoeudF_Ori=$data['NoeudF_Ori'];
                        $NoeudR=$data['NoeudR'];
                        $Radar=$data['Radar_Ori'];
                        $Pont_Ori=$data['Pont_Ori'];
                        $Port_Ori=$data['Port_Ori'];
                        $BaseAerienne=$data['BaseAerienne'];
                        $QualitePiste=$data['QualitePiste'];
                        $Flag_Air=$data['Flag_Air'];
                        $Flag_Port=$data['Flag_Port'];
                        $Flag_Plage=$data['Flag_Plage'];
                        $Flag=$data['Flag'];
                    }
                    mysqli_free_result($result);
                    unset($data);
                }
                $Front_unit=GetFrontByCoord(0,$Latitude_base,$Longitude_base);
                if(!$Porte_avions)
                {
                    $Train_Hydra=array(13,16);
                    if($GHQ and $Credits >=8 and ($Unite_Type ==10 or $Unite_Type ==12)) //unités embarquées
                    {
                        $con=dbconnecti();
                        $resultpac=mysqli_query($con,"SELECT c.ID,c.Nom,c.Esc FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND c.Pays='$country' AND c.Type=21 AND r.Vehicule_Nbr=1");
                        if($resultpac)
                        {
                            while($datapac=mysqli_fetch_array($resultpac,MYSQLI_ASSOC))
                            {
                                //$con=dbconnecti();
                                $Units_PA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Unit WHERE Porte_avions='".$datapac['ID']."'"),0);
                                //mysqli_close($con);
                                if($Units_PA <$datapac['Esc'])
                                    $pac.="<option value='".$datapac['ID']."'>".$datapac['Nom']." (".$Units_PA."/".$datapac['Esc'].")</option>";
                                else
                                    $pac2.="<option value='".$datapac['ID']."' disabled>".$datapac['Nom']." (".$Units_PA."/".$datapac['Esc'].")</option>";
                            }
                            mysqli_free_result($resultpac);
                        }
                        mysqli_close($con);
                        if($pac)
                            $PA_Button="<h2>Embarquement sur un porte-avions</h2><form action='index.php?view=ghq_add_pa' method='post'><input type='hidden' name='Unite' value='".$Unite."'><select name='PAC' class='form-control' style='width: 200px'>".$pac.$pac2."</select>
							<br><img src='images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Embarquer' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                        else
                            $PA_Button="<p class='lead'>Aucun porte-avions n'est disponible pour embarquer cette unité</p>";
                    }
                    elseif(in_array($Train1,$Train_Hydra) and in_array($Train2,$Train_Hydra) and in_array($Train3,$Train_Hydra))
                    {
                        $QualitePiste=100-$Meteo;
                        if($BaseAerienne !=4 and $BaseAerienne !=2)
                            $Faction_Air=GetData("Pays","ID",$Flag_Port,"Faction");
                        else
                            $Faction_Air=GetData("Pays","ID",$Flag_Air,"Faction");
                        if(!$Faction_Air)$Faction_Air=GetData("Pays","ID",$Flag_Plage,"Faction");
                        $Hydravion=true;
                    }
                }
                elseif($Porte_avions >0)
                {
                    $con=dbconnecti();
                    $resultpa=mysqli_query($con,"SELECT Autonomie,Armee FROM Regiment_IA WHERE Vehicule_ID='$Porte_avions'");
                    $PA_Nom=mysqli_result(mysqli_query($con,"SELECT Nom FROM Cible WHERE ID='$Porte_avions'"),0);
                    mysqli_close($con);
                    if($resultpa)
                    {
                        while($datapa=mysqli_fetch_array($resultpa))
                        {
                            $Autonomie_PA=$datapa['Autonomie'];
                            $Armee_PA=$datapa['Armee'];
                        }
                        mysqli_free_result($resultpa);
                    }
                    $Embark_txt='Embarqué sur le <b>'.$PA_Nom.'</b>';
                    if($GHQ and $Credits >=8 and $Zone !=6)
                    {
                        $PA_Button="<form action='index.php?view=ghq_add_pa' method='post'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='Armee' value='".$Armee_PA."'><input type='hidden' name='PAC' value='0'>
						<img src='images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Débarquer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                }
                if(IsAxe($country))
                    $Allies=array(1,6,9,15,18,19,20,24);
                else
                    $Allies=array(2,3,4,5,7,8,10,35,36);
                if($Front_Pilote ==$Front)$CT_Discount-=4;
                $Sqn=GetSqn($country);
                $CT_Refit=36-$CT_Discount;
                $CT_Restore=12-$CT_Discount;
                $CT_Replace=12-$CT_Discount;
                $CT_Upgrade=16-$CT_Discount;
                if($Off_EM_Sub)$CT_Restore-=2;
                if($Trait==14)$CT_Refit-=2;
                if($Trait==1)$CT_Restore-=2;
                if($Trait==8)
                {
                    $CT_Upgrade-=2;
                    $CT_Replace-=2;
                }
                $LongPiste_mini=GetLongPisteMin($Unite_Type,$Avion1,$Avion2,$Avion3);
                $Faction_Flag=GetData("Pays","ID",$Flag,"Faction");
                if($BaseAerienne and !$Hydravion)
                    $Faction_Air=GetData("Pays","ID",$Flag_Air,"Faction");
                if($Credits >=2)
                {
                    if($Avion1_u1)
                    {
                        $con=dbconnecti();
                        $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag,Flag_Air,Flag_Usine,Port_Ori FROM Lieu WHERE ID='$Avion1_u1'");
                        mysqli_close($con);
                        if($resultu)
                        {
                            while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                            {
                                $Avion1_u1_n=$datau['Nom'];
                                if($datau['Port_Ori'] and ($Train1 ==13 or $Train1 ==16))
                                    $Avion1_u1_p=2000;
                                else
                                    $Avion1_u1_p=$datau['LongPiste'];
                                $Avion1_u1_fa=$datau['Flag_Air'];
                                $Avion1_u1_fu=$datau['Flag_Usine'];
                                $Avion1_u1_f=$datau['Flag'];
                            }
                            mysqli_free_result($resultu);
                        }
                        if($Avion1_Lease)
                        {
                            if(in_array($Avion1_u1_f,$Allies) and in_array($Avion1_u1_fu,$Allies))
                                $lend_lease1=true;
                            else
                                $lend_lease1=false;
                        }
                        else
                            $lend_lease1=true;
                    }
                    if($Avion1_Nbr <$MaxFlight or $Avion1 ==$Avion2 or $Avion1 ==$Avion3)
                    {
                        if($Avion1_u2)
                        {
                            $con=dbconnecti();
                            $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag_Air,Flag,Port_Ori FROM Lieu WHERE ID='$Avion1_u2'");
                            mysqli_close($con);
                            if($resultu)
                            {
                                while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                                {
                                    $Avion1_u2_n=$datau['Nom'];
                                    if($datau['Port_Ori'] and ($Train1 ==13 or $Train1 ==16))
                                        $Avion1_u2_p=2000;
                                    else
                                        $Avion1_u2_p=$datau['LongPiste'];
                                    $Avion1_u2_fa=$datau['Flag_Air'];
                                    $Avion1_u2_f=$datau['Flag'];
                                }
                                mysqli_free_result($resultu);
                            }
                        }
                        if($Avion1_u3)
                        {
                            $con=dbconnecti();
                            $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag_Air,Flag,Port_Ori FROM Lieu WHERE ID='$Avion1_u3'");
                            mysqli_close($con);
                            if($resultu)
                            {
                                while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                                {
                                    $Avion1_u3_n=$datau['Nom'];
                                    if($datau['Port_Ori'] and ($Train1 ==13 or $Train1 ==16))
                                        $Avion1_u3_p=2000;
                                    else
                                        $Avion1_u3_p=$datau['LongPiste'];
                                    $Avion1_u3_fa=$datau['Flag_Air'];
                                    $Avion1_u3_f=$datau['Flag'];
                                }
                                mysqli_free_result($resultu);
                            }
                        }
                    }
                    if($Avion2_Nbr <$MaxFlight or $Avion2 ==$Avion3)
                    {
                        if($Avion1 ==$Avion2)
                        {
                            $Avion2_u1_n=$Avion1_u1_n;
                            $Avion2_u1_p=$Avion1_u1_p;
                            $Avion2_u1_fa=$Avion1_u1_fa;
                            $Avion2_u1_f=$Avion1_u1_f;
                            $Avion2_u2_n=$Avion1_u2_n;
                            $Avion2_u2_p=$Avion1_u2_p;
                            $Avion2_u2_fa=$Avion1_u2_fa;
                            $Avion2_u2_f=$Avion1_u2_f;
                            $Avion2_u3_n=$Avion1_u3_n;
                            $Avion2_u3_p=$Avion1_u3_p;
                            $Avion2_u3_fa=$Avion1_u3_fa;
                            $Avion2_u3_f=$Avion1_u3_f;
                            $lend_lease2=$lend_lease1;
                        }
                        else
                        {
                            if($Avion2_u1)
                            {
                                $con=dbconnecti();
                                $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag,Flag_Air,Flag_Usine,Port_Ori FROM Lieu WHERE ID='$Avion2_u1'");
                                mysqli_close($con);
                                if($resultu)
                                {
                                    while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                                    {
                                        $Avion2_u1_n=$datau['Nom'];
                                        if($datau['Port_Ori'] and ($Train2 ==13 or $Train2 ==16))
                                            $Avion2_u1_p=2000;
                                        else
                                            $Avion2_u1_p=$datau['LongPiste'];
                                        $Avion2_u1_fa=$datau['Flag_Air'];
                                        $Avion2_u1_fu=$datau['Flag_Usine'];
                                        $Avion2_u1_f=$datau['Flag'];
                                    }
                                    mysqli_free_result($resultu);
                                }
                                if($Avion2_Lease)
                                {
                                    if(in_array($Avion2_u1_f,$Allies) and in_array($Avion2_u1_fu,$Allies))
                                        $lend_lease2=true;
                                    else
                                        $lend_lease2=false;
                                }
                                else
                                    $lend_lease2=true;
                            }
                            if($Avion2_u2)
                            {
                                $con=dbconnecti();
                                $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag_Air,Flag,Port_Ori FROM Lieu WHERE ID='$Avion2_u2'");
                                mysqli_close($con);
                                if($resultu)
                                {
                                    while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                                    {
                                        $Avion2_u2_n=$datau['Nom'];
                                        if($datau['Port_Ori'] and ($Train2 ==13 or $Train2 ==16))
                                            $Avion2_u2_p=2000;
                                        else
                                            $Avion2_u2_p=$datau['LongPiste'];
                                        $Avion2_u2_fa=$datau['Flag_Air'];
                                        $Avion2_u2_f=$datau['Flag'];
                                    }
                                    mysqli_free_result($resultu);
                                }
                            }
                            if($Avion2_u3)
                            {
                                $con=dbconnecti();
                                $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag_Air,Flag,Port_Ori FROM Lieu WHERE ID='$Avion2_u3'");
                                mysqli_close($con);
                                if($resultu)
                                {
                                    while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                                    {
                                        $Avion2_u3_n=$datau['Nom'];
                                        if($datau['Port_Ori'] and ($Train2 ==13 or $Train2 ==16))
                                            $Avion2_u3_p=2000;
                                        else
                                            $Avion2_u3_p=$datau['LongPiste'];
                                        $Avion2_u3_fa=$datau['Flag_Air'];
                                        $Avion2_u3_f=$datau['Flag'];
                                    }
                                    mysqli_free_result($resultu);
                                }
                            }
                        }
                    }
                    if($Avion3_Nbr <$MaxFlight)
                    {
                        if($Avion1 ==$Avion3)
                        {
                            $Avion3_u1_n=$Avion1_u1_n;
                            $Avion3_u1_p=$Avion1_u1_p;
                            $Avion3_u1_fa=$Avion1_u1_fa;
                            $Avion3_u1_f=$Avion1_u1_f;
                            $Avion3_u2_n=$Avion1_u2_n;
                            $Avion3_u2_p=$Avion1_u2_p;
                            $Avion3_u2_fa=$Avion1_u2_fa;
                            $Avion3_u2_f=$Avion1_u2_f;
                            $Avion3_u3_n=$Avion1_u3_n;
                            $Avion3_u3_p=$Avion1_u3_p;
                            $Avion3_u3_fa=$Avion1_u3_fa;
                            $Avion3_u3_f=$Avion1_u3_f;
                            $lend_lease3=$lend_lease1;
                        }
                        elseif($Avion2 ==$Avion3)
                        {
                            $Avion3_u1_n=$Avion2_u1_n;
                            $Avion3_u1_p=$Avion2_u1_p;
                            $Avion3_u1_fa=$Avion2_u1_fa;
                            $Avion3_u1_f=$Avion2_u1_f;
                            $Avion3_u2_n=$Avion2_u2_n;
                            $Avion3_u2_p=$Avion2_u2_p;
                            $Avion3_u2_fa=$Avion2_u2_fa;
                            $Avion3_u2_f=$Avion2_u2_f;
                            $Avion3_u3_n=$Avion2_u3_n;
                            $Avion3_u3_p=$Avion2_u3_p;
                            $Avion3_u3_fa=$Avion2_u3_fa;
                            $Avion3_u3_f=$Avion2_u3_f;
                            $lend_lease3=$lend_lease2;
                        }
                        else
                        {
                            if($Avion3_u1)
                            {
                                $con=dbconnecti();
                                $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag,Flag_Air,Flag_Usine,Port_Ori FROM Lieu WHERE ID='$Avion3_u1'");
                                mysqli_close($con);
                                if($resultu)
                                {
                                    while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                                    {
                                        $Avion3_u1_n=$datau['Nom'];
                                        if($datau['Port_Ori'] and ($Train3 ==13 or $Train3 ==16))
                                            $Avion3_u1_p=2000;
                                        else
                                            $Avion3_u1_p=$datau['LongPiste'];
                                        $Avion3_u1_fa=$datau['Flag_Air'];
                                        $Avion3_u1_fu=$datau['Flag_Usine'];
                                        $Avion3_u1_f=$datau['Flag'];
                                    }
                                    mysqli_free_result($resultu);
                                }
                                if($Avion3_Lease)
                                {
                                    if(in_array($Avion3_u1_f,$Allies) and in_array($Avion3_u1_fu,$Allies))
                                        $lend_lease3=true;
                                    else
                                        $lend_lease3=false;
                                }
                                else
                                    $lend_lease3=true;
                            }
                            if($Avion3_u2)
                            {
                                $con=dbconnecti();
                                $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag_Air,Flag,Port_Ori FROM Lieu WHERE ID='$Avion3_u2'");
                                mysqli_close($con);
                                if($resultu)
                                {
                                    while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                                    {
                                        $Avion3_u2_n=$datau['Nom'];
                                        if($datau['Port_Ori'] and ($Train3 ==13 or $Train3 ==16))
                                            $Avion3_u2_p=2000;
                                        else
                                            $Avion3_u2_p=$datau['LongPiste'];
                                        $Avion3_u2_fa=$datau['Flag_Air'];
                                        $Avion3_u2_f=$datau['Flag'];
                                    }
                                    mysqli_free_result($resultu);
                                }
                            }
                            if($Avion3_u3)
                            {
                                $con=dbconnecti();
                                $resultu=mysqli_query($con,"SELECT Nom,LongPiste,Flag_Air,Flag,Port_Ori FROM Lieu WHERE ID='$Avion3_u3'");
                                mysqli_close($con);
                                if($resultu)
                                {
                                    while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
                                    {
                                        $Avion3_u3_n=$datau['Nom'];
                                        if($datau['Port_Ori'] and ($Train3 ==13 or $Train3 ==16))
                                            $Avion3_u3_p=2000;
                                        else
                                            $Avion3_u3_p=$datau['LongPiste'];
                                        $Avion3_u3_fa=$datau['Flag_Air'];
                                        $Avion3_u3_f=$datau['Flag'];
                                    }
                                    mysqli_free_result($resultu);
                                }
                            }
                        }
                    }
                    unset($datau);
                }
                //Output
                //include_once('./menu_escadrille.php');
                if($Credits >=$CT_Replace and $Faction_Flag ==$Faction and $Faction_Air ==$Faction)
                {
                    if($Avion1_Nbr <2 or $GHQ)
                        $But_F1="<form action='em_ia4.php' method='post'><input type='hidden' name='flight' value='1'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Replace."'><input type='hidden' name='mode' value='1'>
						<br><img src='images/CT".$CT_Replace.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Remplacer' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    if($Avion2_Nbr <2 or $GHQ)
                        $But_F2="<form action='em_ia4.php' method='post'><input type='hidden' name='flight' value='2'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Replace."'><input type='hidden' name='mode' value='1'>
						<br><img src='images/CT".$CT_Replace.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Remplacer' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    if($Avion3_Nbr <2 or $GHQ)
                        $But_F3="<form action='em_ia4.php' method='post'><input type='hidden' name='flight' value='3'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Replace."'><input type='hidden' name='mode' value='1'>
						<br><img src='images/CT".$CT_Replace.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Remplacer' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    if($GHQ and $Credits >=$CT_Upgrade)
                    {
                        $But_A1="<form action='em_ia4.php' method='post'><input type='hidden' name='flight' value='1'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Upgrade."'><input type='hidden' name='mode' value='2'>
						<br><img src='images/CT".$CT_Upgrade.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Améliorer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                        $But_A2="<form action='em_ia4.php' method='post'><input type='hidden' name='flight' value='2'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Upgrade."'><input type='hidden' name='mode' value='2'>
						<br><img src='images/CT".$CT_Upgrade.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Améliorer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                        $But_A3="<form action='em_ia4.php' method='post'><input type='hidden' name='flight' value='3'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Upgrade."'><input type='hidden' name='mode' value='2'>
						<br><img src='images/CT".$CT_Upgrade.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Améliorer' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                }
                if($Credits >=2)
                {
                    if($Avion1_Nbr <$MaxFlight)
                    {
                        $GHQ_ask=true;
                        if($Avion1_u2 and $Avion1_u2_p >=$LongPiste_mini)
                            $Avion1_u2_n=",".$Avion1_u2_n;
                        else
                            $Avion1_u2_n="";
                        if($Avion1_u3 and $Avion1_u3_p >=$LongPiste_mini)
                            $Avion1_u3_n=",".$Avion1_u3_n;
                        else
                            $Avion1_u3_n="";
                        if($Credits >=$CT_Refit and $lend_lease1){
                            $But_R1="<form action='em_ia3.php' method='post'><input type='hidden' name='flight' value='1'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Refit."'>
							<br><img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Ravitailler' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                            $Rav_R1="Ravitaillement pour <img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'> possible à ".$Avion1_u1_n.$Avion1_u2_n.$Avion1_u3_n;
                        }
                        else
                            $But_R1="Ravitaillement pour <img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'> ici et pour <img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'> possible à ".$Avion1_u1_n.$Avion1_u2_n.$Avion1_u3_n;
                        if(!$Avion1_Lease and $Avion1_Nbr <1 and $Usine1_1 and $Avion1_u1_fa ==$country and $Avion1_u1_f ==$country)
                            $Return_R1="<form action='em_gestioncdt3' method='post'><input type='hidden' name='unitet' value='".$Unite."'><input type='hidden' name='Transfer_esc' value=".$Avion1_u1."><input type='hidden' name='Transfer_val' value='1'><input type='hidden' name='cr' value='".$CT_Refit."'>
							<br><img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='submit' value='Retour à ".$Avion1_u1_n."' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                    if($Avion2_Nbr <$MaxFlight)
                    {
                        $GHQ_ask=true;
                        if($Avion2_u2 and $Avion2_u2_p >=$LongPiste_mini)
                            $Avion2_u2_n=",".$Avion2_u2_n;
                        else
                            $Avion2_u2_n="";
                        if($Avion2_u3 and $Avion2_u3_p >=$LongPiste_mini)
                            $Avion2_u3_n=",".$Avion2_u3_n;
                        else
                            $Avion2_u3_n="";
                        if($Credits >=$CT_Refit and $lend_lease2){
                            $But_R2="<form action='em_ia3.php' method='post'><input type='hidden' name='flight' value='2'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Refit."'>
							<br><img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Ravitailler' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							$Rav_R2="Ravitaillement pour <img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'> possible à ".$Avion2_u1_n.$Avion2_u2_n.$Avion2_u3_n;
                        }
                        else
                            $But_R2="Ravitaillement pour <img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'> ici et pour <img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'> possible à ".$Avion2_u1_n.$Avion2_u2_n.$Avion2_u3_n;
                        if(!$Avion2_Lease and $Avion2_Nbr <1 and $Usine2_1 and $Avion2_u1_fa == $country and $Avion2_u1_f == $country)
                            $Return_R2="<form action='em_gestioncdt3' method='post'><input type='hidden' name='unitet' value='".$Unite."'><input type='hidden' name='Transfer_esc' value=".$Avion2_u1."><input type='hidden' name='Transfer_val' value='1'><input type='hidden' name='cr' value='".$CT_Refit."'>
							<img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Retour à ".$Avion2_u1_n."' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                    if($Avion3_Nbr <$MaxFlight)
                    {
                        $GHQ_ask=true;
                        if($Avion3_u2 and $Avion3_u2_p >=$LongPiste_mini)
                            $Avion3_u2_n=",".$Avion3_u2_n;
                        else
                            $Avion3_u2_n="";
                        if($Avion3_u3 and $Avion3_u3 >=$LongPiste_mini)
                            $Avion3_u3_n=",".$Avion3_u3_n;
                        else
                            $Avion3_u3_n="";
                        if($Credits >=$CT_Refit and $lend_lease3){
                            $But_R3="<form action='em_ia3.php' method='post'><input type='hidden' name='flight' value='3'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='".$CT_Refit."'>
							<br><img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Ravitailler' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
							$Rav_R3="Ravitaillement pour <img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'> possible à ".$Avion3_u1_n.$Avion3_u2_n.$Avion3_u3_n;
                        }
                        else
                            $But_R3="Ravitaillement pour <img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'> ici et pour <img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'> possible à ".$Avion3_u1_n.$Avion3_u2_n.$Avion3_u3_n;
                        if(!$Avion3_Lease and $Avion3_Nbr <1 and $Usine3_1 and $Avion3_u1_fa ==$country and $Avion3_u1_f ==$country)
                            $Return_R3="<form action='em_gestioncdt3' method='post'><input type='hidden' name='unitet' value='".$Unite."'><input type='hidden' name='Transfer_esc' value=".$Avion3_u1."><input type='hidden' name='Transfer_val' value='1'><input type='hidden' name='cr' value='".$CT_Refit."'>
							<img src='images/CT".$CT_Refit.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Retour à ".$Avion3_u1_n."' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                    if($Base >0 and $Usine >0 and $Faction_Flag ==$Faction and $Faction_Air ==$Faction)
                    {
                        if($GHQ)$ghq_hidden_ravit="<input type='hidden' name='ghq' value='".$GHQ."'>";
                        $Avion1_Usine=false;
                        $Avion2_Usine=false;
                        $Avion3_Usine=false;
                        if($Base ==$Avion1_u1 or ($Base ==$Avion1_u2 and $Avion1_u2_p >=$LongPiste_mini) or ($Base ==$Avion1_u3 and $Avion1_u3_p >=$LongPiste_mini))
                            $Avion1_Usine=true;
                        if($Base ==$Avion2_u1 or ($Base ==$Avion2_u2 and $Avion2_u2_p >=$LongPiste_mini) or ($Base ==$Avion2_u3 and $Avion2_u3_p >=$LongPiste_mini))
                            $Avion2_Usine=true;
                        if($Base ==$Avion3_u1 or ($Base ==$Avion3_u2 and $Avion3_u2_p >=$LongPiste_mini) or ($Base ==$Avion3_u3 and $Avion3_u3_p >=$LongPiste_mini))
                            $Avion3_Usine=true;
                        if($Avion1_Nbr <$MaxFlight and $Avion1_Usine and $lend_lease1)
                            $But_R1="<form action='em_ia3.php' method='post'><input type='hidden' name='flight' value='1'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='2'>".$ghq_hidden_ravit."
							<br><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Ravitailler' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                        if($Avion2_Nbr <$MaxFlight and $Avion2_Usine and $lend_lease2)
                            $But_R2="<form action='em_ia3.php' method='post'><input type='hidden' name='flight' value='2'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='2'>".$ghq_hidden_ravit."
							<br><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Ravitailler' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                        if($Avion3_Nbr <$MaxFlight and $Avion3_Usine and $lend_lease3)
                            $But_R3="<form action='em_ia3.php' method='post'><input type='hidden' name='flight' value='3'><input type='hidden' name='Unite' value='".$Unite."'><input type='hidden' name='CT' value='2'>".$ghq_hidden_ravit."
							<br><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Ravitailler' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                }
               /*$But_F1.=$But_R1;
                $But_F2.=$But_R2;
                $But_F3.=$But_R3;*/
                /*if($Unite_Type ==2 or $Unite_Type ==7 or $Unite_Type ==11)
                {
                    if($Avion1_Nbr)$Avion1_infos=$Avion1_bombs_nbr."x ".$Avion1_bombs."kg";
                    if($Avion2_Nbr)$Avion2_infos=$Avion2_bombs_nbr."x ".$Avion2_bombs."kg";
                    if($Avion3_Nbr)$Avion3_infos=$Avion3_bombs_nbr."x ".$Avion3_bombs."kg";
                }*/
                /*if($Admin)
                    echo "<h1>".Afficher_Icone($Unite,$country).$Unite_Nom."</h1>".$menu_units_ia.$menu_mission."<h2><small><img src='images/base14.png' title='Base actuelle'> 
					<a href='javascript:void(0);' class='lien' onclick=\"window.parent.location.href='index.php?view=em_city_ground&id=".$Base."&mode=3';\" target='_top'>".$Base_Nom."</a> <img src='images/".$Base_Pays."20.gif' title='".GetPays($Base_Pays)."'></small></h2>";
                else
                    echo "<h1>".Afficher_Icone($Unite,$country).$Unite_Nom."</h1>".$menu_units_ia.$menu_mission."<h2><small><img src='images/base14.png' title='Base actuelle'> ".$Base_Nom." <img src='images/".$Base_Pays."20.gif' title='".GetPays($Base_Pays)."'></small></h2>";*/
                if($Rav_R1)$Rav_R1='<div class="panel-footer">'.$Rav_R1.'</div>';
                if($Rav_R2)$Rav_R2='<div class="panel-footer">'.$Rav_R2.'</div>';
                if($Rav_R3)$Rav_R3='<div class="panel-footer">'.$Rav_R3.'</div>';
                echo $menu_units_ia.$menu_mission.$Alert;
                echo '<div class="panel panel-war">
                        <div class="panel-heading">
                            <div class="row text-center">
                                <div class="col-xs-4"><a href="#" class="popup-light">'.Afficher_Icone($Unite,$country,$Unite_Nom).'<span>'.Afficher_Icone($Unite,$country,$Unite_Nom,1).'</span></a></div>
                                <div class="col-xs-4"><h2>'.$Unite_Nom.'</h2></div>
                                <div class="col-xs-4"><h3><small class="text-primary"><img src="images/base14.png" title="Base actuelle"> '.$Base_Nom.'</h3></small></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="panel panel-war">
                                        <div class="panel-heading">'.$Sqn.' 1</div>
                                        <div class="panel-body">'.$Avion1_Nbr.' '.GetAvionIcon($Avion1,$country,0,$Unite,$Front_unit,false,$Legend).$xp_avion1.'
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4">'.$But_F1.'</div>
                                                <div class="col-xs-12 col-sm-4">'.$But_A1.'</div>
                                                <div class="col-xs-12 col-sm-4">'.$But_R1.'</div>
                                            </div>                                           
                                        </div>
                                        '.$Rav_R1.'
                                    </div>                               
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="panel panel-war">
                                        <div class="panel-heading">'.$Sqn.' 2</div>
                                        <div class="panel-body">'.$Avion2_Nbr.' '.GetAvionIcon($Avion2,$country,0,$Unite,$Front_unit,false,$Legend).$xp_avion2.'
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4">'.$But_F2.'</div>
                                                <div class="col-xs-12 col-sm-4">'.$But_A2.'</div>
                                                <div class="col-xs-12 col-sm-4">'.$But_R2.'</div>
                                            </div>
                                        </div>
                                        '.$Rav_R2.'
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="panel panel-war">
                                        <div class="panel-heading">'.$Sqn.' 3</div>
                                        <div class="panel-body">'.$Avion3_Nbr.' '.GetAvionIcon($Avion3,$country,0,$Unite,$Front_unit,false,$Legend).$xp_avion3.'
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4">'.$But_F3.'</div>
                                                <div class="col-xs-12 col-sm-4">'.$But_A3.'</div>
                                                <div class="col-xs-12 col-sm-4">'.$But_R3.'</div>
                                            </div>
                                        </div>
                                        '.$Rav_R3.'
                                    </div>
                                </div>
                            </div>
                      ';
                if($Credits >=2)
                {
                    if(!$porte_avions)
                        $gestion_txt.="<form action='index.php?view=em_gestioncdt2' method='post'><input type='hidden' name='unitet' value='".$Unite."'><input type='hidden' name='hydra' value='".$Hydravion."'>
						<div class='i-flex'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a><input type='submit' value='Déménager' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form>";
                    if($GHQ and ($Unite_Type==10 or $Unite_Type==12))
                        $gestion_txt.=$PA_Button;
                }
                if(!$GHQ)
                {
                    if($GHQ_ask)
                    {
                        $Unit_Level=min($Avion1_rating,$Avion2_rating,$Avion3_rating);
                        $con=dbconnecti();
                        $Dem_upgrade=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Avion WHERE Pays='$country' AND Etat=1 AND Prototype=0 AND Premium=0 AND Type='$Unite_Type' AND Rating >'$Unit_Level'"),0);
                        mysqli_close($con);
                        if(!$Avion1_Nbr or !$Avion2_Nbr or !$Avion3_Nbr or (($Avion1_Nbr + $Avion2_Nbr + $Avion3_Nbr) <$MaxFlight and $Avion1_Nbr <$MaxFlight and $Avion2_Nbr <$MaxFlight and $Avion3_Nbr <$MaxFlight))
                            $gestion_txt.="<form action='em_ia4.php' method='post'><input type='hidden' name='mode' value='9'><input type='hidden' name='Unite' value='".$Unite."'>
							<a href='#' class='popup'><img src='images/help.png'><span>Demander au planificateur de remplacer les avions perdus de cette unité. Un aller-retour vers le site de production est à prévoir.</span></a><input type='Submit' value='Demande de renforts' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                        if($Dem_upgrade)
                            $gestion_txt.="<form action='em_ia4.php' method='post'><input type='hidden' name='mode' value='8'><input type='hidden' name='Unite' value='".$Unite."'>
							<a href='#' class='popup'><img src='images/help.png'><span>Demander au planificateur de remplacer les avions actuels de cette unité par des modèles plus récents. Un aller-retour vers le site de production est à prévoir.</span></a><input type='Submit' value='Demande de mise à niveau' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                }
                elseif($GHQ)
                {
                    if($NoEM)
                    {
                        $Mode_res_GHQ=12;
                        $Mode_res_GHQ_txt="Libérer EM";
                    }
                    else
                    {
                        $Mode_res_GHQ=11;
                        $Mode_res_GHQ_txt="Réservé GHQ";
                    }
                    if($Unite_Ravit)
                        $gestion_txt.="<form action='em_ia4.php' method='post'><input type='hidden' name='mode' value='7'><input type='hidden' name='Unite' value='".$Unite."'>
					    <a href='#' class='popup'><img src='images/help.png'><span>Signaler au commandant de front que la demande a été traitée</span></a><input type='submit' value='Demande effectuée' class='btn btn-sm btn-warning' onclick='this.disabled=true;this.form.submit();'></form>";
                    $gestion_txt.="<form action='em_ia4.php' method='post'><input type='hidden' name='mode' value='".$Mode_res_GHQ."'><input type='hidden' name='Unite' value='".$Unite."'>
					<a href='#' class='popup'><img src='images/help.png'><span>Réserver cette unité pour le GHQ</span></a><input type='Submit' value='".$Mode_res_GHQ_txt."' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
                }
                if(!$Off_EM_Sub and !$Cdt)
                {
                    if($Unit_Armee)
                    {
                        $gestion_txt.="<form action='em_ia4.php' method='post'><input type='hidden' name='mode' value='13'><input type='hidden' name='Armee' value='0'><input type='hidden' name='Unite' value='".$Unite."'>
						<a href='#' class='popup'><img src='images/help.png'><span>Transférer le contrôle du commandant d'armée au commandant de front</span></a><input type='Submit' value='Assigner au front' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
                    elseif(($OfficierEMID ==$Commandant or $Admin) and ($Unite_Type ==1 or $Unite_Type ==3 or $Unite_Type ==7))
                    {
                        $con=dbconnecti();
                        $res_armees=mysqli_query($con,"SELECT ID,Nom FROM Armee WHERE Pays='$country' AND Front='$Front_unit' AND Active=1 AND Cdt >0");
                        mysqli_close($con);
                        if($res_armees)
                        {
                            while($dataar=mysqli_fetch_array($res_armees,MYSQLI_ASSOC))
                            {
                                $Armees_txt.="<option value='".$dataar['ID']."'>".$dataar['Nom']."</option>";
                            }
                            mysqli_free_result($res_armees);
                        }
                        unset($dataar);
                        if($Armees_txt)
                        {
                            $gestion_txt.="<form action='em_ia4.php' method='post'><input type='hidden' name='mode' value='13'><input type='hidden' name='Unite' value='".$Unite."'>
							<select name='Armee' class='form-control' style='width: 200px'>".$Armees_txt."</select>
							<a href='#' class='popup'><img src='images/help.png'><span>Transférer le contrôle au commandant d'armée</span></a><input type='submit' value='Assigner à une Armée' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
                        }
                    }
                }
                if(!$GHQ or $Admin or $Nation_IA)
                {
                    if(!$Pilotes_max)
                        $pilotes_txt="<p class='lead'><img src='images/obs.png' style='width:5%;'> Créez une mission pour activer l'unité</p>";
                    else{
                        if($Credits >=$CT_Restore and !$Mission_IA and ($Pilotes <$Pilotes_max or $Pilotes_fatigues >0))
                            $remonter_moral_txt="<form action='em_ia1.php' method='post'><input type='hidden' name='reset' value='5'><input type='hidden' name='Unite' value='".$Unite."'>
                                <div class='i-flex'><img src='images/CT".$CT_Restore.".png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a><input type='submit' value='Remonter le moral' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'><a href='#' class='popup'><img src='images/help.png'><span>Cette action ramène les pilotes à la base, attention si cette unité a une mission en cours!</span></a></div></form>";
                        else
                            $remonter_moral_txt="<img src='images/CT".$CT_Restore.".png' title='Montant en Crédits Temps que nécessite cette action'><i>Remonter le moral</i> <a href='#' class='popup'><img src='images/help.png'><span>Cette action requiert l'action du jour de l'unité.</span></a>";
                        if(!$Mission_IA)
                            $rappel_txt="<form action='em_ia1.php' method='post'><input type='hidden' name='reset' value='1'><input type='hidden' name='Unite' value='".$Unite."'><input type='Submit' class='btn btn-sm btn-warning' value='Rappeler à la base'></form>";
                        $pilotes_txt='<div class="panel panel-war">
                                 <div class="panel-heading">Pilotes</div>
                                 <div class="panel-body">
                                    <img src="images/obs.png" style="max-width:50px;">
                                    <a href="#" class="popup"><b>'.$Pilotes.'</b>/'.$Pilotes_max.' pilotes en état de vol<span>Les unités commandées par des pilotes joueurs peuvent remonter le moral,le courage et la fatigue de leurs pilotes. Vous pouvez également le faire si vous disposez de '.$CT_Restore.' CT.</span></a>
                                    <div class="row">
                                    <div class="col-xs-12 col-sm-6"><form action="em_ia_pil.php" method="post"><input type="hidden" name="Unite" value="'.$Unite.'"><br><input type="submit" value="Détail" class="btn btn-sm btn-primary" onclick="this.disabled=true;this.form.submit();"></form></div>
                                    <div class="col-xs-12 col-sm-6" style="padding-top:15px;">'.$rappel_txt.'</div>
                                    </div>
                                    <hr>
                                    '.$remonter_moral_txt.'
                                 </div>
                                 <div class="panel-footer"><h4><small>Compétences d\'escadrille</small></h4>'.$skill_txt.'</div>
                              </div>';
                    }
                    if($Garnison_Esc <50)
                    {
                        if($Flag_Air ==$country)
                        {
                            $con=dbconnecti();
                            //$Enis2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Base' AND r.Placement=1 AND r.Vehicule_Nbr >0"),0);
                            $Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as i,Pays as y WHERE i.Pays=y.ID AND y.Faction<>'$Faction' AND i.Lieu_ID='$Base' AND i.Placement=1 AND i.Vehicule_Nbr >0"),0);
                            mysqli_close($con);
                            $Enis+=$Enis2;
                            if(!$Enis)
                            {
                                $garnison_txt="<form action='em_ia1.php' method='post'><input type='hidden' name='reset' value='4'><input type='hidden' name='Unite' value='".$Unite."'>
								<br><img src='images/CT".$CT_Restore.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Remonter le moral des troupes' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'><a href='#' class='popup'><img src='images/help.png'><span>Les effectifs ne peuvent excéder 50 hommes</span></a></form>";
                            }
                            else
                                $garnison_txt="<div class='alert alert-danger'>L'aérodrome est sous le feu des troupes ennemies!</div>";
                        }
                        else
                            $garnison_txt="<img src='images/CT".$CT_Restore.".png' title='Montant en Crédits Temps que nécessite cette action'><i>Remonter le moral des troupes</i><a href='#' class='popup'><img src='images/help.png'><span>L'aérodrome doit être contrôlé par votre nation</span></a>";
                    }
                    //Missions
                    $choix17="";
                    $choix15="";
                    $choix7="";
                    switch($Unite_Type)
                    {
                        case 1:
                            $choix4="<option value='4'>Escorte</option>";
                            $choix7="<option value='7'>Patrouille défensive</option>";
                            if($Avion1_rec or $Avion2_rec or $Avion3_rec)$choix5="<option value='5'>Reconnaissance tactique</option>";
                            $Mis_list="4,7";
                            break;
                        case 2:
                            $choix8="<option value='8'>Bombardement stratégique de jour</option><option value='16'>Bombardement stratégique de nuit</option>";
                            if($Avion1_rec or $Avion2_rec or $Avion3_rec)$choix5="<option value='5'>Reconnaissance tactique</option>";
                            $Mis_list="8,16";
                            break;
                        case 3:
                            $choix15="<option value='15'>Reconnaissance stratégique</option>";
                            $choix5="<option value='5'>Reconnaissance tactique</option>";
                            $choix32="<option value='32'>Veille</option>";
                            $Mis_list="5,15";
                            break;
                        case 4:
                            //$choix4="<option value='4'>Escorte</option>";
                            $choix17="<option value='17'>Chasse de nuit</option>";
                            $choix7="<option value='7'>Patrouille défensive</option>";
                            if($Avion1_rec or $Avion2_rec or $Avion3_rec)$choix5="<option value='5'>Reconnaissance tactique</option>";
                            $Mis_list="7,17";
                            break;
                        case 6:
                            $choix15="<option value='23'>Ravitaillement</option>";
                            $choix24="<option value='24'>Parachutage de jour</option><option value='25'>Parachutage de nuit</option>";
                            $Mis_list="23,24,25";
                            break;
                        case 7:
                            $choix8="<option value='8'>Bombardement stratégique de jour</option>";
                            $choix32="<option value='32'>Veille</option>";
                            if($Avion1_rec or $Avion2_rec or $Avion3_rec)$choix5="<option value='5'>Reconnaissance tactique</option>";
                            $Mis_list="8";
                            break;
                        case 9:
                            $choix5="<option value='5'>Reconnaissance tactique</option>";
                            $choix32="<option value='32'>Veille</option>";
                            $Mis_list="5";
                            break;
                        case 10:
                            $choix15="<option value='15'>Reconnaissance stratégique</option>";
                            $choix5="<option value='5'>Reconnaissance tactique</option>";
                            $choix32="<option value='32'>Veille</option>";
                            $Mis_list="5,15";
                            break;
                        case 12:
                            $choix4="<option value='4'>Escorte</option>";
                            $choix7="<option value='7'>Patrouille défensive</option>";
                            if($Avion1_rec or $Avion2_rec or $Avion3_rec)$choix5="<option value='5'>Reconnaissance tactique</option>";
                            $Mis_list="4,7";
                            break;
                    }
                    if($Mission_Lieu)
                        $Mission_Lieu=GetData("Lieu","ID",$Mission_Lieu,"Nom");
                    else
                        $Mission_Lieu="<i>Aucun</i>";
                    if($Mission_Type)
                    {
                        $Mission_Type=GetMissionType($Mission_Type);
                        $Annulem_txt="<input type='submit' class='btn btn-sm btn-warning' value='Annuler'>";
                    }
                    else
                        $Mission_Type='<i>Aucune</i>';
                    $Mission_txt="<div class='panel panel-war'><div class='panel-heading'>Mission d'unité en cours</div><div class='panel-body'><form action='em_ia1.php' method='post'><input type='hidden' name='reset' value='1'><input type='hidden' name='Unite' value='".$Unite."'>
                    <table class='table'><thead><tr><th>Type de mission</th><th>Objectif</th><th>Altitude</th><th>".$Sqn."</th><th>Action</th></tr></thead>
                    <tr><td>".$Mission_Type."</td><td>".$Mission_Lieu."</td><td>".$Mission_alt."m</td><td>".$Mission_Flight."</td><td>".$Annulem_txt."</td></tr>
                    </table></form></div></div>";
                    //Output
                    if($Embark_txt){
                        $Embark_txt='<div class="panel-footer">'.$Embark_txt.'</div>';
                    }
                    echo '<div class="row">
                            <div class="col-xs-12 col-md-5 col-lg-4">
                                <div class="panel panel-war">
                                    <div class="panel-heading">Gestion</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6">'.$gestion_txt.'</div>
                                            <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6">                                
                                                <div class="panel panel-war">
                                                    <div class="panel-heading">Troupes de défense</div>
                                                    <div class="panel-body"><a href="#" class="popup"><img src="images/vehicules/vehicule111.gif"> '.$Garnison_Esc.' hommes<span>Ces troupes défendront les avions contre les attaques terrestres</span></a></div>
                                                    '.$garnison_txt.'
                                                </div>
                                            </div>
                                        </div>                                    
                                    </div>
                                    '.$Embark_txt.'
                                </div>                            
                            </div>
                            <div class="col-xs-12 col-md-7 col-lg-4">
                            '.$pilotes_txt.'                            
                            </div>
                            <div class="col-xs-12 col-md-12 col-lg-4">
                            '.$Mission_txt.'
                            </div>
                          </div>
                     </div></div>'; //End Panel Unit
                }
                echo "<div class='panel panel-war'><div class='panel-heading'>Missions</div><div class='panel-body'>";
                if(!$GHQ or $Admin or $Nation_IA) //Missions
                {
                    if($Porte_avions >0)
                    {
                        $HP_max_PA=GetData("Cible","ID",$Porte_avions,"HP");
                        $HP_PA=GetData("Regiment_IA","Vehicule_ID",$Porte_avions,"HP");
                        $QualitePiste=round(($HP_PA/$HP_max_PA)*100);
                        $Faction_Flag=$Faction;
                        $Faction_Air=$Faction;
                    }
                    $today=getdate();
                    if($Credits <1)
                        echo "<h6>Vous ne disposez pas de suffisamment de Crédits Temps pour assigner une mission à votre unité !<h6>";
                    elseif(($data['Jour_m'] ==$today['mday'] and $today['mon'] ==$data['Mois_m']) or ($today['hours'] >1 and $today['hours'] <7))
                        echo "<div class='alert alert-danger'>L'unité est partie en mission trop récemment!</div>";
                    elseif(!$Hydravion and !$BaseAerienne and !$Porte_avions)
                        echo "<div class='alert alert-danger'>Aucun aérodrome n'est disponible à cet endroit. Pour pouvoir utiliser cette unité, vous devez la déplacer ou remplacer <b>tous</b> les avions par des hydravions!</div>";
                    elseif($QualitePiste >50 and (($Avion1_Nbr+$Avion2_Nbr+$Avion3_Nbr) >0) and $Faction ==$Faction_Flag and $Faction ==$Faction_Air and $Meteo >-50 and !$Mission_IA and $Pilotes >0)
                    {
                        if(($Unite_Type ==2 or $Unite_Type ==6 or $Unite_Type ==9 or $Unite_Type ==11) and $Meteo <-19 and $BaseAerienne >2 and !$Hydravion)
                            echo "<div class='alert alert-danger'><img src='images/meteo".$Meteo.".gif'> Le décollage par temps de pluie n'est possible que sur une piste en dur!</div>";
                        else
                        {
                            $Usines_villes=array($Avion3_u1,$Avion3_u2,$Avion3_u3,$Avion2_u1,$Avion2_u2,$Avion2_u3,$Avion1_u1,$Avion1_u2,$Avion1_u3);
                            if($Base >0 and in_array($Base,$Usines_villes))$Only_Here=true;
                            $Plafonds=array($Avion1_p,$Avion2_p,$Avion3_p);
                            $Autonomies=array($Avion1_a,$Avion2_a,$Avion3_a);
                            $Autonomies_strat=array($Avion1_a_s,$Avion2_a_s,$Avion3_a_s);
                            $Autonomies_tac=array($Avion1_a_t,$Avion2_a_t,$Avion3_a_t);
                            $Autonomies_long=array($Avion1_a_l,$Avion2_a_l,$Avion3_a_l);
                            $Plafonds_long=array($Avion1_p_l,$Avion2_p_l,$Avion3_p_l);
                            $Plafond_max=min(array_filter($Plafonds));
                            $Autonomie_max=min(array_filter($Autonomies));
                            $Plafond_long_max=min(array_filter($Plafonds_long));
                            $Autonomie_strat_max=min(array_filter($Autonomies_strat));
                            $Autonomie_tac_max=min(array_filter($Autonomies_tac));
                            $Autonomie_long_max=min(array_filter($Autonomies_long));
                            if($Autonomie_strat_max <50)$Autonomie_strat_max=50;
                            if($Autonomie_tac_max <50)$Autonomie_tac_max=50;
                            if(!$Porte_avions and !$Nation_IA)
                            {
                                $Lat_min=$Latitude_base-2;
                                $Lat_max=$Latitude_base+2;
                                $Long_min=$Longitude_base-5;
                                $Long_max=$Longitude_base+5;
                                $Stock_87_max=0;
                                $Stock_100_max=0;
                                $Stock_1_max=0;
                                $Auto_Log=GetAutoLog($Front_unit,true);
                                $con=dbconnecti();
                                $resultdepot=mysqli_query($con,"SELECT DISTINCT l.Longitude,l.Latitude,l.Stock_Essence_87,l.Stock_Essence_100,l.Stock_Essence_1
								FROM Lieu as l,Pays as p WHERE l.ValeurStrat >3 AND (l.NoeudF_Ori=100 OR l.Port_Ori=100) AND l.Flag=p.Pays_ID AND p.Faction='$Faction' AND
								(l.ID='$Base' OR ((l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max')))
								UNION SELECT DISTINCT l.Longitude,l.Latitude,d.Stock_Essence_87,d.Stock_Essence_100,d.Stock_Essence_1 FROM Depots as d,Regiment_IA as r,Lieu as l,Pays as p
								WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Base' AND l.ID=r.Lieu_ID AND r.Placement=8 AND r.Vehicule_ID=5392 AND r.Vehicule_Nbr >0");
                                mysqli_close($con);
                                $con=dbconnecti(1);
                                $Carbu1=mysqli_result(mysqli_query($con,"SELECT Carburant FROM Moteur WHERE ID='$Avion1_mot'"),0);
                                $Carbu2=mysqli_result(mysqli_query($con,"SELECT Carburant FROM Moteur WHERE ID='$Avion2_mot'"),0);
                                $Carbu3=mysqli_result(mysqli_query($con,"SELECT Carburant FROM Moteur WHERE ID='$Avion3_mot'"),0);
                                mysqli_close($con);
                                if($resultdepot)
                                {
                                    while($datad=mysqli_fetch_array($resultdepot,MYSQLI_ASSOC))
                                    {
                                        $Dist_depot=GetDistance(0,0,$Longitude_base,$Latitude_base,$datad['Longitude'],$datad['Latitude']);
                                        if($Dist_depot[0] <=$Auto_Log)
                                        {
                                            $Stock_87[]=$datad['Stock_Essence_87'];
                                            $Stock_100[]=$datad['Stock_Essence_100'];
                                            $Stock_1[]=$datad['Stock_Essence_1'];
                                        }
                                    }
                                    mysqli_free_result($resultdepot);
                                }
                                $Stock1=($Avion1_en*$Avion1_a_l*$Avion1_Nbr);///10;
                                $Stock2=($Avion2_en*$Avion2_a_l*$Avion2_Nbr);///10;
                                $Stock3=($Avion3_en*$Avion3_a_l*$Avion3_Nbr);///10;
                                $Stock_87_max=Array_max($Stock_87);
                                $Stock_100_max=Array_max($Stock_100);
                                $Stock_1_max=Array_max($Stock_1);
                                if($Carbu1 ==100)
                                {
                                    $Octane1=" Octane 100";
                                    $Colorc1="danger";
                                    $Stock_Avion1=$Stock_100_max;
                                }
                                elseif($Carbu1 ==1)
                                {
                                    $Octane1=" Diesel";
                                    $Colorc1="success";
                                    $Stock_Avion1=$Stock_1_max;
                                }
                                else
                                {
                                    $Octane1=" Octane 87";
                                    $Colorc1="primary";
                                    $Stock_Avion1=$Stock_87_max;
                                }
                                if($Carbu2 ==100)
                                {
                                    $Octane2=" Octane 100";
                                    $Colorc2="danger";
                                    $Stock_Avion2=$Stock_100_max;
                                }
                                elseif($Carbu2 ==1)
                                {
                                    $Octane2=" Diesel";
                                    $Colorc2="success";
                                    $Stock_Avion2=$Stock_1_max;
                                }
                                else
                                {
                                    $Octane2=" Octane 87";
                                    $Colorc2="primary";
                                    $Stock_Avion2=$Stock_87_max;
                                }
                                if($Carbu3 ==100)
                                {
                                    $Octane3=" Octane 100";
                                    $Colorc3="danger";
                                    $Stock_Avion3=$Stock_100_max;
                                }
                                elseif($Carbu3 ==1)
                                {
                                    $Octane3=" Diesel";
                                    $Colorc3="success";
                                    $Stock_Avion3=$Stock_1_max;
                                }
                                else
                                {
                                    $Octane3=" Octane 87";
                                    $Colorc3="primary";
                                    $Stock_Avion3=$Stock_87_max;
                                }
                                /*$Depot_Log_txt="<span class='label label-primary'>".$Stock_87_max."L Octane 87</span> <span class='label label-danger'>".$Stock_100_max."L Octane 100</span> <span class='label label-success'>".$Stock_1_max."L Diesel</span>";
                                echo "<fieldset><h3>Stock de carburant</h3>".$Depot_Log_txt."
								<table class='table'><thead><tr><th>Requis ".$Sqn." 1 (".$Avion1_nom.")</th><th>Requis ".$Sqn." 2 (".$Avion2_nom.")</th><th>Requis ".$Sqn." 3 (".$Avion3_nom.")</th></tr></thead>
								<tr><td><span class='label label-".$Colorc1."'>".$Stock1."L".$Octane1."</span></td><td><span class='label label-".$Colorc2."'>".$Stock2."L".$Octane2."</span></td><td><span class='label label-".$Colorc3."'>".$Stock3."L".$Octane3."</span></td></tr></table>";
                                echo "<a href='carte_ground.php?map=".$Front_unit."&mode=13&cible=".$Base."' class='btn btn-sm btn-primary' onclick='window.open(this.href); return false;'>Voir la carte logistique</a></fieldset>";*/
                                echo "<div class='panel-warning'>
                                    <div class='panel-heading'>Carburant</div>
                                    <div class='panel-body'>
                                        <table class='table table-striped'>
                                        <thead><tr>
                                            <th>".$Sqn."</th>
                                            <th>Carbu</th>
                                            <th>Requis</th>
                                            <th>Stock</th>
                                        </tr></thead>
                                        <tr>
                                            <td>1</td>
                                            <td>".$Octane1."</td>
                                            <td>".$Stock1."</td>
                                            <td>".$Stock_Avion1."</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>".$Octane2."</td>
                                            <td>".$Stock2."</td>
                                            <td>".$Stock_Avion2."</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>".$Octane3."</td>
                                            <td>".$Stock3."</td>
                                            <td>".$Stock_Avion3."</td>
                                        </tr>
                                        </table>
                                    </div>
                                    <div class='panel-footer'><a href='carte_ground.php?map=".$Front_unit."&mode=13&cible=".$Base."' class='btn btn-sm btn-primary' onclick='window.open(this.href); return false;'>Voir la carte logistique</a></div>
                                </div>";
                            }
                            elseif($Porte_avions)
                                echo "<div class='alert alert-info'><span class='label label-primary'>".$Autonomie_PA." Jours de ravitaillement</span><a href='help/aide_jours.php' target='_blank' title='Cliquez pour aide'><img src='images/help.png'></a><p>Une mission consomme 1 jour de ravitaillement du porte-avions (2 pour une mission longue portée)</p></div>";
                            if($Avion1_Nbr >0 and $Stock_Avion1 >=$Stock1)
                                $Flight_txt="<option value='1' selected>1</option>";
                            if($Avion2_Nbr >0 and $Stock_Avion2 >=$Stock2)
                                $Flight_txt.="<option value='2'>2</option>";
                            if($Avion3_Nbr >0 and $Stock_Avion3 >=$Stock3)
                                $Flight_txt.="<option value='3'>3</option>";
                            $Coord=GetCoord($Front_unit,$country);
                            $Lat_base_min=$Coord[0];
                            $Lat_base_max=$Coord[1];
                            $Long_base_min=$Coord[2];
                            $Long_base_max=$Coord[3];
                            if($Latitude_base <47 and $Longitude_base >7){
                                $Lat_base_max=46.5;
                            }
                            elseif($Latitude_base >47 and $Longitude_base >7){
                                $Lat_base_min=46.5;
                            }
                            if($Flight_txt and !$Canada)
                            {
                                if($G_Treve or ($G_Treve_Med and $Front ==2))$query_treve=" AND Flag='$country'";
                                if($Unite_Type ==6 and $choix15)
                                {
                                    if($Only_Here or $G_Treve or ($G_Treve_Med and $Front ==2))
                                        $Lieuxo.="<option value=".$Base.">".$Base_Nom." (10km)</option>";
                                    else{
                                        $con=dbconnecti();
                                        $Paras_res=mysqli_query($con,"SELECT ID,Position FROM Regiment_IA WHERE Pays='$country' AND Placement=1 AND Position IN(12,13)");
                                        mysqli_close($con);
                                        if($Paras_res)
                                        {
                                            while($datap=mysqli_fetch_array($Paras_res,MYSQLI_ASSOC))
                                            {
                                                if($datap['Position'] ==12)
                                                    $Paras_units.="<option value=".$datap['ID'].">".$datap['ID']."e Cie</option>";
                                                elseif($datap['Position'] ==13)
                                                    $Cdo_units.="<option value=".$datap['ID'].">".$datap['ID']."e Cie</option>";
                                            }
                                            mysqli_free_result($Paras_res);
                                        }
                                        $Reg_Atr=false;
                                        $Axe=array(1,6,9,15,18,19,20,24);
                                        $Allies=array(2,3,4,5,7,8,10,35,36);
                                        $query="SELECT r.ID as Reg,r.Pays as Pays_Reg,r.Division,r.Lieu_ID,r.Placement,r.Ravit,p.Faction,l.Longitude,l.Latitude,l.Flag,l.Flag_Air,l.Flag_Route,l.Flag_Gare,l.Flag_Pont,l.Flag_Port,l.Flag_Usine,l.Flag_Radar,l.Flag_Plage,l.Nom as Lieu_Nom
                                        FROM Regiment_IA as r,Lieu as l,Cible as c,Pays as p WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.Pays=p.ID
                                        AND r.Pays='$country' AND r.Front='$Front' AND c.mobile NOT IN(4,5) AND l.Flag<>r.Pays AND r.Placement<>8 AND r.Vehicule_Nbr>0 ORDER BY l.Nom ASC";
                                        $con=dbconnecti();
                                        $resultat=mysqli_query($con,$query);
                                        mysqli_close($con);
                                        if($resultat)
                                        {
                                            while($dataat=mysqli_fetch_array($resultat,MYSQLI_ASSOC))
                                            {
                                                if($dataat['Placement'] ==1)
                                                    $Flag_Zone=$dataat['Flag_Air'];
                                                elseif($dataat['Placement'] ==2)
                                                    $Flag_Zone=$dataat['Flag_Route'];
                                                elseif($dataat['Placement'] ==3)
                                                    $Flag_Zone=$dataat['Flag_Gare'];
                                                elseif($dataat['Placement'] ==4)
                                                    $Flag_Zone=$dataat['Flag_Port'];
                                                elseif($dataat['Placement'] ==5)
                                                    $Flag_Zone=$dataat['Flag_Pont'];
                                                elseif($dataat['Placement'] ==6)
                                                    $Flag_Zone=$dataat['Flag_Usine'];
                                                elseif($dataat['Placement'] ==7)
                                                    $Flag_Zone=$dataat['Flag_Radar'];
                                                elseif($dataat['Placement'] ==11)
                                                    $Flag_Zone=$dataat['Flag_Plage'];
                                                else
                                                    $Flag_Zone=$dataat['Flag'];
                                                if($dataat['Ravit'])
                                                    $Attrition=false;
                                                elseif(($dataat['Faction'] ==1 and in_array($Flag_Zone,$Axe)) or ($dataat['Faction'] ==2 and in_array($Flag_Zone,$Allies)))
                                                    $Attrition=false;
                                                else{
                                                    $add_link = true;
                                                    if(is_array($Reg_Atr)){
                                                        if(in_array($dataat['Lieu_ID'],$Reg_Atr)){
                                                            $add_link = false;
                                                        }
                                                    }
                                                    if($add_link){
                                                        $Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$dataat['Longitude'],$dataat['Latitude']);
                                                        if($Dist[0] <=$Autonomie_max)
                                                            $Lieuxo.="<option value='".$dataat['Lieu_ID']."'>".$dataat['Lieu_Nom']." (".$Dist[0]."km)</option>";
                                                        $Reg_Atr[]=$dataat['Lieu_ID'];
                                                    }
                                                }
                                            }
                                            mysqli_free_result($resultat);
                                            unset($dataat);
                                        }
                                        $select_alt=false;
                                        if($Plafond_max >=4000)
                                            $select_alt.="<option value='4000' selected>Altitude moyenne (4000m)</option>";
                                        if($Plafond_max >=5000)
                                            $select_alt.="<option value='5000'>Altitude moyenne (5000m)</option>";
                                        if($Plafond_max >=6000)
                                            $select_alt.="<option value='6000'>Altitude moyenne (6000m)</option>";
                                        if($Plafond_max >=7000)
                                            $select_alt.="<option value='7000'>Haute altitude (7000m)</option>";
                                        if($Plafond_max >=8000)
                                            $select_alt.="<option value='8000'>Haute altitude (8000m)</option>";
                                        if($Plafond_max >=9000)
                                            $select_alt.="<option value='9000'>Haute altitude (9000m)</option>";
                                        if($Plafond_max >=10000)
                                            $select_alt.="<option value='10000'>Haute altitude (10000m)</option>";
                                        if($Lieuxo)
                                        {
                                            $Strat_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Ravitaillement</div><div class='panel-body'><span class='label label-warning'>Rayon d'action ".$Autonomie_max."km</span>
											<form action='em_ia1.php' method='post'>
											<input type='hidden' name='Unite' value='".$Unite."'>
											<input type='hidden' name='Avion1' value='".$Avion1."'>
											<input type='hidden' name='Avion2' value='".$Avion2."'>
											<input type='hidden' name='Avion3' value='".$Avion3."'>
											<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
											<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
											<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Cible'>Choix de la Cible</label>
                                                    <select name='Cible' class='form-control'>".$Lieuxo."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Type'>Choix du Type de Mission</label>
                                                    <select name='Type' class='form-control'>".$choix15."</select>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-8'>
                                                    <label for='Altitude'>Choix de l'altitude de Mission</label>
                                                    <select name='Altitude' class='form-control'>														
                                                    <option value='100'>Basse altitude (100m)</option>
                                                    <option value='500'>Basse altitude (500m)</option>
                                                    <option value='1000'>Basse altitude (1000m)</option>
                                                    <option value='2000'>Altitude moyenne (2000m)</option>
                                                    <option value='3000'>Altitude moyenne (3000m)</option>".$select_alt."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-4'>
                                                    <label for='Flight'>Choix du ".$Sqn."</label>
                                                    <select name='Flight' class='form-control'>".$Flight_txt."</select>
                                                </div>
                                            </div>
											<div class='i-flex mt-2'><img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
											<input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></form></div></div></div>";
                                        }
                                        if($Paras_units)
                                        {
                                            $Strat_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Parachutage</div><div class='panel-body'><span class='label label-warning'>Rayon d'action ".$Autonomie_max."km</span>
                                                <form action='em_ia1.php' method='post'>
                                                <input type='hidden' name='Unite' value='".$Unite."'>
                                                <input type='hidden' name='Avion1' value='".$Avion1."'>
                                                <input type='hidden' name='Avion2' value='".$Avion2."'>
                                                <input type='hidden' name='Avion3' value='".$Avion3."'>
                                                <input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
                                                <input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
                                                <input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
                                                <input type='hidden' name='Type' value='24'>
                                               <div class='row'>
                                                    <div class='col-xs-12 col-sm-6'>
                                                        <label for='Cible'>Choix de la Cible</label>
                                                        <select name='Cible' class='form-control'>".$Lieuxo."</select>
                                                    </div>
                                                    <div class='col-xs-12 col-sm-6'>
                                                        <label for='Paras'>Choix de la Compagnie</label>
                                                        <select name='Paras' class='form-control'>".$Paras_units."</select>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-xs-12 col-sm-8'>
                                                        <label for='Altitude'>Choix de l'altitude de Mission</label>
                                                        <select name='Altitude' class='form-control'>														
                                                        <option value='100'>Basse altitude (100m)</option>
                                                        <option value='500'>Basse altitude (500m)</option>
                                                        <option value='1000'>Basse altitude (1000m)</option>
                                                        <option value='2000'>Altitude moyenne (2000m)</option>
                                                        <option value='3000'>Altitude moyenne (3000m)</option>".$select_alt."</select>
                                                    </div>
                                                    <div class='col-xs-12 col-sm-4'>
                                                        <label for='Flight'>Choix du ".$Sqn."</label>
                                                        <select name='Flight' class='form-control'>".$Flight_txt."</select>
                                                    </div>
                                                </div>
                                                <div class='i-flex mt-2'><img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
                                                <input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form></div></div>";
                                        }
                                        if($Cdo_units)
                                        {
                                            $Strat_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Commando</div><div class='panel-body'><span class='label label-warning'>Rayon d'action ".$Autonomie_max."km</span>
                                                <form action='em_ia1.php' method='post'>
                                                <input type='hidden' name='Unite' value='".$Unite."'>
                                                <input type='hidden' name='Avion1' value='".$Avion1."'>
                                                <input type='hidden' name='Avion2' value='".$Avion2."'>
                                                <input type='hidden' name='Avion3' value='".$Avion3."'>
                                                <input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
                                                <input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
                                                <input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
                                                <input type='hidden' name='Type' value='27'>
                                               <div class='row'>
                                                    <div class='col-xs-12 col-sm-6'>
                                                        <label for='Cible'>Choix de la Cible</label>
                                                        <select name='Cible' class='form-control'>".$Lieuxo."</select>
                                                    </div>
                                                    <div class='col-xs-12 col-sm-6'>
                                                        <label for='Paras'>Choix de la Compagnie</label>
                                                        <select name='Paras' class='form-control'>".$Cdo_units."</select>
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='col-xs-12 col-sm-8'>
                                                        <label for='Altitude'>Choix de l'altitude de Mission</label>
                                                        <select name='Altitude' class='form-control'>														
                                                        <option value='100'>Basse altitude (100m)</option>
                                                        <option value='500'>Basse altitude (500m)</option>
                                                        <option value='1000'>Basse altitude (1000m)</option>
                                                        <option value='2000'>Altitude moyenne (2000m)</option>
                                                        <option value='3000'>Altitude moyenne (3000m)</option>".$select_alt."</select>
                                                    </div>
                                                    <div class='col-xs-12 col-sm-4'>
                                                        <label for='Flight'>Choix du ".$Sqn."</label>
                                                        <select name='Flight' class='form-control'>".$Flight_txt."</select>
                                                    </div>
                                                </div>
                                                <div class='i-flex mt-2'><img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
                                                <input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form></div></div>";
                                        }
                                        $Missi_txt="Ravitaillement <a href='#' class='popup'><img src='images/help.png'><span>Mission permettant aux unités terrestres IA sur le lieu de destination de ne pas subir le malus d attrition</span></a>";
                                    }
                                }
                                elseif($Unite_Type ==3 and $choix15)
                                {
                                    $Lieuxo='';
                                    if($Only_Here or $G_Treve or ($G_Treve_Med and $Front ==2))
                                        $Lieuxo.="<option value=".$Base.">".$Base_Nom." (10km)</option>";
                                    else
                                    {
                                        $query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude>='$Lat_base_min' AND Latitude<='$Lat_base_max' AND Longitude>='$Long_base_min' AND Longitude<'$Long_base_max' AND Flag<>'$country' AND Zone<>6 ORDER BY Nom ASC";
                                        $con=dbconnecti();
                                        $result=mysqli_query($con,$query) or die(mysqli_error($con));
                                        mysqli_close($con);
                                        if($result)
                                        {
                                            while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                                            {
                                                $Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data['Longitude'],$data['Latitude']);
                                                if($data['ID']==$Base)$Dist[0]=10;
                                                if($Dist[0] <=$Autonomie_max)
                                                    $Lieuxo.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
                                                if($Dist[0] <=$Autonomie_long_max)
                                                    $Lieuxlongo.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
                                            }
                                            mysqli_free_result($result);
                                            unset($data);
                                        }
                                        else
                                            $mes.="Erreur d'import de données.";
                                    }
                                    if($Lieuxo)
                                    {
                                        $select_alt=false;
                                        if($Plafond_max >=4000)
                                            $select_alt.="<option value='4000' selected>Altitude moyenne (4000m)</option>";
                                        if($Plafond_max >=5000)
                                            $select_alt.="<option value='5000'>Altitude moyenne (5000m)</option>";
                                        if($Plafond_max >=6000)
                                            $select_alt.="<option value='6000'>Altitude moyenne (6000m)</option>";
                                        if($Plafond_max >=7000)
                                            $select_alt.="<option value='7000'>Haute altitude (7000m)</option>";
                                        if($Plafond_max >=8000)
                                            $select_alt.="<option value='8000'>Haute altitude (8000m)</option>";
                                        if($Plafond_max >=9000)
                                            $select_alt.="<option value='9000'>Haute altitude (9000m)</option>";
                                        if($Plafond_max >=10000)
                                            $select_alt.="<option value='10000'>Haute altitude (10000m)</option>";
                                        $Strat_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Mission Stratégique<span class='label label-primary'>Rayon d'action ".$Autonomie_max."km</span></div><div class='panel-body'>
											<form action='em_ia1.php' method='post'>
											<input type='hidden' name='Unite' value='".$Unite."'>
											<input type='hidden' name='Avion1' value='".$Avion1."'>
											<input type='hidden' name='Avion2' value='".$Avion2."'>
											<input type='hidden' name='Avion3' value='".$Avion3."'>
											<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
											<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
											<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
											<div class='row'>
											    <div class='col-xs-12 col-sm-6'>
											        <label for='Cible'>Choix de la Cible</label>
											        <select name='Cible' class='form-control'>".$Lieuxo."</select>
                                                </div>
											    <div class='col-xs-12 col-sm-6'>
											        <label for='Type'>Choix du Type de Mission</label>
											        <select name='Type' class='form-control'>".$choix15."</select>
                                                </div>
                                            </div>
                                            <div class='row'>
											    <div class='col-xs-12 col-sm-8'>
											        <label for='Altitude'>Choix de l'altitude de Mission</label>
											        <select name='Altitude' class='form-control'><option value='500'>Basse altitude (500m)</option>
                                                    <option value='1000'>Basse altitude (1000m)</option>
                                                    <option value='2000'>Altitude moyenne (2000m)</option>
                                                    <option value='3000'>Altitude moyenne (3000m)</option>'.$select_alt.'</select>
                                                </div>
											    <div class='col-xs-12 col-sm-4'>
											        <label for='Flight'>Choix du ".$Sqn."</label>
											        <select name='Flight' class='form-control'>".$Flight_txt."</select>
                                                </div>                                            
                                            </div>
                                            <div class='i-flex mt-2'><img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
											<input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form></div></div>";
                                    }
                                    if($Lieuxlongo and $Credits >=2)
                                    {
                                        $select_alt=false;
                                        if($Plafond_max >=4000)
                                            $select_alt.="<option value='4000' selected>Altitude moyenne (4000m)</option>";
                                        if($Plafond_max >=5000)
                                            $select_alt.="<option value='5000'>Altitude moyenne (5000m)</option>";
                                        if($Plafond_max >=6000)
                                            $select_alt.="<option value='6000'>Altitude moyenne (6000m)</option>";
                                        if($Plafond_max >=7000)
                                            $select_alt.="<option value='7000'>Haute altitude (7000m)</option>";
                                        if($Plafond_max >=8000)
                                            $select_alt.="<option value='8000'>Haute altitude (8000m)</option>";
                                        if($Plafond_max >=9000)
                                            $select_alt.="<option value='9000'>Haute altitude (9000m)</option>";
                                        if($Plafond_max >=10000)
                                            $select_alt.="<option value='10000'>Haute altitude (10000m)</option>";
                                        $Strat_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Longue distance<span class='label label-warning'>Rayon d'action ".$Autonomie_long_max."km</span></div><div class='panel-body'>
											<form action='em_ia1.php' method='post'>
											<input type='hidden' name='Unite' value='".$Unite."'>
											<input type='hidden' name='Avion1' value='".$Avion1."'>
											<input type='hidden' name='Avion2' value='".$Avion2."'>
											<input type='hidden' name='Avion3' value='".$Avion3."'>
											<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
											<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
											<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
											<input type='hidden' name='Long' value='1'>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Cible'>Choix de la Cible</label>
                                                    <select name='Cible' class='form-control'>".$Lieuxlongo."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Type'>Choix du Type de Mission</label>
                                                    <select name='Type' class='form-control'>".$choix15."</select>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-8'>
                                                    <label for='Altitude'>Choix de l'altitude de Mission</label>
                                                    <select name='Altitude' class='form-control'>														
                                                    <option value='100'>Basse altitude (100m)</option>
                                                    <option value='500'>Basse altitude (500m)</option>
                                                    <option value='1000'>Basse altitude (1000m)</option>
                                                    <option value='2000'>Altitude moyenne (2000m)</option>
                                                    <option value='3000'>Altitude moyenne (3000m)</option>".$select_alt."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-4'>
                                                    <label for='Flight'>Choix du ".$Sqn."</label>
                                                    <select name='Flight' class='form-control'>".$Flight_txt."</select>
                                                </div>
                                            </div>
                                            <div class='i-flex mt-2'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a><input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form>";
                                        $Strat_Output_txt.="<div class='alert alert-info'>Les missions longues distances permettent d'atteindre des objectifs éloignés grâce aux réservoirs supplémentaires. Les avions en mission longue distance sont cependant nettement moins performants au combat.</div></div></div>";
                                    }
                                }
                                elseif($Unite_Type ==2 or $Unite_Type ==7 or $Unite_Type ==11)
                                {
                                    $Lieuxo="";
                                    if($Only_Here or $G_Treve)
                                        $Lieuxo.="<option value=".$Base.">".$Base_Nom." (10km)</option>";
                                    else
                                    {
                                        $query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max' AND Flag<>'$country' AND Zone<>6 AND Recce >0 ORDER BY Nom ASC";
                                        $con=dbconnecti();
                                        $result=mysqli_query($con,$query) or die(mysqli_error($con));
                                        mysqli_close($con);
                                        if($result)
                                        {
                                            while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                                            {
                                                $Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data['Longitude'],$data['Latitude']);
                                                if($data['ID']==$Base)$Dist[0]=10;
                                                if($Dist[0] <=$Autonomie_strat_max)
                                                    $Lieuxo.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
                                                elseif($Admin and $Dist[0] <=$Autonomie_max)
                                                    $Lieuxo.="<option value=".$data['ID']." disabled>".$data['Nom']." (".$Dist[0]."km)</option>";
                                            }
                                            mysqli_free_result($result);
                                            unset($data);
                                        }
                                        else
                                            $mes.="Erreur d'import de données.";
                                    }
                                    if($Lieuxo)
                                    {
                                        $Strat_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Bombardement Stratégique<span class='label label-primary'>Rayon d'action ".$Autonomie_strat_max."km</span></div><div class='panel-body'>
											<form action='index.php?view=em_ia2' method='post'>
											<input type='hidden' name='Unite' value='".$Unite."'>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Cible'>Choix de la Cible</label>
                                                    <select name='Cible' class='form-control'>".$Lieuxo."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-4'>
                                                    <label for='Flight'>Choix du ".$Sqn."</label>
                                                    <select name='Flight' class='form-control'>".$Flight_txt."</select>
                                                </div>
                                            </div>
											<div class='i-flex mt-2'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
											<input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form></div></div>";
                                    }
                                    else
                                        $Strat_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Bombardement Stratégique</div><div class='panel-body'><span class='label label-warning'>Rayon d'action ".$Autonomie_strat_max."km</span><div class='alert alert-danger'>Aucune cible n'a été reconnue à portée de cette unité.<br>Une mission de reconnaissance stratégique doit être exécutée avec succès préalablement à tout bombardement stratégique.</div></div></div>";
                                }
                                if($Credits >=4 and (($Unite_Type ==2 or $Unite_Type ==7 or $Unite_Type ==10) or
                                        (($Unite_Type ==1 or $Unite_Type ==3 or $Unite_Type ==4 or $Unite_Type ==12) and ($Avion1_btac or $Avion2_btac or $Avion3_btac))))
                                {
                                    if($Unite_Type ==1 or $Unite_Type ==3 or $Unite_Type ==4 or $Unite_Type ==12)
                                    {
                                        if($Avion1_Nbr >0 and $Stock_Avion1 >=$Stock1 and $Avion1_btac)
                                            $Flight_txt_tac="<option value='1'>1</option>";
                                        if($Avion2_Nbr >0 and $Stock_Avion2 >=$Stock2 and $Avion2_btac)
                                            $Flight_txt_tac.="<option value='2'>2</option>";
                                        if($Avion3_Nbr >0 and $Stock_Avion3 >=$Stock3 and $Avion3_btac)
                                            $Flight_txt_tac.="<option value='3'>3</option>";
                                        $Aide_txt_tac="<a href='#' class='popup'><img src='images/help.png'><span>Cette mission annulera toute mission de patrouille ou d'escorte en cours</span></a>";
                                        $Aide_txt_btac="<div class='alert alert-info'>Cette catégorie d'unité ne peut effectuer de bombardement tactique que sur une seule zone.</div>";
                                    }
                                    else
                                        $Flight_txt_tac=$Flight_txt;
                                    $Lieuxt="";
                                    if($Flight_txt_tac and !$Only_Here)
                                    {
                                        $query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max'".$query_treve." ORDER BY Nom ASC";
                                        $con=dbconnecti();
                                        $result=mysqli_query($con,$query) or die(mysqli_error($con));
                                        if($result)
                                        {
                                            while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                                            {
                                                $Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data['Longitude'],$data['Latitude']);
                                                if($data['ID']==$Base)$Dist[0]=10;
                                                if($Dist[0] <=$Autonomie_tac_max)
                                                {
                                                    $result_unit=false;
                                                    $query_unit="SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='".$data['ID']."' AND r.Vehicule_Nbr >'0' AND p.Faction<>'".$Faction."' AND r.Visible='1' AND r.Bomb_IA='0'";
                                                    //(SELECT COUNT(*) FROM ((SELECT r.ID FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='".$data['ID']."' AND r.Vehicule_Nbr >'0' AND p.Faction<>'".$Faction."' AND r.Visible='1' AND r.Bomb_IA='0') UNION (													//$con=dbconnecti();
                                                    $result_unit=mysqli_result(mysqli_query($con,$query_unit),0);
                                                    //mysqli_close($con);
                                                    if($result_unit >0){
                                                        if($Admin)
                                                            $ad_txt = '<b>- '.$result_unit.' cibles</b>';
                                                        else
                                                            $ad_txt = '';
                                                        $Lieuxt.="<option value='".$data['ID']."'>".$data['Nom']." (".$Dist[0]."km)".$ad_txt."</option>";
                                                    }
                                                    /*elseif($Admin)
														$Lieuxt.="<option value='".$data['ID']."'>".$data['Nom']." (".$Dist[0]."km)</option>";*/
                                                }
                                                /*elseif($Admin and $Dist[0] <=$Autonomie_max)
                                                    $Lieuxt.="<option value='".$data['ID']."' disabled>".$data['Nom']." (".$Dist[0]."km)</option>";*/
                                            }
                                            mysqli_free_result($result);
                                            unset($data);
                                        }
                                        else
                                            $mes.="Erreur d'import de données.";
                                        mysqli_close($con);
                                        if($Lieuxt)
                                        {
                                            $Tac_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Bombardement Tactique ".$Aide_txt_tac."<span class='label label-primary'>Rayon d'action ".$Autonomie_tac_max."km</span></div><div class='panel-body'>
												<form action='index.php?view=em_ia5' method='post'>
												<input type='hidden' name='Unite' value='".$Unite."'>
												<div class='row'>
												    <div class='col-xs-12 col-sm-8'>
												        <label for='Cible'>Choix de la Cible</label>
												        <select name='Cible' class='form-control'>".$Lieuxt."</select>
												    </div>
												    <div class='col-xs-12 col-sm-4'>
												        <label for='Flight'>Choix du ".$Sqn."</label>
												        <select name='Flight' class='form-control'>".$Flight_txt_tac."</select>
												    </div>
												</div>
												<div class='i-flex mt-2'><img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
												<input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form>".$Aide_txt_btac."</div></div>";
                                        }
                                        else
                                            $Tac_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Bombardement Tactique<span class='label label-warning'>Rayon d'action ".$Autonomie_tac_max."km</span></div><div class='panel-body'><div class='alert alert-danger'>Aucune cible n'a été reconnue à portée de cette unité.<br>Une mission de reconnaissance tactique doit être exécutée avec succès préalablement à tout bombardement tactique.</div></div></div>";
                                    }
                                }
                                if($Unite_Type ==9 and $Credits >=4)
                                {
                                    $Lieuxasm="";
                                    $query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max' AND (Zone=6 OR Port_Ori >0 OR Plage >0)".$query_treve." ORDER BY Nom ASC";
                                    $con=dbconnecti();
                                    $result=mysqli_query($con,$query) or die(mysqli_error($con));
                                    mysqli_close($con);
                                    if($result)
                                    {
                                        while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                                        {
                                            $Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data['Longitude'],$data['Latitude']);
                                            if($data['ID']==$Base)$Dist[0]=10;
                                            if($Dist[0] <=$Autonomie_max)
                                            {
                                                $Lieuxasm.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
                                            }
                                        }
                                        mysqli_free_result($result);
                                        unset($data);
                                    }
                                    else
                                        $mes.="Erreur d'import de données.";
                                    if($Lieuxasm)
                                    {
                                        $Tac_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>ASM<span class='label label-warning'>Rayon d'action ".$Autonomie_max."km</span></div><div class='panel-body'>
											<form action='em_ia1.php' method='post'>
											<input type='hidden' name='Unite' value='".$Unite."'>
											<input type='hidden' name='Avion1' value='".$Avion1."'>
											<input type='hidden' name='Avion2' value='".$Avion2."'>
											<input type='hidden' name='Avion3' value='".$Avion3."'>
											<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
											<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
											<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
											<input type='hidden' name='Type' value='29'>
											<input type='hidden' name='Altitude' value='500'>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-8'>
                                                    <label for='Cible'>Choix de la Cible</label>
                                                    <select name='Cible' class='form-control'>".$Lieuxasm."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-4'>
                                                    <label for='Flight'>Choix du ".$Sqn."</label>
                                                    <select name='Flight' class='form-control'>".$Flight_txt_tac."</select>
                                                </div>
                                            </div>
											<div class='i-flex mt-2'><img src='images/CT4.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
											<input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form></div></div>";
                                    }
                                    else
                                        $Tac_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>ASM</div><div class='panel-body'><div class='alert alert-danger'>L'unité n'a pas l'autonomie pour atteindre la zone maritime la plus proche!</div></div></div>";
                                }
                                if($Unite_Type <13 and $Unite_Type !=8 and ($choix17 or $choix4 or $choix5 or $choix7 or $choix32))
                                {
                                    $Lieuxd="";
                                    if($Only_Here)
                                        $Lieuxd.="<option value=".$Base.">".$Base_Nom." (10km)</option>";
                                    else
                                    {
                                        if($Unite_Type ==9)
                                            $query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max' AND Zone=6".$query_treve." ORDER BY Nom ASC";
                                        elseif($Admin){
                                            $query="SELECT DISTINCT ID,Nom,Longitude,Latitude,
                                            (SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID=Lieu.ID AND r.Vehicule_Nbr >'0' AND p.Faction<>'".$Faction."' AND r.Visible='0') AS recce_units
                                            FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max'".$query_treve." 
                                            ORDER BY recce_units DESC, Nom ASC";
                                        }
                                        else
                                            $query="SELECT DISTINCT ID,Nom,Longitude,Latitude FROM Lieu WHERE Latitude >='$Lat_base_min' AND Latitude <='$Lat_base_max' AND Longitude >='$Long_base_min' AND Longitude <'$Long_base_max'".$query_treve." ORDER BY Nom ASC";
                                        $con=dbconnecti();
                                        $result=mysqli_query($con,$query) or die(mysqli_error($con));
                                        if($result)
                                        {
                                            while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                                            {
                                                $Dist=GetDistance(0,0,$Longitude_base,$Latitude_base,$data['Longitude'],$data['Latitude']);
                                                if($data['ID'] ==$Base)$Dist[0]=10;
                                                if($Dist[0] <=$Autonomie_max){
                                                    $ad_txt='';
                                                    if($Admin and $data['recce_units'] >0){
                                                        $ad_txt = '<b>- '.$data['recce_units'].' cibles</b>';
                                                    }
                                                    $Lieuxd.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)".$ad_txt."</option>";
                                                }
                                                if($Dist[0] <=$Autonomie_long_max)
                                                    $Lieuxlong.="<option value=".$data['ID'].">".$data['Nom']." (".$Dist[0]."km)</option>";
                                            }
                                            mysqli_free_result($result);
                                            unset($data);
                                        }
                                        else
                                            $mes.="Erreur d'import de données.";
                                        mysqli_close($con);
                                    }
                                    if($Lieuxd and $choix5)
                                    {
                                        if($Unite_Type ==1 or $Unite_Type ==2 or $Unite_Type ==4 or $Unite_Type ==7 or $Unite_Type ==12)
                                        {
                                            if($Avion1_Nbr >0 and $Stock_Avion1 >=$Stock1 and $Avion1_rec)
                                                $Flight_txt_rec="<option value='1'>1</option>";
                                            if($Avion2_Nbr >0 and $Stock_Avion2 >=$Stock2 and $Avion2_rec)
                                                $Flight_txt_rec.="<option value='2'>2</option>";
                                            if($Avion3_Nbr >0 and $Stock_Avion3 >=$Stock3 and $Avion3_rec)
                                                $Flight_txt_rec.="<option value='3'>3</option>";
                                            $Zones_txt="<select name='Zoneb' class='form-control'><option value='10'>Caserne/Au large</option></select>";
                                            $Aide_txt_tac="<a href='#' class='popup'><img src='images/help.png'><span>Cette mission annulera toute mission de patrouille ou d'escorte en cours</span></a>";
                                        }
                                        else
                                        {
                                            $Flight_txt_rec=$Flight_txt;
                                            $Zones_txt='<select name="Zoneb" class="form-control"><option value="0" selected disabled>Toutes</option></select>';
                                        }
                                        if($Flight_txt_rec)
                                        {
                                            $Tac_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Reconnaissance ".$Aide_txt_tac."<span class='label label-primary'>Rayon d'action ".$Autonomie_max."km</span></div><div class='panel-body'>
												<form action='em_ia1.php' method='post'>
												<input type='hidden' name='Unite' value='".$Unite."'>
												<input type='hidden' name='Avion1' value='".$Avion1."'>
												<input type='hidden' name='Avion2' value='".$Avion2."'>
												<input type='hidden' name='Avion3' value='".$Avion3."'>
												<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
												<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
												<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
												<input type='hidden' name='Type' value='5'>
												<div class='row'>
												    <div class='col-xs-12 col-sm-6'>
												        <label for='Cible'>Choix de la Cible</label>
												        <select name='Cible' class='form-control'>".$Lieuxd."</select>
												    </div>
												    <div class='col-xs-12 col-sm-6'>
												        <label for='Zoneb'>Choix de la Zone</label>
												        ".$Zones_txt."
												    </div>
												</div>
												<div class='row'>
												    <div class='col-xs-12 col-sm-8'>
												        <label for='Altitude'>Choix de l'altitude de Mission</label>
												        <select name='Altitude' class='form-control'>														
												        <option value='100'>Basse altitude (100m)</option>
														<option value='500'>Basse altitude (500m)</option>
														<option value='1000'>Basse altitude (1000m)</option>
														<option value='2000'>Altitude moyenne (2000m)</option></select>
												    </div>
												    <div class='col-xs-12 col-sm-4'>
												        <label for='Flight'>Choix du ".$Sqn."</label>
												        <select name='Flight' class='form-control'>".$Flight_txt_rec."</select>
												    </div>
												</div>
												<div class='i-flex mt-2'><img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
												<input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form></div></div>";
                                        }
                                    }
                                    if($Lieuxd and ($choix17 or $choix4 or $choix7 or $choix32))
                                    {
                                        $select_alt=false;
                                        if($Plafond_max >=4000)
                                            $select_alt.="<option value='4000' selected>Altitude moyenne (4000m)</option>";
                                        if($Plafond_max >=5000)
                                            $select_alt.="<option value='5000'>Altitude moyenne (5000m)</option>";
                                        if($Plafond_max >=6000)
                                            $select_alt.="<option value='6000'>Altitude moyenne (6000m)</option>";
                                        if($Plafond_max >=7000)
                                            $select_alt.="<option value='7000'>Haute altitude (7000m)</option>";
                                        if($Plafond_max >=8000)
                                            $select_alt.="<option value='8000'>Haute altitude (8000m)</option>";
                                        if($Plafond_max >=9000)
                                            $select_alt.="<option value='9000'>Haute altitude (9000m)</option>";
                                        if($Plafond_max >=10000)
                                            $select_alt.="<option value='10000'>Haute altitude (10000m)</option>";
                                        if($Unite_Type ==1 or $Unite_Type ==4 or $Unite_Type ==12)
                                            $Mis_Chs_txt="Chasse";
                                        else
                                            $Mis_Chs_txt="Veille <a href='help/aide_task.php' target='_blank' title='Cliquez pour aide'><img src='images/help.png'></a>";
                                        $Tac_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>".$Mis_Chs_txt."<span class='label label-primary'>Rayon d'action ".$Autonomie_max."km</span></div><div class='panel-body'>
											<form action='em_ia1.php' method='post'>
											<input type='hidden' name='Unite' value='".$Unite."'>
											<input type='hidden' name='Avion1' value='".$Avion1."'>
											<input type='hidden' name='Avion2' value='".$Avion2."'>
											<input type='hidden' name='Avion3' value='".$Avion3."'>
											<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
											<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
											<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Cible'>Choix de la Cible</label>
                                                    <select name='Cible' class='form-control'>".$Lieuxd."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Type'>Choix du Type de Mission</label>
                                                    <select name='Type' class='form-control'>".$choix17.$choix4.$choix7.$choix32."</select>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-8'>
                                                    <label for='Altitude'>Choix de l'altitude de Mission</label>
                                                    <select name='Altitude' class='form-control'>														
                                                    <option value='100'>Basse altitude (100m)</option>
                                                    <option value='500'>Basse altitude (500m)</option>
                                                    <option value='1000'>Basse altitude (1000m)</option>
                                                    <option value='2000'>Altitude moyenne (2000m)</option>
                                                    <option value='3000'>Altitude moyenne (3000m)</option>".$select_alt."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-4'>
                                                    <label for='Flight'>Choix du ".$Sqn."</label>
                                                    <select name='Flight' class='form-control'>".$Flight_txt."</select>
                                                </div>
                                            </div>
                                            <div class='i-flex mt-2'><img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
                                            <input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form>";
                                        if($Unite_Type ==1 or $Unite_Type ==12)
                                            $Tac_Output_txt.="<div class='alert alert-info'>Les avions en patrouille pourront intercepter les avions ennemis approchant de 1000m plus haut à 3000m plus bas que l'altitude de mission. La météo peut réduire cette distance.
											<br>Les avions en escorte pourront assister les avions alliés effectuant leur attaque de 1000m plus haut à 3000m plus bas que l'altitude de mission. La météo peut réduire cette distance.</div>";
                                        elseif($Unite_Type ==4)
                                            $Tac_Output_txt.="<div class='alert alert-info'>Les avions en patrouille pourront intercepter les avions ennemis approchant de 1000m plus haut à 2000m plus bas que l'altitude de mission. La météo peut réduire cette distance tandis que le radar l'augmentera.
											<br>Les avions en escorte pourront assister les avions alliés effectuant leur attaque de 1000m plus haut à 3000m plus bas que l'altitude de mission. La météo peut réduire cette distance.</div>";
                                        $Tac_Output_txt.='</div></div>';
                                    }
                                    if($Lieuxlong and ($choix17 or $choix4 or $choix7) and $Credits >=2)
                                    {
                                        $select_alt=false;
                                        if($Plafond_max >=4000)
                                            $select_alt.="<option value='4000' selected>Altitude moyenne (4000m)</option>";
                                        if($Plafond_max >=5000)
                                            $select_alt.="<option value='5000'>Altitude moyenne (5000m)</option>";
                                        if($Plafond_max >=6000)
                                            $select_alt.="<option value='6000'>Altitude moyenne (6000m)</option>";
                                        if($Plafond_max >=7000)
                                            $select_alt.="<option value='7000'>Haute altitude (7000m)</option>";
                                        if($Plafond_max >=8000)
                                            $select_alt.="<option value='8000'>Haute altitude (8000m)</option>";
                                        if($Plafond_max >=9000)
                                            $select_alt.="<option value='9000'>Haute altitude (9000m)</option>";
                                        if($Plafond_max >=10000)
                                            $select_alt.="<option value='10000'>Haute altitude (10000m)</option>";
                                        $Tac_Output_txt.="<div class='panel panel-war'><div class='panel-heading'>Longue distance<span class='label label-warning'>Rayon d'action ".$Autonomie_long_max."km</span></div><div class='panel-body'>                                        
											<form action='em_ia1.php' method='post'>
											<input type='hidden' name='Unite' value='".$Unite."'>
											<input type='hidden' name='Avion1' value='".$Avion1."'>
											<input type='hidden' name='Avion2' value='".$Avion2."'>
											<input type='hidden' name='Avion3' value='".$Avion3."'>
											<input type='hidden' name='Avion1nbr' value='".$Avion1_Nbr."'>
											<input type='hidden' name='Avion2nbr' value='".$Avion2_Nbr."'>
											<input type='hidden' name='Avion3nbr' value='".$Avion3_Nbr."'>
											<input type='hidden' name='Long' value='1'>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Cible'>Choix de la Cible</label>
                                                    <select name='Cible' class='form-control'>".$Lieuxlong."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-6'>
                                                    <label for='Type'>Choix du Type de Mission</label>
                                                    <select name='Type' class='form-control'>".$choix17.$choix4.$choix7."</select>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-8'>
                                                    <label for='Altitude'>Choix de l'altitude de Mission</label>
                                                    <select name='Altitude' class='form-control'>														
                                                    <option value='100'>Basse altitude (100m)</option>
                                                    <option value='500'>Basse altitude (500m)</option>
                                                    <option value='1000'>Basse altitude (1000m)</option>
                                                    <option value='2000'>Altitude moyenne (2000m)</option>
                                                    <option value='3000'>Altitude moyenne (3000m)</option>".$select_alt."</select>
                                                </div>
                                                <div class='col-xs-12 col-sm-4'>
                                                    <label for='Flight'>Choix du ".$Sqn."</label>
                                                    <select name='Flight' class='form-control'>".$Flight_txt."</select>
                                                </div>
                                            </div>
                                            <div class='i-flex mt-2'><img src='images/CT2.png' title='Montant en Crédits Temps que nécessite cette action'><a href='#' class='popup'><div class='action-jour'></div><span>Compte comme action du jour</span></a>
                                            <input type='submit' value='Valider' class='btn btn-sm btn-default' onclick='this.disabled=true;this.form.submit();'></div></form>";
                                        $Tac_Output_txt.="<div class='alert alert-info'>Les missions longues distances permettent d'atteindre des objectifs éloignés grâce aux réservoirs supplémentaires. Les avions en mission longue distance sont cependant nettement moins performants au combat.</div></div></div>";
                                    }
                                }
                                //Output Missions
                                if($Strat_Output_txt and $Tac_Output_txt)
                                    echo "<div class='row'><div class='col-xs-12 col-lg-6'><h2>Missions Stratégiques</h2>".$Strat_Output_txt."</div><div class='col-xs-12 col-lg-6'><h2>Missions Tactiques</h2>".$Tac_Output_txt."</div></div>";
                                elseif($Strat_Output_txt)
                                    echo "<h2>Missions Stratégiques</h2>".$Strat_Output_txt;
                                elseif($Tac_Output_txt)
                                    echo "<h2>Missions Tactiques</h2>".$Tac_Output_txt;
                                else
                                    echo "<div class='alert alert-danger'>Aucune destination de mission n'est possible depuis cette base pour cette unité</div>";
                                echo '</div></div>';
                            }
                            else
                                echo "<div class='alert alert-danger'>Si les stocks de carburant sont vides, vous devez ravitailler les dépôts proches en utilisant les trains et ou les cargos EM<br>Sur un lieu côtier, vous pouvez également placer un cargo EM au large qui jouera le rôle de dépôt flottant.</div>";
                            if($Only_Here)
                                echo "<div class='alert alert-warning'>Les unités stationnées sur leur usine de production doivent être déplacées sur un autre aérodrome avant de pouvoir effectuer une mission de combat.</div>";
                            //Demandes en cours
                            $txt="";
                            $con=dbconnecti();
                            $result=mysqli_query($con,"SELECT * FROM 
                            (
                              (SELECT DISTINCT 1 as tri,l.Nom,l.Zone,u.Mission_Type_D,p.Pays_ID,u.Nom as Unite,l.Recce,l.ID FROM Unit as u,Lieu as l,Pays as p
							  WHERE (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND u.Pays=p.Pays_ID AND u.Mission_Lieu_D >0 
							  AND u.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' AND u.Mission_Lieu_D=l.ID)
							UNION ALL 
							    (SELECT DISTINCT 2 as tri,l.Nom,l.Zone,r.Mission_Type_D,r.Pays,r.ID as Unite,l.Recce,l.ID FROM Lieu as l,Regiment_IA as r,Pays as p 
							    WHERE r.Pays=p.Pays_ID AND r.Front='$Front' AND r.Mission_Lieu_D=l.ID AND r.Mission_Lieu_D >0 AND r.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' 
							    AND (l.Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (l.Longitude BETWEEN '$Long_base_min' AND '$Long_base_max'))
							) a ORDER BY tri,Nom ASC");
                            /*UNION ALL (SELECT DISTINCT 3 as tri,l.Nom,l.Zone,o.Mission_Type_D,p.Pays_ID,o.Nom as Unite,l.Recce,l.ID FROM Officier as o,Lieu as l,Pays as p
                            WHERE o.Pays=p.Pays_ID AND o.Front='$Front' AND o.Mission_Lieu_D >0 AND o.Mission_Type_D IN(".$Mis_list.") AND p.Faction='$Faction' AND o.Mission_Lieu_D=l.ID)*/
                            if($result)
                            {
                                while($Data=mysqli_fetch_array($result,MYSQLI_NUM))
                                {
                                    if($Data[2] ==6)
                                    {
                                        //$con=dbconnecti();
                                        $Nav_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Lieu_ID='$Data[6]' AND Pays<>'$country' AND Vehicule_Nbr >0 AND Visible=1"),0);
                                        //mysqli_close($con);
                                        if($Nav_eni >0)
                                            $Recce='<b>Oui</b>';
                                        else
                                            $Recce='Non';
                                    }
                                    else
                                    {
                                        if($Data[6] ==2)
                                            $Recce='<b>Eclairé</b>';
                                        elseif($Data[6] ==1)
                                            $Recce='<b>Oui</b>';
                                        else
                                            $Recce='Non';
                                    }
                                    if(is_numeric($Data[5]))$Data[5].="e Cie";
                                    $txt.="<tr><td>".$Data[1]."</td><td><img src='images/zone".$Data[2].".jpg'></td><td>".GetMissionType($Data[3])."</td><td><img src='".$Data[4]."20.gif' title='".$Data[5]."'> ".$Data[5]."</td><td>".$Recce."</td></tr>";
                                }
                                mysqli_free_result($result);
                            }
                            mysqli_close($con);
                            if(!$txt)$txt="<tr><td colspan='5'>Aucune demande actuellement</td></tr>";
                            if($Admin)$Front_txt="(".GetFront($Front_unit).", Latmin=".$Lat_base_min.", Latmax=".$Lat_base_max.", Longmin=".$Long_base_min.", Longmax=".$Long_base_max.")";
                            echo "<h2>Le Front</h2>".$Front_txt."<div class='panel panel-war'><div class='panel-heading'>Demandes de mission en cours</div><div class='panel-body'>
                                <table class='table table-striped'>
								<thead><tr>
								<th>Lieu</th>
								<th>Zone</th>
								<th>Mission demandée</th>
								<th>Unité demandeuse</th>
								<th>Status Reco</th></tr></thead>";
                            echo $txt.'</table></div></div>';
                        }
                    }
                    elseif($Faction !=$Faction_Flag or $Faction !=$Faction_Air)
                        echo "<div class='alert alert-danger'>L'aérodrome est sous le contrôle de l'ennemi. Pour pouvoir utiliser cette unité, vous devez la déplacer!</div>";
                    elseif($Meteo <-49)
                        echo "<div class='alert alert-danger'><img src='images/meteo".$Meteo.".gif'> La météo exécrable empêche tout décollage!</div>";
                    elseif($Pilotes <1)
                        echo "<div class='alert alert-danger'>Aucun pilote n'est en état de voler!</div>";
                    else
                        echo "<div class='alert alert-danger'>Décollage impossible!</div>";
                }
                else
                    echo "<div class='alert alert-danger'>Le GHQ ne peut pas donner d'ordre de mission aux unités de front!</div>";
                echo '</div></div>';
            }
            else
                echo "<img src='images/top_secret.gif'>";
        }
        else
            echo "<div class='alert alert-danger'>Vous manquez de temps pour donner vos ordres...</div>";
    }
    else
        echo "<img src='images/top_secret.gif'>";
}
else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');