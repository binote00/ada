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
	if($OfficierEMID ==$Commandant or $Admin or $GHQ)
		$Acces_Off=true;
	if($Acces_Off)
	{
		if(isset($_POST['tri'])){
			$tri = Insec($_POST['tri']);
		}
		else{
			$tri = 'Nom';
		}
		if(isset($_POST['order'])){
		    if($_POST['order'] =='ASC')
                $order = 'DESC';
		    else
		        $order = 'ASC';
        }
        else{
		    $order = 'DESC';
        }
		$Coord=GetCoord($Front,$country);
		$Lat_min=$Coord[0];
		$Lat_max=$Coord[1];
		$Long_min=$Coord[2];
		$Long_max=$Coord[3];
		if($country==7)
		    $Mult13=10;
		else
		    $Mult13=5;
		$query="SELECT DISTINCT l.ID,l.Nom,l.Longitude,l.Latitude,l.ValeurStrat,l.Oil,l.Stock_Essence_87,l.Stock_Essence_100,l.Stock_Essence_1,l.Stock_Munitions_8,l.Stock_Munitions_13,l.Stock_Munitions_20,l.Stock_Munitions_30,
				l.Stock_Munitions_40,l.Stock_Munitions_50,l.Stock_Munitions_60,l.Stock_Munitions_75,l.Stock_Munitions_90,l.Stock_Munitions_105,l.Stock_Munitions_125,l.Stock_Munitions_150,l.Stock_Munitions_200,l.Stock_Munitions_300,l.Stock_Munitions_360,
				l.Stock_Bombes_30,l.Stock_Bombes_50,l.Stock_Bombes_80,l.Stock_Bombes_125,l.Stock_Bombes_250,l.Stock_Bombes_300,l.Stock_Bombes_400,l.Stock_Bombes_500,l.Stock_Bombes_800,l.Stock_Bombes_1000,l.Stock_Bombes_2000,l.Usine_muns,l.Flag
				FROM Lieu as l WHERE l.ValeurStrat >3 AND Flag!=$country AND (l.Latitude BETWEEN '$Lat_min' AND '$Lat_max') AND (l.Longitude BETWEEN '$Long_min' AND '$Long_max')
				ORDER BY $tri $order";
		$con=dbconnecti();
        $resultr=mysqli_query($con,"SELECT Pays_ID,ev1,ev9,ev10 FROM Pays WHERE Front='$Front'");
		$result=mysqli_query($con,$query);
		mysqli_close($con);
		if($resultr){
			while($datar=mysqli_fetch_array($resultr)){
				$rens_level[$datar['Pays_ID']]=$datar['ev1'];
				$falsif_level[$datar['Pays_ID']]=$datar['ev9'];
                $ce_level[$datar['Pays_ID']]=$datar['ev10'];
			}
		}
		if($result){
            $inconnu='<td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td>
						<td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td><td>Inconnu</td></tr>';
			echo '<h2>Etat des Stocks</h2>
			<div class="table-responsive"><div style="overflow:auto; width: 100%;">
			<table class="table table-striped table-condensed"><thead>
			<tr><th colspan="3" class="text-center">Dépôt</th><th colspan="3" class="text-center">Carburant</th><th colspan="12" class="text-center">Munitions</th><th colspan="6" class="text-center">Bombes</th><th colspan="5" class="text-center">Autres</th></tr>
			<tr>
			<th><form action="#" method="post"><input type="hidden" name="tri" value="Nom"><input type="hidden" name="order" value="'.$order.'"><input class="btn btn-sm btn-default" type="submit" value="Ville"></form></th>
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
			while($data=mysqli_fetch_array($result))
			{	
				if($rens_level[$country] >$ce_level[$data['Flag']])
				{
					if($data['Usine_muns'] >4 and $data['Industrie'] >0)
						$data['Nom']="<img src='/images/ammo_icon.png' title='Grand producteur de munitions de niveau ".$data['Usine_muns']."'>".$data['Nom'];
					elseif($data['Usine_muns'] >0 and $data['Industrie'] >0)
						$data['Nom']="<img src='/images/ammo2_icon.png' title='Petit producteur de munitions de niveau ".$data['Usine_muns']."'>".$data['Nom'];
					if($data['Oil'] >0 and $data['Industrie'] >0)
						$data['Nom']="<img src='/images/poil_icon.png' title='Producteur de carburant de niveau ".$data['Oil']."'>".$data['Nom'];
					echo '<tr><th>'.$data['Nom'].'</th>';
					if($rens_level[$country] ==50 and !$ce_level[$data['Flag']]){
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

                        echo "<td>".$data['Stock_Bombes_50']."</td>
						<td>".$data['Stock_Bombes_125']."</td>
						<td>".$data['Stock_Bombes_250']."</td>
						<td>".$data['Stock_Bombes_500']."</td>
						<td>".$data['Stock_Bombes_1000']."</td>
						<td>".$data['Stock_Bombes_2000']."</td>
						<td>".$data['Stock_Bombes_300']."</td>
						<td>".$data['Stock_Bombes_400']."</td>
						<td>".$data['Stock_Bombes_800']."</td>
						<td>".$data['Stock_Bombes_30']."</td>
						<td>".$data['Stock_Bombes_80']."</td>
						</tr>";
					}
                    elseif($rens_level[$country]-$ce_level[$data['Flag']] >=40){
                        $total_carbu = $data['Stock_Essence_87'] + $data['Stock_Essence_100'] + $data['Stock_Essence_1'];
                        if($total_carbu)
                            echo '<td colspan="3">'.$total_carbu.'</td>';
                        else
                            echo '<td colspan="3" class="text-danger">Absent</td>';
                        $total_muns = $data['Stock_Munitions_8'] + $data['Stock_Munitions_13'] + $data['Stock_Munitions_20'] + $data['Stock_Munitions_30'] + $data['Stock_Munitions_40'] + $data['Stock_Munitions_50'] + $data['Stock_Munitions_60'] + $data['Stock_Munitions_75'] + $data['Stock_Munitions_90'] + $data['Stock_Munitions_105'] + $data['Stock_Munitions_125'] + $data['Stock_Munitions_150'];
                        if($total_muns)
                            echo '<td colspan="12">'.$total_muns.'</td>';
                        else
                            echo '<td colspan="12" class="text-danger">Absent</td>';
                        if($data['Stock_Bombes_50'] + $data['Stock_Bombes_80'] + $data['Stock_Bombes_125'] + $data['Stock_Bombes_250'] + $data['Stock_Bombes_300'] + $data['Stock_Bombes_400'] + $data['Stock_Bombes_500'] + $data['Stock_Bombes_800'] + $data['Stock_Bombes_1000'] + $data['Stock_Bombes_2000'])
                            echo '<td colspan="11">Présent</td>';
                        else
                            echo '<td colspan="11" class="text-danger">Absent</td>';
                    }
                    elseif($rens_level[$country]-$ce_level[$data['Flag']] >=30){
					    $total_carbu = $data['Stock_Essence_87'] + $data['Stock_Essence_100'] + $data['Stock_Essence_1'];
                        if($total_carbu)
                            echo '<td colspan="3">'.$total_carbu.'</td>';
                        else
                            echo '<td colspan="3" class="text-danger">Absent</td>';
                        if($data['Stock_Munitions_8'] + $data['Stock_Munitions_13'] + $data['Stock_Munitions_20'] + $data['Stock_Munitions_30'] + $data['Stock_Munitions_40'] + $data['Stock_Munitions_50'] + $data['Stock_Munitions_60'] + $data['Stock_Munitions_75'] + $data['Stock_Munitions_90'] + $data['Stock_Munitions_105'] + $data['Stock_Munitions_125'] + $data['Stock_Munitions_150'])
                            echo '<td colspan="12">Présent</td>';
                        else
                            echo '<td colspan="12" class="text-danger">Absent</td>';
                        if($data['Stock_Bombes_50'] + $data['Stock_Bombes_80'] + $data['Stock_Bombes_125'] + $data['Stock_Bombes_250'] + $data['Stock_Bombes_300'] + $data['Stock_Bombes_400'] + $data['Stock_Bombes_500'] + $data['Stock_Bombes_800'] + $data['Stock_Bombes_1000'] + $data['Stock_Bombes_2000'])
                            echo '<td colspan="11">Présent</td>';
                        else
                            echo '<td colspan="11" class="text-danger">Absent</td>';
                    }
					elseif($rens_level[$country]-$ce_level[$data['Flag']] >=20){
                        if($data['Stock_Essence_87'] + $data['Stock_Essence_100'] + $data['Stock_Essence_1'])
                            echo '<td colspan="3">Présent</td>';
                        else
                            echo '<td colspan="3" class="text-danger">Absent</td>';
                        if($data['Stock_Munitions_8'] + $data['Stock_Munitions_13'] + $data['Stock_Munitions_20'] + $data['Stock_Munitions_30'] + $data['Stock_Munitions_40'] + $data['Stock_Munitions_50'] + $data['Stock_Munitions_60'] + $data['Stock_Munitions_75'] + $data['Stock_Munitions_90'] + $data['Stock_Munitions_105'] + $data['Stock_Munitions_125'] + $data['Stock_Munitions_150'])
                            echo '<td colspan="12">Présent</td>';
                        else
                            echo '<td colspan="12" class="text-danger">Absent</td>';
                        if($data['Stock_Bombes_50'] + $data['Stock_Bombes_80'] + $data['Stock_Bombes_125'] + $data['Stock_Bombes_250'] + $data['Stock_Bombes_300'] + $data['Stock_Bombes_400'] + $data['Stock_Bombes_500'] + $data['Stock_Bombes_800'] + $data['Stock_Bombes_1000'] + $data['Stock_Bombes_2000'])
                            echo '<td colspan="11">Présent</td>';
                        else
                            echo '<td colspan="11" class="text-danger">Absent</td>';
                    }
                    else
						echo $inconnu;
				}else
					echo '<tr><td>'.$data['Nom'].'</td><td colspan="50">Le niveau de renseignement est insuffisant</td></tr>';
			}
			mysqli_free_result($result);
		}
		echo '</table></div></div>';
		echo "<h3><img src='/images/ammo_icon.png'><img src='/images/ammo2_icon.png'> Usines de munitions</h3>
		<h3><img src='/images/poil_icon.png'>  Raffineries</h3>";
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
