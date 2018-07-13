<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	$Blesse=Insec($_POST['Blesse']);
	$_SESSION['Distance']=0;
	$_SESSION['PVP']=false;
	RetireCandidat($PlayerID);		
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT Unit,Pays,S_Mission,Victoires,Victoires_atk,Missions,Avancement,S_Avancement_mission FROM Pilote WHERE ID='$PlayerID'") or die('Le jeu a rencontré une erreur, merci de le signaler sur le forum avec la référence suivante : prom-player');
	mysqli_close($con);
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Unite=$data['Unit'];
			$Pays=$data['Pays'];
			$Vic=$data['Victoires'];
			$Vic_atk=$data['Victoires_atk'];
			$Missions=$data['Missions'];
			$Mission=$data['S_Mission'];
			$Avancement=$data['Avancement'];
			$Avancement_mission=$data['S_Avancement_mission'];
		}
		mysqli_free_result($result);
		unset($data);
	}
	//Ne concerne pas les missions d'entrainement
	if($Blesse ==98)
	{
		$titre="Retour au mess";
		$mes.="<div class='alert alert-warning'>Vous retournez au mess, dépité.</div>";
		$img="<p><img src='images/fiesta".$Pays.".jpg'></p>";
	}
	elseif($Blesse ==99)
	{
		$titre="Retour au mess";
		$mes.="<div class='alert alert-warning'>Vous retournez au mess en attendant la prochaine alerte.</div>";
		$img="<p><img src='images/fiesta".$Pays.".jpg'></p>";
		$skills.=MoveCredits($PlayerID,8,2);
		UpdateCarac($PlayerID,"Missions_Jour",-1);
		UpdateCarac($PlayerID,"Missions_Max",-1);
	}
	elseif($Blesse ==2)
	{
		$titre="Blessé";
		$mes.="<div class='alert alert-warning'>Vous espérez recevoir du secours d'ici demain...</div>";
		$img="<p><img src='images/blesse.jpg'></p>";
		$con=dbconnecti();
		$insert=mysqli_query($con,"INSERT INTO Pil_medals (PlayerID,Medal,Value) VALUES ('$PlayerID',1,1)");
		mysqli_close($con);
	}
	elseif($Mission <90 or $Blesse ==-1)
	{	
		$Avancement_end=GetAvancement($Avancement,$Pays);
		if($Avancement_mission and ($Avancement_end[1] >$Avancement_mission))
		{
			if($Avancement_end[1] ==8)$Cdte_txt="<br>Ce grade vous permet de commander une escadrille. Vous pouvez dès à présent demander votre mutation pour votre nouvelle unité.";
			if($Blesse ==-1)
				$mes.="<div class='alert alert-warning'>Vous êtes convoqué par votre commandant qui vous annonce que vos états de service vous valent de nouveaux galons!</div>";
			else
				$mes.="<div class='alert alert-warning'>A votre arrivée, vous êtes reçu par votre commandant qui vous annonce fièrement que vos exploits vous valent de nouveaux galons!</div>";
			$galon=$Avancement_end[1];
			$titre="Promotion";
			$img='<p><img src=\'images/promo'.$Pays.'.jpg\'></p>';
			$menu.='<p>Vous êtes nommé au grade de <b>'.$Avancement_end[0].'</b>!'.$Cdte_txt.'</p>';
			$menu.='<p><img src=\'images/grades/grades'.$Pays.$galon.'.png\'></p>';
			UpdateCarac($PlayerID,"Moral",50);
			AddEvent($Avion_db,32,1,$PlayerID,$Unite,$Cible,$Avancement_end[1]);
		}
		elseif($Avancement_end[1] <$Avancement_mission)
		{
			$galon=$Avancement_end[1];
			$titre="Rétrogradation";
			$mes.="<div class='alert alert-warning'>A votre arrivée, vous êtes reçu par votre commandant qui vous annonce que votre attitude face à l'ennemi vous coûte vos galons!</div>";
			$img='<p><img src=\'images/transfer_no'.$Pays.'.jpg\'></p>';
			$menu.='<p>Vous êtes rétrogradé au grade de <b>'.$Avancement_end[0].'</b>!</p>';
			$menu.='<p><img src=\'images/grades/grades'.$Pays.$galon.'.png\'></p>';
			UpdateCarac($PlayerID,"Moral",-50);
			UpdateCarac($PlayerID,"Reputation",-50);
		}
		else
		{
			function GetRangPromo($Table,$PlayerID,$Missions,$Vic,$Vic_atk,$Blesse)
			{
				//Missions Reco
				$con=dbconnecti();
				$Recos=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Recce WHERE Pilote='$PlayerID' AND Type IN (1,2)"),0);
				$Saves=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Sauvetage WHERE PlayerID='$PlayerID'"),0);
				mysqli_close($con);
				$Recos+=$Saves;				
				$Rang_promo=0;
				if($Missions >250000)
				{
					if($Vic >=40000 or $Vic_atk >=300000 or $Recos >=20000)
					{
						$Rang_promo=10;
					}
					elseif($Vic >=20000 or $Vic_atk >=90000 or $Recos >=10000)
					{
						$Rang_promo=9;
					}
					elseif($Vic >= 12000 or $Vic_atk >= 45000 or $Recos >= 5000)
					{
						$Rang_promo=8;
					}
					elseif($Vic >= 8000 or $Vic_atk >= 24000 or $Recos >= 2000)
					{
						$Rang_promo=7;
					}
					elseif($Vic >= 4000 or $Vic_atk >= 12000 or $Recos >= 1000)
					{
						$Rang_promo=6;
					}
					elseif($Vic >= 2000 or $Vic_atk >= 6000 or $Recos >= 500)
					{
						$Rang_promo=5;
					}
					elseif($Vic >= 1200 or $Vic_atk >= 3000 or $Recos >= 200)
					{
						$Rang_promo=4;
					}
					elseif($Vic >= 800 or $Vic_atk >= 1500 or $Recos >= 100)
					{
						$Rang_promo=3;
					}
					elseif($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
					{
						$Rang_promo=2;
					}
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >125000)
				{
					if($Vic >= 20000 or $Vic_atk >= 90000 or $Recos >= 10000)
					{
						$Rang_promo=9;
					}
					elseif($Vic >= 12000 or $Vic_atk >= 45000 or $Recos >= 5000)
					{
						$Rang_promo=8;
					}
					elseif($Vic >= 8000 or $Vic_atk >= 24000 or $Recos >= 2000)
					{
						$Rang_promo=7;
					}
					elseif($Vic >= 4000 or $Vic_atk >= 12000 or $Recos >= 1000)
					{
						$Rang_promo=6;
					}
					elseif($Vic >= 2000 or $Vic_atk >= 6000 or $Recos >= 500)
					{
						$Rang_promo=5;
					}
					elseif($Vic >= 1200 or $Vic_atk >= 3000 or $Recos >= 200)
					{
						$Rang_promo=4;
					}
					elseif($Vic >= 800 or $Vic_atk >= 1500 or $Recos >= 100)
					{
						$Rang_promo=3;
					}
					elseif($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
					{
						$Rang_promo=2;
					}
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >50000)
				{
					if($Vic >= 12000 or $Vic_atk >= 45000 or $Recos >= 5000)
					{
						$Rang_promo=8;
					}
					elseif($Vic >= 8000 or $Vic_atk >= 24000 or $Recos >= 2000)
					{
						$Rang_promo=7;
					}
					elseif($Vic >= 4000 or $Vic_atk >= 12000 or $Recos >= 1000)
					{
						$Rang_promo=6;
					}
					elseif($Vic >= 2000 or $Vic_atk >= 6000 or $Recos >= 500)
					{
						$Rang_promo=5;
					}
					elseif($Vic >= 1200 or $Vic_atk >= 3000 or $Recos >= 200)
					{
						$Rang_promo=4;
					}
					elseif($Vic >= 800 or $Vic_atk >= 1500 or $Recos >= 100)
					{
						$Rang_promo=3;
					}
					elseif($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
					{
						$Rang_promo=2;
					}
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >25000)
				{
					if($Vic >= 8000 or $Vic_atk >= 24000 or $Recos >= 2000)
					{
						$Rang_promo=7;
					}
					elseif($Vic >= 4000 or $Vic_atk >= 12000 or $Recos >= 1000)
					{
						$Rang_promo=6;
					}
					elseif($Vic >= 2000 or $Vic_atk >= 6000 or $Recos >= 500)
					{
						$Rang_promo=5;
					}
					elseif($Vic >= 1200 or $Vic_atk >= 3000 or $Recos >= 200)
					{
						$Rang_promo=4;
					}
					elseif($Vic >= 800 or $Vic_atk >= 1500 or $Recos >= 100)
					{
						$Rang_promo=3;
					}
					elseif($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
					{
						$Rang_promo=2;
					}
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >10000)
				{
					if($Vic >= 4000 or $Vic_atk >= 12000 or $Recos >= 1000)
					{
						$Rang_promo=6;
					}
					elseif($Vic >= 2000 or $Vic_atk >= 6000 or $Recos >= 500)
					{
						$Rang_promo=5;
					}
					elseif($Vic >= 1200 or $Vic_atk >= 3000 or $Recos >= 200)
					{
						$Rang_promo=4;
					}
					elseif($Vic >= 800 or $Vic_atk >= 1500 or $Recos >= 100)
					{
						$Rang_promo=3;
					}
					elseif($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
					{
						$Rang_promo=2;
					}
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >5000)
				{
					if($Vic >= 2000 or $Vic_atk >= 6000 or $Recos >= 500)
					{
						$Rang_promo=5;
					}
					elseif($Vic >= 1200 or $Vic_atk >= 3000 or $Recos >= 200)
					{
						$Rang_promo=4;
					}
					elseif($Vic >= 800 or $Vic_atk >= 1500 or $Recos >= 100)
					{
						$Rang_promo=3;
					}
					elseif($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
					{
						$Rang_promo=2;
					}
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >2500)
				{
					if($Vic >= 1200 or $Vic_atk >= 3000 or $Recos >= 200)
						$Rang_promo=4;
					elseif($Vic >= 800 or $Vic_atk >= 1500 or $Recos >= 100)
						$Rang_promo=3;
					elseif($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
						$Rang_promo=2;
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >1250)
				{
					if($Vic >= 800 or $Vic_atk >= 1500 or $Recos >= 100)
						$Rang_promo=3;
					elseif($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
						$Rang_promo=2;
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >500)
				{
					if($Vic >= 400 or $Vic_atk >= 750 or $Recos >= 50)
						$Rang_promo=2;
					elseif($Vic >= 100 or $Vic_atk >= 300 or $Recos >= 25)
						$Rang_promo=1;
				}
				elseif($Missions >250)
				{
					if($Vic >=100 or $Vic_atk >=300 or $Recos >=25)
						$Rang_promo=1;
				}
				if($Blesse >0)
					$Rang_promo=11;
				if($Rang_promo >0)
				{
					$MedOk=0;
					if($Table =="Pilote")
						$MedOk=GetDoubleData("Pil_medals","PlayerID",$PlayerID,"Medal",$Rang_promo,"Value");
					else
						$MedOk=GetData($Table,"ID",$PlayerID,'medal'.$Rang_promo);
					if(!$MedOk)
					{
						switch($Rang_promo)
						{
							case 1:
								$reput_up=100;
								$courage_up=10;
								$moral_up=25;
								$credit_up=2;
							break;
							case 2:
								$reput_up=500;
								$courage_up=20;
								$moral_up=50;
								$endu_up=1;
								$credit_up=4;
							break;
							case 3:
								$reput_up=1000;
								$courage_up=50;
								$moral_up=100;
								$endu_up=1;
								$credit_up=6;
							break;
							case 4:
								$reput_up=2000;
								$courage_up=100;
								$moral_up=125;
								$endu_up=1;
								$credit_up=8;
							break;
							case 5:
								$reput_up=3000;
								$courage_up=125;
								$moral_up=150;
								$endu_up=1;
								$credit_up=10;
							break;
							case 6:
								$reput_up=5000;
								$courage_up=150;
								$moral_up=175;
								$endu_up=2;
								$credit_up=12;
							break;
							case 7:
								$reput_up=10000;
								$courage_up=200;
								$moral_up=200;
								$endu_up=2;
								$credit_up=14;
							break;
							case 8:
								$reput_up=20000;
								$courage_up=250;
								$moral_up=250;
								$endu_up=2;
								$credit_up=16;
							break;
							case 9:
								$reput_up=25000;
								$courage_up=255;
								$moral_up=255;
								$endu_up=5;
								$credit_up=18;
							break;
							case 10:
								$reput_up=50000;
								$courage_up=255;
								$moral_up=255;
								$endu_up=5;
								$credit_up=20;
							break;
						}
						UpdateCarac($PlayerID,"Reputation",$reput_up,$Table);
						UpdateCarac($PlayerID,"Courage",$courage_up,$Table);
						UpdateCarac($PlayerID,"Moral",$moral_up,$Table);
						UpdateCarac($PlayerID,"Endurance",$endu_up,$Table);
						if($Table =="Pilote")
							$msgcr=MoveCredits($PlayerID,4,$credit_up);
						else
							UpdateCarac($PlayerID,'medal'.$Rang_promo,1,$Table);
					}
				}
				return $Rang_promo;
			}			
			if($Equipage)
			{
				$Vic_Eq=GetData("Equipage","ID",$Equipage,"Victoires");
				$Missions_Eq=GetData("Equipage","ID",$Equipage,"Missions");
				$Rang_promo_Eq=GetRangPromo("Equipage",$Equipage,$Missions_Eq,$Vic_Eq,0,$Blesse);
			}			
			$Rang_promo=GetRangPromo("Pilote",$PlayerID,$Missions,$Vic,$Vic_atk,$Blesse);						
			if($Rang_promo)
			{
				$MedOk=0;
				$MedOk=GetDoubleData("Pil_medals","PlayerID",$PlayerID,"Medal",$Rang_promo,"Value");
				//$MedOk=GetData("Pilote","ID",$PlayerID,'medal'.$Rang_promo);
				if(!$MedOk)
				{
					$con=dbconnecti();
					$insert=mysqli_query($con,"INSERT INTO Pil_medals (PlayerID,Medal,Value) VALUES ('$PlayerID','$Rang_promo',1)");
					mysqli_close($con);
					UpdateCarac($PlayerID,"Skill_Pts",$Rang_promo);
					$Nom_med=GetMedal_Name($Pays,$Rang_promo);
					AddEvent($Avion_db,30,1,$PlayerID,$Unite,$Cible,$Rang_promo);
					$titre="Promotion";
					$mes.="<div class='alert alert-warning'>A votre arrivée, vous êtes reçu par votre commandant qui vous annonce fièrement que vos exploits vous valent une nouvelle décoration,<br> la <b>".$Nom_med."</b>!</div>";
					$img.='<p><img src=\'images/medal'.$Pays.$Rang_promo.'.gif\'></p>';
				}
				else
				{
					if($Vic >0)
					{
						$con=dbconnecti();
						$Vic=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Joueur_win='$PlayerID' AND PVP<>1"),0);
						mysqli_close($con);
						$mes.="<div class='alert alert-warning'>Après cette mission bien remplie, vous êtes invité au mess par vos camarades pour fêter votre <b>".$Vic."e</b> victoire!</div>";
					}
					elseif($Vic_atk >0 or $Recos >0)
						$mes.="<div class='alert alert-warning'>Après cette mission bien remplie, vous êtes invité au mess par vos camarades pour fêter vos récents succès!</div>";
					else
						$mes.="<div class='alert alert-warning'>Après cette mission bien remplie, vous êtes invité au mess par vos camarades!</div>";
					$titre="Retour au mess";
					$img='<p><img src=\'images/mess'.$Pays.'.jpg\'></p>';
				}
			}
			else
			{
				if($Vic >0)
				{
					$con=dbconnecti();
					$Vic=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Joueur_win='$PlayerID' AND PVP<>1"),0);
					mysqli_close($con);
					$mes.="<div class='alert alert-warning'>Après cette mission bien remplie, vous êtes invité au mess par vos camarades pour fêter votre <b>".$Vic."e</b> victoire!</div>";
				}
				elseif($Vic_atk >0 or $Recos >0)
					$mes.="<div class='alert alert-warning'>Après cette mission bien remplie, vous êtes invité au mess par vos camarades pour fêter vos récents succès!</div>";
				else
				{
					$con=dbconnecti();
					$Vic=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Bombardement WHERE Pilote='$PlayerID'"),0);
					mysqli_close($con);
					if($Vic >0)
						$mes.="<div class='alert alert-warning'>Après cette mission bien remplie, vous êtes invité au mess par vos camarades pour fêter votre <b>".$Vic."e</b> bombardement!</div>";
					else
						$mes.="<div class='alert alert-warning'>Après cette mission bien remplie, vous êtes invité au mess par vos camarades!</div>";
				}
				$titre="Retour au mess";
				$img='<p><img src=\'images/mess'.$Pays.'.jpg\'></p>';
			}
		}
	}
	else
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Reputation,Pilotage FROM Pilote WHERE ID='$PlayerID'");
		$Brevet_Pilote=mysqli_result(mysqli_query($con,"SELECT COUNT(ID) FROM Skills_Pil WHERE PlayerID='$PlayerID' AND Skill=120"),0);
		mysqli_close($con);
		//$Brevet_Pilote=GetDoubleData("Pil_medals","PlayerID",$PlayerID,"Medal",0,"Value");
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Reput=$data['Reputation'];
				$Pilotage=$data['Pilotage'];
			}
			mysqli_free_result($result);
			unset($data);
		}			
		if(!$Brevet_Pilote and $Mission >97)
		{
			if($Reput >499 or $Avancement >499)
			{
				$con=dbconnecti();
				$update=mysqli_query($con,"UPDATE Pilote SET Pilotage=50,Acrobatie=50,Bombardement=50,Navigation=50,Tactique=50,Tir=50,Vue=50,Reputation=500,Avancement=500,S_Avancement_mission=500,Courage=Courage+5,Moral=Moral+10,Skill_Pts=Skill_Pts+1 WHERE ID='$PlayerID'");
				$update2=mysqli_query($con,"INSERT INTO Skills_Pil (PlayerID, Skill) VALUES ('$PlayerID',120)");
				mysqli_close($con);
				$Nom_med=GetMedal_Name($Pays,0);
				$Avancement_brevet=GetAvancement(500,$Pays);
				$titre="Brevet";
				$mes.="<div class='alert alert-warning'>A votre arrivée, vous êtes reçu par votre commandant vous annonçant fièrement que votre entrainement vous donne le droit de porter <br> le <b>Brevet de Pilote</b> et vos nouveaux galons!</div>";
				$img='<p><img src=\'images/medal'.$Pays.'0.gif\'></p>';
				$menu.='<p><img src=\'images/grades/grades'.$Pays.'4.png\'></p>';
				$menu.='<p>Vous êtes nommé au grade de <b>'.$Avancement_brevet[0].'</b>!</p>';
				$skills=MoveCredits($PlayerID,4,2);
				AddEvent($Avion_db,30,1,$PlayerID,$Unite,$Cible,0);				
				if($Equipage)UpdateCarac($Equipage,"medal0",1,"Equipage");
			}
			else
				header("Location: ./index.php?view=user");
		}
		else
			header("Location: ./index.php?view=user");
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}
//echo memory_get_usage();
usleep(10);
include_once('./index.php');
?>