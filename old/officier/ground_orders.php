<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	$country=$_SESSION['country'];	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$Reg=Insec($_POST['Reg']);
	$Veh=Insec($_POST['Veh']);
	$Cible=Insec($_POST['Cible']);
	$Conso=Insec($_POST['Conso']);
	$CT=Insec($_POST['CT']);	
	SetData("Officier","Atk",1,"ID",$OfficierID);
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	$Move=GetData("Regiment","ID",$Reg,"Move");
	$Placement=GetData("Regiment","ID",$Reg,"Placement");
	if(!$Placement)$Placement=10;	
	//GetData Lieu
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Nom,Map,Zone,BaseAerienne,Industrie,Pont,Radar,Port,NoeudR,NoeudF,Recce,Flag FROM Lieu WHERE ID='$Cible'");
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Cible_nom=$data['Nom'];
			$Map=$data['Map'];
			$Cible_base=$data['BaseAerienne'];
			$Usine=$data['Industrie'];
			$Pont=$data['Pont'];
			$Port=$data['Port'];
			$Radar=$data['Radar'];
			$NoeudR=$data['NoeudR'];
			$NoeudF=$data['NoeudF'];
			$Zone=$data['Zone'];
			$Recce=$data['Recce'];
			$Flag=$data['Flag'];
		}
		mysqli_free_result($result);
		unset($result);
		unset($data);
	}
	if(is_file('images/lieu/lieu'.$Cible.'.jpg'))
		$img='<img src=\'images/lieu/lieu'.$Cible.'.jpg\' title=\''.$Cible_nom.'\'>';
	else
	{
		if($Nuit)
			$img='<img src=\'images/lieu/objectif_nuit'.$Map.'.jpg\' title=\''.$Cible_nom.'\'>';
		else
			$img='<img src=\'images/lieu/objectif'.$Map.'.jpg\' title=\''.$Cible_nom.'\'>';
	}	
	if($Credits >=$CT and $Flag !=$country and $Move)
	{
		if($Recce >0)
		{
			//Get Vehicule
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Vitesse,mobile,Carbu_ID,Type,Blindage_f FROM Cible WHERE ID='$Veh'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Vitesse=$data['Vitesse'];
					$mobile=$data['mobile'];
					$Type=$data['Type'];
					$Blindage=$data['Blindage_f'];
					$Veh_Carbu=$data['Carbu_ID'];
					$Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type);
					if($Flag ==$country)$Vitesse+=10;
				}
				mysqli_free_result($result);
				unset($data);
			}			
			$Range=$Vitesse*100;			
			if($mobile == 5) //naval
			{
				$Placement=4;
				$Atk=true;
			}
			else
			{			
				$Pass=false;
				$Faction=GetData("Pays","ID",$country,"Faction");
				$con=dbconnecti();
				$result_inf=mysqli_query($con,"(SELECT r.Experience,r.Moral,r.ID FROM Regiment as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Moral >50
				AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.mobile=3 AND r.Placement='$Placement' AND r.Position=10)
				UNION (SELECT r.Experience,r.Moral,r.ID FROM Regiment_IA as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Moral >50
				AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.mobile=3 AND r.Placement='$Placement' AND r.Position=10)");
				mysqli_close($con);
				if($result_inf)
				{
					while($datai=mysqli_fetch_array($result_inf,MYSQLI_NUM))
					{
						$Inf_eni+=1;
						$Moral_eni=$datai[1];
						$Exp_eni=ceil($datai[0]/100*$Moral_eni);
						if(mt_rand(0,$Reg_xp)+$Vitesse >mt_rand(0,$Exp_eni)+($Inf_eni*10))
							$Pass+=1;
					}
					mysqli_free_result($result_inf);
				}
				if($Blindage >0 and $mobile !=3)
				{
					if(IsSkill(19,$OfficierID))
					{
						if(GetData("Officier","ID",$OfficierID,"Trait") ==3)
						{
							$Range*=1.5;
							$Pass*=2;
						}
						else
						{
							$Range*=1.25;
							$Pass*=1.5;
						}
						$mes="<p>Vos troupes bénéficient de votre compétence <b>Fer de Lance</b> !</p>";
					}
				}
				if($Inf_eni >0 and $Pass <($Inf_eni/2))
				{
					$mes.="<p>".$Inf_eni." Cie d'infanterie ennemies forment un front continu vous empêchant d'atteindre les infrastructures</p>";
					$Atk=false;
				}
				else
				{
					if($mobile !=3)
					{
						if(IsSkill(18,$OfficierID))
						{
							$Range *=2;
							$mes.="<p>Vos troupes bénéficient de votre compétence <b>Exploitation</b> !</p>";
						}
					}
					$mes.="<p>Vous parvenez à profiter d'une brèche dans le front tenu par l'ennemi!</p>";
					$Atk=true;
				}
			}
			if($Atk ==true)
			{
				if($Placement ==7)
					$mes.="<p>Vos troupes prennent d'assaut la station radar !</p>";
				elseif($Placement ==6)
					$mes.="<p>Vos troupes prennent d'assaut l'usine !</p>";
				elseif($Placement ==5)
					$mes.="<p>Vos troupes prennent d'assaut le pont !</p>";
				elseif($Placement ==4)
					$mes.="<p>Vos troupes prennent d'assaut le port !</p>";
				elseif($Placement ==3)
					$mes.="<p>Vos troupes prennent d'assaut la gare !</p>";
				elseif($Placement ==1)
					$mes.="<p>Vos troupes prennent d'assaut la base aérienne !</p>";
				else
					$mes.="<p>Vos troupes prennent d'assaut la caserne !</p>";
				$titre="Assaut";
				$menu="<form action='index.php?view=ground_atk' method='post'>
					<input type='hidden' name='CT' value='".$CT."'>
					<input type='hidden' name='Reg' value='".$Reg."'>
					<input type='hidden' name='Veh' value='".$Veh."'>
					<input type='hidden' name='Cible' value='".$Cible."'>
					<input type='hidden' name='Conso' value='".$Conso."'>
					<input type='hidden' name='Action' value='".$Placement."'>				
				<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></div>";				
				include_once('./default.php');
			}
		}
		else
			echo "<p>La cible n'est pas reconnue!</p>";
	}
	else
		echo "<p>Vous n'êtes pas en condition pour attaquer cette cible!</p>";
}
?>