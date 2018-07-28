<?php
require_once './jfv_inc_sessions.php';
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0) //xor $OfficierID >0
{
	$Ordre_ok=false;
	$country=$_SESSION['country'];
	include_once './jfv_include.inc.php';
	include_once './jfv_ground.inc.php';
    include_once './jfv_inc_em.php';
	include_once './jfv_txt.inc.php';
	$Reg=Insec($_POST['Unit']);
    $con=dbconnecti();
	if($OfficierID >0)
	{
		$resulto=mysqli_query($con,"SELECT Front,Credits FROM Officier WHERE ID='$OfficierID'");
		if($resulto)
		{
			while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
			{
				$Front=$datao['Front'];
				$Credits=$datao['Credits'];
			}
			mysqli_free_result($resulto);
		}
	}
	elseif($OfficierEMID)
	{
		$resulto=mysqli_query($con,"SELECT Front,Credits,Trait,Armee FROM Officier_em WHERE ID='$OfficierEMID'");
		if($resulto)
		{
			while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
			{
				$Front=$datao['Front'];
				$Credits=$datao['Credits'];
				$Trait=$datao['Trait'];
				$Armee=$datao['Armee'];
			}
			mysqli_free_result($resulto);
		}
	}
	if($Front ==99)
	{
		$Planificateur=GetData("GHQ","Pays",$country,"Planificateur");
		if($Planificateur >0 and $OfficierEMID ==$Planificateur)
			$GHQ=true;
	}
	else
	{
		$result2=mysqli_query($con,"SELECT Commandant,Adjoint_Terre,Officier_Mer,Officier_Log FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
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
	if(($Commandant >0 and ($Commandant ==$OfficierEMID))
	or ($Adjoint_Terre >0 and ($Adjoint_Terre ==$OfficierEMID))
	or ($Officier_Mer >0 and ($Officier_Mer ==$OfficierEMID))
	or ($Officier_Log >0 and ($Officier_Log ==$OfficierEMID))
	or $Admin ==1 or $GHQ)
		$Ordre_ok=true;
	else
	{
		$reg_pre=mysqli_query($con,"SELECT Bataillon,Division FROM Regiment_IA WHERE ID='$Reg'");
		if($reg_pre)
		{
			while($datarp=mysqli_fetch_array($reg_pre,MYSQLI_ASSOC))
			{
				$Bataillono=$datarp['Bataillon'];
				$Divisiono=$datarp['Division'];
			}
			mysqli_free_result($reg_pre);
		}
		if($OfficierID >0)
		{
			if($Bataillono ==$OfficierID)
				$Ordre_ok=true;
			else
			{
				$Division_Cdt=mysqli_result(mysqli_query($con,"SELECT Cdt FROM Division WHERE ID='$Divisiono'"),0);
				if($Division_Cdt ==$OfficierID)$Ordre_ok=true;
				$menu="<a href='index.php?view=ground_div' class='btn btn-default' title='Retour'>Retour</a>";
			}
		}
		elseif($Armee >0)
		{
			$Division_Armee=GetData("Division","ID",$Divisiono,"Armee");
			if($Division_Armee ==$Armee)$Ordre_ok=true;
		}
	}	
	if($Ordre_ok and $Reg >0)
	{
        $_SESSION['reg']=$Reg;
		//$Division=Insec($_POST['div']);
		$Bataillon=Insec($_POST['bat']);
		$Position=Insec($_POST['pos']);
		$Zone=Insec($_POST['zone']);
		$Lieu=Insec($_POST['cible']);
		$Depot=Insec($_POST['base']);
		$Renforts=Insec($_POST['renf']);
		$Max_Veh=Insec($_POST['Max']);
		$Fret=Insec($_POST['fret']);
		$Fret_d=Insec($_POST['fretd']);
		$Decharge=Insec($_POST['Dech']);
		$Rally=Insec($_POST['rally']);
		$Revendiquer=Insec($_POST['rev']);
		$Lieu_rally=Insec($_POST['Lieu']);
		$Reg_div=Insec($_POST['Reg_div']);
		$Conso_Mun=Insec($_POST['conso_mun']);
		$Reset=Insec($_POST['reset']);
		$Cible=Insec($_POST['Cible_dem']);
		$Type_Dem=Insec($_POST['Type_dem']);
        $Reg_gr=Insec($_POST['Reg_gr']);
		$CT_Action=Insec($_POST['CT']);
		$Retraite=Get_Retraite($Front,$country,30);
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$result3=mysqli_query($con,"SELECT r.Vehicule_ID,r.Vehicule_Nbr,r.Experience,r.Lieu_ID,r.Position,r.Placement,r.Division,r.Transit_Veh,r.Autonomie,r.Move,r.Ravit,r.Skill,r.Matos,r.HP,r.objectif,r.Camouflage,r.Visible,
		c.Categorie,c.Type,c.Taille,c.Vitesse,c.Conso,c.HP as HP_max,l.NoeudF_Ori,l.Port_Ori,l.Flag,l.Flag_Gare,l.Mines_m,l.Meteo,l.Stock_Essence_1,l.Recce_mines_m_ax,l.Recce_mines_m_al
		FROM Regiment_IA as r,Lieu as l,Cible as c WHERE r.Lieu_ID=l.ID AND r.Vehicule_ID=c.ID AND r.ID='$Reg'")
		or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : gemiago-reg');
		mysqli_close($con);
		if($result3)
		{
			while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
			{
				$Type_Veh=$data['Type'];
				$Categorie=$data['Categorie'];
				$Vitesse=$data['Vitesse'];
				$Taille=$data['Taille'];
                $Camouflage=$data['Camouflage'];
                $Visible=$data['Visible'];
				$HP=$data['HP'];
				$HP_max=$data['HP_max'];
				$Meteo=$data['Meteo'];
				$Port_Ori_Base=$data['Port_Ori'];
				$NoeudF_Ori_Base=$data['NoeudF_Ori'];
				$Flag_Base=$data['Flag'];
				$Flag_Gare_Base=$data['Flag_Gare'];
				$Mines_m=$data['Mines_m'];
				$Veh=$data['Vehicule_ID'];
				$Veh_Nbr=$data['Vehicule_Nbr'];
				$Experience=$data['Experience'];
				$Lieu_ID=$data['Lieu_ID'];
				$Division_reg=$data['Division'];
				$Position_ori=$data['Position'];
				$Placement_ori=$data['Placement'];
				$Autonomie=$data['Autonomie'];
				$Transit_Veh=$data['Transit_Veh'];
				$Move=$data['Move'];
				$Ravit=$data['Ravit'];
				$Skill=$data['Skill'];
				$Matos=$data['Matos'];
				$Conso=$data['Conso'];
				$Stock_Essence_1=$data['Stock_Essence_1'];
				$Recce_mines_m_ax=$data['Recce_mines_m_ax'];
				$Recce_mines_m_al=$data['Recce_mines_m_al'];
                $objectif=$data['objectif'];
				if($Transit_Veh ==5000)$Veh=5000;
				$Placement=$Placement_ori;
			}
			mysqli_free_result($result3);
			if($Autonomie >0)$Autonomie_txt=",Autonomie=Autonomie-1";
		}
        /*if($Division >0)
        {
            if($Division ==9999)
            {
                $queryd="UPDATE Regiment_IA SET Division=NULL,Bataillon=NULL WHERE ID='$Reg'";
                $div_dest_txt="l'état-major";
            }
            else
            {
                $queryd="UPDATE Regiment_IA SET Division='$Division',Bataillon=NULL WHERE ID='$Reg'";
                $div_dest_txt="une division";
            }
            $con=dbconnecti();
            $reset_d=mysqli_query($con,$queryd);
            mysqli_close($con);
            if($reset_d)
                $_SESSION['msg']='La Compagnie a été affectée à '.$div_dest_txt.'.<br>Elle a également été retirée de son ancien bataillon.';
            else
                $mes='<div class="alert alert-danger">[Erreur]</div>';
            //$img="<img src='images/em".$country.".jpg' style='width:100%;'>";
            header( 'Location : ./index.php?view=ground_em_ia');
        }*/
        if($Bataillon >0)
        {
            if($Bataillon ==9999)
            {
                $Bataillon=0;
                $mes='La Compagnie a été affectée à l\'état-major';
            }
            else
                $mes='La Compagnie a été affectée à un Bataillon';
            $con=dbconnecti();
            $reset_d=mysqli_query($con,"UPDATE Regiment_IA SET Bataillon='$Bataillon' WHERE ID='$Reg'");
            mysqli_close($con);
            //$img="<img src='images/em".$country.".jpg' style='width:100%;'>";
            $_SESSION['msg'] = $mes;
            header( 'Location : index.php?view=ground_em_ia');
        }
		elseif($Reset ==3)
		{
			SetData("Regiment_IA","Mission_Lieu_D",0,"ID",$Reg);
			SetData("Regiment_IA","Mission_Type_D",0,"ID",$Reg);
            $_SESSION['msg'] = 'Vous annulez la demande de mission en cours!';
            header( 'Location : ./index.php?view=ground_em_ia');
		}
		elseif($Reset ==5)
		{			
			SetData("Regiment_IA","Mission_Lieu_D",$Cible,"ID",$Reg);
			SetData("Regiment_IA","Mission_Type_D",$Type_Dem,"ID",$Reg);
            $_SESSION['msg'] = 'Votre demande de mission aérienne a été validée.';
            header( 'Location : ./index.php?view=ground_em_ia');
		}
		elseif($Reset ==6 and !$Move)
		{
			if($Division_reg >0 and $Lieu >0 and $Credits >=24)
			{
				$Front_Dest=GetFrontByCoord($Lieu);
				if($OfficierID >0)
					UpdateData("Officier","Credits",-24,"ID",$OfficierID);
				elseif($OfficierEMID >0)
					UpdateData("Officier_em","Credits",-24,"ID",$OfficierEMID);
				$con=dbconnecti();
				$reset1=mysqli_query($con,"UPDATE Division SET Front='$Front_Dest',Base='$Lieu' WHERE ID='$Division_reg'");
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Front='$Front_Dest',Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-success">La base arrière de la division a été changée avec succès!<br>Elle opère à présent sur le front '.GetFront($Front_Dest).'</div>';
			}
		}
		elseif($Reset ==7 and !$Move)
		{
			//$img="<img src='images/dechargement.jpg' style='width:50%;'>";
			$con=dbconnecti();
			$reset1=mysqli_query($con,"UPDATE Regiment_IA SET Position=4,Placement=4,Camouflage=1,Move=1,Visible=0,Transit_Veh=0 WHERE ID='$Reg'");
			mysqli_close($con);
            $_SESSION['msg'] = 'Les troupes débarquent dans le port.';
            header( 'Location : ./index.php?view=ground_em_ia');
		}
		elseif($Reset ==8 and !$Move)
		{			
			$Vis_Deb=1;
			$mes='<div class="alert alert-warning">Les troupes débarquent sur les plages!</div>';
			$img="<img src='images/debarquement.jpg' style='width:50%;'>";
			$con=dbconnecti();
			if(!$Type_Veh)$Type_Veh=mysqli_result(mysqli_query($con,"SELECT Type FROM Cible WHERE ID='$Veh'"),0);
			$Faction_lieu=mysqli_result(mysqli_query($con,"SELECT p.Faction FROM Pays as p,Lieu as l WHERE l.Flag=p.ID AND l.ID='$Lieu_ID'"),0);
			if($Type_Veh ==92)$Vis_Deb=0;
			$reset1=mysqli_query($con,"UPDATE Regiment_IA SET Position=4,Placement=11,Camouflage=1,Move=1,Visible=".$Vis_Deb.",Transit_Veh=0 WHERE ID='$Reg'");
			mysqli_close($con);
			if($Type_Veh !=92 and $Faction !=$Faction_lieu)
			{
				$Heure=date('H');
				AddEventFeed(118,$Veh,$OfficierID,$Reg,$Depot,$Veh_Nbr,$Heure);
			}
		}
		elseif($Reset ==9 and !$Move)
		{
			$img="<img src='images/retreat.jpg' style='width:50%;'>";
			if(!$Max_Veh)
				$Veh_final=0;
			elseif($Max_Veh >25)
				$Veh_final=10;
			else
				$Veh_final=1;
			$con=dbconnecti();
			$reset1=mysqli_query($con,"UPDATE Regiment_IA SET Vehicule_Nbr=".$Veh_final.",`Position`=6,HP=HP*0.5,Camouflage=1,Atk=0,Atk_Eni=0,Atk_time=NOW() - INTERVAL 1 DAY,Move_time=NOW() - INTERVAL 1 DAY WHERE ID='$Reg'");
			mysqli_close($con);
            $_SESSION['msg'] = '<div class="alert alert-warning">Une arrière-garde est sacrifiée pour que l\'unité puisse fuir.</div>';
            header( 'Location : ./index.php?view=ground_em_ia');
		}
        elseif(!$Move and $Renforts){
            if($Renforts ==2)
            {
                $CT_cale=4;
                $con=dbconnecti();
                $result1=mysqli_query($con,"SELECT HP,Type FROM Cible WHERE ID='$Veh'");
                if($Type_Veh>17 and $Type_Veh<22){
                    $coules=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Ground_Cbt WHERE Veh_b='$Veh'"),0);
                    if($coules >0)$CT_cale=40;
                }
                mysqli_close($con);
                if($result1){
                    while($dataa=mysqli_fetch_array($result1,MYSQLI_ASSOC)){
                        $HP_max=$dataa['HP'];
                        $Type_veh=$dataa['Type'];
                    }
                    mysqli_free_result($result1);
                    unset($dataa);
                }
                if($Credits >=$CT_cale)
                {
                    UpdateData("Regiment_IA","HP",5000,"ID",$Reg,$HP_max);
                    UpdateData("Regiment_IA","Experience",-10,"ID",$Reg);
                    $con=dbconnecti();
                    $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Placement=4,Position=34,Moral=100,Move=1 WHERE ID='$Reg'");
                    $HP_actu=mysqli_result(mysqli_query($con,"SELECT HP FROM Regiment_IA WHERE ID='$Reg'"),0);
                    mysqli_close($con);
                    if($Veh ==5001 or $Veh ==5124 or $Type_veh ==37 or $Type_veh ==14)
                        SetData("Regiment_IA","Vehicule_Nbr",1,"ID",$Reg);
                    elseif($HP_actu ==$HP_max and $Type_veh >14 and $Type_veh <39)
                    {
                        if($Type_veh ==15 and $Veh_Nbr <4)
                            UpdateData("Regiment_IA","Vehicule_Nbr",1,"ID",$Reg,4);
                        elseif($Type_veh ==16 and $Veh_Nbr <3)
                            UpdateData("Regiment_IA","Vehicule_Nbr",1,"ID",$Reg,3);
                        elseif($Type_veh ==17 and $Veh_Nbr <2)
                            UpdateData("Regiment_IA","Vehicule_Nbr",1,"ID",$Reg,2);
                        elseif($Type_veh ==18 or $Type_veh ==19 or $Type_veh ==20 or $Type_veh ==21)
                            SetData("Regiment_IA","Vehicule_Nbr",1,"ID",$Reg);
                    }
                    if($OfficierID >0)
                        UpdateData("Officier","Credits",-$CT_cale,"ID",$OfficierID);
                    elseif($OfficierEMID >0)
                        UpdateData("Officier_em","Credits",-$CT_cale,"ID",$OfficierEMID);
                    $_SESSION['msg'] = 'Le navire a été réparé';
                }
                else
                    $_SESSION['msg_red'] = 'Le navire n\'a pas pu être réparé par manque de temps!';
                header( 'Location : ./index.php?view=ground_em_ia');
            }
            elseif($Renforts ==3)
            {
                if($Credits >=4)
                {
                    $con=dbconnecti();
                    $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Vehicule_Nbr=1,Moral=100,Experience=250,Visible=0,Move=1 WHERE ID='$Reg'");
                    mysqli_close($con);
                    if($OfficierID >0)
                        UpdateData("Officier","Credits",-4,"ID",$OfficierID);
                    elseif($OfficierEMID >0)
                        UpdateData("Officier_em","Credits",-4,"ID",$OfficierEMID);
                    $_SESSION['msg'] = 'Le train a été réparé';
                }
                else
                    $_SESSION['msg_red'] = 'Le train n\'a pas pu être réparé par manque de temps!';
                header( 'Location : ./index.php?view=ground_em_ia');
            }
            elseif($Renforts ==5)
            {
                if($Credits >=4)
                {
                    $con=dbconnecti();
                    if($Conso)$reset_l=mysqli_query($con,"UPDATE Lieu SET Stock_Essence_1=Stock_Essence_1-".$Conso." WHERE ID='$Lieu_ID'");
                    $reset_r=mysqli_query($con,"UPDATE Regiment_IA as r,Cible as c SET r.Visible=0,r.Move=1,r.Autonomie=c.Autonomie,r.Avions=c.Hydra_Nbr WHERE r.Vehicule_ID=c.ID AND r.ID='$Reg'");
                    mysqli_close($con);
                    if($OfficierID >0)
                        UpdateData("Officier","Credits",-4,"ID",$OfficierID);
                    elseif($OfficierEMID >0)
                        UpdateData("Officier_em","Credits",-4,"ID",$OfficierEMID);
                    $_SESSION['msg'] = 'Le navire a été ravitaillé.<br>'.$Conso.'L de Diesel ont été transférés du dépôt.';
                }
                else
                    $_SESSION['msg_red'] = 'Le navire n\'a pas pu être ravitaillé par manque de temps!';
                header( 'Location : ./index.php?view=ground_em_ia');
            }
            elseif($Renforts ==4)
            {
                $con=dbconnecti();
                $result1=mysqli_query($con,"SELECT HP,Type FROM Cible WHERE ID='$Veh'");
                mysqli_close($con);
                if($result1)
                {
                    while($dataa=mysqli_fetch_array($result1,MYSQLI_ASSOC))
                    {
                        $HP_max=$dataa['HP'];
                        $Type_veh=$dataa['Type'];
                    }
                    mysqli_free_result($result1);
                    unset($dataa);
                }
                if($Credits >=$CT_MAX)
                {
                    UpdateData("Regiment_IA","HP",5000,"ID",$Reg,$HP_max);
                    if($OfficierID >0)
                        UpdateData("Officier","Credits",-$CT_MAX,"ID",$OfficierID);
                    elseif($OfficierEMID >0)
                        UpdateData("Officier_em","Credits",-$CT_MAX,"ID",$OfficierEMID);
                    $con=dbconnecti();
                    $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Vehicule_Nbr=1,Placement=4,Position=34,Moral=100,Experience=Experience-10,Move=1 WHERE ID='$Reg'");
                    mysqli_close($con);
                    $_SESSION['msg'] = 'Le navire a été mis en cale sèche en vue d\'être radoubé.';
                }
                else
                    $_SESSION['msg_red'] = 'Le navire n\'a pas pu être réparé par manque de temps!';
                header( 'Location : ./index.php?view=ground_em_ia');
            }
            elseif($Renforts ==1 and $Max_Veh >0)
            {
                $con=dbconnecti();
                $result3=mysqli_query($con,"SELECT Reput,Usine1,Usine2,Usine3,Stock FROM Cible WHERE ID='$Veh'")
                or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : gemiago-veh');
                mysqli_close($con);
                if($result3)
                {
                    while($data=mysqli_fetch_array($result3,MYSQLI_ASSOC))
                    {
                        $CT_Renf=$data['Reput'];
                        $CT_Usine1=$data['Usine1'];
                        $CT_Usine2=$data['Usine2'];
                        $CT_Usine3=$data['Usine3'];
                        $Stock=floor($data['Stock']);
                    }
                    mysqli_free_result($result3);
                }
                if($Lieu_ID ==$CT_Usine1 or $Lieu_ID ==$CT_Usine2 or $Lieu_ID ==$CT_Usine3)$CT_Renf=1;
                if($Credits >=$CT_Renf)
                {
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
                    $con=dbconnecti();
                    $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Moral=100,Visible=0,Move=1,Position=0,Move_time='0000-00-00 00:00:00' WHERE ID='$Reg'");
                    mysqli_close($con);
                    if($reset_r)
                    {
                        UpdateData("Regiment_IA","Vehicule_Nbr",$up_renf,"ID",$Reg,$Max_Veh);
                        if($Experience >50)
                        {
                            if($down_exp <1)$down_exp=1;
                            UpdateData("Regiment_IA","Experience",-$down_exp,"ID",$Reg);
                        }
                        if($OfficierID >0)
                            UpdateData("Officier","Credits",-$CT_Renf,"ID",$OfficierID);
                        elseif($OfficierEMID >0)
                            UpdateData("Officier_em","Credits",-$CT_Renf,"ID",$OfficierEMID);
                        $_SESSION['msg'] = 'La Compagnie a été renforcée';
                    }
                    else
                        $_SESSION['msg_red'] = 'La Compagnie n\'a pas pu être renforcée!';
                }
                else
                    $_SESSION['msg_red'] = 'La Compagnie n\'a pas pu être renforcée par manque de temps!';
                header( 'Location : ./index.php?view=ground_em_ia');
            }
        }
        elseif(!$Move and $Reg_gr)
        {
            $con=dbconnecti();
            $reset_r=mysqli_query($con,"UPDATE Regiment_IA as r,Cible as c SET r.Autonomie=c.Autonomie WHERE r.Vehicule_ID=c.ID AND r.ID='$Reg_gr'");
            $reset_r2=mysqli_query($con,"UPDATE Regiment_IA SET Move=1 WHERE ID='$Reg'");
            mysqli_close($con);
            $_SESSION['msg'] = 'Le navire a été ravitaillé';
            header( 'Location : ./index.php?view=ground_em_ia');
        }
        else
        {
            $Tactique=$Experience/20;
            if(!$Camouflage)$Camouflage=1;
            elseif($Matos ==11)$Camouflage*=1.1;
            if($Skill ==29 or $Skill ==25 or $Skill ==6)$Camouflage*=1.1;
            elseif($Skill ==126 or $Skill ==129 or $Skill ==51)$Camouflage*=1.2;
            elseif($Skill ==127 or $Skill ==130 or $Skill ==80)$Camouflage*=1.3;
            elseif($Skill ==128 or $Skill ==131 or $Skill ==81)$Camouflage*=1.4;
            $Cam=$Taille/$Camouflage;
            if($Cam <1)$Cam=1;
            if($Lieu >0 and !$Move)
            {
                if(strpos($Lieu,"_") !==false)  //Déroute
                {
                    $Lieu=strstr($Lieu,'_',true);
                    $con=dbconnecti();
                    $reset_ret=mysqli_query($con,"UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Position=6,Moral=Moral-10,Autonomie=0,Atk_Eni=0 WHERE ID='$Reg'");
                    mysqli_close($con);
                }
                $Move_on=true;
                $txt_cie='La Compagnie';
                if($Veh ==424)$Placement=3; //Train
                elseif($Veh >4999) //Navires
                {
                    if($Mines_m >0 and $Veh_Nbr >0) //Mines marines
                    {
                        $Malus_Mines=($Taille-$Experience)/2;
                        if($Categorie ==19)
                            $Malus_Mines-=50;
                        elseif($Categorie ==25)
                            $Malus_Mines-=25;
                        if($Recce_mines_m_ax)
                        {
                            if(IsAxe($country))
                                $Malus_Mines-=50;
                        }
                        elseif($Recce_mines_m_al)
                        {
                            if(IsAllie($country))
                                $Malus_Mines-=50;
                        }
                        if((mt_rand(0,$Mines_m)-$Malus_Mines-$Meteo) <$Mines_m)
                        {
                            if($Malus_Mines <1)$Malus_Mines=1;
                            $mes.="<div class='alert alert-danger'>Le navire percute une mine!</div>";
                            $Degats_mine=mt_rand(500,2000)*$Malus_Mines;
                            if($Degats_mine <500)$Degats_mine=mt_rand(250,500);
                            if($Degats_mine >=$HP)
                            {
                                if($Veh_Nbr >1)
                                    $query_mine="UPDATE Regiment_IA SET Vehicule_Nbr=Vehicule_Nbr-1,Experience=Experience-50,Autonomie=Autonomie-1,HP='$HP_Max' WHERE ID='$Reg'";
                                else{
                                    $query_mine="UPDATE Regiment_IA SET Vehicule_Nbr=0,Experience=0,Skill=0,Position=6,Autonomie=0,Atk_Eni=0,HP=0,Fret=0,Fret_Qty=0 WHERE ID='$Reg'";
                                    $Navire_out=true;
                                }
                                $con=dbconnecti();
                                $reset_ret=mysqli_query($con,$query_mine);
                                mysqli_close($con);
                            }
                            else
                                UpdateData("Regiment_IA","HP",-$Degats_mine,"ID",$Reg);
                        }
                    }
                    if($Navire_out)  //Blocus
                        $Move_on=false;
                    else
                    {
                        $No_Blocus_Naval=true;
                        if($Skill ==35)
                            $Briseur_Blocus=50;
                        elseif($Skill ==144)
                            $Briseur_Blocus=60;
                        elseif($Skill ==145)
                            $Briseur_Blocus=70;
                        elseif($Skill ==146)
                            $Briseur_Blocus=80;
                        $con=dbconnecti();
                        if($Port_Ori_Dest)$resulti_dest=mysqli_query($con,"SELECT COUNT(*),MAX(c.Vitesse),r.Skill FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Position=27 AND c.Type>14 AND r.Vehicule_Nbr>0 AND c.Vitesse>35 AND r.HP>c.HP/2");
                        $resultsmoke=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction='$Faction' AND r.Lieu_ID='$Depot' AND r.Position=37 AND c.Arme_Art>0 AND r.Vehicule_Nbr>0"),0);
                        $resulti=mysqli_query($con,"SELECT COUNT(*),MAX(c.Vitesse),r.Skill FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Depot' AND r.Position=27 AND c.Type>14 AND r.Vehicule_Nbr>0 AND c.Vitesse>35 AND r.HP>c.HP/2");
                        //$resulti2=mysqli_query($con,"SELECT COUNT(*),MAX(c.Vitesse) FROM Regiment as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Lieu' AND r.Position=27 AND c.Type>14 AND r.Vehicule_Nbr>0 AND c.Vitesse>35 AND r.HP>c.HP/2");
                        //$Embout2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Lieu_ID='$Lieu' AND r.Pays=p.ID AND p.Faction='$Faction' AND r.Placement=8 AND r.Position<>25 AND r.Vehicule_Nbr >0"),0);
                        $Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND p.Faction='$Faction' AND r.Placement=8 AND r.Position<>25 AND r.Vehicule_Nbr >0"),0);
                        $resultv=mysqli_query($con,"SELECT ValeurStrat,Zone,Port_Ori,Pont_Ori,NoeudF_Ori,Flag,Flag_Gare FROM Lieu WHERE ID='$Lieu'");
                        mysqli_close($con);
                        if($resultv)
                        {
                            while($data2=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
                            {
                                $Pont_Dest=$data2['Pont_Ori'];
                                $Port_Ori_Dest=$data2['Port_Ori'];
                                $NoeudF_Dest=$data2['NoeudF_Ori'];
                                $Flag_Gare_Dest=$data2['Flag_Gare'];
                                $Flag_Dest=$data2['Flag'];
                                $ValeurStrat=$data2['ValeurStrat'];
                                $Zone_lieu=$data2['Zone'];
                            }
                            mysqli_free_result($resultv);
                        }
                        //Tenter de passer l'Interdiction
                        if($resulti)
                        {
                            while($datai=mysqli_fetch_array($resulti,MYSQLI_NUM))
                            {
                                $Enis_Interdiction=$datai[0];
                                $Speedi=$datai[1];
                                if($datai[2] ==34 or $datai[2] ==141 or $datai[2] ==142 or $datai[2] ==143)
                                {
                                    $Skill_inter[]=$datai[2];
                                    $Speedi+=5;
                                }
                                elseif($datai[2] ==33 or $datai[2] ==138 or $datai[2] ==139 or $datai[2] ==140)
                                    $Skill_bloc[]=$datai[2];
                            }
                            mysqli_free_result($resulti);
                        }
                        if($Enis_Interdiction)
                        {
                            if($resultsmoke)$_SESSION['msg'] .= '<p>Les navires alliés produisent un écran de fumée</p>';
                            if(is_array($Skill_inter))$Skill_Interd=max(array_filter($Skill_inter));
                            if(is_array($Skill_bloc))$Skill_Blocus=max(array_filter($Skill_bloc));
                            if($Skill_Interd and $Briseur_Blocus >49)
                            {
                                if($Skill_Interd ==143)
                                    $Skill_Inter_Pc=80;
                                elseif($Skill_Interd ==142)
                                    $Skill_Inter_Pc=70;
                                elseif($Skill_Interd ==141)
                                    $Skill_Inter_Pc=60;
                                elseif($Skill_Interd ==34)
                                    $Skill_Inter_Pc=50;
                                if(mt_rand(0,100)<=$Skill_Inter_Pc)$Briseur_Blocus=false;
                            }
                            if($Briseur_Blocus >49 and mt_rand(0,100)<=$Briseur_Blocus)$Blocus_Brise=true;
                            if(($Vitesse >=40 and $resultsmoke and $HP >($HP_max/2)) or $Blocus_Brise)$Enis_Interdiction=false;
                            if(($Type_Veh==14 or $Transit_Veh==5000) and $Skill_Blocus and $Port_Ori_Base and !$Blocus_Brise)
                            {
                                if($Skill_Blocus ==143)
                                    $Skill_Blocus_Pc=80;
                                elseif($Skill_Blocus ==142)
                                    $Skill_Blocus_Pc=70;
                                elseif($Skill_Blocus ==141)
                                    $Skill_Blocus_Pc=60;
                                elseif($Skill_Blocus ==34)
                                    $Skill_Blocus_Pc=50;
                                if(mt_rand(0,100)<=$Skill_Blocus_Pc)
                                    $No_Blocus_Naval=false;
                            }
                            if(!$No_Blocus_Naval)
                            {
                                $_SESSION['msg_red'] = 'Le port est soumis à un blocus, aucun appareillage n\'est possible!';
                                $Bloque=true;
                            }
                            elseif($Enis_Interdiction and $Speedi >=$Vitesse and $Vehicule_Nbr >0 and $No_Blocus_Naval)
                            {
                                $_SESSION['msg_red'] = 'Les navires ennemis en interdiction empêchent tout déplacement!';
                                $Bloque=true;
                            }
                            else
                                $_SESSION['msg'] .= 'Votre flottille parvient à quitter la zone malgré la présence de navires ennemis!';
                        }
                        elseif($Port_Ori_Dest) //uniquement vers un port
                        {
                            $No_Blocus_Naval_Dest=true;
                            if($resulti_dest)
                            {
                                while($datai=mysqli_fetch_array($resulti_dest,MYSQLI_NUM))
                                {
                                    $Enis_Interdiction_dest=$datai[0];
                                    $Speedi_dest=$datai[1];
                                    if($datai[2] ==34 or $datai[2] ==141 or $datai[2] ==142 or $datai[2] ==143)
                                    {
                                        $Skill_inter_dest[]=$datai[2];
                                        $Speedi_dest+=5;
                                    }
                                    elseif($datai[2] ==33 or $datai[2] ==138 or $datai[2] ==139 or $datai[2] ==140)
                                        $Skill_bloc_dest[]=$datai[2];
                                }
                                mysqli_free_result($resulti_dest);
                            }
                            if($Enis_Interdiction_dest)
                            {
                                if(is_array($Skill_inter_dest))$Skill_Interd_dest=max(array_filter($Skill_inter_dest));
                                if(is_array($Skill_bloc_dest))$Skill_Blocus_dest=max(array_filter($Skill_bloc_dest));
                                if($Skill_Interd_dest and $Briseur_Blocus >49)
                                {
                                    if($Skill_Interd_dest ==143)
                                        $Skill_Inter_Pc=80;
                                    elseif($Skill_Interd_dest ==142)
                                        $Skill_Inter_Pc=70;
                                    elseif($Skill_Interd_dest ==141)
                                        $Skill_Inter_Pc=60;
                                    elseif($Skill_Interd_dest ==34)
                                        $Skill_Inter_Pc=50;
                                    if(mt_rand(0,100)<=$Skill_Inter_Pc)$Briseur_Blocus=false;
                                }
                                if($Briseur_Blocus >49 and mt_rand(0,100)<=$Briseur_Blocus)$Blocus_Brise_dest=true;
                                if(($Vitesse >=40 and $resultsmoke and $HP >($HP_max/2)) or $Blocus_Brise_dest)$Enis_Interdiction_dest=false;
                                if(($Type_Veh==14 or $Transit_Veh==5000) and $Skill_Blocus and !$Blocus_Brise_dest)
                                {
                                    if($Skill_Blocus ==143)
                                        $Skill_Blocus_Pc=80;
                                    elseif($Skill_Blocus ==142)
                                        $Skill_Blocus_Pc=70;
                                    elseif($Skill_Blocus ==141)
                                        $Skill_Blocus_Pc=60;
                                    elseif($Skill_Blocus ==34)
                                        $Skill_Blocus_Pc=50;
                                    if(mt_rand(0,100)<=$Skill_Blocus_Pc)
                                        $No_Blocus_Naval_Dest=false;
                                }
                                if(!$No_Blocus_Naval_Dest)
                                {
                                    $_SESSION['msg_red'] = 'Le port de destination est soumis à un blocus que votre flottille n\'a pas été capable de forcer!';
                                    $Bloque=true;
                                }
                                elseif($Enis_Interdiction_dest and $Speedi_dest >=$Vitesse and $Vehicule_Nbr >0 and $No_Blocus_Naval_Dest)
                                {
                                    $_SESSION['msg_red'] = 'Les navires ennemis en interdiction empêchent tout déplacement vers le port de destination!';
                                    $Bloque=true;
                                }
                                else
                                    $_SESSION['msg'] .= 'Votre flottille pénètre dans le port malgré la présence de navires ennemis!';
                            }
                        }
                        else
                        {
                            $Embout+=$Embout2+1;
                            $Embout_max=GetEmboutMax($ValeurStrat,$Placement,$Zone_lieu,$Front);
                            if($Embout >$Embout_max)
                            {
                                $_SESSION['msg_red']='La présence d\'un trop grand nombre de navires ne vous permet pas de rejoindre la zone!';
                                $Move_on=false;
                            }
                            else
                            {
                                $Placement=8;
                                $txt_cie="Le Navire";
                            }
                        }
                        if($Move_on){ //PA
                            if($Veh >5100 and $Type_Veh ==21) {
                                $con=dbconnecti();
                                //$reset2=mysqli_query($con,"UPDATE Pilote_IA as p,Unit as u SET p.Cible=0,p.Couverture=0,p.Couverture_Nuit=0,p.Escorte=0,p.Avion=0,p.Alt=0,p.Task=0,u.Base='$Lieu' WHERE p.Unit=u.ID AND u.Porte_avions='$Veh'");
                                $reset2=mysqli_query($con,"UPDATE Pilote_IA as p,Unit as u SET p.Cible=0,p.Couverture=0,p.Couverture_Nuit=0,p.Escorte=0,p.Avion=0,p.Alt=0,p.Task=0 WHERE p.Unit=u.ID AND u.Porte_avions='$Veh'");
                                $reset3=mysqli_query($con,"UPDATE Unit SET Base='$Lieu',Recce=0 WHERE Etat=1 AND Porte_avions='$Veh'");
                                mysqli_close($con);
                                $_SESSION['msg'].='<p>Les escadrilles embarquées suivent le porte-avions!</p>';
                            }
                        }
                    }
                }
                else
                {
                    $Placement=0;
                    if($Lieu !=$Retraite and $Retraite)
                    {
                        $con=dbconnecti();
                        $resultv=mysqli_query($con,"SELECT ValeurStrat,Zone,Pont_Ori,NoeudF_Ori,Flag,Flag_Gare,Meteo FROM Lieu WHERE ID='$Lieu'");
                        if($resultv)
                        {
                            while($data2=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
                            {
                                $Pont_Dest=$data2['Pont_Ori'];
                                $NoeudF_Dest=$data2['NoeudF_Ori'];
                                $Flag_Gare_Dest=$data2['Flag_Gare'];
                                $Flag_Dest=$data2['Flag'];
                                $Meteo_Dest=$data2['Meteo'];
                                $ValeurStrat=$data2['ValeurStrat'];
                                $Zone_lieu=$data2['Zone'];
                            }
                            mysqli_free_result($resultv);
                        }
                        switch($Zone_lieu)
                        {
                            case 2: case 3: case 5: case 7: case 10:
                                $Cam_zone=30;
                                break;
                            case 4:
                                $Cam_zone=20;
                                break;
                            case 1: case 11:
                                $Cam_zone=10;
                                break;
                            case 9:
                                $Cam_zone=50;
                                break;
                            default:
                                $Cam_zone=0;
                                break;
                        }
                        if($Placement_ori ==3 and $NoeudF_Ori_Base and $NoeudF_Dest and $Flag_Gare_Base ==$Flag_Gare_Dest and $Flag_Base ==$Flag_Dest)$Placement=3; //Gare alliée
                        elseif($Pont_Dest)$Placement=5;
                        //$Embout2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND p.Faction='$Faction'"),0);
                        $Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND p.Faction='$Faction'"),0);
                        if($Placement !=8 and $Placement !=9)
                            $Sentinelles=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND p.Faction!='$Faction'"),0);
                        mysqli_close($con);
                        $Embout+=$Embout2+1;
                        if($Embout >GetEmboutMax($ValeurStrat,$Placement,$Zone_lieu,$Front))
                            $Move_on=false;
                    }
                }
                if($Revendiquer ==2 or $Revendiquer ==3)
                {
                    $con=dbconnecti();
                    $Cie_eni=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement_ori' AND r.Vehicule_Nbr >0 AND p.Faction!='$Faction'"),0);
                    if(!$Cie_eni){
                        $resultv=mysqli_query($con,"SELECT Pays,ValeurStrat FROM Lieu WHERE ID='$Lieu'");
                        if($resultv)
                        {
                            while($data2=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
                            {
                                $Pays_Ori=$data2['Pays'];
                                $ValeurStrat=$data2['ValeurStrat'];
                            }
                            mysqli_free_result($resultv);
                        }
                        $Faction_Ori=mysqli_result(mysqli_query($con,"SELECT `Faction` FROM Pays WHERE ID='$Pays_Ori'"),0);
                        if($Faction ==$Faction_Ori)
                            $Pays_Rev=$Pays_Ori;
                        else
                        {
                            /*if($country ==15 or $country ==18 or $country ==19)
                                $Pays_Rev=1;
                            elseif($country ==10)
                                $Pays_Rev=2;
                            else*/
                                $Pays_Rev=$country;
                        }
                        if($Pays_Rev >0 and $Lieu >0)
                        {
                            if($Placement_ori ==1)
                                SetData("Lieu","Flag_Air",$Pays_Rev,"ID",$Lieu);
                            elseif($Placement_ori ==2)
                                SetData("Lieu","Flag_Route",$Pays_Rev,"ID",$Lieu);
                            elseif($Placement_ori ==3)
                                SetData("Lieu","Flag_Gare",$Pays_Rev,"ID",$Lieu);
                            elseif($Placement_ori ==4)
                                SetData("Lieu","Flag_Port",$Pays_Rev,"ID",$Lieu);
                            elseif($Placement_ori ==5)
                                SetData("Lieu","Flag_Pont",$Pays_Rev,"ID",$Lieu);
                            elseif($Placement_ori ==6)
                                SetData("Lieu","Flag_Usine",$Pays_Rev,"ID",$Lieu);
                            elseif($Placement_ori ==7)
                                SetData("Lieu","Flag_Radar",$Pays_Rev,"ID",$Lieu);
                            elseif($Placement_ori ==11)
                                SetData("Lieu","Flag_Plage",$Pays_Rev,"ID",$Lieu);
                            else {
                                $resetl=mysqli_query($con,"UPDATE Lieu SET Flag='$Pays_Rev' WHERE ID='$Lieu'");
                                //Reset Base arrière des unités ennemies dont la base arrière est sur le lieu
                                $reset_armee = "UPDATE Armee a
                                        INNER JOIN Pays p ON a.Pays = p.ID AND a.Front = p.Front
                                        LEFT JOIN Lieu l ON l.Flag = a.Pays AND l.ValeurStrat > 3
                                        SET Base = 
                                        CASE
                                            WHEN p.Base_Arriere > 0 
                                              THEN p.Base_Arriere
                                              ELSE a.Base_Ori
                                        END
                                        WHERE a.Base = $Lieu AND p.Faction != $Faction";
                                $reset_div = "UPDATE Division d
                                        INNER JOIN Pays p ON d.Pays = p.ID AND d.Front = p.Front
                                        LEFT JOIN Armee a ON d.Armee = a.ID
                                        SET Base = 
                                        CASE
                                            WHEN p.Base_Arriere > 0 
                                              THEN p.Base_Arriere 
                                              ELSE 
                                                CASE
                                                  WHEN a.Base > 0 THEN a.Base ELSE d.Base_Ori
                                                END
                                        END
                                        WHERE d.Base = $Lieu AND p.Faction != $Faction";
                                $reset_baarmee=mysqli_query($con,$reset_armee);
                                $reset_badiv=mysqli_query($con,$reset_div);
                            }
                            AddEventFeed(44,$Pays_Rev,$Placement_ori,$Reg,$Lieu,$Faction);
                            $img="<img src='images/capture_flag.jpg' style='width:50%;'>";
                            $reset=mysqli_query($con,"UPDATE Regiment_IA SET Move=1,Move_time=NOW() WHERE ID='$Reg'");
                            mysqli_close($con);
                            $_SESSION['msg'] = 'Vos troupes revendiquent le lieu!';
                            header( 'Location : ./index.php?view=ground_em_ia');
                        }
                        else
                            $mes="[Erreur gemiago-300] Veuillez signaler cette erreur sur le forum!";
                    }
                    else{
                        $_SESSION['msg_red'] = 'Vous ne pouvez pas revendiquer ce lieu!';
                        header( 'Location : ./index.php?view=ground_em_ia');
                    }
                }
                else
                {
                    if($Move_on and !$Bloque)
                    {
                        if(!$Ravit){
                            if($Veh and $Depot)
                            {
                                $con=dbconnecti();
                                $resultv=mysqli_query($con,"SELECT Carbu_ID,Type FROM Cible WHERE ID='$Veh'");
                                $resultd=mysqli_query($con,"SELECT Longitude,Latitude FROM Lieu WHERE ID='$Depot'");
                                $result=mysqli_query($con,"SELECT Longitude,Latitude FROM Lieu WHERE ID='$Lieu'");
                                mysqli_close($con);
                                if($resultv)
                                {
                                    while($data=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
                                    {
                                        $Carbu=$data['Carbu_ID'];
                                        $Type_Veh=$data['Type'];
                                    }
                                    mysqli_free_result($resultv);
                                    unset($data);
                                }
                                if($result)
                                {
                                    while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
                                    {
                                        $Longitude_dest=$data['Longitude'];
                                        $Latitude_dest=$data['Latitude'];
                                    }
                                    mysqli_free_result($result);
                                    unset($data);
                                }
                                if($resultd)
                                {
                                    while($datad=mysqli_fetch_array($resultd,MYSQLI_ASSOC))
                                    {
                                        $Longitude_base=$datad['Longitude'];
                                        $Latitude_base=$datad['Latitude'];
                                    }
                                    mysqli_free_result($resultd);
                                }
                                if($Carbu >0 and $Veh <5000 and $Veh !=424 and $Veh_Nbr >0 and !$reset_ret)
                                {
                                    if($Front ==3)
                                    {
                                        $Lat_min=$Latitude_base-5;
                                        $Lat_max=$Latitude_base+5;
                                        $Long_min=$Longitude_base-7;
                                        $Long_max=$Longitude_base+7;
                                    }
                                    elseif($Front >0)
                                    {
                                        $Lat_min=$Latitude_base-4;
                                        $Lat_max=$Latitude_base+4;
                                        $Long_min=$Longitude_base-5;
                                        $Long_max=$Longitude_base+5;
                                    }
                                    else
                                    {
                                        $Lat_min=$Latitude_base-2;
                                        $Lat_max=$Latitude_base+2;
                                        $Long_min=$Longitude_base-3;
                                        $Long_max=$Longitude_base+3;
                                        $Long_max=$Longitude_base+3;
                                    }
                                    $Distance_depot=GetDistance(0,0,$Longitude_dest,$Latitude_dest,$Longitude_base,$Latitude_base);
                                    if($Type_Veh ==93)
                                        $Vehicule_Nbr_Conso=ceil($Veh_Nbr/10);
                                    else
                                        $Vehicule_Nbr_Conso=$Veh_Nbr;
                                    $Conso=($Distance_depot[0]*$Vehicule_Nbr_Conso)/5;
                                    if($Carbu ==100)
                                    {
                                        $Stock_var="Stock_Essence_100";
                                        $Octane=" Octane 100";
                                    }
                                    elseif($Carbu ==1)
                                    {
                                        $Stock_var="Stock_Essence_1";
                                        $Octane=" Diesel";
                                    }
                                    elseif($Carbu ==87)
                                    {
                                        $Stock_var="Stock_Essence_87";
                                        $Octane=" Octane 87";
                                    }
                                    $con=dbconnecti();
                                    $getflotted=mysqli_result(mysqli_query($con,"SELECT d.ID FROM Regiment_IA as r,Depots as d,Pays as p WHERE r.Pays=p.Pays_ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu_ID' AND r.ID=d.Reg_ID AND d.".$Stock_var." >='$Conso'"),0);
                                    if(!$getflotted)
                                    {
                                        $getdepot=mysqli_result(mysqli_query($con,"SELECT l.ID FROM Lieu as l,Pays as p WHERE l.Flag=p.Pays_ID AND p.Faction='$Faction' AND
										(l.ID='$Lieu_ID' OR ((l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max'))) AND l.".$Stock_var." >='$Conso' ORDER BY l.".$Stock_var." DESC LIMIT 1"),0);
                                        $resetconso=mysqli_query($con,"UPDATE Lieu SET ".$Stock_var."=".$Stock_var."-'$Conso' WHERE ID='".$getdepot."'");
                                        $reset_depot=mysqli_affected_rows($con);
                                    }
                                    else
                                    {
                                        $resetconso=mysqli_query($con,"UPDATE Depots SET ".$Stock_var."=".$Stock_var."-'$Conso' WHERE ID='".$getflotted."'");
                                        $reset_depot=mysqli_affected_rows($con);
                                    }
                                    mysqli_close($con);
                                    if($getflotted)
                                        $depot_nom='la flottille de ravitaillement au large';
                                    else
                                        $depot_nom="le dépôt de <b>".GetData("Lieu","ID",$getdepot,"Nom")."</b>";
                                    $_SESSION['msg'] .= '<b>'.$Conso.'L '.$Octane.'</b> ont été attribués à l\'unité depuis '.$depot_nom;
                                }
                                else
                                    $reset_depot=1;
                            }
                            else
                                $_SESSION['msg_red'].='<b>[Erreur gemiago-880]</b> Veuillez signaler cette erreur sur le forum!';
                        }
                        else
                            $reset_depot=1;
                        if(!$reset_depot)
                            $_SESSION['msg_red'].='L\'unité ne peut pas se déplacer par manque de'.$Octane.' dans un dépôt proche!';
                        else
                        {
                            if($Type_Veh ==37 and $Matos ==18)
                                $Pos_final=25;
                            else
                                $Pos_final=4;
                            if($Trait ==1)$Moral_txt=",Moral=Moral+10";
                            if($Lieu ==$objectif)$obj_txt=",objectif=NULL";
                            $con=dbconnecti();
                            if($Sentinelles){
                                $Bonus_det=0;
                                if($Flag_Dest ==$country)$Bonus_det=10;
                                $ressenti=mysqli_query($con,"SELECT c.Detection,r.Skill,r.Matos FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND r.Lieu_ID='$Lieu' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0 AND p.Faction!='$Faction'");
                                if($ressenti)
                                {
                                    while($datas=mysqli_fetch_array($ressenti,MYSQLI_ASSOC))
                                    {
                                        $Detec_bonus=$datas['Detection'];
                                        if($datas['Skill'] ==29)
                                            $Bonus_det+=10;
                                        if($datas['Skill'] ==126)
                                            $Bonus_det+=15;
                                        if($datas['Skill'] ==127)
                                            $Bonus_det+=20;
                                        if($datas['Skill'] ==128)
                                            $Bonus_det+=25;
                                        if($datas['Matos'] ==9 or $datas['Matos'] ==13)
                                            $Bonus_det+=5;
                                        if($Bonus_det + mt_rand(0,$Detec_bonus) + $Meteo_Dest > $Cam + mt_rand(0,$Tactique) + $Cam_zone){
                                            $Visible=1;
                                        }
                                    }
                                    mysqli_free_result($ressenti);
                                }
                            }
                            $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Pos_final',Placement='$Placement',Mission_Type_D=0,Mission_Lieu_D=0,Visible='$Visible',Atk_Eni=0,Lieu_ID='$Lieu',Move=1,Move_time=NOW()".$obj_txt.$Moral_txt.$Autonomie_txt." WHERE ID='$Reg'");
                            $result1=mysqli_query($con,"SELECT Zone,Garnison,Port_Ori,ValeurStrat,Meteo,Flag FROM Lieu WHERE ID='$Lieu'");
                            mysqli_close($con);
                            if($result1)
                            {
                                while($data1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
                                {
                                    $Zone_lieu=$data1['Zone'];
                                    $Garnison=$data1['Garnison'];
                                    $Port_Ori=$data1['Port_Ori'];
                                    $ValeurStrat=$data1['ValeurStrat'];
                                    $Meteo=$data1['Meteo'];
                                    $Flag_Cible=$data1['Flag'];
                                }
                                mysqli_free_result($result1);
                            }
                            $Faction_Cible=GetData("Pays","ID",$Flag_Cible,"Faction");
                            if(($Veh >4999 or $Transit_Veh ==5000) and $Type_Veh !=37)
                            {
                                if($Zone_lieu !=6 and ($Port_Ori or $Garnison >0))
                                {
                                    if($Faction !=$Faction_Cible)
                                        AddEventFeed(201,$country,0,$Placement,$Lieu);
                                }
                                else
                                {
                                    $con=dbconnecti();
                                    $Detection=mysqli_result(mysqli_query($con,"SELECT MAX(j.Vue) FROM Pilote_IA as j,Pays as p WHERE j.Pays=p.ID AND j.Cible='$Cible' AND j.Task=5 AND j.Avion>0 AND p.Faction<>'$Faction' AND j.Actif=1"),0);
                                    $Detection_radar=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Cible' AND p.Faction<>'$Faction' AND r.Matos=26"),0);
                                    mysqli_close($con);
                                    if($Detection >0 or $Detection_radar){
                                        if((($Detection+mt_rand(-10,10)) >(mt_rand(-10,10)-$Meteo)) or ($Detection_radar >0 and $Meteo >-75))
                                            AddEventFeed(202,$country,0,$Placement,$Lieu);
                                    }
                                }
                            }
                            elseif($Faction !=$Faction_Cible and $Placement !=8)
                                AddEventFeed(200,$country,0,$Placement,$Lieu);
                            $_SESSION['msg'].='<p>'.$txt_cie.' s\'est déplacé dans la région indiquée.<p>';
                        }
                    }
                    elseif($Navire_out)
                        $_SESSION['msg_red']='Toute votre flottille est au fond de l\'eau!';
                    elseif($Bloque){
                        $con=dbconnecti();
                        $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Position=4,Placement=8,Visible=0,Atk_Eni=0,Lieu_ID='$Depot',Move=1".$Autonomie_txt." WHERE ID='$Reg'");
                        mysqli_close($con);
                    }
                    else
                        $_SESSION['msg_red']='Trop d\'unités occupent déjà cette zone, l\'unité ne peut pas s\'y déplacer!';
                    header( 'Location : ./index.php?view=ground_em_ia');
                }
            }
            elseif($Zone >0)
            {
                if($Zone ==10)$Zone=0;
                if($Depot !=$Retraite and $Retraite)
                {
                    $con=dbconnecti();
                    //$Embout2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Depot' AND r.Placement='$Zone' AND r.Vehicule_Nbr >0 AND p.Faction='$Faction'"),0);
                    $Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Depot' AND r.Placement='$Zone' AND r.Vehicule_Nbr >0 AND p.Faction='$Faction'"),0);
                    $resultv=mysqli_query($con,"SELECT ValeurStrat,Zone,Pont_Ori,NoeudF_Ori,Flag,Flag_Gare,Meteo FROM Lieu WHERE ID='$Depot'");
                    if($Zone !=8 and $Zone !=9)
                        $Sentinelles=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='$Depot' AND r.Placement='$Zone' AND r.Vehicule_Nbr >0 AND p.Faction!='$Faction'"),0);
                    mysqli_close($con);
                    if($resultv)
                    {
                        while($data2=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
                        {
                            $Pont_Dest=$data2['Pont_Ori'];
                            $NoeudF_Dest=$data2['NoeudF_Ori'];
                            $Flag_Gare_Dest=$data2['Flag_Gare'];
                            $Flag_Dest=$data2['Flag'];
                            $Meteo_Dest=$data2['Meteo'];
                            $ValeurStrat=$data2['ValeurStrat'];
                            $Zone_lieu=$data2['Zone'];
                        }
                        mysqli_free_result($resultv);
                    }
                    switch($Zone_lieu)
                    {
                        case 2: case 3: case 5: case 7: case 10:
                        $Cam_zone=30;
                        break;
                        case 4:
                            $Cam_zone=20;
                            break;
                        case 1: case 11:
                        $Cam_zone=10;
                        break;
                        case 9:
                            $Cam_zone=50;
                            break;
                        default:
                            $Cam_zone=0;
                            break;
                    }
                    $Embout+=$Embout2+1;
                }
                $Embout_Max=GetEmboutMax($ValeurStrat,$Zone,$Zone_lieu,$Front);
                if($Embout >$Embout_Max or $Embout >5)
                    $_SESSION['msg_red'] = 'Trop d\'unités occupent déjà cette zone, l\'unité ne peut pas s\'y déplacer!';
                else
                {
                    if($Trait ==5)
                        $Cam_txt=",Camouflage=4";
                    else
                        $Cam_txt=",Camouflage=1";
                    $con=dbconnecti();
                    if($Sentinelles){
                        $Bonus_det=0;
                        if($Flag_Dest ==$country)$Bonus_det=10;
                        $ressenti=mysqli_query($con,"SELECT c.Detection,r.Skill,r.Matos FROM Regiment_IA as r,Pays as p,Cible as c WHERE r.Pays=p.ID AND r.Vehicule_ID=c.ID AND r.Lieu_ID='$Depot' AND r.Placement='$Zone' AND r.Vehicule_Nbr >0 AND p.Faction!='$Faction'");
                        if($ressenti)
                        {
                            while($datas=mysqli_fetch_array($ressenti,MYSQLI_ASSOC))
                            {
                                $Detec_bonus=$datas['Detection'];
                                if($datas['Skill'] ==29)
                                    $Bonus_det+=10;
                                if($datas['Skill'] ==126)
                                    $Bonus_det+=15;
                                if($datas['Skill'] ==127)
                                    $Bonus_det+=20;
                                if($datas['Skill'] ==128)
                                    $Bonus_det+=25;
                                if($datas['Matos'] ==9 or $datas['Matos'] ==13)
                                    $Bonus_det+=5;
                                if($Bonus_det + mt_rand(0,$Detec_bonus) + $Meteo_Dest > $Cam + mt_rand(0,$Tactique) + $Cam_zone){
                                    $Visible=1;
                                }
                            }
                            mysqli_free_result($ressenti);
                        }
                    }
                    $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Placement='$Zone',Atk_Eni=0,Visible='$Visible',Position=4".$Cam_txt." WHERE ID='$Reg'");
                    mysqli_close($con);
                    if($OfficierEMID ==1)
                        $_SESSION['msg'] = 'L\'unité s\'est déplacé dans la zone indiquée<br>Embout='.$Embout;
                    else
                        $_SESSION['msg'] = 'L\'unité s\'est déplacé dans la zone indiquée';
                    if($Embout >=$Embout_Max and ($Zone ==3 or $Zone ==2 or $Zone ==0))
                        $_SESSION['msg'] = "<b>".$Embout_Max."</b> unités sont présentes sur cette zone, empêchant tout transit!<br>Etes-vous certain de vouloir laisser cette unité sur cette zone?";
                    elseif($Embout >($Embout_Max-2))
                        $_SESSION['msg'] = $Embout." unités sont présentes sur cette zone. La limite est fixée à <b>".$Embout_Max."</b> unités par zone. Au-delà, cela crée un embouteillage et plus aucun déplacement n'est possible!<br>Etes-vous certain de vouloir laisser cette unité sur cette zone?";
                }
                header( 'Location: ./index.php?view=ground_em_ia');
            }
        }
		if($Rally ==1 and !$Move and $OfficierID >0)
		{
			$bonus=0;
			if(!$Divisiono)$Divisiono=GetData("Regiment_IA","ID",$Reg,"Division");
			if($Divisiono and $Lieu_rally)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT r.ID FROM Officier as o,Regiment as r WHERE r.Officier_ID=o.ID AND o.Division='$Divisiono' AND r.Lieu_ID='$Lieu_rally' AND r.Moral <206");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_NUM))
					{
						UpdateData("Regiment","Moral",50,"ID",$data[0]);
						$bonus+=1;
					}
					mysqli_free_result($result);
				}
				$mes='<div class="alert alert-warning">Vous ralliez '.$bonus.' Compagnies de votre division !</div>';
				$bonus*=10;
				UpdateData("Officier","Avancement",$bonus,"ID",$OfficierID);
				UpdateData("Officier","Reputation",$bonus,"ID",$OfficierID);
				SetData("Regiment_IA","Move",1,"ID",$Reg);
				$img="<img src='images/rally.jpg'>";
			}
			else
				$mes='<div class="alert alert-danger">Le ralliement n\'a aucun effet!</div>';
		}
		elseif($Rally ==11)
			$Position=11;
		if($Position >0)
		{
			if($Position ==11)
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position=11,Placement=8,Visible=0,Move=1,Atk_Eni=0,Transit_Veh=5000 WHERE ID='$Reg'");
				mysqli_close($con);
				//$img="<img src='images/embarquement.jpg' style='width:50%;'>";
                $_SESSION['msg'] = 'Vos troupes embarquent sur des barges de transport';
                header( 'Location : ./index.php?view=ground_em_ia');
			}
			elseif($Position ==12 and !$Move)
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position=12,Placement=1,Visible=0,Atk_Eni=0,Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-warning">Vos troupes sont prêtes à être embarquées dans des avions de transport</div>';
				$img="<img src='images/commando".$country.".jpg' style='width:50%;'>";
			}
			elseif($Position ==13 and !$Move)
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position=13,Placement=1,Visible=0,Atk_Eni=0,Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-warning">Vos commandos sont prêts à être embarquées dans des avions de transport</div>';
				$img="<img src='images/commando".$country.".jpg' style='width:50%;'>";
			}
			elseif($Position ==29 and !$Move)
			{
				$mes='<div class="alert alert-warning">Le navire bombarde les fortifications côtières</div>';
				$img="<img src='images/nav_tirer.jpg' style='width:50%;'>";
				$con=dbconnecti();
				$reset1=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position',Visible=1,Move=1".$Autonomie_txt." WHERE ID='$Reg'");
				$reset2=mysqli_query($con,"UPDATE Lieu SET Fortification=Fortification-10 WHERE ID='$Lieu_ID'");
				mysqli_close($con);
			}
			/*elseif($Position ==24 and !$Move)
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position',Visible=1,Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes="Vos chasseurs de sous-marins se mettent en chasse";
				$img="<img src='images/nav_asm.jpg' style='width:50%;'>";
				$menu="<form action='index.php?view=ground_asm' method='post'>
				<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='".$Veh."'><input type='hidden' name='Cible' value='".$Lieu_ID."'><input type='hidden' name='Conso' value='0'>
				<input type='Submit' value='Grenadage' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}*/
			elseif($Position ==28 and !$Move)
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position',Visible=1,Move=1,Atk=1,Atk_time=NOW() WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-warning">Vos submersibles se mettent en chasse</div>';
				$img="<img src='images/plongee.jpg' style='width:50%;'>";
				$menu="<form action='index.php?view=ground_pldef' method='post'>
				<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='0'><input type='hidden' name='Cible' value='0'><input type='hidden' name='Conso' value='0'><input type='hidden' name='Bomb' value='2'>
				<a href='#' class='popup'><input type='Submit' value='Torpillage en surface' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'><span>Peut cibler les navires gravement endommagés peu importe la distance<br>Vulnérable à la riposte des navires en position Appui</span></a></form>
				<form action='index.php?view=ground_pldef' method='post'>
				<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='0'><input type='hidden' name='Cible' value='0'><input type='hidden' name='Conso' value='0'><input type='hidden' name='Bomb' value='12'>
				<a href='#' class='popup'><input type='Submit' value='Torpillage en plongée' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'><span>Vitesse réduite<br>Camouflage et Initiative augmentés<br>Vulnérable au grenadage des navires en position ASM</span></a></form>";
			}
			elseif($Position ==40 and !$Move)
			{
				if($Credits >=$CT_Action and $CT_Action >0)
				{
					if($OfficierID >0)
						UpdateData("Officier","Credits",-$CT_Action,"ID",$OfficierID);
					elseif($OfficierEMID >0)
						UpdateData("Officier_em","Credits",-$CT_Action,"ID",$OfficierEMID);
					$con=dbconnecti();
					$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position',Visible=1,Move=1,Atk=1,Atk_time=NOW() WHERE ID='$Reg'");
					mysqli_close($con);
					$mes='<div class="alert alert-warning">Vos navires se positionnent pour une attaque à la torpille</div>';
					$img="<img src='images/nav_torp.jpg' style='width:50%;'>";
					$menu="<form action='index.php?view=ground_pldef' method='post'>
					<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='0'><input type='hidden' name='Cible' value='0'><input type='hidden' name='Conso' value='0'><input type='hidden' name='Bomb' value='2'>
					<a href='#' class='popup'><input type='Submit' value='Torpillage' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'><span>Peut cibler les navires gravement endommagés peu importe la distance<br>Vulnérable à la riposte des navires en position Appui</span></a></form>";
				}
			}
			elseif($Position ==30 and !$Move)
			{
				if($Credits >=$CT_Action and $CT_Action >0)
				{
					if($OfficierID >0)
						UpdateData("Officier","Credits",-$CT_Action,"ID",$OfficierID);
					elseif($OfficierEMID >0)
						UpdateData("Officier_em","Credits",-$CT_Action,"ID",$OfficierEMID);
					$con=dbconnecti();
					$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position',Visible=1,Move=1".$Autonomie_txt." WHERE ID='$Reg'");
					mysqli_close($con);
					$mes='<div class="alert alert-warning">Le navire engage les navires ennemis</div>';
					$img="<img src='images/nav_gunfire.jpg' style='width:50%;'>";
					$menu="<form action='index.php?view=ground_pldef' method='post'>
					<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='0'><input type='hidden' name='Cible' value='0'><input type='hidden' name='Conso' value='0'><input type='hidden' name='Bomb' value='1'>
					<input type='Submit' value='Tirer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}
			}
			/*elseif($Position ==31) //Anciennes unités Pacifique
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position',Visible=1,Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes="Vos troupes cherchent à attaquer d'éventuelles unités ennemies";
				$img="<img src='images/attack.jpg' style='width:50%;'>";
				$menu="<form action='index.php?view=ground_pldef' method='post'>
				<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='0'><input type='hidden' name='Cible' value='0'><input type='hidden' name='Conso' value='0'><input type='hidden' name='Bomb' value='1'>
				<input type='Submit' value='Assaut' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}*/
			elseif($Position ==32 and !$Move)
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position',Visible=0,Atk_Eni=0,Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-warning">Vos troupes se préparent à embarquer</div>';
				$img="<img src='images/embarquement.jpg' style='width:50%;'>";
			}
			elseif($Position ==33) //Bombardement naval des infras
			{
				$con=dbconnecti();
				$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position',Visible=1,Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-warning">Vos navires bombardent la garnison ennemie</div>';
				$img="<img src='images/nav_gunfire.jpg' style='width:50%;'>";
				$menu="<form action='index.php?view=ground_atk' method='post'><input type='hidden' name='Cible' value='".$Depot."'>
				<input type='hidden' name='Action' value='110'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='".$Veh."'>
				<input type='Submit' value='Bombardement' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			}
			elseif(($Position ==34 or $Position ==35) and !$Move)
			{
				$con=dbconnecti();
				$result1=mysqli_query($con,"SELECT Arme_Art,Arme_Art_mun,Arme_AT,Arme_AT_mun,Reput FROM Cible WHERE ID='$Veh'");
				$resultd=mysqli_query($con,"SELECT Longitude,Latitude FROM Lieu WHERE ID='$Depot'");
				mysqli_close($con);
				if($resultd)
				{
					while($datad=mysqli_fetch_array($resultd,MYSQLI_ASSOC))
					{
						$Longitude_base=$datad['Longitude'];
						$Latitude_base=$datad['Latitude'];
					}
					mysqli_free_result($resultd);
				}
				if($result1)
				{
					while($dataa=mysqli_fetch_array($result1,MYSQLI_ASSOC))
					{
						$Arme_Art=$dataa['Arme_Art'];
						$Arme_Art_Mun=$dataa['Arme_Art_mun'];
                        $Arme_AT=$dataa['Arme_AT_mun'];
                        $Arme_AT_mun=$dataa['Arme_AT_mun'];
						$Reput_Renf=$dataa['Reput'];
					}
					mysqli_free_result($result1);
					unset($dataa);
				}
				$CT_Spec=floor($Reput_Renf/10);
				if($OfficierEMID)
					$CT_Off=2;
				else
					$CT_Off=4;
				if($Position ==34)$CT_Spec+=$CT_Off;
				if($Credits >=$CT_Spec and $Conso_Mun and ($Arme_Art or $Arme_AT))
				{
				    if($Arme_Art)
    					$Arme_Cal=round(GetData("Armes","ID",$Arme_Art,"Calibre"));
				    else
                        $Arme_Cal=round(GetData("Armes","ID",$Arme_AT,"Calibre"));
					if($Skill ==44 or $Skill ==131 or $Ravit)
					{
						$getdepot=true;
						$depot_nom="la réserve stratégique";
					}
					else
					{
						if($Front >0)
						{						
							$Lat_min=$Latitude_base-6;
							$Lat_max=$Latitude_base+6;
							$Long_min=$Longitude_base-7;
							$Long_max=$Longitude_base+7;
						}
						else
						{
							$Lat_min=$Latitude_base-2;
							$Lat_max=$Latitude_base+2;
							$Long_min=$Longitude_base-3;
							$Long_max=$Longitude_base+3;
						}
						$Stock_var='Stock_Munitions_'.$Arme_Cal;
						$con=dbconnecti();
						$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Visible=1,Atk=1,Atk_time=NOW(),Move=1".$Autonomie_txt." WHERE ID='$Reg'");
						$getflotted=mysqli_result(mysqli_query($con,"SELECT d.ID FROM Regiment_IA as r,Depots as d,Pays as p WHERE r.Pays=p.Pays_ID AND p.Faction='$Faction' AND r.Lieu_ID='$Lieu_ID' AND r.ID=d.Reg_ID AND d.".$Stock_var." >='$Conso_Mun'"),0);
						if(!$getflotted)
						{
							$getdepot=mysqli_result(mysqli_query($con,"SELECT l.ID FROM Lieu as l,Pays as p WHERE l.Flag=p.Pays_ID AND p.Faction='$Faction' AND
							(l.ID='$Lieu_ID' OR ((l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max'))) AND l.".$Stock_var." >='$Conso_Mun' ORDER BY l.".$Stock_var." DESC LIMIT 1"),0);
							$resetconso=mysqli_query($con,"UPDATE Lieu SET ".$Stock_var."=".$Stock_var."-'$Conso_Mun' WHERE ID='".$getdepot."'");
							$depot_nom=GetData("Lieu","ID",$getdepot,"Nom");
						}
						else
							$resetconso=mysqli_query($con,"UPDATE Depots SET ".$Stock_var."=".$Stock_var."-'$Conso_Mun' WHERE ID='".$getflotted."'");
						mysqli_close($con);
					}
					if($getdepot or $getflotted)
					{
						if($getflotted)
							$depot_nom='la flottille de ravitaillement au large';
						else
							$depot_nom='le dépôt de <b>'.$depot_nom.'</b>';
						if($Position ==34)
						{
							if($OfficierID >0)
								UpdateData("Officier","Credits",-$CT_Spec,"ID",$OfficierID);
							elseif($OfficierEMID >0)
								UpdateData("Officier_em","Credits",-$CT_Spec,"ID",$OfficierEMID);
							$mes='<div class="alert alert-warning">Votre artillerie bombarde les unités ennemies<br><b>'.$Conso_Mun.' Obus de '.$Arme_Cal.'mm</b> ont été attribués à l\'unité depuis '.$depot_nom.'</div>';
							$img="<img src='images/attack.jpg' style='width:50%;'>";
							$menu="<form action='index.php?view=ground_pldef' method='post'>
							<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='0'><input type='hidden' name='Cible' value='0'><input type='hidden' name='Conso' value='0'><input type='hidden' name='Bomb' value='1'>
							<input type='submit' value='Tirer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
						}
						elseif($Position ==35)
						{
							$mes='<div class="alert alert-warning">Votre artillerie bombarde la garnison ennemie</div>';
							$img="<img src='images/appui".$country.".jpg' style='width:50%;'>";
							$menu="<form action='index.php?view=ground_atk' method='post'><input type='hidden' name='Cible' value='".$Depot."'>
							<input type='hidden' name='Action' value='110'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='".$Veh."'>
							<input type='submit' value='Bombarder' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
						}
					}
					else{
                        $mes='<div class="alert alert-danger">Aucun dépôt disposant de la munition requise n\'a pu être trouvé!</div>';
                        mail('binote@hotmail.com','ADA : DEBUG ground_em_ia_go','Faction = '.$Faction.' Lieu = '.$Lieu_ID.' Lat_min '.$Lat_min.' Lat_max '.$Lat_max.' Long_min '.$Long_min.' Long_max '.$Long_max.' Stock '.$Stock_var.' Conso '.$Conso_Mun);
                    }
				}
				elseif(!$Arme_Art)
					$mes='<div class="alert alert-warning">Votre unité n\'a pas d\'arme de soutien!</div>';
				elseif(!$Conso_Mun)
					$mes='<div class="alert alert-warning">Votre unité n\'a pas les munitions nécessaires pour cela!</div>';
				else
					$mes='<div class="alert alert-danger">Vous manquez de temps pour cela!</div>';
			}
			elseif($Position ==48 and !$Move)
			{
				$Reput_Renf=GetData("Cible","ID",$Veh,"Reput");
				if($OfficierEMID)
					$CT_Spec=floor($Reput_Renf/10);
				else
					$CT_Spec=2+floor($Reput_Renf/10);
				if($Credits >=$CT_Spec)
				{
					$con=dbconnecti();
					$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Visible=1,Atk_Eni=0,Move=1".$Autonomie_txt." WHERE ID='$Reg'");
					mysqli_close($con);
					if($Position ==48)
					{
						if($OfficierID >0)
							UpdateData("Officier","Credits",-$CT_Spec,"ID",$OfficierID);
						elseif($OfficierEMID >0)
							UpdateData("Officier_em","Credits",-$CT_Spec,"ID",$OfficierEMID);
						$mes='<div class="alert alert-warning">Vos troupes lancent un assaut sur les troupes défendant l\'aérodrome</div>';
						$img="<img src='images/attack.jpg' style='width:50%;'>";
						$menu="<form action='index.php?view=ground_atk_garnison' method='post'><input type='hidden' name='Cible' value='".$Depot."'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='".$Veh."'><input type='hidden' name='Mode' value='48'>
						<input type='submit' value='Assaut' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
					}
				}
				else
					$mes='<div class="alert alert-danger">Vous manquez de temps pour cela!</div>';
			}
			elseif($Position ==38 and !$Move)
			{
				$Reput_Renf=GetData("Cible","ID",$Veh,"Reput");
				if($OfficierEMID)
					$CT_Spec=floor($Reput_Renf/10);
				else
					$CT_Spec=2+floor($Reput_Renf/10);
				if($Credits >=$CT_Spec)
				{
					$con=dbconnecti();
					$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Visible=1,Atk_Eni=0,Move=1".$Autonomie_txt." WHERE ID='$Reg'");
					mysqli_close($con);
					if($Position ==38)
					{
						if($OfficierID >0)
							UpdateData("Officier","Credits",-$CT_Spec,"ID",$OfficierID);
						elseif($OfficierEMID >0)
							UpdateData("Officier_em","Credits",-$CT_Spec,"ID",$OfficierEMID);
						$mes='<div class="alert alert-warning">Vos troupes lancent un assaut sur la garnison ennemie</div>';
						$img="<img src='images/attack.jpg' style='width:50%;'>";
						$menu="<form action='index.php?view=ground_atk_garnison' method='post'><input type='hidden' name='Cible' value='".$Depot."'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='".$Veh."'><input type='hidden' name='Mode' value='38'>
						<input type='submit' value='Assaut' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
					}
				}
				else
					$mes='<div class="alert alert-danger">Vous manquez de temps pour cela!</div>';
			}
			elseif($Position ==36 and !$Move)
			{
				$Reput_Renf=GetData("Cible","ID",$Veh,"Reput");
				if($OfficierEMID)
					$CT_Spec=floor($Reput_Renf/10);
				else
					$CT_Spec=2+floor($Reput_Renf/10);
				if($Credits >=$CT_Spec)
				{
					if($OfficierID >0)
						UpdateData("Officier","Credits",-$CT_Spec,"ID",$OfficierID);
					elseif($OfficierEMID >0)
						UpdateData("Officier_em","Credits",-$CT_Spec,"ID",$OfficierEMID);
					$con=dbconnecti();
					$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Visible=1,Atk_Eni=0,Move=1".$Autonomie_txt." WHERE ID='$Reg'");
					mysqli_close($con);
					$mes='<div class="alert alert-warning">Vos troupes cherchent à attaquer d\'éventuelles unités ennemies</div>';
					$img="<img src='images/attack.jpg' style='width:50%;'>";
					$menu="<form action='index.php?view=ground_pldef' method='post'>
					<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='0'><input type='hidden' name='Cible' value='0'><input type='hidden' name='Conso' value='0'><input type='hidden' name='Bomb' value='3'>
					<input type='submit' value='Attaquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}				
			}
			elseif($Position ==39 and !$Move)
			{
				$Reput_Renf=GetData("Cible","ID",$Veh,"Reput");
				$CT_Spec=4+floor($Reput_Renf/10);
				if($Credits >=$CT_Spec)
				{
					if($OfficierID >0)
						UpdateData("Officier","Credits",-$CT_Spec,"ID",$OfficierID);
					elseif($OfficierEMID >0)
						UpdateData("Officier_em","Credits",-$CT_Spec,"ID",$OfficierEMID);
					$con=dbconnecti();
					$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Visible=1,Atk_Eni=0,Move=1".$Autonomie_txt." WHERE ID='$Reg'");
					mysqli_close($con);
					$mes='<div class="alert alert-warning">Vos troupes cherchent à disperser d\'éventuelles unités d\'infanterie ennemies désorganisées</div>';
					$img="<img src='images/attack.jpg' style='width:50%;'>";
					$menu="<form action='index.php?view=ground_pldef' method='post'>
					<input type='hidden' name='CT' value='0'><input type='hidden' name='Reg' value='".$Reg."'><input type='hidden' name='Veh' value='0'><input type='hidden' name='Cible' value='0'><input type='hidden' name='Conso' value='0'><input type='hidden' name='Bomb' value='4'>
					<input type='submit' value='Attaquer' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
				}				
			}
			elseif($Position ==26 and !$Move)
			{
				if($Trait ==5)$Cam_txt=",Camouflage=4";
				$con=dbconnecti();
				$reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Position=26,Move=1,Visible=0".$Cam_txt." WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-warning">Le navire déploie ses filets anti-torpilles</div>';
			}
			elseif($Position ==27 and !$Move)
			{
				$con=dbconnecti();
				$reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Position=27,Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-warning">Le navire se place en interdiction, tentant d\'intercepter tout navire ennemi tentant de fuir la zone de combat</div>';
			}
			elseif($Position ==37 and !$Move)
			{
				$con=dbconnecti();
				$reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Position=37,Move=1,Visible=0 WHERE ID='$Reg'");
				mysqli_close($con);
				$mes='<div class="alert alert-warning">Le navire génère un écran de fumée, tentant de protéger tout navire allié tentant de fuir la zone de combat</div>';
			}
			elseif($Position ==23 or $Position ==24)
			{
			    if($Credits >=1){
                    if($OfficierID >0)
                        UpdateData("Officier","Credits",-1,"ID",$OfficierID);
                    elseif($OfficierEMID >0)
                        UpdateData("Officier_em","Credits",-1,"ID",$OfficierEMID);
                    $con=dbconnecti();
                    $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position' WHERE ID='$Reg'");
                    mysqli_close($con);
                    if($Position ==24)
                        $_SESSION['msg'] = 'Le navire se place en veille anti-sous-marine';
                    else
                        $_SESSION['msg'] = 'Le navire se place en appui afin de couvrir les navires alliés de son artillerie';
                }
                header( 'Location : ./index.php?view=ground_em_ia');
			}
            elseif($Position ==14 and !$Move)
            {
                if($Trait ==5)
                    $Cam_txt=",Camouflage=4";
                else
                    $Cam_txt=",Camouflage=2";
                $con=dbconnecti();
                $reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Position=14,Move=1".$Cam_txt." WHERE ID='$Reg'");
                mysqli_close($con);
                $_SESSION['msg'] = 'Vos troupes se positionnent afin de tenter de repérer toute incursion de l\'ennemi sur la zone';
                header( 'Location : ./index.php?view=ground_em_ia');
            }
			else
			{
				if($Trait ==5)$Cam_txt=",Camouflage=4";
				$con=dbconnecti();
				$reset_r=mysqli_query($con,"UPDATE Regiment_IA SET Position='$Position'".$Cam_txt." WHERE ID='$Reg'");
				mysqli_close($con);
				//$mes='<div class="alert alert-warning">L\'unité a changé de position</div>';
                header('Location: ./index.php?view=ground_em_ia');
			}
		}
		if($Fret >0 and $Depot >0)
		{
			$Qty=0;
			if($Fret ==1001)
			{
				$Stock='Essence_1';
				$Qty=50000;
				$Qty_txt='L de Diesel';
			}
			elseif($Fret==1087)
			{
				$Stock='Essence_87';
				$Qty=50000;
				$Qty_txt='L d\'essence octane 87';
			}
			elseif($Fret==1100)
			{
				$Stock='Essence_100';
				$Qty=50000;
				$Qty_txt='L d\'essence octane 100';
			}
			elseif($Fret==8)
			{
				$Stock='Munitions_8';
				$Qty=100000;
				$Qty_txt='cartouches de 8mm';
			}
			elseif($Fret==13)
			{
				$Stock='Munitions_13';
				$Qty=50000;
				$Qty_txt='cartouches de 13mm';
			}
			elseif($Fret==20)
			{
				$Stock='Munitions_20';
				$Qty=20000;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==30)
			{
				$Stock='Munitions_30';
				$Qty=10000;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==40)
			{
				$Stock='Munitions_40';
				$Qty=5000;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==50)
			{
				$Stock='Munitions_50';
				$Qty=3000;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==60)
			{
				$Stock='Munitions_60';
				$Qty=2000;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==75)
			{
				$Stock='Munitions_75';
				$Qty=1500;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==90)
			{
				$Stock='Munitions_90';
				$Qty=1000;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==105)
			{
				$Stock='Munitions_105';
				$Qty=750;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==125)
			{
				$Stock='Munitions_125';
				$Qty=500;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==150)
			{
				$Stock='Munitions_150';
				$Qty=200;
				$Qty_txt='obus de '.$Fret.'mm';
			}
			elseif($Fret==1200)
			{
				$Stock='Munitions_200';
				$Qty=100;
				$Qty_txt='obus de 200mm';
			}
			elseif($Fret==310)
			{
				$Stock='Munitions_300';
				$Qty=75;
				$Qty_txt='obus de 300mm';
			}
			elseif($Fret==360)
			{
				$Stock='Munitions_360';
				$Qty=50;
				$Qty_txt='obus de 360mm';
			}
			elseif($Fret==930)
			{
				$Stock='Bombes_30';
				$Qty=10000;
				$Qty_txt='fusées éclairantes';
			}
			elseif($Fret==80)
			{
				$Stock='Bombes_80';
				$Qty=1000;
				$Qty_txt='rockets';
			}
			elseif($Fret==300)
			{
				$Stock='Bombes_300';
				$Qty=250;
			}
			elseif($Fret==400)
			{
				$Stock='Bombes_400';
				$Qty=250;
			}
			elseif($Fret==800)
			{
				$Stock='Bombes_800';
				$Qty=100;
				$Qty_txt='torpilles';
			}
			elseif($Fret==9050)
			{
				$Stock='Bombes_50';
				$Qty=2000;
				$Qty_txt='bombes de 50kg';
			}
			elseif($Fret==9125)
			{
				$Stock='Bombes_125';
				$Qty=1000;
				$Qty_txt='bombes de 125kg';
			}
			elseif($Fret==9250)
			{
				$Stock='Bombes_250';
				$Qty=500;
				$Qty_txt='bombes de 250kg';
			}
			elseif($Fret==9500)
			{
				$Stock='Bombes_500';
				$Qty=200;
				$Qty_txt='bombes de 500kg';
			}
			elseif($Fret==10000)
			{
				$Stock='Bombes_1000';
				$Qty=100;
				$Qty_txt='bombes de 1000kg';
			}
			elseif($Fret==11000)
			{
				$Stock='Bombes_2000';
				$Qty=50;
				$Qty_txt='bombes de 2000kg';
			}
			elseif($Fret==888)
				$Stock=0;
			if($Veh ==5001 or $Veh ==5124 or $Veh ==5392)
				$Qty*=5;
			elseif($Type_Veh ==1)
			{
				$Mult_Camion=Insec($_POST['Mult']); //$Mult_Camion=floor($Charge*$Vehicule_Nbr/1000);
				if($Mult_Camion >($Veh_Nbr*10))$Mult_Camion=$Veh_Nbr*10;
				$Qty=floor($Qty/50*$Mult_Camion);
			}
			if($Reg_div >0)$Qty/=50;
			if($Stock)
			{
				$Stock='Stock_'.$Stock;				
				$con=dbconnecti();
				if($Fret_d)$upreg2=mysqli_query($con,"UPDATE Depots SET ".$Stock."=".$Stock."+".$Qty." WHERE Reg_ID='$Reg'");
				$upreg=mysqli_query($con,"UPDATE Regiment_IA SET Fret=".$Fret.",Autonomie=150,Move=1 WHERE ID='$Reg'");
				$upstock=mysqli_query($con,"UPDATE Lieu SET ".$Stock."=".$Stock."-".$Qty." WHERE ID='$Depot'");
				mysqli_close($con);
				if($upreg and $upstock)
					$_SESSION['msg'] = "Vous chargez ".$Qty." ".$Qty_txt." depuis le dépôt de ".GetData("Lieu","ID",$Depot,"Nom");
				else
                    $_SESSION['msg_red'] = 'Une erreur est survenue!';
			}
			elseif($Fret==888 and $Veh ==5124)
			{
				$con=dbconnecti();
				$upreg=mysqli_query($con,"UPDATE Regiment_IA SET Fret=888,Move=1 WHERE ID='$Reg'");
				mysqli_close($con);
				if($upreg)
                    $_SESSION['msg'] = 'Vous chargez des fournitures Lend-Lease depuis le dépôt de '.GetData("Lieu","ID",$Depot,"Nom");
				else
                    $_SESSION['msg_red'] = 'Une erreur est survenue!';
			}
			else
                $_SESSION['msg_red'] = 'Erreur de chargement!';
			$img="<img src='images/dechargement.jpg' style='width:100%;'>";
            header( 'Location : ./index.php?view=ground_em_ia');
		}
		elseif($Decharge >0){
			$Qty=0;
			if($Decharge ==1001)
			{
				$Stock="Essence_1";
				$Qty=50000;
			}
			elseif($Decharge ==1087)
			{
				$Stock="Essence_87";
				$Qty=50000;
			}
			elseif($Decharge ==1100)
			{
				if($Reg_div)
					$Stock="Essence_87";
				else
					$Stock="Essence_100";
				$Qty=50000;
			}
			elseif($Decharge ==8)
			{
				$Stock="Munitions_8";
				$Qty=100000;
			}
			elseif($Decharge ==13)
			{
				$Stock="Munitions_13";
				$Qty=50000;
			}
			elseif($Decharge ==20)
			{
				$Stock="Munitions_20";
				$Qty=20000;
			}
			elseif($Decharge ==30)
			{
				$Stock="Munitions_30";
				$Qty=10000;
			}
			elseif($Decharge ==40)
			{
				$Stock="Munitions_40";
				$Qty=5000;
			}
			elseif($Decharge ==50)
			{
				$Stock="Munitions_50";
				$Qty=3000;
			}
			elseif($Decharge ==60)
			{
				$Stock="Munitions_60";
				$Qty=2000;
			}
			elseif($Decharge ==75)
			{
				$Stock="Munitions_75";
				$Qty=1500;
			}
			elseif($Decharge ==90)
			{
				$Stock="Munitions_90";
				$Qty=1000;
			}
			elseif($Decharge ==105)
			{
				$Stock="Munitions_105";
				$Qty=750;
			}
			elseif($Decharge ==125)
			{
				$Stock="Munitions_125";
				$Qty=500;
			}
			elseif($Decharge ==150)
			{
				$Stock="Munitions_150";
				$Qty=200;
			}
			elseif($Decharge ==1200)
			{
				$Stock="Munitions_200";
				$Qty=100;
			}
			elseif($Decharge ==310)
			{
				$Stock="Munitions_300";
				$Qty=75;
			}
			elseif($Decharge ==360)
			{
				$Stock="Munitions_360";
				$Qty=50;
			}
			elseif($Decharge ==930)
			{
				$Stock="Bombes_30";
				$Qty=10000;
			}
			elseif($Decharge ==80)
			{
				$Stock="Bombes_80";
				$Qty=1000;
			}
			elseif($Decharge ==300)
			{
				if($Reg_div)
					$Stock="Charges";
				else
					$Stock="Bombes_300";
				$Qty=250;
			}
			elseif($Decharge ==400)
			{
				if($Reg_div)
					$Stock="Mines";
				else
					$Stock="Bombes_400";
				$Qty=250;
			}
			elseif($Decharge ==800)
			{
				if($Reg_div)
					$Stock="Munitions_530";
				else
					$Stock="Bombes_800";
				$Qty=100;
			}
			elseif($Decharge ==9050)
			{
				$Stock="Bombes_50";
				$Qty=2000;
			}
			elseif($Decharge ==9125)
			{
				$Stock="Bombes_125";
				$Qty=1000;
			}
			elseif($Decharge ==9250)
			{
				$Stock="Bombes_250";
				$Qty=500;
			}
			elseif($Decharge ==9500)
			{
				$Stock="Bombes_500";
				$Qty=200;
			}
			elseif($Decharge ==10000)
			{
				$Stock="Bombes_1000";
				$Qty=100;
			}
			elseif($Decharge ==11000)
			{
				$Stock="Bombes_2000";
				$Qty=50;
			}
			elseif($Decharge ==888)
				$Stock=false;
			if($Stock)
			{
                $con=dbconnecti();
                $reset_r2=mysqli_query($con,"UPDATE Regiment_IA SET Move=1,Fret=0 WHERE ID='$Reg'");
                mysqli_close($con);
				if($Depot >0)
				{
					if($Veh ==5001 or $Veh ==5124)
						$Qty*=5;
					elseif($Type_Veh ==1)
					{
						$Mult_Camion=Insec($_POST['Mult']); //$Mult_Camion=floor($Charge*$Vehicule_Nbr/1000);
						if($Mult_Camion >($Veh_Nbr*10))$Mult_Camion=$Veh_Nbr*10;
						$Qty=floor($Qty/50*$Mult_Camion);
					}
					UpdateData("Lieu","Stock_".$Stock,$Qty,"ID",$Depot);
                    $_SESSION['msg'] = 'Vous déchargez '.$Qty.' dans le dépôt';
				}
				elseif($Reg_div >0 and $Decharge <9000)
				{
					$Qty/=10;
					UpdateData("Regiment","Stock_".$Stock,$Qty,"ID",$Reg_div);
                    $_SESSION['msg'] = 'Vous ravitaillez l\'unité de votre flotte de '.$Qty;
				}
				else
                    $_SESSION['msg_red'] = 'Type incompatible!';
			}
			elseif($Decharge ==888 and $Veh ==5124)
			{
                $con=dbconnecti();
                $reset_r2=mysqli_query($con,"UPDATE Regiment_IA SET Move=1,Fret=0 WHERE ID='$Reg'");
                $Pays_eni=mysqli_result(mysqli_query($con,"SELECT Flag FROM Lieu WHERE ID='$Depot'"),0);
                mysqli_close($con);
				UpdateData("Pays","Special_Score",1,"ID",$Pays_eni);
				AddEventFeed(321,$Reg,0,888,$Depot);
                $_SESSION['msg'] = 'Vous déchargez des fournitures Lend-Lease dans le dépôt';
			}
			else
                $_SESSION['msg_red'] = 'Erreur de déchargement!';
            header( 'Location : ./index.php?view=ground_em_ia');
            die;
			$img="<img src='images/dechargement.jpg' style='width:100%;'>";
		}
		if(!$mes and !$Admin)
		    $mes='<div class="alert alert-danger">Non, vraiment, vous ne pouvez pas!</div>';
		if(!$img)$img="<img src='images/radio_naval.jpg' style='width:50%;'>";
		$titre=$Reg.'e Compagnie IA';
		if(!$menu)
		{
			if(!$OfficierEMID)
				$menu="<form action='index.php?view=ground_bat' method='post'><input type='Submit' value='Retour au menu' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			else
				$menu="<form action='index.php?view=ground_em_ia_list' method='post'><input type='submit' value='Retour au menu' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
			if($Reset !=9)
				$menu.="<br><form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='Submit' value='Retour à la Cie' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		}
		include_once './default.php';
	}
	else
		echo '<div class="alert alert-danger">Vous n\'êtes pas autorisé à commander cette unité</div>';
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';