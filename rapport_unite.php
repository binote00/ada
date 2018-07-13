<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Unite=Insec($_GET['unite']);
	$country=$_SESSION['country'];
	$con=dbconnecti(2);
	$resultg=mysqli_query($con,"SELECT Gain,Mode,DATE_FORMAT(Date,'%d-%m-%Y:%Hh%i') as Jour FROM gains_unit WHERE UnitID='$Unite' ORDER BY ID DESC");
	mysqli_close($con);
	if($resultg)
	{
		while($data=mysqli_fetch_array($resultg,MYSQLI_ASSOC))
		{
			if($data['Mode'] ==1)
				$Mode_txt='Mission';
			elseif($data['Mode'] ==2)
				$Mode_txt='Echec de mission';
			elseif($data['Mode'] ==3)
				$Mode_txt='Avion crashé au décollage';
			elseif($data['Mode'] ==4)
				$Mode_txt='Avion abattu';
			elseif($data['Mode'] ==5)
				$Mode_txt='Avion abattu par la DCA';
			elseif($data['Mode'] ==6)
				$Mode_txt='Mission EM';
			elseif($data['Mode'] ==7)
				$Mode_txt='Mission EM réussie';
			elseif($data['Mode'] ==8 or $data['Mode'] ==13)
				$Mode_txt='Demande de mission effectuée';
			elseif($data['Mode'] ==9)
				$Mode_txt='Escorte';
			elseif($data['Mode'] ==10)
				$Mode_txt='Pilote MIA';
			elseif($data['Mode'] ==11 or $data['Mode'] ==14)
				$Mode_txt='Mission de reco';
			elseif($data['Mode'] ==12)
				$Mode_txt='Mission navale';
			elseif($data['Mode'] ==13)
				$Mode_txt='Demande de mission effectuée';
			elseif($data['Mode'] ==15)
				$Mode_txt='Mission de ravitaillement';
			elseif($data['Mode'] ==110)
				$Mode_txt='Bombardement stratégique IA';
			elseif($data['Mode'] ==112)
				$Mode_txt='Bombardement tactique IA';
			elseif($data['Mode'] ==113)
				$Mode_txt='Reco tactique IA';
			elseif($data['Mode'] ==114)
				$Mode_txt='Reco stratégique IA';
			elseif($data['Mode'] ==115)
				$Mode_txt='Ravitaillement IA';
			elseif($data['Mode'] ==116)
				$Mode_txt='Parachutage IA';
			elseif($data['Mode'] ==129)
				$Mode_txt='Patrouille ASM IA';
			$output.="<tr><td>".$data['Jour']."</td><td>".$Mode_txt."</td><td>".$data['Gain']."</td></tr>";
		}
		mysqli_free_result($resultg);
	}
	if($output)$reput_txt="<h2>Gain de réputation</h2><table class='table table-striped'><thead><tr><th>Date</th><th>Catégorie</th><th>Gain</th></tr></thead>".$output."</table>";
	$con=dbconnecti();
	$Unite=mysqli_real_escape_string($con,$Unite);
	$Admin=mysqli_result(mysqli_query($con,"SELECT Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
	$Front=mysqli_result(mysqli_query($con,"SELECT Front FROM Officier_em WHERE ID='$OfficierEMID'"),0);
	$Pays=mysqli_result(mysqli_query($con,"SELECT Pays FROM Unit WHERE ID='$Unite'"),0);
	$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
	if($result2)
	{
		while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
		{
			$Commandant=$data['Commandant'];
			$Officier_Adjoint=$data['Adjoint_EM'];
			$Officier_EM=$data['Officier_EM'];
			$Officier_Rens=$data['Officier_Rens'];
		}
		mysqli_free_result($result2);
	}
	if($country ==$Pays and ($Admin ==1 or $OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens))
	{
	    if($Admin or $OfficierEMID ==$Commandant)$boss=true;
		$result_unit=mysqli_query($con,"SELECT Nom,Base,Commandant,Officier_Technique,Officier_Adjoint,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr,Stock_Essence_87,Stock_Essence_100,Stock_Munitions_8,Stock_Munitions_13,Stock_Munitions_20,Porte_avions FROM Unit WHERE ID='$Unite'");
		if($result_unit)
		{
			while($Data=mysqli_fetch_array($result_unit,MYSQLI_ASSOC)) 
			{
				$Unite_Nom=$Data['Nom'];
				$Unite_Base=$Data['Base'];
				$Commandant=$Data['Commandant'];
				$Officier_Technique=$Data['Officier_Technique'];
				$Officier_Adjoint=$Data['Officier_Adjoint'];
				$Cdt='<img src="images/persos/general0.png"><br><span class="badge">Poste vacant</span>';
				$OA='<img src="images/persos/general0.png"><br><span class="badge">Poste vacant</span>';
				$OT='<img src="images/persos/general0.png"><br><span class="badge">Poste vacant</span>';
				$result_pil=mysqli_query($con,"SELECT ID,Pays,Nom,Avancement,Photo,Photo_Premium,DATE_FORMAT(Credits_date,'%d-%m-%Y') AS Activite,Credits_date BETWEEN CURDATE() + INTERVAL 7 DAY AND CURDATE() AS Inactif FROM Pilote WHERE Unit='$Unite' ORDER BY Avancement DESC");
				if($result_pil)
				{
					while($data_pil=mysqli_fetch_array($result_pil,MYSQLI_ASSOC))
					{
						$Grade=GetAvancement($data_pil['Avancement'],$data_pil['Pays']);
						if($data_pil['Photo_Premium'] ==1)
							$img_pilote="uploads/Pilote/".$data_pil['ID']."_photo.jpg";
						else
							$img_pilote="images/persos/pilote".$data_pil['Pays'].$data_pil['Photo'].".jpg";
						if($data_pil['Inactif']){
						    $inactif_txt='<br><span class="text-danger">Inactif</span>';
                        }
						else
						    $inactif_txt='';
						if($boss)
                            $inactif_txt.="<form action='em_gestioncdt1.php' method='post'><input type='hidden' name='Mutation_Cdt' value=".$data_pil['ID'].">
                            <img src='/images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Virer' class='btn btn-sm btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
                        $pilote_id="<div class='panel panel-default text-center'>
										<div class='panel-body'>
                                            ".$data_pil['Nom']."<br><img title='".$Grade[0]."' src='images/grades/grades".$data_pil['Pays'].$Grade[1].".png'>
										    <br><img src='".$img_pilote."'>
										    <br>".$data_pil['Activite'].$inactif_txt."
										</div>
									</div>";
						if($data_pil['ID'] ==$Commandant)
							$Cdt=$pilote_id;
						elseif($data_pil['ID'] ==$Officier_Adjoint)
							$OA=$pilote_id;
						elseif($data_pil['ID'] ==$Officier_Technique)
							$OT=$pilote_id;
						$pilotes.=$pilote_id;
					}
					mysqli_free_result($result_pil);
				}
				if($Data['Porte_avions'] >0)
				{
					$result=mysqli_query($con,"SELECT a.ID,a.Nom,a.Calibre,a.Degats,a.Multi,a.Portee 
					FROM Armes as a, Cible as r WHERE r.ID='".$Data['Porte_avions']."' AND (a.ID=r.Arme_AA OR a.ID=r.Arme_AA2 OR a.ID=r.Arme_AA3)");
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$dg_max=round($data['Degats']*$data['Multi']);
							$dca_pieces.="<tr><td><img src='images/aa".$data['ID'].".png'><br>".$data['Nom']."</td><td>".round($data['Calibre'])."mm</td>
							<td>".$data['Degats']."-".$dg_max."</td><td>".$data['Portee']."m</td></tr>";
						}
						mysqli_free_result($result);
					}
					$dca_pieces="<h2>Porte-avions ".GetData("Cible","ID",$Data['Porte_avions'],"Nom")."</h2><p><img src='/images/vehicules/vehicule".$Data['Porte_avions'].".gif'></p>
					<table class='table'><thead><tr><th>Nom</th><th>Calibre</th><th>Dégats Max</th><th>Plafond</th></tr></thead>".$dca_pieces."</table>";
				}
				else
				{
					//$dca_res=mysqli_query($con,"SELECT DCA_ID,DCA_Nbr,DCA_Exp,Alt FROM Flak WHERE Unit='$Unite' AND Lieu='$Base'");
					$dca_res=mysqli_query($con,"SELECT DCA_ID,DCA_Nbr,DCA_Exp,Alt,Unit FROM Flak WHERE Lieu='$Base'");
					if($dca_res)
					{
						while($data_flak=mysqli_fetch_array($dca_res,MYSQLI_ASSOC))
						{
							$DCA_ID=$data_flak['DCA_ID'];
							$DCA_Nbr=$data_flak['DCA_Nbr'];
							$DCA_Exp=floor($data_flak['DCA_Exp']);
							$DCA_Alt=$data_flak['Alt'];
							$DCA_Nom=GetData("Armes","ID",$DCA_ID,"Nom");
							if($data_flak['Unit'] == $Unite)
								$dca_pieces_items.="<tr><td><img src='images/aa".$DCA_ID.".png' title='".$DCA_Nom."'><td>".$DCA_Nbr."</td><td>".$DCA_Alt."m</td><td>".$DCA_Exp."</td></tr>";
							else
								$dca_pieces_others_items.="<tr><td><img src='images/aa".$DCA_ID.".png' title='".$DCA_Nom."'><td>".$DCA_Nbr."</td><td>".$DCA_Alt."m</td><td>".$DCA_Exp."</td></tr>";
						}
						mysqli_free_result($dca_res);
					}
					if($dca_pieces_items){
                        $dca_pieces="<h2>Composition de la défense anti-aérienne de l'unité</h2><table class='table table-striped'>
						<thead><tr><th>Type</th><th>Nombre</th><th>Altitude</th><th>Expérience</th></tr></thead>".$dca_pieces_items."</table>";
                    }
                    if($dca_pieces_others_items){
                        $dca_pieces_others="<h2>Défense anti-aérienne des autres unités occupant l'aérodrome</h2><table class='table table-striped'>
						<thead><tr><th>Type</th><th>Nombre</th><th>Altitude</th><th>Expérience</th></tr></thead>".$dca_pieces_others_items."</table>";

                    }
				}
				if($pilotes)
					$pilotes_list="<fieldset><h2>Pilotes</h2><div class='row'>".$pilotes."</div></fieldset>";
				$titre=$Unite_Nom;
				$Sqn=GetSqn($country);
				$mes.="<div class='row'><div class='col-md-4 col-md-offset-4 text-center'>".Afficher_Icone($Unite,$Pays,$Unite_Nom,1)."</div></div>
				<h2>Staff</h2>
				<div class='row text-center'>
					<div class='col-md-4'><h3>".GetStaff($country,1)."</h3>".$Cdt."</div>
					<div class='col-md-4'><h3>".GetStaff($country,2)."</h3>".$OA."</div>
					<div class='col-md-4'><h3>".GetStaff($country,3)."</h3>".$OT."</div>
				</div>
				<fieldset><h2>Avions</h2>
				<div class='row'>
					<div class='col-md-4'><h3>".$Sqn." 1</h3><b>".$Data['Avion1_Nbr']."</b> ".GetAvionIcon($Data['Avion1'],$Pays,0,$Unite,$Front)."</div>
					<div class='col-md-4'><h3>".$Sqn." 2</h3><b>".$Data['Avion2_Nbr']."</b> ".GetAvionIcon($Data['Avion2'],$Pays,0,$Unite,$Front)."</div>
					<div class='col-md-4'><h3>".$Sqn." 3</h3><b>".$Data['Avion3_Nbr']."</b> ".GetAvionIcon($Data['Avion3'],$Pays,0,$Unite,$Front)."</div>
				</div></fieldset>
				<fieldset><h2>Stocks</h2>
				<div class='row'>
					<div class='col-md-2'><h3>87 Octane</h3>".$Data['Stock_Essence_87']."</div>
					<div class='col-md-2'><h3>100 Octane</h3>".$Data['Stock_Essence_100']."</div>
					<div class='col-md-2'><h3>8mm</h3>".$Data['Stock_Munitions_8']."</div>
					<div class='col-md-2'><h3>13mm</h3>".$Data['Stock_Munitions_13']."</div>
					<div class='col-md-2'><h3>20mm</h3>".$Data['Stock_Munitions_20']."</div>
				</div></fieldset>
				".$pilotes_list.$dca_pieces.$dca_pieces_others.$reput_txt;
                include_once('./index.php');
			}
		}
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
    echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';