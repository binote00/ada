<?php
/**
 * User: JF
 * Date: 27-07-18
 * Time: 19:04
 */

if(($Type_Veh ==95 or $Detection >10) and $Position !=6 and $Position !=11 and $Position !=12 and $Position !=13 and $Position !=14 and $Credits >=2 and !$Enis_combi and !$Move and $Vehicule_Nbr >0)
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
