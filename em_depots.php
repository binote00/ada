<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];	
if(isset($_SESSION['AccountID']) and $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./menu_em.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens 
	or $OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Officier_Mer or $OfficierEMID ==$Officier_Log or $Admin or $GHQ)
		$Acces_Off=true;
	if($OfficierEMID)
	{
		if(isset($_POST['tri'])){
			$tri = Insec($_POST['tri']);
		}
		else{
			$tri = 'Nom';
		}
		$Coord=GetCoord($Front,$country);
		$Lat_min=$Coord[0];
		$Lat_max=$Coord[1];
		$Long_min=$Coord[2];
		$Long_max=$Coord[3];
		if($country==7)$Mult13=10;
		else$Mult13=5;
		$query="SELECT DISTINCT l.ID,l.Nom,l.Longitude,l.Latitude,l.ValeurStrat,l.Industrie,l.Oil,l.Stock_Essence_87,l.Stock_Essence_100,l.Stock_Essence_1,l.Stock_Munitions_8,l.Stock_Munitions_13,l.Stock_Munitions_20,l.Stock_Munitions_30,
				l.Stock_Munitions_40,l.Stock_Munitions_50,l.Stock_Munitions_60,l.Stock_Munitions_75,l.Stock_Munitions_90,l.Stock_Munitions_105,l.Stock_Munitions_125,l.Stock_Munitions_150,l.Stock_Munitions_200,l.Stock_Munitions_300,l.Stock_Munitions_360,
				l.Stock_Bombes_30,l.Stock_Bombes_50,l.Stock_Bombes_80,l.Stock_Bombes_125,l.Stock_Bombes_250,l.Stock_Bombes_300,l.Stock_Bombes_400,l.Stock_Bombes_500,l.Stock_Bombes_800,l.Stock_Bombes_1000,l.Stock_Bombes_2000,l.Flag,l.Usine_muns,l.boostProd
				FROM Lieu as l WHERE l.ValeurStrat >3 AND l.Flag='$country' AND (l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max')
				ORDER BY $tri DESC";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		if($Acces_Off)
		{
			$Oil_Prod=mysqli_result(mysqli_query($con,"SELECT SUM(Industrie*Oil) FROM Lieu WHERE Flag='$country' AND Flag_Usine='$country' AND ValeurStrat >3 AND Oil >0 AND Industrie >0"),0)*20;
			$Mun_ProdH=mysqli_result(mysqli_query($con,"SELECT SUM(Industrie*Usine_muns) FROM Lieu WHERE Flag='$country' AND Flag_Usine='$country' AND ValeurStrat >3 AND Usine_muns >4 AND Industrie >0"),0);
			$Mun_Prod=mysqli_result(mysqli_query($con,"SELECT SUM(Industrie*Usine_muns) FROM Lieu WHERE Flag='$country' AND Flag_Usine='$country' AND ValeurStrat >3 AND Usine_muns >0 AND Industrie >0"),0);
		}
		mysqli_close($con);
		if($result)
		{
			if($Acces_Off)
				echo "<h2>Production journalière</h2><table class='table table-condensed'><thead><tr><th>87 Octane</th><th>100 Octane</th><th>Diesel</th><th>8mm</th><th>13mm</th><th>20-40mm</th><th>50mm+</th><th>Bombes</th></tr></thead>
				<tr><td><span class='badge'>".($Oil_Prod*5)."</span></td><td><span class='badge'>".$Oil_Prod."</span></td><td><span class='badge'>".$Oil_Prod."</span></td><td><span class='badge'>".($Mun_Prod*10)."</span></td><td><span class='badge'>".($Mun_Prod*$Mult13)."</span></td><td><span class='badge'>".($Mun_Prod*2)."</span></td><td><span class='badge'>".$Mun_ProdH."</span></td><td><span class='badge'>".($Mun_Prod-1)."</span></td></tr></table>
				<a href='#' class='popup'><img src='images/help.png'><span>Les usines endommagées produisent moins. Les usines sous contrôle ennemi ne produisent rien.</span></a>";
				echo '<h2>Etat des Stocks</h2><div class="alert alert-warning"><ul>
																			<li class="text-primary">Stock suffisant pour une cargaison de navire cargo ou de train</li>
																			<li class="text-warning">Stock suffisant pour une cargaison de train, mais insuffisant pour une cargaison de navire cargo</li>
																			<li class="text-danger">Stock insuffisant pour une cargaison de train ou de navire cargo</li>
																			</ul></div>
				<div class="table-responsive"><div style="overflow:auto; width: 100%;">
				<table class="table table-striped table-condensed"><thead>
				<tr><th colspan="3" class="text-center">Dépôt</th><th colspan="3" class="text-center">Carburant</th><th colspan="12" class="text-center">Munitions</th><th colspan="6" class="text-center">Bombes</th><th colspan="5" class="text-center">Autres</th></tr>
				<tr>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Nom"><input class="btn btn-sm btn-default" type="submit" value="Ville"></form></th>
				<th><span class="btn btn-sm btn-default">Action</span></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Industrie"><input class="btn btn-sm btn-default" type="submit" value="Usine"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Essence_87"><input class="btn btn-sm btn-success" type="submit" value="87"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Essence_100"><input class="btn btn-sm btn-success" type="submit" value="100"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Essence_1"><input class="btn btn-sm btn-success" type="submit" value="Diesel"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_8"><input class="btn btn-sm btn-primary" type="submit" value="8mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_13"><input class="btn btn-sm btn-primary" type="submit" value="13mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_20"><input class="btn btn-sm btn-primary" type="submit" value="20mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_30"><input class="btn btn-sm btn-primary" type="submit" value="30mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_40"><input class="btn btn-sm btn-primary" type="submit" value="40mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_50"><input class="btn btn-sm btn-primary" type="submit" value="50mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_60"><input class="btn btn-sm btn-primary" type="submit" value="60mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_75"><input class="btn btn-sm btn-primary" type="submit" value="75mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_90"><input class="btn btn-sm btn-primary" type="submit" value="90mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_105"><input class="btn btn-sm btn-primary" type="submit" value="105mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_125"><input class="btn btn-sm btn-primary" type="submit" value="125mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Munitions_150"><input class="btn btn-sm btn-primary" type="submit" value="150mm"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_50"><input class="btn btn-sm btn-danger" type="submit" value="50kg"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_125"><input class="btn btn-sm btn-danger" type="submit" value="125kg"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_250"><input class="btn btn-sm btn-danger" type="submit" value="250kg"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_500"><input class="btn btn-sm btn-danger" type="submit" value="500kg"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_1000"><input class="btn btn-sm btn-danger" type="submit" value="1T"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_2000"><input class="btn btn-sm btn-danger" type="submit" value="2T"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_300"><input class="btn btn-sm btn-warning" type="submit" value="Charges"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_400"><input class="btn btn-sm btn-warning" type="submit" value="Mines"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_800"><input class="btn btn-sm btn-warning" type="submit" value="Torpilles"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_30"><input class="btn btn-sm btn-warning" type="submit" value="Fusées"></form></th>
				<th><form action="#" method="post"><input type="hidden" name="tri" value="Stock_Bombes_80"><input class="btn btn-sm btn-warning" type="submit" value="Rockets"></form></th>
				</tr></thead>';
				/*<th>Mun 200mm</th>
				<th>Mun 300mm</th>
				<th>Mun 360mm</th>*/
			while($data=mysqli_fetch_array($result))
			{	
				if($data['Flag'] !=$country)
					echo "<tr><th>".$data['Nom']."</th><th colspan='30' style='color:red;'>Zone de combat</th></tr>";
				else
				{
					//<form> => em_depots_flux
					$detail_txt="<form action='index.php?view=em_depots_flux' method='post'><input type='hidden' name='Lieu' value='".$data['ID']."'>
						<input type='Submit' value='Flux' class='btn btn-default btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
					if($data['Usine_muns'] >4 and $data['Industrie'] >0)
						$data['Nom']="<img src='images/ammo_icon.png' title='Grand producteur de munitions de niveau ".$data['Usine_muns']."'>".$data['Nom'];
					elseif($data['Usine_muns'] >0 and $data['Industrie'] >0)
						$data['Nom']="<img src='images/ammo2_icon.png' title='Petit producteur de munitions de niveau ".$data['Usine_muns']."'>".$data['Nom'];
					if($data['Oil'] >0 and $data['Industrie'] >0)
						$data['Nom']="<img src='images/poil_icon.png' title='Producteur de carburant de niveau ".$data['Oil']."'>".$data['Nom'];
					if(!$data['Industrie'] and ($data['Oil'] or $data['Usine_muns']))
						echo "<tr><th style='color:red;'>".$data['Nom'].$detail_txt."</th>";
					else
						echo "<tr><th>".$data['Nom'].$detail_txt."</th>";
					if($Credits >=8 and ($OfficierEMID ==$Commandant or $Admin or $GHQ))
						echo "<td><form action='index.php?view=ground_bruler_depot' method='post' onsubmit=\"return confirm('Etes vous certain de vouloir brûler le dépôt?');\">
						<input type='hidden' name='Div' value='9999'><input type='hidden' name='Cible' value='".$data['ID']."'>
						<img src='images/CT8.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Brûler' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form></td>";
					else
						echo '<td>N/A</td>';
					if(!$data['Industrie'])
						echo '<td>N/A</td>';
					elseif($data['Industrie'] ==100){
					    if($data['boostProd'])
    					    $boosttxt='<br><br><span class="label label-primary" title="Production augmentée">'.$data['boostProd'].' Boost</span>';
					    else
					        $boosttxt='';
						echo "<td><span class='label label-success'>100%</span>".$boosttxt."</td>";
                    }
					elseif($data['Industrie'] <10)
						echo "<td><span class='label label-danger'>".$data['Industrie']."%</span></td>";
					else
						echo "<td><span class='label label-warning'>".$data['Industrie']."%</span></td>";
					if($Acces_Off){
                        $Stock_1_total+=$data['Stock_Essence_1'];
					    $Stock_87_total+=$data['Stock_Essence_87'];
                        $Stock_100_total+=$data['Stock_Essence_100'];
                        $Stock_8_total+=$data['Stock_Munitions_8'];
                        $Stock_13_total+=$data['Stock_Munitions_13'];
                        $Stock_20_total+=$data['Stock_Munitions_20'];
                        $Stock_30_total+=$data['Stock_Munitions_30'];
                        $Stock_40_total+=$data['Stock_Munitions_40'];
                        $Stock_50_total+=$data['Stock_Munitions_50'];
                        $Stock_60_total+=$data['Stock_Munitions_60'];
                        $Stock_75_total+=$data['Stock_Munitions_75'];
                        $Stock_90_total+=$data['Stock_Munitions_90'];
                        $Stock_105_total+=$data['Stock_Munitions_105'];
                        $Stock_125_total+=$data['Stock_Munitions_125'];
                        $Stock_150_total+=$data['Stock_Munitions_150'];
                        if($data['Stock_Essence_87'] <=50000)
                            echo '<td class="text-danger">'.$data['Stock_Essence_87'].'</td>';
						elseif($data['Stock_Essence_87'] <=250000)
                            echo '<td class="text-warning">'.$data['Stock_Essence_87'].'</td>';
						else
                            echo '<td class="text-primary">'.$data['Stock_Essence_87'].'</td>';
                        if($data['Stock_Essence_100'] <=50000)
                            echo '<td class="text-danger">'.$data['Stock_Essence_100'].'</td>';
						elseif($data['Stock_Essence_100'] <=250000)
                            echo '<td class="text-warning">'.$data['Stock_Essence_100'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Essence_100'].'</td>';
                        if($data['Stock_Essence_1'] <=50000)
                            echo '<td class="text-danger">'.$data['Stock_Essence_1'].'</td>';
						elseif($data['Stock_Essence_1'] <=250000)
                            echo '<td class="text-warning">'.$data['Stock_Essence_1'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Essence_1'].'</td>';
                        if($data['Stock_Munitions_8'] <=100000)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_8'].'</td>';
						elseif($data['Stock_Munitions_8'] <=500000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_8'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_8'].'</td>';
                        if($data['Stock_Munitions_13'] <=50000)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_13'].'</td>';
						elseif($data['Stock_Munitions_13'] <=250000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_13'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_13'].'</td>';
                        if($data['Stock_Munitions_20'] <=20000)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_20'].'</td>';
						elseif($data['Stock_Munitions_20'] <=100000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_20'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_20'].'</td>';
                        if($data['Stock_Munitions_30'] <=10000)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_30'].'</td>';
                        elseif($data['Stock_Munitions_30'] <=50000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_30'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_30'].'</td>';
                        if($data['Stock_Munitions_40'] <=5000)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_40'].'</td>';
                        elseif($data['Stock_Munitions_40'] <=25000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_40'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_40'].'</td>';
                        if($data['Stock_Munitions_50'] <=3000)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_50'].'</td>';
                        elseif($data['Stock_Munitions_50'] <=15000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_50'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_50'].'</td>';
                        if($data['Stock_Munitions_60'] <=2000)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_60'].'</td>';
                        elseif($data['Stock_Munitions_60'] <=10000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_60'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_60'].'</td>';
                        if($data['Stock_Munitions_75'] <=1500)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_75'].'</td>';
                        elseif($data['Stock_Munitions_75'] <=7500)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_75'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_75'].'</td>';
                        if($data['Stock_Munitions_90'] <=1000)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_90'].'</td>';
                        elseif($data['Stock_Munitions_90'] <=5000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_90'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_90'].'</td>';
                        if($data['Stock_Munitions_105'] <=750)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_105'].'</td>';
                        elseif($data['Stock_Munitions_105'] <=3750)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_105'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_105'].'</td>';
                        if($data['Stock_Munitions_125'] <=500)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_125'].'</td>';
                        elseif($data['Stock_Munitions_125'] <=2500)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_125'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_125'].'</td>';
                        if($data['Stock_Munitions_150'] <=200)
                            echo '<td class="text-danger">'.$data['Stock_Munitions_150'].'</td>';
                        elseif($data['Stock_Munitions_150'] <=1000)
                            echo '<td class="text-warning">'.$data['Stock_Munitions_150'].'</td>';
                        else
                            echo '<td class="text-primary">'.$data['Stock_Munitions_150'].'</td>';

                        echo '<td>'.$data['Stock_Bombes_50'].'</td>
						<td>'.$data['Stock_Bombes_125'].'</td>
						<td>'.$data['Stock_Bombes_250'].'</td>
						<td>'.$data['Stock_Bombes_500'].'</td>
						<td>'.$data['Stock_Bombes_1000'].'</td>
						<td>'.$data['Stock_Bombes_2000'].'</td>
						<td>'.$data['Stock_Bombes_300'].'</td>
						<td>'.$data['Stock_Bombes_400'].'</td>
						<td>'.$data['Stock_Bombes_800'].'</td>
						<td>'.$data['Stock_Bombes_30'].'</td>
						<td>'.$data['Stock_Bombes_80'].'</td>
						</tr>';
                        /*<td>".$data['Stock_Munitions_200']."</td>
                        <td>".$data['Stock_Munitions_300']."</td>
                        <td>".$data['Stock_Munitions_360']."</td>*/
                        $Stock_b50_total+=$data['Stock_Bombes_50'];
                        $Stock_b125_total+=$data['Stock_Bombes_125'];
                        $Stock_b250_total+=$data['Stock_Bombes_250'];
                        $Stock_b500_total+=$data['Stock_Bombes_500'];
                        $Stock_b1000_total+=$data['Stock_Bombes_1000'];
                        $Stock_b2000_total+=$data['Stock_Bombes_2000'];
                        $Stock_b300_total+=$data['Stock_Bombes_300'];
                        $Stock_b400_total+=$data['Stock_Bombes_400'];
                        $Stock_b800_total+=$data['Stock_Bombes_800'];
                        $Stock_b30_total+=$data['Stock_Bombes_30'];
                        $Stock_b80_total+=$data['Stock_Bombes_80'];
					}
					else
						echo '<td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td>
						<td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td></tr>'; //<td>Inconnu</td><td>Inconnu</td><td>Inconnu</td>
				}
			}
			mysqli_free_result($result);
		}
		echo '<tr><th colspan="3">Total</th><th>'.$Stock_87_total.'</th><th>'.$Stock_100_total.'</th><th>'.$Stock_1_total.'</th>
        <th>'.$Stock_8_total.'</th><th>'.$Stock_13_total.'</th><th>'.$Stock_20_total.'</th><th>'.$Stock_30_total.'</th><th>'.$Stock_40_total.'</th><th>'.$Stock_50_total.'</th><th>'.$Stock_60_total.'</th><th>'.$Stock_75_total.'</th><th>'.$Stock_90_total.'</th><th>'.$Stock_105_total.'</th><th>'.$Stock_125_total.'</th><th>'.$Stock_150_total.'</th>
        <th>'.$Stock_b50_total.'</th><th>'.$Stock_b125_total.'</th><th>'.$Stock_b250_total.'</th><th>'.$Stock_b500_total.'</th><th>'.$Stock_b1000_total.'</th><th>'.$Stock_b2000_total.'</th><th>'.$Stock_b300_total.'</th><th>'.$Stock_b400_total.'</th><th>'.$Stock_b800_total.'</th><th>'.$Stock_b30_total.'</th><th>'.$Stock_b80_total.'</th>
        </tr></table></div></div>';
		echo "<h3><img src='images/ammo_icon.png'><img src='/images/ammo2_icon.png'> Usines de munitions</h3>
		<div class='alert alert-warning'>- Si l'usine n'est pas détruite et qu'elle est sous le contrôle de la nation contrôlant le lieu, le stock de munitions augmente chaque jour en fonction du niveau de l'usine et de son état.
		<br>- Seules les usines de niveau 5 ou supérieur produisent des calibres supérieurs à 40mm ainsi que des bombes, torpilles, charges et autres mines.
		<br>- La production des munitions de 8 et 13mm est multipliée par 5 (10 pour les USA).
		<br>- La production des munitions de 200mm, 300mm, 360mm, des bombes de 1000kg et 2000kg est divisée par 10.</div>
		<h3><img src='images/poil_icon.png'>  Raffineries</h3>
		<div class='alert alert-warning'>- Si l'usine n'est pas détruite et qu'elle est sous le contrôle de la nation contrôlant le lieu, les stocks d'essence et de diesel augmentent chaque jour en fonction du niveau de la raffinerie et de l'état de l'usine.</div>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
