<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Action=Insec($_POST['Action']);
$Unite=Insec($_POST['Unite']);
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0 AND $Unite >0)
{
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$MIA=GetData("Pilote","ID",$PlayerID,"MIA");
	if(!$MIA and $_SESSION['Distance'] ==0 and $Unite and $PlayerID >0)
	{		
		$Credits=false;
		/*$con=dbconnecti();
		$Pilotes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote WHERE Unit='$Unite' AND Actif=0"),0);
		mysqli_close($con);		
		if($Pilotes >2)
		{*/
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Reputation,Gestion,Commandement FROM Pilote WHERE ID='$PlayerID'");
			$resultu=mysqli_query($con,"SELECT Nom,Pays,Base,Reputation,Pers1,Pers2,Pers3,Pers4,Pers5,Pers6,Pers7,Pers8,Pers9,Pers10 FROM Unit WHERE ID='$Unite'");
			$resulti=mysqli_query($con,"SELECT COUNT(*),SUM(Industrie) FROM Lieu WHERE Flag='$country' AND TypeIndus<>'' AND Flag_Usine='$country'");
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Reputation=$data['Reputation'];
					$Gestion=$data['Gestion'];
					$Commandement=$data['Commandement'];
				}
				mysqli_free_result($result);
			}
			if($resultu)
			{
				while($data=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
				{
					$Unite_Nom=$data['Nom'];
					$Pays=$data['Pays'];
					$Base=$data['Base'];
					$Reputation_Unite=$data['Reputation'];
					$Pers1=$data['Pers1'];
					$Pers2=$data['Pers2'];
					$Pers3=$data['Pers3'];
					$Pers4=$data['Pers4'];
					$Pers5=$data['Pers5'];
					$Pers6=$data['Pers6'];
					$Pers7=$data['Pers7'];
					$Pers8=$data['Pers8'];
					$Pers9=$data['Pers9'];
					$Pers10=$data['Pers10'];
				}
				mysqli_free_result($resultu);
			}
			$Pers=array($Pers1,$Pers2,$Pers3,$Pers4,$Pers5,$Pers6,$Pers7,$Pers8,$Pers9,$Pers10);
			$Personnel=array_count_values($Pers);				
			//GetData Lieu		
			$con=dbconnecti();		
			$result=mysqli_query($con,"SELECT Zone,Latitude,Longitude,Citernes,Camions,Port_Ori,Port,NoeudF_Ori,NoeudF FROM Lieu WHERE ID='$Base'");		
			mysqli_close($con);		
			if($result)		
			{		
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))	
				{
					$Zone=$data['Zone'];
					$Base_Lat=$data['Latitude'];
					$Base_Long=$data['Longitude'];
					$Citernes=$data['Citernes'];
					$Camions=$data['Camions'];
					$Port_ori_base=$data['Port_Ori'];
					$Gare_ori_base=$data['NoeudF_Ori'];
					$Port_base=$data['Port'];
					$Gare_base=$data['NoeudF'];
				}
				mysqli_free_result($result);
				unset($data);
			}	
			if(!$Port_ori_base)
				$Port_base=100;
			if(!$Gare_ori_base)
				$Gare_base=100;
			if($Port_base !=100 and $Port_base >=$Gare_base)
				$Inf_base=$Port_base;
			elseif($Gare_base !=100 and $Gare_base >$Port_base)
				$Inf_base=$Gare_base;
			else
				$Inf_base=100;				
			if($resulti)
			{
				if($data=mysqli_fetch_array($resulti,MYSQLI_NUM))
				{
					if($data[0] >0)
						$Efficacite_prod=round($data[1]/$data[0]);
					else
						$Efficacite_prod=0;
				}
			}
			//Outre-Mer ou anglais
			if($Base_Lat <38.2 or $Base_Long >70 or $Pays ==2 or $Zone ==6)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT COUNT(*),SUM(Port) FROM Lieu WHERE Flag='$country' AND Port_Ori >0 AND Flag_Port='$country'");
				$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$country'");
				mysqli_close($con);
				if($result)
				{
					if($data=mysqli_fetch_array($result,MYSQLI_NUM))
					{
						if($data[0] >0)
							$Efficacite_ravit_port=round($data[1]/$data[0]);
						else
							$Efficacite_ravit_port=0;
					}
					mysqli_free_result($result);
				}
				if($result2)
				{
					if($data2=mysqli_fetch_array($result2,MYSQLI_NUM))
					{
						if($data2[0] >0)
							$Efficacite_ravit=round($data2[1]/$data2[0]);
						else
							$Efficacite_ravit=0;
					}
					mysqli_free_result($result2);
				}
				$Efficacite_ravit=round(($Efficacite_ravit+($Efficacite_ravit_port*2))/3);
			}
			else
			{
				$Lat_base_min=$Base_Lat-1;
				$Lat_base_max=$Base_Lat+1;
				$Long_base_min=$Base_Long-3;
				$Long_base_max=$Base_Long+3;				
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND ValeurStrat >0 AND Flag_Gare='$country'");
				$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND Flag_Gare='$country' 
				AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max')");
				mysqli_close($con);
				if($result)
				{
					if($data=mysqli_fetch_array($result,MYSQLI_NUM))
					{
						if($data[0] >0)
							$Efficacite_ravit1=round($data[1]/$data[0]);
						else
							$Efficacite_ravit1=0;
					}
					mysqli_free_result($result);
				}
				if($result2)
				{
					if($data2=mysqli_fetch_array($result2,MYSQLI_NUM))
					{
						if($data2[0] >0)
							$Efficacite_ravit2=round($data2[1]/$data2[0]);
						else
							$Efficacite_ravit2=0;
					}
					mysqli_free_result($result2);
				}
				$Efficacite_ravit=round(($Efficacite_ravit1+($Efficacite_ravit2*2))/3);
			}
			//Malus ravitaillement par saison ou terrain
			$Saison=$_SESSION['Saison'];
			if($Base_Long >20 and $Base_Lat >43)		//Front Est
			{
				if($Saison ==2)	// Printemps (boue dégel)
					$Camions +=20;
				elseif($Saison ==1) // Automne
					$Camions +=5;
				elseif($Saison ==0) // Hiver
					$Camions +=25;
			}
			elseif($Base_Lat >55) // Europe du nord
			{
				if($Saison ==0) // Hiver
					$Camions +=25;
			}
			elseif($Base_Lat >43) // Europe continentale
			{
				if($Saison ==0) // Hiver
					$Camions +=10;
			}
			elseif($Base_Lat <33) // Désert
			{
				if($Saison ==3) // Ete (chaleur,pannes)
					$Camions +=5;
			}
			if($Zone ==5 or $Zone ==9 or $Zone ==11)
				$Camions +=20;
			elseif($Zone ==4)
				$Camions +=15;
			elseif($Zone ==3)
				$Camions +=10;
			elseif($Zone ==2 or $Zone ==8)
				$Camions +=5;					
			$Efficacite_ravit_muns=($Efficacite_ravit-$Camions+$Personnel[1])*($Inf_base/100);
			$fournitures=0;
			switch($Action)
			{
				case 1:
					$fournitures=floor((mt_rand(0,1000) + mt_rand(10,$Gestion*250) + ($Reputation/10) + ($Reputation_Unite/1000)) * ($Efficacite_ravit_muns/100));
					if($fournitures <0){$fournitures=0;}
					UpdateData("Unit","Stock_Munitions_8",$fournitures,"ID",$Unite,500000);
					$Credits=-1;
					$img_txt="gestion_muns".$country;
					$units="cartouches de 8mm";
					AddEvent("Avion",102,8,$PlayerID,$Unite,$Base,$fournitures);
				break;
				case 2:
					$fournitures=floor((mt_rand(0,500) + mt_rand(10,$Gestion*100) + ($Reputation/20) + ($Reputation_Unite/1000)) * ($Efficacite_ravit_muns/100));
					if($fournitures <0){$fournitures=0;}
					UpdateData("Unit","Stock_Munitions_13",$fournitures,"ID",$Unite,500000);
					$Credits=-2;
					$img_txt="gestion_muns".$country;
					$units="cartouches de 13mm";
					AddEvent("Avion",102,13,$PlayerID,$Unite,$Base,$fournitures);
				break;
				case 3:
					$fournitures=floor((mt_rand(0,100) + mt_rand(10,$Gestion*100) + ($Reputation/20) + ($Reputation_Unite/1000)) * ($Efficacite_ravit_muns/100));
					if($fournitures <0){$fournitures=0;}
					UpdateData("Unit","Stock_Munitions_20",$fournitures,"ID",$Unite,500000);
					$Credits=-3;
					$img_txt="gestion_muns".$country;
					$units="obus de 20mm";
					AddEvent("Avion",102,20,$PlayerID,$Unite,$Base,$fournitures);
				break;
				case 4:
					$fournitures=floor((mt_rand(0,75) + mt_rand(10,$Gestion*50) + ($Reputation/50) + ($Reputation_Unite/1000)) * ($Efficacite_ravit_muns/100));
					if($fournitures <0){$fournitures=0;}
					UpdateData("Unit","Stock_Munitions_40",$fournitures,"ID",$Unite,100000);
					$Credits=-4;
					$img_txt="gestion_muns".$country;
					$units="obus de 40mm";
					AddEvent("Avion",102,40,$PlayerID,$Unite,$Base,$fournitures);
				break;
				case 5:
					$fournitures=floor((mt_rand(0,50) + mt_rand(10,$Gestion*50) + ($Reputation/50) + ($Reputation_Unite/1000)) * ($Efficacite_ravit_muns/100));
					if($fournitures <0){$fournitures=0;}
					UpdateData("Unit","Stock_Munitions_75",$fournitures,"ID",$Unite,50000);
					$Credits=-5;
					$img_txt="gestion_muns".$country;
					$units="obus de 75mm";
					AddEvent("Avion",102,75,$PlayerID,$Unite,$Base,$fournitures);
				break;
				case 6:
					$fournitures=floor((mt_rand(0,50) + mt_rand(10,$Gestion*20) + ($Reputation/100) + ($Reputation_Unite/1000)) * ($Efficacite_ravit_muns/100));
					if($fournitures <0){$fournitures=0;}
					UpdateData("Unit","Stock_Munitions_90",$fournitures,"ID",$Unite,50000);
					$Credits=-6;
					$img_txt="gestion_muns".$country;
					$units="obus de 90mm";
					AddEvent("Avion",102,90,$PlayerID,$Unite,$Base,$fournitures);
				break;
				case 7:
					$fournitures=floor((mt_rand(0,40) + mt_rand(10,$Gestion*20) + ($Reputation/100) + ($Reputation_Unite/1000)) * ($Efficacite_ravit_muns/100));
					if($fournitures <0){$fournitures=0;}
					UpdateData("Unit","Stock_Munitions_105",$fournitures,"ID",$Unite,50000);
					$Credits=-7;
					$img_txt="gestion_muns".$country;
					$units="obus de 105mm";
					AddEvent("Avion",102,105,$PlayerID,$Unite,$Base,$fournitures);
				break;
				case 8:
					$fournitures=floor((mt_rand(0,30) + mt_rand(10,$Gestion*20) + ($Reputation/100) + ($Reputation_Unite/1000)) * ($Efficacite_ravit_muns/100));
					if($fournitures <0){$fournitures=0;}
					UpdateData("Unit","Stock_Munitions_125",$fournitures,"ID",$Unite,50000);
					$Credits=-8;
					$img_txt="gestion_muns".$country;
					$units="obus de 125mm";
					AddEvent("Avion",102,125,$PlayerID,$Unite,$Base,$fournitures);
				break;
			}			
			$Corps=GetEM_Name($country);
			if($fournitures >0)
			{
				$fournitures_txt="<br>Nous sommes en mesure de vous fournir actuellement <b>".$fournitures." ".$units.".</b>";
				$mes="<p>".$msg_prod."<br>".$msg_ravit."</p><p>Le ".$Corps." vous informe que votre demande a été acceptée.".$fournitures_txt."
					<br><br>Votre unité,le <b>".$Unite_Nom."</b> recevra sous peu les fournitures commandées.</p>";
				//Chance de récupérer 25% des CT en fonction du score de Commandement
				if($Credits <-3)
				{
					if(mt_rand(0,200) <$Commandement)
					{
						$Credits=ceil($Credits*0.75);
					}
				}
				$skills=MoveCredits($PlayerID,3,$Credits);
				UpdateCarac($PlayerID,"Avancement",-$Credits);
				UpdateCarac($PlayerID,"Gestion",-$Credits);
			}
			else
				$mes="Le ".$Corps." vous informe que votre demande a été refusée.<br>Vos stocks ne sont pas suffisants ou votre demande est irréalisable au vu de la situation actuelle.";
			$msg_prod="<img src='images/vehicules/vehicule4003.gif' alt='Efficacité de la production' title='Efficacité de la production'> ".$Efficacite_prod."%";
			$msg_ravit="<img src='images/vehicules/vehicule3002.gif' alt='Efficacité du ravitaillement en munitions' title='Efficacité du ravitaillement en munitions'> ".$Efficacite_ravit_muns." %";
			$img="<table border='1'><tr><td><img src='images/".$img_txt.".jpg'></td></tr></table>";
			$menu ="<p><a title='Retour' href='index.php?view=esc_dca' class='btn btn-default'>Retour au menu de gestion de dca</a></p>";
		/*}
		else
		{
			echo "<p>Votre unité manque de personnel pour cela.</p>";
		}*/
	}
	else
	{
		$titre="MIA";
		$mes="Peut-être la reverrez-vous un jour votre escadrille...";
		$img="<img src='images/unites".$country.".jpg'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>