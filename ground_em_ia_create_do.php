<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once './jfv_include.inc.php';
	include_once './jfv_txt.inc.php';
	include_once './jfv_ground.inc.php';
	$Vehicule=Insec($_POST['Ve']);
	$Reput=Insec($_POST['Cr']);
	$Retraite=Insec($_POST['Nid']);
	echo "<h1>Création d'unité EM</h1>";
	if($Vehicule >0 and $Reput >0)
	{
		$Credits=GetData("Officier_em","ID",$OfficierEMID,"Credits");
		$Admin=GetData("Officier_em","ID",$OfficierEMID,"Admin");
		if($Credits >=$Reput) 
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Type,Categorie,mobile,Flak,Portee,HP,Hydra_Nbr,Stock FROM Cible WHERE ID='$Vehicule'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Type=$data['Type'];
					$Categorie=$data['Categorie'];
					$mobile=$data['mobile'];
					$Flak=$data['Flak'];
					$Portee=$data['Portee'];
					$Hydra_Nbr=$data['Hydra_Nbr'];
					if($mobile ==5)
					{
						$HP=$data['HP'];
						$Placement=8;
						$Experience=250;
						$Veh_Nbr=1;
						$Autonomie=10;
					}
					else
					{
						$Placement=6;
						$Experience=50;
						$Autonomie=0;
						$VehNbrMax=GetMaxVeh($Type,$mobile,$Flak,500000);
						if($Stock >=$VehNbrMax)
							$Veh_Nbr=$VehNbrMax;
						else
							$Veh_Nbr=floor($Stock);
					}
				}
				mysqli_free_result($result);
			}
			if($Type !=13 and $Type !=1)
			{
				if($Type ==37) //Sub
					$Skills_1=array(25,32,35,37,43);
				elseif($Type ==21) //PA
					$Skills_1=array(25,30,36);
				elseif($Type ==20 or $Type ==19 or $Type ==18) //Cuirassé & Croiseur
					$Skills_1=array(15,22,25,30,31,33,34,35,36,38,41);
				elseif($Type ==15 or $Type ==16 or $Type ==17) //Escorteurs
					$Skills_1=array(25,30,35,36,37,39,40,42);
				elseif($Type ==14) //Pt navires
					$Skills_1=array(25,35,36);
				elseif($Categorie ==6) //MG
					$Skills_1=array(3,4,6,7,9,11,13,14,23,25,29);
				elseif($Type ==4) //Canon AT
					$Skills_1=array(3,6,9,11,12,14,25);
				elseif($Type ==6)
					$Skills_1=array(6,8,9,12,15,22,25,28);
				elseif($Type ==8)
					$Skills_1=array(6,8,9,15,20,22,25,28);
				elseif($Type ==9)
					$Skills_1=array(1,2,3,5,6,9,10,16,18,19,21,24,25);
				elseif($Type ==12)
					$Skills_1=array(6,9,12,14,25,30);
				elseif($Type ==7 or $Type ==10 or $Type ==91)
					$Skills_1=array(1,2,5,6,9,10,16,18,19,21,24,25);
				elseif($Type ==11)
					$Skills_1=array(1,2,5,6,9,10,16,18,19,21,25,30);
				elseif($Type ==2 or $Type ==3 or $Type ==5 or $Type ==93)
					$Skills_1=array(1,2,5,6,9,10,16,18,19,21,25);
				elseif($Type ==1) //Camions
					$Skills_1=array(6,25);
				else //Inf
					$Skills_1=array(3,4,6,7,9,11,13,14,17,23,25,26,29);
			}
			if(is_array($Skills_1))
				$Skill_p=$Skills_1[mt_rand(0,count($Skills_1)-1)];
			if(!$Retraite)$Retraite=Get_Retraite(99,$country,40);
			$Front=GetFrontByCoord($Retraite);
			$query2="INSERT INTO Regiment_IA (Pays,Front,Vehicule_ID,Lieu_ID,Vehicule_Nbr,Placement,HP,Camouflage,Experience,Moral,Distance,Move,Skill,Avions,Autonomie) 
            VALUES ('$country','$Front','$Vehicule','$Retraite','$Veh_Nbr','$Placement','$HP',1,'$Experience',100,'$Portee',1,'$Skill_p','$Hydra_Nbr','$Autonomie')";
			$con=dbconnecti();
			$ok2=mysqli_query($con,$query2);
			if($Vehicule ==5392) //Dépot flottant
            {
			    $ins_id=mysqli_insert_id($con);
			    $ok3=mysqli_query($con,"INSERT INTO Depots (Reg_ID) VALUES ('$ins_id')");
            }
            else
			    $ok3=true;
			mysqli_close($con);
			if($ok2 && $ok3)
			{
				echo "<div class='alert alert-info'>L'unité a été activée avec succès !</div><br>".$Veh_Nbr." ".GetVehiculeIcon($Vehicule,$country,0,0,$Front);
				if(!$Admin)UpdateCarac($OfficierEMID,"Credits",-$Reput,"Officier_em");
			}
			else
				echo "<div class='alert alert-danger'>Erreur lors de l'activation de l'unité !</div>";
			echo "<br><a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour</a>";
		}
		else
			echo "<div class='alert alert-danger'>Vous ne bénéficiez pas des crédits suffisants !</div>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page</h1>";