<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$planes=false;
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Officier_Rens or $OfficierEMID ==$Officier_Log or $GHQ or $Admin)
	{
		$i=0;
		$Type_avion=$Type; //Insec($_POST['type']);
		if($Type_avion ==7)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_7'>Attaque</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_production_7'>Attaque</a>";
		if($Type_avion ==2)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_2'>Bombardier</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_production_2'>Bombardier</a>";
		if($Type_avion ==11)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_11'>Bombardier lourd</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_production_11'>Bombardier lourd</a>";
		if($Type_avion ==1)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_1'>Chasse</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_production_1'>Chasse</a>";
		if($Type_avion ==12)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_12'>Chasse embarquée</a>";
		elseif($country ==2 or $country ==7 or $country ==9)
			echo "<a class='btn btn-default' href='index.php?view=em_production_12'>Chasse embarquée</a>";
		if($Type_avion ==4)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_4'>Chasse lourde</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_production_4'>Chasse lourde</a>";
		if($Type_avion ==10)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_10'>Embarqué</a>";
		elseif($country ==2 or $country ==7 or $country ==9)
			echo "<a class='btn btn-default' href='index.php?view=em_production_10'>Embarqué</a>";
		if($Type_avion ==9)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_9'>Pat Mar</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_production_9'>Pat Mar</a>";
		if($Type_avion ==3)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_3'>Reco</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_production_3'>Reco</a>";
		if($Type_avion ==6)
			echo "<a class='btn btn-primary' href='index.php?view=em_production_6'>Transport</a>";
		else
			echo "<a class='btn btn-default' href='index.php?view=em_production_6'>Transport</a>";
		if($GHQ or $Admin)
		{
			$CT_Repa_ori=4;
			$Repa_Nbr=4;
			$Repa_ok=true;
		}
		elseif($OfficierEMID ==$Officier_Log)
		{
            $CT_Repa_ori=8;
			$Repa_Nbr=1;
			$Repa_ok=true;
		}
		if($Trait ==24)
            $CT_Repa_ori-=2;
		$con=dbconnecti();
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$result=mysqli_query($con,"SELECT ID,Nom,Pays,Type,Engagement,Fin_Prod,DATE_FORMAT(`Engagement`,'%d-%m-%Y') AS Engage,DATE_FORMAT(`Fin_Prod`,'%d-%m-%Y') AS Date_Fin,Production,Stock,Usine1,Usine2,Usine3,Reserve,Premium,Rating,DATEDIFF(Fin_Prod,Engagement) as Prod_days
		FROM Avion WHERE Pays='$country' AND Prototype=0 AND Type='$Type_avion' ORDER BY Engagement,Fin_Prod ASC");
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Total=0;
				$Usine1='';
				$Usine2='';
				$Usine3='';
				$Transfer='';
				$Industrie2=100;
				$Industrie3=100;
				$Crash=0;
				$Perdu=0;
				$DCA=0;
				$Abattu=0;
				$Taux_rate=1;
				$Flags=false;
				$ID=$data['ID'];
				$Pays=$data['Pays'];
				$Type=GetAvionType($data['Type']);
				$Avion_img=GetAvionIcon($ID,$Pays,0,0,0,$data['Nom'],true,false,true);
				if($data['Usine1'])
				{
					$Industrie1=GetData("Lieu","ID",$data['Usine1'],"Industrie");
					$Usine1=GetData("Lieu","ID",$data['Usine1'],"Nom")."<br>".$Industrie1."%";
					$Taux=$Industrie1;
				}
				if($data['Usine2'])
				{
					$Industrie2=GetData("Lieu","ID",$data['Usine2'],"Industrie");
					$Usine2=GetData("Lieu","ID",$data['Usine2'],"Nom")."<br>".$Industrie2."%";
					$Taux+=$Industrie2;
					$Taux_rate++;
				}
				if($data['Usine3'])
				{
					$Industrie3=GetData("Lieu","ID",$data['Usine3'],"Industrie");
					$Usine3=GetData("Lieu","ID",$data['Usine3'],"Nom")."<br>".$Industrie3."%";
					$Taux+=$Industrie3;
					$Taux_rate++;
				}
				//$result2=mysqli_query($con,"SELECT e.Date,l.Nom,e.Avion_Nbr FROM Event_Historique as e,Lieu as l WHERE e.Type=56 AND e.Avion='$ID' AND e.Lieu=l.ID");
				$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='$ID' AND PVP=1"),0);
				$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='$ID'"),0);
				$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='$ID' AND Etat=1"),0);
				$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='$ID' AND Etat=1"),0);
				$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='$ID' AND Etat=1"),0);
				$con4=dbconnecti(4);
				$Perdu=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='$ID' AND Avion_Nbr >0"),0);
				mysqli_close($con4);
				/*if($result2)
				{
					while($data2=mysqli_fetch_array($result2,MYSQLI_NUM))
					{
						$Transfer.="Usine ".$data2[2]." : ".$data2[1]." à partir du ".$data2[0]."<br>";
					}
					mysqli_free_result($result2);
					unset($data2);
				}*/
				$i++;
				$Total=$DCA+$Abattu+$Perdu;
				$Service=$Service1+$Service2+$Service3;
				$Reste=floor($data['Stock']-$Total-$Service+$data['Reserve']);
				if($Reste+$Service >$data['Stock'])$Reste=floor($data['Stock']-$Service);
				if($Reste<1)$Reste=0;
				$Repa=floor($data['Stock']-$DCA-$Service-$Reste);
				if($data['Rating'] >$CT_Repa_ori)
				    $CT_Repa=$data['Rating'];
				else
                    $CT_Repa=$CT_Repa_ori;
				if($Repa_ok and $Repa>=$Repa_Nbr and $Credits>=$CT_Repa)
					$Repa_txt="<form action='em_prod_repa.php' method='post'><input type='hidden' name='avion' value='".$ID."'><input type='hidden' name='CT' value='".$CT_Repa."'><input type='hidden' name='Type' value='".$Type_avion."'><img src='/images/CT".$CT_Repa.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Réparer' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				else
					$Repa_txt='';
				if($Total >$data['Stock'])
				{
					$Total="<span class='text-danger'>".$Total."</span>";
					$Repa="<span class='text-danger'>0</span>";
				}
				if($Reste<1)$Reste="<span class='text-danger'>0</span>";
				if($Repa<0)$Repa="<span class='text-danger'>0</span>";
				$Rate="<span class='text-danger'>0</span>";
				if($data['Lease'])$Transfer.="<img src='images/lendlease.png' title='Lend Lease requis ".$Lease." points' alt='Lend Lease requis ".$Lease." points'>";
				if($data['Premium'])$Transfer.="<div class='i-flex premium20'></div>";
				/*$Prod_rate=$data['Production']/$data['Prod_days'];
				if($Admin)
					$Prod_txt=$data['Production']." (".$Prod_rate.")";
				else*/
					$Prod_txt=$data['Production'];
				if($Admin)
				{
					$CT_Avion1=12;
					$Kaput=false;
					if(($DCA + $Abattu + $Perdu + $Service1 + $Service2 + $Service3 - $data['Reserve']) >=$data['Stock'])
						$Kaput=true;					
					if($Kaput)
						$CT_Avion1=50;
					elseif($data['Fin_Prod'] <$Date_Campagne)
						$CT_Avion1=12;
					else
					{
						if($Industrie1 <10)
							$CT_Avion1=50;
						elseif($Industrie1 <25)
							$CT_Avion1*=4;
						elseif($Industrie1 <50)
							$CT_Avion1*=2;
						if($Usine2)
						{
							if($Industrie2 <10)
								$CT_Avion1=50;
							elseif($Industrie2 <25)
								$CT_Avion1*=4;
							elseif($Industrie2 <50)
								$CT_Avion1*=2;
						}
						if($Usine3)
						{
							if($Industrie3 <10)
								$CT_Avion1=50;
							elseif($Industrie3 <25)
								$CT_Avion1*=4;
							elseif($Industrie3 <50)
								$CT_Avion1*=2;
						}
					}
					if($CT_Avion1 >50)$CT_Avion1=50;
					$Transfer.="<img src='/images/CT".$CT_Avion1.".png' title='Montant en Crédits Temps pour la commande'>";
				}
				if($data['Fin_Prod'] <$Date_Campagne)
				{
					$planes.="<tr><td>".$i."</td><td>".$Avion_img."</td><td class='hidden-lg-down'><img src='".$Pays."20.gif'></td><td class='hidden-lg-down'>".$Type."</td><td>".$data['Engage']."</td><td class='text-danger'>".$data['Date_Fin']."</td><td>".$Prod_txt."</td><td>".$Rate."</td><th>".floor($data['Stock'])."</th>
					<td class='hidden-lg-down'>".$Abattu."</td><td class='hidden-lg-down'>".$DCA."</td><td class='hidden-lg-down'>".$Crash."</td><td class='hidden-lg-down'>".$Perdu."</td><td class='text-danger'>".$Total."</td><td>".$data['Reserve']."/".$Repa.$Repa_txt."</td><th class='text-info'>".$Service."</th><td class='text-success'>".$Reste."</td>
					<td>".$Usine1."</td><td>".$Usine2."</td><td>".$Usine3."</td><td align='left'>".$Transfer."</td></tr>";
				}
				elseif($data['Engagement'] >$Date_Campagne)
				{
					$planes.="<tr style='color: grey;'><td>".$i."</td><td>".$Avion_img."</td><td class='hidden-lg-down'><img src='".$Pays."20.gif'></td><td class='hidden-lg-down'>".$Type."</td><td><span class='text-danger'>".$data['Engage']."</span></td><td>".$data['Date_Fin']."</td><td>".$Prod_txt."</td><td>".$Rate."</td><th>".floor($data['Stock'])."</th>
					<td class='hidden-lg-down'>".$Abattu."</td><td class='hidden-lg-down'>".$DCA."</td><td class='hidden-lg-down'>".$Crash."</td><td class='hidden-lg-down'>".$Perdu."</td><td class='text-danger'>".$Total."</td><td>".$data['Reserve']."/".$Repa.$Repa_txt."</td><th class='text-info'>".$Service."</th><td class='text-success'>".$Reste."</td>
					<td>".$Usine1."</td><td>".$Usine2."</td><td>".$Usine3."</td><td align='left'>".$Transfer."</td></tr>";
				}
				else
				{
					if($Industrie1 ==0 or $Industrie2 ==0 or $Industrie3 ==0)
						$Reste="<span class='text-danger'>0</span>";
					else
						$Rate=round($data['Production']/$data['Prod_days']*($Taux/$Taux_rate)/100,2);
					if($data['Engagement'] >$Date_Campagne)$data['Engagement']="<span class='text-danger'>".$data['Engagement']."</span>";
					$planes.="<tr><td>".$i."</td><td>".$Avion_img."</td><td class='hidden-lg-down'><img src='".$Pays."20.gif'></td><td class='hidden-lg-down'>".$Type."</td><td>".$data['Engage']."</td><td>".$data['Date_Fin']."</td><td>".$Prod_txt."</td><td>".$Rate."</td><th>".floor($data['Stock'])."</th>
					<td class='hidden-lg-down'>".$Abattu."</td><td class='hidden-lg-down'>".$DCA."</td><td class='hidden-lg-down'>".$Crash."</td><td class='hidden-lg-down'>".$Perdu."</td><td class='text-danger'>".$Total."</td><td>".$data['Reserve']."/".$Repa.$Repa_txt."</td></td><th class='text-info'>".$Service."</th><td class='text-success'>".$Reste."</td>
					<td>".$Usine1."</td><td>".$Usine2."</td><td>".$Usine3."</td><td align='left'>".$Transfer."</td></tr>";
				}
			}
			mysqli_free_result($result);
			unset($data);
		}
		else
			echo "<b>Désolé, aucun avion</b>";
		//echo "<a class='btn btn-default' title='Retour au menu' href='index.php?view=em_production0'>Retour au menu</a>
		echo "<h2>Production aéronautique</h2>";
		if($Type)
		{
			echo "<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Les avions apparaissant en grisé dans cette liste ne sont pas encore produits par nos usines</div>
			<div style='overflow:auto;'><table class='table table-striped table-condensed table-responsive'>
			<thead><tr>
				<th>N°</th>
				<th>Avion</th>
				<th class='hidden-lg-down'>Pays</th>
				<th class='hidden-lg-down'>Type</th>
				<th>Mise en service</th>
				<th>Fin de prod</th>
				<th><a href='#' class='popup'>Total<span>Production totale</span></a></th>
				<th><a href='#' class='popup'>Prod<span>Production quotidienne</span></a></th>
				<th><a href='#' class='popup'>Stock<span>Stock actuel</span></a></th>
				<th class='hidden-lg-down'><a href='#' class='popup'>Abattus<span>Abattus en vol</span></a></th>
				<th class='hidden-lg-down'><a href='#' class='popup'>DCA<span>Abattus par la DCA</span></a></th>
				<th class='hidden-lg-down'>Crashs</th>
				<th class='hidden-lg-down'>Autres</th>
				<th><a href='#' class='popup'>Pertes<span>Pertes totales</span></a></th>
				<th><a href='#' class='popup'>Récup<span>Avions récupérés/Avions récupérables</span></a></th>
				<th><a href='#' class='popup'>En Service<span>Avions déjà affectés à vos escadrilles</span></a></th>
				<th><a href='#' class='popup'>Réserve<span>Avions disponibles pouvant être affectés à vos escadrilles</span></a></th>
				<th>Usine 1</th>
				<th>Usine 2</th>
				<th>Usine 3</th>
				<th>Infos</th></tr></thead>".$planes."</table></div>";
		}
	}
	else
		PrintNoAccess($country,1,4,6);
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>