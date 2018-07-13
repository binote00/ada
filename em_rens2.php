<?/*
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$success=false;
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./jfv_nav.inc.php');
	if(($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Rens or $GHQ) and $Front !=12)
	{
		include_once('./menu_em.php');
		//include_once('./menu_staff.php');
		if($Trait ==23)$Avancement*=2;
		if($Credits >1)
		{
			$Officier=Insec($_POST['Officier']);
			$Unite=Insec($_POST['Unite']);
			$Ville=Insec($_POST['Ville']);
			$Ville_eni=Insec($_POST['Ville_eni']);
			$Officier_eni=Insec($_POST['Officier_eni']);
			$Unite_eni=Insec($_POST['Unite_eni']);
			$Usine_eni=Insec($_POST['Usine_eni']);
			$Usine=Insec($_POST['Usine']);
			$CT_2=Insec($_POST['CT2']);
			$CT_4=Insec($_POST['CT4']);
			$CT_8=Insec($_POST['CT8']);
			$CT_24=Insec($_POST['CT24']);
			if($Officier)
			{
				if($Credits >=$CT_4)
				{
					SetData("Pilote","Hide",1,"ID",$Officier);
					$cr=-$CT_4;
					echo "<p>Le dossier a été maquillé!</p>";
					$success=true;
				}
				else
					echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";
			}
			elseif($Unite)
			{
				if($Credits >=$CT_8)
				{
					SetData("Unit","Hide",1,"ID",$Unite);
					//UpdateCarac($PlayerID,"Note",1);
					echo "<p>Les archives de l'unité sont protégées!</p>";
					$cr=-$CT_8;
					$success=true;
				}
				else
					echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";
			}
			elseif($Usine)
			{
				if($Credits >=$CT_8)
				{
					SetData("Lieu","Recce",0,"ID",$Usine);
					//UpdateCarac($PlayerID,"Note",1);
					echo "<p>Le site de production est protégé!</p>";
					$cr=-$CT_8;
					$success=true;
				}
				else
					echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";
			}
			elseif($Ville)
			{
				if($Credits >=$CT_2)
				{
					$cr=-$CT_2;
					$con=dbconnecti();	
					$result=mysqli_query($con,"SELECT Nom,Meteo_Hour,Meteo FROM Lieu WHERE ID='$Ville'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Lieu_Nom=$data['Nom'];
							$meteo_h_info=$data['Meteo_Hour'];
							$meteo_info=$data['Meteo'];
						}
						mysqli_free_result($result);
					}
					$meteo_det=GetMeteo(0,0,0,$meteo_info);
					if($Avancement >25000)
						echo "<p><img src='images/meteo".$meteo_det[1].".gif' title='".$meteo_det[0]."'> au-dessus de ".$Lieu_Nom." depuis ".$meteo_h_info."h ce jour</p>";
					else
						echo "<p><img src='images/meteo0.gif' title='temps clair, vent nul'> au-dessus de ".$Lieu_Nom." depuis ".$meteo_h_info."h ce jour</p>";
					$success=true;
				}
				else
					echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";	
			}
			elseif($Ville_eni)
			{
				if($Credits >=$CT_4)
				{
					$cr=-$CT_4;
					$con=dbconnecti();	
					$result=mysqli_query($con,"SELECT Nom,Meteo_Hour,Meteo FROM Lieu WHERE ID='$Ville_eni'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Lieu_Nom=$data['Nom'];
							$meteo_h_info=$data['Meteo_Hour'];
							$meteo_info=$data['Meteo'];
						}
						mysqli_free_result($result);
					}
					$meteo_det=GetMeteo(0,0,0,$meteo_info);
					if($Avancement >50000)
						echo "<p><img src='images/meteo".$meteo_det[1].".gif' title='".$meteo_det[0]."'> au-dessus de ".$Lieu_Nom." depuis ".$meteo_h_info."h ce jour</p>";
					else
						echo "<p><img src='images/meteo0.gif' title='temps clair, vent nul'> au-dessus de ".$Lieu_Nom." depuis ".$meteo_h_info."h ce jour</p>";
					$success=true;
				}
				else
					echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";	
			}
			elseif($Officier_eni)
			{
				if($Credits >=$CT_24)
				{
					$cr=-$CT_24;
					//UpdateCarac($PlayerID,"Note",3);
					$Hide_eni=GetData("Pilote","ID",$Officier_eni,"Hide");
					if($Hide_eni >0)
					{
						SetData("Pilote","Hide",0,"ID",$Officier_eni);
						$Event="Le dossier de cet officier a été falsifié, mais vos travaux permettront d'améliorer les prochaines recherches à son sujet.";
					}
					elseif($Avancement >50000)
					{
						//Journal Mutations
						$Nom=GetData("Pilote","ID",$Officier_eni,"Nom");
						$con=dbconnecti(4);
						$resultj=mysqli_query($con,"SELECT `Date`,Lieu,Unit,Avion_Nbr FROM Events WHERE Event_Type=31 AND PlayerID='$Officier_eni' ORDER BY ID DESC LIMIT 10");
						mysqli_close($con);
						if($resultj)
						{
							while($Classement=mysqli_fetch_array($resultj,MYSQLI_ASSOC)) 
							{
								$Event_Date=substr($Classement['Date'],0,16);
								$Event_Lieu=$Classement['Lieu'];
								$Event_Avion_Nbr=$Classement['Avion_Nbr'];
								$Event_Unit_Nom=GetData("Unit","ID",$Classement['Unit'],"Nom");
								$Event_Lieu_Nom=GetData("Lieu","ID",$Event_Lieu,"Nom");
								$Event_Unite_Dest_Nom=GetData("Unit","ID",$Event_Avion_Nbr,"Nom");
								if($Unite ==$Event_Avion_Nbr)
									$Event.=$Event_Date.' : '.$Nom.' a été transféré du '.$Event_Unit_Nom.' vers le '.$Event_Unite_Dest_Nom.', basé à '.$Event_Lieu_Nom.'<br>';
								else
									$Event.=$Event_Date.' : '.$Nom.' a été transféré du <b>'.$Event_Unite_Dest_Nom.'</b> vers le <b>'.$Event_Unit_Nom.'</b>, basé à '.$Event_Lieu_Nom.'<br>';
							}
							mysqli_free_result($resultj);
							unset($Classement);
						}
						else
							$Event="Vous ne découvrez aucune information digne d'intérêt.";
					}
					else
						$Event="Vous ne découvrez aucune information digne d'intérêt.";
					echo "<p>Vous parcourez les informations concernant cet officier<br>".$Event."</p>";
					$success=true;
				}
				else
					echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";	
			}
			elseif($Usine_eni)
			{
				if($Credits >=$CT_24)
				{
					$cr=-$CT_24;
					//UpdateCarac($PlayerID,"Note",3);
					$Limit_Rens=mt_rand(1,5);
					if($Trait ==23)$Limit_Rens*=2;
					$con=dbconnecti();
					$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
					$resultj=mysqli_query($con,"SELECT ID,Nom FROM Avion WHERE Engagement <'$Date_Campagne' AND (Usine1='$Usine_eni' OR Usine2='$Usine_eni' OR Usine3='$Usine_eni') ORDER BY RAND() LIMIT ".$Limit_Rens."");
					$resultv=mysqli_query($con,"SELECT ID,Nom FROM Cible WHERE Date <'$Date_Campagne' AND (Usine1='$Usine_eni' OR Usine2='$Usine_eni' OR Usine3='$Usine_eni') ORDER BY RAND() LIMIT ".$Limit_Rens."");
					mysqli_close($con);
					if($resultj)
					{
						while($Classement=mysqli_fetch_array($resultj,MYSQLI_ASSOC)) 
						{
							if($Avancement >25000)
								$Plane_txt.="<br>".Afficher_Image("images/avions/garage".$Classement['ID'].".jpg","images/avions/avion".$Classement['ID'].".gif",$Classement['Nom'],50);
							else
								$Plane_txt.="<br>".Afficher_Image("images/avions/garage".$Classement['ID'].".jpg","images/avions/avion".$Classement['ID'].".gif","Non identifié",50);
						}
						mysqli_free_result($resultj);
					}
					if($resultv)
					{
						while($Data=mysqli_fetch_array($resultv,MYSQLI_ASSOC)) 
						{
							if($Avancement >25000)
								$Vehs_txt.="<br>".Afficher_Image("images/cibles/cibles".$Data['ID'].".jpg","images/vehicules/vehicule".$Data['ID'].".gif",$Data['Nom'],50);
							else
								$Vehs_txt.="<br>".Afficher_Image("images/cibles/cibles".$Data['ID'].".jpg","images/vehicules/vehicule".$Data['ID'].".gif","Non identifié",50);
						}
						mysqli_free_result($resultv);
					}
					echo "<h2>Rapport de l'usine de ".GetData("Lieu","ID",$Usine_eni,"Nom")."</h2>Les photos prises par vos services vous sont transmises
					<table class='table'><thead><tr><th>Avion</th><th>Véhicule</th></tr></thead>
					<tr><td>".$Plane_txt."</td><td>".$Vehs_txt."</td></tr></table>";
					$success=true;
				}
				else
					echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";	
			}
			elseif($Unite_eni)
			{
				if($Credits >=$CT_24)
				{
					$cr=-$CT_24;
					//UpdateCarac($PlayerID,"Note",3);
					$Hide_eni=GetData("Unit","ID",$Unite_eni,"Hide");
					if(!$Hide_eni)
					{
						//acces dossier (complet en fonction du niveau de renseignement)
						//$marge=300-$Renseignement;
						$marge=100;
						$marge_low=$marge*-10;
						$marge_high=$marge*50;
						$con=dbconnecti();
						$query_unit="SELECT Nom,Base,Base_Ori,Pays,Commandant,Officier_Technique,Officier_Adjoint,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,
						Stock_Essence_87,Stock_Essence_100,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Stock_Munitions_30,
						Mission_Lieu,Mission_Type FROM Unit WHERE ID='$Unite_eni' LIMIT 1";
						$result_unit=mysqli_query($con,$query_unit);
						mysqli_close($con);
						if($result_unit)
						{
							while($Data=mysqli_fetch_array($result_unit,MYSQLI_ASSOC)) 
							{
								$Unite_Nom=$Data['Nom'];
								$Unite_Base=$Data['Base'];
								$Pays=$Data['Pays'];
								$Commandant=$Data['Commandant'];
								$Officier_Technique=$Data['Officier_Technique'];
								$Officier_Adjoint=$Data['Officier_Adjoint'];
								$Cdt="";
								$OT="";
								$OA="";
								if($Commandant)
								{
									$Av1=GetAvancement(GetData("Pilote","ID",$Commandant,"Avancement"),$Pays); 
									$Cdt=$Av1[0]." ".GetData("Pilote","ID",$Commandant,"Nom");
								}
								if($Officier_Technique)
								{
									$Av2=GetAvancement(GetData("Pilote","ID",$Officier_Technique,"Avancement"),$Pays); 
									$OT=$Av2[0]." ".GetData("Pilote","ID",$Officier_Technique,"Nom");
								}
								if($Officier_Adjoint)
								{
									$Av3=GetAvancement(GetData("Pilote","ID",$Officier_Adjoint,"Avancement"),$Pays); 
									$OA=$Av3[0]." ".GetData("Pilote","ID",$Officier_Adjoint,"Nom");
								}
								$Avion1_nom=GetData("Avion","ID",$Data['Avion1'],"Nom");
								$Avion2_nom=GetData("Avion","ID",$Data['Avion2'],"Nom");
								$Avion3_nom=GetData("Avion","ID",$Data['Avion3'],"Nom");
								$Data['Stock_Essence_87'] += mt_rand($marge_low,$marge_high);
								$Data['Stock_Essence_100'] += mt_rand($marge_low,$marge_high);
								$Data['Stock_Munitions_8'] += mt_rand($marge_low,$marge_high);
								$Data['Stock_Munitions_13'] += mt_rand($marge_low,$marge_high);
								$Data['Stock_Munitions_20'] += mt_rand($marge_low,$marge_high);
								$Data['Stock_Munitions_30'] += mt_rand($marge_low,$marge_high);								
								if($Avancement <25000)
									$Unite_Base=$Data['Base_Ori'];
								elseif($Avancement <50000 and mt_rand(0,10) <5)
									$Unite_Base=$Data['Base_Ori'];
								$Event="<br><b>Rapport concernant le <i>".$Unite_Nom."</i> basé à ".GetData("Lieu","ID",$Unite_Base,"Nom")."</b> : 
								<br><img src='images/unit/unit".$Unite_eni.".gif'>
								<br><b>Staff</b><br>".GetStaff($Pays,1)." : ".$Cdt."<br>".GetStaff($Pays,2)." : ".$OA."<br>".GetStaff($Pays,3)." : ".$OT."<p>
								<p><table rules='all'><tr><th colspan='5'>Estimation des stocks</th></tr><tr><td>87 Octane</td><td>100 Octane</td><td>8mm</td><td>13mm</td><td>20mm</td><td>30mm</td></tr>
								<tr><td>".$Data['Stock_Essence_87']."</td><td>".$Data['Stock_Essence_100']."</td><td>".$Data['Stock_Munitions_8']."</td><td>".$Data['Stock_Munitions_13']."</td><td>".$Data['Stock_Munitions_20']."</td><td>".$Data['Stock_Munitions_30']."</td></tr></table></p>
								<p><b>".$Data['Avion1_Nbr']."</b> <img src='images/avions/avion".$Data['Avion1'].".gif' title='".$Avion1_nom."'> <b>".$Data['Avion2_Nbr']."</b> <img src='images/avions/avion".$Data['Avion2'].".gif' title='".$Avion2_nom."'> <b>".$Data['Avion3_Nbr']."</b> <img src='images/avions/avion".$Data['Avion3'].".gif' title='".$Avion3_nom."'></p>";
								if($Avancement >50000)
									$Event.="<p><b>Mission en cours</b> : ".GetMissionType($Data['Mission_Type'])." en direction de ".GetData("Lieu","ID",$Data['Mission_Lieu'],"Nom")."</p>";
							}
						}
					}
					else
					{
						//acces dossier maquillé
						$query_unit="SELECT Nom,Base_Ori,Pays,Commandant,Officier_Technique,Officier_Adjoint,Avion1_Ori,Avion2_Ori,Avion3_Ori FROM Unit WHERE ID='$Unite_eni' LIMIT 1";
						$con=dbconnecti();
						$result_unit=mysqli_query($con, $query_unit);
						mysqli_close($con);
						if($result_unit)
						{
							while($Data=mysqli_fetch_array($result_unit,MYSQLI_ASSOC)) 
							{
								$Unite_Nom=$Data['Nom'];
								$Pays=$Data['Pays'];
								$Commandant=$Data['Commandant'];
								$Officier_Technique=$Data['Officier_Technique'];
								$Officier_Adjoint=$Data['Officier_Adjoint'];
								$Cdt="";
								$OT="";
								$OA="";
								if($Commandant)
								{
									$Av1=GetAvancement(GetData("Pilote","ID",$Commandant,"Avancement"),$Pays); 
									$Cdt=$Av1[0]." ".GetData("Pilote","ID",$Commandant,"Nom");
								}
								if($Officier_Technique)
								{
									$Av2=GetAvancement(GetData("Pilote","ID",$Officier_Technique,"Avancement"),$Pays); 
									$OT=$Av2[0]." ".GetData("Pilote","ID",$Officier_Technique,"Nom");
								}
								if($Officier_Adjoint)
								{
									$Av3=GetAvancement(GetData("Pilote","ID",$Officier_Adjoint,"Avancement"),$Pays); 
									$OA=$Av3[0]." ".GetData("Pilote","ID",$Officier_Adjoint,"Nom");
								}
								$Avion1_nom=GetData("Avion","ID",$Data['Avion1_Ori'],"Nom");
								$Avion2_nom=GetData("Avion","ID",$Data['Avion2_Ori'],"Nom");
								$Avion3_nom=GetData("Avion","ID",$Data['Avion3_Ori'],"Nom");
								$Data['Stock_Essence_87']=mt_rand(0,500000);
								$Data['Stock_Essence_100']=mt_rand(0,500000);
								$Data['Stock_Munitions_8']=mt_rand(0,500000);
								$Data['Stock_Munitions_13']=mt_rand(0,500000);
								$Data['Stock_Munitions_20']=mt_rand(0,500000);
								$Data['Stock_Munitions_30']=mt_rand(0,500000);
								$Event="<br><b>Rapport concernant le <i>".$Unite_Nom."</i> basé à ".GetData("Lieu","ID",$Data['Base_Ori'],"Nom")."</b> : 
								<br><img src='images/unit/unit".$Unite_eni.".gif'>
								<br><b>Staff</b><br>".GetStaff($Pays,1)." : ".$Cdt."<br>".GetStaff($Pays,2)." : ".$OA."<br>".GetStaff($Pays,3)." : ".$OT."<p>
								<p><table rules='all'><tr><th colspan='5'>Estimation des stocks</th></tr><tr><td>87 Octane</td><td>100 Octane</td><td>8mm</td><td>13mm</td><td>20mm</td><td>30mm</td></tr>
								<tr><td>".$Data['Stock_Essence_87']."</td><td>".$Data['Stock_Essence_100']."</td><td>".$Data['Stock_Munitions_8']."</td><td>".$Data['Stock_Munitions_13']."</td><td>".$Data['Stock_Munitions_20']."</td><td>".$Data['Stock_Munitions_30']."</td></tr></table></p>
								<p><b>12</b> <img src='images/avions/avion".$Data['Avion1_Ori'].".gif' title='".$Avion1_nom."'> <b>12</b> <img src='images/avions/avion".$Data['Avion2_Ori'].".gif' title='".$Avion2_nom."'> <b>12</b> <img src='images/avions/avion".$Data['Avion3_Ori'].".gif' title='".$Avion3_nom."'></p>";
								$Mission_Type=mt_rand(1,32);
								$Mission_Lieu=mt_rand(1,2322);
								$Event.="<p><b>Mission en cours</b> : ".GetMissionType($Mission_Type)." en direction de ".GetData("Lieu","ID",$Mission_Lieu,"Nom")."</p>";
							}
						}
						if($Avancement >50000)
							$Event.="<br>Vous décelez des incohérences dans le rapport concernant cette unité!<br>Vos travaux permettront d'améliorer les prochaines recherches sur cette unité.";
						else
							$Event.="<br>Vos travaux permettront d'améliorer les prochaines recherches sur cette unité.";
						SetData("Unit","Hide",0,"ID",$Unite_eni);
					}
					$success=true;
					echo $Event;
				}
				else
					echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";	
			}
			else
				echo "<p>Vous ne disposez pas de suffisamment de temps pour faire cela!</p>";	
		}
		else
			echo "<p>Vous ne disposez vraiment pas de suffisamment de temps pour faire cela!</p>";			
		if($success)
		{
			UpdateData("Officier_em","Credits",$cr,"ID",$OfficierEMID);
			UpdateCarac($OfficierEMID,"Avancement",-$cr,"Officier_em");
			//MoveCredits($PlayerID,15,$cr);
			echo "<p>Votre demande a été exécutée avec succès!</p>";
		}
	}
	else
		PrintNoAccess($country,1,4);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";