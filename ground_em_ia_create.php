<?php
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
//$OfficierID=$_SESSION['Officier'];
if($OfficierEMID >0 xor $OfficierID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_txt.inc.php');
	$Cat=Insec($_POST['Cat']);
	$mode=Insec($_POST['Mode']);
	$Reg=Insec($_POST['Reg']);
	$Lieu=Insec($_POST['Lieu']);
	if($Cat >0 and ($Reg >0 or $mode !=1))
	{
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$Lease_Score=mysqli_result(mysqli_query($con,"SELECT Special_Score FROM Pays WHERE ID='$country'"),0);
		if($OfficierEMID)
			$result=mysqli_query($con,"SELECT Reputation,Avancement,Credits,Trait,Front FROM Officier_em WHERE ID='$OfficierEMID'");
		else
			$result=mysqli_query($con,"SELECT Reputation,Avancement,Credits,Trait,Front FROM Officier WHERE ID='$OfficierID'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Reputation=$data['Reputation'];
				$Avancement=$data['Avancement'];
				$Trait_o=$data['Trait'];
				$Credits_ori=$data['Credits'];
				$Front_ori=$data['Front'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		/*if($country ==7 and $Cat >16)
			$Retraite=2149;
		else
			$Retraite=Get_Retraite($Front,$country,40);*/
			/*if($Admin ==1)
				$Credits_ori=$CT_MAX;*/
		$titre='Hangar';
		$titre_up="<thead><tr>  
				<th width='10%'>Matériel</th>     
				<th width='1%'>Détail</th> 
				<th width='2%'>Dispo</th>
				<th width='2%'>Créer</th>
				<th width='2%'>Robustesse</th>                                           
				<th width='5%'>Armement</th>  
				<th width='5%'>Soutien</th>  
				<th width='5%'>Anti-tank</th>
				<th width='5%'>DCA</th>
				<th width='2%'>Portee</th>
				<th width='2%'>Blindage</th>                            
				<th width='2%'>Vitesse</th>                            
				<th width='2%'>Taille</th>                            
				<th width='2%'>Détection</th> 
				<th width='2%'>Autonomie</th> 
				<th width='2%'>Fiabilite</th>
				<th width='2%'>Charge</th>
				</tr></thead>";		
		$mes="<div class='alert alert-info'>Changer le matériel de cette unité ramènera son expérience à une valeur de 50 et lui attribuera une nouvelle compétence tactique aléatoire de niveau 1</div>
		<h2>Matériel disponible</h2><div><table class='table table-striped table-responsive'>".$titre_up;
		$Nid=Get_Retraite($Front_ori,$country,40,$Cat);
		if($Cat ==100 or $Cat==14 or $Cat==19 or $Cat==25 or $Cat==30) //navires de soutien
			$query="SELECT * FROM Cible WHERE ID IN(5001,5002,5204,5205,5124,5392) ORDER BY Reput ASC,HP ASC,Nom ASC"; //Navires
		elseif($Cat >16 and $Cat <25)
			$query="SELECT * FROM Cible WHERE Pays='$country' AND Date <='$Date_Campagne' AND Unit_ok=1 AND mobile=5 AND Categorie='$Cat' ORDER BY Reput ASC,HP ASC,Nom ASC"; //Navires
		else
			$query="SELECT * FROM Cible WHERE Pays='$country' AND Date <='$Date_Campagne' AND Unit_ok=1 AND mobile NOT IN (4,5) AND Categorie='$Cat' AND Reput <='$Credits_ori' AND Premium=0 AND (Lease=0 OR Lease <='$Lease_Score') ORDER BY Reput ASC,HP ASC,Nom ASC";
		$result=mysqli_query($con,$query);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$ID=$data['ID'];
				$mobile=$data['mobile'];
				$Fiabilite=$data['Fiabilite'];
				if($data['mobile'] !=4 and $data['Production'] >0 and $data['Reput'] >1)
				{
					$Usines=1;
					$Indus1=GetData("Lieu","ID",$data['Usine1'],"Industrie");
					if($data['Usine2'])
					{
						$Indus2=GetData("Lieu","ID",$data['Usine2'],"Industrie");
						$Indus1+=$Indus2;
						$Usines++;
					}
					if($data['Usine3'])
					{
						$Indus3=GetData("Lieu","ID",$data['Usine3'],"Industrie");
						$Indus1+=$Indus3;
						$Usines++;
					}
					$Prod=$Indus1/$Usines;
					if($Prod <50)
						$Reste=0;
					else
					{
						//$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$ID'"),0);
						$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$ID'"),0);
						if($Cat ==20 or $Cat ==21 or $Cat ==24)
						{
							$Perdus=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Vehicule_ID='$ID' AND Vehicule_Nbr=0"),0);
							$Perdus2=0;
						}
						else
						{
							$con4=dbconnecti(4);
							$Perdus=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$ID'"),0);
							$Perdus2=mysqli_result(mysqli_query($con4,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$ID'"),0);
							if($data['Categorie'] ==5 or $data['Categorie'] ==6)
								$Perdus3=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$ID'"),0);
							mysqli_close($con4);
						}
						$Reste=floor($data['Stock']-$Service-$Service2-$Perdus-$Perdus2-$Perdus3+$data['Repare']);
						if($Reste+$Service+$Service2 >$data['Stock'])$Reste=floor($data['Stock']-$Service-$Service2);
					}
				}
				else
					$Reste=100;
				$btn_class='default';
				$Arme_Inf='';
				$Arme_Art='';
				$Arme_AT='';
				$Arme_AA='';
				if($data['Arme_Inf'])
					$Arme_Inf=GetData("Armes","ID",$data['Arme_Inf'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_Inf'],"Calibre"))."mm)<br>".$data['Arme_Inf_mun']."muns";
				elseif($data['Arme_AA3'])
					$Arme_Inf=GetData("Armes","ID",$data['Arme_AA3'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA3'],"Calibre"))."mm)<br>".$data['Arme_Inf_mun']."muns";
				if($data['Arme_Art'])
					$Arme_Art=GetData("Armes","ID",$data['Arme_Art'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_Art'],"Calibre"))."mm)<br>".$data['Arme_Art_mun']."muns";
				elseif($data['Arme_AA2'])
					$Arme_Art=GetData("Armes","ID",$data['Arme_AA2'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA2'],"Calibre"))."mm)<br>".$data['Arme_Art_mun']."muns";
				if($data['Arme_AT'])
					$Arme_AT=GetData("Armes","ID",$data['Arme_AT'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AT'],"Calibre"))."mm)<br>".$data['Arme_AT_mun']."muns";
				if($data['Arme_AA'])
					$Arme_AA=GetData("Armes","ID",$data['Arme_AA'],"Nom")."<br>(".round(GetData("Armes","ID",$data['Arme_AA'],"Calibre"))."mm)<br>".$data['Arme_AA_mun']."muns";
				$HP=$data['HP'];
				$Portee=$data['Portee'];
				$Blindage=$data['Blindage_f'];
				$Vitesse=$data['Vitesse'];
				$Taille=$data['Taille'];
				$Detection=$data['Detection'];
				$Autonomie=$data['Fuel'];
				$Charge=$data['Charge'];
				if(!$Charge)
					$Charge='Aucune';
				else
					$Charge.='kg/l';
				if($data['Carbu_ID'] ==1)
					$Fuel='Diesel';
				elseif($data['Carbu_ID'] ==87)
					$Fuel='Essence';
				else
					$Fuel='Moral';
				if($data['Type'] ==99)
					$data['Nom'].=' (Aide à neutraliser les saboteurs)';
				$Reput=$data['Reput'];
				if($mobile ==5 and $Reput >=$CT_MAX)
				{
					if($data['Categorie'] ==23)
						$Reput=40;
					else
						$Reput=$CT_MAX;
				}
				else
				{
					/*if($Reste <10 and $mobile !=4 and $mobile !=5)
						$Reput*=2;
					else*/if($mode==1 and ($data['Usine1'] ==$Lieu or ($data['Usine2'] >0 and $data['Usine2'] ==$Lieu) or ($data['Usine3'] >0 and $data['Usine3'] ==$Lieu)))
					{
						$Reput=1;
						$btn_class='primary';
					}
				}
				if($Credits_ori >=$Reput)
				{
                    $Lands=GetAllies($Date_Campagne);
                    if(IsAxe($country))
                        $Allies=explode(",",$Lands[1]);
                    else
                        $Allies=explode(",",$Lands[0]);
					if($data['Usine1'])
					{
						$resultu1=mysqli_query($con,"SELECT Nom,Flag,Flag_Usine FROM Lieu WHERE ID='".$data['Usine1']."'");
						if($resultu1)
						{
							while($datau1=mysqli_fetch_array($resultu1,MYSQLI_ASSOC))
							{
								$Usine1_Nom=$datau1['Nom'];
								$Usine1_Flag=$datau1['Flag'];
								$Usine1_Flag_Usine=$datau1['Flag_Usine'];
							}
							mysqli_free_result($resultu1);
						}
					}
					if(($Cat ==5 or $Cat ==100) and $Nid and (!$data['Usine1'] or !in_array($Usine1_Flag,$Allies) or !in_array($Usine1_Flag_Usine,$Allies))) //Infanterie ou navires soutien
					{
						$resultu1=mysqli_query($con,"SELECT Nom,Flag,Flag_Usine FROM Lieu WHERE ID='".$Nid."'");
						if($resultu1)
						{
							while($datau2=mysqli_fetch_array($resultu1,MYSQLI_ASSOC))
							{
								$Usine1_Nom=$datau2['Nom'];
								$Usine1_Flag=$datau2['Flag'];
								$Usine1_Flag_Usine=$datau2['Flag_Usine'];
							}
							mysqli_free_result($resultu1);
						}
					}
					elseif($data['Usine1'])
						$Nid=$data['Usine1'];
					$Reste_txt=$Reste;
					if($data['Lease'])
					{
						if(in_array($Usine1_Flag,$Allies,true) and in_array($Usine1_Flag_Usine,$Allies,true))
							$lend_lease=true;
						else
							$lend_lease=false;
						$Reste_txt.="<a href='#' class='popup'><img src='images/lendlease.png'><span>".$data['Lease']." points Lend-Lease nécessaires<br>Total actuel de la nation <b>".$Lease_Score."</b></span></a>";
					}
					else
						$lend_lease=true;
					$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Nid' AND r.Position<>25 AND r.Placement IN(4,6) AND r.Vehicule_Nbr >0"),0);
					if(!$lend_lease)
						$Creer_txt="<td class='text-danger' title='Ce matériel nécessite ".$data['Lease']." Points de Lend-Lease'>Lend-Lease</td>";
					elseif($Front !=$Front_ori or $Enis >0)
						$Creer_txt="<td><a href='#' class='popup'><i class='text-danger'>Combat</i><span>Votre usine est sous le feu des troupes terrestres ennemies, tout recrutement est impossible !</span></a></td>";
					elseif($Reste >3 or $mobile ==4 or ($Reste >0 and $mobile ==5))
					{
                        if($data['Pays'] >0 and (!$Nid or !in_array($Usine1_Flag,$Allies,true) or !in_array($Usine1_Flag_Usine,$Allies,true)))
						    if(!$data['Production'])
                                $Creer_txt="<td><a href='#' class='popup'><i class='text-danger'>Pas ici</i><span>Cette unité ne peut être recrutée que sur la base arrière du front ".GetData("Lieu","ID",$Nid,"Nom")."</span></a></td>";
						    else
    							$Creer_txt="<td><a href='#' class='popup'><i class='text-danger'>Occupé</i><span>Votre usine est occupée par les troupes ennemies, tout recrutement est impossible !</span></a></td>";
						else
						{
							if($mode==1)
								$Creer_txt="<td><form action='index.php?view=ground_em_ia_upgrade' method='post'>
								<input type='hidden' name='Ve' value='".$data['ID']."'>
								<input type='hidden' name='Cr' value='".$Reput."'>
								<input type='hidden' name='Reg' value='".$Reg."'>
								<input type='Submit' value='".$Reput." CT' class='btn btn-".$btn_class."' onclick='this.disabled=true;this.form.submit();'></form></td>";
							elseif($OfficierEMID >0)
								$Creer_txt="<td><form action='index.php?view=ground_em_ia_create_do' method='post'>
								<input type='hidden' name='Ve' value='".$data['ID']."'>
								<input type='hidden' name='Cr' value='".$Reput."'>
								<input type='hidden' name='Nid' value='".$Nid."'>
								<input type='Submit' value='".$Reput." CT' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td>";
						}
					}
					elseif($Prod <50)
						$Creer_txt="<td class='text-danger' title='Votre nation doit réparer les usines détruites'>Usines ".$Prod."%</td>";
					else
						$Creer_txt="<td class='text-danger' title='Votre nation doit réparer les modèles détruits'>".$Reste." Dispo</td>";
					$mes.="<tr><td><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'><br>".$Usine1_Nom."</td>
					<td><form><input type='button' value='Détail' class='btn btn-primary' onclick=\"window.open('cible.php?cible=".$data['ID']."','Fiche','width=820,height=840,scrollbars=1')\"></form></td>
					<td>".$Reste_txt."</td>
					".$Creer_txt."
					<td>".$HP."</td>
					<td>".$Arme_Inf."</td>
					<td>".$Arme_Art."</td>
					<td>".$Arme_AT."</td>
					<td>".$Arme_AA."</td>
					<td>".$Portee."</td>
					<td>".$Blindage."</td>
					<td>".$Vitesse."</td>
					<td>".$Taille."</td>
					<td>".$Detection."</td>
					<td>".$Autonomie." (".$Fuel.")</td>
					<td>".$Fiabilite."</td>
					<td>".$Charge."</td></tr>";
				}
				else
				{
					$mes.="<tr><td><img src='images/vehicules/vehicule".$data['ID'].".gif' title='".$data['Nom']."'></td>
					<td><form><input type='button' value='Détail' class='btn btn-primary' onclick=\"window.open('cible.php?cible=".$data['ID']."','Fiche','width=820,height=840,scrollbars=1')\"></form></td>
					<td>".$Reste."</td>
					<td class='btn btn-danger'>".$Reput." CT</td>
					<td>".$HP."</td>
					<td>".$Arme_Inf."</td>
					<td>".$Arme_Art."</td>
					<td>".$Arme_AT."</td>
					<td>".$Arme_AA."</td>
					<td>".$Portee."</td>
					<td>".$Blindage."</td>
					<td>".$Vitesse."</td>
					<td>".$Taille."</td>
					<td>".$Detection."</td>
					<td>".$Autonomie." (".$Fuel.")</td>
					<td>".$Fiabilite."</td>
					<td>".$Charge."</td></tr>";
				}
			}
			mysqli_free_result($result);
			unset($data);
		}
		$mes.='</table></div>';
		if($OfficierEMID)
			$menu="<a href='index.php?view=ground_em_ia_list' class='btn btn-default' title='Retour'>Retour</a>";
		else
			$menu="<a href='index.php?view=ground_bat' class='btn btn-default' title='Retour'>Retour</a>";
		include_once('./default.php');
	}
	else
		echo 'Tsss!';
}
else
	echo 'Vous devez être connecté!';