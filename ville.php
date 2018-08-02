<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) and $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once './jfv_include.inc.php';
	include_once './jfv_txt.inc.php';
	include_once './jfv_inc_em.php';
	include_once './menu_em.php';
	if($GHQ or $OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_EM or $OfficierEMID ==$Officier_Rens)
	{
		include_once './jfv_ground.inc.php';
		$QFlag_txt="Flag=$country AND Zone !=6";
		if($GHQ)
			$query="SELECT * FROM Lieu WHERE ".$QFlag_txt;
		elseif($Front ==3)
			$query="SELECT * FROM Lieu WHERE ".$QFlag_txt." AND Longitude >67";
		elseif($Front ==2)
			$query="SELECT * FROM Lieu WHERE ".$QFlag_txt." AND Latitude <43 AND Longitude <50";
		elseif($Front ==1)
			$query="SELECT * FROM Lieu WHERE ".$QFlag_txt." AND Longitude >14 AND Longitude <50 AND Latitude >41 AND Latitude <=50.5";
		elseif($Front ==4)
			$query="SELECT * FROM Lieu WHERE ".$QFlag_txt." AND Longitude >14 AND Longitude <50 AND Latitude >50.5";
		elseif($Front ==5)
			$query="SELECT * FROM Lieu WHERE ".$QFlag_txt." AND Longitude <50 AND Latitude >60";
		else
		{
			if($country ==7)
				$query="SELECT * FROM Lieu WHERE ".$QFlag_txt." AND Latitude <60 AND Longitude <=14";
			elseif($country ==4)
				$query="SELECT * FROM Lieu WHERE ".$QFlag_txt." AND Latitude >=40 AND Latitude <60 AND Longitude <=14";
			else
				$query="SELECT * FROM Lieu WHERE ".$QFlag_txt." AND Latitude >=43 AND Latitude <60 AND Longitude <=14";
		}
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		$resultu=mysqli_query($con,"SELECT COUNT(*),SUM(Industrie) FROM Lieu WHERE Flag='$country' AND TypeIndus<>'' AND Flag_Usine='$country'");
		$result2=mysqli_query($con,"SELECT COUNT(*),SUM(NoeudF) FROM Lieu WHERE Flag='$country' AND NoeudF_Ori >0 AND Flag_Gare='$country'");
		$result3=mysqli_query($con,"SELECT COUNT(*),SUM(Port) FROM Lieu WHERE Flag='$country' AND Port_Ori >0 AND Flag_Port='$country'");
		$DCA_Max=mysqli_result(mysqli_query($con,"SELECT SUM(Valeurstrat) FROM Lieu WHERE Flag='$country'"),0);
		$DCA_actu=mysqli_result(mysqli_query($con,"SELECT SUM(DefenseAA_temp) FROM Lieu WHERE Flag='$country'"),0);
		$Lieux_rev=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Lieu WHERE Flag='$country' AND Zone<>6"),0);
		mysqli_close($con);
		if($resultu)
		{
			if($data=mysqli_fetch_array($resultu,MYSQLI_NUM))
			{
				if($data[0] >0)
					$Efficacite_prod=round($data[1]/$data[0]);
				else
					$Efficacite_prod=0;
			}
			mysqli_free_result($resultu);
		}
		if($result2)
		{
			if($data=mysqli_fetch_array($result2,MYSQLI_NUM))
			{
				if($data[0] >0)
					$Efficacite_ravit=round($data[1]/$data[0]);
				else
					$Efficacite_ravit=0;
			}
			mysqli_free_result($result2);
		}
		//Outre-Mer
		if($result3)
		{
			if($data=mysqli_fetch_array($result3,MYSQLI_NUM))
			{
				if($data[0] >0)
					$Efficacite_ravit_port=round($data[1]/$data[0]);
				else
					$Efficacite_ravit_port=0;
			}
			mysqli_free_result($result3);
		}
		$DCA_Max+=($Lieux_rev*2);
		$Efficacite_ravit_colonies=round($Efficacite_ravit+$Efficacite_ravit_port)/2;		
		$msg_prod="L'efficacité de production de nos usines est estimée à <b>".$Efficacite_prod."%</b>";
		$msg_ravit="Notre ravitaillement continental fonctionne à <b>".$Efficacite_ravit."%</b> de ses possibilités.
		<br>Notre ravitaillement outre-mer fonctionne à <b>".$Efficacite_ravit_colonies."%</b> de ses possibilités.";
		if($DCA_actu >=$DCA_Max)
			$msg_dca="Le nombre de pièces de DCA installées/disponibles est de <span class='text-danger'><b>".$DCA_actu."/".$DCA_Max."</b></span>";
		else
			$msg_dca="Le nombre de pièces de DCA installées/disponibles est de <b>".$DCA_actu."/".$DCA_Max."</b>";
		$Nat="<div class='alert alert-warning'>".$msg_prod."<br>".$msg_ravit."<br>".$msg_dca."</div>";
		if($GHQ)
		{
			for($x=0;$x<6;$x++)
			{
				$Retraite[$x]=Get_Retraite($x,$country,40);
				$Retraite_txt=GetData("Lieu","ID",$Retraite[$x],"Nom");
				if($Retraite_txt)$Rets.="La base arrière du front ".GetFront($x)." est <b>".$Retraite_txt."</b><br>";
			}
		}
		else
		{
			$Retraite=Get_Retraite($Front,$country,40);
			$Retraite_txt=GetData("Lieu","ID",$Retraite,"Nom");
			if($Retraite_txt)$Rets="La base arrière du front est <b>".$Retraite_txt."</b><br>";
		}
        if($_SESSION['msg'])
            $Alert='<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg'].'</div>';
        elseif($_SESSION['msg_red'])
            $Alert='<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$_SESSION['msg_red'].'</div>';
        $_SESSION['msg']=false;
        $_SESSION['msg_red']=false;
		echo $Alert."<h3>Informations nationales</h3><div class='row'><div class='col-md-6'>".$Nat."</div><div class='col-md-6'><div class='alert alert-info'>".$Rets."</div></div></div>";?>
	    <div style='overflow:auto; width: 100%;'><table class='table table-dt table-striped table-condensed'>
		<thead><tr>
            <th>Nom</th><th title='Valeur Stratégique'>Valeur</th><th>Terrain</th><th><a href='#' class='popup'>Infr<span>Infranchissable. Direction depuis laquelle aucun déplacement n'est possible depuis ou vers ce lieu</span></a></th>
            <th>Pays</th><th title='Troupes terrestres contrôlant le lieu'>Flag</th><th>Action</th><th title='Défense Contre Avions'>DCA</th><th title='Fortifications'>Fort</th><th>Industrie</th><th>Raffinerie</th>
            <th>Gare</th><th>Port</th><th>Docks</th><th>Pont</th><th>Piste</th><th>Tour</th><th>Radar</th><th>Garnison</th><th title='Camouflage du site'>Cam</th>
            <th><a href='#' class='popup'>Auto<span>Réparations automatiques désactivées : Si le lieu a une valeur stratégique non nulle et qu'aucune attaque n'a eu lieu ici depuis au moins 3 jours, les infrastructures détruites (0%) sont remises à 1%</span></a></th>
            <th><a href='#' class='popup'>Depot<span>Dépôt réservé aux unités terrestres</span></a></th>
		</tr></thead>
