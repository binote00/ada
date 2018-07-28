<?php
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	$Reg = Insec($_POST['Reg']);
	$Veh = Insec($_POST['Veh']);
	$Cible = Insec($_POST['Cible']);
	$Conso = Insec($_POST['Conso']);
	$Bomb = Insec($_POST['Bomb']);
	$CT = Insec($_POST['CT']);
	$country=$_SESSION['country'];
	$choix="";
	SetData("Officier","Atk",1,"ID",$OfficierID);
	SetData("Officier","Heure_Mission",date('H'),"ID",$OfficierID);
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	if($Credits >=$CT and $CT >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Meteo,Zone,Flag FROM Lieu WHERE ID='$Cible'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : gass-lieu');
		$resultv=mysqli_query($con,"SELECT Vitesse,mobile,Type,Portee FROM Cible WHERE ID='$Veh'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : gass-veh');
		$resultr=mysqli_query($con,"SELECT Experience,Vehicule_Nbr,Placement,Visible FROM Regiment WHERE ID='$Reg'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : gass-reg');
		mysqli_close($con);
		if($resultr)
		{
			while($datar=mysqli_fetch_array($resultr,MYSQLI_ASSOC))
			{
				$Reg_xp=$datar['Experience'];
				$Veh_Nbr=$datar['Vehicule_Nbr'];
				$Placement=$datar['Placement'];
				$Visible=$datar['Visible'];
			}
			mysqli_free_result($resultr);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$meteo=$data['Meteo'];
				$Zone=$data['Zone'];
				$Flag=$data['Flag'];
			}
			mysqli_free_result($result);
		}
		if($resultv)
		{
			while($data=mysqli_fetch_array($resultv,MYSQLI_ASSOC))
			{
				$Vitesse=$data['Vitesse'];
				$Portee=$data['Portee'];
				$Vitesse=Get_LandSpeed($Vitesse,$data['mobile'],$Zone,0,$data['Type']);
				if($Flag ==$country)$Vitesse+=10;
			}
			mysqli_free_result($resultv);
			unset($data);
		}	
		$Range_1=($Vitesse*100)+$Reg_xp;
		$Range_2=$Portee+$Reg_xp;
		if($Range_1 >$Range_2)
			$Range=$Range_1;
		else
			$Range=$Range_2;
		$choix_dist="";
		if($Zone ==2 or $Zone ==3 or $Zone ==5 or $Zone ==7 or $Zone ==9 or $Zone ==11)
		{
			$Step=100;
			if($meteo <-69)
				$Max_Range=300;
			else
				$Max_Range=500;
		}
		elseif($Zone ==1 or $Zone ==4)
		{
			$Step=100;
			if($meteo < -69)
				$Max_Range=500;
			elseif($meteo <-9)
				$Max_Range=700;
			else
				$Max_Range=1000;
		}
		else //Zone 0 et 8(désert et plaine)
		{
			$Step=100;
			if($meteo <-69)
				$Max_Range=500;
			elseif($meteo <-9)
				$Max_Range=1500;
			else
				$Max_Range=2500;					
		}
		if($Max_Range >$Range)$Max_Range=$Range;			
		for($i=$Step;$i<=$Max_Range;$i+=$Step)
		{
			if($i >$Max_Range and $i >300)
				break;
			$choix_dist.="<option value='".$i."'>".$i."m</option>";
		}
		$Distance_tir="<tr><th>Distance de tir 
			<select name='distance' class='form-control' style='width: 100px'>
			<option value='100'>100m</option>
			".$choix_dist."
			</select></th></tr>";		
		if($Veh_Nbr >0)
		{
			if(!$Visible and ($Zone ==3 or $Zone ==4 or $Zone ==5 or $Zone ==7 or $Zone ==9)) //Bonus infanterie en terrain difficile
				$Range+=3000;
			$Pass=false;
			$mes.="Vos troupes lancent l'assaut!";
			$query="(SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Officier_ID,r.Pays FROM Regiment as r,Cible as c 
			WHERE r.Vehicule_ID=c.ID AND c.Portee<='$Range' AND r.Placement='$Placement' AND r.Position=8 AND c.mobile NOT IN (4,5)
			AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Visible=1)
			UNION ALL (SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Officier_ID,r.Pays FROM Regiment_IA as r,Cible as c 
			WHERE r.Vehicule_ID=c.ID AND c.Portee<='$Range' AND r.Placement='$Placement' AND r.Position=8 AND c.mobile NOT IN (4,5)
			AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Pays<>'$country' AND r.Visible=1)";
			if($OfficierID >3)UpdateData("Officier","Credits",-$CT,"ID",$OfficierID);
			$CT=99;
			//Scan Pos
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					if($data['Position'] ==8)
						$Pos_icon="<img src='images/mortar.png' title='Sous le feu'>";
					else
						$Pos_icon="";
					$choix.="<Input type='Radio' name='Action' value='".$data['ID']."_".$data['Officier_ID']."'>- ".$data['Vehicule_Nbr']."<img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'> <img src='".$data['Pays']."20.gif'> ".$Pos_icon."<br>";
				}
			}			
			$img=Afficher_Image('images/assault.jpg',"images/image.png","");
			$menu="<form action='index.php?view=ground_pl' method='post'>
				<input type='hidden' name='CT' value='".$CT."'>
				<input type='hidden' name='Veh' value='".$Veh."'>
				<input type='hidden' name='Reg' value='".$Reg."'>
				<input type='hidden' name='Pass' value='".$Pass."'>
				<input type='hidden' name='Max_Range' value='".$Max_Range."'>
				<table class='table'>
					<thead><tr><th>Cibles repérées ".GetPlace($Placement)."</th></tr></thead>
					<tr><td align='left'><div style='overflow:auto;'>".$choix."</div>
							<Input type='Radio' name='Action' value='0' checked>- Annuler l'attaque.<br>
					</td></tr>".$Distance_tir."
				</table>
				<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
				<div class='alert alert-info'>Seules les unités 'Sous le feu' <img src='images/mortar.png' title='Sous le feu'> peuvent être ciblées par un assaut.</div>";
			include_once('./default.php');
		}
		else
			echo "<br>Ne disposant plus d'aucune troupe, vous n'avez d'autre choix que de rejoindre vos positions de départ!";
	}
	else
		echo "<br>Crédits Temps insuffisants!";
}
?>