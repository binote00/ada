<?
include_once('./jfv_include.inc.php');
$ID=Insec($_GET['cible']);
if(is_numeric($ID))
{
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	include_once('./jfv_combat.inc.php');
	$Premium=0;
	$con=dbconnecti();
	$ID=mysqli_real_escape_string($con,$ID);
	$result=mysqli_query($con,"SELECT c.*,DATE_FORMAT(`Date`,'%d-%m-%Y') as Engagement FROM Cible as c WHERE ID='$ID'");
	if($result)
	{
		while($data=mysqli_fetch_array($result)) 
		{
			$Pays_nom=GetPays($data['Pays']);
			$Rob=log10($data['HP']);
            $CT_Spec=2+floor($data['Reput']/10)-$data['Fiabilite'];
            if($CT_Spec <1)$CT_Spec=1;
            if($data['Categorie'] ==5){
                $Atk_txt.="<img src='/images/CT".$CT_Spec.".png' title='Montant en Crédits Temps que nécessite cette action'> Attaque";
            }
            if($data['Categorie'] ==2 or $data['Categorie'] ==3 or $data['Categorie'] ==5 or $data['Categorie'] ==7){
                $Atk_txt.="<img src='/images/CT0.png' title='Montant en Crédits Temps que nécessite cette action'> Assaut";
            }
			if($Rob <1) //<10
				$Robustesse="<span class='text-danger'>Très faible</span>";
			elseif($Rob <2) //<100
				$Robustesse="<span class='text-danger'>Faible</span>";
			elseif($Rob <3) //<1000
				$Robustesse="Bonne";
			elseif($Rob <4) //<10.000
				$Robustesse="<span class='text-success'>Très bonne</span>";
			elseif($Rob <5) //<100.000
				$Robustesse="<span class='text-success'>Elevée</span>";
			elseif($Rob <6) //<1.000.000
				$Robustesse="<span class='text-success'>Très élevée</span>";
			else
				$Robustesse="<span class='text-success'>Supérieure</span>";
			if($data['Mountain'])$data['Type']=97;
            if($data['Type'] ==18 or $data['Type'] ==19 or $data['Type'] ==20 or $data['Type'] ==21)
				$gros_navire=true;
			else
				$gros_navire=false;
			if(!$gros_navire and $data['Arme_Inf'])
			{
				$Arme_Inf_txt="Standard";
				$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='".$data['Arme_Inf']."'");
				if($resulta)
				{
					while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
					{
						$Arme_Inf_Cal=$data3['Calibre'];
						$Arme_Inf_Mu=$data3['Multi'];
						$Arme_Inf_Dg=$data3['Degats'];
						$Arme_Inf_Perf=$data3['Perf'];
						$Arme_Inf_Range=$data3['Portee'];
						$Arme_Inf_Range_Max=$data3['Portee_max'];
						$Arme_Inf="<b>".$data3['Nom'].'</b> ('.round($Arme_Inf_Cal).'mm)<br>'.$data['Arme_Inf_mun'].' coups';
					}
					mysqli_free_result($resulta);
				}
				if($data['Arme_Inf'] ==136)
					$Specs.="Attaque au lance-flammes démoralisante contre les unités d'infanterie<br>Ignore les fortifications<br>";
				$Arme_Inf.=" <span class='badge'>Dégâts ".round($Arme_Inf_Cal)."-".($Arme_Inf_Dg*$Arme_Inf_Mu)."</span>";
			}
			elseif($gros_navire and $data['Arme_AA3'])
			{
				$Arme_Inf_txt="Anti-aérien basse altitude";
				$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='".$data['Arme_AA3']."'");
				if($resulta)
				{
					while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
					{
						$Arme_Inf_Cal=$data3['Calibre'];
						$Arme_Inf_Mu=$data3['Multi'];
						$Arme_Inf_Dg=$data3['Degats'];
						$Arme_Inf_Perf=$data3['Perf'];
						$Arme_Inf_Range=$data3['Portee'];
						$Arme_Inf_Range_Max=$data3['Portee_max'];
						$Arme_Inf="<b>".$data3['Nom'].'</b> ('.round($Arme_Inf_Cal).'mm)<br>'.$data['Arme_AA3_mun'].' coups';
					}
					mysqli_free_result($resulta);
				}
				$Arme_Inf.=" <span class='badge'>Dégâts ".round($Arme_Inf_Cal)."-".($Arme_Inf_Dg*$Arme_Inf_Mu)."</span>";
			}
			if($data['Arme_Art'])
			{
			    if($data['Categorie'] ==8){
                    $Atk_txt.="<img src='/images/CT".$CT_Spec.".png' title='Montant en Crédits Temps que nécessite cette action'> Bombardement
                    <img src='/images/CT".$CT_Spec.".png' title='Montant en Crédits Temps que nécessite cette action'> Destruction";
                }
				if($gros_navire)
					$Arme_Art_txt="Artillerie principale";
				else
					$Arme_Art_txt="Soutien";
				$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='".$data['Arme_Art']."'");
				if($resulta)
				{
					while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
					{
						$Arme_Art_Cal=$data3['Calibre'];
						$Arme_Art_Mu=$data3['Multi'];
						$Arme_Art_Dg=$data3['Degats'];
						$Arme_Art_Perf=$data3['Perf'];
						$Arme_Art_Range=$data3['Portee'];
						$Arme_Art_Range_Max=$data3['Portee_max'];
						$Arme_Art="<b>".$data3['Nom'].'</b> ('.round($Arme_Art_Cal).'mm)<br>'.$data['Arme_Art_mun'].' coups';
					}
					mysqli_free_result($resulta);
				}
				$Arme_Art.=" <span class='badge'>Dégâts ".round($Arme_Art_Cal)."-".($Arme_Art_Dg*$Arme_Art_Mu)."</span>";
				if($data['Arme_Art'] >186 and $data['Arme_Art'] <192)
					$Specs.="Attaque démoralisante contre les unités d'infanterie<br>";
				if($data['Portee'] >2500 and $data['Categorie'] !=15 and $data['Type'] !=91)
				{
					$Specs.="Peut bombarder les zones adjacentes<br>";
					if($data['mobile'] ==5)
					{
						$Shoots_cb=floor($data['Arme_Art_mun']/10);
						$Shoots_cb=floor(sqrt($Shoots_cb));
						$Riposte_txt=$Shoots_cb." tir(s)";
					}
					else
						$Riposte_txt=$Arme_Art_Mu." tir(s)";
				}
			}
			if($data['Arme_AT'])
			{
				if($data['Type'] >13 and $data['Type'] <38)
					$Arme_AT_txt="Torpilles";
				else
					$Arme_AT_txt="Anti-tank";
				$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='".$data['Arme_AT']."'");
				if($resulta)
				{
					while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
					{
						$Arme_AT_Cal=$data3['Calibre'];
						$Arme_AT_Mu=$data3['Multi'];
						$Arme_AT_Dg=$data3['Degats'];
						$Arme_AT_Perf=$data3['Perf'];
						$Arme_AT_Range=$data3['Portee'];
						$Arme_AT_Range_Max=$data3['Portee_max'];
						$Arme_AT='<b>'.$data3['Nom'].'</b> ('.round($Arme_AT_Cal).'mm)<br>'.$data['Arme_AT_mun'].' coups';
					}
					mysqli_free_result($resulta);
				}
				$Arme_AT.=" <span class='badge'>Dégâts ".round($Arme_AT_Cal)."-".($Arme_AT_Dg*$Arme_AT_Mu)."</span>";
                if($data['Categorie'] ==15 and $Arme_AT_Cal >74){
                    $Atk_txt.="<img src='/images/CT".$CT_Spec.".png' title='Montant en Crédits Temps que nécessite cette action'> Tir";
                }
                if($data['Categorie'] ==2 or $data['Categorie'] ==3 or $data['Categorie'] ==7 or $data['Type'] ==11){
                    $CT_Spec_Blitz=$CT_Spec-2;
                    if($CT_Spec_Blitz <1)$CT_Spec_Blitz=1;
                    $Atk_txt.="<img src='/images/CT".$CT_Spec_Blitz.".png' title='Montant en Crédits Temps que nécessite cette action'> Attaque";
                }
			}
			elseif($gros_navire and $data['Arme_AA2'])
			{
				$Arme_AT_txt="Anti-aérien moyenne altitude";
				$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='".$data['Arme_AA2']."'");
				if($resulta)
				{
					while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
					{
						$Arme_AT_Cal=$data3['Calibre'];
						$Arme_AT_Mu=$data3['Multi'];
						$Arme_AT_Dg=$data3['Degats'];
						$Arme_AT_Perf=$data3['Perf'];
						$Arme_AT_Range=$data3['Portee'];
						$Arme_AT_Range_Max=$data3['Portee_max'];
						$Arme_AT='<b>'.$data3['Nom'].'</b> ('.round($Arme_AT_Cal).'mm)<br>'.$data['Arme_AA2_mun'].' coups';
					}
					mysqli_free_result($resulta);
				}
				$Arme_AT.=" <span class='badge'>Dégâts ".round($Arme_AT_Cal)."-".($Arme_AT_Dg*$Arme_AT_Mu)."</span>";
			}
            elseif($data['Arme_AA3'])
            {
                $Arme_AT_txt="Anti-aérien basse altitude";
                $resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='".$data['Arme_AA3']."'");
                if($resulta)
                {
                    while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
                    {
                        $Arme_AT_Cal=$data3['Calibre'];
                        $Arme_AT_Mu=$data3['Multi'];
                        $Arme_AT_Dg=$data3['Degats'];
                        $Arme_AT_Perf=$data3['Perf'];
                        $Arme_AT_Range=$data3['Portee'];
                        $Arme_AT_Range_Max=$data3['Portee_max'];
                        $Arme_AT="<b>".$data3['Nom'].'</b> ('.round($Arme_AT_Cal).'mm)<br>'.$data['Arme_AA3_mun'].' coups';
                    }
                    mysqli_free_result($resulta);
                }
                $Arme_AT.=" <span class='badge'>Dégâts ".round($Arme_AT_Cal)."-".($Arme_AT_Dg*$Arme_AT_Mu)."</span>";
            }
			if($data['Arme_AA'])
			{
				if($gros_navire)
					$Arme_AA_txt="Anti-aérien haute altitude";
				else
					$Arme_AA_txt="Anti-aérien";
				$resulta=mysqli_query($con,"SELECT Nom,Calibre,Multi,Degats,Perf,Portee,Portee_max FROM Armes WHERE ID='".$data['Arme_AA']."'");
				if($resulta)
				{
					while($data3=mysqli_fetch_array($resulta,MYSQLI_ASSOC))
					{
						$Arme_AA_Cal=$data3['Calibre'];
						$Arme_AA_Mu=$data3['Multi'];
						$Arme_AA_Dg=$data3['Degats'];
						$Arme_AA_Perf=$data3['Perf'];
						$Arme_AA_Range=$data3['Portee'];
						$Arme_AA_Range_Max=$data3['Portee_max'];
						$Arme_AA='<b>'.$data3['Nom'].'</b> ('.round($Arme_AA_Cal).'mm)<br>'.$data['Arme_AA_mun'].' coups';
					}
					mysqli_free_result($resulta);
				}
				$Arme_AA.=" <span class='badge'>Dégâts ".round($Arme_AA_Cal)."-".($Arme_AA_Dg*$Arme_AA_Mu)."</span>";
			}
			if($data['ID'] ==5392)
				$Type='Dépôt flottant';
			elseif($data['Type']){
                $Type=GetData("Veh_Type","ID",$data['Type'],"Type");
                if($data['Type'] == TYPE_ART){
                    if($data['Vitesse'] ==1){
                        $Type.=' de siège';
                        $Specs.='Peut être attaqué par les unités mobiles ennemies s\'il n\'est pas protégé par une unité alliée en ligne ou en embuscade sur la même zone<br>';
                    }elseif($data['Vitesse'] ==2){
                        //$Type.=' ancienne';
                        $Specs.='Peut être attaqué par les unités mobiles ennemies s\'il n\'est pas protégé par une unité alliée en ligne ou en embuscade sur la même zone<br>';
                    }else{
                        $Type.=' moderne';
                        $Specs.='Ne peut être attaqué que par les unités mobiles ennemies possédant une allonge de raid égale ou supérieure à <b>'.($data['Portee']/10000).'</b><br>';
                    }
                }
            }
			else
				$Type='Infanterie';
			if($data['Charge'])
				$Charge=$data['Charge'].'kg';
			else
				$Charge='Aucun';				
			if(!$data['Sol_meuble'])$data['Sol_meuble']=1;				
			if($data['Amphi']){
				$Amphi='<b>Oui</b>';
				$Specs.='Traversée des fleuves facilitée<br>Peut effectuer un débarquement sur une plage<br>';
			}
			else
				$Amphi='Non';
			$Bonus_Tactique=($data['Radio']*5)+($data['Tourelle']*5);			
			if($data['Fiabilite'] >1)
				$Fiabilite="<span class='text-success'>Très Bonne</span>";
			elseif($data['Fiabilite'] >0)
				$Fiabilite="<span class='text-success'>Bonne</span>";
			elseif($data['Fiabilite'] <-1)
				$Fiabilite="<span class='text-danger'>Très Mauvaise</span>";
			elseif($data['Fiabilite'] <0)
				$Fiabilite="<span class='text-danger'>Mauvaise</span>";
			else
				$Fiabilite='Moyenne';
			if($data['Detection'] >19 and $data['Categorie'] <8)
			{
                $Cr_reco=2-$Fiabilite;
                if($Cr_reco <1)$Cr_reco=1;
                $Atk_txt.="<img src='/images/CT".$Cr_reco.".png' title='Montant en Crédits Temps que nécessite cette action'> Reconnaissance";
				if($data['Categorie'] !=5)
					$Type.=" de reconnaissance";
				if($data['mobile'] !=4 and $data['mobile'] !=5)
					$Specs.="Peut revendiquer une zone ou un lieu<br>";
			}
			if($data['Categorie'] ==14)
				$Type.=' (tanker)';
			elseif($data['Categorie'] ==19)
			{
				$Type.=' démineur';
				$Specs.='Peut déminer<br>';
			}
			elseif($data['Categorie'] ==6 or $data['Categorie'] ==9 or ($data['Categorie'] ==15 and $Arme_AT_Cal <75))
                $Specs.='<span class="text-danger">Unité défensive ne pouvant attaquer</span><br>';
			if($data['Vitesse'] >10 and $data['Type'] !=1)
			{
				if($data['mobile'] ==1 or $data['mobile'] ==2 or $data['mobile'] ==6 or $data['mobile'] ==7) 
					$Specs.="Distance de raid doublée dans les plaines ou le désert en cas <a href='#' class='popup'>d'attaque surprise<span>Une attaque surprise a lieu lorsque l'unité attaquante n'est pas détectée par l'ennemi (Si l'unité ennemie est une unité de reconnaissance ou si l'unité attaquante est visible, elle est considérée comme détectée)</span></a><br>";
				elseif($data['mobile'] ==3)
					$Specs.="Distance de raid doublée dans les forêts,montagnes,jungles et zones urbaines en cas <a href='#' class='popup'>d'attaque surprise<span>Une attaque surprise a lieu lorsque l'unité attaquante n'est pas détectée par l'ennemi (Si l'unité ennemie est une unité de reconnaissance ou si l'unité attaquante est visible, elle est considérée comme détectée)</span></a><br>";
			}
			if($data['Type'] ==92)
				$Specs.="Peut saboter";
			elseif($data['Type'] ==95)
				$Specs.="Peut rallier les troupes de sa division<br>Peut revendiquer une zone<br>Peut définir la base arrière de la division";
			elseif($data['Type'] ==97)
				$Specs.="Déplacement doublé en montagne";
			elseif($data['Type'] ==98)
				$Specs.="Peut miner,déminer,réparer,saboter<br>";
			elseif($data['Type'] ==99)
				$Specs.="Diminue les risques de sabotage<br>Détecte les parachutages de commandos ennemis";
			elseif($data['Type'] ==90)
				$Specs.="Déplacement doublé dans les jungles ou les marais";
			elseif($data['Type'] ==91 or $data['Type'] ==7 or $data['Type'] ==10)
				$Specs.="Attaque démoralisante contre les unités d'infanterie non équipées d'armement anti-char";
			elseif($data['Type'] ==4 or $data['Type'] ==9)
				$Specs.="Lors des embuscades, a plus de chance de cibler le blindage arrière ou latéral";
			elseif($data['Type'] ==21)
				$Specs.="Peut embarquer <b>".$data['Esc']."</b> unités aériennes";
			elseif($data['Type'] ==6)
				$Specs.="Ne peut pas se déplacer <b>vers</b> des zones de montagne";
			elseif($data['Categorie'] ==19)
				$Specs.="Peut éliminer les mines marines";
			elseif($data['Categorie'] ==25)
				$Specs.="Peut miner les zone côtières et les détroits";
			elseif($data['Categorie'] ==26)
				$Specs.="Peut charger et décharger du ravitaillement dans les dépôts";
			elseif($data['Categorie'] ==38)
				$Specs.="Peut charger du ravitaillement au dépôt<br>Fonctionne comme un dépôt pour les autres unités terrestres et aériennes sur le même lieu";
			if($data['Para'] >0)
				$Specs.="<br>Peut être parachuté";
			if($data['Charge'] >0 and $data['Type'] >13)
				$Type.=" cargo";				
			if($data['mobile'] ==1)
				$Prop="Roues";
			elseif($data['mobile'] ==2)
				$Prop="Chenilles";
			elseif($data['mobile'] ==6)
				$Prop="Roues 4x4";
			elseif($data['mobile'] ==7)
				$Prop="Monté";
			elseif($data['mobile'] ==4)
				$Prop="Rail";
			elseif($data['mobile'] ==5)
			{
				$Prop="Maritime";
				if($data['Type'] <18 and $data['Detection'] >10)
					$Specs.="Sonar";
			}
			else
				$Prop="Non motorisé";
			if($data['Carbu_ID'] ==1)
				$Carbu="Diesel";
			elseif($data['Carbu_ID'] ==87)
				$Carbu="Essence";
			if(is_file('images/vehicules/vehicule'.$ID.'_f2.gif'))
				$f2="<img src='images/vehicules/vehicule".$ID."_f2.gif'>";
			if(is_file('images/vehicules/vehicule'.$ID.'_f1.gif'))
				$f1="<img src='images/vehicules/vehicule".$ID."_f1.gif'>";				
			if($data['Flak'] > 0)
				$Couv_DCA="<b>Oui</b>";
			else
				$Couv_DCA="Non";
			if($data['Categorie'] ==5 or $data['Categorie'] ==6 or $data['Categorie'] ==9)
				$Couv_Ligne="<b>Oui</b>";
			else
				$Couv_Ligne="Non";				
			if($data['Categorie'] ==13 or $data['Categorie'] ==17)
				$min_grade=$data['Reput']*500;
			elseif($data['mobile'] ==4 or $data['mobile'] ==5)
				$min_grade=$data['Reput']*1000;
			else
			{
				$min_grade=$data['Reput']*1000;
				$min_rep=$data['HP']*2;
				$Rep_min=GetReputOfficier($min_rep);
			}
			$Grade_min=GetAvancement($min_grade,$data['Pays'],0,1);
?>
<!DOCTYPE html><html><head><title>Aube des Aigles</title>
	<link rel="stylesheet" href="./css/lib/bootstrap.min.css">
	<link rel="stylesheet" href="./css/bootstrap-theme.css">
	<link rel="stylesheet" href="./css/main.css">
	<link rel="stylesheet" href="./css/ada.css">
	<link rel="stylesheet" href="./css/cible.css">
</head>
<body class="cible">	
	<table align="center">
		<tr class="titre"><th colspan='4'><?=$data['Nom']?></th></tr>
		<tr>
			<td><?=$data['Engagement']?></td>
			<td colspan='2'><img src="images/vehicules/vehicule<?=$ID?>.gif"><?echo $f2.$f1;?></td>
			<td><?=$Pays_nom?> <img src="images/<?=$data['Pays']?>20.gif"></td>
		</tr>
		<tr class="bg_brown"><th colspan='4'>Informations générales</th></tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Type</th>
			<td class='dark left'><?=$Type?></td>
			<th class='dark left bold'>Taille</th>
			<td class='dark left'><?=$data['Taille']?></td>
		</tr>
		<tr class="bg_brown"><th colspan='4'>Caractéristiques</th></tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Robustesse</th>
			<td class='dark left'><?=$Robustesse?></td>
			<th class='dark left bold'>Blindage</th>
			<td class='dark left'><a href='#' class='popup'><?echo $data['Blindage_f'].'/'.$data['Blindage_l'].'/'.$data['Blindage_a'];?><span>Avant/Flancs/Arrière</span></a></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Vitesse max (sur route)</th>
			<td class='dark left'><?echo $data['Vitesse'].'km/h';?></td>
			<th class='dark left bold'>Propulsion</th>
			<td class='dark left'><?=$Prop?></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'><a href='#' class='popup'>Fiabilité<span>Influe sur le coût des actions offensives et la vitesse en combat</span></a></th>
			<td class='dark left'><?echo $Fiabilite;?></td>
			<th class='dark left bold'><a href='#' class='popup'>Amphibie<span>Unité pouvant être débarquée sur les plages. Traversée des fleuves facilitée</span></a></th>
			<td class='dark left'><?echo $Amphi;?></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Puissance motrice</th>
			<td class='dark left'><?echo $data['Puissance'].'cv';?></td>
			<th class='dark left bold'>Consommation (sur route)</th>
			<td class='dark left'><?echo $data['Conso'].' '.$Carbu;?></td>
		</tr>
		<?if($data['mobile'] !=5){?>
		<tr class="bg_brown"><th colspan='4'>Distance franchissable</th></tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Sur route/En plaine</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=0;
			$Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
            $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
            $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
            $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
			.Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
			.Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
			.Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
			<th class='dark left bold'>Colline</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=1;
            $Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
            $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
            $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
            $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Forêt</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=2;
            $Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
            $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
            $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
            $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
			<th class='dark left bold'>Colline boisée</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=3;
            $Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
            $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
            $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
            $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Montagne</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=4;
                    $Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
                    $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
                    $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
                    $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
			<th class='dark left bold'>Montagne boisée</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=5;
			$Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, $Front,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Urbain</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=7;
                    $Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
                    $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
                    $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
                    $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
			<th class='dark left bold'>Désert</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=8;
                    $Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
                    $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
                    $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
                    $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Marais</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=9;
                    $Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
                    $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
                    $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
                    $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
			<th class='dark left bold'>Jungle</th>
			<td class='dark left'><a href='#' class='popup'><?$Zone=11;
                    $Auto=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 0,true);
                    $Auto1=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 1,true);
                    $Auto2=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 2,true);
                    $Auto3=Get_LandSpeed($data['Fuel'],$data['mobile'],$Zone,0,$data['Type'],0,0,$Amphi, 3,true);
			echo Auto_max($Auto,$Zone,$data['mobile'],0,$data['Type']).'/'
                .Auto_max($Auto2,$Zone,$data['mobile'],2,$data['Type']).'/'
                .Auto_max($Auto1,$Zone,$data['mobile'],1,$data['Type']).'/'
                .Auto_max($Auto3,$Zone,$data['mobile'],3,$data['Type']).' km';?><span>Front Ouest / Front Med / Front Est / Front Pacifique</span></a></td>
		</tr>
		<?}?>
		<tr class="bg_brown"><th colspan='4'>Armement</th></tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'><?echo $Arme_Inf_txt;?></th>
			<td class='dark left'><?echo $Arme_Inf;?></td>
			<th class='dark left bold'><?echo $Arme_Art_txt;?></th>
			<td class='dark left'><?echo $Arme_Art;?></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'><?echo $Arme_AT_txt;?></th>
			<td class='dark left'><?echo $Arme_AT;?></td>
			<th class='dark left bold'><?echo $Arme_AA_txt;?></th>
			<td class='dark left'><?echo $Arme_AA;?></td>
		</tr>
		<?if(!$data['Charge']){?>
		<tr class="bg_brown"><th colspan='4'>Tactique</th></tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'><a href='#' class='popup'>Portée de tir pratique<span>Portée maximale des unités pouvant être ciblées par un bombardement ou une attaque terrestre</span></a></th>
			<td class='dark left'><?echo $data['Portee'].'m';?></td>
			<th class='dark left bold'><a href='#' class='popup'>Allonge maximale de raid<span>Portée maximale des unités pouvant être ciblées par une attaque terrestre</span></a></th>
			<td class='dark left'><?if($data['mobile'] ==7)$data['Vitesse']*=2;elseif($data['mobile'] ==5)$data['Vitesse']=0; echo round($data['Vitesse']*0.05,2).'km';?></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Bonus de Détection</th>
			<td class='dark left'><?echo $data['Detection'];?></td>
			<th class='dark left bold'><a href='#' class='popup'>Couverture DCA<span>Capacité de couvrir les autres unités contre les attaques aériennes</span></a></th>
			<td class='dark left'><?echo $Couv_DCA;?></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'><a href='#' class='popup'>Bonus Tactique<span>Bonus d'initiative et de positionnement (initiative et esquive)</span></a></th>
			<td class='dark left'><?echo $Bonus_Tactique;?></td>
			<th class='dark left bold'><a href='#' class='popup'>Couverture en ligne<span>Capacité de se mettre en ligne et de couvrir les autres unités contre les attaques terrestres</span></a></th>
			<td class='dark left'><?echo $Couv_Ligne;?></td>
		</tr>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Bonus de Tir</th>
			<td class='dark left'><?echo $data['Optics'];?></td>
			<th class='dark left bold'><a href='#' class='popup'>Contre-artillerie<span>Nombre de tirs lors des bombardements et des ripostes de contre-artillerie</span></a></th>
			<td class='dark left'><?if($data['Arme_Art'] >0 and $data['Portee'] >2500) echo $Riposte_txt; else echo "Non";?></td>
		</tr>
		<?}?>
		<tr onmouseover="this.style.background='#FFFFE0'" onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'>Transport de fret</th>
			<td class='dark left'><?echo $Charge;?></td>
			<th class='dark left bold'>Vulnérabilité aux armes légères</th>
			<td class='dark left'><?if($data['Blindage_t'] >0) echo "Non"; else echo "<b>Oui</b>";?></td>
		</tr>
		<?if($Arme_AT_txt =="Anti-tank"){?>
		<tr onmouseover="this.style.background='#FFFFE0'"  onmouseout="this.style.background='#ECDDC1'">
			<th class='dark left bold'><a href='#' class='popup'>Pénétration Anti-Tank<span>Capacité de pénétrer un blindage avec l'arme anti-char</span></a></th>
			<td class='dark left'><span class='badge'><? echo $Arme_AT_Perf.'mm à 500m';?></span></td>
		<?if($data['Portee'] >=1000){?>
			<td class='dark left'><span class='badge'><? echo Get_Perf(1000,$Arme_AT_Cal,$Arme_AT_Perf).'mm à 1000m';?></span></td>
		<?}if($data['Portee'] >=1500){?>
			<td class='dark left'><span class='badge'><? echo Get_Perf(1500,$Arme_AT_Cal,$Arme_AT_Perf).'mm à 1500m';?></span></td>
		<?}?>
		</tr>
		<?}
		if($Arme_Art){?>
        <tr onmouseover="this.style.background='#FFFFE0'"  onmouseout="this.style.background='#ECDDC1'">
            <th class='dark left bold'><a href='#' class='popup'>Pénétration Arme de soutien<span>Capacité de pénétrer un blindage avec l'arme de soutien</span></a></th>
            <td class='dark left'><span class='badge'><? echo Get_Perf($Arme_Art_Range,$Arme_Art_Cal,$Arme_Art_Perf).'mm à '.$Arme_Art_Range.'m';?></span></td>
        <?}
		/*?>
		<tr class="bg_brown"><th colspan='4'>Prérequis</th></tr>
		<tr>
			<td colspan='4'><img src="images/grades/ranks<? echo $data['Pays'].$Grade_min[1]; ?>.png" title="<?echo $Grade_min[0];?>">
			<?if($Rep_min){ echo " ou ";?>
			<img title="<?echo $Rep_min[0];?>" src='images/general<?echo $Rep_min[1];?>.png'>
			<?}?></td>
		</tr>
		*/?>
        </tr>
        <tr class="bg_brown"><th colspan='4'>Actions</th></tr>
        <tr onmouseover="this.style.background='#FFFFE0'"  onmouseout="this.style.background='#ECDDC1'"><td class='specs' colspan='4'><?=$Atk_txt?></td></tr>
		<tr class="bg_brown"><th colspan='4'>Spécificités</th></tr>
		<tr onmouseover="this.style.background='#FFFFE0'"  onmouseout="this.style.background='#ECDDC1'"><td class='specs' colspan='4'><?=$Specs?></td></tr>
		<tr class="bg_brown"><th colspan='4'>Photo</th></tr>
		<tr class="photo"><td colspan='4'><img src="images/cibles/cibles<?echo $ID;?>.jpg"></td></tr>
		<tr class="titre"><th colspan='4'><?echo $data['Nom']; ?></th></tr>
	</table>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="./js/lib/jquery-1.10.2.min.js"><\/script>')</script>
    <script src="./js/lib/bootstrap.min.js"></script>
    <script src="./js/lib/jquery.cookie.js"></script>
    <script src="./js/main.js"></script>
<?
		}
	}
}
else
	echo 'Tsss';
echo '</body></html>';
