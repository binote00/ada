<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	$Action = Insec($_POST['Action']);
	$Qty = floor(Insec($_POST['Qty']));
	$CT = Insec($_POST['CT']);
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	$Division=GetData("Officier","ID",$OfficierID,"Division");
	if(!$CT)$CT=2;
	if($Action ==0)
	{
		echo "Vous annulez votre action.";
		header("Location: ./index.php?view=ground_menu");
	}
	elseif($Credits >=$CT and $Qty >0)
	{
		$country=$_SESSION['country'];
		$Regiment = Insec($_POST['Reg']);	
		$Muns = Insec($_POST['muns']);		
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Officier_ID,Lieu_ID,Vehicule_ID,Vehicule_Nbr,Fret,Placement FROM Regiment WHERE ID='$Regiment'") 
		or die ('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : ground_decharge1-reg');
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Officier_cible = $data['Officier_ID'];
				$Cible = $data['Lieu_ID'];
				$Vehicule = $data['Vehicule_ID'];
				$Vehicule_Nbr = $data['Vehicule_Nbr'];
				$Fret = $data['Fret'];
				$Placement = $data['Placement'];
			}
			mysqli_free_result($result);
		}		
		if($Fret)
		{
			$EventTr=false;
			$Stock=$Qty;
			if($Action ==8888)
			{
				$EventTr=1;
				$Stock=50000;
				$ravit_txt="le dépôt";
			}
			elseif(strpos($Action,"depot") !==false)
			{
				$Action=strstr($Action,"_",true);
				$Unit_table="Lieu";
				$EventMun=912;
				$EventEss=911;
				$EventTr=115;
				$ravit_txt="le dépôt";
			}
			elseif(strpos($Action,"tr") !==false)
			{
				$Action=strstr($Action,"_",true);
				$Unit_table="Lieu";
				$EventTr=116;
			}
			elseif(strpos($Action,"_") !==false)
			{
				$mobile=GetData("Cible","ID",$Vehicule,"mobile");
				if($mobile ==5)
				{
					$Unit_table="Regiment";
					$EventMun=312;
					$EventEss=311;
					if($Fret <300)
						SetData("Regiment","Muns",$Muns,"ID",$Regiment);
					$ravit_txt="la flottille";
				}
				elseif($Fret >9000 or $Fret ==80 or $Fret ==400 or $Fret ==800)
					exit('Vous ne pouvez pas décharger de bombes ou de rockets vers une unité terrestre');
				else
				{
					$Unit_table="Regiment";
					$EventMun=312;
					$EventEss=311;
					if($Fret <300)
						SetData("Regiment","Muns",$Muns,"ID",$Regiment);
					$ravit_txt="l'unité";
				}
				$con=dbconnecti();
				$Division_r = mysqli_result(mysqli_query($con,"SELECT o.Division FROM Regiment as r,Officier as o WHERE r.ID='$Regiment' AND r.Officier_ID = o.ID"),0);
				mysqli_close($con);
			}
			else
			{
				if($Fret ==150)exit('Vous ne pouvez pas décharger cette munition vers une unité aérienne');
				$Unit_table="Unit";
				$EventMun=112;
				$EventEss=111;
				$ravit_txt="l'escadrille";
			}
			/*if(IsSkill(43,$OfficierID))
				$Bonus_Skill=1.25;
			elseif(IsSkill(41,$OfficierID))
				$Bonus_Skill=1.1;
			else
				$Bonus_Skill=1;
			$Charge=GetData("Cible","ID",$Vehicule,"Charge")*$Vehicule_Nbr*$Bonus_Skill;
			$Charge=floor($Charge/100*$Qty);*/
			if($Fret !=200)
				UpdateData("Regiment","Fret_Qty",-$Qty,"ID",$Regiment);
			/*$Fret_Qty=GetData("Regiment","ID",$Regiment,"Fret_Qty");
			if($Fret_Qty <=0)
				SetData("Regiment","Fret",0,"ID",$Regiment);*/			
			switch($Fret)
			{
				case 8: case 13: case 20: case 30: case 40: case 50: case 60: case 75: case 90: case 105: case 125: case 150:
					UpdateData($Unit_table,"Stock_Munitions_".$Fret,$Stock,"ID",$Action);
					AddEvent("Avion",$EventMun,$Fret,$OfficierID,$Action,$Cible,$Stock,$Regiment);
				break;
				case 80:
					//$Stock=floor($Charge/10);
					AddEvent("Avion",$EventMun,$Fret,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_".$Fret,$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_".$Fret,$Stock,"ID",$Action,10000);
				break;
				case 200:
					if($Placement ==8)$Placement=11;
					$con=dbconnecti();
					$reset2=mysqli_query($con,"UPDATE Regiment_IA SET Lieu_ID='$Cible',Placement='$Placement',Position=4,Camouflage=1,Move=1,Visible=1 WHERE ID='$Action'");
					$reset3=mysqli_query($con,"UPDATE Regiment SET Fret=0,Fret_Qty=0 WHERE ID='$Regiment'");
					mysqli_close($con);
					AddEventFeed($EventTr,200,$OfficierID,$Action,$Cible,$Stock,$Regiment);
				break;
				case 300:
					//$Stock=floor($Charge/300);
					AddEvent("Avion",$EventMun,300,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_300",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_300",$Stock,"ID",$Action,5000);
					elseif($Unit_table =="Regiment")
						UpdateData($Unit_table,"Stock_Charges",$Stock,"ID",$Action,5000);
				break;
				case 400:
					//$Stock=floor($Charge/400);
					AddEvent("Avion",$EventMun,400,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_400",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_400",$Stock,"ID",$Action,5000);
					elseif($Unit_table =="Regiment")
						UpdateData($Unit_table,"Stock_Mines",$Stock,"ID",$Action,5000);
				break;
				case 800:
					//$Stock=floor($Charge/800);
					AddEvent("Avion",$EventMun,800,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_800",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_800",$Stock,"ID",$Action,1000);
					elseif($Unit_table =="Regiment")
					{
						if($country ==9)
						{
							UpdateData($Unit_table,"Stock_Munitions_530",$Stock,"ID",$Action,1000);
							UpdateData($Unit_table,"Stock_Munitions_610",$Stock,"ID",$Action,1000);
						}
						else
							UpdateData($Unit_table,"Stock_Munitions_530",$Stock,"ID",$Action,1000);
					}
				break;
				case 888:
					$Pays_eni=GetData("Lieu","ID",$Cible,"Flag");
					UpdateData("Pays","Special_Score",1,"ID",$Pays_eni);
					AddEventFeed(320,$Regiment,$OfficierID,$Action,$Cible);
					SetData("Regiment","Fret",0,"ID",$Regiment);
				break;
				case 1087:
					//$Stock=$Charge;
					UpdateData($Unit_table,"Stock_Essence_87",$Stock,"ID",$Action);
					AddEvent("Avion",$EventEss,87,$OfficierID,$Action,$Cible,$Stock,$Regiment);
				break;
				case 1001:
					//$Stock=$Charge;
					UpdateData($Unit_table,"Stock_Essence_1",$Stock,"ID",$Action);
					AddEvent("Avion",$EventEss,1,$OfficierID,$Action,$Cible,$Stock,$Regiment);
				break;
				case 1100:
					//$Stock=$Charge;
					if($Unit_table =="Regiment")
						UpdateData($Unit_table,"Stock_Essence_87",$Stock,"ID",$Action);
					else
						UpdateData($Unit_table,"Stock_Essence_100",$Stock,"ID",$Action);
					AddEvent("Avion",$EventEss,100,$OfficierID,$Action,$Cible,$Stock,$Regiment);
				break;
				case 9050:
					//$Stock=floor($Charge/50);
					AddEvent("Avion",$EventMun,9050,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_50",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_50",$Stock,"ID",$Action,10000);
				break;
				case 9125:
					//$Stock=floor($Charge/125);
					AddEvent("Avion",$EventMun,9125,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_125",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_125",$Stock,"ID",$Action,5000);
				break;
				case 9250:
					//$Stock=floor($Charge/250);
					AddEvent("Avion",$EventMun,9250,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_250",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_250",$Stock,"ID",$Action,2500);
				break;
				case 9500:
					//$Stock=floor($Charge/500);
					AddEvent("Avion",$EventMun,9500,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_500",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_500",$Stock,"ID",$Action,1000);
				break;
				case 10000:
					//$Stock = floor($Charge/1000);
					AddEvent("Avion",$EventMun,10000,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_1000",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_1000",$Stock,"ID",$Action,500);
				break;
				case 11000:
					//$Stock = floor($Charge/2000);
					AddEvent("Avion",$EventMun,11000,$OfficierID,$Action,$Cible,$Stock,$Regiment);
					if($Unit_table =="Lieu")
						UpdateData($Unit_table,"Stock_Bombes_2000",$Stock,"ID",$Action);
					elseif($Unit_table =="Unit")
						UpdateData($Unit_table,"Bombes_2000",$Stock,"ID",$Action,100);
				break;
				default:
				break;
			}
			if($EventTr ==116)
				$XP_up=floor($Stock/10);
			else
				$XP_up=floor($Stock/500);
			if($XP_up >=1)
			{
				$Division=GetData("Officier","ID",$OfficierID,"Division");
				if($Unit_table =="Regiment" and $Division >0 and $Division ==$Division_r and $Officier_cible !=$OfficierID)
					$XP_up*=2;
				if($XP_up >100)
					$XP_up=100;
				UpdateData("Regiment","Experience",$XP_up,"ID",$Regiment);
				UpdateData("Officier","Avancement",$XP_up,"ID",$OfficierID);
				UpdateData("Officier","Note",1,"ID",$OfficierID);
			}
			UpdateCarac($OfficierID,"Credits",-$CT,"Officier");
			if($Fret ==200)
			{
				$img="<img src='images/debarquement.jpg'>";
				$mes="Vous débarquez <b>".$Charge."</b> troupes!";
			}
			else
			{
				$img="<img src='images/fret.jpg'>";
				$mes="Vous ravitaillez ".$ravit_txt;
			}
			$titre="Ravitaillement";	
			$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
			include_once('./default.php');
		}
		else
			echo "<h6>Vous n'avez aucun chargement!</h6>";
	}
	else
		echo "<h6>Vous devez sélectionner une quantité!</h6>";
}
?>