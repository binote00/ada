<?php
require_once('./jfv_inc_sessions.php');
$Officier_pvp=$_SESSION['Officier_pvp'];
if($Officier_pvp >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_inc_pvp.php');
	$Reg=Insec($_POST['Reg']);
	$Faction=Insec($_POST['Camp']);
	$Battle=Insec($_POST['Battle']);
	$Bomb=Insec($_POST['Bomb']);
	$choix="";
	$go=true;
	if($go)
	{
		$Reg_xp=50;
		$Cible=GetCiblePVP($Battle);
		$con=dbconnecti();
		$result2=mysqli_query($con,"SELECT Flag,Zone,Meteo FROM Lieu WHERE ID='$Cible'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pldef-lieu');
		$result=mysqli_query($con,"SELECT Vehicule_ID,Vehicule_Nbr,Placement,Position,Distance,HP FROM Regiment_PVP WHERE ID='$Reg'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pldef-reg');
		//$reseto=mysqli_query($con,"UPDATE Officier_PVP SET Atk=1 WHERE ID='$Officier_pvp'");
		mysqli_close($con);
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Flag=$data['Flag'];
				$Zone=$data['Zone'];
				$meteo=$data['Meteo'];
			}
			mysqli_free_result($result2);
			unset($data);
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$HP=$data['HP'];
				$Veh=$data['Vehicule_ID'];
				$Vehicule_Nbr=$data['Vehicule_Nbr'];
				$Placement=$data['Placement'];
				$Pos=$data['Position'];
				$Distance=$data['Distance'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		$_SESSION['ground_bomb']=true;
		if($Bomb ==1)
		{
			$con=dbconnecti();
			$resultc=mysqli_query($con,"SELECT Pays,Type,Portee FROM Cible WHERE ID='$Veh'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pldef-veh');
			mysqli_close($con);
			if($resultc)
			{
				while($datac=mysqli_fetch_array($resultc,MYSQLI_ASSOC))
				{
					$Pays=$data['Pays'];
					$Type=$datac['Type'];
					$Range=$datac['Portee'];
				}
				mysqli_free_result($resultc);
			}
			$Range_Battle=$Range;
			$Range_Battle+=($Reg_xp*2);
			if($Muns ==8)$Range_Battle /=2;
			if($Pos ==2 or $Pos ==3 or $Pos ==9 or $Pos ==10 or $Pos ==26)$Range_Battle /=2;
			if($meteo <-69)$Range_Battle /=2;
			$dive="ground_bomb_pvp";
			$Veh_Nbr=1;
		}
		else
		{
			$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Pays,Nom,Vitesse,mobile,Carbu_ID,Type,Blindage_f,Portee,HP FROM Cible WHERE ID='$Veh'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : pldef-veh');
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Pays=$data['Pays'];
					$Nom=$data['Nom'];
					$Vitesse=$data['Vitesse'];
					$mobile=$data['mobile'];
					$Type=$data['Type'];
					$Blindage=$data['Blindage_f'];
					$Range=$data['Portee'];
					$Veh_Carbu=$data['Carbu_ID'];
					if($mobile ==5)$hp_good=round(($HP/$data['HP'])*100);
					$Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type,$hp_good);
				}
				mysqli_free_result($result);
				unset($data);
			}
			if($Bomb ==2)
			{
				if($Placement ==4)$Range=20000;
				//Battle
				$Range_Battle=$Range+($Range-$Distance);//
				$Range_Battle+=($Reg_xp*2);
				if($Pos ==25)$Range_Battle*=2;
				if($Muns ==8)$Range_Battle/=2;
				if($Pos ==26)$Range_Battle/=2;
				$dive="ground_torp_pvp";
			}
			else
			{
				$Range=($Vitesse*100)+($Reg_xp*2);
				//Battle
				$Range_Battle=$Range+($Range-$Distance);//
				if($Pos ==2 or $Pos ==3 or $Pos ==9 or $Pos ==10)$Range_Battle/=2;
				if($mobile ==7)$Range_Battle*=2;
				$dive="ground_pl_pvp";
				if($Bomb !=3)
				{
					if($mobile !=5)
						$Armement="<br><b>Armement </b><select name='armement' style='width: 200px'><option value='0'>Laisser le commandant de compagnie décider</option><option value='1'>Imposer l'utilisation de l'armement de base</option></select>";
					$Repli="<br><b>Repli </b>
					<select name='repli' style='width: 200px'>
					<option value='0'>Continuer l'attaque quoi qu'il arrive</option>
					<option value='1'>Se replier si nécessaire</option>
					<option value='2'>Se replier dès que possible</option>
					</select>";
				}
			}
			if($Bomb ==2)
				$Veh_Nbr=1;
			else
				$Veh_Nbr=$Vehicule_Nbr;
			$choix_dist="";
			$Min_Range=500;
			if($Zone ==6)
			{
				$Step=500;
				if($meteo <-69)
					$Max_Range=5000;
				elseif($meteo <-9)
					$Max_Range=10000;
				else
					$Max_Range=20000;
			}
			elseif($Bomb ==2)
			{
				$Step=500;
				if($meteo <-69)
					$Max_Range=500;
				elseif($meteo <-9)
					$Max_Range=1000;
				else
					$Max_Range=2000;
			}
			elseif($Zone ==2 or $Zone ==3 or $Zone ==5 or $Zone ==7 or $Zone ==9 or $Zone ==11)
			{
				$Min_Range=100;
				$Step=100;
				if($meteo <-69)
					$Max_Range=200;
				else
					$Max_Range=500;
			}
			elseif($Zone ==1 or $Zone ==4)
			{
				$Min_Range=100;
				$Step=100;
				if($meteo <-69)
					$Max_Range=500;
				elseif($meteo <-9)
					$Max_Range=700;
				else
					$Max_Range=1000;
			}
			else //Zone 0 et 8 (désert et plaine)
			{
				$Step=100;
				if($meteo <-69)
					$Max_Range=500;
				elseif($meteo <-9)
					$Max_Range=1500;
				else
					$Max_Range=2500;
			}
			if($Range_Battle >0 and $Max_Range >$Range_Battle)$Max_Range=$Range_Battle;			
			if($Bomb ==3)
				$choix_dist.="<option value='".$Min_Range."'>".$Min_Range."m</option><option value='".$Max_Range."'>".$Max_Range."m</option>";
			else
			{
				for($i=$Min_Range;$i<=$Max_Range;$i+=$Step)
				{
					if($i >$Max_Range)
						break;
					$choix_dist.="<option value='".$i."'>".$i."m</option>";
				}
			}
			$Distance_tir="<br><b>Distance de tir </b> 
				<select name='distance' style='width: 100px'>".$choix_dist."</select>";
		}		
		if($Veh_Nbr >0)
		{
			if($Bomb ==1)
			{
				if($Range >2500)
				{
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.Distance FROM Regiment_PVP as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Placement<>9 AND r.Pays<>'$Pays' AND r.Visible=1 AND r.Position<>25";
				}
				else
				{
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.Distance FROM Regiment_PVP as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Placement='$Placement' AND r.Pays<>'$Pays' AND r.Visible=1 AND r.Position<>25";
				}
			}
			elseif($Zone ==6 or $Placement ==8 or $Bomb ==2) //Torpiller
			{
				$Pass=$Vehicule_Nbr;
				$con=dbconnecti();
				$result_inf=mysqli_query($con,"SELECT r.ID,r.Experience,r.Officier_ID FROM Regiment_PVP as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.mobile=5 AND c.Type IN (15,16,17,18,19,20) AND r.Placement='$Placement' AND r.Position=21");
				mysqli_close($con);
				if($result_inf)
				{
					$Inf_eni=mysqli_num_rows($result_inf);
					while($datai=mysqli_fetch_array($result_inf,MYSQLI_NUM))
					{
						$Inf_couv=$datai[0];
						$Exp_eni=$datai[1];
						$Infoff_couv=$datai[2];
						if(($Exp_eni+($Inf_eni*100))>(mt_rand(0,$Reg_xp)+$Vitesse))
							$Pass-=1;
					}
					mysqli_free_result($result_inf);
				}
				if($Placement ==4)$Range_Battle=20000; //Au port tous les navires de surface peuvent être ciblés
				if($Inf_eni >0 and $Pass <($Inf_eni/2))
				{
					$intro.="<p>".$Inf_eni." navires ennemis forment un écran vous empêchant d'atteindre les navires de seconde ligne de l'ennemi</p>";
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.Distance FROM Regiment_PVP as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.mobile=5 AND c.Type IN (15,16,17,18,19,20) AND r.Pays<>'$Pays' AND r.Visible=1";
				}
				else
				{
					if($Pass >$Vehicule_Nbr)$Pass=$Vehicule_Nbr;
					if($Pass >1)
						$intro.="<p>".$Pass." ".$Nom." parviennent à profiter d'une brèche dans la ligne tenue par l'ennemi à la vitesse de ".$Vitesse."km/h! La visibilité est d'environ ".$Range_Battle."m</p>";
					else
						$intro.="<p>1 ".$Nom." parvient à profiter d'une brèche dans la ligne tenue par l'ennemi! La visibilité est d'environ ".$Range_Battle."m</p>";
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.Distance FROM Regiment_PVP as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND r.Position NOT IN (25,26) AND c.mobile=5 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Pays<>'$Pays' AND r.Visible=1";					
				}
				if($DB=="Regiment")
					$CT=99;
				else
					$CT=98;
			}
			else
			{
				$Detect_infiltres=false;
				$Pass=$Vehicule_Nbr;
				$con=dbconnecti();
				$result_inf=mysqli_query($con,"SELECT r.Experience,r.Moral,r.ID,r.Officier_ID,r.Vehicule_Nbr,c.Detection,c.Categorie FROM Regiment_PVP as r,Cible as c,Pays as p 
				WHERE r.Vehicule_ID=c.ID AND r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Moral >50 AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Categorie IN (5,6,9) AND r.Placement='$Placement' AND r.Position=10");
				mysqli_close($con);
				if($result_inf)
				{
					$Inf_eni=mysqli_num_rows($result_inf);
					while($datai=mysqli_fetch_array($result_inf,MYSQLI_NUM))
					{
						$Moral_eni=$datai[1]+1;
						$Inf_couv=$datai[2];
						$Infoff_couv=$datai[3];
						$Exp_eni=ceil($datai[0]/100*$Moral_eni);
						$Def_line=$Exp_eni+($Inf_eni*100);
						$Atk_line=mt_rand(0,$Reg_xp)+$Vitesse;
						if($Def_line >=$Atk_line)
						{
							if($mobile !=3 and $datai[6] ==9) //AT
								$Pass=$datai[4];
							elseif($datai[4] >=250)
								$Pass-=11;
							elseif($datai[4] >=225)
								$Pass-=10;
							elseif($datai[4] >=200)
								$Pass-=9;
							elseif($datai[4] >=175)
								$Pass-=8;
							elseif($datai[4] >=150)
								$Pass-=7;
							elseif($datai[4] >=125)
								$Pass-=6;
							elseif($datai[4] >=100)
								$Pass-=5;
							elseif($datai[4] >=75)
								$Pass-=4;
							elseif($datai[4] >=50)
								$Pass-=3;
							elseif($datai[4] >=25)
								$Pass-=2;
							else
								$Pass-=1;
							if($datai[5] >10)
								$Detect_infiltres=true;
						}
						if($Admin)
							$intro.="<br>".$Inf_couv."e Cie => Atk=".$Atk_line." <> ".$Def_line."=Def (".$Exp_eni." XP / ".$Moral_eni." Moral)";
					}
					mysqli_free_result($result_inf);
				}
				if(!$Detect_infiltres and $mobile ==3 and !$Visible and ($Zone ==2 or $Zone ==3 or $Zone ==4 or $Zone ==5 or $Zone ==7 or $Zone ==10)) //Bonus infanterie en terrain difficile
				{
					$Range_Battle*=2; //+3000
					$intro.="<br>Votre infanterie parvient à s'approcher de l'ennemi et à le surprendre par ses capacités furtives!";
				}
				if($Inf_eni >0 and $Pass <$Inf_eni)
				{
					$intro.="<p>".$Inf_eni." Cie d'infanterie ennemies forment un front continu vous empêchant d'atteindre les lignes arrières de l'ennemi</p>";
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.Distance FROM Regiment_PVP as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND c.Categorie IN (5,6,9) AND r.Pays<>'$Pays' AND r.Visible=1";
					if($Pass<0)$Pass=0;
				}
				else
				{
					if($Pass >=$Vehicule_Nbr)$Pass=$Vehicule_Nbr;
					if($Pass >1)
						$intro.="<p>".$Pass." ".$Nom." parviennent à profiter d'une brèche dans le front tenu par l'ennemi!</p>";
					else
						$intro.="<p>1 ".$Nom." parvient à profiter d'une brèche dans la ligne tenue par l'ennemi!</p>";
					$query="SELECT r.ID,r.Vehicule_ID,r.Vehicule_Nbr,r.Position,r.Pays,r.Officier_ID,c.Portee,r.Placement,r.Distance FROM Regiment_PVP as r,Cible as c 
					WHERE r.Vehicule_ID=c.ID AND r.Placement='$Placement' AND c.mobile NOT IN (4,5) AND r.Lieu_ID='$Cible' AND r.Vehicule_Nbr >0 AND r.Pays<>'$Pays' AND r.Visible=1";
				}
				if($DB=="Regiment")
					$CT=99;
				else
					$CT=98;
			}
			//Scan Pos
			$con=dbconnecti();
			$result=mysqli_query($con,$query);
			mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					if($data['Distance'])
						$data['Portee']=$data['Distance'];
					if($data['Position'] ==8)
						$Pos_icon="<img src='images/mortar.png' title='Sous le feu'>";
					else
						$Pos_icon="";
					if($data['Portee'] <500)
						$Range_fix="100";
					elseif($data['Portee'] >5000)
						$Range_fix="5500";
					else
					{
						$data['Portee']=round($data['Portee']/500)*500;
						$Range_fix=$data['Portee'];
					}
					if($data['Placement'] !=$Placement)
					{
						$Pos_icon.="<img src='images/strat0.png' title='Zone adjacente'>";
						$Range_fix="5500";
					}
					if($data['Position'] ==11)
					{
						$data['Vehicule_ID']=5000;
						$data['Vehicule_Nbr']=floor($data['Vehicule_Nbr']/10);
					}
					$choix="choix_".$Range_fix;
					if($data['Portee'] <=$Range_Battle)
						$$choix.="<Input type='Radio' name='Action' value='".$data['ID']."_".$data['Officier_ID']."'>".$data['Vehicule_Nbr']."<img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'> <img src='".$data['Pays']."20.gif'> ".$Pos_icon."<br>";
					else
						$$choix.="<Input type='Radio' name='Action' value='".$data['ID']."_".$data['Officier_ID']."' disabled title='Hors de portée'>".$data['Vehicule_Nbr']."<img src='images/vehicules/vehicule".$data['Vehicule_ID'].".gif'> <img src='".$data['Pays']."20.gif'> ".$Pos_icon."<br>";
				}
				mysqli_free_result($result);
			}
			if($Bomb ==2)
			{
				$titre="Torpillage";
				$img=Afficher_Image('images/torpillage.jpg',"images/image.png","");
			}
			elseif($Zone ==6 or $Placement ==8)
			{
				$titre="Combat Naval";
				$img=Afficher_Image('images/nav_tirer.jpg',"images/image.png","");
			}
			else
			{
				$titre="Combat Terrestre";
				$img=Afficher_Image('images/assault.jpg',"images/image.png","");
			}
			/*if($Officier_pvp ==1)
			{
				echo"<pre>";
				print_r(get_defined_vars());
				echo"</pre>";
			}*/
			if($Bomb ==1)
				$Aide="Vous ne pouvez attaquer qu'une cible repérée (via une reco terrestre, navale ou aérienne) située à portée. Plus votre unité aura une portée longue, plus elle pourra attaquer une cible éloignée";
			else
				$Aide="Vous ne pouvez attaquer qu'une cible repérée (via une reco terrestre, navale ou aérienne) située à portée. Plus votre unité est rapide (vitesse modifiée en fonction du terrain et du système de propulsion), plus elle pourra attaquer une cible éloignée";
			$mes="<form action='index.php?view=".$dive."' method='post'>
				<input type='hidden' name='Battle' value='".$Battle."'>
				<input type='hidden' name='Camp' value='".$Faction."'>
				<input type='hidden' name='Reg' value='".$Reg."'>
				<input type='hidden' name='Pass' value='".$Pass."'>
				<input type='hidden' name='Line' value='".$Inf_eni."'>
				<input type='hidden' name='Max_Range' value='".$Max_Range."'>
				<h2>Cibles repérées ".GetPlace($Placement)."</h2><div style='overflow:auto; height: 640px;'><div class='row'><div class='col-md-10'><div class='col-md-1'><p><b>Ligne de front</b></p>".$choix_100."</div><div class='col-md-1'><p><b>500m</b></p>".$choix_500."</div><div class='col-md-1'><p><b>1000m</b></p>".$choix_1000."</div><div class='col-md-1'><p><b>1500m</b></p>".$choix_1500."</div>
				<div class='col-md-1'><p><b>2000m</b></p>".$choix_2000."</div><div class='col-md-1'><p><b>2500m</b></p>".$choix_2500."</div><div class='col-md-1'><p><b>3000m</b></p>".$choix_3000."</div><div class='col-md-1'><p><b>3500m</b></p>".$choix_3500."</div>
				<div class='col-md-1'><p><b>4000m</b></p>".$choix_4000."</div><div class='col-md-1'><p><b>4500m</b></p>".$choix_4500."</div><div class='col-md-1'><p><b>5000m</b></p>".$choix_5000."</div></div><div class='col-md-2'><p><b>+5000m</b></p>".$choix_5500."</div></div></div>
				<Input type='Radio' name='Action' value='0' checked>- Annuler l'attaque.<br>
				".$Distance_tir.$Repli.$Armement."
				<input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'> 
				<span><img src='images/help.png' title=\"".$Aide."\</span></a></form>";
			include_once('./default.php');
		}
		else
			echo "<h6>Ne disposant plus d'aucune troupe, vous n'avez d'autre choix que de rejoindre vos positions de départ!</h6>";
	}
	else
		echo "<h6>Crédits Temps insuffisants!</h6>";
}
?>