<?php	if($result)
		{
			while($data=mysqli_fetch_array($result)) 
			{
				$Pays_nom=GetPays($data['Pays']);
				$ID_city=$data['ID'];					
				if($data['Zone'] ==6)
				{
					$dca_txt='N/A';
					$data['Fortification']='N/A';
				}
				elseif($data['DefenseAA_temp'] >0) {
                    if ($data['Flag'] == $country and ($GHQ or $OfficierEMID == $Commandant or $OfficierEMID == $Officier_EM)) {
                        $dca_txt = $data['DefenseAA_temp'].'<div class="row" style="padding-left: 0.5rem;"><div style="display:inline-block">';
                        include 'form/f_em_dca_up.php';
                        $dca_txt .= '</div><div  style="display:inline-block">';
                        include 'form/f_em_dca_down.php';
                        $dca_txt.='</div></div>';
//                        "<form action='index.php?view=ground_em2' method='post'><input type='hidden' name='lieu' value='".$ID_city."'><input type='hidden' name='dcad' value='1'>
//						<input type='submit' value='-' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
                    }
					else
						$dca_txt=$data['DefenseAA_temp'];
				}
				else {
                    $dca_txt="<span class='text-danger'>0</span>";
                    include 'form/f_em_dca_up.php';
                }
				if(!$data['Pont'] and $data['Pont_Ori'])
					$Pont="<span class='text-danger'>Détruit</span>";
				elseif($data['Pont'] ==100 and $data['Pont_Ori'])
					$Pont='<b>Intact</b>';
				elseif($data['Pont'] <100 and $data['Pont_Ori']){
                    $Pont="<span class='text-danger'>".$data['Pont']."%</span>";
                }
				else
					$Pont='Aucun';
                if($Admin and $data['Pont_Ori'])
                    $Pont.='<br><form action="ville_pont_des.php" method="post"><input type="hidden" name="lieu" value="'.$ID_city.'"><input type="submit" value="Sauter" class="btn btn-sm btn-danger" onclick="this.disabled=true;this.form.submit();"></form>';
				if(!$data['Port'] and $data['Port_Ori'])
					$Port="<span class='text-danger'>Détruit</span>";
				elseif($data['Port'] ==100 and $data['Port_Ori'])
					$Port="<b>Intact</b>";
				elseif($data['Port'] <100 and $data['Port_Ori'])
					$Port="<span class='text-danger'>".$data['Port']."%</span>";
				else
					$Port='Aucun';
				if($data['Port_Ori'])
				{
					if($data['Port_level'] ==3)
						$Port_txt="<a href='#' class='popup'><img src='images/map/lieu_portb0.png'><span>Base navale</span></a>";
					elseif($data['Port_level'] ==2)
						$Port_txt="<a href='#' class='popup'><img src='images/map/lieu_port0.png'><span>Principal</span></a>";
					else
						$Port_txt="<a href='#' class='popup'><img src='images/map/lieu_ports0.png'><span>Secondaire</span></a>";
				}
				else
					$Port_txt='N/A';
				if($data['BaseAerienne'] and !$data['QualitePiste'])
					$Piste="<img src='images/base".$data['BaseAerienne'].$data['Zone'].".png'><br><span class='text-danger'>Détruit</span>";
				elseif(!$data['BaseAerienne'])
					$Piste='Aucune';
				elseif($data['QualitePiste'] <100)
					$Piste="<img src='images/base".$data['BaseAerienne'].$data['Zone'].".png'><br><span class='text-danger'>".$data['QualitePiste']."%</span>";
				else
					$Piste="<img src='images/base".$data['BaseAerienne'].$data['Zone'].".png'><br><b>".$data['QualitePiste']."%</b>";
				if($data['BaseAerienne'] and !$data['Tour'])
					$Tour="/<span class='text-danger'>Détruite</span>";
				elseif(!$data['BaseAerienne'])
					$Tour='Aucune';
				elseif($data['Tour'] <100)
					$Tour="/<span class='text-danger'>".$data['Tour']."%</span>";
				else
					$Tour="/<b>".$data['Tour']."%</b>";					
				if(!$data['NoeudF'] and $data['NoeudF_Ori'])
					$Gare="<span class='text-danger'>Détruit</span>";
				elseif($data['NoeudF'] ==100 and $data['NoeudF_Ori'])
					$Gare="<b>Intacte</b>";
				elseif($data['NoeudF'] <100 and $data['NoeudF_Ori'])
					$Gare="<span class='text-danger'>".$data['NoeudF']."%</span>";
				else
					$Gare='Aucune';
				if(!$data['Radar'] and $data['Radar_Ori'])
					$Radar="<span class='text-danger'>Détruit</span>";
				elseif($data['Radar'] ==100 and $data['Radar_Ori'])
					$Radar="<b>Intact</b>";
				elseif($data['Radar'] <100 and $data['Radar_Ori'])
					$Radar="<span class='text-danger'>".$data['Radar']."%</span>";
				else
					$Radar='Aucun';
				if(!$data['Industrie'] and $data['TypeIndus'])
					$Usine="<span class='text-danger'>Détruite</span>";
				elseif($data['Industrie'] ==100 and $data['TypeIndus'])
					$Usine="<b>Intacte</b>";
				elseif($data['Industrie'] <100 and $data['TypeIndus'])
					$Usine="<span class='text-danger'>".$data['Industrie']."%</span>";
				else
					$Usine='Aucune';
				if($data['Oil'] >0)
					$Oil="<b>Niv ".$data['Oil']."</b>";
				else
					$Oil='Aucune';
				if($data['Zone'] ==6)
				{				
					$Nom="<span class='text-primary'>".$data['Nom']."</span>";
					$aa_type='N/A';
				}
				else
					$Nom=$data['Nom'];
				/*$link_arch="window.open('archives_ville.php?ville=".$data['ID']."','Rapport','width=600,height=400,scrollbars=1')";
				$Nom="<a onclick=".$link_arch.">".$Nom."</a>";*/					
				if($data['Recce'] >0)
					$Camoufle='Non';
				else
					$Camoufle='Oui';
				if($data['Auto_repare'] >0)
					$Repare='Oui';
				else
					$Repare='<b>Non</b>';
				if($data['ValeurStrat'] >3)
				{
					if($data['Depot_prive'] >0)
						$Depot='<b>Oui</b>';
					else
						$Depot='Non';
				}
				else
					$Depot='Aucun';
				$Max_Garnison=($data['ValeurStrat']*100)+100;
				if(!$data['Garnison'])$data['Garnison']='<span class="text-danger">0</span>';
				$Cible="<form action='index.php?view=em_city_ground' method='post'><input type='hidden' name='id' value='".$ID_city."'>
						<input type='submit' value='Détail' class='btn btn-primary btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				if($GHQ or $OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_EM)$Cible.="<form action='index.php?view=ground_em_infras0' method='post'><input type='hidden' name='mode' value='1'><input type='hidden' name='lieu' value='".$ID_city."'>
						<input type='submit' value='Gestion' class='btn btn-default btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				if($data['Impass'])
					$Impass='<b>'.GetImpass($data['Impass']).'</b>';
				else
					$Impass='N/A';
				$txt.="<tr><td align='left'>".$Nom."</td><td>".$data['ValeurStrat']."</td><td><img src='images/zone".$data['Zone'].".jpg'></td><td>".$Impass."</td><td><img src='".$data['Pays']."20.gif' title='".$Pays_nom."'></td><td><img src='".$data['Flag']."20.gif'></td><td>".$Cible."</td><td>".$dca_txt."</td><td>".$data['Fortification']."</td><td>".$Usine
				."</td><td>".$Oil."</td><td>".$Gare."</td><td>".$Port_txt."</td><td>".$Port."</td><td>".$Pont."</td><td>".$Piste."</td><td>".$Tour."</td><td>".$Radar."</td><td>".$data['Garnison']."/".$Max_Garnison."</td><td>".$Camoufle."</td><td>".$Repare."</td><td>".$Depot."</td></tr>";	
			}
			mysqli_free_result($result);
		}
		echo $txt."</table></div><h2>Réparations automatiques (Auto)</h2><h3>Piste</h3>
		<div class='alert alert-warning'>- Si la base aérienne est contrôlée par la nation contrôlant le lieu.
		<br>- Les pistes totalement détruites ne se réparent pas avant un délai de 4 jours sans combat, sauf sur les lieux de valeur stratégique 1 ou supérieur.
		<br>- La réparation est de 1 à 10% par jour.</div>
		<h3>Tour / Usine / Gare / Pont / Port / Radar</h3>
		<div class='alert alert-warning'>- Si la zone est contrôlée par la nation contrôlant le lieu.
		<br>- Les infrastructures totalement détruites ne se réparent pas avant un délai de 4 jours sans combat.
		<br>- La réparation est de 1 à 10% par jour.</div>
		<h2>Camouflage (Cam)</h2>
		<div class='alert alert-warning'>- Si le lieu est dotée d'un camouflage, la reconnaissance stratégique effectuée sur ce lieu s'annule chaque jour.
		<br>- Dans le cas contraire et si le lieu a été la cible d'un marquage, la reconnaissance stratégique reste active.
		<br>- Dans le cas d'un lieu non camouflé, la reconnaissance stratégique reste active, mais le pilote ayant effectué la reconnaissance perd le bénéfice de la coopération. Une reconnaissance doit être effectuée à nouveau par un pilote voulant bénéficier de la coopération.</div>";
	}
	else
		PrintNoAccess($country,1,3,4);
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';