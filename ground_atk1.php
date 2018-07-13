<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
//$OfficierID=$_SESSION['Officier'];
$OfficierEMID=$_SESSION['Officier_em'];
$Action=Insec($_POST['Action']);
if($Action and ($OfficierID >0 or $OfficierEMID))
{
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_combat.inc.php');
	include_once('./jfv_ground.inc.php');
	if($Action !=999)
	{
		$Cible_Atk=Insec($_POST['Cible_Atk']);
		$Reg=Insec($_POST['Reg']);
		$Vehicule=Insec($_POST['Veh']);
		$Cible=Insec($_POST['Cible']);
		$DBA=Insec($_POST['DBA']);
		$Action_110=Insec($_POST['A110']);
		$country=$_SESSION['country'];
		$attaque=false;
		$dca=false;
		$dca_unit=false;
		$gare=false;
		$usine=false;
		$avion_parque=false;
		$hangar=false;
		$caserne=false;
		$citerne=false;
		$camion=false;
		$tour=false;
		$depot=false;
		$dca_unit_skill=10;
		if($DBA)
		{
			$DB='Regiment_IA';
			$Vue=50;
			$Officier=0;
			$queryup_add=",Move=1";
		}
		else
		{
			$DB='Regiment';
			$Vue=floor((GetData("Regiment","ID",$Reg,"Experience")/10)+10);
			$Officier=1;
		}
		$con=dbconnecti();
		$resultup=mysqli_query($con,"UPDATE $DB SET Visible=1".$queryup_add." WHERE ID='$Reg'");
		$result=mysqli_query($con,"SELECT Nom,Latitude,Longitude,Zone,ValeurStrat,Camouflage,Meteo,Recce,Fortification,Garnison,Flag FROM Lieu WHERE ID='$Cible'");
		$result2=mysqli_query($con,"SELECT * FROM Cible WHERE ID='$Vehicule'");
        $resultr=mysqli_query($con,"SELECT Vehicule_Nbr,Muns,Skill FROM $DB WHERE ID='$Reg'");
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Lieu_Nom=$data['Nom'];
				$Lat_base=$data['Latitude'];
				$Long_base=$data['Longitude'];
				$Zone=$data['Zone'];
				$ValStrat=$data['ValeurStrat'];
				$meteo=$data['Meteo'];
				$Camouflage_lieu=$data['Camouflage'];
				$Recce_Lieu=$data['Recce'];
				$Garnison=$data['Garnison'];
				$Fortification=$data['Fortification'];
				$Flag=$data['Flag'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($result2)
		{
			while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$HP=$data['HP'];
				$HP_ori=$HP;
				$Arme_Inf=$data['Arme_Inf'];
				$Arme_AT=$data['Arme_AT'];
				$Arme_Art=$data['Arme_Art'];
				$Blindage=$data['Blindage_f'];
				$Vitesse=$data['Vitesse'];
				$Taille=$data['Taille'];
				$Detection=$data['Detection'];
				$mobile=$data['mobile'];
				$Reput=$data['Reput'];
				$Categorie=$data['Categorie'];
				$Type=$data['Type'];				
				$Vitesse=Get_LandSpeed($Vitesse,$mobile,$Zone,0,$Type);		
				if(!$Blindage)$Blindage=Get_Blindage($Zone,$Taille,0,2);
			}
			mysqli_free_result($result2);
			unset($data);
		}
        if($resultr)
        {
            while($datar=mysqli_fetch_array($resultr,MYSQLI_ASSOC))
            {
                $Vehicule_Nbr=$datar['Vehicule_Nbr'];
                $Avion_Mun=$datar['Muns'];
                $Skill=$datar['Skill'];
            }
            mysqli_free_result($resultr);
            unset($datar);
        }
        if($mobile ==5)
            $Arme=$Arme_Art;
        elseif($def_c >0 and $Arme_AT >0)
            $Arme=$Arme_AT;
        elseif($Arme_Art >0)
            $Arme=$Arme_Art;
        else
            $Arme=$Arme_Inf;
        $resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats FROM Armes WHERE ID='$Arme'");
        mysqli_close($con);
        if($resulta)
        {
            while($dataa=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
            {
                $Arme_Nom=$dataa['Nom'];
                $Arme_Cal=$dataa['Calibre'];
                $ArmeAvion_Multi=$dataa['Multi'];
                $ArmeAvion_Dg=$dataa['Degats'];
            }
            mysqli_free_result($resulta);
            unset($dataa);
        }
		if(strpos($Action,"é"))
		{
			//$menu="Attaque troupes de défense";
			if(strpos($Action,"é") !==false)
			{
				$Regi=0;
				$Esc_eni=strstr($Action,'é',true);
				$tank=48;
				$Pays_eni=$Flag;
				$RR='Garnison_Airfield';
				$attaque=true;
			}
			if($tank)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT Nom,Type,Defense,Arme,HP,Reput,Camouflage,mobile FROM Cible WHERE ID='$tank'");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$nom_c='un '.$data['Nom'];
						$type_c=$data['Type'];
						$def_c=$data['Defense'];
						$arme_c=$data['Arme'];
						$hp_c=$data['HP'];
						$rep_c=$data['Reput'];
						$cam_c=$data['Camouflage'];
						$mobile_eni=$data['mobile'];
						$Taille=50-$cam_c;
					}
					mysqli_free_result($result);
					unset($data);
				}
			}
			else
				mail('binote@hotmail.com','Aube des Aigles: Attaque terrestre error',"Cible : ".$tank." / Lieu : ".$Cible);
		}
		elseif(strpos($Action,"_") ==strlen($Action))
		{
			if(strpos($Action,"_") !==false)
			{
				$Regi=strstr($Action,'_',true);
				$tank=GetData($DB,"ID",$Regi,"Vehicule_ID");
				$Pays_eni=GetData($DB,"ID",$Regi,"Pays");
				$RR='Vehicule_Nbr';
			}
			if($tank)
			{
				//GetData Cible
				if($RR =='Vehicule_Nbr')
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Type,Blindage_f,Arme_Art,Arme_Inf,HP,Reput,Camouflage,Taille,mobile FROM Cible WHERE ID='$tank'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$nom_c='un '.$data['Nom'];
							$type_c=$data['Type'];
							$def_c=$data['Blindage_f'];
							$arme_c=$data['Arme_Art'];
							if(!$arme_c)$arme_c=$data['Arme_Inf'];
							$hp_c=$data['HP'];
							$rep_c=$data['Reput'];
							$cam_c=$data['Camouflage'];
							$mobile_eni=$data['mobile'];
							$Taille=$data['Taille'];
						}
						mysqli_free_result($result);
						unset($data);
					}
				}
				else
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Nom,Type,Defense,Arme,HP,Reput,Camouflage,mobile FROM Cible WHERE ID='$tank'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$nom_c='un '.$data['Nom'];
							$type_c=$data['Type'];
							$def_c=$data['Defense'];
							$arme_c=$data['Arme'];
							$hp_c=$data['HP'];
							$rep_c=$data['Reput'];
							$cam_c=$data['Camouflage'];
							$mobile_eni=$data['mobile'];
							$Taille=50-$cam_c;
						}
						mysqli_free_result($result);
						unset($data);
					}
				}
			}
			else
				mail('binote@hotmail.com','Aube des Aigles: Attaque terrestre error',"Cible : ".$tank." / Lieu : ".$Cible);
		}
		else
		{
			if($Cible_Atk ==1)
			{
				$dca_unit_skill=0;
				if(strpos($Action,'2_') !==false) //Vérifie si la cible est une DCA, extrait l'ID de la pièce
				{
					$arme_c=substr($Action,2);
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT ID,DCA_Exp,Unit FROM Flak WHERE Lieu='$Cible' AND DCA_ID='$arme_c' ORDER BY RAND() LIMIT 1");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result))
						{
							$dca_unit_skill=$data['DCA_Exp'];
							$DCA_Unit_ID=$data['Unit'];
							$DCA_ID=$data['ID'];
						}
						mysqli_free_result($result);
					}
					$Action=2;
				}
				if(!$dca_unit_skill)$arme_c=0;
				$cam_c=$Camouflage_lieu;				
				switch($Action)
				{
					case 1:
						$nom_c='un avion parqué le long de la piste';
						$def_c=0;
						$hp_c=500;
						$rep_c=3;
						$type_c=22;
						$avion_parque=true;
						$tank=0;
					break;
					case 2:
						$nom_c='un emplacement de D.C.A';
						$def_c=10;					
						$hp_c=500;
						$rep_c=7;
						$cam_c += 10;
						$type_c=12;
						$dca_unit=true;
						$tank=16;
					break;
					case 3:
						$nom_c='un hangar';
						$def_c=0;
						$hp_c=1000;
						$rep_c=3;
						$type_c=25;
						$hangar=true;
						$tank=1;
					break;
					case 4:
						$nom_c='la tour de contrôle';
						$def_c=10;
						$hp_c=3000;
						$rep_c=10;
						$type_c=26;
						$tank=2;
						$tour=true;
					break;
				}
			}
			elseif($Cible_Atk ==2)
			{
				switch($Action)
				{
					case 1:
						$nom_c='un entrepôt';
						$def_c=0;
						$arme_c=17;
						$hp_c=1000;
						$rep_c=3;
						$cam_c=0;
						$type_c=25;
						$usine=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=3;
					break;
					case 2:
						$nom_c='un emplacement de D.C.A';
						$def_c=10;
						$arme_c=15;
						$hp_c=500;
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c='un bâtiment secondaire';
						$def_c=10;
						$arme_c=17;
						$hp_c=5000;
						$rep_c=15;
						$cam_c=0;
						$type_c=25;
						$usine=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=4;
					break;
					case 4:
						$nom_c='le bâtiment principal';
						$def_c=20;
						$arme_c=15;
						$hp_c=10000;
						$rep_c=30;
						$cam_c=0;
						$type_c=27;
						$usine=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=5;
					break;
				}
			}
			elseif($Cible_Atk ==3)
			{
				switch($Action)
				{
					case 1:
						$nom_c='un groupe de soldats de la garnison';
						if($Fortification >0)
							$def_c=$Fortification;
						else
							$def_c=0;
						$arme_c=17;
						$hp_c=100;
						$rep_c=1;
						$cam_c=20;
						$tank=48;
					break;
					case 2:
						$nom_c='un canon';
						if($Fortification >0)
							$def_c=$Fortification;
						else
							$def_c=10;
						$arme_c=14;
						$hp_c=500;
						$rep_c=3;
						$cam_c=20;
						$type_c=6;
						$tank=17;
					break;
					case 3:
						$nom_c='un bâtiment secondaire';
						if($Fortification >0)
							$def_c=$Fortification;
						else
							$def_c=5;
						$arme_c=17;
						$hp_c=2000;
						$rep_c=2;
						$cam_c=0;
						$tank=6;
					break;
					case 4:
						$nom_c='le bâtiment principal';
						if($Fortification >0)
							$def_c=$Fortification;
						else
							$def_c=10;
						$arme_c=14;
						$hp_c=5000;
						$rep_c=5;
						$cam_c=0;
						$type_c=34;
						$caserne=true;
						$tank=7;
					break;
				}
			}
			elseif($Cible_Atk ==4)
			{
				switch($Action)
				{
					case 1:
						$nom_c='les voies';
						$def_c=0;
						$arme_c=4;
						$hp_c=500;
						$rep_c=1;
						$cam_c=0;
						$gare=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=8;
					break;
					case 2:
						$nom_c='Emplacement de D.C.A';
						$def_c=10;
						$arme_c=15;
						$hp_c=500;
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c='un entrepôt';
						$def_c=0;
						$arme_c=17;
						$hp_c=1000;
						$rep_c=3;
						$cam_c=0;
						$type_c=25;
						$depot=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=3;
					break;
					case 4:
						$nom_c='le bâtiment principal';
						$def_c=10;
						$arme_c=17;
						$hp_c=3000;
						$rep_c=15;
						$cam_c=0;
						$type_c=28;
						$gare=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=9;
					break;
				}
			}
			elseif($Cible_Atk ==5)
			{
				switch($Action)
				{
					case 1:
						$nom_c='un véhicule';
						$def_c=0;
						$arme_c=4;
						$hp_c=200;
						$rep_c=1;
						$cam_c=5;
						$type_c=1;
						$tank=18;
					break;
					case 2:
						$nom_c='un emplacement de D.C.A';
						$def_c=10;
						$arme_c=15;
						$hp_c=500;
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c='le pont, en enfilade';
						$def_c=20;
						$arme_c=14;
						$hp_c=8000;
						$rep_c=25;
						$cam_c=0;
						$type_c=29;
						$pont=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=10;
					break;
					case 4:
						$nom_c='le pont, perpendiculairement';
						$def_c=20;
						$arme_c=14;
						$hp_c=8000;
						$rep_c=25;
						$cam_c=0;
						$type_c=29;
						$pont=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=10;
					break;
				}
			}	
			elseif($Cible_Atk ==6)
			{
				switch($Action)
				{
					case 1:
						$nom_c='un entrepôt';
						$def_c=0;
						$arme_c=17;
						$hp_c=1000;
						$rep_c=3;
						$cam_c=0;
						$type_c=25;
						$camion=10;
						$depot=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=3;
					break;
					case 2:
						$nom_c='un emplacement de D.C.A';
						$def_c=10;
						$arme_c=15;
						$hp_c=500;
						$rep_c=7;
						$cam_c=10;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c='les réserves de carburant';
						$def_c=0;
						$arme_c=17;
						$hp_c=1500;
						$rep_c=5;
						$cam_c=0;
						$type_c=31;
						$port=true;
						$citerne=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=11;
					break;
					case 4:
						$nom_c='les quais';
						$def_c=20;
						$arme_c=15;
						$hp_c=10000;
						$rep_c=30;
						$cam_c=0;
						$port=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=12;
					break;
				}
			}
			elseif($Cible_Atk ==7)
			{
				switch($Action)
				{			
					case 1:
						$nom_c='un bâtiment secondaire';
						$def_c=5;
						$arme_c=4;
						$hp_c=2000;
						$rep_c=5;
						$cam_c=20;
						$tank=13;
					break;
					case 2:
						$nom_c='un emplacement de D.C.A';
						$def_c=10;
						$arme_c=15;
						$hp_c=500;
						$rep_c=7;
						$cam_c=20;
						$type_c=12;
						$dca=true;
						$tank=16;
					break;
					case 3:
						$nom_c='le bâtiment principal';
						$def_c=20;
						$arme_c=15;
						$hp_c=10000;
						$rep_c=30;
						$cam_c=20;
						$radar=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=14;
					break;
					case 4:
						$nom_c='une antenne';
						$def_c=5;
						$arme_c=15;
						$hp_c=4000;
						$rep_c=20;
						$cam_c=20;
						$radar=true;
						$hp_c+=($hp_c*($ValStrat/2));
						$tank=15;
					break;
				}
			}
		}			
		if($mobile ==5 and $ValStrat >0)	//bombardement côtier
		{
			$Def_cote=$ValStrat+($Fortification/10);
			if($Def_cote >=10)
			{
				if($Pays_cible ==1)
					$arme_c=252;
				elseif($Pays_cible ==2)
					$arme_c=243;
				elseif($Pays_cible ==4)
					$arme_c=249;
				elseif($Pays_cible ==6)
					$arme_c=247;
				elseif($Pays_cible ==7)
					$arme_c=253;
				elseif($Pays_cible ==8)
					$arme_c=165; //To-Do Edit
				elseif($Pays_cible ==9)
					$arme_c=257;
				else
					$arme_c=255;					
			}
			elseif($Def_cote ==9)
			{
				if($Pays_cible ==1)
					$arme_c=252;
				elseif($Pays_cible ==2)
					$arme_c=203;
				elseif($Pays_cible ==4)
					$arme_c=249;
				elseif($Pays_cible ==6)
					$arme_c=247;
				elseif($Pays_cible ==7)
					$arme_c=253;
				elseif($Pays_cible ==8)
					$arme_c=165; //To-Do Edit
				elseif($Pays_cible ==9)
					$arme_c=257;
				else
					$arme_c=255;					
			}
			elseif($Def_cote ==8)
			{
				if($Pays_cible ==1)
					$arme_c=252;
				elseif($Pays_cible ==2)
					$arme_c=242;
				elseif($Pays_cible ==4)
					$arme_c=249;
				elseif($Pays_cible ==6)
					$arme_c=247;
				elseif($Pays_cible ==7)
					$arme_c=253;
				elseif($Pays_cible ==8)
					$arme_c=165; //To-Do Edit
				elseif($Pays_cible ==9)
					$arme_c=257;
				else
					$arme_c=255;					
			}
			elseif($Def_cote ==7)
			{
				if($Pays_cible ==1)
					$arme_c=249;
				elseif($Pays_cible ==2)
					$arme_c=241;
				elseif($Pays_cible ==4)
					$arme_c=249;
				elseif($Pays_cible ==6)
					$arme_c=246;
				elseif($Pays_cible ==7)
					$arme_c=1;
				elseif($Pays_cible ==8)
					$arme_c=165; //To-Do Edit
				elseif($Pays_cible ==9)
					$arme_c=257;
				else
					$arme_c=255;					
			}
			elseif($Def_cote ==6)
			{
				if($Pays_cible ==1)
					$arme_c=251;
				elseif($Pays_cible ==2)
					$arme_c=202;
				elseif($Pays_cible ==4)
					$arme_c=248;
				elseif($Pays_cible ==6)
					$arme_c=246;
				elseif($Pays_cible ==7)
					$arme_c=255;
				elseif($Pays_cible ==8)
					$arme_c=165; //To-Do Edit
				elseif($Pays_cible ==9)
					$arme_c=257;
				else
					$arme_c=255;					
			}
			elseif($Def_cote ==5)
			{
				if($Pays_cible ==1)
					$arme_c=251;
				elseif($Pays_cible ==2)
					$arme_c=200;
				elseif($Pays_cible ==4)
					$arme_c=248;
				elseif($Pays_cible ==6)
					$arme_c=245;
				elseif($Pays_cible ==7)
					$arme_c=255;
				elseif($Pays_cible ==8)
					$arme_c=165; //To-Do Edit
				elseif($Pays_cible ==9)
					$arme_c=256;
				else
					$arme_c=255;					
			}
			elseif($Def_cote ==4)
			{
				if($Pays_cible ==1)
					$arme_c=250;
				elseif($Pays_cible ==2)
					$arme_c=240;
				elseif($Pays_cible ==4)
					$arme_c=126;
				elseif($Pays_cible ==6)
					$arme_c=244;
				elseif($Pays_cible ==7)
					$arme_c=240;
				elseif($Pays_cible ==8)
					$arme_c=157;
				elseif($Pays_cible ==9)
					$arme_c=231;
				else
					$arme_c=96;					
			}
			elseif($Def_cote <=3)
			{
				if($Pays_cible ==1)
					$arme_c=126;
				elseif($Pays_cible ==2)
					$arme_c=121;
				elseif($Pays_cible ==4)
					$arme_c=96;
				elseif($Pays_cible ==6)
					$arme_c=124;
				elseif($Pays_cible ==7)
					$arme_c=221;
				elseif($Pays_cible ==8)
					$arme_c=176;
				elseif($Pays_cible ==9)
					$arme_c=231;
				else
					$arme_c=96;
			}
		}			
		if($HP_eni <1)$HP_eni=$hp_c;
		//DCA
		if($arme_c >0)
		{
			$intro.='<br><b>La défense rapprochée ouvre le feu sur vous!</b>';
			if($Target_id)
				$img=Afficher_Image('images/'.$Target_id.'.jpg',"images/image.png",$nom_c,50);
			else
				$img=Afficher_Image('images/cibles/cibles'.$tank.'_'.$Pays_cible.'.jpg','images/cibles/cibles'.$tank.'.jpg',$nom_c);
			$dca_max=$rep_c*10;
			if($dca_max >250)$dca_max=250;			
			$Shoot_rand=mt_rand(10,50)+mt_rand($DCA,$dca_unit_skill)+mt_rand($rep_c,$dca_max);
			$Shoot=$Shoot_rand+($meteo/10)+$Taille-$Vitesse;
			//JF
			if($OfficierID ==1)
			{
				$skills.="<br>[Score de Tir : ".$Shoot."]
							<br>+Taille ".$Taille."
							<br>-Vitesse ".$Vitesse."
							<br>Tir_eni :".$Shoot_rand;
			}
			//End JF
			if($Shoot >10 or $Shoot_rand >250)
			{
				$con=dbconnecti();		
				$result=mysqli_query($con,"SELECT Degats,Multi FROM Armes WHERE ID='$arme_c'");
				mysqli_close($con);		
				if($result)		
				{		
					while($datab=mysqli_fetch_array($result,MYSQLI_ASSOC))	
					{
						$Armeni_Degats=$datab['Degats'];
						$Armeni_Multi=$datab['Multi'];
					}
					mysqli_free_result($result);
					unset($datab);
				}
				$Degats=(mt_rand(0,$Armeni_Degats)-$Blindage)*GetShoot($Shoot,$Armeni_Multi);
				if($Degats <1)$Degats=mt_rand(1,10);
				$HP-=$Degats;
				if($HP <1)
				{
					$intro.='<br>Le tir ennemi détruit une de vos unités. ('.$Degats.' points de dégats!)';
					UpdateData($DB,"Vehicule_Nbr",-1,"ID",$Reg);
					AddEventGround(400,$Vehicule,$OfficierID,$Reg,$Cible,1,$Regi);
					$Update_XP_eni+=$Reput;
					$HP=$HP_ori;
				}
				else
				{
					if($mobile ==5)
						UpdateData($DB,"HP",-$Degats,"ID",$Reg);
					$intro.='<br>Le tir ennemi endommage une de vos unités, lui occasionnant <b>'.$Degats.'</b> points de dégats!';
					$Update_XP_eni+=1;
				}
				if($Update_xp_eni and $Pays_eni !=$country and $Regi >0)
				{
					UpdateData("Regiment","Experience",$Update_xp_eni,"ID",$Regi);
					UpdateData("Regiment","Moral",$Update_xp_eni,"ID",$Regi);
				}
			}
			else
				$intro.="<br>Les explosions d'obus encadrent votre unité!";
		}			
		if(!$Vehicule_Nbr)
		{
			$intro.="<br>Votre unité a été décimée, l'attaque est stoppée!";
			$attaque=false;
		}
		elseif(!$attaque)
		{
			//Repérage
			if($tank or ($Cible_Atk ==1 and $Action ==1))
			{
				$reperer=$Vue+$Detection-($cam_c*5)-GetMalusReperer($Zone);
				if($reperer)
					$attaque=true;
				else
				{
					$intro.='<br>Vous ne parvenez pas à repérer votre cible!';
					$attaque=false;
					if($OfficierID ==1)$intro.='<br>'.$reperer;
				}
			}
			else
			{
				$intro.='<br>Vous ne parvenez pas à repérer votre cible!';
				$attaque=false;
			}
		}				
		if($attaque)
		{
			if($Officier)
			{
				$Conso_muns=$Vehicule_Nbr*$ArmeAvion_Multi;
				if($Arme ==136)
					$Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Essence_87");
				else
					$Muns_Stock=GetData("Regiment","ID",$Reg,"Stock_Munitions_".$Arme_Cal);
			}
			else
				$Muns_Stock=9999;
			if($Muns_Stock >=$Conso_muns)
			{
				if($Zone ==2 or $Zone ==3 or $Zone ==4 or $Zone ==5 or $Zone ==9 or $Zone ==10)
				{
					if(!$Blindage or !$def_c)
					{
						if($Taille <3)
							$def_c=4;
						elseif($Taille <2)
							$def_c=8;
					}
				}
				elseif($Zone ==7)
				{
					if(!$Blindage or !$def_c)
					{
						if($Taille <5)
							$def_c=4;
						elseif($Taille <3)
							$def_c=8;
						elseif($Taille <2)
							$def_c=13;
					}
				}
				if($Arme ==82 or $Arme ==136){
                    $Avion_Mun=2;
                    $def_c=0;
                }
				elseif($Officier)
                    UpdateData("Regiment","Stock_Munitions_".$Arme_Cal,-$Conso_muns,"ID",$Reg);
				if($ArmeAvion_Multi <1)$ArmeAvion_Multi=1;
				if($OfficierID >0 and IsSkill(37,$OfficierID))
				{
					$Vue+=25;
					$ArmeAvion_Multi+=1;
				}
				$Shoot=mt_rand(0,$Vue)+($meteo/10)-($def_c/10);			
				if(date("H") <7) //pas d'attaque canadienne
					if(mt_rand(0,100) >10)$Shoot=0;
				if($Shoot >0)
				{
					$Degats=0;
					if($Vehicule_Nbr >25)
						$Vehicule_Nbr_shoot=floor($Vehicule_Nbr/10);
					else
						$Vehicule_Nbr_shoot=$Vehicule_Nbr;
					if($Arme_Cal >$def_c or $Arme_Cal >70)
					{
						$Bonus_Dg=Damage_Bonus("Regiment",1,0,$Arme,$def_c,$Avion_Mun);
						if($Categorie ==5 and ($port or $usine or $pont or $gare or $caserne or $hangar or $depot))
						{
							$Bonus_Dg*=2;
                            if($Skill ==17 or $Skill ==117 or $Skill ==118 or $Skill ==119)
                            {
                                $Bonus_Dg*=4;
                                $intro.='<p>Vos troupes bénéficient de votre compétence <b>Démolition</b> !</p>';
                            }
							elseif($OfficierID >0 and IsSkill(17,$OfficierID))
							{
								if(GetData("Officier","ID",$OfficierID,"Trait") ==2)
									$Bonus_Dg*=4;
								else
									$Bonus_Dg*=2;
								$intro.='<p>Vos troupes bénéficient de votre compétence <b>Démolition</b> !</p>';
							}
						}
						for($i=1;$i<=$Vehicule_Nbr_shoot;$i++)
						{
							$Degats+=round((mt_rand(1,$ArmeAvion_Dg)+$Bonus_Dg-$def_c)*mt_rand(1,$ArmeAvion_Multi));
						}
						if($Degats <1)$Degats=mt_rand(1,10);
						$intro.='<p>Le tir de votre unité fait mouche! (<b>'.$Degats.'</b> dégâts)</p>';
					}
					else
					{
						$Degats=mt_rand(1,$Vehicule_Nbr_shoot);
						$intro.='<p>Le tir de votre unité fait mouche, mais les projectiles ricochent sur le blindage! (<b>'.$Degats.'</b> dégâts)</p>';
					}
					$HP_eni-=$Degats;						
					if($OfficierID ==1)$intro.='<br> HP eni='.$HP_eni;						
					if($HP_eni <0)
					{
						$date=GetData("Conf_Update","ID",2,"Date");
						if($dca)
						{
							//AddEvent($Avion_db,13,$avion,$OfficierID,$Unite,$Cible);
							UpdateData("Lieu","DefenseAA_temp",-1,"ID",$Cible);
							$introhit='<br>Votre rafale détruit le canon anti-aérien!';
							AddEvent("Cible",230,$Vehicule,$OfficierID,0,$Cible,16,$Reg);
						}
						elseif($dca_unit)
						{
							if($DCA_Unit_ID and $DCA_ID)
							{
								$DCA_Nbr=GetData("Flak","ID",$DCA_ID,"DCA_Nbr");
								if($DCA_Nbr >1)
									UpdateData("Flak","DCA_Nbr",-1,"ID",$DCA_ID);
								else
									DeleteData("Flak","ID",$DCA_ID);
								AddEvent("Cible",223,$Vehicule,$OfficierID,$DCA_Unit_ID,$Cible,$arme_c,$Reg);
							}
							//AddEvent($Avion_db,13,$avion,$OfficierID,$Unite,$Cible);
							$introhit="<br>Votre rafale détruit un canon anti-aérien de l'aérodrome!";
							UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
						}
						elseif($tour)
						{
							AddEvent("Cible",212,$Vehicule,$OfficierID,$Reg,$Cible,$Damage);
							$Damage=floor(0-($rep_c/10));
							UpdateData("Lieu","Tour",$Damage,"ID",$Cible);
							$introhit="<br>Votre attaque diminue la capacité d'organisation de l'aérodrome ennemi!";
							unset($Damage);
						}
						elseif($gare)
						{
							AddEvent("Cible",215,$Vehicule,$OfficierID,$Reg,$Cible,$Damage);
							$Damage=floor(0-($rep_c/10));
							UpdateData("Lieu","NoeudF",$Damage,"ID",$Cible);
							$introhit="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
						}
						elseif($usine)
						{
							AddEvent("Cible",216,$Vehicule,$OfficierID,$Reg,$Cible,$Damage);
							$Damage=floor(0-($rep_c/10));
							UpdateData("Lieu","Industrie",$Damage,"ID",$Cible);
							$introhit="<br>Votre attaque diminue le potentiel de production de l'ennemi!";
						}
						elseif($caserne)
						{
							AddEvent("Cible",271,$Vehicule,$OfficierID,$Reg,$Cible,$Damage);
							/*$Damage=floor(0-($rep_c/10));*/
							//Fortification
							UpdateData("Lieu","Fortification",-10,"ID",$Cible);
							$introhit="<br>Votre attaque diminue les fortifications!";
						}
						elseif($pont)
						{
							//$Damage=floor(0-($rep_c/2));
							AddEvent("Cible",217,$Vehicule,$OfficierID,$Reg,$Cible,$Damage);
							SetData("Lieu","Pont",-10,"ID",$Cible);
							$introhit="<br>Le pont est endommagé!";
						}
						elseif($port)
						{
							AddEvent("Cible",229,$Vehicule,$OfficierID,$Reg,$Cible,$mobile,$Damage);
							$Damage=floor(0-($rep_c/10));
							UpdateData("Lieu","Port",$Damage,"ID",$Cible);
							$introhit="<br>Votre attaque diminue le potentiel de ravitaillement de l'ennemi!";
							if($citerne)
							{
								if($Cible ==343)
								{
									$Flag_343=GetData("Lieu","ID",343,"Flag");
									$query="SELECT ID FROM Lieu WHERE Flag IN(2,'$Flag_343') AND Latitude <43 AND Longitude <60 AND Zone<>6";
								}
								elseif($Cible ==344)
								{
									$Flag_344=GetData("Lieu","ID",344,"Flag");
									$query="SELECT ID FROM Lieu WHERE Flag IN(2,'$Flag_343') AND Latitude <43 AND Longitude <60 AND Zone<>6";
								}
								else
								{
									//UpdateData("Lieu","Citernes",5,"ID",$Cible);
									$Lat_base_min=$Lat_base-1.00;
									$Lat_base_max=$Lat_base+1.00;
									$Long_base_min=$Long_base-2.00;
									$Long_base_max=$Long_base+2.00;
									$query="SELECT ID FROM Lieu WHERE Flag='$Pays_cible'
									AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
									AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND Zone<>6";
								}
								$con=dbconnecti();
								$resultl=mysqli_query($con,$query);
								mysqli_close($con);
								if($resultl)
								{
									while ($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
									{
										UpdateData("Lieu","Citernes",5,"ID",$datal['ID']);
										AddEvent("Cible",272,$Vehicule,$OfficierID,$Reg,$datal['ID'],5,$Cible);
									}
									mysqli_free_result($resultl);
								}
							}
						}
						elseif($radar)
						{
							AddEvent("Cible",270,$Vehicule,$OfficierID,$Reg,$Cible,$Damage);
							$Damage=floor(0-($rep_c/10));
							UpdateData("Lieu","Radar",$Damage,"ID",$Cible);
							$introhit="<br>Votre attaque diminue le potentiel de détection de l'ennemi!";
						}
						elseif($hangar)
						{
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT DISTINCT ID FROM Unit WHERE Base='$Cible' ORDER BY RAND() LIMIT 1");
							mysqli_close($con);
							if($result)
							{
								$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
								$Unite_loss=$data['ID'];
							}
							//AddEvent($Avion_db,14,$avion,$OfficierID,$Unite,$Cible);
							//Si pas d'unité sur cette base, pas de message de perte de stock
							if($Unite_loss >0)
							{
								AddEvent("Cible",224,$Vehicule,$OfficierID,$Reg,$Cible,1,$Unite_loss);
								$stock_rand=mt_rand(1,9);
								//$stock_qty=mt_rand(-500,-100);
								$stock_qty=-$Degats;
								switch($stock_rand)
								{
									case 1:
										$stock='Stock_Essence_87';
									break;
									case 2:
										$stock='Stock_Essence_100';
									break;
									case 3:
										$stock='Stock_Munitions_8';
									break;
									case 4:
										$stock='Stock_Munitions_13';
									break;
									case 5:
										$stock='Stock_Munitions_20';
									break;
									case 6:
										$stock='Stock_Munitions_30';
									break;
									case 7:
										$stock='Stock_Essence_1';
									break;
									case 8:
										$stock='Stock_Munitions_40';
									break;
									case 9:
										$stock='Stock_Munitions_75';
									break;
								}
								UpdateData("Unit",$stock,$stock_qty,"ID",$Unite_loss);
							}
							$introhit="<br>Votre rafale détruit un hangar, réduisant les stocks de l'ennemi!";
							UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
						}
						elseif($depot)
						{
							//Si pas d'unité sur cette base, pas de message de perte de stock
							$stock_rand=mt_rand(1,12);
							$stock_qty=-$Degats;
							switch($stock_rand)
							{
								case 1:
									$stock='Stock_Munitions_8';
								break;
								case 2:
									$stock='Stock_Munitions_13';
								break;
								case 3:
									$stock='Stock_Munitions_20';
								break;
								case 4:
									$stock='Stock_Munitions_30';
								break;
								case 5:
									$stock='Stock_Munitions_40';
								break;
								case 6:
									$stock='Stock_Munitions_50';
								break;
								case 7:
									$stock='Stock_Munitions_60';
								break;
								case 8:
									$stock='Stock_Munitions_75';
								break;
								case 9:
									$stock='Stock_Munitions_90';
								break;
								case 10:
									$stock='Stock_Munitions_105';
								break;
								case 11:
									$stock='Stock_Munitions_125';
								break;
								case 12:
									$stock='Stock_Munitions_150';
								break;
							}
							AddEvent("Avion",114,$Vehicule,$OfficierID,$Reg,$Cible,3,$stock_qty);
							UpdateData("Lieu",$stock,$stock_qty,"ID",$Cible);
							$introhit="<br>Votre attaque détruit un entrepôt, réduisant les stocks de l'ennemi!";
							UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
						}
						elseif($avion_parque)
						{
							$Avion_det=44; //Ju-52 par défaut si l'aérodrome n'est pas rattaché à une unité
							//$Avion_txt="Avion non identifié";
							$con=dbconnecti();
							$result=mysqli_query($con,"SELECT DISTINCT ID,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE Base='$Cible' AND Etat=1 ORDER BY RAND() LIMIT 1");
							mysqli_close($con);
							if($result)
							{
								while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
								{
									$Unit_sol=$data['ID'];
									if($data['Avion1_Nbr'] >0)
									{
										UpdateData("Unit","Avion1_Nbr",-1,"ID",$data['ID']);
										$Avion_txt=GetData("Avion","ID",$data['Avion1'],"Nom");
										$Avion_det=$data['Avion1'];
										//mail('binote@hotmail.com','Aube des Aigles: Attack Report',"Mission Attaque sur cible ".$Cible." : avion ".$data['Avion1']." détruit");
									}
									elseif($data['Avion2_Nbr'] >0)
									{
										UpdateData("Unit","Avion2_Nbr",-1,"ID",$data['ID']);
										$Avion_txt=GetData("Avion","ID",$data['Avion2'],"Nom");
										$Avion_det=$data['Avion2'];
										//mail('binote@hotmail.com','Aube des Aigles: Attack Report',"Mission Attaque sur cible ".$Cible." : avion ".$data['Avion2']." détruit");
									}
									elseif($data['Avion3_Nbr'] >0)
									{
										UpdateData("Unit","Avion3_Nbr",-1,"ID",$data['ID']);
										$Avion_txt=GetData("Avion","ID",$data['Avion3'],"Nom");
										$Avion_det=$data['Avion3'];
										//mail('binote@hotmail.com','Aube des Aigles: Attack Report',"Mission Attaque sur cible ".$Cible." : avion ".$data['Avion3']." détruit");
									}
									else
									{
										$Avion_txt="avion";
										$rep_c=1;
										//mail('binote@hotmail.com','Aube des Aigles: Erreur Select Avions : No Planes',"Mission Attaque sur cible ".$Cible." : ".$data['Avion1']."/".$data['Avion2']."/".$data['Avion3']);
									}
								}
								mysqli_free_result($result);
								unset($result);
							}
							else
							{
								$Avion_txt="avion";
								$rep_c=1;
								//mail('binote@hotmail.com','Aube des Aigles: Erreur Select Avions : No Unit','Mission Attaque sur cible '.$Cible.'.'.mysqli_error($con));
							}
							AddEvent("Avion",222,$Avion_det,$OfficierID,$Reg,$Cible,1,$Unit_sol);
							//$Target_id=$Avion_txt." au sol";
							$introhit="<br>Votre rafale détruit un <img src='images/avions/avion".$Avion_det.".gif' title='".$Avion_txt."'> au sol!";
							UpdateData("Lieu","Camouflage",-10,"ID",$Cible);
						}
						elseif($citerne)
						{
							$introhit='<p>Votre tir fait mouche, vous détruisez '.$nom_c.'</p>';
							//UpdateData("Lieu","Citernes",1,"ID",$Cible);
							$Lat_base_min=$Lat_base-1.00;
							$Lat_base_max=$Lat_base+1.00;
							$Long_base_min=$Long_base-1.00;
							$Long_base_max=$Long_base+1.00;
							$con=dbconnecti();
							$resultl=mysqli_query($con,"SELECT ID FROM Lieu WHERE Flag='$Pays_cible'
							AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
							AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND Zone<>6");
							mysqli_close($con);
							if($resultl)
							{
								while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
								{
									UpdateData("Lieu","Citernes",1,"ID",$datal['ID']);
									AddEvent("Cible",272,$Vehicule,$OfficierID,$Reg,$datal['ID'],1,$Cible);
								}
								mysqli_free_result($resultl);
							}
							unset($resultl);
						}
						elseif($camion)
						{
							$introhit='<p>Votre tir fait mouche, vous détruisez '.$nom_c.'</p>';
							//UpdateData("Lieu","Camions",$camion,"ID",$Cible);
							$Lat_base_min=$Lat_base-1.00;
							$Lat_base_max=$Lat_base+1.00;
							$Long_base_min=$Long_base-1.00;
							$Long_base_max=$Long_base+1.00;
							$con=dbconnecti();
							$resultl=mysqli_query($con,"SELECT ID FROM Lieu WHERE Flag='$Pays_cible'
							AND (Latitude BETWEEN '$Lat_base_min' AND '$Lat_base_max') 
							AND (Longitude BETWEEN '$Long_base_min' AND '$Long_base_max') AND Zone<>6");
							mysqli_close($con);
							if($resultl)
							{
								while($datal=mysqli_fetch_array($resultl,MYSQLI_ASSOC)) 
								{
									UpdateData("Lieu","Camions",$camion,"ID",$datal['ID']);
									AddEvent("Cible",273,$Vehicule,$OfficierID,$Reg,$datal['ID'],$camion,$Cible);
								}
								mysqli_free_result($resultl);
							}
							unset($resultl);
						}
						elseif($RR =='Garnison_Airfield' and $tank ==48 and $Esc_eni)
						{
							$Garnison=GetData("Unit","ID",$Esc_eni,"Garnison");
							if(!$OfficierID or $Action_110 ==110)
								$Reg_a_ia=1;
							else
								$Reg_a_ia=0;
							$Kills=floor($Degats/100);
							if($Kills >50)$Kills=50;
							if($Kills >$Garnison)$Kills=$Garnison;
							AddGroundAtk($Reg,0,$Vehicule,$Vehicule_Nbr,48,$Garnison,4,2,$Cible,0,0,$Kills,$Reg_a_ia);
							UpdateData("Unit","Garnison",-$Kills,"ID",$Esc_eni);
							$introhit="<p>Vous éliminez une partie des troupes de défense de l'aérodrome</p>";
						}
						elseif($Cible_Atk ==3 and $tank ==48)
						{
							if($Vehicule ==451 or $Vehicule ==452 or $Vehicule ==456 or !$OfficierID or $Action_110 ==110)
								$Reg_a_ia=1;
							else
								$Reg_a_ia=0;
							$Kills=floor($Degats/100);
							if($Kills >100)$Kills=100;
							if($Kills >$Garnison)$Kills=$Garnison;
							AddGroundAtk($Reg,0,$Vehicule,$Vehicule_Nbr,48,$Garnison,4,2,$Cible,0,0,$Kills,$Reg_a_ia);
							UpdateData("Lieu","Garnison",-$Kills,"ID",$Cible);
							$introhit='<p>Vous éliminez une partie de la garnison</p>';
						}
						else
							$introhit='<p>Votre tir fait mouche, vous détruisez '.$nom_c.'</p>';
						if($mobile and $RR)
						{
							if($RR =="Vehicule_Nbr")
							{
								UpdateData("Regiment",$RR,-1,"ID",$Regi);
								AddEventGround(404,$tank,$OfficierID,$Regi,$Cible,1,$Reg);
							}
						}
						if($Pays_eni !=$country and $OfficierID >0)
						{
							if(GetData("Officier","ID",$OfficierID,"Trait") ==1)$rep_c*=2;		
							UpdateData($DB,"Experience",$rep_c,"ID",$Reg);
							UpdateData($DB,"Moral",$rep_c,"ID",$Reg);
							UpdateData("Officier","Avancement",$rep_c,"ID",$OfficierID);
							UpdateData("Officier","Reputation",$rep_c,"ID",$OfficierID);
						}
						/*Tableau de chasse
						if($Simu)
							AddVictoire_atk($Avion_db,$type_c,$tank,$avion,$OfficierID,$Unite,$Cible,$ArmeAvion,$Pays_cible,0,$alt,$Nuit);
						//Reput missions unité
						if($Simu)
						{	
							if(($Zone !=6 and IsWar($Flag, $country)) or ($Zone ==6 and IsWar($Pays_cible,$country)))
							{
								//Mission_Historique
								if($Cible ==$BH_Lieu)
								{
									if(IsAxe($country))
										$Points_cat="Points_Axe";
									else
										$Points_cat="Points_Allies";
									UpdateData("Event_Historique",$Points_cat,$rep_c,"ID",$_SESSION['BH_ID']);
									UpdateData("Unit","Reputation",$rep_c,"ID",$Unite);
								}
								if($rep_c >1)
								{
									UpdateCarac($OfficierID,"Victoires_atk",$rep_c);
									UpdateCarac($OfficierID,"Reputation",$rep_c);
									UpdateCarac($OfficierID,"Avancement",$rep_c);
									UpdateCarac($OfficierID,"Moral",10);
								}
								if($Strike ==false)
								{
									//Doubler la récompense en cas de bataille historique
									if($Cible ==$BH_Lieu)
									{
										$Pts_Bonus=2;
										UpdateCarac($OfficierID,"Batailles_Histo",1);
									}
									else
									{
										$Pts_Bonus=1;
									}
									//Seules les cibles importantes valident la mission
									if($rep_c >1)
									{
										$Front=GetData("Joueur","ID",$OfficierID,"Front");
										if($Cible == GetData("Unit","ID",$Unite,"Mission_Lieu"))
										{
											$Cdt=GetData("Unit","ID",$Unite,"Commandant");
											if($Cdt)
											{
												UpdateCarac($Cdt,"Reputation",10);
												UpdateCarac($Cdt,"Avancement",5);
												UpdateCarac($Cdt,"Commandement",5);
											}
											UpdateData("Unit","Reputation",10,"ID",$Unite);
										}
										elseif($Cible == GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unite,"Type")))
										{
											$Cdt=GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
											if($Cdt)
											{
												UpdateCarac($Cdt,"Reputation",5);
												UpdateCarac($Cdt,"Avancement",10);
											}
											UpdateData("Unit","Reputation",10,"ID",$Unite);
										}
										$introhit.="<p><b>Vous avez accompli votre mission!</b></p>";
										$rep_c+=10;
										$rep_c*=$Pts_Bonus;
										UpdateCarac($OfficierID,"Missions",$rep_c);
										UpdateCarac($OfficierID,"Reputation",$rep_c);
										UpdateCarac($OfficierID,"Avancement",$rep_c);
										if($Equipage and $Endu_Eq and $Equipage_Nbr > 1)
										{
											UpdateCarac($Equipage,"Missions",1,"Equipage");
											UpdateCarac($Equipage,"Avancement",10,"Equipage");
											UpdateCarac($Equipage,"Reputation",15,"Equipage");
										}
									}	
									else
									{
										$introhit.="<p><b>La cible que vous venez de détruire est insignifiante.</b></p>";
									}
									if(!$Nuit and $Mission_Type !=31)
									{
										//Reput Chasseurs escorte
										$con=dbconnecti();
										$result=mysqli_query($con,"SELECT ID,Unit,Pays FROM Pilote WHERE Escorte='$Cible' AND Unit<>'$Unite'");
										mysqli_close($con);
										if($result)
										{
											while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
											{
												//Reput missions unité
												if($Cible == GetData("Unit","ID",$data['Unit'],"Mission_Lieu"))
												{
													$Cdt=GetData("Unit","ID",$data['Unit'],"Commandant");
													if($Cdt)
													{
														UpdateCarac($Cdt,"Reputation",5);
														UpdateCarac($Cdt,"Avancement",2);
														UpdateCarac($Cdt,"Commandement",2);
													}
													UpdateData("Unit","Reputation",10,"ID",$data['Unit']);
												}
												elseif($Cible == GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$data['Unit'],"Type")))
												{
													$Cdt=GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Commandant");
													if($Cdt)
													{
														UpdateCarac($Cdt,"Reputation",2);
														UpdateCarac($Cdt,"Avancement",5);
													}
													UpdateData("Unit","Reputation",10,"ID",$data['Unit']);
												}
												//Doubler la récompense en cas de bataille historique
												if($Cible ==$BH_Lieu)
												{
													if(IsAxe($country))
														$Points_cat="Points_Axe";
													else
														$Points_cat="Points_Allies";
													UpdateData("Event_Historique",$Points_cat,20,"ID",$_SESSION['BH_ID']);
													UpdateCarac($data['ID'], "Batailles_Histo", 1);
													UpdateData("Unit","Reputation",20,"ID",$data['Unit']);
												}
												UpdateCarac($data['ID'],"Missions",20);
												UpdateCarac($data['ID'],"Avancement",10);
												UpdateCarac($data['ID'],"Reputation",10);
												AddEvent($Avion_db,87,$avion,$OfficierID,$data['Unit'],$Cible,0,$data['ID']);
											}
										}	
									}
									//Reput Reco
									$con=dbconnecti();
									$Recce_PID=mysqli_result(mysqli_query($con,"SELECT Recce_PlayerID FROM Lieu WHERE ID='$Cible'"),0);
									mysqli_close($con);
									if($Recce_PID)
									{
										$Unit_Recce=GetData("Pilote","ID",$Recce_PID,"Unit");
										if($Unit_Recce !=$Unite)
										{
											$Bonus_Recce_PID=10+($Valstrat*2);
											//Reput missions unité
											if($Cible == GetData("Unit","ID",$Unit_Recce,"Mission_Lieu"))
											{
												$Cdt=GetData("Unit","ID",$Unit_Recce,"Commandant");
												if($Cdt)
												{
													UpdateCarac($Cdt,"Reputation",10);
													UpdateCarac($Cdt,"Avancement",5);
													UpdateCarac($Cdt,"Commandement",5);
												}
												UpdateData("Unit","Reputation",$Bonus_Recce_PID,"ID",$Unit_Recce);
											}
											elseif($Cible == GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Lieu_Mission".GetData("Unit","ID",$Unit_Recce,"Type")))
											{
												$Cdt=GetDoubleData("Pays","Pays_ID",$data['Pays'],"Front",$Front,"Commandant");
												if($Cdt)
												{
													UpdateCarac($Cdt,"Reputation",5);
													UpdateCarac($Cdt,"Avancement",10);
												}
												UpdateData("Unit","Reputation",$Bonus_Recce_PID,"ID",$Unit_Recce);
											}
											//Doubler la récompense en cas de bataille historique
											if($Cible ==$BH_Lieu)
												UpdateData("Unit","Reputation",$Bonus_Recce_PID,"ID",$Unit_Recce);
											UpdateCarac($Recce_PID,"Missions",$Bonus_Recce_PID);
											UpdateCarac($Recce_PID,"Avancement",$Bonus_Recce_PID);
											UpdateCarac($Recce_PID,"Reputation",$Bonus_Recce_PID);
											AddEvent($Avion_db,89,$avion,$OfficierID,$Unit_Recce,$Cible,0,$Recce_PID);
										}
									}
									SetData("Pilote","S_Strike",1,"ID",$OfficierID);
									SetData("Lieu","Last_Attack",$date,"ID",$Cible);
								}
							}
							else
							{
								$introhit="<p>Vous attaquez des cibles alliées!</p>";
								if($Cible == GetData("Unit","ID",$Unite,"Mission_Lieu"))
								{
									$Cdt=GetData("Unit","ID",$Unite,"Commandant");
									if($Cdt)
									{
										UpdateCarac($Cdt,"Reputation",-20);
										UpdateCarac($Cdt,"Avancement",-10);
									}
									UpdateData("Unit","Reputation",-20,"ID",$Unite);
								}
								UpdateCarac($OfficierID,"Reputation",-20);
								UpdateCarac($OfficierID,"Avancement",-50);
							}
						}
						$seconde_passe=true;*/
					}
					//Dégats persistants grosses unités navales
					elseif($mobile_eni ==5 and $RR =="Vehicule_Nbr")
					{
						if($OfficierID >0)
							UpdateCarac($OfficierID,"Reputation",$Bombs_Hit);
						UpdateData("Regiment","HP",-$Degats,"ID",$Regi);
						SetData("Lieu","Last_Attack",$date,"ID",$Cible);
						if(!$Pays_cible)$Pays_cible=GetData("Regiment","ID",$Regi,"Pays");							
						$introhit="<p>L'explosion, occasionnant ".round($Degats)." dégâts, a endommagé la cible, mais ne l'a pas détruite!</p>";
					}
					else
						$introhit='<p>Votre attaque manque de puissance, vous ne parvenez pas à détruire votre cible!</p>';
					
				}
				else
				{
					$introhit='<p>Votre attaque est inefficace, manquant de précision!</p>';
					if($OfficierID ==1)
					{
						$skills.="<br>[Assaut : ".$Shoot."]
								<br>+Tir ".$Vue." rand
								<br>-meteo ".$meteo."
								<br>-Def ".$def_c;
					}
				}
			}
			else
				$introhit='<p>Votre attaque est inefficace, manquant de munitions!</p>';					
			$intro.=$introhit;			
		}
		//Si le site a été camouflé entre temps, réinitialiser le pilote de reco
		if(!$Recce_Lieu and !$Cible)
			SetData("Lieu","Recce_PlayerID",0,"ID",$Cible);		
		$titre='Assaut';
		$mes.="<h2>Attaque de ".$Lieu_Nom."</h2><table class='table'>
			<thead><tr><th>Vos Troupes</th><th>Armement</th><th>Expérience</th><th>Terrain</th><th>Météo</th></tr></thead>
			<tr><td><img src='images/vehicules/vehicule".$Vehicule.".gif'></td><td>".$Arme_Nom."</td><td>".$Vue."</td><td><img src='images/zone".$Zone.".jpg'></td><td><img src='images/meteo".$meteo.".jpg'></td></tr>
			</table>";
		if($OfficierEMID >0)		
			$menu="<a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour au menu</a>
			<form action='index.php?view=ground_em_ia' method='post'><input type='hidden' name='Reg' value='".$Reg."'><input type='submit' value='Retour' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
		include_once('./default.php');
	}
}
else
{
	$mes=GetMes("init_mission");
	$view='login';
	session_unset();
	session_destroy();
}