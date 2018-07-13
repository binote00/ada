<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierID >0 xor $OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	$CT=Insec($_POST['CT']);
	if($OfficierID >0)
	{
		$Credits=GetData("Officier","ID",$OfficierID,"Credits");
		if($CT >0)
		{
			$DB_Reg='Regiment';
			$Reg_a_ia=false;
		}
		else
		{
			$DB_Reg='Regiment_IA';
			$Reg_a_ia=true;
		}
	}
	elseif($OfficierEMID >0)
	{
		$DB_Reg='Regiment_IA';
		$Reg_a_ia=true;
	}
	if($Credits >=$CT)
	{
		$country=$_SESSION['country'];
		$Veh=Insec($_POST['Veh']);
		$Reg=Insec($_POST['Reg']);
		$Lieu=Insec($_POST['Cible']);
		$Conso=Insec($_POST['Conso']);
		$Reg_b_ia=true;
		if($Reg >0 and $Veh >0)
		{
			$con=dbconnecti();
			$Admin=mysqli_result(mysqli_query($con,"SELECT Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
			$result=mysqli_query($con,"SELECT Vehicule_Nbr,Experience,Position,Lieu_ID,Muns,HP,Stock_Essence_1,Skill,Matos,Autonomie,Move FROM $DB_Reg WHERE ID='$Reg'");
			//mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Vehicule_Nbr=$data['Vehicule_Nbr'];
					$Reg_xp=$data['Experience'];
					$Pos=$data['Position'];
					$Lieu=$data['Lieu_ID'];
					$Muns=$data['Muns'];
					$Stock_Diesel=$data['Stock_Essence_1'];
					$HP_navire=$data['HP'];
					$Skill=$data['Skill'];
					$Matos=$data['Matos'];
                    $Autonomie=$data['Autonomie'];
                    $Move=$data['Move'];
				}
				mysqli_free_result($result);
				unset($data);
			}						
			//$con=dbconnecti();
			$resultv=mysqli_query($con,"SELECT Nom,Arme_Inf,Vitesse,Detection,Type FROM Cible WHERE ID='$Veh'");
			mysqli_close($con);
			if($resultv)
			{
				while($datav=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
				{
					$Veh_Nom=$datav['Nom'];
					$HP=$datav['HP'];
					$Arme=$datav['Arme_Inf'];
					$Vitesse=$datav['Vitesse'];
					$Detection=$datav['Detection'];
					$Type_navire=$datav['Type'];
				}
				mysqli_free_result($resultv);
				unset($datav);
			}
			if($Autonomie >0 and !$Move){
                if(!$Reg_a_ia and $OfficierID >0)
                {
                    if($Stock_Diesel >=$Conso)
                        UpdateData("Regiment","Stock_Essence_1",-$Conso,"ID",$Reg);
                    else
                    {
                        $Diff=($Conso-$Stock_Diesel)/10;
                        SetData("Regiment","Stock_Essence_1",0,"ID",$Reg);
                        if($Diff >0)
                        {
                            UpdateData("Regiment","Vehicule_Nbr",-$Conso,"ID",$Reg);
                            UpdateData("Regiment","Moral",-$Conso,"ID",$Reg);
                            $Vehicule_Nbr=GetData("Regiment","ID",$Reg,"Vehicule_Nbr");
                            AddEventGround(410,$Veh,$OfficierID,$Reg,$Lieu,$Conso);
                            $msg.="<br>Une partie de vos troupes déserte!";
                        }
                    }
                }
                if($Vehicule_Nbr >0)
                {
                    if($OfficierID >0 and IsSkill(35,$OfficierID))
                        $Detection+=25;
                    else
                    {
                        if($Skill ==40)
                            $Detection+=10;
                        elseif($Skill ==159)
                            $Detection+=15;
                        elseif($Skill ==160)
                            $Detection+=20;
                        elseif($Skill ==161)
                            $Detection+=25;
                    }
                    if($Matos ==20)
                    {
                        $Detection+=20;
                        $Bonus_Tir+=10;
                        $msg.="<br>Le <b>Sonar</b> est enclenché!";
                    }
                    elseif($Matos ==19)
                    {
                        $Detection+=10;
                        $Bonus_Tir+=5;
                        $msg.="<br>L'<b>ASDIC</b> est enclenché!";
                    }
                    $query="SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Camouflage,r.Experience,r.Visible FROM Regiment_IA as r,Cible as c 
				WHERE r.Vehicule_ID=c.ID AND c.mobile=5 AND c.Type=37 AND r.Lieu_ID='$Lieu' AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' ORDER BY RAND() LIMIT 1";
                    /*SELECT r.ID,r.Officier_ID,r.Vehicule_ID,r.Camouflage,r.Experience,r.Visible FROM Regiment as r,Cible as c
                    WHERE r.Vehicule_ID=c.ID AND c.mobile=5 AND c.Type=37 AND r.Lieu_ID='$Lieu' AND r.Vehicule_Nbr >0 AND r.Pays<>'$country') UNION (*/
                    $con=dbconnecti();
                    $resultl=mysqli_query($con,"SELECT Nom,Flag,Meteo,Zone FROM Lieu WHERE ID='$Lieu'");
                    $result=mysqli_query($con,$query);
                    mysqli_close($con);
                    if($resultl)
                    {
                        while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC))
                        {
                            $Lieu_Nom=$datal['Nom'];
                            $Flag=$datal['Flag'];
                            $Meteo=$datal['Meteo'];
                            $Zone=$datal['Zone'];
                        }
                        mysqli_free_result($resultl);
                        unset($datal);
                    }
                    if($result)
                    {
                        while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                        {
                            if($Admin)
                                $msg.="<br>S-M ".$data['ID'];
                            if(!$data['Officier_ID'])
                                $Reg_DB="Regiment_IA";
                            else
                                $Reg_DB="Regiment";
                            if($data['Visible'] ==1)
                            {
                                $Reg_eni=$data['ID'];
                                $Vehicule=$data['Vehicule_ID'];
                                $Sub=false;
                            }
                            else
                            {
                                if(mt_rand(0,$Reg_xp)+20+$Detection-($data['Camouflage']*10)-mt_rand(0,$data['Experience']) >0)
                                {
                                    $Reg_eni=$data['ID'];
                                    $Vehicule=$data['Vehicule_ID'];
                                    $Sub=true;
                                }
                                if($Admin)
                                    $msg.="<br>[DEBUG] : Detection ASM=(20 + rand (0,".$Reg_xp.") + Detection ".$Detection.", - Cam ".$data['Camouflage']."*10 - Esquive rand (0,".$data['Experience'].")";
                            }
                        }
                        mysqli_free_result($result);
                    }
                    $HP_ori=$HP;
                    $HP=$HP_navire;
                    if($Meteo <-69)
                        $Max_Range=5000;
                    elseif($Meteo <-9)
                        $Max_Range=10000;
                    else
                        $Max_Range=20000;
                    if($Flag ==$country)
                        $Vitesse+=10;
                    $Range=($Vitesse*250)+($Reg_xp*10);
                    if($Max_Range >$Range)$Range=$Max_Range;
                    $msg.="<p>Vos navires partent en chasse d'éventuels sous-marins ennemis!</p>";
                }
                //Tir
                if($Vehicule_Nbr >0 and $Vehicule >0 and $Reg_eni)
                {
                    $Tir_base=floor(($Reg_xp/10)+10);
                    //Get Vehicule_eni
                    $con=dbconnecti();
                    $result=mysqli_query($con,"SELECT Nom,HP,Blindage_f,Vitesse,Taille,mobile,Reput,Type,Categorie FROM Cible WHERE ID='$Vehicule'");
                    $result2=mysqli_query($con,"SELECT Pays,Vehicule_Nbr,Officier_ID,Position,Placement,Experience,Move,Camouflage,HP,Skill,Matos FROM $Reg_DB WHERE ID='$Reg_eni'");
                    mysqli_close($con);
                    if($result)
                    {
                        while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                        {
                            $Vehicule_Nom=$data['Nom'];
                            $HP_ori_eni=$data['HP'];
                            $Blindage_eni=$data['Blindage_f'];
                            $Vitesse_eni=$data['Vitesse'];
                            $Taille_eni=$data['Taille'];
                            $mobile_eni=$data['mobile'];
                            $Reput_eni=$data['Reput'];
                            $Type_eni=$data['Type'];
                            $Categorie_eni=$data['Categorie'];
                        }
                        mysqli_free_result($result);
                        unset($result);
                    }
                    //GetReg_eni
                    if($result2)
                    {
                        while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
                        {
                            $Officier_eni=$data['Officier_ID'];
                            $Pays_eni=$data['Pays'];
                            $Veh_Nbr_eni=$data['Vehicule_Nbr'];
                            $Pos_eni=$data['Position'];
                            $Placement_eni=$data['Placement'];
                            $Exp_eni=$data['Experience'];
                            $HP_eni=$data['HP'];
                            $Move=$data['Move'];
                            $Skill_eni=$data['Skill'];
                            $Matos_eni=$data['Matos'];
                            /*if($Officier_eni >0)
                            {
                                $Trait_eni=GetData("Officier","ID",$Officier_eni,"Trait");
                                $Reg_b_ia=false;
                            }*/
                            if($Trait_eni ==5)
                                $Cam_bonus_eni=2;
                            else
                                $Cam_bonus_eni=1;
                            $Cam_eni=$Taille_eni/$data['Camouflage']/$Cam_bonus_eni;
                        }
                        mysqli_free_result($result2);
                        unset($data);
                    }
                    //Tir
                    if($Arme ==223)
                    {
                        $Arme_Cal=150;
                        $Arme_Multi=1;
                        $Arme_Dg=1500;
                        if(!$Reg_a_ia)
                            $Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Charges");
                        else
                            $Muns_Stock=100;
                        $Muns_Conso=$Vehicule_Nbr*$Arme_Multi;
                        if($Muns_Stock >=$Muns_Conso and $Muns_Conso >0)
                        {
                            if(!$Reg_a_ia)
                                UpdateData("Regiment","Stock_Charges",-$Muns_Conso,"ID",$Reg);
                            $Arme_Nom=GetData("Armes","ID",$Arme,"Nom");
                            if(!$Move)
                                $Vitesse_eni=0;
                            elseif($Sub)
                                $Vitesse_eni/=2;
                            $msg.="<br>Votre unité largue des ".$Arme_Nom;
                            //$Bonus_Dg=Damage_Bonus("Regiment",1,1,$Arme,$Blindage_eni,$Muns,$Range);
                            $Update_Nbr_eni=0;
                            $Update_Reput=0;
                            $Update_xp=0;
                            if($OfficierID >0 and IsSkill(36,$OfficierID))
                            {
                                $Tir_base+=25;
                                $Arme_Multi+=1;
                            }
                            elseif($Skill ==39)
                            {
                                $Tir_base+=10;
                                $Arme_Multi+=1;
                            }
                            elseif($Skill ==156)
                            {
                                $Tir_base+=15;
                                $Arme_Multi+=2;
                            }
                            elseif($Skill ==157)
                            {
                                $Tir_base+=20;
                                $Arme_Multi+=3;
                            }
                            elseif($Skill ==158)
                            {
                                $Tir_base+=25;
                                $Arme_Multi+=4;
                            }
                            if($Officier_eni >0 and IsSkill(38,$Officier_eni))
                            {
                                $Esquive+=25;
                                $Vitesse_eni+=5;
                            }
                            elseif($Skill_eni ==32)
                            {
                                $Esquive+=10;
                                $Plongee_rapide=20;
                            }
                            elseif($Skill_eni ==135)
                            {
                                $Esquive+=15;
                                $Plongee_rapide=30;
                            }
                            elseif($Skill_eni ==136)
                            {
                                $Esquive+=20;
                                $Plongee_rapide=40;
                            }
                            elseif($Skill_eni ==137)
                            {
                                $Esquive+=25;
                                $Plongee_rapide=50;
                            }
                            if($Pos_eni ==25 and $Matos_eni ==18) //Schnorchel
                            {
                                $Esquive+=50;
                                $Plongee_rapide=100;
                            }
                            for($t=1;$t<=$Vehicule_Nbr;$t++)
                            {
                                if($Veh_Nbr_eni >0)
                                {
                                    $Tir=mt_rand(0,$Tir_base);
                                    $Esquive=(($Exp_eni/10)+10);
                                    $Shoot=$Tir+$Bonus_Tir+$Cam_eni-$Vitesse_eni-mt_rand(0,$Esquive)+$Meteo;
                                    if($Admin ==1)$msg.="<br>[DEBUG] : Shoot=".$Shoot." (".$Tir."/".$Tir_base.") (+Cam ".$Cam_eni." +Bonus ".$Bonus_Tir." - Vit ".$Vitesse_eni." - Esquive ".$Esquive." - Météo ".$Meteo.")";
                                    if($Shoot >0 or $Tir ==$Tir_base)
                                    {
                                        $Base_Dg=mt_rand(1,$Arme_Dg)+$Tir;
                                        if($Tir >0 and $Tir ==$Tir_base)$Base_Dg=$Arme_Dg+$Tir;
                                        $Degats=($Base_Dg-$Blindage_eni)*GetShoot($Shoot,$Arme_Multi);
                                        $Degats=round(Get_Dmg(4,$Arme_Cal,$Blindage_eni,$Range,$Degats,20,$Range,65535));
                                        if($Blindage_eni >0 and $Vitesse_eni >10)
                                            $Degats=floor($Degats/2);
                                        elseif($Pos_eni ==2 and $Cam_eni <2)
                                            $Degats=floor($Degats/2);
                                        if($Degats <1)$Degats=1;
                                        if($Degats >$HP_eni)
                                        {
                                            $Update_Nbr_eni-=1;
                                            $Update_Reput+=$Reput_eni;
                                            $msg.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!<br>La cible est détruite!";
                                            $HP_eni=$HP_ori_eni;
                                            SetData($Reg_DB,"HP",$HP_ori_eni,"ID",$Reg_eni);
                                            $Veh_Nbr_eni-=1;
                                            if($Pos_eni ==20 and $mobile_eni ==5) //formation dispersée
                                                break;
                                        }
                                        elseif($Degats >0)
                                        {
                                            $Update_xp+=1;
                                            $msg.="<br>Votre unité touche la cible et lui occasionne <b>".$Degats."</b> dégâts!";
                                            $HP_eni-=$Degats;
                                            UpdateData($Reg_DB,"HP",-$Degats,"ID",$Reg_eni);
                                        }
                                        else
                                            $msg.="<br>Votre unité touche la cible, mais le blindage n'a pas été percé!";
                                    }
                                    else
                                        $msg.="<br>Votre unité rate la cible!";
                                    if(mt_rand(0,100)<=$Plongee_rapide)
                                    {
                                        $t=126;
                                        SetData($Reg_DB,"Visible",0,"ID",$Reg_eni);
                                        $msg.="<br>Vous perdez contact avec la cible!";
                                        break;
                                    }
                                }
                                else
                                {
                                    if(!$Reg_a_ia and $Officier_eni >0)
                                    {
                                        if(IsSkill(29,$Officier_eni))
                                        {
                                            $Front=GetData("Officier","ID",$OfficierID,"Front");
                                            $Latitude=GetData("Lieu","ID",$Lieu ,"Latitude");
                                            $Retraite=Get_Retraite($Front,$country,$Latitude);
                                            SetData("Regiment","Lieu_ID",$Retraite,"ID",$Officier_eni);
                                        }
                                        $Exp_final_eni=0;
                                        if($Trait_eni ==11)$Exp_final_eni=$Exp_eni;
                                        $con=dbconnecti();
                                        $reset=mysqli_query($con,"UPDATE Regiment SET Experience='$Exp_final_eni',Moral=0,Stock_Munitions_8=0,Stock_Munitions_13=0,Stock_Munitions_20=0,Stock_Munitions_30=0,Stock_Munitions_40=0,Stock_Munitions_50=0,
									Stock_Munitions_60=0,Stock_Munitions_75=0,Stock_Munitions_90=0,Stock_Munitions_105=0,Stock_Munitions_125=0,Stock_Munitions_150=0,Stock_Munitions_200=0,Stock_Munitions_300=0,Stock_Munitions_360=0,Stock_Munitions_530=0,Stock_Munitions_610=0,
									Stock_Charges=0,Stock_Mines=0,Stock_Essence_87=0,Stock_Essence_1=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg_eni'");
                                        mysqli_close($con);
                                    }
                                    else
                                    {
                                        $con=dbconnecti();
                                        $reset=mysqli_query($con,"UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Fret=0,Fret_Qty=0,HP=0 WHERE ID='$Reg_eni'");
                                        mysqli_close($con);
                                    }
                                    $msg_eni.="<br>L'ennemi est totalement en déroute!";
                                    break;
                                }
                            }
                            if($Update_Nbr_eni <0)
                            {
                                $Placement_eni=GetData($Reg_DB,"ID",$Reg_eni,"Placement");
                                UpdateData($Reg_DB,"Vehicule_Nbr",$Update_Nbr_eni,"ID",$Reg_eni);
                                UpdateData($Reg_DB,"Moral",$Update_Nbr_eni,"ID",$Reg_eni);
                                UpdateData($DB_Reg,"Moral",-$Update_Nbr_eni,"ID",$Reg);
                                if($Officier_eni >0)
                                    AddEventGround(405,$Vehicule,$OfficierID,$Reg_eni,$Lieu,-$Update_Nbr_eni,$Reg);
                                else
                                    AddEventGround(507,$Vehicule,$OfficierID,$Reg_eni,$Lieu,-$Update_Nbr_eni,$Reg);
                                AddGroundAtk($Reg,$Reg_eni,$Veh,$Vehicule_Nbr,$Vehicule,$Veh_Nbr_eni,$Pos,$Pos_eni,$Lieu,$Placement_eni,$Range,-$Update_Nbr_eni,$Reg_a_ia,$Reg_b_ia);
                            }
                            if(!$Reg_a_ia and $OfficierID >0)
                            {
                                if($Update_Reput and $Pays_eni !=$country)
                                {
                                    if(GetData("Officier","ID",$OfficierID,"Trait") ==1)
                                        $Update_Reput*=2;
                                    UpdateData("Regiment","Experience",$Update_Reput,"ID",$Reg);
                                    UpdateData("Officier","Avancement",$Update_Reput,"ID",$OfficierID);
                                    UpdateData("Officier","Reputation",$Update_Reput,"ID",$OfficierID);
                                }
                                elseif($Update_Reput and $Pays_eni ==$country)
                                {
                                    UpdateData("Officier","Avancement",-$Update_Reput,"ID",$OfficierID);
                                    UpdateData("Officier","Reputation",-$Update_Reput,"ID",$OfficierID);
                                }
                            }
                            if($Update_xp and $Pays_eni !=$country)
                            {
                                if(!$Reg_a_ia and $OfficierID >0)
                                {
                                    UpdateData("Regiment","Experience",$Update_xp,"ID",$Reg);
                                    UpdateData("Officier","Avancement",$Update_xp,"ID",$OfficierID);
                                    UpdateData("Officier","Reputation",$Update_xp,"ID",$OfficierID);
                                }
                                if(!$Reg_a_ia)
                                    AddEventGround(457,$Vehicule,$OfficierID,$Reg_eni,$Lieu,$Degats,$Reg);
                                else
                                    AddEventGround(467,$Vehicule,$OfficierID,$Reg_eni,$Lieu,$Degats,$Reg);
                            }
                        }
                        else
                            $msg.="<br>Votre unité annule son attaque, faute de munitions!";
                        $titre="Asm";
                        $mes="<div class='row'>
							<div class='col-md-2'><img src='images/zone".$Zone.".jpg'></div>
							<div class='col-md-8'><h2> ".$Lieu_Nom."</h2></div>
							<div class='col-md-2'><img src='images/meteo".$Meteo.".jpg'></div>
						</div>
						<div class='row'>
							<div class='col-md-6 col-xs-12'>
                                <div class='panel panel-war'>
                                    <div class='panel-heading'>Unité attaquante</div>
                                    <div class='panel-body'>
                                        <div class='row'><div class='col-md-12'><img src='images/vehicules/vehicule".$Veh.".gif'></div></div>
                                        <div class='row'><div class='col-md-6'><img src='images/".$country."20.gif'></div><div class='col-md-6'><span class='label label-primary'>".$Reg_xp."XP</span></div></div>
                                        <div class='row'><div class='col-md-12'>".GetPosGr($Pos)."</div></div>                                   
                                    </div>
                                </div>
							</div>
							<div class='col-md-6 col-xs-12'>
                                <div class='panel panel-war'>
                                    <div class='panel-heading'>Unité attaquée</div>
                                    <div class='panel-body'>
                                        <div class='row'><div class='col-md-12'><img src='images/vehicules/vehicule".$Vehicule.".gif'></div></div>
                                        <div class='row'><div class='col-md-6'><img src='images/".$Pays_eni."20.gif'></div><div class='col-md-6'><span class='label label-primary'>".$Exp_eni."XP</span></div></div>
                                        <div class='row'><div class='col-md-12'>".GetPosGr($Pos_eni)."</div></div>                                   
                                    </div>
                                </div>
							</div>
						</div>
						<h3>Rapport</h3>
						<div class='row'>
							<div class='col-md-6 col-xs-12'>".$msg."</div>
							<div class='col-md-6 col-xs-12'>".$msg_eni."</div>
						</div>";
                        mail("binote@hotmail.com","Aube des Aigles: Combat naval asm","Joueur : ".$OfficierID." dans les environs de : ".$Lieu_Nom."<br>Grenadage de ".$Veh_Nom." sur ".$Vehicule_Nom." <html>".$mes."</html>","Content-type: text/html; charset=utf-8");
                    }
                    else
                        $mes='<div class="alert alert-danger">Votre unité ne dispose pas d\'un armement approprié!</div>';
                }
                else
                {
                    $mes='<div class="alert alert-warning">La chasse est infructueuse!</div>';
                    $img="<img src='images/nav_asm.jpg'>";
                }
                $con=dbconnecti();
                $reset2=mysqli_query($con,"UPDATE $DB_Reg SET Position=24,Visible=1,Move=1,Experience=Experience+1,Autonomie=Autonomie-1,Move_time=NOW() WHERE ID='$Reg'");
                $reseteni=mysqli_query($con,"UPDATE $Reg_DB SET Position=25,Visible=0,Experience=Experience+1 WHERE ID='$Reg_eni'");
                mysqli_close($con);
            }
			if($CT >0)
			{
				UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
				$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
			}
			else
				$menu="<a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour au menu</a>";
			include_once('./default.php');
		}
		else
			echo '<div class="alert alert-danger">ERREUR : Aucune unité sélectionnée !</div>';
	}
	else
		echo '<div class="alert alert-danger">Pas assez de crédits!</div>';
}
?>